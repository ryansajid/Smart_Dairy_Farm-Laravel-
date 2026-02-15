# Milestone 122: Delivery Scheduling

## Smart Dairy Digital Smart Portal + ERP System
## Phase 12: Commerce — Subscription & Automation

---

## Document Control

| Attribute | Value |
|-----------|-------|
| **Document ID** | PH12-M122-DELIVERY-SCHEDULING |
| **Version** | 1.0 |
| **Author** | Senior Technical Architect |
| **Created** | 2026-02-04 |
| **Last Updated** | 2026-02-04 |
| **Status** | Final |
| **Classification** | Internal Use |
| **Parent Phase** | Phase 12 - Commerce Subscription & Automation |
| **Timeline** | Days 611-620 |

---

## 1. Executive Summary

### 1.1 Milestone Overview

Milestone 122 implements an intelligent delivery scheduling system that powers subscription fulfillment operations. This module enables time slot management, geographic zone configuration, route optimization using Google OR-Tools, automated driver assignment, and real-time delivery tracking. The system ensures 90%+ on-time delivery rates while minimizing operational costs through efficient routing.

### 1.2 Business Value Proposition

| Value Area | Expected Outcome | Measurement |
|------------|------------------|-------------|
| On-Time Delivery | 90%+ OTIF rate | Delivery tracking |
| Route Efficiency | 25% reduction in delivery costs | Cost per delivery |
| Customer Satisfaction | 4.5+ delivery rating | Customer feedback |
| Operational Visibility | Real-time tracking | GPS data accuracy |
| Driver Productivity | 30+ deliveries per driver/day | Driver metrics |

### 1.3 Strategic Alignment

This milestone directly supports:
- **RFP-DEL-001**: Delivery scheduling with time slots
- **RFP-DEL-002**: Route optimization
- **BRD-DEL-01**: 90% on-time delivery rate
- **SRS-DEL-01**: Route optimization < 30 seconds
- **SRS-DEL-02**: Real-time GPS tracking < 10s latency

---

## 2. Detailed Scope Statement

### 2.1 In-Scope Deliverables

| Category | Deliverable | Priority |
|----------|-------------|----------|
| Data Models | DeliveryTimeSlot model | P0 |
| Data Models | DeliveryZone geographic configuration | P0 |
| Data Models | DeliveryDriver profile and availability | P0 |
| Data Models | SubscriptionDelivery tracking model | P0 |
| Algorithms | Route optimization (TSP/VRP) | P0 |
| Algorithms | Driver assignment optimization | P0 |
| Integration | Google Maps geocoding integration | P0 |
| Integration | Real-time GPS tracking | P1 |
| Features | Time slot capacity management | P0 |
| Features | Delivery proof capture (photo, signature) | P1 |
| Features | Customer delivery notifications | P0 |
| API | Delivery scheduling REST API | P0 |
| API | Driver mobile app API | P0 |

### 2.2 Out-of-Scope Items

- Vehicle maintenance scheduling
- Driver hiring and HR management
- Fuel cost tracking (future enhancement)
- Multi-depot optimization (single depot for initial launch)
- Third-party logistics integration

### 2.3 Assumptions and Constraints

**Assumptions:**
- Subscription engine (Milestone 121) is complete
- Google Maps API access is available for Bangladesh
- Drivers have smartphones with GPS capability
- Single depot location for initial deployment
- Average 8-hour delivery shifts

**Constraints:**
- Route optimization must complete in < 30 seconds for 100 deliveries
- GPS tracking latency < 10 seconds
- Support for 50+ simultaneous drivers
- Delivery time slots in 2-hour windows

---

## 3. Technical Architecture

### 3.1 Delivery Scheduling Data Model

