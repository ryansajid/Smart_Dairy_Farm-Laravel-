# Milestone 55: Customer App Orders & Profile

## Smart Dairy Digital Smart Portal + ERP System
### Phase 6: Mobile App Foundation

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-P6-M55-2026 |
| **Milestone Number** | 55 |
| **Milestone Title** | Customer App Orders & Profile |
| **Phase** | 6 - Mobile App Foundation |
| **Sprint Days** | Days 291-300 (10 working days) |
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

Complete the Customer Mobile Application by implementing order management, push notifications, loyalty points integration, user profile management, and prepare for beta release to app stores (TestFlight for iOS and Internal Testing for Android).

### 1.2 Objectives

| # | Objective | Priority | Success Metric |
|---|-----------|----------|----------------|
| 1 | Implement order history listing with filtering | Critical | Users can view all past orders |
| 2 | Build real-time order tracking with status updates | Critical | Live tracking functional |
| 3 | Integrate Firebase Cloud Messaging for push notifications | Critical | >95% delivery rate |
| 4 | Display loyalty points balance and history | High | Points sync within 5 seconds |
| 5 | Create comprehensive profile management screens | High | All profile fields editable |
| 6 | Implement app settings and preferences | Medium | Settings persist across sessions |
| 7 | Add delivery address management | Critical | Multiple addresses supported |
| 8 | Build notification preferences screen | Medium | Users can control notifications |
| 9 | Prepare app for beta release | Critical | Apps submitted to stores |
| 10 | Complete Customer App documentation | High | User guides ready |

### 1.3 Key Deliverables

| # | Deliverable | Type | Owner | Acceptance Criteria |
|---|-------------|------|-------|---------------------|
| 1 | Order History Screen | Flutter UI | Dev 3 | Infinite scroll, filtering, search |
| 2 | Order Detail Screen | Flutter UI | Dev 3 | Timeline view, reorder function |
| 3 | Order Tracking Map | Flutter UI | Dev 3 | Real-time GPS updates |
| 4 | Order APIs | Odoo REST | Dev 1 | CRUD + tracking endpoints |
| 5 | FCM Integration | Backend | Dev 1 | Token registration, send notifications |
| 6 | Push Notification Handler | Flutter | Dev 2 | Foreground/background handling |
| 7 | Loyalty Points Screen | Flutter UI | Dev 3 | Balance, history, redemption |
| 8 | Profile Screen | Flutter UI | Dev 3 | Edit all user fields |
| 9 | Settings Screen | Flutter UI | Dev 3 | Preferences management |
| 10 | Address Management | Full Stack | Dev 2 | Add/edit/delete addresses |
| 11 | Beta Release Build | DevOps | Dev 2 | TestFlight + Play Internal |
| 12 | User Documentation | Docs | Dev 2 | Customer app user guide |

### 1.4 Prerequisites

| # | Prerequisite | Source | Status |
|---|--------------|--------|--------|
| 1 | Cart and checkout complete | Milestone 54 | Required |
| 2 | Payment integration tested | Milestone 54 | Required |
| 3 | User authentication working | Milestone 52 | Required |
| 4 | Firebase project configured | Milestone 51 | Required |
| 5 | Apple Developer account active | Infrastructure | Required |
| 6 | Google Play Console access | Infrastructure | Required |
| 7 | App signing keys generated | DevOps | Required |

### 1.5 Success Criteria

- [ ] Order history loads within 2 seconds
- [ ] Order tracking updates every 30 seconds
- [ ] Push notifications delivered within 5 seconds
- [ ] Loyalty points sync accurately
- [ ] Profile updates save immediately
- [ ] Settings persist across app restarts
- [ ] Beta app approved on TestFlight
- [ ] Beta app approved on Play Store Internal Testing
- [ ] Code coverage >80%
- [ ] Zero critical bugs in beta release

---

## 2. Requirement Traceability Matrix

### 2.1 RFP Requirements

| RFP Req ID | Requirement Description | Implementation | Day |
|------------|------------------------|----------------|-----|
| RFP-MOB-005 | Order tracking and notifications | Order tracking screen, FCM | 291-294 |
| RFP-MOB-015 | Push notification system | FCM integration | 293-294 |
| RFP-MOB-016 | Loyalty points display | Points screen | 295-296 |
| RFP-MOB-017 | User profile management | Profile screens | 297-298 |
| RFP-MOB-018 | App store deployment | Beta release | 299-300 |

### 2.2 BRD Requirements

| BRD Req ID | Business Requirement | Implementation | Day |
|------------|---------------------|----------------|-----|
| BRD-MOB-004 | Customer self-service order tracking | Tracking map, timeline | 291-292 |
| BRD-MOB-005 | Proactive customer communication | Push notifications | 293-294 |
| BRD-MOB-006 | Loyalty program engagement | Points display, history | 295-296 |
| BRD-MOB-007 | Multiple delivery addresses | Address management | 297-298 |

### 2.3 SRS Requirements

| SRS Req ID | Technical Requirement | Implementation | Day |
|------------|----------------------|----------------|-----|
| SRS-MOB-030 | Order list pagination | Infinite scroll implementation | 291 |
| SRS-MOB-031 | Order status webhook | Real-time status updates | 292 |
| SRS-MOB-032 | FCM token management | Token registration API | 293 |
| SRS-MOB-033 | Notification preferences | Settings screen | 294 |
| SRS-MOB-034 | Points calculation display | Loyalty screen | 295 |
| SRS-MOB-035 | Profile image upload | Image picker + S3 | 297 |

---

## 3. Day-by-Day Breakdown

### Day 291 (Sprint Day 1): Order History APIs & List Screen

#### Objectives
- Build order listing API with pagination and filtering
- Create order history Flutter screen with infinite scroll
- Implement order search and filtering UI

#### Dev 1 - Backend Lead (8 hours)

**Task 1.1: Order Listing API (4 hours)**

Create comprehensive order listing endpoint with filtering capabilities.

```python
# odoo/addons/smart_dairy_mobile_api/controllers/order_controller.py

from odoo import http
from odoo.http import request
import json
from datetime import datetime, timedelta

class OrderController(http.Controller):

    @http.route('/api/v1/orders', type='json', auth='jwt', methods=['GET'], csrf=False)
    def get_orders(self, **kwargs):
        """
        Get customer orders with pagination and filtering.

        Query Parameters:
            - page: Page number (default 1)
            - limit: Items per page (default 20, max 50)
            - status: Filter by status (draft, confirmed, processing, shipped, delivered, cancelled)
            - date_from: Filter orders from date (YYYY-MM-DD)
            - date_to: Filter orders to date (YYYY-MM-DD)
            - search: Search in order number
        """
        try:
            current_user = request.env.user
            partner_id = current_user.partner_id.id

            # Pagination
            page = int(kwargs.get('page', 1))
            limit = min(int(kwargs.get('limit', 20)), 50)
            offset = (page - 1) * limit

            # Build domain
            domain = [
                ('partner_id', '=', partner_id),
                ('state', '!=', 'cancel'),
            ]

            # Status filter
            status = kwargs.get('status')
            if status:
                status_mapping = {
                    'draft': ['draft', 'sent'],
                    'confirmed': ['sale'],
                    'processing': ['processing'],
                    'shipped': ['shipped'],
                    'delivered': ['done'],
                    'cancelled': ['cancel']
                }
                if status in status_mapping:
                    domain.append(('state', 'in', status_mapping[status]))

            # Date filters
            date_from = kwargs.get('date_from')
            date_to = kwargs.get('date_to')
            if date_from:
                domain.append(('date_order', '>=', date_from))
            if date_to:
                domain.append(('date_order', '<=', date_to + ' 23:59:59'))

            # Search
            search = kwargs.get('search')
            if search:
                domain.append(('name', 'ilike', search))

            # Query orders
            SaleOrder = request.env['sale.order'].sudo()
            total_count = SaleOrder.search_count(domain)
            orders = SaleOrder.search(
                domain,
                offset=offset,
                limit=limit,
                order='date_order desc'
            )

            # Serialize orders
            orders_data = []
            for order in orders:
                orders_data.append(self._serialize_order_list(order))

            return {
                'success': True,
                'data': {
                    'orders': orders_data,
                    'pagination': {
                        'page': page,
                        'limit': limit,
                        'total_count': total_count,
                        'total_pages': (total_count + limit - 1) // limit,
                        'has_next': offset + limit < total_count,
                        'has_previous': page > 1
                    }
                }
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    def _serialize_order_list(self, order):
        """Serialize order for list view (lightweight)."""
        # Get primary product image
        first_line = order.order_line[:1]
        product_image = None
        if first_line and first_line.product_id.image_256:
            product_image = f"/web/image/product.product/{first_line.product_id.id}/image_256"

        return {
            'id': order.id,
            'name': order.name,
            'date_order': order.date_order.isoformat() if order.date_order else None,
            'state': self._get_display_state(order),
            'state_color': self._get_state_color(order),
            'amount_total': order.amount_total,
            'currency': order.currency_id.symbol,
            'item_count': len(order.order_line),
            'product_image': product_image,
            'delivery_date': order.commitment_date.isoformat() if order.commitment_date else None,
            'can_cancel': order.state in ['draft', 'sent', 'sale'],
            'can_reorder': order.state == 'done'
        }

    def _get_display_state(self, order):
        """Get user-friendly order status."""
        state_map = {
            'draft': 'Pending',
            'sent': 'Pending',
            'sale': 'Confirmed',
            'processing': 'Processing',
            'shipped': 'Shipped',
            'done': 'Delivered',
            'cancel': 'Cancelled'
        }
        # Check for custom tracking state
        if hasattr(order, 'tracking_state') and order.tracking_state:
            return order.tracking_state
        return state_map.get(order.state, 'Unknown')

    def _get_state_color(self, order):
        """Get status badge color."""
        color_map = {
            'draft': 'orange',
            'sent': 'orange',
            'sale': 'blue',
            'processing': 'purple',
            'shipped': 'teal',
            'done': 'green',
            'cancel': 'red'
        }
        return color_map.get(order.state, 'grey')
```

