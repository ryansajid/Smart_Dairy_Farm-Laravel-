# Milestone 83: Request for Quotation (RFQ)

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field                | Details                                                      |
| -------------------- | ------------------------------------------------------------ |
| **Document ID**      | SD-IMPL-P9-M83-v1.0                                          |
| **Milestone**        | 83 - Request for Quotation                                   |
| **Phase**            | Phase 9: Commerce - B2B Portal Foundation                    |
| **Days**             | 571-580                                                      |
| **Duration**         | 10 Working Days                                              |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 83 implements the Request for Quotation (RFQ) system enabling B2B customers to request custom pricing, compare quotes from multiple suppliers, and convert accepted quotes to orders.

### 1.2 Scope

- RFQ Model with request management
- RFQ Line Model with product specifications
- Quote Model for supplier responses
- Multi-Supplier Bidding System
- Quote Comparison Engine
- Quote-to-Order Conversion
- RFQ Templates for recurring requests
- PDF Quote Generation
- OWL RFQ Management UI

---

## 2. Key Deliverables

| #  | Deliverable                          | Owner  | Priority |
| -- | ------------------------------------ | ------ | -------- |
| 1  | RFQ Model (b2b.rfq)                  | Dev 1  | Critical |
| 2  | RFQ Line Model                       | Dev 1  | Critical |
| 3  | Quote Model (b2b.quote)              | Dev 2  | Critical |
| 4  | Quote Comparison Engine              | Dev 1  | High     |
| 5  | Quote-to-Order Conversion            | Dev 1  | High     |
| 6  | RFQ Templates                        | Dev 2  | Medium   |
| 7  | PDF Quote Generation                 | Dev 2  | High     |
| 8  | OWL RFQ Creation Form                | Dev 3  | Critical |
| 9  | Quote Comparison UI                  | Dev 3  | High     |
| 10 | RFQ API Endpoints                    | Dev 2  | High     |

---

## 3. Day-by-Day Development Plan

### Day 571 (Day 1): RFQ Model Foundation

#### Dev 1 (Backend Lead) - 8 hours

