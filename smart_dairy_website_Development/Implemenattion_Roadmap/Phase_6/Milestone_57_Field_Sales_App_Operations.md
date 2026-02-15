# Milestone 57: Field Sales App Operations

## Smart Dairy Digital Smart Portal + ERP System
### Phase 6: Mobile App Foundation

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-P6-M57-2026 |
| **Milestone Number** | 57 |
| **Milestone Title** | Field Sales App Operations |
| **Phase** | 6 - Mobile App Foundation |
| **Sprint Days** | Days 311-320 (10 working days) |
| **Duration** | 2 weeks |
| **Version** | 1.0 |
| **Status** | Draft |
| **Created Date** | 2026-02-03 |
| **Last Updated** | 2026-02-03 |
| **Author** | Technical Architecture Team |
| **Reviewers** | Project Manager, Mobile Lead, QA Lead |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Requirement Traceability Matrix](#2-requirement-traceability-matrix)
3. [Day-by-Day Breakdown](#3-day-by-day-breakdown)
4. [Technical Specifications](#4-technical-specifications)
5. [Testing & Validation](#5-testing--validation)
6. [Risk & Mitigation](#6-risk--mitigation)
7. [Dependencies & Handoffs](#7-dependencies--handoffs)

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Complete Field Sales App operational features including quick order entry with offline capability, route optimization with Google Maps integration, customer visit tracking with check-in/check-out, payment collection, daily sales reports, and target tracking dashboard.

### 1.2 Objectives

| # | Objective | Priority | Success Metric |
|---|-----------|----------|----------------|
| 1 | Implement quick order entry (offline capable) | Critical | Orders created <30 seconds |
| 2 | Build route optimization with Google Maps | Critical | >95% accuracy |
| 3 | Create customer visit tracking (check-in/out) | Critical | GPS verified visits |
| 4 | Implement payment collection flow | High | Cash/cheque recording |
| 5 | Build daily sales reports | High | Real-time sync |
| 6 | Create target tracking dashboard | High | Daily/weekly/monthly views |
| 7 | Implement order history per customer | Medium | Full order visibility |
| 8 | Build stock availability display | Medium | Real-time stock sync |
| 9 | Create return/exchange handling | Medium | Returns processed offline |
| 10 | Implement supervisor approval workflow | Low | Discount approvals |

### 1.3 Key Deliverables

| # | Deliverable | Type | Owner | Acceptance Criteria |
|---|-------------|------|-------|---------------------|
| 1 | Order Entry APIs | Odoo REST | Dev 1 | Create, update, sync |
| 2 | Quick Order Screen | Flutter UI | Dev 3 | <30 second entry |
| 3 | Route Planning API | Odoo REST | Dev 1 | Optimized sequences |
| 4 | Route Map Screen | Flutter UI | Dev 3 | Google Maps integrated |
| 5 | Visit Check-in/out | Full Stack | Dev 1/3 | GPS + timestamp |
| 6 | Payment Collection | Full Stack | Dev 1/3 | Cash, cheque, MFS |
| 7 | Sales Reports API | Odoo REST | Dev 1 | Daily summaries |
| 8 | Reports Dashboard | Flutter UI | Dev 3 | Charts, exports |
| 9 | Target Tracking | Full Stack | Dev 2 | Progress indicators |
| 10 | Offline Order Queue | Flutter | Dev 2 | Priority sync |

### 1.4 Prerequisites

| # | Prerequisite | Source | Status |
|---|--------------|--------|--------|
| 1 | Field Sales authentication | Milestone 56 | Required |
| 2 | Customer database synced | Milestone 56 | Required |
| 3 | Price lists functional | Milestone 56 | Required |
| 4 | Offline sync engine | Milestone 56 | Required |
| 5 | Google Maps API key | Configuration | Required |
| 6 | Sales territories defined | Configuration | Required |

### 1.5 Success Criteria

- [ ] Order entry completed in <30 seconds average
- [ ] Route optimization reduces travel time by >20%
- [ ] GPS accuracy within 50 meters for check-in
- [ ] 100+ orders/day capacity per sales rep
- [ ] Offline orders sync within 1 minute when online
- [ ] Battery usage <15% per 8-hour shift
- [ ] Reports generate in <5 seconds
- [ ] Code coverage >80%

---

## 2. Requirement Traceability Matrix

### 2.1 RFP Requirements

| RFP Req ID | Requirement Description | Implementation | Day |
|------------|------------------------|----------------|-----|
| RFP-MOB-006 | Field sales order taking | Quick order entry | 311-313 |
| RFP-MOB-007 | Route optimization and GPS | Route planning, maps | 314-316 |
| RFP-MOB-023 | Visit tracking | Check-in/check-out | 314-315 |
| RFP-MOB-024 | Payment collection | Payment screen | 317-318 |
| RFP-MOB-025 | Sales reporting | Reports dashboard | 319-320 |

### 2.2 BRD Requirements

| BRD Req ID | Business Requirement | Implementation | Day |
|------------|---------------------|----------------|-----|
| BRD-MOB-003 | Field sales efficiency | Route optimization | 314-316 |
| BRD-MOB-011 | Real-time sales tracking | Dashboard, sync | 319-320 |
| BRD-MOB-012 | Instant order confirmation | Offline capability | 311-313 |
| BRD-MOB-013 | Payment visibility | Collection tracking | 317-318 |

---

## 3. Day-by-Day Breakdown

### Day 311 (Sprint Day 1): Order Entry Backend APIs

#### Objectives
- Build order creation API with validation
- Implement order line management
- Create order sync endpoints

#### Dev 1 - Backend Lead (8 hours)

**Task 1.1: Field Sales Order Controller (4 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/field_sales_order_controller.py

from odoo import http
from odoo.http import request
from datetime import datetime
import uuid

class FieldSalesOrderController(http.Controller):

    @http.route('/api/v1/field-sales/orders', type='json', auth='jwt_field_sales', methods=['POST'], csrf=False)
    def create_order(self, **kwargs):
        """
        Create a new sales order from field sales app.

        Request:
            - customer_id: Customer ID
            - lines: [{product_id, quantity, unit_price, discount}]
            - notes: Order notes
            - location: {latitude, longitude}
            - local_id: Local UUID for sync tracking
        """
        try:
            sales_user = self._get_current_sales_user()
            customer_id = kwargs.get('customer_id')
            lines = kwargs.get('lines', [])
            notes = kwargs.get('notes', '')
            location = kwargs.get('location', {})
            local_id = kwargs.get('local_id')

            if not customer_id:
                return {'success': False, 'error': 'Customer required'}

            if not lines:
                return {'success': False, 'error': 'At least one product required'}

            # Get customer
            Partner = request.env['res.partner'].sudo()
            customer = Partner.browse(customer_id)

            if not customer.exists():
                return {'success': False, 'error': 'Customer not found'}

            # Validate credit limit
            credit_check = self._check_credit_limit(customer, lines)
            if not credit_check['allowed']:
                return {
                    'success': False,
                    'error': credit_check['message'],
                    'code': 'CREDIT_LIMIT_EXCEEDED'
                }

            # Create order
            order_vals = {
                'partner_id': customer.id,
                'user_id': sales_user.user_id.id,
                'team_id': sales_user.sales_team_id.id if sales_user.sales_team_id else False,
                'pricelist_id': customer.property_product_pricelist.id,
                'note': notes,
                'origin': f'Field Sales - {sales_user.name}',
                'field_sales_user_id': sales_user.id,
                'field_local_id': local_id,
                'order_latitude': location.get('latitude'),
                'order_longitude': location.get('longitude'),
                'order_timestamp': datetime.now(),
            }

            order = request.env['sale.order'].sudo().create(order_vals)

            # Create order lines
            for line_data in lines:
                self._create_order_line(order, line_data)

            # Compute totals
            order._compute_amount_all()

            return {
                'success': True,
                'data': {
                    'order_id': order.id,
                    'order_name': order.name,
                    'local_id': local_id,
                    'amount_total': order.amount_total,
                    'state': order.state,
                    'created_at': order.create_date.isoformat()
                }
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/field-sales/orders/sync', type='json', auth='jwt_field_sales', methods=['POST'], csrf=False)
    def sync_orders(self, **kwargs):
        """
        Sync multiple offline orders.

        Request:
            - orders: List of orders to sync
        """
        try:
            orders_data = kwargs.get('orders', [])
            results = []

            for order_data in orders_data:
                local_id = order_data.get('local_id')

                # Check if already synced
                existing = request.env['sale.order'].sudo().search([
                    ('field_local_id', '=', local_id)
                ], limit=1)

                if existing:
                    results.append({
                        'local_id': local_id,
                        'status': 'already_synced',
                        'order_id': existing.id,
                        'order_name': existing.name
                    })
                    continue

                # Create order
                result = self.create_order(**order_data)

                if result['success']:
                    results.append({
                        'local_id': local_id,
                        'status': 'synced',
                        'order_id': result['data']['order_id'],
                        'order_name': result['data']['order_name']
                    })
                else:
                    results.append({
                        'local_id': local_id,
                        'status': 'failed',
                        'error': result['error']
                    })

            return {
                'success': True,
                'data': {
                    'results': results,
                    'synced_count': len([r for r in results if r['status'] == 'synced']),
                    'failed_count': len([r for r in results if r['status'] == 'failed'])
                }
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/field-sales/orders/<int:order_id>', type='json', auth='jwt_field_sales', methods=['GET'], csrf=False)
    def get_order(self, order_id, **kwargs):
        """Get order details."""
        try:
            sales_user = self._get_current_sales_user()

            order = request.env['sale.order'].sudo().search([
                ('id', '=', order_id),
                ('field_sales_user_id', '=', sales_user.id)
            ], limit=1)

            if not order:
                return {'success': False, 'error': 'Order not found'}

            return {
                'success': True,
                'data': self._serialize_order(order)
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/field-sales/orders/today', type='json', auth='jwt_field_sales', methods=['GET'], csrf=False)
    def get_today_orders(self, **kwargs):
        """Get today's orders for current sales user."""
        try:
            sales_user = self._get_current_sales_user()
            today = datetime.now().date()

            orders = request.env['sale.order'].sudo().search([
                ('field_sales_user_id', '=', sales_user.id),
                ('create_date', '>=', today.strftime('%Y-%m-%d 00:00:00')),
                ('create_date', '<=', today.strftime('%Y-%m-%d 23:59:59'))
            ], order='create_date desc')

            orders_data = []
            total_amount = 0

            for order in orders:
                orders_data.append(self._serialize_order_summary(order))
                total_amount += order.amount_total

            return {
                'success': True,
                'data': {
                    'orders': orders_data,
                    'summary': {
                        'count': len(orders),
                        'total_amount': total_amount,
                        'target': sales_user.daily_order_target,
                        'achievement_percent': (total_amount / sales_user.daily_order_target * 100) if sales_user.daily_order_target else 0
                    }
                }
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/field-sales/customers/<int:customer_id>/orders', type='json', auth='jwt_field_sales', methods=['GET'], csrf=False)
    def get_customer_orders(self, customer_id, **kwargs):
        """Get order history for a customer."""
        try:
            page = int(kwargs.get('page', 1))
            limit = min(int(kwargs.get('limit', 20)), 50)
            offset = (page - 1) * limit

            domain = [
                ('partner_id', '=', customer_id),
                ('state', '!=', 'cancel')
            ]

            total = request.env['sale.order'].sudo().search_count(domain)
            orders = request.env['sale.order'].sudo().search(
                domain,
                offset=offset,
                limit=limit,
                order='date_order desc'
            )

            orders_data = [self._serialize_order_summary(o) for o in orders]

            return {
                'success': True,
                'data': {
                    'orders': orders_data,
                    'pagination': {
                        'page': page,
                        'limit': limit,
                        'total': total,
                        'has_more': offset + limit < total
                    }
                }
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    def _create_order_line(self, order, line_data):
        """Create order line."""
        product_id = line_data.get('product_id')
        quantity = line_data.get('quantity', 1)
        unit_price = line_data.get('unit_price')
        discount = line_data.get('discount', 0)

        Product = request.env['product.product'].sudo()
        product = Product.browse(product_id)

        if not product.exists():
            raise ValueError(f'Product {product_id} not found')

        # Get price from pricelist if not provided
        if not unit_price:
            pricelist = order.pricelist_id
            unit_price = pricelist.get_product_price(product, quantity, order.partner_id)

        line_vals = {
            'order_id': order.id,
            'product_id': product.id,
            'product_uom_qty': quantity,
            'price_unit': unit_price,
            'discount': discount,
        }

        return request.env['sale.order.line'].sudo().create(line_vals)

    def _check_credit_limit(self, customer, lines):
        """Check if order is within credit limit."""
        # Calculate order total
        order_total = sum(
            line.get('quantity', 1) * line.get('unit_price', 0) * (1 - line.get('discount', 0) / 100)
            for line in lines
        )

        # Get current credit usage
        current_credit = customer.credit if hasattr(customer, 'credit') else 0
        credit_limit = customer.credit_limit if hasattr(customer, 'credit_limit') else 0

        if credit_limit <= 0:
            # No credit limit set
            return {'allowed': True}

        available_credit = credit_limit - current_credit

        if order_total > available_credit:
            return {
                'allowed': False,
                'message': f'Order exceeds credit limit. Available: {available_credit:.2f}, Order: {order_total:.2f}'
            }

        return {'allowed': True}

    def _serialize_order(self, order):
        """Serialize full order details."""
        lines = []
        for line in order.order_line:
            lines.append({
                'id': line.id,
                'product_id': line.product_id.id,
                'product_name': line.product_id.name,
                'quantity': line.product_uom_qty,
                'uom': line.product_uom.name,
                'unit_price': line.price_unit,
                'discount': line.discount,
                'subtotal': line.price_subtotal,
            })

        return {
            'id': order.id,
            'name': order.name,
            'local_id': order.field_local_id,
            'customer_id': order.partner_id.id,
            'customer_name': order.partner_id.name,
            'date_order': order.date_order.isoformat() if order.date_order else None,
            'state': order.state,
            'lines': lines,
            'subtotal': order.amount_untaxed,
            'tax_amount': order.amount_tax,
            'total': order.amount_total,
            'notes': order.note,
            'location': {
                'latitude': order.order_latitude,
                'longitude': order.order_longitude,
            } if order.order_latitude else None,
        }

    def _serialize_order_summary(self, order):
        """Serialize order summary for lists."""
        return {
            'id': order.id,
            'name': order.name,
            'customer_name': order.partner_id.name,
            'date_order': order.date_order.isoformat() if order.date_order else None,
            'state': order.state,
            'amount_total': order.amount_total,
            'item_count': len(order.order_line),
        }

    def _get_current_sales_user(self):
        """Get current field sales user."""
        current_user = request.env.user
        FieldSalesUser = request.env['field.sales.user'].sudo()
        return FieldSalesUser.search([
            ('user_id', '=', current_user.id),
            ('is_active', '=', True)
        ], limit=1)
```

**Task 1.2: Sale Order Extension (2 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/models/sale_order_field_sales.py

from odoo import models, fields, api

class SaleOrderFieldSales(models.Model):
    _inherit = 'sale.order'

    # Field Sales tracking
    field_sales_user_id = fields.Many2one('field.sales.user', 'Field Sales Rep')
    field_local_id = fields.Char('Local ID', index=True, help='UUID from mobile app for sync')

    # Location tracking
    order_latitude = fields.Float('Order Latitude', digits=(10, 7))
    order_longitude = fields.Float('Order Longitude', digits=(10, 7))
    order_timestamp = fields.Datetime('Order Timestamp')

    # Visit reference
    visit_id = fields.Many2one('field.sales.visit', 'Customer Visit')

    _sql_constraints = [
        ('field_local_id_unique', 'unique(field_local_id)',
         'Local ID must be unique'),
    ]
```

**Task 1.3: Stock Availability API (2 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/field_sales_stock_controller.py

from odoo import http
from odoo.http import request

class FieldSalesStockController(http.Controller):

    @http.route('/api/v1/field-sales/products/stock', type='json', auth='jwt_field_sales', methods=['GET'], csrf=False)
    def get_stock_levels(self, **kwargs):
        """Get stock levels for products."""
        try:
            product_ids = kwargs.get('product_ids', [])
            warehouse_id = kwargs.get('warehouse_id')

            Product = request.env['product.product'].sudo()

            if product_ids:
                products = Product.browse(product_ids)
            else:
                products = Product.search([
                    ('sale_ok', '=', True),
                    ('active', '=', True)
                ], limit=500)

            stock_data = []
            for product in products:
                qty_available = product.qty_available
                virtual_available = product.virtual_available

                # Get warehouse-specific stock if provided
                if warehouse_id:
                    warehouse = request.env['stock.warehouse'].sudo().browse(warehouse_id)
                    if warehouse.exists():
                        qty_available = product.with_context(
                            warehouse=warehouse_id
                        ).qty_available
                        virtual_available = product.with_context(
                            warehouse=warehouse_id
                        ).virtual_available

                stock_data.append({
                    'product_id': product.id,
                    'product_name': product.name,
                    'qty_available': qty_available,
                    'virtual_available': virtual_available,
                    'uom': product.uom_id.name,
                    'is_available': qty_available > 0,
                })

            return {
                'success': True,
                'data': {
                    'stock': stock_data,
                    'timestamp': fields.Datetime.now().isoformat()
                }
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}
```

#### Dev 2 - Full-Stack Developer (8 hours)

**Task 2.1: Order Local Storage (4 hours)**

```dart
// apps/field_sales/lib/features/orders/data/datasources/order_local_datasource.dart

import 'package:drift/drift.dart';
import 'package:uuid/uuid.dart';
import 'package:smart_dairy_field_sales/core/database/field_sales_database.dart';

class OrderLocalDataSource {
  final FieldSalesDatabase _database;
  final _uuid = const Uuid();

  OrderLocalDataSource(this._database);

  /// Create a new pending order locally
  Future<String> createPendingOrder({
    required int customerId,
    required List<OrderLineInput> lines,
    String? notes,
    double? latitude,
    double? longitude,
  }) async {
    final localId = _uuid.v4();

    // Calculate totals
    double subtotal = 0;
    double taxAmount = 0;
    double discountAmount = 0;

    for (final line in lines) {
      final lineSubtotal = line.quantity * line.unitPrice;
      final lineDiscount = lineSubtotal * (line.discount / 100);
      subtotal += lineSubtotal - lineDiscount;
    }

    final total = subtotal + taxAmount;

    // Create order
    await _database.into(_database.pendingOrders).insert(
      PendingOrdersCompanion.insert(
        localId: localId,
        customerId: customerId,
        orderDate: DateTime.now(),
        subtotal: subtotal,
        taxAmount: taxAmount,
        discountAmount: discountAmount,
        total: total,
        notes: Value(notes),
        latitude: Value(latitude),
        longitude: Value(longitude),
        needsSync: const Value(true),
        createdAt: DateTime.now(),
      ),
    );

    // Create order lines
    for (final line in lines) {
      await _database.into(_database.pendingOrderLines).insert(
        PendingOrderLinesCompanion.insert(
          orderId: localId,
          productId: line.productId,
          productName: line.productName,
          quantity: line.quantity,
          uom: line.uom,
          unitPrice: line.unitPrice,
          discount: Value(line.discount),
          subtotal: line.quantity * line.unitPrice * (1 - line.discount / 100),
        ),
      );
    }

    // Add to sync queue
    await _addToSyncQueue(
      entityType: 'order',
      entityId: localId,
      action: 'create',
    );

    return localId;
  }

  /// Get all pending (unsynced) orders
  Future<List<PendingOrderWithLines>> getPendingOrders() async {
    final orders = await (_database.select(_database.pendingOrders)
      ..where((o) => o.needsSync.equals(true))
      ..orderBy([(o) => OrderingTerm.asc(o.createdAt)]))
      .get();

    final result = <PendingOrderWithLines>[];

    for (final order in orders) {
      final lines = await (_database.select(_database.pendingOrderLines)
        ..where((l) => l.orderId.equals(order.localId)))
        .get();

      result.add(PendingOrderWithLines(order: order, lines: lines));
    }

    return result;
  }

  /// Get today's orders (synced and pending)
  Future<List<PendingOrderWithLines>> getTodayOrders() async {
    final today = DateTime.now();
    final startOfDay = DateTime(today.year, today.month, today.day);
    final endOfDay = startOfDay.add(const Duration(days: 1));

    final orders = await (_database.select(_database.pendingOrders)
      ..where((o) => o.orderDate.isBetweenValues(startOfDay, endOfDay))
      ..orderBy([(o) => OrderingTerm.desc(o.orderDate)]))
      .get();

    final result = <PendingOrderWithLines>[];

    for (final order in orders) {
      final lines = await (_database.select(_database.pendingOrderLines)
        ..where((l) => l.orderId.equals(order.localId)))
        .get();

      result.add(PendingOrderWithLines(order: order, lines: lines));
    }

    return result;
  }

  /// Mark order as synced
  Future<void> markOrderSynced(String localId, int serverId, String orderName) async {
    await (_database.update(_database.pendingOrders)
      ..where((o) => o.localId.equals(localId)))
      .write(PendingOrdersCompanion(
        serverId: Value(serverId),
        status: const Value('confirmed'),
        needsSync: const Value(false),
        syncedAt: Value(DateTime.now()),
      ));

    // Remove from sync queue
    await (_database.delete(_database.syncQueue)
      ..where((s) => s.entityType.equals('order') & s.entityId.equals(localId)))
      .go();
  }

  /// Mark order sync failed
  Future<void> markOrderSyncFailed(String localId, String error) async {
    await (_database.update(_database.pendingOrders)
      ..where((o) => o.localId.equals(localId)))
      .write(PendingOrdersCompanion(
        syncError: Value(error),
      ));

    // Update retry count in sync queue
    final queueItem = await (_database.select(_database.syncQueue)
      ..where((s) => s.entityType.equals('order') & s.entityId.equals(localId)))
      .getSingleOrNull();

    if (queueItem != null) {
      await (_database.update(_database.syncQueue)
        ..where((s) => s.id.equals(queueItem.id)))
        .write(SyncQueueCompanion(
          retryCount: Value(queueItem.retryCount + 1),
          lastAttempt: Value(DateTime.now()),
          lastError: Value(error),
        ));
    }
  }

  Future<void> _addToSyncQueue({
    required String entityType,
    required String entityId,
    required String action,
    int priority = 5,
  }) async {
    await _database.into(_database.syncQueue).insert(
      SyncQueueCompanion.insert(
        entityType: entityType,
        entityId: entityId,
        action: action,
        payload: '{}',
        priority: Value(priority),
        createdAt: DateTime.now(),
      ),
    );
  }
}

// Helper classes
class OrderLineInput {
  final int productId;
  final String productName;
  final double quantity;
  final String uom;
  final double unitPrice;
  final double discount;

  OrderLineInput({
    required this.productId,
    required this.productName,
    required this.quantity,
    required this.uom,
    required this.unitPrice,
    this.discount = 0,
  });
}

class PendingOrderWithLines {
  final PendingOrder order;
  final List<PendingOrderLine> lines;

  PendingOrderWithLines({required this.order, required this.lines});
}
```

**Task 2.2: Order Repository (4 hours)**

```dart
// apps/field_sales/lib/features/orders/data/repositories/order_repository.dart

import 'package:dartz/dartz.dart';
import 'package:smart_dairy_api/api_client.dart';
import 'package:smart_dairy_field_sales/core/error/failures.dart';
import 'package:smart_dairy_field_sales/features/orders/data/datasources/order_local_datasource.dart';
import 'package:smart_dairy_field_sales/features/orders/data/models/order_models.dart';
import 'package:connectivity_plus/connectivity_plus.dart';

class OrderRepository {
  final ApiClient _apiClient;
  final OrderLocalDataSource _localDataSource;
  final Connectivity _connectivity;

  OrderRepository(this._apiClient, this._localDataSource, this._connectivity);

  /// Create order (offline-first)
  Future<Either<Failure, String>> createOrder({
    required int customerId,
    required List<OrderLineInput> lines,
    String? notes,
    double? latitude,
    double? longitude,
  }) async {
    try {
      // Always create locally first
      final localId = await _localDataSource.createPendingOrder(
        customerId: customerId,
        lines: lines,
        notes: notes,
        latitude: latitude,
        longitude: longitude,
      );

      // Try to sync immediately if online
      final connectivityResult = await _connectivity.checkConnectivity();
      if (connectivityResult != ConnectivityResult.none) {
        _syncOrderInBackground(localId);
      }

      return Right(localId);
    } catch (e) {
      return Left(DatabaseFailure(e.toString()));
    }
  }

  /// Sync a single order
  Future<Either<Failure, OrderSyncResult>> syncOrder(String localId) async {
    try {
      final pendingOrders = await _localDataSource.getPendingOrders();
      final orderToSync = pendingOrders.firstWhere(
        (o) => o.order.localId == localId,
        orElse: () => throw Exception('Order not found'),
      );

      final response = await _apiClient.post(
        '/api/v1/field-sales/orders',
        data: _orderToApiPayload(orderToSync),
      );

      if (response['success'] == true) {
        final data = response['data'];
        await _localDataSource.markOrderSynced(
          localId,
          data['order_id'],
          data['order_name'],
        );
        return Right(OrderSyncResult(
          localId: localId,
          serverId: data['order_id'],
          orderName: data['order_name'],
          status: 'synced',
        ));
      }

      await _localDataSource.markOrderSyncFailed(localId, response['error'] ?? 'Unknown error');
      return Left(ServerFailure(response['error'] ?? 'Sync failed'));
    } catch (e) {
      await _localDataSource.markOrderSyncFailed(localId, e.toString());
      return Left(NetworkFailure(e.toString()));
    }
  }

  /// Sync all pending orders
  Future<Either<Failure, List<OrderSyncResult>>> syncAllPendingOrders() async {
    try {
      final pendingOrders = await _localDataSource.getPendingOrders();

      if (pendingOrders.isEmpty) {
        return const Right([]);
      }

      final ordersPayload = pendingOrders.map((o) => _orderToApiPayload(o)).toList();

      final response = await _apiClient.post(
        '/api/v1/field-sales/orders/sync',
        data: {'orders': ordersPayload},
      );

      if (response['success'] == true) {
        final results = <OrderSyncResult>[];

        for (final result in response['data']['results']) {
          final localId = result['local_id'];
          final status = result['status'];

          if (status == 'synced' || status == 'already_synced') {
            await _localDataSource.markOrderSynced(
              localId,
              result['order_id'],
              result['order_name'],
            );
            results.add(OrderSyncResult(
              localId: localId,
              serverId: result['order_id'],
              orderName: result['order_name'],
              status: status,
            ));
          } else {
            await _localDataSource.markOrderSyncFailed(localId, result['error'] ?? 'Unknown');
            results.add(OrderSyncResult(
              localId: localId,
              status: 'failed',
              error: result['error'],
            ));
          }
        }

        return Right(results);
      }

      return Left(ServerFailure(response['error'] ?? 'Sync failed'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  /// Get today's orders
  Future<Either<Failure, TodayOrdersResponse>> getTodayOrders({bool forceRefresh = false}) async {
    try {
      // Get local orders first
      final localOrders = await _localDataSource.getTodayOrders();

      // Try to fetch from server if online
      final connectivityResult = await _connectivity.checkConnectivity();
      if (connectivityResult != ConnectivityResult.none && forceRefresh) {
        try {
          final response = await _apiClient.get('/api/v1/field-sales/orders/today');
          if (response['success'] == true) {
            // Merge with local pending orders
            final serverOrders = (response['data']['orders'] as List)
                .map((o) => OrderSummary.fromJson(o))
                .toList();

            // Include unsynced local orders
            final unsyncedOrders = localOrders
                .where((o) => o.order.needsSync)
                .map((o) => OrderSummary.fromLocal(o))
                .toList();

            return Right(TodayOrdersResponse(
              orders: [...unsyncedOrders, ...serverOrders],
              summary: OrderDaySummary.fromJson(response['data']['summary']),
            ));
          }
        } catch (_) {
          // Fall back to local data
        }
      }

      // Return local data
      final orders = localOrders.map((o) => OrderSummary.fromLocal(o)).toList();
      return Right(TodayOrdersResponse(
        orders: orders,
        summary: _calculateLocalSummary(localOrders),
        isFromCache: true,
      ));
    } catch (e) {
      return Left(DatabaseFailure(e.toString()));
    }
  }

  /// Get customer order history
  Future<Either<Failure, List<OrderSummary>>> getCustomerOrders(int customerId, {int page = 1}) async {
    try {
      final response = await _apiClient.get(
        '/api/v1/field-sales/customers/$customerId/orders',
        queryParameters: {'page': page, 'limit': 20},
      );

      if (response['success'] == true) {
        final orders = (response['data']['orders'] as List)
            .map((o) => OrderSummary.fromJson(o))
            .toList();
        return Right(orders);
      }

      return Left(ServerFailure(response['error'] ?? 'Failed to load orders'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  void _syncOrderInBackground(String localId) async {
    // Fire and forget sync attempt
    syncOrder(localId);
  }

  Map<String, dynamic> _orderToApiPayload(PendingOrderWithLines order) {
    return {
      'local_id': order.order.localId,
      'customer_id': order.order.customerId,
      'lines': order.lines.map((l) => {
        'product_id': l.productId,
        'quantity': l.quantity,
        'unit_price': l.unitPrice,
        'discount': l.discount,
      }).toList(),
      'notes': order.order.notes,
      'location': {
        'latitude': order.order.latitude,
        'longitude': order.order.longitude,
      },
    };
  }

  OrderDaySummary _calculateLocalSummary(List<PendingOrderWithLines> orders) {
    final totalAmount = orders.fold<double>(0, (sum, o) => sum + o.order.total);
    return OrderDaySummary(
      count: orders.length,
      totalAmount: totalAmount,
      target: 50000, // Get from user profile
      achievementPercent: (totalAmount / 50000) * 100,
    );
  }
}
```

#### Dev 3 - Mobile Lead (8 hours)

**Task 3.1: Order Entry Provider (3 hours)**

```dart
// apps/field_sales/lib/features/orders/presentation/providers/order_provider.dart

import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:smart_dairy_field_sales/features/orders/data/datasources/order_local_datasource.dart';
import 'package:smart_dairy_field_sales/features/orders/data/models/order_models.dart';
import 'package:smart_dairy_field_sales/features/orders/data/repositories/order_repository.dart';
import 'package:smart_dairy_field_sales/features/customers/data/models/customer_model.dart';
import 'package:smart_dairy_field_sales/features/products/data/models/product_model.dart';

// Cart state for order entry
class OrderCartState {
  final CustomerModel? customer;
  final List<CartItem> items;
  final bool isSubmitting;
  final String? error;
  final String? submittedOrderId;

  const OrderCartState({
    this.customer,
    this.items = const [],
    this.isSubmitting = false,
    this.error,
    this.submittedOrderId,
  });

  double get subtotal => items.fold(0, (sum, item) => sum + item.lineTotal);
  double get discountTotal => items.fold(0, (sum, item) => sum + item.discountAmount);
  double get total => subtotal - discountTotal;
  int get itemCount => items.length;
  int get totalQuantity => items.fold(0, (sum, item) => sum + item.quantity.toInt());

  OrderCartState copyWith({
    CustomerModel? customer,
    List<CartItem>? items,
    bool? isSubmitting,
    String? error,
    String? submittedOrderId,
    bool clearCustomer = false,
    bool clearError = false,
  }) {
    return OrderCartState(
      customer: clearCustomer ? null : (customer ?? this.customer),
      items: items ?? this.items,
      isSubmitting: isSubmitting ?? this.isSubmitting,
      error: clearError ? null : (error ?? this.error),
      submittedOrderId: submittedOrderId ?? this.submittedOrderId,
    );
  }
}

class CartItem {
  final ProductModel product;
  final double quantity;
  final double unitPrice;
  final double discount;

  CartItem({
    required this.product,
    required this.quantity,
    required this.unitPrice,
    this.discount = 0,
  });

  double get lineTotal => quantity * unitPrice;
  double get discountAmount => lineTotal * (discount / 100);
  double get finalPrice => lineTotal - discountAmount;

  CartItem copyWith({
    ProductModel? product,
    double? quantity,
    double? unitPrice,
    double? discount,
  }) {
    return CartItem(
      product: product ?? this.product,
      quantity: quantity ?? this.quantity,
      unitPrice: unitPrice ?? this.unitPrice,
      discount: discount ?? this.discount,
    );
  }
}

// Order cart notifier
class OrderCartNotifier extends StateNotifier<OrderCartState> {
  final OrderRepository _orderRepository;

  OrderCartNotifier(this._orderRepository) : super(const OrderCartState());

  void setCustomer(CustomerModel customer) {
    state = state.copyWith(customer: customer, clearError: true);
  }

  void clearCustomer() {
    state = state.copyWith(clearCustomer: true, items: []);
  }

  void addItem(ProductModel product, double quantity, {double? price, double discount = 0}) {
    final existingIndex = state.items.indexWhere((item) => item.product.id == product.id);

    if (existingIndex >= 0) {
      // Update existing item
      final existingItem = state.items[existingIndex];
      final updatedItem = existingItem.copyWith(
        quantity: existingItem.quantity + quantity,
      );

      final updatedItems = [...state.items];
      updatedItems[existingIndex] = updatedItem;
      state = state.copyWith(items: updatedItems, clearError: true);
    } else {
      // Add new item
      final item = CartItem(
        product: product,
        quantity: quantity,
        unitPrice: price ?? product.listPrice,
        discount: discount,
      );
      state = state.copyWith(items: [...state.items, item], clearError: true);
    }
  }

  void updateItemQuantity(int productId, double quantity) {
    if (quantity <= 0) {
      removeItem(productId);
      return;
    }

    final updatedItems = state.items.map((item) {
      if (item.product.id == productId) {
        return item.copyWith(quantity: quantity);
      }
      return item;
    }).toList();

    state = state.copyWith(items: updatedItems);
  }

  void updateItemDiscount(int productId, double discount) {
    final updatedItems = state.items.map((item) {
      if (item.product.id == productId) {
        return item.copyWith(discount: discount);
      }
      return item;
    }).toList();

    state = state.copyWith(items: updatedItems);
  }

  void removeItem(int productId) {
    final updatedItems = state.items.where((item) => item.product.id != productId).toList();
    state = state.copyWith(items: updatedItems);
  }

  void clearCart() {
    state = const OrderCartState();
  }

  Future<bool> submitOrder({String? notes, double? latitude, double? longitude}) async {
    if (state.customer == null) {
      state = state.copyWith(error: 'Please select a customer');
      return false;
    }

    if (state.items.isEmpty) {
      state = state.copyWith(error: 'Please add at least one product');
      return false;
    }

    state = state.copyWith(isSubmitting: true, clearError: true);

    final lines = state.items.map((item) => OrderLineInput(
      productId: item.product.id,
      productName: item.product.name,
      quantity: item.quantity,
      uom: item.product.uom,
      unitPrice: item.unitPrice,
      discount: item.discount,
    )).toList();

    final result = await _orderRepository.createOrder(
      customerId: state.customer!.id,
      lines: lines,
      notes: notes,
      latitude: latitude,
      longitude: longitude,
    );

    return result.fold(
      (failure) {
        state = state.copyWith(isSubmitting: false, error: failure.message);
        return false;
      },
      (localId) {
        state = state.copyWith(
          isSubmitting: false,
          submittedOrderId: localId,
        );
        return true;
      },
    );
  }
}

// Providers
final orderRepositoryProvider = Provider<OrderRepository>((ref) {
  throw UnimplementedError('Override in main.dart');
});

final orderCartProvider = StateNotifierProvider<OrderCartNotifier, OrderCartState>((ref) {
  return OrderCartNotifier(ref.watch(orderRepositoryProvider));
});

// Today's orders provider
final todayOrdersProvider = FutureProvider<TodayOrdersResponse>((ref) async {
  final repository = ref.watch(orderRepositoryProvider);
  final result = await repository.getTodayOrders();
  return result.fold(
    (failure) => throw Exception(failure.message),
    (response) => response,
  );
});
```

**Task 3.2: Quick Order Screen (5 hours)**

```dart
// apps/field_sales/lib/features/orders/presentation/screens/quick_order_screen.dart

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:geolocator/geolocator.dart';
import 'package:smart_dairy_field_sales/core/theme/app_colors.dart';
import 'package:smart_dairy_field_sales/features/orders/presentation/providers/order_provider.dart';
import 'package:smart_dairy_field_sales/features/orders/presentation/widgets/customer_selector.dart';
import 'package:smart_dairy_field_sales/features/orders/presentation/widgets/product_search_bar.dart';
import 'package:smart_dairy_field_sales/features/orders/presentation/widgets/cart_item_card.dart';
import 'package:smart_dairy_field_sales/features/orders/presentation/widgets/order_summary_bar.dart';

class QuickOrderScreen extends ConsumerStatefulWidget {
  final int? preselectedCustomerId;

  const QuickOrderScreen({super.key, this.preselectedCustomerId});

  @override
  ConsumerState<QuickOrderScreen> createState() => _QuickOrderScreenState();
}

class _QuickOrderScreenState extends ConsumerState<QuickOrderScreen> {
  final _notesController = TextEditingController();
  Position? _currentPosition;
  bool _isLoadingLocation = false;

  @override
  void initState() {
    super.initState();
    _getCurrentLocation();
  }

  @override
  void dispose() {
    _notesController.dispose();
    super.dispose();
  }

  Future<void> _getCurrentLocation() async {
    setState(() => _isLoadingLocation = true);

    try {
      final permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        await Geolocator.requestPermission();
      }

      _currentPosition = await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high,
        timeLimit: const Duration(seconds: 10),
      );
    } catch (e) {
      // Location not critical, continue without it
    }

    if (mounted) {
      setState(() => _isLoadingLocation = false);
    }
  }

  Future<void> _submitOrder() async {
    final cartState = ref.read(orderCartProvider);

    // Confirm order
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text('Confirm Order'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Customer: ${cartState.customer?.name}'),
            Text('Items: ${cartState.itemCount}'),
            Text('Total: ৳${cartState.total.toStringAsFixed(2)}'),
            const SizedBox(height: 16),
            const Text('Submit this order?'),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Cancel'),
          ),
          ElevatedButton(
            onPressed: () => Navigator.pop(context, true),
            child: const Text('Submit'),
          ),
        ],
      ),
    );

    if (confirmed != true) return;

    final success = await ref.read(orderCartProvider.notifier).submitOrder(
      notes: _notesController.text.isEmpty ? null : _notesController.text,
      latitude: _currentPosition?.latitude,
      longitude: _currentPosition?.longitude,
    );

    if (success && mounted) {
      _showSuccessDialog();
    }
  }

  void _showSuccessDialog() {
    final cartState = ref.read(orderCartProvider);

    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (_) => AlertDialog(
        icon: const Icon(Icons.check_circle, color: Colors.green, size: 64),
        title: const Text('Order Submitted!'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Text('Order ID: ${cartState.submittedOrderId?.substring(0, 8)}...'),
            const SizedBox(height: 8),
            const Text(
              'Your order has been saved and will sync automatically.',
              textAlign: TextAlign.center,
            ),
          ],
        ),
        actions: [
          ElevatedButton(
            onPressed: () {
              ref.read(orderCartProvider.notifier).clearCart();
              Navigator.pop(context); // Close dialog
              Navigator.pop(context); // Return to previous screen
            },
            child: const Text('Done'),
          ),
          TextButton(
            onPressed: () {
              ref.read(orderCartProvider.notifier).clearCart();
              Navigator.pop(context); // Just close dialog, stay on screen
            },
            child: const Text('New Order'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final cartState = ref.watch(orderCartProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Quick Order'),
        actions: [
          if (cartState.items.isNotEmpty)
            IconButton(
              icon: const Icon(Icons.delete_outline),
              onPressed: () => _confirmClearCart(),
            ),
        ],
      ),
      body: Column(
        children: [
          // Customer selector
          Padding(
            padding: const EdgeInsets.all(16),
            child: CustomerSelector(
              selectedCustomer: cartState.customer,
              onCustomerSelected: (customer) {
                ref.read(orderCartProvider.notifier).setCustomer(customer);
              },
            ),
          ),

          // Credit info
          if (cartState.customer != null)
            _buildCreditInfo(cartState.customer!),

          // Product search
          if (cartState.customer != null)
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: ProductSearchBar(
                onProductSelected: (product) {
                  _showQuantityDialog(product);
                },
              ),
            ),

          // Cart items
          Expanded(
            child: cartState.items.isEmpty
                ? _buildEmptyCart()
                : ListView.builder(
                    padding: const EdgeInsets.all(16),
                    itemCount: cartState.items.length,
                    itemBuilder: (context, index) {
                      final item = cartState.items[index];
                      return CartItemCard(
                        item: item,
                        onQuantityChanged: (qty) {
                          ref.read(orderCartProvider.notifier)
                              .updateItemQuantity(item.product.id, qty);
                        },
                        onRemove: () {
                          ref.read(orderCartProvider.notifier)
                              .removeItem(item.product.id);
                        },
                      );
                    },
                  ),
          ),

          // Notes field
          if (cartState.items.isNotEmpty)
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: TextField(
                controller: _notesController,
                decoration: InputDecoration(
                  hintText: 'Order notes (optional)',
                  prefixIcon: const Icon(Icons.note),
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(8),
                  ),
                  contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                ),
                maxLines: 1,
              ),
            ),

          // Error message
          if (cartState.error != null)
            Container(
              margin: const EdgeInsets.all(16),
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: Colors.red[50],
                borderRadius: BorderRadius.circular(8),
              ),
              child: Row(
                children: [
                  const Icon(Icons.error_outline, color: Colors.red),
                  const SizedBox(width: 8),
                  Expanded(child: Text(cartState.error!, style: const TextStyle(color: Colors.red))),
                ],
              ),
            ),

          // Order summary bar
          if (cartState.items.isNotEmpty)
            OrderSummaryBar(
              subtotal: cartState.subtotal,
              discount: cartState.discountTotal,
              total: cartState.total,
              itemCount: cartState.itemCount,
              isSubmitting: cartState.isSubmitting,
              onSubmit: _submitOrder,
            ),
        ],
      ),
    );
  }

  Widget _buildCreditInfo(customer) {
    final availableCredit = customer.availableCredit ?? 0;
    final creditLimit = customer.creditLimit ?? 0;
    final usedPercent = creditLimit > 0 ? ((creditLimit - availableCredit) / creditLimit * 100) : 0;

    Color creditColor = Colors.green;
    if (usedPercent > 80) creditColor = Colors.red;
    else if (usedPercent > 60) creditColor = Colors.orange;

    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: creditColor.withOpacity(0.1),
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: creditColor.withOpacity(0.3)),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Row(
            children: [
              Icon(Icons.account_balance_wallet, color: creditColor, size: 20),
              const SizedBox(width: 8),
              const Text('Credit Available:'),
            ],
          ),
          Text(
            '৳${availableCredit.toStringAsFixed(0)}',
            style: TextStyle(
              fontWeight: FontWeight.bold,
              color: creditColor,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildEmptyCart() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.shopping_cart_outlined, size: 64, color: Colors.grey[400]),
          const SizedBox(height: 16),
          Text(
            'No items in cart',
            style: TextStyle(color: Colors.grey[600]),
          ),
          const SizedBox(height: 8),
          Text(
            'Search and add products above',
            style: TextStyle(color: Colors.grey[500], fontSize: 13),
          ),
        ],
      ),
    );
  }

  void _showQuantityDialog(product) {
    final quantityController = TextEditingController(text: '1');

    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: Text(product.name),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Text('Price: ৳${product.listPrice}'),
            Text('Stock: ${product.stockQty} ${product.uom}'),
            const SizedBox(height: 16),
            TextField(
              controller: quantityController,
              keyboardType: TextInputType.number,
              decoration: const InputDecoration(
                labelText: 'Quantity',
                border: OutlineInputBorder(),
              ),
              autofocus: true,
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cancel'),
          ),
          ElevatedButton(
            onPressed: () {
              final qty = double.tryParse(quantityController.text) ?? 1;
              ref.read(orderCartProvider.notifier).addItem(product, qty);
              Navigator.pop(context);
            },
            child: const Text('Add'),
          ),
        ],
      ),
    );
  }

  void _confirmClearCart() {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text('Clear Cart'),
        content: const Text('Remove all items from cart?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cancel'),
          ),
          TextButton(
            onPressed: () {
              ref.read(orderCartProvider.notifier).clearCart();
              Navigator.pop(context);
            },
            style: TextButton.styleFrom(foregroundColor: Colors.red),
            child: const Text('Clear'),
          ),
        ],
      ),
    );
  }
}
```

#### End-of-Day 311 Deliverables

- [ ] Order creation API with validation
- [ ] Order sync API for batch operations
- [ ] Today's orders API
- [ ] Customer order history API
- [ ] Stock availability API
- [ ] Sale order model extensions
- [ ] Order local data source
- [ ] Order repository (offline-first)
- [ ] Order cart provider
- [ ] Quick order screen UI

---

### Days 312-320 Summary

**Day 312**: Order Entry Completion
- Product search component
- Cart item management
- Order summary bar
- Offline order queue

**Day 313**: Order Edit & Cancel
- Edit pending orders
- Cancel order functionality
- Order confirmation screen

**Day 314**: Route Planning Backend
- Route calculation API
- Visit scheduling
- Customer location mapping

**Day 315**: Route Map Screen
- Google Maps integration
- Route visualization
- Turn-by-turn navigation

**Day 316**: Customer Visit Tracking
- Check-in/check-out APIs
- GPS verification
- Visit history

**Day 317**: Payment Collection - Part 1
- Payment recording API
- Cash collection flow
- Cheque collection

**Day 318**: Payment Collection - Part 2
- MFS integration
- Payment history
- Receipt generation

**Day 319**: Reports Dashboard - Part 1
- Daily reports API
- Sales summary
- Charts and graphs

**Day 320**: Reports Dashboard - Part 2
- Target tracking
- Performance metrics
- Export functionality

---

## 4. Technical Specifications

### 4.1 API Endpoints Summary

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/field-sales/orders` | POST | Create order |
| `/api/v1/field-sales/orders/sync` | POST | Batch sync orders |
| `/api/v1/field-sales/orders/today` | GET | Today's orders |
| `/api/v1/field-sales/orders/{id}` | GET | Order details |
| `/api/v1/field-sales/customers/{id}/orders` | GET | Customer orders |
| `/api/v1/field-sales/products/stock` | GET | Stock levels |
| `/api/v1/field-sales/routes/plan` | GET | Planned route |
| `/api/v1/field-sales/visits` | POST | Record visit |
| `/api/v1/field-sales/visits/check-in` | POST | Check-in |
| `/api/v1/field-sales/visits/check-out` | POST | Check-out |
| `/api/v1/field-sales/payments` | POST | Record payment |
| `/api/v1/field-sales/reports/daily` | GET | Daily report |
| `/api/v1/field-sales/reports/targets` | GET | Target progress |

### 4.2 Key Features

| Feature | Description |
|---------|-------------|
| Offline Orders | Create orders without network |
| Quick Entry | <30 second order creation |
| Route Optimization | Google Maps integration |
| Visit Verification | GPS-based check-in/out |
| Payment Collection | Cash, cheque, MFS |
| Real-time Reports | Dashboard with charts |

---

## 5. Testing & Validation

### 5.1 Unit Tests
- Order calculation logic
- Credit limit validation
- Offline queue management
- Route optimization algorithm

### 5.2 Integration Tests
- Full order lifecycle
- Sync reliability
- GPS accuracy
- Payment recording

### 5.3 Performance Tests
- Order entry speed (<30s)
- Sync throughput (100+ orders)
- Battery consumption
- Memory usage

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| GPS inaccuracy | Medium | Medium | Allow manual override, increase accuracy threshold |
| Order sync conflicts | Medium | High | Timestamp-based resolution, user notification |
| Battery drain (GPS) | High | Medium | Batch location updates, geofencing |
| Large route datasets | Low | Medium | Pagination, incremental loading |

---

## 7. Dependencies & Handoffs

### 7.1 Dependencies
- Milestone 56: Customer database, offline sync
- Google Maps API
- Payment gateway configurations

### 7.2 Handoffs to Milestone 58
- [ ] Complete Field Sales App
- [ ] Order processing patterns
- [ ] GPS tracking implementation
- [ ] Offline sync engine proven

---

## Document Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-03 | Technical Team | Initial document creation |

---

*End of Milestone 57 Document*
