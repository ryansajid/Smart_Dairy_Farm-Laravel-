# Milestone 18: Payment Gateway Integration

## Smart Dairy Digital Portal + ERP Implementation
### Phase 2, Part B: Commerce Foundation
### Days 171-180 (10-Day Sprint)

---

## Document Control

| Attribute | Value |
|-----------|-------|
| **Document ID** | SD-P2-MS18-PAY-v1.0 |
| **Version** | 1.0 |
| **Last Updated** | 2025-01-15 |
| **Status** | Draft |
| **Owner** | Dev 2 (Full-Stack Developer) |
| **Reviewers** | Technical Architect, Project Manager |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Integrate Bangladesh's major payment gateways (bKash, Nagad, Rocket, SSLCommerz) for both B2C and B2B transactions, implement payment reconciliation with accounting, and ensure PCI-DSS compliance for card payments.

### 1.2 Objectives

| # | Objective | Priority | BRD Ref |
|---|-----------|----------|---------|
| 1 | Integrate bKash payment gateway | Critical | FR-CHK-001 |
| 2 | Integrate Nagad payment gateway | Critical | FR-CHK-002 |
| 3 | Integrate Rocket (DBBL) payment | High | FR-CHK-003 |
| 4 | Integrate SSLCommerz multi-payment | Critical | FR-CHK-004 |
| 5 | Implement card payment processing | High | FR-CHK-005 |
| 6 | Build payment reconciliation engine | Critical | FR-CHK-006 |
| 7 | Implement refund processing | High | FR-CHK-007 |
| 8 | Create payment dashboard | Medium | FR-CHK-008 |
| 9 | Configure webhook handlers | Critical | FR-CHK-009 |
| 10 | Implement payment security | Critical | FR-CHK-010 |

### 1.3 Key Deliverables

| Deliverable | Owner | Day |
|-------------|-------|-----|
| Payment provider abstraction layer | Dev 2 | 171 |
| bKash integration | Dev 2 | 172-173 |
| Nagad integration | Dev 2 | 174 |
| Rocket integration | Dev 1 | 175 |
| SSLCommerz integration | Dev 2 | 176-177 |
| Payment reconciliation | Dev 1 | 178 |
| Refund processing | Dev 2 | 179 |
| Dashboard & testing | Dev 3 | 180 |

### 1.4 Prerequisites

- [x] Milestone 17: Public Website completed
- [x] bKash merchant account approved
- [x] Nagad merchant account approved
- [x] SSLCommerz account activated
- [x] SSL certificates installed

### 1.5 Success Criteria

| Criteria | Target | Measurement |
|----------|--------|-------------|
| Payment success rate | >98% | Transaction logs |
| Gateway response time | <3 seconds | API monitoring |
| Reconciliation accuracy | 100% | Daily audit |
| Refund processing time | <24 hours | Process tracking |
| Security compliance | PCI-DSS | Security audit |

---

## 2. Requirement Traceability Matrix

### 2.1 BRD Requirements

| BRD Req ID | Description | Implementation | Day |
|------------|-------------|----------------|-----|
| FR-CHK-001 | bKash payment | `smart_payment.bkash` | 172 |
| FR-CHK-002 | Nagad payment | `smart_payment.nagad` | 174 |
| FR-CHK-003 | Rocket payment | `smart_payment.rocket` | 175 |
| FR-CHK-004 | SSLCommerz | `smart_payment.sslcommerz` | 176 |
| FR-CHK-005 | Card payments | Via SSLCommerz | 177 |
| FR-CHK-006 | Reconciliation | `smart_payment.reconcile` | 178 |

---

## 3. Day-by-Day Breakdown

---

### Day 171: Payment Provider Abstraction Layer

#### Dev 2 Tasks (8h) - Core Payment Framework

**Task 2.1: Payment Provider Base Model (4h)**

