# Milestone 78: Courier API Integration

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P8-M78-v1.0 |
| **Milestone** | 78 - Courier API Integration |
| **Phase** | Phase 8: Operations - Payment & Logistics |
| **Days** | 521-530 |
| **Duration** | 10 Working Days |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Technical Architecture Team |
| **Last Updated** | 2025-01-15 |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Implement third-party courier service integrations with Pathao Courier, RedX, and Paperfly APIs including automatic courier selection, shipping label generation, tracking number synchronization, webhook handlers, rate comparison, and courier management dashboard.**

### 1.2 Scope Summary

| Area | Included |
|------|----------|
| Pathao Integration | Full API integration |
| RedX Integration | Full API integration |
| Paperfly Integration | Full API integration |
| Auto-Selection | Smart courier selection |
| Label Generation | PDF shipping labels |
| Tracking Sync | Real-time status sync |
| Webhooks | Courier status callbacks |
| Rate Comparison | Best rate finder |

---

## 2. Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| O1 | Courier API coverage | 100% endpoints |
| O2 | Shipment creation success | > 98% |
| O3 | Tracking sync accuracy | 100% |
| O4 | Label generation time | < 2 seconds |
| O5 | Webhook processing | 99.9% |
| O6 | Rate optimization | 15% cost savings |

---

## 3. Key Deliverables

| # | Deliverable | Owner | Format | Due Day |
|---|-------------|-------|--------|---------|
| D1 | Courier provider abstraction | Dev 1 | Python | Day 522 |
| D2 | Pathao API integration | Dev 1 | Python | Day 524 |
| D3 | RedX API integration | Dev 1 | Python | Day 526 |
| D4 | Paperfly API integration | Dev 2 | Python | Day 525 |
| D5 | Auto-selection algorithm | Dev 2 | Python | Day 527 |
| D6 | Label generator | Dev 2 | Python | Day 528 |
| D7 | Webhook handlers | Dev 2 | Python | Day 526 |
| D8 | Courier dashboard UI | Dev 3 | OWL/JS | Day 528 |
| D9 | Shipment tracking UI | Dev 3 | OWL/JS | Day 529 |
| D10 | Integration tests | All | Python | Day 530 |

---

## 4. Day-by-Day Development Plan

### Day 521-524: Courier Abstraction & Pathao

#### Dev 1 (Backend Lead)

**Courier Provider Abstraction**

```python
# File: smart_dairy_courier/models/courier_provider.py

from odoo import models, fields, api
from abc import abstractmethod
import logging

_logger = logging.getLogger(__name__)


class CourierProvider(models.Model):
    _name = 'courier.provider'
    _description = 'Courier Provider'

    name = fields.Char(string='Name', required=True)
    code = fields.Selection([
        ('pathao', 'Pathao Courier'),
        ('redx', 'RedX'),
        ('paperfly', 'Paperfly'),
    ], string='Provider Code', required=True)
    active = fields.Boolean(default=True)

    # API Configuration
    api_base_url = fields.Char(string='API Base URL')
    api_key = fields.Char(string='API Key', groups='base.group_system')
    api_secret = fields.Char(string='API Secret', groups='base.group_system')
    store_id = fields.Char(string='Store ID')
    merchant_id = fields.Char(string='Merchant ID')

    # Mode
    mode = fields.Selection([
        ('sandbox', 'Sandbox'),
        ('production', 'Production'),
    ], string='Mode', default='sandbox')

    # Settings
    default_weight = fields.Float(string='Default Weight (kg)', default=0.5)
    max_cod_amount = fields.Monetary(
        string='Max COD Amount',
        currency_field='currency_id'
    )
    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.ref('base.BDT')
    )

    # Zone Mapping
    zone_mapping_ids = fields.One2many(
        'courier.zone.mapping',
        'provider_id',
        string='Zone Mappings'
    )

    def get_service(self):
        """Get the courier service implementation."""
        service_map = {
            'pathao': 'pathao.courier.service',
            'redx': 'redx.courier.service',
            'paperfly': 'paperfly.courier.service',
        }
        service_name = service_map.get(self.code)
        if service_name:
            return self.env[service_name]
        raise NotImplementedError(f"No service for provider: {self.code}")


class BaseCourierService(models.AbstractModel):
    _name = 'base.courier.service'
    _description = 'Base Courier Service'

    @api.model
    def create_shipment(self, provider, order):
        """Create shipment with courier. Override in implementations."""
        raise NotImplementedError()

    @api.model
    def get_tracking(self, provider, tracking_number):
        """Get tracking status. Override in implementations."""
        raise NotImplementedError()

    @api.model
    def cancel_shipment(self, provider, tracking_number):
        """Cancel shipment. Override in implementations."""
        raise NotImplementedError()

    @api.model
    def get_rates(self, provider, origin, destination, weight, cod_amount=0):
        """Get shipping rates. Override in implementations."""
        raise NotImplementedError()

    def _log_api_call(self, provider, endpoint, request, response, duration):
        """Log API call for debugging."""
        self.env['courier.api.log'].create({
            'provider_id': provider.id,
            'endpoint': endpoint,
            'request_data': str(request),
            'response_data': str(response),
            'duration_ms': duration,
        })
```

