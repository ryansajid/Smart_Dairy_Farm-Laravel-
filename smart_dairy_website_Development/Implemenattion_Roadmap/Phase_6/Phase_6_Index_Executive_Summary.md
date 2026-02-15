# Phase 6: Operations - Mobile App Foundation — Index & Executive Summary

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field                  | Details                                                                    |
| ---------------------- | -------------------------------------------------------------------------- |
| **Document Title**     | Phase 6: Operations - Mobile App Foundation — Index & Executive Summary    |
| **Document ID**        | SD-PHASE6-IDX-001                                                          |
| **Version**            | 1.0.0                                                                      |
| **Date Created**       | 2026-02-03                                                                 |
| **Last Updated**       | 2026-02-03                                                                 |
| **Status**             | Draft — Pending Review                                                     |
| **Classification**     | Internal — Confidential                                                    |
| **Project**            | Smart Dairy Digital Smart Portal + ERP System                              |
| **Phase**              | Phase 6: Operations - Mobile App Foundation (Days 251–350)                 |
| **Platform**           | Flutter 3.16+ / Dart 3.2+ / Odoo 19 CE / PostgreSQL 16                     |
| **Organization**       | Smart Dairy Ltd., a subsidiary of Smart Group                              |
| **Budget Allocation**  | BDT 2.50 Crore (of BDT 7 Crore Year 1 total)                               |

### Authors & Contributors

| Role                        | Name / Designation               | Responsibility                            |
| --------------------------- | -------------------------------- | ----------------------------------------- |
| Project Sponsor             | Managing Director, Smart Group   | Strategic oversight, budget approval      |
| Project Manager             | PM — Smart Dairy IT Division     | Planning, coordination, reporting         |
| Backend Lead (Dev 1)        | Senior Developer                 | Odoo REST APIs, JWT Auth, Business Logic  |
| Full-Stack Developer (Dev 2)| Mid-Senior Developer             | CI/CD, Firebase, Payments, Testing        |
| Frontend/Mobile Lead (Dev 3)| Senior Developer                 | Flutter Apps, UI/UX, Offline Architecture |
| Technical Architect         | Solutions Architect              | Architecture review, tech decisions       |
| QA Lead                     | Quality Assurance Engineer       | Test strategy, quality gates              |
| Mobile UX Expert            | UI/UX Designer                   | Mobile design patterns, accessibility     |

### Approval History

| Version | Date       | Approved By       | Remarks                            |
| ------- | ---------- | ----------------- | ---------------------------------- |
| 0.1     | 2026-02-03 | —                 | Initial draft created              |
| 1.0     | TBD        | Project Sponsor   | Pending formal review and sign-off |

### Revision History