```python
# odoo/addons/smart_dairy_b2b/models/b2b_rfq.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
from datetime import timedelta

class B2BRFQ(models.Model):
    _name = 'b2b.rfq'
    _description = 'B2B Request for Quotation'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'create_date desc'

    name = fields.Char(string='RFQ Number', readonly=True, copy=False)
    customer_id = fields.Many2one('b2b.customer', string='Customer', required=True)
    partner_id = fields.Many2one(related='customer_id.partner_id', store=True)

    # Dates
    request_date = fields.Datetime(string='Request Date', default=fields.Datetime.now)
    deadline = fields.Datetime(string='Response Deadline', required=True)
    validity_date = fields.Date(string='Quote Validity Date')

    # Details
    subject = fields.Char(string='Subject', required=True)
    description = fields.Text(string='Description')
    delivery_address_id = fields.Many2one('b2b.delivery.address', string='Delivery Address')
    requested_delivery_date = fields.Date(string='Requested Delivery Date')

    # Lines
    line_ids = fields.One2many('b2b.rfq.line', 'rfq_id', string='Products')

    # Quotes
    quote_ids = fields.One2many('b2b.quote', 'rfq_id', string='Quotes')
    quote_count = fields.Integer(string='Quote Count', compute='_compute_quote_count')
    selected_quote_id = fields.Many2one('b2b.quote', string='Selected Quote')

    # State
    state = fields.Selection([
        ('draft', 'Draft'),
        ('submitted', 'Submitted'),
        ('in_progress', 'Quotes Received'),
        ('quoted', 'Quoted'),
        ('accepted', 'Quote Accepted'),
        ('ordered', 'Order Created'),
        ('cancelled', 'Cancelled'),
        ('expired', 'Expired'),
    ], string='Status', default='draft', tracking=True)

    # Totals
    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.company.currency_id)
    estimated_total = fields.Monetary(string='Estimated Total', compute='_compute_totals')

    # Reference
    sale_order_id = fields.Many2one('sale.order', string='Sales Order')

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            vals['name'] = self.env['ir.sequence'].next_by_code('b2b.rfq.sequence')
        return super().create(vals_list)

    @api.depends('quote_ids')
    def _compute_quote_count(self):
        for record in self:
            record.quote_count = len(record.quote_ids)

    @api.depends('line_ids.subtotal')
    def _compute_totals(self):
        for record in self:
            record.estimated_total = sum(record.line_ids.mapped('subtotal'))

    def action_submit(self):
        """Submit RFQ for quotes"""
        self.ensure_one()
        if not self.line_ids:
            raise ValidationError('Please add at least one product line.')
        self.write({'state': 'submitted'})
        self._notify_suppliers()

    def action_select_quote(self, quote_id):
        """Select a quote"""
        self.ensure_one()
        quote = self.env['b2b.quote'].browse(quote_id)
        if quote.rfq_id != self:
            raise ValidationError('Quote does not belong to this RFQ.')
        self.write({
            'selected_quote_id': quote_id,
            'state': 'accepted',
        })
        quote.write({'state': 'accepted'})
        # Reject other quotes
        self.quote_ids.filtered(lambda q: q.id != quote_id).write({'state': 'rejected'})

    def action_create_order(self):
        """Create sales order from selected quote"""
        self.ensure_one()
        if not self.selected_quote_id:
            raise ValidationError('Please select a quote first.')

        order = self.env['sale.order'].create({
            'partner_id': self.partner_id.id,
            'b2b_customer_id': self.customer_id.id,
            'origin': self.name,
            'order_line': [(0, 0, {
                'product_id': line.product_id.id,
                'product_uom_qty': line.quantity,
                'price_unit': line.unit_price,
            }) for line in self.selected_quote_id.line_ids],
        })

        self.write({
            'sale_order_id': order.id,
            'state': 'ordered',
        })
        return {
            'type': 'ir.actions.act_window',
            'res_model': 'sale.order',
            'res_id': order.id,
            'view_mode': 'form',
        }

    def _notify_suppliers(self):
        """Notify suppliers about new RFQ"""
        template = self.env.ref('smart_dairy_b2b.email_template_rfq_new', raise_if_not_found=False)
        if template:
            template.send_mail(self.id, force_send=True)


class B2BRFQLine(models.Model):
    _name = 'b2b.rfq.line'
    _description = 'B2B RFQ Line'

    rfq_id = fields.Many2one('b2b.rfq', string='RFQ', required=True, ondelete='cascade')
    product_id = fields.Many2one('product.product', string='Product', required=True)
    description = fields.Text(string='Description')
    quantity = fields.Float(string='Quantity', required=True, default=1.0)
    uom_id = fields.Many2one('uom.uom', string='Unit of Measure')

    # Pricing (estimated)
    estimated_price = fields.Monetary(string='Estimated Unit Price')
    currency_id = fields.Many2one(related='rfq_id.currency_id')
    subtotal = fields.Monetary(string='Subtotal', compute='_compute_subtotal')

    # Specifications
    specifications = fields.Text(string='Specifications')

    @api.depends('quantity', 'estimated_price')
    def _compute_subtotal(self):
        for line in self:
            line.subtotal = line.quantity * (line.estimated_price or 0)
```

#### Dev 2 (Full-Stack) - 8 hours