**Pathao Courier Service**

```python
# File: smart_dairy_courier/services/pathao_service.py

from odoo import models, api
import requests
import time
import logging

_logger = logging.getLogger(__name__)


class PathaoCourierService(models.AbstractModel):
    _name = 'pathao.courier.service'
    _inherit = 'base.courier.service'
    _description = 'Pathao Courier Service'

    @api.model
    def _get_token(self, provider):
        """Get Pathao access token."""
        url = f"{provider.api_base_url}/aladdin/api/v1/issue-token"
        payload = {
            'client_id': provider.api_key,
            'client_secret': provider.api_secret,
            'grant_type': 'client_credentials',
        }

        response = requests.post(url, json=payload, timeout=30)
        data = response.json()

        if data.get('type') == 'success':
            return data['data']['access_token']
        raise Exception(data.get('message', 'Token fetch failed'))

    @api.model
    def create_shipment(self, provider, order):
        """Create Pathao shipment."""
        token = self._get_token(provider)
        url = f"{provider.api_base_url}/aladdin/api/v1/orders"

        headers = {
            'Authorization': f'Bearer {token}',
            'Content-Type': 'application/json',
        }

        # Map delivery zone to Pathao zone
        zone_mapping = provider.zone_mapping_ids.filtered(
            lambda z: z.internal_zone_id.id == order.delivery_zone_id.id
        )[:1]

        payload = {
            'store_id': int(provider.store_id),
            'merchant_order_id': order.name,
            'recipient_name': order.partner_shipping_id.name,
            'recipient_phone': order.partner_shipping_id.phone,
            'recipient_address': order.partner_shipping_id.contact_address,
            'recipient_city': zone_mapping.courier_city_id or 1,  # Dhaka default
            'recipient_zone': zone_mapping.courier_zone_id or 1,
            'recipient_area': zone_mapping.courier_area_id or 1,
            'delivery_type': 48,  # 48 hours delivery
            'item_type': 2,  # Parcel
            'special_instruction': order.note or '',
            'item_quantity': len(order.order_line),
            'item_weight': order.total_weight or provider.default_weight,
            'amount_to_collect': order.amount_total if order.payment_method == 'cod' else 0,
            'item_description': 'Smart Dairy Products',
        }

        start_time = time.time()
        response = requests.post(url, json=payload, headers=headers, timeout=30)
        duration = int((time.time() - start_time) * 1000)

        data = response.json()
        self._log_api_call(provider, 'create_order', payload, data, duration)

        if data.get('type') == 'success':
            return {
                'success': True,
                'tracking_number': data['data']['consignment_id'],
                'label_url': data['data'].get('invoice_url'),
                'courier_order_id': data['data']['order_id'],
            }

        return {
            'success': False,
            'error': data.get('message', 'Shipment creation failed'),
        }

    @api.model
    def get_tracking(self, provider, tracking_number):
        """Get Pathao tracking status."""
        token = self._get_token(provider)
        url = f"{provider.api_base_url}/aladdin/api/v1/orders/{tracking_number}"

        headers = {'Authorization': f'Bearer {token}'}
        response = requests.get(url, headers=headers, timeout=30)
        data = response.json()

        if data.get('type') == 'success':
            order_data = data['data']
            return {
                'success': True,
                'status': self._map_pathao_status(order_data['order_status']),
                'status_description': order_data.get('order_status_slug'),
                'current_location': order_data.get('current_hub'),
                'delivered_at': order_data.get('delivered_at'),
                'raw_data': order_data,
            }

        return {'success': False, 'error': data.get('message')}

    def _map_pathao_status(self, pathao_status):
        """Map Pathao status to internal status."""
        status_map = {
            'Pending': 'pending',
            'Picked': 'picked_up',
            'In Transit': 'in_transit',
            'Delivered': 'delivered',
            'Returned': 'returned',
            'Cancelled': 'cancelled',
        }
        return status_map.get(pathao_status, 'unknown')

    @api.model
    def get_rates(self, provider, origin, destination, weight, cod_amount=0):
        """Get Pathao shipping rates."""
        token = self._get_token(provider)
        url = f"{provider.api_base_url}/aladdin/api/v1/delivery/price-plan"

        headers = {'Authorization': f'Bearer {token}'}
        params = {
            'store_id': provider.store_id,
            'item_type': 2,
            'delivery_type': 48,
            'item_weight': weight,
            'recipient_city': destination.get('city_id', 1),
            'recipient_zone': destination.get('zone_id', 1),
        }

        response = requests.get(url, params=params, headers=headers, timeout=30)
        data = response.json()

        if data.get('type') == 'success':
            return {
                'success': True,
                'base_rate': data['data']['price'],
                'cod_charge': data['data'].get('cod_charge', 0),
                'total': data['data']['price'] + (data['data'].get('cod_charge', 0) if cod_amount else 0),
            }

        return {'success': False, 'error': data.get('message')}

    @api.model
    def cancel_shipment(self, provider, tracking_number):
        """Cancel Pathao shipment."""
        token = self._get_token(provider)
        url = f"{provider.api_base_url}/aladdin/api/v1/orders/{tracking_number}/cancel"

        headers = {'Authorization': f'Bearer {token}'}
        response = requests.post(url, headers=headers, timeout=30)
        data = response.json()

        return {
            'success': data.get('type') == 'success',
            'message': data.get('message'),
        }
```

