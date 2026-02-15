# Milestone 66: Wishlist & Favorites

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P7-M66-v1.0 |
| **Milestone** | 66 - Wishlist & Favorites |
| **Phase** | Phase 7: B2C E-Commerce Core |
| **Days** | 401-410 |
| **Duration** | 10 Working Days |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Technical Architecture Team |
| **Last Updated** | 2025-01-15 |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Implement a comprehensive wishlist and favorites system enabling customers to save products for later, create multiple named wishlists, receive price drop alerts, share wishlists with friends/family, and seamlessly move items between wishlist and cart.**

### 1.2 Context

Milestone 66 focuses on customer engagement through wishlist functionality. This feature helps customers save products they are interested in, receive notifications when prices drop, organize products into multiple lists (e.g., "Birthday Gifts", "Weekly Groceries"), and share lists with others for gift-giving purposes.

### 1.3 Scope Summary

| Area | Included |
|------|----------|
| Multiple Wishlists | Create, rename, delete wishlists |
| Add/Remove Items | Quick add from product pages |
| Price Drop Alerts | Notifications on price changes |
| Wishlist Sharing | Share via link, email, social |
| Move to Cart | One-click or bulk move |
| Guest Wishlist | Local storage for guests |
| Wishlist Analytics | Track popular wishlisted items |
| Low Stock Alerts | Notify when wishlisted items low |

---

## 2. Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| O1 | Multiple wishlist support | Avg 2.5 wishlists per active user |
| O2 | Quick add-to-wishlist | Add operation < 300ms |
| O3 | Price drop notifications | Notification delivery > 98% |
| O4 | Wishlist sharing | Share rate > 5% of wishlists |
| O5 | Move to cart functionality | Conversion from wishlist > 25% |
| O6 | Guest wishlist persistence | 7-day local storage retention |
| O7 | Wishlist engagement | Wishlist users have 40% higher AOV |
| O8 | Mobile-optimized UI | Mobile usage > 60% |

---

## 3. Key Deliverables

| # | Deliverable | Owner | Format | Due Day |
|---|-------------|-------|--------|---------|
| D1 | Wishlist data model | Dev 1 | Python | Day 402 |
| D2 | Wishlist CRUD API | Dev 1 | Python | Day 403 |
| D3 | Price monitoring service | Dev 1 | Python | Day 405 |
| D4 | Price drop notification | Dev 2 | Python | Day 404 |
| D5 | Wishlist sharing service | Dev 2 | Python | Day 406 |
| D6 | Stock alert integration | Dev 2 | Python | Day 405 |
| D7 | Wishlist OWL component | Dev 3 | JavaScript | Day 404 |
| D8 | Quick add button UI | Dev 3 | OWL/QWeb | Day 403 |
| D9 | Wishlist page UI | Dev 3 | OWL/QWeb | Day 406 |
| D10 | Share modal component | Dev 3 | OWL | Day 407 |
| D11 | Guest wishlist (localStorage) | Dev 3 | JavaScript | Day 408 |
| D12 | Integration testing | All | Tests | Day 410 |

---

## 4. Requirement Traceability

### 4.1 RFP Requirements

| RFP Req ID | Description | Deliverable |
|------------|-------------|-------------|
| RFP-B2C-4.6.1-001 | Save for later functionality | D1, D2, D7 |
| RFP-B2C-4.6.1-002 | Multiple wishlists | D1, D2 |
| RFP-B2C-4.6.2-001 | Price drop notifications | D3, D4 |
| RFP-B2C-4.6.3-001 | Wishlist sharing | D5, D10 |
| RFP-B2C-4.6.4-001 | Move to cart | D2, D7 |

### 4.2 BRD Requirements

| BRD Req ID | Description | Deliverable |
|------------|-------------|-------------|
| BRD-FR-B2C-006 | Wishlist functionality | All |
| BRD-FR-B2C-006.1 | Create/manage wishlists | D1, D2, D9 |
| BRD-FR-B2C-006.2 | Price alerts | D3, D4 |
| BRD-FR-B2C-006.3 | Social sharing | D5, D10 |
| BRD-FR-B2C-006.4 | Guest experience | D11 |

---

## 5. Day-by-Day Development Plan

### Day 401-402: Wishlist Data Model & Core APIs

#### Dev 1 (Backend Lead) - Days 401-402

**Focus: Wishlist Data Model**

**Hour 1-4: Wishlist Model**

