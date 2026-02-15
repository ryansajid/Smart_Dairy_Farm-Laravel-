# Milestone 87: Contract Management

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field                | Details                                                      |
| -------------------- | ------------------------------------------------------------ |
| **Document ID**      | SD-IMPL-P9-M87-v1.0                                          |
| **Milestone**        | 87 - Contract Management                                     |
| **Phase**            | Phase 9: Commerce - B2B Portal Foundation                    |
| **Days**             | 611-620                                                      |
| **Duration**         | 10 Working Days                                              |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 87 implements contract management for B2B customers including price agreements, volume commitments, contract renewals, and performance tracking.

### 1.2 Scope

- Contract Model with terms and conditions
- Contract Line Model with product commitments
- Volume Commitment Tracking
- Contract Renewal Workflow
- Contract-Based Pricing Override
- Document Repository
- Contract Templates
- OWL Contract Management UI

---

## 2. Key Deliverables

| #  | Deliverable                          | Owner  | Priority |
| -- | ------------------------------------ | ------ | -------- |
| 1  | Contract Model (b2b.contract)        | Dev 1  | Critical |
| 2  | Contract Line Model                  | Dev 1  | Critical |
| 3  | Volume Commitment Tracking           | Dev 1  | High     |
| 4  | Contract Renewal Workflow            | Dev 2  | High     |
| 5  | Pricing Override Logic               | Dev 1  | High     |
| 6  | Document Repository                  | Dev 2  | Medium   |
| 7  | Contract Templates                   | Dev 2  | Medium   |
| 8  | OWL Contract Form                    | Dev 3  | High     |
| 9  | Renewal Wizard                       | Dev 3  | High     |
| 10 | Contract API Endpoints               | Dev 2  | High     |

---

## 3. Day-by-Day Development Plan

### Day 611 (Day 1): Contract Model

#### Dev 1 (Backend Lead) - 8 hours

