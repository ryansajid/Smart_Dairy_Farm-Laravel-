# Milestone 74: COD & Payment Processing

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P8-M74-v1.0 |
| **Milestone** | 74 - COD & Payment Processing |
| **Phase** | Phase 8: Operations - Payment & Logistics |
| **Days** | 481-490 |
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
9. [Fraud Prevention System](#9-fraud-prevention-system)
10. [Testing Requirements](#10-testing-requirements)
11. [Risk & Mitigation](#11-risk--mitigation)
12. [Dependencies](#12-dependencies)
13. [Sign-off Checklist](#13-sign-off-checklist)

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Implement comprehensive Cash on Delivery (COD) payment workflow with OTP verification, fraud scoring, customer eligibility validation, COD limit management, driver collection workflow, and COD settlement processing for secure cash-based transactions.**

### 1.2 Scope Summary

| Area | Included |
|------|----------|
| COD Configuration | Payment method setup and rules |
| Eligibility Validation | Customer COD eligibility checking |
| OTP Verification | SMS-based order confirmation |
| Fraud Scoring | Risk assessment system |
| COD Limits | Maximum order value management |
| Driver Collection | Cash collection workflow |
| Settlement | COD amount reconciliation |
| Unified Payment API | Combined payment method interface |

### 1.3 Prerequisites

- Milestones 71-73 (Payment integrations) completed
- SMS gateway configured (for OTP)
- Delivery zone management ready
- Driver mobile app framework available

---

## 2. Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| O1 | COD eligibility validation | 100% accuracy |
| O2 | OTP delivery rate | > 98% |
| O3 | OTP verification success | > 95% |
| O4 | Fraud detection rate | > 90% |
| O5 | False positive rate | < 5% |
| O6 | Collection confirmation | 100% tracked |
| O7 | Settlement accuracy | > 99.9% |

---

## 3. Key Deliverables

| # | Deliverable | Owner | Format | Due Day |
|---|-------------|-------|--------|---------|
| D1 | COD payment method configuration | Dev 1 | Python/Odoo | Day 482 |
| D2 | Customer eligibility service | Dev 1 | Python | Day 483 |
| D3 | OTP verification system | Dev 2 | Python | Day 484 |
| D4 | Fraud scoring engine | Dev 1 | Python | Day 485 |
| D5 | COD limit management | Dev 1 | Python | Day 486 |
| D6 | COD fee calculator | Dev 2 | Python | Day 485 |
| D7 | Driver collection workflow | Dev 2 | Python | Day 487 |
| D8 | COD settlement service | Dev 1 | Python | Day 488 |
| D9 | COD checkout UI | Dev 3 | OWL/JS | Day 486 |
| D10 | Driver collection app UI | Dev 3 | Flutter | Day 488 |
| D11 | Unified payment API | Dev 2 | Python | Day 489 |
| D12 | Integration tests | All | Python | Day 490 |

---

## 4. Requirement Traceability

### 4.1 RFP Requirements

| RFP Req ID | Description | Deliverable |
|------------|-------------|-------------|
| ECOM-PAY-005 | Cash on Delivery | D1-D8 |
| ECOM-PAY-005.1 | COD eligibility | D2 |
| ECOM-PAY-005.2 | COD OTP verification | D3 |
| ECOM-PAY-005.3 | COD fraud prevention | D4 |
| ECOM-PAY-005.4 | Driver collection | D7, D10 |

### 4.2 BRD Requirements

| BRD Req ID | Description | Deliverable |
|------------|-------------|-------------|
| FR-CHK-002.5 | Cash on Delivery option | D1, D9 |
| FR-CHK-002.6 | COD order limits | D5 |
| FR-SEC-004 | Fraud prevention | D4 |
| FR-LOG-003 | Driver cash collection | D7, D10 |

---

## 5. Day-by-Day Development Plan

### Day 481: Project Setup & Architecture

#### Day Objective
Design COD system architecture and set up module structure.

#### Dev 1 (Backend Lead) - 8 Hours

**Task 1.1: Module Structure (3h)**

```python
# File: smart_dairy_payment_cod/__manifest__.py

{
    'name': 'Smart Dairy - Cash on Delivery',
    'version': '19.0.1.0.0',
    'category': 'Accounting/Payment',
    'summary': 'Cash on Delivery Payment Method with Fraud Prevention',
    'description': """
        Cash on Delivery (COD) Payment Module
        ======================================
        - COD eligibility validation
        - OTP verification for orders
        - Fraud scoring system
        - COD limits by customer/zone
        - Driver collection workflow
        - Settlement processing
    """,
    'author': 'Smart Dairy Technical Team',
    'license': 'LGPL-3',
    'depends': [
        'base',
        'payment',
        'sale',
        'smart_dairy_payment',
        'smart_dairy_delivery',
        'smart_dairy_sms',
    ],
    'data': [
        'security/ir.model.access.csv',
        'security/cod_security.xml',
        'data/cod_config_data.xml',
        'data/fraud_rules_data.xml',
        'views/cod_config_views.xml',
        'views/cod_order_views.xml',
        'views/fraud_score_views.xml',
        'views/cod_settlement_views.xml',
    ],
    'assets': {
        'web.assets_frontend': [
            'smart_dairy_payment_cod/static/src/**/*',
        ],
    },
    'installable': True,
    'auto_install': False,
}
```

**Task 1.2: COD Configuration Model (3h)**

```python
# File: smart_dairy_payment_cod/models/cod_config.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError
import logging

_logger = logging.getLogger(__name__)


class CODConfiguration(models.Model):
    _name = 'cod.configuration'
    _description = 'COD Configuration'

    name = fields.Char(string='Name', required=True)
    active = fields.Boolean(default=True)
    company_id = fields.Many2one('res.company', default=lambda self: self.env.company)

    # Global Settings
    cod_enabled = fields.Boolean(string='COD Enabled', default=True)
    min_order_amount = fields.Monetary(
        string='Minimum Order Amount',
        currency_field='currency_id',
        default=100.00
    )
    max_order_amount = fields.Monetary(
        string='Maximum Order Amount',
        currency_field='currency_id',
        default=10000.00
    )
    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.ref('base.BDT')
    )

    # COD Fee Settings
    cod_fee_type = fields.Selection([
        ('fixed', 'Fixed Amount'),
        ('percentage', 'Percentage'),
        ('tiered', 'Tiered'),
    ], string='COD Fee Type', default='fixed')
    cod_fee_amount = fields.Monetary(
        string='COD Fee (Fixed)',
        currency_field='currency_id',
        default=20.00
    )
    cod_fee_percentage = fields.Float(string='COD Fee (%)', default=2.0)

    # OTP Settings
    otp_required = fields.Boolean(string='OTP Required', default=True)
    otp_threshold_amount = fields.Monetary(
        string='OTP Threshold Amount',
        currency_field='currency_id',
        default=500.00,
        help='OTP required for orders above this amount'
    )
    otp_expiry_minutes = fields.Integer(string='OTP Expiry (minutes)', default=5)
    max_otp_attempts = fields.Integer(string='Max OTP Attempts', default=3)

    # Fraud Prevention
    fraud_check_enabled = fields.Boolean(string='Enable Fraud Check', default=True)
    fraud_threshold_score = fields.Integer(
        string='Fraud Threshold Score',
        default=70,
        help='Orders with fraud score above this will be flagged'
    )
    auto_reject_score = fields.Integer(
        string='Auto Reject Score',
        default=90,
        help='Orders with fraud score above this will be auto-rejected'
    )

    # Customer Limits
    new_customer_cod_limit = fields.Monetary(
        string='New Customer COD Limit',
        currency_field='currency_id',
        default=2000.00
    )
    trusted_customer_cod_limit = fields.Monetary(
        string='Trusted Customer COD Limit',
        currency_field='currency_id',
        default=20000.00
    )
    max_pending_cod_orders = fields.Integer(
        string='Max Pending COD Orders',
        default=2,
        help='Maximum number of pending COD orders per customer'
    )

    @api.model
    def get_active_config(self, company_id=None):
        """Get active COD configuration."""
        domain = [('active', '=', True)]
        if company_id:
            domain.append(('company_id', '=', company_id))
        return self.search(domain, limit=1)
```

**Task 1.3: COD Order Model (2h)**

```python
# File: smart_dairy_payment_cod/models/cod_order.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError
import logging

_logger = logging.getLogger(__name__)


class CODOrder(models.Model):
    _name = 'cod.order'
    _description = 'COD Order'
    _order = 'create_date desc'

    sale_order_id = fields.Many2one(
        'sale.order',
        string='Sale Order',
        required=True,
        ondelete='cascade'
    )
    partner_id = fields.Many2one(
        'res.partner',
        string='Customer',
        related='sale_order_id.partner_id',
        store=True
    )

    # Amount Information
    order_amount = fields.Monetary(
        string='Order Amount',
        currency_field='currency_id'
    )
    cod_fee = fields.Monetary(
        string='COD Fee',
        currency_field='currency_id'
    )
    total_collectible = fields.Monetary(
        string='Total Collectible',
        currency_field='currency_id',
        compute='_compute_total_collectible',
        store=True
    )
    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.ref('base.BDT')
    )

    # Status
    state = fields.Selection([
        ('pending_otp', 'Pending OTP'),
        ('otp_verified', 'OTP Verified'),
        ('confirmed', 'Confirmed'),
        ('out_for_delivery', 'Out for Delivery'),
        ('collected', 'Collected'),
        ('failed', 'Failed'),
        ('cancelled', 'Cancelled'),
    ], string='Status', default='pending_otp')

    # OTP Information
    otp_code = fields.Char(string='OTP Code', groups='base.group_system')
    otp_sent_at = fields.Datetime(string='OTP Sent At')
    otp_verified_at = fields.Datetime(string='OTP Verified At')
    otp_attempts = fields.Integer(string='OTP Attempts', default=0)

    # Fraud Information
    fraud_score = fields.Integer(string='Fraud Score', default=0)
    fraud_flags = fields.Text(string='Fraud Flags')
    is_flagged = fields.Boolean(string='Is Flagged', default=False)

    # Collection Information
    driver_id = fields.Many2one('hr.employee', string='Driver')
    collected_amount = fields.Monetary(
        string='Collected Amount',
        currency_field='currency_id'
    )
    collected_at = fields.Datetime(string='Collected At')
    collection_note = fields.Text(string='Collection Notes')

    # Settlement Information
    settlement_id = fields.Many2one('cod.settlement', string='Settlement')
    is_settled = fields.Boolean(string='Is Settled', default=False)

    @api.depends('order_amount', 'cod_fee')
    def _compute_total_collectible(self):
        for order in self:
            order.total_collectible = order.order_amount + order.cod_fee

    def action_send_otp(self):
        """Send OTP to customer."""
        self.ensure_one()
        otp_service = self.env['cod.otp.service']
        return otp_service.send_otp(self)

    def action_verify_otp(self, otp_code):
        """Verify OTP code."""
        self.ensure_one()
        otp_service = self.env['cod.otp.service']
        return otp_service.verify_otp(self, otp_code)

    def action_mark_collected(self, collected_amount, driver_id=None, notes=None):
        """Mark order as collected."""
        self.ensure_one()
        self.write({
            'state': 'collected',
            'collected_amount': collected_amount,
            'collected_at': fields.Datetime.now(),
            'driver_id': driver_id,
            'collection_note': notes,
        })
        # Update sale order
        self.sale_order_id.write({'cod_collected': True})
```

#### Dev 2 (Full-Stack) - 8 Hours

**Task 2.1: OTP Service (4h)**

```python
# File: smart_dairy_payment_cod/services/otp_service.py

import random
import string
from datetime import datetime, timedelta
from odoo import models, api, fields
from odoo.exceptions import UserError
import logging

_logger = logging.getLogger(__name__)


class CODOTPService(models.AbstractModel):
    _name = 'cod.otp.service'
    _description = 'COD OTP Service'

    @api.model
    def generate_otp(self, length=6):
        """Generate random OTP code."""
        return ''.join(random.choices(string.digits, k=length))

    @api.model
    def send_otp(self, cod_order):
        """
        Send OTP to customer for COD order verification.

        Args:
            cod_order: cod.order record

        Returns:
            dict with result
        """
        config = self.env['cod.configuration'].get_active_config()

        # Check if OTP already sent and not expired
        if cod_order.otp_sent_at:
            expiry = cod_order.otp_sent_at + timedelta(minutes=config.otp_expiry_minutes)
            if datetime.now() < expiry:
                return {
                    'success': False,
                    'error': 'OTP already sent. Please wait before requesting again.',
                    'expires_in': (expiry - datetime.now()).seconds,
                }

        # Check max attempts
        if cod_order.otp_attempts >= config.max_otp_attempts:
            cod_order.write({'state': 'failed'})
            return {
                'success': False,
                'error': 'Maximum OTP attempts exceeded. Order cancelled.',
            }

        # Generate and save OTP
        otp_code = self.generate_otp()
        cod_order.write({
            'otp_code': otp_code,
            'otp_sent_at': fields.Datetime.now(),
            'otp_attempts': cod_order.otp_attempts + 1,
        })

        # Send SMS
        phone = cod_order.partner_id.phone or cod_order.partner_id.mobile
        if not phone:
            return {'success': False, 'error': 'Customer phone number not found'}

        sms_service = self.env['sms.service']
        message = f"আপনার Smart Dairy অর্ডার #{cod_order.sale_order_id.name} এর OTP: {otp_code}। এই কোড {config.otp_expiry_minutes} মিনিটের মধ্যে ব্যবহার করুন।"

        result = sms_service.send_sms(phone, message)

        if result.get('success'):
            _logger.info(f"OTP sent for COD order {cod_order.id}")
            return {
                'success': True,
                'message': 'OTP sent successfully',
                'expires_in': config.otp_expiry_minutes * 60,
            }
        else:
            _logger.error(f"Failed to send OTP for COD order {cod_order.id}")
            return {'success': False, 'error': 'Failed to send OTP'}

    @api.model
    def verify_otp(self, cod_order, otp_code):
        """
        Verify OTP code for COD order.

        Args:
            cod_order: cod.order record
            otp_code: OTP code to verify

        Returns:
            dict with verification result
        """
        config = self.env['cod.configuration'].get_active_config()

        # Check if OTP exists
        if not cod_order.otp_code:
            return {'success': False, 'error': 'No OTP generated for this order'}

        # Check expiry
        if cod_order.otp_sent_at:
            expiry = cod_order.otp_sent_at + timedelta(minutes=config.otp_expiry_minutes)
            if datetime.now() > expiry:
                return {'success': False, 'error': 'OTP has expired. Please request a new one.'}

        # Verify OTP
        if cod_order.otp_code == otp_code:
            cod_order.write({
                'state': 'otp_verified',
                'otp_verified_at': fields.Datetime.now(),
            })
            _logger.info(f"OTP verified for COD order {cod_order.id}")
            return {'success': True, 'message': 'OTP verified successfully'}
        else:
            remaining_attempts = config.max_otp_attempts - cod_order.otp_attempts
            return {
                'success': False,
                'error': f'Invalid OTP. {remaining_attempts} attempts remaining.',
                'remaining_attempts': remaining_attempts,
            }

    @api.model
    def resend_otp(self, cod_order):
        """Resend OTP for COD order."""
        # Clear previous OTP
        cod_order.write({'otp_code': False, 'otp_sent_at': False})
        return self.send_otp(cod_order)
```

**Task 2.2: COD Fee Calculator (2h)**

```python
# File: smart_dairy_payment_cod/services/cod_fee_calculator.py

from odoo import models, api
import logging

_logger = logging.getLogger(__name__)


class CODFeeCalculator(models.AbstractModel):
    _name = 'cod.fee.calculator'
    _description = 'COD Fee Calculator'

    @api.model
    def calculate_fee(self, order_amount, delivery_zone=None):
        """
        Calculate COD fee for an order.

        Args:
            order_amount: Order total amount
            delivery_zone: Optional delivery zone for zone-specific fees

        Returns:
            float: COD fee amount
        """
        config = self.env['cod.configuration'].get_active_config()

        if not config:
            _logger.warning("No active COD configuration found")
            return 0.0

        # Check zone-specific fee
        if delivery_zone and delivery_zone.cod_fee:
            return delivery_zone.cod_fee

        # Calculate based on fee type
        if config.cod_fee_type == 'fixed':
            return config.cod_fee_amount

        elif config.cod_fee_type == 'percentage':
            return order_amount * (config.cod_fee_percentage / 100)

        elif config.cod_fee_type == 'tiered':
            return self._calculate_tiered_fee(order_amount, config)

        return 0.0

    def _calculate_tiered_fee(self, order_amount, config):
        """Calculate tiered COD fee based on order amount."""
        # Get tiered fee rules
        tiers = self.env['cod.fee.tier'].search([
            ('config_id', '=', config.id),
            ('min_amount', '<=', order_amount),
        ], order='min_amount desc', limit=1)

        if tiers:
            tier = tiers[0]
            if tier.fee_type == 'fixed':
                return tier.fee_amount
            else:
                return order_amount * (tier.fee_percentage / 100)

        return config.cod_fee_amount  # Default fee


class CODFeeTier(models.Model):
    _name = 'cod.fee.tier'
    _description = 'COD Fee Tier'
    _order = 'min_amount'

    config_id = fields.Many2one('cod.configuration', string='Configuration')
    min_amount = fields.Monetary(string='Minimum Amount', currency_field='currency_id')
    max_amount = fields.Monetary(string='Maximum Amount', currency_field='currency_id')
    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.ref('base.BDT'))
    fee_type = fields.Selection([
        ('fixed', 'Fixed'),
        ('percentage', 'Percentage'),
    ], default='fixed')
    fee_amount = fields.Monetary(string='Fee Amount', currency_field='currency_id')
    fee_percentage = fields.Float(string='Fee Percentage')
```

**Task 2.3: API Logger (2h)**

```python
# File: smart_dairy_payment_cod/models/cod_api_log.py

from odoo import models, fields


class CODAPILog(models.Model):
    _name = 'cod.api.log'
    _description = 'COD API Log'
    _order = 'create_date desc'

    cod_order_id = fields.Many2one('cod.order', string='COD Order')
    action = fields.Selection([
        ('eligibility_check', 'Eligibility Check'),
        ('otp_send', 'OTP Send'),
        ('otp_verify', 'OTP Verify'),
        ('fraud_check', 'Fraud Check'),
        ('collection', 'Collection'),
    ], string='Action')
    request_data = fields.Text(string='Request Data')
    response_data = fields.Text(string='Response Data')
    status = fields.Selection([
        ('success', 'Success'),
        ('error', 'Error'),
    ], string='Status')
    error_message = fields.Text(string='Error Message')
    duration_ms = fields.Integer(string='Duration (ms)')
```

#### Dev 3 (Frontend/Mobile Lead) - 8 Hours

**Task 3.1: COD Selection UI Component (4h)**

```javascript
/** @odoo-module **/
// File: smart_dairy_payment_cod/static/src/js/cod_payment.js

import { Component, useState, onMounted } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";
import { _t } from "@web/core/l10n/translation";

export class CODPaymentForm extends Component {
    static template = "smart_dairy_payment_cod.PaymentForm";
    static props = {
        orderId: { type: Number, required: true },
        amount: { type: Number, required: true },
        customerId: { type: Number, required: true },
        onSuccess: { type: Function, optional: true },
        onError: { type: Function, optional: true },
    };

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            loading: false,
            step: 'check', // check, otp, verified, error
            eligible: null,
            eligibilityMessage: '',
            codFee: 0,
            totalAmount: 0,
            otpSent: false,
            otpCode: '',
            otpExpiresIn: 0,
            otpError: null,
            remainingAttempts: 3,
        });

        onMounted(() => this.checkEligibility());
    }

    async checkEligibility() {
        this.state.loading = true;

        try {
            const result = await this.rpc('/payment/cod/check-eligibility', {
                order_id: this.props.orderId,
                customer_id: this.props.customerId,
                amount: this.props.amount,
            });

            this.state.eligible = result.eligible;
            this.state.eligibilityMessage = result.message;

            if (result.eligible) {
                this.state.codFee = result.cod_fee;
                this.state.totalAmount = this.props.amount + result.cod_fee;
                this.state.step = result.otp_required ? 'otp' : 'verified';

                if (result.otp_required) {
                    this.sendOTP();
                }
            } else {
                this.state.step = 'error';
            }
        } catch (error) {
            this.state.eligible = false;
            this.state.step = 'error';
            this.state.eligibilityMessage = error.message || _t("Failed to check eligibility");
        } finally {
            this.state.loading = false;
        }
    }

    async sendOTP() {
        this.state.loading = true;

        try {
            const result = await this.rpc('/payment/cod/send-otp', {
                order_id: this.props.orderId,
            });

            if (result.success) {
                this.state.otpSent = true;
                this.state.otpExpiresIn = result.expires_in;
                this.startCountdown();
                this.notification.add(_t("OTP sent to your phone"), { type: "success" });
            } else {
                this.state.otpError = result.error;
            }
        } catch (error) {
            this.state.otpError = error.message;
        } finally {
            this.state.loading = false;
        }
    }

    startCountdown() {
        const interval = setInterval(() => {
            if (this.state.otpExpiresIn > 0) {
                this.state.otpExpiresIn--;
            } else {
                clearInterval(interval);
            }
        }, 1000);
    }

    formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    }

    async verifyOTP() {
        if (this.state.otpCode.length !== 6) {
            this.state.otpError = _t("Please enter 6-digit OTP");
            return;
        }

        this.state.loading = true;
        this.state.otpError = null;

        try {
            const result = await this.rpc('/payment/cod/verify-otp', {
                order_id: this.props.orderId,
                otp_code: this.state.otpCode,
            });

            if (result.success) {
                this.state.step = 'verified';
                this.notification.add(_t("OTP verified successfully"), { type: "success" });

                if (this.props.onSuccess) {
                    this.props.onSuccess({
                        paymentMethod: 'cod',
                        codFee: this.state.codFee,
                        totalAmount: this.state.totalAmount,
                    });
                }
            } else {
                this.state.otpError = result.error;
                this.state.remainingAttempts = result.remaining_attempts || 0;
            }
        } catch (error) {
            this.state.otpError = error.message;
        } finally {
            this.state.loading = false;
        }
    }

    async resendOTP() {
        this.state.otpCode = '';
        this.state.otpError = null;
        await this.sendOTP();
    }
}
```

**Task 3.2: COD Template (2h)**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- File: smart_dairy_payment_cod/static/src/xml/cod_templates.xml -->

<templates xml:space="preserve">
    <t t-name="smart_dairy_payment_cod.PaymentForm">
        <div class="cod-payment-form">
            <div class="cod-header text-center mb-4">
                <i class="fa fa-money fa-3x text-success mb-2"/>
                <h5>Cash on Delivery</h5>
            </div>

            <!-- Loading State -->
            <t t-if="state.loading and state.step === 'check'">
                <div class="text-center py-4">
                    <div class="spinner-border text-success"/>
                    <p class="mt-2">Checking eligibility...</p>
                </div>
            </t>

            <!-- Not Eligible -->
            <t t-if="state.step === 'error'">
                <div class="alert alert-danger text-center">
                    <i class="fa fa-exclamation-circle fa-2x mb-2"/>
                    <p class="mb-0" t-esc="state.eligibilityMessage"/>
                </div>
                <p class="text-center text-muted mt-3">
                    Please choose another payment method or contact support.
                </p>
            </t>

            <!-- OTP Verification Step -->
            <t t-if="state.step === 'otp'">
                <div class="cod-amount-display p-3 mb-4 bg-light rounded">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Order Amount:</span>
                        <span>BDT <t t-esc="props.amount.toLocaleString()"/></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>COD Fee:</span>
                        <span>BDT <t t-esc="state.codFee.toLocaleString()"/></span>
                    </div>
                    <hr/>
                    <div class="d-flex justify-content-between">
                        <strong>Total Payable:</strong>
                        <strong class="text-success">
                            BDT <t t-esc="state.totalAmount.toLocaleString()"/>
                        </strong>
                    </div>
                </div>

                <div class="otp-section">
                    <p class="text-center text-muted mb-3">
                        Enter the 6-digit OTP sent to your phone
                    </p>

                    <div class="otp-input-group mb-3">
                        <input type="text"
                               class="form-control form-control-lg text-center"
                               t-att-class="{'is-invalid': state.otpError}"
                               maxlength="6"
                               placeholder="------"
                               t-model="state.otpCode"
                               t-on-input="(e) => { state.otpCode = e.target.value.replace(/\\D/g, ''); }"/>
                        <t t-if="state.otpError">
                            <div class="invalid-feedback" t-esc="state.otpError"/>
                        </t>
                    </div>

                    <t t-if="state.otpExpiresIn > 0">
                        <p class="text-center text-muted small">
                            OTP expires in <strong t-esc="formatTime(state.otpExpiresIn)"/>
                        </p>
                    </t>
                    <t t-else="">
                        <p class="text-center">
                            <button type="button"
                                    class="btn btn-link"
                                    t-on-click="resendOTP">
                                Resend OTP
                            </button>
                        </p>
                    </t>

                    <button type="button"
                            class="btn btn-success btn-lg w-100"
                            t-att-disabled="state.loading || state.otpCode.length !== 6"
                            t-on-click="verifyOTP">
                        <t t-if="state.loading">
                            <span class="spinner-border spinner-border-sm me-2"/>
                        </t>
                        Verify &amp; Confirm Order
                    </button>
                </div>
            </t>

            <!-- Verified/Success -->
            <t t-if="state.step === 'verified'">
                <div class="text-center py-4">
                    <i class="fa fa-check-circle fa-4x text-success mb-3"/>
                    <h5 class="text-success">Order Confirmed!</h5>
                    <p class="text-muted">
                        Please keep BDT <strong t-esc="state.totalAmount.toLocaleString()"/>
                        ready for the delivery person.
                    </p>
                </div>
            </t>

            <!-- Info Note -->
            <div class="cod-info mt-4 p-3 bg-light rounded small">
                <h6 class="mb-2"><i class="fa fa-info-circle me-1"/> Important</h6>
                <ul class="mb-0 ps-3">
                    <li>Pay cash to delivery person upon receiving your order</li>
                    <li>Please have exact change ready if possible</li>
                    <li>Collect receipt after payment</li>
                </ul>
            </div>
        </div>
    </t>
</templates>
```

**Task 3.3: COD Styling (2h)**

```css
/* File: smart_dairy_payment_cod/static/src/css/cod_payment.css */

.cod-payment-form {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
}

.cod-header i {
    color: #28a745;
}

.cod-amount-display {
    border: 1px solid #e9ecef;
}

.otp-input-group input {
    letter-spacing: 10px;
    font-size: 24px;
    font-weight: bold;
}

.otp-input-group input::placeholder {
    letter-spacing: 5px;
    color: #ccc;
}

.cod-info {
    border-left: 4px solid #28a745;
}

.cod-info h6 {
    color: #28a745;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover:not(:disabled) {
    background-color: #218838;
    border-color: #1e7e34;
}
```

#### End of Day 481 Deliverables

- [ ] Module structure created
- [ ] COD configuration model completed
- [ ] COD order model implemented
- [ ] OTP service created
- [ ] COD fee calculator implemented
- [ ] COD UI component created

---

### Day 482-483: Eligibility & Fraud System

#### Dev 1 (Backend Lead)

**Customer Eligibility Service**

```python
# File: smart_dairy_payment_cod/services/eligibility_service.py

from odoo import models, api
from datetime import datetime, timedelta
import logging

_logger = logging.getLogger(__name__)


class CODEligibilityService(models.AbstractModel):
    _name = 'cod.eligibility.service'
    _description = 'COD Eligibility Service'

    @api.model
    def check_eligibility(self, partner, order_amount, delivery_zone=None):
        """
        Check if customer is eligible for COD.

        Args:
            partner: res.partner record
            order_amount: Order amount
            delivery_zone: Optional delivery zone

        Returns:
            dict with eligibility result
        """
        config = self.env['cod.configuration'].get_active_config()

        if not config or not config.cod_enabled:
            return {
                'eligible': False,
                'reason': 'cod_disabled',
                'message': 'Cash on Delivery is currently not available',
            }

        # Check delivery zone COD availability
        if delivery_zone and not delivery_zone.cod_available:
            return {
                'eligible': False,
                'reason': 'zone_not_available',
                'message': 'COD is not available for your delivery area',
            }

        # Check order amount limits
        if order_amount < config.min_order_amount:
            return {
                'eligible': False,
                'reason': 'below_minimum',
                'message': f'Minimum order amount for COD is BDT {config.min_order_amount}',
            }

        if order_amount > config.max_order_amount:
            return {
                'eligible': False,
                'reason': 'above_maximum',
                'message': f'Maximum order amount for COD is BDT {config.max_order_amount}',
            }

        # Check customer-specific limit
        customer_limit = self._get_customer_cod_limit(partner, config)
        if order_amount > customer_limit:
            return {
                'eligible': False,
                'reason': 'exceeds_customer_limit',
                'message': f'Your COD limit is BDT {customer_limit}',
            }

        # Check pending COD orders
        pending_count = self._get_pending_cod_count(partner)
        if pending_count >= config.max_pending_cod_orders:
            return {
                'eligible': False,
                'reason': 'too_many_pending',
                'message': f'You have {pending_count} pending COD orders. Please complete them first.',
            }

        # Check if customer is blacklisted
        if self._is_customer_blacklisted(partner):
            return {
                'eligible': False,
                'reason': 'blacklisted',
                'message': 'COD is not available for your account',
            }

        # Calculate COD fee
        fee_calculator = self.env['cod.fee.calculator']
        cod_fee = fee_calculator.calculate_fee(order_amount, delivery_zone)

        # Determine if OTP is required
        otp_required = (
            config.otp_required and
            order_amount >= config.otp_threshold_amount
        )

        return {
            'eligible': True,
            'cod_fee': cod_fee,
            'total_amount': order_amount + cod_fee,
            'otp_required': otp_required,
            'customer_limit': customer_limit,
            'message': 'COD is available for this order',
        }

    def _get_customer_cod_limit(self, partner, config):
        """Get COD limit for customer based on trust level."""
        # Check if trusted customer (based on order history)
        completed_orders = self.env['sale.order'].search_count([
            ('partner_id', '=', partner.id),
            ('state', 'in', ['sale', 'done']),
            ('payment_term_id.name', 'ilike', 'cod'),
        ])

        if completed_orders >= 5:
            return config.trusted_customer_cod_limit

        return config.new_customer_cod_limit

    def _get_pending_cod_count(self, partner):
        """Get count of pending COD orders for customer."""
        return self.env['cod.order'].search_count([
            ('partner_id', '=', partner.id),
            ('state', 'in', ['pending_otp', 'otp_verified', 'confirmed', 'out_for_delivery']),
        ])

    def _is_customer_blacklisted(self, partner):
        """Check if customer is COD blacklisted."""
        return partner.cod_blacklisted or False
```

**Fraud Scoring Engine**

```python
# File: smart_dairy_payment_cod/services/fraud_scoring.py

from odoo import models, api, fields
from datetime import datetime, timedelta
import logging

_logger = logging.getLogger(__name__)


class FraudScoringEngine(models.AbstractModel):
    _name = 'cod.fraud.scoring'
    _description = 'COD Fraud Scoring Engine'

    # Fraud rule weights
    RULE_WEIGHTS = {
        'new_customer': 20,
        'high_value_order': 15,
        'multiple_pending_orders': 25,
        'address_mismatch': 30,
        'velocity_check': 35,
        'phone_verification': 20,
        'previous_cancellations': 40,
        'blacklisted_area': 50,
    }

    @api.model
    def calculate_fraud_score(self, partner, order, delivery_address=None):
        """
        Calculate fraud score for COD order.

        Args:
            partner: res.partner record
            order: sale.order record
            delivery_address: Optional delivery address

        Returns:
            dict with fraud score and flags
        """
        score = 0
        flags = []

        # Rule 1: New customer
        if self._is_new_customer(partner):
            score += self.RULE_WEIGHTS['new_customer']
            flags.append('new_customer')

        # Rule 2: High value order
        config = self.env['cod.configuration'].get_active_config()
        if order.amount_total > (config.max_order_amount * 0.7):
            score += self.RULE_WEIGHTS['high_value_order']
            flags.append('high_value_order')

        # Rule 3: Multiple pending orders
        pending_count = self._get_pending_order_count(partner)
        if pending_count > 0:
            score += self.RULE_WEIGHTS['multiple_pending_orders'] * pending_count
            flags.append(f'pending_orders_{pending_count}')

        # Rule 4: Address mismatch
        if delivery_address and self._has_address_mismatch(partner, delivery_address):
            score += self.RULE_WEIGHTS['address_mismatch']
            flags.append('address_mismatch')

        # Rule 5: Velocity check (orders in last 24h)
        recent_orders = self._get_recent_order_count(partner, hours=24)
        if recent_orders >= 3:
            score += self.RULE_WEIGHTS['velocity_check']
            flags.append(f'velocity_{recent_orders}_orders_24h')

        # Rule 6: Phone verification status
        if not self._is_phone_verified(partner):
            score += self.RULE_WEIGHTS['phone_verification']
            flags.append('phone_not_verified')

        # Rule 7: Previous cancellations/failures
        cancellation_rate = self._get_cancellation_rate(partner)
        if cancellation_rate > 0.3:
            score += self.RULE_WEIGHTS['previous_cancellations']
            flags.append(f'cancellation_rate_{int(cancellation_rate*100)}%')

        # Cap score at 100
        score = min(score, 100)

        # Determine action based on score
        action = 'allow'
        if score >= config.auto_reject_score:
            action = 'reject'
        elif score >= config.fraud_threshold_score:
            action = 'flag'

        return {
            'score': score,
            'flags': flags,
            'action': action,
            'is_flagged': action in ['flag', 'reject'],
        }

    def _is_new_customer(self, partner):
        """Check if customer is new (less than 30 days)."""
        if not partner.create_date:
            return True
        return partner.create_date > (datetime.now() - timedelta(days=30))

    def _get_pending_order_count(self, partner):
        """Get pending COD order count."""
        return self.env['cod.order'].search_count([
            ('partner_id', '=', partner.id),
            ('state', 'in', ['pending_otp', 'otp_verified', 'confirmed']),
        ])

    def _has_address_mismatch(self, partner, delivery_address):
        """Check if delivery address differs significantly from customer address."""
        if not partner.city or not delivery_address.get('city'):
            return False
        return partner.city.lower() != delivery_address.get('city', '').lower()

    def _get_recent_order_count(self, partner, hours=24):
        """Get order count in recent hours."""
        since = datetime.now() - timedelta(hours=hours)
        return self.env['sale.order'].search_count([
            ('partner_id', '=', partner.id),
            ('create_date', '>=', since),
        ])

    def _is_phone_verified(self, partner):
        """Check if customer phone is verified."""
        return partner.phone_verified or False

    def _get_cancellation_rate(self, partner):
        """Get COD order cancellation rate."""
        total_cod = self.env['cod.order'].search_count([
            ('partner_id', '=', partner.id),
        ])
        if total_cod == 0:
            return 0

        cancelled = self.env['cod.order'].search_count([
            ('partner_id', '=', partner.id),
            ('state', 'in', ['failed', 'cancelled']),
        ])

        return cancelled / total_cod
```

### Day 484-486: Driver Collection & Settlement

#### Dev 2 (Full-Stack)

**Driver Collection Service**

```python
# File: smart_dairy_payment_cod/services/collection_service.py

from odoo import models, api, fields
from odoo.exceptions import UserError
import logging

_logger = logging.getLogger(__name__)


class CODCollectionService(models.AbstractModel):
    _name = 'cod.collection.service'
    _description = 'COD Collection Service'

    @api.model
    def record_collection(self, cod_order_id, collected_amount, driver_id=None,
                         collection_method='cash', notes=None, proof_image=None):
        """
        Record COD collection by driver.

        Args:
            cod_order_id: cod.order ID
            collected_amount: Amount collected
            driver_id: Driver employee ID
            collection_method: cash, card, mfs
            notes: Collection notes
            proof_image: Base64 encoded proof image

        Returns:
            dict with collection result
        """
        cod_order = self.env['cod.order'].browse(cod_order_id)

        if not cod_order.exists():
            return {'success': False, 'error': 'COD order not found'}

        if cod_order.state == 'collected':
            return {'success': False, 'error': 'Order already collected'}

        if cod_order.state not in ['confirmed', 'out_for_delivery']:
            return {'success': False, 'error': f'Cannot collect order in state: {cod_order.state}'}

        # Verify amount
        if collected_amount != cod_order.total_collectible:
            # Allow small variance (for change issues)
            variance = abs(collected_amount - cod_order.total_collectible)
            if variance > 10:  # More than 10 BDT variance
                return {
                    'success': False,
                    'error': f'Amount mismatch. Expected: {cod_order.total_collectible}, Received: {collected_amount}',
                }

        # Create collection record
        collection = self.env['cod.collection'].create({
            'cod_order_id': cod_order.id,
            'driver_id': driver_id,
            'collected_amount': collected_amount,
            'collection_method': collection_method,
            'notes': notes,
            'proof_image': proof_image,
            'collected_at': fields.Datetime.now(),
        })

        # Update COD order
        cod_order.write({
            'state': 'collected',
            'collected_amount': collected_amount,
            'collected_at': fields.Datetime.now(),
            'driver_id': driver_id,
            'collection_note': notes,
        })

        # Update sale order
        cod_order.sale_order_id.write({
            'cod_collected': True,
            'cod_collection_date': fields.Date.today(),
        })

        _logger.info(f"COD collected for order {cod_order.id} by driver {driver_id}")

        return {
            'success': True,
            'collection_id': collection.id,
            'message': 'Collection recorded successfully',
        }

    @api.model
    def report_collection_issue(self, cod_order_id, issue_type, description, driver_id=None):
        """
        Report issue with COD collection.

        Args:
            cod_order_id: cod.order ID
            issue_type: Type of issue
            description: Issue description
            driver_id: Driver employee ID

        Returns:
            dict with result
        """
        cod_order = self.env['cod.order'].browse(cod_order_id)

        if not cod_order.exists():
            return {'success': False, 'error': 'COD order not found'}

        issue = self.env['cod.collection.issue'].create({
            'cod_order_id': cod_order.id,
            'driver_id': driver_id,
            'issue_type': issue_type,
            'description': description,
        })

        # Update order state if customer refused
        if issue_type == 'customer_refused':
            cod_order.write({'state': 'failed'})

        return {
            'success': True,
            'issue_id': issue.id,
        }


class CODCollection(models.Model):
    _name = 'cod.collection'
    _description = 'COD Collection Record'
    _order = 'collected_at desc'

    cod_order_id = fields.Many2one('cod.order', string='COD Order', required=True)
    driver_id = fields.Many2one('hr.employee', string='Driver')
    collected_amount = fields.Monetary(
        string='Collected Amount',
        currency_field='currency_id'
    )
    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.ref('base.BDT')
    )
    collection_method = fields.Selection([
        ('cash', 'Cash'),
        ('card', 'Card (POS)'),
        ('mfs', 'Mobile Financial Service'),
    ], string='Collection Method', default='cash')
    collected_at = fields.Datetime(string='Collected At')
    notes = fields.Text(string='Notes')
    proof_image = fields.Binary(string='Proof Image')


class CODCollectionIssue(models.Model):
    _name = 'cod.collection.issue'
    _description = 'COD Collection Issue'
    _order = 'create_date desc'

    cod_order_id = fields.Many2one('cod.order', string='COD Order', required=True)
    driver_id = fields.Many2one('hr.employee', string='Driver')
    issue_type = fields.Selection([
        ('customer_refused', 'Customer Refused'),
        ('customer_not_available', 'Customer Not Available'),
        ('wrong_address', 'Wrong Address'),
        ('payment_issue', 'Payment Issue'),
        ('product_issue', 'Product Issue'),
        ('other', 'Other'),
    ], string='Issue Type', required=True)
    description = fields.Text(string='Description')
    resolution = fields.Text(string='Resolution')
    state = fields.Selection([
        ('open', 'Open'),
        ('resolved', 'Resolved'),
    ], default='open')
```

**COD Settlement Service**

```python
# File: smart_dairy_payment_cod/services/settlement_service.py

from odoo import models, api, fields
from datetime import datetime, timedelta
import logging

_logger = logging.getLogger(__name__)


class CODSettlementService(models.AbstractModel):
    _name = 'cod.settlement.service'
    _description = 'COD Settlement Service'

    @api.model
    def create_settlement(self, driver_id=None, date_from=None, date_to=None):
        """
        Create settlement for collected COD amounts.

        Args:
            driver_id: Optional driver to settle for
            date_from: Start date
            date_to: End date

        Returns:
            settlement record
        """
        if not date_from:
            date_from = datetime.now().replace(hour=0, minute=0, second=0)
        if not date_to:
            date_to = datetime.now()

        domain = [
            ('state', '=', 'collected'),
            ('is_settled', '=', False),
            ('collected_at', '>=', date_from),
            ('collected_at', '<=', date_to),
        ]

        if driver_id:
            domain.append(('driver_id', '=', driver_id))

        cod_orders = self.env['cod.order'].search(domain)

        if not cod_orders:
            return None

        total_amount = sum(cod_orders.mapped('collected_amount'))

        settlement = self.env['cod.settlement'].create({
            'driver_id': driver_id,
            'date_from': date_from,
            'date_to': date_to,
            'total_orders': len(cod_orders),
            'total_amount': total_amount,
            'cod_order_ids': [(6, 0, cod_orders.ids)],
        })

        # Mark orders as settled
        cod_orders.write({
            'is_settled': True,
            'settlement_id': settlement.id,
        })

        return settlement

    @api.model
    def confirm_settlement(self, settlement_id, actual_amount=None, notes=None):
        """Confirm settlement after cash handover."""
        settlement = self.env['cod.settlement'].browse(settlement_id)

        if not settlement.exists():
            return {'success': False, 'error': 'Settlement not found'}

        variance = 0
        if actual_amount:
            variance = actual_amount - settlement.total_amount

        settlement.write({
            'state': 'confirmed',
            'confirmed_at': fields.Datetime.now(),
            'confirmed_by_id': self.env.user.id,
            'actual_amount': actual_amount or settlement.total_amount,
            'variance': variance,
            'notes': notes,
        })

        return {'success': True}


class CODSettlement(models.Model):
    _name = 'cod.settlement'
    _description = 'COD Settlement'
    _order = 'create_date desc'

    name = fields.Char(
        string='Reference',
        readonly=True,
        default=lambda self: self.env['ir.sequence'].next_by_code('cod.settlement')
    )
    driver_id = fields.Many2one('hr.employee', string='Driver')
    date_from = fields.Datetime(string='From Date')
    date_to = fields.Datetime(string='To Date')

    total_orders = fields.Integer(string='Total Orders')
    total_amount = fields.Monetary(
        string='Total Amount',
        currency_field='currency_id'
    )
    actual_amount = fields.Monetary(
        string='Actual Amount',
        currency_field='currency_id'
    )
    variance = fields.Monetary(
        string='Variance',
        currency_field='currency_id'
    )
    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.ref('base.BDT')
    )

    cod_order_ids = fields.One2many('cod.order', 'settlement_id', string='COD Orders')

    state = fields.Selection([
        ('draft', 'Draft'),
        ('confirmed', 'Confirmed'),
        ('cancelled', 'Cancelled'),
    ], default='draft')
    confirmed_at = fields.Datetime(string='Confirmed At')
    confirmed_by_id = fields.Many2one('res.users', string='Confirmed By')
    notes = fields.Text(string='Notes')
```

#### Dev 3 (Frontend/Mobile Lead)

**Driver Collection Flutter App**

```dart
// File: driver_app/lib/features/cod/cod_collection_screen.dart

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

class CODCollectionScreen extends ConsumerStatefulWidget {
  final int codOrderId;
  final double expectedAmount;

  const CODCollectionScreen({
    required this.codOrderId,
    required this.expectedAmount,
    super.key,
  });

  @override
  ConsumerState<CODCollectionScreen> createState() => _CODCollectionScreenState();
}

class _CODCollectionScreenState extends ConsumerState<CODCollectionScreen> {
  final _formKey = GlobalKey<FormState>();
  final _amountController = TextEditingController();
  String _collectionMethod = 'cash';
  String? _notes;
  bool _isLoading = false;

  @override
  void initState() {
    super.initState();
    _amountController.text = widget.expectedAmount.toStringAsFixed(2);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('COD Collection'),
        backgroundColor: Colors.green,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Expected Amount Card
              Card(
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    children: [
                      const Text(
                        'Amount to Collect',
                        style: TextStyle(color: Colors.grey),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        'BDT ${widget.expectedAmount.toStringAsFixed(2)}',
                        style: const TextStyle(
                          fontSize: 32,
                          fontWeight: FontWeight.bold,
                          color: Colors.green,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 24),

              // Collected Amount
              TextFormField(
                controller: _amountController,
                keyboardType: TextInputType.number,
                decoration: const InputDecoration(
                  labelText: 'Collected Amount (BDT)',
                  border: OutlineInputBorder(),
                  prefixIcon: Icon(Icons.money),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter collected amount';
                  }
                  final amount = double.tryParse(value);
                  if (amount == null || amount <= 0) {
                    return 'Please enter a valid amount';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),

              // Collection Method
              const Text(
                'Collection Method',
                style: TextStyle(fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 8),
              Row(
                children: [
                  _buildMethodChip('cash', 'Cash', Icons.money),
                  const SizedBox(width: 8),
                  _buildMethodChip('card', 'Card', Icons.credit_card),
                  const SizedBox(width: 8),
                  _buildMethodChip('mfs', 'MFS', Icons.phone_android),
                ],
              ),
              const SizedBox(height: 16),

              // Notes
              TextFormField(
                maxLines: 3,
                decoration: const InputDecoration(
                  labelText: 'Notes (Optional)',
                  border: OutlineInputBorder(),
                  hintText: 'Any notes about the collection...',
                ),
                onChanged: (value) => _notes = value,
              ),
              const SizedBox(height: 24),

              // Submit Button
              SizedBox(
                width: double.infinity,
                height: 50,
                child: ElevatedButton(
                  onPressed: _isLoading ? null : _submitCollection,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.green,
                  ),
                  child: _isLoading
                      ? const CircularProgressIndicator(color: Colors.white)
                      : const Text(
                          'Confirm Collection',
                          style: TextStyle(fontSize: 18),
                        ),
                ),
              ),
              const SizedBox(height: 16),

              // Report Issue Button
              TextButton.icon(
                onPressed: _reportIssue,
                icon: const Icon(Icons.report_problem, color: Colors.orange),
                label: const Text('Report Issue'),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildMethodChip(String value, String label, IconData icon) {
    final isSelected = _collectionMethod == value;
    return FilterChip(
      selected: isSelected,
      label: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 18),
          const SizedBox(width: 4),
          Text(label),
        ],
      ),
      onSelected: (_) => setState(() => _collectionMethod = value),
      selectedColor: Colors.green.shade100,
    );
  }

  Future<void> _submitCollection() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);

    try {
      final amount = double.parse(_amountController.text);
      await ref.read(codServiceProvider).recordCollection(
        codOrderId: widget.codOrderId,
        collectedAmount: amount,
        collectionMethod: _collectionMethod,
        notes: _notes,
      );

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Collection recorded successfully!'),
            backgroundColor: Colors.green,
          ),
        );
        Navigator.pop(context, true);
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Error: ${e.toString()}'),
            backgroundColor: Colors.red,
          ),
        );
      }
    } finally {
      setState(() => _isLoading = false);
    }
  }

  void _reportIssue() {
    showModalBottomSheet(
      context: context,
      builder: (context) => CODIssueReportSheet(
        codOrderId: widget.codOrderId,
      ),
    );
  }
}
```

---

### Day 487-490: Unified API & Testing

*(Complete unified payment API, controllers, and comprehensive testing)*

---

## 6. Technical Specifications

### 6.1 API Endpoints

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/payment/cod/check-eligibility` | POST | User | Check COD eligibility |
| `/payment/cod/send-otp` | POST | User | Send OTP for verification |
| `/payment/cod/verify-otp` | POST | User | Verify OTP code |
| `/payment/cod/confirm` | POST | User | Confirm COD order |
| `/api/v1/cod/collect` | POST | Driver | Record collection |
| `/api/v1/cod/issue` | POST | Driver | Report collection issue |
| `/api/v1/cod/settlements` | GET | Admin | List settlements |