```python
# smart_payment/models/payment_provider.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError, UserError
from abc import abstractmethod
import hashlib
import hmac
import json
import logging
import requests
from datetime import datetime

_logger = logging.getLogger(__name__)

class PaymentProvider(models.Model):
    _name = 'smart.payment.provider'
    _description = 'Payment Provider Configuration'
    _order = 'sequence, name'

    name = fields.Char(string='Provider Name', required=True)
    code = fields.Selection([
        ('bkash', 'bKash'),
        ('nagad', 'Nagad'),
        ('rocket', 'Rocket'),
        ('sslcommerz', 'SSLCommerz'),
        ('bank_transfer', 'Bank Transfer'),
        ('cash', 'Cash on Delivery'),
    ], string='Provider Code', required=True)

    sequence = fields.Integer(string='Sequence', default=10)
    active = fields.Boolean(string='Active', default=True)

    # Environment
    environment = fields.Selection([
        ('sandbox', 'Sandbox/Test'),
        ('production', 'Production'),
    ], string='Environment', default='sandbox', required=True)

    # Credentials
    api_key = fields.Char(string='API Key')
    api_secret = fields.Char(string='API Secret')
    merchant_id = fields.Char(string='Merchant ID')
    store_id = fields.Char(string='Store ID')

    # URLs
    base_url = fields.Char(string='Base API URL')
    callback_url = fields.Char(
        string='Callback URL',
        compute='_compute_callback_url'
    )
    ipn_url = fields.Char(
        string='IPN URL',
        compute='_compute_callback_url'
    )

    # Settings
    payment_journal_id = fields.Many2one(
        'account.journal',
        string='Payment Journal',
        domain=[('type', 'in', ['bank', 'cash'])]
    )
    fee_percentage = fields.Float(
        string='Transaction Fee %',
        digits=(5, 2)
    )
    fee_fixed = fields.Float(
        string='Fixed Fee (BDT)',
        digits=(10, 2)
    )

    # Availability
    available_for_b2c = fields.Boolean(string='Available for B2C', default=True)
    available_for_b2b = fields.Boolean(string='Available for B2B', default=True)
    min_amount = fields.Float(string='Minimum Amount')
    max_amount = fields.Float(string='Maximum Amount')

    # Statistics
    transaction_count = fields.Integer(
        string='Transactions',
        compute='_compute_stats'
    )
    total_volume = fields.Float(
        string='Total Volume',
        compute='_compute_stats'
    )
    success_rate = fields.Float(
        string='Success Rate %',
        compute='_compute_stats'
    )

    @api.depends('code')
    def _compute_callback_url(self):
        base_url = self.env['ir.config_parameter'].sudo().get_param('web.base.url')
        for provider in self:
            provider.callback_url = f"{base_url}/payment/{provider.code}/callback"
            provider.ipn_url = f"{base_url}/payment/{provider.code}/ipn"

    @api.depends('code')
    def _compute_stats(self):
        Transaction = self.env['smart.payment.transaction']
        for provider in self:
            transactions = Transaction.search([
                ('provider_id', '=', provider.id)
            ])
            provider.transaction_count = len(transactions)
            provider.total_volume = sum(transactions.mapped('amount'))

            successful = transactions.filtered(
                lambda t: t.state == 'done'
            )
            if transactions:
                provider.success_rate = len(successful) / len(transactions) * 100
            else:
                provider.success_rate = 0

    def _get_api_url(self, endpoint):
        """Get full API URL for endpoint"""
        self.ensure_one()
        return f"{self.base_url.rstrip('/')}/{endpoint.lstrip('/')}"

    def _get_headers(self):
        """Get API request headers - override per provider"""
        return {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        }

    def _sign_request(self, data):
        """Sign API request - override per provider"""
        return data

    def _make_request(self, method, endpoint, data=None, timeout=30):
        """Make API request to payment provider"""
        self.ensure_one()

        url = self._get_api_url(endpoint)
        headers = self._get_headers()

        if data:
            data = self._sign_request(data)

        try:
            if method.upper() == 'GET':
                response = requests.get(
                    url, headers=headers, params=data, timeout=timeout
                )
            else:
                response = requests.post(
                    url, headers=headers, json=data, timeout=timeout
                )

            _logger.info(
                f"Payment API {method} {url}: {response.status_code}"
            )

            return response.json()

        except requests.Timeout:
            _logger.error(f"Payment API timeout: {url}")
            raise UserError("Payment gateway timeout. Please try again.")

        except requests.RequestException as e:
            _logger.error(f"Payment API error: {e}")
            raise UserError("Payment gateway error. Please try again later.")

    def initiate_payment(self, transaction):
        """Initiate payment - must be overridden"""
        raise NotImplementedError(
            f"Payment initiation not implemented for {self.name}"
        )

    def verify_payment(self, transaction_id):
        """Verify payment status - must be overridden"""
        raise NotImplementedError(
            f"Payment verification not implemented for {self.name}"
        )

    def process_refund(self, transaction, amount=None):
        """Process refund - must be overridden"""
        raise NotImplementedError(
            f"Refund processing not implemented for {self.name}"
        )


class PaymentTransaction(models.Model):
    _name = 'smart.payment.transaction'
    _description = 'Payment Transaction'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'create_date desc'

    name = fields.Char(
        string='Reference',
        required=True,
        readonly=True,
        default='New'
    )

    # Provider
    provider_id = fields.Many2one(
        'smart.payment.provider',
        string='Payment Provider',
        required=True
    )
    provider_code = fields.Selection(
        related='provider_id.code',
        store=True
    )

    # Transaction Details
    amount = fields.Float(
        string='Amount',
        required=True,
        digits=(12, 2)
    )
    currency_id = fields.Many2one(
        'res.currency',
        string='Currency',
        default=lambda self: self.env.ref('base.BDT')
    )
    fee_amount = fields.Float(
        string='Transaction Fee',
        digits=(10, 2)
    )

    # State
    state = fields.Selection([
        ('draft', 'Draft'),
        ('pending', 'Pending'),
        ('authorized', 'Authorized'),
        ('done', 'Completed'),
        ('cancelled', 'Cancelled'),
        ('error', 'Error'),
        ('refunded', 'Refunded'),
    ], string='Status', default='draft', tracking=True)

    # Provider References
    provider_reference = fields.Char(
        string='Provider Transaction ID',
        readonly=True
    )
    payment_url = fields.Char(string='Payment URL')

    # Customer
    partner_id = fields.Many2one('res.partner', string='Customer')
    partner_phone = fields.Char(string='Customer Phone')
    partner_email = fields.Char(string='Customer Email')

    # Source Document
    sale_order_id = fields.Many2one('sale.order', string='Sale Order')
    invoice_id = fields.Many2one('account.move', string='Invoice')

    # Response Data
    response_data = fields.Text(string='Response Data')
    error_message = fields.Text(string='Error Message')

    # Timestamps
    initiated_at = fields.Datetime(string='Initiated At')
    completed_at = fields.Datetime(string='Completed At')

    # Accounting
    payment_id = fields.Many2one(
        'account.payment',
        string='Accounting Payment',
        readonly=True
    )

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            if vals.get('name', 'New') == 'New':
                vals['name'] = self.env['ir.sequence'].next_by_code(
                    'smart.payment.transaction'
                ) or 'New'
        return super().create(vals_list)

    def action_initiate(self):
        """Initiate payment with provider"""
        self.ensure_one()

        if self.state != 'draft':
            raise UserError("Can only initiate draft transactions.")

        self.initiated_at = datetime.now()

        try:
            result = self.provider_id.initiate_payment(self)

            self.write({
                'state': 'pending',
                'provider_reference': result.get('transaction_id'),
                'payment_url': result.get('payment_url'),
                'response_data': json.dumps(result),
            })

            return result

        except Exception as e:
            self.write({
                'state': 'error',
                'error_message': str(e),
            })
            raise

    def action_verify(self):
        """Verify payment status with provider"""
        self.ensure_one()

        if self.state not in ['pending', 'authorized']:
            raise UserError("Can only verify pending transactions.")

        try:
            result = self.provider_id.verify_payment(self.provider_reference)

            if result.get('status') == 'success':
                self._mark_as_completed(result)
            elif result.get('status') == 'failed':
                self._mark_as_failed(result)

            return result

        except Exception as e:
            _logger.error(f"Payment verification error: {e}")
            raise

    def _mark_as_completed(self, result):
        """Mark transaction as completed and create accounting entry"""
        self.write({
            'state': 'done',
            'completed_at': datetime.now(),
            'response_data': json.dumps(result),
        })

        # Create accounting payment
        if self.invoice_id:
            self._create_accounting_payment()

        # Update sale order if applicable
        if self.sale_order_id:
            self.sale_order_id.message_post(
                body=f"Payment received via {self.provider_id.name}: "
                     f"BDT {self.amount:,.2f}"
            )

    def _mark_as_failed(self, result):
        """Mark transaction as failed"""
        self.write({
            'state': 'error',
            'error_message': result.get('message', 'Payment failed'),
            'response_data': json.dumps(result),
        })

    def _create_accounting_payment(self):
        """Create accounting payment from transaction"""
        self.ensure_one()

        if not self.invoice_id or self.payment_id:
            return

        payment_vals = {
            'payment_type': 'inbound',
            'partner_type': 'customer',
            'partner_id': self.partner_id.id,
            'amount': self.amount,
            'currency_id': self.currency_id.id,
            'journal_id': self.provider_id.payment_journal_id.id,
            'ref': f"{self.name} - {self.provider_id.name}",
        }

        payment = self.env['account.payment'].create(payment_vals)
        payment.action_post()

        # Reconcile with invoice
        if self.invoice_id.state == 'posted':
            lines = (payment.move_id.line_ids + self.invoice_id.line_ids).filtered(
                lambda l: l.account_id.account_type in [
                    'asset_receivable', 'liability_payable'
                ] and not l.reconciled
            )
            lines.reconcile()

        self.payment_id = payment

    def action_refund(self):
        """Open refund wizard"""
        return {
            'type': 'ir.actions.act_window',
            'res_model': 'smart.payment.refund.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_transaction_id': self.id},
        }
```