```python
# odoo/addons/smart_dairy_b2b/models/b2b_contract.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
from dateutil.relativedelta import relativedelta

class B2BContract(models.Model):
    _name = 'b2b.contract'
    _description = 'B2B Contract'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'date_start desc'

    name = fields.Char(string='Contract Number', readonly=True, copy=False)
    customer_id = fields.Many2one('b2b.customer', string='Customer', required=True)
    partner_id = fields.Many2one(related='customer_id.partner_id', store=True)

    # Contract Type
    contract_type = fields.Selection([
        ('price_agreement', 'Price Agreement'),
        ('volume_commitment', 'Volume Commitment'),
        ('framework', 'Framework Agreement'),
        ('exclusive', 'Exclusive Supply'),
    ], string='Contract Type', required=True, default='price_agreement')

    # Dates
    date_start = fields.Date(string='Start Date', required=True)
    date_end = fields.Date(string='End Date', required=True)
    duration_months = fields.Integer(string='Duration (Months)', compute='_compute_duration')

    # Volume Commitment
    volume_commitment = fields.Float(string='Volume Commitment')
    volume_uom_id = fields.Many2one('uom.uom', string='Volume UOM')
    volume_achieved = fields.Float(string='Volume Achieved', compute='_compute_volume_achieved')
    volume_percentage = fields.Float(string='Achievement %', compute='_compute_volume_achieved')

    # Value
    value_commitment = fields.Monetary(string='Value Commitment')
    value_achieved = fields.Monetary(string='Value Achieved', compute='_compute_value_achieved')
    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.company.currency_id)

    # Lines
    line_ids = fields.One2many('b2b.contract.line', 'contract_id', string='Contract Lines')

    # Terms
    payment_term_id = fields.Many2one('account.payment.term', string='Payment Terms')
    discount_percentage = fields.Float(string='Contract Discount %')
    terms_conditions = fields.Html(string='Terms and Conditions')

    # Documents
    document_ids = fields.One2many('b2b.contract.document', 'contract_id', string='Documents')

    # State
    state = fields.Selection([
        ('draft', 'Draft'),
        ('pending', 'Pending Approval'),
        ('active', 'Active'),
        ('expired', 'Expired'),
        ('renewed', 'Renewed'),
        ('terminated', 'Terminated'),
    ], string='Status', default='draft', tracking=True)

    # Renewal
    parent_contract_id = fields.Many2one('b2b.contract', string='Previous Contract')
    renewal_contract_id = fields.Many2one('b2b.contract', string='Renewal Contract')
    auto_renew = fields.Boolean(string='Auto Renew', default=False)
    renewal_notice_days = fields.Integer(string='Renewal Notice (Days)', default=30)

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            vals['name'] = self.env['ir.sequence'].next_by_code('b2b.contract.sequence')
        return super().create(vals_list)

    @api.depends('date_start', 'date_end')
    def _compute_duration(self):
        for contract in self:
            if contract.date_start and contract.date_end:
                delta = relativedelta(contract.date_end, contract.date_start)
                contract.duration_months = delta.months + delta.years * 12
            else:
                contract.duration_months = 0

    @api.depends('line_ids.quantity_achieved')
    def _compute_volume_achieved(self):
        for contract in self:
            contract.volume_achieved = sum(contract.line_ids.mapped('quantity_achieved'))
            if contract.volume_commitment:
                contract.volume_percentage = (contract.volume_achieved / contract.volume_commitment) * 100
            else:
                contract.volume_percentage = 0

    @api.depends('line_ids.value_achieved')
    def _compute_value_achieved(self):
        for contract in self:
            contract.value_achieved = sum(contract.line_ids.mapped('value_achieved'))

    def action_activate(self):
        """Activate contract"""
        self.write({'state': 'active'})

    def action_renew(self):
        """Open renewal wizard"""
        return {
            'type': 'ir.actions.act_window',
            'name': 'Renew Contract',
            'res_model': 'b2b.contract.renew.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_contract_id': self.id},
        }

    def action_terminate(self):
        """Terminate contract"""
        self.write({'state': 'terminated'})

    @api.model
    def cron_check_expiry(self):
        """Check for expiring contracts"""
        today = fields.Date.today()
        notice_date = today + relativedelta(days=30)

        # Expiring soon
        expiring = self.search([
            ('state', '=', 'active'),
            ('date_end', '<=', notice_date),
            ('date_end', '>=', today),
        ])
        for contract in expiring:
            contract._send_expiry_notification()

        # Expired
        expired = self.search([
            ('state', '=', 'active'),
            ('date_end', '<', today),
        ])
        expired.write({'state': 'expired'})

    def get_contract_price(self, product_id, quantity=1.0):
        """Get contract price for product"""
        self.ensure_one()
        line = self.line_ids.filtered(lambda l: l.product_id.id == product_id)
        if line:
            return line[0].contract_price
        return None


class B2BContractLine(models.Model):
    _name = 'b2b.contract.line'
    _description = 'B2B Contract Line'

    contract_id = fields.Many2one('b2b.contract', string='Contract', required=True, ondelete='cascade')
    product_id = fields.Many2one('product.product', string='Product', required=True)

    # Commitment
    quantity_commitment = fields.Float(string='Quantity Commitment')
    quantity_achieved = fields.Float(string='Quantity Achieved', compute='_compute_achieved')
    uom_id = fields.Many2one('uom.uom', string='UOM')

    # Pricing
    contract_price = fields.Monetary(string='Contract Price', required=True)
    standard_price = fields.Monetary(string='Standard Price', related='product_id.lst_price')
    discount_percentage = fields.Float(string='Discount %', compute='_compute_discount')
    currency_id = fields.Many2one(related='contract_id.currency_id')

    # Achievement
    value_achieved = fields.Monetary(string='Value Achieved', compute='_compute_achieved')

    @api.depends('product_id', 'contract_id.customer_id')
    def _compute_achieved(self):
        for line in self:
            orders = self.env['sale.order.line'].search([
                ('order_id.partner_id', '=', line.contract_id.partner_id.id),
                ('order_id.state', 'in', ['sale', 'done']),
                ('product_id', '=', line.product_id.id),
                ('order_id.date_order', '>=', line.contract_id.date_start),
                ('order_id.date_order', '<=', line.contract_id.date_end),
            ])
            line.quantity_achieved = sum(orders.mapped('product_uom_qty'))
            line.value_achieved = sum(orders.mapped('price_subtotal'))

    @api.depends('contract_price', 'standard_price')
    def _compute_discount(self):
        for line in self:
            if line.standard_price:
                line.discount_percentage = ((line.standard_price - line.contract_price) / line.standard_price) * 100
            else:
                line.discount_percentage = 0
```

---

### Days 612-620: Summary

**Day 612**: Volume tracking, commitment progress calculation
**Day 613**: Contract pricing override, price validation
**Day 614**: Renewal workflow, renewal alerts
**Day 615**: Performance analytics, KPI calculation
**Day 616**: Document repository, file upload service
**Day 617**: Contract templates, template engine
**Day 618**: Approval workflow, notification service
**Day 619**: Unit tests, integration tests
**Day 620**: Documentation, UAT preparation

---

## 4. Technical Specifications

### 4.1 Contract Types

| Type | Description | Features |
|------|-------------|----------|
| Price Agreement | Fixed prices for products | Price override |
| Volume Commitment | Purchase volume targets | Achievement tracking |
| Framework | General terms | Flexible ordering |
| Exclusive | Exclusive supply | Priority fulfillment |

### 4.2 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/b2b/contract` | POST | Create contract |
| `/api/v1/b2b/contract/{id}` | GET | Get contract |
| `/api/v1/b2b/contract/{id}/renew` | POST | Renew contract |
| `/api/v1/b2b/contract/{id}/performance` | GET | Get performance |

---

## 5. Sign-off Checklist

- [ ] Contract Model complete
- [ ] Volume commitment tracking
- [ ] Pricing override working
- [ ] Renewal workflow operational
- [ ] Document repository functional
- [ ] OWL Contract UI complete
- [ ] Performance analytics
- [ ] API endpoints tested

---

**Document End**

*Milestone 87: Contract Management*
*Days 611-620 | Phase 9: Commerce - B2B Portal Foundation*
