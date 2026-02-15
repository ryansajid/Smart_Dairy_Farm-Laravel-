# Integration Test Cases for Smart Dairy Ltd.

---

## Document Information

| Field | Details |
|-------|---------|
| **Document ID** | G-015 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | QA Engineer |
| **Owner** | QA Lead |
| **Reviewer** | Solution Architect |
| **Status** | Draft |
| **Project** | Smart Dairy Smart Web Portal System & Integrated ERP |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Test Environment](#2-test-environment)
3. [Integration Test Scenarios](#3-integration-test-scenarios)
   - 3.1 [End-to-End Order Flow](#31-end-to-end-order-flow)
   - 3.2 [Farm-to-Consumer Traceability](#32-farm-to-consumer-traceability)
   - 3.3 [IoT Integration Flows](#33-iot-integration-flows)
   - 3.4 [Payment Integration Flows](#34-payment-integration-flows)
   - 3.5 [Mobile-ERP Sync Flows](#35-mobile-erp-sync-flows)
   - 3.6 [Inventory-Order Integration](#36-inventory-order-integration)
   - 3.7 [Subscription Management Flow](#37-subscription-management-flow)
4. [Integration Test Summary](#4-integration-test-summary)
5. [Test Data Requirements](#5-test-data-requirements)
6. [Appendices](#6-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines comprehensive integration test cases for the Smart Dairy Smart Web Portal System & Integrated ERP. Integration testing validates that all system components work together correctly, ensuring seamless data flow between modules, external systems, and devices.

### 1.2 Scope

This document covers integration testing for:
- Synchronous and asynchronous system integrations
- Internal module-to-module integrations within the ERP ecosystem
- External system integrations (payment gateways, IoT devices, mobile apps)
- Data transformation and validation across system boundaries
- Error handling and rollback procedures

### 1.3 Systems Under Test

| System Code | System Name | Description |
|-------------|-------------|-------------|
| SYS-001 | B2C E-commerce Portal | Customer-facing online store |
| SYS-002 | B2B Portal | Business partner wholesale platform |
| SYS-003 | ERP Core | Odoo 19 CE/ERPNext main system |
| SYS-004 | Farm Management Module | Herd and production management |
| SYS-005 | Inventory Module | Stock and warehouse management |
| SYS-006 | Payment Gateway | bKash, Nagad, Rocket, SSLCommerz |
| SYS-007 | IoT Platform | Sensor data collection and processing |
| SYS-008 | Mobile Application | Android/iOS field staff app |
| SYS-009 | SMS Gateway | Bulk SMS and OTP service |
| SYS-010 | Email Service | Transactional email system |
| SYS-011 | Cold Chain Monitoring | Temperature and storage monitoring |
| SYS-012 | BI/Analytics | Business intelligence dashboards |

### 1.4 Test Types

| Type | Description |
|------|-------------|
| **Synchronous** | Real-time request/response integrations |
| **Asynchronous** | Message queue, batch processing, scheduled jobs |
| **API-based** | RESTful API integrations |
| **Event-driven** | Webhook, callback-based integrations |
| **File-based** | CSV/Excel import/export integrations |
| **Database** | Direct database synchronization |

---

## 2. Test Environment

### 2.1 Environment Configuration

| Environment | URL/Connection | Purpose |
|-------------|----------------|---------|
| Integration Test | https://test.smartdairybd.com | Primary integration testing |
| Staging | https://staging.smartdairybd.com | Pre-production validation |
| IoT Test Hub | mqtt://test-iot.smartdairybd.com:1883 | IoT device simulation |
| Payment Sandbox | Various sandbox endpoints | Payment gateway testing |

### 2.2 Test Tools

| Tool | Purpose |
|------|---------|
| Postman | API testing and validation |
| MQTT.fx | IoT message testing |
| JMeter | Load and performance testing |
| Selenium | UI automation testing |
| TestRail | Test case management |

---

## 3. Integration Test Scenarios

---

### 3.1 End-to-End Order Flow

#### Integration Test Case INT-001: B2C Order - Complete Flow (Happy Path)

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-001 |
| **Priority** | Critical |
| **Test Type** | Synchronous, End-to-End |
| **Systems Involved** | SYS-001 (B2C Portal), SYS-003 (ERP), SYS-005 (Inventory), SYS-006 (Payment) |

**Test Objective:**
Validate the complete B2C order flow from customer placement through payment processing to order fulfillment.

**Preconditions:**
- Customer account exists and is verified
- Product "Saffron Organic Milk (1000ml)" has stock: 500 units
- Product price: ৳120
- Customer has valid payment method registered
- All systems are operational

**Step-by-Step Flow:**

| Step | Action | System | Data Transformation |
|------|--------|--------|---------------------|
| 1 | Customer logs into B2C portal | SYS-001 | Auth token generated |
| 2 | Customer adds 5 units to cart | SYS-001 | Cart JSON: `{product_id: "MILK001", qty: 5}` |
| 3 | Customer proceeds to checkout | SYS-001 | Checkout session initiated |
| 4 | System validates stock availability | SYS-005 | Query: `SELECT stock FROM products WHERE id='MILK001'` → Returns 500 |
| 5 | Stock reservation created | SYS-005 | `UPDATE products SET reserved=5 WHERE id='MILK001'` |
| 6 | Order created in ERP | SYS-003 | Order JSON with status "PENDING_PAYMENT" |
| 7 | Payment request sent to gateway | SYS-006 | Payment payload: `{amount: 600, order_ref: "ORD001", method: "bKash"}` |
| 8 | Customer completes payment | SYS-006 | Transaction ID: TXN123456 |
| 9 | Payment callback received | SYS-006 → SYS-003 | Callback: `{status: "SUCCESS", txn_id: "TXN123456"}` |
| 10 | Order status updated to "PAID" | SYS-003 | Status transition: PENDING_PAYMENT → PAID |
| 11 | Inventory deducted | SYS-005 | `UPDATE products SET stock=495, reserved=0 WHERE id='MILK001'` |
| 12 | Invoice generated | SYS-003 | PDF invoice created, entry in accounting |
| 13 | Order sent to fulfillment | SYS-003 | Pick list generated for warehouse |
| 14 | Customer notification sent | SYS-009 | SMS: "Your order #ORD001 is confirmed" |

**Expected Results at Each Step:**

| Step | Expected Result |
|------|-----------------|
| 1 | HTTP 200, JWT token valid for 24h |
| 2 | Cart persists in Redis, expiry 30 min |
| 3 | Checkout session ID returned |
| 4 | Stock check returns 500 (sufficient) |
| 5 | Reserved stock = 5, available = 495 |
| 6 | Order record created, ID returned |
| 7 | Payment gateway URL returned |
| 8 | Payment provider confirms success |
| 9 | Webhook received within 5 seconds |
| 10 | Order status = PAID, timestamp recorded |
| 11 | Stock = 495, no reservation |
| 12 | Invoice number generated (INV-YYYY-XXXXX) |
| 13 | Fulfillment queue updated |
| 14 | SMS delivered within 30 seconds |

**Error Handling:**

| Error Scenario | Handling |
|----------------|----------|
| Payment timeout | Order auto-cancels after 30 min, stock released |
| Payment failure | Order status = PAYMENT_FAILED, customer notified |
| Stock insufficient | Error at step 4, customer shown "Out of Stock" |
| ERP unavailable | Order queued in message broker, retry every 5 min |

**Rollback Procedures:**

| Failure Point | Rollback Action |
|---------------|-----------------|
| After Step 5, before Step 6 | Release stock reservation |
| After Step 6, before Step 9 | Order marked CANCELLED after timeout |
| After Step 9, before Step 11 | Payment refunded, order CANCELLED |
| After Step 11 | Manual intervention required, stock audit needed |

---

#### Integration Test Case INT-002: B2C Order - Stock Insufficient

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-002 |
| **Priority** | High |
| **Test Type** | Synchronous, Negative |
| **Systems Involved** | SYS-001, SYS-005 |

**Test Objective:**
Validate system behavior when customer attempts to order more than available stock.

**Preconditions:**
- Product "Saffron Sweet Yogurt" stock: 10 units
- Customer attempts to order 15 units

**Step-by-Step Flow:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Customer adds 15 units to cart | Added to cart |
| 2 | Customer proceeds to checkout | Checkout initiated |
| 3 | System validates stock | Returns available: 10, requested: 15 |
| 4 | Stock check fails | Error response: `{"error": "INSUFFICIENT_STOCK", "available": 10}` |
| 5 | B2C Portal displays error | "Only 10 units available" |
| 6 | Customer can adjust quantity | Cart remains intact |

**Error Handling:**
- No order created
- No payment initiated
- No inventory reserved
- Customer can modify quantity

---

#### Integration Test Case INT-003: B2C Order - Payment Timeout

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-003 |
| **Priority** | High |
| **Test Type** | Asynchronous, Timeout |
| **Systems Involved** | SYS-001, SYS-003, SYS-005, SYS-006 |

**Test Objective:**
Validate order cancellation and stock release when payment is not completed within timeout period.

**Preconditions:**
- Order created with status "PENDING_PAYMENT"
- Stock reserved: 5 units
- Payment timeout: 30 minutes

**Step-by-Step Flow:**

| Step | Action | Time | Expected Result |
|------|--------|------|-----------------|
| 1 | Order created | T+0 | Status: PENDING_PAYMENT |
| 2 | Stock reserved | T+0 | Reserved: 5 |
| 3 | No payment received | T+30min | Timeout job triggered |
| 4 | Order cancelled | T+30min | Status: CANCELLED |
| 5 | Stock released | T+30min | Reserved: 0 |
| 6 | Customer notified | T+30min | SMS: "Order cancelled due to non-payment" |

**Rollback Procedures:**
- Automatic, no manual intervention required
- Audit log records cancellation reason

---

#### Integration Test Case INT-004: B2B Order - Credit Check and Approval Flow

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-004 |
| **Priority** | Critical |
| **Test Type** | Synchronous + Asynchronous, Workflow |
| **Systems Involved** | SYS-002 (B2B Portal), SYS-003 (ERP), SYS-005 (Inventory) |

**Test Objective:**
Validate B2B order flow with credit limit check and approval workflow.

**Preconditions:**
- B2B Partner: "Fresh Mart Ltd"
- Credit limit: ৳100,000
- Current outstanding: ৳75,000
- Available credit: ৳25,000
- Order amount: ৳50,000

**Step-by-Step Flow:**

| Step | Action | System | Data |
|------|--------|--------|------|
| 1 | Partner logs into B2B portal | SYS-002 | Authentication success |
| 2 | Partner creates bulk order | SYS-002 | Order value: ৳50,000 |
| 3 | Credit check initiated | SYS-003 | Query: `SELECT SUM(outstanding) FROM partner_accounts WHERE partner_id='PM001'` |
| 4 | Credit limit validation | SYS-003 | Current: ৳75,000 + New: ৳50,000 = ৳125,000 > Limit: ৳100,000 |
| 5 | Credit check fails | SYS-003 | Status: CREDIT_LIMIT_EXCEEDED |
| 6 | Order routed to approval | SYS-003 | Approval workflow triggered |
| 7 | Notification sent to manager | SYS-009 | SMS: "B2B order pending approval" |
| 8 | Manager reviews order | SYS-002 | Manager approves with override |
| 9 | Credit temporarily extended | SYS-003 | Temporary limit: ৳125,000 |
| 10 | Order approved | SYS-003 | Status: APPROVED |
| 11 | Stock reserved | SYS-005 | Bulk stock reservation |
| 12 | Delivery scheduled | SYS-003 | Delivery route assigned |

**Expected Results:**
- Credit check prevents automatic processing
- Approval workflow triggers correctly
- Manager can override with justification
- Audit trail captures approval decision
- Order proceeds after approval

**Error Handling:**

| Scenario | Handling |
|----------|----------|
| Manager rejects | Order CANCELLED, partner notified |
| No approval in 24h | Auto-escalation to senior management |
| Credit system down | Queue for retry, manual fallback |

---

#### Integration Test Case INT-005: B2B Order - Within Credit Limit

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-005 |
| **Priority** | High |
| **Test Type** | Synchronous |
| **Systems Involved** | SYS-002, SYS-003, SYS-005 |

**Test Objective:**
Validate B2B order auto-approval when within credit limit.

**Preconditions:**
- Partner credit limit: ৳100,000
- Current outstanding: ৳20,000
- New order: ৳30,000

**Step-by-Step Flow:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Partner submits order | Order received |
| 2 | Credit check | Total: ৳50,000 ≤ Limit: ৳100,000 |
| 3 | Auto-approval | Status: APPROVED (no manual intervention) |
| 4 | Stock reserved | Reserved successfully |
| 5 | Order confirmed | Partner receives confirmation |

**Expected Results:**
- Order auto-approved within 2 seconds
- No manual approval required
- Partner receives instant confirmation

---

#### Integration Test Case INT-006: Order Cancellation - Post Payment

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-006 |
| **Priority** | High |
| **Test Type** | Synchronous, Reverse Flow |
| **Systems Involved** | SYS-001, SYS-003, SYS-005, SYS-006 |

**Test Objective:**
Validate order cancellation flow with refund processing and inventory restoration.

**Preconditions:**
- Order status: PAID
- Payment received: ৳600
- Stock deducted: 5 units

**Step-by-Step Flow:**

| Step | Action | System | Expected Result |
|------|--------|--------|-----------------|
| 1 | Customer requests cancellation | SYS-001 | Cancellation request logged |
| 2 | System validates eligibility | SYS-003 | Within 1-hour cancellation window |
| 3 | Refund initiated | SYS-006 | Refund request: `{txn_id: "TXN123456", amount: 600}` |
| 4 | Refund confirmed | SYS-006 | Refund ID: REF789012 |
| 5 | Order status updated | SYS-003 | Status: CANCELLED |
| 6 | Stock restored | SYS-005 | Stock +5, reservation cleared |
| 7 | Invoice voided | SYS-003 | Credit note generated |
| 8 | Customer notified | SYS-009 | SMS: "Order cancelled, refund processed" |

**Rollback Procedures:**
- If refund fails, manual intervention required
- Order remains in "REFUND_PENDING" status

---

---

### 3.2 Farm-to-Consumer Traceability

#### Integration Test Case INT-007: Complete Traceability Chain

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-007 |
| **Priority** | Critical |
| **Test Type** | Synchronous + Asynchronous |
| **Systems Involved** | SYS-004 (Farm Mgmt), SYS-003 (ERP), SYS-001 (B2C), SYS-011 (Cold Chain) |

**Test Objective:**
Validate complete traceability from milk production to consumer sale.

**Preconditions:**
- Cow #BD-001 is lactating and registered
- Quality standards configured
- Packaging line operational

**Step-by-Step Flow:**

| Step | Stage | System | Data Transformation |
|------|-------|--------|---------------------|
| 1 | Milk production recorded | SYS-004 | `{cow_id: "BD-001", volume: 15.5, timestamp: "2026-01-31T06:30:00Z"}` |
| 2 | Milk collected in bulk tank | SYS-004 | Batch ID: BATCH-20260131-A |
| 3 | Quality test conducted | SYS-004 | `{fat: 4.2%, snf: 8.5%, protein: 3.4%, status: "PASS"}` |
| 4 | Cold chain monitoring starts | SYS-011 | Temp: 4°C, logged every 5 min |
| 5 | Processing scheduled | SYS-003 | Work order: PASTEURIZE-BATCH-20260131-A |
| 6 | Processing completed | SYS-003 | Output: 1000L pasteurized milk |
| 7 | Packaging initiated | SYS-003 | Batch: PKG-20260131-001, 1000 bottles |
| 8 | Individual unit tracking | SYS-003 | Each bottle gets QR code with trace ID |
| 9 | Quality final check | SYS-004 | Microbial test passed |
| 10 | Inventory updated | SYS-005 | Stock: +1000 units |
| 11 | Product available for sale | SYS-001 | B2C catalog updated |
| 12 | Customer scans QR code | SYS-001 | Traceability data retrieved |

**Data Transformations:**

```json
// Step 1: Production Record
{
  "production_id": "PROD-20260131-001",
  "cow_id": "BD-001",
  "cow_breed": "Holstein Friesian",
  "milking_time": "2026-01-31T06:30:00Z",
  "volume_liters": 15.5,
  "milker_id": "EMP-101",
  "raw_quality": {
    "temperature": 37.5,
    "appearance": "NORMAL"
  }
}

// Step 3: Quality Check
{
  "batch_id": "BATCH-20260131-A",
  "test_timestamp": "2026-01-31T07:00:00Z",
  "tester_id": "LAB-001",
  "parameters": {
    "fat_percentage": 4.2,
    "snf_percentage": 8.5,
    "protein_percentage": 3.4,
    "lactose_percentage": 4.8,
    "density": 1.032,
    "ph": 6.7,
    "antibiotic_residue": "NEGATIVE",
    "somatic_cell_count": "< 200,000/ml"
  },
  "grade": "PREMIUM",
  "status": "APPROVED"
}

// Step 8: Unit Tracking
{
  "trace_id": "TRACE-20260131-001-000001",
  "batch_id": "PKG-20260131-001",
  "production_batch": "BATCH-20260131-A",
  "production_date": "2026-01-31",
  "expiry_date": "2026-02-03",
  "processing_info": {
    "pasteurization_temp": 72,
    "pasteurization_duration": 15,
    "packaging_time": "2026-01-31T09:30:00Z"
  },
  "qr_code": "SD20260131000001"
}
```

**Expected Results:**
- Each stage creates immutable audit record
- QR code contains complete traceability chain
- Consumer can view full journey from cow to shelf
- Cold chain violations trigger alerts

**Error Handling:**

| Scenario | Handling |
|----------|----------|
| Quality test fails | Batch quarantined, investigation initiated |
| Cold chain breach | Alert to QA team, batch held for evaluation |
| Trace ID duplicate | System rejects, generates new unique ID |

---

#### Integration Test Case INT-008: Quality Failure - Batch Rejection

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-008 |
| **Priority** | High |
| **Test Type** | Synchronous, Exception Flow |
| **Systems Involved** | SYS-004, SYS-003, SYS-011 |

**Test Objective:**
Validate traceability when batch fails quality check.

**Preconditions:**
- Batch BATCH-20260131-B collected
- Quality test scheduled

**Step-by-Step Flow:**

| Step | Action | Result |
|------|--------|--------|
| 1 | Milk collected | Batch created |
| 2 | Quality test | Antibiotic residue: POSITIVE |
| 3 | Quality check | Status: REJECTED |
| 4 | Alert generated | QA team notified |
| 5 | Batch quarantined | Moved to quarantine storage |
| 6 | Investigation | Source cow identified (BD-015) |
| 7 | Disposal | Batch marked for disposal |
| 8 | Traceability | Complete rejection audit trail |

**Expected Results:**
- Batch never reaches processing
- Alert sent within 5 minutes
- Complete audit trail maintained
- Source animal identified for veterinary review

---

#### Integration Test Case INT-009: Product Recall Simulation

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-009 |
| **Priority** | Critical |
| **Test Type** | Asynchronous, Emergency Flow |
| **Systems Involved** | All systems |

**Test Objective:**
Validate product recall process using traceability data.

**Preconditions:**
- Batch PKG-20260131-001 contains 1000 bottles
- 500 bottles already sold
- Quality issue discovered post-distribution

**Step-by-Step Flow:**

| Step | Action | System | Result |
|------|--------|--------|--------|
| 1 | Recall initiated | SYS-003 | Recall ID: RECALL-001 |
| 2 | Affected units identified | SYS-003 | Trace IDs: TRACE-20260131-001-000001 to 001000 |
| 3 | Inventory segregation | SYS-005 | Remaining 500 units marked "DO NOT SELL" |
| 4 | Sold units traced | SYS-001 | Customer orders identified |
| 5 | Customer notifications | SYS-009 | Recall SMS sent to 450 customers |
| 6 | B2B partners notified | SYS-002 | Wholesale recall notice |
| 7 | Retail locations alerted | SYS-010 | Email recall notification |
| 8 | Traceability report | SYS-003 | Complete distribution map generated |
| 9 | Refund processing | SYS-006 | Automatic refunds for affected orders |
| 10 | Disposal tracking | SYS-003 | Returned units tracked for disposal |

**Expected Results:**
- All affected units identified within 2 minutes
- 95%+ customers notified within 1 hour
- Complete recall audit trail
- Financial impact calculated

---

---

### 3.3 IoT Integration Flows

#### Integration Test Case INT-010: Milk Meter Data Flow

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-010 |
| **Priority** | Critical |
| **Test Type** | Asynchronous, Real-time |
| **Systems Involved** | SYS-007 (IoT), SYS-004 (Farm), SYS-003 (ERP), SYS-012 (BI) |

**Test Objective:**
Validate end-to-end milk meter data flow from sensor to dashboard.

**Preconditions:**
- Milk meter MM-001 installed at milking station 1
- MQTT broker configured
- Dashboard configured

**Step-by-Step Flow:**

| Step | Action | Source | Data |
|------|--------|--------|------|
| 1 | Cow enters milking station | IoT Sensor | RFID: COW-BD-001 |
| 2 | Milking starts | Milk Meter | Timestamp logged |
| 3 | Volume data stream | MM-001 | `{"volume": 0.5, "flow_rate": 2.1}` every 5 sec |
| 4 | MQTT publish | SYS-007 | Topic: `smartdairy/meters/MM-001/data` |
| 5 | Message broker receives | SYS-007 | Message queued |
| 6 | Data processor consumes | SYS-007 | Parse and validate |
| 7 | Farm DB updated | SYS-004 | Production record created |
| 8 | ERP inventory updated | SYS-003 | Raw milk stock incremented |
| 9 | Dashboard updated | SYS-012 | Real-time production chart refreshed |
| 10 | Alert check | SYS-007 | Volume within expected range |

**Data Transformations:**

```json
// Raw MQTT Message
{
  "device_id": "MM-001",
  "timestamp": "2026-01-31T06:30:15Z",
  "cow_rfid": "COW-BD-001",
  "session_id": "MILK-20260131-001",
  "measurements": {
    "volume_liters": 15.5,
    "flow_rate_lpm": 2.3,
    "temperature_c": 37.2,
    "conductivity_ms": 4.5
  },
  "duration_seconds": 405
}

// Processed Record
{
  "production_id": "PROD-20260131-001",
  "cow_id": "BD-001",
  "milking_session": "MORNING",
  "volume_liters": 15.5,
  "quality_indicators": {
    "conductivity": 4.5,
    "temperature": 37.2
  },
  "flagged_for_review": false
}
```

**Expected Results:**
- Data received within 1 second of MQTT publish
- All systems updated within 5 seconds
- Dashboard reflects real-time production

**Error Handling:**

| Scenario | Handling |
|----------|----------|
| Meter offline | Alert to farm manager, manual entry mode |
| Duplicate reading | Deduplication based on timestamp |
| Abnormal reading | Flag for review, notify supervisor |

---

#### Integration Test Case INT-011: Environmental Sensor Alert

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-011 |
| **Priority** | High |
| **Test Type** | Asynchronous, Event-driven |
| **Systems Involved** | SYS-007, SYS-004, SYS-009 |

**Test Objective:**
Validate alert generation for environmental threshold breaches.

**Preconditions:**
- Temperature sensor TS-001 in barn 1
- Threshold: Max 28°C
- Current: 25°C

**Step-by-Step Flow:**

| Step | Action | Data | Result |
|------|--------|------|--------|
| 1 | Temperature rises | 29°C | Threshold exceeded |
| 2 | Sensor publishes alert | `{"alert": "HIGH_TEMP", "value": 29}` | MQTT message sent |
| 3 | Alert processor triggers | - | Rule engine activated |
| 4 | Alert notification | - | SMS sent to farm manager |
| 5 | Dashboard alert | - | Red alert on monitoring screen |
| 6 | Escalation (if >30°C) | 31°C | Senior management notified |
| 7 | Temperature normalizes | 26°C | Alert cleared |
| 8 | Resolution logged | - | Audit trail updated |

**Expected Results:**
- Alert generated within 30 seconds of threshold breach
- SMS delivered within 1 minute
- Escalation occurs for critical thresholds

---

#### Integration Test Case INT-012: Cold Chain Temperature Breach

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-012 |
| **Priority** | Critical |
| **Test Type** | Asynchronous, Event-driven |
| **Systems Involved** | SYS-007, SYS-011, SYS-003, SYS-009 |

**Test Objective:**
Validate cold chain monitoring and alert system.

**Preconditions:**
- Cold storage CS-001: Milk storage
- Temperature range: 2-4°C
- Current inventory: 500L milk

**Step-by-Step Flow:**

| Step | Action | Data | Result |
|------|--------|------|--------|
| 1 | Temperature sensor reading | 6.5°C | Above threshold |
| 2 | Alert generated | SYS-011 | HIGH_TEMP alert |
| 3 | Inventory flagged | SYS-003 | Batch marked "UNDER REVIEW" |
| 4 | Immediate notifications | SYS-009 | SMS to warehouse manager, QA lead |
| 5 | Continuous monitoring | SYS-007 | Every 30 seconds |
| 6 | Temperature continues rising | 8°C | Escalation to operations manager |
| 7 | Temperature restored | 3.5°C | Alert cleared |
| 8 | Batch evaluation | QA team | Microbial test scheduled |
| 9 | Decision recorded | SYS-003 | PASS or DISCARD |

**Expected Results:**
- Alert within 30 seconds
- Automatic inventory hold
- Complete temperature excursion log
- Batch disposition tracked

---

#### Integration Test Case INT-013: IoT Device Offline Handling

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-013 |
| **Priority** | High |
| **Test Type** | Asynchronous, Failure |
| **Systems Involved** | SYS-007, SYS-004, SYS-009 |

**Test Objective:**
Validate system behavior when IoT device goes offline.

**Preconditions:**
- Device MM-001 connected and transmitting
- Last heartbeat: < 5 minutes ago

**Step-by-Step Flow:**

| Step | Action | Time | Result |
|------|--------|------|--------|
| 1 | Device stops transmitting | T+0 | Last message received |
| 2 | No heartbeat | T+5min | Yellow warning on dashboard |
| 3 | No heartbeat | T+15min | Orange alert |
| 4 | No heartbeat | T+30min | Red alert, SMS to technician |
| 5 | Device comes online | T+45min | Heartbeat received |
| 6 | Status updated | - | All alerts cleared |
| 7 | Data sync | - | Any queued data processed |

**Expected Results:**
- Progressive alert escalation
- Manual data entry mode activated
- No data loss when device reconnects

---

---

### 3.4 Payment Integration Flows

#### Integration Test Case INT-014: bKash Payment - Success Flow

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-014 |
| **Priority** | Critical |
| **Test Type** | Synchronous + Asynchronous |
| **Systems Involved** | SYS-001, SYS-006 (bKash), SYS-003 |

**Test Objective:**
Validate complete bKash payment gateway integration.

**Preconditions:**
- Customer has bKash account
- Order total: ৳1,200
- bKash sandbox configured

**Step-by-Step Flow:**

| Step | Action | System | Data |
|------|--------|--------|------|
| 1 | Customer selects bKash | SYS-001 | Payment method: BKASH |
| 2 | Payment request | SYS-001 → SYS-006 | `{amount: 1200, invoice: "INV001"}` |
| 3 | bKash checkout URL | SYS-006 | Checkout URL returned |
| 4 | Customer authenticates | bKash App | PIN entered |
| 5 | Payment confirmation | bKash | OTP verified |
| 6 | Payment success | SYS-006 | Transaction completed |
| 7 | Callback to ERP | SYS-006 → SYS-003 | Webhook: `{status: "success", txn_id: "BK123456"}` |
| 8 | Payment verified | SYS-003 | Signature validation |
| 9 | Order confirmed | SYS-003 | Status: PAID |
| 10 | Receipt generated | SYS-003 | Digital receipt created |

**Expected Results:**
- Checkout loads within 3 seconds
- Callback received within 10 seconds
- Order status updated within 15 seconds
- Receipt available immediately

**Error Handling:**

| Error | Handling |
|-------|----------|
| Insufficient balance | Payment declined, customer notified |
| Wrong PIN | 3 attempts allowed, then blocked |
| Timeout | Order held for 30 min, retry allowed |
| Callback failure | Retry every 5 min for 1 hour |

---

#### Integration Test Case INT-015: Nagad Payment - Success Flow

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-015 |
| **Priority** | High |
| **Test Type** | Synchronous + Asynchronous |
| **Systems Involved** | SYS-001, SYS-006 (Nagad), SYS-003 |

**Test Objective:**
Validate Nagad payment gateway integration.

**Preconditions:**
- Customer has Nagad account
- Order total: ৳850

**Step-by-Step Flow:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Select Nagad payment | Method selected |
| 2 | Request payment | Order ID generated |
| 3 | Redirect to Nagad | URL returned |
| 4 | Complete payment | Success confirmation |
| 5 | IPN received | Instant notification |
| 6 | Verify signature | Authenticated |
| 7 | Update order | PAID status |
| 8 | Send confirmation | Customer notified |

---

#### Integration Test Case INT-016: Payment Gateway Timeout

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-016 |
| **Priority** | High |
| **Test Type** | Asynchronous, Timeout |
| **Systems Involved** | SYS-001, SYS-006, SYS-003, SYS-005 |

**Test Objective:**
Validate handling of payment gateway timeout.

**Preconditions:**
- Order pending payment
- Stock reserved

**Step-by-Step Flow:**

| Step | Action | Time | Result |
|------|--------|------|--------|
| 1 | Payment initiated | T+0 | Order status: PENDING_PAYMENT |
| 2 | Gateway timeout | T+2min | No response received |
| 3 | Retry attempt | T+2min | Automatic retry |
| 4 | Second timeout | T+4min | Retry failed |
| 5 | Order on hold | T+5min | Status: PAYMENT_INVESTIGATION |
| 6 | Stock reserved | - | Extended reservation |
| 7 | Manual check | T+30min | Admin verifies payment status |
| 8 | Resolution | - | Confirm or cancel |

**Expected Results:**
- Automatic retry mechanism
- Order not auto-cancelled on timeout
- Manual review process triggered

---

#### Integration Test Case INT-017: Duplicate Payment Prevention

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-017 |
| **Priority** | Critical |
| **Test Type** | Synchronous, Idempotency |
| **Systems Involved** | SYS-001, SYS-006, SYS-003 |

**Test Objective:**
Validate prevention of duplicate payment processing.

**Preconditions:**
- Customer double-clicks pay button
- Same order, rapid requests

**Step-by-Step Flow:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | First payment request | Idempotency key: IDEM-001 |
| 2 | Second request (same key) | Rejected: DUPLICATE_REQUEST |
| 3 | First payment completes | Success |
| 4 | Second callback arrives | Ignored (already processed) |
| 5 | Customer charged once | Single transaction |

**Expected Results:**
- Idempotency key prevents duplicates
- Customer charged exactly once
- Single order confirmation

---

#### Integration Test Case INT-018: Partial Refund Flow

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-018 |
| **Priority** | High |
| **Test Type** | Synchronous, Reverse Transaction |
| **Systems Involved** | SYS-003, SYS-006, SYS-001 |

**Test Objective:**
Validate partial refund processing.

**Preconditions:**
- Original order: ৳2,000 (5 items)
- Customer returns 2 items (৳800 value)

**Step-by-Step Flow:**

| Step | Action | Data | Result |
|------|--------|------|--------|
| 1 | Return request | SYS-001 | 2 items marked for return |
| 2 | Return approved | SYS-003 | Approval workflow completed |
| 3 | Refund initiated | SYS-006 | `{amount: 800, original_txn: "TXN123456"}` |
| 4 | Gateway processes | SYS-006 | Refund ID generated |
| 5 | Refund confirmed | SYS-006 | Customer account credited |
| 6 | Order updated | SYS-003 | Partial refund logged |
| 7 | Inventory updated | SYS-005 | Returned items back to stock |
| 8 | Notification | SYS-009 | SMS: "Refund of ৳800 processed" |

---

#### Integration Test Case INT-019: Cash on Delivery Order Flow

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-019 |
| **Priority** | High |
| **Test Type** | Synchronous + Asynchronous |
| **Systems Involved** | SYS-001, SYS-003, SYS-005 |

**Test Objective:**
Validate COD order flow without online payment.

**Preconditions:**
- Customer selects COD option
- Delivery address within service area
- Maximum COD limit: ৳5,000

**Step-by-Step Flow:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Select COD | Method accepted |
| 2 | Validate amount | ৳2,000 < ৳5,000 limit |
| 3 | Address verification | Delivery area confirmed |
| 4 | Order created | Status: COD_PENDING |
| 5 | Stock reserved | Reserved for delivery |
| 6 | Delivery assigned | Route optimized |
| 7 | Delivery completed | Status: DELIVERED |
| 8 | Payment collected | Cash received |
| 9 | Order closed | Status: COMPLETED |

---

---

### 3.5 Mobile-ERP Sync Flows

#### Integration Test Case INT-020: Offline Data Entry - Sync on Connect

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-020 |
| **Priority** | Critical |
| **Test Type** | Asynchronous, Sync |
| **Systems Involved** | SYS-008 (Mobile), SYS-004 (Farm), SYS-003 (ERP) |

**Test Objective:**
Validate offline data entry and synchronization when connectivity is restored.

**Preconditions:**
- Field worker logged into mobile app
- Network connectivity: OFFLINE
- Cow BD-050 needs health check record

**Step-by-Step Flow:**

| Step | Action | System | Data |
|------|--------|--------|------|
| 1 | App detects offline | SYS-008 | Offline mode activated |
| 2 | Worker creates record | SYS-008 | Health check: `{cow_id: "BD-050", temp: 38.5, condition: "HEALTHY"}` |
| 3 | Data queued locally | SYS-008 | SQLite queue updated |
| 4 | Multiple records added | SYS-008 | 5 records queued |
| 5 | Network restored | SYS-008 | Connectivity detected |
| 6 | Sync initiated | SYS-008 | Upload starts |
| 7 | Records sent to ERP | SYS-008 → SYS-004 | Batch upload |
| 8 | Server validates | SYS-004 | All records valid |
| 9 | Acknowledgment | SYS-004 → SYS-008 | Sync confirmation |
| 10 | Local queue cleared | SYS-008 | Records removed from queue |
| 11 | Dashboard updated | SYS-003 | Records visible in ERP |

**Data Transformations:**

```json
// Local Queue Entry
{
  "local_id": "LOCAL-001",
  "timestamp_created": "2026-01-31T10:00:00Z",
  "sync_status": "PENDING",
  "attempts": 0,
  "data": {
    "record_type": "health_check",
    "cow_id": "BD-050",
    "temperature": 38.5,
    "symptoms": [],
    "treatment": null,
    "veterinarian_id": "VET-001"
  }
}

// Sync Batch Request
{
  "device_id": "MOBILE-FARM-001",
  "sync_timestamp": "2026-01-31T11:30:00Z",
  "records": [
    {
      "local_id": "LOCAL-001",
      "data": { ... }
    },
    {
      "local_id": "LOCAL-002", 
      "data": { ... }
    }
  ]
}
```

**Expected Results:**
- Data persists during offline period
- Automatic sync when online
- No data loss
- Conflict resolution if needed

---

#### Integration Test Case INT-021: Sync Conflict Resolution

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-021 |
| **Priority** | High |
| **Test Type** | Asynchronous, Conflict |
| **Systems Involved** | SYS-008, SYS-004 |

**Test Objective:**
Validate conflict resolution when same record modified on mobile and ERP.

**Preconditions:**
- Cow BD-025 weight recorded offline on mobile: 450kg
- Same cow weight updated in ERP: 455kg
- Mobile comes online to sync

**Step-by-Step Flow:**

| Step | Action | Timestamp | Data |
|------|--------|-----------|------|
| 1 | Mobile records weight | T+0 | 450kg, timestamp: 10:00 |
| 2 | ERP updates weight | T+5min | 455kg, timestamp: 10:05 |
| 3 | Mobile comes online | T+30min | Sync initiated |
| 4 | Conflict detected | - | Same cow, different values |
| 5 | Resolution strategy | - | Timestamp-based: later wins |
| 6 | ERP value kept | - | 455kg retained |
| 7 | Mobile notification | - | "Weight updated by another user" |
| 8 | Audit log | - | Conflict and resolution logged |

**Expected Results:**
- Conflict automatically detected
- Resolution strategy applied
- Audit trail maintained
- User notified of conflict

**Rollback Procedures:**
- Manual override available for supervisor
- Previous values archived

---

#### Integration Test Case INT-022: Large Dataset Sync

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-022 |
| **Priority** | High |
| **Test Type** | Asynchronous, Performance |
| **Systems Involved** | SYS-008, SYS-004 |

**Test Objective:**
Validate sync performance with large dataset.

**Preconditions:**
- Field worker offline for 3 days
- 500 records queued for sync
- Mixed network quality

**Step-by-Step Flow:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Network restored | Sync auto-initiated |
| 2 | Batch upload (100 records) | First batch sent |
| 3 | Progress indicator | Shows 20% complete |
| 4 | Continue batches | Records sent in chunks |
| 5 | Network interruption | Sync paused |
| 6 | Network restored | Sync resumes from last batch |
| 7 | All records uploaded | 100% complete |
| 8 | Verification | Server confirms all records |

**Expected Results:**
- Chunked upload for large datasets
- Progress tracking
- Resume capability after interruption
- Complete within 10 minutes

---

#### Integration Test Case INT-023: Mobile Milk Collection Entry

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-023 |
| **Priority** | Critical |
| **Test Type** | Synchronous |
| **Systems Involved** | SYS-008, SYS-004, SYS-003 |

**Test Objective:**
Validate mobile app milk collection entry and immediate sync.

**Preconditions:**
- Farmer "Abdul Rahman" registered
- Quality testing device connected via Bluetooth
- Network: Available

**Step-by-Step Flow:**

| Step | Action | System | Data |
|------|--------|--------|------|
| 1 | Select farmer | SYS-008 | Farmer ID: FARM-101 |
| 2 | Scan/enter cow ID | SYS-008 | Cow: BD-030 |
| 3 | Connect quality meter | SYS-008 | Bluetooth paired |
| 4 | Measure quality | Device | Fat: 4.1%, SNF: 8.4% |
| 5 | Data auto-populated | SYS-008 | Quality fields filled |
| 6 | Enter volume | SYS-008 | 12.5 liters |
| 7 | Record temperature | SYS-008 | 36.8°C |
| 8 | Submit record | SYS-008 | POST to ERP |
| 9 | Server processes | SYS-004 | Record validated |
| 10 | Payment calculated | SYS-003 | Rate: ৳45/L = ৳562.50 |
| 11 | Confirmation | SYS-008 | Receipt number displayed |
| 12 | Print receipt | SYS-008 | Bluetooth printer |

**Expected Results:**
- Complete entry within 2 minutes
- Payment calculated instantly
- Receipt printed
- Farmer account updated

---

---

### 3.6 Inventory-Order Integration

#### Integration Test Case INT-024: Stock Reservation Flow

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-024 |
| **Priority** | Critical |
| **Test Type** | Synchronous |
| **Systems Involved** | SYS-001, SYS-005, SYS-003 |

**Test Objective:**
Validate stock reservation during order placement.

**Preconditions:**
- Product: Saffron Organic Milk
- Available stock: 200 units
- Reserved: 0

**Step-by-Step Flow:**

| Step | Action | System | Query/Update |
|------|--------|--------|--------------|
| 1 | Customer adds 10 to cart | SYS-001 | Cart updated |
| 2 | Checkout initiated | SYS-001 | Reserve request |
| 3 | Stock check | SYS-005 | `SELECT available FROM stock WHERE sku='MILK001'` → 200 |
| 4 | Reservation created | SYS-005 | `UPDATE stock SET reserved=10 WHERE sku='MILK001'` |
| 5 | Reservation timer | SYS-005 | Expires in 30 min |
| 6 | Payment completed | SYS-003 | Order confirmed |
| 7 | Deduct stock | SYS-005 | `UPDATE stock SET qty=190, reserved=0` |
| 8 | If timeout | - | Reserved released |

**Expected Results:**
- Reservation prevents overselling
- Timer ensures stock availability
- Atomic stock operations

---

#### Integration Test Case INT-025: Multi-Location Stock Allocation

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-025 |
| **Priority** | High |
| **Test Type** | Synchronous |
| **Systems Involved** | SYS-001, SYS-005, SYS-003 |

**Test Objective:**
Validate stock allocation across multiple warehouse locations.

**Preconditions:**
- Product: Saffron Yogurt
- Warehouse A (Dhaka): 50 units
- Warehouse B (Narayanganj): 30 units
- Customer location: Dhaka

**Step-by-Step Flow:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Customer orders 20 units | Order received |
| 2 | Location check | Customer in Dhaka zone |
| 3 | Stock allocation | Warehouse A selected (closest) |
| 4 | Reservation | 20 units reserved at Warehouse A |
| 5 | Delivery assignment | Route from Warehouse A |
| 6 | Picking list | Generated for Warehouse A |

**Expected Results:**
- Closest warehouse allocated
- Delivery time minimized
- Stock balanced across locations

---

#### Integration Test Case INT-026: Batch/Lot Tracking

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-026 |
| **Priority** | Critical |
| **Test Type** | Synchronous |
| **Systems Involved** | SYS-005, SYS-003, SYS-001 |

**Test Objective:**
Validate FIFO/FEFO batch allocation for perishable products.

**Preconditions:**
- Product: Fresh Milk (3-day shelf life)
- Batch A (Jan 31): 100 units, Expires Feb 3
- Batch B (Jan 30): 50 units, Expires Feb 2
- Customer orders 60 units

**Step-by-Step Flow:**

| Step | Action | Logic | Result |
|------|--------|-------|--------|
| 1 | Order received | - | 60 units requested |
| 2 | FEFO selection | Earliest expiry first | Batch B selected |
| 3 | Batch B allocation | 50 available | 50 allocated from B |
| 4 | Remaining needed | 10 more | Batch A selected |
| 5 | Batch A allocation | 100 available | 10 allocated from A |
| 6 | Order picked | - | 50 (B) + 10 (A) |
| 7 | Stock update | - | Batch B: 0, Batch A: 90 |
| 8 | Traceability | - | Order linked to batches |

**Expected Results:**
- Oldest batch dispatched first (FEFO)
- Expiry dates tracked
- Minimized waste

---

#### Integration Test Case INT-027: Inventory Reorder Automation

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-027 |
| **Priority** | High |
| **Test Type** | Asynchronous, Scheduled |
| **Systems Involved** | SYS-005, SYS-003, SYS-009 |

**Test Objective:**
Validate automatic purchase order generation when stock falls below reorder point.

**Preconditions:**
- Product: Packaging Bottles
- Current stock: 500 units
- Reorder point: 1,000 units
- Reorder quantity: 5,000 units
- Supplier: PackTech Ltd configured

**Step-by-Step Flow:**

| Step | Action | System | Result |
|------|--------|--------|--------|
| 1 | Inventory transaction | SYS-005 | 600 bottles used in production |
| 2 | Stock level update | SYS-005 | New stock: -100 (below zero not allowed, actually 500-600=-100 error) |
| 2 (corrected) | Stock consumption | SYS-005 | Stock: 500 → 400 |
| 3 | Reorder point check | SYS-005 | 400 < 1,000, trigger activated |
| 4 | Auto-PO generation | SYS-003 | Draft PO created |
| 5 | Notification | SYS-009 | SMS to purchase manager |
| 6 | Manager approval | SYS-003 | PO approved |
| 7 | PO sent to supplier | SYS-003 | Email sent |
| 8 | Expected delivery | SYS-003 | Stock receipt scheduled |

**Expected Results:**
- Reorder triggered automatically
- Manager approval required
- Complete audit trail

---

#### Integration Test Case INT-028: Negative Stock Prevention

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-028 |
| **Priority** | Critical |
| **Test Type** | Synchronous, Negative |
| **Systems Involved** | SYS-001, SYS-005 |

**Test Objective:**
Validate system prevents negative stock.

**Preconditions:**
- Product stock: 10 units
- Two simultaneous orders for 8 units each

**Step-by-Step Flow:**

| Step | Action | Time | Result |
|------|--------|------|--------|
| 1 | Order 1 checks stock | T+0ms | 10 available |
| 2 | Order 2 checks stock | T+1ms | 10 available |
| 3 | Order 1 reserves 8 | T+2ms | Reserved: 8, Available: 2 |
| 4 | Order 2 requests 8 | T+3ms | Only 2 available |
| 5 | Order 2 rejected | T+4ms | Insufficient stock error |
| 6 | Order 1 completes | T+5ms | Stock: 2 |

**Expected Results:**
- No negative stock allowed
- Concurrent access handled safely
- Database transaction isolation

---

---

### 3.7 Subscription Management Flow

#### Integration Test Case INT-029: New Subscription Creation

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-029 |
| **Priority** | Critical |
| **Test Type** | Synchronous + Asynchronous |
| **Systems Involved** | SYS-001, SYS-003, SYS-006, SYS-005 |

**Test Objective:**
Validate new subscription creation and first order generation.

**Preconditions:**
- Customer: "Rahim Khan"
- Product: Daily Milk Subscription
- Plan: 1 liter/day, 30 days
- Price: ৳3,600 (৳120/day)
- Payment method: bKash Auto-debit

**Step-by-Step Flow:**

| Step | Action | System | Data |
|------|--------|--------|------|
| 1 | Customer selects subscription | SYS-001 | Plan: DAILY-1L-30D |
| 2 | Delivery address confirmed | SYS-001 | Address validated |
| 3 | Payment method setup | SYS-006 | bKash auto-debit authorized |
| 4 | Subscription created | SYS-003 | `{sub_id: "SUB-001", status: "ACTIVE"}` |
| 5 | First payment charged | SYS-006 | ৳3,600 charged |
| 6 | First order generated | SYS-003 | Order for tomorrow's delivery |
| 7 | Delivery scheduled | SYS-003 | Route assigned |
| 8 | Confirmation | SYS-009 | SMS: "Subscription activated" |
| 9 | Calendar event | SYS-001 | Delivery schedule displayed |

**Expected Results:**
- Subscription active immediately
- First payment processed
- First delivery scheduled for next day
- Calendar shows all delivery dates

---

#### Integration Test Case INT-030: Recurring Order Generation

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-030 |
| **Priority** | Critical |
| **Test Type** | Asynchronous, Scheduled |
| **Systems Involved** | SYS-003, SYS-005, SYS-006, SYS-009 |

**Test Objective:**
Validate daily automatic order generation for active subscriptions.

**Preconditions:**
- Subscription SUB-001: Active
- Schedule: Daily at 6 AM delivery
- Next delivery: Tomorrow

**Step-by-Step Flow:**

| Step | Action | Time | System |
|------|--------|------|--------|
| 1 | Cron job triggers | Day-1, 8:00 PM | SYS-003 |
| 2 | Find tomorrow's deliveries | - | Query active subscriptions |
| 3 | Generate orders | - | Create delivery orders |
| 4 | Reserve stock | - | SYS-005 |
| 5 | Payment check | - | Verify auto-debit authorized |
| 6 | Route optimization | - | Group deliveries by area |
| 7 | Delivery list | - | Generate for drivers |
| 8 | Customer reminder | Day, 5:00 AM | SMS: "Your milk is on the way" |
| 9 | Delivery completed | Day, 6:00 AM | Status updated |

**Expected Results:**
- Orders generated daily at 8 PM
- Stock reserved automatically
- Drivers receive optimized routes
- Customers get delivery reminders

---

#### Integration Test Case INT-031: Subscription Pause and Resume

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-031 |
| **Priority** | High |
| **Test Type** | Synchronous |
| **Systems Involved** | SYS-001, SYS-003 |

**Test Objective:**
Validate subscription pause and resume functionality.

**Preconditions:**
- Active subscription: SUB-001
- Customer going on vacation: Feb 5-10

**Step-by-Step Flow:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Customer requests pause | Pause form submitted |
| 2 | Select dates | Feb 5-10 selected |
| 3 | System validates | Pause allowed |
| 4 | Subscription updated | Status: PAUSED (Feb 5-10) |
| 5 | Pending orders cancelled | Feb 5-10 orders removed |
| 6 | Extension calculated | End date extended by 6 days |
| 7 | Confirmation | SMS: "Subscription paused" |
| 8 | Auto-resume | Feb 11: Status back to ACTIVE |
| 9 | Orders resume | Feb 11 delivery scheduled |

**Expected Results:**
- Pause takes effect on selected dates
- No deliveries during pause
- Subscription extended appropriately
- Automatic resume

---

#### Integration Test Case INT-032: Subscription Modification

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-032 |
| **Priority** | High |
| **Test Type** | Synchronous |
| **Systems Involved** | SYS-001, SYS-003, SYS-006 |

**Test Objective:**
Validate subscription plan modification.

**Preconditions:**
- Current plan: 1 liter/day
- Customer wants to change to: 2 liters/day
- Mid-subscription (15 days remaining)

**Step-by-Step Flow:**

| Step | Action | Calculation | Result |
|------|--------|-------------|--------|
| 1 | Change request | 1L → 2L | Submitted |
| 2 | Prorated calculation | 15 days × ৳120 = ৳1,800 additional | Calculated |
| 3 | Payment charged | Extra ৳1,800 | Charged to same method |
| 4 | Plan updated | Effective tomorrow | New rate active |
| 5 | Future orders | All updated | 2L quantities |
| 6 | Confirmation | - | SMS sent |

**Expected Results:**
- Prorated charge calculated correctly
- Change effective next day
- All future orders updated
- Payment processed

---

#### Integration Test Case INT-033: Subscription Renewal

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-033 |
| **Priority** | Critical |
| **Test Type** | Asynchronous |
| **Systems Involved** | SYS-003, SYS-006, SYS-009 |

**Test Objective:**
Validate automatic subscription renewal.

**Preconditions:**
- Subscription expires in 3 days
- Auto-renewal: Enabled
- Payment method: Valid

**Step-by-Step Flow:**

| Step | Action | Time | Result |
|------|--------|------|--------|
| 1 | Renewal reminder | 3 days before | SMS: "Your subscription expires soon" |
| 2 | Pre-renewal check | 1 day before | Validate payment method |
| 3 | Renewal attempt | Expiry day | Charge ৳3,600 |
| 4 | Payment success | - | New cycle activated |
| 5 | Subscription extended | - | +30 days from expiry |
| 6 | Confirmation | - | SMS: "Subscription renewed" |
| 7 | Continue deliveries | - | No interruption |

**Error Handling:**

| Scenario | Handling |
|----------|----------|
| Payment fails | Retry for 3 days, then suspend |
| Invalid payment method | Email to update payment info |
| Customer cancels | Stop at expiry, no renewal |

---

---

## Additional Integration Test Cases

#### Integration Test Case INT-034: User Authentication - SSO Flow

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-034 |
| **Priority** | High |
| **Test Type** | Synchronous |
| **Systems Involved** | SYS-001, SYS-002, SYS-003 |

**Test Objective:**
Validate single sign-on across B2C and B2B portals.

**Preconditions:**
- User has account with roles: Customer + Partner
- SSO provider configured

**Step-by-Step Flow:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login to B2C | Success, JWT token issued |
| 2 | Access B2B | Token validated, auto-login |
| 3 | Access ERP portal | Token validated, appropriate access |
| 4 | Logout from B2C | All sessions terminated |
| 5 | Try B2B again | Redirect to login |

---

#### Integration Test Case INT-035: Notification Multi-Channel Delivery

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-035 |
| **Priority** | High |
| **Test Type** | Asynchronous |
| **Systems Involved** | SYS-003, SYS-009, SYS-010 |

**Test Objective:**
Validate notification delivery across multiple channels.

**Preconditions:**
- Order confirmed
- Customer preferences: SMS + Email

**Step-by-Step Flow:**

| Step | Channel | Content | Status |
|------|---------|---------|--------|
| 1 | SMS | Order confirmation | Delivered |
| 2 | Email | Detailed invoice | Delivered |
| 3 | Push (if app) | Notification | Delivered |
| 4 | In-app | Message center | Updated |

**Expected Results:**
- All channels notified
- Delivery confirmation tracked
- Failed channels retry

---

#### Integration Test Case INT-036: Report Generation - Multi-Module

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-036 |
| **Priority** | Medium |
| **Test Type** | Asynchronous |
| **Systems Involved** | SYS-003, SYS-004, SYS-005, SYS-012 |

**Test Objective:**
Validate report generation aggregating data from multiple modules.

**Preconditions:**
- Monthly operations report requested
- Data from Farm, Inventory, Sales required

**Step-by-Step Flow:**

| Step | Data Source | Query |
|------|-------------|-------|
| 1 | Farm data | Production volume, quality metrics |
| 2 | Inventory | Stock levels, wastage |
| 3 | Sales | Revenue, orders, customers |
| 4 | Aggregation | Combine into report |
| 5 | Format | Generate PDF |
| 6 | Delivery | Email to management |

---

#### Integration Test Case INT-037: Data Import - Bulk Product Upload

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-037 |
| **Priority** | Medium |
| **Test Type** | Asynchronous |
| **Systems Involved** | SYS-003, SYS-001, SYS-005 |

**Test Objective:**
Validate bulk product import and catalog update.

**Preconditions:**
- CSV file: 100 new products
- Template validated

**Step-by-Step Flow:**

| Step | Action | Validation |
|------|--------|------------|
| 1 | Upload CSV | File format check |
| 2 | Validate rows | Required fields present |
| 3 | Check duplicates | SKU uniqueness |
| 4 | Import products | ERP master data |
| 5 | Create inventory | Initial stock = 0 |
| 6 | Update catalog | B2C/B2B visible |
| 7 | Report | Success/failure summary |

---

#### Integration Test Case INT-038: Manufacturing Work Order Flow

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-038 |
| **Priority** | High |
| **Test Type** | Synchronous + Asynchronous |
| **Systems Involved** | SYS-003 (MRP), SYS-005, SYS-004 |

**Test Objective:**
Validate manufacturing work order from creation to completion.

**Preconditions:**
- Product: Sweet Yogurt
- BOM configured
- Raw materials available

**Step-by-Step Flow:**

| Step | Action | System |
|------|--------|--------|
| 1 | Production plan | Create work order |
| 2 | Raw material check | Reserve ingredients |
| 3 | Batch creation | Assign batch number |
| 4 | Production start | Update status |
| 5 | Quality checks | In-process testing |
| 6 | Production complete | Yield recorded |
| 7 | Finished goods | Stock updated |
| 8 | Cost calculation | Production cost logged |

---

#### Integration Test Case INT-039: Delivery Route Optimization

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-039 |
| **Priority** | Medium |
| **Test Type** | Asynchronous |
| **Systems Involved** | SYS-003, SYS-001, Mobile |

**Test Objective:**
Validate delivery route optimization for multiple orders.

**Preconditions:**
- 50 orders for delivery today
- Multiple delivery zones
- Vehicle capacity: 200 orders

**Step-by-Step Flow:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Collect addresses | Geocoded |
| 2 | Cluster by zone | 5 zones identified |
| 3 | Optimize routes | Shortest path per zone |
| 4 | Assign vehicles | 3 vehicles assigned |
| 5 | Driver app update | Routes pushed |
| 6 | Real-time tracking | GPS tracking enabled |

---

#### Integration Test Case INT-040: Audit Trail Logging

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-040 |
| **Priority** | High |
| **Test Type** | Synchronous |
| **Systems Involved** | All Systems |

**Test Objective:**
Validate comprehensive audit trail across all transactions.

**Preconditions:**
- Any transaction initiated

**Step-by-Step Flow:**

| Step | Action | Log Entry |
|------|--------|-----------|
| 1 | User action | Who, what, when |
| 2 | Data change | Before/after values |
| 3 | System processing | Internal steps |
| 4 | External call | API requests/responses |
| 5 | Completion | Final status |

**Expected Results:**
- Immutable audit log
- Tamper-proof records
- Queryable by transaction ID

---

#### Integration Test Case INT-041: API Rate Limiting

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-041 |
| **Priority** | Medium |
| **Test Type** | Synchronous |
| **Systems Involved** | SYS-001, SYS-003 |

**Test Objective:**
Validate API rate limiting protection.

**Preconditions:**
- Rate limit: 100 requests/minute

**Step-by-Step Flow:**

| Step | Requests | Result |
|------|----------|--------|
| 1 | 100 requests | All successful |
| 2 | 101st request | 429 Too Many Requests |
| 3 | Wait 1 minute | Limit reset |
| 4 | New request | Success |

---

#### Integration Test Case INT-042: Data Encryption in Transit

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-042 |
| **Priority** | Critical |
| **Test Type** | Synchronous |
| **Systems Involved** | All External Systems |

**Test Objective:**
Validate TLS encryption for all data transmissions.

**Step-by-Step Flow:**

| Step | Check | Expected |
|------|-------|----------|
| 1 | HTTPS only | No HTTP allowed |
| 2 | TLS version | 1.2 or higher |
| 3 | Certificate valid | Not expired |
| 4 | Cipher strength | Strong ciphers only |

---

#### Integration Test Case INT-043: Database Replication Lag Handling

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-043 |
| **Priority** | High |
| **Test Type** | Asynchronous |
| **Systems Involved** | SYS-003, Database |

**Test Objective:**
Validate handling of database replication lag.

**Step-by-Step Flow:**

| Step | Action | Handling |
|------|--------|----------|
| 1 | Write to primary | Success |
| 2 | Immediate read from replica | May not see write |
| 3 | System handles | Retry or read from primary |
| 4 | Eventually consistent | Replica catches up |

---

#### Integration Test Case INT-044: Webhook Retry Mechanism

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-044 |
| **Priority** | High |
| **Test Type** | Asynchronous |
| **Systems Involved** | SYS-003, External Systems |

**Test Objective:**
Validate webhook retry mechanism for failed deliveries.

**Step-by-Step Flow:**

| Step | Attempt | Result |
|------|---------|--------|
| 1 | Initial webhook | Receiver offline |
| 2 | Retry 1 (5 min) | Still offline |
| 3 | Retry 2 (15 min) | Still offline |
| 4 | Retry 3 (30 min) | Success |
| 5 | Exponential backoff | Works correctly |

---

#### Integration Test Case INT-045: Multi-Currency Handling

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-045 |
| **Priority** | Medium |
| **Test Type** | Synchronous |
| **Systems Involved** | SYS-002, SYS-003, SYS-006 |

**Test Objective:**
Validate multi-currency pricing and payment.

**Preconditions:**
- B2B partner from USA
- Order in USD
- Payment in USD

**Step-by-Step Flow:**

| Step | Action | Conversion |
|------|--------|------------|
| 1 | Display USD price | Rate: 1 USD = 110 BDT |
| 2 | Invoice in USD | $500 |
| 3 | Payment gateway | USD transaction |
| 4 | Accounting | USD recorded |
| 5 | Reporting | Convert to BDT if needed |

---

#### Integration Test Case INT-046: Supplier Integration - Purchase Order

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-046 |
| **Priority** | High |
| **Test Type** | Synchronous + Asynchronous |
| **Systems Involved** | SYS-003, External Supplier Portal |

**Test Objective:**
Validate purchase order transmission to supplier system.

**Step-by-Step Flow:**

| Step | Action | Method |
|------|--------|--------|
| 1 | PO created in ERP | Internal |
| 2 | EDI/API send to supplier | REST API |
| 3 | Supplier acknowledges | Response |
| 4 | Confirmation received | Status updated |
| 5 | Delivery scheduled | Expected date recorded |

---

#### Integration Test Case INT-047: Customer Feedback Flow

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-047 |
| **Priority** | Medium |
| **Test Type** | Asynchronous |
| **Systems Involved** | SYS-001, SYS-003, SYS-009 |

**Test Objective:**
Validate customer feedback collection and processing.

**Step-by-Step Flow:**

| Step | Action | Result |
|------|--------|--------|
| 1 | Post-delivery survey | SMS sent |
| 2 | Customer rates | 5 stars submitted |
| 3 | Store feedback | CRM updated |
| 4 | Negative feedback | Alert to support |
| 5 | Follow-up | Support contacts customer |

---

#### Integration Test Case INT-048: Loyalty Points Integration

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-048 |
| **Priority** | Medium |
| **Test Type** | Synchronous |
| **Systems Involved** | SYS-001, SYS-003 |

**Test Objective:**
Validate loyalty points accrual and redemption.

**Step-by-Step Flow:**

| Step | Action | Points |
|------|--------|--------|
| 1 | Order placed | ৳1,000 = 10 points |
| 2 | Points credited | Balance updated |
| 3 | Redeem points | 100 points = ৳100 off |
| 4 | Discount applied | Order total reduced |
| 5 | Points deducted | Balance reduced |

---

#### Integration Test Case INT-049: Tax Calculation Integration

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-049 |
| **Priority** | Critical |
| **Test Type** | Synchronous |
| **Systems Involved** | SYS-001, SYS-003 |

**Test Objective:**
Validate automatic tax calculation based on location and product.

**Step-by-Step Flow:**

| Step | Input | Calculation |
|------|-------|-------------|
| 1 | Product: Milk | VAT exempt (food) |
| 2 | Product: Cheese | 15% VAT |
| 3 | Location: Dhaka | Standard rate |
| 4 | B2B order | VAT excluded |
| 5 | Invoice | Correct tax shown |

---

#### Integration Test Case INT-050: Backup and Recovery Validation

| Attribute | Details |
|-----------|---------|
| **Test ID** | INT-050 |
| **Priority** | Critical |
| **Test Type** | Asynchronous |
| **Systems Involved** | All Systems |

**Test Objective:**
Validate backup integrity and recovery procedures.

**Step-by-Step Flow:**

| Step | Action | Verification |
|------|--------|--------------|
| 1 | Create test transaction | Order #TEST001 |
| 2 | Trigger backup | Database backup |
| 3 | Delete transaction | Remove from system |
| 4 | Restore from backup | Recovery process |
| 5 | Verify transaction | Order #TEST001 present |

---

## 4. Integration Test Summary

### 4.1 Test Case Distribution by Category

| Category | Test Cases | Priority Breakdown |
|----------|------------|-------------------|
| End-to-End Order Flow | INT-001 to INT-006 | 3 Critical, 3 High |
| Farm-to-Consumer Traceability | INT-007 to INT-009 | 2 Critical, 1 High |
| IoT Integration Flows | INT-010 to INT-013 | 2 Critical, 2 High |
| Payment Integration Flows | INT-014 to INT-019 | 2 Critical, 4 High |
| Mobile-ERP Sync Flows | INT-020 to INT-023 | 2 Critical, 2 High |
| Inventory-Order Integration | INT-024 to INT-028 | 3 Critical, 2 High |
| Subscription Management | INT-029 to INT-033 | 3 Critical, 2 High |
| Additional Test Cases | INT-034 to INT-050 | 3 Critical, 10 High, 4 Medium |
| **Total** | **50 Test Cases** | 18 Critical, 28 High, 4 Medium |

### 4.2 Integration Type Distribution

| Type | Count | Test Case IDs |
|------|-------|---------------|
| Synchronous | 22 | INT-001, 002, 004, 005, 006, 014, 015, 016, 017, 018, 019, 023, 024, 025, 026, 028, 029, 031, 032, 034, 041, 042, 045, 048, 049 |
| Asynchronous | 28 | INT-003, 007, 008, 009, 010, 011, 012, 013, 020, 021, 022, 027, 030, 033, 035, 036, 037, 038, 039, 040, 043, 044, 046, 047, 050 |

### 4.3 Systems Coverage Matrix

| System | Test Coverage |
|--------|---------------|
| SYS-001 (B2C) | INT-001, 002, 003, 007, 009, 014, 015, 016, 017, 018, 019, 024, 025, 026, 029, 031, 032, 034, 035, 037, 039, 041, 047, 048, 049 |
| SYS-002 (B2B) | INT-004, 005, 009, 034, 045, 046 |
| SYS-003 (ERP Core) | All 50 test cases |
| SYS-004 (Farm) | INT-007, 008, 009, 010, 011, 013, 020, 021, 022, 023, 038 |
| SYS-005 (Inventory) | INT-001, 002, 003, 004, 005, 006, 007, 009, 024, 025, 026, 027, 028, 029, 030, 032, 037, 038 |
| SYS-006 (Payment) | INT-001, 003, 006, 014, 015, 016, 017, 018, 019, 029, 030, 033, 045 |
| SYS-007 (IoT) | INT-010, 011, 012, 013 |
| SYS-008 (Mobile) | INT-020, 021, 022, 023 |
| SYS-009 (SMS) | INT-001, 004, 006, 007, 009, 011, 012, 027, 029, 030, 031, 033, 035, 047 |
| SYS-010 (Email) | INT-007, 009, 036, 046 |
| SYS-011 (Cold Chain) | INT-007, 009, 012 |
| SYS-012 (BI) | INT-010, 036 |

---

## 5. Test Data Requirements

### 5.1 Master Test Data

| Data Type | Records Required | Source |
|-----------|------------------|--------|
| Customers | 100 | Test data generator |
| B2B Partners | 20 | Test data generator |
| Products | 50 | Product catalog |
| Cattle | 100 | Farm records |
| Employees | 30 | HR system |
| Warehouses | 3 | Location config |

### 5.2 Transactional Test Data

| Data Type | Volume | Purpose |
|-----------|--------|---------|
| Orders | 1,000/day | Load testing |
| IoT readings | 10,000/hour | Sensor simulation |
| Payment transactions | 500/day | Payment testing |
| Sync records | 5,000/day | Mobile sync testing |

### 5.3 Test Data Seeding Script

```sql
-- Example: Seed test customers
INSERT INTO customers (id, name, phone, email, type, credit_limit)
VALUES 
  ('CUST-001', 'Test Customer 1', '01700000001', 'test1@email.com', 'B2C', 0),
  ('CUST-002', 'Test Customer 2', '01700000002', 'test2@email.com', 'B2C', 0),
  ('PART-001', 'Fresh Mart Ltd', '01700000003', 'info@freshmart.com', 'B2B', 100000);
```

---

## 6. Appendices

### Appendix A: Integration Test Environment Setup

| Component | Configuration |
|-----------|---------------|
| Application Servers | 3 nodes (load balanced) |
| Database | Primary + 2 Replicas |
| Message Queue | Redis/RabbitMQ cluster |
| IoT Gateway | MQTT broker + processor |
| Test Data | Isolated test database |

### Appendix B: Test Execution Schedule

| Phase | Duration | Test Cases |
|-------|----------|------------|
| Sprint 1 | Week 1-2 | INT-001 to INT-010 |
| Sprint 2 | Week 3-4 | INT-011 to INT-020 |
| Sprint 3 | Week 5-6 | INT-021 to INT-030 |
| Sprint 4 | Week 7-8 | INT-031 to INT-040 |
| Sprint 5 | Week 9-10 | INT-041 to INT-050 |
| Regression | Week 11-12 | All test cases |

### Appendix C: Defect Severity Definitions

| Severity | Definition | Example |
|----------|------------|---------|
| Critical | System unusable, data loss | Payment processed but order not created |
| High | Major feature broken | Stock not updating after order |
| Medium | Workaround available | SMS not sent but order successful |
| Low | Cosmetic issues | Incorrect message text |

### Appendix D: Test Sign-Off Criteria

| Criteria | Target |
|----------|--------|
| Test Case Execution | 100% |
| Pass Rate | ≥ 95% |
| Critical Defects | 0 open |
| High Defects | ≤ 2 open |
| Performance SLA | All met |

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | QA Engineer | Initial document creation |

---

## Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| QA Lead | [To be filled] | _____________ | _______ |
| Solution Architect | [To be filled] | _____________ | _______ |
| Project Manager | [To be filled] | _____________ | _______ |

---

*End of Document G-015: Integration Test Cases for Smart Dairy Ltd.*