```sql
-- =====================================================
-- DELIVERY SCHEDULING SCHEMA
-- Milestone 122 - Days 611-620
-- =====================================================

-- =====================================================
-- DELIVERY TIME SLOT TABLE
-- Available delivery windows
-- =====================================================

CREATE TABLE delivery_time_slot (
    id SERIAL PRIMARY KEY,

    -- Basic Information
    name VARCHAR(50) NOT NULL,
    slot_code VARCHAR(20) UNIQUE NOT NULL,

    -- Time Window
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    duration_minutes INTEGER GENERATED ALWAYS AS (
        EXTRACT(EPOCH FROM (end_time - start_time)) / 60
    ) STORED,

    -- Capacity Management
    max_deliveries_per_slot INTEGER DEFAULT 50,
    buffer_minutes INTEGER DEFAULT 10,

    -- Availability
    available_days VARCHAR(20) NOT NULL DEFAULT 'weekdays'
        CHECK (available_days IN ('weekdays', 'weekends', 'all', 'custom')),
    available_monday BOOLEAN DEFAULT TRUE,
    available_tuesday BOOLEAN DEFAULT TRUE,
    available_wednesday BOOLEAN DEFAULT TRUE,
    available_thursday BOOLEAN DEFAULT TRUE,
    available_friday BOOLEAN DEFAULT TRUE,
    available_saturday BOOLEAN DEFAULT FALSE,
    available_sunday BOOLEAN DEFAULT FALSE,

    -- Pricing
    extra_charge DECIMAL(10, 2) DEFAULT 0,
    is_premium BOOLEAN DEFAULT FALSE,

    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    display_order INTEGER DEFAULT 10,

    -- Metadata
    company_id INTEGER REFERENCES res_company(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_time_slot_code ON delivery_time_slot(slot_code);
CREATE INDEX idx_time_slot_active ON delivery_time_slot(is_active);

-- =====================================================
-- DELIVERY ZONE TABLE
-- Geographic delivery areas
-- =====================================================

CREATE TABLE delivery_zone (
    id SERIAL PRIMARY KEY,

    -- Basic Information
    name VARCHAR(100) NOT NULL,
    zone_code VARCHAR(20) UNIQUE NOT NULL,
    description TEXT,

    -- Geographic Definition
    zone_type VARCHAR(20) NOT NULL DEFAULT 'polygon'
        CHECK (zone_type IN ('polygon', 'radius', 'zip_codes')),

    -- For polygon type
    polygon_coordinates JSONB,

    -- For radius type
    center_latitude DECIMAL(10, 7),
    center_longitude DECIMAL(10, 7),
    radius_km DECIMAL(8, 2),

    -- For zip_code type
    zip_codes TEXT[],

    -- Service Configuration
    delivery_fee DECIMAL(10, 2) DEFAULT 0,
    minimum_order_amount DECIMAL(10, 2) DEFAULT 0,
    estimated_delivery_minutes INTEGER DEFAULT 30,

    -- Capacity
    max_daily_deliveries INTEGER DEFAULT 200,

    -- Time Slots
    available_time_slot_ids INTEGER[],

    -- Priority (lower = higher priority for overlapping zones)
    priority INTEGER DEFAULT 10,

    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    is_serviceable BOOLEAN DEFAULT TRUE,

    -- Metadata
    company_id INTEGER REFERENCES res_company(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_delivery_zone_code ON delivery_zone(zone_code);
CREATE INDEX idx_delivery_zone_active ON delivery_zone(is_active, is_serviceable);

-- =====================================================
-- DELIVERY DRIVER TABLE
-- Driver profiles and availability
-- =====================================================

CREATE TABLE delivery_driver (
    id SERIAL PRIMARY KEY,

    -- Employee Link
    employee_id INTEGER REFERENCES hr_employee(id),
    user_id INTEGER REFERENCES res_users(id),

    -- Basic Information
    driver_code VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    photo BYTEA,

    -- License Information
    license_number VARCHAR(50),
    license_expiry DATE,
    license_type VARCHAR(20),

    -- Vehicle Information
    vehicle_type VARCHAR(30) CHECK (
        vehicle_type IN ('motorcycle', 'van', 'truck', 'bicycle', 'auto_rickshaw')
    ),
    vehicle_number VARCHAR(30),
    vehicle_capacity_kg DECIMAL(8, 2),
    vehicle_capacity_liters DECIMAL(10, 2),

    -- Assignment Configuration
    assigned_zone_ids INTEGER[],
    max_deliveries_per_day INTEGER DEFAULT 30,
    preferred_start_time TIME DEFAULT '08:00',
    preferred_end_time TIME DEFAULT '18:00',

    -- Current Status
    current_status VARCHAR(20) DEFAULT 'offline'
        CHECK (current_status IN ('offline', 'available', 'on_delivery', 'break', 'end_of_day')),
    current_latitude DECIMAL(10, 7),
    current_longitude DECIMAL(10, 7),
    last_location_update TIMESTAMP,

    -- Performance Metrics
    total_deliveries INTEGER DEFAULT 0,
    successful_deliveries INTEGER DEFAULT 0,
    failed_deliveries INTEGER DEFAULT 0,
    average_rating DECIMAL(3, 2) DEFAULT 5.0,
    total_ratings INTEGER DEFAULT 0,

    -- App Configuration
    device_token VARCHAR(255),
    app_version VARCHAR(20),
    last_app_login TIMESTAMP,

    -- Status
    is_active BOOLEAN DEFAULT TRUE,

    -- Metadata
    company_id INTEGER REFERENCES res_company(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_driver_code ON delivery_driver(driver_code);
CREATE INDEX idx_driver_status ON delivery_driver(current_status, is_active);
CREATE INDEX idx_driver_zones ON delivery_driver USING GIN(assigned_zone_ids);

-- =====================================================
-- SUBSCRIPTION DELIVERY TABLE
-- Individual delivery records
-- =====================================================

CREATE TABLE subscription_delivery (
    id SERIAL PRIMARY KEY,

    -- Delivery Identifier
    delivery_number VARCHAR(20) UNIQUE NOT NULL,

    -- Subscription Link
    subscription_id INTEGER NOT NULL REFERENCES customer_subscription(id),

    -- Schedule Information
    scheduled_date DATE NOT NULL,
    time_slot_id INTEGER REFERENCES delivery_time_slot(id),
    scheduled_start_time TIME,
    scheduled_end_time TIME,

    -- Location
    delivery_address_id INTEGER REFERENCES res_partner(id),
    delivery_latitude DECIMAL(10, 7),
    delivery_longitude DECIMAL(10, 7),
    zone_id INTEGER REFERENCES delivery_zone(id),
    delivery_instructions TEXT,

    -- Assignment
    driver_id INTEGER REFERENCES delivery_driver(id),
    assigned_at TIMESTAMP,
    route_sequence INTEGER,

    -- Status
    state VARCHAR(20) NOT NULL DEFAULT 'scheduled'
        CHECK (state IN (
            'scheduled', 'assigned', 'picked', 'in_transit',
            'arrived', 'delivered', 'failed', 'skipped', 'cancelled'
        )),

    -- Timing
    picked_at TIMESTAMP,
    departed_at TIMESTAMP,
    arrived_at TIMESTAMP,
    completed_at TIMESTAMP,

    -- Delivery Proof
    proof_type VARCHAR(20) CHECK (
        proof_type IN ('signature', 'photo', 'otp', 'none')
    ),
    signature_image BYTEA,
    delivery_photo BYTEA,
    otp_code VARCHAR(6),
    otp_verified BOOLEAN DEFAULT FALSE,
    receiver_name VARCHAR(100),
    receiver_phone VARCHAR(20),

    -- Failure Information
    failure_reason VARCHAR(50),
    failure_notes TEXT,
    rescheduled_to DATE,

    -- Products
    total_items INTEGER DEFAULT 0,
    total_weight_kg DECIMAL(8, 2),
    total_amount DECIMAL(12, 2),

    -- Customer Feedback
    rating INTEGER CHECK (rating BETWEEN 1 AND 5),
    feedback TEXT,
    feedback_submitted_at TIMESTAMP,

    -- Metadata
    company_id INTEGER REFERENCES res_company(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_delivery_number ON subscription_delivery(delivery_number);
CREATE INDEX idx_delivery_subscription ON subscription_delivery(subscription_id);
CREATE INDEX idx_delivery_date ON subscription_delivery(scheduled_date, state);
CREATE INDEX idx_delivery_driver ON subscription_delivery(driver_id, scheduled_date);
CREATE INDEX idx_delivery_state ON subscription_delivery(state);

-- =====================================================
-- SUBSCRIPTION DELIVERY LINE TABLE
-- Products in each delivery
-- =====================================================

CREATE TABLE subscription_delivery_line (
    id SERIAL PRIMARY KEY,
    delivery_id INTEGER NOT NULL REFERENCES subscription_delivery(id) ON DELETE CASCADE,

    product_id INTEGER NOT NULL REFERENCES product_product(id),
    product_name VARCHAR(255),
    quantity DECIMAL(10, 2) NOT NULL,
    uom_id INTEGER REFERENCES uom_uom(id),
    price_unit DECIMAL(12, 4),
    subtotal DECIMAL(12, 2),

    -- Status
    is_delivered BOOLEAN DEFAULT FALSE,
    delivered_quantity DECIMAL(10, 2),
    shortage_quantity DECIMAL(10, 2),
    shortage_reason VARCHAR(100),

    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_delivery_line_delivery ON subscription_delivery_line(delivery_id);

-- =====================================================
-- DELIVERY ROUTE TABLE
-- Optimized routes for drivers
-- =====================================================

CREATE TABLE delivery_route (
    id SERIAL PRIMARY KEY,

    -- Route Identifier
    route_number VARCHAR(20) UNIQUE NOT NULL,
    route_date DATE NOT NULL,

    -- Assignment
    driver_id INTEGER NOT NULL REFERENCES delivery_driver(id),
    vehicle_type VARCHAR(30),

    -- Route Details
    start_location_lat DECIMAL(10, 7),
    start_location_lng DECIMAL(10, 7),
    end_location_lat DECIMAL(10, 7),
    end_location_lng DECIMAL(10, 7),

    -- Statistics
    total_stops INTEGER DEFAULT 0,
    total_distance_km DECIMAL(10, 2),
    estimated_duration_minutes INTEGER,
    actual_duration_minutes INTEGER,

    -- Status
    state VARCHAR(20) DEFAULT 'planned'
        CHECK (state IN ('planned', 'in_progress', 'completed', 'cancelled')),
    started_at TIMESTAMP,
    completed_at TIMESTAMP,

    -- Optimization
    optimization_score DECIMAL(5, 2),
    is_optimized BOOLEAN DEFAULT FALSE,
    optimization_method VARCHAR(50),

    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_route_date ON delivery_route(route_date, driver_id);
CREATE INDEX idx_route_state ON delivery_route(state);

-- =====================================================
-- DELIVERY ROUTE STOP TABLE
-- Individual stops in a route
-- =====================================================

CREATE TABLE delivery_route_stop (
    id SERIAL PRIMARY KEY,
    route_id INTEGER NOT NULL REFERENCES delivery_route(id) ON DELETE CASCADE,
    delivery_id INTEGER NOT NULL REFERENCES subscription_delivery(id),

    -- Sequence
    stop_sequence INTEGER NOT NULL,

    -- Location
    latitude DECIMAL(10, 7),
    longitude DECIMAL(10, 7),

    -- Timing
    estimated_arrival TIME,
    actual_arrival TIMESTAMP,
    estimated_service_minutes INTEGER DEFAULT 5,
    actual_service_minutes INTEGER,

    -- Distance from previous stop
    distance_from_previous_km DECIMAL(8, 2),
    duration_from_previous_minutes INTEGER,

    -- Status
    state VARCHAR(20) DEFAULT 'pending'
        CHECK (state IN ('pending', 'arrived', 'completed', 'skipped')),

    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_route_stop_route ON delivery_route_stop(route_id);
CREATE INDEX idx_route_stop_sequence ON delivery_route_stop(route_id, stop_sequence);

-- =====================================================
-- TIME SLOT CAPACITY TABLE
-- Daily capacity tracking
-- =====================================================

CREATE TABLE time_slot_capacity (
    id SERIAL PRIMARY KEY,
    time_slot_id INTEGER NOT NULL REFERENCES delivery_time_slot(id),
    zone_id INTEGER REFERENCES delivery_zone(id),
    capacity_date DATE NOT NULL,

    -- Capacity
    max_capacity INTEGER NOT NULL,
    booked_count INTEGER DEFAULT 0,
    available_count INTEGER GENERATED ALWAYS AS (max_capacity - booked_count) STORED,

    -- Status
    is_available BOOLEAN DEFAULT TRUE,
    blocked_reason VARCHAR(100),

    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(time_slot_id, zone_id, capacity_date)
);

CREATE INDEX idx_slot_capacity_date ON time_slot_capacity(capacity_date, time_slot_id);
```

