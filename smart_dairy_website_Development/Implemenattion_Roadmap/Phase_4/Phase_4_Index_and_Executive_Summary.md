# Phase 4 - Smart Portal + ERP System Implementation

## Smart Dairy Digital Transformation Initiative

### Comprehensive Implementation Guide & Executive Documentation

**Document Version:** 4.0.0  
**Last Updated:** February 2026  
**Classification:** Internal - Implementation Documentation  
**Project Phase:** Phase 4 (Days 301-400)  
**Document Owner:** Smart Dairy IT Directorate  

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Phase 4 Architecture Overview](#2-phase-4-architecture-overview)
3. [Milestone Summary Table](#3-milestone-summary-table)
4. [Team Structure & Developer Allocation](#4-team-structure--developer-allocation)
5. [Technology Stack Deep Dive](#5-technology-stack-deep-dive)
6. [Integration Architecture](#6-integration-architecture)
7. [Risk Management Framework](#7-risk-management-framework)
8. [Quality Assurance Framework](#8-quality-assurance-framework)
9. [Appendices](#9-appendices)

---

## 1. Executive Summary

### 1.1 Phase 4 Vision and Strategic Objectives

Phase 4 of the Smart Dairy Digital Transformation Initiative represents the **culmination of 300 days of foundational development** and the **beginning of enterprise-grade operations**. This phase delivers the complete Smart Portal + ERP System, transforming Smart Dairy from a digitally-enabled dairy company into a fully integrated, data-driven smart enterprise.

#### 1.1.1 Vision Statement

> *"By Day 400, Smart Dairy will operate on a unified digital platform that seamlessly connects our B2B customers, farm operations, administrative functions, and financial management into a single, intelligent ecosystem capable of real-time decision making and predictive analytics."*

#### 1.1.2 Strategic Objectives

| Objective ID | Strategic Objective | Target Metric | Success Criteria |
|--------------|---------------------|---------------|------------------|
| P4-OBJ-001 | Complete B2B Marketplace Ecosystem | 100% Feature Completion | All B2B features operational and tested |
| P4-OBJ-002 | Implement Smart Farm Management | 95% IoT Coverage | All critical farm assets monitored |
| P4-OBJ-003 | Deploy Comprehensive Admin Portal | 100% RBAC Implementation | Complete role-based access control |
| P4-OBJ-004 | Launch Integrated ERP System | 100% Module Integration | All ERP modules interconnected |
| P4-OBJ-005 | Achieve Production Deployment | Zero Critical Defects | System live with <5 minor issues |
| P4-OBJ-006 | Complete Knowledge Transfer | 100% Staff Training | All departments trained and certified |

#### 1.1.3 Business Value Proposition

**Quantifiable Benefits (Projected Year 1):**

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SMART DAIRY PHASE 4 ROI PROJECTION                        │
├─────────────────────────────────────────────────────────────────────────────┤
│  Revenue Enhancement                                                         │
│  ├── B2B Marketplace Commission:      ৳12,000,000                           │
│  ├── Subscription Services:           ৳8,500,000                            │
│  └── Premium Features:                ৳4,200,000                            │
│  SUBTOTAL:                            ৳24,700,000                           │
├─────────────────────────────────────────────────────────────────────────────┤
│  Operational Cost Reduction                                                  │
│  ├── Automated Farm Operations:       ৳6,800,000                            │
│  ├── Reduced Manual Data Entry:       ৳3,200,000                            │
│  ├── Inventory Optimization:          ৳4,500,000                            │
│  └── Administrative Efficiency:       ৳2,100,000                            │
│  SUBTOTAL:                            ৳16,600,000                           │
├─────────────────────────────────────────────────────────────────────────────┤
│  TOTAL PROJECTED VALUE:               ৳41,300,000                           │
│  PHASE 4 INVESTMENT:                  ৳15,200,000                           │
│  NET ROI:                             171.7%                                │
│  PAYBACK PERIOD:                      4.4 months                            │
└─────────────────────────────────────────────────────────────────────────────┘
```

**Strategic Capabilities Delivered:**

1. **Unified Customer Experience**: Single platform serving B2C, B2B, and internal stakeholders
2. **Real-time Operational Intelligence**: IoT-enabled farm monitoring with predictive capabilities
3. **Financial Transparency**: Complete audit trail and compliance-ready reporting
4. **Scalable Architecture**: Cloud-native infrastructure supporting 10x growth
5. **Competitive Differentiation**: Bangladesh's first fully-integrated smart dairy platform

### 1.2 Phase 4 Scope Definition

#### 1.2.1 In-Scope Deliverables

**B2B Marketplace Completion (Milestones 31-33):**

| Component | Description | Business Value |
|-----------|-------------|----------------|
| Advanced Wholesale Ordering | Tier-based pricing, volume discounts, contract management | 40% increase in B2B order value |
| Credit Management System | Credit limits, approval workflows, payment terms | 60% reduction in credit risk |
| B2B Mobile Application | Native Android/iOS apps for wholesale customers | 85% improvement in order frequency |
| Vendor Management Portal | Supplier onboarding, performance tracking, EDI integration | 30% procurement efficiency gain |
| B2B Analytics Dashboard | Purchase analytics, trend analysis, forecasting | Data-driven decision making |

**Smart Farm Management (Milestones 33-34):**

| Component | Description | Business Value |
|-----------|-------------|----------------|
| Herd Management System | Complete livestock lifecycle tracking | 25% improvement in herd productivity |
| IoT Sensor Integration | Environmental sensors, wearables, milk meters | Real-time farm visibility |
| Health Monitoring | Automated health alerts, veterinary workflows | 50% reduction in livestock mortality |
| Production Tracking | Automated milk collection, quality testing | 15% yield improvement |
| Breeding Management | Estrus detection, AI scheduling, genealogy | Optimized breeding programs |

**Admin Portal (Milestones 35-36):**

| Component | Description | Business Value |
|-----------|-------------|----------------|
| User Management Console | Complete CRUD operations, role assignment | Centralized identity management |
| RBAC System | Granular permissions, audit logging | Enhanced security posture |
| System Configuration | Feature flags, business rules, workflows | Agile business operations |
| Monitoring Dashboard | System health, performance metrics, alerts | 99.9% uptime achievement |
| Content Management | CMS for website, notifications, marketing | Self-service content updates |

**Integrated ERP System (Milestones 36-38):**

| Module | Description | Localization |
|--------|-------------|--------------|
| Accounting & Finance | GL, AP, AR, reconciliation, reporting | Bangladesh Tax (VAT), NBR compliance |
| Inventory Management | Multi-warehouse, lot tracking, expiry management | FIFO/LIFO support, batch tracking |
| Manufacturing (MRP) | Production planning, BOM management, quality | Dairy-specific processes |
| HR & Payroll | Employee management, attendance, payroll | Bangladesh Labor Law compliance |
| Procurement | Purchase orders, vendor management, RFQ | Local vendor integration |
| Sales Management | Order management, CRM, commissions | BDT currency, local invoicing |

**System Integration & Deployment (Milestones 39-40):**

| Activity | Description | Deliverable |
|----------|-------------|-------------|
| Integration Testing | End-to-end system validation | Test reports, defect logs |
| Performance Testing | Load testing, stress testing | Performance benchmarks |
| Security Audit | Penetration testing, vulnerability assessment | Security certification |
| Production Deployment | Live system cutover | Production environment |
| Training Program | User training, documentation, certification | Trained personnel |

#### 1.2.2 Out-of-Scope Items

The following items are explicitly excluded from Phase 4 scope:

| Item | Reason | Future Phase |
|------|--------|--------------|
| International expansion | Focus on Bangladesh market | Phase 5 |
| Blockchain integration | Emerging technology, needs maturation | Phase 5 |
| AI/ML predictive models | Data collection phase required | Phase 5 |
| Drone-based monitoring | Hardware complexity | Phase 6 |
| Customer mobile wallet | Requires financial license | Phase 5 |
| Third-party logistics integration | Partner negotiations ongoing | Phase 5 |

### 1.3 Success Metrics and KPIs

#### 1.3.1 Technical KPIs

```python
# Technical Success Criteria Definition
TECHNICAL_KPIS = {
    "system_availability": {
        "target": "99.9%",
        "measurement": "uptime_percentage",
        "calculation": "(total_minutes - downtime_minutes) / total_minutes * 100"
    },
    "api_response_time": {
        "target": "< 200ms (p95)",
        "measurement": "response_time_percentile",
        "threshold": "500ms maximum"
    },
    "database_performance": {
        "target": "< 100ms query time",
        "measurement": "average_query_duration",
        "optimization": "index_coverage > 95%"
    },
    "code_coverage": {
        "target": "> 85%",
        "measurement": "automated_test_coverage",
        "components": ["unit", "integration", "e2e"]
    },
    "security_score": {
        "target": "A+ rating",
        "measurement": "vulnerability_scan",
        "tools": ["OWASP ZAP", "SonarQube", "Snyk"]
    }
}
```

#### 1.3.2 Business KPIs

| KPI Category | Metric | Baseline | Target | Measurement Frequency |
|--------------|--------|----------|--------|----------------------|
| **User Engagement** | Daily Active Users (DAU) | 2,500 | 8,000 | Daily |
| | Monthly Active Users (MAU) | 15,000 | 45,000 | Monthly |
| | Session Duration | 4.5 min | 8.0 min | Daily |
| **B2B Commerce** | B2B Order Value | ৳450K/day | ৳1.2M/day | Daily |
| | Average Order Size | ৳12,000 | ৳25,000 | Weekly |
| | Order Processing Time | 48 hours | 4 hours | Per order |
| **Farm Operations** | Data Collection Rate | 60% | 98% | Real-time |
| | Alert Response Time | 30 min | 5 min | Per alert |
| | Herd Health Incidents | 45/month | 15/month | Monthly |
| **ERP Operations** | Invoice Generation Time | 2 days | 2 hours | Per invoice |
| | Inventory Accuracy | 92% | 99.5% | Weekly |
| | Payroll Processing Time | 5 days | 1 day | Monthly |

#### 1.3.3 Quality Metrics

```
┌────────────────────────────────────────────────────────────────────────────┐
│                     QUALITY GATES AND THRESHOLDS                           │
├────────────────────────────────────────────────────────────────────────────┤
│  Code Quality                                                               │
│  ├── Cyclomatic Complexity:        ≤ 10 per function                       │
│  ├── Code Duplication:             ≤ 3%                                    │
│  ├── Technical Debt Ratio:         ≤ 5%                                    │
│  └── Maintainability Index:        ≥ 85                                    │
├────────────────────────────────────────────────────────────────────────────┤
│  Testing Quality                                                            │
│  ├── Unit Test Coverage:           ≥ 85%                                   │
│  ├── Integration Test Coverage:    ≥ 70%                                   │
│  ├── E2E Test Pass Rate:           ≥ 95%                                   │
│  └── Defect Density:               ≤ 0.5 per 1000 LOC                      │
├────────────────────────────────────────────────────────────────────────────┤
│  Performance Standards                                                      │
│  ├── Page Load Time:               ≤ 3 seconds                             │
│  ├── API Response Time (p99):      ≤ 500ms                                 │
│  ├── Database Query Time:          ≤ 100ms                                 │
│  └── Mobile App Launch Time:       ≤ 2 seconds                             │
├────────────────────────────────────────────────────────────────────────────┤
│  Security Standards                                                         │
│  ├── OWASP Top 10:                 0 critical vulnerabilities              │
│  ├── Dependency Vulnerabilities:   0 high/critical                         │
│  ├── Authentication:               MFA required for admin                  │
│  └── Data Encryption:              AES-256 at rest, TLS 1.3 in transit     │
└────────────────────────────────────────────────────────────────────────────┘
```

### 1.4 Budget Summary

#### 1.4.1 Phase 4 Budget Allocation

| Category | Amount (BDT) | Amount (USD) | % of Total | Notes |
|----------|-------------|--------------|------------|-------|
| **Personnel Costs** | ৳8,500,000 | $77,273 | 55.9% | 3 senior developers + contractors |
| Infrastructure & Cloud | ৳1,800,000 | $16,364 | 11.8% | AWS/Azure + monitoring |
| Third-Party Licenses | ৳1,200,000 | $10,909 | 7.9% | Odoo enterprise, tools |
| Hardware & IoT Devices | ৳1,500,000 | $13,636 | 9.9% | Sensors, gateways, servers |
| Training & Certification | ৳600,000 | $5,455 | 3.9% | Staff training programs |
| Contingency Reserve | ৳1,600,000 | $14,545 | 10.5% | Risk mitigation buffer |
| **TOTAL** | **৳15,200,000** | **$138,182** | **100%** | |

#### 1.4.2 Developer Cost Breakdown (100 Days)

| Role | Daily Rate | Days | Total Cost | Responsibilities |
|------|-----------|------|------------|------------------|
| Senior Odoo Developer | ৳18,000 | 100 | ৳1,800,000 | ERP modules, backend |
| Full-Stack Developer | ৳15,000 | 100 | ৳1,500,000 | Portal, APIs, integrations |
| Mobile App Developer | ৳14,000 | 100 | ৳1,400,000 | Flutter apps, IoT client |
| DevOps Engineer (Part-time) | ৳12,000 | 50 | ৳600,000 | CI/CD, infrastructure |
| QA Engineer (Part-time) | ৳10,000 | 40 | ৳400,000 | Testing, automation |
| UI/UX Designer (Part-time) | ৳12,000 | 30 | ৳360,000 | Design system, mockups |
| Project Manager (20%) | ৳25,000 | 20 | ৳500,000 | Coordination, reporting |
| **Subtotal** | | | **৳6,560,000** | |
| Benefits & Overhead (30%) | | | ৳1,968,000 | Insurance, equipment |
| **Total Personnel** | | | **৳8,528,000** | |

### 1.5 Critical Success Factors

#### 1.5.1 Technical Success Factors

1. **Architecture Integrity**: Maintain clean separation of concerns between modules
2. **Data Consistency**: Ensure transactional integrity across distributed systems
3. **Scalability**: Design for 10x growth without architectural changes
4. **Security**: Implement defense-in-depth strategy
5. **Observability**: Comprehensive logging, monitoring, and alerting

#### 1.5.2 Organizational Success Factors

1. **Executive Sponsorship**: Continued C-level support and resource allocation
2. **Change Management**: Effective communication and training programs
3. **User Adoption**: Intuitive UX driving high user engagement
4. **Vendor Management**: Reliable third-party service providers
5. **Risk Mitigation**: Proactive identification and resolution of blockers

### 1.6 Dependencies on Previous Phases

```
┌────────────────────────────────────────────────────────────────────────────┐
│                    PHASE DEPENDENCY MAP                                    │
├────────────────────────────────────────────────────────────────────────────┤
│                                                                            │
│   Phase 1 (Days 1-100)        Phase 2 (Days 101-200)      Phase 3 (Days 201-300)   │
│   ┌──────────────┐           ┌──────────────┐          ┌──────────────┐   │
│   │ Foundation   │──────────▶│ Operations   │─────────▶│ Commerce &   │   │
│   │              │           │              │          │ Intelligence │   │
│   │ • Core Infra │           │ • B2C E-com  │          │ • B2B Portal │   │
│   │ • Odoo Setup │           │ • Mobile Apps│          │ • IoT Found. │   │
│   │ • Basic Web  │           │ • Subscriptions│        │ • BI Tools   │   │
│   └──────────────┘           └──────────────┘          └──────────────┘   │
│          │                          │                         │            │
│          │                          │                         │            │
│          │                          │                         ▼            │
│          │                          │                  ┌──────────────┐    │
│          │                          │                  │ Phase 4      │    │
│          │                          └─────────────────▶│ (Days 301-400)│    │
│          │                                            │              │    │
│          └────────────────────────────────────────────▶│ • B2B Market │    │
│                                                        │ • Smart Farm │    │
│                                                        │ • Admin Portal│   │
│                                                        │ • ERP System │    │
│                                                        │ • Integration│    │
│                                                        └──────────────┘    │
│                                                                            │
└────────────────────────────────────────────────────────────────────────────┘
```

#### 1.6.1 Critical Dependencies from Phase 3

| Phase 3 Deliverable | Phase 4 Dependency | Impact if Missing |
|---------------------|-------------------|-------------------|
| B2B Portal Foundation | Advanced B2B features | Cannot implement tier pricing |
| IoT Infrastructure | Smart Farm Management | Cannot collect sensor data |
| BI Tools Setup | ERP Reporting | Limited analytics capabilities |
| PostgreSQL 16 Cluster | ERP Data Storage | Performance degradation |
| Authentication Service | Admin Portal RBAC | Security vulnerabilities |
| API Gateway | All integrations | Integration complexity increases |

---

## 2. Phase 4 Architecture Overview

### 2.1 System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                      SMART DAIRY PHASE 4 ARCHITECTURE                                │
│                                    Complete System Topology (Day 400)                               │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                          CLIENT LAYER                                                │
├─────────────────────────────┬─────────────────────────────┬─────────────────────────────────────────┤
│      B2B CUSTOMERS          │       FARM OPERATIONS       │          ADMINISTRATORS                 │
│  ┌─────────────────────┐   │  ┌─────────────────────┐   │  ┌─────────────────────────────────┐   │
│  │   B2B Web Portal    │   │  │  Farm Mobile App    │   │  │    Admin Dashboard (Web)        │   │
│  │   (React.js)        │   │  │  (Flutter)          │   │  │    (Odoo Backend)               │   │
│  └─────────────────────┘   │  └─────────────────────┘   │  └─────────────────────────────────┘   │
│  ┌─────────────────────┐   │  ┌─────────────────────┐   │  ┌─────────────────────────────────┐   │
│  │   B2B Mobile App    │   │  │  IoT Sensors        │   │  │    System Monitor (Grafana)     │   │
│  │   (Flutter)         │   │  │  (MQTT/LoRaWAN)     │   │  └─────────────────────────────────┘   │
│  └─────────────────────┘   │  └─────────────────────┘   │                                        │
└─────────────────────────────┴─────────────────────────────┴─────────────────────────────────────────┘
                                            │
                                            ▼
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                    API GATEWAY LAYER (Kong/AWS)                                      │
│  ┌─────────────────────────────────────────────────────────────────────────────────────────────┐   │
│  │  • Rate Limiting  • Authentication  • Request Routing  • Caching  • SSL Termination         │   │
│  └─────────────────────────────────────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘
                                            │
                    ┌───────────────────────┼───────────────────────┐
                    ▼                       ▼                       ▼
┌──────────────────────────────┐ ┌──────────────────────┐ ┌──────────────────────────────────────────┐
│      APPLICATION LAYER       │ │    IoT LAYER         │ │         ERP LAYER (Odoo 19 CE)           │
│  ┌────────────────────────┐  │ │  ┌────────────────┐  │ │  ┌──────────┬──────────┬────────────────┐ │
│  │   B2B Service          │  │ │  │ MQTT Broker    │  │ │  │ Accounting│ Inventory│ Manufacturing  │ │
│  │   (Python/FastAPI)     │  │ │  │ (EMQX)         │  │ │  ├──────────┼──────────┼────────────────┤ │
│  ├────────────────────────┤  │ │  └────────────────┘  │ │  │ Purchase  │   Sales  │      HR        │ │
│  │   Farm Management      │  │ │  ┌────────────────┐  │ │  ├──────────┴──────────┴────────────────┤ │
│  │   (Odoo Custom)        │  │ │  │ TimescaleDB    │  │ │  │         Common Base Modules            │ │
│  ├────────────────────────┤  │ │  │ (Time-series)  │  │ │  │  • Base  • Mail  • Calendar  • CRM     │ │
│  │   Notification Service │  │ │  └────────────────┘  │ │  └────────────────────────────────────────┘ │
│  │   (Node.js)            │  │ │  ┌────────────────┐  │ └──────────────────────────────────────────┘
│  ├────────────────────────┤  │ │  │ IoT Processor  │  │
│  │   Payment Gateway      │  │ │  │ (Python)       │  │
│  │   (bKash/Nagad)        │  │ │  └────────────────┘  │
│  └────────────────────────┘  │ └──────────────────────┘
└──────────────────────────────┘
                                            │
                                            ▼
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                    DATA LAYER                                                        │
├─────────────────────────────┬─────────────────────────────┬─────────────────────────────────────────┤
│   PostgreSQL 16 Cluster     │   TimescaleDB Cluster       │        Redis Cluster                    │
│   ┌─────────────────────┐   │   ┌─────────────────────┐   │   ┌─────────────────────────────────┐   │
│   │ Primary (Write)     │   │   │ Hypertables         │   │   │ Session Store                   │   │
│   │ • Odoo ERP Data     │   │   │ • Sensor Data       │   │   │ • Cache Layer                   │   │
│   │ • User Data         │   │   │ • Time-series       │   │   │ • Job Queue                     │   │
│   ├─────────────────────┤   │   ├─────────────────────┤   │   │ • Real-time Pub/Sub             │   │
│   │ Replicas (Read)     │   │   │ Continuous Aggreg.  │   │   └─────────────────────────────────┘   │
│   │ • Reporting         │   │   │ • Data Retention    │   │                                        │
│   │ • Analytics         │   │   └─────────────────────┘   │                                        │
│   └─────────────────────┘   │                             │                                        │
└─────────────────────────────┴─────────────────────────────┴─────────────────────────────────────────┘
                                            │
                                            ▼
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                  INTEGRATION LAYER                                                   │
│  ┌────────────────┐  ┌────────────────┐  ┌────────────────┐  ┌────────────────┐  ┌────────────────┐ │
│  │   bKash API    │  │   Nagad API    │  │  Bangladesh    │  │   SMS Gateway  │  │   Email Service│ │
│  │   (Payment)    │  │   (Payment)    │  │  Bank APIs     │  │   (SSL/Grameen)│  │   (SendGrid)   │ │
│  └────────────────┘  └────────────────┘  └────────────────┘  └────────────────┘  └────────────────┘ │
│  ┌────────────────┐  ┌────────────────┐  ┌────────────────┐  ┌────────────────┐                      │
│  │   NBR VAT      │  │   GPS Tracking │  │   Weather API  │  │   AI/ML        │                      │
│  │   Integration  │  │   (Vehicle)    │  │   (Forecast)   │  │   (Future)     │                      │
│  └────────────────┘  └────────────────┘  └────────────────┘  └────────────────┘                      │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘
                                            │
                                            ▼
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                  MONITORING & OBSERVABILITY                                          │
│  ┌─────────────────────────────────────────────────────────────────────────────────────────────┐   │
│  │  Prometheus (Metrics) │ Grafana (Visualization) │ ELK Stack (Logs) │ Jaeger (Tracing)       │   │
│  └─────────────────────────────────────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Technology Stack Additions

#### 2.2.1 Phase 4 New Technologies

```python
PHASE_4_TECHNOLOGY_STACK = {
    "backend_frameworks": {
        "odoo_19_ce": {
            "version": "19.0 Community Edition",
            "python_version": "3.12+",
            "postgresql": "16.x",
            "use_cases": [
                "ERP Core System",
                "Accounting Module",
                "Inventory Management",
                "Manufacturing (MRP)",
                "HR & Payroll",
                "Purchase & Sales"
            ],
            "customization_approach": "Module-based custom development"
        },
        "fastapi": {
            "version": "0.104+",
            "use_cases": [
                "B2B API Services",
                "IoT Data Ingestion",
                "Real-time Notifications",
                "Third-party Integrations"
            ],
            "performance_target": "10,000 req/sec per instance"
        }
    },
    
    "mobile_development": {
        "flutter": {
            "version": "3.16+",
            "dart_version": "3.2+",
            "platforms": ["Android", "iOS"],
            "applications": [
                "B2B Customer Mobile App",
                "Farm Operations Mobile App",
                "Admin Companion App"
            ],
            "state_management": "Riverpod + Clean Architecture",
            "local_storage": "Hive + SQLite",
            "connectivity": "Offline-first with sync"
        }
    },
    
    "iot_infrastructure": {
        "mqtt_broker": {
            "product": "EMQX Enterprise",
            "version": "5.x",
            "protocols": ["MQTT 3.1.1", "MQTT 5.0", "WebSocket"],
            "clustering": "Multi-node cluster",
            "expected_connections": "10,000+ concurrent"
        },
        "timescaledb": {
            "version": "2.12+",
            "postgresql_extension": "Yes",
            "use_cases": [
                "Sensor time-series data",
                "Environmental monitoring",
                "Production metrics",
                "Equipment telemetry"
            ],
            "retention_policy": "Hot: 30 days, Warm: 90 days, Cold: 2 years"
        },
        "sensor_protocols": {
            "mqtt": "Primary transport",
            "lora_wan": "Long-range farm sensors",
            "ble": "Wearable devices",
            "modbus_tcp": "Industrial equipment",
            "opc_ua": "Future industrial integration"
        }
    },
    
    "bangladesh_payment_gateways": {
        "bkash": {
            "integration_type": "bKash Payment Gateway API v3.0",
            "features": [
                "Checkout URL",
                "Create Payment",
                "Execute Payment",
                "Query Payment",
                "Refund"
            ],
            "authentication": "JWT + API Key",
            "webhook_support": "Yes"
        },
        "nagad": {
            "integration_type": "Nagad Merchant API",
            "features": [
                "Initialize Payment",
                "Verify Payment",
                "Check Payment Status",
                "Refund Transaction"
            ],
            "authentication": "RSA Signature + API Key"
        },
        "bank_transfer": {
            "supported_banks": [
                "Sonali Bank",
                "Janata Bank", 
                "Agrani Bank",
                "Rupali Bank",
                "BRAC Bank",
                "Dutch Bangla Bank"
            ],
            "integration": "NPSB/RTGS/BEFTN"
        }
    },
    
    "business_intelligence": {
        "apache_superset": {
            "version": "3.0+",
            "use_cases": [
                "Executive Dashboards",
                "Sales Analytics",
                "Farm Performance",
                "Financial Reporting"
            ],
            "data_sources": ["PostgreSQL", "TimescaleDB", "CSV Upload"],
            "alerting": "Email + In-app notifications"
        },
        "metabase": {
            "use": "Ad-hoc queries and self-service analytics",
            "deployment": "Docker container"
        }
    }
}
```

#### 2.2.2 Infrastructure Specifications

```yaml
# Infrastructure Configuration for Phase 4
infrastructure:
  cloud_provider: "AWS / Azure / Local Data Center (Hybrid)"
  
  compute:
    application_servers:
      count: 4
      specs:
        cpu: "8 vCPU"
        memory: "32 GB RAM"
        storage: "200 GB SSD"
      os: "Ubuntu 24.04 LTS"
      load_balancer: "NGINX / AWS ALB"
    
    database_servers:
      primary:
        specs:
          cpu: "16 vCPU"
          memory: "64 GB RAM"
          storage: "1 TB NVMe SSD"
        replication: "Streaming replication to 2 replicas"
      
      timescaledb:
        specs:
          cpu: "12 vCPU"
          memory: "48 GB RAM"
          storage: "2 TB SSD (expandable)"
        partitioning: "Monthly hypertable chunks"
    
    cache_servers:
      redis_cluster:
        nodes: 3
        specs_per_node:
          cpu: "4 vCPU"
          memory: "16 GB RAM"
        persistence: "AOF + RDB"
  
  networking:
    vpc_cidr: "10.0.0.0/16"
    subnets:
      public: ["10.0.1.0/24", "10.0.2.0/24"]
      private_app: ["10.0.10.0/24", "10.0.11.0/24"]
      private_db: ["10.0.20.0/24", "10.0.21.0/24"]
    
    security:
      ssl_tls: "TLS 1.3 enforced"
      vpn: "Site-to-site for farm connectivity"
      waf: "AWS WAF / ModSecurity"
  
  monitoring:
    prometheus:
      retention: "15 days"
      scrape_interval: "15s"
    
    grafana:
      dashboards: 25
      alert_channels: ["Email", "Slack", "SMS"]
    
    elk_stack:
      log_retention: "90 days"
      daily_volume: "50 GB"
```

### 2.3 Integration Patterns

#### 2.3.1 Enterprise Integration Patterns

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                               INTEGRATION PATTERN CATALOG                                            │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘

Pattern 1: API Gateway Pattern
──────────────────────────────────────────────────────────────────────────────────────────────────────

    ┌──────────────┐     ┌──────────────┐     ┌──────────────┐
    │   Client A   │     │   Client B   │     │   Client C   │
    └──────┬───────┘     └──────┬───────┘     └──────┬───────┘
           │                    │                    │
           └────────────────────┼────────────────────┘
                                │
                    ┌───────────▼───────────┐
                    │     API Gateway       │
                    │  ┌─────────────────┐  │
                    │  │ • Auth          │  │
                    │  │ • Rate Limit    │  │
                    │  │ • Routing       │  │
                    │  │ • Caching       │  │
                    │  └─────────────────┘  │
                    └───────────┬───────────┘
                                │
           ┌────────────────────┼────────────────────┐
           │                    │                    │
    ┌──────▼───────┐     ┌──────▼───────┐     ┌──────▼───────┐
    │  B2B Service │     │  Farm Service│     │   ERP Module │
    └──────────────┘     └──────────────┘     └──────────────┘

Pattern 2: Event-Driven Architecture (EDA)
──────────────────────────────────────────────────────────────────────────────────────────────────────

    ┌──────────────┐
    │ IoT Sensor   │──────┐
    └──────────────┘      │     ┌─────────────────────────────────────────┐
                          │     │          Event Bus (Redis/RabbitMQ)    │
    ┌──────────────┐      │     │  ┌─────────┐  ┌─────────┐  ┌─────────┐ │
    │ Farm System  │──────┼────▶│  │ Topic 1 │  │ Topic 2 │  │ Topic 3 │ │
    └──────────────┘      │     │  └────┬────┘  └────┬────┘  └────┬────┘ │
                          │     └───────┼────────────┼────────────┼──────┘
    ┌──────────────┐      │             │            │            │
    │ ERP System   │──────┘             │            │            │
    └──────────────┘                    │            │            │
                                        ▼            ▼            ▼
                                  ┌─────────┐  ┌─────────┐  ┌─────────┐
                                  │Handler 1│  │Handler 2│  │Handler 3│
                                  │(Alerts) │  │(Storage)│  │(Analytics)
                                  └─────────┘  └─────────┘  └─────────┘

Pattern 3: Saga Pattern for Distributed Transactions
──────────────────────────────────────────────────────────────────────────────────────────────────────

    Order Creation Saga:
    
    ┌──────────────┐
    │   Start      │
    └──────┬───────┘
           │
           ▼
    ┌──────────────┐     Success      ┌──────────────┐
    │ Reserve Stock│─────────────────▶│ Process Payment
    └──────────────┘                  └──────┬───────┘
           │                                  │
           │ Failure                          │ Success
           ▼                                  ▼
    ┌──────────────┐                  ┌──────────────┐
    │  Compensate  │                  │ Create Invoice│
    │  (Release    │                  └──────┬───────┘
    │   Stock)     │                         │
    └──────────────┘                         ▼
                                      ┌──────────────┐
                                      │   Complete   │
                                      └──────────────┘

Pattern 4: CQRS (Command Query Responsibility Segregation)
──────────────────────────────────────────────────────────────────────────────────────────────────────

    ┌─────────────────────────────────────────────────────────────────────────┐
    │                              CLIENT                                      │
    └───────────────────────────────┬─────────────────────────────────────────┘
                                    │
                    ┌───────────────┴───────────────┐
                    │                               │
                    ▼                               ▼
           ┌────────────────┐              ┌────────────────┐
           │  Command Side  │              │   Query Side   │
           │                │              │                │
           │ ┌────────────┐ │              │ ┌────────────┐ │
           │ │  Commands  │ │              │ │   Queries  │ │
           │ │  (Write)   │ │              │ │   (Read)   │ │
           │ └─────┬──────┘ │              │ └─────┬──────┘ │
           └───────┼────────┘              └───────┼────────┘
                   │                               │
                   ▼                               ▼
           ┌────────────────┐              ┌────────────────┐
           │ Write Model    │              │  Read Model    │
           │ (PostgreSQL)   │              │  (Redis/       │
           │                │              │   Materialized │
           │ Normalized     │              │   Views)       │
           │ Schema         │              │                │
           └────────────────┘              └────────────────┘
                   │                               │
                   │    Event Sourcing Stream      │
                   └──────────────────────────────▶│
                                                   │
                                            ┌──────▼───────┐
                                            │ Event Store  │
                                            │ (Append-only)│
                                            └──────────────┘

Pattern 5: Strangler Fig Pattern (Legacy Migration)
──────────────────────────────────────────────────────────────────────────────────────────────────────

    Phase 1 (Current)                    Phase 2                            Phase 3 (Target)
    
    ┌──────────────┐                     ┌──────────────┐
    │   Legacy     │◀────────────────────│   Legacy     │
    │   System     │                     │   System     │
    └──────┬───────┘                     └──────┬───────┘
           │                                    │
           │                                    │
           ▼                                    ▼
    ┌──────────────┐                     ┌──────────────┐
    │   Monolith   │                     │   Monolith   │
    │   (Partial)  │                     │   (Reduced)  │
    └──────────────┘                     └──────┬───────┘
                                                │
                                         ┌──────┴──────┐
                                         │             │
                                         ▼             ▼
                                   ┌──────────┐  ┌──────────┐
                                   │ Service 1│  │ Service 2│
                                   │ (New)    │  │ (New)    │
                                   └──────────┘  └──────────┘
```

#### 2.3.2 Data Flow Architecture

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                              PHASE 4 DATA FLOW ARCHITECTURE                                          │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘

Flow 1: B2B Order Processing
──────────────────────────────────────────────────────────────────────────────────────────────────────

    B2B Customer                              Smart Dairy Platform
    
    ┌─────────────┐                           ┌─────────────────────────────────────────────────────┐
    │ Browse      │                           │                                                     │
    │ Catalog     │──────────────────────────▶│  1. Product Service (Search/Filter)                 │
    └─────────────┘                           │                                                     │
          │                                   └─────────────────────────────────────────────────────┘
          │                                                            │
          │                                                            ▼
          │                                   ┌─────────────────────────────────────────────────────┐
          │                                   │  2. Pricing Engine (Tier/Volume pricing)            │
          │                                   │     • Customer tier lookup                          │
          │                                   │     • Volume discount calculation                   │
          │                                   │     • Credit limit check                            │
          │                                   └─────────────────────────────────────────────────────┘
          │                                                            │
          ▼                                                            ▼
    ┌─────────────┐                           ┌─────────────────────────────────────────────────────┐
    │ Add to      │                           │  3. Cart Service                                      │
    │ Cart        │──────────────────────────▶│     • Session management                              │
    └─────────────┘                           │     • Cart persistence                                │
          │                                   └─────────────────────────────────────────────────────┘
          │                                                            │
          ▼                                                            ▼
    ┌─────────────┐                           ┌─────────────────────────────────────────────────────┐
    │ Checkout    │                           │  4. Order Service                                     │
    │             │──────────────────────────▶│     • Order validation                                │
    └─────────────┘                           │     • Inventory reservation                           │
          │                                   │     • Payment initiation                              │
          │                                   └─────────────────────────────────────────────────────┘
          │                                                            │
          │                                   ┌─────────────────────────────────────────────────────┐
          │                                   │  5. Payment Gateway (bKash/Nagad)                   │
          │                                   │     • Payment URL generation                          │
          │                                   │     • Transaction logging                             │
          │                                   └─────────────────────────────────────────────────────┘
          │                                                            │
          ▼                                                            ▼
    ┌─────────────┐                           ┌─────────────────────────────────────────────────────┐
    │ Payment     │                           │  6. Webhook Handler                                   │
    │ Complete    │◀──────────────────────────│     • Payment status update                           │
    └─────────────┘                           │     • Order confirmation                              │
          │                                   │     • Invoice generation                              │
          │                                   │     • Notification dispatch                           │
          │                                   └─────────────────────────────────────────────────────┘
          │                                                            │
          ▼                                                            ▼
    ┌─────────────┐                           ┌─────────────────────────────────────────────────────┐
    │ Receive     │                           │  7. ERP Integration                                   │
    │ Invoice     │◀──────────────────────────│     • Odoo Sales Order                                │
    └─────────────┘                           │     • Inventory update                                │
                                              │     • Accounting entry                                │
                                              └─────────────────────────────────────────────────────┘

Flow 2: IoT Sensor Data Pipeline
──────────────────────────────────────────────────────────────────────────────────────────────────────

    Farm Sensors                              Data Processing Pipeline
    
    ┌─────────────┐     MQTT/LoRaWAN          ┌─────────────────────────────────────────────────────┐
    │ Temperature │──────────────────────────▶│  1. MQTT Broker (EMQX)                                │
    │ Sensor      │                           │     • Topic: farm/shed-01/temperature                 │
    └─────────────┘                           │     • QoS: 1 (at least once)                          │
                                              │     • Retain: false                                   │
                                              └─────────────────────────────────────────────────────┘
                                                                          │
                                                                          ▼
                                              ┌─────────────────────────────────────────────────────┐
    ┌─────────────┐                           │  2. Stream Processor (Python/AIOKafka)              │
    │ Humidity    │──────────────────────────▶│     • Message validation                              │
    │ Sensor      │                           │     • Anomaly detection                               │
    └─────────────┘                           │     • Data enrichment (metadata, location)          │
                                              └─────────────────────────────────────────────────────┘
                                                                          │
                                    ┌─────────────────────────────────────┴─────────────────────────┐
                                    │                                                               │
                                    ▼                                                               ▼
                            ┌───────────────────────┐                               ┌───────────────────────┐
                            │ 3a. TimescaleDB       │                               │ 3b. Real-time Alerts  │
                            │    (Time-series)      │                               │    (Redis Pub/Sub)    │
                            │                       │                               │                       │
                            │ Hypertable: sensor_data│                              │ Alert Rules Engine    │
                            │ - time (timestamptz)  │                               │ - Threshold checks    │
                            │ - sensor_id (int)     │                               │ - Pattern detection   │
                            │ - value (float)       │                               │ - Multi-channel       │
                            │ - metadata (jsonb)    │                               │   dispatch            │
                            │                       │                               │                       │
                            │ Chunk: 1 day          │                               │                       │
                            │ Retention: 2 years    │                               │                       │
                            └───────────────────────┘                               └───────────────────────┘
                                                                          │                               │
                                                                          │                               ▼
                                                                          │                       ┌───────────────────────┐
                                                                          │                       │ 4. Notification       │
                                                                          │                       │    Dispatch           │
                                                                          │                       │    - Push (FCM/APNS)  │
                                                                          │                       │    - SMS (Twilio)     │
                                                                          │                       │    - Email (SendGrid) │
                                                                          │                       │    - Dashboard        │
                                                                          │                       └───────────────────────┘
                                                                          │
                                                                          ▼
                                              ┌─────────────────────────────────────────────────────┐
                                              │ 5. Aggregations & Analytics                           │
                                              │    • Continuous aggregates (hourly, daily)          │
                                              │    • Materialized views for dashboards              │
                                              │    • Data export for BI tools                         │
                                              └─────────────────────────────────────────────────────┘

Flow 3: ERP Transaction Processing
──────────────────────────────────────────────────────────────────────────────────────────────────────

    User Action                               ERP System (Odoo)
    
    ┌─────────────┐                           ┌─────────────────────────────────────────────────────┐
    │ Create      │                           │  1. Controller Layer (@http.route)                  │
    │ Sales Order │──────────────────────────▶│     • Request validation                              │
    │             │                           │     • Authentication check                            │
    └─────────────┘                           │     • Authorization (RBAC)                            │
                                              └─────────────────────────────────────────────────────┘
                                                                          │
                                                                          ▼
                                              ┌─────────────────────────────────────────────────────┐
                                              │  2. Business Logic Layer (Model Methods)            │
                                              │     • def create(self, vals):                         │
                                              │     • Stock availability check                        │
                                              │     • Pricing calculation                             │
                                              │     • Tax computation (Bangladesh VAT)                │
                                              │     • Credit limit validation                         │
                                              └─────────────────────────────────────────────────────┘
                                                                          │
                                    ┌─────────────────────────────────────┴─────────────────────────┐
                                    │                                                               │
                                    ▼                                                               ▼
                            ┌───────────────────────┐                               ┌───────────────────────┐
                            │ 3a. Database Layer    │                               │ 3b. External          │
                            │    (PostgreSQL)       │                               │    Integrations       │
                            │                       │                               │                       │
                            │ Transaction:          │                               │ • bKash (if payment)  │
                            │ BEGIN;                │                               │ • SMS Gateway         │
                            │ INSERT INTO sale_order│                               │ • Email Service       │
                            │ INSERT INTO order_line│                               │ • Inventory System    │
                            │ UPDATE stock_quant    │                               │                       │
                            │ COMMIT;               │                               │                       │
                            │                       │                               │                       │
                            │ Constraints:          │                               │                       │
                            │ - Foreign keys        │                               │                       │
                            │ - Check constraints   │                               │                       │
                            │ - Triggers (audit)    │                               │                       │
                            └───────────────────────┘                               └───────────────────────┘
                                                                          │
                                                                          ▼
                                              ┌─────────────────────────────────────────────────────┐
                                              │  4. Post-Processing                                   │
                                              │     • Workflow state update                           │
                                              │     • Automated actions (email, task)                 │
                                              │     • Journal entry creation (accounting)             │
                                              │     • Cache invalidation                              │
                                              └─────────────────────────────────────────────────────┘
                                                                          │
                                                                          ▼
                                              ┌─────────────────────────────────────────────────────┐
                                              │  5. Response                                          │
                                              │     • Serialized record (JSON)                        │
                                              │     • HTTP 200/201                                    │
                                              │     • ETag for caching                                │
                                              └─────────────────────────────────────────────────────┘
```

### 2.4 Database Schema Design

#### 2.4.1 ERP Core Schema (Odoo)

```sql
-- ============================================
-- SMART DAIRY ERP DATABASE SCHEMA
-- Phase 4 Implementation - Odoo 19 CE
-- ============================================

-- ============================================
-- B2B MARKETPLACE TABLES
-- ============================================

-- Customer Tiers and Pricing
CREATE TABLE res_partner_tier (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(20) UNIQUE NOT NULL,
    min_monthly_volume DECIMAL(15,2),
    discount_percentage DECIMAL(5,2) DEFAULT 0.00,
    credit_limit DECIMAL(15,2) DEFAULT 0.00,
    payment_terms INTEGER REFERENCES account_payment_term(id),
    priority INTEGER DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- B2B Contract Management
CREATE TABLE b2b_contract (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    partner_id INTEGER REFERENCES res_partner(id) NOT NULL,
    tier_id INTEGER REFERENCES res_partner_tier(id),
    contract_type VARCHAR(50), -- 'annual', 'quarterly', 'monthly'
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    min_order_value DECIMAL(15,2),
    committed_volume DECIMAL(15,2),
    special_pricing JSONB, -- Custom pricing rules
    payment_terms INTEGER,
    state VARCHAR(20) DEFAULT 'draft', -- draft, confirmed, active, expired, cancelled
    responsible_id INTEGER REFERENCES res_users(id),
    company_id INTEGER REFERENCES res_company(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Volume-based Pricing Tiers
CREATE TABLE product_volume_price (
    id SERIAL PRIMARY KEY,
    product_id INTEGER REFERENCES product_template(id) NOT NULL,
    tier_id INTEGER REFERENCES res_partner_tier(id),
    min_quantity DECIMAL(10,2) NOT NULL,
    max_quantity DECIMAL(10,2),
    price_unit DECIMAL(15,2) NOT NULL,
    currency_id INTEGER REFERENCES res_currency(id),
    date_start DATE,
    date_end DATE,
    active BOOLEAN DEFAULT TRUE,
    company_id INTEGER REFERENCES res_company(id)
);

-- Credit Management
CREATE TABLE account_credit_limit (
    id SERIAL PRIMARY KEY,
    partner_id INTEGER REFERENCES res_partner(id) NOT NULL,
    credit_limit DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    credit_used DECIMAL(15,2) DEFAULT 0.00,
    credit_available DECIMAL(15,2) GENERATED ALWAYS AS (credit_limit - credit_used) STORED,
    approval_required_above DECIMAL(15,2),
    approval_chain JSONB, -- Array of approver user IDs
    hold_orders_if_exceeded BOOLEAN DEFAULT TRUE,
    company_id INTEGER REFERENCES res_company(id),
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- SMART FARM MANAGEMENT TABLES
-- ============================================

-- Livestock/Herd Master Data
CREATE TABLE farm_animal (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,
    species VARCHAR(50) NOT NULL, -- 'cattle', 'buffalo', 'goat'
    breed_id INTEGER REFERENCES farm_breed(id),
    gender VARCHAR(10) NOT NULL, -- 'male', 'female'
    birth_date DATE,
    age_days INTEGER GENERATED ALWAYS AS (CURRENT_DATE - birth_date) STORED,
    weight_current DECIMAL(8,2),
    weight_birth DECIMAL(8,2),
    color VARCHAR(50),
    identifying_marks TEXT,
    mother_id INTEGER REFERENCES farm_animal(id),
    father_id INTEGER REFERENCES farm_animal(id),
    supplier_id INTEGER REFERENCES res_partner(id),
    purchase_date DATE,
    purchase_price DECIMAL(12,2),
    location_id INTEGER REFERENCES farm_location(id),
    status VARCHAR(30) DEFAULT 'active', -- active, sold, deceased, breeding
    state VARCHAR(30) DEFAULT 'healthy', -- healthy, sick, quarantine, treatment
    image VARCHAR(255),
    company_id INTEGER REFERENCES res_company(id),
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Animal Health Records
CREATE TABLE farm_health_record (
    id SERIAL PRIMARY KEY,
    animal_id INTEGER REFERENCES farm_animal(id) NOT NULL,
    record_type VARCHAR(50) NOT NULL, -- checkup, vaccination, treatment, surgery
    record_date DATE NOT NULL DEFAULT CURRENT_DATE,
    veterinarian_id INTEGER REFERENCES res_partner(id),
    diagnosis TEXT,
    symptoms TEXT,
    treatment TEXT,
    medications JSONB, -- [{"name": "", "dosage": "", "frequency": ""}]
    follow_up_date DATE,
    cost DECIMAL(10,2),
    attachment_ids INTEGER[], -- References to ir.attachment
    notes TEXT,
    state VARCHAR(20) DEFAULT 'open', -- open, in_progress, resolved, chronic
    company_id INTEGER REFERENCES res_company(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Milk Production Records
CREATE TABLE farm_milk_production (
    id SERIAL PRIMARY KEY,
    animal_id INTEGER REFERENCES farm_animal(id) NOT NULL,
    milking_date DATE NOT NULL DEFAULT CURRENT_DATE,
    milking_session VARCHAR(20) NOT NULL, -- morning, afternoon, evening
    milking_time TIMESTAMP,
    quantity_liters DECIMAL(6,2) NOT NULL,
    fat_percentage DECIMAL(4,2),
    snf_percentage DECIMAL(4,2), -- Solids-not-fat
    protein_percentage DECIMAL(4,2),
    lactose_percentage DECIMAL(4,2),
    somatic_cell_count INTEGER, -- SCC
    temperature DECIMAL(4,1),
    quality_grade VARCHAR(10), -- A, B, C
    equipment_id INTEGER REFERENCES farm_equipment(id),
    operator_id INTEGER REFERENCES res_users(id),
    location_id INTEGER REFERENCES farm_location(id),
    company_id INTEGER REFERENCES res_company(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Breeding Management
CREATE TABLE farm_breeding (
    id SERIAL PRIMARY KEY,
    female_id INTEGER REFERENCES farm_animal(id) NOT NULL,
    male_id INTEGER REFERENCES farm_animal(id),
    semen_id INTEGER REFERENCES farm_semen_inventory(id),
    breeding_type VARCHAR(20) NOT NULL, -- natural, ai (artificial insemination)
    breeding_date DATE NOT NULL,
    expected_calving_date DATE,
    actual_calving_date DATE,
    pregnancy_check_date DATE,
    pregnancy_confirmed BOOLEAN,
    vet_id INTEGER REFERENCES res_partner(id),
    cost DECIMAL(10,2),
    outcome VARCHAR(20), -- successful, failed, aborted
    offspring_ids INTEGER[], -- References to farm_animal
    notes TEXT,
    company_id INTEGER REFERENCES res_company(id),
    state VARCHAR(20) DEFAULT 'planned'
);

-- Farm Locations/Sheds
CREATE TABLE farm_location (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,
    location_type VARCHAR(50), -- shed, pasture, milking_parlor, quarantine
    capacity INTEGER,
    current_occupancy INTEGER DEFAULT 0,
    area_sqm DECIMAL(8,2),
    manager_id INTEGER REFERENCES res_users(id),
    parent_id INTEGER REFERENCES farm_location(id),
    gps_coordinates POINT,
    active BOOLEAN DEFAULT TRUE,
    company_id INTEGER REFERENCES res_company(id)
);

-- ============================================
-- IOT SENSOR DATA (TimescaleDB Hypertables)
-- ============================================

-- Sensor Registry
CREATE TABLE iot_sensor (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    device_id VARCHAR(100) UNIQUE NOT NULL,
    sensor_type VARCHAR(50) NOT NULL, -- temperature, humidity, motion, gps
    location_id INTEGER REFERENCES farm_location(id),
    animal_id INTEGER REFERENCES farm_animal(id),
    protocol VARCHAR(20) DEFAULT 'mqtt', -- mqtt, lora, ble
    manufacturer VARCHAR(100),
    model VARCHAR(100),
    firmware_version VARCHAR(50),
    battery_level INTEGER, -- percentage
    signal_strength INTEGER, -- RSSI
    calibration_date DATE,
    active BOOLEAN DEFAULT TRUE,
    metadata JSONB,
    company_id INTEGER REFERENCES res_company(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sensor Readings Time-series Data (TimescaleDB Hypertable)
CREATE TABLE iot_sensor_reading (
    time TIMESTAMPTZ NOT NULL,
    sensor_id INTEGER REFERENCES iot_sensor(id) NOT NULL,
    location_id INTEGER REFERENCES farm_location(id),
    reading_type VARCHAR(50) NOT NULL, -- temperature, humidity, pressure, etc.
    value DOUBLE PRECISION NOT NULL,
    unit VARCHAR(20), -- celsius, percent, pascal
    quality_score INTEGER DEFAULT 100, -- Data quality indicator
    metadata JSONB, -- Additional sensor-specific data
    processed BOOLEAN DEFAULT FALSE -- Whether alert rules have been evaluated
);

-- Convert to hypertable for time-series optimization
SELECT create_hypertable('iot_sensor_reading', 'time', chunk_time_interval => INTERVAL '1 day');

-- Create indexes for common queries
CREATE INDEX idx_sensor_reading_sensor_time ON iot_sensor_reading (sensor_id, time DESC);
CREATE INDEX idx_sensor_reading_location_time ON iot_sensor_reading (location_id, time DESC);
CREATE INDEX idx_sensor_reading_type_time ON iot_sensor_reading (reading_type, time DESC);

-- Continuous Aggregates for Dashboard
CREATE MATERIALIZED VIEW iot_hourly_summary
WITH (timescaledb.continuous) AS
SELECT
    time_bucket('1 hour', time) AS bucket,
    sensor_id,
    reading_type,
    AVG(value) as avg_value,
    MIN(value) as min_value,
    MAX(value) as max_value,
    COUNT(*) as reading_count
FROM iot_sensor_reading
GROUP BY bucket, sensor_id, reading_type;

-- ============================================
-- INVENTORY & MANUFACTURING
-- ============================================

-- Multi-warehouse Inventory
CREATE TABLE stock_warehouse (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,
    warehouse_type VARCHAR(50), -- farm, processing, distribution, retail
    location_id INTEGER REFERENCES res_partner(id),
    manager_id INTEGER REFERENCES res_users(id),
    capacity_volume DECIMAL(12,2), -- cubic meters
    temperature_controlled BOOLEAN DEFAULT FALSE,
    temperature_min DECIMAL(5,2),
    temperature_max DECIMAL(5,2),
    active BOOLEAN DEFAULT TRUE,
    company_id INTEGER REFERENCES res_company(id)
);

-- Lot/Serial Tracking for Dairy Products
CREATE TABLE stock_production_lot (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    product_id INTEGER REFERENCES product_product(id) NOT NULL,
    ref VARCHAR(100),
    production_date DATE,
    expiry_date DATE,
    quantity DECIMAL(15,2),
    uom_id INTEGER REFERENCES uom_uom(id),
    quality_state VARCHAR(20), -- quarantine, accepted, rejected
    quality_check_ids INTEGER[],
    location_id INTEGER REFERENCES stock_location(id),
    company_id INTEGER REFERENCES res_company(id),
    CONSTRAINT unique_lot_per_product UNIQUE (name, product_id)
);

-- Bill of Materials for Dairy Products
CREATE TABLE mrp_bom_dairy (
    id SERIAL PRIMARY KEY,
    product_id INTEGER REFERENCES product_template(id) NOT NULL,
    product_qty DECIMAL(10,3) NOT NULL,
    product_uom_id INTEGER REFERENCES uom_uom(id),
    bom_type VARCHAR(20) DEFAULT 'normal', -- normal, phantom, subcontract
    dairy_specific JSONB, -- Fat content, SNF requirements, etc.
    operation_ids INTEGER[], -- Manufacturing operations
    company_id INTEGER REFERENCES res_company(id),
    active BOOLEAN DEFAULT TRUE
);

-- BOM Components
CREATE TABLE mrp_bom_line_dairy (
    id SERIAL PRIMARY KEY,
    bom_id INTEGER REFERENCES mrp_bom_dairy(id) NOT NULL,
    product_id INTEGER REFERENCES product_product(id) NOT NULL,
    product_qty DECIMAL(10,3) NOT NULL,
    product_uom_id INTEGER REFERENCES uom_uom(id),
    loss_percentage DECIMAL(5,2) DEFAULT 0.00, -- Wastage during processing
    sequence INTEGER DEFAULT 10,
    operation_id INTEGER, -- Associated manufacturing step
    cost_share DECIMAL(5,2) DEFAULT 100.00
);

-- ============================================
-- HR & PAYROLL (Bangladesh Localization)
-- ============================================

-- Employee Master (Extended)
CREATE TABLE hr_employee_bd (
    employee_id INTEGER PRIMARY KEY REFERENCES hr_employee(id),
    nid_number VARCHAR(50), -- National ID
    birth_registration VARCHAR(50),
    tax_id VARCHAR(50), -- TIN
    pf_number VARCHAR(50), -- Provident Fund
    insurance_number VARCHAR(50),
    blood_group VARCHAR(10),
    emergency_contact_name VARCHAR(100),
    emergency_contact_phone VARCHAR(20),
    emergency_contact_relation VARCHAR(50),
    joining_date DATE,
    confirmation_date DATE,
    resignation_date DATE,
    notice_period_days INTEGER DEFAULT 30,
    service_length_years DECIMAL(4,2) GENERATED ALWAYS AS (
        CASE 
            WHEN resignation_date IS NULL THEN 
                EXTRACT(YEAR FROM AGE(CURRENT_DATE, joining_date))
            ELSE 
                EXTRACT(YEAR FROM AGE(resignation_date, joining_date))
        END
    ) STORED
);

-- Attendance with Biometric Integration
CREATE TABLE hr_attendance_biometric (
    id SERIAL PRIMARY KEY,
    employee_id INTEGER REFERENCES hr_employee(id) NOT NULL,
    check_in TIMESTAMP NOT NULL,
    check_out TIMESTAMP,
    check_in_device VARCHAR(100),
    check_out_device VARCHAR(100),
    check_in_location POINT,
    check_out_location POINT,
    work_hours DECIMAL(4,2) GENERATED ALWAYS AS (
        EXTRACT(EPOCH FROM (check_out - check_in)) / 3600
    ) STORED,
    overtime_hours DECIMAL(4,2) DEFAULT 0.00,
    state VARCHAR(20) DEFAULT 'present', -- present, absent, late, early_leave
    notes TEXT,
    company_id INTEGER REFERENCES res_company(id)
);

-- Bangladesh Payroll Structure
CREATE TABLE hr_payslip_line_bd (
    id SERIAL PRIMARY KEY,
    payslip_id INTEGER REFERENCES hr_payslip(id) NOT NULL,
    category VARCHAR(50) NOT NULL, -- basic, house_rent, medical, conveyance, etc.
    name VARCHAR(100),
    amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    quantity DECIMAL(8,2) DEFAULT 1.00,
    rate DECIMAL(12,2),
    computation_method VARCHAR(50), -- fixed, percentage, formula
    employer_contribution DECIMAL(12,2) DEFAULT 0.00, -- For PF, etc.
    tax_applicable BOOLEAN DEFAULT TRUE,
    sequence INTEGER DEFAULT 10
);

-- Leave Types (Bangladesh Labor Law)
CREATE TABLE hr_leave_type_bd (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,
    allocation_type VARCHAR(20), -- annual, sick, casual, maternity, etc.
    days_per_year DECIMAL(5,2),
    carry_forward BOOLEAN DEFAULT FALSE,
    max_carry_forward_days DECIMAL(5,2),
    encashable BOOLEAN DEFAULT FALSE,
    gender_specific VARCHAR(10), -- male, female, both
    min_service_months INTEGER DEFAULT 0,
    paid_leave BOOLEAN DEFAULT TRUE,
    company_id INTEGER REFERENCES res_company(id)
);

-- ============================================
-- AUDIT & COMPLIANCE
-- ============================================

-- Audit Trail for All Transactions
CREATE TABLE audit_log (
    id BIGSERIAL PRIMARY KEY,
    table_name VARCHAR(100) NOT NULL,
    record_id INTEGER NOT NULL,
    action VARCHAR(20) NOT NULL, -- CREATE, UPDATE, DELETE
    old_values JSONB,
    new_values JSONB,
    user_id INTEGER REFERENCES res_users(id),
    user_name VARCHAR(100),
    ip_address INET,
    user_agent TEXT,
    action_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    transaction_id VARCHAR(100)
);

-- Create audit trigger function
CREATE OR REPLACE FUNCTION audit_trigger_func()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'DELETE') THEN
        INSERT INTO audit_log (table_name, record_id, action, old_values, user_id)
        VALUES (TG_TABLE_NAME, OLD.id, 'DELETE', row_to_json(OLD), current_setting('app.current_user_id')::INTEGER);
        RETURN OLD;
    ELSIF (TG_OP = 'UPDATE') THEN
        INSERT INTO audit_log (table_name, record_id, action, old_values, new_values, user_id)
        VALUES (TG_TABLE_NAME, NEW.id, 'UPDATE', row_to_json(OLD), row_to_json(NEW), current_setting('app.current_user_id')::INTEGER);
        RETURN NEW;
    ELSIF (TG_OP = 'INSERT') THEN
        INSERT INTO audit_log (table_name, record_id, action, new_values, user_id)
        VALUES (TG_TABLE_NAME, NEW.id, 'CREATE', row_to_json(NEW), current_setting('app.current_user_id')::INTEGER);
        RETURN NEW;
    END IF;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;
```

#### 2.4.2 Database Performance Optimization

```sql
-- ============================================
-- PERFORMANCE OPTIMIZATION
-- ============================================

-- Partitioning for Large Transaction Tables
CREATE TABLE account_move_line_partitioned (
    LIKE account_move_line INCLUDING ALL
) PARTITION BY RANGE (date);

-- Create monthly partitions
CREATE TABLE account_move_line_y2024m01 PARTITION OF account_move_line_partitioned
    FOR VALUES FROM ('2024-01-01') TO ('2024-02-01');
CREATE TABLE account_move_line_y2024m02 PARTITION OF account_move_line_partitioned
    FOR VALUES FROM ('2024-02-01') TO ('2024-03-01');
-- ... continue for all months

-- Indexes for Common Queries
CREATE INDEX CONCURRENTLY idx_sale_order_partner_state ON sale_order (partner_id, state);
CREATE INDEX CONCURRENTLY idx_stock_move_product_location ON stock_move (product_id, location_id, state);
CREATE INDEX CONCURRENTLY idx_farm_animal_status ON farm_animal (status) WHERE status = 'active';
CREATE INDEX CONCURRENTLY idx_milk_production_date ON farm_milk_production (milking_date DESC);

-- Full-text Search for Product Catalog
ALTER TABLE product_template ADD COLUMN search_vector tsvector;

CREATE OR REPLACE FUNCTION update_product_search_vector()
RETURNS TRIGGER AS $$
BEGIN
    NEW.search_vector := 
        setweight(to_tsvector('english', COALESCE(NEW.name, '')), 'A') ||
        setweight(to_tsvector('english', COALESCE(NEW.description, '')), 'B') ||
        setweight(to_tsvector('english', COALESCE(NEW.default_code, '')), 'C');
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_update_product_search
    BEFORE INSERT OR UPDATE ON product_template
    FOR EACH ROW EXECUTE FUNCTION update_product_search_vector();

-- Create GIN index for full-text search
CREATE INDEX idx_product_search ON product_template USING GIN (search_vector);

-- Connection Pooling with PgBouncer Configuration
-- /etc/pgbouncer/pgbouncer.ini
/*
[databases]
smart_dairy = host=localhost port=5432 dbname=smart_dairy

[pgbouncer]
pool_mode = transaction
max_client_conn = 10000
default_pool_size = 20
min_pool_size = 10
reserve_pool_size = 5
reserve_pool_timeout = 3
server_idle_timeout = 600
server_lifetime = 3600
*/
```

---

## 3. Milestone Summary Table

### 3.1 Phase 4 Milestone Overview

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                         PHASE 4 MILESTONE TIMELINE (Days 301-400)                                   │
├─────────────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                                     │
│  M31        M32        M33        M34        M35        M36        M37        M38        M39   M40 │
│   │          │          │          │          │          │          │          │          │     │  │
│   ▼          ▼          ▼          ▼          ▼          ▼          ▼          ▼          ▼     ▼  │
│  ┌─┐        ┌─┐        ┌─┐        ┌─┐        ┌─┐        ┌─┐        ┌─┐        ┌─┐        ┌─┐  ┌─┐ │
│  │B2B│──────▶│B2B│──────▶│Farm│─────▶│Farm│─────▶│Admin│────▶│ERP │──────▶│ERP │──────▶│ERP │──▶│Int│─▶│Dep│ │
│  │Adv│       │Mob│       │IoT │      │Mgt │      │Port │      │Core│      │Inv │      │MRP │   │Tst│  │Loy│ │
│  └─┘        └─┘        └─┘        └─┘        └─┘        └─┘        └─┘        └─┘        └─┘  └─┘ │
│   │          │          │          │          │          │          │          │          │     │  │
│  D301      D315       D330       D345       D360       D375       D385       D390       D395 D400│
│                                                                                                     │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Detailed Milestone Breakdown



| Milestone | Name | Duration | Start | End | Primary Focus | Key Deliverables |
|-----------|------|----------|-------|-----|---------------|------------------|
| **M31** | B2B Advanced Features | 15 days | Day 301 | Day 315 | Wholesale ordering, tier pricing | Tier pricing engine, volume discounts, contract management |
| **M32** | B2B Mobile & Credit | 15 days | Day 316 | Day 330 | B2B mobile app, credit system | Flutter B2B app, credit workflows, approval chains |
| **M33** | IoT Infrastructure | 15 days | Day 331 | Day 345 | Sensor integration, data pipeline | MQTT broker, TimescaleDB, sensor ingestion |
| **M34** | Smart Farm Management | 15 days | Day 346 | Day 360 | Herd management, health monitoring | Herd module, vet workflows, production tracking |
| **M35** | Admin Portal | 15 days | Day 361 | Day 375 | Back-office administration | User management, RBAC, system config |
| **M36** | ERP Core & Accounting | 10 days | Day 376 | Day 385 | ERP foundation, BD localization | GL, AP, AR, Bangladesh tax compliance |
| **M37** | ERP Inventory & Purchase | 10 days | Day 386 | Day 390 | Inventory management | Multi-warehouse, lot tracking, procurement |
| **M38** | ERP MRP & HR | 10 days | Day 391 | Day 395 | Manufacturing, HR/Payroll | BOM, production planning, payroll |
| **M39** | Integration Testing | 5 days | Day 396 | Day 398 | End-to-end validation | Test reports, bug fixes, performance tuning |
| **M40** | Production Deployment | 5 days | Day 399 | Day 400 | Go-live, training | Production release, documentation, handover |

### 3.3 Milestone Dependencies and Critical Path

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                           MILESTONE DEPENDENCY GRAPH                                                 │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘

Phase 3 Dependencies:
┌──────────────┐    ┌──────────────┐    ┌──────────────┐
│ B2B Portal   │    │ IoT Infra    │    │ PostgreSQL   │
│ Foundation   │    │ Foundation   │    │ Cluster      │
└──────┬───────┘    └──────┬───────┘    └──────┬───────┘
       │                   │                    │
       └───────────────────┼────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                     PHASE 4 MILESTONES                                               │
├─────────────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                                     │
│  ┌─────────┐                                                                                       │
│  │   M31   │───┐                                                                                   │
│  │ B2B Adv │   │                                                                                   │
│  └─────────┘   │    ┌─────────┐                                                                    │
│                ├───▶│   M32   │───┐                                                                │
│  ┌─────────┐   │    │ B2B Mob │   │                                                                │
│  │   M33   │───┘    └─────────┘   │    ┌─────────┐                                                │
│  │IoT Infra│                       ├───▶│   M35   │───┐                                            │
│  └────┬────┘                       │    │  Admin  │   │                                            │
│       │                            │    └─────────┘   │                                            │
│       │                            │                 │    ┌─────────┐    ┌─────────┐              │
│       │                            │                 └───▶│   M36   │───▶│   M37   │              │
│       │                            │                      │ERP Core │    │ERP Inv  │              │
│       │                            │                      └─────────┘    └────┬────┘              │
│       ▼                            │                                          │                   │
│  ┌─────────┐                       │                                          ▼                   │
│  │   M34   │───────────────────────┘                                   ┌─────────┐                │
│  │Farm Mgt │                                                            │   M38   │───┐            │
│  └─────────┘                                                            │ERP MRP  │   │            │
│                                                                         │& HR     │   │            │
│                                                                         └─────────┘   │            │
│                                                                                       │            │
│                              ┌────────────────────────────────────────────────────────┘            │
│                              │                                                                     │
│                              ▼                                                                     │
│                       ┌─────────┐    ┌─────────┐                                                   │
│                       │   M39   │───▶│   M40   │                                                   │
│                       │  Test   │    │ Deploy  │                                                   │
│                       └─────────┘    └─────────┘                                                   │
│                                                                                                     │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘

CRITICAL PATH: M33 → M34 → M35 → M36 → M37 → M38 → M39 → M40 (70 days)
FLOAT PATH:    M31 → M32 (can run parallel to M33-M34)
```

### 3.4 Milestone 31: B2B Advanced Features (Days 301-315)

#### 3.4.1 Objectives

| Objective ID | Description | Priority | Estimated Effort |
|--------------|-------------|----------|------------------|
| M31-OBJ-001 | Implement tier-based pricing engine | Critical | 40 hours |
| M31-OBJ-002 | Build volume discount calculation system | Critical | 32 hours |
| M31-OBJ-003 | Create B2B contract management module | High | 48 hours |
| M31-OBJ-004 | Develop wholesale ordering workflow | Critical | 56 hours |
| M31-OBJ-005 | Implement advanced catalog search | Medium | 24 hours |

#### 3.4.2 Technical Specifications

```python
# Tier-based Pricing Engine Pseudocode

class TierPricingEngine:
    """
    Calculates pricing based on customer tier, volume, and contract terms.
    """
    
    def calculate_price(self, product, customer, quantity, order_date=None):
        """
        Calculate final price for a product given customer and quantity.
        
        Algorithm:
        1. Get base price from product
        2. Apply customer tier discount
        3. Apply volume-based discount
        4. Apply contract-specific pricing if exists
        5. Apply promotional discounts
        """
        order_date = order_date or datetime.now()
        
        # Step 1: Base price
        base_price = product.list_price
        
        # Step 2: Customer tier discount
        tier_discount = self._get_tier_discount(customer, product)
        
        # Step 3: Volume discount
        volume_discount = self._get_volume_discount(product, quantity)
        
        # Step 4: Contract pricing
        contract_price = self._get_contract_price(customer, product, order_date)
        
        # Step 5: Calculate final
        if contract_price:
            # Contract pricing overrides all
            return {
                'base_price': base_price,
                'final_price': contract_price,
                'discount_type': 'contract',
                'savings': base_price - contract_price
            }
        
        # Apply tier + volume discounts
        tiered_price = base_price * (1 - tier_discount)
        volume_price = tiered_price * (1 - volume_discount)
        
        return {
            'base_price': base_price,
            'tier_discount': tier_discount,
            'volume_discount': volume_discount,
            'final_price': volume_price,
            'total_discount': base_price - volume_price,
            'discount_percentage': ((base_price - volume_price) / base_price) * 100
        }
    
    def _get_tier_discount(self, customer, product):
        """Get discount percentage based on customer tier."""
        if not customer.tier_id:
            return 0.0
        
        # Check for product-specific tier pricing
        product_tier_price = self.db.query("""
            SELECT discount_percentage 
            FROM product_tier_price 
            WHERE product_id = %s AND tier_id = %s AND active = TRUE
        """, (product.id, customer.tier_id.id))
        
        if product_tier_price:
            return product_tier_price[0] / 100
        
        # Return default tier discount
        return customer.tier_id.discount_percentage / 100
    
    def _get_volume_discount(self, product, quantity):
        """Get volume-based discount for quantity thresholds."""
        volume_price = self.db.query("""
            SELECT discount_percentage 
            FROM product_volume_price 
            WHERE product_id = %s 
              AND min_quantity <= %s 
              AND (max_quantity IS NULL OR max_quantity >= %s)
              AND active = TRUE
            ORDER BY min_quantity DESC
            LIMIT 1
        """, (product.id, quantity, quantity))
        
        return (volume_price[0] / 100) if volume_price else 0.0
    
    def _get_contract_price(self, customer, product, order_date):
        """Check for active contract with special pricing."""
        contract = self.db.query("""
            SELECT special_pricing 
            FROM b2b_contract 
            WHERE partner_id = %s 
              AND state = 'active'
              AND start_date <= %s 
              AND end_date >= %s
            LIMIT 1
        """, (customer.id, order_date, order_date))
        
        if contract and contract[0].get('product_prices'):
            return contract[0]['product_prices'].get(str(product.id))
        
        return None
```

#### 3.4.3 Deliverables Checklist

- [ ] Tier pricing engine module installed and configured
- [ ] Volume discount rules engine operational
- [ ] B2B contract management UI complete
- [ ] Wholesale ordering workflow tested
- [ ] Price calculation API endpoints documented
- [ ] Unit test coverage > 85%
- [ ] Integration tests passing

### 3.5 Milestone 32: B2B Mobile & Credit Management (Days 316-330)

#### 3.5.1 Objectives

| Objective ID | Description | Priority | Estimated Effort |
|--------------|-------------|----------|------------------|
| M32-OBJ-001 | Develop B2B Flutter mobile application | Critical | 80 hours |
| M32-OBJ-002 | Implement credit limit management | Critical | 40 hours |
| M32-OBJ-003 | Build approval workflow engine | High | 48 hours |
| M32-OBJ-004 | Create vendor management portal | Medium | 32 hours |
| M32-OBJ-005 | Implement B2B analytics dashboard | Medium | 24 hours |

#### 3.5.2 B2B Mobile App Architecture

```dart
// B2B Mobile App - Clean Architecture Overview
// lib/

// Data Layer
lib/data/
├── datasources/
│   ├── remote/
│   │   ├── api_client.dart              # Dio HTTP client
│   │   ├── auth_api.dart                # Authentication endpoints
│   │   ├── product_api.dart             # Product catalog API
│   │   ├── order_api.dart               # Order management API
│   │   └── payment_api.dart             # Payment processing
│   └── local/
│       ├── database/
│       │   ├── app_database.dart        # Drift/SQLite
│       │   ├── dao/
│       │   │   ├── product_dao.dart
│       │   │   ├── cart_dao.dart
│       │   │   └── order_dao.dart
│       │   └── tables/
│       │       ├── products.dart
│       │       ├── cart_items.dart
│       │       └── orders.dart
│       └── shared_prefs/
│           └── auth_prefs.dart
├── models/
│   ├── product_model.dart
│   ├── cart_model.dart
│   ├── order_model.dart
│   └── user_model.dart
└── repositories/
    ├── auth_repository_impl.dart
    ├── product_repository_impl.dart
    ├── cart_repository_impl.dart
    └── order_repository_impl.dart

// Domain Layer
lib/domain/
├── entities/
│   ├── product.dart
│   ├── cart.dart
│   ├── order.dart
│   └── user.dart
├── repositories/
│   ├── auth_repository.dart
│   ├── product_repository.dart
│   ├── cart_repository.dart
│   └── order_repository.dart
└── usecases/
    ├── auth/
    │   ├── login.dart
    │   ├── logout.dart
    │   └── refresh_token.dart
    ├── product/
    │   ├── get_products.dart
    │   ├── search_products.dart
    │   └── get_product_details.dart
    ├── cart/
    │   ├── add_to_cart.dart
    │   ├── update_cart_item.dart
    │   ├── remove_from_cart.dart
    │   └── get_cart.dart
    └── order/
        ├── create_order.dart
        ├── get_orders.dart
        ├── cancel_order.dart
        └── track_order.dart

// Presentation Layer
lib/presentation/
├── bloc/  # Or use Riverpod
│   ├── auth/
│   ├── products/
│   ├── cart/
│   └── orders/
├── pages/
│   ├── auth/
│   │   ├── login_page.dart
│   │   └── forgot_password_page.dart
│   ├── home/
│   │   ├── home_page.dart
│   │   └── dashboard_page.dart
│   ├── products/
│   │   ├── product_list_page.dart
│   │   ├── product_detail_page.dart
│   │   └── search_page.dart
│   ├── cart/
│   │   └── cart_page.dart
│   ├── orders/
│   │   ├── order_history_page.dart
│   │   ├── order_detail_page.dart
│   │   └── checkout_page.dart
│   └── profile/
│       └── profile_page.dart
├── widgets/
│   ├── common/
│   ├── product/
│   ├── cart/
│   └── order/
└── navigation/
    └── app_router.dart
```

#### 3.5.3 Credit Management Workflow

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                           CREDIT MANAGEMENT WORKFLOW                                                 │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘

Order Submitted (B2B Customer)
            │
            ▼
┌─────────────────────────────────────────────────────────────────────┐
│  1. Credit Check                                                    │
│     SELECT credit_limit, credit_used, credit_available             │
│     FROM account_credit_limit WHERE partner_id = ?                 │
│                                                                     │
│     IF order_total > credit_available:                              │
│        → Order Status = "PENDING_APPROVAL"                         │
│        → Send notification to approvers                            │
│     ELSE:                                                           │
│        → Reserve credit amount                                     │
│        → Order Status = "CONFIRMED"                                │
└─────────────────────────────────────────────────────────────────────┘
            │
            ▼ (If approval required)
┌─────────────────────────────────────────────────────────────────────┐
│  2. Approval Workflow                                               │
│     approval_chain = [sales_manager, finance_manager, director]    │
│                                                                     │
│     FOR approver IN approval_chain:                                │
│         IF order_total > approver.approval_limit:                  │
│             → Send approval request                                │
│             → Wait for response (timeout: 24h)                     │
│             → IF approved: continue to next approver               │
│             → IF rejected: Order Status = "REJECTED"               │
│             → IF timeout: Escalate to next level                   │
└─────────────────────────────────────────────────────────────────────┘
            │
            ▼
┌─────────────────────────────────────────────────────────────────────┐
│  3. Post-Approval Processing                                        │
│     IF all approvals received:                                      │
│        → Update credit_limit (temporarily increased if needed)     │
│        → Reserve credit amount                                     │
│        → Order Status = "CONFIRMED"                                │
│        → Trigger fulfillment workflow                              │
└─────────────────────────────────────────────────────────────────────┘
            │
            ▼
┌─────────────────────────────────────────────────────────────────────┐
│  4. Payment & Credit Release                                        │
│     WHEN payment_received:                                          │
│        → Update credit_used -= order_total                         │
│        → Release reserved credit                                   │
│        → Generate invoice                                          │
│                                                                     │
│     WHEN payment_overdue:                                           │
│        → Apply late payment fee                                    │
│        → Send reminder notification                                │
│        → IF > 30 days overdue: Block new orders                    │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.6 Milestone 33: IoT Infrastructure (Days 331-345)

#### 3.6.1 Objectives

| Objective ID | Description | Priority | Estimated Effort |
|--------------|-------------|----------|------------------|
| M33-OBJ-001 | Deploy MQTT broker cluster | Critical | 24 hours |
| M33-OBJ-002 | Setup TimescaleDB hypertables | Critical | 16 hours |
| M33-OBJ-003 | Build sensor data ingestion pipeline | Critical | 48 hours |
| M33-OBJ-004 | Implement real-time alert engine | High | 32 hours |
| M33-OBJ-005 | Create device management system | Medium | 24 hours |

#### 3.6.2 IoT Data Ingestion Service

```python
# iot_ingestion_service.py
# High-performance async sensor data ingestion

import asyncio
import aiomqtt
import asyncpg
import json
from datetime import datetime
from typing import Dict, Any
import logging

logger = logging.getLogger(__name__)

class IoTIngestionService:
    """
    High-throughput IoT sensor data ingestion service.
    Handles MQTT messages, validates data, and writes to TimescaleDB.
    """
    
    def __init__(self, config: Dict[str, Any]):
        self.mqtt_host = config['mqtt_host']
        self.mqtt_port = config['mqtt_port']
        self.db_dsn = config['database_dsn']
        self.batch_size = config.get('batch_size', 100)
        self.batch_timeout = config.get('batch_timeout', 5)  # seconds
        
        self.message_buffer = []
        self.buffer_lock = asyncio.Lock()
        self.db_pool = None
        
    async def start(self):
        """Start the ingestion service."""
        # Initialize database connection pool
        self.db_pool = await asyncpg.create_pool(
            dsn=self.db_dsn,
            min_size=5,
            max_size=20,
            command_timeout=60
        )
        
        # Start batch writer
        asyncio.create_task(self._batch_writer())
        
        # Connect to MQTT broker
        async with aiomqtt.Client(
            hostname=self.mqtt_host,
            port=self.mqtt_port,
            clean_session=True
        ) as client:
            # Subscribe to sensor topics
            await client.subscribe("farm/+/sensors/+/+")
            logger.info(f"Subscribed to farm sensor topics")
            
            async for message in client.messages:
                await self._handle_message(message)
    
    async def _handle_message(self, message: aiomqtt.Message):
        """Process incoming MQTT message."""
        try:
            # Parse topic: farm/{location_id}/sensors/{sensor_id}/{reading_type}
            topic_parts = message.topic.value.split('/')
            if len(topic_parts) != 5:
                logger.warning(f"Invalid topic format: {message.topic}")
                return
            
            _, location_id, _, sensor_id, reading_type = topic_parts
            
            # Parse payload
            payload = json.loads(message.payload.decode())
            
            # Validate and transform
            reading = {
                'time': datetime.utcnow(),
                'sensor_id': int(sensor_id),
                'location_id': int(location_id),
                'reading_type': reading_type,
                'value': float(payload.get('value')),
                'unit': payload.get('unit'),
                'quality_score': payload.get('quality', 100),
                'metadata': json.dumps(payload.get('metadata', {}))
            }
            
            # Add to buffer
            async with self.buffer_lock:
                self.message_buffer.append(reading)
                
        except json.JSONDecodeError as e:
            logger.error(f"Invalid JSON payload: {e}")
        except Exception as e:
            logger.error(f"Error processing message: {e}")
    
    async def _batch_writer(self):
        """Periodically flush buffer to database."""
        while True:
            await asyncio.sleep(self.batch_timeout)
            
            async with self.buffer_lock:
                if len(self.message_buffer) >= self.batch_size:
                    batch = self.message_buffer[:self.batch_size]
                    self.message_buffer = self.message_buffer[self.batch_size:]
                elif self.message_buffer:
                    batch = self.message_buffer
                    self.message_buffer = []
                else:
                    continue
            
            if batch:
                await self._write_batch(batch)
    
    async def _write_batch(self, batch: list):
        """Write batch of readings to TimescaleDB."""
        query = """
            INSERT INTO iot_sensor_reading 
            (time, sensor_id, location_id, reading_type, value, unit, quality_score, metadata)
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
        """
        
        try:
            async with self.db_pool.acquire() as conn:
                await conn.executemany(query, [
                    (
                        r['time'], r['sensor_id'], r['location_id'], 
                        r['reading_type'], r['value'], r['unit'],
                        r['quality_score'], r['metadata']
                    )
                    for r in batch
                ])
            logger.debug(f"Written {len(batch)} readings to database")
            
        except asyncpg.exceptions.DatabaseError as e:
            logger.error(f"Database write error: {e}")
            # Implement retry logic or dead letter queue
            
    async def stop(self):
        """Graceful shutdown."""
        # Flush remaining buffer
        async with self.buffer_lock:
            if self.message_buffer:
                await self._write_batch(self.message_buffer)
                self.message_buffer = []
        
        if self.db_pool:
            await self.db_pool.close()


# Alert Rule Engine
class AlertRuleEngine:
    """
    Evaluates sensor readings against alert rules and triggers notifications.
    """
    
    def __init__(self, db_pool, notification_service):
        self.db_pool = db_pool
        self.notification = notification_service
        self.rules_cache = {}
        self.cache_ttl = 300  # 5 minutes
        
    async def evaluate_reading(self, reading: Dict[str, Any]):
        """Evaluate a sensor reading against applicable rules."""
        # Get rules for this sensor/reading type
        rules = await self._get_rules(
            reading['sensor_id'], 
            reading['reading_type']
        )
        
        for rule in rules:
            if self._check_condition(reading['value'], rule):
                await self._trigger_alert(reading, rule)
    
    def _check_condition(self, value: float, rule: Dict) -> bool:
        """Check if value violates rule condition."""
        operator = rule['operator']  # gt, lt, gte, lte, eq, between
        threshold = rule['threshold']
        
        if operator == 'gt':
            return value > threshold
        elif operator == 'lt':
            return value < threshold
        elif operator == 'gte':
            return value >= threshold
        elif operator == 'lte':
            return value <= threshold
        elif operator == 'between':
            return threshold[0] <= value <= threshold[1]
        
        return False
    
    async def _trigger_alert(self, reading: Dict, rule: Dict):
        """Send alert through configured channels."""
        alert = {
            'timestamp': reading['time'],
            'severity': rule['severity'],
            'sensor_id': reading['sensor_id'],
            'reading_type': reading['reading_type'],
            'value': reading['value'],
            'threshold': rule['threshold'],
            'message': rule['message_template'].format(
                value=reading['value'],
                threshold=rule['threshold']
            ),
            'channels': rule['notification_channels']
        }
        
        await self.notification.send(alert)
        
    async def _get_rules(self, sensor_id: int, reading_type: str):
        """Get alert rules from cache or database."""
        cache_key = f"{sensor_id}:{reading_type}"
        
        # Check cache
        if cache_key in self.rules_cache:
            cached = self.rules_cache[cache_key]
            if time.time() - cached['timestamp'] < self.cache_ttl:
                return cached['rules']
        
        # Fetch from database
        async with self.db_pool.acquire() as conn:
            rows = await conn.fetch("""
                SELECT * FROM iot_alert_rule 
                WHERE sensor_id = $1 
                  AND reading_type = $2 
                  AND active = TRUE
            """, sensor_id, reading_type)
        
        rules = [dict(row) for row in rows]
        self.rules_cache[cache_key] = {
            'rules': rules,
            'timestamp': time.time()
        }
        
        return rules
```

### 3.7 Milestone 34: Smart Farm Management (Days 346-360)

#### 3.7.1 Objectives

| Objective ID | Description | Priority | Estimated Effort |
|--------------|-------------|----------|------------------|
| M34-OBJ-001 | Implement herd management module | Critical | 64 hours |
| M34-OBJ-002 | Build health monitoring system | Critical | 48 hours |
| M34-OBJ-003 | Create veterinary workflow | High | 32 hours |
| M34-OBJ-004 | Develop production tracking | Critical | 40 hours |
| M34-OBJ-005 | Implement breeding management | High | 32 hours |

#### 3.7.2 Herd Management Data Model

```python
# Odoo Model Definitions for Herd Management
# smart_dairy_farm/models/farm_animal.py

from odoo import models, fields, api, _
from odoo.exceptions import ValidationError
from datetime import datetime, date

class FarmAnimal(models.Model):
    """
    Livestock/Herd Management Model
    Tracks complete lifecycle of farm animals from birth to disposal.
    """
    _name = 'farm.animal'
    _description = 'Farm Animal/Livestock'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'create_date desc'
    
    # Identification
    name = fields.Char(string='Animal Name', required=True, tracking=True)
    code = fields.Char(string='Animal ID', required=True, copy=False, 
                       readonly=True, default=lambda self: _('New'))
    rfid_tag = fields.Char(string='RFID Tag', help='Electronic identification tag')
    ear_tag = fields.Char(string='Ear Tag Number')
    
    # Classification
    species = fields.Selection([
        ('cattle', 'Cattle'),
        ('buffalo', 'Buffalo'),
        ('goat', 'Goat'),
        ('sheep', 'Sheep')
    ], string='Species', required=True, default='cattle')
    
    breed_id = fields.Many2one('farm.breed', string='Breed')
    gender = fields.Selection([
        ('male', 'Male'),
        ('female', 'Female')
    ], string='Gender', required=True)
    
    # Dates
    birth_date = fields.Date(string='Birth Date')
    age_days = fields.Integer(string='Age (Days)', compute='_compute_age', store=True)
    age_display = fields.Char(string='Age', compute='_compute_age_display')
    purchase_date = fields.Date(string='Purchase Date')
    
    # Physical Characteristics
    weight_birth = fields.Float(string='Birth Weight (kg)', digits=(8, 2))
    weight_current = fields.Float(string='Current Weight (kg)', digits=(8, 2))
    weight_weaning = fields.Float(string='Weaning Weight (kg)', digits=(8, 2))
    color = fields.Char(string='Color/Markings')
    identifying_marks = fields.Text(string='Identifying Marks')
    
    # Relationships
    mother_id = fields.Many2one('farm.animal', string='Mother', 
                                domain=[('gender', '=', 'female')])
    father_id = fields.Many2one('farm.animal', string='Father',
                                domain=[('gender', '=', 'male')])
    offspring_ids = fields.One2many('farm.animal', 'mother_id', string='Offspring')
    offspring_count = fields.Integer(string='Number of Offspring', 
                                     compute='_compute_offspring')
    
    # Location and Status
    location_id = fields.Many2one('farm.location', string='Current Location',
                                  tracking=True)
    status = fields.Selection([
        ('active', 'Active'),
        ('breeding', 'Breeding'),
        ('quarantine', 'Quarantine'),
        ('sold', 'Sold'),
        ('deceased', 'Deceased'),
        ('retired', 'Retired')
    ], string='Status', default='active', tracking=True)
    
    state = fields.Selection([
        ('healthy', 'Healthy'),
        ('sick', 'Sick'),
        ('treatment', 'Under Treatment'),
        ('recovering', 'Recovering')
    ], string='Health State', default='healthy', tracking=True)
    
    # Production (for dairy animals)
    is_dairy = fields.Boolean(string='Dairy Animal', compute='_compute_is_dairy', store=True)
    lactation_number = fields.Integer(string='Lactation Number', default=0)
    last_calving_date = fields.Date(string='Last Calving Date')
    current_milk_yield = fields.Float(string='Current Daily Yield (L)', 
                                      compute='_compute_milk_yield')
    
    # Financial
    purchase_price = fields.Monetary(string='Purchase Price')
    current_value = fields.Monetary(string='Current Book Value', 
                                    compute='_compute_current_value')
    currency_id = fields.Many2one('res.currency', related='company_id.currency_id')
    supplier_id = fields.Many2one('res.partner', string='Supplier')
    
    # Metadata
    image = fields.Image(string='Photo', max_width=1024, max_height=1024)
    company_id = fields.Many2one('res.company', string='Company', 
                                 default=lambda self: self.env.company)
    
    # Related Records
    health_record_ids = fields.One2many('farm.health.record', 'animal_id', 
                                        string='Health Records')
    health_record_count = fields.Integer(string='Health Records', 
                                         compute='_compute_health_count')
    milk_production_ids = fields.One2many('farm.milk.production', 'animal_id',
                                          string='Milk Production Records')
    breeding_ids = fields.One2many('farm.breeding', 'female_id', string='Breeding History')
    
    # Smart Tags
    tag_ids = fields.Many2many('farm.animal.tag', string='Tags')
    
    @api.model
    def create(self, vals):
        """Generate unique animal code on creation."""
        if vals.get('code', _('New')) == _('New'):
            vals['code'] = self.env['ir.sequence'].next_by_code('farm.animal') or _('New')
        return super(FarmAnimal, self).create(vals)
    
    @api.depends('birth_date')
    def _compute_age(self):
        """Calculate age in days."""
        for animal in self:
            if animal.birth_date:
                animal.age_days = (fields.Date.today() - animal.birth_date).days
            else:
                animal.age_days = 0
    
    @api.depends('age_days')
    def _compute_age_display(self):
        """Format age for display (e.g., '2 years, 3 months')."""
        for animal in self:
            if animal.age_days:
                years = animal.age_days // 365
                months = (animal.age_days % 365) // 30
                animal.age_display = f"{years}y {months}m" if years > 0 else f"{months}m"
            else:
                animal.age_display = "Unknown"
    
    @api.depends('offspring_ids')
    def _compute_offspring(self):
        """Count number of offspring."""
        for animal in self:
            animal.offspring_count = len(animal.offspring_ids)
    
    @api.depends('species')
    def _compute_is_dairy(self):
        """Determine if animal is used for dairy production."""
        dairy_species = ['cattle', 'buffalo']
        for animal in self:
            animal.is_dairy = animal.species in dairy_species
    
    def action_view_health_records(self):
        """Open health records for this animal."""
        return {
            'name': _('Health Records'),
            'type': 'ir.actions.act_window',
            'res_model': 'farm.health.record',
            'view_mode': 'tree,form',
            'domain': [('animal_id', '=', self.id)],
            'context': {'default_animal_id': self.id}
        }
    
    def action_record_milking(self):
        """Quick action to record milking session."""
        return {
            'name': _('Record Milking'),
            'type': 'ir.actions.act_window',
            'res_model': 'farm.milk.production',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_animal_id': self.id}
        }


class FarmHealthRecord(models.Model):
    """
    Animal Health Record
    Tracks all health-related events: checkups, vaccinations, treatments.
    """
    _name = 'farm.health.record'
    _description = 'Animal Health Record'
    _order = 'record_date desc'
    
    name = fields.Char(string='Reference', required=True, copy=False,
                       default=lambda self: _('New'))
    animal_id = fields.Many2one('farm.animal', string='Animal', required=True)
    record_type = fields.Selection([
        ('checkup', 'Routine Checkup'),
        ('vaccination', 'Vaccination'),
        ('treatment', 'Treatment'),
        ('surgery', 'Surgery'),
        ('emergency', 'Emergency'),
        ('deworming', 'Deworming'),
        ('hoof_care', 'Hoof Care')
    ], string='Record Type', required=True)
    
    record_date = fields.Date(string='Date', required=True, default=fields.Date.today)
    veterinarian_id = fields.Many2one('res.partner', string='Veterinarian',
                                      domain=[('is_vet', '=', True)])
    
    diagnosis = fields.Text(string='Diagnosis')
    symptoms = fields.Text(string='Symptoms Observed')
    treatment = fields.Text(string='Treatment Plan')
    medications = fields.One2many('farm.health.medication', 'record_id', 
                                  string='Medications')
    
    follow_up_date = fields.Date(string='Follow-up Date')
    cost = fields.Monetary(string='Treatment Cost')
    currency_id = fields.Many2one('res.currency', related='company_id.currency_id')
    
    notes = fields.Text(string='Additional Notes')
    state = fields.Selection([
        ('open', 'Open'),
        ('in_progress', 'In Progress'),
        ('resolved', 'Resolved'),
        ('chronic', 'Chronic Condition')
    ], string='Status', default='open')
    
    attachment_ids = fields.Many2many('ir.attachment', string='Attachments')
    company_id = fields.Many2one('res.company', related='animal_id.company_id')


class FarmMilkProduction(models.Model):
    """
    Daily Milk Production Record
    Tracks milk yield per animal per milking session.
    """
    _name = 'farm.milk.production'
    _description = 'Milk Production Record'
    _order = 'milking_date desc, milking_session'
    
    animal_id = fields.Many2one('farm.animal', string='Animal', required=True,
                                domain=[('is_dairy', '=', True)])
    milking_date = fields.Date(string='Date', required=True, default=fields.Date.today)
    milking_session = fields.Selection([
        ('morning', 'Morning'),
        ('afternoon', 'Afternoon'),
        ('evening', 'Evening')
    ], string='Session', required=True)
    
    quantity_liters = fields.Float(string='Quantity (Liters)', required=True, digits=(6, 2))
    fat_percentage = fields.Float(string='Fat %', digits=(4, 2))
    snf_percentage = fields.Float(string='SNF %', digits=(4, 2))
    protein_percentage = fields.Float(string='Protein %', digits=(4, 2))
    
    temperature = fields.Float(string='Temperature (°C)', digits=(4, 1))
    quality_grade = fields.Selection([
        ('a', 'Grade A'),
        ('b', 'Grade B'),
        ('c', 'Grade C'),
        ('rejected', 'Rejected')
    ], string='Quality Grade')
    
    equipment_id = fields.Many2one('farm.equipment', string='Milking Equipment')
    operator_id = fields.Many2one('res.users', string='Operator',
                                  default=lambda self: self.env.user)
    location_id = fields.Many2one('farm.location', string='Milking Location')
    
    company_id = fields.Many2one('res.company', related='animal_id.company_id')
```

### 3.8 Milestone 35: Admin Portal (Days 361-375)

#### 3.8.1 Objectives

| Objective ID | Description | Priority | Estimated Effort |
|--------------|-------------|----------|------------------|
| M35-OBJ-001 | Build user management console | Critical | 40 hours |
| M35-OBJ-002 | Implement RBAC system | Critical | 48 hours |
| M35-OBJ-003 | Create system configuration UI | High | 32 hours |
| M35-OBJ-004 | Develop monitoring dashboard | High | 24 hours |
| M35-OBJ-005 | Build content management system | Medium | 16 hours |

#### 3.8.2 RBAC Implementation

```python
# Odoo RBAC Model Implementation
# smart_dairy_admin/models/res_role.py

from odoo import models, fields, api, _
from odoo.exceptions import AccessError

class ResRole(models.Model):
    """
    Role-Based Access Control Model
    Extends Odoo's groups with hierarchical roles and permissions.
    """
    _name = 'res.role'
    _description = 'User Role'
    _inherit = ['mail.thread']
    
    name = fields.Char(string='Role Name', required=True, translate=True)
    code = fields.Char(string='Role Code', required=True, copy=False, unique=True)
    description = fields.Text(string='Description')
    
    # Hierarchy
    parent_id = fields.Many2one('res.role', string='Parent Role')
    child_ids = fields.One2many('res.role', 'parent_id', string='Child Roles')
    
    # Permissions
    permission_ids = fields.One2many('res.role.permission', 'role_id', 
                                     string='Permissions')
    group_ids = fields.Many2many('res.groups', string='Linked Odoo Groups')
    
    # Users
    user_ids = fields.Many2many('res.users', string='Assigned Users')
    user_count = fields.Integer(string='User Count', compute='_compute_user_count')
    
    # Settings
    is_system_role = fields.Boolean(string='System Role', default=False,
                                    help='Cannot be deleted or modified')
    active = fields.Boolean(string='Active', default=True)
    company_id = fields.Many2one('res.company', string='Company')
    
    @api.depends('user_ids')
    def _compute_user_count(self):
        for role in self:
            role.user_count = len(role.user_ids)
    
    def action_assign_users(self):
        """Open wizard to assign users to this role."""
        return {
            'name': _('Assign Users'),
            'type': 'ir.actions.act_window',
            'res_model': 'res.role.assign.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_role_id': self.id}
        }


class ResRolePermission(models.Model):
    """
    Granular Permission Definition
    Defines access rights at model/field level.
    """
    _name = 'res.role.permission'
    _description = 'Role Permission'
    
    role_id = fields.Many2one('res.role', string='Role', required=True, ondelete='cascade')
    
    # Target
    model_id = fields.Many2one('ir.model', string='Model', required=True)
    model_name = fields.Char(related='model_id.model', string='Model Name', store=True)
    
    # Access Rights
    perm_read = fields.Boolean(string='Read', default=False)
    perm_write = fields.Boolean(string='Write', default=False)
    perm_create = fields.Boolean(string='Create', default=False)
    perm_unlink = fields.Boolean(string='Delete', default=False)
    
    # Record Rules
    domain_filter = fields.Char(string='Domain Filter',
                                help='Domain to restrict accessible records')
    
    # Field-level Permissions
    field_permission_ids = fields.One2many('res.role.field.permission', 
                                           'permission_id', 
                                           string='Field Permissions')
    
    # Scope
    company_scope = fields.Selection([
        ('all', 'All Companies'),
        ('current', 'Current Company Only'),
        ('children', 'Current + Child Companies')
    ], string='Company Scope', default='current')


class ResRoleFieldPermission(models.Model):
    """
    Field-level permission control
    """
    _name = 'res.role.field.permission'
    _description = 'Field Permission'
    
    permission_id = fields.Many2one('res.role.permission', required=True, ondelete='cascade')
    field_id = fields.Many2one('ir.model.fields', string='Field', required=True)
    access_type = fields.Selection([
        ('visible', 'Visible'),
        ('readonly', 'Read Only'),
        ('hidden', 'Hidden'),
        ('required', 'Required')
    ], string='Access Type', default='visible')


# Permission Checking Decorator
def require_permission(model, perm_type='read'):
    """
    Decorator to check user permissions before executing method.
    
    Usage:
        @require_permission('sale.order', 'write')
        def confirm_order(self):
            ...
    """
    def decorator(func):
        def wrapper(self, *args, **kwargs):
            user = self.env.user
            if not user.has_permission(model, perm_type):
                raise AccessError(_(
                    "You don't have {permission} permission on {model}"
                ).format(permission=perm_type, model=model))
            return func(self, *args, **kwargs)
        return wrapper
    return decorator
```

### 3.9 Milestone 36-38: ERP System Implementation

#### 3.9.1 ERP Module Breakdown

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                         ERP MODULE ARCHITECTURE                                                      │
├─────────────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                                     │
│  ┌─────────────────────────────────────────────────────────────────────────────────────────────┐   │
│  │                              ODOO 19 CE FOUNDATION                                           │   │
│  │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐                          │   │
│  │  │   BASE   │ │   MAIL   │ │  CALENDAR│ │  CONTACTS│ │  DISCUSS │                          │   │
│  │  └────┬─────┘ └────┬─────┘ └────┬─────┘ └────┬─────┘ └────┬─────┘                          │   │
│  │       └─────────────┴─────────────┴─────────────┴─────────────┘                              │   │
│  └─────────────────────────────────────────────────────────────────────────────────────────────┘   │
│                                              │                                                      │
│                    ┌─────────────────────────┼─────────────────────────┐                           │
│                    │                         │                         │                           │
│                    ▼                         ▼                         ▼                           │
│           ┌────────────────┐      ┌────────────────┐      ┌────────────────┐                      │
│           │   ACCOUNTING   │      │   INVENTORY    │      │ MANUFACTURING  │                      │
│           │    MODULE      │      │    MODULE      │      │    (MRP)       │                      │
│           ├────────────────┤      ├────────────────┤      ├────────────────┤                      │
│           │• General Ledger│      │• Multi-warehouse│     │• BOM Management│                      │
│           │• Accounts Rec. │      │• Lot Tracking  │      │• Production    │                      │
│           │• Accounts Pay. │      │• Expiry Dates  │      │  Planning      │                      │
│           │• Bank Recon.   │      │• Serial Numbers│      │• Work Centers  │                      │
│           │• Bangladesh Tax│      │• Barcodes      │      │• Quality Ctrl  │                      │
│           │• Financial Rpt │      │• Replenishment │      │• Cost Analysis │                      │
│           └───────┬────────┘      └───────┬────────┘      └───────┬────────┘                      │
│                   │                       │                       │                               │
│           ┌───────┴────────┐      ┌───────┴────────┐      ┌───────┴────────┐                      │
│           │ PURCHASE       │      │      SALES     │      │      HR        │                      │
│           │ MODULE         │      │     MODULE     │      │    MODULE      │                      │
│           ├────────────────┤      ├────────────────┤      ├────────────────┤                      │
│           │• RFQ           │      │• Quotations    │      │• Employees     │                      │
│           │• Purchase Order│      │• Sales Orders  │      │• Attendance    │                      │
│           │• Vendor Mgt    │      │• Invoicing     │      │• Leave Mgt     │                      │
│           │• Price Lists   │      │• Customer Portal│     │• Payroll (BD)  │                      │
│           │• Receipts      │      │• Commission    │      │• Appraisals    │                      │
│           └────────────────┘      └────────────────┘      └────────────────┘                      │
│                                                                                                     │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘
```

#### 3.9.2 Bangladesh Accounting Localization

```python
# Bangladesh Accounting Localization
# l10n_bd/models/account_chart_template.py

from odoo import models, fields, api, _

class AccountChartTemplate(models.Model):
    _inherit = 'account.chart.template'
    
    def _prepare_all_journals(self, acc_template_ref, company, journals_dict=None):
        """Add Bangladesh-specific journals."""
        journals = super()._prepare_all_journals(acc_template_ref, company, journals_dict)
        
        # Add VAT Journal
        journals.append({
            'name': _('VAT Transactions'),
            'code': 'VAT',
            'type': 'general',
            'favorite': True,
            'sequence': 10,
        })
        
        # Add Import Journal for LC transactions
        journals.append({
            'name': _('Import/LC'),
            'code': 'IMP',
            'type': 'general',
            'favorite': True,
            'sequence': 11,
        })
        
        return journals


class AccountTax(models.Model):
    _inherit = 'account.tax'
    
    # Bangladesh Tax-specific fields
    bd_vat_category = fields.Selection([
        ('standard', 'Standard Rate (15%)'),
        ('reduced', 'Reduced Rate (5%)'),
        ('zero', 'Zero Rate (0%)'),
        ('exempt', 'Exempt'),
        ('truncated', 'Truncated Base'),
    ], string='Bangladesh VAT Category')
    
    bd_vat_type = fields.Selection([
        ('output', 'Output VAT (Sales)'),
        ('input', 'Input VAT (Purchase)'),
        ('withholding', 'Withholding VAT'),
        ('supplementary', 'Supplementary Duty'),
    ], string='VAT Type')
    
    nbr_report_code = fields.Char(string='NBR Report Code',
                                   help='Code for NBR VAT return filing')


class AccountMove(models.Model):
    _inherit = 'account.move'
    
    # Bangladesh-specific fields
    bd_vat_challan_no = fields.Char(string='VAT Challan No.')
    bd_vat_challan_date = fields.Date(string='Challan Date')
    bd_bin_no = fields.Char(string='BIN Number',
                            related='partner_id.bd_bin_no', store=True)
    
    # Mushak 6.3 (VAT Invoice) fields
    mushak_6_3_printed = fields.Boolean(string='Mushak 6.3 Printed')
    mushak_6_3_number = fields.Char(string='Mushak 6.3 Number')
    
    def action_print_mushak_6_3(self):
        """Generate Bangladesh VAT Invoice (Mushak 6.3)."""
        self.ensure_one()
        if self.state != 'posted':
            raise UserError(_('Only posted invoices can print Mushak 6.3'))
        
        self.mushak_6_3_printed = True
        self.mushak_6_3_number = self.env['ir.sequence'].next_by_code('mushak.6.3')
        
        return self.env.ref('l10n_bd.action_report_mushak_6_3').report_action(self)
```

#### 3.9.3 Payroll Bangladesh Localization

```python
# Bangladesh Payroll Structure
# l10n_bd_payroll/models/hr_payslip.py

from odoo import models, fields, api, _
from odoo.tools import float_round

class HrPayslip(models.Model):
    _inherit = 'hr.payslip'
    
    # Bangladesh-specific fields
    working_days = fields.Float(string='Working Days', default=30)
    days_present = fields.Float(string='Days Present')
    days_absent = fields.Float(string='Days Absent')
    days_leave = fields.Float(string='Leave Days')
    overtime_hours = fields.Float(string='Overtime Hours')
    
    # Calculated fields
    basic_salary = fields.Monetary(string='Basic Salary', compute='_compute_salary_components')
    house_rent = fields.Monetary(string='House Rent Allowance', compute='_compute_salary_components')
    medical_allowance = fields.Monetary(string='Medical Allowance', compute='_compute_salary_components')
    conveyance = fields.Monetary(string='Conveyance', compute='_compute_salary_components')
    
    # Deductions
    pf_contribution = fields.Monetary(string='PF Contribution (Employee)', 
                                      compute='_compute_deductions')
    pf_employer_contribution = fields.Monetary(string='PF Contribution (Employer)',
                                                compute='_compute_deductions')
    income_tax = fields.Monetary(string='Income Tax', compute='_compute_deductions')
    advance_deduction = fields.Monetary(string='Advance Deduction')
    loan_deduction = fields.Monetary(string='Loan Deduction')
    
    # Net calculations
    gross_salary = fields.Monetary(string='Gross Salary', compute='_compute_totals')
    total_deduction = fields.Monetary(string='Total Deduction', compute='_compute_totals')
    net_salary = fields.Monetary(string='Net Salary', compute='_compute_totals')
    
    @api.depends('contract_id', 'working_days', 'days_present')
    def _compute_salary_components(self):
        """Calculate salary components based on Bangladesh labor law."""
        for payslip in self:
            if not payslip.contract_id:
                continue
                
            wage = payslip.contract_id.wage
            
            # Standard Bangladesh salary structure
            payslip.basic_salary = wage * 0.50  # 50% of gross
            payslip.house_rent = wage * 0.30    # 30% of gross
            payslip.medical_allowance = wage * 0.10  # 10% of gross
            payslip.conveyance = wage * 0.10    # 10% of gross
    
    @api.depends('basic_salary', 'overtime_hours')
    def _compute_deductions(self):
        """Calculate deductions."""
        for payslip in self:
            # PF Contribution (typically 10% of basic for both employee and employer)
            payslip.pf_contribution = payslip.basic_salary * 0.10
            payslip.pf_employer_contribution = payslip.basic_salary * 0.10
            
            # Income Tax Calculation (Simplified Bangladesh Tax Slab)
            annual_income = payslip.basic_salary * 12
            if annual_income <= 300000:
                tax = 0
            elif annual_income <= 400000:
                tax = (annual_income - 300000) * 0.05
            elif annual_income <= 700000:
                tax = 5000 + (annual_income - 400000) * 0.10
            elif annual_income <= 1100000:
                tax = 35000 + (annual_income - 700000) * 0.15
            else:
                tax = 95000 + (annual_income - 1100000) * 0.20
            
            payslip.income_tax = tax / 12  # Monthly tax
    
    @api.depends('basic_salary', 'house_rent', 'medical_allowance', 'conveyance',
                 'pf_contribution', 'income_tax')
    def _compute_totals(self):
        """Calculate gross and net salary."""
        for payslip in self:
            payslip.gross_salary = (
                payslip.basic_salary + 
                payslip.house_rent + 
                payslip.medical_allowance + 
                payslip.conveyance
            )
            
            payslip.total_deduction = (
                payslip.pf_contribution +
                payslip.income_tax +
                payslip.advance_deduction +
                payslip.loan_deduction
            )
            
            payslip.net_salary = payslip.gross_salary - payslip.total_deduction
```

---

## 4. Team Structure & Developer Allocation

### 4.1 Development Team Structure

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                              PHASE 4 DEVELOPMENT TEAM                                                │
├─────────────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                                     │
│                                  ┌──────────────────────┐                                           │
│                                  │   Project Manager    │                                           │
│                                  │  (20% Allocation)    │                                           │
│                                  └──────────┬───────────┘                                           │
│                                             │                                                         │
│                    ┌────────────────────────┼────────────────────────┐                               │
│                    │                        │                        │                               │
│                    ▼                        ▼                        ▼                               │
│           ┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐                          │
│           │  SENIOR ODOO    │    │  FULL-STACK     │    │   MOBILE APP    │                          │
│           │   DEVELOPER     │    │   DEVELOPER     │    │   DEVELOPER     │                          │
│           │   (Dev 1)       │    │   (Dev 2)       │    │   (Dev 3)       │                          │
│           │                 │    │                 │    │                 │                          │
│           │ Primary: ERP    │    │ Primary: B2B    │    │ Primary: Flutter│                          │
│           │ Secondary: Farm │    │ Secondary: Admin│    │ Secondary: IoT  │                          │
│           │                 │    │                 │    │                 │                          │
│           │ Experience:     │    │ Experience:     │    │ Experience:     │                          │
│           │ • 6 years Odoo  │    │ • 5 years Web   │    │ • 4 years Mobile│                          │
│           │ • 3 years ERP   │    │ • 3 years Python│    │ • 3 years Flutter│                         │
│           │ • 2 years Dairy │    │ • 2 years React │    │ • 2 years IoT   │                          │
│           └─────────────────┘    └─────────────────┘    └─────────────────┘                          │
│                                                                                                     │
│  Supporting Roles (Part-time):                                                                      │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐                │
│  │ DevOps Engineer │  │  QA Engineer    │  │  UI/UX Designer │  │  Tech Lead      │                │
│  │  (50% time)     │  │  (40% time)     │  │  (30% time)     │  │  (Advisory)     │                │
│  └─────────────────┘  └─────────────────┘  └─────────────────┘  └─────────────────┘                │
│                                                                                                     │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘
```

### 4.2 Developer Profiles

#### 4.2.1 Senior Odoo Developer (Dev 1)

**Profile Summary:**

| Attribute | Details |
|-----------|---------|
| **Role** | Senior Odoo Developer / ERP Specialist |
| **Experience** | 6+ years Odoo development, 3+ years ERP implementation |
| **Primary Skills** | Python, Odoo Framework, PostgreSQL, ORM |
| **Domain Knowledge** | Dairy Industry, Manufacturing, Supply Chain |
| **Certifications** | Odoo Certified Professional, PostgreSQL Certified |
| **Daily Rate** | ৳18,000 |

**Technical Skills Matrix:**

| Skill | Proficiency | Years Experience | Relevance to Phase 4 |
|-------|-------------|------------------|---------------------|
| Odoo Framework | Expert | 6 | Critical - Core ERP development |
| Python | Expert | 8 | Critical - All backend development |
| PostgreSQL | Advanced | 6 | High - Database design |
| XML/XPath | Advanced | 5 | High - Odoo views and data |
| QWeb Templating | Advanced | 5 | Medium - Reports and views |
| OWL Framework | Intermediate | 2 | Medium - Frontend components |
| Manufacturing (MRP) | Advanced | 3 | Critical - MRP module |
| Accounting | Advanced | 4 | Critical - BD localization |
| IoT Integration | Intermediate | 1 | Medium - Sensor integration |

**Responsibilities:**

1. **ERP Module Development**
   - Accounting module with Bangladesh localization
   - Manufacturing (MRP) with dairy-specific BOMs
   - Inventory management with multi-warehouse support
   - HR & Payroll with Bangladesh labor law compliance

2. **Smart Farm Management**
   - Herd management module design
   - Health monitoring system
   - Production tracking integration
   - Veterinary workflow implementation

3. **Database Design**
   - PostgreSQL schema design
   - Performance optimization
   - TimescaleDB integration for IoT data
   - Migration scripts

4. **Code Review & Quality**
   - Review all Odoo module code
   - Ensure coding standards compliance
   - Performance optimization review

#### 4.2.2 Full-Stack Developer (Dev 2)

**Profile Summary:**

| Attribute | Details |
|-----------|---------|
| **Role** | Full-Stack Developer / API Specialist |
| **Experience** | 5+ years web development, 3+ years API design |
| **Primary Skills** | Python, FastAPI, React.js, Node.js, PostgreSQL |
| **Domain Knowledge** | E-commerce, B2B Portals, Payment Systems |
| **Certifications** | AWS Certified Developer, MongoDB Certified |
| **Daily Rate** | ৳15,000 |

**Technical Skills Matrix:**

| Skill | Proficiency | Years Experience | Relevance to Phase 4 |
|-------|-------------|------------------|---------------------|
| Python/FastAPI | Expert | 4 | Critical - API development |
| React.js | Expert | 4 | Critical - B2B Portal frontend |
| Node.js | Advanced | 3 | Medium - Notification service |
| PostgreSQL | Advanced | 4 | High - Database queries |
| Redis | Advanced | 3 | High - Caching and queues |
| Docker/Kubernetes | Intermediate | 2 | Medium - Deployment |
| Payment Gateways | Advanced | 3 | Critical - bKash/Nagad |
| REST API Design | Expert | 5 | Critical - API architecture |

**Responsibilities:**

1. **B2B Portal Development**
   - Advanced wholesale ordering system
   - Tier pricing and volume discount engine
   - Credit management and approval workflows
   - B2B analytics dashboard

2. **Admin Portal**
   - User management console
   - RBAC implementation
   - System configuration UI
   - Monitoring dashboard

3. **API Development**
   - B2B API endpoints (FastAPI)
   - Payment gateway integrations
   - Third-party service integrations
   - Webhook handlers

4. **Integration Services**
   - bKash/Nagad payment integration
   - SMS gateway integration
   - Email service integration
   - NBR VAT reporting integration

#### 4.2.3 Mobile App Developer (Dev 3)

**Profile Summary:**

| Attribute | Details |
|-----------|---------|
| **Role** | Mobile Application Developer / IoT Specialist |
| **Experience** | 4+ years mobile development, 2+ years Flutter |
| **Primary Skills** | Flutter, Dart, Android, iOS, IoT Protocols |
| **Domain Knowledge** | Mobile Commerce, IoT Applications, Real-time Systems |
| **Certifications** | Flutter Certified Developer, AWS IoT Specialty |
| **Daily Rate** | ৳14,000 |

**Technical Skills Matrix:**

| Skill | Proficiency | Years Experience | Relevance to Phase 4 |
|-------|-------------|------------------|---------------------|
| Flutter/Dart | Expert | 3 | Critical - Mobile apps |
| Android Native | Advanced | 4 | Medium - Platform-specific |
| iOS Native | Intermediate | 2 | Medium - Platform-specific |
| MQTT/IoT Protocols | Advanced | 2 | Critical - Sensor integration |
| SQLite/Hive | Advanced | 3 | High - Local storage |
| Bluetooth/BLE | Intermediate | 2 | Medium - Wearable devices |
| Firebase | Advanced | 3 | High - Push notifications |
| Clean Architecture | Advanced | 2 | High - App architecture |

**Responsibilities:**

1. **B2B Mobile Application**
   - Flutter B2B customer app (Android/iOS)
   - Product catalog browsing
   - Order management
   - Payment processing

2. **Farm Operations Mobile App**
   - Herd management mobile interface
   - Health record entry
   - Milk production recording
   - Offline-first architecture

3. **IoT Integration**
   - MQTT client implementation
   - Sensor data visualization
   - Real-time alerts on mobile
   - Device management interface

4. **Mobile-specific Features**
   - Push notifications (FCM/APNS)
   - Barcode/QR scanning
   - Image capture for animal records
   - Biometric authentication

### 4.3 Daily Task Allocation (Sample Week)

#### 4.3.1 Week 1 (Days 301-307): M31 - B2B Advanced Features Kickoff

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                    WEEK 1 DAILY TASK ALLOCATION (Days 301-307)                                      │
├─────────────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                                     │
│ DAY 301 - MONDAY                                                                                    │
│ ┌─────────────────────────────────────────────────────────────────────────────────────────────────┐ │
│ │ Dev 1 (Odoo)                                         │ Dev 2 (Full-Stack)  │ Dev 3 (Mobile)     │ │
│ │ • Review Phase 3 B2B foundation code                 │ • Setup FastAPI     │ • Setup Flutter    │ │
│ │ • Design tier pricing database schema                │   project structure │   B2B project      │ │
│ │ • Create res.partner.tier model                      │ • Design B2B API    │ • Design app        │ │
│ │ Est: 8h │ Actual: ___                               │   endpoints (OpenAPI)│  architecture      │ │
│ │ Blockers: None                                       │ Est: 8h │ Actual: __ │ Est: 8h │ Actual: _│ │
│ │ Commits: smart_dairy_b2b: tier_pricing_model.py    │ Commits: b2b_api:   │ Commits: b2b_mobile:│ │
│ └─────────────────────────────────────────────────────────────────────────────────────────────────┘ │
│                                                                                                     │
│ DAY 302 - TUESDAY                                                                                   │
│ ┌─────────────────────────────────────────────────────────────────────────────────────────────────┐ │
│ │ Dev 1 (Odoo)                                         │ Dev 2 (Full-Stack)  │ Dev 3 (Mobile)     │ │
│ │ • Implement volume discount calculation              │ • Implement tier    │ • Create login &   │ │
│ │ • Create product.volume.price model                  │   pricing API       │   auth screens     │ │
│ │ • Unit tests for pricing engine                      │ • Credit check API  │ • API client setup │ │
│ │ Est: 8h │ Actual: ___                               │ Est: 8h │ Actual: __ │ Est: 8h │ Actual: _│ │
│ │ Commits: tier_pricing: engine, tests               │ Commits: api:       │ Commits: mobile:   │ │
│ │                                                      │   tier_pricing.py   │   auth, api_client │ │
│ └─────────────────────────────────────────────────────────────────────────────────────────────────┘ │
│                                                                                                     │
│ DAY 303 - WEDNESDAY                                                                                 │
│ ┌─────────────────────────────────────────────────────────────────────────────────────────────────┐ │
│ │ Dev 1 (Odoo)                                         │ Dev 2 (Full-Stack)  │ Dev 3 (Mobile)     │ │
│ │ • B2B contract management model                      │ • Contract API      │ • Product catalog  │ │
│ │ • Contract approval workflow                         │ • Wholesale order   │   UI components    │ │
│ │ • Integration with existing sale.order               │   workflow API      │ • Cart functionality│ │
│ │ Est: 8h │ Actual: ___                               │ Est: 8h │ Actual: __ │ Est: 8h │ Actual: _│ │
│ └─────────────────────────────────────────────────────────────────────────────────────────────────┘ │
│                                                                                                     │
│ DAY 304 - THURSDAY                                                                                  │
│ ┌─────────────────────────────────────────────────────────────────────────────────────────────────┐ │
│ │ Dev 1 (Odoo)                                         │ Dev 2 (Full-Stack)  │ Dev 3 (Mobile)     │ │
│ │ • Contract UI (backend views)                        │ • Order validation  │ • Checkout flow    │ │
│ │ • Special pricing JSON handling                      │ • Price calculation │ • Payment integration││
│ │ • Testing with sample data                           │   service           │ • Order history    │ │
│ │ Est: 8h │ Actual: ___                               │ Est: 8h │ Actual: __ │ Est: 8h │ Actual: _│ │
│ └─────────────────────────────────────────────────────────────────────────────────────────────────┘ │
│                                                                                                     │
│ DAY 305 - FRIDAY                                                                                    │
│ ┌─────────────────────────────────────────────────────────────────────────────────────────────────┐ │
│ │ Dev 1 (Odoo)                                         │ Dev 2 (Full-Stack)  │ Dev 3 (Mobile)     │ │
│ │ • Review & refactor pricing code                     │ • API documentation │ • UI polish        │ │
│ │ • Performance optimization                           │ • Error handling    │ • Testing on device│ │
│ │ • Code review for Dev 2's API work                   │ • Unit tests        │ • Bug fixes        │ │
│ │ Est: 8h │ Actual: ___                               │ Est: 8h │ Actual: __ │ Est: 8h │ Actual: _│ │
│ │ Code Review: b2b_api PR #142                         │ Code Review: odoo   │ Code Review:       │ │
│ │                                                      │   PR #89            │   mobile PR #56    │ │
│ └─────────────────────────────────────────────────────────────────────────────────────────────────┘ │
│                                                                                                     │
│ DAY 306 - SATURDAY (Half Day)                                                                       │
│ ┌─────────────────────────────────────────────────────────────────────────────────────────────────┐ │
│ │ Dev 1 (Odoo)                     │ Dev 2 (Full-Stack)            │ Dev 3 (Mobile)                │ │
│ │ • M31 documentation              │ • API integration tests       │ • Build preparation           │ │
│ │ • Handover notes                 │ • Postman collection          │ • TestFlight setup            │ │
│ │ Est: 4h                          │ Est: 4h                       │ Est: 4h                       │ │
│ └─────────────────────────────────────────────────────────────────────────────────────────────────┘ │
│                                                                                                     │
│ DAY 307 - SUNDAY (Planning Day)                                                                     │
│ ┌─────────────────────────────────────────────────────────────────────────────────────────────────┐ │
│ │ TEAM SYNC - 10:00 AM                                                                            │ │
│ │ • M31 progress review                                                                           │ │
│ │ • Blocker discussion                                                                            │ │
│ │ • M32 planning (B2B Mobile & Credit)                                                           │ │
│ │                                                                                                 │ │
│ │ Individual Planning:                                                                            │ │
│ │ • Task breakdown for next week                                                                  │ │
│ │ • Estimation refinement                                                                         │ │
│ │ • Technical design review                                                                       │ │
│ └─────────────────────────────────────────────────────────────────────────────────────────────────┘ │
│                                                                                                     │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘
```

#### 4.3.2 Responsibility Matrix

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                         RESPONSIBILITY MATRIX (RACI)                                                 │
├───────────┬─────────────────────────────────────────────────────────────────────────────────────────┤
│ Component │                   Developer Assignment                                                  │
├───────────┼─────────────────────────────────────────────────────────────────────────────────────────┤
│           │ Dev 1 (Odoo)    │ Dev 2 (Full-Stack) │ Dev 3 (Mobile) │ DevOps │ QA     │ Designer    │
├───────────┼─────────────────┼────────────────────┼────────────────┼────────┼────────┼─────────────┤
│ B2B Tier  │ R (Responsible) │ A (Accountable)    │ C (Consulted)  │ C      │ C      │ I (Informed)│
│ Pricing   │                 │                    │                │        │        │             │
├───────────┼─────────────────┼────────────────────┼────────────────┼────────┼────────┼─────────────┤
│ B2B Credit│ R               │ A                  │ C              │ C      │ C      │ I           │
│ Mgmt      │                 │                    │                │        │        │             │
├───────────┼─────────────────┼────────────────────┼────────────────┼────────┼────────┼─────────────┤
│ B2B Mobile│ C               │ A                  │ R              │ C      │ C      │ C           │
│ App       │                 │                    │                │        │        │             │
├───────────┼─────────────────┼────────────────────┼────────────────┼────────┼────────┼─────────────┤
│ IoT       │ C               │ A                  │ R              │ R      │ C      │ I           │
│ Ingestion │                 │                    │                │        │        │             │
├───────────┼─────────────────┼────────────────────┼────────────────┼────────┼────────┼─────────────┤
│ Farm Mgt  │ R               │ C                  │ C              │ C      │ C      │ C           │
│ Module    │                 │                    │                │        │        │             │
├───────────┼─────────────────┼────────────────────┼────────────────┼────────┼────────┼─────────────┤
│ Farm      │ C               │ C                  │ R              │ I      │ C      │ C           │
│ Mobile    │                 │                    │                │        │        │             │
├───────────┼─────────────────┼────────────────────┼────────────────┼────────┼────────┼─────────────┤
│ Admin     │ R               │ A                  │ I              │ C      │ C      │ C           │
│ Portal    │                 │                    │                │        │        │             │
├───────────┼─────────────────┼────────────────────┼────────────────┼────────┼────────┼─────────────┤
│ ERP       │ R               │ C                  │ I              │ C      │ C      │ I           │
│ Accounting│                 │                    │                │        │        │             │
├───────────┼─────────────────┼────────────────────┼────────────────┼────────┼────────┼─────────────┤
│ ERP       │ R               │ C                  │ I              │ C      │ C      │ I           │
│ Inventory │                 │                    │                │        │        │             │
├───────────┼─────────────────┼────────────────────┼────────────────┼────────┼────────┼─────────────┤
│ ERP MRP   │ R               │ C                  │ I              │ C      │ C      │ I           │
├───────────┼─────────────────┼────────────────────┼────────────────┼────────┼────────┼─────────────┤
│ ERP       │ R               │ C                  │ I              │ C      │ C      │ I           │
│ Payroll   │                 │                    │                │        │        │             │
├───────────┼─────────────────┼────────────────────┼────────────────┼────────┼────────┼─────────────┤
│ Payment   │ C               │ R                  │ C              │ C      │ C      │ I           │
│ Gateway   │                 │                    │                │        │        │             │
├───────────┼─────────────────┼────────────────────┼────────────────┼────────┼────────┼─────────────┤
│ CI/CD     │ C               │ C                  │ C              │ R      │ I      │ I           │
│ Pipeline  │                 │                    │                │        │        │             │
├───────────┼─────────────────┼────────────────────┼────────────────┼────────┼────────┼─────────────┤
│ Testing   │ C               │ C                  │ C              │ I      │ R      │ I           │
│ Strategy  │                 │                    │                │        │        │             │
└───────────┴─────────────────┴────────────────────┴────────────────┴────────┴────────┴─────────────┘

Legend: R = Responsible (does the work)
        A = Accountable (approves/signs off)
        C = Consulted (provides input)
        I = Informed (kept updated)
```

### 4.4 Development Workflow

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                         DEVELOPMENT WORKFLOW (Git Flow)                                              │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘

Feature Development Cycle (2-3 days per feature):

┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐
│  Create  │───▶│  Develop │───▶│  Local   │───▶│   Push   │───▶│  Create  │───▶│  Code    │
│  Branch  │    │  Feature │    │   Test   │    │  Branch  │    │    PR    │    │  Review  │
└──────────┘    └──────────┘    └──────────┘    └──────────┘    └──────────┘    └──────────┘
                                                                                      │
        ┌─────────────────────────────────────────────────────────────────────────────┘
        │
        ▼
┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐
│  Review  │───▶│  Deploy  │───▶│  QA Test │───▶│  Merge   │───▶│  Deploy  │
│  Pass?   │No  │  Fixes   │    │  Staging │    │  to Dev  │    │  to Prod │
└────┬─────┘    └──────────┘    └────┬─────┘    └──────────┘    └──────────┘
     │ Yes                             │ Pass?
     │                                 │
     └─────────────────────────────────┘ No
                                       ▼
                                ┌──────────┐
                                │  Create  │
                                │  Bug     │
                                │  Ticket  │
                                └──────────┘

Branch Strategy:

main (production)
  │
  ├── develop (integration branch)
  │     │
  │     ├── feature/M31-tier-pricing-engine
  │     ├── feature/M31-volume-discounts
  │     ├── feature/M32-b2b-mobile-auth
  │     ├── feature/M33-mqtt-ingestion
  │     └── ...
  │
  ├── hotfix/critical-payment-bug
  │
  └── release/v4.0.0 (staging)

Commit Message Convention:

type(scope): subject

[optional body]

[optional footer]

Types:
  feat:     New feature
  fix:      Bug fix
  docs:     Documentation
  style:    Formatting (no code change)
  refactor: Code restructuring
  perf:     Performance improvement
  test:     Adding tests
  chore:    Build/config changes

Examples:
  feat(b2b): implement tier pricing calculation
  fix(erp): correct VAT calculation for export orders
  docs(api): add OpenAPI spec for payment endpoints
  test(farm): add unit tests for herd management
```

---

## 5. Technology Stack Deep Dive

### 5.1 Odoo 19 CE Custom Modules

#### 5.1.1 Module Structure

```
smart_dairy/
├── smart_dairy_core/              # Base module with common functionality
│   ├── __init__.py
│   ├── __manifest__.py
│   ├── models/
│   │   ├── __init__.py
│   │   ├── res_company.py         # Company extensions
│   │   ├── res_partner.py         # Partner extensions
│   │   └── res_config_settings.py # Configuration
│   ├── data/
│   │   └── ir_sequence_data.xml   # Sequence definitions
│   └── security/
│       └── ir.model.access.csv
│
├── smart_dairy_b2b/               # B2B Marketplace
│   ├── __init__.py
│   ├── __manifest__.py
│   ├── models/
│   │   ├── __init__.py
│   │   ├── res_partner_tier.py
│   │   ├── product_volume_price.py
│   │   ├── b2b_contract.py
│   │   └── sale_order.py          # Extensions
│   ├── views/
│   │   ├── res_partner_tier_views.xml
│   │   ├── b2b_contract_views.xml
│   │   └── sale_order_views.xml
│   ├── report/
│   │   └── b2b_sales_report.xml
│   └── security/
│       ├── ir.model.access.csv
│       └── b2b_security.xml
│
├── smart_dairy_farm/              # Smart Farm Management
│   ├── __init__.py
│   ├── __manifest__.py
│   ├── models/
│   │   ├── __init__.py
│   │   ├── farm_animal.py
│   │   ├── farm_health_record.py
│   │   ├── farm_milk_production.py
│   │   ├── farm_breeding.py
│   │   └── farm_location.py
│   ├── views/
│   ├── report/
│   └── security/
│
├── smart_dairy_iot/               # IoT Integration
│   ├── __init__.py
│   ├── __manifest__.py
│   ├── models/
│   │   ├── iot_sensor.py
│   │   ├── iot_sensor_reading.py
│   │   ├── iot_alert_rule.py
│   │   └── iot_device.py
│   ├── controllers/
│   │   └── sensor_webhook.py
│   └── data/
│       └── alert_cron.xml
│
├── smart_dairy_admin/             # Admin Portal Extensions
│   ├── __init__.py
│   ├── __manifest__.py
│   ├── models/
│   │   ├── res_role.py
│   │   ├── res_role_permission.py
│   │   └── res_users.py
│   └── views/
│       ├── res_role_views.xml
│       └── admin_dashboard.xml
│
├── l10n_bd/                       # Bangladesh Localization
│   ├── __init__.py
│   ├── __manifest__.py
│   ├── data/
│   │   ├── account_chart_template_data.xml
│   │   ├── account_tax_data.xml
│   │   └── res_country_state_data.xml
│   ├── models/
│   │   ├── account_tax.py
│   │   ├── account_move.py
│   │   └── res_partner.py
│   └── report/
│       └── mushak_6_3_report.xml
│
└── l10n_bd_payroll/               # Bangladesh Payroll
    ├── __init__.py
    ├── __manifest__.py
    ├── models/
    │   ├── hr_employee.py
    │   ├── hr_payslip.py
    │   └── hr_contract.py
    └── data/
        ├── salary_rules.xml
        └── payroll_structure.xml
```

#### 5.1.2 Module Manifest Example

```python
# smart_dairy_b2b/__manifest__.py

{
    'name': 'Smart Dairy B2B Marketplace',
    'version': '4.0.0',
    'category': 'Sales/Smart Dairy',
    'summary': 'Advanced B2B wholesale ordering with tier pricing',
    'description': """
Smart Dairy B2B Marketplace
===========================

This module provides advanced B2B functionality for the Smart Dairy platform:

* Customer tier management with automatic classification
* Volume-based and tier-based pricing engines
* B2B contract management with custom pricing
* Credit limit management with approval workflows
* Wholesale ordering with minimum order quantities
* Integration with accounting for credit tracking

Technical Features:
-------------------
* Optimized pricing calculation with caching
* Asynchronous credit approval workflows
* REST API endpoints for mobile integration
* Real-time inventory availability checks

Authors:
--------
* Smart Dairy IT Team

Maintainers:
------------
* Smart Dairy IT Directorate
    """,
    'author': 'Smart Dairy',
    'website': 'https://smartdairy.com.bd',
    'license': 'LGPL-3',
    'depends': [
        'base',
        'sale',
        'sale_management',
        'account',
        'stock',
        'smart_dairy_core',
    ],
    'data': [
        # Security
        'security/b2b_security.xml',
        'security/ir.model.access.csv',
        
        # Data
        'data/res_partner_tier_data.xml',
        'data/b2b_sequence_data.xml',
        
        # Views
        'views/res_partner_views.xml',
        'views/res_partner_tier_views.xml',
        'views/product_views.xml',
        'views/product_volume_price_views.xml',
        'views/b2b_contract_views.xml',
        'views/sale_order_views.xml',
        'views/b2b_menus.xml',
        
        # Reports
        'report/b2b_sales_report.xml',
        'report/b2b_invoice_report.xml',
    ],
    'demo': [
        'demo/b2b_demo_data.xml',
    ],
    'installable': True,
    'application': True,
    'auto_install': False,
    'price': 0,
    'currency': 'BDT',
    'support': 'it@smartdairy.com.bd',
}
```

### 5.2 Flutter Mobile Development

#### 5.2.1 Project Structure

```
smart_dairy_mobile/
├── android/                       # Android-specific config
├── ios/                          # iOS-specific config
├── lib/
│   ├── main.dart                 # Entry point
│   ├── app.dart                  # App widget with providers
│   │
│   ├── core/                     # Core functionality
│   │   ├── constants/
│   │   │   ├── api_constants.dart
│   │   │   ├── app_constants.dart
│   │   │   └── storage_keys.dart
│   │   ├── errors/
│   │   │   ├── exceptions.dart
│   │   │   └── failures.dart
│   │   ├── usecases/
│   │   │   └── usecase.dart
│   │   ├── utils/
│   │   │   ├── date_utils.dart
│   │   │   ├── number_utils.dart
│   │   │   └── validation_utils.dart
│   │   └── theme/
│   │       ├── app_colors.dart
│   │       ├── app_theme.dart
│   │       └── app_typography.dart
│   │
│   ├── features/                 # Feature modules
│   │   ├── auth/
│   │   │   ├── data/
│   │   │   │   ├── datasources/
│   │   │   │   │   ├── auth_local_datasource.dart
│   │   │   │   │   └── auth_remote_datasource.dart
│   │   │   │   ├── models/
│   │   │   │   │   ├── user_model.dart
│   │   │   │   │   └── token_model.dart
│   │   │   │   └── repositories/
│   │   │   │       └── auth_repository_impl.dart
│   │   │   ├── domain/
│   │   │   │   ├── entities/
│   │   │   │   │   └── user.dart
│   │   │   │   ├── repositories/
│   │   │   │   │   └── auth_repository.dart
│   │   │   │   └── usecases/
│   │   │   │       ├── login.dart
│   │   │   │       ├── logout.dart
│   │   │   │       └── refresh_token.dart
│   │   │   └── presentation/
│   │   │       ├── bloc/
│   │   │       │   ├── auth_bloc.dart
│   │   │       │   ├── auth_event.dart
│   │   │       │   └── auth_state.dart
│   │   │       ├── pages/
│   │   │       │   ├── login_page.dart
│   │   │       │   └── forgot_password_page.dart
│   │   │       └── widgets/
│   │   │           ├── login_form.dart
│   │   │           └── auth_button.dart
│   │   │
│   │   ├── products/
│   │   │   ├── data/
│   │   │   ├── domain/
│   │   │   └── presentation/
│   │   │
│   │   ├── cart/
│   │   │   ├── data/
│   │   │   ├── domain/
│   │   │   └── presentation/
│   │   │
│   │   ├── orders/
│   │   │   ├── data/
│   │   │   ├── domain/
│   │   │   └── presentation/
│   │   │
│   │   └── profile/
│   │       ├── data/
│   │       ├── domain/
│   │       └── presentation/
│   │
│   ├── navigation/               # App navigation
│   │   ├── app_router.dart
│   │   └── route_names.dart
│   │
│   └── services/                 # Shared services
│       ├── api_client.dart
│       ├── connectivity_service.dart
│       ├── local_storage_service.dart
│       ├── notification_service.dart
│       └── logging_service.dart
│
├── test/
│   ├── unit/
│   ├── widget/
│   └── integration/
│
├── pubspec.yaml
└── analysis_options.yaml
```

#### 5.2.2 State Management with Riverpod

```dart
// lib/features/products/presentation/providers/products_provider.dart

import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:smart_dairy_mobile/features/products/domain/entities/product.dart';
import 'package:smart_dairy_mobile/features/products/domain/usecases/get_products.dart';
import 'package:smart_dairy_mobile/features/products/presentation/providers/products_state.dart';

// Provider for GetProducts use case
final getProductsUseCaseProvider = Provider<GetProducts>((ref) {
  final repository = ref.watch(productRepositoryProvider);
  return GetProducts(repository);
});

// StateNotifier for Products
class ProductsNotifier extends StateNotifier<ProductsState> {
  final GetProducts _getProducts;
  
  ProductsNotifier(this._getProducts) : super(const ProductsState.initial());
  
  Future<void> loadProducts({
    String? categoryId,
    String? searchQuery,
    int page = 1,
    int pageSize = 20,
  }) async {
    state = const ProductsState.loading();
    
    final result = await _getProducts(
      GetProductsParams(
        categoryId: categoryId,
        searchQuery: searchQuery,
        page: page,
        pageSize: pageSize,
      ),
    );
    
    result.fold(
      (failure) => state = ProductsState.error(failure.message),
      (products) => state = ProductsState.loaded(
        products: products,
        hasMore: products.length == pageSize,
        currentPage: page,
      ),
    );
  }
  
  Future<void> loadMore() async {
    final currentState = state;
    if (currentState is! ProductsLoaded || !currentState.hasMore) return;
    
    final result = await _getProducts(
      GetProductsParams(page: currentState.currentPage + 1),
    );
    
    result.fold(
      (failure) => state = ProductsState.error(failure.message),
      (newProducts) => state = ProductsState.loaded(
        products: [...currentState.products, ...newProducts],
        hasMore: newProducts.length == 20,
        currentPage: currentState.currentPage + 1,
      ),
    );
  }
}

// Riverpod provider
final productsNotifierProvider = StateNotifierProvider<ProductsNotifier, ProductsState>((ref) {
  final getProducts = ref.watch(getProductsUseCaseProvider);
  return ProductsNotifier(getProducts);
});

// State classes
@freezed
class ProductsState with _$ProductsState {
  const factory ProductsState.initial() = ProductsInitial;
  const factory ProductsState.loading() = ProductsLoading;
  const factory ProductsState.loaded({
    required List<Product> products,
    required bool hasMore,
    required int currentPage,
  }) = ProductsLoaded;
  const factory ProductsState.error(String message) = ProductsError;
}
```

### 5.3 IoT Sensor Integration

#### 5.3.1 MQTT Client Implementation

```dart
// lib/services/mqtt_service.dart

import 'dart:convert';
import 'dart:io';
import 'package:mqtt_client/mqtt_client.dart';
import 'package:mqtt_client/mqtt_server_client.dart';
import 'package:smart_dairy_mobile/core/constants/api_constants.dart';

class MqttService {
  late MqttServerClient _client;
  final String _clientId;
  final Function(String topic, Map<String, dynamic> payload)? onMessage;
  final Function()? onConnected;
  final Function()? onDisconnected;
  
  MqttService({
    required String clientId,
    this.onMessage,
    this.onConnected,
    this.onDisconnected,
  }) : _clientId = clientId;
  
  Future<void> connect() async {
    _client = MqttServerClient(
      ApiConstants.mqttBrokerHost,
      _clientId,
    );
    
    _client.port = ApiConstants.mqttBrokerPort;
    _client.keepAlivePeriod = 60;
    _client.onDisconnected = _onDisconnected;
    _client.onConnected = _onConnected;
    _client.onSubscribed = _onSubscribed;
    _client.logging(on: false);
    
    // Connection message with authentication
    final connMessage = MqttConnectMessage()
        .withClientIdentifier(_clientId)
        .withWillTopic('willtopic')
        .withWillMessage('Client disconnected unexpectedly')
        .startClean()
        .withWillQos(MqttQos.atLeastOnce);
    
    _client.connectionMessage = connMessage;
    
    try {
      await _client.connect(
        ApiConstants.mqttUsername,
        ApiConstants.mqttPassword,
      );
    } on SocketException catch (e) {
      throw MqttConnectionException('Failed to connect: ${e.message}');
    }
    
    // Listen for messages
    _client.updates!.listen((List<MqttReceivedMessage<MqttMessage>> c) {
      final MqttPublishMessage message = c[0].payload as MqttPublishMessage;
      final payload = MqttPublishPayload.bytesToStringAsString(message.payload.message);
      
      try {
        final jsonPayload = jsonDecode(payload) as Map<String, dynamic>;
        onMessage?.call(c[0].topic, jsonPayload);
      } catch (e) {
        print('Error parsing MQTT message: $e');
      }
    });
  }
  
  void subscribe(String topic, {MqttQos qos = MqttQos.atLeastOnce}) {
    _client.subscribe(topic, qos);
  }
  
  void unsubscribe(String topic) {
    _client.unsubscribe(topic);
  }
  
  void publish(String topic, Map<String, dynamic> payload, {MqttQos qos = MqttQos.atLeastOnce}) {
    final builder = MqttClientPayloadBuilder();
    builder.addString(jsonEncode(payload));
    
    _client.publishMessage(topic, qos, builder.payload!);
  }
  
  void disconnect() {
    _client.disconnect();
  }
  
  bool get isConnected => _client.connectionStatus?.state == MqttConnectionState.connected;
  
  void _onConnected() {
    print('MQTT Connected');
    onConnected?.call();
  }
  
  void _onDisconnected() {
    print('MQTT Disconnected');
    onDisconnected?.call();
  }
  
  void _onSubscribed(String topic) {
    print('Subscribed to: $topic');
  }
}

// Usage in app
final mqttServiceProvider = Provider<MqttService>((ref) {
  final userId = ref.watch(currentUserProvider)?.id ?? 'anonymous';
  
  return MqttService(
    clientId: 'smart_dairy_mobile_$userId',
    onMessage: (topic, payload) {
      // Handle incoming sensor data
      ref.read(sensorDataProvider.notifier).updateSensorData(topic, payload);
    },
    onConnected: () {
      // Subscribe to relevant topics
      ref.read(mqttServiceProvider).subscribe('farm/+/sensors/+/+');
    },
  );
});
```

### 5.4 Bangladesh Payment Gateway Integration

#### 5.4.1 bKash Integration

```python
# integrations/payment/bkash_client.py

import requests
import json
from datetime import datetime, timedelta
from typing import Dict, Optional, Any
import logging

logger = logging.getLogger(__name__)

class BKashClient:
    """
    bKash Payment Gateway Integration Client
    Implements bKash Payment API v3.0
    """
    
    SANDBOX_BASE_URL = "https://tokenized.sandbox.bka.sh"
    PRODUCTION_BASE_URL = "https://tokenized.pay.bka.sh"
    
    def __init__(self, app_key: str, app_secret: str, username: str, password: str, 
                 is_sandbox: bool = True):
        self.app_key = app_key
        self.app_secret = app_secret
        self.username = username
        self.password = password
        self.base_url = self.SANDBOX_BASE_URL if is_sandbox else self.PRODUCTION_BASE_URL
        
        self._id_token: Optional[str] = None
        self._refresh_token: Optional[str] = None
        self._token_expiry: Optional[datetime] = None
        
    def _get_headers(self, include_auth: bool = True) -> Dict[str, str]:
        """Get request headers with authentication."""
        headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'x-app-key': self.app_key,
        }
        
        if include_auth and self._id_token:
            headers['Authorization'] = f'Bearer {self._id_token}'
            
        return headers
    
    def _is_token_valid(self) -> bool:
        """Check if current token is valid."""
        if not self._id_token or not self._token_expiry:
            return False
        return datetime.now() < self._token_expiry - timedelta(minutes=5)
    
    def _refresh_auth_token(self) -> None:
        """Refresh authentication token."""
        url = f"{self.base_url}/v1.2.0-beta/tokenized/checkout/token/grant"
        
        payload = {
            'app_key': self.app_key,
            'app_secret': self.app_secret,
        }
        
        response = requests.post(
            url,
            headers=self._get_headers(include_auth=False),
            json=payload,
            auth=(self.username, self.password)
        )
        response.raise_for_status()
        
        data = response.json()
        self._id_token = data['id_token']
        self._refresh_token = data['refresh_token']
        self._token_expiry = datetime.now() + timedelta(seconds=data['expires_in'])
        
        logger.info("bKash token refreshed successfully")
    
    def _ensure_authenticated(self) -> None:
        """Ensure client has valid authentication."""
        if not self._is_token_valid():
            self._refresh_auth_token()
    
    def create_payment(self, amount: float, invoice_number: str, callback_url: str,
                       payer_reference: Optional[str] = None,
                       merchant_invoice_number: Optional[str] = None) -> Dict[str, Any]:
        """
        Create a new payment request.
        
        Args:
            amount: Payment amount
            invoice_number: Internal invoice reference
            callback_url: URL for payment callback
            payer_reference: Customer phone number (optional)
            merchant_invoice_number: Merchant invoice number (optional)
            
        Returns:
            Payment creation response with bkashURL for redirection
        """
        self._ensure_authenticated()
        
        url = f"{self.base_url}/v1.2.0-beta/tokenized/checkout/create"
        
        payload = {
            'mode': '0011',  # Checkout URL mode
            'payerReference': payer_reference or invoice_number,
            'callbackURL': callback_url,
            'amount': str(amount),
            'currency': 'BDT',
            'intent': 'sale',
            'merchantInvoiceNumber': merchant_invoice_number or invoice_number,
        }
        
        response = requests.post(
            url,
            headers=self._get_headers(),
            json=payload
        )
        response.raise_for_status()
        
        result = response.json()
        
        if result.get('statusCode') != '0000':
            logger.error(f"bKash payment creation failed: {result}")
            raise BKashPaymentError(result.get('statusMessage', 'Unknown error'))
            
        logger.info(f"bKash payment created: {result.get('paymentID')}")
        return result
    
    def execute_payment(self, payment_id: str) -> Dict[str, Any]:
        """
        Execute payment after customer confirmation.
        
        Args:
            payment_id: Payment ID from create_payment response
            
        Returns:
            Payment execution response
        """
        self._ensure_authenticated()
        
        url = f"{self.base_url}/v1.2.0-beta/tokenized/checkout/execute"
        
        payload = {
            'paymentID': payment_id,
        }
        
        response = requests.post(
            url,
            headers=self._get_headers(),
            json=payload
        )
        response.raise_for_status()
        
        result = response.json()
        
        if result.get('statusCode') != '0000':
            logger.error(f"bKash payment execution failed: {result}")
            raise BKashPaymentError(result.get('statusMessage', 'Unknown error'))
            
        logger.info(f"bKash payment executed: {payment_id}")
        return result
    
    def query_payment(self, payment_id: str) -> Dict[str, Any]:
        """Query payment status."""
        self._ensure_authenticated()
        
        url = f"{self.base_url}/v1.2.0-beta/tokenized/checkout/payment/status"
        
        payload = {
            'paymentID': payment_id,
        }
        
        response = requests.post(
            url,
            headers=self._get_headers(),
            json=payload
        )
        response.raise_for_status()
        
        return response.json()
    
    def refund_payment(self, payment_id: str, amount: float, trx_id: str,
                       sku: Optional[str] = None, reason: Optional[str] = None) -> Dict[str, Any]:
        """Process refund for a completed payment."""
        self._ensure_authenticated()
        
        url = f"{self.base_url}/v1.2.0-beta/tokenized/checkout/payment/refund"
        
        payload = {
            'paymentID': payment_id,
            'amount': str(amount),
            'trxID': trx_id,
            'sku': sku or 'NA',
            'reason': reason or 'Customer request',
        }
        
        response = requests.post(
            url,
            headers=self._get_headers(),
            json=payload
        )
        response.raise_for_status()
        
        result = response.json()
        
        if result.get('statusCode') != '0000':
            logger.error(f"bKash refund failed: {result}")
            raise BKashPaymentError(result.get('statusMessage', 'Unknown error'))
            
        logger.info(f"bKash refund processed: {payment_id}")
        return result


class BKashPaymentError(Exception):
    """Custom exception for bKash payment errors."""
    pass
```

---



## 6. Integration Architecture

### 6.1 ERP Integration Patterns

#### 6.1.1 Odoo External API Integration

```python
# integrations/odoo/odoo_api_client.py

from typing import Dict, List, Optional, Any, Union
import requests
from urllib.parse import urljoin
import xmlrpc.client
import logging

logger = logging.getLogger(__name__)

class OdooAPIClient:
    """
    External API Client for Odoo Integration.
    Provides clean interface for external systems to interact with Odoo.
    """
    
    def __init__(self, url: str, database: str, username: str, password: str):
        self.url = url.rstrip('/')
        self.database = database
        self.username = username
        self.password = password
        self._uid: Optional[int] = None
        self._models = None
        
    def authenticate(self) -> int:
        """Authenticate and get user ID."""
        common = xmlrpc.client.ServerProxy(
            urljoin(self.url, '/xmlrpc/2/common')
        )
        self._uid = common.authenticate(
            self.database, self.username, self.password, {}
        )
        if not self._uid:
            raise AuthenticationError("Failed to authenticate with Odoo")
        
        self._models = xmlrpc.client.ServerProxy(
            urljoin(self.url, '/xmlrpc/2/object')
        )
        logger.info(f"Authenticated as user {self._uid}")
        return self._uid
    
    def execute_kw(self, model: str, method: str, 
                   args: List = None, kwargs: Dict = None) -> Any:
        """Execute Odoo model method."""
        if not self._uid:
            self.authenticate()
            
        args = args or []
        kwargs = kwargs or {}
        
        return self._models.execute_kw(
            self.database, self._uid, self.password,
            model, method, args, kwargs
        )
    
    def search_read(self, model: str, domain: List = None, 
                    fields: List[str] = None, limit: int = None,
                    offset: int = None, order: str = None) -> List[Dict]:
        """Search and read records."""
        kwargs = {'context': {'lang': 'en_US'}}
        
        if fields:
            kwargs['fields'] = fields
        if limit:
            kwargs['limit'] = limit
        if offset:
            kwargs['offset'] = offset
        if order:
            kwargs['order'] = order
            
        return self.execute_kw(model, 'search_read', [domain or []], kwargs)
    
    def create(self, model: str, values: Dict) -> int:
        """Create a new record."""
        return self.execute_kw(model, 'create', [values])
    
    def write(self, model: str, ids: Union[int, List[int]], values: Dict) -> bool:
        """Update existing records."""
        if isinstance(ids, int):
            ids = [ids]
        return self.execute_kw(model, 'write', [ids, values])
    
    def unlink(self, model: str, ids: Union[int, List[int]]) -> bool:
        """Delete records."""
        if isinstance(ids, int):
            ids = [ids]
        return self.execute_kw(model, 'unlink', [ids])
    
    def call_method(self, model: str, ids: Union[int, List[int]], 
                    method: str, args: List = None, kwargs: Dict = None) -> Any:
        """Call custom method on record(s)."""
        if isinstance(ids, int):
            ids = [ids]
        args = args or []
        kwargs = kwargs or {}
        return self.execute_kw(model, method, [ids] + args, kwargs)


# REST API Wrapper for Odoo
class OdooRESTAPI:
    """
    RESTful API wrapper over Odoo's XML-RPC.
    Provides modern REST interface for frontend applications.
    """
    
    def __init__(self, odoo_client: OdooAPIClient):
        self.odoo = odoo_client
        
    # Product API
    def get_products(self, category_id: Optional[int] = None, 
                     search: Optional[str] = None,
                     limit: int = 20, offset: int = 0) -> List[Dict]:
        """Get products with optional filtering."""
        domain = [('sale_ok', '=', True), ('active', '=', True)]
        
        if category_id:
            domain.append(('categ_id', '=', category_id))
        if search:
            domain.append('|')
            domain.append(('name', 'ilike', search))
            domain.append(('default_code', 'ilike', search))
            
        return self.odoo.search_read(
            'product.product',
            domain=domain,
            fields=['id', 'name', 'default_code', 'list_price', 'qty_available',
                   'uom_id', 'categ_id', 'image_1920'],
            limit=limit,
            offset=offset,
            order='name ASC'
        )
    
    def get_product(self, product_id: int) -> Optional[Dict]:
        """Get single product details."""
        products = self.odoo.search_read(
            'product.product',
            domain=[('id', '=', product_id)],
            fields=['id', 'name', 'default_code', 'description_sale', 'list_price',
                   'qty_available', 'uom_id', 'categ_id', 'image_1920',
                   'product_tmpl_id', 'barcode'],
            limit=1
        )
        return products[0] if products else None
    
    # Customer API
    def get_customer(self, partner_id: int) -> Optional[Dict]:
        """Get customer details with B2B info."""
        partners = self.odoo.search_read(
            'res.partner',
            domain=[('id', '=', partner_id)],
            fields=['id', 'name', 'email', 'phone', 'mobile', 'street', 'city',
                   'state_id', 'country_id', 'zip', 'vat', 'tier_id', 
                   'credit_limit', 'credit_used'],
            limit=1
        )
        return partners[0] if partners else None
    
    # Order API
    def create_order(self, partner_id: int, order_lines: List[Dict],
                     warehouse_id: Optional[int] = None,
                     pricelist_id: Optional[int] = None) -> Dict:
        """Create sales order."""
        # Prepare order values
        order_vals = {
            'partner_id': partner_id,
            'order_line': [(0, 0, {
                'product_id': line['product_id'],
                'product_uom_qty': line['quantity'],
                'price_unit': line.get('price_unit', 0),
            }) for line in order_lines],
        }
        
        if warehouse_id:
            order_vals['warehouse_id'] = warehouse_id
        if pricelist_id:
            order_vals['pricelist_id'] = pricelist_id
            
        order_id = self.odoo.create('sale.order', order_vals)
        
        # Confirm order
        self.odoo.call_method('sale.order', order_id, 'action_confirm')
        
        return {'order_id': order_id, 'status': 'confirmed'}
    
    def get_order(self, order_id: int) -> Optional[Dict]:
        """Get order details."""
        orders = self.odoo.search_read(
            'sale.order',
            domain=[('id', '=', order_id)],
            fields=['id', 'name', 'partner_id', 'date_order', 'amount_total',
                   'state', 'order_line', 'warehouse_id', 'pricelist_id'],
            limit=1
        )
        
        if not orders:
            return None
            
        order = orders[0]
        
        # Get order lines
        lines = self.odoo.search_read(
            'sale.order.line',
            domain=[('order_id', '=', order_id)],
            fields=['id', 'product_id', 'name', 'product_uom_qty', 
                   'price_unit', 'price_subtotal']
        )
        order['order_lines'] = lines
        
        return order
    
    # Inventory API
    def get_stock_quantity(self, product_id: int, 
                          warehouse_id: Optional[int] = None) -> float:
        """Get available stock quantity."""
        domain = [('product_id', '=', product_id)]
        
        if warehouse_id:
            domain.append(('warehouse_id', '=', warehouse_id))
            
        quants = self.odoo.search_read(
            'stock.quant',
            domain=domain,
            fields=['quantity', 'reserved_quantity']
        )
        
        available = sum(
            q['quantity'] - q['reserved_quantity'] 
            for q in quants
        )
        return available


class AuthenticationError(Exception):
    pass
```

#### 6.1.2 Webhook Integration Pattern

```python
# integrations/webhooks/webhook_handler.py

from flask import Flask, request, jsonify
from functools import wraps
import hmac
import hashlib
import json
import logging
from typing import Callable, Dict, Any

logger = logging.getLogger(__name__)
app = Flask(__name__)

class WebhookHandler:
    """
    Generic webhook handler with signature verification.
    Supports multiple payment gateways and external services.
    """
    
    def __init__(self, secret_key: str):
        self.secret_key = secret_key
        self.handlers: Dict[str, Callable] = {}
        
    def register_handler(self, event_type: str, handler: Callable):
        """Register handler for specific event type."""
        self.handlers[event_type] = handler
        logger.info(f"Registered handler for {event_type}")
        
    def verify_signature(self, payload: bytes, signature: str, 
                         algorithm: str = 'sha256') -> bool:
        """Verify webhook signature."""
        expected = hmac.new(
            self.secret_key.encode(),
            payload,
            getattr(hashlib, algorithm)
        ).hexdigest()
        
        return hmac.compare_digest(expected, signature)
    
    def handle_webhook(self, event_type: str, payload: Dict[str, Any]) -> Dict:
        """Route webhook to appropriate handler."""
        handler = self.handlers.get(event_type)
        
        if not handler:
            logger.warning(f"No handler registered for {event_type}")
            return {'status': 'ignored', 'reason': 'no_handler'}
            
        try:
            result = handler(payload)
            return {'status': 'success', 'result': result}
        except Exception as e:
            logger.error(f"Handler error for {event_type}: {e}")
            return {'status': 'error', 'message': str(e)}


# bKash Webhook Handler
def handle_bkash_payment(payload: Dict) -> Dict:
    """Handle bKash payment webhook."""
    from services.order_service import OrderService
    
    payment_id = payload.get('paymentID')
    transaction_id = payload.get('trxID')
    status = payload.get('transactionStatus')
    amount = payload.get('amount')
    
    order_service = OrderService()
    
    if status == 'Completed':
        # Update order status
        order_service.confirm_payment(
            payment_reference=payment_id,
            transaction_id=transaction_id,
            amount=float(amount),
            gateway='bkash'
        )
        return {'order_updated': True}
        
    elif status == 'Failed':
        order_service.fail_payment(payment_reference=payment_id)
        return {'order_failed': True}
        
    return {'status': 'unknown', 'transaction_status': status}


# Nagad Webhook Handler
def handle_nagad_payment(payload: Dict) -> Dict:
    """Handle Nagad payment webhook."""
    from services.order_service import OrderService
    
    order_service = OrderService()
    
    if payload.get('status') == 'SUCCESS':
        order_service.confirm_payment(
            payment_reference=payload['orderId'],
            transaction_id=payload['issuerPaymentRefNo'],
            amount=float(payload['amount']),
            gateway='nagad'
        )
        return {'order_updated': True}
        
    return {'status': payload.get('status')}


# Flask Routes
webhook_handler = WebhookHandler(secret_key='your_webhook_secret')
webhook_handler.register_handler('bkash.payment', handle_bkash_payment)
webhook_handler.register_handler('nagad.payment', handle_nagad_payment)


@app.route('/webhooks/bkash', methods=['POST'])
def bkash_webhook():
    """bKash payment webhook endpoint."""
    signature = request.headers.get('X-BKash-Signature', '')
    payload = request.get_data()
    
    if not webhook_handler.verify_signature(payload, signature):
        return jsonify({'error': 'Invalid signature'}), 401
    
    data = request.get_json()
    result = webhook_handler.handle_webhook('bkash.payment', data)
    
    return jsonify(result), 200


@app.route('/webhooks/nagad', methods=['POST'])
def nagad_webhook():
    """Nagad payment webhook endpoint."""
    # Nagad uses different signature mechanism
    data = request.get_json()
    result = webhook_handler.handle_webhook('nagad.payment', data)
    
    return jsonify(result), 200


@app.route('/webhooks/iot/sensor', methods=['POST'])
def iot_sensor_webhook():
    """IoT sensor data webhook."""
    from services.iot_service import IoTService
    
    data = request.get_json()
    
    iot_service = IoTService()
    iot_service.process_sensor_data(data)
    
    return jsonify({'status': 'processed'}), 200
```

### 6.2 B2B Portal Integration

#### 6.2.1 API Specification (OpenAPI)

```yaml
# openapi.yaml
openapi: 3.0.3
info:
  title: Smart Dairy B2B API
  description: |
    API for Smart Dairy B2B Portal.
    Provides endpoints for wholesale ordering, pricing, and account management.
  version: 4.0.0
  contact:
    name: Smart Dairy IT Support
    email: api@smartdairy.com.bd

servers:
  - url: https://api.smartdairy.com.bd/v4
    description: Production
  - url: https://api-staging.smartdairy.com.bd/v4
    description: Staging

security:
  - bearerAuth: []

paths:
  /auth/login:
    post:
      summary: Authenticate user
      tags:
        - Authentication
      security: []
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/LoginRequest'
      responses:
        '200':
          description: Login successful
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/LoginResponse'
        '401':
          description: Invalid credentials

  /products:
    get:
      summary: List products
      tags:
        - Products
      parameters:
        - name: category_id
          in: query
          schema:
            type: integer
        - name: search
          in: query
          schema:
            type: string
        - name: page
          in: query
          schema:
            type: integer
            default: 1
        - name: page_size
          in: query
          schema:
            type: integer
            default: 20
      responses:
        '200':
          description: Product list
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ProductListResponse'

  /products/{id}:
    get:
      summary: Get product details
      tags:
        - Products
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      responses:
        '200':
          description: Product details
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/Product'

  /pricing/calculate:
    post:
      summary: Calculate price for order
      tags:
        - Pricing
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/PricingRequest'
      responses:
        '200':
          description: Price calculation
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/PricingResponse'

  /orders:
    post:
      summary: Create new order
      tags:
        - Orders
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/OrderCreateRequest'
      responses:
        '201':
          description: Order created
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/Order'
    
    get:
      summary: List orders
      tags:
        - Orders
      parameters:
        - name: status
          in: query
          schema:
            type: string
            enum: [draft, pending, confirmed, delivered, cancelled]
        - name: page
          in: query
          schema:
            type: integer
            default: 1
      responses:
        '200':
          description: Order list
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/OrderListResponse'

  /orders/{id}:
    get:
      summary: Get order details
      tags:
        - Orders
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      responses:
        '200':
          description: Order details
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/Order'

  /orders/{id}/payment:
    post:
      summary: Initiate payment for order
      tags:
        - Payments
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/PaymentRequest'
      responses:
        '200':
          description: Payment initiated
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/PaymentResponse'

  /account/credit:
    get:
      summary: Get credit limit information
      tags:
        - Account
      responses:
        '200':
          description: Credit information
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/CreditInfo'

components:
  securitySchemes:
    bearerAuth:
      type: http
      scheme: bearer
      bearerFormat: JWT

  schemas:
    LoginRequest:
      type: object
      properties:
        email:
          type: string
          format: email
        password:
          type: string
          format: password
      required: [email, password]

    LoginResponse:
      type: object
      properties:
        access_token:
          type: string
        refresh_token:
          type: string
        expires_in:
          type: integer
        user:
          $ref: '#/components/schemas/User'

    User:
      type: object
      properties:
        id:
          type: integer
        email:
          type: string
        name:
          type: string
        company_id:
          type: integer
        tier_id:
          type: integer

    Product:
      type: object
      properties:
        id:
          type: integer
        name:
          type: string
        sku:
          type: string
        description:
          type: string
        unit_price:
          type: number
          format: decimal
        uom:
          type: string
        category_id:
          type: integer
        stock_quantity:
          type: number
        image_url:
          type: string
          format: uri

    ProductListResponse:
      type: object
      properties:
        data:
          type: array
          items:
            $ref: '#/components/schemas/Product'
        pagination:
          $ref: '#/components/schemas/Pagination'

    PricingRequest:
      type: object
      properties:
        items:
          type: array
          items:
            type: object
            properties:
              product_id:
                type: integer
              quantity:
                type: number
        customer_id:
          type: integer

    PricingResponse:
      type: object
      properties:
        subtotal:
          type: number
        discount_amount:
          type: number
        tax_amount:
          type: number
        total:
          type: number
        items:
          type: array
          items:
            type: object
            properties:
              product_id:
                type: integer
              unit_price:
                type: number
              quantity:
                type: number
              line_total:
                type: number
              discount_applied:
                type: number

    OrderCreateRequest:
      type: object
      properties:
        items:
          type: array
          items:
            type: object
            properties:
              product_id:
                type: integer
              quantity:
                type: number
              unit_price:
                type: number
        delivery_address:
          $ref: '#/components/schemas/Address'
        payment_method:
          type: string
          enum: [bkash, nagad, bank_transfer, credit]
        notes:
          type: string

    Order:
      type: object
      properties:
        id:
          type: integer
        order_number:
          type: string
        customer_id:
          type: integer
        status:
          type: string
          enum: [draft, pending, confirmed, processing, delivered, cancelled]
        items:
          type: array
          items:
            $ref: '#/components/schemas/OrderItem'
        subtotal:
          type: number
        discount_amount:
          type: number
        tax_amount:
          type: number
        total:
          type: number
        created_at:
          type: string
          format: date-time
        delivery_date:
          type: string
          format: date

    OrderItem:
      type: object
      properties:
        id:
          type: integer
        product_id:
          type: integer
        product_name:
          type: string
        quantity:
          type: number
        unit_price:
          type: number
        line_total:
          type: number

    PaymentRequest:
      type: object
      properties:
        gateway:
          type: string
          enum: [bkash, nagad, bank_transfer]
        callback_url:
          type: string
          format: uri

    PaymentResponse:
      type: object
      properties:
        payment_id:
          type: string
        payment_url:
          type: string
          format: uri
        expires_at:
          type: string
          format: date-time

    CreditInfo:
      type: object
      properties:
        credit_limit:
          type: number
        credit_used:
          type: number
        credit_available:
          type: number
        payment_terms:
          type: string
        outstanding_invoices:
          type: array
          items:
            type: object
            properties:
              invoice_number:
                type: string
              amount:
                type: number
              due_date:
                type: string
                format: date

    Pagination:
      type: object
      properties:
        current_page:
          type: integer
        total_pages:
          type: integer
        total_items:
          type: integer
        items_per_page:
          type: integer

    Address:
      type: object
      properties:
        street:
          type: string
        city:
          type: string
        state:
          type: string
        zip:
          type: string
        country:
          type: string
```

### 6.3 IoT Data Pipeline

#### 6.3.1 Data Ingestion Architecture

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                              IOT DATA PIPELINE ARCHITECTURE                                          │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                     EDGE LAYER (Farm)                                                │
│  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐             │
│  │ Temperature      │  │ Humidity         │  │ Milk Meter       │  │ GPS Collar       │             │
│  │ Sensor (MQTT)    │  │ Sensor (MQTT)    │  │ (Modbus TCP)     │  │ (LoRaWAN)        │             │
│  └────────┬─────────┘  └────────┬─────────┘  └────────┬─────────┘  └────────┬─────────┘             │
│           │                     │                     │                     │                        │
│           └─────────────────────┴─────────────────────┴─────────────────────┘                        │
│                                     │                                                                │
│                                     ▼                                                                │
│                           ┌──────────────────┐                                                       │
│                           │ Edge Gateway     │  • Protocol translation (MQTT, Modbus, LoRa)         │
│                           │ (Raspberry Pi/   │  • Local buffering (SQLite)                          │
│                           │  Industrial PC)  │  • Edge computing (alerts, aggregation)              │
│                           └────────┬─────────┘  • Store-and-forward (offline resilience)           │
│                                    │                                                                 │
│                                    │ MQTT over TLS                                                    │
└────────────────────────────────────┼─────────────────────────────────────────────────────────────────┘
                                     │
                                     ▼
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                  INGESTION LAYER                                                     │
│                                                                                                      │
│  ┌─────────────────────────────────────────────────────────────────────────────────────────────┐    │
│  │                           MQTT Broker Cluster (EMQX)                                         │    │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐       │    │
│  │  │   Node 1    │──│   Node 2    │──│   Node 3    │──│   Node 4    │──│   Node 5    │       │    │
│  │  │  (Master)   │  │  (Replica)  │  │  (Replica)  │  │  (Replica)  │  │  (Replica)  │       │    │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘       │    │
│  │                                                                                             │    │
│  │  Topics:                                                                                    │    │
│  │    farm/+/sensors/+/+     (Raw sensor data)                                                │    │
│  │    farm/+/alerts/+        (Alert notifications)                                            │    │
│  │    farm/+/commands/+      (Device commands)                                                │    │
│  │    farm/+/status/+        (Device status)                                                  │    │
│  │                                                                                             │    │
│  │  QoS: 1 (at least once)                                                                     │    │
│  │  Retain: false for data, true for config                                                   │    │
│  └─────────────────────────────────────────────────────────────────────────────────────────────┘    │
│                                     │                                                                │
│                                     │ Topic Routing                                                    │
│           ┌─────────────────────────┼─────────────────────────┐                                       │
│           │                         │                         │                                       │
│           ▼                         ▼                         ▼                                       │
│  ┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐                             │
│  │ Stream          │       │ Stream          │       │ Stream          │                             │
│  │ Processor 1     │       │ Processor 2     │       │ Processor 3     │                             │
│  │ (Validation)    │       │ (Enrichment)    │       │ (Alert Rules)   │                             │
│  └────────┬────────┘       └────────┬────────┘       └────────┬────────┘                             │
│           │                         │                         │                                       │
└───────────┼─────────────────────────┼─────────────────────────┼───────────────────────────────────────┘
            │                         │                         │
            ▼                         ▼                         ▼
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                  STORAGE LAYER                                                       │
│                                                                                                      │
│  ┌─────────────────────────────────────────────────────────────────────────────────────────────┐    │
│  │                            TimescaleDB (Time-series Data)                                   │    │
│  │                                                                                              │    │
│  │  Table: iot_sensor_reading                                                                   │    │
│  │  ├── Hypertable (time-based partitioning)                                                    │    │
│  │  ├── Chunk size: 1 day                                                                       │    │
│  │  ├── Compression: Enabled after 7 days                                                       │    │
│  │  └── Retention: 2 years                                                                      │    │
│  │                                                                                              │    │
│  │  Continuous Aggregates:                                                                      │    │
│  │  ├── iot_hourly_summary (1 hour buckets)                                                    │    │
│  │  ├── iot_daily_summary (1 day buckets)                                                      │    │
│  │  └── iot_monthly_summary (1 month buckets)                                                  │    │
│  │                                                                                              │    │
│  │  Indexes:                                                                                    │    │
│  │  ├── (sensor_id, time DESC)                                                                  │    │
│  │  ├── (location_id, time DESC)                                                                │    │
│  │  └── (reading_type, time DESC)                                                               │    │
│  └─────────────────────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                                      │
│  ┌─────────────────────────────────────────────────────────────────────────────────────────────┐    │
│  │                            PostgreSQL (Operational Data)                                    │    │
│  │                                                                                              │    │
│  │  Tables:                                                                                     │    │
│  │  ├── iot_sensor (Device registry)                                                            │    │
│  │  ├── iot_alert_rule (Alert configuration)                                                    │    │
│  │  ├── iot_alert_history (Alert log)                                                           │    │
│  │  └── iot_device_config (Device settings)                                                     │    │
│  └─────────────────────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                                      │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘
            │
            │ Materialized Views / Triggers
            ▼
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                  CONSUMPTION LAYER                                                   │
│                                                                                                      │
│  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐             │
│  │ Grafana          │  │ Mobile App       │  │ ERP System       │  │ BI Dashboard     │             │
│  │ Dashboards       │  │ Real-time UI     │  │ Production       │  │ Analytics        │             │
│  │                  │  │                  │  │ Planning         │  │                  │             │
│  │ • Temperature    │  │ • Live alerts    │  │ • Milk yield     │  │ • Trend analysis │             │
│  │ • Humidity       │  │ • Sensor status  │  │   optimization   │  │ • Predictive     │             │
│  │ • Production     │  │ • Historical     │  │ • Feed planning  │  │   maintenance    │             │
│  │   metrics        │  │   charts         │  │                  │  │                  │             │
│  └──────────────────┘  └──────────────────┘  └──────────────────┘  └──────────────────┘             │
│                                                                                                      │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘
```

---

## 7. Risk Management Framework

### 7.1 Risk Register

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                              PHASE 4 RISK REGISTER                                                   │
├──────┬─────────────────────────────────────┬──────────┬──────────┬──────────┬────────────────────────┤
│  ID  │ Risk Description                    │ Impact   │ Likeli-  │ Risk     │ Mitigation Strategy    │
│      │                                     │ (1-5)    │ hood     │ Score    │                        │
│      │                                     │          │ (1-5)    │ (IxL)    │                        │
├──────┼─────────────────────────────────────┼──────────┼──────────┼──────────┼────────────────────────┤
│ R001 │ Key developer illness/departure     │    5     │    2     │   10     │ Cross-training,        │
│      │ during critical milestone           │          │          │          │ documentation,         │
│      │                                     │          │          │          │ backup assignments     │
├──────┼─────────────────────────────────────┼──────────┼──────────┼──────────┼────────────────────────┤
│ R002 │ Payment gateway API changes         │    4     │    3     │   12     │ API versioning,        │
│      │ (bKash/Nagad) breaking integration  │          │          │          │ abstraction layer,     │
│      │                                     │          │          │          │ early testing          │
├──────┼─────────────────────────────────────┼──────────┼──────────┼──────────┼────────────────────────┤
│ R003 │ IoT sensor hardware failures        │    3     │    4     │   12     │ Redundant sensors,     │
│      │ in farm environment                 │          │          │          │ edge buffering,        │
│      │                                     │          │          │          │ local storage          │
├──────┼─────────────────────────────────────┼──────────┼──────────┼──────────┼────────────────────────┤
│ R004 │ Database performance degradation    │    4     │    3     │   12     │ Optimization,          │
│      │ with high sensor data volume        │          │          │          │ partitioning,          │
│      │                                     │          │          │          │ scaling plan           │
├──────┼─────────────────────────────────────┼──────────┼──────────┼──────────┼────────────────────────┤
│ R005 │ Bangladesh tax regulation changes   │    4     │    2     │    8     │ Modular tax code,      │
│      │ affecting ERP calculations          │          │          │          │ compliance monitoring  │
├──────┼─────────────────────────────────────┼──────────┼──────────┼──────────┼────────────────────────┤
│ R006 │ Third-party service outages         │    3     │    4     │   12     │ Circuit breakers,      │
│      │ (SMS, email gateways)               │          │          │          │ fallback providers,    │
│      │                                     │          │          │          │ queueing               │
├──────┼─────────────────────────────────────┼──────────┼──────────┼──────────┼────────────────────────┤
│ R007 │ Security breach or data leak        │    5     │    2     │   10     │ Security audit,        │
│      │                                     │          │          │          │ encryption,            │
│      │                                     │          │          │          │ access controls        │
├──────┼─────────────────────────────────────┼──────────┼──────────┼──────────┼────────────────────────┤
│ R008 │ Mobile app store rejection          │    3     │    3     │    9     │ Early review,          │
│      │ (iOS/Android)                       │          │          │          │ compliance checklist   │
├──────┼─────────────────────────────────────┼──────────┼──────────┼──────────┼────────────────────────┤
│ R009 │ Integration complexity between      │    4     │    4     │   16     │ API-first design,      │
│      │ ERP and external systems            │          │          │          │ contract testing,      │
│      │                                     │          │          │          │ staged rollout         │
├──────┼─────────────────────────────────────┼──────────┼──────────┼──────────┼────────────────────────┤
│ R010 │ User adoption resistance            │    3     │    3     │    9     │ Training program,      │
│      │ (farm staff, B2B customers)         │          │          │          │ change management,     │
│      │                                     │          │          │          │ incentives             │
├──────┼─────────────────────────────────────┼──────────┼──────────┼──────────┼────────────────────────┤
│ R011 │ Scope creep beyond 100 days         │    4     │    4     │   16     │ Strict change control, │
│      │                                     │          │          │          │ phased approach,       │
│      │                                     │          │          │          │ MVP definition         │
├──────┼─────────────────────────────────────┼──────────┼──────────┼──────────┼────────────────────────┤
│ R012 │ Infrastructure/cloud cost overrun   │    3     │    3     │    9     │ Cost monitoring,       │
│      │                                     │          │          │          │ reserved instances,    │
│      │                                     │          │          │          │ optimization           │
└──────┴─────────────────────────────────────┴──────────┴──────────┴──────────┴────────────────────────┘

Risk Score Thresholds:
  - 20-25: CRITICAL - Immediate action required, escalate to PM/Steering Committee
  - 15-19: HIGH - Develop mitigation plan within 48 hours
  - 10-14: MEDIUM - Monitor closely, include in weekly reports
  - 5-9:  LOW - Standard monitoring, document in risk log
  - 1-4:  MINIMAL - Accept and monitor

Top 5 Risks Requiring Immediate Attention:
  1. R009: Integration complexity (Score: 16)
  2. R011: Scope creep (Score: 16)
  3. R002: Payment gateway API changes (Score: 12)
  4. R003: IoT hardware failures (Score: 12)
  5. R004: Database performance (Score: 12)
```

### 7.2 Mitigation Strategies

#### 7.2.1 High-Priority Risk Mitigations

**R009: Integration Complexity**

```python
# Mitigation: API Contract Testing
# tests/integration/test_api_contracts.py

import pytest
from pact import Consumer, Provider

@pytest.fixture
def pact():
    return Consumer('SmartDairyMobile').has_pact_with(
        Provider('SmartDairyAPI'),
        pact_dir='./pacts'
    )

def test_get_products_contract(pact):
    """Verify API contract for product listing."""
    expected = {
        'data': [
            {
                'id': 1,
                'name': 'Fresh Milk 1L',
                'sku': 'MILK-001',
                'unit_price': 80.00,
                'stock_quantity': 100
            }
        ],
        'pagination': {
            'current_page': 1,
            'total_pages': 10,
            'total_items': 200
        }
    }
    
    (pact
     .given('products exist')
     .upon_receiving('a request for products')
     .with_request('GET', '/v4/products', query={'page': '1'})
     .will_respond_with(200, body=expected))
    
    with pact:
        result = api_client.get_products(page=1)
        assert result['data'][0]['name'] == 'Fresh Milk 1L'


# Circuit Breaker Pattern for Third-party Resilience
from circuitbreaker import circuit
import requests

@circuit(failure_threshold=5, recovery_timeout=60, expected_exception=requests.RequestException)
def call_payment_gateway(api_request):
    """Call payment gateway with circuit breaker protection."""
    return requests.post(api_request.url, json=api_request.payload, timeout=10)


# Fallback Strategy
class PaymentGatewayWithFallback:
    """Primary gateway with automatic fallback."""
    
    def __init__(self):
        self.primary = BKashClient()
        self.fallback = NagadClient()
        self.circuit_breakers = {
            'bkash': circuit(failure_threshold=3)(self.primary.create_payment),
            'nagad': circuit(failure_threshold=3)(self.fallback.create_payment),
        }
    
    def create_payment(self, amount, invoice_number, callback_url, preferred='bkash'):
        """Try preferred gateway, fallback if unavailable."""
        gateways = [preferred] + [g for g in ['bkash', 'nagad'] if g != preferred]
        
        for gateway in gateways:
            try:
                return self.circuit_breakers[gateway](amount, invoice_number, callback_url)
            except Exception as e:
                logger.warning(f"{gateway} failed: {e}, trying fallback")
                continue
        
        raise PaymentError("All payment gateways unavailable")
```

**R011: Scope Creep Prevention**

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                         CHANGE CONTROL PROCESS                                                       │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘

Change Request Flow:

  ┌─────────────┐
  │   Request   │  Originator submits change request form
  │  Submitted  │  with business justification and impact
  └──────┬──────┘
         │
         ▼
  ┌─────────────┐
  │   Initial   │  Tech Lead reviews technical feasibility
  │   Review    │  and provides effort estimate
  └──────┬──────┘
         │
         ▼
  ┌─────────────────────────────────────────────────────────┐
  │                    IMPACT ASSESSMENT                     │
  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
  │  │   Schedule   │  │    Budget    │  │   Quality    │  │
  │  │   Impact     │  │   Impact     │  │   Impact     │  │
  │  │  + X days    │  │  + ৳Y        │  │  Risk Level  │  │
  │  └──────────────┘  └──────────────┘  └──────────────┘  │
  └─────────────────────────────────────────────────────────┘
         │
         ▼
  ┌─────────────┐
  │  Steering   │  Decision: Approve / Reject / Defer
  │  Committee  │  Requires: <5 days impact, <৳100K budget
  └──────┬──────┘  change for approval during Phase 4
         │
    ┌────┴────┐
    │         │
    ▼         ▼
┌───────┐ ┌─────────┐
│Approve│ │ Defer to│
│       │ │ Phase 5 │
└───┬───┘ └─────────┘
    │
    ▼
┌─────────────┐
│  Update     │  Update project plan, notify team
│  Baseline   │
└─────────────┘

Change Request Form Template:

┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│  CHANGE REQUEST #CR-4-XXX                                                                          │
├─────────────────────────────────────────────────────────────────────────────────────────────────────┤
│  Date: _______________  Requestor: _______________  Priority: [ ] High [ ] Med [ ] Low              │
├─────────────────────────────────────────────────────────────────────────────────────────────────────┤
│  Description of Change:                                                                             │
│  ________________________________________________________________________________________________   │
│  ________________________________________________________________________________________________   │
├─────────────────────────────────────────────────────────────────────────────────────────────────────┤
│  Business Justification:                                                                              │
│  ________________________________________________________________________________________________   │
├─────────────────────────────────────────────────────────────────────────────────────────────────────┤
│  IMPACT ASSESSMENT (To be completed by Tech Lead)                                                    │
│  ┌─────────────────────────────────────────────────────────────────────────────────────────────┐   │
│  │ Effort Estimate: ___ person-days                                                           │   │
│  │ Schedule Impact: ___ days delay                                                            │   │
│  │ Budget Impact: ৳___ additional                                                             │   │
│  │ Quality Risk: [ ] Low [ ] Medium [ ] High                                                  │   │
│  │ Dependencies Affected: __________________________________________________________________  │   │
│  └─────────────────────────────────────────────────────────────────────────────────────────────┘   │
├─────────────────────────────────────────────────────────────────────────────────────────────────────┤
│  STEERING COMMITTEE DECISION:                                                                        │
│  [ ] APPROVED  [ ] REJECTED  [ ] DEFERRED                                                            │
│  Comments: _____________________________________________________________________________________    │
│  Approved By: _________________  Date: _________________                                             │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘
```

### 7.3 Contingency Plans

#### 7.3.1 Technical Contingencies

| Scenario | Contingency Plan | Trigger | Responsible |
|----------|------------------|---------|-------------|
| Developer unavailable | Reallocate tasks to remaining developers; engage contractor | >3 days absence | Project Manager |
| Payment gateway down | Switch to alternative gateway; enable bank transfer option | >30 min outage | Dev 2 |
| Database overload | Enable read replicas; implement caching layer | CPU >80% for 10 min | DevOps |
| Mobile app rejection | Address review feedback; expedite resubmission | Rejection notice | Dev 3 |
| IoT connectivity loss | Activate offline mode; queue data for sync | Gateway timeout | Dev 1 |

#### 7.3.2 Business Contingencies

| Scenario | Contingency Plan | Trigger | Responsible |
|----------|------------------|---------|-------------|
| Budget overrun >20% | Defer non-critical features; seek additional funding | Budget review | Project Manager |
| Deadline at risk | Implement phased delivery; prioritize MVP features | Schedule variance >10% | Steering Committee |
| Key stakeholder change | Conduct knowledge transfer; update project docs | Resignation/transfer | Project Manager |
| Regulatory change | Engage legal/compliance; update affected modules | NBR notification | Dev 1 |

---

## 8. Quality Assurance Framework

### 8.1 Testing Strategy

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                              TESTING PYRAMID                                                         │
├─────────────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                                     │
│                              ┌─────────────┐                                                        │
│                              │    E2E      │  < 50 tests                                           │
│                              │   Tests     │  Selenium, Appium, Playwright                         │
│                              │   (10%)     │  Critical user journeys                               │
│                              └──────┬──────┘                                                        │
│                                     │                                                               │
│                            ┌────────┴────────┐                                                      │
│                            │  Integration    │  ~200 tests                                          │
│                            │     Tests       │  API contracts, DB integration                        │
│                            │     (20%)       │  Service interactions                                 │
│                            └────────┬────────┘                                                      │
│                                     │                                                               │
│                       ┌─────────────┴─────────────┐                                                 │
│                       │         Unit Tests        │  ~800 tests                                     │
│                       │           (70%)           │  Jest, pytest, Flutter test                       │
│                       │  Business logic, utilities │  Model methods, services                         │
│                       └───────────────────────────┘                                                 │
│                                                                                                     │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘

Test Coverage Requirements:
  - Unit Tests: > 85% coverage
  - Integration Tests: All API endpoints, critical paths
  - E2E Tests: All primary user workflows
  - Security Tests: OWASP Top 10 coverage
  - Performance Tests: Load testing for 10,000 concurrent users
```

### 8.2 Testing Environments

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                              ENVIRONMENT ARCHITECTURE                                                │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘

Developer Local
├── Each developer's machine
├── Docker Compose for dependencies
├── Hot reload for rapid iteration
└── Unit tests run locally

    ↓ Push to feature branch

CI/CD Pipeline (GitHub Actions/GitLab CI)
├── Automated build on every PR
├── Run unit tests (< 5 minutes)
├── Run integration tests (< 15 minutes)
├── Code quality checks (SonarQube, linting)
├── Security scan (Snyk, Trivy)
└── Deploy to Dev environment on merge

    ↓ Merge to develop branch

Development Environment
├── URL: dev.smartdairy.com.bd
├── Shared database (refreshed nightly)
├── Latest features for testing
├── Internal team testing
└── Smoke tests run hourly

    ↓ QA approval

Staging Environment
├── URL: staging.smartdairy.com.bd
├── Production-like configuration
├── Anonymized production data
├── UAT with selected customers
├── Performance testing
└── Security penetration testing

    ↓ Sign-off from stakeholders

Production Environment
├── URL: smartdairy.com.bd
├── Blue-green deployment
├── Canary releases (10% → 50% → 100%)
├── Real-time monitoring
└── Automated rollback capability
```

### 8.3 Code Quality Standards

```yaml
# .github/workflows/quality-checks.yml
name: Code Quality Checks

on:
  pull_request:
    branches: [develop, main]

jobs:
  lint-and-test:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v4
      
      # Python (Odoo) Quality Checks
      - name: Set up Python
        uses: actions/setup-python@v4
        with:
          python-version: '3.12'
      
      - name: Install Python dependencies
        run: |
          pip install flake8 pylint black isort bandit mypy
          pip install -r requirements.txt
      
      - name: Run flake8
        run: flake8 . --count --select=E9,F63,F7,F82 --show-source --statistics
      
      - name: Run pylint
        run: pylint --rcfile=.pylintrc smart_dairy/
      
      - name: Check formatting with black
        run: black --check .
      
      - name: Security scan with bandit
        run: bandit -r smart_dairy/ -f json -o bandit-report.json || true
      
      # Flutter Quality Checks
      - name: Set up Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
      
      - name: Get Flutter dependencies
        run: flutter pub get
        working-directory: ./mobile
      
      - name: Analyze Flutter code
        run: flutter analyze --fatal-infos
        working-directory: ./mobile
      
      - name: Check Flutter formatting
        run: dart format --set-exit-if-changed .
        working-directory: ./mobile
      
      - name: Run Flutter tests
        run: flutter test --coverage
        working-directory: ./mobile
      
      # SonarQube Analysis
      - name: SonarQube Scan
        uses: SonarSource/sonarqube-scan-action@master
        env:
          SONAR_TOKEN: ${{ secrets.SONAR_TOKEN }}
          SONAR_HOST_URL: ${{ secrets.SONAR_HOST_URL }}

      # Coverage Reporting
      - name: Upload coverage to Codecov
        uses: codecov/codecov-action@v3
        with:
          files: ./coverage/lcov.info,./mobile/coverage/lcov.info
          flags: unittests
          name: codecov-umbrella
```

### 8.4 Security Requirements

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                              SECURITY CHECKLIST                                                      │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘

Authentication & Authorization
[ ] Passwords hashed with bcrypt (cost factor >= 12)
[ ] JWT tokens with expiration (access: 15min, refresh: 7 days)
[ ] MFA for admin accounts
[ ] RBAC implemented with principle of least privilege
[ ] Session timeout after 30 minutes inactivity
[ ] Account lockout after 5 failed attempts

Data Protection
[ ] TLS 1.3 for all network communications
[ ] AES-256 encryption for data at rest
[ ] Database credentials in secure vault (HashiCorp/AWS Secrets)
[ ] PII data anonymized in non-production environments
[ ] Database backups encrypted

API Security
[ ] Rate limiting: 100 requests/minute per IP
[ ] Input validation on all endpoints
[ ] SQL injection prevention (parameterized queries)
[ ] XSS prevention (output encoding)
[ ] CSRF tokens for state-changing operations
[ ] CORS properly configured

Infrastructure Security
[ ] Network segmentation (DMZ, App, DB tiers)
[ ] WAF enabled with OWASP rules
[ ] DDoS protection (CloudFlare/AWS Shield)
[ ] Security groups restrict traffic to required ports
[ ] OS patches applied within 7 days of release
[ ] Container images scanned for vulnerabilities

Compliance
[ ] NBR VAT compliance for all transactions
[ ] Bangladesh Data Protection Act compliance
[ ] Audit logs retained for 7 years
[ ] PCI DSS considerations for payment data

Security Testing
[ ] SAST (Static Application Security Testing) - SonarQube
[ ] DAST (Dynamic Application Security Testing) - OWASP ZAP
[ ] Dependency vulnerability scan - Snyk/Dependabot
[ ] Penetration testing before production
[ ] Security code review for all authentication/payment code
```

---

## 9. Appendices

### 9.1 Glossary

| Term | Definition |
|------|------------|
| **B2B** | Business-to-Business; commerce between companies |
| **BOM** | Bill of Materials; list of raw materials for manufacturing |
| **ERP** | Enterprise Resource Planning; integrated business management software |
| **Hypertable** | TimescaleDB's partitioned time-series table |
| **IoT** | Internet of Things; connected sensor devices |
| **LoRaWAN** | Long Range Wide Area Network; wireless protocol for IoT |
| **MRP** | Manufacturing Resource Planning; production planning module |
| **MQTT** | Message Queuing Telemetry Transport; lightweight IoT messaging protocol |
| **RBAC** | Role-Based Access Control; permission system based on user roles |
| **SCC** | Somatic Cell Count; milk quality indicator |
| **SNF** | Solids-Not-Fat; milk composition measure |
| **VAT** | Value Added Tax; consumption tax in Bangladesh |
| **WAF** | Web Application Firewall; security layer for web applications |

### 9.2 Reference Documents

| Document ID | Title | Location |
|-------------|-------|----------|
| SD-DOC-001 | Smart Dairy Company Profile | /docs/01_Company_Profile.md |
| SD-DOC-002 | RFP - Smart Web Portal System | /docs/02_RFP.md |
| SD-DOC-003 | Phase 1 Implementation Guide | /Implementation_Roadmap/Phase_1/ |
| SD-DOC-004 | Phase 2 Implementation Guide | /Implementation_Roadmap/Phase_2/ |
| SD-DOC-005 | Phase 3 Implementation Guide | /Implementation_Roadmap/Phase_3/ |
| SD-DOC-006 | Odoo 19 Developer Documentation | https://www.odoo.com/documentation/19.0/ |
| SD-DOC-007 | bKash Payment Gateway API | https://developer.bka.sh/docs |
| SD-DOC-008 | Nagad Merchant API | https://nagad.com.bd/merchant |
| SD-DOC-009 | Bangladesh VAT Act, 1991 | NBR Official Website |
| SD-DOC-010 | Flutter Documentation | https://docs.flutter.dev |

### 9.3 Standards and Guidelines

#### 9.3.1 Coding Standards

**Python (Odoo):**
- Follow PEP 8 style guide
- Maximum line length: 100 characters
- Use type hints where applicable
- Document all public methods with docstrings
- Maintain cyclomatic complexity < 10 per function

**Dart (Flutter):**
- Follow Effective Dart guidelines
- Use `const` constructors where possible
- Prefer `final` over `var`
- Use named parameters for clarity
- Organize imports: Dart, Flutter, Packages, Relative

#### 9.3.2 Database Naming Conventions

- Tables: lowercase with underscores (e.g., `farm_animal`)
- Columns: lowercase with underscores (e.g., `birth_date`)
- Primary keys: `id` (auto-increment)
- Foreign keys: `{related_table}_id` (e.g., `partner_id`)
- Indexes: `idx_{table}_{column}`
- Constraints: `chk_{table}_{rule}` (check), `fk_{table}_{ref}` (foreign key)

#### 9.3.3 API Design Standards

- RESTful design principles
- JSON for request/response bodies
- ISO 8601 for datetime formats
- snake_case for property names
- HTTP status codes: 200 (OK), 201 (Created), 400 (Bad Request), 
  401 (Unauthorized), 403 (Forbidden), 404 (Not Found), 500 (Error)

### 9.4 Hardware Specifications

#### 9.4.1 IoT Sensor Requirements

| Sensor Type | Model Recommendation | Quantity | Unit Cost (BDT) | Total Cost |
|-------------|---------------------|----------|-----------------|------------|
| Temperature/Humidity | DHT22 | 50 | 500 | 25,000 |
| Milk Flow Meter | Electromagnetic Flow Meter | 20 | 45,000 | 900,000 |
| GPS Collar | LoRaWAN GPS Tracker | 200 | 3,500 | 700,000 |
| pH Sensor | Industrial pH Probe | 10 | 12,000 | 120,000 |
| Weight Scale | Digital Load Cell | 15 | 25,000 | 375,000 |
| **TOTAL** | | **295** | | **2,120,000** |

#### 9.4.2 Server Hardware Specifications

| Component | Specification | Quantity | Purpose |
|-----------|--------------|----------|---------|
| Application Server | 8 vCPU, 32GB RAM, 200GB SSD | 4 | Web/API hosting |
| Database Server (Primary) | 16 vCPU, 64GB RAM, 1TB NVMe | 1 | PostgreSQL primary |
| Database Server (Replica) | 16 vCPU, 64GB RAM, 1TB NVMe | 2 | Read replicas |
| TimescaleDB Server | 12 vCPU, 48GB RAM, 2TB SSD | 2 | IoT time-series |
| Redis Cluster Node | 4 vCPU, 16GB RAM | 3 | Caching/Sessions |
| Load Balancer | AWS ALB / NGINX Plus | 2 | Traffic distribution |

### 9.5 Sign-off Sheet

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Project Sponsor | | | |
| Project Manager | | | |
| Technical Lead | | | |
| Senior Odoo Developer | | | |
| Full-Stack Developer | | | |
| Mobile App Developer | | | |
| QA Lead | | | |
| Business Stakeholder | | | |

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 4.0.0 | 2026-02-02 | Smart Dairy IT Team | Initial Phase 4 documentation |
| | | | |
| | | | |

---

*End of Document*

**Smart Dairy Digital Transformation Initiative**  
**Phase 4 - Smart Portal + ERP System Implementation**  
**Document Version 4.0.0 | February 2026**