```python
# File: smart_dairy_ecommerce/models/wishlist.py

from odoo import models, fields, api
from odoo.exceptions import UserError, ValidationError
import uuid
import json
from datetime import datetime, timedelta

class Wishlist(models.Model):
    _name = 'customer.wishlist'
    _description = 'Customer Wishlist'
    _order = 'is_default desc, name'

    name = fields.Char(
        string='Wishlist Name',
        required=True,
        default='My Wishlist',
        translate=True
    )
    partner_id = fields.Many2one(
        'res.partner',
        string='Customer',
        required=True,
        ondelete='cascade',
        index=True
    )
    is_default = fields.Boolean(
        string='Default Wishlist',
        default=False
    )
    is_public = fields.Boolean(
        string='Public',
        default=False,
        help='Public wishlists can be shared via link'
    )

    # Sharing
    share_token = fields.Char(
        string='Share Token',
        default=lambda self: str(uuid.uuid4()),
        copy=False,
        index=True
    )
    share_url = fields.Char(
        string='Share URL',
        compute='_compute_share_url'
    )

    # Items
    item_ids = fields.One2many(
        'customer.wishlist.item',
        'wishlist_id',
        string='Items'
    )
    item_count = fields.Integer(
        string='Item Count',
        compute='_compute_item_count',
        store=True
    )
    total_value = fields.Monetary(
        string='Total Value',
        compute='_compute_total_value',
        currency_field='currency_id'
    )

    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.company.currency_id
    )

    # Settings
    notify_price_drop = fields.Boolean(
        string='Notify on Price Drop',
        default=True
    )
    notify_low_stock = fields.Boolean(
        string='Notify on Low Stock',
        default=False
    )
    notify_back_in_stock = fields.Boolean(
        string='Notify Back in Stock',
        default=True
    )

    # Timestamps
    last_modified = fields.Datetime(
        string='Last Modified',
        default=fields.Datetime.now
    )

    _sql_constraints = [
        ('unique_default_per_partner',
         'EXCLUDE (partner_id WITH =) WHERE (is_default = true)',
         'Only one default wishlist per customer'),
    ]

    @api.depends('item_ids')
    def _compute_item_count(self):
        for wishlist in self:
            wishlist.item_count = len(wishlist.item_ids)

    @api.depends('item_ids.current_price')
    def _compute_total_value(self):
        for wishlist in self:
            wishlist.total_value = sum(wishlist.item_ids.mapped('current_price'))

    def _compute_share_url(self):
        base_url = self.env['ir.config_parameter'].sudo().get_param('web.base.url')
        for wishlist in self:
            wishlist.share_url = f"{base_url}/wishlist/shared/{wishlist.share_token}"

    @api.model
    def get_or_create_default(self, partner_id):
        """Get or create default wishlist for partner"""
        wishlist = self.search([
            ('partner_id', '=', partner_id),
            ('is_default', '=', True)
        ], limit=1)

        if not wishlist:
            wishlist = self.create({
                'name': 'My Wishlist',
                'partner_id': partner_id,
                'is_default': True,
            })

        return wishlist

    def action_make_default(self):
        """Make this wishlist the default"""
        self.ensure_one()
        # Remove default from other wishlists
        self.search([
            ('partner_id', '=', self.partner_id.id),
            ('is_default', '=', True),
            ('id', '!=', self.id)
        ]).write({'is_default': False})

        self.is_default = True
        return True

    def action_regenerate_share_token(self):
        """Generate new share token"""
        self.ensure_one()
        self.share_token = str(uuid.uuid4())
        return True

    def add_item(self, product_id, notes=''):
        """Add product to wishlist"""
        self.ensure_one()

        product = self.env['product.product'].browse(product_id)
        if not product.exists():
            return {'error': 'Product not found'}

        # Check if already in wishlist
        existing = self.item_ids.filtered(lambda i: i.product_id.id == product_id)
        if existing:
            return {'message': 'Product already in wishlist', 'item_id': existing[0].id}

        item = self.env['customer.wishlist.item'].create({
            'wishlist_id': self.id,
            'product_id': product_id,
            'price_when_added': product.lst_price,
            'notes': notes,
        })

        self.last_modified = fields.Datetime.now()

        return {
            'success': True,
            'item_id': item.id,
            'message': f'{product.name} added to {self.name}'
        }

    def remove_item(self, product_id=None, item_id=None):
        """Remove item from wishlist"""
        self.ensure_one()

        if item_id:
            item = self.item_ids.filtered(lambda i: i.id == item_id)
        else:
            item = self.item_ids.filtered(lambda i: i.product_id.id == product_id)

        if item:
            product_name = item.product_id.name
            item.unlink()
            self.last_modified = fields.Datetime.now()
            return {'success': True, 'message': f'{product_name} removed from wishlist'}

        return {'error': 'Item not found in wishlist'}

    def move_to_cart(self, item_ids=None):
        """Move items to shopping cart"""
        self.ensure_one()

        items = self.item_ids
        if item_ids:
            items = items.filtered(lambda i: i.id in item_ids)

        cart = self.env['cart.session'].sudo().get_or_create_session(
            partner_id=self.partner_id.id
        )

        moved = 0
        errors = []

        for item in items:
            if item.product_id.stock_status == 'out_of_stock':
                errors.append(f'{item.product_id.name} is out of stock')
                continue

            result = cart.add_item(item.product_id.id, 1)
            if 'error' not in result:
                item.unlink()
                moved += 1
            else:
                errors.append(f'{item.product_id.name}: {result["error"]}')

        self.last_modified = fields.Datetime.now()

        return {
            'success': True,
            'moved': moved,
            'errors': errors,
            'cart_count': cart.item_count,
        }

    def get_wishlist_data(self):
        """Get wishlist data for API"""
        self.ensure_one()
        return {
            'id': self.id,
            'name': self.name,
            'is_default': self.is_default,
            'is_public': self.is_public,
            'share_url': self.share_url if self.is_public else None,
            'item_count': self.item_count,
            'total_value': self.total_value,
            'items': [item.get_item_data() for item in self.item_ids],
            'settings': {
                'notify_price_drop': self.notify_price_drop,
                'notify_low_stock': self.notify_low_stock,
                'notify_back_in_stock': self.notify_back_in_stock,
            },
            'last_modified': self.last_modified.isoformat() if self.last_modified else None,
        }


class WishlistItem(models.Model):
    _name = 'customer.wishlist.item'
    _description = 'Wishlist Item'
    _order = 'create_date desc'

    wishlist_id = fields.Many2one(
        'customer.wishlist',
        string='Wishlist',
        required=True,
        ondelete='cascade',
        index=True
    )
    partner_id = fields.Many2one(
        'res.partner',
        string='Customer',
        related='wishlist_id.partner_id',
        store=True
    )
    product_id = fields.Many2one(
        'product.product',
        string='Product',
        required=True,
        ondelete='cascade',
        index=True
    )

    # Price Tracking
    price_when_added = fields.Monetary(
        string='Price When Added',
        currency_field='currency_id'
    )
    current_price = fields.Monetary(
        string='Current Price',
        related='product_id.lst_price',
        currency_field='currency_id'
    )
    price_change = fields.Monetary(
        string='Price Change',
        compute='_compute_price_change',
        currency_field='currency_id'
    )
    price_change_percent = fields.Float(
        string='Price Change %',
        compute='_compute_price_change'
    )
    has_price_dropped = fields.Boolean(
        string='Price Dropped',
        compute='_compute_price_change'
    )

    # Stock Status
    stock_status = fields.Selection(
        related='product_id.stock_status',
        string='Stock Status'
    )
    stock_available = fields.Float(
        related='product_id.qty_available',
        string='Available Qty'
    )

    # Notes
    notes = fields.Text(string='Notes')
    priority = fields.Selection([
        ('0', 'Low'),
        ('1', 'Normal'),
        ('2', 'High'),
    ], string='Priority', default='1')

    currency_id = fields.Many2one(
        'res.currency',
        related='wishlist_id.currency_id'
    )

    # Notification tracking
    price_drop_notified = fields.Boolean(
        string='Price Drop Notified',
        default=False
    )
    price_drop_notified_at = fields.Datetime(
        string='Price Drop Notified At'
    )
    low_stock_notified = fields.Boolean(
        string='Low Stock Notified',
        default=False
    )

    _sql_constraints = [
        ('unique_product_wishlist',
         'UNIQUE(wishlist_id, product_id)',
         'Product already in this wishlist'),
    ]

    @api.depends('price_when_added', 'current_price')
    def _compute_price_change(self):
        for item in self:
            if item.price_when_added and item.price_when_added > 0:
                item.price_change = item.current_price - item.price_when_added
                item.price_change_percent = (item.price_change / item.price_when_added) * 100
                item.has_price_dropped = item.price_change < 0
            else:
                item.price_change = 0
                item.price_change_percent = 0
                item.has_price_dropped = False

    def get_item_data(self):
        """Get item data for API"""
        return {
            'id': self.id,
            'product_id': self.product_id.id,
            'product_name': self.product_id.name,
            'product_image': f'/web/image/product.product/{self.product_id.id}/image_256',
            'price_when_added': self.price_when_added,
            'current_price': self.current_price,
            'price_change': self.price_change,
            'price_change_percent': round(self.price_change_percent, 1),
            'has_price_dropped': self.has_price_dropped,
            'stock_status': self.stock_status,
            'stock_available': self.stock_available if self.product_id.show_stock_quantity else None,
            'notes': self.notes,
            'priority': self.priority,
            'added_at': self.create_date.isoformat(),
        }

    def action_move_to_cart(self):
        """Move this item to cart"""
        return self.wishlist_id.move_to_cart([self.id])

    def action_move_to_wishlist(self, target_wishlist_id):
        """Move item to another wishlist"""
        self.ensure_one()

        target = self.env['customer.wishlist'].browse(target_wishlist_id)
        if not target.exists() or target.partner_id.id != self.partner_id.id:
            return {'error': 'Target wishlist not found'}

        # Check if already in target
        if target.item_ids.filtered(lambda i: i.product_id.id == self.product_id.id):
            return {'error': 'Product already in target wishlist'}

        self.wishlist_id = target_wishlist_id
        return {'success': True, 'message': f'Moved to {target.name}'}
```

