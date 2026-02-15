# Milestone 73: Rocket & SSLCommerz Integration

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P8-M73-v1.0 |
| **Milestone** | 73 - Rocket & SSLCommerz Integration |
| **Phase** | Phase 8: Operations - Payment & Logistics |
| **Days** | 471-480 |
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

**Implement DBBL Rocket mobile banking payment and SSLCommerz multi-channel payment gateway supporting credit/debit cards (Visa, MasterCard, Amex), bank transfers, and mobile wallets with 3D Secure authentication and IPN webhook handling.**

### 1.2 Scope Summary

| Area | Included |
|------|----------|
| Rocket Integration | DBBL Rocket payment API |
| SSLCommerz Session | Payment session initialization |
| Card Payments | Visa, MasterCard, Amex processing |
| 3D Secure | Authentication flow handling |
| IPN Webhooks | Instant Payment Notification |
| Transaction Validation | Hash verification, amount checking |
| Multi-Gateway Selector | Unified payment method UI |
| Saved Cards | Tokenization for returning customers |

### 1.3 Prerequisites

- Milestone 71 (bKash) and Milestone 72 (Nagad) completed
- SSLCommerz merchant account approved
- DBBL Rocket merchant integration approved
- SSL certificate for IPN endpoints
- PCI DSS compliance understanding

---

## 2. Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| O1 | Rocket payment integration | 100% API coverage |
| O2 | SSLCommerz card payments | Support all major cards |
| O3 | 3D Secure authentication | 100% 3DS transactions |
| O4 | IPN reliability | 99.9% delivery processing |
| O5 | Payment success rate | > 95% |
| O6 | Response time | < 3s for session init |
| O7 | Test coverage | > 80% code coverage |

---

## 3. Key Deliverables

| # | Deliverable | Owner | Format | Due Day |
|---|-------------|-------|--------|---------|
| D1 | Rocket provider configuration | Dev 1 | Python/Odoo | Day 472 |
| D2 | Rocket payment flow | Dev 1 | Python | Day 473 |
| D3 | SSLCommerz provider model | Dev 1 | Python | Day 474 |
| D4 | SSLCommerz session API | Dev 1 | Python | Day 475 |
| D5 | IPN webhook handler | Dev 2 | Python | Day 475 |
| D6 | Transaction validation service | Dev 2 | Python | Day 476 |
| D7 | 3D Secure handling | Dev 2 | Python | Day 477 |
| D8 | Multi-gateway payment selector | Dev 3 | OWL/JS | Day 476 |
| D9 | Card payment UI | Dev 3 | OWL/JS | Day 478 |
| D10 | Saved cards management | Dev 2 | Python/JS | Day 479 |
| D11 | Integration tests | All | Python | Day 480 |

---

## 4. Requirement Traceability

### 4.1 RFP Requirements

| RFP Req ID | Description | Deliverable |
|------------|-------------|-------------|
| ECOM-PAY-003 | Rocket Payment Integration | D1, D2 |
| ECOM-PAY-004 | Card Payment Gateway | D3, D4 |
| ECOM-PAY-004.1 | Visa/MasterCard support | D4, D9 |
| ECOM-PAY-004.2 | 3D Secure authentication | D7 |
| ECOM-PAY-004.3 | Saved payment methods | D10 |

### 4.2 BRD Requirements

| BRD Req ID | Description | Deliverable |
|------------|-------------|-------------|
| FR-CHK-002.3 | Mobile Banking - Rocket | D1, D2 |
| FR-CHK-002.4 | Card Payment Gateway | D3, D4 |
| FR-CHK-002.6 | Multi-payment selection | D8 |
| FR-SEC-003.1 | 3D Secure compliance | D7 |

---

## 5. Day-by-Day Development Plan

### Day 471: Project Setup & Architecture

#### Day Objective
Set up Rocket and SSLCommerz integration modules and design unified payment architecture.

#### Dev 1 (Backend Lead) - 8 Hours

**Task 1.1: Module Structure (3h)**

```python
# File: smart_dairy_payment_rocket/__manifest__.py

{
    'name': 'Smart Dairy - Rocket Payment Integration',
    'version': '19.0.1.0.0',
    'category': 'Accounting/Payment',
    'summary': 'DBBL Rocket Mobile Banking Integration',
    'description': """
        Rocket Payment Integration
        ==========================
        - DBBL Rocket payment processing
        - Mobile number verification
        - Transaction callbacks
        - Refund processing
    """,
    'author': 'Smart Dairy Technical Team',
    'license': 'LGPL-3',
    'depends': ['base', 'payment', 'sale', 'smart_dairy_payment'],
    'data': [
        'security/ir.model.access.csv',
        'data/payment_provider_data.xml',
        'views/payment_provider_views.xml',
    ],
    'assets': {
        'web.assets_frontend': [
            'smart_dairy_payment_rocket/static/src/**/*',
        ],
    },
    'installable': True,
    'auto_install': False,
}
```

```python
# File: smart_dairy_payment_sslcommerz/__manifest__.py

{
    'name': 'Smart Dairy - SSLCommerz Payment Gateway',
    'version': '19.0.1.0.0',
    'category': 'Accounting/Payment',
    'summary': 'SSLCommerz Multi-Channel Payment Gateway',
    'description': """
        SSLCommerz Payment Gateway Integration
        ======================================
        - Credit/Debit card processing (Visa, MasterCard, Amex)
        - Bank transfer payments
        - Mobile wallet integration
        - 3D Secure authentication
        - IPN webhook handling
        - Saved card tokenization
    """,
    'author': 'Smart Dairy Technical Team',
    'license': 'LGPL-3',
    'depends': ['base', 'payment', 'sale', 'smart_dairy_payment'],
    'data': [
        'security/ir.model.access.csv',
        'security/sslcommerz_security.xml',
        'data/payment_provider_data.xml',
        'views/payment_provider_views.xml',
        'views/payment_transaction_views.xml',
        'views/saved_card_views.xml',
    ],
    'assets': {
        'web.assets_frontend': [
            'smart_dairy_payment_sslcommerz/static/src/js/**/*',
            'smart_dairy_payment_sslcommerz/static/src/css/**/*',
            'smart_dairy_payment_sslcommerz/static/src/xml/**/*',
        ],
    },
    'external_dependencies': {
        'python': ['requests'],
    },
    'installable': True,
    'auto_install': False,
}
```

**Task 1.2: Unified Payment Gateway Architecture (3h)**

```python
# File: smart_dairy_payment/services/payment_gateway_factory.py

from odoo import models, api
import logging

_logger = logging.getLogger(__name__)


class PaymentGatewayFactory(models.AbstractModel):
    _name = 'payment.gateway.factory'
    _description = 'Payment Gateway Factory'

    GATEWAY_MAPPING = {
        'bkash': 'bkash.checkout.service',
        'nagad': 'nagad.checkout.service',
        'rocket': 'rocket.payment.service',
        'sslcommerz': 'sslcommerz.payment.service',
    }

    @api.model
    def get_gateway_service(self, provider_code):
        """Get the appropriate gateway service for a provider."""
        service_name = self.GATEWAY_MAPPING.get(provider_code)
        if service_name:
            return self.env[service_name]
        raise ValueError(f"Unknown payment provider: {provider_code}")

    @api.model
    def get_available_gateways(self):
        """Get list of available payment gateways."""
        providers = self.env['payment.provider'].search([
            ('state', 'in', ['enabled', 'test']),
            ('code', 'in', list(self.GATEWAY_MAPPING.keys())),
        ])
        return providers

    @api.model
    def initialize_payment(self, provider, transaction, callback_url):
        """Initialize payment through appropriate gateway."""
        service = self.get_gateway_service(provider.code)
        return service.initialize_payment(provider, transaction, callback_url)

    @api.model
    def verify_payment(self, provider, payment_ref):
        """Verify payment through appropriate gateway."""
        service = self.get_gateway_service(provider.code)
        return service.verify_payment(provider, payment_ref)
```