### 3.2 Route Optimization Service

```python
# odoo/addons/smart_dairy_subscription/services/route_optimizer.py
"""
Route Optimization Service
Milestone 122 - Days 611-620
Implements TSP/VRP algorithms using Google OR-Tools
"""

from ortools.constraint_solver import routing_enums_pb2
from ortools.constraint_solver import pywrapcp
import numpy as np
from math import radians, cos, sin, asin, sqrt
from typing import List, Dict, Tuple, Optional
from dataclasses import dataclass
from datetime import datetime, time, timedelta
import logging

_logger = logging.getLogger(__name__)


@dataclass
class DeliveryStop:
    """Represents a delivery stop for route optimization"""
    delivery_id: int
    latitude: float
    longitude: float
    time_window_start: Optional[time] = None
    time_window_end: Optional[time] = None
    service_time_minutes: int = 5
    demand: int = 1  # Number of items or weight
    priority: int = 1  # Higher = more important


@dataclass
class Vehicle:
    """Represents a delivery vehicle/driver"""
    driver_id: int
    capacity: int
    start_lat: float
    start_lng: float
    end_lat: Optional[float] = None
    end_lng: Optional[float] = None
    max_distance_km: float = 100
    max_duration_minutes: int = 480  # 8 hours


@dataclass
class OptimizedRoute:
    """Result of route optimization"""
    driver_id: int
    stops: List[Tuple[int, int]]  # (delivery_id, sequence)
    total_distance_km: float
    estimated_duration_minutes: int
    optimization_score: float


class RouteOptimizer:
    """
    Route Optimization using Google OR-Tools

    Implements:
    - TSP (Traveling Salesman Problem) for single vehicle
    - VRP (Vehicle Routing Problem) for multiple vehicles
    - CVRP (Capacitated VRP) with vehicle capacity constraints
    - VRPTW (VRP with Time Windows) for time slot constraints
    """

    def __init__(
        self,
        depot_location: Tuple[float, float],
        vehicles: List[Vehicle],
        deliveries: List[DeliveryStop],
        use_time_windows: bool = True,
        use_capacity_constraints: bool = True
    ):
        """
        Initialize the route optimizer

        :param depot_location: (latitude, longitude) of the depot
        :param vehicles: List of available vehicles/drivers
        :param deliveries: List of delivery stops
        :param use_time_windows: Whether to consider time window constraints
        :param use_capacity_constraints: Whether to consider capacity constraints
        """
        self.depot_location = depot_location
        self.vehicles = vehicles
        self.deliveries = deliveries
        self.use_time_windows = use_time_windows
        self.use_capacity_constraints = use_capacity_constraints

        # Build location list: depot + deliveries
        self.locations = [depot_location] + [
            (d.latitude, d.longitude) for d in deliveries
        ]

        self.distance_matrix = self._build_distance_matrix()

    def _haversine_distance(
        self,
        lat1: float, lon1: float,
        lat2: float, lon2: float
    ) -> float:
        """
        Calculate the great circle distance between two points
        on the earth (specified in decimal degrees)

        :return: Distance in kilometers
        """
        # Convert to radians
        lon1, lat1, lon2, lat2 = map(radians, [lon1, lat1, lon2, lat2])

        # Haversine formula
        dlon = lon2 - lon1
        dlat = lat2 - lat1
        a = sin(dlat/2)**2 + cos(lat1) * cos(lat2) * sin(dlon/2)**2
        c = 2 * asin(sqrt(a))

        # Radius of earth in kilometers
        r = 6371

        return c * r

    def _build_distance_matrix(self) -> np.ndarray:
        """
        Build a distance matrix between all locations

        :return: 2D numpy array of distances in meters (for OR-Tools)
        """
        n = len(self.locations)
        matrix = np.zeros((n, n))

        for i in range(n):
            for j in range(n):
                if i != j:
                    distance = self._haversine_distance(
                        self.locations[i][0], self.locations[i][1],
                        self.locations[j][0], self.locations[j][1]
                    )
                    # Convert to meters and add some buffer for actual road distance
                    # (straight line * 1.3 is a common approximation)
                    matrix[i][j] = int(distance * 1000 * 1.3)

        return matrix

    def _time_to_minutes(self, t: time) -> int:
        """Convert time object to minutes from midnight"""
        return t.hour * 60 + t.minute

    def optimize(self, time_limit_seconds: int = 30) -> List[OptimizedRoute]:
        """
        Run the route optimization

        :param time_limit_seconds: Maximum time to spend on optimization
        :return: List of optimized routes per vehicle
        """
        if not self.deliveries:
            return []

        num_locations = len(self.locations)
        num_vehicles = len(self.vehicles)

        # Create the routing index manager
        # Node 0 is the depot, nodes 1 to n are delivery locations
        manager = pywrapcp.RoutingIndexManager(
            num_locations,
            num_vehicles,
            0  # depot index
        )

        # Create the routing model
        routing = pywrapcp.RoutingModel(manager)

        # ================================================
        # Define Distance Callback
        # ================================================
        def distance_callback(from_index, to_index):
            """Returns the distance between two nodes."""
            from_node = manager.IndexToNode(from_index)
            to_node = manager.IndexToNode(to_index)
            return int(self.distance_matrix[from_node][to_node])

        transit_callback_index = routing.RegisterTransitCallback(distance_callback)

        # Define cost of each arc (distance)
        routing.SetArcCostEvaluatorOfAllVehicles(transit_callback_index)

        # ================================================
        # Add Capacity Constraints
        # ================================================
        if self.use_capacity_constraints:
            def demand_callback(from_index):
                """Returns the demand of the node."""
                from_node = manager.IndexToNode(from_index)
                if from_node == 0:
                    return 0  # Depot has no demand
                return self.deliveries[from_node - 1].demand

            demand_callback_index = routing.RegisterUnaryTransitCallback(demand_callback)

            routing.AddDimensionWithVehicleCapacity(
                demand_callback_index,
                0,  # null capacity slack
                [v.capacity for v in self.vehicles],  # vehicle capacities
                True,  # start cumul to zero
                'Capacity'
            )

        # ================================================
        # Add Distance Constraint
        # ================================================
        routing.AddDimension(
            transit_callback_index,
            0,  # no slack
            int(max(v.max_distance_km for v in self.vehicles) * 1000 * 1.3),  # max distance in meters
            True,  # start cumul to zero
            'Distance'
        )

        # ================================================
        # Add Time Window Constraints
        # ================================================
        if self.use_time_windows:
            def time_callback(from_index, to_index):
                """Returns the travel time between two nodes plus service time."""
                from_node = manager.IndexToNode(from_index)
                to_node = manager.IndexToNode(to_index)

                # Assume average speed of 30 km/h in urban areas
                distance_km = self.distance_matrix[from_node][to_node] / 1000 / 1.3
                travel_time_minutes = distance_km / 30 * 60

                # Add service time at destination
                if to_node > 0:
                    service_time = self.deliveries[to_node - 1].service_time_minutes
                else:
                    service_time = 0

                return int(travel_time_minutes + service_time)

            time_callback_index = routing.RegisterTransitCallback(time_callback)

            routing.AddDimension(
                time_callback_index,
                30,  # allow waiting time of 30 minutes
                int(max(v.max_duration_minutes for v in self.vehicles)),  # max time per vehicle
                False,  # don't force start cumul to zero
                'Time'
            )

            time_dimension = routing.GetDimensionOrDie('Time')

            # Add time window constraints for each delivery
            for location_idx in range(1, num_locations):
                delivery = self.deliveries[location_idx - 1]
                index = manager.NodeToIndex(location_idx)

                if delivery.time_window_start and delivery.time_window_end:
                    start_minutes = self._time_to_minutes(delivery.time_window_start)
                    end_minutes = self._time_to_minutes(delivery.time_window_end)
                    time_dimension.CumulVar(index).SetRange(start_minutes, end_minutes)

        # ================================================
        # Set Search Parameters
        # ================================================
        search_parameters = pywrapcp.DefaultRoutingSearchParameters()

        # First solution strategy
        search_parameters.first_solution_strategy = (
            routing_enums_pb2.FirstSolutionStrategy.PATH_CHEAPEST_ARC
        )

        # Local search metaheuristic
        search_parameters.local_search_metaheuristic = (
            routing_enums_pb2.LocalSearchMetaheuristic.GUIDED_LOCAL_SEARCH
        )

        # Time limit
        search_parameters.time_limit.FromSeconds(time_limit_seconds)

        # ================================================
        # Solve
        # ================================================
        _logger.info(f"Starting route optimization for {len(self.deliveries)} deliveries, {num_vehicles} vehicles")
        solution = routing.SolveWithParameters(search_parameters)

        if not solution:
            _logger.warning("No solution found for route optimization")
            return []

        # ================================================
        # Extract Routes
        # ================================================
        routes = []

        for vehicle_id in range(num_vehicles):
            route_stops = []
            total_distance = 0
            total_time = 0

            index = routing.Start(vehicle_id)
            sequence = 0

            while not routing.IsEnd(index):
                node_index = manager.IndexToNode(index)

                if node_index != 0:  # Skip depot
                    delivery = self.deliveries[node_index - 1]
                    route_stops.append((delivery.delivery_id, sequence))
                    sequence += 1

                previous_index = index
                index = solution.Value(routing.NextVar(index))

                # Calculate distance for this arc
                total_distance += routing.GetArcCostForVehicle(
                    previous_index, index, vehicle_id
                )

            # Only create route if it has stops
            if route_stops:
                # Convert distance from meters to km
                total_distance_km = total_distance / 1000

                # Estimate duration (distance / avg speed + service times)
                service_time = len(route_stops) * 5  # 5 min average per stop
                travel_time = total_distance_km / 30 * 60  # 30 km/h average
                estimated_duration = int(travel_time + service_time)

                # Calculate optimization score (lower distance = better)
                # Normalized to 0-100, higher is better
                optimal_theoretical = len(route_stops) * 1.5  # ~1.5 km between stops
                optimization_score = min(100, (optimal_theoretical / total_distance_km) * 100)

                routes.append(OptimizedRoute(
                    driver_id=self.vehicles[vehicle_id].driver_id,
                    stops=route_stops,
                    total_distance_km=round(total_distance_km, 2),
                    estimated_duration_minutes=estimated_duration,
                    optimization_score=round(optimization_score, 2)
                ))

        _logger.info(f"Route optimization complete: {len(routes)} routes generated")

        return routes

    @classmethod
    def optimize_for_date(
        cls,
        env,
        delivery_date,
        zone_id: Optional[int] = None
    ) -> List[OptimizedRoute]:
        """
        High-level method to optimize routes for a specific date

        :param env: Odoo environment
        :param delivery_date: Date to optimize
        :param zone_id: Optional zone filter
        :return: List of optimized routes
        """
        from datetime import datetime, time

        # Get depot location (company address)
        company = env.company
        depot_location = (
            company.partner_id.partner_latitude or 23.8103,  # Dhaka default
            company.partner_id.partner_longitude or 90.4125
        )

        # Get available drivers
        domain = [('is_active', '=', True), ('current_status', '!=', 'offline')]
        if zone_id:
            domain.append(('assigned_zone_ids', '@>', [zone_id]))

        drivers = env['delivery.driver'].search(domain)

        vehicles = [
            Vehicle(
                driver_id=driver.id,
                capacity=driver.max_deliveries_per_day,
                start_lat=depot_location[0],
                start_lng=depot_location[1],
                max_distance_km=100,
                max_duration_minutes=480
            )
            for driver in drivers
        ]

        if not vehicles:
            _logger.warning("No available drivers for route optimization")
            return []

        # Get scheduled deliveries
        delivery_domain = [
            ('scheduled_date', '=', delivery_date),
            ('state', '=', 'scheduled')
        ]
        if zone_id:
            delivery_domain.append(('zone_id', '=', zone_id))

        deliveries_records = env['subscription.delivery'].search(delivery_domain)

        deliveries = []
        for delivery in deliveries_records:
            # Get time window from time slot
            time_start = None
            time_end = None

            if delivery.time_slot_id:
                time_start = time(
                    hour=int(delivery.time_slot_id.start_time),
                    minute=int((delivery.time_slot_id.start_time % 1) * 60)
                )
                time_end = time(
                    hour=int(delivery.time_slot_id.end_time),
                    minute=int((delivery.time_slot_id.end_time % 1) * 60)
                )

            deliveries.append(DeliveryStop(
                delivery_id=delivery.id,
                latitude=delivery.delivery_latitude or depot_location[0],
                longitude=delivery.delivery_longitude or depot_location[1],
                time_window_start=time_start,
                time_window_end=time_end,
                service_time_minutes=5,
                demand=1
            ))

        if not deliveries:
            _logger.info(f"No deliveries to optimize for {delivery_date}")
            return []

        # Run optimization
        optimizer = cls(
            depot_location=depot_location,
            vehicles=vehicles,
            deliveries=deliveries,
            use_time_windows=True,
            use_capacity_constraints=True
        )

        return optimizer.optimize(time_limit_seconds=30)
```

