# Milestone 64: Checkout Optimization

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P7-M64-v1.0 |
| **Milestone** | 64 - Checkout Optimization |
| **Phase** | Phase 7: B2C E-Commerce Core |
| **Days** | 381-390 |
| **Duration** | 10 Working Days |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Technical Architecture Team |
| **Last Updated** | 2025-01-15 |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Build a conversion-optimized multi-step checkout flow with guest checkout, address validation, delivery scheduling, promo codes, and one-click checkout for returning customers.**

### 1.2 Scope Summary

| Area | Included |
|------|----------|
| Multi-Step Checkout | ✅ 4 steps: Cart → Shipping → Payment → Confirm |
| Guest Checkout | ✅ No registration required |
| Address Validation | ✅ Google Places integration |
| Delivery Slots | ✅ Time slot selection |
| Promo Codes | ✅ Coupon validation & application |
| One-Click Checkout | ✅ Saved payment methods |
| Order Summary | ✅ Editable summary |
| Progress Indicator | ✅ Visual step tracker |

---

## 2. Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| O1 | Multi-step checkout wizard | Completion rate > 80% |
| O2 | Guest checkout flow | < 3 min checkout time |
| O3 | Address validation | Validation accuracy > 98% |
| O4 | Delivery slot selection | Slot availability 100% accurate |
| O5 | Promo code validation | Valid code acceptance 100% |
| O6 | One-click checkout | < 30 sec for returning customers |
| O7 | Mobile-optimized checkout | Mobile conversion > 4% |
| O8 | Payment integration | Success rate > 98% |

---

## 3. Key Deliverables

| # | Deliverable | Owner | Format | Due Day |
|---|-------------|-------|--------|---------|
| D1 | Checkout service orchestration | Dev 1 | Python | Day 383 |
| D2 | Promo code validation system | Dev 1 | Python | Day 384 |
| D3 | Delivery slot management | Dev 2 | Python | Day 383 |
| D4 | Payment gateway integration | Dev 2 | Python | Day 386 |
| D5 | Checkout wizard OWL component | Dev 3 | JavaScript | Day 385 |
| D6 | Address validation UI | Dev 3 | OWL/QWeb | Day 384 |
| D7 | Payment selection UI | Dev 3 | OWL | Day 387 |
| D8 | Order confirmation page | Dev 3 | QWeb | Day 388 |
| D9 | One-click checkout | Dev 1/Dev 3 | Python/JS | Day 389 |
| D10 | Integration testing | All | Tests | Day 390 |

---

## 4. Day-by-Day Development Plan

### Day 381-382: Checkout Architecture & Service Design

#### Dev 1 (Backend Lead)

**Checkout Service Model**