### 6.2 COD Order States

```
┌──────────────┐     ┌─────────────┐     ┌───────────┐
│ pending_otp  │────▶│ otp_verified│────▶│ confirmed │
└──────────────┘     └─────────────┘     └─────┬─────┘
                                               │
                                               ▼
                                    ┌──────────────────┐
                                    │ out_for_delivery │
                                    └────────┬─────────┘
                                             │
                              ┌──────────────┼──────────────┐
                              ▼              ▼              ▼
                        ┌──────────┐   ┌──────────┐   ┌───────────┐
                        │ collected│   │  failed  │   │ cancelled │
                        └──────────┘   └──────────┘   └───────────┘
```

---

## 7. Database Schema

### 7.1 COD Tables

```sql
-- COD Configuration
CREATE TABLE cod_configuration (
    id SERIAL PRIMARY KEY,
    name VARCHAR(128),
    active BOOLEAN DEFAULT TRUE,
    company_id INTEGER REFERENCES res_company(id),
    cod_enabled BOOLEAN DEFAULT TRUE,
    min_order_amount DECIMAL(10,2),
    max_order_amount DECIMAL(10,2),
    cod_fee_type VARCHAR(20),
    cod_fee_amount DECIMAL(10,2),
    otp_required BOOLEAN DEFAULT TRUE,
    fraud_threshold_score INTEGER DEFAULT 70,
    create_date TIMESTAMP DEFAULT NOW()
);

-- COD Orders
CREATE TABLE cod_order (
    id SERIAL PRIMARY KEY,
    sale_order_id INTEGER REFERENCES sale_order(id),
    partner_id INTEGER REFERENCES res_partner(id),
    order_amount DECIMAL(10,2),
    cod_fee DECIMAL(10,2),
    state VARCHAR(20),
    otp_code VARCHAR(6),
    otp_sent_at TIMESTAMP,
    fraud_score INTEGER DEFAULT 0,
    driver_id INTEGER REFERENCES hr_employee(id),
    collected_amount DECIMAL(10,2),
    collected_at TIMESTAMP,
    settlement_id INTEGER,
    is_settled BOOLEAN DEFAULT FALSE,
    create_date TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_cod_order_partner ON cod_order(partner_id);
CREATE INDEX idx_cod_order_state ON cod_order(state);
CREATE INDEX idx_cod_order_settlement ON cod_order(settlement_id);
```

