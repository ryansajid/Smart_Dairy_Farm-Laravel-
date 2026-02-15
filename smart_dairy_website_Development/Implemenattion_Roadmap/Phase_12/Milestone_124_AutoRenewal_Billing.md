# Milestone 124: Auto-renewal & Billing

## Smart Dairy Digital Portal + ERP System
## Phase 12: Commerce - Subscription & Automation

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-P12-M124-v1.0 |
| **Version** | 1.0 |
| **Author** | Technical Documentation Team |
| **Created Date** | 2026-02-04 |
| **Last Updated** | 2026-02-04 |
| **Status** | Draft |
| **Parent Phase** | Phase 12: Commerce - Subscription & Automation |
| **Milestone Duration** | Days 631-640 (10 working days) |
| **Dependencies** | Milestone 121 (Subscription Engine), Milestone 123 (Subscription Portal) |

---

## 1. Executive Summary

### 1.1 Milestone Overview

Milestone 124 implements a comprehensive automated billing system for subscription management. This includes scheduled billing jobs, payment gateway integration, failed payment retry logic, dunning management, prorated billing calculations, and automated invoice generation. The system ensures 99.9% billing accuracy while minimizing involuntary churn through intelligent retry mechanisms.

### 1.2 Business Value Proposition

| Value Driver | Metric | Target |
|--------------|--------|--------|
| Billing Accuracy | Invoice accuracy rate | 99.9% |
| Payment Success | First-attempt success | 95%+ |
| Involuntary Churn Reduction | Failed payment recovery | 70%+ |
| Revenue Recognition | Billing cycle time | <24 hours |
| Operational Efficiency | Manual billing tasks | -90% |

### 1.3 Strategic Alignment

**RFP Requirements:**
- REQ-BIL-001: Automated recurring billing
- REQ-BIL-002: Multiple payment gateway support
- REQ-BIL-003: Failed payment handling

**BRD Requirements:**
- BR-BIL-01: 99.9% billing accuracy
- BR-BIL-02: <2% failed payment rate
- BR-BIL-03: 70% failed payment recovery

**SRS Requirements:**
- SRS-BIL-01: Billing processing <5 minutes per batch
- SRS-BIL-02: PCI DSS compliance
- SRS-BIL-03: Real-time payment status updates

---

## 2. Detailed Scope Statement

### 2.1 In-Scope Deliverables

| Priority | Deliverable | Description |
|----------|-------------|-------------|
| **P0** | Billing Scheduler | Scheduled jobs for daily/weekly/monthly billing |
| **P0** | Payment Processing | Integration with bKash, Nagad, SSLCommerz |
| **P0** | Failed Payment Retry | Exponential backoff retry mechanism |
| **P0** | Invoice Generation | Automated invoice creation and delivery |
| **P1** | Dunning Management | Multi-stage dunning workflow |
| **P1** | Proration Calculator | Pro-rata billing for mid-cycle changes |
| **P1** | Payment Method Validation | Card/wallet validation before billing |
| **P1** | Payment Reminders | Pre-billing notifications |
| **P2** | Credit Management | Customer credit balance handling |
| **P2** | Refund Processing | Automated refund workflows |
| **P2** | Revenue Recognition | Deferred revenue tracking |

### 2.2 Out-of-Scope Items

- New payment gateway integrations (beyond bKash, Nagad, SSLCommerz)
- Tax calculation engine (uses existing Phase 8 tax module)
- Financial reporting dashboards (Milestone 125)
- Customer self-service billing (Milestone 123)

### 2.3 Assumptions

1. Payment gateways provide webhook notifications
2. Customer payment methods are pre-validated
3. Billing cycles align with calendar periods
4. BDT is the primary currency

### 2.4 Constraints

1. PCI DSS compliance required for card handling
2. Billing window: 2 AM - 6 AM local time
3. Maximum 3 retry attempts per failed payment
4. Grace period: 7 days before service suspension

---

## 3. Technical Architecture

### 3.1 System Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                    Billing Orchestrator                          │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐           │
│  │Scheduler │ │Billing   │ │Retry     │ │Dunning   │           │
│  │(Celery)  │ │Engine    │ │Manager   │ │Workflow  │           │
│  └────┬─────┘ └────┬─────┘ └────┬─────┘ └────┬─────┘           │
└───────┼────────────┼────────────┼────────────┼──────────────────┘
        │            │            │            │
        ▼            ▼            ▼            ▼
┌─────────────────────────────────────────────────────────────────┐
│                    Payment Processing Layer                      │
│  ┌────────────────────────────────────────────────────────┐    │
│  │              Payment Gateway Router                     │    │
│  │  ┌─────────┐  ┌─────────┐  ┌───────────┐              │    │
│  │  │ bKash   │  │ Nagad   │  │SSLCommerz │              │    │
│  │  │ Adapter │  │ Adapter │  │ Adapter   │              │    │
│  │  └─────────┘  └─────────┘  └───────────┘              │    │
│  └────────────────────────────────────────────────────────┘    │
└───────────────────────────┬─────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                    Data & Event Layer                            │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │ PostgreSQL   │  │ Redis Queue  │  │ Event Bus   │          │
│  │ (Billing DB) │  │ (Job Queue)  │  │ (Webhooks)  │          │
│  └──────────────┘  └──────────────┘  └──────────────┘          │
└─────────────────────────────────────────────────────────────────┘
```

### 3.2 Database Schema

#### 3.2.1 Billing Cycle Table

```sql
-- Billing cycle management
CREATE TABLE billing_cycle (
    id SERIAL PRIMARY KEY,
    subscription_id INTEGER NOT NULL REFERENCES customer_subscription(id),
    cycle_number INTEGER NOT NULL,
    period_start DATE NOT NULL,
    period_end DATE NOT NULL,
    billing_date DATE NOT NULL,
    due_date DATE NOT NULL,

    amount_subtotal DECIMAL(12,2) NOT NULL,
    amount_discount DECIMAL(12,2) DEFAULT 0,
    amount_tax DECIMAL(12,2) DEFAULT 0,
    amount_total DECIMAL(12,2) NOT NULL,
    amount_paid DECIMAL(12,2) DEFAULT 0,
    amount_due DECIMAL(12,2) NOT NULL,

    status VARCHAR(20) DEFAULT 'pending',
    -- 'pending', 'processing', 'paid', 'partial', 'failed', 'void'

    invoice_id INTEGER REFERENCES account_invoice(id),
    payment_attempts INTEGER DEFAULT 0,
    last_payment_attempt TIMESTAMP,
    next_retry_date TIMESTAMP,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT unique_subscription_cycle UNIQUE (subscription_id, cycle_number),
    CONSTRAINT valid_billing_status CHECK (
        status IN ('pending', 'processing', 'paid', 'partial', 'failed', 'void')
    )
);