```python
# File: smart_dairy_ecommerce/models/checkout_service.py

from odoo import models, fields, api
from odoo.exceptions import UserError
import uuid

class CheckoutSession(models.Model):
    _name = 'checkout.session'
    _description = 'Checkout Session'

    session_id = fields.Char(
        string='Session ID',
        default=lambda self: str(uuid.uuid4()),
        required=True,
        index=True
    )
    cart_session_id = fields.Many2one(
        'cart.session',
        string='Cart Session'
    )
    partner_id = fields.Many2one(
        'res.partner',
        string='Customer'
    )
    sale_order_id = fields.Many2one(
        'sale.order',
        string='Sale Order'
    )

    # Checkout Steps
    current_step = fields.Selection([
        ('cart', 'Cart Review'),
        ('shipping', 'Shipping'),
        ('payment', 'Payment'),
        ('confirm', 'Confirmation'),
    ], string='Current Step', default='cart')

    # Shipping Information
    shipping_address_id = fields.Many2one(
        'customer.address',
        string='Shipping Address'
    )
    shipping_address_data = fields.Text(
        string='Shipping Address JSON'
    )
    delivery_slot_id = fields.Many2one(
        'delivery.slot',
        string='Delivery Slot'
    )
    delivery_instructions = fields.Text(
        string='Delivery Instructions'
    )

    # Billing
    billing_same_as_shipping = fields.Boolean(
        string='Billing Same as Shipping',
        default=True
    )
    billing_address_id = fields.Many2one(
        'customer.address',
        string='Billing Address'
    )

    # Payment
    payment_method = fields.Selection([
        ('cod', 'Cash on Delivery'),
        ('bkash', 'bKash'),
        ('nagad', 'Nagad'),
        ('rocket', 'Rocket'),
        ('card', 'Credit/Debit Card'),
        ('sslcommerz', 'SSLCommerz'),
    ], string='Payment Method')
    saved_payment_id = fields.Many2one(
        'customer.saved.payment',
        string='Saved Payment'
    )

    # Promo & Loyalty
    promo_code = fields.Char(string='Promo Code')
    promo_discount = fields.Monetary(
        string='Promo Discount',
        currency_field='currency_id'
    )
    loyalty_points_used = fields.Integer(
        string='Loyalty Points Used',
        default=0
    )
    loyalty_discount = fields.Monetary(
        string='Loyalty Discount',
        currency_field='currency_id'
    )

    # Totals
    subtotal = fields.Monetary(
        string='Subtotal',
        currency_field='currency_id'
    )
    shipping_cost = fields.Monetary(
        string='Shipping Cost',
        currency_field='currency_id'
    )
    tax_amount = fields.Monetary(
        string='Tax',
        currency_field='currency_id'
    )
    total_discount = fields.Monetary(
        string='Total Discount',
        compute='_compute_total_discount',
        currency_field='currency_id'
    )
    grand_total = fields.Monetary(
        string='Grand Total',
        compute='_compute_grand_total',
        currency_field='currency_id'
    )

    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.company.currency_id
    )

    # Status
    is_guest = fields.Boolean(string='Guest Checkout', default=False)
    guest_email = fields.Char(string='Guest Email')
    guest_phone = fields.Char(string='Guest Phone')

    completed = fields.Boolean(string='Completed', default=False)
    completed_at = fields.Datetime(string='Completed At')

    expires_at = fields.Datetime(
        string='Expires At',
        default=lambda self: fields.Datetime.add(fields.Datetime.now(), hours=2)
    )

    @api.depends('promo_discount', 'loyalty_discount')
    def _compute_total_discount(self):
        for session in self:
            session.total_discount = session.promo_discount + session.loyalty_discount

    @api.depends('subtotal', 'shipping_cost', 'tax_amount', 'total_discount')
    def _compute_grand_total(self):
        for session in self:
            session.grand_total = (
                session.subtotal +
                session.shipping_cost +
                session.tax_amount -
                session.total_discount
            )

    def validate_step(self, step):
        """Validate checkout step data"""
        self.ensure_one()

        if step == 'shipping':
            if not self.shipping_address_id and not self.shipping_address_data:
                return {'error': 'Shipping address is required'}
            if not self.delivery_slot_id:
                return {'error': 'Please select a delivery slot'}

        elif step == 'payment':
            if not self.payment_method:
                return {'error': 'Please select a payment method'}

        return {'success': True}

    def complete_checkout(self):
        """Complete the checkout process"""
        self.ensure_one()

        # Create or update sale order
        if not self.sale_order_id:
            order = self._create_sale_order()
        else:
            order = self.sale_order_id

        # Apply promo code discount
        if self.promo_discount > 0:
            self._apply_promo_discount(order)

        # Apply loyalty discount
        if self.loyalty_discount > 0:
            self._apply_loyalty_discount(order)

        # Confirm order
        order.action_confirm()

        # Mark session complete
        self.write({
            'completed': True,
            'completed_at': fields.Datetime.now(),
            'sale_order_id': order.id,
        })

        return {
            'success': True,
            'order_id': order.id,
            'order_name': order.name,
        }

    def _create_sale_order(self):
        """Create sale order from checkout session"""
        # Implementation details...
        pass

    def _apply_promo_discount(self, order):
        """Apply promo code discount to order"""
        # Implementation details...
        pass

    def _apply_loyalty_discount(self, order):
        """Apply loyalty points discount to order"""
        # Implementation details...
        pass
```

#### Dev 2 (Full-Stack)

**Delivery Slot Management**