### 3.3 Delivery Time Slot Model

```python
# odoo/addons/smart_dairy_subscription/models/delivery_time_slot.py
"""
Delivery Time Slot Model
Milestone 122 - Days 611-620
"""

from odoo import models, fields, api
from odoo.exceptions import ValidationError, UserError
from datetime import datetime, date, timedelta
import logging

_logger = logging.getLogger(__name__)


class DeliveryTimeSlot(models.Model):
    _name = 'delivery.time.slot'
    _description = 'Delivery Time Slot'
    _order = 'start_time'

    # ==========================================
    # Basic Information
    # ==========================================

    name = fields.Char(
        string='Slot Name',
        required=True,
        help='e.g., "Morning (8AM-10AM)"'
    )

    slot_code = fields.Char(
        string='Slot Code',
        required=True,
        copy=False
    )

    # ==========================================
    # Time Configuration
    # ==========================================

    start_time = fields.Float(
        string='Start Time',
        required=True,
        help='Start time in 24-hour format (e.g., 8.0 for 8:00 AM)'
    )

    end_time = fields.Float(
        string='End Time',
        required=True,
        help='End time in 24-hour format (e.g., 10.0 for 10:00 AM)'
    )

    duration_minutes = fields.Integer(
        string='Duration (minutes)',
        compute='_compute_duration',
        store=True
    )

    start_time_display = fields.Char(
        string='Start Time Display',
        compute='_compute_time_display'
    )

    end_time_display = fields.Char(
        string='End Time Display',
        compute='_compute_time_display'
    )

    # ==========================================
    # Capacity Management
    # ==========================================

    max_deliveries = fields.Integer(
        string='Max Deliveries per Slot',
        default=50,
        help='Maximum deliveries that can be scheduled in this time slot'
    )

    buffer_minutes = fields.Integer(
        string='Buffer Time (minutes)',
        default=10,
        help='Buffer time between deliveries'
    )

    # ==========================================
    # Availability
    # ==========================================

    available_days = fields.Selection([
        ('weekdays', 'Weekdays Only (Mon-Fri)'),
        ('weekends', 'Weekends Only (Sat-Sun)'),
        ('all', 'All Days'),
        ('custom', 'Custom Days'),
    ], string='Available Days', default='weekdays', required=True)

    available_monday = fields.Boolean(string='Monday', default=True)
    available_tuesday = fields.Boolean(string='Tuesday', default=True)
    available_wednesday = fields.Boolean(string='Wednesday', default=True)
    available_thursday = fields.Boolean(string='Thursday', default=True)
    available_friday = fields.Boolean(string='Friday', default=True)
    available_saturday = fields.Boolean(string='Saturday', default=False)
    available_sunday = fields.Boolean(string='Sunday', default=False)

    # ==========================================
    # Geographic Coverage
    # ==========================================

    zone_ids = fields.Many2many(
        'delivery.zone',
        'time_slot_zone_rel',
        'time_slot_id',
        'zone_id',
        string='Delivery Zones',
        help='Zones where this time slot is available (empty = all zones)'
    )

    # ==========================================
    # Pricing
    # ==========================================

    extra_charge = fields.Float(
        string='Extra Charge',
        default=0.0,
        help='Additional delivery charge for this time slot'
    )

    is_premium = fields.Boolean(
        string='Premium Slot',
        default=False,
        help='Premium time slots may have limited availability or higher fees'
    )

    # ==========================================
    # Status
    # ==========================================

    is_active = fields.Boolean(
        string='Active',
        default=True
    )

    display_order = fields.Integer(
        string='Display Order',
        default=10
    )

    # ==========================================
    # Company
    # ==========================================

    company_id = fields.Many2one(
        'res.company',
        string='Company',
        default=lambda self: self.env.company
    )

    # ==========================================
    # Constraints
    # ==========================================

    _sql_constraints = [
        ('slot_code_unique', 'UNIQUE(slot_code, company_id)', 'Slot code must be unique!'),
        ('time_order', 'CHECK(end_time > start_time)', 'End time must be after start time!'),
    ]

    # ==========================================
    # Computed Fields
    # ==========================================

    @api.depends('start_time', 'end_time')
    def _compute_duration(self):
        for slot in self:
            slot.duration_minutes = int((slot.end_time - slot.start_time) * 60)

    def _compute_time_display(self):
        for slot in self:
            slot.start_time_display = self._float_to_time_str(slot.start_time)
            slot.end_time_display = self._float_to_time_str(slot.end_time)

    @staticmethod
    def _float_to_time_str(float_time):
        """Convert float time to string (e.g., 8.5 -> "8:30 AM")"""
        hours = int(float_time)
        minutes = int((float_time % 1) * 60)
        period = 'AM' if hours < 12 else 'PM'
        display_hours = hours if hours <= 12 else hours - 12
        if display_hours == 0:
            display_hours = 12
        return f"{display_hours}:{minutes:02d} {period}"

    # ==========================================
    # Validation
    # ==========================================

    @api.constrains('start_time', 'end_time')
    def _check_time_range(self):
        for slot in self:
            if slot.start_time < 0 or slot.start_time >= 24:
                raise ValidationError('Start time must be between 0:00 and 23:59')
            if slot.end_time < 0 or slot.end_time > 24:
                raise ValidationError('End time must be between 0:00 and 24:00')
            if slot.end_time <= slot.start_time:
                raise ValidationError('End time must be after start time')

    # ==========================================
    # Business Methods
    # ==========================================

    def is_available_on_date(self, check_date):
        """
        Check if this time slot is available on a specific date

        :param check_date: date to check
        :return: boolean
        """
        self.ensure_one()

        weekday = check_date.weekday()  # 0 = Monday, 6 = Sunday

        if self.available_days == 'weekdays':
            return weekday < 5  # Monday to Friday

        elif self.available_days == 'weekends':
            return weekday >= 5  # Saturday and Sunday

        elif self.available_days == 'all':
            return True

        else:  # custom
            day_fields = [
                'available_monday', 'available_tuesday', 'available_wednesday',
                'available_thursday', 'available_friday', 'available_saturday',
                'available_sunday'
            ]
            return getattr(self, day_fields[weekday])

    def get_available_capacity(self, delivery_date, zone_id=None):
        """
        Get available delivery capacity for a specific date and optional zone

        :param delivery_date: date to check
        :param zone_id: optional zone ID
        :return: dict with capacity info
        """
        self.ensure_one()

        if not self.is_available_on_date(delivery_date):
            return {
                'max_capacity': 0,
                'booked': 0,
                'available': 0,
                'is_available': False
            }

        # Check capacity record
        domain = [
            ('time_slot_id', '=', self.id),
            ('capacity_date', '=', delivery_date)
        ]
        if zone_id:
            domain.append(('zone_id', '=', zone_id))
        else:
            domain.append(('zone_id', '=', False))

        capacity = self.env['time.slot.capacity'].search(domain, limit=1)

        if capacity:
            return {
                'max_capacity': capacity.max_capacity,
                'booked': capacity.booked_count,
                'available': capacity.available_count,
                'is_available': capacity.is_available and capacity.available_count > 0
            }

        # Default capacity
        booked = self.env['subscription.delivery'].search_count([
            ('time_slot_id', '=', self.id),
            ('scheduled_date', '=', delivery_date),
            ('state', 'in', ['scheduled', 'assigned', 'picked', 'in_transit'])
        ])

        available = self.max_deliveries - booked

        return {
            'max_capacity': self.max_deliveries,
            'booked': booked,
            'available': max(0, available),
            'is_available': available > 0
        }

    def book_slot(self, delivery_date, zone_id=None):
        """
        Book a slot for a delivery (increment booked count)

        :param delivery_date: date to book
        :param zone_id: optional zone ID
        :return: boolean success
        """
        self.ensure_one()

        capacity = self.get_available_capacity(delivery_date, zone_id)

        if not capacity['is_available']:
            raise UserError(f'Time slot {self.name} is not available on {delivery_date}')

        # Update or create capacity record
        domain = [
            ('time_slot_id', '=', self.id),
            ('capacity_date', '=', delivery_date)
        ]
        if zone_id:
            domain.append(('zone_id', '=', zone_id))
        else:
            domain.append(('zone_id', '=', False))

        capacity_record = self.env['time.slot.capacity'].search(domain, limit=1)

        if capacity_record:
            capacity_record.booked_count += 1
        else:
            self.env['time.slot.capacity'].create({
                'time_slot_id': self.id,
                'zone_id': zone_id,
                'capacity_date': delivery_date,
                'max_capacity': self.max_deliveries,
                'booked_count': 1,
            })

        return True

    @api.model
    def get_available_slots_for_date(self, delivery_date, zone_id=None, partner_id=None):
        """
        Get all available time slots for a date

        :param delivery_date: date to check
        :param zone_id: optional zone ID
        :param partner_id: optional partner for personalization
        :return: list of dicts with slot info
        """
        slots = self.search([('is_active', '=', True)])

        available_slots = []

        for slot in slots:
            capacity = slot.get_available_capacity(delivery_date, zone_id)

            if capacity['is_available']:
                available_slots.append({
                    'id': slot.id,
                    'name': slot.name,
                    'start_time': slot.start_time_display,
                    'end_time': slot.end_time_display,
                    'extra_charge': slot.extra_charge,
                    'is_premium': slot.is_premium,
                    'available_capacity': capacity['available'],
                    'total_capacity': capacity['max_capacity'],
                })

        return sorted(available_slots, key=lambda x: x['id'])


class TimeSlotCapacity(models.Model):
    _name = 'time.slot.capacity'
    _description = 'Time Slot Capacity Tracking'

    time_slot_id = fields.Many2one(
        'delivery.time.slot',
        string='Time Slot',
        required=True,
        ondelete='cascade'
    )

    zone_id = fields.Many2one(
        'delivery.zone',
        string='Delivery Zone'
    )

    capacity_date = fields.Date(
        string='Date',
        required=True
    )

    max_capacity = fields.Integer(
        string='Max Capacity',
        required=True
    )

    booked_count = fields.Integer(
        string='Booked',
        default=0
    )

    available_count = fields.Integer(
        string='Available',
        compute='_compute_available',
        store=True
    )

    is_available = fields.Boolean(
        string='Available',
        default=True
    )

    blocked_reason = fields.Char(
        string='Blocked Reason'
    )

    _sql_constraints = [
        ('unique_slot_zone_date', 'UNIQUE(time_slot_id, zone_id, capacity_date)',
         'Capacity record already exists for this slot, zone, and date!'),
    ]

    @api.depends('max_capacity', 'booked_count')
    def _compute_available(self):
        for record in self:
            record.available_count = max(0, record.max_capacity - record.booked_count)
```

