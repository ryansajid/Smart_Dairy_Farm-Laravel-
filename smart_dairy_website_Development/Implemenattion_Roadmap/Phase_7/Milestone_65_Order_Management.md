# Milestone 65: Order Management

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P7-M65-v1.0 |
| **Milestone** | 65 - Order Management |
| **Phase** | Phase 7: B2C E-Commerce Core |
| **Days** | 391-400 |
| **Duration** | 10 Working Days |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Technical Architecture Team |
| **Last Updated** | 2025-01-15 |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Implement comprehensive post-purchase order lifecycle management including real-time tracking, order modifications, cancellation workflows, returns/refunds processing, delivery rescheduling, invoice PDF generation, and multi-channel customer notifications.**

### 1.2 Context

Milestone 65 focuses on the complete order management experience after purchase. This includes enabling customers to track their orders in real-time, request modifications before dispatch, cancel orders with proper refund workflows, initiate returns, reschedule deliveries, and receive automated notifications at every stage.

### 1.3 Scope Summary

| Area | Included |
|------|----------|
| Order Tracking | Real-time status updates, GPS tracking |
| Order Modification | Pre-dispatch changes (quantity, address) |
| Cancellation | Self-service cancellation with reason |
| Returns/Refunds | RMA creation, refund processing |
| Delivery Reschedule | Date/time slot changes |
| Invoice Generation | PDF invoice with digital signature |
| Notifications | Email, SMS, push notifications |
| Order History | Complete order timeline view |

---

## 2. Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| O1 | Real-time order tracking | Status update latency < 30 sec |
| O2 | Self-service order modification | Pre-dispatch modification success > 95% |
| O3 | Automated cancellation workflow | Cancellation processing < 5 min |
| O4 | Returns/refund management | RMA creation to resolution < 48 hrs |
| O5 | Delivery rescheduling | Reschedule success rate > 98% |
| O6 | Invoice PDF generation | PDF generation < 3 sec |
| O7 | Multi-channel notifications | Notification delivery > 99% |
| O8 | Order history dashboard | Page load < 2 sec |

---

## 3. Key Deliverables

| # | Deliverable | Owner | Format | Due Day |
|---|-------------|-------|--------|---------|
| D1 | Order lifecycle state machine | Dev 1 | Python | Day 393 |
| D2 | Order modification service | Dev 1 | Python | Day 394 |
| D3 | Cancellation workflow engine | Dev 1 | Python | Day 395 |
| D4 | Returns/RMA management | Dev 2 | Python | Day 394 |
| D5 | Refund processing service | Dev 2 | Python | Day 396 |
| D6 | Delivery reschedule API | Dev 2 | Python | Day 395 |
| D7 | Invoice PDF generator | Dev 2 | Python/QWeb | Day 397 |
| D8 | Notification service | Dev 1 | Python | Day 396 |
| D9 | Order tracking OWL component | Dev 3 | JavaScript | Day 395 |
| D10 | Order history dashboard | Dev 3 | OWL/QWeb | Day 397 |
| D11 | Return request UI | Dev 3 | OWL | Day 398 |
| D12 | Integration testing | All | Tests | Day 400 |

---

## 4. Requirement Traceability

### 4.1 RFP Requirements

| RFP Req ID | Description | Deliverable |
|------------|-------------|-------------|
| RFP-B2C-4.5.1-001 | Order status tracking | D1, D9 |
| RFP-B2C-4.5.1-002 | Real-time delivery updates | D1, D8 |
| RFP-B2C-4.5.2-001 | Order modification | D2 |
| RFP-B2C-4.5.2-002 | Order cancellation | D3 |
| RFP-B2C-4.5.3-001 | Returns management | D4, D11 |
| RFP-B2C-4.5.3-002 | Refund processing | D5 |
| RFP-B2C-4.5.4-001 | Invoice generation | D7 |
| RFP-B2C-4.5.5-001 | Customer notifications | D8 |

### 4.2 BRD Requirements

| BRD Req ID | Description | Deliverable |
|------------|-------------|-------------|
| BRD-FR-B2C-005 | Post-purchase experience | All |
| BRD-FR-B2C-005.1 | Order lifecycle management | D1, D2, D3 |
| BRD-FR-B2C-005.2 | Returns and refunds | D4, D5, D11 |
| BRD-FR-B2C-005.3 | Delivery management | D6 |
| BRD-FR-B2C-005.4 | Order documentation | D7, D10 |
| BRD-FR-B2C-005.5 | Proactive notifications | D8 |

---

## 5. Day-by-Day Development Plan

### Day 391-392: Order Lifecycle Architecture

#### Dev 1 (Backend Lead) - Days 391-392

**Focus: Order State Machine & Lifecycle Management**

**Hour 1-4: Order Status Model Extension**

```python
# File: smart_dairy_ecommerce/models/order_lifecycle.py

from odoo import models, fields, api
from odoo.exceptions import UserError, ValidationError
from datetime import datetime, timedelta
import json

class SaleOrder(models.Model):
    _inherit = 'sale.order'

    # Extended Status Tracking
    order_status = fields.Selection([
        ('pending', 'Pending Confirmation'),
        ('confirmed', 'Order Confirmed'),
        ('processing', 'Processing'),
        ('ready_to_ship', 'Ready to Ship'),
        ('shipped', 'Shipped'),
        ('out_for_delivery', 'Out for Delivery'),
        ('delivered', 'Delivered'),
        ('cancelled', 'Cancelled'),
        ('return_requested', 'Return Requested'),
        ('return_approved', 'Return Approved'),
        ('return_received', 'Return Received'),
        ('refunded', 'Refunded'),
    ], string='Order Status', default='pending', tracking=True)

    # Timestamps
    confirmed_at = fields.Datetime(string='Confirmed At')
    processing_at = fields.Datetime(string='Processing Started')
    shipped_at = fields.Datetime(string='Shipped At')
    delivered_at = fields.Datetime(string='Delivered At')
    cancelled_at = fields.Datetime(string='Cancelled At')

    # Delivery Information
    delivery_slot_id = fields.Many2one(
        'delivery.slot',
        string='Delivery Slot'
    )
    delivery_instructions = fields.Text(string='Delivery Instructions')
    delivery_tracking_number = fields.Char(string='Tracking Number')
    delivery_carrier = fields.Char(string='Carrier Name')
    estimated_delivery = fields.Date(string='Estimated Delivery')
    actual_delivery = fields.Datetime(string='Actual Delivery')

    # GPS Tracking
    current_location_lat = fields.Float(string='Current Latitude')
    current_location_lng = fields.Float(string='Current Longitude')
    last_location_update = fields.Datetime(string='Last Location Update')

    # Modification Tracking
    is_modifiable = fields.Boolean(
        string='Can Modify',
        compute='_compute_modifiable'
    )
    is_cancellable = fields.Boolean(
        string='Can Cancel',
        compute='_compute_cancellable'
    )
    modification_count = fields.Integer(
        string='Modifications',
        default=0
    )
    modification_deadline = fields.Datetime(
        string='Modification Deadline',
        compute='_compute_modification_deadline'
    )

    # Cancellation
    cancellation_reason = fields.Selection([
        ('changed_mind', 'Changed My Mind'),
        ('found_cheaper', 'Found Cheaper Alternative'),
        ('delivery_too_long', 'Delivery Time Too Long'),
        ('ordered_by_mistake', 'Ordered by Mistake'),
        ('duplicate_order', 'Duplicate Order'),
        ('payment_issue', 'Payment Issue'),
        ('other', 'Other Reason'),
    ], string='Cancellation Reason')
    cancellation_notes = fields.Text(string='Cancellation Notes')
    cancelled_by = fields.Many2one(
        'res.users',
        string='Cancelled By'
    )

    # Returns
    return_request_ids = fields.One2many(
        'order.return.request',
        'order_id',
        string='Return Requests'
    )
    has_pending_return = fields.Boolean(
        string='Has Pending Return',
        compute='_compute_pending_return'
    )

    # History
    status_history_ids = fields.One2many(
        'order.status.history',
        'order_id',
        string='Status History'
    )

    @api.depends('order_status', 'confirmed_at')
    def _compute_modifiable(self):
        for order in self:
            # Can modify before processing starts
            modifiable_statuses = ['pending', 'confirmed']
            order.is_modifiable = (
                order.order_status in modifiable_statuses and
                order.modification_count < 3  # Max 3 modifications
            )

    @api.depends('order_status', 'shipped_at')
    def _compute_cancellable(self):
        for order in self:
            # Can cancel before shipping
            cancellable_statuses = ['pending', 'confirmed', 'processing', 'ready_to_ship']
            order.is_cancellable = order.order_status in cancellable_statuses

    @api.depends('confirmed_at')
    def _compute_modification_deadline(self):
        for order in self:
            if order.confirmed_at:
                # 2 hours after confirmation
                order.modification_deadline = order.confirmed_at + timedelta(hours=2)
            else:
                order.modification_deadline = False

    def _compute_pending_return(self):
        for order in self:
            order.has_pending_return = bool(order.return_request_ids.filtered(
                lambda r: r.state in ['requested', 'approved']
            ))

    def action_confirm_order(self):
        """Confirm order and start processing"""
        self.ensure_one()
        if self.order_status != 'pending':
            raise UserError('Order is not in pending state')

        self.write({
            'order_status': 'confirmed',
            'confirmed_at': fields.Datetime.now(),
        })
        self._log_status_change('confirmed', 'Order confirmed by customer')
        self._send_notification('order_confirmed')
        return True

    def action_start_processing(self):
        """Start order processing"""
        self.ensure_one()
        if self.order_status != 'confirmed':
            raise UserError('Order must be confirmed first')

        self.write({
            'order_status': 'processing',
            'processing_at': fields.Datetime.now(),
        })
        self._log_status_change('processing', 'Order processing started')
        self._send_notification('order_processing')
        return True

    def action_mark_ready_to_ship(self):
        """Mark order as ready for shipment"""
        self.ensure_one()
        self.write({'order_status': 'ready_to_ship'})
        self._log_status_change('ready_to_ship', 'Order packed and ready to ship')
        return True

    def action_ship_order(self, tracking_number=None, carrier=None):
        """Ship the order"""
        self.ensure_one()
        if self.order_status not in ['processing', 'ready_to_ship']:
            raise UserError('Order is not ready for shipping')

        self.write({
            'order_status': 'shipped',
            'shipped_at': fields.Datetime.now(),
            'delivery_tracking_number': tracking_number,
            'delivery_carrier': carrier,
        })
        self._log_status_change('shipped', f'Order shipped via {carrier or "carrier"}')
        self._send_notification('order_shipped')
        return True

    def action_out_for_delivery(self):
        """Mark order as out for delivery"""
        self.ensure_one()
        self.write({'order_status': 'out_for_delivery'})
        self._log_status_change('out_for_delivery', 'Order is out for delivery')
        self._send_notification('out_for_delivery')
        return True

    def action_mark_delivered(self):
        """Mark order as delivered"""
        self.ensure_one()
        self.write({
            'order_status': 'delivered',
            'delivered_at': fields.Datetime.now(),
            'actual_delivery': fields.Datetime.now(),
        })
        self._log_status_change('delivered', 'Order delivered successfully')
        self._send_notification('order_delivered')

        # Award loyalty points
        self._award_loyalty_points()
        return True

    def action_cancel_order(self, reason, notes=None):
        """Cancel the order"""
        self.ensure_one()
        if not self.is_cancellable:
            raise UserError('This order cannot be cancelled')

        self.write({
            'order_status': 'cancelled',
            'cancelled_at': fields.Datetime.now(),
            'cancellation_reason': reason,
            'cancellation_notes': notes,
            'cancelled_by': self.env.user.id,
        })
        self._log_status_change('cancelled', f'Order cancelled: {reason}')
        self._send_notification('order_cancelled')

        # Process refund if payment was made
        self._process_cancellation_refund()
        return True

    def _log_status_change(self, new_status, notes=''):
        """Log status change to history"""
        self.env['order.status.history'].create({
            'order_id': self.id,
            'status': new_status,
            'notes': notes,
            'changed_by': self.env.user.id,
        })

    def _send_notification(self, notification_type):
        """Send notification to customer"""
        self.env['order.notification'].sudo().send_notification(
            self, notification_type
        )

    def _process_cancellation_refund(self):
        """Process refund for cancelled order"""
        # Check if payment was made
        payments = self.env['payment.transaction.custom'].search([
            ('sale_order_id', '=', self.id),
            ('state', '=', 'done')
        ])
        if payments:
            self.env['order.refund'].create({
                'order_id': self.id,
                'amount': self.amount_total,
                'reason': 'cancellation',
                'payment_transaction_id': payments[0].id,
            })

    def _award_loyalty_points(self):
        """Award loyalty points after delivery"""
        if self.partner_id and self.amount_total > 0:
            points = int(self.amount_total / 10)  # 1 point per 10 BDT
            self.env['loyalty.points'].sudo().add_points(
                self.partner_id.id,
                points,
                f'Order {self.name} - Purchase reward'
            )

    def get_tracking_info(self):
        """Get order tracking information for API"""
        self.ensure_one()
        return {
            'order_id': self.id,
            'order_name': self.name,
            'status': self.order_status,
            'status_display': dict(self._fields['order_status'].selection).get(self.order_status),
            'tracking_number': self.delivery_tracking_number,
            'carrier': self.delivery_carrier,
            'estimated_delivery': self.estimated_delivery.isoformat() if self.estimated_delivery else None,
            'current_location': {
                'lat': self.current_location_lat,
                'lng': self.current_location_lng,
                'updated_at': self.last_location_update.isoformat() if self.last_location_update else None,
            } if self.current_location_lat else None,
            'timeline': [{
                'status': h.status,
                'status_display': dict(self._fields['order_status'].selection).get(h.status),
                'timestamp': h.create_date.isoformat(),
                'notes': h.notes,
            } for h in self.status_history_ids.sorted('create_date')],
            'is_modifiable': self.is_modifiable,
            'is_cancellable': self.is_cancellable,
            'modification_deadline': self.modification_deadline.isoformat() if self.modification_deadline else None,
        }


class OrderStatusHistory(models.Model):
    _name = 'order.status.history'
    _description = 'Order Status History'
    _order = 'create_date desc'

    order_id = fields.Many2one(
        'sale.order',
        string='Order',
        required=True,
        ondelete='cascade',
        index=True
    )
    status = fields.Char(string='Status', required=True)
    notes = fields.Text(string='Notes')
    changed_by = fields.Many2one(
        'res.users',
        string='Changed By',
        default=lambda self: self.env.user
    )
    location_lat = fields.Float(string='Latitude')
    location_lng = fields.Float(string='Longitude')
```

