# Milestone 63: Shopping Experience

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P7-M63-v1.0 |
| **Milestone** | 63 - Shopping Experience |
| **Phase** | Phase 7: B2C E-Commerce Core |
| **Days** | 371-380 |
| **Duration** | 10 Working Days |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Technical Architecture Team |
| **Last Updated** | 2025-01-15 |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Optimize the shopping experience with quick add-to-cart, product bundles, mini cart, stock indicators, and engagement features to increase conversion rates and average order value.**

### 1.2 Context

Milestone 63 focuses on enhancing the product browsing and cart experience. This includes AJAX-powered quick cart functionality, product bundling capabilities, real-time stock indicators, and back-in-stock notifications to reduce friction and increase sales.

### 1.3 Scope Summary

| Area | Included |
|------|----------|
| Quick Cart | ✅ AJAX add-to-cart, quantity updates |
| Mini Cart | ✅ Slide-out cart, live updates |
| Product Bundles | ✅ Combo deals, bundle pricing |
| Stock Indicators | ✅ Low stock alerts, availability status |
| Back-in-Stock | ✅ Email/push notifications |
| Product Sharing | ✅ Social media integration |
| Size Guides | ✅ Product guides and FAQs |
| Recently Viewed | ✅ Product history tracking |

---

## 2. Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| O1 | Implement AJAX quick add-to-cart | Cart update < 500ms |
| O2 | Create product bundle system | Bundle AOV +25% |
| O3 | Build real-time mini cart | Updates without refresh |
| O4 | Add stock level indicators | Display accuracy 100% |
| O5 | Launch back-in-stock alerts | Notification delivery > 98% |
| O6 | Enable social sharing | Share rate > 2% |
| O7 | Implement recently viewed | Track last 20 products |
| O8 | Create quantity selectors | Intuitive UX |

---

## 3. Key Deliverables

| # | Deliverable | Owner | Format | Due Day |
|---|-------------|-------|--------|---------|
| D1 | Quick cart AJAX API | Dev 1 | Python | Day 372 |
| D2 | Product bundle model | Dev 1 | Python | Day 374 |
| D3 | Stock notification service | Dev 2 | Python | Day 373 |
| D4 | Back-in-stock subscription | Dev 2 | Python/API | Day 375 |
| D5 | Mini cart OWL component | Dev 3 | JavaScript | Day 373 |
| D6 | Quick add-to-cart UI | Dev 3 | OWL/QWeb | Day 374 |
| D7 | Bundle display component | Dev 3 | OWL/QWeb | Day 376 |
| D8 | Stock indicator widgets | Dev 3 | OWL | Day 377 |
| D9 | Social sharing integration | Dev 2 | Python/JS | Day 378 |
| D10 | Integration & testing | All | Tests | Day 380 |

---

## 4. Requirement Traceability

### 4.1 RFP Requirements

| RFP Req ID | Description | Deliverable |
|------------|-------------|-------------|
| RFP-B2C-4.3.1-001 | Quick add to cart | D1, D6 |
| RFP-B2C-4.3.1-002 | Mini cart display | D5 |
| RFP-B2C-4.3.2-001 | Product bundles | D2, D7 |
| RFP-B2C-4.3.3-001 | Stock availability | D3, D8 |
| RFP-B2C-4.3.3-002 | Back-in-stock alerts | D4 |
| RFP-B2C-4.3.4-001 | Social sharing | D9 |

### 4.2 BRD Requirements

| BRD Req ID | Description | Deliverable |
|------------|-------------|-------------|
| BRD-FR-B2C-003 | Shopping cart experience | All |
| BRD-FR-B2C-003.1 | Add to cart without page reload | D1, D5, D6 |
| BRD-FR-B2C-003.2 | Bundle/combo products | D2, D7 |
| BRD-FR-B2C-003.3 | Stock visibility | D3, D8 |

---

## 5. Day-by-Day Development Plan

### Day 371 (Phase Day 21): Cart API Foundation

#### Day 371 Objectives
- Design cart data structure for AJAX operations
- Create cart session management
- Set up WebSocket for real-time updates

---

#### Dev 1 (Backend Lead) - Day 371

**Focus: Cart API Architecture**

**Hour 1-4: Cart Session Management**