**Task 1.3: Configuration Documentation (2h)**

```python
# File: smart_dairy_payment_sslcommerz/docs/sslcommerz_spec.py
"""
SSLCommerz API Specification
============================

Base URLs:
- Sandbox: https://sandbox.sslcommerz.com
- Production: https://securepay.sslcommerz.com

Key Endpoints:
1. Session Init: POST /gwprocess/v4/api.php
2. Transaction Query: GET /validator/api/merchantTransIDvalidationAPI.php
3. Refund: POST /validator/api/merchantTransIDvalidationAPI.php

Required Parameters:
- store_id: Merchant Store ID
- store_passwd: Store Password
- total_amount: Transaction amount
- currency: BDT
- tran_id: Unique transaction ID
- success_url: Success redirect URL
- fail_url: Failure redirect URL
- cancel_url: Cancel redirect URL
- ipn_url: IPN callback URL

Supported Payment Methods:
- Visa, MasterCard, Amex (card_type: 'creditcard')
- Bank Transfer (card_type: 'netbanking')
- bKash via SSLCommerz (card_type: 'bkash')
- Mobile Banking (card_type: 'mobilebanking')
"""

SSLCOMMERZ_CONFIG = {
    'sandbox': {
        'session_url': 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php',
        'validation_url': 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php',
        'transaction_url': 'https://sandbox.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php',
    },
    'production': {
        'session_url': 'https://securepay.sslcommerz.com/gwprocess/v4/api.php',
        'validation_url': 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php',
        'transaction_url': 'https://securepay.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php',
    },
    'supported_currencies': ['BDT', 'USD', 'EUR', 'GBP'],
    'card_brands': ['VISA', 'MASTER', 'AMEX', 'DINNERS', 'DISCOVER'],
}
```

#### Dev 2 (Full-Stack) - 8 Hours

**Task 2.1: IPN Handler Architecture (4h)**

```python
# File: smart_dairy_payment_sslcommerz/services/ipn_handler.py

import hashlib
import hmac
from odoo import models, api, fields
import logging

_logger = logging.getLogger(__name__)


class SSLCommerzIPNHandler(models.AbstractModel):
    _name = 'sslcommerz.ipn.handler'
    _description = 'SSLCommerz IPN Handler'

    @api.model
    def process_ipn(self, ipn_data):
        """
        Process SSLCommerz IPN notification.

        Args:
            ipn_data: Dictionary containing IPN parameters

        Returns:
            dict with processing result
        """
        _logger.info(f"Processing SSLCommerz IPN: {ipn_data.get('tran_id')}")

        # Extract key fields
        tran_id = ipn_data.get('tran_id')
        val_id = ipn_data.get('val_id')
        status = ipn_data.get('status')
        amount = ipn_data.get('amount')
        store_amount = ipn_data.get('store_amount')
        currency = ipn_data.get('currency')
        bank_tran_id = ipn_data.get('bank_tran_id')
        card_type = ipn_data.get('card_type')
        card_brand = ipn_data.get('card_brand')

        # Find transaction
        transaction = self.env['payment.transaction'].sudo().search([
            ('reference', '=', tran_id),
        ], limit=1)

        if not transaction:
            _logger.error(f"IPN: Transaction not found for {tran_id}")
            return {'success': False, 'error': 'Transaction not found'}

        # Verify with SSLCommerz server
        if not self._verify_transaction(transaction.provider_id, val_id):
            _logger.error(f"IPN: Verification failed for {tran_id}")
            return {'success': False, 'error': 'Verification failed'}

        # Verify amount
        if float(amount) != transaction.amount:
            _logger.error(f"IPN: Amount mismatch for {tran_id}")
            transaction._set_error("Amount mismatch detected")
            return {'success': False, 'error': 'Amount mismatch'}

        # Update transaction based on status
        if status == 'VALID':
            transaction.write({
                'sslc_val_id': val_id,
                'sslc_bank_tran_id': bank_tran_id,
                'sslc_card_type': card_type,
                'sslc_card_brand': card_brand,
                'sslc_store_amount': float(store_amount),
            })
            transaction._set_done()
            return {'success': True, 'status': 'completed'}

        elif status == 'FAILED':
            transaction._set_error(ipn_data.get('error', 'Payment failed'))
            return {'success': True, 'status': 'failed'}

        elif status == 'CANCELLED':
            transaction._set_canceled()
            return {'success': True, 'status': 'cancelled'}

        return {'success': False, 'error': f'Unknown status: {status}'}

    def _verify_transaction(self, provider, val_id):
        """Verify transaction with SSLCommerz server."""
        import requests

        if provider.sslc_mode == 'sandbox':
            url = 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php'
        else:
            url = 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php'

        params = {
            'val_id': val_id,
            'store_id': provider.sslc_store_id,
            'store_passwd': provider.sslc_store_passwd,
            'format': 'json',
        }

        try:
            response = requests.get(url, params=params, timeout=30)
            data = response.json()
            return data.get('status') == 'VALID'
        except Exception as e:
            _logger.error(f"SSLCommerz verification error: {str(e)}")
            return False
```

**Task 2.2: Hash Verification Service (2h)**

```python
# File: smart_dairy_payment_sslcommerz/services/hash_verification.py

import hashlib
from odoo import models, api
import logging

_logger = logging.getLogger(__name__)


class SSLCommerzHashService(models.AbstractModel):
    _name = 'sslcommerz.hash.service'
    _description = 'SSLCommerz Hash Verification Service'

    @api.model
    def verify_ipn_hash(self, ipn_data, store_passwd):
        """
        Verify IPN hash for authenticity.

        Args:
            ipn_data: Dictionary containing IPN data
            store_passwd: Store password for hash verification

        Returns:
            Boolean indicating if hash is valid
        """
        received_hash = ipn_data.get('verify_sign')
        if not received_hash:
            _logger.warning("IPN hash verification: No verify_sign present")
            return False

        # Get fields to verify (exclude verify_sign and verify_key)
        verify_key = ipn_data.get('verify_key', '')
        fields_to_verify = verify_key.split(',')

        # Build verification string
        hash_string = ''
        for field in fields_to_verify:
            value = ipn_data.get(field, '')
            hash_string += f"{field}={value}&"

        # Remove trailing &
        hash_string = hash_string.rstrip('&')

        # Calculate expected hash
        expected_hash = hashlib.md5(hash_string.encode()).hexdigest()

        is_valid = expected_hash == received_hash
        if not is_valid:
            _logger.warning(f"IPN hash mismatch: expected {expected_hash}, got {received_hash}")

        return is_valid

    @api.model
    def generate_request_hash(self, params, store_passwd):
        """
        Generate hash for outgoing request.

        Args:
            params: Dictionary of request parameters
            store_passwd: Store password

        Returns:
            MD5 hash string
        """
        sorted_params = sorted(params.items())
        hash_string = '&'.join([f"{k}={v}" for k, v in sorted_params])
        hash_string += f"&store_passwd={store_passwd}"

        return hashlib.md5(hash_string.encode()).hexdigest()
```

