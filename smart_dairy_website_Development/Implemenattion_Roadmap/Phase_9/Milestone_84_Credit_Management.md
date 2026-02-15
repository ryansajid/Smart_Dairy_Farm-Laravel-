# Milestone 84: Credit Management

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field                | Details                                                      |
| -------------------- | ------------------------------------------------------------ |
| **Document ID**      | SD-IMPL-P9-M84-v1.0                                          |
| **Milestone**        | 84 - Credit Management                                       |
| **Phase**            | Phase 9: Commerce - B2B Portal Foundation                    |
| **Days**             | 581-590                                                      |
| **Duration**         | 10 Working Days                                              |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 84 implements comprehensive credit management for B2B customers including credit limits, payment terms (Net 30/60/90), aging reports, automated credit holds, and collection workflows.

### 1.2 Scope

- Credit Limit Assignment by customer tier
- Payment Terms Configuration (Net 30/60/90)
- Credit Utilization Tracking in real-time
- Aging Report Engine (30/60/90/120 days)
- Automated Credit Holds
- Collection Workflow
- Credit Score Model
- Statement of Accounts
- OWL Credit Dashboard

---

## 2. Key Deliverables

| #  | Deliverable                          | Owner  | Priority |
| -- | ------------------------------------ | ------ | -------- |
| 1  | Credit Limit Model                   | Dev 1  | Critical |
| 2  | Payment Terms Configuration          | Dev 2  | Critical |
| 3  | Credit Utilization Tracking          | Dev 1  | Critical |
| 4  | Aging Report Engine                  | Dev 1  | High     |
| 5  | Automated Credit Holds               | Dev 1  | High     |
| 6  | Collection Workflow                  | Dev 2  | High     |
| 7  | Credit Score Model                   | Dev 1  | Medium   |
| 8  | Statement of Accounts                | Dev 2  | High     |
| 9  | OWL Credit Dashboard                 | Dev 3  | High     |
| 10 | Credit API Endpoints                 | Dev 2  | High     |

---

## 3. Day-by-Day Development Plan

### Day 581 (Day 1): Credit Limit Model

#### Dev 1 (Backend Lead) - 8 hours

