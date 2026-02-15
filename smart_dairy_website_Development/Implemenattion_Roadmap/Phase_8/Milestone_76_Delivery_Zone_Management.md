# Milestone 76: Delivery Zone Management

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P8-M76-v1.0 |
| **Milestone** | 76 - Delivery Zone Management |
| **Phase** | Phase 8: Operations - Payment & Logistics |
| **Days** | 501-510 |
| **Duration** | 10 Working Days |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Technical Architecture Team |
| **Last Updated** | 2025-01-15 |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Implement comprehensive delivery zone management system with geo-polygon zone definition, zone-based delivery fees, minimum order amounts, delivery time estimation, express delivery configuration, zone restriction management, and admin zone management interface.**

### 1.2 Scope Summary

| Area | Included |
|------|----------|
| Zone Definition | Polygon-based geo-fencing |
| Delivery Fees | Zone-based fee calculation |
| Order Minimums | Minimum order by zone |
| Time Estimation | Delivery time by zone |
| Express Delivery | Express zone settings |
| Zone Restrictions | Product/time restrictions |
| Checkout Integration | Zone selection in checkout |
| Admin Interface | Zone management dashboard |

---

## 2. Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| O1 | Zone coverage | 100% serviceable areas |
| O2 | Address validation | > 99% accuracy |
| O3 | Fee calculation | 100% accurate |
| O4 | Time estimation | ±30 min accuracy |
| O5 | Zone lookup speed | < 200ms |
| O6 | Admin usability | Zone creation < 5 min |

---

## 3. Key Deliverables

| # | Deliverable | Owner | Format | Due Day |
|---|-------------|-------|--------|---------|
| D1 | Delivery zone model | Dev 1 | Python/Odoo | Day 502 |
| D2 | Geo-polygon service | Dev 1 | Python | Day 503 |
| D3 | Zone fee calculator | Dev 1 | Python | Day 504 |
| D4 | Address geocoding service | Dev 2 | Python | Day 503 |
| D5 | Zone lookup API | Dev 2 | Python | Day 504 |
| D6 | Delivery time estimator | Dev 2 | Python | Day 505 |
| D7 | Zone selection checkout UI | Dev 3 | OWL/JS | Day 505 |
| D8 | Zone map editor | Dev 3 | JS/Leaflet | Day 507 |
| D9 | Admin zone dashboard | Dev 3 | OWL/JS | Day 508 |
| D10 | Integration tests | All | Python | Day 510 |

---

## 4. Day-by-Day Development Plan

### Day 501-502: Zone Model & Configuration

#### Dev 1 (Backend Lead) - 8 Hours

**Delivery Zone Model**