```python
# File: smart_dairy_ecommerce/models/delivery_slot.py

from odoo import models, fields, api
from datetime import datetime, timedelta

class DeliverySlot(models.Model):
    _name = 'delivery.slot'
    _description = 'Delivery Time Slot'
    _order = 'date, start_time'

    name = fields.Char(
        string='Slot Name',
        compute='_compute_name',
        store=True
    )
    date = fields.Date(
        string='Delivery Date',
        required=True,
        index=True
    )
    start_time = fields.Float(
        string='Start Time',
        required=True
    )
    end_time = fields.Float(
        string='End Time',
        required=True
    )

    # Capacity
    max_orders = fields.Integer(
        string='Max Orders',
        default=50
    )
    booked_count = fields.Integer(
        string='Booked Orders',
        compute='_compute_booked_count'
    )
    available_capacity = fields.Integer(
        string='Available',
        compute='_compute_available_capacity'
    )

    # Pricing
    is_express = fields.Boolean(
        string='Express Delivery',
        default=False
    )
    extra_charge = fields.Monetary(
        string='Extra Charge',
        currency_field='currency_id',
        default=0
    )
    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.company.currency_id
    )

    # Zone
    zone_id = fields.Many2one(
        'delivery.zone',
        string='Delivery Zone'
    )

    active = fields.Boolean(default=True)

    @api.depends('date', 'start_time', 'end_time')
    def _compute_name(self):
        for slot in self:
            start = self._float_to_time(slot.start_time)
            end = self._float_to_time(slot.end_time)
            slot.name = f"{slot.date} {start}-{end}"

    def _compute_booked_count(self):
        for slot in self:
            slot.booked_count = self.env['checkout.session'].search_count([
                ('delivery_slot_id', '=', slot.id),
                ('completed', '=', True)
            ])

    @api.depends('max_orders', 'booked_count')
    def _compute_available_capacity(self):
        for slot in self:
            slot.available_capacity = slot.max_orders - slot.booked_count

    @staticmethod
    def _float_to_time(float_time):
        hours = int(float_time)
        minutes = int((float_time - hours) * 60)
        return f"{hours:02d}:{minutes:02d}"

    @api.model
    def get_available_slots(self, zone_id=None, days_ahead=7):
        """Get available delivery slots"""
        today = fields.Date.today()
        end_date = today + timedelta(days=days_ahead)

        domain = [
            ('date', '>=', today),
            ('date', '<=', end_date),
            ('active', '=', True),
        ]
        if zone_id:
            domain.append(('zone_id', '=', zone_id))

        slots = self.search(domain)

        # Group by date
        slots_by_date = {}
        for slot in slots:
            if slot.available_capacity > 0:
                date_str = slot.date.isoformat()
                if date_str not in slots_by_date:
                    slots_by_date[date_str] = []
                slots_by_date[date_str].append({
                    'id': slot.id,
                    'start': self._float_to_time(slot.start_time),
                    'end': self._float_to_time(slot.end_time),
                    'available': slot.available_capacity,
                    'is_express': slot.is_express,
                    'extra_charge': slot.extra_charge,
                })

        return slots_by_date

    @api.model
    def generate_slots_cron(self):
        """Cron: Generate delivery slots for next 14 days"""
        today = fields.Date.today()

        # Default slot times
        slot_times = [
            (9.0, 12.0),   # 9 AM - 12 PM
            (12.0, 15.0),  # 12 PM - 3 PM
            (15.0, 18.0),  # 3 PM - 6 PM
            (18.0, 21.0),  # 6 PM - 9 PM
        ]

        zones = self.env['delivery.zone'].search([('active', '=', True)])

        for day_offset in range(14):
            date = today + timedelta(days=day_offset)

            for zone in zones:
                for start, end in slot_times:
                    existing = self.search([
                        ('date', '=', date),
                        ('start_time', '=', start),
                        ('zone_id', '=', zone.id)
                    ])
                    if not existing:
                        self.create({
                            'date': date,
                            'start_time': start,
                            'end_time': end,
                            'zone_id': zone.id,
                            'max_orders': zone.default_slot_capacity,
                        })
```