---

## 4. Day-by-Day Implementation Plan

### Day 611: Time Slot & Zone Models

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create DeliveryTimeSlot model | `models/delivery_time_slot.py` |
| 3-6h | Create DeliveryZone model | `models/delivery_zone.py` |
| 6-8h | Implement zone geographic validation | Zone validation methods |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create TimeSlotCapacity model | `models/time_slot_capacity.py` |
| 2-4h | Implement capacity booking logic | Capacity methods |
| 4-6h | Google Maps geocoding integration | Geocoding service |
| 6-8h | Write zone/slot tests | `tests/test_time_slots.py` |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create time slot form/list views | `views/delivery_time_slot_views.xml` |
| 3-5h | Create zone management views | `views/delivery_zone_views.xml` |
| 5-7h | Build time slot selection widget | OWL component |
| 7-8h | Add menu items | Menu configuration |

**Daily Deliverables:**
- [ ] DeliveryTimeSlot model complete
- [ ] DeliveryZone model with geographic support
- [ ] Capacity tracking working
- [ ] Geocoding integration functional

---

### Day 612: Driver Management

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create DeliveryDriver model | `models/delivery_driver.py` |
| 3-5h | Implement driver availability logic | Availability methods |
| 5-7h | Driver zone assignment | Zone assignment |
| 7-8h | Driver performance metrics | Metric calculations |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Driver authentication setup | Driver user accounts |
| 2-4h | Implement driver status tracking | Status management |
| 4-6h | Device token management | FCM token storage |
| 6-8h | Write driver tests | `tests/test_driver.py` |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create driver profile views | `views/delivery_driver_views.xml` |
| 3-5h | Build driver dashboard widget | Dashboard component |
| 5-7h | Design driver availability calendar | Calendar widget |
| 7-8h | Mobile-responsive driver list | Responsive design |