**Task 2.3: Test Setup (2h)**

```python
# File: smart_dairy_payment_sslcommerz/tests/common.py

from odoo.tests.common import TransactionCase
from unittest.mock import patch, MagicMock


class SSLCommerzTestCommon(TransactionCase):
    """Common test setup for SSLCommerz payment tests."""

    @classmethod
    def setUpClass(cls):
        super().setUpClass()

        cls.partner = cls.env['res.partner'].create({
            'name': 'Test Customer Card',
            'email': 'test.card@example.com',
            'phone': '+8801812345678',
            'street': '123 Test Street',
            'city': 'Dhaka',
            'zip': '1205',
        })

        cls.provider = cls.env['payment.provider'].create({
            'name': 'SSLCommerz Test',
            'code': 'sslcommerz',
            'state': 'test',
            'sslc_mode': 'sandbox',
            'sslc_store_id': 'teststore001',
            'sslc_store_passwd': 'teststore001@ssl',
        })

        cls.product = cls.env['product.product'].create({
            'name': 'Premium Dairy Pack',
            'list_price': 2500.00,
            'type': 'consu',
        })

        cls.sale_order = cls.env['sale.order'].create({
            'partner_id': cls.partner.id,
            'order_line': [(0, 0, {
                'product_id': cls.product.id,
                'product_uom_qty': 1,
                'price_unit': 2500.00,
            })],
        })

    def _create_mock_ipn_data(self, status='VALID'):
        """Create mock IPN data for testing."""
        return {
            'tran_id': f'SO-{self.sale_order.id}-TEST',
            'val_id': 'VAL123456789',
            'status': status,
            'amount': '2500.00',
            'store_amount': '2437.50',
            'currency': 'BDT',
            'bank_tran_id': 'BANK123456',
            'card_type': 'VISA',
            'card_brand': 'VISA',
            'card_issuer': 'Test Bank',
            'card_issuer_country': 'Bangladesh',
        }
```

#### Dev 3 (Frontend/Mobile Lead) - 8 Hours

**Task 3.1: Payment Method Selector Component (4h)**

```javascript
/** @odoo-module **/
// File: smart_dairy_payment/static/src/js/payment_method_selector.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";
import { _t } from "@web/core/l10n/translation";

export class PaymentMethodSelector extends Component {
    static template = "smart_dairy_payment.PaymentMethodSelector";
    static props = {
        amount: { type: Number, required: true },
        currency: { type: String, default: "BDT" },
        orderId: { type: Number, optional: true },
        onProviderSelected: { type: Function, optional: true },
    };

    setup() {
        this.rpc = useService("rpc");

        this.state = useState({
            providers: [],
            selectedProvider: null,
            loading: true,
            categories: {
                mfs: { label: _t("Mobile Financial Services"), providers: [] },
                cards: { label: _t("Credit/Debit Cards"), providers: [] },
                banking: { label: _t("Internet Banking"), providers: [] },
            },
        });

        onWillStart(() => this.loadProviders());
    }

    async loadProviders() {
        try {
            const result = await this.rpc('/payment/providers/available', {
                amount: this.props.amount,
                currency: this.props.currency,
            });

            if (result.success) {
                this.categorizeProviders(result.providers);
            }
        } catch (error) {
            console.error("Failed to load providers:", error);
        } finally {
            this.state.loading = false;
        }
    }

    categorizeProviders(providers) {
        const mfs = ['bkash', 'nagad', 'rocket'];
        const cards = ['sslcommerz'];

        providers.forEach(provider => {
            if (mfs.includes(provider.code)) {
                this.state.categories.mfs.providers.push(provider);
            } else if (cards.includes(provider.code)) {
                this.state.categories.cards.providers.push(provider);
            } else {
                this.state.categories.banking.providers.push(provider);
            }
        });

        this.state.providers = providers;
    }

    selectProvider(provider) {
        this.state.selectedProvider = provider;
        if (this.props.onProviderSelected) {
            this.props.onProviderSelected(provider);
        }
    }

    getProviderIcon(code) {
        const icons = {
            bkash: '/smart_dairy_payment_bkash/static/src/img/bkash-icon.png',
            nagad: '/smart_dairy_payment_nagad/static/src/img/nagad-icon.png',
            rocket: '/smart_dairy_payment_rocket/static/src/img/rocket-icon.png',
            sslcommerz: '/smart_dairy_payment_sslcommerz/static/src/img/sslcommerz-icon.png',
        };
        return icons[code] || '/smart_dairy_payment/static/src/img/default-payment.png';
    }

    isSelected(provider) {
        return this.state.selectedProvider?.id === provider.id;
    }
}
```

**Task 3.2: Payment Selector Template (2h)**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- File: smart_dairy_payment/static/src/xml/payment_method_selector.xml -->

<templates xml:space="preserve">
    <t t-name="smart_dairy_payment.PaymentMethodSelector">
        <div class="payment-method-selector">
            <t t-if="state.loading">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"/>
                    <p class="mt-2 text-muted">Loading payment methods...</p>
                </div>
            </t>

            <t t-else="">
                <!-- Mobile Financial Services -->
                <t t-if="state.categories.mfs.providers.length > 0">
                    <div class="payment-category mb-4">
                        <h6 class="category-title text-muted mb-3">
                            <i class="fa fa-mobile me-2"/>
                            <t t-esc="state.categories.mfs.label"/>
                        </h6>
                        <div class="provider-grid">
                            <t t-foreach="state.categories.mfs.providers" t-as="provider" t-key="provider.id">
                                <div class="provider-card"
                                     t-att-class="{'selected': isSelected(provider)}"
                                     t-on-click="() => selectProvider(provider)">
                                    <img t-att-src="getProviderIcon(provider.code)"
                                         t-att-alt="provider.name"
                                         class="provider-icon"/>
                                    <span class="provider-name" t-esc="provider.name"/>
                                    <t t-if="isSelected(provider)">
                                        <i class="fa fa-check-circle selected-indicator"/>
                                    </t>
                                </div>
                            </t>
                        </div>
                    </div>
                </t>

                <!-- Credit/Debit Cards -->
                <t t-if="state.categories.cards.providers.length > 0">
                    <div class="payment-category mb-4">
                        <h6 class="category-title text-muted mb-3">
                            <i class="fa fa-credit-card me-2"/>
                            <t t-esc="state.categories.cards.label"/>
                        </h6>
                        <div class="provider-grid">
                            <t t-foreach="state.categories.cards.providers" t-as="provider" t-key="provider.id">
                                <div class="provider-card card-provider"
                                     t-att-class="{'selected': isSelected(provider)}"
                                     t-on-click="() => selectProvider(provider)">
                                    <div class="card-brands">
                                        <img src="/smart_dairy_payment_sslcommerz/static/src/img/visa.svg"
                                             alt="Visa" class="card-brand-icon"/>
                                        <img src="/smart_dairy_payment_sslcommerz/static/src/img/mastercard.svg"
                                             alt="MasterCard" class="card-brand-icon"/>
                                        <img src="/smart_dairy_payment_sslcommerz/static/src/img/amex.svg"
                                             alt="Amex" class="card-brand-icon"/>
                                    </div>
                                    <span class="provider-name">Credit/Debit Card</span>
                                    <t t-if="isSelected(provider)">
                                        <i class="fa fa-check-circle selected-indicator"/>
                                    </t>
                                </div>
                            </t>
                        </div>
                    </div>
                </t>

                <!-- Amount Summary -->
                <div class="payment-summary mt-4 p-3 bg-light rounded">
                    <div class="d-flex justify-content-between">
                        <span>Amount to Pay:</span>
                        <strong>
                            <t t-esc="props.currency"/>
                            <t t-esc="props.amount.toLocaleString()"/>
                        </strong>
                    </div>
                </div>
            </t>
        </div>
    </t>
