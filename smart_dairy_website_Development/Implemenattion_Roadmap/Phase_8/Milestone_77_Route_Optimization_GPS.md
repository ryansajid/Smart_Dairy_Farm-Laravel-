# Milestone 77: Route Optimization & GPS Tracking

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P8-M77-v1.0 |
| **Milestone** | 77 - Route Optimization & GPS Tracking |
| **Phase** | Phase 8: Operations - Payment & Logistics |
| **Days** | 511-520 |
| **Duration** | 10 Working Days |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Technical Architecture Team |
| **Last Updated** | 2025-01-15 |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Implement delivery route optimization using Vehicle Routing Problem (VRP) solver with Google OR-Tools, driver assignment system, real-time GPS tracking, delivery sequence optimization, ETA calculation, driver mobile app features, and customer tracking page.**

### 1.2 Scope Summary

| Area | Included |
|------|----------|
| VRP Solver | Route optimization with constraints |
| Driver Assignment | Automated and manual assignment |
| GPS Tracking | Real-time location updates |
| ETA Calculation | Dynamic arrival estimation |
| Driver App | Flutter-based delivery app |
| Customer Tracking | Live order tracking page |
| Route Analytics | Efficiency metrics and reports |

---

## 2. Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| O1 | Route optimization | 20% distance reduction |
| O2 | GPS tracking accuracy | > 95% within 50m |
| O3 | ETA accuracy | ±15 minutes |
| O4 | Driver assignment time | < 1 minute |
| O5 | Location update frequency | Every 30 seconds |
| O6 | Battery optimization | < 5% hourly drain |

---

## 3. Key Deliverables

| # | Deliverable | Owner | Format | Due Day |
|---|-------------|-------|--------|---------|
| D1 | VRP solver implementation | Dev 1 | Python | Day 513 |
| D2 | Driver assignment service | Dev 1 | Python | Day 514 |
| D3 | Route model and API | Dev 1 | Python | Day 515 |
| D4 | GPS tracking service | Dev 2 | Python | Day 514 |
| D5 | ETA calculator | Dev 2 | Python | Day 515 |
| D6 | Real-time location websocket | Dev 2 | Python | Day 516 |
| D7 | Driver mobile app screens | Dev 3 | Flutter | Day 517 |
| D8 | Customer tracking page | Dev 3 | OWL/JS | Day 518 |
| D9 | Route analytics dashboard | Dev 3 | OWL/JS | Day 519 |
| D10 | Integration tests | All | Python | Day 520 |

---

## 4. Day-by-Day Development Plan

### Day 511-513: VRP Solver & Route Optimization

#### Dev 1 (Backend Lead)

**Route Optimization Service with OR-Tools**