**Daily Deliverables:**
- [ ] DeliveryDriver model complete
- [ ] Driver availability tracking working
- [ ] Zone assignment functional
- [ ] Driver dashboard implemented

---

### Day 613: Subscription Delivery Model

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create SubscriptionDelivery model | `models/subscription_delivery.py` |
| 3-5h | Create SubscriptionDeliveryLine model | Line items model |
| 5-7h | Implement delivery state machine | State transitions |
| 7-8h | Delivery scheduling from subscriptions | Auto-scheduling |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Delivery number sequence | Sequence configuration |
| 2-4h | Implement delivery proof capture | Proof of delivery |
| 4-6h | OTP verification system | OTP service |
| 6-8h | Integration with subscription module | Module integration |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create delivery form views | `views/subscription_delivery_views.xml` |
| 3-5h | Build delivery timeline component | Timeline widget |
| 5-7h | Design delivery status badges | Status components |
| 7-8h | Create delivery calendar view | Calendar view |

**Daily Deliverables:**
- [ ] SubscriptionDelivery model complete
- [ ] Delivery line items working
- [ ] State machine implemented
- [ ] Proof of delivery capture ready

---

### Day 614: Route Optimization Engine

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-4h | Implement RouteOptimizer class | `services/route_optimizer.py` |
| 4-6h | Build distance matrix calculation | Haversine formula |
| 6-8h | Integrate OR-Tools VRP solver | TSP/VRP integration |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Add time window constraints | VRPTW support |
| 2-4h | Add capacity constraints | CVRP support |
| 4-6h | Implement optimization scoring | Route scoring |
| 6-8h | Performance optimization | Algorithm tuning |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Design route visualization UI | Route map component |
| 3-5h | Build optimization progress indicator | Progress UI |
| 5-7h | Create route comparison view | Comparison widget |
| 7-8h | Google Maps integration for display | Map integration |