```python
# odoo/addons/smart_dairy_b2b/models/b2b_credit.py
from odoo import models, fields, api
from odoo.exceptions import UserError
from datetime import date, timedelta

class B2BCredit(models.Model):
    _name = 'b2b.credit'
    _description = 'B2B Customer Credit'
    _inherit = ['mail.thread']

    customer_id = fields.Many2one('b2b.customer', string='Customer', required=True, ondelete='cascade')
    partner_id = fields.Many2one(related='customer_id.partner_id', store=True)

    # Credit Limit
    credit_limit = fields.Monetary(string='Credit Limit', tracking=True)
    credit_limit_currency_id = fields.Many2one('res.currency', default=lambda self: self.env.company.currency_id)

    # Utilization
    credit_used = fields.Monetary(string='Credit Used', compute='_compute_credit_used', store=True)
    credit_available = fields.Monetary(string='Credit Available', compute='_compute_credit_available')
    utilization_percentage = fields.Float(string='Utilization %', compute='_compute_utilization')

    # Payment Terms
    payment_term_id = fields.Many2one('account.payment.term', string='Payment Terms')

    # Credit Hold
    is_on_hold = fields.Boolean(string='On Credit Hold', default=False, tracking=True)
    hold_reason = fields.Text(string='Hold Reason')
    hold_date = fields.Datetime(string='Hold Date')
    hold_by = fields.Many2one('res.users', string='Held By')

    # Aging
    amount_due = fields.Monetary(string='Total Due', compute='_compute_aging')
    amount_overdue = fields.Monetary(string='Overdue Amount', compute='_compute_aging')
    overdue_days = fields.Integer(string='Max Overdue Days', compute='_compute_aging')
    aging_bucket_current = fields.Monetary(string='Current', compute='_compute_aging')
    aging_bucket_30 = fields.Monetary(string='1-30 Days', compute='_compute_aging')
    aging_bucket_60 = fields.Monetary(string='31-60 Days', compute='_compute_aging')
    aging_bucket_90 = fields.Monetary(string='61-90 Days', compute='_compute_aging')
    aging_bucket_120 = fields.Monetary(string='90+ Days', compute='_compute_aging')

    # Credit Score
    credit_score = fields.Integer(string='Credit Score', compute='_compute_credit_score')
    payment_history_score = fields.Integer(string='Payment History Score')
    order_frequency_score = fields.Integer(string='Order Frequency Score')

    # Timestamps
    last_payment_date = fields.Date(string='Last Payment Date')
    last_review_date = fields.Date(string='Last Review Date')
    next_review_date = fields.Date(string='Next Review Date')

    @api.depends('customer_id.partner_id')
    def _compute_credit_used(self):
        for record in self:
            invoices = self.env['account.move'].search([
                ('partner_id', '=', record.partner_id.id),
                ('move_type', '=', 'out_invoice'),
                ('state', '=', 'posted'),
                ('payment_state', 'in', ['not_paid', 'partial']),
            ])
            record.credit_used = sum(invoices.mapped('amount_residual'))

    @api.depends('credit_limit', 'credit_used')
    def _compute_credit_available(self):
        for record in self:
            record.credit_available = max(record.credit_limit - record.credit_used, 0)

    @api.depends('credit_limit', 'credit_used')
    def _compute_utilization(self):
        for record in self:
            if record.credit_limit:
                record.utilization_percentage = (record.credit_used / record.credit_limit) * 100
            else:
                record.utilization_percentage = 0

    @api.depends('customer_id.partner_id')
    def _compute_aging(self):
        today = date.today()
        for record in self:
            invoices = self.env['account.move'].search([
                ('partner_id', '=', record.partner_id.id),
                ('move_type', '=', 'out_invoice'),
                ('state', '=', 'posted'),
                ('payment_state', 'in', ['not_paid', 'partial']),
            ])

            record.amount_due = sum(invoices.mapped('amount_residual'))
            record.aging_bucket_current = 0
            record.aging_bucket_30 = 0
            record.aging_bucket_60 = 0
            record.aging_bucket_90 = 0
            record.aging_bucket_120 = 0
            record.overdue_days = 0

            for invoice in invoices:
                due_date = invoice.invoice_date_due or invoice.invoice_date
                days_overdue = (today - due_date).days if due_date else 0

                if days_overdue <= 0:
                    record.aging_bucket_current += invoice.amount_residual
                elif days_overdue <= 30:
                    record.aging_bucket_30 += invoice.amount_residual
                elif days_overdue <= 60:
                    record.aging_bucket_60 += invoice.amount_residual
                elif days_overdue <= 90:
                    record.aging_bucket_90 += invoice.amount_residual
                else:
                    record.aging_bucket_120 += invoice.amount_residual

                record.overdue_days = max(record.overdue_days, days_overdue)

            record.amount_overdue = record.aging_bucket_30 + record.aging_bucket_60 + \
                                    record.aging_bucket_90 + record.aging_bucket_120

    @api.depends('payment_history_score', 'order_frequency_score', 'utilization_percentage')
    def _compute_credit_score(self):
        for record in self:
            # Base score from components
            history = record.payment_history_score or 50
            frequency = record.order_frequency_score or 50

            # Utilization penalty
            util_penalty = 0
            if record.utilization_percentage > 90:
                util_penalty = 20
            elif record.utilization_percentage > 75:
                util_penalty = 10

            record.credit_score = min(max(int((history + frequency) / 2 - util_penalty), 0), 100)

    def action_place_hold(self, reason=None):
        """Place credit hold on customer"""
        self.ensure_one()
        self.write({
            'is_on_hold': True,
            'hold_reason': reason or 'Manual hold',
            'hold_date': fields.Datetime.now(),
            'hold_by': self.env.user.id,
        })
        self._send_hold_notification()

    def action_release_hold(self):
        """Release credit hold"""
        self.ensure_one()
        self.write({
            'is_on_hold': False,
            'hold_reason': False,
            'hold_date': False,
            'hold_by': False,
        })

    def check_credit_available(self, amount):
        """Check if credit is available for order"""
        self.ensure_one()
        if self.is_on_hold:
            return False, 'Customer is on credit hold'
        if amount > self.credit_available:
            return False, f'Insufficient credit. Available: {self.credit_available}'
        return True, None

    @api.model
    def cron_auto_hold(self):
        """Auto-hold customers with severe overdue"""
        credits = self.search([('is_on_hold', '=', False)])
        for credit in credits:
            if credit.overdue_days > 45 or credit.utilization_percentage > 95:
                credit.action_place_hold(
                    f'Auto-hold: {credit.overdue_days} days overdue, {credit.utilization_percentage:.1f}% utilization'
                )

    def _send_hold_notification(self):
        """Send notification when credit is placed on hold"""
        template = self.env.ref('smart_dairy_b2b.email_template_credit_hold', raise_if_not_found=False)
        if template:
            template.send_mail(self.id, force_send=True)
```

#### Dev 2 (Full-Stack) - 8 hours