```python
# File: smart_dairy_ecommerce/models/cart_session.py

from odoo import models, fields, api
from odoo.http import request
import json
import hashlib
from datetime import datetime, timedelta

class CartSession(models.Model):
    _name = 'cart.session'
    _description = 'Shopping Cart Session'

    session_id = fields.Char(
        string='Session ID',
        required=True,
        index=True
    )
    partner_id = fields.Many2one(
        'res.partner',
        string='Customer'
    )
    sale_order_id = fields.Many2one(
        'sale.order',
        string='Sale Order'
    )

    # Cart State
    cart_data = fields.Text(
        string='Cart JSON',
        default='{}'
    )
    item_count = fields.Integer(
        string='Item Count',
        compute='_compute_cart_stats',
        store=True
    )
    subtotal = fields.Float(
        string='Subtotal',
        compute='_compute_cart_stats',
        store=True
    )

    # Timestamps
    last_activity = fields.Datetime(
        string='Last Activity',
        default=fields.Datetime.now
    )
    created_at = fields.Datetime(
        string='Created',
        default=fields.Datetime.now
    )

    # Abandonment tracking
    is_abandoned = fields.Boolean(
        string='Abandoned',
        default=False
    )
    abandonment_email_sent = fields.Boolean(
        string='Abandonment Email Sent',
        default=False
    )

    @api.depends('cart_data')
    def _compute_cart_stats(self):
        for session in self:
            try:
                cart = json.loads(session.cart_data or '{}')
                items = cart.get('items', [])
                session.item_count = sum(item.get('quantity', 0) for item in items)
                session.subtotal = sum(
                    item.get('quantity', 0) * item.get('price', 0)
                    for item in items
                )
            except json.JSONDecodeError:
                session.item_count = 0
                session.subtotal = 0

    @api.model
    def get_or_create_session(self, session_id=None, partner_id=None):
        """Get existing cart session or create new one"""
        domain = []

        if partner_id:
            domain = [('partner_id', '=', partner_id)]
        elif session_id:
            domain = [('session_id', '=', session_id)]
        else:
            # Generate new session ID
            session_id = hashlib.sha256(
                f"{datetime.now().isoformat()}{id(self)}".encode()
            ).hexdigest()[:32]

        session = self.search(domain, limit=1) if domain else False

        if not session:
            session = self.create({
                'session_id': session_id,
                'partner_id': partner_id,
            })

        return session

    def update_cart(self, items):
        """Update cart with new items"""
        self.ensure_one()
        self.write({
            'cart_data': json.dumps({'items': items}),
            'last_activity': fields.Datetime.now(),
        })
        return self._get_cart_summary()

    def add_item(self, product_id, quantity=1, **kwargs):
        """Add item to cart"""
        self.ensure_one()
        cart = json.loads(self.cart_data or '{}')
        items = cart.get('items', [])

        product = self.env['product.product'].browse(product_id)
        if not product.exists():
            return {'error': 'Product not found'}

        # Check stock
        if product.type == 'product' and product.qty_available < quantity:
            return {'error': 'Insufficient stock', 'available': product.qty_available}

        # Find existing item or add new
        existing = next((i for i in items if i['product_id'] == product_id), None)

        if existing:
            new_qty = existing['quantity'] + quantity
            if product.type == 'product' and product.qty_available < new_qty:
                return {'error': 'Insufficient stock', 'available': product.qty_available}
            existing['quantity'] = new_qty
        else:
            items.append({
                'product_id': product_id,
                'name': product.name,
                'quantity': quantity,
                'price': product.lst_price,
                'image_url': f'/web/image/product.product/{product_id}/image_128',
                'uom': product.uom_id.name,
                'bundle_id': kwargs.get('bundle_id'),
            })

        return self.update_cart(items)

    def update_item_quantity(self, product_id, quantity):
        """Update item quantity"""
        self.ensure_one()
        cart = json.loads(self.cart_data or '{}')
        items = cart.get('items', [])

        if quantity <= 0:
            items = [i for i in items if i['product_id'] != product_id]
        else:
            product = self.env['product.product'].browse(product_id)
            if product.type == 'product' and product.qty_available < quantity:
                return {'error': 'Insufficient stock', 'available': product.qty_available}

            for item in items:
                if item['product_id'] == product_id:
                    item['quantity'] = quantity
                    break

        return self.update_cart(items)

    def remove_item(self, product_id):
        """Remove item from cart"""
        return self.update_item_quantity(product_id, 0)

    def clear_cart(self):
        """Clear all items from cart"""
        self.ensure_one()
        return self.update_cart([])

    def _get_cart_summary(self):
        """Get cart summary for API response"""
        self.ensure_one()
        cart = json.loads(self.cart_data or '{}')
        items = cart.get('items', [])

        # Enrich with current product data
        enriched_items = []
        for item in items:
            product = self.env['product.product'].browse(item['product_id'])
            if product.exists():
                enriched_items.append({
                    **item,
                    'name': product.name,
                    'price': product.lst_price,
                    'stock_available': product.qty_available if product.type == 'product' else 999,
                    'image_url': f'/web/image/product.product/{item["product_id"]}/image_128',
                })

        subtotal = sum(i['quantity'] * i['price'] for i in enriched_items)

        return {
            'session_id': self.session_id,
            'items': enriched_items,
            'item_count': sum(i['quantity'] for i in enriched_items),
            'subtotal': subtotal,
            'currency': self.env.company.currency_id.symbol,
        }

    @api.model
    def mark_abandoned_carts(self):
        """Cron: Mark carts as abandoned after 1 hour of inactivity"""
        threshold = datetime.now() - timedelta(hours=1)
        abandoned = self.search([
            ('last_activity', '<', threshold),
            ('is_abandoned', '=', False),
            ('item_count', '>', 0),
        ])
        abandoned.write({'is_abandoned': True})
        return True
```