**Task 2.2: Transaction Sequence & Security (4h)**

```xml
<!-- smart_payment/data/payment_data.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <data noupdate="1">
        <!-- Transaction Sequence -->
        <record id="seq_payment_transaction" model="ir.sequence">
            <field name="name">Payment Transaction Sequence</field>
            <field name="code">smart.payment.transaction</field>
            <field name="prefix">PAY-%(year)s%(month)s-</field>
            <field name="padding">6</field>
        </record>

        <!-- Default Providers (Sandbox) -->
        <record id="provider_bkash" model="smart.payment.provider">
            <field name="name">bKash</field>
            <field name="code">bkash</field>
            <field name="sequence">1</field>
            <field name="environment">sandbox</field>
            <field name="base_url">https://tokenized.sandbox.bka.sh/v1.2.0-beta</field>
            <field name="fee_percentage">1.5</field>
            <field name="available_for_b2c">True</field>
            <field name="available_for_b2b">True</field>
            <field name="min_amount">10</field>
            <field name="max_amount">50000</field>
        </record>

        <record id="provider_nagad" model="smart.payment.provider">
            <field name="name">Nagad</field>
            <field name="code">nagad</field>
            <field name="sequence">2</field>
            <field name="environment">sandbox</field>
            <field name="base_url">https://api.sandbox.mynagad.com</field>
            <field name="fee_percentage">1.45</field>
            <field name="available_for_b2c">True</field>
            <field name="available_for_b2b">True</field>
        </record>

        <record id="provider_sslcommerz" model="smart.payment.provider">
            <field name="name">SSLCommerz</field>
            <field name="code">sslcommerz</field>
            <field name="sequence">3</field>
            <field name="environment">sandbox</field>
            <field name="base_url">https://sandbox.sslcommerz.com</field>
            <field name="fee_percentage">2.0</field>
            <field name="available_for_b2c">True</field>
            <field name="available_for_b2b">True</field>
        </record>
    </data>
</odoo>
```