**RedX Courier Service**

```python
# File: smart_dairy_courier/services/redx_service.py

from odoo import models, api
import requests
import time
import logging

_logger = logging.getLogger(__name__)


class RedXCourierService(models.AbstractModel):
    _name = 'redx.courier.service'
    _inherit = 'base.courier.service'
    _description = 'RedX Courier Service'

    @api.model
    def create_shipment(self, provider, order):
        """Create RedX shipment."""
        url = f"{provider.api_base_url}/v1/parcel"

        headers = {
            'API-ACCESS-TOKEN': f'Bearer {provider.api_key}',
            'Content-Type': 'application/json',
        }

        payload = {
            'customer_name': order.partner_shipping_id.name,
            'customer_phone': order.partner_shipping_id.phone,
            'delivery_area': order.delivery_zone_id.code,
            'delivery_area_id': self._get_redx_area_id(provider, order.delivery_zone_id),
            'customer_address': order.partner_shipping_id.contact_address,
            'merchant_invoice_id': order.name,
            'cash_collection_amount': str(order.amount_total) if order.payment_method == 'cod' else '0',
            'parcel_weight': order.total_weight or provider.default_weight,
            'instruction': order.note or '',
            'value': str(order.amount_total),
            'parcel_details_json': [{
                'name': 'Smart Dairy Products',
                'category': 'Dairy',
                'value': str(order.amount_total),
            }],
        }

        start_time = time.time()
        response = requests.post(url, json=payload, headers=headers, timeout=30)
        duration = int((time.time() - start_time) * 1000)

        data = response.json()
        self._log_api_call(provider, 'create_parcel', payload, data, duration)

        if response.status_code == 200:
            return {
                'success': True,
                'tracking_number': data.get('tracking_id'),
                'courier_order_id': data.get('parcel_id'),
            }

        return {
            'success': False,
            'error': data.get('message', 'Shipment creation failed'),
        }

    @api.model
    def get_tracking(self, provider, tracking_number):
        """Get RedX tracking status."""
        url = f"{provider.api_base_url}/v1/parcel/track/{tracking_number}"

        headers = {'API-ACCESS-TOKEN': f'Bearer {provider.api_key}'}
        response = requests.get(url, headers=headers, timeout=30)
        data = response.json()

        if response.status_code == 200:
            return {
                'success': True,
                'status': self._map_redx_status(data.get('status')),
                'status_description': data.get('status'),
                'tracking_events': data.get('tracking_history', []),
            }

        return {'success': False, 'error': data.get('message')}

    def _map_redx_status(self, redx_status):
        """Map RedX status to internal status."""
        status_map = {
            'Pending': 'pending',
            'Pickup Pending': 'pending',
            'Picked Up': 'picked_up',
            'In Transit': 'in_transit',
            'Delivered': 'delivered',
            'Return': 'returned',
        }
        return status_map.get(redx_status, 'unknown')

    def _get_redx_area_id(self, provider, zone):
        """Get RedX area ID from zone mapping."""
        mapping = provider.zone_mapping_ids.filtered(
            lambda z: z.internal_zone_id.id == zone.id
        )[:1]
        return mapping.courier_area_id if mapping else 1

    @api.model
    def get_rates(self, provider, origin, destination, weight, cod_amount=0):
        """Get RedX shipping rates."""
        url = f"{provider.api_base_url}/v1/areas"

        headers = {'API-ACCESS-TOKEN': f'Bearer {provider.api_key}'}
        params = {'area_id': destination.get('area_id')}

        response = requests.get(url, params=params, headers=headers, timeout=30)
        data = response.json()

        if response.status_code == 200:
            area = data.get('area', {})
            return {
                'success': True,
                'base_rate': area.get('home_delivery_charge', 60),
                'cod_charge': area.get('cod_charge', 0),
                'total': area.get('home_delivery_charge', 60),
            }

        return {'success': False, 'error': 'Rate lookup failed'}
```