**Hour 5-8: Cart API Controller**

```python
# File: smart_dairy_ecommerce/controllers/cart_controller.py

from odoo import http
from odoo.http import request
import json

class CartController(http.Controller):

    def _get_cart_session(self):
        """Get or create cart session for current user/visitor"""
        session_id = request.httprequest.cookies.get('cart_session_id')
        partner_id = request.env.user.partner_id.id if request.env.user._is_public() is False else None

        cart = request.env['cart.session'].sudo().get_or_create_session(
            session_id=session_id,
            partner_id=partner_id
        )
        return cart

    @http.route('/api/v1/cart', type='json', auth='public', methods=['GET'], csrf=False)
    def get_cart(self, **kwargs):
        """Get current cart contents"""
        cart = self._get_cart_session()
        return {
            'success': True,
            'data': cart._get_cart_summary()
        }

    @http.route('/api/v1/cart/add', type='json', auth='public', methods=['POST'], csrf=False)
    def add_to_cart(self, **kwargs):
        """Add item to cart"""
        product_id = kwargs.get('product_id')
        quantity = kwargs.get('quantity', 1)
        bundle_id = kwargs.get('bundle_id')

        if not product_id:
            return {'error': 'product_id is required', 'code': 400}

        cart = self._get_cart_session()
        result = cart.add_item(product_id, quantity, bundle_id=bundle_id)

        if 'error' in result:
            return {'error': result['error'], 'code': 400, 'available': result.get('available')}

        response = request.make_response(json.dumps({
            'success': True,
            'data': result,
            'message': 'Item added to cart'
        }))

        # Set session cookie if new
        if not request.httprequest.cookies.get('cart_session_id'):
            response.set_cookie('cart_session_id', cart.session_id, max_age=30*24*60*60)

        return {
            'success': True,
            'data': result,
            'message': 'Item added to cart'
        }

    @http.route('/api/v1/cart/update', type='json', auth='public', methods=['POST'], csrf=False)
    def update_cart_item(self, **kwargs):
        """Update item quantity in cart"""
        product_id = kwargs.get('product_id')
        quantity = kwargs.get('quantity', 0)

        if not product_id:
            return {'error': 'product_id is required', 'code': 400}

        cart = self._get_cart_session()
        result = cart.update_item_quantity(product_id, quantity)

        if 'error' in result:
            return {'error': result['error'], 'code': 400}

        return {
            'success': True,
            'data': result,
            'message': 'Cart updated'
        }

    @http.route('/api/v1/cart/remove', type='json', auth='public', methods=['POST'], csrf=False)
    def remove_from_cart(self, **kwargs):
        """Remove item from cart"""
        product_id = kwargs.get('product_id')

        if not product_id:
            return {'error': 'product_id is required', 'code': 400}

        cart = self._get_cart_session()
        result = cart.remove_item(product_id)

        return {
            'success': True,
            'data': result,
            'message': 'Item removed'
        }

    @http.route('/api/v1/cart/clear', type='json', auth='public', methods=['POST'], csrf=False)
    def clear_cart(self, **kwargs):
        """Clear entire cart"""
        cart = self._get_cart_session()
        result = cart.clear_cart()

        return {
            'success': True,
            'data': result,
            'message': 'Cart cleared'
        }

    @http.route('/api/v1/cart/count', type='json', auth='public', methods=['GET'], csrf=False)
    def get_cart_count(self, **kwargs):
        """Get cart item count (lightweight endpoint for header)"""
        cart = self._get_cart_session()
        return {
            'success': True,
            'data': {
                'count': cart.item_count,
                'subtotal': cart.subtotal,
            }
        }
```

---

#### Dev 2 (Full-Stack) - Day 371

**Focus: Stock Level Service**

**Hour 1-4: Stock Level Model Extensions**

