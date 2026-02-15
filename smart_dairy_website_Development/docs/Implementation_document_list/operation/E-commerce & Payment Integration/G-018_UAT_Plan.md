# SMART DAIRY LTD.
## USER ACCEPTANCE TESTING (UAT) PLAN
### Smart Web Portal System & Integrated ERP - Phase 2

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | G-018 |
| **Version** | 1.0 |
| **Date** | May 23, 2026 |
| **Author** | QA Lead |
| **Owner** | QA Lead |
| **Reviewer** | Project Manager |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [UAT Scope](#2-uat-scope)
3. [Test Environment](#3-test-environment)
4. [Test Participants](#4-test-participants)
5. [Test Schedule](#5-test-schedule)
6. [Test Scenarios](#6-test-scenarios)
7. [Acceptance Criteria](#7-acceptance-criteria)
8. [Defect Management](#8-defect-management)
9. [Sign-off Process](#9-sign-off-process)
10. [Risks & Mitigation](#10-risks--mitigation)
11. [Appendices](#11-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This User Acceptance Testing (UAT) Plan defines the comprehensive testing strategy for the Smart Dairy Phase 2 B2C E-commerce Portal launch. UAT validates that the system meets business requirements and is ready for production deployment.

### 1.2 UAT Objectives

| # | Objective | Success Criteria |
|---|-----------|------------------|
| 1 | Validate B2C portal functionality | 100% critical test cases pass |
| 2 | Verify payment gateway integration | All payment methods successful |
| 3 | Test subscription management | End-to-end flow validated |
| 4 | Confirm performance under load | < 3s page load time |
| 5 | Validate mobile responsiveness | Works on target devices |
| 6 | Verify Bengali localization | All critical screens in Bengali |

---

## 2. UAT SCOPE

### 2.1 In Scope

| Module | Features |
|--------|----------|
| **Customer Portal** | Registration, login, profile management |
| **Product Catalog** | Browse, filter, search products |
| **Shopping Cart** | Add/remove items, quantity update |
| **Checkout** | Address, delivery slot, payment selection |
| **Payments** | bKash, Nagad, Rocket, COD |
| **Subscriptions** | Create, modify, pause, cancel |
| **Order Management** | Order history, tracking, reorder |
| **Notifications** | SMS, email, push notifications |
| **Admin Panel** | Order processing, delivery management |

### 2.2 Out of Scope

| Feature | Reason |
|---------|--------|
| B2B portal | Phase 3 feature |
| Advanced analytics | Post-launch enhancement |
| Loyalty program | Phase 3 feature |
| International payments | Not applicable |

---

## 3. TEST ENVIRONMENT

### 3.1 Environment Details

| Parameter | Value |
|-----------|-------|
| **URL** | https://uat.smartdairybd.com |
| **API Base** | https://api-uat.smartdairybd.com |
| **Database** | PostgreSQL 16 (UAT instance) |
| **Cache** | Redis (UAT instance) |
| **CDN** | CloudFlare (UAT zone) |

### 3.2 Test Data

| Data Type | Quantity | Notes |
|-----------|----------|-------|
| Test Customers | 500 | Various personas |
| Products | 25 | All SKUs in catalog |
| Test Orders | 2,000 | Historical data |
| Payment Methods | 4 | bKash, Nagad, Rocket, COD |
| Delivery Zones | 15 | Dhaka coverage areas |

### 3.3 Test Accounts

| Account Type | Count | Purpose |
|--------------|-------|---------|
| **Customer Accounts** | 50 | End-user testing |
| **Admin Accounts** | 10 | Admin panel testing |
| **Delivery Accounts** | 15 | Delivery app testing |
| **Payment Test Wallets** | 20 | Payment gateway testing |

---

## 4. TEST PARTICIPANTS

### 4.1 UAT Team Structure

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         UAT ORGANIZATION                                     │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│                          ┌──────────────┐                                   │
│                          │  UAT Lead    │                                   │
│                          │  (QA Lead)   │                                   │
│                          └──────┬───────┘                                   │
│                                 │                                            │
│            ┌────────────────────┼────────────────────┐                      │
│            │                    │                    │                      │
│    ┌───────▼────────┐  ┌───────▼────────┐  ┌───────▼────────┐             │
│    │  Business      │  │  Technical     │  │  End User      │             │
│    │  Testers       │  │  Testers       │  │  Testers       │             │
│    │                │  │                │  │                │             │
│    │ • Product      │  │ • Dev Team     │  │ • Beta         │             │
│    │   Manager      │  │ • QA Team      │  │   Customers    │             │
│    │ • Sales Lead   │  │ • DevOps       │  │ • Farm Staff   │             │
│    │ • Marketing    │  │ • Security     │  │                │             │
│    └────────────────┘  └────────────────┘  └────────────────┘             │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 4.2 Participant Responsibilities

| Role | Participants | Responsibilities |
|------|--------------|------------------|
| **UAT Lead** | QA Lead | Overall coordination, defect triage, reporting |
| **Business Analysts** | Product Manager, Sales Lead | Validate business requirements |
| **Functional Testers** | QA Team | Execute test cases, document defects |
| **Technical Testers** | Dev Team | API testing, performance testing |
| **End Users** | 20 Beta Customers | Real-world usage feedback |
| **UAT Support** | Dev Team | Bug fixes, environment support |

---

## 5. TEST SCHEDULE

### 5.1 UAT Timeline

| Phase | Start Date | End Date | Duration | Activities |
|-------|------------|----------|----------|------------|
| **Preparation** | May 20 | May 23 | 4 days | Environment setup, test data creation |
| **Round 1** | May 24 | May 28 | 5 days | Functional testing |
| **Bug Fix** | May 29 | May 31 | 3 days | Critical bug fixes |
| **Round 2** | Jun 1 | Jun 3 | 3 days | Regression testing |
| **Performance** | Jun 4 | Jun 5 | 2 days | Load testing |
| **Sign-off** | Jun 6 | Jun 8 | 3 days | Final validation, documentation |
| **Total** | | | 20 days | |

### 5.2 Daily Schedule

| Time | Activity | Participants |
|------|----------|--------------|
| 9:00 - 9:30 | Daily Standup | All |
| 9:30 - 12:30 | Test Execution | All Testers |
| 12:30 - 13:30 | Lunch Break | |
| 13:30 - 16:30 | Test Execution | All Testers |
| 16:30 - 17:00 | Bug Triage | UAT Lead + Devs |
| 17:00 - 17:30 | Daily Report | UAT Lead |

---

## 6. TEST SCENARIOS

### 6.1 Test Case Categories

| Category | Test Cases | Priority |
|----------|------------|----------|
| **Customer Registration** | 15 | High |
| **Product Browsing** | 20 | High |
| **Shopping Cart** | 25 | High |
| **Checkout Flow** | 30 | Critical |
| **Payment Processing** | 40 | Critical |
| **Subscription Management** | 35 | Critical |
| **Order Management** | 25 | High |
| **Admin Operations** | 30 | High |
| **Mobile Experience** | 20 | High |
| **Bengali Language** | 15 | High |
| **Negative Scenarios** | 25 | Medium |

### 6.2 Critical Test Scenarios

#### TC-CHECKOUT-001: Complete Purchase with bKash

| # | Step | Expected Result |
|---|------|-----------------|
| 1 | Register new customer account | Account created, confirmation email sent |
| 2 | Add products to cart | Cart shows correct items and total |
| 3 | Proceed to checkout | Checkout page loads with cart summary |
| 4 | Enter delivery address | Address saved and validated |
| 5 | Select delivery slot | Available slots shown, selection confirmed |
| 6 | Select bKash payment | bKash payment option selected |
| 7 | Enter bKash number | Number validated (format check) |
| 8 | Confirm payment | bKash OTP sent to phone |
| 9 | Enter OTP | Payment processed, success message shown |
| 10 | Verify order confirmation | Order ID generated, confirmation email sent |
| 11 | Check order in My Orders | Order appears with "Processing" status |

**Acceptance Criteria:**
- [ ] Order completed within 5 minutes
- [ ] Payment confirmation received within 30 seconds
- [ ] Order confirmation email received within 2 minutes
- [ ] Order visible in customer dashboard

#### TC-SUB-001: Create Daily Milk Subscription

| # | Step | Expected Result |
|---|------|-----------------|
| 1 | Navigate to Subscriptions page | Subscription plans displayed |
| 2 | Select "Daily Fresh 1L" plan | Plan details shown with pricing |
| 3 | Click Subscribe | Subscription creation wizard opens |
| 4 | Select delivery address | Address selected from saved addresses |
| 5 | Select time slot (6:00-8:00 AM) | Slot availability confirmed |
| 6 | Choose billing cycle (Monthly) | Price updated: ৳1,800/month |
| 7 | Select payment method (bKash) | Payment method selected |
| 8 | Review and confirm | Subscription summary displayed |
| 9 | Confirm subscription | First invoice generated, payment processed |
| 10 | Check subscription dashboard | Active subscription shown with next 7 deliveries |

**Acceptance Criteria:**
- [ ] Subscription created successfully
- [ ] First payment processed
- [ ] Delivery schedule generated for 30 days
- [ ] Confirmation SMS/email received

#### TC-PAY-001: Failed Payment Recovery

| # | Step | Expected Result |
|---|------|-----------------|
| 1 | Initiate checkout | Checkout page loaded |
| 2 | Select Nagad payment | Nagad option selected |
| 3 | Enter valid Nagad number | Number accepted |
| 4 | Enter incorrect OTP | Payment fails with error message |
| 5 | Retry with correct OTP | Payment successful |
| 6 | Verify order status | Order marked as paid |

**Acceptance Criteria:**
- [ ] Clear error message for failed payment
- [ ] Retry option available
- [ ] No duplicate order created

#### TC-SUB-002: Pause and Resume Subscription

| # | Step | Expected Result |
|---|------|-----------------|
| 1 | Navigate to active subscription | Subscription details displayed |
| 2 | Click Pause Subscription | Pause dialog opens |
| 3 | Select pause dates (Feb 1-7) | Date range accepted |
| 4 | Enter pause reason (Vacation) | Reason saved |
| 5 | Confirm pause | Subscription paused, affected deliveries cancelled |
| 6 | Check delivery calendar | No deliveries scheduled for Feb 1-7 |
| 7 | Click Resume Subscription | Confirmation dialog shown |
| 8 | Confirm resume | Subscription active, deliveries resume |

**Acceptance Criteria:**
- [ ] Pause takes effect immediately
- [ ] Affected deliveries cancelled/notified
- [ ] Resume restores normal schedule
- [ ] Pause history recorded

#### TC-PERF-001: Concurrent User Load Test

| # | Step | Expected Result |
|---|------|-----------------|
| 1 | Generate 1000 concurrent users | Load test initiated |
| 2 | Users browse catalog simultaneously | All users see catalog within 3 seconds |
| 3 | 500 users add to cart simultaneously | All operations successful |
| 4 | 200 users checkout simultaneously | Checkouts processed without errors |
| 5 | Monitor response times | Average < 2 seconds, 95th percentile < 5 seconds |
| 6 | Monitor error rate | Error rate < 1% |

**Acceptance Criteria:**
- [ ] System handles 1000 concurrent users
- [ ] Response times within SLA
- [ ] No critical errors
- [ ] Database connections stable

---

## 7. ACCEPTANCE CRITERIA

### 7.1 Entry Criteria

| # | Criteria | Status |
|---|----------|--------|
| 1 | System Integration Testing completed | ☐ |
| 2 | All critical bugs from SIT fixed | ☐ |
| 3 | UAT environment ready | ☐ |
| 4 | Test data prepared | ☐ |
| 5 | Test cases reviewed and approved | ☐ |
| 6 | UAT team trained | ☐ |

### 7.2 Exit Criteria

| # | Criteria | Threshold |
|---|----------|-----------|
| 1 | Critical test cases passed | 100% |
| 2 | High priority test cases passed | ≥ 95% |
| 3 | Medium priority test cases passed | ≥ 90% |
| 4 | Critical defects resolved | 100% |
| 5 | High defects resolved | ≥ 95% |
| 6 | Performance requirements met | 100% |
| 7 | Security scan passed | No critical issues |

---

## 8. DEFECT MANAGEMENT

### 8.1 Defect Severity

| Severity | Definition | Response Time | Resolution Target |
|----------|------------|---------------|-------------------|
| **Critical** | System crash, data loss, security breach | 2 hours | 24 hours |
| **High** | Major functionality broken, workaround difficult | 4 hours | 48 hours |
| **Medium** | Feature not working as expected, workaround exists | 8 hours | 72 hours |
| **Low** | Cosmetic issues, typos, minor UI issues | 24 hours | 1 week |

### 8.2 Defect Workflow

```
┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐
│  New     │────►│  Triage  │────►│ Assigned │────►│ In Fix   │
└──────────┘     └──────────┘     └──────────┘     └────┬─────┘
                                                        │
    ┌───────────────────────────────────────────────────┘
    │
    ▼
┌──────────┐     ┌──────────┐     ┌──────────┐
│  Fixed   │────►│  Verify  │────►│  Closed  │
└──────────┘     └──────────┘     └──────────┘
                      │
                      ▼
                ┌──────────┐
                │ Rejected │────► Back to Triage
                └──────────┘
```

### 8.3 Defect Template

| Field | Description |
|-------|-------------|
| **ID** | SD-UAT-XXX |
| **Title** | Brief description |
| **Severity** | Critical/High/Medium/Low |
| **Priority** | Urgent/High/Normal/Low |
| **Module** | Affected module |
| **Environment** | UAT/Staging |
| **Browser/Device** | Chrome/Mobile/etc |
| **Steps to Reproduce** | Numbered steps |
| **Expected Result** | What should happen |
| **Actual Result** | What actually happened |
| **Attachments** | Screenshots/videos |
| **Reporter** | Name |
| **Date** | Reported date |

---

## 9. SIGN-OFF PROCESS

### 9.1 Sign-off Checklist

| # | Item | Product Manager | Tech Lead | QA Lead |
|---|------|-----------------|-----------|---------|
| 1 | All critical test cases passed | ☐ | ☐ | ☐ |
| 2 | No open critical/high defects | ☐ | ☐ | ☐ |
| 3 | Performance benchmarks met | ☐ | ☐ | ☐ |
| 4 | Security review passed | ☐ | ☐ | ☐ |
| 5 | Business requirements validated | ☐ | N/A | ☐ |
| 6 | User documentation ready | ☐ | ☐ | ☐ |
| 7 | Rollback plan tested | N/A | ☐ | ☐ |
| 8 | Go-live approval obtained | ☐ | ☐ | ☐ |

### 9.2 UAT Sign-off Form

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    UAT SIGN-OFF CERTIFICATE                                  │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Project: Smart Dairy - Phase 2 B2C Portal                                  │
│  UAT Period: May 24 - June 8, 2026                                          │
│  Release: v2.0.0                                                            │
│                                                                              │
│  TEST RESULTS SUMMARY:                                                      │
│  ┌─────────────────┬──────────┬──────────┬──────────┬──────────┐           │
│  │ Category        │ Planned  │ Executed │ Passed   │ Failed   │           │
│  ├─────────────────┼──────────┼──────────┼──────────┼──────────┤           │
│  │ Critical        │    50    │    50    │    50    │     0    │           │
│  │ High            │    80    │    80    │    78    │     2    │           │
│  │ Medium          │   100    │   100    │    95    │     5    │           │
│  │ Low             │    50    │    50    │    50    │     0    │           │
│  └─────────────────┴──────────┴──────────┴──────────┴──────────┘           │
│                                                                              │
│  DEFECT SUMMARY:                                                            │
│  Critical: 0 Open | High: 0 Open | Medium: 2 Open (Accepted) | Low: 5 Open  │
│                                                                              │
│  APPROVAL:                                                                  │
│                                                                              │
│  Product Manager: _________________________ Date: _________                 │
│  Tech Lead:       _________________________ Date: _________                 │
│  QA Lead:         _________________________ Date: _________                 │
│                                                                              │
│  RECOMMENDATION:                                                            │
│  [ ] Approved for Production Deployment                                     │
│  [ ] Approved with Conditions: ______________________________________       │
│  [ ] Not Approved - Requires Additional Testing                             │
│                                                                              │
│  CONDITIONS (if any):                                                       │
│  ______________________________________________________________________     │
│  ______________________________________________________________________     │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 10. RISKS & MITIGATION

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Payment gateway issues in UAT | Medium | High | Use sandbox environments, have test credentials ready |
| Limited tester availability | Medium | Medium | Schedule in advance, have backup testers |
| Critical bugs found late | Low | High | Daily triage, prioritize critical path testing |
| Performance issues | Medium | High | Early load testing, performance monitoring |
| Scope creep | Medium | Medium | Strict change control, document all changes |

---

## 11. APPENDICES

### Appendix A: UAT Test Cases (Sample)

**Detailed test cases available in:** `TestRail / Smart Dairy / Phase 2 UAT`

### Appendix B: Device Matrix

| Device | OS | Browser | Priority |
|--------|----|---------|----------|
| iPhone 14 | iOS 17 | Safari | High |
| Samsung Galaxy S23 | Android 14 | Chrome | High |
| Xiaomi Redmi Note 12 | Android 13 | Chrome | Medium |
| Desktop Windows 11 | - | Chrome, Edge | High |
| Desktop macOS | - | Safari, Chrome | Medium |

### Appendix C: Browser Compatibility

| Browser | Versions | Status |
|---------|----------|--------|
| Chrome | Latest, Latest-1 | Supported |
| Firefox | Latest, Latest-1 | Supported |
| Safari | Latest, Latest-1 | Supported |
| Edge | Latest | Supported |

---

**END OF UAT PLAN**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | May 23, 2026 | QA Lead | Initial version |