```python
# odoo/addons/smart_dairy_b2b/models/b2b_quote.py
from odoo import models, fields, api

class B2BQuote(models.Model):
    _name = 'b2b.quote'
    _description = 'B2B Quote'
    _inherit = ['mail.thread']
    _order = 'total_amount'

    name = fields.Char(string='Quote Number', readonly=True, copy=False)
    rfq_id = fields.Many2one('b2b.rfq', string='RFQ', required=True, ondelete='cascade')
    supplier_id = fields.Many2one('res.partner', string='Supplier')

    # Dates
    quote_date = fields.Datetime(string='Quote Date', default=fields.Datetime.now)
    validity_date = fields.Date(string='Valid Until', required=True)
    delivery_date = fields.Date(string='Estimated Delivery')

    # Lines
    line_ids = fields.One2many('b2b.quote.line', 'quote_id', string='Quote Lines')

    # Totals
    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.company.currency_id)
    subtotal = fields.Monetary(string='Subtotal', compute='_compute_totals', store=True)
    tax_amount = fields.Monetary(string='Tax Amount', compute='_compute_totals', store=True)
    total_amount = fields.Monetary(string='Total', compute='_compute_totals', store=True)

    # Terms
    payment_term_id = fields.Many2one('account.payment.term', string='Payment Terms')
    delivery_terms = fields.Text(string='Delivery Terms')
    notes = fields.Text(string='Notes')

    # State
    state = fields.Selection([
        ('draft', 'Draft'),
        ('submitted', 'Submitted'),
        ('accepted', 'Accepted'),
        ('rejected', 'Rejected'),
        ('expired', 'Expired'),
    ], string='Status', default='draft', tracking=True)

    # Comparison metrics
    price_rank = fields.Integer(string='Price Rank', compute='_compute_ranking')
    delivery_rank = fields.Integer(string='Delivery Rank', compute='_compute_ranking')

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            vals['name'] = self.env['ir.sequence'].next_by_code('b2b.quote.sequence')
        return super().create(vals_list)

    @api.depends('line_ids.subtotal', 'line_ids.tax_amount')
    def _compute_totals(self):
        for quote in self:
            quote.subtotal = sum(quote.line_ids.mapped('subtotal'))
            quote.tax_amount = sum(quote.line_ids.mapped('tax_amount'))
            quote.total_amount = quote.subtotal + quote.tax_amount

    @api.depends('rfq_id.quote_ids.total_amount', 'rfq_id.quote_ids.delivery_date')
    def _compute_ranking(self):
        for quote in self:
            siblings = quote.rfq_id.quote_ids.sorted('total_amount')
            quote.price_rank = list(siblings).index(quote) + 1 if quote in siblings else 0

            siblings_delivery = quote.rfq_id.quote_ids.sorted('delivery_date')
            quote.delivery_rank = list(siblings_delivery).index(quote) + 1 if quote in siblings_delivery else 0


class B2BQuoteLine(models.Model):
    _name = 'b2b.quote.line'
    _description = 'B2B Quote Line'

    quote_id = fields.Many2one('b2b.quote', string='Quote', required=True, ondelete='cascade')
    rfq_line_id = fields.Many2one('b2b.rfq.line', string='RFQ Line')
    product_id = fields.Many2one('product.product', string='Product', required=True)

    quantity = fields.Float(string='Quantity', required=True)
    unit_price = fields.Monetary(string='Unit Price', required=True)
    discount = fields.Float(string='Discount %')
    tax_ids = fields.Many2many('account.tax', string='Taxes')

    currency_id = fields.Many2one(related='quote_id.currency_id')
    subtotal = fields.Monetary(string='Subtotal', compute='_compute_amounts', store=True)
    tax_amount = fields.Monetary(string='Tax Amount', compute='_compute_amounts', store=True)
    total = fields.Monetary(string='Total', compute='_compute_amounts', store=True)

    @api.depends('quantity', 'unit_price', 'discount', 'tax_ids')
    def _compute_amounts(self):
        for line in self:
            price = line.unit_price * (1 - line.discount / 100)
            line.subtotal = line.quantity * price
            taxes = line.tax_ids.compute_all(price, quantity=line.quantity)
            line.tax_amount = taxes['total_included'] - taxes['total_excluded']
            line.total = line.subtotal + line.tax_amount
```

#### Dev 3 (Frontend Lead) - 8 hours