**Hour 5-8: Order Tracking Controller**

```python
# File: smart_dairy_ecommerce/controllers/order_controller.py

from odoo import http
from odoo.http import request
import json

class OrderController(http.Controller):

    def _get_customer_orders(self):
        """Get orders for current customer"""
        partner = request.env.user.partner_id
        return request.env['sale.order'].sudo().search([
            ('partner_id', '=', partner.id),
            ('state', '!=', 'draft'),
        ], order='create_date desc')

    @http.route('/api/v1/orders', type='json', auth='user', methods=['GET'])
    def get_orders(self, **kwargs):
        """Get customer orders"""
        page = kwargs.get('page', 1)
        limit = kwargs.get('limit', 10)
        status = kwargs.get('status')

        domain = [
            ('partner_id', '=', request.env.user.partner_id.id),
            ('state', '!=', 'draft'),
        ]
        if status:
            domain.append(('order_status', '=', status))

        orders = request.env['sale.order'].sudo().search(
            domain,
            limit=limit,
            offset=(page - 1) * limit,
            order='create_date desc'
        )
        total = request.env['sale.order'].sudo().search_count(domain)

        return {
            'success': True,
            'data': {
                'orders': [{
                    'id': o.id,
                    'name': o.name,
                    'date': o.create_date.isoformat(),
                    'status': o.order_status,
                    'status_display': dict(o._fields['order_status'].selection).get(o.order_status),
                    'total': o.amount_total,
                    'item_count': len(o.order_line),
                    'is_modifiable': o.is_modifiable,
                    'is_cancellable': o.is_cancellable,
                } for o in orders],
                'pagination': {
                    'page': page,
                    'limit': limit,
                    'total': total,
                    'pages': (total + limit - 1) // limit,
                }
            }
        }

    @http.route('/api/v1/orders/<int:order_id>', type='json', auth='user', methods=['GET'])
    def get_order_detail(self, order_id, **kwargs):
        """Get order details"""
        order = request.env['sale.order'].sudo().browse(order_id)

        if not order.exists() or order.partner_id.id != request.env.user.partner_id.id:
            return {'error': 'Order not found', 'code': 404}

        return {
            'success': True,
            'data': {
                'id': order.id,
                'name': order.name,
                'date': order.create_date.isoformat(),
                'status': order.order_status,
                'status_display': dict(order._fields['order_status'].selection).get(order.order_status),
                'items': [{
                    'product_id': line.product_id.id,
                    'product_name': line.product_id.name,
                    'quantity': line.product_uom_qty,
                    'price': line.price_unit,
                    'subtotal': line.price_subtotal,
                    'image_url': f'/web/image/product.product/{line.product_id.id}/image_128',
                } for line in order.order_line],
                'subtotal': order.amount_untaxed,
                'tax': order.amount_tax,
                'shipping': order.delivery_price if hasattr(order, 'delivery_price') else 0,
                'total': order.amount_total,
                'shipping_address': {
                    'name': order.partner_shipping_id.name,
                    'street': order.partner_shipping_id.street,
                    'city': order.partner_shipping_id.city,
                    'phone': order.partner_shipping_id.phone,
                },
                'delivery_slot': {
                    'date': order.delivery_slot_id.date.isoformat() if order.delivery_slot_id else None,
                    'time': order.delivery_slot_id.name if order.delivery_slot_id else None,
                } if order.delivery_slot_id else None,
                'tracking': order.get_tracking_info(),
                'is_modifiable': order.is_modifiable,
                'is_cancellable': order.is_cancellable,
                'has_pending_return': order.has_pending_return,
            }
        }

    @http.route('/api/v1/orders/<int:order_id>/tracking', type='json', auth='user', methods=['GET'])
    def get_order_tracking(self, order_id, **kwargs):
        """Get order tracking information"""
        order = request.env['sale.order'].sudo().browse(order_id)

        if not order.exists() or order.partner_id.id != request.env.user.partner_id.id:
            return {'error': 'Order not found', 'code': 404}

        return {
            'success': True,
            'data': order.get_tracking_info()
        }

    @http.route('/api/v1/orders/<int:order_id>/cancel', type='json', auth='user', methods=['POST'])
    def cancel_order(self, order_id, **kwargs):
        """Cancel an order"""
        reason = kwargs.get('reason')
        notes = kwargs.get('notes')

        if not reason:
            return {'error': 'Cancellation reason is required', 'code': 400}

        order = request.env['sale.order'].sudo().browse(order_id)

        if not order.exists() or order.partner_id.id != request.env.user.partner_id.id:
            return {'error': 'Order not found', 'code': 404}

        if not order.is_cancellable:
            return {'error': 'This order cannot be cancelled', 'code': 400}

        try:
            order.action_cancel_order(reason, notes)
            return {
                'success': True,
                'message': 'Order cancelled successfully',
                'data': {'refund_initiated': True}
            }
        except Exception as e:
            return {'error': str(e), 'code': 500}

    @http.route('/api/v1/orders/<int:order_id>/modify', type='json', auth='user', methods=['POST'])
    def modify_order(self, order_id, **kwargs):
        """Modify an order"""
        modifications = kwargs.get('modifications', {})

        order = request.env['sale.order'].sudo().browse(order_id)

        if not order.exists() or order.partner_id.id != request.env.user.partner_id.id:
            return {'error': 'Order not found', 'code': 404}

        if not order.is_modifiable:
            return {'error': 'This order cannot be modified', 'code': 400}

        try:
            result = request.env['order.modification'].sudo().process_modification(
                order, modifications
            )
            return result
        except Exception as e:
            return {'error': str(e), 'code': 500}

    @http.route('/api/v1/orders/<int:order_id>/reschedule', type='json', auth='user', methods=['POST'])
    def reschedule_delivery(self, order_id, **kwargs):
        """Reschedule delivery"""
        new_slot_id = kwargs.get('slot_id')

        if not new_slot_id:
            return {'error': 'New delivery slot is required', 'code': 400}

        order = request.env['sale.order'].sudo().browse(order_id)

        if not order.exists() or order.partner_id.id != request.env.user.partner_id.id:
            return {'error': 'Order not found', 'code': 404}

        # Check if rescheduling is allowed
        if order.order_status not in ['confirmed', 'processing', 'ready_to_ship']:
            return {'error': 'Delivery cannot be rescheduled at this stage', 'code': 400}

        new_slot = request.env['delivery.slot'].sudo().browse(new_slot_id)
        if not new_slot.exists() or new_slot.available_capacity <= 0:
            return {'error': 'Selected delivery slot is not available', 'code': 400}

        old_slot = order.delivery_slot_id
        order.write({'delivery_slot_id': new_slot_id})
        order._log_status_change(
            order.order_status,
            f'Delivery rescheduled from {old_slot.name} to {new_slot.name}'
        )
        order._send_notification('delivery_rescheduled')

        return {
            'success': True,
            'message': 'Delivery rescheduled successfully',
            'data': {
                'new_slot': {
                    'date': new_slot.date.isoformat(),
                    'time': new_slot.name,
                }
            }
        }
```