CREATE INDEX idx_billing_cycle_status ON billing_cycle(status);
CREATE INDEX idx_billing_cycle_date ON billing_cycle(billing_date);
CREATE INDEX idx_billing_cycle_subscription ON billing_cycle(subscription_id);
```

#### 3.2.2 Payment Transaction Table

```sql
-- Payment transaction records
CREATE TABLE payment_transaction (
    id SERIAL PRIMARY KEY,
    billing_cycle_id INTEGER REFERENCES billing_cycle(id),
    subscription_id INTEGER NOT NULL REFERENCES customer_subscription(id),
    partner_id INTEGER NOT NULL REFERENCES res_partner(id),

    transaction_ref VARCHAR(100) UNIQUE NOT NULL,
    gateway VARCHAR(30) NOT NULL,
    -- 'bkash', 'nagad', 'sslcommerz', 'manual', 'credit'
    gateway_transaction_id VARCHAR(100),

    payment_method_id INTEGER REFERENCES customer_payment_method(id),
    amount DECIMAL(12,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'BDT',

    status VARCHAR(20) DEFAULT 'pending',
    -- 'pending', 'processing', 'success', 'failed', 'refunded', 'cancelled'

    error_code VARCHAR(50),
    error_message TEXT,
    gateway_response JSONB,

    initiated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP,

    ip_address INET,
    user_agent TEXT,

    CONSTRAINT valid_transaction_status CHECK (
        status IN ('pending', 'processing', 'success', 'failed', 'refunded', 'cancelled')
    )
);

CREATE INDEX idx_txn_status ON payment_transaction(status);
CREATE INDEX idx_txn_gateway ON payment_transaction(gateway);
CREATE INDEX idx_txn_subscription ON payment_transaction(subscription_id);
CREATE INDEX idx_txn_ref ON payment_transaction(transaction_ref);
CREATE INDEX idx_txn_gateway_id ON payment_transaction(gateway_transaction_id);
```

#### 3.2.3 Payment Retry Schedule Table

```sql
-- Payment retry tracking
CREATE TABLE payment_retry_schedule (
    id SERIAL PRIMARY KEY,
    billing_cycle_id INTEGER NOT NULL REFERENCES billing_cycle(id),
    attempt_number INTEGER NOT NULL,
    scheduled_at TIMESTAMP NOT NULL,
    executed_at TIMESTAMP,

    status VARCHAR(20) DEFAULT 'scheduled',
    -- 'scheduled', 'processing', 'success', 'failed', 'cancelled'

    payment_transaction_id INTEGER REFERENCES payment_transaction(id),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT unique_retry_attempt UNIQUE (billing_cycle_id, attempt_number),
    CONSTRAINT max_retry_attempts CHECK (attempt_number <= 5)
);

CREATE INDEX idx_retry_scheduled ON payment_retry_schedule(scheduled_at)
    WHERE status = 'scheduled';
```

#### 3.2.4 Dunning Action Table

```sql
-- Dunning workflow actions
CREATE TABLE dunning_action (
    id SERIAL PRIMARY KEY,
    subscription_id INTEGER NOT NULL REFERENCES customer_subscription(id),
    billing_cycle_id INTEGER REFERENCES billing_cycle(id),

    dunning_stage INTEGER NOT NULL,
    -- 1: First reminder, 2: Second reminder, 3: Final warning, 4: Suspension

    action_type VARCHAR(30) NOT NULL,
    -- 'email', 'sms', 'push', 'restrict_service', 'suspend', 'cancel'

    scheduled_at TIMESTAMP NOT NULL,
    executed_at TIMESTAMP,

    status VARCHAR(20) DEFAULT 'pending',
    -- 'pending', 'executed', 'skipped', 'cancelled'

    notification_id INTEGER,
    response_received BOOLEAN DEFAULT FALSE,
    response_action VARCHAR(30),
    -- 'paid', 'payment_method_updated', 'contacted_support', 'ignored'

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT valid_dunning_stage CHECK (dunning_stage BETWEEN 1 AND 4)
);

CREATE INDEX idx_dunning_scheduled ON dunning_action(scheduled_at)
    WHERE status = 'pending';
CREATE INDEX idx_dunning_subscription ON dunning_action(subscription_id);
```

### 3.3 Odoo Models

#### 3.3.1 Billing Cycle Model

```python
# models/billing_cycle.py
from odoo import models, fields, api
from odoo.exceptions import UserError
from datetime import date, timedelta
from decimal import Decimal

class BillingCycle(models.Model):
    _name = 'billing.cycle'
    _description = 'Subscription Billing Cycle'
    _order = 'billing_date desc'

    subscription_id = fields.Many2one(
        'customer.subscription',
        string='Subscription',
        required=True,
        ondelete='cascade'
    )
    cycle_number = fields.Integer(string='Cycle Number', required=True)

    period_start = fields.Date(string='Period Start', required=True)
    period_end = fields.Date(string='Period End', required=True)
    billing_date = fields.Date(string='Billing Date', required=True)
    due_date = fields.Date(string='Due Date', required=True)

    amount_subtotal = fields.Monetary(string='Subtotal', required=True)
    amount_discount = fields.Monetary(string='Discount', default=0)
    amount_tax = fields.Monetary(string='Tax', default=0)
    amount_total = fields.Monetary(string='Total', required=True)
    amount_paid = fields.Monetary(string='Paid', default=0)
    amount_due = fields.Monetary(
        string='Amount Due',
        compute='_compute_amount_due',
        store=True
    )
    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.company.currency_id
    )

    status = fields.Selection([
        ('pending', 'Pending'),
        ('processing', 'Processing'),
        ('paid', 'Paid'),
        ('partial', 'Partially Paid'),
        ('failed', 'Failed'),
        ('void', 'Voided'),
    ], string='Status', default='pending')

    invoice_id = fields.Many2one('account.move', string='Invoice')
    payment_attempts = fields.Integer(default=0)
    last_payment_attempt = fields.Datetime(string='Last Attempt')
    next_retry_date = fields.Datetime(string='Next Retry')

    transaction_ids = fields.One2many(
        'payment.transaction',
        'billing_cycle_id',
        string='Transactions'
    )
    retry_schedule_ids = fields.One2many(
        'payment.retry.schedule',
        'billing_cycle_id',
        string='Retry Schedule'
    )

    @api.depends('amount_total', 'amount_paid')
    def _compute_amount_due(self):
        for cycle in self:
            cycle.amount_due = cycle.amount_total - cycle.amount_paid

    def action_process_payment(self):
        """Process payment for billing cycle."""
        self.ensure_one()
        if self.status not in ('pending', 'failed', 'partial'):
            raise UserError("Cannot process payment for this billing cycle.")

        self.write({
            'status': 'processing',
            'payment_attempts': self.payment_attempts + 1,
            'last_payment_attempt': fields.Datetime.now(),
        })

        # Get payment method
        payment_method = self.subscription_id.payment_method_id
        if not payment_method:
            self._handle_no_payment_method()
            return False

        # Process through payment gateway
        payment_service = self.env['payment.gateway.service']
        result = payment_service.process_payment(
            payment_method=payment_method,
            amount=self.amount_due,
            currency=self.currency_id.name,
            reference=f"SUB-{self.subscription_id.id}-CYC-{self.cycle_number}",
            description=f"Subscription billing for {self.subscription_id.name}",
        )

        # Create transaction record
        transaction = self.env['payment.transaction'].create({
            'billing_cycle_id': self.id,
            'subscription_id': self.subscription_id.id,
            'partner_id': self.subscription_id.partner_id.id,
            'transaction_ref': result.get('transaction_ref'),
            'gateway': payment_method.gateway,
            'gateway_transaction_id': result.get('gateway_transaction_id'),
            'payment_method_id': payment_method.id,
            'amount': self.amount_due,
            'status': result.get('status'),
            'error_code': result.get('error_code'),
            'error_message': result.get('error_message'),
            'gateway_response': result.get('raw_response'),
        })

        if result.get('status') == 'success':
            self._handle_payment_success(transaction)
        else:
            self._handle_payment_failure(transaction, result)

        return result.get('status') == 'success'

    def _handle_payment_success(self, transaction):
        """Handle successful payment."""
        self.write({
            'amount_paid': self.amount_paid + transaction.amount,
            'status': 'paid' if self.amount_due <= 0 else 'partial',
        })

        # Update subscription
        self.subscription_id.write({
            'last_payment_date': fields.Date.today(),
            'consecutive_failed_payments': 0,
        })

        # Generate/update invoice
        if not self.invoice_id:
            self._generate_invoice()

        # Send receipt
        self._send_payment_receipt(transaction)

        # Cancel pending dunning actions
        self.env['dunning.action'].search([
            ('billing_cycle_id', '=', self.id),
            ('status', '=', 'pending'),
        ]).write({'status': 'cancelled'})

    def _handle_payment_failure(self, transaction, result):
        """Handle failed payment."""
        self.write({'status': 'failed'})

        # Update subscription
        self.subscription_id.write({
            'consecutive_failed_payments': (
                self.subscription_id.consecutive_failed_payments + 1
            ),
        })

        # Schedule retry if attempts remaining
        if self.payment_attempts < 3:
            self._schedule_retry()
        else:
            # Initiate dunning workflow
            self._initiate_dunning()

    def _schedule_retry(self):
        """Schedule payment retry with exponential backoff."""
        # Retry intervals: 1 day, 3 days, 7 days
        retry_intervals = [1, 3, 7]
        interval = retry_intervals[min(self.payment_attempts - 1, 2)]

        next_retry = fields.Datetime.now() + timedelta(days=interval)

        self.write({'next_retry_date': next_retry})

        self.env['payment.retry.schedule'].create({
            'billing_cycle_id': self.id,
            'attempt_number': self.payment_attempts + 1,
            'scheduled_at': next_retry,
        })

    def _initiate_dunning(self):
        """Start dunning workflow for failed billing."""
        dunning_service = self.env['dunning.service']
        dunning_service.initiate_dunning(self)

    def _generate_invoice(self):
        """Generate invoice for billing cycle."""
        invoice_vals = {
            'partner_id': self.subscription_id.partner_id.id,
            'move_type': 'out_invoice',
            'invoice_date': self.billing_date,
            'invoice_date_due': self.due_date,
            'invoice_origin': self.subscription_id.name,
            'invoice_line_ids': [],
        }

        for line in self.subscription_id.line_ids:
            invoice_vals['invoice_line_ids'].append((0, 0, {
                'product_id': line.product_id.id,
                'quantity': line.quantity,
                'price_unit': line.unit_price,
            }))

        invoice = self.env['account.move'].create(invoice_vals)
        invoice.action_post()

        self.write({'invoice_id': invoice.id})
        return invoice

    def _send_payment_receipt(self, transaction):
        """Send payment receipt to customer."""
        template = self.env.ref('smart_dairy_billing.email_payment_receipt')
        template.send_mail(transaction.id, force_send=True)


