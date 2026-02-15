# SMART DAIRY LTD.
## MOBILE APP TEST CASES - FIELD SALES
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | G-012 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | QA Engineer |
| **Owner** | QA Lead |
| **Reviewer** | Sales Team Lead |
| **Status** | Draft |
| **Related Documents** | A-009 (Mobile UI/UX Specs), B-004 (B2B Technical Spec), H-005 (Offline-First Architecture) |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Test Environment & Setup](#2-test-environment--setup)
3. [Route Planning & Optimization Test Cases](#3-route-planning--optimization-test-cases)
4. [Customer Management Test Cases](#4-customer-management-test-cases)
5. [Order Taking (B2B) Test Cases](#5-order-taking-b2b-test-cases)
6. [Product Catalog & Pricing Test Cases](#6-product-catalog--pricing-test-cases)
7. [Payment Collection Test Cases](#7-payment-collection-test-cases)
8. [Visit Logging Test Cases](#8-visit-logging-test-cases)
9. [GPS Tracking Test Cases](#9-gps-tracking-test-cases)
10. [Offline Mode Test Cases](#10-offline-mode-test-cases)
11. [Signature Capture Test Cases](#11-signature-capture-test-cases)
12. [Dashboard & Reports Test Cases](#12-dashboard--reports-test-cases)
13. [Field Conditions & Device Test Cases](#13-field-conditions--device-test-cases)
14. [Regression Test Suite](#14-regression-test-suite)
15. [Appendices](#15-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines comprehensive test cases for the Smart Dairy Field Sales Mobile Application. The app enables sales representatives to manage B2B customer relationships, take orders in the field, collect payments, and track daily activities while operating in offline mode with periodic synchronization.

### 1.2 Test Coverage Summary

| Module | Total Cases | Priority High | Priority Medium | Priority Low |
|--------|-------------|---------------|-----------------|--------------|
| Route Planning & Optimization | 6 | 4 | 2 | 0 |
| Customer Management | 6 | 4 | 2 | 0 |
| Order Taking (B2B) | 8 | 6 | 2 | 0 |
| Product Catalog & Pricing | 5 | 4 | 1 | 0 |
| Payment Collection | 5 | 4 | 1 | 0 |
| Visit Logging | 4 | 3 | 1 | 0 |
| GPS Tracking | 4 | 3 | 1 | 0 |
| Offline Mode | 8 | 6 | 2 | 0 |
| Signature Capture | 4 | 3 | 1 | 0 |
| Dashboard & Reports | 4 | 2 | 2 | 0 |
| Field Conditions & Device | 6 | 4 | 2 | 0 |
| **TOTAL** | **60** | **43** | **17** | **0** |

### 1.3 Test Environment

| Component | Specification |
|-----------|--------------|
| **Platform** | Android 10+ / iOS 14+ |
| **Devices** | Mid-range Android (6.5", 4GB RAM) / iPhone 12+ |
| **Backend** | Odoo 19 CE (staging) |
| **API** | RESTful API v1 |
| **Database** | PostgreSQL 16 (staging) |
| **Offline Storage** | SQLite |
| **Test Users** | 10 Field Sales Representatives |
| **Test Territory** | Dhaka North (50 test customers) |

### 1.4 Test Data Requirements

| Data Category | Volume | Notes |
|---------------|--------|-------|
| B2B Customers | 50 | Various tiers (Retailer, Distributor, HORECA) |
| Products | 25 | All active dairy products with tier pricing |
| Sales Reps | 10 | With different territory assignments |
| Test Orders | 200 | Historical order data for testing |
| Credit Limits | 50 | Various credit profiles |

---

## 2. TEST ENVIRONMENT & SETUP

### 2.1 Device Matrix

| Device | OS Version | Screen Size | RAM | Test Focus |
|--------|------------|-------------|-----|------------|
| Samsung Galaxy A34 | Android 13 | 6.6" | 6GB | Primary test device |
| Xiaomi Redmi Note 12 | Android 12 | 6.67" | 4GB | Performance testing |
| iPhone 13 | iOS 16 | 6.1" | 4GB | iOS compatibility |
| iPhone SE | iOS 15 | 4.7" | 3GB | Small screen testing |
| Tablet (Samsung Tab A8) | Android 12 | 10.5" | 3GB | Tablet layout testing |

### 2.2 Network Simulation Profiles

| Profile | Download | Upload | Latency | Packet Loss | Use Case |
|---------|----------|--------|---------|-------------|----------|
| Excellent 4G | 20 Mbps | 10 Mbps | 30ms | 0% | Urban areas |
| Good 3G | 5 Mbps | 2 Mbps | 100ms | 1% | Suburban areas |
| Poor Edge | 100 Kbps | 50 Kbps | 500ms | 5% | Rural areas |
| Offline | 0 | 0 | - | - | No connectivity |
| Intermittent | Variable | Variable | 200ms | 10% | Moving vehicle |

---

## 3. ROUTE PLANNING & OPTIMIZATION TEST CASES

### TC-ROUTE-001: Daily Route Generation

| Field | Value |
|-------|-------|
| **Test ID** | TC-ROUTE-001 |
| **Module** | Route Planning |
| **Priority** | High |
| **Type** | Functional |

**Preconditions:**
- Sales rep logged in with valid credentials
- Territory assigned with 10+ customers
- GPS location permission granted

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Open app and navigate to Route tab | Route screen loads with map view |
| 2 | Verify today's date displayed | Correct date shown |
| 3 | Check route customer list | All assigned customers displayed |
| 4 | Verify visit sequence order | Optimized route order shown (1, 2, 3...) |
| 5 | Check estimated travel time | Total duration calculated and displayed |
| 6 | Check total distance | Kilometers shown for entire route |
| 7 | Tap on first customer in list | Customer details and navigation option shown |

**Expected Result:** Route generated with optimized sequence, time, and distance estimates
**Status:** ⬜ Not Executed

---

### TC-ROUTE-002: Route Optimization Algorithm

| Field | Value |
|-------|-------|
| **Test ID** | TC-ROUTE-002 |
| **Module** | Route Planning |
| **Priority** | High |
| **Type** | Functional |

**Preconditions:**
- Multiple customers at different locations
- Current location known via GPS

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Note current GPS position | Location accurately captured |
| 2 | View unoptimized customer list | Random order displayed |
| 3 | Tap "Optimize Route" button | Algorithm processes locations |
| 4 | Verify optimized sequence | Nearest-first or TSP-optimized order |
| 5 | Check time savings | Estimated time reduction shown |
| 6 | Compare with manual calculation | Shortest path validated |
| 7 | Accept optimized route | Route updated and saved |

**Expected Result:** Algorithm produces efficient route minimizing travel time and distance
**Status:** ⬜ Not Executed

---

### TC-ROUTE-003: Route Check-in and Progress Tracking

| Field | Value |
|-------|-------|
| **Test ID** | TC-ROUTE-003 |
| **Module** | Route Planning |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to first customer location | Arrival within 100m of coordinates |
| 2 | Tap "Check In" button | Check-in recorded with timestamp |
| 3 | Verify visit status updated | Customer marked as "In Progress" |
| 4 | Complete visit activities | Order taken and notes added |
| 5 | Tap "Check Out" button | Check-out recorded with duration |
| 6 | Verify progress indicator | Route shows "1 of 8 completed" |
| 7 | Check completion percentage | Progress bar updated |

**Expected Result:** Visit check-in/out tracked accurately with timestamps
**Status:** ⬜ Not Executed

---

### TC-ROUTE-004: Add Unscheduled Visit

| Field | Value |
|-------|-------|
| **Test ID** | TC-ROUTE-004 |
| **Module** | Route Planning |
| **Priority** | Medium |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Tap "+ Add Unscheduled Visit" | Customer search screen opens |
| 2 | Search for customer "Metro Stores" | Search results displayed |
| 3 | Select customer from list | Customer details shown |
| 4 | Tap "Add to Today's Route" | Added to route list |
| 5 | Verify route re-optimization | New customer inserted in optimal position |
| 6 | Check updated time estimate | Additional time calculated |

**Expected Result:** Unscheduled visit added and route re-optimized
**Status:** ⬜ Not Executed

---

### TC-ROUTE-005: Navigation Integration

| Field | Value |
|-------|-------|
| **Test ID** | TC-ROUTE-005 |
| **Module** | Route Planning |
| **Priority** | High |
| **Type** | Integration |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Select next customer in route | Customer card displayed |
| 2 | Tap "Navigate" button | External map app opens (Google Maps) |
| 3 | Verify address passed correctly | Correct destination in navigation |
| 4 | Complete navigation to customer | Arrived at correct location |
| 5 | Return to Field Sales app | App resumes with check-in prompt |

**Expected Result:** Seamless integration with navigation apps
**Status:** ⬜ Not Executed

---

### TC-ROUTE-006: Route History and Analytics

| Field | Value |
|-------|-------|
| **Test ID** | TC-ROUTE-006 |
| **Module** | Route Planning |
| **Priority** | Medium |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to "Route History" | List of previous routes displayed |
| 2 | Select a completed route | Route details shown with map |
| 3 | Verify customers visited | All checked-in customers listed |
| 4 | Check actual vs planned time | Variance shown |
| 5 | View route efficiency metrics | Distance, time, orders per visit |
| 6 | Export route report | PDF report generated |

**Expected Result:** Historical route data accessible with analytics
**Status:** ⬜ Not Executed

---

## 4. CUSTOMER MANAGEMENT TEST CASES

### TC-CUST-001: Customer Search and Filter

| Field | Value |
|-------|-------|
| **Test ID** | TC-CUST-001 |
| **Module** | Customer Management |
| **Priority** | High |
| **Type** | Functional |

**Preconditions:**
- Customer data synced to device
- 50+ customers in territory

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to Customers tab | Customer list loads |
| 2 | Type "Bengal" in search box | Matching customers filtered |
| 3 | Search by customer ID "B2B-2024-001" | Exact match found |
| 4 | Apply filter "Overdue Payment" | Filtered list shows only overdue |
| 5 | Apply filter "Credit Limit > ৳100K" | High-value customers shown |
| 6 | Clear all filters | Full customer list restored |
| 7 | Test search with Bengali text | Bangla search works correctly |

**Expected Result:** Search and filter functions return accurate results
**Status:** ⬜ Not Executed

---

### TC-CUST-002: Customer Profile View

| Field | Value |
|-------|-------|
| **Test ID** | TC-CUST-002 |
| **Module** | Customer Management |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Select customer "Bengal Traders" | Profile screen opens |
| 2 | Verify basic information | Name, address, phone displayed |
| 3 | Check customer tier | Tier badge shown (Distributor/Retailer) |
| 4 | View credit status | Limit, used, available credit shown |
| 5 | Check payment status | Overdue amount and days displayed |
| 6 | View last order summary | Recent order details visible |
| 7 | Access full order history | Historical orders list loaded |
| 8 | View visit notes | Previous visit notes displayed |

**Expected Result:** Complete customer information accessible
**Status:** ⬜ Not Executed

---

### TC-CUST-003: Credit Limit Validation

| Field | Value |
|-------|-------|
| **Test ID** | TC-CUST-003 |
| **Module** | Customer Management |
| **Priority** | High |
| **Type** | Functional |

**Test Data:**

| Customer | Credit Limit | Used | Available |
|----------|--------------|------|-----------|
| Test Customer A | ৳100,000 | ৳85,000 | ৳15,000 |
| Test Customer B | ৳50,000 | ৳50,000 | ৳0 |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Open Customer A profile | Shows ৳15,000 available |
| 2 | Attempt to create order for ৳20,000 | Warning: "Exceeds credit limit" |
| 3 | Modify order to ৳10,000 | Order accepted |
| 4 | Open Customer B profile | Shows "Credit Limit Exhausted" |
| 5 | Attempt any order | Blocked: "Payment required before new order" |
| 6 | Check override option | Supervisor approval option available |

**Expected Result:** Credit limits enforced with clear warnings
**Status:** ⬜ Not Executed

---

### TC-CUST-004: Add New Customer (Onboarding)

| Field | Value |
|-------|-------|
| **Test ID** | TC-CUST-004 |
| **Module** | Customer Management |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Tap "+ New Customer" | Registration form opens |
| 2 | Enter business name "New Mart" | Field accepts input |
| 3 | Select customer type "Retailer" | Tier-specific fields shown |
| 4 | Enter contact details | Phone, email validated |
| 5 | Capture GPS location | Current location auto-filled |
| 6 | Take shop photo | Camera opens, photo captured |
| 7 | Upload trade license | Document uploaded |
| 8 | Submit for approval | "Pending approval" status shown |
| 9 | Verify offline queue | Customer in pending sync queue |

**Expected Result:** New customer registration completed and queued for sync
**Status:** ⬜ Not Executed

---

### TC-CUST-005: Quick Actions from Customer Profile

| Field | Value |
|-------|-------|
| **Test ID** | TC-CUST-005 |
| **Module** | Customer Management |
| **Priority** | Medium |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Open customer profile | Profile screen displayed |
| 2 | Tap "Call" button | Phone dialer opens with number |
| 3 | Return to app | Back to customer profile |
| 4 | Tap "WhatsApp" button | WhatsApp opens with chat |
| 5 | Return to app | Back to customer profile |
| 6 | Tap "Create Order" | Order screen with customer pre-selected |
| 7 | Return and tap "Record Payment" | Payment screen opened |
| 8 | Tap "Add Note" | Note entry dialog opens |

**Expected Result:** Quick actions function correctly
**Status:** ⬜ Not Executed

---

### TC-CUST-006: Customer Data Sync Verification

| Field | Value |
|-------|-------|
| **Test ID** | TC-CUST-006 |
| **Module** | Customer Management |
| **Priority** | High |
| **Type** | Integration |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Check customer data in ERP | Verify master data |
| 2 | Trigger sync in mobile app | Sync initiated |
| 3 | Verify customer count matches | Mobile shows same count as ERP |
| 4 | Check specific customer details | All fields match ERP |
| 5 | Verify pricing tier synced | Correct tier pricing displayed |
| 6 | Check credit limit accuracy | Matches ERP credit data |
| 7 | Verify last order date | Recent transactions synced |

**Expected Result:** Customer data synchronized accurately with ERP
**Status:** ⬜ Not Executed

---

## 5. ORDER TAKING (B2B) TEST CASES

### TC-ORDER-001: Create New B2B Order

| Field | Value |
|-------|-------|
| **Test ID** | TC-ORDER-001 |
| **Module** | Order Taking |
| **Priority** | High |
| **Type** | Functional |

**Preconditions:**
- Customer selected
- Product catalog synced

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Tap "Create Order" | Order entry screen opens |
| 2 | Verify customer pre-populated | Correct customer shown |
| 3 | Tap "Add Product" | Product catalog opens |
| 4 | Select "Fresh Milk 1L" | Product added to order |
| 5 | Enter quantity 50 | Quantity accepted |
| 6 | Verify tier pricing applied | Price shows ৳70 (distributor tier) |
| 7 | Add second product "Yogurt 500g" | Multiple products in order |
| 8 | Verify subtotal calculation | Correct sum displayed |
| 9 | Select delivery date | Calendar picker works |
| 10 | Add special instructions | Notes field accepts text |
| 11 | Review order summary | All details accurate |

**Expected Result:** Order created with correct products, pricing, and details
**Status:** ⬜ Not Executed

---

### TC-ORDER-002: B2B Pricing Tiers Validation

| Field | Value |
|-------|-------|
| **Test ID** | TC-ORDER-002 |
| **Module** | Order Taking |
| **Priority** | High |
| **Type** | Functional |

**Test Data:**

| Product | Retailer | Distributor | HORECA | Institutional |
|---------|----------|-------------|--------|---------------|
| Fresh Milk 1L | ৳75 | ৳70 | ৳72 | ৳68 |
| Yogurt 500g | ৳55 | ৳50 | ৳52 | ৳48 |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login as Retailer tier | Dashboard loaded |
| 2 | Create order for Fresh Milk 1L | Price shows ৳75 |
| 3 | Switch to Distributor account | Re-login as distributor |
| 4 | Create same order | Price shows ৳70 |
| 5 | Switch to HORECA account | Re-login as HORECA |
| 6 | Create same order | Price shows ৳72 |
| 7 | Switch to Institutional account | Re-login as institutional |
| 8 | Create same order | Price shows ৳68 |
| 9 | Verify pricing hidden from other tiers | Only own tier price visible |

**Expected Result:** Correct tier pricing applied for each customer type
**Status:** ⬜ Not Executed

---

### TC-ORDER-003: Minimum Order Quantity (MOQ) Enforcement

| Field | Value |
|-------|-------|
| **Test ID** | TC-ORDER-003 |
| **Module** | Order Taking |
| **Priority** | High |
| **Type** | Functional |

**Test Data:**

| Product | MOQ | Price |
|---------|-----|-------|
| Fresh Milk 1L | 50 units | ৳70 |
| Butter 200g | 20 units | ৳120 |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Select Fresh Milk 1L | Product details shown |
| 2 | Enter quantity 30 | Warning: "MOQ is 50 units" |
| 3 | Attempt to add to order | Add button disabled or warning shown |
| 4 | Change quantity to 50 | No warning, can add |
| 5 | Add to order | Successfully added |
| 6 | Test Butter with quantity 15 | MOQ warning displayed |
| 7 | Change to 20 | Accepted |

**Expected Result:** MOQ enforced with clear user feedback
**Status:** ⬜ Not Executed

---

### TC-ORDER-004: Real-time Inventory Check

| Field | Value |
|-------|-------|
| **Test ID** | TC-ORDER-004 |
| **Module** | Order Taking |
| **Priority** | High |
| **Type** | Functional |

**Test Data:**

| Product | Available Stock | Reserved | Actual Available |
|---------|-----------------|----------|------------------|
| Fresh Milk 1L | 1,000 | 300 | 700 |
| Organic Ghee | 50 | 50 | 0 |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to product catalog | Stock levels displayed |
| 2 | Check Fresh Milk 1L stock | Shows "700 available" |
| 3 | Attempt to order 800 units | Error: "Only 700 available" |
| 4 | Change to 700 | Order accepted |
| 5 | Check Organic Ghee | Shows "Out of Stock" |
| 6 | Attempt to order | Button disabled |
| 7 | Enable "Show alternatives" | Similar products suggested |

**Expected Result:** Real-time stock check prevents overselling
**Status:** ⬜ Not Executed

---

### TC-ORDER-005: Order Templates and Quick Reorder

| Field | Value |
|-------|-------|
| **Test ID** | TC-ORDER-005 |
| **Module** | Order Taking |
| **Priority** | Medium |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | View customer order history | Previous orders listed |
| 2 | Tap "Reorder" on last order | All items added to new order |
| 3 | Verify quantities editable | Can adjust before submitting |
| 4 | Save as template "Weekly Order" | Template saved |
| 5 | Create new order | "Use Template" option available |
| 6 | Select saved template | All template items added |
| 7 | Modify and submit | Customized order placed |

**Expected Result:** Quick reorder and templates speed up order entry
**Status:** ⬜ Not Executed

---

### TC-ORDER-006: Barcode Scanning for Order Entry

| Field | Value |
|-------|-------|
| **Test ID** | TC-ORDER-006 |
| **Module** | Order Taking |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Tap barcode scan icon | Camera opens with scanner overlay |
| 2 | Scan valid product barcode | Product identified and added |
| 3 | Enter quantity | Quantity accepted |
| 4 | Scan another product | Second product added |
| 5 | Scan invalid barcode | "Product not found" message |
| 6 | Scan damaged barcode | Manual entry option provided |
| 7 | Complete order | All scanned products in order |

**Expected Result:** Barcode scanning speeds up order entry
**Status:** ⬜ Not Executed

---

### TC-ORDER-007: Order Modification Before Sync

| Field | Value |
|-------|-------|
| **Test ID** | TC-ORDER-007 |
| **Module** | Order Taking |
| **Priority** | High |
| **Type** | Functional - Offline |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Create order in offline mode | Order saved locally |
| 2 | Navigate to sync queue | Order listed as pending |
| 3 | Tap "Edit" on pending order | Order opens for editing |
| 4 | Add new product | Product added to order |
| 5 | Change quantity of existing item | Quantity updated |
| 6 | Save modifications | Updated order in queue |
| 7 | Delete order from queue | Order removed, confirmation asked |

**Expected Result:** Orders editable while in pending queue
**Status:** ⬜ Not Executed

---

### TC-ORDER-008: Bulk Discount and Promotions

| Field | Value |
|-------|-------|
| **Test ID** | TC-ORDER-008 |
| **Module** | Order Taking |
| **Priority** | Medium |
| **Type** | Functional |

**Test Data:**

| Promotion | Condition | Discount |
|-----------|-----------|----------|
| Volume Discount | > 200 units | 10% |
| Seasonal Offer | All Yogurt | 5% |
| Loyalty Bonus | Orders > ৳50K | 3% |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Add 250 units Milk 1L | Volume discount auto-applied |
| 2 | Verify 10% discount shown | Discount line item visible |
| 3 | Add Yogurt products | Additional 5% applied |
| 4 | Check total exceeds ৳50K | Loyalty bonus applied |
| 5 | Review discount breakdown | Each discount shown separately |
| 6 | Verify final total | Correct after all discounts |

**Expected Result:** Multiple discounts applied correctly
**Status:** ⬜ Not Executed

---

## 6. PRODUCT CATALOG & PRICING TEST CASES

### TC-PROD-001: Product Catalog Display

| Field | Value |
|-------|-------|
| **Test ID** | TC-PROD-001 |
| **Module** | Product Catalog |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to Products | Catalog grid/list view shown |
| 2 | Verify product images loaded | All images display correctly |
| 3 | Check product names | Names in English and Bengali |
| 4 | Verify pricing displayed | Correct tier price shown |
| 5 | Check stock indicator | Availability status visible |
| 6 | Scroll through catalog | Smooth scrolling, no lag |
| 7 | Switch to category view | Products grouped by category |

**Expected Result:** Product catalog displays correctly with all information
**Status:** ⬜ Not Executed

---

### TC-PROD-002: Product Search and Filter

| Field | Value |
|-------|-------|
| **Test ID** | TC-PROD-002 |
| **Module** | Product Catalog |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Tap search icon | Search bar opens |
| 2 | Type "milk" | Products with "milk" filtered |
| 3 | Type Bengali "দুধ" | Bangla search works |
| 4 | Apply category filter "Yogurt" | Only yogurt products shown |
| 5 | Apply "In Stock Only" filter | Out of stock items hidden |
| 6 | Sort by "Price Low to High" | Correct sorting applied |
| 7 | Clear filters | All products shown |

**Expected Result:** Search and filter return accurate results
**Status:** ⬜ Not Executed

---

### TC-PROD-003: Product Details View

| Field | Value |
|-------|-------|
| **Test ID** | TC-PROD-003 |
| **Module** | Product Catalog |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Tap on product | Detail screen opens |
| 2 | View product images | Image gallery swipeable |
| 3 | Check product description | Full description displayed |
| 4 | View specifications | SKU, weight, shelf life shown |
| 5 | Check tier pricing table | All tier prices visible |
| 6 | View stock availability | Current stock quantity shown |
| 7 | Tap "Add to Order" | Quick add to current order |

**Expected Result:** Complete product information accessible
**Status:** ⬜ Not Executed

---

### TC-PROD-004: Dynamic Pricing Updates

| Field | Value |
|-------|-------|
| **Test ID** | TC-PROD-004 |
| **Module** | Product Catalog |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Note current price of Product A | Price recorded |
| 2 | Change price in ERP backend | Price updated in system |
| 3 | Trigger sync in mobile app | Sync completed |
| 4 | Check product price in app | New price displayed |
| 5 | Verify pending orders updated | Draft orders reflect new price |
| 6 | Check price change notification | Alert shown for significant changes |

**Expected Result:** Pricing updates propagate correctly
**Status:** ⬜ Not Executed

---

### TC-PROD-005: Catalog Offline Availability

| Field | Value |
|-------|-------|
| **Test ID** | TC-PROD-005 |
| **Module** | Product Catalog |
| **Priority** | High |
| **Type** | Functional - Offline |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Sync catalog in online mode | Full catalog downloaded |
| 2 | Enable airplane mode | Offline mode activated |
| 3 | Open product catalog | Cached catalog displayed |
| 4 | Search for products | Search works on cached data |
| 5 | View product details | All details available offline |
| 6 | Check stock levels | Last synced stock shown |
| 7 | Create order from catalog | Order created offline |

**Expected Result:** Full catalog functionality available offline
**Status:** ⬜ Not Executed

---

## 7. PAYMENT COLLECTION TEST CASES

### TC-PAY-001: Cash Payment Collection

| Field | Value |
|-------|-------|
| **Test ID** | TC-PAY-001 |
| **Module** | Payment Collection |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to customer profile | Profile displayed |
| 2 | Tap "Record Payment" | Payment screen opens |
| 3 | Select payment type "Cash" | Cash fields shown |
| 4 | Enter amount ৳25,000 | Amount accepted |
| 5 | Select invoice(s) to apply | Invoice list with checkboxes |
| 6 | Verify total matches | Allocation amount = payment amount |
| 7 | Add receipt number "CR-2024-001" | Receipt number saved |
| 8 | Take photo of cash receipt | Photo captured and attached |
| 9 | Submit payment | Payment recorded and queued |

**Expected Result:** Cash payment recorded with receipt photo
**Status:** ⬜ Not Executed

---

### TC-PAY-002: Cheque Payment Collection

| Field | Value |
|-------|-------|
| **Test ID** | TC-PAY-002 |
| **Module** | Payment Collection |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Select payment type "Cheque" | Cheque fields shown |
| 2 | Enter cheque number "123456" | Number validated |
| 3 | Select bank from dropdown | Bank list displayed |
| 4 | Enter cheque date | Date picker works |
| 5 | Enter cheque amount | Amount accepted |
| 6 | Take photo of cheque | Front and back captured |
| 7 | Verify cheque image quality | Clear, readable image |
| 8 | Submit payment | Status "Pending Clearance" |

**Expected Result:** Cheque payment recorded with image proof
**Status:** ⬜ Not Executed

---

### TC-PAY-003: Mobile Payment Integration (bKash)

| Field | Value |
|-------|-------|
| **Test ID** | TC-PAY-003 |
| **Module** | Payment Collection |
| **Priority** | High |
| **Type** | Integration |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Select payment type "bKash" | bKash integration opens |
| 2 | Enter customer bKash number | Number validated |
| 3 | Enter payment amount | Amount displayed |
| 4 | Initiate payment request | bKash prompt sent to customer |
| 5 | Customer confirms payment | Success notification received |
| 6 | Verify transaction ID captured | bKash txn ID stored |
| 7 | Check payment status | Status "Completed" |
| 8 | Verify instant sync | Payment synced immediately |

**Expected Result:** Mobile payment processed and recorded
**Status:** ⬜ Not Executed

---

### TC-PAY-004: Partial Payment Allocation

| Field | Value |
|-------|-------|
| **Test ID** | TC-PAY-004 |
| **Module** | Payment Collection |
| **Priority** | Medium |
| **Type** | Functional |

**Test Data:**

| Invoice | Amount Due |
|---------|------------|
| INV-001 | ৳30,000 |
| INV-002 | ৳20,000 |
| Total Due | ৳50,000 |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | View customer outstanding invoices | List of 2 invoices shown |
| 2 | Enter payment amount ৳40,000 | Amount entered |
| 3 | Allocate ৳30,000 to INV-001 | Fully paid |
| 4 | Allocate ৳10,000 to INV-002 | Partially paid |
| 5 | Verify allocation total | Sum = payment amount |
| 6 | Submit payment | Both invoices updated |
| 7 | Check INV-001 status | Status "Paid" |
| 8 | Check INV-002 status | Status "Partial" with ৳10,000 remaining |

**Expected Result:** Partial payment correctly allocated across invoices
**Status:** ⬜ Not Executed

---

### TC-PAY-005: Payment Receipt Generation

| Field | Value |
|-------|-------|
| **Test ID** | TC-PAY-005 |
| **Module** | Payment Collection |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Complete payment recording | Payment saved |
| 2 | Tap "Generate Receipt" | Receipt preview shown |
| 3 | Verify receipt details | Amount, date, invoices, customer correct |
| 4 | Tap "Print" | Mobile printer connection initiated |
| 5 | Verify print format | Receipt prints correctly |
| 6 | Tap "Share PDF" | PDF generated |
| 7 | Send via WhatsApp/Email | Receipt shared with customer |
| 8 | Verify receipt number unique | Sequential numbering correct |

**Expected Result:** Receipt generated and shareable/printable
**Status:** ⬜ Not Executed

---

## 8. VISIT LOGGING TEST CASES

### TC-VISIT-001: Log Customer Visit

| Field | Value |
|-------|-------|
| **Test ID** | TC-VISIT-001 |
| **Module** | Visit Logging |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Arrive at customer location | GPS detects proximity |
| 2 | Tap "Check In" | Check-in recorded with timestamp |
| 3 | Verify GPS coordinates captured | Location stored |
| 4 | Perform visit activities | Order, payment, notes |
| 5 | Tap "Check Out" | Check-out recorded |
| 6 | Verify visit duration calculated | Time spent calculated |
| 7 | View visit in history | Visit logged with all details |

**Expected Result:** Visit logged with accurate time and location data
**Status:** ⬜ Not Executed

---

### TC-VISIT-002: Add Visit Notes with Voice Input

| Field | Value |
|-------|-------|
| **Test ID** | TC-VISIT-002 |
| **Module** | Visit Logging |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Tap "Add Note" during visit | Note entry screen opens |
| 2 | Tap microphone icon | Voice recording starts |
| 3 | Speak note in Bengali | Speech recognized |
| 4 | Verify text transcription | Accurate Bangla text displayed |
| 5 | Edit if needed | Text editable |
| 6 | Categorize note type | Categories: General, Complaint, Feedback |
| 7 | Attach photo if needed | Camera opens for attachment |
| 8 | Save note | Note saved with timestamp |

**Expected Result:** Voice note recorded and transcribed accurately
**Status:** ⬜ Not Executed

---

### TC-VISIT-003: Missed Visit Logging

| Field | Value |
|-------|-------|
| **Test ID** | TC-VISIT-003 |
| **Module** | Visit Logging |
| **Priority** | Medium |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Attempt to visit customer | Customer unavailable |
| 2 | Tap "Mark as Missed Visit" | Missed visit form opens |
| 3 | Select reason from dropdown | Options: Closed, Owner Away, etc. |
| 4 | Add additional notes | Notes field accepts text |
| 5 | Take photo of shop (optional) | Photo captured |
| 6 | Set follow-up reminder | Reminder scheduled |
| 7 | Save missed visit | Logged with reason |
| 8 | Check rescheduling | Customer added to next route |

**Expected Result:** Missed visit properly documented
**Status:** ⬜ Not Executed

---

### TC-VISIT-004: Visit History and Analytics

| Field | Value |
|-------|-------|
| **Test ID** | TC-VISIT-004 |
| **Module** | Visit Logging |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to Visit History | List of past visits displayed |
| 2 | Filter by date range | Range selector works |
| 3 | Filter by customer | Customer-specific visits shown |
| 4 | View visit details | Full visit information |
| 5 | Check visit frequency stats | Visits per week/month |
| 6 | View order conversion rate | Orders vs visits ratio |
| 7 | Export visit report | CSV/PDF generated |

**Expected Result:** Visit history and analytics accessible
**Status:** ⬜ Not Executed

---

## 9. GPS TRACKING TEST CASES

### TC-GPS-001: GPS Location Tracking Accuracy

| Field | Value |
|-------|-------|
| **Test ID** | TC-GPS-001 |
| **Module** | GPS Tracking |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Enable location services | GPS permission granted |
| 2 | Check current location on map | Accurate position shown |
| 3 | Verify location accuracy | Within 10 meters of actual position |
| 4 | Move to new location | Location updates |
| 5 | Check location refresh rate | Updates every 30 seconds during active use |
| 6 | Verify location stored with check-in | GPS coordinates saved |
| 7 | Compare with known landmark | Accuracy confirmed |

**Expected Result:** GPS tracking accurate within acceptable tolerance
**Status:** ⬜ Not Executed

---

### TC-GPS-002: Background Location Tracking

| Field | Value |
|-------|-------|
| **Test ID** | TC-GPS-002 |
| **Module** | GPS Tracking |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Start route with background tracking enabled | Tracking active notification shown |
| 2 | Minimize app (home button) | App runs in background |
| 3 | Travel to next customer | Location tracked in background |
| 4 | Check battery optimization | Battery usage reasonable |
| 5 | Open app after travel | Route progress updated |
| 6 | Verify path recorded | Travel path visible on map |
| 7 | Stop tracking at end of day | Tracking disabled |

**Expected Result:** Background tracking works without excessive battery drain
**Status:** ⬜ Not Executed

---

### TC-GPS-003: Geofencing for Customer Locations

| Field | Value |
|-------|-------|
| **Test ID** | TC-GPS-003 |
| **Module** | GPS Tracking |
| **Priority** | Medium |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Approach customer location | Within 100m radius |
| 2 | Verify geofence trigger | Notification: "Near Bengal Traders" |
| 3 | Auto-suggest check-in | Check-in prompt shown |
| 4 | Leave location | Exit geofence detected |
| 5 | Verify check-out reminder | Prompt to check out |
| 6 | View geofence settings | Radius configurable |

**Expected Result:** Geofencing triggers appropriate notifications
**Status:** ⬜ Not Executed

---

### TC-GPS-004: GPS Data Privacy and Security

| Field | Value |
|-------|-------|
| **Test ID** | TC-GPS-004 |
| **Module** | GPS Tracking |
| **Priority** | High |
| **Type** | Security |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Verify location data encrypted | Data encrypted at rest |
| 2 | Check transmission security | HTTPS for location sync |
| 3 | Verify role-based access | Only managers see rep locations |
| 4 | Check data retention policy | Old locations purged per policy |
| 5 | Verify opt-out available | Rep can disable tracking |
| 6 | Check audit logs | Location access logged |

**Expected Result:** GPS data handled securely with privacy controls
**Status:** ⬜ Not Executed

---

## 10. OFFLINE MODE TEST CASES

### TC-OFFLINE-001: Create Order in Offline Mode

| Field | Value |
|-------|-------|
| **Test ID** | TC-OFFLINE-001 |
| **Module** | Offline Mode |
| **Priority** | High |
| **Type** | Functional - Offline |

**Preconditions:**
- Device in airplane mode or no signal area
- Customer and product data previously synced

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Enable airplane mode | Offline indicator shown |
| 2 | Navigate to customer | Customer data available |
| 3 | Create new order | Order form functional |
| 4 | Add products to order | Products added with cached prices |
| 5 | Complete order details | All fields editable |
| 6 | Submit order | Order saved locally |
| 7 | Check sync queue | Order listed as pending |
| 8 | Verify offline badge | "Pending Sync" indicator shown |

**Expected Result:** Order created and queued for sync
**Status:** ⬜ Not Executed

---

### TC-OFFLINE-002: Offline Queue Management

| Field | Value |
|-------|-------|
| **Test ID** | TC-OFFLINE-002 |
| **Module** | Offline Mode |
| **Priority** | High |
| **Type** | Functional - Offline |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Create multiple orders offline | 3 orders in queue |
| 2 | Record payment offline | Payment added to queue |
| 3 | Add customer note offline | Note queued |
| 4 | View sync queue | All items listed with type |
| 5 | Check storage usage | MB used displayed |
| 6 | Reorder queue items | Priority adjustment possible |
| 7 | Delete queue item | Confirmation required |

**Expected Result:** Queue management fully functional offline
**Status:** ⬜ Not Executed

---

### TC-OFFLINE-003: Automatic Sync When Online

| Field | Value |
|-------|-------|
| **Test ID** | TC-OFFLINE-003 |
| **Module** | Offline Mode |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Verify items in sync queue | 5 items pending |
| 2 | Disable airplane mode | Connection restored |
| 3 | Wait for auto-sync trigger | Sync starts automatically |
| 4 | Monitor sync progress | Progress bar shows status |
| 5 | Verify item-by-item sync | Each item processed |
| 6 | Check success confirmations | Checkmarks for successful items |
| 7 | Verify ERP reflects changes | Orders visible in backend |
| 8 | Check local data cleared | Queue empty after sync |

**Expected Result:** Automatic sync succeeds when connectivity restored
**Status:** ⬜ Not Executed

---

### TC-OFFLINE-004: Sync Conflict Resolution

| Field | Value |
|-------|-------|
| **Test ID** | TC-OFFLINE-004 |
| **Module** | Offline Mode |
| **Priority** | High |
| **Type** | Functional |

**Test Scenario:**
- Order created offline for customer
- Meanwhile, customer's credit limit reduced in ERP
- Sync attempts to submit order exceeding new limit

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Create offline order | Order in queue |
| 2 | Reduce customer credit in ERP | Backend change made |
| 3 | Attempt sync | Conflict detected |
| 4 | Check error notification | "Credit limit exceeded" shown |
| 5 | Review conflict details | Order details vs new limit |
| 6 | Edit order to reduce amount | Order modified |
| 7 | Retry sync | Sync successful |

**Expected Result:** Conflicts detected and resolvable
**Status:** ⬜ Not Executed

---

### TC-OFFLINE-005: Data Persistence Across App Restarts

| Field | Value |
|-------|-------|
| **Test ID** | TC-OFFLINE-005 |
| **Module** | Offline Mode |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Create order offline | Order saved locally |
| 2 | Force close app | App terminated |
| 3 | Reopen app | App restarts |
| 4 | Check sync queue | Order still in queue |
| 5 | Verify order details intact | All data preserved |
| 6 | Add another order offline | Second order queued |
| 7 | Restart device | Device rebooted |
| 8 | Check data after reboot | Both orders still in queue |

**Expected Result:** Offline data persists across app/device restarts
**Status:** ⬜ Not Executed

---

### TC-OFFLINE-006: Storage Limit Management

| Field | Value |
|-------|-------|
| **Test ID** | TC-OFFLINE-006 |
| **Module** | Offline Mode |
| **Priority** | Medium |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Check storage settings | Limit shown (e.g., 500MB) |
| 2 | Monitor usage as data added | Usage bar increases |
| 3 | Create large orders with photos | Storage fills up |
| 4 | Reach 90% capacity | Warning notification shown |
| 5 | Reach 100% capacity | Critical alert, sync blocked |
| 6 | Attempt to add more data | Error: "Storage full, sync required" |
| 7 | Clear old synced data | Storage freed |

**Expected Result:** Storage limits managed with user alerts
**Status:** ⬜ Not Executed

---

### TC-OFFLINE-007: Manual Sync Trigger

| Field | Value |
|-------|-------|
| **Test ID** | TC-OFFLINE-007 |
| **Module** | Offline Mode |
| **Priority** | Medium |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Ensure items in sync queue | Pending items exist |
| 2 | Tap "Sync Now" button | Manual sync initiated |
| 3 | View sync progress | Upload progress shown |
| 4 | Check last sync timestamp | Updated after completion |
| 5 | Pull down to refresh | Sync triggered by gesture |
| 6 | Shake device | Alternative sync trigger works |

**Expected Result:** Manual sync triggers work correctly
**Status:** ⬜ Not Executed

---

### TC-OFFLINE-008: Selective Sync Configuration

| Field | Value |
|-------|-------|
| **Test ID** | TC-OFFLINE-008 |
| **Module** | Offline Mode |
| **Priority** | Medium |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Open sync settings | Configuration screen |
| 2 | Enable "Auto-sync on WiFi only" | Setting saved |
| 3 | Test on mobile data | Auto-sync does not trigger |
| 4 | Connect to WiFi | Auto-sync activates |
| 5 | Disable auto-sync | Manual sync only |
| 6 | Set sync schedule | Daily at specific time |
| 7 | Verify schedule applied | Sync occurs at set time |

**Expected Result:** Sync behavior configurable per user preference
**Status:** ⬜ Not Executed

---

## 11. SIGNATURE CAPTURE TEST CASES

### TC-SIGN-001: Capture Customer Signature

| Field | Value |
|-------|-------|
| **Test ID** | TC-SIGN-001 |
| **Module** | Signature Capture |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Complete order entry | Order ready for confirmation |
| 2 | Tap "Get Signature" | Signature pad opens |
| 3 | Sign using finger | Signature captured |
| 4 | Check signature quality | Clear, readable signature |
| 5 | Tap "Clear" to retry | Canvas cleared |
| 6 | Sign again | New signature captured |
| 7 | Tap "Confirm" | Signature attached to order |
| 8 | Verify signature in preview | Signature displayed on order |

**Expected Result:** Signature captured and attached to order
**Status:** ⬜ Not Executed

---

### TC-SIGN-002: Signature Storage and Retrieval

| Field | Value |
|-------|-------|
| **Test ID** | TC-SIGN-002 |
| **Module** | Signature Capture |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Capture signature on order | Signature saved locally |
| 2 | Sync order with signature | Signature uploaded |
| 3 | Verify in ERP backend | Signature attached to sales order |
| 4 | Check signature file format | PNG/SVG format |
| 5 | Download signature from ERP | File downloadable |
| 6 | Verify signature integrity | Image matches original |
| 7 | Check audit trail | Timestamp and signer recorded |

**Expected Result:** Signatures stored securely and retrievable
**Status:** ⬜ Not Executed

---

### TC-SIGN-003: Signature with Terms Acceptance

| Field | Value |
|-------|-------|
| **Test ID** | TC-SIGN-003 |
| **Module** | Signature Capture |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to signature screen | Terms displayed |
| 2 | Verify terms checkbox | Unchecked by default |
| 3 | Attempt to submit without checking | Error: "Accept terms required" |
| 4 | Check terms acceptance box | Checkbox marked |
| 5 | Capture signature | Signature recorded |
| 6 | Submit order | Order confirmed with signature |
| 7 | Verify legal record | Terms acceptance logged |

**Expected Result:** Signature valid with explicit terms acceptance
**Status:** ⬜ Not Executed

---

### TC-SIGN-004: Signature Capture in Offline Mode

| Field | Value |
|-------|-------|
| **Test ID** | TC-SIGN-004 |
| **Module** | Signature Capture |
| **Priority** | High |
| **Type** | Functional - Offline |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Enable airplane mode | Offline mode active |
| 2 | Create order and proceed to signature | Signature pad functional |
| 3 | Capture signature offline | Signature saved locally |
| 4 | Verify order in sync queue | Order with signature queued |
| 5 | Reconnect to network | Online mode restored |
| 6 | Trigger sync | Order with signature synced |
| 7 | Verify signature in ERP | Signature available in backend |

**Expected Result:** Signatures captured and synced in offline mode
**Status:** ⬜ Not Executed

---

## 12. DASHBOARD & REPORTS TEST CASES

### TC-DASH-001: Daily Performance Dashboard

| Field | Value |
|-------|-------|
| **Test ID** | TC-DASH-001 |
| **Module** | Dashboard |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to Dashboard | Dashboard screen loads |
| 2 | Check date display | Today's date shown |
| 3 | View revenue metric | Today's sales amount displayed |
| 4 | Check orders count | Number of orders shown |
| 5 | View visits completed | Visit progress shown |
| 6 | Check target achievement | Progress bar vs daily target |
| 7 | View recent orders list | Last 5 orders displayed |
| 8 | Pull to refresh | Data updates from server |

**Expected Result:** Dashboard shows accurate daily performance metrics
**Status:** ⬜ Not Executed

---

### TC-DASH-002: Weekly and Monthly Reports

| Field | Value |
|-------|-------|
| **Test ID** | TC-DASH-002 |
| **Module** | Dashboard |
| **Priority** | Medium |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Tap "View Reports" | Report selection screen |
| 2 | Select "Weekly Summary" | Week picker displayed |
| 3 | Choose current week | Weekly report generated |
| 4 | Verify daily breakdown | Each day's data shown |
| 5 | Check comparison vs last week | Variance percentage displayed |
| 6 | View monthly report | Month data aggregated |
| 7 | Check top customers | Best performing customers listed |
| 8 | Export report | PDF generated and shared |

**Expected Result:** Historical reports generated with accurate data
**Status:** ⬜ Not Executed

---

### TC-DASH-003: Sync Status Dashboard

| Field | Value |
|-------|-------|
| **Test ID** | TC-DASH-003 |
| **Module** | Dashboard |
| **Priority** | High |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | View sync status widget | Status indicator shown |
| 2 | Check last sync time | Timestamp accurate |
| 3 | View pending items count | Number of items to sync |
| 4 | Check storage usage | MB used / total displayed |
| 5 | View connection status | Online/offline indicator |
| 6 | Tap sync widget | Navigate to sync queue |
| 7 | Check data freshness | Last update timestamps |

**Expected Result:** Sync status clearly visible and actionable
**Status:** ⬜ Not Executed

---

### TC-DASH-004: Target and Achievement Tracking

| Field | Value |
|-------|-------|
| **Test ID** | TC-DASH-004 |
| **Module** | Dashboard |
| **Priority** | Medium |
| **Type** | Functional |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | View monthly target | Target amount displayed |
| 2 | Check current achievement | Amount achieved so far |
| 3 | Verify percentage complete | Progress calculation correct |
| 4 | View target breakdown | Daily/weekly sub-targets |
| 5 | Check achievement trend | Line chart showing progress |
| 6 | View incentive calculator | Bonus/commission estimate |
| 7 | Compare with team average | Peer comparison shown |

**Expected Result:** Sales targets tracked with achievement metrics
**Status:** ⬜ Not Executed

---

## 13. FIELD CONDITIONS & DEVICE TEST CASES

### TC-FIELD-001: Low Network Connectivity

| Field | Value |
|-------|-------|
| **Test ID** | TC-FIELD-001 |
| **Module** | Field Conditions |
| **Priority** | High |
| **Type** | Performance |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Simulate 2G/Edge connection | Network throttled |
| 2 | Attempt to sync | Sync attempts with retry logic |
| 3 | Create order | Order created offline mode |
| 4 | Check response times | UI remains responsive |
| 5 | Verify offline indicator | "Poor Connection" warning shown |
| 6 | Test auto-retry | Sync retries automatically |
| 7 | Verify no data loss | All actions saved locally |

**Expected Result:** App functions gracefully on poor networks
**Status:** ⬜ Not Executed

---

### TC-FIELD-002: Battery Optimization

| Field | Value |
|-------|-------|
| **Test ID** | TC-FIELD-002 |
| **Module** | Field Conditions |
| **Priority** | High |
| **Type** | Performance |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Start with 100% battery | Baseline recorded |
| 2 | Use app for 4 hours typical usage | GPS, orders, sync |
| 3 | Check battery drain | < 30% consumed in 4 hours |
| 4 | Enable battery saver mode | App adjusts behavior |
| 5 | Verify background sync reduced | Less frequent updates |
| 6 | Check GPS accuracy maintained | Location still accurate |
| 7 | Test with low battery (< 20%) | Warnings and restrictions applied |

**Expected Result:** Battery consumption optimized for field use
**Status:** ⬜ Not Executed

---

### TC-FIELD-003: Device Heating Management

| Field | Value |
|-------|-------|
| **Test ID** | TC-FIELD-003 |
| **Module** | Field Conditions |
| **Priority** | Medium |
| **Type** | Performance |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Use app continuously for 2 hours | Intensive usage |
| 2 | Monitor device temperature | Temperature within safe range |
| 3 | Check for throttling warnings | Alerts if overheating |
| 4 | Verify background processes reduced | Automatic optimization |
| 5 | Test camera functionality | Photo capture still works |
| 6 | Check sync behavior | Paused if overheating |
| 7 | Verify app stability | No crashes due to heat |

**Expected Result:** App manages device temperature effectively
**Status:** ⬜ Not Executed

---

### TC-FIELD-004: Sunlight Visibility

| Field | Value |
|-------|-------|
| **Test ID** | TC-FIELD-004 |
| **Module** | Field Conditions |
| **Priority** | High |
| **Type** | UI/UX |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Use app in bright sunlight | Outdoor conditions |
| 2 | Verify high contrast mode | Dark text on light background |
| 3 | Check text readability | Font size adequate |
| 4 | Test with brightness auto-adjust | Screen brightens automatically |
| 5 | Verify button visibility | Touch targets clearly visible |
| 6 | Check map readability | Routes and pins visible |
| 7 | Test with sunglasses | Polarized lens compatible |

**Expected Result:** UI visible and usable in bright sunlight
**Status:** ⬜ Not Executed

---

### TC-FIELD-005: One-Handed Operation

| Field | Value |
|-------|-------|
| **Test ID** | TC-FIELD-005 |
| **Module** | Field Conditions |
| **Priority** | High |
| **Type** | UI/UX |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Hold device in one hand | Comfortable grip |
| 2 | Navigate using thumb only | All primary actions reachable |
| 3 | Check bottom navigation | Within thumb reach |
| 4 | Test quick action buttons | Large touch targets |
| 5 | Verify swipe gestures | Swipe to complete actions |
| 6 | Check floating action button | Positioned for thumb access |
| 7 | Test order creation flow | Completable with one hand |

**Expected Result:** Core functions accessible with one-handed use
**Status:** ⬜ Not Executed

---

### TC-FIELD-006: Mobile Printing Support

| Field | Value |
|-------|-------|
| **Test ID** | TC-FIELD-006 |
| **Module** | Field Conditions |
| **Priority** | Medium |
| **Type** | Integration |

**Test Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Pair Bluetooth printer | Printer connected |
| 2 | Complete order with signature | Order confirmed |
| 3 | Tap "Print Receipt" | Print dialog opens |
| 4 | Select paired printer | Printer selected |
| 5 | Preview print output | Receipt preview shown |
| 6 | Send to print | Receipt printed |
| 7 | Verify print quality | Text clear, barcode scannable |
| 8 | Test with WiFi printer | Alternative connection works |

**Expected Result:** Mobile printing functional for receipts
**Status:** ⬜ Not Executed

---

## 14. REGRESSION TEST SUITE

### 14.1 Smoke Test Checklist

| # | Test | Status |
|---|------|--------|
| 1 | App launches successfully | ⬜ |
| 2 | Login works | ⬜ |
| 3 | Route loads with customers | ⬜ |
| 4 | Customer profile displays | ⬜ |
| 5 | Order creation functional | ⬜ |
| 6 | Signature capture works | ⬜ |
| 7 | Payment recording works | ⬜ |
| 8 | Sync queue accessible | ⬜ |
| 9 | Dashboard shows data | ⬜ |
| 10 | Offline mode activates | ⬜ |

### 14.2 Critical Path Test Suite

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    FIELD SALES CRITICAL PATH FLOW                            │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  LOGIN ──▶ ROUTE ──▶ CUSTOMER ──▶ ORDER ──▶ SIGNATURE ──▶ SYNC             │
│                                                                              │
│  Test Data:                                                                  │
│  • Sales Rep: test.rep@smartdairy.com / TestPass123!                        │
│  • Customer: Bengal Traders (B2B-2024-001)                                  │
│  • Product: Fresh Milk 1L × 50 units                                        │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 15. APPENDICES

### Appendix A: Test Data

#### A.1 Test Sales Representatives

| Username | Password | Territory | Role |
|----------|----------|-----------|------|
| rahim.rep@test.com | TestPass123! | Dhaka North | Sales Rep |
| karim.rep@test.com | TestPass123! | Dhaka South | Sales Rep |
| test.supervisor@test.com | TestPass123! | All | Supervisor |

#### A.2 Test Customers

| Customer ID | Name | Tier | Credit Limit | Status |
|-------------|------|------|--------------|--------|
| B2B-2024-001 | Bengal Traders | Distributor | ৳500,000 | Active |
| B2B-2024-002 | Dhaka Dairy Mart | Retailer | ৳100,000 | Active |
| B2B-2024-003 | Fresh Foods Ltd | HORECA | ৳200,000 | Active |
| B2B-2024-004 | City Supermarket | Retailer | ৳50,000 | On Hold |

#### A.3 Test Products

| SKU | Name | Base Price | MOQ | Stock |
|-----|------|------------|-----|-------|
| MILK-001 | Fresh Milk 1L | ৳70 | 50 | 1,000 |
| MILK-002 | Fresh Milk 500ml | ৳40 | 100 | 2,000 |
| YOGR-001 | Sweet Yogurt 500g | ৳50 | 30 | 500 |
| BUTR-001 | Organic Butter 200g | ৳120 | 20 | 200 |

### Appendix B: Defect Severity Classification

| Severity | Definition | Response Time |
|----------|------------|---------------|
| **P1 - Critical** | App crash, data loss, security breach | Immediate fix |
| **P2 - High** | Core feature not working, workaround difficult | Fix within 24 hours |
| **P3 - Medium** | Feature partially working, workaround available | Fix within 1 week |
| **P4 - Low** | Cosmetic issues, minor inconvenience | Fix in next release |

### Appendix C: Field Testing Checklist

| # | Checklist Item | Pass/Fail |
|---|----------------|-----------|
| 1 | App installs on test devices | ⬜ |
| 2 | Login works with test credentials | ⬜ |
| 3 | GPS location accurate within 10m | ⬜ |
| 4 | Offline order creation works | ⬜ |
| 5 | Signature capture quality acceptable | ⬜ |
| 6 | Photo capture works in bright light | ⬜ |
| 7 | Battery usage < 30% per 4 hours | ⬜ |
| 8 | App responsive on 2G network | ⬜ |
| 9 | Sync completes within 5 minutes | ⬜ |
| 10 | Receipt printing works | ⬜ |

---

## DOCUMENT REVISION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | QA Engineer | Initial document creation with 60 test cases |

---

*This document is the property of Smart Dairy Ltd. and contains confidential information.*
*Unauthorized distribution or reproduction is prohibited.*