```python
# File: smart_dairy_delivery/models/delivery_zone.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError
import json
import logging

_logger = logging.getLogger(__name__)


class DeliveryZone(models.Model):
    _name = 'delivery.zone'
    _description = 'Delivery Zone'
    _order = 'sequence, name'

    name = fields.Char(string='Zone Name', required=True)
    code = fields.Char(string='Zone Code', required=True, index=True)
    sequence = fields.Integer(string='Sequence', default=10)
    active = fields.Boolean(default=True)

    # Geographic Definition
    zone_type = fields.Selection([
        ('polygon', 'Polygon Area'),
        ('radius', 'Radius from Point'),
        ('postcode', 'Postcode/ZIP'),
    ], string='Zone Type', default='polygon', required=True)

    geo_polygon = fields.Text(
        string='GeoJSON Polygon',
        help='GeoJSON format polygon coordinates'
    )
    center_latitude = fields.Float(string='Center Latitude', digits=(10, 7))
    center_longitude = fields.Float(string='Center Longitude', digits=(10, 7))
    radius_km = fields.Float(string='Radius (km)')
    postcodes = fields.Text(string='Postcodes', help='Comma-separated postcodes')

    # Parent Zone (for hierarchical zones)
    parent_id = fields.Many2one('delivery.zone', string='Parent Zone')
    child_ids = fields.One2many('delivery.zone', 'parent_id', string='Sub-zones')

    # Delivery Settings
    delivery_available = fields.Boolean(string='Delivery Available', default=True)
    delivery_fee = fields.Monetary(
        string='Delivery Fee',
        currency_field='currency_id',
        default=50.0
    )
    min_order_amount = fields.Monetary(
        string='Minimum Order',
        currency_field='currency_id',
        default=200.0
    )
    free_delivery_threshold = fields.Monetary(
        string='Free Delivery Above',
        currency_field='currency_id',
        default=1000.0
    )
    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.ref('base.BDT')
    )

    # Time Settings
    delivery_days = fields.Integer(string='Standard Delivery Days', default=1)
    cutoff_time = fields.Float(
        string='Order Cutoff Time',
        default=18.0,
        help='Orders after this time ship next day (24h format)'
    )

    # Express Delivery
    express_available = fields.Boolean(string='Express Available', default=False)
    express_fee = fields.Monetary(
        string='Express Fee',
        currency_field='currency_id',
        default=100.0
    )
    express_hours = fields.Integer(
        string='Express Delivery Hours',
        default=4,
        help='Delivery within X hours'
    )
    express_cutoff_time = fields.Float(
        string='Express Cutoff',
        default=14.0,
        help='Last order time for same-day express'
    )

    # COD Settings
    cod_available = fields.Boolean(string='COD Available', default=True)
    cod_fee = fields.Monetary(
        string='COD Fee',
        currency_field='currency_id'
    )

    # Restrictions
    restricted_products = fields.Many2many(
        'product.product',
        'delivery_zone_restricted_products_rel',
        string='Restricted Products'
    )
    restricted_categories = fields.Many2many(
        'product.category',
        'delivery_zone_restricted_categories_rel',
        string='Restricted Categories'
    )

    # Operating Hours
    operating_days = fields.Selection([
        ('all', 'All Days'),
        ('weekdays', 'Weekdays Only'),
        ('custom', 'Custom'),
    ], string='Operating Days', default='all')
    sunday = fields.Boolean(default=True)
    monday = fields.Boolean(default=True)
    tuesday = fields.Boolean(default=True)
    wednesday = fields.Boolean(default=True)
    thursday = fields.Boolean(default=True)
    friday = fields.Boolean(default=True)
    saturday = fields.Boolean(default=True)

    # Statistics
    total_orders = fields.Integer(
        string='Total Orders',
        compute='_compute_order_stats'
    )
    avg_order_value = fields.Monetary(
        string='Avg Order Value',
        currency_field='currency_id',
        compute='_compute_order_stats'
    )

    _sql_constraints = [
        ('unique_code', 'UNIQUE(code)', 'Zone code must be unique'),
    ]

    @api.depends('code')
    def _compute_order_stats(self):
        for zone in self:
            orders = self.env['sale.order'].search([
                ('delivery_zone_id', '=', zone.id),
                ('state', 'in', ['sale', 'done']),
            ])
            zone.total_orders = len(orders)
            zone.avg_order_value = (
                sum(orders.mapped('amount_total')) / len(orders)
                if orders else 0
            )

    def check_point_in_zone(self, latitude, longitude):
        """Check if a coordinate point is within this zone."""
        self.ensure_one()

        if self.zone_type == 'polygon':
            return self._check_point_in_polygon(latitude, longitude)
        elif self.zone_type == 'radius':
            return self._check_point_in_radius(latitude, longitude)
        elif self.zone_type == 'postcode':
            return False  # Postcode check handled separately

        return False

    def _check_point_in_polygon(self, lat, lng):
        """Check if point is inside polygon using ray casting."""
        if not self.geo_polygon:
            return False

        try:
            from shapely.geometry import Point, shape

            polygon_data = json.loads(self.geo_polygon)
            polygon = shape(polygon_data)
            point = Point(lng, lat)  # Note: shapely uses (lng, lat) order

            return polygon.contains(point)
        except Exception as e:
            _logger.error(f"Polygon check error: {str(e)}")
            return False

    def _check_point_in_radius(self, lat, lng):
        """Check if point is within radius of center."""
        if not self.center_latitude or not self.center_longitude:
            return False

        from math import radians, cos, sin, asin, sqrt

        # Haversine formula
        R = 6371  # Earth radius in km

        lat1, lon1 = radians(self.center_latitude), radians(self.center_longitude)
        lat2, lon2 = radians(lat), radians(lng)

        dlat = lat2 - lat1
        dlon = lon2 - lon1

        a = sin(dlat/2)**2 + cos(lat1) * cos(lat2) * sin(dlon/2)**2
        c = 2 * asin(sqrt(a))

        distance = R * c

        return distance <= self.radius_km

    def get_delivery_fee(self, order_amount):
        """Calculate delivery fee for an order amount."""
        self.ensure_one()

        if order_amount >= self.free_delivery_threshold:
            return 0

        return self.delivery_fee

    def get_estimated_delivery_date(self, order_datetime=None):
        """Get estimated delivery date for an order."""
        self.ensure_one()
        from datetime import datetime, timedelta

        if not order_datetime:
            order_datetime = datetime.now()

        # Check cutoff time
        cutoff_hour = int(self.cutoff_time)
        cutoff_minute = int((self.cutoff_time % 1) * 60)

        order_time = order_datetime.time()
        cutoff = order_datetime.replace(
            hour=cutoff_hour,
            minute=cutoff_minute,
            second=0
        ).time()

        # If after cutoff, add extra day
        extra_days = 1 if order_time > cutoff else 0

        delivery_date = order_datetime.date() + timedelta(
            days=self.delivery_days + extra_days
        )

        # Skip non-operating days
        delivery_date = self._adjust_for_operating_days(delivery_date)

        return delivery_date

    def _adjust_for_operating_days(self, delivery_date):
        """Adjust delivery date to skip non-operating days."""
        if self.operating_days == 'all':
            return delivery_date

        day_flags = {
            0: self.monday,
            1: self.tuesday,
            2: self.wednesday,
            3: self.thursday,
            4: self.friday,
            5: self.saturday,
            6: self.sunday,
        }

        from datetime import timedelta
        max_attempts = 14  # Max 2 weeks lookahead

        for _ in range(max_attempts):
            if day_flags.get(delivery_date.weekday(), True):
                return delivery_date
            delivery_date += timedelta(days=1)

        return delivery_date
```