```python
# File: smart_dairy_delivery/services/route_optimizer.py

from odoo import models, api, fields
from ortools.constraint_solver import routing_enums_pb2
from ortools.constraint_solver import pywrapcp
import logging

_logger = logging.getLogger(__name__)


class RouteOptimizer(models.AbstractModel):
    _name = 'route.optimizer'
    _description = 'Route Optimization Service'

    @api.model
    def optimize_routes(self, deliveries, drivers, depot_location):
        """
        Optimize delivery routes using VRP solver.

        Args:
            deliveries: List of delivery.order records
            drivers: List of available driver records
            depot_location: (lat, lng) tuple for depot

        Returns:
            dict with optimized routes per driver
        """
        if not deliveries or not drivers:
            return {'routes': [], 'unassigned': []}

        # Create data model
        data = self._create_data_model(deliveries, drivers, depot_location)

        # Create routing manager and model
        manager = pywrapcp.RoutingIndexManager(
            data['num_locations'],
            data['num_vehicles'],
            data['depot']
        )
        routing = pywrapcp.RoutingModel(manager)

        # Define distance callback
        def distance_callback(from_index, to_index):
            from_node = manager.IndexToNode(from_index)
            to_node = manager.IndexToNode(to_index)
            return data['distance_matrix'][from_node][to_node]

        transit_callback_index = routing.RegisterTransitCallback(distance_callback)
        routing.SetArcCostEvaluatorOfAllVehicles(transit_callback_index)

        # Add capacity constraints
        def demand_callback(from_index):
            from_node = manager.IndexToNode(from_index)
            return data['demands'][from_node]

        demand_callback_index = routing.RegisterUnaryTransitCallback(demand_callback)
        routing.AddDimensionWithVehicleCapacity(
            demand_callback_index,
            0,  # null capacity slack
            data['vehicle_capacities'],
            True,  # start cumul to zero
            'Capacity'
        )

        # Add time windows if present
        if data.get('time_windows'):
            self._add_time_windows(routing, manager, data)

        # Set search parameters
        search_parameters = pywrapcp.DefaultRoutingSearchParameters()
        search_parameters.first_solution_strategy = (
            routing_enums_pb2.FirstSolutionStrategy.PATH_CHEAPEST_ARC
        )
        search_parameters.local_search_metaheuristic = (
            routing_enums_pb2.LocalSearchMetaheuristic.GUIDED_LOCAL_SEARCH
        )
        search_parameters.time_limit.seconds = 30

        # Solve
        solution = routing.SolveWithParameters(search_parameters)

        if solution:
            return self._extract_routes(
                manager, routing, solution, data, deliveries, drivers
            )

        _logger.warning("No solution found for route optimization")
        return {'routes': [], 'unassigned': list(deliveries)}

    def _create_data_model(self, deliveries, drivers, depot_location):
        """Create data model for VRP solver."""
        locations = [depot_location]  # Index 0 is depot

        for delivery in deliveries:
            locations.append((
                delivery.delivery_latitude,
                delivery.delivery_longitude
            ))

        # Calculate distance matrix
        distance_matrix = self._calculate_distance_matrix(locations)

        # Demands (package count or weight)
        demands = [0]  # Depot has 0 demand
        for delivery in deliveries:
            demands.append(delivery.package_count or 1)

        # Vehicle capacities
        vehicle_capacities = [d.vehicle_capacity or 50 for d in drivers]

        return {
            'distance_matrix': distance_matrix,
            'demands': demands,
            'vehicle_capacities': vehicle_capacities,
            'num_vehicles': len(drivers),
            'num_locations': len(locations),
            'depot': 0,
            'locations': locations,
        }

    def _calculate_distance_matrix(self, locations):
        """Calculate distance matrix using Haversine formula."""
        from math import radians, cos, sin, asin, sqrt

        def haversine(loc1, loc2):
            R = 6371 * 1000  # meters
            lat1, lon1 = map(radians, loc1)
            lat2, lon2 = map(radians, loc2)

            dlat = lat2 - lat1
            dlon = lon2 - lon1

            a = sin(dlat/2)**2 + cos(lat1) * cos(lat2) * sin(dlon/2)**2
            c = 2 * asin(sqrt(a))

            return int(R * c)

        n = len(locations)
        matrix = [[0] * n for _ in range(n)]

        for i in range(n):
            for j in range(n):
                if i != j:
                    matrix[i][j] = haversine(locations[i], locations[j])

        return matrix

    def _add_time_windows(self, routing, manager, data):
        """Add time window constraints to routing model."""
        time_callback_index = routing.RegisterTransitCallback(
            lambda from_index, to_index: data['travel_times'][
                manager.IndexToNode(from_index)
            ][manager.IndexToNode(to_index)]
        )

        routing.AddDimension(
            time_callback_index,
            30,  # allow waiting time
            8 * 60,  # maximum time per vehicle (8 hours in minutes)
            False,  # don't force start cumul to zero
            'Time'
        )

        time_dimension = routing.GetDimensionOrDie('Time')

        for location_idx, time_window in enumerate(data['time_windows']):
            if location_idx == data['depot']:
                continue
            index = manager.NodeToIndex(location_idx)
            time_dimension.CumulVar(index).SetRange(
                time_window[0], time_window[1]
            )

    def _extract_routes(self, manager, routing, solution, data, deliveries, drivers):
        """Extract optimized routes from solution."""
        routes = []
        total_distance = 0

        for vehicle_id in range(data['num_vehicles']):
            route = {
                'driver_id': drivers[vehicle_id].id,
                'driver_name': drivers[vehicle_id].name,
                'stops': [],
                'total_distance': 0,
                'total_time': 0,
            }

            index = routing.Start(vehicle_id)
            route_distance = 0
            stop_sequence = 0

            while not routing.IsEnd(index):
                node_index = manager.IndexToNode(index)

                if node_index > 0:  # Skip depot
                    delivery = deliveries[node_index - 1]
                    stop_sequence += 1
                    route['stops'].append({
                        'sequence': stop_sequence,
                        'delivery_id': delivery.id,
                        'address': delivery.delivery_address,
                        'lat': data['locations'][node_index][0],
                        'lng': data['locations'][node_index][1],
                    })

                previous_index = index
                index = solution.Value(routing.NextVar(index))
                route_distance += routing.GetArcCostForVehicle(
                    previous_index, index, vehicle_id
                )

            route['total_distance'] = route_distance
            route['total_time'] = int(route_distance / 500 * 60)  # Assume 500m/min

            if route['stops']:
                routes.append(route)
                total_distance += route_distance

        return {
            'routes': routes,
            'total_distance': total_distance,
            'total_drivers': len([r for r in routes if r['stops']]),
            'total_deliveries': sum(len(r['stops']) for r in routes),
        }


class DeliveryRoute(models.Model):
    _name = 'delivery.route'
    _description = 'Delivery Route'
    _order = 'route_date desc, sequence'

    name = fields.Char(string='Route Name', required=True)
    sequence = fields.Integer(string='Sequence', default=10)
    route_date = fields.Date(string='Route Date', required=True, default=fields.Date.today)

    driver_id = fields.Many2one('hr.employee', string='Driver', required=True)
    vehicle_id = fields.Many2one('fleet.vehicle', string='Vehicle')

    # Route Details
    stop_ids = fields.One2many('delivery.route.stop', 'route_id', string='Stops')
    total_stops = fields.Integer(string='Total Stops', compute='_compute_totals')
    total_distance = fields.Float(string='Total Distance (km)')
    estimated_time = fields.Float(string='Estimated Time (min)')

    # Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('confirmed', 'Confirmed'),
        ('in_progress', 'In Progress'),
        ('completed', 'Completed'),
        ('cancelled', 'Cancelled'),
    ], string='Status', default='draft')

    started_at = fields.Datetime(string='Started At')
    completed_at = fields.Datetime(string='Completed At')

    # GPS Tracking
    current_latitude = fields.Float(string='Current Latitude', digits=(10, 7))
    current_longitude = fields.Float(string='Current Longitude', digits=(10, 7))
    last_location_update = fields.Datetime(string='Last Location Update')

    @api.depends('stop_ids')
    def _compute_totals(self):
        for route in self:
            route.total_stops = len(route.stop_ids)

    def action_start_route(self):
        """Start delivery route."""
        self.ensure_one()
        self.write({
            'state': 'in_progress',
            'started_at': fields.Datetime.now(),
        })

    def action_complete_route(self):
        """Complete delivery route."""
        self.ensure_one()
        self.write({
            'state': 'completed',
            'completed_at': fields.Datetime.now(),
        })


class DeliveryRouteStop(models.Model):
    _name = 'delivery.route.stop'
    _description = 'Delivery Route Stop'
    _order = 'sequence'

    route_id = fields.Many2one('delivery.route', string='Route', ondelete='cascade')
    sequence = fields.Integer(string='Sequence', required=True)

    delivery_order_id = fields.Many2one('delivery.order', string='Delivery Order')
    partner_id = fields.Many2one('res.partner', string='Customer')

    # Location
    address = fields.Text(string='Address')
    latitude = fields.Float(string='Latitude', digits=(10, 7))
    longitude = fields.Float(string='Longitude', digits=(10, 7))

    # Timing
    estimated_arrival = fields.Datetime(string='ETA')
    actual_arrival = fields.Datetime(string='Actual Arrival')
    departure_time = fields.Datetime(string='Departure')

    # Status
    state = fields.Selection([
        ('pending', 'Pending'),
        ('arrived', 'Arrived'),
        ('completed', 'Completed'),
        ('failed', 'Failed'),
        ('skipped', 'Skipped'),
    ], string='Status', default='pending')

    notes = fields.Text(string='Notes')
    signature = fields.Binary(string='Customer Signature')
    delivery_photo = fields.Binary(string='Delivery Photo')
```