</templates>
```

**Task 3.3: Selector Styling (2h)**

```css
/* File: smart_dairy_payment/static/src/css/payment_selector.css */

.payment-method-selector {
    max-width: 500px;
}

.payment-category {
    border-bottom: 1px solid #eee;
    padding-bottom: 15px;
}

.payment-category:last-child {
    border-bottom: none;
}

.category-title {
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.provider-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 12px;
}

.provider-card {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: white;
}

.provider-card:hover {
    border-color: #007bff;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
}

.provider-card.selected {
    border-color: #28a745;
    background: linear-gradient(to bottom, #f0fff4, white);
}

.provider-icon {
    height: 40px;
    width: auto;
    margin-bottom: 8px;
    object-fit: contain;
}

.provider-name {
    font-size: 0.85rem;
    font-weight: 500;
    text-align: center;
}

.selected-indicator {
    position: absolute;
    top: 8px;
    right: 8px;
    color: #28a745;
    font-size: 1.2rem;
}

/* Card provider specific */
.card-provider .card-brands {
    display: flex;
    gap: 8px;
    margin-bottom: 10px;
}

.card-brand-icon {
    height: 24px;
    width: auto;
}

/* Mobile responsive */
@media (max-width: 576px) {
    .provider-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .provider-card {
        padding: 12px;
    }

    .provider-icon {
        height: 32px;
    }
}
```

#### End of Day 471 Deliverables

- [ ] Module structures created
- [ ] Unified payment factory implemented
- [ ] IPN handler architecture designed
- [ ] Hash verification service created
- [ ] Test setup completed
- [ ] Payment selector component created

---

### Day 472: Rocket Payment Integration

#### Day Objective
Implement complete DBBL Rocket mobile banking payment integration.

#### Dev 1 (Backend Lead) - 8 Hours

**Task 1.1: Rocket Provider Model (3h)**

```python
# File: smart_dairy_payment_rocket/models/payment_provider.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError
import logging

_logger = logging.getLogger(__name__)


class PaymentProvider(models.Model):
    _inherit = 'payment.provider'

    code = fields.Selection(
        selection_add=[('rocket', 'Rocket')],
        ondelete={'rocket': 'set default'}
    )

    # Rocket Configuration
    rocket_merchant_id = fields.Char(
        string='Merchant ID',
        groups='base.group_system'
    )
    rocket_api_key = fields.Char(
        string='API Key',
        groups='base.group_system'
    )
    rocket_api_secret = fields.Char(
        string='API Secret',
        groups='base.group_system'
    )
    rocket_mode = fields.Selection([
        ('sandbox', 'Sandbox'),
        ('production', 'Production'),
    ], string='Mode', default='sandbox')

    def _get_rocket_base_url(self):
        """Get Rocket API base URL."""
        self.ensure_one()
        if self.rocket_mode == 'sandbox':
            return 'https://sandbox.rocket.com.bd/api/v1'
        return 'https://api.rocket.com.bd/api/v1'

    @api.constrains('code', 'rocket_merchant_id')
    def _check_rocket_credentials(self):
        for provider in self:
            if provider.code == 'rocket' and provider.state != 'disabled':
                if not provider.rocket_merchant_id:
                    raise ValidationError("Rocket Merchant ID is required")
```

**Task 1.2: Rocket Payment Service (5h)**

```python
# File: smart_dairy_payment_rocket/services/rocket_payment.py

import requests
import hashlib
import time
from datetime import datetime
from odoo import models, api
from odoo.exceptions import UserError
import logging

_logger = logging.getLogger(__name__)


class RocketPaymentService(models.AbstractModel):
    _name = 'rocket.payment.service'
    _description = 'Rocket Payment Service'

    @api.model
    def initialize_payment(self, provider, transaction, callback_url):
        """
        Initialize Rocket payment.

        Args:
            provider: payment.provider record
            transaction: payment.transaction record
            callback_url: Callback URL for payment status

        Returns:
            dict with payment initialization result
        """
        # Generate unique transaction ID
        tran_id = f"SD-{transaction.id}-{int(time.time())}"
        transaction.write({'rocket_tran_id': tran_id})

        # Prepare request data
        timestamp = datetime.now().strftime('%Y%m%d%H%M%S')
        signature = self._generate_signature(
            provider, tran_id, transaction.amount, timestamp
        )

        payload = {
            'merchant_id': provider.rocket_merchant_id,
            'tran_id': tran_id,
            'amount': str(transaction.amount),
            'currency': 'BDT',
            'customer_name': transaction.partner_id.name,
            'customer_phone': transaction.partner_id.phone,
            'callback_url': callback_url,
            'timestamp': timestamp,
            'signature': signature,
        }

        url = f"{provider._get_rocket_base_url()}/payment/initialize"
        headers = {
            'Content-Type': 'application/json',
            'Authorization': f'Bearer {provider.rocket_api_key}',
        }

        try:
            response = requests.post(url, json=payload, headers=headers, timeout=30)
            data = response.json()

            if response.status_code == 200 and data.get('success'):
                transaction._set_pending()
                return {
                    'success': True,
                    'redirect_url': data.get('payment_url'),
                    'tran_id': tran_id,
                }
            else:
                error_msg = data.get('message', 'Payment initialization failed')
                transaction._set_error(error_msg)
                return {'success': False, 'error': error_msg}

        except Exception as e:
            _logger.error(f"Rocket payment init error: {str(e)}")
            transaction._set_error(str(e))
            return {'success': False, 'error': str(e)}

    @api.model
    def verify_payment(self, provider, tran_id):
        """Verify Rocket payment status."""
        url = f"{provider._get_rocket_base_url()}/payment/verify"
        headers = {
            'Authorization': f'Bearer {provider.rocket_api_key}',
        }
        params = {
            'merchant_id': provider.rocket_merchant_id,
            'tran_id': tran_id,
        }

        try:
            response = requests.get(url, params=params, headers=headers, timeout=30)
            data = response.json()

            if data.get('status') == 'SUCCESS':
                return {
                    'success': True,
                    'data': {
                        'tran_id': data.get('tran_id'),
                        'amount': data.get('amount'),
                        'rocket_ref': data.get('rocket_reference'),
                    }
                }
            return {
                'success': False,
                'status': data.get('status'),
                'error': data.get('message'),
            }

        except Exception as e:
            _logger.error(f"Rocket verify error: {str(e)}")
            return {'success': False, 'error': str(e)}

    def _generate_signature(self, provider, tran_id, amount, timestamp):
        """Generate request signature."""
        sign_string = f"{provider.rocket_merchant_id}{tran_id}{amount}{timestamp}{provider.rocket_api_secret}"
        return hashlib.sha256(sign_string.encode()).hexdigest()
```

#### Dev 2 (Full-Stack) - 8 Hours

**Task 2.1: Rocket Transaction Extension (3h)**

```python
# File: smart_dairy_payment_rocket/models/payment_transaction.py

from odoo import models, fields, api
import logging

_logger = logging.getLogger(__name__)


class PaymentTransaction(models.Model):
    _inherit = 'payment.transaction'

    rocket_tran_id = fields.Char(string='Rocket Transaction ID', readonly=True)
    rocket_ref = fields.Char(string='Rocket Reference', readonly=True)
    rocket_status = fields.Selection([
        ('initiated', 'Initiated'),
        ('pending', 'Pending'),
        ('success', 'Success'),
        ('failed', 'Failed'),
        ('cancelled', 'Cancelled'),
    ], string='Rocket Status', readonly=True, default='initiated')

    def _set_rocket_done(self, rocket_data=None):
        """Mark transaction as completed from Rocket."""
        self.ensure_one()
        vals = {'rocket_status': 'success'}
        if rocket_data:
            vals['rocket_ref'] = rocket_data.get('rocket_ref')
        self.write(vals)
        self._set_done()

    def _set_rocket_error(self, message):
        """Mark transaction as failed."""
        self.ensure_one()
        self.write({
            'rocket_status': 'failed',
            'state_message': message,
        })
        self._set_error(message)
```

**Task 2.2: Rocket Controller (3h)**

```python
# File: smart_dairy_payment_rocket/controllers/main.py

import logging
from odoo import http
from odoo.http import request

_logger = logging.getLogger(__name__)


class RocketController(http.Controller):

    @http.route('/payment/rocket/initialize', type='json', auth='public', methods=['POST'])
    def rocket_initialize(self, provider_id, amount, reference, phone=None, **kwargs):
        """Initialize Rocket payment."""
        try:
            provider = request.env['payment.provider'].sudo().browse(provider_id)
            if not provider.exists() or provider.code != 'rocket':
                return {'success': False, 'error': 'Invalid provider'}

            # Get or create transaction
            transaction = self._get_or_create_transaction(provider, amount, reference)

            base_url = request.env['ir.config_parameter'].sudo().get_param('web.base.url')
            callback_url = f"{base_url}/payment/rocket/callback"

            service = request.env['rocket.payment.service'].sudo()
            return service.initialize_payment(provider, transaction, callback_url)

        except Exception as e:
            _logger.error(f"Rocket init error: {str(e)}")
            return {'success': False, 'error': str(e)}

    @http.route('/payment/rocket/callback', type='http', auth='public',
                methods=['GET', 'POST'], csrf=False)
    def rocket_callback(self, **post):
        """Handle Rocket payment callback."""
        _logger.info(f"Rocket callback: {post}")

        tran_id = post.get('tran_id')
        status = post.get('status')

        transaction = request.env['payment.transaction'].sudo().search([
            ('rocket_tran_id', '=', tran_id),
        ], limit=1)

        if not transaction:
            return request.redirect('/payment/status?error=transaction_not_found')

        if status == 'SUCCESS':
            # Verify with Rocket
            service = request.env['rocket.payment.service'].sudo()
            result = service.verify_payment(transaction.provider_id, tran_id)

            if result.get('success'):
                transaction._set_rocket_done(result.get('data'))
                return request.redirect(f'/payment/status?reference={transaction.reference}&status=success')

        transaction._set_rocket_error(post.get('message', 'Payment failed'))
        return request.redirect(f'/payment/status?reference={transaction.reference}&status=failed')

    def _get_or_create_transaction(self, provider, amount, reference):
        """Get or create payment transaction."""
        Transaction = request.env['payment.transaction'].sudo()
        existing = Transaction.search([
            ('reference', '=', reference),
            ('provider_id', '=', provider.id),
        ], limit=1)

        if existing:
            return existing

        return Transaction.create({
            'provider_id': provider.id,
            'amount': amount,
            'currency_id': request.env.ref('base.BDT').id,
            'reference': reference,
            'partner_id': request.env.user.partner_id.id,
        })
```

**Task 2.3: Rocket API Logger (2h)**

```python
# File: smart_dairy_payment_rocket/models/rocket_api_log.py

from odoo import models, fields, api
import json


class RocketAPILog(models.Model):
    _name = 'rocket.api.log'
    _description = 'Rocket API Log'
    _order = 'create_date desc'

    transaction_id = fields.Many2one('payment.transaction', string='Transaction')
    endpoint = fields.Char(string='Endpoint')
    method = fields.Selection([('GET', 'GET'), ('POST', 'POST')], string='Method')
    request_data = fields.Text(string='Request')
    response_data = fields.Text(string='Response')
    response_code = fields.Integer(string='Status Code')
    duration_ms = fields.Integer(string='Duration (ms)')
    status = fields.Selection([
        ('success', 'Success'),
        ('error', 'Error'),
    ], string='Status')
```

#### Dev 3 (Frontend/Mobile Lead) - 8 Hours

**Task 3.1: Rocket Payment UI (4h)**

```javascript
/** @odoo-module **/
// File: smart_dairy_payment_rocket/static/src/js/rocket_payment.js

import { Component, useState } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";
import { _t } from "@web/core/l10n/translation";

export class RocketPaymentForm extends Component {
    static template = "smart_dairy_payment_rocket.PaymentForm";
    static props = {
        providerId: { type: Number, required: true },
        amount: { type: Number, required: true },
        reference: { type: String, required: true },
        onSuccess: { type: Function, optional: true },
        onError: { type: Function, optional: true },
    };

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            loading: false,
            rocketNumber: '',
            step: 'input',
            error: null,
        });
    }

    validateNumber() {
        const num = this.state.rocketNumber;
        // Rocket numbers typically start with 018
        if (num.length !== 10) {
            return _t("Number must be 10 digits");
        }
        if (!num.startsWith('18')) {
            return _t("Invalid Rocket number format");
        }
        return null;
    }

    async initiatePayment() {
        const error = this.validateNumber();
        if (error) {
            this.state.error = error;
            return;
        }

        this.state.loading = true;
        this.state.error = null;

        try {
            const result = await this.rpc('/payment/rocket/initialize', {
                provider_id: this.props.providerId,
                amount: this.props.amount,
                reference: this.props.reference,
                phone: `+880${this.state.rocketNumber}`,
            });

            if (result.success && result.redirect_url) {
                window.location.href = result.redirect_url;
            } else {
                this.state.error = result.error || _t("Payment failed");
            }
        } catch (error) {
            this.state.error = error.message;
        } finally {
            this.state.loading = false;
        }
    }
}
```

**Task 3.2: Rocket Template and Styling (4h)**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- File: smart_dairy_payment_rocket/static/src/xml/rocket_templates.xml -->

<templates xml:space="preserve">
    <t t-name="smart_dairy_payment_rocket.PaymentForm">
        <div class="rocket-payment-form">
            <div class="rocket-header text-center mb-4">
                <img src="/smart_dairy_payment_rocket/static/src/img/rocket-logo.png"
                     alt="Rocket" class="rocket-logo" style="height: 50px;"/>
                <h5 class="mt-2">Pay with Rocket</h5>
            </div>

            <div class="amount-display text-center p-3 mb-4 bg-light rounded">
                <span class="text-muted">Amount</span>
                <h4 class="mb-0 text-primary">
                    BDT <t t-esc="props.amount.toLocaleString()"/>
                </h4>
            </div>

            <t t-if="state.step === 'input'">
                <div class="mb-3">
                    <label class="form-label">Rocket Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-purple text-white">+880</span>
                        <input type="tel"
                               class="form-control"
                               t-att-class="{'is-invalid': state.error}"
                               t-model="state.rocketNumber"
                               placeholder="18XXXXXXXX"
                               maxlength="10"
                               t-on-input="(e) => { state.rocketNumber = e.target.value.replace(/\\D/g, ''); state.error = null; }"/>
                    </div>
                    <t t-if="state.error">
                        <div class="invalid-feedback d-block" t-esc="state.error"/>
                    </t>
                </div>

                <button type="button"
                        class="btn btn-purple btn-lg w-100"
                        t-att-disabled="state.loading || state.rocketNumber.length !== 10"
                        t-on-click="initiatePayment">
                    <t t-if="state.loading">
                        <span class="spinner-border spinner-border-sm me-2"/>
                        Processing...
                    </t>
                    <t t-else="">Pay with Rocket</t>
                </button>
            </t>

            <div class="text-center mt-3">
                <small class="text-muted">
                    <i class="fa fa-lock me-1"/>
                    Powered by Dutch-Bangla Bank
                </small>
            </div>
        </div>
    </t>
</templates>
```