---

## 8. API Documentation

### 8.1 Check Eligibility

```json
POST /payment/cod/check-eligibility
{
    "order_id": 12345,
    "customer_id": 67890,
    "amount": 2500.00
}

Response:
{
    "eligible": true,
    "cod_fee": 30.00,
    "total_amount": 2530.00,
    "otp_required": true,
    "message": "COD is available for this order"
}
```

### 8.2 Record Collection (Driver)

```json
POST /api/v1/cod/collect
Headers: Authorization: Bearer <driver_token>
{
    "cod_order_id": 123,
    "collected_amount": 2530.00,
    "collection_method": "cash",
    "notes": "Customer paid exact amount"
}

Response:
{
    "success": true,
    "collection_id": 456,
    "message": "Collection recorded successfully"
}
```

---

## 9. Fraud Prevention System

### 9.1 Fraud Scoring Rules

| Rule | Weight | Trigger |
|------|--------|---------|
| New Customer | 20 | Account < 30 days |
| High Value | 15 | Order > 70% of max |
| Pending Orders | 25 | Each pending order |
| Address Mismatch | 30 | Delivery ≠ billing |
| Velocity | 35 | >3 orders in 24h |
| Phone Unverified | 20 | No OTP history |
| Cancellations | 40 | Rate > 30% |

