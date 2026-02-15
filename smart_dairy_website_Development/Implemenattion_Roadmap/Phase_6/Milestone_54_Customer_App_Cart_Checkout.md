# Milestone 54: Customer App — Cart & Checkout

## Smart Dairy Digital Smart Portal + ERP — Phase 6: Mobile App Foundation

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 54 of 150 (4 of 10 in Phase 6)                                         |
| **Title**        | Customer App — Cart & Checkout                                         |
| **Phase**        | Phase 6 — Mobile App Foundation                                        |
| **Days**         | Days 281-290 (10 working days)                                         |
| **Duration**     | 10 working days                                                        |
| **Version**      | 1.0                                                                    |
| **Status**       | Draft                                                                  |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack), Dev 3 (Mobile Lead)          |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Implement a complete shopping cart and checkout experience with Bangladesh payment gateway integrations (bKash, Nagad, Rocket, SSLCommerz), address management with GPS, delivery slot selection, and real-time inventory validation.

### 1.2 Objectives

1. Create cart CRUD API with real-time sync
2. Implement cart state management with persistence
3. Build address management with GPS autocomplete
4. Create delivery slot selection system
5. Integrate bKash payment gateway
6. Integrate Nagad payment gateway
7. Integrate Rocket payment gateway
8. Integrate SSLCommerz for card payments
9. Implement order creation and confirmation
10. Build invoice generation and display

### 1.3 Key Deliverables

| Deliverable                     | Owner  | Format            | Due Day |
| ------------------------------- | ------ | ----------------- | ------- |
| Cart API (CRUD)                 | Dev 1  | REST endpoints    | 281     |
| Cart Validation Logic           | Dev 1  | Python service    | 282     |
| Address API                     | Dev 1  | REST endpoints    | 283     |
| Delivery Slots API              | Dev 1  | REST endpoint     | 284     |
| Checkout API                    | Dev 1  | REST endpoint     | 285     |
| bKash Integration               | Dev 2  | Payment module    | 286     |
| Nagad Integration               | Dev 2  | Payment module    | 287     |
| Rocket & SSLCommerz             | Dev 2  | Payment modules   | 288     |
| Cart Screen UI                  | Dev 3  | Flutter widget    | 282     |
| Address Management UI           | Dev 3  | Flutter screens   | 283     |
| Checkout Flow UI                | Dev 3  | Flutter screens   | 285     |
| Payment Selection UI            | Dev 3  | Flutter widget    | 286     |
| Order Confirmation              | Dev 3  | Flutter screen    | 289     |
| Checkout E2E Tests              | Dev 2  | Test suite        | 290     |

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference        |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------- |
| RFP-MOB-004  | RFP    | Shopping cart and checkout               | 281-290 | Complete milestone    |
| RFP-MOB-012  | RFP    | Bangladesh payment integration           | 286-288 | Payment gateways      |
| SRS-MOB-007  | SRS    | bKash tokenized checkout                 | 286     | bKash integration     |
| BRD-MOB-002  | BRD    | 1,000 orders/day processing              | 285     | Checkout API          |

---

## 3. Day-by-Day Breakdown

### Day 281 — Cart API Implementation

**Objective:** Create comprehensive cart API with CRUD operations and real-time sync.

#### Dev 1 — Backend Lead (8h)

**Task 1: Cart API Controller (6h)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/cart_controller.py
from odoo import http
from odoo.http import request
from .base_controller import MobileAPIController, api_response, jwt_required
import logging

_logger = logging.getLogger(__name__)