```css
/* File: smart_dairy_payment_rocket/static/src/css/rocket.css */

.rocket-payment-form {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
}

.bg-purple {
    background-color: #7952b3;
    border-color: #7952b3;
}

.btn-purple {
    background-color: #7952b3;
    border-color: #7952b3;
    color: white;
}

.btn-purple:hover:not(:disabled) {
    background-color: #6a4499;
    border-color: #6a4499;
    color: white;
}

.text-purple {
    color: #7952b3;
}
```

#### End of Day 472 Deliverables

- [ ] Rocket provider model completed
- [ ] Rocket payment service implemented
- [ ] Transaction extension done
- [ ] Controllers created
- [ ] API logging implemented
- [ ] Payment UI completed

---

### Day 473-475: SSLCommerz Integration

#### Dev 1 (Backend Lead)

**SSLCommerz Provider Model**

```python
# File: smart_dairy_payment_sslcommerz/models/payment_provider.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError
import logging

_logger = logging.getLogger(__name__)


class PaymentProvider(models.Model):
    _inherit = 'payment.provider'

    code = fields.Selection(
        selection_add=[('sslcommerz', 'SSLCommerz')],
        ondelete={'sslcommerz': 'set default'}
    )

    # SSLCommerz Configuration
    sslc_store_id = fields.Char(
        string='Store ID',
        groups='base.group_system'
    )
    sslc_store_passwd = fields.Char(
        string='Store Password',
        groups='base.group_system'
    )
    sslc_mode = fields.Selection([
        ('sandbox', 'Sandbox'),
        ('production', 'Production'),
    ], string='Mode', default='sandbox')

    # Payment Options
    sslc_enable_cards = fields.Boolean(string='Enable Card Payments', default=True)
    sslc_enable_mfs = fields.Boolean(string='Enable Mobile Banking', default=True)
    sslc_enable_net_banking = fields.Boolean(string='Enable Net Banking', default=True)

    def _get_sslc_session_url(self):
        """Get SSLCommerz session API URL."""
        self.ensure_one()
        if self.sslc_mode == 'sandbox':
            return 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'
        return 'https://securepay.sslcommerz.com/gwprocess/v4/api.php'

    def _get_sslc_validation_url(self):
        """Get SSLCommerz validation API URL."""
        self.ensure_one()
        if self.sslc_mode == 'sandbox':
            return 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php'
        return 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php'

    @api.constrains('code', 'sslc_store_id', 'sslc_store_passwd')
    def _check_sslc_credentials(self):
        for provider in self:
            if provider.code == 'sslcommerz' and provider.state != 'disabled':
                if not provider.sslc_store_id or not provider.sslc_store_passwd:
                    raise ValidationError("SSLCommerz Store ID and Password are required")
```