---

### Day 172-173: bKash Integration

#### Dev 2 Tasks (16h) - bKash Payment Gateway

**Task 2.1: bKash Provider Implementation (8h)**

```python
# smart_payment/models/bkash_provider.py
from odoo import models, fields, api
from odoo.exceptions import UserError
import requests
import json
import logging
from datetime import datetime

_logger = logging.getLogger(__name__)

class BkashProvider(models.Model):
    _inherit = 'smart.payment.provider'

    # bKash specific fields
    bkash_app_key = fields.Char(string='bKash App Key')
    bkash_app_secret = fields.Char(string='bKash App Secret')
    bkash_username = fields.Char(string='bKash Username')
    bkash_password = fields.Char(string='bKash Password')

    # Token management
    bkash_token = fields.Char(string='Access Token')
    bkash_token_expiry = fields.Datetime(string='Token Expiry')
    bkash_refresh_token = fields.Char(string='Refresh Token')

    def _get_bkash_token(self):
        """Get or refresh bKash access token"""
        self.ensure_one()

        if self.code != 'bkash':
            return

        # Check if token is still valid
        if self.bkash_token and self.bkash_token_expiry:
            if datetime.now() < self.bkash_token_expiry:
                return self.bkash_token

        # Get new token
        url = self._get_api_url('/tokenized/checkout/token/grant')

        headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'username': self.bkash_username,
            'password': self.bkash_password,
        }

        payload = {
            'app_key': self.bkash_app_key,
            'app_secret': self.bkash_app_secret,
        }

        try:
            response = requests.post(url, headers=headers, json=payload, timeout=30)
            data = response.json()

            if data.get('statusCode') == '0000':
                from datetime import timedelta
                expiry = datetime.now() + timedelta(seconds=int(data.get('expires_in', 3600)))

                self.write({
                    'bkash_token': data.get('id_token'),
                    'bkash_token_expiry': expiry,
                    'bkash_refresh_token': data.get('refresh_token'),
                })

                return data.get('id_token')
            else:
                _logger.error(f"bKash token error: {data}")
                raise UserError(f"bKash authentication failed: {data.get('statusMessage')}")

        except requests.RequestException as e:
            _logger.error(f"bKash token request error: {e}")
            raise UserError("Unable to connect to bKash. Please try again.")

    def _get_bkash_headers(self):
        """Get headers for bKash API requests"""
        token = self._get_bkash_token()
        return {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': token,
            'X-APP-Key': self.bkash_app_key,
        }

    def initiate_payment(self, transaction):
        """Initiate bKash payment"""
        if self.code != 'bkash':
            return super().initiate_payment(transaction)

        url = self._get_api_url('/tokenized/checkout/create')
        headers = self._get_bkash_headers()

        payload = {
            'mode': '0011',  # Checkout URL
            'payerReference': transaction.partner_phone or transaction.partner_id.phone,
            'callbackURL': self.callback_url,
            'amount': str(transaction.amount),
            'currency': 'BDT',
            'intent': 'sale',
            'merchantInvoiceNumber': transaction.name,
        }

        try:
            response = requests.post(url, headers=headers, json=payload, timeout=30)
            data = response.json()

            _logger.info(f"bKash create response: {data}")

            if data.get('statusCode') == '0000':
                return {
                    'status': 'pending',
                    'transaction_id': data.get('paymentID'),
                    'payment_url': data.get('bkashURL'),
                    'raw_response': data,
                }
            else:
                raise UserError(
                    f"bKash payment failed: {data.get('statusMessage')}"
                )

        except requests.RequestException as e:
            _logger.error(f"bKash create error: {e}")
            raise UserError("Unable to initiate bKash payment. Please try again.")

    def verify_payment(self, payment_id):
        """Verify bKash payment status"""
        if self.code != 'bkash':
            return super().verify_payment(payment_id)

        url = self._get_api_url('/tokenized/checkout/execute')
        headers = self._get_bkash_headers()

        payload = {
            'paymentID': payment_id,
        }

        try:
            response = requests.post(url, headers=headers, json=payload, timeout=30)
            data = response.json()

            _logger.info(f"bKash execute response: {data}")

            if data.get('statusCode') == '0000' and data.get('transactionStatus') == 'Completed':
                return {
                    'status': 'success',
                    'transaction_id': data.get('trxID'),
                    'amount': float(data.get('amount', 0)),
                    'customer_msisdn': data.get('customerMsisdn'),
                    'raw_response': data,
                }
            else:
                return {
                    'status': 'failed',
                    'message': data.get('statusMessage', 'Payment failed'),
                    'raw_response': data,
                }

        except requests.RequestException as e:
            _logger.error(f"bKash execute error: {e}")
            raise UserError("Unable to verify bKash payment. Please try again.")

    def process_refund(self, transaction, amount=None):
        """Process bKash refund"""
        if self.code != 'bkash':
            return super().process_refund(transaction, amount)

        if not transaction.provider_reference:
            raise UserError("No transaction ID found for refund.")

        refund_amount = amount or transaction.amount

        url = self._get_api_url('/tokenized/checkout/payment/refund')
        headers = self._get_bkash_headers()

        # First query the transaction
        query_url = self._get_api_url('/tokenized/checkout/payment/query')
        query_payload = {'paymentID': transaction.provider_reference}

        query_response = requests.post(query_url, headers=headers, json=query_payload, timeout=30)
        query_data = query_response.json()

        if query_data.get('statusCode') != '0000':
            raise UserError(f"Unable to query transaction: {query_data.get('statusMessage')}")

        trx_id = query_data.get('trxID')

        # Process refund
        payload = {
            'paymentID': transaction.provider_reference,
            'trxID': trx_id,
            'amount': str(refund_amount),
            'reason': 'Customer requested refund',
            'sku': 'REFUND',
        }

        try:
            response = requests.post(url, headers=headers, json=payload, timeout=30)
            data = response.json()

            _logger.info(f"bKash refund response: {data}")

            if data.get('statusCode') == '0000':
                return {
                    'status': 'success',
                    'refund_id': data.get('refundTrxID'),
                    'amount': float(data.get('amount', 0)),
                    'raw_response': data,
                }
            else:
                return {
                    'status': 'failed',
                    'message': data.get('statusMessage', 'Refund failed'),
                    'raw_response': data,
                }

        except requests.RequestException as e:
            _logger.error(f"bKash refund error: {e}")
            raise UserError("Unable to process refund. Please try again.")
```