---

#### Dev 2 (Full-Stack) - Days 391-392

**Focus: Returns/RMA System Foundation**

**Hour 1-4: Return Request Model**

```python
# File: smart_dairy_ecommerce/models/order_return.py

from odoo import models, fields, api
from odoo.exceptions import UserError, ValidationError
from datetime import datetime, timedelta

class OrderReturnRequest(models.Model):
    _name = 'order.return.request'
    _description = 'Order Return Request (RMA)'
    _order = 'create_date desc'
    _inherit = ['mail.thread', 'mail.activity.mixin']

    name = fields.Char(
        string='RMA Number',
        required=True,
        readonly=True,
        default='New',
        copy=False
    )
    order_id = fields.Many2one(
        'sale.order',
        string='Order',
        required=True,
        ondelete='cascade',
        index=True
    )
    partner_id = fields.Many2one(
        'res.partner',
        string='Customer',
        related='order_id.partner_id',
        store=True
    )

    # Return Items
    line_ids = fields.One2many(
        'order.return.line',
        'return_id',
        string='Return Items'
    )

    # Return Reason
    return_reason = fields.Selection([
        ('defective', 'Defective/Damaged Product'),
        ('wrong_item', 'Wrong Item Received'),
        ('not_as_described', 'Not as Described'),
        ('quality_issue', 'Quality Issue'),
        ('expired', 'Expired Product'),
        ('changed_mind', 'Changed My Mind'),
        ('other', 'Other'),
    ], string='Return Reason', required=True, tracking=True)
    reason_description = fields.Text(string='Reason Description')

    # Evidence
    evidence_image_ids = fields.Many2many(
        'ir.attachment',
        'return_evidence_rel',
        'return_id',
        'attachment_id',
        string='Evidence Images'
    )

    # State
    state = fields.Selection([
        ('draft', 'Draft'),
        ('requested', 'Requested'),
        ('approved', 'Approved'),
        ('rejected', 'Rejected'),
        ('pickup_scheduled', 'Pickup Scheduled'),
        ('picked_up', 'Picked Up'),
        ('received', 'Received'),
        ('inspected', 'Inspected'),
        ('refund_initiated', 'Refund Initiated'),
        ('completed', 'Completed'),
        ('cancelled', 'Cancelled'),
    ], string='Status', default='draft', tracking=True)

    # Pickup Information
    pickup_address_id = fields.Many2one(
        'res.partner',
        string='Pickup Address'
    )
    pickup_date = fields.Date(string='Pickup Date')
    pickup_slot = fields.Selection([
        ('morning', '9 AM - 12 PM'),
        ('afternoon', '12 PM - 3 PM'),
        ('evening', '3 PM - 6 PM'),
    ], string='Pickup Time Slot')
    pickup_notes = fields.Text(string='Pickup Notes')

    # Inspection
    inspection_date = fields.Datetime(string='Inspection Date')
    inspection_notes = fields.Text(string='Inspection Notes')
    inspection_result = fields.Selection([
        ('approved', 'Approved for Refund'),
        ('partial', 'Partial Refund'),
        ('rejected', 'Rejected'),
    ], string='Inspection Result')

    # Refund
    refund_id = fields.Many2one(
        'order.refund',
        string='Refund'
    )
    refund_amount = fields.Monetary(
        string='Refund Amount',
        compute='_compute_refund_amount',
        currency_field='currency_id'
    )
    currency_id = fields.Many2one(
        'res.currency',
        related='order_id.currency_id'
    )

    # Timestamps
    requested_at = fields.Datetime(string='Requested At')
    approved_at = fields.Datetime(string='Approved At')
    received_at = fields.Datetime(string='Received At')
    completed_at = fields.Datetime(string='Completed At')

    # Policy
    return_window_days = fields.Integer(
        string='Return Window (Days)',
        default=7
    )
    is_within_return_window = fields.Boolean(
        string='Within Return Window',
        compute='_compute_within_return_window'
    )

    @api.model
    def create(self, vals):
        if vals.get('name', 'New') == 'New':
            vals['name'] = self.env['ir.sequence'].next_by_code('order.return.request') or 'New'
        return super().create(vals)

    @api.depends('line_ids.refund_amount')
    def _compute_refund_amount(self):
        for request in self:
            request.refund_amount = sum(request.line_ids.mapped('refund_amount'))

    @api.depends('order_id.delivered_at', 'return_window_days')
    def _compute_within_return_window(self):
        for request in self:
            if request.order_id.delivered_at:
                deadline = request.order_id.delivered_at + timedelta(days=request.return_window_days)
                request.is_within_return_window = datetime.now() <= deadline
            else:
                request.is_within_return_window = False

    def action_submit_request(self):
        """Submit return request"""
        self.ensure_one()
        if not self.line_ids:
            raise UserError('Please add at least one item to return')

        if not self.is_within_return_window:
            raise UserError('Return window has expired for this order')

        self.write({
            'state': 'requested',
            'requested_at': fields.Datetime.now(),
        })
        self._send_notification('return_requested')
        return True

    def action_approve(self):
        """Approve return request"""
        self.ensure_one()
        self.write({
            'state': 'approved',
            'approved_at': fields.Datetime.now(),
        })
        self.order_id.write({'order_status': 'return_approved'})
        self._send_notification('return_approved')
        return True

    def action_reject(self, reason):
        """Reject return request"""
        self.ensure_one()
        self.write({
            'state': 'rejected',
            'inspection_notes': reason,
        })
        self._send_notification('return_rejected')
        return True

    def action_schedule_pickup(self, pickup_date, pickup_slot):
        """Schedule pickup for return"""
        self.ensure_one()
        self.write({
            'state': 'pickup_scheduled',
            'pickup_date': pickup_date,
            'pickup_slot': pickup_slot,
            'pickup_address_id': self.order_id.partner_shipping_id.id,
        })
        self._send_notification('pickup_scheduled')
        return True

    def action_mark_picked_up(self):
        """Mark items as picked up"""
        self.ensure_one()
        self.write({'state': 'picked_up'})
        self._send_notification('items_picked_up')
        return True

    def action_mark_received(self):
        """Mark items as received at warehouse"""
        self.ensure_one()
        self.write({
            'state': 'received',
            'received_at': fields.Datetime.now(),
        })
        self.order_id.write({'order_status': 'return_received'})
        return True

    def action_complete_inspection(self, result, notes=''):
        """Complete inspection and initiate refund"""
        self.ensure_one()
        self.write({
            'state': 'inspected',
            'inspection_date': fields.Datetime.now(),
            'inspection_result': result,
            'inspection_notes': notes,
        })

        if result in ['approved', 'partial']:
            self._initiate_refund()

        return True

    def _initiate_refund(self):
        """Create refund for approved return"""
        refund = self.env['order.refund'].create({
            'order_id': self.order_id.id,
            'return_request_id': self.id,
            'amount': self.refund_amount,
            'reason': 'return',
        })
        self.write({
            'state': 'refund_initiated',
            'refund_id': refund.id,
        })
        self.order_id.write({'order_status': 'refunded'})
        self._send_notification('refund_initiated')

    def action_complete(self):
        """Mark return as completed"""
        self.ensure_one()
        self.write({
            'state': 'completed',
            'completed_at': fields.Datetime.now(),
        })
        self._send_notification('return_completed')
        return True

    def _send_notification(self, notification_type):
        """Send notification for return events"""
        self.env['order.notification'].sudo().send_return_notification(
            self, notification_type
        )

    def get_return_info(self):
        """Get return information for API"""
        self.ensure_one()
        return {
            'id': self.id,
            'rma_number': self.name,
            'order_id': self.order_id.id,
            'order_name': self.order_id.name,
            'state': self.state,
            'reason': self.return_reason,
            'reason_description': self.reason_description,
            'items': [{
                'product_id': line.product_id.id,
                'product_name': line.product_id.name,
                'quantity': line.quantity,
                'refund_amount': line.refund_amount,
            } for line in self.line_ids],
            'refund_amount': self.refund_amount,
            'pickup': {
                'date': self.pickup_date.isoformat() if self.pickup_date else None,
                'slot': self.pickup_slot,
                'address': self.pickup_address_id.contact_address if self.pickup_address_id else None,
            } if self.state == 'pickup_scheduled' else None,
            'timeline': {
                'requested': self.requested_at.isoformat() if self.requested_at else None,
                'approved': self.approved_at.isoformat() if self.approved_at else None,
                'received': self.received_at.isoformat() if self.received_at else None,
                'completed': self.completed_at.isoformat() if self.completed_at else None,
            }
        }


class OrderReturnLine(models.Model):
    _name = 'order.return.line'
    _description = 'Order Return Line'

    return_id = fields.Many2one(
        'order.return.request',
        string='Return Request',
        required=True,
        ondelete='cascade'
    )
    order_line_id = fields.Many2one(
        'sale.order.line',
        string='Order Line',
        required=True
    )
    product_id = fields.Many2one(
        'product.product',
        string='Product',
        related='order_line_id.product_id',
        store=True
    )
    quantity = fields.Float(
        string='Return Quantity',
        required=True
    )
    max_quantity = fields.Float(
        string='Max Quantity',
        related='order_line_id.product_uom_qty'
    )
    unit_price = fields.Monetary(
        string='Unit Price',
        related='order_line_id.price_unit',
        currency_field='currency_id'
    )
    refund_amount = fields.Monetary(
        string='Refund Amount',
        compute='_compute_refund_amount',
        currency_field='currency_id'
    )
    currency_id = fields.Many2one(
        'res.currency',
        related='return_id.currency_id'
    )

    condition = fields.Selection([
        ('unopened', 'Unopened'),
        ('opened', 'Opened but Unused'),
        ('used', 'Used'),
        ('damaged', 'Damaged'),
    ], string='Item Condition', required=True, default='unopened')

    @api.depends('quantity', 'unit_price', 'condition')
    def _compute_refund_amount(self):
        for line in self:
            base_amount = line.quantity * line.unit_price
            # Apply condition-based deductions
            if line.condition == 'unopened':
                line.refund_amount = base_amount
            elif line.condition == 'opened':
                line.refund_amount = base_amount * 0.9  # 10% restocking fee
            elif line.condition == 'used':
                line.refund_amount = base_amount * 0.7  # 30% deduction
            else:  # damaged
                line.refund_amount = base_amount * 0.5  # 50% deduction

    @api.constrains('quantity')
    def _check_quantity(self):
        for line in self:
            if line.quantity <= 0:
                raise ValidationError('Return quantity must be greater than 0')
            if line.quantity > line.max_quantity:
                raise ValidationError(f'Cannot return more than ordered quantity ({line.max_quantity})')
```