class CartController(MobileAPIController):
    """Shopping Cart API endpoints"""

    @http.route('/api/v1/cart', type='json', auth='none', methods=['GET'], csrf=False)
    @jwt_required
    def get_cart(self, current_user=None, **kwargs):
        """
        Get current user's cart.

        Response:
            {
                "success": true,
                "data": {
                    "id": 123,
                    "items": [...],
                    "subtotal": 500.00,
                    "delivery_fee": 50.00,
                    "discount": 25.00,
                    "total": 525.00,
                    "item_count": 5,
                    "currency": "BDT"
                }
            }
        """
        cart = self._get_or_create_cart(current_user)
        return api_response(
            success=True,
            data=self._serialize_cart(cart)
        )

    @http.route('/api/v1/cart/items', type='json', auth='none', methods=['POST'], csrf=False)
    @jwt_required
    def add_to_cart(self, current_user=None, **kwargs):
        """
        Add item to cart.

        Request:
            {
                "product_id": 1,
                "quantity": 2,
                "variant_id": null
            }
        """
        product_id = kwargs.get('product_id')
        quantity = int(kwargs.get('quantity', 1))
        variant_id = kwargs.get('variant_id')

        # Validate
        is_valid, error = self._validate_required_fields(kwargs, ['product_id'])
        if not is_valid:
            return error

        # Get product
        product = request.env['product.product'].sudo().browse(product_id)
        if not product.exists() or not product.sale_ok:
            return api_response(
                success=False,
                message='Product not found or not available',
                error_code='INVALID_PRODUCT'
            )

        # Check stock
        if quantity > product.qty_available:
            return api_response(
                success=False,
                message=f'Only {int(product.qty_available)} items available',
                error_code='INSUFFICIENT_STOCK',
                data={'available_quantity': int(product.qty_available)}
            )

        # Get or create cart
        cart = self._get_or_create_cart(current_user)

        # Check if product already in cart
        existing_line = cart.order_line.filtered(
            lambda l: l.product_id.id == product_id
        )

        if existing_line:
            new_qty = existing_line.product_uom_qty + quantity
            if new_qty > product.qty_available:
                return api_response(
                    success=False,
                    message=f'Cannot add more. Only {int(product.qty_available)} available',
                    error_code='INSUFFICIENT_STOCK'
                )
            existing_line.write({'product_uom_qty': new_qty})
        else:
            request.env['sale.order.line'].sudo().create({
                'order_id': cart.id,
                'product_id': product_id,
                'product_uom_qty': quantity,
                'price_unit': product.list_price,
            })

        return api_response(
            success=True,
            data=self._serialize_cart(cart),
            message='Item added to cart'
        )

    @http.route('/api/v1/cart/items/<int:line_id>', type='json', auth='none', methods=['PUT'], csrf=False)
    @jwt_required
    def update_cart_item(self, line_id, current_user=None, **kwargs):
        """Update cart item quantity"""
        quantity = int(kwargs.get('quantity', 1))

        cart = self._get_or_create_cart(current_user)
        line = cart.order_line.filtered(lambda l: l.id == line_id)

        if not line:
            return api_response(
                success=False,
                message='Cart item not found',
                error_code='ITEM_NOT_FOUND'
            )

        if quantity <= 0:
            line.unlink()
        else:
            # Check stock
            if quantity > line.product_id.qty_available:
                return api_response(
                    success=False,
                    message=f'Only {int(line.product_id.qty_available)} available',
                    error_code='INSUFFICIENT_STOCK'
                )
            line.write({'product_uom_qty': quantity})

        return api_response(
            success=True,
            data=self._serialize_cart(cart)
        )

    @http.route('/api/v1/cart/items/<int:line_id>', type='json', auth='none', methods=['DELETE'], csrf=False)
    @jwt_required
    def remove_cart_item(self, line_id, current_user=None, **kwargs):
        """Remove item from cart"""
        cart = self._get_or_create_cart(current_user)
        line = cart.order_line.filtered(lambda l: l.id == line_id)

        if line:
            line.unlink()

        return api_response(
            success=True,
            data=self._serialize_cart(cart),
            message='Item removed from cart'
        )

    @http.route('/api/v1/cart/clear', type='json', auth='none', methods=['DELETE'], csrf=False)
    @jwt_required
    def clear_cart(self, current_user=None, **kwargs):
        """Clear all items from cart"""
        cart = self._get_or_create_cart(current_user)
        cart.order_line.unlink()

        return api_response(
            success=True,
            data=self._serialize_cart(cart),
            message='Cart cleared'
        )

    def _get_or_create_cart(self, user):
        """Get existing cart or create new one"""
        SaleOrder = request.env['sale.order'].sudo()

        # Find draft order (cart)
        cart = SaleOrder.search([
            ('partner_id', '=', user.partner_id.id),
            ('state', '=', 'draft'),
            ('is_mobile_cart', '=', True),
        ], limit=1, order='create_date desc')

        if not cart:
            cart = SaleOrder.create({
                'partner_id': user.partner_id.id,
                'is_mobile_cart': True,
                'state': 'draft',
            })

        return cart

    def _serialize_cart(self, cart):
        """Serialize cart for API response"""
        items = []
        for line in cart.order_line:
            items.append({
                'id': line.id,
                'product_id': line.product_id.id,
                'product_name': line.product_id.name,
                'product_image': f'/web/image/product.product/{line.product_id.id}/image_128',
                'quantity': line.product_uom_qty,
                'unit_price': line.price_unit,
                'subtotal': line.price_subtotal,
                'unit': line.product_uom.name,
                'stock_status': 'in_stock' if line.product_id.qty_available > line.product_uom_qty else 'low_stock',
                'max_quantity': int(line.product_id.qty_available),
            })

        return {
            'id': cart.id,
            'items': items,
            'item_count': len(items),
            'subtotal': cart.amount_untaxed,
            'tax': cart.amount_tax,
            'delivery_fee': self._calculate_delivery_fee(cart),
            'discount': cart.amount_discount if hasattr(cart, 'amount_discount') else 0,
            'total': cart.amount_total,
            'currency': cart.currency_id.name,
            'currency_symbol': '৳',
        }

    def _calculate_delivery_fee(self, cart):
        """Calculate delivery fee based on order value and location"""
        # Free delivery over 500 BDT
        if cart.amount_untaxed >= 500:
            return 0
        return 50.0  # Default delivery fee