class BillingCycleGenerator(models.Model):
    _name = 'billing.cycle.generator'
    _description = 'Billing Cycle Generator Service'

    def generate_billing_cycles(self, billing_date=None):
        """Generate billing cycles for all due subscriptions."""
        if billing_date is None:
            billing_date = date.today()

        # Find subscriptions due for billing
        subscriptions = self.env['customer.subscription'].search([
            ('status', '=', 'active'),
            ('next_billing_date', '<=', billing_date),
            ('is_paused', '=', False),
        ])

        cycles_created = []
        for subscription in subscriptions:
            try:
                cycle = self._create_billing_cycle(subscription, billing_date)
                cycles_created.append(cycle)
            except Exception as e:
                _logger.error(
                    f"Failed to create billing cycle for subscription "
                    f"{subscription.id}: {str(e)}"
                )

        return cycles_created

    def _create_billing_cycle(self, subscription, billing_date):
        """Create billing cycle for a subscription."""
        # Calculate period dates
        period_start = subscription.current_period_start or billing_date
        period_end = self._calculate_period_end(
            period_start,
            subscription.plan_id.subscription_type
        )

        # Calculate amounts
        subtotal = subscription.recurring_total
        discount = self._calculate_discount(subscription)
        tax = self._calculate_tax(subscription, subtotal - discount)
        total = subtotal - discount + tax

        # Get next cycle number
        last_cycle = self.env['billing.cycle'].search([
            ('subscription_id', '=', subscription.id),
        ], order='cycle_number desc', limit=1)

        cycle_number = (last_cycle.cycle_number + 1) if last_cycle else 1

        # Create billing cycle
        cycle = self.env['billing.cycle'].create({
            'subscription_id': subscription.id,
            'cycle_number': cycle_number,
            'period_start': period_start,
            'period_end': period_end,
            'billing_date': billing_date,
            'due_date': billing_date + timedelta(days=7),
            'amount_subtotal': subtotal,
            'amount_discount': discount,
            'amount_tax': tax,
            'amount_total': total,
        })

        # Update subscription next billing date
        subscription.write({
            'current_period_start': period_start,
            'current_period_end': period_end,
            'next_billing_date': self._calculate_next_billing_date(
                period_end,
                subscription.plan_id.subscription_type
            ),
        })

        return cycle

    def _calculate_period_end(self, start_date, subscription_type):
        """Calculate period end date based on subscription type."""
        if subscription_type == 'daily':
            return start_date
        elif subscription_type == 'weekly':
            return start_date + timedelta(days=6)
        elif subscription_type == 'monthly':
            # Add one month
            next_month = start_date.replace(day=28) + timedelta(days=4)
            return next_month.replace(day=1) - timedelta(days=1)
        else:
            return start_date + timedelta(days=29)  # Default to monthly

    def _calculate_next_billing_date(self, period_end, subscription_type):
        """Calculate next billing date."""
        return period_end + timedelta(days=1)

    def _calculate_discount(self, subscription):
        """Calculate applicable discounts."""
        discount = Decimal('0')

        # Check for active discount
        if subscription.discount_percent and subscription.discount_end_date:
            if subscription.discount_end_date >= date.today():
                discount = subscription.recurring_total * (
                    subscription.discount_percent / 100
                )

        if subscription.discount_fixed and subscription.discount_end_date:
            if subscription.discount_end_date >= date.today():
                discount += subscription.discount_fixed

        return float(discount)

    def _calculate_tax(self, subscription, taxable_amount):
        """Calculate tax amount."""
        # Use Odoo's tax calculation
        tax_amount = Decimal('0')
        for line in subscription.line_ids:
            taxes = line.product_id.taxes_id.compute_all(
                line.unit_price * line.quantity,
                currency=subscription.currency_id,
                quantity=1,
                product=line.product_id,
                partner=subscription.partner_id,
            )
            tax_amount += Decimal(str(taxes['total_included'])) - Decimal(
                str(taxes['total_excluded'])
            )

        return float(tax_amount)