```python
# File: smart_dairy_ecommerce/models/stock_level.py

from odoo import models, fields, api

class ProductProduct(models.Model):
    _inherit = 'product.product'

    # Stock Display Fields
    stock_status = fields.Selection([
        ('in_stock', 'In Stock'),
        ('low_stock', 'Low Stock'),
        ('out_of_stock', 'Out of Stock'),
        ('preorder', 'Pre-order'),
    ], string='Stock Status', compute='_compute_stock_status', store=True)

    low_stock_threshold = fields.Integer(
        string='Low Stock Threshold',
        default=10
    )
    show_stock_quantity = fields.Boolean(
        string='Show Exact Quantity',
        default=False
    )
    allow_backorder = fields.Boolean(
        string='Allow Backorder',
        default=False
    )
    expected_restock_date = fields.Date(
        string='Expected Restock Date'
    )

    # Back-in-stock subscribers
    stock_subscriber_ids = fields.One2many(
        'stock.subscriber',
        'product_id',
        string='Stock Subscribers'
    )
    subscriber_count = fields.Integer(
        string='Waiting Customers',
        compute='_compute_subscriber_count'
    )

    @api.depends('qty_available', 'low_stock_threshold', 'allow_backorder')
    def _compute_stock_status(self):
        for product in self:
            if product.type != 'product':
                product.stock_status = 'in_stock'
            elif product.qty_available <= 0:
                product.stock_status = 'preorder' if product.allow_backorder else 'out_of_stock'
            elif product.qty_available <= product.low_stock_threshold:
                product.stock_status = 'low_stock'
            else:
                product.stock_status = 'in_stock'

    def _compute_subscriber_count(self):
        for product in self:
            product.subscriber_count = len(product.stock_subscriber_ids.filtered(
                lambda s: not s.notified
            ))

    def get_stock_info(self):
        """Get stock information for API"""
        self.ensure_one()
        return {
            'status': self.stock_status,
            'available': self.qty_available if self.show_stock_quantity else None,
            'low_stock': self.stock_status == 'low_stock',
            'threshold': self.low_stock_threshold if self.show_stock_quantity else None,
            'allow_backorder': self.allow_backorder,
            'expected_restock': self.expected_restock_date.isoformat() if self.expected_restock_date else None,
            'waiting_count': self.subscriber_count if self.stock_status == 'out_of_stock' else 0,
        }


class StockSubscriber(models.Model):
    _name = 'stock.subscriber'
    _description = 'Back-in-Stock Subscriber'
    _order = 'create_date desc'

    product_id = fields.Many2one(
        'product.product',
        string='Product',
        required=True,
        ondelete='cascade',
        index=True
    )
    partner_id = fields.Many2one(
        'res.partner',
        string='Customer'
    )
    email = fields.Char(
        string='Email',
        required=True,
        index=True
    )
    phone = fields.Char(string='Phone')

    notify_email = fields.Boolean(
        string='Notify by Email',
        default=True
    )
    notify_sms = fields.Boolean(
        string='Notify by SMS',
        default=False
    )
    notify_push = fields.Boolean(
        string='Notify by Push',
        default=False
    )

    notified = fields.Boolean(
        string='Notified',
        default=False
    )
    notified_date = fields.Datetime(
        string='Notification Date'
    )

    converted = fields.Boolean(
        string='Converted to Sale',
        default=False
    )
    converted_order_id = fields.Many2one(
        'sale.order',
        string='Converted Order'
    )

    _sql_constraints = [
        ('unique_subscriber', 'UNIQUE(product_id, email)',
         'You are already subscribed for this product'),
    ]

    @api.model
    def subscribe(self, product_id, email, **kwargs):
        """Subscribe to back-in-stock notification"""
        product = self.env['product.product'].browse(product_id)
        if not product.exists():
            return {'error': 'Product not found'}

        if product.stock_status != 'out_of_stock':
            return {'error': 'Product is currently in stock'}

        # Check existing subscription
        existing = self.search([
            ('product_id', '=', product_id),
            ('email', '=', email),
            ('notified', '=', False)
        ])
        if existing:
            return {'message': 'Already subscribed'}

        partner = self.env['res.partner'].search([('email', '=', email)], limit=1)

        self.create({
            'product_id': product_id,
            'email': email,
            'partner_id': partner.id if partner else False,
            'phone': kwargs.get('phone'),
            'notify_email': kwargs.get('notify_email', True),
            'notify_sms': kwargs.get('notify_sms', False),
        })

        return {'success': True, 'message': 'You will be notified when this product is back in stock'}

    @api.model
    def notify_subscribers(self, product_id):
        """Notify all subscribers when product is back in stock"""
        subscribers = self.search([
            ('product_id', '=', product_id),
            ('notified', '=', False)
        ])

        for subscriber in subscribers:
            try:
                if subscriber.notify_email:
                    self._send_email_notification(subscriber)
                if subscriber.notify_sms and subscriber.phone:
                    self._send_sms_notification(subscriber)

                subscriber.write({
                    'notified': True,
                    'notified_date': fields.Datetime.now()
                })
            except Exception as e:
                # Log error but continue with other subscribers
                pass

        return len(subscribers)

    def _send_email_notification(self, subscriber):
        """Send back-in-stock email"""
        template = self.env.ref(
            'smart_dairy_ecommerce.email_back_in_stock',
            raise_if_not_found=False
        )
        if template:
            template.send_mail(subscriber.id, force_send=True)

    def _send_sms_notification(self, subscriber):
        """Send back-in-stock SMS"""
        # SMS gateway integration
        pass
```

**Hour 5-8: Stock API Endpoints**