#### Dev 2 (Full-Stack) - 8 Hours

**Geocoding Service**

```python
# File: smart_dairy_delivery/services/geocoding_service.py

from odoo import models, api
import requests
import logging

_logger = logging.getLogger(__name__)


class GeocodingService(models.AbstractModel):
    _name = 'geocoding.service'
    _description = 'Geocoding Service'

    @api.model
    def geocode_address(self, address):
        """
        Convert address to coordinates using Google Maps API.

        Args:
            address: Address string

        Returns:
            dict with lat, lng, formatted_address
        """
        api_key = self.env['ir.config_parameter'].sudo().get_param(
            'google.maps.api_key'
        )

        if not api_key:
            _logger.warning("Google Maps API key not configured")
            return self._fallback_geocode(address)

        url = 'https://maps.googleapis.com/maps/api/geocode/json'
        params = {
            'address': address,
            'key': api_key,
            'region': 'bd',  # Bangladesh bias
            'components': 'country:BD',
        }

        try:
            response = requests.get(url, params=params, timeout=10)
            data = response.json()

            if data.get('status') == 'OK' and data.get('results'):
                result = data['results'][0]
                location = result['geometry']['location']

                return {
                    'success': True,
                    'latitude': location['lat'],
                    'longitude': location['lng'],
                    'formatted_address': result['formatted_address'],
                    'place_id': result.get('place_id'),
                    'address_components': result.get('address_components', []),
                }
            else:
                return {
                    'success': False,
                    'error': data.get('status', 'Unknown error'),
                }

        except Exception as e:
            _logger.error(f"Geocoding error: {str(e)}")
            return {'success': False, 'error': str(e)}

    def _fallback_geocode(self, address):
        """Fallback geocoding using OpenStreetMap Nominatim."""
        url = 'https://nominatim.openstreetmap.org/search'
        params = {
            'q': address,
            'format': 'json',
            'countrycodes': 'bd',
            'limit': 1,
        }
        headers = {
            'User-Agent': 'SmartDairy/1.0',
        }

        try:
            response = requests.get(url, params=params, headers=headers, timeout=10)
            data = response.json()

            if data:
                result = data[0]
                return {
                    'success': True,
                    'latitude': float(result['lat']),
                    'longitude': float(result['lon']),
                    'formatted_address': result['display_name'],
                }
            return {'success': False, 'error': 'Address not found'}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @api.model
    def reverse_geocode(self, latitude, longitude):
        """Convert coordinates to address."""
        api_key = self.env['ir.config_parameter'].sudo().get_param(
            'google.maps.api_key'
        )

        if not api_key:
            return self._fallback_reverse_geocode(latitude, longitude)

        url = 'https://maps.googleapis.com/maps/api/geocode/json'
        params = {
            'latlng': f'{latitude},{longitude}',
            'key': api_key,
        }

        try:
            response = requests.get(url, params=params, timeout=10)
            data = response.json()

            if data.get('status') == 'OK' and data.get('results'):
                result = data['results'][0]
                return {
                    'success': True,
                    'formatted_address': result['formatted_address'],
                    'address_components': result.get('address_components', []),
                }
            return {'success': False, 'error': 'No results'}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    def _fallback_reverse_geocode(self, latitude, longitude):
        """Fallback reverse geocoding using Nominatim."""
        url = 'https://nominatim.openstreetmap.org/reverse'
        params = {
            'lat': latitude,
            'lon': longitude,
            'format': 'json',
        }
        headers = {'User-Agent': 'SmartDairy/1.0'}

        try:
            response = requests.get(url, params=params, headers=headers, timeout=10)
            data = response.json()

            if data:
                return {
                    'success': True,
                    'formatted_address': data.get('display_name'),
                }
            return {'success': False, 'error': 'No results'}

        except Exception as e:
            return {'success': False, 'error': str(e)}
```

