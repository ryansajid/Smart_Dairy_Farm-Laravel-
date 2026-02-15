# Phase 8: Operations - Payment & Logistics — Index & Executive Summary

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field                  | Details                                                                    |
| ---------------------- | -------------------------------------------------------------------------- |
| **Document Title**     | Phase 8: Operations - Payment & Logistics — Index & Executive Summary      |
| **Document ID**        | SD-PHASE8-IDX-001                                                          |
| **Version**            | 1.0.0                                                                      |
| **Date Created**       | 2026-02-04                                                                 |
| **Last Updated**       | 2026-02-04                                                                 |
| **Status**             | Draft — Pending Review                                                     |
| **Classification**     | Internal — Confidential                                                    |
| **Project**            | Smart Dairy Digital Smart Portal + ERP System                              |
| **Phase**              | Phase 8: Operations - Payment & Logistics (Days 451–550)                   |
| **Platform**           | Odoo 19 CE / Python 3.11+ / PostgreSQL 16 / bKash / Nagad / SSLCommerz     |
| **Organization**       | Smart Dairy Ltd., a subsidiary of Smart Group                              |
| **Budget Allocation**  | BDT 2.00 Crore (of BDT 7 Crore Year 1 total)                               |

### Authors & Contributors

| Role                        | Name / Designation               | Responsibility                                      |
| --------------------------- | -------------------------------- | --------------------------------------------------- |
| Project Sponsor             | Managing Director, Smart Group   | Strategic oversight, budget approval                |
| Project Manager             | PM — Smart Dairy IT Division     | Planning, coordination, reporting                   |
| Backend Lead (Dev 1)        | Senior Developer                 | Payment APIs, Reconciliation, Transaction Security  |
| Full-Stack Developer (Dev 2)| Mid-Senior Developer             | Courier APIs, SMS, Testing, DevOps                  |
| Frontend/Mobile Lead (Dev 3)| Senior Developer                 | Payment UI, Checkout, Driver App, GPS Tracking      |
| Technical Architect         | Solutions Architect              | Architecture review, tech decisions                 |
| QA Lead                     | Quality Assurance Engineer       | Test strategy, quality gates                        |
| Security Specialist         | InfoSec Engineer                 | PCI DSS compliance, payment security audit          |

### Approval History

| Version | Date       | Approved By       | Remarks                            |
| ------- | ---------- | ----------------- | ---------------------------------- |
| 0.1     | 2026-02-04 | —                 | Initial draft created              |
| 1.0     | TBD        | Project Sponsor   | Pending formal review and sign-off |

### Revision History