| Version | Date       | Author       | Changes                                 |
| ------- | ---------- | ------------ | --------------------------------------- |
| 0.1     | 2026-02-03 | Project Team | Initial document creation               |
| 1.0     | TBD        | Project Team | Incorporates review feedback, finalized |

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Strategic Alignment](#2-strategic-alignment)
3. [Phase 6 Scope Statement](#3-phase-6-scope-statement)
4. [Milestone Index Table](#4-milestone-index-table)
5. [Technology Stack Summary](#5-technology-stack-summary)
6. [Team Structure & Workload Distribution](#6-team-structure--workload-distribution)
7. [Requirement Traceability Summary](#7-requirement-traceability-summary)
8. [Phase 6 Key Deliverables](#8-phase-6-key-deliverables)
9. [Success Criteria & Exit Gates](#9-success-criteria--exit-gates)
10. [Risk Register](#10-risk-register)
11. [Dependencies](#11-dependencies)
12. [Quality Assurance Framework](#12-quality-assurance-framework)
13. [Communication & Reporting](#13-communication--reporting)
14. [Phase 6 to Phase 7 Transition Criteria](#14-phase-6-to-phase-7-transition-criteria)
15. [Appendix A: Glossary](#15-appendix-a-glossary)
16. [Appendix B: Reference Documents](#16-appendix-b-reference-documents)

---

## 1. Executive Summary

### 1.1 Purpose of This Document

This document serves as the master index and executive summary for **Phase 6: Operations - Mobile App Foundation** of the Smart Dairy Digital Smart Portal + ERP System project. It provides a comprehensive overview of Days 251-350 of implementation, organized into 10 milestones of 10 days each. The document is intended for project sponsors, the development team, technical architects, and all stakeholders who require a consolidated view of Phase 6 scope, deliverables, timelines, and governance.

Phase 6 represents a critical mobile enablement phase that extends Smart Dairy's digital platform to mobile devices. Building upon the comprehensive farm management system established in Phase 5, this phase focuses on developing three Flutter-based mobile applications that serve distinct user segments: consumers (B2C Customer App), sales representatives (Field Sales App), and farm workers (Farmer App).

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

### 1.3 Phase 6 Overview

**Phase 6: Operations - Mobile App Foundation** is structured into two logical parts:

**Part A — Customer & E-commerce Mobile (Days 251–300):**

- Establish Flutter monorepo architecture with shared packages for all three apps
- Implement comprehensive customer authentication (OTP, email, social login)
- Build product catalog browsing with offline-first caching
- Create shopping cart with real-time synchronization
- Integrate Bangladesh payment gateways (bKash, Nagad, Rocket, SSLCommerz)
- Deploy order tracking and push notifications

**Part B — Field Operations & Farmer Apps (Days 301–350):**

- Develop Field Sales App for route optimization and order taking
- Build Farmer App with simplified authentication for low-literacy users
- Implement RFID and barcode scanning for animal identification
- Create Bangla voice input for hands-free data entry
- Deploy comprehensive offline sync with conflict resolution
- Complete mobile app testing and app store submissions

### 1.4 Investment & Budget Context

Phase 6 is allocated **BDT 2.50 Crore** from the total Year 1 budget of **BDT 7 Crore**. This represents approximately 35.7% of the annual budget, reflecting the strategic importance of mobile channels for customer engagement and field operations efficiency.

| Category                           | Allocation (BDT) | Percentage |
| ---------------------------------- | ---------------- | ---------- |
| Developer Salaries (3.3 months)    | 99,00,000        | 39.6%      |
| Mobile UX/UI Design                | 18,00,000        | 7.2%       |
| Flutter Development Tools          | 8,00,000         | 3.2%       |
| Firebase Services (Annual)         | 12,00,000        | 4.8%       |
| Payment Gateway Integration        | 15,00,000        | 6.0%       |
| App Store Developer Accounts       | 50,000           | 0.2%       |
| Testing Devices (iOS/Android)      | 10,00,000        | 4.0%       |
| Cloud Infrastructure (API)         | 20,00,000        | 8.0%       |
| Third-Party APIs (Maps, STT)       | 12,00,000        | 4.8%       |
| QA & Testing Infrastructure        | 15,00,000        | 6.0%       |
| Training & Documentation           | 10,00,000        | 4.0%       |
| Marketing/ASO                      | 5,00,000         | 2.0%       |
| Contingency Reserve (10%)          | 25,50,000        | 10.2%      |
| **Total Phase 6 Budget**           | **2,50,00,000**  | **100%**   |

### 1.5 Expected Outcomes

Upon successful completion of Phase 6, the following outcomes will be achieved:

1. **Customer Mobile Engagement**: Feature-complete B2C mobile app enabling 50,000+ customers to browse, order, and track deliveries directly from their smartphones

2. **Field Sales Efficiency**: 40% improvement in field sales productivity through route optimization, offline order taking, and real-time customer data access

3. **Farm Data Digitization**: 100% digitization of field data entry through Farmer App with Bangla voice input, eliminating paper-based processes

4. **Offline Operations**: All three apps capable of full-day offline operation with intelligent sync when connectivity is restored

5. **Payment Flexibility**: Multiple payment options including bKash, Nagad, Rocket, and cards, supporting Bangladesh's mobile payment ecosystem

6. **Cross-Platform Reach**: Single Flutter codebase serving both iOS and Android platforms with native performance

7. **Real-Time Tracking**: Push notifications and real-time order tracking improving customer satisfaction to 90%+ NPS

8. **Voice Accessibility**: Bangla voice input achieving 90%+ accuracy for low-literacy farm workers

### 1.6 Critical Success Factors

- **Phase 5 API Stability**: All REST APIs from Phase 5 must be stable and documented
- **Flutter Team Expertise**: Dev 3 must have strong Flutter/Dart proficiency
- **Firebase Project Setup**: Firebase project must be configured before Day 251
- **Test Device Availability**: iOS and Android devices ready for testing
- **Payment Gateway Sandbox**: bKash, Nagad, Rocket sandbox accounts activated
- **Google Play & App Store Accounts**: Developer accounts registered
- **Network Testing Environment**: Ability to simulate poor network conditions
- **Stakeholder Mobile Access**: All reviewers have mobile devices for UAT

---

## 2. Strategic Alignment

### 2.1 Smart Dairy Business Model Alignment

Phase 6 directly enables all four of Smart Dairy's business verticals through mobile channel access:

| Vertical                    | Phase 6 Enablement                        | Key Mobile Features                           |
| --------------------------- | ----------------------------------------- | --------------------------------------------- |
| **Dairy Products**          | Customer App for direct B2C sales         | Product browsing, ordering, delivery tracking |
| **Livestock Trading**       | Farmer App for animal documentation       | RFID scanning, health records, photos         |
| **Equipment & Technology**  | Field Sales App for equipment demos       | Product catalog, quotation generation         |
| **Services & Consultancy**  | Appointment booking via mobile            | Service scheduling, payment, notifications    |

### 2.2 Revenue Enablement

Phase 6 mobile apps directly support Smart Dairy's revenue growth:

| Revenue Stream              | Phase 6 Enabler                           | Expected Impact                               |
| --------------------------- | ----------------------------------------- | --------------------------------------------- |
| B2C E-commerce Revenue      | Customer App                              | 20% of total revenue via mobile by Year 2     |
| Field Sales Orders          | Field Sales App                           | 40% increase in orders per sales rep          |
| Subscription Revenue        | Customer App subscription feature         | 10,000 active subscriptions by Year 2         |
| Service Booking Revenue     | Mobile appointment scheduling             | 50% of bookings via mobile                    |
| Reduced Operational Costs   | Farmer App data entry                     | 70% reduction in paper-based costs            |

### 2.3 Operational Excellence Targets

| Metric                      | Pre-Phase 6       | Phase 6 Target       | Phase 6 Enabler                |
| --------------------------- | ----------------- | -------------------- | ------------------------------ |
| Customer Ordering Channel   | Web only          | Web + Mobile         | Customer App                   |
| Field Sales Coverage        | 50 visits/day     | 80 visits/day        | Route optimization             |
| Order Entry Time            | 5 min/order       | 1 min/order          | Quick order feature            |
| Farm Data Entry             | Paper forms       | Real-time digital    | Farmer App                     |
| Payment Methods             | Limited           | 5+ options           | Payment gateway integration    |
| Customer Support Response   | Manual calls      | In-app support       | Chat and notification system   |

### 2.4 Digital Transformation Roadmap Position

```
Phase 1: Foundation - Infrastructure (Complete)          Days 1-50      COMPLETE
Phase 2: Foundation - Database & Security (Complete)     Days 51-100    COMPLETE
Phase 3: Foundation - ERP Core Configuration (Complete)  Days 101-150   COMPLETE
Phase 4: Foundation - Public Website & CMS (Complete)    Days 151-200   COMPLETE
Phase 5: Operations - Farm Management Foundation         Days 201-250   COMPLETE
Phase 6: Operations - Mobile App Foundation              Days 251-350   THIS PHASE
Phase 7: Operations - B2C E-commerce Core                Days 351-400
Phase 8: Operations - Payment & Logistics                Days 401-450
Phase 9: Commerce - B2B Portal Foundation                Days 451-500
Phase 10: Commerce - IoT Integration Core                Days 501-550
Phase 11: Commerce - Advanced Analytics                  Days 551-600
Phase 12: Commerce - Subscription & Automation           Days 601-650
Phase 13: Optimization - Performance & AI                Days 651-700
Phase 14: Optimization - Testing & Documentation         Days 701-750
Phase 15: Optimization - Deployment & Handover           Days 751-800
```

---

## 3. Phase 6 Scope Statement

### 3.1 In-Scope Items

#### 3.1.1 Flutter Project Foundation (Milestone 51)

- Flutter monorepo architecture with shared packages
- Clean Architecture implementation (presentation, domain, data layers)
- State management setup (Riverpod/BLoC)
- Shared API client with Dio and JWT interceptors
- Offline storage foundation (Drift/SQLite, Hive)
- Firebase integration (FCM, Analytics, Crashlytics)
- CI/CD pipeline (CodeMagic/GitHub Actions)
- Design system and component library
- Localization framework (English and Bangla)
- Environment configuration (dev, staging, prod)

#### 3.1.2 Customer App - Authentication (Milestone 52)

- Phone number OTP authentication (+880 Bangladesh format)
- Email and password authentication
- Social login (Google, Facebook)
- Biometric authentication (fingerprint, Face ID)
- JWT token management with refresh
- Secure token storage (flutter_secure_storage)
- Profile setup and management
- Password reset flow
- Session management across devices

#### 3.1.3 Customer App - Product Catalog (Milestone 53)

- Product list with category navigation
- Product search with Elasticsearch
- Advanced filters (price, category, availability)
- Product detail view with images and specifications
- Offline product caching (Hive)
- Wishlist functionality
- Product reviews and ratings
- Recently viewed products
- Promotional banners and offers

#### 3.1.4 Customer App - Cart & Checkout (Milestone 54)

- Shopping cart CRUD operations
- Cart synchronization across devices
- Address management with GPS autocomplete
- Delivery slot selection
- bKash payment integration
- Nagad payment integration
- Rocket payment integration
- SSLCommerz card payment integration
- Order confirmation flow
- Invoice generation

#### 3.1.5 Customer App - Orders & Profile (Milestone 55)

- Order history with pagination
- Order detail view
- Real-time order tracking
- Push notifications (FCM)
- Loyalty points display
- Profile editing
- Notification preferences
- App settings (language, theme)
- Help and support section
- Beta release to TestFlight and Internal Testing

#### 3.1.6 Field Sales App - Core (Milestone 56)

- Sales representative authentication (PIN + device binding)
- Offline customer database with delta sync
- Customer list with search and filters
- Customer detail view with purchase history
- New customer registration
- Credit limit tracking
- Price list management (tiered pricing)
- Offline sync engine with conflict resolution

#### 3.1.7 Field Sales App - Operations (Milestone 57)

- Quick order entry interface
- Order history for sales rep
- Route planning with Google Maps
- GPS location tracking
- Customer visit check-in/check-out
- Payment collection (cash, digital)
- Daily sales summary
- Sales target tracking
- Performance dashboard

#### 3.1.8 Farmer App - Core (Milestone 58)

- Simple PIN and fingerprint authentication
- Animal database with offline sync
- Animal list with filters
- Animal detail view
- RFID tag scanning (NFC)
- Barcode/QR code scanning
- Photo-based animal lookup
- Quick animal search by ear tag

#### 3.1.9 Farmer App - Data Entry (Milestone 59)

- Milk production entry form
- Batch milk entry grid
- Health observation logging
- Bangla voice input integration
- Voice command support
- Photo capture with GPS tagging
- Quick action buttons
- Sync status and summary
- Full offline operation mode

#### 3.1.10 Mobile Integration Testing (Milestone 60)

- Unit test suite (>80% coverage)
- Widget tests for UI components
- Integration tests for API flows
- E2E tests for critical user journeys
- Performance testing (startup, scrolling)
- Security audit and penetration testing
- Cross-app sync testing
- UAT preparation and execution
- App store submission (Google Play, App Store)
- Phase documentation and handoff

### 3.2 Out-of-Scope Items

The following items are explicitly excluded from Phase 6 and deferred to subsequent phases:

- B2B marketplace mobile features (Phase 9)
- IoT sensor data visualization in mobile (Phase 10)
- AI-powered recommendations (Phase 13)
- In-app chat/messaging (Phase 7)
- Delivery partner app (Phase 8)
- Advanced analytics in mobile (Phase 11)
- Subscription management UI (Phase 12)
- Apple Watch/Wear OS apps
- Tablet-optimized layouts
- Multi-language beyond English/Bangla

### 3.3 Assumptions

1. Phase 5 REST APIs are complete, stable, and documented
2. Firebase project is created and configured
3. Payment gateway sandbox accounts are available
4. Google Play and App Store developer accounts are registered
5. Test devices (iOS and Android) are available
6. Development team has Flutter/Dart proficiency
7. Network connectivity is available for development
8. Google Maps API key is provisioned
9. Bangla speech-to-text API is accessible
10. Backend can handle mobile API traffic load

### 3.4 Constraints

1. **Budget Constraint**: BDT 2.50 Crore maximum budget
2. **Timeline Constraint**: 100 working days (Days 251-350)
3. **Team Size**: 3 Full-Stack Developers
4. **Platform Constraint**: iOS 12+ and Android 5.0+
5. **App Size Constraint**: <50MB APK, <70MB IPA
6. **Offline Constraint**: Apps must work without internet
7. **Language Constraint**: English and Bangla support required
8. **Performance Constraint**: <3s cold start time
9. **Battery Constraint**: <15% drain per 8-hour shift (Field Sales)
10. **Voice Accuracy**: >90% Bangla speech recognition

---

## 4. Milestone Index Table

### 4.1 Part A — Customer & E-commerce Mobile (Days 251-300)

| Milestone | Name                                  | Days      | Duration | Primary Focus               | Key Deliverables                        |
| --------- | ------------------------------------- | --------- | -------- | --------------------------- | --------------------------------------- |
| 51        | Flutter Project Foundation            | 251-260   | 10 days  | Shared architecture         | Monorepo, CI/CD, Firebase, design system|
| 52        | Customer App - Authentication         | 261-270   | 10 days  | Auth flows                  | OTP, social login, biometric            |
| 53        | Customer App - Product Catalog        | 271-280   | 10 days  | Product browsing            | Search, filters, offline cache          |
| 54        | Customer App - Cart & Checkout        | 281-290   | 10 days  | Payment integration         | Cart, bKash, Nagad, Rocket              |
| 55        | Customer App - Orders & Profile       | 291-300   | 10 days  | Order management            | Tracking, notifications, beta release   |

### 4.2 Part B — Field Operations & Farmer Apps (Days 301-350)

| Milestone | Name                                  | Days      | Duration | Primary Focus               | Key Deliverables                        |
| --------- | ------------------------------------- | --------- | -------- | --------------------------- | --------------------------------------- |
| 56        | Field Sales App - Core                | 301-310   | 10 days  | Customer management         | Offline DB, customer CRUD, sync         |
| 57        | Field Sales App - Operations          | 311-320   | 10 days  | Route & orders              | GPS tracking, quick order, targets      |
| 58        | Farmer App - Core                     | 321-330   | 10 days  | Animal lookup               | RFID scan, barcode, photo ID            |
| 59        | Farmer App - Data Entry               | 331-340   | 10 days  | Voice input                 | Milk entry, health, Bangla voice        |
| 60        | Mobile Integration Testing            | 341-350   | 10 days  | Quality assurance           | Testing, UAT, store submission          |

### 4.3 Milestone Documents Reference

| Milestone | Document Name                                      | Description                         |
| --------- | -------------------------------------------------- | ----------------------------------- |
| 51        | `Milestone_51_Flutter_Project_Foundation.md`       | Monorepo, architecture, CI/CD       |
| 52        | `Milestone_52_Customer_App_Authentication.md`      | OTP, social, biometric auth         |
| 53        | `Milestone_53_Customer_App_Product_Catalog.md`     | Products, search, offline           |
| 54        | `Milestone_54_Customer_App_Cart_Checkout.md`       | Cart, payments, checkout            |
| 55        | `Milestone_55_Customer_App_Orders_Profile.md`      | Orders, profile, notifications      |
| 56        | `Milestone_56_Field_Sales_App_Core.md`             | Customer DB, offline sync           |
| 57        | `Milestone_57_Field_Sales_App_Operations.md`       | Routes, orders, GPS tracking        |
| 58        | `Milestone_58_Farmer_App_Core.md`                  | RFID, barcode, animal lookup        |
| 59        | `Milestone_59_Farmer_App_Data_Entry.md`            | Milk entry, voice input             |
| 60        | `Milestone_60_Mobile_Integration_Testing.md`       | Testing, UAT, store submission      |

---

## 5. Technology Stack Summary

### 5.1 Mobile Technologies

| Component             | Technology           | Version    | Purpose                              |
| --------------------- | -------------------- | ---------- | ------------------------------------ |
| Framework             | Flutter              | 3.16+      | Cross-platform mobile development    |
| Language              | Dart                 | 3.2+       | Flutter programming language         |
| State Management      | Riverpod             | 2.4+       | Reactive state management            |
| State Management Alt  | flutter_bloc         | 8.1+       | BLoC pattern (complex screens)       |
| HTTP Client           | Dio                  | 5.4+       | API communication with interceptors  |
| Local Database        | Drift (moor)         | 2.14+      | SQLite with type-safe queries        |
| Key-Value Storage     | Hive                 | 2.2+       | Fast local storage, offline cache    |
| Secure Storage        | flutter_secure_storage| 9.0+      | Encrypted credential storage         |
| Image Caching         | cached_network_image | 3.3+       | Efficient image loading              |
| Navigation            | go_router            | 13.0+      | Declarative routing                  |

### 5.2 Firebase Services

| Service               | Purpose                                  | Configuration                        |
| --------------------- | ---------------------------------------- | ------------------------------------ |
| Firebase Auth         | Social login, phone auth                 | Google, Facebook providers           |
| Cloud Messaging (FCM) | Push notifications                       | Topics: orders, promotions, alerts   |
| Analytics             | User behavior tracking                   | Custom events, conversions           |
| Crashlytics           | Crash reporting                          | Breadcrumbs, non-fatal errors        |
| Remote Config         | Feature flags                            | App version control                  |
| Performance           | App performance monitoring               | Traces, network monitoring           |

### 5.3 Backend Technologies (Odoo Extensions)

| Component             | Technology           | Version    | Purpose                              |
| --------------------- | -------------------- | ---------- | ------------------------------------ |
| ERP Framework         | Odoo CE              | 19.0       | Core business logic                  |
| Language              | Python               | 3.11+      | Backend development                  |
| API Framework         | Odoo REST            | Built-in   | Mobile API endpoints                 |
| API Extension         | FastAPI              | 0.104+     | High-performance mobile APIs         |
| Authentication        | JWT                  | PyJWT 2.8+ | Token-based authentication           |
| Database              | PostgreSQL           | 16.1+      | Primary data storage                 |
| Cache                 | Redis                | 7.2+       | Session, rate limiting, caching      |
| Message Queue         | Celery               | 5.3+       | Background tasks, notifications      |

### 5.4 Payment Gateway SDKs

| Provider              | Package/SDK                              | Features                             |
| --------------------- | ---------------------------------------- | ------------------------------------ |
| bKash                 | flutter_bkash                            | Tokenized checkout, recurring        |
| Nagad                 | Custom REST integration                  | Payment initiation, callback         |
| Rocket                | Custom REST integration                  | Payment initiation, callback         |
| SSLCommerz            | sslcommerz_flutter                       | Card payments, EMI                   |

### 5.5 Third-Party Services

| Service               | Provider             | Purpose                              |
| --------------------- | -------------------- | ------------------------------------ |
| Maps & Geocoding      | Google Maps Platform | Route optimization, address lookup   |
| Speech-to-Text        | Google Cloud STT     | Bangla voice input                   |
| SMS Gateway           | SSL Wireless         | OTP delivery                         |
| Push Notifications    | Firebase FCM         | Order updates, promotions            |
| Analytics             | Mixpanel/Amplitude   | Advanced user analytics              |

### 5.6 Development Tools

| Tool                  | Purpose                                  | Usage                                |
| --------------------- | ---------------------------------------- | ------------------------------------ |
| VS Code               | Primary IDE                              | Flutter, Dart, Python development    |
| Android Studio        | Android emulator, debugging              | Android-specific development         |
| Xcode                 | iOS simulator, signing                   | iOS-specific development             |
| CodeMagic             | CI/CD for mobile                         | Build, test, deploy automation       |
| GitHub Actions        | Backend CI/CD                            | API testing, deployment              |
| Postman               | API testing                              | Endpoint documentation, mocking      |
| Firebase Console      | Firebase management                      | Notifications, analytics, configs    |

---

## 6. Team Structure & Workload Distribution

### 6.1 Developer Roles

| Role                       | Specialization                          | Primary Focus in Phase 6             |
| -------------------------- | --------------------------------------- | ------------------------------------ |
| **Dev 1 (Backend Lead)**   | Python, Odoo, PostgreSQL, APIs          | Mobile REST APIs, JWT, business logic|
| **Dev 2 (Full-Stack)**     | CI/CD, Firebase, Payments, Testing      | Integrations, payments, DevOps       |
| **Dev 3 (Mobile Lead)**    | Flutter, Dart, UI/UX                    | All three mobile apps, offline sync  |

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

| Milestone | Dev 1 (Backend) | Dev 2 (Full-Stack) | Dev 3 (Mobile) |
| --------- | --------------- | ------------------ | -------------- |
| 51 - Flutter Foundation    | 35% | 35% | 30% |
| 52 - Customer Auth         | 40% | 30% | 30% |
| 53 - Product Catalog       | 35% | 25% | 40% |
| 54 - Cart & Checkout       | 35% | 40% | 25% |
| 55 - Orders & Profile      | 30% | 30% | 40% |
| 56 - Field Sales Core      | 40% | 25% | 35% |
| 57 - Field Sales Ops       | 35% | 35% | 30% |
| 58 - Farmer App Core       | 40% | 25% | 35% |
| 59 - Farmer App Entry      | 35% | 30% | 35% |
| 60 - Integration Testing   | 25% | 40% | 35% |
| **Average**                | **35%** | **31.5%** | **33.5%** |

### 6.4 Developer Daily Hour Allocation

**Dev 1 - Backend Lead (8h/day):**
- Mobile API development: 4h
- JWT and authentication: 1.5h
- Business logic: 1.5h
- Code review: 1h

**Dev 2 - Full-Stack (8h/day):**
- Payment integration: 2.5h
- CI/CD and DevOps: 2h
- Firebase services: 1.5h
- Testing: 1.5h
- Code review: 0.5h

**Dev 3 - Mobile Lead (8h/day):**
- Flutter UI development: 4h
- State management: 1.5h
- Offline/sync: 1.5h
- Testing: 1h

---

## 7. Requirement Traceability Summary

### 7.1 RFP Requirements Mapping

| Req ID        | Description                              | Milestone | Status  |
| ------------- | ---------------------------------------- | --------- | ------- |
| RFP-MOB-001   | Cross-platform mobile development        | 51        | Planned |
| RFP-MOB-002   | Customer authentication (OTP, social)    | 52        | Planned |
| RFP-MOB-003   | Product catalog with offline support     | 53        | Planned |
| RFP-MOB-004   | Shopping cart and checkout               | 54        | Planned |
| RFP-MOB-005   | Order tracking and notifications         | 55        | Planned |
| RFP-MOB-006   | Field sales order taking                 | 56-57     | Planned |
| RFP-MOB-007   | Route optimization with GPS              | 57        | Planned |
| RFP-MOB-008   | Farmer data entry module                 | 58-59     | Planned |
| RFP-MOB-009   | Bangla voice input support               | 59        | Planned |
| RFP-MOB-010   | RFID/barcode scanning                    | 58        | Planned |
| RFP-MOB-011   | Offline-first architecture               | 51,56,58  | Planned |
| RFP-MOB-012   | Bangladesh payment integration           | 54        | Planned |

### 7.2 BRD Requirements Mapping

| Req ID        | Business Requirement                     | Milestone | Status  |
| ------------- | ---------------------------------------- | --------- | ------- |
| BRD-MOB-001   | 50,000+ registered customers via app     | 52-55     | Planned |
| BRD-MOB-002   | 1,000 orders/day processing              | 54-55     | Planned |
| BRD-MOB-003   | Field sales efficiency improvement       | 56-57     | Planned |
| BRD-MOB-004   | 100% farm data digitization              | 58-59     | Planned |
| BRD-MOB-005   | Low-literacy user accessibility          | 59        | Planned |
| BRD-MOB-006   | 40% faster order processing              | 56-57     | Planned |
| BRD-MOB-007   | Real-time inventory visibility           | 53-54     | Planned |
| BRD-MOB-008   | Customer satisfaction >90% NPS           | 55        | Planned |

### 7.3 SRS Requirements Mapping

| Req ID        | Technical Requirement                    | Milestone | Status  |
| ------------- | ---------------------------------------- | --------- | ------- |
| SRS-MOB-001   | Flutter 3.16+ with Dart 3.2+             | 51        | Planned |
| SRS-MOB-002   | Riverpod state management                | 51        | Planned |
| SRS-MOB-003   | Drift SQLite for offline storage         | 51        | Planned |
| SRS-MOB-004   | JWT authentication with refresh tokens   | 52        | Planned |
| SRS-MOB-005   | OTP via SSL Wireless SMS gateway         | 52        | Planned |
| SRS-MOB-006   | Elasticsearch product search             | 53        | Planned |
| SRS-MOB-007   | bKash tokenized checkout                 | 54        | Planned |
| SRS-MOB-008   | Firebase FCM push notifications          | 55        | Planned |
| SRS-MOB-009   | Google Maps route optimization           | 57        | Planned |
| SRS-MOB-010   | Google Cloud STT for Bangla              | 59        | Planned |
| SRS-MOB-011   | >80% unit test coverage                  | 60        | Planned |
| SRS-MOB-012   | <50MB APK size                           | 60        | Planned |

---

## 8. Phase 6 Key Deliverables

### 8.1 Customer App Deliverables

1. Phone OTP authentication with +880 format validation
2. Email/password login with forgot password flow
3. Google and Facebook social login
4. Biometric authentication (fingerprint/Face ID)
5. Product catalog with category navigation
6. Full-text search with Elasticsearch
7. Advanced filters (price, category, availability)
8. Wishlist management
9. Shopping cart with real-time sync
10. Address management with GPS autocomplete
11. bKash payment integration
12. Nagad payment integration
13. Rocket payment integration
14. SSLCommerz card payment
15. Order confirmation and invoice
16. Order history and tracking
17. Push notifications
18. Profile management
19. App settings (language, notifications)
20. TestFlight and Internal Testing release

### 8.2 Field Sales App Deliverables

21. Sales rep PIN + device authentication
22. Offline customer database
23. Customer list with search and filters
24. Customer detail with purchase history
25. New customer registration
26. Credit limit tracking
27. Price list management
28. Quick order entry
29. Order history
30. Route planning with Google Maps
31. GPS location tracking
32. Customer visit check-in/check-out
33. Payment collection
34. Daily sales summary
35. Sales target tracking

### 8.3 Farmer App Deliverables

36. Simple PIN authentication
37. Fingerprint authentication
38. Animal database with offline sync
39. Animal list with filters
40. Animal detail view
41. RFID tag scanning
42. Barcode/QR scanning
43. Photo-based animal lookup
44. Milk production entry
45. Health observation logging
46. Bangla voice input (90%+ accuracy)
47. Voice command support
48. Photo capture with GPS
49. Sync status and summary
50. Full offline operation

### 8.4 Technical Deliverables

51. Flutter monorepo with shared packages
52. Clean Architecture implementation
53. Riverpod state management
54. Drift SQLite database
55. Hive cache implementation
56. Dio API client with interceptors
57. JWT token management
58. Firebase integration (FCM, Analytics, Crashlytics)
59. CI/CD pipeline (CodeMagic)
60. Unit tests (>80% coverage)
61. Widget tests
62. Integration tests
63. E2E tests
64. Security audit report
65. App store submissions (Google Play, App Store)

---

## 9. Success Criteria & Exit Gates

### 9.1 Milestone Exit Criteria

Each milestone must meet these criteria before proceeding:

| Criterion                | Requirement                              |
| ------------------------ | ---------------------------------------- |
| Code Complete            | All planned features implemented         |
| Unit Tests               | >80% code coverage for new code          |
| Widget Tests             | All reusable widgets tested              |
| Code Review              | All code reviewed and approved           |
| Documentation            | Technical docs updated                   |
| No Critical Bugs         | Zero P0/P1 bugs open                     |
| Demo Completed           | Stakeholder demo conducted               |
| Performance Verified     | <3s cold start, 60fps scrolling          |

### 9.2 Phase 6 Exit Criteria

| Exit Gate                | Requirement                              | Verification Method           |
| ------------------------ | ---------------------------------------- | ----------------------------- |
| Customer App Complete    | All features functional                  | E2E testing, UAT              |
| Field Sales App Complete | Offline operation verified               | Field testing                 |
| Farmer App Complete      | Voice input 90%+ accuracy                | User testing with farmers     |
| Payment Integration      | All 4 gateways functional                | Transaction testing           |
| Offline Sync             | Sync works after 8h offline              | Battery/connectivity tests    |
| Push Notifications       | Order notifications delivered            | FCM testing                   |
| Performance              | <50MB APK, <3s start, 60fps              | Performance testing           |
| Security                 | No critical vulnerabilities              | Security audit                |
| Test Coverage            | >80% overall coverage                    | Coverage reports              |
| Store Submission         | Apps approved for internal testing       | TestFlight, Internal Track    |

### 9.3 Quality Metrics

| Metric                   | Target                                   |
| ------------------------ | ---------------------------------------- |
| Code Coverage            | >80% overall                             |
| Critical Bugs            | 0 at phase end                           |
| High Bugs                | <5 at phase end                          |
| App Crash Rate           | <0.5% (99.5% crash-free)                 |
| Cold Start Time          | <3 seconds                               |
| Frame Rate               | 60fps for animations                     |
| API Response Time        | <500ms (p95)                             |
| Offline Sync Success     | >99% success rate                        |
| Voice Recognition        | >90% accuracy for Bangla                 |
| Customer App Size        | <50MB APK                                |

---

## 10. Risk Register

| Risk ID | Description                          | Impact | Probability | Mitigation Strategy                   |
| ------- | ------------------------------------ | ------ | ----------- | ------------------------------------- |
| R6-01   | App store rejection                  | High   | Medium      | Early compliance review, beta testing |
| R6-02   | Offline sync data conflicts          | Medium | High        | Last-write-wins + manual resolution   |
| R6-03   | Bangla voice recognition accuracy    | Medium | High        | Multiple STT providers, fallback input|
| R6-04   | Payment gateway integration issues   | High   | Medium      | Sandbox testing, fallback methods     |
| R6-05   | Device fragmentation (Android)       | Medium | Medium      | Extensive device testing matrix       |
| R6-06   | Battery drain from GPS tracking      | Medium | Medium      | Geofencing, smart polling intervals   |
| R6-07   | Poor network in rural Bangladesh     | High   | High        | Aggressive offline caching            |
| R6-08   | RFID hardware compatibility          | Medium | Medium      | Test with multiple NFC readers        |
| R6-09   | Flutter version breaking changes     | Medium | Low         | Version pinning, staged upgrades      |
| R6-10   | Firebase quota limits                | Low    | Low         | Monitor usage, upgrade plan if needed |
| R6-11   | Team skill gaps in Flutter           | Medium | Low         | Training, pair programming            |
| R6-12   | iOS signing and provisioning issues  | Medium | Medium      | Early certificate setup, documentation|

### Risk Severity Matrix

| Probability / Impact | Low      | Medium    | High      |
| -------------------- | -------- | --------- | --------- |
| **High**             |          | R6-02, R6-03 | R6-07  |
| **Medium**           | R6-09    | R6-05, R6-06, R6-08, R6-12 | R6-01, R6-04 |
| **Low**              | R6-10, R6-11 |        |           |

---

## 11. Dependencies

### 11.1 Internal Dependencies

| Dependency                | Required By     | Impact if Delayed              |
| ------------------------- | --------------- | ------------------------------ |
| Phase 5 REST APIs stable  | Day 251         | Phase 6 cannot start           |
| Odoo 19 CE mobile module  | Day 251         | API development blocked        |
| Firebase project setup    | Day 251         | Push notifications blocked     |
| Design system from Phase 4| Day 253         | UI development blocked         |
| Product catalog API       | Day 271         | Catalog feature blocked        |
| Payment APIs              | Day 281         | Checkout blocked               |

### 11.2 External Dependencies

| Dependency                | Required By     | Impact if Delayed              |
| ------------------------- | --------------- | ------------------------------ |
| bKash merchant account    | Day 281         | bKash payment blocked          |
| Nagad merchant account    | Day 284         | Nagad payment blocked          |
| Google Play account       | Day 295         | Android release blocked        |
| Apple Developer account   | Day 295         | iOS release blocked            |
| Google Maps API key       | Day 311         | Route optimization blocked     |
| Google Cloud STT API      | Day 331         | Voice input blocked            |
| SSL Wireless SMS account  | Day 261         | OTP functionality blocked      |

### 11.3 Milestone Dependencies

```
Milestone 51 (Flutter Foundation)
    └── Milestone 52 (Customer Auth) - depends on shared packages
    └── Milestone 56 (Field Sales Core) - depends on auth patterns
    └── Milestone 58 (Farmer Core) - depends on auth patterns
        └── Milestone 53 (Product Catalog) - depends on API client
            └── Milestone 54 (Cart & Checkout) - depends on product module
                └── Milestone 55 (Orders & Profile) - depends on checkout
        └── Milestone 57 (Field Sales Ops) - depends on core
        └── Milestone 59 (Farmer Entry) - depends on core
            └── Milestone 60 (Testing) - depends on all milestones
```

---

## 12. Quality Assurance Framework

### 12.1 Testing Strategy

| Test Type          | Scope                    | Timing              | Responsibility    |
| ------------------ | ------------------------ | ------------------- | ----------------- |
| Unit Testing       | Dart functions, BLoCs    | During development  | All developers    |
| Widget Testing     | UI components            | During development  | Dev 3             |
| Integration Testing| API flows, offline sync  | End of milestone    | Dev 2             |
| E2E Testing        | User journeys            | Milestone 60        | Dev 2, Dev 3      |
| Performance Testing| Startup, rendering       | Milestone 60        | Dev 2             |
| Security Testing   | OWASP Mobile Top 10      | Milestone 60        | External/Dev 2    |
| UAT                | Business requirements    | Milestone 60        | Stakeholders      |
| Device Testing     | Multiple devices         | Throughout          | Dev 3             |

### 12.2 Code Review Process

1. All code changes require pull request
2. Minimum one reviewer approval required
3. CI must pass (build, lint, tests)
4. Flutter analyze with zero errors
5. Security-sensitive code requires two reviewers
6. Review turnaround within 24 hours

### 12.3 Definition of Done

A feature is considered "Done" when:

- [ ] Code implemented and tested
- [ ] Unit tests written (>80% coverage)
- [ ] Widget tests for UI components
- [ ] Code reviewed and approved
- [ ] Documentation updated
- [ ] No critical or high-severity bugs
- [ ] Acceptance criteria verified
- [ ] Demo completed to stakeholders
- [ ] Performance verified (<3s, 60fps)

### 12.4 Mobile Testing Matrix

| Device Type        | OS Version              | Priority | Quantity  |
| ------------------ | ----------------------- | -------- | --------- |
| Android Phone      | Android 5.0 (API 21)    | High     | 1         |
| Android Phone      | Android 10 (API 29)     | High     | 2         |
| Android Phone      | Android 13 (API 33)     | High     | 2         |
| Android Tablet     | Android 11              | Medium   | 1         |
| iPhone             | iOS 12                  | High     | 1         |
| iPhone             | iOS 15                  | High     | 1         |
| iPhone             | iOS 17                  | High     | 1         |
| iPad               | iPadOS 15               | Low      | 1         |

---

## 13. Communication & Reporting

### 13.1 Meeting Schedule

| Meeting              | Frequency    | Duration  | Participants              |
| -------------------- | ------------ | --------- | ------------------------- |
| Daily Standup        | Daily        | 15 min    | All developers            |
| Sprint Planning      | Bi-weekly    | 2 hours   | Team + PM                 |
| Sprint Demo          | Bi-weekly    | 1 hour    | Team + Stakeholders       |
| Retrospective        | Bi-weekly    | 1 hour    | Team                      |
| Technical Review     | Weekly       | 1 hour    | Developers + Architect    |
| Stakeholder Update   | Weekly       | 30 min    | PM + Stakeholders         |
| Mobile Design Review | Weekly       | 1 hour    | Dev 3 + UX + PM           |

### 13.2 Reporting Cadence

| Report               | Frequency    | Audience                  |
| -------------------- | ------------ | ------------------------- |
| Daily Status         | Daily        | Project Manager           |
| Build Status         | Per commit   | Development Team          |
| Sprint Report        | Bi-weekly    | Stakeholders              |
| Milestone Report     | Per milestone| Project Sponsor           |
| Phase Report         | End of phase | Executive Team            |
| App Analytics        | Weekly       | Product Team              |
| Crash Reports        | Daily        | Development Team          |

### 13.3 Escalation Path

```
Level 1: Developer → Tech Lead (within 4 hours)
Level 2: Tech Lead → Project Manager (within 8 hours)
Level 3: Project Manager → Project Sponsor (within 24 hours)
```

### 13.4 Communication Channels

| Channel              | Purpose                  | Response Time             |
| -------------------- | ------------------------ | ------------------------- |
| Slack #mobile-dev    | Day-to-day development   | 1 hour                    |
| Slack #mobile-bugs   | Bug reports              | 2 hours                   |
| Email                | Formal communication     | 24 hours                  |
| Jira                 | Task tracking            | 4 hours                   |
| GitHub               | Code reviews             | 24 hours                  |
| Firebase Console     | Crash monitoring         | Continuous                |

---

## 14. Phase 6 to Phase 7 Transition Criteria

### 14.1 Mandatory Criteria

| Criterion                | Requirement                              |
| ------------------------ | ---------------------------------------- |
| All Milestones Complete  | Milestones 51-60 signed off              |
| Customer App Released    | Available on TestFlight + Internal Track |
| Field Sales App Released | Available for internal testing           |
| Farmer App Released      | Available for internal testing           |
| UAT Sign-off             | All stakeholder groups approved          |
| Test Coverage            | >80% code coverage achieved              |
| Bug Status               | Zero P0/P1 bugs, <5 P2 bugs              |
| Documentation            | All technical docs complete              |
| API Documentation        | Mobile API docs published                |

### 14.2 Transition Activities

| Activity                 | Duration     | Owner                     |
| ------------------------ | ------------ | ------------------------- |
| Knowledge Transfer       | 3 days       | All developers            |
| Documentation Review     | 2 days       | Tech Lead                 |
| Code Freeze              | 1 day        | All developers            |
| Handoff Meeting          | 0.5 day      | PM + Team                 |
| Phase 7 Kickoff Prep     | 1 day        | PM                        |

### 14.3 Handoff Checklist

- [ ] All code merged to main branch
- [ ] All tests passing in CI/CD
- [ ] Apps published to internal testing tracks
- [ ] Technical documentation complete
- [ ] API documentation published
- [ ] User guides finalized
- [ ] Training sessions completed
- [ ] Known issues documented
- [ ] Phase 7 requirements clarified
- [ ] Firebase project handoff documented
- [ ] App store accounts credentials secured

---

## 15. Appendix A: Glossary

### Mobile Development Terminology

| Term                   | Definition                                            |
| ---------------------- | ----------------------------------------------------- |
| **APK**                | Android Package - Android app distribution format     |
| **BLoC**               | Business Logic Component - Flutter state pattern      |
| **Cold Start**         | App launch from terminated state                      |
| **Dart**               | Programming language for Flutter                      |
| **Deep Link**          | URL that opens specific app screen                    |
| **Drift**              | Type-safe SQLite wrapper for Flutter                  |
| **FCM**                | Firebase Cloud Messaging for push notifications       |
| **Flutter**            | Google's cross-platform UI framework                  |
| **Hive**               | Lightweight key-value database for Flutter            |
| **Hot Reload**         | Flutter feature for instant code updates              |
| **IPA**                | iOS App Package - iOS app distribution format         |
| **JWT**                | JSON Web Token for authentication                     |
| **Riverpod**           | Reactive state management for Flutter                 |
| **TestFlight**         | Apple's beta testing platform                         |
| **Widget**             | Building block of Flutter UI                          |

### Bangladesh-Specific Terms

| Term                   | Definition                                            |
| ---------------------- | ----------------------------------------------------- |
| **bKash**              | Leading mobile financial service in Bangladesh        |
| **Nagad**              | Government-backed mobile payment service              |
| **Rocket**             | Mobile banking service by Dutch Bangla Bank           |
| **SSLCommerz**         | Payment gateway for card and wallet payments          |
| **Bangla**             | Bengali language (official language of Bangladesh)    |
| **+880**               | Bangladesh country code for phone numbers             |
| **BDT**                | Bangladeshi Taka (currency)                           |

### Dairy Farm Terminology

| Term                   | Definition                                            |
| ---------------------- | ----------------------------------------------------- |
| **RFID**               | Radio Frequency Identification for animal tracking    |
| **Ear Tag**            | Physical identifier attached to animal's ear          |
| **Milk Yield**         | Amount of milk produced by an animal                  |
| **Lactation**          | Milk production period (~305 days)                    |
| **SCC**                | Somatic Cell Count - indicator of udder health        |

---

## 16. Appendix B: Reference Documents

### Project Documents

| Document ID    | Document Name                              | Location                      |
| -------------- | ------------------------------------------ | ----------------------------- |
| SD-RFP-006     | RFP Customer Mobile App                    | `/docs/RFP/`                  |
| SD-RFP-007     | RFP Vendor Mobile App                      | `/docs/RFP/`                  |
| SD-BRD-001     | Business Requirements Document             | `/docs/BRD/`                  |
| SD-SRS-001     | Software Requirements Specification        | `/docs/`                      |
| SD-TECH-001    | Technology Stack Document                  | `/docs/`                      |

### Implementation Guides

| Document ID    | Document Name                              | Location                      |
| -------------- | ------------------------------------------ | ----------------------------- |
| H-001          | Mobile App Architecture                    | `/docs/Implementation_document_list/` |
| H-002          | Flutter Development Guidelines             | `/docs/Implementation_document_list/` |
| H-005          | Offline-First Architecture                 | `/docs/Implementation_document_list/` |
| H-008          | Mobile Security Implementation             | `/docs/Implementation_document_list/` |
| E-001          | API Specification Document                 | `/docs/Implementation_document_list/` |

### Previous Phase Documents

| Document ID    | Document Name                              | Location                      |
| -------------- | ------------------------------------------ | ----------------------------- |
| SD-PHASE1-IDX  | Phase 1 Index & Executive Summary          | `/Implemenattion_Roadmap/Phase_1/` |
| SD-PHASE2-IDX  | Phase 2 Index & Executive Summary          | `/Implemenattion_Roadmap/Phase_2/` |
| SD-PHASE3-IDX  | Phase 3 Index & Executive Summary          | `/Implemenattion_Roadmap/Phase_3/` |
| SD-PHASE4-IDX  | Phase 4 Index & Executive Summary          | `/Implemenattion_Roadmap/Phase_4/` |
| SD-PHASE5-IDX  | Phase 5 Index & Executive Summary          | `/Implemenattion_Roadmap/Phase_5/` |

### Business Context

| Document ID    | Document Name                              | Location                      |
| -------------- | ------------------------------------------ | ----------------------------- |
| SD-BIZ-001     | Smart Dairy Company Profile                | `/Smart-dairy_business/`      |
| SD-BIZ-002     | Smart Dairy Products and Services          | `/Smart-dairy_business/`      |
| SD-BIZ-003     | Four Business Verticals                    | `/Smart-dairy_business/`      |

### External References

| Resource                 | URL / Reference                           |
| ------------------------ | ----------------------------------------- |
| Flutter Documentation    | https://docs.flutter.dev                  |
| Dart Documentation       | https://dart.dev/guides                   |
| Riverpod Documentation   | https://riverpod.dev                      |
| Firebase Flutter         | https://firebase.flutter.dev              |
| bKash Developer Portal   | https://developer.bka.sh                  |
| Nagad Merchant API       | https://nagad.com.bd/merchant             |
| Google Maps Flutter      | https://pub.dev/packages/google_maps_flutter |
| Google Cloud STT         | https://cloud.google.com/speech-to-text   |

---

## Document Statistics

| Metric                 | Value                                      |
| ---------------------- | ------------------------------------------ |
| Total Pages            | ~30                                        |
| Total Words            | ~10,000                                    |
| Tables                 | 55+                                        |
| Milestones Covered     | 10 (Milestones 51-60)                      |
| Requirements Mapped    | 35+ (RFP, BRD, SRS)                        |
| Risks Identified       | 12                                         |
| Deliverables Listed    | 65+                                        |

---

**Document Prepared By:** Smart Dairy Digital Transformation Team

**Review Status:** Pending Technical Review and Stakeholder Approval

**Next Review Date:** TBD

---

*This document is part of the Smart Dairy Digital Smart Portal + ERP Implementation Roadmap. For questions or clarifications, contact the Project Manager.*