**Task 1.2: Order Statistics API (2 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/order_controller.py (continued)

    @http.route('/api/v1/orders/stats', type='json', auth='jwt', methods=['GET'], csrf=False)
    def get_order_stats(self, **kwargs):
        """Get order statistics for the current user."""
        try:
            current_user = request.env.user
            partner_id = current_user.partner_id.id

            SaleOrder = request.env['sale.order'].sudo()

            # Count by status
            stats = {
                'total_orders': SaleOrder.search_count([
                    ('partner_id', '=', partner_id),
                    ('state', '!=', 'cancel')
                ]),
                'pending': SaleOrder.search_count([
                    ('partner_id', '=', partner_id),
                    ('state', 'in', ['draft', 'sent'])
                ]),
                'confirmed': SaleOrder.search_count([
                    ('partner_id', '=', partner_id),
                    ('state', '=', 'sale')
                ]),
                'processing': SaleOrder.search_count([
                    ('partner_id', '=', partner_id),
                    ('state', '=', 'processing')
                ]),
                'shipped': SaleOrder.search_count([
                    ('partner_id', '=', partner_id),
                    ('state', '=', 'shipped')
                ]),
                'delivered': SaleOrder.search_count([
                    ('partner_id', '=', partner_id),
                    ('state', '=', 'done')
                ]),
                'cancelled': SaleOrder.search_count([
                    ('partner_id', '=', partner_id),
                    ('state', '=', 'cancel')
                ])
            }

            # Recent orders summary
            thirty_days_ago = datetime.now() - timedelta(days=30)
            recent_orders = SaleOrder.search([
                ('partner_id', '=', partner_id),
                ('state', '=', 'done'),
                ('date_order', '>=', thirty_days_ago.strftime('%Y-%m-%d'))
            ])

            stats['last_30_days'] = {
                'order_count': len(recent_orders),
                'total_spent': sum(recent_orders.mapped('amount_total'))
            }

            return {'success': True, 'data': stats}

        except Exception as e:
            return {'success': False, 'error': str(e)}
```

**Task 1.3: Order Repository & Provider Setup (2 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/models/sale_order_extend.py

from odoo import models, fields, api

class SaleOrderExtend(models.Model):
    _inherit = 'sale.order'

    # Mobile tracking fields
    tracking_state = fields.Char('Tracking State', readonly=True)
    tracking_updated = fields.Datetime('Tracking Updated', readonly=True)
    delivery_person_id = fields.Many2one('res.partner', 'Delivery Person')
    delivery_latitude = fields.Float('Delivery Latitude', digits=(10, 7))
    delivery_longitude = fields.Float('Delivery Longitude', digits=(10, 7))
    estimated_delivery = fields.Datetime('Estimated Delivery')
    actual_delivery = fields.Datetime('Actual Delivery')
    delivery_notes = fields.Text('Delivery Notes')
    customer_rating = fields.Integer('Customer Rating')
    customer_feedback = fields.Text('Customer Feedback')

    # FCM notification tracking
    notification_sent = fields.Boolean('Notification Sent', default=False)
    last_notification_time = fields.Datetime('Last Notification Time')

    def update_tracking_state(self, state, latitude=None, longitude=None):
        """Update order tracking state and notify customer."""
        self.ensure_one()
        self.write({
            'tracking_state': state,
            'tracking_updated': fields.Datetime.now(),
            'delivery_latitude': latitude or self.delivery_latitude,
            'delivery_longitude': longitude or self.delivery_longitude
        })

        # Send push notification
        self._send_tracking_notification(state)

        return True

    def _send_tracking_notification(self, state):
        """Send FCM notification for tracking update."""
        notification_service = self.env['mobile.notification.service'].sudo()

        title_map = {
            'confirmed': 'Order Confirmed!',
            'processing': 'Order Being Prepared',
            'shipped': 'Order On The Way!',
            'delivered': 'Order Delivered!'
        }

        body_map = {
            'confirmed': f'Your order {self.name} has been confirmed.',
            'processing': f'Your order {self.name} is being prepared.',
            'shipped': f'Your order {self.name} is out for delivery.',
            'delivered': f'Your order {self.name} has been delivered. Enjoy!'
        }

        if state in title_map:
            notification_service.send_to_user(
                user_id=self.partner_id.user_ids[:1].id,
                title=title_map[state],
                body=body_map[state],
                data={
                    'type': 'order_tracking',
                    'order_id': self.id,
                    'order_name': self.name,
                    'state': state
                }
            )
```

#### Dev 2 - Full-Stack Developer (8 hours)

**Task 2.1: Order Repository (3 hours)**

```dart
// lib/features/orders/data/repositories/order_repository_impl.dart

import 'package:dartz/dartz.dart';
import 'package:smart_dairy_customer/core/error/failures.dart';
import 'package:smart_dairy_customer/core/network/api_client.dart';
import 'package:smart_dairy_customer/features/orders/data/models/order_model.dart';
import 'package:smart_dairy_customer/features/orders/domain/entities/order.dart';
import 'package:smart_dairy_customer/features/orders/domain/repositories/order_repository.dart';

class OrderRepositoryImpl implements OrderRepository {
  final ApiClient _apiClient;
  final OrderLocalDataSource _localDataSource;

  OrderRepositoryImpl(this._apiClient, this._localDataSource);

  @override
  Future<Either<Failure, OrderListResponse>> getOrders({
    int page = 1,
    int limit = 20,
    String? status,
    DateTime? dateFrom,
    DateTime? dateTo,
    String? search,
  }) async {
    try {
      final queryParams = <String, dynamic>{
        'page': page,
        'limit': limit,
      };

      if (status != null) queryParams['status'] = status;
      if (dateFrom != null) queryParams['date_from'] = _formatDate(dateFrom);
      if (dateTo != null) queryParams['date_to'] = _formatDate(dateTo);
      if (search != null && search.isNotEmpty) queryParams['search'] = search;

      final response = await _apiClient.get(
        '/api/v1/orders',
        queryParameters: queryParams,
      );

      if (response['success'] == true) {
        final data = response['data'];
        final orders = (data['orders'] as List)
            .map((json) => OrderModel.fromJson(json))
            .toList();

        final pagination = PaginationInfo.fromJson(data['pagination']);

        // Cache orders locally
        await _localDataSource.cacheOrders(orders);

        return Right(OrderListResponse(orders: orders, pagination: pagination));
      }

      return Left(ServerFailure(response['error'] ?? 'Failed to fetch orders'));
    } catch (e) {
      // Try to return cached orders on error
      try {
        final cachedOrders = await _localDataSource.getCachedOrders();
        if (cachedOrders.isNotEmpty) {
          return Right(OrderListResponse(
            orders: cachedOrders,
            pagination: PaginationInfo(
              page: 1,
              limit: cachedOrders.length,
              totalCount: cachedOrders.length,
              totalPages: 1,
              hasNext: false,
              hasPrevious: false,
            ),
            isFromCache: true,
          ));
        }
      } catch (_) {}

      return Left(NetworkFailure(e.toString()));
    }
  }

  @override
  Future<Either<Failure, OrderStats>> getOrderStats() async {
    try {
      final response = await _apiClient.get('/api/v1/orders/stats');

      if (response['success'] == true) {
        return Right(OrderStats.fromJson(response['data']));
      }

      return Left(ServerFailure(response['error'] ?? 'Failed to fetch stats'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  String _formatDate(DateTime date) {
    return '${date.year}-${date.month.toString().padLeft(2, '0')}-${date.day.toString().padLeft(2, '0')}';
  }
}
```

**Task 2.2: Order Models (3 hours)**

```dart
// lib/features/orders/data/models/order_model.dart

import 'package:freezed_annotation/freezed_annotation.dart';

part 'order_model.freezed.dart';
part 'order_model.g.dart';

@freezed
class OrderModel with _$OrderModel {
  const factory OrderModel({
    required int id,
    required String name,
    required DateTime dateOrder,
    required String state,
    required String stateColor,
    required double amountTotal,
    required String currency,
    required int itemCount,
    String? productImage,
    DateTime? deliveryDate,
    required bool canCancel,
    required bool canReorder,
  }) = _OrderModel;

  factory OrderModel.fromJson(Map<String, dynamic> json) => _$OrderModelFromJson(json);
}

@freezed
class OrderListResponse with _$OrderListResponse {
  const factory OrderListResponse({
    required List<OrderModel> orders,
    required PaginationInfo pagination,
    @Default(false) bool isFromCache,
  }) = _OrderListResponse;
}

@freezed
class PaginationInfo with _$PaginationInfo {
  const factory PaginationInfo({
    required int page,
    required int limit,
    required int totalCount,
    required int totalPages,
    required bool hasNext,
    required bool hasPrevious,
  }) = _PaginationInfo;

  factory PaginationInfo.fromJson(Map<String, dynamic> json) => _$PaginationInfoFromJson(json);
}

@freezed
class OrderStats with _$OrderStats {
  const factory OrderStats({
    required int totalOrders,
    required int pending,
    required int confirmed,
    required int processing,
    required int shipped,
    required int delivered,
    required int cancelled,
    required Last30DaysStats last30Days,
  }) = _OrderStats;

  factory OrderStats.fromJson(Map<String, dynamic> json) => _$OrderStatsFromJson(json);
}

@freezed
class Last30DaysStats with _$Last30DaysStats {
  const factory Last30DaysStats({
    required int orderCount,
    required double totalSpent,
  }) = _Last30DaysStats;

  factory Last30DaysStats.fromJson(Map<String, dynamic> json) => _$Last30DaysStatsFromJson(json);
}
```

**Task 2.3: Order Local Data Source (2 hours)**

```dart
// lib/features/orders/data/datasources/order_local_datasource.dart

import 'package:drift/drift.dart';
import 'package:smart_dairy_customer/core/database/app_database.dart';
import 'package:smart_dairy_customer/features/orders/data/models/order_model.dart';

class OrderLocalDataSource {
  final AppDatabase _database;

  OrderLocalDataSource(this._database);

  Future<void> cacheOrders(List<OrderModel> orders) async {
    await _database.batch((batch) {
      batch.insertAllOnConflictUpdate(
        _database.cachedOrders,
        orders.map((order) => CachedOrdersCompanion.insert(
          orderId: order.id,
          name: order.name,
          dateOrder: order.dateOrder,
          state: order.state,
          stateColor: order.stateColor,
          amountTotal: order.amountTotal,
          currency: order.currency,
          itemCount: order.itemCount,
          productImage: Value(order.productImage),
          deliveryDate: Value(order.deliveryDate),
          canCancel: order.canCancel,
          canReorder: order.canReorder,
          cachedAt: DateTime.now(),
        )).toList(),
      );
    });
  }

  Future<List<OrderModel>> getCachedOrders() async {
    final rows = await (_database.select(_database.cachedOrders)
      ..orderBy([(t) => OrderingTerm.desc(t.dateOrder)])
      ..limit(50))
      .get();

    return rows.map((row) => OrderModel(
      id: row.orderId,
      name: row.name,
      dateOrder: row.dateOrder,
      state: row.state,
      stateColor: row.stateColor,
      amountTotal: row.amountTotal,
      currency: row.currency,
      itemCount: row.itemCount,
      productImage: row.productImage,
      deliveryDate: row.deliveryDate,
      canCancel: row.canCancel,
      canReorder: row.canReorder,
    )).toList();
  }

  Future<void> clearCache() async {
    await _database.delete(_database.cachedOrders).go();
  }
}
```

#### Dev 3 - Mobile Lead (8 hours)

**Task 3.1: Orders Provider (2 hours)**

```dart
// lib/features/orders/presentation/providers/orders_provider.dart

import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:smart_dairy_customer/features/orders/data/models/order_model.dart';
import 'package:smart_dairy_customer/features/orders/domain/repositories/order_repository.dart';

// Order list state
class OrderListState {
  final List<OrderModel> orders;
  final bool isLoading;
  final bool isLoadingMore;
  final String? error;
  final PaginationInfo? pagination;
  final OrderFilter filter;
  final bool isFromCache;

  const OrderListState({
    this.orders = const [],
    this.isLoading = false,
    this.isLoadingMore = false,
    this.error,
    this.pagination,
    this.filter = const OrderFilter(),
    this.isFromCache = false,
  });

  OrderListState copyWith({
    List<OrderModel>? orders,
    bool? isLoading,
    bool? isLoadingMore,
    String? error,
    PaginationInfo? pagination,
    OrderFilter? filter,
    bool? isFromCache,
  }) {
    return OrderListState(
      orders: orders ?? this.orders,
      isLoading: isLoading ?? this.isLoading,
      isLoadingMore: isLoadingMore ?? this.isLoadingMore,
      error: error,
      pagination: pagination ?? this.pagination,
      filter: filter ?? this.filter,
      isFromCache: isFromCache ?? this.isFromCache,
    );
  }

  bool get canLoadMore => pagination?.hasNext ?? false;
}

class OrderFilter {
  final String? status;
  final DateTime? dateFrom;
  final DateTime? dateTo;
  final String? search;

  const OrderFilter({
    this.status,
    this.dateFrom,
    this.dateTo,
    this.search,
  });

  OrderFilter copyWith({
    String? status,
    DateTime? dateFrom,
    DateTime? dateTo,
    String? search,
    bool clearStatus = false,
    bool clearDates = false,
    bool clearSearch = false,
  }) {
    return OrderFilter(
      status: clearStatus ? null : (status ?? this.status),
      dateFrom: clearDates ? null : (dateFrom ?? this.dateFrom),
      dateTo: clearDates ? null : (dateTo ?? this.dateTo),
      search: clearSearch ? null : (search ?? this.search),
    );
  }

  bool get hasActiveFilters =>
      status != null || dateFrom != null || dateTo != null || search != null;
}

// Orders notifier
class OrderListNotifier extends StateNotifier<OrderListState> {
  final OrderRepository _repository;

  OrderListNotifier(this._repository) : super(const OrderListState());

  Future<void> loadOrders({bool refresh = false}) async {
    if (state.isLoading) return;

    state = state.copyWith(
      isLoading: true,
      error: null,
      orders: refresh ? [] : state.orders,
    );

    final result = await _repository.getOrders(
      page: 1,
      status: state.filter.status,
      dateFrom: state.filter.dateFrom,
      dateTo: state.filter.dateTo,
      search: state.filter.search,
    );

    result.fold(
      (failure) => state = state.copyWith(
        isLoading: false,
        error: failure.message,
      ),
      (response) => state = state.copyWith(
        isLoading: false,
        orders: response.orders,
        pagination: response.pagination,
        isFromCache: response.isFromCache,
      ),
    );
  }

  Future<void> loadMore() async {
    if (state.isLoadingMore || !state.canLoadMore) return;

    state = state.copyWith(isLoadingMore: true);

    final nextPage = (state.pagination?.page ?? 0) + 1;

    final result = await _repository.getOrders(
      page: nextPage,
      status: state.filter.status,
      dateFrom: state.filter.dateFrom,
      dateTo: state.filter.dateTo,
      search: state.filter.search,
    );

    result.fold(
      (failure) => state = state.copyWith(isLoadingMore: false),
      (response) => state = state.copyWith(
        isLoadingMore: false,
        orders: [...state.orders, ...response.orders],
        pagination: response.pagination,
      ),
    );
  }

  void updateFilter(OrderFilter filter) {
    state = state.copyWith(filter: filter);
    loadOrders(refresh: true);
  }

  void clearFilters() {
    state = state.copyWith(filter: const OrderFilter());
    loadOrders(refresh: true);
  }
}

// Providers
final orderRepositoryProvider = Provider<OrderRepository>((ref) {
  throw UnimplementedError('Override in main.dart');
});

final orderListProvider =
    StateNotifierProvider<OrderListNotifier, OrderListState>((ref) {
  return OrderListNotifier(ref.watch(orderRepositoryProvider));
});

final orderStatsProvider = FutureProvider<OrderStats>((ref) async {
  final repository = ref.watch(orderRepositoryProvider);
  final result = await repository.getOrderStats();
  return result.fold(
    (failure) => throw Exception(failure.message),
    (stats) => stats,
  );
});
```

**Task 3.2: Order History Screen (4 hours)**

```dart
// lib/features/orders/presentation/screens/order_history_screen.dart

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:smart_dairy_customer/core/theme/app_colors.dart';
import 'package:smart_dairy_customer/features/orders/presentation/providers/orders_provider.dart';
import 'package:smart_dairy_customer/features/orders/presentation/widgets/order_card.dart';
import 'package:smart_dairy_customer/features/orders/presentation/widgets/order_filter_sheet.dart';
import 'package:smart_dairy_customer/features/orders/presentation/widgets/order_stats_header.dart';

class OrderHistoryScreen extends ConsumerStatefulWidget {
  const OrderHistoryScreen({super.key});

  @override
  ConsumerState<OrderHistoryScreen> createState() => _OrderHistoryScreenState();
}

class _OrderHistoryScreenState extends ConsumerState<OrderHistoryScreen> {
  final _scrollController = ScrollController();
  final _searchController = TextEditingController();

  @override
  void initState() {
    super.initState();
    _scrollController.addListener(_onScroll);

    // Load orders on init
    WidgetsBinding.instance.addPostFrameCallback((_) {
      ref.read(orderListProvider.notifier).loadOrders();
    });
  }

  @override
  void dispose() {
    _scrollController.dispose();
    _searchController.dispose();
    super.dispose();
  }

  void _onScroll() {
    if (_scrollController.position.pixels >=
        _scrollController.position.maxScrollExtent - 200) {
      ref.read(orderListProvider.notifier).loadMore();
    }
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(orderListProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('My Orders'),
        actions: [
          IconButton(
            icon: Badge(
              isLabelVisible: state.filter.hasActiveFilters,
              child: const Icon(Icons.filter_list),
            ),
            onPressed: () => _showFilterSheet(context),
          ),
        ],
      ),
      body: Column(
        children: [
          // Search bar
          Padding(
            padding: const EdgeInsets.all(16),
            child: TextField(
              controller: _searchController,
              decoration: InputDecoration(
                hintText: 'Search orders...',
                prefixIcon: const Icon(Icons.search),
                suffixIcon: _searchController.text.isNotEmpty
                    ? IconButton(
                        icon: const Icon(Icons.clear),
                        onPressed: () {
                          _searchController.clear();
                          ref.read(orderListProvider.notifier).updateFilter(
                            state.filter.copyWith(clearSearch: true),
                          );
                        },
                      )
                    : null,
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
                filled: true,
                fillColor: Colors.grey[100],
              ),
              onSubmitted: (value) {
                ref.read(orderListProvider.notifier).updateFilter(
                  state.filter.copyWith(search: value),
                );
              },
            ),
          ),

          // Stats header
          const OrderStatsHeader(),

          // Status filter chips
          _buildStatusChips(state),

          // Orders list
          Expanded(
            child: _buildOrderList(state),
          ),
        ],
      ),
    );
  }

  Widget _buildStatusChips(OrderListState state) {
    final statuses = [
      ('All', null),
      ('Pending', 'pending'),
      ('Confirmed', 'confirmed'),
      ('Shipped', 'shipped'),
      ('Delivered', 'delivered'),
    ];

    return SizedBox(
      height: 50,
      child: ListView.separated(
        scrollDirection: Axis.horizontal,
        padding: const EdgeInsets.symmetric(horizontal: 16),
        itemCount: statuses.length,
        separatorBuilder: (_, __) => const SizedBox(width: 8),
        itemBuilder: (context, index) {
          final (label, value) = statuses[index];
          final isSelected = state.filter.status == value;

          return FilterChip(
            label: Text(label),
            selected: isSelected,
            onSelected: (_) {
              ref.read(orderListProvider.notifier).updateFilter(
                state.filter.copyWith(
                  status: value,
                  clearStatus: value == null,
                ),
              );
            },
            selectedColor: AppColors.primary.withOpacity(0.2),
            checkmarkColor: AppColors.primary,
          );
        },
      ),
    );
  }

  Widget _buildOrderList(OrderListState state) {
    if (state.isLoading && state.orders.isEmpty) {
      return const Center(child: CircularProgressIndicator());
    }

    if (state.error != null && state.orders.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.error_outline, size: 64, color: Colors.grey[400]),
            const SizedBox(height: 16),
            Text(state.error!, style: TextStyle(color: Colors.grey[600])),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: () => ref.read(orderListProvider.notifier).loadOrders(),
              child: const Text('Retry'),
            ),
          ],
        ),
      );
    }

    if (state.orders.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.shopping_bag_outlined, size: 64, color: Colors.grey[400]),
            const SizedBox(height: 16),
            Text(
              'No orders found',
              style: TextStyle(color: Colors.grey[600], fontSize: 16),
            ),
            if (state.filter.hasActiveFilters) ...[
              const SizedBox(height: 8),
              TextButton(
                onPressed: () => ref.read(orderListProvider.notifier).clearFilters(),
                child: const Text('Clear filters'),
              ),
            ],
          ],
        ),
      );
    }

    return RefreshIndicator(
      onRefresh: () => ref.read(orderListProvider.notifier).loadOrders(refresh: true),
      child: ListView.builder(
        controller: _scrollController,
        padding: const EdgeInsets.all(16),
        itemCount: state.orders.length + (state.isLoadingMore ? 1 : 0),
        itemBuilder: (context, index) {
          if (index == state.orders.length) {
            return const Center(
              child: Padding(
                padding: EdgeInsets.all(16),
                child: CircularProgressIndicator(),
              ),
            );
          }

          final order = state.orders[index];
          return Padding(
            padding: const EdgeInsets.only(bottom: 12),
            child: OrderCard(
              order: order,
              onTap: () => _navigateToDetail(order.id),
              onReorder: order.canReorder ? () => _reorder(order.id) : null,
            ),
          );
        },
      ),
    );
  }

  void _showFilterSheet(BuildContext context) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (_) => const OrderFilterSheet(),
    );
  }

  void _navigateToDetail(int orderId) {
    Navigator.pushNamed(context, '/order-detail', arguments: orderId);
  }

  void _reorder(int orderId) {
    // Implement reorder logic
  }
}
```

**Task 3.3: Order Card Widget (2 hours)**

```dart
// lib/features/orders/presentation/widgets/order_card.dart

import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:smart_dairy_customer/core/theme/app_colors.dart';
import 'package:smart_dairy_customer/features/orders/data/models/order_model.dart';

class OrderCard extends StatelessWidget {
  final OrderModel order;
  final VoidCallback onTap;
  final VoidCallback? onReorder;

  const OrderCard({
    super.key,
    required this.order,
    required this.onTap,
    this.onReorder,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Header row
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    order.name,
                    style: const TextStyle(
                      fontWeight: FontWeight.bold,
                      fontSize: 16,
                    ),
                  ),
                  _buildStatusBadge(),
                ],
              ),

              const SizedBox(height: 12),

              // Order info row
              Row(
                children: [
                  // Product image
                  if (order.productImage != null)
                    ClipRRect(
                      borderRadius: BorderRadius.circular(8),
                      child: Image.network(
                        order.productImage!,
                        width: 60,
                        height: 60,
                        fit: BoxFit.cover,
                        errorBuilder: (_, __, ___) => Container(
                          width: 60,
                          height: 60,
                          color: Colors.grey[200],
                          child: const Icon(Icons.image),
                        ),
                      ),
                    )
                  else
                    Container(
                      width: 60,
                      height: 60,
                      decoration: BoxDecoration(
                        color: Colors.grey[200],
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: const Icon(Icons.shopping_bag),
                    ),

                  const SizedBox(width: 12),

                  // Order details
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          DateFormat('MMM dd, yyyy • hh:mm a')
                              .format(order.dateOrder),
                          style: TextStyle(
                            color: Colors.grey[600],
                            fontSize: 13,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          '${order.itemCount} item${order.itemCount > 1 ? 's' : ''}',
                          style: TextStyle(
                            color: Colors.grey[600],
                            fontSize: 13,
                          ),
                        ),
                        if (order.deliveryDate != null) ...[
                          const SizedBox(height: 4),
                          Row(
                            children: [
                              Icon(
                                Icons.local_shipping_outlined,
                                size: 14,
                                color: Colors.grey[600],
                              ),
                              const SizedBox(width: 4),
                              Text(
                                'Est. ${DateFormat('MMM dd').format(order.deliveryDate!)}',
                                style: TextStyle(
                                  color: Colors.grey[600],
                                  fontSize: 13,
                                ),
                              ),
                            ],
                          ),
                        ],
                      ],
                    ),
                  ),

                  // Amount
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.end,
                    children: [
                      Text(
                        '${order.currency}${order.amountTotal.toStringAsFixed(2)}',
                        style: const TextStyle(
                          fontWeight: FontWeight.bold,
                          fontSize: 16,
                        ),
                      ),
                    ],
                  ),
                ],
              ),

              // Actions row
              if (order.canReorder || order.canCancel) ...[
                const SizedBox(height: 12),
                const Divider(height: 1),
                const SizedBox(height: 12),
                Row(
                  mainAxisAlignment: MainAxisAlignment.end,
                  children: [
                    if (order.canReorder && onReorder != null)
                      TextButton.icon(
                        onPressed: onReorder,
                        icon: const Icon(Icons.replay, size: 18),
                        label: const Text('Reorder'),
                        style: TextButton.styleFrom(
                          foregroundColor: AppColors.primary,
                        ),
                      ),
                    const SizedBox(width: 8),
                    TextButton.icon(
                      onPressed: onTap,
                      icon: const Icon(Icons.visibility_outlined, size: 18),
                      label: const Text('View Details'),
                    ),
                  ],
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildStatusBadge() {
    final color = _getStatusColor(order.stateColor);

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: color.withOpacity(0.3)),
      ),
      child: Text(
        order.state,
        style: TextStyle(
          color: color,
          fontSize: 12,
          fontWeight: FontWeight.w600,
        ),
      ),
    );
  }

  Color _getStatusColor(String colorName) {
    switch (colorName) {
      case 'orange':
        return Colors.orange;
      case 'blue':
        return Colors.blue;
      case 'purple':
        return Colors.purple;
      case 'teal':
        return Colors.teal;
      case 'green':
        return Colors.green;
      case 'red':
        return Colors.red;
      default:
        return Colors.grey;
    }
  }
}
```

#### End-of-Day 291 Deliverables

- [ ] Order listing API with pagination and filtering
- [ ] Order statistics API endpoint
- [ ] Order repository implementation
- [ ] Order models with Freezed
- [ ] Local caching for orders
- [ ] Orders provider with state management
- [ ] Order history screen with infinite scroll
- [ ] Order card widget
- [ ] Status filter chips

---

### Day 292 (Sprint Day 2): Order Detail & Tracking

#### Objectives
- Build comprehensive order detail API
- Create order detail screen with timeline
- Implement real-time tracking map

#### Dev 1 - Backend Lead (8 hours)

**Task 1.1: Order Detail API (4 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/order_controller.py (continued)

    @http.route('/api/v1/orders/<int:order_id>', type='json', auth='jwt', methods=['GET'], csrf=False)
    def get_order_detail(self, order_id, **kwargs):
        """Get detailed order information including tracking."""
        try:
            current_user = request.env.user
            partner_id = current_user.partner_id.id

            order = request.env['sale.order'].sudo().search([
                ('id', '=', order_id),
                ('partner_id', '=', partner_id)
            ], limit=1)

            if not order:
                return {'success': False, 'error': 'Order not found'}

            return {
                'success': True,
                'data': self._serialize_order_detail(order)
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    def _serialize_order_detail(self, order):
        """Serialize complete order details."""
        # Order lines
        lines = []
        for line in order.order_line:
            lines.append({
                'id': line.id,
                'product_id': line.product_id.id,
                'product_name': line.product_id.name,
                'product_image': f"/web/image/product.product/{line.product_id.id}/image_256" if line.product_id.image_256 else None,
                'quantity': line.product_uom_qty,
                'uom': line.product_uom.name,
                'unit_price': line.price_unit,
                'discount': line.discount,
                'subtotal': line.price_subtotal,
                'tax_amount': line.price_tax,
                'total': line.price_total,
            })

        # Delivery address
        delivery_address = None
        if order.partner_shipping_id:
            addr = order.partner_shipping_id
            delivery_address = {
                'id': addr.id,
                'name': addr.name,
                'street': addr.street,
                'street2': addr.street2,
                'city': addr.city,
                'state': addr.state_id.name if addr.state_id else None,
                'zip': addr.zip,
                'country': addr.country_id.name if addr.country_id else None,
                'phone': addr.phone,
                'latitude': addr.partner_latitude,
                'longitude': addr.partner_longitude,
            }

        # Payment info
        payment_info = None
        if order.payment_tx_ids:
            tx = order.payment_tx_ids[:1]
            payment_info = {
                'method': tx.provider_id.name if tx.provider_id else 'Unknown',
                'status': tx.state,
                'reference': tx.reference,
                'amount': tx.amount,
                'date': tx.create_date.isoformat() if tx.create_date else None,
            }

        # Tracking timeline
        timeline = self._get_order_timeline(order)

        # Delivery person info
        delivery_person = None
        if order.delivery_person_id:
            dp = order.delivery_person_id
            delivery_person = {
                'id': dp.id,
                'name': dp.name,
                'phone': dp.phone,
                'image': f"/web/image/res.partner/{dp.id}/image_128" if dp.image_128 else None,
            }

        return {
            'id': order.id,
            'name': order.name,
            'date_order': order.date_order.isoformat() if order.date_order else None,
            'state': self._get_display_state(order),
            'state_color': self._get_state_color(order),
            'tracking_state': order.tracking_state,

            # Amounts
            'subtotal': order.amount_untaxed,
            'tax_amount': order.amount_tax,
            'delivery_fee': order.delivery_fee if hasattr(order, 'delivery_fee') else 0,
            'discount_amount': order.discount_amount if hasattr(order, 'discount_amount') else 0,
            'amount_total': order.amount_total,
            'currency': order.currency_id.symbol,

            # Lines
            'lines': lines,
            'item_count': len(lines),

            # Addresses
            'delivery_address': delivery_address,

            # Payment
            'payment_info': payment_info,

            # Delivery
            'commitment_date': order.commitment_date.isoformat() if order.commitment_date else None,
            'estimated_delivery': order.estimated_delivery.isoformat() if order.estimated_delivery else None,
            'actual_delivery': order.actual_delivery.isoformat() if order.actual_delivery else None,
            'delivery_notes': order.delivery_notes,
            'delivery_person': delivery_person,

            # Tracking
            'timeline': timeline,
            'tracking_location': {
                'latitude': order.delivery_latitude,
                'longitude': order.delivery_longitude,
                'updated_at': order.tracking_updated.isoformat() if order.tracking_updated else None,
            } if order.delivery_latitude and order.delivery_longitude else None,

            # Actions
            'can_cancel': order.state in ['draft', 'sent', 'sale'],
            'can_reorder': order.state == 'done',
            'can_track': order.state in ['shipped', 'processing'],
            'can_rate': order.state == 'done' and not order.customer_rating,

            # Rating
            'customer_rating': order.customer_rating,
            'customer_feedback': order.customer_feedback,
        }

    def _get_order_timeline(self, order):
        """Build order status timeline."""
        timeline = []

        # Order placed
        timeline.append({
            'status': 'placed',
            'title': 'Order Placed',
            'description': f'Order {order.name} was placed',
            'timestamp': order.date_order.isoformat() if order.date_order else None,
            'completed': True,
            'icon': 'shopping_cart'
        })

        # Payment confirmed
        if order.payment_tx_ids and order.payment_tx_ids[:1].state == 'done':
            tx = order.payment_tx_ids[:1]
            timeline.append({
                'status': 'payment_confirmed',
                'title': 'Payment Confirmed',
                'description': f'Payment of {order.currency_id.symbol}{order.amount_total} received',
                'timestamp': tx.last_state_change.isoformat() if tx.last_state_change else None,
                'completed': True,
                'icon': 'payment'
            })

        # Order confirmed
        if order.state not in ['draft', 'sent', 'cancel']:
            timeline.append({
                'status': 'confirmed',
                'title': 'Order Confirmed',
                'description': 'Your order has been confirmed',
                'timestamp': order.date_order.isoformat() if order.date_order else None,
                'completed': True,
                'icon': 'check_circle'
            })

        # Processing
        if order.state in ['processing', 'shipped', 'done']:
            timeline.append({
                'status': 'processing',
                'title': 'Processing',
                'description': 'Your order is being prepared',
                'timestamp': None,  # Would come from tracking log
                'completed': True,
                'icon': 'inventory'
            })
        elif order.state not in ['draft', 'sent', 'cancel']:
            timeline.append({
                'status': 'processing',
                'title': 'Processing',
                'description': 'Your order will be prepared soon',
                'timestamp': None,
                'completed': False,
                'icon': 'inventory'
            })

        # Shipped
        if order.state in ['shipped', 'done']:
            timeline.append({
                'status': 'shipped',
                'title': 'Out for Delivery',
                'description': 'Your order is on the way',
                'timestamp': None,
                'completed': True,
                'icon': 'local_shipping'
            })
        elif order.state not in ['draft', 'sent', 'cancel']:
            timeline.append({
                'status': 'shipped',
                'title': 'Out for Delivery',
                'description': 'Waiting for dispatch',
                'timestamp': None,
                'completed': False,
                'icon': 'local_shipping'
            })

        # Delivered
        if order.state == 'done':
            timeline.append({
                'status': 'delivered',
                'title': 'Delivered',
                'description': 'Your order has been delivered',
                'timestamp': order.actual_delivery.isoformat() if order.actual_delivery else None,
                'completed': True,
                'icon': 'done_all'
            })
        elif order.state not in ['draft', 'sent', 'cancel']:
            timeline.append({
                'status': 'delivered',
                'title': 'Delivered',
                'description': f'Expected by {order.commitment_date.strftime("%b %d")}' if order.commitment_date else 'Delivery pending',
                'timestamp': None,
                'completed': False,
                'icon': 'done_all'
            })

        return timeline
```

**Task 1.2: Order Tracking API (2 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/order_controller.py (continued)

    @http.route('/api/v1/orders/<int:order_id>/tracking', type='json', auth='jwt', methods=['GET'], csrf=False)
    def get_order_tracking(self, order_id, **kwargs):
        """Get real-time tracking information for an order."""
        try:
            current_user = request.env.user
            partner_id = current_user.partner_id.id

            order = request.env['sale.order'].sudo().search([
                ('id', '=', order_id),
                ('partner_id', '=', partner_id),
                ('state', 'in', ['shipped', 'processing'])
            ], limit=1)

            if not order:
                return {'success': False, 'error': 'Order not found or not trackable'}

            tracking_data = {
                'order_id': order.id,
                'order_name': order.name,
                'state': order.tracking_state or self._get_display_state(order),
                'estimated_delivery': order.estimated_delivery.isoformat() if order.estimated_delivery else None,
            }

            # Current location
            if order.delivery_latitude and order.delivery_longitude:
                tracking_data['current_location'] = {
                    'latitude': order.delivery_latitude,
                    'longitude': order.delivery_longitude,
                    'updated_at': order.tracking_updated.isoformat() if order.tracking_updated else None,
                }

            # Delivery address (destination)
            if order.partner_shipping_id:
                addr = order.partner_shipping_id
                tracking_data['destination'] = {
                    'latitude': addr.partner_latitude,
                    'longitude': addr.partner_longitude,
                    'address': f"{addr.street}, {addr.city}",
                }

            # Delivery person
            if order.delivery_person_id:
                dp = order.delivery_person_id
                tracking_data['delivery_person'] = {
                    'id': dp.id,
                    'name': dp.name,
                    'phone': dp.phone,
                    'image': f"/web/image/res.partner/{dp.id}/image_128" if dp.image_128 else None,
                }

            return {'success': True, 'data': tracking_data}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/orders/<int:order_id>/cancel', type='json', auth='jwt', methods=['POST'], csrf=False)
    def cancel_order(self, order_id, **kwargs):
        """Cancel an order if allowed."""
        try:
            current_user = request.env.user
            partner_id = current_user.partner_id.id

            reason = kwargs.get('reason', '')

            order = request.env['sale.order'].sudo().search([
                ('id', '=', order_id),
                ('partner_id', '=', partner_id),
                ('state', 'in', ['draft', 'sent', 'sale'])
            ], limit=1)

            if not order:
                return {'success': False, 'error': 'Order cannot be cancelled'}

            # Cancel the order
            order.with_context(cancel_reason=reason).action_cancel()

            return {
                'success': True,
                'message': 'Order cancelled successfully'
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}
```

**Task 1.3: Order Rating API (2 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/order_controller.py (continued)

    @http.route('/api/v1/orders/<int:order_id>/rate', type='json', auth='jwt', methods=['POST'], csrf=False)
    def rate_order(self, order_id, **kwargs):
        """Submit rating and feedback for a delivered order."""
        try:
            current_user = request.env.user
            partner_id = current_user.partner_id.id

            rating = kwargs.get('rating')  # 1-5 stars
            feedback = kwargs.get('feedback', '')

            if not rating or not (1 <= rating <= 5):
                return {'success': False, 'error': 'Rating must be between 1 and 5'}

            order = request.env['sale.order'].sudo().search([
                ('id', '=', order_id),
                ('partner_id', '=', partner_id),
                ('state', '=', 'done'),
                ('customer_rating', '=', False)
            ], limit=1)

            if not order:
                return {'success': False, 'error': 'Order not found or already rated'}

            order.write({
                'customer_rating': rating,
                'customer_feedback': feedback
            })

            # Also rate the delivery person if assigned
            if order.delivery_person_id and rating >= 4:
                # Logic to update delivery person rating average
                pass

            return {
                'success': True,
                'message': 'Thank you for your feedback!'
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/orders/<int:order_id>/reorder', type='json', auth='jwt', methods=['POST'], csrf=False)
    def reorder(self, order_id, **kwargs):
        """Create a new order based on a previous order."""
        try:
            current_user = request.env.user
            partner_id = current_user.partner_id.id

            original_order = request.env['sale.order'].sudo().search([
                ('id', '=', order_id),
                ('partner_id', '=', partner_id),
                ('state', '=', 'done')
            ], limit=1)

            if not original_order:
                return {'success': False, 'error': 'Order not found'}

            # Check product availability
            unavailable_products = []
            for line in original_order.order_line:
                product = line.product_id
                if not product.active or product.qty_available < line.product_uom_qty:
                    unavailable_products.append(product.name)

            if unavailable_products:
                return {
                    'success': False,
                    'error': 'Some products are unavailable',
                    'unavailable_products': unavailable_products
                }

            # Add items to cart
            cart_service = request.env['mobile.cart.service'].sudo()

            for line in original_order.order_line:
                cart_service.add_to_cart(
                    user_id=current_user.id,
                    product_id=line.product_id.id,
                    quantity=line.product_uom_qty
                )

            return {
                'success': True,
                'message': 'Items added to cart',
                'redirect_to': 'cart'
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}
```

#### Dev 2 - Full-Stack Developer (8 hours)

**Task 2.1: Order Detail Model (2 hours)**

```dart
// lib/features/orders/data/models/order_detail_model.dart

import 'package:freezed_annotation/freezed_annotation.dart';

part 'order_detail_model.freezed.dart';
part 'order_detail_model.g.dart';

@freezed
class OrderDetailModel with _$OrderDetailModel {
  const factory OrderDetailModel({
    required int id,
    required String name,
    required DateTime dateOrder,
    required String state,
    required String stateColor,
    String? trackingState,

    // Amounts
    required double subtotal,
    required double taxAmount,
    required double deliveryFee,
    required double discountAmount,
    required double amountTotal,
    required String currency,

    // Lines
    required List<OrderLineModel> lines,
    required int itemCount,

    // Addresses
    OrderAddressModel? deliveryAddress,

    // Payment
    PaymentInfoModel? paymentInfo,

    // Delivery
    DateTime? commitmentDate,
    DateTime? estimatedDelivery,
    DateTime? actualDelivery,
    String? deliveryNotes,
    DeliveryPersonModel? deliveryPerson,

    // Tracking
    required List<TimelineItemModel> timeline,
    TrackingLocationModel? trackingLocation,

    // Actions
    required bool canCancel,
    required bool canReorder,
    required bool canTrack,
    required bool canRate,

    // Rating
    int? customerRating,
    String? customerFeedback,
  }) = _OrderDetailModel;

  factory OrderDetailModel.fromJson(Map<String, dynamic> json) =>
      _$OrderDetailModelFromJson(json);
}

@freezed
class OrderLineModel with _$OrderLineModel {
  const factory OrderLineModel({
    required int id,
    required int productId,
    required String productName,
    String? productImage,
    required double quantity,
    required String uom,
    required double unitPrice,
    required double discount,
    required double subtotal,
    required double taxAmount,
    required double total,
  }) = _OrderLineModel;

  factory OrderLineModel.fromJson(Map<String, dynamic> json) =>
      _$OrderLineModelFromJson(json);
}

@freezed
class OrderAddressModel with _$OrderAddressModel {
  const factory OrderAddressModel({
    required int id,
    required String name,
    String? street,
    String? street2,
    String? city,
    String? state,
    String? zip,
    String? country,
    String? phone,
    double? latitude,
    double? longitude,
  }) = _OrderAddressModel;

  factory OrderAddressModel.fromJson(Map<String, dynamic> json) =>
      _$OrderAddressModelFromJson(json);
}

@freezed
class TimelineItemModel with _$TimelineItemModel {
  const factory TimelineItemModel({
    required String status,
    required String title,
    required String description,
    DateTime? timestamp,
    required bool completed,
    required String icon,
  }) = _TimelineItemModel;

  factory TimelineItemModel.fromJson(Map<String, dynamic> json) =>
      _$TimelineItemModelFromJson(json);
}

@freezed
class TrackingLocationModel with _$TrackingLocationModel {
  const factory TrackingLocationModel({
    required double latitude,
    required double longitude,
    DateTime? updatedAt,
  }) = _TrackingLocationModel;

  factory TrackingLocationModel.fromJson(Map<String, dynamic> json) =>
      _$TrackingLocationModelFromJson(json);
}

@freezed
class DeliveryPersonModel with _$DeliveryPersonModel {
  const factory DeliveryPersonModel({
    required int id,
    required String name,
    String? phone,
    String? image,
  }) = _DeliveryPersonModel;

  factory DeliveryPersonModel.fromJson(Map<String, dynamic> json) =>
      _$DeliveryPersonModelFromJson(json);
}

@freezed
class PaymentInfoModel with _$PaymentInfoModel {
  const factory PaymentInfoModel({
    required String method,
    required String status,
    String? reference,
    required double amount,
    DateTime? date,
  }) = _PaymentInfoModel;

  factory PaymentInfoModel.fromJson(Map<String, dynamic> json) =>
      _$PaymentInfoModelFromJson(json);
}
```

**Task 2.2: Order Detail Repository (3 hours)**

```dart
// lib/features/orders/data/repositories/order_repository_impl.dart (continued)

  @override
  Future<Either<Failure, OrderDetailModel>> getOrderDetail(int orderId) async {
    try {
      final response = await _apiClient.get('/api/v1/orders/$orderId');

      if (response['success'] == true) {
        return Right(OrderDetailModel.fromJson(response['data']));
      }

      return Left(ServerFailure(response['error'] ?? 'Failed to fetch order'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  @override
  Future<Either<Failure, TrackingData>> getOrderTracking(int orderId) async {
    try {
      final response = await _apiClient.get('/api/v1/orders/$orderId/tracking');

      if (response['success'] == true) {
        return Right(TrackingData.fromJson(response['data']));
      }

      return Left(ServerFailure(response['error'] ?? 'Failed to fetch tracking'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  @override
  Future<Either<Failure, void>> cancelOrder(int orderId, String reason) async {
    try {
      final response = await _apiClient.post(
        '/api/v1/orders/$orderId/cancel',
        data: {'reason': reason},
      );

      if (response['success'] == true) {
        return const Right(null);
      }

      return Left(ServerFailure(response['error'] ?? 'Failed to cancel order'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  @override
  Future<Either<Failure, void>> rateOrder(
    int orderId,
    int rating,
    String? feedback,
  ) async {
    try {
      final response = await _apiClient.post(
        '/api/v1/orders/$orderId/rate',
        data: {
          'rating': rating,
          'feedback': feedback ?? '',
        },
      );

      if (response['success'] == true) {
        return const Right(null);
      }

      return Left(ServerFailure(response['error'] ?? 'Failed to submit rating'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  @override
  Future<Either<Failure, String>> reorder(int orderId) async {
    try {
      final response = await _apiClient.post('/api/v1/orders/$orderId/reorder');

      if (response['success'] == true) {
        return Right(response['redirect_to'] ?? 'cart');
      }

      return Left(ServerFailure(response['error'] ?? 'Failed to reorder'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }
```

**Task 2.3: Order Detail Provider (3 hours)**

```dart
// lib/features/orders/presentation/providers/order_detail_provider.dart

import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:smart_dairy_customer/features/orders/data/models/order_detail_model.dart';
import 'package:smart_dairy_customer/features/orders/domain/repositories/order_repository.dart';
import 'package:smart_dairy_customer/features/orders/presentation/providers/orders_provider.dart';

// Order detail state
class OrderDetailState {
  final OrderDetailModel? order;
  final bool isLoading;
  final String? error;
  final bool isCancelling;
  final bool isRating;

  const OrderDetailState({
    this.order,
    this.isLoading = false,
    this.error,
    this.isCancelling = false,
    this.isRating = false,
  });

  OrderDetailState copyWith({
    OrderDetailModel? order,
    bool? isLoading,
    String? error,
    bool? isCancelling,
    bool? isRating,
  }) {
    return OrderDetailState(
      order: order ?? this.order,
      isLoading: isLoading ?? this.isLoading,
      error: error,
      isCancelling: isCancelling ?? this.isCancelling,
      isRating: isRating ?? this.isRating,
    );
  }
}

// Order detail notifier
class OrderDetailNotifier extends StateNotifier<OrderDetailState> {
  final OrderRepository _repository;
  final int orderId;

  OrderDetailNotifier(this._repository, this.orderId) : super(const OrderDetailState()) {
    loadOrder();
  }

  Future<void> loadOrder() async {
    state = state.copyWith(isLoading: true, error: null);

    final result = await _repository.getOrderDetail(orderId);

    result.fold(
      (failure) => state = state.copyWith(
        isLoading: false,
        error: failure.message,
      ),
      (order) => state = state.copyWith(
        isLoading: false,
        order: order,
      ),
    );
  }

  Future<bool> cancelOrder(String reason) async {
    if (state.order == null || !state.order!.canCancel) return false;

    state = state.copyWith(isCancelling: true);

    final result = await _repository.cancelOrder(orderId, reason);

    return result.fold(
      (failure) {
        state = state.copyWith(isCancelling: false);
        return false;
      },
      (_) {
        loadOrder(); // Refresh order
        return true;
      },
    );
  }

  Future<bool> submitRating(int rating, String? feedback) async {
    if (state.order == null || !state.order!.canRate) return false;

    state = state.copyWith(isRating: true);

    final result = await _repository.rateOrder(orderId, rating, feedback);

    return result.fold(
      (failure) {
        state = state.copyWith(isRating: false);
        return false;
      },
      (_) {
        loadOrder(); // Refresh order
        return true;
      },
    );
  }
}

// Provider factory
final orderDetailProvider = StateNotifierProvider.family<
    OrderDetailNotifier, OrderDetailState, int>((ref, orderId) {
  return OrderDetailNotifier(
    ref.watch(orderRepositoryProvider),
    orderId,
  );
});

// Tracking provider with auto-refresh
final orderTrackingProvider = StreamProvider.family<TrackingData, int>((ref, orderId) async* {
  final repository = ref.watch(orderRepositoryProvider);

  while (true) {
    final result = await repository.getOrderTracking(orderId);

    yield* result.fold(
      (failure) => Stream.error(failure),
      (tracking) => Stream.value(tracking),
    );

    // Refresh every 30 seconds
    await Future.delayed(const Duration(seconds: 30));
  }
});
```

#### Dev 3 - Mobile Lead (8 hours)

**Task 3.1: Order Detail Screen (4 hours)**

```dart
// lib/features/orders/presentation/screens/order_detail_screen.dart

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import 'package:smart_dairy_customer/core/theme/app_colors.dart';
import 'package:smart_dairy_customer/features/orders/presentation/providers/order_detail_provider.dart';
import 'package:smart_dairy_customer/features/orders/presentation/widgets/order_timeline.dart';
import 'package:smart_dairy_customer/features/orders/presentation/widgets/order_item_card.dart';
import 'package:smart_dairy_customer/features/orders/presentation/widgets/rating_dialog.dart';

class OrderDetailScreen extends ConsumerWidget {
  final int orderId;

  const OrderDetailScreen({super.key, required this.orderId});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final state = ref.watch(orderDetailProvider(orderId));

    if (state.isLoading && state.order == null) {
      return Scaffold(
        appBar: AppBar(title: const Text('Order Details')),
        body: const Center(child: CircularProgressIndicator()),
      );
    }

    if (state.error != null && state.order == null) {
      return Scaffold(
        appBar: AppBar(title: const Text('Order Details')),
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Icon(Icons.error_outline, size: 64, color: Colors.grey[400]),
              const SizedBox(height: 16),
              Text(state.error!),
              const SizedBox(height: 16),
              ElevatedButton(
                onPressed: () => ref.read(orderDetailProvider(orderId).notifier).loadOrder(),
                child: const Text('Retry'),
              ),
            ],
          ),
        ),
      );
    }

    final order = state.order!;

    return Scaffold(
      appBar: AppBar(
        title: Text(order.name),
        actions: [
          if (order.canTrack)
            IconButton(
              icon: const Icon(Icons.map),
              onPressed: () => _navigateToTracking(context),
            ),
          PopupMenuButton<String>(
            onSelected: (value) => _handleMenuAction(context, ref, value),
            itemBuilder: (_) => [
              if (order.canReorder)
                const PopupMenuItem(
                  value: 'reorder',
                  child: ListTile(
                    leading: Icon(Icons.replay),
                    title: Text('Reorder'),
                    contentPadding: EdgeInsets.zero,
                  ),
                ),
              if (order.canCancel)
                const PopupMenuItem(
                  value: 'cancel',
                  child: ListTile(
                    leading: Icon(Icons.cancel, color: Colors.red),
                    title: Text('Cancel Order', style: TextStyle(color: Colors.red)),
                    contentPadding: EdgeInsets.zero,
                  ),
                ),
              const PopupMenuItem(
                value: 'help',
                child: ListTile(
                  leading: Icon(Icons.help_outline),
                  title: Text('Get Help'),
                  contentPadding: EdgeInsets.zero,
                ),
              ),
            ],
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () => ref.read(orderDetailProvider(orderId).notifier).loadOrder(),
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Status card
              _buildStatusCard(order),

              const SizedBox(height: 20),

              // Timeline
              const Text(
                'Order Timeline',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 12),
              OrderTimeline(timeline: order.timeline),

              const SizedBox(height: 20),

              // Items
              const Text(
                'Order Items',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 12),
              ...order.lines.map((line) => OrderItemCard(item: line)),

              const SizedBox(height: 20),

              // Price breakdown
              _buildPriceBreakdown(order),

              const SizedBox(height: 20),

              // Delivery address
              if (order.deliveryAddress != null) ...[
                _buildDeliveryAddress(order.deliveryAddress!),
                const SizedBox(height: 20),
              ],

              // Payment info
              if (order.paymentInfo != null) ...[
                _buildPaymentInfo(order.paymentInfo!),
                const SizedBox(height: 20),
              ],

              // Rating section
              if (order.canRate)
                _buildRatingPrompt(context, ref)
              else if (order.customerRating != null)
                _buildRatingDisplay(order),

              const SizedBox(height: 40),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildStatusCard(OrderDetailModel order) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: _getStatusColor(order.stateColor).withOpacity(0.1),
                borderRadius: BorderRadius.circular(12),
              ),
              child: Icon(
                _getStatusIcon(order.state),
                color: _getStatusColor(order.stateColor),
                size: 32,
              ),
            ),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    order.state,
                    style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: _getStatusColor(order.stateColor),
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    'Placed on ${DateFormat('MMM dd, yyyy').format(order.dateOrder)}',
                    style: TextStyle(color: Colors.grey[600]),
                  ),
                  if (order.estimatedDelivery != null) ...[
                    const SizedBox(height: 4),
                    Text(
                      'Expected by ${DateFormat('MMM dd').format(order.estimatedDelivery!)}',
                      style: const TextStyle(fontWeight: FontWeight.w500),
                    ),
                  ],
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildPriceBreakdown(OrderDetailModel order) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Price Details',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 12),
            _priceRow('Subtotal', order.subtotal, order.currency),
            if (order.discountAmount > 0)
              _priceRow('Discount', -order.discountAmount, order.currency, isDiscount: true),
            _priceRow('Tax', order.taxAmount, order.currency),
            _priceRow('Delivery Fee', order.deliveryFee, order.currency),
            const Divider(height: 24),
            _priceRow('Total', order.amountTotal, order.currency, isTotal: true),
          ],
        ),
      ),
    );
  }

  Widget _priceRow(String label, double amount, String currency,
      {bool isTotal = false, bool isDiscount = false}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            label,
            style: TextStyle(
              fontWeight: isTotal ? FontWeight.bold : FontWeight.normal,
              fontSize: isTotal ? 16 : 14,
            ),
          ),
          Text(
            '${isDiscount ? '-' : ''}$currency${amount.abs().toStringAsFixed(2)}',
            style: TextStyle(
              fontWeight: isTotal ? FontWeight.bold : FontWeight.normal,
              fontSize: isTotal ? 16 : 14,
              color: isDiscount ? Colors.green : null,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildDeliveryAddress(OrderAddressModel address) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                const Icon(Icons.location_on, color: AppColors.primary),
                const SizedBox(width: 8),
                const Text(
                  'Delivery Address',
                  style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Text(address.name, style: const TextStyle(fontWeight: FontWeight.w500)),
            if (address.street != null) Text(address.street!),
            if (address.street2 != null) Text(address.street2!),
            Text('${address.city ?? ''} ${address.zip ?? ''}'.trim()),
            if (address.phone != null) ...[
              const SizedBox(height: 8),
              Text('Phone: ${address.phone}'),
            ],
          ],
        ),
      ),
    );
  }

  Widget _buildPaymentInfo(PaymentInfoModel payment) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                const Icon(Icons.payment, color: AppColors.primary),
                const SizedBox(width: 8),
                const Text(
                  'Payment',
                  style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(payment.method),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                  decoration: BoxDecoration(
                    color: payment.status == 'done' ? Colors.green[50] : Colors.orange[50],
                    borderRadius: BorderRadius.circular(4),
                  ),
                  child: Text(
                    payment.status == 'done' ? 'Paid' : 'Pending',
                    style: TextStyle(
                      color: payment.status == 'done' ? Colors.green : Colors.orange,
                      fontSize: 12,
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildRatingPrompt(BuildContext context, WidgetRef ref) {
    return Card(
      color: AppColors.primary.withOpacity(0.05),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            const Icon(Icons.star_border, size: 48, color: AppColors.primary),
            const SizedBox(height: 12),
            const Text(
              'How was your order?',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            const Text(
              'Help us improve by rating your experience',
              style: TextStyle(color: Colors.grey),
            ),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: () => _showRatingDialog(context, ref),
              child: const Text('Rate Order'),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildRatingDisplay(OrderDetailModel order) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Your Rating',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 12),
            Row(
              children: List.generate(5, (index) {
                return Icon(
                  index < (order.customerRating ?? 0)
                      ? Icons.star
                      : Icons.star_border,
                  color: Colors.amber,
                  size: 28,
                );
              }),
            ),
            if (order.customerFeedback != null && order.customerFeedback!.isNotEmpty) ...[
              const SizedBox(height: 8),
              Text(
                '"${order.customerFeedback}"',
                style: TextStyle(
                  fontStyle: FontStyle.italic,
                  color: Colors.grey[600],
                ),
              ),
            ],
          ],
        ),
      ),
    );
  }

  void _showRatingDialog(BuildContext context, WidgetRef ref) {
    showDialog(
      context: context,
      builder: (_) => RatingDialog(
        onSubmit: (rating, feedback) async {
          final success = await ref
              .read(orderDetailProvider(orderId).notifier)
              .submitRating(rating, feedback);
          if (success && context.mounted) {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text('Thank you for your feedback!')),
            );
          }
        },
      ),
    );
  }

  void _navigateToTracking(BuildContext context) {
    Navigator.pushNamed(context, '/order-tracking', arguments: orderId);
  }

  void _handleMenuAction(BuildContext context, WidgetRef ref, String action) {
    switch (action) {
      case 'reorder':
        _handleReorder(context, ref);
        break;
      case 'cancel':
        _showCancelDialog(context, ref);
        break;
      case 'help':
        Navigator.pushNamed(context, '/help', arguments: {'order_id': orderId});
        break;
    }
  }

  void _handleReorder(BuildContext context, WidgetRef ref) async {
    final repository = ref.read(orderRepositoryProvider);
    final result = await repository.reorder(orderId);

    result.fold(
      (failure) {
        if (context.mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(failure.message)),
          );
        }
      },
      (redirectTo) {
        if (context.mounted) {
          Navigator.pushNamed(context, '/$redirectTo');
        }
      },
    );
  }

  void _showCancelDialog(BuildContext context, WidgetRef ref) {
    final reasonController = TextEditingController();

    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text('Cancel Order'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Text('Are you sure you want to cancel this order?'),
            const SizedBox(height: 16),
            TextField(
              controller: reasonController,
              decoration: const InputDecoration(
                labelText: 'Reason (optional)',
                border: OutlineInputBorder(),
              ),
              maxLines: 2,
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('No, Keep Order'),
          ),
          TextButton(
            onPressed: () async {
              Navigator.pop(context);
              final success = await ref
                  .read(orderDetailProvider(orderId).notifier)
                  .cancelOrder(reasonController.text);
              if (success && context.mounted) {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Order cancelled successfully')),
                );
              }
            },
            style: TextButton.styleFrom(foregroundColor: Colors.red),
            child: const Text('Yes, Cancel'),
          ),
        ],
      ),
    );
  }

  Color _getStatusColor(String colorName) {
    switch (colorName) {
      case 'orange': return Colors.orange;
      case 'blue': return Colors.blue;
      case 'purple': return Colors.purple;
      case 'teal': return Colors.teal;
      case 'green': return Colors.green;
      case 'red': return Colors.red;
      default: return Colors.grey;
    }
  }

  IconData _getStatusIcon(String state) {
    switch (state.toLowerCase()) {
      case 'pending': return Icons.schedule;
      case 'confirmed': return Icons.check_circle;
      case 'processing': return Icons.inventory;
      case 'shipped': return Icons.local_shipping;
      case 'delivered': return Icons.done_all;
      case 'cancelled': return Icons.cancel;
      default: return Icons.help_outline;
    }
  }
}
```

**Task 3.2: Order Timeline Widget (2 hours)**

```dart
// lib/features/orders/presentation/widgets/order_timeline.dart

import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:smart_dairy_customer/core/theme/app_colors.dart';
import 'package:smart_dairy_customer/features/orders/data/models/order_detail_model.dart';

class OrderTimeline extends StatelessWidget {
  final List<TimelineItemModel> timeline;

  const OrderTimeline({super.key, required this.timeline});

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: List.generate(timeline.length, (index) {
            final item = timeline[index];
            final isLast = index == timeline.length - 1;

            return IntrinsicHeight(
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Timeline indicator
                  Column(
                    children: [
                      Container(
                        width: 32,
                        height: 32,
                        decoration: BoxDecoration(
                          color: item.completed
                              ? AppColors.primary
                              : Colors.grey[300],
                          shape: BoxShape.circle,
                        ),
                        child: Icon(
                          _getIcon(item.icon),
                          color: item.completed ? Colors.white : Colors.grey[500],
                          size: 18,
                        ),
                      ),
                      if (!isLast)
                        Expanded(
                          child: Container(
                            width: 2,
                            color: item.completed
                                ? AppColors.primary
                                : Colors.grey[300],
                          ),
                        ),
                    ],
                  ),

                  const SizedBox(width: 16),

                  // Content
                  Expanded(
                    child: Padding(
                      padding: EdgeInsets.only(bottom: isLast ? 0 : 24),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            item.title,
                            style: TextStyle(
                              fontWeight: FontWeight.bold,
                              color: item.completed ? Colors.black : Colors.grey,
                            ),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            item.description,
                            style: TextStyle(
                              color: Colors.grey[600],
                              fontSize: 13,
                            ),
                          ),
                          if (item.timestamp != null) ...[
                            const SizedBox(height: 4),
                            Text(
                              DateFormat('MMM dd, hh:mm a').format(item.timestamp!),
                              style: TextStyle(
                                color: Colors.grey[500],
                                fontSize: 12,
                              ),
                            ),
                          ],
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            );
          }),
        ),
      ),
    );
  }

  IconData _getIcon(String iconName) {
    switch (iconName) {
      case 'shopping_cart': return Icons.shopping_cart;
      case 'payment': return Icons.payment;
      case 'check_circle': return Icons.check_circle;
      case 'inventory': return Icons.inventory;
      case 'local_shipping': return Icons.local_shipping;
      case 'done_all': return Icons.done_all;
      default: return Icons.circle;
    }
  }
}
```

**Task 3.3: Order Tracking Map Screen (2 hours)**

```dart
// lib/features/orders/presentation/screens/order_tracking_screen.dart

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:smart_dairy_customer/core/theme/app_colors.dart';
import 'package:smart_dairy_customer/features/orders/presentation/providers/order_detail_provider.dart';
import 'package:url_launcher/url_launcher.dart';

class OrderTrackingScreen extends ConsumerStatefulWidget {
  final int orderId;

  const OrderTrackingScreen({super.key, required this.orderId});

  @override
  ConsumerState<OrderTrackingScreen> createState() => _OrderTrackingScreenState();
}

class _OrderTrackingScreenState extends ConsumerState<OrderTrackingScreen> {
  GoogleMapController? _mapController;

  @override
  Widget build(BuildContext context) {
    final trackingAsync = ref.watch(orderTrackingProvider(widget.orderId));

    return Scaffold(
      appBar: AppBar(
        title: const Text('Track Order'),
      ),
      body: trackingAsync.when(
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (error, _) => Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Icon(Icons.error_outline, size: 64, color: Colors.grey[400]),
              const SizedBox(height: 16),
              Text('Unable to load tracking: $error'),
            ],
          ),
        ),
        data: (tracking) => Stack(
          children: [
            // Map
            GoogleMap(
              initialCameraPosition: CameraPosition(
                target: tracking.currentLocation != null
                    ? LatLng(
                        tracking.currentLocation!.latitude,
                        tracking.currentLocation!.longitude,
                      )
                    : const LatLng(23.8103, 90.4125), // Dhaka default
                zoom: 15,
              ),
              onMapCreated: (controller) {
                _mapController = controller;
                _fitBounds(tracking);
              },
              markers: _buildMarkers(tracking),
              polylines: _buildPolylines(tracking),
              myLocationEnabled: false,
              zoomControlsEnabled: false,
            ),

            // Bottom card
            Positioned(
              left: 16,
              right: 16,
              bottom: 16,
              child: _buildTrackingCard(tracking),
            ),
          ],
        ),
      ),
    );
  }

  Set<Marker> _buildMarkers(TrackingData tracking) {
    final markers = <Marker>{};

    // Current location (delivery person)
    if (tracking.currentLocation != null) {
      markers.add(Marker(
        markerId: const MarkerId('delivery'),
        position: LatLng(
          tracking.currentLocation!.latitude,
          tracking.currentLocation!.longitude,
        ),
        icon: BitmapDescriptor.defaultMarkerWithHue(BitmapDescriptor.hueBlue),
        infoWindow: const InfoWindow(title: 'Delivery Person'),
      ));
    }

    // Destination
    if (tracking.destination != null) {
      markers.add(Marker(
        markerId: const MarkerId('destination'),
        position: LatLng(
          tracking.destination!.latitude,
          tracking.destination!.longitude,
        ),
        icon: BitmapDescriptor.defaultMarkerWithHue(BitmapDescriptor.hueGreen),
        infoWindow: InfoWindow(title: 'Delivery Address', snippet: tracking.destination!.address),
      ));
    }

    return markers;
  }

  Set<Polyline> _buildPolylines(TrackingData tracking) {
    if (tracking.currentLocation == null || tracking.destination == null) {
      return {};
    }

    return {
      Polyline(
        polylineId: const PolylineId('route'),
        points: [
          LatLng(tracking.currentLocation!.latitude, tracking.currentLocation!.longitude),
          LatLng(tracking.destination!.latitude, tracking.destination!.longitude),
        ],
        color: AppColors.primary,
        width: 4,
        patterns: [PatternItem.dash(20), PatternItem.gap(10)],
      ),
    };
  }

  void _fitBounds(TrackingData tracking) {
    if (tracking.currentLocation != null && tracking.destination != null) {
      final bounds = LatLngBounds(
        southwest: LatLng(
          tracking.currentLocation!.latitude < tracking.destination!.latitude
              ? tracking.currentLocation!.latitude
              : tracking.destination!.latitude,
          tracking.currentLocation!.longitude < tracking.destination!.longitude
              ? tracking.currentLocation!.longitude
              : tracking.destination!.longitude,
        ),
        northeast: LatLng(
          tracking.currentLocation!.latitude > tracking.destination!.latitude
              ? tracking.currentLocation!.latitude
              : tracking.destination!.latitude,
          tracking.currentLocation!.longitude > tracking.destination!.longitude
              ? tracking.currentLocation!.longitude
              : tracking.destination!.longitude,
        ),
      );

      _mapController?.animateCamera(
        CameraUpdate.newLatLngBounds(bounds, 80),
      );
    }
  }

  Widget _buildTrackingCard(TrackingData tracking) {
    return Card(
      elevation: 8,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            // Status
            Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: AppColors.primary.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: const Icon(Icons.local_shipping, color: AppColors.primary),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        tracking.state,
                        style: const TextStyle(
                          fontWeight: FontWeight.bold,
                          fontSize: 16,
                        ),
                      ),
                      if (tracking.estimatedDelivery != null)
                        Text(
                          'Arriving by ${_formatTime(tracking.estimatedDelivery!)}',
                          style: TextStyle(color: Colors.grey[600]),
                        ),
                    ],
                  ),
                ),
                if (tracking.currentLocation?.updatedAt != null)
                  Text(
                    'Updated ${_getTimeAgo(tracking.currentLocation!.updatedAt!)}',
                    style: TextStyle(color: Colors.grey[500], fontSize: 12),
                  ),
              ],
            ),

            // Delivery person
            if (tracking.deliveryPerson != null) ...[
              const Divider(height: 24),
              Row(
                children: [
                  CircleAvatar(
                    radius: 24,
                    backgroundImage: tracking.deliveryPerson!.image != null
                        ? NetworkImage(tracking.deliveryPerson!.image!)
                        : null,
                    child: tracking.deliveryPerson!.image == null
                        ? const Icon(Icons.person)
                        : null,
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          tracking.deliveryPerson!.name,
                          style: const TextStyle(fontWeight: FontWeight.w500),
                        ),
                        const Text(
                          'Delivery Partner',
                          style: TextStyle(color: Colors.grey, fontSize: 13),
                        ),
                      ],
                    ),
                  ),
                  if (tracking.deliveryPerson!.phone != null)
                    IconButton(
                      icon: const Icon(Icons.phone, color: AppColors.primary),
                      onPressed: () => _callDeliveryPerson(tracking.deliveryPerson!.phone!),
                    ),
                ],
              ),
            ],
          ],
        ),
      ),
    );
  }

  String _formatTime(DateTime time) {
    final hour = time.hour > 12 ? time.hour - 12 : time.hour;
    final amPm = time.hour >= 12 ? 'PM' : 'AM';
    return '$hour:${time.minute.toString().padLeft(2, '0')} $amPm';
  }

  String _getTimeAgo(DateTime time) {
    final diff = DateTime.now().difference(time);
    if (diff.inMinutes < 1) return 'just now';
    if (diff.inMinutes < 60) return '${diff.inMinutes}m ago';
    return '${diff.inHours}h ago';
  }

  void _callDeliveryPerson(String phone) {
    launchUrl(Uri.parse('tel:$phone'));
  }
}
```

#### End-of-Day 292 Deliverables

- [ ] Order detail API with complete information
- [ ] Order tracking API with real-time location
- [ ] Order cancellation API
- [ ] Order rating API
- [ ] Reorder functionality API
- [ ] Order detail models with Freezed
- [ ] Order detail repository methods
- [ ] Order detail provider with state management
- [ ] Order detail screen with timeline
- [ ] Order timeline widget
- [ ] Order tracking map screen
- [ ] Rating dialog widget

---

### Day 293 (Sprint Day 3): Push Notification Backend

#### Objectives
- Implement FCM token registration API
- Build notification service in Odoo
- Create notification templates system

#### Dev 1 - Backend Lead (8 hours)

**Task 1.1: FCM Token Management (3 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/models/mobile_fcm_token.py

from odoo import models, fields, api
from datetime import datetime, timedelta

class MobileFCMToken(models.Model):
    _name = 'mobile.fcm.token'
    _description = 'Mobile FCM Token'
    _order = 'write_date desc'

    user_id = fields.Many2one('res.users', 'User', required=True, ondelete='cascade')
    token = fields.Char('FCM Token', required=True, index=True)
    device_id = fields.Char('Device ID')
    device_type = fields.Selection([
        ('android', 'Android'),
        ('ios', 'iOS'),
    ], 'Device Type', required=True)
    device_name = fields.Char('Device Name')
    app_version = fields.Char('App Version')
    is_active = fields.Boolean('Active', default=True)
    last_used = fields.Datetime('Last Used')
    created_date = fields.Datetime('Created', default=fields.Datetime.now)

    _sql_constraints = [
        ('token_unique', 'unique(token)', 'FCM token must be unique'),
    ]

    @api.model
    def register_token(self, user_id, token, device_id, device_type, device_name=None, app_version=None):
        """Register or update FCM token for a user."""
        # Deactivate old tokens for same device
        existing = self.search([
            ('user_id', '=', user_id),
            ('device_id', '=', device_id),
            ('token', '!=', token)
        ])
        existing.write({'is_active': False})

        # Check if token already exists
        token_record = self.search([('token', '=', token)], limit=1)

        if token_record:
            # Update existing token
            token_record.write({
                'user_id': user_id,
                'device_id': device_id,
                'device_type': device_type,
                'device_name': device_name,
                'app_version': app_version,
                'is_active': True,
                'last_used': fields.Datetime.now()
            })
            return token_record
        else:
            # Create new token
            return self.create({
                'user_id': user_id,
                'token': token,
                'device_id': device_id,
                'device_type': device_type,
                'device_name': device_name,
                'app_version': app_version,
            })

    @api.model
    def get_user_tokens(self, user_id):
        """Get all active tokens for a user."""
        return self.search([
            ('user_id', '=', user_id),
            ('is_active', '=', True)
        ])

    @api.model
    def cleanup_inactive_tokens(self, days=30):
        """Remove tokens not used in specified days."""
        cutoff = datetime.now() - timedelta(days=days)
        old_tokens = self.search([
            '|',
            ('last_used', '<', cutoff),
            '&', ('last_used', '=', False), ('create_date', '<', cutoff)
        ])
        old_tokens.unlink()
        return len(old_tokens)
```