### 9.2 Score Thresholds

| Score Range | Action |
|-------------|--------|
| 0-69 | Allow (normal) |
| 70-89 | Flag for review |
| 90-100 | Auto-reject |

---

## 10. Testing Requirements

### 10.1 Test Coverage

| Component | Target |
|-----------|--------|
| Eligibility Service | > 90% |
| OTP Service | > 95% |
| Fraud Scoring | > 90% |
| Collection Service | > 85% |
| Settlement Service | > 85% |

### 10.2 Test Scenarios

```python
class TestCODEligibility(TransactionCase):

    def test_eligible_customer(self):
        """Test eligible customer can use COD."""
        pass

    def test_new_customer_limit(self):
        """Test new customer COD limit."""
        pass

    def test_max_pending_orders(self):
        """Test max pending orders restriction."""
        pass

    def test_blacklisted_customer(self):
        """Test blacklisted customer rejected."""
        pass


class TestFraudScoring(TransactionCase):

    def test_new_customer_score(self):
        """Test new customer gets fraud score."""
        pass

    def test_velocity_detection(self):
        """Test rapid ordering detection."""
        pass

    def test_auto_reject_high_score(self):
        """Test auto-rejection for high fraud score."""
        pass
```

---

## 11. Risk & Mitigation

| Risk | Impact | Mitigation |
|------|--------|------------|
| OTP delivery failure | High | Multiple SMS providers, retry |
| Fraud bypass | High | Multi-factor scoring, review |
| Driver theft | High | Real-time tracking, settlements |
| Amount mismatch | Medium | Strict validation, variance alerts |
| Customer refusal | Medium | Penalty system, blacklisting |

---

## 12. Dependencies

### 12.1 Internal

| Dependency | Purpose |
|------------|---------|
| `smart_dairy_sms` | OTP delivery |
| `smart_dairy_delivery` | Delivery integration |
| `hr` | Driver management |

### 12.2 External

| Package | Purpose |
|---------|---------|
| SMS Gateway API | OTP delivery |

---

## 13. Sign-off Checklist

### 13.1 COD Configuration

- [ ] Configuration model complete
- [ ] Fee calculation working
- [ ] Limits enforced correctly

### 13.2 Eligibility & Fraud

- [ ] Eligibility checks accurate
- [ ] Fraud scoring functioning
- [ ] Blacklist enforcement working

### 13.3 OTP System

- [ ] OTP generation secure
- [ ] SMS delivery reliable
- [ ] Verification working

### 13.4 Collection & Settlement

- [ ] Driver collection UI complete
- [ ] Collection recording accurate
- [ ] Settlement processing correct

---

**Document End**

*Milestone 74: COD & Payment Processing*
*Days 481-490 | Phase 8: Operations - Payment & Logistics*
*Smart Dairy Digital Smart Portal + ERP System*