```python
# File: smart_dairy_ecommerce/controllers/stock_controller.py

from odoo import http
from odoo.http import request

class StockController(http.Controller):

    @http.route('/api/v1/product/<int:product_id>/stock', type='json', auth='public', methods=['GET'])
    def get_stock_info(self, product_id, **kwargs):
        """Get product stock information"""
        product = request.env['product.product'].sudo().browse(product_id)
        if not product.exists():
            return {'error': 'Product not found', 'code': 404}

        return {
            'success': True,
            'data': product.get_stock_info()
        }

    @http.route('/api/v1/product/<int:product_id>/notify-stock', type='json', auth='public', methods=['POST'])
    def subscribe_stock_notification(self, product_id, **kwargs):
        """Subscribe to back-in-stock notification"""
        email = kwargs.get('email')
        if not email:
            return {'error': 'Email is required', 'code': 400}

        result = request.env['stock.subscriber'].sudo().subscribe(
            product_id,
            email,
            phone=kwargs.get('phone'),
            notify_email=kwargs.get('notify_email', True),
            notify_sms=kwargs.get('notify_sms', False),
        )

        if 'error' in result:
            return {'error': result['error'], 'code': 400}

        return {'success': True, 'message': result.get('message')}

    @http.route('/api/v1/products/stock-status', type='json', auth='public', methods=['POST'])
    def get_bulk_stock_status(self, **kwargs):
        """Get stock status for multiple products"""
        product_ids = kwargs.get('product_ids', [])
        if not product_ids:
            return {'success': True, 'data': {}}

        products = request.env['product.product'].sudo().browse(product_ids)

        return {
            'success': True,
            'data': {
                str(p.id): p.get_stock_info() for p in products if p.exists()
            }
        }
```

---

#### Dev 3 (Frontend Lead) - Day 371

**Focus: Mini Cart Component Foundation**

**Hour 1-4: Mini Cart OWL Component**

```javascript
/** @odoo-module **/
// File: smart_dairy_ecommerce/static/src/js/cart/mini_cart.js

import { Component, useState, onWillStart, onMounted } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class MiniCart extends Component {
    static template = "smart_dairy_ecommerce.MiniCart";
    static props = {
        showOnAdd: { type: Boolean, optional: true },
    };

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            isOpen: false,
            loading: false,
            items: [],
            itemCount: 0,
            subtotal: 0,
            currency: '৳',
            updating: null, // product_id being updated
        });

        // Listen for cart events
        this.cartEventHandler = this.onCartEvent.bind(this);

        onWillStart(async () => {
            await this.loadCart();
        });

        onMounted(() => {
            document.addEventListener('cart:updated', this.cartEventHandler);
            document.addEventListener('cart:add', this.cartEventHandler);
        });
    }

    onCartEvent(event) {
        if (event.type === 'cart:add' && this.props.showOnAdd) {
            this.state.isOpen = true;
        }
        this.loadCart();
    }

    async loadCart() {
        try {
            const result = await this.rpc('/api/v1/cart', {});
            if (result.success) {
                this.state.items = result.data.items;
                this.state.itemCount = result.data.item_count;
                this.state.subtotal = result.data.subtotal;
                this.state.currency = result.data.currency;
            }
        } catch (error) {
            console.error('Failed to load cart:', error);
        }
    }

    toggleCart() {
        this.state.isOpen = !this.state.isOpen;
    }

    closeCart() {
        this.state.isOpen = false;
    }

    async updateQuantity(productId, delta) {
        const item = this.state.items.find(i => i.product_id === productId);
        if (!item) return;

        const newQty = item.quantity + delta;
        if (newQty < 1) {
            await this.removeItem(productId);
            return;
        }

        try {
            this.state.updating = productId;
            const result = await this.rpc('/api/v1/cart/update', {
                product_id: productId,
                quantity: newQty,
            });

            if (result.success) {
                this.state.items = result.data.items;
                this.state.itemCount = result.data.item_count;
                this.state.subtotal = result.data.subtotal;
                this.dispatchCartUpdate();
            } else {
                this.notification.add(result.error, { type: "warning" });
            }
        } catch (error) {
            this.notification.add("Failed to update cart", { type: "danger" });
        } finally {
            this.state.updating = null;
        }
    }

    async removeItem(productId) {
        try {
            this.state.updating = productId;
            const result = await this.rpc('/api/v1/cart/remove', {
                product_id: productId,
            });

            if (result.success) {
                this.state.items = result.data.items;
                this.state.itemCount = result.data.item_count;
                this.state.subtotal = result.data.subtotal;
                this.dispatchCartUpdate();
                this.notification.add("Item removed", { type: "info" });
            }
        } catch (error) {
            this.notification.add("Failed to remove item", { type: "danger" });
        } finally {
            this.state.updating = null;
        }
    }

    dispatchCartUpdate() {
        document.dispatchEvent(new CustomEvent('cart:updated', {
            detail: {
                itemCount: this.state.itemCount,
                subtotal: this.state.subtotal,
            }
        }));
    }

    formatCurrency(amount) {
        return `${this.state.currency}${amount.toLocaleString('en-BD')}`;
    }

    goToCheckout() {
        window.location.href = '/shop/checkout';
    }

    goToCart() {
        window.location.href = '/shop/cart';
    }
}
```

**Hour 5-8: Mini Cart QWeb Template**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- File: smart_dairy_ecommerce/static/src/xml/mini_cart.xml -->