```javascript
/** @odoo-module */
// odoo/addons/smart_dairy_b2b/static/src/js/b2b_rfq_form.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class B2BRFQForm extends Component {
    static template = "smart_dairy_b2b.B2BRFQForm";

    setup() {
        this.orm = useService("orm");
        this.notification = useService("notification");

        this.state = useState({
            rfq: {
                subject: "",
                description: "",
                deadline: "",
                requestedDeliveryDate: "",
                deliveryAddressId: null,
                lines: [],
            },
            addresses: [],
            products: [],
            loading: false,
            productSearch: "",
        });

        onWillStart(async () => {
            await this.loadAddresses();
        });
    }

    async loadAddresses() {
        const customerId = this.props.customerId;
        if (customerId) {
            this.state.addresses = await this.orm.searchRead(
                "b2b.delivery.address",
                [["customer_id", "=", customerId]],
                ["id", "name", "city", "is_default"]
            );
        }
    }

    async searchProducts(query) {
        if (query.length < 2) return;

        const products = await this.orm.searchRead(
            "product.product",
            [
                ["sale_ok", "=", true],
                "|",
                ["name", "ilike", query],
                ["default_code", "ilike", query],
            ],
            ["id", "name", "default_code", "lst_price"],
            { limit: 10 }
        );
        this.state.products = products;
    }

    addProduct(product) {
        const existing = this.state.rfq.lines.find(l => l.productId === product.id);
        if (existing) {
            existing.quantity += 1;
        } else {
            this.state.rfq.lines.push({
                productId: product.id,
                productName: product.name,
                sku: product.default_code,
                quantity: 1,
                estimatedPrice: product.lst_price,
                specifications: "",
            });
        }
        this.state.productSearch = "";
        this.state.products = [];
    }

    removeLine(index) {
        this.state.rfq.lines.splice(index, 1);
    }

    updateLineQuantity(index, quantity) {
        this.state.rfq.lines[index].quantity = parseFloat(quantity) || 1;
    }

    get estimatedTotal() {
        return this.state.rfq.lines.reduce(
            (sum, line) => sum + line.quantity * line.estimatedPrice,
            0
        );
    }

    async submitRFQ() {
        if (!this.state.rfq.subject) {
            this.notification.add("Subject is required", { type: "warning" });
            return;
        }
        if (!this.state.rfq.deadline) {
            this.notification.add("Deadline is required", { type: "warning" });
            return;
        }
        if (this.state.rfq.lines.length === 0) {
            this.notification.add("Please add at least one product", { type: "warning" });
            return;
        }

        this.state.loading = true;
        try {
            const result = await this.orm.call("b2b.rfq", "create_from_portal", [
                {
                    customer_id: this.props.customerId,
                    subject: this.state.rfq.subject,
                    description: this.state.rfq.description,
                    deadline: this.state.rfq.deadline,
                    requested_delivery_date: this.state.rfq.requestedDeliveryDate,
                    delivery_address_id: this.state.rfq.deliveryAddressId,
                    lines: this.state.rfq.lines.map(l => ({
                        product_id: l.productId,
                        quantity: l.quantity,
                        estimated_price: l.estimatedPrice,
                        specifications: l.specifications,
                    })),
                },
            ]);

            this.notification.add("RFQ submitted successfully!", { type: "success" });
            window.location.href = `/b2b/rfq/${result.rfq_id}`;
        } catch (error) {
            this.notification.add(error.message || "Failed to submit RFQ", { type: "danger" });
        } finally {
            this.state.loading = false;
        }
    }
}
```

---

### Days 572-580: Summary

**Day 572**: RFQ workflow states, supplier notification system
**Day 573**: Multi-supplier bidding interface, response tracking
**Day 574**: Quote validation, email templates
**Day 575**: Quote comparison logic, API endpoints
**Day 576**: Quote-to-order conversion, PDF generation
**Day 577**: RFQ templates, history tracking
**Day 578**: Expiry management, unit tests
**Day 579**: Integration tests, performance optimization
**Day 580**: Documentation, UAT preparation

---

## 4. Technical Specifications

### 4.1 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/b2b/rfq` | POST | Create RFQ |
| `/api/v1/b2b/rfq/{id}` | GET | Get RFQ details |
| `/api/v1/b2b/rfq/{id}/submit` | POST | Submit RFQ |
| `/api/v1/b2b/rfq/{id}/quotes` | GET | List quotes |
| `/api/v1/b2b/quote/{id}/accept` | POST | Accept quote |
| `/api/v1/b2b/rfq/{id}/compare` | GET | Compare quotes |

---

## 5. Sign-off Checklist

- [ ] RFQ Model with workflow states
- [ ] Quote Model with comparison
- [ ] Quote-to-Order conversion
- [ ] PDF quote generation
- [ ] OWL RFQ form complete
- [ ] Quote comparison UI
- [ ] API endpoints tested
- [ ] Unit tests passing (>80%)

---

**Document End**

*Milestone 83: Request for Quotation*
*Days 571-580 | Phase 9: Commerce - B2B Portal Foundation*
