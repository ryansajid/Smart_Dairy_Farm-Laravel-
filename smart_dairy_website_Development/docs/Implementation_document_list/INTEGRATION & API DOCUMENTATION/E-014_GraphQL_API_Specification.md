# E-014: GraphQL API Specification

## Smart Dairy Ltd. - Smart Web Portal System

---

| **Document Information** | |
|--------------------------|---|
| **Document ID** | E-014 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Integration Engineer |
| **Owner** | Tech Lead |
| **Reviewer** | CTO |
| **Classification** | Internal - Technical Specification |
| **Status** | Draft |

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Integration Engineer | Initial Release |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Schema Design](#2-schema-design)
3. [Core Types](#3-core-types)
4. [Query Examples](#4-query-examples)
5. [Mutation Examples](#5-mutation-examples)
6. [Subscriptions](#6-subscriptions)
7. [Pagination](#7-pagination)
8. [Filtering & Sorting](#8-filtering--sorting)
9. [Error Handling](#9-error-handling)
10. [Implementation](#10-implementation)
11. [Performance](#11-performance)
12. [Security](#12-security)
13. [Federation](#13-federation)
14. [Appendices](#14-appendices)

---

## 1. Introduction

### 1.1 Overview

This document defines the GraphQL API Specification for the Smart Dairy Smart Web Portal System. GraphQL serves as the primary API layer for all client applications including the B2C E-commerce Portal, B2B Marketplace Portal, Farm Management Portal, and Mobile Applications.

### 1.2 GraphQL Benefits Over REST for Smart Dairy

| Aspect | REST Approach | GraphQL Approach | Benefit for Smart Dairy |
|--------|---------------|------------------|------------------------|
| **Data Fetching** | Multiple endpoints, over/under-fetching | Single endpoint, exact data request | Reduces mobile data usage for field staff |
| **API Versioning** | URL versioning (/v1/, /v2/) | Schema evolution without versioning | Seamless updates across all portals |
| **Real-time Data** | Polling or separate WebSocket | Native subscriptions | Live order tracking for customers |
| **Type Safety** | Manual validation | Schema-defined types | Fewer runtime errors in farm data |
| **Documentation** | External Swagger/OpenAPI | Introspective schema | Self-documenting API for partners |
| **Mobile Optimization** | Multiple round trips | Single request | Better performance on rural networks |

### 1.3 Use Cases for Smart Dairy

#### 1.3.1 B2C E-commerce Portal
- **Product Catalog Browsing**: Fetch products with reviews, ratings, and inventory status in a single query
- **Shopping Cart**: Retrieve cart with product details, pricing, and availability
- **Order Tracking**: Real-time subscription for order status updates
- **User Profile**: Fetch user data, order history, addresses, and loyalty points

#### 1.3.2 B2B Marketplace Portal
- **Bulk Ordering**: Complex queries with product variants, tiered pricing, and credit limits
- **Invoice Management**: Fetch invoices with line items, payment status, and aging reports
- **Partner Dashboard**: Aggregated data across multiple entities

#### 1.3.3 Farm Management Portal
- **Herd Management**: Cattle profiles with health records, lineage, and production data
- **Milk Production Tracking**: Time-series data with filtering and aggregation
- **IoT Sensor Data**: Real-time subscriptions for environmental monitoring

#### 1.3.4 Mobile Applications
- **Offline-First**: Optimized queries for caching and synchronization
- **Low Bandwidth**: Minimal payload size through precise field selection
- **Real-time Notifications**: Push-like updates via subscriptions

### 1.4 Architecture Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           CLIENT APPLICATIONS                                │
├─────────────────────────────────────────────────────────────────────────────┤
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    │
│  │  B2C Portal  │  │  B2B Portal  │  │ Farm Portal  │  │ Mobile Apps  │    │
│  │   (React)    │  │   (React)    │  │   (React)    │  │  (Flutter)   │    │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘    │
│         │                 │                 │                 │            │
│         └─────────────────┴─────────────────┴─────────────────┘            │
│                                    │                                        │
│                           GraphQL Client (Apollo/Flutter)                   │
│                                    │                                        │
└────────────────────────────────────┼────────────────────────────────────────┘
                                     │
                              ┌──────▼──────┐
                              │  GraphQL    │
                              │  Endpoint   │
                              │  /graphql   │
                              └──────┬──────┘
                                     │
┌────────────────────────────────────┼────────────────────────────────────────┐
│                         GRAPHQL SERVER LAYER                               │
├────────────────────────────────────┼────────────────────────────────────────┤
│                                    │                                        │
│    ┌───────────────────────────────▼───────────────────────────────┐       │
│    │                    Strawberry GraphQL                         │       │
│    │                   (Python/FastAPI)                            │       │
│    │                                                               │       │
│    │  ┌──────────────┐  ┌──────────────┐  ┌──────────────────────┐ │       │
│    │  │   Schema     │  │  Resolvers   │  │    DataLoaders       │ │       │
│    │  │  Definition  │  │              │  │   (N+1 Prevention)   │ │       │
│    │  └──────────────┘  └──────────────┘  └──────────────────────┘ │       │
│    │                                                               │       │
│    │  ┌──────────────┐  ┌──────────────┐  ┌──────────────────────┐ │       │
│    │  │ Auth Middleware│ │  Complexity  │  │   Subscriptions      │ │       │
│    │  │   (JWT)      │  │    Limiting  │  │   (WebSocket)        │ │       │
│    │  └──────────────┘  └──────────────┘  └──────────────────────┘ │       │
│    └───────────────────────────────────────────────────────────────┘       │
│                                    │                                        │
└────────────────────────────────────┼────────────────────────────────────────┘
                                     │
┌────────────────────────────────────┼────────────────────────────────────────┐
│                      BACKEND SERVICES LAYER                                │
├────────────────────────────────────┼────────────────────────────────────────┤
│                                    │                                        │
│    ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│    │ Odoo ERP     │  │  PostgreSQL  │  │    Redis     │  │    Kafka     │  │
│    │  API Layer   │  │   Database   │  │    Cache     │  │   Events     │  │
│    └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘  │
│                                                                           │
│    ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│    │ IoT Platform │  │  MinIO/S3    │  │ Elasticsearch│  │  External    │  │
│    │  (MQTT)      │  │   Storage    │  │    Search    │  │   APIs       │  │
│    └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘  │
│                                                                           │
└───────────────────────────────────────────────────────────────────────────┘
```

---

## 2. Schema Design

### 2.1 Schema Definition Language (SDL)

The Smart Dairy GraphQL schema is defined using Strawberry, a modern Python library for GraphQL that provides type annotations and automatic schema generation.

### 2.2 Schema Organization

```
schema/
├── __init__.py
├── scalars.py          # Custom scalars (DateTime, JSON, Decimal)
├── enums.py            # GraphQL enums
├── inputs.py           # Input types for mutations
├── interfaces.py       # Shared interfaces
├── types/
│   ├── __init__.py
│   ├── user.py         # User, Address, Profile types
│   ├── product.py      # Product, Category, Variant types
│   ├── order.py        # Order, OrderItem, Payment types
│   ├── farm.py         # Cattle, HealthRecord, MilkProduction types
│   ├── review.py       # Review, Rating types
│   └── common.py       # PageInfo, Connection types
├── queries.py          # Root Query type
├── mutations.py        # Root Mutation type
└── subscriptions.py    # Root Subscription type
```

### 2.3 Root Types

```python
# schema/schema.py
import strawberry
from typing import List, Optional
from .queries import Query
from .mutations import Mutation
from .subscriptions import Subscription

schema = strawberry.Schema(
    query=Query,
    mutation=Mutation,
    subscription=Subscription,
    extensions=[
        strawberry.extensions.QueryDepthLimiter(max_depth=10),
        strawberry.extensions.ValidationCache,
    ]
)
```

### 2.4 Custom Scalars

```python
# schema/scalars.py
import strawberry
from datetime import datetime, date
from decimal import Decimal
from typing import NewType

# Custom scalar for JSON data
JSON = strawberry.scalar(
    NewType("JSON", object),
    serialize=lambda v: v,
    parse_value=lambda v: v,
    description="Arbitrary JSON object"
)

# Custom scalar for Date (without time)
Date = strawberry.scalar(
    NewType("Date", date),
    serialize=lambda v: v.isoformat(),
    parse_value=lambda v: date.fromisoformat(v),
    description="ISO 8601 date string (YYYY-MM-DD)"
)

# Custom scalar for DateTime
DateTime = strawberry.scalar(
    NewType("DateTime", datetime),
    serialize=lambda v: v.isoformat(),
    parse_value=lambda v: datetime.fromisoformat(v.replace('Z', '+00:00')),
    description="ISO 8601 datetime string"
)

# Custom scalar for Decimal (monetary values)
Decimal = strawberry.scalar(
    NewType("Decimal", Decimal),
    serialize=lambda v: str(v),
    parse_value=lambda v: Decimal(v),
    description="Decimal number as string for precise financial calculations"
)
```

---

## 3. Core Types

### 3.1 User Types

```python
# schema/types/user.py
import strawberry
from typing import List, Optional
from datetime import datetime
from enum import Enum

@strawberry.enum
class UserRole(Enum):
    CUSTOMER = "customer"
    B2B_PARTNER = "b2b_partner"
    FARM_STAFF = "farm_staff"
    ADMIN = "admin"
    SUPER_ADMIN = "super_admin"

@strawberry.enum
class UserStatus(Enum):
    ACTIVE = "active"
    INACTIVE = "inactive"
    PENDING_VERIFICATION = "pending_verification"
    SUSPENDED = "suspended"

@strawberry.type
class Address:
    id: strawberry.ID
    street_address: str
    apartment: Optional[str] = None
    city: str
    state: str
    postal_code: str
    country: str = "Bangladesh"
    is_default: bool = False
    label: Optional[str] = None  # "Home", "Office", "Farm"
    latitude: Optional[float] = None
    longitude: Optional[float] = None
    created_at: datetime
    updated_at: datetime

@strawberry.type
class UserProfile:
    id: strawberry.ID
    user_id: strawberry.ID
    first_name: str
    last_name: str
    phone: Optional[str] = None
    date_of_birth: Optional[str] = None
    avatar_url: Optional[str] = None
    preferred_language: str = "en"  # "en" or "bn"
    notification_preferences: Optional[strawberry.scalars.JSON] = None
    loyalty_points: int = 0
    loyalty_tier: str = "bronze"  # bronze, silver, gold, platinum

@strawberry.type
class User:
    id: strawberry.ID
    email: str
    role: UserRole
    status: UserStatus
    is_verified: bool
    profile: Optional[UserProfile] = None
    addresses: List[Address] = strawberry.field(default_factory=list)
    created_at: datetime
    updated_at: datetime
    last_login: Optional[datetime] = None
    
    @strawberry.field
    def full_name(self) -> str:
        if self.profile:
            return f"{self.profile.first_name} {self.profile.last_name}"
        return self.email
    
    @strawberry.field
    def orders(self, info, first: int = 10, after: Optional[str] = None) -> "OrderConnection":
        # Resolver implementation
        pass
```

### 3.2 Product Types

```python
# schema/types/product.py
import strawberry
from typing import List, Optional
from decimal import Decimal
from datetime import datetime
from enum import Enum

@strawberry.enum
class ProductStatus(Enum):
    DRAFT = "draft"
    ACTIVE = "active"
    OUT_OF_STOCK = "out_of_stock"
    DISCONTINUED = "discontinued"

@strawberry.enum
class ProductType(Enum):
    DAIRY = "dairy"
    MEAT = "meat"
    SUBSCRIPTION = "subscription"
    BUNDLE = "bundle"

@strawberry.type
class Category:
    id: strawberry.ID
    name: str
    slug: str
    description: Optional[str] = None
    image_url: Optional[str] = None
    parent: Optional["Category"] = None
    children: List["Category"] = strawberry.field(default_factory=list)
    product_count: int = 0
    sort_order: int = 0
    is_active: bool = True
    meta_title: Optional[str] = None
    meta_description: Optional[str] = None

@strawberry.type
class ProductVariant:
    id: strawberry.ID
    sku: str
    name: str
    price: Decimal
    compare_at_price: Optional[Decimal] = None
    stock_quantity: int
    weight_grams: Optional[int] = None
    volume_ml: Optional[int] = None
    attributes: Optional[strawberry.scalars.JSON] = None  # { "size": "1L", "flavor": "plain" }
    is_default: bool = False
    barcode: Optional[str] = None

@strawberry.type
class ProductImage:
    id: strawberry.ID
    url: str
    alt_text: Optional[str] = None
    sort_order: int = 0
    is_primary: bool = False

@strawberry.type
class ProductNutrition:
    id: strawberry.ID
    serving_size: str
    calories: Optional[int] = None
    protein_g: Optional[float] = None
    fat_g: Optional[float] = None
    carbohydrates_g: Optional[float] = None
    calcium_mg: Optional[float] = None
    other_nutrients: Optional[strawberry.scalars.JSON] = None

@strawberry.type
class Product:
    id: strawberry.ID
    name: str
    slug: str
    sku: str
    description: str
    short_description: Optional[str] = None
    product_type: ProductType
    status: ProductStatus
    
    # Pricing
    base_price: Decimal
    compare_at_price: Optional[Decimal] = None
    cost_price: Optional[Decimal] = None
    
    # Categorization
    categories: List[Category] = strawberry.field(default_factory=list)
    tags: List[str] = strawberry.field(default_factory=list)
    
    # Variants & Inventory
    variants: List[ProductVariant] = strawberry.field(default_factory=list)
    has_variants: bool = False
    track_inventory: bool = True
    allow_backorders: bool = False
    
    # Media
    images: List[ProductImage] = strawberry.field(default_factory=list)
    primary_image: Optional[ProductImage] = None
    
    # Product Details
    nutrition_info: Optional[ProductNutrition] = None
    ingredients: Optional[str] = None
    storage_instructions: Optional[str] = None
    shelf_life_days: Optional[int] = None
    origin: Optional[str] = "Smart Dairy Farm, Narayanganj"
    certifications: List[str] = strawberry.field(default_factory=list)  # ["Organic", "Antibiotic-Free"]
    
    # SEO
    meta_title: Optional[str] = None
    meta_description: Optional[str] = None
    
    # Reviews
    average_rating: Optional[float] = None
    review_count: int = 0
    
    # Timestamps
    created_at: datetime
    updated_at: datetime
    published_at: Optional[datetime] = None
    
    # Computed fields
    @strawberry.field
    def is_on_sale(self) -> bool:
        if self.compare_at_price and self.base_price < self.compare_at_price:
            return True
        return False
    
    @strawberry.field
    def discount_percentage(self) -> Optional[float]:
        if self.is_on_sale and self.compare_at_price:
            discount = (self.compare_at_price - self.base_price) / self.compare_at_price
            return round(float(discount) * 100, 2)
        return None
    
    @strawberry.field
    def reviews(self, info, first: int = 10, after: Optional[str] = None) -> "ReviewConnection":
        # Resolver implementation
        pass
```

### 3.3 Order Types

```python
# schema/types/order.py
import strawberry
from typing import List, Optional
from decimal import Decimal
from datetime import datetime
from enum import Enum

@strawberry.enum
class OrderStatus(Enum):
    PENDING = "pending"
    CONFIRMED = "confirmed"
    PROCESSING = "processing"
    READY_FOR_DELIVERY = "ready_for_delivery"
    OUT_FOR_DELIVERY = "out_for_delivery"
    DELIVERED = "delivered"
    CANCELLED = "cancelled"
    REFUNDED = "refunded"

@strawberry.enum
class PaymentStatus(Enum):
    PENDING = "pending"
    AUTHORIZED = "authorized"
    PAID = "paid"
    PARTIALLY_PAID = "partially_paid"
    REFUNDED = "refunded"
    FAILED = "failed"

@strawberry.enum
class PaymentMethod(Enum):
    BKASH = "bkash"
    NAGAD = "nagad"
    ROCKET = "rocket"
    CARD = "card"
    CASH_ON_DELIVERY = "cash_on_delivery"
    BANK_TRANSFER = "bank_transfer"

@strawberry.enum
class DeliveryType(Enum):
    HOME_DELIVERY = "home_delivery"
    PICKUP = "pickup"
    EXPRESS = "express"
    SUBSCRIPTION = "subscription"

@strawberry.type
class OrderItem:
    id: strawberry.ID
    order_id: strawberry.ID
    product_id: strawberry.ID
    product_name: str
    product_sku: str
    variant_id: Optional[strawberry.ID] = None
    variant_name: Optional[str] = None
    quantity: int
    unit_price: Decimal
    total_price: Decimal
    discount_amount: Decimal = Decimal("0")
    tax_amount: Decimal = Decimal("0")
    product_image_url: Optional[str] = None

@strawberry.type
class Payment:
    id: strawberry.ID
    order_id: strawberry.ID
    amount: Decimal
    method: PaymentMethod
    status: PaymentStatus
    transaction_id: Optional[str] = None
    gateway_response: Optional[strawberry.scalars.JSON] = None
    paid_at: Optional[datetime] = None
    created_at: datetime

@strawberry.type
class DeliverySlot:
    id: strawberry.ID
    date: str  # ISO date
    start_time: str  # HH:MM
    end_time: str  # HH:MM
    is_available: bool = True

@strawberry.type
class OrderStatusHistory:
    id: strawberry.ID
    status: OrderStatus
    previous_status: Optional[OrderStatus] = None
    notes: Optional[str] = None
    created_by: Optional[str] = None  # user_id or "system"
    created_at: datetime

@strawberry.type
class Order:
    id: strawberry.ID
    order_number: str  # Human-readable order number (e.g., "SD-2026-0001")
    user_id: strawberry.ID
    
    # Status
    status: OrderStatus
    status_history: List[OrderStatusHistory] = strawberry.field(default_factory=list)
    
    # Items
    items: List[OrderItem] = strawberry.field(default_factory=list)
    item_count: int = 0
    
    # Pricing
    subtotal: Decimal
    discount_total: Decimal = Decimal("0")
    tax_total: Decimal = Decimal("0")
    delivery_fee: Decimal = Decimal("0")
    total: Decimal
    
    # Currency (for future multi-currency support)
    currency: str = "BDT"
    
    # Delivery Information
    delivery_type: DeliveryType
    delivery_address: Optional["Address"] = None
    delivery_instructions: Optional[str] = None
    delivery_slot: Optional[DeliverySlot] = None
    estimated_delivery: Optional[datetime] = None
    actual_delivery: Optional[datetime] = None
    
    # Payment
    payment_status: PaymentStatus
    payment_method: PaymentMethod
    payments: List[Payment] = strawberry.field(default_factory=list)
    
    # Tracking
    tracking_number: Optional[str] = None
    tracking_url: Optional[str] = None
    
    # Metadata
    notes: Optional[str] = None
    source: str = "web"  # web, mobile, b2b, admin
    ip_address: Optional[str] = None
    user_agent: Optional[str] = None
    
    # B2B Fields
    purchase_order_number: Optional[str] = None  # For B2B orders
    credit_days: Optional[int] = None
    
    # Subscription Fields
    is_subscription_order: bool = False
    subscription_id: Optional[strawberry.ID] = None
    
    # Timestamps
    created_at: datetime
    updated_at: datetime
    confirmed_at: Optional[datetime] = None
    
    @strawberry.field
    def can_cancel(self) -> bool:
        cancellable_statuses = [OrderStatus.PENDING, OrderStatus.CONFIRMED]
        return self.status in cancellable_statuses
    
    @strawberry.field
    def total_items(self) -> int:
        return sum(item.quantity for item in self.items)
```

### 3.4 Farm Types

```python
# schema/types/farm.py
import strawberry
from typing import List, Optional
from decimal import Decimal
from datetime import datetime, date
from enum import Enum

@strawberry.enum
class CattleGender(Enum):
    MALE = "male"
    FEMALE = "female"

@strawberry.enum
class CattleStatus(Enum):
    ACTIVE = "active"
    SOLD = "sold"
    DECEASED = "deceased"
    QUARANTINE = "quarantine"

@strawberry.enum
class CattleCategory(Enum):
    LACTATING = "lactating"
    DRY = "dry"
    HEIFER = "heifer"
    CALF = "calf"
    BULL = "bull"
    FATTEING = "fattening"

@strawberry.enum
class HealthRecordType(Enum):
    VACCINATION = "vaccination"
    TREATMENT = "treatment"
    CHECKUP = "checkup"
    DEWORMING = "deworming"
    BREEDING = "breeding"
    CALVING = "calving"

@strawberry.type
class CattleBreed:
    id: strawberry.ID
    name: str
    origin: Optional[str] = None
    characteristics: Optional[str] = None
    average_milk_yield: Optional[float] = None  # liters per day

@strawberry.type
class Cattle:
    id: strawberry.ID
    tag_number: str  # RFID or ear tag number
    name: Optional[str] = None
    
    # Basic Info
    breed: CattleBreed
    gender: CattleGender
    status: CattleStatus
    category: CattleCategory
    
    # Dates
    birth_date: Optional[date] = None
    acquisition_date: date
    acquisition_type: str  # born_on_farm, purchased, donated
    
    # Physical Characteristics
    weight_kg: Optional[float] = None
    color: Optional[str] = None
    markings: Optional[str] = None
    
    # Parentage
    mother_id: Optional[strawberry.ID] = None
    father_id: Optional[strawberry.ID] = None
    mother: Optional["Cattle"] = None
    father: Optional["Cattle"] = None
    
    # Production (for lactating cows)
    current_lactation_number: int = 0
    last_calving_date: Optional[date] = None
    is_pregnant: bool = False
    expected_calving_date: Optional[date] = None
    
    # Images
    photo_url: Optional[str] = None
    
    # Location
    shed_id: Optional[strawberry.ID] = None
    shed_name: Optional[str] = None
    
    # Timestamps
    created_at: datetime
    updated_at: datetime
    
    @strawberry.field
    def age_days(self) -> Optional[int]:
        if self.birth_date:
            return (date.today() - self.birth_date).days
        return None
    
    @strawberry.field
    def age_display(self) -> Optional[str]:
        days = self.age_days()
        if days is None:
            return None
        years = days // 365
        months = (days % 365) // 30
        if years > 0:
            return f"{years}y {months}m"
        return f"{months}m"
    
    @strawberry.field
    def milk_production_today(self) -> Optional[float]:
        # Resolver to fetch today's milk production
        pass
    
    @strawberry.field
    def health_records(self, info, first: int = 10, after: Optional[str] = None) -> "HealthRecordConnection":
        pass

@strawberry.type
class HealthRecord:
    id: strawberry.ID
    cattle_id: strawberry.ID
    cattle: Cattle
    record_type: HealthRecordType
    
    # Details
    date: date
    description: str
    diagnosis: Optional[str] = None
    treatment: Optional[str] = None
    medication: Optional[str] = None
    dosage: Optional[str] = None
    
    # Personnel
    veterinarian_name: Optional[str] = None
    recorded_by: str
    
    # Follow-up
    follow_up_date: Optional[date] = None
    follow_up_notes: Optional[str] = None
    
    # Cost
    cost: Optional[Decimal] = None
    
    created_at: datetime

@strawberry.type
class MilkProduction:
    id: strawberry.ID
    cattle_id: strawberry.ID
    cattle: Cattle
    
    # Production Data
    date: date
    session: str  # morning, evening, night
    volume_liters: float
    
    # Quality Parameters
    fat_percentage: Optional[float] = None
    snf_percentage: Optional[float] = None  # Solids-Not-Fat
    protein_percentage: Optional[float] = None
    lactose_percentage: Optional[float] = None
    density: Optional[float] = None
    temperature_celsius: Optional[float] = None
    
    # Quality Grade
    grade: Optional[str] = None  # A, B, C
    
    # Recording
    recorded_by: str
    milking_method: str = "machine"  # machine, hand
    
    created_at: datetime

@strawberry.type
class MilkProductionSummary:
    date: date
    total_volume_liters: float
    session_count: int
    cattle_count: int
    average_fat: Optional[float] = None
    average_snf: Optional[float] = None

@strawberry.type
class FarmShed:
    id: strawberry.ID
    name: str
    code: str
    capacity: int
    current_occupancy: int = 0
    purpose: str  # lactating, dry, calves, quarantine
    is_active: bool = True
```

### 3.5 Review Types

```python
# schema/types/review.py
import strawberry
from typing import List, Optional
from datetime import datetime

@strawberry.type
class Review:
    id: strawberry.ID
    product_id: strawberry.ID
    user_id: strawberry.ID
    user_name: str
    user_avatar: Optional[str] = None
    
    # Rating (1-5)
    rating: int
    title: Optional[str] = None
    content: str
    
    # Helpfulness
    helpful_count: int = 0
    not_helpful_count: int = 0
    
    # Moderation
    is_approved: bool = True
    is_verified_purchase: bool = False
    
    # Images
    images: List[str] = strawberry.field(default_factory=list)
    
    # Response from Smart Dairy
    response: Optional[str] = None
    responded_at: Optional[datetime] = None
    responded_by: Optional[str] = None
    
    created_at: datetime
    updated_at: datetime

@strawberry.type
class ReviewConnection:
    edges: List["ReviewEdge"]
    page_info: "PageInfo"
    total_count: int
    average_rating: Optional[float] = None
    rating_distribution: Optional[strawberry.scalars.JSON] = None  # { "5": 10, "4": 5, ... }

@strawberry.type
class ReviewEdge:
    node: Review
    cursor: str
```

### 3.6 Connection Types (Pagination)

```python
# schema/types/common.py
import strawberry
from typing import List, Optional, Generic, TypeVar
from typing_extensions import TypeVar

T = TypeVar("T")

@strawberry.type
class PageInfo:
    has_next_page: bool
    has_previous_page: bool
    start_cursor: Optional[str] = None
    end_cursor: Optional[str] = None
    total_count: int = 0

@strawberry.type
class Edge(Generic[T]):
    node: T
    cursor: str

@strawberry.type
class Connection(Generic[T]):
    edges: List[Edge[T]]
    page_info: PageInfo
    total_count: int

# Specific Connection Types
@strawberry.type
class UserConnection:
    edges: List["UserEdge"]
    page_info: PageInfo
    total_count: int

@strawberry.type
class UserEdge:
    node: "User"
    cursor: str

@strawberry.type
class ProductConnection:
    edges: List["ProductEdge"]
    page_info: PageInfo
    total_count: int

@strawberry.type
class ProductEdge:
    node: "Product"
    cursor: str

@strawberry.type
class OrderConnection:
    edges: List["OrderEdge"]
    page_info: PageInfo
    total_count: int

@strawberry.type
class OrderEdge:
    node: "Order"
    cursor: str

@strawberry.type
class CattleConnection:
    edges: List["CattleEdge"]
    page_info: PageInfo
    total_count: int

@strawberry.type
class CattleEdge:
    node: "Cattle"
    cursor: str

@strawberry.type
class HealthRecordConnection:
    edges: List["HealthRecordEdge"]
    page_info: PageInfo
    total_count: int

@strawberry.type
class HealthRecordEdge:
    node: "HealthRecord"
    cursor: str

@strawberry.type
class MilkProductionConnection:
    edges: List["MilkProductionEdge"]
    page_info: PageInfo
    total_count: int

@strawberry.type
class MilkProductionEdge:
    node: "MilkProduction"
    cursor: str
```

---

## 4. Query Examples

### 4.1 Root Query Definition

```python
# schema/queries.py
import strawberry
from typing import List, Optional

from .types.user import User, UserConnection
from .types.product import Product, ProductConnection, Category
from .types.order import Order, OrderConnection
from .types.farm import Cattle, CattleConnection, MilkProductionSummary, FarmShed
from .types.review import ReviewConnection
from .types.common import PageInfo
from .inputs import (
    ProductFilterInput, OrderFilterInput, CattleFilterInput,
    PaginationInput, SortInput
)

@strawberry.type
class Query:
    # ============================================
    # User Queries
    # ============================================
    
    @strawberry.field
    async def me(self, info) -> Optional[User]:
        """Get the currently authenticated user."""
        user = info.context.user
        if not user:
            return None
        return await info.context.loaders.user.load(user.id)
    
    @strawberry.field
    async def user(self, info, id: strawberry.ID) -> Optional[User]:
        """Get a user by ID (admin only)."""
        # Check permissions
        await info.context.auth.require_admin(info)
        return await info.context.loaders.user.load(id)
    
    @strawberry.field
    async def users(
        self,
        info,
        filter: Optional["UserFilterInput"] = None,
        sort: Optional[SortInput] = None,
        pagination: Optional[PaginationInput] = None
    ) -> UserConnection:
        """List users with filtering and pagination (admin only)."""
        await info.context.auth.require_admin(info)
        return await info.context.services.user.list_users(
            filter=filter,
            sort=sort,
            pagination=pagination
        )
    
    # ============================================
    # Product Queries
    # ============================================
    
    @strawberry.field
    async def product(
        self,
        info,
        id: Optional[strawberry.ID] = None,
        slug: Optional[str] = None
    ) -> Optional[Product]:
        """Get a product by ID or slug."""
        if id:
            return await info.context.loaders.product.load(id)
        if slug:
            return await info.context.services.product.get_by_slug(slug)
        raise ValueError("Either id or slug must be provided")
    
    @strawberry.field
    async def products(
        self,
        info,
        filter: Optional[ProductFilterInput] = None,
        sort: Optional[SortInput] = None,
        pagination: Optional[PaginationInput] = None
    ) -> ProductConnection:
        """List products with filtering, sorting, and pagination."""
        return await info.context.services.product.list_products(
            filter=filter,
            sort=sort,
            pagination=pagination
        )
    
    @strawberry.field
    async def categories(
        self,
        info,
        parent_id: Optional[strawberry.ID] = None,
        is_active: bool = True
    ) -> List[Category]:
        """List product categories."""
        return await info.context.services.product.list_categories(
            parent_id=parent_id,
            is_active=is_active
        )
    
    @strawberry.field
    async def category(
        self,
        info,
        id: strawberry.ID
    ) -> Optional[Category]:
        """Get a category by ID."""
        return await info.context.loaders.category.load(id)
    
    @strawberry.field
    async def search_products(
        self,
        info,
        query: str,
        pagination: Optional[PaginationInput] = None
    ) -> ProductConnection:
        """Search products by name, description, or tags."""
        return await info.context.services.product.search(
            query=query,
            pagination=pagination
        )
    
    # ============================================
    # Order Queries
    # ============================================
    
    @strawberry.field
    async def order(
        self,
        info,
        id: Optional[strawberry.ID] = None,
        order_number: Optional[str] = None
    ) -> Optional[Order]:
        """Get an order by ID or order number."""
        user = info.context.user
        
        if id:
            order = await info.context.loaders.order.load(id)
        elif order_number:
            order = await info.context.services.order.get_by_number(order_number)
        else:
            raise ValueError("Either id or order_number must be provided")
        
        # Check permissions - users can only see their own orders
        if order and not info.context.auth.can_view_order(user, order):
            raise PermissionError("You don't have permission to view this order")
        
        return order
    
    @strawberry.field
    async def my_orders(
        self,
        info,
        filter: Optional[OrderFilterInput] = None,
        sort: Optional[SortInput] = None,
        pagination: Optional[PaginationInput] = None
    ) -> OrderConnection:
        """Get orders for the current user."""
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        
        return await info.context.services.order.list_orders(
            user_id=user.id,
            filter=filter,
            sort=sort,
            pagination=pagination
        )
    
    @strawberry.field
    async def orders(
        self,
        info,
        filter: Optional[OrderFilterInput] = None,
        sort: Optional[SortInput] = None,
        pagination: Optional[PaginationInput] = None
    ) -> OrderConnection:
        """List all orders (admin only)."""
        await info.context.auth.require_admin(info)
        return await info.context.services.order.list_orders(
            filter=filter,
            sort=sort,
            pagination=pagination
        )
    
    # ============================================
    # Farm Queries
    # ============================================
    
    @strawberry.field
    async def cattle(
        self,
        info,
        id: strawberry.ID
    ) -> Optional[Cattle]:
        """Get a cattle record by ID."""
        await info.context.auth.require_farm_staff(info)
        return await info.context.loaders.cattle.load(id)
    
    @strawberry.field
    async def cattle_list(
        self,
        info,
        filter: Optional[CattleFilterInput] = None,
        sort: Optional[SortInput] = None,
        pagination: Optional[PaginationInput] = None
    ) -> CattleConnection:
        """List cattle with filtering and pagination."""
        await info.context.auth.require_farm_staff(info)
        return await info.context.services.farm.list_cattle(
            filter=filter,
            sort=sort,
            pagination=pagination
        )
    
    @strawberry.field
    async def milk_production_summary(
        self,
        info,
        start_date: str,
        end_date: str
    ) -> List[MilkProductionSummary]:
        """Get daily milk production summaries for a date range."""
        await info.context.auth.require_farm_staff(info)
        return await info.context.services.farm.get_production_summary(
            start_date=start_date,
            end_date=end_date
        )
    
    @strawberry.field
    async def sheds(
        self,
        info,
        is_active: bool = True
    ) -> List[FarmShed]:
        """List farm sheds."""
        await info.context.auth.require_farm_staff(info)
        return await info.context.services.farm.list_sheds(is_active=is_active)
```

### 4.2 GraphQL Query Examples

#### 4.2.1 Fetch Product with Reviews

```graphql
query GetProductWithReviews($slug: String!, $reviewsFirst: Int!) {
  product(slug: $slug) {
    id
    name
    slug
    description
    shortDescription
    basePrice
    compareAtPrice
    isOnSale
    discountPercentage
    status
    primaryImage {
      url
      altText
    }
    images {
      url
      altText
    }
    nutritionInfo {
      servingSize
      calories
      proteinG
      fatG
      carbohydratesG
      calciumMg
    }
    ingredients
    storageInstructions
    certifications
    averageRating
    reviewCount
    reviews(first: $reviewsFirst) {
      edges {
        node {
          id
          rating
          title
          content
          userName
          userAvatar
          isVerifiedPurchase
          helpfulCount
          images
          response
          createdAt
        }
        cursor
      }
      pageInfo {
        hasNextPage
        endCursor
      }
      totalCount
      averageRating
      ratingDistribution
    }
  }
}

# Variables
{
  "slug": "saffron-organic-milk-1l",
  "reviewsFirst": 5
}
```

#### 4.2.2 Fetch Products with Filters

```graphql
query GetFilteredProducts(
  $filter: ProductFilterInput
  $sort: SortInput
  $first: Int!
  $after: String
) {
  products(
    filter: $filter
    sort: $sort
    pagination: { first: $first, after: $after }
  ) {
    edges {
      node {
        id
        name
        slug
        shortDescription
        basePrice
        compareAtPrice
        isOnSale
        primaryImage {
          url
        }
        averageRating
        reviewCount
        categories {
          id
          name
        }
      }
      cursor
    }
    pageInfo {
      hasNextPage
      endCursor
      totalCount
    }
  }
}

# Variables
{
  "filter": {
    "categoryId": "cat_dairy",
    "status": "ACTIVE",
    "priceRange": {
      "min": 50.00,
      "max": 500.00
    },
    "inStock": true,
    "certifications": ["Organic"]
  },
  "sort": {
    "field": "CREATED_AT",
    "direction": "DESC"
  },
  "first": 20
}
```

#### 4.2.3 Fetch User Orders with Details

```graphql
query GetMyOrders(
  $filter: OrderFilterInput
  $first: Int!
  $after: String
) {
  myOrders(
    filter: $filter
    sort: { field: CREATED_AT, direction: DESC }
    pagination: { first: $first, after: $after }
  ) {
    edges {
      node {
        id
        orderNumber
        status
        paymentStatus
        total
        currency
        itemCount
        deliveryType
        estimatedDelivery
        trackingNumber
        trackingUrl
        createdAt
        canCancel
        items {
          id
          productName
          productSku
          variantName
          quantity
          unitPrice
          totalPrice
          productImageUrl
        }
        deliveryAddress {
          streetAddress
          city
          postalCode
          label
        }
        payments {
          id
          amount
          method
          status
          paidAt
        }
        statusHistory {
          status
          previousStatus
          notes
          createdAt
        }
      }
      cursor
    }
    pageInfo {
      hasNextPage
      endCursor
    }
    totalCount
  }
}

# Variables
{
  "filter": {
    "status": ["PENDING", "CONFIRMED", "PROCESSING", "OUT_FOR_DELIVERY"]
  },
  "first": 10
}
```

#### 4.2.4 Fetch Farm Data - Cattle List

```graphql
query GetCattleList(
  $filter: CattleFilterInput
  $first: Int!
  $after: String
) {
  cattleList(
    filter: $filter
    sort: { field: TAG_NUMBER, direction: ASC }
    pagination: { first: $first, after: $after }
  ) {
    edges {
      node {
        id
        tagNumber
        name
        breed {
          name
        }
        gender
        status
        category
        ageDisplay
        weightKg
        photoUrl
        shedName
        currentLactationNumber
        isPregnant
        expectedCalvingDate
        milkProductionToday
      }
      cursor
    }
    pageInfo {
      hasNextPage
      endCursor
      totalCount
    }
  }
}

# Variables
{
  "filter": {
    "category": "LACTATING",
    "status": "ACTIVE",
    "isPregnant": false
  },
  "first": 50
}
```

#### 4.2.5 Complex Nested Query - Cart with Recommendations

```graphql
query GetCartWithRecommendations {
  me {
    id
    fullName
    cart {
      id
      items {
        id
        product {
          id
          name
          slug
          basePrice
          primaryImage {
            url
          }
        }
        variant {
          id
          name
          sku
        }
        quantity
        unitPrice
        totalPrice
      }
      subtotal
      taxTotal
      deliveryFee
      total
      itemCount
    }
  }
  recommendedProducts: products(
    filter: { status: ACTIVE, inStock: true }
    sort: { field: POPULARITY, direction: DESC }
    pagination: { first: 6 }
  ) {
    edges {
      node {
        id
        name
        slug
        basePrice
        primaryImage {
          url
        }
        averageRating
      }
    }
  }
}
```



---

## 5. Mutation Examples

### 5.1 Root Mutation Definition

```python
# schema/mutations.py
import strawberry
from typing import Optional

from .types.user import User, UserProfile, Address
from .types.product import Product, Category
from .types.order import Order, Payment
from .types.review import Review
from .inputs import (
    CreateUserInput, UpdateUserInput,
    CreateOrderInput, UpdateOrderStatusInput,
    CreateReviewInput, UpdateCartInput,
    AddToCartInput, RemoveFromCartInput
)

@strawberry.type
class Mutation:
    # ============================================
    # Authentication Mutations
    # ============================================
    
    @strawberry.mutation
    async def register(self, info, input: CreateUserInput) -> "AuthPayload":
        """Register a new user account."""
        return await info.context.services.auth.register(input)
    
    @strawberry.mutation
    async def login(self, info, email: str, password: str) -> "AuthPayload":
        """Authenticate user and return tokens."""
        return await info.context.services.auth.login(email, password)
    
    @strawberry.mutation
    async def logout(self, info) -> bool:
        """Logout the current user."""
        return await info.context.services.auth.logout(info.context.user)
    
    @strawberry.mutation
    async def refreshToken(self, info, refresh_token: str) -> "TokenPayload":
        """Refresh access token using refresh token."""
        return await info.context.services.auth.refresh_token(refresh_token)
    
    @strawberry.mutation
    async def requestPasswordReset(self, info, email: str) -> "MutationResponse":
        """Request password reset email."""
        return await info.context.services.auth.request_password_reset(email)
    
    @strawberry.mutation
    async def resetPassword(
        self,
        info,
        token: str,
        new_password: str
    ) -> "MutationResponse":
        """Reset password using reset token."""
        return await info.context.services.auth.reset_password(token, new_password)
    
    @strawberry.mutation
    async def verifyEmail(self, info, token: str) -> "MutationResponse":
        """Verify email address."""
        return await info.context.services.auth.verify_email(token)
    
    # ============================================
    # User Profile Mutations
    # ============================================
    
    @strawberry.mutation
    async def updateProfile(
        self,
        info,
        input: UpdateUserInput
    ) -> User:
        """Update current user profile."""
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        return await info.context.services.user.update_profile(user.id, input)
    
    @strawberry.mutation
    async def addAddress(self, info, input: "AddressInput") -> Address:
        """Add a new address to the user's account."""
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        return await info.context.services.user.add_address(user.id, input)
    
    @strawberry.mutation
    async def updateAddress(
        self,
        info,
        id: strawberry.ID,
        input: "AddressInput"
    ) -> Address:
        """Update an existing address."""
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        return await info.context.services.user.update_address(user.id, id, input)
    
    @strawberry.mutation
    async def deleteAddress(self, info, id: strawberry.ID) -> bool:
        """Delete an address."""
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        return await info.context.services.user.delete_address(user.id, id)
    
    @strawberry.mutation
    async def setDefaultAddress(self, info, id: strawberry.ID) -> Address:
        """Set an address as default."""
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        return await info.context.services.user.set_default_address(user.id, id)
    
    # ============================================
    # Cart Mutations
    # ============================================
    
    @strawberry.mutation
    async def addToCart(self, info, input: AddToCartInput) -> "Cart":
        """Add an item to the cart."""
        user = info.context.user
        if not user:
            # Handle guest cart via session/local storage
            raise PermissionError("Authentication required")
        return await info.context.services.cart.add_item(user.id, input)
    
    @strawberry.mutation
    async def updateCartItem(
        self,
        info,
        item_id: strawberry.ID,
        quantity: int
    ) -> "Cart":
        """Update cart item quantity."""
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        return await info.context.services.cart.update_item(user.id, item_id, quantity)
    
    @strawberry.mutation
    async def removeFromCart(self, info, item_id: strawberry.ID) -> "Cart":
        """Remove an item from the cart."""
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        return await info.context.services.cart.remove_item(user.id, item_id)
    
    @strawberry.mutation
    async def clearCart(self, info) -> "Cart":
        """Clear all items from the cart."""
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        return await info.context.services.cart.clear(user.id)
    
    @strawberry.mutation
    async def applyCoupon(self, info, code: str) -> "Cart":
        """Apply a coupon code to the cart."""
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        return await info.context.services.cart.apply_coupon(user.id, code)
    
    @strawberry.mutation
    async def removeCoupon(self, info) -> "Cart":
        """Remove the applied coupon from the cart."""
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        return await info.context.services.cart.remove_coupon(user.id)
    
    # ============================================
    # Order Mutations
    # ============================================
    
    @strawberry.mutation
    async def createOrder(self, info, input: CreateOrderInput) -> Order:
        """Create a new order from cart."""
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        return await info.context.services.order.create(user.id, input)
    
    @strawberry.mutation
    async def cancelOrder(self, info, order_id: strawberry.ID, reason: Optional[str] = None) -> Order:
        """Cancel an order."""
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        return await info.context.services.order.cancel(user.id, order_id, reason)
    
    @strawberry.mutation
    async def updateOrderStatus(
        self,
        info,
        order_id: strawberry.ID,
        input: UpdateOrderStatusInput
    ) -> Order:
        """Update order status (admin/staff only)."""
        await info.context.auth.require_staff(info)
        return await info.context.services.order.update_status(order_id, input)
    
    @strawberry.mutation
    async def processPayment(
        self,
        info,
        order_id: strawberry.ID,
        method: str,
        payment_data: Optional[strawberry.scalars.JSON] = None
    ) -> Payment:
        """Process payment for an order."""
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        return await info.context.services.payment.process(
            user.id, order_id, method, payment_data
        )
    
    # ============================================
    # Review Mutations
    # ============================================
    
    @strawberry.mutation
    async def createReview(self, info, input: CreateReviewInput) -> Review:
        """Submit a product review."""
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        return await info.context.services.review.create(user.id, input)
    
    @strawberry.mutation
    async def updateReview(
        self,
        info,
        review_id: strawberry.ID,
        rating: Optional[int] = None,
        title: Optional[str] = None,
        content: Optional[str] = None
    ) -> Review:
        """Update an existing review."""
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        return await info.context.services.review.update(
            user.id, review_id, rating, title, content
        )
    
    @strawberry.mutation
    async def deleteReview(self, info, review_id: strawberry.ID) -> bool:
        """Delete a review."""
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        return await info.context.services.review.delete(user.id, review_id)
    
    @strawberry.mutation
    async def markReviewHelpful(
        self,
        info,
        review_id: strawberry.ID,
        is_helpful: bool
    ) -> Review:
        """Mark a review as helpful or not helpful."""
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        return await info.context.services.review.mark_helpful(
            user.id, review_id, is_helpful
        )

# Supporting types for mutations
@strawberry.type
class AuthPayload:
    success: bool
    user: Optional[User] = None
    access_token: Optional[str] = None
    refresh_token: Optional[str] = None
    expires_in: Optional[int] = None  # seconds
    message: Optional[str] = None
    errors: Optional[strawberry.scalars.JSON] = None

@strawberry.type
class TokenPayload:
    access_token: str
    refresh_token: str
    expires_in: int

@strawberry.type
class MutationResponse:
    success: bool
    message: str
    errors: Optional[strawberry.scalars.JSON] = None
```

### 5.2 GraphQL Mutation Examples

#### 5.2.1 User Registration

```graphql
mutation Register($input: CreateUserInput!) {
  register(input: $input) {
    success
    user {
      id
      email
      profile {
        firstName
        lastName
        fullName
      }
    }
    accessToken
    refreshToken
    expiresIn
    message
    errors
  }
}

# Variables
{
  "input": {
    "email": "customer@example.com",
    "password": "SecurePass123!",
    "firstName": "John",
    "lastName": "Doe",
    "phone": "+8801234567890",
    "preferredLanguage": "en"
  }
}
```

#### 5.2.2 Login

```graphql
mutation Login($email: String!, $password: String!) {
  login(email: $email, password: $password) {
    success
    user {
      id
      email
      role
      profile {
        firstName
        lastName
        fullName
        loyaltyPoints
        loyaltyTier
      }
    }
    accessToken
    refreshToken
    expiresIn
    message
  }
}
```

#### 5.2.3 Update User Profile

```graphql
mutation UpdateProfile($input: UpdateUserInput!) {
  updateProfile(input: $input) {
    id
    email
    profile {
      firstName
      lastName
      fullName
      phone
      dateOfBirth
      preferredLanguage
      notificationPreferences
    }
    updatedAt
  }
}

# Variables
{
  "input": {
    "firstName": "Jane",
    "lastName": "Smith",
    "phone": "+8801987654321",
    "dateOfBirth": "1990-05-15",
    "preferredLanguage": "bn",
    "notificationPreferences": {
      "email": true,
      "sms": true,
      "push": true,
      "orderUpdates": true,
      "promotions": false
    }
  }
}
```

#### 5.2.4 Add Address

```graphql
mutation AddAddress($input: AddressInput!) {
  addAddress(input: $input) {
    id
    streetAddress
    apartment
    city
    state
    postalCode
    country
    label
    isDefault
    latitude
    longitude
    createdAt
  }
}

# Variables
{
  "input": {
    "streetAddress": "123 Main Street, Block B",
    "apartment": "Floor 3, Apt 301",
    "city": "Dhaka",
    "state": "Dhaka Division",
    "postalCode": "1207",
    "label": "Home",
    "latitude": 23.8103,
    "longitude": 90.4125,
    "isDefault": true
  }
}
```

#### 5.2.5 Add to Cart

```graphql
mutation AddToCart($input: AddToCartInput!) {
  addToCart(input: $input) {
    id
    items {
      id
      product {
        id
        name
        slug
        primaryImage {
          url
        }
      }
      variant {
        id
        name
        sku
      }
      quantity
      unitPrice
      totalPrice
    }
    subtotal
    taxTotal
    deliveryFee
    discountAmount
    couponCode
    total
    itemCount
  }
}

# Variables
{
  "input": {
    "productId": "prod_123",
    "variantId": "var_456",
    "quantity": 2
  }
}
```

#### 5.2.6 Create Order

```graphql
mutation CreateOrder($input: CreateOrderInput!) {
  createOrder(input: $input) {
    id
    orderNumber
    status
    paymentStatus
    items {
      id
      productName
      productSku
      variantName
      quantity
      unitPrice
      totalPrice
    }
    subtotal
    discountTotal
    taxTotal
    deliveryFee
    total
    deliveryType
    deliveryAddress {
      streetAddress
      city
      postalCode
      label
    }
    deliverySlot {
      date
      startTime
      endTime
    }
    deliveryInstructions
    canCancel
    createdAt
  }
}

# Variables
{
  "input": {
    "addressId": "addr_789",
    "deliveryType": "HOME_DELIVERY",
    "deliverySlotId": "slot_101",
    "deliveryInstructions": "Ring the bell twice",
    "paymentMethod": "BKASH",
    "notes": "Please call before delivery",
    "source": "mobile"
  }
}
```

#### 5.2.7 Submit Review

```graphql
mutation CreateReview($input: CreateReviewInput!) {
  createReview(input: $input) {
    id
    productId
    rating
    title
    content
    userName
    isVerifiedPurchase
    images
    createdAt
  }
}

# Variables
{
  "input": {
    "productId": "prod_123",
    "rating": 5,
    "title": "Excellent organic milk!",
    "content": "This is the freshest milk I've ever tasted. The taste is amazing and I love knowing it's 100% organic and antibiotic-free. Highly recommend!",
    "images": [
      "https://cdn.smartdairy.com/reviews/img_001.jpg",
      "https://cdn.smartdairy.com/reviews/img_002.jpg"
    ]
  }
}
```

#### 5.2.8 Cancel Order

```graphql
mutation CancelOrder($orderId: ID!, $reason: String) {
  cancelOrder(orderId: $orderId, reason: $reason) {
    id
    orderNumber
    status
    statusHistory {
      status
      previousStatus
      notes
      createdAt
    }
    canCancel
  }
}

# Variables
{
  "orderId": "order_456",
  "reason": "Changed my mind, ordered wrong items"
}
```

### 5.3 Input Types

```python
# schema/inputs.py
import strawberry
from typing import List, Optional
from decimal import Decimal
from datetime import datetime

from .types.product import ProductStatus
from .types.order import OrderStatus, PaymentMethod, DeliveryType
from .types.farm import CattleCategory, CattleStatus

# ============================================
# Pagination & Sorting Inputs
# ============================================

@strawberry.input
class PaginationInput:
    first: Optional[int] = 20  # Limit
    after: Optional[str] = None  # Cursor
    last: Optional[int] = None
    before: Optional[str] = None

@strawberry.enum
class SortDirection:
    ASC = "asc"
    DESC = "desc"

@strawberry.enum
class ProductSortField:
    NAME = "name"
    PRICE = "base_price"
    CREATED_AT = "created_at"
    UPDATED_AT = "updated_at"
    POPULARITY = "popularity"
    RATING = "average_rating"

@strawberry.enum
class OrderSortField:
    ORDER_NUMBER = "order_number"
    TOTAL = "total"
    CREATED_AT = "created_at"
    STATUS = "status"

@strawberry.input
class SortInput:
    field: str
    direction: SortDirection = SortDirection.DESC

# ============================================
# Filter Inputs
# ============================================

@strawberry.input
class PriceRangeInput:
    min: Optional[Decimal] = None
    max: Optional[Decimal] = None

@strawberry.input
class ProductFilterInput:
    categoryId: Optional[strawberry.ID] = None
    categorySlug: Optional[str] = None
    status: Optional[ProductStatus] = ProductStatus.ACTIVE
    productType: Optional[str] = None
    priceRange: Optional[PriceRangeInput] = None
    inStock: Optional[bool] = None
    tags: Optional[List[str]] = None
    certifications: Optional[List[str]] = None
    searchQuery: Optional[str] = None

@strawberry.input
class OrderFilterInput:
    status: Optional[List[OrderStatus]] = None
    paymentStatus: Optional[List[str]] = None
    paymentMethod: Optional[List[PaymentMethod]] = None
    dateFrom: Optional[str] = None  # ISO date
    dateTo: Optional[str] = None
    minTotal: Optional[Decimal] = None
    maxTotal: Optional[Decimal] = None
    isSubscriptionOrder: Optional[bool] = None

@strawberry.input
class CattleFilterInput:
    category: Optional[CattleCategory] = None
    status: Optional[CattleStatus] = CattleStatus.ACTIVE
    gender: Optional[str] = None
    breedId: Optional[strawberry.ID] = None
    shedId: Optional[strawberry.ID] = None
    isPregnant: Optional[bool] = None
    ageMinDays: Optional[int] = None
    ageMaxDays: Optional[int] = None

@strawberry.input
class UserFilterInput:
    role: Optional[List[str]] = None
    status: Optional[List[str]] = None
    isVerified: Optional[bool] = None
    searchQuery: Optional[str] = None

# ============================================
# User Input Types
# ============================================

@strawberry.input
class CreateUserInput:
    email: str
    password: str
    firstName: str
    lastName: str
    phone: Optional[str] = None
    preferredLanguage: str = "en"

@strawberry.input
class UpdateUserInput:
    firstName: Optional[str] = None
    lastName: Optional[str] = None
    phone: Optional[str] = None
    dateOfBirth: Optional[str] = None
    preferredLanguage: Optional[str] = None
    notificationPreferences: Optional[strawberry.scalars.JSON] = None

@strawberry.input
class AddressInput:
    streetAddress: str
    apartment: Optional[str] = None
    city: str
    state: str
    postalCode: str
    country: str = "Bangladesh"
    label: Optional[str] = None
    latitude: Optional[float] = None
    longitude: Optional[float] = None
    isDefault: bool = False

# ============================================
# Cart Input Types
# ============================================

@strawberry.input
class AddToCartInput:
    productId: strawberry.ID
    variantId: Optional[strawberry.ID] = None
    quantity: int

@strawberry.input
class UpdateCartInput:
    itemId: strawberry.ID
    quantity: int

@strawberry.input
class RemoveFromCartInput:
    itemId: strawberry.ID

# ============================================
# Order Input Types
# ============================================

@strawberry.input
class CreateOrderInput:
    addressId: strawberry.ID
    deliveryType: DeliveryType
    deliverySlotId: Optional[strawberry.ID] = None
    deliveryInstructions: Optional[str] = None
    paymentMethod: PaymentMethod
    couponCode: Optional[str] = None
    notes: Optional[str] = None
    source: str = "web"  # web, mobile, b2b, admin
    purchaseOrderNumber: Optional[str] = None  # For B2B

@strawberry.input
class UpdateOrderStatusInput:
    status: OrderStatus
    notes: Optional[str] = None
    trackingNumber: Optional[str] = None
    trackingUrl: Optional[str] = None

# ============================================
# Review Input Types
# ============================================

@strawberry.input
class CreateReviewInput:
    productId: strawberry.ID
    rating: int
    title: Optional[str] = None
    content: str
    images: Optional[List[str]] = None
```

---

## 6. Subscriptions

### 6.1 Root Subscription Definition

```python
# schema/subscriptions.py
import strawberry
from typing import AsyncGenerator, Optional
import asyncio

from .types.order import Order
from .types.farm import MilkProduction
from .types.common import Notification

@strawberry.type
class Subscription:
    # ============================================
    # Order Subscriptions
    # ============================================
    
    @strawberry.subscription
    async def orderStatusChanged(
        self,
        info,
        orderId: strawberry.ID
    ) -> AsyncGenerator["OrderStatusUpdate", None]:
        """
        Subscribe to real-time order status updates.
        """
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        
        # Verify user has access to this order
        order = await info.context.loaders.order.load(orderId)
        if not order or order.user_id != user.id:
            raise PermissionError("Access denied")
        
        # Subscribe to order events
        async for update in info.context.event_bus.subscribe(
            f"order:{orderId}:status_changed"
        ):
            yield OrderStatusUpdate(
                order_id=orderId,
                status=update["status"],
                previous_status=update.get("previous_status"),
                message=update.get("message"),
                timestamp=update["timestamp"]
            )
    
    @strawberry.subscription
    async def myOrdersUpdated(
        self,
        info
    ) -> AsyncGenerator["OrderUpdate", None]:
        """
        Subscribe to updates for all user's orders.
        """
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        
        async for update in info.context.event_bus.subscribe(
            f"user:{user.id}:orders"
        ):
            yield OrderUpdate(
                order_id=update["order_id"],
                event_type=update["event_type"],
                order=update.get("order"),
                timestamp=update["timestamp"]
            )
    
    # ============================================
    # Notification Subscriptions
    # ============================================
    
    @strawberry.subscription
    async def notifications(
        self,
        info
    ) -> AsyncGenerator["Notification", None]:
        """
        Subscribe to real-time notifications for the current user.
        """
        user = info.context.user
        if not user:
            raise PermissionError("Authentication required")
        
        async for notification in info.context.event_bus.subscribe(
            f"user:{user.id}:notifications"
        ):
            yield Notification(
                id=notification["id"],
                type=notification["type"],
                title=notification["title"],
                message=notification["message"],
                data=notification.get("data"),
                is_read=notification.get("is_read", False),
                created_at=notification["created_at"]
            )
    
    # ============================================
    # Farm Subscriptions (IoT Data)
    # ============================================
    
    @strawberry.subscription
    async def milkProductionRecorded(
        self,
        info
    ) -> AsyncGenerator[MilkProduction, None]:
        """
        Subscribe to real-time milk production data.
        For farm staff and managers.
        """
        await info.context.auth.require_farm_staff(info)
        
        async for data in info.context.event_bus.subscribe("farm:milk_production"):
            yield MilkProduction(
                id=data["id"],
                cattle_id=data["cattle_id"],
                date=data["date"],
                session=data["session"],
                volume_liters=data["volume_liters"],
                fat_percentage=data.get("fat_percentage"),
                snf_percentage=data.get("snf_percentage"),
                recorded_by=data["recorded_by"],
                created_at=data["created_at"]
            )
    
    @strawberry.subscription
    async def cattleHealthAlert(
        self,
        info,
        cattleId: Optional[strawberry.ID] = None
    ) -> AsyncGenerator["HealthAlert", None]:
        """
        Subscribe to cattle health alerts.
        """
        await info.context.auth.require_farm_staff(info)
        
        channel = f"farm:cattle:{cattleId}:health" if cattleId else "farm:cattle:health"
        
        async for alert in info.context.event_bus.subscribe(channel):
            yield HealthAlert(
                cattle_id=alert["cattle_id"],
                tag_number=alert["tag_number"],
                alert_type=alert["alert_type"],
                severity=alert["severity"],
                message=alert["message"],
                recorded_at=alert["recorded_at"]
            )
    
    @strawberry.subscription
    async def environmentalSensorData(
        self,
        info,
        shedId: Optional[strawberry.ID] = None
    ) -> AsyncGenerator["SensorData", None]:
        """
        Subscribe to real-time environmental sensor data.
        """
        await info.context.auth.require_farm_staff(info)
        
        channel = f"farm:shed:{shedId}:sensors" if shedId else "farm:sensors"
        
        async for data in info.context.event_bus.subscribe(channel):
            yield SensorData(
                sensor_id=data["sensor_id"],
                shed_id=data["shed_id"],
                sensor_type=data["sensor_type"],
                value=data["value"],
                unit=data["unit"],
                timestamp=data["timestamp"]
            )
    
    # ============================================
    # System Subscriptions
    # ============================================
    
    @strawberry.subscription
    async def lowStockAlert(
        self,
        info
    ) -> AsyncGenerator["StockAlert", None]:
        """
        Subscribe to low stock alerts (admin only).
        """
        await info.context.auth.require_admin(info)
        
        async for alert in info.context.event_bus.subscribe("inventory:low_stock"):
            yield StockAlert(
                product_id=alert["product_id"],
                product_name=alert["product_name"],
                variant_id=alert.get("variant_id"),
                variant_name=alert.get("variant_name"),
                current_stock=alert["current_stock"],
                threshold=alert["threshold"],
                alert_time=alert["alert_time"]
            )

# Subscription payload types
@strawberry.type
class OrderStatusUpdate:
    order_id: strawberry.ID
    status: str
    previous_status: Optional[str] = None
    message: Optional[str] = None
    timestamp: datetime

@strawberry.type
class OrderUpdate:
    order_id: strawberry.ID
    event_type: str  # created, updated, cancelled, delivered
    order: Optional[Order] = None
    timestamp: datetime

@strawberry.type
class Notification:
    id: strawberry.ID
    type: str  # order, promotion, system
    title: str
    message: str
    data: Optional[strawberry.scalars.JSON] = None
    is_read: bool
    created_at: datetime

@strawberry.type
class HealthAlert:
    cattle_id: strawberry.ID
    tag_number: str
    alert_type: str
    severity: str  # low, medium, high, critical
    message: str
    recorded_at: datetime

@strawberry.type
class SensorData:
    sensor_id: str
    shed_id: Optional[strawberry.ID] = None
    sensor_type: str  # temperature, humidity, air_quality
    value: float
    unit: str
    timestamp: datetime

@strawberry.type
class StockAlert:
    product_id: strawberry.ID
    product_name: str
    variant_id: Optional[strawberry.ID] = None
    variant_name: Optional[str] = None
    current_stock: int
    threshold: int
    alert_time: datetime
```

### 6.2 Subscription Examples

#### 6.2.1 Order Status Updates

```graphql
subscription OrderStatusUpdates($orderId: ID!) {
  orderStatusChanged(orderId: $orderId) {
    orderId
    status
    previousStatus
    message
    timestamp
  }
}

# Variables
{
  "orderId": "order_456"
}
```

#### 6.2.2 User Notifications

```graphql
subscription Notifications {
  notifications {
    id
    type
    title
    message
    data
    isRead
    createdAt
  }
}
```

#### 6.2.3 Milk Production (Farm Portal)

```graphql
subscription MilkProductionStream {
  milkProductionRecorded {
    id
    cattleId
    cattle {
      tagNumber
      name
    }
    date
    session
    volumeLiters
    fatPercentage
    snfPercentage
    recordedBy
    createdAt
  }
}
```

#### 6.2.4 Environmental Sensors

```graphql
subscription EnvironmentalData($shedId: ID) {
  environmentalSensorData(shedId: $shedId) {
    sensorId
    shedId
    sensorType
    value
    unit
    timestamp
  }
}

# Variables
{
  "shedId": "shed_001"
}
```

### 6.3 WebSocket Configuration

```python
# api/websocket.py
from fastapi import WebSocket, WebSocketDisconnect
from strawberry.subscriptions import GRAPHQL_TRANSPORT_WS_PROTOCOL
import json

class GraphQLWebSocketHandler:
    def __init__(self, schema):
        self.schema = schema
        self.active_connections = {}
    
    async def handle(self, websocket: WebSocket):
        await websocket.accept(subprotocol=GRAPHQL_TRANSPORT_WS_PROTOCOL)
        
        connection_id = id(websocket)
        self.active_connections[connection_id] = websocket
        
        try:
            while True:
                message = await websocket.receive_json()
                await self.process_message(websocket, message)
        except WebSocketDisconnect:
            del self.active_connections[connection_id]
    
    async def process_message(self, websocket: WebSocket, message: dict):
        message_type = message.get("type")
        
        if message_type == "connection_init":
            # Handle authentication in connection params
            payload = message.get("payload", {})
            auth_token = payload.get("Authorization")
            # Validate token and set user context
            await websocket.send_json({"type": "connection_ack"})
        
        elif message_type == "subscribe":
            # Handle subscription start
            subscription_id = message["id"]
            query = message["payload"]["query"]
            variables = message["payload"].get("variables", {})
            
            # Execute subscription
            async for result in self.schema.subscribe(
                query,
                variable_values=variables,
                context_value={"websocket": websocket}
            ):
                await websocket.send_json({
                    "type": "next",
                    "id": subscription_id,
                    "payload": {"data": result.data}
                })
        
        elif message_type == "complete":
            # Handle subscription stop
            pass
```

---

## 7. Pagination

### 7.1 Cursor-Based Pagination

Smart Dairy GraphQL API uses cursor-based pagination following the Relay specification for all list queries.

### 7.2 Connection Pattern

```graphql
# Example Connection structure
type ProductConnection {
  edges: [ProductEdge!]!
  pageInfo: PageInfo!
  totalCount: Int!
}

type ProductEdge {
  node: Product!
  cursor: String!
}

type PageInfo {
  hasNextPage: Boolean!
  hasPreviousPage: Boolean!
  startCursor: String
  endCursor: String
}
```

### 7.3 Pagination Implementation

```python
# services/pagination.py
from typing import TypeVar, Generic, List, Optional
from dataclasses import dataclass
import base64
import json

T = TypeVar("T")

@dataclass
class Cursor:
    """Pagination cursor containing offset information."""
    offset: int
    
    def encode(self) -> str:
        """Encode cursor to base64 string."""
        data = json.dumps({"o": self.offset})
        return base64.b64encode(data.encode()).decode()
    
    @classmethod
    def decode(cls, cursor: str) -> "Cursor":
        """Decode base64 cursor string."""
        data = base64.b64decode(cursor.encode()).decode()
        parsed = json.loads(data)
        return cls(offset=parsed["o"])

@dataclass
class PaginatedResult(Generic[T]):
    """Result of a paginated query."""
    items: List[T]
    total_count: int
    has_next_page: bool
    has_previous_page: bool
    start_cursor: Optional[str] = None
    end_cursor: Optional[str] = None

class CursorPaginator(Generic[T]):
    """Generic cursor-based paginator."""
    
    def __init__(self, query, cursor_field: str = "id"):
        self.query = query
        self.cursor_field = cursor_field
    
    async def paginate(
        self,
        first: Optional[int] = None,
        after: Optional[str] = None,
        last: Optional[int] = None,
        before: Optional[str] = None
    ) -> PaginatedResult[T]:
        """
        Execute paginated query.
        
        Args:
            first: Number of items to fetch forward from 'after' cursor
            after: Cursor to start fetching from
            last: Number of items to fetch backward from 'before' cursor
            before: Cursor to end fetching at
        """
        # Get total count
        total_count = await self.query.count()
        
        # Calculate offset
        offset = 0
        if after:
            cursor = Cursor.decode(after)
            offset = cursor.offset
        
        # Apply limit
        limit = first or 20
        if limit > 100:
            limit = 100  # Max page size
        
        # Fetch items
        items = await self.query.offset(offset).limit(limit + 1).all()
        
        # Check if there's a next page
        has_next_page = len(items) > limit
        if has_next_page:
            items = items[:-1]  # Remove the extra item
        
        # Calculate cursors
        start_cursor = None
        end_cursor = None
        
        if items:
            start_cursor = Cursor(offset=offset).encode()
            end_cursor = Cursor(offset=offset + len(items)).encode()
        
        has_previous_page = offset > 0
        
        return PaginatedResult(
            items=items,
            total_count=total_count,
            has_next_page=has_next_page,
            has_previous_page=has_previous_page,
            start_cursor=start_cursor,
            end_cursor=end_cursor
        )
```

### 7.4 Pagination Query Examples

#### 7.4.1 Forward Pagination

```graphql
query GetProductsForward($first: Int!, $after: String) {
  products(
    pagination: { first: $first, after: $after }
    sort: { field: CREATED_AT, direction: DESC }
  ) {
    edges {
      node {
        id
        name
        basePrice
      }
      cursor
    }
    pageInfo {
      hasNextPage
      endCursor
      totalCount
    }
  }
}

# First page
{
  "first": 10
}

# Next page
{
  "first": 10,
  "after": "eyJvIjoxMH0="
}
```

#### 7.4.2 Backward Pagination

```graphql
query GetProductsBackward($last: Int!, $before: String) {
  products(
    pagination: { last: $last, before: $before }
    sort: { field: CREATED_AT, direction: DESC }
  ) {
    edges {
      node {
        id
        name
      }
      cursor
    }
    pageInfo {
      hasPreviousPage
      startCursor
      totalCount
    }
  }
}
```

#### 7.4.3 Bidirectional Navigation

```graphql
query GetOrdersPaginated(
  $first: Int
  $after: String
  $last: Int
  $before: String
) {
  myOrders(
    pagination: { 
      first: $first, 
      after: $after,
      last: $last,
      before: $before
    }
    sort: { field: CREATED_AT, direction: DESC }
  ) {
    edges {
      node {
        id
        orderNumber
        status
        total
        createdAt
      }
      cursor
    }
    pageInfo {
      hasNextPage
      hasPreviousPage
      startCursor
      endCursor
      totalCount
    }
  }
}
```

---

## 8. Filtering & Sorting

### 8.1 Filter Input Patterns

```python
# services/filters.py
from typing import Optional, List, Any
from sqlalchemy import and_, or_, desc, asc
import operator

class FilterOperator:
    """Filter operators for building complex queries."""
    
    EQ = "eq"           # Equal
    NE = "ne"           # Not equal
    GT = "gt"           # Greater than
    GTE = "gte"         # Greater than or equal
    LT = "lt"           # Less than
    LTE = "lte"         # Less than or equal
    IN = "in"           # In list
    NIN = "nin"         # Not in list
    CONTAINS = "contains"   # Contains substring
    STARTS_WITH = "startsWith"
    ENDS_WITH = "endsWith"

class FilterBuilder:
    """Build SQLAlchemy filters from GraphQL inputs."""
    
    OPERATORS = {
        FilterOperator.EQ: operator.eq,
        FilterOperator.NE: operator.ne,
        FilterOperator.GT: operator.gt,
        FilterOperator.GTE: operator.ge,
        FilterOperator.LT: operator.lt,
        FilterOperator.LTE: operator.le,
    }
    
    @staticmethod
    def build(model, filter_input: dict) -> Any:
        """Build SQLAlchemy filter conditions."""
        conditions = []
        
        for field, value in filter_input.items():
            if value is None:
                continue
            
            # Handle range filters (e.g., priceRange)
            if isinstance(value, dict) and ("min" in value or "max" in value):
                if value.get("min") is not None:
                    conditions.append(getattr(model, field) >= value["min"])
                if value.get("max") is not None:
                    conditions.append(getattr(model, field) <= value["max"])
            
            # Handle list filters (e.g., status: [PENDING, CONFIRMED])
            elif isinstance(value, list):
                conditions.append(getattr(model, field).in_(value))
            
            # Handle string search
            elif isinstance(value, str) and field.endswith("Query"):
                column = field.replace("Query", "")
                conditions.append(
                    getattr(model, column).ilike(f"%{value}%")
                )
            
            # Handle simple equality
            else:
                conditions.append(getattr(model, field) == value)
        
        return and_(*conditions) if conditions else None
```

### 8.2 Advanced Filter Examples

```graphql
# Complex product filter
query SearchProducts($filter: ProductFilterInput!) {
  products(filter: $filter) {
    edges {
      node {
        id
        name
        basePrice
      }
    }
  }
}

# Variables - Multiple filter conditions
{
  "filter": {
    "categoryId": "cat_dairy",
    "status": "ACTIVE",
    "priceRange": {
      "min": "50.00",
      "max": "500.00"
    },
    "inStock": true,
    "certifications": ["Organic", "Antibiotic-Free"],
    "searchQuery": "milk"
  }
}
```

### 8.3 Sorting Implementation

```python
# services/sorting.py
from sqlalchemy import desc, asc
from typing import List, Optional

class SortBuilder:
    """Build SQLAlchemy order_by clauses."""
    
    DIRECTION_MAP = {
        "asc": asc,
        "desc": desc,
    }
    
    @staticmethod
    def build(model, sort_input: "SortInput") -> Any:
        """Build order_by clause."""
        if not sort_input:
            return desc(model.created_at)  # Default sort
        
        column = getattr(model, sort_input.field, None)
        if not column:
            raise ValueError(f"Invalid sort field: {sort_input.field}")
        
        direction_fn = SortBuilder.DIRECTION_MAP.get(
            sort_input.direction.value.lower(),
            desc
        )
        
        return direction_fn(column)
    
    @staticmethod
    def build_multiple(model, sort_inputs: List["SortInput"]) -> List[Any]:
        """Build multiple order_by clauses."""
        return [SortBuilder.build(model, s) for s in sort_inputs]
```

### 8.4 Multi-Field Sorting

```python
@strawberry.input
class MultiSortInput:
    """Support for sorting by multiple fields."""
    fields: List[SortInput]

# Usage in schema
@strawberry.field
async def products(
    self,
    info,
    sort: Optional[List[SortInput]] = None,
    # ...
) -> ProductConnection:
    # Sort by price ASC, then created_at DESC
    # sort: [{field: PRICE, direction: ASC}, {field: CREATED_AT, direction: DESC}]
```

---

## 9. Error Handling

### 9.1 GraphQL Error Format

```python
# exceptions/graphql_exceptions.py
import strawberry
from typing import List, Optional, Dict, Any
from graphql import GraphQLError

class SmartDairyError(Exception):
    """Base exception for Smart Dairy GraphQL API."""
    
    def __init__(
        self,
        message: str,
        code: str,
        extensions: Optional[Dict[str, Any]] = None
    ):
        self.message = message
        self.code = code
        self.extensions = extensions or {}
        super().__init__(message)
    
    def to_dict(self) -> Dict[str, Any]:
        return {
            "message": self.message,
            "extensions": {
                "code": self.code,
                **self.extensions
            }
        }

class AuthenticationError(SmartDairyError):
    """User authentication failed."""
    def __init__(self, message: str = "Authentication required"):
        super().__init__(message, "UNAUTHENTICATED")

class PermissionDeniedError(SmartDairyError):
    """User doesn't have permission."""
    def __init__(self, message: str = "Permission denied"):
        super().__init__(message, "FORBIDDEN")

class ValidationError(SmartDairyError):
    """Input validation failed."""
    def __init__(
        self,
        message: str,
        field_errors: Optional[Dict[str, List[str]]] = None
    ):
        super().__init__(
            message,
            "VALIDATION_ERROR",
            {"fieldErrors": field_errors or {}}
        )

class NotFoundError(SmartDairyError):
    """Requested resource not found."""
    def __init__(self, resource: str, identifier: str):
        super().__init__(
            f"{resource} with id '{identifier}' not found",
            "NOT_FOUND",
            {"resource": resource, "identifier": identifier}
        )

class ConflictError(SmartDairyError):
    """Resource conflict (e.g., duplicate)."""
    def __init__(self, message: str):
        super().__init__(message, "CONFLICT")

class RateLimitError(SmartDairyError):
    """Rate limit exceeded."""
    def __init__(self, retry_after: int):
        super().__init__(
            "Rate limit exceeded",
            "RATE_LIMITED",
            {"retryAfter": retry_after}
        )
```

### 9.2 Error Response Format

```json
{
  "data": null,
  "errors": [
    {
      "message": "Validation failed",
      "locations": [{"line": 2, "column": 3}],
      "path": ["createOrder"],
      "extensions": {
        "code": "VALIDATION_ERROR",
        "fieldErrors": {
          "addressId": ["Delivery address is required"],
          "paymentMethod": ["Invalid payment method"]
        }
      }
    }
  ]
}
```

### 9.3 Partial Success Handling

```python
# Handling partial success in mutations
@strawberry.type
class BatchOperationResult:
    """Result for batch operations with partial success."""
    success: bool
    completed_count: int
    failed_count: int
    errors: List["BatchOperationError"]
    results: List[strawberry.scalars.JSON]

@strawberry.type
class BatchOperationError:
    index: int
    identifier: str
    message: str
    code: str

# Example: Batch order cancellation
@strawberry.mutation
async def cancelOrdersBatch(
    self,
    info,
    order_ids: List[strawberry.ID],
    reason: Optional[str] = None
) -> BatchOperationResult:
    """Cancel multiple orders with partial success support."""
    user = info.context.user
    if not user:
        raise AuthenticationError()
    
    results = []
    errors = []
    
    for index, order_id in enumerate(order_ids):
        try:
            order = await info.context.services.order.cancel(
                user.id, order_id, reason
            )
            results.append({"orderId": order_id, "status": "cancelled"})
        except NotFoundError as e:
            errors.append(BatchOperationError(
                index=index,
                identifier=order_id,
                message=str(e),
                code="NOT_FOUND"
            ))
        except PermissionDeniedError as e:
            errors.append(BatchOperationError(
                index=index,
                identifier=order_id,
                message=str(e),
                code="FORBIDDEN"
            ))
        except Exception as e:
            errors.append(BatchOperationError(
                index=index,
                identifier=order_id,
                message=str(e),
                code="INTERNAL_ERROR"
            ))
    
    return BatchOperationResult(
        success=len(errors) == 0,
        completed_count=len(results),
        failed_count=len(errors),
        errors=errors,
        results=results
    )
```

### 9.4 Error Extension Middleware

```python
# middleware/error_middleware.py
from strawberry.extensions import Extension
from graphql import GraphQLError
import traceback
import logging

logger = logging.getLogger(__name__)

class ErrorHandlerExtension(Extension):
    """GraphQL extension for consistent error handling."""
    
    def on_request_end(self):
        """Process errors at the end of request."""
        result = self.execution_context.result
        
        if result.errors:
            formatted_errors = []
            
            for error in result.errors:
                # Log error
                logger.error(
                    f"GraphQL Error: {error.message}",
                    extra={
                        "path": error.path,
                        "locations": error.locations,
                        "extensions": error.extensions
                    }
                )
                
                # Format error for client
                if isinstance(error.original_error, SmartDairyError):
                    formatted_errors.append(error.original_error.to_dict())
                else:
                    # Generic error - don't expose internal details
                    formatted_errors.append({
                        "message": "An unexpected error occurred",
                        "extensions": {
                            "code": "INTERNAL_ERROR",
                            "requestId": self.execution_context.context.get("request_id")
                        }
                    })
            
            # Update result with formatted errors
            result.errors = [
                GraphQLError(
                    message=e["message"],
                    extensions=e.get("extensions", {})
                )
                for e in formatted_errors
            ]
```

---

## 10. Implementation

### 10.1 Strawberry + FastAPI Integration

```python
# main.py
from fastapi import FastAPI, Request, Depends
from fastapi.middleware.cors import CORSMiddleware
from strawberry.fastapi import GraphQLRouter
from contextlib import asynccontextmanager

from schema import schema
from services.auth import get_current_user
from dataloaders import create_dataloaders
from services import ServiceContainer
from event_bus import EventBus

@asynccontextmanager
async def lifespan(app: FastAPI):
    # Startup
    app.state.service_container = ServiceContainer()
    app.state.event_bus = EventBus()
    await app.state.event_bus.connect()
    yield
    # Shutdown
    await app.state.event_bus.disconnect()

app = FastAPI(
    title="Smart Dairy GraphQL API",
    description="GraphQL API for Smart Dairy Smart Web Portal",
    version="1.0.0",
    lifespan=lifespan
)

# CORS configuration
app.add_middleware(
    CORSMiddleware,
    allow_origins=[
        "https://smartdairybd.com",
        "https://admin.smartdairybd.com",
        "http://localhost:3000",
        "http://localhost:8080",
    ],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

async def get_context(request: Request):
    """Create GraphQL context for each request."""
    # Get authenticated user
    user = None
    auth_header = request.headers.get("Authorization")
    if auth_header and auth_header.startswith("Bearer "):
        token = auth_header.replace("Bearer ", "")
        user = await get_current_user(token)
    
    return {
        "request": request,
        "user": user,
        "services": request.app.state.service_container,
        "event_bus": request.app.state.event_bus,
        "loaders": create_dataloaders(request.app.state.service_container),
        "auth": AuthContext(user),
    }

# Create GraphQL router
graphql_router = GraphQLRouter(
    schema,
    context_getter=get_context,
    graphql_ide="apollo-sandbox",  # GraphQL Playground
)

app.include_router(graphql_router, prefix="/graphql")

# Health check endpoint
@app.get("/health")
async def health_check():
    return {"status": "healthy", "service": "graphql-api"}
```

### 10.2 Project Structure

```
smart-dairy-graphql/
├── api/
│   ├── __init__.py
│   ├── main.py              # FastAPI application entry
│   ├── websocket.py         # WebSocket handler for subscriptions
│   └── middleware/
│       ├── __init__.py
│       ├── auth.py          # Authentication middleware
│       ├── rate_limit.py    # Rate limiting
│       └── error_handler.py # Error handling
├── schema/
│   ├── __init__.py          # Schema export
│   ├── schema.py            # Strawberry schema definition
│   ├── scalars.py           # Custom scalars
│   ├── enums.py             # GraphQL enums
│   ├── inputs.py            # Input types
│   ├── interfaces.py        # Interfaces
│   ├── queries.py           # Root Query
│   ├── mutations.py         # Root Mutation
│   ├── subscriptions.py     # Root Subscription
│   └── types/
│       ├── __init__.py
│       ├── user.py
│       ├── product.py
│       ├── order.py
│       ├── farm.py
│       ├── review.py
│       └── common.py
├── services/
│   ├── __init__.py
│   ├── base.py              # Base service class
│   ├── user.py              # User service
│   ├── product.py           # Product service
│   ├── order.py             # Order service
│   ├── cart.py              # Cart service
│   ├── payment.py           # Payment service
│   ├── review.py            # Review service
│   └── farm.py              # Farm service
├── dataloaders/
│   ├── __init__.py
│   ├── user.py              # User DataLoader
│   ├── product.py           # Product DataLoader
│   ├── order.py             # Order DataLoader
│   ├── cattle.py            # Cattle DataLoader
│   └── batch.py             # Batch loading utilities
├── models/
│   ├── __init__.py
│   ├── base.py              # SQLAlchemy base
│   ├── user.py              # User models
│   ├── product.py           # Product models
│   ├── order.py             # Order models
│   └── farm.py              # Farm models
├── integrations/
│   ├── __init__.py
│   ├── odoo.py              # Odoo ERP integration
│   ├── payment/
│   │   ├── __init__.py
│   │   ├── bkash.py
│   │   ├── nagad.py
│   │   └── stripe.py
│   └── sms.py               # SMS gateway integration
├── event_bus/
│   ├── __init__.py
│   ├── base.py              # Event bus interface
│   └── redis.py             # Redis-based implementation
├── exceptions/
│   ├── __init__.py
│   └── graphql_exceptions.py
├── config/
│   ├── __init__.py
│   ├── settings.py          # App configuration
│   └── logging.py           # Logging setup
├── tests/
│   ├── __init__.py
│   ├── conftest.py
│   ├── unit/
│   └── integration/
├── requirements.txt
├── Dockerfile
└── docker-compose.yml
```

### 10.3 Requirements

```txt
# requirements.txt
# Web Framework
fastapi==0.104.1
uvicorn[standard]==0.24.0
strawberry-graphql[fastapi]==0.213.0

# Database
sqlalchemy[asyncio]==2.0.23
asyncpg==0.29.0
alembic==1.12.1
redis==5.0.1

# Authentication
pyjwt==2.8.0
passlib[bcrypt]==1.7.4
python-jose[cryptography]==3.3.0

# Data Loading
aiodataloader==0.4.0

# Event Bus (for subscriptions)
kafka-python==2.0.2
aioredis==2.0.1

# Utilities
pydantic==2.5.2
pydantic-settings==2.1.0
python-multipart==0.0.6
httpx==0.25.2
aiohttp==3.9.1

# Monitoring & Logging
structlog==23.2.0
prometheus-client==0.19.0
opentelemetry-api==1.21.0
opentelemetry-sdk==1.21.0

# Testing
pytest==7.4.3
pytest-asyncio==0.21.1
pytest-cov==4.1.0
httpx==0.25.2
```



---

## 11. Performance

### 11.1 The N+1 Query Problem

The N+1 query problem occurs when fetching a list of items and their related data results in N+1 database queries:
- 1 query to fetch the list
- N queries to fetch related data for each item

```python
# WITHOUT DataLoader - N+1 Problem
# Query: { products { name category { name } } }

# Query 1: Fetch products
products = await db.query(Product).all()  # 1 query

# Queries 2-N+1: Fetch category for each product
for product in products:
    category = await db.query(Category).get(product.category_id)  # N queries
```

### 11.2 DataLoader Pattern

DataLoader provides batching and caching to solve the N+1 problem:

```python
# dataloaders/base.py
from aiodataloader import DataLoader
from typing import List, Any, Optional
import asyncio

class BaseDataLoader(DataLoader):
    """Base DataLoader with caching support."""
    
    def __init__(self, db_session, cache_map=None):
        super().__init__(cache_map=cache_map)
        self.db = db_session
        self._cache = {}
    
    async def batch_load_fn(self, keys: List[Any]) -> List[Any]:
        """Override in subclasses to implement batch loading."""
        raise NotImplementedError
    
    def get_cache_key(self, key: Any) -> str:
        """Generate cache key for an item."""
        return str(key)

# dataloaders/user.py
from .base import BaseDataLoader
from models import User as UserModel

class UserLoader(BaseDataLoader):
    """DataLoader for User entities."""
    
    async def batch_load_fn(self, keys: List[str]) -> List[Optional[UserModel]]:
        """Batch load users by IDs."""
        # Single query to fetch all users
        users = await self.db.query(UserModel).filter(
            UserModel.id.in_(keys)
        ).all()
        
        # Create lookup map
        user_map = {user.id: user for user in users}
        
        # Return in same order as keys
        return [user_map.get(key) for key in keys]

# dataloaders/product.py
from .base import BaseDataLoader
from models import Category as CategoryModel, ProductImage as ProductImageModel

class CategoryLoader(BaseDataLoader):
    """DataLoader for Category entities."""
    
    async def batch_load_fn(self, keys: List[str]) -> List[Optional[CategoryModel]]:
        categories = await self.db.query(CategoryModel).filter(
            CategoryModel.id.in_(keys)
        ).all()
        category_map = {cat.id: cat for cat in categories}
        return [category_map.get(key) for key in keys]

class ProductImagesLoader(BaseDataLoader):
    """DataLoader for product images (one-to-many)."""
    
    async def batch_load_fn(self, keys: List[str]) -> List[List[ProductImageModel]]:
        # Fetch all images for all products
        images = await self.db.query(ProductImageModel).filter(
            ProductImageModel.product_id.in_(keys)
        ).order_by(ProductImageModel.sort_order).all()
        
        # Group by product_id
        images_by_product = {}
        for image in images:
            if image.product_id not in images_by_product:
                images_by_product[image.product_id] = []
            images_by_product[image.product_id].append(image)
        
        return [images_by_product.get(key, []) for key in keys]
```

### 11.3 DataLoader Factory

```python
# dataloaders/__init__.py
from typing import Dict
from contextlib import asynccontextmanager

from .user import UserLoader
from .product import CategoryLoader, ProductImagesLoader, ProductVariantLoader
from .order import OrderLoader, OrderItemsLoader
from .farm import CattleLoader, BreedLoader

class DataLoaderRegistry:
    """Registry for all DataLoaders."""
    
    def __init__(self, db_session):
        self.db = db_session
        self._loaders: Dict[str, DataLoader] = {}
    
    def get_loader(self, name: str, loader_class) -> DataLoader:
        """Get or create a DataLoader instance."""
        if name not in self._loaders:
            self._loaders[name] = loader_class(self.db)
        return self._loaders[name]
    
    @property
    def user(self) -> UserLoader:
        return self.get_loader("user", UserLoader)
    
    @property
    def category(self) -> CategoryLoader:
        return self.get_loader("category", CategoryLoader)
    
    @property
    def product_images(self) -> ProductImagesLoader:
        return self.get_loader("product_images", ProductImagesLoader)
    
    @property
    def product_variants(self) -> ProductVariantLoader:
        return self.get_loader("product_variants", ProductVariantLoader)
    
    @property
    def order(self) -> OrderLoader:
        return self.get_loader("order", OrderLoader)
    
    @property
    def order_items(self) -> OrderItemsLoader:
        return self.get_loader("order_items", OrderItemsLoader)
    
    @property
    def cattle(self) -> CattleLoader:
        return self.get_loader("cattle", CattleLoader)
    
    @property
    def breed(self) -> BreedLoader:
        return self.get_loader("breed", BreedLoader)

def create_dataloaders(db_session) -> DataLoaderRegistry:
    """Create DataLoader registry for a request."""
    return DataLoaderRegistry(db_session)
```

### 11.4 Using DataLoaders in Resolvers

```python
# schema/types/product.py
import strawberry
from typing import List

@strawberry.type
class Product:
    id: strawberry.ID
    name: str
    # ... other fields
    
    @strawberry.field
    async def category(self, info) -> Optional["Category"]:
        """Resolve category using DataLoader."""
        if not self.category_id:
            return None
        return await info.context.loaders.category.load(self.category_id)
    
    @strawberry.field
    async def images(self, info) -> List["ProductImage"]:
        """Resolve images using DataLoader."""
        return await info.context.loaders.product_images.load(self.id)
    
    @strawberry.field
    async def variants(self, info) -> List["ProductVariant"]:
        """Resolve variants using DataLoader."""
        return await info.context.loaders.product_variants.load(self.id)
    
    @strawberry.field
    async def primary_image(self, info) -> Optional["ProductImage"]:
        """Get primary image from images."""
        images = await self.images(info)
        for image in images:
            if image.is_primary:
                return image
        return images[0] if images else None
```

### 11.5 Performance Monitoring

```python
# middleware/performance.py
import time
import logging
from strawberry.extensions import Extension
from prometheus_client import Counter, Histogram, Gauge

# Metrics
GRAPHQL_REQUESTS = Counter(
    'graphql_requests_total',
    'Total GraphQL requests',
    ['operation_type', 'operation_name']
)

GRAPHQL_DURATION = Histogram(
    'graphql_request_duration_seconds',
    'GraphQL request duration',
    ['operation_type', 'operation_name'],
    buckets=[0.01, 0.025, 0.05, 0.1, 0.25, 0.5, 1.0, 2.5, 5.0, 10.0]
)

GRAPHQL_ACTIVE_REQUESTS = Gauge(
    'graphql_active_requests',
    'Number of active GraphQL requests'
)

class PerformanceMonitorExtension(Extension):
    """Monitor GraphQL query performance."""
    
    def on_request_start(self):
        self.start_time = time.time()
        GRAPHQL_ACTIVE_REQUESTS.inc()
    
    def on_request_end(self):
        duration = time.time() - self.start_time
        GRAPHQL_ACTIVE_REQUESTS.dec()
        
        # Get operation info
        operation = self.execution_context.operation
        operation_type = operation.type if operation else "unknown"
        operation_name = operation.name if operation and operation.name else "anonymous"
        
        # Record metrics
        GRAPHQL_REQUESTS.labels(
            operation_type=operation_type,
            operation_name=operation_name
        ).inc()
        
        GRAPHQL_DURATION.labels(
            operation_type=operation_type,
            operation_name=operation_name
        ).observe(duration)
        
        # Log slow queries
        if duration > 1.0:  # 1 second threshold
            logging.warning(
                f"Slow GraphQL query: {operation_name} took {duration:.2f}s",
                extra={
                    "operation_type": operation_type,
                    "operation_name": operation_name,
                    "duration": duration,
                    "query": self.execution_context.query
                }
            )
    
    def on_validation_start(self):
        self.validation_start = time.time()
    
    def on_validation_end(self):
        duration = time.time() - self.validation_start
        if duration > 0.1:
            logging.warning(f"Slow validation: {duration:.3f}s")
    
    def on_parsing_start(self):
        self.parsing_start = time.time()
    
    def on_parsing_end(self):
        duration = time.time() - self.parsing_start
        if duration > 0.1:
            logging.warning(f"Slow parsing: {duration:.3f}s")
```

### 11.6 Query Complexity Analysis

```python
# middleware/complexity.py
from strawberry.extensions import Extension
from graphql import ValidationRule, ValidationContext
from typing import List, Dict, Any

class QueryComplexityCalculator:
    """Calculate query complexity score."""
    
    # Complexity scores for different field types
    FIELD_COST = 1
    CONNECTION_COST = 5
    SUBSCRIPTION_COST = 10
    
    def __init__(self, max_complexity: int = 1000):
        self.max_complexity = max_complexity
    
    def calculate(self, document: Any) -> int:
        """Calculate complexity score for a query document."""
        total_cost = 0
        
        for definition in document.definitions:
            if definition.kind == "operation_definition":
                total_cost += self._calculate_node_cost(definition)
        
        return total_cost
    
    def _calculate_node_cost(self, node: Any, depth: int = 0) -> int:
        """Recursively calculate cost of a node."""
        cost = 0
        
        # Get field name
        field_name = self._get_field_name(node)
        
        # Base cost for the field
        cost += self.FIELD_COST
        
        # Connection fields cost more
        if field_name and field_name.endswith("Connection"):
            cost += self.CONNECTION_COST
        
        # Subscriptions cost more
        if node.kind == "operation_definition" and node.operation == "subscription":
            cost += self.SUBSCRIPTION_COST
        
        # Multiplier for nested fields
        depth_multiplier = 1 + (depth * 0.5)
        cost = int(cost * depth_multiplier)
        
        # Recurse into selection sets
        if hasattr(node, "selection_set") and node.selection_set:
            for selection in node.selection_set.selections:
                cost += self._calculate_node_cost(selection, depth + 1)
        
        return cost
    
    def _get_field_name(self, node: Any) -> str:
        """Extract field name from node."""
        if hasattr(node, "name") and node.name:
            return node.name.value
        return ""

class QueryComplexityExtension(Extension):
    """Validate query complexity before execution."""
    
    def __init__(self, max_complexity: int = 1000):
        self.calculator = QueryComplexityCalculator(max_complexity)
        self.max_complexity = max_complexity
    
    def on_validation_start(self):
        document = self.execution_context.graphql_document
        if document:
            complexity = self.calculator.calculate(document)
            
            if complexity > self.max_complexity:
                from graphql import GraphQLError
                raise GraphQLError(
                    f"Query too complex: {complexity} (max: {self.max_complexity})",
                    extensions={"code": "QUERY_TOO_COMPLEX", "complexity": complexity}
                )
            
            # Store complexity for metrics
            self.execution_context.context["query_complexity"] = complexity
```

---

## 12. Security

### 12.1 Query Depth Limiting

```python
# middleware/depth_limit.py
from strawberry.extensions import QueryDepthLimiter

# In schema configuration
schema = strawberry.Schema(
    query=Query,
    mutation=Mutation,
    subscription=Subscription,
    extensions=[
        QueryDepthLimiter(max_depth=10),  # Limit query depth
    ]
)
```

### 12.2 Authentication Middleware

```python
# middleware/auth.py
from fastapi import Request, HTTPException
from fastapi.security import HTTPBearer, HTTPAuthorizationCredentials
import jwt
from datetime import datetime, timedelta

security = HTTPBearer()

class AuthContext:
    """Authentication context for GraphQL operations."""
    
    def __init__(self, user=None):
        self.user = user
    
    async def require_auth(self, info):
        """Require authenticated user."""
        if not self.user:
            raise PermissionError("Authentication required")
    
    async def require_admin(self, info):
        """Require admin role."""
        await self.require_auth(info)
        if self.user.role != "admin":
            raise PermissionError("Admin access required")
    
    async def require_staff(self, info):
        """Require staff role (admin, farm_staff, etc.)."""
        await self.require_auth(info)
        staff_roles = ["admin", "super_admin", "farm_staff", "b2b_partner"]
        if self.user.role not in staff_roles:
            raise PermissionError("Staff access required")
    
    async def require_farm_staff(self, info):
        """Require farm staff role."""
        await self.require_auth(info)
        farm_roles = ["admin", "super_admin", "farm_staff"]
        if self.user.role not in farm_roles:
            raise PermissionError("Farm staff access required")
    
    def can_view_order(self, user, order) -> bool:
        """Check if user can view an order."""
        if not user:
            return False
        if user.role in ["admin", "super_admin"]:
            return True
        return order.user_id == user.id

# JWT Token utilities
SECRET_KEY = "your-secret-key"  # Use environment variable
ALGORITHM = "HS256"
ACCESS_TOKEN_EXPIRE_MINUTES = 30
REFRESH_TOKEN_EXPIRE_DAYS = 7

def create_access_token(data: dict) -> str:
    """Create JWT access token."""
    to_encode = data.copy()
    expire = datetime.utcnow() + timedelta(minutes=ACCESS_TOKEN_EXPIRE_MINUTES)
    to_encode.update({"exp": expire, "type": "access"})
    return jwt.encode(to_encode, SECRET_KEY, algorithm=ALGORITHM)

def create_refresh_token(data: dict) -> str:
    """Create JWT refresh token."""
    to_encode = data.copy()
    expire = datetime.utcnow() + timedelta(days=REFRESH_TOKEN_EXPIRE_DAYS)
    to_encode.update({"exp": expire, "type": "refresh"})
    return jwt.encode(to_encode, SECRET_KEY, algorithm=ALGORITHM)

def decode_token(token: str) -> dict:
    """Decode and verify JWT token."""
    try:
        payload = jwt.decode(token, SECRET_KEY, algorithms=[ALGORITHM])
        return payload
    except jwt.ExpiredSignatureError:
        raise ValueError("Token has expired")
    except jwt.InvalidTokenError:
        raise ValueError("Invalid token")

async def get_current_user(token: str) -> Optional["User"]:
    """Get current user from JWT token."""
    try:
        payload = decode_token(token)
        user_id = payload.get("sub")
        if user_id:
            # Fetch user from database
            return await fetch_user_by_id(user_id)
    except ValueError:
        return None
    return None
```

### 12.3 Rate Limiting

```python
# middleware/rate_limit.py
from fastapi import Request, HTTPException
from redis.asyncio import Redis
import time
from typing import Optional

class RateLimiter:
    """Redis-based rate limiter."""
    
    def __init__(self, redis: Redis):
        self.redis = redis
    
    async def is_allowed(
        self,
        key: str,
        max_requests: int,
        window_seconds: int
    ) -> tuple[bool, dict]:
        """
        Check if request is allowed under rate limit.
        
        Returns:
            Tuple of (allowed, metadata)
        """
        current_time = time.time()
        window_start = current_time - window_seconds
        
        # Remove old entries
        await self.redis.zremrangebyscore(key, 0, window_start)
        
        # Count current requests
        current_count = await self.redis.zcard(key)
        
        if current_count >= max_requests:
            # Get retry after time
            oldest = await self.redis.zrange(key, 0, 0, withscores=True)
            retry_after = int(oldest[0][1] + window_seconds - current_time) if oldest else window_seconds
            
            return False, {
                "limit": max_requests,
                "remaining": 0,
                "reset": int(current_time + window_seconds),
                "retry_after": retry_after
            }
        
        # Add current request
        await self.redis.zadd(key, {str(current_time): current_time})
        await self.redis.expire(key, window_seconds)
        
        return True, {
            "limit": max_requests,
            "remaining": max_requests - current_count - 1,
            "reset": int(current_time + window_seconds)
        }

class GraphQLRateLimitMiddleware:
    """Rate limiting for GraphQL requests."""
    
    # Rate limits by operation type
    LIMITS = {
        "query": (100, 60),      # 100 queries per minute
        "mutation": (30, 60),    # 30 mutations per minute
        "subscription": (10, 60), # 10 subscriptions per minute
    }
    
    def __init__(self, redis: Redis):
        self.limiter = RateLimiter(redis)
    
    async def check_rate_limit(self, request: Request, operation_type: str) -> None:
        """Check rate limit for request."""
        # Get client identifier
        client_id = await self._get_client_id(request)
        key = f"ratelimit:graphql:{operation_type}:{client_id}"
        
        max_requests, window = self.LIMITS.get(operation_type, (60, 60))
        
        allowed, metadata = await self.limiter.is_allowed(
            key, max_requests, window
        )
        
        if not allowed:
            raise HTTPException(
                status_code=429,
                detail={
                    "message": "Rate limit exceeded",
                    "retry_after": metadata["retry_after"]
                },
                headers={
                    "X-RateLimit-Limit": str(metadata["limit"]),
                    "X-RateLimit-Remaining": str(metadata["remaining"]),
                    "X-RateLimit-Reset": str(metadata["reset"]),
                    "Retry-After": str(metadata["retry_after"])
                }
            )
        
        # Add rate limit headers to response
        request.state.rate_limit = metadata
    
    async def _get_client_id(self, request: Request) -> str:
        """Get unique client identifier."""
        # Use authenticated user ID if available
        auth_header = request.headers.get("Authorization")
        if auth_header and auth_header.startswith("Bearer "):
            try:
                token = auth_header.replace("Bearer ", "")
                payload = decode_token(token)
                return f"user:{payload.get('sub')}"
            except:
                pass
        
        # Fall back to IP address
        forwarded = request.headers.get("X-Forwarded-For")
        if forwarded:
            return f"ip:{forwarded.split(',')[0].strip()}"
        
        return f"ip:{request.client.host}"
```

### 12.4 Field-Level Authorization

```python
# decorators/auth.py
import functools
from typing import Callable, Any

def require_role(roles: list[str]):
    """Decorator to require specific roles for a field resolver."""
    def decorator(func: Callable) -> Callable:
        @functools.wraps(func)
        async def wrapper(self, info, **kwargs) -> Any:
            user = info.context.user
            
            if not user:
                raise PermissionError("Authentication required")
            
            if user.role not in roles:
                raise PermissionError(
                    f"Required role: {', '.join(roles)}"
                )
            
            return await func(self, info, **kwargs)
        return wrapper
    return decorator

# Usage in schema
@strawberry.type
class Query:
    
    @strawberry.field
    @require_role(["admin", "super_admin"])
    async def users(self, info) -> List[User]:
        """Admin-only: List all users."""
        return await info.context.services.user.list_all()
    
    @strawberry.field
    @require_role(["farm_staff", "admin", "super_admin"])
    async def cattle_list(self, info) -> List[Cattle]:
        """Farm staff only: List cattle."""
        return await info.context.services.farm.list_cattle()
```

### 12.5 Input Sanitization

```python
# validators/sanitizers.py
import re
from html import escape
from typing import Optional

def sanitize_string(value: Optional[str], max_length: int = 1000) -> Optional[str]:
    """Sanitize string input."""
    if not value:
        return value
    
    # Trim whitespace
    value = value.strip()
    
    # Limit length
    if len(value) > max_length:
        value = value[:max_length]
    
    # Escape HTML
    value = escape(value)
    
    return value

def sanitize_email(email: str) -> str:
    """Sanitize and normalize email."""
    email = email.strip().lower()
    
    # Basic email validation
    pattern = r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$'
    if not re.match(pattern, email):
        raise ValueError("Invalid email format")
    
    return email

def sanitize_phone(phone: str) -> str:
    """Sanitize phone number."""
    # Remove all non-digit characters except + at start
    phone = re.sub(r'[^\d+]', '', phone)
    
    # Ensure it starts with country code for Bangladesh
    if phone.startswith('0'):
        phone = '+88' + phone
    elif not phone.startswith('+'):
        phone = '+88' + phone
    
    return phone
```

### 12.6 Security Headers

```python
# middleware/security_headers.py
from fastapi import Request, Response
from starlette.middleware.base import BaseHTTPMiddleware

class SecurityHeadersMiddleware(BaseHTTPMiddleware):
    """Add security headers to all responses."""
    
    async def dispatch(self, request: Request, call_next):
        response = await call_next(request)
        
        # Prevent MIME type sniffing
        response.headers["X-Content-Type-Options"] = "nosniff"
        
        # Prevent clickjacking
        response.headers["X-Frame-Options"] = "DENY"
        
        # XSS protection
        response.headers["X-XSS-Protection"] = "1; mode=block"
        
        # Content Security Policy for GraphQL Playground
        response.headers["Content-Security-Policy"] = (
            "default-src 'self'; "
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net; "
            "style-src 'self' 'unsafe-inline' fonts.googleapis.com; "
            "font-src 'self' fonts.gstatic.com; "
            "img-src 'self' data: https:; "
            "connect-src 'self' https: wss:;"
        )
        
        # HSTS (HTTPS only)
        response.headers["Strict-Transport-Security"] = (
            "max-age=31536000; includeSubDomains; preload"
        )
        
        # Referrer policy
        response.headers["Referrer-Policy"] = "strict-origin-when-cross-origin"
        
        return response
```

---

## 13. Federation

### 13.1 Schema Federation Overview

Schema Federation allows splitting a single GraphQL schema across multiple microservices:

```
┌─────────────────────────────────────────────────────────────────────────┐
│                        FEDERATED GATEWAY                                 │
│                    (Apollo Gateway / GraphQL Mesh)                       │
└─────────────────────────┬───────────────────────────────────────────────┘
                          │
        ┌─────────────────┼─────────────────┐
        │                 │                 │
        ▼                 ▼                 ▼
┌───────────────┐ ┌───────────────┐ ┌───────────────┐
│  User Service │ │Product Service│ │  Order Service│
│  (Port 8001)  │ │  (Port 8002)  │ │  (Port 8003)  │
└───────────────┘ └───────────────┘ └───────────────┘
        │                 │                 │
        │                 │                 │
        ▼                 ▼                 ▼
┌───────────────┐ ┌───────────────┐ ┌───────────────┐
│  User DB      │ │  Product DB   │ │  Order DB     │
└───────────────┘ └───────────────┘ └───────────────┘
```

### 13.2 Federated Schema Example

```python
# user_service/schema.py
import strawberry
from strawberry.federation import Schema, key

@strawberry.federation.type(keys=["id"])
class User:
    id: strawberry.ID
    email: str
    
    @classmethod
    def resolve_reference(cls, id: strawberry.ID) -> "User":
        # Resolve user from other services
        return get_user_by_id(id)

@strawberry.type
class Query:
    @strawberry.field
    def user(self, id: strawberry.ID) -> User:
        return get_user_by_id(id)

schema = Schema(query=Query)
```

```python
# order_service/schema.py
import strawberry
from strawberry.federation import Schema, key, extend, external

@strawberry.federation.type(keys=["id"])
class Order:
    id: strawberry.ID
    user_id: strawberry.ID
    total: float
    
    @strawberry.federation.field
    async def user(self) -> "User":
        # User type extended from User Service
        pass

@extend
@strawberry.federation.type(keys=["id"])
class User:
    id: strawberry.ID = external
    
    @strawberry.field
    async def orders(self) -> List[Order]:
        return get_orders_by_user(self.id)

@strawberry.type
class Query:
    @strawberry.field
    def order(self, id: strawberry.ID) -> Order:
        return get_order_by_id(id)

schema = Schema(query=Query)
```

### 13.3 Gateway Configuration

```python
# gateway/main.py
from apollo_federation import Gateway
from fastapi import FastAPI

gateway = Gateway(
    service_list=[
        {"name": "users", "url": "http://user-service:8001/graphql"},
        {"name": "products", "url": "http://product-service:8002/graphql"},
        {"name": "orders", "url": "http://order-service:8003/graphql"},
        {"name": "farm", "url": "http://farm-service:8004/graphql"},
    ]
)

app = FastAPI()
app.mount("/graphql", gateway)
```

### 13.4 Future Considerations

| Aspect | Current | Future (Federation) |
|--------|---------|---------------------|
| **Architecture** | Monolithic | Microservices |
| **Deployment** | Single service | Multiple services |
| **Scaling** | Horizontal only | Independent service scaling |
| **Team Structure** | Single team | Domain teams |
| **Database** | Shared PostgreSQL | Service-specific databases |
| **Complexity** | Lower | Higher |
| **Performance** | Direct DB access | Inter-service calls |

---

## 14. Appendices

### Appendix A: Complete Schema Definition

```graphql
# Complete Smart Dairy GraphQL Schema

"""Custom Scalars"""
scalar DateTime
scalar Date
scalar JSON
scalar Decimal

"""Enums"""
enum UserRole {
  CUSTOMER
  B2B_PARTNER
  FARM_STAFF
  ADMIN
  SUPER_ADMIN
}

enum UserStatus {
  ACTIVE
  INACTIVE
  PENDING_VERIFICATION
  SUSPENDED
}

enum ProductStatus {
  DRAFT
  ACTIVE
  OUT_OF_STOCK
  DISCONTINUED
}

enum ProductType {
  DAIRY
  MEAT
  SUBSCRIPTION
  BUNDLE
}

enum OrderStatus {
  PENDING
  CONFIRMED
  PROCESSING
  READY_FOR_DELIVERY
  OUT_FOR_DELIVERY
  DELIVERED
  CANCELLED
  REFUNDED
}

enum PaymentStatus {
  PENDING
  AUTHORIZED
  PAID
  PARTIALLY_PAID
  REFUNDED
  FAILED
}

enum PaymentMethod {
  BKASH
  NAGAD
  ROCKET
  CARD
  CASH_ON_DELIVERY
  BANK_TRANSFER
}

enum DeliveryType {
  HOME_DELIVERY
  PICKUP
  EXPRESS
  SUBSCRIPTION
}

enum CattleStatus {
  ACTIVE
  SOLD
  DECEASED
  QUARANTINE
}

enum CattleCategory {
  LACTATING
  DRY
  HEIFER
  CALF
  BULL
  FATTENING
}

enum SortDirection {
  ASC
  DESC
}

"""Interfaces"""
interface Node {
  id: ID!
}

"""Types"""
type User implements Node {
  id: ID!
  email: String!
  role: UserRole!
  status: UserStatus!
  isVerified: Boolean!
  profile: UserProfile
  addresses: [Address!]!
  createdAt: DateTime!
  updatedAt: DateTime!
  lastLogin: DateTime
  fullName: String!
  orders(first: Int, after: String): OrderConnection!
}

type UserProfile {
  id: ID!
  userId: ID!
  firstName: String!
  lastName: String!
  phone: String
  dateOfBirth: Date
  avatarUrl: String
  preferredLanguage: String!
  notificationPreferences: JSON
  loyaltyPoints: Int!
  loyaltyTier: String!
}

type Address {
  id: ID!
  streetAddress: String!
  apartment: String
  city: String!
  state: String!
  postalCode: String!
  country: String!
  isDefault: Boolean!
  label: String
  latitude: Float
  longitude: Float
  createdAt: DateTime!
  updatedAt: DateTime!
}

type Category {
  id: ID!
  name: String!
  slug: String!
  description: String
  imageUrl: String
  parent: Category
  children: [Category!]!
  productCount: Int!
  sortOrder: Int!
  isActive: Boolean!
  metaTitle: String
  metaDescription: String
}

type Product implements Node {
  id: ID!
  name: String!
  slug: String!
  sku: String!
  description: String!
  shortDescription: String
  productType: ProductType!
  status: ProductStatus!
  basePrice: Decimal!
  compareAtPrice: Decimal
  costPrice: Decimal
  categories: [Category!]!
  tags: [String!]!
  variants: [ProductVariant!]!
  hasVariants: Boolean!
  trackInventory: Boolean!
  allowBackorders: Boolean!
  images: [ProductImage!]!
  primaryImage: ProductImage
  nutritionInfo: ProductNutrition
  ingredients: String
  storageInstructions: String
  shelfLifeDays: Int
  origin: String
  certifications: [String!]!
  metaTitle: String
  metaDescription: String
  averageRating: Float
  reviewCount: Int!
  createdAt: DateTime!
  updatedAt: DateTime!
  publishedAt: DateTime
  isOnSale: Boolean!
  discountPercentage: Float
  reviews(first: Int, after: String): ReviewConnection!
}

type ProductVariant {
  id: ID!
  sku: String!
  name: String!
  price: Decimal!
  compareAtPrice: Decimal
  stockQuantity: Int!
  weightGrams: Int
  volumeMl: Int
  attributes: JSON
  isDefault: Boolean!
  barcode: String
}

type ProductImage {
  id: ID!
  url: String!
  altText: String
  sortOrder: Int!
  isPrimary: Boolean!
}

type ProductNutrition {
  id: ID!
  servingSize: String!
  calories: Int
  proteinG: Float
  fatG: Float
  carbohydratesG: Float
  calciumMg: Float
  otherNutrients: JSON
}

type Order implements Node {
  id: ID!
  orderNumber: String!
  userId: ID!
  status: OrderStatus!
  statusHistory: [OrderStatusHistory!]!
  items: [OrderItem!]!
  itemCount: Int!
  subtotal: Decimal!
  discountTotal: Decimal!
  taxTotal: Decimal!
  deliveryFee: Decimal!
  total: Decimal!
  currency: String!
  deliveryType: DeliveryType!
  deliveryAddress: Address
  deliveryInstructions: String
  deliverySlot: DeliverySlot
  estimatedDelivery: DateTime
  actualDelivery: DateTime
  paymentStatus: PaymentStatus!
  paymentMethod: PaymentMethod!
  payments: [Payment!]!
  trackingNumber: String
  trackingUrl: String
  notes: String
  source: String!
  ipAddress: String
  userAgent: String
  purchaseOrderNumber: String
  creditDays: Int
  isSubscriptionOrder: Boolean!
  subscriptionId: ID
  createdAt: DateTime!
  updatedAt: DateTime!
  confirmedAt: DateTime
  canCancel: Boolean!
  totalItems: Int!
}

type OrderItem {
  id: ID!
  orderId: ID!
  productId: ID!
  productName: String!
  productSku: String!
  variantId: ID
  variantName: String
  quantity: Int!
  unitPrice: Decimal!
  totalPrice: Decimal!
  discountAmount: Decimal!
  taxAmount: Decimal!
  productImageUrl: String
}

type Payment {
  id: ID!
  orderId: ID!
  amount: Decimal!
  method: PaymentMethod!
  status: PaymentStatus!
  transactionId: String
  gatewayResponse: JSON
  paidAt: DateTime
  createdAt: DateTime!
}

type OrderStatusHistory {
  id: ID!
  status: OrderStatus!
  previousStatus: OrderStatus
  notes: String
  createdBy: String
  createdAt: DateTime!
}

type DeliverySlot {
  id: ID!
  date: Date!
  startTime: String!
  endTime: String!
  isAvailable: Boolean!
}

type Review {
  id: ID!
  productId: ID!
  userId: ID!
  userName: String!
  userAvatar: String
  rating: Int!
  title: String
  content: String!
  helpfulCount: Int!
  notHelpfulCount: Int!
  isApproved: Boolean!
  isVerifiedPurchase: Boolean!
  images: [String!]!
  response: String
  respondedAt: DateTime
  respondedBy: String
  createdAt: DateTime!
  updatedAt: DateTime!
}

type Cattle implements Node {
  id: ID!
  tagNumber: String!
  name: String
  breed: CattleBreed!
  gender: String!
  status: CattleStatus!
  category: CattleCategory!
  birthDate: Date
  acquisitionDate: Date!
  acquisitionType: String!
  weightKg: Float
  color: String
  markings: String
  motherId: ID
  fatherId: ID
  mother: Cattle
  father: Cattle
  currentLactationNumber: Int!
  lastCalvingDate: Date
  isPregnant: Boolean!
  expectedCalvingDate: Date
  photoUrl: String
  shedId: ID
  shedName: String
  createdAt: DateTime!
  updatedAt: DateTime!
  ageDays: Int
  ageDisplay: String
  milkProductionToday: Float
  healthRecords(first: Int, after: String): HealthRecordConnection!
}

type CattleBreed {
  id: ID!
  name: String!
  origin: String
  characteristics: String
  averageMilkYield: Float
}

type HealthRecord {
  id: ID!
  cattleId: ID!
  cattle: Cattle!
  recordType: String!
  date: Date!
  description: String!
  diagnosis: String
  treatment: String
  medication: String
  dosage: String
  veterinarianName: String
  recordedBy: String!
  followUpDate: Date
  followUpNotes: String
  cost: Decimal
  createdAt: DateTime!
}

type MilkProduction {
  id: ID!
  cattleId: ID!
  cattle: Cattle!
  date: Date!
  session: String!
  volumeLiters: Float!
  fatPercentage: Float
  snfPercentage: Float
  proteinPercentage: Float
  lactosePercentage: Float
  density: Float
  temperatureCelsius: Float
  grade: String
  recordedBy: String!
  milkingMethod: String!
  createdAt: DateTime!
}

type MilkProductionSummary {
  date: Date!
  totalVolumeLiters: Float!
  sessionCount: Int!
  cattleCount: Int!
  averageFat: Float
  averageSnf: Float
}

type FarmShed {
  id: ID!
  name: String!
  code: String!
  capacity: Int!
  currentOccupancy: Int!
  purpose: String!
  isActive: Boolean!
}

"""Connection Types"""
type PageInfo {
  hasNextPage: Boolean!
  hasPreviousPage: Boolean!
  startCursor: String
  endCursor: String
  totalCount: Int!
}

type UserConnection {
  edges: [UserEdge!]!
  pageInfo: PageInfo!
  totalCount: Int!
}

type UserEdge {
  node: User!
  cursor: String!
}

type ProductConnection {
  edges: [ProductEdge!]!
  pageInfo: PageInfo!
  totalCount: Int!
}

type ProductEdge {
  node: Product!
  cursor: String!
}

type OrderConnection {
  edges: [OrderEdge!]!
  pageInfo: PageInfo!
  totalCount: Int!
}

type OrderEdge {
  node: Order!
  cursor: String!
}

type CattleConnection {
  edges: [CattleEdge!]!
  pageInfo: PageInfo!
  totalCount: Int!
}

type CattleEdge {
  node: Cattle!
  cursor: String!
}

type ReviewConnection {
  edges: [ReviewEdge!]!
  pageInfo: PageInfo!
  totalCount: Int!
  averageRating: Float
  ratingDistribution: JSON
}

type ReviewEdge {
  node: Review!
  cursor: String!
}

type HealthRecordConnection {
  edges: [HealthRecordEdge!]!
  pageInfo: PageInfo!
  totalCount: Int!
}

type HealthRecordEdge {
  node: HealthRecord!
  cursor: String!
}

"""Input Types"""
input PaginationInput {
  first: Int
  after: String
  last: Int
  before: String
}

input SortInput {
  field: String!
  direction: SortDirection! = DESC
}

input PriceRangeInput {
  min: Decimal
  max: Decimal
}

input ProductFilterInput {
  categoryId: ID
  categorySlug: String
  status: ProductStatus
  productType: String
  priceRange: PriceRangeInput
  inStock: Boolean
  tags: [String!]
  certifications: [String!]
  searchQuery: String
}

input OrderFilterInput {
  status: [OrderStatus!]
  paymentStatus: [String!]
  paymentMethod: [PaymentMethod!]
  dateFrom: String
  dateTo: String
  minTotal: Decimal
  maxTotal: Decimal
  isSubscriptionOrder: Boolean
}

input CattleFilterInput {
  category: CattleCategory
  status: CattleStatus
  gender: String
  breedId: ID
  shedId: ID
  isPregnant: Boolean
  ageMinDays: Int
  ageMaxDays: Int
}

input CreateUserInput {
  email: String!
  password: String!
  firstName: String!
  lastName: String!
  phone: String
  preferredLanguage: String! = "en"
}

input UpdateUserInput {
  firstName: String
  lastName: String
  phone: String
  dateOfBirth: String
  preferredLanguage: String
  notificationPreferences: JSON
}

input AddressInput {
  streetAddress: String!
  apartment: String
  city: String!
  state: String!
  postalCode: String!
  country: String! = "Bangladesh"
  label: String
  latitude: Float
  longitude: Float
  isDefault: Boolean! = false
}

input AddToCartInput {
  productId: ID!
  variantId: ID
  quantity: Int!
}

input CreateOrderInput {
  addressId: ID!
  deliveryType: DeliveryType!
  deliverySlotId: ID
  deliveryInstructions: String
  paymentMethod: PaymentMethod!
  couponCode: String
  notes: String
  source: String! = "web"
  purchaseOrderNumber: String
}

input UpdateOrderStatusInput {
  status: OrderStatus!
  notes: String
  trackingNumber: String
  trackingUrl: String
}

input CreateReviewInput {
  productId: ID!
  rating: Int!
  title: String
  content: String!
  images: [String!]
}

"""Payload Types"""
type AuthPayload {
  success: Boolean!
  user: User
  accessToken: String
  refreshToken: String
  expiresIn: Int
  message: String
  errors: JSON
}

type TokenPayload {
  accessToken: String!
  refreshToken: String!
  expiresIn: Int!
}

type MutationResponse {
  success: Boolean!
  message: String!
  errors: JSON
}

type Cart {
  id: ID!
  items: [CartItem!]!
  subtotal: Decimal!
  taxTotal: Decimal!
  deliveryFee: Decimal!
  discountAmount: Decimal!
  couponCode: String
  total: Decimal!
  itemCount: Int!
}

type CartItem {
  id: ID!
  product: Product!
  variant: ProductVariant
  quantity: Int!
  unitPrice: Decimal!
  totalPrice: Decimal!
}

"""Subscription Types"""
type OrderStatusUpdate {
  orderId: ID!
  status: String!
  previousStatus: String
  message: String
  timestamp: DateTime!
}

type OrderUpdate {
  orderId: ID!
  eventType: String!
  order: Order
  timestamp: DateTime!
}

type Notification {
  id: ID!
  type: String!
  title: String!
  message: String!
  data: JSON
  isRead: Boolean!
  createdAt: DateTime!
}

type HealthAlert {
  cattleId: ID!
  tagNumber: String!
  alertType: String!
  severity: String!
  message: String!
  recordedAt: DateTime!
}

type SensorData {
  sensorId: String!
  shedId: ID
  sensorType: String!
  value: Float!
  unit: String!
  timestamp: DateTime!
}

type StockAlert {
  productId: ID!
  productName: String!
  variantId: ID
  variantName: String
  currentStock: Int!
  threshold: Int!
  alertTime: DateTime!
}

"""Root Types"""
type Query {
  # User Queries
  me: User
  user(id: ID!): User
  users(filter: UserFilterInput, sort: SortInput, pagination: PaginationInput): UserConnection!
  
  # Product Queries
  product(id: ID, slug: String): Product
  products(filter: ProductFilterInput, sort: SortInput, pagination: PaginationInput): ProductConnection!
  categories(parentId: ID, isActive: Boolean): [Category!]!
  category(id: ID!): Category
  searchProducts(query: String!, pagination: PaginationInput): ProductConnection!
  
  # Order Queries
  order(id: ID, orderNumber: String): Order
  myOrders(filter: OrderFilterInput, sort: SortInput, pagination: PaginationInput): OrderConnection!
  orders(filter: OrderFilterInput, sort: SortInput, pagination: PaginationInput): OrderConnection!
  
  # Farm Queries
  cattle(id: ID!): Cattle
  cattleList(filter: CattleFilterInput, sort: SortInput, pagination: PaginationInput): CattleConnection!
  milkProductionSummary(startDate: String!, endDate: String!): [MilkProductionSummary!]!
  sheds(isActive: Boolean): [FarmShed!]!
}

type Mutation {
  # Authentication
  register(input: CreateUserInput!): AuthPayload!
  login(email: String!, password: String!): AuthPayload!
  logout: Boolean!
  refreshToken(refreshToken: String!): TokenPayload!
  requestPasswordReset(email: String!): MutationResponse!
  resetPassword(token: String!, newPassword: String!): MutationResponse!
  verifyEmail(token: String!): MutationResponse!
  
  # User Profile
  updateProfile(input: UpdateUserInput!): User!
  addAddress(input: AddressInput!): Address!
  updateAddress(id: ID!, input: AddressInput!): Address!
  deleteAddress(id: ID!): Boolean!
  setDefaultAddress(id: ID!): Address!
  
  # Cart
  addToCart(input: AddToCartInput!): Cart!
  updateCartItem(itemId: ID!, quantity: Int!): Cart!
  removeFromCart(itemId: ID!): Cart!
  clearCart: Cart!
  applyCoupon(code: String!): Cart!
  removeCoupon: Cart!
  
  # Orders
  createOrder(input: CreateOrderInput!): Order!
  cancelOrder(orderId: ID!, reason: String): Order!
  updateOrderStatus(orderId: ID!, input: UpdateOrderStatusInput!): Order!
  processPayment(orderId: ID!, method: String!, paymentData: JSON): Payment!
  
  # Reviews
  createReview(input: CreateReviewInput!): Review!
  updateReview(reviewId: ID!, rating: Int, title: String, content: String): Review!
  deleteReview(reviewId: ID!): Boolean!
  markReviewHelpful(reviewId: ID!, isHelpful: Boolean!): Review!
}

type Subscription {
  # Order Subscriptions
  orderStatusChanged(orderId: ID!): OrderStatusUpdate!
  myOrdersUpdated: OrderUpdate!
  
  # Notifications
  notifications: Notification!
  
  # Farm Subscriptions
  milkProductionRecorded: MilkProduction!
  cattleHealthAlert(cattleId: ID): HealthAlert!
  environmentalSensorData(shedId: ID): SensorData!
  
  # System Subscriptions
  lowStockAlert: StockAlert!
}

schema {
  query: Query
  mutation: Mutation
  subscription: Subscription
}
```

### Appendix B: Python Resolver Examples

```python
# resolvers/product_resolver.py
from typing import List, Optional
import strawberry
from sqlalchemy import select, and_, or_
from sqlalchemy.orm import selectinload

from models import Product as ProductModel, Category as CategoryModel
from schema.types import Product, ProductConnection, ProductEdge, PageInfo
from schema.inputs import ProductFilterInput, SortInput, PaginationInput

class ProductResolver:
    """Resolver for Product-related operations."""
    
    async def get_product(
        self,
        db_session,
        id: Optional[str] = None,
        slug: Optional[str] = None
    ) -> Optional[Product]:
        """Get a single product by ID or slug."""
        query = select(ProductModel).options(
            selectinload(ProductModel.category),
            selectinload(ProductModel.images),
            selectinload(ProductModel.variants),
            selectinload(ProductModel.nutrition_info)
        )
        
        if id:
            query = query.where(ProductModel.id == id)
        elif slug:
            query = query.where(ProductModel.slug == slug)
        else:
            raise ValueError("Either id or slug must be provided")
        
        result = await db_session.execute(query)
        product = result.scalar_one_or_none()
        
        return self._to_graphql(product) if product else None
    
    async def list_products(
        self,
        db_session,
        filter: Optional[ProductFilterInput] = None,
        sort: Optional[SortInput] = None,
        pagination: Optional[PaginationInput] = None
    ) -> ProductConnection:
        """List products with filtering, sorting, and pagination."""
        # Build base query
        query = select(ProductModel).options(
            selectinload(ProductModel.category),
            selectinload(ProductModel.images)
        )
        
        # Apply filters
        if filter:
            conditions = []
            
            if filter.category_id:
                conditions.append(ProductModel.category_id == filter.category_id)
            
            if filter.status:
                conditions.append(ProductModel.status == filter.status.value)
            
            if filter.in_stock is not None:
                if filter.in_stock:
                    conditions.append(ProductModel.stock_quantity > 0)
                else:
                    conditions.append(ProductModel.stock_quantity == 0)
            
            if filter.price_range:
                if filter.price_range.min is not None:
                    conditions.append(ProductModel.base_price >= filter.price_range.min)
                if filter.price_range.max is not None:
                    conditions.append(ProductModel.base_price <= filter.price_range.max)
            
            if filter.search_query:
                search = f"%{filter.search_query}%"
                conditions.append(
                    or_(
                        ProductModel.name.ilike(search),
                        ProductModel.description.ilike(search)
                    )
                )
            
            if conditions:
                query = query.where(and_(*conditions))
        
        # Get total count
        count_query = select(func.count()).select_from(query.subquery())
        total_count = await db_session.scalar(count_query)
        
        # Apply sorting
        if sort:
            sort_column = getattr(ProductModel, sort.field.lower(), ProductModel.created_at)
            if sort.direction.value == "DESC":
                query = query.order_by(desc(sort_column))
            else:
                query = query.order_by(asc(sort_column))
        else:
            query = query.order_by(desc(ProductModel.created_at))
        
        # Apply pagination
        first = pagination.first if pagination else 20
        after = pagination.after if pagination else None
        
        if after:
            # Decode cursor and apply offset
            from base64 import b64decode
            import json
            cursor_data = json.loads(b64decode(after))
            offset = cursor_data.get("o", 0)
            query = query.offset(offset)
        
        query = query.limit(first + 1)  # +1 to check for next page
        
        # Execute query
        result = await db_session.execute(query)
        products = result.scalars().all()
        
        # Check for next page
        has_next_page = len(products) > first
        if has_next_page:
            products = products[:-1]
        
        # Create edges and cursors
        edges = []
        offset = int(after.split(":")[1]) if after else 0
        
        for i, product in enumerate(products):
            cursor = b64encode(json.dumps({"o": offset + i + 1}).encode()).decode()
            edges.append(ProductEdge(
                node=self._to_graphql(product),
                cursor=cursor
            ))
        
        return ProductConnection(
            edges=edges,
            page_info=PageInfo(
                has_next_page=has_next_page,
                has_previous_page=offset > 0,
                start_cursor=edges[0].cursor if edges else None,
                end_cursor=edges[-1].cursor if edges else None,
                total_count=total_count
            )
        )
    
    def _to_graphql(self, product: ProductModel) -> Product:
        """Convert database model to GraphQL type."""
        return Product(
            id=product.id,
            name=product.name,
            slug=product.slug,
            sku=product.sku,
            description=product.description,
            short_description=product.short_description,
            base_price=product.base_price,
            compare_at_price=product.compare_at_price,
            status=product.status,
            created_at=product.created_at,
            updated_at=product.updated_at
            # ... other fields
        )
```

### Appendix C: Flutter Client Implementation

```dart
// lib/graphql/client.dart
import 'package:graphql_flutter/graphql_flutter.dart';
import 'package:flutter/material.dart';

class GraphQLConfig {
  static HttpLink httpLink = HttpLink(
    'https://api.smartdairybd.com/graphql',
  );
  
  static WebSocketLink websocketLink = WebSocketLink(
    'wss://api.smartdairybd.com/graphql',
    config: const SocketClientConfig(
      autoReconnect: true,
      inactivityTimeout: Duration(seconds: 30),
    ),
  );
  
  static AuthLink authLink(String? token) {
    return AuthLink(
      getToken: () async => token != null ? 'Bearer $token' : '',
    );
  }
  
  static Link linkChain(String? token) {
    final link = Link.split(
      (request) => request.isSubscription,
      websocketLink,
      authLink(token).concat(httpLink),
    );
    return link;
  }
  
  static ValueNotifier<GraphQLClient> client(String? token) {
    return ValueNotifier(
      GraphQLClient(
        link: linkChain(token),
        cache: GraphQLCache(store: HiveStore()),
        defaultPolicies: DefaultPolicies(
          query: Policies(
            fetch: FetchPolicy.cacheAndNetwork,
          ),
        ),
      ),
    );
  }
}

// lib/graphql/queries.dart
class GraphQLQueries {
  // Product Queries
  static const String getProduct = r'''
    query GetProduct($slug: String!) {
      product(slug: $slug) {
        id
        name
        slug
        description
        shortDescription
        basePrice
        compareAtPrice
        isOnSale
        discountPercentage
        status
        primaryImage {
          url
          altText
        }
        images {
          url
          altText
        }
        nutritionInfo {
          servingSize
          calories
          proteinG
          fatG
          carbohydratesG
          calciumMg
        }
        ingredients
        storageInstructions
        certifications
        averageRating
        reviewCount
      }
    }
  ''';
  
  static const String getProducts = r'''
    query GetProducts($filter: ProductFilterInput, $first: Int!, $after: String) {
      products(filter: $filter, pagination: { first: $first, after: $after }) {
        edges {
          node {
            id
            name
            slug
            shortDescription
            basePrice
            compareAtPrice
            isOnSale
            primaryImage {
              url
            }
            averageRating
            reviewCount
          }
          cursor
        }
        pageInfo {
          hasNextPage
          endCursor
          totalCount
        }
      }
    }
  ''';
  
  // Order Queries
  static const String getMyOrders = r'''
    query GetMyOrders($first: Int!, $after: String) {
      myOrders(
        sort: { field: CREATED_AT, direction: DESC }
        pagination: { first: $first, after: $after }
      ) {
        edges {
          node {
            id
            orderNumber
            status
            paymentStatus
            total
            itemCount
            deliveryType
            trackingNumber
            trackingUrl
            createdAt
            canCancel
            items {
              id
              productName
              quantity
              unitPrice
              totalPrice
              productImageUrl
            }
          }
          cursor
        }
        pageInfo {
          hasNextPage
          endCursor
        }
      }
    }
  ''';
  
  // User Queries
  static const String getMe = r'''
    query GetMe {
      me {
        id
        email
        role
        profile {
          firstName
          lastName
          fullName
          phone
          avatarUrl
          loyaltyPoints
          loyaltyTier
        }
        addresses {
          id
          streetAddress
          city
          postalCode
          isDefault
          label
        }
      }
    }
  ''';
}

// lib/graphql/mutations.dart
class GraphQLMutations {
  // Auth Mutations
  static const String login = r'''
    mutation Login($email: String!, $password: String!) {
      login(email: $email, password: $password) {
        success
        user {
          id
          email
          profile {
            firstName
            lastName
            fullName
          }
        }
        accessToken
        refreshToken
        expiresIn
      }
    }
  ''';
  
  static const String register = r'''
    mutation Register($input: CreateUserInput!) {
      register(input: $input) {
        success
        user {
          id
          email
        }
        accessToken
        refreshToken
        message
        errors
      }
    }
  ''';
  
  // Cart Mutations
  static const String addToCart = r'''
    mutation AddToCart($input: AddToCartInput!) {
      addToCart(input: $input) {
        id
        items {
          id
          product {
            id
            name
            primaryImage {
              url
            }
          }
          quantity
          unitPrice
          totalPrice
        }
        subtotal
        total
        itemCount
      }
    }
  ''';
  
  // Order Mutations
  static const String createOrder = r'''
    mutation CreateOrder($input: CreateOrderInput!) {
      createOrder(input: $input) {
        id
        orderNumber
        status
        total
        createdAt
      }
    }
  ''';
}

// lib/graphql/subscriptions.dart
class GraphQLSubscriptions {
  // Order Subscriptions
  static const String orderStatusChanged = r'''
    subscription OrderStatusChanged($orderId: ID!) {
      orderStatusChanged(orderId: $orderId) {
        orderId
        status
        previousStatus
        message
        timestamp
      }
    }
  ''';
  
  static const String notifications = r'''
    subscription Notifications {
      notifications {
        id
        type
        title
        message
        data
        isRead
        createdAt
      }
    }
  ''';
}

// lib/widgets/product_list.dart
import 'package:flutter/material.dart';
import 'package:graphql_flutter/graphql_flutter.dart';
import '../graphql/queries.dart';

class ProductListScreen extends StatefulWidget {
  const ProductListScreen({Key? key}) : super(key: key);
  
  @override
  State<ProductListScreen> createState() => _ProductListScreenState();
}

class _ProductListScreenState extends State<ProductListScreen> {
  String? _cursor;
  bool _hasNextPage = true;
  final List<dynamic> _products = [];
  bool _isLoading = false;
  
  @override
  Widget build(BuildContext context) {
    return Query(
      options: QueryOptions(
        document: gql(GraphQLQueries.getProducts),
        variables: const {
          'first': 20,
          'filter': {'status': 'ACTIVE', 'inStock': true},
        },
        fetchPolicy: FetchPolicy.cacheAndNetwork,
      ),
      builder: (QueryResult result, {VoidCallback? refetch, FetchMore? fetchMore}) {
        if (result.hasException) {
          return Center(
            child: Text('Error: ${result.exception.toString()}'),
          );
        }
        
        if (result.isLoading && _products.isEmpty) {
          return const Center(child: CircularProgressIndicator());
        }
        
        final products = result.data?['products']['edges'] as List? ?? [];
        final pageInfo = result.data?['products']['pageInfo'];
        
        if (products.isNotEmpty && _products.isEmpty) {
          _products.addAll(products.map((e) => e['node']));
          _hasNextPage = pageInfo['hasNextPage'];
          _cursor = pageInfo['endCursor'];
        }
        
        return ListView.builder(
          itemCount: _products.length + (_hasNextPage ? 1 : 0),
          itemBuilder: (context, index) {
            if (index == _products.length) {
              // Load more button
              return TextButton(
                onPressed: () {
                  fetchMore?.call(FetchMoreOptions(
                    document: gql(GraphQLQueries.getProducts),
                    variables: {
                      'first': 20,
                      'after': _cursor,
                      'filter': {'status': 'ACTIVE', 'inStock': true},
                    },
                    updateQuery: (previousResultData, fetchMoreResultData) {
                      final previousProducts = previousResultData?['products']['edges'] ?? [];
                      final newProducts = fetchMoreResultData?['products']['edges'] ?? [];
                      final newPageInfo = fetchMoreResultData?['products']['pageInfo'];
                      
                      _products.addAll(newProducts.map((e) => e['node']));
                      _hasNextPage = newPageInfo['hasNextPage'];
                      _cursor = newPageInfo['endCursor'];
                      
                      return {
                        'products': {
                          'edges': [...previousProducts, ...newProducts],
                          'pageInfo': newPageInfo,
                        },
                      };
                    },
                  ));
                },
                child: const Text('Load More'),
              );
            }
            
            final product = _products[index];
            return ProductCard(product: product);
          },
        );
      },
    );
  }
}

class ProductCard extends StatelessWidget {
  final dynamic product;
  
  const ProductCard({Key? key, required this.product}) : super(key: key);
  
  @override
  Widget build(BuildContext context) {
    return Card(
      child: ListTile(
        leading: Image.network(
          product['primaryImage']?['url'] ?? '',
          width: 60,
          height: 60,
          fit: BoxFit.cover,
          errorBuilder: (_, __, ___) => const Icon(Icons.image),
        ),
        title: Text(product['name']),
        subtitle: Text('৳${product['basePrice']}'),
        trailing: product['isOnSale']
            ? Chip(
                label: Text('-${product['discountPercentage']}%'),
                backgroundColor: Colors.red,
                labelStyle: const TextStyle(color: Colors.white),
              )
            : null,
        onTap: () {
          Navigator.pushNamed(
            context,
            '/product/${product['slug']}',
          );
        },
      ),
    );
  }
}

// lib/widgets/order_tracking.dart
import 'package:flutter/material.dart';
import 'package:graphql_flutter/graphql_flutter.dart';
import '../graphql/subscriptions.dart';

class OrderTrackingWidget extends StatelessWidget {
  final String orderId;
  
  const OrderTrackingWidget({Key? key, required this.orderId}) : super(key: key);
  
  @override
  Widget build(BuildContext context) {
    return Subscription(
      options: SubscriptionOptions(
        document: gql(GraphQLSubscriptions.orderStatusChanged),
        variables: {'orderId': orderId},
      ),
      builder: (result) {
        if (result.hasException) {
          return Text('Subscription error: ${result.exception}');
        }
        
        if (result.isLoading) {
          return const CircularProgressIndicator();
        }
        
        final data = result.data?['orderStatusChanged'];
        if (data == null) {
          return const Text('Waiting for updates...');
        }
        
        return Column(
          children: [
            const Icon(Icons.notifications_active, color: Colors.green),
            Text('Status: ${data['status']}'),
            if (data['message'] != null) Text(data['message']),
            Text('Updated: ${data['timestamp']}'),
          ],
        );
      },
    );
  }
}

// lib/main.dart
import 'package:flutter/material.dart';
import 'package:graphql_flutter/graphql_flutter.dart';
import 'graphql/client.dart';

void main() async {
  await initHiveForFlutter();
  
  runApp(const SmartDairyApp());
}

class SmartDairyApp extends StatelessWidget {
  const SmartDairyApp({Key? key}) : super(key: key);
  
  @override
  Widget build(BuildContext context) {
    // Get auth token from secure storage
    final token = null; // TODO: Get from secure storage
    
    return GraphQLProvider(
      client: GraphQLConfig.client(token),
      child: CacheProvider(
        child: MaterialApp(
          title: 'Smart Dairy',
          theme: ThemeData(
            primarySwatch: Colors.green,
            useMaterial3: true,
          ),
          home: const ProductListScreen(),
        ),
      ),
    );
  }
}
```

### Appendix D: Environment Configuration

```yaml
# docker-compose.yml
version: '3.8'

services:
  graphql-api:
    build: .
    ports:
      - "8000:8000"
    environment:
      - DATABASE_URL=postgresql+asyncpg://user:pass@postgres:5432/smart_dairy
      - REDIS_URL=redis://redis:6379/0
      - JWT_SECRET_KEY=${JWT_SECRET_KEY}
      - ODOO_URL=${ODOO_URL}
      - ODOO_API_KEY=${ODOO_API_KEY}
    depends_on:
      - postgres
      - redis
    volumes:
      - ./:/app
    command: uvicorn api.main:app --host 0.0.0.0 --port 8000 --reload

  postgres:
    image: postgres:15-alpine
    environment:
      - POSTGRES_USER=user
      - POSTGRES_PASSWORD=pass
      - POSTGRES_DB=smart_dairy
    volumes:
      - postgres_data:/var/lib/postgresql/data
    ports:
      - "5432:5432"

  redis:
    image: redis:7-alpine
    ports:
      - "6379:6379"
    volumes:
      - redis_data:/data

  prometheus:
    image: prom/prometheus
    ports:
      - "9090:9090"
    volumes:
      - ./prometheus.yml:/etc/prometheus/prometheus.yml

volumes:
  postgres_data:
  redis_data:
```

```dockerfile
# Dockerfile
FROM python:3.11-slim

WORKDIR /app

# Install dependencies
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy application
COPY . .

# Expose port
EXPOSE 8000

# Run application
CMD ["uvicorn", "api.main:app", "--host", "0.0.0.0", "--port", "8000"]
```

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Integration Engineer | Initial Release |

---

*This document is the property of Smart Dairy Ltd. Unauthorized distribution or reproduction is prohibited.*

**End of Document E-014: GraphQL API Specification**