**Hour 5-8: Wishlist API Controller**

```python
# File: smart_dairy_ecommerce/controllers/wishlist_controller.py

from odoo import http
from odoo.http import request
import json

class WishlistController(http.Controller):

    def _get_partner_id(self):
        """Get current partner ID"""
        if request.env.user._is_public():
            return None
        return request.env.user.partner_id.id

    @http.route('/api/v1/wishlists', type='json', auth='user', methods=['GET'])
    def get_wishlists(self, **kwargs):
        """Get all wishlists for current user"""
        partner_id = self._get_partner_id()
        if not partner_id:
            return {'error': 'Authentication required', 'code': 401}

        wishlists = request.env['customer.wishlist'].sudo().search([
            ('partner_id', '=', partner_id)
        ])

        return {
            'success': True,
            'data': {
                'wishlists': [wl.get_wishlist_data() for wl in wishlists],
                'total_items': sum(wishlists.mapped('item_count')),
            }
        }

    @http.route('/api/v1/wishlists/<int:wishlist_id>', type='json', auth='user', methods=['GET'])
    def get_wishlist(self, wishlist_id, **kwargs):
        """Get specific wishlist"""
        partner_id = self._get_partner_id()
        wishlist = request.env['customer.wishlist'].sudo().browse(wishlist_id)

        if not wishlist.exists() or wishlist.partner_id.id != partner_id:
            return {'error': 'Wishlist not found', 'code': 404}

        return {
            'success': True,
            'data': wishlist.get_wishlist_data()
        }

    @http.route('/api/v1/wishlists', type='json', auth='user', methods=['POST'])
    def create_wishlist(self, **kwargs):
        """Create new wishlist"""
        partner_id = self._get_partner_id()
        if not partner_id:
            return {'error': 'Authentication required', 'code': 401}

        name = kwargs.get('name', 'My Wishlist')
        is_public = kwargs.get('is_public', False)

        wishlist = request.env['customer.wishlist'].sudo().create({
            'name': name,
            'partner_id': partner_id,
            'is_public': is_public,
        })

        return {
            'success': True,
            'data': wishlist.get_wishlist_data(),
            'message': f'Wishlist "{name}" created'
        }

    @http.route('/api/v1/wishlists/<int:wishlist_id>', type='json', auth='user', methods=['PUT'])
    def update_wishlist(self, wishlist_id, **kwargs):
        """Update wishlist settings"""
        partner_id = self._get_partner_id()
        wishlist = request.env['customer.wishlist'].sudo().browse(wishlist_id)

        if not wishlist.exists() or wishlist.partner_id.id != partner_id:
            return {'error': 'Wishlist not found', 'code': 404}

        update_data = {}
        if 'name' in kwargs:
            update_data['name'] = kwargs['name']
        if 'is_public' in kwargs:
            update_data['is_public'] = kwargs['is_public']
        if 'notify_price_drop' in kwargs:
            update_data['notify_price_drop'] = kwargs['notify_price_drop']
        if 'notify_low_stock' in kwargs:
            update_data['notify_low_stock'] = kwargs['notify_low_stock']
        if 'notify_back_in_stock' in kwargs:
            update_data['notify_back_in_stock'] = kwargs['notify_back_in_stock']

        if update_data:
            wishlist.write(update_data)

        return {
            'success': True,
            'data': wishlist.get_wishlist_data(),
            'message': 'Wishlist updated'
        }

    @http.route('/api/v1/wishlists/<int:wishlist_id>', type='json', auth='user', methods=['DELETE'])
    def delete_wishlist(self, wishlist_id, **kwargs):
        """Delete wishlist"""
        partner_id = self._get_partner_id()
        wishlist = request.env['customer.wishlist'].sudo().browse(wishlist_id)

        if not wishlist.exists() or wishlist.partner_id.id != partner_id:
            return {'error': 'Wishlist not found', 'code': 404}

        if wishlist.is_default:
            return {'error': 'Cannot delete default wishlist', 'code': 400}

        name = wishlist.name
        wishlist.unlink()

        return {
            'success': True,
            'message': f'Wishlist "{name}" deleted'
        }

    @http.route('/api/v1/wishlists/<int:wishlist_id>/items', type='json', auth='user', methods=['POST'])
    def add_to_wishlist(self, wishlist_id, **kwargs):
        """Add product to wishlist"""
        partner_id = self._get_partner_id()
        product_id = kwargs.get('product_id')
        notes = kwargs.get('notes', '')

        if not product_id:
            return {'error': 'product_id is required', 'code': 400}

        wishlist = request.env['customer.wishlist'].sudo().browse(wishlist_id)

        if not wishlist.exists() or wishlist.partner_id.id != partner_id:
            return {'error': 'Wishlist not found', 'code': 404}

        result = wishlist.add_item(product_id, notes)
        return result

    @http.route('/api/v1/wishlist/quick-add', type='json', auth='user', methods=['POST'])
    def quick_add_to_wishlist(self, **kwargs):
        """Quick add to default wishlist"""
        partner_id = self._get_partner_id()
        if not partner_id:
            return {'error': 'Authentication required', 'code': 401}

        product_id = kwargs.get('product_id')
        if not product_id:
            return {'error': 'product_id is required', 'code': 400}

        wishlist = request.env['customer.wishlist'].sudo().get_or_create_default(partner_id)
        result = wishlist.add_item(product_id)

        if 'success' in result:
            result['wishlist_count'] = wishlist.item_count

        return result

    @http.route('/api/v1/wishlists/<int:wishlist_id>/items/<int:item_id>', type='json', auth='user', methods=['DELETE'])
    def remove_from_wishlist(self, wishlist_id, item_id, **kwargs):
        """Remove item from wishlist"""
        partner_id = self._get_partner_id()
        wishlist = request.env['customer.wishlist'].sudo().browse(wishlist_id)

        if not wishlist.exists() or wishlist.partner_id.id != partner_id:
            return {'error': 'Wishlist not found', 'code': 404}

        result = wishlist.remove_item(item_id=item_id)
        return result

    @http.route('/api/v1/wishlists/<int:wishlist_id>/move-to-cart', type='json', auth='user', methods=['POST'])
    def move_to_cart(self, wishlist_id, **kwargs):
        """Move items to cart"""
        partner_id = self._get_partner_id()
        item_ids = kwargs.get('item_ids')  # None = all items

        wishlist = request.env['customer.wishlist'].sudo().browse(wishlist_id)

        if not wishlist.exists() or wishlist.partner_id.id != partner_id:
            return {'error': 'Wishlist not found', 'code': 404}

        result = wishlist.move_to_cart(item_ids)
        return result

    @http.route('/api/v1/wishlist/check/<int:product_id>', type='json', auth='user', methods=['GET'])
    def check_in_wishlist(self, product_id, **kwargs):
        """Check if product is in any wishlist"""
        partner_id = self._get_partner_id()
        if not partner_id:
            return {'success': True, 'data': {'in_wishlist': False}}

        item = request.env['customer.wishlist.item'].sudo().search([
            ('partner_id', '=', partner_id),
            ('product_id', '=', product_id)
        ], limit=1)

        return {
            'success': True,
            'data': {
                'in_wishlist': bool(item),
                'wishlist_id': item.wishlist_id.id if item else None,
                'item_id': item.id if item else None,
            }
        }

    @http.route('/wishlist/shared/<string:token>', type='http', auth='public', website=True)
    def view_shared_wishlist(self, token, **kwargs):
        """View shared wishlist"""
        wishlist = request.env['customer.wishlist'].sudo().search([
            ('share_token', '=', token),
            ('is_public', '=', True)
        ], limit=1)

        if not wishlist:
            return request.render('smart_dairy_ecommerce.wishlist_not_found')

        return request.render('smart_dairy_ecommerce.shared_wishlist', {
            'wishlist': wishlist,
        })

    @http.route('/api/v1/wishlists/<int:wishlist_id>/share', type='json', auth='user', methods=['POST'])
    def share_wishlist(self, wishlist_id, **kwargs):
        """Generate share link or send share email"""
        partner_id = self._get_partner_id()
        wishlist = request.env['customer.wishlist'].sudo().browse(wishlist_id)

        if not wishlist.exists() or wishlist.partner_id.id != partner_id:
            return {'error': 'Wishlist not found', 'code': 404}

        # Make public if not already
        if not wishlist.is_public:
            wishlist.is_public = True

        share_method = kwargs.get('method', 'link')
        recipient_email = kwargs.get('email')

        if share_method == 'email' and recipient_email:
            # Send email
            request.env['wishlist.share'].sudo().send_share_email(
                wishlist, recipient_email, kwargs.get('message', '')
            )
            return {
                'success': True,
                'message': f'Wishlist shared with {recipient_email}'
            }

        return {
            'success': True,
            'data': {
                'share_url': wishlist.share_url,
            }
        }
```