```

**Task 2: Cart Model Extensions (2h)**

```python
# odoo/addons/smart_dairy_mobile_api/models/sale_order_extension.py
from odoo import models, fields


class SaleOrderExtension(models.Model):
    _inherit = 'sale.order'

    is_mobile_cart = fields.Boolean(
        string='Is Mobile Cart',
        default=False,
        help='Indicates this order was created from mobile app'
    )
    mobile_device_id = fields.Char(string='Mobile Device ID')
    delivery_slot_id = fields.Many2one(
        'delivery.slot',
        string='Delivery Slot'
    )
    delivery_instructions = fields.Text(string='Delivery Instructions')
    payment_method = fields.Selection([
        ('bkash', 'bKash'),
        ('nagad', 'Nagad'),
        ('rocket', 'Rocket'),
        ('card', 'Card'),
        ('cod', 'Cash on Delivery'),
    ], string='Payment Method')
    payment_transaction_id = fields.Char(string='Payment Transaction ID')
    payment_status = fields.Selection([
        ('pending', 'Pending'),
        ('processing', 'Processing'),
        ('completed', 'Completed'),
        ('failed', 'Failed'),
        ('refunded', 'Refunded'),
    ], string='Payment Status', default='pending')
```

#### Dev 2 — Full-Stack (8h)

**Task 1: Payment Gateway Base Setup (4h)**

```python
# odoo/addons/smart_dairy_mobile_api/models/payment_service.py
from odoo import models, api
import requests
import hashlib
import hmac
import logging
from datetime import datetime

_logger = logging.getLogger(__name__)