#### Dev 3 (Frontend Lead)

**Checkout Wizard Component**

```javascript
/** @odoo-module **/
// File: smart_dairy_ecommerce/static/src/js/checkout/checkout_wizard.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class CheckoutWizard extends Component {
    static template = "smart_dairy_ecommerce.CheckoutWizard";
    static components = {
        CartReviewStep: () => import('./steps/cart_review'),
        ShippingStep: () => import('./steps/shipping'),
        PaymentStep: () => import('./steps/payment'),
        ConfirmationStep: () => import('./steps/confirmation'),
    };

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            currentStep: 0,
            steps: ['cart', 'shipping', 'payment', 'confirm'],
            loading: false,
            session: null,
            cartItems: [],
            totals: {},
            shippingAddress: null,
            deliverySlot: null,
            paymentMethod: null,
            promoCode: '',
            promoDiscount: 0,
            error: null,
        });

        onWillStart(async () => {
            await this.initCheckout();
        });
    }

    async initCheckout() {
        try {
            this.state.loading = true;
            const result = await this.rpc('/api/v1/checkout/init', {});

            if (result.success) {
                this.state.session = result.data.session;
                this.state.cartItems = result.data.cart_items;
                this.state.totals = result.data.totals;
            } else {
                this.state.error = result.error;
            }
        } catch (error) {
            this.state.error = 'Failed to initialize checkout';
        } finally {
            this.state.loading = false;
        }
    }

    get currentStepName() {
        return this.state.steps[this.state.currentStep];
    }

    get isFirstStep() {
        return this.state.currentStep === 0;
    }

    get isLastStep() {
        return this.state.currentStep === this.state.steps.length - 1;
    }

    async nextStep() {
        // Validate current step
        const validation = await this.validateCurrentStep();
        if (!validation.success) {
            this.notification.add(validation.error, { type: "warning" });
            return;
        }

        if (!this.isLastStep) {
            this.state.currentStep++;
            await this.saveProgress();
        } else {
            await this.completeCheckout();
        }
    }

    previousStep() {
        if (!this.isFirstStep) {
            this.state.currentStep--;
        }
    }

    goToStep(index) {
        if (index < this.state.currentStep) {
            this.state.currentStep = index;
        }
    }

    async validateCurrentStep() {
        const step = this.currentStepName;

        if (step === 'cart') {
            return this.state.cartItems.length > 0
                ? { success: true }
                : { success: false, error: 'Your cart is empty' };
        }

        if (step === 'shipping') {
            if (!this.state.shippingAddress) {
                return { success: false, error: 'Please provide shipping address' };
            }
            if (!this.state.deliverySlot) {
                return { success: false, error: 'Please select delivery slot' };
            }
            return { success: true };
        }

        if (step === 'payment') {
            if (!this.state.paymentMethod) {
                return { success: false, error: 'Please select payment method' };
            }
            return { success: true };
        }

        return { success: true };
    }

    async saveProgress() {
        try {
            await this.rpc('/api/v1/checkout/update', {
                session_id: this.state.session.id,
                step: this.currentStepName,
                data: {
                    shipping_address: this.state.shippingAddress,
                    delivery_slot_id: this.state.deliverySlot?.id,
                    payment_method: this.state.paymentMethod,
                    promo_code: this.state.promoCode,
                }
            });
        } catch (error) {
            console.error('Failed to save progress:', error);
        }
    }

    async applyPromoCode() {
        if (!this.state.promoCode) return;

        try {
            const result = await this.rpc('/api/v1/checkout/apply-promo', {
                session_id: this.state.session.id,
                code: this.state.promoCode,
            });

            if (result.success) {
                this.state.promoDiscount = result.data.discount;
                this.state.totals = result.data.totals;
                this.notification.add(`Promo code applied! You save ${result.data.discount_formatted}`, {
                    type: "success"
                });
            } else {
                this.notification.add(result.error, { type: "danger" });
            }
        } catch (error) {
            this.notification.add("Failed to apply promo code", { type: "danger" });
        }
    }

    async completeCheckout() {
        try {
            this.state.loading = true;
            const result = await this.rpc('/api/v1/checkout/complete', {
                session_id: this.state.session.id,
            });

            if (result.success) {
                window.location.href = `/shop/confirmation/${result.data.order_id}`;
            } else {
                this.notification.add(result.error, { type: "danger" });
            }
        } catch (error) {
            this.notification.add("Checkout failed. Please try again.", { type: "danger" });
        } finally {
            this.state.loading = false;
        }
    }

    formatCurrency(amount) {
        return `৳${amount.toLocaleString('en-BD')}`;
    }
}
```