**Task 2.2: bKash Callback Controller (4h)**

```python
# smart_payment/controllers/bkash_controller.py
from odoo import http
from odoo.http import request
import json
import logging

_logger = logging.getLogger(__name__)

class BkashController(http.Controller):

    @http.route('/payment/bkash/callback', type='http', auth='public',
                methods=['GET', 'POST'], csrf=False)
    def bkash_callback(self, **kwargs):
        """Handle bKash payment callback"""
        _logger.info(f"bKash callback received: {kwargs}")

        payment_id = kwargs.get('paymentID')
        status = kwargs.get('status')

        if not payment_id:
            return request.redirect('/shop/payment/failed?error=missing_payment_id')

        # Find transaction
        transaction = request.env['smart.payment.transaction'].sudo().search([
            ('provider_reference', '=', payment_id),
            ('provider_id.code', '=', 'bkash'),
        ], limit=1)

        if not transaction:
            _logger.error(f"Transaction not found for payment_id: {payment_id}")
            return request.redirect('/shop/payment/failed?error=transaction_not_found')

        if status == 'success':
            try:
                # Execute/verify payment
                result = transaction.action_verify()

                if transaction.state == 'done':
                    # Redirect to success page
                    if transaction.sale_order_id:
                        return request.redirect(
                            f'/shop/confirmation?order_id={transaction.sale_order_id.id}'
                        )
                    return request.redirect('/shop/payment/success')
                else:
                    return request.redirect(
                        f'/shop/payment/failed?error={result.get("message", "verification_failed")}'
                    )

            except Exception as e:
                _logger.error(f"bKash callback error: {e}")
                return request.redirect(f'/shop/payment/failed?error={str(e)}')

        elif status == 'cancel':
            transaction.write({'state': 'cancelled'})
            return request.redirect('/shop/payment/cancelled')

        elif status == 'failure':
            transaction.write({
                'state': 'error',
                'error_message': kwargs.get('errorMessage', 'Payment failed'),
            })
            return request.redirect('/shop/payment/failed')

        return request.redirect('/shop/cart')

    @http.route('/payment/bkash/ipn', type='json', auth='public',
                methods=['POST'], csrf=False)
    def bkash_ipn(self, **kwargs):
        """Handle bKash IPN (Instant Payment Notification)"""
        data = request.jsonrequest
        _logger.info(f"bKash IPN received: {data}")

        payment_id = data.get('paymentID')
        trx_id = data.get('trxID')
        status = data.get('transactionStatus')

        if not payment_id:
            return {'status': 'error', 'message': 'Missing paymentID'}

        transaction = request.env['smart.payment.transaction'].sudo().search([
            ('provider_reference', '=', payment_id),
            ('provider_id.code', '=', 'bkash'),
        ], limit=1)

        if not transaction:
            return {'status': 'error', 'message': 'Transaction not found'}

        if status == 'Completed' and transaction.state == 'pending':
            transaction._mark_as_completed({
                'status': 'success',
                'transaction_id': trx_id,
                'raw_response': data,
            })

        return {'status': 'ok'}
```