**Task 1.2: Notification Service (3 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/models/notification_service.py

from odoo import models, fields, api
import firebase_admin
from firebase_admin import credentials, messaging
import json
import logging

_logger = logging.getLogger(__name__)

class MobileNotificationService(models.AbstractModel):
    _name = 'mobile.notification.service'
    _description = 'Mobile Notification Service'

    @api.model
    def _get_firebase_app(self):
        """Initialize Firebase Admin SDK."""
        if not firebase_admin._apps:
            config = self.env['ir.config_parameter'].sudo()
            service_account = config.get_param('mobile.firebase_service_account')

            if service_account:
                cred = credentials.Certificate(json.loads(service_account))
                firebase_admin.initialize_app(cred)
            else:
                _logger.warning('Firebase service account not configured')
                return None

        return firebase_admin.get_app()

    def send_to_user(self, user_id, title, body, data=None, image=None):
        """Send notification to all devices of a user."""
        app = self._get_firebase_app()
        if not app:
            return False

        FCMToken = self.env['mobile.fcm.token'].sudo()
        tokens = FCMToken.get_user_tokens(user_id)

        if not tokens:
            _logger.info(f'No FCM tokens found for user {user_id}')
            return False

        results = []
        for token_record in tokens:
            try:
                result = self._send_notification(
                    token=token_record.token,
                    title=title,
                    body=body,
                    data=data,
                    image=image,
                    device_type=token_record.device_type
                )
                results.append(result)

                # Update last used
                token_record.write({'last_used': fields.Datetime.now()})

            except messaging.UnregisteredError:
                # Token is invalid, deactivate it
                token_record.write({'is_active': False})
            except Exception as e:
                _logger.error(f'FCM send error: {e}')

        # Log notification
        self._log_notification(user_id, title, body, data, results)

        return any(results)

    def send_to_token(self, token, title, body, data=None, image=None):
        """Send notification to a specific token."""
        app = self._get_firebase_app()
        if not app:
            return False

        return self._send_notification(token, title, body, data, image)

    def send_to_topic(self, topic, title, body, data=None, image=None):
        """Send notification to a topic."""
        app = self._get_firebase_app()
        if not app:
            return False

        message = messaging.Message(
            notification=messaging.Notification(
                title=title,
                body=body,
                image=image
            ),
            data=self._prepare_data(data),
            topic=topic,
        )

        try:
            response = messaging.send(message)
            _logger.info(f'Topic notification sent: {response}')
            return True
        except Exception as e:
            _logger.error(f'Topic notification error: {e}')
            return False

    def _send_notification(self, token, title, body, data=None, image=None, device_type='android'):
        """Send notification to a single token."""
        # Android specific config
        android_config = messaging.AndroidConfig(
            priority='high',
            notification=messaging.AndroidNotification(
                icon='notification_icon',
                color='#4CAF50',
                sound='default',
                channel_id='orders'
            )
        )

        # iOS specific config
        apns_config = messaging.APNSConfig(
            payload=messaging.APNSPayload(
                aps=messaging.Aps(
                    sound='default',
                    badge=1,
                    content_available=True
                )
            )
        )

        message = messaging.Message(
            notification=messaging.Notification(
                title=title,
                body=body,
                image=image
            ),
            data=self._prepare_data(data),
            token=token,
            android=android_config if device_type == 'android' else None,
            apns=apns_config if device_type == 'ios' else None,
        )

        response = messaging.send(message)
        _logger.info(f'Notification sent: {response}')
        return True

    def _prepare_data(self, data):
        """Convert data values to strings for FCM."""
        if not data:
            return {}

        return {k: str(v) for k, v in data.items()}

    def _log_notification(self, user_id, title, body, data, results):
        """Log sent notification."""
        self.env['mobile.notification.log'].sudo().create({
            'user_id': user_id,
            'title': title,
            'body': body,
            'data': json.dumps(data) if data else None,
            'success': any(results),
            'sent_count': len([r for r in results if r]),
            'failed_count': len([r for r in results if not r])
        })
```

**Task 1.3: Notification API Controller (2 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/notification_controller.py

from odoo import http
from odoo.http import request

class NotificationController(http.Controller):

    @http.route('/api/v1/notifications/register', type='json', auth='jwt', methods=['POST'], csrf=False)
    def register_token(self, **kwargs):
        """Register FCM token for push notifications."""
        try:
            current_user = request.env.user

            token = kwargs.get('token')
            device_id = kwargs.get('device_id')
            device_type = kwargs.get('device_type')  # 'android' or 'ios'
            device_name = kwargs.get('device_name')
            app_version = kwargs.get('app_version')

            if not token or not device_id or not device_type:
                return {'success': False, 'error': 'Missing required fields'}

            FCMToken = request.env['mobile.fcm.token'].sudo()
            token_record = FCMToken.register_token(
                user_id=current_user.id,
                token=token,
                device_id=device_id,
                device_type=device_type,
                device_name=device_name,
                app_version=app_version
            )

            return {
                'success': True,
                'data': {
                    'token_id': token_record.id,
                    'registered': True
                }
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/notifications/unregister', type='json', auth='jwt', methods=['POST'], csrf=False)
    def unregister_token(self, **kwargs):
        """Unregister FCM token."""
        try:
            token = kwargs.get('token')

            if not token:
                return {'success': False, 'error': 'Token required'}

            FCMToken = request.env['mobile.fcm.token'].sudo()
            token_record = FCMToken.search([('token', '=', token)], limit=1)

            if token_record:
                token_record.write({'is_active': False})

            return {'success': True, 'message': 'Token unregistered'}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/notifications/preferences', type='json', auth='jwt', methods=['GET'], csrf=False)
    def get_preferences(self, **kwargs):
        """Get notification preferences."""
        try:
            current_user = request.env.user
            partner = current_user.partner_id

            preferences = {
                'order_updates': partner.notify_order_updates if hasattr(partner, 'notify_order_updates') else True,
                'promotions': partner.notify_promotions if hasattr(partner, 'notify_promotions') else True,
                'delivery_updates': partner.notify_delivery if hasattr(partner, 'notify_delivery') else True,
                'loyalty_points': partner.notify_loyalty if hasattr(partner, 'notify_loyalty') else True,
                'new_products': partner.notify_new_products if hasattr(partner, 'notify_new_products') else False,
            }

            return {'success': True, 'data': preferences}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/notifications/preferences', type='json', auth='jwt', methods=['PUT'], csrf=False)
    def update_preferences(self, **kwargs):
        """Update notification preferences."""
        try:
            current_user = request.env.user
            partner = current_user.partner_id

            updates = {}
            pref_mapping = {
                'order_updates': 'notify_order_updates',
                'promotions': 'notify_promotions',
                'delivery_updates': 'notify_delivery',
                'loyalty_points': 'notify_loyalty',
                'new_products': 'notify_new_products',
            }

            for key, field in pref_mapping.items():
                if key in kwargs:
                    updates[field] = kwargs[key]

            if updates:
                partner.sudo().write(updates)

            return {'success': True, 'message': 'Preferences updated'}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/notifications/history', type='json', auth='jwt', methods=['GET'], csrf=False)
    def get_history(self, **kwargs):
        """Get notification history."""
        try:
            current_user = request.env.user
            page = int(kwargs.get('page', 1))
            limit = min(int(kwargs.get('limit', 20)), 50)
            offset = (page - 1) * limit

            NotificationLog = request.env['mobile.notification.log'].sudo()

            domain = [('user_id', '=', current_user.id)]
            total = NotificationLog.search_count(domain)
            notifications = NotificationLog.search(
                domain,
                offset=offset,
                limit=limit,
                order='create_date desc'
            )

            data = []
            for notif in notifications:
                data.append({
                    'id': notif.id,
                    'title': notif.title,
                    'body': notif.body,
                    'data': notif.data,
                    'read': notif.is_read,
                    'created_at': notif.create_date.isoformat()
                })

            return {
                'success': True,
                'data': {
                    'notifications': data,
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

    @http.route('/api/v1/notifications/<int:notification_id>/read', type='json', auth='jwt', methods=['POST'], csrf=False)
    def mark_as_read(self, notification_id, **kwargs):
        """Mark notification as read."""
        try:
            current_user = request.env.user

            notification = request.env['mobile.notification.log'].sudo().search([
                ('id', '=', notification_id),
                ('user_id', '=', current_user.id)
            ], limit=1)

            if notification:
                notification.write({'is_read': True})
                return {'success': True}

            return {'success': False, 'error': 'Notification not found'}

        except Exception as e:
            return {'success': False, 'error': str(e)}
```

#### Dev 2 - Full-Stack Developer (8 hours)

**Task 2.1: Firebase Messaging Flutter Setup (3 hours)**

```dart
// lib/core/services/notification_service.dart

import 'dart:convert';
import 'dart:io';
import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:smart_dairy_customer/core/network/api_client.dart';
import 'package:smart_dairy_customer/core/storage/secure_storage.dart';
import 'package:device_info_plus/device_info_plus.dart';
import 'package:package_info_plus/package_info_plus.dart';

// Background message handler (must be top-level)
@pragma('vm:entry-point')
Future<void> _firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  await Firebase.initializeApp();
  print('Handling background message: ${message.messageId}');
}

class NotificationService {
  static final NotificationService _instance = NotificationService._internal();
  factory NotificationService() => _instance;
  NotificationService._internal();

  final FirebaseMessaging _fcm = FirebaseMessaging.instance;
  final FlutterLocalNotificationsPlugin _localNotifications =
      FlutterLocalNotificationsPlugin();

  final ApiClient _apiClient;
  final SecureStorage _storage;

  NotificationService.withDeps(this._apiClient, this._storage);

  // Notification channels
  static const AndroidNotificationChannel _ordersChannel =
      AndroidNotificationChannel(
    'orders',
    'Order Updates',
    description: 'Notifications about your order status',
    importance: Importance.high,
  );

  static const AndroidNotificationChannel _promotionsChannel =
      AndroidNotificationChannel(
    'promotions',
    'Promotions',
    description: 'Special offers and promotions',
    importance: Importance.defaultImportance,
  );

  Future<void> initialize() async {
    // Request permission
    final settings = await _fcm.requestPermission(
      alert: true,
      badge: true,
      sound: true,
      provisional: false,
    );

    if (settings.authorizationStatus == AuthorizationStatus.authorized) {
      print('User granted notification permission');

      // Initialize local notifications
      await _initializeLocalNotifications();

      // Set up message handlers
      FirebaseMessaging.onBackgroundMessage(_firebaseMessagingBackgroundHandler);
      FirebaseMessaging.onMessage.listen(_handleForegroundMessage);
      FirebaseMessaging.onMessageOpenedApp.listen(_handleMessageOpenedApp);

      // Get and register token
      await _registerToken();

      // Listen for token refresh
      _fcm.onTokenRefresh.listen(_onTokenRefresh);
    }
  }

  Future<void> _initializeLocalNotifications() async {
    const androidSettings = AndroidInitializationSettings('@mipmap/ic_launcher');
    const iosSettings = DarwinInitializationSettings(
      requestAlertPermission: true,
      requestBadgePermission: true,
      requestSoundPermission: true,
    );

    const initSettings = InitializationSettings(
      android: androidSettings,
      iOS: iosSettings,
    );

    await _localNotifications.initialize(
      initSettings,
      onDidReceiveNotificationResponse: _onNotificationTapped,
    );

    // Create notification channels (Android)
    if (Platform.isAndroid) {
      await _localNotifications
          .resolvePlatformSpecificImplementation<
              AndroidFlutterLocalNotificationsPlugin>()
          ?.createNotificationChannel(_ordersChannel);

      await _localNotifications
          .resolvePlatformSpecificImplementation<
              AndroidFlutterLocalNotificationsPlugin>()
          ?.createNotificationChannel(_promotionsChannel);
    }
  }

  Future<void> _registerToken() async {
    try {
      final token = await _fcm.getToken();
      if (token == null) return;

      final deviceInfo = DeviceInfoPlugin();
      final packageInfo = await PackageInfo.fromPlatform();

      String deviceId;
      String deviceType;
      String deviceName;

      if (Platform.isAndroid) {
        final androidInfo = await deviceInfo.androidInfo;
        deviceId = androidInfo.id;
        deviceType = 'android';
        deviceName = '${androidInfo.brand} ${androidInfo.model}';
      } else {
        final iosInfo = await deviceInfo.iosInfo;
        deviceId = iosInfo.identifierForVendor ?? '';
        deviceType = 'ios';
        deviceName = iosInfo.name;
      }

      await _apiClient.post('/api/v1/notifications/register', data: {
        'token': token,
        'device_id': deviceId,
        'device_type': deviceType,
        'device_name': deviceName,
        'app_version': packageInfo.version,
      });

      await _storage.write('fcm_token', token);
    } catch (e) {
      print('Failed to register FCM token: $e');
    }
  }

  void _onTokenRefresh(String token) async {
    await _registerToken();
  }

  void _handleForegroundMessage(RemoteMessage message) {
    print('Foreground message received: ${message.messageId}');

    final notification = message.notification;
    if (notification == null) return;

    // Show local notification
    _showLocalNotification(
      title: notification.title ?? '',
      body: notification.body ?? '',
      payload: jsonEncode(message.data),
      channelId: _getChannelId(message.data),
    );
  }

  void _handleMessageOpenedApp(RemoteMessage message) {
    print('Message opened app: ${message.data}');
    _navigateFromNotification(message.data);
  }

  void _onNotificationTapped(NotificationResponse response) {
    if (response.payload != null) {
      final data = jsonDecode(response.payload!) as Map<String, dynamic>;
      _navigateFromNotification(data);
    }
  }

  void _navigateFromNotification(Map<String, dynamic> data) {
    final type = data['type'];
    switch (type) {
      case 'order_tracking':
        final orderId = int.tryParse(data['order_id']?.toString() ?? '');
        if (orderId != null) {
          // Navigate to order detail
          // NavigationService.navigateTo('/order-detail', arguments: orderId);
        }
        break;
      case 'promotion':
        // Navigate to promotions
        break;
      case 'loyalty_points':
        // Navigate to loyalty screen
        break;
    }
  }

  String _getChannelId(Map<String, dynamic> data) {
    final type = data['type']?.toString() ?? '';
    if (type.contains('order') || type.contains('delivery')) {
      return 'orders';
    }
    return 'promotions';
  }

  Future<void> _showLocalNotification({
    required String title,
    required String body,
    String? payload,
    String channelId = 'orders',
  }) async {
    final androidDetails = AndroidNotificationDetails(
      channelId,
      channelId == 'orders' ? 'Order Updates' : 'Promotions',
      importance: Importance.high,
      priority: Priority.high,
      icon: '@mipmap/ic_launcher',
    );

    const iosDetails = DarwinNotificationDetails(
      presentAlert: true,
      presentBadge: true,
      presentSound: true,
    );

    final details = NotificationDetails(
      android: androidDetails,
      iOS: iosDetails,
    );

    await _localNotifications.show(
      DateTime.now().millisecondsSinceEpoch ~/ 1000,
      title,
      body,
      details,
      payload: payload,
    );
  }

  Future<void> unregisterToken() async {
    try {
      final token = await _storage.read('fcm_token');
      if (token != null) {
        await _apiClient.post('/api/v1/notifications/unregister', data: {
          'token': token,
        });
        await _storage.delete('fcm_token');
      }
    } catch (e) {
      print('Failed to unregister token: $e');
    }
  }
}
```

**Task 2.2: Notification Preferences Repository (2 hours)**

```dart
// lib/features/notifications/data/repositories/notification_repository.dart

import 'package:dartz/dartz.dart';
import 'package:smart_dairy_customer/core/error/failures.dart';
import 'package:smart_dairy_customer/core/network/api_client.dart';

class NotificationPreferences {
  final bool orderUpdates;
  final bool promotions;
  final bool deliveryUpdates;
  final bool loyaltyPoints;
  final bool newProducts;

  NotificationPreferences({
    required this.orderUpdates,
    required this.promotions,
    required this.deliveryUpdates,
    required this.loyaltyPoints,
    required this.newProducts,
  });

  factory NotificationPreferences.fromJson(Map<String, dynamic> json) {
    return NotificationPreferences(
      orderUpdates: json['order_updates'] ?? true,
      promotions: json['promotions'] ?? true,
      deliveryUpdates: json['delivery_updates'] ?? true,
      loyaltyPoints: json['loyalty_points'] ?? true,
      newProducts: json['new_products'] ?? false,
    );
  }

  Map<String, dynamic> toJson() => {
        'order_updates': orderUpdates,
        'promotions': promotions,
        'delivery_updates': deliveryUpdates,
        'loyalty_points': loyaltyPoints,
        'new_products': newProducts,
      };

  NotificationPreferences copyWith({
    bool? orderUpdates,
    bool? promotions,
    bool? deliveryUpdates,
    bool? loyaltyPoints,
    bool? newProducts,
  }) {
    return NotificationPreferences(
      orderUpdates: orderUpdates ?? this.orderUpdates,
      promotions: promotions ?? this.promotions,
      deliveryUpdates: deliveryUpdates ?? this.deliveryUpdates,
      loyaltyPoints: loyaltyPoints ?? this.loyaltyPoints,
      newProducts: newProducts ?? this.newProducts,
    );
  }
}

class NotificationRepository {
  final ApiClient _apiClient;

  NotificationRepository(this._apiClient);

  Future<Either<Failure, NotificationPreferences>> getPreferences() async {
    try {
      final response = await _apiClient.get('/api/v1/notifications/preferences');

      if (response['success'] == true) {
        return Right(NotificationPreferences.fromJson(response['data']));
      }

      return Left(ServerFailure(response['error'] ?? 'Failed to load preferences'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  Future<Either<Failure, void>> updatePreferences(
      NotificationPreferences preferences) async {
    try {
      final response = await _apiClient.put(
        '/api/v1/notifications/preferences',
        data: preferences.toJson(),
      );

      if (response['success'] == true) {
        return const Right(null);
      }

      return Left(ServerFailure(response['error'] ?? 'Failed to update preferences'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  Future<Either<Failure, List<NotificationHistoryItem>>> getHistory({
    int page = 1,
    int limit = 20,
  }) async {
    try {
      final response = await _apiClient.get(
        '/api/v1/notifications/history',
        queryParameters: {'page': page, 'limit': limit},
      );

      if (response['success'] == true) {
        final items = (response['data']['notifications'] as List)
            .map((json) => NotificationHistoryItem.fromJson(json))
            .toList();
        return Right(items);
      }

      return Left(ServerFailure(response['error'] ?? 'Failed to load history'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  Future<Either<Failure, void>> markAsRead(int notificationId) async {
    try {
      final response = await _apiClient.post(
        '/api/v1/notifications/$notificationId/read',
      );

      if (response['success'] == true) {
        return const Right(null);
      }

      return Left(ServerFailure(response['error'] ?? 'Failed to mark as read'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }
}

class NotificationHistoryItem {
  final int id;
  final String title;
  final String body;
  final Map<String, dynamic>? data;
  final bool read;
  final DateTime createdAt;

  NotificationHistoryItem({
    required this.id,
    required this.title,
    required this.body,
    this.data,
    required this.read,
    required this.createdAt,
  });

  factory NotificationHistoryItem.fromJson(Map<String, dynamic> json) {
    return NotificationHistoryItem(
      id: json['id'],
      title: json['title'],
      body: json['body'],
      data: json['data'] != null ? Map<String, dynamic>.from(json['data']) : null,
      read: json['read'] ?? false,
      createdAt: DateTime.parse(json['created_at']),
    );
  }
}
```

**Task 2.3: Notification Log Model (3 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/models/notification_log.py

from odoo import models, fields, api
import json

class MobileNotificationLog(models.Model):
    _name = 'mobile.notification.log'
    _description = 'Mobile Notification Log'
    _order = 'create_date desc'

    user_id = fields.Many2one('res.users', 'User', required=True, index=True)
    title = fields.Char('Title', required=True)
    body = fields.Text('Body')
    data = fields.Text('Data (JSON)')
    notification_type = fields.Selection([
        ('order_status', 'Order Status'),
        ('delivery', 'Delivery Update'),
        ('promotion', 'Promotion'),
        ('loyalty', 'Loyalty Points'),
        ('general', 'General'),
    ], 'Type', default='general')
    is_read = fields.Boolean('Read', default=False)
    success = fields.Boolean('Sent Successfully', default=False)
    sent_count = fields.Integer('Sent Count', default=0)
    failed_count = fields.Integer('Failed Count', default=0)
    error_message = fields.Text('Error Message')

    @api.model
    def create_notification(self, user_id, title, body, notification_type='general', data=None):
        """Create a notification log entry."""
        return self.create({
            'user_id': user_id,
            'title': title,
            'body': body,
            'notification_type': notification_type,
            'data': json.dumps(data) if data else None,
        })

    def get_data_dict(self):
        """Get data as dictionary."""
        if self.data:
            return json.loads(self.data)
        return {}
```

#### Dev 3 - Mobile Lead (8 hours)

**Task 3.1: Notification Provider (3 hours)**

```dart
// lib/features/notifications/presentation/providers/notification_provider.dart

import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:smart_dairy_customer/features/notifications/data/repositories/notification_repository.dart';

// Preferences state
class NotificationPreferencesState {
  final NotificationPreferences? preferences;
  final bool isLoading;
  final bool isSaving;
  final String? error;

  const NotificationPreferencesState({
    this.preferences,
    this.isLoading = false,
    this.isSaving = false,
    this.error,
  });

  NotificationPreferencesState copyWith({
    NotificationPreferences? preferences,
    bool? isLoading,
    bool? isSaving,
    String? error,
  }) {
    return NotificationPreferencesState(
      preferences: preferences ?? this.preferences,
      isLoading: isLoading ?? this.isLoading,
      isSaving: isSaving ?? this.isSaving,
      error: error,
    );
  }
}

class NotificationPreferencesNotifier
    extends StateNotifier<NotificationPreferencesState> {
  final NotificationRepository _repository;

  NotificationPreferencesNotifier(this._repository)
      : super(const NotificationPreferencesState()) {
    loadPreferences();
  }

  Future<void> loadPreferences() async {
    state = state.copyWith(isLoading: true, error: null);

    final result = await _repository.getPreferences();

    result.fold(
      (failure) => state = state.copyWith(
        isLoading: false,
        error: failure.message,
      ),
      (preferences) => state = state.copyWith(
        isLoading: false,
        preferences: preferences,
      ),
    );
  }

  Future<bool> updatePreference(String key, bool value) async {
    if (state.preferences == null) return false;

    final newPrefs = _updatePreference(state.preferences!, key, value);
    state = state.copyWith(preferences: newPrefs, isSaving: true);

    final result = await _repository.updatePreferences(newPrefs);

    return result.fold(
      (failure) {
        state = state.copyWith(isSaving: false, error: failure.message);
        return false;
      },
      (_) {
        state = state.copyWith(isSaving: false);
        return true;
      },
    );
  }

  NotificationPreferences _updatePreference(
      NotificationPreferences prefs, String key, bool value) {
    switch (key) {
      case 'orderUpdates':
        return prefs.copyWith(orderUpdates: value);
      case 'promotions':
        return prefs.copyWith(promotions: value);
      case 'deliveryUpdates':
        return prefs.copyWith(deliveryUpdates: value);
      case 'loyaltyPoints':
        return prefs.copyWith(loyaltyPoints: value);
      case 'newProducts':
        return prefs.copyWith(newProducts: value);
      default:
        return prefs;
    }
  }
}

// Providers
final notificationRepositoryProvider = Provider<NotificationRepository>((ref) {
  throw UnimplementedError('Override in main.dart');
});

final notificationPreferencesProvider = StateNotifierProvider<
    NotificationPreferencesNotifier, NotificationPreferencesState>((ref) {
  return NotificationPreferencesNotifier(
    ref.watch(notificationRepositoryProvider),
  );
});

// History provider
final notificationHistoryProvider =
    FutureProvider<List<NotificationHistoryItem>>((ref) async {
  final repository = ref.watch(notificationRepositoryProvider);
  final result = await repository.getHistory();
  return result.fold(
    (failure) => throw Exception(failure.message),
    (items) => items,
  );
});
```

**Task 3.2: Notification Settings Screen (3 hours)**

```dart
// lib/features/notifications/presentation/screens/notification_settings_screen.dart

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:smart_dairy_customer/core/theme/app_colors.dart';
import 'package:smart_dairy_customer/features/notifications/presentation/providers/notification_provider.dart';

class NotificationSettingsScreen extends ConsumerWidget {
  const NotificationSettingsScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final state = ref.watch(notificationPreferencesProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Notification Settings'),
      ),
      body: state.isLoading
          ? const Center(child: CircularProgressIndicator())
          : state.error != null && state.preferences == null
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text(state.error!),
                      ElevatedButton(
                        onPressed: () => ref
                            .read(notificationPreferencesProvider.notifier)
                            .loadPreferences(),
                        child: const Text('Retry'),
                      ),
                    ],
                  ),
                )
              : ListView(
                  padding: const EdgeInsets.all(16),
                  children: [
                    // Info card
                    Card(
                      color: AppColors.primary.withOpacity(0.05),
                      child: const Padding(
                        padding: EdgeInsets.all(16),
                        child: Row(
                          children: [
                            Icon(Icons.notifications_active,
                                color: AppColors.primary),
                            SizedBox(width: 12),
                            Expanded(
                              child: Text(
                                'Choose which notifications you want to receive from Smart Dairy',
                                style: TextStyle(fontSize: 13),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),

                    const SizedBox(height: 24),

                    // Order notifications
                    _buildSection(
                      context,
                      ref,
                      'Orders & Delivery',
                      [
                        _NotificationOption(
                          key: 'orderUpdates',
                          title: 'Order Updates',
                          subtitle: 'Get notified about order confirmations and status changes',
                          icon: Icons.shopping_bag_outlined,
                          value: state.preferences?.orderUpdates ?? true,
                        ),
                        _NotificationOption(
                          key: 'deliveryUpdates',
                          title: 'Delivery Updates',
                          subtitle: 'Real-time updates when your order is on the way',
                          icon: Icons.local_shipping_outlined,
                          value: state.preferences?.deliveryUpdates ?? true,
                        ),
                      ],
                    ),

                    const SizedBox(height: 24),

                    // Marketing notifications
                    _buildSection(
                      context,
                      ref,
                      'Offers & Updates',
                      [
                        _NotificationOption(
                          key: 'promotions',
                          title: 'Promotions & Offers',
                          subtitle: 'Special discounts and promotional offers',
                          icon: Icons.local_offer_outlined,
                          value: state.preferences?.promotions ?? true,
                        ),
                        _NotificationOption(
                          key: 'loyaltyPoints',
                          title: 'Loyalty Points',
                          subtitle: 'Updates about your points balance and rewards',
                          icon: Icons.stars_outlined,
                          value: state.preferences?.loyaltyPoints ?? true,
                        ),
                        _NotificationOption(
                          key: 'newProducts',
                          title: 'New Products',
                          subtitle: 'Be the first to know about new products',
                          icon: Icons.new_releases_outlined,
                          value: state.preferences?.newProducts ?? false,
                        ),
                      ],
                    ),

                    if (state.isSaving)
                      const Padding(
                        padding: EdgeInsets.all(16),
                        child: Center(
                          child: SizedBox(
                            width: 20,
                            height: 20,
                            child: CircularProgressIndicator(strokeWidth: 2),
                          ),
                        ),
                      ),
                  ],
                ),
    );
  }

  Widget _buildSection(
    BuildContext context,
    WidgetRef ref,
    String title,
    List<_NotificationOption> options,
  ) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          title,
          style: const TextStyle(
            fontSize: 16,
            fontWeight: FontWeight.bold,
            color: Colors.grey,
          ),
        ),
        const SizedBox(height: 12),
        Card(
          child: Column(
            children: options.map((option) {
              final isLast = option == options.last;
              return Column(
                children: [
                  SwitchListTile(
                    secondary: Icon(option.icon, color: AppColors.primary),
                    title: Text(option.title),
                    subtitle: Text(
                      option.subtitle,
                      style: TextStyle(fontSize: 12, color: Colors.grey[600]),
                    ),
                    value: option.value,
                    onChanged: (value) {
                      ref
                          .read(notificationPreferencesProvider.notifier)
                          .updatePreference(option.key, value);
                    },
                  ),
                  if (!isLast) const Divider(height: 1, indent: 72),
                ],
              );
            }).toList(),
          ),
        ),
      ],
    );
  }
}