<templates xml:space="preserve">
    <t t-name="smart_dairy_ecommerce.MiniCart">
        <div class="sd-mini-cart">
            <!-- Cart Toggle Button -->
            <button class="sd-cart-toggle btn position-relative"
                    t-on-click="toggleCart"
                    aria-label="Shopping Cart">
                <i class="fa fa-shopping-cart fa-lg"/>
                <t t-if="state.itemCount > 0">
                    <span class="sd-cart-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <t t-esc="state.itemCount"/>
                    </span>
                </t>
            </button>

            <!-- Slide-out Cart Panel -->
            <div t-attf-class="sd-cart-panel #{state.isOpen ? 'open' : ''}">
                <!-- Backdrop -->
                <div class="sd-cart-backdrop" t-on-click="closeCart"/>

                <!-- Cart Content -->
                <div class="sd-cart-content">
                    <!-- Header -->
                    <div class="sd-cart-header d-flex justify-content-between align-items-center p-3 border-bottom">
                        <h5 class="mb-0">
                            <i class="fa fa-shopping-cart me-2"/>
                            Your Cart
                            <span class="badge bg-primary ms-2" t-esc="state.itemCount"/>
                        </h5>
                        <button class="btn btn-sm btn-link text-muted" t-on-click="closeCart">
                            <i class="fa fa-times fa-lg"/>
                        </button>
                    </div>

                    <!-- Cart Items -->
                    <div class="sd-cart-items flex-grow-1 overflow-auto">
                        <t t-if="state.items.length">
                            <t t-foreach="state.items" t-as="item" t-key="item.product_id">
                                <div class="sd-cart-item p-3 border-bottom">
                                    <div class="d-flex">
                                        <!-- Product Image -->
                                        <img t-att-src="item.image_url"
                                             t-att-alt="item.name"
                                             class="sd-item-image rounded"
                                             width="64" height="64"/>

                                        <!-- Product Details -->
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1 sd-item-name" t-esc="item.name"/>
                                            <p class="text-muted small mb-2">
                                                <t t-esc="formatCurrency(item.price)"/> each
                                            </p>

                                            <!-- Quantity Controls -->
                                            <div class="d-flex align-items-center">
                                                <div class="sd-qty-control btn-group btn-group-sm">
                                                    <button class="btn btn-outline-secondary"
                                                            t-att-disabled="state.updating === item.product_id"
                                                            t-on-click="() => this.updateQuantity(item.product_id, -1)">
                                                        <i class="fa fa-minus"/>
                                                    </button>
                                                    <span class="btn btn-outline-secondary disabled">
                                                        <t t-if="state.updating === item.product_id">
                                                            <i class="fa fa-spinner fa-spin"/>
                                                        </t>
                                                        <t t-else="">
                                                            <t t-esc="item.quantity"/>
                                                        </t>
                                                    </span>
                                                    <button class="btn btn-outline-secondary"
                                                            t-att-disabled="state.updating === item.product_id || item.quantity >= item.stock_available"
                                                            t-on-click="() => this.updateQuantity(item.product_id, 1)">
                                                        <i class="fa fa-plus"/>
                                                    </button>
                                                </div>

                                                <button class="btn btn-sm btn-link text-danger ms-auto"
                                                        t-att-disabled="state.updating === item.product_id"
                                                        t-on-click="() => this.removeItem(item.product_id)">
                                                    <i class="fa fa-trash"/>
                                                </button>
                                            </div>

                                            <!-- Low Stock Warning -->
                                            <t t-if="item.stock_available &lt;= 5 and item.stock_available > 0">
                                                <p class="text-warning small mb-0 mt-1">
                                                    <i class="fa fa-exclamation-triangle me-1"/>
                                                    Only <t t-esc="item.stock_available"/> left
                                                </p>
                                            </t>
                                        </div>

                                        <!-- Item Total -->
                                        <div class="text-end ms-2">
                                            <strong t-esc="formatCurrency(item.quantity * item.price)"/>
                                        </div>
                                    </div>
                                </div>
                            </t>
                        </t>

                        <!-- Empty Cart -->
                        <t t-else="">
                            <div class="text-center py-5">
                                <i class="fa fa-shopping-cart fa-4x text-muted mb-3"/>
                                <p class="text-muted">Your cart is empty</p>
                                <a href="/shop" class="btn btn-primary" t-on-click="closeCart">
                                    Start Shopping
                                </a>
                            </div>
                        </t>
                    </div>

                    <!-- Cart Footer -->
                    <t t-if="state.items.length">
                        <div class="sd-cart-footer border-top p-3">
                            <!-- Subtotal -->
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold">Subtotal:</span>
                                <span class="fw-bold fs-5" t-esc="formatCurrency(state.subtotal)"/>
                            </div>

                            <p class="text-muted small mb-3">
                                Shipping and taxes calculated at checkout
                            </p>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary btn-lg" t-on-click="goToCheckout">
                                    Checkout
                                </button>
                                <button class="btn btn-outline-secondary" t-on-click="goToCart">
                                    View Full Cart
                                </button>
                            </div>
                        </div>
                    </t>
                </div>
            </div>
        </div>
    </t>