**Task 2.3: bKash Frontend Integration (4h)**

```javascript
// smart_payment/static/src/js/bkash_payment.js

document.addEventListener('DOMContentLoaded', function() {
    const bkashButton = document.getElementById('pay-with-bkash');
    if (!bkashButton) return;

    bkashButton.addEventListener('click', async function(e) {
        e.preventDefault();

        const orderId = this.dataset.orderId;
        const amount = this.dataset.amount;

        // Show loading
        this.disabled = true;
        this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';

        try {
            // Create transaction
            const response = await fetch('/payment/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    jsonrpc: '2.0',
                    method: 'call',
                    params: {
                        provider: 'bkash',
                        order_id: parseInt(orderId),
                        amount: parseFloat(amount),
                    }
                })
            });

            const data = await response.json();

            if (data.result && data.result.payment_url) {
                // Redirect to bKash payment page
                window.location.href = data.result.payment_url;
            } else {
                throw new Error(data.error?.message || 'Payment initiation failed');
            }

        } catch (error) {
            console.error('bKash payment error:', error);
            alert('Unable to process payment. Please try again.');

            this.disabled = false;
            this.innerHTML = '<i class="fa fa-mobile-alt"></i> Pay with bKash';
        }
    });
});
```

---

### Day 174-177: Nagad, Rocket & SSLCommerz Integration

*(Condensed - similar pattern to bKash)*

```python
# smart_payment/models/nagad_provider.py
class NagadProvider(models.Model):
    _inherit = 'smart.payment.provider'

    nagad_merchant_id = fields.Char(string='Nagad Merchant ID')
    nagad_merchant_number = fields.Char(string='Nagad Merchant Number')
    nagad_public_key = fields.Text(string='Nagad Public Key')
    nagad_private_key = fields.Text(string='Nagad Private Key')

    def initiate_payment(self, transaction):
        if self.code != 'nagad':
            return super().initiate_payment(transaction)

        # Nagad uses encryption for request
        from Crypto.PublicKey import RSA
        from Crypto.Cipher import PKCS1_v1_5
        import base64

        # Implementation follows Nagad API spec
        # ... (similar structure to bKash)


# smart_payment/models/sslcommerz_provider.py
class SSLCommerzProvider(models.Model):
    _inherit = 'smart.payment.provider'

    ssl_store_id = fields.Char(string='SSLCommerz Store ID')
    ssl_store_password = fields.Char(string='SSLCommerz Store Password')
    ssl_is_sandbox = fields.Boolean(
        string='Sandbox Mode',
        compute='_compute_ssl_sandbox'
    )

    @api.depends('environment')
    def _compute_ssl_sandbox(self):
        for provider in self:
            provider.ssl_is_sandbox = provider.environment == 'sandbox'

    def initiate_payment(self, transaction):
        if self.code != 'sslcommerz':
            return super().initiate_payment(transaction)

        url = f"{self.base_url}/gwprocess/v4/api.php"

        payload = {
            'store_id': self.ssl_store_id,
            'store_passwd': self.ssl_store_password,
            'total_amount': transaction.amount,
            'currency': 'BDT',
            'tran_id': transaction.name,
            'success_url': f"{self.callback_url}?status=success",
            'fail_url': f"{self.callback_url}?status=fail",
            'cancel_url': f"{self.callback_url}?status=cancel",
            'ipn_url': self.ipn_url,
            'cus_name': transaction.partner_id.name,
            'cus_email': transaction.partner_email or 'customer@example.com',
            'cus_phone': transaction.partner_phone or '',
            'cus_add1': transaction.partner_id.street or '',
            'cus_city': transaction.partner_id.city or '',
            'cus_country': 'Bangladesh',
            'shipping_method': 'NO',
            'product_name': 'Smart Dairy Products',
            'product_category': 'Dairy',
            'product_profile': 'general',
        }

        response = requests.post(url, data=payload, timeout=30)
        data = response.json()

        if data.get('status') == 'SUCCESS':
            return {
                'status': 'pending',
                'transaction_id': data.get('sessionkey'),
                'payment_url': data.get('GatewayPageURL'),
                'raw_response': data,
            }
        else:
            raise UserError(f"SSLCommerz error: {data.get('failedreason')}")
```

