# TECHNICAL ARCHITECTURE DOCUMENT
## Smart Dairy Digital Ecosystem
### Modular Monolith Architecture for Integrated Platform

**Document Version:** 2.0
**Date:** January 31, 2026
**Supersedes:** Version 1.0 (December 2, 2025)
**Scope:** Full digital ecosystem (Website, Marketplace, Livestock, ERP, Mobile)
**Target:** Solo Developer, Linux Environment (scalable to small team)

---

## TABLE OF CONTENTS

1. [Architecture Overview](#1-architecture-overview)
2. [Technology Stack](#2-technology-stack)
3. [Modular Monolith Design](#3-modular-monolith-design)
4. [Project Structure](#4-project-structure)
5. [Module Architecture](#5-module-architecture)
6. [Database Architecture](#6-database-architecture)
7. [API Gateway Design](#7-api-gateway-design)
8. [Authentication & Authorization](#8-authentication--authorization)
9. [Real-Time & IoT Architecture](#9-real-time--iot-architecture)
10. [Mobile Application Architecture](#10-mobile-application-architecture)
11. [File Storage Strategy](#11-file-storage-strategy)
12. [Caching Strategy](#12-caching-strategy)
13. [Job Queue & Background Processing](#13-job-queue--background-processing)
14. [Search Architecture](#14-search-architecture)
15. [Notification System](#15-notification-system)
16. [Monitoring & Observability](#16-monitoring--observability)
17. [Deployment Architecture](#17-deployment-architecture)
18. [Security Architecture](#18-security-architecture)
19. [Scalability Strategy](#19-scalability-strategy)

---

## 1. ARCHITECTURE OVERVIEW

### 1.1 System Context

Smart Dairy Digital Ecosystem is a five-module integrated platform serving multiple user types across web and mobile interfaces:

```
                          EXTERNAL USERS
    ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐
    │Consumers │  │ Vendors  │  │Livestock │  │  Farm    │
    │  (B2C)   │  │(Sellers) │  │ Traders  │  │  Staff   │
    └────┬─────┘  └────┬─────┘  └────┬─────┘  └────┬─────┘
         │             │             │             │
    ┌────┴─────────────┴─────────────┴─────────────┴─────┐
    │              ACCESS CHANNELS                         │
    │  ┌──────────┐  ┌──────────┐  ┌──────────────────┐  │
    │  │  Web     │  │  Mobile  │  │  IoT Sensors     │  │
    │  │ Browser  │  │   App    │  │  (MQTT Devices)  │  │
    │  └────┬─────┘  └────┬─────┘  └────────┬─────────┘  │
    └───────┼──────────────┼─────────────────┼────────────┘
            │              │                 │
    ┌───────┴──────────────┴─────────────────┴────────────┐
    │           SMART DAIRY DIGITAL ECOSYSTEM               │
    │                                                       │
    │  ┌─────────────────────────────────────────────────┐ │
    │  │              MODULAR MONOLITH                    │ │
    │  │                                                  │ │
    │  │  Module A: Public Website & E-Commerce           │ │
    │  │  Module B: Agro Marketplace (Multi-Vendor)       │ │
    │  │  Module C: Livestock Trading Platform            │ │
    │  │  Module D: Dairy Farm Management ERP             │ │
    │  │  Common:   Auth, Payments, Notifications, Files  │ │
    │  └─────────────────────────────────────────────────┘ │
    │                                                       │
    │  ┌─────────────────────────────────────────────────┐ │
    │  │              DATA LAYER                          │ │
    │  │  PostgreSQL │ Redis │ File Storage │ MQTT Broker │ │
    │  └─────────────────────────────────────────────────┘ │
    └───────────────────────────────────────────────────────┘
            │              │                 │
    ┌───────┴──────────────┴─────────────────┴────────────┐
    │              EXTERNAL SERVICES                        │
    │  SSL Commerz │ bKash │ Nagad │ SMS │ Email │ Maps    │
    └──────────────────────────────────────────────────────┘
```

### 1.2 Architecture Decision: Modular Monolith

**Decision:** Adopt a Modular Monolith architecture over microservices or pure monolith.

**Rationale:**

| Factor | Microservices | Pure Monolith | Modular Monolith (Chosen) |
|--------|--------------|---------------|---------------------------|
| Solo developer feasibility | Very poor | Good | Excellent |
| Operational complexity | High | Low | Low |
| Data consistency | Saga patterns needed | Simple transactions | Simple transactions |
| Development speed | Slow (contracts, versioning) | Fast but messy at scale | Fast with clean boundaries |
| Debugging | Distributed tracing | Standard debugger | Standard debugger |
| Future team scaling | Already split | Painful refactoring | Clean extraction possible |
| Code organization at 105 tables | N/A (split) | Unmanageable | Schema-per-module separation |

**Key Principle:** A modular monolith gives us the organizational benefits of microservices (clear module boundaries, independent concerns) with the operational simplicity of a monolith (single deployment, single database, simple transactions).

### 1.3 Architecture Principles

1. **Module Isolation:** Each module owns its database schema and exposes typed interfaces. No direct cross-module table access.
2. **Event-Driven Internal Communication:** Modules communicate via an in-process typed event bus, not direct function imports.
3. **Shared Nothing Between Modules:** Except the `common` services layer (auth, payments, notifications, files).
4. **Offline-First for Farm Operations:** Mobile ERP features must function without connectivity.
5. **API-First Design:** All functionality exposed via RESTful APIs consumed by both web and mobile clients.
6. **Progressive Enhancement:** Start with PostgreSQL full-text search, add Meilisearch later. Start with manual data entry, add IoT sensors later.
7. **Solo Developer Optimization:** Choose proven, well-documented technologies. Automate everything repeatable. Document as you build.

---

## 2. TECHNOLOGY STACK

### 2.1 Complete Stack Summary

```
FRONTEND (Web):
  Next.js 15 (App Router) + React 18 + TypeScript 5.x
  Tailwind CSS 3.x + shadcn/ui component library
  Zustand (state management)
  React Hook Form + Zod (forms & validation)
  Recharts (dashboards & analytics)
  Socket.IO Client (real-time features)
  next-intl (i18n: English + Bengali)

BACKEND:
  Node.js 22 LTS + Express.js 4.x + TypeScript 5.x
  Prisma ORM 5.x (type-safe database access)
  BullMQ 5.x (Redis-based job queue)
  Socket.IO 4.x (real-time server)
  Passport.js + JWT (authentication)
  Multer + Sharp (file upload & image processing)
  node-cron (scheduled tasks)
  PDFKit (PDF report generation)

DATA LAYER:
  PostgreSQL 16 (primary database, schema-per-module)
  Redis 7+ (cache, sessions, job queue, pub/sub)
  Mosquitto MQTT Broker (IoT sensor ingestion)

MOBILE:
  React Native (Expo SDK 51+) + TypeScript
  Expo Camera, Location, Notifications APIs
  AsyncStorage + expo-sqlite (offline capability)
  Shared type definitions with backend

SEARCH (Phase 4+):
  Meilisearch 1.x (marketplace & livestock search)

INFRASTRUCTURE:
  Docker + docker-compose (local dev & deployment)
  Nginx 1.25+ (reverse proxy, SSL, static files)
  DigitalOcean Droplet (4 vCPU, 8GB RAM)
  DigitalOcean Spaces (S3-compatible file storage)
  Cloudflare (CDN, WAF, DDoS protection)
  GitHub Actions (CI/CD pipeline)

MONITORING:
  Prometheus + Grafana (metrics & dashboards)
  Winston (structured logging)
  Sentry (error tracking)

DEVELOPMENT:
  VS Code + ESLint + Prettier
  Jest + Supertest (testing)
  Linux (Ubuntu 22.04 LTS)
```

### 2.2 Technology Selection Rationale

**Why React Native (Expo) for Mobile:**
- Same TypeScript/React skills as web frontend
- Shared type definitions, Zod schemas, API client code
- Expo EAS Build handles iOS builds without macOS
- Camera, GPS, push notifications via Expo APIs
- expo-sqlite for offline data storage
- Over-the-air updates without app store review

**Why BullMQ over RabbitMQ/Kafka:**
- Uses existing Redis instance (no new infrastructure)
- Simple API, TypeScript-native
- Handles: email dispatch, SMS sending, payment webhooks, report generation, IoT data ingestion, vendor payout processing
- Dashboard UI available (Bull Board)

**Why Meilisearch over Elasticsearch:**
- Single binary deployment (~200MB RAM vs 2GB+ for ES)
- Built-in typo tolerance (critical for Bengali input)
- Sub-50ms search responses
- Simpler to operate for solo developer
- Deferred to Phase 4 (use PostgreSQL FTS until then)

**Why Socket.IO for Real-Time:**
- Native Express.js integration
- Auto-fallback (WebSocket -> long-polling)
- Room-based broadcasting (per-auction, per-dashboard)
- Used for: livestock auction bidding, IoT sensor dashboards, order status updates, farm alerts

**Why MQTT (Mosquitto) for IoT:**
- Industry standard for sensor telemetry
- Lightweight protocol (runs on ESP32 microcontrollers)
- QoS levels for reliable delivery
- Used for: milk flow meters, temperature sensors, bulk tank monitoring

---

## 3. MODULAR MONOLITH DESIGN

### 3.1 Module Boundary Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                     EXPRESS.JS PROCESS                           │
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │                    API GATEWAY LAYER                       │   │
│  │  Rate Limiter → CORS → Auth Middleware → Module Router    │   │
│  └──────────────────────┬───────────────────────────────────┘   │
│                          │                                       │
│  ┌───────────┬───────────┼───────────┬───────────┐              │
│  │           │           │           │           │              │
│  ▼           ▼           ▼           ▼           ▼              │
│ ┌─────┐  ┌─────┐  ┌──────┐  ┌─────┐  ┌───────┐               │
│ │ Web │  │ Mkt │  │ Lvs  │  │ ERP │  │Common │               │
│ │Mod A│  │Mod B│  │Mod C │  │Mod D│  │Service│               │
│ │     │  │     │  │      │  │     │  │       │               │
│ │Route│  │Route│  │Route │  │Route│  │ Auth  │               │
│ │Ctrl │  │Ctrl │  │Ctrl  │  │Ctrl │  │ Pay   │               │
│ │Svc  │  │Svc  │  │Svc   │  │Svc  │  │ Notif │               │
│ │     │  │     │  │      │  │     │  │ File  │               │
│ └──┬──┘  └──┬──┘  └──┬───┘  └──┬──┘  │ Search│               │
│    │        │        │         │      └───┬───┘               │
│    │        │        │         │          │                    │
│  ┌─┴────────┴────────┴─────────┴──────────┴──┐                │
│  │           INTERNAL EVENT BUS               │                │
│  │    (In-Process, Typed TypeScript Events)    │                │
│  └─────────────────────┬─────────────────────┘                │
│                         │                                       │
│  ┌──────────────────────┴──────────────────────┐               │
│  │              PRISMA ORM LAYER                │               │
│  │   Schema: common | web | mkt | lvs | erp     │               │
│  └──────────────────────┬──────────────────────┘               │
└──────────────────────────┼──────────────────────────────────────┘
                           │
              ┌────────────┼────────────┐
              ▼            ▼            ▼
         PostgreSQL     Redis      File Storage
         (5 schemas)    (cache+     (DO Spaces)
                        queue+
                        sessions)
```

### 3.2 Module Rules

**Rule 1: Schema Ownership**
Each module owns its own PostgreSQL schema. Tables are prefixed:
- `common.*` - Shared entities (users, roles, notifications, payments)
- `web.*` - Website & e-commerce (products, orders, cart, blog, B2B)
- `mkt.*` - Marketplace (vendors, listings, marketplace orders, payouts)
- `lvs.*` - Livestock (animal listings, auctions, bids, transport)
- `erp.*` - Farm ERP (animals, milk records, feed, health, finance, HR)

**Rule 2: No Cross-Schema Direct Access**
Module B cannot `SELECT * FROM erp.animals`. Instead, it calls `erpModule.getAnimalForListing(animalId)` which returns a sanitized DTO.

**Rule 3: Event Bus for Side Effects**
When a milk record is created in the ERP, it emits `erp.milk.recorded`. The website module can listen and update the live dashboard cache. No direct coupling.

**Rule 4: Shared Types**
All modules share TypeScript type definitions from a `shared/` directory. Prisma generates types per schema, and DTOs are defined in `shared/types/`.

### 3.3 Internal Event Bus

```typescript
// src/common/events/event-bus.ts
import { EventEmitter } from 'events';

type EventMap = {
  // ERP Events
  'erp.animal.created': { animalId: number; tagNumber: string; breed: string };
  'erp.animal.statusChanged': { animalId: number; oldStatus: string; newStatus: string };
  'erp.milk.recorded': { animalId: number; date: string; session: string; quantity: number };
  'erp.milk.dailySummary': { date: string; totalLiters: number; cowCount: number };
  'erp.health.alert': { animalId: number; alertType: string; severity: string };
  'erp.vaccination.due': { animalId: number; vaccine: string; dueDate: string };
  'erp.feed.lowInventory': { itemId: number; itemName: string; currentStock: number };

  // Marketplace Events
  'mkt.order.placed': { orderId: number; vendorId: number; totalAmount: number };
  'mkt.order.delivered': { orderId: number; vendorId: number };
  'mkt.payout.scheduled': { vendorId: number; amount: number; settlementDate: string };
  'mkt.vendor.approved': { vendorId: number; companyName: string };

  // Livestock Events
  'lvs.listing.created': { listingId: number; animalTag: string; price: number };
  'lvs.auction.bidPlaced': { auctionId: number; bidderId: number; amount: number };
  'lvs.auction.ended': { auctionId: number; winnerId: number; finalPrice: number };
  'lvs.listing.sold': { listingId: number; buyerId: number; price: number };

  // Website Events
  'web.order.placed': { orderId: number; customerId: number; totalAmount: number };
  'web.order.delivered': { orderId: number };

  // Common Events
  'common.payment.completed': { transactionId: number; amount: number; module: string };
  'common.payment.failed': { transactionId: number; reason: string; module: string };
  'common.user.registered': { userId: number; role: string };
};

class TypedEventBus {
  private emitter = new EventEmitter();

  emit<K extends keyof EventMap>(event: K, data: EventMap[K]): void {
    this.emitter.emit(event, data);
  }

  on<K extends keyof EventMap>(event: K, handler: (data: EventMap[K]) => void): void {
    this.emitter.on(event, handler);
  }

  off<K extends keyof EventMap>(event: K, handler: (data: EventMap[K]) => void): void {
    this.emitter.off(event, handler);
  }
}

export const eventBus = new TypedEventBus();
```

### 3.4 Cross-Module Data Flow Examples

**Example 1: Listing a farm animal for sale**
```
1. Farm manager marks animal SD-045 as "for sale" in ERP
2. ERP module updates erp.animals.status = 'for_sale'
3. ERP emits event: 'erp.animal.statusChanged' { animalId: 45, newStatus: 'for_sale' }
4. Livestock module listens, calls erpModule.getAnimalForListing(45)
5. ERP returns sanitized DTO: { tag, breed, age, healthRecords, vaccinations, genealogy, photos }
6. Livestock creates draft listing in lvs.animal_listings with source_animal_id = 45
7. Farm manager reviews and publishes the listing
```

**Example 2: Real-time milk production dashboard on website**
```
1. Farm worker records milk via mobile app -> POST /api/v1/erp/milk-records
2. ERP module saves to erp.milk_records
3. ERP emits: 'erp.milk.recorded' { animalId, date, session, quantity }
4. Common service listener updates Redis cache: 'dashboard:milk:today' (increments total)
5. Socket.IO broadcasts to connected dashboard clients: { event: 'milkUpdate', todayTotal: 892 }
6. Website dashboard updates in real-time
```

**Example 3: Multi-vendor marketplace order**
```
1. Customer adds items from Vendor A and Vendor B to cart
2. Customer checks out -> POST /api/v1/marketplace/orders
3. Marketplace module creates mkt.mkt_orders (one per vendor)
4. Common payment service processes full amount via SSL Commerz
5. On payment success: 'common.payment.completed' event fires
6. Marketplace splits: Vendor A amount = price - 10% commission, Vendor B = price - 8%
7. Creates mkt.mkt_payouts records (pending, settlement in 7 days)
8. Notification service sends SMS to each vendor: "New order received"
9. BullMQ schedules payout job for settlement date
```

---

## 4. PROJECT STRUCTURE

### 4.1 Root Directory Structure

```
smart-dairy/
├── frontend/                    # Next.js 15 Web Application
│   ├── src/
│   │   ├── app/                 # Next.js App Router
│   │   │   ├── (website)/       # Module A: Public website routes
│   │   │   ├── (marketplace)/   # Module B: Marketplace routes
│   │   │   ├── (livestock)/     # Module C: Livestock routes
│   │   │   ├── (erp)/           # Module D: ERP dashboard routes
│   │   │   ├── (auth)/          # Authentication routes
│   │   │   ├── (admin)/         # Admin panel routes
│   │   │   ├── layout.tsx       # Root layout
│   │   │   └── page.tsx         # Homepage
│   │   ├── components/
│   │   │   ├── ui/              # shadcn/ui base components
│   │   │   ├── layout/          # Header, Footer, Sidebar, Navigation
│   │   │   ├── website/         # Module A components
│   │   │   ├── marketplace/     # Module B components
│   │   │   ├── livestock/       # Module C components
│   │   │   ├── erp/             # Module D components
│   │   │   └── common/          # Shared components
│   │   ├── lib/                 # Utilities, API client, helpers
│   │   ├── stores/              # Zustand stores
│   │   ├── hooks/               # Custom React hooks
│   │   └── i18n/                # Internationalization (EN/BN)
│   ├── public/                  # Static assets
│   ├── next.config.js
│   ├── tailwind.config.ts
│   └── package.json
│
├── backend/                     # Express.js Modular Monolith
│   ├── src/
│   │   ├── server.ts            # Entry point
│   │   ├── app.ts               # Express app configuration
│   │   │
│   │   ├── modules/             # Domain modules (core architecture)
│   │   │   ├── website/         # Module A
│   │   │   │   ├── routes/
│   │   │   │   ├── controllers/
│   │   │   │   ├── services/
│   │   │   │   ├── validators/
│   │   │   │   └── index.ts     # Module registration
│   │   │   │
│   │   │   ├── marketplace/     # Module B
│   │   │   │   ├── routes/
│   │   │   │   ├── controllers/
│   │   │   │   ├── services/
│   │   │   │   ├── validators/
│   │   │   │   └── index.ts
│   │   │   │
│   │   │   ├── livestock/       # Module C
│   │   │   │   ├── routes/
│   │   │   │   ├── controllers/
│   │   │   │   ├── services/
│   │   │   │   ├── validators/
│   │   │   │   └── index.ts
│   │   │   │
│   │   │   └── erp/             # Module D
│   │   │       ├── routes/
│   │   │       ├── controllers/
│   │   │       ├── services/
│   │   │       │   ├── herd.service.ts
│   │   │       │   ├── milk.service.ts
│   │   │       │   ├── feed.service.ts
│   │   │       │   ├── health.service.ts
│   │   │       │   ├── breeding.service.ts
│   │   │       │   ├── finance.service.ts
│   │   │       │   ├── hr.service.ts
│   │   │       │   ├── inventory.service.ts
│   │   │       │   └── equipment.service.ts
│   │   │       ├── validators/
│   │   │       └── index.ts
│   │   │
│   │   ├── common/              # Shared services
│   │   │   ├── auth/            # Authentication & authorization
│   │   │   │   ├── auth.service.ts
│   │   │   │   ├── auth.middleware.ts
│   │   │   │   ├── rbac.middleware.ts
│   │   │   │   └── jwt.util.ts
│   │   │   ├── payments/        # Payment processing
│   │   │   │   ├── payment.service.ts
│   │   │   │   ├── sslcommerz.provider.ts
│   │   │   │   ├── bkash.provider.ts
│   │   │   │   └── nagad.provider.ts
│   │   │   ├── notifications/   # Multi-channel notifications
│   │   │   │   ├── notification.service.ts
│   │   │   │   ├── sms.provider.ts
│   │   │   │   ├── email.provider.ts
│   │   │   │   └── push.provider.ts
│   │   │   ├── files/           # File upload & storage
│   │   │   │   ├── file.service.ts
│   │   │   │   └── spaces.provider.ts
│   │   │   ├── search/          # Search service
│   │   │   │   └── search.service.ts
│   │   │   └── events/          # Internal event bus
│   │   │       ├── event-bus.ts
│   │   │       └── event-listeners.ts
│   │   │
│   │   ├── middleware/          # Global middleware
│   │   │   ├── error.middleware.ts
│   │   │   ├── rate-limit.middleware.ts
│   │   │   ├── logging.middleware.ts
│   │   │   └── cors.middleware.ts
│   │   │
│   │   ├── config/              # Configuration
│   │   │   ├── database.ts
│   │   │   ├── redis.ts
│   │   │   ├── mqtt.ts
│   │   │   ├── socket.ts
│   │   │   └── app.config.ts
│   │   │
│   │   ├── jobs/                # BullMQ job processors
│   │   │   ├── email.job.ts
│   │   │   ├── sms.job.ts
│   │   │   ├── payout.job.ts
│   │   │   ├── report.job.ts
│   │   │   ├── iot-ingestion.job.ts
│   │   │   └── scheduler.ts     # node-cron scheduled tasks
│   │   │
│   │   ├── iot/                 # IoT sensor ingestion
│   │   │   ├── mqtt-subscriber.ts
│   │   │   └── sensor-handlers.ts
│   │   │
│   │   └── utils/               # Shared utilities
│   │       ├── logger.ts
│   │       ├── helpers.ts
│   │       └── constants.ts
│   │
│   ├── prisma/
│   │   ├── schema.prisma        # Main Prisma schema (multi-schema)
│   │   ├── migrations/
│   │   └── seed.ts              # Database seeding
│   │
│   ├── .env
│   ├── tsconfig.json
│   └── package.json
│
├── mobile/                      # React Native (Expo) Application
│   ├── app/                     # Expo Router (file-based routing)
│   │   ├── (tabs)/              # Tab navigation
│   │   │   ├── erp/             # ERP features
│   │   │   ├── marketplace/     # Marketplace browsing
│   │   │   ├── livestock/       # Livestock browsing
│   │   │   └── profile/         # User profile
│   │   ├── (auth)/              # Auth screens
│   │   └── _layout.tsx          # Root layout
│   ├── components/              # Shared mobile components
│   ├── services/                # API client, offline sync
│   ├── stores/                  # Zustand stores
│   ├── database/                # expo-sqlite local DB
│   ├── utils/                   # Helpers
│   ├── app.json                 # Expo config
│   └── package.json
│
├── shared/                      # Shared between frontend, backend, mobile
│   ├── types/                   # TypeScript type definitions
│   │   ├── user.types.ts
│   │   ├── product.types.ts
│   │   ├── order.types.ts
│   │   ├── animal.types.ts
│   │   ├── milk.types.ts
│   │   ├── marketplace.types.ts
│   │   ├── livestock.types.ts
│   │   └── common.types.ts
│   ├── constants/               # Shared constants
│   │   ├── roles.ts
│   │   ├── permissions.ts
│   │   └── enums.ts
│   └── validators/              # Shared Zod schemas
│       ├── auth.schema.ts
│       ├── product.schema.ts
│       ├── animal.schema.ts
│       └── milk-record.schema.ts
│
├── docker/                      # Docker configurations
│   ├── Dockerfile.frontend
│   ├── Dockerfile.backend
│   ├── nginx/
│   │   └── nginx.conf
│   └── mosquitto/
│       └── mosquitto.conf
│
├── docker-compose.yml           # Development environment
├── docker-compose.prod.yml      # Production environment
├── .github/
│   └── workflows/
│       ├── ci.yml               # Test & lint on PR
│       └── deploy.yml           # Deploy on merge to main
└── README.md
```

### 4.2 Module Registration Pattern

Each module self-registers its routes and event listeners:

```typescript
// backend/src/modules/erp/index.ts
import { Router } from 'express';
import { eventBus } from '../../common/events/event-bus';
import { herdRoutes } from './routes/herd.routes';
import { milkRoutes } from './routes/milk.routes';
import { feedRoutes } from './routes/feed.routes';
import { healthRoutes } from './routes/health.routes';
import { breedingRoutes } from './routes/breeding.routes';
import { financeRoutes } from './routes/finance.routes';
import { hrRoutes } from './routes/hr.routes';
import { inventoryRoutes } from './routes/inventory.routes';
import { equipmentRoutes } from './routes/equipment.routes';
import { dashboardRoutes } from './routes/dashboard.routes';

export function registerErpModule(router: Router): void {
  // Register routes
  router.use('/herd', herdRoutes);
  router.use('/milk', milkRoutes);
  router.use('/feed', feedRoutes);
  router.use('/health', healthRoutes);
  router.use('/breeding', breedingRoutes);
  router.use('/finance', financeRoutes);
  router.use('/hr', hrRoutes);
  router.use('/inventory', inventoryRoutes);
  router.use('/equipment', equipmentRoutes);
  router.use('/dashboard', dashboardRoutes);

  // Register event listeners
  eventBus.on('common.payment.completed', async (data) => {
    if (data.module === 'erp') {
      // Update milk sale payment status
    }
  });
}

// Public API for other modules
export { getAnimalForListing } from './services/herd.service';
export { getAnimalHealthSummary } from './services/health.service';
export { getProductionSummary } from './services/milk.service';
```

```typescript
// backend/src/app.ts
import express from 'express';
import { registerWebsiteModule } from './modules/website';
import { registerMarketplaceModule } from './modules/marketplace';
import { registerLivestockModule } from './modules/livestock';
import { registerErpModule } from './modules/erp';

const app = express();

// Global middleware
app.use(/* ... cors, helmet, morgan, json parser ... */);

// Module registration
const websiteRouter = express.Router();
const marketplaceRouter = express.Router();
const livestockRouter = express.Router();
const erpRouter = express.Router();

registerWebsiteModule(websiteRouter);
registerMarketplaceModule(marketplaceRouter);
registerLivestockModule(livestockRouter);
registerErpModule(erpRouter);

// API Gateway routing
app.use('/api/v1/auth', authRoutes);
app.use('/api/v1/website', websiteRouter);
app.use('/api/v1/marketplace', marketplaceRouter);
app.use('/api/v1/livestock', livestockRouter);
app.use('/api/v1/erp', erpRouter);
app.use('/api/v1/common', commonRoutes);

// Global error handler
app.use(errorMiddleware);

export default app;
```

---

## 5. MODULE ARCHITECTURE

### 5.1 Module A: Public Website & E-Commerce

**Scope:** Smart Dairy's own product e-commerce, B2B portal, virtual farm tours, blog/content, technology showcase.

**Database Schema:** `web.*` (~25 tables)

**Key Routes:**
```
GET    /api/v1/website/products              - Product catalog
GET    /api/v1/website/products/:slug        - Product detail
POST   /api/v1/website/cart/items            - Add to cart
POST   /api/v1/website/orders                - Place order
GET    /api/v1/website/orders/:id/track      - Track order
POST   /api/v1/website/b2b/register          - B2B registration
GET    /api/v1/website/b2b/quotes            - B2B quotes
GET    /api/v1/website/blog                  - Blog posts
GET    /api/v1/website/virtual-tour          - Virtual tour data
GET    /api/v1/website/farm-dashboard        - Live farm metrics (from ERP via cache)
```

**Dependencies:** Common Auth, Common Payments, Common Notifications, ERP (read-only farm data via event bus cache)

### 5.2 Module B: Agro Marketplace

**Scope:** Multi-vendor marketplace for agro products, equipment, machinery.

**Database Schema:** `mkt.*` (~18 tables)

**Key Routes:**
```
POST   /api/v1/marketplace/vendors/register      - Vendor registration
GET    /api/v1/marketplace/products               - Marketplace product search
POST   /api/v1/marketplace/products               - Vendor: create listing
POST   /api/v1/marketplace/orders                 - Place marketplace order
GET    /api/v1/marketplace/vendors/dashboard       - Vendor dashboard
GET    /api/v1/marketplace/vendors/orders           - Vendor: view orders
PUT    /api/v1/marketplace/vendors/orders/:id/ship - Vendor: mark shipped
GET    /api/v1/marketplace/vendors/payouts          - Vendor: payout history
POST   /api/v1/marketplace/disputes                 - Customer: raise dispute
GET    /api/v1/marketplace/admin/vendors            - Admin: manage vendors
```

**Payment Flow:**
```
Customer Payment (Full Amount)
  → SSL Commerz / bKash / Nagad
  → Smart Dairy Escrow Account
  → On delivery confirmation (or 7 days):
     → Vendor Amount (price - commission%) → Vendor bank/bKash
     → Commission → Smart Dairy Revenue
  → Weekly settlement batch via BullMQ job
```

**Dependencies:** Common Auth, Common Payments (with split logic), Common Notifications, Common Files

### 5.3 Module C: Livestock Trading

**Scope:** Cattle trading platform with health records, pedigree, auctions.

**Database Schema:** `lvs.*` (~15 tables)

**Key Routes:**
```
GET    /api/v1/livestock/listings                 - Browse animals
GET    /api/v1/livestock/listings/:id             - Animal detail + health + pedigree
POST   /api/v1/livestock/listings                 - Create listing
POST   /api/v1/livestock/listings/:id/bid         - Place bid (auction)
POST   /api/v1/livestock/listings/:id/offer       - Make offer (negotiation)
GET    /api/v1/livestock/auctions/active           - Active auctions
WS     /livestock/auction/:id                      - Real-time auction (Socket.IO)
POST   /api/v1/livestock/transport/book            - Book transport
GET    /api/v1/livestock/transport/:id/track       - Track transport
POST   /api/v1/livestock/verify                    - Request SD verification
```

**Real-Time Auction Architecture:**
```
Socket.IO namespace: /livestock
  Room per auction: auction:{auctionId}

  Events:
    bid:placed    → broadcast to room → update Redis auction state
    bid:accepted  → broadcast winner → close room
    auction:ending → 60-second warning → anti-snipe extension

  State: Redis hash 'auction:{id}' with current_price, top_bidder, end_time
  Anti-snipe: If bid placed within last 2 minutes, extend by 2 minutes
```

**Dependencies:** Common Auth, Common Payments, Common Notifications, ERP Module (read-only animal data)

### 5.4 Module D: Dairy Farm Management ERP

**Scope:** Complete farm operations management for 260 cattle (scalable to 1,200).

**Database Schema:** `erp.*` (~35 tables)

**Sub-modules and Key Routes:**

```
# Herd Management
GET    /api/v1/erp/herd/animals                   - List all animals
POST   /api/v1/erp/herd/animals                   - Register animal
GET    /api/v1/erp/herd/animals/:id                - Animal detail
PUT    /api/v1/erp/herd/animals/:id                - Update animal
POST   /api/v1/erp/herd/animals/:id/move           - Move between groups
GET    /api/v1/erp/herd/groups                     - List groups with counts
GET    /api/v1/erp/herd/animals/:id/pedigree       - 3-generation pedigree

# Milk Production
POST   /api/v1/erp/milk/records                    - Record milking
GET    /api/v1/erp/milk/records?date=YYYY-MM-DD    - Daily records
GET    /api/v1/erp/milk/summary/daily               - Daily summary
GET    /api/v1/erp/milk/summary/monthly             - Monthly summary
GET    /api/v1/erp/milk/animals/:id/history         - Per-cow production history
POST   /api/v1/erp/milk/bulk-tank                   - Record bulk tank data

# Feed Management
GET    /api/v1/erp/feed/items                       - Feed library
POST   /api/v1/erp/feed/rations                     - Create ration formula
POST   /api/v1/erp/feed/consumption                 - Log daily consumption
GET    /api/v1/erp/feed/inventory                    - Current feed inventory
PUT    /api/v1/erp/feed/inventory/:id/adjust         - Stock adjustment

# Health Management
POST   /api/v1/erp/health/events                    - Log health event
GET    /api/v1/erp/health/animals/:id/history        - Animal health history
GET    /api/v1/erp/health/vaccinations/due           - Overdue vaccinations
POST   /api/v1/erp/health/vaccinations               - Record vaccination
GET    /api/v1/erp/health/disease-incidents           - Disease tracking

# Breeding Management
POST   /api/v1/erp/breeding/records                  - Log breeding event
POST   /api/v1/erp/breeding/pregnancy-checks          - Record pregnancy check
POST   /api/v1/erp/breeding/calving                   - Record calving
GET    /api/v1/erp/breeding/animals/:id/lactations     - Lactation records

# Financial Management
POST   /api/v1/erp/finance/transactions               - Record transaction
GET    /api/v1/erp/finance/summary/monthly             - Monthly P&L
GET    /api/v1/erp/finance/cost-per-liter              - Cost analysis
POST   /api/v1/erp/finance/milk-sales                  - Record milk sale

# HR Management
GET    /api/v1/erp/hr/employees                       - Employee list
POST   /api/v1/erp/hr/attendance                       - Log attendance
GET    /api/v1/erp/hr/payroll/monthly                  - Monthly payroll

# Inventory
GET    /api/v1/erp/inventory/items                     - All inventory
POST   /api/v1/erp/inventory/purchase-orders            - Create PO
GET    /api/v1/erp/inventory/low-stock                  - Low stock alerts

# Equipment
GET    /api/v1/erp/equipment                           - Equipment list
GET    /api/v1/erp/equipment/maintenance/due             - Due maintenance
POST   /api/v1/erp/equipment/maintenance/log             - Log maintenance

# Dashboard & Reports
GET    /api/v1/erp/dashboard                           - KPI dashboard data
GET    /api/v1/erp/reports/production/:period           - Production report
GET    /api/v1/erp/reports/financial/:period             - Financial report
GET    /api/v1/erp/reports/herd                          - Herd report
GET    /api/v1/erp/reports/export/:type                  - Export as PDF
```

**IoT Data Ingestion Flow:**
```
[Sensor] →MQTT→ [Mosquitto] →subscribe→ [mqtt-subscriber.ts]
  → Validate payload schema
  → Write to erp.milk_records / erp.bulk_tank_records
  → Update Redis cache (dashboard:milk:today)
  → Emit event: 'erp.milk.recorded'
  → Socket.IO broadcast to ERP dashboard subscribers
```

**Dependencies:** Common Auth, Common Notifications, MQTT Broker (IoT)

---

## 6. DATABASE ARCHITECTURE

### 6.1 Schema Organization

Single PostgreSQL 16 database with five schemas:

```sql
CREATE SCHEMA IF NOT EXISTS common;
CREATE SCHEMA IF NOT EXISTS web;
CREATE SCHEMA IF NOT EXISTS mkt;
CREATE SCHEMA IF NOT EXISTS lvs;
CREATE SCHEMA IF NOT EXISTS erp;
```

### 6.2 Table Distribution

| Schema | Table Count | Key Tables |
|--------|-------------|------------|
| `common` | ~12 | users, roles, permissions, user_roles, sessions, notifications, payment_transactions, files, audit_logs, settings, sms_logs, email_logs |
| `web` | ~25 | products, categories, orders, order_items, cart_items, reviews, b2b_partners, b2b_orders, b2b_quotes, blog_posts, pages, media, coupons, wishlists, subscriptions, contact_messages |
| `mkt` | ~18 | vendors, vendor_documents, vendor_bank_accounts, mkt_products, mkt_product_images, mkt_categories, mkt_orders, mkt_order_items, mkt_shipments, mkt_reviews, mkt_disputes, mkt_commissions, mkt_payouts, mkt_conversations |
| `lvs` | ~15 | animal_listings, listing_images, listing_bids, listing_offers, livestock_categories, transport_providers, transport_bookings, auctions, auction_bids, verifications, favorites, alerts, transactions |
| `erp` | ~35 | animals, animal_groups, animal_movements, animal_weights, milk_records, bulk_tank_records, milk_quality_tests, feed_items, feed_rations, feed_inventory, feed_consumption, health_events, vaccination_schedules, vaccination_records, disease_incidents, breeding_records, pregnancy_checks, calving_records, lactation_records, employees, attendance, payroll, financial_accounts, transactions, milk_sales, cost_analysis, inventory_items, inventory_transactions, purchase_orders, suppliers, equipment, maintenance_schedules, maintenance_logs, report_templates, settings |
| **TOTAL** | **~105** | |

### 6.3 Prisma Multi-Schema Configuration

```prisma
// prisma/schema.prisma
generator client {
  provider        = "prisma-client-js"
  previewFeatures = ["multiSchema"]
}

datasource db {
  provider = "postgresql"
  url      = env("DATABASE_URL")
  schemas  = ["common", "web", "mkt", "lvs", "erp"]
}

// ─── COMMON SCHEMA ───────────────────────────────────────

model User {
  id            Int       @id @default(autoincrement())
  email         String    @unique @db.VarChar(255)
  name          String    @db.VarChar(100)
  phone         String    @unique @db.VarChar(20)
  passwordHash  String    @map("password_hash")
  role          String    @default("customer") @db.VarChar(30)
  status        String    @default("active") @db.VarChar(20)
  language      String    @default("en") @db.VarChar(5)
  emailVerified Boolean   @default(false) @map("email_verified")
  createdAt     DateTime  @default(now()) @map("created_at")
  updatedAt     DateTime  @updatedAt @map("updated_at")
  deletedAt     DateTime? @map("deleted_at")

  // Relations across schemas
  webOrders     WebOrder[]
  mktVendor     MktVendor?
  erpEmployee   ErpEmployee?

  @@map("users")
  @@schema("common")
}

// ─── ERP SCHEMA (selected core tables) ───────────────────

model ErpAnimal {
  id               Int       @id @default(autoincrement())
  tagNumber        String    @unique @map("tag_number") @db.VarChar(20)
  name             String?   @db.VarChar(50)
  breed            String    @db.VarChar(50)
  sex              String    @db.VarChar(10)
  dateOfBirth      DateTime  @map("date_of_birth")
  acquisitionDate  DateTime  @map("acquisition_date")
  acquisitionType  String    @map("acquisition_type") @db.VarChar(20)
  status           String    @default("active") @db.VarChar(20)
  currentGroup     String    @map("current_group") @db.VarChar(30)
  currentWeight    Decimal?  @map("current_weight") @db.Decimal(6, 2)
  sireId           Int?      @map("sire_id")
  damId            Int?      @map("dam_id")
  breedComposition Json?     @map("breed_composition")
  photoUrl         String?   @map("photo_url")
  createdAt        DateTime  @default(now()) @map("created_at")
  updatedAt        DateTime  @updatedAt @map("updated_at")

  sire             ErpAnimal?  @relation("Sire", fields: [sireId], references: [id])
  dam              ErpAnimal?  @relation("Dam", fields: [damId], references: [id])
  sireOf           ErpAnimal[] @relation("Sire")
  damOf            ErpAnimal[] @relation("Dam")
  milkRecords      ErpMilkRecord[]
  healthEvents     ErpHealthEvent[]
  breedingRecords  ErpBreedingRecord[]
  vaccinations     ErpVaccinationRecord[]

  @@map("animals")
  @@schema("erp")
}

model ErpMilkRecord {
  id              BigInt    @id @default(autoincrement())
  animalId        Int       @map("animal_id")
  recordDate      DateTime  @map("record_date") @db.Date
  session         String    @db.VarChar(10)  // morning | evening
  quantityLiters  Decimal   @map("quantity_liters") @db.Decimal(6, 2)
  fatPercent      Decimal?  @map("fat_percent") @db.Decimal(4, 2)
  snfPercent      Decimal?  @map("snf_percent") @db.Decimal(4, 2)
  proteinPercent  Decimal?  @map("protein_percent") @db.Decimal(4, 2)
  scc             Int?      // somatic cell count
  recordedBy      Int       @map("recorded_by")
  method          String    @default("manual") @db.VarChar(20)
  createdAt       DateTime  @default(now()) @map("created_at")

  animal          ErpAnimal @relation(fields: [animalId], references: [id])

  @@index([animalId, recordDate])
  @@index([recordDate])
  @@map("milk_records")
  @@schema("erp")
}

// Additional models defined in full Database Design Document
```

### 6.4 Data Partitioning Strategy

For the ERP module at 1,200-cattle scale (~1.3M new rows/year):

```sql
-- Partition milk_records by year (largest table: ~730K rows/year)
-- Note: Prisma doesn't natively support partitioning, so this is
-- applied via raw SQL migration after initial table creation

CREATE TABLE erp.milk_records (
    id BIGSERIAL,
    animal_id INTEGER NOT NULL,
    record_date DATE NOT NULL,
    session VARCHAR(10) NOT NULL,
    quantity_liters DECIMAL(6,2) NOT NULL,
    fat_percent DECIMAL(4,2),
    snf_percent DECIMAL(4,2),
    protein_percent DECIMAL(4,2),
    scc INTEGER,
    recorded_by INTEGER NOT NULL,
    method VARCHAR(20) DEFAULT 'manual',
    created_at TIMESTAMPTZ DEFAULT NOW(),
    PRIMARY KEY (id, record_date)
) PARTITION BY RANGE (record_date);

CREATE TABLE erp.milk_records_2026 PARTITION OF erp.milk_records
    FOR VALUES FROM ('2026-01-01') TO ('2027-01-01');
CREATE TABLE erp.milk_records_2027 PARTITION OF erp.milk_records
    FOR VALUES FROM ('2027-01-01') TO ('2028-01-01');

-- Similarly partition feed_consumption and audit_logs
```

### 6.5 Indexing Strategy

```sql
-- ERP: Milk records (most queried)
CREATE INDEX idx_milk_records_animal_date ON erp.milk_records(animal_id, record_date);
CREATE INDEX idx_milk_records_date ON erp.milk_records(record_date);

-- ERP: Animals
CREATE INDEX idx_animals_tag ON erp.animals(tag_number);
CREATE INDEX idx_animals_group ON erp.animals(current_group) WHERE status = 'active';
CREATE INDEX idx_animals_status ON erp.animals(status);

-- ERP: Health events
CREATE INDEX idx_health_events_animal ON erp.health_events(animal_id, date DESC);
CREATE INDEX idx_vaccination_records_due ON erp.vaccination_records(next_due_date)
    WHERE next_due_date IS NOT NULL;

-- Marketplace: Product search
CREATE INDEX idx_mkt_products_search ON mkt.mkt_products
    USING GIN (to_tsvector('english', name || ' ' || COALESCE(description, '')));
CREATE INDEX idx_mkt_products_vendor ON mkt.mkt_products(vendor_id, status);
CREATE INDEX idx_mkt_products_category ON mkt.mkt_products(category_id, status);

-- Livestock: Listings
CREATE INDEX idx_lvs_listings_status ON lvs.animal_listings(status, created_at DESC);
CREATE INDEX idx_lvs_listings_breed ON lvs.animal_listings(breed, status);
CREATE INDEX idx_lvs_auction_bids ON lvs.auction_bids(auction_id, amount DESC);

-- Common: Users
CREATE INDEX idx_users_email ON common.users(email);
CREATE INDEX idx_users_role ON common.users(role);
CREATE INDEX idx_audit_logs_user ON common.audit_logs(user_id, created_at DESC);
```

### 6.6 Backup & Recovery

| Frequency | Method | Retention | Storage |
|-----------|--------|-----------|---------|
| Continuous | PostgreSQL WAL streaming | RPO <5 min | DO Spaces (separate region) |
| Daily 2:00 AM | pg_dump --format=custom | 30 days | DO Spaces |
| Weekly | Full backup + WAL archive | 12 weeks | DO Spaces |
| Monthly | Full archive | 12 months | DO Spaces |

**Recovery Targets:** RPO <5 minutes, RTO <1 hour

**Priority Order:** ERP data (irreplaceable farm records) > Financial data > User accounts > Marketplace > Content

---

## 7. API GATEWAY DESIGN

### 7.1 Route Prefix Mapping

```
Base URL (Production): https://api.smartdairy.com.bd/v1
Base URL (Development): http://localhost:5000/api/v1

/api/v1/auth/*          → Common Auth Service
/api/v1/website/*       → Module A (Website & E-Commerce)
/api/v1/marketplace/*   → Module B (Agro Marketplace)
/api/v1/livestock/*     → Module C (Livestock Trading)
/api/v1/erp/*           → Module D (Farm ERP)
/api/v1/common/*        → Common Services (files, notifications, search)
/api/v1/mobile/*        → Mobile-specific endpoints (sync, offline)
/api/health             → Health check (no auth required)
```

### 7.2 Middleware Chain

```
Request → Rate Limiter → CORS → Request Logger → JSON Parser
  → Auth Middleware (JWT verify, attach user)
  → RBAC Middleware (check permissions)
  → Module Router
  → Response
  → Error Handler (global catch)
```

### 7.3 API Response Format

```typescript
// Success response
{
  "success": true,
  "data": { /* response payload */ },
  "meta": {
    "page": 1,
    "perPage": 20,
    "total": 150,
    "totalPages": 8
  }
}

// Error response
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Invalid input data",
    "details": [
      { "field": "email", "message": "Invalid email format" }
    ]
  }
}
```

### 7.4 Rate Limiting

| Endpoint Category | Rate Limit | Window |
|-------------------|-----------|--------|
| Auth (login/register) | 10 requests | 15 minutes |
| Public read APIs | 100 requests | 1 minute |
| Authenticated write APIs | 30 requests | 1 minute |
| ERP data entry (mobile) | 200 requests | 1 minute |
| File uploads | 10 requests | 1 minute |
| Auction bids | 30 requests | 1 minute |
| Search | 60 requests | 1 minute |

---

## 8. AUTHENTICATION & AUTHORIZATION

### 8.1 JWT Token Strategy

```
Access Token:
  - Lifetime: 15 minutes
  - Storage: In-memory (JavaScript variable)
  - Contains: userId, role, permissions[]

Refresh Token:
  - Lifetime: 7 days
  - Storage: HttpOnly, Secure, SameSite=Strict cookie
  - Stored in: Redis (for revocation capability)
  - Rotation: New refresh token on each use
```

### 8.2 RBAC Permission Model

```typescript
// shared/constants/permissions.ts
export const PERMISSIONS = {
  // Website
  'web:products:read': 'Browse product catalog',
  'web:orders:create': 'Place orders',
  'web:orders:read': 'View own orders',
  'web:admin:manage': 'Admin panel access',

  // Marketplace
  'mkt:products:list': 'Create marketplace listings',
  'mkt:orders:manage': 'Manage vendor orders',
  'mkt:payouts:view': 'View payout history',
  'mkt:admin:manage': 'Marketplace admin',
  'mkt:disputes:resolve': 'Resolve disputes',

  // Livestock
  'lvs:listings:create': 'Create animal listings',
  'lvs:listings:bid': 'Place bids on auctions',
  'lvs:admin:verify': 'Verify animal listings',

  // ERP
  'erp:herd:read': 'View animal records',
  'erp:herd:write': 'Manage animal records',
  'erp:milk:read': 'View milk records',
  'erp:milk:write': 'Record milk production',
  'erp:health:read': 'View health records',
  'erp:health:write': 'Record health events',
  'erp:breeding:read': 'View breeding records',
  'erp:breeding:write': 'Record breeding events',
  'erp:feed:read': 'View feed data',
  'erp:feed:write': 'Manage feed records',
  'erp:finance:read': 'View financial data',
  'erp:finance:write': 'Record transactions',
  'erp:hr:read': 'View employee data',
  'erp:hr:write': 'Manage employees & payroll',
  'erp:inventory:read': 'View inventory',
  'erp:inventory:write': 'Manage inventory',
  'erp:equipment:read': 'View equipment',
  'erp:equipment:write': 'Manage equipment',
  'erp:reports:generate': 'Generate reports',
  'erp:settings:manage': 'ERP settings',
} as const;

// Role-to-permission mapping
export const ROLE_PERMISSIONS: Record<string, string[]> = {
  super_admin: Object.keys(PERMISSIONS), // All permissions

  farm_manager: [
    'erp:herd:read', 'erp:herd:write',
    'erp:milk:read', 'erp:milk:write',
    'erp:health:read', 'erp:health:write',
    'erp:breeding:read', 'erp:breeding:write',
    'erp:feed:read', 'erp:feed:write',
    'erp:finance:read', 'erp:finance:write',
    'erp:hr:read', 'erp:hr:write',
    'erp:inventory:read', 'erp:inventory:write',
    'erp:equipment:read', 'erp:equipment:write',
    'erp:reports:generate', 'erp:settings:manage',
    'lvs:listings:create',
  ],

  farm_worker: [
    'erp:herd:read',
    'erp:milk:read', 'erp:milk:write',
    'erp:health:read', 'erp:health:write',
    'erp:feed:read', 'erp:feed:write',
    'erp:inventory:read',
    'erp:equipment:read',
  ],

  veterinarian: [
    'erp:herd:read',
    'erp:health:read', 'erp:health:write',
    'erp:breeding:read', 'erp:breeding:write',
    'erp:milk:read',
  ],

  marketplace_vendor: [
    'mkt:products:list', 'mkt:orders:manage', 'mkt:payouts:view',
    'web:products:read',
  ],

  customer: [
    'web:products:read', 'web:orders:create', 'web:orders:read',
    'lvs:listings:bid',
  ],

  b2b_customer: [
    'web:products:read', 'web:orders:create', 'web:orders:read',
  ],
};
```

### 8.3 Authorization Middleware

```typescript
// backend/src/common/auth/rbac.middleware.ts
import { Request, Response, NextFunction } from 'express';
import { ROLE_PERMISSIONS } from '../../../shared/constants/permissions';

export function authorize(...requiredPermissions: string[]) {
  return (req: Request, res: Response, next: NextFunction) => {
    const user = req.user; // Attached by auth middleware
    if (!user) {
      return res.status(401).json({ success: false, error: { code: 'UNAUTHORIZED' } });
    }

    const userPermissions = ROLE_PERMISSIONS[user.role] || [];
    const hasPermission = requiredPermissions.every(p => userPermissions.includes(p));

    if (!hasPermission) {
      return res.status(403).json({ success: false, error: { code: 'FORBIDDEN' } });
    }

    next();
  };
}

// Usage in routes:
// router.post('/milk/records', authorize('erp:milk:write'), milkController.createRecord);
// router.get('/finance/summary', authorize('erp:finance:read'), financeController.getSummary);
```

---

## 9. REAL-TIME & IoT ARCHITECTURE

### 9.1 Socket.IO Configuration

```typescript
// backend/src/config/socket.ts
import { Server as HttpServer } from 'http';
import { Server as SocketIOServer } from 'socket.io';
import { verifyJWT } from '../common/auth/jwt.util';

export function setupSocketIO(httpServer: HttpServer): SocketIOServer {
  const io = new SocketIOServer(httpServer, {
    cors: {
      origin: process.env.FRONTEND_URL,
      credentials: true,
    },
    transports: ['websocket', 'polling'],
  });

  // Authentication middleware
  io.use(async (socket, next) => {
    const token = socket.handshake.auth.token;
    try {
      const user = await verifyJWT(token);
      socket.data.user = user;
      next();
    } catch (err) {
      next(new Error('Authentication failed'));
    }
  });

  // Namespaces
  const erpNamespace = io.of('/erp');
  const livestockNamespace = io.of('/livestock');
  const dashboardNamespace = io.of('/dashboard');

  // ERP namespace: real-time farm data
  erpNamespace.on('connection', (socket) => {
    socket.join(`farm:${socket.data.user.farmId || 'default'}`);
  });

  // Livestock namespace: auction rooms
  livestockNamespace.on('connection', (socket) => {
    socket.on('join:auction', (auctionId: number) => {
      socket.join(`auction:${auctionId}`);
    });
  });

  // Dashboard namespace: public farm metrics
  dashboardNamespace.on('connection', (socket) => {
    socket.join('public-dashboard');
  });

  return io;
}
```

### 9.2 MQTT IoT Ingestion

```typescript
// backend/src/iot/mqtt-subscriber.ts
import mqtt from 'mqtt';
import { prisma } from '../config/database';
import { eventBus } from '../common/events/event-bus';
import { redis } from '../config/redis';
import { logger } from '../utils/logger';

const MQTT_BROKER = process.env.MQTT_BROKER_URL || 'mqtt://localhost:1883';

export function startMqttSubscriber(): void {
  const client = mqtt.connect(MQTT_BROKER, {
    clientId: 'smart-dairy-backend',
    username: process.env.MQTT_USERNAME,
    password: process.env.MQTT_PASSWORD,
  });

  client.on('connect', () => {
    logger.info('Connected to MQTT broker');
    client.subscribe('farm/milk/#');
    client.subscribe('farm/environment/#');
    client.subscribe('farm/tank/#');
  });

  client.on('message', async (topic, payload) => {
    try {
      const data = JSON.parse(payload.toString());
      const topicParts = topic.split('/');

      switch (topicParts[1]) {
        case 'milk':
          await handleMilkSensorData(topicParts, data);
          break;
        case 'environment':
          await handleEnvironmentData(topicParts, data);
          break;
        case 'tank':
          await handleBulkTankData(topicParts, data);
          break;
      }
    } catch (error) {
      logger.error('MQTT message processing error', { topic, error });
    }
  });
}

async function handleMilkSensorData(
  topicParts: string[],
  data: { quantity: number; flowRate: number; timestamp: string }
): Promise<void> {
  const animalTag = topicParts[2]; // farm/milk/{animal_tag}/{session}
  const session = topicParts[3];

  // Find animal by tag
  const animal = await prisma.erpAnimal.findUnique({
    where: { tagNumber: animalTag },
  });

  if (!animal) {
    logger.warn(`Unknown animal tag from sensor: ${animalTag}`);
    return;
  }

  // Save milk record
  await prisma.erpMilkRecord.create({
    data: {
      animalId: animal.id,
      recordDate: new Date(),
      session,
      quantityLiters: data.quantity,
      method: 'automated',
      recordedBy: 0, // System
    },
  });

  // Update daily total in Redis
  const dateKey = new Date().toISOString().split('T')[0];
  await redis.incrByFloat(`dashboard:milk:${dateKey}`, data.quantity);

  // Emit event for real-time dashboard
  eventBus.emit('erp.milk.recorded', {
    animalId: animal.id,
    date: dateKey,
    session,
    quantity: data.quantity,
  });

  // Anomaly detection: check against 7-day average
  const avgResult = await prisma.erpMilkRecord.aggregate({
    _avg: { quantityLiters: true },
    where: {
      animalId: animal.id,
      session,
      recordDate: { gte: new Date(Date.now() - 7 * 24 * 60 * 60 * 1000) },
    },
  });

  const avg = Number(avgResult._avg.quantityLiters || 0);
  if (avg > 0 && data.quantity < avg * 0.8) {
    eventBus.emit('erp.health.alert', {
      animalId: animal.id,
      alertType: 'low_milk_yield',
      severity: 'warning',
    });
  }
}
```

### 9.3 MQTT Topic Structure

```
farm/milk/{animal_tag}/{session}
  Payload: { quantity: 12.5, flowRate: 2.1, timestamp: "2026-01-31T06:30:00Z" }

farm/environment/{location}/temp
  Payload: { temperature: 28.5, humidity: 72, timestamp: "..." }

farm/environment/{location}/humidity
  Payload: { humidity: 72, timestamp: "..." }

farm/tank/{tank_id}/level
  Payload: { levelLiters: 450, capacity: 1000, timestamp: "..." }

farm/tank/{tank_id}/temp
  Payload: { temperature: 4.2, timestamp: "..." }
  Alert if temperature > 8°C (cold chain breach)

farm/animal/{tag}/activity
  Payload: { steps: 1200, restingMinutes: 45, timestamp: "..." }
  (Future: pedometer/activity monitor data)
```

**Sensor Hardware (Bangladesh-available):**
- Milk flow meters: Inline flow meters ($50-100 each, Alibaba)
- Temperature: DS18B20 waterproof sensors + ESP32 microcontroller ($5-10/node)
- Bulk tank: Industrial temperature probe + level sensor + ESP32 gateway
- Farm gateway: Raspberry Pi 4 running Mosquitto as local MQTT broker

---

## 10. MOBILE APPLICATION ARCHITECTURE

### 10.1 React Native (Expo) Structure

```
mobile/
├── app/                         # Expo Router (file-based)
│   ├── _layout.tsx              # Root layout with navigation
│   ├── (auth)/
│   │   ├── login.tsx
│   │   └── register.tsx
│   ├── (tabs)/
│   │   ├── _layout.tsx          # Tab navigator
│   │   ├── erp/
│   │   │   ├── index.tsx        # ERP dashboard
│   │   │   ├── milk-record.tsx  # Quick milk recording
│   │   │   ├── health-event.tsx # Log health event
│   │   │   ├── animals/
│   │   │   │   ├── index.tsx    # Animal list
│   │   │   │   └── [id].tsx     # Animal detail
│   │   │   └── reports.tsx      # View reports
│   │   ├── marketplace/
│   │   │   ├── index.tsx        # Browse products
│   │   │   ├── [id].tsx         # Product detail
│   │   │   └── orders.tsx       # My orders
│   │   ├── livestock/
│   │   │   ├── index.tsx        # Browse listings
│   │   │   ├── [id].tsx         # Animal listing detail
│   │   │   └── auction.tsx      # Live auction
│   │   └── profile/
│   │       ├── index.tsx        # Profile & settings
│   │       └── notifications.tsx
│   └── (vendor)/                # Vendor-specific screens
│       ├── dashboard.tsx
│       ├── listings.tsx
│       └── orders.tsx
│
├── components/
│   ├── ui/                      # Shared UI components
│   ├── erp/                     # ERP-specific components
│   ├── marketplace/             # Marketplace components
│   └── livestock/               # Livestock components
│
├── services/
│   ├── api.ts                   # API client (Axios)
│   ├── auth.ts                  # Auth service
│   └── sync.ts                  # Offline sync service
│
├── database/
│   ├── schema.ts                # expo-sqlite schema definition
│   ├── migrations.ts            # Local DB migrations
│   └── sync-queue.ts            # Offline action queue
│
├── stores/
│   ├── auth.store.ts
│   ├── erp.store.ts
│   └── sync.store.ts
│
└── utils/
    ├── offline.ts               # Offline detection
    └── camera.ts                # Camera helpers
```

### 10.2 Offline-First Architecture

```typescript
// mobile/database/schema.ts
// Local SQLite schema for offline capability

export const LOCAL_TABLES = {
  // Cached master data (downloaded on sync)
  animals: `CREATE TABLE IF NOT EXISTS animals (
    id INTEGER PRIMARY KEY,
    tag_number TEXT NOT NULL,
    name TEXT,
    breed TEXT NOT NULL,
    sex TEXT NOT NULL,
    current_group TEXT NOT NULL,
    synced_at TEXT NOT NULL
  )`,

  // Offline action queue (uploaded on sync)
  sync_queue: `CREATE TABLE IF NOT EXISTS sync_queue (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    action TEXT NOT NULL,
    endpoint TEXT NOT NULL,
    payload TEXT NOT NULL,
    created_at TEXT NOT NULL,
    synced INTEGER DEFAULT 0,
    retry_count INTEGER DEFAULT 0,
    error TEXT
  )`,

  // Locally recorded milk data (pending sync)
  local_milk_records: `CREATE TABLE IF NOT EXISTS local_milk_records (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    animal_id INTEGER NOT NULL,
    record_date TEXT NOT NULL,
    session TEXT NOT NULL,
    quantity_liters REAL NOT NULL,
    recorded_by INTEGER NOT NULL,
    created_at TEXT NOT NULL,
    synced INTEGER DEFAULT 0
  )`,

  // Locally recorded health events
  local_health_events: `CREATE TABLE IF NOT EXISTS local_health_events (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    animal_id INTEGER NOT NULL,
    date TEXT NOT NULL,
    event_type TEXT NOT NULL,
    diagnosis TEXT,
    symptoms TEXT,
    treatment_details TEXT,
    photo_path TEXT,
    recorded_by INTEGER NOT NULL,
    created_at TEXT NOT NULL,
    synced INTEGER DEFAULT 0
  )`,
};
```

```typescript
// mobile/services/sync.ts
import * as SQLite from 'expo-sqlite';
import NetInfo from '@react-native-community/netinfo';
import { api } from './api';

export class SyncService {
  private db: SQLite.SQLiteDatabase;

  constructor(db: SQLite.SQLiteDatabase) {
    this.db = db;
  }

  // Download master data from server
  async pullMasterData(): Promise<void> {
    const animals = await api.get('/api/v1/erp/herd/animals?status=active');
    const now = new Date().toISOString();

    await this.db.execAsync('DELETE FROM animals');
    for (const animal of animals.data.data) {
      await this.db.runAsync(
        'INSERT INTO animals (id, tag_number, name, breed, sex, current_group, synced_at) VALUES (?, ?, ?, ?, ?, ?, ?)',
        [animal.id, animal.tagNumber, animal.name, animal.breed, animal.sex, animal.currentGroup, now]
      );
    }
  }

  // Push locally recorded data to server
  async pushPendingRecords(): Promise<{ synced: number; failed: number }> {
    let synced = 0;
    let failed = 0;

    // Sync milk records
    const milkRecords = await this.db.getAllAsync(
      'SELECT * FROM local_milk_records WHERE synced = 0 ORDER BY created_at'
    );

    for (const record of milkRecords) {
      try {
        await api.post('/api/v1/erp/milk/records', {
          animalId: record.animal_id,
          recordDate: record.record_date,
          session: record.session,
          quantityLiters: record.quantity_liters,
          recordedBy: record.recorded_by,
        });
        await this.db.runAsync('UPDATE local_milk_records SET synced = 1 WHERE id = ?', [record.id]);
        synced++;
      } catch (error) {
        await this.db.runAsync(
          'UPDATE local_milk_records SET retry_count = retry_count + 1 WHERE id = ?',
          [record.id]
        );
        failed++;
      }
    }

    // Similarly sync health events, attendance, etc.
    return { synced, failed };
  }

  // Full sync cycle
  async fullSync(): Promise<void> {
    const netInfo = await NetInfo.fetch();
    if (!netInfo.isConnected) return;

    await this.pushPendingRecords();
    await this.pullMasterData();
  }
}
```

### 10.3 Push Notifications

```typescript
// Notification categories for farm operations
const NOTIFICATION_CHANNELS = {
  farm_alerts: {
    name: 'Farm Alerts',
    importance: 'high',
    sound: true,
    vibrate: true,
    // Vaccination due, health emergency, calving expected
  },
  production: {
    name: 'Production Updates',
    importance: 'default',
    // Daily summary, milk anomaly, target reached
  },
  marketplace: {
    name: 'Marketplace',
    importance: 'default',
    // New order, shipping update, payout received
  },
  livestock: {
    name: 'Livestock Trading',
    importance: 'high',
    // Auction bid, listing sold, transport update
  },
};
```

---

## 11. FILE STORAGE STRATEGY

### 11.1 DigitalOcean Spaces Configuration

```
Bucket: smart-dairy-files
Region: sgp1 (Singapore - closest to Bangladesh)

Directory Structure:
  /products/           # Website product images
  /marketplace/        # Marketplace listing images
  /livestock/          # Animal listing photos
  /erp/
    /animals/          # Animal photos
    /documents/        # Health certificates, invoices
    /reports/          # Generated PDF reports
  /content/
    /blog/             # Blog post images
    /virtual-tour/     # 360° tour assets
  /users/
    /avatars/          # User profile photos
    /documents/        # NID, trade license uploads
```

### 11.2 Image Processing Pipeline

```typescript
// All uploaded images processed via Sharp before storage
Upload → Multer (temp) → Sharp Processing → DO Spaces Upload → DB URL Save

Processing steps:
  1. Validate file type (JPEG, PNG, WebP only)
  2. Strip EXIF data (privacy)
  3. Generate variants:
     - thumbnail: 150x150 (product grids)
     - medium: 600x600 (product cards)
     - large: 1200x1200 (product detail)
     - original: max 2000px (preserved)
  4. Convert to WebP (60% smaller than JPEG)
  5. Upload all variants to Spaces
  6. Store URLs in database
```

---

## 12. CACHING STRATEGY

### 12.1 Redis Cache Layers

```
Layer 1: Session Cache
  Key: session:{sessionId}
  TTL: 7 days
  Data: User session data, refresh token

Layer 2: Application Cache
  Key: cache:products:{page}:{filters}
  TTL: 5 minutes (product listings)

  Key: cache:product:{slug}
  TTL: 1 hour (individual product)

  Key: cache:mkt:products:{page}:{filters}
  TTL: 3 minutes (marketplace - changes more often)

  Key: cache:lvs:listings:{page}:{filters}
  TTL: 2 minutes (livestock - auction prices change)

Layer 3: Dashboard Cache
  Key: dashboard:milk:{date}
  TTL: No expiry (updated on each recording)
  Data: Total liters for the day

  Key: dashboard:herd:counts
  TTL: 10 minutes
  Data: { lactating: 75, heifers: 100, bulls: 40, calves: 30 }

  Key: dashboard:kpi:{date}
  TTL: 5 minutes
  Data: { production, revenue, costPerLiter, overdueVaccinations }

Layer 4: Auction State
  Key: auction:{auctionId}
  TTL: Until auction ends
  Data: { currentPrice, topBidderId, bidCount, endTime }
  Structure: Redis Hash for atomic updates

Layer 5: Rate Limiting
  Key: ratelimit:{ip}:{endpoint}
  TTL: Per rate limit window
  Data: Request count (atomic increment)

Layer 6: RBAC Cache
  Key: permissions:{userId}
  TTL: 15 minutes (matches access token lifetime)
  Data: User's resolved permissions array
```

### 12.2 Cache Invalidation

```
Strategy: Event-driven invalidation via event bus

When product updated → delete cache:product:{slug}, delete cache:products:*
When milk recorded → update dashboard:milk:{date} (increment, not replace)
When animal moved → delete dashboard:herd:counts
When auction bid → update auction:{id} hash (Redis HSET, atomic)
When user role changed → delete permissions:{userId}
```

---

## 13. JOB QUEUE & BACKGROUND PROCESSING

### 13.1 BullMQ Queue Architecture

```typescript
// backend/src/config/queues.ts
import { Queue, Worker } from 'bullmq';
import { redis } from './redis';

// Queue definitions
export const emailQueue = new Queue('email', { connection: redis });
export const smsQueue = new Queue('sms', { connection: redis });
export const payoutQueue = new Queue('payout', { connection: redis });
export const reportQueue = new Queue('report', { connection: redis });
export const iotQueue = new Queue('iot-ingestion', { connection: redis });
export const syncQueue = new Queue('mobile-sync', { connection: redis });

// Workers process jobs from queues
new Worker('email', async (job) => {
  const { to, subject, template, data } = job.data;
  await sendEmail(to, subject, template, data);
}, { connection: redis, concurrency: 5 });

new Worker('sms', async (job) => {
  const { phone, message, provider } = job.data;
  await sendSMS(phone, message, provider);
}, { connection: redis, concurrency: 3 });

new Worker('payout', async (job) => {
  const { vendorId, amount, method, reference } = job.data;
  await processVendorPayout(vendorId, amount, method, reference);
}, { connection: redis, concurrency: 1 }); // Sequential for financial ops

new Worker('report', async (job) => {
  const { type, period, format, userId } = job.data;
  const pdf = await generateReport(type, period, format);
  await notifyReportReady(userId, pdf.url);
}, { connection: redis, concurrency: 2 });
```

### 13.2 Scheduled Tasks (node-cron)

```typescript
// backend/src/jobs/scheduler.ts
import cron from 'node-cron';

// Daily at 6:00 AM: Vaccination reminders
cron.schedule('0 6 * * *', async () => {
  const dueVaccinations = await getOverdueVaccinations();
  for (const vax of dueVaccinations) {
    await smsQueue.add('vaccination-reminder', {
      phone: vax.farmManagerPhone,
      message: `Vaccination due: ${vax.animalTag} - ${vax.vaccineName} (due ${vax.dueDate})`,
    });
  }
});

// Daily at 8:00 PM: Daily milk production summary
cron.schedule('0 20 * * *', async () => {
  const summary = await getDailyMilkSummary();
  await smsQueue.add('daily-summary', {
    phone: process.env.FARM_MANAGER_PHONE,
    message: `Today's production: ${summary.totalLiters}L from ${summary.cowCount} cows. Avg: ${summary.avgPerCow}L/cow.`,
  });
});

// Weekly Monday 9:00 AM: Low feed inventory check
cron.schedule('0 9 * * 1', async () => {
  const lowStock = await getLowStockFeedItems();
  if (lowStock.length > 0) {
    await emailQueue.add('low-stock-alert', {
      to: process.env.FARM_MANAGER_EMAIL,
      subject: 'Low Feed Inventory Alert',
      template: 'low-stock',
      data: { items: lowStock },
    });
  }
});

// Every Friday: Marketplace vendor payout processing
cron.schedule('0 10 * * 5', async () => {
  const pendingPayouts = await getPendingVendorPayouts();
  for (const payout of pendingPayouts) {
    await payoutQueue.add('process-payout', {
      vendorId: payout.vendorId,
      amount: payout.amount,
      method: payout.preferredMethod,
      reference: payout.referenceId,
    });
  }
});

// Monthly 1st at 2:00 AM: Generate monthly reports
cron.schedule('0 2 1 * *', async () => {
  const lastMonth = getPreviousMonth();
  await reportQueue.add('monthly-production', { type: 'production', period: lastMonth, format: 'pdf' });
  await reportQueue.add('monthly-financial', { type: 'financial', period: lastMonth, format: 'pdf' });
  await reportQueue.add('monthly-herd', { type: 'herd', period: lastMonth, format: 'pdf' });
});
```

---

## 14. SEARCH ARCHITECTURE

### 14.1 Phase 1-3: PostgreSQL Full-Text Search

```sql
-- Product search (website + marketplace)
CREATE INDEX idx_products_fts ON web.products
  USING GIN (to_tsvector('english', name || ' ' || COALESCE(description, '')));

-- Marketplace product search
CREATE INDEX idx_mkt_products_fts ON mkt.mkt_products
  USING GIN (to_tsvector('english', name || ' ' || COALESCE(description, '')));

-- Livestock search
CREATE INDEX idx_lvs_listings_fts ON lvs.animal_listings
  USING GIN (to_tsvector('english', breed || ' ' || COALESCE(description, '')));

-- Search query example
SELECT * FROM web.products
WHERE to_tsvector('english', name || ' ' || COALESCE(description, ''))
  @@ plainto_tsquery('english', $1)
AND status = 'active'
ORDER BY ts_rank(to_tsvector('english', name || ' ' || COALESCE(description, '')),
  plainto_tsquery('english', $1)) DESC
LIMIT 20;
```

### 14.2 Phase 4+: Meilisearch Integration

Add Meilisearch when marketplace products exceed 1,000 items for typo-tolerant, faceted search:

```typescript
// backend/src/common/search/search.service.ts
import { MeiliSearch } from 'meilisearch';

const meili = new MeiliSearch({
  host: process.env.MEILISEARCH_URL || 'http://localhost:7700',
  apiKey: process.env.MEILISEARCH_API_KEY,
});

// Indexes
const productsIndex = meili.index('products');      // Website + Marketplace
const livestockIndex = meili.index('livestock');     // Animal listings
const blogIndex = meili.index('blog');               // Blog posts

// Configure searchable attributes and filters
await productsIndex.updateSettings({
  searchableAttributes: ['name', 'description', 'category', 'vendor'],
  filterableAttributes: ['category', 'vendor', 'priceRange', 'rating', 'source'],
  sortableAttributes: ['price', 'rating', 'createdAt'],
});

// Unified search across all indexes
export async function globalSearch(query: string) {
  const results = await meili.multiSearch({
    queries: [
      { indexUid: 'products', q: query, limit: 5 },
      { indexUid: 'livestock', q: query, limit: 3 },
      { indexUid: 'blog', q: query, limit: 3 },
    ],
  });
  return results;
}
```

---

## 15. NOTIFICATION SYSTEM

### 15.1 Multi-Channel Notification Service

```typescript
// backend/src/common/notifications/notification.service.ts

interface NotificationPayload {
  userId: number;
  channels: ('sms' | 'email' | 'push' | 'in_app')[];
  template: string;
  data: Record<string, any>;
  priority: 'low' | 'normal' | 'high' | 'critical';
  language?: 'en' | 'bn';
}

export async function sendNotification(payload: NotificationPayload): Promise<void> {
  const user = await prisma.user.findUnique({ where: { id: payload.userId } });
  if (!user) return;

  const lang = payload.language || user.language || 'en';

  // Render template in user's language
  const content = renderTemplate(payload.template, payload.data, lang);

  for (const channel of payload.channels) {
    switch (channel) {
      case 'sms':
        await smsQueue.add('send-sms', {
          phone: user.phone,
          message: content.sms,
          priority: payload.priority,
        });
        break;

      case 'email':
        await emailQueue.add('send-email', {
          to: user.email,
          subject: content.emailSubject,
          html: content.emailBody,
        });
        break;

      case 'push':
        await sendPushNotification(user.id, {
          title: content.pushTitle,
          body: content.pushBody,
          data: payload.data,
        });
        break;

      case 'in_app':
        await prisma.notification.create({
          data: {
            userId: user.id,
            type: payload.template,
            title: content.pushTitle,
            message: content.pushBody,
            data: payload.data,
            read: false,
          },
        });
        // Real-time via Socket.IO
        io.to(`user:${user.id}`).emit('notification', {
          title: content.pushTitle,
          message: content.pushBody,
        });
        break;
    }
  }
}
```

### 15.2 Notification Templates

```typescript
// Critical farm alerts (always SMS + push)
const FARM_ALERT_TEMPLATES = {
  vaccination_due: {
    en: { sms: 'Smart Dairy Alert: {animalTag} vaccination due - {vaccineName} by {dueDate}' },
    bn: { sms: 'স্মার্ট ডেইরি: {animalTag} টিকা দিতে হবে - {vaccineName} তারিখ {dueDate}' },
  },
  calving_expected: {
    en: { sms: 'Smart Dairy Alert: {animalTag} expected to calve within 48 hours' },
    bn: { sms: 'স্মার্ট ডেইরি: {animalTag} ৪৮ ঘন্টার মধ্যে বাছুর প্রসবের সম্ভাবনা' },
  },
  health_emergency: {
    en: { sms: 'URGENT: {animalTag} health emergency - {diagnosis}. Immediate attention required.' },
    bn: { sms: 'জরুরি: {animalTag} স্বাস্থ্য জরুরি অবস্থা - {diagnosis}। তাৎক্ষণিক মনোযোগ প্রয়োজন।' },
  },
  tank_temp_alert: {
    en: { sms: 'ALERT: Bulk tank {tankId} temperature {temperature}°C exceeds safe limit (8°C)' },
    bn: { sms: 'সতর্কতা: বাল্ক ট্যাংক {tankId} তাপমাত্রা {temperature}°C নিরাপদ সীমা (৮°C) ছাড়িয়েছে' },
  },
  low_feed_inventory: {
    en: { sms: 'Smart Dairy: {feedItem} stock low - {currentStock}{unit} remaining (reorder level: {reorderLevel}{unit})' },
    bn: { sms: 'স্মার্ট ডেইরি: {feedItem} মজুদ কম - {currentStock}{unit} বাকি' },
  },
};
```

---

## 16. MONITORING & OBSERVABILITY

### 16.1 Monitoring Stack

```
Prometheus (metrics collection)
  → Scrapes Node.js metrics every 15 seconds
  → Custom metrics: API response times, queue depths, active users, milk production

Grafana (dashboards)
  → System Dashboard: CPU, memory, disk, network
  → Application Dashboard: API latency, error rates, request volume
  → Business Dashboard: Orders, milk production, active users, revenue
  → ERP Dashboard: Herd counts, daily production, feed inventory levels

Winston (structured logging)
  → JSON format, correlated request IDs
  → Log levels: error, warn, info, debug
  → Output: stdout (Docker logs) + file rotation

Sentry (error tracking)
  → Automatic error capture with stack traces
  → Source maps for frontend errors
  → Performance monitoring (transaction tracing)
```

### 16.2 Custom Prometheus Metrics

```typescript
import { Counter, Histogram, Gauge } from 'prom-client';

// API metrics
const httpRequestDuration = new Histogram({
  name: 'http_request_duration_seconds',
  help: 'HTTP request duration',
  labelNames: ['method', 'route', 'status'],
  buckets: [0.05, 0.1, 0.25, 0.5, 1, 2.5, 5],
});

// Business metrics
const dailyMilkProduction = new Gauge({
  name: 'dairy_milk_production_liters',
  help: 'Daily milk production in liters',
});

const activeHerdCount = new Gauge({
  name: 'dairy_herd_count',
  help: 'Active herd count',
  labelNames: ['group'], // lactating, heifer, bull, calf
});

const marketplaceOrders = new Counter({
  name: 'marketplace_orders_total',
  help: 'Total marketplace orders',
  labelNames: ['vendor', 'status'],
});

const queueDepth = new Gauge({
  name: 'bullmq_queue_depth',
  help: 'BullMQ queue depth',
  labelNames: ['queue'],
});
```

---

## 17. DEPLOYMENT ARCHITECTURE

### 17.1 Docker Compose (Development)

```yaml
# docker-compose.yml
version: '3.8'

services:
  frontend:
    build:
      context: ./frontend
      dockerfile: ../docker/Dockerfile.frontend
    ports:
      - "3000:3000"
    volumes:
      - ./frontend/src:/app/src
    environment:
      - NEXT_PUBLIC_API_URL=http://localhost:5000/api/v1
    depends_on:
      - backend

  backend:
    build:
      context: ./backend
      dockerfile: ../docker/Dockerfile.backend
    ports:
      - "5000:5000"
    volumes:
      - ./backend/src:/app/src
      - ./shared:/app/shared
    environment:
      - DATABASE_URL=postgresql://smartdairy:password@postgres:5432/smartdairy
      - REDIS_URL=redis://redis:6379
      - MQTT_BROKER_URL=mqtt://mosquitto:1883
    depends_on:
      - postgres
      - redis
      - mosquitto

  postgres:
    image: postgres:16-alpine
    ports:
      - "5432:5432"
    environment:
      - POSTGRES_DB=smartdairy
      - POSTGRES_USER=smartdairy
      - POSTGRES_PASSWORD=password
    volumes:
      - postgres_data:/var/lib/postgresql/data

  redis:
    image: redis:7-alpine
    ports:
      - "6379:6379"
    volumes:
      - redis_data:/data

  mosquitto:
    image: eclipse-mosquitto:2
    ports:
      - "1883:1883"
      - "9001:9001"
    volumes:
      - ./docker/mosquitto/mosquitto.conf:/mosquitto/config/mosquitto.conf

  # Monitoring (optional in dev)
  prometheus:
    image: prom/prometheus:latest
    ports:
      - "9090:9090"
    profiles: ["monitoring"]

  grafana:
    image: grafana/grafana:latest
    ports:
      - "3001:3000"
    profiles: ["monitoring"]

volumes:
  postgres_data:
  redis_data:
```

### 17.2 Production Deployment

```
DigitalOcean Droplet (4 vCPU, 8GB RAM, 80GB SSD)
  │
  ├── Nginx (reverse proxy, SSL via Let's Encrypt)
  │     ├── smartdairy.com.bd → Next.js (port 3000)
  │     └── api.smartdairy.com.bd → Express.js (port 5000)
  │
  ├── Docker Compose (production)
  │     ├── frontend (Next.js, PM2 cluster mode)
  │     ├── backend (Express.js, PM2 cluster mode)
  │     ├── postgres (with WAL archiving)
  │     ├── redis (with AOF persistence)
  │     ├── mosquitto (MQTT broker)
  │     ├── prometheus + grafana (monitoring)
  │     └── meilisearch (Phase 4+)
  │
  ├── DigitalOcean Spaces (file storage, backups)
  └── Cloudflare (CDN, WAF, DDoS protection)
```

### 17.3 CI/CD Pipeline (GitHub Actions)

```yaml
# .github/workflows/ci.yml
name: CI

on:
  pull_request:
    branches: [main, develop]

jobs:
  lint-and-test:
    runs-on: ubuntu-latest
    services:
      postgres:
        image: postgres:16-alpine
        env:
          POSTGRES_DB: test
          POSTGRES_PASSWORD: test
        ports: ['5432:5432']
      redis:
        image: redis:7-alpine
        ports: ['6379:6379']

    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: 22

      - name: Install & lint backend
        working-directory: ./backend
        run: |
          npm ci
          npm run lint
          npm run test

      - name: Install & lint frontend
        working-directory: ./frontend
        run: |
          npm ci
          npm run lint
          npm run build

      - name: Type check shared
        working-directory: ./shared
        run: npx tsc --noEmit
```

---

## 18. SECURITY ARCHITECTURE

### 18.1 Security Layers

```
Layer 1: Network
  - Cloudflare WAF (OWASP Top 10 rules)
  - DDoS protection (Cloudflare)
  - TLS 1.3 everywhere (SSL/TLS termination at Nginx)
  - VPN for admin/ERP access (optional)

Layer 2: Application
  - Helmet.js security headers
  - CORS whitelist (specific origins only)
  - Rate limiting per endpoint category
  - Input validation (Zod schemas on every endpoint)
  - SQL injection prevention (Prisma parameterized queries)
  - XSS prevention (React auto-escaping + CSP headers)
  - CSRF protection (SameSite cookies + CSRF tokens for forms)

Layer 3: Authentication
  - JWT with short-lived access tokens (15 min)
  - Refresh token rotation (detect token reuse)
  - bcrypt password hashing (salt rounds: 12)
  - Account lockout after 5 failed attempts (30 min)
  - OTP via SMS for sensitive operations

Layer 4: Authorization
  - RBAC with granular permissions
  - Row-level security for marketplace (vendor sees only own data)
  - Schema-level isolation (modules cannot access other schemas directly)

Layer 5: Data
  - Encryption at rest (PostgreSQL TDE or filesystem encryption)
  - Sensitive fields encrypted in application layer (NID numbers, bank accounts)
  - PCI DSS compliance (never store card numbers - use payment gateway tokens)
  - GDPR-style data handling (soft deletes, data export capability)

Layer 6: Monitoring
  - Audit logging for all data mutations (who, what, when)
  - Failed login attempt monitoring
  - Anomaly detection (unusual API patterns)
  - Sentry error tracking with PII scrubbing
```

### 18.2 Sensitive Data Handling

```typescript
// Fields requiring encryption at rest
const ENCRYPTED_FIELDS = [
  'user.nid_number',           // National ID
  'vendor.bank_account_number', // Bank account
  'vendor.trade_license',       // Trade license number
  'employee.nid_number',        // Employee NID
  'employee.bank_account',      // Employee bank details
];

// Fields requiring masking in logs
const MASKED_FIELDS = [
  'password', 'passwordHash', 'token', 'refreshToken',
  'nidNumber', 'bankAccount', 'cardNumber',
];
```

---

## 19. SCALABILITY STRATEGY

### 19.1 Current vs Target Scale

| Metric | Current (Phase 1) | Target (Phase 6) | Architecture Support |
|--------|-------------------|-------------------|---------------------|
| Herd size | 260 cattle | 1,200 cattle | Table partitioning, indexed queries |
| Daily milk records | ~150/day | ~2,400/day | Partitioned by year, batch inserts |
| Concurrent web users | 100 | 500+ | Redis cache, CDN, connection pooling |
| ERP users | 10 | 50 | Stateless API, session in Redis |
| Marketplace vendors | 0 | 200+ | Row-level security, indexed queries |
| Marketplace products | 0 | 10,000+ | Meilisearch, pagination |
| Database size | ~100MB | ~5GB | Single PostgreSQL instance sufficient |
| File storage | ~1GB | ~50GB | DO Spaces auto-scales |

### 19.2 Scaling Path (If Needed Beyond Year 2)

```
Step 1 (Current): Single server, all services
  4 vCPU, 8GB RAM DigitalOcean Droplet

Step 2 (If CPU-bound): Upgrade server
  8 vCPU, 16GB RAM ($96/mo)

Step 3 (If DB-bound): Add read replica
  PostgreSQL read replica for reporting queries
  Write to primary, read from replica

Step 4 (If traffic-bound): Horizontal scaling
  Add second app server behind Nginx load balancer
  Stateless design already supports this

Step 5 (If search-bound): Dedicated Meilisearch
  Move Meilisearch to separate server

Note: Steps 3-5 are unlikely to be needed within the 24-month timeline
given the projected scale. The architecture supports them without
code changes due to the modular design.
```

---

## APPENDIX A: ENVIRONMENT VARIABLES

```bash
# .env (backend)

# Server
NODE_ENV=development
PORT=5000
FRONTEND_URL=http://localhost:3000

# Database
DATABASE_URL=postgresql://smartdairy:password@localhost:5432/smartdairy

# Redis
REDIS_URL=redis://localhost:6379

# JWT
JWT_SECRET=your-256-bit-secret
JWT_REFRESH_SECRET=your-256-bit-refresh-secret

# MQTT
MQTT_BROKER_URL=mqtt://localhost:1883
MQTT_USERNAME=smartdairy
MQTT_PASSWORD=mqtt-password

# File Storage (DigitalOcean Spaces)
DO_SPACES_KEY=your-spaces-key
DO_SPACES_SECRET=your-spaces-secret
DO_SPACES_ENDPOINT=sgp1.digitaloceanspaces.com
DO_SPACES_BUCKET=smart-dairy-files

# Payment Gateways
SSLCOMMERZ_STORE_ID=your-store-id
SSLCOMMERZ_STORE_PASS=your-store-password
SSLCOMMERZ_IS_LIVE=false
BKASH_APP_KEY=your-bkash-key
BKASH_APP_SECRET=your-bkash-secret
NAGAD_MERCHANT_ID=your-nagad-id

# SMS
SMS_PROVIDER=bulksmsbd
SMS_API_KEY=your-sms-api-key

# Email
SENDGRID_API_KEY=your-sendgrid-key
EMAIL_FROM=noreply@smartdairy.com.bd

# Meilisearch (Phase 4+)
MEILISEARCH_URL=http://localhost:7700
MEILISEARCH_API_KEY=your-meili-key

# Sentry
SENTRY_DSN=your-sentry-dsn

# Farm Config
FARM_MANAGER_PHONE=+880XXXXXXXXXX
FARM_MANAGER_EMAIL=manager@smartdairy.com.bd
```

---

## APPENDIX B: RELATED DOCUMENTS

| Document | Path | Description |
|----------|------|-------------|
| Database Design | [SRS/05_Database_Design_Document.md](05_Database_Design_Document.md) | Complete schema for all 105 tables |
| API Specification | [SRS/06_API_Specification.md](06_API_Specification.md) | Full endpoint documentation with request/response schemas |
| Functional Requirements | [SRS/02_Functional_Requirements.md](02_Functional_Requirements.md) | Detailed feature specifications per module |
| Deployment Guide | [SRS/08_Deployment_DevOps_Strategy.md](08_Deployment_DevOps_Strategy.md) | Production deployment procedures |
| Testing Plan | [SRS/07_Testing_QA_Plan.md](07_Testing_QA_Plan.md) | Test strategy and coverage targets |
| Technology Stack | [TECHNOLOGY_STACK/technology_architecture.md](../TECHNOLOGY_STACK/technology_architecture.md) | Detailed technology decisions and configurations |
| Implementation Roadmap | [roadmap/](../roadmap/) | 24-month phased implementation plan |

---

**Document Status:** Complete
**Architecture Review:** Pending stakeholder approval
**Next Action:** Begin Phase 0 (Architecture Foundation) implementation
**Maintained By:** Senior Solutions Architect / Lead Developer