</templates>
```

---

#### Day 371 End-of-Day Deliverables

| # | Deliverable | Status | Owner |
|---|-------------|--------|-------|
| 1 | Cart session model | ✅ | Dev 1 |
| 2 | Cart CRUD operations | ✅ | Dev 1 |
| 3 | Cart API endpoints | ✅ | Dev 1 |
| 4 | Stock level model extensions | ✅ | Dev 2 |
| 5 | Stock subscriber model | ✅ | Dev 2 |
| 6 | Stock API endpoints | ✅ | Dev 2 |
| 7 | Mini cart OWL component | ✅ | Dev 3 |
| 8 | Mini cart QWeb template | ✅ | Dev 3 |

---

### Day 372-373: Quick Add-to-Cart & Bundle System

#### Dev 1 (Backend Lead) - Days 372-373

**Focus: Product Bundle Model**

```python
# File: smart_dairy_ecommerce/models/product_bundle.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError

class ProductBundle(models.Model):
    _name = 'product.bundle'
    _description = 'Product Bundle / Combo Deal'
    _order = 'sequence, id'

    name = fields.Char(
        string='Bundle Name',
        required=True,
        translate=True
    )
    code = fields.Char(
        string='Bundle Code',
        required=True,
        copy=False
    )
    active = fields.Boolean(default=True)
    sequence = fields.Integer(default=10)

    # Bundle Type
    bundle_type = fields.Selection([
        ('fixed', 'Fixed Bundle'),
        ('mix_match', 'Mix & Match'),
        ('bogo', 'Buy One Get One'),
    ], string='Bundle Type', default='fixed', required=True)

    # Products
    line_ids = fields.One2many(
        'product.bundle.line',
        'bundle_id',
        string='Bundle Lines'
    )

    # Pricing
    pricing_type = fields.Selection([
        ('fixed', 'Fixed Price'),
        ('discount_percent', 'Percentage Discount'),
        ('discount_amount', 'Fixed Discount'),
    ], string='Pricing Type', default='fixed', required=True)

    fixed_price = fields.Monetary(
        string='Bundle Price',
        currency_field='currency_id'
    )
    discount_percent = fields.Float(
        string='Discount %',
        default=0
    )
    discount_amount = fields.Monetary(
        string='Discount Amount',
        currency_field='currency_id'
    )

    # Computed Prices
    original_price = fields.Monetary(
        string='Original Price',
        compute='_compute_prices',
        currency_field='currency_id'
    )
    final_price = fields.Monetary(
        string='Final Price',
        compute='_compute_prices',
        currency_field='currency_id'
    )
    savings = fields.Monetary(
        string='You Save',
        compute='_compute_prices',
        currency_field='currency_id'
    )
    savings_percent = fields.Float(
        string='Savings %',
        compute='_compute_prices'
    )

    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.company.currency_id
    )

    # Validity
    date_start = fields.Date(string='Valid From')
    date_end = fields.Date(string='Valid Until')

    # Limits
    max_quantity_per_order = fields.Integer(
        string='Max per Order',
        default=0,
        help='0 = unlimited'
    )
    total_available = fields.Integer(
        string='Total Available',
        default=0,
        help='0 = unlimited'
    )
    sold_count = fields.Integer(
        string='Sold Count',
        default=0,
        readonly=True
    )

    # Display
    image = fields.Binary(string='Bundle Image')
    description = fields.Html(string='Description', translate=True)
    highlight_text = fields.Char(
        string='Highlight Text',
        default='Best Value',
        translate=True
    )

    # Statistics
    is_available = fields.Boolean(
        string='Available',
        compute='_compute_availability'
    )

    _sql_constraints = [
        ('unique_code', 'UNIQUE(code)', 'Bundle code must be unique'),
    ]

    @api.depends('line_ids.subtotal', 'pricing_type', 'fixed_price',
                 'discount_percent', 'discount_amount')
    def _compute_prices(self):
        for bundle in self:
            original = sum(bundle.line_ids.mapped('subtotal'))
            bundle.original_price = original

            if bundle.pricing_type == 'fixed':
                bundle.final_price = bundle.fixed_price
            elif bundle.pricing_type == 'discount_percent':
                bundle.final_price = original * (1 - bundle.discount_percent / 100)
            else:  # discount_amount
                bundle.final_price = max(0, original - bundle.discount_amount)

            bundle.savings = original - bundle.final_price
            bundle.savings_percent = (bundle.savings / original * 100) if original else 0

    @api.depends('date_start', 'date_end', 'total_available', 'sold_count',
                 'line_ids.product_id.qty_available')
    def _compute_availability(self):
        today = fields.Date.today()
        for bundle in self:
            available = True

            # Check dates
            if bundle.date_start and today < bundle.date_start:
                available = False
            if bundle.date_end and today > bundle.date_end:
                available = False

            # Check quantity limit
            if bundle.total_available > 0 and bundle.sold_count >= bundle.total_available:
                available = False

            # Check product stock
            for line in bundle.line_ids:
                if line.product_id.type == 'product':
                    if line.product_id.qty_available < line.quantity:
                        available = False
                        break

            bundle.is_available = available

    def get_bundle_info(self):
        """Get bundle information for API"""
        self.ensure_one()
        return {
            'id': self.id,
            'name': self.name,
            'code': self.code,
            'type': self.bundle_type,
            'original_price': self.original_price,
            'final_price': self.final_price,
            'savings': self.savings,
            'savings_percent': round(self.savings_percent, 1),
            'highlight_text': self.highlight_text,
            'is_available': self.is_available,
            'image_url': f'/web/image/product.bundle/{self.id}/image' if self.image else None,
            'products': [{
                'id': line.product_id.id,
                'name': line.product_id.name,
                'quantity': line.quantity,
                'price': line.product_id.lst_price,
                'image_url': f'/web/image/product.product/{line.product_id.id}/image_128',
            } for line in self.line_ids],
        }

    def add_to_cart(self, cart_session, quantity=1):
        """Add bundle to cart"""
        self.ensure_one()

        if not self.is_available:
            return {'error': 'Bundle is not available'}

        if self.max_quantity_per_order and quantity > self.max_quantity_per_order:
            return {'error': f'Maximum {self.max_quantity_per_order} bundles per order'}

        # Add each product in bundle
        for line in self.line_ids:
            cart_session.add_item(
                line.product_id.id,
                line.quantity * quantity,
                bundle_id=self.id
            )

        return {'success': True, 'message': f'Bundle "{self.name}" added to cart'}