---

### Day 178: Payment Reconciliation

#### Dev 1 Tasks (8h) - Reconciliation Engine

```python
# smart_payment/models/payment_reconciliation.py
from odoo import models, fields, api
from datetime import date, timedelta
import logging

_logger = logging.getLogger(__name__)

class PaymentReconciliation(models.Model):
    _name = 'smart.payment.reconciliation'
    _description = 'Payment Reconciliation'
    _order = 'date desc'

    name = fields.Char(string='Reference', readonly=True, default='New')
    date = fields.Date(string='Reconciliation Date', required=True, default=fields.Date.today)

    provider_id = fields.Many2one(
        'smart.payment.provider',
        string='Payment Provider',
        required=True
    )

    state = fields.Selection([
        ('draft', 'Draft'),
        ('in_progress', 'In Progress'),
        ('done', 'Completed'),
        ('discrepancy', 'Has Discrepancies'),
    ], string='Status', default='draft')

    # Totals
    system_total = fields.Float(
        string='System Total',
        compute='_compute_totals',
        store=True
    )
    provider_total = fields.Float(string='Provider Total')
    difference = fields.Float(
        string='Difference',
        compute='_compute_totals',
        store=True
    )

    # Transactions
    transaction_ids = fields.Many2many(
        'smart.payment.transaction',
        string='Transactions'
    )
    transaction_count = fields.Integer(
        string='Transaction Count',
        compute='_compute_totals'
    )

    # Discrepancies
    discrepancy_ids = fields.One2many(
        'smart.payment.discrepancy',
        'reconciliation_id',
        string='Discrepancies'
    )
    discrepancy_count = fields.Integer(compute='_compute_discrepancy_count')

    notes = fields.Text(string='Notes')

    @api.depends('transaction_ids', 'provider_total')
    def _compute_totals(self):
        for rec in self:
            rec.transaction_count = len(rec.transaction_ids)
            rec.system_total = sum(
                rec.transaction_ids.filtered(
                    lambda t: t.state == 'done'
                ).mapped('amount')
            )
            rec.difference = rec.system_total - (rec.provider_total or 0)

    @api.depends('discrepancy_ids')
    def _compute_discrepancy_count(self):
        for rec in self:
            rec.discrepancy_count = len(rec.discrepancy_ids)

    def action_fetch_transactions(self):
        """Fetch transactions for the date and provider"""
        self.ensure_one()

        transactions = self.env['smart.payment.transaction'].search([
            ('provider_id', '=', self.provider_id.id),
            ('completed_at', '>=', self.date),
            ('completed_at', '<', self.date + timedelta(days=1)),
            ('state', '=', 'done'),
        ])

        self.transaction_ids = [(6, 0, transactions.ids)]

    def action_reconcile(self):
        """Perform reconciliation with provider"""
        self.ensure_one()
        self.state = 'in_progress'

        # Fetch provider settlement report
        try:
            provider_data = self._fetch_provider_settlement()

            self.provider_total = provider_data.get('total', 0)

            # Compare transactions
            discrepancies = []

            for txn in self.transaction_ids:
                provider_txn = self._find_provider_transaction(
                    txn.provider_reference, provider_data
                )

                if not provider_txn:
                    discrepancies.append({
                        'reconciliation_id': self.id,
                        'transaction_id': txn.id,
                        'discrepancy_type': 'missing_provider',
                        'system_amount': txn.amount,
                        'provider_amount': 0,
                        'notes': 'Transaction not found in provider settlement',
                    })
                elif abs(txn.amount - provider_txn['amount']) > 0.01:
                    discrepancies.append({
                        'reconciliation_id': self.id,
                        'transaction_id': txn.id,
                        'discrepancy_type': 'amount_mismatch',
                        'system_amount': txn.amount,
                        'provider_amount': provider_txn['amount'],
                    })

            # Create discrepancy records
            if discrepancies:
                self.env['smart.payment.discrepancy'].create(discrepancies)
                self.state = 'discrepancy'
            else:
                self.state = 'done'

        except Exception as e:
            _logger.error(f"Reconciliation error: {e}")
            self.notes = f"Error during reconciliation: {str(e)}"

    def _fetch_provider_settlement(self):
        """Fetch settlement data from provider - override per provider"""
        # This would call provider-specific settlement API
        # For now, return empty dict
        return {'total': 0, 'transactions': []}

    def _find_provider_transaction(self, ref, provider_data):
        """Find transaction in provider data"""
        for txn in provider_data.get('transactions', []):
            if txn.get('trx_id') == ref:
                return txn
        return None


class PaymentDiscrepancy(models.Model):
    _name = 'smart.payment.discrepancy'
    _description = 'Payment Reconciliation Discrepancy'

    reconciliation_id = fields.Many2one(
        'smart.payment.reconciliation',
        required=True,
        ondelete='cascade'
    )
    transaction_id = fields.Many2one('smart.payment.transaction')

    discrepancy_type = fields.Selection([
        ('missing_provider', 'Missing in Provider'),
        ('missing_system', 'Missing in System'),
        ('amount_mismatch', 'Amount Mismatch'),
        ('status_mismatch', 'Status Mismatch'),
    ], string='Type', required=True)

    system_amount = fields.Float(string='System Amount')
    provider_amount = fields.Float(string='Provider Amount')
    difference = fields.Float(
        string='Difference',
        compute='_compute_difference'
    )

    state = fields.Selection([
        ('open', 'Open'),
        ('resolved', 'Resolved'),
        ('written_off', 'Written Off'),
    ], string='Status', default='open')

    resolution_notes = fields.Text(string='Resolution Notes')
    resolved_by_id = fields.Many2one('res.users', string='Resolved By')
    resolved_date = fields.Date(string='Resolved Date')

    notes = fields.Text(string='Notes')

    @api.depends('system_amount', 'provider_amount')
    def _compute_difference(self):
        for disc in self:
            disc.difference = disc.system_amount - disc.provider_amount
```

