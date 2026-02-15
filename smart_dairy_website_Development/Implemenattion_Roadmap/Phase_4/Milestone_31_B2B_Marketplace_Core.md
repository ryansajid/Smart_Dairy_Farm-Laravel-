# Milestone 31 - B2B Marketplace Core Implementation

## Smart Dairy Smart Portal + ERP System Implementation Roadmap

---

**Document Version:** 1.0  
**Milestone Duration:** Days 301-310 (10 Working Days)  
**Phase:** Phase 4 - Advanced Features & Integration  
**Classification:** Technical Implementation Document  
**Author:** Smart Dairy Technical Architecture Team  
**Date:** February 2026  
**Review Status:** Draft for Implementation  

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Technical Architecture](#2-technical-architecture)
3. [Detailed Daily Task Allocation](#3-detailed-daily-task-allocation)
4. [Database Schema & Models](#4-database-schema--models)
5. [Pseudocode & Implementation Logic](#5-pseudocode--implementation-logic)
6. [API Specifications](#6-api-specifications)
7. [Frontend Implementation](#7-frontend-implementation)
8. [Testing Protocols](#8-testing-protocols)
9. [Deliverables & Acceptance Criteria](#9-deliverables--acceptance-criteria)
10. [Risk & Issue Management](#10-risk--issue-management)
11. [Appendices](#11-appendices)

---

## 1. Executive Summary

### 1.1 Milestone Vision and Goals

Milestone 31 represents a critical inflection point in the Smart Dairy Smart Portal + ERP System development lifecycle, marking the transition from foundational B2B portal infrastructure to fully operational B2B Marketplace Core functionality. This milestone builds upon the substantial groundwork laid during Phase 3, where the B2B module scaffold was established, partner registration workflows were implemented, and multi-user account structures were prepared for production use.

The vision for Milestone 31 encompasses the transformation of the Smart Dairy B2B portal from a static information and registration platform into a dynamic, transactional marketplace that enables seamless business-to-business commerce. This transformation requires the implementation of sophisticated pricing engines, intuitive ordering interfaces, bulk processing capabilities, and robust integration with the ERP backend to ensure real-time inventory visibility, credit management, and order fulfillment.

#### Primary Strategic Goals

**Goal 1: Enable Tier-Based B2B Commerce**
The marketplace must support complex B2B pricing structures that recognize the hierarchical nature of business relationships. Different partner tiers (Bronze, Silver, Gold, Platinum, Diamond) will have access to differentiated pricing, volume discounts, and exclusive product catalogs. This tier-based approach incentivizes partners to increase their business volume with Smart Dairy while providing the flexibility to negotiate custom terms for strategic accounts.

**Goal 2: Streamline the Ordering Process**
B2B customers have unique ordering requirements that differ significantly from B2C scenarios. The Quick Order Pad interface will allow experienced buyers to rapidly enter product codes and quantities without navigating through browse-heavy interfaces. Additionally, bulk ordering via CSV upload will enable procurement teams to process large, complex orders efficiently, integrating with their existing procurement systems and workflows.

**Goal 3: Integrate Real-Time Credit Management**
Unlike B2C transactions where payment is typically immediate, B2B commerce operates on credit terms. The marketplace must integrate with the ERP's credit limit system to validate orders against available credit in real-time, preventing orders that would exceed credit limits while providing clear visibility into credit utilization and availability.

**Goal 4: Ensure ERP Synchronization**
All marketplace activities must be reflected in the ERP system in real-time or near-real-time. This includes inventory updates, order creation, credit utilization, and fulfillment status. The integration architecture must handle high transaction volumes while maintaining data consistency and integrity across systems.

### 1.2 Success Criteria

The successful completion of Milestone 31 will be measured against the following quantitative and qualitative criteria:

#### Quantitative Success Metrics

| Metric | Target | Measurement Method |
|--------|--------|-------------------|
| API Response Time (p95) | < 200ms | Application Performance Monitoring |
| Database Query Time (p99) | < 50ms | PostgreSQL Slow Query Log |
| CSV Upload Processing | 1000 lines/sec | Load Testing |
| Concurrent User Support | 500+ | Load Testing with JMeter |
| Code Coverage | > 85% | Coverage.py Reports |
| Integration Test Pass Rate | 100% | Automated Test Suite |
| Security Vulnerability Count | 0 Critical/High | OWASP ZAP Scan |
| Uptime During Testing | 99.9% | Infrastructure Monitoring |

#### Qualitative Success Criteria

1. **User Experience Excellence**: The Quick Order Pad must enable experienced users to complete orders in less than 60 seconds for up to 50 line items.

2. **Pricing Accuracy**: Tier pricing calculations must be 100% accurate across all tested scenarios, including edge cases with multiple discount types.

3. **Credit Validation Reliability**: Credit limit validation must prevent 100% of orders that would exceed available credit, with zero false positives or negatives.

4. **ERP Data Consistency**: Zero data inconsistencies between marketplace and ERP systems after transaction completion.

5. **Mobile Responsiveness**: All interfaces must be fully functional on tablet devices commonly used by field sales teams.

### 1.3 Key Deliverables

Milestone 31 will produce the following tangible deliverables:

#### 1.3.1 Software Components

**Backend Components:**
- B2B Catalog Management Module (Odoo)
- Tier Pricing Engine (Python/Odoo)
- Quick Order Pad Service (Python/FastAPI)
- CSV Import Processor (Python/Celery)
- Cart Management Service (Python/Redis)
- Credit Validation Service (Python/Odoo)
- Order Workflow Integration Layer (Python)

**Frontend Components:**
- Quick Order Pad React Component
- B2B Product Catalog Grid (React)
- Shopping Cart Interface (React)
- CSV Upload Component (React)
- Tier Pricing Display Components (React)
- Mobile-Responsive Layout Components (React/CSS)

**Database Artifacts:**
- Complete Database Schema (PostgreSQL)
- Migration Scripts
- Index Optimization Scripts
- Partitioning Strategy for High-Volume Tables

#### 1.3.2 Documentation Deliverables

- API Documentation (OpenAPI 3.0)
- Technical Architecture Diagrams
- Deployment Guide
- Operations Runbook
- User Training Materials
- Test Case Documentation

#### 1.3.3 Testing Artifacts

- Unit Test Suite (pytest)
- Integration Test Suite
- Performance Test Scripts (JMeter)
- Security Test Results
- User Acceptance Test Results

---

## 2. Technical Architecture

### 2.1 B2B Marketplace System Architecture

The B2B Marketplace Core implementation follows a microservices-oriented architecture within the Odoo framework, leveraging modern Python development practices and React-based frontend technologies. This architecture balances the comprehensive ERP capabilities of Odoo with the performance and flexibility requirements of a high-volume B2B marketplace.

#### 2.1.1 High-Level Architecture Overview

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                         SMART DAIRY B2B MARKETPLACE                              │
│                           SYSTEM ARCHITECTURE                                    │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────┐   │
│  │                        PRESENTATION LAYER                                │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐ │   │
│  │  │   React SPA  │  │  Quick Order │  │   Mobile     │  │   Admin      │ │   │
│  │  │   (Web App)  │  │     Pad      │  │   Flutter    │  │   Dashboard  │ │   │
│  │  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘ │   │
│  └─────────┼─────────────────┼─────────────────┼─────────────────┼─────────┘   │
│            │                 │                 │                 │              │
│            └─────────────────┴────────┬────────┴─────────────────┘              │
│                                       │                                          │
│                              ┌────────▼────────┐                                 │
│                              │  API Gateway    │                                 │
│                              │  (Kong/Nginx)   │                                 │
│                              │  - Rate Limit   │                                 │
│                              │  - Auth/JWT     │                                 │
│                              │  - Logging      │                                 │
│                              └────────┬────────┘                                 │
│                                       │                                          │
│  ┌────────────────────────────────────┼─────────────────────────────────────┐   │
│  │                      API LAYER (REST/GraphQL)                            │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │   │
│  │  │   Catalog    │  │    Cart      │  │    Order     │  │   Credit     │  │   │
│  │  │    API       │  │    API       │  │     API      │  │    API       │  │   │
│  │  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘  │   │
│  └─────────┼─────────────────┼─────────────────┼─────────────────┼──────────┘   │
│            │                 │                 │                 │               │
│  ┌─────────┴─────────────────┴─────────────────┴─────────────────┴──────────┐   │
│  │                      SERVICE LAYER (Python/Odoo)                          │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │   │
│  │  │    B2B       │  │   Pricing    │  │    Order     │  │   Credit     │  │   │
│  │  │   Catalog    │  │   Engine     │  │   Processor  │  │  Validator   │  │   │
│  │  │   Service    │  │              │  │              │  │              │  │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘  │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │   │
│  │  │   Quick      │  │     CSV      │  │   Workflow   │  │ Notification │  │   │
│  │  │   Order      │  │   Import     │  │   Engine     │  │   Service    │  │   │
│  │  │   Service    │  │   Service    │  │              │  │              │  │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘  │   │
│  └──────────────────────────────────────────────────────────────────────────┘   │
│                                       │                                          │
│                              ┌────────▼────────┐                                 │
│                              │  Message Queue  │                                 │
│                              │    (Celery +    │                                 │
│                              │     Redis)      │                                 │
│                              └────────┬────────┘                                 │
│                                       │                                          │
│  ┌────────────────────────────────────┼─────────────────────────────────────┐   │
│  │                      DATA LAYER                                          │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │   │
│  │  │  PostgreSQL  │  │    Redis     │  │Elasticsearch │  │    MinIO     │  │   │
│  │  │   (Primary)  │  │   (Cache)    │  │   (Search)   │  │  (Files)     │  │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘  │   │
│  └──────────────────────────────────────────────────────────────────────────┘   │
│                                       │                                          │
│                              ┌────────▼────────┐                                 │
│  ┌───────────────────────────┤  ERP INTEGRATION  ├────────────────────────────┐ │
│  │                           │     LAYER         │                            │ │
│  │                           └────────┬────────┘                            │ │
│  │                                    │                                      │ │
│  │  ┌─────────────────────────────────┼──────────────────────────────────┐  │ │
│  │  │                    ODOO ERP CORE                                     │  │ │
│  │  │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐  │  │ │
│  │  │  │ Inventory│ │  Sales   │ │Accounting│ │  CRM     │ │ Purchasing│  │  │ │
│  │  │  │  Module  │ │  Module  │ │  Module  │ │  Module  │ │  Module   │  │  │ │
│  │  │  └──────────┘ └──────────┘ └──────────┘ └──────────┘ └──────────┘  │  │ │
│  │  └───────────────────────────────────────────────────────────────────┘  │ │
│  └─────────────────────────────────────────────────────────────────────────┘ │
│                                                                               │
└─────────────────────────────────────────────────────────────────────────────────┘
```

#### 2.1.2 Component Architecture Details

**API Gateway Layer**
The API Gateway serves as the single entry point for all client requests, providing:
- Authentication and authorization via JWT tokens
- Rate limiting to prevent abuse (1000 req/min per API key)
- Request/response logging for audit trails
- SSL termination and security headers
- Load balancing across multiple backend instances
- API versioning support (/v1/, /v2/)

**Service Layer Architecture**
The service layer implements domain-driven design principles with clear bounded contexts:

1. **B2B Catalog Service**: Manages product visibility, categorization, and tier-based access control
2. **Pricing Engine**: Calculates prices based on partner tier, volume, and custom agreements
3. **Order Processor**: Handles order validation, submission, and status tracking
4. **Credit Validator**: Integrates with ERP accounting for real-time credit checks
5. **Quick Order Service**: Optimized service for rapid order entry scenarios
6. **CSV Import Service**: Asynchronous processing of bulk orders via file upload
7. **Workflow Engine**: Manages order approval flows and state transitions
8. **Notification Service**: Email and in-app notifications for order updates

**Data Layer Components**

1. **PostgreSQL 16**: Primary transactional database with:
   - Master-slave replication for read scaling
   - Connection pooling via PgBouncer
   - Automated backup and point-in-time recovery
   - Partitioning for order history tables

2. **Redis 7**: Multi-purpose cache and queue:
   - Session storage with TTL
   - Product catalog caching
   - Cart state management
   - Celery task queue backend
   - Real-time price caching

3. **Elasticsearch 8**: Full-text search and analytics:
   - Product search with faceting
   - Order history search
   - Analytics aggregation
   - Auto-complete suggestions

4. **MinIO**: Object storage for:
   - CSV upload files
   - Product images and documents
   - Export files and reports

### 2.2 Component Diagrams

#### 2.2.1 B2B Catalog Component Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                          B2B CATALOG MODULE                                  │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                        Catalog Controller                            │   │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐   │   │
│  │  │  Product    │ │  Category   │ │   Tier      │ │   Search    │   │   │
│  │  │  Listing    │ │   Browser   │ │   Filter    │ │   Endpoint  │   │   │
│  │  └──────┬──────┘ └──────┬──────┘ └──────┬──────┘ └──────┬──────┘   │   │
│  └─────────┼───────────────┼───────────────┼───────────────┼──────────┘   │
│            │               │               │               │               │
│            └───────────────┴───────┬───────┴───────────────┘               │
│                                    │                                        │
│  ┌─────────────────────────────────┼─────────────────────────────────────┐ │
│  │                        Catalog Service Layer                         │ │
│  │  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐   │ │
│  │  │  Product         │  │  Tier Access     │  │  Pricing         │   │ │
│  │  │  Visibility      │  │  Control         │  │  Resolver        │   │ │
│  │  │  Service         │  │  Service         │  │  Service         │   │ │
│  │  └──────────────────┘  └──────────────────┘  └──────────────────┘   │ │
│  └─────────────────────────────────────────────────────────────────────┘ │
│                                    │                                      │
│            ┌───────────────────────┼───────────────────────┐              │
│            │                       │                       │              │
│  ┌─────────▼─────────┐  ┌──────────▼──────────┐  ┌────────▼────────┐    │
│  │   Product         │  │    Partner Tier     │  │    Price        │    │
│  │   Repository      │  │    Repository       │  │    Repository   │    │
│  │                   │  │                     │  │                 │    │
│  │  - find_visible() │  │  - get_tier()       │  │  - get_price()  │    │
│  │  - search()       │  │  - check_access()   │  │  - get_tier()   │    │
│  │  - get_details()  │  │  - list_products()  │  │  - calculate()  │    │
│  └─────────┬─────────┘  └──────────┬──────────┘  └────────┬────────┘    │
│            │                       │                       │              │
│            └───────────────────────┼───────────────────────┘              │
│                                    │                                      │
│  ┌─────────────────────────────────┼─────────────────────────────────────┐│
│  │                        Data Access Layer                             ││
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐               ││
│  │  │  PostgreSQL  │  │    Redis     │  │Elasticsearch │               ││
│  │  │  (Products)  │  │  (Cache)     │  │   (Search)   │               ││
│  │  └──────────────┘  └──────────────┘  └──────────────┘               ││
│  └─────────────────────────────────────────────────────────────────────┘│
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### 2.2.2 Order Processing Component Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                       ORDER PROCESSING SYSTEM                                │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                      Order Entry Interfaces                          │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │ Quick Order  │  │   Standard   │  │  CSV Import  │              │   │
│  │  │     Pad      │  │    Cart      │  │   Upload     │              │   │
│  │  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘              │   │
│  └─────────┼─────────────────┼─────────────────┼──────────────────────┘   │
│            │                 │                 │                           │
│            └─────────────────┴────────┬────────┴───────────────────────────┘
│                                       │                                    │
│  ┌────────────────────────────────────┼──────────────────────────────────┐│
│  │                      Order Orchestrator                              ││
│  │  ┌────────────────────────────────────────────────────────────────┐  ││
│  │  │                     Order Validation Pipeline                   │  ││
│  │  │  ┌────────────┐ ┌────────────┐ ┌────────────┐ ┌────────────┐  │  ││
│  │  │  │  Product   │ │   Stock    │ │   Credit   │ │   Price    │  │  ││
│  │  │  │ Validation │ │  Check     │ │  Check     │ │ Validation │  │  ││
│  │  │  └────────────┘ └────────────┘ └────────────┘ └────────────┘  │  ││
│  │  └────────────────────────────────────────────────────────────────┘  ││
│  └─────────────────────────────────────────────────────────────────────┘│
│                                       │                                   │
│            ┌──────────────────────────┼──────────────────────────┐       │
│            │                          │                          │       │
│  ┌─────────▼──────────┐  ┌────────────▼────────────┐  ┌──────────▼──────┐│
│  │   Cart Service     │  │    Order Service        │  │  Workflow       ││
│  │                    │  │                         │  │  Engine         ││
│  │  - add_item()      │  │  - create_order()       │  │                 ││
│  │  - update_qty()    │  │  - submit_order()       │  │  - approve()    ││
│  │  - remove_item()   │  │  - cancel_order()       │  │  - reject()     ││
│  │  - checkout()      │  │  - get_status()         │  │  - escalate()   ││
│  └─────────┬──────────┘  └────────────┬────────────┘  └──────────┬──────┘│
│            │                          │                          │       │
│            │              ┌───────────┴───────────┐              │       │
│            │              │                       │              │       │
│  ┌─────────▼──────────┐  ┌▼──────────────┐ ┌──────▼───────┐  ┌───▼──────┐│
│  │  Redis Cart Store  │  │ Order Repository│ │ Order Line   │  │  ERP     ││
│  │                    │  │                 │ │ Repository   │  │  Sync    ││
│  │  (Session-based)   │  │  - save()       │ │              │  │          ││
│  │  (TTL: 7 days)     │  │  - find()       │ │  - save()    │  │  - push  ││
│  │                    │  │  - update()     │ │  - find()    │  │  - pull  ││
│  └────────────────────┘  └─────────────────┘ └──────────────┘  └─────────┘│
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.3 Integration Points with ERP

The B2B Marketplace Core integrates with multiple Odoo ERP modules to ensure data consistency and business process alignment.

#### 2.3.1 ERP Integration Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         ERP INTEGRATION LAYER                                │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    Integration API Gateway                           │   │
│  │                                                                       │   │
│  │  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌────────────┐    │   │
│  │  │  Internal  │  │   XML-RPC  │  │   REST     │  │  Webhook   │    │   │
│  │  │    ORM     │  │   Bridge   │  │   API      │  │  Handler   │    │   │
│  │  └──────┬─────┘  └──────┬─────┘  └──────┬─────┘  └──────┬─────┘    │   │
│  └─────────┼───────────────┼───────────────┼───────────────┼──────────┘   │
│            │               │               │               │               │
│            └───────────────┴───────┬───────┴───────────────┘               │
│                                    │                                        │
│  ┌─────────────────────────────────┼─────────────────────────────────────┐ │
│  │                    ERP Module Integration Matrix                     │ │
│  │                                                                      │ │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐      │ │
│  │  │  Sales Module   │  │Inventory Module │  │Accounting Module│      │ │
│  │  │                 │  │                 │  │                 │      │ │
│  │  │ • Customer      │  │ • Stock Levels  │  │ • Credit Limits │      │ │
│  │  │   Master        │  │ • Reservations  │  │ • Invoicing     │      │ │
│  │  │ • Quotations    │  │ • Warehouses    │  │ • Payments      │      │ │
│  │  │ • Sales Orders  │  │ • Movements     │  │ • Receivables   │      │ │
│  │  │ • Pricing Lists │  │ • Reordering    │  │ • Aging         │      │ │
│  │  └─────────────────┘  └─────────────────┘  └─────────────────┘      │ │
│  │                                                                      │ │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐      │ │
│  │  │ Purchase Module │  │   CRM Module    │  │  Website Module │      │ │
│  │  │                 │  │                 │  │                 │      │ │
│  │  │ • Vendors       │  │ • Partners      │  │ • E-commerce    │      │ │
│  │  │ • RFQs          │  │ • Activities    │  │ • Products      │      │ │
│  │  │ • POs           │  │ • Pipeline      │  │ • Categories    │      │ │
│  │  │ • Receipts      │  │ • Communications│  │ • Promotions    │      │ │
│  │  └─────────────────┘  └─────────────────┘  └─────────────────┘      │ │
│  └─────────────────────────────────────────────────────────────────────┘ │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### 2.3.2 Data Synchronization Strategy

| Integration Point | Direction | Frequency | Method | Conflict Resolution |
|-------------------|-----------|-----------|--------|---------------------|
| Product Master | ERP → Portal | Real-time | Webhook | ERP wins |
| Stock Levels | ERP → Portal | 5 minutes | Polling | ERP wins |
| Customer Master | Bi-directional | Real-time | Webhook | Timestamp wins |
| Credit Limits | ERP → Portal | 1 minute | Polling | ERP wins |
| Sales Orders | Portal → ERP | Real-time | API | Portal wins (draft) |
| Order Status | ERP → Portal | Real-time | Webhook | ERP wins |
| Invoices | ERP → Portal | 15 minutes | Polling | ERP wins |
| Payments | ERP → Portal | 15 minutes | Polling | ERP wins |

#### 2.3.3 Integration Implementation Details

**Product Catalog Integration**
```python
# Odoo Integration Model for Product Sync
class B2BProductSync(models.Model):
    """
    Handles synchronization of products between ERP and B2B Portal.
    Implements the Outbox pattern for reliable message delivery.
    """
    _name = 'b2b.product.sync'
    _description = 'B2B Product Synchronization'
    
    product_id = fields.Many2one('product.product', required=True)
    sync_status = fields.Selection([
        ('pending', 'Pending'),
        ('syncing', 'Syncing'),
        ('synced', 'Synced'),
        ('failed', 'Failed')
    ], default='pending')
    last_sync = fields.Datetime()
    sync_attempts = fields.Integer(default=0)
    error_message = fields.Text()
    
    def sync_to_portal(self):
        """
        Synchronize product data to B2B Portal via webhook.
        Implements retry logic with exponential backoff.
        """
        for record in self:
            try:
                record.sync_status = 'syncing'
                product_data = record._prepare_product_data()
                
                response = requests.post(
                    f"{config.PORTAL_URL}/api/v1/products/sync",
                    json=product_data,
                    headers={'Authorization': f'Bearer {config.PORTAL_API_KEY}'},
                    timeout=30
                )
                response.raise_for_status()
                
                record.write({
                    'sync_status': 'synced',
                    'last_sync': fields.Datetime.now(),
                    'sync_attempts': 0,
                    'error_message': False
                })
            except Exception as e:
                record._handle_sync_error(e)
    
    def _prepare_product_data(self):
        """Prepare normalized product data for portal consumption."""
        self.ensure_one()
        product = self.product_id
        return {
            'erp_id': product.id,
            'name': product.name,
            'default_code': product.default_code,
            'barcode': product.barcode,
            'list_price': product.list_price,
            'standard_price': product.standard_price,
            'qty_available': product.qty_available,
            'virtual_available': product.virtual_available,
            'categ_id': product.categ_id.id,
            'uom_id': product.uom_id.id,
            'image_1920': product.image_1920,
            'sale_ok': product.sale_ok,
            'b2b_available': product.b2b_available,
            'b2b_tier_ids': [(6, 0, product.b2b_tier_ids.ids)],
            'sync_timestamp': fields.Datetime.now().isoformat()
        }
```

### 2.4 Data Flow Diagrams

#### 2.4.1 Order Submission Data Flow

```
┌─────────┐     ┌─────────┐     ┌─────────┐     ┌─────────┐     ┌─────────┐
│  User   │     │  React  │     │  API    │     │  Order  │     │   ERP   │
│ Browser │     │   App   │     │ Gateway │     │ Service │     │  Odoo   │
└────┬────┘     └────┬────┘     └────┬────┘     └────┬────┘     └────┬────┘
     │               │               │               │               │
     │  1. Submit    │               │               │               │
     │──────────────>│               │               │               │
     │               │               │               │               │
     │               │  2. POST /orders              │               │
     │               │──────────────>│               │               │
     │               │               │               │               │
     │               │               │  3. Validate &│               │
     │               │               │    Forward    │               │
     │               │               │──────────────>│               │
     │               │               │               │               │
     │               │               │               │  4. Validate  │
     │               │               │               │  Credit/Stock │
     │               │               │               │──────────────>│
     │               │               │               │               │
     │               │               │               │  5. Validation│
     │               │               │               │    Response   │
     │               │               │               │<──────────────│
     │               │               │               │               │
     │               │               │               │  6. Create    │
     │               │               │               │  Sales Order  │
     │               │               │               │──────────────>│
     │               │               │               │               │
     │               │               │               │  7. Order     │
     │               │               │               │  Reference    │
     │               │               │               │<──────────────│
     │               │               │               │               │
     │               │               │  8. Success   │               │
     │               │               │<──────────────│               │
     │               │               │               │               │
     │               │  9. 201       │               │               │
     │               │<──────────────│               │               │
     │               │               │               │               │
     │  10. Show     │               │               │               │
     │  Confirmation │               │               │               │
     │<──────────────│               │               │               │
     │               │               │               │               │
     │               │               │               │  11. Trigger  │
     │               │               │               │  Webhooks     │
     │               │               │               │──────────────>│
     │               │               │               │               │
     │               │               │               │  12. Process  │
     │               │               │               │  Workflow     │
     │               │               │               │<──────────────│
     │               │               │               │               │
```

#### 2.4.2 CSV Import Data Flow

```
┌─────────┐     ┌─────────┐     ┌─────────┐     ┌─────────┐     ┌─────────┐
│  User   │     │  React  │     │   API   │     │  Celery │     │  Order  │
│         │     │   App   │     │         │     │  Worker │     │ Service │
└────┬────┘     └────┬────┘     └────┬────┘     └────┬────┘     └────┬────┘
     │               │               │               │               │
     │  1. Select    │               │               │               │
     │  CSV File     │               │               │               │
     │──────────────>│               │               │               │
     │               │               │               │               │
     │               │  2. Upload    │               │               │
     │               │  /import/csv  │               │               │
     │               │──────────────>│               │               │
     │               │               │               │               │
     │               │               │  3. Store to  │               │
     │               │               │  MinIO        │               │
     │               │               │  Validate     │               │
     │               │               │  Headers      │               │
     │               │               │               │               │
     │               │               │  4. Queue     │               │
     │               │               │  Import Job   │               │
     │               │               │──────────────>│               │
     │               │               │               │               │
     │               │  5. Job ID    │               │               │
     │               │<──────────────│               │               │
     │               │               │               │               │
     │  6. Show      │               │               │               │
     │  Progress UI  │               │               │               │
     │<──────────────│               │               │               │
     │               │               │               │               │
     │               │               │               │  7. Parse CSV │
     │               │               │               │  Validate Rows│
     │               │               │               │               │
     │               │               │               │  8. Create    │
     │               │               │               │  Order Lines  │
     │               │               │               │──────────────>│
     │               │               │               │               │
     │               │               │               │  9. Submit    │
     │               │               │               │  Order        │
     │               │               │               │<──────────────│
     │               │               │               │               │
     │               │               │               │  10. Update   │
     │               │               │  WebSocket    │  Progress     │
     │               │               │<──────────────│               │
     │               │               │               │               │
     │  11. Progress │               │               │               │
     │  Update       │               │               │               │
     │<──────────────│               │               │               │
     │               │               │               │               │
```

---

## 3. Detailed Daily Task Allocation

### 3.1 Resource Overview

| Developer | Role | Primary Expertise | Allocation |
|-----------|------|-------------------|------------|
| Dev 1 (Lead) | Lead Developer - Backend/Architecture | Python, Odoo, System Design | 100% |
| Dev 2 | Backend Developer - Odoo/Python | Odoo Framework, PostgreSQL | 100% |
| Dev 3 | Frontend Developer - React/Flutter | React, TypeScript, UI/UX | 100% |

### 3.2 Day 301 - Project Kickoff & Architecture Setup

#### Developer 1 (Lead Dev - Backend/Architecture)

**Morning Tasks (4 hours):**

1. **Milestone Kickoff Meeting (1 hour)**
   - Conduct detailed briefing with all team members
   - Review Phase 3 achievements and Phase 4 objectives
   - Discuss technical approach and architectural decisions
   - Establish coding standards and review processes
   - Set up communication channels and escalation procedures

2. **Development Environment Setup (2 hours)**
   - Provision development servers for Milestone 31
   - Configure Docker Compose environment with all services
   - Set up PostgreSQL 16 with proper extensions (pg_trgm, unaccent)
   - Configure Redis 7 with persistence settings
   - Install and configure Elasticsearch 8 with custom analyzers
   - Set up MinIO for object storage
   - Configure Nginx as reverse proxy with SSL certificates
   - Create environment-specific configuration files

3. **Repository Structure Setup (1 hour)**
   - Create feature branch: `feature/milestone-31-b2b-marketplace`
   - Set up directory structure for new modules:
     ```
     smart_dairy_b2b_marketplace/
     ├── __init__.py
     ├── __manifest__.py
     ├── models/
     ├── controllers/
     ├── services/
     ├── utils/
     ├── data/
     ├── demo/
     ├── views/
     ├── static/
     ├── tests/
     └── security/
     ```
   - Initialize FastAPI service structure for microservices
   - Configure pre-commit hooks (black, flake8, mypy)

**Afternoon Tasks (4 hours):**

4. **Database Schema Design (2 hours)**
   - Design comprehensive ER diagram for B2B marketplace
   - Define table structures for:
     - b2b_catalog_product
     - b2b_tier_pricing
     - b2b_partner_tier
     - b2b_cart
     - b2b_cart_line
     - b2b_order_import
     - b2b_quick_order_template
   - Define indexes for performance optimization
   - Create migration scripts
   - Document schema decisions

5. **API Architecture Design (2 hours)**
   - Design REST API endpoint structure
   - Define OpenAPI 3.0 specifications
   - Plan authentication and authorization flows
   - Design rate limiting strategy
   - Create API versioning strategy
   - Document error handling patterns

**Deliverables for Day 301:**
- Development environment fully operational
- Git repository with proper structure
- Database schema design document
- API architecture specification
- Team onboarding completed

---

#### Developer 2 (Backend Dev - Odoo/Python)

**Morning Tasks (4 hours):**

1. **Odoo Module Initialization (2 hours)**
   - Create new Odoo addon: `smart_dairy_b2b_marketplace`
   - Define module manifest with dependencies:
     ```python
     {
         'name': 'Smart Dairy B2B Marketplace',
         'version': '1.0.0',
         'depends': [
             'base',
             'product',
             'sale',
             'stock',
             'account',
             'website',
             'portal',
             'smart_dairy_b2b_portal',
         ],
         'data': [
             'security/ir.model.access.csv',
             'data/b2b_partner_tier_data.xml',
             'views/b2b_product_views.xml',
             'views/b2b_cart_views.xml',
             'views/b2b_order_views.xml',
         ],
     }
     ```
   - Set up security access control files
   - Create initial model stubs

2. **Core Model Development - B2B Product (2 hours)**
   ```python
   class B2BProduct(models.Model):
       _name = 'b2b.catalog.product'
       _description = 'B2B Catalog Product'
       _inherit = ['product.product', 'portal.mixin']
       
       b2b_available = fields.Boolean(
           string='Available in B2B',
           default=False,
           index=True,
           help='Whether this product is available in the B2B marketplace'
       )
       
       b2b_description = fields.Html(
           string='B2B Description',
           sanitize=True,
           help='Product description specifically for B2B partners'
       )
       
       b2b_tier_ids = fields.Many2many(
           'b2b.partner.tier',
           string='Available for Tiers',
           help='Partner tiers that can view and purchase this product'
       )
       
       min_order_qty = fields.Float(
           string='Minimum Order Quantity',
           default=1.0,
           help='Minimum quantity required for B2B orders'
       )
       
       order_multiple = fields.Float(
           string='Order Multiple',
           default=1.0,
           help='Quantity must be ordered in multiples of this value'
       )
       
       lead_time_days = fields.Integer(
           string='Lead Time (Days)',
           help='Expected delivery lead time for B2B orders'
       )
       
       @api.constrains('min_order_qty', 'order_multiple')
       def _check_order_quantities(self):
           for product in self:
               if product.min_order_qty <= 0:
                   raise ValidationError(_('Minimum order quantity must be positive'))
               if product.order_multiple <= 0:
                   raise ValidationError(_('Order multiple must be positive'))
   ```

**Afternoon Tasks (4 hours):**

3. **Partner Tier Model Development (2 hours)**
   ```python
   class B2BPartnerTier(models.Model):
       _name = 'b2b.partner.tier'
       _description = 'B2B Partner Tier'
       _order = 'sequence, id'
       
       name = fields.Char(string='Tier Name', required=True, translate=True)
       code = fields.Char(string='Code', required=True, index=True)
       sequence = fields.Integer(string='Sequence', default=10)
       
       # Visual representation
       color = fields.Integer(string='Color')
       icon = fields.Char(string='Icon Class')
       
       # Discount structure
       discount_type = fields.Selection([
           ('percentage', 'Percentage Discount'),
           ('fixed', 'Fixed Amount'),
           ('tier_price', 'Tier-Based Pricing'),
       ], string='Discount Type', default='percentage')
       
       discount_percentage = fields.Float(
           string='Discount %',
           help='Percentage discount applied to standard price'
       )
       
       # Minimum requirements for tier
       min_annual_purchase = fields.Monetary(
           string='Minimum Annual Purchase',
           currency_field='currency_id'
       )
       
       min_order_count = fields.Integer(
           string='Minimum Order Count',
           help='Minimum number of orders to maintain tier'
       )
       
       currency_id = fields.Many2one(
           'res.currency',
           string='Currency',
           default=lambda self: self.env.company.currency_id
       )
       
       # Associated partners
       partner_ids = fields.One2many(
           'res.partner',
           'b2b_tier_id',
           string='Partners'
       )
       
       # Available products
       product_ids = fields.Many2many(
           'product.product',
           'b2b_tier_product_rel',
           'tier_id',
           'product_id',
           string='Available Products'
       )
       
       active = fields.Boolean(default=True)
       
       _sql_constraints = [
           ('code_uniq', 'unique(code)', 'Tier code must be unique!'),
       ]
   ```

4. **Integration with Partner Model (2 hours)**
   - Extend `res.partner` model with B2B tier fields
   - Implement partner tier assignment logic
   - Create automated tier upgrade/downgrade mechanisms
   - Add credit limit integration fields

**Deliverables for Day 301:**
- Odoo module structure created
- B2B Product model implemented with all fields
- Partner Tier model implemented
- Partner model extended with B2B fields
- Initial security rules defined

---

#### Developer 3 (Frontend Dev - React/Flutter)

**Morning Tasks (4 hours):**

1. **Frontend Project Setup (2 hours)**
   - Create new React project with TypeScript:
     ```bash
     npx create-react-app smart-dairy-b2b-frontend --template typescript
     ```
   - Install core dependencies:
     ```bash
     npm install @mui/material @emotion/react @emotion/styled
     npm install @mui/icons-material @mui/x-data-grid
     npm install react-router-dom axios react-query zustand
     npm install react-hook-form @hookform/resolvers yup
     npm install react-dropzone papaparse @types/papaparse
     npm install react-virtuoso
     ```
   - Configure project structure:
     ```
     src/
     ├── components/
     │   ├── common/
     │   ├── catalog/
     │   ├── cart/
     │   ├── quick-order/
     │   └── layout/
     ├── hooks/
     ├── services/
     ├── stores/
     ├── types/
     ├── utils/
     ├── constants/
     └── styles/
     ```
   - Set up ESLint and Prettier configuration
   - Configure absolute imports

2. **Base UI Components Setup (2 hours)**
   - Create theme configuration:
     ```typescript
     // src/styles/theme.ts
     import { createTheme } from '@mui/material/styles';
     
     export const b2bTheme = createTheme({
       palette: {
         primary: {
           main: '#1976d2',
           light: '#42a5f5',
           dark: '#1565c0',
         },
         secondary: {
           main: '#dc004e',
         },
         background: {
           default: '#f5f5f5',
           paper: '#ffffff',
         },
       },
       typography: {
         fontFamily: '"Roboto", "Helvetica", "Arial", sans-serif',
         h1: { fontSize: '2rem', fontWeight: 500 },
         h2: { fontSize: '1.75rem', fontWeight: 500 },
         h3: { fontSize: '1.5rem', fontWeight: 500 },
       },
       components: {
         MuiButton: {
           styleOverrides: {
             root: {
               textTransform: 'none',
               borderRadius: 8,
             },
           },
         },
         MuiCard: {
           styleOverrides: {
             root: {
               borderRadius: 12,
               boxShadow: '0 2px 8px rgba(0,0,0,0.1)',
             },
           },
         },
       },
     });
     ```
   - Create common components: Button, Card, Input, Select
   - Set up responsive layout components
   - Implement loading states and skeleton screens

**Afternoon Tasks (4 hours):**

3. **API Service Layer Setup (2 hours)**
   ```typescript
   // src/services/api.ts
   import axios, { AxiosInstance, AxiosError } from 'axios';
   
   const API_BASE_URL = process.env.REACT_APP_API_URL || 'http://localhost:8069';
   
   class ApiService {
     private client: AxiosInstance;
     
     constructor() {
       this.client = axios.create({
         baseURL: `${API_BASE_URL}/api/v1`,
         timeout: 30000,
         headers: {
           'Content-Type': 'application/json',
         },
       });
       
       this.setupInterceptors();
     }
     
     private setupInterceptors() {
       // Request interceptor for auth token
       this.client.interceptors.request.use(
         (config) => {
           const token = localStorage.getItem('auth_token');
           if (token) {
             config.headers.Authorization = `Bearer ${token}`;
           }
           return config;
         },
         (error) => Promise.reject(error)
       );
       
       // Response interceptor for error handling
       this.client.interceptors.response.use(
         (response) => response,
         (error: AxiosError) => {
           if (error.response?.status === 401) {
             // Redirect to login
             window.location.href = '/login';
           }
           return Promise.reject(error);
         }
       );
     }
     
     // Catalog API
     async getProducts(params: ProductQueryParams): Promise<PaginatedResponse<Product>> {
       const response = await this.client.get('/catalog/products', { params });
       return response.data;
     }
     
     async getProductById(id: number): Promise<Product> {
       const response = await this.client.get(`/catalog/products/${id}`);
       return response.data;
     }
     
     // Cart API
     async getCart(): Promise<Cart> {
       const response = await this.client.get('/cart');
       return response.data;
     }
     
     async addToCart(item: CartItemInput): Promise<Cart> {
       const response = await this.client.post('/cart/items', item);
       return response.data;
     }
     
     async updateCartItem(itemId: number, quantity: number): Promise<Cart> {
       const response = await this.client.patch(`/cart/items/${itemId}`, { quantity });
       return response.data;
     }
     
     async removeCartItem(itemId: number): Promise<void> {
       await this.client.delete(`/cart/items/${itemId}`);
     }
     
     // Order API
     async createOrder(orderData: OrderInput): Promise<Order> {
       const response = await this.client.post('/orders', orderData);
       return response.data;
     }
     
     async quickOrder(items: QuickOrderItem[]): Promise<Order> {
       const response = await this.client.post('/orders/quick', { items });
       return response.data;
     }
     
     async uploadCsv(file: File): Promise<ImportJob> {
       const formData = new FormData();
       formData.append('file', file);
       
       const response = await this.client.post('/orders/import/csv', formData, {
         headers: { 'Content-Type': 'multipart/form-data' },
       });
       return response.data;
     }
   }
   
   export const apiService = new ApiService();
   ```

4. **State Management Setup (2 hours)**
   ```typescript
   // src/stores/cartStore.ts
   import { create } from 'zustand';
   import { devtools, persist } from 'zustand/middleware';
   import { apiService } from '../services/api';
   
   interface CartState {
     cart: Cart | null;
     isLoading: boolean;
     error: string | null;
     
     // Actions
     fetchCart: () => Promise<void>;
     addItem: (productId: number, quantity: number) => Promise<void>;
     updateItem: (itemId: number, quantity: number) => Promise<void>;
     removeItem: (itemId: number) => Promise<void>;
     clearCart: () => Promise<void>;
     clearError: () => void;
   }
   
   export const useCartStore = create<CartState>()(
     devtools(
       persist(
         (set, get) => ({
           cart: null,
           isLoading: false,
           error: null,
           
           fetchCart: async () => {
             set({ isLoading: true, error: null });
             try {
               const cart = await apiService.getCart();
               set({ cart, isLoading: false });
             } catch (error) {
               set({ error: (error as Error).message, isLoading: false });
             }
           },
           
           addItem: async (productId, quantity) => {
             set({ isLoading: true, error: null });
             try {
               const cart = await apiService.addToCart({ product_id: productId, quantity });
               set({ cart, isLoading: false });
             } catch (error) {
               set({ error: (error as Error).message, isLoading: false });
             }
           },
           
           updateItem: async (itemId, quantity) => {
             set({ isLoading: true, error: null });
             try {
               const cart = await apiService.updateCartItem(itemId, quantity);
               set({ cart, isLoading: false });
             } catch (error) {
               set({ error: (error as Error).message, isLoading: false });
             }
           },
           
           removeItem: async (itemId) => {
             set({ isLoading: true, error: null });
             try {
               await apiService.removeCartItem(itemId);
               await get().fetchCart();
             } catch (error) {
               set({ error: (error as Error).message, isLoading: false });
             }
           },
           
           clearCart: async () => {
             set({ cart: null });
           },
           
           clearError: () => set({ error: null }),
         }),
         {
           name: 'cart-storage',
           partialize: (state) => ({ cart: state.cart }),
         }
       )
     )
   );
   ```

**Deliverables for Day 301:**
- React project with TypeScript configured
- All dependencies installed
- Base UI components created
- API service layer implemented
- State management (Zustand) configured
- Theme system established

---

### 3.3 Day 302 - Tier Pricing System Development

#### Developer 1 (Lead Dev - Backend/Architecture)

**Morning Tasks (4 hours):**

1. **Pricing Engine Architecture (2 hours)**
   - Design pricing calculation engine
   - Define pricing rules and priority system
   - Create pricing strategy pattern implementation
   ```python
   # Pricing Engine Core Architecture
   from abc import ABC, abstractmethod
   from typing import List, Optional, Dict
   from dataclasses import dataclass
   from decimal import Decimal
   
   @dataclass
   class PricingContext:
       """Context object containing all information needed for pricing."""
       partner_id: int
       product_id: int
       quantity: float
       uom_id: int
       date: datetime
       currency_id: int
       pricelist_id: Optional[int] = None
       
   @dataclass
   class PricingResult:
       """Result of pricing calculation."""
       price: Decimal
       original_price: Decimal
       discount_amount: Decimal
       discount_percentage: float
       pricing_rule_applied: str
       currency_id: int
       valid_from: datetime
       valid_until: Optional[datetime]
       
   class PricingStrategy(ABC):
       """Abstract base class for pricing strategies."""
       
       @abstractmethod
       def calculate_price(self, context: PricingContext) -> Optional[PricingResult]:
           """Calculate price for given context. Returns None if not applicable."""
           pass
       
       @abstractmethod
       def get_priority(self) -> int:
           """Return priority (lower = higher priority)."""
           pass
   ```

2. **Tier Pricing Strategy Implementation (2 hours)**
   ```python
   class TierPricingStrategy(PricingStrategy):
       """
       Pricing strategy based on partner tier.
       Applies tier-specific discounts and pricing rules.
       """
       
       def __init__(self, env):
           self.env = env
           self.priority = 100
       
       def get_priority(self) -> int:
           return self.priority
       
       def calculate_price(self, context: PricingContext) -> Optional[PricingResult]:
           # Get partner's tier
           partner = self.env['res.partner'].browse(context.partner_id)
           tier = partner.b2b_tier_id
           
           if not tier:
               return None
           
           # Get product's base price
           product = self.env['product.product'].browse(context.product_id)
           base_price = Decimal(str(product.list_price))
           
           # Check if tier-specific pricing exists for this product
           tier_price = self.env['b2b.tier.pricing'].search([
               ('tier_id', '=', tier.id),
               ('product_id', '=', context.product_id),
               ('date_start', '<=', context.date),
               '|', ('date_end', '=', False),
                    ('date_end', '>=', context.date),
           ], limit=1, order='priority desc')
           
           if tier_price:
               return self._apply_tier_price(tier_price, base_price, context)
           
           # Apply tier discount to base price
           if tier.discount_type == 'percentage':
               discount_pct = tier.discount_percentage
               discounted_price = base_price * (1 - Decimal(str(discount_pct)) / 100)
               
               return PricingResult(
                   price=discounted_price.quantize(Decimal('0.01')),
                   original_price=base_price,
                   discount_amount=base_price - discounted_price,
                   discount_percentage=discount_pct,
                   pricing_rule_applied=f'tier_discount_{tier.code}',
                   currency_id=context.currency_id,
                   valid_from=fields.Datetime.now(),
                   valid_until=None
               )
           
           return None
       
       def _apply_tier_price(self, tier_price, base_price, context):
           """Apply tier-specific pricing rule."""
           if tier_price.price_type == 'fixed':
               final_price = Decimal(str(tier_price.fixed_price))
           elif tier_price.price_type == 'discount_percentage':
               discount = Decimal(str(tier_price.discount_percentage))
               final_price = base_price * (1 - discount / 100)
           elif tier_price.price_type == 'discount_amount':
               discount = Decimal(str(tier_price.discount_amount))
               final_price = max(Decimal('0'), base_price - discount)
           else:
               final_price = base_price
           
           discount_amount = base_price - final_price
           discount_pct = float((discount_amount / base_price) * 100) if base_price > 0 else 0
           
           return PricingResult(
               price=final_price.quantize(Decimal('0.01')),
               original_price=base_price,
               discount_amount=discount_amount,
               discount_percentage=discount_pct,
               pricing_rule_applied=f'tier_price_{tier_price.id}',
               currency_id=context.currency_id,
               valid_from=tier_price.date_start,
               valid_until=tier_price.date_end
           )
   ```

**Afternoon Tasks (4 hours):**

3. **Volume Pricing Strategy (2 hours)**
   ```python
   class VolumePricingStrategy(PricingStrategy):
       """
       Pricing strategy based on quantity breaks.
       Higher quantities receive better pricing.
       """
       
       def __init__(self, env):
           self.env = env
           self.priority = 200
       
       def get_priority(self) -> int:
           return self.priority
       
       def calculate_price(self, context: PricingContext) -> Optional[PricingResult]:
           # Find applicable volume pricing rule
           volume_price = self.env['b2b.volume.pricing'].search([
               ('product_id', '=', context.product_id),
               ('min_quantity', '<=', context.quantity),
               ('date_start', '<=', context.date),
               '|', ('date_end', '=', False),
                    ('date_end', '>=', context.date),
           ], order='min_quantity desc', limit=1)
           
           if not volume_price:
               return None
           
           product = self.env['product.product'].browse(context.product_id)
           base_price = Decimal(str(product.list_price))
           
           if volume_price.price_type == 'fixed':
               final_price = Decimal(str(volume_price.fixed_price))
           elif volume_price.price_type == 'percentage':
               final_price = base_price * (1 - Decimal(str(volume_price.discount_percentage)) / 100)
           else:
               return None
           
           discount_amount = base_price - final_price
           
           return PricingResult(
               price=final_price.quantize(Decimal('0.01')),
               original_price=base_price,
               discount_amount=discount_amount,
               discount_percentage=float((discount_amount / base_price) * 100) if base_price > 0 else 0,
               pricing_rule_applied=f'volume_price_{volume_price.id}',
               currency_id=context.currency_id,
               valid_from=volume_price.date_start,
               valid_until=volume_price.date_end
           )
   ```

4. **Pricing Engine Orchestrator (2 hours)**
   ```python
   class B2BPricingEngine:
       """
       Central pricing engine that orchestrates multiple pricing strategies.
       Implements chain of responsibility pattern for pricing calculation.
       """
       
       def __init__(self, env):
           self.env = env
           self.strategies: List[PricingStrategy] = []
           self._load_strategies()
       
       def _load_strategies(self):
           """Load all available pricing strategies in priority order."""
           self.strategies = [
               ContractPricingStrategy(self.env),      # Priority: 50
               TierPricingStrategy(self.env),          # Priority: 100
               VolumePricingStrategy(self.env),        # Priority: 200
               PromotionalPricingStrategy(self.env),   # Priority: 300
               StandardPricingStrategy(self.env),      # Priority: 1000
           ]
           self.strategies.sort(key=lambda s: s.get_priority())
       
       def get_price(self, context: PricingContext) -> PricingResult:
           """
           Calculate the best price by applying all strategies in priority order.
           Returns the first applicable pricing result.
           """
           # Validate context
           self._validate_context(context)
           
           # Try each strategy in priority order
           for strategy in self.strategies:
               try:
                   result = strategy.calculate_price(context)
                   if result:
                       _logger.info(
                           f"Pricing strategy '{strategy.__class__.__name__}' applied. "
                           f"Price: {result.price}, Rule: {result.pricing_rule_applied}"
                       )
                       return result
               except Exception as e:
                   _logger.error(
                       f"Error in pricing strategy {strategy.__class__.__name__}: {e}"
                   )
                   continue
           
           # Fallback: return base price
           return self._get_fallback_price(context)
       
       def get_price_breaks(self, product_id: int, partner_id: int) -> List[Dict]:
           """
           Get all price breaks for a product to display in the catalog.
           Shows quantity-based pricing tiers.
           """
           product = self.env['product.product'].browse(product_id)
           partner = self.env['res.partner'].browse(partner_id)
           
           breaks = []
           
           # Get volume pricing breaks
           volume_prices = self.env['b2b.volume.pricing'].search([
               ('product_id', '=', product_id),
               '|', ('date_end', '=', False),
                    ('date_end', '>=', fields.Date.today()),
           ], order='min_quantity')
           
           for vp in volume_prices:
               context = PricingContext(
                   partner_id=partner_id,
                   product_id=product_id,
                   quantity=vp.min_quantity,
                   uom_id=product.uom_id.id,
                   date=datetime.now(),
                   currency_id=product.currency_id.id
               )
               result = self.get_price(context)
               
               breaks.append({
                   'min_quantity': vp.min_quantity,
                   'price': float(result.price),
                   'currency': product.currency_id.name,
                   'savings_percentage': result.discount_percentage,
               })
           
           return breaks
       
       def _validate_context(self, context: PricingContext):
           """Validate pricing context."""
           if context.quantity <= 0:
               raise ValueError("Quantity must be positive")
           if not self.env['product.product'].browse(context.product_id).exists():
               raise ValueError("Product does not exist")
           if not self.env['res.partner'].browse(context.partner_id).exists():
               raise ValueError("Partner does not exist")
       
       def _get_fallback_price(self, context: PricingContext) -> PricingResult:
           """Get fallback price when no strategy applies."""
           product = self.env['product.product'].browse(context.product_id)
           base_price = Decimal(str(product.list_price))
           
           return PricingResult(
               price=base_price,
               original_price=base_price,
               discount_amount=Decimal('0'),
               discount_percentage=0.0,
               pricing_rule_applied='fallback_base_price',
               currency_id=context.currency_id,
               valid_from=fields.Datetime.now(),
               valid_until=None
           )
   ```

**Deliverables for Day 302:**
- Pricing engine architecture implemented
- Tier pricing strategy completed
- Volume pricing strategy completed
- Pricing orchestrator with chain of responsibility pattern
- Unit tests for pricing calculations

---

#### Developer 2 (Backend Dev - Odoo/Python)

**Morning Tasks (4 hours):**

1. **Tier Pricing Models (2 hours)**
   ```python
   class B2BTierPricing(models.Model):
       """
       Tier-specific pricing rules for B2B products.
       Allows custom pricing per tier per product.
       """
       _name = 'b2b.tier.pricing'
       _description = 'B2B Tier Pricing'
       _order = 'tier_id, product_id, priority desc'
       
       name = fields.Char(string='Description', translate=True)
       
       tier_id = fields.Many2one(
           'b2b.partner.tier',
           string='Partner Tier',
           required=True,
           ondelete='cascade',
           index=True
       )
       
       product_id = fields.Many2one(
           'product.product',
           string='Product',
           required=True,
           ondelete='cascade',
           index=True,
           domain=[('b2b_available', '=', True)]
       )
       
       product_tmpl_id = fields.Many2one(
           'product.template',
           related='product_id.product_tmpl_id',
           store=True,
           index=True
       )
       
       price_type = fields.Selection([
           ('fixed', 'Fixed Price'),
           ('discount_percentage', 'Discount Percentage'),
           ('discount_amount', 'Discount Amount'),
       ], string='Price Type', required=True, default='discount_percentage')
       
       fixed_price = fields.Float(
           string='Fixed Price',
           help='Exact price for this tier'
       )
       
       discount_percentage = fields.Float(
           string='Discount %',
           help='Percentage discount from list price'
       )
       
       discount_amount = fields.Monetary(
           string='Discount Amount',
           help='Fixed amount to subtract from list price'
       )
       
       currency_id = fields.Many2one(
           'res.currency',
           string='Currency',
           related='product_id.currency_id',
           store=True
       )
       
       min_quantity = fields.Float(
           string='Minimum Quantity',
           default=1.0,
           help='Minimum quantity to apply this pricing'
       )
       
       date_start = fields.Date(
           string='Start Date',
           default=fields.Date.today,
           required=True
       )
       
       date_end = fields.Date(
           string='End Date',
           help='Leave empty for no end date'
       )
       
       priority = fields.Integer(
           string='Priority',
           default=10,
           help='Higher priority rules are applied first'
       )
       
       active = fields.Boolean(default=True)
       
       company_id = fields.Many2one(
           'res.company',
           string='Company',
           default=lambda self: self.env.company
       )
       
       computed_price = fields.Float(
           string='Computed Price',
           compute='_compute_computed_price',
           store=False
       )
       
       @api.constrains('date_start', 'date_end')
       def _check_dates(self):
           for record in self:
               if record.date_end and record.date_start > record.date_end:
                   raise ValidationError(_('End date must be after start date'))
       
       @api.constrains('fixed_price', 'discount_percentage', 'discount_amount', 'price_type')
       def _check_price_values(self):
           for record in self:
               if record.price_type == 'fixed' and record.fixed_price <= 0:
                   raise ValidationError(_('Fixed price must be positive'))
               if record.price_type == 'discount_percentage':
                   if not 0 < record.discount_percentage <= 100:
                       raise ValidationError(_('Discount percentage must be between 0 and 100'))
               if record.price_type == 'discount_amount' and record.discount_amount <= 0:
                   raise ValidationError(_('Discount amount must be positive'))
       
       @api.depends('price_type', 'fixed_price', 'discount_percentage', 
                    'discount_amount', 'product_id.list_price')
       def _compute_computed_price(self):
           for record in self:
               base_price = record.product_id.list_price
               
               if record.price_type == 'fixed':
                   record.computed_price = record.fixed_price
               elif record.price_type == 'discount_percentage':
                   record.computed_price = base_price * (1 - record.discount_percentage / 100)
               elif record.price_type == 'discount_amount':
                   record.computed_price = max(0, base_price - record.discount_amount)
               else:
                   record.computed_price = base_price
       
       @api.model
       def get_price_for_partner(self, product_id, partner_id, quantity=1.0):
           """
           Get tier price for a specific partner and product.
           Returns tuple of (price, rule_id) or (None, None) if no rule applies.
           """
           partner = self.env['res.partner'].browse(partner_id)
           if not partner.b2b_tier_id:
               return None, None
           
           today = fields.Date.today()
           
           rule = self.search([
               ('tier_id', '=', partner.b2b_tier_id.id),
               ('product_id', '=', product_id),
               ('min_quantity', '<=', quantity),
               ('date_start', '<=', today),
               '|', ('date_end', '=', False),
                    ('date_end', '>=', today),
               ('active', '=', True),
           ], order='priority desc, min_quantity desc', limit=1)
           
           if rule:
               return rule.computed_price, rule.id
           return None, None
       
       _sql_constraints = [
           ('unique_tier_product', 
            'unique(tier_id, product_id, min_quantity, date_start, date_end)', 
            'A pricing rule already exists for this combination!'),
       ]
   ```

2. **Volume Pricing Models (2 hours)**
   ```python
   class B2BVolumePricing(models.Model):
       """
       Volume-based pricing rules for quantity breaks.
       Independent of tier - available to all B2B customers.
       """
       _name = 'b2b.volume.pricing'
       _description = 'B2B Volume Pricing'
       _order = 'product_id, min_quantity'
       
       name = fields.Char(string='Description', translate=True)
       
       product_id = fields.Many2one(
           'product.product',
           string='Product',
           required=True,
           ondelete='cascade',
           index=True,
           domain=[('b2b_available', '=', True)]
       )
       
       product_tmpl_id = fields.Many2one(
           'product.template',
           related='product_id.product_tmpl_id',
           store=True
       )
       
       min_quantity = fields.Float(
           string='Minimum Quantity',
           required=True,
           help='Minimum quantity to qualify for this price'
       )
       
       max_quantity = fields.Float(
           string='Maximum Quantity',
           help='Maximum quantity for this price range (exclusive)'
       )
       
       price_type = fields.Selection([
           ('fixed', 'Fixed Price per Unit'),
           ('percentage', 'Percentage Discount'),
       ], string='Price Type', required=True, default='percentage')
       
       fixed_price = fields.Float(
           string='Fixed Price',
           digits='Product Price',
           help='Fixed price per unit at this quantity'
       )
       
       discount_percentage = fields.Float(
           string='Discount %',
           help='Percentage discount from list price'
       )
       
       currency_id = fields.Many2one(
           'res.currency',
           string='Currency',
           related='product_id.currency_id',
           store=True
       )
       
       date_start = fields.Date(
           string='Start Date',
           default=fields.Date.today,
           required=True
       )
       
       date_end = fields.Date(string='End Date')
       
       active = fields.Boolean(default=True)
       
       company_id = fields.Many2one(
           'res.company',
           string='Company',
           default=lambda self: self.env.company
       )
       
       @api.constrains('min_quantity', 'max_quantity')
       def _check_quantities(self):
           for record in self:
               if record.min_quantity <= 0:
                   raise ValidationError(_('Minimum quantity must be positive'))
               if record.max_quantity and record.max_quantity <= record.min_quantity:
                   raise ValidationError(_('Maximum quantity must be greater than minimum'))
       
       @api.constrains('fixed_price', 'discount_percentage', 'price_type')
       def _check_price_values(self):
           for record in self:
               if record.price_type == 'fixed' and record.fixed_price < 0:
                   raise ValidationError(_('Fixed price cannot be negative'))
               if record.price_type == 'percentage':
                   if not 0 <= record.discount_percentage <= 100:
                       raise ValidationError(_('Discount percentage must be between 0 and 100'))
       
       @api.constrains('product_id', 'min_quantity', 'date_start')
       def _check_overlapping_ranges(self):
           """Ensure no overlapping quantity ranges for same product and period."""
           for record in self:
               overlapping = self.search([
                   ('id', '!=', record.id),
                   ('product_id', '=', record.product_id.id),
                   ('active', '=', True),
                   ('min_quantity', '<', record.max_quantity or float('inf')),
                   ('max_quantity', '>', record.min_quantity) if record.max_quantity else ('id', '!=', False),
               ])
               
               for other in overlapping:
                   # Check date overlap
                   if (not record.date_end or not other.date_end or 
                       record.date_start <= other.date_end or 
                       other.date_start <= record.date_end):
                       raise ValidationError(_(
                           'Overlapping quantity range with existing rule: %s'
                       ) % other.name)
       
       def get_price_for_quantity(self, quantity):
           """Calculate price for given quantity."""
           self.ensure_one()
           base_price = self.product_id.list_price
           
           if self.price_type == 'fixed':
               return self.fixed_price
           else:
               return base_price * (1 - self.discount_percentage / 100)
   ```

**Afternoon Tasks (4 hours):**

3. **Pricing API Endpoints (2 hours)**
   ```python
   # controllers/pricing_controller.py
   from odoo import http
   from odoo.http import request
   import json
   
   class B2BPricingController(http.Controller):
       """
       REST API endpoints for pricing operations.
       """
       
       @http.route('/api/v1/pricing/calculate', type='json', auth='user', methods=['POST'])
       def calculate_price(self, **kwargs):
           """
           Calculate price for a product.
           
           Request:
           {
               'product_id': int,
               'quantity': float (optional, default: 1),
               'uom_id': int (optional)
           }
           
           Response:
           {
               'success': bool,
               'data': {
                   'price': float,
                   'original_price': float,
                   'discount_amount': float,
                   'discount_percentage': float,
                   'pricing_rule': str,
                   'currency': str
               }
           }
           """
           try:
               data = json.loads(request.httprequest.data)
               product_id = data.get('product_id')
               quantity = data.get('quantity', 1.0)
               uom_id = data.get('uom_id')
               
               if not product_id:
                   return {'success': False, 'error': 'Product ID is required'}
               
               partner = request.env.user.partner_id
               product = request.env['product.product'].browse(product_id)
               
               if not product.exists():
                   return {'success': False, 'error': 'Product not found'}
               
               # Build pricing context
               from ..services.pricing_engine import PricingContext
               context = PricingContext(
                   partner_id=partner.id,
                   product_id=product_id,
                   quantity=quantity,
                   uom_id=uom_id or product.uom_id.id,
                   date=fields.Datetime.now(),
                   currency_id=product.currency_id.id
               )
               
               # Calculate price
               engine = request.env['b2b.pricing.engine']
               result = engine.get_price(context)
               
               return {
                   'success': True,
                   'data': {
                       'price': float(result.price),
                       'original_price': float(result.original_price),
                       'discount_amount': float(result.discount_amount),
                       'discount_percentage': result.discount_percentage,
                       'pricing_rule': result.pricing_rule_applied,
                       'currency': request.env['res.currency'].browse(result.currency_id).name,
                       'valid_until': result.valid_until.isoformat() if result.valid_until else None,
                   }
               }
               
           except Exception as e:
               return {'success': False, 'error': str(e)}
       
       @http.route('/api/v1/pricing/breaks/<int:product_id>', type='json', auth='user')
       def get_price_breaks(self, product_id, **kwargs):
           """
           Get price breaks for a product.
           Shows quantity-based pricing tiers.
           """
           try:
               product = request.env['product.product'].browse(product_id)
               if not product.exists():
                   return {'success': False, 'error': 'Product not found'}
               
               engine = request.env['b2b.pricing.engine']
               breaks = engine.get_price_breaks(product_id, request.env.user.partner_id.id)
               
               return {
                   'success': True,
                   'data': {
                       'product_id': product_id,
                       'product_name': product.name,
                       'base_price': product.list_price,
                       'currency': product.currency_id.name,
                       'price_breaks': breaks
                   }
               }
               
           except Exception as e:
               return {'success': False, 'error': str(e)}
       
       @http.route('/api/v1/pricing/bulk', type='json', auth='user', methods=['POST'])
       def bulk_calculate_prices(self, **kwargs):
           """
           Calculate prices for multiple products at once.
           Used for cart pricing and quick order.
           
           Request:
           {
               'items': [
                   {'product_id': int, 'quantity': float},
                   ...
               ]
           }
           """
           try:
               data = json.loads(request.httprequest.data)
               items = data.get('items', [])
               
               if not items:
                   return {'success': False, 'error': 'No items provided'}
               
               partner = request.env.user.partner_id
               engine = request.env['b2b.pricing.engine']
               results = []
               
               for item in items:
                   product_id = item.get('product_id')
                   quantity = item.get('quantity', 1.0)
                   
                   product = request.env['product.product'].browse(product_id)
                   if not product.exists():
                       results.append({
                           'product_id': product_id,
                           'error': 'Product not found'
                       })
                       continue
                   
                   context = PricingContext(
                       partner_id=partner.id,
                       product_id=product_id,
                       quantity=quantity,
                       uom_id=product.uom_id.id,
                       date=fields.Datetime.now(),
                       currency_id=product.currency_id.id
                   )
                   
                   result = engine.get_price(context)
                   results.append({
                       'product_id': product_id,
                       'product_name': product.name,
                       'quantity': quantity,
                       'price': float(result.price),
                       'subtotal': float(result.price * Decimal(str(quantity))),
                       'original_price': float(result.original_price),
                       'discount_percentage': result.discount_percentage,
                   })
               
               return {
                   'success': True,
                   'data': {
                       'items': results,
                       'total_items': len(results),
                       'currency': request.env.company.currency_id.name
                   }
               }
               
           except Exception as e:
               return {'success': False, 'error': str(e)}
   ```

4. **Pricing Cache Implementation (2 hours)**
   ```python
   class B2BPricingCache(models.AbstractModel):
       """
       Pricing cache manager using Redis for performance.
       Implements cache warming and invalidation strategies.
       """
       _name = 'b2b.pricing.cache'
       _description = 'B2B Pricing Cache Manager'
       
       CACHE_TTL = 3600  # 1 hour
       CACHE_PREFIX = 'b2b:pricing:'
       
       def _get_cache_key(self, context: PricingContext) -> str:
           """Generate cache key from pricing context."""
           key_parts = [
               self.CACHE_PREFIX,
               f'p:{context.product_id}',
               f'pt:{context.partner_id}',
               f'q:{context.quantity}',
               f'u:{context.uom_id}',
               f'c:{context.currency_id}',
               f'd:{context.date.strftime("%Y%m%d")}'
           ]
           return ':'.join(key_parts)
       
       def get_cached_price(self, context: PricingContext) -> Optional[PricingResult]:
           """Get price from cache if available."""
           cache_key = self._get_cache_key(context)
           
           try:
               redis_client = self._get_redis_client()
               cached_data = redis_client.get(cache_key)
               
               if cached_data:
                   data = json.loads(cached_data)
                   return PricingResult(**data)
               
           except Exception as e:
               _logger.warning(f"Cache read error: {e}")
           
           return None
       
       def cache_price(self, context: PricingContext, result: PricingResult):
           """Store price result in cache."""
           cache_key = self._get_cache_key(context)
           
           try:
               redis_client = self._get_redis_client()
               cache_data = json.dumps({
                   'price': str(result.price),
                   'original_price': str(result.original_price),
                   'discount_amount': str(result.discount_amount),
                   'discount_percentage': result.discount_percentage,
                   'pricing_rule_applied': result.pricing_rule_applied,
                   'currency_id': result.currency_id,
                   'valid_from': result.valid_from.isoformat(),
                   'valid_until': result.valid_until.isoformat() if result.valid_until else None,
               })
               
               redis_client.setex(cache_key, self.CACHE_TTL, cache_data)
               
           except Exception as e:
               _logger.warning(f"Cache write error: {e}")
       
       def invalidate_product_cache(self, product_id: int):
           """Invalidate all cached prices for a product."""
           try:
               redis_client = self._get_redis_client()
               pattern = f"{self.CACHE_PREFIX}p:{product_id}:*"
               
               # Find and delete matching keys
               cursor = 0
               while True:
                   cursor, keys = redis_client.scan(cursor, match=pattern, count=100)
                   if keys:
                       redis_client.delete(*keys)
                   if cursor == 0:
                       break
                       
           except Exception as e:
               _logger.warning(f"Cache invalidation error: {e}")
       
       def warm_cache_for_partner(self, partner_id: int):
           """
           Pre-populate cache for frequently accessed products.
           Called when partner logs in or tier changes.
           """
           partner = self.env['res.partner'].browse(partner_id)
           if not partner.b2b_tier_id:
               return
           
           # Get products available for this tier
           products = self.env['product.product'].search([
               ('b2b_available', '=', True),
               '|', ('b2b_tier_ids', '=', False),
                    ('b2b_tier_ids', 'in', partner.b2b_tier_id.ids),
           ], limit=100, order='b2b_popularity desc')
           
           engine = self.env['b2b.pricing.engine']
           
           for product in products:
               context = PricingContext(
                   partner_id=partner_id,
                   product_id=product.id,
                   quantity=1.0,
                   uom_id=product.uom_id.id,
                   date=fields.Datetime.now(),
                   currency_id=product.currency_id.id
               )
               
               result = engine.get_price(context)
               self.cache_price(context, result)
       
       def _get_redis_client(self):
           """Get Redis client instance."""
           import redis
           return redis.Redis(
               host=config.get('redis_host', 'localhost'),
               port=int(config.get('redis_port', 6379)),
               db=int(config.get('redis_db', 0)),
               decode_responses=True
           )
   ```

**Deliverables for Day 302:**
- Tier pricing model with all constraints
- Volume pricing model with overlap validation
- Pricing calculation API endpoints
- Redis-based pricing cache implementation
- Model unit tests

---

#### Developer 3 (Frontend Dev - React/Flutter)

**Morning Tasks (4 hours):**

1. **Tier Pricing Display Components (2 hours)**
   ```typescript
   // src/components/catalog/TierPriceDisplay.tsx
   import React from 'react';
   import { Box, Chip, Typography, Tooltip } from '@mui/material';
   import { Star, LocalOffer } from '@mui/icons-material';
   import { usePartnerStore } from '../../stores/partnerStore';
   
   interface TierPriceDisplayProps {
     basePrice: number;
     tierPrice: number;
     currency: string;
     tierName: string;
     tierColor?: string;
   }
   
   export const TierPriceDisplay: React.FC<TierPriceDisplayProps> = ({
     basePrice,
     tierPrice,
     currency,
     tierName,
     tierColor = '#1976d2'
   }) => {
     const discount = ((basePrice - tierPrice) / basePrice) * 100;
     const savings = basePrice - tierPrice;
     
     return (
       <Box sx={{ display: 'flex', flexDirection: 'column', gap: 0.5 }}>
         <Box sx={{ display: 'flex', alignItems: 'center', gap: 1 }}>
           <Typography variant="h6" color="primary" fontWeight="bold">
             {currency} {tierPrice.toFixed(2)}
           </Typography>
           <Chip
             size="small"
             icon={<Star sx={{ fontSize: 16 }} />}
             label={tierName}
             sx={{
               backgroundColor: tierColor,
               color: 'white',
               '& .MuiChip-icon': { color: 'white' }
             }}
           />
         </Box>
         
         {discount > 0 && (
           <Box sx={{ display: 'flex', alignItems: 'center', gap: 1 }}>
             <Typography
               variant="body2"
               color="text.secondary"
               sx={{ textDecoration: 'line-through' }}
             >
               {currency} {basePrice.toFixed(2)}
             </Typography>
             <Tooltip title={`You save ${currency} ${savings.toFixed(2)}`}>
               <Chip
                 size="small"
                 icon={<LocalOffer sx={{ fontSize: 14 }} />}
                 label={`-${discount.toFixed(0)}%`}
                 color="success"
                 variant="outlined"
               />
             </Tooltip>
           </Box>
         )}
       </Box>
     );
   };
   ```

   ```typescript
   // src/components/catalog/VolumePriceTable.tsx
   import React from 'react';
   import {
     Table,
     TableBody,
     TableCell,
     TableHead,
     TableRow,
     Paper,
     Typography,
     Box
   } from '@mui/material';
   import { TrendingDown } from '@mui/icons-material';
   
   interface PriceBreak {
     min_quantity: number;
     max_quantity?: number;
     price: number;
     savings_percentage: number;
   }
   
   interface VolumePriceTableProps {
     priceBreaks: PriceBreak[];
     currency: string;
     currentQuantity?: number;
   }
   
   export const VolumePriceTable: React.FC<VolumePriceTableProps> = ({
     priceBreaks,
     currency,
     currentQuantity = 1
   }) => {
     return (
       <Paper elevation={2} sx={{ p: 2, mt: 2 }}>
         <Box sx={{ display: 'flex', alignItems: 'center', gap: 1, mb: 2 }}>
           <TrendingDown color="primary" />
           <Typography variant="subtitle1" fontWeight="medium">
             Volume Pricing
           </Typography>
         </Box>
         
         <Table size="small">
           <TableHead>
             <TableRow>
               <TableCell>Quantity Range</TableCell>
               <TableCell align="right">Price per Unit</TableCell>
               <TableCell align="right">Savings</TableCell>
             </TableRow>
           </TableHead>
           <TableBody>
             {priceBreaks.map((breakItem, index) => {
               const isActive = currentQuantity >= breakItem.min_quantity &&
                 (!breakItem.max_quantity || currentQuantity < breakItem.max_quantity);
               
               return (
                 <TableRow
                   key={index}
                   sx={{
                     backgroundColor: isActive ? 'rgba(25, 118, 210, 0.08)' : 'inherit',
                     '& td': { fontWeight: isActive ? 'bold' : 'normal' }
                   }}
                 >
                   <TableCell>
                     {breakItem.max_quantity
                       ? `${breakItem.min_quantity} - ${breakItem.max_quantity}`
                       : `${breakItem.min_quantity}+`}
                   </TableCell>
                   <TableCell align="right">
                     {currency} {breakItem.price.toFixed(2)}
                   </TableCell>
                   <TableCell align="right">
                     {breakItem.savings_percentage > 0 && (
                       <Typography color="success.main" variant="body2">
                         -{breakItem.savings_percentage.toFixed(0)}%
                       </Typography>
                     )}
                   </TableCell>
                 </TableRow>
               );
             })}
           </TableBody>
         </Table>
       </Paper>
     );
   };
   ```

2. **Product Card Component (2 hours)**
   ```typescript
   // src/components/catalog/ProductCard.tsx
   import React, { useState } from 'react';
   import {
     Card,
     CardContent,
     CardMedia,
     Typography,
     Box,
     Button,
     TextField,
     IconButton,
     Chip,
     Tooltip,
     Dialog,
     DialogTitle,
     DialogContent
   } from '@mui/material';
   import { Add, Remove, Info, ShoppingCart } from '@mui/icons-material';
   import { useCartStore } from '../../stores/cartStore';
   import { TierPriceDisplay } from './TierPriceDisplay';
   import { VolumePriceTable } from './VolumePriceTable';
   import { useQuery } from 'react-query';
   import { apiService } from '../../services/api';
   
   interface Product {
     id: number;
     name: string;
     default_code: string;
     description?: string;
     image_url?: string;
     list_price: number;
     currency: string;
     uom_name: string;
     min_order_qty: number;
     order_multiple: number;
     stock_quantity: number;
     is_b2b_available: boolean;
   }
   
   interface ProductCardProps {
     product: Product;
     tierPrice?: number;
     tierName?: string;
   }
   
   export const ProductCard: React.FC<ProductCardProps> = ({
     product,
     tierPrice,
     tierName
   }) => {
     const [quantity, setQuantity] = useState(product.min_order_qty);
     const [showDetails, setShowDetails] = useState(false);
     const { addItem, isLoading } = useCartStore();
     
     const { data: priceBreaks } = useQuery(
       ['price-breaks', product.id],
       () => apiService.getPriceBreaks(product.id),
       { enabled: showDetails }
     );
     
     const handleQuantityChange = (delta: number) => {
       const newQty = quantity + delta * product.order_multiple;
       if (newQty >= product.min_order_qty) {
         setQuantity(newQty);
       }
     };
     
     const handleAddToCart = async () => {
       await addItem(product.id, quantity);
     };
     
     const displayPrice = tierPrice ?? product.list_price;
     
     return (
       <Card sx={{ height: '100%', display: 'flex', flexDirection: 'column' }}>
         <CardMedia
           component="img"
           height="180"
           image={product.image_url || '/images/product-placeholder.jpg'}
           alt={product.name}
           sx={{ objectFit: 'contain', p: 2, backgroundColor: '#f5f5f5' }}
         />
         
         <CardContent sx={{ flexGrow: 1, display: 'flex', flexDirection: 'column' }}>
           <Box sx={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
             <Typography variant="h6" component="h3" gutterBottom>
               {product.name}
             </Typography>
             <Tooltip title="View Details">
               <IconButton size="small" onClick={() => setShowDetails(true)}>
                 <Info />
               </IconButton>
             </Tooltip>
           </Box>
           
           <Typography variant="caption" color="text.secondary" gutterBottom>
             SKU: {product.default_code}
           </Typography>
           
           <Box sx={{ mt: 'auto', pt: 2 }}>
             {tierPrice ? (
               <TierPriceDisplay
                 basePrice={product.list_price}
                 tierPrice={tierPrice}
                 currency={product.currency}
                 tierName={tierName || 'Your Price'}
               />
             ) : (
               <Typography variant="h6" color="primary" fontWeight="bold">
                 {product.currency} {product.list_price.toFixed(2)}
               </Typography>
             )}
             
             <Box sx={{ display: 'flex', alignItems: 'center', gap: 1, mt: 2 }}>
               <IconButton
                 size="small"
                 onClick={() => handleQuantityChange(-1)}
                 disabled={quantity <= product.min_order_qty}
               >
                 <Remove />
               </IconButton>
               
               <TextField
                 size="small"
                 value={quantity}
                 onChange={(e) => setQuantity(Number(e.target.value))}
                 inputProps={{
                   min: product.min_order_qty,
                   step: product.order_multiple,
                   style: { textAlign: 'center', width: 60 }
                 }}
               />
               
               <IconButton size="small" onClick={() => handleQuantityChange(1)}>
                 <Add />
               </IconButton>
               
               <Typography variant="caption" color="text.secondary">
                 {product.uom_name}
               </Typography>
             </Box>
             
             <Button
               variant="contained"
               fullWidth
               startIcon={<ShoppingCart />}
               onClick={handleAddToCart}
               disabled={isLoading || product.stock_quantity < quantity}
               sx={{ mt: 2 }}
             >
               Add to Cart
             </Button>
             
             {product.stock_quantity < quantity && (
               <Typography variant="caption" color="error" sx={{ mt: 1, display: 'block' }}>
                 Insufficient stock ({product.stock_quantity} available)
               </Typography>
             )}
           </Box>
         </CardContent>
         
         <Dialog open={showDetails} onClose={() => setShowDetails(false)} maxWidth="sm" fullWidth>
           <DialogTitle>{product.name}</DialogTitle>
           <DialogContent>
             <Typography variant="body2" color="text.secondary" paragraph>
               {product.description || 'No description available'}
             </Typography>
             
             <Box sx={{ mb: 2 }}>
               <Chip label={`Min Order: ${product.min_order_qty}`} size="small" sx={{ mr: 1 }} />
               <Chip label={`Multiple: ${product.order_multiple}`} size="small" />
               <Chip 
                 label={`Stock: ${product.stock_quantity}`} 
                 size="small"
                 color={product.stock_quantity > 10 ? 'success' : 'warning'}
                 sx={{ ml: 1 }}
               />
             </Box>
             
             {priceBreaks?.data?.price_breaks && (
               <VolumePriceTable
                 priceBreaks={priceBreaks.data.price_breaks}
                 currency={product.currency}
                 currentQuantity={quantity}
               />
             )}
           </DialogContent>
         </Dialog>
       </Card>
     );
   };
   ```

**Afternoon Tasks (4 hours):**

3. **Product Catalog Grid (2 hours)**
   ```typescript
   // src/components/catalog/ProductCatalogGrid.tsx
   import React, { useState, useCallback } from 'react';
   import {
     Grid,
     Box,
     Pagination,
     Typography,
     Skeleton,
     Fade,
     Alert
   } from '@mui/material';
   import { useQuery } from 'react-query';
   import { ProductCard } from './ProductCard';
   import { apiService } from '../../services/api';
   import { useDebounce } from '../../hooks/useDebounce';
   
   interface ProductQueryParams {
     page: number;
     per_page: number;
     category_id?: number;
     search?: string;
     tier_id?: number;
     min_price?: number;
     max_price?: number;
     in_stock?: boolean;
     sort_by?: string;
     sort_order?: 'asc' | 'desc';
   }
   
   interface ProductCatalogGridProps {
     categoryId?: number;
     searchQuery?: string;
   }
   
   export const ProductCatalogGrid: React.FC<ProductCatalogGridProps> = ({
     categoryId,
     searchQuery
   }) => {
     const [page, setPage] = useState(1);
     const perPage = 12;
     
     const debouncedSearch = useDebounce(searchQuery, 300);
     
     const queryParams: ProductQueryParams = {
       page,
       per_page: perPage,
       ...(categoryId && { category_id: categoryId }),
       ...(debouncedSearch && { search: debouncedSearch }),
     };
     
     const { data, isLoading, error } = useQuery(
       ['products', queryParams],
       () => apiService.getProducts(queryParams),
       { keepPreviousData: true }
     );
     
     const handlePageChange = useCallback((event: React.ChangeEvent<unknown>, value: number) => {
       setPage(value);
       window.scrollTo({ top: 0, behavior: 'smooth' });
     }, []);
     
     if (error) {
       return (
         <Alert severity="error">
           Failed to load products. Please try again later.
         </Alert>
       );
     }
     
     if (isLoading) {
       return (
         <Grid container spacing={3}>
           {Array.from({ length: 6 }).map((_, index) => (
             <Grid item xs={12} sm={6} md={4} lg={3} key={index}>
               <Skeleton variant="rectangular" height={350} />
             </Grid>
           ))}
         </Grid>
       );
     }
     
     const products = data?.data?.items || [];
     const totalPages = Math.ceil((data?.data?.total || 0) / perPage);
     
     if (products.length === 0) {
       return (
         <Box sx={{ textAlign: 'center', py: 8 }}>
           <Typography variant="h6" color="text.secondary">
             No products found
           </Typography>
           <Typography variant="body2" color="text.secondary">
             Try adjusting your search or filters
           </Typography>
         </Box>
       );
     }
     
     return (
       <Box>
         <Fade in={!isLoading}>
           <Grid container spacing={3}>
             {products.map((product) => (
               <Grid item xs={12} sm={6} md={4} lg={3} key={product.id}>
                 <ProductCard
                   product={product}
                   tierPrice={product.tier_price}
                   tierName={product.tier_name}
                 />
               </Grid>
             ))}
           </Grid>
         </Fade>
         
         {totalPages > 1 && (
           <Box sx={{ display: 'flex', justifyContent: 'center', mt: 4 }}>
             <Pagination
               count={totalPages}
               page={page}
               onChange={handlePageChange}
               color="primary"
               size="large"
               showFirstButton
               showLastButton
             />
           </Box>
         )}
       </Box>
     );
   };
   ```

4. **Category Filter Sidebar (2 hours)**
   ```typescript
   // src/components/catalog/CategoryFilter.tsx
   import React from 'react';
   import {
     List,
     ListItem,
     ListItemText,
     ListItemIcon,
     Collapse,
     Checkbox,
     Typography,
     Box,
     Divider,
     Chip
   } from '@mui/material';
   import {
     ExpandLess,
     ExpandMore,
     Category,
     Inventory,
     LocalOffer
   } from '@mui/icons-material';
   import { useQuery } from 'react-query';
   import { apiService } from '../../services/api';
   
   interface Category {
     id: number;
     name: string;
     product_count: number;
     children?: Category[];
   }
   
   interface CategoryFilterProps {
     selectedCategories: number[];
     onCategoryToggle: (categoryId: number) => void;
   }
   
   export const CategoryFilter: React.FC<CategoryFilterProps> = ({
     selectedCategories,
     onCategoryToggle
   }) => {
     const [expanded, setExpanded] = React.useState<number[]>([]);
     
     const { data: categories } = useQuery(
       'categories',
       () => apiService.getCategories()
     );
     
     const handleExpand = (categoryId: number) => {
       setExpanded(prev =>
         prev.includes(categoryId)
           ? prev.filter(id => id !== categoryId)
           : [...prev, categoryId]
       );
     };
     
     const renderCategory = (category: Category, level = 0) => {
       const hasChildren = category.children && category.children.length > 0;
       const isExpanded = expanded.includes(category.id);
       const isSelected = selectedCategories.includes(category.id);
       
       return (
         <React.Fragment key={category.id}>
           <ListItem
             sx={{ pl: level * 2 }}
             dense
             button
             onClick={() => hasChildren && handleExpand(category.id)}
           >
             <ListItemIcon sx={{ minWidth: 36 }}>
               <Checkbox
                 edge="start"
                 checked={isSelected}
                 onChange={() => onCategoryToggle(category.id)}
                 onClick={(e) => e.stopPropagation()}
               />
             </ListItemIcon>
             
             <ListItemText
               primary={
                 <Box sx={{ display: 'flex', alignItems: 'center', gap: 1 }}>
                   <Typography variant="body2">{category.name}</Typography>
                   <Chip size="small" label={category.product_count} variant="outlined" />
                 </Box>
               }
             />
             
             {hasChildren && (
               isExpanded ? <ExpandLess /> : <ExpandMore />
             )}
           </ListItem>
           
           {hasChildren && (
             <Collapse in={isExpanded} timeout="auto" unmountOnExit>
               <List component="div" disablePadding>
                 {category.children!.map(child => renderCategory(child, level + 1))}
               </List>
             </Collapse>
           )}
         </React.Fragment>
       );
     };
     
     return (
       <Box>
         <Typography variant="h6" gutterBottom sx={{ display: 'flex', alignItems: 'center', gap: 1 }}>
           <Category fontSize="small" />
           Categories
         </Typography>
         
         <Divider sx={{ mb: 2 }} />
         
         <List dense>
           {categories?.data?.map(category => renderCategory(category))}
         </List>
       </Box>
     );
   };
   ```

**Deliverables for Day 302:**
- Tier price display component
- Volume pricing table component
- Product card with add-to-cart functionality
- Product catalog grid with pagination
- Category filter sidebar
- Responsive layout implementation



---

## 8. Testing Protocols

### 8.1 Unit Test Specifications

#### Python Unit Tests (pytest)

```python
# tests/test_pricing_engine.py
import pytest
from decimal import Decimal
from unittest.mock import Mock, patch

from odoo.tests.common import TransactionCase
from odoo.addons.smart_dairy_b2b_marketplace.services.pricing_engine import (
    PricingEngine, PricingContext, PricingResult
)

class TestPricingEngine(TransactionCase):
    """Test cases for B2B Pricing Engine."""
    
    def setUp(self):
        super().setUp()
        self.engine = self.env['b2b.pricing.engine']
        
        # Create test data
        self.partner = self.env['res.partner'].create({
            'name': 'Test Partner',
            'b2b_tier_id': self.env['b2b.partner.tier'].create({
                'name': 'Gold Tier',
                'code': 'GOLD',
                'discount_type': 'percentage',
                'discount_percentage': 10.0,
            }).id
        })
        
        self.product = self.env['product.product'].create({
            'name': 'Test Product',
            'default_code': 'TEST-001',
            'list_price': 100.0,
            'b2b_available': True,
            'type': 'product',
        })
    
    def test_tier_discount_calculation(self):
        """Test that tier discount is correctly applied."""
        context = PricingContext(
            partner_id=self.partner.id,
            product_id=self.product.id,
            quantity=1.0,
            uom_id=self.product.uom_id.id,
            date=datetime.now(),
            currency_id=self.env.company.currency_id.id
        )
        
        result = self.engine.get_price(context)
        
        self.assertEqual(result.price, Decimal('90.00'))
        self.assertEqual(result.original_price, Decimal('100.00'))
        self.assertEqual(result.discount_percentage, 10.0)
    
    def test_volume_pricing_break(self):
        """Test volume-based pricing."""
        # Create volume pricing rule
        self.env['b2b.volume.pricing'].create({
            'product_id': self.product.id,
            'min_quantity': 10,
            'price_type': 'percentage',
            'discount_percentage': 20.0,
        })
        
        context = PricingContext(
            partner_id=self.partner.id,
            product_id=self.product.id,
            quantity=15.0,
            uom_id=self.product.uom_id.id,
            date=datetime.now(),
            currency_id=self.env.company.currency_id.id
        )
        
        result = self.engine.get_price(context)
        
        # Volume pricing should override tier pricing
        self.assertEqual(result.price, Decimal('80.00'))
        self.assertEqual(result.discount_percentage, 20.0)
    
    def test_tier_specific_product_pricing(self):
        """Test tier-specific product pricing overrides general discount."""
        self.env['b2b.tier.pricing'].create({
            'tier_id': self.partner.b2b_tier_id.id,
            'product_id': self.product.id,
            'price_type': 'fixed',
            'fixed_price': 75.0,
        })
        
        context = PricingContext(
            partner_id=self.partner.id,
            product_id=self.product.id,
            quantity=1.0,
            uom_id=self.product.uom_id.id,
            date=datetime.now(),
            currency_id=self.env.company.currency_id.id
        )
        
        result = self.engine.get_price(context)
        
        self.assertEqual(result.price, Decimal('75.00'))


# tests/test_credit_validation.py
class TestCreditValidation(TransactionCase):
    """Test cases for credit validation."""
    
    def setUp(self):
        super().setUp()
        self.service = self.env['b2b.credit.validator']
        
        self.partner = self.env['res.partner'].create({
            'name': 'Test Partner',
            'credit_limit': 10000.0,
        })
    
    def test_credit_check_approved(self):
        """Test order within credit limit is approved."""
        result = self.service.check_credit_for_order(
            partner_id=self.partner.id,
            order_amount=Decimal('5000.00')
        )
        
        self.assertEqual(result.status, CreditStatus.APPROVED)
        self.assertTrue(result.can_proceed)
    
    def test_credit_check_exceeds_limit(self):
        """Test order exceeding credit limit is rejected."""
        result = self.service.check_credit_for_order(
            partner_id=self.partner.id,
            order_amount=Decimal('15000.00')
        )
        
        self.assertEqual(result.status, CreditStatus.EXCEEDS_LIMIT)
        self.assertFalse(result.can_proceed)
        self.assertEqual(result.exceeded_by, Decimal('5000.00'))
    
    def test_credit_check_with_existing_balance(self):
        """Test credit check considers existing balance."""
        # Create existing invoice
        self.env['account.move'].create({
            'move_type': 'out_invoice',
            'partner_id': self.partner.id,
            'invoice_date': fields.Date.today(),
            'line_ids': [
                (0, 0, {
                    'name': 'Test Product',
                    'quantity': 1,
                    'price_unit': 3000.0,
                })
            ]
        })
        
        result = self.service.check_credit_for_order(
            partner_id=self.partner.id,
            order_amount=Decimal('8000.00')
        )
        
        # Should exceed because 3000 + 8000 > 10000
        self.assertEqual(result.status, CreditStatus.EXCEEDS_LIMIT)


# tests/test_quick_order.py
class TestQuickOrderService(TransactionCase):
    """Test cases for quick order service."""
    
    def setUp(self):
        super().setUp()
        self.service = self.env['b2b.quick.order.service']
        
        self.partner = self.env['res.partner'].create({
            'name': 'Test Partner',
        })
        
        self.product = self.env['product.product'].create({
            'name': 'Test Product',
            'default_code': 'TEST-001',
            'list_price': 100.0,
            'b2b_available': True,
            'min_order_qty': 10,
            'order_multiple': 5,
            'type': 'product',
        })
    
    def test_quick_order_validation_valid(self):
        """Test validation with valid items."""
        items = [
            {'product_code': 'TEST-001', 'quantity': 15}
        ]
        
        result = self.service.process_quick_order(
            partner_id=self.partner.id,
            items=items,
            validate_only=True
        )
        
        self.assertEqual(len(result.valid_lines), 1)
        self.assertEqual(len(result.invalid_lines), 0)
        self.assertTrue(result.can_proceed)
    
    def test_quick_order_validation_invalid_product(self):
        """Test validation with invalid product code."""
        items = [
            {'product_code': 'INVALID', 'quantity': 10}
        ]
        
        result = self.service.process_quick_order(
            partner_id=self.partner.id,
            items=items,
            validate_only=True
        )
        
        self.assertEqual(len(result.valid_lines), 0)
        self.assertEqual(len(result.invalid_lines), 1)
        self.assertFalse(result.can_proceed)
    
    def test_quick_order_validation_min_quantity(self):
        """Test validation enforces minimum quantity."""
        items = [
            {'product_code': 'TEST-001', 'quantity': 5}  # Below min of 10
        ]
        
        result = self.service.process_quick_order(
            partner_id=self.partner.id,
            items=items,
            validate_only=True
        )
        
        self.assertEqual(len(result.invalid_lines), 1)
        self.assertIn('minimum', result.invalid_lines[0].error_message.lower())
    
    def test_quick_order_validation_order_multiple(self):
        """Test validation enforces order multiple."""
        items = [
            {'product_code': 'TEST-001', 'quantity': 12}  # Not multiple of 5
        ]
        
        result = self.service.process_quick_order(
            partner_id=self.partner.id,
            items=items,
            validate_only=True
        )
        
        # Should auto-correct to 15
        self.assertEqual(len(result.valid_lines), 1)
        self.assertEqual(result.valid_lines[0].quantity, 15)
```

#### JavaScript Unit Tests (Jest + React Testing Library)

```typescript
// src/components/catalog/__tests__/ProductCard.test.tsx
import React from 'react';
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { QueryClient, QueryClientProvider } from 'react-query';
import { ProductCard } from '../ProductCard';
import { useCartStore } from '../../../stores/cartStore';

// Mock the cart store
jest.mock('../../../stores/cartStore');

const mockAddItem = jest.fn();
const mockUseCartStore = useCartStore as unknown as jest.Mock;

mockUseCartStore.mockReturnValue({
  addItem: mockAddItem,
  isLoading: false,
});

const mockProduct = {
  id: 1,
  name: 'Test Product',
  default_code: 'TEST-001',
  description: 'Test description',
  image_url: '/test-image.jpg',
  list_price: 100.00,
  currency: 'USD',
  uom_name: 'Unit',
  min_order_qty: 10,
  order_multiple: 5,
  stock_quantity: 50,
  is_b2b_available: true,
};

const queryClient = new QueryClient();

const wrapper = ({ children }: { children: React.ReactNode }) => (
  <QueryClientProvider client={queryClient}>
    {children}
  </QueryClientProvider>
);

describe('ProductCard', () => {
  beforeEach(() => {
    jest.clearAllMocks();
  });

  it('renders product information correctly', () => {
    render(<ProductCard product={mockProduct} />, { wrapper });
    
    expect(screen.getByText('Test Product')).toBeInTheDocument();
    expect(screen.getByText('SKU: TEST-001')).toBeInTheDocument();
    expect(screen.getByText('USD 100.00')).toBeInTheDocument();
  });

  it('displays tier pricing when available', () => {
    render(
      <ProductCard 
        product={mockProduct} 
        tierPrice={85.00}
        tierName="Gold Partner"
      />, 
      { wrapper }
    );
    
    expect(screen.getByText('USD 85.00')).toBeInTheDocument();
    expect(screen.getByText('Gold Partner')).toBeInTheDocument();
    expect(screen.getByText('-15%')).toBeInTheDocument();
  });

  it('validates minimum order quantity', async () => {
    render(<ProductCard product={mockProduct} />, { wrapper });
    
    const quantityInput = screen.getByDisplayValue('10');
    
    // Try to decrease below minimum
    const decreaseButton = screen.getAllByRole('button')[0];
    fireEvent.click(decreaseButton);
    
    // Quantity should stay at minimum
    await waitFor(() => {
      expect(screen.getByDisplayValue('10')).toBeInTheDocument();
    });
  });

  it('adds item to cart when clicked', async () => {
    render(<ProductCard product={mockProduct} />, { wrapper });
    
    const addButton = screen.getByText('Add to Cart');
    fireEvent.click(addButton);
    
    await waitFor(() => {
      expect(mockAddItem).toHaveBeenCalledWith(1, 10);
    });
  });

  it('disables add button when stock is insufficient', () => {
    const lowStockProduct = {
      ...mockProduct,
      stock_quantity: 5,
    };
    
    render(<ProductCard product={lowStockProduct} />, { wrapper });
    
    const addButton = screen.getByText('Add to Cart');
    expect(addButton).toBeDisabled();
    
    expect(screen.getByText(/insufficient stock/i)).toBeInTheDocument();
  });
});


// src/components/quick-order/__tests__/QuickOrderPad.test.tsx
describe('QuickOrderPad', () => {
  const mockValidate = jest.fn();
  const mockSubmit = jest.fn();
  
  beforeEach(() => {
    jest.clearAllMocks();
    
    global.fetch = jest.fn(() =>
      Promise.resolve({
        json: () => Promise.resolve({ success: true, data: { lines: [], summary: { can_proceed: true } } }),
      })
    ) as jest.Mock;
  });

  it('adds new line when add button is clicked', () => {
    render(<QuickOrderPad />, { wrapper });
    
    const addButton = screen.getByText('Add Line');
    fireEvent.click(addButton);
    
    // Should now have 2 rows (default 1 + added 1)
    const rows = screen.getAllByRole('row');
    expect(rows.length).toBeGreaterThan(2);
  });

  it('validates entries before submission', async () => {
    render(<QuickOrderPad />, { wrapper });
    
    // Enter a product code
    const productInput = screen.getByPlaceholderText('Enter SKU or scan barcode');
    fireEvent.change(productInput, { target: { value: 'TEST-001' } });
    
    const validateButton = screen.getByText('Validate');
    fireEvent.click(validateButton);
    
    await waitFor(() => {
      expect(global.fetch).toHaveBeenCalledWith(
        expect.stringContaining('/quick-order/validate'),
        expect.any(Object)
      );
    });
  });

  it('displays validation results', async () => {
    const validationResponse = {
      success: true,
      data: {
        lines: [
          {
            line_number: 1,
            product_code: 'TEST-001',
            product_name: 'Test Product',
            is_valid: true,
            unit_price: 100.00,
            subtotal: 1000.00,
          }
        ],
        summary: {
          total_lines: 1,
          valid_lines: 1,
          invalid_lines: 0,
          total_amount: 1000.00,
          can_proceed: true,
        }
      }
    };
    
    global.fetch = jest.fn(() =>
      Promise.resolve({
        json: () => Promise.resolve(validationResponse),
      })
    ) as jest.Mock;
    
    render(<QuickOrderPad />, { wrapper });
    
    const validateButton = screen.getByText('Validate');
    fireEvent.click(validateButton);
    
    await waitFor(() => {
      expect(screen.getByText('Test Product')).toBeInTheDocument();
      expect(screen.getByText('$1000.00')).toBeInTheDocument();
    });
  });
});
```

### 8.2 Integration Test Scenarios

```gherkin
Feature: B2B Order Processing

  Scenario: Complete order flow from catalog to confirmation
    Given a B2B partner with Gold tier status
    And the partner has available credit of $50,000
    When the partner browses the product catalog
    And adds 24 units of "Organic Milk" to cart
    And proceeds to checkout
    Then the order should be created successfully
    And the partner should receive order confirmation
    And available credit should be reduced by order amount

  Scenario: Quick order with CSV upload
    Given a B2B partner with valid products in catalog
    When the partner uploads a CSV file with 50 order lines
    Then the system should validate all lines
    And process the file asynchronously
    And notify the partner when complete
    And create an order with valid lines

  Scenario: Credit limit validation during checkout
    Given a B2B partner with credit limit of $10,000
    And existing balance of $8,000
    When the partner attempts to place an order for $5,000
    Then the system should display credit exceeded message
    And offer option to request credit approval
    And prevent order submission

  Scenario: Tier pricing application
    Given a product with list price of $100
    And a Silver tier partner with 10% discount
    When the partner views the product
    Then the displayed price should be $90
    And the discount of 10% should be visible

  Scenario: Volume pricing override
    Given a product with volume pricing:
      | Quantity | Price |
      | 1-9      | $100  |
      | 10-49    | $90   |
      | 50+      | $80   |
    When a partner adds 25 units to cart
    Then the unit price should be $90
    And the subtotal should be $2,250
```

### 8.3 Performance Test Criteria

| Test Scenario | Target Metric | Measurement Method |
|---------------|---------------|-------------------|
| Catalog page load | < 500ms | Lighthouse, WebPageTest |
| Product search | < 200ms | API response time |
| Quick order validation (50 items) | < 2s | API response time |
| Cart add operation | < 100ms | API response time |
| CSV import (1000 lines) | < 30s | End-to-end timing |
| Pricing calculation | < 50ms | API response time |
| Credit validation | < 100ms | API response time |
| Database query (product list) | < 50ms | PostgreSQL logs |
| Concurrent users (500) | < 3s p95 latency | Load testing |
| Cache hit ratio | > 90% | Redis monitoring |

### 8.4 Security Test Cases

```python
# tests/security/test_access_control.py

class TestAccessControl(TransactionCase):
    """Test security and access control."""
    
    def test_partner_cannot_see_other_cart(self):
        """Verify partners cannot access other partners' carts."""
        partner1 = self.env['res.partner'].create({'name': 'Partner 1'})
        partner2 = self.env['res.partner'].create({'name': 'Partner 2'})
        
        user1 = self._create_portal_user(partner1)
        user2 = self._create_portal_user(partner2)
        
        # Create cart for partner1
        cart1 = self.env['b2b.cart'].create({
            'partner_id': partner1.id,
        })
        
        # Try to access as partner2
        with self.assertRaises(AccessError):
            cart1.with_user(user2).read(['id'])
    
    def test_unauthorized_pricing_access(self):
        """Test that pricing requires authentication."""
        # Anonymous request should fail
        response = self.url_open(
            '/api/v1/pricing/calculate',
            data=json.dumps({'product_id': 1}),
            headers={'Content-Type': 'application/json'}
        )
        
        self.assertEqual(response.status_code, 403)
    
    def test_sql_injection_prevention(self):
        """Test that search is protected against SQL injection."""
        malicious_input = "'; DROP TABLE b2b_cart; --"
        
        response = self.url_open(
            '/api/v1/catalog/search?q=' + malicious_input,
            headers=self._auth_headers()
        )
        
        # Should return 200 with no results, not crash
        self.assertEqual(response.status_code, 200)
        
        # Verify table still exists
        self.env.cr.execute("SELECT COUNT(*) FROM b2b_cart")
        # Should not raise exception

    def test_rate_limiting(self):
        """Test API rate limiting."""
        # Make 1001 requests (limit is 1000/min)
        for i in range(1001):
            response = self.url_open(
                '/api/v1/catalog/products',
                headers=self._auth_headers()
            )
        
        # Last request should be rate limited
        self.assertEqual(response.status_code, 429)
```

---

## 9. Deliverables & Acceptance Criteria

### 9.1 Complete Deliverables Table

| # | Deliverable | Owner | Format | Location |
|---|-------------|-------|--------|----------|
| 1 | B2B Catalog Module | Dev 2 | Odoo Addon | /addons/smart_dairy_b2b_marketplace/ |
| 2 | Pricing Engine | Dev 1 | Python Package | /services/pricing_engine.py |
| 3 | Quick Order Service | Dev 1 | Python Service | /services/quick_order_service.py |
| 4 | Credit Validation | Dev 1 | Python Service | /services/credit_validation_service.py |
| 5 | Cart Service | Dev 2 | Odoo Models | /models/b2b_cart.py |
| 6 | CSV Import Processor | Dev 2 | Python/Celery | /services/csv_import_processor.py |
| 7 | React Frontend | Dev 3 | React/TypeScript | /frontend/b2b-marketplace/ |
| 8 | API Documentation | Dev 1 | OpenAPI 3.0 | /docs/api/openapi.yaml |
| 9 | Database Schema | Dev 1 | SQL | /migrations/milestone_31.sql |
| 10 | Unit Tests | All | Python/JS | /tests/ |
| 11 | Integration Tests | All | Gherkin | /tests/integration/ |
| 12 | User Manual | Dev 3 | Markdown/PDF | /docs/user/manual.md |
| 13 | Deployment Guide | Dev 1 | Markdown | /docs/deployment.md |
| 14 | Training Materials | Dev 3 | Video/PPT | /docs/training/ |

### 9.2 Success Metrics

| Metric | Target | Measurement Method |
|--------|--------|-------------------|
| Code Coverage | > 85% | Coverage.py/Jest |
| API Response Time (p95) | < 200ms | APM tools |
| Database Query Time (p99) | < 50ms | PostgreSQL logs |
| Frontend Load Time | < 2s | Lighthouse |
| Error Rate | < 0.1% | Error tracking |
| Uptime | 99.9% | Monitoring |
| User Satisfaction | > 4.5/5 | Surveys |

### 9.3 Sign-off Criteria

1. **Functional Requirements**
   - [ ] Tier pricing correctly calculates for all partner levels
   - [ ] Quick Order Pad validates and processes orders
   - [ ] CSV upload handles 1000+ line files
   - [ ] Cart persists across sessions
   - [ ] Credit validation prevents overspending

2. **Performance Requirements**
   - [ ] All API endpoints meet response time targets
   - [ ] Frontend loads within specified time
   - [ ] Database queries are optimized
   - [ ] Cache hit ratio meets target

3. **Security Requirements**
   - [ ] OWASP Top 10 vulnerabilities addressed
   - [ ] Authentication/authorization working
   - [ ] Input validation implemented
   - [ ] Rate limiting active

4. **Quality Requirements**
   - [ ] Unit tests passing > 85% coverage
   - [ ] Integration tests passing
   - [ ] Code review completed
   - [ ] Documentation complete

---

## 10. Risk & Issue Management

### 10.1 Risk Register

| ID | Risk | Probability | Impact | Mitigation Strategy |
|----|------|-------------|--------|---------------------|
| R1 | ERP integration delays | Medium | High | Early API testing, mock services |
| R2 | Performance issues with large catalogs | Medium | Medium | Caching strategy, pagination |
| R3 | Credit validation complexity | Low | High | Thorough testing, fallback rules |
| R4 | CSV format variations | High | Low | Flexible parser, validation |
| R5 | Browser compatibility issues | Low | Medium | Cross-browser testing |
| R6 | Data migration failures | Low | High | Backup strategy, rollback plan |
| R7 | Third-party service downtime | Low | Medium | Circuit breakers, fallbacks |
| R8 | Security vulnerabilities | Low | Critical | Security audit, penetration testing |

### 10.2 Mitigation Strategies

**R1 - ERP Integration Delays:**
- Create mock ERP services for parallel development
- Define clear API contracts early
- Schedule daily sync meetings with ERP team
- Prepare fallback to manual sync if needed

**R2 - Performance Issues:**
- Implement Redis caching layer
- Use database connection pooling
- Add read replicas for scaling
- Implement lazy loading for catalog

**R3 - Credit Validation:**
- Comprehensive unit testing
- Staged rollout to limited users
- Manual override capability for edge cases
- Daily reconciliation processes

### 10.3 Escalation Procedures

| Level | Trigger | Response Time | Escalate To |
|-------|---------|---------------|-------------|
| 1 | Minor bugs, UI issues | 4 hours | Team Lead |
| 2 | Feature not working as expected | 2 hours | Dev Manager |
| 3 | Data integrity issues | 1 hour | Project Manager |
| 4 | Security breach, system down | Immediate | CTO |

---

## 11. Appendices

### 11.1 Code Samples

#### Sample Odoo Controller
```python
# controllers/main.py
from odoo import http
from odoo.http import request
import json

class B2BMarketplaceController(http.Controller):
    """Main controller for B2B Marketplace endpoints."""
    
    @http.route('/b2b/catalog', type='http', auth='user', website=True)
    def catalog_page(self, **kwargs):
        """Render catalog page."""
        return request.render('smart_dairy_b2b_marketplace.catalog_page', {
            'categories': request.env['product.category'].search([]),
        })
```

#### Sample React Hook
```typescript
// hooks/usePricing.ts
import { useQuery } from 'react-query';
import { apiService } from '../services/api';

export const usePricing = (productId: number, quantity: number) => {
  return useQuery(
    ['pricing', productId, quantity],
    () => apiService.calculatePrice(productId, quantity),
    {
      enabled: productId > 0 && quantity > 0,
      staleTime: 5 * 60 * 1000, // 5 minutes
    }
  );
};
```

### 11.2 Configuration Examples

#### Odoo Manifest
```python
{
    'name': 'Smart Dairy B2B Marketplace',
    'version': '1.0.0',
    'category': 'Sales/B2B',
    'summary': 'B2B Marketplace Core Module',
    'description': """
        Core B2B marketplace functionality for Smart Dairy ERP.
        Features:
        - Tier-based pricing
        - Quick order pad
        - CSV bulk import
        - Credit limit validation
    """,
    'author': 'Smart Dairy Tech Team',
    'website': 'https://smartdairy.com',
    'depends': [
        'base',
        'product',
        'sale',
        'stock',
        'account',
        'website',
        'portal',
    ],
    'data': [
        'security/security.xml',
        'security/ir.model.access.csv',
        'data/b2b_tier_data.xml',
        'views/product_views.xml',
        'views/cart_views.xml',
        'views/order_views.xml',
        'views/templates.xml',
    ],
    'demo': [
        'demo/demo_data.xml',
    ],
    'installable': True,
    'application': True,
    'auto_install': False,
    'license': 'LGPL-3',
}
```

#### Docker Compose Configuration
```yaml
version: '3.8'
services:
  odoo:
    image: odoo:16.0
    depends_on:
      - db
      - redis
    environment:
      - HOST=db
      - USER=odoo
      - PASSWORD=odoo
      - REDIS_HOST=redis
    volumes:
      - ./addons:/mnt/extra-addons
    ports:
      - "8069:8069"
  
  db:
    image: postgres:15
    environment:
      - POSTGRES_DB=postgres
      - POSTGRES_PASSWORD=odoo
      - POSTGRES_USER=odoo
    volumes:
      - odoo-db-data:/var/lib/postgresql/data
  
  redis:
    image: redis:7-alpine
    volumes:
      - redis-data:/data
  
  frontend:
    build: ./frontend
    ports:
      - "3000:3000"
    environment:
      - REACT_APP_API_URL=http://localhost:8069

volumes:
  odoo-db-data:
  redis-data:
```

### 11.3 API Reference Snippets

#### Authentication
```bash
# Login
curl -X POST http://api.example.com/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username": "user@example.com", "password": "secret"}'

# Response
{
  "success": true,
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIs...",
    "refresh_token": "...",
    "expires_in": 3600
  }
}
```

#### Quick Order Example
```bash
# Validate quick order
curl -X POST http://api.example.com/api/v1/quick-order/validate \
  -H "Authorization: Bearer <token>" \
  -H "Content-Type: application/json" \
  -d '{
    "items": [
      {"product_code": "MILK-001", "quantity": 24},
      {"product_code": "CHEESE-001", "quantity": 12}
    ]
  }'
```

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-02 | Technical Architecture Team | Initial document creation |

---

**END OF DOCUMENT**

*This document contains confidential and proprietary information of Smart Dairy. Unauthorized distribution is prohibited.*