**Hour 5-8: Refund Processing Model**

```python
# File: smart_dairy_ecommerce/models/order_refund.py

from odoo import models, fields, api
from odoo.exceptions import UserError
import requests
import json

class OrderRefund(models.Model):
    _name = 'order.refund'
    _description = 'Order Refund'
    _order = 'create_date desc'
    _inherit = ['mail.thread', 'mail.activity.mixin']

    name = fields.Char(
        string='Refund Reference',
        required=True,
        readonly=True,
        default='New',
        copy=False
    )
    order_id = fields.Many2one(
        'sale.order',
        string='Order',
        required=True,
        index=True
    )
    return_request_id = fields.Many2one(
        'order.return.request',
        string='Return Request'
    )
    partner_id = fields.Many2one(
        'res.partner',
        string='Customer',
        related='order_id.partner_id',
        store=True
    )
    payment_transaction_id = fields.Many2one(
        'payment.transaction.custom',
        string='Original Payment'
    )

    # Amount
    amount = fields.Monetary(
        string='Refund Amount',
        required=True,
        currency_field='currency_id'
    )
    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.company.currency_id
    )

    # Reason
    reason = fields.Selection([
        ('cancellation', 'Order Cancellation'),
        ('return', 'Product Return'),
        ('partial_delivery', 'Partial Delivery'),
        ('quality_issue', 'Quality Issue'),
        ('overcharge', 'Overcharge Correction'),
        ('goodwill', 'Goodwill/Customer Service'),
    ], string='Refund Reason', required=True)
    reason_notes = fields.Text(string='Reason Notes')

    # Refund Method
    refund_method = fields.Selection([
        ('original', 'Original Payment Method'),
        ('wallet', 'Store Wallet'),
        ('bank', 'Bank Transfer'),
    ], string='Refund Method', default='original')

    # Bank Details (if bank transfer)
    bank_name = fields.Char(string='Bank Name')
    account_number = fields.Char(string='Account Number')
    account_holder = fields.Char(string='Account Holder')

    # Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('pending', 'Pending'),
        ('processing', 'Processing'),
        ('completed', 'Completed'),
        ('failed', 'Failed'),
    ], string='Status', default='draft', tracking=True)

    # Provider Response
    provider_reference = fields.Char(string='Provider Reference')
    provider_response = fields.Text(string='Provider Response')
    error_message = fields.Text(string='Error Message')

    # Timestamps
    initiated_at = fields.Datetime(string='Initiated At')
    processed_at = fields.Datetime(string='Processed At')
    completed_at = fields.Datetime(string='Completed At')

    @api.model
    def create(self, vals):
        if vals.get('name', 'New') == 'New':
            vals['name'] = self.env['ir.sequence'].next_by_code('order.refund') or 'New'
        return super().create(vals)

    def action_initiate(self):
        """Initiate refund processing"""
        self.ensure_one()
        self.write({
            'state': 'pending',
            'initiated_at': fields.Datetime.now(),
        })
        # Auto-process if below threshold
        if self.amount <= 5000:  # Auto-approve below 5000 BDT
            self.action_process()
        return True

    def action_process(self):
        """Process the refund"""
        self.ensure_one()
        self.write({'state': 'processing'})

        try:
            if self.refund_method == 'original':
                result = self._process_original_payment_refund()
            elif self.refund_method == 'wallet':
                result = self._process_wallet_refund()
            else:  # bank transfer
                result = self._process_bank_refund()

            if result.get('success'):
                self.write({
                    'state': 'completed',
                    'processed_at': fields.Datetime.now(),
                    'completed_at': fields.Datetime.now(),
                    'provider_reference': result.get('reference'),
                    'provider_response': json.dumps(result),
                })
                self._send_notification('refund_completed')
            else:
                self.write({
                    'state': 'failed',
                    'error_message': result.get('error'),
                })
                self._send_notification('refund_failed')

        except Exception as e:
            self.write({
                'state': 'failed',
                'error_message': str(e),
            })

        return True

    def _process_original_payment_refund(self):
        """Refund to original payment method"""
        if not self.payment_transaction_id:
            return {'error': 'No original payment found'}

        provider = self.payment_transaction_id.provider

        if provider == 'bkash':
            return self._refund_bkash()
        elif provider == 'nagad':
            return self._refund_nagad()
        elif provider == 'sslcommerz':
            return self._refund_sslcommerz()
        elif provider == 'cod':
            # COD refund goes to wallet
            return self._process_wallet_refund()

        return {'error': 'Unknown payment provider'}

    def _refund_bkash(self):
        """Process bKash refund"""
        config = self.env['ir.config_parameter'].sudo()
        base_url = config.get_param('bkash.base_url')
        app_key = config.get_param('bkash.app_key')

        # Get token first
        token_response = self._get_bkash_token()
        if not token_response.get('success'):
            return token_response

        id_token = token_response['id_token']

        # Process refund
        refund_response = requests.post(
            f"{base_url}/tokenized/checkout/payment/refund",
            headers={
                'Content-Type': 'application/json',
                'Authorization': id_token,
                'X-APP-Key': app_key,
            },
            json={
                'paymentID': self.payment_transaction_id.provider_reference,
                'amount': str(self.amount),
                'trxID': self.payment_transaction_id.reference,
                'sku': 'refund',
                'reason': self.reason,
            }
        )

        if refund_response.status_code == 200:
            data = refund_response.json()
            if data.get('statusCode') == '0000':
                return {
                    'success': True,
                    'reference': data.get('refundTrxID'),
                }
            return {'error': data.get('statusMessage', 'bKash refund failed')}

        return {'error': 'bKash API error'}

    def _refund_sslcommerz(self):
        """Process SSLCommerz refund"""
        config = self.env['ir.config_parameter'].sudo()
        store_id = config.get_param('sslcommerz.store_id')
        store_passwd = config.get_param('sslcommerz.store_passwd')
        base_url = config.get_param('sslcommerz.base_url')

        refund_response = requests.post(
            f"{base_url}/validator/api/merchantTransIDvalidationAPI.php",
            data={
                'store_id': store_id,
                'store_passwd': store_passwd,
                'refund_amount': self.amount,
                'refund_remarks': self.reason_notes or self.reason,
                'bank_tran_id': self.payment_transaction_id.provider_reference,
            }
        )

        if refund_response.status_code == 200:
            data = refund_response.json()
            if data.get('status') == 'VALID':
                return {
                    'success': True,
                    'reference': data.get('refund_ref_id'),
                }
            return {'error': data.get('errorReason', 'SSLCommerz refund failed')}

        return {'error': 'SSLCommerz API error'}

    def _process_wallet_refund(self):
        """Refund to customer wallet"""
        wallet = self.env['customer.wallet'].sudo().get_or_create_wallet(
            self.partner_id.id
        )
        wallet.add_credit(
            self.amount,
            f'Refund for Order {self.order_id.name}',
            self.id
        )
        return {
            'success': True,
            'reference': f'WALLET-{self.name}',
        }

    def _process_bank_refund(self):
        """Process bank transfer refund (manual)"""
        # Create bank transfer request for finance team
        self.env['bank.transfer.request'].create({
            'refund_id': self.id,
            'amount': self.amount,
            'bank_name': self.bank_name,
            'account_number': self.account_number,
            'account_holder': self.account_holder,
        })
        return {
            'success': True,
            'reference': f'BANK-{self.name}',
            'note': 'Bank transfer will be processed within 3-5 business days',
        }

    def _get_bkash_token(self):
        """Get bKash API token"""
        config = self.env['ir.config_parameter'].sudo()
        base_url = config.get_param('bkash.base_url')
        app_key = config.get_param('bkash.app_key')
        app_secret = config.get_param('bkash.app_secret')

        response = requests.post(
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

        if response.status_code == 200:
            data = response.json()
            return {
                'success': True,
                'id_token': data.get('id_token'),
            }
        return {'error': 'Failed to get bKash token'}

    def _send_notification(self, notification_type):
        """Send refund notification"""
        self.env['order.notification'].sudo().send_refund_notification(
            self, notification_type
        )
```

---

#### Dev 3 (Frontend Lead) - Days 391-392

**Focus: Order Tracking UI Foundation**

**Hour 1-4: Order Tracking OWL Component**

```javascript
/** @odoo-module **/
// File: smart_dairy_ecommerce/static/src/js/orders/order_tracking.js

import { Component, useState, onWillStart, onMounted, onWillUnmount } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class OrderTracking extends Component {
    static template = "smart_dairy_ecommerce.OrderTracking";
    static props = {
        orderId: { type: Number, required: true },
    };

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            loading: true,
            order: null,
            tracking: null,
            error: null,
            refreshInterval: null,
        });

        this.statusSteps = [
            { key: 'confirmed', label: 'Confirmed', icon: 'fa-check-circle' },
            { key: 'processing', label: 'Processing', icon: 'fa-cog' },
            { key: 'shipped', label: 'Shipped', icon: 'fa-truck' },
            { key: 'out_for_delivery', label: 'Out for Delivery', icon: 'fa-bicycle' },
            { key: 'delivered', label: 'Delivered', icon: 'fa-home' },
        ];

        onWillStart(async () => {
            await this.loadOrderTracking();
        });

        onMounted(() => {
            // Auto-refresh every 30 seconds for active orders
            if (this.isActiveOrder) {
                this.state.refreshInterval = setInterval(() => {
                    this.loadOrderTracking();
                }, 30000);
            }
        });

        onWillUnmount(() => {
            if (this.state.refreshInterval) {
                clearInterval(this.state.refreshInterval);
            }
        });
    }

    get isActiveOrder() {
        const activeStatuses = ['confirmed', 'processing', 'shipped', 'out_for_delivery'];
        return this.state.tracking && activeStatuses.includes(this.state.tracking.status);
    }

    get currentStepIndex() {
        if (!this.state.tracking) return -1;
        return this.statusSteps.findIndex(s => s.key === this.state.tracking.status);
    }

    async loadOrderTracking() {
        try {
            const result = await this.rpc(`/api/v1/orders/${this.props.orderId}/tracking`, {});

            if (result.success) {
                this.state.tracking = result.data;
                this.state.error = null;
            } else {
                this.state.error = result.error;
            }
        } catch (error) {
            this.state.error = 'Failed to load tracking information';
        } finally {
            this.state.loading = false;
        }
    }

    isStepCompleted(stepKey) {
        const stepIndex = this.statusSteps.findIndex(s => s.key === stepKey);
        return stepIndex <= this.currentStepIndex;
    }

    isStepActive(stepKey) {
        return this.state.tracking?.status === stepKey;
    }

    getStepClass(stepKey) {
        if (this.isStepActive(stepKey)) return 'active';
        if (this.isStepCompleted(stepKey)) return 'completed';
        return 'pending';
    }

    formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-BD', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    }

    getTimelineEntry(status) {
        return this.state.tracking?.timeline?.find(t => t.status === status);
    }

    async refreshTracking() {
        this.state.loading = true;
        await this.loadOrderTracking();
    }
}
```