class PaymentService(models.AbstractModel):
    _name = 'mobile.payment.service'
    _description = 'Payment Gateway Service'

    @api.model
    def get_available_methods(self):
        """Get available payment methods"""
        ICP = self.env['ir.config_parameter'].sudo()

        methods = []

        if ICP.get_param('payment.bkash.enabled', 'true') == 'true':
            methods.append({
                'id': 'bkash',
                'name': 'bKash',
                'icon': 'bkash',
                'description': 'Pay with bKash mobile wallet',
            })

        if ICP.get_param('payment.nagad.enabled', 'true') == 'true':
            methods.append({
                'id': 'nagad',
                'name': 'Nagad',
                'icon': 'nagad',
                'description': 'Pay with Nagad mobile wallet',
            })

        if ICP.get_param('payment.rocket.enabled', 'true') == 'true':
            methods.append({
                'id': 'rocket',
                'name': 'Rocket',
                'icon': 'rocket',
                'description': 'Pay with Rocket mobile banking',
            })

        if ICP.get_param('payment.sslcommerz.enabled', 'true') == 'true':
            methods.append({
                'id': 'card',
                'name': 'Credit/Debit Card',
                'icon': 'card',
                'description': 'Pay with Visa, Mastercard, or AMEX',
            })

        if ICP.get_param('payment.cod.enabled', 'true') == 'true':
            methods.append({
                'id': 'cod',
                'name': 'Cash on Delivery',
                'icon': 'cash',
                'description': 'Pay when you receive your order',
            })

        return methods

    @api.model
    def initiate_payment(self, order_id, method, callback_url):
        """
        Initiate payment for an order.

        Args:
            order_id: Sale order ID
            method: Payment method (bkash, nagad, rocket, card, cod)
            callback_url: URL for payment completion callback

        Returns:
            dict: Payment initiation response
        """
        order = self.env['sale.order'].sudo().browse(order_id)

        if not order.exists():
            return {'success': False, 'error': 'Order not found'}

        if method == 'bkash':
            return self._initiate_bkash(order, callback_url)
        elif method == 'nagad':
            return self._initiate_nagad(order, callback_url)
        elif method == 'rocket':
            return self._initiate_rocket(order, callback_url)
        elif method == 'card':
            return self._initiate_sslcommerz(order, callback_url)
        elif method == 'cod':
            return self._process_cod(order)
        else:
            return {'success': False, 'error': 'Invalid payment method'}

    def _initiate_bkash(self, order, callback_url):
        """Initialize bKash payment"""
        ICP = self.env['ir.config_parameter'].sudo()

        app_key = ICP.get_param('payment.bkash.app_key')
        app_secret = ICP.get_param('payment.bkash.app_secret')
        username = ICP.get_param('payment.bkash.username')
        password = ICP.get_param('payment.bkash.password')
        base_url = ICP.get_param('payment.bkash.base_url', 'https://tokenized.sandbox.bka.sh/v1.2.0-beta')

        try:
            # Get grant token
            grant_response = requests.post(
                f'{base_url}/tokenized/checkout/token/grant',
                json={
                    'app_key': app_key,
                    'app_secret': app_secret,
                },
                headers={
                    'Content-Type': 'application/json',
                    'username': username,
                    'password': password,
                },
                timeout=30
            )
            grant_data = grant_response.json()

            if 'id_token' not in grant_data:
                return {'success': False, 'error': 'Failed to get bKash token'}

            id_token = grant_data['id_token']

            # Create payment
            payment_response = requests.post(
                f'{base_url}/tokenized/checkout/create',
                json={
                    'mode': '0011',
                    'payerReference': str(order.partner_id.id),
                    'callbackURL': callback_url,
                    'amount': str(order.amount_total),
                    'currency': 'BDT',
                    'intent': 'sale',
                    'merchantInvoiceNumber': order.name,
                },
                headers={
                    'Content-Type': 'application/json',
                    'Authorization': id_token,
                    'X-APP-Key': app_key,
                },
                timeout=30
            )
            payment_data = payment_response.json()

            if payment_data.get('statusCode') == '0000':
                # Store payment ID
                order.write({
                    'payment_method': 'bkash',
                    'payment_transaction_id': payment_data.get('paymentID'),
                    'payment_status': 'processing',
                })

                return {
                    'success': True,
                    'payment_id': payment_data.get('paymentID'),
                    'bkash_url': payment_data.get('bkashURL'),
                }
            else:
                return {
                    'success': False,
                    'error': payment_data.get('statusMessage', 'Payment initiation failed'),
                }

        except Exception as e:
            _logger.error(f'bKash payment error: {e}')
            return {'success': False, 'error': str(e)}

    def _process_cod(self, order):
        """Process Cash on Delivery order"""
        order.write({
            'payment_method': 'cod',
            'payment_status': 'pending',
        })

        # Confirm order
        order.action_confirm()

        return {
            'success': True,
            'order_id': order.id,
            'order_name': order.name,
            'message': 'Order placed successfully. Pay on delivery.',
        }