**Zone Lookup Service**

```python
# File: smart_dairy_delivery/services/zone_lookup_service.py

from odoo import models, api
import logging

_logger = logging.getLogger(__name__)


class ZoneLookupService(models.AbstractModel):
    _name = 'zone.lookup.service'
    _description = 'Zone Lookup Service'

    @api.model
    def find_zone_for_address(self, address=None, latitude=None, longitude=None):
        """
        Find delivery zone for an address or coordinates.

        Args:
            address: Address string (will be geocoded)
            latitude: Latitude coordinate
            longitude: Longitude coordinate

        Returns:
            delivery.zone record or None
        """
        # Geocode if only address provided
        if not latitude or not longitude:
            if not address:
                return None

            geocode_service = self.env['geocoding.service']
            result = geocode_service.geocode_address(address)

            if not result.get('success'):
                _logger.warning(f"Could not geocode address: {address}")
                return None

            latitude = result['latitude']
            longitude = result['longitude']

        # Search zones
        zones = self.env['delivery.zone'].search([
            ('active', '=', True),
            ('delivery_available', '=', True),
        ], order='sequence')

        # Check each zone (most specific first due to sequence)
        for zone in zones:
            if zone.check_point_in_zone(latitude, longitude):
                return zone

        # Check parent zones if no direct match
        for zone in zones.filtered(lambda z: z.parent_id):
            if zone.parent_id.check_point_in_zone(latitude, longitude):
                return zone.parent_id

        return None

    @api.model
    def get_available_zones(self, latitude=None, longitude=None):
        """Get all available zones, optionally sorted by proximity."""
        zones = self.env['delivery.zone'].search([
            ('active', '=', True),
            ('delivery_available', '=', True),
        ])

        if latitude and longitude:
            # Sort by distance to center
            def distance_to_center(zone):
                if zone.center_latitude and zone.center_longitude:
                    return self._calculate_distance(
                        latitude, longitude,
                        zone.center_latitude, zone.center_longitude
                    )
                return float('inf')

            zones = zones.sorted(key=distance_to_center)

        return zones

    def _calculate_distance(self, lat1, lon1, lat2, lon2):
        """Calculate distance between two points in km."""
        from math import radians, cos, sin, asin, sqrt

        R = 6371
        lat1, lon1, lat2, lon2 = map(radians, [lat1, lon1, lat2, lon2])

        dlat = lat2 - lat1
        dlon = lon2 - lon1

        a = sin(dlat/2)**2 + cos(lat1) * cos(lat2) * sin(dlon/2)**2
        c = 2 * asin(sqrt(a))

        return R * c

    @api.model
    def get_delivery_info(self, zone_id, order_amount):
        """Get complete delivery information for a zone."""
        zone = self.env['delivery.zone'].browse(zone_id)

        if not zone.exists():
            return {'error': 'Zone not found'}

        delivery_fee = zone.get_delivery_fee(order_amount)
        delivery_date = zone.get_estimated_delivery_date()

        result = {
            'zone_id': zone.id,
            'zone_name': zone.name,
            'delivery_available': zone.delivery_available,
            'delivery_fee': delivery_fee,
            'free_delivery': delivery_fee == 0,
            'min_order_amount': zone.min_order_amount,
            'delivery_date': delivery_date.isoformat(),
            'delivery_days': zone.delivery_days,
            'cod_available': zone.cod_available,
            'cod_fee': zone.cod_fee if zone.cod_available else 0,
        }

        # Add express info if available
        if zone.express_available:
            result['express'] = {
                'available': True,
                'fee': zone.express_fee,
                'hours': zone.express_hours,
            }

        return result
```