#### Dev 2 (Full-Stack)

**Auto-Selection Algorithm**

```python
# File: smart_dairy_courier/services/courier_selector.py

from odoo import models, api
import logging

_logger = logging.getLogger(__name__)


class CourierSelector(models.AbstractModel):
    _name = 'courier.selector'
    _description = 'Courier Auto-Selection Service'

    @api.model
    def select_best_courier(self, order, criteria='cost'):
        """
        Select best courier for an order.

        Args:
            order: sale.order record
            criteria: Selection criteria ('cost', 'speed', 'rating')

        Returns:
            courier.provider record
        """
        providers = self.env['courier.provider'].search([
            ('active', '=', True),
        ])

        if not providers:
            return None

        candidates = []

        for provider in providers:
            try:
                service = provider.get_service()

                # Get rates
                rate_result = service.get_rates(
                    provider,
                    origin={'city_id': 1},  # Depot
                    destination={
                        'city_id': self._get_city_id(provider, order.delivery_zone_id),
                        'area_id': self._get_area_id(provider, order.delivery_zone_id),
                    },
                    weight=order.total_weight or provider.default_weight,
                    cod_amount=order.amount_total if order.payment_method == 'cod' else 0,
                )

                if rate_result.get('success'):
                    candidates.append({
                        'provider': provider,
                        'cost': rate_result['total'],
                        'delivery_time': self._get_estimated_time(provider, order),
                        'rating': provider.avg_rating or 3.0,
                    })

            except Exception as e:
                _logger.warning(f"Failed to get rate from {provider.name}: {str(e)}")
                continue

        if not candidates:
            # Fallback to first active provider
            return providers[0]

        # Sort by criteria
        if criteria == 'cost':
            candidates.sort(key=lambda x: x['cost'])
        elif criteria == 'speed':
            candidates.sort(key=lambda x: x['delivery_time'])
        elif criteria == 'rating':
            candidates.sort(key=lambda x: x['rating'], reverse=True)

        return candidates[0]['provider']

    def _get_city_id(self, provider, zone):
        """Get courier city ID from zone mapping."""
        mapping = provider.zone_mapping_ids.filtered(
            lambda z: z.internal_zone_id.id == zone.id
        )[:1]
        return mapping.courier_city_id if mapping else 1

    def _get_area_id(self, provider, zone):
        """Get courier area ID from zone mapping."""
        mapping = provider.zone_mapping_ids.filtered(
            lambda z: z.internal_zone_id.id == zone.id
        )[:1]
        return mapping.courier_area_id if mapping else 1

    def _get_estimated_time(self, provider, order):
        """Estimate delivery time in hours."""
        zone = order.delivery_zone_id
        if zone:
            # Use zone's delivery days
            return zone.delivery_days * 24

        # Default by provider
        default_times = {
            'pathao': 48,
            'redx': 72,
            'paperfly': 48,
        }
        return default_times.get(provider.code, 48)


class CourierShipment(models.Model):
    _name = 'courier.shipment'
    _description = 'Courier Shipment'
    _order = 'create_date desc'

    name = fields.Char(string='Reference', readonly=True)
    sale_order_id = fields.Many2one('sale.order', string='Sales Order')
    provider_id = fields.Many2one('courier.provider', string='Courier')

    tracking_number = fields.Char(string='Tracking Number', index=True)
    courier_order_id = fields.Char(string='Courier Order ID')
    label_url = fields.Char(string='Label URL')
    label_data = fields.Binary(string='Label PDF')

    # Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('created', 'Created'),
        ('picked_up', 'Picked Up'),
        ('in_transit', 'In Transit'),
        ('out_for_delivery', 'Out for Delivery'),
        ('delivered', 'Delivered'),
        ('returned', 'Returned'),
        ('cancelled', 'Cancelled'),
    ], string='Status', default='draft')

    # Tracking
    last_tracking_update = fields.Datetime(string='Last Tracking Update')
    tracking_events = fields.Text(string='Tracking Events')

    # COD
    cod_amount = fields.Monetary(string='COD Amount', currency_field='currency_id')
    cod_collected = fields.Boolean(string='COD Collected', default=False)
    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.ref('base.BDT'))

    # Costs
    shipping_cost = fields.Monetary(string='Shipping Cost', currency_field='currency_id')
    cod_charge = fields.Monetary(string='COD Charge', currency_field='currency_id')

    def action_create_shipment(self):
        """Create shipment with courier."""
        self.ensure_one()
        service = self.provider_id.get_service()
        result = service.create_shipment(self.provider_id, self.sale_order_id)

        if result.get('success'):
            self.write({
                'state': 'created',
                'tracking_number': result['tracking_number'],
                'courier_order_id': result.get('courier_order_id'),
                'label_url': result.get('label_url'),
            })
            return True

        raise Exception(result.get('error', 'Failed to create shipment'))

    def action_refresh_tracking(self):
        """Refresh tracking status from courier."""
        self.ensure_one()
        if not self.tracking_number:
            return

        service = self.provider_id.get_service()
        result = service.get_tracking(self.provider_id, self.tracking_number)

        if result.get('success'):
            self.write({
                'state': result['status'],
                'last_tracking_update': fields.Datetime.now(),
                'tracking_events': str(result.get('tracking_events', [])),
            })
```