---

### Day 179-180: Refund Processing & Dashboard

```python
# smart_payment/models/payment_refund.py
class PaymentRefund(models.Model):
    _name = 'smart.payment.refund'
    _description = 'Payment Refund'
    _inherit = ['mail.thread']

    name = fields.Char(readonly=True, default='New')
    transaction_id = fields.Many2one(
        'smart.payment.transaction',
        required=True
    )
    amount = fields.Float(required=True)
    reason = fields.Selection([
        ('customer_request', 'Customer Request'),
        ('order_cancelled', 'Order Cancelled'),
        ('duplicate', 'Duplicate Payment'),
        ('product_return', 'Product Return'),
        ('other', 'Other'),
    ], required=True)
    notes = fields.Text()

    state = fields.Selection([
        ('draft', 'Draft'),
        ('pending', 'Pending Approval'),
        ('approved', 'Approved'),
        ('processing', 'Processing'),
        ('done', 'Completed'),
        ('rejected', 'Rejected'),
        ('failed', 'Failed'),
    ], default='draft')

    provider_refund_id = fields.Char(string='Provider Refund ID')
    processed_at = fields.Datetime()

    def action_submit(self):
        """Submit refund for approval"""
        self.state = 'pending'

    def action_approve(self):
        """Approve refund"""
        self.state = 'approved'

    def action_process(self):
        """Process refund with provider"""
        self.ensure_one()
        self.state = 'processing'

        try:
            result = self.transaction_id.provider_id.process_refund(
                self.transaction_id, self.amount
            )

            if result.get('status') == 'success':
                self.write({
                    'state': 'done',
                    'provider_refund_id': result.get('refund_id'),
                    'processed_at': fields.Datetime.now(),
                })

                # Update original transaction
                self.transaction_id.state = 'refunded'

                # Create accounting entry
                self._create_refund_accounting()

            else:
                self.write({
                    'state': 'failed',
                    'notes': result.get('message'),
                })

        except Exception as e:
            self.write({
                'state': 'failed',
                'notes': str(e),
            })

    def _create_refund_accounting(self):
        """Create accounting entries for refund"""
        if self.transaction_id.payment_id:
            # Create reversal payment
            reversal = self.transaction_id.payment_id.copy({
                'payment_type': 'outbound',
                'ref': f"Refund: {self.name}",
            })
            reversal.action_post()
```

---

## 4. Module Manifest

```python
# smart_payment/__manifest__.py
{
    'name': 'Smart Dairy Payment Gateway',
    'version': '19.0.1.0.0',
    'category': 'Accounting/Payment',
    'summary': 'Bangladesh Payment Gateway Integration',
    'depends': [
        'account',
        'sale',
        'website_sale',
        'smart_b2b_portal',
    ],
    'data': [
        'security/ir.model.access.csv',
        'security/payment_security.xml',
        'data/payment_data.xml',
        'views/payment_provider_views.xml',
        'views/payment_transaction_views.xml',
        'views/payment_reconciliation_views.xml',
        'views/payment_refund_views.xml',
        'views/payment_templates.xml',
        'views/menus.xml',
    ],
    'assets': {
        'web.assets_frontend': [
            'smart_payment/static/src/js/*.js',
            'smart_payment/static/src/scss/*.scss',
        ],
    },
    'license': 'LGPL-3',
}
```

---

## 5. Milestone Completion Checklist

- [ ] Payment provider abstraction complete
- [ ] bKash integration tested
- [ ] Nagad integration tested
- [ ] Rocket integration tested
- [ ] SSLCommerz integration tested
- [ ] Card payments via SSLCommerz working
- [ ] Payment reconciliation operational
- [ ] Refund processing functional
- [ ] Webhook handlers secure
- [ ] Payment dashboard complete
- [ ] All security measures in place
- [ ] PCI-DSS compliance verified

---

**End of Milestone 18 Documentation**