```

**Task 2: Webhook Handlers (4h)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/payment_webhook_controller.py
from odoo import http
from odoo.http import request
import json
import logging

_logger = logging.getLogger(__name__)


class PaymentWebhookController(http.Controller):
    """Payment gateway webhook handlers"""

    @http.route('/api/v1/webhooks/bkash', type='json', auth='none', methods=['POST'], csrf=False)
    def bkash_webhook(self, **kwargs):
        """Handle bKash payment callback"""
        _logger.info(f'bKash webhook received: {kwargs}')

        payment_id = kwargs.get('paymentID')
        status = kwargs.get('status')

        if not payment_id:
            return {'status': 'error', 'message': 'Missing payment ID'}

        order = request.env['sale.order'].sudo().search([
            ('payment_transaction_id', '=', payment_id)
        ], limit=1)

        if not order:
            return {'status': 'error', 'message': 'Order not found'}

        if status == 'success':
            order.write({'payment_status': 'completed'})
            order.action_confirm()

            # Send notification
            self._send_order_confirmation(order)

        elif status == 'failure':
            order.write({'payment_status': 'failed'})

        return {'status': 'ok'}

    @http.route('/api/v1/webhooks/nagad', type='json', auth='none', methods=['POST'], csrf=False)
    def nagad_webhook(self, **kwargs):
        """Handle Nagad payment callback"""
        _logger.info(f'Nagad webhook received: {kwargs}')
        # Similar implementation
        return {'status': 'ok'}

    @http.route('/api/v1/webhooks/sslcommerz', type='http', auth='none', methods=['POST'], csrf=False)
    def sslcommerz_webhook(self, **post):
        """Handle SSLCommerz IPN"""
        _logger.info(f'SSLCommerz IPN received: {post}')

        # Verify signature
        if not self._verify_sslcommerz_signature(post):
            return 'FAILED'

        tran_id = post.get('tran_id')
        status = post.get('status')

        order = request.env['sale.order'].sudo().search([
            ('name', '=', tran_id)
        ], limit=1)

        if order and status == 'VALID':
            order.write({
                'payment_status': 'completed',
                'payment_transaction_id': post.get('bank_tran_id'),
            })
            order.action_confirm()

        return 'VALIDATED'

    def _verify_sslcommerz_signature(self, data):
        """Verify SSLCommerz signature"""
        # Implementation based on SSLCommerz documentation
        return True  # Simplified for brevity

    def _send_order_confirmation(self, order):
        """Send order confirmation notification"""
        # Push notification
        fcm_service = request.env['mobile.fcm.service'].sudo()
        fcm_service.send_order_notification(
            user_id=order.partner_id.user_ids[0].id if order.partner_id.user_ids else None,
            order_id=order.id,
            title='Order Confirmed!',
            body=f'Your order #{order.name} has been confirmed.',
        )
```

#### Dev 3 — Mobile Lead (8h)

**Task 1: Cart Screen UI (5h)**