#### Dev 2 (Full-Stack)

**GPS Tracking Service**

```python
# File: smart_dairy_delivery/services/gps_tracking.py

from odoo import models, api, fields
from datetime import datetime, timedelta
import logging

_logger = logging.getLogger(__name__)


class GPSTrackingService(models.AbstractModel):
    _name = 'gps.tracking.service'
    _description = 'GPS Tracking Service'

    @api.model
    def update_driver_location(self, driver_id, latitude, longitude, speed=None,
                               heading=None, accuracy=None, timestamp=None):
        """
        Update driver's current location.

        Args:
            driver_id: Driver employee ID
            latitude: Current latitude
            longitude: Current longitude
            speed: Speed in km/h (optional)
            heading: Direction in degrees (optional)
            accuracy: GPS accuracy in meters (optional)
            timestamp: Location timestamp (optional)

        Returns:
            dict with update result
        """
        driver = self.env['hr.employee'].browse(driver_id)
        if not driver.exists():
            return {'success': False, 'error': 'Driver not found'}

        # Update driver location
        driver.write({
            'current_latitude': latitude,
            'current_longitude': longitude,
            'last_location_update': timestamp or fields.Datetime.now(),
        })

        # Create location history entry
        self.env['driver.location.history'].create({
            'driver_id': driver_id,
            'latitude': latitude,
            'longitude': longitude,
            'speed': speed,
            'heading': heading,
            'accuracy': accuracy,
            'recorded_at': timestamp or fields.Datetime.now(),
        })

        # Update active route if exists
        active_route = self.env['delivery.route'].search([
            ('driver_id', '=', driver_id),
            ('state', '=', 'in_progress'),
        ], limit=1)

        if active_route:
            active_route.write({
                'current_latitude': latitude,
                'current_longitude': longitude,
                'last_location_update': fields.Datetime.now(),
            })

            # Update ETAs for remaining stops
            self._update_route_etas(active_route, latitude, longitude)

            # Broadcast location update via websocket
            self._broadcast_location(active_route, latitude, longitude)

        return {
            'success': True,
            'driver_id': driver_id,
            'route_id': active_route.id if active_route else None,
        }

    def _update_route_etas(self, route, current_lat, current_lng):
        """Update ETAs for remaining stops on route."""
        pending_stops = route.stop_ids.filtered(lambda s: s.state == 'pending')

        if not pending_stops:
            return

        # Get current time
        now = datetime.now()
        cumulative_time = 0

        for stop in pending_stops.sorted('sequence'):
            # Calculate distance to stop
            distance = self._calculate_distance(
                current_lat, current_lng,
                stop.latitude, stop.longitude
            )

            # Estimate time (assume 25 km/h average in city)
            travel_time = (distance / 25) * 60  # minutes
            stop_time = 10  # 10 minutes per stop

            cumulative_time += travel_time + stop_time
            eta = now + timedelta(minutes=cumulative_time)

            stop.write({'estimated_arrival': eta})

            # Update position for next calculation
            current_lat, current_lng = stop.latitude, stop.longitude

    def _calculate_distance(self, lat1, lng1, lat2, lng2):
        """Calculate distance between two points in km."""
        from math import radians, cos, sin, asin, sqrt

        R = 6371

        lat1, lng1, lat2, lng2 = map(radians, [lat1, lng1, lat2, lng2])
        dlat = lat2 - lat1
        dlng = lng2 - lng1

        a = sin(dlat/2)**2 + cos(lat1) * cos(lat2) * sin(dlng/2)**2
        c = 2 * asin(sqrt(a))

        return R * c

    def _broadcast_location(self, route, latitude, longitude):
        """Broadcast location update to connected clients."""
        # This would integrate with websocket server
        channel = f'delivery_route_{route.id}'
        message = {
            'type': 'location_update',
            'route_id': route.id,
            'driver_id': route.driver_id.id,
            'latitude': latitude,
            'longitude': longitude,
            'timestamp': fields.Datetime.now().isoformat(),
        }

        # Send via Odoo bus or external websocket
        self.env['bus.bus']._sendone(channel, 'location_update', message)

    @api.model
    def get_driver_location(self, driver_id):
        """Get current location of a driver."""
        driver = self.env['hr.employee'].browse(driver_id)
        if not driver.exists():
            return None

        return {
            'driver_id': driver.id,
            'latitude': driver.current_latitude,
            'longitude': driver.current_longitude,
            'last_update': driver.last_location_update,
        }

    @api.model
    def get_route_tracking_info(self, route_id):
        """Get tracking information for a delivery route."""
        route = self.env['delivery.route'].browse(route_id)
        if not route.exists():
            return None

        return {
            'route_id': route.id,
            'driver_name': route.driver_id.name,
            'current_location': {
                'latitude': route.current_latitude,
                'longitude': route.current_longitude,
            },
            'last_update': route.last_location_update,
            'total_stops': route.total_stops,
            'completed_stops': len(route.stop_ids.filtered(lambda s: s.state == 'completed')),
            'next_stop': self._get_next_stop_info(route),
            'state': route.state,
        }

    def _get_next_stop_info(self, route):
        """Get information about next pending stop."""
        next_stop = route.stop_ids.filtered(
            lambda s: s.state == 'pending'
        ).sorted('sequence')[:1]

        if not next_stop:
            return None

        return {
            'sequence': next_stop.sequence,
            'address': next_stop.address,
            'eta': next_stop.estimated_arrival,
        }


class DriverLocationHistory(models.Model):
    _name = 'driver.location.history'
    _description = 'Driver Location History'
    _order = 'recorded_at desc'

    driver_id = fields.Many2one('hr.employee', string='Driver', required=True, index=True)
    route_id = fields.Many2one('delivery.route', string='Route')

    latitude = fields.Float(string='Latitude', digits=(10, 7))
    longitude = fields.Float(string='Longitude', digits=(10, 7))
    speed = fields.Float(string='Speed (km/h)')
    heading = fields.Float(string='Heading (degrees)')
    accuracy = fields.Float(string='Accuracy (m)')

    recorded_at = fields.Datetime(string='Recorded At', required=True, index=True)

    @api.model
    def cleanup_old_records(self, days=30):
        """Remove location history older than specified days."""
        cutoff = datetime.now() - timedelta(days=days)
        old_records = self.search([('recorded_at', '<', cutoff)])
        old_records.unlink()
        return len(old_records)
```

