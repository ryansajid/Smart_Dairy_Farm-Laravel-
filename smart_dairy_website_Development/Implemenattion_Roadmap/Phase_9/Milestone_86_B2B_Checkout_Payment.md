# Milestone 86: B2B Checkout & Payment

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field                | Details                                                      |
| -------------------- | ------------------------------------------------------------ |
| **Document ID**      | SD-IMPL-P9-M86-v1.0                                          |
| **Milestone**        | 86 - B2B Checkout & Payment                                  |
| **Phase**            | Phase 9: Commerce - B2B Portal Foundation                    |
| **Days**             | 601-610                                                      |
| **Duration**         | 10 Working Days                                              |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 86 implements B2B checkout with credit-based payments, purchase order integration, multi-payment methods (Credit, Wire Transfer, Check), and automated invoice generation.

### 1.2 Scope

- B2B Order Model extension
- Credit-Based Checkout with limit validation
- Purchase Order Integration
- Multi-Payment Methods
- Invoice Generation with payment terms
- Payment Reminders
- OWL Checkout Flow

---

## 2. Key Deliverables

| #  | Deliverable                          | Owner  | Priority |
| -- | ------------------------------------ | ------ | -------- |
| 1  | B2B Order Model Extension            | Dev 1  | Critical |
| 2  | Credit Checkout Logic                | Dev 1  | Critical |
| 3  | PO Integration                       | Dev 2  | High     |
| 4  | Wire Transfer Flow                   | Dev 2  | High     |
| 5  | Invoice Generation                   | Dev 1  | High     |
| 6  | Payment Reminders                    | Dev 2  | Medium   |
| 7  | OWL Checkout Flow                    | Dev 3  | Critical |
| 8  | Payment Method Selector              | Dev 3  | High     |
| 9  | Checkout API Endpoints               | Dev 2  | High     |

---

## 3. Day-by-Day Development Plan

### Day 601 (Day 1): B2B Order Model

#### Dev 1 (Backend Lead) - 8 hours

```python
# odoo/addons/smart_dairy_b2b/models/b2b_order.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError

class SaleOrder(models.Model):
    _inherit = 'sale.order'

    # B2B Fields
    b2b_customer_id = fields.Many2one('b2b.customer', string='B2B Customer')
    is_b2b_order = fields.Boolean(string='Is B2B Order', compute='_compute_is_b2b')

    # Payment
    b2b_payment_method = fields.Selection([
        ('credit', 'Credit Account'),
        ('wire', 'Wire Transfer'),
        ('check', 'Check'),
        ('prepaid', 'Prepaid'),
    ], string='B2B Payment Method')

    # Purchase Order Reference
    customer_po_number = fields.Char(string='Customer PO Number')
    customer_po_date = fields.Date(string='Customer PO Date')
    customer_po_file = fields.Binary(string='PO Document')
    customer_po_filename = fields.Char(string='PO Filename')

    # Credit Validation
    credit_validated = fields.Boolean(string='Credit Validated', default=False)
    credit_validation_date = fields.Datetime(string='Credit Validated At')
    credit_validation_amount = fields.Monetary(string='Validated Credit Amount')

    # Delivery
    b2b_delivery_address_id = fields.Many2one('b2b.delivery.address', string='B2B Delivery Address')
    requested_delivery_date = fields.Date(string='Requested Delivery')
    delivery_instructions = fields.Text(string='Delivery Instructions')

    # Approval
    b2b_approval_required = fields.Boolean(string='Approval Required', compute='_compute_approval_required')
    b2b_approved_by = fields.Many2one('res.users', string='Approved By')
    b2b_approved_date = fields.Datetime(string='Approved Date')

    @api.depends('b2b_customer_id')
    def _compute_is_b2b(self):
        for order in self:
            order.is_b2b_order = bool(order.b2b_customer_id)

    @api.depends('amount_total', 'b2b_customer_id')
    def _compute_approval_required(self):
        for order in self:
            order.b2b_approval_required = False
            if order.b2b_customer_id:
                # Require approval for orders > credit limit or large orders
                credit = self.env['b2b.credit'].search([
                    ('customer_id', '=', order.b2b_customer_id.id)
                ], limit=1)
                if credit and order.amount_total > credit.credit_available:
                    order.b2b_approval_required = True

    def action_validate_credit(self):
        """Validate credit before confirming order"""
        self.ensure_one()
        if not self.b2b_customer_id:
            return True

        credit = self.env['b2b.credit'].search([
            ('customer_id', '=', self.b2b_customer_id.id)
        ], limit=1)

        if not credit:
            raise ValidationError('No credit record found for customer')

        if credit.is_on_hold:
            raise ValidationError('Customer is on credit hold')

        if self.b2b_payment_method == 'credit':
            available, msg = credit.check_credit_available(self.amount_total)
            if not available:
                raise ValidationError(msg)

        self.write({
            'credit_validated': True,
            'credit_validation_date': fields.Datetime.now(),
            'credit_validation_amount': self.amount_total,
        })
        return True

    def action_confirm(self):
        """Override to add B2B validations"""
        for order in self:
            if order.is_b2b_order:
                if not order.credit_validated:
                    order.action_validate_credit()
                if order.b2b_approval_required and not order.b2b_approved_by:
                    raise ValidationError('This order requires approval before confirmation')
        return super().action_confirm()

    def action_b2b_approve(self):
        """Approve B2B order"""
        self.write({
            'b2b_approved_by': self.env.user.id,
            'b2b_approved_date': fields.Datetime.now(),
        })

    def _create_invoices(self, grouped=False, final=False, date=None):
        """Override to apply B2B payment terms"""
        invoices = super()._create_invoices(grouped=grouped, final=final, date=date)
        for invoice in invoices:
            if invoice.partner_id:
                b2b_customer = self.env['b2b.customer'].search([
                    ('partner_id', '=', invoice.partner_id.id)
                ], limit=1)
                if b2b_customer:
                    credit = self.env['b2b.credit'].search([
                        ('customer_id', '=', b2b_customer.id)
                    ], limit=1)
                    if credit and credit.payment_term_id:
                        invoice.invoice_payment_term_id = credit.payment_term_id.id
        return invoices
```

