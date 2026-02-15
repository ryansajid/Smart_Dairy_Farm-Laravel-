# Smart Dairy Ltd.
## Data Entry Standards & Procedures

---

| **Document Control** | |
|---------------------|--|
| **Document ID:** | K-018 |
| **Version:** | 1.0 |
| **Date:** | January 31, 2026 |
| **Author:** | Data Manager |
| **Owner:** | QA Lead |
| **Reviewer:** | Operations Manager |
| **Status:** | Approved |
| **Classification:** | Internal Use |

---

## Document History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 31, 2026 | Data Manager | Initial release |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [General Data Entry Principles](#2-general-data-entry-principles)
3. [Data Entry by Module](#3-data-entry-by-module)
4. [Validation Rules](#4-validation-rules)
5. [Common Errors & Prevention](#5-common-errors--prevention)
6. [Batch Data Entry](#6-batch-data-entry)
7. [Data Quality Checks](#7-data-quality-checks)
8. [Training Requirements](#8-training-requirements)
9. [Appendices](#9-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document establishes standardized data entry procedures and quality standards for Smart Dairy Ltd.'s Smart Web Portal System. It ensures data integrity, consistency, and reliability across all business operations, from farm management to financial accounting.

### 1.2 Scope

This document applies to all personnel entering data into the Smart Dairy system, including:
- Farm Managers and Supervisors
- Inventory Personnel
- Sales Representatives
- Accounting Staff
- Administrative Personnel
- Data Entry Operators

### 1.3 Importance of Data Quality

High-quality data is fundamental to Smart Dairy's operations:

| Impact Area | Consequence of Poor Data | Benefit of Quality Data |
|-------------|-------------------------|------------------------|
| **Farm Management** | Incorrect animal records, missed health alerts | Accurate livestock tracking, proactive health management |
| **Inventory** | Stock discrepancies, production delays | Optimal stock levels, efficient procurement |
| **Sales** | Customer dissatisfaction, billing errors | Strong customer relationships, accurate invoicing |
| **Accounting** | Financial misstatements, compliance issues | Reliable financial reporting, audit readiness |
| **Decision Making** | Flawed strategic decisions | Data-driven business insights |

### 1.4 Document Objectives

- Establish uniform data entry standards across all modules
- Define validation rules to prevent errors at the point of entry
- Provide clear guidelines for handling Bengali and English data
- Enable efficient batch data processing
- Ensure regulatory compliance for Bangladesh operations

---

## 2. General Data Entry Principles

### 2.1 Accuracy

**Definition:** Data must correctly represent the real-world values or events it describes.

**Guidelines:**
- Verify all data from source documents before entry
- Double-check numerical values, especially amounts and quantities
- Confirm spelling of names and addresses
- Validate codes against approved reference lists

**Accuracy Targets:**
| Data Type | Target Accuracy Rate | Measurement Method |
|-----------|---------------------|-------------------|
| Financial Data | 99.9% | Monthly reconciliation reports |
| Customer Data | 99.5% | Data quality audits |
| Inventory Data | 99.0% | Physical stock counts |
| Farm Records | 98.5% | Cross-reference with manual logs |

### 2.2 Completeness

**Definition:** All required fields must be populated; no critical data should be missing.

**Guidelines:**
- Never skip mandatory fields (marked with red asterisk *)
- Enter "N/A" or appropriate codes for truly inapplicable fields
- Ensure related records are fully linked (e.g., sales order to customer)
- Complete all sections of multi-part forms

**Completeness Checklist:**
- [ ] All mandatory fields completed
- [ ] Supporting documents attached where required
- [ ] Cross-references established
- [ ] Approval signatures obtained

### 2.3 Timeliness

**Definition:** Data must be entered within specified timeframes to maintain operational relevance.

**Entry Timeframes:**

| Data Category | Maximum Entry Delay | Priority |
|--------------|---------------------|----------|
| Milk Collection Records | Same day | Critical |
| Health Emergency Records | Within 2 hours | Critical |
| Sales Transactions | Same day | High |
| Inventory Movements | Within 24 hours | High |
| General Accounting | Within 3 working days | Medium |
| Historical Analysis Data | Within 1 week | Low |

### 2.4 Consistency

**Definition:** Data should follow standardized formats and conventions throughout the system.

**Consistency Requirements:**
- Use standardized naming conventions
- Apply uniform date and number formats
- Maintain consistent units of measurement
- Follow established coding schemes
- Use approved terminology

---

## 3. Data Entry by Module

### 3.1 Farm Data Entry Standards

#### 3.1.1 Animal Identification Format

**Standard Format:** `SD-YYYY-NNNN`

| Component | Description | Example |
|-----------|-------------|---------|
| SD | Smart Dairy prefix (fixed) | SD |
| YYYY | Year of registration | 2026 |
| NNNN | Sequential 4-digit number | 0001-9999 |

**Example Animal IDs:**
- SD-2026-0001 (First animal registered in 2026)
- SD-2026-0156 (156th animal registered in 2026)
- SD-2025-0892 (Animal registered in 2025)

**Entry Rules:**
- System auto-generates IDs; do not manually override
- For data imports, ensure ID format matches exactly
- IDs are permanent and never reassigned
- Report any duplicate ID immediately

#### 3.1.2 Date Format Standards

**Standard Format:** `DD/MM/YYYY`

| Field Type | Format Example | Validation |
|------------|---------------|------------|
| Birth Date | 15/03/2023 | Cannot be future date |
| Purchase Date | 10/01/2026 | Cannot be before company establishment |
| Health Check Date | 31/01/2026 | Cannot be future date |
| Breeding Date | 25/12/2025 | Must be before expected calving |

**Bengali Calendar Considerations:**
- Primary system uses Gregorian calendar (DD/MM/YYYY)
- Bengali dates may be stored in separate field for cultural reference
- Conversion: System provides automatic Bengali date display
- Example: 31/01/2026 ↔ ১৭ মাঘ ১৪৩২ বঙ্গাব্দ

#### 3.1.3 Milk Quantity Entry

**Standard Unit:** Liters (L)

| Measurement | Decimal Places | Example | Entry Format |
|-------------|---------------|---------|--------------|
| Individual Animal Yield | 2 | 12.35 liters | 12.35 |
| Batch Collection | 2 | 156.50 liters | 156.50 |
| Daily Total | 2 | 1,250.75 liters | 1250.75 |
| Monthly Summary | 2 | 38,450.00 liters | 38450.00 |

**Fat Content Entry:**
- Format: Percentage with 2 decimal places
- Example: 4.25% entered as `4.25`
- Range: 2.00% to 6.00%

**SNF (Solids-Not-Fat) Entry:**
- Format: Percentage with 2 decimal places
- Example: 8.50% entered as `8.50`
- Range: 7.00% to 10.00%

#### 3.1.4 Health Record Standards

**Health Event Types:**

| Code | Event Type | Description |
|------|-----------|-------------|
| VAC | Vaccination | Routine immunization |
| TRE | Treatment | Medical treatment |
| CHK | Checkup | Routine health examination |
| EMG | Emergency | Urgent medical attention |
| INS | Insemination | Artificial insemination |
| CAL | Calving | Birth event |
| DRY | Dry Off | End of lactation |

**Health Record Format:**

| Field | Format | Example |
|-------|--------|---------|
| Event ID | HE-YYYYMMDD-NNN | HE-20260131-001 |
| Animal ID | SD-YYYY-NNNN | SD-2026-0156 |
| Event Date | DD/MM/YYYY | 31/01/2026 |
| Event Type | Code (3 chars) | VAC |
| Description | Free text (max 500 chars) | Routine FMD vaccination |
| Veterinarian | Name or ID | Dr. Rahman / VET-001 |
| Cost | Decimal (2 places) | 150.00 |
| Next Due Date | DD/MM/YYYY | 28/02/2026 |

---

### 3.2 Inventory Data Entry Standards

#### 3.2.1 Product Code Structure

**Format:** `CC-SSS-NNNN`

| Component | Description | Values |
|-----------|-------------|--------|
| CC | Category Code | See table below |
| SSS | Sub-category | Alphanumeric (3 chars) |
| NNNN | Sequential Number | 0001-9999 |

**Category Codes:**

| Code | Category | Examples |
|------|----------|----------|
| FD | Finished Dairy Products | FD-LIQ-0001 (Liquid Milk) |
| RM | Raw Materials | RM-MLK-0001 (Raw Milk) |
| PK | Packaging Materials | PK-BTL-0001 (Bottles) |
| VT | Veterinary Supplies | VT-MED-0001 (Medicines) |
| FE | Feed & Nutrition | FE-CON-0001 (Concentrate) |
| EQ | Equipment & Machinery | EQ-MIL-0001 (Milking Machine) |
| OF | Office Supplies | OF-STN-0001 (Stationery) |

**Example Product Codes:**
- FD-MLK-0001: Pasteurized Full Cream Milk 1L
- FD-YGT-0005: Plain Yogurt 500g
- PK-BTL-0012: 1 Liter Plastic Bottle
- VT-VAC-0034: FMD Vaccine

#### 3.2.2 Unit of Measure Standards

**Standard Units:**

| Category | Unit | Abbreviation | Decimal Places |
|----------|------|--------------|----------------|
| Liquid Products | Liter | L | 2 |
| Liquid Products | Milliliter | ml | 0 |
| Solid Products | Kilogram | kg | 3 |
| Solid Products | Gram | g | 0 |
| Count Items | Piece | pc | 0 |
| Count Items | Box/Case | box | 0 |
| Count Items | Carton | ctn | 0 |

**Unit Conversion Standards:**

| From | To | Conversion Factor | Example |
|------|-----|-------------------|---------|
| L | ml | × 1000 | 1.5 L = 1500 ml |
| kg | g | × 1000 | 2.5 kg = 2500 g |
| box (24 pc) | pc | × 24 | 2 box = 48 pc |
| ctn (12 box) | box | × 12 | 3 ctn = 36 box |

#### 3.2.3 Location Codes

**Warehouse Location Format:** `WH-ZZ-AA-RR-SS`

| Component | Description | Example |
|-----------|-------------|---------|
| WH | Warehouse prefix | WH |
| ZZ | Zone (2 chars) | RM (Raw Materials), FG (Finished Goods) |
| AA | Aisle (2 chars) | A1, A2, B1 |
| RR | Rack/Row (2 chars) | R1, R2, R3 |
| SS | Shelf/Slot (2 chars) | S1, S2 |

**Location Examples:**
- WH-RM-A1-R1-S1: Raw Materials, Aisle A1, Rack 1, Shelf 1
- WH-FG-B2-R3-S2: Finished Goods, Aisle B2, Rack 3, Shelf 2
- WH-VT-C1-R1-S1: Veterinary Supplies, Aisle C1, Rack 1, Shelf 1

#### 3.2.4 Batch Number Format

**Format:** `BBB-YYYYMMDD-NN`

| Component | Description | Example |
|-----------|-------------|---------|
| BBB | Product Code Prefix | FD-MLK |
| YYYYMMDD | Production Date | 20260131 |
| NN | Batch Sequence | 01 |

**Complete Batch Number Example:**
- FD-MLK-20260131-01: First batch of milk produced on 31/01/2026
- FD-YGT-20260130-03: Third batch of yogurt on 30/01/2026

**Batch Entry Requirements:**
- Every production batch must have unique batch number
- Record production date and time
- Capture expiry date (if applicable)
- Link to quality test results
- Track raw material batches used

---

### 3.3 Sales Data Entry Standards

#### 3.3.1 Customer Naming Convention

**Business Customers:**
- Format: `[Business Type] [Business Name]`
- Examples:
  - "Retailer Rahman Store"
  - "Distributor Fresh Foods Ltd"
  - "Hotel Grand Palace"
  - "Cafe Coffee Corner"

**Individual Customers:**
- Format: `[Title] [First Name] [Last Name]`
- Examples:
  - "Mr. Md. Rahman Ahmed"
  - "Mrs. Fatema Begum"
  - "Ms. Sumaiya Islam"

**Naming Rules:**
- Use official registered business names
- Include trade license number for B2B customers
- Maintain consistent spelling across all records
- Avoid abbreviations unless officially registered

#### 3.3.2 Address Format (Bangladesh)

**Standard Address Structure:**

```
[House/Building Number] [Street/Road Name]
[Area/Locality]
[Thana/Upazila]
[City/District] - [Postal Code]
Bangladesh
```

**Field Specifications:**

| Field | Max Length | Example (English) | Example (Bengali) |
|-------|-----------|-------------------|-------------------|
| House/Building | 50 chars | House 12, Road 5 | বাড়ি ১২, রোড ৫ |
| Area/Locality | 50 chars | Dhanmondi | ধানমন্ডি |
| Thana/Upazila | 50 chars | Dhanmondi Thana | ধানমন্ডি থানা |
| District | 50 chars | Dhaka | ঢাকা |
| Division | 50 chars | Dhaka Division | ঢাকা বিভাগ |
| Postal Code | 4 digits | 1205 | ১২০৫ |

**Example Complete Address:**
```
House 45, Road 7, Block C
Mirpur 10
Mirpur Thana
Dhaka - 1216
Bangladesh
```

#### 3.3.3 Phone Number Format

**Bangladesh Phone Number Standards:**

| Type | Format | Example | Validation |
|------|--------|---------|------------|
| Mobile | +880 1XXX-NNNNNN | +880 1712-345678 | 11 digits after country code |
| Mobile (local) | 01XXX-NNNNNN | 01712-345678 | 11 digits |
| Landline | +880 XX-NNNNNNNN | +880 2-91234567 | Include area code |
| Landline (local) | 0XX-NNNNNNNN | 02-91234567 | Include leading 0 |

**Entry Guidelines:**
- Always store with country code (+880) in primary field
- Store local format in secondary field for display
- Remove spaces when storing: `+8801712345678`
- Format with spaces for display: `+880 1712-345678`

#### 3.3.4 Email Validation Rules

**Email Format Requirements:**
- Must contain exactly one @ symbol
- Local part (before @): 1-64 characters
- Domain part (after @): Valid domain format
- Allowed characters: a-z, 0-9, ., -, _
- No consecutive special characters
- Must end with valid TLD (.com, .bd, .org, etc.)

**Validation Regex Pattern:**
```
^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$
```

**Common Email Domains (Bangladesh):**
- @gmail.com
- @yahoo.com
- @hotmail.com
- @outlook.com
- @[company].com.bd
- @[company].bd

---

### 3.4 Accounting Data Entry Standards

#### 3.4.1 Account Codes (Bangladesh Chart of Accounts)

**Account Code Structure:** `X-XX-XX-XXX`

| Level | Code Range | Description |
|-------|-----------|-------------|
| 1st (Category) | 1-9 | Major account category |
| 2nd (Group) | XX | Account group |
| 3rd (Sub-group) | XX | Account sub-group |
| 4th (Ledger) | XXX | Individual ledger account |

**Major Account Categories:**

| Code | Category | Nature |
|------|----------|--------|
| 1 | Assets | Debit |
| 2 | Liabilities | Credit |
| 3 | Equity | Credit |
| 4 | Revenue | Credit |
| 5 | Cost of Goods Sold | Debit |
| 6 | Expenses | Debit |
| 7 | Other Income | Credit |
| 8 | Other Expenses | Debit |

**Smart Dairy Specific Account Codes:**

| Code | Account Name | Type |
|------|-------------|------|
| 1-01-01-001 | Cash on Hand | Current Asset |
| 1-01-02-001 | Bank Account - City Bank | Current Asset |
| 1-02-01-001 | Accounts Receivable | Current Asset |
| 1-03-01-001 | Raw Milk Inventory | Inventory |
| 1-03-01-002 | Finished Goods Inventory | Inventory |
| 1-04-01-001 | Dairy Equipment | Fixed Asset |
| 2-01-01-001 | Accounts Payable | Current Liability |
| 2-02-01-001 | VAT Payable | Tax Liability |
| 4-01-01-001 | Milk Sales Revenue | Revenue |
| 4-01-01-002 | Yogurt Sales Revenue | Revenue |
| 5-01-01-001 | Raw Milk Purchase COGS | COGS |
| 6-01-01-001 | Staff Salaries | Operating Expense |
| 6-01-02-001 | Electricity Expense | Operating Expense |

#### 3.4.2 Transaction Description Standards

**Description Format:** `[Type] - [Reference] - [Details]`

**Sales Transaction:**
- Format: `Sales - INV-000123 - [Customer Name] - [Product]`
- Example: `Sales - INV-000156 - Retailer Rahman Store - Full Cream Milk 1L x 50`

**Purchase Transaction:**
- Format: `Purchase - PO-00045 - [Supplier] - [Item]`
- Example: `Purchase - PO-00067 - Local Farm Supply - Raw Milk 500L`

**Expense Transaction:**
- Format: `Expense - [Category] - [Description]`
- Example: `Expense - Utilities - January Electricity Bill - Dhaka Power`

**Journal Voucher:**
- Format: `JV-[Number] - [Purpose]`
- Example: `JV-00156 - Month-end Depreciation Entry`

#### 3.4.3 Reference Number Standards

| Document Type | Format | Example | Sequence |
|--------------|--------|---------|----------|
| Sales Invoice | INV-YYYY-NNNNN | INV-2026-00123 | Annual reset |
| Purchase Order | PO-YYYY-NNNNN | PO-2026-00045 | Annual reset |
| Goods Receipt | GR-YYYY-NNNNN | GR-2026-00078 | Annual reset |
| Payment Voucher | PV-YYYY-NNNNN | PV-2026-00034 | Annual reset |
| Receipt Voucher | RV-YYYY-NNNNN | RV-2026-00056 | Annual reset |
| Journal Voucher | JV-YYYY-NNNNN | JV-2026-00012 | Annual reset |

#### 3.4.4 VAT Categorization (Bangladesh)

**VAT Rate Categories:**

| Category | Rate | Code | Applicability |
|----------|------|------|---------------|
| Standard Rate | 15% | VAT-15 | Most goods and services |
| Reduced Rate | 10% | VAT-10 | Specific dairy products |
| Zero Rate | 0% | VAT-00 | Export sales |
| Exempt | - | VAT-EX | Essential food items |
| Turnover Tax | 4% | TOT-04 | Small traders |

**VAT Entry Requirements:**

| Field | Format | Example |
|-------|--------|---------|
| VAT Category | Code | VAT-15 |
| VAT Amount | Decimal (2 places) | 150.00 |
| VAT Rate | Percentage | 15% |
| VAT Registration No | XXXXXXXXXXXX (12 digits) | 190510123456 |
| Mushak 6.3 No | YYYY-MM-NNNNNN | 2026-01-001234 |

**Mushak 6.3 (Sales Invoice) Requirements:**
- Must include 12-digit VAT registration number
- Sequential invoice numbering
- Date of supply and invoice date
- Description of goods/services
- Taxable value and VAT amount separately
- Signature of authorized person

---

## 4. Validation Rules

### 4.1 Required Fields

**Global Required Fields (All Modules):**

| Field | Rule | Error Message |
|-------|------|---------------|
| Created Date | Cannot be empty | "Created date is required" |
| Created By | Cannot be empty | "Created by user is required" |
| Company/Branch | Cannot be empty | "Company/Branch is required" |

**Module-Specific Required Fields:**

**Farm Module:**
| Field | Rule | Error Message |
|-------|------|---------------|
| Animal ID | Mandatory | "Animal ID is required" |
| Animal Type | Mandatory | "Select animal type" |
| Birth/Purchase Date | Mandatory | "Date is required" |
| Current Status | Mandatory | "Status is required" |

**Inventory Module:**
| Field | Rule | Error Message |
|-------|------|---------------|
| Product Code | Mandatory | "Product code is required" |
| Product Name | Mandatory | "Product name is required" |
| Unit of Measure | Mandatory | "Unit of measure is required" |
| Location | Mandatory | "Storage location is required" |

**Sales Module:**
| Field | Rule | Error Message |
|-------|------|---------------|
| Customer Name | Mandatory | "Customer name is required" |
| Invoice Date | Mandatory | "Invoice date is required" |
| Product/Service | Mandatory | "At least one item required" |
| Quantity | Mandatory & > 0 | "Valid quantity required" |

**Accounting Module:**
| Field | Rule | Error Message |
|-------|------|---------------|
| Transaction Date | Mandatory | "Transaction date is required" |
| Account Code | Mandatory | "Account code is required" |
| Debit/Credit Amount | Mandatory & = 0 | "Amount must be greater than zero" |
| Description | Mandatory | "Transaction description is required" |

### 4.2 Format Validation

**Text Field Validation:**

| Field Type | Max Length | Allowed Characters | Pattern |
|------------|-----------|-------------------|---------|
| Name | 100 chars | Alphabets, spaces, -, ' | `^[A-Za-z\s\-\']+$` |
| Address | 255 chars | Alphanumeric, spaces, -, /, , | `^[A-Za-z0-9\s\-\/,\.]+$` |
| Description | 1000 chars | All printable characters | None |
| Remarks | 500 chars | All printable characters | None |

**Numeric Field Validation:**

| Field Type | Min Value | Max Value | Decimal Places |
|------------|-----------|-----------|----------------|
| Quantity | 0.001 | 999,999.999 | 3 |
| Amount (BDT) | 0.01 | 999,999,999.99 | 2 |
| Percentage | 0 | 100 | 2 |
| Rate/Price | 0.01 | 999,999.99 | 2 |

**Code Field Validation:**

| Code Type | Format | Regex Pattern | Example |
|-----------|--------|---------------|---------|
| Animal ID | SD-YYYY-NNNN | `^SD-\d{4}-\d{4}$` | SD-2026-0123 |
| Product Code | CC-SSS-NNNN | `^[A-Z]{2}-[A-Z0-9]{3}-\d{4}$` | FD-MLK-0001 |
| Batch Number | CCC-YYYYMMDD-NN | `^[A-Z]{2,3}-\d{8}-\d{2}$` | FD-MLK-20260131-01 |
| Account Code | X-XX-XX-XXX | `^\d{1}-\d{2}-\d{2}-\d{3}$` | 1-01-01-001 |

### 4.3 Range Checks

**Date Range Validation:**

| Field Type | Minimum Date | Maximum Date | Notes |
|------------|-------------|-------------|-------|
| Transaction Date | Company establishment | Today + 7 days | Cannot be in far future |
| Birth Date | 20 years ago | Today | For livestock |
| Expiry Date | Today | Today + 5 years | Product expiry |
| Contract Date | 1 year ago | Today + 1 year | Agreement validity |

**Numeric Range Validation:**

| Field | Minimum | Maximum | Validation |
|-------|---------|---------|------------|
| Milk Fat % | 2.00 | 6.00 | Warn if outside 3.5-4.5 |
| Milk SNF % | 7.00 | 10.00 | Warn if outside 8.0-8.8 |
| Animal Weight (kg) | 10 | 1000 | Context-dependent by type |
| Temperature (°C) | -10 | 50 | Normal range: 0-40 |
| pH Level | 5.0 | 9.0 | Milk pH: 6.5-6.7 |

### 4.4 Cross-Field Validation

**Farm Module Cross-Validations:**

| Validation | Fields | Rule |
|------------|--------|------|
| Calving Date | Breeding Date vs Calving Date | Calving must be 280-290 days after breeding |
| Dry Off Date | Lactation Period | Dry off typically at 305 days of lactation |
| Health Check | Next Due Date | Next check must be after current date |
| Purchase Price | Purchase Date vs Birth Date | Purchase date must be after birth date |

**Inventory Module Cross-Validations:**

| Validation | Fields | Rule |
|------------|--------|------|
| Stock Quantity | Opening + Received - Issued = Closing | Balance validation |
| Expiry Date | Production Date vs Expiry Date | Expiry must be after production |
| Batch Quantity | Batch Qty vs Total Stock | Sum of batch quantities must equal total |
| Location Stock | Location quantities vs Total | Sum of location stocks equals total stock |

**Sales Module Cross-Validations:**

| Validation | Fields | Rule |
|------------|--------|------|
| Invoice Total | Unit Price × Quantity = Line Total | Mathematical accuracy |
| Invoice Total | Sum of Line Totals + VAT = Grand Total | Total calculation |
| Credit Limit | Outstanding + Current Invoice ≤ Credit Limit | Credit control |
| Delivery Date | Order Date vs Delivery Date | Delivery on or after order date |

**Accounting Module Cross-Validations:**

| Validation | Fields | Rule |
|------------|--------|------|
| Journal Balance | Sum of Debits = Sum of Credits | Double-entry balance |
| Bank Reconciliation | Book Balance vs Bank Statement | Difference within tolerance |
| VAT Calculation | Amount × Rate = VAT Amount | Tax calculation accuracy |
| Period Closure | All transactions posted | No unposted entries in closed period |

---

## 5. Common Errors & Prevention

### 5.1 Typo Prevention

**Common Typo Categories:**

| Category | Examples | Prevention Strategy |
|----------|----------|---------------------|
| Transposed Characters | "Recieve" instead of "Receive" | Spell check, dropdown lists |
| Missing Characters | "Accout" instead of "Account" | Auto-complete, validation |
| Extra Characters | "Amountt" instead of "Amount" | Field masking, validation |
| Wrong Similar Keys | "Dgat" instead of "Dhanmondi" | Dropdown selections |
| Homophone Errors | "Their" vs "There" | Context-aware validation |

**Prevention Measures:**

1. **Use Dropdown Lists:**
   - Standardize common entries (cities, districts, product names)
   - Reduce free-text entry where possible

2. **Auto-Complete Functionality:**
   - Suggest based on first few characters
   - Learn from historical entries

3. **Spell Check Integration:**
   - Enable for description fields
   - Custom dictionary for dairy terminology

4. **Field Masking:**
   - Predefined formats for codes and numbers
   - Visual guides for data entry

### 5.2 Duplicate Prevention

**Duplicate Detection Rules:**

| Data Type | Duplicate Check Fields | Action on Detection |
|-----------|----------------------|---------------------|
| Animal ID | Animal ID | Block - Must be unique |
| Customer | Name + Phone + Address | Warn - Review before save |
| Product Code | Product Code | Block - Must be unique |
| Invoice | Invoice Number | Block - Must be unique |
| Supplier | Name + Phone | Warn - Review before save |
| Transaction | Date + Amount + Account | Warn - Possible duplicate |

**Duplicate Prevention Workflow:**

```
1. User enters data
    ↓
2. System checks for duplicates
    ↓
3. IF duplicate found:
   a) Display existing record(s)
   b) Ask user to confirm: "Is this a new entry?"
   c) IF Yes: Proceed with warning
   d) IF No: Cancel and show existing record
    ↓
4. Continue with save process
```

### 5.3 Date Entry Errors

**Common Date Errors:**

| Error Type | Example | Prevention |
|------------|---------|------------|
| Day/Month Swap | 01/02/2026 (intended Feb 1, entered Jan 2) | Date picker, format display |
| Wrong Year | 31/01/2025 (entered previous year) | Default to current year |
| Future Date | 31/12/2027 (accidental future date) | Range validation |
| Leap Year Error | 29/02/2025 (2025 is not leap year) | Calendar validation |
| Invalid Date | 31/02/2026 (February has 28/29 days) | Date validation |

**Date Entry Best Practices:**

1. **Always use date picker** when available
2. **Verify year** before confirming entry
3. **Check day of week** if relevant to business context
4. **Cross-reference** with related dates (e.g., delivery after order)

### 5.4 Number Format Errors

**Common Number Format Errors:**

| Error Type | Example | Correct Format |
|------------|---------|---------------|
| Decimal Point Comma Confusion | 1,234.56 vs 1.234,56 | Use period (.) for decimal |
| Thousand Separator Inclusion | 1,234.56 (stored as text) | Store as 1234.56 |
| Leading Zeros | 001234 | 1234 (unless code) |
| Negative Numbers | -100 for quantity | Validate: quantity must be positive |
| Scientific Notation | 1.23E+4 | 12300 |
| Currency Symbol Inclusion | ৳1,234.56 | 1234.56 (number field) |

**Number Entry Guidelines:**

- **Never include thousand separators** in numeric fields
- **Use period (.)** for decimal separator
- **Enter only digits** and single decimal point
- **Let system format** display with separators and currency

---

## 6. Batch Data Entry

### 6.1 Import Templates

**Available Import Templates:**

| Template | Purpose | File Format |
|----------|---------|-------------|
| Animal Registration | Bulk animal registration | CSV, Excel |
| Milk Production | Daily milk collection data | CSV, Excel |
| Inventory Items | Product master data | Excel |
| Stock Adjustment | Inventory adjustments | CSV, Excel |
| Customer Master | Customer information | Excel |
| Sales Orders | Bulk order entry | CSV, Excel |
| Purchase Orders | Bulk PO creation | CSV, Excel |
| Journal Entries | Accounting entries | CSV, Excel |

### 6.2 CSV Format Specifications

**General CSV Requirements:**

| Specification | Requirement |
|--------------|-------------|
| File Extension | .csv |
| Encoding | UTF-8 (supports Bengali) |
| Delimiter | Comma (,) |
| Text Qualifier | Double quote (") |
| Line Ending | CRLF (Windows) |
| Header Row | Required (first row) |

**CSV Structure Example:**

```csv
AnimalID,AnimalType,BirthDate,Breed,Status,PurchasePrice
SD-2026-0001,Cow,15/03/2023,Holstein Friesian,Active,150000.00
SD-2026-0002,Cow,20/03/2023,Sahiwal,Active,120000.00
SD-2026-0003,Buffalo,10/04/2023,Murrah,Active,180000.00
```

**Date Format in CSV:**
- Use DD/MM/YYYY format
- Examples: 31/01/2026, 15/03/2023
- Do not use Excel auto-format dates

**Number Format in CSV:**
- No thousand separators
- Period (.) for decimal
- Examples: 1234.56, 150000.00

### 6.3 Excel Templates

**Excel File Specifications:**

| Specification | Requirement |
|--------------|-------------|
| File Extension | .xlsx (Excel 2007+) |
| Worksheets | Single worksheet per template |
| Header Row | Row 1 - Must match template |
| Data Start | Row 2 onwards |
| Formatting | Minimal - no merged cells |
| Formulas | Avoid formulas; use values only |
| Validation | Use provided dropdowns |

**Excel Template Structure:**

```
| A           | B            | C          | D              | E        |
|-------------|--------------|------------|----------------|----------|
| AnimalID*   | AnimalType*  | BirthDate* | Breed          | Status*  |
| SD-2026-0001| Cow          | 15/03/2023 | Holstein       | Active   |
| SD-2026-0002| Cow          | 20/03/2023 | Sahiwal        | Active   |
```

**Required Field Marking:**
- Fields marked with asterisk (*) are mandatory
- System will reject rows with missing mandatory fields

### 6.4 Validation Procedures

**Pre-Import Validation:**

| Step | Action | Responsible |
|------|--------|-------------|
| 1 | Download latest template | Data Entry User |
| 2 | Review template instructions | Data Entry User |
| 3 | Prepare data following standards | Data Entry User |
| 4 | Validate data format | Data Entry User |
| 5 | Save in required format | Data Entry User |
| 6 | Run system pre-validation | System |
| 7 | Review validation report | Data Entry User |
| 8 | Correct errors and re-validate | Data Entry User |

**Import Process:**

```
1. Upload File
    ↓
2. System Validation
   - Format check
   - Data type check
   - Range check
   - Duplicate check
    ↓
3. Validation Report
   IF ERRORS:
     - Download error report
     - Correct errors
     - Re-upload
   IF SUCCESS:
     - Proceed to import
    ↓
4. Data Import
   - Insert validated records
   - Generate import log
    ↓
5. Post-Import Verification
   - Review imported records
   - Verify record count
   - Check sample records
```

**Validation Error Categories:**

| Severity | Description | Action |
|----------|-------------|--------|
| Error | Critical issue - Import blocked | Must fix before import |
| Warning | Potential issue - Import allowed | Review recommended |
| Info | Notification only | Review at convenience |

---

## 7. Data Quality Checks

### 7.1 Daily Reconciliation

**Morning Reconciliation (9:00 AM):**

| Check | Description | Owner |
|-------|-------------|-------|
| Previous Day Sales | Verify all sales invoices posted | Sales Admin |
| Previous Day Collections | Match collections with invoices | Accounts |
| Milk Collection Data | Verify all routes entered | Farm Supervisor |
| Inventory Movements | Check production entries | Store Keeper |
| Health Records | Confirm emergency entries | Vet Assistant |

**Evening Reconciliation (5:00 PM):**

| Check | Description | Owner |
|-------|-------------|-------|
| Day's Transactions | All entries for current day | Data Entry |
| Stock Levels | Physical vs System stock | Store Keeper |
| Pending Approvals | Clear approval queue | Department Head |
| Error Log | Review and clear system errors | System Admin |

**Daily Reconciliation Checklist:**

```
☐ All milk collection records entered
☐ All sales invoices generated
☐ All receipts/payments recorded
☐ Inventory transactions posted
☐ Health records updated
☐ No unposted transactions
☐ Error log is empty
☐ Backup completed successfully
```

### 7.2 Weekly Audits

**Weekly Data Quality Audit Schedule:**

| Day | Focus Area | Auditor |
|-----|-----------|---------|
| Monday | Farm Data | Farm Manager |
| Tuesday | Inventory Data | Inventory Controller |
| Wednesday | Sales Data | Sales Manager |
| Thursday | Accounting Data | Finance Manager |
| Friday | System-wide Review | QA Lead |

**Weekly Audit Checklist:**

**Farm Data Audit:**
- [ ] Animal count matches physical count
- [ ] All births/deaths recorded
- [ ] Health check records complete
- [ ] Milk production data consistent
- [ ] Feed consumption records accurate
- [ ] No orphaned records

**Inventory Audit:**
- [ ] Physical stock matches system stock
- [ ] All batch numbers valid
- [ ] Expiry dates correctly entered
- [ ] Location data accurate
- [ ] Movement logs complete
- [ ] No negative stock balances

**Sales Data Audit:**
- [ ] All invoices have valid customers
- [ ] Invoice totals calculated correctly
- [ ] VAT applied correctly
- [ ] Payment allocations accurate
- [ ] No duplicate invoices
- [ ] Credit limits respected

**Accounting Audit:**
- [ ] Journal entries balanced
- [ ] Bank reconciliation current
- [ ] All transactions have supporting docs
- [ ] Period dates correct
- [ ] Account codes valid
- [ ] No orphaned transactions

### 7.3 Monthly Reports

**Monthly Data Quality Report:**

| Metric | Target | Measurement |
|--------|--------|-------------|
| Data Entry Accuracy | >99% | Error rate from validation |
| Timeliness | >95% | Entries within timeframe |
| Completeness | >98% | Mandatory fields filled |
| Duplicate Rate | <0.1% | Duplicates per 1000 records |
| Correction Rate | <2% | Records requiring correction |

**Monthly Report Contents:**

1. **Executive Summary**
   - Overall data quality score
   - Key issues identified
   - Improvement recommendations

2. **Detailed Metrics**
   - Records entered by module
   - Error breakdown by type
   - User performance statistics
   - System performance metrics

3. **Exception Reports**
   - Records requiring attention
   - Out-of-range values
   - Missing critical data
   - Unusual patterns

4. **Trend Analysis**
   - Month-over-month comparison
   - Quality trend direction
   - Seasonal patterns

---

## 8. Training Requirements

### 8.1 Data Entry Training

**Training Levels:**

| Level | Target Audience | Duration | Content |
|-------|-----------------|----------|---------|
| Basic | New Data Entry Operators | 2 days | System navigation, basic entry, validation rules |
| Intermediate | Regular Users (6+ months) | 1 day | Batch entry, error correction, advanced features |
| Advanced | Power Users, Supervisors | 2 days | Import/Export, reporting, troubleshooting, audit |
| Module-Specific | Department Staff | 1 day per module | Module-specific standards and procedures |

**Basic Training Curriculum:**

**Day 1:**
- Morning: System overview, login, navigation
- Afternoon: Basic data entry, field types, validation

**Day 2:**
- Morning: Module-specific entry (based on role)
- Afternoon: Error handling, correction procedures, assessment

**Training Materials:**
- Training manual (this document)
- Video tutorials
- Practice exercises
- Quick reference cards
- Assessment quizzes

### 8.2 Certification

**Certification Levels:**

| Level | Requirements | Validity |
|-------|-------------|----------|
| Certified Data Entry Operator | Complete Basic Training + Pass Assessment (80%) | 1 year |
| Certified Advanced User | Complete Intermediate + Advanced Training + Pass Assessment (85%) | 1 year |
| Certified Module Specialist | Module Training + Practical Assessment (90%) | 2 years |
| Certified Data Quality Auditor | All trainings + Audit Training + Experience (6 months) | 2 years |

**Assessment Components:**

| Component | Weight | Passing Score |
|-----------|--------|---------------|
| Theory Test | 40% | 80% |
| Practical Exercise | 50% | 85% |
| Data Quality Test | 10% | 90% |

**Certification Renewal:**
- Complete refresher training
- Pass renewal assessment
- Demonstrate continued proficiency
- Update on system changes

### 8.3 Refresher Training

**Refresher Training Schedule:**

| User Type | Frequency | Duration | Focus |
|-----------|-----------|----------|-------|
| All Users | Annual | 4 hours | System updates, common errors, best practices |
| High Error Rate Users | Quarterly | 2 hours | Error prevention, targeted coaching |
| New Feature Release | As needed | 1-2 hours | New features, changed processes |
| Compliance Update | As needed | 1 hour | Regulatory changes, new requirements |

**Refresher Training Topics:**

1. **System Updates:**
   - New features and enhancements
   - Changed workflows
   - Deprecated features

2. **Common Error Review:**
   - Most frequent errors from past period
   - Prevention strategies
   - Case studies

3. **Best Practices:**
   - Efficiency tips
   - Keyboard shortcuts
   - Advanced features

4. **Regulatory Updates:**
   - VAT rule changes
   - Reporting requirements
   - Compliance standards

---

## 9. Appendices

### Appendix A: Data Entry Templates

#### A.1 Animal Registration Template

| Field | Format | Example | Required |
|-------|--------|---------|----------|
| Animal Type | Dropdown: Cow/Buffalo/Heifer/Calf/Bull | Cow | Yes |
| Breed | Dropdown/Text | Holstein Friesian | Yes |
| Birth Date | DD/MM/YYYY | 15/03/2023 | Yes |
| Gender | Dropdown: Male/Female | Female | Yes |
| Birth Weight (kg) | Number (2 decimal) | 35.50 | No |
| Color/Markings | Text | Black & White | No |
| Source | Dropdown: Born/Purchased | Born | Yes |
| Dam ID | Animal ID | SD-2024-0056 | If Born |
| Sire ID | Animal ID/Text | HF-1234 | If Known |
| Purchase Date | DD/MM/YYYY | 20/01/2026 | If Purchased |
| Purchase Price (BDT) | Number (2 decimal) | 150000.00 | If Purchased |
| Supplier | Text | Local Dairy Farm | If Purchased |
| Ear Tag Number | Text | ET-2026-123 | No |
| RFID Number | Text | RFID987654321 | No |
| Initial Location | Location Code | FARM-A1 | Yes |
| Entry Notes | Text (500 chars) | Healthy, vaccinated | No |

#### A.2 Milk Collection Template

| Field | Format | Example | Required |
|-------|--------|---------|----------|
| Collection Date | DD/MM/YYYY | 31/01/2026 | Yes |
| Collection Time | HH:MM | 06:30 | Yes |
| Session | Dropdown: Morning/Evening | Morning | Yes |
| Route | Route Code | R-01 | Yes |
| Farmer ID | Farmer Code | FM-0123 | If External |
| Animal ID | Animal ID | SD-2026-0001 | If Internal |
| Quantity (L) | Number (2 decimal) | 12.50 | Yes |
| Fat % | Number (2 decimal) | 4.25 | Yes |
| SNF % | Number (2 decimal) | 8.50 | Yes |
| Temperature (°C) | Number (1 decimal) | 5.5 | No |
| Quality Grade | Dropdown: A/B/C/Reject | A | Yes |
| Collector ID | Staff ID | ST-001 | Yes |

#### A.3 Sales Invoice Template

| Field | Format | Example | Required |
|-------|--------|---------|----------|
| Invoice Date | DD/MM/YYYY | 31/01/2026 | Yes |
| Customer ID | Customer Code | C-00123 | Yes |
| Payment Terms | Dropdown | Cash/7 Days/30 Days | Yes |
| Delivery Date | DD/MM/YYYY | 31/01/2026 | No |
| Sales Rep | Staff ID | ST-005 | Yes |
| Line # | Number | 1 | Auto |
| Product Code | Product Code | FD-MLK-0001 | Yes |
| Quantity | Number | 50 | Yes |
| Unit | Dropdown | L/box/pc | Yes |
| Unit Price (BDT) | Number (2 decimal) | 75.00 | Yes |
| Discount % | Number (2 decimal) | 5.00 | No |
| VAT Category | Code | VAT-15 | Yes |
| Notes | Text | Deliver to back entrance | No |

### Appendix B: Validation Checklists

#### B.1 Pre-Save Validation Checklist

```
☐ All mandatory fields (*) completed
☐ Date formats correct (DD/MM/YYYY)
☐ Numeric values in correct format
☐ Codes match standard formats
☐ No special characters in restricted fields
☐ Text within maximum length limits
☐ Dropdown selections from valid options
☐ Related records properly linked
☐ Calculated fields show correct values
☐ No validation error messages displayed
```

#### B.2 Batch Import Validation Checklist

```
☐ Using latest template version
☐ File format correct (CSV/Excel)
☐ Encoding is UTF-8
☐ Header row matches template
☐ All mandatory columns present
☐ Date format: DD/MM/YYYY throughout
☐ Numbers without thousand separators
☐ No merged cells (Excel)
☐ No formulas, values only
☐ File size within limits (<10MB)
☐ Pre-validation report reviewed
☐ All errors corrected
☐ Sample records verified
```

#### B.3 Monthly Data Quality Checklist

```
☐ Daily reconciliation completed all days
☐ Weekly audits performed as scheduled
☐ All exceptions resolved
☐ No orphaned records in system
☐ Duplicate records reviewed and merged
☐ Invalid/outdated records archived
☐ User access reviewed
☐ Training records updated
☐ Backup and recovery tested
☐ Data quality metrics calculated
☐ Improvement actions identified
```

### Appendix C: Error Correction Procedures

#### C.1 Immediate Error Correction

**For errors caught before save:**

1. Read error message carefully
2. Identify the problematic field
3. Clear incorrect value
4. Enter correct value following standards
5. Verify no other errors
6. Save record

**For errors caught after save (same day):**

1. Open the record in edit mode
2. Make necessary corrections
3. Add correction note in remarks: "[DD/MM/YYYY] Corrected [field] from [old] to [new] - [Initials]"
4. Save corrected record
5. Notify supervisor if significant

#### C.2 Historical Error Correction

**Procedure for correcting errors after day-end:**

1. **Identify the Error:**
   - Document incorrect value
   - Determine correct value
   - Assess impact on related records

2. **Request Authorization:**
   - Submit correction request form
   - Explain reason for correction
   - Provide supporting documentation
   - Obtain supervisor approval

3. **Perform Correction:**
   - Create adjustment entry (preferred method)
   - OR edit with proper authorization and audit trail
   - Document correction details

4. **Verification:**
   - Review corrected record
   - Verify impact on reports
   - Confirm no new errors introduced

**Correction Request Form:**

| Field | Description |
|-------|-------------|
| Request Date | Date of correction request |
| Requested By | User ID and Name |
| Record Type | Module and record type |
| Record ID | Unique identifier |
| Field to Correct | Specific field name |
| Current Value | Incorrect value |
| Proposed Value | Correct value |
| Reason for Correction | Detailed explanation |
| Supporting Documents | List of evidence |
| Impact Assessment | Effect on other records/reports |
| Requested By Signature | |
| Supervisor Approval | Name, Date, Signature |
| Correction Date | When correction made |
| Corrected By | User ID who performed correction |

#### C.3 Mass Correction Procedures

**When multiple records require correction:**

1. **Assess Scope:**
   - Count affected records
   - Determine root cause
   - Estimate correction time

2. **Prepare Correction File:**
   - Export affected records
   - Mark records needing correction
   - Prepare corrected values
   - Create backup before changes

3. **Review and Approve:**
   - Submit mass correction plan
   - Get management approval
   - Schedule during low-activity period

4. **Execute Mass Correction:**
   - Use batch update feature
   - OR prepare import file with corrections
   - Verify corrections after execution

5. **Post-Correction:**
   - Generate audit report
   - Notify affected users
   - Update procedures if needed

### Appendix D: Sample Data Formats

#### D.1 Sample Animal Records

```
Animal ID: SD-2026-0001
Animal Type: Cow
Breed: Holstein Friesian
Birth Date: 15/03/2023
Gender: Female
Current Status: Lactating
Current Location: FARM-A1-S1
Current Lactation: 2
Last Calving Date: 10/01/2026
Expected Dry Date: 15/11/2026
```

```
Animal ID: SD-2026-0056
Animal Type: Buffalo
Breed: Murrah
Birth Date: 01/05/2022
Gender: Female
Current Status: Pregnant
Current Location: FARM-A2-S3
Last Calving Date: 20/06/2025
Expected Calving Date: 15/04/2026
Pregnancy Confirm Date: 15/07/2025
```

#### D.2 Sample Inventory Records

```
Product Code: FD-MLK-0001
Product Name: Pasteurized Full Cream Milk
Variant: 1 Liter Pouch
Category: Finished Goods - Liquid Milk
Unit of Measure: Liter (L)
Stock Quantity: 2,500.00
Reorder Level: 500.00
Location: WH-FG-A1-R1-S1
Batch: FD-MLK-20260131-01
Production Date: 31/01/2026
Expiry Date: 05/02/2026
Unit Cost: 55.00 BDT
Selling Price: 75.00 BDT
```

```
Product Code: PK-BTL-0012
Product Name: Plastic Milk Bottle 1L
Category: Packaging Materials
Unit of Measure: Piece (pc)
Stock Quantity: 5,000
Reorder Level: 1,000
Location: WH-PK-B1-R2-S1
Supplier: Bangladesh Plastics Ltd
Last Purchase Price: 8.50 BDT
```

#### D.3 Sample Sales Records

```
Invoice Number: INV-2026-00156
Invoice Date: 31/01/2026
Customer: Retailer Rahman Store
Customer ID: C-00156
Address: Shop 12, Market Road, Dhanmondi, Dhaka-1205
Phone: +880 1712-345678
Sales Rep: Mr. Karim Ahmed

Line Items:
1. Full Cream Milk 1L - 50 units @ 75.00 = 3,750.00
2. Yogurt 500g - 30 units @ 60.00 = 1,800.00
3. Ghee 500g - 10 units @ 450.00 = 4,500.00

Subtotal: 10,050.00
Discount (5%): 502.50
Taxable Amount: 9,547.50
VAT (15%): 1,432.13
Total Amount: 10,979.63

Payment Terms: 7 Days
Due Date: 07/02/2026
Status: Pending Payment
```

#### D.4 Sample Accounting Entries

```
Journal Voucher: JV-2026-00056
Date: 31/01/2026
Description: Month-end depreciation entry for January 2026

Debit: 6-05-01-001 (Depreciation Expense) - 25,000.00
Credit: 1-04-02-001 (Accumulated Depreciation - Equipment) - 25,000.00

Posted By: Accounts Manager
Posted Date: 31/01/2026
```

```
Payment Voucher: PV-2026-00034
Date: 30/01/2026
Payee: Local Farm Supply
Description: Purchase of raw milk - Batch RM-20260130-01

Debit: 5-01-01-001 (Raw Milk Purchase COGS) - 45,000.00
Debit: 2-02-01-001 (VAT Payable) - 6,750.00
Credit: 1-01-02-001 (Bank - City Bank) - 51,750.00

Reference: GR-2026-00078, PO-2026-00045
```

### Appendix E: Bengali Data Entry Guidelines

#### E.1 Bengali Text Entry

**Character Encoding:**
- Always use UTF-8 encoding
- System supports Bengali Unicode (Bangla script)
- Fonts: Use system default (SolaimanLipi, Siyam Rupali, etc.)

**Bengali Numerals:**

| English | Bengali |
|---------|---------|
| 0 | ০ |
| 1 | ১ |
| 2 | ২ |
| 3 | ৩ |
| 4 | ৪ |
| 5 | ৫ |
| 6 | ৬ |
| 7 | ৭ |
| 8 | ৮ |
| 9 | ৯ |

**Entry Rule:** Use English numerals (0-9) in data entry fields. System will display Bengali numerals where appropriate.

#### E.2 Bilingual Field Guidelines

| Field Type | Primary Language | Secondary Language | Notes |
|------------|------------------|-------------------|-------|
| Product Name | English | Bengali | Both required for customer-facing |
| Customer Name | Bengali preferred | English | Use customer's preference |
| Address | Bengali preferred | English | Complete address in both |
| Descriptions | English | Bengali | Technical in English |
| Legal Documents | Bengali | English | As per official requirements |

#### E.3 Common Bengali Terms for Data Entry

| English | Bengali (Unicode) | Use Case |
|---------|------------------|----------|
| Milk | দুধ | Product names |
| Cow | গাভী | Animal type |
| Buffalo | মহিষ | Animal type |
| Farm | খামার | Location |
| Customer | ক্রেতা | Sales module |
| Supplier | সরবরাহকারী | Purchase module |
| Invoice | চালান | Sales documents |
| Payment | পরিশোধ | Accounting |
| Receipt | রসিদ | Accounting |
| Stock | মজুদ | Inventory |

#### E.4 Keyboard Layout for Bengali

**Recommended Input Methods:**
1. **Avro Keyboard** (Phonetic) - Most popular
2. **Bangla Unicode** (System built-in)
3. **Bijoy** (Legacy - avoid for new data)

**Phonetic Typing Example (Avro):**
- Type "dudh" → দুধ
- Type "goru" → গরু
- Type "khamar" → খামার

**System Configuration:**
- Enable Bengali language support in Windows
- Set up keyboard switching (Alt+Shift or Win+Space)
- Train staff on phonetic typing

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Prepared By | Data Manager | _________________ | _______ |
| Reviewed By | Operations Manager | _________________ | _______ |
| Approved By | QA Lead | _________________ | _______ |

---

## Distribution List

| Department | Copies | Format |
|------------|--------|--------|
| Operations | 1 | Digital + Print |
| IT Department | 1 | Digital |
| Training Department | 2 | Digital + Print |
| QA Department | 1 | Digital + Print |
| All Department Heads | 1 | Digital |

---

*End of Document K-018*

**Document Control:** This document is controlled. Any changes must be approved by the QA Lead and documented in the Document History section.