#### Dev 3 (Frontend/Mobile Lead) - 8 Hours

**Zone Selection Component**

```javascript
/** @odoo-module **/
// File: smart_dairy_delivery/static/src/js/zone_selector.js

import { Component, useState, onMounted, onWillUnmount } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";
import { _t } from "@web/core/l10n/translation";

export class DeliveryZoneSelector extends Component {
    static template = "smart_dairy_delivery.ZoneSelector";
    static props = {
        orderAmount: { type: Number, default: 0 },
        onZoneSelected: { type: Function, optional: true },
        initialAddress: { type: String, optional: true },
    };

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            loading: false,
            address: this.props.initialAddress || '',
            searchResults: [],
            selectedZone: null,
            deliveryInfo: null,
            detectingLocation: false,
            map: null,
            marker: null,
        });

        this.mapRef = { el: null };

        onMounted(() => {
            if (this.props.initialAddress) {
                this.searchAddress();
            }
        });
    }

    async searchAddress() {
        if (!this.state.address || this.state.address.length < 3) {
            return;
        }

        this.state.loading = true;

        try {
            const result = await this.rpc('/delivery/zone/search', {
                address: this.state.address,
            });

            if (result.success) {
                this.state.searchResults = result.zones;

                if (result.detected_zone) {
                    this.selectZone(result.detected_zone);
                }
            } else {
                this.notification.add(
                    result.error || _t("Could not find address"),
                    { type: "warning" }
                );
            }
        } catch (error) {
            console.error("Address search error:", error);
        } finally {
            this.state.loading = false;
        }
    }

    async detectLocation() {
        if (!navigator.geolocation) {
            this.notification.add(_t("Geolocation not supported"), { type: "warning" });
            return;
        }

        this.state.detectingLocation = true;

        navigator.geolocation.getCurrentPosition(
            async (position) => {
                const { latitude, longitude } = position.coords;

                try {
                    const result = await this.rpc('/delivery/zone/detect', {
                        latitude,
                        longitude,
                    });

                    if (result.success && result.zone) {
                        this.state.address = result.formatted_address || '';
                        this.selectZone(result.zone);
                    } else {
                        this.notification.add(
                            _t("No delivery zone found for your location"),
                            { type: "warning" }
                        );
                    }
                } catch (error) {
                    console.error("Zone detection error:", error);
                } finally {
                    this.state.detectingLocation = false;
                }
            },
            (error) => {
                this.state.detectingLocation = false;
                this.notification.add(
                    _t("Could not detect location"),
                    { type: "warning" }
                );
            }
        );
    }

    async selectZone(zone) {
        this.state.selectedZone = zone;

        // Get delivery info
        try {
            const info = await this.rpc('/delivery/zone/info', {
                zone_id: zone.id,
                order_amount: this.props.orderAmount,
            });

            this.state.deliveryInfo = info;

            if (this.props.onZoneSelected) {
                this.props.onZoneSelected({
                    zone: zone,
                    deliveryInfo: info,
                });
            }
        } catch (error) {
            console.error("Failed to get delivery info:", error);
        }
    }

    formatCurrency(amount) {
        return `৳${amount.toFixed(2)}`;
    }

    formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('bn-BD', {
            weekday: 'long',
            month: 'short',
            day: 'numeric',
        });
    }
}
```