```

#### 3.3.2 Payment Gateway Service

```python
# services/payment_gateway.py
from odoo import models, api
import requests
import hashlib
import hmac
import json
import logging
from datetime import datetime

_logger = logging.getLogger(__name__)


class PaymentGatewayService(models.AbstractModel):
    _name = 'payment.gateway.service'
    _description = 'Payment Gateway Integration Service'

    def process_payment(self, payment_method, amount, currency, reference,
                        description=None):
        """Route payment to appropriate gateway."""
        gateway = payment_method.gateway

        if gateway == 'bkash':
            return self._process_bkash_payment(
                payment_method, amount, currency, reference, description
            )
        elif gateway == 'nagad':
            return self._process_nagad_payment(
                payment_method, amount, currency, reference, description
            )
        elif gateway == 'sslcommerz':
            return self._process_sslcommerz_payment(
                payment_method, amount, currency, reference, description
            )
        else:
            raise ValueError(f"Unsupported payment gateway: {gateway}")

    def _process_bkash_payment(self, payment_method, amount, currency,
                                reference, description):
        """Process payment through bKash."""
        config = self._get_bkash_config()

        # Get auth token
        token = self._get_bkash_token(config)
        if not token:
            return {
                'status': 'failed',
                'error_code': 'AUTH_FAILED',
                'error_message': 'Failed to authenticate with bKash',
            }

        # Execute payment using agreement (tokenized)
        headers = {
            'Authorization': token,
            'X-APP-Key': config['app_key'],
            'Content-Type': 'application/json',
        }

        payload = {
            'agreementID': payment_method.token,
            'mode': '0001',  # Direct payment
            'payerReference': reference,
            'amount': str(amount),
            'currency': currency,
            'intent': 'authorization',
            'merchantInvoiceNumber': reference,
        }

        try:
            response = requests.post(
                f"{config['base_url']}/tokenized/checkout/execute",
                json=payload,
                headers=headers,
                timeout=30
            )
            result = response.json()

            if result.get('statusCode') == '0000':
                return {
                    'status': 'success',
                    'transaction_ref': reference,
                    'gateway_transaction_id': result.get('trxID'),
                    'raw_response': result,
                }
            else:
                return {
                    'status': 'failed',
                    'transaction_ref': reference,
                    'error_code': result.get('statusCode'),
                    'error_message': result.get('statusMessage'),
                    'raw_response': result,
                }
        except Exception as e:
            _logger.error(f"bKash payment error: {str(e)}")
            return {
                'status': 'failed',
                'transaction_ref': reference,
                'error_code': 'GATEWAY_ERROR',
                'error_message': str(e),
            }

    def _process_nagad_payment(self, payment_method, amount, currency,
                                reference, description):
        """Process payment through Nagad."""
        config = self._get_nagad_config()

        # Prepare payment data
        sensitive_data = {
            'merchantId': config['merchant_id'],
            'datetime': datetime.now().strftime('%Y%m%d%H%M%S'),
            'orderId': reference,
            'challenge': self._generate_challenge(),
        }

        # Encrypt sensitive data
        encrypted_data = self._encrypt_nagad_data(
            sensitive_data,
            config['pg_public_key']
        )

        # Sign data
        signature = self._sign_nagad_data(
            sensitive_data,
            config['merchant_private_key']
        )

        payload = {
            'accountNumber': payment_method.token,  # Tokenized account
            'dateTime': sensitive_data['datetime'],
            'sensitiveData': encrypted_data,
            'signature': signature,
        }

        try:
            # Initialize payment
            init_response = requests.post(
                f"{config['base_url']}/api/dfs/check-out/initialize/"
                f"{config['merchant_id']}/{reference}",
                json=payload,
                timeout=30
            )
            init_result = init_response.json()

            if init_result.get('status') == 'Success':
                # Complete payment
                complete_response = requests.post(
                    f"{config['base_url']}/api/dfs/check-out/complete/"
                    f"{init_result.get('paymentReferenceId')}",
                    json={'amount': str(amount)},
                    timeout=30
                )
                complete_result = complete_response.json()

                if complete_result.get('status') == 'Success':
                    return {
                        'status': 'success',
                        'transaction_ref': reference,
                        'gateway_transaction_id': complete_result.get(
                            'paymentReferenceId'
                        ),
                        'raw_response': complete_result,
                    }

            return {
                'status': 'failed',
                'transaction_ref': reference,
                'error_code': init_result.get('reason'),
                'error_message': init_result.get('message'),
                'raw_response': init_result,
            }
        except Exception as e:
            _logger.error(f"Nagad payment error: {str(e)}")
            return {
                'status': 'failed',
                'transaction_ref': reference,
                'error_code': 'GATEWAY_ERROR',
                'error_message': str(e),
            }

    def _process_sslcommerz_payment(self, payment_method, amount, currency,
                                     reference, description):
        """Process payment through SSLCommerz."""
        config = self._get_sslcommerz_config()

        # For tokenized/recurring payments
        payload = {
            'store_id': config['store_id'],
            'store_passwd': config['store_password'],
            'total_amount': str(amount),
            'currency': currency,
            'tran_id': reference,
            'cus_name': payment_method.partner_id.name,
            'cus_email': payment_method.partner_id.email,
            'cus_phone': payment_method.partner_id.phone,
            'token': payment_method.token,  # Card token
            'token_payment': 'YES',
        }

        try:
            response = requests.post(
                f"{config['base_url']}/gwprocess/v4/api.php",
                data=payload,
                timeout=30
            )
            result = response.json()

            if result.get('status') == 'VALID':
                return {
                    'status': 'success',
                    'transaction_ref': reference,
                    'gateway_transaction_id': result.get('tran_id'),
                    'raw_response': result,
                }
            else:
                return {
                    'status': 'failed',
                    'transaction_ref': reference,
                    'error_code': result.get('error'),
                    'error_message': result.get('failedreason'),
                    'raw_response': result,
                }
        except Exception as e:
            _logger.error(f"SSLCommerz payment error: {str(e)}")
            return {
                'status': 'failed',
                'transaction_ref': reference,
                'error_code': 'GATEWAY_ERROR',
                'error_message': str(e),
            }

    def _get_bkash_config(self):
        """Get bKash configuration."""
        params = self.env['ir.config_parameter'].sudo()
        return {
            'base_url': params.get_param('bkash_base_url'),
            'app_key': params.get_param('bkash_app_key'),
            'app_secret': params.get_param('bkash_app_secret'),
            'username': params.get_param('bkash_username'),
            'password': params.get_param('bkash_password'),
        }

    def _get_nagad_config(self):
        """Get Nagad configuration."""
        params = self.env['ir.config_parameter'].sudo()
        return {
            'base_url': params.get_param('nagad_base_url'),
            'merchant_id': params.get_param('nagad_merchant_id'),
            'pg_public_key': params.get_param('nagad_pg_public_key'),
            'merchant_private_key': params.get_param('nagad_merchant_private_key'),
        }

    def _get_sslcommerz_config(self):
        """Get SSLCommerz configuration."""
        params = self.env['ir.config_parameter'].sudo()
        return {
            'base_url': params.get_param('sslcommerz_base_url'),
            'store_id': params.get_param('sslcommerz_store_id'),
            'store_password': params.get_param('sslcommerz_store_password'),
        }

    def _get_bkash_token(self, config):
        """Get bKash authentication token."""
        try:
            response = requests.post(
                f"{config['base_url']}/tokenized/checkout/token/grant",
                json={
                    'app_key': config['app_key'],
                    'app_secret': config['app_secret'],
                },
                headers={
                    'Content-Type': 'application/json',
                    'username': config['username'],
                    'password': config['password'],
                },
                timeout=10
            )
            result = response.json()
            return result.get('id_token')
        except Exception as e:
            _logger.error(f"bKash token error: {str(e)}")
            return None