---

#### Dev 2 (Full-Stack) - Days 401-402

**Focus: Price Monitoring & Notification Service**

**Hour 1-4: Price Monitoring Service**

```python
# File: smart_dairy_ecommerce/models/price_monitor.py

from odoo import models, fields, api
from datetime import datetime, timedelta

class PriceHistory(models.Model):
    _name = 'product.price.history'
    _description = 'Product Price History'
    _order = 'recorded_at desc'

    product_id = fields.Many2one(
        'product.product',
        string='Product',
        required=True,
        ondelete='cascade',
        index=True
    )
    price = fields.Monetary(
        string='Price',
        required=True,
        currency_field='currency_id'
    )
    previous_price = fields.Monetary(
        string='Previous Price',
        currency_field='currency_id'
    )
    price_change = fields.Monetary(
        string='Change',
        compute='_compute_price_change',
        currency_field='currency_id'
    )
    change_percent = fields.Float(
        string='Change %',
        compute='_compute_price_change'
    )
    recorded_at = fields.Datetime(
        string='Recorded At',
        default=fields.Datetime.now
    )
    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.company.currency_id
    )

    @api.depends('price', 'previous_price')
    def _compute_price_change(self):
        for record in self:
            if record.previous_price and record.previous_price > 0:
                record.price_change = record.price - record.previous_price
                record.change_percent = (record.price_change / record.previous_price) * 100
            else:
                record.price_change = 0
                record.change_percent = 0


class PriceMonitorService(models.Model):
    _name = 'price.monitor.service'
    _description = 'Price Monitor Service'

    @api.model
    def record_price_change(self, product_id, new_price, old_price):
        """Record a price change"""
        if new_price != old_price:
            self.env['product.price.history'].create({
                'product_id': product_id,
                'price': new_price,
                'previous_price': old_price,
            })

            # Check for price drops and notify
            if new_price < old_price:
                self._check_and_notify_price_drop(product_id, new_price, old_price)

    @api.model
    def _check_and_notify_price_drop(self, product_id, new_price, old_price):
        """Check wishlists and send price drop notifications"""
        # Find all wishlist items for this product with price drop notification enabled
        items = self.env['customer.wishlist.item'].search([
            ('product_id', '=', product_id),
            ('wishlist_id.notify_price_drop', '=', True),
            ('price_drop_notified', '=', False),
        ])

        for item in items:
            # Only notify if dropped below the price when added
            if new_price < item.price_when_added:
                self.env['wishlist.notification'].send_price_drop_alert(item)
                item.write({
                    'price_drop_notified': True,
                    'price_drop_notified_at': fields.Datetime.now(),
                })

    @api.model
    def monitor_wishlisted_products_cron(self):
        """Cron: Monitor price changes for wishlisted products"""
        # Get all unique products in wishlists
        wishlisted_products = self.env['customer.wishlist.item'].search([]).mapped('product_id')

        for product in wishlisted_products:
            # Get last recorded price
            last_history = self.env['product.price.history'].search([
                ('product_id', '=', product.id)
            ], limit=1, order='recorded_at desc')

            last_price = last_history.price if last_history else product.lst_price

            if product.lst_price != last_price:
                self.record_price_change(product.id, product.lst_price, last_price)

        return True

    @api.model
    def check_low_stock_alerts_cron(self):
        """Cron: Check for low stock on wishlisted items"""
        # Find items where stock is low and notification enabled
        items = self.env['customer.wishlist.item'].search([
            ('wishlist_id.notify_low_stock', '=', True),
            ('low_stock_notified', '=', False),
            ('product_id.stock_status', '=', 'low_stock'),
        ])

        for item in items:
            self.env['wishlist.notification'].send_low_stock_alert(item)
            item.low_stock_notified = True

        return True

    @api.model
    def check_back_in_stock_alerts_cron(self):
        """Cron: Check for back in stock items"""
        # Find items that were out of stock but now available
        items = self.env['customer.wishlist.item'].search([
            ('wishlist_id.notify_back_in_stock', '=', True),
            ('product_id.stock_status', 'in', ['in_stock', 'low_stock']),
        ]).filtered(lambda i: i.product_id.qty_available > 0)

        # Check against notification history
        for item in items:
            # Check if we already notified for this restock
            if not self._was_back_in_stock_notified(item):
                self.env['wishlist.notification'].send_back_in_stock_alert(item)

        return True

    def _was_back_in_stock_notified(self, item):
        """Check if back in stock notification was already sent"""
        last_notification = self.env['wishlist.notification.log'].search([
            ('item_id', '=', item.id),
            ('notification_type', '=', 'back_in_stock'),
        ], limit=1, order='sent_at desc')

        if not last_notification:
            return False

        # If notified in last 7 days, don't notify again
        return last_notification.sent_at > datetime.now() - timedelta(days=7)


class ProductProduct(models.Model):
    _inherit = 'product.product'

    def write(self, vals):
        """Override to track price changes"""
        if 'lst_price' in vals:
            for product in self:
                old_price = product.lst_price
                new_price = vals['lst_price']
                if old_price != new_price:
                    self.env['price.monitor.service'].record_price_change(
                        product.id, new_price, old_price
                    )
        return super().write(vals)
```