#### Dev 2 (Full-Stack) - 8 hours

```python
# B2B Payment Service
# odoo/addons/smart_dairy_b2b/services/b2b_payment_service.py
from odoo import models, api, fields

class B2BPaymentService(models.AbstractModel):
    _name = 'b2b.payment.service'
    _description = 'B2B Payment Service'

    @api.model
    def get_available_payment_methods(self, customer_id):
        """Get available payment methods for customer"""
        customer = self.env['b2b.customer'].browse(customer_id)
        credit = self.env['b2b.credit'].search([('customer_id', '=', customer_id)], limit=1)

        methods = []

        # Credit account (if available credit)
        if credit and credit.credit_available > 0 and not credit.is_on_hold:
            methods.append({
                'code': 'credit',
                'name': 'Credit Account',
                'available_amount': credit.credit_available,
                'payment_term': credit.payment_term_id.name if credit.payment_term_id else 'Due on Receipt',
            })

        # Wire Transfer (always available)
        methods.append({
            'code': 'wire',
            'name': 'Wire Transfer',
            'bank_details': self._get_bank_details(),
        })

        # Check (for approved customers)
        if customer.state == 'approved':
            methods.append({
                'code': 'check',
                'name': 'Check',
                'instructions': 'Payable to Smart Dairy Ltd.',
            })

        # Prepaid (always available)
        methods.append({
            'code': 'prepaid',
            'name': 'Prepaid',
        })

        return methods

    @api.model
    def _get_bank_details(self):
        return {
            'bank_name': 'Dutch-Bangla Bank Ltd.',
            'account_name': 'Smart Dairy Ltd.',
            'account_number': '123456789012',
            'branch': 'Dhaka Main Branch',
            'routing_number': '090261234',
        }

    @api.model
    def process_checkout(self, order_id, payment_method, po_data=None):
        """Process B2B checkout"""
        order = self.env['sale.order'].browse(order_id)

        if not order.exists():
            return {'success': False, 'error': 'Order not found'}

        # Update order with payment method
        update_vals = {'b2b_payment_method': payment_method}

        # Handle PO data
        if po_data:
            update_vals.update({
                'customer_po_number': po_data.get('po_number'),
                'customer_po_date': po_data.get('po_date'),
            })
            if po_data.get('po_file'):
                update_vals['customer_po_file'] = po_data['po_file']
                update_vals['customer_po_filename'] = po_data.get('po_filename', 'PO.pdf')

        order.write(update_vals)

        # Validate and confirm
        try:
            order.action_validate_credit()
            order.action_confirm()

            # Create invoice for credit orders
            if payment_method == 'credit':
                order._create_invoices(final=True)

            return {
                'success': True,
                'order_id': order.id,
                'order_name': order.name,
                'state': order.state,
            }
        except Exception as e:
            return {'success': False, 'error': str(e)}
```

#### Dev 3 (Frontend Lead) - 8 hours