#### Dev 3 (Frontend/Mobile Lead)

**Driver App - Delivery Screen (Flutter)**

```dart
// File: driver_app/lib/features/delivery/delivery_screen.dart

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:geolocator/geolocator.dart';

class DeliveryScreen extends ConsumerStatefulWidget {
  final int routeId;

  const DeliveryScreen({required this.routeId, super.key});

  @override
  ConsumerState<DeliveryScreen> createState() => _DeliveryScreenState();
}

class _DeliveryScreenState extends ConsumerState<DeliveryScreen> {
  GoogleMapController? _mapController;
  Position? _currentPosition;
  Set<Marker> _markers = {};
  Set<Polyline> _polylines = {};

  @override
  void initState() {
    super.initState();
    _initLocationTracking();
    _loadRouteData();
  }

  Future<void> _initLocationTracking() async {
    final permission = await Geolocator.checkPermission();
    if (permission == LocationPermission.denied) {
      await Geolocator.requestPermission();
    }

    Geolocator.getPositionStream(
      locationSettings: const LocationSettings(
        accuracy: LocationAccuracy.high,
        distanceFilter: 10, // Update every 10 meters
      ),
    ).listen((position) {
      setState(() => _currentPosition = position);
      _updateLocation(position);
    });
  }

  Future<void> _updateLocation(Position position) async {
    await ref.read(locationServiceProvider).updateLocation(
      routeId: widget.routeId,
      latitude: position.latitude,
      longitude: position.longitude,
      speed: position.speed * 3.6, // Convert m/s to km/h
      heading: position.heading,
      accuracy: position.accuracy,
    );
  }

  Future<void> _loadRouteData() async {
    final route = await ref.read(routeServiceProvider).getRoute(widget.routeId);
    _buildMarkersAndRoute(route);
  }

  void _buildMarkersAndRoute(RouteData route) {
    final markers = <Marker>{};
    final points = <LatLng>[];

    for (final stop in route.stops) {
      final position = LatLng(stop.latitude, stop.longitude);
      points.add(position);

      markers.add(Marker(
        markerId: MarkerId('stop_${stop.sequence}'),
        position: position,
        infoWindow: InfoWindow(
          title: 'Stop ${stop.sequence}',
          snippet: stop.address,
        ),
        icon: stop.state == 'completed'
            ? BitmapDescriptor.defaultMarkerWithHue(BitmapDescriptor.hueGreen)
            : BitmapDescriptor.defaultMarkerWithHue(BitmapDescriptor.hueRed),
      ));
    }

    setState(() {
      _markers = markers;
      _polylines = {
        Polyline(
          polylineId: const PolylineId('route'),
          points: points,
          color: Colors.blue,
          width: 4,
        ),
      };
    });
  }

  @override
  Widget build(BuildContext context) {
    final routeAsync = ref.watch(routeProvider(widget.routeId));

    return Scaffold(
      appBar: AppBar(
        title: const Text('Delivery Route'),
        actions: [
          IconButton(
            icon: const Icon(Icons.navigation),
            onPressed: _openNavigation,
          ),
        ],
      ),
      body: Column(
        children: [
          // Map
          Expanded(
            flex: 2,
            child: GoogleMap(
              initialCameraPosition: CameraPosition(
                target: _currentPosition != null
                    ? LatLng(_currentPosition!.latitude, _currentPosition!.longitude)
                    : const LatLng(23.8103, 90.4125), // Dhaka default
                zoom: 14,
              ),
              markers: _markers,
              polylines: _polylines,
              myLocationEnabled: true,
              myLocationButtonEnabled: true,
              onMapCreated: (controller) => _mapController = controller,
            ),
          ),

          // Stop List
          Expanded(
            flex: 1,
            child: routeAsync.when(
              data: (route) => _buildStopList(route),
              loading: () => const Center(child: CircularProgressIndicator()),
              error: (e, _) => Center(child: Text('Error: $e')),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStopList(RouteData route) {
    return ListView.builder(
      itemCount: route.stops.length,
      itemBuilder: (context, index) {
        final stop = route.stops[index];
        return ListTile(
          leading: CircleAvatar(
            backgroundColor: stop.state == 'completed' ? Colors.green : Colors.orange,
            child: Text('${stop.sequence}'),
          ),
          title: Text(stop.address, maxLines: 1, overflow: TextOverflow.ellipsis),
          subtitle: stop.eta != null
              ? Text('ETA: ${_formatTime(stop.eta!)}')
              : null,
          trailing: stop.state == 'pending'
              ? ElevatedButton(
                  onPressed: () => _navigateToStop(stop),
                  child: const Text('Start'),
                )
              : Icon(
                  stop.state == 'completed' ? Icons.check_circle : Icons.cancel,
                  color: stop.state == 'completed' ? Colors.green : Colors.red,
                ),
        );
      },
    );
  }

  String _formatTime(DateTime time) {
    return '${time.hour.toString().padLeft(2, '0')}:${time.minute.toString().padLeft(2, '0')}';
  }

  void _navigateToStop(StopData stop) {
    // Open Google Maps navigation
  }

  void _openNavigation() {
    // Open turn-by-turn navigation
  }
}
```