class _NotificationOption {
  final String key;
  final String title;
  final String subtitle;
  final IconData icon;
  final bool value;

  _NotificationOption({
    required this.key,
    required this.title,
    required this.subtitle,
    required this.icon,
    required this.value,
  });
}
```

**Task 3.3: Notification History Screen (2 hours)**

```dart
// lib/features/notifications/presentation/screens/notification_history_screen.dart

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import 'package:smart_dairy_customer/core/theme/app_colors.dart';
import 'package:smart_dairy_customer/features/notifications/data/repositories/notification_repository.dart';
import 'package:smart_dairy_customer/features/notifications/presentation/providers/notification_provider.dart';

class NotificationHistoryScreen extends ConsumerWidget {
  const NotificationHistoryScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final historyAsync = ref.watch(notificationHistoryProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Notifications'),
        actions: [
          TextButton(
            onPressed: () {
              // Mark all as read
            },
            child: const Text('Mark all read'),
          ),
        ],
      ),
      body: historyAsync.when(
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (error, _) => Center(child: Text('Error: $error')),
        data: (notifications) {
          if (notifications.isEmpty) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.notifications_none,
                      size: 64, color: Colors.grey[400]),
                  const SizedBox(height: 16),
                  Text(
                    'No notifications yet',
                    style: TextStyle(color: Colors.grey[600]),
                  ),
                ],
              ),
            );
          }

          return ListView.separated(
            padding: const EdgeInsets.all(16),
            itemCount: notifications.length,
            separatorBuilder: (_, __) => const SizedBox(height: 8),
            itemBuilder: (context, index) {
              final item = notifications[index];
              return _NotificationCard(
                item: item,
                onTap: () => _handleNotificationTap(context, ref, item),
              );
            },
          );
        },
      ),
    );
  }

  void _handleNotificationTap(
    BuildContext context,
    WidgetRef ref,
    NotificationHistoryItem item,
  ) {
    // Mark as read
    if (!item.read) {
      ref.read(notificationRepositoryProvider).markAsRead(item.id);
    }

    // Navigate based on data
    final type = item.data?['type'];
    switch (type) {
      case 'order_tracking':
        final orderId = int.tryParse(item.data?['order_id']?.toString() ?? '');
        if (orderId != null) {
          Navigator.pushNamed(context, '/order-detail', arguments: orderId);
        }
        break;
      // Handle other types...
    }
  }
}