```javascript
/** @odoo-module */
// B2B Checkout Component
// odoo/addons/smart_dairy_b2b/static/src/js/b2b_checkout.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class B2BCheckout extends Component {
    static template = "smart_dairy_b2b.B2BCheckout";

    setup() {
        this.orm = useService("orm");
        this.notification = useService("notification");

        this.state = useState({
            step: 1,
            order: null,
            paymentMethods: [],
            selectedMethod: null,
            addresses: [],
            selectedAddress: null,
            poNumber: "",
            poDate: "",
            poFile: null,
            deliveryDate: "",
            instructions: "",
            loading: false,
        });

        onWillStart(async () => {
            await this.loadCheckoutData();
        });
    }

    async loadCheckoutData() {
        // Load order
        this.state.order = await this.orm.read(
            "sale.order",
            [this.props.orderId],
            ["name", "amount_total", "order_line", "partner_id"]
        );

        // Load payment methods
        this.state.paymentMethods = await this.orm.call(
            "b2b.payment.service",
            "get_available_payment_methods",
            [this.props.customerId]
        );

        // Load addresses
        this.state.addresses = await this.orm.searchRead(
            "b2b.delivery.address",
            [["customer_id", "=", this.props.customerId]],
            ["id", "name", "street", "city", "contact_phone", "is_default"]
        );

        // Set defaults
        const defaultAddr = this.state.addresses.find(a => a.is_default);
        if (defaultAddr) {
            this.state.selectedAddress = defaultAddr.id;
        }
    }

    selectPaymentMethod(method) {
        this.state.selectedMethod = method;
    }

    nextStep() {
        if (this.state.step === 1 && !this.state.selectedAddress) {
            this.notification.add("Please select delivery address", { type: "warning" });
            return;
        }
        if (this.state.step === 2 && !this.state.selectedMethod) {
            this.notification.add("Please select payment method", { type: "warning" });
            return;
        }
        this.state.step++;
    }

    prevStep() {
        this.state.step--;
    }

    async handlePoFileUpload(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.state.poFile = e.target.result.split(",")[1];
            };
            reader.readAsDataURL(file);
        }
    }

    async submitCheckout() {
        this.state.loading = true;
        try {
            // Update delivery info
            await this.orm.write("sale.order", [this.props.orderId], {
                b2b_delivery_address_id: this.state.selectedAddress,
                requested_delivery_date: this.state.deliveryDate || false,
                delivery_instructions: this.state.instructions,
            });

            // Process checkout
            const result = await this.orm.call(
                "b2b.payment.service",
                "process_checkout",
                [
                    this.props.orderId,
                    this.state.selectedMethod,
                    {
                        po_number: this.state.poNumber,
                        po_date: this.state.poDate,
                        po_file: this.state.poFile,
                    },
                ]
            );

            if (result.success) {
                this.notification.add("Order confirmed successfully!", { type: "success" });
                window.location.href = `/b2b/order/${result.order_id}/confirmation`;
            } else {
                this.notification.add(result.error, { type: "danger" });
            }
        } finally {
            this.state.loading = false;
        }
    }
}
```

---

### Days 602-610: Summary

**Day 602**: Credit checkout logic, credit validation service
**Day 603**: PO integration, PO reference tracking
**Day 604**: Wire transfer flow, bank details config
**Day 605**: Check payment flow, payment recording
**Day 606**: Invoice generation, payment terms application
**Day 607**: Payment reminders, reminder cron job
**Day 608**: Statement of accounts, API endpoints
**Day 609**: Unit tests, integration tests
**Day 610**: Documentation, UAT preparation

---

## 4. Technical Specifications

### 4.1 Payment Methods

| Method | Requirements | Processing |
|--------|--------------|------------|
| Credit | Available credit, not on hold | Instant invoice |
| Wire | Bank transfer proof | Manual verification |
| Check | Approved customer | Manual clearing |
| Prepaid | Payment received | Confirmation on payment |

### 4.2 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/b2b/order/{id}/checkout` | POST | Process checkout |
| `/api/v1/b2b/payment-methods` | GET | Available methods |
| `/api/v1/b2b/order/{id}/invoice` | GET | Get invoice |

---

## 5. Sign-off Checklist

- [ ] B2B Order Model extended
- [ ] Credit validation working
- [ ] PO integration complete
- [ ] Multi-payment methods supported
- [ ] Invoice generation automated
- [ ] OWL Checkout Flow complete
- [ ] Payment reminders operational
- [ ] API endpoints tested

---

**Document End**

*Milestone 86: B2B Checkout & Payment*
*Days 601-610 | Phase 9: Commerce - B2B Portal Foundation*