**Hour 5-8: Order Tracking QWeb Template**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- File: smart_dairy_ecommerce/static/src/xml/order_tracking.xml -->

<templates xml:space="preserve">
    <t t-name="smart_dairy_ecommerce.OrderTracking">
        <div class="sd-order-tracking">
            <!-- Loading State -->
            <t t-if="state.loading">
                <div class="text-center py-5">
                    <i class="fa fa-spinner fa-spin fa-3x text-primary"/>
                    <p class="mt-3 text-muted">Loading tracking information...</p>
                </div>
            </t>

            <!-- Error State -->
            <t t-elif="state.error">
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle me-2"/>
                    <t t-esc="state.error"/>
                </div>
            </t>

            <!-- Tracking Content -->
            <t t-else="">
                <!-- Order Header -->
                <div class="sd-tracking-header mb-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="mb-1">Order <t t-esc="state.tracking.order_name"/></h4>
                            <p class="text-muted mb-0">
                                <t t-esc="state.tracking.status_display"/>
                            </p>
                        </div>
                        <button class="btn btn-outline-primary btn-sm" t-on-click="refreshTracking">
                            <i class="fa fa-refresh me-1"/>
                            Refresh
                        </button>
                    </div>
                </div>

                <!-- Progress Steps -->
                <div class="sd-progress-tracker mb-4">
                    <div class="progress-container">
                        <t t-foreach="statusSteps" t-as="step" t-key="step.key">
                            <div t-attf-class="progress-step #{getStepClass(step.key)}">
                                <div class="step-icon">
                                    <i t-attf-class="fa #{step.icon}"/>
                                </div>
                                <div class="step-label">
                                    <t t-esc="step.label"/>
                                </div>
                                <t t-if="getTimelineEntry(step.key)">
                                    <div class="step-time text-muted small">
                                        <t t-esc="formatDate(getTimelineEntry(step.key).timestamp)"/>
                                    </div>
                                </t>
                            </div>
                        </t>
                        <!-- Progress Line -->
                        <div class="progress-line">
                            <div class="progress-fill" t-attf-style="width: #{(currentStepIndex / (statusSteps.length - 1)) * 100}%"/>
                        </div>
                    </div>
                </div>

                <!-- Tracking Details -->
                <div class="row">
                    <!-- Left Column - Timeline -->
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fa fa-history me-2"/>
                                    Tracking Timeline
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="sd-timeline">
                                    <t t-foreach="state.tracking.timeline" t-as="event" t-key="event_index">
                                        <div class="timeline-item">
                                            <div class="timeline-marker">
                                                <i class="fa fa-circle"/>
                                            </div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1" t-esc="event.status_display"/>
                                                <p class="text-muted small mb-1" t-esc="event.notes"/>
                                                <span class="text-muted small">
                                                    <i class="fa fa-clock-o me-1"/>
                                                    <t t-esc="formatDate(event.timestamp)"/>
                                                </span>
                                            </div>
                                        </div>
                                    </t>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Details -->
                    <div class="col-md-5">
                        <!-- Tracking Number -->
                        <t t-if="state.tracking.tracking_number">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Tracking Number</h6>
                                    <div class="d-flex align-items-center">
                                        <code class="fs-5 me-2" t-esc="state.tracking.tracking_number"/>
                                        <button class="btn btn-sm btn-outline-secondary"
                                                t-on-click="() => this.copyTrackingNumber()">
                                            <i class="fa fa-copy"/>
                                        </button>
                                    </div>
                                    <t t-if="state.tracking.carrier">
                                        <p class="text-muted small mt-2 mb-0">
                                            Carrier: <t t-esc="state.tracking.carrier"/>
                                        </p>
                                    </t>
                                </div>
                            </div>
                        </t>

                        <!-- Estimated Delivery -->
                        <t t-if="state.tracking.estimated_delivery">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Estimated Delivery</h6>
                                    <p class="fs-5 mb-0">
                                        <i class="fa fa-calendar me-2 text-primary"/>
                                        <t t-esc="formatDate(state.tracking.estimated_delivery)"/>
                                    </p>
                                </div>
                            </div>
                        </t>

                        <!-- Live Location (if available) -->
                        <t t-if="state.tracking.current_location">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">
                                        <i class="fa fa-map-marker me-1 text-danger"/>
                                        Live Location
                                    </h6>
                                    <div id="tracking-map" class="sd-map-container" style="height: 200px;">
                                        <!-- Map will be rendered here -->
                                    </div>
                                    <p class="text-muted small mt-2 mb-0">
                                        Last updated: <t t-esc="formatDate(state.tracking.current_location.updated_at)"/>
                                    </p>
                                </div>
                            </div>
                        </t>

                        <!-- Actions -->
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-muted mb-3">Quick Actions</h6>
                                <div class="d-grid gap-2">
                                    <t t-if="state.tracking.is_cancellable">
                                        <button class="btn btn-outline-danger"
                                                t-on-click="() => this.env.bus.trigger('open-cancel-modal')">
                                            <i class="fa fa-times me-2"/>
                                            Cancel Order
                                        </button>
                                    </t>
                                    <t t-if="state.tracking.is_modifiable">
                                        <button class="btn btn-outline-primary"
                                                t-on-click="() => this.env.bus.trigger('open-modify-modal')">
                                            <i class="fa fa-edit me-2"/>
                                            Modify Order
                                        </button>
                                    </t>
                                    <a href="/contact" class="btn btn-outline-secondary">
                                        <i class="fa fa-headphones me-2"/>
                                        Contact Support
                                    </a>
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

---

### Day 393-394: Order Modification & Notification Services

#### Dev 1 (Backend Lead) - Days 393-394

**Focus: Order Modification Service**

```python
# File: smart_dairy_ecommerce/models/order_modification.py

from odoo import models, fields, api
from odoo.exceptions import UserError, ValidationError
from datetime import datetime

class OrderModification(models.Model):
    _name = 'order.modification'
    _description = 'Order Modification Request'
    _order = 'create_date desc'

    name = fields.Char(
        string='Reference',
        required=True,
        readonly=True,
        default='New',
        copy=False
    )
    order_id = fields.Many2one(
        'sale.order',
        string='Order',
        required=True,
        index=True
    )
    partner_id = fields.Many2one(
        'res.partner',
        string='Customer',
        related='order_id.partner_id'
    )

    # Modification Type
    modification_type = fields.Selection([
        ('quantity', 'Quantity Change'),
        ('add_item', 'Add Item'),
        ('remove_item', 'Remove Item'),
        ('address', 'Address Change'),
        ('delivery_slot', 'Delivery Slot Change'),
        ('instructions', 'Delivery Instructions'),
    ], string='Modification Type', required=True)

    # Changes JSON
    changes_data = fields.Text(
        string='Changes Data',
        help='JSON data of the modification'
    )

    # Status
    state = fields.Selection([
        ('pending', 'Pending'),
        ('approved', 'Approved'),
        ('rejected', 'Rejected'),
        ('applied', 'Applied'),
    ], string='Status', default='pending')

    # Price Impact
    price_difference = fields.Monetary(
        string='Price Difference',
        currency_field='currency_id'
    )
    requires_payment = fields.Boolean(
        string='Requires Additional Payment',
        compute='_compute_requires_payment'
    )
    currency_id = fields.Many2one(
        'res.currency',
        related='order_id.currency_id'
    )

    # Timestamps
    applied_at = fields.Datetime(string='Applied At')
    applied_by = fields.Many2one('res.users', string='Applied By')

    @api.model
    def create(self, vals):
        if vals.get('name', 'New') == 'New':
            vals['name'] = self.env['ir.sequence'].next_by_code('order.modification') or 'New'
        return super().create(vals)

    @api.depends('price_difference')
    def _compute_requires_payment(self):
        for mod in self:
            mod.requires_payment = mod.price_difference > 0

    @api.model
    def process_modification(self, order, modifications):
        """Process order modification request"""
        import json

        if not order.is_modifiable:
            return {'error': 'Order cannot be modified at this stage'}

        # Parse modifications
        mod_type = modifications.get('type')
        changes = modifications.get('changes', {})

        # Calculate price impact
        price_diff = 0

        if mod_type == 'quantity':
            price_diff = self._calculate_quantity_change_impact(order, changes)
        elif mod_type == 'add_item':
            price_diff = self._calculate_add_item_impact(changes)
        elif mod_type == 'remove_item':
            price_diff = self._calculate_remove_item_impact(order, changes)

        # Create modification record
        modification = self.create({
            'order_id': order.id,
            'modification_type': mod_type,
            'changes_data': json.dumps(changes),
            'price_difference': price_diff,
        })

        # Auto-approve if no additional payment or refund below threshold
        if price_diff <= 0 or price_diff <= 500:  # Auto-approve up to 500 BDT
            modification.action_approve()
            modification.action_apply()
            return {
                'success': True,
                'message': 'Modification applied successfully',
                'data': {
                    'modification_id': modification.id,
                    'price_difference': price_diff,
                    'new_total': order.amount_total,
                }
            }
        else:
            return {
                'success': True,
                'message': 'Modification requires additional payment',
                'data': {
                    'modification_id': modification.id,
                    'requires_payment': True,
                    'price_difference': price_diff,
                }
            }

    def _calculate_quantity_change_impact(self, order, changes):
        """Calculate price impact of quantity changes"""
        price_diff = 0
        for change in changes.get('items', []):
            line = order.order_line.filtered(
                lambda l: l.product_id.id == change['product_id']
            )
            if line:
                qty_diff = change['new_quantity'] - line.product_uom_qty
                price_diff += qty_diff * line.price_unit
        return price_diff

    def _calculate_add_item_impact(self, changes):
        """Calculate price impact of adding items"""
        price_diff = 0
        for item in changes.get('items', []):
            product = self.env['product.product'].browse(item['product_id'])
            if product.exists():
                price_diff += product.lst_price * item['quantity']
        return price_diff

    def _calculate_remove_item_impact(self, order, changes):
        """Calculate price impact of removing items"""
        price_diff = 0
        for item in changes.get('items', []):
            line = order.order_line.filtered(
                lambda l: l.product_id.id == item['product_id']
            )
            if line:
                price_diff -= line.price_subtotal
        return price_diff

    def action_approve(self):
        """Approve modification"""
        self.write({'state': 'approved'})
        return True

    def action_reject(self, reason=''):
        """Reject modification"""
        self.write({
            'state': 'rejected',
        })
        return True

    def action_apply(self):
        """Apply modification to order"""
        self.ensure_one()
        import json

        if self.state != 'approved':
            raise UserError('Modification must be approved first')

        order = self.order_id
        changes = json.loads(self.changes_data or '{}')

        if self.modification_type == 'quantity':
            self._apply_quantity_changes(order, changes)
        elif self.modification_type == 'add_item':
            self._apply_add_items(order, changes)
        elif self.modification_type == 'remove_item':
            self._apply_remove_items(order, changes)
        elif self.modification_type == 'address':
            self._apply_address_change(order, changes)
        elif self.modification_type == 'delivery_slot':
            self._apply_slot_change(order, changes)
        elif self.modification_type == 'instructions':
            self._apply_instructions_change(order, changes)

        self.write({
            'state': 'applied',
            'applied_at': fields.Datetime.now(),
            'applied_by': self.env.user.id,
        })

        order.modification_count += 1
        order._log_status_change(order.order_status, f'Order modified: {self.modification_type}')

        return True

    def _apply_quantity_changes(self, order, changes):
        """Apply quantity changes to order lines"""
        for change in changes.get('items', []):
            line = order.order_line.filtered(
                lambda l: l.product_id.id == change['product_id']
            )
            if line:
                if change['new_quantity'] <= 0:
                    line.unlink()
                else:
                    line.product_uom_qty = change['new_quantity']

    def _apply_add_items(self, order, changes):
        """Add new items to order"""
        for item in changes.get('items', []):
            product = self.env['product.product'].browse(item['product_id'])
            if product.exists():
                self.env['sale.order.line'].create({
                    'order_id': order.id,
                    'product_id': product.id,
                    'product_uom_qty': item['quantity'],
                    'price_unit': product.lst_price,
                })

    def _apply_remove_items(self, order, changes):
        """Remove items from order"""
        for item in changes.get('items', []):
            line = order.order_line.filtered(
                lambda l: l.product_id.id == item['product_id']
            )
            if line:
                line.unlink()

    def _apply_address_change(self, order, changes):
        """Apply shipping address change"""
        address_id = changes.get('address_id')
        if address_id:
            order.partner_shipping_id = address_id

    def _apply_slot_change(self, order, changes):
        """Apply delivery slot change"""
        slot_id = changes.get('slot_id')
        if slot_id:
            order.delivery_slot_id = slot_id

    def _apply_instructions_change(self, order, changes):
        """Apply delivery instructions change"""
        instructions = changes.get('instructions')
        if instructions:
            order.delivery_instructions = instructions
```