**Daily Deliverables:**
- [ ] Route optimization working for single vehicle (TSP)
- [ ] Multi-vehicle routing (VRP) implemented
- [ ] Time and capacity constraints supported
- [ ] Optimization completes in < 30 seconds

---

### Day 615: Route Model & Assignment

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create DeliveryRoute model | `models/delivery_route.py` |
| 3-5h | Create DeliveryRouteStop model | Route stops model |
| 5-7h | Implement route generation from optimization | Route creation |
| 7-8h | Automated driver assignment | Assignment logic |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Daily route scheduling cron | Scheduled job |
| 2-4h | Route re-optimization on changes | Re-optimization |
| 4-6h | Manual route override support | Manual adjustments |
| 6-8h | Route assignment notifications | Driver notifications |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create route management views | `views/delivery_route_views.xml` |
| 3-5h | Build drag-drop route editor | Route editor |
| 5-7h | Design route stop list component | Stop list UI |
| 7-8h | Create route print view | Print template |

**Daily Deliverables:**
- [ ] DeliveryRoute and RouteStop models complete
- [ ] Routes generated from optimization results
- [ ] Driver assignment automated
- [ ] Route management UI functional

---

### Day 616: Real-Time Tracking

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create tracking service | `services/tracking_service.py` |
| 3-5h | Implement location update endpoint | Location API |
| 5-7h | ETA calculation algorithm | ETA service |
| 7-8h | Tracking history storage | History logging |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | WebSocket setup for real-time updates | WebSocket server |
| 2-4h | Firebase integration for mobile tracking | Firebase setup |
| 4-6h | Geofencing for arrival detection | Geofence service |
| 6-8h | Tracking accuracy validation | Accuracy tests |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Build real-time map component | Map tracking UI |
| 3-5h | Create driver location marker | Map markers |
| 5-7h | Design ETA display widget | ETA component |
| 7-8h | Mobile tracking view | Mobile UI |