```

#### 3.3.3 Dunning Service

```python
# services/dunning_service.py
from odoo import models, api, fields
from datetime import datetime, timedelta

class DunningService(models.AbstractModel):
    _name = 'dunning.service'
    _description = 'Dunning Workflow Service'

    DUNNING_STAGES = {
        1: {'days_after': 1, 'action': 'email', 'template': 'first_reminder'},
        2: {'days_after': 3, 'action': 'email_sms', 'template': 'second_reminder'},
        3: {'days_after': 5, 'action': 'email_sms_push', 'template': 'final_warning'},
        4: {'days_after': 7, 'action': 'suspend', 'template': 'suspension_notice'},
    }

    def initiate_dunning(self, billing_cycle):
        """Start dunning workflow for failed billing cycle."""
        # Create dunning actions for all stages
        base_date = datetime.now()

        for stage, config in self.DUNNING_STAGES.items():
            scheduled_at = base_date + timedelta(days=config['days_after'])

            self.env['dunning.action'].create({
                'subscription_id': billing_cycle.subscription_id.id,
                'billing_cycle_id': billing_cycle.id,
                'dunning_stage': stage,
                'action_type': config['action'],
                'scheduled_at': scheduled_at,
            })

    def process_pending_dunning_actions(self):
        """Process all pending dunning actions due for execution."""
        actions = self.env['dunning.action'].search([
            ('status', '=', 'pending'),
            ('scheduled_at', '<=', datetime.now()),
        ])

        for action in actions:
            self._execute_dunning_action(action)

    def _execute_dunning_action(self, action):
        """Execute a single dunning action."""
        # Check if payment was made since action was scheduled
        if action.billing_cycle_id.status == 'paid':
            action.write({
                'status': 'skipped',
                'response_action': 'paid',
            })
            return

        # Execute based on action type
        if action.action_type == 'email':
            self._send_dunning_email(action)
        elif action.action_type == 'email_sms':
            self._send_dunning_email(action)
            self._send_dunning_sms(action)
        elif action.action_type == 'email_sms_push':
            self._send_dunning_email(action)
            self._send_dunning_sms(action)
            self._send_dunning_push(action)
        elif action.action_type == 'suspend':
            self._suspend_subscription(action)

        action.write({
            'status': 'executed',
            'executed_at': datetime.now(),
        })

    def _send_dunning_email(self, action):
        """Send dunning email notification."""
        template_name = self.DUNNING_STAGES[action.dunning_stage]['template']
        template = self.env.ref(
            f'smart_dairy_billing.email_dunning_{template_name}'
        )
        template.send_mail(action.id, force_send=True)

    def _send_dunning_sms(self, action):
        """Send dunning SMS notification."""
        subscription = action.subscription_id
        partner = subscription.partner_id

        if not partner.mobile:
            return

        sms_service = self.env['sms.service']
        message = self._get_dunning_sms_message(action)
        sms_service.send_sms(partner.mobile, message)

    def _send_dunning_push(self, action):
        """Send dunning push notification."""
        subscription = action.subscription_id
        partner = subscription.partner_id

        push_service = self.env['push.notification.service']
        push_service.send_notification(
            user_id=partner.user_id.id if partner.user_id else None,
            title='Payment Required',
            body=f'Your subscription payment of ৳{action.billing_cycle_id.amount_due} is overdue.',
            data={'subscription_id': subscription.id},
        )

    def _suspend_subscription(self, action):
        """Suspend subscription due to non-payment."""
        subscription = action.subscription_id

        subscription.write({
            'status': 'suspended',
            'suspension_reason': 'payment_failed',
            'suspended_at': datetime.now(),
        })

        # Send suspension notification
        template = self.env.ref(
            'smart_dairy_billing.email_subscription_suspended'
        )
        template.send_mail(subscription.id, force_send=True)

    def _get_dunning_sms_message(self, action):
        """Get SMS message for dunning stage."""
        subscription = action.subscription_id
        amount = action.billing_cycle_id.amount_due

        messages = {
            1: f"Smart Dairy: Your payment of ৳{amount} is overdue. "
               f"Please update your payment method to continue service.",
            2: f"Smart Dairy Reminder: Payment of ৳{amount} pending. "
               f"Service may be interrupted if not paid within 4 days.",
            3: f"FINAL NOTICE: Smart Dairy payment of ৳{amount} overdue. "
               f"Service will be suspended in 2 days. Pay now to avoid interruption.",
            4: f"Smart Dairy: Your subscription has been suspended due to "
               f"non-payment. Pay ৳{amount} to restore service.",
        }

        return messages.get(action.dunning_stage, messages[1])