**Notification Service**

```python
# File: smart_dairy_ecommerce/models/order_notification.py

from odoo import models, fields, api
import json

class OrderNotification(models.Model):
    _name = 'order.notification'
    _description = 'Order Notification Service'

    # Notification Templates
    NOTIFICATION_TEMPLATES = {
        'order_confirmed': {
            'subject': 'Order Confirmed - {order_name}',
            'template': 'smart_dairy_ecommerce.email_order_confirmed',
            'sms_message': 'Your order {order_name} has been confirmed. Track: {tracking_url}',
        },
        'order_processing': {
            'subject': 'Order Being Prepared - {order_name}',
            'template': 'smart_dairy_ecommerce.email_order_processing',
            'sms_message': 'Good news! Your order {order_name} is being prepared.',
        },
        'order_shipped': {
            'subject': 'Order Shipped - {order_name}',
            'template': 'smart_dairy_ecommerce.email_order_shipped',
            'sms_message': 'Your order {order_name} has been shipped! Tracking: {tracking_number}',
        },
        'out_for_delivery': {
            'subject': 'Out for Delivery - {order_name}',
            'template': 'smart_dairy_ecommerce.email_out_for_delivery',
            'sms_message': 'Your order {order_name} is out for delivery! Arriving today.',
        },
        'order_delivered': {
            'subject': 'Order Delivered - {order_name}',
            'template': 'smart_dairy_ecommerce.email_order_delivered',
            'sms_message': 'Your order {order_name} has been delivered. Thank you for shopping!',
        },
        'order_cancelled': {
            'subject': 'Order Cancelled - {order_name}',
            'template': 'smart_dairy_ecommerce.email_order_cancelled',
            'sms_message': 'Your order {order_name} has been cancelled. Refund will be processed.',
        },
        'delivery_rescheduled': {
            'subject': 'Delivery Rescheduled - {order_name}',
            'template': 'smart_dairy_ecommerce.email_delivery_rescheduled',
            'sms_message': 'Delivery for {order_name} has been rescheduled.',
        },
    }

    @api.model
    def send_notification(self, order, notification_type):
        """Send order notification via all enabled channels"""
        if notification_type not in self.NOTIFICATION_TEMPLATES:
            return False

        template_config = self.NOTIFICATION_TEMPLATES[notification_type]
        partner = order.partner_id

        # Prepare context
        context = {
            'order_name': order.name,
            'tracking_number': order.delivery_tracking_number or '',
            'tracking_url': f'/my/orders/{order.id}/tracking',
        }

        # Send email
        if partner.email:
            self._send_email(order, template_config, context)

        # Send SMS
        if partner.phone and self._is_sms_enabled():
            self._send_sms(partner.phone, template_config, context)

        # Send push notification
        if self._has_push_subscription(partner.id):
            self._send_push(partner.id, template_config, context)

        # Log notification
        self._log_notification(order.id, notification_type, partner.id)

        return True

    def _send_email(self, order, template_config, context):
        """Send email notification"""
        template = self.env.ref(template_config['template'], raise_if_not_found=False)
        if template:
            template.with_context(**context).send_mail(order.id, force_send=True)

    def _send_sms(self, phone, template_config, context):
        """Send SMS notification"""
        message = template_config['sms_message'].format(**context)

        # SMS Gateway integration
        config = self.env['ir.config_parameter'].sudo()
        sms_provider = config.get_param('sms.provider', 'ssl_wireless')

        if sms_provider == 'ssl_wireless':
            self._send_ssl_wireless_sms(phone, message)
        elif sms_provider == 'infobip':
            self._send_infobip_sms(phone, message)

    def _send_ssl_wireless_sms(self, phone, message):
        """Send SMS via SSL Wireless"""
        import requests

        config = self.env['ir.config_parameter'].sudo()
        api_url = config.get_param('ssl_wireless.api_url')
        api_token = config.get_param('ssl_wireless.api_token')
        sender_id = config.get_param('ssl_wireless.sender_id')

        try:
            response = requests.post(api_url, json={
                'api_token': api_token,
                'sid': sender_id,
                'msisdn': phone,
                'sms': message,
                'csms_id': f'ORDER-{fields.Datetime.now().timestamp()}',
            })
            return response.status_code == 200
        except Exception:
            return False

    def _send_push(self, partner_id, template_config, context):
        """Send push notification"""
        subscriptions = self.env['push.subscription'].search([
            ('partner_id', '=', partner_id),
            ('active', '=', True)
        ])

        for sub in subscriptions:
            self._send_web_push(sub, {
                'title': template_config['subject'].format(**context),
                'body': template_config['sms_message'].format(**context),
                'url': context.get('tracking_url'),
            })

    def _send_web_push(self, subscription, payload):
        """Send web push notification"""
        from pywebpush import webpush, WebPushException
        import json

        config = self.env['ir.config_parameter'].sudo()
        vapid_private = config.get_param('webpush.vapid_private_key')
        vapid_email = config.get_param('webpush.vapid_email')

        try:
            webpush(
                subscription_info=json.loads(subscription.subscription_data),
                data=json.dumps(payload),
                vapid_private_key=vapid_private,
                vapid_claims={'sub': f'mailto:{vapid_email}'}
            )
        except WebPushException:
            subscription.active = False

    def _is_sms_enabled(self):
        """Check if SMS notifications are enabled"""
        config = self.env['ir.config_parameter'].sudo()
        return config.get_param('notifications.sms_enabled', 'False') == 'True'

    def _has_push_subscription(self, partner_id):
        """Check if partner has push subscription"""
        return self.env['push.subscription'].search_count([
            ('partner_id', '=', partner_id),
            ('active', '=', True)
        ]) > 0

    def _log_notification(self, order_id, notification_type, partner_id):
        """Log notification for audit"""
        self.env['notification.log'].create({
            'order_id': order_id,
            'notification_type': notification_type,
            'partner_id': partner_id,
            'sent_at': fields.Datetime.now(),
        })

    @api.model
    def send_return_notification(self, return_request, notification_type):
        """Send return-related notification"""
        partner = return_request.partner_id
        context = {
            'rma_number': return_request.name,
            'order_name': return_request.order_id.name,
        }
        # Similar implementation as order notifications
        pass

    @api.model
    def send_refund_notification(self, refund, notification_type):
        """Send refund-related notification"""
        partner = refund.partner_id
        context = {
            'refund_ref': refund.name,
            'order_name': refund.order_id.name,
            'amount': refund.amount,
        }
        # Similar implementation as order notifications
        pass


class NotificationLog(models.Model):
    _name = 'notification.log'
    _description = 'Notification Log'
    _order = 'sent_at desc'

    order_id = fields.Many2one('sale.order', string='Order')
    notification_type = fields.Char(string='Type')
    partner_id = fields.Many2one('res.partner', string='Recipient')
    channel = fields.Selection([
        ('email', 'Email'),
        ('sms', 'SMS'),
        ('push', 'Push'),
    ], string='Channel')
    sent_at = fields.Datetime(string='Sent At')
    status = fields.Selection([
        ('sent', 'Sent'),
        ('failed', 'Failed'),
    ], string='Status', default='sent')
```

---

#### Dev 2 (Full-Stack) - Days 393-394

**Focus: Invoice PDF Generator**

