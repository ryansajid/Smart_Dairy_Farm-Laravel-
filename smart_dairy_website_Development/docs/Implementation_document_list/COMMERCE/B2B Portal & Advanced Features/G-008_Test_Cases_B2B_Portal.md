# SMART DAIRY LTD.
## TEST CASES - B2B PORTAL
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | G-008 |
| **Version** | 1.0 |
| **Date** | August 1, 2026 |
| **Author** | QA Lead |
| **Owner** | QA Lead |
| **Reviewer** | Dev Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Test Strategy](#2-test-strategy)
3. [Functional Test Cases](#3-functional-test-cases)
4. [Integration Test Cases](#4-integration-test-cases)
5. [Performance Test Cases](#5-performance-test-cases)
6. [Security Test Cases](#6-security-test-cases)
7. [Regression Test Suite](#7-regression-test-suite)
8. [Test Data](#8-test-data)
9. [Appendices](#9-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines comprehensive test cases for the Smart Dairy B2B Portal, covering functional, integration, performance, and security testing scenarios. The B2B Portal serves wholesale customers with bulk ordering, credit management, and multi-user account features.

### 1.2 Test Coverage Summary

| Module | Total Cases | Priority High | Priority Medium | Priority Low |
|--------|-------------|---------------|-----------------|--------------|
| Partner Registration | 25 | 15 | 8 | 2 |
| User Management | 30 | 18 | 10 | 2 |
| Product Catalog | 35 | 20 | 12 | 3 |
| Ordering & Cart | 45 | 28 | 14 | 3 |
| Credit Management | 30 | 20 | 8 | 2 |
| Invoice & Payment | 25 | 15 | 8 | 2 |
| Reports & Analytics | 20 | 10 | 8 | 2 |
| API Integration | 25 | 15 | 8 | 2 |
| **TOTAL** | **235** | **141** | **76** | **18** |

### 1.3 Test Environment

| Component | Specification |
|-----------|--------------|
| **Browser** | Chrome 120+, Firefox 121+, Edge 120+, Safari 17+ |
| **Resolution** | 1920×1080, 1366×768, 390×844 (mobile) |
| **Backend** | Odoo 19 CE (staging) |
| **Database** | PostgreSQL 16 (staging) |
| **Test Users** | 50 B2B accounts with various tiers |

---

## 2. TEST STRATEGY

### 2.1 Test Approach

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                          B2B PORTAL TEST PYRAMID                            │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│                              ┌─────────┐                                     │
│                              │  E2E    │  10% - Selenium/Cypress            │
│                              │  Tests  │     Critical user journeys          │
│                              └────┬────┘                                     │
│                              ┌────┴────┐                                     │
│                              │Integration│ 20% - API tests, Odoo integration│
│                              │  Tests  │     Webhook validation              │
│                              └────┬────┘                                     │
│                         ┌─────────┴─────────┐                                │
│                         │    Unit Tests     │ 70% - Business logic, models   │
│                         │                   │     ACL validation              │
│                         └───────────────────┘                                │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Test Categories

| Category | Description | Tools |
|----------|-------------|-------|
| **Smoke** | Critical path validation | Cypress, Manual |
| **Functional** | Feature verification | Selenium, pytest-odoo |
| **Integration** | API and webhook testing | Postman, pytest |
| **Performance** | Load and stress testing | k6, JMeter |
| **Security** | Vulnerability assessment | OWASP ZAP, Burp Suite |
| **UAT** | Business user validation | Manual testing |

---

## 3. FUNCTIONAL TEST CASES

### 3.1 Partner Registration Module

#### TC-B2B-001: Successful Retailer Registration

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-001 |
| **Module** | Partner Registration |
| **Priority** | High |
| **Type** | Functional |

**Preconditions:**
- User has valid business email
- Trade license document available

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to B2B registration page | Registration form displays |
| 2 | Select "Retailer" as partner type | Tier-specific fields appear |
| 3 | Enter valid business name | No validation error |
| 4 | Enter valid email (retailer@example.com) | Email validation passes |
| 5 | Upload trade license (PDF) | Upload successful, preview shows |
| 6 | Enter valid phone number | Validation passes |
| 7 | Enter business address | Address fields accept input |
| 8 | Click "Submit Registration" | Success message displayed |
| 9 | Check email inbox | Verification email received |
| 10 | Click verification link | Account activated |

**Expected Result:** Account created with "Pending Approval" status
**Status:** ⬜ Not Executed

---

#### TC-B2B-002: Duplicate Email Registration

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-002 |
| **Module** | Partner Registration |
| **Priority** | High |
| **Type** | Functional - Negative |

**Preconditions:**
- Email "existing@example.com" already registered

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to registration | Form displays |
| 2 | Enter email "existing@example.com" | Validation error: "Email already registered" |
| 3 | Try alternative email | Form accepts |

**Expected Result:** Appropriate error message displayed
**Status:** ⬜ Not Executed

---

#### TC-B2B-003: Invalid Trade License Upload

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-003 |
| **Module** | Partner Registration |
| **Priority** | Medium |
| **Type** | Functional - Negative |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to registration | Form displays |
| 2 | Upload file "license.exe" | Error: "Invalid file type" |
| 3 | Upload PDF > 10MB | Error: "File too large, max 10MB" |
| 4 | Upload corrupted PDF | Error: "Invalid file format" |
| 5 | Upload valid PDF | Upload successful |

**Expected Result:** File validation works correctly
**Status:** ⬜ Not Executed

---

### 3.2 User Management Module

#### TC-B2B-010: Primary User Creates Team Member

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-010 |
| **Module** | User Management |
| **Priority** | High |
| **Type** | Functional |

**Preconditions:**
- User logged in as Primary Contact
- Account is Distributor tier

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to "Team" section | Team management page displays |
| 2 | Click "Add Team Member" | Add user form opens |
| 3 | Enter name "John Doe" | Field accepts input |
| 4 | Enter email "john@company.com" | Email validation passes |
| 5 | Select role "Order Manager" | Role-specific permissions shown |
| 6 | Check "Can Place Orders" | Checkbox selected |
| 7 | Click "Send Invitation" | Success message, invitation sent |
| 8 | Check email | Invitation email received |
| 9 | User accepts invitation | Account linked to company |

**Expected Result:** Team member created with appropriate permissions
**Status:** ⬜ Not Executed

---

#### TC-B2B-011: Permission Validation Matrix

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-011 |
| **Module** | User Management |
| **Priority** | High |
| **Type** | Functional |

**Test Matrix:**

| Role | View Products | Place Order | Approve Order | View Invoices | Manage Users |
|------|---------------|-------------|---------------|---------------|--------------|
| **View Only** | ✅ | ❌ | ❌ | ✅ | ❌ |
| **Order Manager** | ✅ | ✅ | ❌ | ✅ | ❌ |
| **Approver** | ✅ | ❌ | ✅ | ✅ | ❌ |
| **Admin** | ✅ | ✅ | ✅ | ✅ | ✅ |

**Test Steps:** For each role combination, verify access control

**Expected Result:** ACL enforced correctly for all roles
**Status:** ⬜ Not Executed

---

#### TC-B2B-012: Approval Hierarchy Configuration

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-012 |
| **Module** | User Management |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login as Admin | Dashboard displays |
| 2 | Navigate to "Approval Settings" | Hierarchy configuration page |
| 3 | Set Level 1: Orders > ৳50,000 | Threshold saved |
| 4 | Assign approver "Manager A" | User selected |
| 5 | Set Level 2: Orders > ৳100,000 | Threshold saved |
| 6 | Assign approver "Director B" | User selected |
| 7 | Save configuration | Settings persisted |
| 8 | Create order ৳75,000 | Routes to Manager A |
| 9 | Create order ৳150,000 | Routes to Director B |

**Expected Result:** Approval routing works per hierarchy
**Status:** ⬜ Not Executed

---

### 3.3 Product Catalog Module

#### TC-B2B-020: Tiered Pricing Display

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-020 |
| **Module** | Product Catalog |
| **Priority** | High |
| **Type** | Functional |

**Test Data:**

| Product | Base Price | Retailer | Distributor | HORECA | Institutional |
|---------|------------|----------|-------------|--------|---------------|
| Fresh Milk 1L | ৳80 | ৳75 | ৳70 | ৳72 | ৳68 |
| Yogurt 500g | ৳60 | ৳55 | ৳50 | ৳52 | ৳48 |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login as Retailer | Dashboard displays |
| 2 | Navigate to "Products" | Product catalog loads |
| 3 | View "Fresh Milk 1L" | Price shows ৳75 (retailer tier) |
| 4 | Logout, login as Distributor | Dashboard displays |
| 5 | View same product | Price shows ৳70 (distributor tier) |
| 6 | Verify tier column hidden | Only own price visible |

**Expected Result:** Correct tier pricing displayed per user type
**Status:** ⬜ Not Executed

---

#### TC-B2B-021: Bulk Pricing Calculation

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-021 |
| **Module** | Product Catalog |
| **Priority** | High |
| **Type** | Functional |

**Test Data:**

| Product | Unit Price | MOQ | Tier Discount | Bulk Discount |
|---------|------------|-----|---------------|---------------|
| Milk 1L | ৳70 | 50 units | 5% | 10% > 200 units |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login as Distributor | Dashboard displays |
| 2 | Add 30 units to cart | Warning: "MOQ is 50 units" |
| 3 | Change to 50 units | Cart shows: 50 × ৳70 = ৳3,500 |
| 4 | Change to 150 units | Discount applied: ৳9,975 (5% off) |
| 5 | Change to 250 units | Bulk discount: ৳15,750 (10% off) |
| 6 | Verify total calculation | 250 × ৳70 × 0.9 = ৳15,750 |

**Expected Result:** Tier and bulk discounts calculated correctly
**Status:** ⬜ Not Executed

---

#### TC-B2B-022: Stock Availability Display

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-022 |
| **Module** | Product Catalog |
| **Priority** | High |
| **Type** | Functional |

**Test Data:**

| Product | Stock Qty | Reserved Qty | Available Qty |
|---------|-----------|--------------|---------------|
| Fresh Milk 1L | 1,000 | 200 | 800 |
| Yogurt 500g | 0 | 0 | 0 |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to Products | Catalog displays |
| 2 | View Fresh Milk 1L | Shows "800 units available" |
| 3 | View Yogurt 500g | Shows "Out of Stock" badge |
| 4 | Try add 1,000 units Milk | Error: "Only 800 available" |
| 5 | Try add Yogurt | Button disabled, "Notify Me" shown |

**Expected Result:** Stock levels accurately displayed and enforced
**Status:** ⬜ Not Executed

---

### 3.4 Ordering & Cart Module

#### TC-B2B-030: CSV Bulk Order Upload

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-030 |
| **Module** | Ordering |
| **Priority** | High |
| **Type** | Functional |

**Test Data (CSV):**
```csv
product_code,quantity
MILK-001,100
YOGR-002,50
CHEE-003,25
```

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to "Bulk Order" | Upload page displays |
| 2 | Click "Download Template" | CSV template downloaded |
| 3 | Upload valid CSV file | File accepted, preview shown |
| 4 | Verify product mapping | Each code matched to product |
| 5 | Verify quantity validation | Stock check performed |
| 6 | Click "Add to Cart" | Items added successfully |
| 7 | Verify cart totals | Correct quantities and prices |

**Expected Result:** CSV upload processed correctly
**Status:** ⬜ Not Executed

---

#### TC-B2B-031: CSV Upload Error Handling

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-031 |
| **Module** | Ordering |
| **Priority** | Medium |
| **Type** | Functional - Negative |

**Test Cases:**

| Scenario | Input | Expected Result |
|----------|-------|-----------------|
| Invalid product code | ABC-999 | Error: "Product not found" |
| Invalid quantity | -5 | Error: "Invalid quantity" |
| Missing column | No quantity | Error: "Missing required columns" |
| Exceeds stock | 10,000 units | Error: "Exceeds available stock" |
| Duplicate rows | Same product twice | Warning: "Duplicate entries merged" |
| File too large | > 5MB | Error: "File too large, max 5MB" |

**Expected Result:** Appropriate error messages for each scenario
**Status:** ⬜ Not Executed

---

#### TC-B2B-032: Standing Order Creation

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-032 |
| **Module** | Ordering |
| **Priority** | Medium |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to "Standing Orders" | List page displays |
| 2 | Click "Create Standing Order" | Form opens |
| 3 | Select products | Products added |
| 4 | Set frequency "Weekly" | Frequency options displayed |
| 5 | Select delivery day "Monday" | Day saved |
| 6 | Set start date | Calendar picker works |
| 7 | Enable "Auto-approve" | Toggle activated |
| 8 | Save standing order | Success message, order created |
| 9 | Verify first order generated | Order appears in "Pending" |
| 10 | Simulate next Monday | New order auto-generated |

**Expected Result:** Standing order creates recurring orders automatically
**Status:** ⬜ Not Executed

---

#### TC-B2B-033: Order Approval Workflow

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-033 |
| **Module** | Ordering |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login as Order Manager | Dashboard displays |
| 2 | Create order ৳75,000 | Order submitted |
| 3 | Verify status "Pending Approval" | Correct status shown |
| 4 | Check approver notification | Email/notification sent |
| 5 | Login as Approver | Dashboard displays |
| 6 | View "Pending Approvals" | Order listed |
| 7 | Click "Review Order" | Order details displayed |
| 8 | Click "Approve" | Order status: "Approved" |
| 9 | Verify Order Manager notified | Notification received |
| 10 | Verify order proceeds to processing | Status updated |

**Expected Result:** Approval workflow executes correctly
**Status:** ⬜ Not Executed

---

#### TC-B2B-034: Order Rejection with Comments

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-034 |
| **Module** | Ordering |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login as Approver | Dashboard displays |
| 2 | View pending order | Order details displayed |
| 3 | Click "Reject" | Rejection dialog opens |
| 4 | Try submit without comment | Validation: "Comment required" |
| 5 | Enter rejection reason | Text accepted |
| 6 | Submit rejection | Order rejected |
| 7 | Verify Order Manager notification | Email with reason received |
| 8 | Check order status | Status: "Rejected" |
| 9 | Order Manager edits and resubmits | New approval cycle starts |

**Expected Result:** Rejection workflow with comments works
**Status:** ⬜ Not Executed

---

### 3.5 Credit Management Module

#### TC-B2B-040: Credit Limit Enforcement

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-040 |
| **Module** | Credit Management |
| **Priority** | High |
| **Type** | Functional |

**Test Data:**

| Customer | Credit Limit | Credit Used | Available |
|----------|--------------|-------------|-----------|
| Retailer A | ৳100,000 | ৳45,000 | ৳55,000 |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login as Retailer A | Dashboard shows credit: ৳55,000 available |
| 2 | Create order ৳50,000 | Order accepted (within limit) |
| 3 | Create order ৳10,000 | Error: "Exceeds credit limit" |
| 4 | Reduce order to ৳5,000 | Order accepted |
| 5 | Verify credit calculation | Used: ৳50,000, Available: ৳50,000 |
| 6 | Complete payment for ৳50,000 order | Credit released |
| 7 | Verify updated credit | Available back to ৳100,000 |

**Expected Result:** Credit limit enforced correctly
**Status:** ⬜ Not Executed

---

#### TC-B2B-041: Credit Aging Report

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-041 |
| **Module** | Credit Management |
| **Priority** | High |
| **Type** | Functional |

**Test Data:**

| Invoice | Amount | Due Date | Days Overdue |
|---------|--------|----------|--------------|
| INV-001 | ৳25,000 | 15 days ago | 15 |
| INV-002 | ৳20,000 | 5 days ago | 5 |
| INV-003 | ৳15,000 | Tomorrow | 0 |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to "Aging Report" | Report page displays |
| 2 | Verify aging buckets | 0-30, 31-60, 61-90, 90+ days |
| 3 | Check INV-001 placement | In "16-30 days" bucket |
| 4 | Check INV-002 placement | In "1-15 days" bucket |
| 5 | Check INV-003 placement | In "Current" bucket |
| 6 | Verify totals | Sum correct per bucket |
| 7 | Export to Excel | File downloaded |
| 8 | Verify Excel formatting | Correct structure |

**Expected Result:** Aging report accurate and exportable
**Status:** ⬜ Not Executed

---

#### TC-B2B-042: Credit Hold Auto-Trigger

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-042 |
| **Module** | Credit Management |
| **Priority** | High |
| **Type** | Functional |

**Test Data:**

| Customer | Credit Limit | Overdue Amount | Days Overdue |
|----------|--------------|----------------|--------------|
| Retailer B | ৳50,000 | ৳30,000 | 45 |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Verify credit hold threshold | Set to 30 days |
| 2 | Login as Retailer B | Dashboard displays |
| 3 | Try place new order | Error: "Account on credit hold" |
| 4 | View notification | Message: "Pay overdue invoices to continue" |
| 5 | Make partial payment (৳20,000) | Payment processed |
| 6 | Try place order again | Still blocked (৳10,000 overdue > 30 days) |
| 7 | Pay remaining ৳10,000 | Payment processed |
| 8 | Try place order | Order accepted |

**Expected Result:** Credit hold enforced and released correctly
**Status:** ⬜ Not Executed

---

### 3.6 Invoice & Payment Module

#### TC-B2B-050: Invoice View and Download

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-050 |
| **Module** | Invoice & Payment |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to "Invoices" | Invoice list displays |
| 2 | Verify list sorting | Sorted by date (newest first) |
| 3 | Filter by status "Unpaid" | Only unpaid invoices shown |
| 4 | Click invoice number | Invoice details displayed |
| 5 | Verify line items | Match order details |
| 6 | Click "Download PDF" | PDF downloaded |
| 7 | Verify PDF content | Correct formatting, all details |
| 8 | Click "Print" | Print dialog opens |

**Expected Result:** Invoice management functions correctly
**Status:** ⬜ Not Executed

---

#### TC-B2B-051: Online Payment Integration

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-051 |
| **Module** | Invoice & Payment |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | View unpaid invoice | Invoice details displayed |
| 2 | Click "Pay Now" | Payment options shown |
| 3 | Select "bKash" | bKash flow initiated |
| 4 | Enter bKash number | Validation passes |
| 5 | Complete payment | Redirected back to portal |
| 6 | Verify success message | "Payment successful" shown |
| 7 | Check invoice status | Status: "Paid" |
| 8 | Verify receipt generated | Receipt available for download |
| 9 | Check email | Payment confirmation received |

**Expected Result:** Payment processed successfully
**Status:** ⬜ Not Executed

---

### 3.7 Reports & Analytics Module

#### TC-B2B-060: Purchase History Report

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-060 |
| **Module** | Reports |
| **Priority** | Medium |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to "Reports" | Reports dashboard |
| 2 | Select "Purchase History" | Report form opens |
| 3 | Set date range (Jan 2026 - Mar 2026) | Range accepted |
| 4 | Click "Generate" | Loading indicator |
| 5 | View report results | Data accurate |
| 6 | Verify summary metrics | Total orders, total amount correct |
| 7 | View trend chart | Chart renders |
| 8 | Export to PDF | Download successful |

**Expected Result:** Report generates correctly
**Status:** ⬜ Not Executed

---

## 4. INTEGRATION TEST CASES

### 4.1 API Integration

#### TC-B2B-100: Partner API Authentication

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-100 |
| **Module** | API Integration |
| **Priority** | High |
| **Type** | Integration |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Request API key via portal | Key generated |
| 2 | Call API without auth header | HTTP 401 Unauthorized |
| 3 | Call API with invalid key | HTTP 401 Unauthorized |
| 4 | Call API with valid key | HTTP 200 OK |
| 5 | Verify rate limiting | 429 after limit exceeded |
| 6 | Test token refresh | New token issued |

**Expected Result:** API authentication secure and functional
**Status:** ⬜ Not Executed

---

#### TC-B2B-101: Order API CRUD Operations

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-101 |
| **Module** | API Integration |
| **Priority** | High |
| **Type** | Integration |

**API Endpoints:**

| Method | Endpoint | Test |
|--------|----------|------|
| POST | /api/v1/orders | Create order |
| GET | /api/v1/orders | List orders |
| GET | /api/v1/orders/{id} | Get order details |
| PUT | /api/v1/orders/{id} | Update order |
| DELETE | /api/v1/orders/{id} | Cancel order |

**Test Steps:** For each endpoint, verify request/response format and data integrity

**Expected Result:** All CRUD operations work correctly
**Status:** ⬜ Not Executed

---

#### TC-B2B-102: Webhook Delivery

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-102 |
| **Module** | API Integration |
| **Priority** | High |
| **Type** | Integration |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Configure webhook URL | Webhook endpoint saved |
| 2 | Place order | Webhook payload delivered |
| 3 | Verify payload format | JSON schema valid |
| 4 | Simulate webhook failure | Retry attempts made |
| 5 | Verify retry logic | Exponential backoff |
| 6 | Check webhook logs | All events logged |

**Expected Result:** Webhook delivery reliable
**Status:** ⬜ Not Executed

---

### 4.2 Odoo ERP Integration

#### TC-B2B-110: Order Sync to Odoo

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-110 |
| **Module** | ERP Integration |
| **Priority** | High |
| **Type** | Integration |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Create order in B2B portal | Order created |
| 2 | Verify Odoo sync | Sales order created in Odoo |
| 3 | Check customer mapping | Correct partner linked |
| 4 | Verify product mapping | Products mapped correctly |
| 5 | Check pricing sync | Prices match |
| 6 | Process order in Odoo | State changes sync back |
| 7 | Verify status updates | Portal reflects changes |

**Expected Result:** Bidirectional sync works correctly
**Status:** ⬜ Not Executed

---

## 5. PERFORMANCE TEST CASES

### 5.1 Load Testing

#### TC-B2B-200: Concurrent User Login

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-200 |
| **Module** | Performance |
| **Priority** | High |
| **Type** | Load |

**Test Parameters:**

| Metric | Target |
|--------|--------|
| Concurrent Users | 500 |
| Ramp-up Time | 5 minutes |
| Duration | 30 minutes |
| Login Response Time | < 3 seconds |
| Error Rate | < 1% |

**Expected Result:** System handles load without degradation
**Status:** ⬜ Not Executed

---

#### TC-B2B-201: Order Processing Throughput

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-201 |
| **Module** | Performance |
| **Priority** | High |
| **Type** | Load |

**Test Parameters:**

| Metric | Target |
|--------|--------|
| Orders/Minute | 100 |
| Order Response Time | < 5 seconds |
| Database CPU | < 70% |
| Application CPU | < 70% |

**Expected Result:** Order processing meets throughput target
**Status:** ⬜ Not Executed

---

## 6. SECURITY TEST CASES

### 6.1 Authentication & Authorization

#### TC-B2B-300: Session Security

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-300 |
| **Module** | Security |
| **Priority** | High |
| **Type** | Security |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login successfully | Session created |
| 2 | Check session timeout | Inactive after 30 min |
| 3 | Verify secure cookie | HttpOnly, Secure flags |
| 4 | Test session fixation | New session ID issued |
| 5 | Test concurrent login | Previous session invalidated |
| 6 | Test logout | Session destroyed |

**Expected Result:** Session management secure
**Status:** ⬜ Not Executed

---

#### TC-B2B-301: SQL Injection Prevention

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-301 |
| **Module** | Security |
| **Priority** | High |
| **Type** | Security |

**Test Inputs:**

| Input Field | Malicious Input | Expected Result |
|-------------|-----------------|-----------------|
| Search | `' OR '1'='1` | No SQL error, no data leak |
| Email | `admin'; DROP TABLE users;--` | Input sanitized |
| Order ID | `999 UNION SELECT * FROM passwords` | No unauthorized data |

**Expected Result:** All SQL injection attempts blocked
**Status:** ⬜ Not Executed

---

#### TC-B2B-302: XSS Prevention

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-302 |
| **Module** | Security |
| **Priority** | High |
| **Type** | Security |

**Test Inputs:**

| Field | Malicious Input | Expected Result |
|-------|-----------------|-----------------|
| Company Name | `<script>alert('XSS')</script>` | Displayed as text, not executed |
| Address | `<img src=x onerror=alert('XSS')>` | Image tag sanitized |
| Search | `javascript:alert('XSS')` | URL encoded |

**Expected Result:** XSS attacks prevented
**Status:** ⬜ Not Executed

---

### 6.2 Access Control

#### TC-B2B-310: URL Authorization Bypass

| Field | Value |
|-------|-------|
| **Test ID** | TC-B2B-310 |
| **Module** | Security |
| **Priority** | High |
| **Type** | Security |

**Test Scenarios:**

| User Role | Attempted Access | Expected Result |
|-----------|-----------------|-----------------|
| View Only | POST /api/orders | HTTP 403 Forbidden |
| Order Manager | /admin/users | HTTP 403 Forbidden |
| User A | /orders?customer_id=456 | Only own orders shown |
| Logged Out | /dashboard | Redirect to login |

**Expected Result:** Access control enforced
**Status:** ⬜ Not Executed

---

## 7. REGRESSION TEST SUITE

### 7.1 Smoke Test Checklist

| # | Test | Status |
|---|------|--------|
| 1 | Homepage loads | ⬜ |
| 2 | Login works | ⬜ |
| 3 | Product catalog displays | ⬜ |
| 4 | Add to cart works | ⬜ |
| 5 | Checkout completes | ⬜ |
| 6 | Invoice generates | ⬜ |
| 7 | Payment processes | ⬜ |
| 8 | Logout works | ⬜ |

---

## 8. TEST DATA

### 8.1 Test Users

| Username | Password | Role | Tier | Credit Limit |
|----------|----------|------|------|--------------|
| retailer@test.com | TestPass123! | Primary | Retailer | ৳50,000 |
| distributor@test.com | TestPass123! | Primary | Distributor | ৳200,000 |
| horeca@test.com | TestPass123! | Primary | HORECA | ৳100,000 |
| institutional@test.com | TestPass123! | Primary | Institutional | ৳500,000 |
| order.manager@test.com | TestPass123! | Order Manager | - | - |
| approver@test.com | TestPass123! | Approver | - | - |

### 8.2 Test Products

| SKU | Name | Unit Price | Stock |
|-----|------|------------|-------|
| MILK-001 | Fresh Milk 1L | ৳80 | 1,000 |
| YOGR-002 | Yogurt 500g | ৳60 | 500 |
| CHEE-003 | Cheese Block 200g | ৳120 | 200 |
| BUTR-004 | Butter 100g | ৳45 | 300 |
| GHEE-005 | Pure Ghee 500ml | ৳350 | 150 |

---

## 9. APPENDICES

### Appendix A: Test Execution Log

| Test ID | Date | Tester | Result | Notes |
|---------|------|--------|--------|-------|
| | | | | |

### Appendix B: Defect Log

| ID | Test ID | Severity | Description | Status |
|----|---------|----------|-------------|--------|
| | | | | |

### Appendix C: Test Environment Details

```yaml
Application Server: Odoo 19 CE
Database: PostgreSQL 16
Cache: Redis 7
Web Server: Nginx 1.24
OS: Ubuntu 22.04 LTS
Browser Versions:
  - Chrome: 120.0.x
  - Firefox: 121.0.x
  - Edge: 120.0.x
  - Safari: 17.1.x
```

---

**END OF TEST CASES - B2B PORTAL**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | August 1, 2026 | QA Lead | Initial version |