**Daily Deliverables:**
- [ ] Real-time location updates working
- [ ] ETA calculation accurate
- [ ] WebSocket updates streaming
- [ ] Map tracking functional

---

### Day 617: Delivery Notifications

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create notification service | `services/delivery_notifications.py` |
| 3-5h | Implement notification templates | Notification templates |
| 5-7h | Multi-channel orchestration (SMS, push, email) | Channel logic |
| 7-8h | Notification preference handling | Preferences |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | SMS gateway integration | SMS service |
| 2-4h | Push notification via Firebase | FCM integration |
| 4-6h | Email notification templates | Email templates |
| 6-8h | Notification delivery tracking | Delivery tracking |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create notification history UI | History view |
| 3-5h | Build notification preference UI | Preference settings |
| 5-7h | Design notification templates | Template design |
| 7-8h | Mobile notification handling | Mobile handling |

**Daily Deliverables:**
- [ ] SMS notifications sending
- [ ] Push notifications working
- [ ] Email notifications delivered
- [ ] Notification preferences configurable

---

### Day 618: Delivery API & Driver App Backend

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create delivery scheduling API | `controllers/delivery_api.py` |
| 3-5h | Implement time slot selection API | Slot selection endpoint |
| 5-7h | Create delivery tracking API | Tracking endpoints |
| 7-8h | API documentation | OpenAPI spec |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create driver app API | `controllers/driver_app_api.py` |
| 3-5h | Implement route retrieval for drivers | Driver route API |
| 5-7h | Delivery status update endpoints | Status update API |
| 7-8h | Proof of delivery upload API | POD upload |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-4h | Create API documentation page | API docs UI |
| 4-6h | Build Postman collection | Postman collection |
| 6-8h | API testing automation | API tests |

**Daily Deliverables:**
- [ ] Delivery scheduling API complete
- [ ] Driver app API endpoints working
- [ ] Status updates functional
- [ ] API documentation published

---

### Day 619: Driver Mobile Integration

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Optimize APIs for mobile | Mobile optimization |
| 3-5h | Implement offline sync support | Sync endpoints |
| 5-7h | Background location service | Background tracking |
| 7-8h | Battery-efficient tracking | Efficiency tuning |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | JWT authentication for drivers | Driver auth |
| 2-4h | Image compression for proofs | Image handling |
| 4-6h | Conflict resolution for offline | Conflict handling |
| 6-8h | Mobile API security testing | Security tests |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-4h | Create Flutter driver service | Dart service |
| 4-6h | Build route list widget | Flutter widget |
| 6-8h | Design delivery completion flow | Completion UI |

**Daily Deliverables:**
- [ ] Mobile-optimized APIs
- [ ] Offline sync support
- [ ] Flutter service layer
- [ ] Driver app integration tested

---

### Day 620: Testing & Documentation

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Complete unit test suite | 90%+ coverage |
| 3-5h | Route optimization benchmarks | Performance tests |
| 5-7h | Integration testing | Integration tests |
| 7-8h | Bug fixes | Bug fixes |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Security testing | Security scan |
| 2-4h | Load testing delivery APIs | Load tests |
| 4-6h | End-to-end delivery flow tests | E2E tests |
| 6-8h | CI/CD finalization | Pipeline complete |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | UI testing | UI tests |
| 3-5h | Write user documentation | User guide |
| 5-7h | Create technical documentation | Technical docs |
| 7-8h | Milestone review and sign-off | Sign-off |

**Daily Deliverables:**
- [ ] 90%+ test coverage achieved
- [ ] Route optimization < 30s validated
- [ ] Documentation complete
- [ ] Milestone 122 sign-off

---

## 5. Success Criteria

### 5.1 Functional Criteria

- [ ] Time slots configurable with capacity limits
- [ ] Geographic zones defined with boundaries
- [ ] Route optimization completes in < 30 seconds for 100 deliveries
- [ ] Drivers receive optimized routes on mobile
- [ ] Real-time tracking shows delivery progress
- [ ] Delivery proof captured (photo/signature/OTP)
- [ ] Notifications sent at each delivery stage
- [ ] Customer can track their delivery

### 5.2 Technical Criteria

- [ ] Route optimization < 30 seconds (100 deliveries)
- [ ] GPS tracking latency < 10 seconds
- [ ] API response time < 500ms (P95)
- [ ] Support 50+ simultaneous drivers
- [ ] 90%+ unit test coverage

### 5.3 Business Criteria

- [ ] 90%+ on-time delivery rate achievable
- [ ] 25% route efficiency improvement
- [ ] Driver productivity 30+ deliveries/day
- [ ] Customer delivery visibility

---

## 6. Risk Register

| Risk ID | Description | Impact | Probability | Mitigation |
|---------|-------------|--------|-------------|------------|
| R122-01 | OR-Tools performance at scale | High | Medium | Algorithm tuning, caching |
| R122-02 | GPS accuracy in urban areas | Medium | Medium | Multiple location sources |
| R122-03 | Driver app adoption | High | Medium | Simple UX, training |
| R122-04 | Network connectivity issues | Medium | High | Offline mode support |
| R122-05 | Time slot overbooking | Medium | Low | Real-time capacity checks |

---

**END OF MILESTONE 122: DELIVERY SCHEDULING**

---

*Document prepared for Smart Dairy Digital Smart Portal + ERP System*
*Phase 12: Commerce — Subscription & Automation*
*Milestone 122: Delivery Scheduling (Days 611-620)*
*Version 1.0 — 2026-02-04*