**Hour 5-8: Wishlist Notification Service**

```python
# File: smart_dairy_ecommerce/models/wishlist_notification.py

from odoo import models, fields, api

class WishlistNotification(models.Model):
    _name = 'wishlist.notification'
    _description = 'Wishlist Notification Service'

    @api.model
    def send_price_drop_alert(self, item):
        """Send price drop notification"""
        partner = item.partner_id
        product = item.product_id

        savings = item.price_when_added - item.current_price
        savings_percent = abs(item.price_change_percent)

        # Email
        if partner.email:
            template = self.env.ref(
                'smart_dairy_ecommerce.email_wishlist_price_drop',
                raise_if_not_found=False
            )
            if template:
                template.with_context(
                    savings=savings,
                    savings_percent=savings_percent,
                ).send_mail(item.id, force_send=True)

        # Push notification
        self._send_push_notification(
            partner.id,
            f'Price Drop Alert!',
            f'{product.name} dropped from ৳{item.price_when_added} to ৳{item.current_price}',
            {'type': 'price_drop', 'product_id': product.id}
        )

        # Log notification
        self._log_notification(item.id, 'price_drop', partner.id)

    @api.model
    def send_low_stock_alert(self, item):
        """Send low stock notification"""
        partner = item.partner_id
        product = item.product_id

        # Email
        if partner.email:
            template = self.env.ref(
                'smart_dairy_ecommerce.email_wishlist_low_stock',
                raise_if_not_found=False
            )
            if template:
                template.send_mail(item.id, force_send=True)

        # Push notification
        self._send_push_notification(
            partner.id,
            f'Low Stock Alert!',
            f'{product.name} is running low. Only {int(product.qty_available)} left!',
            {'type': 'low_stock', 'product_id': product.id}
        )

        self._log_notification(item.id, 'low_stock', partner.id)

    @api.model
    def send_back_in_stock_alert(self, item):
        """Send back in stock notification"""
        partner = item.partner_id
        product = item.product_id

        # Email
        if partner.email:
            template = self.env.ref(
                'smart_dairy_ecommerce.email_wishlist_back_in_stock',
                raise_if_not_found=False
            )
            if template:
                template.send_mail(item.id, force_send=True)

        # Push notification
        self._send_push_notification(
            partner.id,
            f'Back in Stock!',
            f'{product.name} is back in stock. Get it before it sells out!',
            {'type': 'back_in_stock', 'product_id': product.id}
        )

        self._log_notification(item.id, 'back_in_stock', partner.id)

    def _send_push_notification(self, partner_id, title, body, data=None):
        """Send push notification via web push"""
        subscriptions = self.env['push.subscription'].search([
            ('partner_id', '=', partner_id),
            ('active', '=', True)
        ])

        for sub in subscriptions:
            self.env['order.notification']._send_web_push(sub, {
                'title': title,
                'body': body,
                'data': data or {},
                'icon': '/smart_dairy_ecommerce/static/img/notification-icon.png',
            })

    def _log_notification(self, item_id, notification_type, partner_id):
        """Log notification for tracking"""
        self.env['wishlist.notification.log'].create({
            'item_id': item_id,
            'notification_type': notification_type,
            'partner_id': partner_id,
        })


class WishlistNotificationLog(models.Model):
    _name = 'wishlist.notification.log'
    _description = 'Wishlist Notification Log'
    _order = 'sent_at desc'

    item_id = fields.Many2one(
        'customer.wishlist.item',
        string='Wishlist Item',
        ondelete='cascade'
    )
    notification_type = fields.Selection([
        ('price_drop', 'Price Drop'),
        ('low_stock', 'Low Stock'),
        ('back_in_stock', 'Back in Stock'),
    ], string='Type')
    partner_id = fields.Many2one(
        'res.partner',
        string='Recipient'
    )
    sent_at = fields.Datetime(
        string='Sent At',
        default=fields.Datetime.now
    )
    channel = fields.Selection([
        ('email', 'Email'),
        ('push', 'Push'),
        ('sms', 'SMS'),
    ], string='Channel', default='email')


class WishlistShare(models.Model):
    _name = 'wishlist.share'
    _description = 'Wishlist Share Service'

    @api.model
    def send_share_email(self, wishlist, recipient_email, message=''):
        """Send wishlist share email"""
        template = self.env.ref(
            'smart_dairy_ecommerce.email_wishlist_share',
            raise_if_not_found=False
        )
        if template:
            template.with_context(
                recipient_email=recipient_email,
                personal_message=message,
            ).send_mail(wishlist.id, force_send=True, email_values={
                'email_to': recipient_email,
            })

        # Log share
        self.env['wishlist.share.log'].create({
            'wishlist_id': wishlist.id,
            'shared_with': recipient_email,
            'message': message,
        })


class WishlistShareLog(models.Model):
    _name = 'wishlist.share.log'
    _description = 'Wishlist Share Log'
    _order = 'create_date desc'

    wishlist_id = fields.Many2one(
        'customer.wishlist',
        string='Wishlist',
        ondelete='cascade'
    )
    shared_with = fields.Char(string='Shared With')
    message = fields.Text(string='Message')
    viewed = fields.Boolean(string='Viewed', default=False)
    viewed_at = fields.Datetime(string='Viewed At')
```

---

#### Dev 3 (Frontend Lead) - Days 401-402

**Focus: Wishlist UI Components**

**Hour 1-4: Add to Wishlist Button Component**