class ProductBundleLine(models.Model):
    _name = 'product.bundle.line'
    _description = 'Product Bundle Line'
    _order = 'sequence, id'

    bundle_id = fields.Many2one(
        'product.bundle',
        string='Bundle',
        required=True,
        ondelete='cascade'
    )
    product_id = fields.Many2one(
        'product.product',
        string='Product',
        required=True,
        domain=[('sale_ok', '=', True)]
    )
    quantity = fields.Integer(
        string='Quantity',
        default=1,
        required=True
    )
    sequence = fields.Integer(default=10)

    # Computed
    unit_price = fields.Monetary(
        string='Unit Price',
        related='product_id.lst_price',
        currency_field='currency_id'
    )
    subtotal = fields.Monetary(
        string='Subtotal',
        compute='_compute_subtotal',
        currency_field='currency_id'
    )
    currency_id = fields.Many2one(
        'res.currency',
        related='bundle_id.currency_id'
    )

    @api.depends('product_id.lst_price', 'quantity')
    def _compute_subtotal(self):
        for line in self:
            line.subtotal = line.product_id.lst_price * line.quantity

    @api.constrains('quantity')
    def _check_quantity(self):
        for line in self:
            if line.quantity <= 0:
                raise ValidationError('Quantity must be greater than 0')
```

---

### Days 374-380: UI Components, Social Sharing & Testing

Due to document length, the remaining days (374-380) follow similar patterns with:

- **Day 374-375**: Bundle display components, recently viewed tracking
- **Day 376-377**: Stock indicator widgets, quantity selectors
- **Day 378-379**: Social sharing integration, product guides
- **Day 380**: Integration testing, documentation

---

## 6. Technical Specifications

### 6.1 API Endpoints

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/v1/cart` | GET | Public | Get cart contents |
| `/api/v1/cart/add` | POST | Public | Add item to cart |
| `/api/v1/cart/update` | POST | Public | Update item quantity |
| `/api/v1/cart/remove` | POST | Public | Remove item |
| `/api/v1/cart/clear` | POST | Public | Clear cart |
| `/api/v1/product/<id>/stock` | GET | Public | Get stock info |
| `/api/v1/product/<id>/notify-stock` | POST | Public | Subscribe to alerts |
| `/api/v1/bundles` | GET | Public | List bundles |
| `/api/v1/bundles/<id>/add-to-cart` | POST | Public | Add bundle to cart |

### 6.2 Performance Targets

| Metric | Target |
|--------|--------|
| Add to cart response | < 500ms |
| Mini cart update | < 300ms |
| Stock check | < 200ms |
| Bundle calculation | < 100ms |

---

## 7. Testing Requirements

### 7.1 Unit Test Coverage

| Area | Target |
|------|--------|
| Cart operations | > 90% |
| Bundle pricing | > 95% |
| Stock calculations | > 90% |

### 7.2 Integration Tests

- Cart add/update/remove flow
- Bundle to cart conversion
- Stock notification subscription
- Social sharing generation

---

## 8. Sign-off Checklist

### 8.1 Functional Requirements

- [ ] Quick add-to-cart works without page reload
- [ ] Mini cart displays and updates correctly
- [ ] Product bundles calculate pricing correctly
- [ ] Stock levels display accurately
- [ ] Back-in-stock notifications are sent
- [ ] Social sharing generates correct links
- [ ] Recently viewed products track correctly

### 8.2 Non-Functional Requirements

- [ ] Cart operations < 500ms
- [ ] Mobile responsive design
- [ ] Accessibility compliant

---

**Document End**

*Last Updated: 2025-01-15*
*Version: 1.0*