```dart
// apps/customer/lib/features/cart/presentation/screens/cart_screen.dart
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_slidable/flutter_slidable.dart';

class CartScreen extends ConsumerWidget {
  const CartScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final cartAsync = ref.watch(cartProvider);
    final theme = Theme.of(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Shopping Cart'),
        actions: [
          cartAsync.whenOrNull(
            data: (cart) => cart.items.isNotEmpty
                ? TextButton(
                    onPressed: () => _showClearConfirmation(context, ref),
                    child: const Text('Clear'),
                  )
                : null,
          ) ?? const SizedBox.shrink(),
        ],
      ),
      body: cartAsync.when(
        data: (cart) => cart.items.isEmpty
            ? const _EmptyCart()
            : _CartContent(cart: cart),
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (e, _) => Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(Icons.error_outline, size: 48, color: Colors.grey),
              const SizedBox(height: 16),
              Text('Failed to load cart: $e'),
              ElevatedButton(
                onPressed: () => ref.refresh(cartProvider),
                child: const Text('Retry'),
              ),
            ],
          ),
        ),
      ),
      bottomNavigationBar: cartAsync.whenOrNull(
        data: (cart) => cart.items.isNotEmpty
            ? _CartSummaryBar(cart: cart)
            : null,
      ),
    );
  }

  void _showClearConfirmation(BuildContext context, WidgetRef ref) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Clear Cart'),
        content: const Text('Are you sure you want to remove all items?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cancel'),
          ),
          TextButton(
            onPressed: () {
              ref.read(cartProvider.notifier).clearCart();
              Navigator.pop(context);
            },
            child: const Text('Clear', style: TextStyle(color: Colors.red)),
          ),
        ],
      ),
    );
  }
}

class _CartContent extends StatelessWidget {
  final Cart cart;

  const _CartContent({required this.cart});

  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: cart.items.length,
      itemBuilder: (context, index) {
        return Padding(
          padding: const EdgeInsets.only(bottom: 12),
          child: _CartItemCard(item: cart.items[index]),
        );
      },
    );
  }
}

class _CartItemCard extends ConsumerWidget {
  final CartItem item;

  const _CartItemCard({required this.item});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final theme = Theme.of(context);

    return Slidable(
      endActionPane: ActionPane(
        motion: const ScrollMotion(),
        children: [
          SlidableAction(
            onPressed: (_) {
              ref.read(cartProvider.notifier).removeItem(item.id);
            },
            backgroundColor: Colors.red,
            foregroundColor: Colors.white,
            icon: Icons.delete,
            label: 'Remove',
          ),
        ],
      ),
      child: Card(
        child: Padding(
          padding: const EdgeInsets.all(12),
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Product Image
              ClipRRect(
                borderRadius: BorderRadius.circular(8),
                child: CachedNetworkImage(
                  imageUrl: item.productImage,
                  width: 80,
                  height: 80,
                  fit: BoxFit.cover,
                  placeholder: (_, __) => Container(
                    color: Colors.grey[200],
                    child: const Icon(Icons.image),
                  ),
                ),
              ),

              const SizedBox(width: 12),

              // Details
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      item.productName,
                      style: theme.textTheme.titleSmall,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 4),
                    Text(
                      '৳${item.unitPrice.toStringAsFixed(0)} / ${item.unit}',
                      style: theme.textTheme.bodySmall?.copyWith(
                        color: Colors.grey[600],
                      ),
                    ),
                    const SizedBox(height: 8),

                    // Quantity Selector
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        _QuantitySelector(
                          quantity: item.quantity.toInt(),
                          maxQuantity: item.maxQuantity,
                          onChanged: (qty) {
                            ref.read(cartProvider.notifier).updateQuantity(
                              item.id,
                              qty.toDouble(),
                            );
                          },
                        ),
                        Text(
                          '৳${item.subtotal.toStringAsFixed(0)}',
                          style: theme.textTheme.titleMedium?.copyWith(
                            fontWeight: FontWeight.bold,
                            color: theme.primaryColor,
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _QuantitySelector extends StatelessWidget {
  final int quantity;
  final int maxQuantity;
  final ValueChanged<int> onChanged;

  const _QuantitySelector({
    required this.quantity,
    required this.maxQuantity,
    required this.onChanged,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        border: Border.all(color: Colors.grey[300]!),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          IconButton(
            icon: const Icon(Icons.remove, size: 18),
            onPressed: quantity > 1
                ? () => onChanged(quantity - 1)
                : null,
            constraints: const BoxConstraints(minWidth: 36, minHeight: 36),
          ),
          Container(
            width: 40,
            alignment: Alignment.center,
            child: Text(
              quantity.toString(),
              style: const TextStyle(fontWeight: FontWeight.bold),
            ),
          ),
          IconButton(
            icon: const Icon(Icons.add, size: 18),
            onPressed: quantity < maxQuantity
                ? () => onChanged(quantity + 1)
                : null,
            constraints: const BoxConstraints(minWidth: 36, minHeight: 36),
          ),
        ],
      ),
    );
  }
}

class _CartSummaryBar extends StatelessWidget {
  final Cart cart;

  const _CartSummaryBar({required this.cart});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.1),
            blurRadius: 10,
            offset: const Offset(0, -5),
          ),
        ],
      ),
      child: SafeArea(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            // Summary
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text('Subtotal'),
                Text('৳${cart.subtotal.toStringAsFixed(0)}'),
              ],
            ),
            const SizedBox(height: 4),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text('Delivery'),
                Text(
                  cart.deliveryFee > 0
                      ? '৳${cart.deliveryFee.toStringAsFixed(0)}'
                      : 'FREE',
                  style: TextStyle(
                    color: cart.deliveryFee > 0 ? null : Colors.green,
                  ),
                ),
              ],
            ),
            if (cart.discount > 0) ...[
              const SizedBox(height: 4),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text('Discount'),
                  Text(
                    '-৳${cart.discount.toStringAsFixed(0)}',
                    style: const TextStyle(color: Colors.green),
                  ),
                ],
              ),
            ],
            const Divider(height: 16),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  'Total',
                  style: theme.textTheme.titleMedium?.copyWith(
                    fontWeight: FontWeight.bold,
                  ),
                ),
                Text(
                  '৳${cart.total.toStringAsFixed(0)}',
                  style: theme.textTheme.titleLarge?.copyWith(
                    fontWeight: FontWeight.bold,
                    color: theme.primaryColor,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 16),

            // Checkout Button
            SizedBox(
              width: double.infinity,
              height: 52,
              child: ElevatedButton(
                onPressed: () => context.push('/checkout'),
                child: Text(
                  'Proceed to Checkout (${cart.itemCount} items)',
                  style: const TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _EmptyCart extends StatelessWidget {
  const _EmptyCart();

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(
            Icons.shopping_cart_outlined,
            size: 80,
            color: Colors.grey[400],
          ),
          const SizedBox(height: 16),
          Text(
            'Your cart is empty',
            style: Theme.of(context).textTheme.titleLarge,
          ),
          const SizedBox(height: 8),
          Text(
            'Add some dairy products to get started',
            style: TextStyle(color: Colors.grey[600]),
          ),
          const SizedBox(height: 24),
          ElevatedButton(
            onPressed: () => context.go('/products'),
            child: const Text('Browse Products'),
          ),
        ],
      ),
    );
  }
}
```

