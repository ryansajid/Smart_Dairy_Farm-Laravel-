# SMART DAIRY LTD.
## TEST PLAN - PHASE 3 (COMMERCE)
### Document ID: G-004

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | G-004 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | QA Lead |
| **Owner** | QA Lead |
| **Reviewer** | Project Manager |
| **Classification** | Internal - Confidential |
| **Status** | Draft |

---

## DOCUMENT APPROVAL

| Role | Name | Signature | Date |
|------|------|-----------|------|
| QA Lead | [Name] | _________________ | _______ |
| Project Manager | [Name] | _________________ | _______ |
| IT Director | [Name] | _________________ | _______ |
| Managing Director | [Name] | _________________ | _______ |

---

## REVISION HISTORY

| Version | Date | Author | Changes Description |
|---------|------|--------|---------------------|
| 1.0 | January 31, 2026 | QA Lead | Initial document creation |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Test Scope](#2-test-scope)
3. [Test Schedule](#3-test-schedule)
4. [Payment Testing](#4-payment-testing)
5. [Security Testing](#5-security-testing)
6. [Performance Testing](#6-performance-testing)
7. [Mobile Testing](#7-mobile-testing)
8. [Integration Testing](#8-integration-testing)
9. [UAT Planning](#9-uat-planning)
10. [Entry & Exit Criteria](#10-entry--exit-criteria)
11. [Compliance Testing](#11-compliance-testing)
12. [Defect Management](#12-defect-management)
13. [Rollback Planning](#13-rollback-planning)
14. [Appendices](#14-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This Test Plan defines the comprehensive testing strategy for Phase 3 (Commerce) of the Smart Dairy Smart Web Portal System and Integrated ERP implementation. Phase 3 encompasses Months 7-9 of the project timeline and focuses on the launch of e-commerce capabilities that will enable Smart Dairy to conduct direct-to-consumer sales and B2B wholesale operations.

### 1.2 Phase 3 Scope Overview

Phase 3 represents the critical commerce enablement phase of the Smart Dairy digital transformation. This phase transitions the system from internal operations to customer-facing revenue generation through multiple channels.

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    PHASE 3: COMMERCE (MONTHS 7-9)                           │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐              │
│  │   B2C PORTAL    │  │   B2B PORTAL    │  │  MOBILE APPS    │              │
│  │                 │  │                 │  │                 │              │
│  │ • Product Store │  │ • Wholesale     │  │ • iOS App       │              │
│  │ • Subscriptions │  │ • Credit Mgmt   │  │ • Android App   │              │
│  │ • Checkout      │  │ • Bulk Orders   │  │ • Ordering      │              │
│  │ • Customer Mgmt │  │ • Partner Portal│  │ • Tracking      │              │
│  └────────┬────────┘  └────────┬────────┘  └────────┬────────┘              │
│           │                    │                    │                        │
│           └────────────────────┼────────────────────┘                        │
│                                │                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │              PAYMENT GATEWAY INTEGRATION LAYER                       │    │
│  │  • bKash      • Nagad      • Rocket      • SSLCommerz               │    │
│  │  • Cards      • COD        • Wallet      • BNPL                     │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                │                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │              ERP INTEGRATION & INVENTORY SYNC                        │    │
│  │  • Real-time Stock Updates  • Order Processing  • Delivery Mgmt     │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 1.3 Commerce Components Under Test

| Component | Description | Criticality |
|-----------|-------------|-------------|
| **B2C E-commerce Portal** | Consumer-facing online store with product catalog, shopping cart, and checkout | Critical |
| **B2B Marketplace Portal** | Wholesale ordering platform for retailers, HORECA, and institutional buyers | Critical |
| **Payment Gateway Integration** | Integration with bKash, Nagad, Rocket, SSLCommerz, and cash on delivery | Critical |
| **Subscription Management** | Recurring order engine for daily/weekly/monthly milk subscriptions | Critical |
| **Customer Mobile App** | Native iOS and Android applications for consumer ordering | Critical |
| **Inventory Integration** | Real-time synchronization between e-commerce and ERP inventory | High |
| **Delivery Management** | Route optimization and delivery tracking system | High |
| **Loyalty & Rewards** | Customer points system and referral program | Medium |

### 1.4 Testing Objectives

The primary objectives of Phase 3 testing are to:

1. **Validate Payment Security** - Ensure all payment transactions are secure and PCI DSS compliant
2. **Verify Order Processing** - Confirm end-to-end order flow from cart to delivery
3. **Test Multi-Channel Experience** - Ensure consistent experience across web and mobile
4. **Validate Subscription Logic** - Test recurring orders, billing, and modification workflows
5. **Ensure Inventory Accuracy** - Verify real-time stock synchronization prevents overselling
6. **Confirm B2B Functionality** - Validate credit terms, bulk ordering, and partner management
7. **Assess Performance Under Load** - Ensure system handles peak traffic during promotions
8. **Validate Compliance** - Confirm adherence to Bangladesh e-commerce regulations

### 1.5 References

| Document ID | Document Name | Version |
|-------------|---------------|---------|
| RFP-02 | B2C E-commerce Portal RFP | 1.0 |
| RFP-03 | B2B Marketplace Portal RFP | 1.0 |
| RFP-06 | Customer Mobile App RFP | 1.0 |
| E-003 | Payment Gateway Integration Guide | 1.0 |
| E-004 | bKash Payment Integration Guide | 1.0 |
| E-005 | Nagad Payment Integration Guide | 1.0 |
| F-002 | PCI DSS Compliance Guide | 1.0 |
| G-001 | Test Strategy Document | 1.0 |
| G-002 | Test Plan - Phase 1 (Foundation) | 1.0 |
| G-003 | Test Plan - Phase 2 (Operations) | 1.0 |

---

## 2. TEST SCOPE

### 2.1 In-Scope Components

#### 2.1.1 B2C E-commerce Portal Testing

| Feature Area | Testing Focus | Test Types |
|--------------|---------------|------------|
| **Product Catalog** | Category browsing, filtering, search, product details | Functional, UI/UX, Cross-browser |
| **Shopping Cart** | Add/remove items, quantity update, persistency | Functional, Session, Security |
| **Checkout Flow** | Guest/registered checkout, address management | Functional, Integration, Usability |
| **User Registration** | Account creation, email verification, profile management | Functional, Security |
| **Order Management** | Order placement, tracking, history, cancellation | Functional, Integration |
| **Wishlist** | Save items, move to cart, share functionality | Functional |
| **Reviews & Ratings** | Product reviews, moderation, helpful voting | Functional, Moderation |
| **Promotions** | Coupon codes, discounts, flash sales, bundles | Functional, Calculation |
| **Customer Service** | Contact forms, chatbot, FAQ, ticket tracking | Functional, Integration |

#### 2.1.2 B2B Marketplace Portal Testing

| Feature Area | Testing Focus | Test Types |
|--------------|---------------|------------|
| **Partner Registration** | Self-service signup, approval workflow, tier assignment | Functional, Workflow |
| **Partner Tiers** | Distributor, Retailer, HORECA, Institutional differences | Functional, Authorization |
| **Bulk Ordering** | Quick order forms, CSV upload, reorder templates | Functional, Data Import |
| **Tier-based Pricing** | Volume discounts, contract pricing, quote requests | Functional, Calculation |
| **Credit Management** | Credit limit setup, terms, aging, statements | Functional, Financial |
| **B2B Dashboard** | Order history, outstanding payments, delivery schedules | Functional, Reporting |
| **Invoice Management** | Electronic invoices, credit notes, downloads | Functional, PDF Generation |
| **RFQ Workflow** | Request for quotation, approval, conversion to order | Functional, Workflow |
| **API Access** | Partner system integration, rate limiting, authentication | API, Security |

#### 2.1.3 Payment Gateway Testing

| Gateway | Test Scenarios | Priority |
|---------|----------------|----------|
| **bKash** | Wallet payment, PIN verification, transaction status | P0 - Critical |
| **Nagad** | Wallet payment, OTP verification, confirmation | P0 - Critical |
| **Rocket** | Bank wallet integration, transaction processing | P0 - Critical |
| **SSLCommerz** | Card payments (Visa, MasterCard, Amex), EMI | P0 - Critical |
| **Cash on Delivery** | COD order flow, delivery confirmation, reconciliation | P1 - High |
| **BNPL (Future)** | Installment plans, eligibility checks | P2 - Medium |

#### 2.1.4 Subscription Management Testing

| Feature | Testing Focus | Test Types |
|---------|---------------|------------|
| **Subscription Creation** | Daily/weekly/monthly plans, quantity selection | Functional |
| **Modification** | Pause, resume, change quantity, change frequency | Functional, State Management |
| **Billing Cycle** | Automatic billing, payment success/failure handling | Functional, Integration |
| **Delivery Schedule** | Calendar-based delivery, skip dates, holiday handling | Functional, Calendar Logic |
| **Subscription Dashboard** | Upcoming deliveries, payment history, modification history | Functional |
| **Renewal Logic** | Auto-renewal, expirations, grace periods | Functional, Date Logic |

#### 2.1.5 Customer Mobile App Testing

| Platform | Testing Focus | Device Coverage |
|----------|---------------|-----------------|
| **iOS** | App Store compliance, iOS versions 15-18, iPhone/iPad | iPhone 12-16 series, iPad Air/Pro |
| **Android** | Play Store compliance, Android 10-15, various OEMs | Samsung, Xiaomi, Realme, Vivo |
| **Common** | Offline functionality, push notifications, deep linking | Cross-platform |

### 2.2 Out-of-Scope Items

The following items are explicitly out of scope for Phase 3 testing:

| Item | Reason | Future Phase |
|------|--------|--------------|
| Vendor Mobile App | Scheduled for Phase 4 | Phase 4 |
| Advanced AI Recommendations | ML model training incomplete | Phase 4 |
| International Payment Methods | Not required for Bangladesh market | Phase 4 |
| Franchise Portal | Business model not finalized | Phase 4 |
| Advanced Analytics & BI | Core commerce priority first | Phase 4 |
| Multi-warehouse Fulfillment | Single warehouse operation currently | Phase 4 |

### 2.3 Test Environment Requirements

| Environment | Purpose | Configuration |
|-------------|---------|---------------|
| **Development** | Unit testing, developer validation | Local Docker containers |
| **QA/Staging** | Full integration testing, SIT | Cloud-based, isolated data |
| **UAT** | User acceptance testing | Production-like data subset |
| **Pre-Production** | Final validation, performance testing | Production mirror |
| **Production** | Live monitoring, smoke testing | Live environment |

### 2.4 Test Data Requirements

| Data Type | Volume | Source | PII Handling |
|-----------|--------|--------|--------------|
| Test Products | 50+ SKUs | Production anonymized | N/A |
| Test Customers | 500 accounts | Synthetic generation | Masked |
| Test B2B Partners | 50 partners | Synthetic generation | Masked |
| Test Payment Methods | All gateways | Sandbox environments | No real PII |
| Test Orders | 10,000+ historical | Generated patterns | Anonymized |
| Test Inventory | Multi-location | ERP test instance | N/A |

---

## 3. TEST SCHEDULE

### 3.1 Phase 3 Commerce Timeline

```
MONTH 7                      MONTH 8                      MONTH 9
Week: 1  2  3  4        Week: 1  2  3  4        Week: 1  2  3  4
─────┬────┬────┬────   ─────┬────┬────┬────   ─────┬────┬────┬────
     │B2C │B2C │B2C        │B2B │B2B │B2B        │UAT │UAT │    
     │Dev │Dev │Test       │Dev │Dev │Test       │    │    │GO  
     │    │    │           │    │    │           │    │    │LIVE
     ├────┼────┤           ├────┼────┤           ├────┼────┤    
     │Payment               │Mobile│Mobile       │Perf│Secu│    
     │Integration           │Dev   │Test         │Test│rity│    
     │                      │      │             │    │    │    
     ├────┴────┤           ├──────┴────┤        ├────┴────┤    
     │B2C SIT  │           │B2B SIT    │        │UAT Exec │    
     │          │           │            │        │         │    
─────┴──────────┴───────────┴────────────┴────────┴─────────┴────
```

### 3.2 Detailed Test Schedule

| Activity | Start Date | End Date | Duration | Owner |
|----------|------------|----------|----------|-------|
| **Month 7 - B2C E-commerce** |
| Test Planning & Preparation | July 1 | July 5 | 5 days | QA Lead |
| B2C Portal Test Case Creation | July 6 | July 12 | 7 days | QA Team |
| Payment Gateway Test Setup | July 6 | July 10 | 5 days | QA Engineer |
| B2C Functional Testing | July 15 | July 26 | 10 days | QA Team |
| Payment Integration Testing | July 20 | July 31 | 10 days | QA Engineer |
| B2C System Integration Testing | July 29 | August 9 | 10 days | QA Team |
| **Month 8 - B2B & Mobile** |
| B2B Portal Test Case Creation | August 1 | August 7 | 7 days | QA Team |
| Mobile App Test Case Creation | August 1 | August 7 | 7 days | Mobile QA |
| B2B Functional Testing | August 5 | August 16 | 10 days | QA Team |
| Mobile App Testing (iOS) | August 8 | August 18 | 9 days | Mobile QA |
| Mobile App Testing (Android) | August 12 | August 22 | 9 days | Mobile QA |
| Subscription Engine Testing | August 15 | August 23 | 7 days | QA Engineer |
| B2B System Integration Testing | August 19 | August 30 | 10 days | QA Team |
| **Month 9 - UAT & Go-Live** |
| Performance Testing | September 2 | September 8 | 5 days | Perf QA |
| Security Testing (PCI DSS) | September 2 | September 10 | 7 days | Security QA |
| UAT Preparation & Training | September 2 | September 6 | 5 days | QA Lead |
| User Acceptance Testing | September 9 | September 20 | 10 days | Business Users |
| Bug Fix Verification | September 16 | September 24 | 7 days | QA Team |
| Go-Live Readiness Assessment | September 23 | September 25 | 3 days | QA Lead |
| Production Smoke Testing | September 26 | September 27 | 2 days | QA Team |
| **Commerce Go-Live** | **September 30** | - | - | Project Team |

### 3.3 Milestone Review Gates

| Gate | Date | Criteria | Approver |
|------|------|----------|----------|
| **B2C Ready for SIT** | July 26 | All P0 B2C features tested, <5 open P1 bugs | QA Lead |
| **Payment Integration Ready** | July 31 | All payment flows tested, sandbox certified | QA Lead |
| **B2B Ready for SIT** | August 16 | All P0 B2B features tested, <5 open P1 bugs | QA Lead |
| **Mobile Apps Ready** | August 22 | iOS & Android tested, app store ready | Mobile QA Lead |
| **Performance Baseline Met** | September 8 | All performance criteria achieved | Perf QA Lead |
| **Security Sign-off** | September 10 | PCI DSS controls validated | Security Lead |
| **UAT Complete** | September 20 | All critical business scenarios passed | Business Lead |
| **Go-Live Approval** | September 27 | All exit criteria met | Project Manager |

---

## 4. PAYMENT TESTING

### 4.1 Payment Gateway Test Strategy

Payment testing is the most critical aspect of Phase 3 commerce testing. A multi-layered approach ensures transaction security, accuracy, and reliability.

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                     PAYMENT TESTING PYRAMID                                  │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│                           ┌─────────────┐                                    │
│                           │   E2E FLOW  │  ← Full checkout to settlement   │
│                           │   TESTING   │                                    │
│                           └──────┬──────┘                                    │
│                          ┌───────┴───────┐                                   │
│                          │  INTEGRATION  │  ← Gateway API integration       │
│                          │    TESTING    │                                    │
│                          └───────┬───────┘                                   │
│                     ┌────────────┴────────────┐                              │
│                     │    GATEWAY-SPECIFIC     │  ← bKash, Nagad, etc.        │
│                     │       TESTING           │                              │
│                     └────────────┬────────────┘                              │
│            ┌─────────────────────┴─────────────────────┐                     │
│            │              UNIT TESTING                 │  ← Code level       │
│            │         (Payment Service Layer)           │                     │
│            └───────────────────────────────────────────┘                     │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 4.2 bKash Payment Testing

#### 4.2.1 bKash Test Scenarios

| Test ID | Scenario | Expected Result | Priority |
|---------|----------|-----------------|----------|
| BK-001 | Successful wallet payment | Transaction completed, confirmation received | P0 |
| BK-002 | Insufficient balance handling | Clear error message, order not placed | P0 |
| BK-003 | Invalid PIN entry | Error displayed, retry allowed (max 3) | P0 |
| BK-004 | Transaction timeout | Graceful timeout handling, order pending | P0 |
| BK-005 | Payment cancellation by user | Order cancelled, no deduction | P0 |
| BK-006 | Duplicate transaction prevention | Same transaction ID rejected | P0 |
| BK-007 | Refund processing | Full refund completed within SLA | P1 |
| BK-008 | Partial refund | Partial amount refunded correctly | P1 |
| BK-009 | Transaction status inquiry | Real-time status retrieval | P1 |
| BK-010 | Sandbox to production migration | Seamless transition, no code changes | P1 |

#### 4.2.2 bKash Integration Test Flow

```
Customer Initiates Payment
         │
         ▼
┌─────────────────┐
│  Order Created  │
│ Status: PENDING │
└────────┬────────┘
         │
         ▼
┌─────────────────┐     ┌─────────────────┐
│  bKash Checkout │────▶│  bKash Gateway  │
│  URL Generated  │     │  (Sandbox/Prod) │
└────────┬────────┘     └────────┬────────┘
         │                        │
         │                        ▼
         │              ┌─────────────────┐
         │              │ PIN Verification│
         │              │ (Customer App)  │
         │              └────────┬────────┘
         │                       │
         │                       ▼
         │              ┌─────────────────┐
         │              │  OTP/Confirm    │
         │              │  (if required)  │
         │              └────────┬────────┘
         │                       │
         ▼                       ▼
┌─────────────────┐     ┌─────────────────┐
│Webhook Received │◀────│ Transaction     │
│Status Updated   │     │ Completed       │
└────────┬────────┘     └─────────────────┘
         │
         ▼
┌─────────────────┐
│ Order Confirmed │
│ Inventory Deduct│
│ Notify Customer │
└─────────────────┘
```

### 4.3 Nagad Payment Testing

| Test ID | Scenario | Expected Result | Priority |
|---------|----------|-----------------|----------|
| NG-001 | Successful Nagad wallet payment | Transaction completed | P0 |
| NG-002 | OTP verification flow | OTP received and validated | P0 |
| NG-003 | Invalid OTP handling | Error, retry with new OTP | P0 |
| NG-004 | Nagad app not installed | Redirect to web checkout | P1 |
| NG-005 | Account not registered | Clear registration prompt | P1 |
| NG-006 | Transaction limit exceeded | Appropriate error message | P1 |
| NG-007 | Merchant callback failure | Retry mechanism activated | P1 |

### 4.4 Rocket Payment Testing

| Test ID | Scenario | Expected Result | Priority |
|---------|----------|-----------------|----------|
| RK-001 | Successful Rocket payment | Transaction completed | P0 |
| RK-002 | Dutch-Bangla Bank account debit | Correct account debited | P0 |
| RK-003 | Rocket wallet payment | Wallet balance deducted | P0 |
| RK-004 | PIN verification | Secure PIN validation | P0 |
| RK-005 | Transaction failure recovery | Order status synchronized | P1 |

### 4.5 SSLCommerz Payment Testing

| Test ID | Scenario | Expected Result | Priority |
|---------|----------|-----------------|----------|
| SSL-001 | Successful Visa card payment | Transaction authorized | P0 |
| SSL-002 | Successful MasterCard payment | Transaction authorized | P0 |
| SSL-003 | Card 3D Secure authentication | OTP/password verified | P0 |
| SSL-004 | Declined card handling | Clear decline reason shown | P0 |
| SSL-005 | Expired card rejection | Appropriate error message | P0 |
| SSL-006 | Invalid CVV handling | CVV validation error | P0 |
| SSL-007 | EMI option display | Available EMI plans shown | P1 |
| SSL-008 | EMI transaction processing | EMI plan correctly applied | P1 |
| SSL-009 | Multi-currency handling | BDT conversion correct | P2 |
| SSL-010 | Card tokenization | Secure token storage | P1 |

### 4.6 Cash on Delivery (COD) Testing

| Test ID | Scenario | Expected Result | Priority |
|---------|----------|-----------------|----------|
| COD-001 | COD order placement | Order confirmed, payment pending | P0 |
| COD-002 | COD eligibility check | Address/serviceability validated | P0 |
| COD-003 | COD amount limit | Orders above limit rejected | P1 |
| COD-004 | Delivery confirmation | Payment marked received | P0 |
| COD-005 | COD reconciliation | Daily reconciliation report | P1 |
| COD-006 | COD cancellation before delivery | Order cancelled, no charge | P1 |

### 4.7 Payment Reconciliation Testing

| Test ID | Scenario | Expected Result | Priority |
|---------|----------|-----------------|----------|
| REC-001 | Daily settlement report | All transactions reconciled | P0 |
| REC-002 | Gateway vs ERP mismatch detection | Discrepancies flagged | P0 |
| REC-003 | Failed transaction handling | Failed orders not settled | P0 |
| REC-004 | Partial settlement handling | Correct amounts recorded | P1 |
| REC-005 | Refund reconciliation | Refunds matched to original | P1 |

---

## 5. SECURITY TESTING

### 5.1 PCI DSS Compliance Testing

Payment Card Industry Data Security Standard (PCI DSS) compliance is mandatory for all payment handling systems.

#### 5.1.1 PCI DSS Requirements Testing Matrix

| Requirement | Description | Test Approach | Status |
|-------------|-------------|---------------|--------|
| **Req 1** | Install and maintain network security controls | Firewall configuration review, penetration testing | Planned |
| **Req 2** | Apply secure configurations to all system components | Hardening checklist, vulnerability scan | Planned |
| **Req 3** | Protect stored account data | Data discovery, encryption validation | Planned |
| **Req 4** | Protect cardholder data with strong cryptography | TLS 1.3 validation, cipher suite audit | Planned |
| **Req 5** | Protect all systems and networks from malicious software | Anti-malware testing, EDR validation | Planned |
| **Req 6** | Develop and maintain secure systems and software | Code review, SAST/DAST scanning | Planned |
| **Req 7** | Restrict access to system components and cardholder data | Access control testing, RBAC validation | Planned |
| **Req 8** | Identify users and authenticate access to system components | Authentication testing, MFA validation | Planned |
| **Req 9** | Restrict physical access to cardholder data | Physical security review (datacenter) | Planned |
| **Req 10** | Log and monitor all access to system components | Log review, SIEM validation | Planned |
| **Req 11** | Test security of systems and networks regularly | Quarterly scan, annual penetration test | Planned |
| **Req 12** | Support information security with organizational policies | Policy review, documentation audit | Planned |

#### 5.1.2 PCI DSS Test Schedule

| Activity | Start | End | Responsible |
|----------|-------|-----|-------------|
| ASV (Approved Scanning Vendor) Scan | Sep 2 | Sep 4 | External Vendor |
| Internal Vulnerability Assessment | Sep 2 | Sep 5 | Security Team |
| Application Security Testing | Sep 3 | Sep 8 | Security QA |
| Configuration Review | Sep 4 | Sep 6 | Security Team |
| Penetration Testing | Sep 5 | Sep 10 | External Vendor |
| Remediation | Sep 8 | Sep 15 | Dev Team |
| Re-scan | Sep 16 | Sep 17 | Security Team |
| Compliance Report | Sep 18 | Sep 20 | Security Lead |

### 5.2 Application Security Testing

| Test Type | Tools | Scope | Timeline |
|-----------|-------|-------|----------|
| **SAST** | SonarQube, Checkmarx | All custom code | Ongoing |
| **DAST** | OWASP ZAP, Burp Suite | Running applications | Sep 3-8 |
| **Dependency Scan** | Snyk, OWASP Dependency-Check | All libraries | Ongoing |
| **Container Scan** | Trivy, Clair | Docker images | Ongoing |
| **Secret Detection** | GitLeaks, TruffleHog | Code repository | Ongoing |

### 5.3 Security Test Scenarios

| Test ID | Scenario | Test Method | Expected Result |
|---------|----------|-------------|-----------------|
| SEC-001 | SQL Injection on search | Automated scan + manual | No injection possible |
| SEC-002 | XSS on product reviews | Manual testing | Proper sanitization |
| SEC-003 | CSRF on checkout | Automated testing | Tokens validated |
| SEC-004 | IDOR on order viewing | Manual testing | Access restricted to owner |
| SEC-005 | Session fixation | Manual testing | Session regenerated |
| SEC-006 | Card data in logs | Log review | No PAN in logs |
| SEC-007 | TLS downgrade | SSL Labs scan | TLS 1.3 enforced |
| SEC-008 | Brute force protection | Automated testing | Rate limiting active |
| SEC-009 | Insecure direct object reference | Manual testing | Proper authorization |
| SEC-010 | Sensitive data exposure | Data scan | Encryption at rest |

---

## 6. PERFORMANCE TESTING

### 6.1 Performance Testing Strategy

Commerce systems must handle traffic spikes during promotional periods and daily subscription order windows.

#### 6.1.1 Performance Test Objectives

| Metric | Target | Measurement |
|--------|--------|-------------|
| **Page Load Time** | < 3 seconds | 95th percentile |
| **API Response Time** | < 500ms | Average |
| **Checkout Completion** | < 5 seconds | End-to-end |
| **Payment Processing** | < 3 seconds | Gateway response |
| **Concurrent Users** | 1,000+ | Simultaneous active |
| **Orders per Minute** | 100+ | Sustained throughput |
| **Database Query Time** | < 100ms | 95th percentile |
| **Error Rate** | < 0.1% | All transactions |

#### 6.1.2 Load Testing Scenarios

| Scenario | Users | Duration | Ramp-up | Purpose |
|----------|-------|----------|---------|---------|
| **Normal Load** | 200 | 30 min | 5 min | Baseline performance |
| **Peak Load** | 500 | 1 hour | 10 min | Daily peak simulation |
| **Stress Test** | 1,000 | 30 min | 15 min | System breaking point |
| **Spike Test** | 0→500→0 | 15 min | 2 min | Sudden traffic spike |
| **Endurance Test** | 300 | 8 hours | 10 min | Memory leak detection |
| **Checkout Load** | 100 concurrent | 30 min | 5 min | Payment bottleneck |

#### 6.1.3 Performance Test Schedule

| Activity | Date | Duration | Owner |
|----------|------|----------|-------|
| Test Environment Setup | Sep 2 | 1 day | DevOps |
| Script Development | Sep 2-3 | 2 days | Perf QA |
| Baseline Testing | Sep 4 | 1 day | Perf QA |
| Load Testing | Sep 5-6 | 2 days | Perf QA |
| Stress Testing | Sep 6 | 1 day | Perf QA |
| Analysis & Reporting | Sep 7-8 | 2 days | Perf QA |
| Optimization | Sep 8-15 | As needed | Dev Team |

### 6.2 Performance Test Tools

| Tool | Purpose | Usage |
|------|---------|-------|
| **JMeter** | Load generation | Primary load testing |
| **Gatling** | High concurrency | Spike testing |
| **k6** | API performance | Continuous testing |
| **New Relic** | APM monitoring | Real-time metrics |
| **Grafana** | Visualization | Dashboards |
| **Prometheus** | Metrics collection | Infrastructure metrics |

---

## 7. MOBILE TESTING

### 7.1 Mobile Testing Strategy

The Customer Mobile App is available on both iOS and Android platforms, requiring comprehensive testing across devices, OS versions, and network conditions.

### 7.2 Device Coverage Matrix

#### 7.2.1 iOS Devices

| Device | OS Versions | Priority | Quantity |
|--------|-------------|----------|----------|
| iPhone 16 Pro | iOS 18.x | P0 | 2 |
| iPhone 15 | iOS 17.x, 18.x | P0 | 2 |
| iPhone 14 | iOS 16.x, 17.x, 18.x | P0 | 2 |
| iPhone 13 | iOS 15.x - 18.x | P1 | 1 |
| iPhone 12 | iOS 15.x - 17.x | P1 | 1 |
| iPad Pro | iPadOS 17.x, 18.x | P1 | 1 |
| iPad Air | iPadOS 16.x - 18.x | P2 | 1 |

#### 7.2.2 Android Devices

| Device | OS Versions | Priority | Quantity |
|--------|-------------|----------|----------|
| Samsung Galaxy S24 | Android 14 | P0 | 2 |
| Samsung Galaxy S23 | Android 13, 14 | P0 | 2 |
| Xiaomi 14 | Android 14 | P0 | 1 |
| Realme GT | Android 13, 14 | P1 | 1 |
| Vivo V29 | Android 13, 14 | P1 | 1 |
| Samsung A54 (Mid-range) | Android 13, 14 | P1 | 1 |
| Xiaomi Redmi Note (Budget) | Android 12, 13 | P2 | 1 |

### 7.3 Mobile Test Categories

| Category | Focus Areas | Test Count |
|----------|-------------|------------|
| **Functional** | User flows, features, validation | 200+ |
| **UI/UX** | Layout, navigation, gestures | 100+ |
| **Compatibility** | OS versions, screen sizes, OEMs | 50+ |
| **Performance** | Launch time, memory, battery | 30+ |
| **Network** | Offline, slow, intermittent | 40+ |
| **Security** | Storage, transmission, authentication | 25+ |
| **Accessibility** | Screen readers, contrast, sizing | 20+ |

### 7.4 Mobile Test Scenarios

| Test ID | Scenario | iOS | Android |
|---------|----------|-----|---------|
| MOB-001 | App installation from store | ✓ | ✓ |
| MOB-002 | First-time user onboarding | ✓ | ✓ |
| MOB-003 | Product browsing and search | ✓ | ✓ |
| MOB-004 | Add to cart and checkout | ✓ | ✓ |
| MOB-005 | bKash payment flow | ✓ | ✓ |
| MOB-006 | Push notification delivery | ✓ | ✓ |
| MOB-007 | Deep linking from notification | ✓ | ✓ |
| MOB-008 | Order tracking | ✓ | ✓ |
| MOB-009 | Subscription management | ✓ | ✓ |
| MOB-010 | Offline browsing (cached products) | ✓ | ✓ |
| MOB-011 | App background/foreground handling | ✓ | ✓ |
| MOB-012 | Session timeout handling | ✓ | ✓ |
| MOB-013 | Biometric authentication | ✓ | ✓ |
| MOB-014 | Screen rotation handling | ✓ | ✓ |
| MOB-015 | Low memory handling | ✓ | ✓ |

### 7.5 Mobile App Store Compliance

| Requirement | iOS App Store | Google Play | Status |
|-------------|---------------|-------------|--------|
| App Store Guidelines | Review checklist | Content rating | Planned |
| Privacy Policy | Required | Required | In Progress |
| Data Usage Disclosure | Nutrition labels | Data safety | In Progress |
| Screenshots | 5+ screenshots | 8+ screenshots | Planned |
| App Preview Video | Optional | Optional | Planned |
| Age Rating | 4+ | Everyone | Planned |
| In-App Purchases | Not applicable | Not applicable | N/A |

---

## 8. INTEGRATION TESTING

### 8.1 ERP Integration Testing

Real-time inventory synchronization between e-commerce and ERP is critical to prevent overselling.

#### 8.1.1 Integration Points

| Integration | Direction | Frequency | Criticality |
|-------------|-----------|-----------|-------------|
| **Product Catalog** | ERP → E-commerce | Real-time | High |
| **Inventory Stock** | ERP ↔ E-commerce | Real-time | Critical |
| **Price Updates** | ERP → E-commerce | Real-time | High |
| **Order Creation** | E-commerce → ERP | Real-time | Critical |
| **Order Status** | ERP → E-commerce | Real-time | High |
| **Customer Data** | Bidirectional | Real-time | High |
| **Payment Status** | Gateway → ERP | Real-time | Critical |
| **Delivery Updates** | ERP → E-commerce | Real-time | Medium |

#### 8.1.2 Inventory Sync Testing Scenarios

| Test ID | Scenario | Expected Behavior | Priority |
|---------|----------|-------------------|----------|
| INT-INV-001 | Stock reduction on order | Inventory deducted immediately | P0 |
| INT-INV-002 | Stock increase on cancellation | Inventory restored immediately | P0 |
| INT-INV-003 | Concurrent orders exceeding stock | Second order rejected/waitlisted | P0 |
| INT-INV-004 | ERP stock update to e-commerce | Web stock updated within 30s | P0 |
| INT-INV-005 | Network failure during sync | Retry mechanism, queue preserved | P0 |
| INT-INV-006 | Batch stock adjustment | All channels synchronized | P1 |
| INT-INV-007 | Multi-location inventory | Correct location stock displayed | P1 |
| INT-INV-008 | Reserved stock handling | Available vs. on-hand distinction | P1 |

#### 8.1.3 Order Flow Integration Testing

```
┌─────────────────┐
│ Customer Places │
│ Order (Web/App) │
└────────┬────────┘
         │
         ▼
┌─────────────────────────────────────────────────────────────────┐
│                    INTEGRATION FLOW                              │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌───────────────┐    ┌───────────────┐    ┌───────────────┐   │
│  │  E-commerce   │───▶│  Payment      │───▶│  Order        │   │
│  │  Platform     │    │  Gateway      │    │  Confirmed    │   │
│  └───────┬───────┘    └───────────────┘    └───────┬───────┘   │
│          │                                          │            │
│          │            ┌─────────────────┐           │            │
│          └───────────▶│  Real-time      │◀──────────┘            │
│                       │  Inventory      │                        │
│          ┌───────────▶│  Update         │◀──────────┐            │
│          │            └─────────────────┘           │            │
│          │                                          │            │
│  ┌───────▼───────┐    ┌───────────────┐    ┌───────▼───────┐   │
│  │  ERP System   │◀───│  Sales Order  │───▶│  Delivery     │   │
│  │  (Odoo)       │    │  Created      │    │  Planning     │   │
│  └───────┬───────┘    └───────────────┘    └───────────────┘   │
│          │                                                      │
│          ▼                                                      │
│  ┌───────────────┐    ┌───────────────┐    ┌───────────────┐   │
│  │  Accounting   │    │  Inventory    │    │  Customer     │   │
│  │  Entry        │    │  Deduction    │    │  Notification │   │
│  └───────────────┘    └───────────────┘    └───────────────┘   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 8.2 Payment Gateway Integration Testing

| Test ID | Integration Point | Test Scenario | Expected Result |
|---------|-------------------|---------------|-----------------|
| INT-PAY-001 | E-commerce → bKash | Checkout initiation | Checkout URL received |
| INT-PAY-002 | bKash → E-commerce | Webhook callback | Order status updated |
| INT-PAY-003 | E-commerce → SSLCommerz | Card payment flow | Redirect to 3DS/result |
| INT-PAY-004 | SSLCommerz → E-commerce | IPN notification | Payment confirmed |
| INT-PAY-005 | Gateway → ERP | Settlement data | Reconciliation records |
| INT-PAY-006 | ERP → E-commerce | Refund trigger | Refund processed |

### 8.3 SMS/Notification Integration Testing

| Test ID | Scenario | Integration | Expected Result |
|---------|----------|-------------|-----------------|
| INT-NOT-001 | Order confirmation SMS | SMS Gateway | SMS delivered within 30s |
| INT-NOT-002 | Payment confirmation | SMS + Push | Both notifications sent |
| INT-NOT-003 | Delivery update | Push + SMS | Delivery notification |
| INT-NOT-004 | OTP for payment | SMS Gateway | OTP delivered |
| INT-NOT-005 | Email invoice | Email Service | Email with PDF delivered |

---

## 9. UAT PLANNING

### 9.1 UAT Strategy

User Acceptance Testing for Phase 3 Commerce will involve a pilot program with real customers and B2B partners in a controlled environment.

### 9.2 UAT Participants

| User Group | Count | Role | Responsibilities |
|------------|-------|------|------------------|
| **B2C Pilot Customers** | 50 | End consumers | Test normal shopping flows |
| **Retail Partners** | 10 | Small retailers | Test B2B ordering |
| **HORECA Partners** | 5 | Hotels/Restaurants | Test bulk ordering, credit |
| **Internal Sales Team** | 5 | Sales staff | Test admin functions |
| **Customer Service** | 3 | Support agents | Test order management |
| **Management** | 3 | Decision makers | Sign-off on go-live |

### 9.3 UAT Test Scenarios

#### 9.3.1 B2C Customer Scenarios

| Scenario ID | Description | Success Criteria |
|-------------|-------------|------------------|
| UAT-B2C-001 | Browse products and add to cart | Products displayed, cart updates |
| UAT-B2C-002 | Complete checkout with bKash | Payment successful, order confirmed |
| UAT-B2C-003 | Set up daily milk subscription | Subscription active, first delivery scheduled |
| UAT-B2C-004 | Track order delivery | Tracking information accurate |
| UAT-B2C-005 | Apply promotional coupon | Discount applied correctly |
| UAT-B2C-006 | Request order cancellation | Cancellation processed, refund initiated |
| UAT-B2C-007 | Write product review | Review submitted and visible |
| UAT-B2C-008 | Use mobile app for order | Same functionality as web |

#### 9.3.2 B2B Partner Scenarios

| Scenario ID | Description | Success Criteria |
|-------------|-------------|------------------|
| UAT-B2B-001 | Register as new partner | Account approved, tier assigned |
| UAT-B2B-002 | Place bulk order with CSV upload | Order created from CSV data |
| UAT-B2B-003 | Request credit limit increase | Request submitted, approved |
| UAT-B2B-004 | View statement of account | Accurate outstanding balance |
| UAT-B2B-005 | Reorder from history | Previous order copied, editable |
| UAT-B2B-006 | Request quotation for custom order | RFQ submitted, quote received |
| UAT-B2B-007 | Download tax invoice | PDF generated correctly |

### 9.4 UAT Schedule

| Activity | Dates | Duration |
|----------|-------|----------|
| UAT Environment Setup | Sep 2-6 | 5 days |
| Test Data Preparation | Sep 2-6 | 5 days |
| UAT Kickoff & Training | Sep 9 | 1 day |
| B2C Pilot Testing | Sep 10-15 | 6 days |
| B2B Pilot Testing | Sep 12-17 | 6 days |
| Bug Fixes & Retest | Sep 16-22 | 7 days |
| UAT Sign-off | Sep 23 | 1 day |

### 9.5 UAT Entry Criteria

- All P0 test cases passed in SIT
- No open P0 bugs
- UAT environment provisioned with production-like data
- UAT test cases reviewed and approved
- User guides and training materials ready
- Support team trained and available

### 9.6 UAT Exit Criteria

- 100% of critical UAT scenarios passed
- 95% of high-priority scenarios passed
- No open P0 or P1 bugs
- All P2 bugs have workaround documented
- Business stakeholders provide sign-off
- Rollback plan tested and ready

---

## 10. ENTRY & EXIT CRITERA

### 10.1 Phase 3 Entry Criteria

The following criteria must be met before Phase 3 Commerce testing can commence:

| Criterion | Requirement | Verification |
|-----------|-------------|--------------|
| **Code Completion** | All P0/P1 features developed and unit tested | Code review sign-off |
| **Environment Ready** | QA environment provisioned and configured | Environment checklist |
| **Test Data** | Test data loaded and validated | Data validation report |
| **Payment Sandboxes** | All payment gateway sandboxes configured | Integration test |
| **Documentation** | Functional specs and test cases ready | Document review |
| **Phase 2 Stable** | Phase 2 operations features stable | Phase 2 sign-off |
| **Test Team Ready** | QA team trained on commerce domain | Training completion |

### 10.2 Commerce Go-Live Exit Criteria

| Criterion | Target | Minimum Acceptable |
|-----------|--------|-------------------|
| **Test Coverage** | 100% of requirements | 95% of requirements |
| **Test Pass Rate** | 100% P0, 95% P1 | 100% P0, 90% P1 |
| **Defect Density** | <1 P0 per 100 test cases | <2 P0 per 100 test cases |
| **Open Defects** | 0 P0, <5 P1 | 0 P0, <10 P1 |
| **Performance** | All targets met | Critical paths meet targets |
| **Security** | PCI DSS compliant | No critical vulnerabilities |
| **UAT Completion** | 100% scenarios passed | 95% scenarios passed |
| **Documentation** | All operational docs ready | Critical docs ready |

### 10.3 Commerce Readiness Checklist

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                   COMMERCE GO-LIVE READINESS CHECKLIST                       │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  FUNCTIONAL TESTING                                                          │
│  □ All P0 B2C test cases passed                                              │
│  □ All P0 B2B test cases passed                                              │
│  □ All P0 Mobile app test cases passed                                       │
│  □ All P0 Subscription test cases passed                                     │
│  □ UAT completed with business sign-off                                      │
│                                                                              │
│  PAYMENT TESTING                                                             │
│  □ bKash integration certified                                               │
│  □ Nagad integration certified                                               │
│  □ Rocket integration certified                                              │
│  □ SSLCommerz integration certified                                          │
│  □ COD flow tested and working                                               │
│  □ Reconciliation process validated                                          │
│                                                                              │
│  SECURITY & COMPLIANCE                                                       │
│  □ PCI DSS compliance validated                                              │
│  □ ASV scan passed                                                           │
│  □ Penetration test completed                                                │
│  □ Security sign-off obtained                                                │
│                                                                              │
│  PERFORMANCE & RELIABILITY                                                   │
│  □ Load testing completed - 1000+ users                                      │
│  □ Stress testing completed                                                  │
│  □ Performance benchmarks achieved                                           │
│  □ Failover testing completed                                                │
│                                                                              │
│  INTEGRATION                                                                 │
│  □ ERP inventory sync validated                                              │
│  □ SMS/Email integration tested                                              │
│  □ All third-party APIs validated                                            │
│                                                                              │
│  OPERATIONAL READINESS                                                       │
│  □ Monitoring and alerting configured                                        │
│  □ Support team trained                                                      │
│  □ Incident response plan documented                                         │
│  □ Rollback plan tested                                                      │
│  □ Data backup and recovery tested                                           │
│                                                                              │
│  BUSINESS APPROVALS                                                          │
│  □ QA Lead sign-off                                                          │
│  □ IT Director sign-off                                                      │
│  □ Business stakeholder sign-off                                             │
│  □ Managing Director go-live approval                                        │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 11. COMPLIANCE TESTING

### 11.1 Bangladesh E-commerce Regulations

Smart Dairy must comply with Bangladesh e-commerce regulations and payment industry standards.

| Regulation | Requirement | Testing Approach |
|------------|-------------|------------------|
| **ICT Act 2006** | Data protection, cyber security | Security audit, policy review |
| **Digital Security Act 2018** | Secure transaction handling | Encryption validation |
| **Bangladesh Bank Guidelines** | MFS (Mobile Financial Services) compliance | Gateway certification |
| **VAT Act 1991** | Proper tax calculation and display | Tax calculation testing |
| **Consumer Rights Protection Act** | Clear pricing, refund policy | UI/UX review |
| **PCI DSS** | Payment card security | Full PCI assessment |

### 11.2 Payment Regulation Testing

| Requirement | Description | Test Method |
|-------------|-------------|-------------|
| **Two-Factor Authentication** | Strong customer authentication for payments | Test all MFS flows |
| **Transaction Limits** | Daily/monthly limits enforced | Boundary testing |
| **KYC Compliance** | Customer identity verification | Registration flow testing |
| **AML Screening** | Anti-money laundering checks | Transaction monitoring test |
| **Dispute Handling** | Chargeback and dispute process | Workflow testing |
| **Settlement Timing** | T+1 settlement compliance | Reconciliation testing |

### 11.3 Tax Compliance Testing

| Test ID | Scenario | Expected Result |
|---------|----------|-----------------|
| TAX-001 | VAT calculation on product | 15% VAT calculated correctly |
| TAX-002 | VAT on delivery charge | Delivery VAT separate |
| TAX-003 | Tax invoice generation | Proper format per NBR |
| TAX-004 | B2B tax treatment | Input tax credit eligible |
| TAX-005 | Export treatment (if applicable) | Zero-rated correctly |

---

## 12. DEFECT MANAGEMENT

### 12.1 Defect Severity Definitions

| Severity | Definition | Examples | Response Time |
|----------|------------|----------|---------------|
| **P0 - Critical** | System unusable, data loss, security breach | Payment not processing, checkout broken | Immediate |
| **P1 - High** | Major feature unusable, significant workaround | Discount not applying, inventory sync failing | 24 hours |
| **P2 - Medium** | Feature partially working, minor workaround | UI glitches, non-critical validation | 72 hours |
| **P3 - Low** | Cosmetic issues, enhancement requests | Spacing issues, color mismatch | Next release |

### 12.2 Critical Payment Bug SLA

Payment-related defects have accelerated SLAs due to revenue impact:

| Severity | Detection to Fix | Fix to Deploy | Total SLA |
|----------|------------------|---------------|-----------|
| **P0 - Critical Payment Bug** | 2 hours | 4 hours | 6 hours |
| **P1 - High Payment Bug** | 8 hours | 16 hours | 24 hours |
| **P0 - Critical Other** | 4 hours | 8 hours | 12 hours |
| **P1 - High Other** | 24 hours | 48 hours | 72 hours |

### 12.3 Defect Management Process

```
┌──────────────┐    ┌──────────────┐    ┌──────────────┐    ┌──────────────┐
│   DEFECT     │───▶│  TRIAGE &    │───▶│   FIX &      │───▶│   VERIFY     │
│  DISCOVERED  │    │  ASSIGNMENT  │    │   TEST       │    │   & CLOSE    │
└──────────────┘    └──────────────┘    └──────────────┘    └──────────────┘
       │                  │                  │                  │
       ▼                  ▼                  ▼                  ▼
┌──────────────┐    ┌──────────────┐    ┌──────────────┐    ┌──────────────┐
• Log in Jira  │    • Daily       │    • Developer   │    • QA verifies │
• Assign to QA │    •   triage    │    •   fixes     │    •   fix       │
• Attach logs  │    • Severity    │    • Unit tests  │    • Regression  │
• Screenshot   │    •   assigned  │    • Code review │    •   test      │
• Repro steps  │    • Owner       │    • Deploy to   │    • Close/      │
               │    •   assigned  │    │   QA         │    │   Reopen     │
└──────────────┘    └──────────────┘    └──────────────┘    └──────────────┘
```

### 12.4 Defect Metrics and Reporting

| Metric | Target | Reporting Frequency |
|--------|--------|---------------------|
| Defect Density | <5 per 100 test cases | Weekly |
| Defect Removal Efficiency | >95% | Weekly |
| Mean Time to Fix (P0) | <6 hours | Daily |
| Mean Time to Fix (P1) | <24 hours | Daily |
| Reopen Rate | <5% | Weekly |
| Test Pass Rate | >95% | Daily |

### 12.5 Payment Bug Escalation Matrix

| Scenario | Escalation Level | Notification | Action |
|----------|------------------|--------------|--------|
| Payment gateway down | P0 | CTO, IT Director, QA Lead | War room activated |
| Multiple payment failures | P0 | IT Director, QA Lead | Immediate investigation |
| Security vulnerability | P0 | CTO, Security Lead | Emergency patch |
| Checkout broken | P1 | QA Lead, Dev Lead | Priority fix queue |
| Payment reconciliation mismatch | P1 | Finance, QA Lead | Immediate audit |

---

## 13. ROLLBACK PLANNING

### 13.1 Production Deployment Strategy

Phase 3 Commerce deployment uses a blue-green deployment strategy to minimize risk.

### 13.2 Rollback Triggers

| Trigger | Threshold | Action |
|---------|-----------|--------|
| Payment Failure Rate | >1% | Immediate rollback |
| Checkout Abandonment | >80% | Evaluate and rollback |
| Error Rate | >5% | Immediate rollback |
| System Response Time | >10s | Evaluate and rollback |
| Customer Complaints | >10 in 1 hour | Evaluate and rollback |
| Security Incident | Any confirmed | Immediate rollback |

### 13.3 Rollback Procedures

| Step | Action | Owner | Time |
|------|--------|-------|------|
| 1 | Stop all traffic to new deployment | DevOps | 2 min |
| 2 | Switch load balancer to previous version | DevOps | 2 min |
| 3 | Verify previous version health | QA Lead | 5 min |
| 4 | Monitor for 30 minutes | DevOps + QA | 30 min |
| 5 | Notify stakeholders | Project Manager | 5 min |
| 6 | Root cause analysis | Technical Lead | Ongoing |

### 13.4 Rollback Testing

| Test ID | Scenario | Frequency | Last Tested |
|---------|----------|-----------|-------------|
| RBT-001 | Full system rollback | Pre-go-live | TBD |
| RBT-002 | Database rollback | Pre-go-live | TBD |
| RBT-003 | Payment gateway rollback | Pre-go-live | TBD |
| RBT-004 | CDN/cache purge | Pre-go-live | TBD |

---

## 14. APPENDICES

### Appendix A: Payment Test Cases

#### A.1 bKash Test Cases

| Test Case ID | Description | Preconditions | Steps | Expected Result |
|--------------|-------------|---------------|-------|-----------------|
| BK-TC-001 | Successful payment | Valid bKash account, sufficient balance | 1. Add product to cart<br>2. Checkout with bKash<br>3. Enter valid wallet<br>4. Enter correct PIN<br>5. Confirm | Payment successful, order confirmed |
| BK-TC-002 | Insufficient balance | Valid bKash account, low balance | 1. Add product to cart<br>2. Checkout with bKash<br>3. Enter wallet number<br>4. Enter PIN | Error: Insufficient balance shown |
| BK-TC-003 | Invalid PIN | Valid bKash account | 1. Checkout with bKash<br>2. Enter valid wallet<br>3. Enter wrong PIN | Error: Invalid PIN, retry allowed |
| BK-TC-004 | Three wrong PINs | Valid bKash account | 1. Enter wrong PIN 3 times | Transaction cancelled, no deduction |
| BK-TC-005 | Timeout handling | Valid bKash account | 1. Initiate payment<br>2. Wait 5 minutes | Session timeout, order pending |
| BK-TC-006 | Cancel payment | Valid bKash account | 1. Initiate bKash payment<br>2. Click cancel | Payment cancelled, return to cart |
| BK-TC-007 | Duplicate transaction | Valid bKash account | 1. Complete payment<br>2. Resubmit same order | Duplicate prevented, single charge |
| BK-TC-008 | Refund processing | Completed order | 1. Process refund from admin<br>2. Verify bKash wallet | Refund credited within SLA |

#### A.2 SSLCommerz Test Cases

| Test Case ID | Description | Preconditions | Steps | Expected Result |
|--------------|-------------|---------------|-------|-----------------|
| SSL-TC-001 | Successful Visa payment | Valid Visa card | 1. Checkout with card<br>2. Enter Visa details<br>3. Complete 3DS | Payment authorized, order confirmed |
| SSL-TC-002 | Successful MasterCard | Valid MasterCard | 1. Checkout with card<br>2. Enter MC details<br>3. Complete 3DS | Payment authorized, order confirmed |
| SSL-TC-003 | Declined card | Card with no funds | 1. Checkout with card<br>2. Enter declined card | Decline message shown |
| SSL-TC-004 | Expired card | Expired card | 1. Checkout with card<br>2. Enter expired date | Expired card error shown |
| SSL-TC-005 | Invalid CVV | Valid card | 1. Enter wrong CVV | CVV validation error |
| SSL-TC-006 | 3DS failure | Valid card | 1. Complete checkout<br>2. Fail 3DS auth | Return to merchant, order pending |
| SSL-TC-007 | EMI selection | EMI-eligible card | 1. Select EMI option<br>2. Choose tenure<br>3. Complete payment | EMI plan applied correctly |

### Appendix B: Test Environment Details

| Environment | URL | Database | Purpose |
|-------------|-----|----------|---------|
| QA | https://qa.smartdairybd.com | qa_smartdairy | Functional testing |
| UAT | https://uat.smartdairybd.com | uat_smartdairy | User acceptance |
| Pre-Prod | https://staging.smartdairybd.com | staging_smartdairy | Performance testing |
| Production | https://smartdairybd.com | smartdairy | Live environment |

### Appendix C: Payment Gateway Sandbox Details

| Gateway | Sandbox URL | Test Credentials | Contact |
|---------|-------------|------------------|---------|
| bKash | https://sandbox.bka.sh | Provided by bKash | integration@bka.sh |
| Nagad | https://sandbox.nagad.com.bd | Provided by Nagad | support@nagad.com.bd |
| Rocket | https://sandbox.dutchbanglabank.com | Provided by DBBL | support@dutchbanglabank.com |
| SSLCommerz | https://sandbox.sslcommerz.com | Store ID: TEST001 | support@sslcommerz.com |

### Appendix D: Test Data Templates

#### D.1 Customer Test Data

| Field | Test Value | Notes |
|-------|------------|-------|
| Name | Test Customer | Synthetic |
| Email | test.customer{##}@smartdairy.test | Auto-generated |
| Phone | 017XXXXXXXX | Valid Bangladesh format |
| Address | Test Address, Dhaka | Valid address |
| Password | Test@123456 | Meets complexity |

#### D.2 B2B Partner Test Data

| Field | Test Value | Notes |
|-------|------------|-------|
| Company | Test Retailer {##} | Synthetic |
| Trade License | TRADEXXXXXXXXX | Valid format |
| TIN | 123456789012 | Valid format |
| Tier | Retailer/HORECA/etc. | Varies by test |
| Credit Limit | 50,000 - 500,000 | Tier-based |

### Appendix E: Abbreviations and Definitions

| Abbreviation | Definition |
|--------------|------------|
| **B2B** | Business-to-Business |
| **B2C** | Business-to-Consumer |
| **BNPL** | Buy Now Pay Later |
| **COD** | Cash on Delivery |
| **ERP** | Enterprise Resource Planning |
| **HORECA** | Hotels, Restaurants, Cafes |
| **MFS** | Mobile Financial Services |
| **PAN** | Primary Account Number (card number) |
| **PCI DSS** | Payment Card Industry Data Security Standard |
| **RFQ** | Request for Quotation |
| **SIT** | System Integration Testing |
| **SLA** | Service Level Agreement |
| **UAT** | User Acceptance Testing |
| **3DS** | 3D Secure (card authentication) |

### Appendix F: Testing Tools and Licenses

| Tool | Purpose | License | Owner |
|------|---------|---------|-------|
| Jira | Test management, bug tracking | Cloud | Project |
| Confluence | Documentation | Cloud | Project |
| TestRail | Test case management | Cloud | QA |
| JMeter | Load testing | Open Source | QA |
| Selenium | UI automation | Open Source | QA |
| Appium | Mobile automation | Open Source | QA |
| OWASP ZAP | Security testing | Open Source | Security |
| BrowserStack | Cross-browser testing | Subscription | QA |
| Firebase Test Lab | Mobile device testing | Pay-per-use | QA |

### Appendix G: Contact Information

| Role | Name | Email | Phone |
|------|------|-------|-------|
| QA Lead | [TBD] | qa.lead@smartdairybd.com | [TBD] |
| Project Manager | [TBD] | pm@smartdairybd.com | [TBD] |
| IT Director | [TBD] | it.director@smartgroup.com.bd | [TBD] |
| Security Lead | [TBD] | security@smartdairybd.com | [TBD] |
| Payment Gateway Support | [TBD] | payments@smartdairybd.com | [TBD] |

---

## DOCUMENT CONTROL

### Review and Approval

This document requires the following approvals before implementation:

| Version | Reviewer | Role | Date | Status |
|---------|----------|------|------|--------|
| 1.0 | [Name] | QA Lead | [Date] | Draft |
| 1.0 | [Name] | Project Manager | [Date] | Pending Review |
| 1.0 | [Name] | IT Director | [Date] | Pending Review |
| 1.0 | [Name] | Managing Director | [Date] | Pending Review |

### Distribution List

| Recipient | Role | Purpose |
|-----------|------|---------|
| QA Team | Testing | Execute test plan |
| Development Team | Engineering | Understand testing scope |
| Project Management | PMO | Track progress |
| Business Stakeholders | Business | UAT participation |
| IT Operations | DevOps | Environment support |

---

**END OF DOCUMENT**

---

*This document is confidential and proprietary to Smart Dairy Ltd. Unauthorized distribution or reproduction is prohibited.*

**Next Review Date:** March 31, 2026