**Customer Tracking Page (OWL)**

```javascript
/** @odoo-module **/
// File: smart_dairy_delivery/static/src/js/order_tracking.js

import { Component, useState, onMounted, onWillUnmount } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class OrderTrackingPage extends Component {
    static template = "smart_dairy_delivery.OrderTracking";
    static props = {
        orderId: { type: Number, required: true },
    };

    setup() {
        this.rpc = useService("rpc");
        this.bus = useService("bus_service");

        this.state = useState({
            loading: true,
            order: null,
            route: null,
            driverLocation: null,
            eta: null,
            stops: [],
        });

        this.map = null;
        this.driverMarker = null;
        this.updateInterval = null;

        onMounted(() => {
            this.loadTrackingData();
            this.subscribeToUpdates();
        });

        onWillUnmount(() => {
            if (this.updateInterval) {
                clearInterval(this.updateInterval);
            }
        });
    }

    async loadTrackingData() {
        try {
            const result = await this.rpc('/delivery/track', {
                order_id: this.props.orderId,
            });

            this.state.order = result.order;
            this.state.route = result.route;
            this.state.driverLocation = result.driver_location;
            this.state.eta = result.eta;
            this.state.stops = result.stops;

            this.initMap();
        } catch (error) {
            console.error("Failed to load tracking:", error);
        } finally {
            this.state.loading = false;
        }
    }

    subscribeToUpdates() {
        if (this.state.route) {
            const channel = `delivery_route_${this.state.route.id}`;
            this.bus.subscribe(channel, (message) => {
                if (message.type === 'location_update') {
                    this.updateDriverLocation(message);
                }
            });
        }

        // Fallback polling every 30 seconds
        this.updateInterval = setInterval(() => {
            this.refreshLocation();
        }, 30000);
    }

    async refreshLocation() {
        if (!this.state.route) return;

        try {
            const result = await this.rpc('/delivery/track/location', {
                route_id: this.state.route.id,
            });

            if (result.success) {
                this.updateDriverLocation(result);
            }
        } catch (error) {
            console.error("Location refresh error:", error);
        }
    }

    updateDriverLocation(data) {
        this.state.driverLocation = {
            latitude: data.latitude,
            longitude: data.longitude,
        };
        this.state.eta = data.eta;

        if (this.driverMarker && this.map) {
            this.driverMarker.setPosition({
                lat: data.latitude,
                lng: data.longitude,
            });
        }
    }

    initMap() {
        if (!this.state.driverLocation) return;

        const mapDiv = document.getElementById('tracking-map');
        if (!mapDiv) return;

        this.map = new google.maps.Map(mapDiv, {
            center: {
                lat: this.state.driverLocation.latitude,
                lng: this.state.driverLocation.longitude,
            },
            zoom: 15,
        });

        // Driver marker
        this.driverMarker = new google.maps.Marker({
            position: {
                lat: this.state.driverLocation.latitude,
                lng: this.state.driverLocation.longitude,
            },
            map: this.map,
            icon: '/smart_dairy_delivery/static/src/img/delivery-truck.png',
            title: 'Delivery Driver',
        });

        // Destination marker
        if (this.state.order) {
            new google.maps.Marker({
                position: {
                    lat: this.state.order.delivery_latitude,
                    lng: this.state.order.delivery_longitude,
                },
                map: this.map,
                icon: '/smart_dairy_delivery/static/src/img/destination.png',
                title: 'Your Location',
            });
        }
    }

    formatETA() {
        if (!this.state.eta) return 'Calculating...';
        const eta = new Date(this.state.eta);
        return eta.toLocaleTimeString('bn-BD', { hour: '2-digit', minute: '2-digit' });
    }

    getStopsBefore() {
        if (!this.state.stops) return 0;
        return this.state.stops.filter(s => s.state === 'pending' && s.is_before_you).length;
    }
}
```

