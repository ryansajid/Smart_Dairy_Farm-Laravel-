# Test Plan - Phase 2 (Operations)

## Smart Dairy Ltd. - Smart Web Portal System & Integrated ERP

---

| **Document ID** | G-003 |
|-----------------|-------|
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | QA Lead |
| **Owner** | QA Lead |
| **Reviewer** | Project Manager |
| **Status** | Draft |

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | QA Lead | Initial Release |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Test Scope](#2-test-scope)
3. [Test Schedule](#3-test-schedule)
4. [Test Environment](#4-test-environment)
5. [Test Deliverables](#5-test-deliverables)
6. [Resource Planning](#6-resource-planning)
7. [Test Execution Strategy](#7-test-execution-strategy)
8. [IoT Testing](#8-iot-testing)
9. [Mobile Testing](#9-mobile-testing)
10. [Integration Testing](#10-integration-testing)
11. [Entry & Exit Criteria](#11-entry--exit-criteria)
12. [Defect Management](#12-defect-management)
13. [UAT Preparation](#13-uat-preparation)
14. [Appendices](#14-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the Test Plan for Phase 2 (Operations) of the Smart Dairy Smart Web Portal System & Integrated ERP implementation. It outlines the testing strategy, scope, resources, and schedule for validating all components developed during Months 4-6 of the project timeline.

### 1.2 Phase 2 Scope

Phase 2 focuses on operational modules that form the core of Smart Dairy's farm-to-consumer value chain:

| Component | Description | Priority |
|-----------|-------------|----------|
| Smart Farm Management Module | Herd management, milk production, health tracking | Critical |
| IoT Integration Platform | Milk meters, RFID tags, environmental sensors | Critical |
| Mobile Applications | Farmer App (iOS/Android), Field Sales App | High |
| Advanced ERP Modules | Manufacturing (MRP), Quality Management | High |
| Integration Layer | Connectivity with Phase 1 ERP components | Critical |

### 1.3 Test Objectives

1. **Functional Validation**: Verify all farm management, IoT, and mobile features work as specified
2. **Integration Assurance**: Ensure seamless data flow between Phase 2 and Phase 1 components
3. **Data Accuracy**: Validate IoT sensor data collection and processing accuracy
4. **Mobile Experience**: Confirm offline capability and sync functionality
5. **Operational Readiness**: Ensure systems support 3x growth targets
6. **Compliance**: Verify traceability and quality management meet FSSAI/BFSA requirements

### 1.4 Timeline

| Milestone | Start Date | End Date | Duration |
|-----------|------------|----------|----------|
| Test Planning | Month 4, Week 1 | Month 4, Week 2 | 2 weeks |
| Test Environment Setup | Month 4, Week 2 | Month 4, Week 3 | 1 week |
| Unit Testing | Month 4, Week 3 | Month 5, Week 1 | 3 weeks |
| Integration Testing | Month 5, Week 1 | Month 5, Week 3 | 3 weeks |
| System Testing | Month 5, Week 3 | Month 6, Week 1 | 3 weeks |
| IoT Testing | Month 5, Week 2 | Month 6, Week 1 | 4 weeks |
| Mobile Testing | Month 5, Week 3 | Month 6, Week 2 | 4 weeks |
| Performance Testing | Month 6, Week 1 | Month 6, Week 2 | 2 weeks |
| UAT Preparation | Month 6, Week 2 | Month 6, Week 3 | 2 weeks |
| UAT Execution | Month 6, Week 3 | Month 6, Week 4 | 2 weeks |

---

## 2. Test Scope

### 2.1 In Scope

#### 2.1.1 Smart Farm Management Module

| Feature Area | Test Coverage |
|--------------|---------------|
| **Herd Management** | Cattle registration, RFID/ear tag integration, lineage tracking, breed management |
| **Milk Production** | Daily yield recording, Fat/SNF testing, quality parameters, production trends |
| **Health Management** | Vaccination schedules, treatment records, veterinary visits, disease alerts |
| **Reproduction** | Breeding records, pregnancy tracking, calving alerts, embryo transfer logs |
| **Feed Management** | Ration planning, inventory tracking, consumption monitoring, cost analysis |
| **Farm Inventory** | Medicine, equipment, supplies tracking with expiry alerts |
| **Task Management** | Daily task lists, work assignment, completion tracking |
| **Reporting** | Production reports, health summaries, profitability analysis |

#### 2.1.2 IoT Integration Platform

| Sensor Type | Data Points to Test |
|-------------|---------------------|
| **Milk Meters** | Volume accuracy, temperature readings, conductivity measurement |
| **Milk Quality Analyzers** | Fat %, SNF %, protein %, lactose %, density readings |
| **RFID Readers** | Tag reading accuracy, range, multiple tag handling |
| **Environmental Sensors** | Barn temperature, humidity, air quality monitoring |
| **Cattle Wearables** | Activity data, heat detection accuracy, rumination monitoring |
| **Cold Storage Sensors** | Temperature alerts, humidity monitoring, threshold breaches |
| **Feed Management Systems** | Automated feeding integration, consumption data |

#### 2.1.3 Mobile Applications

| Application | Key Testing Areas |
|-------------|-------------------|
| **Farmer App (iOS/Android)** | Cattle data entry, milk recording, health logging, offline sync, barcode/RFID scanning |
| **Field Sales App** | Order taking, customer visits, route optimization, inventory check, digital signatures |

#### 2.1.4 Advanced ERP Modules

| Module | Test Coverage |
|--------|---------------|
| **Manufacturing (MRP)** | Bill of Materials, production planning, work orders, yield tracking |
| **Quality Management** | Quality checks (receiving, in-process, final), CoA generation, non-conformance |
| **Traceability** | Farm-to-fork tracking, batch/lot genealogy, recall management |

### 2.2 Out of Scope

- Phase 3 components (B2C E-commerce, advanced B2B features)
- AI/ML features (planned for Phase 4)
- Third-party logistics provider testing (separate integration project)
- Hardware manufacturing/quality (assumed delivered by vendors)

### 2.3 Assumptions and Dependencies

| ID | Assumption |
|----|------------|
| A1 | Phase 1 components (Accounting, Inventory, Sales, Purchase) are stable and deployed |
| A2 | IoT hardware is procured and installed at the farm by Month 4 |
| A3 | Mobile devices (smartphones/tablets) are provisioned for field staff |
| A4 | Network connectivity is available at the farm location |
| A5 | Test data representing historical farm operations is available |
| A6 | Farm staff will be available for UAT participation |

| ID | Dependency |
|----|------------|
| D1 | Completion of Phase 1 development and stabilization |
| D2 | IoT device calibration and baseline configuration |
| D3 | Mobile app builds are delivered to test environment |
| D4 | MQTT broker and IoT gateway are operational |
| D5 | SSL certificates and security configurations are in place |

---

## 3. Test Schedule

### 3.1 Master Schedule

```
Month 4                                    Month 5                                    Month 6
Week:  1      2      3      4        1      2      3      4        1      2      3      4
       |______|______|______|________|______|______|______|________|______|______|______|
       
TP     ████████
TES           ███████
UT                  ████████████
IT                               ██████████████
ST                                              ██████████████
IoT-T                                           ████████████████████
Mob-T                                                  ████████████████████
PT                                                           ████████
UAT-P                                                               ████████
UAT                                                                          ████████
```

Legend: TP=Test Planning, TES=Test Environment Setup, UT=Unit Testing, IT=Integration Testing, ST=System Testing, IoT-T=IoT Testing, Mob-T=Mobile Testing, PT=Performance Testing, UAT-P=UAT Preparation

### 3.2 Detailed Schedule

| Activity | Start | End | Duration | Resources |
|----------|-------|-----|----------|-----------|
| Test Case Design - Farm Module | M4-W1 | M4-W3 | 3 weeks | QA Engineer (Farm), BA |
| Test Case Design - IoT | M4-W2 | M4-W4 | 3 weeks | QA Engineer (IoT) |
| Test Case Design - Mobile | M4-W2 | M4-W4 | 3 weeks | QA Engineer (Mobile) |
| Test Case Design - ERP | M4-W2 | M5-W1 | 3 weeks | QA Engineer (ERP) |
| IoT Test Environment Setup | M4-W2 | M4-W4 | 2 weeks | DevOps, IoT Specialist |
| Mobile Test Lab Setup | M4-W3 | M4-W4 | 2 weeks | QA Lead |
| Unit Testing | M4-W3 | M5-W1 | 3 weeks | Development Team |
| Farm Module SIT | M5-W1 | M5-W2 | 2 weeks | QA Engineer (Farm) |
| IoT Integration Testing | M5-W2 | M5-W4 | 3 weeks | QA Engineer (IoT) |
| Mobile App Testing | M5-W3 | M6-W2 | 4 weeks | QA Engineer (Mobile) |
| ERP Module SIT | M5-W2 | M5-W4 | 3 weeks | QA Engineer (ERP) |
| Phase 1-2 Integration | M5-W4 | M6-W2 | 3 weeks | QA Lead |
| System Testing | M5-W3 | M6-W1 | 3 weeks | Full QA Team |
| Performance Testing | M6-W1 | M6-W2 | 2 weeks | Performance Engineer |
| Security Testing | M6-W1 | M6-W2 | 2 weeks | Security Specialist |
| UAT Preparation | M6-W2 | M6-W3 | 2 weeks | QA Lead, BA |
| UAT Execution | M6-W3 | M6-W4 | 2 weeks | Business Users, QA |
| Bug Fix & Regression | M6-W3 | M6-W4 | 2 weeks | Development Team |

### 3.3 Integration with Phase 1 Components

| Integration Point | Phase 1 Component | Phase 2 Component | Testing Approach |
|-------------------|-------------------|-------------------|------------------|
| Farm → Inventory | Inventory Management | Farm Medicine/Feed Inventory | Data sync, stock movements, valuation |
| Farm → Accounting | General Ledger | Farm Production Costs | Cost allocation, GL entries, P&L impact |
| Farm → Purchase | Purchase Orders | Feed/Supply Procurement | PO generation, GRN, invoice matching |
| MRP → Inventory | Inventory & Warehouse | Manufacturing BOM | BOM accuracy, stock reservations |
| Quality → Inventory | Inventory Tracking | Quality Control | Quarantine, release, reject workflows |
| Sales → Farm | Sales Orders | Traceability | Batch tracking from farm to sale |

---

## 4. Test Environment

### 4.1 Environment Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         PHASE 2 TEST ENVIRONMENT                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                    TEST MANAGEMENT LAYER                             │    │
│  │  • TestRail / Jira Test Management                                   │    │
│  │  • Test Data Repository                                              │    │
│  │  • Automation Framework (Selenium/Appium)                            │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                    APPLICATION UNDER TEST                            │    │
│  │  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐                 │    │
│  │  │  Farm Mgmt   │ │  ERP (MRP)   │ │    Mobile    │                 │    │
│  │  │   Module     │ │   Module     │ │    APIs      │                 │    │
│  │  └──────────────┘ └──────────────┘ └──────────────┘                 │    │
│  │  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐                 │    │
│  │  │   Quality    │ │  Traceability│ │  Integration │                 │    │
│  │  │   Module     │ │   Module     │ │    Layer     │                 │    │
│  │  └──────────────┘ └──────────────┘ └──────────────┘                 │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                    IoT TEST SANDBOX                                  │    │
│  │  • MQTT Broker (Mosquitto/RabbitMQ)                                  │    │
│  │  • IoT Gateway Simulator                                             │    │
│  │  • Sensor Data Simulator                                             │    │
│  │  • RFID Emulator                                                     │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                    BACKEND INFRASTRUCTURE                            │    │
│  │  • Application Server (Odoo/ERPNext)                                 │    │
│  │  • Database (PostgreSQL/MySQL)                                       │    │
│  │  • Cache Layer (Redis)                                               │    │
│  │  • Message Queue                                                     │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 4.2 IoT Test Sandbox

| Component | Specification | Purpose |
|-----------|---------------|---------|
| **MQTT Broker** | Mosquitto 2.x or RabbitMQ 3.x | Message broker for IoT device communication |
| **IoT Gateway** | Raspberry Pi 4 or similar | Physical gateway for connecting sensors |
| **Sensor Simulator** | Python/MQTT scripts | Simulate milk meters, temperature sensors, RFID |
| **RFID Emulator** | Software-based | Simulate cattle tag readings |
| **Data Logger** | InfluxDB/TimescaleDB | Store time-series sensor data |

### 4.3 Mobile Test Lab

| Device Type | Model | OS Version | Quantity |
|-------------|-------|------------|----------|
| Android Phone (Low-end) | Samsung Galaxy A13 | Android 12 | 2 |
| Android Phone (Mid-range) | Samsung Galaxy A54 | Android 13 | 2 |
| Android Phone (High-end) | Samsung Galaxy S23 | Android 14 | 1 |
| iPhone | iPhone 12 | iOS 17 | 2 |
| iPhone | iPhone 15 | iOS 17 | 1 |
| Android Tablet | Samsung Tab A8 | Android 13 | 2 |
| iPad | iPad 10th Gen | iPadOS 17 | 1 |

### 4.4 Environment Configuration

| Parameter | Development | QA | UAT | Production |
|-----------|-------------|-----|-----|------------|
| **Server Specs** | 4 vCPU, 8GB RAM | 8 vCPU, 16GB RAM | 8 vCPU, 16GB RAM | 16 vCPU, 32GB RAM |
| **Database** | PostgreSQL 15 | PostgreSQL 15 | PostgreSQL 15 | PostgreSQL 15 Cluster |
| **SSL** | Self-signed | Let's Encrypt | Let's Encrypt | Commercial SSL |
| **Data** | Synthetic | Masked Production | Production Snapshot | Production |
| **Uptime** | Business hours | 24x7 | Business hours | 24x7 |

---

## 5. Test Deliverables

### 5.1 Test Planning Deliverables

| Deliverable | Description | Due Date |
|-------------|-------------|----------|
| Test Plan Document (this document) | Comprehensive test strategy and approach | M4-W2 |
| Test Case Repository | Detailed test cases for all components | M5-W1 |
| Test Data Plan | Data requirements and generation strategy | M4-W3 |
| Test Environment Plan | Environment specifications and setup guide | M4-W2 |
| Test Automation Strategy | Framework selection and automation plan | M4-W2 |

### 5.2 Test Execution Deliverables

| Deliverable | Description | Frequency |
|-------------|-------------|-----------|
| Daily Test Execution Report | Test cases executed, passed, failed, blocked | Daily |
| Defect Report | Detailed bug reports with reproduction steps | As needed |
| Test Summary Report | Weekly summary of testing progress | Weekly |
| IoT Test Results | Sensor accuracy, connectivity, data validation | Per sprint |
| Mobile Test Results | Device compatibility, performance metrics | Per sprint |
| Integration Test Results | Cross-module integration validation | Per sprint |
| Performance Test Report | Load testing, stress testing results | M6-W2 |
| Security Test Report | Vulnerability assessment, penetration test | M6-W2 |

### 5.3 Certifications and Compliance Reports

| Certification | Description | Evidence Required |
|---------------|-------------|-------------------|
| **IoT Accuracy Certification** | Sensor data accuracy validation | Calibration reports, accuracy measurements |
| **Mobile App Compliance** | App store compliance (Google Play, App Store) | Privacy policy, terms of service, app review |
| **Data Integrity Certification** | Farm data accuracy and completeness | Data validation reports, audit trails |
| **Traceability Compliance** | FSSAI/BFSA traceability requirements | End-to-end traceability test results |
| **Performance Certification** | System meets performance requirements | Load test results, response time metrics |

### 5.4 Final Deliverables

| Deliverable | Description | Due Date |
|-------------|-------------|----------|
| Phase 2 Test Summary Report | Comprehensive testing summary with metrics | M6-W4 |
| UAT Sign-off Document | Formal acceptance from business stakeholders | M6-W4 |
| Test Closure Report | Lessons learned, recommendations | M6-W4 |
| Defect Summary Report | All defects with resolution status | M6-W4 |
| Test Asset Handover | Test cases, scripts, data for maintenance | M6-W4 |

---

## 6. Resource Planning

### 6.1 Team Structure

```
                    ┌─────────────────┐
                    │   QA Lead       │
                    │  (1 resource)   │
                    └────────┬────────┘
                             │
        ┌────────────────────┼────────────────────┐
        │                    │                    │
        ▼                    ▼                    ▼
┌───────────────┐    ┌───────────────┐    ┌───────────────┐
│  Farm Testing │    │   IoT Testing │    │  ERP Testing  │
│  Specialist   │    │   Specialist  │    │   Specialist  │
│ (1 resource)  │    │ (1 resource)  │    │ (1 resource)  │
└───────────────┘    └───────────────┘    └───────────────┘
        │                    │                    │
        ▼                    ▼                    ▼
┌───────────────┐    ┌───────────────┐    ┌───────────────┐
│ Mobile Testing│    │   Automation  │    │   Performance │
│  Specialist   │    │    Engineer   │    │    Engineer   │
│ (1 resource)  │    │ (1 resource)  │    │ (1 resource)  │
└───────────────┘    └───────────────┘    └───────────────┘
```

### 6.2 Role Definitions

| Role | Responsibilities | Skills Required |
|------|------------------|-----------------|
| **QA Lead** | Overall test planning, coordination, reporting, stakeholder management | 8+ years QA, ERP testing, leadership |
| **Farm Testing Specialist** | Farm management module testing, domain expertise in dairy operations | Dairy/agriculture domain knowledge, ERP testing |
| **IoT Testing Specialist** | IoT platform testing, sensor validation, MQTT protocol testing | IoT protocols, MQTT, hardware testing |
| **Mobile Testing Specialist** | iOS/Android app testing, offline mode, device compatibility | Mobile testing, Appium, manual testing |
| **ERP Testing Specialist** | MRP, Quality, Traceability module testing | ERP testing, manufacturing domain |
| **Automation Engineer** | Test automation framework, automated test development | Python/Java, Selenium, Appium |
| **Performance Engineer** | Load testing, stress testing, performance optimization | JMeter, Locust, performance analysis |

### 6.3 Training Requirements

| Training Topic | Target Audience | Duration | Provider |
|----------------|-----------------|----------|----------|
| Dairy Farm Operations | QA Team | 2 days | Farm Manager |
| IoT Fundamentals & MQTT | IoT Specialist | 3 days | External/Online |
| Mobile Testing Best Practices | Mobile Specialist | 2 days | External/Online |
| Manufacturing/MRP Concepts | ERP Specialist | 2 days | Business Analyst |
| Odoo/ERPNext Platform | All QA | 5 days | Implementation Partner |
| Farm Management Software | Farm Specialist | 3 days | Implementation Partner |

### 6.4 Tools and Licenses

| Tool Category | Tool | Purpose | License Count |
|---------------|------|---------|---------------|
| Test Management | TestRail or Jira | Test case management, execution tracking | 7 users |
| Defect Tracking | Jira | Bug tracking, workflow management | 7 users |
| Automation | Selenium Grid | Web automation | Open source |
| Mobile Automation | Appium | Mobile app automation | Open source |
| Performance | JMeter | Load and performance testing | Open source |
| API Testing | Postman | REST API testing | 5 users |
| Device Lab | BrowserStack/Sauce Labs | Cloud device testing | Enterprise |
| IoT Testing | MQTT.fx, MQTT Explorer | MQTT broker testing | Open source |
| Database | DBeaver | Database validation | Open source |

---

## 7. Test Execution Strategy

### 7.1 Farm Operations Testing Approach

#### 7.1.1 Herd Management Testing

| Test Scenario | Test Type | Priority |
|---------------|-----------|----------|
| Cattle registration with RFID/ear tag | Functional | Critical |
| Individual cattle profile creation | Functional | Critical |
| Lineage and breeding history tracking | Functional | High |
| Breed classification and genetic data | Functional | High |
| Cattle movement (location changes) | Functional | Medium |
| Cattle status changes (active, sold, deceased) | Functional | Critical |
| Bulk cattle import from CSV | Functional | Medium |
| Search and filter cattle records | Usability | Medium |
| RFID tag replacement workflow | Functional | Medium |

#### 7.1.2 Milk Production Testing

| Test Scenario | Test Type | Priority |
|---------------|-----------|----------|
| Daily milk yield entry per cow | Functional | Critical |
| Milk meter data integration | Integration | Critical |
| Fat/SNF test result entry | Functional | Critical |
| Milk quality parameter recording | Functional | High |
| Production trend analysis reports | Functional | High |
| Abnormal yield alert triggers | Functional | High |
| Milking session management (AM/PM) | Functional | High |
| Milk collection summary generation | Functional | Medium |
| Integration with inventory for milk stock | Integration | Critical |

#### 7.1.3 Health Management Testing

| Test Scenario | Test Type | Priority |
|---------------|-----------|----------|
| Vaccination schedule creation | Functional | Critical |
| Vaccination due alerts | Functional | Critical |
| Treatment record entry | Functional | Critical |
| Veterinary visit scheduling | Functional | High |
| Disease outbreak tracking | Functional | High |
| Medicine inventory integration | Integration | High |
| Withdrawal period calculations | Functional | Critical |
| Health certificate generation | Functional | Medium |
| Medical history reports | Functional | Medium |

### 7.2 Test Execution Workflow

```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│  Test Case  │───▶│   Execute   │───▶│   Result    │───▶│   Action    │
│  Selection  │    │    Test     │    │  Evaluation │    │             │
└─────────────┘    └─────────────┘    └─────────────┘    └──────┬──────┘
                                                                 │
                    ┌─────────────────────────────────────────────┼─────┐
                    │                                             │     │
                    ▼                                             ▼     ▼
            ┌─────────────┐                               ┌─────────────┐
            │    PASS     │                               │    FAIL     │
            └──────┬──────┘                               └──────┬──────┘
                   │                                             │
                   ▼                                             ▼
            ┌─────────────┐                               ┌─────────────┐
            │ Update Test │                               │ Log Defect  │
            │   Status    │                               │ in Jira     │
            └─────────────┘                               └──────┬──────┘
                                                                 │
                                                                 ▼
                                                          ┌─────────────┐
                                                          │ Assign to   │
                                                          │ Developer   │
                                                          └─────────────┘
```

### 7.3 Test Case Metrics

| Metric | Target | Measurement |
|--------|--------|-------------|
| Test Case Coverage | ≥ 95% | Requirements covered by test cases |
| Test Case Execution | 100% | All planned test cases executed |
| Pass Rate | ≥ 95% | Test cases passed on first run |
| Defect Density | < 5 per 100 hours | Defects per testing effort |
| Critical Defects | 0 open at UAT | All P1 defects resolved |
| Test Case Productivity | 10 cases/day per tester | Test case creation rate |

---

## 8. IoT Testing

### 8.1 IoT Testing Strategy

IoT testing ensures accurate data collection from farm sensors, reliable communication, and proper integration with the farm management system.

### 8.2 MQTT Protocol Testing

| Test Category | Test Scenarios | Expected Result |
|---------------|----------------|-----------------|
| **Connection** | Device connects to MQTT broker | Connection established within 5 seconds |
| | Device reconnection after network loss | Automatic reconnection within 30 seconds |
| | Multiple device connections | Broker handles 50+ concurrent connections |
| **Topics** | Correct topic structure (farm/sensor/type/id) | Messages published to correct topics |
| | Topic subscription | System subscribes to relevant topics |
| | Wildcard subscriptions | Proper handling of # and + wildcards |
| **QoS Levels** | QoS 0 (at most once) | Fire-and-forget delivery |
| | QoS 1 (at least once) | Guaranteed delivery with duplicates |
| | QoS 2 (exactly once) | Guaranteed single delivery |
| **Messages** | JSON payload validation | Valid JSON structure with required fields |
| | Message size limits | Messages under 256KB accepted |
| | Message frequency | Proper handling of high-frequency data |

### 8.3 Sensor Data Accuracy Testing

| Sensor Type | Accuracy Test | Tolerance | Calibration |
|-------------|---------------|-----------|-------------|
| **Milk Meter** | Volume measurement | ± 0.5% | Weekly |
| | Temperature reading | ± 0.5°C | Monthly |
| | Conductivity | ± 1% | Monthly |
| **Milk Quality Analyzer** | Fat % reading | ± 0.1% | Daily |
| | SNF % reading | ± 0.1% | Daily |
| | Protein % reading | ± 0.05% | Weekly |
| | Density reading | ± 0.5 kg/m³ | Weekly |
| **Temperature Sensor** | Ambient temperature | ± 0.5°C | Monthly |
| | Cold storage temp | ± 0.3°C | Weekly |
| **Humidity Sensor** | Relative humidity | ± 3% RH | Monthly |
| **RFID Reader** | Tag read accuracy | 99.9% | Monthly |
| | Read range | 1-5 meters | Monthly |
| | Multiple tag read | 20 tags/second | Monthly |

### 8.4 IoT Integration Test Cases

| Test ID | Description | Steps | Expected Result |
|---------|-------------|-------|-----------------|
| IOT-001 | Milk meter data to farm system | 1. Simulate milk meter reading<br>2. Publish to MQTT topic<br>3. Verify database update | Milk production record created with correct values |
| IOT-002 | RFID cattle identification | 1. Simulate RFID tag scan<br>2. Verify tag lookup<br>3. Display cattle profile | Correct cattle profile displayed |
| IOT-003 | Temperature alert generation | 1. Send temperature above threshold<br>2. Wait for processing<br>3. Check alert notification | Alert generated and sent to operators |
| IOT-004 | Sensor offline detection | 1. Stop sensor heartbeat<br>2. Wait timeout period<br>3. Check system status | Sensor marked as offline, alert generated |
| IOT-005 | Historical data aggregation | 1. Send 24 hours of sensor data<br>2. Run aggregation job<br>3. Verify summary reports | Accurate daily/weekly summaries generated |
| IOT-006 | Data synchronization | 1. Disconnect network<br>2. Generate local data<br>3. Reconnect and sync | All data synchronized correctly |

### 8.5 IoT Performance Testing

| Test Type | Scenario | Target Metric |
|-----------|----------|---------------|
| **Throughput** | 100 sensors sending data every minute | < 100ms processing time per message |
| **Burst Handling** | 1000 messages in 10 seconds | No message loss, queue management |
| **Long-term Stability** | 7 days continuous operation | 99.9% uptime, no memory leaks |
| **Network Latency** | High latency (500ms) simulation | Graceful handling, no timeouts |
| **Packet Loss** | 5% packet loss simulation | QoS 1 ensures data delivery |

---

## 9. Mobile Testing

### 9.1 Mobile Testing Strategy

Mobile testing covers two applications: Farmer App and Field Sales App on both iOS and Android platforms.

### 9.2 Device Coverage Matrix

| Device Category | Resolution | OS Version | Test Priority |
|-----------------|------------|------------|---------------|
| Android Phone (Small - 5") | 720x1440 | Android 11, 12, 13, 14 | High |
| Android Phone (Medium - 6") | 1080x2400 | Android 12, 13, 14 | Critical |
| Android Phone (Large - 6.7") | 1080x2400 | Android 13, 14 | High |
| Android Tablet | 1920x1200 | Android 12, 13 | Medium |
| iPhone (Small - SE) | 750x1334 | iOS 16, 17 | High |
| iPhone (Medium - Standard) | 1170x2532 | iOS 16, 17 | Critical |
| iPhone (Large - Pro Max) | 1290x2796 | iOS 17 | High |
| iPad | 2360x1640 | iPadOS 16, 17 | Medium |

### 9.3 Functional Testing

#### 9.3.1 Farmer App - Functional Tests

| Feature | Test Scenarios |
|---------|----------------|
| **Login/Authentication** | Valid credentials, invalid credentials, biometric login, session timeout |
| **Cattle Data Entry** | Add new cattle, update profile, photo capture, RFID scanning |
| **Milk Recording** | Daily entry, AM/PM sessions, offline entry, bulk entry |
| **Health Logging** | Vaccination entry, treatment notes, veterinary visit log |
| **Task Management** | View tasks, mark complete, add notes, photo evidence |
| **Dashboard** | Production summary, alerts, upcoming tasks, quick actions |
| **Reports** | View production reports, export data, share reports |

#### 9.3.2 Field Sales App - Functional Tests

| Feature | Test Scenarios |
|---------|----------------|
| **Route Management** | View route, optimize route, check-in/check-out, GPS tracking |
| **Customer Management** | View customer list, add new customer, update profile, visit history |
| **Order Taking** | Create order, edit order, apply discounts, calculate totals |
| **Inventory Check** | Real-time stock check, reservation, backorder handling |
| **Payment Collection** | Record payment, generate receipt, partial payment, credit note |
| **Digital Signature** | Customer signature capture, upload, document generation |
| **Offline Mode** | Full offline functionality, sync when connected, conflict resolution |

### 9.4 Offline Mode Testing

| Test Scenario | Steps | Expected Result |
|---------------|-------|-----------------|
| Offline Data Entry | 1. Enable airplane mode<br>2. Enter milk production data<br>3. Save locally | Data saved to local storage |
| Offline Queue | 1. Enter multiple records offline<br>2. View pending sync queue<br>3. Count items | All records queued for sync |
| Auto Sync | 1. Have pending records<br>2. Disable airplane mode<br>3. Wait for sync | All records automatically synced |
| Conflict Resolution | 1. Edit record offline<br>2. Same record edited online<br>3. Sync offline device | Conflict detected, user prompted |
| Offline Indicator | 1. Disconnect network<br>2. Check app UI | Clear offline mode indicator visible |
| Background Sync | 1. Add records offline<br>2. Close app<br>3. Connect network, reopen | Background sync completed |

### 9.5 Scanning and NFC Testing

| Scan Type | Test Scenarios |
|-----------|----------------|
| **Barcode Scanning** | Standard 1D barcodes (Code 128, EAN-13), 2D barcodes (QR, DataMatrix) |
| **RFID Scanning** | LF RFID (125kHz), HF RFID (13.56MHz), read range validation |
| **Camera Scanning** | Various lighting conditions, damaged codes, scan speed |
| **NFC Tag Reading** | NFC-enabled cattle tags, read distance, tag writing |

### 9.6 Mobile Performance Testing

| Metric | Target | Test Method |
|--------|--------|-------------|
| App Launch Time | < 3 seconds | Cold start measurement |
| Screen Load Time | < 2 seconds | Navigation timing |
| API Response Time | < 500ms | Network request monitoring |
| Battery Consumption | < 5% per hour active use | Battery monitoring tools |
| Memory Usage | < 200MB | Memory profiling |
| Storage Usage | < 100MB app size | Installation size check |
| Offline Sync Speed | < 5 seconds per 100 records | Sync performance test |

---

## 10. Integration Testing

### 10.1 Integration Testing Strategy

Integration testing validates the data flow and functionality between Phase 2 components and between Phase 2 and Phase 1 systems.

### 10.2 Phase 2 Internal Integration

| Integration | From Module | To Module | Test Focus |
|-------------|-------------|-----------|------------|
| INT-001 | IoT Platform | Farm Management | Sensor data to cattle records |
| INT-002 | Farm Management | Inventory | Medicine/feed stock movements |
| INT-003 | Farm Management | Quality Management | Milk quality test results |
| INT-004 | Mobile Apps | Farm Management | Data sync, offline handling |
| INT-005 | Manufacturing MRP | Inventory | BOM consumption, production receipts |
| INT-006 | Quality Management | Manufacturing | Quality holds, release decisions |
| INT-007 | Traceability | All Modules | Batch tracking across operations |

### 10.3 Phase 1-2 Integration

| Integration | Phase 1 | Phase 2 | Test Focus |
|-------------|---------|---------|------------|
| INT-008 | Accounting | Farm Management | Farm expense allocation, cost center posting |
| INT-009 | Purchase | Farm Management | Feed/medicine procurement, GRN |
| INT-010 | Inventory | Farm Management | Stock visibility, transfers |
| INT-011 | Sales | Traceability | Product batch tracking to customer |
| INT-012 | CRM | Farm Management | Supplier management for farm inputs |
| INT-013 | General Ledger | Manufacturing | Production cost postings |
| INT-014 | Inventory | Manufacturing | Raw material allocation, WIP tracking |
| INT-015 | Purchase | Manufacturing | Raw material procurement |

### 10.4 Integration Test Scenarios

#### Scenario 1: End-to-End Milk Production Flow

```
┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐
│  RFID    │───▶│  Milk    │───▶│   Farm   │───▶│  Quality │───▶│ Inventory│
│  Scan    │    │  Meter   │    │   Mgmt   │    │   Mgmt   │    │   Stock  │
└──────────┘    └──────────┘    └──────────┘    └──────────┘    └──────────┘
     │               │               │               │               │
     │               │               │               │               │
     ▼               ▼               ▼               ▼               ▼
Cattle ID     Volume/Temp      Production     Fat/SNF Test    Available
Identified    Recorded         Recorded       Results         for Sale
```

**Test Steps:**
1. Scan RFID tag for cattle identification
2. Milk meter records volume and temperature
3. Data flows to Farm Management system
4. Lab technician enters Fat/SNF test results
5. Quality Management validates and approves
6. Inventory is updated with available stock
7. Traceability record created linking cattle to batch

**Validation Points:**
- Cattle profile updated with latest milking
- Production report reflects accurate totals
- Quality parameters stored correctly
- Inventory quantity increased by milk volume
- Traceability chain complete

#### Scenario 2: Manufacturing Production Flow

```
┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐
│  Sales   │───▶│    MRP   │───▶│    BOM   │───▶│ Production│───▶│ Quality  │
│  Order   │    │  Planning│    │  Explosion│    │  Order    │    │ Control  │
└──────────┘    └──────────┘    └──────────┘    └──────────┘    └──────────┘
```

**Test Steps:**
1. Sales order created for yogurt products
2. MRP runs and generates production orders
3. BOM explodes raw material requirements
4. Inventory reserved for production
5. Production order executed on shop floor
6. Quality control samples taken and tested
7. Finished goods receipt to inventory
8. Batch traceability updated

### 10.5 API Integration Testing

| API Endpoint | Method | Integration Test |
|--------------|--------|------------------|
| `/api/v1/farm/cattle` | POST | Create cattle from RFID scan |
| `/api/v1/farm/milk-production` | POST | Record milk production from meter |
| `/api/v1/iot/sensor-data` | POST | Receive sensor data from MQTT |
| `/api/v1/mobile/sync` | POST | Mobile offline data sync |
| `/api/v1/mrp/production-order` | POST | Create production order |
| `/api/v1/quality/inspection` | POST | Submit quality test results |
| `/api/v1/inventory/stock-movement` | POST | Record inventory transactions |
| `/api/v1/traceability/batch-trace` | GET | Query batch genealogy |

---

## 11. Entry & Exit Criteria

### 11.1 Entry Criteria

#### 11.1.1 Test Planning Entry

| Criterion | Verification Method |
|-----------|---------------------|
| Phase 2 requirements approved and signed off | Requirements Traceability Matrix |
| Test environment hardware provisioned | Infrastructure checklist |
| Test team onboarded and trained | Training completion certificates |
| Test tools licensed and configured | Tool access verification |

#### 11.1.2 Unit Testing Entry

| Criterion | Verification Method |
|-----------|---------------------|
| Development coding standards review passed | Code review checklist |
| Unit test cases defined | Unit test plan document |
| Test data prepared | Test data validation |
| Development environment stable | Smoke test results |

#### 11.1.3 System Testing Entry

| Criterion | Verification Method |
|-----------|---------------------|
| Unit testing completed | Unit test report |
| Critical/High defects resolved | Defect report review |
| Build deployed to QA environment | Deployment confirmation |
| Smoke tests passed | Smoke test results |
| Test cases reviewed and approved | Test case review sign-off |

#### 11.1.4 UAT Entry

| Criterion | Verification Method |
|-----------|---------------------|
| System testing completed | System test report |
| All Critical and High defects resolved | Defect status report |
| Performance testing passed | Performance test report |
| Security testing passed | Security assessment |
| UAT test cases prepared | UAT test case review |
| Farm users trained | Training attendance |

### 11.2 Exit Criteria

#### 11.2.1 Unit Testing Exit

| Criterion | Target |
|-----------|--------|
| Unit test coverage | ≥ 80% |
| Unit test pass rate | 100% |
| Critical defects open | 0 |
| Code review issues | All resolved |

#### 11.2.2 System Testing Exit

| Criterion | Target |
|-----------|--------|
| Test case execution | 100% |
| Test case pass rate | ≥ 95% |
| Critical defects open | 0 |
| High defects open | 0 |
| Medium defects open | ≤ 5 |
| Low defects open | ≤ 10 |
| Deferred defects approved | Change Advisory Board |

#### 11.2.3 UAT Exit (Phase 2 Go-Live)

| Criterion | Target |
|-----------|--------|
| UAT test case execution | 100% |
| Business sign-off obtained | Signed document |
| Critical business scenarios passed | All pass |
| Training completion | 100% of users |
| Data migration validated | Migration report |
| Rollback plan documented | DR plan approved |
| Support team ready | Support roster confirmed |

### 11.3 Build Acceptance Criteria

| Check | Criteria |
|-------|----------|
| **Smoke Test** | All critical paths executable |
| **Login** | All user roles can authenticate |
| **Navigation** | All menus and screens accessible |
| **Data Loading** | Sample data loads without errors |
| **API Health** | All API endpoints respond |
| **Reports** | Key reports generate successfully |
| **Mobile Apps** | Apps install and launch |
| **IoT Connectivity** | Test sensors connect and transmit |

---

## 12. Defect Management

### 12.1 Defect Severity Definitions

#### Farm Operations Specific

| Severity | Definition | Example |
|----------|------------|---------|
| **Critical (S1)** | Complete failure of core farm operation, data loss, regulatory non-compliance | Milk production data not saved, traceability chain broken, wrong medication alert not triggered |
| **High (S2)** | Major feature not working, significant workaround required | RFID tags not reading, offline sync failing, production reports incorrect |
| **Medium (S3)** | Feature partially working, minor workaround | UI display issues, slow report generation, missing filters |
| **Low (S4)** | Cosmetic issues, suggestions for improvement | Typos, alignment issues, color inconsistency |

#### IoT Specific

| Severity | Definition | Example |
|----------|------------|---------|
| **Critical** | Sensor data not reaching system, incorrect critical alerts | Milk meter not recording, temperature alerts not firing |
| **High** | Intermittent connectivity, accuracy issues | RFID reads sporadic, sensor readings off by > tolerance |
| **Medium** | Delayed data, minor accuracy variance | Data arrives with 5-minute delay, sensor within 2x tolerance |
| **Low** | Configuration issues, display formatting | Timestamp format, sensor naming |

#### Mobile Specific

| Severity | Definition | Example |
|----------|------------|---------|
| **Critical** | App crash, data loss, security breach | App crashes on startup, offline data lost, unauthorized access |
| **High** | Core feature not working on specific device | Camera not working, sync failing on certain OS version |
| **Medium** | Feature works with limitations | Slow performance, UI issues on small screens |
| **Low** | Minor UI/UX issues | Button spacing, icon resolution |

### 12.2 Defect Priority Matrix

| Severity | Priority | Resolution Target |
|----------|----------|-------------------|
| Critical | P1 - Immediate | 24 hours |
| High | P2 - Urgent | 72 hours |
| Medium | P3 - Normal | 1 week |
| Low | P4 - Low | Next release |

### 12.3 Defect Lifecycle

```
┌─────────┐     ┌─────────┐     ┌─────────┐     ┌─────────┐     ┌─────────┐
│   New   │────▶│  Assign │────▶│   Open  │────▶│  Fixed  │────▶│  Verify │
│         │     │         │     │         │     │         │     │         │
└─────────┘     └─────────┘     └─────────┘     └─────────┘     └────┬────┘
                                                                     │
    ┌─────────────────────────────────────────────────────────────────┘
    │
    ▼
┌─────────┐     ┌─────────┐     ┌─────────┐
│  Closed │◀────│ Reopen  │◀────│ Reject  │
│         │     │         │     │         │
└─────────┘     └─────────┘     └─────────┘
    │
    │ (if not a defect)
    ▼
┌─────────┐
│ Not a   │
│  Bug    │
└─────────┘
```

### 12.4 Defect Reporting Template

```markdown
## Defect Report

**Defect ID:** BUG-XXX
**Title:** [Brief description]
**Severity:** [Critical/High/Medium/Low]
**Priority:** [P1/P2/P3/P4]
**Module:** [Farm/IoT/Mobile/ERP]
**Environment:** [QA/UAT]
**Reporter:** [Name]
**Date Reported:** [Date]

### Description
[Detailed description of the issue]

### Steps to Reproduce
1. Step 1
2. Step 2
3. Step 3

### Expected Result
[What should happen]

### Actual Result
[What actually happens]

### Evidence
- Screenshot: [attach]
- Video: [attach]
- Log files: [attach]
- Test Data: [attach]

### Impact
[Business impact description]

### Affected Users
[Who is affected by this defect]
```

### 12.5 Defect Metrics and Reporting

| Metric | Calculation | Target |
|--------|-------------|--------|
| Defect Density | Total Defects / Size (FP or KLOC) | < 5 per 100 FP |
| Defect Removal Efficiency | Defects found in Testing / Total Defects | > 95% |
| Defect Rejection Rate | Rejected Defects / Total Reported | < 10% |
| Mean Time to Fix | Sum(Fix Times) / Number of Defects | < 3 days for High+ |
| Defect Age | Days from Open to Closed | < 5 days for Medium+ |

---

## 13. UAT Preparation

### 13.1 UAT Strategy

User Acceptance Testing (UAT) validates that the system meets business requirements and is ready for production use. Phase 2 UAT focuses on farm operations and requires active participation from farm staff.

### 13.2 UAT Participants

| Role | Number | Responsibility |
|------|--------|----------------|
| Farm Manager | 1 | Overall UAT coordination, approval |
| Herd Supervisor | 1 | Herd management testing |
| Milk Production Supervisor | 1 | Milk recording, quality testing |
| Veterinary Officer | 1 | Health management testing |
| Field Sales Representatives | 2 | Mobile sales app testing |
| Farm Workers | 3 | Mobile farmer app testing |
| Inventory Manager | 1 | Integration with inventory |
| Quality Manager | 1 | Quality management testing |

### 13.3 Farm Worker Training Plan

#### Training Schedule

| Session | Topic | Duration | Audience |
|---------|-------|----------|----------|
| Day 1 - Morning | System Overview, Login, Navigation | 2 hours | All users |
| Day 1 - Afternoon | Herd Management Module | 3 hours | Herd Supervisor, Farm Manager |
| Day 2 - Morning | Milk Production Recording | 3 hours | Milk Production Supervisor |
| Day 2 - Afternoon | Health Management | 2 hours | Veterinary Officer |
| Day 3 - Morning | Mobile App - Farmer App | 3 hours | Farm Workers |
| Day 3 - Afternoon | Mobile App - Field Sales App | 3 hours | Sales Representatives |
| Day 4 - Morning | Quality Management | 2 hours | Quality Manager |
| Day 4 - Afternoon | Reporting and Dashboards | 2 hours | All users |

#### Training Materials

| Material | Description | Format |
|----------|-------------|--------|
| User Manual | Comprehensive guide for all modules | PDF, Printed |
| Quick Reference Guide | One-page guides for common tasks | Laminated cards |
| Video Tutorials | Screen recordings of key workflows | MP4, YouTube |
| Interactive Simulations | Practice environment for hands-on | Web-based |
| FAQ Document | Common questions and answers | PDF, Intranet |
| Mobile App Guide | Device-specific instructions | PDF, Mobile-friendly |

### 13.4 UAT Test Scenarios

#### Scenario 1: Daily Milk Production Workflow

| Step | Action | Expected Result | Verification |
|------|--------|-----------------|--------------|
| 1 | Login as Farm Worker | Dashboard displayed | ✓ |
| 2 | Select "Milk Recording" | Milk recording screen opens | ✓ |
| 3 | Scan RFID tag | Cattle profile displayed | ✓ |
| 4 | Enter milk volume | Volume accepted | ✓ |
| 5 | Enter temperature | Temperature accepted | ✓ |
| 6 | Save record | Record saved, success message | ✓ |
| 7 | View daily summary | Correct totals displayed | ✓ |
| 8 | Sync data (if offline) | All records synced | ✓ |

#### Scenario 2: Health Management - Vaccination Schedule

| Step | Action | Expected Result | Verification |
|------|--------|-----------------|--------------|
| 1 | Login as Veterinary Officer | Dashboard displayed | ✓ |
| 2 | Navigate to Health → Vaccination | Vaccination list displayed | ✓ |
| 3 | View upcoming vaccinations | Due vaccines listed | ✓ |
| 4 | Select cattle, record vaccination | Vaccination recorded | ✓ |
| 5 | Set next due date | Reminder scheduled | ✓ |
| 6 | Generate vaccination report | Report displays correctly | ✓ |

#### Scenario 3: Field Sales - Order Taking

| Step | Action | Expected Result | Verification |
|------|--------|-----------------|--------------|
| 1 | Login as Sales Rep | Route and customers displayed | ✓ |
| 2 | Select customer | Customer profile opens | ✓ |
| 3 | Check inventory availability | Current stock displayed | ✓ |
| 4 | Add products to order | Order totals calculated | ✓ |
| 5 | Apply customer pricing | Correct price applied | ✓ |
| 6 | Get customer signature | Signature captured | ✓ |
| 7 | Submit order | Order confirmed, receipt generated | ✓ |
| 8 | Sync when online | Order synced to ERP | ✓ |

### 13.5 UAT Acceptance Criteria

| Criterion | Target |
|-----------|--------|
| UAT test case execution | 100% |
| UAT pass rate | ≥ 95% |
| Critical business scenarios | 100% pass |
| Farm worker satisfaction | ≥ 4.0/5.0 |
| System usability rating | ≥ 4.0/5.0 |
| Training effectiveness | ≥ 90% assessment pass |
| Data accuracy validation | 100% match with manual records |
| Performance during peak usage | < 3 second response time |

### 13.6 UAT Sign-off Process

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│  UAT Execution  │───▶│  Issue Resolution│───▶│  Final Review   │
│                 │    │                 │    │                 │
└─────────────────┘    └─────────────────┘    └────────┬────────┘
                                                        │
                                                        ▼
                                               ┌─────────────────┐
                                               │  Sign-off       │
                                               │  Document       │
                                               │                 │
                                               │  • Farm Manager │
                                               │  • IT Manager   │
                                               │  • Project Mgr  │
                                               │  • QA Lead      │
                                               └─────────────────┘
                                                        │
                                                        ▼
                                               ┌─────────────────┐
                                               │  Go-Live        │
                                               │  Approval       │
                                               └─────────────────┘
```

---

## 14. Appendices

### Appendix A: Test Case Template

```markdown
## Test Case ID: TC-XXX

**Test Case Name:** [Descriptive name]
**Module:** [Farm/IoT/Mobile/ERP/Integration]
**Feature:** [Specific feature]
**Priority:** [Critical/High/Medium/Low]
**Type:** [Functional/Integration/Performance/Security/Usability]
**Related Requirement:** [REQ-XXX]

### Preconditions
- [List preconditions]

### Test Data
| Field | Value |
|-------|-------|
| Data 1 | Value 1 |
| Data 2 | Value 2 |

### Test Steps
| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | [Action] | [Expected result] |
| 2 | [Action] | [Expected result] |
| 3 | [Action] | [Expected result] |

### Postconditions
- [List postconditions]

### Test Results
| Execution Date | Tester | Status | Notes |
|----------------|--------|--------|-------|
| [Date] | [Name] | [Pass/Fail] | [Notes] |
```

### Appendix B: Test Data Requirements

#### B.1 Master Data

| Data Type | Quantity | Format | Source |
|-----------|----------|--------|--------|
| Cattle Profiles | 255 records | CSV/Excel | Farm records |
| RFID Tags | 255 records | CSV | Asset database |
| Farm Workers | 15 users | Manual entry | HR records |
| Medicine Inventory | 50 SKUs | CSV | Purchase records |
| Feed Inventory | 20 SKUs | CSV | Purchase records |
| Suppliers | 30 vendors | CSV | Existing ERP |
| Equipment | 100 assets | CSV | Asset register |

#### B.2 Transactional Data

| Data Type | Quantity | Period | Purpose |
|-----------|----------|--------|---------|
| Historical Milk Production | 27,000 records | 1 year | Trend analysis, reporting |
| Health Records | 500 records | 1 year | Health tracking validation |
| Breeding Records | 100 records | 2 years | Reproduction tracking |
| Vaccination History | 1,000 records | 1 year | Schedule validation |
| Sensor Readings | 1 million+ | 3 months | IoT testing |

#### B.3 Test Data Generation Scripts

| Script | Purpose | Output |
|--------|---------|--------|
| generate_cattle_data.py | Create realistic cattle profiles | CSV file |
| generate_milk_data.py | Generate daily production records | CSV file |
| generate_sensor_data.py | Simulate IoT sensor readings | MQTT messages |
| generate_health_records.py | Create veterinary records | CSV file |

### Appendix C: Testing Checklists

#### C.1 Pre-Test Checklist

| # | Item | Status |
|---|------|--------|
| 1 | Test environment provisioned | [ ] |
| 2 | Test data loaded and validated | [ ] |
| 3 | User accounts created | [ ] |
| 4 | Test cases reviewed and approved | [ ] |
| 5 | Defect tracking configured | [ ] |
| 6 | Test management tool configured | [ ] |
| 7 | Mobile devices provisioned | [ ] |
| 8 | IoT sandbox configured | [ ] |
| 9 | Test schedule communicated | [ ] |
| 10 | Stakeholders informed | [ ] |

#### C.2 Build Acceptance Checklist

| # | Item | Status |
|---|------|--------|
| 1 | Build deployed successfully | [ ] |
| 2 | Application starts without errors | [ ] |
| 3 | Database migrations applied | [ ] |
| 4 | Login functionality works | [ ] |
| 5 | Main navigation accessible | [ ] |
| 6 | Critical features functional | [ ] |
| 7 | Mobile apps installable | [ ] |
| 8 | IoT connectivity working | [ ] |
| 9 | Smoke tests passed | [ ] |
| 10 | Known issues documented | [ ] |

#### C.3 UAT Readiness Checklist

| # | Item | Status |
|---|------|--------|
| 1 | System testing completed | [ ] |
| 2 | Critical/High defects resolved | [ ] |
| 3 | Performance testing passed | [ ] |
| 4 | Security testing completed | [ ] |
| 5 | UAT environment ready | [ ] |
| 6 | UAT test cases prepared | [ ] |
| 7 | Users trained | [ ] |
| 8 | Training materials distributed | [ ] |
| 9 | Support team ready | [ ] |
| 10 | Rollback plan documented | [ ] |

### Appendix D: IoT Device Specifications

| Device | Model | Communication | Protocol | Data Points |
|--------|-------|---------------|----------|-------------|
| Milk Meter | DeLaval/GEA | Wired/Wireless | MQTT | Volume, Temp, Conductivity |
| Milk Analyzer | Lactoscan/FOSS | Ethernet/WiFi | MQTT/API | Fat, SNF, Protein, Density |
| RFID Reader | Various | RS-485/Ethernet | MQTT | Tag ID, Timestamp |
| Temperature Sensor | DS18B20/DHT22 | Wireless | MQTT | Temperature, Humidity |
| Activity Monitor | CowManager/Allflex | LoRa/Cellular | MQTT/API | Activity, Rumination, Heat |
| Cold Storage Sensor | Custom | WiFi | MQTT | Temperature, Humidity |

### Appendix E: Mobile Device Matrix

| Device | OS | Screen Size | Resolution | Test Priority |
|--------|-----|-------------|------------|---------------|
| Samsung Galaxy A13 | Android 12 | 6.6" | 1080x2408 | High |
| Samsung Galaxy A54 | Android 13 | 6.4" | 1080x2340 | Critical |
| Samsung Galaxy S23 | Android 14 | 6.1" | 1080x2340 | Critical |
| iPhone 12 | iOS 17 | 6.1" | 1170x2532 | High |
| iPhone 15 | iOS 17 | 6.1" | 1179x2556 | Critical |
| iPhone 15 Pro Max | iOS 17 | 6.7" | 1290x2796 | High |
| Samsung Tab A8 | Android 13 | 10.5" | 1920x1200 | Medium |
| iPad 10th Gen | iPadOS 17 | 10.9" | 2360x1640 | Medium |

### Appendix F: Glossary

| Term | Definition |
|------|------------|
| **BOM** | Bill of Materials - list of raw materials for manufacturing |
| **CoA** | Certificate of Analysis - document certifying quality test results |
| **FEFO** | First Expired, First Out - inventory management method |
| **FIFO** | First In, First Out - inventory management method |
| **FMD** | Foot and Mouth Disease - cattle disease |
| **GRN** | Goods Receipt Note - document for receiving inventory |
| **IoT** | Internet of Things - network of connected devices |
| **MQTT** | Message Queuing Telemetry Transport - lightweight messaging protocol |
| **MRP** | Material Requirements Planning - manufacturing planning system |
| **QoS** | Quality of Service - MQTT message delivery guarantee level |
| **RFID** | Radio Frequency Identification - wireless tag technology |
| **SNF** | Solids-Not-Fat - milk quality parameter |
| **SIT** | System Integration Testing - testing component integration |
| **UAT** | User Acceptance Testing - final validation by business users |
| **WIP** | Work In Progress - partially completed production |

### Appendix G: References

| Document | ID | Version |
|----------|-----|---------|
| Smart Dairy Company Profile | N/A | N/A |
| Smart Dairy RFP | RFP-001 | 1.0 |
| Test Plan - Phase 1 | G-001 | 1.0 |
| Requirements Specification - Phase 2 | REQ-002 | 1.0 |
| Solution Architecture Document | ARCH-001 | 1.0 |
| Security Testing Guidelines | SEC-001 | 1.0 |
| Performance Testing Guidelines | PERF-001 | 1.0 |

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author (QA Lead) | | | |
| Reviewer (Project Manager) | | | |
| Approver (IT Manager) | | | |
| Approver (Farm Manager) | | | |

---

*Document Control: This document is controlled and maintained by the QA Lead. Any changes must be approved by the Project Manager and documented in the version history.*

---

**END OF DOCUMENT**
