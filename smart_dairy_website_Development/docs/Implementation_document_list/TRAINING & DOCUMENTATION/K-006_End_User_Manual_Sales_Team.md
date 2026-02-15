# End User Manual - Sales Team

**Document ID:** K-006  
**Version:** 1.0  
**Date:** January 31, 2026  
**Author:** Technical Writer  
**Owner:** Training Lead  
**Reviewer:** Sales Manager  

**Target Audience:** Sales Team (B2B sales, order management, customer relations)  
**Classification:** Internal Use - Training Document  

---

## Document Control

| Version | Date | Author | Changes | Approved By |
|---------|------|--------|---------|-------------|
| 1.0 | January 31, 2026 | Technical Writer | Initial release | Sales Manager |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Getting Started](#2-getting-started)
3. [Customer Management](#3-customer-management)
4. [Order Management](#4-order-management)
5. [Product Catalog](#5-product-catalog)
6. [Route Planning (for Field Sales)](#6-route-planning-for-field-sales)
7. [Payment Collection](#7-payment-collection)
8. [Sales Reports](#8-sales-reports)
9. [CRM Features](#9-crm-features)
10. [Mobile App - Sales Features](#10-mobile-app---sales-features)
11. [Troubleshooting](#11-troubleshooting)
12. [Support Contact](#12-support-contact)

---

## 1. Introduction

### 1.1 Manual Overview

Welcome to the **Smart Dairy Web Portal - Sales Team User Manual**. This comprehensive guide is designed to help sales representatives, sales supervisors, and field sales officers effectively use the Smart Dairy B2B sales management system.

### 1.2 Purpose

This manual provides:
- Step-by-step instructions for daily sales operations
- Guidance on customer relationship management
- Order processing and tracking procedures
- Payment collection workflows
- Route planning for field sales
- Reporting and analytics tools

### 1.3 System Overview

The Smart Dairy Sales Portal is a comprehensive B2B sales management system that enables:

| Feature | Description |
|---------|-------------|
| **Customer Management** | Manage B2B customer profiles, credit limits, and purchase history |
| **Order Processing** | Create, modify, and track B2B orders in real-time |
| **Route Planning** | Optimize daily sales routes with GPS tracking |
| **Payment Collection** | Record payments and manage outstanding balances |
| **CRM** | Lead management, follow-ups, and communication tracking |
| **Reporting** | Sales analytics, performance metrics, and target tracking |

### 1.4 User Roles

| Role | Access Level | Primary Functions |
|------|--------------|-------------------|
| **Sales Representative** | Standard | Customer visits, order entry, payment collection |
| **Sales Supervisor** | Enhanced | Team management, approval workflows, reports |
| **Field Sales Officer** | Mobile-focused | Route-based selling, offline order entry |
| **Sales Manager** | Full Access | Analytics, target setting, strategic planning |

### 1.5 Bengali Terms Reference

For effective customer communication, familiarize yourself with these terms:

| English | Bengali (বাংলা) | Usage |
|---------|---------------|-------|
| Customer | কাস্টমার / গ্রাহক | "প্রিয় গ্রাহক" (Dear Customer) |
| Order | অর্ডার | "আপনার অর্ডার" (Your Order) |
| Payment | পেমেন্ট / পরিশোধ | "পেমেন্ট গ্রহণ" (Receive Payment) |
| Delivery | ডেলিভারি | "ডেলিভারি সময়" (Delivery Time) |
| Credit Limit | ক্রেডিট লিমিট | "ক্রেডিট সীমা" |
| Invoice | ইনভয়েস | "বিল / চালান" |
| Discount | ডিসকাউন্ট | "ছাড়" |
| Product | পণ্য | "দুগ্ধ পণ্য" (Dairy Products) |
| Fresh | ফ্রেশ | "তাজা দুধ" (Fresh Milk) |
| Thank You | ধন্যবাদ | "ধন্যবাদ গ্রাহক" |

---

## 2. Getting Started

### 2.1 System Requirements

#### For Web Portal (Desktop/Laptop)
- **Browser:** Chrome 90+, Firefox 88+, Edge 90+, Safari 14+
- **Internet:** Minimum 2 Mbps stable connection
- **Screen Resolution:** 1366x768 or higher

#### For Mobile App
- **Android:** Version 8.0 (Oreo) or higher
- **iOS:** Version 13.0 or higher
- **Storage:** Minimum 100 MB free space
- **GPS:** Required for field sales features

### 2.2 Login Process

#### Step-by-Step Login

**Step 1:** Open your web browser and navigate to:
```
https://sales.smartdairy.bd
```

**Step 2:** Enter your credentials on the login page

| Field | Description | Example |
|-------|-------------|---------|
| Username | Your employee ID or email | EMP001 or sales@smartdairy.bd |
| Password | Your secure password | ******** |

**Step 3:** Click the **"Login" (লগইন)** button

**Step 4:** If MFA is enabled, enter the OTP sent to your registered mobile number

**Screenshot Description:**
> *Figure 2.1: Login Screen*
> - Smart Dairy logo at top center
> - Username and Password input fields
> - "Remember Me" checkbox
> - Blue "Login" button
> - "Forgot Password?" link below

#### First-Time Login

If logging in for the first time:
1. Use the temporary password provided by IT
2. You'll be prompted to change your password
3. Password requirements:
   - Minimum 8 characters
   - At least one uppercase letter
   - At least one number
   - At least one special character

### 2.3 Dashboard Overview

Upon successful login, you'll see the Sales Dashboard with the following sections:

**Screenshot Description:**
> *Figure 2.2: Sales Dashboard*
> - Top navigation bar with user profile
> - Left sidebar menu
> - Main content area with widgets
> - Quick action buttons

#### Dashboard Widgets

| Widget | Description | Refresh Rate |
|--------|-------------|--------------|
| **Today's Targets** | Daily sales target vs achievement | Real-time |
| **Pending Orders** | Orders awaiting processing | Every 5 minutes |
| **Payment Collection** | Today's collection amount | Real-time |
| **Route Status** | Field visit progress | Every 15 minutes |
| **Customer Alerts** | Credit limit warnings, overdue payments | Real-time |
| **Follow-up Reminders** | Scheduled customer calls/visits | Daily at 8 AM |

#### Navigation Menu

```
┌─────────────────────────────────────┐
│  📊 Dashboard                       │
├─────────────────────────────────────┤
│  👥 Customer Management             │
│     ├─ Customer List                │
│     ├─ Add New Customer             │
│     └─ Customer Map                 │
├─────────────────────────────────────┤
│  📦 Order Management                │
│     ├─ Create Order                 │
│     ├─ Order History                │
│     ├─ Bulk Order Entry             │
│     └─ Order Approvals              │
├─────────────────────────────────────┤
│  🛒 Product Catalog                 │
│     ├─ View Products                │
│     ├─ B2B Pricing                  │
│     └─ Promotions                   │
├─────────────────────────────────────┤
│  🗺️ Route Planning                  │
│     ├─ Daily Route                  │
│     ├─ Visit Log                    │
│     └─ GPS Tracking                 │
├─────────────────────────────────────┤
│  💰 Payment Collection              │
│     ├─ Record Payment               │
│     ├─ Outstanding Report           │
│     └─ Reconciliation               │
├─────────────────────────────────────┤
│  📈 Reports & Analytics             │
│     ├─ Sales Summary                │
│     ├─ Performance Reports          │
│     └─ Target Achievement           │
├─────────────────────────────────────┤
│  🤝 CRM                             │
│     ├─ Leads                        │
│     ├─ Follow-ups                   │
│     └─ Communications               │
└─────────────────────────────────────┘
```

### 2.4 Profile Settings

To update your profile:

1. Click on your **Profile Picture/Name** in the top-right corner
2. Select **"My Profile" (আমার প্রোফাইল)**
3. Update the following information:
   - Personal Details
   - Contact Information
   - Profile Photo
   - Notification Preferences
4. Click **"Save Changes"**

---

## 3. Customer Management

### 3.1 View Customer List

The Customer List provides a comprehensive view of all your B2B customers.

#### Accessing Customer List

**Navigation:** Customer Management → Customer List

**Screenshot Description:**
> *Figure 3.1: Customer List Page*
> - Search bar at top
> - Filter dropdowns (Zone, Type, Status)
> - Data table with customer information
> - Pagination controls at bottom
> - Export button (CSV/Excel)

#### Customer List Columns

| Column | Description | Filterable |
|--------|-------------|------------|
| Customer Code | Unique identifier | Yes |
| Business Name | Shop/Company name | Yes (Search) |
| Owner Name | Contact person | Yes (Search) |
| Zone/Area | Geographic location | Yes |
| Customer Type | Retailer, Distributor, Hotel, etc. | Yes |
| Credit Limit | Maximum credit allowed | Yes (Range) |
| Current Balance | Outstanding amount | Yes (Range) |
| Status | Active/Inactive/Blocked | Yes |
| Last Order | Date of most recent order | Yes (Date) |

#### Filtering and Searching

**Search Options:**
1. **Quick Search:** Type in the search box to find by name/code
2. **Advanced Filters:** Click "Filters" button to filter by:
   - Zone/Area
   - Customer Type
   - Credit Status
   - Order History (Last 7/30/90 days)
   - Payment Status

**Example Filter Combination:**
```
Zone: Dhaka North
Customer Type: Retailer
Status: Active
Credit Status: Within Limit
```

#### Exporting Customer Data

To export customer list:
1. Apply desired filters
2. Click **"Export"** button
3. Select format: CSV or Excel
4. Choose fields to include
5. Click **"Download"**

### 3.2 Add New B2B Customers

#### New Customer Registration Process

**Navigation:** Customer Management → Add New Customer

**Screenshot Description:**
> *Figure 3.2: New Customer Registration Form*
> - Tabbed interface: Basic Info, Business Details, Banking, Documents
> - Required fields marked with red asterisk (*)
> - Save as Draft and Submit buttons

#### Step-by-Step: Adding a New Customer

**Step 1: Basic Information**

| Field | Required | Description | Example |
|-------|----------|-------------|---------|
| Business Name | Yes | Registered business name | ABC Dairy Corner |
| Business Type | Yes | Type of establishment | Retail Shop |
| Owner Name | Yes | Full name of owner | Mohammad Ali |
| Contact Number | Yes | Primary mobile number | 017XXXXXXXX |
| Alternative Number | No | Secondary contact | 018XXXXXXXX |
| Email Address | No | Business email | abc@email.com |

**Business Type Options:**
- Retail Shop (খুচরা দোকান)
- Supermarket (সুপারমার্কেট)
- Hotel/Restaurant (হোটেল/রেস্তোরাঁ)
- Bakery (বেকারি)
- Tea Stall (চায়ের দোকান)
- Distributor (ডিস্ট্রিবিউটর)
- Corporate (কর্পোরেট)
- Educational Institution (শিক্ষা প্রতিষ্ঠান)

**Step 2: Address Information**

| Field | Required | Description |
|-------|----------|-------------|
| Division | Yes | Administrative division |
| District | Yes | District name |
| Thana/Upazila | Yes | Local jurisdiction |
| Address Line 1 | Yes | Street/Village name |
| Address Line 2 | No | Additional address details |
| Landmark | No | Nearby notable location |
| GPS Coordinates | No | Auto-captured or manual entry |

**Step 3: Business Details**

| Field | Description | Example |
|-------|-------------|---------|
| Trade License No | Business registration number | TRD-2024-001234 |
| BIN Number | Business Identification Number | 12345678901 |
| TIN Number | Tax Identification Number | 123456789012 |
| Establishment Year | Year business started | 2015 |
| Number of Outlets | For chain businesses | 3 |
| Monthly Volume Estimate | Expected monthly purchase | 500 liters |

**Step 4: Credit and Payment Terms**

| Field | Description | Default |
|-------|-------------|---------|
| Credit Limit (BDT) | Maximum outstanding allowed | 50,000 |
| Credit Period (Days) | Payment due period | 15 days |
| Payment Method | Preferred payment mode | Cash/Cheque/bKash |
| Security Deposit | If applicable | 0 |

**Step 5: Contact Persons**

Add multiple contact persons if needed:

| Field | Description |
|-------|-------------|
| Name | Full name |
| Designation | Role in business |
| Phone | Contact number |
| Email | Email address |
| Is Primary | Main contact person |

**Step 6: Document Upload**

Required documents for verification:

| Document | Format | Max Size |
|----------|--------|----------|
| Trade License | PDF/JPG/PNG | 5 MB |
| NID/Passport of Owner | PDF/JPG/PNG | 2 MB |
| Bank Statement (Last 3 months) | PDF | 10 MB |
| Shop/Establishment Photo | JPG/PNG | 5 MB |
| Signature of Owner | JPG/PNG | 1 MB |

**Step 7: Review and Submit**

1. Review all entered information
2. Verify document uploads
3. Add any special notes
4. Click **"Submit for Approval"**

**Workflow:**
```
Draft → Submitted → Under Review → Approved/Rejected → Active
```

#### Approval Process Timeline

| Stage | Responsible | Timeline |
|-------|-------------|----------|
| Submission | Sales Rep | Immediate |
| Verification | Sales Supervisor | Within 24 hours |
| Credit Check | Finance Team | Within 48 hours |
| Final Approval | Sales Manager | Within 72 hours |
| Account Activation | System | Immediate after approval |

### 3.3 Customer Profiles and History

#### Viewing Customer Profile

**Navigation:** Customer List → Click on Customer Name/Code

**Screenshot Description:**
> *Figure 3.3: Customer Profile Page*
> - Header with customer name and status badge
> - Tab navigation: Overview, Orders, Payments, Visits, Documents
> - Action buttons: Edit, Create Order, Record Payment

#### Profile Sections

**1. Overview Tab**

```
┌─────────────────────────────────────────────────────────┐
│  ABC Dairy Corner                    [Status: Active]   │
│  📍 Mirpur-10, Dhaka                                    │
│  📞 01712345678                                         │
├─────────────────────────────────────────────────────────┤
│  CREDIT SUMMARY           │  SALES SUMMARY              │
│  ─────────────────        │  ───────────────            │
│  Credit Limit: ৳100,000   │  This Month: ৳450,000       │
│  Used: ৳65,000            │  Last Month: ৳420,000       │
│  Available: ৳35,000       │  Growth: +7.1%              │
│  ─────────────────        │  ───────────────            │
│  Last Payment: ৳20,000    │  Total Orders: 45           │
│  Payment Date: 25-Jan-26  │  Avg. Order: ৳10,000        │
└─────────────────────────────────────────────────────────┘
```

**2. Order History Tab**

View all historical orders:

| Column | Description |
|--------|-------------|
| Order ID | Unique order number |
| Date | Order placement date |
| Amount | Total order value |
| Status | Delivered/Pending/Cancelled |
| Products | Summary of items ordered |
| Invoice | Link to invoice PDF |

**Filter Options:**
- Date Range
- Order Status
- Product Category
- Amount Range

**3. Payment History Tab**

| Column | Description |
|--------|-------------|
| Date | Payment date |
| Amount | Payment amount |
| Mode | Cash/Cheque/bKash/Bank Transfer |
| Reference | Transaction/Check number |
| Receipt | Download link |
| Notes | Additional information |

**4. Visit History Tab**

Track all sales visits:

| Column | Description |
|--------|-------------|
| Date | Visit date |
| Purpose | Order collection/Payment/Follow-up |
| Outcome | Successful/No order/Not available |
| Notes | Visit summary |
| GPS Location | Map view of visit location |
| Photo | Visit verification photo |

### 3.4 Credit Limit Checks

#### Understanding Credit Limits

The system enforces credit limits to manage financial risk:

**Credit Limit Components:**

```
┌──────────────────────────────────────────┐
│  CREDIT LIMIT BREAKDOWN                  │
├──────────────────────────────────────────┤
│  Total Credit Limit:         ৳100,000    │
│  ─────────────────────────────────────   │
│  Outstanding Invoices:      -৳45,000     │
│  Pending Orders:            -৳20,000     │
│  ─────────────────────────────────────   │
│  Available Credit:           ৳35,000     │
└──────────────────────────────────────────┘
```

#### Credit Status Indicators

| Status | Color | Meaning | Action Required |
|--------|-------|---------|-----------------|
| Healthy | 🟢 Green | < 70% limit used | None |
| Warning | 🟡 Yellow | 70-90% limit used | Monitor closely |
| Critical | 🔴 Red | > 90% limit used | Request payment |
| Exceeded | ⚫ Black | Limit exceeded | Stop new orders |

#### Checking Credit Before Order

**Automatic Check:**
- System automatically checks credit when creating orders
- Warning displayed if order exceeds available credit

**Manual Check:**
1. Open Customer Profile
2. View "Credit Summary" section
3. Check "Available Credit" amount

#### Credit Limit Increase Request

To request a credit limit increase:

1. Open customer profile
2. Click **"Request Credit Increase"**
3. Enter requested amount
4. Provide justification:
   - Increased business volume
   - Payment history
   - Market potential
5. Upload supporting documents (if any)
6. Submit for approval

**Approval Hierarchy:**

| Increase Amount | Approved By |
|-----------------|-------------|
| Up to ৳50,000 | Sales Supervisor |
| ৳50,001 - ৳200,000 | Sales Manager |
| Above ৳200,000 | Finance Manager |

---

## 4. Order Management

### 4.1 Create B2B Orders

#### Order Creation Process

**Navigation:** Order Management → Create Order

**Screenshot Description:**
> *Figure 4.1: Order Creation Page*
> - Customer selection dropdown
> - Product selection panel
> - Order summary sidebar
> - Delivery options section
> - Notes and special instructions

#### Step-by-Step: Creating a New Order

**Step 1: Select Customer**

1. Click **"Select Customer"** field
2. Search by:
   - Customer Code
   - Business Name
   - Owner Name
   - Phone Number
3. Click on the desired customer
4. System displays:
   - Customer details
   - Credit status
   - Last order information
   - Special pricing (if applicable)

**Step 2: Add Products**

Product selection interface:

```
┌──────────────────────────────────────────────────────────┐
│  PRODUCT CATALOG                          [Search...]    │
├──────────────────────────────────────────────────────────┤
│  🥛 Liquid Milk              │  🧈 Butter & Ghee         │
│  ───────────────             │  ───────────────          │
│  □ Full Cream Milk 1L        │  □ Butter 200g            │
│    ৳85/liter                 │    ৳180/pack              │
│                              │                           │
│  □ Full Cream Milk 500ml     │  □ Ghee 500g              │
│    ৳45/pack                  │    ৳450/jar               │
│                              │                           │
│  □ Low Fat Milk 1L           │  🧀 Cheese & Yogurt       │
│    ৳80/liter                 │  ───────────────          │
│                              │  □ Processed Cheese 200g  │
│  □ UHT Milk 1L               │    ৳220/pack              │
│    ৳90/pack                  │                           │
└──────────────────────────────────────────────────────────┘
```

For each product:
1. Check the checkbox to select
2. Enter quantity in the field
3. System calculates:
   - Base price
   - Applicable discount
   - Tax (if applicable)
   - Line total

**Step 3: Review Pricing**

Pricing components shown:

| Component | Description | Example |
|-----------|-------------|---------|
| Base Price | Standard B2B price | ৳85.00 |
| Volume Discount | Based on quantity | -৳2.00 |
| Special Price | Customer-specific | -৳3.00 |
| Net Price | After discounts | ৳80.00 |
| VAT (if applicable) | 15% VAT on certain products | ৳12.00 |
| **Line Total** | | **৳92.00** |

**Step 4: Set Delivery Details**

| Field | Required | Options |
|-------|----------|---------|
| Delivery Date | Yes | Calendar selection |
| Delivery Time Slot | Yes | Morning (6-10 AM), Afternoon (2-6 PM) |
| Delivery Address | Yes | Select from saved addresses |
| Special Instructions | No | Any delivery notes |

**Step 5: Order Summary Review**

```
┌─────────────────────────────────────────┐
│  ORDER SUMMARY                          │
├─────────────────────────────────────────┤
│  Subtotal:                   ৳25,000    │
│  Volume Discount:           -৳1,000     │
│  Special Discount:          -৳500       │
│  ─────────────────────────────────────  │
│  Net Amount:                 ৳23,500    │
│  VAT (15%):                  ৳3,525     │
│  ─────────────────────────────────────  │
│  TOTAL:                      ৳27,025    │
├─────────────────────────────────────────┤
│  Available Credit:           ৳35,000    │
│  Status: ✅ Within Limit                │
└─────────────────────────────────────────┘
```

**Step 6: Submit Order**

1. Review all details
2. Add internal notes (if any)
3. Click **"Submit Order"**
4. System generates Order ID
5. Order confirmation displayed

**Order Confirmation Includes:**
- Order ID (e.g., SD-ORD-20260131-001234)
- Expected delivery date/time
- Summary of items
- Total amount
- Print/Download options

### 4.2 Modify Existing Orders

#### When Modifications Are Allowed

| Order Status | Modification Allowed | Actions Possible |
|--------------|----------------------|------------------|
| Pending | Yes | Edit all fields |
| Confirmed | Limited | Delivery address, instructions |
| Processing | No | Cancel only |
| Dispatched | No | Contact support |
| Delivered | No | Create return request |

#### Modification Process

**Navigation:** Order Management → Order History → Select Order

**Screenshot Description:**
> *Figure 4.2: Order Detail Page*
> - Order status badge
> - Timeline of order progress
> - Edit button (if applicable)
> - Cancel button

**Step-by-Step: Modifying an Order**

1. Search and open the order
2. Check current status
3. Click **"Edit Order"** (if available)
4. Make necessary changes:
   - Add/remove products
   - Change quantities
   - Update delivery details
5. Provide reason for modification
6. Click **"Save Changes"**

**Modification Approval:**
- Minor changes: Automatic approval
- Major changes (amount increase > 20%): Requires supervisor approval

### 4.3 Order Status Tracking

#### Order Status Definitions

| Status | Description | Next Action |
|--------|-------------|-------------|
| **Draft** | Order saved but not submitted | Complete and submit |
| **Pending** | Submitted, awaiting confirmation | Await processing |
| **Confirmed** | Order accepted by system | Prepare for dispatch |
| **Processing** | Being prepared at warehouse | - |
| **Ready for Dispatch** | Packed and ready | Await pickup |
| **Dispatched** | Out for delivery | Track delivery |
| **In Transit** | Being delivered | Monitor progress |
| **Delivered** | Successfully delivered | Confirm receipt |
| **Partially Delivered** | Some items pending | Check details |
| **Cancelled** | Order cancelled | - |
| **Returned** | Items returned | Process return |

#### Tracking Orders

**Real-time Tracking:**

```
┌────────────────────────────────────────────────────────────┐
│  ORDER TIMELINE: SD-ORD-20260131-001234                    │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  ✅ Order Placed          30 Jan, 10:30 AM                │
│      By: Sales Rep (EMP001)                                │
│                                                            │
│  ✅ Order Confirmed       30 Jan, 11:15 AM                │
│      By: System                                            │
│                                                            │
│  ✅ Processing Started    30 Jan, 02:00 PM                │
│      At: Dhaka Central Warehouse                           │
│                                                            │
│  ✅ Packed                31 Jan, 06:00 AM                │
│      Items: 12/12 packed                                   │
│                                                            │
│  🚚 Out for Delivery      31 Jan, 08:30 AM                │
│      Vehicle: DHK-TA-11-1234                               │
│      Driver: Karim (017XXXXXXXX)                           │
│                                                            │
│  ⏳ Expected Delivery     31 Jan, 10:00 AM                │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

**Live Tracking Features:**
- GPS location of delivery vehicle
- Estimated time of arrival (ETA)
- Delivery confirmation with signature/photo
- Real-time status updates

### 4.4 Bulk Order Entry

#### When to Use Bulk Entry

Use bulk order entry for:
- Regular standing orders from multiple customers
- Monthly/quarterly bulk ordering
- Festival season high-volume orders
- Corporate catering orders

#### Bulk Order Process

**Navigation:** Order Management → Bulk Order Entry

**Screenshot Description:**
> *Figure 4.3: Bulk Order Entry Page*
> - Excel template download option
> - File upload area
> - Validation results panel
> - Summary preview

**Step-by-Step: Bulk Order Entry**

**Step 1: Download Template**

1. Click **"Download Excel Template"**
2. Template includes columns:
   - Customer Code
   - Customer Name
   - Product Code
   - Product Name
   - Quantity
   - Unit
   - Delivery Date
   - Special Instructions

**Step 2: Fill Template**

Example data:

| Customer Code | Customer Name | Product Code | Product Name | Quantity | Unit | Delivery Date |
|---------------|---------------|--------------|--------------|----------|------|---------------|
| CUST001 | ABC Store | MILK001 | Full Cream 1L | 50 | liter | 2026-02-01 |
| CUST001 | ABC Store | YOG001 | Yogurt 500g | 20 | pack | 2026-02-01 |
| CUST002 | XYZ Hotel | MILK001 | Full Cream 1L | 100 | liter | 2026-02-01 |

**Step 3: Upload File**

1. Click **"Choose File"** or drag and drop
2. Select your filled Excel file
3. Click **"Upload & Validate"**

**Step 4: Review Validation Results**

System checks for:
- Valid customer codes
- Product availability
- Credit limit compliance
- Date validity

| Validation | Status | Action |
|------------|--------|--------|
| Customer codes | ✅ Passed | - |
| Product codes | ⚠️ 2 warnings | Review |
| Credit limits | ❌ 1 error | Fix required |
| Delivery dates | ✅ Passed | - |

**Step 5: Fix Errors (if any)**

1. Review error details
2. Download error report
3. Fix issues in Excel
4. Re-upload

**Step 6: Submit Orders**

1. Review summary:
   - Total orders: 45
   - Total amount: ৳1,250,000
   - Customers: 12
2. Click **"Submit All Orders"**
3. Confirmation with order IDs list

---

## 5. Product Catalog

### 5.1 View Products and Pricing

#### Accessing Product Catalog

**Navigation:** Product Catalog → View Products

**Screenshot Description:**
> *Figure 5.1: Product Catalog Page*
> - Category filters on left sidebar
> - Product grid view
> - Quick view modal option
> - Price display with unit

#### Product Categories

| Category | Products | Target Segment |
|----------|----------|----------------|
| **Liquid Milk** | Full Cream, Low Fat, UHT, Flavored | All B2B |
| **Yogurt & Curd** | Plain, Flavored, Greek, Mishti Doi | Retail, Hotels |
| **Butter & Ghee** | Table Butter, Cooking Butter, Ghee | Bakeries, Hotels |
| **Cheese** | Processed, Mozzarella, Cream Cheese | Restaurants |
| **Ice Cream** | Various flavors and sizes | Retail, Hotels |
| **Powdered Milk** | Full Cream, Skimmed | Distributors |
| **Infant Nutrition** | Formula milk | Pharmacies |

#### Product Information Display

Each product card shows:

```
┌─────────────────────────────┐
│  [Product Image]            │
│                             │
│  Full Cream Milk            │
│  1 Liter Pack               │
│                             │
│  MRP: ৳95                   │
│  B2B Price: ৳85             │
│  Min. Order: 20 liters      │
│                             │
│  [View Details] [Add to     │
│   Order]                    │
└─────────────────────────────┘
```

**Product Details Include:**
- Product specifications
- Nutritional information
- Shelf life
- Storage requirements
- Packaging details
- Availability status

### 5.2 B2B Pricing Tiers

#### Understanding Pricing Structure

Smart Dairy uses a tiered pricing system for B2B customers:

| Tier | Monthly Volume | Discount | Credit Terms |
|------|----------------|----------|--------------|
| **Standard** | < ৳50,000 | 5% | 7 days |
| **Silver** | ৳50,000 - ৳100,000 | 8% | 15 days |
| **Gold** | ৳100,000 - ৳250,000 | 12% | 21 days |
| **Platinum** | > ৳250,000 | 15% | 30 days |

#### Viewing Customer-Specific Pricing

1. Open Customer Profile
2. Navigate to "Pricing" tab
3. View negotiated rates:
   - Standard catalog price
   - Customer-specific price
   - Effective discount %
   - Validity period

#### Price Change Notifications

The system notifies when:
- Prices are updated
- Special promotions are available
- Volume-based discounts are earned
- Contract prices are expiring

### 5.3 Promotions and Discounts

#### Current Promotions View

**Navigation:** Product Catalog → Promotions

**Screenshot Description:**
> *Figure 5.2: Promotions Page*
> - Active promotions banner
> - Promotion cards with details
> - Applicable products list
> - Validity dates

#### Promotion Types

| Type | Description | Example |
|------|-------------|---------|
| **Volume Discount** | Discount based on quantity | Buy 100L, get 5% off |
| **Bundle Offer** | Combined product discount | Milk + Yogurt combo |
| **Seasonal Promo** | Time-limited offers | Eid special pricing |
| **New Product Launch** | Introductory pricing | 20% off new cheese |
| **Loyalty Rewards** | Based on purchase history | Extra 2% for 1+ year customers |
| **Clearance Sale** | Near-expiry products | Up to 50% off |

#### Applying Promotions

**Automatic Application:**
- System automatically applies applicable promotions
- Discounts calculated at checkout
- Multiple promotions prioritized by value

**Manual Promotion Code:**
1. During order creation, click "Apply Promo Code"
2. Enter promotion code
3. System validates and applies discount
4. Discount shown in order summary

---

## 6. Route Planning (for Field Sales)

### 6.1 Daily Route Assignment

#### Understanding Route Planning

Field sales representatives use the route planning module to optimize daily customer visits.

**Navigation:** Route Planning → Daily Route

**Screenshot Description:**
> *Figure 6.1: Route Planning Interface*
> - Map view with customer locations
> - Route optimization panel
> - Customer visit list
> - Distance and time estimates

#### Daily Route Assignment Process

**Step 1: Access Today's Route**

Upon login, the system displays:

```
┌────────────────────────────────────────────────────────────┐
│  TODAY'S ROUTE: February 1, 2026                          │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  📊 Route Summary:                                         │
│     Total Customers: 18                                    │
│     Estimated Distance: 42 km                              │
│     Estimated Time: 6 hours 30 minutes                     │
│     Expected Orders: ৳150,000                              │
│                                                            │
│  🎯 Priority Customers:                                    │
│     • ABC Store (Overdue payment)                          │
│     • XYZ Hotel (New product demo)                         │
│     • Fresh Mart (Credit limit review)                     │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

**Step 2: Review and Optimize Route**

System suggests optimal route based on:
- Geographic clustering
- Customer priority levels
- Expected visit duration
- Traffic patterns
- Historical data

**Optimization Options:**
1. **Accept Suggested Route** - Use system recommendation
2. **Manual Reorder** - Drag and drop to rearrange
3. **Add/Remove Customers** - Modify route dynamically
4. **Set Start/End Points** - Define route endpoints

**Step 3: Start Route**

1. Click **"Start Route"**
2. GPS tracking activates
3. First customer navigation begins
4. Visit timer starts

### 6.2 Customer Visit Logging

#### Visit Check-In Process

**At Customer Location:**

1. Click **"Check In"** for current customer
2. System verifies GPS location (within 100m radius)
3. Upload visit photo (optional but recommended)
4. Select visit purpose:
   - Order Collection (অর্ডার সংগ্রহ)
   - Payment Collection (পেমেন্ট সংগ্রহ)
   - Follow-up (ফলো-আপ)
   - Complaint Resolution (অভিযোগ সমাধান)
   - New Product Demo (নতুন পণ্য প্রদর্শন)
   - Relationship Visit (সম্পর্ক পরিদর্শন)

**Screenshot Description:**
> *Figure 6.2: Visit Check-In Screen*
> - GPS location verification
> - Camera access for photo
> - Purpose selection dropdown
> - Notes input area

#### Recording Visit Outcomes

**After Customer Meeting:**

1. Click **"Record Outcome"**
2. Select outcome type:

| Outcome | Description | Next Action |
|---------|-------------|-------------|
| Order Taken | Successfully collected order | Process order |
| Payment Received | Collected outstanding payment | Record payment |
| No Order | Customer didn't place order | Schedule follow-up |
| Follow-up Required | Need to revisit | Set reminder |
| Complaint Raised | Customer issue reported | Escalate to support |
| Not Available | Customer not present | Reschedule visit |
| Shop Closed | Business closed | Record for tracking |

3. Enter visit notes (voice-to-text supported)
4. Record any commitments made
5. Schedule follow-up if needed
6. Click **"Check Out"**

#### Visit Notes Template

```
Visit Date: [Auto-filled]
Customer: [Auto-filled]
Purpose: [Selected]

Discussion Summary:
────────────────────────
• [Key points discussed]
• [Customer feedback]
• [Competitor mentions]

Action Items:
────────────────────────
□ [Action 1]
□ [Action 2]

Next Visit: [Date if scheduled]
Special Notes: [Any important info]
```

### 6.3 GPS Tracking

#### Real-Time Location Tracking

The system tracks field sales location for:
- Route optimization verification
- Visit authenticity
- Safety monitoring
- Performance analysis

**Privacy Note:**
- Tracking active only during working hours (configurable)
- Tracking stops when "End Day" is clicked
- Location data used for business purposes only

#### GPS Features

| Feature | Description | Usage |
|---------|-------------|-------|
| Live Location | Real-time position on map | Supervisors can view |
| Route History | Path taken during day | Performance review |
| Geofencing | Alert if deviating from route | Quality control |
| Visit Verification | Confirm presence at customer | Fraud prevention |
| Distance Calculation | Auto-calculate travel | Expense reimbursement |

#### Viewing Route Progress

Supervisors can view:

```
┌────────────────────────────────────────────────────────────┐
│  FIELD SALES TRACKING - Mohammad Ali (EMP001)             │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  Status: 🟢 On Route                                       │
│  Current Location: Mirpur-10 Circle                        │
│  Last Update: 2 minutes ago                                │
│                                                            │
│  Progress:                                                 │
│  ████████████████░░░░░░░░░░ 12/18 customers visited       │
│                                                            │
│  Today's Summary:                                          │
│  • Distance Covered: 28 km                                │
│  • Orders Collected: ৳85,000                              │
│  • Payments Collected: ৳32,000                            │
│  • Current Customer: ABC Store                            │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

### 6.4 Offline Order Entry

#### Working in Offline Mode

Field sales can continue working even without internet connectivity:

**When Offline:**
- System switches to offline mode automatically
- Data stored locally on device
- Full functionality maintained
- Auto-sync when connection restored

**Offline Capabilities:**

| Feature | Offline Support | Notes |
|---------|-----------------|-------|
| Customer List | ✅ Yes | Cached data |
| Product Catalog | ✅ Yes | Cached with prices |
| Order Entry | ✅ Yes | Stored locally |
| Payment Recording | ✅ Yes | Receipts queued |
| Visit Logging | ✅ Yes | GPS + timestamp saved |
| Photos | ✅ Yes | Uploaded later |

#### Sync Process

**Automatic Sync:**
- Occurs when connection restored
- Uploads pending orders
- Syncs visit logs
- Updates customer data

**Manual Sync:**
1. Go to Settings → Sync Data
2. Click **"Sync Now"**
3. Review sync status
4. Resolve any conflicts

**Sync Status Indicators:**

| Icon | Meaning | Action |
|------|---------|--------|
| 🟢 | Fully synced | None |
| 🟡 | Syncing in progress | Wait |
| 🔴 | Sync failed | Retry manually |
| ⚪ | Offline mode | Check connection |

---

## 7. Payment Collection

### 7.1 Record Payments

#### Payment Collection Process

**Navigation:** Payment Collection → Record Payment

**Screenshot Description:**
> *Figure 7.1: Payment Recording Form*
> - Customer search field
> - Outstanding amount display
> - Payment mode selection
> - Reference number input
> - Receipt generation

#### Step-by-Step: Recording a Payment

**Step 1: Select Customer**

1. Search for customer by code/name/phone
2. System displays:
   - Customer details
   - Total outstanding balance
   - Invoice-wise breakdown
   - Credit limit status

**Outstanding Invoice Display:**

```
┌────────────────────────────────────────────────────────────┐
│  OUTSTANDING INVOICES - ABC Dairy Corner                  │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  Invoice       Date         Amount    Due Date    Status   │
│  ─────────────────────────────────────────────────────────│
│  INV-001234   15-Jan-26    ৳25,000   30-Jan-26    ⏰ Due  │
│  INV-001256   20-Jan-26    ৳18,000   04-Feb-26    ✅ OK   │
│  INV-001289   25-Jan-26    ৳22,000   09-Feb-26    ✅ OK   │
│                                                            │
│  Total Outstanding: ৳65,000                               │
│  Credit Limit: ৳100,000                                    │
│  Available: ৳35,000                                        │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

**Step 2: Enter Payment Details**

| Field | Required | Description |
|-------|----------|-------------|
| Payment Amount | Yes | Amount being collected |
| Payment Date | Yes | Date of payment (default: today) |
| Payment Mode | Yes | Cash/Cheque/bKash/Bank Transfer/Card |
| Reference No | Conditional | Check/Transaction number |
| Bank Name | For Cheque | Issuing bank |
| Collection Location | Yes | At customer site/office/other |

**Step 3: Allocate Payment (if partial)**

If payment is less than total outstanding:

1. System suggests invoice allocation (oldest first)
2. You can manually allocate:
   - Select specific invoices
   - Enter amount for each
   - Or use "Auto Allocate"

**Step 4: Generate Receipt**

1. Review payment details
2. Add notes if needed
3. Click **"Record Payment & Generate Receipt"**
4. Receipt generated with unique number
5. Options:
   - Print receipt
   - Send via SMS/WhatsApp
   - Email receipt
   - Save as PDF

**Receipt Format:**

```
┌────────────────────────────────────────────────────────────┐
│              SMART DAIRY LIMITED                           │
│           Payment Receipt                                  │
│                                                            │
│  Receipt No: REC-20260201-001567                          │
│  Date: February 1, 2026                                    │
│                                                            │
│  Received From: ABC Dairy Corner                          │
│  Customer Code: CUST001                                    │
│                                                            │
│  Amount Received: ৳25,000                                  │
│  Mode: Cash                                                │
│                                                            │
│  Against Invoice(s):                                       │
│  • INV-001234: ৳25,000                                    │
│                                                            │
│  Collected By: Mohammad Ali (EMP001)                      │
│                                                            │
│  Outstanding Balance: ৳40,000                              │
│                                                            │
│  [Stamp]                      [Signature]                  │
│  This is a computer generated receipt.                     │
└────────────────────────────────────────────────────────────┘
```

### 7.2 Payment Reconciliation

#### Daily Reconciliation Process

End-of-day reconciliation ensures all collections are accounted for:

**Navigation:** Payment Collection → Reconciliation

**Screenshot Description:**
> *Figure 7.2: Reconciliation Dashboard*
> - Today's collection summary
> - Mode-wise breakdown
> - Unreconciled items list
> - Cash denomination calculator

#### Step-by-Step: Daily Reconciliation

**Step 1: Review Collections**

System shows summary:

```
┌────────────────────────────────────────────────────────────┐
│  DAILY COLLECTION SUMMARY - Feb 1, 2026                   │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  Payment Mode          Amount      Count    Status        │
│  ─────────────────────────────────────────────────────────│
│  Cash                  ৳45,000     8        ⏳ Pending    │
│  Cheque                ৳25,000     3        ⏳ Pending    │
│  bKash                 ৳15,000     5        ✅ Verified   │
│  Bank Transfer         ৳35,000     2        ✅ Verified   │
│                                                            │
│  Total Collected: ৳120,000                                │
│  Total Reconciled: ৳50,000                                │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

**Step 2: Cash Reconciliation**

1. Count physical cash
2. Enter denomination-wise count:

| Denomination | Count | Amount |
|--------------|-------|--------|
| ৳1000 | 25 | ৳25,000 |
| ৳500 | 30 | ৳15,000 |
| ৳200 | 10 | ৳2,000 |
| ৳100 | 20 | ৳2,000 |
| ৳50 | 10 | ৳500 |
| ৳20 | 25 | ৳500 |
| **Total** | | **৳45,000** |

3. Compare with system amount
4. Note any discrepancy

**Step 3: Cheque Verification**

1. Verify physical cheques match records
2. Check:
   - Bank name
   - Amount in figures and words
   - Date
   - Signature
3. Mark as verified or flag issues

**Step 4: Digital Payment Verification**

- bKash/Bank transfers auto-verified via API
- Review any failed/pending transactions

**Step 5: Submit Reconciliation**

1. Attach cash deposit slip (if deposited)
2. Note any discrepancies with reasons
3. Submit for supervisor approval

### 7.3 Outstanding Balance Reports

#### Accessing Outstanding Reports

**Navigation:** Payment Collection → Outstanding Report

**Screenshot Description:**
> *Figure 7.3: Outstanding Report Page*
> - Filter options (Date, Zone, Customer type)
> - Aging analysis chart
> - Detailed report table
> - Export options

#### Report Types

| Report | Description | Use Case |
|--------|-------------|----------|
| **Customer-wise** | Outstanding by customer | Collection prioritization |
| **Aging Analysis** | Outstanding by due date | Risk assessment |
| **Zone-wise** | Geographic outstanding | Territory analysis |
| **Sales Rep-wise** | By responsible salesperson | Performance review |
| **Overdue** | Past due payments | Urgent collection |

#### Aging Analysis

```
┌────────────────────────────────────────────────────────────┐
│  AGING ANALYSIS - Outstanding Receivables                 │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  Period              Amount         % of Total   Count    │
│  ─────────────────────────────────────────────────────────│
│  Current (0-30)      ৳450,000       45%          45       │
│  31-60 days          ৳250,000       25%          28       │
│  61-90 days          ৳180,000       18%          15       │
│  91-120 days         ৳80,000        8%           8        │
│  120+ days           ৳40,000        4%           5        │
│                                                            │
│  Total Outstanding:  ৳1,000,000     100%         101      │
│                                                            │
│  Visual:                                                   │
│  ████████████████████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░       │
│  ███████████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░       │
│  ████████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░       │
│  ███░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░       │
│  ██░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░       │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

#### Collection Priority List

System generates priority list based on:
- Days overdue
- Outstanding amount
- Customer history
- Last contact date

**Priority Levels:**

| Priority | Criteria | Action |
|----------|----------|--------|
| 🔴 Critical | > 90 days overdue | Immediate visit required |
| 🟠 High | 61-90 days overdue | Call + schedule visit |
| 🟡 Medium | 31-60 days overdue | Follow-up call |
| 🟢 Low | Current-30 days | Routine follow-up |

---

## 8. Sales Reports

### 8.1 Daily Sales Summary

#### Accessing Daily Summary

**Navigation:** Reports & Analytics → Sales Summary

**Screenshot Description:**
> *Figure 8.1: Daily Sales Summary Dashboard*
> - Date selector
> - KPI cards
> - Trend charts
> - Detailed breakdown tables

#### Daily Summary Components

```
┌────────────────────────────────────────────────────────────┐
│  DAILY SALES SUMMARY - February 1, 2026                   │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  KEY METRICS                                               │
│  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐       │
│  │   ৳250,000   │ │     28       │ │     45       │       │
│  │    Orders    │ │  Customers   │ │   Invoices   │       │
│  └──────────────┘ └──────────────┘ └──────────────┘       │
│  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐       │
│  │   ৳120,000   │ │    98%       │ │     3.2      │       │
│  │  Collection  │ │   Target     │ │  Avg Order   │       │
│  └──────────────┘ └──────────────┘ └──────────────┘       │
│                                                            │
│  TARGET ACHIEVEMENT                                        │
│  Daily Target: ৳300,000                                   │
│  Achieved: ৳250,000 (83%)                                 │
│  ████████████████████████████████░░░░░░░░░░ 83%           │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

#### Sales Breakdown

| Category | Amount | % of Total |
|----------|--------|------------|
| Liquid Milk | ৳125,000 | 50% |
| Yogurt & Curd | ৳50,000 | 20% |
| Butter & Ghee | ৳37,500 | 15% |
| Cheese | ৳25,000 | 10% |
| Other Products | ৳12,500 | 5% |

#### Hourly Sales Trend

```
Sales by Hour:
8 AM  ████████ ৳35,000
9 AM  ████████████████ ৳70,000
10 AM ██████████████ ৳60,000
11 AM ██████████ ৳45,000
12 PM ██████ ৳25,000
1 PM  ████ ৳15,000

[Chart showing peak sales hours]
```

### 8.2 Customer-wise Reports

#### Customer Performance Analysis

**Navigation:** Reports & Analytics → Customer-wise Reports

**Report Columns:**

| Column | Description |
|--------|-------------|
| Customer Code | Unique identifier |
| Business Name | Customer name |
| This Month | Current month sales |
| Last Month | Previous month sales |
| Growth % | Month-over-month growth |
| YTD Sales | Year-to-date total |
| Avg Order Value | Average transaction size |
| Visit Frequency | Monthly visit count |
| Last Order Date | Most recent activity |

#### Top Customers Report

```
┌────────────────────────────────────────────────────────────┐
│  TOP 10 CUSTOMERS - January 2026                          │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  Rank  Customer              Sales      Growth   Status   │
│  ─────────────────────────────────────────────────────────│
│  1     XYZ Hotel Group       ৳450,000   +15%     ⬆️       │
│  2     ABC Supermarket       ৳380,000   +8%      ⬆️       │
│  3     Fresh Mart Chain      ৳320,000   -3%      ⬇️       │
│  4     Sunrise Bakery        ৳280,000   +22%     ⬆️       │
│  5     Royal Restaurant      ৳250,000   +5%      ⬆️       │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

#### Customer Activity Analysis

| Status | Count | Description |
|--------|-------|-------------|
| Active | 245 | Ordered in last 30 days |
| Dormant | 45 | No order in 31-60 days |
| Inactive | 23 | No order in 61-90 days |
| At Risk | 12 | No order in 90+ days |
| New | 18 | First order this month |

### 8.3 Target vs Achievement

#### Understanding Targets

**Navigation:** Reports & Analytics → Target Achievement

**Screenshot Description:**
> *Figure 8.2: Target Achievement Dashboard*
> - Target vs actual comparison
> - Progress bars
> - Trend lines
> - Individual product targets

#### Target Categories

| Type | Measurement | Frequency |
|------|-------------|-----------|
| Revenue Target | Total sales value | Monthly/Quarterly/Annual |
| Volume Target | Units/Liters sold | Monthly |
| Collection Target | Payment collection | Monthly |
| New Customer Target | New acquisitions | Monthly |
| Visit Target | Customer visits | Daily/Weekly |

#### Performance Dashboard

```
┌────────────────────────────────────────────────────────────┐
│  TARGET ACHIEVEMENT - January 2026                        │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  REVENUE TARGET                                            │
│  Target: ৳1,500,000                                       │
│  Achieved: ৳1,425,000                                     │
│  Progress: ████████████████████████████████████████░░░ 95%│
│  Gap: ৳75,000 (5 days remaining)                          │
│                                                            │
│  PRODUCT-WISE PERFORMANCE                                  │
│  ─────────────────────────────────────────────────────────│
│  Full Cream Milk    105%  ██████████████████████████████  │
│  Yogurt              92%  ████████████████████████████░░  │
│  Butter & Ghee       88%  ██████████████████████████░░░░  │
│  Cheese             110%  ████████████████████████████████│
│                                                            │
│  ZONE-WISE PERFORMANCE                                     │
│  ─────────────────────────────────────────────────────────│
│  Dhaka North        102%  ██████████████████████████████  │
│  Dhaka South         95%  ███████████████████████████░░░  │
│  Chittagong          89%  ██████████████████████████░░░░  │
│  Sylhet              98%  ████████████████████████████░░  │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

#### Individual Performance Report

Sales representatives can view their personal performance:

| Metric | Target | Actual | Achievement |
|--------|--------|--------|-------------|
| Sales Revenue | ৳300,000 | ৳285,000 | 95% |
| Order Count | 60 | 58 | 97% |
| Customer Visits | 80 | 85 | 106% |
| New Customers | 5 | 7 | 140% |
| Collection | ৳200,000 | ৳220,000 | 110% |

**Overall Performance Score: 109.6%** 🌟

---

## 9. CRM Features

### 9.1 Lead Management

#### Lead Pipeline Overview

**Navigation:** CRM → Leads

**Screenshot Description:**
> *Figure 9.1: Lead Management Dashboard*
> - Kanban board view of leads
> - Stage-wise columns
> - Lead cards with key info
> - Drag-and-drop interface

#### Lead Stages

```
┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐
│   NEW    │───▶│ CONTACT  │───▶│ PROPOSAL │───▶│ NEGOTIATE│
│  (12)    │    │  (8)     │    │   (5)    │    │   (3)    │
└──────────┘    └──────────┘    └──────────┘    └────┬─────┘
                                                      │
                              ┌───────────────────────┘
                              ▼
                    ┌──────────────────┐
                    │  WON / LOST (15) │
                    └──────────────────┘
```

| Stage | Description | Average Duration |
|-------|-------------|------------------|
| New | Recently captured lead | 1-2 days |
| Contacted | Initial contact made | 2-3 days |
| Proposal | Quote/Proposal sent | 3-7 days |
| Negotiation | Terms under discussion | 5-14 days |
| Won | Successfully converted | - |
| Lost | Did not convert | - |

#### Adding a New Lead

**Step-by-Step:**

1. Click **"Add New Lead"**
2. Enter lead information:

| Field | Required | Description |
|-------|----------|-------------|
| Business Name | Yes | Company/Shop name |
| Contact Person | Yes | Decision maker name |
| Phone | Yes | Primary contact |
| Email | No | Business email |
| Address | Yes | Full address |
| Business Type | Yes | Category of business |
| Potential Volume | No | Estimated monthly need |
| Source | Yes | How lead was generated |

3. Lead Source Options:
   - Field Visit
   - Referral
   - Phone Inquiry
   - Website
   - Exhibition/Event
   - Social Media
   - Cold Call
   - Existing Customer

4. Add initial notes
5. Assign to sales representative
6. Set follow-up date
7. Save lead

#### Lead Scoring

System automatically scores leads based on:

| Factor | Weight | Points |
|--------|--------|--------|
| Business Type | High | 1-25 |
| Estimated Volume | High | 1-30 |
| Location Proximity | Medium | 1-15 |
| Response Speed | Medium | 1-15 |
| Decision Maker Contact | High | 1-15 |

**Lead Quality Indicators:**
- 🟢 Hot Lead (80-100 points) - Priority follow-up
- 🟡 Warm Lead (50-79 points) - Active nurturing
- 🔵 Cold Lead (25-49 points) - Long-term nurturing
- ⚪ Unqualified (< 25 points) - Archive/Delete

### 9.2 Follow-up Reminders

#### Managing Follow-ups

**Navigation:** CRM → Follow-ups

**Screenshot Description:**
> *Figure 9.2: Follow-up Calendar*
> - Calendar view with scheduled follow-ups
> - List view option
> - Priority indicators
> - Quick action buttons

#### Follow-up Types

| Type | Description | Default Frequency |
|------|-------------|-------------------|
| **Sales Call** | Regular check-in with customer | Weekly/Bi-weekly |
| **Order Follow-up** | Confirm order/delivery status | As needed |
| **Payment Reminder** | Collect outstanding payments | Based on due date |
| **Lead Nurturing** | Progress lead through pipeline | Every 3-5 days |
| **Complaint Resolution** | Follow up on issues | Daily until resolved |
| **Contract Renewal** | Renew annual contracts | 30 days before expiry |

#### Setting Follow-up Reminders

**From Customer Profile:**
1. Open customer/lead profile
2. Click **"Schedule Follow-up"**
3. Enter details:
   - Date and time
   - Type of follow-up
   - Purpose/Agenda
   - Preferred contact method
4. Set reminder (15 min, 1 hour, 1 day before)
5. Save

**Automatic Follow-ups:**
System auto-generates follow-ups for:
- Orders not placed in 14 days
- Overdue payments
- Contract renewals
- New lead no activity in 3 days

#### Daily Follow-up List

```
┌────────────────────────────────────────────────────────────┐
│  TODAY'S FOLLOW-UPS - February 1, 2026                    │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  ⏰ OVERDUE (3)                                           │
│  ─────────────────────────────────────────────────────────│
│  🔴 ABC Store - Payment follow-up (2 days overdue)        │
│     Action: Urgent call for ৳25,000 collection           │
│                                                            │
│  🔴 XYZ Hotel - Contract renewal (1 day overdue)          │
│     Action: Schedule meeting                              │
│                                                            │
│  🕐 SCHEDULED TODAY (8)                                   │
│  ─────────────────────────────────────────────────────────│
│  10:00 AM - Fresh Mart - Order collection                 │
│  11:30 AM - Royal Bakery - New product demo               │
│  02:00 PM - New Lead: Sunrise Cafe - Proposal follow-up   │
│  04:00 PM - Payment collection - 3 customers              │
│                                                            │
│  📅 UPCOMING (5)                                          │
│  ─────────────────────────────────────────────────────────│
│  Tomorrow - 3 follow-ups scheduled                        │
│  This Week - 12 total follow-ups                          │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

### 9.3 Customer Communication Log

#### Communication Tracking

All customer interactions are logged for reference:

**Navigation:** CRM → Communications

**Screenshot Description:**
> *Figure 9.3: Communication History*
> - Chronological list of interactions
> - Filter by type/date/customer
> - Call recording links (if enabled)
> - Notes and outcome fields

#### Communication Types Logged

| Type | Details Captured | Auto-logged |
|------|------------------|-------------|
| Phone Call | Duration, recording, notes | Manual |
| SMS/WhatsApp | Message content, timestamp | Manual |
| Email | Subject, body, attachments | Via integration |
| In-Person Visit | GPS, photo, notes | Via app |
| Video Call | Platform, duration, recording | Manual |

#### Logging a Communication

**Step-by-Step:**

1. Click **"Log Communication"**
2. Select customer/lead
3. Choose communication type
4. Enter details:

```
Communication Log Form:
────────────────────────
Customer: ABC Dairy Corner
Date/Time: Feb 1, 2026, 10:30 AM
Type: Phone Call
Duration: 15 minutes
Direction: Outbound

Summary:
• Discussed monthly volume increase
• Customer interested in new yogurt flavors
• Agreed to sample delivery next week
• Payment of ৳25,000 promised by Friday

Next Action:
□ Schedule sample delivery
□ Follow up on payment
□ Prepare volume discount proposal

Follow-up Date: Feb 8, 2026
Priority: High
```

5. Attach any files (photos, documents)
6. Save log

#### Communication Analytics

Reports available:
- Communication frequency by customer
- Response time analysis
- Preferred communication channels
- Successful outcome tracking

---

## 10. Mobile App - Sales Features

### 10.1 Mobile App Overview

The Smart Dairy Sales App provides full sales functionality on mobile devices for field sales teams.

#### Download and Installation

| Platform | Method | Link |
|----------|--------|------|
| Android | Google Play Store | Search "Smart Dairy Sales" |
| iOS | App Store | Search "Smart Dairy Sales" |
| Direct APK | IT Department | Internal distribution |

**System Requirements:**
- Android 8.0+ / iOS 13+
- 2GB RAM minimum
- 100MB free storage
- GPS enabled
- Camera access

### 10.2 Mobile Interface

**Screenshot Description:**
> *Figure 10.1: Mobile App Home Screen*
> - Bottom navigation bar (5 tabs)
> - Dashboard cards
> - Quick action buttons
> - Notification bell

#### Navigation Structure

```
┌────────────────────────────────────────────────────────────┐
│  ≡  Smart Dairy              🔔                            │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  👋 Good Morning, Mohammad!                                │
│                                                            │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐        │
│  │  📍 Start   │  │  ➕ Quick   │  │  📊 Today's │        │
│  │    Route    │  │    Order    │  │   Summary   │        │
│  └─────────────┘  └─────────────┘  └─────────────┘        │
│                                                            │
│  Today's Progress:                                         │
│  ██████████████████░░░░ 72%                               │
│  13/18 customers visited                                   │
│                                                            │
│  ┌─────────────────────────────────────────────────────┐  │
│  │  🎯 Next Stop: ABC Store (2.5 km)                  │  │
│  │  📍 Tap to Navigate                                 │  │
│  └─────────────────────────────────────────────────────┘  │
│                                                            │
│  Quick Stats:                                              │
│  Orders: 12  │  Collection: ৳85K  │  Distance: 28 km     │
│                                                            │
├────────────────────────────────────────────────────────────┤
│  🏠    👥    ➕    🗺️    ⚙️                                │
│ Home  Cust.  Order Route  More                             │
└────────────────────────────────────────────────────────────┘
```

#### Bottom Navigation Tabs

| Tab | Icon | Features |
|-----|------|----------|
| Home | 🏠 | Dashboard, daily summary, quick actions |
| Customers | 👥 | Customer list, search, quick view |
| Order | ➕ | Quick order creation |
| Route | 🗺️ | Today's route, map view, navigation |
| More | ⚙️ | Reports, payments, settings, help |

### 10.3 Mobile Order Entry

#### Quick Order Creation

**Step-by-Step:**

1. Tap **"Quick Order"** (+ button)
2. Search and select customer
3. Add products:
   - Tap product category
   - Enter quantity
   - View running total
4. Review order summary
5. Select delivery date/time
6. Add notes if needed
7. Submit order

**Offline Order Entry:**
- Works without internet
- Orders saved locally
- Auto-sync when online
- Visual indicator for pending sync

### 10.4 Mobile Payment Collection

#### Recording Payments on Mobile

1. Tap **"Record Payment"** from dashboard
2. Scan QR code or search customer
3. View outstanding invoices
4. Enter payment amount
5. Select payment mode
6. Capture payment photo (if cash)
7. Generate digital receipt
8. Share receipt via WhatsApp/SMS

**Digital Receipt Features:**
- Auto-generated PDF
- QR code for verification
- Share via multiple channels
- Customer signature capture

### 10.5 GPS and Route Navigation

#### Using Map Navigation

1. Tap **"Route"** tab
2. View optimized route on map
3. Tap customer pin for details
4. Tap **"Navigate"** to open Google Maps
5. Check-in upon arrival
6. Automatic visit logging

#### Offline Maps

- Download area maps for offline use
- Cached customer locations
- GPS works without internet
- Route stored locally

---

## 11. Troubleshooting

### 11.1 Common Issues and Solutions

#### Login Issues

| Issue | Possible Cause | Solution |
|-------|---------------|----------|
| "Invalid credentials" | Wrong password | Use "Forgot Password" link |
| "Account locked" | Too many failed attempts | Contact IT/Admin |
| "Session expired" | Inactivity timeout | Login again |
| OTP not received | Network delay | Wait 2 min, request new OTP |
| "Browser not supported" | Outdated browser | Update to latest version |

#### Order Creation Issues

| Issue | Possible Cause | Solution |
|-------|---------------|----------|
| Customer not found | Typo in search | Check customer code |
| "Credit limit exceeded" | Outstanding too high | Collect payment first |
| Product unavailable | Out of stock | Check alternative products |
| Cannot modify order | Status changed | Check order status |
| "Price not found" | Pricing not configured | Contact pricing team |

#### Payment Recording Issues

| Issue | Possible Cause | Solution |
|-------|---------------|----------|
| Duplicate receipt number | System error | Retry with new number |
| Amount mismatch | Calculation error | Reconcile and retry |
| "Customer not found" | Wrong selection | Verify customer code |
| Cannot print receipt | Printer offline | Check connections |

#### Mobile App Issues

| Issue | Possible Cause | Solution |
|-------|---------------|----------|
| App crashes | Memory issue | Close other apps, restart |
| GPS not working | Location disabled | Enable location services |
| Cannot sync | No internet | Check connection, retry |
| Camera not opening | Permission denied | Grant camera permission |
| Slow performance | Cache full | Clear app cache |

### 11.2 Error Messages Reference

| Error Code | Message | Meaning | Action |
|------------|---------|---------|--------|
| ERR-001 | "Session Timeout" | Login expired | Re-login |
| ERR-002 | "Access Denied" | No permission | Contact supervisor |
| ERR-003 | "Record Not Found" | Data missing | Check search criteria |
| ERR-004 | "Validation Failed" | Input error | Check required fields |
| ERR-005 | "Server Error" | Backend issue | Retry after few minutes |
| ERR-006 | "Network Error" | Connection lost | Check internet |
| ERR-007 | "Duplicate Entry" | Already exists | Check existing records |
| ERR-008 | "Credit Check Failed" | Limit exceeded | Collect payment |

### 11.3 System Performance Tips

#### For Faster Performance

1. **Clear Browser Cache:** Weekly cache clearing improves speed
2. **Use Recommended Browsers:** Chrome or Edge for best experience
3. **Close Unused Tabs:** Reduces memory usage
4. **Stable Internet:** Use wired connection when possible
5. **Update Regularly:** Keep browser and OS updated

#### Mobile App Optimization

1. **Keep App Updated:** Latest version has bug fixes
2. **Download Offline Maps:** For areas with poor connectivity
3. **Sync Regularly:** Don't let data pile up
4. **Free Up Storage:** Maintain at least 500MB free space
5. **Restart Weekly:** Clears temporary files

### 11.4 Data Backup and Recovery

#### Automatic Backups

The system automatically:
- Backs up data every 4 hours
- Stores last 30 days of backups
- Maintains redundancy across servers

#### Manual Data Export

To backup your data:
1. Go to Reports → Export Data
2. Select data type (Orders, Customers, etc.)
3. Choose date range
4. Select format (Excel/CSV)
5. Download and save locally

---

## 12. Support Contact

### 12.1 Support Channels

| Channel | Contact | Availability | Response Time |
|---------|---------|--------------|---------------|
| **Hotline** | 16242 ( Toll Free ) | 24/7 | Immediate |
| **WhatsApp** | 01712-345678 | 8 AM - 10 PM | 15 minutes |
| **Email** | support@smartdairy.bd | 24/7 | 4 hours |
| **Live Chat** | Portal chat widget | 9 AM - 6 PM | 5 minutes |
| **Ticket System** | helpdesk.smartdairy.bd | 24/7 | 24 hours |

### 12.2 Escalation Matrix

| Issue Type | First Contact | Escalation | Resolution |
|------------|---------------|------------|------------|
| Password reset | IT Helpdesk | - | Immediate |
| Feature how-to | Training Team | - | Same day |
| Technical bug | IT Support | Development | 24-48 hours |
| Credit approval | Sales Supervisor | Sales Manager | 24 hours |
| Payment dispute | Finance Team | Finance Manager | 48 hours |
| Customer complaint | Customer Service | Sales Manager | 24 hours |

### 12.3 Emergency Contacts

| Role | Name | Contact | When to Contact |
|------|------|---------|-----------------|
| IT Manager | TBD | 017XX-XXXXXX | Critical system down |
| Sales Manager | TBD | 017XX-XXXXXX | Major customer issue |
| Training Lead | TBD | 017XX-XXXXXX | Training emergencies |
| After Hours | Duty Manager | 017XX-XXXXXX | Urgent after 6 PM |

### 12.4 Feedback and Suggestions

We value your feedback! Submit suggestions via:

1. **Feedback Form:** Available in portal footer
2. **Email:** feedback@smartdairy.bd
3. **Monthly Survey:** Sent via email
4. **Suggestion Box:** Physical box at office

### 12.5 Training Resources

Additional learning materials:

| Resource | Location | Access |
|----------|----------|--------|
| Video Tutorials | training.smartdairy.bd | All users |
| FAQ Database | help.smartdairy.bd | Public |
| User Forum | community.smartdairy.bd | Registered users |
| Monthly Webinars | Zoom/Teams link | Invitation only |
| Reference Cards | Download from portal | PDF format |

---

## Appendices

### Appendix A: Keyboard Shortcuts

| Shortcut | Action |
|----------|--------|
| Ctrl + N | New Order |
| Ctrl + F | Search/Find |
| Ctrl + P | Print |
| Ctrl + S | Save |
| Esc | Cancel/Close |
| F1 | Help |
| F5 | Refresh |

### Appendix B: Glossary

| Term | Definition |
|------|------------|
| **B2B** | Business-to-Business |
| **SKU** | Stock Keeping Unit |
| **CRM** | Customer Relationship Management |
| **GPS** | Global Positioning System |
| **OTP** | One-Time Password |
| **UHT** | Ultra-High Temperature processed milk |
| **VAT** | Value Added Tax |
| **BIN** | Business Identification Number |
| **TIN** | Tax Identification Number |
| **MRP** | Maximum Retail Price |

### Appendix C: Document History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | January 31, 2026 | Initial release |

---

**Document End**

---

*This document is the property of Smart Dairy Ltd. Unauthorized distribution is prohibited.*

*For queries or updates, contact the Training Department at training@smartdairy.bd*

**© 2026 Smart Dairy Ltd. All Rights Reserved.**