**Task 2: Cart Provider (3h)**

```dart
// apps/customer/lib/features/cart/presentation/providers/cart_provider.dart
import 'package:riverpod_annotation/riverpod_annotation.dart';
import 'package:smart_dairy_core/core.dart';

part 'cart_provider.g.dart';

@riverpod
class Cart extends _$Cart {
  @override
  Future<CartModel> build() async {
    return _fetchCart();
  }

  Future<CartModel> _fetchCart() async {
    final repository = ref.read(cartRepositoryProvider);
    return repository.getCart();
  }

  Future<void> addItem(int productId, {int quantity = 1}) async {
    state = const AsyncValue.loading();

    try {
      final repository = ref.read(cartRepositoryProvider);
      final cart = await repository.addToCart(productId, quantity);
      state = AsyncValue.data(cart);
    } catch (e, st) {
      state = AsyncValue.error(e, st);
    }
  }

  Future<void> updateQuantity(int lineId, double quantity) async {
    final currentCart = state.value;
    if (currentCart == null) return;

    // Optimistic update
    final updatedItems = currentCart.items.map((item) {
      if (item.id == lineId) {
        return item.copyWith(quantity: quantity);
      }
      return item;
    }).toList();

    state = AsyncValue.data(currentCart.copyWith(items: updatedItems));

    try {
      final repository = ref.read(cartRepositoryProvider);
      final cart = await repository.updateCartItem(lineId, quantity.toInt());
      state = AsyncValue.data(cart);
    } catch (e, st) {
      // Revert on error
      state = AsyncValue.data(currentCart);
      rethrow;
    }
  }

  Future<void> removeItem(int lineId) async {
    final currentCart = state.value;
    if (currentCart == null) return;

    // Optimistic update
    final updatedItems = currentCart.items.where((item) => item.id != lineId).toList();
    state = AsyncValue.data(currentCart.copyWith(items: updatedItems));

    try {
      final repository = ref.read(cartRepositoryProvider);
      await repository.removeCartItem(lineId);
    } catch (e, st) {
      // Revert on error
      state = AsyncValue.data(currentCart);
      rethrow;
    }
  }

  Future<void> clearCart() async {
    state = const AsyncValue.loading();

    try {
      final repository = ref.read(cartRepositoryProvider);
      final cart = await repository.clearCart();
      state = AsyncValue.data(cart);
    } catch (e, st) {
      state = AsyncValue.error(e, st);
    }
  }
}
```