class _NotificationCard extends StatelessWidget {
  final NotificationHistoryItem item;
  final VoidCallback onTap;

  const _NotificationCard({required this.item, required this.onTap});

  @override
  Widget build(BuildContext context) {
    return Card(
      color: item.read ? null : AppColors.primary.withOpacity(0.05),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                padding: const EdgeInsets.all(10),
                decoration: BoxDecoration(
                  color: AppColors.primary.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Icon(
                  _getIcon(item.data?['type']),
                  color: AppColors.primary,
                  size: 24,
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Expanded(
                          child: Text(
                            item.title,
                            style: TextStyle(
                              fontWeight:
                                  item.read ? FontWeight.normal : FontWeight.bold,
                            ),
                          ),
                        ),
                        if (!item.read)
                          Container(
                            width: 8,
                            height: 8,
                            decoration: const BoxDecoration(
                              color: AppColors.primary,
                              shape: BoxShape.circle,
                            ),
                          ),
                      ],
                    ),
                    const SizedBox(height: 4),
                    Text(
                      item.body,
                      style: TextStyle(
                        color: Colors.grey[600],
                        fontSize: 13,
                      ),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 8),
                    Text(
                      _formatDate(item.createdAt),
                      style: TextStyle(
                        color: Colors.grey[500],
                        fontSize: 12,
                      ),
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

  IconData _getIcon(String? type) {
    switch (type) {
      case 'order_status':
        return Icons.shopping_bag;
      case 'delivery':
        return Icons.local_shipping;
      case 'promotion':
        return Icons.local_offer;
      case 'loyalty':
        return Icons.stars;
      default:
        return Icons.notifications;
    }
  }

  String _formatDate(DateTime date) {
    final now = DateTime.now();
    final diff = now.difference(date);

    if (diff.inMinutes < 60) {
      return '${diff.inMinutes}m ago';
    } else if (diff.inHours < 24) {
      return '${diff.inHours}h ago';
    } else if (diff.inDays < 7) {
      return '${diff.inDays}d ago';
    } else {
      return DateFormat('MMM dd').format(date);
    }
  }
}
```

#### End-of-Day 293 Deliverables

- [ ] FCM token model and management
- [ ] Notification service with Firebase Admin SDK
- [ ] Notification API controller
- [ ] Flutter Firebase Messaging integration
- [ ] Local notifications setup
- [ ] Notification preferences repository
- [ ] Notification log model
- [ ] Notification provider with state management
- [ ] Notification settings screen
- [ ] Notification history screen

---

### Day 294 (Sprint Day 4): Loyalty Points & Rewards

#### Objectives
- Build loyalty points API endpoints
- Create loyalty points Flutter screens
- Implement points redemption flow

#### Dev 1 - Backend Lead (8 hours)

**Task 1.1: Loyalty Points API (4 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/loyalty_controller.py

from odoo import http
from odoo.http import request
from datetime import datetime, timedelta

class LoyaltyController(http.Controller):

    @http.route('/api/v1/loyalty/balance', type='json', auth='jwt', methods=['GET'], csrf=False)
    def get_balance(self, **kwargs):
        """Get loyalty points balance and summary."""
        try:
            current_user = request.env.user
            partner = current_user.partner_id

            # Get loyalty program
            LoyaltyProgram = request.env['loyalty.program'].sudo()
            program = LoyaltyProgram.search([('active', '=', True)], limit=1)

            if not program:
                return {'success': False, 'error': 'No active loyalty program'}

            # Get customer's loyalty card
            LoyaltyCard = request.env['loyalty.card'].sudo()
            card = LoyaltyCard.search([
                ('partner_id', '=', partner.id),
                ('program_id', '=', program.id)
            ], limit=1)

            if not card:
                # Create card if not exists
                card = LoyaltyCard.create({
                    'partner_id': partner.id,
                    'program_id': program.id,
                    'points': 0
                })

            # Calculate points value
            points_value = card.points * program.point_value if hasattr(program, 'point_value') else 0

            # Get points expiring soon (within 30 days)
            expiring_points = self._get_expiring_points(card, 30)

            return {
                'success': True,
                'data': {
                    'balance': card.points,
                    'points_value': points_value,
                    'currency': partner.company_id.currency_id.symbol,
                    'tier': self._get_tier(card.points),
                    'tier_progress': self._get_tier_progress(card.points),
                    'expiring_soon': expiring_points,
                    'program_name': program.name,
                    'earn_rate': f"Earn 1 point for every ৳{program.points_per_unit if hasattr(program, 'points_per_unit') else 100} spent",
                }
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/loyalty/history', type='json', auth='jwt', methods=['GET'], csrf=False)
    def get_history(self, **kwargs):
        """Get loyalty points transaction history."""
        try:
            current_user = request.env.user
            partner = current_user.partner_id

            page = int(kwargs.get('page', 1))
            limit = min(int(kwargs.get('limit', 20)), 50)
            offset = (page - 1) * limit
            filter_type = kwargs.get('type')  # 'earned', 'redeemed', 'expired'

            # Get transactions
            PointsHistory = request.env['loyalty.points.history'].sudo()
            domain = [('partner_id', '=', partner.id)]

            if filter_type == 'earned':
                domain.append(('points', '>', 0))
            elif filter_type == 'redeemed':
                domain.append(('points', '<', 0))
                domain.append(('transaction_type', '=', 'redemption'))
            elif filter_type == 'expired':
                domain.append(('transaction_type', '=', 'expiry'))

            total = PointsHistory.search_count(domain)
            transactions = PointsHistory.search(
                domain,
                offset=offset,
                limit=limit,
                order='create_date desc'
            )

            history = []
            for trans in transactions:
                history.append({
                    'id': trans.id,
                    'points': trans.points,
                    'description': trans.description,
                    'transaction_type': trans.transaction_type,
                    'order_name': trans.order_id.name if trans.order_id else None,
                    'date': trans.create_date.isoformat(),
                    'balance_after': trans.balance_after
                })

            return {
                'success': True,
                'data': {
                    'transactions': history,
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

    @http.route('/api/v1/loyalty/rewards', type='json', auth='jwt', methods=['GET'], csrf=False)
    def get_rewards(self, **kwargs):
        """Get available rewards for redemption."""
        try:
            current_user = request.env.user
            partner = current_user.partner_id

            # Get customer's points
            card = self._get_loyalty_card(partner.id)
            current_points = card.points if card else 0

            # Get available rewards
            Reward = request.env['loyalty.reward'].sudo()
            rewards = Reward.search([('active', '=', True)])

            rewards_data = []
            for reward in rewards:
                rewards_data.append({
                    'id': reward.id,
                    'name': reward.name,
                    'description': reward.description,
                    'points_required': reward.points_required,
                    'reward_type': reward.reward_type,  # 'discount', 'product', 'cashback'
                    'discount_amount': reward.discount_amount if reward.reward_type == 'discount' else None,
                    'product_id': reward.product_id.id if reward.reward_type == 'product' else None,
                    'product_name': reward.product_id.name if reward.reward_type == 'product' else None,
                    'can_redeem': current_points >= reward.points_required,
                    'image': f"/web/image/loyalty.reward/{reward.id}/image_256" if reward.image_256 else None,
                })

            return {
                'success': True,
                'data': {
                    'rewards': rewards_data,
                    'current_points': current_points
                }
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/loyalty/redeem', type='json', auth='jwt', methods=['POST'], csrf=False)
    def redeem_points(self, **kwargs):
        """Redeem points for a reward."""
        try:
            current_user = request.env.user
            partner = current_user.partner_id

            reward_id = kwargs.get('reward_id')

            if not reward_id:
                return {'success': False, 'error': 'Reward ID required'}

            # Get reward
            Reward = request.env['loyalty.reward'].sudo()
            reward = Reward.browse(reward_id)

            if not reward.exists() or not reward.active:
                return {'success': False, 'error': 'Reward not found'}

            # Get customer's card
            card = self._get_loyalty_card(partner.id)

            if not card or card.points < reward.points_required:
                return {'success': False, 'error': 'Insufficient points'}

            # Create redemption
            redemption = self._create_redemption(card, reward)

            # Deduct points
            card.points -= reward.points_required

            # Log transaction
            self._log_points_transaction(
                partner_id=partner.id,
                points=-reward.points_required,
                description=f"Redeemed: {reward.name}",
                transaction_type='redemption',
                balance_after=card.points
            )

            return {
                'success': True,
                'data': {
                    'redemption_id': redemption.id,
                    'redemption_code': redemption.code,
                    'reward_name': reward.name,
                    'points_deducted': reward.points_required,
                    'new_balance': card.points,
                    'valid_until': redemption.valid_until.isoformat() if redemption.valid_until else None
                }
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    def _get_loyalty_card(self, partner_id):
        """Get or create loyalty card for partner."""
        LoyaltyProgram = request.env['loyalty.program'].sudo()
        program = LoyaltyProgram.search([('active', '=', True)], limit=1)

        if not program:
            return None

        LoyaltyCard = request.env['loyalty.card'].sudo()
        card = LoyaltyCard.search([
            ('partner_id', '=', partner_id),
            ('program_id', '=', program.id)
        ], limit=1)

        return card

    def _get_tier(self, points):
        """Get tier based on points."""
        if points >= 10000:
            return {'name': 'Platinum', 'color': '#E5E4E2', 'icon': 'diamond'}
        elif points >= 5000:
            return {'name': 'Gold', 'color': '#FFD700', 'icon': 'star'}
        elif points >= 2000:
            return {'name': 'Silver', 'color': '#C0C0C0', 'icon': 'verified'}
        else:
            return {'name': 'Bronze', 'color': '#CD7F32', 'icon': 'workspace_premium'}

    def _get_tier_progress(self, points):
        """Get progress to next tier."""
        tiers = [
            (2000, 'Silver'),
            (5000, 'Gold'),
            (10000, 'Platinum'),
        ]

        for threshold, tier_name in tiers:
            if points < threshold:
                return {
                    'next_tier': tier_name,
                    'points_needed': threshold - points,
                    'progress_percent': (points / threshold) * 100
                }

        return {
            'next_tier': None,
            'points_needed': 0,
            'progress_percent': 100
        }

    def _get_expiring_points(self, card, days):
        """Get points expiring within specified days."""
        # Implement based on your expiry logic
        return 0

    def _log_points_transaction(self, partner_id, points, description, transaction_type, balance_after, order_id=None):
        """Log points transaction."""
        request.env['loyalty.points.history'].sudo().create({
            'partner_id': partner_id,
            'points': points,
            'description': description,
            'transaction_type': transaction_type,
            'balance_after': balance_after,
            'order_id': order_id
        })

    def _create_redemption(self, card, reward):
        """Create redemption record."""
        import uuid
        from datetime import datetime, timedelta

        return request.env['loyalty.redemption'].sudo().create({
            'card_id': card.id,
            'reward_id': reward.id,
            'code': str(uuid.uuid4())[:8].upper(),
            'points_used': reward.points_required,
            'valid_until': datetime.now() + timedelta(days=30),
            'state': 'valid'
        })
```

**Task 1.2: Loyalty Points History Model (2 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/models/loyalty_points_history.py

from odoo import models, fields

class LoyaltyPointsHistory(models.Model):
    _name = 'loyalty.points.history'
    _description = 'Loyalty Points Transaction History'
    _order = 'create_date desc'

    partner_id = fields.Many2one('res.partner', 'Customer', required=True, index=True)
    points = fields.Integer('Points', required=True)
    description = fields.Char('Description')
    transaction_type = fields.Selection([
        ('earn', 'Earned'),
        ('redemption', 'Redemption'),
        ('expiry', 'Expiry'),
        ('adjustment', 'Adjustment'),
        ('bonus', 'Bonus'),
    ], 'Type', required=True, default='earn')
    order_id = fields.Many2one('sale.order', 'Related Order')
    balance_after = fields.Integer('Balance After')
```

**Task 1.3: Loyalty Redemption Model (2 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/models/loyalty_redemption.py

from odoo import models, fields, api

class LoyaltyRedemption(models.Model):
    _name = 'loyalty.redemption'
    _description = 'Loyalty Points Redemption'
    _order = 'create_date desc'

    card_id = fields.Many2one('loyalty.card', 'Loyalty Card', required=True)
    reward_id = fields.Many2one('loyalty.reward', 'Reward', required=True)
    code = fields.Char('Redemption Code', required=True, index=True)
    points_used = fields.Integer('Points Used', required=True)
    valid_until = fields.Datetime('Valid Until')
    used_date = fields.Datetime('Used Date')
    order_id = fields.Many2one('sale.order', 'Applied to Order')
    state = fields.Selection([
        ('valid', 'Valid'),
        ('used', 'Used'),
        ('expired', 'Expired'),
        ('cancelled', 'Cancelled'),
    ], 'Status', default='valid')

    def apply_to_order(self, order):
        """Apply redemption to an order."""
        self.ensure_one()
        if self.state != 'valid':
            return False

        if self.valid_until and fields.Datetime.now() > self.valid_until:
            self.state = 'expired'
            return False

        self.write({
            'state': 'used',
            'used_date': fields.Datetime.now(),
            'order_id': order.id
        })

        return True
```

#### Dev 2 - Full-Stack Developer (8 hours)

**Task 2.1: Loyalty Repository (3 hours)**

```dart
// lib/features/loyalty/data/repositories/loyalty_repository.dart

import 'package:dartz/dartz.dart';
import 'package:smart_dairy_customer/core/error/failures.dart';
import 'package:smart_dairy_customer/core/network/api_client.dart';
import 'package:smart_dairy_customer/features/loyalty/data/models/loyalty_models.dart';

class LoyaltyRepository {
  final ApiClient _apiClient;

  LoyaltyRepository(this._apiClient);

  Future<Either<Failure, LoyaltyBalance>> getBalance() async {
    try {
      final response = await _apiClient.get('/api/v1/loyalty/balance');

      if (response['success'] == true) {
        return Right(LoyaltyBalance.fromJson(response['data']));
      }

      return Left(ServerFailure(response['error'] ?? 'Failed to load balance'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  Future<Either<Failure, PointsHistoryResponse>> getHistory({
    int page = 1,
    int limit = 20,
    String? type,
  }) async {
    try {
      final queryParams = <String, dynamic>{
        'page': page,
        'limit': limit,
      };
      if (type != null) queryParams['type'] = type;

      final response = await _apiClient.get(
        '/api/v1/loyalty/history',
        queryParameters: queryParams,
      );

      if (response['success'] == true) {
        return Right(PointsHistoryResponse.fromJson(response['data']));
      }

      return Left(ServerFailure(response['error'] ?? 'Failed to load history'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  Future<Either<Failure, RewardsResponse>> getRewards() async {
    try {
      final response = await _apiClient.get('/api/v1/loyalty/rewards');

      if (response['success'] == true) {
        return Right(RewardsResponse.fromJson(response['data']));
      }

      return Left(ServerFailure(response['error'] ?? 'Failed to load rewards'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  Future<Either<Failure, RedemptionResult>> redeemPoints(int rewardId) async {
    try {
      final response = await _apiClient.post(
        '/api/v1/loyalty/redeem',
        data: {'reward_id': rewardId},
      );

      if (response['success'] == true) {
        return Right(RedemptionResult.fromJson(response['data']));
      }

      return Left(ServerFailure(response['error'] ?? 'Failed to redeem'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }
}
```

**Task 2.2: Loyalty Models (2 hours)**

```dart
// lib/features/loyalty/data/models/loyalty_models.dart

import 'package:freezed_annotation/freezed_annotation.dart';

part 'loyalty_models.freezed.dart';
part 'loyalty_models.g.dart';

@freezed
class LoyaltyBalance with _$LoyaltyBalance {
  const factory LoyaltyBalance({
    required int balance,
    required double pointsValue,
    required String currency,
    required LoyaltyTier tier,
    required TierProgress tierProgress,
    required int expiringSoon,
    required String programName,
    required String earnRate,
  }) = _LoyaltyBalance;

  factory LoyaltyBalance.fromJson(Map<String, dynamic> json) =>
      _$LoyaltyBalanceFromJson(json);
}

@freezed
class LoyaltyTier with _$LoyaltyTier {
  const factory LoyaltyTier({
    required String name,
    required String color,
    required String icon,
  }) = _LoyaltyTier;

  factory LoyaltyTier.fromJson(Map<String, dynamic> json) =>
      _$LoyaltyTierFromJson(json);
}

@freezed
class TierProgress with _$TierProgress {
  const factory TierProgress({
    String? nextTier,
    required int pointsNeeded,
    required double progressPercent,
  }) = _TierProgress;

  factory TierProgress.fromJson(Map<String, dynamic> json) =>
      _$TierProgressFromJson(json);
}

@freezed
class PointsTransaction with _$PointsTransaction {
  const factory PointsTransaction({
    required int id,
    required int points,
    required String description,
    required String transactionType,
    String? orderName,
    required DateTime date,
    required int balanceAfter,
  }) = _PointsTransaction;

  factory PointsTransaction.fromJson(Map<String, dynamic> json) =>
      _$PointsTransactionFromJson(json);
}

@freezed
class PointsHistoryResponse with _$PointsHistoryResponse {
  const factory PointsHistoryResponse({
    required List<PointsTransaction> transactions,
    required PaginationInfo pagination,
  }) = _PointsHistoryResponse;

  factory PointsHistoryResponse.fromJson(Map<String, dynamic> json) =>
      _$PointsHistoryResponseFromJson(json);
}

@freezed
class LoyaltyReward with _$LoyaltyReward {
  const factory LoyaltyReward({
    required int id,
    required String name,
    String? description,
    required int pointsRequired,
    required String rewardType,
    double? discountAmount,
    int? productId,
    String? productName,
    required bool canRedeem,
    String? image,
  }) = _LoyaltyReward;

  factory LoyaltyReward.fromJson(Map<String, dynamic> json) =>
      _$LoyaltyRewardFromJson(json);
}

@freezed
class RewardsResponse with _$RewardsResponse {
  const factory RewardsResponse({
    required List<LoyaltyReward> rewards,
    required int currentPoints,
  }) = _RewardsResponse;

  factory RewardsResponse.fromJson(Map<String, dynamic> json) =>
      _$RewardsResponseFromJson(json);
}

@freezed
class RedemptionResult with _$RedemptionResult {
  const factory RedemptionResult({
    required int redemptionId,
    required String redemptionCode,
    required String rewardName,
    required int pointsDeducted,
    required int newBalance,
    DateTime? validUntil,
  }) = _RedemptionResult;

  factory RedemptionResult.fromJson(Map<String, dynamic> json) =>
      _$RedemptionResultFromJson(json);
}

@freezed
class PaginationInfo with _$PaginationInfo {
  const factory PaginationInfo({
    required int page,
    required int limit,
    required int total,
    required bool hasMore,
  }) = _PaginationInfo;

  factory PaginationInfo.fromJson(Map<String, dynamic> json) =>
      _$PaginationInfoFromJson(json);
}
```

**Task 2.3: Loyalty Provider (3 hours)**

```dart
// lib/features/loyalty/presentation/providers/loyalty_provider.dart

import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:smart_dairy_customer/features/loyalty/data/models/loyalty_models.dart';
import 'package:smart_dairy_customer/features/loyalty/data/repositories/loyalty_repository.dart';

// Balance state
class LoyaltyBalanceState {
  final LoyaltyBalance? balance;
  final bool isLoading;
  final String? error;

  const LoyaltyBalanceState({
    this.balance,
    this.isLoading = false,
    this.error,
  });

  LoyaltyBalanceState copyWith({
    LoyaltyBalance? balance,
    bool? isLoading,
    String? error,
  }) {
    return LoyaltyBalanceState(
      balance: balance ?? this.balance,
      isLoading: isLoading ?? this.isLoading,
      error: error,
    );
  }
}

class LoyaltyBalanceNotifier extends StateNotifier<LoyaltyBalanceState> {
  final LoyaltyRepository _repository;

  LoyaltyBalanceNotifier(this._repository) : super(const LoyaltyBalanceState()) {
    loadBalance();
  }

  Future<void> loadBalance() async {
    state = state.copyWith(isLoading: true, error: null);

    final result = await _repository.getBalance();

    result.fold(
      (failure) => state = state.copyWith(
        isLoading: false,
        error: failure.message,
      ),
      (balance) => state = state.copyWith(
        isLoading: false,
        balance: balance,
      ),
    );
  }
}

// Rewards state
class RewardsState {
  final List<LoyaltyReward> rewards;
  final int currentPoints;
  final bool isLoading;
  final bool isRedeeming;
  final String? error;

  const RewardsState({
    this.rewards = const [],
    this.currentPoints = 0,
    this.isLoading = false,
    this.isRedeeming = false,
    this.error,
  });

  RewardsState copyWith({
    List<LoyaltyReward>? rewards,
    int? currentPoints,
    bool? isLoading,
    bool? isRedeeming,
    String? error,
  }) {
    return RewardsState(
      rewards: rewards ?? this.rewards,
      currentPoints: currentPoints ?? this.currentPoints,
      isLoading: isLoading ?? this.isLoading,
      isRedeeming: isRedeeming ?? this.isRedeeming,
      error: error,
    );
  }
}

class RewardsNotifier extends StateNotifier<RewardsState> {
  final LoyaltyRepository _repository;

  RewardsNotifier(this._repository) : super(const RewardsState()) {
    loadRewards();
  }

  Future<void> loadRewards() async {
    state = state.copyWith(isLoading: true, error: null);

    final result = await _repository.getRewards();

    result.fold(
      (failure) => state = state.copyWith(
        isLoading: false,
        error: failure.message,
      ),
      (response) => state = state.copyWith(
        isLoading: false,
        rewards: response.rewards,
        currentPoints: response.currentPoints,
      ),
    );
  }

  Future<RedemptionResult?> redeemReward(int rewardId) async {
    state = state.copyWith(isRedeeming: true);

    final result = await _repository.redeemPoints(rewardId);

    return result.fold(
      (failure) {
        state = state.copyWith(isRedeeming: false, error: failure.message);
        return null;
      },
      (redemption) {
        state = state.copyWith(
          isRedeeming: false,
          currentPoints: redemption.newBalance,
        );
        return redemption;
      },
    );
  }
}

// Providers
final loyaltyRepositoryProvider = Provider<LoyaltyRepository>((ref) {
  throw UnimplementedError('Override in main.dart');
});

final loyaltyBalanceProvider =
    StateNotifierProvider<LoyaltyBalanceNotifier, LoyaltyBalanceState>((ref) {
  return LoyaltyBalanceNotifier(ref.watch(loyaltyRepositoryProvider));
});

final rewardsProvider =
    StateNotifierProvider<RewardsNotifier, RewardsState>((ref) {
  return RewardsNotifier(ref.watch(loyaltyRepositoryProvider));
});

final pointsHistoryProvider =
    FutureProvider.family<PointsHistoryResponse, String?>((ref, type) async {
  final repository = ref.watch(loyaltyRepositoryProvider);
  final result = await repository.getHistory(type: type);
  return result.fold(
    (failure) => throw Exception(failure.message),
    (response) => response,
  );
});
```

#### Dev 3 - Mobile Lead (8 hours)

**Task 3.1: Loyalty Points Screen (4 hours)**

```dart
// lib/features/loyalty/presentation/screens/loyalty_screen.dart

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:smart_dairy_customer/core/theme/app_colors.dart';
import 'package:smart_dairy_customer/features/loyalty/presentation/providers/loyalty_provider.dart';
import 'package:smart_dairy_customer/features/loyalty/presentation/widgets/points_balance_card.dart';
import 'package:smart_dairy_customer/features/loyalty/presentation/widgets/tier_progress_card.dart';
import 'package:smart_dairy_customer/features/loyalty/presentation/widgets/reward_card.dart';

class LoyaltyScreen extends ConsumerWidget {
  const LoyaltyScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final balanceState = ref.watch(loyaltyBalanceProvider);
    final rewardsState = ref.watch(rewardsProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Loyalty Points'),
        actions: [
          IconButton(
            icon: const Icon(Icons.history),
            onPressed: () => Navigator.pushNamed(context, '/loyalty-history'),
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () async {
          await ref.read(loyaltyBalanceProvider.notifier).loadBalance();
          await ref.read(rewardsProvider.notifier).loadRewards();
        },
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Balance card
              if (balanceState.isLoading && balanceState.balance == null)
                const Center(child: CircularProgressIndicator())
              else if (balanceState.balance != null) ...[
                PointsBalanceCard(balance: balanceState.balance!),
                const SizedBox(height: 16),
                TierProgressCard(
                  tier: balanceState.balance!.tier,
                  progress: balanceState.balance!.tierProgress,
                ),
              ],

              const SizedBox(height: 24),

              // How to earn section
              _buildHowToEarn(balanceState.balance?.earnRate ?? ''),

              const SizedBox(height: 24),

              // Rewards section
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text(
                    'Redeem Rewards',
                    style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  TextButton(
                    onPressed: () => Navigator.pushNamed(context, '/rewards'),
                    child: const Text('See All'),
                  ),
                ],
              ),
              const SizedBox(height: 12),

              if (rewardsState.isLoading && rewardsState.rewards.isEmpty)
                const Center(child: CircularProgressIndicator())
              else if (rewardsState.rewards.isEmpty)
                const Center(
                  child: Text('No rewards available'),
                )
              else
                SizedBox(
                  height: 200,
                  child: ListView.separated(
                    scrollDirection: Axis.horizontal,
                    itemCount: rewardsState.rewards.take(5).length,
                    separatorBuilder: (_, __) => const SizedBox(width: 12),
                    itemBuilder: (context, index) {
                      final reward = rewardsState.rewards[index];
                      return SizedBox(
                        width: 160,
                        child: RewardCard(
                          reward: reward,
                          onRedeem: reward.canRedeem
                              ? () => _showRedeemDialog(context, ref, reward)
                              : null,
                        ),
                      );
                    },
                  ),
                ),

              const SizedBox(height: 40),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildHowToEarn(String earnRate) {
    return Card(
      color: AppColors.primary.withOpacity(0.05),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: AppColors.primary.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: const Icon(Icons.info_outline, color: AppColors.primary),
                ),
                const SizedBox(width: 12),
                const Text(
                  'How to Earn Points',
                  style: TextStyle(
                    fontWeight: FontWeight.bold,
                    fontSize: 16,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Text(earnRate, style: TextStyle(color: Colors.grey[700])),
            const SizedBox(height: 8),
            const Text(
              '• Order dairy products\n'
              '• Write product reviews\n'
              '• Refer friends\n'
              '• Complete your profile',
              style: TextStyle(fontSize: 13),
            ),
          ],
        ),
      ),
    );
  }

  void _showRedeemDialog(BuildContext context, WidgetRef ref, LoyaltyReward reward) {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: Text('Redeem ${reward.name}?'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('This will cost ${reward.pointsRequired} points.'),
            const SizedBox(height: 8),
            if (reward.description != null)
              Text(
                reward.description!,
                style: TextStyle(color: Colors.grey[600], fontSize: 13),
              ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cancel'),
          ),
          ElevatedButton(
            onPressed: () async {
              Navigator.pop(context);
              final result = await ref
                  .read(rewardsProvider.notifier)
                  .redeemReward(reward.id);
              if (result != null && context.mounted) {
                _showRedemptionSuccess(context, result);
              }
            },
            child: const Text('Redeem'),
          ),
        ],
      ),
    );
  }

  void _showRedemptionSuccess(BuildContext context, RedemptionResult result) {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        icon: const Icon(Icons.check_circle, color: Colors.green, size: 64),
        title: const Text('Redemption Successful!'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Text('You redeemed: ${result.rewardName}'),
            const SizedBox(height: 16),
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: AppColors.primary.withOpacity(0.1),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Column(
                children: [
                  const Text('Your Code', style: TextStyle(color: Colors.grey)),
                  const SizedBox(height: 4),
                  Text(
                    result.redemptionCode,
                    style: const TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                      letterSpacing: 2,
                    ),
                  ),
                ],
              ),
            ),
            if (result.validUntil != null) ...[
              const SizedBox(height: 12),
              Text(
                'Valid until: ${result.validUntil}',
                style: TextStyle(color: Colors.grey[600], fontSize: 13),
              ),
            ],
          ],
        ),
        actions: [
          ElevatedButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Done'),
          ),
        ],
      ),
    );
  }
}
```

**Task 3.2: Points Balance Card (2 hours)**

```dart
// lib/features/loyalty/presentation/widgets/points_balance_card.dart

import 'package:flutter/material.dart';
import 'package:smart_dairy_customer/core/theme/app_colors.dart';
import 'package:smart_dairy_customer/features/loyalty/data/models/loyalty_models.dart';

class PointsBalanceCard extends StatelessWidget {
  final LoyaltyBalance balance;

  const PointsBalanceCard({super.key, required this.balance});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: [
            AppColors.primary,
            AppColors.primary.withOpacity(0.8),
          ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: AppColors.primary.withOpacity(0.3),
            blurRadius: 15,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                balance.programName,
                style: const TextStyle(
                  color: Colors.white70,
                  fontSize: 14,
                ),
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                decoration: BoxDecoration(
                  color: Colors.white.withOpacity(0.2),
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Icon(
                      _getTierIcon(balance.tier.icon),
                      color: Color(int.parse(balance.tier.color.replaceAll('#', '0xFF'))),
                      size: 16,
                    ),
                    const SizedBox(width: 4),
                    Text(
                      balance.tier.name,
                      style: const TextStyle(
                        color: Colors.white,
                        fontWeight: FontWeight.w600,
                        fontSize: 12,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(height: 20),
          const Text(
            'Your Points',
            style: TextStyle(
              color: Colors.white70,
              fontSize: 14,
            ),
          ),
          const SizedBox(height: 4),
          Row(
            crossAxisAlignment: CrossAxisAlignment.end,
            children: [
              Text(
                '${balance.balance}',
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 42,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(width: 8),
              Padding(
                padding: const EdgeInsets.only(bottom: 8),
                child: Text(
                  '≈ ${balance.currency}${balance.pointsValue.toStringAsFixed(0)}',
                  style: const TextStyle(
                    color: Colors.white70,
                    fontSize: 16,
                  ),
                ),
              ),
            ],
          ),
          if (balance.expiringSoon > 0) ...[
            const SizedBox(height: 16),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
              decoration: BoxDecoration(
                color: Colors.orange.withOpacity(0.2),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  const Icon(Icons.warning_amber, color: Colors.orange, size: 18),
                  const SizedBox(width: 8),
                  Text(
                    '${balance.expiringSoon} points expiring soon',
                    style: const TextStyle(
                      color: Colors.white,
                      fontSize: 13,
                    ),
                  ),
                ],
              ),
            ),
          ],
        ],
      ),
    );
  }

  IconData _getTierIcon(String iconName) {
    switch (iconName) {
      case 'diamond':
        return Icons.diamond;
      case 'star':
        return Icons.star;
      case 'verified':
        return Icons.verified;
      default:
        return Icons.workspace_premium;
    }
  }
}
```

**Task 3.3: Reward Card Widget (2 hours)**

```dart
// lib/features/loyalty/presentation/widgets/reward_card.dart

import 'package:flutter/material.dart';
import 'package:smart_dairy_customer/core/theme/app_colors.dart';
import 'package:smart_dairy_customer/features/loyalty/data/models/loyalty_models.dart';

class RewardCard extends StatelessWidget {
  final LoyaltyReward reward;
  final VoidCallback? onRedeem;

  const RewardCard({
    super.key,
    required this.reward,
    this.onRedeem,
  });

  @override
  Widget build(BuildContext context) {
    final canRedeem = reward.canRedeem;

    return Card(
      clipBehavior: Clip.antiAlias,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Image
          Container(
            height: 80,
            width: double.infinity,
            decoration: BoxDecoration(
              color: AppColors.primary.withOpacity(0.1),
            ),
            child: reward.image != null
                ? Image.network(
                    reward.image!,
                    fit: BoxFit.cover,
                    errorBuilder: (_, __, ___) => _buildPlaceholder(),
                  )
                : _buildPlaceholder(),
          ),

          // Content
          Expanded(
            child: Padding(
              padding: const EdgeInsets.all(12),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    reward.name,
                    style: const TextStyle(
                      fontWeight: FontWeight.bold,
                      fontSize: 14,
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const Spacer(),
                  Row(
                    children: [
                      const Icon(Icons.stars, size: 16, color: Colors.amber),
                      const SizedBox(width: 4),
                      Text(
                        '${reward.pointsRequired}',
                        style: const TextStyle(
                          fontWeight: FontWeight.bold,
                          color: AppColors.primary,
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 8),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: onRedeem,
                      style: ElevatedButton.styleFrom(
                        backgroundColor:
                            canRedeem ? AppColors.primary : Colors.grey[300],
                        foregroundColor:
                            canRedeem ? Colors.white : Colors.grey[600],
                        padding: const EdgeInsets.symmetric(vertical: 8),
                      ),
                      child: Text(
                        canRedeem ? 'Redeem' : 'Not enough points',
                        style: const TextStyle(fontSize: 12),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPlaceholder() {
    return Center(
      child: Icon(
        _getRewardTypeIcon(),
        size: 40,
        color: AppColors.primary.withOpacity(0.5),
      ),
    );
  }

  IconData _getRewardTypeIcon() {
    switch (reward.rewardType) {
      case 'discount':
        return Icons.local_offer;
      case 'product':
        return Icons.card_giftcard;
      case 'cashback':
        return Icons.account_balance_wallet;
      default:
        return Icons.stars;
    }
  }
}
```

#### End-of-Day 294 Deliverables

- [ ] Loyalty balance API endpoint
- [ ] Loyalty history API endpoint
- [ ] Rewards listing API endpoint
- [ ] Points redemption API endpoint
- [ ] Loyalty points history model
- [ ] Loyalty redemption model
- [ ] Loyalty repository implementation
- [ ] Loyalty models with Freezed
- [ ] Loyalty provider with state management
- [ ] Loyalty points screen
- [ ] Points balance card widget
- [ ] Tier progress card widget
- [ ] Reward card widget

---

### Day 295 (Sprint Day 5): Profile Management Backend

#### Objectives
- Build comprehensive profile API endpoints
- Implement profile image upload
- Create address management APIs

#### Dev 1 - Backend Lead (8 hours)

**Task 1.1: Profile API Controller (4 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/profile_controller.py

from odoo import http
from odoo.http import request
import base64
import re

class ProfileController(http.Controller):

    @http.route('/api/v1/profile', type='json', auth='jwt', methods=['GET'], csrf=False)
    def get_profile(self, **kwargs):
        """Get current user profile."""
        try:
            current_user = request.env.user
            partner = current_user.partner_id

            return {
                'success': True,
                'data': self._serialize_profile(partner)
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/profile', type='json', auth='jwt', methods=['PUT'], csrf=False)
    def update_profile(self, **kwargs):
        """Update user profile."""
        try:
            current_user = request.env.user
            partner = current_user.partner_id

            # Allowed fields to update
            allowed_fields = {
                'name': 'name',
                'email': 'email',
                'phone': 'phone',
                'mobile': 'mobile',
                'street': 'street',
                'street2': 'street2',
                'city': 'city',
                'zip': 'zip',
                'gender': 'gender',
                'birthdate': 'birthdate',
            }

            updates = {}
            for param_key, field_name in allowed_fields.items():
                if param_key in kwargs:
                    value = kwargs[param_key]
                    # Validation
                    if param_key == 'email' and value:
                        if not self._validate_email(value):
                            return {'success': False, 'error': 'Invalid email format'}
                    if param_key == 'phone' and value:
                        if not self._validate_phone(value):
                            return {'success': False, 'error': 'Invalid phone format'}
                    updates[field_name] = value

            if updates:
                partner.sudo().write(updates)

            return {
                'success': True,
                'data': self._serialize_profile(partner)
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/profile/image', type='json', auth='jwt', methods=['POST'], csrf=False)
    def upload_profile_image(self, **kwargs):
        """Upload profile image."""
        try:
            current_user = request.env.user
            partner = current_user.partner_id

            image_data = kwargs.get('image')  # Base64 encoded image

            if not image_data:
                return {'success': False, 'error': 'Image data required'}

            # Validate image size (max 5MB)
            if len(image_data) > 5 * 1024 * 1024:
                return {'success': False, 'error': 'Image too large (max 5MB)'}

            # Update profile image
            partner.sudo().write({'image_1920': image_data})

            return {
                'success': True,
                'data': {
                    'image_url': f"/web/image/res.partner/{partner.id}/image_256?unique={partner.write_date.timestamp()}"
                }
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/profile/image', type='json', auth='jwt', methods=['DELETE'], csrf=False)
    def delete_profile_image(self, **kwargs):
        """Remove profile image."""
        try:
            current_user = request.env.user
            partner = current_user.partner_id

            partner.sudo().write({'image_1920': False})

            return {'success': True, 'message': 'Profile image removed'}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/profile/change-password', type='json', auth='jwt', methods=['POST'], csrf=False)
    def change_password(self, **kwargs):
        """Change user password."""
        try:
            current_user = request.env.user

            current_password = kwargs.get('current_password')
            new_password = kwargs.get('new_password')
            confirm_password = kwargs.get('confirm_password')

            if not all([current_password, new_password, confirm_password]):
                return {'success': False, 'error': 'All fields are required'}

            if new_password != confirm_password:
                return {'success': False, 'error': 'Passwords do not match'}

            if len(new_password) < 8:
                return {'success': False, 'error': 'Password must be at least 8 characters'}

            # Verify current password
            try:
                request.env['res.users'].sudo().check(
                    request.env.cr.dbname,
                    current_user.id,
                    current_password
                )
            except Exception:
                return {'success': False, 'error': 'Current password is incorrect'}

            # Change password
            current_user.sudo().write({'password': new_password})

            return {'success': True, 'message': 'Password changed successfully'}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    def _serialize_profile(self, partner):
        """Serialize partner profile."""
        return {
            'id': partner.id,
            'name': partner.name,
            'email': partner.email,
            'phone': partner.phone,
            'mobile': partner.mobile,
            'street': partner.street,
            'street2': partner.street2,
            'city': partner.city,
            'zip': partner.zip,
            'state': partner.state_id.name if partner.state_id else None,
            'country': partner.country_id.name if partner.country_id else None,
            'gender': partner.gender if hasattr(partner, 'gender') else None,
            'birthdate': partner.birthdate.isoformat() if hasattr(partner, 'birthdate') and partner.birthdate else None,
            'image_url': f"/web/image/res.partner/{partner.id}/image_256" if partner.image_256 else None,
            'created_date': partner.create_date.isoformat() if partner.create_date else None,
            'loyalty_points': self._get_loyalty_points(partner),
            'total_orders': self._get_total_orders(partner),
        }

    def _get_loyalty_points(self, partner):
        """Get loyalty points balance."""
        LoyaltyCard = request.env['loyalty.card'].sudo()
        card = LoyaltyCard.search([('partner_id', '=', partner.id)], limit=1)
        return card.points if card else 0

    def _get_total_orders(self, partner):
        """Get total completed orders."""
        SaleOrder = request.env['sale.order'].sudo()
        return SaleOrder.search_count([
            ('partner_id', '=', partner.id),
            ('state', '=', 'done')
        ])

    def _validate_email(self, email):
        """Validate email format."""
        pattern = r'^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$'
        return bool(re.match(pattern, email))

    def _validate_phone(self, phone):
        """Validate Bangladesh phone format."""
        # Remove spaces and dashes
        phone = re.sub(r'[\s-]', '', phone)
        # Bangladesh format: +880XXXXXXXXXX or 01XXXXXXXXX
        pattern = r'^(\+880|880|0)?1[3-9]\d{8}$'
        return bool(re.match(pattern, phone))
```

**Task 1.2: Address Management API (4 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/address_controller.py

from odoo import http
from odoo.http import request

class AddressController(http.Controller):

    @http.route('/api/v1/addresses', type='json', auth='jwt', methods=['GET'], csrf=False)
    def get_addresses(self, **kwargs):
        """Get all delivery addresses."""
        try:
            current_user = request.env.user
            partner = current_user.partner_id

            # Get child addresses (delivery addresses)
            addresses = request.env['res.partner'].sudo().search([
                ('parent_id', '=', partner.id),
                ('type', '=', 'delivery')
            ])

            # Include main address
            all_addresses = [self._serialize_address(partner, is_default=True)]

            for addr in addresses:
                all_addresses.append(self._serialize_address(addr))

            return {
                'success': True,
                'data': all_addresses
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/addresses', type='json', auth='jwt', methods=['POST'], csrf=False)
    def create_address(self, **kwargs):
        """Create a new delivery address."""
        try:
            current_user = request.env.user
            partner = current_user.partner_id

            # Required fields
            name = kwargs.get('name')
            street = kwargs.get('street')
            city = kwargs.get('city')

            if not all([name, street, city]):
                return {'success': False, 'error': 'Name, street, and city are required'}

            # Create address
            address_data = {
                'parent_id': partner.id,
                'type': 'delivery',
                'name': name,
                'street': street,
                'street2': kwargs.get('street2'),
                'city': city,
                'zip': kwargs.get('zip'),
                'phone': kwargs.get('phone'),
                'partner_latitude': kwargs.get('latitude'),
                'partner_longitude': kwargs.get('longitude'),
            }

            # Set state if provided
            state_name = kwargs.get('state')
            if state_name:
                state = request.env['res.country.state'].sudo().search([
                    ('name', 'ilike', state_name),
                    ('country_id.code', '=', 'BD')
                ], limit=1)
                if state:
                    address_data['state_id'] = state.id

            # Set country to Bangladesh
            bangladesh = request.env['res.country'].sudo().search([('code', '=', 'BD')], limit=1)
            if bangladesh:
                address_data['country_id'] = bangladesh.id

            new_address = request.env['res.partner'].sudo().create(address_data)

            return {
                'success': True,
                'data': self._serialize_address(new_address)
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/addresses/<int:address_id>', type='json', auth='jwt', methods=['PUT'], csrf=False)
    def update_address(self, address_id, **kwargs):
        """Update a delivery address."""
        try:
            current_user = request.env.user
            partner = current_user.partner_id

            # Find address (must belong to user)
            address = request.env['res.partner'].sudo().search([
                ('id', '=', address_id),
                '|',
                ('id', '=', partner.id),
                ('parent_id', '=', partner.id)
            ], limit=1)

            if not address:
                return {'success': False, 'error': 'Address not found'}

            # Update allowed fields
            update_data = {}
            allowed_fields = ['name', 'street', 'street2', 'city', 'zip', 'phone']

            for field in allowed_fields:
                if field in kwargs:
                    update_data[field] = kwargs[field]

            # Handle coordinates
            if 'latitude' in kwargs:
                update_data['partner_latitude'] = kwargs['latitude']
            if 'longitude' in kwargs:
                update_data['partner_longitude'] = kwargs['longitude']

            if update_data:
                address.write(update_data)

            return {
                'success': True,
                'data': self._serialize_address(address)
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/addresses/<int:address_id>', type='json', auth='jwt', methods=['DELETE'], csrf=False)
    def delete_address(self, address_id, **kwargs):
        """Delete a delivery address."""
        try:
            current_user = request.env.user
            partner = current_user.partner_id

            # Find address (must be child address, not main)
            address = request.env['res.partner'].sudo().search([
                ('id', '=', address_id),
                ('parent_id', '=', partner.id),
                ('type', '=', 'delivery')
            ], limit=1)

            if not address:
                return {'success': False, 'error': 'Address not found or cannot be deleted'}

            # Check if address is used in pending orders
            pending_orders = request.env['sale.order'].sudo().search_count([
                ('partner_shipping_id', '=', address_id),
                ('state', 'in', ['draft', 'sent', 'sale', 'processing'])
            ])

            if pending_orders > 0:
                return {'success': False, 'error': 'Cannot delete address with pending orders'}

            address.unlink()

            return {'success': True, 'message': 'Address deleted'}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/addresses/<int:address_id>/default', type='json', auth='jwt', methods=['POST'], csrf=False)
    def set_default_address(self, address_id, **kwargs):
        """Set address as default delivery address."""
        try:
            current_user = request.env.user
            partner = current_user.partner_id

            # Find address
            address = request.env['res.partner'].sudo().search([
                ('id', '=', address_id),
                '|',
                ('id', '=', partner.id),
                ('parent_id', '=', partner.id)
            ], limit=1)

            if not address:
                return {'success': False, 'error': 'Address not found'}

            # Update default shipping address
            partner.sudo().write({
                'default_shipping_id': address_id
            })

            return {'success': True, 'message': 'Default address updated'}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    def _serialize_address(self, address, is_default=False):
        """Serialize address for API response."""
        return {
            'id': address.id,
            'name': address.name,
            'street': address.street,
            'street2': address.street2,
            'city': address.city,
            'state': address.state_id.name if address.state_id else None,
            'zip': address.zip,
            'country': address.country_id.name if address.country_id else 'Bangladesh',
            'phone': address.phone,
            'latitude': address.partner_latitude,
            'longitude': address.partner_longitude,
            'is_default': is_default or (hasattr(address.parent_id, 'default_shipping_id') and
                                         address.parent_id.default_shipping_id.id == address.id),
            'type': address.type or 'contact',
        }
```

#### Dev 2 & Dev 3 - Profile & Settings Screens (8 hours each)

*Content continues with Flutter profile repository, models, providers, and UI screens for profile management, address management, and app settings...*

#### End-of-Day 295 Deliverables

- [ ] Profile GET/PUT API endpoints
- [ ] Profile image upload/delete endpoints
- [ ] Change password endpoint
- [ ] Address CRUD API endpoints
- [ ] Set default address endpoint
- [ ] Profile repository implementation
- [ ] Address repository implementation
- [ ] Profile provider
- [ ] Address provider

---

### Days 296-300 Summary

**Day 296**: Profile & Settings Flutter Screens
- Profile edit screen with form validation
- Profile image picker and cropper
- Address list and management screens
- Add/Edit address screens with GPS picker

**Day 297**: App Settings & Preferences
- Settings screen with theme, language options
- Account deletion flow
- Help & support section
- Privacy policy and terms screens

**Day 298**: Final Integration & Polish
- Deep linking implementation
- App shortcuts
- Widget completion
- Error handling improvements

**Day 299**: Beta Release Preparation
- App store metadata preparation
- Screenshots generation
- Privacy policy compliance
- TestFlight submission
- Play Store Internal Testing submission

**Day 300**: Documentation & Handoff
- Customer App user guide
- API documentation update
- Code review and cleanup
- Beta tester onboarding
- Milestone completion checklist

---

## 4. Technical Specifications

### 4.1 API Endpoints Summary

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/orders` | GET | List orders with pagination |
| `/api/v1/orders/stats` | GET | Get order statistics |
| `/api/v1/orders/{id}` | GET | Get order detail |
| `/api/v1/orders/{id}/tracking` | GET | Get real-time tracking |
| `/api/v1/orders/{id}/cancel` | POST | Cancel order |
| `/api/v1/orders/{id}/rate` | POST | Submit rating |
| `/api/v1/orders/{id}/reorder` | POST | Reorder items |
| `/api/v1/notifications/register` | POST | Register FCM token |
| `/api/v1/notifications/preferences` | GET/PUT | Notification preferences |
| `/api/v1/notifications/history` | GET | Notification history |
| `/api/v1/loyalty/balance` | GET | Get points balance |
| `/api/v1/loyalty/history` | GET | Points transaction history |
| `/api/v1/loyalty/rewards` | GET | Available rewards |
| `/api/v1/loyalty/redeem` | POST | Redeem points |
| `/api/v1/profile` | GET/PUT | User profile |
| `/api/v1/profile/image` | POST/DELETE | Profile image |
| `/api/v1/profile/change-password` | POST | Change password |
| `/api/v1/addresses` | GET/POST | Address management |
| `/api/v1/addresses/{id}` | PUT/DELETE | Update/delete address |

### 4.2 Flutter Screens Summary

| Screen | Description |
|--------|-------------|
| `OrderHistoryScreen` | List all orders with filtering |
| `OrderDetailScreen` | Complete order information |
| `OrderTrackingScreen` | Real-time map tracking |
| `NotificationSettingsScreen` | Push notification preferences |
| `NotificationHistoryScreen` | Past notifications |
| `LoyaltyScreen` | Points balance and rewards |
| `ProfileScreen` | User profile management |
| `AddressListScreen` | Delivery addresses |
| `AddAddressScreen` | Add new address |
| `SettingsScreen` | App settings |

---

## 5. Testing & Validation

### 5.1 Unit Tests

- Order repository tests
- Notification service tests
- Loyalty calculations tests
- Profile validation tests

### 5.2 Integration Tests

- Order flow end-to-end
- Push notification delivery
- Points earning and redemption
- Profile update persistence

### 5.3 UI Tests

- Order list scrolling and filtering
- Order tracking map rendering
- Profile form validation
- Settings persistence

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| FCM delivery failures | Medium | Medium | Implement retry logic, local notifications fallback |
| App store rejection | Medium | High | Pre-submission review, compliance checks |
| GPS tracking battery drain | Medium | Medium | Smart polling, geofencing |
| Offline order history | Low | Medium | Local caching with sync |

---

## 7. Dependencies & Handoffs

### 7.1 Dependencies

- Milestone 54: Cart and checkout complete
- Milestone 52: Authentication working
- Milestone 51: Firebase configured

### 7.2 Handoffs to Milestone 56

- [ ] Customer App beta deployed
- [ ] FCM infrastructure ready for Field Sales App
- [ ] Profile/address patterns established
- [ ] API documentation updated

---

## Document Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-03 | Technical Team | Initial document creation |

---

*End of Milestone 55 Document*
