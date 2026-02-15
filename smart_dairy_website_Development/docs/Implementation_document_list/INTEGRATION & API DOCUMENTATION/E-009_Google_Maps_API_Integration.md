# E-009: Google Maps API Integration

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | E-009 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Integration Engineer |
| **Owner** | Tech Lead |
| **Reviewer** | CTO |
| **Status** | Draft |
| **Classification** | Internal |

### Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-31 | Integration Engineer | Initial document creation |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [API Setup](#2-api-setup)
3. [Enabled APIs](#3-enabled-apis)
4. [Maps JavaScript API](#4-maps-javascript-api)
5. [Geocoding API](#5-geocoding-api)
6. [Directions API](#6-directions-api)
7. [Distance Matrix API](#7-distance-matrix-api)
8. [Places API](#8-places-api)
9. [Implementation](#9-implementation)
10. [Cost Optimization](#10-cost-optimization)
11. [Error Handling](#11-error-handling)
12. [Appendices](#12-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides comprehensive guidelines for integrating Google Maps APIs into the Smart Dairy Ltd. digital ecosystem. The integration enables location-based services across mobile applications, web portals, and backend systems.

### 1.2 Use Cases

| Use Case | Description | API Used |
|----------|-------------|----------|
| **Customer Address Autocomplete** | Real-time address suggestions during checkout | Places API |
| **Delivery Route Optimization** | Optimal routes for delivery drivers | Directions API |
| **Farm Location Display** | Visual representation of farm locations on maps | Maps JavaScript API |
| **Distance-Based Delivery Fee** | Calculate delivery charges based on distance | Distance Matrix API |
| **Nearest Store Finder** | Locate closest distributor or store | Places API + Distance Matrix API |
| **Driver Tracking** | Real-time delivery tracking for customers | Maps JavaScript API |

### 1.3 Scope

- **Frontend**: Flutter mobile applications, React web portal
- **Backend**: Python-based microservices
- **Geographic Focus**: Bangladesh (primary), with extensibility for international operations

---

## 2. API Setup

### 2.1 Google Cloud Console Setup

#### Step 1: Create Google Cloud Project

```bash
# Via Cloud Console or gcloud CLI
gcloud projects create smart-dairy-maps-prod \
  --name="Smart Dairy Maps Production" \
  --organization=YOUR_ORG_ID
```

#### Step 2: Enable Billing

1. Navigate to **Billing** in Google Cloud Console
2. Link billing account to project
3. Set up budget alerts at 50%, 80%, and 100% thresholds
4. Recommended monthly budget: $500-1000 USD for production

#### Step 3: Create API Key

```bash
# Using gcloud CLI (requires Maps API credentials access)
# Or manually via Console:
# APIs & Services > Credentials > Create Credentials > API Key
```

**API Key Restrictions (Recommended)**:

| Restriction Type | Configuration |
|-----------------|---------------|
| Application Restrictions | HTTP referrers (for web), IP addresses (for backend) |
| API Restrictions | Maps JavaScript API, Geocoding API, Directions API, Distance Matrix API, Places API |

### 2.2 API Key Security

```yaml
# config/maps_config.yaml (Backend)
google_maps:
  api_key: ${GOOGLE_MAPS_API_KEY}  # Environment variable
  region: "bd"
  language: "bn"  # Bengali for Bangladesh
  
# Environment variable setup
# .env file (NEVER commit to version control)
GOOGLE_MAPS_API_KEY=AIzaSyA...
```

### 2.3 Environment Configuration

| Environment | API Key | Billing Account | Rate Limits |
|-------------|---------|-----------------|-------------|
| Development | Separate key with restrictions | Same account | 100 requests/day |
| Staging | Separate key with restrictions | Same account | 1000 requests/day |
| Production | Restricted production key | Production account | Based on quota |

---

## 3. Enabled APIs

### 3.1 Required APIs Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                    Google Maps Platform                          │
├─────────────────────────────────────────────────────────────────┤
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │ Maps JS API  │  │ Geocoding API│  │Directions API│          │
│  │   (Display)  │  │(Coordinates) │  │   (Routes)   │          │
│  └──────────────┘  └──────────────┘  └──────────────┘          │
│  ┌──────────────┐  ┌──────────────┐                              │
│  │Distance Matrix│  │  Places API  │                              │
│  │(Distances)    │  │ (Locations)  │                              │
│  └──────────────┘  └──────────────┘                              │
└─────────────────────────────────────────────────────────────────┘
```

### 3.2 API Selection Matrix

| Feature | API | SKU | Priority |
|---------|-----|-----|----------|
| Display interactive maps | Maps JavaScript API | Dynamic Maps | High |
| Convert address to coordinates | Geocoding API | Geocoding | High |
| Calculate delivery routes | Directions API | Directions | High |
| Calculate distances between points | Distance Matrix API | Distance Matrix | High |
| Address autocomplete | Places API | Places Details + Autocomplete | High |
| Get place details | Places API | Place Details | Medium |
| Static map images | Maps Static API | Static Maps | Low |

### 3.3 API Enablement Commands

```bash
# Enable all required APIs via gcloud
gcloud services enable maps-backend.googleapis.com
gcloud services enable geocoding-backend.googleapis.com
gcloud services enable direction-backend.googleapis.com
gcloud services enable distance-matrix-backend.googleapis.com
gcloud services enable places-backend.googleapis.com
```

---

## 4. Maps JavaScript API

### 4.1 Web Integration

#### HTML Setup

```html
<!-- index.html -->
<!DOCTYPE html>
<html>
<head>
    <title>Smart Dairy - Farm Locations</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places&region=BD&language=bn"></script>
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
        .farm-info-window {
            padding: 10px;
            max-width: 300px;
        }
        .farm-name {
            font-weight: bold;
            color: #2E7D32;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div id="map"></div>
    <script src="maps.js"></script>
</body>
</html>
```

#### JavaScript Implementation

```javascript
// maps.js - Farm Location Display
class SmartDairyMap {
    constructor(mapElementId, apiKey) {
        this.mapElement = document.getElementById(mapElementId);
        this.markers = [];
        this.infoWindows = [];
        this.initMap();
    }

    initMap() {
        // Center on Bangladesh
        const bangladeshCenter = { lat: 23.8103, lng: 90.4125 };
        
        this.map = new google.maps.Map(this.mapElement, {
            zoom: 7,
            center: bangladeshCenter,
            mapTypeId: 'roadmap',
            styles: this.getCustomMapStyle(),
            mapTypeControl: true,
            streetViewControl: false,
            fullscreenControl: true
        });
    }

    // Custom map styling for Smart Dairy branding
    getCustomMapStyle() {
        return [
            {
                featureType: "all",
                elementType: "labels.text.fill",
                stylers: [{ color: "#2E7D32" }]
            },
            {
                featureType: "landscape",
                elementType: "all",
                stylers: [{ color: "#f5f5f5" }]
            },
            {
                featureType: "poi.park",
                elementType: "geometry",
                stylers: [{ color: "#c8e6c9" }]
            },
            {
                featureType: "water",
                elementType: "geometry",
                stylers: [{ color: "#bbdefb" }]
            }
        ];
    }

    // Add farm markers
    addFarmMarkers(farms) {
        farms.forEach(farm => {
            const marker = new google.maps.Marker({
                position: { lat: farm.latitude, lng: farm.longitude },
                map: this.map,
                title: farm.name,
                icon: {
                    url: '/assets/icons/dairy-farm-marker.png',
                    scaledSize: new google.maps.Size(40, 40)
                },
                animation: google.maps.Animation.DROP
            });

            const infoWindow = new google.maps.InfoWindow({
                content: this.createInfoWindowContent(farm)
            });

            marker.addListener('click', () => {
                this.closeAllInfoWindows();
                infoWindow.open(this.map, marker);
            });

            this.markers.push(marker);
            this.infoWindows.push(infoWindow);
        });
    }

    createInfoWindowContent(farm) {
        return `
            <div class="farm-info-window">
                <div class="farm-name">${farm.name}</div>
                <div><strong>Location:</strong> ${farm.district}, ${farm.division}</div>
                <div><strong>Cattle Count:</strong> ${farm.cattleCount}</div>
                <div><strong>Daily Production:</strong> ${farm.dailyProduction}L</div>
                <div><strong>Manager:</strong> ${farm.managerName}</div>
                <button onclick="viewFarmDetails('${farm.id}')">View Details</button>
            </div>
        `;
    }

    closeAllInfoWindows() {
        this.infoWindows.forEach(iw => iw.close());
    }

    // Fit map to show all markers
    fitBounds() {
        const bounds = new google.maps.LatLngBounds();
        this.markers.forEach(marker => bounds.extend(marker.getPosition()));
        this.map.fitBounds(bounds);
    }
}

// Usage
const map = new SmartDairyMap('map');
const farms = [
    {
        id: 'F001',
        name: 'Smart Dairy Farm - Gazipur',
        latitude: 23.9999,
        longitude: 90.4203,
        district: 'Gazipur',
        division: 'Dhaka',
        cattleCount: 500,
        dailyProduction: 2500,
        managerName: 'Md. Rahman'
    },
    // More farms...
];
map.addFarmMarkers(farms);
```

### 4.2 Delivery Tracking Map

```javascript
// delivery-tracking.js
class DeliveryTracker {
    constructor(mapElementId) {
        this.map = new google.maps.Map(document.getElementById(mapElementId), {
            zoom: 12,
            center: { lat: 23.8103, lng: 90.4125 },
            styles: this.getTrackingMapStyle()
        });
        this.driverMarker = null;
        this.routePolyline = null;
    }

    getTrackingMapStyle() {
        return [
            {
                featureType: "transit",
                elementType: "geometry",
                stylers: [{ visibility: "off" }]
            }
        ];
    }

    // Initialize driver tracking
    initializeTracking(driverLocation, customerLocation, waypoints = []) {
        // Add driver marker
        this.driverMarker = new google.maps.Marker({
            position: driverLocation,
            map: this.map,
            icon: {
                url: '/assets/icons/delivery-van.png',
                scaledSize: new google.maps.Size(50, 50),
                anchor: new google.maps.Point(25, 25)
            },
            title: 'Delivery Vehicle'
        });

        // Add customer marker
        new google.maps.Marker({
            position: customerLocation,
            map: this.map,
            icon: {
                url: '/assets/icons/customer-location.png',
                scaledSize: new google.maps.Size(35, 35)
            },
            title: 'Delivery Address'
        });

        // Calculate and display route
        this.calculateRoute(driverLocation, customerLocation, waypoints);
    }

    calculateRoute(origin, destination, waypoints) {
        const directionsService = new google.maps.DirectionsService();
        
        const request = {
            origin: origin,
            destination: destination,
            waypoints: waypoints.map(wp => ({ location: wp, stopover: true })),
            optimizeWaypoints: true,
            travelMode: google.maps.TravelMode.DRIVING,
            drivingOptions: {
                departureTime: new Date(),
                trafficModel: 'bestguess'
            }
        };

        directionsService.route(request, (result, status) => {
            if (status === 'OK') {
                this.displayRoute(result);
                this.eta = result.routes[0].legs[0].duration.text;
            }
        });
    }

    displayRoute(directionsResult) {
        const directionsRenderer = new google.maps.DirectionsRenderer({
            map: this.map,
            directions: directionsResult,
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#2E7D32',
                strokeWeight: 5,
                strokeOpacity: 0.8
            }
        });
    }

    // Update driver position (called via WebSocket)
    updateDriverPosition(newPosition) {
        if (this.driverMarker) {
            this.driverMarker.setPosition(newPosition);
            this.map.panTo(newPosition);
        }
    }
}
```

---

## 5. Geocoding API

### 5.1 Address to Coordinates (Forward Geocoding)

#### Python Backend Implementation

```python
# services/geocoding_service.py
import os
import requests
from typing import Optional, Dict, Tuple
from dataclasses import dataclass
from functools import lru_cache
import logging

logger = logging.getLogger(__name__)

@dataclass
class GeocodingResult:
    latitude: float
    longitude: float
    formatted_address: str
    place_id: str
    address_components: Dict
    partial_match: bool

class GoogleGeocodingService:
    BASE_URL = "https://maps.googleapis.com/maps/api/geocode/json"
    
    def __init__(self, api_key: Optional[str] = None):
        self.api_key = api_key or os.getenv('GOOGLE_MAPS_API_KEY')
        if not self.api_key:
            raise ValueError("Google Maps API key is required")
    
    def geocode_address(
        self, 
        address: str, 
        region: str = "bd",
        language: str = "bn",
        components: Optional[str] = None
    ) -> Optional[GeocodingResult]:
        """
        Convert address to coordinates.
        Optimized for Bangladesh addresses.
        """
        params = {
            'address': address,
            'key': self.api_key,
            'region': region,
            'language': language
        }
        
        if components:
            params['components'] = components
        
        try:
            response = requests.get(self.BASE_URL, params=params, timeout=10)
            response.raise_for_status()
            data = response.json()
            
            if data['status'] != 'OK':
                logger.warning(f"Geocoding failed: {data['status']} for address: {address}")
                return None
            
            result = data['results'][0]
            location = result['geometry']['location']
            
            return GeocodingResult(
                latitude=location['lat'],
                longitude=location['lng'],
                formatted_address=result['formatted_address'],
                place_id=result['place_id'],
                address_components=result['address_components'],
                partial_match=result.get('partial_match', False)
            )
            
        except requests.RequestException as e:
            logger.error(f"Geocoding request failed: {e}")
            return None
    
    def geocode_with_fallback(self, address: str) -> Optional[GeocodingResult]:
        """
        Try geocoding with Bangladesh-specific formatting first,
        then fallback to general search.
        """
        # Try with country component restriction
        result = self.geocode_address(
            address,
            components="country:BD"
        )
        
        if result:
            return result
        
        # Fallback: try without restriction
        return self.geocode_address(address)
    
    def reverse_geocode(
        self, 
        latitude: float, 
        longitude: float,
        language: str = "bn"
    ) -> Optional[GeocodingResult]:
        """Convert coordinates to address."""
        params = {
            'latlng': f"{latitude},{longitude}",
            'key': self.api_key,
            'language': language
        }
        
        try:
            response = requests.get(self.BASE_URL, params=params, timeout=10)
            response.raise_for_status()
            data = response.json()
            
            if data['status'] != 'OK':
                return None
            
            result = data['results'][0]
            location = result['geometry']['location']
            
            return GeocodingResult(
                latitude=location['lat'],
                longitude=location['lng'],
                formatted_address=result['formatted_address'],
                place_id=result['place_id'],
                address_components=result['address_components'],
                partial_match=result.get('partial_match', False)
            )
            
        except requests.RequestException as e:
            logger.error(f"Reverse geocoding failed: {e}")
            return None

# Usage in delivery service
class DeliveryAddressService:
    def __init__(self):
        self.geocoding = GoogleGeocodingService()
    
    def validate_and_geocode_address(self, raw_address: str) -> Dict:
        """Validate customer address and get coordinates."""
        # Format address for Bangladesh
        formatted_address = self.format_bd_address(raw_address)
        
        result = self.geocoding.geocode_with_fallback(formatted_address)
        
        if not result:
            return {
                'valid': False,
                'error': 'Address could not be verified'
            }
        
        # Check if within service area (Bangladesh)
        if not self.is_within_bangladesh(result.latitude, result.longitude):
            return {
                'valid': False,
                'error': 'Address outside service area'
            }
        
        return {
            'valid': True,
            'coordinates': {
                'lat': result.latitude,
                'lng': result.longitude
            },
            'formatted_address': result.formatted_address,
            'place_id': result.place_id,
            'partial_match': result.partial_match
        }
    
    def format_bd_address(self, address: str) -> str:
        """Format address with Bangladesh context."""
        # Add common suffixes if missing
        address = address.strip()
        
        # Ensure Bangladesh is mentioned
        if 'bangladesh' not in address.lower():
            address += ", Bangladesh"
        
        return address
    
    def is_within_bangladesh(self, lat: float, lng: float) -> bool:
        """Check if coordinates are within Bangladesh bounding box."""
        # Approximate bounding box for Bangladesh
        return (20.7 <= lat <= 26.6) and (88.0 <= lng <= 92.7)
```

### 5.2 Database Schema for Geocoded Addresses

```sql
-- Migration: Add geocoding support to addresses table
ALTER TABLE customer_addresses 
ADD COLUMN latitude DECIMAL(10, 8) NULL,
ADD COLUMN longitude DECIMAL(11, 8) NULL,
ADD COLUMN place_id VARCHAR(255) NULL,
ADD COLUMN geocoded_address TEXT NULL,
ADD COLUMN geocoded_at TIMESTAMP NULL,
ADD COLUMN geocoding_confidence VARCHAR(20) NULL,
ADD COLUMN is_serviceable BOOLEAN DEFAULT FALSE,
ADD COLUMN delivery_zone_id INT NULL;

-- Create index for geospatial queries
CREATE INDEX idx_addresses_coordinates 
ON customer_addresses(latitude, longitude) 
WHERE latitude IS NOT NULL AND longitude IS NOT NULL;

-- Delivery zones table
CREATE TABLE delivery_zones (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    center_latitude DECIMAL(10, 8) NOT NULL,
    center_longitude DECIMAL(11, 8) NOT NULL,
    radius_km DECIMAL(5, 2) NOT NULL,
    base_delivery_fee DECIMAL(10, 2) DEFAULT 0.00,
    per_km_charge DECIMAL(5, 2) DEFAULT 0.00,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 6. Directions API

### 6.1 Route Calculation for Delivery

#### Python Backend - Route Optimization

```python
# services/directions_service.py
import os
import requests
from typing import List, Dict, Optional, Tuple
from dataclasses import dataclass
from datetime import datetime
import polyline

@dataclass
class RouteLeg:
    distance_meters: int
    distance_text: str
    duration_seconds: int
    duration_text: str
    start_location: Tuple[float, float]
    end_location: Tuple[float, float]
    steps: List[Dict]

@dataclass
class Route:
    summary: str
    total_distance_meters: int
    total_duration_seconds: int
    legs: List[RouteLeg]
    overview_polyline: str
    warnings: List[str]
    copyrights: str

class DirectionsService:
    BASE_URL = "https://maps.googleapis.com/maps/api/directions/json"
    
    def __init__(self, api_key: Optional[str] = None):
        self.api_key = api_key or os.getenv('GOOGLE_MAPS_API_KEY')
    
    def get_route(
        self,
        origin: Tuple[float, float],
        destination: Tuple[float, float],
        waypoints: Optional[List[Tuple[float, float]]] = None,
        optimize_waypoints: bool = True,
        departure_time: Optional[datetime] = None,
        avoid_tolls: bool = True
    ) -> Optional[Route]:
        """Calculate optimal route for delivery."""
        
        params = {
            'origin': f"{origin[0]},{origin[1]}",
            'destination': f"{destination[0]},{destination[1]}",
            'key': self.api_key,
            'mode': 'driving',
            'language': 'bn'
        }
        
        if waypoints:
            waypoint_str = '|'.join([f"{wp[0]},{wp[1]}" for wp in waypoints])
            if optimize_waypoints:
                waypoint_str = f"optimize:true|{waypoint_str}"
            params['waypoints'] = waypoint_str
        
        if departure_time:
            params['departure_time'] = int(departure_time.timestamp())
        
        if avoid_tolls:
            params['avoid'] = 'tolls'
        
        try:
            response = requests.get(self.BASE_URL, params=params, timeout=15)
            response.raise_for_status()
            data = response.json()
            
            if data['status'] != 'OK':
                logger.error(f"Directions API error: {data['status']}")
                return None
            
            route_data = data['routes'][0]
            return self._parse_route(route_data)
            
        except requests.RequestException as e:
            logger.error(f"Directions request failed: {e}")
            return None
    
    def _parse_route(self, route_data: Dict) -> Route:
        """Parse API response into Route object."""
        legs = []
        total_distance = 0
        total_duration = 0
        
        for leg_data in route_data['legs']:
            leg = RouteLeg(
                distance_meters=leg_data['distance']['value'],
                distance_text=leg_data['distance']['text'],
                duration_seconds=leg_data['duration']['value'],
                duration_text=leg_data['duration']['text'],
                start_location=(
                    leg_data['start_location']['lat'],
                    leg_data['start_location']['lng']
                ),
                end_location=(
                    leg_data['end_location']['lat'],
                    leg_data['end_location']['lng']
                ),
                steps=leg_data.get('steps', [])
            )
            legs.append(leg)
            total_distance += leg.distance_meters
            total_duration += leg.duration_seconds
        
        return Route(
            summary=route_data['summary'],
            total_distance_meters=total_distance,
            total_duration_seconds=total_duration,
            legs=legs,
            overview_polyline=route_data['overview_polyline']['points'],
            warnings=route_data.get('warnings', []),
            copyrights=route_data['copyrights']
        )

# Delivery Route Optimizer
class DeliveryRouteOptimizer:
    def __init__(self):
        self.directions = DirectionsService()
    
    def optimize_delivery_route(
        self,
        depot_location: Tuple[float, float],
        delivery_stops: List[Dict]
    ) -> Dict:
        """
        Optimize multi-stop delivery route.
        Returns ordered stops and total metrics.
        """
        if not delivery_stops:
            return {'error': 'No delivery stops provided'}
        
        # Extract coordinates
        waypoints = [
            (stop['latitude'], stop['longitude']) 
            for stop in delivery_stops
        ]
        
        # Return to depot (round trip)
        route = self.directions.get_route(
            origin=depot_location,
            destination=depot_location,
            waypoints=waypoints,
            optimize_waypoints=True
        )
        
        if not route:
            return {'error': 'Could not calculate route'}
        
        # Reorder stops based on optimization
        waypoint_order = route.legs[:-1]  # Exclude return to depot
        
        # Calculate delivery times
        current_time = datetime.now()
        optimized_stops = []
        accumulated_time = 0
        
        for i, leg in enumerate(route.legs):
            accumulated_time += leg.duration_seconds
            
            if i < len(delivery_stops):
                stop = delivery_stops[i]
                estimated_arrival = current_time.timestamp() + accumulated_time
                
                optimized_stops.append({
                    'order': i + 1,
                    'customer_id': stop['customer_id'],
                    'address': stop['address'],
                    'estimated_arrival': datetime.fromtimestamp(estimated_arrival),
                    'travel_time_minutes': leg.duration_seconds // 60,
                    'distance_km': round(leg.distance_meters / 1000, 2)
                })
        
        return {
            'optimized_stops': optimized_stops,
            'total_distance_km': round(route.total_distance_meters / 1000, 2),
            'total_duration_minutes': route.total_duration_seconds // 60,
            'route_polyline': route.overview_polyline,
            'estimated_completion': optimized_stops[-1]['estimated_arrival'] if optimized_stops else None
        }
    
    def calculate_delivery_eta(
        self,
        driver_location: Tuple[float, float],
        destination: Tuple[float, float]
    ) -> Dict:
        """Calculate ETA for active delivery."""
        route = self.directions.get_route(
            origin=driver_location,
            destination=destination,
            departure_time=datetime.now()
        )
        
        if not route:
            return {'error': 'Could not calculate ETA'}
        
        eta_seconds = route.total_duration_seconds
        eta_timestamp = datetime.now().timestamp() + eta_seconds
        
        return {
            'eta_seconds': eta_seconds,
            'eta_minutes': eta_seconds // 60,
            'eta_formatted': datetime.fromtimestamp(eta_timestamp).strftime('%I:%M %p'),
            'distance_km': round(route.total_distance_meters / 1000, 2),
            'traffic_delay_seconds': route.legs[0].duration_seconds - eta_seconds if route.legs else 0
        }
```

### 6.2 Driver Mobile Route Display

```dart
// Flutter - Driver route display
import 'package:flutter/material.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:flutter_polyline_points/flutter_polyline_points.dart';

class DriverRouteScreen extends StatefulWidget {
  final List<DeliveryStop> stops;
  
  const DriverRouteScreen({Key? key, required this.stops}) : super(key: key);
  
  @override
  _DriverRouteScreenState createState() => _DriverRouteScreenState();
}

class _DriverRouteScreenState extends State<DriverRouteScreen> {
  GoogleMapController? _mapController;
  List<LatLng> _polylineCoordinates = [];
  Set<Marker> _markers = {};
  Set<Polyline> _polylines = {};
  
  @override
  void initState() {
    super.initState();
    _loadRoute();
  }
  
  Future<void> _loadRoute() async {
    // Fetch route from backend
    final routeData = await DeliveryApi.getOptimizedRoute(widget.stops);
    
    // Decode polyline
    final polylinePoints = PolylinePoints();
    final points = polylinePoints.decodePolyline(routeData['route_polyline']);
    
    setState(() {
      _polylineCoordinates = points
          .map((p) => LatLng(p.latitude, p.longitude))
          .toList();
      
      // Add markers
      for (int i = 0; i < widget.stops.length; i++) {
        _markers.add(Marker(
          markerId: MarkerId('stop_$i'),
          position: LatLng(
            widget.stops[i].latitude,
            widget.stops[i].longitude,
          ),
          icon: BitmapDescriptor.defaultMarkerWithHue(
            i == 0 ? BitmapDescriptor.hueGreen : BitmapDescriptor.hueRed,
          ),
          infoWindow: InfoWindow(
            title: 'Stop ${i + 1}',
            snippet: widget.stops[i].customerName,
          ),
        ));
      }
      
      // Add polyline
      _polylines.add(Polyline(
        polylineId: PolylineId('route'),
        points: _polylineCoordinates,
        color: Colors.green.shade700,
        width: 5,
      ));
    });
    
    // Fit bounds
    _fitBounds();
  }
  
  void _fitBounds() {
    if (_markers.isEmpty || _mapController == null) return;
    
    double minLat = 90, maxLat = -90;
    double minLng = 180, maxLng = -180;
    
    for (final marker in _markers) {
      final lat = marker.position.latitude;
      final lng = marker.position.longitude;
      minLat = lat < minLat ? lat : minLat;
      maxLat = lat > maxLat ? lat : maxLat;
      minLng = lng < minLng ? lng : minLng;
      maxLng = lng > maxLng ? lng : maxLng;
    }
    
    _mapController!.animateCamera(
      CameraUpdate.newLatLngBounds(
        LatLngBounds(
          southwest: LatLng(minLat, minLng),
          northeast: LatLng(maxLat, maxLng),
        ),
        50,
      ),
    );
  }
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Delivery Route'),
        actions: [
          IconButton(
            icon: const Icon(Icons.navigation),
            onPressed: _startTurnByTurnNavigation,
          ),
        ],
      ),
      body: GoogleMap(
        mapType: MapType.normal,
        initialCameraPosition: const CameraPosition(
          target: LatLng(23.8103, 90.4125),
          zoom: 12,
        ),
        markers: _markers,
        polylines: _polylines,
        myLocationEnabled: true,
        myLocationButtonEnabled: true,
        onMapCreated: (controller) => _mapController = controller,
      ),
      bottomNavigationBar: _buildStopList(),
    );
  }
  
  Widget _buildStopList() {
    return Container(
      height: 200,
      child: ListView.builder(
        itemCount: widget.stops.length,
        itemBuilder: (context, index) {
          final stop = widget.stops[index];
          return ListTile(
            leading: CircleAvatar(
              child: Text('${index + 1}'),
              backgroundColor: index == 0 ? Colors.green : Colors.grey,
            ),
            title: Text(stop.customerName),
            subtitle: Text(stop.address),
            trailing: Text(stop.estimatedArrival),
            onTap: () => _focusOnStop(index),
          );
        },
      ),
    );
  }
  
  void _startTurnByTurnNavigation() {
    // Launch external navigation app
    final destination = widget.stops.first;
    MapsLauncher.launchCoordinates(
      destination.latitude,
      destination.longitude,
    );
  }
  
  void _focusOnStop(int index) {
    final stop = widget.stops[index];
    _mapController?.animateCamera(
      CameraUpdate.newLatLngZoom(
        LatLng(stop.latitude, stop.longitude),
        16,
      ),
    );
  }
  
  @override
  void dispose() {
    _mapController?.dispose();
    super.dispose();
  }
}
```

---

## 7. Distance Matrix API

### 7.1 Batch Distance Calculation

#### Python Backend - Distance Calculation Service

```python
# services/distance_service.py
import os
import requests
from typing import List, Dict, Tuple, Optional
from dataclasses import dataclass
from concurrent.futures import ThreadPoolExecutor
import logging

logger = logging.getLogger(__name__)

@dataclass
class DistanceResult:
    origin_index: int
    destination_index: int
    distance_meters: int
    distance_text: str
    duration_seconds: int
    duration_text: str
    status: str

class DistanceMatrixService:
    BASE_URL = "https://maps.googleapis.com/maps/api/distancematrix/json"
    MAX_ELEMENTS_PER_REQUEST = 100  # Google API limit
    
    def __init__(self, api_key: Optional[str] = None):
        self.api_key = api_key or os.getenv('GOOGLE_MAPS_API_KEY')
    
    def calculate_distances(
        self,
        origins: List[Tuple[float, float]],
        destinations: List[Tuple[float, float]],
        mode: str = "driving",
        departure_time: Optional[str] = "now"
    ) -> List[List[Optional[DistanceResult]]]:
        """
        Calculate distances between multiple origins and destinations.
        Handles batching for large requests.
        """
        results = [[None] * len(destinations) for _ in origins]
        
        # Calculate batch sizes
        total_elements = len(origins) * len(destinations)
        
        if total_elements <= self.MAX_ELEMENTS_PER_REQUEST:
            return self._make_distance_request(origins, destinations, mode, departure_time)
        
        # Batch processing for large requests
        origin_batches = self._batch_origins(origins, len(destinations))
        
        for batch_indices, batch_origins in origin_batches:
            batch_results = self._make_distance_request(
                batch_origins, destinations, mode, departure_time
            )
            
            # Merge results
            for i, row in enumerate(batch_results):
                global_origin_index = batch_indices[i]
                results[global_origin_index] = row
        
        return results
    
    def _batch_origins(
        self, 
        origins: List[Tuple[float, float]], 
        num_destinations: int
    ) -> List[Tuple[List[int], List[Tuple[float, float]]]]:
        """Split origins into batches respecting API limits."""
        max_origins = self.MAX_ELEMENTS_PER_REQUEST // num_destinations
        
        batches = []
        for i in range(0, len(origins), max_origins):
            batch_indices = list(range(i, min(i + max_origins, len(origins))))
            batch_origins = [origins[j] for j in batch_indices]
            batches.append((batch_indices, batch_origins))
        
        return batches
    
    def _make_distance_request(
        self,
        origins: List[Tuple[float, float]],
        destinations: List[Tuple[float, float]],
        mode: str,
        departure_time: Optional[str]
    ) -> List[List[Optional[DistanceResult]]]:
        """Make single Distance Matrix API request."""
        
        origin_str = '|'.join([f"{lat},{lng}" for lat, lng in origins])
        dest_str = '|'.join([f"{lat},{lng}" for lat, lng in destinations])
        
        params = {
            'origins': origin_str,
            'destinations': dest_str,
            'mode': mode,
            'key': self.api_key,
            'language': 'bn',
            'region': 'bd'
        }
        
        if departure_time:
            params['departure_time'] = departure_time
        
        try:
            response = requests.get(self.BASE_URL, params=params, timeout=15)
            response.raise_for_status()
            data = response.json()
            
            if data['status'] != 'OK':
                logger.error(f"Distance Matrix API error: {data['status']}")
                return [[None] * len(destinations) for _ in origins]
            
            return self._parse_distance_response(data, len(origins), len(destinations))
            
        except requests.RequestException as e:
            logger.error(f"Distance Matrix request failed: {e}")
            return [[None] * len(destinations) for _ in origins]
    
    def _parse_distance_response(
        self, 
        data: Dict, 
        num_origins: int, 
        num_destinations: int
    ) -> List[List[Optional[DistanceResult]]]:
        """Parse API response into structured results."""
        results = []
        
        for i, row in enumerate(data['rows']):
            row_results = []
            for j, element in enumerate(row['elements']):
                if element['status'] == 'OK':
                    result = DistanceResult(
                        origin_index=i,
                        destination_index=j,
                        distance_meters=element['distance']['value'],
                        distance_text=element['distance']['text'],
                        duration_seconds=element['duration']['value'],
                        duration_text=element['duration']['text'],
                        status='OK'
                    )
                else:
                    result = DistanceResult(
                        origin_index=i,
                        destination_index=j,
                        distance_meters=0,
                        distance_text='',
                        duration_seconds=0,
                        duration_text='',
                        status=element['status']
                    )
                row_results.append(result)
            results.append(row_results)
        
        return results

# Delivery Fee Calculator
class DeliveryFeeCalculator:
    BASE_FEE = 30.0  # BDT
    PER_KM_RATE = 15.0  # BDT per km
    FREE_DELIVERY_THRESHOLD = 500.0  # BDT order value
    MAX_DELIVERY_DISTANCE_KM = 50
    
    def __init__(self):
        self.distance_service = DistanceMatrixService()
    
    def calculate_delivery_fee(
        self,
        customer_location: Tuple[float, float],
        warehouse_location: Tuple[float, float],
        order_value: float
    ) -> Dict:
        """Calculate delivery fee based on distance."""
        
        # Check for free delivery
        if order_value >= self.FREE_DELIVERY_THRESHOLD:
            return {
                'delivery_fee': 0.0,
                'is_free_delivery': True,
                'reason': f'Free delivery for orders over ৳{self.FREE_DELIVERY_THRESHOLD}'
            }
        
        # Calculate distance
        distances = self.distance_service.calculate_distances(
            origins=[warehouse_location],
            destinations=[customer_location]
        )
        
        if not distances or not distances[0] or not distances[0][0]:
            return {
                'delivery_fee': self.BASE_FEE,
                'is_estimate': True,
                'error': 'Could not calculate exact distance'
            }
        
        distance_km = distances[0][0].distance_meters / 1000
        
        # Check service area
        if distance_km > self.MAX_DELIVERY_DISTANCE_KM:
            return {
                'delivery_fee': None,
                'is_serviceable': False,
                'error': f'Delivery not available beyond {self.MAX_DELIVERY_DISTANCE_KM}km'
            }
        
        # Calculate fee
        delivery_fee = self.BASE_FEE + (distance_km * self.PER_KM_RATE)
        
        return {
            'delivery_fee': round(delivery_fee, 2),
            'distance_km': round(distance_km, 2),
            'estimated_time_minutes': distances[0][0].duration_seconds // 60,
            'is_serviceable': True,
            'breakdown': {
                'base_fee': self.BASE_FEE,
                'distance_charge': round(distance_km * self.PER_KM_RATE, 2)
            }
        }
    
    def find_nearest_warehouse(
        self,
        customer_location: Tuple[float, float],
        warehouses: List[Dict]
    ) -> Optional[Dict]:
        """Find nearest warehouse to customer."""
        if not warehouses:
            return None
        
        warehouse_locations = [
            (w['latitude'], w['longitude']) for w in warehouses
        ]
        
        distances = self.distance_service.calculate_distances(
            origins=[customer_location],
            destinations=warehouse_locations
        )
        
        if not distances or not distances[0]:
            return warehouses[0]  # Fallback to first
        
        # Find nearest
        nearest_index = 0
        min_distance = float('inf')
        
        for i, result in enumerate(distances[0]):
            if result and result.distance_meters < min_distance:
                min_distance = result.distance_meters
                nearest_index = i
        
        return {
            **warehouses[nearest_index],
            'distance_km': round(min_distance / 1000, 2)
        }
```

### 7.2 Nearest Store Finder

```python
# services/store_finder.py
from typing import List, Dict, Tuple, Optional
from dataclasses import dataclass
import math

@dataclass
class StoreLocation:
    id: str
    name: str
    address: str
    latitude: float
    longitude: float
    phone: str
    operating_hours: Dict
    services: List[str]
    distance_km: Optional[float] = None
    travel_time_minutes: Optional[int] = None

class NearestStoreFinder:
    def __init__(self):
        self.distance_service = DistanceMatrixService()
    
    def find_nearest_stores(
        self,
        customer_location: Tuple[float, float],
        limit: int = 5,
        max_distance_km: float = 20.0
    ) -> List[StoreLocation]:
        """Find nearest Smart Dairy stores/distributors."""
        
        # Get all active stores from database
        stores = self._get_active_stores()
        
        if not stores:
            return []
        
        # Calculate distances to all stores
        store_locations = [(s.latitude, s.longitude) for s in stores]
        
        distances = self.distance_service.calculate_distances(
            origins=[customer_location],
            destinations=store_locations
        )
        
        if not distances or not distances[0]:
            # Fallback to Haversine distance calculation
            return self._calculate_haversine_distances(
                customer_location, stores, limit, max_distance_km
            )
        
        # Attach distances to stores
        for i, store in enumerate(stores):
            result = distances[0][i]
            if result and result.status == 'OK':
                store.distance_km = round(result.distance_meters / 1000, 2)
                store.travel_time_minutes = result.duration_seconds // 60
        
        # Filter and sort
        nearby_stores = [
            s for s in stores 
            if s.distance_km is not None and s.distance_km <= max_distance_km
        ]
        nearby_stores.sort(key=lambda s: s.distance_km or float('inf'))
        
        return nearby_stores[:limit]
    
    def _calculate_haversine_distances(
        self,
        customer_location: Tuple[float, float],
        stores: List[StoreLocation],
        limit: int,
        max_distance_km: float
    ) -> List[StoreLocation]:
        """Fallback distance calculation using Haversine formula."""
        
        for store in stores:
            store.distance_km = round(self._haversine_distance(
                customer_location[0], customer_location[1],
                store.latitude, store.longitude
            ), 2)
        
        nearby_stores = [s for s in stores if s.distance_km <= max_distance_km]
        nearby_stores.sort(key=lambda s: s.distance_km)
        
        return nearby_stores[:limit]
    
    def _haversine_distance(
        self, 
        lat1: float, lon1: float, 
        lat2: float, lon2: float
    ) -> float:
        """Calculate great circle distance between two points."""
        R = 6371  # Earth's radius in km
        
        lat1_rad = math.radians(lat1)
        lat2_rad = math.radians(lat2)
        delta_lat = math.radians(lat2 - lat1)
        delta_lon = math.radians(lon2 - lon1)
        
        a = (math.sin(delta_lat / 2) ** 2 + 
             math.cos(lat1_rad) * math.cos(lat2_rad) * math.sin(delta_lon / 2) ** 2)
        c = 2 * math.atan2(math.sqrt(a), math.sqrt(1 - a))
        
        return R * c
    
    def _get_active_stores(self) -> List[StoreLocation]:
        """Fetch active stores from database."""
        # This would query your database
        # Placeholder implementation
        return [
            StoreLocation(
                id="S001",
                name="Smart Dairy - Dhanmondi",
                address="Road 27, Dhanmondi, Dhaka",
                latitude=23.7461,
                longitude=90.3742,
                phone="+880123456789",
                operating_hours={
                    'open': '08:00',
                    'close': '22:00',
                    'days': ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu']
                },
                services=['retail', 'wholesale', 'home_delivery']
            ),
            # More stores...
        ]
```

---

## 8. Places API

### 8.1 Address Autocomplete

#### Flutter Mobile Implementation

```dart
// Flutter - Address Autocomplete Widget
import 'package:flutter/material.dart';
import 'package:google_maps_webservice/places.dart';

class AddressAutocompleteField extends StatefulWidget {
  final Function(PlaceDetails) onAddressSelected;
  final String label;
  final String hint;
  
  const AddressAutocompleteField({
    Key? key,
    required this.onAddressSelected,
    this.label = 'Delivery Address',
    this.hint = 'Search for your address',
  }) : super(key: key);
  
  @override
  _AddressAutocompleteFieldState createState() => 
      _AddressAutocompleteFieldState();
}

class _AddressAutocompleteFieldState extends State<AddressAutocompleteField> {
  final TextEditingController _controller = TextEditingController();
  final GoogleMapsPlaces _places = GoogleMapsPlaces(
    apiKey: 'YOUR_API_KEY',
  );
  
  List<Prediction> _predictions = [];
  bool _isLoading = false;
  bool _showPredictions = false;
  
  Future<void> _onSearchChanged(String query) async {
    if (query.length < 3) {
      setState(() {
        _predictions = [];
        _showPredictions = false;
      });
      return;
    }
    
    setState(() => _isLoading = true);
    
    try {
      final response = await _places.autocomplete(
        query,
        language: 'bn',
        components: [Component(Component.country, 'bd')],
        types: ['address'],
        strictbounds: false,
      );
      
      if (response.isOkay) {
        setState(() {
          _predictions = response.predictions;
          _showPredictions = true;
        });
      }
    } catch (e) {
      debugPrint('Places API error: $e');
    } finally {
      setState(() => _isLoading = false);
    }
  }
  
  Future<void> _onPredictionSelected(Prediction prediction) async {
    setState(() {
      _controller.text = prediction.description ?? '';
      _showPredictions = false;
      _isLoading = true;
    });
    
    try {
      final details = await _places.getDetailsByPlaceId(prediction.placeId!);
      
      if (details.isOkay) {
        widget.onAddressSelected(details.result);
      }
    } catch (e) {
      debugPrint('Place details error: $e');
    } finally {
      setState(() => _isLoading = false);
    }
  }
  
  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          widget.label,
          style: Theme.of(context).textTheme.subtitle2,
        ),
        const SizedBox(height: 8),
        TextField(
          controller: _controller,
          decoration: InputDecoration(
            hintText: widget.hint,
            prefixIcon: const Icon(Icons.location_on_outlined),
            suffixIcon: _isLoading 
                ? const SizedBox(
                    width: 20,
                    height: 20,
                    child: CircularProgressIndicator(strokeWidth: 2),
                  )
                : const Icon(Icons.search),
            border: const OutlineInputBorder(),
          ),
          onChanged: _onSearchChanged,
        ),
        if (_showPredictions && _predictions.isNotEmpty)
          Container(
            margin: const EdgeInsets.only(top: 4),
            decoration: BoxDecoration(
              border: Border.all(color: Colors.grey.shade300),
              borderRadius: BorderRadius.circular(4),
            ),
            child: ListView.separated(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              itemCount: _predictions.length,
              separatorBuilder: (_, __) => const Divider(height: 1),
              itemBuilder: (context, index) {
                final prediction = _predictions[index];
                return ListTile(
                  dense: true,
                  leading: const Icon(Icons.location_on, size: 20),
                  title: Text(
                    prediction.structuredFormatting?.mainText ?? 
                    prediction.description ?? '',
                    style: const TextStyle(fontSize: 14),
                  ),
                  subtitle: Text(
                    prediction.structuredFormatting?.secondaryText ?? '',
                    style: TextStyle(
                      fontSize: 12,
                      color: Colors.grey.shade600,
                    ),
                  ),
                  onTap: () => _onPredictionSelected(prediction),
                );
              },
            ),
          ),
      ],
    );
  }
  
  @override
  void dispose() {
    _controller.dispose();
    _places.dispose();
    super.dispose();
  }
}

// Usage in checkout screen
class CheckoutScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Checkout')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            AddressAutocompleteField(
              onAddressSelected: (PlaceDetails details) {
                // Extract address components
                final address = _parseAddressComponents(details);
                
                // Save to order
                context.read<CheckoutBloc>().add(
                  AddressSelected(
                    address: address,
                    coordinates: LatLng(
                      details.geometry!.location.lat,
                      details.geometry!.location.lng,
                    ),
                    placeId: details.placeId,
                  ),
                );
              },
            ),
            // ... rest of checkout form
          ],
        ),
      ),
    );
  }
  
  Map<String, dynamic> _parseAddressComponents(PlaceDetails details) {
    final components = <String, String>{};
    
    for (final component in details.addressComponents) {
      final type = component.types.first;
      switch (type) {
        case 'street_number':
          components['street_number'] = component.longName;
          break;
        case 'route':
          components['street'] = component.longName;
          break;
        case 'sublocality_level_1':
        case 'sublocality':
          components['area'] = component.longName;
          break;
        case 'locality':
          components['city'] = component.longName;
          break;
        case 'administrative_area_level_1':
          components['division'] = component.longName;
          break;
        case 'postal_code':
          components['postcode'] = component.longName;
          break;
      }
    }
    
    return components;
  }
}
```

#### Web Implementation - Address Autocomplete

```javascript
// web/address-autocomplete.js
class AddressAutocomplete {
    constructor(inputElementId, onSelectCallback) {
        this.input = document.getElementById(inputElementId);
        this.onSelect = onSelectCallback;
        this.initAutocomplete();
    }

    initAutocomplete() {
        this.autocomplete = new google.maps.places.Autocomplete(this.input, {
            componentRestrictions: { country: 'bd' },
            fields: ['address_components', 'geometry', 'place_id', 'formatted_address'],
            types: ['address']
        });

        this.autocomplete.addListener('place_changed', () => {
            const place = this.autocomplete.getPlace();
            
            if (!place.geometry) {
                console.error('No geometry found for selected place');
                return;
            }

            const addressData = this.parseAddressComponents(place);
            
            this.onSelect({
                formattedAddress: place.formatted_address,
                coordinates: {
                    lat: place.geometry.location.lat(),
                    lng: place.geometry.location.lng()
                },
                placeId: place.place_id,
                components: addressData
            });
        });
    }

    parseAddressComponents(place) {
        const components = {};
        
        const componentMap = {
            'street_number': 'streetNumber',
            'route': 'street',
            'sublocality_level_1': 'area',
            'sublocality': 'area',
            'locality': 'city',
            'administrative_area_level_1': 'division',
            'postal_code': 'postcode'
        };

        for (const component of place.address_components) {
            const type = component.types[0];
            if (componentMap[type]) {
                components[componentMap[type]] = component.long_name;
            }
        }

        return components;
    }
}

// Usage
document.addEventListener('DOMContentLoaded', () => {
    const addressInput = new AddressAutocomplete(
        'delivery-address',
        (addressData) => {
            console.log('Selected address:', addressData);
            
            // Fill form fields
            document.getElementById('address-line-1').value = 
                `${addressData.components.streetNumber || ''} ${addressData.components.street || ''}`.trim();
            document.getElementById('area').value = addressData.components.area || '';
            document.getElementById('city').value = addressData.components.city || '';
            document.getElementById('postcode').value = addressData.components.postcode || '';
            
            // Store coordinates for delivery fee calculation
            window.selectedAddressCoordinates = addressData.coordinates;
        }
    );
});
```

### 8.2 Backend - Place Details Caching

```python
# services/place_cache.py
import json
from datetime import datetime, timedelta
from typing import Optional, Dict
import hashlib
from models import PlaceCache  # SQLAlchemy model

class PlaceCacheService:
    CACHE_TTL_DAYS = 30
    
    def __init__(self, db_session):
        self.db = db_session
    
    def get_cached_place(self, place_id: str) -> Optional[Dict]:
        """Get cached place details."""
        cached = self.db.query(PlaceCache).filter(
            PlaceCache.place_id == place_id,
            PlaceCache.expires_at > datetime.utcnow()
        ).first()
        
        if cached:
            return json.loads(cached.place_data)
        return None
    
    def cache_place(self, place_id: str, place_data: Dict):
        """Cache place details."""
        cached = self.db.query(PlaceCache).filter(
            PlaceCache.place_id == place_id
        ).first()
        
        expires_at = datetime.utcnow() + timedelta(days=self.CACHE_TTL_DAYS)
        
        if cached:
            cached.place_data = json.dumps(place_data)
            cached.expires_at = expires_at
            cached.updated_at = datetime.utcnow()
        else:
            cached = PlaceCache(
                place_id=place_id,
                place_data=json.dumps(place_data),
                expires_at=expires_at
            )
            self.db.add(cached)
        
        self.db.commit()
    
    def get_cached_geocode(self, address_hash: str) -> Optional[Dict]:
        """Get cached geocoding result."""
        # Implementation similar to place cache
        pass
    
    @staticmethod
    def hash_address(address: str) -> str:
        """Create hash for address caching."""
        return hashlib.md5(address.lower().strip().encode()).hexdigest()
```

---

## 9. Implementation

### 9.1 Flutter Mobile Integration

#### Android Configuration

```xml
<!-- android/app/src/main/AndroidManifest.xml -->
<manifest>
    <!-- Permissions -->
    <uses-permission android:name="android.permission.INTERNET"/>
    <uses-permission android:name="android.permission.ACCESS_FINE_LOCATION"/>
    <uses-permission android:name="android.permission.ACCESS_COARSE_LOCATION"/>
    <uses-permission android:name="android.permission.ACCESS_BACKGROUND_LOCATION"/>
    <uses-permission android:name="android.permission.FOREGROUND_SERVICE"/>
    
    <application
        android:label="Smart Dairy"
        android:name="${applicationName}">
        
        <!-- Google Maps API Key -->
        <meta-data 
            android:name="com.google.android.geo.API_KEY"
            android:value="${GOOGLE_MAPS_API_KEY}"/>
        
        <activity android:name=".MainActivity">
            <intent-filter>
                <action android:name="android.intent.action.MAIN"/>
                <category android:name="android.intent.category.LAUNCHER"/>
            </intent-filter>
        </activity>
    </application>
</manifest>
```

#### iOS Configuration

```xml
<!-- ios/Runner/AppDelegate.swift -->
import UIKit
import Flutter
import GoogleMaps

@UIApplicationMain
@objc class AppDelegate: FlutterAppDelegate {
    override func application(
        _ application: UIApplication,
        didFinishLaunchingWithOptions launchOptions: [UIApplication.LaunchOptionsKey: Any]?
    ) -> Bool {
        GMSServices.provideAPIKey("YOUR_API_KEY")
        GeneratedPluginRegistrant.register(with: self)
        return super.application(application, didFinishLaunchingWithOptions: launchOptions)
    }
}
```

```xml
<!-- ios/Runner/Info.plist -->
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" 
    "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <!-- Location Permissions -->
    <key>NSLocationWhenInUseUsageDescription</key>
    <string>Smart Dairy needs your location to show nearby stores and calculate delivery fees.</string>
    <key>NSLocationAlwaysUsageDescription</key>
    <string>Smart Dairy tracks your location during delivery to keep customers updated.</string>
    <key>NSLocationAlwaysAndWhenInUseUsageDescription</key>
    <string>Smart Dairy needs your location for delivery tracking and route optimization.</string>
</dict>
</plist>
```

#### pubspec.yaml Dependencies

```yaml
# pubspec.yaml
dependencies:
  flutter:
    sdk: flutter
  
  # Google Maps
  google_maps_flutter: ^2.5.0
  google_maps_flutter_platform_interface: ^2.4.0
  
  # Places API
  google_maps_webservice: ^0.0.20
  
  # Location Services
  geolocator: ^10.1.0
  location: ^5.0.0
  
  # Polyline decoding
  flutter_polyline_points: ^2.0.0
  
  # Maps launcher
  maps_launcher: ^2.2.1
  
  # HTTP client
  dio: ^5.4.0
  
dev_dependencies:
  flutter_test:
    sdk: flutter
```

### 9.2 Backend Python Integration

```python
# config/maps_config.py
import os
from dataclasses import dataclass

@dataclass
class MapsConfig:
    API_KEY: str = os.getenv('GOOGLE_MAPS_API_KEY', '')
    REGION: str = 'bd'
    LANGUAGE: str = 'bn'
    
    # Rate limiting
    REQUESTS_PER_SECOND: int = 50
    REQUESTS_PER_DAY: int = 10000
    
    # Timeouts
    REQUEST_TIMEOUT: int = 10
    
    # Caching
    CACHE_TTL_HOURS: int = 24
    
    @classmethod
    def from_env(cls):
        return cls(
            API_KEY=os.getenv('GOOGLE_MAPS_API_KEY'),
            REGION=os.getenv('GOOGLE_MAPS_REGION', 'bd'),
            LANGUAGE=os.getenv('GOOGLE_MAPS_LANGUAGE', 'bn')
        )

# requirements.txt
# Google Maps API Client
googlemaps==4.10.0

# HTTP requests
requests==2.31.0
httpx==0.25.2

# Geospatial utilities
shapely==2.0.2
geopy==2.4.0

# Cache
redis==5.0.1
```

### 9.3 API Routes

```python
# routes/maps_routes.py
from fastapi import APIRouter, HTTPException, Depends
from pydantic import BaseModel
from typing import List, Optional
from services.geocoding_service import GoogleGeocodingService
from services.distance_service import DeliveryFeeCalculator
from services.directions_service import DeliveryRouteOptimizer
from services.store_finder import NearestStoreFinder

router = APIRouter(prefix="/api/v1/maps", tags=["maps"])

# Request/Response Models
class GeocodeRequest(BaseModel):
    address: str

class GeocodeResponse(BaseModel):
    valid: bool
    coordinates: Optional[dict]
    formatted_address: Optional[str]
    error: Optional[str]

class DeliveryFeeRequest(BaseModel):
    customer_lat: float
    customer_lng: float
    order_value: float
    warehouse_id: Optional[str] = None

class DeliveryFeeResponse(BaseModel):
    delivery_fee: Optional[float]
    distance_km: Optional[float]
    estimated_time_minutes: Optional[int]
    is_serviceable: bool
    is_free_delivery: bool
    error: Optional[str]

class RouteOptimizationRequest(BaseModel):
    driver_id: str
    delivery_ids: List[str]

# Endpoints
@router.post("/geocode", response_model=GeocodeResponse)
async def geocode_address(request: GeocodeRequest):
    """Validate and geocode customer address."""
    service = GoogleGeocodingService()
    result = service.validate_and_geocode_address(request.address)
    return GeocodeResponse(**result)

@router.post("/delivery-fee", response_model=DeliveryFeeResponse)
async def calculate_delivery_fee(request: DeliveryFeeRequest):
    """Calculate delivery fee based on distance."""
    calculator = DeliveryFeeCalculator()
    
    # Get warehouse location (would fetch from DB)
    warehouse_location = (23.8103, 90.4125)  # Default Dhaka
    
    result = calculator.calculate_delivery_fee(
        customer_location=(request.customer_lat, request.customer_lng),
        warehouse_location=warehouse_location,
        order_value=request.order_value
    )
    
    return DeliveryFeeResponse(
        delivery_fee=result.get('delivery_fee'),
        distance_km=result.get('distance_km'),
        estimated_time_minutes=result.get('estimated_time_minutes'),
        is_serviceable=result.get('is_serviceable', False),
        is_free_delivery=result.get('is_free_delivery', False),
        error=result.get('error')
    )

@router.get("/nearest-stores")
async def find_nearest_stores(
    lat: float,
    lng: float,
    limit: int = 5
):
    """Find nearest Smart Dairy stores."""
    finder = NearestStoreFinder()
    stores = finder.find_nearest_stores(
        customer_location=(lat, lng),
        limit=limit
    )
    return {
        'stores': [
            {
                'id': s.id,
                'name': s.name,
                'address': s.address,
                'distance_km': s.distance_km,
                'travel_time_minutes': s.travel_time_minutes,
                'phone': s.phone,
                'services': s.services
            }
            for s in stores
        ]
    }

@router.post("/optimize-route")
async def optimize_delivery_route(request: RouteOptimizationRequest):
    """Optimize delivery route for driver."""
    optimizer = DeliveryRouteOptimizer()
    
    # Fetch deliveries from DB
    deliveries = await fetch_pending_deliveries(request.delivery_ids)
    
    depot_location = (23.8103, 90.4125)  # Distribution center
    
    result = optimizer.optimize_delivery_route(depot_location, deliveries)
    return result
```

---

## 10. Cost Optimization

### 10.1 Caching Strategy

```python
# services/maps_cache.py
import redis
import json
from functools import wraps
from datetime import timedelta

class MapsCache:
    def __init__(self, redis_client: redis.Redis):
        self.redis = redis_client
        self.default_ttl = timedelta(hours=24)
    
    def cache_geocode(self, address: str, result: dict):
        """Cache geocoding results."""
        key = f"geocode:{hash(address)}"
        self.redis.setex(
            key,
            self.default_ttl,
            json.dumps(result)
        )
    
    def get_cached_geocode(self, address: str) -> Optional[dict]:
        """Get cached geocoding result."""
        key = f"geocode:{hash(address)}"
        cached = self.redis.get(key)
        return json.loads(cached) if cached else None
    
    def cache_distance(self, origin: tuple, dest: tuple, result: dict):
        """Cache distance calculation."""
        key = f"distance:{origin}:{dest}"
        self.redis.setex(
            key,
            timedelta(hours=12),
            json.dumps(result)
        )
    
    def get_cached_distance(self, origin: tuple, dest: tuple) -> Optional[dict]:
        """Get cached distance."""
        key = f"distance:{origin}:{dest}"
        cached = self.redis.get(key)
        return json.loads(cached) if cached else None

# Decorator for caching
def cache_maps_result(cache_key_func, ttl_hours=24):
    def decorator(func):
        @wraps(func)
        def wrapper(self, *args, **kwargs):
            cache = MapsCache(self.redis)
            key = cache_key_func(*args, **kwargs)
            
            # Try cache first
            cached = cache.redis.get(key)
            if cached:
                return json.loads(cached)
            
            # Call function
            result = func(self, *args, **kwargs)
            
            # Cache result
            if result:
                cache.redis.setex(
                    key,
                    timedelta(hours=ttl_hours),
                    json.dumps(result)
                )
            
            return result
        return wrapper
    return decorator
```

### 10.2 Batch Request Optimization

```python
# services/batch_processor.py
from typing import List, Dict, Callable
from concurrent.futures import ThreadPoolExecutor, as_completed
import time

class BatchProcessor:
    def __init__(self, max_workers=5, requests_per_second=50):
        self.max_workers = max_workers
        self.requests_per_second = requests_per_second
        self.min_interval = 1.0 / requests_per_second
    
    def process_batch(
        self, 
        items: List[Dict], 
        process_func: Callable,
        batch_size: int = 25
    ) -> List[Dict]:
        """Process items in batches with rate limiting."""
        results = []
        
        for i in range(0, len(items), batch_size):
            batch = items[i:i + batch_size]
            
            with ThreadPoolExecutor(max_workers=self.max_workers) as executor:
                futures = {
                    executor.submit(process_func, item): item 
                    for item in batch
                }
                
                for future in as_completed(futures):
                    try:
                        result = future.result()
                        results.append(result)
                    except Exception as e:
                        logger.error(f"Batch processing error: {e}")
            
            # Rate limiting
            time.sleep(self.min_interval * batch_size)
        
        return results
```

### 10.3 Usage Quotas and Monitoring

```yaml
# maps_quotas.yaml
quotas:
  geocoding:
    daily_limit: 40000
    per_second: 50
    alert_threshold: 0.8
  
  directions:
    daily_limit: 40000
    per_second: 50
    alert_threshold: 0.8
  
  distance_matrix:
    daily_limit: 40000
    per_second: 50
    elements_per_request: 100
    alert_threshold: 0.8
  
  places:
    autocomplete:
      daily_limit: 6000
      per_session: 100
    details:
      daily_limit: 12000
    alert_threshold: 0.8
  
  maps_js:
    monthly_loads: 100000
    alert_threshold: 0.9
```

---

## 11. Error Handling

### 11.1 API Error Handling

```python
# services/maps_error_handler.py
from enum import Enum
import logging
import time

class MapsErrorCode(Enum):
    OVER_QUERY_LIMIT = "OVER_QUERY_LIMIT"
    REQUEST_DENIED = "REQUEST_DENIED"
    INVALID_REQUEST = "INVALID_REQUEST"
    ZERO_RESULTS = "ZERO_RESULTS"
    MAX_WAYPOINTS_EXCEEDED = "MAX_WAYPOINTS_EXCEEDED"
    MAX_ROUTE_LENGTH_EXCEEDED = "MAX_ROUTE_LENGTH_EXCEEDED"
    UNKNOWN_ERROR = "UNKNOWN_ERROR"
    NETWORK_ERROR = "NETWORK_ERROR"
    TIMEOUT = "TIMEOUT"

class MapsErrorHandler:
    MAX_RETRIES = 3
    BASE_DELAY = 1  # seconds
    
    def __init__(self):
        self.retry_count = 0
    
    def handle_error(self, error_code: MapsErrorCode, context: dict = None) -> dict:
        """Handle API errors with appropriate fallback."""
        
        handlers = {
            MapsErrorCode.OVER_QUERY_LIMIT: self._handle_quota_exceeded,
            MapsErrorCode.REQUEST_DENIED: self._handle_auth_error,
            MapsErrorCode.ZERO_RESULTS: self._handle_no_results,
            MapsErrorCode.NETWORK_ERROR: self._handle_network_error,
            MapsErrorCode.TIMEOUT: self._handle_timeout,
        }
        
        handler = handlers.get(error_code, self._handle_unknown_error)
        return handler(context)
    
    def _handle_quota_exceeded(self, context):
        """Handle quota exceeded - use cache or fallback."""
        logger.warning("Maps API quota exceeded")
        
        # Try cache
        if context and 'cache_key' in context:
            cached = self._get_from_cache(context['cache_key'])
            if cached:
                return {'success': True, 'data': cached, 'source': 'cache'}
        
        # Fallback to approximate calculation
        if context and 'fallback_func' in context:
            return {
                'success': True, 
                'data': context['fallback_func'](),
                'source': 'fallback',
                'approximate': True
            }
        
        return {
            'success': False,
            'error': 'Service temporarily unavailable. Please try again later.',
            'code': 'QUOTA_EXCEEDED'
        }
    
    def _handle_auth_error(self, context):
        """Handle authentication errors."""
        logger.error("Maps API authentication failed")
        return {
            'success': False,
            'error': 'Map service configuration error. Contact support.',
            'code': 'AUTH_ERROR'
        }
    
    def _handle_no_results(self, context):
        """Handle zero results."""
        return {
            'success': False,
            'error': 'Location not found. Please check the address and try again.',
            'code': 'NO_RESULTS'
        }
    
    def _handle_network_error(self, context):
        """Handle network errors with retry."""
        if self.retry_count < self.MAX_RETRIES:
            self.retry_count += 1
            delay = self.BASE_DELAY * (2 ** self.retry_count)
            time.sleep(delay)
            return {'retry': True}
        
        return {
            'success': False,
            'error': 'Network error. Please check your connection.',
            'code': 'NETWORK_ERROR'
        }
    
    def _handle_timeout(self, context):
        """Handle timeout errors."""
        return self._handle_network_error(context)
    
    def _handle_unknown_error(self, context):
        """Handle unknown errors."""
        logger.error(f"Unknown Maps API error: {context}")
        return {
            'success': False,
            'error': 'An unexpected error occurred. Please try again.',
            'code': 'UNKNOWN_ERROR'
        }
```

### 11.2 Flutter Error Handling

```dart
// Flutter error handling
class MapsErrorHandler {
  static String getErrorMessage(dynamic error) {
    if (error is PlatformException) {
      switch (error.code) {
        case 'PERMISSION_DENIED':
          return 'Location permission denied. Please enable location services.';
        case 'LOCATION_SERVICES_DISABLED':
          return 'Location services are disabled. Please enable GPS.';
        case 'API_KEY_INVALID':
          return 'Map configuration error. Please update the app.';
        default:
          return 'Map error: ${error.message}';
      }
    }
    return 'An error occurred while loading maps.';
  }
  
  static void showError(BuildContext context, dynamic error) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(getErrorMessage(error)),
        backgroundColor: Colors.red,
        action: SnackBarAction(
          label: 'Retry',
          onPressed: () {
            // Retry logic
          },
        ),
      ),
    );
  }
}
```

---

## 12. Appendices

### Appendix A: Pricing Calculator

#### Monthly Cost Estimation

| API | Usage | Rate | Monthly Cost (USD) |
|-----|-------|------|-------------------|
| Maps JavaScript API | 10,000 loads | $7.00 per 1,000 | $70.00 |
| Geocoding API | 5,000 requests | $5.00 per 1,000 | $25.00 |
| Directions API | 3,000 requests | $5.00 per 1,000 | $15.00 |
| Distance Matrix API | 4,000 elements | $5.00 per 1,000 | $20.00 |
| Places API - Autocomplete | 10,000 sessions | $2.83 per 1,000 | $28.30 |
| Places API - Details | 5,000 requests | $17.00 per 1,000 | $85.00 |
| **Total** | | | **$243.30** |

#### Cost Reduction Strategies

1. **Session-based Autocomplete**: Group autocomplete requests into sessions
2. **Cache Common Addresses**: Cache results for popular delivery areas
3. **Batch Geocoding**: Geocode addresses in batches during off-peak hours
4. **Static Maps**: Use Maps Static API for simple map images
5. **Field Filtering**: Request only necessary fields in Places API

### Appendix B: Code Examples

#### Complete Flutter Map Screen

```dart
// screens/map_screen.dart
import 'package:flutter/material.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';

class SmartDairyMapScreen extends StatefulWidget {
  @override
  _SmartDairyMapScreenState createState() => _SmartDairyMapScreenState();
}

class _SmartDairyMapScreenState extends State<SmartDairyMapScreen> {
  GoogleMapController? _controller;
  Set<Marker> _markers = {};
  Set<Polyline> _polylines = {};
  
  static const CameraPosition _initialPosition = CameraPosition(
    target: LatLng(23.8103, 90.4125),
    zoom: 12,
  );
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Smart Dairy Locations'),
        actions: [
          IconButton(
            icon: const Icon(Icons.my_location),
            onPressed: _goToCurrentLocation,
          ),
        ],
      ),
      body: GoogleMap(
        mapType: MapType.normal,
        initialCameraPosition: _initialPosition,
        markers: _markers,
        polylines: _polylines,
        myLocationEnabled: true,
        myLocationButtonEnabled: false,
        onMapCreated: (controller) => _controller = controller,
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: _showNearbyStores,
        child: const Icon(Icons.store),
      ),
    );
  }
  
  void _goToCurrentLocation() async {
    // Implementation
  }
  
  void _showNearbyStores() async {
    // Implementation
  }
}
```

#### Python Test Suite

```python
# tests/test_maps_integration.py
import pytest
from unittest.mock import Mock, patch
from services.geocoding_service import GoogleGeocodingService

class TestGeocodingService:
    @pytest.fixture
    def service(self):
        return GoogleGeocodingService(api_key='test_key')
    
    @patch('requests.get')
    def test_geocode_address_success(self, mock_get, service):
        mock_get.return_value.json.return_value = {
            'status': 'OK',
            'results': [{
                'geometry': {
                    'location': {'lat': 23.8103, 'lng': 90.4125}
                },
                'formatted_address': 'Dhaka, Bangladesh',
                'place_id': 'ChIJrQ...',
                'address_components': []
            }]
        }
        
        result = service.geocode_address('Dhaka')
        
        assert result is not None
        assert result.latitude == 23.8103
        assert result.longitude == 90.4125
    
    @patch('requests.get')
    def test_geocode_address_zero_results(self, mock_get, service):
        mock_get.return_value.json.return_value = {
            'status': 'ZERO_RESULTS'
        }
        
        result = service.geocode_address('InvalidAddress123')
        
        assert result is None
```

### Appendix C: Bangladesh-Specific Considerations

#### Address Formatting

```python
# Format for Bangladesh addresses
BD_ADDRESS_FORMAT = """
{house_number} {street_name},
{area/thana},
{district} - {postcode},
Bangladesh
"""

# Common districts
DISTRICTS = [
    'Dhaka', 'Chittagong', 'Khulna', 'Rajshahi', 'Barisal',
    'Sylhet', 'Rangpur', 'Mymensingh', 'Gazipur', 'Narayanganj',
    # ... all 64 districts
]

# Postal code ranges
POSTCODE_RANGES = {
    'Dhaka': (1000, 1299),
    'Chittagong': (4000, 4299),
    'Khulna': (9000, 9299),
    # ...
}
```

#### Local Language Support

| Feature | Bengali (bn) | English (en) |
|---------|-------------|--------------|
| Map Labels | ✓ | ✓ |
| Directions | ✓ | ✓ |
| Address Components | ✓ | ✓ |
| Place Names | Partial | Full |

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Integration Engineer | _________________ | 2026-01-31 |
| Owner | Tech Lead | _________________ | ________ |
| Reviewer | CTO | _________________ | ________ |

---

*End of Document E-009: Google Maps API Integration*