```python
# File: smart_dairy_ecommerce/models/invoice_generator.py

from odoo import models, fields, api
from odoo.exceptions import UserError
import base64
from io import BytesIO

class InvoicePDFGenerator(models.Model):
    _name = 'invoice.pdf.generator'
    _description = 'Invoice PDF Generator'

    @api.model
    def generate_invoice_pdf(self, order_id):
        """Generate PDF invoice for order"""
        order = self.env['sale.order'].browse(order_id)
        if not order.exists():
            raise UserError('Order not found')

        # Generate PDF using QWeb report
        report = self.env.ref('smart_dairy_ecommerce.report_order_invoice')
        pdf_content, content_type = report._render_qweb_pdf([order_id])

        # Store PDF
        attachment = self.env['ir.attachment'].create({
            'name': f'Invoice_{order.name}.pdf',
            'type': 'binary',
            'datas': base64.b64encode(pdf_content),
            'res_model': 'sale.order',
            'res_id': order_id,
            'mimetype': 'application/pdf',
        })

        return {
            'attachment_id': attachment.id,
            'filename': attachment.name,
            'download_url': f'/web/content/{attachment.id}?download=true',
        }

    @api.model
    def get_or_generate_invoice(self, order_id):
        """Get existing invoice or generate new one"""
        order = self.env['sale.order'].browse(order_id)

        # Check for existing attachment
        existing = self.env['ir.attachment'].search([
            ('res_model', '=', 'sale.order'),
            ('res_id', '=', order_id),
            ('name', 'like', 'Invoice_%'),
        ], limit=1)

        if existing:
            return {
                'attachment_id': existing.id,
                'filename': existing.name,
                'download_url': f'/web/content/{existing.id}?download=true',
            }

        return self.generate_invoice_pdf(order_id)


class SaleOrder(models.Model):
    _inherit = 'sale.order'

    invoice_attachment_id = fields.Many2one(
        'ir.attachment',
        string='Invoice PDF'
    )

    def action_generate_invoice_pdf(self):
        """Generate invoice PDF for this order"""
        self.ensure_one()
        result = self.env['invoice.pdf.generator'].generate_invoice_pdf(self.id)
        self.invoice_attachment_id = result['attachment_id']
        return result

    def action_download_invoice(self):
        """Download invoice PDF"""
        self.ensure_one()
        if not self.invoice_attachment_id:
            self.action_generate_invoice_pdf()

        return {
            'type': 'ir.actions.act_url',
            'url': f'/web/content/{self.invoice_attachment_id.id}?download=true',
            'target': 'new',
        }
```

**Invoice QWeb Report Template**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- File: smart_dairy_ecommerce/reports/invoice_template.xml -->

<odoo>
    <template id="report_order_invoice_document">
        <t t-call="web.html_container">
            <t t-foreach="docs" t-as="o">
                <t t-call="web.external_layout">
                    <div class="page">
                        <!-- Invoice Header -->
                        <div class="row mb-4">
                            <div class="col-6">
                                <img t-if="o.company_id.logo"
                                     t-att-src="image_data_uri(o.company_id.logo)"
                                     style="max-height: 80px;"/>
                                <h2 class="mt-2">TAX INVOICE</h2>
                            </div>
                            <div class="col-6 text-end">
                                <h3 t-field="o.name"/>
                                <p>
                                    <strong>Date:</strong>
                                    <span t-field="o.date_order" t-options='{"format": "dd/MM/yyyy"}'/>
                                </p>
                            </div>
                        </div>

                        <!-- Customer Details -->
                        <div class="row mb-4">
                            <div class="col-6">
                                <h5>Bill To:</h5>
                                <address>
                                    <strong t-field="o.partner_id.name"/><br/>
                                    <span t-field="o.partner_id.street"/><br/>
                                    <span t-field="o.partner_id.city"/><br/>
                                    <span t-field="o.partner_id.phone"/>
                                </address>
                            </div>
                            <div class="col-6">
                                <h5>Ship To:</h5>
                                <address>
                                    <strong t-field="o.partner_shipping_id.name"/><br/>
                                    <span t-field="o.partner_shipping_id.street"/><br/>
                                    <span t-field="o.partner_shipping_id.city"/><br/>
                                    <span t-field="o.partner_shipping_id.phone"/>
                                </address>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Tax</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <t t-foreach="o.order_line" t-as="line">
                                    <tr>
                                        <td t-esc="line_index + 1"/>
                                        <td>
                                            <strong t-field="line.product_id.name"/>
                                            <t t-if="line.product_id.default_code">
                                                <br/>
                                                <small class="text-muted">
                                                    SKU: <t t-esc="line.product_id.default_code"/>
                                                </small>
                                            </t>
                                        </td>
                                        <td class="text-center" t-field="line.product_uom_qty"/>
                                        <td class="text-end" t-field="line.price_unit"
                                            t-options='{"widget": "monetary", "display_currency": o.currency_id}'/>
                                        <td class="text-end" t-field="line.price_tax"
                                            t-options='{"widget": "monetary", "display_currency": o.currency_id}'/>
                                        <td class="text-end" t-field="line.price_subtotal"
                                            t-options='{"widget": "monetary", "display_currency": o.currency_id}'/>
                                    </tr>
                                </t>
                            </tbody>
                        </table>

                        <!-- Totals -->
                        <div class="row">
                            <div class="col-6"/>
                            <div class="col-6">
                                <table class="table">
                                    <tr>
                                        <td><strong>Subtotal:</strong></td>
                                        <td class="text-end" t-field="o.amount_untaxed"
                                            t-options='{"widget": "monetary", "display_currency": o.currency_id}'/>
                                    </tr>
                                    <tr>
                                        <td><strong>Tax:</strong></td>
                                        <td class="text-end" t-field="o.amount_tax"
                                            t-options='{"widget": "monetary", "display_currency": o.currency_id}'/>
                                    </tr>
                                    <t t-if="o.delivery_price">
                                        <tr>
                                            <td><strong>Shipping:</strong></td>
                                            <td class="text-end">
                                                <span t-esc="o.delivery_price"
                                                      t-options='{"widget": "monetary", "display_currency": o.currency_id}'/>
                                            </td>
                                        </tr>
                                    </t>
                                    <tr class="border-top">
                                        <td><strong>Total:</strong></td>
                                        <td class="text-end">
                                            <strong t-field="o.amount_total"
                                                    t-options='{"widget": "monetary", "display_currency": o.currency_id}'/>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Payment Info -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <p>
                                    <strong>Payment Method:</strong>
                                    <span t-esc="dict(o._fields['payment_method'].selection).get(o.payment_method, 'N/A')"/>
                                </p>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <p class="text-muted">
                                    Thank you for shopping with Smart Dairy!
                                </p>
                                <p class="small text-muted">
                                    This is a computer-generated invoice and does not require a signature.
                                </p>
                            </div>
                        </div>
                    </div>
                </t>
            </t>
        </t>
    </template>

    <record id="report_order_invoice" model="ir.actions.report">
        <field name="name">Order Invoice</field>
        <field name="model">sale.order</field>
        <field name="report_type">qweb-pdf</field>
        <field name="report_name">smart_dairy_ecommerce.report_order_invoice_document</field>
        <field name="report_file">smart_dairy_ecommerce.report_order_invoice_document</field>
        <field name="print_report_name">'Invoice_%s' % object.name</field>
        <field name="binding_model_id" ref="sale.model_sale_order"/>
        <field name="binding_type">report</field>
    </record>