```

#### 3.3.4 Celery Tasks

```python
# tasks/billing_tasks.py
from celery import shared_task
from odoo import api, SUPERUSER_ID
from datetime import date, datetime
import logging

_logger = logging.getLogger(__name__)


@shared_task(bind=True, max_retries=3)
def generate_billing_cycles_task(self):
    """Daily task to generate billing cycles for due subscriptions."""
    try:
        with api.Environment.manage():
            env = api.Environment(registry.cursor(), SUPERUSER_ID, {})

            generator = env['billing.cycle.generator']
            cycles = generator.generate_billing_cycles(date.today())

            _logger.info(f"Generated {len(cycles)} billing cycles")
            return {'cycles_created': len(cycles)}
    except Exception as e:
        _logger.error(f"Billing cycle generation failed: {str(e)}")
        raise self.retry(exc=e, countdown=300)


@shared_task(bind=True, max_retries=3)
def process_pending_billing_task(self):
    """Process pending billing cycles for payment."""
    try:
        with api.Environment.manage():
            env = api.Environment(registry.cursor(), SUPERUSER_ID, {})

            cycles = env['billing.cycle'].search([
                ('status', '=', 'pending'),
                ('billing_date', '<=', date.today()),
            ])

            success_count = 0
            failed_count = 0

            for cycle in cycles:
                try:
                    if cycle.action_process_payment():
                        success_count += 1
                    else:
                        failed_count += 1
                except Exception as e:
                    _logger.error(
                        f"Payment processing failed for cycle {cycle.id}: {str(e)}"
                    )
                    failed_count += 1

            return {
                'processed': len(cycles),
                'success': success_count,
                'failed': failed_count,
            }
    except Exception as e:
        _logger.error(f"Billing processing failed: {str(e)}")
        raise self.retry(exc=e, countdown=300)


@shared_task(bind=True, max_retries=3)
def process_payment_retries_task(self):
    """Process scheduled payment retries."""
    try:
        with api.Environment.manage():
            env = api.Environment(registry.cursor(), SUPERUSER_ID, {})

            retries = env['payment.retry.schedule'].search([
                ('status', '=', 'scheduled'),
                ('scheduled_at', '<=', datetime.now()),
            ])

            for retry in retries:
                retry.write({'status': 'processing'})

                try:
                    cycle = retry.billing_cycle_id
                    success = cycle.action_process_payment()

                    retry.write({
                        'status': 'success' if success else 'failed',
                        'executed_at': datetime.now(),
                    })
                except Exception as e:
                    _logger.error(f"Retry failed for {retry.id}: {str(e)}")
                    retry.write({'status': 'failed'})

            return {'retries_processed': len(retries)}
    except Exception as e:
        _logger.error(f"Retry processing failed: {str(e)}")
        raise self.retry(exc=e, countdown=300)


@shared_task
def process_dunning_actions_task():
    """Process pending dunning actions."""
    with api.Environment.manage():
        env = api.Environment(registry.cursor(), SUPERUSER_ID, {})

        dunning_service = env['dunning.service']
        dunning_service.process_pending_dunning_actions()


@shared_task
def send_payment_reminders_task():
    """Send payment reminders for upcoming billing."""
    with api.Environment.manage():
        env = api.Environment(registry.cursor(), SUPERUSER_ID, {})

        # Find subscriptions billing in 3 days
        reminder_date = date.today() + timedelta(days=3)

        subscriptions = env['customer.subscription'].search([
            ('status', '=', 'active'),
            ('next_billing_date', '=', reminder_date),
        ])

        for subscription in subscriptions:
            template = env.ref('smart_dairy_billing.email_payment_reminder')
            template.send_mail(subscription.id, force_send=True)

        return {'reminders_sent': len(subscriptions)}
