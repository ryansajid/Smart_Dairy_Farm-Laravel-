# Milestone 36: ERP Integration Layer

## Smart Dairy Smart Portal + ERP System Implementation

**Document Version:** 1.0  
**Phase:** 4 - Core Infrastructure & Integration  
**Milestone:** 36  
**Duration:** Days 351-360 (10 Days)  
**Status:** Implementation Plan  
**Classification:** Technical Implementation Document  
**Author:** Smart Dairy Technical Architecture Team  
**Last Updated:** 2026-02-02  

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 0.1 | 2026-01-15 | Architecture Team | Initial draft |
| 0.2 | 2026-01-20 | Integration Lead | Added API Gateway specs |
| 0.3 | 2026-01-25 | Backend Team | Added connector implementations |
| 0.4 | 2026-01-28 | Data Team | Added sync engine details |
| 0.5 | 2026-01-30 | QA Team | Added testing procedures |
| 1.0 | 2026-02-02 | Tech Lead | Final review and release |

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Integration Architecture](#2-integration-architecture)
3. [Daily Task Allocation](#3-daily-task-allocation)
4. [API Gateway Specifications](#4-api-gateway-specifications)
5. [Connector Implementations](#5-connector-implementations)
6. [Synchronization Engine](#6-synchronization-engine)
7. [Data Transformation](#7-data-transformation)
8. [Error Handling](#8-error-handling)
9. [Testing & Validation](#9-testing--validation)
10. [Monitoring & Observability](#10-monitoring--observability)
11. [Appendices](#11-appendices)

---

## 1. Executive Summary

### 1.1 Integration Layer Objectives

The ERP Integration Layer (Milestone 36) represents a critical architectural component that bridges the Smart Dairy Smart Portal ecosystem with the core Enterprise Resource Planning (ERP) system. This milestone delivers a unified, scalable, and resilient integration framework that enables seamless bidirectional data flow between all portal modules (B2B, B2C, Farm Management, Mobile Applications) and the central ERP backbone.

#### Primary Objectives

**1. Unified API Gateway Implementation**

The integration layer establishes a centralized API Gateway that serves as the single entry point for all ERP-bound communications. This gateway provides standardized authentication, authorization, rate limiting, request routing, protocol translation, and comprehensive logging across all integration touchpoints. The gateway eliminates point-to-point integration complexity and ensures consistent security policies and performance characteristics.

**2. Real-time Synchronization Engine**

A high-performance synchronization engine forms the heart of the integration layer, enabling both real-time event-driven synchronization and scheduled batch processing. The engine supports multiple sync patterns including:

- **Event-driven synchronization**: Immediate propagation of critical business events (orders, payments, inventory changes) within sub-second latency requirements
- **Scheduled batch synchronization**: Periodic bulk data transfers for non-critical data sets (historical records, analytics data, reference data)
- **On-demand synchronization**: Ad-hoc sync triggers for manual reconciliation and administrative operations
- **Conflict resolution**: Intelligent merging strategies for handling concurrent updates across systems

**3. Comprehensive Connector Ecosystem**

The milestone delivers production-ready connectors for each portal module:

- **B2B Portal Connector**: Handles complex B2B workflows including negotiated pricing, contract terms, bulk ordering, credit management, and corporate account hierarchies
- **B2C E-commerce Connector**: Manages consumer-facing transactions, product catalogs, promotional pricing, loyalty programs, and payment reconciliation
- **Farm Management Connector**: Integrates livestock data, production metrics, health records, breeding cycles, feed management, and IoT sensor data
- **Mobile Application Connector**: Optimized for mobile constraints with offline synchronization, push notification support, and data caching strategies

**4. Data Transformation and Validation**

A robust data transformation layer ensures semantic consistency between portal data models and ERP schemas. Key capabilities include:

- Field-level mapping with configurable transformation rules
- Data type conversion and normalization
- Business rule validation and enrichment
- Referential integrity verification
- Custom transformation logic support

**5. Enterprise-grade Error Handling**

The integration layer implements sophisticated error handling mechanisms:

- Hierarchical error classification system
- Configurable retry policies with exponential backoff
- Dead letter queue management for failed operations
- Comprehensive alerting and notification workflows
- Automatic recovery procedures
- Transaction compensation mechanisms

### 1.2 ERP Connectivity Strategy

#### Strategic Architecture Approach

The ERP Integration Layer employs a **Hub-and-Spoke Architecture** pattern that centralizes integration logic while maintaining flexibility for individual portal requirements. This approach provides several strategic advantages:

**Centralized Control and Governance**

All ERP interactions flow through the API Gateway, enabling centralized policy enforcement, security controls, and monitoring. This eliminates integration sprawl and ensures consistent adherence to enterprise architecture standards.

**Decoupled System Dependencies**

Portal modules communicate with abstracted integration interfaces rather than direct ERP connections. This decoupling allows independent evolution of portal functionality and ERP system upgrades without cascading changes.

**Scalable Message Processing**

The integration layer leverages distributed message queuing and event streaming to handle varying load patterns. Peak transaction volumes (e.g., promotional sales events, end-of-month batch processing) are absorbed by the queue infrastructure without impacting system stability.

**Multi-Protocol Support**

The gateway supports diverse communication protocols to accommodate ERP capabilities and portal requirements:

| Protocol | Use Case | Implementation |
|----------|----------|----------------|
| REST/HTTP | Real-time APIs, CRUD operations | Spring Cloud Gateway |
| gRPC | High-performance internal services | Netty-based gRPC server |
| GraphQL | Flexible data fetching for mobile | Apollo Gateway |
| SOAP | Legacy ERP integrations | Apache CXF |
| MQTT | IoT data streams | Eclipse Mosquitto |
| AMQP | Async message queuing | RabbitMQ |
| Kafka | Event streaming | Apache Kafka |

#### Integration Patterns Implementation

**Request-Reply Pattern**

Synchronous operations (customer lookup, order validation, pricing queries) use request-reply semantics with configurable timeouts and circuit breaker protection.

**Fire-and-Forget Pattern**

Non-critical updates (activity logging, analytics events) use asynchronous fire-and-forget messaging to minimize latency impact on user-facing operations.

**Publish-Subscribe Pattern**

Business events (order created, inventory updated, payment received) are published to topic exchanges, allowing multiple subscribers to react independently.

**Aggregator Pattern**

Complex queries requiring data from multiple ERP modules use the aggregator pattern to parallelize requests and combine responses.

**Circuit Breaker Pattern**

All ERP connections implement circuit breakers to prevent cascade failures during ERP outages or degraded performance scenarios.

#### Security Architecture

**Zero Trust Network Principles**

The integration layer implements zero trust principles where every request is authenticated and authorized regardless of network origin:

- **Mutual TLS (mTLS)**: All service-to-service communications use certificate-based authentication
- **OAuth 2.0 / OIDC**: User-facing APIs use OAuth 2.0 with JWT tokens
- **API Keys**: System integrations use scoped API keys with rotation policies
- **HMAC Signatures**: Webhook endpoints verify message integrity using HMAC-SHA256

**Data Protection**

- Encryption at rest: All queued messages and audit logs use AES-256 encryption
- Encryption in transit: TLS 1.3 for all external communications
- Field-level encryption: PII and financial data use additional encryption layers
- Tokenization: Credit card and bank account data use PCI-compliant tokenization

### 1.3 Success Criteria

#### Functional Success Criteria

| Criteria ID | Description | Target Metric | Measurement Method |
|-------------|-------------|---------------|-------------------|
| SC-001 | API Gateway Availability | 99.99% uptime | Synthetic monitoring probes |
| SC-002 | Real-time Sync Latency | < 500ms p95 | APM transaction traces |
| SC-003 | Batch Processing Throughput | 10,000 records/minute | Load testing metrics |
| SC-004 | Data Consistency | 100% accuracy | Automated reconciliation |
| SC-005 | Error Recovery Rate | > 99% auto-recovery | Error tracking analytics |
| SC-006 | Connector Coverage | All 4 portal modules | Integration test pass rate |

#### Technical Success Criteria

**Performance Targets**

```yaml
Performance_SLAs:
  API_Gateway:
    P50_Latency: "< 50ms"
    P95_Latency: "< 200ms"
    P99_Latency: "< 500ms"
    Throughput: "> 10,000 RPS"
    Concurrent_Connections: "> 100,000"
  
  Synchronization:
    Real_Time_Latency: "< 1 second"
    Batch_Processing_Rate: "> 50,000 records/minute"
    Queue_Depth_Threshold: "< 10,000 messages"
    Conflict_Resolution_Time: "< 5 seconds"
  
  Data_Processing:
    Transformation_Latency: "< 100ms per record"
    Validation_Throughput: "> 100,000 records/minute"
    Import_File_Size_Limit: "1 GB"
    Export_Generation_Time: "< 5 minutes for 1M records"
```

**Reliability Targets**

```yaml
Reliability_SLAs:
  Availability:
    API_Gateway: "99.99%"
    Sync_Engine: "99.95%"
    Connectors: "99.9%"
  
  Durability:
    Message_Persistence: "100% - zero message loss"
    Audit_Log_Completeness: "100%"
    Backup_RPO: "< 5 minutes"
    Recovery_RTO: "< 15 minutes"
  
  Fault_Tolerance:
    Retry_Success_Rate: "> 95% on transient failures"
    Circuit_Breaker_Response: "< 1 second"
    Failover_Time: "< 30 seconds"
```

**Scalability Targets**

```yaml
Scalability_Requirements:
  Horizontal_Scaling:
    API_Gateway: "Auto-scale 5-50 instances"
    Sync_Workers: "Auto-scale 10-200 instances"
    Queue_Consumers: "Auto-scale 5-100 instances"
  
  Data_Volume:
    Daily_Transactions: "> 10 million"
    Concurrent_Users: "> 100,000"
    Data_Retention: "7 years"
    Peak_Load_Multiplier: "10x average"
```

#### Business Success Criteria

**Operational Efficiency**

- **Manual Reconciliation Reduction**: 90% reduction in manual data reconciliation efforts
- **Order Processing Time**: 70% reduction in end-to-end order processing time
- **Inventory Visibility**: Real-time inventory accuracy across all channels
- **Financial Close**: 50% reduction in month-end financial close time

**System Integration**

- **Legacy ERP Connectivity**: Successful integration with existing ERP without modifications
- **Future ERP Migration**: Architecture supports future ERP replacement without portal changes
- **Third-party Integration**: Framework supports additional third-party system integrations

**Risk Mitigation**

- **Data Integrity**: Zero data loss during synchronization
- **Compliance**: Full audit trail for SOX, GDPR, and industry regulations
- **Business Continuity**: < 15 minute recovery time for critical integration functions

---

## 2. Integration Architecture

### 2.1 API Gateway Architecture

#### 2.1.1 Gateway Component Overview

The Smart Dairy API Gateway serves as the central nervous system for all ERP integrations, implementing a multi-tier architecture that separates concerns and enables independent scaling of components.

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                              API GATEWAY ARCHITECTURE                             │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                   │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │                         EDGE LAYER (CDN + WAF)                          │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐                   │    │
│  │  │   CDN Cache  │  │  DDoS Protect│  │   WAF Rules  │                   │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘                   │    │
│  └─────────────────────────────────────────────────────────────────────────┘    │
│                                      │                                           │
│                                      ▼                                           │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │                     LOAD BALANCER LAYER (L7)                            │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐                   │    │
│  │  │   HAProxy    │  │ Health Checks│  │ SSL Offload  │                   │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘                   │    │
│  └─────────────────────────────────────────────────────────────────────────┘    │
│                                      │                                           │
│                                      ▼                                           │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │                    GATEWAY CORE (Spring Cloud Gateway)                  │    │
│  │  ┌─────────────────────────────────────────────────────────────────┐    │    │
│  │  │                    ROUTING ENGINE                                │    │    │
│  │  │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐          │    │    │
│  │  │  │ Route #1 │ │ Route #2 │ │ Route #3 │ │ Route #N │          │    │    │
│  │  │  │ B2B API  │ │ B2C API  │ │ Farm API │ │ Mobile   │          │    │    │
│  │  │  └──────────┘ └──────────┘ └──────────┘ └──────────┘          │    │    │
│  │  └─────────────────────────────────────────────────────────────────┘    │    │
│  │                                                                               │
│  │  ┌─────────────────────────────────────────────────────────────────┐    │    │
│  │  │                 FILTER CHAIN                                     │    │    │
│  │  │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐          │    │    │
│  │  │  │   Auth   │ │  Rate    │ │ Request  │ │Response  │          │    │    │
│  │  │  │  Filter  │ │  Limit   │ │Transform │ │ Transform│          │    │    │
│  │  │  └──────────┘ └──────────┘ └──────────┘ └──────────┘          │    │    │
│  │  └─────────────────────────────────────────────────────────────────┘    │    │
│  │                                                                               │
│  │  ┌─────────────────────────────────────────────────────────────────┐    │    │
│  │  │                 SECURITY LAYER                                   │    │    │
│  │  │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐          │    │    │
│  │  │  │  OAuth   │ │   JWT    │ │   mTLS   │ │ API Key  │          │    │    │
│  │  │  │ 2.0/OIDC │ │ Validation│ │  Verify  │ │ Validate │          │    │    │
│  │  │  └──────────┘ └──────────┘ └──────────┘ └──────────┘          │    │    │
│  │  └─────────────────────────────────────────────────────────────────┘    │    │
│  └─────────────────────────────────────────────────────────────────────────┘    │
│                                      │                                           │
│                                      ▼                                           │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │                    BACKEND INTEGRATION LAYER                            │    │
│  │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐      │    │
│  │  │ B2B Conn │ │ B2C Conn │ │ Farm Conn│ │ Mobile   │ │ ERP Core │      │    │
│  │  │ ector    │ │ ector    │ │ ector    │ │ Connector│ │ Adapter  │      │    │
│  │  └──────────┘ └──────────┘ └──────────┘ └──────────┘ └──────────┘      │    │
│  └─────────────────────────────────────────────────────────────────────────┘    │
│                                                                                   │
└─────────────────────────────────────────────────────────────────────────────────┘
```

#### 2.1.2 Gateway Configuration Architecture

The API Gateway uses a dynamic configuration system that enables runtime updates without service restarts:

```yaml
# gateway-config.yaml - Master Configuration
server:
  port: 8080
  netty:
    connection-timeout: 2000
    keep-alive: true
    max-initial-line-length: 8192
    max-header-size: 8192
    max-chunk-size: 8192

spring:
  cloud:
    gateway:
      # Global CORS Configuration
      globalcors:
        cors-configurations:
          '[/**]':
            allowedOrigins:
              - "https://portal.smartdairy.com"
              - "https://b2b.smartdairy.com"
              - "https://farm.smartdairy.com"
              - "https://mobile.smartdairy.com"
            allowedMethods:
              - GET
              - POST
              - PUT
              - DELETE
              - PATCH
              - OPTIONS
            allowedHeaders:
              - "*"
            allowCredentials: true
            maxAge: 3600
      
      # Default Filters Applied to All Routes
      default-filters:
        - name: RequestRateLimiter
          args:
            redis-rate-limiter.replenishRate: 1000
            redis-rate-limiter.burstCapacity: 2000
            redis-rate-limiter.requestedTokens: 1
            key-resolver: "#{@userKeyResolver}"
        - name: Retry
          args:
            retries: 3
            statuses: BAD_GATEWAY,SERVICE_UNAVAILABLE,INTERNAL_SERVER_ERROR
            methods: GET,POST,PUT
            backoff:
              firstBackoff: 50ms
              maxBackoff: 500ms
              factor: 2
              basedOnPreviousValue: false
        - name: CircuitBreaker
          args:
            name: erpCircuitBreaker
            fallbackUri: forward:/fallback/erp-unavailable
            statusCodes:
              - 500
              - 502
              - 503
              - 504
        - name: RequestSize
          args:
            maxSize: 100MB
        - name: DedupeResponseHeader
          args:
            name: Access-Control-Allow-Credentials Access-Control-Allow-Origin
            strategy: RETAIN_FIRST
      
      # Route Definitions
      routes:
        # B2B Portal Routes
        - id: b2b-orders-route
          uri: lb://b2b-connector-service
          predicates:
            - Path=/api/v1/b2b/orders/**
            - Method=GET,POST,PUT,DELETE,PATCH
          filters:
            - StripPrefix=3
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 500
                redis-rate-limiter.burstCapacity: 1000
                key-resolver: "#{@b2bClientKeyResolver}"
            - name: CircuitBreaker
              args:
                name: b2bOrdersCircuitBreaker
                fallbackUri: forward:/fallback/b2b-orders
            - AddRequestHeader=X-Portal-Type, B2B
            - AddRequestHeader=X-API-Version, 1.0
            - name: CacheRequestBody
              args:
                cache: b2bRequestCache
            - name: ModifyResponseBody
              args:
                rewriteFunction: "#{@b2bResponseModifier}"
          metadata:
            apiCategory: b2b
            priority: high
            timeout: 5000
            retryable: true

        - id: b2b-customers-route
          uri: lb://b2b-connector-service
          predicates:
            - Path=/api/v1/b2b/customers/**
            - Method=GET,POST,PUT,DELETE
          filters:
            - StripPrefix=3
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 300
                redis-rate-limiter.burstCapacity: 600
                key-resolver: "#{@b2bClientKeyResolver}"
            - AddRequestHeader=X-Portal-Type, B2B
            - AddRequestHeader=X-Resource-Type, Customer
          metadata:
            apiCategory: b2b
            priority: high
            timeout: 3000

        - id: b2b-pricing-route
          uri: lb://b2b-connector-service
          predicates:
            - Path=/api/v1/b2b/pricing/**
            - Method=GET,POST
          filters:
            - StripPrefix=3
            - AddRequestHeader=X-Portal-Type, B2B
            - name: CacheRequest
              args:
                cacheName: pricingCache
                ttl: 300
          metadata:
            apiCategory: b2b
            priority: critical
            timeout: 1000
            cacheable: true

        - id: b2b-inventory-route
          uri: lb://b2b-connector-service
          predicates:
            - Path=/api/v1/b2b/inventory/**
            - Method=GET
          filters:
            - StripPrefix=3
            - AddRequestHeader=X-Portal-Type, B2B
            - name: CacheRequest
              args:
                cacheName: inventoryCache
                ttl: 60
          metadata:
            apiCategory: b2b
            priority: critical
            timeout: 500
            cacheable: true

        # B2C E-commerce Routes
        - id: b2c-products-route
          uri: lb://b2c-connector-service
          predicates:
            - Path=/api/v1/b2c/products/**
            - Method=GET
          filters:
            - StripPrefix=3
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 2000
                redis-rate-limiter.burstCapacity: 5000
                key-resolver: "#{@ipKeyResolver}"
            - AddRequestHeader=X-Portal-Type, B2C
            - name: CacheRequest
              args:
                cacheName: productCache
                ttl: 600
          metadata:
            apiCategory: b2c
            priority: high
            timeout: 300
            cacheable: true

        - id: b2c-orders-route
          uri: lb://b2c-connector-service
          predicates:
            - Path=/api/v1/b2c/orders/**
            - Method=GET,POST,PUT
          filters:
            - StripPrefix=3
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 1000
                redis-rate-limiter.burstCapacity: 2000
                key-resolver: "#{@userKeyResolver}"
            - name: CircuitBreaker
              args:
                name: b2cOrdersCircuitBreaker
                fallbackUri: forward:/fallback/b2c-orders
            - AddRequestHeader=X-Portal-Type, B2C
          metadata:
            apiCategory: b2c
            priority: critical
            timeout: 3000
            retryable: true

        - id: b2c-payments-route
          uri: lb://b2c-connector-service
          predicates:
            - Path=/api/v1/b2c/payments/**
            - Method=GET,POST
          filters:
            - StripPrefix=3
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 500
                redis-rate-limiter.burstCapacity: 1000
                key-resolver: "#{@userKeyResolver}"
            - name: CircuitBreaker
              args:
                name: paymentsCircuitBreaker
                fallbackUri: forward:/fallback/payments
            - AddRequestHeader=X-Portal-Type, B2C
            - AddRequestHeader=X-Transaction-Type, Payment
          metadata:
            apiCategory: b2c
            priority: critical
            timeout: 5000
            idempotent: true

        - id: b2c-customers-route
          uri: lb://b2c-connector-service
          predicates:
            - Path=/api/v1/b2c/customers/**
            - Method=GET,POST,PUT,DELETE
          filters:
            - StripPrefix=3
            - AddRequestHeader=X-Portal-Type, B2C
          metadata:
            apiCategory: b2c
            priority: high
            timeout: 2000

        # Farm Management Routes
        - id: farm-animals-route
          uri: lb://farm-connector-service
          predicates:
            - Path=/api/v1/farm/animals/**
            - Method=GET,POST,PUT,DELETE
          filters:
            - StripPrefix=3
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 200
                redis-rate-limiter.burstCapacity: 500
                key-resolver: "#{@farmKeyResolver}"
            - AddRequestHeader=X-Portal-Type, FARM
            - AddRequestHeader=X-Resource-Type, Animal
          metadata:
            apiCategory: farm
            priority: high
            timeout: 2000

        - id: farm-production-route
          uri: lb://farm-connector-service
          predicates:
            - Path=/api/v1/farm/production/**
            - Method=GET,POST,PUT
          filters:
            - StripPrefix=3
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 400
                redis-rate-limiter.burstCapacity: 800
                key-resolver: "#{@farmKeyResolver}"
            - AddRequestHeader=X-Portal-Type, FARM
          metadata:
            apiCategory: farm
            priority: high
            timeout: 1500

        - id: farm-health-route
          uri: lb://farm-connector-service
          predicates:
            - Path=/api/v1/farm/health/**
            - Method=GET,POST,PUT
          filters:
            - StripPrefix=3
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 300
                redis-rate-limiter.burstCapacity: 600
                key-resolver: "#{@farmKeyResolver}"
            - AddRequestHeader=X-Portal-Type, FARM
            - AddRequestHeader=X-Alert-Level, High
          metadata:
            apiCategory: farm
            priority: critical
            timeout: 1000

        - id: farm-iot-route
          uri: lb://farm-connector-service
          predicates:
            - Path=/api/v1/farm/iot/**
            - Method=GET,POST
          filters:
            - StripPrefix=3
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 5000
                redis-rate-limiter.burstCapacity: 10000
                key-resolver: "#{@deviceKeyResolver}"
            - AddRequestHeader=X-Portal-Type, FARM
            - AddRequestHeader=X-Data-Source, IoT
          metadata:
            apiCategory: farm
            priority: medium
            timeout: 500

        # Mobile Application Routes
        - id: mobile-sync-route
          uri: lb://mobile-connector-service
          predicates:
            - Path=/api/v1/mobile/sync/**
            - Method=GET,POST
          filters:
            - StripPrefix=3
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 1000
                redis-rate-limiter.burstCapacity: 2000
                key-resolver: "#{@deviceKeyResolver}"
            - AddRequestHeader=X-Portal-Type, MOBILE
            - AddRequestHeader=X-Client-Type, Mobile
          metadata:
            apiCategory: mobile
            priority: high
            timeout: 5000

        - id: mobile-offline-route
          uri: lb://mobile-connector-service
          predicates:
            - Path=/api/v1/mobile/offline/**
            - Method=POST
          filters:
            - StripPrefix=3
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 500
                redis-rate-limiter.burstCapacity: 1000
                key-resolver: "#{@deviceKeyResolver}"
            - AddRequestHeader=X-Portal-Type, MOBILE
            - AddRequestHeader=X-Sync-Mode, Offline
            - name: RequestSize
              args:
                maxSize: 50MB
          metadata:
            apiCategory: mobile
            priority: medium
            timeout: 10000

        - id: mobile-push-route
          uri: lb://mobile-connector-service
          predicates:
            - Path=/api/v1/mobile/push/**
            - Method=GET,POST
          filters:
            - StripPrefix=3
            - AddRequestHeader=X-Portal-Type, MOBILE
            - AddRequestHeader=X-Service-Type, PushNotification
          metadata:
            apiCategory: mobile
            priority: medium
            timeout: 2000

        # ERP Core Routes
        - id: erp-masterdata-route
          uri: lb://erp-adapter-service
          predicates:
            - Path=/api/v1/erp/masterdata/**
            - Method=GET
          filters:
            - StripPrefix=3
            - AddRequestHeader=X-System-Type, ERP
            - name: CacheRequest
              args:
                cacheName: masterDataCache
                ttl: 3600
          metadata:
            apiCategory: erp
            priority: medium
            timeout: 1000
            cacheable: true

        - id: erp-reports-route
          uri: lb://erp-adapter-service
          predicates:
            - Path=/api/v1/erp/reports/**
            - Method=GET,POST
          filters:
            - StripPrefix=3
            - AddRequestHeader=X-System-Type, ERP
            - AddRequestHeader=X-Report-Source, ERP
          metadata:
            apiCategory: erp
            priority: low
            timeout: 30000

        - id: erp-batch-route
          uri: lb://erp-adapter-service
          predicates:
            - Path=/api/v1/erp/batch/**
            - Method=POST
          filters:
            - StripPrefix=3
            - AddRequestHeader=X-System-Type, ERP
            - AddRequestHeader=X-Processing-Mode, Batch
            - name: RequestSize
              args:
                maxSize: 500MB
          metadata:
            apiCategory: erp
            priority: low
            timeout: 300000

      # Discovery Client Configuration
      discovery:
        locator:
          enabled: true
          lower-case-service-id: true
          include-expression: "true"

# Security Configuration
security:
  oauth2:
    client:
      provider:
        keycloak:
          issuer-uri: https://auth.smartdairy.com/realms/smart-dairy
          token-uri: https://auth.smartdairy.com/realms/smart-dairy/protocol/openid-connect/token
          user-info-uri: https://auth.smartdairy.com/realms/smart-dairy/protocol/openid-connect/userinfo
          jwk-set-uri: https://auth.smartdairy.com/realms/smart-dairy/protocol/openid-connect/certs
      resourceserver:
        jwt:
          issuer-uri: https://auth.smartdairy.com/realms/smart-dairy
          jwk-set-uri: https://auth.smartdairy.com/realms/smart-dairy/protocol/openid-connect/certs
  
  api-key:
    enabled: true
    header-name: X-API-Key
    keys:
      - name: b2b-production
        key: "${B2B_API_KEY}"
        scopes:
          - b2b:read
          - b2b:write
        rate-limit: 1000
      - name: b2c-production
        key: "${B2C_API_KEY}"
        scopes:
          - b2c:read
          - b2c:write
        rate-limit: 2000
      - name: farm-production
        key: "${FARM_API_KEY}"
        scopes:
          - farm:read
          - farm:write
        rate-limit: 500
      - name: mobile-production
        key: "${MOBILE_API_KEY}"
        scopes:
          - mobile:read
          - mobile:write
        rate-limit: 1000

# Rate Limiting Configuration
rate-limiter:
  enabled: true
  redis:
    host: ${REDIS_HOST:localhost}
    port: ${REDIS_PORT:6379}
    password: ${REDIS_PASSWORD}
    ssl: ${REDIS_SSL:true}
    timeout: 2000
    lettuce:
      pool:
        max-active: 100
        max-idle: 50
        min-idle: 10
  
  key-resolvers:
    user-key-resolver:
      type: header
      header-name: X-User-ID
      fallback: anonymous
    
    ip-key-resolver:
      type: remote-address
      mask: /24
    
    b2b-client-key-resolver:
      type: header
      header-name: X-Client-ID
      fallback: default-b2b
    
    farm-key-resolver:
      type: header
      header-name: X-Farm-ID
      fallback: default-farm
    
    device-key-resolver:
      type: header
      header-name: X-Device-ID
      fallback: default-device

# Circuit Breaker Configuration
resilience4j:
  circuitbreaker:
    configs:
      default:
        register-health-indicator: true
        sliding-window-size: 100
        minimum-number-of-calls: 10
        permitted-number-of-calls-in-half-open-state: 5
        automatic-transition-from-open-to-half-open-enabled: true
        wait-duration-in-open-state: 30s
        failure-rate-threshold: 50
        slow-call-rate-threshold: 80
        slow-call-duration-threshold: 2s
        event-consumer-buffer-size: 100
    
    instances:
      erpCircuitBreaker:
        base-config: default
        sliding-window-size: 50
        wait-duration-in-open-state: 60s
      
      b2bOrdersCircuitBreaker:
        base-config: default
        sliding-window-size: 30
        wait-duration-in-open-state: 30s
      
      b2cOrdersCircuitBreaker:
        base-config: default
        sliding-window-size: 50
        wait-duration-in-open-state: 20s
      
      paymentsCircuitBreaker:
        base-config: default
        sliding-window-size: 20
        wait-duration-in-open-state: 30s
        failure-rate-threshold: 30
  
  timelimiter:
    configs:
      default:
        timeout-duration: 5s
        cancel-running-future: true
    
    instances:
      b2bOrdersCircuitBreaker:
        timeout-duration: 10s
      
      b2cOrdersCircuitBreaker:
        timeout-duration: 5s
      
      paymentsCircuitBreaker:
        timeout-duration: 10s
  
  retry:
    configs:
      default:
        max-attempts: 3
        wait-duration: 100ms
        exponential-backoff-multiplier: 2
        retry-exceptions:
          - java.io.IOException
          - java.util.concurrent.TimeoutException
          - org.springframework.web.reactive.function.client.WebClientResponseException$ServiceUnavailable
          - org.springframework.web.reactive.function.client.WebClientResponseException$BadGateway
        ignore-exceptions:
          - org.springframework.web.reactive.function.client.WebClientResponseException$NotFound
          - org.springframework.web.reactive.function.client.WebClientResponseException$BadRequest
    
    instances:
      erpRetry:
        base-config: default
        max-attempts: 5
        wait-duration: 500ms
      
      paymentRetry:
        base-config: default
        max-attempts: 3
        wait-duration: 1000ms

# Cache Configuration
spring:
  cache:
    type: redis
    redis:
      time-to-live: 300000
      cache-null-values: false
      use-key-prefix: true
      key-prefix: "smart-dairy:gateway:"
  
  redis:
    cache:
      caches:
        pricingCache:
          ttl: 300
          max-size: 10000
        inventoryCache:
          ttl: 60
          max-size: 50000
        productCache:
          ttl: 600
          max-size: 100000
        masterDataCache:
          ttl: 3600
          max-size: 5000
        b2bRequestCache:
          ttl: 60
          max-size: 1000

# Metrics and Monitoring
management:
  endpoints:
    web:
      exposure:
        include: health,info,metrics,prometheus,gateway,env,configprops
      base-path: /actuator
  
  endpoint:
    health:
      show-details: when_authorized
      show-components: always
      probes:
        enabled: true
    
    metrics:
      enabled: true
      export:
        prometheus:
          enabled: true
    
    gateway:
      enabled: true
  
  metrics:
    distribution:
      percentiles-histogram:
        http.server.requests: true
        gateway.requests: true
      percentiles:
        http.server.requests: 0.5, 0.9, 0.95, 0.99
        gateway.requests: 0.5, 0.9, 0.95, 0.99
      slo:
        http.server.requests: 100ms, 200ms, 500ms, 1s, 2s, 5s
        gateway.requests: 50ms, 100ms, 200ms, 500ms, 1s
    
    tags:
      application: ${spring.application.name}
      environment: ${spring.profiles.active:default}

# Logging Configuration
logging:
  level:
    org.springframework.cloud.gateway: DEBUG
    org.springframework.security: DEBUG
    com.smartdairy.gateway: DEBUG
    io.github.resilience4j: DEBUG
  
  pattern:
    console: "%d{yyyy-MM-dd HH:mm:ss.SSS} [%thread] [%X{traceId:-},%X{spanId:-}] %-5level %logger{36} - %msg%n"
```

#### 2.1.3 Gateway Implementation Code

```java
// ApiGatewayApplication.java
package com.smartdairy.gateway;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.cache.annotation.EnableCaching;
import org.springframework.cloud.client.discovery.EnableDiscoveryClient;
import org.springframework.context.annotation.Bean;
import org.springframework.data.redis.connection.ReactiveRedisConnectionFactory;
import org.springframework.data.redis.core.ReactiveRedisTemplate;
import org.springframework.data.redis.serializer.RedisSerializationContext;

@SpringBootApplication
@EnableDiscoveryClient
@EnableCaching
public class ApiGatewayApplication {
    public static void main(String[] args) {
        SpringApplication.run(ApiGatewayApplication.class, args);
    }
    
    @Bean
    public ReactiveRedisTemplate<String, String> reactiveRedisTemplate(
            ReactiveRedisConnectionFactory factory) {
        RedisSerializationContext<String, String> context = RedisSerializationContext
            .<String, String>newSerializationContext(new StringRedisSerializer())
            .hashValue(new GenericJackson2JsonRedisSerializer())
            .build();
        return new ReactiveRedisTemplate<>(factory, context);
    }
}
```

```java
// SecurityConfig.java - Authentication and Authorization
package com.smartdairy.gateway.config;

import com.smartdairy.gateway.filter.ApiKeyAuthenticationFilter;
import com.smartdairy.gateway.filter.JwtAuthenticationFilter;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.config.annotation.web.reactive.EnableWebFluxSecurity;
import org.springframework.security.config.web.server.SecurityWebFiltersOrder;
import org.springframework.security.config.web.server.ServerHttpSecurity;
import org.springframework.security.web.server.SecurityWebFilterChain;
import org.springframework.security.web.server.authentication.AuthenticationWebFilter;

@Configuration
@EnableWebFluxSecurity
public class SecurityConfig {
    
    @Bean
    public SecurityWebFilterChain springSecurityFilterChain(
            ServerHttpSecurity http,
            JwtAuthenticationFilter jwtFilter,
            ApiKeyAuthenticationFilter apiKeyFilter) {
        
        return http
            .csrf().disable()
            .cors().and()
            .authorizeExchange()
                // Public health check endpoint
                .pathMatchers("/actuator/health", "/actuator/info").permitAll()
                // OpenAPI documentation
                .pathMatchers("/v3/api-docs/**", "/swagger-ui/**", "/swagger-ui.html").permitAll()
                // Fallback endpoints
                .pathMatchers("/fallback/**").permitAll()
                // Authenticated routes
                .pathMatchers("/api/v1/b2b/**").hasAnyAuthority("SCOPE_b2b:read", "SCOPE_b2b:write", "API_KEY_B2B")
                .pathMatchers("/api/v1/b2c/**").hasAnyAuthority("SCOPE_b2c:read", "SCOPE_b2c:write", "API_KEY_B2C")
                .pathMatchers("/api/v1/farm/**").hasAnyAuthority("SCOPE_farm:read", "SCOPE_farm:write", "API_KEY_FARM")
                .pathMatchers("/api/v1/mobile/**").hasAnyAuthority("SCOPE_mobile:read", "SCOPE_mobile:write", "API_KEY_MOBILE")
                .pathMatchers("/api/v1/erp/**").hasAnyAuthority("SCOPE_erp:read", "SCOPE_erp:write", "API_KEY_ERP")
                // Admin routes
                .pathMatchers("/actuator/**").hasAuthority("ROLE_ADMIN")
                .anyExchange().authenticated()
            .and()
            .addFilterAt(apiKeyFilter, SecurityWebFiltersOrder.AUTHENTICATION)
            .addFilterAt(jwtFilter, SecurityWebFiltersOrder.AUTHENTICATION)
            .exceptionHandling()
                .authenticationEntryPoint((exchange, ex) -> {
                    exchange.getResponse().setStatusCode(HttpStatus.UNAUTHORIZED);
                    return exchange.getResponse().setComplete();
                })
                .accessDeniedPage("/fallback/access-denied")
            .and()
            .build();
    }
    
    @Bean
    public ReactiveJwtDecoder jwtDecoder() {
        NimbusReactiveJwtDecoder decoder = NimbusReactiveJwtDecoder
            .withJwkSetUri("https://auth.smartdairy.com/realms/smart-dairy/protocol/openid-connect/certs")
            .build();
        
        OAuth2TokenValidator<Jwt> withIssuer = JwtValidators.createDefaultWithIssuer(
            "https://auth.smartdairy.com/realms/smart-dairy"
        );
        decoder.setJwtValidator(withIssuer);
        
        return decoder;
    }
}
```

```java
// RateLimiterConfig.java - Advanced Rate Limiting
package com.smartdairy.gateway.config;

import org.springframework.cloud.gateway.filter.ratelimit.KeyResolver;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import reactor.core.publisher.Mono;

@Configuration
public class RateLimiterConfig {
    
    @Bean
    public KeyResolver userKeyResolver() {
        return exchange -> {
            String userId = exchange.getRequest().getHeaders().getFirst("X-User-ID");
            if (userId != null && !userId.isEmpty()) {
                return Mono.just(userId);
            }
            // Fallback to API key
            String apiKey = exchange.getRequest().getHeaders().getFirst("X-API-Key");
            if (apiKey != null && !apiKey.isEmpty()) {
                return Mono.just("apikey:" + apiKey);
            }
            // Fallback to IP address
            return Mono.just(exchange.getRequest().getRemoteAddress().getAddress().getHostAddress());
        };
    }
    
    @Bean
    public KeyResolver ipKeyResolver() {
        return exchange -> Mono.just(
            exchange.getRequest().getRemoteAddress().getAddress().getHostAddress()
        );
    }
    
    @Bean
    public KeyResolver b2bClientKeyResolver() {
        return exchange -> {
            String clientId = exchange.getRequest().getHeaders().getFirst("X-Client-ID");
            if (clientId != null && !clientId.isEmpty()) {
                return Mono.just("b2b:" + clientId);
            }
            return userKeyResolver().resolve(exchange);
        };
    }
    
    @Bean
    public KeyResolver farmKeyResolver() {
        return exchange -> {
            String farmId = exchange.getRequest().getHeaders().getFirst("X-Farm-ID");
            if (farmId != null && !farmId.isEmpty()) {
                return Mono.just("farm:" + farmId);
            }
            return userKeyResolver().resolve(exchange);
        };
    }
    
    @Bean
    public KeyResolver deviceKeyResolver() {
        return exchange -> {
            String deviceId = exchange.getRequest().getHeaders().getFirst("X-Device-ID");
            if (deviceId != null && !deviceId.isEmpty()) {
                return Mono.just("device:" + deviceId);
            }
            return userKeyResolver().resolve(exchange);
        };
    }
}
```



### 2.2 Integration Patterns

#### 2.2.1 Pattern Catalog

The Smart Dairy ERP Integration Layer implements a comprehensive set of enterprise integration patterns to handle diverse integration scenarios:

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                      ENTERPRISE INTEGRATION PATTERNS                                │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                       │
│  ┌───────────────────────────────────────────────────────────────────────────────┐  │
│  │                          MESSAGING PATTERNS                                    │  │
│  │                                                                                │  │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐               │  │
│  │  │   Point-to-Point│  │  Publish-Sub    │  │   Message Bus   │               │  │
│  │  │   Channel       │  │  scribe         │  │                 │               │  │
│  │  │                 │  │                 │  │                 │               │  │
│  │  │  Use: Direct    │  │  Use: Events    │  │  Use: Multiple  │               │  │
│  │  │  req/resp       │  │  broadcasting   │  │  subscribers    │               │  │
│  │  └─────────────────┘  └─────────────────┘  └─────────────────┘               │  │
│  │                                                                                │  │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐               │  │
│  │  │   Competing     │  │  Request-Reply  │  │   Return Address │               │  │
│  │  │   Consumers     │  │                 │  │                  │               │  │
│  │  │                 │  │                 │  │                  │               │  │
│  │  │  Use: Load      │  │  Use: Sync      │  │  Use: Dynamic    │               │  │
│  │  │  balancing      │  │  operations     │  │  response dest   │               │  │
│  │  └─────────────────┘  └─────────────────┘  └─────────────────┘               │  │
│  └───────────────────────────────────────────────────────────────────────────────┘  │
│                                                                                       │
│  ┌───────────────────────────────────────────────────────────────────────────────┐  │
│  │                      TRANSFORMATION PATTERNS                                   │  │
│  │                                                                                │  │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐               │  │
│  │  │   Message       │  │   Content       │  │   Normalizer    │               │  │
│  │  │   Translator    │  │   Enricher      │  │                 │               │  │
│  │  │                 │  │                 │  │                 │               │  │
│  │  │  Use: Format    │  │  Use: Add       │  │  Use: Canonical │               │  │
│  │  │  conversion     │  │  context data   │  │  data model     │               │  │
│  │  └─────────────────┘  └─────────────────┘  └─────────────────┘               │  │
│  │                                                                                │  │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐               │  │
│  │  │   Claim Check   │  │   Aggregator    │  │   Splitter      │               │  │
│  │  │                 │  │                 │  │                 │               │  │
│  │  │  Use: Large     │  │  Use: Combine   │  │  Use: Break     │               │  │
│  │  │  message store  │  │  partial results│  │  into pieces    │               │  │
│  │  └─────────────────┘  └─────────────────┘  └─────────────────┘               │  │
│  └───────────────────────────────────────────────────────────────────────────────┘  │
│                                                                                       │
│  ┌───────────────────────────────────────────────────────────────────────────────┐  │
│  │                       ROUTING PATTERNS                                         │  │
│  │                                                                                │  │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐               │  │
│  │  │   Content-Based │  │   Dynamic       │  │   Recipient     │               │  │
│  │  │   Router        │  │   Router        │  │   List          │               │  │
│  │  │                 │  │                 │  │                 │               │  │
│  │  │  Use: Route by  │  │  Use: Runtime   │  │  Use: Multiple  │               │  │
│  │  │  message content│  │  routing rules  │  │  recipients     │               │  │
│  │  └─────────────────┘  └─────────────────┘  └─────────────────┘               │  │
│  │                                                                                │  │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐               │  │
│  │  │   Splitter      │  │   Aggregator    │  │   Resequencer   │               │  │
│  │  │   Router        │  │   Router        │  │                 │               │  │
│  │  │                 │  │                 │  │                 │               │  │
│  │  │  Use: Parallel  │  │  Use: Combine   │  │  Use: Message   │               │  │
│  │  │  processing     │  │  responses      │  │  ordering       │               │  │
│  │  └─────────────────┘  └─────────────────┘  └─────────────────┘               │  │
│  └───────────────────────────────────────────────────────────────────────────────┘  │
│                                                                                       │
│  ┌───────────────────────────────────────────────────────────────────────────────┐  │
│  │                      SYSTEM MANAGEMENT PATTERNS                                │  │
│  │                                                                                │  │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐               │  │
│  │  │   Circuit       │  │   Retry         │  │   Timeout       │               │  │
│  │  │   Breaker       │  │                 │  │                 │               │  │
│  │  │                 │  │                 │  │                 │               │  │
│  │  │  Use: Fault     │  │  Use: Transient │  │  Use: Prevent   │               │  │
│  │  │  isolation      │  │  failure recovery│  │  hung requests  │               │  │
│  │  └─────────────────┘  └─────────────────┘  └─────────────────┘               │  │
│  │                                                                                │  │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐               │  │
│  │  │   Idempotent    │  │   Transaction   │  │   Compensating  │               │  │
│  │  │   Receiver      │  │   Manager       │  │   Transaction   │               │  │
│  │  │                 │  │                 │  │                 │               │  │
│  │  │  Use: Duplicate │  │  Use: ACID      │  │  Use: Undo      │               │  │
│  │  │  elimination    │  │  across systems │  │  operations     │               │  │
│  │  └─────────────────┘  └─────────────────┘  └─────────────────┘               │  │
│  └───────────────────────────────────────────────────────────────────────────────┘  │
│                                                                                       │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

#### 2.2.2 Pattern Implementation Matrix

| Pattern | Implementation | Technology | Use Case |
|---------|---------------|------------|----------|
| API Gateway | Spring Cloud Gateway | Java/WebFlux | Centralized API management |
| Circuit Breaker | Resilience4j | Java | Fault tolerance |
| Retry | Resilience4j + Custom | Java | Transient failure recovery |
| Rate Limiter | Redis + Bucket4j | Redis/Java | Traffic control |
| Message Queue | RabbitMQ | AMQP | Async processing |
| Event Streaming | Apache Kafka | Kafka | Event sourcing |
| Cache | Redis | Redis | Performance optimization |
| Saga | Orchestration pattern | Java/Camunda | Distributed transactions |
| Outbox | Transactional outbox | PostgreSQL + Debezium | Reliable messaging |
| CQRS | Separate read/write models | PostgreSQL + Elasticsearch | Query optimization |

#### 2.2.3 Implementation Code Examples

```java
// Circuit Breaker Pattern Implementation
package com.smartdairy.gateway.resilience;

import io.github.resilience4j.circuitbreaker.CircuitBreaker;
import io.github.resilience4j.circuitbreaker.CircuitBreakerConfig;
import io.github.resilience4j.circuitbreaker.CircuitBreakerRegistry;
import io.github.resilience4j.reactor.circuitbreaker.operator.CircuitBreakerOperator;
import org.springframework.stereotype.Component;
import reactor.core.publisher.Mono;

import java.time.Duration;
import java.util.concurrent.ConcurrentHashMap;

@Component
public class GatewayCircuitBreaker {
    
    private final CircuitBreakerRegistry registry;
    private final ConcurrentHashMap<String, CircuitBreaker> circuitBreakers = new ConcurrentHashMap<>();
    
    public GatewayCircuitBreaker() {
        CircuitBreakerConfig defaultConfig = CircuitBreakerConfig.custom()
            .failureRateThreshold(50)
            .slowCallRateThreshold(80)
            .slowCallDurationThreshold(Duration.ofSeconds(2))
            .permittedNumberOfCallsInHalfOpenState(5)
            .slidingWindowSize(100)
            .minimumNumberOfCalls(10)
            .waitDurationInOpenState(Duration.ofSeconds(30))
            .automaticTransitionFromOpenToHalfOpenEnabled(true)
            .recordExceptions(
                IOException.class,
                TimeoutException.class,
                WebClientResponseException.ServiceUnavailable.class,
                WebClientResponseException.BadGateway.class
            )
            .ignoreExceptions(
                WebClientResponseException.NotFound.class,
                WebClientResponseException.BadRequest.class
            )
            .build();
        
        this.registry = CircuitBreakerRegistry.of(defaultConfig);
    }
    
    public CircuitBreaker getCircuitBreaker(String name) {
        return circuitBreakers.computeIfAbsent(name, 
            key -> registry.circuitBreaker(key));
    }
    
    public <T> Mono<T> executeWithCircuitBreaker(String name, Mono<T> operation) {
        CircuitBreaker circuitBreaker = getCircuitBreaker(name);
        return operation
            .transformDeferred(CircuitBreakerOperator.of(circuitBreaker))
            .onErrorResume(throwable -> {
                if (circuitBreaker.getState() == CircuitBreaker.State.OPEN) {
                    return Mono.error(new CircuitBreakerOpenException(
                        "Circuit breaker is OPEN for: " + name));
                }
                return Mono.error(throwable);
            });
    }
    
    public CircuitBreaker.Metrics getMetrics(String name) {
        return getCircuitBreaker(name).getMetrics();
    }
}
```

```java
// Retry Pattern Implementation
package com.smartdairy.gateway.resilience;

import io.github.resilience4j.retry.Retry;
import io.github.resilience4j.retry.RetryConfig;
import io.github.resilience4j.retry.RetryRegistry;
import io.github.resilience4j.reactor.retry.RetryOperator;
import org.springframework.stereotype.Component;
import reactor.core.publisher.Mono;

import java.time.Duration;
import java.util.concurrent.ConcurrentHashMap;

@Component
public class GatewayRetry {
    
    private final RetryRegistry registry;
    private final ConcurrentHashMap<String, Retry> retries = new ConcurrentHashMap<>();
    
    public GatewayRetry() {
        RetryConfig defaultConfig = RetryConfig.custom()
            .maxAttempts(3)
            .waitDuration(Duration.ofMillis(100))
            .exponentialBackoffMultiplier(2)
            .retryExceptions(
                IOException.class,
                TimeoutException.class,
                WebClientResponseException.ServiceUnavailable.class,
                WebClientResponseException.BadGateway.class,
                WebClientResponseException.GatewayTimeout.class
            )
            .ignoreExceptions(
                WebClientResponseException.NotFound.class,
                WebClientResponseException.BadRequest.class,
                WebClientResponseException.Unauthorized.class,
                WebClientResponseException.Forbidden.class,
                BusinessValidationException.class
            )
            .build();
        
        this.registry = RetryRegistry.of(defaultConfig);
    }
    
    public Retry getRetry(String name, RetryConfig customConfig) {
        return retries.computeIfAbsent(name, key -> {
            if (customConfig != null) {
                return registry.retry(key, customConfig);
            }
            return registry.retry(key);
        });
    }
    
    public <T> Mono<T> executeWithRetry(String name, Mono<T> operation) {
        return executeWithRetry(name, operation, null);
    }
    
    public <T> Mono<T> executeWithRetry(String name, Mono<T> operation, RetryConfig config) {
        Retry retry = getRetry(name, config);
        return operation
            .transformDeferred(RetryOperator.of(retry))
            .doOnError(error -> {
                // Log retry exhaustion
                System.err.println("Retry exhausted for " + name + ": " + error.getMessage());
            });
    }
}
```

```java
// Bulkhead Pattern Implementation
package com.smartdairy.gateway.resilience;

import io.github.resilience4j.bulkhead.Bulkhead;
import io.github.resilience4j.bulkhead.BulkheadConfig;
import io.github.resilience4j.bulkhead.BulkheadRegistry;
import io.github.resilience4j.reactor.bulkhead.BulkheadOperator;
import org.springframework.stereotype.Component;
import reactor.core.publisher.Mono;

import java.time.Duration;
import java.util.concurrent.ConcurrentHashMap;

@Component
public class GatewayBulkhead {
    
    private final BulkheadRegistry registry;
    private final ConcurrentHashMap<String, Bulkhead> bulkheads = new ConcurrentHashMap<>();
    
    public GatewayBulkhead() {
        BulkheadConfig defaultConfig = BulkheadConfig.custom()
            .maxConcurrentCalls(50)
            .maxWaitDuration(Duration.ofMillis(500))
            .build();
        
        this.registry = BulkheadRegistry.of(defaultConfig);
    }
    
    public Bulkhead getBulkhead(String name, BulkheadConfig config) {
        return bulkheads.computeIfAbsent(name, key -> {
            if (config != null) {
                return registry.bulkhead(key, config);
            }
            return registry.bulkhead(key);
        });
    }
    
    public <T> Mono<T> executeWithBulkhead(String name, Mono<T> operation) {
        return executeWithBulkhead(name, operation, null);
    }
    
    public <T> Mono<T> executeWithBulkhead(String name, Mono<T> operation, BulkheadConfig config) {
        Bulkhead bulkhead = getBulkhead(name, config);
        return operation
            .transformDeferred(BulkheadOperator.of(bulkhead))
            .onErrorResume(BulkheadFullException.class, ex -> {
                return Mono.error(new ServiceUnavailableException(
                    "Service " + name + " is currently overloaded. Please try again later."));
            });
    }
}
```

### 2.3 Data Flow Diagrams

#### 2.3.1 High-Level Data Flow Architecture

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────┐
│                            SMART DAIRY ERP INTEGRATION DATA FLOW                                │
├─────────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                                   │
│   ┌─────────────────────────────────────────────────────────────────────────────────────────┐   │
│   │                                    CLIENT LAYER                                          │   │
│   │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐                │   │
│   │  │   B2B Portal │  │   B2C E-com  │  │   Farm Mgmt  │  │   Mobile Apps│                │   │
│   │  │              │  │              │  │              │  │              │                │   │
│   │  │  Web App     │  │  Web/Mobile  │  │  Web App     │  │  iOS/Android │                │   │
│   │  │  React SPA   │  │  Store       │  │  Angular     │  │  React Native│                │   │
│   │  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘                │   │
│   │         │                 │                 │                 │                        │   │
│   └─────────┼─────────────────┼─────────────────┼─────────────────┼────────────────────────┘   │
│             │                 │                 │                 │                           │
│             │                 │                 │                 │                           │
│             ▼                 ▼                 ▼                 ▼                           │
│   ┌─────────────────────────────────────────────────────────────────────────────────────────┐   │
│   │                              API GATEWAY LAYER                                           │   │
│   │  ┌─────────────────────────────────────────────────────────────────────────────────┐   │   │
│   │  │                    SPRING CLOUD GATEWAY                                          │   │   │
│   │  │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐          │   │   │
│   │  │  │  Auth    │  │  Rate    │  │  Route   │  │  Circuit │  │  Cache   │          │   │   │
│   │  │  │  Filter  │  │  Limit   │  │  Engine  │  │  Breaker │  │  Layer   │          │   │   │
│   │  │  └──────────┘  └──────────┘  └──────────┘  └──────────┘  └──────────┘          │   │   │
│   │  └─────────────────────────────────────────────────────────────────────────────────┘   │   │
│   └─────────────────────────────────────────────────────────────────────────────────────────┘   │
│             │                 │                 │                 │                           │
│             │                 │                 │                 │                           │
│             ▼                 ▼                 ▼                 ▼                           │
│   ┌─────────────────────────────────────────────────────────────────────────────────────────┐   │
│   │                           CONNECTOR SERVICE LAYER                                        │   │
│   │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐                │   │
│   │  │ B2B Connector│  │ B2C Connector│  │Farm Connector│  │Mobile Connect│                │   │
│   │  │              │  │              │  │              │  │              │                │   │
│   │  │  ┌────────┐  │  │  ┌────────┐  │  │  ┌────────┐  │  │  ┌────────┐  │                │   │
│   │  │  │Business│  │  │  │Business│  │  │  │Business│  │  │  │Business│  │                │   │
│   │  │  │Logic   │  │  │  │Logic   │  │  │  │Logic   │  │  │  │Logic   │  │                │   │
│   │  │  └────────┘  │  │  └────────┘  │  │  └────────┘  │  │  └────────┘  │                │   │
│   │  │  ┌────────┐  │  │  ┌────────┐  │  │  ┌────────┐  │  │  ┌────────┐  │                │   │
│   │  │  │Transform│  │  │  │Transform│  │  │  │Transform│  │  │  │Transform│  │                │   │
│   │  │  │Layer   │  │  │  │Layer   │  │  │  │Layer   │  │  │  │Layer   │  │                │   │
│   │  │  └────────┘  │  │  └────────┘  │  │  └────────┘  │  │  └────────┘  │                │   │
│   │  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘                │   │
│   │         │                 │                 │                 │                        │   │
│   └─────────┼─────────────────┼─────────────────┼─────────────────┼────────────────────────┘   │
│             │                 │                 │                 │                           │
│             └─────────────────┴─────────────────┴─────────────────┘                           │
│                                           │                                                    │
│                                           ▼                                                    │
│   ┌─────────────────────────────────────────────────────────────────────────────────────────┐   │
│   │                           MESSAGE QUEUE LAYER (EVENT BUS)                                │   │
│   │  ┌─────────────────────────────────────────────────────────────────────────────────┐   │   │
│   │  │                        APACHE KAFKA + RABBITMQ                                   │   │   │
│   │  │                                                                                  │   │   │
│   │  │   Topics:                                                                         │   │   │
│   │  │   ├─ erp.orders.created                                                          │   │   │
│   │  │   ├─ erp.orders.updated                                                          │   │   │
│   │  │   ├─ erp.inventory.changed                                                       │   │   │
│   │  │   ├─ erp.customers.sync                                                          │   │   │
│   │  │   ├─ erp.products.sync                                                           │   │   │
│   │  │   ├─ farm.animal.events                                                          │   │   │
│   │  │   ├─ farm.production.records                                                     │   │   │
│   │  │   ├─ farm.health.alerts                                                          │   │   │
│   │  │   └─ farm.iot.sensor.data                                                        │   │   │
│   │  │                                                                                  │   │   │
│   │  │   Queues:                                                                         │   │   │
│   │  │   ├─ b2b.order.queue                                                             │   │   │
│   │  │   ├─ b2c.order.queue                                                             │   │   │
│   │  │   ├─ b2c.payment.queue                                                           │   │   │
│   │  │   ├─ farm.sync.queue                                                             │   │   │
│   │  │   └─ mobile.offline.queue                                                        │   │   │
│   │  └─────────────────────────────────────────────────────────────────────────────────┘   │   │
│   └─────────────────────────────────────────────────────────────────────────────────────────┘   │
│             │                 │                 │                 │                           │
│             └─────────────────┴─────────────────┴─────────────────┘                           │
│                                           │                                                    │
│                                           ▼                                                    │
│   ┌─────────────────────────────────────────────────────────────────────────────────────────┐   │
│   │                              SYNC ENGINE LAYER                                           │   │
│   │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐                │   │
│   │  │  Real-time   │  │   Batch      │  │   Conflict   │  │   Change     │                │   │
│   │  │  Sync Worker │  │   Processor  │  │   Resolver   │  │   Tracker    │                │   │
│   │  │              │  │              │  │              │  │              │                │   │
│   │  │  ┌────────┐  │  │  ┌────────┐  │  │  ┌────────┐  │  │  ┌────────┐  │                │   │
│   │  │  │ Event  │  │  │  │Import/ │  │  │  │Last   │  │  │  │CDC     │  │                │   │
│   │  │  │Handlers│  │  │  │Export  │  │  │  │Write  │  │  │  │Capture │  │                │   │
│   │  │  │        │  │  │  │        │  │  │  │Wins   │  │  │  │        │  │                │   │
│   │  │  └────────┘  │  │  └────────┘  │  │  └────────┘  │  │  └────────┘  │                │   │
│   │  └──────┬───────┘  └──────┬───────┘  └──────────────┘  └──────────────┘                │   │
│   │         │                 │                                                             │   │
│   └─────────┼─────────────────┼─────────────────────────────────────────────────────────────┘   │
│             │                 │                                                                  │
│             ▼                 ▼                                                                  │
│   ┌─────────────────────────────────────────────────────────────────────────────────────────┐   │
│   │                               ERP ADAPTER LAYER                                          │   │
│   │  ┌─────────────────────────────────────────────────────────────────────────────────┐   │   │
│   │  │                         ERP INTEGRATION ADAPTER                                  │   │   │
│   │  │                                                                                  │   │   │
│   │  │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐          │   │   │
│   │  │  │  REST    │  │   SOAP   │  │  OData   │  │   EDI    │  │   File   │          │   │   │
│   │  │  │  Client  │  │  Client  │  │  Client  │  │  Handler │  │  Import  │          │   │   │
│   │  │  └──────────┘  └──────────┘  └──────────┘  └──────────┘  └──────────┘          │   │   │
│   │  │                                                                                  │   │   │
│   │  │  Protocol Translators:                                                            │   │   │
│   │  │  ├─ JSON ↔ XML                                                                    │   │   │
│   │  │  ├─ REST ↔ SOAP                                                                   │   │   │
│   │  │  ├─ GraphQL ↔ OData                                                               │   │   │
│   │  │  └─ Custom formats                                                                │   │   │
│   │  └─────────────────────────────────────────────────────────────────────────────────┘   │   │
│   └─────────────────────────────────────────────────────────────────────────────────────────┘   │
│                                           │                                                      │
│                                           ▼                                                      │
│   ┌─────────────────────────────────────────────────────────────────────────────────────────┐   │
│   │                                 CORE ERP SYSTEM                                          │   │
│   │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐   │   │
│   │  │   GL     │  │   AP     │  │   AR     │  │Inventory │  │  Sales   │  │Purchasing│   │   │
│   │  │  Module  │  │  Module  │  │  Module  │  │  Module  │  │  Module  │  │  Module  │   │   │
│   │  └──────────┘  └──────────┘  └──────────┘  └──────────┘  └──────────┘  └──────────┘   │   │
│   │                                                                                        │   │
│   │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐   │   │
│   │  │   CRM    │  │  HCM     │  │Manufact'g│  │  WMS     │  │  TMS     │  │  Farm    │   │   │
│   │  │  Module  │  │  Module  │  │  Module  │  │  Module  │  │  Module  │  │  Module  │   │   │
│   │  └──────────┘  └──────────┘  └──────────┘  └──────────┘  └──────────┘  └──────────┘   │   │
│   └─────────────────────────────────────────────────────────────────────────────────────────┘   │
│                                                                                                   │
└─────────────────────────────────────────────────────────────────────────────────────────────────┘
```

#### 2.3.2 Order Processing Data Flow

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────┐
│                              ORDER PROCESSING DATA FLOW                                         │
├─────────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                                   │
│   SCENARIO: B2B Customer Places Bulk Milk Order                                                  │
│                                                                                                   │
│   Step 1: Order Submission                                                                         │
│   ═══════════════════════                                                                          │
│   ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐              │
│   │   B2B    │────▶│   API    │────▶│   B2B    │────▶│  Message │────▶│  Order   │              │
│   │  Portal  │     │ Gateway  │     │ Connector│     │  Queue   │     │  Worker  │              │
│   └──────────┘     └──────────┘     └──────────┘     └──────────┘     └────┬─────┘              │
│                                                                            │                      │
│   Step 2: Validation & Enrichment                                                                    │
│   ════════════════════════════════                                                                   │
│                                                                            ▼                      │
│   ┌────────────────────────────────────────────────────────────────────────────────────────┐      │
│   │                         VALIDATION & ENRICHMENT PIPELINE                                │      │
│   │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐   │      │
│   │  │ Customer │─▶│ Contract │─▶│  Credit  │─▶│  Price   │─▶│ Inventory│─▶│ Delivery │   │      │
│   │  │  Auth    │  │  Terms   │  │  Check   │  │  Lookup  │  │  Check   │  │  Date    │   │      │
│   │  └──────────┘  └──────────┘  └──────────┘  └──────────┘  └──────────┘  └──────────┘   │      │
│   │                                                                                        │      │
│   │  Validation Rules:                                                                      │      │
│   │  ✓ Customer account active                                                             │      │
│   │  ✓ Contract pricing agreement valid                                                    │      │
│   │  ✓ Credit limit not exceeded                                                           │      │
│   │  ✓ Volume pricing tiers applied                                                        │      │
│   │  ✓ Inventory available for requested SKU                                               │      │
│   │  ✓ Delivery date feasible                                                              │      │
│   └────────────────────────────────────────────────────────────────────────────────────────┘      │
│                                                                            │                      │
│   Step 3: ERP Integration                                                                            │
│   ════════════════════════                                                                           │
│                                                                            ▼                      │
│   ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐              │
│   │  Order   │────▶│   ERP    │────▶│  Sales   │────▶│Inventory │────▶│  GL/JE   │              │
│   │  Worker  │     │  Adapter │     │  Order   │     │  Reserve │     │  Entry   │              │
│   └──────────┘     └──────────┘     └──────────┘     └──────────┘     └──────────┘              │
│                                                                            │                      │
│   Step 4: Response Propagation                                                                       │
│   ════════════════════════════                                                                       │
│                                                                            ▼                      │
│   ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐              │
│   │  Event   │◀────│  Status  │◀────│  Order   │◀────│   ERP    │◀────│   ERP    │              │
│   │  Bus     │     │  Update  │     │ Confirm  │     │ Confirm  │     │  Commit  │              │
│   └────┬─────┘     └──────────┘     └──────────┘     └──────────┘     └──────────┘              │
│        │                                                                                          │
│        │ Step 5: Multi-Channel Notification                                                          │
│        ▼                                                                                          │
│   ┌────────────────────────────────────────────────────────────────────────────────────────┐      │
│   │                            NOTIFICATION DISPATCH                                        │      │
│   │                                                                                        │      │
│   │    ┌──────────┐      ┌──────────┐      ┌──────────┐      ┌──────────┐                │      │
│   │    │   B2B    │      │   ERP    │      │   WMS    │      │   TMS    │                │      │
│   │    │  Portal  │      │  System  │      │  System  │      │  System  │                │      │
│   │    │  (User)  │      │  (Admin) │      │(Fulfill) │      │(Delivery)│                │      │
│   │    └──────────┘      └──────────┘      └──────────┘      └──────────┘                │      │
│   │                                                                                        │      │
│   │    Notifications:                                                                       │      │
│   │    • Order Confirmation Email                                                          │      │
│   │    • Dashboard Update                                                                  │      │
│   │    • Pick List Generation                                                              │      │
│   │    • Delivery Schedule Update                                                          │      │
│   └────────────────────────────────────────────────────────────────────────────────────────┘      │
│                                                                                                   │
└─────────────────────────────────────────────────────────────────────────────────────────────────┘
```

#### 2.3.3 Farm Data Synchronization Flow

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────┐
│                              FARM DATA SYNCHRONIZATION FLOW                                     │
├─────────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                                   │
│   REAL-TIME SYNC (IoT Sensors)                                                                     │
│   ════════════════════════════                                                                     │
│                                                                                                   │
│   ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐              │
│   │   IoT    │────▶│   MQTT   │────▶│  Farm    │────▶│  Kafka   │────▶│  Stream  │              │
│   │ Sensors  │     │  Broker  │     │ Connector│     │  Topic   │     │ Processor│              │
│   │          │     │          │     │          │     │          │     │          │              │
│   │ • Temp   │     │          │     │          │     │farm.iot  │     │          │              │
│   │ • Humidity│     │          │     │          │     │.sensors  │     │          │              │
│   │ • Milk Q │     │          │     │          │     │          │     │          │              │
│   └──────────┘     └──────────┘     └──────────┘     └──────────┘     └────┬─────┘              │
│                                                                            │                      │
│                                                                            ▼                      │
│   ┌────────────────────────────────────────────────────────────────────────────────────────┐      │
│   │                         STREAM PROCESSING (Apache Flink/Kafka Streams)                  │      │
│   │                                                                                        │      │
│   │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐                 │      │
│   │  │  Window  │─▶│ Aggregate│─▶│ Anomaly  │─▶│  Alert   │─▶│   Sink   │                 │      │
│   │  │  Events  │  │  Metrics │  │ Detection│  │  Rules   │  │  to DB   │                 │      │
│   │  └──────────┘  └──────────┘  └──────────┘  └──────────┘  └──────────┘                 │      │
│   │                                                                                        │      │
│   └────────────────────────────────────────────────────────────────────────────────────────┘      │
│                                                                            │                      │
│                                                                            ▼                      │
│   ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐              │
│   │   Farm   │◀────│   ERP    │◀────│  Change  │◀────│  Batch   │◀────│  Real-   │              │
│   │  Module  │     │  Adapter │     │  Tracker │     │  Ingest  │     │  time    │              │
│   └──────────┘     └──────────┘     └──────────┘     └──────────┘     └──────────┘              │
│                                                                                                   │
│   BATCH SYNC (Daily Operations)                                                                    │
│   ═════════════════════════════                                                                    │
│                                                                                                   │
│   ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐              │
│   │  Farm    │────▶│  Mobile  │────▶│   Sync   │────▶│  Message │────▶│  Batch   │              │
│   │  Staff   │     │   App    │     │  Queue   │     │  Router  │     │ Processor│              │
│   │          │     │ (Offline)│     │          │     │          │     │          │              │
│   │ • Health │     │          │     │          │     │          │     │          │              │
│   │ • Feed   │     │          │     │          │     │          │     │          │              │
│   │ • Breed  │     │          │     │          │     │          │     │          │              │
│   └──────────┘     └──────────┘     └──────────┘     └──────────┘     └────┬─────┘              │
│                                                                            │                      │
│                                                                            ▼                      │
│   ┌────────────────────────────────────────────────────────────────────────────────────────┐      │
│   │                            BATCH PROCESSING PIPELINE                                    │      │
│   │                                                                                        │      │
│   │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐                 │      │
│   │  │ Extract  │─▶│ Validate │─▶│Transform │─▶│ Enrich   │─▶│  Load    │                 │      │
│   │  │          │  │          │  │          │  │          │  │          │                 │      │
│   │  │ • Parse  │  │ • Schema │  │ • Map    │  │ • Lookup │  │ • Batch  │                 │      │
│   │  │ • Filter │  │ • Rules  │  │ • Convert│  │ • Calc   │  │   Insert │                 │      │
│   │  │ • Sort   │  │ • Custom │  │ • Clean  │  │ • Derive │  │ • Commit │                 │      │
│   │  └──────────┘  └──────────┘  └──────────┘  └──────────┘  └──────────┘                 │      │
│   │                                                                                        │      │
│   └────────────────────────────────────────────────────────────────────────────────────────┘      │
│                                                                            │                      │
│                                                                            ▼                      │
│   ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐              │
│   │   ERP    │◀────│  Change  │◀────│  Audit   │◀────│  Commit  │◀────│  Batch   │              │
│   │  Confirm │     │  Tracker │     │   Log    │     │  to ERP  │     │  Results │              │
│   └──────────┘     └──────────┘     └──────────┘     └──────────┘     └──────────┘              │
│                                                                                                   │
└─────────────────────────────────────────────────────────────────────────────────────────────────┘
```

### 2.4 Sync Engine Design

#### 2.4.1 Synchronization Architecture

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────┐
│                              SYNCHRONIZATION ENGINE ARCHITECTURE                                │
├─────────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                                   │
│  ┌─────────────────────────────────────────────────────────────────────────────────────────┐    │
│  │                              SYNC ENGINE COMPONENTS                                      │    │
│  │                                                                                          │    │
│  │  ┌─────────────────────────────────────────────────────────────────────────────────┐    │    │
│  │  │                         SYNC COORDINATOR                                          │    │    │
│  │  │                                                                                  │    │    │
│  │  │  Responsibilities:                                                                │    │    │
│  │  │  • Schedule sync jobs                                                            │    │    │
│  │  │  • Monitor sync health                                                           │    │    │
│  │  │  • Trigger failover                                                             │    │    │
│  │  │  • Manage sync locks                                                            │    │    │
│  │  │  • Coordinate distributed workers                                               │    │    │
│  │  └─────────────────────────────────────────────────────────────────────────────────┘    │    │
│  │                                                                                          │    │
│  │  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐                      │    │
│  │  │   SYNC WORKER    │  │   SYNC WORKER    │  │   SYNC WORKER    │                      │    │
│  │  │     POOL #1      │  │     POOL #2      │  │     POOL #3      │                      │    │
│  │  │                  │  │                  │  │                  │                      │    │
│  │  │  ┌────────────┐  │  │  ┌────────────┐  │  │  ┌────────────┐  │                      │    │
│  │  │  │ Real-time  │  │  │  │   Batch    │  │  │  │  On-demand │  │                      │    │
│  │  │  │  Handler   │  │  │  │ Processor  │  │  │  │  Handler   │  │                      │    │
│  │  │  └────────────┘  │  │  └────────────┘  │  │  └────────────┘  │                      │    │
│  │  │  ┌────────────┐  │  │  │  ┌──────────┐ │  │  │  ┌──────────┐ │                      │    │
│  │  │  │ Change     │  │  │  │  │ Extract  │ │  │  │  │ Request  │ │                      │    │
│  │  │  │ Capture    │  │  │  │  │          │ │  │  │  │ Queue    │ │                      │    │
│  │  │  └────────────┘  │  │  │  └──────────┘ │  │  │  └──────────┘ │                      │    │
│  │  │  ┌────────────┐  │  │  │  ┌──────────┐ │  │  │  ┌──────────┐ │                      │    │
│  │  │  │ Validation │  │  │  │  │ Transform│ │  │  │  │ Priority │ │                      │    │
│  │  │  │ Engine     │  │  │  │  │          │ │  │  │  │ Handler  │ │                      │    │
│  │  │  └────────────┘  │  │  │  └──────────┘ │  │  │  └──────────┘ │                      │    │
│  │  │  ┌────────────┐  │  │  │  ┌──────────┐ │  │  │  ┌──────────┐ │                      │    │
│  │  │  │ Router     │  │  │  │  │ Load     │ │  │  │  │ Response │ │                      │    │
│  │  │  └────────────┘  │  │  │  └──────────┘ │  │  │  └──────────┘ │                      │    │
│  │  └──────────────────┘  └──────────────────┘  └──────────────────┘                      │    │
│  │                                                                                          │    │
│  └─────────────────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                                   │
│  ┌─────────────────────────────────────────────────────────────────────────────────────────┐    │
│  │                           CONFLICT RESOLUTION ENGINE                                     │    │
│  │                                                                                          │    │
│  │  Conflict Types:                                                                          │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐                │    │
│  │  │   Update     │  │    Delete    │  │   Create     │  │   Merge      │                │    │
│  │  │   Conflict   │  │   Conflict   │  │   Conflict   │  │   Conflict   │                │    │
│  │  │              │  │              │  │              │  │              │                │    │
│  │  │ Same record  │  │ Update vs    │  │ Duplicate    │  │ Related      │                │    │
│  │  │ modified in  │  │ Delete       │  │ key creation │  │ records      │                │    │
│  │  │ both systems │  │              │  │              │  │ changed      │                │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘                │    │
│  │                                                                                          │    │
│  │  Resolution Strategies:                                                                   │    │
│  │  ┌─────────────────────────────────────────────────────────────────────────────────┐    │    │
│  │  │                                                                                  │    │    │
│  │  │  1. LAST_WRITE_WINS (Default for most entities)                                  │    │    │
│  │  │     • Compare timestamps                                                         │    │    │
│  │  │     • Keep most recent version                                                   │    │    │
│  │  │     • Log conflict for audit                                                     │    │    │
│  │  │                                                                                  │    │    │
│  │  │  2. ERP_WINS (For financial data)                                                │    │    │
│  │  │     • Always prefer ERP version                                                  │    │    │
│  │  │     • Queue portal changes for review                                           │    │    │
│  │  │                                                                                  │    │    │
│  │  │  3. PORTAL_WINS (For operational data)                                           │    │    │
│  │  │     • Prefer portal version                                                      │    │    │
│  │  │     • Sync to ERP as new revision                                               │    │    │
│  │  │                                                                                  │    │    │
│  │  │  4. MANUAL_REVIEW (For critical conflicts)                                       │    │    │
│  │  │     • Escalate to admin queue                                                    │    │    │
│  │  │     • Send notification                                                          │    │    │
│  │  │     • Hold sync until resolved                                                  │    │    │
│  │  │                                                                                  │    │    │
│  │  │  5. MERGE_FIELDS (For partial conflicts)                                         │    │    │
│  │  │     • Apply field-level changes                                                  │    │    │
│  │  │     • Keep non-conflicting updates from both sides                               │    │    │
│  │  │                                                                                  │    │    │
│  │  └─────────────────────────────────────────────────────────────────────────────────┘    │    │
│  └─────────────────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                                   │
│  ┌─────────────────────────────────────────────────────────────────────────────────────────┐    │
│  │                              CHANGE TRACKING SYSTEM                                      │    │
│  │                                                                                          │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐                │    │
│  │  │   Change     │  │   Change     │  │   Change     │  │   Change     │                │    │
│  │  │   Log Table  │  │   Log Table  │  │   Log Table  │  │   Log Table  │                │    │
│  │  │   (Portal)   │  │    (ERP)     │  │   (Farm)     │  │   (Mobile)   │                │    │
│  │  │              │  │              │  │              │  │              │                │    │
│  │  │ change_id    │  │ change_id    │  │ change_id    │  │ change_id    │                │    │
│  │  │ entity_type  │  │ entity_type  │  │ entity_type  │  │ entity_type  │                │    │
│  │  │ entity_id    │  │ entity_id    │  │ entity_id    │  │ entity_id    │                │    │
│  │  │ change_type  │  │ change_type  │  │ change_type  │  │ change_type  │                │    │
│  │  │ old_values   │  │ old_values   │  │ old_values   │  │ old_values   │                │    │
│  │  │ new_values   │  │ new_values   │  │ new_values   │  │ new_values   │                │    │
│  │  │ changed_by   │  │ changed_by   │  │ changed_by   │  │ changed_by   │                │    │
│  │  │ changed_at   │  │ changed_at   │  │ changed_at   │  │ changed_at   │                │    │
│  │  │ sync_status  │  │ sync_status  │  │ sync_status  │  │ sync_status  │                │    │
│  │  │ sync_at      │  │ sync_at      │  │ sync_at      │  │ sync_at      │                │    │
│  │  │ version      │  │ version      │  │ version      │  │ version      │                │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘                │    │
│  │                                                                                          │    │
│  │  Capture Methods:                                                                         │    │
│  │  • Database Triggers (PostgreSQL)                                                        │    │
│  │  • Hibernate Interceptors (Application layer)                                            │    │
│  │  • Debezium CDC (Log-based capture)                                                      │    │
│  │  • Application Events (Event sourcing)                                                   │    │
│  └─────────────────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                                   │
└─────────────────────────────────────────────────────────────────────────────────────────────────┘
```

#### 2.4.2 Sync Engine Implementation

```java
// SyncEngine.java - Core Synchronization Engine
package com.smartdairy.sync.engine;

import com.smartdairy.sync.model.*;
import com.smartdairy.sync.repository.ChangeLogRepository;
import io.micrometer.core.instrument.MeterRegistry;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.scheduling.annotation.Scheduled;
import org.springframework.stereotype.Component;
import reactor.core.publisher.Flux;
import reactor.core.publisher.Mono;
import reactor.core.scheduler.Schedulers;

import java.time.Duration;
import java.time.Instant;
import java.util.List;
import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.atomic.AtomicInteger;

@Slf4j
@Component
@RequiredArgsConstructor
public class SyncEngine {
    
    private final ChangeLogRepository changeLogRepository;
    private final SyncWorkerPool workerPool;
    private final ConflictResolver conflictResolver;
    private final SyncMetrics metrics;
    private final MeterRegistry meterRegistry;
    
    private final ConcurrentHashMap<String, SyncLock> activeLocks = new ConcurrentHashMap<>();
    private final AtomicInteger activeSyncJobs = new AtomicInteger(0);
    
    /**
     * Real-time sync triggered by change events
     */
    public Mono<SyncResult> syncRealtime(ChangeEvent event) {
        return Mono.fromCallable(() -> activeSyncJobs.incrementAndGet())
            .flatMap(count -> {
                metrics.recordRealtimeSyncStarted();
                
                return acquireLock(event.getEntityType(), event.getEntityId())
                    .flatMap(lock -> processChangeEvent(event)
                        .flatMap(result -> {
                            if (result.hasConflict()) {
                                return conflictResolver.resolve(result.getConflict())
                                    .flatMap(resolution -> applyResolution(event, resolution));
                            }
                            return Mono.just(result);
                        })
                        .doFinally(signal -> releaseLock(lock))
                        .doOnSuccess(r -> metrics.recordRealtimeSyncSuccess())
                        .doOnError(e -> {
                            metrics.recordRealtimeSyncFailure();
                            log.error("Real-time sync failed for {}:{}", 
                                event.getEntityType(), event.getEntityId(), e);
                        })
                    );
            })
            .doFinally(signal -> activeSyncJobs.decrementAndGet());
    }
    
    /**
     * Scheduled batch synchronization
     */
    @Scheduled(fixedRate = 60000) // Every minute
    public void syncBatch() {
        Instant cutoffTime = Instant.now().minus(Duration.ofMinutes(5));
        
        changeLogRepository.findUnsyncedChanges(cutoffTime)
            .buffer(100) // Process in batches of 100
            .flatMap(batch -> processBatch(batch)
                .subscribeOn(Schedulers.boundedElastic()),
                5) // Max 5 concurrent batches
            .subscribe(
                result -> log.debug("Batch sync completed: {}", result),
                error -> log.error("Batch sync failed", error),
                () -> log.debug("Scheduled batch sync completed")
            );
    }
    
    /**
     * On-demand sync for specific entities
     */
    public Mono<SyncResult> syncOnDemand(String entityType, String entityId, SyncOptions options) {
        return changeLogRepository.findByEntityTypeAndEntityId(entityType, entityId)
            .collectList()
            .flatMap(changes -> {
                if (changes.isEmpty()) {
                    return Mono.just(SyncResult.noChanges(entityType, entityId));
                }
                
                return acquireLock(entityType, entityId, options.getTimeout())
                    .flatMap(lock -> Flux.fromIterable(changes)
                        .concatMap(this::processChangeEvent)
                        .collectList()
                        .map(results -> aggregateResults(results, entityType, entityId))
                        .doFinally(signal -> releaseLock(lock))
                    );
            });
    }
    
    /**
     * Full sync for a system (used for initial sync or recovery)
     */
    public Flux<SyncResult> syncFull(String sourceSystem, String targetSystem, SyncOptions options) {
        return changeLogRepository.findAllForSystem(sourceSystem)
            .filter(change -> !change.isSynced())
            .buffer(options.getBatchSize())
            .flatMap(batch -> workerPool.submitBatch(batch, targetSystem)
                .flatMapMany(Flux::fromIterable),
                options.getConcurrency())
            .onErrorContinue((error, obj) -> {
                log.error("Error during full sync, continuing: {}", error.getMessage());
                metrics.recordSyncError(error);
            });
    }
    
    private Mono<SyncLock> acquireLock(String entityType, String entityId) {
        return acquireLock(entityType, entityId, Duration.ofSeconds(30));
    }
    
    private Mono<SyncLock> acquireLock(String entityType, String entityId, Duration timeout) {
        String lockKey = String.format("%s:%s", entityType, entityId);
        
        return Mono.fromCallable(() -> {
            SyncLock lock = new SyncLock(lockKey, Instant.now().plus(timeout));
            SyncLock existing = activeLocks.putIfAbsent(lockKey, lock);
            
            if (existing != null && existing.isExpired()) {
                activeLocks.put(lockKey, lock);
                return lock;
            } else if (existing != null) {
                throw new SyncLockException("Entity is already being synced: " + lockKey);
            }
            
            return lock;
        }).subscribeOn(Schedulers.boundedElastic());
    }
    
    private void releaseLock(SyncLock lock) {
        activeLocks.remove(lock.getKey());
    }
    
    private Mono<SyncResult> processChangeEvent(ChangeEvent event) {
        return workerPool.getWorker(event.getTargetSystem())
            .flatMap(worker -> worker.process(event))
            .flatMap(result -> {
                if (result.isSuccess()) {
                    return changeLogRepository.markAsSynced(event.getChangeId())
                        .thenReturn(result);
                }
                return Mono.just(result);
            });
    }
    
    private Mono<BatchSyncResult> processBatch(List<ChangeEvent> batch) {
        return Flux.fromIterable(batch)
            .concatMap(this::processChangeEvent)
            .collectList()
            .map(results -> new BatchSyncResult(results, batch.size()));
    }
    
    private Mono<SyncResult> applyResolution(ChangeEvent event, ConflictResolution resolution) {
        return workerPool.getWorker(event.getTargetSystem())
            .flatMap(worker -> worker.applyResolution(event, resolution));
    }
    
    private SyncResult aggregateResults(List<SyncResult> results, String entityType, String entityId) {
        long successCount = results.stream().filter(SyncResult::isSuccess).count();
        long failureCount = results.size() - successCount;
        
        return SyncResult.builder()
            .entityType(entityType)
            .entityId(entityId)
            .success(failureCount == 0)
            .processedCount(results.size())
            .successCount(successCount)
            .failureCount(failureCount)
            .build();
    }
    
    public SyncStatus getStatus() {
        return SyncStatus.builder()
            .activeJobs(activeSyncJobs.get())
            .activeLocks(activeLocks.size())
            .pendingChanges(changeLogRepository.countUnsynced().block())
            .build();
    }
}
```

```java
// ConflictResolver.java - Conflict Resolution Engine
package com.smartdairy.sync.engine;

import com.smartdairy.sync.model.*;
import com.smartdairy.sync.strategy.*;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.stereotype.Component;
import reactor.core.publisher.Mono;

import java.time.Instant;
import java.util.Comparator;
import java.util.Map;

@Slf4j
@Component
@RequiredArgsConstructor
public class ConflictResolver {
    
    private final ResolutionStrategyRegistry strategyRegistry;
    private final ConflictLogRepository conflictLogRepository;
    
    public Mono<ConflictResolution> resolve(SyncConflict conflict) {
        ResolutionStrategy strategy = strategyRegistry.getStrategy(
            conflict.getEntityType(), 
            conflict.getConflictType()
        );
        
        return Mono.fromCallable(() -> {
            log.debug("Resolving conflict for {}:{} using strategy {}",
                conflict.getEntityType(), 
                conflict.getEntityId(),
                strategy.getClass().getSimpleName());
            
            ConflictResolution resolution = strategy.resolve(conflict);
            
            // Log the conflict and resolution
            logConflict(conflict, resolution);
            
            return resolution;
        });
    }
    
    private void logConflict(SyncConflict conflict, ConflictResolution resolution) {
        ConflictLog log = ConflictLog.builder()
            .conflictId(generateConflictId())
            .entityType(conflict.getEntityType())
            .entityId(conflict.getEntityId())
            .sourceVersion(conflict.getSourceVersion())
            .targetVersion(conflict.getTargetVersion())
            .conflictType(conflict.getConflictType())
            .conflictDetails(conflict.getDetails())
            .resolutionStrategy(resolution.getStrategy())
            .resolutionAction(resolution.getAction())
            .resolvedValues(resolution.getResolvedValues())
            .resolvedAt(Instant.now())
            .requiresManualReview(resolution.requiresManualReview())
            .build();
        
        conflictLogRepository.save(log).subscribe();
    }
    
    private String generateConflictId() {
        return "CONF-" + System.currentTimeMillis() + "-" + 
               (int)(Math.random() * 10000);
    }
}
```

```java
// Resolution Strategies
package com.smartdairy.sync.strategy;

import com.smartdairy.sync.model.*;

/**
 * Last Write Wins Strategy - Default for most entities
 */
@Component
public class LastWriteWinsStrategy implements ResolutionStrategy {
    
    @Override
    public ConflictResolution resolve(SyncConflict conflict) {
        ChangeEvent sourceChange = conflict.getSourceChange();
        ChangeEvent targetChange = conflict.getTargetChange();
        
        // Compare timestamps
        if (sourceChange.getChangedAt().isAfter(targetChange.getChangedAt())) {
            return ConflictResolution.builder()
                .strategy("LAST_WRITE_WINS")
                .action(ResolutionAction.USE_SOURCE)
                .resolvedValues(sourceChange.getNewValues())
                .reason("Source change is more recent")
                .requiresManualReview(false)
                .build();
        } else {
            return ConflictResolution.builder()
                .strategy("LAST_WRITE_WINS")
                .action(ResolutionAction.USE_TARGET)
                .resolvedValues(targetChange.getNewValues())
                .reason("Target change is more recent")
                .requiresManualReview(false)
                .build();
        }
    }
    
    @Override
    public boolean supports(String entityType, ConflictType conflictType) {
        return conflictType == ConflictType.UPDATE_CONFLICT;
    }
}

/**
 * ERP Wins Strategy - For financial and master data
 */
@Component
public class ErpWinsStrategy implements ResolutionStrategy {
    
    private static final Set<String> ERP_MASTER_ENTITIES = Set.of(
        "CUSTOMER", "SUPPLIER", "PRODUCT", "CHART_OF_ACCOUNTS",
        "COST_CENTER", "PROFIT_CENTER"
    );
    
    @Override
    public ConflictResolution resolve(SyncConflict conflict) {
        return ConflictResolution.builder()
            .strategy("ERP_WINS")
            .action(ResolutionAction.USE_TARGET)
            .resolvedValues(conflict.getTargetChange().getNewValues())
            .reason("ERP is authoritative for this entity type")
            .requiresManualReview(false)
            .build();
    }
    
    @Override
    public boolean supports(String entityType, ConflictType conflictType) {
        return ERP_MASTER_ENTITIES.contains(entityType.toUpperCase());
    }
}

/**
 * Merge Fields Strategy - For partial conflicts
 */
@Component
public class MergeFieldsStrategy implements ResolutionStrategy {
    
    @Override
    public ConflictResolution resolve(SyncConflict conflict) {
        Map<String, Object> sourceValues = conflict.getSourceChange().getNewValues();
        Map<String, Object> targetValues = conflict.getTargetChange().getNewValues();
        Map<String, Object> baseValues = conflict.getBaseValues();
        
        Map<String, Object> mergedValues = new HashMap<>();
        Set<String> conflictedFields = new HashSet<>();
        
        // Get all field names
        Set<String> allFields = new HashSet<>();
        allFields.addAll(sourceValues.keySet());
        allFields.addAll(targetValues.keySet());
        
        for (String field : allFields) {
            Object sourceVal = sourceValues.get(field);
            Object targetVal = targetValues.get(field);
            Object baseVal = baseValues != null ? baseValues.get(field) : null;
            
            if (Objects.equals(sourceVal, targetVal)) {
                // No conflict for this field
                mergedValues.put(field, sourceVal);
            } else if (Objects.equals(sourceVal, baseVal)) {
                // Only target changed
                mergedValues.put(field, targetVal);
            } else if (Objects.equals(targetVal, baseVal)) {
                // Only source changed
                mergedValues.put(field, sourceVal);
            } else {
                // Both changed - field-level conflict
                conflictedFields.add(field);
                // Use source value as default
                mergedValues.put(field, sourceVal);
            }
        }
        
        return ConflictResolution.builder()
            .strategy("MERGE_FIELDS")
            .action(ResolutionAction.MERGE)
            .resolvedValues(mergedValues)
            .conflictedFields(conflictedFields)
            .reason("Merged non-conflicting fields, " + conflictedFields.size() + " fields in conflict")
            .requiresManualReview(!conflictedFields.isEmpty())
            .build();
    }
    
    @Override
    public boolean supports(String entityType, ConflictType conflictType) {
        return conflictType == ConflictType.UPDATE_CONFLICT;
    }
}

/**
 * Manual Review Strategy - For critical conflicts
 */
@Component
public class ManualReviewStrategy implements ResolutionStrategy {
    
    private static final Set<String> CRITICAL_ENTITIES = Set.of(
        "FINANCIAL_TRANSACTION", "PAYMENT", "INVOICE", "JOURNAL_ENTRY"
    );
    
    @Override
    public ConflictResolution resolve(SyncConflict conflict) {
        return ConflictResolution.builder()
            .strategy("MANUAL_REVIEW")
            .action(ResolutionAction.HOLD_FOR_REVIEW)
            .resolvedValues(null)
            .reason("Critical entity requires manual review: " + conflict.getEntityType())
            .requiresManualReview(true)
            .build();
    }
    
    @Override
    public boolean supports(String entityType, ConflictType conflictType) {
        return CRITICAL_ENTITIES.contains(entityType.toUpperCase());
    }
}
```



### 2.5 Error Handling Architecture

#### 2.5.1 Error Classification System

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────┐
│                              ERROR CLASSIFICATION HIERARCHY                                     │
├─────────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                                   │
│  ┌─────────────────────────────────────────────────────────────────────────────────────────┐    │
│  │                              ERROR CATEGORIES                                            │    │
│  │                                                                                          │    │
│  │  ┌─────────────────────────────────────────────────────────────────────────────────┐    │    │
│  │  │ LEVEL 1: SYSTEM ERRORS (Infrastructure Layer)                                    │    │    │
│  │  │                                                                                  │    │    │
│  │  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐        │    │    │
│  │  │  │  Network     │  │   Database   │  │    Cache     │  │    Queue     │        │    │    │
│  │  │  │  Timeout     │  │   Timeout    │  │   Failure    │  │   Failure    │        │    │    │
│  │  │  │              │  │              │  │              │  │              │        │    │    │
│  │  │  │ Retryable:   │  │ Retryable:   │  │ Retryable:   │  │ Retryable:   │        │    │    │
│  │  │  │ YES          │  │ YES          │  │ YES          │  │ YES          │        │    │    │
│  │  │  │              │  │              │  │              │  │              │        │    │    │
│  │  │  │ Code: SYS001 │  │ Code: SYS002 │  │ Code: SYS003 │  │ Code: SYS004 │        │    │    │
│  │  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘        │    │    │
│  │  │                                                                                  │    │    │
│  │  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐        │    │    │
│  │  │  │  ERP         │  │   Disk       │  │   Memory     │  │    CPU       │        │    │    │
│  │  │  │  Unavailable │  │   Full       │  │   Exhausted  │  │   Throttled  │        │    │    │
│  │  │  │              │  │              │  │              │  │              │        │    │    │
│  │  │  │ Retryable:   │  │ Retryable:   │  │ Retryable:   │  │ Retryable:   │        │    │    │
│  │  │  │ YES          │  │ YES          │  │ NO           │  │ YES          │        │    │    │
│  │  │  │              │  │              │  │              │  │              │        │    │    │
│  │  │  │ Code: SYS005 │  │ Code: SYS006 │  │ Code: SYS007 │  │ Code: SYS008 │        │    │    │
│  │  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘        │    │    │
│  │  └─────────────────────────────────────────────────────────────────────────────────┘    │    │
│  │                                                                                          │    │
│  │  ┌─────────────────────────────────────────────────────────────────────────────────┐    │    │
│  │  │ LEVEL 2: APPLICATION ERRORS (Business Logic Layer)                               │    │    │
│  │  │                                                                                  │    │    │
│  │  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐        │    │    │
│  │  │  │ Validation   │  │   Business   │  │    Auth      │  │   Rate       │        │    │    │
│  │  │  │ Error        │  │   Rule Viol. │  │   Failure    │  │   Limit      │        │    │    │
│  │  │  │              │  │              │  │              │  │              │        │    │    │
│  │  │  │ Retryable:   │  │ Retryable:   │  │ Retryable:   │  │ Retryable:   │        │    │    │
│  │  │  │ NO           │  │ NO           │  │ NO           │  │ YES (delay)  │        │    │    │
│  │  │  │              │  │              │  │              │  │              │        │    │    │
│  │  │  │ Code: APP001 │  │ Code: APP002 │  │ Code: APP003 │  │ Code: APP004 │        │    │    │
│  │  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘        │    │    │
│  │  │                                                                                  │    │    │
│  │  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐        │    │    │
│  │  │  │  Data        │  │   Duplicate  │  │   Not Found  │  │   Conflict   │        │    │    │
│  │  │  │  Format      │  │   Record     │  │              │  │              │        │    │    │
│  │  │  │              │  │              │  │              │  │              │        │    │    │
│  │  │  │ Retryable:   │  │ Retryable:   │  │ Retryable:   │  │ Retryable:   │        │    │    │
│  │  │  │ NO           │  │ NO           │  │ NO           │  │ NO           │        │    │    │
│  │  │  │              │  │              │  │              │  │              │        │    │    │
│  │  │  │ Code: APP005 │  │ Code: APP006 │  │ Code: APP007 │  │ Code: APP008 │        │    │    │
│  │  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘        │    │    │
│  │  └─────────────────────────────────────────────────────────────────────────────────┘    │    │
│  │                                                                                          │    │
│  │  ┌─────────────────────────────────────────────────────────────────────────────────┐    │    │
│  │  │ LEVEL 3: INTEGRATION ERRORS (ERP Connector Layer)                                │    │    │
│  │  │                                                                                  │    │    │
│  │  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐        │    │    │
│  │  │  │  ERP API     │  │   ERP Auth   │  │   ERP Rate   │  │   Schema     │        │    │    │
│  │  │  │  Error       │  │   Expired    │  │   Limit      │  │   Mismatch   │        │    │    │
│  │  │  │              │  │              │  │              │  │              │        │    │    │
│  │  │  │ Retryable:   │  │ Retryable:   │  │ Retryable:   │  │ Retryable:   │        │    │    │
│  │  │  │ Depends      │  │ YES          │  │ YES (delay)  │  │ NO           │        │    │    │
│  │  │  │              │  │              │  │              │  │              │        │    │    │
│  │  │  │ Code: INT001 │  │ Code: INT002 │  │ Code: INT003 │  │ Code: INT004 │        │    │    │
│  │  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘        │    │    │
│  │  │                                                                                  │    │    │
│  │  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐        │    │    │
│  │  │  │  ERP         │  │   ERP        │  │   Protocol   │  │   Transform  │        │    │    │
│  │  │  │  Timeout     │  │   Maintenance│  │   Error      │  │   Error      │        │    │    │
│  │  │  │              │  │              │  │              │  │              │        │    │    │
│  │  │  │ Retryable:   │  │ Retryable:   │  │ Retryable:   │  │ Retryable:   │        │    │    │
│  │  │  │ YES          │  │ YES (later)  │  │ Depends      │  │ NO           │        │    │    │
│  │  │  │              │  │              │  │              │  │              │        │    │    │
│  │  │  │ Code: INT005 │  │ Code: INT006 │  │ Code: INT007 │  │ Code: INT008 │        │    │    │
│  │  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘        │    │    │
│  │  └─────────────────────────────────────────────────────────────────────────────────┘    │    │
│  │                                                                                          │    │
│  │  ┌─────────────────────────────────────────────────────────────────────────────────┐    │    │
│  │  │ LEVEL 4: SYNC ERRORS (Data Synchronization Layer)                                │    │    │
│  │  │                                                                                  │    │    │
│  │  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐        │    │    │
│  │  │  │  Version     │  │   Sync       │  │   Lock       │  │   Data       │        │    │    │
│  │  │  │  Conflict    │  │   Loop       │  │   Timeout    │  │   Loss       │        │    │    │
│  │  │  │              │  │              │  │              │  │              │        │    │    │
│  │  │  │ Retryable:   │  │ Retryable:   │  │ Retryable:   │  │ Retryable:   │        │    │    │
│  │  │  │ Manual       │  │ NO           │  │ YES          │  │ NO           │        │    │    │
│  │  │  │              │  │              │  │              │  │              │        │    │    │
│  │  │  │ Code: SYN001 │  │ Code: SYN002 │  │ Code: SYN003 │  │ Code: SYN004 │        │    │    │
│  │  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘        │    │    │
│  │  └─────────────────────────────────────────────────────────────────────────────────┘    │    │
│  └─────────────────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                                   │
└─────────────────────────────────────────────────────────────────────────────────────────────────┘
```

#### 2.5.2 Error Handling Implementation

```java
// ErrorHandlingConfig.java - Central Error Handler
package com.smartdairy.integration.error;

import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.amqp.rabbit.core.RabbitTemplate;
import org.springframework.stereotype.Component;
import reactor.core.publisher.Mono;
import reactor.util.retry.Retry;

import java.time.Duration;
import java.util.Map;

@Slf4j
@Component
@RequiredArgsConstructor
public class ErrorHandler {
    
    private final DeadLetterQueue dlq;
    private final RetryPolicyRepository retryPolicyRepository;
    private final AlertService alertService;
    private final MetricsService metricsService;
    
    /**
     * Handle error with appropriate strategy based on error classification
     */
    public Mono<Void> handleError(IntegrationError error, IntegrationContext context) {
        return classifyError(error)
            .flatMap(classification -> {
                log.error("Error occurred: {} - {} (Retryable: {})",
                    classification.getErrorCode(),
                    error.getMessage(),
                    classification.isRetryable());
                
                metricsService.recordError(classification);
                
                if (classification.isRetryable()) {
                    return handleRetryableError(error, classification, context);
                } else {
                    return handleNonRetryableError(error, classification, context);
                }
            });
    }
    
    private Mono<ErrorClassification> classifyError(IntegrationError error) {
        return Mono.fromCallable(() -> {
            // Determine error category and code
            ErrorCategory category = determineCategory(error);
            String errorCode = generateErrorCode(category, error);
            boolean retryable = isRetryable(error, category);
            int severity = determineSeverity(error, category);
            
            return ErrorClassification.builder()
                .errorCode(errorCode)
                .category(category)
                .retryable(retryable)
                .severity(severity)
                .rootCause(error.getCause())
                .build();
        });
    }
    
    private Mono<Void> handleRetryableError(IntegrationError error, 
                                            ErrorClassification classification,
                                            IntegrationContext context) {
        return retryPolicyRepository.findByErrorCode(classification.getErrorCode())
            .defaultIfEmpty(getDefaultRetryPolicy())
            .flatMap(policy -> {
                if (shouldRetry(error, policy)) {
                    // Schedule retry
                    scheduleRetry(error, policy, context);
                    return Mono.empty();
                } else {
                    // Max retries exceeded - move to DLQ
                    return moveToDeadLetterQueue(error, classification, context);
                }
            });
    }
    
    private Mono<Void> handleNonRetryableError(IntegrationError error,
                                               ErrorClassification classification,
                                               IntegrationContext context) {
        return moveToDeadLetterQueue(error, classification, context)
            .flatMap(v -> {
                // Send alert for critical errors
                if (classification.getSeverity() >= 4) {
                    return sendAlert(error, classification);
                }
                return Mono.empty();
            });
    }
    
    private Mono<Void> moveToDeadLetterQueue(IntegrationError error,
                                             ErrorClassification classification,
                                             IntegrationContext context) {
        DeadLetterMessage dlqMessage = DeadLetterMessage.builder()
            .messageId(generateMessageId())
            .originalMessage(context.getOriginalMessage())
            .errorCode(classification.getErrorCode())
            .errorMessage(error.getMessage())
            .stackTrace(getStackTrace(error))
            .classification(classification)
            .context(context)
            .timestamp(Instant.now())
            .retryCount(context.getRetryCount())
            .build();
        
        return dlq.send(dlqMessage)
            .doOnSuccess(v -> log.info("Message moved to DLQ: {}", dlqMessage.getMessageId()))
            .doOnError(e -> log.error("Failed to move message to DLQ", e));
    }
    
    private void scheduleRetry(IntegrationError error, RetryPolicy policy, IntegrationContext context) {
        long delayMs = calculateBackoff(context.getRetryCount(), policy);
        
        RetryTask retryTask = RetryTask.builder()
            .taskId(generateTaskId())
            .originalMessage(context.getOriginalMessage())
            .context(context)
            .scheduledTime(Instant.now().plusMillis(delayMs))
            .retryCount(context.getRetryCount() + 1)
            .build();
        
        // Schedule using delayed queue or scheduler
        retryScheduler.schedule(retryTask, delayMs);
        
        log.info("Scheduled retry {} for message {} in {}ms",
            retryTask.getRetryCount(), 
            context.getMessageId(),
            delayMs);
    }
    
    private long calculateBackoff(int retryCount, RetryPolicy policy) {
        long baseDelay = policy.getBaseDelayMs();
        double multiplier = policy.getBackoffMultiplier();
        long maxDelay = policy.getMaxDelayMs();
        
        long delay = (long) (baseDelay * Math.pow(multiplier, retryCount));
        return Math.min(delay, maxDelay);
    }
    
    private boolean shouldRetry(IntegrationError error, RetryPolicy policy) {
        return error.getRetryCount() < policy.getMaxRetries();
    }
    
    private ErrorCategory determineCategory(IntegrationError error) {
        Throwable cause = error.getCause();
        
        if (cause instanceof TimeoutException) {
            return ErrorCategory.SYSTEM;
        } else if (cause instanceof WebClientResponseException) {
            return ErrorCategory.INTEGRATION;
        } else if (cause instanceof ValidationException) {
            return ErrorCategory.APPLICATION;
        } else if (cause instanceof SyncException) {
            return ErrorCategory.SYNCHRONIZATION;
        }
        
        return ErrorCategory.UNKNOWN;
    }
    
    private boolean isRetryable(IntegrationError error, ErrorCategory category) {
        return switch (category) {
            case SYSTEM -> true;
            case INTEGRATION -> isRetryableIntegrationError(error);
            case APPLICATION -> false;
            case SYNCHRONIZATION -> isRetryableSyncError(error);
            default -> false;
        };
    }
    
    private boolean isRetryableIntegrationError(IntegrationError error) {
        if (error.getCause() instanceof WebClientResponseException wcre) {
            int status = wcre.getStatusCode().value();
            // Retry on 5xx errors and 429 (rate limit)
            return status >= 500 || status == 429;
        }
        return true;
    }
    
    private boolean isRetryableSyncError(IntegrationError error) {
        // Sync conflicts and loops are not retryable
        return !(error instanceof SyncConflictException || 
                 error instanceof SyncLoopException);
    }
}
```

```java
// DeadLetterQueue.java - DLQ Management
package com.smartdairy.integration.error;

import org.springframework.amqp.rabbit.core.RabbitTemplate;
import org.springframework.data.mongodb.core.MongoTemplate;
import org.springframework.stereotype.Component;
import reactor.core.publisher.Flux;
import reactor.core.publisher.Mono;

import java.time.Instant;
import java.util.List;

@Component
public class DeadLetterQueue {
    
    private final RabbitTemplate rabbitTemplate;
    private final MongoTemplate mongoTemplate;
    
    private static final String DLQ_EXCHANGE = "smart-dairy.dlx";
    private static final String DLQ_ROUTING_KEY = "dead-letter";
    private static final String DLQ_COLLECTION = "dead_letter_messages";
    
    public Mono<Void> send(DeadLetterMessage message) {
        return Mono.fromCallable(() -> {
            // Persist to MongoDB for long-term storage and querying
            mongoTemplate.save(message, DLQ_COLLECTION);
            
            // Also send to RabbitMQ for immediate processing/retry
            rabbitTemplate.convertAndSend(DLQ_EXCHANGE, DLQ_ROUTING_KEY, message);
            
            return null;
        }).subscribeOn(Schedulers.boundedElastic());
    }
    
    public Flux<DeadLetterMessage> getMessages(ErrorFilter filter) {
        Query query = buildQuery(filter);
        return Flux.fromIterable(mongoTemplate.find(query, DeadLetterMessage.class, DLQ_COLLECTION));
    }
    
    public Mono<DeadLetterMessage> getMessage(String messageId) {
        Query query = Query.query(Criteria.where("messageId").is(messageId));
        return Mono.justOrEmpty(mongoTemplate.findOne(query, DeadLetterMessage.class, DLQ_COLLECTION));
    }
    
    public Mono<Void> retryMessage(String messageId) {
        return getMessage(messageId)
            .flatMap(this::reprocessMessage)
            .flatMap(v -> markAsRetried(messageId));
    }
    
    public Mono<Void> purgeOldMessages(Instant before) {
        Query query = Query.query(Criteria.where("timestamp").lt(before));
        return Mono.fromCallable(() -> {
            mongoTemplate.remove(query, DLQ_COLLECTION);
            return null;
        }).subscribeOn(Schedulers.boundedElastic());
    }
    
    public Mono<DLQStats> getStats() {
        return Mono.fromCallable(() -> {
            long total = mongoTemplate.count(new Query(), DLQ_COLLECTION);
            long last24h = mongoTemplate.count(
                Query.query(Criteria.where("timestamp").gte(Instant.now().minus(24, ChronoUnit.HOURS))),
                DLQ_COLLECTION
            );
            
            Aggregation agg = Aggregation.newAggregation(
                Aggregation.group("errorCode").count().as("count"),
                Aggregation.sort(Sort.Direction.DESC, "count"),
                Aggregation.limit(10)
            );
            
            List<Document> topErrors = mongoTemplate.aggregate(agg, DLQ_COLLECTION, Document.class)
                .getMappedResults();
            
            return DLQStats.builder()
                .totalMessages(total)
                .messagesLast24h(last24h)
                .topErrors(topErrors)
                .build();
        }).subscribeOn(Schedulers.boundedElastic());
    }
}
```

---

## 3. Daily Task Allocation

### 3.1 Day 351: Project Kickoff & Architecture Setup

#### Developer 1 (Lead Dev - Integration Architecture)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-10:30 | Architecture Review | Review integration architecture with stakeholders | Architecture sign-off document |
| 10:30-12:30 | Gateway Setup | Initialize Spring Cloud Gateway project structure | Gateway skeleton project |
| 13:30-15:00 | Route Configuration | Configure basic routing rules for all portals | Route configuration YAML |
| 15:00-17:00 | Security Setup | Implement OAuth2/JWT authentication filters | Auth filter implementation |
| 17:00-18:00 | Documentation | Document architecture decisions | ADR-001: Gateway Architecture |

#### Developer 2 (Backend Dev - ERP Connectors)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-10:30 | B2B Connector Setup | Create B2B connector service project | B2B connector skeleton |
| 10:30-12:30 | ERP Client Setup | Implement ERP REST client with WebClient | ERP client implementation |
| 13:30-15:00 | Order Sync Service | Begin order synchronization service | Order sync service skeleton |
| 15:00-17:00 | Customer Sync Service | Begin customer synchronization service | Customer sync skeleton |
| 17:00-18:00 | Testing Setup | Write unit tests for ERP client | Unit tests (80% coverage) |

#### Developer 3 (Backend Dev - Data Processing)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-10:30 | Import Service Setup | Create data import service project | Import service skeleton |
| 10:30-12:30 | CSV Parser Implementation | Implement robust CSV parsing with validation | CSV parser component |
| 13:30-15:00 | Data Transformation | Create field mapping configuration system | Mapping DSL implementation |
| 15:00-17:00 | Validation Framework | Implement data validation rules engine | Validation framework |
| 17:00-18:00 | Error Handling | Set up error classification system | Error handling skeleton |

---

### 3.2 Day 352: API Gateway Core Implementation

#### Developer 1 (Lead Dev - Integration Architecture)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Rate Limiter Implementation | Implement Redis-based rate limiting | Rate limiter with multiple strategies |
| 11:00-12:30 | Circuit Breaker Setup | Configure Resilience4j circuit breakers | Circuit breaker configuration |
| 13:30-15:00 | Caching Layer | Implement Redis caching for API responses | Cache configuration and filters |
| 15:00-17:00 | Request/Response Logging | Implement comprehensive request logging | Logging filter with correlation IDs |
| 17:00-18:00 | Load Testing | Run initial load tests on gateway | Load test results baseline |

#### Developer 2 (Backend Dev - ERP Connectors)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | B2B Order Sync | Complete order synchronization logic | Order sync implementation |
| 11:00-12:30 | B2B Customer Sync | Complete customer synchronization logic | Customer sync implementation |
| 13:30-15:00 | B2B Pricing Sync | Implement pricing synchronization | Pricing sync service |
| 15:00-17:00 | B2B Inventory Sync | Implement inventory synchronization | Inventory sync service |
| 17:00-18:00 | Integration Tests | Write integration tests for B2B connector | Integration test suite |

#### Developer 3 (Backend Dev - Data Processing)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Excel Parser | Implement Excel file parsing (.xlsx, .xls) | Excel parser component |
| 11:00-12:30 | XML Parser | Implement XML parsing for EDI formats | XML parser component |
| 13:30-15:00 | JSON Parser | Implement JSON parsing with schema validation | JSON parser component |
| 15:00-17:00 | Data Type Conversion | Implement comprehensive type conversion | Type converter service |
| 17:00-18:00 | Parser Tests | Write comprehensive parser tests | Parser test suite |

---

### 3.3 Day 353: B2C Connector Implementation

#### Developer 1 (Lead Dev - Integration Architecture)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Gateway Monitoring | Implement Micrometer metrics collection | Metrics instrumentation |
| 11:00-12:30 | Health Checks | Implement health check endpoints | Health check implementation |
| 13:30-15:00 | API Documentation | Set up OpenAPI/Swagger documentation | API documentation portal |
| 15:00-17:00 | Gateway Security Audit | Review and harden security configurations | Security audit report |
| 17:00-18:00 | Performance Tuning | Optimize gateway performance | Performance improvements |

#### Developer 2 (Backend Dev - ERP Connectors)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | B2C Connector Setup | Create B2C connector service project | B2C connector skeleton |
| 11:00-12:30 | B2C Product Sync | Implement product catalog synchronization | Product sync service |
| 13:30-15:00 | B2C Order Processing | Implement B2C order processing | Order processing service |
| 15:00-17:00 | B2C Payment Reconciliation | Implement payment reconciliation | Payment sync service |
| 17:00-18:00 | B2C Integration Tests | Write B2C connector integration tests | B2C test suite |

#### Developer 3 (Backend Dev - Data Processing)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Data Enrichment | Implement data enrichment logic | Enrichment service |
| 11:00-12:30 | Lookup Services | Implement reference data lookup | Lookup service implementation |
| 13:30-15:00 | Data Quality Rules | Implement data quality validation | DQ rules engine |
| 15:00-17:00 | Export Service | Implement data export functionality | Export service |
| 17:00-18:00 | Export Formats | Support multiple export formats (CSV, Excel, JSON) | Multi-format export |

---

### 3.4 Day 354: Farm Management Connector

#### Developer 1 (Lead Dev - Integration Architecture)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | WebSocket Support | Implement WebSocket gateway for real-time | WebSocket gateway config |
| 11:00-12:30 | gRPC Support | Add gRPC protocol support to gateway | gRPC gateway configuration |
| 13:30-15:00 | Protocol Translation | Implement protocol translation layer | Protocol translator |
| 15:00-17:00 | Gateway Resilience | Implement bulkhead and timeout patterns | Resilience configuration |
| 17:00-18:00 | Documentation | Update gateway architecture docs | Updated documentation |

#### Developer 2 (Backend Dev - ERP Connectors)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Farm Connector Setup | Create farm connector service project | Farm connector skeleton |
| 11:00-12:30 | Animal Data Sync | Implement livestock data synchronization | Animal sync service |
| 13:30-15:00 | Production Records | Implement production record sync | Production sync service |
| 15:00-17:00 | Health Records | Implement health record synchronization | Health sync service |
| 17:00-18:00 | Farm Integration Tests | Write farm connector tests | Farm test suite |

#### Developer 3 (Backend Dev - Data Processing)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Error Classification | Implement comprehensive error classification | Error classifier |
| 11:00-12:30 | Retry Mechanism | Implement exponential backoff retry | Retry service |
| 13:30-15:00 | Dead Letter Queue | Implement DLQ management system | DLQ implementation |
| 15:00-17:00 | Alert System | Implement alerting for critical errors | Alert service |
| 17:00-18:00 | Error Dashboard | Create error monitoring dashboard | Dashboard implementation |

---

### 3.5 Day 355: Mobile Connector & IoT Integration

#### Developer 1 (Lead Dev - Integration Architecture)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Mobile API Optimization | Optimize APIs for mobile constraints | Mobile-optimized endpoints |
| 11:00-12:30 | Push Notification | Set up push notification gateway | Push gateway configuration |
| 13:30-15:00 | Offline Sync Support | Implement offline sync API patterns | Offline sync endpoints |
| 15:00-17:00 | Data Compression | Implement response compression for mobile | Compression configuration |
| 17:00-18:00 | Mobile Security | Implement mobile-specific security | Mobile security layer |

#### Developer 2 (Backend Dev - ERP Connectors)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Mobile Connector Setup | Create mobile connector service | Mobile connector skeleton |
| 11:00-12:30 | IoT Data Handler | Implement IoT sensor data handling | IoT data handler |
| 13:30-15:00 | MQTT Integration | Integrate with MQTT broker for IoT | MQTT client implementation |
| 15:00-17:00 | Mobile Sync API | Implement mobile synchronization APIs | Mobile sync API |
| 17:00-18:00 | Mobile Tests | Write mobile connector tests | Mobile test suite |

#### Developer 3 (Backend Dev - Data Processing)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Change Tracking | Implement change data capture system | CDC implementation |
| 11:00-12:30 | Audit Logging | Implement comprehensive audit logging | Audit log service |
| 13:30-15:00 | Data Lineage | Implement data lineage tracking | Lineage service |
| 15:00-17:00 | Reconciliation | Implement data reconciliation service | Reconciliation engine |
| 17:00-18:00 | Audit Dashboard | Create audit and lineage dashboard | Audit dashboard |

---

### 3.6 Day 356: Synchronization Engine Core

#### Developer 1 (Lead Dev - Integration Architecture)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Sync Engine Design | Finalize sync engine architecture | Sync engine design doc |
| 11:00-12:30 | Kafka Setup | Set up Kafka for event streaming | Kafka configuration |
| 13:30-15:00 | Event Schema | Define event schemas for all entities | Avro schemas |
| 15:00-17:00 | Event Producers | Implement event producers for all connectors | Event producer implementations |
| 17:00-18:00 | Integration | Integrate producers with connectors | Integrated event flow |

#### Developer 2 (Backend Dev - ERP Connectors)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Sync Worker Setup | Create sync worker service project | Sync worker skeleton |
| 11:00-12:30 | Event Consumers | Implement Kafka event consumers | Event consumers |
| 13:30-15:00 | Sync Orchestrator | Implement sync orchestration logic | Sync orchestrator |
| 15:00-17:00 | Batch Sync | Implement batch synchronization | Batch sync processor |
| 17:00-18:00 | Sync Tests | Write sync engine tests | Sync test suite |

#### Developer 3 (Backend Dev - Data Processing)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Conflict Detection | Implement conflict detection algorithm | Conflict detector |
| 11:00-12:30 | Conflict Resolution | Implement resolution strategies | Resolution strategies |
| 13:30-15:00 | Version Management | Implement optimistic locking | Version management |
| 15:00-17:00 | Merge Logic | Implement field-level merge logic | Merge service |
| 17:00-18:00 | Conflict UI | Create conflict resolution UI | Conflict management UI |

---

### 3.7 Day 357: Advanced Sync Features

#### Developer 1 (Lead Dev - Integration Architecture)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Transaction Management | Implement distributed transaction support | Saga implementation |
| 11:00-12:30 | Outbox Pattern | Implement transactional outbox | Outbox pattern |
| 13:30-15:00 | Idempotency | Implement idempotency keys | Idempotency service |
| 15:00-17:00 | Exactly-Once | Ensure exactly-once processing | Exactly-once semantics |
| 17:00-18:00 | Transaction Tests | Write transaction tests | Transaction test suite |

#### Developer 2 (Backend Dev - ERP Connectors)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Real-time Sync | Implement real-time sync triggers | Real-time sync |
| 11:00-12:30 | Scheduled Sync | Implement cron-based scheduled sync | Scheduled sync |
| 13:30-15:00 | Delta Sync | Implement delta/incremental sync | Delta sync processor |
| 15:00-17:00 | Full Sync | Implement full synchronization | Full sync processor |
| 17:00-18:00 | Sync Monitoring | Add sync monitoring and metrics | Sync metrics |

#### Developer 3 (Backend Dev - Data Processing)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Queue Management | Implement priority queue management | Priority queue |
| 11:00-12:30 | Backpressure | Implement backpressure handling | Backpressure controller |
| 13:30-15:00 | Load Balancing | Implement load balancing for workers | Load balancer |
| 15:00-17:00 | Auto-scaling | Implement auto-scaling for sync workers | Auto-scaling config |
| 17:00-18:00 | Queue Monitoring | Add queue monitoring dashboards | Queue dashboards |

---

### 3.8 Day 358: Testing & Quality Assurance

#### Developer 1 (Lead Dev - Integration Architecture)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Load Testing | Execute comprehensive load tests | Load test results |
| 11:00-12:30 | Stress Testing | Execute stress tests | Stress test results |
| 13:30-15:00 | Failover Testing | Test failover scenarios | Failover test results |
| 15:00-17:00 | Chaos Testing | Execute chaos engineering tests | Chaos test results |
| 17:00-18:00 | Performance Report | Compile performance test report | Performance report |

#### Developer 2 (Backend Dev - ERP Connectors)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Unit Tests | Complete unit test coverage | 90%+ coverage |
| 11:00-12:30 | Integration Tests | Complete integration tests | Integration test suite |
| 13:30-15:00 | Contract Tests | Implement Pact contract tests | Contract tests |
| 15:00-17:00 | E2E Tests | Implement end-to-end tests | E2E test suite |
| 17:00-18:00 | Test Report | Compile test coverage report | Test report |

#### Developer 3 (Backend Dev - Data Processing)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Data Validation Tests | Test data validation rules | Validation test suite |
| 11:00-12:30 | Transform Tests | Test data transformation | Transform test suite |
| 13:30-15:00 | Error Handling Tests | Test error scenarios | Error test suite |
| 15:00-17:00 | Recovery Tests | Test recovery mechanisms | Recovery test suite |
| 17:00-18:00 | Data Quality Report | Compile data quality metrics | DQ report |

---

### 3.9 Day 359: Monitoring & Observability

#### Developer 1 (Lead Dev - Integration Architecture)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Metrics Pipeline | Set up Prometheus metrics collection | Metrics pipeline |
| 11:00-12:30 | Grafana Dashboards | Create comprehensive Grafana dashboards | Dashboards |
| 13:30-15:00 | Distributed Tracing | Implement Jaeger distributed tracing | Tracing configuration |
| 15:00-17:00 | Log Aggregation | Set up ELK stack for log aggregation | ELK configuration |
| 17:00-18:00 | Alerting Rules | Create Prometheus alerting rules | Alert rules |

#### Developer 2 (Backend Dev - ERP Connectors)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Custom Metrics | Add business metrics to connectors | Business metrics |
| 11:00-12:30 | Health Indicators | Implement custom health indicators | Health indicators |
| 13:30-15:00 | Sync Monitoring | Add sync-specific metrics | Sync metrics |
| 15:00-17:00 | Connector Dashboards | Create connector-specific dashboards | Connector dashboards |
| 17:00-18:00 | SLO Definition | Define SLOs and SLIs | SLO document |

#### Developer 3 (Backend Dev - Data Processing)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Processing Metrics | Add data processing metrics | Processing metrics |
| 11:00-12:30 | Error Metrics | Add error tracking metrics | Error metrics |
| 13:30-15:00 | Pipeline Dashboard | Create data pipeline dashboard | Pipeline dashboard |
| 15:00-17:00 | Audit Dashboard | Finalize audit dashboard | Audit dashboard |
| 17:00-18:00 | Monitoring Runbook | Create operational runbooks | Runbooks |

---

### 3.10 Day 360: Final Integration & Deployment

#### Developer 1 (Lead Dev - Integration Architecture)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Final Integration | Complete end-to-end integration | Integrated system |
| 11:00-12:30 | Performance Validation | Validate all performance targets | Performance validation |
| 13:30-15:00 | Security Review | Final security review | Security sign-off |
| 15:00-17:00 | Deployment | Deploy to staging environment | Staging deployment |
| 17:00-18:00 | Handover | Knowledge transfer and handover | Handover documentation |

#### Developer 2 (Backend Dev - ERP Connectors)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Connector Integration | Final connector integration | Integrated connectors |
| 11:00-12:30 | ERP Connection Test | Test all ERP connections | ERP connection validation |
| 13:30-15:00 | Data Flow Testing | Validate all data flows | Data flow validation |
| 15:00-17:00 | Documentation | Complete connector documentation | Connector docs |
| 17:00-18:00 | Code Review | Final code review and fixes | Code review completion |

#### Developer 3 (Backend Dev - Data Processing)
**Time Allocation:** 8 hours

| Time | Task | Description | Deliverable |
|------|------|-------------|-------------|
| 09:00-11:00 | Pipeline Integration | Final data pipeline integration | Integrated pipelines |
| 11:00-12:30 | Import/Export Testing | Test all import/export scenarios | I/E validation |
| 13:30-15:00 | Error Handling Validation | Validate all error scenarios | Error handling validation |
| 15:00-17:00 | Documentation | Complete data processing docs | Processing docs |
| 17:00-18:00 | Sign-off | Obtain milestone sign-off | Milestone 36 sign-off |

---

## 4. API Gateway Specifications

### 4.1 Gateway Configuration Reference

```yaml
# Complete API Gateway Configuration
# File: application-gateway.yml

spring:
  application:
    name: smart-dairy-api-gateway
  
  cloud:
    gateway:
      httpclient:
        connect-timeout: 2000
        response-timeout: 30s
        pool:
          type: elastic
          max-connections: 1000
          max-idle-time: 10s
          max-life-time: 60s
        ssl:
          use-insecure-trust-manager: false
      
      httpserver:
        wiretap: false
      
      globalcors:
        cors-configurations:
          '[/**]':
            allowed-origin-patterns:
              - "https://*.smartdairy.com"
              - "https://localhost:[*]"
            allowed-methods: "*"
            allowed-headers: "*"
            allow-credentials: true
            max-age: 3600
      
      default-filters:
        # Request ID propagation
        - AddRequestHeader=X-Request-ID, ${requestId}
        - AddResponseHeader=X-Request-ID, ${requestId}
        
        # Global rate limiting
        - name: RequestRateLimiter
          args:
            redis-rate-limiter.replenishRate: 10000
            redis-rate-limiter.burstCapacity: 20000
            key-resolver: "#{@ipKeyResolver}"
        
        # Global retry
        - name: Retry
          args:
            retries: 3
            statuses: BAD_GATEWAY,SERVICE_UNAVAILABLE,GATEWAY_TIMEOUT
            methods: GET,POST,PUT,DELETE
            backoff:
              firstBackoff: 100ms
              maxBackoff: 1000ms
              factor: 2
        
        # Global circuit breaker
        - name: CircuitBreaker
          args:
            name: globalCircuitBreaker
            fallbackUri: forward:/fallback/service-unavailable
      
      routes:
        # ═══════════════════════════════════════════════════════════════
        # B2B PORTAL ROUTES
        # ═══════════════════════════════════════════════════════════════
        
        # B2B Orders
        - id: b2b-orders
          uri: lb://b2b-connector
          predicates:
            - Path=/api/b2b/orders/**
            - Method=GET,POST,PUT,PATCH,DELETE
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 500
                redis-rate-limiter.burstCapacity: 1000
                key-resolver: "#{@b2bClientKeyResolver}"
            - name: CircuitBreaker
              args:
                name: b2bOrdersCB
                fallbackUri: forward:/fallback/b2b/orders
            - name: Retry
              args:
                retries: 3
                statuses: SERVICE_UNAVAILABLE
            - AddRequestHeader=X-Portal, B2B
            - AddRequestHeader=X-Resource, Order
            - name: CacheRequestBody
              args:
                cache: requestBodyCache
          metadata:
            api.version: "1.0"
            api.group: "b2b"
            priority: high
            timeout: 10000
        
        # B2B Customers
        - id: b2b-customers
          uri: lb://b2b-connector
          predicates:
            - Path=/api/b2b/customers/**
            - Method=GET,POST,PUT,DELETE
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 300
                redis-rate-limiter.burstCapacity: 600
            - name: CircuitBreaker
              args:
                name: b2bCustomersCB
                fallbackUri: forward:/fallback/b2b/customers
            - AddRequestHeader=X-Portal, B2B
            - AddRequestHeader=X-Resource, Customer
          metadata:
            api.version: "1.0"
            api.group: "b2b"
            priority: high
            timeout: 5000
        
        # B2B Pricing
        - id: b2b-pricing
          uri: lb://b2b-connector
          predicates:
            - Path=/api/b2b/pricing/**
            - Method=GET
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 1000
                redis-rate-limiter.burstCapacity: 2000
            - name: CircuitBreaker
              args:
                name: b2bPricingCB
            - name: CacheRequest
              args:
                cacheName: b2bPricingCache
                ttl: 300
            - AddRequestHeader=X-Portal, B2B
            - AddRequestHeader=X-Resource, Pricing
          metadata:
            api.version: "1.0"
            api.group: "b2b"
            priority: critical
            timeout: 1000
            cacheable: true
        
        # B2B Inventory
        - id: b2b-inventory
          uri: lb://b2b-connector
          predicates:
            - Path=/api/b2b/inventory/**
            - Method=GET
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 2000
                redis-rate-limiter.burstCapacity: 4000
            - name: CircuitBreaker
              args:
                name: b2bInventoryCB
            - name: CacheRequest
              args:
                cacheName: b2bInventoryCache
                ttl: 60
            - AddRequestHeader=X-Portal, B2B
            - AddRequestHeader=X-Resource, Inventory
          metadata:
            api.version: "1.0"
            api.group: "b2b"
            priority: critical
            timeout: 500
            cacheable: true
        
        # B2B Contracts
        - id: b2b-contracts
          uri: lb://b2b-connector
          predicates:
            - Path=/api/b2b/contracts/**
            - Method=GET,POST,PUT
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 200
                redis-rate-limiter.burstCapacity: 400
            - name: CircuitBreaker
              args:
                name: b2bContractsCB
            - AddRequestHeader=X-Portal, B2B
            - AddRequestHeader=X-Resource, Contract
          metadata:
            api.version: "1.0"
            api.group: "b2b"
            priority: high
            timeout: 5000
        
        # B2B Reports
        - id: b2b-reports
          uri: lb://b2b-connector
          predicates:
            - Path=/api/b2b/reports/**
            - Method=GET,POST
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 100
                redis-rate-limiter.burstCapacity: 200
            - AddRequestHeader=X-Portal, B2B
            - AddRequestHeader=X-Resource, Report
          metadata:
            api.version: "1.0"
            api.group: "b2b"
            priority: low
            timeout: 60000
        
        # ═══════════════════════════════════════════════════════════════
        # B2C E-COMMERCE ROUTES
        # ═══════════════════════════════════════════════════════════════
        
        # B2C Products
        - id: b2c-products
          uri: lb://b2c-connector
          predicates:
            - Path=/api/b2c/products/**
            - Method=GET
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 5000
                redis-rate-limiter.burstCapacity: 10000
                key-resolver: "#{@ipKeyResolver}"
            - name: CircuitBreaker
              args:
                name: b2cProductsCB
            - name: CacheRequest
              args:
                cacheName: b2cProductsCache
                ttl: 600
            - AddRequestHeader=X-Portal, B2C
            - AddRequestHeader=X-Resource, Product
          metadata:
            api.version: "1.0"
            api.group: "b2c"
            priority: critical
            timeout: 300
            cacheable: true
        
        # B2C Categories
        - id: b2c-categories
          uri: lb://b2c-connector
          predicates:
            - Path=/api/b2c/categories/**
            - Method=GET
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 3000
                redis-rate-limiter.burstCapacity: 6000
            - name: CacheRequest
              args:
                cacheName: b2cCategoriesCache
                ttl: 3600
            - AddRequestHeader=X-Portal, B2C
            - AddRequestHeader=X-Resource, Category
          metadata:
            api.version: "1.0"
            api.group: "b2c"
            priority: high
            timeout: 200
            cacheable: true
        
        # B2C Orders
        - id: b2c-orders
          uri: lb://b2c-connector
          predicates:
            - Path=/api/b2c/orders/**
            - Method=GET,POST,PUT
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 2000
                redis-rate-limiter.burstCapacity: 4000
                key-resolver: "#{@userKeyResolver}"
            - name: CircuitBreaker
              args:
                name: b2cOrdersCB
                fallbackUri: forward:/fallback/b2c/orders
            - AddRequestHeader=X-Portal, B2C
            - AddRequestHeader=X-Resource, Order
          metadata:
            api.version: "1.0"
            api.group: "b2c"
            priority: critical
            timeout: 5000
        
        # B2C Cart
        - id: b2c-cart
          uri: lb://b2c-connector
          predicates:
            - Path=/api/b2c/cart/**
            - Method=GET,POST,PUT,DELETE
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 3000
                redis-rate-limiter.burstCapacity: 6000
            - name: CircuitBreaker
              args:
                name: b2cCartCB
            - AddRequestHeader=X-Portal, B2C
            - AddRequestHeader=X-Resource, Cart
          metadata:
            api.version: "1.0"
            api.group: "b2c"
            priority: high
            timeout: 2000
        
        # B2C Payments
        - id: b2c-payments
          uri: lb://b2c-connector
          predicates:
            - Path=/api/b2c/payments/**
            - Method=GET,POST
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 1000
                redis-rate-limiter.burstCapacity: 2000
            - name: CircuitBreaker
              args:
                name: b2cPaymentsCB
                fallbackUri: forward:/fallback/b2c/payments
            - name: Retry
              args:
                retries: 3
                statuses: SERVICE_UNAVAILABLE
                backoff:
                  firstBackoff: 500ms
                  maxBackoff: 2000ms
            - AddRequestHeader=X-Portal, B2C
            - AddRequestHeader=X-Resource, Payment
            - AddRequestHeader=X-Idempotent-Key, ${requestId}
          metadata:
            api.version: "1.0"
            api.group: "b2c"
            priority: critical
            timeout: 10000
            idempotent: true
        
        # B2C Customers
        - id: b2c-customers
          uri: lb://b2c-connector
          predicates:
            - Path=/api/b2c/customers/**
            - Method=GET,POST,PUT,DELETE
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 2000
                redis-rate-limiter.burstCapacity: 4000
            - name: CircuitBreaker
              args:
                name: b2cCustomersCB
            - AddRequestHeader=X-Portal, B2C
            - AddRequestHeader=X-Resource, Customer
          metadata:
            api.version: "1.0"
            api.group: "b2c"
            priority: high
            timeout: 3000
        
        # B2C Wishlist
        - id: b2c-wishlist
          uri: lb://b2c-connector
          predicates:
            - Path=/api/b2c/wishlist/**
            - Method=GET,POST,DELETE
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 2000
                redis-rate-limiter.burstCapacity: 4000
            - AddRequestHeader=X-Portal, B2C
            - AddRequestHeader=X-Resource, Wishlist
          metadata:
            api.version: "1.0"
            api.group: "b2c"
            priority: medium
            timeout: 2000
        
        # B2C Reviews
        - id: b2c-reviews
          uri: lb://b2c-connector
          predicates:
            - Path=/api/b2c/reviews/**
            - Method=GET,POST,PUT,DELETE
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 1000
                redis-rate-limiter.burstCapacity: 2000
            - AddRequestHeader=X-Portal, B2C
            - AddRequestHeader=X-Resource, Review
          metadata:
            api.version: "1.0"
            api.group: "b2c"
            priority: low
            timeout: 3000
        
        # ═══════════════════════════════════════════════════════════════
        # FARM MANAGEMENT ROUTES
        # ═══════════════════════════════════════════════════════════════
        
        # Farm Animals
        - id: farm-animals
          uri: lb://farm-connector
          predicates:
            - Path=/api/farm/animals/**
            - Method=GET,POST,PUT,DELETE
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 500
                redis-rate-limiter.burstCapacity: 1000
                key-resolver: "#{@farmKeyResolver}"
            - name: CircuitBreaker
              args:
                name: farmAnimalsCB
            - AddRequestHeader=X-Portal, FARM
            - AddRequestHeader=X-Resource, Animal
          metadata:
            api.version: "1.0"
            api.group: "farm"
            priority: high
            timeout: 5000
        
        # Farm Production
        - id: farm-production
          uri: lb://farm-connector
          predicates:
            - Path=/api/farm/production/**
            - Method=GET,POST,PUT
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 800
                redis-rate-limiter.burstCapacity: 1600
            - name: CircuitBreaker
              args:
                name: farmProductionCB
            - AddRequestHeader=X-Portal, FARM
            - AddRequestHeader=X-Resource, Production
          metadata:
            api.version: "1.0"
            api.group: "farm"
            priority: high
            timeout: 3000
        
        # Farm Health
        - id: farm-health
          uri: lb://farm-connector
          predicates:
            - Path=/api/farm/health/**
            - Method=GET,POST,PUT
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 600
                redis-rate-limiter.burstCapacity: 1200
            - name: CircuitBreaker
              args:
                name: farmHealthCB
            - AddRequestHeader=X-Portal, FARM
            - AddRequestHeader=X-Resource, Health
            - AddRequestHeader=X-Priority, High
          metadata:
            api.version: "1.0"
            api.group: "farm"
            priority: critical
            timeout: 2000
        
        # Farm Breeding
        - id: farm-breeding
          uri: lb://farm-connector
          predicates:
            - Path=/api/farm/breeding/**
            - Method=GET,POST,PUT
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 400
                redis-rate-limiter.burstCapacity: 800
            - AddRequestHeader=X-Portal, FARM
            - AddRequestHeader=X-Resource, Breeding
          metadata:
            api.version: "1.0"
            api.group: "farm"
            priority: medium
            timeout: 5000
        
        # Farm Feed
        - id: farm-feed
          uri: lb://farm-connector
          predicates:
            - Path=/api/farm/feed/**
            - Method=GET,POST,PUT
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 500
                redis-rate-limiter.burstCapacity: 1000
            - AddRequestHeader=X-Portal, FARM
            - AddRequestHeader=X-Resource, Feed
          metadata:
            api.version: "1.0"
            api.group: "farm"
            priority: medium
            timeout: 4000
        
        # Farm IoT
        - id: farm-iot
          uri: lb://farm-connector
          predicates:
            - Path=/api/farm/iot/**
            - Method=GET,POST
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 10000
                redis-rate-limiter.burstCapacity: 20000
                key-resolver: "#{@deviceKeyResolver}"
            - name: CircuitBreaker
              args:
                name: farmIotCB
            - AddRequestHeader=X-Portal, FARM
            - AddRequestHeader=X-Resource, IoT
            - AddRequestHeader=X-Data-Source, Sensor
          metadata:
            api.version: "1.0"
            api.group: "farm"
            priority: medium
            timeout: 1000
        
        # Farm Reports
        - id: farm-reports
          uri: lb://farm-connector
          predicates:
            - Path=/api/farm/reports/**
            - Method=GET,POST
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 200
                redis-rate-limiter.burstCapacity: 400
            - AddRequestHeader=X-Portal, FARM
            - AddRequestHeader=X-Resource, Report
          metadata:
            api.version: "1.0"
            api.group: "farm"
            priority: low
            timeout: 30000
        
        # ═══════════════════════════════════════════════════════════════
        # MOBILE APPLICATION ROUTES
        # ═══════════════════════════════════════════════════════════════
        
        # Mobile Sync
        - id: mobile-sync
          uri: lb://mobile-connector
          predicates:
            - Path=/api/mobile/sync/**
            - Method=GET,POST
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 2000
                redis-rate-limiter.burstCapacity: 4000
                key-resolver: "#{@deviceKeyResolver}"
            - name: CircuitBreaker
              args:
                name: mobileSyncCB
            - AddRequestHeader=X-Portal, MOBILE
            - AddRequestHeader=X-Resource, Sync
          metadata:
            api.version: "1.0"
            api.group: "mobile"
            priority: high
            timeout: 10000
        
        # Mobile Offline
        - id: mobile-offline
          uri: lb://mobile-connector
          predicates:
            - Path=/api/mobile/offline/**
            - Method=POST
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 1000
                redis-rate-limiter.burstCapacity: 2000
            - name: RequestSize
              args:
                maxSize: 50MB
            - AddRequestHeader=X-Portal, MOBILE
            - AddRequestHeader=X-Resource, Offline
            - AddRequestHeader=X-Sync-Mode, Batch
          metadata:
            api.version: "1.0"
            api.group: "mobile"
            priority: medium
            timeout: 60000
        
        # Mobile Push
        - id: mobile-push
          uri: lb://mobile-connector
          predicates:
            - Path=/api/mobile/push/**
            - Method=GET,POST,DELETE
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 1000
                redis-rate-limiter.burstCapacity: 2000
            - AddRequestHeader=X-Portal, MOBILE
            - AddRequestHeader=X-Resource, Push
          metadata:
            api.version: "1.0"
            api.group: "mobile"
            priority: medium
            timeout: 3000
        
        # Mobile Auth
        - id: mobile-auth
          uri: lb://mobile-connector
          predicates:
            - Path=/api/mobile/auth/**
            - Method=POST
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 500
                redis-rate-limiter.burstCapacity: 1000
            - AddRequestHeader=X-Portal, MOBILE
            - AddRequestHeader=X-Resource, Auth
          metadata:
            api.version: "1.0"
            api.group: "mobile"
            priority: critical
            timeout: 5000
        
        # ═══════════════════════════════════════════════════════════════
        # ERP CORE ROUTES
        # ═══════════════════════════════════════════════════════════════
        
        # ERP Master Data
        - id: erp-masterdata
          uri: lb://erp-adapter
          predicates:
            - Path=/api/erp/masterdata/**
            - Method=GET
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 1000
                redis-rate-limiter.burstCapacity: 2000
            - name: CircuitBreaker
              args:
                name: erpMasterdataCB
            - name: CacheRequest
              args:
                cacheName: erpMasterdataCache
                ttl: 3600
            - AddRequestHeader=X-System, ERP
            - AddRequestHeader=X-Resource, MasterData
          metadata:
            api.version: "1.0"
            api.group: "erp"
            priority: medium
            timeout: 2000
            cacheable: true
        
        # ERP Batch Processing
        - id: erp-batch
          uri: lb://erp-adapter
          predicates:
            - Path=/api/erp/batch/**
            - Method=POST
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 100
                redis-rate-limiter.burstCapacity: 200
            - name: RequestSize
              args:
                maxSize: 500MB
            - AddRequestHeader=X-System, ERP
            - AddRequestHeader=X-Resource, Batch
            - AddRequestHeader=X-Processing-Mode, Async
          metadata:
            api.version: "1.0"
            api.group: "erp"
            priority: low
            timeout: 300000
        
        # ERP Reports
        - id: erp-reports
          uri: lb://erp-adapter
          predicates:
            - Path=/api/erp/reports/**
            - Method=GET,POST
          filters:
            - StripPrefix=2
            - name: RequestRateLimiter
              args:
                redis-rate-limiter.replenishRate: 200
                redis-rate-limiter.burstCapacity: 400
            - AddRequestHeader=X-System, ERP
            - AddRequestHeader=X-Resource, Report
          metadata:
            api.version: "1.0"
            api.group: "erp"
            priority: low
            timeout: 60000

# Resilience Configuration
resilience4j:
  circuitbreaker:
    configs:
      default:
        register-health-indicator: true
        sliding-window-size: 100
        minimum-number-of-calls: 20
        permitted-number-of-calls-in-half-open-state: 10
        automatic-transition-from-open-to-half-open-enabled: true
        wait-duration-in-open-state: 30s
        failure-rate-threshold: 50
        slow-call-rate-threshold: 80
        slow-call-duration-threshold: 2s
        event-consumer-buffer-size: 100
    
    instances:
      globalCircuitBreaker:
        base-config: default
      
      b2bOrdersCB:
        base-config: default
        sliding-window-size: 50
        wait-duration-in-open-state: 30s
      
      b2cOrdersCB:
        base-config: default
        sliding-window-size: 100
        wait-duration-in-open-state: 20s
      
      b2cPaymentsCB:
        base-config: default
        sliding-window-size: 30
        failure-rate-threshold: 30
        wait-duration-in-open-state: 60s
      
      farmHealthCB:
        base-config: default
        sliding-window-size: 20
        wait-duration-in-open-state: 15s
      
      farmIotCB:
        base-config: default
        sliding-window-size: 200
        failure-rate-threshold: 70
  
  timelimiter:
    configs:
      default:
        timeout-duration: 5s
        cancel-running-future: true
  
  retry:
    configs:
      default:
        max-attempts: 3
        wait-duration: 100ms
        exponential-backoff-multiplier: 2
        retry-exceptions:
          - java.io.IOException
          - java.util.concurrent.TimeoutException
          - org.springframework.web.reactive.function.client.WebClientResponseException$ServiceUnavailable
```



## 5. Connector Implementations

### 5.1 B2B Portal Connector

```java
// B2BConnectorApplication.java
package com.smartdairy.connector.b2b;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.cloud.client.discovery.EnableDiscoveryClient;
import org.springframework.data.jpa.repository.config.EnableJpaAuditing;
import org.springframework.kafka.annotation.EnableKafka;
import org.springframework.scheduling.annotation.EnableAsync;
import org.springframework.scheduling.annotation.EnableScheduling;

@SpringBootApplication
@EnableDiscoveryClient
@EnableKafka
@EnableAsync
@EnableScheduling
@EnableJpaAuditing
public class B2BConnectorApplication {
    public static void main(String[] args) {
        SpringApplication.run(B2BConnectorApplication.class, args);
    }
}
```

```java
// B2BOrderService.java - Order Synchronization Service
package com.smartdairy.connector.b2b.service;

import com.smartdairy.connector.b2b.dto.*;
import com.smartdairy.connector.b2b.entity.*;
import com.smartdairy.connector.b2b.repository.*;
import com.smartdairy.erp.adapter.client.ErpClient;
import com.smartdairy.erp.adapter.dto.*;
import com.smartdairy.sync.engine.SyncEngine;
import com.smartdairy.sync.model.ChangeEvent;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.kafka.core.KafkaTemplate;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import reactor.core.publisher.Mono;

import java.math.BigDecimal;
import java.time.Instant;
import java.util.List;
import java.util.UUID;
import java.util.stream.Collectors;

@Slf4j
@Service
@RequiredArgsConstructor
public class B2BOrderService {
    
    private final B2BOrderRepository orderRepository;
    private final B2BOrderLineRepository orderLineRepository;
    private final ErpClient erpClient;
    private final SyncEngine syncEngine;
    private final KafkaTemplate<String, Object> kafkaTemplate;
    private final B2BOrderMapper orderMapper;
    
    private static final String ORDER_TOPIC = "b2b.orders.events";
    private static final String ENTITY_TYPE = "B2B_ORDER";
    
    /**
     * Create a new B2B order and sync to ERP
     */
    @Transactional
    public Mono<B2BOrderResponse> createOrder(CreateOrderRequest request) {
        log.info("Creating B2B order for customer: {}", request.getCustomerId());
        
        return validateOrder(request)
            .flatMap(valid -> enrichOrderData(request))
            .flatMap(enriched -> {
                // Create order entity
                B2BOrder order = B2BOrder.builder()
                    .orderId(generateOrderId())
                    .customerId(request.getCustomerId())
                    .customerCode(request.getCustomerCode())
                    .contractId(request.getContractId())
                    .orderDate(Instant.now())
                    .requestedDeliveryDate(request.getRequestedDeliveryDate())
                    .status(OrderStatus.PENDING)
                    .paymentTerms(request.getPaymentTerms())
                    .shippingAddress(request.getShippingAddress())
                    .billingAddress(request.getBillingAddress())
                    .specialInstructions(request.getSpecialInstructions())
                    .createdAt(Instant.now())
                    .createdBy(request.getUserId())
                    .build();
                
                // Create order lines
                List<B2BOrderLine> lines = request.getLines().stream()
                    .map(lineReq -> createOrderLine(order, lineReq))
                    .collect(Collectors.toList());
                
                // Calculate totals
                BigDecimal subtotal = lines.stream()
                    .map(B2BOrderLine::getLineTotal)
                    .reduce(BigDecimal.ZERO, BigDecimal::add);
                
                BigDecimal taxAmount = calculateTax(subtotal, request.getTaxRate());
                BigDecimal discountAmount = calculateDiscount(subtotal, request.getDiscountPercent());
                BigDecimal totalAmount = subtotal.add(taxAmount).subtract(discountAmount);
                
                order.setSubtotal(subtotal);
                order.setTaxAmount(taxAmount);
                order.setDiscountAmount(discountAmount);
                order.setTotalAmount(totalAmount);
                order.setCurrency(request.getCurrency());
                
                // Save order
                B2BOrder savedOrder = orderRepository.save(order);
                lines.forEach(line -> line.setOrder(savedOrder));
                orderLineRepository.saveAll(lines);
                savedOrder.setLines(lines);
                
                // Publish event
                publishOrderEvent(savedOrder, "ORDER_CREATED");
                
                // Sync to ERP asynchronously
                syncOrderToErp(savedOrder);
                
                return Mono.just(orderMapper.toResponse(savedOrder));
            });
    }
    
    /**
     * Sync order to ERP system
     */
    public Mono<Void> syncOrderToErp(B2BOrder order) {
        log.info("Syncing order {} to ERP", order.getOrderId());
        
        ErpSalesOrderRequest erpRequest = mapToErpSalesOrder(order);
        
        return erpClient.createSalesOrder(erpRequest)
            .flatMap(erpResponse -> {
                // Update order with ERP reference
                order.setErpOrderNumber(erpResponse.getOrderNumber());
                order.setErpOrderId(erpResponse.getErpOrderId());
                order.setStatus(OrderStatus.SYNCED_TO_ERP);
                order.setSyncedAt(Instant.now());
                orderRepository.save(order);
                
                // Publish sync event
                publishOrderEvent(order, "ORDER_SYNCED_TO_ERP");
                
                return Mono.empty();
            })
            .onErrorResume(error -> {
                log.error("Failed to sync order {} to ERP: {}", order.getOrderId(), error.getMessage());
                order.setStatus(OrderStatus.SYNC_FAILED);
                order.setSyncError(error.getMessage());
                orderRepository.save(order);
                
                // Trigger retry through sync engine
                triggerRetry(order, error);
                
                return Mono.empty();
            });
    }
    
    /**
     * Handle order status update from ERP
     */
    @Transactional
    public Mono<Void> handleErpStatusUpdate(String orderId, ErpOrderStatusUpdate update) {
        return Mono.fromCallable(() -> {
            B2BOrder order = orderRepository.findByOrderId(orderId)
                .orElseThrow(() -> new OrderNotFoundException(orderId));
            
            OrderStatus newStatus = mapErpStatus(update.getErpStatus());
            order.setStatus(newStatus);
            order.setErpStatus(update.getErpStatus());
            
            if (update.getDeliveryDate() != null) {
                order.setConfirmedDeliveryDate(update.getDeliveryDate());
            }
            
            if (update.getTrackingNumber() != null) {
                order.setTrackingNumber(update.getTrackingNumber());
            }
            
            order.setUpdatedAt(Instant.now());
            orderRepository.save(order);
            
            // Publish status change event
            publishOrderEvent(order, "ORDER_STATUS_CHANGED");
            
            return null;
        }).subscribeOn(Schedulers.boundedElastic());
    }
    
    /**
     * Get orders with pagination
     */
    public Page<B2BOrderResponse> getOrders(String customerId, OrderStatus status, 
                                           Instant fromDate, Instant toDate, Pageable pageable) {
        if (customerId != null && status != null) {
            return orderRepository.findByCustomerIdAndStatus(customerId, status, pageable)
                .map(orderMapper::toResponse);
        } else if (customerId != null) {
            return orderRepository.findByCustomerId(customerId, pageable)
                .map(orderMapper::toResponse);
        } else if (status != null) {
            return orderRepository.findByStatus(status, pageable)
                .map(orderMapper::toResponse);
        } else if (fromDate != null && toDate != null) {
            return orderRepository.findByOrderDateBetween(fromDate, toDate, pageable)
                .map(orderMapper::toResponse);
        }
        return orderRepository.findAll(pageable)
            .map(orderMapper::toResponse);
    }
    
    /**
     * Cancel order and sync to ERP
     */
    @Transactional
    public Mono<B2BOrderResponse> cancelOrder(String orderId, String reason, String userId) {
        return Mono.fromCallable(() -> {
            B2BOrder order = orderRepository.findByOrderId(orderId)
                .orElseThrow(() -> new OrderNotFoundException(orderId));
            
            if (!canCancel(order)) {
                throw new OrderCancellationException("Order cannot be cancelled in status: " + order.getStatus());
            }
            
            order.setStatus(OrderStatus.CANCELLED);
            order.setCancellationReason(reason);
            order.setCancelledBy(userId);
            order.setCancelledAt(Instant.now());
            order.setUpdatedAt(Instant.now());
            
            B2BOrder saved = orderRepository.save(order);
            
            // Sync cancellation to ERP
            syncOrderCancellationToErp(saved);
            
            // Publish event
            publishOrderEvent(saved, "ORDER_CANCELLED");
            
            return orderMapper.toResponse(saved);
        }).subscribeOn(Schedulers.boundedElastic());
    }
    
    private Mono<Boolean> validateOrder(CreateOrderRequest request) {
        return Mono.fromCallable(() -> {
            // Validate customer exists and is active
            // Validate contract terms
            // Validate product availability
            // Validate pricing
            // Validate delivery date
            return true;
        });
    }
    
    private Mono<CreateOrderRequest> enrichOrderData(CreateOrderRequest request) {
        return Mono.fromCallable(() -> {
            // Enrich with customer details
            // Apply contract pricing
            // Calculate taxes
            // Set payment terms
            return request;
        });
    }
    
    private B2BOrderLine createOrderLine(B2BOrder order, OrderLineRequest lineReq) {
        return B2BOrderLine.builder()
            .order(order)
            .productId(lineReq.getProductId())
            .productCode(lineReq.getProductCode())
            .productName(lineReq.getProductName())
            .quantity(lineReq.getQuantity())
            .unitOfMeasure(lineReq.getUnitOfMeasure())
            .unitPrice(lineReq.getUnitPrice())
            .lineTotal(lineReq.getUnitPrice().multiply(BigDecimal.valueOf(lineReq.getQuantity())))
            .discountPercent(lineReq.getDiscountPercent())
            .discountAmount(calculateLineDiscount(lineReq))
            .build();
    }
    
    private BigDecimal calculateTax(BigDecimal subtotal, BigDecimal taxRate) {
        return subtotal.multiply(taxRate).divide(BigDecimal.valueOf(100), 2, RoundingMode.HALF_UP);
    }
    
    private BigDecimal calculateDiscount(BigDecimal subtotal, BigDecimal discountPercent) {
        if (discountPercent == null || discountPercent.compareTo(BigDecimal.ZERO) == 0) {
            return BigDecimal.ZERO;
        }
        return subtotal.multiply(discountPercent).divide(BigDecimal.valueOf(100), 2, RoundingMode.HALF_UP);
    }
    
    private BigDecimal calculateLineDiscount(OrderLineRequest line) {
        if (line.getDiscountPercent() == null) {
            return BigDecimal.ZERO;
        }
        BigDecimal lineTotal = line.getUnitPrice().multiply(BigDecimal.valueOf(line.getQuantity()));
        return lineTotal.multiply(line.getDiscountPercent()).divide(BigDecimal.valueOf(100), 2, RoundingMode.HALF_UP);
    }
    
    private String generateOrderId() {
        return "B2B-" + UUID.randomUUID().toString().substring(0, 8).toUpperCase();
    }
    
    private ErpSalesOrderRequest mapToErpSalesOrder(B2BOrder order) {
        return ErpSalesOrderRequest.builder()
            .externalOrderId(order.getOrderId())
            .customerCode(order.getCustomerCode())
            .orderDate(order.getOrderDate())
            .requestedDeliveryDate(order.getRequestedDeliveryDate())
            .paymentTerms(order.getPaymentTerms())
            .currency(order.getCurrency())
            .lines(order.getLines().stream()
                .map(line -> ErpOrderLine.builder()
                    .productCode(line.getProductCode())
                    .quantity(line.getQuantity())
                    .unitOfMeasure(line.getUnitOfMeasure())
                    .unitPrice(line.getUnitPrice())
                    .discountPercent(line.getDiscountPercent())
                    .build())
                .collect(Collectors.toList()))
            .shippingAddress(mapAddress(order.getShippingAddress()))
            .billingAddress(mapAddress(order.getBillingAddress()))
            .specialInstructions(order.getSpecialInstructions())
            .build();
    }
    
    private void publishOrderEvent(B2BOrder order, String eventType) {
        OrderEvent event = OrderEvent.builder()
            .eventId(UUID.randomUUID().toString())
            .eventType(eventType)
            .orderId(order.getOrderId())
            .customerId(order.getCustomerId())
            .status(order.getStatus())
            .timestamp(Instant.now())
            .payload(orderMapper.toEventPayload(order))
            .build();
        
        kafkaTemplate.send(ORDER_TOPIC, order.getOrderId(), event);
    }
    
    private void triggerRetry(B2BOrder order, Throwable error) {
        ChangeEvent changeEvent = ChangeEvent.builder()
            .changeId(UUID.randomUUID().toString())
            .entityType(ENTITY_TYPE)
            .entityId(order.getOrderId())
            .changeType("RETRY_SYNC")
            .sourceSystem("B2B_PORTAL")
            .targetSystem("ERP")
            .retryCount(order.getRetryCount())
            .build();
        
        syncEngine.scheduleRetry(changeEvent);
    }
    
    private OrderStatus mapErpStatus(String erpStatus) {
        return switch (erpStatus) {
            case "APPROVED" -> OrderStatus.APPROVED;
            case "REJECTED" -> OrderStatus.REJECTED;
            case "PROCESSING" -> OrderStatus.PROCESSING;
            case "SHIPPED" -> OrderStatus.SHIPPED;
            case "DELIVERED" -> OrderStatus.DELIVERED;
            case "INVOICED" -> OrderStatus.INVOICED;
            case "PAID" -> OrderStatus.PAID;
            default -> OrderStatus.PENDING;
        };
    }
}
```

### 5.2 B2C E-commerce Connector

```java
// B2CProductService.java - Product Synchronization
package com.smartdairy.connector.b2c.service;

import com.smartdairy.connector.b2c.dto.*;
import com.smartdairy.connector.b2c.entity.*;
import com.smartdairy.connector.b2c.repository.*;
import com.smartdairy.erp.adapter.client.ErpClient;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.cache.annotation.CacheEvict;
import org.springframework.cache.annotation.Cacheable;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.scheduling.annotation.Scheduled;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.math.BigDecimal;
import java.time.Instant;
import java.util.List;
import java.util.Optional;
import java.util.stream.Collectors;

@Slf4j
@Service
@RequiredArgsConstructor
public class B2CProductService {
    
    private final B2CProductRepository productRepository;
    private final B2CCategoryRepository categoryRepository;
    private final ErpClient erpClient;
    private final ProductMapper productMapper;
    private final PriceCalculator priceCalculator;
    
    private static final String PRODUCT_CACHE = "b2cProducts";
    private static final String CATEGORY_CACHE = "b2cCategories";
    
    /**
     * Get product by ID with caching
     */
    @Cacheable(value = PRODUCT_CACHE, key = "#productId")
    public B2CProductResponse getProduct(String productId) {
        B2CProduct product = productRepository.findByProductId(productId)
            .orElseThrow(() -> new ProductNotFoundException(productId));
        return productMapper.toResponse(product);
    }
    
    /**
     * Get products with filters and pagination
     */
    public Page<B2CProductResponse> getProducts(ProductFilter filter, Pageable pageable) {
        if (filter.getCategoryId() != null) {
            return productRepository.findByCategoryIdAndActiveTrue(filter.getCategoryId(), pageable)
                .map(productMapper::toResponse);
        } else if (filter.getSearchQuery() != null) {
            return productRepository.searchProducts(filter.getSearchQuery(), pageable)
                .map(productMapper::toResponse);
        } else if (filter.getMinPrice() != null && filter.getMaxPrice() != null) {
            return productRepository.findByPriceBetween(filter.getMinPrice(), filter.getMaxPrice(), pageable)
                .map(productMapper::toResponse);
        }
        return productRepository.findByActiveTrue(pageable)
            .map(productMapper::toResponse);
    }
    
    /**
     * Sync products from ERP
     */
    @Scheduled(fixedRate = 300000) // Every 5 minutes
    @Transactional
    public void syncProductsFromErp() {
        log.info("Starting product sync from ERP");
        
        Instant lastSync = getLastSyncTime();
        
        erpClient.getProductUpdates(lastSync)
            .collectList()
            .subscribe(erpProducts -> {
                for (ErpProduct erpProduct : erpProducts) {
                    syncProduct(erpProduct);
                }
                updateLastSyncTime(Instant.now());
                log.info("Product sync completed. Updated {} products", erpProducts.size());
            }, error -> {
                log.error("Product sync failed: {}", error.getMessage());
            });
    }
    
    /**
     * Sync single product
     */
    @Transactional
    @CacheEvict(value = PRODUCT_CACHE, key = "#erpProduct.productId")
    public void syncProduct(ErpProduct erpProduct) {
        Optional<B2CProduct> existing = productRepository.findBySku(erpProduct.getSku());
        
        B2CProduct product;
        if (existing.isPresent()) {
            product = existing.get();
            updateProductFromErp(product, erpProduct);
        } else {
            product = createProductFromErp(erpProduct);
        }
        
        // Calculate consumer price with markup
        BigDecimal consumerPrice = priceCalculator.calculateConsumerPrice(
            erpProduct.getBasePrice(),
            erpProduct.getCategoryCode()
        );
        product.setConsumerPrice(consumerPrice);
        
        // Apply promotional pricing if active
        if (erpProduct.getPromotionalPrice() != null && 
            erpProduct.getPromotionStart() != null && 
            erpProduct.getPromotionEnd() != null) {
            Instant now = Instant.now();
            if (now.isAfter(erpProduct.getPromotionStart()) && 
                now.isBefore(erpProduct.getPromotionEnd())) {
                product.setSalePrice(erpProduct.getPromotionalPrice());
                product.setOnSale(true);
            }
        }
        
        product.setUpdatedAt(Instant.now());
        productRepository.save(product);
        
        log.debug("Synced product: {}", product.getSku());
    }
    
    /**
     * Get categories with caching
     */
    @Cacheable(value = CATEGORY_CACHE)
    public List<B2CCategoryResponse> getCategories() {
        return categoryRepository.findByActiveTrueOrderBySortOrder()
            .stream()
            .map(productMapper::toCategoryResponse)
            .collect(Collectors.toList());
    }
    
    /**
     * Update inventory from ERP
     */
    @Transactional
    @CacheEvict(value = PRODUCT_CACHE, allEntries = true)
    public void updateInventory(List<InventoryUpdate> updates) {
        for (InventoryUpdate update : updates) {
            productRepository.findBySku(update.getSku())
                .ifPresent(product -> {
                    product.setQuantityAvailable(update.getQuantityAvailable());
                    product.setQuantityReserved(update.getQuantityReserved());
                    product.setQuantityOnOrder(update.getQuantityOnOrder());
                    product.setStockStatus(calculateStockStatus(update));
                    product.setUpdatedAt(Instant.now());
                    productRepository.save(product);
                });
        }
    }
    
    private B2CProduct createProductFromErp(ErpProduct erpProduct) {
        return B2CProduct.builder()
            .productId(generateProductId())
            .sku(erpProduct.getSku())
            .name(erpProduct.getName())
            .description(erpProduct.getDescription())
            .shortDescription(erpProduct.getShortDescription())
            .basePrice(erpProduct.getBasePrice())
            .categoryId(erpProduct.getCategoryCode())
            .brand(erpProduct.getBrand())
            .weight(erpProduct.getWeight())
            .weightUnit(erpProduct.getWeightUnit())
            .images(erpProduct.getImages())
            .attributes(erpProduct.getAttributes())
            .active(erpProduct.isActive())
            .createdAt(Instant.now())
            .build();
    }
    
    private void updateProductFromErp(B2CProduct product, ErpProduct erpProduct) {
        product.setName(erpProduct.getName());
        product.setDescription(erpProduct.getDescription());
        product.setShortDescription(erpProduct.getShortDescription());
        product.setBasePrice(erpProduct.getBasePrice());
        product.setCategoryId(erpProduct.getCategoryCode());
        product.setBrand(erpProduct.getBrand());
        product.setWeight(erpProduct.getWeight());
        product.setImages(erpProduct.getImages());
        product.setAttributes(erpProduct.getAttributes());
        product.setActive(erpProduct.isActive());
    }
    
    private StockStatus calculateStockStatus(InventoryUpdate update) {
        int available = update.getQuantityAvailable() - update.getQuantityReserved();
        if (available <= 0) {
            return StockStatus.OUT_OF_STOCK;
        } else if (available < 10) {
            return StockStatus.LOW_STOCK;
        }
        return StockStatus.IN_STOCK;
    }
    
    private String generateProductId() {
        return "PROD-" + UUID.randomUUID().toString().substring(0, 8).toUpperCase();
    }
}
```

### 5.3 Farm Management Connector

```java
// FarmAnimalService.java - Livestock Management
package com.smartdairy.connector.farm.service;

import com.smartdairy.connector.farm.dto.*;
import com.smartdairy.connector.farm.entity.*;
import com.smartdairy.connector.farm.repository.*;
import com.smartdairy.erp.adapter.client.ErpClient;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.time.Instant;
import java.time.LocalDate;
import java.util.List;
import java.util.Optional;
import java.util.UUID;

@Slf4j
@Service
@RequiredArgsConstructor
public class FarmAnimalService {
    
    private final AnimalRepository animalRepository;
    private final HealthRecordRepository healthRecordRepository;
    private final ProductionRecordRepository productionRecordRepository;
    private final BreedingRecordRepository breedingRecordRepository;
    private final ErpClient erpClient;
    private final AnimalMapper animalMapper;
    
    /**
     * Register new animal
     */
    @Transactional
    public AnimalResponse registerAnimal(RegisterAnimalRequest request) {
        log.info("Registering new animal: {}", request.getTagNumber());
        
        // Validate tag number uniqueness
        if (animalRepository.existsByTagNumber(request.getTagNumber())) {
            throw new DuplicateTagException(request.getTagNumber());
        }
        
        Animal animal = Animal.builder()
            .animalId(generateAnimalId())
            .tagNumber(request.getTagNumber())
            .rfidTag(request.getRfidTag())
            .name(request.getName())
            .species(request.getSpecies())
            .breed(request.getBreed())
            .gender(request.getGender())
            .birthDate(request.getBirthDate())
            .birthWeight(request.getBirthWeight())
            .color(request.getColor())
            .identificationMarks(request.getIdentificationMarks())
            .sireId(request.getSireId())
            .damId(request.getDamId())
            .herdId(request.getHerdId())
            .barnId(request.getBarnId())
            .status(AnimalStatus.ACTIVE)
            .acquisitionDate(request.getAcquisitionDate())
            .acquisitionType(request.getAcquisitionType())
            .purchasePrice(request.getPurchasePrice())
            .vendorId(request.getVendorId())
            .notes(request.getNotes())
            .createdAt(Instant.now())
            .createdBy(request.getUserId())
            .build();
        
        Animal saved = animalRepository.save(animal);
        
        // Sync to ERP
        syncAnimalToErp(saved);
        
        return animalMapper.toResponse(saved);
    }
    
    /**
     * Record health event
     */
    @Transactional
    public HealthRecordResponse recordHealthEvent(HealthEventRequest request) {
        Animal animal = animalRepository.findByAnimalId(request.getAnimalId())
            .orElseThrow(() -> new AnimalNotFoundException(request.getAnimalId()));
        
        HealthRecord record = HealthRecord.builder()
            .recordId(generateRecordId())
            .animal(animal)
            .recordDate(request.getRecordDate())
            .eventType(request.getEventType())
            .symptoms(request.getSymptoms())
            .diagnosis(request.getDiagnosis())
            .treatment(request.getTreatment())
            .medication(request.getMedication())
            .dosage(request.getDosage())
            .administeredBy(request.getAdministeredBy())
            .withdrawalPeriod(request.getWithdrawalPeriod())
            .withdrawalUntil(request.getWithdrawalUntil())
            .cost(request.getCost())
            .followUpDate(request.getFollowUpDate())
            .notes(request.getNotes())
            .createdAt(Instant.now())
            .build();
        
        HealthRecord saved = healthRecordRepository.save(record);
        
        // Update animal health status
        updateAnimalHealthStatus(animal, request);
        
        // Sync to ERP
        syncHealthRecordToErp(saved);
        
        // Alert if critical
        if (isCriticalEvent(request.getEventType())) {
            alertService.sendHealthAlert(animal, record);
        }
        
        return animalMapper.toHealthResponse(saved);
    }
    
    /**
     * Record production (milk, eggs, etc.)
     */
    @Transactional
    public ProductionRecordResponse recordProduction(ProductionRecordRequest request) {
        Animal animal = animalRepository.findByAnimalId(request.getAnimalId())
            .orElseThrow(() -> new AnimalNotFoundException(request.getAnimalId()));
        
        ProductionRecord record = ProductionRecord.builder()
            .recordId(generateRecordId())
            .animal(animal)
            .productionDate(request.getProductionDate())
            .session(request.getSession()) // AM, PM
            .productType(request.getProductType()) // MILK, EGGS
            .quantity(request.getQuantity())
            .unitOfMeasure(request.getUnitOfMeasure())
            .quality(request.getQuality())
            .fatContent(request.getFatContent())
            .proteinContent(request.getProteinContent())
            .somaticCellCount(request.getSomaticCellCount())
            .temperature(request.getTemperature())
            .collectedBy(request.getCollectedBy())
            .storageLocation(request.getStorageLocation())
            .notes(request.getNotes())
            .createdAt(Instant.now())
            .build();
        
        ProductionRecord saved = productionRecordRepository.save(record);
        
        // Update animal production statistics
        updateAnimalProductionStats(animal, request);
        
        // Sync to ERP
        syncProductionRecordToErp(saved);
        
        return animalMapper.toProductionResponse(saved);
    }
    
    /**
     * Process IoT sensor data
     */
    @Transactional
    public void processIoTData(String deviceId, List<SensorReading> readings) {
        for (SensorReading reading : readings) {
            // Find animal by RFID or device association
            Optional<Animal> animal = animalRepository.findByRfidTag(reading.getRfidTag());
            
            if (animal.isPresent()) {
                Animal a = animal.get();
                
                switch (reading.getSensorType()) {
                    case TEMPERATURE:
                        a.setCurrentTemperature(reading.getValue());
                        if (reading.getValue() > 39.5) {
                            alertService.sendTemperatureAlert(a, reading);
                        }
                        break;
                    case ACTIVITY:
                        a.setActivityLevel(reading.getValue());
                        if (reading.getValue() < 10) { // Low activity
                            alertService.sendLowActivityAlert(a, reading);
                        }
                        break;
                    case RUMINATION:
                        a.setRuminationMinutes((int) reading.getValue());
                        break;
                    case MILK_YIELD:
                        // Auto-record production data
                        autoRecordProduction(a, reading);
                        break;
                }
                
                a.setLastSensorUpdate(Instant.now());
                animalRepository.save(a);
            }
        }
    }
    
    /**
     * Get animal dashboard data
     */
    public AnimalDashboardResponse getAnimalDashboard(String animalId) {
        Animal animal = animalRepository.findByAnimalId(animalId)
            .orElseThrow(() -> new AnimalNotFoundException(animalId));
        
        return AnimalDashboardResponse.builder()
            .animal(animalMapper.toResponse(animal))
            .recentHealthRecords(healthRecordRepository.findTop5ByAnimalOrderByRecordDateDesc(animal))
            .recentProduction(productionRecordRepository.findTop30ByAnimalOrderByProductionDateDesc(animal))
            .lactationStats(calculateLactationStats(animal))
            .healthStatus(animal.getHealthStatus())
            .nextScheduledEvent(getNextScheduledEvent(animal))
            .build();
    }
    
    private void syncAnimalToErp(Animal animal) {
        ErpAnimalRequest request = animalMapper.toErpRequest(animal);
        erpClient.syncAnimal(request)
            .subscribe(
                response -> log.debug("Animal {} synced to ERP", animal.getAnimalId()),
                error -> log.error("Failed to sync animal {}: {}", animal.getAnimalId(), error.getMessage())
            );
    }
    
    private void syncHealthRecordToErp(HealthRecord record) {
        ErpHealthRecordRequest request = animalMapper.toErpHealthRequest(record);
        erpClient.syncHealthRecord(request)
            .subscribe(
                response -> log.debug("Health record {} synced to ERP", record.getRecordId()),
                error -> log.error("Failed to sync health record: {}", error.getMessage())
            );
    }
    
    private void syncProductionRecordToErp(ProductionRecord record) {
        ErpProductionRecordRequest request = animalMapper.toErpProductionRequest(record);
        erpClient.syncProductionRecord(request)
            .subscribe(
                response -> log.debug("Production record {} synced to ERP", record.getRecordId()),
                error -> log.error("Failed to sync production record: {}", error.getMessage())
            );
    }
    
    private boolean isCriticalEvent(HealthEventType eventType) {
        return eventType == HealthEventType.DISEASE ||
               eventType == HealthEventType.INJURY ||
               eventType == HealthEventType.EMERGENCY;
    }
    
    private String generateAnimalId() {
        return "ANM-" + UUID.randomUUID().toString().substring(0, 8).toUpperCase();
    }
    
    private String generateRecordId() {
        return "REC-" + UUID.randomUUID().toString().substring(0, 8).toUpperCase();
    }
}
```

### 5.4 Mobile Connector

```java
// MobileSyncService.java - Mobile Synchronization
package com.smartdairy.connector.mobile.service;

import com.smartdairy.connector.mobile.dto.*;
import com.smartdairy.connector.mobile.entity.*;
import com.smartdairy.connector.mobile.repository.*;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.time.Instant;
import java.util.ArrayList;
import java.util.List;
import java.util.UUID;

@Slf4j
@Service
@RequiredArgsConstructor
public class MobileSyncService {
    
    private final OfflineChangeRepository offlineChangeRepository;
    private final SyncCheckpointRepository checkpointRepository;
    private final B2BOrderService b2bOrderService;
    private final B2COrderService b2cOrderService;
    private final FarmAnimalService farmAnimalService;
    
    /**
     * Process offline changes from mobile device
     */
    @Transactional
    public SyncResult processOfflineChanges(String deviceId, String userId, 
                                           List<OfflineChange> changes) {
        log.info("Processing {} offline changes from device: {}", changes.size(), deviceId);
        
        SyncResult result = new SyncResult();
        
        for (OfflineChange change : changes) {
            try {
                processSingleChange(deviceId, userId, change);
                result.addSuccess(change.getChangeId());
            } catch (Exception e) {
                log.error("Failed to process offline change {}: {}", 
                    change.getChangeId(), e.getMessage());
                result.addFailure(change.getChangeId(), e.getMessage());
            }
        }
        
        // Update device checkpoint
        updateCheckpoint(deviceId, userId, changes);
        
        return result;
    }
    
    /**
     * Get changes since last sync
     */
    public SyncDataResponse getChangesSince(String deviceId, String entityType, 
                                           Instant lastSync) {
        SyncCheckpoint checkpoint = checkpointRepository
            .findByDeviceIdAndEntityType(deviceId, entityType)
            .orElse(new SyncCheckpoint());
        
        Instant syncFrom = checkpoint.getLastSyncTime() != null 
            ? checkpoint.getLastSyncTime() 
            : lastSync;
        
        List<ChangeItem> changes = new ArrayList<>();
        
        switch (entityType) {
            case "PRODUCTS":
                changes = getProductChanges(syncFrom);
                break;
            case "ORDERS":
                changes = getOrderChanges(deviceId, syncFrom);
                break;
            case "ANIMALS":
                changes = getAnimalChanges(deviceId, syncFrom);
                break;
            case "HEALTH_RECORDS":
                changes = getHealthRecordChanges(deviceId, syncFrom);
                break;
        }
        
        return SyncDataResponse.builder()
            .entityType(entityType)
            .changes(changes)
            .serverTimestamp(Instant.now())
            .hasMoreChanges(changes.size() >= 100)
            .build();
    }
    
    /**
     * Get compressed sync package for initial load
     */
    public CompressedSyncPackage getInitialSyncPackage(String deviceId, String userId) {
        // Get user's data based on roles
        List<String> roles = getUserRoles(userId);
        
        CompressedSyncPackage.CompressedSyncPackageBuilder packageBuilder = 
            CompressedSyncPackage.builder()
                .packageId(UUID.randomUUID().toString())
                .generatedAt(Instant.now())
                .compressionType("gzip");
        
        if (roles.contains("B2B_CUSTOMER")) {
            packageBuilder.b2bData(getB2BInitialData(userId));
        }
        
        if (roles.contains("B2C_CUSTOMER")) {
            packageBuilder.b2cData(getB2CInitialData());
        }
        
        if (roles.contains("FARM_MANAGER") || roles.contains("FARM_WORKER")) {
            packageBuilder.farmData(getFarmInitialData(userId));
        }
        
        return packageBuilder.build();
    }
    
    private void processSingleChange(String deviceId, String userId, OfflineChange change) {
        // Store the change record
        OfflineChangeRecord record = OfflineChangeRecord.builder()
            .changeId(change.getChangeId())
            .deviceId(deviceId)
            .userId(userId)
            .entityType(change.getEntityType())
            .entityId(change.getEntityId())
            .operation(change.getOperation())
            .payload(change.getPayload())
            .clientTimestamp(change.getTimestamp())
            .serverTimestamp(Instant.now())
            .status(ProcessingStatus.PENDING)
            .build();
        
        offlineChangeRepository.save(record);
        
        // Process based on entity type
        switch (change.getEntityType()) {
            case "B2B_ORDER":
                processB2BOrderChange(change, userId);
                break;
            case "B2C_ORDER":
                processB2COrderChange(change, userId);
                break;
            case "ANIMAL":
                processAnimalChange(change, userId);
                break;
            case "HEALTH_RECORD":
                processHealthRecordChange(change, userId);
                break;
            case "PRODUCTION_RECORD":
                processProductionRecordChange(change, userId);
                break;
            default:
                throw new UnsupportedEntityTypeException(change.getEntityType());
        }
        
        // Mark as processed
        record.setStatus(ProcessingStatus.COMPLETED);
        record.setProcessedAt(Instant.now());
        offlineChangeRepository.save(record);
    }
    
    private void processB2BOrderChange(OfflineChange change, String userId) {
        switch (change.getOperation()) {
            case CREATE:
                CreateOrderRequest createRequest = parsePayload(change.getPayload(), CreateOrderRequest.class);
                createRequest.setUserId(userId);
                createRequest.setOfflineCreated(true);
                createRequest.setOfflineChangeId(change.getChangeId());
                b2bOrderService.createOrder(createRequest).block();
                break;
            case UPDATE:
                UpdateOrderRequest updateRequest = parsePayload(change.getPayload(), UpdateOrderRequest.class);
                b2bOrderService.updateOrder(updateRequest.getOrderId(), updateRequest).block();
                break;
            case DELETE:
                String orderId = change.getEntityId();
                b2bOrderService.cancelOrder(orderId, "Cancelled from mobile", userId).block();
                break;
        }
    }
    
    private void processAnimalChange(OfflineChange change, String userId) {
        switch (change.getOperation()) {
            case CREATE:
                RegisterAnimalRequest request = parsePayload(change.getPayload(), RegisterAnimalRequest.class);
                request.setUserId(userId);
                farmAnimalService.registerAnimal(request);
                break;
            case UPDATE:
                UpdateAnimalRequest updateRequest = parsePayload(change.getPayload(), UpdateAnimalRequest.class);
                farmAnimalService.updateAnimal(updateRequest.getAnimalId(), updateRequest);
                break;
        }
    }
    
    private void processHealthRecordChange(OfflineChange change, String userId) {
        HealthEventRequest request = parsePayload(change.getPayload(), HealthEventRequest.class);
        request.setAdministeredBy(userId);
        farmAnimalService.recordHealthEvent(request);
    }
    
    private void processProductionRecordChange(OfflineChange change, String userId) {
        ProductionRecordRequest request = parsePayload(change.getPayload(), ProductionRecordRequest.class);
        request.setCollectedBy(userId);
        farmAnimalService.recordProduction(request);
    }
    
    private void updateCheckpoint(String deviceId, String userId, List<OfflineChange> changes) {
        if (!changes.isEmpty()) {
            Instant latestTimestamp = changes.stream()
                .map(OfflineChange::getTimestamp)
                .max(Instant::compareTo)
                .orElse(Instant.now());
            
            SyncCheckpoint checkpoint = SyncCheckpoint.builder()
                .deviceId(deviceId)
                .userId(userId)
                .lastSyncTime(latestTimestamp)
                .updatedAt(Instant.now())
                .build();
            
            checkpointRepository.save(checkpoint);
        }
    }
    
    private <T> T parsePayload(String payload, Class<T> clazz) {
        return objectMapper.readValue(payload, clazz);
    }
}
```

### 5.5 Data Mapping Specifications

```yaml
# Data Mapping Configuration
# File: data-mapping.yml

mappings:
  # ═══════════════════════════════════════════════════════════════
  # B2B Order Mappings
  # ═══════════════════════════════════════════════════════════════
  b2b_order:
    source: portal.b2b_order
    target: erp.sales_order
    direction: bidirectional
    
    fields:
      - source: order_id
        target: external_order_id
        type: string
        required: true
        validation: "^[A-Z0-9-]+$"
      
      - source: customer_id
        target: customer_code
        type: string
        required: true
        lookup:
          table: erp.customer
          key: customer_id
          value: customer_code
      
      - source: order_date
        target: document_date
        type: datetime
        format: "ISO8601"
      
      - source: requested_delivery_date
        target: requested_delivery_date
        type: date
        format: "yyyy-MM-dd"
        validation:
          min: "today"
          max: "today+90days"
      
      - source: status
        target: order_status
        type: enum
        values:
          PENDING: "OPEN"
          APPROVED: "APPROVED"
          SYNCED_TO_ERP: "APPROVED"
          PROCESSING: "IN_PROCESS"
          SHIPPED: "SHIPPED"
          DELIVERED: "DELIVERED"
          CANCELLED: "CANCELLED"
      
      - source: total_amount
        target: net_amount
        type: decimal
        precision: 15
        scale: 2
      
      - source: currency
        target: currency_code
        type: string
        default: "USD"
      
      - source: payment_terms
        target: payment_terms_code
        type: string
        lookup:
          table: erp.payment_terms
          key: portal_code
          value: erp_code
    
    line_items:
      source: portal.b2b_order_line
      target: erp.sales_order_line
      
      fields:
        - source: product_code
          target: material_code
          type: string
          required: true
          lookup:
            table: erp.material
            key: portal_sku
            value: material_code
        
        - source: quantity
          target: order_quantity
          type: decimal
          precision: 15
          scale: 3
          validation:
            min: 0.001
        
        - source: unit_price
          target: net_price
          type: decimal
          precision: 15
          scale: 2
        
        - source: discount_percent
          target: discount_percentage
          type: decimal
          precision: 5
          scale: 2
          validation:
            min: 0
            max: 100
  
  # ═══════════════════════════════════════════════════════════════
  # B2C Product Mappings
  # ═══════════════════════════════════════════════════════════════
  b2c_product:
    source: erp.material
    target: portal.b2c_product
    direction: erp_to_portal
    
    fields:
      - source: material_code
        target: sku
        type: string
        required: true
      
      - source: material_name
        target: name
        type: string
        transform: "titleCase"
      
      - source: material_description
        target: description
        type: string
      
      - source: base_price
        target: base_price
        type: decimal
        transform: "currency:USD"
      
      - source: product_category
        target: category_id
        type: string
        lookup:
          table: mapping.category
          key: erp_category
          value: b2c_category_id
      
      - source: gross_weight
        target: weight
        type: decimal
        precision: 10
        scale: 3
      
      - source: weight_unit
        target: weight_unit
        type: string
      
      - source: is_active
        target: active
        type: boolean
  
  # ═══════════════════════════════════════════════════════════════
  # Farm Animal Mappings
  # ═══════════════════════════════════════════════════════════════
  farm_animal:
    source: portal.animal
    target: erp.livestock_master
    direction: bidirectional
    
    fields:
      - source: animal_id
        target: livestock_id
        type: string
        required: true
      
      - source: tag_number
        target: ear_tag_number
        type: string
        required: true
      
      - source: rfid_tag
        target: rfid_identifier
        type: string
      
      - source: species
        target: species_code
        type: enum
        values:
          CATTLE: "BOV"
          SHEEP: "OVI"
          GOAT: "CAP"
          PIG: "POR"
      
      - source: breed
        target: breed_code
        type: string
        lookup:
          table: mapping.breed
          key: common_name
          value: erp_breed_code
      
      - source: gender
        target: sex_code
        type: enum
        values:
          MALE: "M"
          FEMALE: "F"
      
      - source: birth_date
        target: date_of_birth
        type: date
        format: "yyyy-MM-dd"
      
      - source: birth_weight
        target: birth_weight_kg
        type: decimal
        precision: 8
        scale: 2
      
      - source: status
        target: livestock_status
        type: enum
        values:
          ACTIVE: "ACTIVE"
          SOLD: "DISPOSED"
          DECEASED: "DECEASED"
          BREEDING: "BREEDING"
  
  # ═══════════════════════════════════════════════════════════════
  # Production Record Mappings
  # ═══════════════════════════════════════════════════════════════
  production_record:
    source: portal.production_record
    target: erp.milk_production
    direction: portal_to_erp
    
    fields:
      - source: record_id
        target: production_entry_id
        type: string
      
      - source: animal.tag_number
        target: ear_tag_reference
        type: string
        lookup:
          table: erp.livestock_master
          key: portal_animal_id
          value: ear_tag_number
      
      - source: production_date
        target: milking_date
        type: date
      
      - source: session
        target: milking_session
        type: enum
        values:
          AM: "MORNING"
          PM: "EVENING"
      
      - source: quantity
        target: milk_volume_liters
        type: decimal
        precision: 8
        scale: 2
      
      - source: fat_content
        target: fat_percentage
        type: decimal
        precision: 4
        scale: 2
      
      - source: protein_content
        target: protein_percentage
        type: decimal
        precision: 4
        scale: 2
      
      - source: somatic_cell_count
        target: scc_count
        type: integer
```



## 6. Synchronization Engine

### 6.1 Real-time Sync Architecture

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────┐
│                         REAL-TIME SYNCHRONIZATION ARCHITECTURE                                  │
├─────────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                                   │
│  ┌─────────────────────────────────────────────────────────────────────────────────────────┐    │
│  │                           CHANGE DATA CAPTURE (CDC) LAYER                                │    │
│  │                                                                                          │    │
│  │   PostgreSQL ──► Debezium ──► Kafka Connect ──► Kafka Topics                             │    │
│  │                                                                                          │    │
│  │   Captured Events:                                                                        │    │
│  │   • INSERT - New records created                                                         │    │
│  │   • UPDATE - Existing records modified                                                   │    │
│  │   • DELETE - Records removed                                                             │    │
│  │                                                                                          │    │
│  │   Configuration:                                                                          │    │
│  │   ┌─────────────────────────────────────────────────────────────────────────────────┐   │    │
│  │   │ {                                                                                │   │    │
│  │   │   "connector.class": "io.debezium.connector.postgresql.PostgresConnector",      │   │    │
│  │   │   "database.hostname": "postgres.smartdairy.com",                                │   │    │
│  │   │   "database.port": "5432",                                                       │   │    │
│  │   │   "database.user": "debezium",                                                   │   │    │
│  │   │   "database.password": "${SECRET}",                                              │   │    │
│  │   │   "database.dbname": "smartdairy",                                               │   │    │
│  │   │   "database.server.name": "smartdairy-db",                                       │   │    │
│  │   │   "table.include.list": "public.orders,public.customers,public.animals",         │   │    │
│  │   │   "plugin.name": "pgoutput",                                                     │   │    │
│  │   │   "slot.name": "debezium_slot",                                                  │   │    │
│  │   │   "publication.name": "dbz_publication",                                         │   │    │
│  │   │   "transforms": "unwrap",                                                        │   │    │
│  │   │   "transforms.unwrap.type": "io.debezium.transforms.ExtractNewRecordState",      │   │    │
│  │   │   "transforms.unwrap.drop.tombstones": "false",                                  │   │    │
│  │   │   "transforms.unwrap.delete.handling.mode": "rewrite"                            │   │    │
│  │   │ }                                                                                │   │    │
│  │   └─────────────────────────────────────────────────────────────────────────────────┘   │    │
│  └─────────────────────────────────────────────────────────────────────────────────────────┘    │
│                                             │                                                     │
│                                             ▼                                                     │
│  ┌─────────────────────────────────────────────────────────────────────────────────────────┐    │
│  │                              KAFKA TOPICS LAYER                                          │    │
│  │                                                                                          │    │
│  │  Topic                              Partitions  Replication  Retention                   │    │
│  │  ─────────────────────────────────────────────────────────────────────────               │    │
│  │  smartdairy-db.public.orders         12          3            7 days                     │    │
│  │  smartdairy-db.public.customers      6           3            7 days                     │    │
│  │  smartdairy-db.public.animals        6           3            7 days                     │    │
│  │  smartdairy-db.public.products       6           3            7 days                     │    │
│  │  smartdairy-db.public.health_records 6           3            7 days                     │    │
│  │                                                                                          │    │
│  │  Event Structure:                                                                         │    │
│  │  {                                                                                        │    │
│  │    "schema": {...},                                                                       │    │
│  │    "payload": {                                                                           │    │
│  │      "before": {...},     // Previous values (for UPDATE/DELETE)                         │    │
│  │      "after": {...},      // New values (for INSERT/UPDATE)                              │    │
│  │      "source": {                                                                          │    │
│  │        "version": "2.1.0",                                                                │    │
│  │        "connector": "postgresql",                                                         │    │
│  │        "name": "smartdairy-db",                                                           │    │
│  │        "ts_ms": 1643723400000,                                                            │    │
│  │        "db": "smartdairy",                                                                │    │
│  │        "schema": "public",                                                                │    │
│  │        "table": "orders",                                                                 │    │
│  │        "txId": 12345,                                                                     │    │
│  │        "lsn": 123456789                                                                   │    │
│  │      },                                                                                   │    │
│  │      "op": "c",           // c=create, u=update, d=delete, r=read                        │    │
│  │      "ts_ms": 1643723400123                                                               │    │
│  │    }                                                                                      │    │
│  │  }                                                                                        │    │
│  └─────────────────────────────────────────────────────────────────────────────────────────┘    │
│                                             │                                                     │
│                                             ▼                                                     │
│  ┌─────────────────────────────────────────────────────────────────────────────────────────┐    │
│  │                         STREAM PROCESSING LAYER (Kafka Streams)                          │    │
│  │                                                                                          │    │
│  │  ┌─────────────────────────────────────────────────────────────────────────────────┐    │    │
│  │  │                           TOPOLOGY                                                │    │    │
│  │  │                                                                                  │    │    │
│  │  │  Source: CDC Topics ──► Branch by Operation Type                                  │    │    │
│  │  │                                                                                  │    │    │
│  │  │  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐                          │    │    │
│  │  │  │  INSERT     │    │  UPDATE     │    │  DELETE     │                          │    │    │
│  │  │  │  Stream     │    │  Stream     │    │  Stream     │                          │    │    │
│  │  │  └──────┬──────┘    └──────┬──────┘    └──────┬──────┘                          │    │    │
│  │  │         │                  │                  │                                  │    │    │
│  │  │         ▼                  ▼                  ▼                                  │    │    │
│  │  │  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐                          │    │    │
│  │  │  │  Transform  │    │  Diff       │    │  Tombstone  │                          │    │    │
│  │  │  │  & Enrich   │    │  Detection  │    │  Handler    │                          │    │    │
│  │  │  └──────┬──────┘    └──────┬──────┘    └──────┬──────┘                          │    │    │
│  │  │         │                  │                  │                                  │    │    │
│  │  │         └──────────────────┼──────────────────┘                                  │    │    │
│  │  │                            ▼                                                     │    │    │
│  │  │                   ┌─────────────┐                                                │    │    │
│  │  │                   │  Route by   │                                                │    │    │
│  │  │                   │  Entity Type│                                                │    │    │
│  │  │                   └──────┬──────┘                                                │    │    │
│  │  │                          │                                                       │    │    │
│  │  │    ┌─────────────────────┼─────────────────────┐                                │    │    │
│  │  │    ▼                     ▼                     ▼                                │    │    │
│  │  │ ┌──────┐            ┌──────┐            ┌──────┐                                │    │    │
│  │  │ │Order │            │Animal│            │Health│                                │    │    │
│  │  │ │Sink  │            │Sink  │            │Sink  │                                │    │    │
│  │  │ └──┬───┘            └──┬───┘            └──┬───┘                                │    │    │
│  │  │    │                   │                   │                                    │    │    │
│  │  └────┴───────────────────┴───────────────────┴────────────────────────────────────┘    │    │
│  │                                                                                          │    │
│  └─────────────────────────────────────────────────────────────────────────────────────────┘    │
│                                             │                                                     │
│                                             ▼                                                     │
│  ┌─────────────────────────────────────────────────────────────────────────────────────────┐    │
│  │                              SYNC WORKER LAYER                                           │    │
│  │                                                                                          │    │
│  │  ┌─────────────────────────────────────────────────────────────────────────────────┐    │    │
│  │  │                          CONSUMER GROUPS                                          │    │    │
│  │  │                                                                                  │    │    │
│  │  │  Group: erp-sync-workers                                                        │    │    │
│  │  │  ├─ Instance 1 (Partitions 0-3)                                                 │    │    │
│  │  │  ├─ Instance 2 (Partitions 4-7)                                                 │    │    │
│  │  │  ├─ Instance 3 (Partitions 8-11)                                                │    │    │
│  │  │                                                                                  │    │    │
│  │  │  Processing Strategy:                                                            │    │    │
│  │  │  • At-least-once delivery (configurable)                                        │    │    │
│  │  │  • Manual offset commit after successful sync                                   │    │    │
│  │  │  • Idempotent writes to ERP                                                     │    │    │
│  │  │  • Retry with exponential backoff                                               │    │    │
│  │  └─────────────────────────────────────────────────────────────────────────────────┘    │    │
│  │                                                                                          │    │
│  └─────────────────────────────────────────────────────────────────────────────────────────┘    │
│                                             │                                                     │
│                                             ▼                                                     │
│  ┌─────────────────────────────────────────────────────────────────────────────────────────┐    │
│  │                              TARGET ERP SYSTEM                                           │    │
│  │                                                                                          │    │
│  │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐                  │    │
│  │  │  Sales   │  │ Inventory│  │  Animal  │  │Production│  │  Health  │                  │    │
│  │  │  Module  │  │  Module  │  │  Module  │  │  Module  │  │  Module  │                  │    │
│  │  └──────────┘  └──────────┘  └──────────┘  └──────────┘  └──────────┘                  │    │
│  │                                                                                          │    │
│  └─────────────────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                                   │
└─────────────────────────────────────────────────────────────────────────────────────────────────┘
```

### 6.2 Batch Processing

```java
// BatchSyncProcessor.java
package com.smartdairy.sync.batch;

import com.smartdairy.sync.model.*;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.batch.core.*;
import org.springframework.batch.core.configuration.annotation.JobBuilderFactory;
import org.springframework.batch.core.configuration.annotation.StepBuilderFactory;
import org.springframework.batch.core.launch.JobLauncher;
import org.springframework.batch.item.ItemProcessor;
import org.springframework.batch.item.ItemReader;
import org.springframework.batch.item.ItemWriter;
import org.springframework.batch.item.database.JpaPagingItemReader;
import org.springframework.batch.item.database.builder.JpaPagingItemReaderBuilder;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.scheduling.annotation.Scheduled;

import javax.persistence.EntityManagerFactory;
import java.time.Instant;
import java.time.temporal.ChronoUnit;

@Slf4j
@Configuration
@RequiredArgsConstructor
public class BatchSyncProcessor {
    
    private final JobLauncher jobLauncher;
    private final JobBuilderFactory jobBuilderFactory;
    private final StepBuilderFactory stepBuilderFactory;
    private final EntityManagerFactory entityManagerFactory;
    private final OrderSyncProcessor orderSyncProcessor;
    private final ProductSyncProcessor productSyncProcessor;
    
    /**
     * Scheduled job: Sync pending orders every 5 minutes
     */
    @Scheduled(fixedRate = 300000)
    public void runOrderSyncJob() throws Exception {
        JobParameters params = new JobParametersBuilder()
            .addString("JobID", String.valueOf(System.currentTimeMillis()))
            .addString("syncType", "orders")
            .toJobParameters();
        
        jobLauncher.run(orderSyncJob(), params);
    }
    
    /**
     * Scheduled job: Full product sync every hour
     */
    @Scheduled(cron = "0 0 * * * *")
    public void runProductSyncJob() throws Exception {
        JobParameters params = new JobParametersBuilder()
            .addString("JobID", String.valueOf(System.currentTimeMillis()))
            .addString("syncType", "products")
            .toJobParameters();
        
        jobLauncher.run(productSyncJob(), params);
    }
    
    @Bean
    public Job orderSyncJob() {
        return jobBuilderFactory.get("orderSyncJob")
            .incrementer(new RunIdIncrementer())
            .start(orderSyncStep())
            .build();
    }
    
    @Bean
    public Step orderSyncStep() {
        return stepBuilderFactory.get("orderSyncStep")
            .<B2BOrder, SyncResult>chunk(100)
            .reader(orderReader())
            .processor(orderProcessor())
            .writer(orderWriter())
            .faultTolerant()
            .skipLimit(10)
            .skip(SyncException.class)
            .retryLimit(3)
            .retry(TransientDataAccessException.class)
            .listener(new StepExecutionListener() {
                @Override
                public void beforeStep(StepExecution stepExecution) {
                    log.info("Starting order sync batch job");
                }
                
                @Override
                public ExitStatus afterStep(StepExecution stepExecution) {
                    log.info("Order sync completed. Read: {}, Written: {}, Skipped: {}",
                        stepExecution.getReadCount(),
                        stepExecution.getWriteCount(),
                        stepExecution.getSkipCount());
                    return stepExecution.getExitStatus();
                }
            })
            .build();
    }
    
    @Bean
    public ItemReader<B2BOrder> orderReader() {
        Instant since = Instant.now().minus(24, ChronoUnit.HOURS);
        
        return new JpaPagingItemReaderBuilder<B2BOrder>()
            .name("orderReader")
            .entityManagerFactory(entityManagerFactory)
            .queryString("SELECT o FROM B2BOrder o WHERE o.status = 'PENDING' OR " +
                        "(o.status = 'SYNC_FAILED' AND o.retryCount < 5) " +
                        "AND o.createdAt >= :since")
            .parameterValues(Map.of("since", since))
            .pageSize(100)
            .build();
    }
    
    @Bean
    public ItemProcessor<B2BOrder, SyncResult> orderProcessor() {
        return order -> {
            try {
                return orderSyncProcessor.process(order);
            } catch (Exception e) {
                log.error("Failed to process order {}: {}", order.getOrderId(), e.getMessage());
                throw new SyncException("Order processing failed", e);
            }
        };
    }
    
    @Bean
    public ItemWriter<SyncResult> orderWriter() {
        return results -> {
            for (SyncResult result : results) {
                if (result.isSuccess()) {
                    log.debug("Successfully synced order: {}", result.getEntityId());
                } else {
                    log.error("Failed to sync order: {} - {}", 
                        result.getEntityId(), result.getErrorMessage());
                }
            }
        };
    }
}
```

## 7. Data Transformation

### 7.1 Field Mapping Engine

```java
// FieldMapper.java
package com.smartdairy.transform.engine;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import com.fasterxml.jackson.databind.node.ObjectNode;
import com.smartdairy.transform.config.MappingConfig;
import com.smartdairy.transform.config.MappingRule;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.expression.spel.standard.SpelExpressionParser;
import org.springframework.expression.spel.support.StandardEvaluationContext;
import org.springframework.stereotype.Component;

import java.math.BigDecimal;
import java.math.RoundingMode;
import java.text.SimpleDateFormat;
import java.time.Instant;
import java.time.LocalDate;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.*;

@Slf4j
@Component
@RequiredArgsConstructor
public class FieldMapper {
    
    private final ObjectMapper objectMapper;
    private final SpelExpressionParser expressionParser;
    private final LookupService lookupService;
    private final ValidationService validationService;
    
    /**
     * Transform source object to target format based on mapping configuration
     */
    public JsonNode transform(JsonNode source, MappingConfig config) {
        ObjectNode target = objectMapper.createObjectNode();
        
        for (MappingRule rule : config.getRules()) {
            try {
                JsonNode value = applyRule(source, rule);
                if (value != null && !value.isNull()) {
                    target.set(rule.getTargetField(), value);
                } else if (rule.isRequired()) {
                    throw new MappingException("Required field " + rule.getTargetField() + " is null");
                } else if (rule.getDefaultValue() != null) {
                    target.put(rule.getTargetField(), rule.getDefaultValue());
                }
            } catch (Exception e) {
                if (rule.isRequired()) {
                    throw new MappingException("Failed to map required field " + rule.getTargetField(), e);
                }
                log.warn("Failed to map field {}: {}", rule.getTargetField(), e.getMessage());
            }
        }
        
        return target;
    }
    
    private JsonNode applyRule(JsonNode source, MappingRule rule) {
        // Get source value
        JsonNode value = getSourceValue(source, rule.getSourceField());
        
        // Apply transform if specified
        if (rule.getTransform() != null) {
            value = applyTransform(value, rule.getTransform());
        }
        
        // Apply lookup if specified
        if (rule.getLookup() != null) {
            value = applyLookup(value, rule.getLookup());
        }
        
        // Apply validation if specified
        if (rule.getValidation() != null) {
            validateValue(value, rule.getValidation());
        }
        
        // Apply format conversion
        if (rule.getTargetType() != null) {
            value = convertType(value, rule.getTargetType(), rule.getFormat());
        }
        
        return value;
    }
    
    private JsonNode getSourceValue(JsonNode source, String path) {
        if (path == null || path.isEmpty()) {
            return null;
        }
        
        // Support nested paths (e.g., "customer.address.city")
        String[] parts = path.split("\\.");
        JsonNode current = source;
        
        for (String part : parts) {
            if (current == null || current.isNull()) {
                return null;
            }
            current = current.get(part);
        }
        
        return current;
    }
    
    private JsonNode applyTransform(JsonNode value, String transform) {
        if (value == null || value.isNull()) {
            return value;
        }
        
        String stringValue = value.asText();
        
        switch (transform.toLowerCase()) {
            case "uppercase":
                return objectMapper.valueToTree(stringValue.toUpperCase());
            case "lowercase":
                return objectMapper.valueToTree(stringValue.toLowerCase());
            case "titlecase":
                return objectMapper.valueToTree(toTitleCase(stringValue));
            case "trim":
                return objectMapper.valueToTree(stringValue.trim());
            case "currency:usd":
                return objectMapper.valueToTree(convertToUSD(value.decimalValue()));
            case "currency:eur":
                return objectMapper.valueToTree(convertToEUR(value.decimalValue()));
            case "round:2":
                return objectMapper.valueToTree(value.decimalValue().setScale(2, RoundingMode.HALF_UP));
            case "date:iso":
                return objectMapper.valueToTree(formatDateISO(value.asText()));
            case "date:epoch":
                return objectMapper.valueToTree(parseDateToEpoch(value.asText()));
            default:
                log.warn("Unknown transform: {}", transform);
                return value;
        }
    }
    
    private JsonNode applyLookup(JsonNode value, MappingRule.LookupConfig lookup) {
        if (value == null || value.isNull()) {
            return value;
        }
        
        String lookupValue = lookupService.lookup(
            lookup.getTable(),
            lookup.getSourceColumn(),
            value.asText(),
            lookup.getTargetColumn()
        );
        
        return objectMapper.valueToTree(lookupValue);
    }
    
    private void validateValue(JsonNode value, MappingRule.ValidationConfig validation) {
        if (value == null || value.isNull()) {
            if (validation.isRequired()) {
                throw new ValidationException("Required field is null");
            }
            return;
        }
        
        // Pattern validation
        if (validation.getPattern() != null) {
            String stringValue = value.asText();
            if (!stringValue.matches(validation.getPattern())) {
                throw new ValidationException("Value does not match pattern: " + validation.getPattern());
            }
        }
        
        // Range validation for numbers
        if (value.isNumber()) {
            BigDecimal decimalValue = value.decimalValue();
            if (validation.getMin() != null && decimalValue.compareTo(validation.getMin()) < 0) {
                throw new ValidationException("Value below minimum: " + validation.getMin());
            }
            if (validation.getMax() != null && decimalValue.compareTo(validation.getMax()) > 0) {
                throw new ValidationException("Value above maximum: " + validation.getMax());
            }
        }
        
        // Enum validation
        if (validation.getAllowedValues() != null && !validation.getAllowedValues().isEmpty()) {
            String stringValue = value.asText();
            if (!validation.getAllowedValues().contains(stringValue)) {
                throw new ValidationException("Value not in allowed set: " + validation.getAllowedValues());
            }
        }
    }
    
    private JsonNode convertType(JsonNode value, String targetType, String format) {
        if (value == null || value.isNull()) {
            return value;
        }
        
        try {
            switch (targetType.toLowerCase()) {
                case "string":
                    return objectMapper.valueToTree(value.asText());
                case "integer":
                    return objectMapper.valueToTree(value.asInt());
                case "long":
                    return objectMapper.valueToTree(value.asLong());
                case "decimal":
                    return objectMapper.valueToTree(value.decimalValue());
                case "boolean":
                    return objectMapper.valueToTree(value.asBoolean());
                case "date":
                    return objectMapper.valueToTree(parseDate(value.asText(), format));
                case "datetime":
                    return objectMapper.valueToTree(parseDateTime(value.asText(), format));
                default:
                    return value;
            }
        } catch (Exception e) {
            throw new MappingException("Type conversion failed for type " + targetType, e);
        }
    }
    
    private String toTitleCase(String input) {
        String[] words = input.toLowerCase().split("\\s+");
        StringBuilder result = new StringBuilder();
        for (String word : words) {
            if (!word.isEmpty()) {
                result.append(Character.toUpperCase(word.charAt(0)))
                      .append(word.substring(1))
                      .append(" ");
            }
        }
        return result.toString().trim();
    }
    
    private BigDecimal convertToUSD(BigDecimal amount) {
        // Apply exchange rate conversion
        return amount.multiply(new BigDecimal("1.0")); // Simplified
    }
    
    private BigDecimal convertToEUR(BigDecimal amount) {
        return amount.multiply(new BigDecimal("0.85")); // Simplified
    }
    
    private String formatDateISO(String dateStr) {
        // Parse and format to ISO date
        return dateStr; // Simplified
    }
    
    private long parseDateToEpoch(String dateStr) {
        LocalDate date = LocalDate.parse(dateStr);
        return date.atStartOfDay().toEpochSecond(java.time.ZoneOffset.UTC);
    }
    
    private String parseDate(String dateStr, String format) {
        if (format == null) {
            format = "yyyy-MM-dd";
        }
        LocalDate date = LocalDate.parse(dateStr, DateTimeFormatter.ofPattern(format));
        return date.toString();
    }
    
    private String parseDateTime(String dateTimeStr, String format) {
        if (format == null) {
            format = "yyyy-MM-dd HH:mm:ss";
        }
        LocalDateTime dateTime = LocalDateTime.parse(dateTimeStr, DateTimeFormatter.ofPattern(format));
        return dateTime.toString();
    }
}
```

## 8. Error Handling

### 8.1 Error Handling Strategy

```java
// GlobalExceptionHandler.java
package com.smartdairy.integration.error;

import lombok.extern.slf4j.Slf4j;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.ExceptionHandler;
import org.springframework.web.bind.annotation.RestControllerAdvice;
import org.springframework.web.reactive.function.client.WebClientResponseException;

import java.time.Instant;
import java.util.UUID;

@Slf4j
@RestControllerAdvice
public class GlobalExceptionHandler {
    
    @ExceptionHandler(IntegrationException.class)
    public ResponseEntity<ErrorResponse> handleIntegrationException(IntegrationException ex) {
        String errorId = generateErrorId();
        log.error("Integration error [{}]: {}", errorId, ex.getMessage(), ex);
        
        ErrorResponse response = ErrorResponse.builder()
            .errorId(errorId)
            .errorCode(ex.getErrorCode())
            .message(ex.getMessage())
            .timestamp(Instant.now())
            .retryable(ex.isRetryable())
            .build();
        
        return ResponseEntity.status(ex.getHttpStatus()).body(response);
    }
    
    @ExceptionHandler(ValidationException.class)
    public ResponseEntity<ErrorResponse> handleValidationException(ValidationException ex) {
        String errorId = generateErrorId();
        log.warn("Validation error [{}]: {}", errorId, ex.getMessage());
        
        ErrorResponse response = ErrorResponse.builder()
            .errorId(errorId)
            .errorCode("VALIDATION_ERROR")
            .message(ex.getMessage())
            .timestamp(Instant.now())
            .retryable(false)
            .build();
        
        return ResponseEntity.status(HttpStatus.BAD_REQUEST).body(response);
    }
    
    @ExceptionHandler(WebClientResponseException.class)
    public ResponseEntity<ErrorResponse> handleWebClientException(WebClientResponseException ex) {
        String errorId = generateErrorId();
        log.error("ERP communication error [{}]: Status {}, Body: {}", 
            errorId, ex.getStatusCode(), ex.getResponseBodyAsString(), ex);
        
        boolean retryable = ex.getStatusCode().is5xxServerError() || 
                           ex.getStatusCode().value() == 429;
        
        ErrorResponse response = ErrorResponse.builder()
            .errorId(errorId)
            .errorCode("ERP_COMMUNICATION_ERROR")
            .message("Failed to communicate with ERP system")
            .details(ex.getResponseBodyAsString())
            .timestamp(Instant.now())
            .retryable(retryable)
            .build();
        
        return ResponseEntity.status(ex.getStatusCode()).body(response);
    }
    
    @ExceptionHandler(Exception.class)
    public ResponseEntity<ErrorResponse> handleGenericException(Exception ex) {
        String errorId = generateErrorId();
        log.error("Unexpected error [{}]: {}", errorId, ex.getMessage(), ex);
        
        ErrorResponse response = ErrorResponse.builder()
            .errorId(errorId)
            .errorCode("INTERNAL_ERROR")
            .message("An unexpected error occurred")
            .timestamp(Instant.now())
            .retryable(true)
            .build();
        
        return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).body(response);
    }
    
    private String generateErrorId() {
        return "ERR-" + UUID.randomUUID().toString().substring(0, 8).toUpperCase();
    }
}
```

## 9. Testing & Validation

### 9.1 Test Strategy

```yaml
# test-strategy.yml

test_levels:
  unit_tests:
    scope: "Individual components"
    coverage_target: 80%
    tools:
      - JUnit 5
      - Mockito
      - AssertJ
    run_on: every_commit
  
  integration_tests:
    scope: "Component interactions"
    coverage_target: 70%
    tools:
      - Spring Boot Test
      - TestContainers
      - WireMock
    run_on: pull_request
  
  contract_tests:
    scope: "API contracts"
    tools:
      - Pact
      - Spring Cloud Contract
    run_on: pull_request
  
  e2e_tests:
    scope: "End-to-end flows"
    tools:
      - Cucumber
      - Selenium
      - REST Assured
    run_on: nightly
  
  performance_tests:
    scope: "Load and stress"
    tools:
      - JMeter
      - Gatling
      - K6
    run_on: pre_release

test_environments:
  - name: local
    description: "Developer local environment"
    data: synthetic
  
  - name: ci
    description: "CI/CD pipeline"
    data: synthetic
  
  - name: staging
    description: "Pre-production"
    data: anonymized_production
  
  - name: production
    description: "Production smoke tests"
    data: production_read_only
```

### 9.2 Integration Test Example

```java
// B2BOrderIntegrationTest.java
@SpringBootTest
@AutoConfigureMockMvc
@Testcontainers
public class B2BOrderIntegrationTest {
    
    @Container
    static PostgreSQLContainer<?> postgres = new PostgreSQLContainer<>("postgres:14")
        .withDatabaseName("testdb")
        .withUsername("test")
        .withPassword("test");
    
    @Container
    static GenericContainer<?> redis = new GenericContainer<>("redis:7")
        .withExposedPorts(6379);
    
    @Autowired
    private MockMvc mockMvc;
    
    @Autowired
    private ObjectMapper objectMapper;
    
    @MockBean
    private ErpClient erpClient;
    
    @Test
    void shouldCreateOrderAndSyncToErp() throws Exception {
        // Given
        CreateOrderRequest request = CreateOrderRequest.builder()
            .customerId("CUST-001")
            .customerCode("100001")
            .lines(List.of(
                OrderLineRequest.builder()
                    .productCode("MILK-001")
                    .quantity(new BigDecimal("100"))
                    .unitPrice(new BigDecimal("2.50"))
                    .build()
            ))
            .requestedDeliveryDate(LocalDate.now().plusDays(7))
            .build();
        
        when(erpClient.createSalesOrder(any()))
            .thenReturn(Mono.just(ErpSalesOrderResponse.builder()
                .orderNumber("SO-12345")
                .erpOrderId("ERP-12345")
                .build()));
        
        // When
        MvcResult result = mockMvc.perform(post("/api/b2b/orders")
                .contentType(MediaType.APPLICATION_JSON)
                .header("X-API-Key", "test-api-key")
                .content(objectMapper.writeValueAsString(request)))
            .andExpect(status().isCreated())
            .andExpect(jsonPath("$.orderId").exists())
            .andExpect(jsonPath("$.status").value("SYNCED_TO_ERP"))
            .andReturn();
        
        // Then
        B2BOrderResponse response = objectMapper.readValue(
            result.getResponse().getContentAsString(),
            B2BOrderResponse.class
        );
        
        assertThat(response.getErpOrderNumber()).isEqualTo("SO-12345");
        
        verify(erpClient, times(1)).createSalesOrder(any());
    }
    
    @Test
    void shouldRetryOnErpFailure() throws Exception {
        // Given
        CreateOrderRequest request = CreateOrderRequest.builder()
            .customerId("CUST-001")
            .customerCode("100001")
            .lines(List.of(
                OrderLineRequest.builder()
                    .productCode("MILK-001")
                    .quantity(new BigDecimal("100"))
                    .unitPrice(new BigDecimal("2.50"))
                    .build()
            ))
            .build();
        
        when(erpClient.createSalesOrder(any()))
            .thenReturn(Mono.error(new RuntimeException("ERP unavailable")));
        
        // When
        mockMvc.perform(post("/api/b2b/orders")
                .contentType(MediaType.APPLICATION_JSON)
                .header("X-API-Key", "test-api-key")
                .content(objectMapper.writeValueAsString(request)))
            .andExpect(status().isAccepted()) // Accepted but sync pending
            .andExpect(jsonPath("$.status").value("PENDING"));
        
        // Then - verify retry is scheduled
        await().atMost(Duration.ofSeconds(5))
            .untilAsserted(() -> {
                verify(erpClient, atLeast(1)).createSalesOrder(any());
            });
    }
}
```

## 10. Monitoring & Observability

### 10.1 Metrics

```yaml
# metrics-configuration.yml

metrics:
  categories:
    infrastructure:
      - name: system_cpu_usage
        type: gauge
        description: "Current CPU usage percentage"
      
      - name: jvm_memory_used_bytes
        type: gauge
        labels: [area, id]
        description: "JVM memory usage"
      
      - name: http_server_requests_seconds
        type: histogram
        labels: [uri, method, status]
        buckets: [0.1, 0.25, 0.5, 1.0, 2.5, 5.0, 10.0]
        description: "HTTP request duration"
    
    business:
      - name: orders_created_total
        type: counter
        labels: [portal_type, customer_type]
        description: "Total orders created"
      
      - name: orders_sync_duration_seconds
        type: histogram
        labels: [portal_type, status]
        description: "Order sync duration"
      
      - name: sync_failures_total
        type: counter
        labels: [entity_type, error_category]
        description: "Total sync failures"
      
      - name: active_sync_jobs
        type: gauge
        description: "Number of active sync jobs"
    
    integration:
      - name: erp_requests_total
        type: counter
        labels: [operation, status]
        description: "Total ERP requests"
      
      - name: erp_request_duration_seconds
        type: histogram
        labels: [operation]
        description: "ERP request duration"
      
      - name: circuit_breaker_state
        type: gauge
        labels: [name, state]
        description: "Circuit breaker state (0=closed, 1=open, 2=half_open)"

alerts:
  - name: HighErrorRate
    condition: rate(http_server_requests_seconds_count{status=~"5.."}[5m]) > 0.1
    severity: critical
    channel: pagerduty
  
  - name: SyncLag
    condition: time() - sync_last_success_timestamp > 300
    severity: warning
    channel: slack
  
  - name: CircuitBreakerOpen
    condition: circuit_breaker_state == 1
    severity: warning
    channel: slack
```

### 10.2 Dashboard Configuration

```json
{
  "dashboard": {
    "title": "ERP Integration Layer",
    "panels": [
      {
        "title": "Request Rate",
        "type": "graph",
        "targets": [
          {
            "expr": "rate(http_server_requests_seconds_count[5m])",
            "legendFormat": "{{uri}}"
          }
        ]
      },
      {
        "title": "Error Rate",
        "type": "graph",
        "targets": [
          {
            "expr": "rate(http_server_requests_seconds_count{status=~\"5..\"}[5m])",
            "legendFormat": "Errors"
          }
        ]
      },
      {
        "title": "Sync Status",
        "type": "stat",
        "targets": [
          {
            "expr": "active_sync_jobs",
            "legendFormat": "Active Jobs"
          }
        ]
      },
      {
        "title": "ERP Response Time",
        "type": "heatmap",
        "targets": [
          {
            "expr": "rate(erp_request_duration_seconds_bucket[5m])",
            "legendFormat": "{{le}}"
          }
        ]
      }
    ]
  }
}
```

## 11. Appendices

### Appendix A: Configuration Examples

```yaml
# application-integration.yml - Full Configuration Example

spring:
  application:
    name: smart-dairy-integration
  
  datasource:
    url: jdbc:postgresql://localhost:5432/smartdairy
    username: ${DB_USERNAME}
    password: ${DB_PASSWORD}
    hikari:
      maximum-pool-size: 20
      minimum-idle: 5
  
  kafka:
    bootstrap-servers: localhost:9092
    producer:
      key-serializer: org.apache.kafka.common.serialization.StringSerializer
      value-serializer: org.springframework.kafka.support.serializer.JsonSerializer
      acks: all
      retries: 3
    consumer:
      group-id: erp-sync-group
      auto-offset-reset: earliest
      enable-auto-commit: false
  
  redis:
    host: localhost
    port: 6379
    password: ${REDIS_PASSWORD}
    lettuce:
      pool:
        max-active: 50
        max-idle: 20

erp:
  base-url: https://erp.smartdairy.com/api/v1
  auth:
    type: oauth2
    client-id: ${ERP_CLIENT_ID}
    client-secret: ${ERP_CLIENT_SECRET}
    token-url: https://erp.smartdairy.com/oauth/token
  timeout:
    connect: 5000
    read: 30000
  retry:
    max-attempts: 3
    backoff-ms: 1000

sync:
  batch-size: 100
  max-concurrent-jobs: 10
  retry-policy:
    max-retries: 5
    backoff-multiplier: 2
    max-backoff-ms: 60000
  
logging:
  level:
    com.smartdairy.integration: DEBUG
    org.springframework.web: INFO
  pattern:
    console: "%d{yyyy-MM-dd HH:mm:ss.SSS} [%thread] [%X{traceId:-},%X{spanId:-}] %-5level %logger{36} - %msg%n"
```

### Appendix B: API Reference

```yaml
openapi: 3.0.0
info:
  title: Smart Dairy Integration API
  version: 1.0.0

paths:
  /api/v1/sync/b2b/orders:
    post:
      summary: Create B2B Order
      requestBody:
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/CreateOrderRequest'
      responses:
        201:
          description: Order created
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/OrderResponse'
  
  /api/v1/sync/farm/animals:
    post:
      summary: Register Animal
      requestBody:
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/RegisterAnimalRequest'
      responses:
        201:
          description: Animal registered

components:
  schemas:
    CreateOrderRequest:
      type: object
      required:
        - customerId
        - lines
      properties:
        customerId:
          type: string
        lines:
          type: array
          items:
            $ref: '#/components/schemas/OrderLine'
    
    OrderLine:
      type: object
      properties:
        productCode:
          type: string
        quantity:
          type: number
        unitPrice:
          type: number
```

### Appendix C: Troubleshooting Guide

| Issue | Symptoms | Possible Causes | Resolution |
|-------|----------|-----------------|------------|
| Orders not syncing | Orders stuck in PENDING status | ERP unavailable, Auth failure | Check ERP health, Verify credentials |
| High error rate | 5xx errors increasing | Circuit breaker open, Database issues | Check circuit breaker status, Review DB metrics |
| Slow response times | P95 latency > 500ms | High load, Cache misses | Scale horizontally, Review cache hit rates |
| Sync conflicts | Conflict resolution alerts | Concurrent updates | Review conflict resolution rules, Check for sync loops |
| Memory issues | OOM errors, GC pressure | Memory leaks, Large payloads | Review heap dumps, Implement pagination |

### Appendix D: Glossary

| Term | Definition |
|------|------------|
| CDC | Change Data Capture - Technique for tracking database changes |
| DLQ | Dead Letter Queue - Storage for failed messages |
| ERP | Enterprise Resource Planning - Core business system |
| ETL | Extract, Transform, Load - Data processing pattern |
| Idempotency | Property allowing multiple executions with same result |
| Saga | Distributed transaction pattern |
| Webhook | HTTP callback for event notifications |

---

## Document Approval

| Role | Name | Date | Signature |
|------|------|------|-----------|
| Tech Lead | | | |
| Solution Architect | | | |
| QA Lead | | | |
| Product Owner | | | |

---

**Document Version:** 1.0  
**Last Updated:** 2026-02-02  
**Classification:** Internal Use Only

---

*End of Document - Milestone 36: ERP Integration Layer*