</odoo>
```

---

#### Dev 3 (Frontend Lead) - Days 393-394

**Focus: Order History Dashboard & Return Request UI**

```javascript
/** @odoo-module **/
// File: smart_dairy_ecommerce/static/src/js/orders/order_history.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class OrderHistory extends Component {
    static template = "smart_dairy_ecommerce.OrderHistory";

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            loading: true,
            orders: [],
            pagination: {
                page: 1,
                limit: 10,
                total: 0,
                pages: 0,
            },
            filter: 'all',
            selectedOrder: null,
        });

        this.filterOptions = [
            { key: 'all', label: 'All Orders' },
            { key: 'confirmed', label: 'Confirmed' },
            { key: 'processing', label: 'Processing' },
            { key: 'shipped', label: 'Shipped' },
            { key: 'delivered', label: 'Delivered' },
            { key: 'cancelled', label: 'Cancelled' },
        ];

        onWillStart(async () => {
            await this.loadOrders();
        });
    }

    async loadOrders() {
        try {
            this.state.loading = true;
            const params = {
                page: this.state.pagination.page,
                limit: this.state.pagination.limit,
            };

            if (this.state.filter !== 'all') {
                params.status = this.state.filter;
            }

            const result = await this.rpc('/api/v1/orders', params);

            if (result.success) {
                this.state.orders = result.data.orders;
                this.state.pagination = result.data.pagination;
            }
        } catch (error) {
            this.notification.add('Failed to load orders', { type: 'danger' });
        } finally {
            this.state.loading = false;
        }
    }

    async changeFilter(filter) {
        this.state.filter = filter;
        this.state.pagination.page = 1;
        await this.loadOrders();
    }

    async goToPage(page) {
        this.state.pagination.page = page;
        await this.loadOrders();
    }

    viewOrder(orderId) {
        window.location.href = `/my/orders/${orderId}`;
    }

    trackOrder(orderId) {
        window.location.href = `/my/orders/${orderId}/tracking`;
    }

    async downloadInvoice(orderId) {
        try {
            const result = await this.rpc(`/api/v1/orders/${orderId}/invoice`, {});
            if (result.success) {
                window.open(result.data.download_url, '_blank');
            } else {
                this.notification.add(result.error, { type: 'danger' });
            }
        } catch (error) {
            this.notification.add('Failed to download invoice', { type: 'danger' });
        }
    }

    reorderItems(orderId) {
        window.location.href = `/shop/reorder/${orderId}`;
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-BD', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        });
    }

    formatCurrency(amount) {
        return `৳${amount.toLocaleString('en-BD')}`;
    }

    getStatusBadgeClass(status) {
        const classes = {
            pending: 'bg-warning',
            confirmed: 'bg-info',
            processing: 'bg-primary',
            shipped: 'bg-primary',
            out_for_delivery: 'bg-info',
            delivered: 'bg-success',
            cancelled: 'bg-danger',
            return_requested: 'bg-warning',
            refunded: 'bg-secondary',
        };
        return classes[status] || 'bg-secondary';
    }
}
```

**Order History QWeb Template**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- File: smart_dairy_ecommerce/static/src/xml/order_history.xml -->

<templates xml:space="preserve">
    <t t-name="smart_dairy_ecommerce.OrderHistory">
        <div class="sd-order-history">
            <div class="container py-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>My Orders</h3>
                    <a href="/shop" class="btn btn-primary">
                        <i class="fa fa-shopping-cart me-2"/>
                        Continue Shopping
                    </a>
                </div>

                <!-- Filters -->
                <div class="sd-filters mb-4">
                    <div class="btn-group" role="group">
                        <t t-foreach="filterOptions" t-as="opt" t-key="opt.key">
                            <button t-attf-class="btn #{state.filter === opt.key ? 'btn-primary' : 'btn-outline-primary'}"
                                    t-on-click="() => this.changeFilter(opt.key)">
                                <t t-esc="opt.label"/>
                            </button>
                        </t>
                    </div>
                </div>

                <!-- Loading -->
                <t t-if="state.loading">
                    <div class="text-center py-5">
                        <i class="fa fa-spinner fa-spin fa-3x"/>
                    </div>
                </t>

                <!-- Orders List -->
                <t t-elif="state.orders.length">
                    <div class="sd-orders-list">
                        <t t-foreach="state.orders" t-as="order" t-key="order.id">
                            <div class="card mb-3 sd-order-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong t-esc="order.name"/>
                                        <span class="text-muted ms-2">
                                            <t t-esc="formatDate(order.date)"/>
                                        </span>
                                    </div>
                                    <span t-attf-class="badge #{getStatusBadgeClass(order.status)}">
                                        <t t-esc="order.status_display"/>
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <p class="mb-1">
                                                <strong><t t-esc="order.item_count"/></strong> item(s)
                                            </p>
                                            <p class="mb-0 fs-5">
                                                <strong t-esc="formatCurrency(order.total)"/>
                                            </p>
                                        </div>
                                        <div class="col-md-6 text-md-end">
                                            <div class="btn-group">
                                                <button class="btn btn-outline-primary btn-sm"
                                                        t-on-click="() => this.viewOrder(order.id)">
                                                    <i class="fa fa-eye me-1"/>
                                                    View Details
                                                </button>
                                                <t t-if="['confirmed', 'processing', 'shipped', 'out_for_delivery'].includes(order.status)">
                                                    <button class="btn btn-primary btn-sm"
                                                            t-on-click="() => this.trackOrder(order.id)">
                                                        <i class="fa fa-map-marker me-1"/>
                                                        Track
                                                    </button>
                                                </t>
                                                <t t-if="order.status === 'delivered'">
                                                    <button class="btn btn-outline-secondary btn-sm"
                                                            t-on-click="() => this.downloadInvoice(order.id)">
                                                        <i class="fa fa-download me-1"/>
                                                        Invoice
                                                    </button>
                                                    <button class="btn btn-success btn-sm"
                                                            t-on-click="() => this.reorderItems(order.id)">
                                                        <i class="fa fa-refresh me-1"/>
                                                        Reorder
                                                    </button>
                                                </t>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </t>
                    </div>

                    <!-- Pagination -->
                    <t t-if="state.pagination.pages > 1">
                        <nav class="mt-4">
                            <ul class="pagination justify-content-center">
                                <li t-attf-class="page-item #{state.pagination.page === 1 ? 'disabled' : ''}">
                                    <button class="page-link"
                                            t-on-click="() => this.goToPage(state.pagination.page - 1)">
                                        Previous
                                    </button>
                                </li>
                                <t t-foreach="Array.from({length: state.pagination.pages}, (_, i) => i + 1)"
                                   t-as="pageNum" t-key="pageNum">
                                    <li t-attf-class="page-item #{state.pagination.page === pageNum ? 'active' : ''}">
                                        <button class="page-link"
                                                t-on-click="() => this.goToPage(pageNum)">
                                            <t t-esc="pageNum"/>
                                        </button>
                                    </li>
                                </t>
                                <li t-attf-class="page-item #{state.pagination.page === state.pagination.pages ? 'disabled' : ''}">
                                    <button class="page-link"
                                            t-on-click="() => this.goToPage(state.pagination.page + 1)">
                                        Next
                                    </button>
                                </li>
                            </ul>
                        </nav>
                    </t>
                </t>

                <!-- Empty State -->
                <t t-else="">
                    <div class="text-center py-5">
                        <i class="fa fa-shopping-bag fa-4x text-muted mb-3"/>
                        <h5>No orders found</h5>
                        <p class="text-muted">Start shopping to see your orders here</p>
                        <a href="/shop" class="btn btn-primary">
                            Browse Products
                        </a>
                    </div>
                </t>
            </div>
        </div>
    </t>
</templates>
```

---

### Days 395-400: Return UI, Integration & Testing

The remaining days (395-400) follow similar implementation patterns for:

- **Day 395-396**: Return request form UI, cancellation modal
- **Day 397-398**: Order detail page, delivery reschedule UI
- **Day 399-400**: Integration testing, bug fixes, documentation

---

## 6. Technical Specifications

### 6.1 API Endpoints

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/v1/orders` | GET | User | List customer orders |
| `/api/v1/orders/<id>` | GET | User | Get order details |
| `/api/v1/orders/<id>/tracking` | GET | User | Get tracking info |
| `/api/v1/orders/<id>/cancel` | POST | User | Cancel order |
| `/api/v1/orders/<id>/modify` | POST | User | Modify order |
| `/api/v1/orders/<id>/reschedule` | POST | User | Reschedule delivery |
| `/api/v1/orders/<id>/return` | POST | User | Create return request |
| `/api/v1/orders/<id>/invoice` | GET | User | Get/generate invoice |
| `/api/v1/returns/<id>` | GET | User | Get return status |
| `/api/v1/returns/<id>/pickup` | POST | User | Schedule pickup |

### 6.2 Database Schema

| Table | Description |
|-------|-------------|
| `order.status.history` | Order status timeline |
| `order.return.request` | RMA requests |
| `order.return.line` | Return line items |
| `order.refund` | Refund records |
| `order.modification` | Modification requests |
| `notification.log` | Notification audit |

### 6.3 Performance Targets

| Metric | Target |
|--------|--------|
| Order list load | < 1s |
| Tracking update | < 500ms |
| Invoice generation | < 3s |
| Notification delivery | < 30s |

---

## 7. Testing Requirements

### 7.1 Unit Test Coverage

```python
# File: smart_dairy_ecommerce/tests/test_order_management.py

from odoo.tests import TransactionCase, tagged

@tagged('post_install', '-at_install')
class TestOrderLifecycle(TransactionCase):

    def setUp(self):
        super().setUp()
        self.partner = self.env['res.partner'].create({
            'name': 'Test Customer',
            'email': 'test@example.com',
        })
        self.product = self.env['product.product'].create({
            'name': 'Test Product',
            'list_price': 100,
            'type': 'product',
        })
        self.order = self.env['sale.order'].create({
            'partner_id': self.partner.id,
            'order_line': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 2,
            })],
        })

    def test_order_confirmation(self):
        """Test order confirmation flow"""
        self.order.action_confirm_order()
        self.assertEqual(self.order.order_status, 'confirmed')
        self.assertTrue(self.order.confirmed_at)

    def test_order_cancellation(self):
        """Test order cancellation"""
        self.order.action_confirm_order()
        self.assertTrue(self.order.is_cancellable)

        self.order.action_cancel_order('changed_mind', 'Testing')
        self.assertEqual(self.order.order_status, 'cancelled')
        self.assertEqual(self.order.cancellation_reason, 'changed_mind')

    def test_order_not_cancellable_after_shipping(self):
        """Test that shipped orders cannot be cancelled"""
        self.order.action_confirm_order()
        self.order.action_start_processing()
        self.order.action_ship_order('TRACK123', 'Test Carrier')

        self.assertFalse(self.order.is_cancellable)

    def test_status_history_tracking(self):
        """Test status history is recorded"""
        self.order.action_confirm_order()
        self.order.action_start_processing()

        history = self.order.status_history_ids
        self.assertEqual(len(history), 2)


@tagged('post_install', '-at_install')
class TestReturnManagement(TransactionCase):

    def setUp(self):
        super().setUp()
        # Setup delivered order
        self.partner = self.env['res.partner'].create({
            'name': 'Test Customer',
            'email': 'test@example.com',
        })
        self.product = self.env['product.product'].create({
            'name': 'Test Product',
            'list_price': 100,
        })
        self.order = self.env['sale.order'].create({
            'partner_id': self.partner.id,
            'order_status': 'delivered',
            'delivered_at': fields.Datetime.now(),
            'order_line': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 2,
                'price_unit': 100,
            })],
        })

    def test_return_request_creation(self):
        """Test return request creation"""
        return_req = self.env['order.return.request'].create({
            'order_id': self.order.id,
            'return_reason': 'defective',
            'line_ids': [(0, 0, {
                'order_line_id': self.order.order_line[0].id,
                'quantity': 1,
                'condition': 'unopened',
            })],
        })

        self.assertEqual(return_req.state, 'draft')
        self.assertEqual(return_req.refund_amount, 100)  # 1 x 100

    def test_return_approval_flow(self):
        """Test return approval workflow"""
        return_req = self.env['order.return.request'].create({
            'order_id': self.order.id,
            'return_reason': 'defective',
            'line_ids': [(0, 0, {
                'order_line_id': self.order.order_line[0].id,
                'quantity': 1,
                'condition': 'unopened',
            })],
        })

        return_req.action_submit_request()
        self.assertEqual(return_req.state, 'requested')

        return_req.action_approve()
        self.assertEqual(return_req.state, 'approved')
```

### 7.2 Integration Tests

| Test Case | Description |
|-----------|-------------|
| Order lifecycle | Verify complete order flow |
| Cancellation with refund | Test cancellation triggers refund |
| Return to refund | Test return approval creates refund |
| Notification delivery | Verify notifications sent at each stage |
| Invoice generation | Test PDF generation accuracy |

---

## 8. Sign-off Checklist

### 8.1 Functional Requirements

- [ ] Order status tracking displays correctly
- [ ] Real-time tracking updates work
- [ ] Order modification before dispatch works
- [ ] Order cancellation with reason works
- [ ] Returns/RMA creation works
- [ ] Refund processing completes
- [ ] Delivery rescheduling works
- [ ] Invoice PDF generates correctly
- [ ] All notifications delivered

### 8.2 Non-Functional Requirements

- [ ] Order list loads < 1s
- [ ] Tracking updates < 500ms
- [ ] Invoice generation < 3s
- [ ] Mobile responsive design
- [ ] Accessibility compliant

### 8.3 Security Requirements

- [ ] Users can only view their own orders
- [ ] Modification rules enforced
- [ ] Cancellation rules enforced
- [ ] Return window validated

---

**Document End**

*Last Updated: 2025-01-15*
*Version: 1.0*
