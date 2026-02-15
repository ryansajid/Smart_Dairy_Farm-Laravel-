# Document E-012: Third-Party Logistics Integration

## Smart Dairy Ltd. - Smart Web Portal System

---

| **Metadata** | **Value** |
|--------------|-----------|
| **Document ID** | E-012 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Integration Engineer |
| **Owner** | Tech Lead |
| **Reviewer** | CTO |
| **Classification** | Internal - Technical |
| **Status** | Draft |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Bangladesh Logistics Providers](#2-bangladesh-logistics-providers)
3. [Integration Architecture](#3-integration-architecture)
4. [API Capabilities](#4-api-capabilities)
5. [Implementation](#5-implementation)
6. [Rate Calculation](#6-rate-calculation)
7. [Shipment Creation](#7-shipment-creation)
8. [Tracking Integration](#8-tracking-integration)
9. [COD (Cash on Delivery)](#9-cod-cash-on-delivery)
10. [Returns Management](#10-returns-management)
11. [Coverage Areas](#11-coverage-areas)
12. [Error Handling](#12-error-handling)
13. [Analytics](#13-analytics)
14. [Appendices](#14-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the technical specifications and implementation guidelines for integrating Third-Party Logistics (3PL) providers with Smart Dairy Ltd.'s Smart Web Portal System. The integration enables automated delivery operations, real-time tracking, and comprehensive logistics management across multiple Bangladesh-based courier services.

### 1.2 Scope

This integration covers:
- Multi-provider logistics aggregation
- Automated shipment creation and management
- Real-time package tracking
- Cash on Delivery (COD) handling
- Returns and exchange logistics
- Rate calculation and zone-based pricing
- Delivery performance analytics

### 1.3 Use Cases

| ID | Use Case | Description |
|----|----------|-------------|
| UC-E012-01 | Automated Shipment Creation | Automatically create shipments with 3PL providers upon order confirmation |
| UC-E012-02 | Real-time Customer Tracking | Provide customers with live tracking updates via web and mobile interfaces |
| UC-E012-03 | Smart Partner Assignment | Automatically assign the optimal delivery partner based on coverage, cost, and performance |
| UC-E012-04 | COD Reconciliation | Track cash collection, remittance status, and reconcile payments |
| UC-E012-05 | Return Logistics | Handle return pickups, exchanges, and reverse logistics workflows |

### 1.4 Business Objectives

1. **Reduce Delivery Time**: Achieve same-day or next-day delivery in major cities
2. **Optimize Costs**: Dynamic provider selection based on cost and performance
3. **Improve Visibility**: Real-time tracking for customers and operations team
4. **Ensure Reliability**: 99.5% delivery success rate through provider redundancy
5. **Scale Operations**: Support 10,000+ daily shipments across Bangladesh

---

## 2. Bangladesh Logistics Providers

### 2.1 Provider Comparison Matrix

| Feature | Pathao | Paperfly | REDX | eCourier |
|---------|--------|----------|------|----------|
| **API Version** | v2.0 | v1.5 | v3.0 | v2.1 |
| **Authentication** | API Key + Secret | OAuth 2.0 | API Key | API Key + HMAC |
| **Rate Limit** | 1000/hour | 500/hour | 2000/hour | 1000/hour |
| **Coverage** | 64 districts | 64 districts | 64 districts | 64 districts |
| **Same-Day Cities** | Dhaka, Chattogram | Dhaka | Dhaka, Chattogram | Dhaka |
| **COD Support** | Yes | Yes | Yes | Yes |
| **Return Service** | Yes | Yes | Yes | Yes |
| **SLA (Metro)** | 24-48 hours | 48-72 hours | 24-48 hours | 48-72 hours |
| **SLA (Outside Metro)** | 3-5 days | 3-5 days | 3-5 days | 3-5 days |
| **Base Rate (1kg)** | ৳60 | ৳55 | ৳58 | ৳52 |
| **API Documentation** | pathao.com/dev | paperfly.com.bd/api | redx.com.bd/api | ecourier.com.bd/api |

### 2.2 Provider Selection Criteria

```python
# Provider selection scoring weights
SELECTION_CRITERIA = {
    'cost': 0.30,           # 30% - Delivery cost
    'speed': 0.25,          # 25% - Estimated delivery time
    'reliability': 0.20,    # 20% - Historical success rate
    'coverage': 0.15,       # 15% - Coverage in destination area
    'cod_fee': 0.10         # 10% - COD handling fee
}
```

### 2.3 Provider-Specific Configurations

#### Pathao
```yaml
provider: pathao
base_url: https://api.pathao.com/v2
auth_type: api_key_secret
endpoints:
  create_order: /orders
  track_order: /orders/{consignment_id}/track
  calculate_price: /pricing
  cancel_order: /orders/{consignment_id}/cancel
features:
  - Express delivery
  - Same-day in Dhaka
  - Real-time tracking
  - COD available
```

#### Paperfly
```yaml
provider: paperfly
base_url: https://api.paperfly.com.bd/v1
auth_type: oauth2
endpoints:
  create_order: /order/create
  track_order: /order/track
  calculate_price: /order/pricing
  cancel_order: /order/cancel
features:
  - Next-day delivery
  - Wide coverage
  - COD available
  - Return service
```

#### REDX
```yaml
provider: redx
base_url: https://api.redx.com.bd/v3
auth_type: api_key
endpoints:
  create_order: /shipments
  track_order: /shipments/{tracking_id}/status
  calculate_price: /pricing/calculate
  cancel_order: /shipments/{tracking_id}/cancel
features:
  - Same-day delivery
  - API reliability
  - COD available
  - Multiple pickup options
```

#### eCourier
```yaml
provider: ecourier
base_url: https://api.ecourier.com.bd/v2
auth_type: api_key_hmac
endpoints:
  create_order: /parcel/create
  track_order: /parcel/track
  calculate_price: /parcel/pricing
  cancel_order: /parcel/cancel
features:
  - Cost-effective
  - Reliable tracking
  - COD available
  - Bulk operations
```

---

## 3. Integration Architecture

### 3.1 High-Level Architecture

```
+-----------------------------------------------------------------------------+
|                         Smart Dairy Web Portal                              |
|  +--------------+  +--------------+  +--------------+  +--------------+     |
|  |   Orders     |  |   Tracking   |  |   Returns    |  |  Analytics   |     |
|  +------+-------+  +------+-------+  +------+-------+  +------+-------+     |
+--------+-----------------+-----------------+-----------------+-------------+
          |                 |                 |                 |
          +-----------------+--------+--------+-----------------+
                                     |
                    +----------------v----------------+
                    |    Logistics Service Layer      |
                    |  +---------------------------+  |
                    |  |   Provider Router         |  |
                    |  |  (Selection Algorithm)    |  |
                    |  +-------------+-------------+  |
                    |                |                |
                    |  +-------------+-------------+  |
                    |  |            |            |    |
                    |  v            v            v    |
                    | +----+     +----+     +----+   |
                    | |Path|     |Pap |     |REDX|   |
                    | |ao  |     |erfl|     |    |   |
                    | |Adap|     |yAda|     |Adap|   |
                    | |ter |     |pter|     |ter |   |
                    | +----+     +----+     +----+   |
                    +----+---------+---------+-------+
                         |          |          |
                         v          v          v
                    +---------------------------------+
                    |     3PL Provider APIs           |
                    |  Pathao | Paperfly | REDX | ...|
                    +---------------------------------+
```

### 3.2 Unified Interface Pattern

The integration uses the **Adapter Pattern** to provide a unified interface across all providers.

### 3.3 Service Layer Components

| Component | Purpose |
|-----------|---------|
| Provider Router | Selects optimal provider based on scoring algorithm |
| Rate Service | Calculates dynamic pricing across providers |
| Tracking Service | Aggregates and caches tracking updates |
| COD Service | Manages cash collection and reconciliation |
| Returns Service | Handles reverse logistics workflows |
| Coverage Service | Manages serviceable areas and zone pricing |
| Analytics Service | Tracks performance metrics and SLA compliance |

---

## 4. API Capabilities

### 4.1 Capability Matrix

| Capability | Pathao | Paperfly | REDX | eCourier |
|------------|--------|----------|------|----------|
| Create Shipment | Yes | Yes | Yes | Yes |
| Track Package | Yes | Yes | Yes | Yes |
| Calculate Rates | Yes | Yes | Yes | Yes |
| Cancel Order | Yes | Yes | Yes | Yes |
| Bulk Operations | No | No | Yes | Yes |
| Webhook Support | Yes | Yes | Yes | Yes |
| Label Generation | Yes | Yes | Yes | PDF/PNG |
| Pickup Scheduling | Yes | Yes | Yes | Yes |
| Serviceability Check | Yes | Yes | Yes | Yes |
| Proof of Delivery | Photo | Photo+Sign | Photo | Signature |

### 4.2 Unified Internal API

```yaml
base_path: /api/v1/logistics

endpoints:
  POST /shipments:
    summary: Create new shipment
    request: ShipmentRequest
    response: ShipmentResponse
  
  GET /shipments/{tracking_id}:
    summary: Get shipment details
    response: ShipmentDetails
  
  DELETE /shipments/{tracking_id}:
    summary: Cancel shipment
    response: CancellationResponse
  
  GET /tracking/{tracking_id}:
    summary: Get tracking updates
    response: TrackingHistory
  
  POST /rates:
    summary: Get rate quotes from all providers
    request: RateRequest
    response: List[RateQuote]
  
  POST /coverage/check:
    summary: Check serviceability
    request: Address
    response: CoverageResponse
  
  POST /returns:
    summary: Initiate return pickup
    request: ReturnRequest
    response: ReturnResponse
  
  POST /webhooks/{provider}:
    summary: Receive provider webhooks
```

---

## 5. Implementation

### 5.1 Core Interface Classes

```python
"""Core Logistics Interface"""
from abc import ABC, abstractmethod
from dataclasses import dataclass
from typing import Optional, List, Dict, Any
from datetime import datetime
from decimal import Decimal
import enum

class DeliveryType(enum.Enum):
    STANDARD = "standard"
    EXPRESS = "express"
    SAME_DAY = "same_day"
    NEXT_DAY = "next_day"

class PackageStatus(enum.Enum):
    PENDING = "pending"
    PICKED_UP = "picked_up"
    IN_TRANSIT = "in_transit"
    OUT_FOR_DELIVERY = "out_for_delivery"
    DELIVERED = "delivered"
    FAILED = "failed"
    RETURNED = "returned"
    CANCELLED = "cancelled"

@dataclass
class Address:
    name: str
    phone: str
    address_line1: str
    address_line2: Optional[str] = None
    city: str = ""
    district: str = ""
    thana: str = ""
    area: str = ""
    postcode: str = ""
    instructions: Optional[str] = None

@dataclass
class Package:
    weight_kg: Decimal
    length_cm: Optional[Decimal] = None
    width_cm: Optional[Decimal] = None
    height_cm: Optional[Decimal] = None
    description: Optional[str] = None
    is_fragile: bool = False
    declared_value: Optional[Decimal] = None

@dataclass
class ShipmentRequest:
    order_id: str
    pickup_address: Address
    delivery_address: Address
    package: Package
    delivery_type: DeliveryType
    is_cod: bool = False
    cod_amount: Optional[Decimal] = None
    invoice_amount: Optional[Decimal] = None
    merchant_order_id: Optional[str] = None
    reference_note: Optional[str] = None

@dataclass
class ShipmentResponse:
    success: bool
    tracking_id: Optional[str] = None
    consignment_id: Optional[str] = None
    label_url: Optional[str] = None
    estimated_delivery: Optional[datetime] = None
    cost: Optional[Decimal] = None
    currency: str = "BDT"
    error_message: Optional[str] = None
    raw_response: Optional[Dict[str, Any]] = None

@dataclass
class TrackingUpdate:
    tracking_id: str
    status: PackageStatus
    location: Optional[str] = None
    description: str = ""
    timestamp: datetime = None
    expected_delivery: Optional[datetime] = None
    delivery_agent_name: Optional[str] = None
    delivery_agent_phone: Optional[str] = None

@dataclass
class RateQuote:
    provider: str
    delivery_type: DeliveryType
    cost: Decimal
    currency: str = "BDT"
    estimated_days: int
    is_available: bool

class LogisticsProvider(ABC):
    """Abstract base class for all logistics providers."""
    
    def __init__(self, config: Dict[str, Any]):
        self.config = config
        self.name = config.get('name', 'unknown')
        self.base_url = config.get('base_url', '')
        self._setup_auth()
    
    @abstractmethod
    def _setup_auth(self) -> None:
        pass
    
    @abstractmethod
    async def create_shipment(self, request: ShipmentRequest) -> ShipmentResponse:
        pass
    
    @abstractmethod
    async def track_shipment(self, tracking_id: str) -> List[TrackingUpdate]:
        pass
    
    @abstractmethod
    async def calculate_rate(self, pickup_address: Address, 
                            delivery_address: Address, 
                            package: Package,
                            delivery_type: DeliveryType) -> List[RateQuote]:
        pass
    
    @abstractmethod
    async def cancel_shipment(self, tracking_id: str, reason: str = "") -> bool:
        pass
    
    @abstractmethod
    async def is_serviceable(self, address: Address) -> bool:
        pass
    
    @abstractmethod
    def parse_webhook(self, payload: Dict[str, Any]) -> TrackingUpdate:
        pass
```

### 5.2 Logistics Service

```python
"""Main Logistics Service"""
from typing import List, Optional, Dict, Any
from dataclasses import dataclass
import asyncio
import logging
from decimal import Decimal

logger = logging.getLogger(__name__)

@dataclass
class ProviderScore:
    provider: str
    score: Decimal
    rate_quote: RateQuote
    coverage_confirmed: bool

class LogisticsService:
    """Main service class for logistics operations."""
    
    SELECTION_CRITERIA = {
        'cost': 0.30,
        'speed': 0.25,
        'reliability': 0.20,
        'coverage': 0.15,
        'cod_fee': 0.10
    }
    
    def __init__(self, config: Dict[str, Any]):
        self.providers: Dict[str, LogisticsProvider] = {}
        self.default_provider = config.get('default_provider', 'pathao')
        self._initialize_providers(config.get('providers', {}))
    
    def _initialize_providers(self, provider_configs: Dict[str, Any]):
        """Initialize all configured provider adapters."""
        adapter_map = {
            'pathao': PathaoAdapter,
            'paperfly': PaperflyAdapter,
            'redx': RedxAdapter,
            'ecourier': ECourierAdapter
        }
        
        for provider_name, provider_config in provider_configs.items():
            if provider_name in adapter_map:
                try:
                    adapter_class = adapter_map[provider_name]
                    self.providers[provider_name] = adapter_class(provider_config)
                    logger.info(f"Initialized {provider_name} adapter")
                except Exception as e:
                    logger.error(f"Failed to initialize {provider_name}: {e}")
    
    async def get_best_provider(self, request: ShipmentRequest,
                                preferred_provider: Optional[str] = None) -> Optional[LogisticsProvider]:
        """Select the best provider based on scoring algorithm."""
        if preferred_provider and preferred_provider in self.providers:
            provider = self.providers[preferred_provider]
            if await provider.is_serviceable(request.delivery_address):
                return provider
        
        scores = await self._score_providers(request)
        if not scores:
            return None
        
        best = max(scores, key=lambda x: x.score)
        return self.providers[best.provider]
    
    async def _score_providers(self, request: ShipmentRequest) -> List[ProviderScore]:
        """Calculate score for each serviceable provider."""
        scores = []
        tasks = [
            self._score_single_provider(name, provider, request)
            for name, provider in self.providers.items()
        ]
        
        results = await asyncio.gather(*tasks, return_exceptions=True)
        
        for result in results:
            if isinstance(result, ProviderScore):
                scores.append(result)
        
        return scores
    
    async def _score_single_provider(self, name: str, provider: LogisticsProvider,
                                     request: ShipmentRequest) -> Optional[ProviderScore]:
        """Calculate score for a single provider."""
        try:
            is_serviceable = await provider.is_serviceable(request.delivery_address)
            if not is_serviceable:
                return None
            
            quotes = await provider.calculate_rate(
                request.pickup_address,
                request.delivery_address,
                request.package,
                request.delivery_type
            )
            
            if not quotes:
                return None
            
            quote = min(quotes, key=lambda x: x.cost)
            
            # Calculate composite score (0-100)
            score = Decimal('0')
            
            # Cost score
            max_cost = Decimal('200')
            cost_score = ((max_cost - min(quote.cost, max_cost)) / max_cost) * 100
            score += cost_score * Decimal(str(self.SELECTION_CRITERIA['cost']))
            
            # Speed score
            max_days = 7
            speed_score = ((max_days - min(quote.estimated_days, max_days)) / max_days) * 100
            score += speed_score * Decimal(str(self.SELECTION_CRITERIA['speed']))
            
            # Reliability score (default 95%)
            reliability = Decimal('0.95')
            score += reliability * 100 * Decimal(str(self.SELECTION_CRITERIA['reliability']))
            
            # Coverage score
            score += Decimal('100') * Decimal(str(self.SELECTION_CRITERIA['coverage']))
            
            return ProviderScore(
                provider=name,
                score=score,
                rate_quote=quote,
                coverage_confirmed=True
            )
        except Exception as e:
            logger.error(f"Error scoring provider {name}: {e}")
            return None
    
    async def create_shipment(self, request: ShipmentRequest,
                              preferred_provider: Optional[str] = None) -> ShipmentResponse:
        """Create a shipment using the best or preferred provider."""
        provider = await self.get_best_provider(request, preferred_provider)
        
        if not provider:
            return ShipmentResponse(
                success=False,
                error_message="No serviceable providers available"
            )
        
        try:
            response = await provider.create_shipment(request)
            await self._log_shipment_creation(request, provider.name, response)
            return response
        except Exception as e:
            logger.error(f"Shipment creation failed: {e}")
            return ShipmentResponse(success=False, error_message=str(e))
    
    async def track_shipment(self, tracking_id: str, provider: str) -> List[TrackingUpdate]:
        """Track a shipment across any provider."""
        if provider not in self.providers:
            raise ValueError(f"Unknown provider: {provider}")
        return await self.providers[provider].track_shipment(tracking_id)
    
    async def _log_shipment_creation(self, request: ShipmentRequest,
                                     provider: str, response: ShipmentResponse):
        """Log shipment creation for analytics."""
        pass
```

---

## 6. Rate Calculation

### 6.1 Rate Calculation Service

```python
"""Rate Calculation Service"""
from decimal import Decimal
from typing import List, Dict, Any
from dataclasses import dataclass
from enum import Enum

class ZoneType(Enum):
    METRO = "metro"
    SUBURBAN = "suburban"
    DISTRICT = "district"
    REMOTE = "remote"

@dataclass
class ZoneRate:
    zone_type: ZoneType
    base_rate: Decimal
    per_kg_rate: Decimal
    cod_percentage: Decimal
    cod_minimum: Decimal
    express_multiplier: Decimal
    same_day_multiplier: Decimal

class RateCalculationService:
    """Service for calculating delivery rates."""
    
    PROVIDER_RATES = {
        'pathao': {
            ZoneType.METRO: ZoneRate(
                zone_type=ZoneType.METRO,
                base_rate=Decimal('60'),
                per_kg_rate=Decimal('15'),
                cod_percentage=Decimal('0.01'),
                cod_minimum=Decimal('10'),
                express_multiplier=Decimal('1.5'),
                same_day_multiplier=Decimal('2.0')
            ),
            ZoneType.SUBURBAN: ZoneRate(
                zone_type=ZoneType.SUBURBAN,
                base_rate=Decimal('80'),
                per_kg_rate=Decimal('20'),
                cod_percentage=Decimal('0.01'),
                cod_minimum=Decimal('10'),
                express_multiplier=Decimal('1.5'),
                same_day_multiplier=Decimal('2.0')
            ),
            ZoneType.DISTRICT: ZoneRate(
                zone_type=ZoneType.DISTRICT,
                base_rate=Decimal('100'),
                per_kg_rate=Decimal('25'),
                cod_percentage=Decimal('0.015'),
                cod_minimum=Decimal('15'),
                express_multiplier=Decimal('1.3'),
                same_day_multiplier=Decimal('2.0')
            ),
        }
    }
    
    METRO_CITIES = ['dhaka', 'chattogram', 'sylhet', 'khulna']
    
    def determine_zone(self, city: str, area: str = None) -> ZoneType:
        """Determine zone type based on city and area."""
        city_lower = city.lower().strip()
        
        if city_lower in self.METRO_CITIES:
            return ZoneType.METRO
        
        remote_districts = ['bandarban', 'rangamati', 'khagrachari']
        if city_lower in remote_districts:
            return ZoneType.REMOTE
        
        return ZoneType.DISTRICT
    
    def calculate_rate(self, provider: str, city: str, area: str,
                       weight_kg: Decimal, delivery_type: str,
                       is_cod: bool = False, cod_amount: Decimal = Decimal('0')) -> Dict[str, Any]:
        """Calculate delivery rate for given parameters."""
        zone = self.determine_zone(city, area)
        provider_rates = self.PROVIDER_RATES.get(provider.lower(), {})
        zone_rate = provider_rates.get(zone)
        
        if not zone_rate:
            raise ValueError(f"No rates for zone: {zone}")
        
        # Calculate base delivery charge
        delivery_charge = zone_rate.base_rate + (zone_rate.per_kg_rate * weight_kg)
        
        # Apply delivery type multiplier
        multiplier = Decimal('1.0')
        if delivery_type == 'express':
            multiplier = zone_rate.express_multiplier
        elif delivery_type == 'same_day':
            multiplier = zone_rate.same_day_multiplier
        
        delivery_charge = delivery_charge * multiplier
        
        # Calculate COD charge
        cod_charge = Decimal('0')
        if is_cod and cod_amount > 0:
            cod_charge = max(cod_amount * zone_rate.cod_percentage, zone_rate.cod_minimum)
        
        total = delivery_charge + cod_charge
        
        return {
            'provider': provider,
            'zone': zone.value,
            'delivery_type': delivery_type,
            'weight_kg': float(weight_kg),
            'delivery_charge': float(delivery_charge),
            'cod_charge': float(cod_charge),
            'total': float(total),
            'currency': 'BDT'
        }
```

---

## 7. Shipment Creation

### 7.1 Order to Shipment Workflow

```python
"""Order to Shipment Workflow"""
from typing import Dict, Any
from decimal import Decimal
from datetime import datetime

class ShipmentWorkflow:
    """Workflow for converting orders to shipments."""
    
    def __init__(self, logistics_service, rate_service, unit_of_work):
        self.logistics_service = logistics_service
        self.rate_service = rate_service
        self.uow = unit_of_work
    
    async def process_order_confirmation(self, order_id: str) -> Dict[str, Any]:
        """Process an order confirmation and create shipment."""
        # Get and validate order
        order = await self.uow.orders.get(order_id)
        if not order:
            raise ValueError(f"Order not found: {order_id}")
        
        # Build shipment request
        shipment_request = self._build_shipment_request(order)
        
        # Get rate quotes and select provider
        rate_quotes = await self.logistics_service.get_all_rates(
            pickup_address=shipment_request.pickup_address,
            delivery_address=shipment_request.delivery_address,
            package=shipment_request.package,
            delivery_type=shipment_request.delivery_type
        )
        
        if not rate_quotes:
            raise ValueError("No serviceable providers for delivery address")
        
        best_quote = rate_quotes[0]
        
        # Create shipment with best provider
        shipment_response = await self.logistics_service.create_shipment(
            request=shipment_request,
            preferred_provider=best_quote.provider
        )
        
        if not shipment_response.success:
            raise Exception(f"Shipment creation failed: {shipment_response.error_message}")
        
        return {
            'success': True,
            'tracking_id': shipment_response.tracking_id,
            'provider': best_quote.provider,
            'label_url': shipment_response.label_url,
            'cost': float(shipment_response.cost)
        }
    
    def _build_shipment_request(self, order) -> ShipmentRequest:
        """Convert order to shipment request."""
        pickup_address = Address(
            name="Smart Dairy Warehouse",
            phone="+8801234567890",
            address_line1="House 12, Road 5, Sector 7",
            city="Dhaka",
            area="Uttara",
            postcode="1230"
        )
        
        delivery_address = Address(
            name=order.customer_name,
            phone=order.customer_phone,
            address_line1=order.shipping_address_line1,
            city=order.shipping_city,
            area=order.shipping_area
        )
        
        package = Package(
            weight_kg=Decimal('1.0'),
            description=f"Order #{order.order_number}",
            is_fragile=True
        )
        
        return ShipmentRequest(
            order_id=order.id,
            pickup_address=pickup_address,
            delivery_address=delivery_address,
            package=package,
            delivery_type=DeliveryType.STANDARD,
            is_cod=order.payment_method == 'cod',
            cod_amount=order.total_amount if order.payment_method == 'cod' else None,
            merchant_order_id=order.order_number
        )
```

---

## 8. Tracking Integration

### 8.1 Real-Time Tracking Service

```python
"""Real-Time Tracking Integration"""
from typing import List, Dict, Any, Optional
from datetime import datetime
import asyncio
import logging

logger = logging.getLogger(__name__)

class TrackingService:
    """Service for managing package tracking."""
    
    CACHE_TTL = 300  # 5 minutes
    POLLING_INTERVAL = 300  # 5 minutes
    
    def __init__(self, logistics_service, cache, notification_service=None):
        self.logistics_service = logistics_service
        self.cache = cache
        self.notification_service = notification_service
    
    async def get_tracking_status(self, tracking_id: str, provider: str,
                                  force_refresh: bool = False) -> Dict[str, Any]:
        """Get current tracking status with caching."""
        cache_key = f"tracking:{provider}:{tracking_id}"
        
        if not force_refresh:
            cached = await self.cache.get(cache_key)
            if cached:
                return cached
        
        updates = await self.logistics_service.track_shipment(tracking_id, provider)
        
        if not updates:
            return {'tracking_id': tracking_id, 'status': 'unknown', 'updates': []}
        
        current = updates[0]
        response = {
            'tracking_id': tracking_id,
            'provider': provider,
            'current_status': current.status.value,
            'current_location': current.location,
            'description': current.description,
            'last_updated': current.timestamp.isoformat(),
            'expected_delivery': current.expected_delivery.isoformat() if current.expected_delivery else None,
            'delivery_agent': {
                'name': current.delivery_agent_name,
                'phone': current.delivery_agent_phone
            } if current.delivery_agent_name else None,
            'is_delivered': current.status == PackageStatus.DELIVERED,
            'history': [
                {
                    'status': u.status.value,
                    'location': u.location,
                    'description': u.description,
                    'timestamp': u.timestamp.isoformat()
                }
                for u in updates
            ]
        }
        
        await self.cache.set(cache_key, response, ttl=self.CACHE_TTL)
        return response
```

### 8.2 Webhook Handler

```python
"""Webhook Handlers for 3PL Providers"""
from fastapi import APIRouter, Request, HTTPException
from typing import Dict, Any

router = APIRouter(prefix="/webhooks/logistics")

class WebhookHandler:
    """Base webhook handler with common functionality."""
    
    def __init__(self, logistics_service, notification_service):
        self.logistics_service = logistics_service
        self.notification_service = notification_service
    
    async def process_tracking_update(self, provider: str, payload: Dict[str, Any]):
        """Process and persist tracking update."""
        provider_adapter = self.logistics_service.providers.get(provider)
        if not provider_adapter:
            raise ValueError(f"Unknown provider: {provider}")
        
        tracking_update = provider_adapter.parse_webhook(payload)
        
        # Update shipment status
        await self._update_shipment_status(tracking_update)
        
        # Send notifications
        await self._send_customer_notifications(tracking_update)
        
        return tracking_update
    
    async def _send_customer_notifications(self, update):
        """Send notifications based on status update."""
        notification_map = {
            'picked_up': "order_picked_up",
            'in_transit': "order_in_transit",
            'out_for_delivery': "out_for_delivery",
            'delivered': "order_delivered",
            'failed': "delivery_failed",
        }
        
        template = notification_map.get(update.status.value)
        if template and self.notification_service:
            await self.notification_service.send(
                template=template,
                tracking_id=update.tracking_id,
                status=update.status.value
            )

@router.post("/pathao")
async def pathao_webhook(request: Request, handler: WebhookHandler):
    """Handle Pathao webhook notifications."""
    payload = await request.json()
    await handler.process_tracking_update('pathao', payload)
    return {"status": "success"}

@router.post("/redx")
async def redx_webhook(request: Request, handler: WebhookHandler):
    """Handle REDX webhook notifications."""
    payload = await request.json()
    await handler.process_tracking_update('redx', payload)
    return {"status": "success"}

@router.post("/paperfly")
async def paperfly_webhook(request: Request, handler: WebhookHandler):
    """Handle Paperfly webhook notifications."""
    payload = await request.json()
    await handler.process_tracking_update('paperfly', payload)
    return {"status": "success"}

@router.post("/ecourier")
async def ecourier_webhook(request: Request, handler: WebhookHandler):
    """Handle eCourier webhook notifications."""
    payload = await request.json()
    await handler.process_tracking_update('ecourier', payload)
    return {"status": "success"}
```

---

## 9. COD (Cash on Delivery)

### 9.1 COD Management Service

```python
"""Cash on Delivery (COD) Management"""
from typing import List, Dict, Any, Optional
from decimal import Decimal
from datetime import datetime, date
from enum import Enum

class CODStatus(Enum):
    PENDING = "pending"
    COLLECTED = "collected"
    REMITTED = "remitted"
    DISPUTED = "disputed"

class CODService:
    """Service for managing COD operations."""
    
    REMITTANCE_CYCLES = {
        'pathao': 3,      # T+3 days
        'redx': 3,
        'paperfly': 7,
        'ecourier': 7,
    }
    
    def __init__(self, logistics_service, accounting_service=None):
        self.logistics_service = logistics_service
        self.accounting_service = accounting_service
    
    async def track_cod_collection(self, shipment_id: str) -> Dict[str, Any]:
        """Track COD collection status for a shipment."""
        shipment = await self._get_shipment(shipment_id)
        if not shipment or not shipment.is_cod:
            raise ValueError("Not a COD shipment")
        
        tracking = await self.logistics_service.track_shipment(
            shipment.tracking_id, shipment.provider
        )
        
        collection_status = CODStatus.PENDING
        collected_amount = Decimal('0')
        collection_date = None
        
        if tracking and tracking[0].status.value == 'delivered':
            collection_status = CODStatus.COLLECTED
            collected_amount = shipment.cod_amount
            collection_date = tracking[0].timestamp
        
        remittance_days = self.REMITTANCE_CYCLES.get(shipment.provider, 7)
        expected_remit_date = None
        if collection_date:
            expected_remit_date = collection_date + timedelta(days=remittance_days)
        
        return {
            'shipment_id': shipment_id,
            'cod_amount': float(shipment.cod_amount),
            'collection_status': collection_status.value,
            'collected_amount': float(collected_amount),
            'collection_date': collection_date.isoformat() if collection_date else None,
            'expected_remit_date': expected_remit_date.isoformat() if expected_remit_date else None
        }
    
    async def get_cod_summary(self, start_date: date, end_date: date,
                              provider: Optional[str] = None) -> Dict[str, Any]:
        """Get COD summary for a date range."""
        shipments = await self._query_shipments({
            'created_at__gte': start_date,
            'created_at__lte': end_date,
            'is_cod': True
        })
        
        if provider:
            shipments = [s for s in shipments if s.provider == provider]
        
        total_cod = sum(s.cod_amount for s in shipments)
        collected = sum(s.cod_amount for s in shipments if s.status == 'delivered')
        remitted = sum(s.cod_amount for s in shipments if s.remittance_status == 'remitted')
        
        return {
            'total_cod_orders': len(shipments),
            'total_cod_amount': float(total_cod),
            'collected_amount': float(collected),
            'remitted_amount': float(remitted),
            'pending_remittance': float(collected - remitted)
        }
```

---

## 10. Returns Management

### 10.1 Returns Service

```python
"""Returns Management Service"""
from typing import List, Dict, Any, Optional
from decimal import Decimal
from datetime import datetime
from enum import Enum

class ReturnReason(Enum):
    DAMAGED = "damaged"
    WRONG_ITEM = "wrong_item"
    NOT_AS_DESCRIBED = "not_as_described"
    CUSTOMER_CHANGED_MIND = "customer_changed_mind"
    QUALITY_ISSUE = "quality_issue"

class ReturnType(Enum):
    REFUND = "refund"
    EXCHANGE = "exchange"
    STORE_CREDIT = "store_credit"

class ReturnsService:
    """Service for managing return logistics."""
    
    RETURN_WINDOW_DAYS = 7
    
    def __init__(self, logistics_service, notification_service=None):
        self.logistics_service = logistics_service
        self.notification_service = notification_service
    
    async def initiate_return(self, order_id: str, items: List[Dict],
                              return_type: ReturnType, reason: ReturnReason) -> Dict[str, Any]:
        """Initiate a return request."""
        order = await self._get_order(order_id)
        if not order:
            raise ValueError(f"Order not found: {order_id}")
        
        # Check eligibility
        days_since_delivery = (datetime.now() - order.delivered_at).days
        if days_since_delivery > self.RETURN_WINDOW_DAYS:
            raise ValueError("Return window expired")
        
        # Schedule pickup
        pickup_response = await self._schedule_return_pickup(order, items)
        
        return {
            'success': True,
            'pickup_tracking_id': pickup_response.get('tracking_id'),
            'status': 'pickup_scheduled'
        }
    
    async def _schedule_return_pickup(self, order, items) -> Dict[str, Any]:
        """Schedule return pickup with logistics provider."""
        pickup_address = Address(
            name=order.customer_name,
            phone=order.customer_phone,
            address_line1=order.shipping_address_line1,
            city=order.shipping_city,
            area=order.shipping_area
        )
        
        delivery_address = Address(
            name="Smart Dairy Returns",
            phone="+8801234567890",
            address_line1="House 12, Road 5, Sector 7",
            city="Dhaka",
            area="Uttara"
        )
        
        package = Package(
            weight_kg=Decimal(str(len(items) * 0.5)),
            description=f"Return for Order #{order.order_number}"
        )
        
        shipment_request = ShipmentRequest(
            order_id=order.id,
            pickup_address=pickup_address,
            delivery_address=delivery_address,
            package=package,
            delivery_type=DeliveryType.STANDARD,
            is_cod=False,
            merchant_order_id=f"RET-{order.order_number}"
        )
        
        response = await self.logistics_service.create_shipment(shipment_request)
        
        return {
            'tracking_id': response.tracking_id,
            'label_url': response.label_url
        }
```

---

## 11. Coverage Areas

### 11.1 Coverage Service

```python
"""Coverage Area Service"""
from typing import List, Dict, Any, Optional
from dataclasses import dataclass

@dataclass
class CoverageArea:
    city: str
    district: str
    thana: str
    area: str
    zone_type: str
    providers: List[str]

class CoverageService:
    """Service for managing delivery coverage areas."""
    
    METRO_CITIES = ['dhaka', 'chattogram', 'sylhet', 'khulna']
    
    def __init__(self, logistics_service, cache):
        self.logistics_service = logistics_service
        self.cache = cache
    
    async def check_coverage(self, city: str, area: str) -> Dict[str, Any]:
        """Check coverage for a specific location."""
        cache_key = f"coverage:{city}:{area}"
        
        cached = await self.cache.get(cache_key)
        if cached:
            return cached
        
        results = {
            'city': city,
            'area': area,
            'is_serviceable': False,
            'providers': []
        }
        
        for provider_name, provider in self.logistics_service.providers.items():
            address = Address(
                name="Test", phone="00000000000",
                address_line1="Test", city=city, area=area
            )
            
            try:
                is_serviceable = await provider.is_serviceable(address)
                if is_serviceable:
                    results['is_serviceable'] = True
                    results['providers'].append(provider_name)
            except Exception:
                pass
        
        await self.cache.set(cache_key, results, ttl=86400)
        return results
    
    async def get_zone_matrix(self) -> Dict[str, Any]:
        """Get zone-based pricing matrix."""
        return {
            'zones': {
                'metro': {
                    'cities': self.METRO_CITIES,
                    'base_rate': 60,
                    'per_kg_rate': 15,
                    'delivery_days': {'same_day': 1, 'express': 1, 'standard': 2}
                },
                'district': {
                    'base_rate': 100,
                    'per_kg_rate': 25,
                    'delivery_days': {'standard': 3}
                },
                'remote': {
                    'base_rate': 150,
                    'per_kg_rate': 35,
                    'delivery_days': {'standard': 5}
                }
            }
        }
```

---

## 12. Error Handling

### 12.1 Error Handling and Recovery

```python
"""Error Handling for Logistics Operations"""
from typing import Dict, Any, Optional
from datetime import datetime, timedelta
from enum import Enum

class FailureReason(Enum):
    CUSTOMER_NOT_AVAILABLE = "customer_not_available"
    ADDRESS_INCORRECT = "address_incorrect"
    CUSTOMER_REFUSED = "customer_refused"
    DAMAGED_IN_TRANSIT = "damaged_in_transit"

class LogisticsErrorHandler:
    """Handler for logistics errors and failed deliveries."""
    
    MAX_DELIVERY_ATTEMPTS = 3
    REATTEMPT_DELAY_HOURS = 24
    
    FAILURE_ACTIONS = {
        FailureReason.CUSTOMER_NOT_AVAILABLE: {
            'reattempt': True,
            'notify_customer': True
        },
        FailureReason.ADDRESS_INCORRECT: {
            'reattempt': False,
            'require_address_update': True
        },
        FailureReason.CUSTOMER_REFUSED: {
            'reattempt': False,
            'initiate_return': True
        },
        FailureReason.DAMAGED_IN_TRANSIT: {
            'reattempt': False,
            'replace_order': True
        }
    }
    
    def __init__(self, logistics_service, notification_service):
        self.logistics_service = logistics_service
        self.notification_service = notification_service
    
    async def handle_failed_delivery(self, shipment_id: str,
                                     failure_reason: str) -> Dict[str, Any]:
        """Handle a failed delivery attempt."""
        shipment = await self._get_shipment(shipment_id)
        attempt_number = len(shipment.failed_attempts) + 1
        
        # Record failed attempt
        await self._record_failed_attempt(shipment_id, failure_reason)
        
        # Check max attempts
        if attempt_number >= self.MAX_DELIVERY_ATTEMPTS:
            await self._handle_max_attempts_reached(shipment)
            return {'action': 'max_attempts_reached'}
        
        # Determine action
        reason = FailureReason(failure_reason)
        action_config = self.FAILURE_ACTIONS.get(reason, {'reattempt': True})
        
        if action_config.get('require_address_update'):
            await self._request_address_update(shipment)
        elif action_config.get('initiate_return'):
            await self._initiate_return(shipment)
        elif action_config.get('reattempt'):
            await self._schedule_reattempt(shipment, attempt_number)
        
        return {'action': action_config}
    
    async def _schedule_reattempt(self, shipment, attempt_number: int):
        """Schedule a delivery reattempt."""
        reattempt_date = datetime.now() + timedelta(hours=self.REATTEMPT_DELAY_HOURS)
        
        await self.notification_service.send(
            template='delivery_reattempt_scheduled',
            tracking_id=shipment.tracking_id,
            reattempt_date=reattempt_date.isoformat(),
            attempt_number=attempt_number
        )
```

---

## 13. Analytics

### 13.1 Delivery Performance Analytics

```python
"""Delivery Performance Analytics"""
from typing import Dict, Any, List, Optional
from datetime import datetime, date, timedelta
import statistics

class LogisticsAnalytics:
    """Analytics service for logistics operations."""
    
    def __init__(self, cache, db_session=None):
        self.cache = cache
        self.db = db_session
    
    async def get_dashboard_metrics(self, start_date: date,
                                    end_date: date) -> Dict[str, Any]:
        """Get key logistics metrics for dashboard."""
        cache_key = f"analytics:dashboard:{start_date}:{end_date}"
        cached = await self.cache.get(cache_key)
        if cached:
            return cached
        
        shipments = await self._get_shipments_in_range(start_date, end_date)
        
        metrics = {
            'period': {'start': start_date.isoformat(), 'end': end_date.isoformat()},
            'overview': self._calculate_overview_metrics(shipments),
            'delivery_performance': self._calculate_delivery_performance(shipments),
            'sla_compliance': self._calculate_sla_compliance(shipments),
            'by_provider': self._calculate_provider_performance(shipments)
        }
        
        await self.cache.set(cache_key, metrics, ttl=3600)
        return metrics
    
    def _calculate_overview_metrics(self, shipments: List) -> Dict[str, Any]:
        """Calculate overview metrics."""
        total = len(shipments)
        if total == 0:
            return {'total_shipments': 0}
        
        delivered = len([s for s in shipments if s.status == 'delivered'])
        failed = len([s for s in shipments if s.status == 'failed'])
        
        return {
            'total_shipments': total,
            'delivered': delivered,
            'failed': failed,
            'delivery_rate': round(delivered / total * 100, 2),
            'failure_rate': round(failed / total * 100, 2)
        }
    
    def _calculate_delivery_performance(self, shipments: List) -> Dict[str, Any]:
        """Calculate delivery performance metrics."""
        delivered = [s for s in shipments if s.status == 'delivered']
        
        if not delivered:
            return {'avg_delivery_time_hours': 0}
        
        delivery_times = []
        for s in delivered:
            if s.delivered_at and s.created_at:
                delta = s.delivered_at - s.created_at
                delivery_times.append(delta.total_seconds() / 3600)
        
        return {
            'avg_delivery_time_hours': round(statistics.mean(delivery_times), 2) if delivery_times else 0,
            'median_delivery_time_hours': round(statistics.median(delivery_times), 2) if delivery_times else 0
        }
    
    def _calculate_sla_compliance(self, shipments: List) -> Dict[str, Any]:
        """Calculate SLA compliance metrics."""
        delivered = [s for s in shipments if s.status == 'delivered']
        
        sla_met = 0
        for s in delivered:
            if s.delivered_at and s.estimated_delivery:
                if s.delivered_at <= s.estimated_delivery:
                    sla_met += 1
        
        total = len(delivered)
        return {
            'sla_compliance_rate': round(sla_met / total * 100, 2) if total > 0 else 0,
            'sla_met': sla_met,
            'sla_missed': total - sla_met
        }
    
    async def generate_provider_scorecard(self, provider: str,
                                          month: int, year: int) -> Dict[str, Any]:
        """Generate monthly scorecard for a provider."""
        start_date = date(year, month, 1)
        if month == 12:
            end_date = date(year + 1, 1, 1) - timedelta(days=1)
        else:
            end_date = date(year, month + 1, 1) - timedelta(days=1)
        
        shipments = await self._get_shipments_in_range(start_date, end_date, provider=provider)
        delivered = [s for s in shipments if s.status == 'delivered']
        
        return {
            'provider': provider,
            'period': f"{year}-{month:02d}",
            'total_shipments': len(shipments),
            'delivery_rate': round(len(delivered) / len(shipments) * 100, 2) if shipments else 0,
            'sla_compliance': self._calculate_sla_compliance(shipments).get('sla_compliance_rate', 0)
        }
```

---

## 14. Appendices

### Appendix A: Provider API Documentation References

| Provider | API Documentation URL | Version |
|----------|----------------------|---------|
| Pathao | https://pathao.com/developer/docs | v2.0 |
| REDX | https://redx.com.bd/api/docs | v3.0 |
| Paperfly | https://paperfly.com.bd/api/documentation | v1.5 |
| eCourier | https://ecourier.com.bd/api/docs | v2.1 |

### Appendix B: Zone Matrix

| Zone Type | Cities/Areas | Base Rate (1kg) | Per Kg Rate | Same Day | Express | Standard |
|-----------|-------------|-----------------|-------------|----------|---------|----------|
| Metro | Dhaka, Chattogram, Sylhet, Khulna | ৳60 | ৳15 | Yes (Dhaka) | Yes | 1-2 days |
| Suburban | Gazipur, Narayanganj, Ashulia | ৳80 | ৳20 | No | Yes | 1-2 days |
| District | All District HQs | ৳100 | ৳25 | No | Yes | 2-3 days |
| Remote | Hill Tracts, Border Areas | ৳150 | ৳35 | No | Limited | 4-5 days |

### Appendix C: Environment Configuration

```yaml
# config/logistics.yaml
logistics:
  default_provider: pathao
  
  selection_criteria:
    cost: 0.30
    speed: 0.25
    reliability: 0.20
    coverage: 0.15
    cod_fee: 0.10
  
  providers:
    pathao:
      name: pathao
      base_url: https://api.pathao.com/v2
      client_id: ${PATHAO_CLIENT_ID}
      client_secret: ${PATHAO_CLIENT_SECRET}
      username: ${PATHAO_USERNAME}
      password: ${PATHAO_PASSWORD}
      store_id: ${PATHAO_STORE_ID}
      enabled: true
    
    redx:
      name: redx
      base_url: https://api.redx.com.bd/v3
      api_key: ${REDX_API_KEY}
      api_secret: ${REDX_API_SECRET}
      enabled: true
    
    paperfly:
      name: paperfly
      base_url: https://api.paperfly.com.bd/v1
      client_id: ${PAPERFLY_CLIENT_ID}
      client_secret: ${PAPERFLY_CLIENT_SECRET}
      enabled: true
    
    ecourier:
      name: ecourier
      base_url: https://api.ecourier.com.bd/v2
      api_key: ${ECOURIER_API_KEY}
      api_secret: ${ECOURIER_API_SECRET}
      enabled: true
```

### Appendix D: Database Schema

```sql
-- Shipments table
CREATE TABLE shipments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    order_id UUID REFERENCES orders(id),
    provider VARCHAR(50) NOT NULL,
    tracking_id VARCHAR(100) NOT NULL,
    consignment_id VARCHAR(100),
    status VARCHAR(50) NOT NULL,
    cost DECIMAL(10, 2),
    label_url TEXT,
    estimated_delivery TIMESTAMP,
    delivered_at TIMESTAMP,
    is_cod BOOLEAN DEFAULT FALSE,
    cod_amount DECIMAL(10, 2),
    remittance_status VARCHAR(50),
    remittance_date TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tracking events table
CREATE TABLE tracking_events (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    shipment_id UUID REFERENCES shipments(id),
    status VARCHAR(50) NOT NULL,
    location VARCHAR(200),
    description TEXT,
    timestamp TIMESTAMP NOT NULL,
    raw_data JSONB,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Failed delivery attempts
CREATE TABLE failed_delivery_attempts (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    shipment_id UUID REFERENCES shipments(id),
    attempt_number INTEGER NOT NULL,
    reason VARCHAR(100) NOT NULL,
    details TEXT,
    delivery_agent_notes TEXT,
    attempted_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Return requests
CREATE TABLE return_requests (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    order_id UUID REFERENCES orders(id),
    customer_id UUID REFERENCES users(id),
    return_type VARCHAR(50) NOT NULL,
    status VARCHAR(50) NOT NULL,
    reason VARCHAR(100) NOT NULL,
    reason_details TEXT,
    items JSONB NOT NULL,
    pickup_tracking_id VARCHAR(100),
    pickup_scheduled_at TIMESTAMP,
    received_at TIMESTAMP,
    resolution VARCHAR(50),
    resolution_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP
);

-- COD remittances
CREATE TABLE cod_remittances (
    id VARCHAR(100) PRIMARY KEY,
    provider VARCHAR(50) NOT NULL,
    remittance_date DATE NOT NULL,
    total_amount DECIMAL(12, 2) NOT NULL,
    total_expected DECIMAL(12, 2) NOT NULL,
    status VARCHAR(50) NOT NULL,
    details JSONB,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Appendix E: Error Codes

| Code | Description | Action |
|------|-------------|--------|
| L001 | Provider API unavailable | Retry with fallback provider |
| L002 | Invalid address | Request address correction |
| L003 | Coverage not available | Notify customer, offer pickup |
| L004 | Rate limit exceeded | Implement exponential backoff |
| L005 | Authentication failed | Refresh credentials |
| L006 | Shipment creation failed | Manual review required |
| L007 | Tracking unavailable | Queue for retry |
| L008 | Cancellation failed | Contact provider support |
| L009 | COD reconciliation mismatch | Initiate dispute |
| L010 | Max delivery attempts reached | Initiate return |

### Appendix F: Sequence Diagrams

#### Shipment Creation Flow
```
Order Service -> Logistics Service: Create Shipment Request
Logistics Service -> Provider Router: Get Best Provider
Provider Router -> Pathao: Check Coverage & Rates
Provider Router -> REDX: Check Coverage & Rates
Provider Router -> Paperfly: Check Coverage & Rates
Provider Router -> Logistics Service: Return Best Provider
Logistics Service -> Pathao: Create Shipment
Pathao -> Logistics Service: Return Tracking ID
Logistics Service -> Order Service: Return Shipment Details
Order Service -> Customer: Send Tracking Notification
```

#### Tracking Update Flow
```
Pathao -> Webhook Handler: Status Update
Webhook Handler -> Tracking Service: Process Update
Tracking Service -> Database: Save Event
Tracking Service -> Notification Service: Send Notification
Notification Service -> Customer: Push Notification
```

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-31 | Integration Engineer | Initial document |

---

*End of Document E-012: Third-Party Logistics Integration*