```javascript
/** @odoo-module **/
// File: smart_dairy_ecommerce/static/src/js/wishlist/add_to_wishlist.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class AddToWishlistButton extends Component {
    static template = "smart_dairy_ecommerce.AddToWishlistButton";
    static props = {
        productId: { type: Number, required: true },
        size: { type: String, optional: true },
        showLabel: { type: Boolean, optional: true },
    };

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            inWishlist: false,
            wishlistId: null,
            itemId: null,
            loading: false,
            showWishlistPicker: false,
            wishlists: [],
        });

        onWillStart(async () => {
            await this.checkWishlistStatus();
        });
    }

    get buttonClass() {
        const size = this.props.size || 'md';
        const sizeClass = {
            sm: 'btn-sm',
            md: '',
            lg: 'btn-lg',
        }[size];

        return `btn ${this.state.inWishlist ? 'btn-danger' : 'btn-outline-danger'} ${sizeClass}`;
    }

    get iconClass() {
        return this.state.inWishlist ? 'fa fa-heart' : 'fa fa-heart-o';
    }

    async checkWishlistStatus() {
        try {
            const result = await this.rpc(`/api/v1/wishlist/check/${this.props.productId}`, {});
            if (result.success) {
                this.state.inWishlist = result.data.in_wishlist;
                this.state.wishlistId = result.data.wishlist_id;
                this.state.itemId = result.data.item_id;
            }
        } catch (error) {
            // Silently fail for guests
        }
    }

    async toggleWishlist() {
        if (this.state.loading) return;

        this.state.loading = true;

        try {
            if (this.state.inWishlist) {
                // Remove from wishlist
                await this.removeFromWishlist();
            } else {
                // Add to default wishlist (or show picker)
                await this.addToWishlist();
            }
        } finally {
            this.state.loading = false;
        }
    }

    async addToWishlist(wishlistId = null) {
        try {
            const endpoint = wishlistId
                ? `/api/v1/wishlists/${wishlistId}/items`
                : '/api/v1/wishlist/quick-add';

            const result = await this.rpc(endpoint, {
                product_id: this.props.productId,
            });

            if (result.success || result.item_id) {
                this.state.inWishlist = true;
                this.state.itemId = result.item_id;
                this.notification.add(result.message || 'Added to wishlist', {
                    type: 'success',
                });

                // Dispatch event for wishlist counter
                document.dispatchEvent(new CustomEvent('wishlist:updated', {
                    detail: { count: result.wishlist_count }
                }));
            } else if (result.code === 401) {
                // Not logged in - redirect to login
                window.location.href = `/web/login?redirect=${encodeURIComponent(window.location.href)}`;
            } else {
                this.notification.add(result.error || result.message, {
                    type: 'warning',
                });
            }
        } catch (error) {
            this.notification.add('Failed to add to wishlist', { type: 'danger' });
        }
    }

    async removeFromWishlist() {
        if (!this.state.wishlistId || !this.state.itemId) return;

        try {
            const result = await this.rpc(
                `/api/v1/wishlists/${this.state.wishlistId}/items/${this.state.itemId}`,
                {},
                { method: 'DELETE' }
            );

            if (result.success) {
                this.state.inWishlist = false;
                this.state.itemId = null;
                this.notification.add(result.message || 'Removed from wishlist', {
                    type: 'info',
                });

                document.dispatchEvent(new CustomEvent('wishlist:updated'));
            }
        } catch (error) {
            this.notification.add('Failed to remove from wishlist', { type: 'danger' });
        }
    }

    async loadWishlists() {
        try {
            const result = await this.rpc('/api/v1/wishlists', {});
            if (result.success) {
                this.state.wishlists = result.data.wishlists;
            }
        } catch (error) {
            console.error('Failed to load wishlists:', error);
        }
    }

    async showWishlistPicker() {
        await this.loadWishlists();
        this.state.showWishlistPicker = true;
    }

    hideWishlistPicker() {
        this.state.showWishlistPicker = false;
    }

    async selectWishlist(wishlistId) {
        this.hideWishlistPicker();
        await this.addToWishlist(wishlistId);
    }
}
```

**Add to Wishlist QWeb Template**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- File: smart_dairy_ecommerce/static/src/xml/add_to_wishlist.xml -->

<templates xml:space="preserve">
    <t t-name="smart_dairy_ecommerce.AddToWishlistButton">
        <div class="sd-wishlist-btn-container d-inline-block position-relative">
            <!-- Main Button -->
            <button t-att-class="buttonClass"
                    t-on-click="toggleWishlist"
                    t-att-disabled="state.loading"
                    t-att-title="state.inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist'">
                <t t-if="state.loading">
                    <i class="fa fa-spinner fa-spin"/>
                </t>
                <t t-else="">
                    <i t-att-class="iconClass"/>
                </t>
                <t t-if="props.showLabel">
                    <span class="ms-1">
                        <t t-if="state.inWishlist">In Wishlist</t>
                        <t t-else="">Add to Wishlist</t>
                    </span>
                </t>
            </button>

            <!-- Wishlist Picker Dropdown -->
            <div t-if="state.showWishlistPicker"
                 class="sd-wishlist-picker dropdown-menu show position-absolute">
                <h6 class="dropdown-header">Add to Wishlist</h6>
                <t t-foreach="state.wishlists" t-as="wishlist" t-key="wishlist.id">
                    <button class="dropdown-item d-flex justify-content-between align-items-center"
                            t-on-click="() => this.selectWishlist(wishlist.id)">
                        <span>
                            <t t-esc="wishlist.name"/>
                            <t t-if="wishlist.is_default">
                                <span class="badge bg-primary ms-1">Default</span>
                            </t>
                        </span>
                        <span class="badge bg-secondary">
                            <t t-esc="wishlist.item_count"/>
                        </span>
                    </button>
                </t>
                <div class="dropdown-divider"/>
                <button class="dropdown-item text-primary"
                        t-on-click="() => this.env.bus.trigger('create-wishlist')">
                    <i class="fa fa-plus me-2"/>
                    Create New Wishlist
                </button>
            </div>

            <!-- Backdrop for picker -->
            <div t-if="state.showWishlistPicker"
                 class="sd-wishlist-backdrop"
                 t-on-click="hideWishlistPicker"/>
        </div>
    </t>