---

### Day 383-386: Promo Codes & Payment Integration

#### Dev 1 - Promo Code System

```python
# File: smart_dairy_ecommerce/models/promo_code.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError
from datetime import datetime

class PromoCode(models.Model):
    _name = 'promo.code'
    _description = 'Promotional Code'

    name = fields.Char(string='Code', required=True, index=True)
    description = fields.Text(string='Description')
    active = fields.Boolean(default=True)

    # Discount Type
    discount_type = fields.Selection([
        ('percent', 'Percentage'),
        ('fixed', 'Fixed Amount'),
        ('free_shipping', 'Free Shipping'),
    ], string='Discount Type', required=True, default='percent')

    discount_value = fields.Float(string='Discount Value')
    max_discount = fields.Monetary(
        string='Maximum Discount',
        currency_field='currency_id',
        help='Maximum discount amount for percentage discounts'
    )

    # Conditions
    min_order_amount = fields.Monetary(
        string='Minimum Order',
        currency_field='currency_id'
    )
    max_uses = fields.Integer(
        string='Maximum Uses',
        default=0,
        help='0 = unlimited'
    )
    uses_per_customer = fields.Integer(
        string='Uses per Customer',
        default=1
    )
    current_uses = fields.Integer(
        string='Current Uses',
        default=0,
        readonly=True
    )

    # Validity
    date_start = fields.Datetime(string='Valid From')
    date_end = fields.Datetime(string='Valid Until')

    # Restrictions
    first_order_only = fields.Boolean(
        string='First Order Only',
        default=False
    )
    product_ids = fields.Many2many(
        'product.product',
        string='Applicable Products'
    )
    category_ids = fields.Many2many(
        'product.category',
        string='Applicable Categories'
    )
    excluded_product_ids = fields.Many2many(
        'product.product',
        'promo_excluded_products_rel',
        string='Excluded Products'
    )

    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.company.currency_id
    )

    _sql_constraints = [
        ('unique_code', 'UNIQUE(name)', 'Promo code must be unique'),
    ]

    def validate_code(self, partner_id, order_amount, product_ids=None):
        """Validate promo code for given context"""
        self.ensure_one()
        now = datetime.now()

        # Check active
        if not self.active:
            return {'valid': False, 'error': 'This code is no longer active'}

        # Check dates
        if self.date_start and now < self.date_start:
            return {'valid': False, 'error': 'This code is not yet valid'}
        if self.date_end and now > self.date_end:
            return {'valid': False, 'error': 'This code has expired'}

        # Check max uses
        if self.max_uses > 0 and self.current_uses >= self.max_uses:
            return {'valid': False, 'error': 'This code has reached its usage limit'}

        # Check minimum order
        if self.min_order_amount and order_amount < self.min_order_amount:
            return {
                'valid': False,
                'error': f'Minimum order of ৳{self.min_order_amount} required'
            }

        # Check customer usage
        if partner_id:
            customer_uses = self.env['promo.code.usage'].search_count([
                ('promo_code_id', '=', self.id),
                ('partner_id', '=', partner_id)
            ])
            if customer_uses >= self.uses_per_customer:
                return {'valid': False, 'error': 'You have already used this code'}

            # Check first order only
            if self.first_order_only:
                partner = self.env['res.partner'].browse(partner_id)
                if partner.total_orders > 0:
                    return {'valid': False, 'error': 'This code is for first orders only'}

        return {'valid': True}

    def calculate_discount(self, order_amount, product_totals=None):
        """Calculate discount amount"""
        self.ensure_one()

        if self.discount_type == 'percent':
            discount = order_amount * (self.discount_value / 100)
            if self.max_discount:
                discount = min(discount, self.max_discount)
            return discount

        elif self.discount_type == 'fixed':
            return min(self.discount_value, order_amount)

        elif self.discount_type == 'free_shipping':
            return 0  # Shipping cost handled separately

        return 0

    def record_usage(self, partner_id, order_id):
        """Record promo code usage"""
        self.env['promo.code.usage'].create({
            'promo_code_id': self.id,
            'partner_id': partner_id,
            'order_id': order_id,
        })
        self.current_uses += 1


class PromoCodeUsage(models.Model):
    _name = 'promo.code.usage'
    _description = 'Promo Code Usage Record'

    promo_code_id = fields.Many2one(
        'promo.code',
        string='Promo Code',
        required=True,
        ondelete='cascade'
    )
    partner_id = fields.Many2one(
        'res.partner',
        string='Customer'
    )
    order_id = fields.Many2one(
        'sale.order',
        string='Order'
    )
    used_at = fields.Datetime(
        string='Used At',
        default=fields.Datetime.now
    )
```