```python
# Payment Terms Configuration extension
# odoo/addons/smart_dairy_b2b/models/b2b_payment_terms.py
from odoo import models, fields, api

class B2BPaymentTerms(models.Model):
    _inherit = 'account.payment.term'

    # B2B specific fields
    is_b2b_term = fields.Boolean(string='B2B Payment Term', default=False)
    min_tier_required = fields.Many2one('b2b.customer.tier', string='Minimum Tier Required')
    min_credit_score = fields.Integer(string='Minimum Credit Score')
    grace_period_days = fields.Integer(string='Grace Period (Days)', default=0)
    late_fee_percentage = fields.Float(string='Late Fee %', default=0)

    @api.model
    def get_available_terms(self, customer_id):
        """Get payment terms available for customer based on tier and credit score"""
        customer = self.env['b2b.customer'].browse(customer_id)
        credit = self.env['b2b.credit'].search([('customer_id', '=', customer_id)], limit=1)

        domain = [('is_b2b_term', '=', True)]

        available_terms = self.search(domain)
        result = self.env['account.payment.term']

        for term in available_terms:
            # Check tier requirement
            if term.min_tier_required:
                if customer.tier_id.sequence < term.min_tier_required.sequence:
                    continue
            # Check credit score
            if term.min_credit_score and credit:
                if credit.credit_score < term.min_credit_score:
                    continue
            result |= term

        return result
```

#### Dev 3 (Frontend Lead) - 8 hours

```javascript
/** @odoo-module */
// B2B Credit Dashboard Component
// odoo/addons/smart_dairy_b2b/static/src/js/b2b_credit_dashboard.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class B2BCreditDashboard extends Component {
    static template = "smart_dairy_b2b.B2BCreditDashboard";

    setup() {
        this.orm = useService("orm");

        this.state = useState({
            credit: null,
            transactions: [],
            loading: true,
        });

        onWillStart(async () => {
            await this.loadCreditData();
        });
    }

    async loadCreditData() {
        try {
            const creditData = await this.orm.call(
                "b2b.credit",
                "get_customer_credit_summary",
                [this.props.customerId]
            );
            this.state.credit = creditData;

            const transactions = await this.orm.searchRead(
                "account.move",
                [
                    ["partner_id", "=", creditData.partner_id],
                    ["move_type", "=", "out_invoice"],
                    ["state", "=", "posted"],
                ],
                ["name", "invoice_date", "invoice_date_due", "amount_total", "amount_residual", "payment_state"],
                { limit: 20, order: "invoice_date desc" }
            );
            this.state.transactions = transactions;
        } finally {
            this.state.loading = false;
        }
    }

    get utilizationClass() {
        const pct = this.state.credit?.utilization_percentage || 0;
        if (pct >= 90) return "danger";
        if (pct >= 75) return "warning";
        return "success";
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat("en-BD", {
            style: "currency",
            currency: "BDT",
        }).format(amount);
    }
}
```

---

### Days 582-590: Summary

**Day 582**: Credit utilization compute, credit history logging
**Day 583**: Aging report engine, bucket calculations
**Day 584**: Credit hold logic, hold notifications
**Day 585**: Collection workflow, collection actions
**Day 586**: Credit release rules, API endpoints
**Day 587**: Credit score model, integration tests
**Day 588**: Reporting queries, unit tests
**Day 589**: Audit trail, E2E tests
**Day 590**: Documentation, UAT preparation

---

## 4. Technical Specifications

### 4.1 Credit Rules

```python
CREDIT_HOLD_RULES = {
    'overdue_days_threshold': 45,
    'utilization_threshold': 95,
    'min_order_with_hold': 0,
}

PAYMENT_TERMS = {
    'bronze': 'Net 30',
    'silver': 'Net 45',
    'gold': 'Net 60',
    'platinum': 'Net 90',
}
```

### 4.2 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/b2b/customer/{id}/credit` | GET | Get credit info |
| `/api/v1/b2b/customer/{id}/aging` | GET | Get aging report |
| `/api/v1/b2b/customer/{id}/credit/request` | POST | Request limit increase |

---

## 5. Sign-off Checklist

- [ ] Credit limit model complete
- [ ] Real-time utilization tracking
- [ ] Aging buckets calculated correctly
- [ ] Automated credit holds working
- [ ] Collection workflow operational
- [ ] Credit dashboard functional
- [ ] API endpoints tested
- [ ] Unit tests passing (>80%)

---

**Document End**

*Milestone 84: Credit Management*
*Days 581-590 | Phase 9: Commerce - B2B Portal Foundation*