| Version | Date       | Author       | Changes                                 |
| ------- | ---------- | ------------ | --------------------------------------- |
| 0.1     | 2026-02-04 | Project Team | Initial document creation               |
| 1.0     | TBD        | Project Team | Incorporates review feedback, finalized |

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Strategic Alignment](#2-strategic-alignment)
3. [Phase 8 Scope Statement](#3-phase-8-scope-statement)
4. [Milestone Index Table](#4-milestone-index-table)
5. [Technology Stack Summary](#5-technology-stack-summary)
6. [Team Structure & Workload Distribution](#6-team-structure--workload-distribution)
7. [Requirement Traceability Summary](#7-requirement-traceability-summary)
8. [Phase 8 Key Deliverables](#8-phase-8-key-deliverables)
9. [Success Criteria & Exit Gates](#9-success-criteria--exit-gates)
10. [Risk Register](#10-risk-register)
11. [Dependencies](#11-dependencies)
12. [Quality Assurance Framework](#12-quality-assurance-framework)
13. [Communication & Reporting](#13-communication--reporting)
14. [Phase 8 to Phase 9 Transition Criteria](#14-phase-8-to-phase-9-transition-criteria)
15. [Appendix A: Glossary](#15-appendix-a-glossary)
16. [Appendix B: Reference Documents](#16-appendix-b-reference-documents)

---

## 1. Executive Summary

### 1.1 Purpose of This Document

This document serves as the master index and executive summary for **Phase 8: Operations - Payment & Logistics** of the Smart Dairy Digital Smart Portal + ERP System project. It provides a comprehensive overview of Days 451-550 of implementation, organized into 10 milestones of 10 days each. The document is intended for project sponsors, the development team, technical architects, and all stakeholders who require a consolidated view of Phase 8 scope, deliverables, timelines, and governance.

Phase 8 represents a critical operational phase that implements the payment processing backbone and logistics management infrastructure for Smart Dairy's e-commerce platform. Building upon the B2C e-commerce features established in Phase 7, this phase focuses on integrating Bangladesh-specific payment gateways, delivery zone management, courier API integrations, and customer notification systems.

### 1.2 Previous Phases Completion Summary

**Phase 1: Foundation - Infrastructure & Core Setup (Days 1-50)** established:

| Component                  | Status   | Key Outcomes                                           |
| -------------------------- | -------- | ------------------------------------------------------ |
| Development Environment    | Complete | Docker-based stack with CI/CD, Ubuntu 24.04 LTS        |
| Core Odoo Modules          | Complete | 15+ modules installed and configured                   |
| Database Architecture      | Complete | PostgreSQL 16, TimescaleDB, Redis 7 caching            |
| Monitoring Infrastructure  | Complete | Prometheus, Grafana, ELK stack operational             |
| Documentation Standards    | Complete | Coding standards, API guidelines established           |

**Phase 2: Foundation - Database & Security (Days 51-100)** delivered:

| Component                  | Status   | Key Outcomes                                           |
| -------------------------- | -------- | ------------------------------------------------------ |
| Security Architecture      | Complete | Multi-layered security design                          |
| IAM Implementation         | Complete | RBAC with dairy-specific roles                         |
| Data Encryption            | Complete | AES-256 at rest, TLS 1.3 in transit                    |
| API Security               | Complete | OAuth 2.0, JWT tokens, rate limiting                   |
| Database Security          | Complete | Row-level security, audit logging                      |
| High Availability          | Complete | Streaming replication, failover procedures             |
| Compliance Framework       | Complete | GDPR-ready, Bangladesh Data Protection Act compliant   |

**Phase 3: Foundation - ERP Core Configuration (Days 101-150)** delivered:

| Component                  | Status   | Key Outcomes                                           |
| -------------------------- | -------- | ------------------------------------------------------ |
| Manufacturing MRP          | Complete | 6 dairy product BOMs with yield tracking               |
| Quality Management         | Complete | QMS with COA generation, BSTI compliance               |
| Advanced Inventory         | Complete | FEFO, lot tracking, 99.5% accuracy                     |
| Bangladesh Localization    | Complete | VAT, Mushak forms, Bengali 95%+ translation            |
| CRM Configuration          | Complete | B2B/B2C segmentation, Customer 360 view                |
| B2B Portal Foundation      | Complete | Tiered pricing, credit management                      |
| Payment Gateways           | Complete | bKash, Nagad, Rocket, SSLCommerz integration           |
| Reporting & BI             | Complete | Executive dashboards, scheduled reports                |
| Integration Middleware     | Complete | API Gateway, SMS/Email integration                     |

**Phase 4: Foundation - Public Website & CMS (Days 151-200)** delivered:

| Component                  | Status   | Key Outcomes                                           |
| -------------------------- | -------- | ------------------------------------------------------ |
| Design System              | Complete | Smart Dairy brand identity, component library          |
| CMS Configuration          | Complete | Content workflows, media library, templates            |
| Homepage & Landing Pages   | Complete | Hero sections, product highlights, CTAs                |
| Product Catalog            | Complete | 50+ dairy products with specifications                 |
| Company Information        | Complete | About, team, certifications, CSR pages                 |
| Blog & News                | Complete | Article management, categories, SEO                    |
| Contact & Support          | Complete | Forms, FAQ, chatbot foundation                         |
| SEO & Performance          | Complete | Lighthouse >90, <2s load time, structured data         |
| Multilingual Support       | Complete | English & Bangla with language switcher                |
| Launch Preparation         | Complete | Testing complete, analytics configured                 |

**Phase 5: Operations - Farm Management Foundation (Days 201-250)** delivered:

| Component                  | Status   | Key Outcomes                                           |
| -------------------------- | -------- | ------------------------------------------------------ |
| Herd Management Module     | Complete | 1000+ animal registration, RFID integration            |
| Health Management System   | Complete | Vaccination schedules, treatment protocols             |
| Breeding & Reproduction    | Complete | Heat detection, AI scheduling, pregnancy tracking      |
| Milk Production Tracking   | Complete | Daily yield, quality parameters, lactation curves      |
| Feed Management            | Complete | Ration planning, cost analysis, inventory              |
| Farm Analytics Dashboard   | Complete | KPI dashboards, trend analysis, alerts                 |
| Mobile API Foundation      | Complete | REST API endpoints for mobile integration              |
| Veterinary Module          | Complete | Appointments, treatments, prescriptions                |
| Farm Reporting System      | Complete | PDF/Excel exports, scheduled reports                   |
| Integration Testing        | Complete | >80% coverage, UAT sign-off achieved                   |

**Phase 6: Operations - Mobile App Foundation (Days 251-350)** delivered:

| Component                  | Status   | Key Outcomes                                           |
| -------------------------- | -------- | ------------------------------------------------------ |
| Flutter Monorepo           | Complete | Shared packages for all three mobile apps              |
| Customer App Authentication| Complete | OTP, social login, biometric authentication            |
| Customer App Catalog       | Complete | Product browsing with offline cache                    |
| Customer App Cart/Checkout | Complete | bKash, Nagad, Rocket, SSLCommerz integration           |
| Customer App Orders        | Complete | Order tracking, push notifications                     |
| Field Sales App            | Complete | Customer management, route optimization                |
| Farmer App                 | Complete | RFID scanning, Bangla voice input                      |
| Mobile Testing             | Complete | >80% coverage, app store submissions                   |

**Phase 7: Operations - B2C E-Commerce Core (Days 351-450)** delivered:

| Component                  | Status   | Key Outcomes                                           |
| -------------------------- | -------- | ------------------------------------------------------ |
| Product Catalog Enhancement| Complete | Elasticsearch search, AI recommendations               |
| Customer Account Management| Complete | Dashboard, loyalty program, 2FA                        |
| Shopping Experience        | Complete | Quick cart, bundles, wishlists, stock alerts           |
| Checkout Optimization      | Complete | Multi-step checkout, promo codes, one-click            |
| Order Management           | Complete | Real-time tracking, returns, refunds                   |
| Wishlist & Favorites       | Complete | Multiple lists, price alerts, sharing                  |
| Product Reviews & Ratings  | Complete | Verified reviews, moderation, seller responses         |
| Email Marketing            | Complete | Newsletter, abandoned cart, campaigns                  |
| Customer Support Chat      | Complete | Live chat, chatbot, FAQ portal                         |
| E-commerce Testing         | Complete | >80% coverage, UAT sign-off achieved                   |

### 1.3 Phase 8 Overview

**Phase 8: Operations - Payment & Logistics** is structured into two logical parts:

**Part A — Payment Integration (Days 451–500):**

- Implement bKash Tokenized Checkout with full API integration
- Integrate Nagad Merchant Checkout with encryption
- Add Rocket (DBBL) mobile banking integration
- Configure SSLCommerz for card payments with 3D Secure
- Build Cash on Delivery workflow with OTP verification
- Develop automated payment reconciliation engine

**Part B — Logistics & Notifications (Days 501–550):**

- Create delivery zone management with geo-fencing
- Implement route optimization using VRP algorithms
- Integrate GPS tracking for real-time delivery updates
- Build courier API integrations (Pathao, RedX, Paperfly)
- Develop SMS notification system for all stages
- Execute comprehensive payment and logistics testing

### 1.4 Investment & Budget Context

Phase 8 is allocated **BDT 2.00 Crore** from the total Year 1 budget of **BDT 7 Crore**. This represents approximately 28.6% of the annual budget, reflecting the critical importance of payment processing and logistics infrastructure for e-commerce operations.

| Category                           | Allocation (BDT) | Percentage |
| ---------------------------------- | ---------------- | ---------- |
| Developer Salaries (3.3 months)    | 95,00,000        | 47.5%      |
| Payment Gateway Integration Fees   | 15,00,000        | 7.5%       |
| Courier API Integration            | 10,00,000        | 5.0%       |
| SMS Gateway Credits                | 8,00,000         | 4.0%       |
| GPS/Maps API Costs                 | 12,00,000        | 6.0%       |
| Security Audit (PCI DSS)           | 15,00,000        | 7.5%       |
| Cloud Infrastructure (API scaling) | 18,00,000        | 9.0%       |
| Testing & QA Infrastructure        | 12,00,000        | 6.0%       |
| Training & Documentation           | 5,00,000         | 2.5%       |
| Contingency Reserve (5%)           | 10,00,000        | 5.0%       |
| **Total**                          | **2,00,00,000**  | **100%**   |

### 1.5 Expected Outcomes

| Metric                    | Target          | Business Impact                            |
| ------------------------- | --------------- | ------------------------------------------ |
| Payment Success Rate      | > 98%           | Reduced cart abandonment, revenue increase |
| Payment Processing Time   | < 2 minutes     | Better customer experience                 |
| Refund Processing Time    | < 24 hours      | Customer satisfaction improvement          |
| On-time Delivery Rate     | > 95%           | Customer retention, fewer complaints       |
| Delivery Cost Reduction   | > 30%           | Improved margins through route optimization|
| SMS Delivery Rate         | > 98%           | Better customer communication              |
| COD Fraud Rate            | < 0.5%          | Reduced financial losses                   |
| Reconciliation Accuracy   | > 99.9%         | Financial control, audit compliance        |

### 1.6 Critical Success Factors

1. **Payment Gateway Stability**: All payment providers (bKash, Nagad, Rocket, SSLCommerz) operational with <1% failure rate
2. **PCI DSS Compliance**: Zero security incidents, successful compliance audit
3. **Courier Integration**: 3+ courier partners fully integrated with auto-selection
4. **Real-time Tracking**: GPS updates every 30 seconds during active deliveries
5. **Reconciliation Automation**: Daily automated matching with <0.01% discrepancy
6. **COD Fraud Prevention**: OTP verification and fraud scoring operational
7. **SMS Reliability**: 98%+ delivery rate for all notification types

---

## 2. Strategic Alignment

### 2.1 Business Model Alignment

Phase 8 directly enables Smart Dairy's e-commerce revenue model across all four business verticals:

| Vertical               | Phase 8 Contribution                                    |
| ---------------------- | ------------------------------------------------------- |
| **B2C E-commerce**     | Complete payment processing, delivery to customers      |
| **B2B Wholesale**      | Credit terms, invoice payments, bulk delivery           |
| **Subscription**       | Recurring payment tokenization, scheduled deliveries    |
| **Farm-to-Table**      | COD for fresh deliveries, express delivery options      |

### 2.2 Revenue Enablement

| Revenue Stream             | Phase 8 Enabler                                         |
| -------------------------- | ------------------------------------------------------- |
| Online Sales               | Multi-gateway payment processing                        |
| Subscription Revenue       | Tokenized recurring payments                            |
| Delivery Fees              | Zone-based delivery fee calculation                     |
| Express Delivery Premium   | Time-slot based premium delivery                        |
| COD Collection             | Secure COD with OTP verification                        |

### 2.3 Operational Excellence Targets

| Metric                    | Current | Phase 8 Target | Improvement |
| ------------------------- | ------- | -------------- | ----------- |
| Payment Success Rate      | 92%     | 98%            | +6%         |
| Order-to-Delivery Time    | 72h     | 24h            | -67%        |
| Delivery Cost per Order   | BDT 80  | BDT 55         | -31%        |
| Customer Complaints       | 5%      | 2%             | -60%        |
| Manual Reconciliation     | 100%    | 5%             | -95%        |

### 2.4 Digital Transformation Roadmap Position

```
Phase 1-4: FOUNDATION
├─ Infrastructure, Security, ERP Core, Website
│
Phase 5-8: OPERATIONS ← CURRENT POSITION (Phase 8)
├─ Farm Management, Mobile Apps, E-commerce, Payment & Logistics
│
Phase 9-12: COMMERCE
├─ B2B Portal, IoT Integration, Analytics, Subscriptions
│
Phase 13-15: OPTIMIZATION
└─ Performance, Testing, Deployment
```

---

## 3. Phase 8 Scope Statement

### 3.1 In-Scope Items

#### 3.1.1 Milestone 71: bKash Payment Integration (Days 451-460)

- bKash Tokenized Checkout API integration
- Token Grant, Create Payment, Execute Payment flows
- Payment query and status checking
- Refund API implementation
- Webhook handler for callbacks
- Tokenization for saved payment methods
- bKash payment UI component
- Unit and integration tests

#### 3.1.2 Milestone 72: Nagad Payment Integration (Days 461-470)

- Nagad Merchant Checkout API configuration
- Public/Private key encryption setup
- Initialize and Complete Checkout flows
- Payment verification and callback handling
- Refund processing
- Encrypted payload generation
- Nagad payment UI component
- Testing suite

#### 3.1.3 Milestone 73: Rocket & SSLCommerz Integration (Days 471-480)

- Rocket (DBBL) mobile banking API integration
- Rocket payment flow implementation
- SSLCommerz session initialization
- Card payment processing (Visa, MasterCard, Amex)
- 3D Secure authentication handling
- IPN (Instant Payment Notification) webhooks
- Multi-gateway payment selector UI
- Saved card tokenization

#### 3.1.4 Milestone 74: COD & Payment Processing (Days 481-490)

- Cash on Delivery payment method
- COD order validation rules
- OTP verification for COD orders
- COD limit management (max order value)
- Fraud scoring system
- COD fee calculation
- Driver collection workflow
- Payment method unified API

#### 3.1.5 Milestone 75: Payment Reconciliation (Days 491-500)

- Reconciliation engine architecture
- Bank statement import (CSV, MT940)
- Automated transaction matching
- Discrepancy detection and alerting
- Manual reconciliation queue
- Settlement report generation
- Provider-wise reconciliation dashboard
- Audit trail and logging

#### 3.1.6 Milestone 76: Delivery Zone Management (Days 501-510)

- Delivery zone model and configuration
- Zone polygon definition (geo-fencing)
- Delivery fee calculation by zone
- Minimum order amount by zone
- Express delivery zone settings
- Zone-based delivery time estimation
- Zone selection UI in checkout
- Admin zone management interface

#### 3.1.7 Milestone 77: Route Optimization & GPS (Days 511-520)

- Route optimization algorithm (VRP solver)
- Driver assignment system
- GPS tracking integration
- Real-time location updates
- Delivery sequence optimization
- ETA calculation and updates
- Driver mobile app GPS features
- Customer tracking page

#### 3.1.8 Milestone 78: Courier API Integration (Days 521-530)

- Pathao Courier API integration
- RedX API integration
- Paperfly API integration
- Automatic courier selection algorithm
- Shipping label generation (PDF)
- Tracking number synchronization
- Courier webhook handlers
- Rate comparison dashboard

#### 3.1.9 Milestone 79: SMS Notification System (Days 531-540)

- SMS gateway integration (SSL Wireless)
- SMS template management
- Order confirmation SMS
- Payment status SMS
- Delivery status updates
- OTP delivery system
- Promotional SMS campaigns
- SMS delivery tracking and analytics

#### 3.1.10 Milestone 80: Payment & Logistics Testing (Days 541-550)

- Unit test suite for all payment providers
- Integration tests for payment flows
- E2E tests for checkout-to-delivery
- Payment security audit (PCI DSS)
- Load testing for payment APIs
- Courier API mock testing
- SMS delivery rate testing
- UAT with stakeholders

### 3.2 Out-of-Scope Items

The following items are explicitly excluded from Phase 8 and deferred to subsequent phases:

- B2B marketplace payment terms (Phase 9)
- IoT-triggered payments (Phase 10)
- Advanced fraud detection with ML (Phase 13)
- International payment processing
- Multi-currency support
- Cryptocurrency payments
- Buy Now Pay Later (BNPL)
- Drone delivery integration
- Cold chain monitoring IoT

### 3.3 Assumptions

1. Phase 7 e-commerce features are stable and documented
2. bKash, Nagad, Rocket sandbox accounts are provisioned
3. SSLCommerz merchant account is active
4. Courier partner contracts are signed (Pathao, RedX, Paperfly)
5. SMS gateway account is configured with sender ID approved
6. GPS/Maps API keys are provisioned
7. Development team has payment API integration experience
8. PCI DSS compliance requirements are understood
9. Finance team available for reconciliation testing
10. Delivery zones are defined by operations team

### 3.4 Constraints

1. **Regulatory Constraint**: Bangladesh Bank payment regulations compliance required
2. **Security Constraint**: PCI DSS Level 3 compliance mandatory
3. **Budget Constraint**: BDT 2.00 Crore maximum budget
4. **Timeline Constraint**: 100 working days (Days 451-550)
5. **Team Size**: 3 Full-Stack Developers
6. **Performance Constraint**: Payment API response <2s
7. **Availability Constraint**: Payment services 99.9% uptime
8. **Language Constraint**: SMS in Bangla and English
9. **Geographic Constraint**: Dhaka metro area for initial delivery zones
10. **COD Constraint**: Maximum COD order value BDT 10,000

---

## 4. Milestone Index Table

### 4.1 Part A — Payment Integration (Days 451-500)

| Milestone | Name                         | Days      | Duration | Primary Focus               | Key Deliverables                         |
| --------- | ---------------------------- | --------- | -------- | --------------------------- | ---------------------------------------- |
| 71        | bKash Payment Integration    | 451-460   | 10 days  | bKash API, tokenization     | Payment flow, webhooks, refunds          |
| 72        | Nagad Payment Integration    | 461-470   | 10 days  | Nagad API, encryption       | Checkout flow, verification              |
| 73        | Rocket & SSLCommerz          | 471-480   | 10 days  | Cards, mobile banking       | 3D Secure, multi-gateway UI              |
| 74        | COD & Payment Processing     | 481-490   | 10 days  | COD, fraud prevention       | OTP verification, fraud scoring          |
| 75        | Payment Reconciliation       | 491-500   | 10 days  | Auto-reconciliation         | Bank import, matching, reports           |

### 4.2 Part B — Logistics & Notifications (Days 501-550)

| Milestone | Name                         | Days      | Duration | Primary Focus               | Key Deliverables                         |
| --------- | ---------------------------- | --------- | -------- | --------------------------- | ---------------------------------------- |
| 76        | Delivery Zone Management     | 501-510   | 10 days  | Zones, fees                 | Geo-fencing, fee calculation             |
| 77        | Route Optimization & GPS     | 511-520   | 10 days  | Routes, tracking            | VRP solver, GPS integration              |
| 78        | Courier API Integration      | 521-530   | 10 days  | Pathao, RedX, Paperfly      | Auto-selection, labels, tracking         |
| 79        | SMS Notification System      | 531-540   | 10 days  | SMS gateway                 | Templates, OTP, delivery alerts          |
| 80        | Payment & Logistics Testing  | 541-550   | 10 days  | QA, UAT                     | Security audit, load testing, sign-off   |

### 4.3 Milestone Documents Reference

| Milestone | Document Name                                           | Description                              |
| --------- | ------------------------------------------------------- | ---------------------------------------- |
| 71        | `Milestone_71_bKash_Payment_Integration.md`             | bKash API, tokenization, webhooks        |
| 72        | `Milestone_72_Nagad_Payment_Integration.md`             | Nagad encryption, checkout flow          |
| 73        | `Milestone_73_Rocket_SSLCommerz_Integration.md`         | Cards, mobile banking, 3D Secure         |
| 74        | `Milestone_74_COD_Payment_Processing.md`                | COD workflow, OTP, fraud prevention      |
| 75        | `Milestone_75_Payment_Reconciliation.md`                | Auto-reconciliation, bank import         |
| 76        | `Milestone_76_Delivery_Zone_Management.md`              | Zones, fees, geo-fencing                 |
| 77        | `Milestone_77_Route_Optimization_GPS.md`                | VRP solver, GPS tracking                 |
| 78        | `Milestone_78_Courier_API_Integration.md`               | Pathao, RedX, Paperfly APIs              |
| 79        | `Milestone_79_SMS_Notification_System.md`               | SMS gateway, templates, OTP              |
| 80        | `Milestone_80_Payment_Logistics_Testing.md`             | Testing, UAT, documentation              |

---

## 5. Technology Stack Summary

### 5.1 Core Payment Technologies

| Component             | Technology           | Version        | Purpose                              |
| --------------------- | -------------------- | -------------- | ------------------------------------ |
| ERP Framework         | Odoo CE              | 19.0           | Core payment and business logic      |
| Programming Language  | Python               | 3.11+          | Backend development                  |
| Frontend Framework    | OWL                  | 2.0            | Payment UI components                |
| Database              | PostgreSQL           | 16+            | Transaction storage                  |
| Cache Layer           | Redis                | 7.2+           | Session, payment state cache         |
| Web Server            | Nginx                | 1.24+          | SSL termination, reverse proxy       |
| Task Queue            | Celery               | 5.3+           | Async payment processing             |

### 5.2 Payment Gateways

| Gateway               | API Version          | Protocol       | Purpose                              |
| --------------------- | -------------------- | -------------- | ------------------------------------ |
| bKash                 | v1.2.0-beta          | REST/JSON      | Mobile financial service payments    |
| Nagad                 | v2.0                 | REST/JSON      | Mobile financial service payments    |
| Rocket (DBBL)         | v1.0                 | REST/JSON      | Mobile banking payments              |
| SSLCommerz            | REST                 | POST/JSON      | Card payments (Visa, MC, Amex)       |

### 5.3 Courier APIs

| Courier               | API Version          | Capabilities   | Coverage                             |
| --------------------- | -------------------- | -------------- | ------------------------------------ |
| Pathao Courier        | v1                   | Full CRUD      | Dhaka, Chittagong, major cities      |
| RedX                  | v1                   | Full CRUD      | Nationwide                           |
| Paperfly              | v1                   | Full CRUD      | Dhaka metro, Chittagong              |

### 5.4 SMS Gateway

| Provider              | Protocol             | Delivery Rate  | Cost/SMS                             |
| --------------------- | -------------------- | -------------- | ------------------------------------ |
| SSL Wireless          | REST API             | 98%            | BDT 0.25                             |
| Grameenphone SMS      | SMPP                 | 99%            | BDT 0.30                             |

### 5.5 GPS & Maps

| Service               | Purpose              | API                                  |
| --------------------- | -------------------- | ------------------------------------ |
| Google Maps Platform  | Geocoding, routing   | Directions, Distance Matrix, Places  |
| OpenStreetMap         | Map display          | Tile server for driver app           |

### 5.6 Route Optimization

| Library               | Purpose              | Algorithm                            |
| --------------------- | -------------------- | ------------------------------------ |
| Google OR-Tools       | VRP solving          | Constraint-based optimization        |
| OSRM                  | Routing engine       | Shortest path calculation            |

### 5.7 Development Tools

| Tool                  | Purpose                                  | Usage                                |
| --------------------- | ---------------------------------------- | ------------------------------------ |
| VS Code               | Primary IDE                              | Python, JavaScript, Dart development |
| Docker                | Containerization                         | Payment service isolation            |
| Git                   | Version control                          | Code management, collaboration       |
| GitHub Actions        | CI/CD                                    | Automated testing and deployment     |
| Postman               | API testing                              | Payment API documentation/testing    |
| ngrok                 | Webhook testing                          | Payment callback testing locally     |
| Locust                | Load testing                             | Payment API performance testing      |

---

## 6. Team Structure & Workload Distribution

### 6.1 Developer Roles

| Role                       | Specialization                          | Primary Focus in Phase 8             |
| -------------------------- | --------------------------------------- | ------------------------------------ |
| **Dev 1 (Backend Lead)**   | Python, Odoo, Payment APIs, Security    | Payment gateway integration, reconciliation |
| **Dev 2 (Full-Stack)**     | DevOps, Courier APIs, SMS, Testing      | Courier integration, SMS, testing    |
| **Dev 3 (Frontend Lead)**  | OWL, Flutter, GPS, UI/UX               | Payment UI, checkout, driver app GPS |

### 6.2 Weekly Schedule Template

| Time Slot    | Monday          | Tuesday         | Wednesday       | Thursday        | Friday          |
| ------------ | --------------- | --------------- | --------------- | --------------- | --------------- |
| 09:00-09:30  | Daily Standup   | Daily Standup   | Daily Standup   | Daily Standup   | Daily Standup   |
| 09:30-12:00  | Development     | Development     | Development     | Development     | Development     |
| 12:00-13:00  | Lunch           | Lunch           | Lunch           | Lunch           | Lunch           |
| 13:00-14:00  | Code Review     | Development     | Sprint Demo     | Development     | Retrospective   |
| 14:00-17:00  | Development     | Development     | Development     | Development     | Documentation   |
| 17:00-18:00  | Testing         | Testing         | Testing         | Testing         | Weekly Report   |

### 6.3 Workload Distribution by Milestone

| Milestone | Dev 1 (Backend) | Dev 2 (Full-Stack) | Dev 3 (Frontend) |
| --------- | --------------- | ------------------ | ---------------- |
| 71 - bKash Integration       | 50% | 20% | 30% |
| 72 - Nagad Integration       | 50% | 20% | 30% |
| 73 - Rocket & SSLCommerz     | 50% | 25% | 25% |
| 74 - COD & Payment Processing| 40% | 30% | 30% |
| 75 - Payment Reconciliation  | 55% | 30% | 15% |
| 76 - Delivery Zones          | 40% | 25% | 35% |
| 77 - Route Optimization      | 35% | 30% | 35% |
| 78 - Courier Integration     | 35% | 45% | 20% |
| 79 - SMS Notifications       | 30% | 50% | 20% |
| 80 - Testing & UAT           | 30% | 45% | 25% |
| **Average**                  | **41.5%** | **32%** | **26.5%** |

### 6.4 Developer Daily Hour Allocation

**Dev 1 - Backend Lead (8h/day):**
- Payment API integration: 3h
- Transaction processing logic: 2h
- Security implementation: 1.5h
- Code review: 1h
- Documentation: 0.5h

**Dev 2 - Full-Stack (8h/day):**
- Courier API integration: 2h
- SMS gateway integration: 1.5h
- Testing and QA: 2h
- DevOps and deployment: 1.5h
- Code review: 0.5h
- Documentation: 0.5h

**Dev 3 - Frontend Lead (8h/day):**
- Payment UI development: 3h
- Checkout flow integration: 2h
- Driver app GPS features: 2h
- UI/UX polish: 0.5h
- Code review: 0.5h

---

## 7. Requirement Traceability Summary

### 7.1 RFP Requirements Mapping

| Req ID           | Description                              | Milestone | Status  |
| ---------------- | ---------------------------------------- | --------- | ------- |
| ECOM-PAY-001     | bKash payment integration                | 71        | Planned |
| ECOM-PAY-002     | Nagad payment integration                | 72        | Planned |
| ECOM-PAY-003     | Rocket payment integration               | 73        | Planned |
| ECOM-PAY-004     | Card payment (SSLCommerz)                | 73        | Planned |
| ECOM-PAY-005     | Cash on Delivery workflow                | 74        | Planned |
| ECOM-PAY-006     | Payment reconciliation                   | 75        | Planned |
| ECOM-LOG-001     | Delivery zone management                 | 76        | Planned |
| ECOM-LOG-002     | Route optimization                       | 77        | Planned |
| ECOM-LOG-003     | Courier integration                      | 78        | Planned |
| ECOM-LOG-004     | Real-time GPS tracking                   | 77        | Planned |
| ECOM-NOT-001     | SMS notifications                        | 79        | Planned |
| ECOM-NOT-002     | OTP delivery                             | 79        | Planned |

### 7.2 BRD Requirements Mapping

| Req ID        | Business Requirement                     | Milestone | Status  |
| ------------- | ---------------------------------------- | --------- | ------- |
| FR-CHK-002    | Multiple payment methods                 | 71-74     | Planned |
| FR-CHK-003    | Delivery management                      | 76-78     | Planned |
| FR-CHK-004    | Delivery scheduling                      | 76        | Planned |
| FR-CHK-005    | Order confirmation notification          | 79        | Planned |
| FR-CRM-005    | Route and delivery management            | 77        | Planned |
| BRD-PAY-001   | 98% payment success rate                 | 71-74     | Planned |
| BRD-PAY-002   | <24h refund processing                   | 71-74     | Planned |
| BRD-LOG-001   | 95% on-time delivery                     | 76-78     | Planned |
| BRD-LOG-002   | 30% delivery cost reduction              | 77        | Planned |

### 7.3 SRS Requirements Mapping

| Req ID        | Technical Requirement                    | Milestone | Status  |
| ------------- | ---------------------------------------- | --------- | ------- |
| SRS-PAY-001   | PCI DSS Level 3 compliance               | 71-74     | Planned |
| SRS-PAY-002   | Payment API response <2s                 | 71-74     | Planned |
| SRS-PAY-003   | Payment tokenization                     | 71-73     | Planned |
| SRS-PAY-004   | Webhook signature verification           | 71-74     | Planned |
| SRS-LOG-001   | GPS update frequency <30s                | 77        | Planned |
| SRS-LOG-002   | VRP optimization for 100+ stops          | 77        | Planned |
| SRS-LOG-003   | Courier API response <3s                 | 78        | Planned |
| SRS-SMS-001   | SMS delivery rate >98%                   | 79        | Planned |
| SRS-SMS-002   | OTP delivery <10s                        | 79        | Planned |
| SRS-TST-001   | >80% test coverage                       | 80        | Planned |

---

## 8. Phase 8 Key Deliverables

### 8.1 Payment Gateway Deliverables

1. bKash Tokenized Checkout API integration
2. bKash Token Grant, Create Payment, Execute Payment flows
3. bKash refund processing API
4. bKash webhook handler for payment callbacks
5. Nagad Merchant Checkout API integration
6. Nagad public/private key encryption
7. Nagad payment verification and callbacks
8. Rocket (DBBL) mobile banking integration
9. SSLCommerz card payment session management
10. SSLCommerz 3D Secure authentication
11. SSLCommerz IPN webhook handler
12. Multi-gateway payment selector UI

### 8.2 COD & Processing Deliverables

13. Cash on Delivery payment method configuration
14. COD order validation rules engine
15. COD OTP verification system
16. COD limit management by customer tier
17. Fraud scoring system with risk indicators
18. COD fee calculation by zone
19. Driver COD collection interface
20. Payment method unified API layer

### 8.3 Reconciliation Deliverables

21. Reconciliation engine architecture
22. Bank statement import (CSV, MT940 formats)
23. Automated transaction matching algorithm
24. Discrepancy detection with threshold alerts
25. Manual reconciliation queue interface
26. Settlement report generation (daily, weekly)
27. Provider-wise reconciliation dashboard
28. Audit trail logging for all reconciliation actions

### 8.4 Delivery Zone Deliverables

29. Delivery zone model with configuration
30. Zone polygon definition (GeoJSON)
31. Delivery fee calculation by zone
32. Minimum order amount by zone
33. Express delivery zone settings
34. Zone-based delivery time estimation
35. Zone selection UI in checkout
36. Admin zone management CRUD interface

### 8.5 Route & GPS Deliverables

37. Route optimization VRP solver integration
38. Driver assignment system
39. GPS tracking integration (Flutter)
40. Real-time location updates to server
41. Delivery sequence optimization
42. ETA calculation and customer updates
43. Driver mobile app navigation features
44. Customer order tracking map view

### 8.6 Courier Integration Deliverables

45. Pathao Courier API integration
46. RedX API integration
47. Paperfly API integration
48. Automatic courier selection algorithm
49. Shipping label PDF generation
50. Tracking number synchronization
51. Courier status webhook handlers
52. Rate comparison dashboard

### 8.7 SMS Notification Deliverables

53. SMS gateway integration (SSL Wireless)
54. SMS template management system
55. Order confirmation SMS
56. Payment success/failure SMS
57. Delivery pickup/dispatch SMS
58. Out for delivery SMS with driver contact
59. Delivery completion SMS
60. OTP delivery for COD verification
61. Promotional campaign SMS
62. SMS delivery analytics dashboard

### 8.8 Testing Deliverables

63. Unit test suite for payment providers (>80% coverage)
64. Integration tests for payment flows
65. E2E tests for checkout-to-delivery
66. Payment security audit report (PCI DSS)
67. Load testing results for payment APIs
68. Courier API mock testing suite
69. SMS delivery rate test results
70. UAT test cases and sign-off documentation

---

## 9. Success Criteria & Exit Gates

### 9.1 Milestone Exit Criteria

Each milestone must meet the following criteria before proceeding:

| Criteria                           | Target                                   |
| ---------------------------------- | ---------------------------------------- |
| Code complete                      | 100% of planned features implemented     |
| Unit test coverage                 | >80% for new code                        |
| Integration tests passing          | 100% pass rate                           |
| Code review completed              | All PRs reviewed and approved            |
| Documentation updated              | API docs, README, inline comments        |
| No critical bugs                   | Zero P0/P1 bugs open                     |
| Performance benchmarks met         | Within defined thresholds                |
| Security review passed             | No high/critical vulnerabilities         |

### 9.2 Phase Exit Criteria

| Criteria                           | Target                                   |
| ---------------------------------- | ---------------------------------------- |
| All milestones complete            | 10/10 milestones signed off              |
| Payment success rate               | >98% in production                       |
| On-time delivery rate              | >95% in testing                          |
| Reconciliation accuracy            | >99.9%                                   |
| PCI DSS compliance                 | Audit passed                             |
| UAT sign-off                       | Business stakeholder approval            |
| Load test passed                   | 1000 concurrent payments handled         |
| Documentation complete             | All technical docs finalized             |
| Training delivered                 | Operations team trained                  |
| Handover complete                  | Phase 9 team briefed                     |

### 9.3 Quality Metrics

| Metric                     | Target          | Measurement Method                      |
| -------------------------- | --------------- | --------------------------------------- |
| Code coverage              | >80%            | pytest-cov, coverage.py                 |
| Code quality score         | A rating        | SonarQube, CodeClimate                  |
| Bug density                | <2 per 1000 LOC | Static analysis + testing               |
| API response time (p95)    | <2s             | Locust, New Relic                       |
| Error rate                 | <1%             | Application monitoring                  |
| Security vulnerabilities   | 0 critical      | OWASP ZAP, security audit               |

---

## 10. Risk Register

### 10.1 High Priority Risks

| Risk ID | Description                      | Impact   | Probability | Mitigation Strategy                      |
| ------- | -------------------------------- | -------- | ----------- | ---------------------------------------- |
| R8-01   | bKash API downtime               | High     | Medium      | Implement failover to Nagad, retry logic |
| R8-02   | Payment callback failures        | High     | Medium      | Queue callbacks, manual reconciliation   |
| R8-03   | COD fraud (fake orders)          | High     | High        | OTP verification, fraud scoring          |
| R8-04   | PCI compliance violation         | Critical | Low         | Tokenization, no card storage            |
| R8-05   | Reconciliation discrepancy       | High     | Medium      | Automated alerts, manual review queue    |

### 10.2 Medium Priority Risks

| Risk ID | Description                      | Impact   | Probability | Mitigation Strategy                      |
| ------- | -------------------------------- | -------- | ----------- | ---------------------------------------- |
| R8-06   | GPS tracking accuracy issues     | Medium   | Medium      | Multiple GPS sources, cell tower fallback|
| R8-07   | Courier API rate limits          | Medium   | Low         | Request caching, throttling              |
| R8-08   | SMS delivery rate below target   | Medium   | Low         | Multiple SMS providers, retry logic      |
| R8-09   | Route optimization slow          | Medium   | Medium      | Pre-calculation, caching, async processing|
| R8-10   | Driver app battery drain         | Medium   | Medium      | Background location optimization         |

### 10.3 Low Priority Risks

| Risk ID | Description                      | Impact   | Probability | Mitigation Strategy                      |
| ------- | -------------------------------- | -------- | ----------- | ---------------------------------------- |
| R8-11   | Currency formatting errors       | Low      | Low         | Standardized BDT handling, validation    |
| R8-12   | Timezone handling issues         | Low      | Low         | UTC storage, BST display conversion      |
| R8-13   | Label printing failures          | Low      | Medium      | PDF fallback, retry mechanism            |

---

## 11. Dependencies

### 11.1 Internal Dependencies

| Dependency                         | Source Phase | Required For                            |
| ---------------------------------- | ------------ | --------------------------------------- |
| Customer authentication APIs       | Phase 6      | Payment authorization                   |
| Order management system            | Phase 7      | Payment linking, status updates         |
| Checkout flow                      | Phase 7      | Payment method selection                |
| Product catalog                    | Phase 7      | Order validation                        |
| Mobile app foundation              | Phase 6      | Driver app, customer tracking           |
| Email notification system          | Phase 7      | Payment confirmation emails             |

### 11.2 External Dependencies

| Dependency                         | Provider           | Required For                            |
| ---------------------------------- | ------------------ | --------------------------------------- |
| bKash Merchant API access          | bKash Limited      | Mobile payment processing               |
| Nagad Merchant API access          | Nagad Limited      | Mobile payment processing               |
| Rocket API access                  | DBBL               | Mobile banking payments                 |
| SSLCommerz Merchant account        | SSLCommerz         | Card payment processing                 |
| Pathao Merchant account            | Pathao             | Courier services                        |
| RedX Merchant account              | RedX Bangladesh    | Courier services                        |
| Paperfly Merchant account          | Paperfly           | Courier services                        |
| SSL Wireless account               | SSL Wireless       | SMS gateway                             |
| Google Maps API key                | Google             | Maps, routing, geocoding                |

### 11.3 Milestone Dependencies

```
Milestone 71 (bKash) ─────┐
                          │
Milestone 72 (Nagad) ─────┼──► Milestone 75 (Reconciliation)
                          │           │
Milestone 73 (Rocket/SSL)─┘           │
                                      │
Milestone 74 (COD) ───────────────────┘
        │
        └──► Milestone 79 (SMS - OTP for COD)

Milestone 76 (Zones) ─────┐
                          │
Milestone 77 (Routes/GPS)─┼──► Milestone 78 (Courier)
                          │           │
                          └───────────┼──► Milestone 79 (SMS - Delivery alerts)
                                      │
                                      └──► Milestone 80 (Testing)
```

---

## 12. Quality Assurance Framework

### 12.1 Testing Strategy

| Test Type         | Coverage Target | Tools                    | Frequency           |
| ----------------- | --------------- | ------------------------ | ------------------- |
| Unit Tests        | >80%            | pytest, unittest         | Every commit        |
| Integration Tests | 100% APIs       | pytest, requests         | Daily               |
| E2E Tests         | Critical flows  | Selenium, Cypress        | Pre-release         |
| Load Tests        | 1000 concurrent | Locust, K6               | Weekly              |
| Security Tests    | OWASP Top 10    | OWASP ZAP, Burp Suite    | Per milestone       |
| Smoke Tests       | Core functions  | Custom scripts           | Every deployment    |

### 12.2 Code Review Process

1. **Feature branch creation** from `develop`
2. **Self-review** before PR submission
3. **Automated checks** (lint, tests, coverage)
4. **Peer review** by at least one team member
5. **Security review** for payment-related code (mandatory)
6. **QA verification** in staging environment
7. **Merge to develop** after approval

### 12.3 Definition of Done

A feature is considered "Done" when:

- [ ] Code is complete and follows coding standards
- [ ] Unit tests written and passing (>80% coverage)
- [ ] Integration tests passing
- [ ] Code reviewed and approved
- [ ] Security review passed (for payment code)
- [ ] Documentation updated
- [ ] Deployed to staging and tested
- [ ] No critical/high bugs
- [ ] Product owner acceptance

---

## 13. Communication & Reporting

### 13.1 Meeting Schedule

| Meeting              | Frequency    | Participants                    | Purpose                         |
| -------------------- | ------------ | ------------------------------- | ------------------------------- |
| Daily Standup        | Daily        | Dev Team                        | Progress, blockers              |
| Sprint Planning      | Bi-weekly    | Dev Team, PM                    | Sprint scope definition         |
| Sprint Demo          | Bi-weekly    | Dev Team, PM, Stakeholders      | Feature demonstration           |
| Retrospective        | Bi-weekly    | Dev Team, PM                    | Process improvement             |
| Stakeholder Update   | Weekly       | PM, Business Stakeholders       | Progress report                 |
| Technical Review     | Weekly       | Dev Team, Architect             | Architecture decisions          |

### 13.2 Reporting Cadence

| Report                    | Frequency    | Audience               | Content                         |
| ------------------------- | ------------ | ---------------------- | ------------------------------- |
| Daily Status              | Daily        | PM                     | Tasks completed, blockers       |
| Sprint Report             | Bi-weekly    | Stakeholders           | Sprint achievements, metrics    |
| Monthly Executive Summary | Monthly      | Sponsor, Management    | Phase progress, risks, budget   |
| Quality Report            | Per milestone| QA Lead, PM            | Test results, coverage          |
| Security Report           | Per milestone| Security, PM           | Vulnerability assessment        |

### 13.3 Communication Channels

| Channel              | Purpose                                    | Response Time         |
| -------------------- | ------------------------------------------ | --------------------- |
| Slack #phase-8       | Day-to-day team communication              | <2 hours              |
| Email                | Formal communications, stakeholders        | <24 hours             |
| GitHub Issues        | Bug tracking, feature requests             | <4 hours              |
| Confluence           | Documentation, meeting notes               | N/A                   |
| Video Call           | Complex discussions, demos                 | Scheduled             |

---

## 14. Phase 8 to Phase 9 Transition Criteria

### 14.1 Technical Readiness

| Criteria                           | Requirement                              |
| ---------------------------------- | ---------------------------------------- |
| Payment APIs stable                | <1% failure rate over 7 days             |
| All payment gateways operational   | bKash, Nagad, Rocket, SSLCommerz live    |
| Reconciliation automated           | Daily auto-reconciliation running        |
| Courier integration complete       | 3+ couriers with auto-selection          |
| SMS system operational             | 98%+ delivery rate                       |
| GPS tracking functional            | Real-time updates working                |
| Documentation complete             | All APIs documented                      |

### 14.2 Business Readiness

| Criteria                           | Requirement                              |
| ---------------------------------- | ---------------------------------------- |
| UAT sign-off                       | Business stakeholder approval            |
| Finance team trained               | Reconciliation process understood        |
| Operations team trained            | Delivery zone management understood      |
| Customer support briefed           | Payment/delivery FAQ documented          |
| SOP documented                     | Payment and logistics procedures         |

### 14.3 Handover Items

1. Technical documentation package
2. API credentials and access matrix
3. Runbook for payment operations
4. Escalation procedures for payment failures
5. Courier partner contact matrix
6. SMS gateway management guide
7. Known issues and workarounds list
8. Phase 9 dependency brief

---

## 15. Appendix A: Glossary

| Term                    | Definition                                                  |
| ----------------------- | ----------------------------------------------------------- |
| **bKash**               | Leading mobile financial service provider in Bangladesh     |
| **Nagad**               | Mobile financial service by Bangladesh Post Office          |
| **Rocket**              | Mobile banking service by Dutch-Bangla Bank Limited         |
| **SSLCommerz**          | Payment gateway for card transactions in Bangladesh         |
| **COD**                 | Cash on Delivery - payment collected at delivery            |
| **OTP**                 | One-Time Password for verification                          |
| **PCI DSS**             | Payment Card Industry Data Security Standard                |
| **VRP**                 | Vehicle Routing Problem - optimization algorithm            |
| **IPN**                 | Instant Payment Notification - webhook callback             |
| **3D Secure**           | Card authentication protocol (Verified by Visa, etc.)       |
| **Tokenization**        | Replacing sensitive data with non-sensitive tokens          |
| **MT940**               | SWIFT standard for bank statement format                    |
| **Geo-fencing**         | Virtual geographic boundary using GPS/RFID                  |
| **ETA**                 | Estimated Time of Arrival                                   |
| **SMPP**                | Short Message Peer-to-Peer protocol for SMS                 |

---

## 16. Appendix B: Reference Documents

### 16.1 Project Documents

| Document                                    | Location                                   |
| ------------------------------------------- | ------------------------------------------ |
| Master Implementation Roadmap               | `Implemenattion_Roadmap/MASTER_Implementation_Roadmap.md` |
| Phase 7 Index                               | `Phase_7/Phase_7_Index_Executive_Summary.md` |
| RFP - B2C E-commerce                        | `docs/RFP/02_RFP_B2C_Ecommerce_Portal.md`  |
| BRD - Functional Requirements               | `docs/BRD/BRD_Part2_Functional_Requirements.md` |
| SRS - Technical Specifications              | `docs/SRS/SRS_Document.md`                 |

### 16.2 Technical Guides

| Guide                                       | Purpose                                    |
| ------------------------------------------- | ------------------------------------------ |
| bKash Integration Guide                     | bKash API implementation reference         |
| Nagad Integration Guide                     | Nagad API implementation reference         |
| SSLCommerz Documentation                    | Card payment integration                   |
| Pathao Merchant API Docs                    | Courier integration reference              |
| SSL Wireless API Guide                      | SMS gateway integration                    |
| Google OR-Tools Documentation               | Route optimization reference               |

### 16.3 Compliance Documents

| Document                                    | Purpose                                    |
| ------------------------------------------- | ------------------------------------------ |
| PCI DSS Requirements v4.0                   | Payment security compliance                |
| Bangladesh Bank Payment Guidelines          | Regulatory compliance                      |
| BTRC SMS Guidelines                         | SMS service regulations                    |

---

**Document End**

*Last Updated: 2026-02-04*
*Version: 1.0.0*
*Phase 8: Operations - Payment & Logistics*