#### Dev 2 - Payment Gateway Integration

```python
# File: smart_dairy_ecommerce/models/payment_gateway.py

from odoo import models, fields, api
import requests
import hashlib
import json

class PaymentTransaction(models.Model):
    _name = 'payment.transaction.custom'
    _description = 'Payment Transaction'

    reference = fields.Char(
        string='Reference',
        required=True,
        index=True
    )
    checkout_session_id = fields.Many2one(
        'checkout.session',
        string='Checkout Session'
    )
    sale_order_id = fields.Many2one(
        'sale.order',
        string='Sale Order'
    )
    partner_id = fields.Many2one(
        'res.partner',
        string='Customer'
    )

    amount = fields.Monetary(
        string='Amount',
        currency_field='currency_id',
        required=True
    )
    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.company.currency_id
    )

    provider = fields.Selection([
        ('bkash', 'bKash'),
        ('nagad', 'Nagad'),
        ('rocket', 'Rocket'),
        ('sslcommerz', 'SSLCommerz'),
        ('cod', 'Cash on Delivery'),
    ], string='Provider', required=True)

    state = fields.Selection([
        ('draft', 'Draft'),
        ('pending', 'Pending'),
        ('done', 'Completed'),
        ('error', 'Error'),
        ('cancel', 'Cancelled'),
    ], string='Status', default='draft')

    provider_reference = fields.Char(string='Provider Reference')
    provider_response = fields.Text(string='Provider Response')

    error_message = fields.Text(string='Error Message')

    def initiate_payment(self):
        """Initiate payment with provider"""
        self.ensure_one()

        if self.provider == 'bkash':
            return self._initiate_bkash()
        elif self.provider == 'nagad':
            return self._initiate_nagad()
        elif self.provider == 'sslcommerz':
            return self._initiate_sslcommerz()
        elif self.provider == 'cod':
            return self._process_cod()

        return {'error': 'Unknown payment provider'}

    def _initiate_bkash(self):
        """Initiate bKash payment"""
        config = self.env['ir.config_parameter'].sudo()
        app_key = config.get_param('bkash.app_key')
        app_secret = config.get_param('bkash.app_secret')
        base_url = config.get_param('bkash.base_url')

        # Get grant token
        token_response = requests.post(
            f"{base_url}/tokenized/checkout/token/grant",
            headers={
                'Content-Type': 'application/json',
                'username': config.get_param('bkash.username'),
                'password': config.get_param('bkash.password'),
            },
            json={
                'app_key': app_key,
                'app_secret': app_secret,
            }
        )

        if token_response.status_code != 200:
            return {'error': 'Failed to get bKash token'}

        token_data = token_response.json()
        id_token = token_data.get('id_token')

        # Create payment
        callback_url = f"{config.get_param('web.base.url')}/payment/bkash/callback"

        payment_response = requests.post(
            f"{base_url}/tokenized/checkout/create",
            headers={
                'Content-Type': 'application/json',
                'Authorization': id_token,
                'X-APP-Key': app_key,
            },
            json={
                'mode': '0011',
                'payerReference': self.reference,
                'callbackURL': callback_url,
                'amount': str(self.amount),
                'currency': 'BDT',
                'intent': 'sale',
                'merchantInvoiceNumber': self.reference,
            }
        )

        if payment_response.status_code == 200:
            data = payment_response.json()
            self.write({
                'state': 'pending',
                'provider_reference': data.get('paymentID'),
                'provider_response': json.dumps(data),
            })
            return {
                'success': True,
                'redirect_url': data.get('bkashURL'),
            }

        return {'error': 'Failed to create bKash payment'}

    def _initiate_sslcommerz(self):
        """Initiate SSLCommerz payment"""
        config = self.env['ir.config_parameter'].sudo()
        store_id = config.get_param('sslcommerz.store_id')
        store_passwd = config.get_param('sslcommerz.store_passwd')
        base_url = config.get_param('sslcommerz.base_url')

        callback_base = config.get_param('web.base.url')

        data = {
            'store_id': store_id,
            'store_passwd': store_passwd,
            'total_amount': self.amount,
            'currency': 'BDT',
            'tran_id': self.reference,
            'success_url': f"{callback_base}/payment/sslcommerz/success",
            'fail_url': f"{callback_base}/payment/sslcommerz/fail",
            'cancel_url': f"{callback_base}/payment/sslcommerz/cancel",
            'cus_name': self.partner_id.name or 'Guest',
            'cus_email': self.partner_id.email or 'guest@example.com',
            'cus_phone': self.partner_id.phone or '01700000000',
            'cus_add1': self.partner_id.street or 'N/A',
            'cus_city': self.partner_id.city or 'Dhaka',
            'cus_country': 'Bangladesh',
            'shipping_method': 'NO',
            'product_name': 'Smart Dairy Order',
            'product_category': 'E-commerce',
            'product_profile': 'general',
        }

        response = requests.post(
            f"{base_url}/gwprocess/v4/api.php",
            data=data
        )

        if response.status_code == 200:
            result = response.json()
            if result.get('status') == 'SUCCESS':
                self.write({
                    'state': 'pending',
                    'provider_reference': result.get('sessionkey'),
                    'provider_response': json.dumps(result),
                })
                return {
                    'success': True,
                    'redirect_url': result.get('GatewayPageURL'),
                }

        return {'error': 'Failed to create SSLCommerz payment'}

    def _process_cod(self):
        """Process Cash on Delivery"""
        self.write({
            'state': 'pending',
            'provider_reference': f'COD-{self.reference}',
        })
        return {'success': True, 'cod': True}

    def confirm_payment(self, provider_data):
        """Confirm payment after provider callback"""
        self.ensure_one()
        self.write({
            'state': 'done',
            'provider_response': json.dumps(provider_data),
        })

        # Confirm sale order
        if self.sale_order_id:
            self.sale_order_id.action_confirm()

        return True
```

---

## 5. Technical Specifications

### 5.1 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/checkout/init` | POST | Initialize checkout session |
| `/api/v1/checkout/update` | POST | Update checkout data |
| `/api/v1/checkout/apply-promo` | POST | Apply promo code |
| `/api/v1/checkout/delivery-slots` | GET | Get available slots |
| `/api/v1/checkout/complete` | POST | Complete checkout |
| `/payment/bkash/callback` | POST | bKash callback |
| `/payment/sslcommerz/*` | POST | SSLCommerz callbacks |

### 5.2 Performance Targets

| Metric | Target |
|--------|--------|
| Checkout init | < 1s |
| Promo validation | < 200ms |
| Payment redirect | < 2s |
| Order confirmation | < 3s |

---

## 6. Sign-off Checklist

- [ ] Multi-step checkout flow works
- [ ] Guest checkout functional
- [ ] Address validation accurate
- [ ] Delivery slots display correctly
- [ ] Promo codes validate and apply
- [ ] All payment methods work
- [ ] One-click checkout for returning customers
- [ ] Order confirmation emails sent
- [ ] Mobile checkout optimized

---

**Document End**

*Last Updated: 2025-01-15*
*Version: 1.0*