**Zone Selector Template**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- File: smart_dairy_delivery/static/src/xml/zone_selector_templates.xml -->

<templates xml:space="preserve">
    <t t-name="smart_dairy_delivery.ZoneSelector">
        <div class="delivery-zone-selector">
            <!-- Address Input -->
            <div class="address-input-group mb-3">
                <label class="form-label">Delivery Address</label>
                <div class="input-group">
                    <input type="text"
                           class="form-control"
                           placeholder="Enter your delivery address..."
                           t-model="state.address"
                           t-on-keyup.enter="searchAddress"/>
                    <button class="btn btn-outline-secondary"
                            type="button"
                            t-att-disabled="state.detectingLocation"
                            t-on-click="detectLocation"
                            title="Use my location">
                        <t t-if="state.detectingLocation">
                            <span class="spinner-border spinner-border-sm"/>
                        </t>
                        <t t-else="">
                            <i class="fa fa-location-arrow"/>
                        </t>
                    </button>
                    <button class="btn btn-primary"
                            type="button"
                            t-att-disabled="state.loading"
                            t-on-click="searchAddress">
                        <t t-if="state.loading">
                            <span class="spinner-border spinner-border-sm"/>
                        </t>
                        <t t-else="">Search</t>
                    </button>
                </div>
            </div>

            <!-- Zone Selection -->
            <t t-if="state.searchResults.length > 0">
                <div class="zone-list mb-3">
                    <label class="form-label">Select Delivery Zone</label>
                    <div class="list-group">
                        <t t-foreach="state.searchResults" t-as="zone" t-key="zone.id">
                            <a href="#" class="list-group-item list-group-item-action"
                               t-att-class="{'active': state.selectedZone?.id === zone.id}"
                               t-on-click.prevent="() => selectZone(zone)">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong t-esc="zone.name"/>
                                        <small class="d-block text-muted" t-esc="zone.delivery_days + ' day delivery'"/>
                                    </div>
                                    <div class="text-end">
                                        <t t-if="zone.delivery_fee > 0">
                                            <span t-esc="formatCurrency(zone.delivery_fee)"/>
                                        </t>
                                        <t t-else="">
                                            <span class="badge bg-success">Free</span>
                                        </t>
                                    </div>
                                </div>
                            </a>
                        </t>
                    </div>
                </div>
            </t>

            <!-- Delivery Info -->
            <t t-if="state.deliveryInfo">
                <div class="delivery-info-card card">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fa fa-truck me-2"/>
                            Delivery Details
                        </h6>

                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Delivery Fee</small>
                                <p class="mb-2">
                                    <t t-if="state.deliveryInfo.free_delivery">
                                        <span class="text-success">
                                            <del class="text-muted me-1">
                                                <t t-esc="formatCurrency(state.selectedZone.delivery_fee)"/>
                                            </del>
                                            Free!
                                        </span>
                                    </t>
                                    <t t-else="">
                                        <t t-esc="formatCurrency(state.deliveryInfo.delivery_fee)"/>
                                    </t>
                                </p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Estimated Delivery</small>
                                <p class="mb-2" t-esc="formatDate(state.deliveryInfo.delivery_date)"/>
                            </div>
                        </div>

                        <!-- Express Option -->
                        <t t-if="state.deliveryInfo.express?.available">
                            <div class="express-option mt-2 p-2 bg-light rounded">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="expressDelivery"/>
                                    <label class="form-check-label" for="expressDelivery">
                                        <i class="fa fa-bolt text-warning me-1"/>
                                        Express Delivery
                                        (<t t-esc="state.deliveryInfo.express.hours"/>h)
                                        - +<t t-esc="formatCurrency(state.deliveryInfo.express.fee)"/>
                                    </label>
                                </div>
                            </div>
                        </t>

                        <!-- Minimum Order Warning -->
                        <t t-if="props.orderAmount &lt; state.deliveryInfo.min_order_amount">
                            <div class="alert alert-warning mt-2 mb-0 py-2">
                                <i class="fa fa-exclamation-triangle me-1"/>
                                Minimum order for this zone is
                                <t t-esc="formatCurrency(state.deliveryInfo.min_order_amount)"/>
                            </div>
                        </t>
                    </div>
                </div>
            </t>
        </div>
    </t>
