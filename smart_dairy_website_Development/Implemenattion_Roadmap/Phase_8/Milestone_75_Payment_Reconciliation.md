# Milestone 75: Payment Reconciliation Engine

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P8-M75-v1.0 |
| **Milestone** | 75 - Payment Reconciliation Engine |
| **Phase** | Phase 8: Operations - Payment & Logistics |
| **Days** | 491-500 |
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
9. [Reconciliation Workflows](#9-reconciliation-workflows)
10. [Testing Requirements](#10-testing-requirements)
11. [Risk & Mitigation](#11-risk--mitigation)
12. [Sign-off Checklist](#12-sign-off-checklist)

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Implement comprehensive payment reconciliation engine with automated transaction matching across all payment providers (bKash, Nagad, Rocket, SSLCommerz, COD), bank statement import, discrepancy detection, manual reconciliation queue, settlement reports, and finance team dashboard.**

### 1.2 Scope Summary

| Area | Included |
|------|----------|
| Auto-Matching | Automated transaction matching |
| Bank Import | CSV, MT940 statement import |
| Discrepancy Detection | Amount/status mismatches |
| Manual Queue | Unmatched transaction review |
| Settlement Reports | Provider-wise reports |
| Audit Trail | Complete reconciliation history |
| Alerts | Automated discrepancy notifications |
| Dashboard | Finance team reconciliation UI |

### 1.3 Prerequisites

- Milestones 71-74 (All payment integrations) completed
- Bank statement formats documented
- Finance team access configured
- Notification system ready

---

## 2. Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| O1 | Auto-match rate | > 95% transactions |
| O2 | Reconciliation accuracy | > 99.9% |
| O3 | Processing time | < 5 minutes daily batch |
| O4 | Discrepancy detection | 100% flagged |
| O5 | Report generation | < 30 seconds |
| O6 | Manual queue SLA | < 24h resolution |
| O7 | Audit compliance | 100% trail coverage |

---

## 3. Key Deliverables

| # | Deliverable | Owner | Format | Due Day |
|---|-------------|-------|--------|---------|
| D1 | Reconciliation engine core | Dev 1 | Python/Odoo | Day 493 |
| D2 | Bank statement parser | Dev 1 | Python | Day 494 |
| D3 | Auto-matching algorithm | Dev 1 | Python | Day 495 |
| D4 | Provider settlement fetcher | Dev 2 | Python | Day 494 |
| D5 | Discrepancy detection service | Dev 2 | Python | Day 496 |
| D6 | Manual reconciliation queue | Dev 2 | Python | Day 497 |
| D7 | Settlement report generator | Dev 1 | Python | Day 498 |
| D8 | Reconciliation dashboard UI | Dev 3 | OWL/JS | Day 497 |
| D9 | Alert notification system | Dev 2 | Python | Day 498 |
| D10 | Bank statement import UI | Dev 3 | OWL/JS | Day 496 |
| D11 | Audit trail system | Dev 1 | Python | Day 499 |
| D12 | Integration tests | All | Python | Day 500 |

---

## 4. Requirement Traceability

### 4.1 RFP Requirements

| RFP Req ID | Description | Deliverable |
|------------|-------------|-------------|
| ECOM-PAY-006 | Payment Reconciliation | D1-D7 |
| ECOM-PAY-006.1 | Auto-matching | D3 |
| ECOM-PAY-006.2 | Discrepancy detection | D5 |
| ECOM-PAY-006.3 | Settlement reports | D7 |
| ECOM-PAY-006.4 | Audit trail | D11 |

### 4.2 BRD Requirements

| BRD Req ID | Description | Deliverable |
|------------|-------------|-------------|
| FR-FIN-001 | Payment reconciliation | D1 |
| FR-FIN-002 | Bank statement import | D2, D10 |
| FR-FIN-003 | Discrepancy alerts | D5, D9 |
| FR-AUD-001 | Reconciliation audit | D11 |

---

## 5. Day-by-Day Development Plan

### Day 491: Project Setup & Architecture

#### Day Objective
Design reconciliation system architecture and set up module structure.

#### Dev 1 (Backend Lead) - 8 Hours

**Task 1.1: Module Structure (3h)**

```python
# File: smart_dairy_reconciliation/__manifest__.py

{
    'name': 'Smart Dairy - Payment Reconciliation',
    'version': '19.0.1.0.0',
    'category': 'Accounting/Payment',
    'summary': 'Payment Reconciliation Engine',
    'description': """
        Payment Reconciliation Module
        =============================
        - Automated transaction matching
        - Bank statement import (CSV, MT940)
        - Discrepancy detection and alerts
        - Manual reconciliation queue
        - Settlement reports by provider
        - Complete audit trail
    """,
    'author': 'Smart Dairy Technical Team',
    'license': 'LGPL-3',
    'depends': [
        'base',
        'account',
        'payment',
        'smart_dairy_payment',
        'smart_dairy_payment_bkash',
        'smart_dairy_payment_nagad',
        'smart_dairy_payment_sslcommerz',
        'smart_dairy_payment_cod',
    ],
    'data': [
        'security/ir.model.access.csv',
        'security/reconciliation_security.xml',
        'data/reconciliation_config.xml',
        'data/cron_jobs.xml',
        'views/reconciliation_views.xml',
        'views/settlement_views.xml',
        'views/discrepancy_views.xml',
        'views/dashboard_views.xml',
        'reports/settlement_report_templates.xml',
    ],
    'assets': {
        'web.assets_backend': [
            'smart_dairy_reconciliation/static/src/**/*',
        ],
    },
    'installable': True,
    'auto_install': False,
}
```

**Task 1.2: Core Models (3h)**

```python
# File: smart_dairy_reconciliation/models/reconciliation.py

from odoo import models, fields, api
from datetime import datetime, date
import logging

_logger = logging.getLogger(__name__)


class ReconciliationBatch(models.Model):
    _name = 'reconciliation.batch'
    _description = 'Reconciliation Batch'
    _order = 'date desc, id desc'

    name = fields.Char(
        string='Reference',
        readonly=True,
        default=lambda self: self.env['ir.sequence'].next_by_code('reconciliation.batch')
    )
    date = fields.Date(string='Reconciliation Date', required=True, default=fields.Date.today)
    provider_code = fields.Selection([
        ('bkash', 'bKash'),
        ('nagad', 'Nagad'),
        ('rocket', 'Rocket'),
        ('sslcommerz', 'SSLCommerz'),
        ('cod', 'Cash on Delivery'),
        ('all', 'All Providers'),
    ], string='Provider', required=True)

    # Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('processing', 'Processing'),
        ('completed', 'Completed'),
        ('failed', 'Failed'),
    ], string='Status', default='draft')

    # Statistics
    total_expected = fields.Integer(string='Expected Transactions')
    total_received = fields.Integer(string='Received Transactions')
    total_matched = fields.Integer(string='Matched')
    total_unmatched = fields.Integer(string='Unmatched')
    total_discrepancies = fields.Integer(string='Discrepancies')

    # Amounts
    expected_amount = fields.Monetary(
        string='Expected Amount',
        currency_field='currency_id'
    )
    received_amount = fields.Monetary(
        string='Received Amount',
        currency_field='currency_id'
    )
    variance_amount = fields.Monetary(
        string='Variance',
        currency_field='currency_id',
        compute='_compute_variance',
        store=True
    )
    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.ref('base.BDT')
    )

    # Related records
    match_ids = fields.One2many(
        'reconciliation.match',
        'batch_id',
        string='Matches'
    )
    discrepancy_ids = fields.One2many(
        'reconciliation.discrepancy',
        'batch_id',
        string='Discrepancies'
    )

    # Timing
    started_at = fields.Datetime(string='Started At')
    completed_at = fields.Datetime(string='Completed At')
    duration_seconds = fields.Integer(
        string='Duration (s)',
        compute='_compute_duration'
    )

    # Audit
    created_by_id = fields.Many2one('res.users', default=lambda self: self.env.user)
    notes = fields.Text(string='Notes')

    @api.depends('expected_amount', 'received_amount')
    def _compute_variance(self):
        for batch in self:
            batch.variance_amount = batch.received_amount - batch.expected_amount

    @api.depends('started_at', 'completed_at')
    def _compute_duration(self):
        for batch in self:
            if batch.started_at and batch.completed_at:
                delta = batch.completed_at - batch.started_at
                batch.duration_seconds = int(delta.total_seconds())
            else:
                batch.duration_seconds = 0

    def action_start_reconciliation(self):
        """Start reconciliation process."""
        self.ensure_one()
        self.write({
            'state': 'processing',
            'started_at': fields.Datetime.now(),
        })

        # Run reconciliation
        engine = self.env['reconciliation.engine']
        try:
            result = engine.run_reconciliation(self)
            self.write({
                'state': 'completed',
                'completed_at': fields.Datetime.now(),
                'total_expected': result.get('total_expected', 0),
                'total_received': result.get('total_received', 0),
                'total_matched': result.get('total_matched', 0),
                'total_unmatched': result.get('total_unmatched', 0),
                'total_discrepancies': result.get('total_discrepancies', 0),
                'expected_amount': result.get('expected_amount', 0),
                'received_amount': result.get('received_amount', 0),
            })
        except Exception as e:
            _logger.error(f"Reconciliation failed: {str(e)}")
            self.write({
                'state': 'failed',
                'notes': str(e),
            })


class ReconciliationMatch(models.Model):
    _name = 'reconciliation.match'
    _description = 'Reconciliation Match'

    batch_id = fields.Many2one('reconciliation.batch', string='Batch', ondelete='cascade')
    transaction_id = fields.Many2one('payment.transaction', string='Transaction')
    settlement_line_id = fields.Many2one('provider.settlement.line', string='Settlement Line')

    # Match details
    match_type = fields.Selection([
        ('exact', 'Exact Match'),
        ('amount_match', 'Amount Match'),
        ('reference_match', 'Reference Match'),
        ('manual', 'Manual Match'),
    ], string='Match Type')

    match_confidence = fields.Float(string='Confidence %', default=100.0)
    matched_at = fields.Datetime(string='Matched At', default=fields.Datetime.now)

    # Amounts
    expected_amount = fields.Monetary(currency_field='currency_id')
    received_amount = fields.Monetary(currency_field='currency_id')
    difference = fields.Monetary(currency_field='currency_id', compute='_compute_difference')
    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.ref('base.BDT'))

    @api.depends('expected_amount', 'received_amount')
    def _compute_difference(self):
        for match in self:
            match.difference = match.received_amount - match.expected_amount


class ReconciliationDiscrepancy(models.Model):
    _name = 'reconciliation.discrepancy'
    _description = 'Reconciliation Discrepancy'
    _order = 'create_date desc'

    batch_id = fields.Many2one('reconciliation.batch', string='Batch')
    transaction_id = fields.Many2one('payment.transaction', string='Transaction')
    settlement_line_id = fields.Many2one('provider.settlement.line', string='Settlement Line')

    # Discrepancy details
    discrepancy_type = fields.Selection([
        ('missing_transaction', 'Missing Transaction'),
        ('missing_settlement', 'Missing Settlement'),
        ('amount_mismatch', 'Amount Mismatch'),
        ('status_mismatch', 'Status Mismatch'),
        ('duplicate', 'Duplicate Entry'),
        ('other', 'Other'),
    ], string='Type', required=True)

    description = fields.Text(string='Description')
    expected_amount = fields.Monetary(currency_field='currency_id')
    actual_amount = fields.Monetary(currency_field='currency_id')
    difference = fields.Monetary(currency_field='currency_id')
    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.ref('base.BDT'))

    # Resolution
    state = fields.Selection([
        ('open', 'Open'),
        ('investigating', 'Investigating'),
        ('resolved', 'Resolved'),
        ('written_off', 'Written Off'),
    ], string='Status', default='open')
    assigned_to_id = fields.Many2one('res.users', string='Assigned To')
    resolution_notes = fields.Text(string='Resolution Notes')
    resolved_at = fields.Datetime(string='Resolved At')
    resolved_by_id = fields.Many2one('res.users', string='Resolved By')

    def action_resolve(self, notes=None):
        """Mark discrepancy as resolved."""
        self.ensure_one()
        self.write({
            'state': 'resolved',
            'resolution_notes': notes,
            'resolved_at': fields.Datetime.now(),
            'resolved_by_id': self.env.user.id,
        })
```

**Task 1.3: Configuration (2h)**

```python
# File: smart_dairy_reconciliation/models/reconciliation_config.py

from odoo import models, fields, api


class ReconciliationConfig(models.Model):
    _name = 'reconciliation.config'
    _description = 'Reconciliation Configuration'

    name = fields.Char(string='Name', required=True)
    active = fields.Boolean(default=True)
    company_id = fields.Many2one('res.company', default=lambda self: self.env.company)

    # Matching Settings
    auto_match_enabled = fields.Boolean(string='Enable Auto-Matching', default=True)
    match_tolerance_amount = fields.Float(
        string='Amount Tolerance (BDT)',
        default=1.0,
        help='Maximum difference for automatic matching'
    )
    match_tolerance_percentage = fields.Float(
        string='Amount Tolerance (%)',
        default=0.1,
        help='Maximum percentage difference'
    )

    # Alert Settings
    alert_threshold_amount = fields.Monetary(
        string='Alert Threshold Amount',
        currency_field='currency_id',
        default=1000.0
    )
    alert_recipients = fields.Many2many(
        'res.users',
        'reconciliation_alert_users_rel',
        string='Alert Recipients'
    )
    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.ref('base.BDT'))

    # Schedule Settings
    auto_reconcile_time = fields.Float(
        string='Auto Reconcile Time',
        default=23.0,
        help='Hour of day for automatic reconciliation (24h format)'
    )

    # Provider-specific settings
    provider_config_ids = fields.One2many(
        'reconciliation.provider.config',
        'config_id',
        string='Provider Configurations'
    )


class ReconciliationProviderConfig(models.Model):
    _name = 'reconciliation.provider.config'
    _description = 'Provider Reconciliation Config'

    config_id = fields.Many2one('reconciliation.config', string='Configuration')
    provider_code = fields.Selection([
        ('bkash', 'bKash'),
        ('nagad', 'Nagad'),
        ('rocket', 'Rocket'),
        ('sslcommerz', 'SSLCommerz'),
        ('cod', 'COD'),
    ], string='Provider', required=True)

    enabled = fields.Boolean(string='Enabled', default=True)
    settlement_delay_hours = fields.Integer(
        string='Settlement Delay (hours)',
        default=24,
        help='Expected hours before settlement appears'
    )
    reference_field = fields.Char(
        string='Reference Field',
        help='Transaction field to match against settlement'
    )
```

#### Dev 2 (Full-Stack) - 8 Hours

**Task 2.1: Provider Settlement Model (4h)**

```python
# File: smart_dairy_reconciliation/models/provider_settlement.py

from odoo import models, fields, api
from datetime import datetime
import logging

_logger = logging.getLogger(__name__)


class ProviderSettlement(models.Model):
    _name = 'provider.settlement'
    _description = 'Provider Settlement'
    _order = 'settlement_date desc'

    name = fields.Char(string='Reference', required=True)
    provider_code = fields.Selection([
        ('bkash', 'bKash'),
        ('nagad', 'Nagad'),
        ('rocket', 'Rocket'),
        ('sslcommerz', 'SSLCommerz'),
    ], string='Provider', required=True)

    settlement_date = fields.Date(string='Settlement Date', required=True)
    import_date = fields.Datetime(string='Import Date', default=fields.Datetime.now)
    source = fields.Selection([
        ('api', 'API'),
        ('manual', 'Manual Import'),
        ('bank_statement', 'Bank Statement'),
    ], string='Source')

    # Totals
    total_transactions = fields.Integer(string='Total Transactions')
    gross_amount = fields.Monetary(string='Gross Amount', currency_field='currency_id')
    fees = fields.Monetary(string='Fees', currency_field='currency_id')
    net_amount = fields.Monetary(string='Net Amount', currency_field='currency_id')
    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.ref('base.BDT'))

    # Lines
    line_ids = fields.One2many(
        'provider.settlement.line',
        'settlement_id',
        string='Lines'
    )

    # Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('confirmed', 'Confirmed'),
        ('reconciled', 'Reconciled'),
    ], default='draft')

    reconciliation_batch_id = fields.Many2one('reconciliation.batch')


class ProviderSettlementLine(models.Model):
    _name = 'provider.settlement.line'
    _description = 'Provider Settlement Line'

    settlement_id = fields.Many2one(
        'provider.settlement',
        string='Settlement',
        ondelete='cascade'
    )
    provider_code = fields.Selection(related='settlement_id.provider_code')

    # Transaction info
    transaction_ref = fields.Char(string='Transaction Reference', required=True)
    provider_ref = fields.Char(string='Provider Reference')
    bank_ref = fields.Char(string='Bank Reference')

    transaction_date = fields.Datetime(string='Transaction Date')
    settlement_date = fields.Date(string='Settlement Date')

    # Amounts
    gross_amount = fields.Monetary(string='Gross Amount', currency_field='currency_id')
    fee_amount = fields.Monetary(string='Fee', currency_field='currency_id')
    net_amount = fields.Monetary(string='Net Amount', currency_field='currency_id')
    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.ref('base.BDT'))

    # Status
    transaction_status = fields.Char(string='Status')
    is_refund = fields.Boolean(string='Is Refund', default=False)

    # Matching
    is_matched = fields.Boolean(string='Is Matched', default=False)
    matched_transaction_id = fields.Many2one('payment.transaction', string='Matched Transaction')
    match_id = fields.Many2one('reconciliation.match', string='Match Record')
```

**Task 2.2: Bank Statement Model (2h)**

```python
# File: smart_dairy_reconciliation/models/bank_statement.py

from odoo import models, fields, api
import logging

_logger = logging.getLogger(__name__)


class BankStatementImport(models.Model):
    _name = 'bank.statement.import'
    _description = 'Bank Statement Import'
    _order = 'import_date desc'

    name = fields.Char(string='Reference', required=True)
    bank_account = fields.Char(string='Bank Account')
    bank_name = fields.Char(string='Bank Name')

    statement_date = fields.Date(string='Statement Date')
    import_date = fields.Datetime(string='Import Date', default=fields.Datetime.now)

    file_name = fields.Char(string='File Name')
    file_data = fields.Binary(string='File', attachment=True)
    file_format = fields.Selection([
        ('csv', 'CSV'),
        ('mt940', 'MT940'),
        ('excel', 'Excel'),
    ], string='Format')

    # Parsing result
    total_lines = fields.Integer(string='Total Lines')
    parsed_lines = fields.Integer(string='Parsed Lines')
    error_lines = fields.Integer(string='Error Lines')

    line_ids = fields.One2many(
        'bank.statement.line',
        'import_id',
        string='Lines'
    )

    state = fields.Selection([
        ('draft', 'Draft'),
        ('parsed', 'Parsed'),
        ('processed', 'Processed'),
        ('error', 'Error'),
    ], default='draft')

    error_message = fields.Text(string='Error Message')


class BankStatementLine(models.Model):
    _name = 'bank.statement.line'
    _description = 'Bank Statement Line'

    import_id = fields.Many2one(
        'bank.statement.import',
        string='Import',
        ondelete='cascade'
    )

    # Transaction details
    transaction_date = fields.Date(string='Date')
    value_date = fields.Date(string='Value Date')
    reference = fields.Char(string='Reference')
    description = fields.Text(string='Description')

    # Amounts
    debit = fields.Monetary(string='Debit', currency_field='currency_id')
    credit = fields.Monetary(string='Credit', currency_field='currency_id')
    balance = fields.Monetary(string='Balance', currency_field='currency_id')
    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.ref('base.BDT'))

    # Matching
    is_matched = fields.Boolean(string='Is Matched', default=False)
    matched_settlement_id = fields.Many2one('provider.settlement', string='Matched Settlement')

    # Parsing
    raw_data = fields.Text(string='Raw Data')
    parse_error = fields.Text(string='Parse Error')
```

**Task 2.3: Settlement Fetcher Service (2h)**

```python
# File: smart_dairy_reconciliation/services/settlement_fetcher.py

from odoo import models, api
from datetime import datetime, timedelta
import requests
import logging

_logger = logging.getLogger(__name__)


class SettlementFetcher(models.AbstractModel):
    _name = 'settlement.fetcher'
    _description = 'Provider Settlement Fetcher'

    @api.model
    def fetch_all_settlements(self, date_from, date_to):
        """Fetch settlements from all providers."""
        results = {}

        providers = ['bkash', 'nagad', 'rocket', 'sslcommerz']
        for provider in providers:
            try:
                result = self._fetch_provider_settlement(provider, date_from, date_to)
                results[provider] = result
            except Exception as e:
                _logger.error(f"Failed to fetch {provider} settlement: {str(e)}")
                results[provider] = {'success': False, 'error': str(e)}

        return results

    def _fetch_provider_settlement(self, provider_code, date_from, date_to):
        """Fetch settlement from specific provider."""
        method_map = {
            'bkash': self._fetch_bkash_settlement,
            'nagad': self._fetch_nagad_settlement,
            'rocket': self._fetch_rocket_settlement,
            'sslcommerz': self._fetch_sslcommerz_settlement,
        }

        fetcher = method_map.get(provider_code)
        if fetcher:
            return fetcher(date_from, date_to)
        return {'success': False, 'error': 'Unknown provider'}

    def _fetch_bkash_settlement(self, date_from, date_to):
        """Fetch bKash settlement via API."""
        provider = self.env['payment.provider'].search([
            ('code', '=', 'bkash'),
            ('state', '!=', 'disabled'),
        ], limit=1)

        if not provider:
            return {'success': False, 'error': 'bKash provider not configured'}

        # bKash settlement API call
        base_url = provider._get_bkash_base_url()
        url = f"{base_url}/tokenized/checkout/payment/settlement"

        # Get token
        token = provider._bkash_get_token()

        headers = {
            'Authorization': f'Bearer {token}',
            'X-App-Key': provider.bkash_app_key,
            'Content-Type': 'application/json',
        }

        params = {
            'fromDate': date_from.strftime('%Y-%m-%d'),
            'toDate': date_to.strftime('%Y-%m-%d'),
        }

        try:
            response = requests.get(url, headers=headers, params=params, timeout=60)
            data = response.json()

            if response.status_code == 200:
                return self._process_bkash_settlement(data, date_from)
            return {'success': False, 'error': data.get('message', 'API error')}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    def _process_bkash_settlement(self, data, settlement_date):
        """Process bKash settlement data."""
        settlement = self.env['provider.settlement'].create({
            'name': f"BKASH-{settlement_date.strftime('%Y%m%d')}",
            'provider_code': 'bkash',
            'settlement_date': settlement_date,
            'source': 'api',
            'total_transactions': len(data.get('transactions', [])),
            'gross_amount': data.get('totalAmount', 0),
            'fees': data.get('totalFee', 0),
            'net_amount': data.get('netAmount', 0),
        })

        for txn in data.get('transactions', []):
            self.env['provider.settlement.line'].create({
                'settlement_id': settlement.id,
                'transaction_ref': txn.get('merchantInvoiceNumber'),
                'provider_ref': txn.get('trxID'),
                'transaction_date': txn.get('transactionTime'),
                'gross_amount': float(txn.get('amount', 0)),
                'fee_amount': float(txn.get('fee', 0)),
                'net_amount': float(txn.get('netAmount', 0)),
                'transaction_status': txn.get('transactionStatus'),
            })

        return {'success': True, 'settlement_id': settlement.id}

    def _fetch_sslcommerz_settlement(self, date_from, date_to):
        """Fetch SSLCommerz settlement via API."""
        provider = self.env['payment.provider'].search([
            ('code', '=', 'sslcommerz'),
            ('state', '!=', 'disabled'),
        ], limit=1)

        if not provider:
            return {'success': False, 'error': 'SSLCommerz provider not configured'}

        if provider.sslc_mode == 'sandbox':
            url = 'https://sandbox.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php'
        else:
            url = 'https://securepay.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php'

        params = {
            'store_id': provider.sslc_store_id,
            'store_passwd': provider.sslc_store_passwd,
            'from_date': date_from.strftime('%Y-%m-%d'),
            'to_date': date_to.strftime('%Y-%m-%d'),
            'format': 'json',
        }

        try:
            response = requests.get(url, params=params, timeout=60)
            data = response.json()

            if data.get('status') == 'SUCCESS':
                return self._process_sslcommerz_settlement(data, date_from)
            return {'success': False, 'error': data.get('failedreason', 'API error')}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    def _process_sslcommerz_settlement(self, data, settlement_date):
        """Process SSLCommerz settlement data."""
        transactions = data.get('element', [])

        settlement = self.env['provider.settlement'].create({
            'name': f"SSLC-{settlement_date.strftime('%Y%m%d')}",
            'provider_code': 'sslcommerz',
            'settlement_date': settlement_date,
            'source': 'api',
            'total_transactions': len(transactions),
            'gross_amount': sum(float(t.get('amount', 0)) for t in transactions),
            'fees': sum(float(t.get('processing_fee', 0)) for t in transactions),
            'net_amount': sum(float(t.get('store_amount', 0)) for t in transactions),
        })

        for txn in transactions:
            self.env['provider.settlement.line'].create({
                'settlement_id': settlement.id,
                'transaction_ref': txn.get('tran_id'),
                'provider_ref': txn.get('val_id'),
                'bank_ref': txn.get('bank_tran_id'),
                'transaction_date': txn.get('tran_date'),
                'gross_amount': float(txn.get('amount', 0)),
                'fee_amount': float(txn.get('processing_fee', 0)),
                'net_amount': float(txn.get('store_amount', 0)),
                'transaction_status': txn.get('status'),
            })

        return {'success': True, 'settlement_id': settlement.id}

    def _fetch_nagad_settlement(self, date_from, date_to):
        """Fetch Nagad settlement - placeholder for API integration."""
        _logger.info("Nagad settlement fetch not implemented - use manual import")
        return {'success': False, 'error': 'Manual import required for Nagad'}

    def _fetch_rocket_settlement(self, date_from, date_to):
        """Fetch Rocket settlement - placeholder for API integration."""
        _logger.info("Rocket settlement fetch not implemented - use manual import")
        return {'success': False, 'error': 'Manual import required for Rocket'}
```

#### Dev 3 (Frontend/Mobile Lead) - 8 Hours

**Task 3.1: Reconciliation Dashboard (4h)**

```javascript
/** @odoo-module **/
// File: smart_dairy_reconciliation/static/src/js/reconciliation_dashboard.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";
import { _t } from "@web/core/l10n/translation";

export class ReconciliationDashboard extends Component {
    static template = "smart_dairy_reconciliation.Dashboard";

    setup() {
        this.rpc = useService("rpc");
        this.action = useService("action");

        this.state = useState({
            loading: true,
            summary: {
                pending_reconciliation: 0,
                open_discrepancies: 0,
                today_matched: 0,
                today_variance: 0,
            },
            providerStats: [],
            recentBatches: [],
            topDiscrepancies: [],
        });

        onWillStart(() => this.loadDashboardData());
    }

    async loadDashboardData() {
        try {
            const result = await this.rpc('/reconciliation/dashboard/data');

            this.state.summary = result.summary;
            this.state.providerStats = result.provider_stats;
            this.state.recentBatches = result.recent_batches;
            this.state.topDiscrepancies = result.top_discrepancies;
        } catch (error) {
            console.error("Failed to load dashboard:", error);
        } finally {
            this.state.loading = false;
        }
    }

    async startReconciliation(providerCode) {
        try {
            const result = await this.rpc('/reconciliation/start', {
                provider_code: providerCode,
                date: new Date().toISOString().split('T')[0],
            });

            if (result.success) {
                this.loadDashboardData();
            }
        } catch (error) {
            console.error("Reconciliation failed:", error);
        }
    }

    openBatch(batchId) {
        this.action.doAction({
            type: 'ir.actions.act_window',
            res_model: 'reconciliation.batch',
            res_id: batchId,
            views: [[false, 'form']],
        });
    }

    openDiscrepancy(discrepancyId) {
        this.action.doAction({
            type: 'ir.actions.act_window',
            res_model: 'reconciliation.discrepancy',
            res_id: discrepancyId,
            views: [[false, 'form']],
        });
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat('en-BD', {
            style: 'currency',
            currency: 'BDT',
        }).format(amount);
    }

    getStatusColor(state) {
        const colors = {
            completed: 'success',
            processing: 'warning',
            failed: 'danger',
            draft: 'secondary',
        };
        return colors[state] || 'secondary';
    }
}
```

**Task 3.2: Dashboard Template (2h)**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- File: smart_dairy_reconciliation/static/src/xml/dashboard_templates.xml -->

<templates xml:space="preserve">
    <t t-name="smart_dairy_reconciliation.Dashboard">
        <div class="reconciliation-dashboard p-4">
            <t t-if="state.loading">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary"/>
                    <p class="mt-2">Loading dashboard...</p>
                </div>
            </t>

            <t t-else="">
                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2">Pending Reconciliation</h6>
                                <h2 class="card-title mb-0" t-esc="state.summary.pending_reconciliation"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2">Open Discrepancies</h6>
                                <h2 class="card-title mb-0" t-esc="state.summary.open_discrepancies"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2">Today Matched</h6>
                                <h2 class="card-title mb-0" t-esc="state.summary.today_matched"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card" t-att-class="state.summary.today_variance >= 0 ? 'bg-info' : 'bg-warning'">
                            <div class="card-body text-white">
                                <h6 class="card-subtitle mb-2">Today's Variance</h6>
                                <h2 class="card-title mb-0" t-esc="formatCurrency(state.summary.today_variance)"/>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Provider Stats -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Provider Status</h5>
                                <button class="btn btn-primary btn-sm"
                                        t-on-click="() => startReconciliation('all')">
                                    <i class="fa fa-sync me-1"/> Reconcile All
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Provider</th>
                                                <th>Last Reconciled</th>
                                                <th>Expected</th>
                                                <th>Received</th>
                                                <th>Matched %</th>
                                                <th>Variance</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <t t-foreach="state.providerStats" t-as="provider" t-key="provider.code">
                                                <tr>
                                                    <td>
                                                        <strong t-esc="provider.name"/>
                                                    </td>
                                                    <td t-esc="provider.last_reconciled || 'Never'"/>
                                                    <td t-esc="formatCurrency(provider.expected_amount)"/>
                                                    <td t-esc="formatCurrency(provider.received_amount)"/>
                                                    <td>
                                                        <div class="progress" style="width: 100px;">
                                                            <div class="progress-bar bg-success"
                                                                 t-att-style="'width: ' + provider.match_rate + '%'"/>
                                                        </div>
                                                        <small t-esc="provider.match_rate + '%'"/>
                                                    </td>
                                                    <td t-att-class="provider.variance >= 0 ? 'text-success' : 'text-danger'"
                                                        t-esc="formatCurrency(provider.variance)"/>
                                                    <td>
                                                        <button class="btn btn-outline-primary btn-sm"
                                                                t-on-click="() => startReconciliation(provider.code)">
                                                            Reconcile
                                                        </button>
                                                    </td>
                                                </tr>
                                            </t>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Recent Batches -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Recent Reconciliations</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <t t-foreach="state.recentBatches" t-as="batch" t-key="batch.id">
                                        <a href="#" class="list-group-item list-group-item-action"
                                           t-on-click.prevent="() => openBatch(batch.id)">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1" t-esc="batch.name"/>
                                                <span t-att-class="'badge bg-' + getStatusColor(batch.state)"
                                                      t-esc="batch.state"/>
                                            </div>
                                            <p class="mb-1 small text-muted">
                                                <t t-esc="batch.provider"/> |
                                                <t t-esc="batch.total_matched"/> matched |
                                                <t t-esc="batch.total_discrepancies"/> discrepancies
                                            </p>
                                        </a>
                                    </t>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Open Discrepancies -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Open Discrepancies</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <t t-foreach="state.topDiscrepancies" t-as="disc" t-key="disc.id">
                                        <a href="#" class="list-group-item list-group-item-action"
                                           t-on-click.prevent="() => openDiscrepancy(disc.id)">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1" t-esc="disc.type_display"/>
                                                <span class="text-danger" t-esc="formatCurrency(disc.difference)"/>
                                            </div>
                                            <p class="mb-1 small text-muted" t-esc="disc.description"/>
                                        </a>
                                    </t>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </t>
        </div>
    </t>
</templates>
```

**Task 3.3: Styling (2h)**

```css
/* File: smart_dairy_reconciliation/static/src/css/reconciliation.css */

.reconciliation-dashboard {
    background-color: #f8f9fa;
    min-height: 100vh;
}

.reconciliation-dashboard .card {
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 1rem;
}

.reconciliation-dashboard .card-header {
    background-color: white;
    border-bottom: 1px solid #eee;
}

.reconciliation-dashboard .progress {
    height: 8px;
    background-color: #e9ecef;
}

.reconciliation-dashboard .list-group-item {
    border-left: 3px solid transparent;
    transition: border-color 0.2s;
}

.reconciliation-dashboard .list-group-item:hover {
    border-left-color: #007bff;
}

.variance-positive {
    color: #28a745;
}

.variance-negative {
    color: #dc3545;
}
```

#### End of Day 491 Deliverables

- [ ] Module structure created
- [ ] Core reconciliation models implemented
- [ ] Configuration model completed
- [ ] Provider settlement models created
- [ ] Bank statement models defined
- [ ] Settlement fetcher service started
- [ ] Dashboard UI component created

---

### Day 492-495: Reconciliation Engine & Matching

#### Dev 1 (Backend Lead)

**Reconciliation Engine**

```python
# File: smart_dairy_reconciliation/services/reconciliation_engine.py

from odoo import models, api, fields
from datetime import datetime, timedelta
import logging

_logger = logging.getLogger(__name__)


class ReconciliationEngine(models.AbstractModel):
    _name = 'reconciliation.engine'
    _description = 'Reconciliation Engine'

    @api.model
    def run_reconciliation(self, batch):
        """
        Run reconciliation for a batch.

        Args:
            batch: reconciliation.batch record

        Returns:
            dict with reconciliation results
        """
        _logger.info(f"Starting reconciliation for batch {batch.name}")

        provider_code = batch.provider_code
        recon_date = batch.date

        # Get expected transactions
        expected_txns = self._get_expected_transactions(provider_code, recon_date)

        # Get settlement data
        settlement_lines = self._get_settlement_lines(provider_code, recon_date)

        # Run matching algorithm
        matches, unmatched_expected, unmatched_settlement = self._match_transactions(
            expected_txns, settlement_lines, batch
        )

        # Create discrepancies for unmatched items
        discrepancies = self._create_discrepancies(
            batch, unmatched_expected, unmatched_settlement
        )

        # Calculate totals
        expected_amount = sum(expected_txns.mapped('amount'))
        received_amount = sum(settlement_lines.mapped('net_amount'))

        result = {
            'total_expected': len(expected_txns),
            'total_received': len(settlement_lines),
            'total_matched': len(matches),
            'total_unmatched': len(unmatched_expected) + len(unmatched_settlement),
            'total_discrepancies': len(discrepancies),
            'expected_amount': expected_amount,
            'received_amount': received_amount,
        }

        _logger.info(f"Reconciliation completed: {result}")
        return result

    def _get_expected_transactions(self, provider_code, recon_date):
        """Get expected transactions from payment module."""
        domain = [
            ('state', '=', 'done'),
            ('create_date', '>=', recon_date),
            ('create_date', '<', recon_date + timedelta(days=1)),
        ]

        if provider_code != 'all':
            domain.append(('provider_code', '=', provider_code))

        return self.env['payment.transaction'].search(domain)

    def _get_settlement_lines(self, provider_code, recon_date):
        """Get settlement lines from providers."""
        domain = [
            ('settlement_date', '=', recon_date),
            ('is_matched', '=', False),
        ]

        if provider_code != 'all':
            domain.append(('provider_code', '=', provider_code))

        return self.env['provider.settlement.line'].search(domain)

    def _match_transactions(self, expected_txns, settlement_lines, batch):
        """
        Match transactions with settlement lines.

        Returns:
            tuple: (matches, unmatched_expected, unmatched_settlement)
        """
        config = self.env['reconciliation.config'].search([], limit=1)
        tolerance = config.match_tolerance_amount if config else 1.0

        matches = []
        matched_txn_ids = set()
        matched_settlement_ids = set()

        # First pass: Exact reference match
        for txn in expected_txns:
            for line in settlement_lines:
                if line.id in matched_settlement_ids:
                    continue

                if self._is_reference_match(txn, line):
                    match = self._create_match(batch, txn, line, 'exact', 100.0)
                    matches.append(match)
                    matched_txn_ids.add(txn.id)
                    matched_settlement_ids.add(line.id)
                    break

        # Second pass: Amount + date match for unmatched
        for txn in expected_txns:
            if txn.id in matched_txn_ids:
                continue

            for line in settlement_lines:
                if line.id in matched_settlement_ids:
                    continue

                if self._is_amount_match(txn, line, tolerance):
                    match = self._create_match(batch, txn, line, 'amount_match', 85.0)
                    matches.append(match)
                    matched_txn_ids.add(txn.id)
                    matched_settlement_ids.add(line.id)
                    break

        # Identify unmatched
        unmatched_expected = expected_txns.filtered(lambda t: t.id not in matched_txn_ids)
        unmatched_settlement = settlement_lines.filtered(lambda l: l.id not in matched_settlement_ids)

        return matches, unmatched_expected, unmatched_settlement

    def _is_reference_match(self, txn, line):
        """Check if transaction and settlement line match by reference."""
        # Check various reference fields
        txn_refs = [
            txn.reference,
            getattr(txn, 'bkash_payment_id', None),
            getattr(txn, 'nagad_payment_ref_id', None),
            getattr(txn, 'sslc_tran_id', None),
        ]

        line_refs = [
            line.transaction_ref,
            line.provider_ref,
            line.bank_ref,
        ]

        for txn_ref in txn_refs:
            if txn_ref and txn_ref in line_refs:
                return True

        return False

    def _is_amount_match(self, txn, line, tolerance):
        """Check if transaction and settlement line match by amount."""
        diff = abs(txn.amount - line.net_amount)
        return diff <= tolerance

    def _create_match(self, batch, txn, line, match_type, confidence):
        """Create reconciliation match record."""
        match = self.env['reconciliation.match'].create({
            'batch_id': batch.id,
            'transaction_id': txn.id,
            'settlement_line_id': line.id,
            'match_type': match_type,
            'match_confidence': confidence,
            'expected_amount': txn.amount,
            'received_amount': line.net_amount,
        })

        # Mark settlement line as matched
        line.write({
            'is_matched': True,
            'matched_transaction_id': txn.id,
            'match_id': match.id,
        })

        return match

    def _create_discrepancies(self, batch, unmatched_expected, unmatched_settlement):
        """Create discrepancy records for unmatched items."""
        discrepancies = []

        # Missing settlements (we have transaction but no settlement)
        for txn in unmatched_expected:
            disc = self.env['reconciliation.discrepancy'].create({
                'batch_id': batch.id,
                'transaction_id': txn.id,
                'discrepancy_type': 'missing_settlement',
                'description': f'No settlement found for transaction {txn.reference}',
                'expected_amount': txn.amount,
                'actual_amount': 0,
                'difference': -txn.amount,
            })
            discrepancies.append(disc)

        # Missing transactions (we have settlement but no transaction)
        for line in unmatched_settlement:
            disc = self.env['reconciliation.discrepancy'].create({
                'batch_id': batch.id,
                'settlement_line_id': line.id,
                'discrepancy_type': 'missing_transaction',
                'description': f'No transaction found for settlement {line.transaction_ref}',
                'expected_amount': 0,
                'actual_amount': line.net_amount,
                'difference': line.net_amount,
            })
            discrepancies.append(disc)

        return discrepancies

    @api.model
    def run_daily_reconciliation(self):
        """Cron job for daily reconciliation."""
        yesterday = fields.Date.today() - timedelta(days=1)

        for provider in ['bkash', 'nagad', 'rocket', 'sslcommerz', 'cod']:
            batch = self.env['reconciliation.batch'].create({
                'date': yesterday,
                'provider_code': provider,
            })
            batch.action_start_reconciliation()
```

**Bank Statement Parser**

```python
# File: smart_dairy_reconciliation/services/statement_parser.py

from odoo import models, api
import csv
import io
import logging

_logger = logging.getLogger(__name__)


class BankStatementParser(models.AbstractModel):
    _name = 'bank.statement.parser'
    _description = 'Bank Statement Parser'

    @api.model
    def parse_statement(self, import_record):
        """
        Parse bank statement file.

        Args:
            import_record: bank.statement.import record

        Returns:
            dict with parsing results
        """
        if import_record.file_format == 'csv':
            return self._parse_csv(import_record)
        elif import_record.file_format == 'mt940':
            return self._parse_mt940(import_record)
        elif import_record.file_format == 'excel':
            return self._parse_excel(import_record)
        else:
            return {'success': False, 'error': 'Unsupported format'}

    def _parse_csv(self, import_record):
        """Parse CSV bank statement."""
        import base64

        try:
            file_content = base64.b64decode(import_record.file_data)
            csv_data = io.StringIO(file_content.decode('utf-8'))
            reader = csv.DictReader(csv_data)

            lines_created = 0
            errors = []

            for row in reader:
                try:
                    self._create_statement_line(import_record, row)
                    lines_created += 1
                except Exception as e:
                    errors.append(f"Row error: {str(e)}")

            import_record.write({
                'state': 'parsed',
                'total_lines': lines_created + len(errors),
                'parsed_lines': lines_created,
                'error_lines': len(errors),
            })

            return {
                'success': True,
                'parsed': lines_created,
                'errors': errors,
            }

        except Exception as e:
            import_record.write({
                'state': 'error',
                'error_message': str(e),
            })
            return {'success': False, 'error': str(e)}

    def _create_statement_line(self, import_record, row):
        """Create bank statement line from parsed row."""
        # Map common CSV column names
        date_fields = ['Date', 'Transaction Date', 'Value Date', 'date']
        ref_fields = ['Reference', 'Ref', 'Transaction ID', 'reference']
        desc_fields = ['Description', 'Narration', 'Details', 'description']
        debit_fields = ['Debit', 'Withdrawal', 'debit']
        credit_fields = ['Credit', 'Deposit', 'credit']

        def get_field(row, field_names, default=''):
            for field in field_names:
                if field in row and row[field]:
                    return row[field]
            return default

        from datetime import datetime

        date_str = get_field(row, date_fields)
        try:
            txn_date = datetime.strptime(date_str, '%Y-%m-%d').date()
        except:
            try:
                txn_date = datetime.strptime(date_str, '%d/%m/%Y').date()
            except:
                txn_date = None

        debit_str = get_field(row, debit_fields, '0')
        credit_str = get_field(row, credit_fields, '0')

        debit = float(debit_str.replace(',', '')) if debit_str else 0
        credit = float(credit_str.replace(',', '')) if credit_str else 0

        self.env['bank.statement.line'].create({
            'import_id': import_record.id,
            'transaction_date': txn_date,
            'reference': get_field(row, ref_fields),
            'description': get_field(row, desc_fields),
            'debit': debit,
            'credit': credit,
            'raw_data': str(row),
        })

    def _parse_mt940(self, import_record):
        """Parse MT940 SWIFT format."""
        # MT940 parsing implementation
        _logger.info("MT940 parsing not fully implemented")
        return {'success': False, 'error': 'MT940 parsing coming soon'}

    def _parse_excel(self, import_record):
        """Parse Excel bank statement."""
        import base64

        try:
            import openpyxl
            from io import BytesIO

            file_content = base64.b64decode(import_record.file_data)
            workbook = openpyxl.load_workbook(BytesIO(file_content))
            sheet = workbook.active

            lines_created = 0
            errors = []

            headers = [cell.value for cell in sheet[1]]

            for row in sheet.iter_rows(min_row=2, values_only=True):
                row_dict = dict(zip(headers, row))
                try:
                    self._create_statement_line(import_record, row_dict)
                    lines_created += 1
                except Exception as e:
                    errors.append(str(e))

            import_record.write({
                'state': 'parsed',
                'parsed_lines': lines_created,
                'error_lines': len(errors),
            })

            return {'success': True, 'parsed': lines_created}

        except Exception as e:
            return {'success': False, 'error': str(e)}
```

---

### Day 496-500: Reports, Alerts & Testing

*(Complete settlement reports, alert system, audit trail, and testing)*

---

## 6. Technical Specifications

### 6.1 API Endpoints

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/reconciliation/dashboard/data` | GET | Finance | Dashboard data |
| `/reconciliation/start` | POST | Finance | Start reconciliation |
| `/reconciliation/batch/<id>` | GET | Finance | Batch details |
| `/reconciliation/discrepancy/<id>/resolve` | POST | Finance | Resolve discrepancy |
| `/reconciliation/import/statement` | POST | Finance | Import statement |
| `/reconciliation/reports/settlement` | GET | Finance | Settlement report |

### 6.2 Matching Algorithm

```
1. EXACT MATCH (Confidence: 100%)
   - Reference exact match
   - Provider reference exact match

2. AMOUNT MATCH (Confidence: 85%)
   - Amount within tolerance (±1 BDT)
   - Same date range

3. FUZZY MATCH (Confidence: 70%)
   - Partial reference match
   - Amount within percentage tolerance
```

---

## 7. Database Schema

### 7.1 Reconciliation Tables

```sql
-- Reconciliation Batch
CREATE TABLE reconciliation_batch (
    id SERIAL PRIMARY KEY,
    name VARCHAR(64),
    date DATE NOT NULL,
    provider_code VARCHAR(20),
    state VARCHAR(20) DEFAULT 'draft',
    total_expected INTEGER DEFAULT 0,
    total_received INTEGER DEFAULT 0,
    total_matched INTEGER DEFAULT 0,
    expected_amount DECIMAL(15,2),
    received_amount DECIMAL(15,2),
    started_at TIMESTAMP,
    completed_at TIMESTAMP,
    created_by_id INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT NOW()
);

-- Reconciliation Match
CREATE TABLE reconciliation_match (
    id SERIAL PRIMARY KEY,
    batch_id INTEGER REFERENCES reconciliation_batch(id),
    transaction_id INTEGER REFERENCES payment_transaction(id),
    settlement_line_id INTEGER REFERENCES provider_settlement_line(id),
    match_type VARCHAR(20),
    match_confidence DECIMAL(5,2),
    expected_amount DECIMAL(15,2),
    received_amount DECIMAL(15,2),
    matched_at TIMESTAMP DEFAULT NOW()
);

-- Reconciliation Discrepancy
CREATE TABLE reconciliation_discrepancy (
    id SERIAL PRIMARY KEY,
    batch_id INTEGER REFERENCES reconciliation_batch(id),
    discrepancy_type VARCHAR(30),
    description TEXT,
    expected_amount DECIMAL(15,2),
    actual_amount DECIMAL(15,2),
    difference DECIMAL(15,2),
    state VARCHAR(20) DEFAULT 'open',
    assigned_to_id INTEGER REFERENCES res_users(id),
    resolved_at TIMESTAMP,
    resolved_by_id INTEGER REFERENCES res_users(id)
);

CREATE INDEX idx_rb_date ON reconciliation_batch(date);
CREATE INDEX idx_rb_provider ON reconciliation_batch(provider_code);
CREATE INDEX idx_rd_state ON reconciliation_discrepancy(state);
```

---

## 8. API Documentation

### 8.1 Start Reconciliation

```json
POST /reconciliation/start
{
    "provider_code": "bkash",
    "date": "2024-01-15"
}

Response:
{
    "success": true,
    "batch_id": 123,
    "message": "Reconciliation started"
}
```

### 8.2 Dashboard Data

```json
GET /reconciliation/dashboard/data

Response:
{
    "summary": {
        "pending_reconciliation": 5,
        "open_discrepancies": 12,
        "today_matched": 156,
        "today_variance": -250.00
    },
    "provider_stats": [
        {
            "code": "bkash",
            "name": "bKash",
            "last_reconciled": "2024-01-14",
            "expected_amount": 150000.00,
            "received_amount": 149750.00,
            "match_rate": 98.5,
            "variance": -250.00
        }
    ],
    "recent_batches": [...],
    "top_discrepancies": [...]
}
```

---

## 9. Reconciliation Workflows

### 9.1 Daily Reconciliation Flow

```
┌─────────────────────────────────────────────────────────────┐
│                  DAILY RECONCILIATION                        │
├─────────────────────────────────────────────────────────────┤
│  1. Cron job triggers at 11 PM                              │
│  2. Fetch settlements from all providers                    │
│  3. Get expected transactions from database                 │
│  4. Run matching algorithm                                  │
│  5. Create discrepancies for unmatched                     │
│  6. Send alerts for significant discrepancies               │
│  7. Update batch status                                     │
└─────────────────────────────────────────────────────────────┘
```

### 9.2 Discrepancy Resolution Flow

```
Open → Investigating → Resolved/Written Off
         ↓
    Assigned to user
         ↓
    Investigation notes
         ↓
    Resolution action
```

---

## 10. Testing Requirements

### 10.1 Test Coverage

| Component | Target |
|-----------|--------|
| Reconciliation Engine | > 90% |
| Statement Parser | > 85% |
| Matching Algorithm | > 95% |
| Settlement Fetcher | > 80% |

### 10.2 Test Scenarios

```python
class TestReconciliationEngine(TransactionCase):

    def test_exact_match(self):
        """Test exact reference matching."""
        pass

    def test_amount_tolerance(self):
        """Test amount matching within tolerance."""
        pass

    def test_discrepancy_creation(self):
        """Test discrepancy creation for unmatched."""
        pass

    def test_daily_batch(self):
        """Test daily reconciliation batch."""
        pass
```

---

## 11. Risk & Mitigation

| Risk | Impact | Mitigation |
|------|--------|------------|
| Provider API unavailable | High | Manual import option |
| Large data volumes | Medium | Batch processing, pagination |
| Matching accuracy | High | Multiple matching passes |
| False positives | Medium | Confidence scoring |
| Audit gaps | High | Complete trail logging |

---

## 12. Sign-off Checklist

### 12.1 Core Functionality

- [ ] Reconciliation engine working
- [ ] Auto-matching accurate
- [ ] Manual matching functional
- [ ] Discrepancy detection complete

### 12.2 Data Import

- [ ] CSV import working
- [ ] Provider API fetch working
- [ ] Statement parsing accurate

### 12.3 Reporting & UI

- [ ] Dashboard functional
- [ ] Settlement reports generating
- [ ] Alerts triggering correctly

### 12.4 Audit & Compliance

- [ ] Complete audit trail
- [ ] All actions logged
- [ ] Access controls enforced

---

**Document End**

*Milestone 75: Payment Reconciliation Engine*
*Days 491-500 | Phase 8: Operations - Payment & Logistics*
*Smart Dairy Digital Smart Portal + ERP System*