**SSLCommerz Payment Service**

```python
# File: smart_dairy_payment_sslcommerz/services/sslcommerz_payment.py

import requests
import time
from datetime import datetime
from odoo import models, api
from odoo.exceptions import UserError
import logging

_logger = logging.getLogger(__name__)


class SSLCommerzPaymentService(models.AbstractModel):
    _name = 'sslcommerz.payment.service'
    _description = 'SSLCommerz Payment Service'

    @api.model
    def initialize_payment(self, provider, transaction, success_url, fail_url,
                          cancel_url, ipn_url):
        """
        Initialize SSLCommerz payment session.

        Args:
            provider: payment.provider record
            transaction: payment.transaction record
            success_url: Success redirect URL
            fail_url: Failure redirect URL
            cancel_url: Cancel redirect URL
            ipn_url: IPN callback URL

        Returns:
            dict with session initialization result
        """
        # Generate transaction ID
        tran_id = f"SD-{transaction.id}-{int(time.time())}"
        transaction.write({'sslc_tran_id': tran_id})

        partner = transaction.partner_id

        # Prepare POST data
        post_data = {
            'store_id': provider.sslc_store_id,
            'store_passwd': provider.sslc_store_passwd,
            'total_amount': transaction.amount,
            'currency': 'BDT',
            'tran_id': tran_id,
            'success_url': success_url,
            'fail_url': fail_url,
            'cancel_url': cancel_url,
            'ipn_url': ipn_url,

            # Customer Info
            'cus_name': partner.name or 'Customer',
            'cus_email': partner.email or 'customer@example.com',
            'cus_phone': partner.phone or '',
            'cus_add1': partner.street or 'N/A',
            'cus_city': partner.city or 'Dhaka',
            'cus_country': 'Bangladesh',
            'cus_postcode': partner.zip or '1000',

            # Shipping Info
            'shipping_method': 'NO',
            'num_of_item': 1,

            # Product Info
            'product_name': 'Smart Dairy Products',
            'product_category': 'Dairy',
            'product_profile': 'general',
        }

        # Add payment options based on configuration
        if not provider.sslc_enable_cards:
            post_data['multi_card_name'] = ''
        if not provider.sslc_enable_mfs:
            post_data['allowed_bin'] = ''

        try:
            _logger.info(f"SSLCommerz session init for {tran_id}")
            response = requests.post(
                provider._get_sslc_session_url(),
                data=post_data,
                timeout=30
            )
            data = response.json()

            # Log the API call
            self.env['sslcommerz.api.log'].sudo().create({
                'transaction_id': transaction.id,
                'endpoint': 'session_init',
                'request_data': str(post_data),
                'response_data': str(data),
                'status': 'success' if data.get('status') == 'SUCCESS' else 'error',
            })

            if data.get('status') == 'SUCCESS':
                transaction.write({
                    'sslc_session_key': data.get('sessionkey'),
                })
                transaction._set_pending()

                return {
                    'success': True,
                    'gateway_url': data.get('GatewayPageURL'),
                    'session_key': data.get('sessionkey'),
                    'tran_id': tran_id,
                }
            else:
                error_msg = data.get('failedreason', 'Session initialization failed')
                transaction._set_error(error_msg)
                return {'success': False, 'error': error_msg}

        except Exception as e:
            _logger.error(f"SSLCommerz init error: {str(e)}")
            transaction._set_error(str(e))
            return {'success': False, 'error': str(e)}

    @api.model
    def validate_transaction(self, provider, val_id):
        """
        Validate transaction with SSLCommerz server.

        Args:
            provider: payment.provider record
            val_id: Validation ID from callback

        Returns:
            dict with validation result
        """
        url = provider._get_sslc_validation_url()
        params = {
            'val_id': val_id,
            'store_id': provider.sslc_store_id,
            'store_passwd': provider.sslc_store_passwd,
            'format': 'json',
        }

        try:
            response = requests.get(url, params=params, timeout=30)
            data = response.json()

            if data.get('status') == 'VALID' or data.get('status') == 'VALIDATED':
                return {
                    'success': True,
                    'data': {
                        'tran_id': data.get('tran_id'),
                        'val_id': val_id,
                        'amount': data.get('amount'),
                        'store_amount': data.get('store_amount'),
                        'card_type': data.get('card_type'),
                        'card_brand': data.get('card_brand'),
                        'card_issuer': data.get('card_issuer'),
                        'bank_tran_id': data.get('bank_tran_id'),
                        'tran_date': data.get('tran_date'),
                        'currency': data.get('currency'),
                        'risk_level': data.get('risk_level'),
                        'risk_title': data.get('risk_title'),
                    }
                }
            else:
                return {
                    'success': False,
                    'status': data.get('status'),
                    'error': data.get('error') or 'Validation failed',
                }

        except Exception as e:
            _logger.error(f"SSLCommerz validation error: {str(e)}")
            return {'success': False, 'error': str(e)}

    @api.model
    def process_refund(self, provider, transaction, amount=None, reason=None):
        """
        Process refund through SSLCommerz.

        Args:
            provider: payment.provider record
            transaction: payment.transaction record
            amount: Refund amount (None for full refund)
            reason: Refund reason

        Returns:
            dict with refund result
        """
        if provider.sslc_mode == 'sandbox':
            url = 'https://sandbox.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php'
        else:
            url = 'https://securepay.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php'

        refund_amount = amount or transaction.amount

        params = {
            'store_id': provider.sslc_store_id,
            'store_passwd': provider.sslc_store_passwd,
            'refund_amount': refund_amount,
            'refund_remarks': reason or 'Customer requested refund',
            'bank_tran_id': transaction.sslc_bank_tran_id,
            'refe_id': transaction.reference,
        }

        try:
            response = requests.get(url, params=params, timeout=30)
            data = response.json()

            if data.get('status') == 'success':
                # Create refund record
                self.env['payment.refund'].sudo().create({
                    'transaction_id': transaction.id,
                    'amount': refund_amount,
                    'status': 'completed',
                    'refund_reference': data.get('refund_ref_id'),
                })
                return {'success': True, 'refund_ref': data.get('refund_ref_id')}
            else:
                return {'success': False, 'error': data.get('errorReason', 'Refund failed')}

        except Exception as e:
            _logger.error(f"SSLCommerz refund error: {str(e)}")
            return {'success': False, 'error': str(e)}
```