#### Dev 3 (Frontend/Mobile Lead)

**Courier Dashboard**

```javascript
/** @odoo-module **/
// File: smart_dairy_courier/static/src/js/courier_dashboard.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class CourierDashboard extends Component {
    static template = "smart_dairy_courier.Dashboard";

    setup() {
        this.rpc = useService("rpc");
        this.action = useService("action");

        this.state = useState({
            loading: true,
            summary: {},
            providerStats: [],
            recentShipments: [],
            pendingPickup: [],
        });

        onWillStart(() => this.loadData());
    }

    async loadData() {
        try {
            const data = await this.rpc('/courier/dashboard/data');
            this.state.summary = data.summary;
            this.state.providerStats = data.provider_stats;
            this.state.recentShipments = data.recent_shipments;
            this.state.pendingPickup = data.pending_pickup;
        } finally {
            this.state.loading = false;
        }
    }

    async createShipment(orderId) {
        const result = await this.rpc('/courier/shipment/create', {
            order_id: orderId,
            auto_select: true,
        });

        if (result.success) {
            this.loadData();
        }
    }

    viewShipment(shipmentId) {
        this.action.doAction({
            type: 'ir.actions.act_window',
            res_model: 'courier.shipment',
            res_id: shipmentId,
            views: [[false, 'form']],
        });
    }

    getStatusBadgeClass(state) {
        const classes = {
            created: 'bg-info',
            picked_up: 'bg-primary',
            in_transit: 'bg-warning',
            delivered: 'bg-success',
            returned: 'bg-danger',
        };
        return classes[state] || 'bg-secondary';
    }
}
```