---

## 5. Technical Specifications

### 5.1 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/delivery/routes/optimize` | POST | Run route optimization |
| `/delivery/route/<id>/start` | POST | Start delivery route |
| `/delivery/route/<id>/location` | POST | Update driver location |
| `/delivery/track` | POST | Get tracking info |
| `/api/v1/driver/route` | GET | Driver app route data |

### 5.2 Websocket Events

| Event | Direction | Description |
|-------|-----------|-------------|
| `location_update` | Server→Client | Driver location changed |
| `stop_completed` | Server→Client | Delivery completed |
| `eta_update` | Server→Client | ETA recalculated |

---

## 6. Database Schema

```sql
CREATE TABLE delivery_route (
    id SERIAL PRIMARY KEY,
    name VARCHAR(128),
    route_date DATE NOT NULL,
    driver_id INTEGER REFERENCES hr_employee(id),
    state VARCHAR(20) DEFAULT 'draft',
    total_distance DECIMAL(10, 2),
    current_latitude DECIMAL(10, 7),
    current_longitude DECIMAL(10, 7),
    started_at TIMESTAMP,
    completed_at TIMESTAMP
);

CREATE TABLE delivery_route_stop (
    id SERIAL PRIMARY KEY,
    route_id INTEGER REFERENCES delivery_route(id),
    sequence INTEGER NOT NULL,
    latitude DECIMAL(10, 7),
    longitude DECIMAL(10, 7),
    estimated_arrival TIMESTAMP,
    actual_arrival TIMESTAMP,
    state VARCHAR(20) DEFAULT 'pending'
);

CREATE TABLE driver_location_history (
    id SERIAL PRIMARY KEY,
    driver_id INTEGER REFERENCES hr_employee(id),
    latitude DECIMAL(10, 7),
    longitude DECIMAL(10, 7),
    speed DECIMAL(6, 2),
    recorded_at TIMESTAMP
);

CREATE INDEX idx_dlh_driver_time ON driver_location_history(driver_id, recorded_at);
```

---

## 7. Sign-off Checklist

- [ ] VRP solver optimizing routes
- [ ] Driver assignment working
- [ ] GPS tracking accurate
- [ ] ETA calculations correct
- [ ] Real-time updates working
- [ ] Driver app functional
- [ ] Customer tracking page complete
- [ ] Route analytics available

---

**Document End**

*Milestone 77: Route Optimization & GPS Tracking*
*Days 511-520 | Phase 8: Operations - Payment & Logistics*
*Smart Dairy Digital Smart Portal + ERP System*