</templates>
```

**Hour 5-8: Wishlist Page Component**

```javascript
/** @odoo-module **/
// File: smart_dairy_ecommerce/static/src/js/wishlist/wishlist_page.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class WishlistPage extends Component {
    static template = "smart_dairy_ecommerce.WishlistPage";

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            loading: true,
            wishlists: [],
            activeWishlist: null,
            showCreateModal: false,
            showShareModal: false,
            newWishlistName: '',
            selectedItems: new Set(),
            selectAll: false,
        });

        onWillStart(async () => {
            await this.loadWishlists();
        });
    }

    async loadWishlists() {
        try {
            this.state.loading = true;
            const result = await this.rpc('/api/v1/wishlists', {});

            if (result.success) {
                this.state.wishlists = result.data.wishlists;
                // Set active wishlist to default or first
                if (this.state.wishlists.length > 0) {
                    this.state.activeWishlist = this.state.wishlists.find(w => w.is_default)
                        || this.state.wishlists[0];
                }
            }
        } catch (error) {
            this.notification.add('Failed to load wishlists', { type: 'danger' });
        } finally {
            this.state.loading = false;
        }
    }

    selectWishlist(wishlist) {
        this.state.activeWishlist = wishlist;
        this.state.selectedItems.clear();
        this.state.selectAll = false;
    }

    toggleItemSelection(itemId) {
        if (this.state.selectedItems.has(itemId)) {
            this.state.selectedItems.delete(itemId);
        } else {
            this.state.selectedItems.add(itemId);
        }
        this.updateSelectAll();
    }

    toggleSelectAll() {
        this.state.selectAll = !this.state.selectAll;
        if (this.state.selectAll) {
            this.state.activeWishlist.items.forEach(item => {
                this.state.selectedItems.add(item.id);
            });
        } else {
            this.state.selectedItems.clear();
        }
    }

    updateSelectAll() {
        const totalItems = this.state.activeWishlist?.items.length || 0;
        this.state.selectAll = totalItems > 0 && this.state.selectedItems.size === totalItems;
    }

    isItemSelected(itemId) {
        return this.state.selectedItems.has(itemId);
    }

    async removeItem(itemId) {
        const wishlist = this.state.activeWishlist;
        try {
            const result = await this.rpc(
                `/api/v1/wishlists/${wishlist.id}/items/${itemId}`,
                {},
                { method: 'DELETE' }
            );

            if (result.success) {
                // Remove from local state
                wishlist.items = wishlist.items.filter(i => i.id !== itemId);
                wishlist.item_count = wishlist.items.length;
                this.state.selectedItems.delete(itemId);
                this.notification.add(result.message, { type: 'success' });
            }
        } catch (error) {
            this.notification.add('Failed to remove item', { type: 'danger' });
        }
    }

    async moveToCart(itemIds = null) {
        const wishlist = this.state.activeWishlist;
        const ids = itemIds || Array.from(this.state.selectedItems);

        if (ids.length === 0) {
            this.notification.add('Please select items to move', { type: 'warning' });
            return;
        }

        try {
            const result = await this.rpc(`/api/v1/wishlists/${wishlist.id}/move-to-cart`, {
                item_ids: ids,
            });

            if (result.success) {
                // Remove moved items from local state
                wishlist.items = wishlist.items.filter(i => !ids.includes(i.id));
                wishlist.item_count = wishlist.items.length;
                this.state.selectedItems.clear();

                this.notification.add(
                    `${result.moved} item(s) moved to cart`,
                    { type: 'success' }
                );

                if (result.errors.length > 0) {
                    result.errors.forEach(err => {
                        this.notification.add(err, { type: 'warning' });
                    });
                }

                // Update cart counter
                document.dispatchEvent(new CustomEvent('cart:updated', {
                    detail: { count: result.cart_count }
                }));
            }
        } catch (error) {
            this.notification.add('Failed to move items to cart', { type: 'danger' });
        }
    }

    async moveAllToCart() {
        const itemIds = this.state.activeWishlist.items.map(i => i.id);
        await this.moveToCart(itemIds);
    }

    openCreateModal() {
        this.state.showCreateModal = true;
        this.state.newWishlistName = '';
    }

    closeCreateModal() {
        this.state.showCreateModal = false;
    }

    async createWishlist() {
        if (!this.state.newWishlistName.trim()) {
            this.notification.add('Please enter a wishlist name', { type: 'warning' });
            return;
        }

        try {
            const result = await this.rpc('/api/v1/wishlists', {
                name: this.state.newWishlistName,
            });

            if (result.success) {
                this.state.wishlists.push(result.data);
                this.state.activeWishlist = result.data;
                this.closeCreateModal();
                this.notification.add(result.message, { type: 'success' });
            }
        } catch (error) {
            this.notification.add('Failed to create wishlist', { type: 'danger' });
        }
    }

    async deleteWishlist(wishlistId) {
        if (!confirm('Are you sure you want to delete this wishlist?')) return;

        try {
            const result = await this.rpc(
                `/api/v1/wishlists/${wishlistId}`,
                {},
                { method: 'DELETE' }
            );

            if (result.success) {
                this.state.wishlists = this.state.wishlists.filter(w => w.id !== wishlistId);
                if (this.state.activeWishlist?.id === wishlistId) {
                    this.state.activeWishlist = this.state.wishlists[0] || null;
                }
                this.notification.add(result.message, { type: 'success' });
            } else {
                this.notification.add(result.error, { type: 'warning' });
            }
        } catch (error) {
            this.notification.add('Failed to delete wishlist', { type: 'danger' });
        }
    }

    openShareModal() {
        this.state.showShareModal = true;
    }

    closeShareModal() {
        this.state.showShareModal = false;
    }

    formatCurrency(amount) {
        return `৳${amount.toLocaleString('en-BD')}`;
    }

    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-BD', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        });
    }

    getPriceChangeClass(item) {
        if (item.has_price_dropped) return 'text-success';
        if (item.price_change > 0) return 'text-danger';
        return '';
    }
}
```

---

### Days 403-410: Share Modal, Guest Wishlist & Integration

The remaining days continue with similar implementation patterns:

- **Day 403-404**: Share modal UI, email sharing, social sharing
- **Day 405-406**: Guest wishlist with localStorage, wishlist sync on login
- **Day 407-408**: Wishlist analytics, popular items tracking
- **Day 409-410**: Integration testing, performance optimization

**Guest Wishlist Service (localStorage)**

```javascript
/** @odoo-module **/
// File: smart_dairy_ecommerce/static/src/js/wishlist/guest_wishlist.js

const STORAGE_KEY = 'sd_guest_wishlist';
const EXPIRY_DAYS = 7;

export class GuestWishlistService {
    constructor() {
        this.cleanExpired();
    }

    getWishlist() {
        try {
            const data = localStorage.getItem(STORAGE_KEY);
            if (!data) return { items: [], created_at: new Date().toISOString() };

            const wishlist = JSON.parse(data);

            // Check expiry
            const createdAt = new Date(wishlist.created_at);
            const expiryDate = new Date(createdAt.getTime() + EXPIRY_DAYS * 24 * 60 * 60 * 1000);

            if (new Date() > expiryDate) {
                this.clearWishlist();
                return { items: [], created_at: new Date().toISOString() };
            }

            return wishlist;
        } catch (error) {
            return { items: [], created_at: new Date().toISOString() };
        }
    }

    saveWishlist(wishlist) {
        try {
            wishlist.updated_at = new Date().toISOString();
            localStorage.setItem(STORAGE_KEY, JSON.stringify(wishlist));
        } catch (error) {
            console.error('Failed to save guest wishlist:', error);
        }
    }

    addItem(product) {
        const wishlist = this.getWishlist();

        // Check if already exists
        if (wishlist.items.some(i => i.product_id === product.id)) {
            return { message: 'Product already in wishlist' };
        }

        wishlist.items.push({
            product_id: product.id,
            product_name: product.name,
            product_image: product.image_url,
            price_when_added: product.price,
            added_at: new Date().toISOString(),
        });

        this.saveWishlist(wishlist);

        return {
            success: true,
            item_count: wishlist.items.length,
            message: `${product.name} added to wishlist`
        };
    }