---

## 5. Technical Specifications

### 5.1 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/courier/shipment/create` | POST | Create new shipment |
| `/courier/shipment/<id>/track` | GET | Get tracking info |
| `/courier/shipment/<id>/label` | GET | Download label |
| `/courier/rates/compare` | POST | Compare rates |
| `/courier/webhook/<provider>` | POST | Webhook handler |

### 5.2 Supported Couriers

| Courier | API Version | Features |
|---------|-------------|----------|
| Pathao | v1 | Full CRUD, tracking, labels |
| RedX | v1 | Shipments, tracking |
| Paperfly | v1 | Shipments, tracking, COD |

---

## 6. Database Schema

```sql
CREATE TABLE courier_provider (
    id SERIAL PRIMARY KEY,
    name VARCHAR(128),
    code VARCHAR(20),
    api_base_url VARCHAR(256),
    api_key VARCHAR(256),
    store_id VARCHAR(64),
    mode VARCHAR(20) DEFAULT 'sandbox',
    active BOOLEAN DEFAULT TRUE
);

CREATE TABLE courier_shipment (
    id SERIAL PRIMARY KEY,
    sale_order_id INTEGER REFERENCES sale_order(id),
    provider_id INTEGER REFERENCES courier_provider(id),
    tracking_number VARCHAR(64),
    state VARCHAR(20) DEFAULT 'draft',
    cod_amount DECIMAL(10, 2),
    shipping_cost DECIMAL(10, 2),
    create_date TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_cs_tracking ON courier_shipment(tracking_number);
CREATE INDEX idx_cs_state ON courier_shipment(state);
```

---

## 7. Sign-off Checklist

- [ ] Pathao integration complete
- [ ] RedX integration complete
- [ ] Paperfly integration complete
- [ ] Auto-selection working
- [ ] Label generation functional
- [ ] Tracking sync accurate
- [ ] Webhook handlers working
- [ ] Rate comparison functional

---

**Document End**

*Milestone 78: Courier API Integration*
*Days 521-530 | Phase 8: Operations - Payment & Logistics*
*Smart Dairy Digital Smart Portal + ERP System*