</templates>
```

---

### Day 503-508: Zone Editor & Admin Interface

*(Complete zone map editor with polygon drawing, admin dashboard, and API controllers)*

---

## 5. Technical Specifications

### 5.1 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/delivery/zone/search` | POST | Search zones by address |
| `/delivery/zone/detect` | POST | Detect zone by coordinates |
| `/delivery/zone/info` | POST | Get delivery info for zone |
| `/delivery/zones/available` | GET | List all available zones |
| `/api/v1/admin/zones` | GET/POST | Admin zone management |

### 5.2 GeoJSON Polygon Format

```json
{
    "type": "Polygon",
    "coordinates": [
        [
            [90.3563, 23.7104],
            [90.4200, 23.7104],
            [90.4200, 23.8100],
            [90.3563, 23.8100],
            [90.3563, 23.7104]
        ]
    ]
}
```

---

## 6. Database Schema

```sql
CREATE TABLE delivery_zone (
    id SERIAL PRIMARY KEY,
    name VARCHAR(128) NOT NULL,
    code VARCHAR(32) UNIQUE NOT NULL,
    zone_type VARCHAR(20) DEFAULT 'polygon',
    geo_polygon TEXT,
    center_latitude DECIMAL(10, 7),
    center_longitude DECIMAL(10, 7),
    radius_km DECIMAL(10, 2),
    delivery_fee DECIMAL(10, 2) DEFAULT 50.00,
    min_order_amount DECIMAL(10, 2) DEFAULT 200.00,
    free_delivery_threshold DECIMAL(10, 2) DEFAULT 1000.00,
    delivery_days INTEGER DEFAULT 1,
    express_available BOOLEAN DEFAULT FALSE,
    express_fee DECIMAL(10, 2) DEFAULT 100.00,
    cod_available BOOLEAN DEFAULT TRUE,
    active BOOLEAN DEFAULT TRUE,
    create_date TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_dz_code ON delivery_zone(code);
CREATE INDEX idx_dz_active ON delivery_zone(active);
```

---

## 7. Testing Requirements

### 7.1 Test Coverage

| Component | Target |
|-----------|--------|
| Zone Model | > 90% |
| Geocoding Service | > 80% |
| Zone Lookup | > 90% |
| Fee Calculator | > 95% |

### 7.2 Test Scenarios

```python
class TestDeliveryZone(TransactionCase):

    def test_point_in_polygon(self):
        """Test polygon containment check."""
        pass

    def test_point_in_radius(self):
        """Test radius containment check."""
        pass

    def test_fee_calculation(self):
        """Test delivery fee calculation."""
        pass

    def test_free_delivery_threshold(self):
        """Test free delivery for large orders."""
        pass

    def test_delivery_date_estimation(self):
        """Test delivery date calculation."""
        pass
```

---

## 8. Sign-off Checklist

- [ ] Zone model complete
- [ ] Polygon geo-fencing working
- [ ] Geocoding integration functional
- [ ] Fee calculation accurate
- [ ] Time estimation working
- [ ] Express delivery configured
- [ ] Zone selector UI complete
- [ ] Admin zone editor working
- [ ] All tests passing

---

**Document End**

*Milestone 76: Delivery Zone Management*
*Days 501-510 | Phase 8: Operations - Payment & Logistics*
*Smart Dairy Digital Smart Portal + ERP System*