    removeItem(productId) {
        const wishlist = this.getWishlist();
        const item = wishlist.items.find(i => i.product_id === productId);

        if (!item) {
            return { error: 'Item not found' };
        }

        wishlist.items = wishlist.items.filter(i => i.product_id !== productId);
        this.saveWishlist(wishlist);

        return {
            success: true,
            item_count: wishlist.items.length,
            message: 'Removed from wishlist'
        };
    }

    isInWishlist(productId) {
        const wishlist = this.getWishlist();
        return wishlist.items.some(i => i.product_id === productId);
    }

    getItemCount() {
        return this.getWishlist().items.length;
    }

    clearWishlist() {
        localStorage.removeItem(STORAGE_KEY);
    }

    cleanExpired() {
        // Clean up expired guest wishlist
        const wishlist = this.getWishlist();
        if (wishlist.items.length === 0) {
            this.clearWishlist();
        }
    }

    async syncWithServer(rpc) {
        // Sync guest wishlist with server after login
        const wishlist = this.getWishlist();

        if (wishlist.items.length === 0) return;

        try {
            const result = await rpc('/api/v1/wishlist/sync-guest', {
                items: wishlist.items.map(i => ({
                    product_id: i.product_id,
                    price_when_added: i.price_when_added,
                }))
            });

            if (result.success) {
                this.clearWishlist();
            }

            return result;
        } catch (error) {
            console.error('Failed to sync guest wishlist:', error);
            return { error: 'Sync failed' };
        }
    }
}

// Export singleton
export const guestWishlist = new GuestWishlistService();
```

---

## 6. Technical Specifications

### 6.1 API Endpoints

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/v1/wishlists` | GET | User | List all wishlists |
| `/api/v1/wishlists` | POST | User | Create wishlist |
| `/api/v1/wishlists/<id>` | GET | User | Get wishlist details |
| `/api/v1/wishlists/<id>` | PUT | User | Update wishlist |
| `/api/v1/wishlists/<id>` | DELETE | User | Delete wishlist |
| `/api/v1/wishlists/<id>/items` | POST | User | Add item |
| `/api/v1/wishlists/<id>/items/<item_id>` | DELETE | User | Remove item |
| `/api/v1/wishlists/<id>/move-to-cart` | POST | User | Move to cart |
| `/api/v1/wishlists/<id>/share` | POST | User | Share wishlist |
| `/api/v1/wishlist/quick-add` | POST | User | Quick add to default |
| `/api/v1/wishlist/check/<product_id>` | GET | User | Check product status |
| `/api/v1/wishlist/sync-guest` | POST | User | Sync guest wishlist |
| `/wishlist/shared/<token>` | GET | Public | View shared wishlist |

### 6.2 Database Schema

| Table | Description |
|-------|-------------|
| `customer.wishlist` | Wishlist records |
| `customer.wishlist.item` | Wishlist items |
| `product.price.history` | Price change history |
| `wishlist.notification.log` | Notification audit |
| `wishlist.share.log` | Share history |

### 6.3 Performance Targets

| Metric | Target |
|--------|--------|
| Add to wishlist | < 300ms |
| Load wishlist | < 500ms |
| Price check (bulk) | < 200ms |
| Share URL generation | < 100ms |

---

## 7. Testing Requirements

### 7.1 Unit Tests

```python
# File: smart_dairy_ecommerce/tests/test_wishlist.py

from odoo.tests import TransactionCase, tagged

@tagged('post_install', '-at_install')
class TestWishlist(TransactionCase):

    def setUp(self):
        super().setUp()
        self.partner = self.env['res.partner'].create({
            'name': 'Test Customer',
            'email': 'test@example.com',
        })
        self.product = self.env['product.product'].create({
            'name': 'Test Product',
            'list_price': 100,
        })

    def test_create_default_wishlist(self):
        """Test default wishlist creation"""
        wishlist = self.env['customer.wishlist'].get_or_create_default(
            self.partner.id
        )
        self.assertTrue(wishlist.is_default)
        self.assertEqual(wishlist.partner_id.id, self.partner.id)

    def test_add_item_to_wishlist(self):
        """Test adding item to wishlist"""
        wishlist = self.env['customer.wishlist'].get_or_create_default(
            self.partner.id
        )
        result = wishlist.add_item(self.product.id)

        self.assertTrue(result.get('success') or result.get('item_id'))
        self.assertEqual(wishlist.item_count, 1)

    def test_duplicate_item_prevention(self):
        """Test that duplicate items are prevented"""
        wishlist = self.env['customer.wishlist'].get_or_create_default(
            self.partner.id
        )
        wishlist.add_item(self.product.id)
        result = wishlist.add_item(self.product.id)

        self.assertIn('already', result.get('message', '').lower())
        self.assertEqual(wishlist.item_count, 1)

    def test_move_to_cart(self):
        """Test moving items to cart"""
        wishlist = self.env['customer.wishlist'].get_or_create_default(
            self.partner.id
        )
        wishlist.add_item(self.product.id)

        result = wishlist.move_to_cart()

        self.assertEqual(result.get('moved'), 1)
        self.assertEqual(wishlist.item_count, 0)

    def test_price_drop_tracking(self):
        """Test price drop detection"""
        wishlist = self.env['customer.wishlist'].get_or_create_default(
            self.partner.id
        )
        wishlist.add_item(self.product.id)

        item = wishlist.item_ids[0]
        original_price = item.price_when_added

        # Simulate price drop
        self.product.lst_price = 80

        self.assertTrue(item.has_price_dropped)
        self.assertEqual(item.price_change, -20)
```

### 7.2 Integration Tests

| Test Case | Description |
|-----------|-------------|
| Wishlist CRUD | Full create/read/update/delete flow |
| Cross-wishlist move | Move item between wishlists |
| Share and view | Share wishlist and view via link |
| Price notifications | Test price drop alert trigger |
| Guest sync | Test guest wishlist sync on login |

---

## 8. Sign-off Checklist

### 8.1 Functional Requirements

- [ ] Multiple wishlists can be created
- [ ] Items can be added/removed from wishlists
- [ ] Default wishlist auto-created
- [ ] Price drop alerts sent correctly
- [ ] Wishlist sharing via link works
- [ ] Wishlist sharing via email works
- [ ] Move to cart works (single/bulk)
- [ ] Guest wishlist persists in localStorage
- [ ] Guest wishlist syncs on login
- [ ] Low stock alerts sent

### 8.2 Non-Functional Requirements

- [ ] Add to wishlist < 300ms
- [ ] Mobile responsive design
- [ ] Accessibility compliant
- [ ] Works offline (guest mode)

---

**Document End**

*Last Updated: 2025-01-15*
*Version: 1.0*