**End-of-Day 281 Deliverables:**

- [ ] Cart CRUD API complete
- [ ] Cart model extensions added
- [ ] Payment service base created
- [ ] Cart screen UI implemented
- [ ] Cart provider with optimistic updates

---

### Days 282-290 Summary

**Day 282:** Cart validation, stock checking, cart UI polish
**Day 283:** Address management API, address screens, GPS integration
**Day 284:** Delivery slots API, slot selection UI, calendar picker
**Day 285:** Checkout flow API, checkout screens, order summary
**Day 286:** bKash integration complete, bKash UI, testing
**Day 287:** Nagad integration complete, Nagad UI, testing
**Day 288:** Rocket and SSLCommerz integration, card payment UI
**Day 289:** Order confirmation screen, invoice display, success animation
**Day 290:** Integration testing, payment flow E2E tests, documentation

---

## 4. Technical Specifications

### 4.1 Payment Flow State Machine

```dart
enum PaymentStatus {
  idle,
  selectingMethod,
  initiating,
  redirecting,
  processing,
  completed,
  failed,
  cancelled,
}
```

### 4.2 Payment Gateway Configurations

| Gateway | Sandbox URL | Production URL | Timeout |
|---------|-------------|----------------|---------|
| bKash | tokenized.sandbox.bka.sh | tokenized.pay.bka.sh | 30s |
| Nagad | sandbox.mynagad.com | api.mynagad.com | 30s |
| SSLCommerz | sandbox.sslcommerz.com | securepay.sslcommerz.com | 60s |

---

## 5. Testing & Validation

### 5.1 Test Coverage Requirements

| Component | Required Coverage |
|-----------|------------------|
| Cart logic | >85% |
| Payment service | >80% |
| Checkout flow | >80% |

### 5.2 Payment Test Scenarios

- [ ] bKash success flow
- [ ] bKash failure handling
- [ ] Nagad success flow
- [ ] Card payment success
- [ ] COD order placement
- [ ] Payment timeout handling

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Payment gateway downtime | Medium | High | Fallback to COD |
| Stock changes during checkout | Medium | Medium | Final validation |
| Payment fraud | Low | High | Signature verification |

---

## 7. Dependencies & Handoffs

### 7.1 Input Dependencies

| Dependency | Source | Required By |
|------------|--------|-------------|
| Milestone 53 Products | Phase 6 | Day 281 |
| bKash merchant account | IT Admin | Day 286 |
| Nagad merchant account | IT Admin | Day 287 |

---

**Document Prepared By:** Smart Dairy Development Team
**Next Milestone:** Milestone 55 — Customer App Orders & Profile