**SSLCommerz Transaction Model**

```python
# File: smart_dairy_payment_sslcommerz/models/payment_transaction.py

from odoo import models, fields, api
import logging

_logger = logging.getLogger(__name__)


class PaymentTransaction(models.Model):
    _inherit = 'payment.transaction'

    # SSLCommerz specific fields
    sslc_tran_id = fields.Char(string='SSLCommerz Transaction ID', readonly=True)
    sslc_session_key = fields.Char(string='Session Key', readonly=True)
    sslc_val_id = fields.Char(string='Validation ID', readonly=True)
    sslc_bank_tran_id = fields.Char(string='Bank Transaction ID', readonly=True)
    sslc_card_type = fields.Char(string='Card Type', readonly=True)
    sslc_card_brand = fields.Char(string='Card Brand', readonly=True)
    sslc_card_issuer = fields.Char(string='Card Issuer', readonly=True)
    sslc_store_amount = fields.Float(string='Store Amount', readonly=True)
    sslc_risk_level = fields.Char(string='Risk Level', readonly=True)

    def _set_sslc_done(self, sslc_data=None):
        """Mark transaction as completed from SSLCommerz."""
        self.ensure_one()
        vals = {}
        if sslc_data:
            vals.update({
                'sslc_val_id': sslc_data.get('val_id'),
                'sslc_bank_tran_id': sslc_data.get('bank_tran_id'),
                'sslc_card_type': sslc_data.get('card_type'),
                'sslc_card_brand': sslc_data.get('card_brand'),
                'sslc_card_issuer': sslc_data.get('card_issuer'),
                'sslc_store_amount': float(sslc_data.get('store_amount', 0)),
                'sslc_risk_level': sslc_data.get('risk_level'),
            })
        self.write(vals)
        self._set_done()
```

#### Dev 3 (Frontend/Mobile Lead)

**SSLCommerz Card Payment UI**

```javascript
/** @odoo-module **/
// File: smart_dairy_payment_sslcommerz/static/src/js/sslcommerz_payment.js

import { Component, useState } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";
import { _t } from "@web/core/l10n/translation";

export class SSLCommerzPaymentForm extends Component {
    static template = "smart_dairy_payment_sslcommerz.PaymentForm";
    static props = {
        providerId: { type: Number, required: true },
        amount: { type: Number, required: true },
        reference: { type: String, required: true },
        customerInfo: { type: Object, optional: true },
    };

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            loading: false,
            selectedMethod: null,
            error: null,
        });

        this.paymentMethods = [
            { id: 'card', label: _t('Credit/Debit Card'), icon: 'fa-credit-card', brands: ['visa', 'mastercard', 'amex'] },
            { id: 'bkash', label: _t('bKash'), icon: 'fa-mobile' },
            { id: 'nagad', label: _t('Nagad'), icon: 'fa-mobile' },
            { id: 'rocket', label: _t('Rocket'), icon: 'fa-mobile' },
            { id: 'bank', label: _t('Internet Banking'), icon: 'fa-university' },
        ];
    }

    selectMethod(method) {
        this.state.selectedMethod = method;
        this.state.error = null;
    }

    async initiatePayment() {
        if (!this.state.selectedMethod) {
            this.state.error = _t("Please select a payment method");
            return;
        }

        this.state.loading = true;

        try {
            const result = await this.rpc('/payment/sslcommerz/initialize', {
                provider_id: this.props.providerId,
                amount: this.props.amount,
                reference: this.props.reference,
                payment_method: this.state.selectedMethod.id,
                customer_info: this.props.customerInfo,
            });

            if (result.success && result.gateway_url) {
                window.location.href = result.gateway_url;
            } else {
                this.state.error = result.error || _t("Payment initialization failed");
            }
        } catch (error) {
            this.state.error = error.message;
        } finally {
            this.state.loading = false;
        }
    }
}
```

