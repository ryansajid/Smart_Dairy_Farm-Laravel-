# Document K-008: End User Manual - Accounting

## Smart Dairy Ltd. Smart Web Portal System

---

**Document Control Information**

| Field | Value |
|-------|-------|
| **Document ID** | K-008 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Technical Writer |
| **Owner** | Training Lead |
| **Reviewer** | Finance Manager |
| **Status** | Approved |
| **Classification** | Internal Use |

---

**Approval History**

| Version | Date | Author | Reviewer | Changes |
|---------|------|--------|----------|---------|
| 1.0 | 2026-01-31 | Technical Writer | Finance Manager | Initial Release |

---

**Target Audience**

This manual is designed for:
- Finance Managers and Accountants
- VAT Specialists and Tax Compliance Officers
- Payroll Administrators
- Financial Reporting Analysts
- Internal Auditors
- Accounting Support Staff

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Getting Started](#2-getting-started)
3. [Accounts Payable](#3-accounts-payable)
4. [Accounts Receivable](#4-accounts-receivable)
5. [VAT Management (Bangladesh)](#5-vat-management-bangladesh)
6. [Bank Reconciliation](#6-bank-reconciliation)
7. [Payroll Processing](#7-payroll-processing)
8. [General Ledger](#8-general-ledger)
9. [Financial Reports](#9-financial-reports)
10. [Cost Accounting](#10-cost-accounting)
11. [Year-End Procedures](#11-year-end-procedures)
12. [Troubleshooting](#12-troubleshooting)
13. [Support](#13-support)

---

## 1. Introduction

### 1.1 Manual Overview

This End User Manual provides comprehensive guidance for accounting operations within the Smart Dairy Ltd. Smart Web Portal System. It covers all aspects of financial management, from daily transactions to year-end closing procedures, with special focus on Bangladesh accounting standards and VAT compliance.

### 1.2 Accounting Standards Compliance

Smart Dairy Ltd. follows these accounting standards:

| Standard | Description | Application |
|----------|-------------|-------------|
| **BFRS** | Bangladesh Financial Reporting Standards | Financial statement preparation |
| **ICAB** | Institute of Chartered Accountants of Bangladesh | Professional standards |
| **NBR** | National Board of Revenue | Tax and VAT compliance |
| **Bangladesh Labour Act** | Labour Law 2006 (amended) | Payroll and benefits |
| **Companies Act** | Companies Act 1994 | Corporate compliance |

### 1.3 Key Accounting Policies

| Policy | Description |
|--------|-------------|
| **Fiscal Year** | January 1 - December 31 |
| **Base Currency** | Bangladesh Taka (BDT/৳) |
| **Reporting Currency** | BDT with USD equivalent for international reports |
| **Accounting Method** | Accrual Basis |
| **Inventory Valuation** | Weighted Average Cost |
| **Depreciation Method** | Straight-Line Method |
| **VAT Rate** | 15% Standard Rate |
| **Corporate Tax** | 25% (Manufacturing Company) |

### 1.4 System Modules Overview

```
┌─────────────────────────────────────────────────────────────────┐
│              SMART DAIRY ACCOUNTING MODULES                      │
├─────────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐             │
│  │   General   │  │   Accounts  │  │   Accounts  │             │
│  │   Ledger    │  │   Payable   │  │  Receivable │             │
│  └─────────────┘  └─────────────┘  └─────────────┘             │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐             │
│  │     VAT     │  │    Bank     │  │   Payroll   │             │
│  │  Management │  │Reconciliation│  │  Processing │             │
│  └─────────────┘  └─────────────┘  └─────────────┘             │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐             │
│  │    Cost     │  │   Fixed     │  │   Financial │             │
│  │  Accounting │  │   Assets    │  │   Reports   │             │
│  └─────────────┘  └─────────────┘  └─────────────┘             │
└─────────────────────────────────────────────────────────────────┘
```

---

## 2. Getting Started

### 2.1 System Login

**Step-by-Step Login Procedure:**

1. **Access the Portal**
   - Open web browser (Chrome/Firefox/Edge recommended)
   - Navigate to: `https://accounting.smartdairy.com.bd`
   - Or access via main portal: Dashboard → Accounting Module

2. **Enter Credentials**
   ```
   Username: [Your Employee ID]
   Password: [Your Secure Password]
   ```

3. **Two-Factor Authentication (2FA)**
   - Enter OTP sent to registered mobile/email
   - Or use authenticator app code

4. **Role Selection** (if multiple roles assigned)
   - Select "Accounting" role for full access
   - Select "Viewer" role for read-only access

5. **Dashboard Access**
   - Upon successful login, Accounting Dashboard displays

### 2.2 User Roles and Permissions

| Role | Access Level | Functions |
|------|--------------|-----------|
| **Finance Manager** | Full Access | All modules, reports, configurations |
| **Senior Accountant** | Extended Access | AP, AR, GL, reports, no config changes |
| **Accountant** | Standard Access | Transaction entry, basic reports |
| **VAT Officer** | Specialized Access | VAT modules, returns, compliance reports |
| **Payroll Officer** | Specialized Access | Payroll processing, employee records |
| **Viewer** | Read-Only | View reports, no transaction capability |

### 2.3 Chart of Accounts

The Chart of Accounts follows Bangladesh accounting standards with the following structure:

**Account Code Format:** `X-XXXX-XXX`
- First digit: Account Category (1-9)
- Next four digits: Account Group
- Last three digits: Specific Account

#### 2.3.1 Account Categories

| Code | Category | Range | Example Accounts |
|------|----------|-------|------------------|
| **1** | Assets | 1000-1999 | Cash, Bank, Inventory, Fixed Assets |
| **2** | Liabilities | 2000-2999 | Payables, Loans, Accruals |
| **3** | Equity | 3000-3999 | Share Capital, Retained Earnings |
| **4** | Revenue | 4000-4999 | Sales, Other Income |
| **5** | Cost of Sales | 5000-5999 | Raw Materials, Production Costs |
| **6** | Expenses | 6000-6999 | Operating Expenses |
| **7** | VAT Accounts | 7000-7999 | Input VAT, Output VAT |
| **8** | Payroll Accounts | 8000-8999 | Salaries, Benefits, Deductions |
| **9** | Statistical | 9000-9999 | Budgets, Non-financial |

#### 2.3.2 Key Accounts Reference

**Asset Accounts (1xxx)**

| Code | Account Name | Type | Description |
|------|--------------|------|-------------|
| 1-1001-001 | Cash in Hand | Current Asset | Physical cash |
| 1-1002-001 | BRAC Bank Ltd. | Current Asset | Main operating account |
| 1-1002-002 | Dutch-Bangla Bank | Current Asset | Secondary account |
| 1-1101-001 | Accounts Receivable | Current Asset | Trade debtors |
| 1-1201-001 | Raw Milk Inventory | Current Asset | Milk in storage |
| 1-1201-002 | Finished Goods | Current Asset | Dairy products |
| 1-1501-001 | Buildings | Fixed Asset | Factory buildings |
| 1-1502-001 | Machinery | Fixed Asset | Production equipment |

**Liability Accounts (2xxx)**

| Code | Account Name | Type | Description |
|------|--------------|------|-------------|
| 2-2001-001 | Accounts Payable | Current Liability | Trade creditors |
| 2-2101-001 | VAT Payable | Current Liability | Output VAT liability |
| 2-2101-002 | VAT Input Credit | Current Asset | Input VAT claimable |
| 2-2201-001 | TDS Payable | Current Liability | Tax deducted at source |
| 2-2301-001 | Salaries Payable | Current Liability | Accrued salaries |
| 2-2401-001 | Bank Loan - Short Term | Current Liability | Short-term borrowings |

**VAT Accounts (7xxx)**

| Code | Account Name | Type | Description |
|------|--------------|------|-------------|
| 7-7001-001 | Input VAT - Raw Materials | VAT | VAT on purchases |
| 7-7001-002 | Input VAT - Services | VAT | VAT on services |
| 7-7002-001 | Output VAT - 15% | VAT | VAT on sales |
| 7-7003-001 | VAT Adjustment | VAT | Corrections/rounding |
| 7-7004-001 | Mushak 9.1 Payable | VAT | Monthly VAT liability |

### 2.4 Fiscal Year Configuration

**Fiscal Year Settings:**

```
Current Fiscal Year: 2026
Start Date: January 1, 2026
End Date: December 31, 2026
Periods: 12 Monthly Periods + 1 Adjustment Period
```

**Accounting Periods:**

| Period | Start Date | End Date | Status | Closed |
|--------|------------|----------|--------|--------|
| Period 01 | Jan 1 | Jan 31 | Active | No |
| Period 02 | Feb 1 | Feb 28 | Future | No |
| ... | ... | ... | ... | ... |
| Period 13 | Dec 31 | Dec 31 | Adjustment | No |

**To View/Change Fiscal Year:**
1. Navigate to: General Ledger → Setup → Fiscal Year
2. Select desired fiscal year from dropdown
3. Click "Set as Active"
4. Confirm change in popup dialog

---

## 3. Accounts Payable

### 3.1 Vendor Management

#### 3.1.1 Adding a New Vendor

**Navigation:** Accounts Payable → Vendors → New Vendor

**Step-by-Step Procedure:**

1. **Basic Information Tab**
   ```
   Vendor Code: [Auto-generated or manual V-XXXXX]
   Vendor Name: [Full legal name]
   Trade Name: [If different from legal name]
   Vendor Type: [Manufacturer/Supplier/Service Provider/Contractor]
   Status: Active
   ```

2. **Contact Information**
   ```
   Address Line 1: [Street address]
   Address Line 2: [Area/Thana]
   City: [City name]
   District: [District name]
   Postal Code: [XXXX]
   Country: Bangladesh
   
   Primary Contact: [Name]
   Phone: [+880 XXXX-XXXXXX]
   Email: [email@domain.com]
   ```

3. **Tax Information (Critical for VAT)**
   ```
   BIN (Business Identification Number): [XXXXXXXXXXXX]
   TIN (Taxpayer Identification Number): [XXXXXXXXXXX]
   VAT Registration Date: [DD/MM/YYYY]
   VAT Circle: [Name of VAT circle]
   VAT Rate: 15% (Default)
   ```

4. **Payment Terms**
   ```
   Payment Terms: [Net 30/Net 45/Net 60/Immediate]
   Credit Limit: [Amount in BDT]
   Credit Days: [Number of days]
   Currency: BDT
   ```

5. **Bank Details**
   ```
   Bank Name: [Bank name]
   Branch: [Branch name]
   Account Number: [Account number]
   Account Name: [Account holder name]
   Routing Number: [Routing number]
   ```

6. **Document Upload**
   - Upload VAT registration certificate
   - Upload TIN certificate
   - Upload trade license
   - Upload vendor agreement/contract

7. **Save and Submit**
   - Click "Save Draft" to save incomplete entry
   - Click "Submit for Approval" to route for authorization

#### 3.1.2 Vendor Approval Workflow

```
┌─────────┐    ┌─────────┐    ┌─────────┐    ┌─────────┐
│  Data   │───→│  Senior │───→│ Finance │───→│  Active │
│  Entry  │    │Accountant│   │ Manager │    │ Status  │
└─────────┘    └─────────┘    └─────────┘    └─────────┘
```

| Stage | Approver | Action | Timeline |
|-------|----------|--------|----------|
| 1 | Data Entry | Create vendor record | Day 0 |
| 2 | Senior Accountant | Verify details, documents | 1 day |
| 3 | Finance Manager | Final approval | 1 day |
| 4 | System | Activate vendor | Automatic |

### 3.2 Vendor Invoice Processing

#### 3.2.1 Recording a Vendor Invoice

**Navigation:** Accounts Payable → Invoices → New Invoice

**Step-by-Step Procedure:**

1. **Invoice Header**
   ```
   Vendor: [Select from dropdown]
   Invoice Number: [Vendor's invoice number]
   Invoice Date: [Date on invoice]
   Due Date: [Calculated based on payment terms]
   Reference: [PO number or other reference]
   ```

2. **Invoice Details**
   ```
   Line 1:
   - Item/Description: [Product/Service description]
   - GL Account: [Select appropriate expense/asset account]
   - Quantity: [Number of units]
   - Unit Price: [Price per unit]
   - Amount: [Auto-calculated]
   
   Line 2, 3...: [Additional lines as needed]
   ```

3. **VAT Calculation**
   ```
   Subtotal: [Sum of line items]
   VAT 15%: [Subtotal × 0.15]
   Total Amount: [Subtotal + VAT]
   ```

4. **VAT Information (Mandatory)**
   ```
   VAT Challan Number: [If advance VAT paid]
   Mushak 6.3 Number: [If applicable]
   VAT Date: [Date of VAT payment/document]
   Input VAT Account: 7-7001-001 (auto-selected)
   ```

5. **Document Attachment**
   - Scan and attach original invoice
   - Attach delivery challan (মেমো ছাড়)
   - Attach VAT invoice/challan (if separate)
   - Attach purchase order

6. **Submit for Approval**
   - Click "Save" to keep as draft
   - Click "Submit" to route for approval

#### 3.2.2 Invoice Approval Workflow

| Stage | Role | Responsibility | Checkpoints |
|-------|------|----------------|-------------|
| 1 | Data Entry | Record invoice | Accuracy of amounts, dates |
| 2 | Dept. Head | Verify receipt | Confirm goods/services received |
| 3 | Senior Accountant | Validate | Correct coding, VAT calculation |
| 4 | Finance Manager | Authorize | Payment approval, budget check |

#### 3.2.3 Invoice Processing Checklist

- [ ] Original invoice received with VAT details
- [ ] Vendor BIN number valid and verified
- [ ] Purchase Order matched (if applicable)
- [ ] Goods Receipt Note (GRN) attached
- [ ] VAT calculated correctly at 15%
- [ ] Input VAT account properly coded
- [ ] Supporting documents scanned and attached
- [ ] Approval workflow completed
- [ ] Recorded in correct accounting period

### 3.3 Payment Processing

#### 3.3.1 Creating a Payment Batch

**Navigation:** Accounts Payable → Payments → Create Payment Batch

**Step-by-Step Procedure:**

1. **Payment Batch Header**
   ```
   Batch Name: PB-[YYYYMMDD]-[Sequence]
   Payment Date: [Planned payment date]
   Payment Method: [Bank Transfer/Cheque/Cash]
   Bank Account: [Select from: BRAC Bank/Dutch-Bangla/etc.]
   Currency: BDT
   ```

2. **Select Invoices for Payment**
   - Filter by: Vendor, Due Date, Amount Range
   - System displays eligible invoices
   - Check/select invoices to include
   - System calculates total batch amount

3. **Payment Priority Rules**
   ```
   Priority 1: Invoices with penalty for late payment
   Priority 2: Invoices due within 3 days
   Priority 3: Early payment discounts available
   Priority 4: Regular due invoices
   ```

4. **Payment Advice Generation**
   - Generate payment advice for each vendor
   - Include: Invoice references, amounts, deductions
   - Export in PDF/Excel format

5. **Approval and Execution**
   - Route for approval based on amount thresholds
   - After approval, generate bank transfer file
   - Upload to bank portal or print cheques

#### 3.3.2 Payment Amount Thresholds and Approvals

| Payment Amount | Approver 1 | Approver 2 | Approver 3 |
|----------------|------------|------------|------------|
| Up to ৳50,000 | Senior Accountant | - | - |
| ৳50,001 - ৳200,000 | Senior Accountant | Finance Manager | - |
| ৳200,001 - ৳500,000 | Finance Manager | GM Finance | - |
| Above ৳500,000 | Finance Manager | GM Finance | Managing Director |

#### 3.3.3 Recording Payment

**Navigation:** Accounts Payable → Payments → Record Payment

1. Select invoice(s) to pay
2. Enter payment details:
   ```
   Payment Date: [Actual payment date]
   Payment Reference: [Cheque/Transfer number]
   Bank Account: [Account used]
   Amount Paid: [Amount in BDT]
   TDS Amount: [If applicable - see 3.4]
   Net Payment: [Amount Paid - TDS]
   ```
3. Attach bank payment confirmation
4. Post payment to update vendor balance

### 3.4 TDS (Tax Deducted at Source)

#### 3.4.1 TDS Rates and Applicability

| Nature of Payment | TDS Rate | Section | Threshold (৳) |
|-------------------|----------|---------|---------------|
| Professional Services | 10% | 52A | 25,000 |
| Rent (Land/Building) | 5% | 52A | - |
| Contractors/Suppliers | 7.5% | 52A | 50,000 |
| Interest on Securities | 10% | 52A | 5,000 |
| Consultancy Fees | 12% | 52A | 25,000 |
| Supply of Goods | 7.5% | 52A | 50,000 |
| Royalty | 12% | 52A | 25,000 |

#### 3.4.2 TDS Calculation and Deduction

**Step-by-Step TDS Processing:**

1. **Determine TDS Applicability**
   - Check payment nature against TDS schedule
   - Verify amount exceeds threshold
   - Confirm vendor not exempt

2. **Calculate TDS**
   ```
   Invoice Amount: ৳100,000
   TDS Rate (Professional): 10%
   TDS Amount: ৳100,000 × 10% = ৳10,000
   Net Payment to Vendor: ৳100,000 - ৳10,000 = ৳90,000
   ```

3. **Record TDS in System**
   ```
   Debit: Expense Account (full amount) ৳100,000
   Credit: TDS Payable (2-2201-001) ৳10,000
   Credit: Bank/Cash (net payment) ৳90,000
   ```

4. **Generate TDS Certificate**
   - Navigate to: Accounts Payable → TDS → Generate Certificate
   - Select vendor and period
   - Generate Form 16A certificate
   - Provide to vendor for tax credit

#### 3.4.3 Monthly TDS Return Filing

**Navigation:** Accounts Payable → TDS → Monthly Return

1. **Generate TDS Summary**
   ```
   Period: [Month, Year]
   Total TDS Deducted: [Amount]
   Number of Vendors: [Count]
   ```

2. **Prepare Return**
   - Review all TDS entries for the month
   - Reconcile TDS payable account
   - Generate TDS return file

3. **File with NBR**
   - Upload to NBR e-filing portal
   - Pay TDS amount to government
   - Save acknowledgment receipt

4. **Record Payment**
   ```
   Debit: TDS Payable (2-2201-001)
   Credit: Bank
   Amount: [Total TDS paid]
   ```

### 3.5 Aging Reports

#### 3.5.1 Accounts Payable Aging Report

**Navigation:** Accounts Payable → Reports → Aging Report

**Report Parameters:**
```
As of Date: [Reporting date]
Vendor Range: [All/Specific vendor]
Aging Buckets: 0-30, 31-60, 61-90, 91+ days
Currency: BDT
```

**Sample Report Output:**

| Vendor | Current | 1-30 Days | 31-60 Days | 61-90 Days | 90+ Days | Total |
|--------|---------|-----------|------------|------------|----------|-------|
| ABC Suppliers | 45,000 | 120,000 | 25,000 | - | - | 190,000 |
| XYZ Services | 30,000 | 15,000 | - | 8,000 | - | 53,000 |
| Global Milk Ltd | 200,000 | 150,000 | 75,000 | - | - | 425,000 |
| **Total** | **275,000** | **285,000** | **100,000** | **8,000** | **-** | **668,000** |

#### 3.5.2 Vendor Balance Report

**Navigation:** Accounts Payable → Reports → Vendor Balance

**Report Columns:**
- Vendor Code and Name
- Opening Balance
- Invoices (Debit)
- Payments (Credit)
- Adjustments
- Closing Balance

---

## 4. Accounts Receivable

### 4.1 Customer Management

#### 4.1.1 Adding a New Customer

**Navigation:** Accounts Receivable → Customers → New Customer

**Step-by-Step Procedure:**

1. **Basic Information**
   ```
   Customer Code: [Auto-generated C-XXXXX]
   Customer Name: [Full legal name]
   Trade Name: [If different]
   Customer Type: [Distributor/Retailer/Institutional/Export]
   Status: Active/On Hold/Blocked
   ```

2. **Business Information**
   ```
   Business Type: [Partnership/Company/Proprietorship]
   BIN: [XXXXXXXXXXXX]
   TIN: [XXXXXXXXXXX]
   Trade License: [Number and validity]
   ```

3. **Contact Information**
   ```
   Billing Address: [Complete address]
   Shipping Address: [If different]
   Contact Person: [Name and designation]
   Phone/Mobile: [Numbers]
   Email: [Primary contact email]
   ```

4. **Credit Management**
   ```
   Credit Limit: [Maximum credit amount]
   Credit Days: [Allowed payment days]
   Payment Terms: [Net 15/Net 30/Net 45]
   Security/Collateral: [Details if any]
   ```

5. **Sales Representative**
   ```
   Assigned SR: [Name]
   Territory: [Sales territory]
   Commission Rate: [If applicable]
   ```

6. **Documents Required**
   - Trade license copy
   - TIN certificate
   - BIN certificate
   - Bank reference letter
   - Security cheque (for credit customers)

### 4.2 Customer Invoice Processing

#### 4.2.1 Creating a Sales Invoice

**Navigation:** Accounts Receivable → Invoices → New Invoice

**Step-by-Step Procedure:**

1. **Invoice Header**
   ```
   Customer: [Select from dropdown]
   Invoice Date: [Date of sale]
   Due Date: [Auto-calculated from payment terms]
   Reference: [DO/Challan number]
   Sales Order: [Linked SO number]
   ```

2. **Product Lines**
   ```
   Line 1:
   - Product: [Select product]
   - Description: [Product description]
   - Quantity: [Number of units]
   - Unit: [KG/Liter/Piece/Carton]
   - Unit Price: [Price per unit]
   - Discount %: [If applicable]
   - Line Total: [Auto-calculated]
   ```

3. **VAT Calculation**
   ```
   Subtotal: [Sum of line items after discount]
   VAT 15%: [Subtotal × 0.15]
   Total Amount: [Subtotal + VAT]
   Amount in Words: [Auto-generated]
   ```

4. **Invoice Terms**
   ```
   Payment Terms: [As per customer master]
   Delivery Terms: [Ex-factory/Delivered]
   Due Date: [Calculated date]
   Late Payment Interest: [1.5% per month if applicable]
   ```

5. **Print and Delivery**
   - Print invoice (3 copies: Customer, Accounts, Delivery)
   - Generate VAT invoice with Mushak 6.3 format
   - Record delivery confirmation

#### 4.2.2 VAT Invoice Format (Mushak 6.3)

**Required Fields on VAT Invoice:**

| Field | Description |
|-------|-------------|
| Invoice Number | Unique sequential number |
| Date | Invoice date |
| Seller BIN | Smart Dairy BIN: XXXXXXXXXXXX |
| Buyer BIN | Customer BIN (if registered) |
| Product Description | Detailed item description |
| Quantity | Units sold |
| Unit Price | Price before VAT |
| Value | Quantity × Unit Price |
| VAT Rate | 15% |
| VAT Amount | Value × 15% |
| Total Value | Value + VAT Amount |
| Authorized Signatory | Name and designation |

#### 4.2.3 Invoice Delivery Checklist

- [ ] Invoice printed on company letterhead
- [ ] VAT calculation verified (15%)
- [ ] BIN numbers included (Seller and Buyer)
- [ ] Authorized signature obtained
- [ ] Customer copy delivered
- [ ] Delivery Challan (DC) attached
- [ ] Acknowledgment received from customer
- [ ] Entry recorded in system

### 4.3 Payment Receipt

#### 4.3.1 Recording Customer Payment

**Navigation:** Accounts Receivable → Receipts → Record Receipt

**Step-by-Step Procedure:**

1. **Receipt Header**
   ```
   Receipt Number: [Auto-generated R-XXXXX]
   Date: [Date of receipt]
   Customer: [Select customer]
   Payment Mode: [Cash/Cheque/EFT/ Mobile Banking]
   Reference: [Cheque/Transfer number]
   Bank: [If cheque/transfer]
   ```

2. **Apply to Invoices**
   - System displays outstanding invoices
   - Select invoices to apply payment
   - Allocate amount to each invoice
   - Handle partial payments
   - Record any discount/adjustment

3. **Payment Allocation**
   ```
   Total Receipt: ৳100,000
   
   Applied to:
   - Invoice INV-001: ৳45,000 (Full payment)
   - Invoice INV-002: ৳35,000 (Partial)
   - Advance/On Account: ৳20,000
   ```

4. **Receipt Voucher**
   - Generate receipt voucher
   - Print for customer acknowledgment
   - Update customer balance

#### 4.3.2 Bank Deposit Recording

**For Cash/Cheque Collections:**

1. **Daily Collection Summary**
   ```
   Date: [Collection date]
   Sales Representative: [Name]
   Cash Collections: [Amount]
   Cheque Collections: [Amount]
   Total: [Amount]
   ```

2. **Deposit Slip Entry**
   - Create bank deposit entry
   - Attach scanned deposit slip
   - Record in bank module

3. **Reconciliation**
   - Match deposit with bank statement
   - Clear receipts in AR module

### 4.4 Collections Management

#### 4.4.1 Collection Target Setting

**Navigation:** Accounts Receivable → Collections → Targets

| Customer/Region | Monthly Target | Weekly Target | Achievement |
|-----------------|----------------|---------------|-------------|
| Distributor A | ৳500,000 | ৳125,000 | [Actual] |
| Distributor B | ৳400,000 | ৳100,000 | [Actual] |
| Retail Network | ৳800,000 | ৳200,000 | [Actual] |

#### 4.4.2 Collection Follow-up Process

**Collection Schedule:**

| Days Overdue | Action | Responsibility |
|--------------|--------|----------------|
| 1-7 days | Phone reminder | Sales Representative |
| 8-15 days | Formal letter/email | AR Officer |
| 16-30 days | Visit + Demand letter | AR Manager |
| 31-45 days | Legal notice | Legal Department |
| 45+ days | Collection agency/Legal | Finance Manager |

**Collection Call Script Template:**

```
Good [Morning/Afternoon], this is [Name] from Smart Dairy Ltd. 
Finance Department. I'm calling regarding your outstanding 
payment of ৳[Amount] for Invoice [Number] dated [Date] which 
was due on [Due Date]. Could you please confirm when we can 
expect payment?

[Record response in system]

Next Follow-up: [Date]
Committed Payment Date: [Date]
Amount Committed: [Amount]
```

### 4.5 Credit Control

#### 4.5.1 Credit Limit Management

**Navigation:** Accounts Receivable → Credit Control → Limits

**Credit Limit Calculation Factors:**
- Past payment history
- Average monthly purchases
- Bank reference
- Security/collateral provided
- Market reputation

**Credit Limit Approval Levels:**

| Credit Limit | Approver | Documentation Required |
|--------------|----------|------------------------|
| Up to ৳100,000 | Finance Manager | Application, trade license |
| ৳100,001 - ৳500,000 | GM Finance | Bank reference, security cheque |
| Above ৳500,000 | Managing Director | Board approval, collateral |

#### 4.5.2 Credit Hold Procedures

**Automatic Credit Hold Triggers:**
- Exceeds credit limit
- Payment overdue by 30+ days
- Bounced cheque history
- Adverse market report

**To Place Account on Hold:**
1. Navigate to Customer record
2. Change Status to "On Hold"
3. Enter reason code
4. Set review date
5. Notify Sales and Logistics

**To Release Hold:**
1. Verify payment received
2. Review account status
3. Change Status to "Active"
4. Document release reason

### 4.6 Accounts Receivable Aging

**Navigation:** Accounts Receivable → Reports → Aging Report

**Sample Aging Report:**

| Customer | Current | 1-30 | 31-60 | 61-90 | 90+ | Total |
|----------|---------|------|-------|-------|-----|-------|
| Dist. Rahman Dairy | 150,000 | 75,000 | 25,000 | - | - | 250,000 |
| Dist. Fresh Milk Mart | 200,000 | 100,000 | - | - | - | 300,000 |
| Retail Shop Network | 80,000 | 120,000 | 60,000 | 20,000 | - | 280,000 |
| **Total** | **430,000** | **295,000** | **85,000** | **20,000** | **-** | **830,000** |

**Collection Effectiveness Metrics:**
- Days Sales Outstanding (DSO): Target < 30 days
- Collection Rate: Target > 95%
- Bad Debt Ratio: Target < 1%

---

## 5. VAT Management (Bangladesh)

### 5.1 VAT Configuration

#### 5.1.1 System VAT Settings

**Navigation:** VAT Module → Setup → Configuration

**Standard Configuration:**
```
Company BIN: [XXXXXXXXXXXX]
VAT Registration Date: [DD/MM/YYYY]
Standard VAT Rate: 15%
Reduced VAT Rate: 5% (if applicable)
Zero Rate: 0% (Exports)
Exempt: [Selected items]
VAT Circle: [Name and address]
VAT Return Frequency: Monthly
```

#### 5.1.2 Product VAT Classification

| Product Category | VAT Rate | Classification |
|------------------|----------|----------------|
| Fresh Milk | 0% | Exempt (agricultural) |
| Pasteurized Milk | 15% | Standard |
| Yogurt | 15% | Standard |
| Cheese | 15% | Standard |
| Butter/Ghee | 15% | Standard |
| Flavored Milk | 15% | Standard |
| Milk Powder | 15% | Standard |
| Ice Cream | 15% | Standard |
| Whey Products | 15% | Standard |
| Export Products | 0% | Zero-rated |

**To Set Product VAT Rate:**
1. Navigate to: Inventory → Products → Select Product
2. Go to "Tax" tab
3. Select VAT Category
4. Enter HSN Code (Harmonized System of Nomenclature)
5. Save changes

### 5.2 Input VAT Tracking

#### 5.2.1 Recording Input VAT

**Input VAT** is VAT paid on purchases and expenses that can be claimed as credit against output VAT.

**Eligible Input VAT:**
- Raw materials (milk, ingredients, packaging)
- Machinery and equipment
- Utilities (electricity, gas, water)
- Professional services
- Office supplies and services
- Transportation services

**Ineligible Input VAT:**
- Entertainment expenses
- Personal expenses
- Goods/services not for business use
- Purchases from unregistered vendors

#### 5.2.2 Input VAT Entry Procedure

**Navigation:** VAT Module → Input VAT → New Entry

**Step-by-Step:**

1. **Document Details**
   ```
   Document Type: [Purchase Invoice/Bill of Entry/Expense]
   Document Number: [Vendor invoice number]
   Document Date: [Invoice date]
   Vendor BIN: [XXXXXXXXXXXX]
   ```

2. **VAT Details**
   ```
   Taxable Amount: [Amount before VAT]
   VAT Rate: 15%
   VAT Amount: [Auto-calculated]
   Total Amount: [Taxable + VAT]
   ```

3. **Verification**
   ```
   BIN Verified: [Yes/No - check NBR database]
   Document Valid: [Check format and details]
   Business Purpose: [Yes/No]
   ```

4. **Account Allocation**
   ```
   Debit: Expense/Asset Account [Taxable Amount]
   Debit: Input VAT Account [VAT Amount]
   Credit: Accounts Payable/Cash [Total Amount]
   ```

#### 5.2.3 Input VAT Register

**Navigation:** VAT Module → Input VAT → Register

**Input VAT Register Format:**

| Date | Doc No | Vendor | BIN | Taxable | VAT 15% | Total | Status |
|------|--------|--------|-----|---------|---------|-------|--------|
| 01/01/26 | PI-001 | ABC Suppliers | XXXXX | 50,000 | 7,500 | 57,500 | Claimed |
| 02/01/26 | PI-002 | XYZ Services | XXXXX | 30,000 | 4,500 | 34,500 | Pending |

### 5.3 Output VAT Calculation

#### 5.3.1 Recording Output VAT

**Output VAT** is VAT charged on sales to customers.

**Navigation:** VAT Module → Output VAT → Sales Register

**Output VAT on Sales:**

| Transaction Type | VAT Treatment | Accounting Entry |
|------------------|---------------|------------------|
| Domestic Sales (15%) | Charge VAT | Cr Output VAT 15% |
| Export Sales (0%) | Zero-rated | Cr Output VAT 0% |
| Exempt Sales | No VAT | No VAT entry |

#### 5.3.2 Output VAT Calculation Example

```
Monthly Sales Summary - January 2026

Standard Rated Sales (15% VAT):
- Product Sales: ৳500,000
- Output VAT @ 15%: ৳75,000

Zero-Rated Sales (0% VAT):
- Export Sales: ৳200,000
- Output VAT @ 0%: ৳0

Exempt Sales:
- Fresh Milk: ৳100,000
- VAT: Not applicable

Total Output VAT Liability: ৳75,000
```

### 5.4 Mushak 9.1 (VAT Return)

#### 5.4.1 Monthly VAT Return Filing

**Due Date:** 15th of following month (or next working day)

**Navigation:** VAT Module → Returns → Mushak 9.1

**Mushak 9.1 Form Sections:**

| Section | Description | Source in System |
|---------|-------------|------------------|
| Part 1 | Taxpayer Information | Company Master |
| Part 2 | Supply/Import Details | Sales Register |
| Part 3 | Input Tax Credit | Input VAT Register |
| Part 4 | Output Tax Payable | Output VAT Register |
| Part 5 | Net Tax Calculation | Auto-calculated |
| Part 6 | Payment Details | Bank records |
| Part 7 | Declaration | Authorized signatory |

#### 5.4.2 Mushak 9.1 Preparation Procedure

**Step-by-Step:**

1. **Generate Preliminary Return**
   ```
   Navigation: VAT Module → Returns → Generate Mushak 9.1
   Period: [Select month and year]
   Click: Generate Draft
   ```

2. **Review Part 2 - Supplies**
   ```
   Row 1: Value of zero-rated supplies (exports)
   Row 2: Value of exempt supplies (fresh milk)
   Row 3: Value of standard-rated supplies
   Row 4: Total output VAT
   ```

3. **Review Part 3 - Input Tax Credit**
   ```
   Row 5: Input VAT on raw materials
   Row 6: Input VAT on capital goods
   Row 7: Input VAT on services
   Row 8: Total input VAT credit
   ```

4. **Calculate Net VAT (Part 5)**
   ```
   Output VAT (Row 4): ৳XXX,XXX
   Less: Input VAT (Row 8): ৳XXX,XXX
   Net VAT Payable/(Excess): ৳XXX,XXX
   ```

5. **Adjustments**
   - Add any corrections from previous periods
   - Include penalty/interest if applicable
   - Apply for adjustment if excess credit

6. **Final Review and Sign-off**
   - Finance Manager review
   - Authorized signatory approval
   - Generate final PDF

7. **Submission**
   - Upload to NBR VAT online portal
   - Make payment if VAT payable
   - Save acknowledgment

#### 5.4.3 Mushak 9.1 Filing Checklist

- [ ] All sales invoices recorded for the month
- [ ] All purchase invoices with VAT entered
- [ ] Export documents attached for zero-rated sales
- [ ] Input VAT verified against valid BIN
- [ ] Previous month adjustments included
- [ ] Calculations verified manually
- [ ] Authorized signatory available for signature
- [ ] Payment arranged if VAT payable
- [ ] Filed before 15th of month
- [ ] Acknowledgment saved

### 5.5 Mushak 6.3 (VAT Challan)

#### 5.5.1 Understanding Mushak 6.3

**Mushak 6.3** is the VAT challan form used for:
- VAT payment to government
- Advance VAT deposit
- Penalty/interest payments

**Navigation:** VAT Module → Payments → Mushak 6.3

#### 5.5.2 Generating Mushak 6.3

**Step-by-Step:**

1. **Select Purpose**
   ```
   Purpose: [Monthly VAT/TDS VAT/Advance/Other]
   Period: [Month/Year if applicable]
   Amount: [Total amount to pay]
   ```

2. **Payment Details**
   ```
   Bank Name: [Select authorized bank]
   Challan Date: [Date]
   Breakdown:
   - VAT Amount: ৳XXX,XXX
   - Interest: ৳XXX (if applicable)
   - Penalty: ৳XXX (if applicable)
   ```

3. **Generate Challan**
   - System generates Mushak 6.3 form
   - Print in triplicate
   - Submit to bank with payment

4. **Record Payment**
   ```
   Debit: VAT Payable Account
   Credit: Bank Account
   Reference: Mushak 6.3 Number
   ```

#### 5.5.3 Mushak 6.3 Format

| Field | Entry |
|-------|-------|
| BIN | XXXXXXXXXXXX |
| Taxpayer Name | Smart Dairy Ltd. |
| Address | [Registered address] |
| Challan Number | [Auto-generated] |
| Date | [Payment date] |
| Assessment Year | [YYYY-YY] |
| Tax Period | [Month/Year] |
| Nature of Payment | [VAT/Interest/Penalty] |
| Amount in Figures | ৳XXX,XXX |
| Amount in Words | [Auto-generated] |

### 5.6 VAT Compliance Calendar

| Deadline | Activity | Form | Responsible |
|----------|----------|------|-------------|
| 15th of month | Monthly VAT Return | Mushak 9.1 | VAT Officer |
| 15th of month | VAT Payment | Mushak 6.3 | VAT Officer |
| 31st January | Annual Return | Mushak 9.2 | VAT Officer |
| Quarterly | VAT Audit Report | As required | External Auditor |
| As required | Amendment Returns | Mushak 9.1 | VAT Officer |

### 5.7 VAT Reconciliation

**Monthly VAT Reconciliation:**

```
Input VAT Reconciliation:
Input VAT per GL: ৳XXX,XXX
Add: Unclaimed from previous: ৳XXX,XXX
Less: Rejected/Invalid: ৳XXX,XXX
Input VAT Claimed: ৳XXX,XXX

Output VAT Reconciliation:
Output VAT per GL: ৳XXX,XXX
Add: Adjustments: ৳XXX,XXX
Output VAT Payable: ৳XXX,XXX

Net Position:
Output VAT: ৳XXX,XXX
Less: Input VAT: ৳XXX,XXX
Net Payable/(Credit): ৳XXX,XXX
```

---

## 6. Bank Reconciliation

### 6.1 Bank Account Setup

#### 6.1.1 Bank Master Maintenance

**Navigation:** Banking → Setup → Bank Accounts

**Bank Account Details:**

| Field | BRAC Bank | Dutch-Bangla Bank |
|-------|-----------|-------------------|
| Account Code | BANK-001 | BANK-002 |
| Bank Name | BRAC Bank Ltd. | Dutch-Bangla Bank |
| Branch | [Branch name] | [Branch name] |
| Account Number | [Account number] | [Account number] |
| Account Type | Current | Current |
| Currency | BDT | BDT |
| GL Account | 1-1002-001 | 1-1002-002 |
| Opening Balance | [Amount] | [Amount] |

#### 6.1.2 Bank Signatory Setup

**Authorized Signatories:**

| Bank | First Signatory | Second Signatory | Limit |
|------|-----------------|-------------------|-------|
| BRAC Bank | Finance Manager | GM Finance | Up to ৳500,000 |
| BRAC Bank | GM Finance | Managing Director | Above ৳500,000 |
| DBBL | Finance Manager | GM Finance | Up to ৳300,000 |

### 6.2 Importing Bank Statements

#### 6.2.1 Supported Formats

| Bank | Format | File Extension |
|------|--------|----------------|
| BRAC Bank | CSV, Excel | .csv, .xlsx |
| Dutch-Bangla | CSV, Excel, MT940 | .csv, .xlsx, .sta |
| Standard Chartered | CSV, MT940 | .csv, .sta |
| Islami Bank | Excel | .xlsx |

#### 6.2.2 Import Procedure

**Navigation:** Banking → Reconciliation → Import Statement

**Step-by-Step:**

1. **Download Statement from Bank**
   - Login to bank's internet banking
   - Select date range
   - Download in compatible format
   - Save to designated folder

2. **Import to System**
   ```
   Bank Account: [Select account]
   Statement Period: [From date - To date]
   File: [Browse and select file]
   Format: [Auto-detected or select]
   ```

3. **Map Fields**
   System attempts auto-mapping. Verify:
   ```
   Date column → Transaction Date
   Description → Description/Narration
   Debit → Outflow
   Credit → Inflow
   Reference → Reference/Check Number
   ```

4. **Validate Import**
   - Review imported transactions
   - Check opening balance matches
   - Verify closing balance
   - Confirm total debits and credits

5. **Save Imported Transactions**
   - Click "Import"
   - System creates un-reconciled transactions

### 6.3 Matching Transactions

#### 6.3.1 Automatic Matching

**Navigation:** Banking → Reconciliation → Match Transactions

**Auto-Match Criteria:**
- Exact amount match
- Date within tolerance (±3 days)
- Reference number match
- Description keyword match

**To Run Auto-Match:**
1. Select bank account
2. Select date range
3. Click "Auto-Match"
4. System suggests matches with confidence scores

#### 6.3.2 Manual Matching

**For Unmatched Transactions:**

1. **Select Bank Transaction**
   - View unmatched bank transactions
   - Click on transaction to match

2. **Find System Transaction**
   - Search by amount, date, reference
   - View possible matches
   - Select correct match

3. **Confirm Match**
   - Verify amount and details
   - Click "Match"
   - System links the transactions

#### 6.3.3 Creating Missing Transactions

**For Bank Transactions Not in System:**

1. Click "Create Transaction"
2. Select transaction type:
   ```
   Type: [Receipt/Payment/Transfer]
   Account: [Select GL account]
   Description: [Enter details]
   ```
3. Complete entry details
4. Save and match to bank transaction

### 6.4 Bank Reconciliation Statement

#### 6.4.1 Generating Reconciliation Report

**Navigation:** Banking → Reconciliation → Reconciliation Report

**Reconciliation Statement Format:**

```
BANK RECONCILIATION STATEMENT
Bank: BRAC Bank Ltd.
Account: XXXXXXXXXXXX
As of: January 31, 2026

Balance as per Bank Statement:          ৳2,450,000

Add: Deposits in Transit
  Deposit dated 31/01/26:                  ৳150,000
                                           ----------
                                           ৳2,600,000

Less: Outstanding Cheques
  Chq #12345 dated 25/01/26:              (৳50,000)
  Chq #12346 dated 28/01/26:              (৳75,000)
                                           ----------
                                           (৳125,000)

Adjusted Bank Balance:                    ৳2,475,000

Balance as per Books:                     ৳2,475,000

Difference:                               ৳0
```

#### 6.4.2 Reconciliation Checklist

- [ ] Bank statement imported for the period
- [ ] All receipts recorded in system
- [ ] All payments recorded in system
- [ ] Auto-matches reviewed and confirmed
- [ ] Manual matches completed
- [ ] Missing transactions created
- [ ] Deposits in transit identified
- [ ] Outstanding cheques listed
- [ ] Adjusted balance matches book balance
- [ ] Reconciliation report generated and saved
- [ ] Approved by Finance Manager

### 6.5 Bank Charges and Interest

#### 6.5.1 Recording Bank Charges

**Navigation:** Banking → Transactions → Bank Charges

**Entry Template:**
```
Date: [Statement date]
Bank Account: [Select bank]
Charge Type: [Account maintenance/Transaction fee/Other]
Amount: [Amount in BDT]
VAT: [If applicable - 15%]
Total: [Amount + VAT]

Journal Entry:
Debit: Bank Charges (Expense) - 6-XXXX-XXX
Debit: Input VAT (if applicable) - 7-7001-002
Credit: Bank Account - 1-1002-XXX
```

#### 6.5.2 Recording Bank Interest

**Interest Received:**
```
Debit: Bank Account
Credit: Interest Income (4-XXXX-XXX)
```

**Interest Paid (on overdraft/loan):**
```
Debit: Interest Expense (6-XXXX-XXX)
Credit: Bank Account
```

---

## 7. Payroll Processing

### 7.1 Employee Setup

#### 7.1.1 Employee Master Data

**Navigation:** Payroll → Employees → New Employee

**Personal Information:**
```
Employee ID: [EMP-XXXXX]
Full Name: [As per NID]
Father's Name: [Name]
Mother's Name: [Name]
Date of Birth: [DD/MM/YYYY]
Gender: [Male/Female/Other]
Marital Status: [Single/Married/Divorced/Widowed]
National ID: [NID number]
Blood Group: [Group]
Nationality: Bangladeshi
```

**Employment Details:**
```
Department: [Production/Finance/Sales/etc.]
Designation: [Job title]
Employment Type: [Permanent/Contractual/Probation]
Joining Date: [DD/MM/YYYY]
Confirmation Date: [If confirmed]
Employment Status: [Active/Suspended/Terminated]
```

**Contact Information:**
```
Present Address: [Full address]
Permanent Address: [Full address]
Mobile: [+880 XXXX-XXXXXX]
Email: [Email address]
Emergency Contact: [Name, Relationship, Phone]
```

**Bank Information:**
```
Bank Name: [Bank name]
Branch: [Branch]
Account Number: [Account number]
Account Name: [Name as per bank]
Routing Number: [Routing number]
```

#### 7.1.2 Salary Structure Setup

**Navigation:** Payroll → Setup → Salary Structure

**Salary Components:**

| Component | Type | Calculation Base | Account Code |
|-----------|------|------------------|--------------|
| Basic Salary | Fixed | Monthly | 8-8001-001 |
| House Rent Allowance | Fixed | 50% of Basic | 8-8001-002 |
| Medical Allowance | Fixed | 10% of Basic (Max ৳10,000) | 8-8001-003 |
| Conveyance Allowance | Fixed | Fixed amount | 8-8001-004 |
| Dearness Allowance | Fixed | As applicable | 8-8001-005 |
| Overtime | Variable | (Basic/208) × 2 × Hours | 8-8002-001 |
| Festival Bonus | Variable | 2× Basic per year | 8-8002-002 |

**Deduction Components:**

| Component | Type | Calculation | Account Code |
|-----------|------|-------------|--------------|
| Provident Fund (Employee) | Statutory | 10% of Basic | 8-8003-001 |
| Income Tax | Statutory | As per tax slab | 8-8003-002 |
| Advance Salary | Recovery | As per recovery schedule | 8-8003-003 |
| Loan Recovery | Recovery | EMI amount | 8-8003-004 |
| Other Deductions | Other | As applicable | 8-8003-005 |

### 7.2 Monthly Payroll Processing

#### 7.2.1 Payroll Calendar

| Activity | Deadline | Responsible |
|----------|----------|-------------|
| Attendance Finalization | 25th of month | HR |
| Overtime Calculation | 26th of month | Department Heads |
| Input to Payroll System | 27th of month | HR |
| Payroll Processing | 28th of month | Payroll Officer |
| Review and Approval | 29th of month | Finance Manager |
| Bank Transfer/Disbursement | 30th/31st | Finance |
| Payslip Distribution | 1st of next month | HR |

#### 7.2.2 Payroll Processing Procedure

**Navigation:** Payroll → Processing → Monthly Payroll

**Step-by-Step:**

1. **Initialize Payroll Run**
   ```
   Payroll Month: [Select month/year]
   Payroll Type: [Regular/Supplementary/Settlement]
   Click: Initialize
   ```

2. **Import Attendance Data**
   - Upload attendance file from biometric system
   - System calculates:
     - Days present
     - Days absent
     - Leave availed
     - Overtime hours

3. **Enter Variable Inputs**
   ```
   For each employee:
   - Overtime hours (if not imported)
   - Incentives/Bonuses
   - Arrears
   - Deductions (advances, loans)
   - Leave without pay days
   ```

4. **Process Payroll**
   - Click "Calculate Payroll"
   - System computes:
     - Gross Salary
     - Total Deductions
     - Net Payable
     - Employer PF contribution
     - Gratuity accrual

5. **Review Payroll Register**
   ```
   Generate Payroll Register:
   - Employee-wise details
   - Department-wise summary
   - Component-wise summary
   ```

6. **Approval**
   - Submit to Finance Manager
   - Submit to HR Manager
   - Final approval by GM Finance

7. **Generate Bank File**
   - Create bank transfer file
   - Upload to bank portal
   - Process transfer

8. **Post to GL**
   ```
   Debit: Salary Expense Accounts (8-8001-XXX)
   Credit: Bank Account (Net Pay)
   Credit: PF Payable (Employee + Employer)
   Credit: Tax Payable
   Credit: Other Deductions Payable
   ```

### 7.3 Provident Fund (PF)

#### 7.3.1 PF Configuration

**Navigation:** Payroll → Setup → Provident Fund

**PF Rules:**
```
Employee Contribution: 10% of Basic Salary
Employer Contribution: 10% of Basic Salary
Total Contribution: 20% of Basic Salary per month
Interest Rate: As declared by Board (typically 8-10% p.a.)
Vesting Period: 5 years for employer contribution
Withdrawal: On retirement, resignation (after 5 years), or emergency
```

#### 7.3.2 PF Calculation

**Monthly PF Calculation Example:**
```
Employee: EMP-0001
Basic Salary: ৳30,000

Employee Contribution (10%): ৳3,000
Employer Contribution (10%): ৳3,000
Total Monthly PF: ৳6,000

Accounting Entry:
Debit: Salary Expense - Basic: ৳30,000
Debit: PF Expense (Employer): ৳3,000
Credit: Bank (Net Salary): ৳XXX
Credit: PF Payable - Employee: ৳3,000
Credit: PF Payable - Employer: ৳3,000
```

#### 7.3.3 PF Statement

**Navigation:** Payroll → PF → Employee Statement

**PF Statement Format:**

| Month | Employee Cont. | Employer Cont. | Interest | Balance |
|-------|----------------|----------------|----------|---------|
| Jan 2026 | 3,000 | 3,000 | - | 6,000 |
| Feb 2026 | 3,000 | 3,000 | 40 | 12,040 |
| Mar 2026 | 3,000 | 3,000 | 80 | 18,120 |
| **Total** | **9,000** | **9,000** | **120** | **18,120** |

### 7.4 Gratuity Calculation

#### 7.4.1 Gratuity Eligibility and Formula

**As per Bangladesh Labour Act, 2006 (amended):**

| Service Period | Eligibility | Formula |
|----------------|-------------|---------|
| Less than 5 years | Not eligible | 0 |
| 5 years or more | Eligible | (Last Basic × 30 days) / 26 × Years of Service |

#### 7.4.2 Gratuity Calculation Example

```
Employee: EMP-0001
Date of Joining: January 1, 2016
Date of Calculation: January 1, 2026
Years of Service: 10 years
Last Basic Salary: ৳50,000

Gratuity Calculation:
= (50,000 × 30) / 26 × 10
= (1,500,000) / 26 × 10
= 57,692.31 × 10
= ৳576,923
```

#### 7.4.3 Monthly Gratuity Accrual

**Navigation:** Payroll → Gratuity → Accrual

**Monthly Accrual Entry:**
```
Estimated Annual Gratuity: ৳57,692
Monthly Accrual: ৳57,692 / 12 = ৳4,808

Journal Entry:
Debit: Gratuity Expense: ৳4,808
Credit: Gratuity Provision: ৳4,808
```

### 7.5 Festival Bonus

#### 7.5.1 Festival Bonus Entitlement

**As per Bangladesh Labour Act:**
- Two festival bonuses per year
- Amount: Equivalent to one basic salary each
- Typically paid before Eid-ul-Fitr and Eid-ul-Adha
- Pro-rata for employees with less than one year

#### 7.5.2 Festival Bonus Processing

**Navigation:** Payroll → Processing → Festival Bonus

**Step-by-Step:**

1. **Generate Festival Bonus List**
   ```
   Festival: [Eid-ul-Fitr/Eid-ul-Adha]
   Payment Date: [Target date]
   Eligibility: [Employees with 6+ months service]
   ```

2. **Calculate Bonus Amount**
   ```
   For confirmed employees: 1 × Basic Salary
   For probationary employees: Pro-rata based on service
   ```

3. **Approval and Payment**
   - Route for approval
   - Generate bank file
   - Process payment
   - Distribute bonus letters

4. **Accounting Entry**
   ```
   Debit: Festival Bonus Expense: [Total amount]
   Credit: Bank Account: [Net amount]
   Credit: Tax Payable (if applicable): [Tax deducted]
   ```

### 7.6 Payslip Generation

#### 7.6.1 Payslip Format

**Navigation:** Payroll → Reports → Payslips

**Payslip Contents:**

```
┌─────────────────────────────────────────────────────────┐
│                    SMART DAIRY LTD.                     │
│              Monthly Salary Payslip                     │
│              For the month of: January 2026             │
├─────────────────────────────────────────────────────────┤
│ Employee ID: EMP-0001      Department: Production       │
│ Name: [Employee Name]      Designation: Supervisor      │
│ Joining Date: [Date]       Days Worked: 26              │
├─────────────────────────────────────────────────────────┤
│ EARNINGS                          AMOUNT (৳)            │
│ Basic Salary                      30,000.00             │
│ House Rent Allowance (50%)        15,000.00             │
│ Medical Allowance (10%)            3,000.00             │
│ Conveyance Allowance               2,500.00             │
│ Overtime (10 hrs @ 288.46)         2,884.60             │
│ ─────────────────────────────────────────────           │
│ Gross Salary                      53,384.60             │
├─────────────────────────────────────────────────────────┤
│ DEDUCTIONS                        AMOUNT (৳)            │
│ Provident Fund (10%)               3,000.00             │
│ Income Tax                         1,250.00             │
│ Loan Recovery                      2,000.00             │
│ ─────────────────────────────────────────────           │
│ Total Deductions                   6,250.00             │
├─────────────────────────────────────────────────────────┤
│ NET PAYABLE:                      ৳47,134.60            │
│ (Forty-seven thousand one hundred thirty-four           │
│  and sixty poisha only)                                 │
├─────────────────────────────────────────────────────────┤
│ Employer PF Contribution:          3,000.00             │
│ Bank Account: [XXXX-XXXX-XXXX-1234]                     │
│ Payment Date: January 31, 2026                          │
└─────────────────────────────────────────────────────────┘
```

#### 7.6.2 Payslip Distribution

**Distribution Methods:**
1. **Email:** Automatic email to employee's registered email
2. **Portal:** Available in employee self-service portal
3. **Physical:** Printed copy on request

**Navigation for Bulk Generation:**
```
Payroll → Reports → Generate Payslips
Month: [Select]
Format: [PDF/Excel]
Distribution: [Email/Print/Both]
Click: Generate
```

### 7.7 Income Tax Calculation

#### 7.7.1 Tax Slabs (Individual - Bangladesh)

| Income Range (৳) | Tax Rate | Amount |
|------------------|----------|--------|
| Up to 350,000 | 0% | Nil |
| 350,001 - 450,000 | 5% | On excess over 350,000 |
| 450,001 - 750,000 | 10% | 5,000 + 10% on excess |
| 750,001 - 1,150,000 | 15% | 35,000 + 15% on excess |
| 1,150,001 - 1,750,000 | 20% | 95,000 + 20% on excess |
| Above 1,750,000 | 25% | 215,000 + 25% on excess |

*Note: Tax-free threshold may vary based on gender, age, and disability status.*

#### 7.7.2 Tax Calculation Example

```
Annual Gross Income: ৳800,000

Taxable Income Calculation:
Gross Income:           ৳800,000
Less: Exemptions
  - Investment allowance (20%): ৳160,000
Taxable Income:         ৳640,000

Tax Calculation:
First 350,000:          ৳0
Next 100,000 @ 5%:      ৳5,000
Remaining 190,000 @ 10%: ৳19,000
─────────────────────────────
Total Tax:              ৳24,000

Monthly TDS: ৳24,000 / 12 = ৳2,000
```

---

## 8. General Ledger

### 8.1 Journal Entries

#### 8.1.1 Creating a Journal Entry

**Navigation:** General Ledger → Journal Entries → New Entry

**Step-by-Step Procedure:**

1. **Entry Header**
   ```
   Journal Number: [Auto-generated JV-XXXXX]
   Date: [Transaction date]
   Reference: [Supporting document reference]
   Description: [Brief description]
   Period: [Auto-populated from date]
   ```

2. **Entry Lines**
   ```
   Line 1:
   - Account: [Select GL account]
   - Debit: [Amount]
   - Credit: [Leave blank]
   - Description: [Line description]
   - Cost Center: [If applicable]
   
   Line 2:
   - Account: [Select GL account]
   - Debit: [Leave blank]
   - Credit: [Amount]
   - Description: [Line description]
   - Cost Center: [If applicable]
   ```

3. **Balancing**
   - System validates Total Debits = Total Credits
   - Unbalanced entries cannot be saved

4. **Document Attachment**
   - Attach supporting documents
   - Maximum file size: 10MB per file
   - Supported formats: PDF, JPG, PNG

5. **Submit for Approval**
   - Save as draft (editable)
   - Submit for approval (triggers workflow)

#### 8.1.2 Journal Entry Types

| Type | Code | Description | Example |
|------|------|-------------|---------|
| Regular | JV | General journal voucher | Accruals, adjustments |
| Adjusting | AJ | Month-end adjustments | Depreciation, provisions |
| Reversing | RJ | Reversal entries | Reversal of accruals |
| Closing | CJ | Year-end closing | Closing entries |
| Opening | OJ | Year-opening balances | Opening entries |

#### 8.1.3 Common Journal Entries

**1. Accrual Entry - Monthly Expense:**
```
Debit: Electricity Expense      ৳45,000
Credit: Accrued Expenses        ৳45,000
(To record estimated electricity for January)
```

**2. Depreciation Entry:**
```
Debit: Depreciation Expense     ৳125,000
Credit: Accumulated Depreciation ৳125,000
(Monthly depreciation on fixed assets)
```

**3. Prepaid Adjustment:**
```
Debit: Insurance Expense        ৳8,333
Credit: Prepaid Insurance       ৳8,333
(Monthly amortization of annual insurance)
```

**4. Provision Entry:**
```
Debit: Bad Debt Expense         ৳15,000
Credit: Provision for Bad Debts  ৳15,000
(Provision for doubtful receivables)
```

### 8.2 Account Reconciliation

#### 8.2.1 Balance Sheet Account Reconciliation

**Monthly Reconciliation Schedule:**

| Account | Frequency | Owner | Due Date |
|---------|-----------|-------|----------|
| Cash & Bank | Daily | Cashier | Daily |
| Bank Accounts | Monthly | Accountant | 5th of month |
| Accounts Receivable | Monthly | AR Officer | 5th of month |
| Inventory | Monthly | Store Officer | 5th of month |
| Fixed Assets | Quarterly | FA Accountant | 10th of next quarter |
| Accounts Payable | Monthly | AP Officer | 5th of month |
| Accruals | Monthly | Accountant | 5th of month |
| Provisions | Quarterly | Senior Accountant | 10th of next quarter |

#### 8.2.2 Reconciliation Format

**Account Reconciliation Statement:**

```
ACCOUNT RECONCILIATION STATEMENT
Account: Accounts Receivable - Distributor Channel
Account Code: 1-1101-001
As of: January 31, 2026

Balance per General Ledger:             ৳830,000

Reconciling Items:
1. Unapplied receipts:                    ৳(25,000)
2. Disputed invoices (pending resolution): ৳15,000
3. Credit notes not processed:            ৳(10,000)

Adjusted Balance:                       ৳810,000

Verified Against:
- AR Aging Report: ৳830,000 ✓
- Customer Confirmations: In progress

Prepared by: _________________ Date: _______
Reviewed by: _________________ Date: _______
```

### 8.3 Period-End Closing

#### 8.3.1 Month-End Closing Checklist

**Tasks by Day:**

**Day 25-26:**
- [ ] All invoices entered (AP)
- [ ] All sales invoices posted (AR)
- [ ] All receipts/payments recorded
- [ ] Bank statements reconciled
- [ ] Cash counts verified

**Day 27-28:**
- [ ] Payroll processed and posted
- [ ] Accrual entries prepared
- [ ] Depreciation calculated
- [ ] Prepaid expenses amortized

**Day 29-30:**
- [ ] Inventory valuation completed
- [ ] Cost of goods sold calculated
- [ ] Intercompany transactions reconciled
- [ ] Journal entries posted

**Day 31/1st of next month:**
- [ ] Trial balance generated
- [ ] Variances analyzed
- [ ] Management reports prepared
- [ ] Period closure initiated

#### 8.3.2 Period Closing Procedure

**Navigation:** General Ledger → Period Close → Close Period

**Step-by-Step:**

1. **Pre-Close Validation**
   ```
   Run Pre-Close Check:
   - Unposted journals: [Count]
   - Unreconciled bank items: [Count]
   - Unmatched AR/AP items: [Count]
   - Open transactions: [Count]
   ```

2. **Close Sub-Ledgers**
   - Close Inventory module
   - Close AP module
   - Close AR module
   - Close Payroll module
   - Close Fixed Assets module

3. **Post Adjusting Entries**
   - Depreciation
   - Accruals
   - Provisions
   - Prepayments

4. **Generate Trial Balance**
   - Review for anomalies
   - Verify all accounts balanced
   - Check period activity

5. **Close Period**
   ```
   Period: January 2026
   Status: Closing
   Click: Confirm Close
   ```

6. **Post-Close Activities**
   - Generate financial reports
   - Backup period data
   - Prepare management pack

#### 8.3.3 Period Re-Opening

**Emergency Period Re-Opening:**

*Note: Period re-opening requires Finance Manager approval*

```
Navigation: General Ledger → Period Close → Reopen Period

1. Select period to reopen
2. Enter reason for reopening
3. Upload approval document
4. Click "Request Reopening"
5. System routes to approver
6. Upon approval, period reopens
```

---

## 9. Financial Reports

### 9.1 Trial Balance

#### 9.1.1 Generating Trial Balance

**Navigation:** Financial Reports → Trial Balance

**Report Parameters:**
```
As of Date: [Select date]
Period Range: [From period - To period]
Level: [Summary/Detail]
Include Zero Balances: [Yes/No]
Include Inactive Accounts: [Yes/No]
Format: [PDF/Excel/HTML]
```

#### 9.1.2 Trial Balance Format

```
                        SMART DAIRY LTD.
                         TRIAL BALANCE
                    As of January 31, 2026

Account     Account Name              Debit       Credit
Code                                (৳)         (৳)
─────────────────────────────────────────────────────────
1-1001-001  Cash in Hand            25,000
1-1002-001  BRAC Bank              450,000
1-1002-002  Dutch-Bangla Bank      275,000
1-1101-001  Accounts Receivable    830,000
1-1201-001  Raw Milk Inventory     520,000
1-1201-002  Finished Goods         380,000
...         ...                    ...         ...
2-2001-001  Accounts Payable                   668,000
2-2101-001  VAT Payable                         75,000
2-2301-001  Salaries Payable                   450,000
...         ...                    ...         ...
4-4001-001  Sales Revenue                      950,000
4-4002-001  Export Sales                       200,000
...         ...                    ...         ...
6-6001-001  Raw Material Cost      350,000
6-6002-001  Salaries Expense       450,000
6-6003-001  Utilities Expense       85,000
...         ...                    ...         ...
─────────────────────────────────────────────────────────
            TOTAL                 X,XXX,XXX   X,XXX,XXX
─────────────────────────────────────────────────────────
```

### 9.2 Balance Sheet

#### 9.2.1 Generating Balance Sheet

**Navigation:** Financial Reports → Balance Sheet

**Report Parameters:**
```
As of Date: [Select date]
Comparison: [Previous Year/Previous Month/Budget]
Format: [Standard/Detailed]
Show Notes: [Yes/No]
```

#### 9.2.2 Balance Sheet Format

```
                        SMART DAIRY LTD.
                        BALANCE SHEET
                    As of January 31, 2026

ASSETS
─────────────────────────────────────────────────────────
CURRENT ASSETS
  Cash and Cash Equivalents                 ৳750,000
    Cash in Hand                              25,000
    BRAC Bank                                450,000
    Dutch-Bangla Bank                        275,000

  Accounts Receivable (Net)                  830,000
    Less: Provision for Bad Debts            (15,000)

  Inventory                                1,200,000
    Raw Milk                                 520,000
    Finished Goods                           380,000
    Packaging Materials                      300,000

  Prepaid Expenses                            45,000
  Input VAT Credit                            85,000
─────────────────────────────────────────────────────────
TOTAL CURRENT ASSETS                       2,895,000
─────────────────────────────────────────────────────────

NON-CURRENT ASSETS
  Property, Plant & Equipment              5,250,000
    Land                                   1,500,000
    Buildings                              2,000,000
    Machinery & Equipment                  1,800,000
    Less: Accumulated Depreciation          (350,000)

  Intangible Assets                          125,000
  Long-term Investments                      500,000
─────────────────────────────────────────────────────────
TOTAL NON-CURRENT ASSETS                   5,875,000
─────────────────────────────────────────────────────────

TOTAL ASSETS                               ৳8,770,000
═════════════════════════════════════════════════════════

LIABILITIES AND EQUITY
─────────────────────────────────────────────────────────
CURRENT LIABILITIES
  Accounts Payable                           ৳668,000
  Short-term Borrowings                       300,000
  Current Portion of Long-term Debt           250,000
  Salaries Payable                            450,000
  VAT Payable                                  75,000
  TDS Payable                                  25,000
  Accrued Expenses                            85,000
─────────────────────────────────────────────────────────
TOTAL CURRENT LIABILITIES                  1,853,000
─────────────────────────────────────────────────────────

NON-CURRENT LIABILITIES
  Long-term Debt                           1,500,000
  Gratuity Provision                         180,000
  Deferred Tax Liability                      45,000
─────────────────────────────────────────────────────────
TOTAL NON-CURRENT LIABILITIES              1,725,000
─────────────────────────────────────────────────────────

TOTAL LIABILITIES                          3,578,000
─────────────────────────────────────────────────────────

EQUITY
  Share Capital                            3,000,000
  Retained Earnings                        2,192,000
─────────────────────────────────────────────────────────
TOTAL EQUITY                               5,192,000
─────────────────────────────────────────────────────────

TOTAL LIABILITIES AND EQUITY               ৳8,770,000
═════════════════════════════════════════════════════════
```

### 9.3 Profit & Loss Statement

#### 9.3.1 Generating P&L Statement

**Navigation:** Financial Reports → Profit & Loss

**Report Parameters:**
```
Period: [Select period]
Comparison: [Same period last year/Budget]
Format: [Standard/Detailed with COA]
Show Variance: [Yes/No]
Show % of Revenue: [Yes/No]
```

#### 9.3.2 Profit & Loss Format

```
                        SMART DAIRY LTD.
                  PROFIT AND LOSS STATEMENT
              For the Month Ended January 31, 2026

REVENUE
─────────────────────────────────────────────────────────
Sales Revenue - Domestic                    ৳950,000
Export Sales (Zero-rated)                    200,000
Other Income                                  15,000
─────────────────────────────────────────────────────────
TOTAL REVENUE                              1,165,000
═════════════════════════════════════════════════════════

COST OF SALES
─────────────────────────────────────────────────────────
Opening Inventory                          (450,000)
Add: Purchases                              780,000
Add: Direct Labor                           280,000
Add: Manufacturing Overhead                 190,000
Less: Closing Inventory                     520,000
─────────────────────────────────────────────────────────
COST OF SALES                             (1,280,000)
═════════════════════════════════════════════════════════

GROSS PROFIT/(LOSS)                         (115,000)

OPERATING EXPENSES
─────────────────────────────────────────────────────────
Administrative Expenses
  Salaries and Benefits                     450,000
  Rent                                       75,000
  Utilities                                  45,000
  Office Expenses                            25,000
  Depreciation                               85,000
─────────────────────────────────────────────────────────
Total Administrative Expenses              (680,000)

Selling and Distribution Expenses
  Sales Salaries                            180,000
  Transportation                             95,000
  Marketing                                  65,000
  Commission                                 35,000
─────────────────────────────────────────────────────────
Total Selling Expenses                     (375,000)
─────────────────────────────────────────────────────────
TOTAL OPERATING EXPENSES                 (1,055,000)
═════════════════════════════════════════════════════════

OPERATING LOSS                            (1,170,000)

Other Income/(Expenses)
  Interest Income                             5,000
  Interest Expense                          (25,000)
  Bank Charges                               (3,000)
─────────────────────────────────────────────────────────
Net Other Expenses                          (23,000)
─────────────────────────────────────────────────────────

LOSS BEFORE TAX                           (1,193,000)

Income Tax Expense                                 -
─────────────────────────────────────────────────────────

NET LOSS FOR THE PERIOD                  ৳(1,193,000)
═════════════════════════════════════════════════════════
```

### 9.4 Cash Flow Statement

#### 9.4.1 Generating Cash Flow Statement

**Navigation:** Financial Reports → Cash Flow

**Report Parameters:**
```
Period: [Select period]
Method: [Direct/Indirect]
Grouping: [Standard/BFRS Format]
Comparison: [Previous Period/Year to Date]
```

#### 9.4.2 Cash Flow Statement Format (Indirect Method)

```
                        SMART DAIRY LTD.
                    CASH FLOW STATEMENT
              For the Month Ended January 31, 2026

CASH FLOW FROM OPERATING ACTIVITIES
─────────────────────────────────────────────────────────
Net Loss for the Period                   ৳(1,193,000)
Adjustments for:
  Depreciation and Amortization               125,000
  Interest Expense                             25,000
  Provision for Bad Debts                      15,000
Changes in Working Capital:
  (Increase)/Decrease in Accounts Receivable  (50,000)
  (Increase)/Decrease in Inventory           (200,000)
  Increase/(Decrease) in Accounts Payable     150,000
  Increase/(Decrease) in Accrued Expenses      35,000
─────────────────────────────────────────────────────────
Net Cash from Operating Activities        (1,093,000)
═════════════════════════════════════════════════════════

CASH FLOW FROM INVESTING ACTIVITIES
─────────────────────────────────────────────────────────
Purchase of Property, Plant & Equipment      (150,000)
Purchase of Intangible Assets                 (25,000)
Proceeds from Sale of Assets                        -
─────────────────────────────────────────────────────────
Net Cash used in Investing Activities        (175,000)
═════════════════════════════════════════════════════════

CASH FLOW FROM FINANCING ACTIVITIES
─────────────────────────────────────────────────────────
Proceeds from Long-term Borrowings            500,000
Repayment of Long-term Borrowings            (100,000)
Interest Paid                                 (25,000)
Dividends Paid                                      -
─────────────────────────────────────────────────────────
Net Cash from Financing Activities            375,000
═════════════════════════════════════════════════════════

NET INCREASE/(DECREASE) IN CASH           (893,000)

Cash and Cash Equivalents at Beginning      1,643,000
Cash and Cash Equivalents at Ending         ৳750,000
═════════════════════════════════════════════════════════
```

### 9.5 Bangladesh Compliance Reports

#### 9.5.1 Required Regulatory Reports

| Report | Authority | Frequency | Due Date |
|--------|-----------|-----------|----------|
| VAT Return (Mushak 9.1) | NBR | Monthly | 15th of month |
| TDS Return | NBR | Monthly | 15th of month |
| Import/Export Information | NBR | Monthly | 15th of month |
| Advance Income Tax | NBR | Quarterly | 15th of quarter |
| Annual Income Tax Return | NBR | Annual | 15th January |
| Annual VAT Return (Mushak 9.2) | NBR | Annual | 31st January |
| BIDA Statistics | BIDA | Quarterly | End of quarter |
| Bangladesh Bank Returns | BB | Monthly | 10th of month |

#### 9.5.2 BIDA (Bangladesh Investment Development Authority) Report

**Navigation:** Compliance Reports → BIDA → Quarterly Return

**Required Information:**
```
- Production quantity by product
- Sales value (domestic and export)
- Raw material consumption
- Employment statistics
- Investment figures
- Utility consumption
```

#### 9.5.3 Bangladesh Bank Returns

**Required Reports:**
1. **Schedule A:** Foreign exchange transactions
2. **Schedule B:** Import payments
3. **Schedule C:** Export receipts
4. **Schedule D:** Foreign loans and investments

---

## 10. Cost Accounting

### 10.1 Product Costing

#### 10.1.1 Cost Components

| Cost Element | Percentage | Basis |
|--------------|------------|-------|
| Direct Materials (Raw Milk) | 45-50% | Per liter |
| Direct Labor | 8-10% | Per unit produced |
| Manufacturing Overhead | 15-20% | Allocated by volume |
| Packaging Materials | 8-10% | Per unit |
| **Total Production Cost** | **80-90%** | |
| Administrative Overhead | 5-8% | Allocated |
| Selling & Distribution | 5-10% | Allocated |

#### 10.1.2 Standard Cost Calculation

**Navigation:** Cost Accounting → Setup → Standard Costs

**Standard Cost Card - Pasteurized Milk (1 Liter):**

```
Direct Materials:
  Raw Milk (1.05 liter @ ৳55/liter)        ৳57.75
  Additives (preservatives, etc.)            ৳2.50
  Packaging (bottle/carton)                  ৳8.00
───────────────────────────────────────────────────
Total Direct Materials                        ৳68.25

Direct Labor (0.05 hours @ ৳150/hour)         ৳7.50

Manufacturing Overhead (150% of DL)          ৳11.25
───────────────────────────────────────────────────
STANDARD PRODUCTION COST                      ৳87.00

Add: Administrative Overhead (5%)             ৳4.35
Add: Selling & Distribution (8%)              ৳7.31
───────────────────────────────────────────────────
TOTAL STANDARD COST                           ৳98.66

Add: Profit Margin (15%)                      ৳14.80
───────────────────────────────────────────────────
STANDARD SELLING PRICE (Excl. VAT)           ৳113.46
Add: VAT @ 15%                                ৳17.02
───────────────────────────────────────────────────
STANDARD SELLING PRICE (Incl. VAT)           ৳130.48
```

### 10.2 Department Allocation

#### 10.2.1 Cost Centers

**Primary Cost Centers (Production):**
| Code | Department | Allocation Base |
|------|------------|-----------------|
| CC-001 | Milk Reception | Volume received |
| CC-002 | Processing | Processing hours |
| CC-003 | Packaging | Units packaged |
| CC-004 | Quality Control | Tests performed |
| CC-005 | Cold Storage | Storage days |

**Service Cost Centers:**
| Code | Department | Allocation to |
|------|------------|---------------|
| CC-101 | Maintenance | Production centers (hours) |
| CC-102 | Power Plant | Production centers (kWh) |
| CC-103 | Boiler House | Production centers (steam) |
| CC-104 | Water Treatment | Production centers (liters) |

#### 10.2.2 Cost Allocation Procedure

**Step 1: Allocate Service Centers to Production Centers**

Example - Maintenance Cost Allocation:
```
Total Maintenance Cost: ৳150,000

Allocation:
- Milk Reception (200 hours): ৳40,000
- Processing (350 hours): ৳70,000
- Packaging (150 hours): ৳30,000
- Quality Control (50 hours): ৳10,000
```

**Step 2: Allocate Production Center Costs to Products**

Example - Processing Department Cost Allocation:
```
Total Processing Cost: ৳850,000
Total Processing Hours: 1,200 hours
Rate per hour: ৳708.33

Allocation:
- Pasteurized Milk (800 hrs): ৳566,664
- Yogurt (300 hrs): ৳212,499
- Cheese (100 hrs): ৳70,833
```

### 10.3 Budget vs Actual Analysis

#### 10.3.1 Budget Setup

**Navigation:** Cost Accounting → Budget → Define Budget

**Budget Structure:**
```
Budget Type: Annual Operating Budget
Fiscal Year: 2026
Version: V1 (Approved)

Categories:
- Revenue Budget (by product)
- Production Budget (by product)
- Cost Budget (by element)
- Operating Expense Budget (by department)
- Capital Expenditure Budget
```

#### 10.3.2 Budget vs Actual Report

**Navigation:** Cost Accounting → Reports → Variance Analysis

**Sample Report:**

```
BUDGET VS ACTUAL ANALYSIS - JANUARY 2026

Revenue Analysis:
─────────────────────────────────────────────────────────
Product          Budget      Actual     Variance    %
─────────────────────────────────────────────────────────
Pasteurized Milk ৳600,000   ৳520,000   (৳80,000)  -13%
Yogurt          ৳250,000   ৳285,000    ৳35,000   +14%
Cheese          ৳150,000   ৳145,000    (৳5,000)   -3%
─────────────────────────────────────────────────────────
Total           ৳1,000,000  ৳950,000   (৳50,000)   -5%
═════════════════════════════════════════════════════════

Expense Analysis:
─────────────────────────────────────────────────────────
Category         Budget      Actual     Variance    %
─────────────────────────────────────────────────────────
Raw Materials   ৳720,000   ৳780,000    ৳60,000    +8%
Labor           ৳480,000   ৳490,000    ৳10,000    +2%
Overhead        ৳180,000   ৳190,000    ৳10,000    +6%
S&D Expenses    ৳350,000   ৳375,000    ৳25,000    +7%
Admin Expenses  ৳240,000   ৳250,000    ৳10,000    +4%
─────────────────────────────────────────────────────────
Total           ৳1,970,000 ৳2,085,000  ৳115,000   +6%
═════════════════════════════════════════════════════════

NET RESULT:     (৳970,000) (৳1,135,000) (৳165,000) -17%
```

---

## 11. Year-End Procedures

### 11.1 Year-End Closing Checklist

#### 11.1.1 Pre-Close Activities (December 1-20)

- [ ] Review and clear all pending journal entries
- [ ] Complete all bank reconciliations
- [ ] Verify all AP and AR balances
- [ ] Conduct physical inventory count
- [ ] Review fixed asset register
- [ ] Verify prepayments and accruals
- [ ] Confirm intercompany balances
- [ ] Review provisions and reserves
- [ ] Validate tax computations
- [ ] Prepare year-end adjustments

#### 11.1.2 Closing Activities (December 21-31)

- [ ] Record all December transactions
- [ ] Post depreciation for full year
- [ ] Post amortization entries
- [ ] Calculate and post gratuity provision
- [ ] Calculate and post bonus provision
- [ ] Record inventory adjustments
- [ ] Post all accruals
- [ ] Calculate deferred tax
- [ ] Generate trial balance
- [ ] Review variance analysis
- [ ] Obtain management review
- [ ] Generate draft financial statements

#### 11.1.3 Post-Close Activities (January 1-15)

- [ ] Close accounting periods
- [ ] Generate final financial statements
- [ ] Prepare board report
- [ ] Prepare tax returns
- [ ] Schedule statutory audit
- [ ] Prepare auditor working papers
- [ ] Conduct management review meeting
- [ ] Approve financial statements
- [ ] File regulatory returns
- [ ] Archive year-end documents

### 11.2 Audit Preparation

#### 11.2.1 Auditor Requirements

**Documents to Prepare:**

| Category | Documents |
|----------|-----------|
| General | Trial balance, GL details, Chart of accounts |
| Cash & Bank | Bank statements, reconciliation, petty cash count |
| AR | Aging, confirmation letters, bad debt analysis |
| Inventory | Count sheets, valuation reports, cost sheets |
| Fixed Assets | Register, depreciation schedule, physical verification |
| AP | Aging, confirmation letters, unrecorded liability review |
| Payroll | Salary registers, PF statements, tax challans |
| VAT | Returns, challans, reconciliation |
| Revenue | Sales register, VAT invoices, export documents |
| Expenses | Expense register, supporting documents |

#### 11.2.2 Audit Schedule

| Week | Activity | Responsible |
|------|----------|-------------|
| 1 | Opening meeting, planning | Finance Manager |
| 2 | Cash, bank, AP testing | Accountant |
| 3 | AR, inventory, payroll testing | Senior Accountant |
| 4 | Revenue, expenses, VAT testing | VAT Officer |
| 5 | Final review, closing meeting | Finance Manager |

### 11.3 Tax Return Preparation

#### 11.3.1 Corporate Income Tax Return

**Navigation:** Taxation → Returns → Income Tax

**Return Components:**
1. Financial Statements (audited)
2. Tax computation schedule
3. Depreciation schedule
4. Disallowed expenses schedule
5. Tax credits and exemptions
6. Advance tax paid details
7. TDS certificates received

#### 11.3.2 Tax Computation

```
NET PROFIT/(LOSS) PER ACCOUNTS:          (৳XXX,XXX)

Add: Disallowed Expenses
  Depreciation per books                  ৳XXX,XXX
  Entertainment (excess)                  ৳XXX,XXX
  Penalties and fines                     ৳XXX,XXX
  Provision for bad debts                 ৳XXX,XXX
────────────────────────────────────────────────────
                                          ৳XXX,XXX

Less: Allowable Deductions
  Depreciation per tax rules              ৳XXX,XXX
  Tax depreciation                         ৳XXX,XXX
────────────────────────────────────────────────────
                                          ৳XXX,XXX

TAXABLE INCOME/(LOSS):                    ৳XXX,XXX

Tax @ 25%:                                ৳XXX,XXX
Less: Tax credits
  Advance tax paid                        ৳XXX,XXX
  TDS credits                             ৳XXX,XXX
────────────────────────────────────────────────────
TAX PAYABLE/(REFUNDABLE):                 ৳XXX,XXX
```

---

## 12. Troubleshooting

### 12.1 Common Issues and Solutions

#### 12.1.1 Journal Entry Issues

| Issue | Cause | Solution |
|-------|-------|----------|
| Unbalanced entry | Debits ≠ Credits | Check all lines for correct amounts |
| Invalid account | Account inactive | Activate account or use valid alternative |
| Period closed | Attempting entry in closed period | Request period reopen or use open period |
| Duplicate number | Journal number exists | Use next available number |

#### 12.1.2 Bank Reconciliation Issues

| Issue | Cause | Solution |
|-------|-------|----------|
| Opening balance mismatch | Previous reconciliation not saved | Review and correct prior period |
| Unmatched transactions | Missing entries in system | Create missing transactions |
| Duplicate transactions | Import error | Delete duplicates and re-import |
| Wrong bank selected | Human error | Cancel and restart with correct bank |

#### 12.1.3 VAT Issues

| Issue | Cause | Solution |
|-------|-------|----------|
| Wrong VAT calculation | Wrong rate applied | Check product VAT classification |
| Invalid BIN | Vendor not registered | Verify with NBR database |
| Missing input VAT | Invoice not recorded | Enter invoice with VAT details |
| Return mismatch | Entries not complete | Review all sales and purchase entries |

#### 12.1.4 Payroll Issues

| Issue | Cause | Solution |
|-------|-------|----------|
| Wrong net pay | Incorrect deductions | Review salary structure and deductions |
| Missing employee | Not included in payroll run | Add employee and reprocess |
| Bank transfer failed | Wrong account details | Update bank details and retry |
| Tax calculation error | Wrong tax slab | Review tax setup and recalculate |

### 12.2 Data Correction Procedures

#### 12.2.1 Correcting Posted Transactions

**Procedure:**
1. Document the error
2. Identify correction needed
3. Create reversing entry (if required)
4. Create correct entry
5. Attach supporting documents
6. Get approval from Finance Manager
7. Post correction

#### 12.2.2 Reversing Entries

**Navigation:** General Ledger → Journal Entries → Reverse

**Step-by-Step:**
```
1. Select original journal entry
2. Click "Reverse"
3. Enter reversal reason
4. Select reversal date (usually same as original)
5. System creates exact opposite entry
6. Post reversal
7. Create correct entry
```

### 12.3 System Performance Issues

| Symptom | Possible Cause | Solution |
|---------|----------------|----------|
| Slow report generation | Large data volume | Use date range filters |
| Timeout errors | Network issues | Retry or contact IT |
| Cannot save entry | Validation error | Check all required fields |
| Data not appearing | Cache issue | Refresh or clear cache |
| Login issues | Password expired | Reset password |

---

## 13. Support

### 13.1 Help Desk Contact

| Issue Type | Contact | Method | Response Time |
|------------|---------|--------|---------------|
| System Access | IT Help Desk | Ext. 2001/help@smartdairy.com | 1 hour |
| Functional Query | Finance Manager | Ext. 2005 | 4 hours |
| Technical Issue | System Admin | Ext. 2002/sysadmin@smartdairy.com | 2 hours |
| VAT/Compliance | VAT Officer | Ext. 2008 | Same day |
| Payroll Issues | HR/Payroll | Ext. 2010 | Same day |
| Urgent Issues | After-hours hotline | +880 1XXX-XXXXXX | 30 minutes |

### 13.2 Training Resources

**Internal Resources:**
- Intranet Knowledge Base
- Video Tutorials (Finance Portal)
- Monthly Training Sessions
- User Manuals (all modules)
- Quick Reference Guides

**External Training:**
- NBR VAT Workshops
- ICAB Training Programs
- Software Vendor Training
- Industry Seminars

### 13.3 Document Control

**Document Updates:**
- Manual version control maintained
- Quarterly review scheduled
- Change log maintained
- User feedback incorporated
- Approved by Finance Manager

**Distribution:**
- Electronic version: Finance Portal
- Printed copies: Department heads
- Training copies: Training room

### 13.4 Feedback and Improvement

**To Submit Feedback:**
1. Navigate to: Finance Portal → Feedback
2. Select document: K-008 End User Manual - Accounting
3. Enter feedback details
4. Submit

**Feedback Review Cycle:**
- Monthly review of feedback received
- Quarterly manual update
- Annual comprehensive review

---

## Appendices

### Appendix A: Month-End Checklist

```
MONTH-END CLOSING CHECKLIST
Month: __________ Year: _______

BANK & CASH
□ Bank statements imported for all accounts
□ Bank reconciliations completed
□ Outstanding items reviewed
□ Cash counts verified
□ Petty cash reconciled

ACCOUNTS RECEIVABLE
□ All sales invoices posted
□ Receipts recorded and applied
□ AR aging generated
□ Follow-up actions documented
□ Bad debt provision reviewed

ACCOUNTS PAYABLE
□ All vendor invoices entered
□ Payment batches processed
□ TDS deducted and recorded
□ AP aging generated
□ Accruals posted

INVENTORY
□ Physical count completed (if scheduled)
□ Inventory valuation completed
□ Cost of goods sold calculated
□ Inventory adjustments posted
□ WIP valuation completed

PAYROLL
□ Attendance finalized
□ Payroll processed
□ Bank transfers completed
□ Payslips distributed
□ GL posting verified

GENERAL LEDGER
□ All journals posted
□ Accruals entered
□ Prepayments adjusted
□ Depreciation calculated
□ Allocations completed
□ Intercompany reconciled

VAT & COMPLIANCE
□ Output VAT calculated
□ Input VAT verified
□ VAT reconciliation completed
□ TDS summary prepared
□ Monthly returns filed (if month-end)

REPORTS
□ Trial balance generated
□ Financial statements prepared
□ Variance analysis completed
□ Management reports distributed
□ Period closed

Prepared by: _________________ Date: _______
Reviewed by: _________________ Date: _______
Approved by: _________________ Date: _______
```

### Appendix B: Year-End Checklist

```
YEAR-END CLOSING CHECKLIST
Fiscal Year: _______

PRE-CLOSE (December 1-20)
□ All regular month-end tasks completed
□ Fixed asset verification completed
□ Investment valuations obtained
□ Provision calculations updated
□ Inventory fully counted and valued
□ Intercompany balances confirmed
□ Prepaid/accrued items reviewed

CLOSING ENTRIES (December 21-31)
□ Depreciation - full year
□ Amortization - full year
□ Bad debt provision
□ Inventory obsolescence provision
□ Gratuity provision calculation
□ Bonus provision calculation
□ Deferred tax calculation
□ Prior period adjustments (if any)

FINANCIAL STATEMENTS
□ Trial balance finalized
□ Balance sheet prepared
□ Profit & loss prepared
□ Cash flow statement prepared
□ Notes to accounts drafted
□ Comparative figures verified
□ Budget vs actual prepared

AUDIT PREPARATION
□ Auditor appointment confirmed
□ PBC list items prepared
□ Working papers organized
□ Management representation drafted
□ Supporting schedules prepared

TAXATION
□ Tax computation finalized
□ Tax depreciation schedule
□ Transfer pricing documentation
□ Tax credits verified
□ Tax return prepared

APPROVALS & FILING
□ Board meeting scheduled
□ Financial statements approved
□ Audit report received
□ Tax return filed
□ Regulatory returns filed
□ New year opening balances carried forward

Prepared by: _________________ Date: _______
Reviewed by: _________________ Date: _______
Approved by: _________________ Date: _______
```

### Appendix C: Chart of Accounts Summary

| Range | Category | Count | Example |
|-------|----------|-------|---------|
| 1000-1999 | Assets | 150+ | Cash, Receivables, Inventory, Fixed Assets |
| 2000-2999 | Liabilities | 80+ | Payables, Loans, Provisions, VAT |
| 3000-3999 | Equity | 20+ | Share Capital, Retained Earnings |
| 4000-4999 | Revenue | 30+ | Sales, Other Income |
| 5000-5999 | Cost of Sales | 40+ | Raw Materials, Labor, Overhead |
| 6000-6999 | Operating Expenses | 100+ | Salaries, Rent, Utilities, Marketing |
| 7000-7999 | VAT Accounts | 15+ | Input VAT, Output VAT, TDS |
| 8000-8999 | Payroll Accounts | 25+ | Salaries, PF, Tax, Benefits |
| 9000-9999 | Statistical/Budget | 50+ | Budget accounts, statistical accounts |

### Appendix D: VAT Quick Reference

| Item | Rate | Form |
|------|------|------|
| Standard VAT | 15% | Applied to most goods/services |
| Zero-rated | 0% | Exports, essential medicines |
| Exempt | N/A | Fresh milk, agricultural products |
| Monthly Return | - | Mushak 9.1 (Due: 15th) |
| Annual Return | - | Mushak 9.2 (Due: 31st Jan) |
| Payment Challan | - | Mushak 6.3 |
| Invoice Format | - | Mushak 6.3 compliant |

### Appendix E: Key Contacts

| Role | Name | Extension | Email |
|------|------|-----------|-------|
| Finance Manager | [Name] | 2005 | fm@smartdairy.com |
| GM Finance | [Name] | 2015 | gmf@smartdairy.com |
| VAT Officer | [Name] | 2008 | vat@smartdairy.com |
| Payroll Officer | [Name] | 2010 | payroll@smartdairy.com |
| IT Help Desk | - | 2001 | help@smartdairy.com |
| System Admin | [Name] | 2002 | sysadmin@smartdairy.com |
| HR Manager | [Name] | 2020 | hr@smartdairy.com |

---

## Document Control

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | 2026-01-31 | Technical Writer | Initial release |

---

**End of Document K-008**

*This document is the property of Smart Dairy Ltd. and is confidential. Unauthorized distribution is prohibited.*