```

---

## 4. Day-by-Day Implementation Plan

### Day 631: Billing Infrastructure & Database Setup

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Design billing database schema | ERD and schema document |
| 2-4h | Create billing_cycle table migration | SQL migration file |
| 4-6h | Create payment_transaction table | Migration with indexes |
| 6-8h | Create retry and dunning tables | Complete schema migration |

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Setup Celery beat scheduler | Celery configuration |
| 2-4h | Configure Redis job queues | Queue definitions |
| 4-6h | Create billing task templates | Celery task stubs |
| 6-8h | Setup payment webhook handlers | Webhook endpoints |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Design billing admin UI wireframes | Figma designs |
| 2-4h | Create billing dashboard component | BillingDashboard.tsx |
| 4-6h | Build billing cycle list view | BillingCycleList.tsx |
| 6-8h | Design payment transaction view | TransactionList.tsx |

#### Day 631 Deliverables Checklist

- [ ] Billing database schema created
- [ ] Celery beat scheduler configured
- [ ] Admin billing dashboard skeleton
- [ ] Webhook endpoints stubbed

---

### Day 632: Billing Cycle Generator

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement BillingCycle Odoo model | Python model file |
| 2-4h | Create BillingCycleGenerator service | Generator service |
| 4-6h | Implement period calculation logic | Date calculation methods |
| 6-8h | Add discount and tax calculations | Pricing logic |

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create billing cycle generation task | Celery task |
| 2-4h | Implement batch processing logic | Batch generator |
| 4-6h | Add error handling and logging | Logging config |
| 6-8h | Write unit tests for generator | pytest tests |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create billing cycle detail view | CycleDetail.tsx |
| 2-4h | Build invoice preview component | InvoicePreview.tsx |
| 4-6h | Implement billing history timeline | BillingTimeline.tsx |
| 6-8h | Add billing status indicators | StatusIndicator.tsx |

#### Day 632 Deliverables Checklist

- [ ] Billing cycle model implemented
- [ ] Automatic cycle generation working
- [ ] Discount/tax calculations functional
- [ ] Admin UI for billing cycles

---

### Day 633: Payment Gateway Integration - bKash

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Design payment gateway abstraction | Gateway interface |
| 2-4h | Implement bKash API client | bKash adapter |
| 4-6h | Add tokenized payment support | Token management |
| 6-8h | Implement payment execution | Execute payment method |

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Setup bKash sandbox environment | Sandbox config |
| 2-4h | Implement bKash webhook handler | Webhook processor |
| 4-6h | Add payment verification logic | Verification service |
| 6-8h | Write integration tests | bKash tests |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create payment processing modal | PaymentModal.tsx |
| 2-4h | Build payment status indicator | PaymentStatus.tsx |
| 4-6h | Implement transaction detail view | TransactionDetail.tsx |
| 6-8h | Add payment receipt display | ReceiptView.tsx |

#### Day 633 Deliverables Checklist

- [ ] bKash integration functional
- [ ] Tokenized payments working
- [ ] Webhook handling implemented
- [ ] Payment UI components complete

---

### Day 634: Payment Gateway Integration - Nagad & SSLCommerz

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement Nagad API client | Nagad adapter |
| 2-4h | Add Nagad encryption/signing | Security methods |
| 4-6h | Implement SSLCommerz adapter | SSLCommerz client |
| 6-8h | Add card tokenization support | Token management |

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Setup Nagad sandbox | Sandbox config |
| 2-4h | Setup SSLCommerz sandbox | Sandbox config |
| 4-6h | Implement webhook handlers | Webhook processors |
| 6-8h | Write integration tests | Gateway tests |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Update payment method selector | MethodSelector.tsx |
| 2-4h | Add gateway-specific UI elements | GatewayUI.tsx |
| 4-6h | Implement error handling UI | PaymentError.tsx |
| 6-8h | Test payment flows | E2E payment tests |

#### Day 634 Deliverables Checklist

- [ ] Nagad integration functional
- [ ] SSLCommerz integration functional
- [ ] All gateways tested in sandbox
- [ ] Unified payment UI complete

---

### Day 635: Payment Processing & Transaction Management

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement PaymentTransaction model | Transaction model |
| 2-4h | Create payment processing service | Processing service |
| 4-6h | Add transaction status management | Status transitions |
| 6-8h | Implement receipt generation | Receipt service |

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create payment processing task | Celery task |
| 2-4h | Add idempotency handling | Duplicate prevention |
| 4-6h | Implement transaction logging | Audit logging |
| 6-8h | Write payment processing tests | Integration tests |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Build transaction management UI | TransactionManager.tsx |
| 2-4h | Create refund request form | RefundForm.tsx |
| 4-6h | Implement payment export | ExportPayments.tsx |
| 6-8h | Add transaction filters | TransactionFilters.tsx |

#### Day 635 Deliverables Checklist

- [ ] Transaction management complete
- [ ] Payment processing automated
- [ ] Audit logging implemented
- [ ] Admin transaction UI functional

---

### Day 636: Failed Payment Handling & Retry Logic

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement PaymentRetrySchedule model | Retry model |
| 2-4h | Create exponential backoff logic | Retry algorithm |
| 4-6h | Add retry attempt tracking | Attempt tracking |
| 6-8h | Implement max retry limits | Limit enforcement |

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create retry processing task | Celery task |
| 2-4h | Add failure categorization | Error classification |
| 4-6h | Implement retry notifications | Notification service |
| 6-8h | Write retry logic tests | Unit tests |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create retry schedule view | RetrySchedule.tsx |
| 2-4h | Build failure analysis chart | FailureAnalysis.tsx |
| 4-6h | Implement manual retry trigger | ManualRetry.tsx |
| 6-8h | Add retry history view | RetryHistory.tsx |

#### Day 636 Deliverables Checklist

- [ ] Retry scheduling implemented
- [ ] Exponential backoff working
- [ ] Failure categorization complete
- [ ] Admin retry management UI

---

### Day 637: Dunning Management Workflow

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement DunningAction model | Dunning model |
| 2-4h | Create DunningService | Dunning workflow |
| 4-6h | Implement multi-stage dunning | Stage progression |
| 6-8h | Add subscription suspension | Suspension logic |

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create dunning notification templates | Email/SMS templates |
| 2-4h | Implement dunning Celery task | Scheduled task |
| 4-6h | Add customer response tracking | Response tracking |
| 6-8h | Write dunning workflow tests | Integration tests |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create dunning dashboard | DunningDashboard.tsx |
| 2-4h | Build dunning timeline view | DunningTimeline.tsx |
| 4-6h | Implement suspension management | SuspensionManager.tsx |
| 6-8h | Add dunning analytics view | DunningAnalytics.tsx |

#### Day 637 Deliverables Checklist

- [ ] Dunning workflow automated
- [ ] Multi-stage notifications working
- [ ] Suspension logic implemented
- [ ] Admin dunning dashboard complete

---

### Day 638: Invoice Generation & Proration

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement automated invoice generation | Invoice generator |
| 2-4h | Create proration calculator | Proration service |
| 4-6h | Add mid-cycle change handling | Change proration |
| 6-8h | Implement credit application | Credit handling |

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create invoice PDF generator | PDF service |
| 2-4h | Implement invoice email delivery | Email delivery |
| 4-6h | Add invoice number sequence | Numbering service |
| 6-8h | Write proration tests | Calculation tests |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create invoice list view | InvoiceList.tsx |
| 2-4h | Build invoice detail/print view | InvoiceDetail.tsx |
| 4-6h | Implement proration preview | ProrationPreview.tsx |
| 6-8h | Add invoice download option | InvoiceDownload.tsx |

#### Day 638 Deliverables Checklist

- [ ] Invoice generation automated
- [ ] Proration calculations accurate
- [ ] PDF generation working
- [ ] Invoice management UI complete

---

### Day 639: Payment Reminders & Notifications

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement pre-billing reminder system | Reminder service |
| 2-4h | Create payment method expiry alerts | Expiry notifications |
| 4-6h | Add successful payment notifications | Receipt notifications |
| 6-8h | Implement failed payment alerts | Failure alerts |

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create notification templates | All billing templates |
| 2-4h | Implement scheduled reminder task | Celery task |
| 4-6h | Add notification preference check | Preference filtering |
| 6-8h | Write notification tests | E2E notification tests |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create notification log view | NotificationLog.tsx |
| 2-4h | Build notification preview | NotificationPreview.tsx |
| 4-6h | Implement template editor | TemplateEditor.tsx |
| 6-8h | Add notification analytics | NotificationStats.tsx |

#### Day 639 Deliverables Checklist

- [ ] Payment reminders automated
- [ ] All notification templates created
- [ ] Notification preferences respected
- [ ] Admin notification management

---

### Day 640: Testing, Optimization & Documentation

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Write comprehensive unit tests | Test coverage >80% |
| 2-4h | Performance test billing batch | Load test results |
| 4-6h | Optimize database queries | Query optimization |
| 6-8h | Create API documentation | OpenAPI spec |

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | End-to-end billing flow tests | E2E test suite |
| 2-4h | Security audit of payment handling | Security checklist |
| 4-6h | Setup billing monitoring | Grafana dashboards |
| 6-8h | Deploy to staging environment | Staging deployment |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | UI component testing | Component tests |
| 2-4h | Cross-browser testing | Browser compatibility |
| 4-6h | Performance optimization | Bundle optimization |
| 6-8h | User documentation | Admin user guide |

#### Day 640 Deliverables Checklist

- [ ] Test coverage >80%
- [ ] Security audit passed
- [ ] Staging deployment successful
- [ ] Documentation complete

---

## 5. Success Criteria

### 5.1 Functional Criteria

| Criterion | Target | Measurement |
|-----------|--------|-------------|
| Billing accuracy | 99.9% | Invoice validation |
| Payment success rate | 95%+ first attempt | Transaction analytics |
| Failed payment recovery | 70%+ | Recovery metrics |
| Invoice delivery | 100% | Delivery confirmation |

### 5.2 Technical Criteria

| Criterion | Target | Measurement |
|-----------|--------|-------------|
| Billing batch time | <5 min per 1000 | Performance test |
| Payment processing | <30 seconds | Transaction timing |
| System uptime | 99.9% | Monitoring |
| API response time | <500ms | APM |

---

## 6. Risk Register

| Risk ID | Description | Impact | Probability | Mitigation |
|---------|-------------|--------|-------------|------------|
| R124-01 | Payment gateway downtime | High | Low | Multiple gateway fallback |
| R124-02 | Billing calculation errors | High | Low | Extensive testing, audit logs |
| R124-03 | Dunning causing customer complaints | Medium | Medium | Clear communication, easy resolution |
| R124-04 | PCI compliance issues | High | Low | Tokenization, security audit |
| R124-05 | Retry storms overwhelming gateway | Medium | Low | Rate limiting, backoff |

---

## 7. Dependencies

### 7.1 Internal Dependencies

| Dependency | Source | Impact |
|------------|--------|--------|
| Subscription Engine | Milestone 121 | Billing cycle generation |
| Customer Portal | Milestone 123 | Payment method management |
| Product catalog | Phase 8 | Pricing information |
| Tax module | Phase 8 | Tax calculations |

### 7.2 External Dependencies

| Dependency | Provider | Impact |
|------------|----------|--------|
| bKash API | bKash | Payment processing |
| Nagad API | Nagad | Payment processing |
| SSLCommerz API | SSLCommerz | Card payments |
| SMS Gateway | Provider | Dunning notifications |

---

## 8. Quality Assurance

### 8.1 Testing Strategy

| Test Type | Coverage | Tool |
|-----------|----------|------|
| Unit Tests | 80%+ coverage | pytest |
| Integration Tests | All payment flows | pytest |
| E2E Tests | Critical billing paths | Cypress |
| Load Tests | 1000+ concurrent | Locust |
| Security Tests | PCI requirements | OWASP ZAP |

### 8.2 Code Quality Standards

- All payment code peer reviewed
- PCI DSS compliance verified
- No sensitive data in logs
- Transaction audit trail complete

---

**Document Version**: 1.0
**Last Updated**: 2026-02-04
**Status**: Draft
**Next Review**: Prior to Milestone 125 kickoff
```
