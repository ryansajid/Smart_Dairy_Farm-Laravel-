# SMART DAIRY LTD.
## FUNCTIONAL TEST CASES - ERP CORE MODULES
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | G-010 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | QA Engineer |
| **Owner** | QA Lead |
| **Reviewer** | ERP Implementation Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Test Case Overview](#2-test-case-overview)
3. [Inventory Management Test Cases](#3-inventory-management-test-cases)
4. [Purchase Management Test Cases](#4-purchase-management-test-cases)
5. [Sales Management Test Cases](#5-sales-management-test-cases)
6. [Accounting & Finance Test Cases](#6-accounting--finance-test-cases)
7. [Human Resources & Payroll Test Cases](#7-human-resources--payroll-test-cases)
8. [Manufacturing (MRP) Test Cases](#8-manufacturing-mrp-test-cases)
9. [Point of Sale (POS) Test Cases](#9-point-of-sale-pos-test-cases)
10. [Cross-Module Integration Test Cases](#10-cross-module-integration-test-cases)
11. [Bangladesh Localization Test Cases](#11-bangladesh-localization-test-cases)
12. [Appendices](#12-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides comprehensive functional test cases for the Smart Dairy ERP Core Modules. The test cases ensure that all business operations, inventory management, procurement, sales, accounting, payroll, manufacturing, and point-of-sale functionality work correctly and comply with Bangladesh regulatory requirements.

### 1.2 Scope

| Module | Coverage |
|--------|----------|
| Inventory Management | Multi-location stock, cold chain, batch/lot tracking, FEFO/FIFO |
| Purchase Management | Supplier management, RFQ, purchase orders, goods receipt |
| Sales Management | Quotations, sales orders, delivery, invoicing, CRM |
| Accounting & Finance | GL, AP/AR, Bangladesh VAT (15%), bank reconciliation, multi-currency |
| HR & Payroll | Employee management, attendance, leave, Bangladesh payroll compliance |
| Manufacturing (MRP) | BOM, production planning, work orders, yield tracking |
| Point of Sale (POS) | Retail operations, offline mode, integrated payments |

### 1.3 Test Environment

| Environment | URL | Purpose |
|-------------|-----|---------|
| QA | https://erp-qa.smartdairybd.com | Functional testing |
| Staging | https://erp-staging.smartdairybd.com | UAT |
| Production | https://erp.smartdairybd.com | Live validation |

### 1.4 Reference Documents

- B-001: Technical Design Document
- B-002: Odoo Development Guide
- B-005: Bangladesh Localization Module Technical Spec
- E-001: API Specification Document

---

## 2. TEST CASE OVERVIEW

### 2.1 Test Case Summary

| Module | Total Cases | Critical | High | Medium | Low |
|--------|-------------|----------|------|--------|-----|
| Inventory Management | 15 | 4 | 6 | 4 | 1 |
| Purchase Management | 12 | 3 | 5 | 3 | 1 |
| Sales Management | 12 | 3 | 5 | 3 | 1 |
| Accounting & Finance | 18 | 5 | 7 | 4 | 2 |
| HR & Payroll | 14 | 4 | 5 | 4 | 1 |
| Manufacturing (MRP) | 10 | 3 | 4 | 2 | 1 |
| Point of Sale (POS) | 12 | 4 | 5 | 2 | 1 |
| **TOTAL** | **93** | **26** | **37** | **22** | **8** |

### 2.2 Test Priority Definitions

| Priority | Definition | Response Time |
|----------|------------|---------------|
| Critical | Core business functionality; system cannot operate without | Fix within 24 hours |
| High | Important features affecting daily operations | Fix within 48 hours |
| Medium | Enhancement features; workarounds available | Fix within 1 week |
| Low | Cosmetic issues, minor inconveniences | Fix in next release |

### 2.3 User Roles for Testing

| Role | Permissions | Test Focus |
|------|-------------|------------|
| ERP Admin | Full system access | Configuration, security |
| Finance Manager | Accounting, reporting | Financial modules |
| Inventory Manager | Stock, warehouse | Inventory operations |
| Purchase Officer | Procurement | Purchase workflow |
| Sales Manager | Sales, CRM | Sales pipeline |
| HR Manager | Employee, payroll | HR compliance |
| Production Manager | Manufacturing | MRP, production |
| POS Operator | Sales transactions | POS operations |
| Store Keeper | Stock movements | Warehouse operations |

---

## 3. INVENTORY MANAGEMENT TEST CASES

### 3.1 Module Overview

The Inventory Management module handles multi-location stock tracking with specialized support for cold chain items (dairy products), batch/lot tracking, FEFO (First Expired First Out) methodology, and integration with IoT temperature sensors.

| Metric | Value |
|--------|-------|
| Total Test Cases | 15 |
| Critical | 4 |
| High | 6 |
| Test Data Required | Products, Warehouses, Batches, UoM |

---

#### TC-INV-001: Product Master Creation - Cold Chain Item

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INV-001 |
| **Title** | Verify Cold Chain Product Setup |
| **Priority** | Critical |

**Preconditions:**
- User logged in as Inventory Manager
- Product categories configured
- Unit of Measure (UoM) defined

**Test Steps:**
1. Navigate to Inventory > Products > Products
2. Click "Create" new product
3. Enter product details:
   - Name: Saffron Organic Milk 1L
   - Internal Reference: SM-ORG-001
   - Category: Dairy Products / Fresh Milk
   - Product Type: Storable Product
   - Default UoM: Liter(s)
   - Purchase UoM: Carton (12x1L)
4. Enable "Cold Chain Required" checkbox
5. Set temperature requirements:
   - Min Temperature: 2°C
   - Max Temperature: 4°C
   - Critical Alert: > 6°C
6. Set shelf life parameters:
   - Shelf Life: 5 days
   - Alert Before: 1 day
7. Enable batch tracking: "By Lots"
8. Save product

**Expected Results:**
- [ ] Product created successfully
- [ ] Cold chain icon displayed on product card
- [ ] Temperature range saved correctly
- [ ] Batch tracking enabled
- [ ] Product appears in cold chain monitoring dashboard
- [ ] Expiry alert scheduled automatically

**Post-conditions:**
- Product available for procurement and sales
- Cold chain monitoring active for this product

---

#### TC-INV-002: Warehouse Creation with Cold Storage Zones

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INV-002 |
| **Title** | Verify Multi-Zone Warehouse Setup |
| **Priority** | Critical |

**Preconditions:**
- User logged in as ERP Admin
- Locations module installed

**Test Steps:**
1. Navigate to Inventory > Configuration > Warehouses
2. Click "Create" new warehouse
3. Enter warehouse details:
   - Warehouse Name: Dhaka Distribution Center
   - Short Name: DDC
   - Address: Smart Dairy Warehouse, Tongi, Gazipur
4. Configure location structure:
   - Create "Cold Storage" location type: Internal Location
   - Set temperature zone: 0-4°C
   - Create "Ambient Storage" location type: Internal Location
   - Create "Quarantine" location type: Internal Location
   - Create "Scrap" location type: Virtual Location
5. Enable IoT temperature monitoring for Cold Storage
6. Save warehouse

**Expected Results:**
- [ ] Warehouse created with unique code
- [ ] Location hierarchy established
- [ ] Cold storage zone configured with temperature tracking
- [ ] IoT sensor integration enabled
- [ ] Location available in stock operations

**Post-conditions:**
- Warehouse operational for receiving and dispatch
- Temperature alerts configured

---

#### TC-INV-003: Goods Receipt with Batch Tracking

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INV-003 |
| **Title** | Verify Batch/Lot Creation on Receipt |
| **Priority** | Critical |

**Preconditions:**
- Purchase order created for milk products
- Supplier delivery arrived
- User logged in as Store Keeper

**Test Steps:**
1. Navigate to Purchase > Orders
2. Open confirmed PO: PO/2026/0001
3. Click "Receive Products"
4. Validate received quantities:
   - Saffron Organic Milk 1L: 50 cartons
   - Batch/Lot: AUTO-GENERATE
5. Specify batch details:
   - Production Date: 31/01/2026
   - Expiry Date: 05/02/2026
   - Supplier Batch: SB-2026-1234
6. Record temperature on arrival: 3.5°C
7. Assign to Cold Storage location
8. Validate receipt

**Expected Results:**
- [ ] Batch number auto-generated: LOT/DDC/0001
- [ ] Expiry date calculated (5 days from production)
- [ ] Temperature reading recorded
- [ ] Stock updated in Cold Storage: +50 cartons
- [ ] FEFO queue updated with new batch
- [ ] Quality alert created for expiry in 5 days

**Post-conditions:**
- Inventory updated with batch details
- Batch available for FEFO picking

---

#### TC-INV-004: FEFO (First Expired First Out) Picking

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INV-004 |
| **Title** | Verify FEFO Picking Strategy |
| **Priority** | High |

**Preconditions:**
- Multiple batches of same product exist
- Sales order created requiring 30 cartons
- User logged in as Store Keeper

**Test Steps:**
1. Verify current stock:
   - LOT/DDC/0001: 20 cartons (Expires: 03/02/2026)
   - LOT/DDC/0002: 50 cartons (Expires: 05/02/2026)
   - LOT/DDC/0003: 30 cartons (Expires: 02/02/2026)
2. Navigate to Sales > Orders
3. Open Sales Order SO/2026/0010
4. Click "Delivery" to create picking
5. Review reserved quantities

**Expected Results:**
- [ ] System selects LOT/DDC/0003 first (expires 02/02)
- [ ] Then selects LOT/DDC/0001 (expires 03/02)
- [ ] Partial from LOT/DDC/0002 if needed
- [ ] Reservation respects FEFO strategy
- [ ] Expiry dates visible on picking slip

**Post-conditions:**
- Oldest expiry batches reserved first
- Minimum wastage ensured

---

#### TC-INV-005: Cold Chain Temperature Alert

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INV-005 |
| **Title** | Verify Temperature Breach Alert |
| **Priority** | Critical |

**Preconditions:**
- IoT sensors installed in Cold Storage
- Alert rules configured
- User has notification permissions

**Test Steps:**
1. Monitor Cold Storage DDC/COLD/001
2. Simulate temperature breach via IoT test data:
   - Send temperature reading: 7.5°C
3. Wait for alert processing
4. Check notifications
5. Review alert details
6. Acknowledge alert and document action

**Expected Results:**
- [ ] Temperature breach detected (> 6°C threshold)
- [ ] Critical alert generated within 2 minutes
- [ ] Email notification sent to Inventory Manager
- [ ] SMS notification sent to on-duty staff
- [ ] Alert dashboard shows breach details:
  - Location: DDC/COLD/001
  - Current Temperature: 7.5°C
  - Threshold: 6°C
  - Time: [Timestamp]
  - Affected Products: [List]
- [ ] Audit trail created

**Post-conditions:**
- Alert logged in system
- Temperature history updated

---

#### TC-INV-006: Stock Adjustment with Reason Codes

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INV-006 |
| **Title** | Verify Inventory Adjustment with Audit Trail |
| **Priority** | High |

**Preconditions:**
- Physical stock count completed
- Discrepancy identified: 5 cartons damaged
- User logged in as Inventory Manager

**Test Steps:**
1. Navigate to Inventory > Operations > Inventory Adjustments
2. Create new adjustment:
   - Location: DDC/COLD/001
   - Product: Saffron Organic Milk 1L
   - Lot: LOT/DDC/0001
3. Enter counted quantity: 45 (system shows 50)
4. Select reason code: "Damaged/Spoiled"
5. Attach photo evidence
6. Enter notes: "Cartons damaged during transport"
7. Submit for approval
8. Approve as Inventory Manager

**Expected Results:**
- [ ] Adjustment request created
- [ ] Reason code mandatory
- [ ] Photo attached successfully
- [ ] Approval workflow triggered
- [ ] Stock updated after approval: -5 cartons
- [ ] Movement type: Inventory Loss
- [ ] Audit trail: User, timestamp, reason

**Post-conditions:**
- Inventory reflects actual stock
- Loss recorded in accounting

---

#### TC-INV-007: Inter-Warehouse Transfer

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INV-007 |
| **Title** | Verify Stock Transfer Between Warehouses |
| **Priority** | High |

**Preconditions:**
- Two warehouses configured: DDC and Farm Store
- Stock available at source
- User logged in as Inventory Manager

**Test Steps:**
1. Navigate to Inventory > Operations > Transfers
2. Create internal transfer:
   - Source: DDC/COLD/001
   - Destination: FARM/STORE/001
   - Operation Type: Internal Transfer
3. Add product lines:
   - Product: Saffron Organic Milk 1L
   - Lot: LOT/DDC/0002
   - Quantity: 20 cartons
4. Confirm transfer
5. Process picking at source
6. Record transport temperature: 3°C
7. Receive at destination
8. Validate transfer

**Expected Results:**
- [ ] Transfer document created: INT/2026/0001
- [ ] Stock reserved at source
- [ ] Temperature monitoring during transit
- [ ] Stock received at destination
- [ ] Stock levels updated at both locations
- [ ] Transfer history maintained

**Post-conditions:**
- Source warehouse stock reduced
- Destination warehouse stock increased

---

#### TC-INV-008: Expiry Date Alert and Auto-Quarantine

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INV-008 |
| **Title** | Verify Expiry Alert and Quarantine Process |
| **Priority** | High |

**Preconditions:**
- Products with approaching expiry dates
- Quarantine location configured
- Automated rules enabled

**Test Steps:**
1. Review products expiring within 24 hours
2. Verify alert generation
3. Check auto-quarantine execution
4. Review quarantined stock report

**Expected Results:**
- [ ] Daily expiry alert email sent
- [ ] Alert lists all batches expiring within 24 hours
- [ ] Auto-quarantine moves stock to QUARANTINE location
- [ ] Products blocked from sales reservations
- [ ] Quarantine report available
- [ ] Option to extend/destroy stock

**Post-conditions:**
- Expired stock isolated
- No sales from expired batches possible

---

#### TC-INV-009: Reorder Point and Procurement Suggestion

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INV-009 |
| **Title** | Verify Automated Reorder Suggestions |
| **Priority** | Medium |

**Preconditions:**
- Product configured with reorder rules
- Current stock below minimum
- User logged in as Purchase Officer

**Test Steps:**
1. Navigate to Inventory > Operations > Replenishment
2. Review procurement suggestions
3. Verify reorder point calculations
4. Confirm purchase suggestion

**Expected Results:**
- [ ] Products below minimum stock highlighted
- [ ] Suggested quantity calculated:
  - Minimum: 100 units
  - Maximum: 500 units
  - Current: 80 units
  - Suggested: 420 units
- [ ] Preferred supplier populated
- [ ] One-click RFQ creation available

**Post-conditions:**
- Procurement suggestions generated
- Purchase process initiated

---

#### TC-INV-010: Inventory Valuation Report

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INV-010 |
| **Title** | Verify Stock Valuation by FIFO |
| **Priority** | Medium |

**Preconditions:**
- Stock movements recorded
- Costs assigned to products
- User logged in as Finance Manager

**Test Steps:**
1. Navigate to Inventory > Reporting > Inventory Valuation
2. Select date: 31/01/2026
3. Select valuation method: FIFO
4. Generate report
5. Export to Excel

**Expected Results:**
- [ ] Report shows all stock quantities
- [ ] Unit costs displayed per batch
- [ ] Total valuation calculated in BDT
- [ ] Breakdown by product category
- [ ] Breakdown by warehouse
- [ ] Excel export successful

**Post-conditions:**
- Valuation report available for accounting

---

#### TC-INV-011: Negative Stock Prevention

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INV-011 |
| **Title** | Verify Negative Stock Blocking |
| **Priority** | High |

**Preconditions:**
- Product stock: 10 units
- User logged in as Store Keeper
- Negative stock disabled in settings

**Test Steps:**
1. Create delivery order for 15 units
2. Attempt to validate
3. Observe error handling

**Expected Results:**
- [ ] System prevents validation
- [ ] Error message: "Insufficient stock"
- [ ] Available quantity displayed: 10
- [ ] Required quantity highlighted: 15
- [ ] Force availability option (with warning) for admins

**Post-conditions:**
- No negative stock created
- Delivery remains in waiting state

---

#### TC-INV-012: Stock Aging Report

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INV-012 |
| **Title** | Verify Stock Aging Analysis |
| **Priority** | Medium |

**Preconditions:**
- Multiple batches with different ages
- User logged in as Inventory Manager

**Test Steps:**
1. Navigate to Inventory > Reporting > Stock Aging
2. Select aging buckets: 0-7 days, 8-14 days, 15-30 days, 30+ days
3. Generate report
4. Review aging distribution

**Expected Results:**
- [ ] Report categorized by age buckets
- [ ] Value shown for each bucket
- [ ] Risk highlighting for old stock
- [ ] Product-wise breakdown available
- [ ] Warehouse-wise breakdown available

**Post-conditions:**
- Aging report available for management review

---

#### TC-INV-013: Barcode Scanning for Operations

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INV-013 |
| **Title** | Verify Barcode Scanning in Stock Operations |
| **Priority** | Medium |

**Preconditions:**
- Barcode labels printed
- Scanner connected
- User logged in as Store Keeper

**Test Steps:**
1. Open receiving operation
2. Scan product barcode
3. Scan lot number barcode
4. Enter quantity via scanner
5. Complete operation

**Expected Results:**
- [ ] Product auto-identified from barcode
- [ ] Lot number populated
- [ ] Quantity field focused
- [ ] Operation completes with scan confirmation

**Post-conditions:**
- Fast, accurate data entry
- Reduced manual entry errors

---

#### TC-INV-014: Product Category with Accounts Mapping

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INV-014 |
| **Title** | Verify Accounting Integration for Categories |
| **Priority** | High |

**Preconditions:**
- Chart of accounts configured
- User logged in as Finance Manager

**Test Steps:**
1. Navigate to Inventory > Configuration > Product Categories
2. Create category: "Fresh Dairy Products"
3. Map accounts:
   - Stock Valuation Account: 110100 - Raw Materials
   - Stock Input Account: 110101 - Goods In Transit
   - Stock Output Account: 510100 - Cost of Goods Sold
   - Income Account: 410100 - Sales Revenue
   - Expense Account: 610100 - Inventory Loss
4. Save category
5. Create product in this category

**Expected Results:**
- [ ] Category created with account mappings
- [ ] Product inherits account mappings
- [ ] Stock moves generate correct journal entries
- [ ] Sales generate correct revenue entries

**Post-conditions:**
- Accounting integration working
- Correct financial postings

---

#### TC-INV-015: Physical Inventory Count - Cycle Counting

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INV-015 |
| **Title** | Verify Cycle Count Process |
| **Priority** | Medium |

**Preconditions:**
- Cycle count schedule configured
- ABC classification done
- User logged in as Inventory Manager

**Test Steps:**
1. Generate cycle count list for today
2. Review products to count (A items: monthly)
3. Print count sheets
4. Perform physical count
5. Enter count results
6. Review variances
7. Approve adjustments

**Expected Results:**
- [ ] Count list generated by ABC classification
- [ ] Count sheets with locations
- [ ] Variance report: System vs Physical
- [ ] Variance tolerance checking
- [ ] Approval workflow for adjustments
- [ ] Count history maintained

**Post-conditions:**
- Inventory accuracy improved
- Audit trail complete

---

## 4. PURCHASE MANAGEMENT TEST CASES

### 4.1 Module Overview

The Purchase Management module handles supplier management, request for quotations, purchase orders, goods receipt, and supplier evaluation with support for multi-currency and Bangladesh-specific procurement compliance.

| Metric | Value |
|--------|-------|
| Total Test Cases | 12 |
| Critical | 3 |
| High | 5 |

---

#### TC-PUR-001: Supplier Creation with Bangladesh Tax Info

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-PUR-001 |
| **Title** | Verify Supplier Registration with BIN |
| **Priority** | High |

**Preconditions:**
- User logged in as Purchase Officer
- Country: Bangladesh configured

**Test Steps:**
1. Navigate to Purchase > Orders > Vendors
2. Click "Create" new vendor
3. Enter company details:
   - Company Name: ABC Feed Suppliers Ltd.
   - Address: 123 Mirpur, Dhaka-1216
   - Country: Bangladesh
4. Enter Bangladesh-specific tax info:
   - Business Identification Number (BIN): 123456789012
   - VAT Registration: Yes
   - TIN: 987654321098
5. Add contact person:
   - Name: Mr. Karim Ahmed
   - Phone: +8801712345678
   - Email: karim@abcfeed.com
6. Set payment terms: Net 30
7. Configure price list: Feed Products
8. Save vendor

**Expected Results:**
- [ ] Vendor created with unique ID: V/2026/0001
- [ ] BIN validated (12 digits)
- [ ] VAT status recorded
- [ ] Contact linked to company
- [ ] Vendor available in RFQ/PO

**Post-conditions:**
- Supplier ready for procurement transactions

---

#### TC-PUR-002: Request for Quotation (RFQ) Creation

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-PUR-002 |
| **Title** | Verify RFQ Creation and Sending |
| **Priority** | High |

**Preconditions:**
- Products configured
- Suppliers registered
- User logged in as Purchase Officer

**Test Steps:**
1. Navigate to Purchase > Orders > Requests for Quotation
2. Click "Create" new RFQ
3. Select vendors (3 suppliers for comparison)
4. Add product lines:
   - Wheat Bran: 5000 kg
   - Mustard Oil Cake: 2000 kg
   - Rice Polish: 3000 kg
5. Set deadline: 7 days from today
6. Add terms and conditions
7. Send RFQ by email
8. Track responses

**Expected Results:**
- [ ] RFQ created: RFQ/2026/0001
- [ ] Email sent to all 3 vendors
- [ ] RFQ status: "Sent"
- [ ] Response tracking enabled
- [ ] Portal link included for online response

**Post-conditions:**
- Vendors receive RFQ
- Waiting for quotations

---

#### TC-PUR-003: Purchase Order with Multi-Currency

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-PUR-003 |
| **Title** | Verify USD Purchase Order |
| **Priority** | High |

**Preconditions:**
- Foreign supplier configured
- USD currency enabled
- Exchange rate updated
- User logged in as Purchase Officer

**Test Steps:**
1. Navigate to Purchase > Orders > Purchase Orders
2. Create new PO
3. Select vendor: International Dairy Equipment Ltd. (USD)
4. Currency auto-populates: USD
5. Add product: Milking Machine Spare Parts
   - Qty: 2 units
   - Unit Price: $1,500.00
6. Review exchange rate: 1 USD = 110 BDT
7. Check BDT equivalent: ৳330,000
8. Confirm order

**Expected Results:**
- [ ] PO created in USD
- [ ] Exchange rate displayed
- [ ] BDT equivalent calculated
- [ ] Total in USD: $3,000.00
- [ ] Total in BDT: ৳330,000
- [ ] Currency conversion logged

**Post-conditions:**
- PO ready for approval
- FX exposure recorded

---

#### TC-PUR-004: Purchase Approval Workflow

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-PUR-004 |
| **Title** | Verify Multi-Level PO Approval |
| **Priority** | Critical |

**Preconditions:**
- Approval rules configured:
  - < ৳50,000: Department Head
  - ৳50,000 - ৳200,000: Finance Manager
  - > ৳200,000: Managing Director
- PO created for ৳250,000

**Test Steps:**
1. Create PO for 250,000 BDT
2. Submit for approval
3. Review approval notification as Finance Manager
4. Approve/Reject
5. Check MD approval requirement
6. Final approval

**Expected Results:**
- [ ] PO status: "To Approve"
- [ ] Finance Manager receives notification
- [ ] Approval comment required
- [ ] After FM approval: "Partially Approved"
- [ ] MD receives notification
- [ ] After MD approval: "Approved"
- [ ] Rejection option with reason

**Post-conditions:**
- Approved PO ready to send

---

#### TC-PUR-005: Goods Receipt with Quality Check

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-PUR-005 |
| **Title** | Verify Receipt with QC Hold |
| **Priority** | Critical |

**Preconditions:**
- PO confirmed and sent
- Goods arrived at warehouse
- User logged in as Store Keeper

**Test Steps:**
1. Open incoming shipment from PO
2. Verify received quantities
3. Record lot numbers
4. Set quality check: "Pending"
5. Move to QC Hold location
6. QC approves
7. Move to regular stock

**Expected Results:**
- [ ] Receipt recorded
- [ ] Stock in QC Hold (not available for sale)
- [ ] QC task created
- [ ] After approval, stock moved
- [ ] Available for operations

**Post-conditions:**
- Quality verified stock available

---

#### TC-PUR-006: Supplier Bill Creation with VAT

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-PUR-006 |
| **Title** | Verify Vendor Bill with 15% VAT |
| **Priority** | High |

**Preconditions:**
- Goods received
- Supplier invoice received
- User logged in as Finance Manager

**Test Steps:**
1. Navigate to Purchase > Orders > Vendor Bills
2. Create bill from PO
3. Enter supplier invoice details:
   - Invoice Number: INV-ABC-2026-001
   - Date: 31/01/2026
4. Verify line items from PO
5. Apply 15% VAT:
   - Subtotal: ৳100,000
   - VAT (15%): ৳15,000
   - Total: ৳115,000
6. Match with receipt
7. Confirm bill

**Expected Results:**
- [ ] Bill created with PO reference
- [ ] VAT calculated correctly (15%)
- [ ] Accounting entry:
  - Dr. Inventory: ৳100,000
  - Dr. Input VAT: ৳15,000
  - Cr. Accounts Payable: ৳115,000
- [ ] Receipt matched

**Post-conditions:**
- Bill ready for payment
- VAT recorded for return

---

#### TC-PUR-007: Purchase Return to Supplier

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-PUR-007 |
| **Title** | Verify Purchase Return Process |
| **Priority** | Medium |

**Preconditions:**
- Goods received with defects
- Return authorization from supplier
- User logged in as Purchase Officer

**Test Steps:**
1. Create return from receipt
2. Select products to return
3. Specify reason: "Damaged"
4. Create vendor credit note
5. Process return shipment
6. Receive credit note

**Expected Results:**
- [ ] Return authorization created
- [ ] Stock reduced
- [ ] Credit note generated
- [ ] Accounting entry reversed
- [ ] Supplier performance updated

**Post-conditions:**
- Return processed
- Credit applied

---

#### TC-PUR-008: Blanket Order with Release

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-PUR-008 |
| **Title** | Verify Blanket PO with Releases |
| **Priority** | Medium |

**Preconditions:**
- Annual contract with supplier
- User logged in as Purchase Officer

**Test Steps:**
1. Create blanket order:
   - Total Qty: 50,000 kg feed
   - Price: ৳45/kg
   - Valid: 1 year
2. Create release #1: 5,000 kg
3. Create release #2: 3,000 kg
4. Track remaining quantity

**Expected Results:**
- [ ] Blanket order shows total and consumed
- [ ] Releases linked to blanket
- [ ] Remaining: 42,000 kg
- [ ] Price locked from blanket

**Post-conditions:**
- Contract purchasing managed
- Price protection active

---

#### TC-PUR-009: Supplier Performance Evaluation

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-PUR-009 |
| **Title** | Verify Supplier Rating System |
| **Priority** | Medium |

**Preconditions:**
- Multiple deliveries completed
- User logged in as Purchase Manager

**Test Steps:**
1. Navigate to Purchase > Reporting > Supplier Performance
2. Select evaluation period: Last 6 months
3. Review metrics:
   - On-time delivery rate
   - Quality acceptance rate
   - Price competitiveness
   - Responsiveness
4. Rate supplier: 4.5/5
5. Add comments

**Expected Results:**
- [ ] Performance metrics calculated
- [ ] Rating saved
- [ ] Historical trend visible
- [ ] Report exportable

**Post-conditions:**
- Supplier evaluation documented
- Future sourcing informed

---

#### TC-PUR-010: Purchase Analysis Report

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-PUR-010 |
| **Title** | Verify Purchase Spend Analysis |
| **Priority** | Low |

**Preconditions:**
- Purchase history available
- User logged in as Purchase Manager

**Test Steps:**
1. Generate purchase analysis
2. Group by: Product category
3. Filter by: Last quarter
4. Review spend trends

**Expected Results:**
- [ ] Spend by category displayed
- [ ] Top suppliers listed
- [ ] Price variance analysis
- [ ] Export to Excel available

**Post-conditions:**
- Procurement insights available

---

#### TC-PUR-011: Procurement with UoM Conversion

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-PUR-011 |
| **Title** | Verify Purchase UoM vs Stock UoM |
| **Priority** | Medium |

**Preconditions:**
- Product: Milk Cartons
- Purchase UoM: Carton (12 bottles)
- Stock UoM: Bottle
- User logged in as Purchase Officer

**Test Steps:**
1. Create PO for 50 cartons
2. Convert to bottles: 600 bottles
3. Receive partial: 25 cartons
4. Verify stock: +300 bottles

**Expected Results:**
- [ ] PO shows cartons
- [ ] Receipt shows cartons
- [ ] Stock updated in bottles
- [ ] Conversion factor applied

**Post-conditions:**
- UoM conversion working
- Stock accurate

---

#### TC-PUR-012: Landed Cost Allocation

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-PUR-012 |
| **Title** | Verify Landed Costs on Imports |
| **Priority** | High |

**Preconditions:**
- Import shipment received
- Additional costs: Freight, Insurance, Duty
- User logged in as Finance Manager

**Test Steps:**
1. Open receipt for imported goods
2. Add landed costs:
   - Freight: ৳25,000
   - Insurance: ৳5,000
   - Customs Duty: ৳50,000
3. Allocate by value
4. Update product costs

**Expected Results:**
- [ ] Landed costs recorded
- [ ] Allocated to products
- [ ] Updated cost price calculated
- [ ] Accounting entries created

**Post-conditions:**
- True product cost captured
- COGS accurately calculated

---

## 5. SALES MANAGEMENT TEST CASES

### 5.1 Module Overview

The Sales Management module manages the complete sales cycle from lead generation to customer payment, supporting B2B wholesale operations, B2C retail, and subscription-based milk delivery with integrated CRM functionality.

| Metric | Value |
|--------|-------|
| Total Test Cases | 12 |
| Critical | 3 |
| High | 5 |

---

#### TC-SAL-001: Customer Creation with Credit Limit

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SAL-001 |
| **Title** | Verify B2B Customer with Credit Terms |
| **Priority** | High |

**Preconditions:**
- User logged in as Sales Manager
- Customer pricing tiers configured

**Test Steps:**
1. Navigate to Sales > Orders > Customers
2. Create new customer:
   - Company: Green Mart Supermarket
   - Type: B2B Wholesale
   - BIN: 987654321098
3. Configure business terms:
   - Credit Limit: ৳500,000
   - Payment Terms: Net 15
   - Price List: Wholesale Tier 1
4. Add multiple delivery addresses
5. Add contacts
6. Save customer

**Expected Results:**
- [ ] Customer created: C/2026/0001
- [ ] Credit limit set
- [ ] Payment terms configured
- [ ] Multiple addresses saved
- [ ] Credit utilization tracking active

**Post-conditions:**
- Customer ready for sales transactions
- Credit monitoring active

---

#### TC-SAL-002: Sales Quotation to Order Conversion

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SAL-002 |
| **Title** | Verify Quote to Sales Order Flow |
| **Priority** | High |

**Preconditions:**
- Customer inquiry received
- Products priced
- User logged in as Sales Officer

**Test Steps:**
1. Create quotation for customer
2. Add products with quantities
3. Apply discount: 5%
4. Send quotation to customer
5. Customer approves via portal
6. Convert quotation to sales order
7. Confirm order

**Expected Results:**
- [ ] Quotation created: QT/2026/0001
- [ ] Email sent to customer
- [ ] Customer approval received
- [ ] SO created: SO/2026/0001
- [ ] SO linked to quotation
- [ ] Inventory reserved

**Post-conditions:**
- Sales order confirmed
- Delivery process initiated

---

#### TC-SAL-003: B2B Bulk Order with Tier Pricing

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SAL-003 |
| **Title** | Verify Volume-Based Tier Pricing |
| **Priority** | High |

**Preconditions:**
- Customer: Wholesale Tier 1
- Tier pricing configured:
  - 1-50 cartons: ৳85/carton
  - 51-100 cartons: ৳80/carton
  - 101+ cartons: ৳75/carton
- User logged in as Sales Officer

**Test Steps:**
1. Create sales order
2. Add 120 cartons Saffron Milk
3. Verify price applied: ৳75/carton
4. Change quantity to 80
5. Verify price updated: ৳80/carton

**Expected Results:**
- [ ] Price auto-calculated by tier
- [ ] 120 cartons @ ৳75 = ৳9,000
- [ ] 80 cartons @ ৳80 = ৳6,400
- [ ] Tier breakdown shown

**Post-conditions:**
- Correct pricing applied
- Margin protected

---

#### TC-SAL-004: Sales Order with Partial Delivery

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SAL-004 |
| **Title** | Verify Partial Shipment Handling |
| **Priority** | Critical |

**Preconditions:**
- Confirmed sales order: 100 cartons
- Stock available: 60 cartons
- User logged in as Warehouse Manager

**Test Steps:**
1. Open delivery order from SO
2. Update delivered quantity: 60
3. Create backorder for 40
4. Process partial delivery
5. Complete first delivery
6. Later deliver remaining 40

**Expected Results:**
- [ ] Partial delivery allowed
- [ ] Backorder auto-created
- [ ] SO status: Partially Delivered
- [ ] Second delivery linked to SO
- [ ] Final status: Fully Delivered

**Post-conditions:**
- Customer receives partial shipment
- Backorder tracked

---

#### TC-SAL-005: Customer Invoice with VAT

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SAL-005 |
| **Title** | Verify Sales Invoice with 15% VAT |
| **Priority** | Critical |

**Preconditions:**
- Goods delivered
- Delivery validated
- User logged in as Finance Manager

**Test Steps:**
1. Create invoice from delivery
2. Verify product lines
3. Apply 15% VAT:
   - Subtotal: ৳100,000
   - VAT (15%): ৳15,000
   - Total: ৳115,000
4. Add payment terms
5. Validate invoice
6. Send to customer

**Expected Results:**
- [ ] Invoice created: INV/2026/0001
- [ ] VAT calculated correctly (15%)
- [ ] Accounting entry:
  - Dr. Accounts Receivable: ৳115,000
  - Cr. Sales Revenue: ৳100,000
  - Cr. Output VAT: ৳15,000
- [ ] Invoice PDF generated

**Post-conditions:**
- Invoice posted
- VAT liability recorded

---

#### TC-SAL-006: Sales Return and Credit Note

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SAL-006 |
| **Title** | Verify Customer Return Process |
| **Priority** | High |

**Preconditions:**
- Customer reports damaged goods
- Return authorization approved
- Goods received back
- User logged in as Sales Manager

**Test Steps:**
1. Create return from original SO
2. Select products to return
3. Specify reason: Damaged
4. Receive goods to warehouse
5. Create credit note
6. Apply to customer account

**Expected Results:**
- [ ] Return authorization created
- [ ] Stock received (may go to scrap)
- [ ] Credit note generated with VAT reversal
- [ ] Customer balance updated
- [ ] Accounting entries reversed

**Post-conditions:**
- Return processed
- Customer credited

---

#### TC-SAL-007: CRM Lead to Opportunity Conversion

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CRM-001 |
| **Title** | Verify Lead Management Pipeline |
| **Priority** | Medium |

**Preconditions:**
- Lead imported from website
- Sales team assigned
- User logged in as Sales Officer

**Test Steps:**
1. Review new lead from website
2. Qualify lead
3. Convert to opportunity
4. Assign probability: 50%
5. Schedule follow-up activities
6. Move through pipeline stages
7. Win/Lost opportunity

**Expected Results:**
- [ ] Lead converted to opportunity
- [ ] Pipeline stage tracked
- [ ] Activities scheduled
- [ ] Revenue forecast updated
- [ ] Win/Loss reasons recorded

**Post-conditions:**
- Sales pipeline updated
- Forecast accurate

---

#### TC-SAL-008: Subscription Sales - Daily Milk Delivery

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SAL-007 |
| **Title** | Verify Subscription Order Creation |
| **Priority** | High |

**Preconditions:**
- Customer wants daily milk delivery
- Subscription products configured
- User logged in as Sales Officer

**Test Steps:**
1. Create subscription order:
   - Product: Daily Fresh Milk 1L
   - Quantity: 2 liters/day
   - Duration: Monthly
   - Price: ৳1,800/month
2. Set delivery schedule: 7-9 AM
3. Set start date
4. Confirm subscription
5. Verify auto-renewal setup

**Expected Results:**
- [ ] Subscription created
- [ ] Daily deliveries scheduled
- [ ] Monthly invoice auto-generated
- [ ] Auto-renewal enabled
- [ ] Pause/Resume option available

**Post-conditions:**
- Subscription active
- Deliveries scheduled

---

#### TC-SAL-009: Multi-Currency Sales Invoice

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SAL-008 |
| **Title** | Verify USD Sales to Export Customer |
| **Priority** | Medium |

**Preconditions:**
- Export customer configured
- USD pricing available
- User logged in as Sales Manager

**Test Steps:**
1. Create sales order for export
2. Currency: USD
3. Add products with USD prices
4. Confirm order
5. Create invoice in USD
6. Record exchange rate

**Expected Results:**
- [ ] SO created in USD
- [ ] Invoice in USD
- [ ] Exchange rate logged
- [ ] BDT equivalent calculated
- [ ] Export documentation flagged

**Post-conditions:**
- Export sale recorded
- FX gain/loss tracked

---

#### TC-SAL-010: Customer Statement of Account

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SAL-009 |
| **Title** | Verify Customer Account Statement |
| **Priority** | Medium |

**Preconditions:**
- Customer has multiple transactions
- User logged in as Finance Manager

**Test Steps:**
1. Navigate to customer record
2. Generate statement of account
3. Select date range: Last 3 months
4. Review transactions:
   - Invoices
   - Payments
   - Credit notes
   - Balance
5. Send to customer

**Expected Results:**
- [ ] Statement generated
- [ ] Opening balance shown
- [ ] All transactions listed
- [ ] Closing balance calculated
- [ ] Aging summary included
- [ ] PDF export available

**Post-conditions:**
- Customer reconciliation facilitated

---

#### TC-SAL-011: Sales Target vs Achievement

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SAL-010 |
| **Title** | Verify Sales Team Performance |
| **Priority** | Low |

**Preconditions:**
- Sales targets set
- Transactions recorded
- User logged in as Sales Manager

**Test Steps:**
1. View sales dashboard
2. Compare target vs actual
3. Review by salesperson
4. Review by product
5. Export report

**Expected Results:**
- [ ] Target achievement % displayed
- [ ] Variance analysis shown
- [ ] Top performers highlighted
- [ ] Trend graphs available

**Post-conditions:**
- Performance tracked
- Commissions calculable

---

#### TC-SAL-012: Promotional Discount Application

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SAL-011 |
| **Title** | Verify Promotional Pricing Rules |
| **Priority** | Medium |

**Preconditions:**
- Promotion: "Eid Special - 10% off"
- Active date range set
- User logged in as Sales Officer

**Test Steps:**
1. Create sales order during promotion
2. Add qualifying products
3. Verify discount auto-applied
4. Check final price
5. Override discount (manager approval)

**Expected Results:**
- [ ] Discount applied automatically
- [ ] 10% reduction calculated
- [ ] Promotion name shown
- [ ] Override logged with reason

**Post-conditions:**
- Promotion tracked
- Margin analyzed

---

## 6. ACCOUNTING & FINANCE TEST CASES

### 6.1 Module Overview

The Accounting & Finance module provides comprehensive financial management with Bangladesh-specific compliance features including 15% VAT, TDS (Tax Deducted at Source), multi-currency handling, and Bangladesh-standard chart of accounts.

| Metric | Value |
|--------|-------|
| Total Test Cases | 18 |
| Critical | 5 |
| High | 7 |

---

#### TC-ACC-001: Chart of Accounts - Bangladesh Standards

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-ACC-001 |
| **Title** | Verify Bangladesh COA Structure |
| **Priority** | Critical |

**Preconditions:**
- ERP Admin logged in
- Bangladesh localization installed

**Test Steps:**
1. Navigate to Accounting > Configuration > Chart of Accounts
2. Review account structure:
   - Class 1: Assets (1xxxx)
   - Class 2: Liabilities (2xxxx)
   - Class 3: Equity (3xxxx)
   - Class 4: Revenue (4xxxx)
   - Class 5: Expenses (5xxxx)
3. Verify key accounts:
   - 110100: Cash and Bank
   - 110200: Accounts Receivable
   - 210100: Accounts Payable
   - 210200: VAT Payable
   - 410100: Sales Revenue
4. Check account types and hierarchies

**Expected Results:**
- [ ] Bangladesh standard COA loaded
- [ ] Account codes follow convention
- [ ] Parent-child relationships correct
- [ ] Account types properly classified
- [ ] Opening balances can be entered

**Post-conditions:**
- COA ready for transactions

---

#### TC-ACC-002: Journal Entry with VAT Split

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-ACC-002 |
| **Title** | Verify Manual Journal with VAT |
| **Priority** | High |

**Preconditions:**
- User logged in as Accountant
- Period open

**Test Steps:**
1. Navigate to Accounting > Accounting > Journal Entries
2. Create new journal entry
3. Add lines:
   - Dr. Office Equipment: ৳100,000
   - Dr. Input VAT (15%): ৳15,000
   - Cr. Bank Account: ৳115,000
4. Reference: Purchase of computers
5. Post entry

**Expected Results:**
- [ ] Journal entry balanced
- [ ] VAT separated correctly
- [ ] Entry posted successfully
- [ ] Account balances updated
- [ ] Audit trail created

**Post-conditions:**
- Transaction recorded
- VAT recoverable tracked

---

#### TC-ACC-003: Bank Reconciliation

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-ACC-003 |
| **Title** | Verify Bank Statement Reconciliation |
| **Priority** | Critical |

**Preconditions:**
- Bank statement imported
- Transactions recorded in system
- User logged in as Accountant

**Test Steps:**
1. Navigate to Accounting > Accounting > Reconciliation
2. Select bank journal: BRAC Bank
3. Import bank statement CSV
4. Match system transactions with bank lines
5. Create missing entries for unmatched items
6. Validate reconciliation

**Expected Results:**
- [ ] Bank balance: ৳500,000
- [ ] System balance: ৳485,000
- [ ] Unmatched items identified
- [ ] Reconciliation difference explained
- [ ] Reconciliation statement generated

**Post-conditions:**
- Books reconciled with bank
- Variance explained

---

#### TC-ACC-004: VAT Return Preparation (Musak 9.1)

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-ACC-004 |
| **Title** | Verify Monthly VAT Return Calculation |
| **Priority** | Critical |

**Preconditions:**
- Month-end closed
- All transactions posted
- User logged in as Finance Manager

**Test Steps:**
1. Navigate to Accounting > Reporting > Bangladesh VAT
2. Select period: January 2026
3. Generate VAT report:
   - Output VAT (Sales): ৳150,000
   - Input VAT (Purchases): ৳100,000
   - Net VAT Payable: ৳50,000
4. Review transaction details
5. Export to Musak 9.1 format

**Expected Results:**
- [ ] Output VAT calculated from sales
- [ ] Input VAT calculated from purchases
- [ ] Net VAT payable/receivable shown
- [ ] Supporting details available
- [ ] NBR format export available

**Post-conditions:**
- VAT return ready for submission

---

#### TC-ACC-005: Multi-Currency Transaction Recording

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-ACC-005 |
| **Title** | Verify Foreign Currency Transaction |
| **Priority** | High |

**Preconditions:**
- Multi-currency enabled
- Exchange rates updated
- User logged in as Accountant

**Test Steps:**
1. Record USD payment:
   - Amount: $1,000
   - Rate: 1 USD = 110 BDT
   - BDT Equivalent: ৳110,000
2. Post transaction
3. Review currency gain/loss

**Expected Results:**
- [ ] Transaction recorded in USD
- [ ] BDT equivalent calculated
- [ ] Exchange rate logged
- [ ] Unrealized gain/loss tracked

**Post-conditions:**
- FX transaction recorded
- Gain/loss monitored

---

#### TC-ACC-006: Accounts Payable Aging Report

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-ACC-006 |
| **Title** | Verify AP Aging Analysis |
| **Priority** | Medium |

**Preconditions:**
- Vendor bills posted
- Partial payments made
- User logged in as Finance Manager

**Test Steps:**
1. Navigate to Accounting > Reporting > AP Aging
2. Generate report as of today
3. Review aging buckets:
   - Current
   - 1-30 days
   - 31-60 days
   - 61-90 days
   - 90+ days
4. Drill down to vendor details

**Expected Results:**
- [ ] Total AP balance shown
- [ ] Aging distribution displayed
- [ ] Overdue items highlighted
- [ ] Vendor-wise breakdown available
- [ ] Export to Excel available

**Post-conditions:**
- Payment priorities identified

---

#### TC-ACC-007: Accounts Receivable Collection Follow-up

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-ACC-007 |
| **Title** | Verify AR Collection Reminders |
| **Priority** | High |

**Preconditions:**
- Customer invoices overdue
- Reminder rules configured
- User logged in as Credit Controller

**Test Steps:**
1. Review overdue invoices report
2. Select customers for reminder
3. Send automated reminder email
4. Schedule follow-up call
5. Record promise to pay

**Expected Results:**
- [ ] Overdue list generated
- [ ] Reminder email sent
- [ ] Activity logged
- [ ] Promise to pay recorded
- [ ] Next follow-up scheduled

**Post-conditions:**
- Collection process active

---

#### TC-ACC-008: TDS (Tax Deducted at Source) Calculation

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-ACC-008 |
| **Title** | Verify TDS on Supplier Payments |
| **Priority** | High |

**Preconditions:**
- Supplier payment exceeding threshold
- TDS rates configured
- User logged in as Accountant

**Test Steps:**
1. Create vendor payment
2. Amount: ৳100,000 (rent)
3. TDS rate: 5%
4. Calculate deduction: ৳5,000
5. Net payment: ৳95,000
6. Record TDS liability

**Expected Results:**
- [ ] TDS calculated automatically
- [ ] TDS certificate reference generated
- [ ] Net payment processed
- [ ] TDS liability recorded
- [ ] Quarterly return data collected

**Post-conditions:**
- TDS compliance maintained

---

#### TC-ACC-009: Financial Statements Generation

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-ACC-009 |
| **Title** | Verify P&L, Balance Sheet, Cash Flow |
| **Priority** | Critical |

**Preconditions:**
- Period closed
- All entries posted
- User logged in as Finance Manager

**Test Steps:**
1. Generate Profit & Loss:
   - Period: January 2026
   - Compare to budget
2. Generate Balance Sheet:
   - As of 31/01/2026
3. Generate Cash Flow Statement
4. Review variances
5. Export reports

**Expected Results:**
- [ ] P&L shows revenue, expenses, net profit
- [ ] Balance sheet balanced: Assets = Liabilities + Equity
- [ ] Cash flow categorized properly
- [ ] Comparative periods available
- [ ] Excel/PDF export successful

**Post-conditions:**
- Financial reports ready

---

#### TC-ACC-010: Budget vs Actual Analysis

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-ACC-010 |
| **Title** | Verify Budget Variance Report |
| **Priority** | Medium |

**Preconditions:**
- Annual budget loaded
- Actual transactions posted
- User logged in as Finance Manager

**Test Steps:**
1. Navigate to Accounting > Reporting > Budget
2. Select budget: 2026 Operating Budget
3. Review variance by account:
   - Budgeted
   - Actual
   - Variance
   - Variance %
4. Drill down to transactions

**Expected Results:**
- [ ] Budget loaded correctly
- [ ] Actuals captured
- [ ] Variances calculated
- [ ] Favorable/Unfavorable flagged
- [ ] Line-item details available

**Post-conditions:**
- Variance analysis complete

---

#### TC-ACC-011: Period End Closing Process

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-ACC-011 |
| **Title** | Verify Month-End Close |
| **Priority** | Critical |

**Preconditions:**
- All transactions entered
- Reconciliations complete
- User logged in as Finance Manager

**Test Steps:**
1. Run pre-close checklist:
   - All invoices posted
   - Bank reconciled
   - Inventory counted
   - Accruals entered
2. Verify no unposted entries
3. Close period: January 2026
4. Lock period for editing

**Expected Results:**
- [ ] Checklist complete
- [ ] No unposted entries
- [ ] Period closed successfully
- [ ] New period auto-opened
- [ ] Historical period locked

**Post-conditions:**
- Period closed
- Reporting finalized

---

#### TC-ACC-012: Fixed Asset Depreciation

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-ACC-012 |
| **Title** | Verify Asset Depreciation Calculation |
| **Priority** | Medium |

**Preconditions:**
- Assets registered
- Depreciation methods set
- User logged in as Accountant

**Test Steps:**
1. Navigate to Accounting > Assets
2. Review asset register
3. Calculate depreciation for period
4. Post depreciation entry
5. Verify net book value

**Expected Results:**
- [ ] Depreciation calculated by method:
  - Straight-line: Equal amounts
  - Declining balance: % on NBV
- [ ] Journal entry created
- [ ] Accumulated depreciation updated
- [ ] NBV calculated correctly

**Post-conditions:**
- Depreciation recorded
- Asset values updated

---

#### TC-ACC-013: Petty Cash Management

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-ACC-013 |
| **Title** | Verify Petty Cash Operations |
| **Priority** | Low |

**Preconditions:**
- Petty cash fund established
- Custodian assigned
- User logged in as Accountant

**Test Steps:**
1. Record petty cash expenses
2. Categorize by expense type
3. Attach receipts
4. Replenish fund
5. Reconcile balance

**Expected Results:**
- [ ] Expenses recorded with details
- [ ] Receipts attached
- [ ] Fund replenishment calculated
- [ ] Balance reconciled

**Post-conditions:**
- Petty cash managed

---

#### TC-ACC-014: Inter-Company Transactions

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-ACC-014 |
| **Title** | Verify Inter-Company Elimination |
| **Priority** | Medium |

**Preconditions:**
- Multiple company setup
- Transaction between entities
- User logged in as Group Accountant

**Test Steps:**
1. Record inter-company sale
2. Record corresponding purchase
3. Generate consolidation
4. Verify elimination entries

**Expected Results:**
- [ ] Inter-company transactions flagged
- [ ] Elimination entries generated
- [ ] Consolidated reports correct

**Post-conditions:**
- Consolidation accurate

---

#### TC-ACC-015: Payment Follow-up Letters

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-ACC-015 |
| **Title** | Verify Automated Dunning Letters |
| **Priority** | Medium |

**Preconditions:**
- Overdue customers identified
- Dunning levels configured
- User logged in as Credit Controller

**Test Steps:**
1. Review overdue accounts
2. Generate dunning letter Level 1
3. After 15 days, Level 2
4. After 30 days, Final Notice
5. Record responses

**Expected Results:**
- [ ] Letters generated by level
- [ ] Escalation automatic
- [ ] Customer responses tracked

**Post-conditions:**
- Collection process documented

---

#### TC-ACC-016: Analytic Accounting

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-ACC-016 |
| **Title** | Verify Cost Center Tracking |
| **Priority** | Medium |

**Preconditions:**
- Analytic accounts created
- User logged in as Accountant

**Test Steps:**
1. Create analytic distribution:
   - Farm Operations: 60%
   - Distribution: 30%
   - Admin: 10%
2. Apply to expense entry
3. Generate analytic report

**Expected Results:**
- [ ] Distribution applied correctly
- [ ] Costs allocated by center
- [ ] Profitability by segment shown

**Post-conditions:**
- Management reporting enabled

---

#### TC-ACC-017: Audit Trail Review

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-ACC-017 |
| **Title** | Verify Complete Audit Trail |
| **Priority** | High |

**Preconditions:**
- Transactions posted
- Audit logging enabled
- User logged in as Auditor

**Test Steps:**
1. Navigate to Accounting > Audit Trail
2. Select date range
3. Review all entries:
   - Create date
   - User
   - Old value
   - New value
4. Export audit log

**Expected Results:**
- [ ] All changes logged
- [ ] User identification
- [ ] Timestamps recorded
- [ ] Before/after values
- [ ] Tamper-evident log

**Post-conditions:**
- Audit compliance maintained

---

#### TC-ACC-018: Bangladesh Tax Calendar Compliance

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-ACC-018 |
| **Title** | Verify Tax Filing Reminders |
| **Priority** | High |

**Preconditions:**
- Tax calendar configured
- Bangladesh tax rules loaded
- User logged in as Finance Manager

**Test Steps:**
1. Review upcoming deadlines:
   - Monthly VAT: 15th of following month
   - Quarterly TDS: 15th of following quarter
   - Annual Income Tax: December 31
2. Verify reminder notifications
3. Generate draft returns

**Expected Results:**
- [ ] Tax calendar populated
- [ ] Reminders sent before deadline
- [ ] Draft returns available
- [ ] Compliance status tracked

**Post-conditions:**
- Tax compliance managed

---

## 7. HUMAN RESOURCES & PAYROLL TEST CASES

### 7.1 Module Overview

The HR & Payroll module manages employee lifecycle, attendance, leave management, and payroll processing with full compliance to Bangladesh Labor Law 2006, including provisions for provident fund, gratuity, and festival bonuses.

| Metric | Value |
|--------|-------|
| Total Test Cases | 14 |
| Critical | 4 |
| High | 5 |

---

#### TC-HR-001: Employee Registration with Bangladesh Info

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HR-001 |
| **Title** | Verify Employee Registration |
| **Priority** | Critical |

**Preconditions:**
- User logged in as HR Manager
- Departments configured

**Test Steps:**
1. Navigate to HR > Employees > Employees
2. Click "Create" new employee
3. Enter personal details:
   - Name: Mohammad Ali
   - Gender: Male
   - Date of Birth: 15/03/1990
   - National ID: 1234567890123
   - Mobile: +8801712345678
   - Email: mohammad.ali@smartdairybd.com
4. Enter employment details:
   - Department: Production
   - Job Position: Senior Technician
   - Joining Date: 01/01/2026
   - Employee ID: SD/2026/0100
5. Enter contract details:
   - Contract Type: Permanent
   - Wage Type: Monthly
   - Basic Salary: ৳35,000
6. Upload documents:
   - NID copy
   - Photo
   - Educational certificates
7. Save employee

**Expected Results:**
- [ ] Employee created with unique ID
- [ ] NID validated (13 digits)
- [ ] Contract linked to employee
- [ ] Documents uploaded
- [ ] User access auto-created (optional)

**Post-conditions:**
- Employee ready for payroll

---

#### TC-HR-002: Attendance Recording - Biometric Integration

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HR-002 |
| **Title** | Verify Biometric Attendance Capture |
| **Priority** | High |

**Preconditions:**
- Biometric device installed
- Integration configured
- User logged in as HR Officer

**Test Steps:**
1. Employee scans fingerprint at 8:00 AM
2. System records check-in
3. Employee scans at 5:00 PM
4. System records check-out
5. Verify attendance log

**Expected Results:**
- [ ] Check-in time recorded: 08:00:15
- [ ] Check-out time recorded: 17:00:30
- [ ] Work hours calculated: 9 hours
- [ ] Late flag if after 8:30 AM
- [ ] Early departure flag if before 5:00 PM

**Post-conditions:**
- Attendance recorded for payroll

---

#### TC-HR-003: Leave Application and Approval Workflow

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HR-003 |
| **Title** | Verify Leave Request Process |
| **Priority** | High |

**Preconditions:**
- Employee has leave balance
- Approval hierarchy configured
- User logged in as Employee

**Test Steps:**
1. Employee submits leave request:
   - Type: Annual Leave
   - From: 10/02/2026
   - To: 12/02/2026
   - Days: 3
   - Reason: Family vacation
2. Manager receives notification
3. Manager approves
4. Leave balance updated

**Expected Results:**
- [ ] Leave request created
- [ ] Manager notified
- [ ] Approval/rejection recorded
- [ ] Leave balance reduced: -3 days
- [ ] Calendar updated

**Post-conditions:**
- Leave approved
- Payroll adjustment if needed

---

#### TC-HR-004: Payroll Processing - Bangladesh Compliance

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HR-004 |
| **Title** | Verify Monthly Payroll Calculation |
| **Priority** | Critical |

**Preconditions:**
- Attendance finalized
- Leaves approved
- User logged in as HR Manager

**Test Steps:**
1. Navigate to Payroll > Payslips
2. Generate payslip batch for January 2026
3. Review calculation for employee:
   - Basic Salary: ৳35,000
   - House Rent (50%): ৳17,500
   - Medical (10%): ৳3,500
   - Conveyance: ৳2,500
   - Gross Salary: ৳58,500
   - Less: Provident Fund (10%): ৳3,500
   - Less: Tax (if applicable)
   - Net Payable: ৳55,000
4. Process payment

**Expected Results:**
- [ ] Gross salary calculated correctly
- [ ] PF deduction: 10% of basic
- [ ] Tax calculated per Bangladesh slab
- [ ] Net salary computed
- [ ] Payslip generated in Bengali & English
- [ ] Bank transfer file generated

**Post-conditions:**
- Payroll processed
- Payments ready

---

#### TC-HR-005: Overtime Calculation

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HR-005 |
| **Title** | Verify OT as per Labor Law |
| **Priority** | High |

**Preconditions:**
- Overtime hours recorded
- Rates configured per Bangladesh law
- User logged in as HR Officer

**Test Steps:**
1. Record overtime:
   - Regular OT (after 8 hours): 10 hours
   - Weekly Holiday OT: 8 hours
   - Festival Holiday OT: 0 hours
2. Calculate OT payment:
   - Regular OT: 10 × (Basic/208) × 2
   - Weekly Holiday: 8 × (Basic/208) × 2
3. Add to payslip

**Expected Results:**
- [ ] OT hours categorized correctly
- [ ] Regular OT at 2x rate
- [ ] Holiday OT at 2x rate
- [ ] Total OT amount calculated
- [ ] OT reflected in payslip

**Post-conditions:**
- OT payment calculated correctly

---

#### TC-HR-006: Provident Fund Management

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HR-006 |
| **Title** | Verify PF Contribution Tracking |
| **Priority** | High |

**Preconditions:**
- Employee completed 1 year
- PF rules configured
- User logged in as HR Manager

**Test Steps:**
1. Review PF contributions:
   - Employee contribution (10%): ৳3,500
   - Employer contribution (10%): ৳3,500
2. View PF statement
3. Calculate accumulated balance
4. Process PF loan application

**Expected Results:**
- [ ] Monthly contributions recorded
- [ ] Interest calculated (if applicable)
- [ ] PF balance available
- [ ] Loan eligibility calculated (50% of balance)
- [ ] Withdrawal on exit calculated

**Post-conditions:**
- PF compliance maintained

---

#### TC-HR-007: Gratuity Calculation

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HR-007 |
| **Title** | Verify Gratuity for Completed Years |
| **Priority** | High |

**Preconditions:**
- Employee completing 5+ years
- User logged in as HR Manager

**Test Steps:**
1. Employee resigns after 7 years
2. Calculate gratuity:
   - Last basic salary: ৳40,000
   - Years of service: 7
   - Gratuity = (Basic × 30 days × 7 years) / 26
   - Gratuity = ৳323,077
3. Process final settlement

**Expected Results:**
- [ ] Gratuity calculated per Labor Law
- [ ] Formula: (Basic × 30 × Years) / 26
- [ ] Amount included in final settlement
- [ ] Accounting entry created

**Post-conditions:**
- Gratuity compliance maintained

---

#### TC-HR-008: Festival Bonus Processing

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HR-008 |
| **Title** | Verify Eid Bonus Calculation |
| **Priority** | Critical |

**Preconditions:**
- Eid approaching
- Bonus policy configured
- User logged in as HR Manager

**Test Steps:**
1. Identify eligible employees
2. Calculate bonus:
   - Basic Salary: ৳35,000
   - Eid Bonus (1 basic): ৳35,000
3. Process bonus payment
4. Generate bonus payslip

**Expected Results:**
- [ ] Eligible employees identified
- [ ] Bonus calculated as 1 basic salary
- [ ] Two Eid bonuses tracked separately
- [ ] Tax treatment applied
- [ ] Payment processed with salary or separately

**Post-conditions:**
- Festival bonus paid

---

#### TC-HR-009: Employee Loan Management

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HR-009 |
| **Title** | Verify Salary Advance and Loan |
| **Priority** | Medium |

**Preconditions:**
- Employee eligible for loan
- User logged in as HR Manager

**Test Steps:**
1. Employee applies for loan: ৳50,000
2. Manager approves
3. Configure repayment:
   - 10 monthly installments
   - ৳5,000 per month
4. Process disbursement
5. Deduct from payroll

**Expected Results:**
- [ ] Loan approved
- [ ] Disbursement recorded
- [ ] Monthly deduction scheduled
- [ ] Balance tracked
- [ ] Interest calculated (if applicable)

**Post-conditions:**
- Loan managed
- Deductions active

---

#### TC-HR-010: Employee Expense Reimbursement

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HR-010 |
| **Title** | Verify Expense Claim Processing |
| **Priority** | Medium |

**Preconditions:**
- Employee incurred business expense
- Receipts available
- User logged in as Employee

**Test Steps:**
1. Submit expense claim:
   - Date: 15/01/2026
   - Category: Travel
   - Amount: ৳2,500
   - Receipt attached
   - Description: Farm visit
2. Manager approves
3. Finance processes payment

**Expected Results:**
- [ ] Expense claim created
- [ ] Receipt attached
- [ ] Approval workflow completed
- [ ] Payment processed
- [ ] Accounting entry created

**Post-conditions:**
- Expense reimbursed

---

#### TC-HR-011: Recruitment Process

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HR-011 |
| **Title** | Verify Job Application to Hiring |
| **Priority** | Medium |

**Preconditions:**
- Job position posted
- Applications received
- User logged in as HR Officer

**Test Steps:**
1. Review applications
2. Shortlist candidates
3. Schedule interviews
4. Record interview feedback
5. Make job offer
6. Convert to employee

**Expected Results:**
- [ ] Applications tracked
- [ ] Interview stages managed
- [ ] Feedback recorded
- [ ] Offer letter generated
- [ ] Seamless employee creation

**Post-conditions:**
- New hire onboarded

---

#### TC-HR-012: Performance Appraisal

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HR-012 |
| **Title** | Verify Employee Evaluation Process |
| **Priority** | Medium |

**Preconditions:**
- Appraisal period ended
- Evaluation criteria defined
- User logged in as Manager

**Test Steps:**
1. Create appraisal for employee
2. Self-assessment by employee
3. Manager assessment
4. Final review meeting
5. Set goals for next period

**Expected Results:**
- [ ] Appraisal form completed
- [ ] Ratings recorded
- [ ] Comments documented
- [ ] Goals set
- [ ] Salary increment recommendation

**Post-conditions:**
- Performance documented
- Development plan created

---

#### TC-HR-013: Employee Offboarding

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HR-013 |
| **Title** | Verify Exit Process and Final Settlement |
| **Priority** | High |

**Preconditions:**
- Employee resigned
- Notice period served
- User logged in as HR Manager

**Test Steps:**
1. Record resignation
2. Create exit checklist:
   - Asset return
   - Clearance from departments
   - Knowledge transfer
3. Calculate final settlement:
   - Unpaid salary
   - Leave encashment
   - Gratuity
   - PF withdrawal
4. Process settlement

**Expected Results:**
- [ ] Exit checklist completed
- [ ] Clearances obtained
- [ ] Final settlement calculated
- [ ] Payment processed
- [ ] Employee record archived

**Post-conditions:**
- Clean exit processed

---

#### TC-HR-014: HR Reports and Analytics

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HR-014 |
| **Title** | Verify HR Dashboard and Reports |
| **Priority** | Medium |

**Preconditions:**
- HR data populated
- User logged in as HR Manager

**Test Steps:**
1. View HR dashboard
2. Generate headcount report
3. Generate attendance summary
4. Generate payroll summary
5. Export reports

**Expected Results:**
- [ ] Dashboard shows key metrics
- [ ] Headcount by department
- [ ] Attendance statistics
- [ ] Payroll summary
- [ ] Export to Excel/PDF

**Post-conditions:**
- HR insights available

---

## 8. MANUFACTURING (MRP) TEST CASES

### 8.1 Module Overview

The Manufacturing (MRP) module supports dairy product production including yogurt, butter, cheese, and packaged milk. It manages Bill of Materials (BOM), production planning, work orders, and yield tracking with quality control integration.

| Metric | Value |
|--------|-------|
| Total Test Cases | 10 |
| Critical | 3 |
| High | 4 |

---

#### TC-MRP-001: Bill of Materials Creation

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-MRP-001 |
| **Title** | Verify BOM for Sweet Yogurt |
| **Priority** | Critical |

**Preconditions:**
- Raw materials defined
- User logged in as Production Manager

**Test Steps:**
1. Navigate to Manufacturing > Products > Bills of Materials
2. Create BOM:
   - Product: Saffron Sweet Yogurt (500g)
   - Quantity: 100 units
3. Add components:
   - Fresh Milk: 55 liters
   - Sugar: 5 kg
   - Yogurt Culture: 0.5 kg
   - Packaging (500g cup): 100 units
   - Label: 100 units
4. Set operations:
   - Mixing: 30 minutes
   - Fermentation: 8 hours
   - Packaging: 45 minutes
5. Save BOM

**Expected Results:**
- [ ] BOM created with version
- [ ] Components quantified
- [ ] Operations defined
- [ ] Cost rollup calculated
- [ ] BOM available for production planning

**Post-conditions:**
- BOM ready for manufacturing

---

#### TC-MRP-002: Production Planning with MRP

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-MRP-002 |
| **Title** | Verify MRP Run and Procurement Suggestions |
| **Priority** | High |

**Preconditions:**
- Sales forecast available
- BOMs defined
- Inventory levels known
- User logged in as Production Planner

**Test Steps:**
1. Run MRP calculation
2. Review MRP exceptions
3. Check procurement suggestions:
   - Shortages
   - Excess inventory
4. Convert suggestions to POs
5. Review production orders suggested

**Expected Results:**
- [ ] MRP explosion completed
- [ ] Net requirements calculated
- [ ] Planned orders created
- [ ] Purchase requisitions generated
- [ ] Action messages displayed

**Post-conditions:**
- Production planned
- Procurement initiated

---

#### TC-MRP-003: Work Order Creation and Execution

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-MRP-003 |
| **Title** | Verify Work Order for Yogurt Production |
| **Priority** | Critical |

**Preconditions:**
- BOM available
- Raw materials in stock
- User logged in as Production Supervisor

**Test Steps:**
1. Create manufacturing order:
   - Product: Sweet Yogurt 500g
   - Quantity: 500 units
   - Planned Date: 01/02/2026
2. Check material availability
3. Confirm production order
4. Print production sheet
5. Issue raw materials

**Expected Results:**
- [ ] MO created: MO/2026/0001
- [ ] Material availability checked
- [ ] Components reserved
- [ ] Production sheet generated with:
  - BOM components
  - Work instructions
  - Quality checkpoints
- [ ] Raw materials issued from stock

**Post-conditions:**
- Production ready to start

---

#### TC-MRP-004: Production with By-Products

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-MRP-004 |
| **Title** | Verify By-Product Recording (Butter Milk) |
| **Priority** | Medium |

**Preconditions:**
- Butter production planned
- By-product defined
- User logged in as Production Supervisor

**Test Steps:**
1. Create MO for Butter production
2. Start production
3. Record main output: Butter 50 kg
4. Record by-product: Mattha 450 liters
5. Complete production order

**Expected Results:**
- [ ] Main product produced
- [ ] By-product recorded
- [ ] Both added to inventory
- [ ] Costs allocated appropriately
- [ ] By-product value credited to production

**Post-conditions:**
- All outputs recorded
- Inventory accurate

---

#### TC-MRP-005: Yield Tracking and Variance

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-MRP-005 |
| **Title** | Verify Production Yield Analysis |
| **Priority** | High |

**Preconditions:**
- Production completed
- Actual quantities recorded
- User logged in as Production Manager

**Test Steps:**
1. Review production order completion
2. Compare planned vs actual:
   - Planned yield: 100%
   - Actual yield: 95%
3. Analyze variance
4. Review loss reasons

**Expected Results:**
- [ ] Yield percentage calculated
- [ ] Variance highlighted
- [ ] Loss reasons categorized:
  - Spillage
  - Quality rejection
  - Process loss
- [ ] Trend analysis available

**Post-conditions:**
- Yield performance tracked
- Improvement opportunities identified

---

#### TC-MRP-006: Quality Control at Production

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-MRP-006 |
| **Title** | Verify In-Process Quality Checks |
| **Priority** | Critical |

**Preconditions:**
- Production in progress
- QC plan defined
- User logged in as QC Inspector

**Test Steps:**
1. Review quality checkpoints from MO
2. Perform fat content test
3. Perform microbial test
4. Record results
5. Pass/Fail decision
6. Release/batch for next operation

**Expected Results:**
- [ ] QC checklist displayed
- [ ] Test results recorded
- [ ] Specifications compared
- [ ] Pass: Continue production
- [ ] Fail: Quarantine, rework, or scrap
- [ ] Certificate of analysis generated

**Post-conditions:**
- Quality verified
- Product released

---

#### TC-MRP-007: Work Center Capacity Planning

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-MRP-007 |
| **Title** | Verify Work Center Load Analysis |
| **Priority** | Medium |

**Preconditions:**
- Work centers defined
- Production orders scheduled
- User logged in as Production Planner

**Test Steps:**
1. View work center calendar
2. Review capacity vs load:
   - Fermentation Room: 8 hours/day capacity
   - Scheduled load: 6 hours
3. Identify bottlenecks
4. Reschedule if needed

**Expected Results:**
- [ ] Capacity displayed
- [ ] Load calculated from MOs
- [ ] Utilization % shown
- [ ] Overload warnings
- [ ] Gantt chart view available

**Post-conditions:**
- Capacity optimized
- Bottlenecks managed

---

#### TC-MRP-008: Batch Manufacturing Record

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-MRP-008 |
| **Title** | Verify Complete Batch Documentation |
| **Priority** | High |

**Preconditions:**
- Production completed
- All data recorded
- User logged in as Production Manager

**Test Steps:**
1. Open completed production order
2. Generate batch manufacturing record
3. Review document contents:
   - Batch number
   - Production date
   - Raw material lots used
   - Process parameters
   - Quality test results
   - Operators involved
4. Sign-off electronically
5. Archive record

**Expected Results:**
- [ ] Complete BMR generated
- [ ] All lots traceable
- [ ] Signatures captured
- [ ] Audit trail complete
- [ ] BFSA compliance documented

**Post-conditions:**
- Regulatory compliance maintained

---

#### TC-MRP-009: Subcontracting Process

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-MRP-009 |
| **Title** | Verify Third-Party Manufacturing |
| **Priority** | Medium |

**Preconditions:**
- Subcontractor configured
- User logged in as Production Manager

**Test Steps:**
1. Create subcontracting PO:
   - Send: Raw milk
   - Receive: Processed cheese
2. Issue raw materials to subcontractor
3. Track work-in-progress
4. Receive finished goods
5. Validate and pay

**Expected Results:**
- [ ] Subcontracting PO created
- [ ] Materials issued tracked
- [ ] Finished goods receipt recorded
- [ ] Subcontractor charges applied
- [ ] Inventory updated

**Post-conditions:**
- Subcontracting completed

---

#### TC-MRP-010: Production Cost Analysis

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-MRP-010 |
| **Title** | Verify Product Costing |
| **Priority** | High |

**Preconditions:**
- Production completed
- Costs captured
- User logged in as Cost Accountant

**Test Steps:**
1. Review production cost report
2. Analyze cost breakdown:
   - Material cost
   - Labor cost
   - Overhead cost
3. Compare standard vs actual
4. Calculate variances

**Expected Results:**
- [ ] Total cost calculated
- [ ] Cost per unit computed
- [ ] Standard vs actual comparison
- [ ] Variance analysis:
  - Material price variance
  - Material usage variance
  - Labor efficiency variance
- [ ] Cost trends visible

**Post-conditions:**
- Product cost known
- Pricing decisions informed

---

## 9. POINT OF SALE (POS) TEST CASES

### 9.1 Module Overview

The Point of Sale (POS) module supports retail store operations with offline capability, integrated payment processing (bKash, Nagad, cards, cash), receipt printing, and seamless integration with inventory and accounting.

| Metric | Value |
|--------|-------|
| Total Test Cases | 12 |
| Critical | 4 |
| High | 5 |

---

#### TC-POS-001: POS Session Opening

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-POS-001 |
| **Title** | Verify Cashier Session Start |
| **Priority** | Critical |

**Preconditions:**
- POS configuration done
- Cashier logged in
- Opening cash: ৳5,000

**Test Steps:**
1. Open POS application
2. Login with cashier credentials
3. Count opening cash: ৳5,000
4. Enter opening balance
5. Start session

**Expected Results:**
- [ ] Session created
- [ ] Opening cash recorded
- [ ] POS ready for sales
- [ ] Session ID assigned

**Post-conditions:**
- Session active
- Sales can begin

---

#### TC-POS-002: Cash Sale Transaction

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-POS-002 |
| **Title** | Verify Complete Cash Sale |
| **Priority** | Critical |

**Preconditions:**
- POS session open
- Products loaded
- Customer ready

**Test Steps:**
1. Scan product: Saffron Milk 1L
   - Quantity: 2
   - Price: ৳95 each
2. Scan product: Sweet Yogurt
   - Quantity: 1
   - Price: ৳120
3. Apply discount: 5%
4. Calculate total: ৳313.25
5. Receive cash: ৳320
6. Give change: ৳6.75
7. Print receipt

**Expected Results:**
- [ ] Products added to cart
- [ ] Prices correct
- [ ] Discount applied
- [ ] Total calculated
- [ ] Change calculated
- [ ] Receipt printed with:
  - Store details
  - Items purchased
  - VAT breakdown
  - Total amount
  - Payment method
  - Thank you message

**Post-conditions:**
- Sale completed
- Inventory updated

---

#### TC-POS-003: bKash Payment Integration

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-POS-003 |
| **Title** | Verify bKash QR Payment |
| **Priority** | Critical |

**Preconditions:**
- bKash merchant account configured
- Internet connected
- Customer has bKash app

**Test Steps:**
1. Complete product selection
2. Select payment: bKash
3. Generate QR code
4. Customer scans with bKash app
5. Customer confirms payment
6. Verify payment confirmation
7. Complete sale

**Expected Results:**
- [ ] QR code generated
- [ ] bKash transaction ID received
- [ ] Payment status: Confirmed
- [ ] Receipt printed
- [ ] Transaction logged

**Post-conditions:**
- bKash payment received

---

#### TC-POS-004: Offline Mode Operation

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-POS-004 |
| **Title** | Verify Offline Sales and Sync |
| **Priority** | Critical |

**Preconditions:**
- POS was online previously
- Internet now disconnected
- Local data cached

**Test Steps:**
1. Disconnect internet
2. Observe offline indicator
3. Create sale transaction
4. Record payment
5. Complete offline sale
6. Reconnect internet
7. Wait for sync

**Expected Results:**
- [ ] Offline mode detected
- [ ] Indicator shows "Offline"
- [ ] Sale processes normally
- [ ] Data saved locally
- [ ] On reconnect: Auto-sync starts
- [ ] Sync completion confirmed
- [ ] All transactions uploaded
- [ ] Inventory updated on server

**Post-conditions:**
- Sales synchronized
- No data loss

---

#### TC-POS-005: Return and Refund

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-POS-005 |
| **Title** | Verify Customer Return at POS |
| **Priority** | High |

**Preconditions:**
- Original sale in system
- Product returnable
- Customer has receipt

**Test Steps:**
1. Select return mode
2. Scan original receipt barcode
3. Select items to return
4. Verify product condition
5. Select refund method:
   - Cash refund
   - bKash refund
   - Store credit
6. Process refund
7. Print return receipt

**Expected Results:**
- [ ] Original sale found
- [ ] Items selected for return
- [ ] Refund amount calculated
- [ ] Refund processed
- [ ] Inventory adjusted
- [ ] Return receipt printed

**Post-conditions:**
- Return completed
- Accounts updated

---

#### TC-POS-006: Multiple Payment Methods

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-POS-006 |
| **Title** | Verify Split Payment |
| **Priority** | High |

**Preconditions:**
- Order total: ৳500
- Customer wants to split payment

**Test Steps:**
1. Complete product selection
2. Select split payment
3. Enter first payment:
   - Method: bKash
   - Amount: ৳300
4. Enter second payment:
   - Method: Cash
   - Amount: ৳200
5. Complete transaction

**Expected Results:**
- [ ] Split payment option available
- [ ] Multiple methods accepted
- [ ] Total matches order amount
- [ ] Each payment recorded separately
- [ ] Single receipt with split details

**Post-conditions:**
- Split payment recorded

---

#### TC-POS-007: Customer Loyalty Points

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-POS-007 |
| **Title** | Verify Loyalty Program at POS |
| **Priority** | Medium |

**Preconditions:**
- Customer registered
- Loyalty program active
- Customer has 500 points

**Test Steps:**
1. Identify customer (phone/QR)
2. Complete purchase: ৳1,000
3. Points earned: 10 (1%)
4. Balance: 510 points
5. Redeem points: 100 = ৳50 discount
6. Final payment

**Expected Results:**
- [ ] Customer identified
- [ ] Points calculated and added
- [ ] Redemption option available
- [ ] Discount applied
- [ ] Updated balance shown

**Post-conditions:**
- Loyalty updated
- Engagement tracked

---

#### TC-POS-008: Daily Sales Closing

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-POS-008 |
| **Title** | Verify End of Day Closing |
| **Priority** | High |

**Preconditions:**
- Sales completed for the day
- Cash in drawer
- User: Cashier + Manager

**Test Steps:**
1. End session
2. Count cash in drawer
3. System shows expected cash
4. Enter counted amount
5. Note any variance
6. Generate Z-report
7. Manager approval
8. Close session

**Expected Results:**
- [ ] Sales summary generated
- [ ] Payment method breakdown
- [ ] Expected cash calculated
- [ ] Variance calculated (if any)
- [ ] Z-report printed:
  - Total sales
  - Returns
  - Discounts
  - Taxes
  - Payment methods
- [ ] Session closed

**Post-conditions:**
- Day closed
- Accounting updated

---

#### TC-POS-009: Product Search and Barcode Scan

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-POS-009 |
| **Title** | Verify Product Lookup |
| **Priority** | Medium |

**Preconditions:**
- Products in database
- Barcode scanner connected

**Test Steps:**
1. Scan barcode
2. Product auto-added
3. Test manual search by name
4. Browse by category
5. Select from favorites

**Expected Results:**
- [ ] Barcode scan instant
- [ ] Search by name works
- [ ] Category browse available
- [ ] Favorites quick access
- [ ] Product image displayed
- [ ] Price shown
- [ ] Stock availability indicated

**Post-conditions:**
- Product found and added

---

#### TC-POS-010: Discount and Promotion Application

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-POS-010 |
| **Title** | Verify Promotional Pricing |
| **Priority** | Medium |

**Preconditions:**
- Promotion active
- Cashier authorized

**Test Steps:**
1. Add promotional product
2. Discount auto-applies
3. Test manual discount (manager override)
4. Apply coupon code
5. Verify final price

**Expected Results:**
- [ ] Auto-discount applied
- [ ] Promotion name shown
- [ ] Manual discount requires authorization
- [ ] Coupon validated
- [ ] Final price correct

**Post-conditions:**
- Discount applied correctly

---

#### TC-POS-011: Hold and Retrieve Sale

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-POS-011 |
| **Title** | Verify Suspend and Resume Sale |
| **Priority** | Medium |

**Preconditions:**
- Sale in progress
- Customer needs to add items

**Test Steps:**
1. Add items to cart
2. Click "Hold Sale"
3. Assign reference number
4. Process other customers
5. Retrieve held sale
6. Complete transaction

**Expected Results:**
- [ ] Sale suspended
- [ ] Reference assigned
- [ ] Other sales can proceed
- [ ] Held sale retrievable
- [ ] Items intact
- [ ] Completion possible

**Post-conditions:**
- Sale completed

---

#### TC-POS-012: POS Integration with Inventory

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-POS-012 |
| **Title** | Verify Real-Time Stock Update |
| **Priority** | High |

**Preconditions:**
- Product stock: 10 units
- Sale of 2 units

**Test Steps:**
1. Check stock before sale: 10
2. Complete sale of 2 units
3. Check stock after sale: 8
4. Verify in backend inventory

**Expected Results:**
- [ ] Stock decremented immediately
- [ ] Backend reflects same quantity
- [ ] Low stock alert if below threshold
- [ ] FEFO lot consumed

**Post-conditions:**
- Inventory synchronized

---

## 10. CROSS-MODULE INTEGRATION TEST CASES

### 10.1 End-to-End Business Process Tests

| Metric | Value |
|--------|-------|
| Total Test Cases | 5 |
| Critical | 2 |
| High | 2 |

---

#### TC-INT-001: Procure to Pay (P2P) Flow

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INT-001 |
| **Title** | Verify Complete Procurement to Payment |
| **Priority** | Critical |

**Test Steps:**
1. Create Purchase Requisition
2. Convert to RFQ
3. Select supplier, create PO
4. Receive goods (Inventory)
5. Receive supplier invoice (Accounting)
6. Match invoice with receipt
7. Process payment
8. Verify GL entries

**Expected Results:**
- [ ] All documents linked
- [ ] Inventory updated on receipt
- [ ] Liability recorded on invoice
- [ ] Payment clears liability
- [ ] Complete audit trail

---

#### TC-INT-002: Order to Cash (O2C) Flow

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INT-002 |
| **Title** | Verify Complete Sales to Collection |
| **Priority** | Critical |

**Test Steps:**
1. Create Sales Quotation
2. Convert to Sales Order
3. Reserve inventory
4. Create Delivery
5. Create Invoice
6. Register Payment
7. Verify GL entries

**Expected Results:**
- [ ] Seamless document flow
- [ ] Inventory reserved then shipped
- [ ] Revenue recognized
- [ ] Cash received
- [ ] Complete audit trail

---

#### TC-INT-003: Production with Inventory and Accounting

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INT-003 |
| **Title** | Verify Manufacturing Integration |
| **Priority** | High |

**Test Steps:**
1. Check raw material availability
2. Create Production Order
3. Consume raw materials
4. Complete production
5. Receive finished goods
6. Verify cost entries

**Expected Results:**
- [ ] Raw materials reserved
- [ ] WIP tracked
- [ ] Finished goods received
- [ ] Costs properly allocated
- [ ] Inventory valuation updated

---

#### TC-INT-004: Payroll to Accounting

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INT-004 |
| **Title** | Verify Payroll Accounting Entries |
| **Priority** | High |

**Test Steps:**
1. Process payroll
2. Verify accounting entries:
   - Salary expense
   - PF payable
   - Tax payable
   - Net salary payable
3. Process payment
4. Reconcile bank

**Expected Results:**
- [ ] All payroll entries posted
- [ ] Liabilities recorded
- [ ] Bank payment reconciled
- [ ] Compliance reports generated

---

#### TC-INT-005: POS to Inventory to Accounting

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INT-005 |
| **Title** | Verify Retail Sale Integration |
| **Priority** | Medium |

**Test Steps:**
1. Complete POS sale
2. Verify inventory reduction
3. Verify revenue recognition
4. Verify cash/bank entry
5. Check VAT recording

**Expected Results:**
- [ ] Sale recorded in POS
- [ ] Inventory updated
- [ ] Revenue booked
- [ ] Cash recorded
- [ ] VAT liability created

---

## 11. BANGLADESH LOCALIZATION TEST CASES

### 11.1 Country-Specific Compliance Tests

| Metric | Value |
|--------|-------|
| Total Test Cases | 5 |
| Critical | 2 |
| High | 2 |

---

#### TC-BD-001: Bengali Language Support

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-BD-001 |
| **Title** | Verify Bengali (Bangla) Interface |
| **Priority** | High |

**Test Steps:**
1. Change user language to Bengali
2. Navigate through modules
3. Verify translations:
   - Menu items
   - Field labels
   - Reports
   - Email templates
4. Test data entry in Bengali
5. Print documents in Bengali

**Expected Results:**
- [ ] Interface in Bengali
- [ ] All labels translated
- [ ] Data entry accepts Bengali text
- [ ] Reports print in Bengali
- [ ] Mixed English-Bengali works

---

#### TC-BD-002: Bangladesh Date Format

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-BD-002 |
| **Title** | Verify DD/MM/YYYY Date Format |
| **Priority** | Medium |

**Test Steps:**
1. Check date display across modules
2. Enter date: 31/01/2026
3. Verify system stores correctly
4. Verify reports show correctly

**Expected Results:**
- [ ] Dates display as DD/MM/YYYY
- [ ] Input accepted in local format
- [ ] No format confusion
- [ ] Fiscal year handling correct

---

#### TC-BD-003: BDT Currency Formatting

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-BD-003 |
| **Title** | Verify Taka (৳) Currency Display |
| **Priority** | Medium |

**Test Steps:**
1. Check currency symbol: ৳
2. Verify number format: 1,00,000.00
3. Check in reports
4. Check on printed documents

**Expected Results:**
- [ ] Taka symbol displayed
- [ ] Indian numbering system (lakhs, crores)
- [ ] Two decimal places
- [ ] Consistent across system

---

#### TC-BD-004: NBR VAT Return Format Export

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-BD-004 |
| **Title** | Verify Musak 9.1 Export |
| **Priority** | Critical |

**Test Steps:**
1. Generate monthly VAT data
2. Export to NBR format
3. Verify fields:
   - Supplier BINs
   - Invoice details
   - VAT amounts
4. Validate file format

**Expected Results:**
- [ ] NBR-compliant format
- [ ] All required fields populated
- [ ] File uploadable to NBR portal
- [ ] Validation errors none

---

#### TC-BD-005: Bangladesh Labor Law Reports

| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-BD-005 |
| **Title** | Verify Compliance Report Generation |
| **Priority** | High |

**Test Steps:**
1. Generate attendance register
2. Generate wage register
3. Generate overtime register
4. Generate leave register
5. Verify compliance formats

**Expected Results:**
- [ ] All registers in prescribed format
- [ ] Required fields present
- [ ] Signatures sections available
- [ ] Export to official format

---

## 12. APPENDICES

### Appendix A: Test Data Requirements

#### A.1 Products

| Product Code | Name | Category | Price | UoM |
|--------------|------|----------|-------|-----|
| SM-ORG-001 | Saffron Organic Milk 1L | Fresh Milk | ৳95 | Liter |
| SM-MAT-001 | Saffron Mattha 1L | Fermented | ৳85 | Liter |
| SM-YOG-001 | Saffron Sweet Yogurt 500g | Yogurt | ৳120 | Unit |
| SM-BUT-001 | Saffron Butter 200g | Butter | ৳180 | Unit |
| SM-BEE-001 | Organic Beef 2KG | Meat | ৳1,200 | Pack |

#### A.2 Customers

| ID | Name | Type | Credit Limit | Terms |
|----|------|------|--------------|-------|
| C001 | Green Mart | B2B | ৳500,000 | Net 15 |
| C002 | City Supermarket | B2B | ৳300,000 | Net 30 |
| C003 | Walk-in Customer | B2C | Cash | Immediate |

#### A.3 Suppliers

| ID | Name | BIN | Terms |
|----|------|-----|-------|
| V001 | ABC Feed Ltd. | 123456789012 | Net 30 |
| V002 | Packaging Solutions | 987654321098 | Net 15 |

#### A.4 Chart of Accounts (Sample)

| Code | Account Name | Type |
|------|--------------|------|
| 110100 | Cash and Bank | Asset |
| 110200 | Accounts Receivable | Asset |
| 110300 | Inventory | Asset |
| 210100 | Accounts Payable | Liability |
| 210200 | VAT Payable | Liability |
| 310100 | Share Capital | Equity |
| 410100 | Sales Revenue | Income |
| 510100 | Cost of Goods Sold | Expense |

### Appendix B: Test Environment Details

| Environment | URL | Database | Purpose |
|-------------|-----|----------|---------|
| Development | https://erp-dev.smartdairybd.com | erp_dev | Unit testing |
| QA | https://erp-qa.smartdairybd.com | erp_qa | Functional testing |
| Staging | https://erp-staging.smartdairybd.com | erp_staging | UAT |
| Production | https://erp.smartdairybd.com | erp_prod | Live |

### Appendix C: VAT Rate Reference

| Category | VAT Rate | Notes |
|----------|----------|-------|
| Standard Rate | 15% | Most goods and services |
| Zero Rated | 0% | Export, essential goods |
| Exempt | - | Specific exemptions |

### Appendix D: Bangladesh Labor Law Key Provisions

| Provision | Requirement | System Compliance |
|-----------|-------------|-------------------|
| Working Hours | 8 hours/day, 48 hours/week | Attendance tracking |
| Overtime | Maximum 4 hours/day, 2x rate | OT calculation |
| Weekly Holiday | 1 day with full pay | Schedule management |
| Annual Leave | 1 day per 18 working days | Leave accrual |
| Sick Leave | 14 days per year | Leave tracking |
| Maternity Leave | 16 weeks (8 before, 8 after) | Leave management |
| Festival Bonus | 2 per year (1 basic each) | Bonus processing |
| Gratuity | After 5 years, 30 days per year | Gratuity calculation |
| Provident Fund | 10% employee + 10% employer | PF tracking |

### Appendix E: Test Sign-off Template

| Module | Test Cases | Passed | Failed | Blocked | Sign-off |
|--------|------------|--------|--------|---------|----------|
| Inventory Management | 15 | | | | |
| Purchase Management | 12 | | | | |
| Sales Management | 12 | | | | |
| Accounting & Finance | 18 | | | | |
| HR & Payroll | 14 | | | | |
| Manufacturing (MRP) | 10 | | | | |
| Point of Sale (POS) | 12 | | | | |
| **TOTAL** | **93** | | | | |

**Sign-off Authority:**

| Role | Name | Signature | Date |
|------|------|-----------|------|
| QA Lead | | | |
| ERP Implementation Lead | | | |
| Finance Manager | | | |
| IT Director | | | |

---

**END OF FUNCTIONAL TEST CASES - ERP CORE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | QA Engineer | Initial version |

---

*This document is proprietary to Smart Dairy Ltd. and confidential. Unauthorized distribution is prohibited.*