---

### Day 476-480: Testing, Integration & Documentation

*(Complete IPN webhooks, 3D Secure handling, saved cards, and comprehensive testing)*

---

## 6. Technical Specifications

### 6.1 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/payment/rocket/initialize` | POST | Initialize Rocket payment |
| `/payment/rocket/callback` | GET/POST | Rocket callback handler |
| `/payment/sslcommerz/initialize` | POST | Initialize SSLCommerz session |
| `/payment/sslcommerz/success` | POST | Success redirect |
| `/payment/sslcommerz/fail` | POST | Failure redirect |
| `/payment/sslcommerz/cancel` | POST | Cancel redirect |
| `/payment/sslcommerz/ipn` | POST | IPN webhook |
| `/payment/sslcommerz/validate` | POST | Validate transaction |

### 6.2 SSLCommerz Payment Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                    SSLCommerz Payment Flow                       │
├─────────────────────────────────────────────────────────────────┤
│  1. Customer selects payment method                              │
│  2. Merchant creates session via API                             │
│  3. Customer redirected to SSLCommerz gateway                    │
│  4. Customer completes payment (3DS if card)                     │
│  5. SSLCommerz sends IPN notification                            │
│  6. Customer redirected to success/fail URL                      │
│  7. Merchant validates transaction with val_id                   │
│  8. Transaction completed                                        │
└─────────────────────────────────────────────────────────────────┘
```

### 6.3 Supported Payment Methods

| Method | Provider | Card Brands |
|--------|----------|-------------|
| Cards | SSLCommerz | Visa, MasterCard, Amex |
| Mobile Banking | Rocket | DBBL |
| MFS via SSLCommerz | bKash, Nagad | - |
| Net Banking | Various | 20+ Banks |

---

## 7. Database Schema

### 7.1 SSLCommerz Transaction Fields

```sql
ALTER TABLE payment_transaction ADD COLUMN sslc_tran_id VARCHAR(64);
ALTER TABLE payment_transaction ADD COLUMN sslc_session_key VARCHAR(128);
ALTER TABLE payment_transaction ADD COLUMN sslc_val_id VARCHAR(64);
ALTER TABLE payment_transaction ADD COLUMN sslc_bank_tran_id VARCHAR(64);
ALTER TABLE payment_transaction ADD COLUMN sslc_card_type VARCHAR(32);
ALTER TABLE payment_transaction ADD COLUMN sslc_card_brand VARCHAR(32);
ALTER TABLE payment_transaction ADD COLUMN sslc_card_issuer VARCHAR(128);
ALTER TABLE payment_transaction ADD COLUMN sslc_store_amount DECIMAL(10,2);
ALTER TABLE payment_transaction ADD COLUMN sslc_risk_level VARCHAR(20);

CREATE INDEX idx_pt_sslc_tran_id ON payment_transaction(sslc_tran_id);
CREATE INDEX idx_pt_sslc_val_id ON payment_transaction(sslc_val_id);
```

### 7.2 Saved Cards Table

```sql
CREATE TABLE sslcommerz_saved_card (
    id SERIAL PRIMARY KEY,
    partner_id INTEGER REFERENCES res_partner(id),
    card_token VARCHAR(128),
    card_brand VARCHAR(32),
    card_last4 CHAR(4),
    card_exp_month INTEGER,
    card_exp_year INTEGER,
    card_holder_name VARCHAR(128),
    is_default BOOLEAN DEFAULT FALSE,
    active BOOLEAN DEFAULT TRUE,
    create_date TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_ssc_partner ON sslcommerz_saved_card(partner_id);
```

---

## 8. API Documentation

### 8.1 SSLCommerz Session Request

```json
POST /payment/sslcommerz/initialize
{
    "provider_id": 7,
    "amount": 2500.00,
    "reference": "SO-12345",
    "payment_method": "card",
    "customer_info": {
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "+8801712345678"
    }
}
```

### 8.2 IPN Notification Parameters

| Parameter | Description |
|-----------|-------------|
| `tran_id` | Merchant transaction ID |
| `val_id` | SSLCommerz validation ID |
| `amount` | Transaction amount |
| `store_amount` | Amount after fees |
| `status` | VALID, FAILED, CANCELLED |
| `card_type` | VISA, MASTER, AMEX, etc. |
| `bank_tran_id` | Bank reference |
| `verify_sign` | Hash signature |

---

## 9. Security Implementation

### 9.1 IPN Security

- Hash verification for all IPN callbacks
- Server-side transaction validation
- IP whitelisting for IPN endpoints
- Amount verification against database

### 9.2 3D Secure

- All card transactions use 3DS
- Liability shift to issuer
- Additional authentication layer

### 9.3 PCI DSS Compliance

- No card data stored locally
- Tokenization for saved cards
- Secure credential management

---

## 10. Testing Requirements

### 10.1 Test Coverage Targets

| Component | Coverage |
|-----------|----------|
| SSLCommerz Service | > 85% |
| Rocket Service | > 85% |
| IPN Handler | > 90% |
| Controllers | > 80% |

### 10.2 Test Scenarios

```python
class TestSSLCommerzPayment(SSLCommerzTestCommon):

    def test_session_initialization(self):
        """Test payment session creation."""
        pass

    def test_ipn_valid_payment(self):
        """Test IPN for successful payment."""
        pass

    def test_ipn_hash_verification(self):
        """Test IPN hash verification."""
        pass

    def test_3ds_callback(self):
        """Test 3D Secure callback handling."""
        pass

    def test_refund_processing(self):
        """Test refund flow."""
        pass
```

---

## 11. Risk & Mitigation

| Risk | Impact | Mitigation |
|------|--------|------------|
| Gateway downtime | High | Retry logic, fallback UI |
| IPN delivery failure | High | Webhook queue, manual verify |
| 3DS authentication timeout | Medium | Clear user messaging |
| Fraudulent transactions | High | Risk scoring, amount limits |
| Hash collision | Low | Strong hashing algorithm |

---

## 12. Dependencies

### 12.1 Internal

| Dependency | Purpose |
|------------|---------|
| `smart_dairy_payment` | Base payment module |
| `payment` | Core payment provider |
| `sale` | Order integration |

### 12.2 External

| Package | Version | Purpose |
|---------|---------|---------|
| `requests` | >= 2.28 | HTTP client |

---

## 13. Sign-off Checklist

### 13.1 Rocket Integration

- [ ] Payment initialization works
- [ ] Callback handling correct
- [ ] Verification successful
- [ ] Error handling comprehensive

### 13.2 SSLCommerz Integration

- [ ] Session creation works
- [ ] All payment methods functional
- [ ] IPN processing reliable
- [ ] Hash verification working
- [ ] 3D Secure flow complete
- [ ] Refund processing works
- [ ] Saved cards functional

### 13.3 Multi-Gateway

- [ ] Payment selector UI complete
- [ ] Gateway switching works
- [ ] Unified status tracking

### 13.4 Security

- [ ] No sensitive data exposed
- [ ] All callbacks verified
- [ ] PCI DSS guidelines followed

---

**Document End**

*Milestone 73: Rocket & SSLCommerz Integration*
*Days 471-480 | Phase 8: Operations - Payment & Logistics*
*Smart Dairy Digital Smart Portal + ERP System*
