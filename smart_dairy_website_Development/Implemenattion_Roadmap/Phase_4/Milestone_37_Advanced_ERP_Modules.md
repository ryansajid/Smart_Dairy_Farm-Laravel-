# Milestone 37: Advanced ERP Modules

## Smart Dairy Smart Portal + ERP System Implementation

**Phase 4: Enterprise Resource Planning (ERP) Core Development**  
**Duration:** Days 361-370  
**Total Estimated Effort:** 400 Developer Hours  
**Status:** Implementation Phase  
**Last Updated:** February 2026

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [ERP Module Architecture](#2-erp-module-architecture)
3. [Daily Task Allocation](#3-daily-task-allocation)
4. [Accounting Module](#4-accounting-module)
5. [Inventory & Warehouse Management](#5-inventory--warehouse-management)
6. [Manufacturing/MRP Module](#6-manufacturingmrp-module)
7. [HR & Payroll Module](#7-hr--payroll-module)
8. [Quality & Traceability](#8-quality--traceability)
9. [Database Schema](#9-database-schema)
10. [Implementation Code](#10-implementation-code)
11. [Appendices](#11-appendices)

---

## 1. Executive Summary

### 1.1 Advanced ERP Objectives

Milestone 37 represents a critical inflection point in the Smart Dairy Smart Portal + ERP System development lifecycle. This milestone focuses on implementing a comprehensive, production-ready ERP system built on Odoo 17 Enterprise Edition, with extensive customizations tailored specifically for the dairy industry in Bangladesh. The objectives are multifaceted, addressing operational efficiency, regulatory compliance, and strategic business intelligence.

**Primary Objectives:**

1. **Accounting with Bangladesh Localization**: Implement a complete double-entry accounting system compliant with Bangladesh Financial Reporting Standards (BFRS), including automated VAT (Value Added Tax) calculations aligned with National Board of Revenue (NBR) requirements, Tax Deducted at Source (TDS) automation, and generation of Mushak forms (VAT returns).

2. **Multi-Warehouse Inventory Management**: Deploy a sophisticated inventory management system capable of handling multiple warehouse locations (farms, processing plants, cold storage facilities, distribution centers), implementing FEFO (First Expired First Out) strategies critical for perishable dairy products, batch/lot tracking for full traceability, and cold chain temperature monitoring integration.

3. **Manufacturing Resource Planning (MRP)**: Develop dairy-specific Bill of Materials (BOM) structures for products including pasteurized milk, yogurt, cheese, butter, ghee, and ice cream. Implement production planning algorithms that optimize resource utilization, work order management, by-product handling (whey, buttermilk), and yield tracking for cost control.

4. **HR and Payroll with Bangladesh Compliance**: Build a comprehensive human resource management system incorporating Bangladesh Labor Law 2006 requirements, automated payroll calculations including provident fund contributions, income tax calculations based on Bangladesh Income Tax Ordinance 1984, and leave management compliant with factory rules.

5. **Procurement and Supplier Management**: Create an integrated procurement module with supplier evaluation frameworks, purchase requisition workflows, vendor-managed inventory capabilities, and automated purchase order generation based on reorder points and MRP calculations.

6. **Quality Management and Traceability**: Implement end-to-end quality control workflows from raw milk intake to finished product dispatch, laboratory integration for microbiological and chemical testing, comprehensive traceability reports enabling rapid product recalls, and compliance with Bangladesh Standard and Testing Institution (BSTI) requirements.

7. **Fixed Asset Management**: Develop a complete fixed asset register with depreciation calculations (straight-line and declining balance methods), asset maintenance scheduling, and capital expenditure tracking.

8. **Advanced Reporting and Analytics**: Build a business intelligence layer with real-time dashboards, financial reports (P&L, Balance Sheet, Cash Flow), operational KPIs, and predictive analytics for demand forecasting.

### 1.2 Module Overview

The ERP architecture follows a modular design pattern where each module operates independently while maintaining seamless integration through a centralized data model.

**Module Interdependencies:**

- **Accounting** serves as the financial backbone, receiving transactions from all operational modules
- **Inventory** provides stock availability data to Manufacturing, Procurement, and Sales modules
- **MRP** consumes inventory data and generates manufacturing orders that affect inventory levels
- **HR/Payroll** provides labor cost data to Manufacturing for accurate product costing
- **Quality Management** gates inventory movements and manufacturing processes
- **Procurement** feeds into Inventory and generates Accounts Payable entries
- **Asset Management** feeds depreciation into Accounting

### 1.3 Success Criteria

The successful completion of Milestone 37 will be measured against the following quantitative and qualitative criteria:

**Functional Success Metrics:**

| Metric | Target | Measurement Method |
|--------|--------|-------------------|
| Module Implementation Coverage | 100% | Checklist verification of all 8 modules |
| Bangladesh VAT Compliance | 100% | NBR Mushak form validation |
| Payroll Calculation Accuracy | 100% | Parallel testing with manual calculations |
| Inventory Valuation Accuracy | 99.9% | Stock reconciliation reports |
| MRP Planning Accuracy | 95%+ | Historical demand comparison |
| Traceability Query Response | <5 seconds | Database query performance testing |
| Financial Report Generation | <30 seconds | Report generation timing |
| User Acceptance Testing Pass Rate | 95%+ | UAT test case execution |

**Technical Success Metrics:**

| Metric | Target | Measurement Method |
|--------|--------|-------------------|
| Code Coverage | 80%+ | Unit test coverage reports |
| API Response Time (p95) | <500ms | Load testing with JMeter |
| Database Query Performance | <100ms | PostgreSQL slow query log |
| System Availability | 99.9% | Uptime monitoring |
| Data Migration Accuracy | 100% | Record count and value reconciliation |
| Security Vulnerabilities | 0 Critical | OWASP ZAP scan results |

**Business Success Metrics:**

| Metric | Target | Measurement Method |
|--------|--------|-------------------|
| Month-end Close Time | <3 days | Time tracking |
| Inventory Accuracy | 99%+ | Physical count variance |
| Procurement Cycle Time | -30% | Time study comparison |
| Payroll Processing Time | -50% | Time tracking comparison |
| Quality Hold Reduction | -25% | Defect rate tracking |

---

## 2. ERP Module Architecture

### 2.1 Module Dependency Diagram

The ERP system architecture follows a layered approach with clear separation of concerns and well-defined interfaces between modules.

**Architecture Layers:**

1. **Presentation Layer**: Odoo Web Client, Mobile Apps, External Portals
2. **Business Logic Layer**: Odoo Models, Controllers, Business Rules
3. **Integration Layer**: API Gateway, Event Bus, Message Queue
4. **Data Persistence Layer**: PostgreSQL, Redis Cache, File Storage

**Core Module Dependencies:**

```
smart_dairy_base
    ├── smart_dairy_accounting_bd
    ├── smart_dairy_inventory
    ├── smart_dairy_mrp
    ├── smart_dairy_hr_payroll_bd
    ├── smart_dairy_quality
    └── smart_dairy_procurement
```

### 2.2 Data Flow Between Modules

The ERP system implements a bidirectional data flow pattern with event-driven updates.

**Key Data Flows:**

1. **Sales-to-Manufacturing Flow**: Sales Orders trigger MRP runs, generate Purchase Orders for shortages, create Manufacturing Orders, and update inventory levels
2. **Payroll-to-Accounting Flow**: Time and Attendance feed into Payroll calculations, create Journal Entries, and update Financial Statements
3. **Quality-to-Inventory Flow**: QC inspections gate inventory movements, trigger holds/releases, and update lot statuses

### 2.3 Integration Patterns

The ERP system implements several integration patterns:

**Event-Driven Architecture (EDA):**
- Uses Odoo's built-in event bus for module-to-module communication
- Asynchronous processing for non-critical updates
- Event persistence for audit trails

**Transactional Consistency:**
- Critical business operations use database transactions
- ACID compliance for financial and inventory operations
- Savepoint management for partial rollbacks

**API Gateway Pattern:**
- Centralized routing for external integrations
- Authentication and rate limiting
- Request/response transformation

### 2.4 Customization Architecture

The Smart Dairy ERP extends Odoo's base functionality through a layered customization approach:

**Inheritance Strategy:**
- Classical inheritance for model extensions
- Prototypal inheritance for new models
- Delegation inheritance for complex relationships

**Module Hierarchy:**
```
smart_dairy_base/              # Base module with common utilities
smart_dairy_accounting_bd/     # Bangladesh Accounting
smart_dairy_inventory/         # Inventory Management
smart_dairy_mrp/               # Manufacturing
smart_dairy_hr_payroll_bd/     # HR & Payroll (Bangladesh)
smart_dairy_quality/           # Quality Management
```

---

## 3. Daily Task Allocation

### 3.1 Development Team Structure

**Team Composition for Milestone 37:**

| Role | Developer | Primary Focus | Secondary Focus |
|------|-----------|---------------|-----------------|
| Lead Developer | Dev 1 | ERP Architecture | Integration Framework |
| Senior Backend | Dev 2 | Core ERP Modules | Quality Management |
| Backend Developer | Dev 3 | HR & Operations | Reporting |
| QA Engineer | QA 1 | Testing & Validation | Documentation |
| Business Analyst | BA 1 | Requirements | UAT Support |

### 3.2 Day 361: Foundation Setup

**Developer 1 (Lead Dev - ERP Architecture):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Module Structure Design | Architecture Document v1.0 | None |
| 10:30-12:00 | Base Module Creation | smart_dairy_base module | Architecture Doc |
| 13:00-15:00 | ORM Extensions | Custom ORM mixins | Base Module |
| 15:00-17:00 | Security Framework | Access control rules | ORM Extensions |
| 17:00-18:00 | Code Review | Review feedback | All above |

**Key Deliverables:**
1. Module manifest files with version constraints
2. SmartDairyAuditMixin for audit trails
3. Security groups hierarchy
4. Base data initialization

**Developer 2 (Backend Dev - Core ERP):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Accounting Module Setup | Module skeleton | Base Module |
| 10:30-12:00 | Chart of Accounts Data | BD COA XML | Module Setup |
| 13:00-15:00 | Tax Configuration | VAT/TDS data | COA Data |
| 15:00-17:00 | Journal Configuration | Journal types | Tax Config |
| 17:00-18:00 | Unit Testing | Test cases | All above |

**Key Deliverables:**
1. Bangladesh Chart of Accounts
2. VAT tax templates (5%, 7.5%, 10%, 15%)
3. TDS deduction rules
4. Journal configuration

**Developer 3 (Backend Dev - HR & Operations):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | HR Module Setup | Module skeleton | Base Module |
| 10:30-12:00 | Employee Model | Employee structure | Module Setup |
| 13:00-15:00 | Contract Model | Contract framework | Employee Model |
| 15:00-17:00 | Leave Types BD | Bangladesh leave | Contract |
| 17:00-18:00 | Unit Testing | Test cases | All above |

**Key Deliverables:**
1. HR module structure
2. Employee model with Bangladesh-specific fields
3. Contract framework
4. Bangladesh leave types

### 3.3 Day 362: VAT & TDS Implementation

**Developer 1 (Lead Dev - ERP Architecture):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Mushak Report Framework | Report base class | Accounting Module |
| 10:30-12:00 | Mushak 9.1 Implementation | Purchase report | Framework |
| 13:00-15:00 | Mushak 9.2 Implementation | Sales report | Framework |
| 15:00-17:00 | Mushak 6.3 Implementation | I/O report | 9.1, 9.2 |
| 17:00-18:00 | Integration Testing | Test results | All above |

**Developer 2 (Backend Dev - Core ERP):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | TDS Configuration | TDS rules | Tax Config |
| 10:30-12:00 | TDS Automation | Auto-deduct | TDS Config |
| 13:00-15:00 | Bank Integration | Bank models | TDS Auto |
| 15:00-17:00 | Reconciliation | Recon engine | Bank Models |
| 17:00-18:00 | Testing | Test results | All above |

**Developer 3 (Backend Dev - HR & Operations):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Attendance Model | Attendance tracking | Employee |
| 10:30-12:00 | Shift Management | Shift config | Attendance |
| 13:00-15:00 | Leave Accrual | Accrual engine | Shift |
| 15:00-17:00 | Holiday Calendar | BD Holidays | Leave |
| 17:00-18:00 | Integration | HR module integration | All above |

### 3.4 Day 363: Payroll Core Implementation

**Developer 1 (Lead Dev - ERP Architecture):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Payroll Engine Design | Engine architecture | HR Models |
| 10:30-12:00 | Salary Structure Framework | Structure models | Engine |
| 13:00-15:00 | Salary Rules BD | Bangladesh rules | Structure |
| 15:00-17:00 | Contribution Registers | PF/Gratuity | Rules |
| 17:00-18:00 | Review & Refinement | Updated code | All above |

**Developer 2 (Backend Dev - Core ERP):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Payslip Calculation | Payslip engine | Payroll Frame |
| 10:30-12:00 | Tax Calculation | Tax engine | Payslip |
| 13:00-15:00 | PF Calculation | PF engine | Tax |
| 15:00-17:00 | Loan Management | Loan module | PF |
| 17:00-18:00 | Testing | Unit tests | All above |

**Developer 3 (Backend Dev - HR & Operations):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Payslip PDF Generation | Report template | Payslip |
| 10:30-12:00 | Bank Advice Generation | Bank file export | PDF |
| 13:00-15:00 | Employee Self-Service Portal | ESS views | Bank |
| 15:00-17:00 | Payslip Email Notification | Email template | ESS |
| 17:00-18:00 | Testing | Integration tests | All above |

### 3.5 Day 364: Inventory Management Core

**Developer 1 (Lead Dev - ERP Architecture):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Multi-Warehouse Architecture | Warehouse design | Base Module |
| 10:30-12:00 | Location Hierarchy | Location models | Architecture |
| 13:00-15:00 | Inventory Valuation Framework | Valuation engine | Hierarchy |
| 15:00-17:00 | FEFO/FIFO Strategy | Strategy models | Valuation |
| 17:00-18:00 | Code Review | Review notes | All above |

**Developer 2 (Backend Dev - Core ERP):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Stock Quant Management | Quant models | Location |
| 10:30-12:00 | Stock Move Processing | Move workflow | Quant |
| 13:00-15:00 | FEFO Implementation | FEFO logic | Move |
| 15:00-17:00 | Batch/Lot Tracking | Lot management | FEFO |
| 17:00-18:00 | Testing | Unit tests | All above |

**Developer 3 (Backend Dev - HR & Operations):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Reorder Rules | Reorder model | Inventory |
| 10:30-12:00 | Procurement Requests | PR workflow | Reorder |
| 13:00-15:00 | Vendor Management | Vendor models | PR |
| 15:00-17:00 | Purchase Agreements | Contract framework | Vendor |
| 17:00-18:00 | Integration Testing | Test results | All above |

### 3.6 Day 365: Manufacturing/MRP Core

**Developer 1 (Lead Dev - ERP Architecture):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | MRP Architecture | MRP design | Inventory |
| 10:30-12:00 | BOM Structure Design | BOM models | Architecture |
| 13:00-15:00 | Routing Configuration | Routing models | BOM |
| 15:00-17:00 | Work Center Setup | Work center | Routing |
| 17:00-18:00 | Code Review | Review notes | All above |

**Developer 2 (Backend Dev - Core ERP):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Dairy-Specific BOMs | BOM data | BOM Design |
| 10:30-12:00 | By-Product Handling | By-product | BOMs |
| 13:00-15:00 | Production Planning | Planning engine | By-product |
| 15:00-17:00 | Work Order Management | WO workflow | Planning |
| 17:00-18:00 | Testing | Unit tests | All above |

**Developer 3 (Backend Dev - HR & Operations):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | MRP Scheduler | Scheduler config | Planning |
| 10:30-12:00 | Capacity Planning | Capacity models | Scheduler |
| 13:00-15:00 | Production Reporting | Reports | Capacity |
| 15:00-17:00 | Cost Analysis | Costing | Reports |
| 17:00-18:00 | Integration | Full integration | All above |

### 3.7 Day 366: Quality Management

**Developer 1 (Lead Dev - ERP Architecture):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | QC Framework Design | QC architecture | MRP |
| 10:30-12:00 | QC Plan Model | Plan structure | Framework |
| 13:00-15:00 | Inspection Model | Inspection | Plan |
| 15:00-17:00 | Non-Conformance | NCR model | Inspection |
| 17:00-18:00 | CAPA Integration | CAPA workflow | NCR |

**Developer 2 (Backend Dev - Core ERP):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Lab Integration Model | Lab models | QC Framework |
| 10:30-12:00 | Lab Equipment Interface | Equipment | Lab Model |
| 13:00-15:00 | Automated Testing | Auto-tests | Equipment |
| 15:00-17:00 | Certificate of Analysis | CoA generation | Auto-tests |
| 17:00-18:00 | Testing | Unit tests | All above |

**Developer 3 (Backend Dev - HR & Operations):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Traceability Reports | Trace queries | Lab |
| 10:30-12:00 | Recall Management | Recall workflow | Trace |
| 13:00-15:00 | Recall Notifications | Notification | Recall |
| 15:00-17:00 | Mock Recall Testing | Test results | Notifications |
| 17:00-18:00 | Documentation | Test docs | All above |

### 3.8 Day 367: Procurement & Assets

**Developer 1 (Lead Dev - ERP Architecture):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Procurement Framework | Procurement arch | Inventory |
| 10:30-12:00 | Requisition Workflow | PR workflow | Framework |
| 13:00-15:00 | RFQ Management | RFQ module | PR |
| 15:00-17:00 | Vendor Evaluation | Vendor scoring | RFQ |
| 17:00-18:00 | Code Review | Review notes | All above |

**Developer 2 (Backend Dev - Core ERP):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Asset Category Model | Asset categories | Framework |
| 10:30-12:00 | Asset Registration | Asset model | Categories |
| 13:00-15:00 | Depreciation Engine | Depreciation | Asset |
| 15:00-17:00 | Asset Maintenance | Maintenance | Depreciation |
| 17:00-18:00 | Testing | Unit tests | All above |

**Developer 3 (Backend Dev - HR & Operations):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Purchase Orders | PO module | Vendor |
| 10:30-12:00 | Goods Receipt | GRN module | PO |
| 13:00-15:00 | Supplier Invoices | Invoice matching | GRN |
| 15:00-17:00 | Payment Processing | Payment workflow | Invoice |
| 17:00-18:00 | Integration | Integration tests | All above |

### 3.9 Day 368: Reporting & Analytics

**Developer 1 (Lead Dev - ERP Architecture):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Reporting Framework | Report engine | All Modules |
| 10:30-12:00 | Financial Reports | Financial | Framework |
| 13:00-15:00 | Operational Reports | Operational | Financial |
| 15:00-17:00 | Dashboard Design | Dashboard | Operational |
| 17:00-18:00 | Code Review | Review notes | All above |

**Developer 2 (Backend Dev - Core ERP):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Stock Valuation Report | Valuation | Inventory |
| 10:30-12:00 | Production Report | Production | Valuation |
| 13:00-15:00 | Cost Analysis Report | Costing | Production |
| 15:00-17:00 | Inventory Aging | Aging report | Cost |
| 17:00-18:00 | Testing | Unit tests | All above |

**Developer 3 (Backend Dev - HR & Operations):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Payroll Reports | Payroll | HR Module |
| 10:30-12:00 | Attendance Reports | Attendance | Payroll |
| 13:00-15:00 | HR Analytics | HR metrics | Attendance |
| 15:00-17:00 | Compliance Reports | Compliance | Analytics |
| 17:00-18:00 | Integration | Integration tests | All above |

### 3.10 Day 369: Integration & Testing

**Developer 1 (Lead Dev - ERP Architecture):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Module Integration | Integration | All Modules |
| 10:30-12:00 | Workflow Integration | Workflows | Integration |
| 13:00-15:00 | Data Migration | Migration scripts | Workflows |
| 15:00-17:00 | Performance Optimization | Performance | Migration |
| 17:00-18:00 | Review | Review notes | All above |

**Developer 2 (Backend Dev - Core ERP):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Unit Testing | Test suite | All Modules |
| 10:30-12:00 | Integration Testing | Integration tests | Unit Tests |
| 13:00-15:00 | VAT Compliance Testing | VAT tests | Integration |
| 15:00-17:00 | Payroll Testing | Payroll tests | VAT |
| 17:00-18:00 | Bug Fixes | Fixed code | Tests |

**Developer 3 (Backend Dev - HR & Operations):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Inventory Testing | Inventory tests | All Modules |
| 10:30-12:00 | MRP Testing | MRP tests | Inventory |
| 13:00-15:00 | Quality Testing | QC tests | MRP |
| 15:00-17:00 | End-to-End Testing | E2E tests | QC |
| 17:00-18:00 | Bug Fixes | Fixed code | Tests |

### 3.11 Day 370: Finalization & Documentation

**Developer 1 (Lead Dev - ERP Architecture):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Final Code Review | Review report | All |
| 10:30-12:00 | Documentation | Tech docs | Review |
| 13:00-15:00 | Deployment Scripts | Scripts | Docs |
| 15:00-17:00 | Demo Preparation | Demo | Scripts |
| 17:00-18:00 | Handover | Handover docs | All |

**Developer 2 (Backend Dev - Core ERP):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | User Documentation | User guide | All |
| 10:30-12:00 | Admin Documentation | Admin guide | User |
| 13:00-15:00 | Training Materials | Training | Admin |
| 15:00-17:00 | Video Tutorials | Videos | Training |
| 17:00-18:00 | Handover | Handover docs | All |

**Developer 3 (Backend Dev - HR & Operations):**

| Time | Task | Deliverable | Dependencies |
|------|------|-------------|--------------|
| 09:00-10:30 | Final Testing | Test report | All |
| 10:30-12:00 | UAT Support | UAT feedback | Testing |
| 13:00-15:00 | Issue Resolution | Fixes | UAT |
| 15:00-17:00 | Sign-off Documentation | Sign-off | Fixes |
| 17:00-18:00 | Milestone Closure | Closure | All |

---

## 4. Accounting Module

### 4.1 Bangladesh Chart of Accounts

The Bangladesh Chart of Accounts for Smart Dairy follows the Bangladesh Financial Reporting Standards (BFRS) and includes dairy-specific accounts for comprehensive financial tracking.

#### 4.1.1 Account Numbering Convention

```
Level 1 (2 digits): Major Category
Level 2 (2 digits): Sub-category
Level 3 (2 digits): Account Detail

Example: 13-01-01 = Raw Milk Inventory
```

#### 4.1.2 Major Account Categories

**Assets (11-13):**
- 11: Current Assets
- 12: Fixed Assets
- 13: Other Assets

**Liabilities (21-22):**
- 21: Current Liabilities
- 22: Long-term Liabilities

**Equity (31):**
- 31: Shareholders' Equity

**Revenue (41-43):**
- 41: Operating Revenue
- 42: Other Income
- 43: Financial Income

**Expenses (51-61):**
- 51: Cost of Goods Sold
- 61: Operating Expenses

### 4.2 VAT Configuration (Mushak Forms)

The Value Added Tax system in Bangladesh requires specific reporting through Mushak forms. Smart Dairy implements automated generation of these forms.

#### 4.2.1 VAT Tax Rates

| Category | Rate | Applicability |
|----------|------|---------------|
| Standard Rate | 15% | Most goods and services |
| Reduced Rate | 5% | Essential goods including milk |
| Truncated Rate | 2%, 3%, 4%, 5% | Specific industries |
| Zero Rate | 0% | Exports, essential medicines |
| Exempt | - | Basic food items, education |

#### 4.2.2 Mushak Form Types

**Mushak 9.1 (Purchase Register):**
- Records all purchases with VAT
- Captures supplier BIN/NID
- Categorizes by supply type
- Calculates input VAT

**Mushak 9.2 (Sales Register):**
- Records all sales with VAT
- Captures customer BIN
- Categorizes by supply type
- Calculates output VAT

**Mushak 6.3 (Input-Output Coefficient):**
- Tracks raw material inputs
- Tracks finished goods outputs
- Used for manufacturing entities

**Mushak 6.1 (Monthly Return):**
- Consolidated monthly VAT return
- Summary of input and output VAT
- Net VAT payable calculation

### 4.3 TDS Automation

Tax Deducted at Source (TDS) in Bangladesh follows the Income Tax Ordinance 1984 with specific rates for different types of payments.

#### 4.3.1 TDS Section Codes

| Section | Description | Threshold (BDT) | Rate |
|---------|-------------|-----------------|------|
| 52 | Salary | Annual taxable | Slab rates |
| 52A | Contractor payment | 50,000 | 7% (company), 5.5% (individual) |
| 52AA | Import | All | 3% |
| 53 | Interest on securities | 5,000 | 10% |
| 53A | Insurance commission | 15,000 | 5% |
| 53C | House rent | 25,000/month | 5% |
| 53F | Professional service | 25,000 | 10% |
| 53M | Technical consultancy | 25,000 | 12% |
| 56 | Cash withdrawal | 1,00,000/day | 0.5% |

#### 4.3.2 TDS Certificate Generation

The system automatically generates TDS certificates for deductees:
- Form 16A for non-salary payments
- Form 16 for salary payments
- Quarterly summary reports
- Online submission to NBR

### 4.4 Bank Reconciliation

The bank reconciliation module automates the matching of bank statements with accounting entries.

#### 4.4.1 Reconciliation Features

1. **Auto-Matching**: Automatic matching based on reference numbers, amounts, and dates
2. **Rule-Based Matching**: Custom rules for recurring transactions
3. **Manual Matching**: Interface for manual reconciliation
4. **Exception Handling**: Flagging of unmatched items

#### 4.4.2 Reconciliation Statement

The reconciliation statement shows:
- Balance as per bank statement
- Add: Deposits in transit
- Less: Outstanding checks
- Balance as per books
- Adjustments for bank charges, interest

### 4.5 Financial Reporting

#### 4.5.1 Profit and Loss Statement

```
SMART DAIRY LIMITED
PROFIT AND LOSS STATEMENT
FOR THE YEAR ENDED [DATE]

REVENUE:
  Sales of Milk and Dairy Products          XXXX
  Less: Sales Returns                          (XXX)
  Less: Trade Discounts                        (XXX)
  NET SALES                                    XXXX

COST OF GOODS SOLD:
  Opening Inventory                            XXX
  Add: Purchases                              XXXX
  Add: Direct Expenses                         XXX
  Less: Closing Inventory                      (XXX)
  COST OF GOODS SOLD                          (XXXX)

GROSS PROFIT                                   XXXX

OPERATING EXPENSES:
  Administrative Expenses                      (XXX)
  Selling and Distribution Expenses            (XXX)
  Finance Costs                                (XXX)
  TOTAL OPERATING EXPENSES                     (XXXX)

NET PROFIT/(LOSS) BEFORE TAX                   XXXX

INCOME TAX EXPENSE                             (XXX)

NET PROFIT/(LOSS) AFTER TAX                    XXXX
```

#### 4.5.2 Balance Sheet

```
SMART DAIRY LIMITED
BALANCE SHEET
AS AT [DATE]

ASSETS:
NON-CURRENT ASSETS:
  Property, Plant and Equipment               XXXX
  Intangible Assets                            XXX
  Long-term Investments                        XXX
  TOTAL NON-CURRENT ASSETS                    XXXX

CURRENT ASSETS:
  Inventories                                 XXXX
  Trade Receivables                           XXXX
  Cash and Bank Balances                      XXXX
  Prepayments and Advances                     XXX
  TOTAL CURRENT ASSETS                        XXXX

TOTAL ASSETS                                  XXXXX

EQUITY AND LIABILITIES:
SHAREHOLDERS' EQUITY:
  Issued Capital                              XXXX
  Retained Earnings                           XXXX
  TOTAL EQUITY                                XXXX

NON-CURRENT LIABILITIES:
  Long-term Borrowings                        XXXX
  Deferred Tax Liability                       XXX
  TOTAL NON-CURRENT LIABILITIES               XXXX

CURRENT LIABILITIES:
  Trade Payables                              XXXX
  Short-term Borrowings                        XXX
  Current Tax Payable                          XXX
  Accrued Expenses                             XXX
  TOTAL CURRENT LIABILITIES                   XXXX

TOTAL EQUITY AND LIABILITIES                  XXXXX
```

---

## 5. Inventory & Warehouse Management

### 5.1 Multi-Location Inventory

Smart Dairy operates multiple warehouse locations to support its integrated supply chain:

**Warehouse Types:**
1. **Dairy Farms**: Raw milk collection points
2. **Milk Collection Centers**: Aggregation and initial cooling
3. **Processing Plants**: Manufacturing facilities
4. **Cold Storage Facilities**: Long-term storage
5. **Distribution Centers**: Regional distribution hubs
6. **Retail Outlets**: Company-owned retail stores

#### 5.1.1 Warehouse Configuration

Each warehouse is configured with:
- Physical address and GPS coordinates
- Operating hours and receiving/dispatch schedules
- Storage capacity (weight and volume)
- Temperature control capabilities
- Quality control requirements

#### 5.1.2 Inter-Warehouse Transfers

The system supports:
- Automated transfer orders based on stock levels
- Route optimization for transfers
- Cold chain monitoring during transit
- Transfer cost allocation

### 5.2 FEFO/FIFO Strategies

For dairy products, FEFO (First Expired First Out) is the primary removal strategy due to the perishable nature of products.

#### 5.2.1 FEFO Implementation

```python
# Removal priority:
# 1. Expiration date (earliest first)
# 2. Removal date (earliest first)
# 3. In date (earliest first)

REMOVAL_ORDER = 'expiration_date ASC, removal_date ASC, in_date ASC'
```

#### 5.2.2 Expiration Date Management

Each product category has defined shelf life:

| Product | Shelf Life | Removal Days Before Expiry |
|---------|-----------|---------------------------|
| Pasteurized Milk | 2-5 days | 1 day |
| UHT Milk | 6 months | 7 days |
| Yogurt | 15-30 days | 3 days |
| Cheese | 3-12 months | 15 days |
| Butter | 6 months | 15 days |
| Ice Cream | 12 months | 30 days |

### 5.3 Batch/Lot Tracking

Every production batch is assigned a unique lot number with complete traceability.

#### 5.3.1 Lot Number Format

```
Format: [PRODUCT-CODE]-[YYMMDD]-[SEQUENCE]
Example: PM-240201-001
         PM = Pasteurized Milk
         240201 = February 1, 2024
         001 = Sequence number
```

#### 5.3.2 Lot Attributes

Each lot tracks:
- Production date and time
- Raw material source farms
- Milk fat and SNF percentages
- QC test results
- Temperature history
- Expiration date
- Storage locations

### 5.4 Cold Chain Management

Temperature monitoring is critical for dairy product quality and safety.

#### 5.4.1 Temperature Zones

| Zone | Temperature Range | Products |
|------|------------------|----------|
| Raw Milk Reception | 0-4 degrees C | Raw milk |
| Cold Storage | 2-4 degrees C | Pasteurized milk, yogurt |
| Deep Freezer | -18 to -25 degrees C | Ice cream |
| Processing | 4-8 degrees C | Work in progress |
| Blast Freezing | -35 to -40 degrees C | Quick freezing |

#### 5.4.2 Temperature Monitoring

- IoT sensors in each temperature-controlled location
- Real-time alerts for temperature breaches
- Automated hold on affected inventory
- Temperature logs for regulatory compliance

### 5.5 Stock Valuation

The system supports multiple inventory valuation methods:

#### 5.5.1 Valuation Methods

1. **FIFO (First In First Out)**: Oldest inventory cost assigned first
2. **FEFO (First Expired First Out)**: Based on expiration date
3. **Average Cost**: Weighted average of all inventory
4. **Standard Cost**: Predetermined standard costs with variance tracking

#### 5.5.2 Perpetual Inventory

The system maintains perpetual inventory records:
- Real-time stock levels
- Automatic valuation on every movement
- Integration with general ledger
- Inventory adjustment workflows

---

## 6. Manufacturing/MRP Module

### 6.1 Dairy-Specific BOMs

Bill of Materials for dairy products include precise ingredient ratios and quality specifications.

#### 6.1.1 Pasteurized Milk BOM

```
PRODUCT: Pasteurized Milk (1 Liter Pouch)

RAW MATERIALS:
- Raw Milk (Standardized): 1.05 liters
- Packaging Pouch: 1 unit

BY-PRODUCTS:
- Cream (excess fat): 0.02 kg

QUALITY SPECIFICATIONS:
- Fat: 3.5% +/- 0.2%
- SNF: 8.5% +/- 0.2%
- Acidity: 0.15% max
- Plate Count: <50,000 CFU/ml
```

#### 6.1.2 Yogurt BOM

```
PRODUCT: Set Yogurt (500g Cup)

RAW MATERIALS:
- Standardized Milk: 0.52 liters
- Yogurt Culture: 0.5% of milk
- Sugar (optional): 40g
- Flavor (optional): 2g
- Cup and Lid: 1 unit

BY-PRODUCTS:
- Whey (drained): 0.05 liters
```

#### 6.1.3 Cheese BOM

```
PRODUCT: Mozzarella Cheese (1 kg)

RAW MATERIALS:
- Whole Milk: 10 liters
- Rennet: 0.2g
- Calcium Chloride: 2g
- Cheese Culture: 1g
- Salt: 20g
- Packaging: 1 unit

BY-PRODUCTS:
- Whey: 9 liters (90% of milk)
```

### 6.2 Production Planning

The MRP engine plans production based on:
- Sales forecasts
- Current inventory levels
- Lead times
- Production capacity
- Minimum order quantities

#### 6.2.1 Planning Horizon

- **Long-term**: 3-6 months (capacity planning)
- **Medium-term**: 4-12 weeks (master production schedule)
- **Short-term**: 1-4 weeks (detailed scheduling)
- **Daily**: Shop floor execution

#### 6.2.2 Production Scheduling

The scheduler considers:
- Product shelf life constraints
- Equipment changeover times
- Cleaning requirements (CIP)
- Shift availability
- Quality hold times

### 6.3 Work Order Management

Work orders track production execution from start to finish.

#### 6.3.1 Work Order Lifecycle

```
DRAFT -> CONFIRMED -> READY -> IN_PROGRESS -> QC_HOLD -> QC_PASS -> DONE -> CLOSED
          |             |          |             |           |
          v             v          v             v           v
       CANCELLED    WAITING   PAUSED      REJECTED    REWORK
```

#### 6.3.2 Time Tracking

- Setup time: Equipment preparation
- Run time: Actual production
- Cleaning time: CIP operations
- Down time: Unplanned stops
- Yield tracking: Actual vs expected output

### 6.4 By-Product Handling

Dairy processing generates significant by-products that require proper accounting.

#### 6.4.1 Common By-Products

| Process | By-Product | Typical Yield | Usage |
|---------|-----------|---------------|-------|
| Standardization | Cream | 5-10% of milk | Butter, ghee |
| Cheese making | Whey | 85-90% of milk | Whey protein, animal feed |
| Butter making | Buttermilk | 50-60% of cream | Cultured products |
| Yogurt draining | Whey | 10-20% | Protein recovery |

#### 6.4.2 By-Product Cost Allocation

Costs can be allocated to by-products using:
- Net realizable value method
- Physical measurement method
- No allocation (treated as scrap)

### 6.5 Yield Tracking

Yield tracking measures production efficiency and identifies losses.

#### 6.5.1 Yield Calculation

```
Theoretical Yield = Input Quantity * Expected Yield %
Actual Yield = Output Quantity
Yield Variance = Actual Yield - Theoretical Yield
Yield Variance % = (Yield Variance / Theoretical Yield) * 100
```

#### 6.5.2 Yield Analysis

- Daily yield reports by product
- Trend analysis for efficiency improvements
- Loss categorization (evaporation, spillage, QC rejection)
- Benchmarking against industry standards

---

## 7. HR & Payroll Module

### 7.1 Employee Management

The employee management system captures all relevant information for Bangladesh labor compliance.

#### 7.1.1 Employee Categories

| Category | Definition | Benefits |
|----------|-----------|----------|
| Permanent | Regular employment | Full benefits |
| Contractual | Fixed-term contract | Pro-rated benefits |
| Temporary | Seasonal/Project | Basic benefits |
| Probationary | Trial period (3-6 months) | Limited benefits |
| Part-time | <40 hours/week | Pro-rated benefits |

#### 7.1.2 Employee Data

- Personal information (name, NID, photo)
- Contact details
- Emergency contacts
- Education and qualifications
- Employment history
- Bank account details (for salary)
- PF and tax information

### 7.2 Bangladesh Payroll Rules

The payroll system implements all statutory requirements under Bangladesh labor laws.

#### 7.2.1 Basic Pay Structure

```
Gross Salary = Basic + House Rent + Medical + Conveyance

Typical Allocation:
- Basic: 50-60% of gross
- House Rent: 50% of basic (max)
- Medical: 10% of basic or fixed amount
- Conveyance: Fixed amount (min 2,500 BDT)
```

#### 7.2.2 Overtime Calculation

Per Bangladesh Labor Law 2006:
- Normal working hours: 8 hours/day, 48 hours/week
- Overtime rate: 2x normal hourly rate
- Maximum overtime: 16 hours/week

```python
hourly_rate = basic_salary / (working_days * 8)
overtime_rate = hourly_rate * 2
overtime_amount = overtime_hours * overtime_rate
```

#### 7.2.3 Festival Bonus

Two festival bonuses per year (typically Eid-ul-Fitr and Eid-ul-Adha):
- Eligible employees: Permanent and contractual
- Amount: 1 basic wage per bonus
- Pro-rated for employees with <1 year service

### 7.3 Provident Fund

The Provident Fund is a retirement benefit scheme.

#### 7.3.1 PF Contribution

| Contributor | Rate | Calculation Base |
|-------------|------|-----------------|
| Employee | 10% | Basic salary |
| Employer | 10% | Basic salary |
| Total | 20% | Basic salary |

#### 7.3.2 PF Rules

- Eligibility: Permanent employees after probation
- Vesting: Immediate for employee contribution
- Withdrawal: Retirement, resignation, or emergency
- Interest: Credited annually based on fund performance

### 7.4 Tax Calculation

Income tax calculation follows the Bangladesh Income Tax Ordinance 1984.

#### 7.4.1 Tax Slabs (FY 2024-25)

| Taxable Income (BDT) | Rate | Calculation |
|---------------------|------|-------------|
| First 3,50,000 | 0% | Tax-free threshold |
| Next 1,00,000 | 5% | First slab |
| Next 3,00,000 | 10% | Second slab |
| Next 4,00,000 | 15% | Third slab |
| Next 5,00,000 | 20% | Fourth slab |
| Above 13,50,000 | 25% | Highest slab |

#### 7.4.2 Tax Rebates

- Investment rebate: Up to 20% of eligible investment (max 1,00,000)
- Disabled person: Additional 50,000 allowance
- Freedom fighter: Higher threshold
- Senior citizen (65+): Higher threshold

#### 7.4.3 Monthly Tax Calculation

```python
def calculate_monthly_tax(annual_taxable_income):
    annual_tax = calculate_annual_tax(annual_taxable_income)
    monthly_tax = annual_tax / 12
    return monthly_tax
```

### 7.5 Leave Management

The leave system complies with Bangladesh Labor Law 2006 and Factory Rules.

#### 7.5.1 Leave Types

| Leave Type | Entitlement | Carry Forward |
|-----------|-------------|---------------|
| Casual Leave | 10 days/year | No |
| Sick Leave | 14 days/year | No |
| Annual Leave | 1 day per 18 working days | Yes (max 2 years) |
| Festival Leave | 11 days/year (public holidays) | - |
| Maternity Leave | 16 weeks (8 before, 8 after) | - |
| Paternity Leave | 5 days | - |
| Earned Leave | As per service | Yes |

#### 7.5.2 Leave Accrual

- Annual leave accrues based on working days
- Accrual rate: 1 day per 18 days worked
- Maximum accumulation: 2 years entitlement
- Encashment on resignation/retirement

---

## 8. Quality & Traceability

### 8.1 QC Workflows

Quality control workflows ensure product safety and compliance.

#### 8.1.1 Inspection Points

1. **Raw Milk Reception**:
   - Temperature check
   - Organoleptic test (smell, appearance)
   - FAT and SNF testing
   - Acidity test
   - Adulteration test
   - Antibiotic residue test

2. **In-Process QC**:
   - Pasteurization temperature verification
   - Homogenization efficiency
   - pH monitoring
   - Microbiological checks

3. **Finished Product QC**:
   - Package integrity
   - Coding verification
   - Weight check
   - Sensory evaluation
   - Microbiological testing

4. **Pre-shipment QC**:
   - Batch verification
   - Documentation check
   - Temperature verification

#### 8.1.2 Quality Gates

- **Hold**: Product quarantined pending QC
- **Approved**: Product cleared for release
- **Rejected**: Product rejected for disposal
- **Regrade**: Product downgraded to lower grade

### 8.2 Lab Integration

Laboratory testing ensures product safety and compliance with BSTI standards.

#### 8.2.1 Microbiological Tests

| Test | Standard | Frequency |
|------|----------|-----------|
| Total Plate Count | <50,000 CFU/ml | Daily batch |
| Coliform Count | <10 CFU/ml | Daily batch |
| E. coli | Absent/10ml | Weekly |
| Salmonella | Absent/25ml | Weekly |
| S. aureus | <10 CFU/ml | Weekly |
| Yeast and Mold | <100 CFU/ml | Weekly |

#### 8.2.2 Chemical Tests

| Test | Standard | Frequency |
|------|----------|-----------|
| Fat content | As per label +/- 0.2% | Every batch |
| SNF content | As per label +/- 0.2% | Every batch |
| Acidity | <0.15% | Every batch |
| pH | 6.5-6.8 | Continuous |
| Antibiotic residue | Negative | Every collection |
| Adulterants | Negative | Random |

### 8.3 Traceability Reports

Traceability enables rapid response to quality issues.

#### 8.3.1 Forward Traceability

From raw material to finished product:
- Raw milk source farms
- Collection date and time
- Processing batch
- Finished product lots
- Customer deliveries

#### 8.3.2 Backward Traceability

From finished product to raw materials:
- Finished product lot
- Production batch
- Raw material lots used
- Supplier information
- Farm origins

#### 8.3.3 Traceability Report Format

```
TRACEABILITY REPORT
Lot Number: [LOT NUMBER]
Product: [PRODUCT NAME]
Production Date: [DATE]

BACKWARD TRACEABILITY:
Raw Materials:
- Lot [X]: [Product], Supplier [Y], Quantity [Z]
- Lot [A]: [Product], Supplier [B], Quantity [C]

Source Farms:
- Farm [1]: [Name], Collection [Date], Quantity [Q]
- Farm [2]: [Name], Collection [Date], Quantity [Q]

FORWARD TRACEABILITY:
Deliveries:
- Invoice [I1]: Customer [C1], Date [D1], Quantity [Q1]
- Invoice [I2]: Customer [C2], Date [D2], Quantity [Q2]
```

### 8.4 Recall Management

The recall management system enables rapid product recall when necessary.

#### 8.4.1 Recall Types

1. **Class I**: Dangerous or defective product that could cause serious health problems or death
2. **Class II**: Product that might cause temporary or reversible health problem
3. **Class III**: Product unlikely to cause adverse health reaction

#### 8.4.2 Recall Process

1. **Detection**: Issue identified through QC or customer complaint
2. **Assessment**: Risk assessment and recall classification
3. **Decision**: Recall decision by quality team
4. **Notification**: Notify distributors, retailers, customers
5. **Trace**: Identify all affected lots and distribution
6. **Retrieve**: Collect affected products
7. **Disposition**: Destroy or reprocess as appropriate
8. **Report**: Submit recall report to authorities
9. **Review**: Root cause analysis and corrective action

#### 8.4.3 Mock Recall

Quarterly mock recalls test the system:
- Random lot selection
- 4-hour traceability challenge
- Communication test
- Documentation review
- Gap analysis

---

## 9. Database Schema

### 9.1 ERP Table Structures

#### 9.1.1 Accounting Tables

**account_move (Journal Entries):**
```sql
CREATE TABLE account_move (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    state VARCHAR(20) DEFAULT 'draft',
    move_type VARCHAR(50),
    journal_id INTEGER REFERENCES account_journal(id),
    company_id INTEGER REFERENCES res_company(id),
    amount_total NUMERIC(16,2),
    ref VARCHAR(255),
    narration TEXT,
    bd_vat_amount NUMERIC(16,2),
    bd_tds_amount NUMERIC(16,2),
    created_at TIMESTAMP DEFAULT NOW(),
    created_by INTEGER REFERENCES res_users(id)
);
```

**account_move_line (Journal Entry Lines):**
```sql
CREATE TABLE account_move_line (
    id SERIAL PRIMARY KEY,
    move_id INTEGER REFERENCES account_move(id),
    account_id INTEGER REFERENCES account_account(id),
    name TEXT,
    debit NUMERIC(16,2) DEFAULT 0,
    credit NUMERIC(16,2) DEFAULT 0,
    balance NUMERIC(16,2) DEFAULT 0,
    amount_currency NUMERIC(16,2),
    currency_id INTEGER REFERENCES res_currency(id),
    partner_id INTEGER REFERENCES res_partner(id),
    product_id INTEGER REFERENCES product_product(id),
    quantity NUMERIC(16,4),
    bd_vat_amount NUMERIC(16,2),
    bd_tds_amount NUMERIC(16,2),
    mushak_line_id INTEGER REFERENCES smart_dairy_mushak_line(id)
);
```

#### 9.1.2 Inventory Tables

**stock_quant (Inventory Quantities):**
```sql
CREATE TABLE stock_quant (
    id SERIAL PRIMARY KEY,
    product_id INTEGER REFERENCES product_product(id),
    location_id INTEGER REFERENCES stock_location(id),
    lot_id INTEGER REFERENCES stock_lot(id),
    package_id INTEGER REFERENCES stock_quant_package(id),
    owner_id INTEGER REFERENCES res_partner(id),
    quantity NUMERIC(16,4) DEFAULT 0,
    reserved_quantity NUMERIC(16,4) DEFAULT 0,
    available_quantity NUMERIC(16,4) GENERATED ALWAYS AS (quantity - reserved_quantity) STORED,
    quality_status VARCHAR(20) DEFAULT 'pending',
    expiration_date DATE,
    removal_date DATE,
    in_date TIMESTAMP,
    inventory_value NUMERIC(16,2),
    company_id INTEGER REFERENCES res_company(id)
);

CREATE INDEX idx_stock_quant_product_location ON stock_quant(product_id, location_id);
CREATE INDEX idx_stock_quant_lot ON stock_quant(lot_id);
CREATE INDEX idx_stock_quant_expiration ON stock_quant(expiration_date) WHERE quantity > 0;
```

**stock_lot (Lot/Serial Numbers):**
```sql
CREATE TABLE stock_lot (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    product_id INTEGER REFERENCES product_product(id),
    company_id INTEGER REFERENCES res_company(id),
    production_date DATE,
    expiration_date DATE,
    removal_date DATE,
    milk_fat_percentage NUMERIC(5,2),
    milk_snf_percentage NUMERIC(5,2),
    source_farm_ids INTEGER[],
    qc_passed BOOLEAN DEFAULT FALSE,
    qc_date DATE,
    qc_inspector_id INTEGER REFERENCES res_users(id),
    temperature_compliant BOOLEAN DEFAULT TRUE,
    temperature_breaches INTEGER DEFAULT 0
);
```

#### 9.1.3 Manufacturing Tables

**mrp_production (Manufacturing Orders):**
```sql
CREATE TABLE mrp_production (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    product_id INTEGER REFERENCES product_product(id),
    product_qty NUMERIC(16,4) NOT NULL,
    product_uom_id INTEGER REFERENCES uom_uom(id),
    bom_id INTEGER REFERENCES mrp_bom(id),
    state VARCHAR(20) DEFAULT 'draft',
    date_planned_start TIMESTAMP,
    date_planned_finished TIMESTAMP,
    date_start TIMESTAMP,
    date_finished TIMESTAMP,
    location_src_id INTEGER REFERENCES stock_location(id),
    location_dest_id INTEGER REFERENCES stock_location(id),
    company_id INTEGER REFERENCES res_company(id),
    milk_fat_percentage NUMERIC(5,2),
    milk_snf_percentage NUMERIC(5,2),
    actual_yield NUMERIC(16,4),
    expected_yield NUMERIC(16,4),
    yield_variance NUMERIC(16,4),
    user_id INTEGER REFERENCES res_users(id)
);
```

**mrp_bom (Bill of Materials):**
```sql
CREATE TABLE mrp_bom (
    id SERIAL PRIMARY KEY,
    product_tmpl_id INTEGER REFERENCES product_template(id),
    product_id INTEGER REFERENCES product_product(id),
    product_qty NUMERIC(16,4) DEFAULT 1.0,
    product_uom_id INTEGER REFERENCES uom_uom(id),
    type VARCHAR(20) DEFAULT 'normal',
    target_fat_percentage NUMERIC(4,2),
    target_snf_percentage NUMERIC(4,2),
    expected_yield_percentage NUMERIC(5,2) DEFAULT 95.0,
    company_id INTEGER REFERENCES res_company(id),
    active BOOLEAN DEFAULT TRUE
);
```

#### 9.1.4 HR Tables

**hr_employee (Employees):**
```sql
CREATE TABLE hr_employee (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    work_email VARCHAR(255),
    work_phone VARCHAR(32),
    mobile_phone VARCHAR(32),
    department_id INTEGER REFERENCES hr_department(id),
    job_id INTEGER REFERENCES hr_job(id),
    parent_id INTEGER REFERENCES hr_employee(id),
    coach_id INTEGER REFERENCES hr_employee(id),
    company_id INTEGER REFERENCES res_company(id),
    -- Bangladesh specific
    bd_national_id VARCHAR(17),
    bd_tin VARCHAR(12),
    bd_pf_number VARCHAR(50),
    employment_type VARCHAR(20),
    department_section VARCHAR(50),
    shift_type VARCHAR(20),
    is_pf_eligible BOOLEAN DEFAULT TRUE,
    pf_join_date DATE,
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_hr_employee_national_id ON hr_employee(bd_national_id);
CREATE INDEX idx_hr_employee_pf ON hr_employee(bd_pf_number);
```

**hr_payslip (Payslips):**
```sql
CREATE TABLE hr_payslip (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    employee_id INTEGER REFERENCES hr_employee(id),
    contract_id INTEGER REFERENCES hr_contract(id),
    struct_id INTEGER REFERENCES hr_payroll_structure(id),
    date_from DATE NOT NULL,
    date_to DATE NOT NULL,
    state VARCHAR(20) DEFAULT 'draft',
    number VARCHAR(50),
    worked_days_line_ids INTEGER[],
    line_ids INTEGER[],
    -- Bangladesh specific
    bd_tax_deducted NUMERIC(16,2),
    pf_employee_contribution NUMERIC(16,2),
    pf_employer_contribution NUMERIC(16,2),
    overtime_hours NUMERIC(8,2),
    overtime_amount NUMERIC(16,2),
    festival_bonus NUMERIC(16,2),
    net_amount NUMERIC(16,2),
    paid BOOLEAN DEFAULT FALSE,
    payment_date DATE
);
```

### 9.2 Custom Field Definitions

#### 9.2.1 Product Template Extensions

```sql
ALTER TABLE product_template ADD COLUMN is_dairy_product BOOLEAN DEFAULT FALSE;
ALTER TABLE product_template ADD COLUMN dairy_category VARCHAR(50);
ALTER TABLE product_template ADD COLUMN shelf_life_days INTEGER;
ALTER TABLE product_template ADD COLUMN target_fat_percent NUMERIC(5,2);
ALTER TABLE product_template ADD COLUMN target_snf_percent NUMERIC(5,2);
ALTER TABLE product_template ADD COLUMN requires_cold_storage BOOLEAN DEFAULT FALSE;
ALTER TABLE product_template ADD COLUMN storage_temp_min NUMERIC(5,2);
ALTER TABLE product_template ADD COLUMN storage_temp_max NUMERIC(5,2);
ALTER TABLE product_template ADD COLUMN hs_code VARCHAR(20);
ALTER TABLE product_template ADD COLUMN bd_vat_rate NUMERIC(5,2) DEFAULT 15.0;
```

#### 9.2.2 Partner Extensions

```sql
ALTER TABLE res_partner ADD COLUMN bd_nid VARCHAR(17);
ALTER TABLE res_partner ADD COLUMN bd_tin VARCHAR(12);
ALTER TABLE res_partner ADD COLUMN bd_bin VARCHAR(20);
ALTER TABLE res_partner ADD COLUMN is_registered_vat BOOLEAN DEFAULT FALSE;
ALTER TABLE res_partner ADD COLUMN is_farmer BOOLEAN DEFAULT FALSE;
ALTER TABLE res_partner ADD COLUMN farmer_group VARCHAR(100);
ALTER TABLE res_partner ADD COLUMN farm_location_id INTEGER REFERENCES stock_location(id);
ALTER TABLE res_partner ADD COLUMN milk_supply_capacity NUMERIC(16,2);
```

### 9.3 Index Optimization

#### 9.3.1 High-Traffic Table Indexes

```sql
-- Account move indexes
CREATE INDEX idx_account_move_date ON account_move(date);
CREATE INDEX idx_account_move_state ON account_move(state) WHERE state = 'draft';
CREATE INDEX idx_account_move_partner ON account_move(partner_id, date);

-- Stock move indexes
CREATE INDEX idx_stock_move_product ON stock_move(product_id, state);
CREATE INDEX idx_stock_move_location ON stock_move(location_id, location_dest_id);
CREATE INDEX idx_stock_move_date ON stock_move(date);

-- Payslip indexes
CREATE INDEX idx_hr_payslip_employee ON hr_payslip(employee_id, date_from, date_to);
CREATE INDEX idx_hr_payslip_state ON hr_payslip(state) WHERE state = 'draft';
```

### 9.4 Partitioning Strategy

#### 9.4.1 Transaction Tables

```sql
-- Partition account_move by year for historical data
CREATE TABLE account_move_2024 PARTITION OF account_move
    FOR VALUES FROM ('2024-01-01') TO ('2025-01-01');

CREATE TABLE account_move_2025 PARTITION OF account_move
    FOR VALUES FROM ('2025-01-01') TO ('2026-01-01');

-- Partition stock_move by date
CREATE TABLE stock_move_2024_q1 PARTITION OF stock_move
    FOR VALUES FROM ('2024-01-01') TO ('2024-04-01');
```

---

## 10. Implementation Code

### 10.1 Model Definitions

#### 10.1.1 Base Model with Audit Trail

```python
# smart_dairy_base/models/smart_dairy_mixin.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError

class SmartDairyAuditMixin(models.AbstractModel):
    _name = 'smart_dairy.audit.mixin'
    _description = 'Audit Trail Mixin for Smart Dairy'
    
    created_by = fields.Many2one(
        'res.users', 
        string='Created By',
        default=lambda self: self.env.user.id,
        readonly=True
    )
    created_on = fields.Datetime(
        string='Created On',
        default=fields.Datetime.now,
        readonly=True
    )
    modified_by = fields.Many2one(
        'res.users',
        string='Last Modified By',
        readonly=True
    )
    modified_on = fields.Datetime(
        string='Last Modified On',
        readonly=True
    )
    
    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            vals['created_by'] = self.env.user.id
            vals['created_on'] = fields.Datetime.now()
        return super().create(vals_list)
    
    def write(self, vals):
        vals['modified_by'] = self.env.user.id
        vals['modified_on'] = fields.Datetime.now()
        return super().write(vals)


class SmartDairyCompanyMixin(models.AbstractModel):
    _name = 'smart_dairy.company.mixin'
    _description = 'Company-specific Mixin'
    
    company_id = fields.Many2one(
        'res.company',
        string='Company',
        default=lambda self: self.env.company,
        required=True
    )
    currency_id = fields.Many2one(
        'res.currency',
        related='company_id.currency_id',
        store=True
    )
```

#### 10.1.2 Bangladesh VAT Model

```python
# smart_dairy_accounting_bd/models/bd_vat.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError
import base64
import io
import xlsxwriter

class BdVatReportBase(models.Model):
    _name = 'smart_dairy.bd.vat.report.base'
    _description = 'Base for Bangladesh VAT Reports'
    _inherit = ['smart_dairy.audit.mixin']
    
    name = fields.Char(string='Report Name', required=True)
    report_type = fields.Selection([
        ('mushak_9_1', 'Mushak 9.1 - Purchase'),
        ('mushak_9_2', 'Mushak 9.2 - Sales'),
        ('mushak_6_3', 'Mushak 6.3 - Input/Output'),
        ('mushak_6_1', 'Mushak 6.1 - Monthly Return'),
    ], required=True)
    
    date_from = fields.Date(string='From Date', required=True)
    date_to = fields.Date(string='To Date', required=True)
    company_id = fields.Many2one(
        'res.company',
        required=True,
        default=lambda self: self.env.company
    )
    state = fields.Selection([
        ('draft', 'Draft'),
        ('generated', 'Generated'),
        ('submitted', 'Submitted to NBR'),
        ('approved', 'Approved'),
    ], default='draft')
    
    line_ids = fields.One2many(
        'smart_dairy.bd.vat.report.line',
        'report_id'
    )
    
    total_taxable_value = fields.Monetary(compute='_compute_totals')
    total_vat_amount = fields.Monetary(compute='_compute_totals')
    total_sd_amount = fields.Monetary(compute='_compute_totals')
    
    currency_id = fields.Many2one(related='company_id.currency_id')
    
    @api.depends('line_ids')
    def _compute_totals(self):
        for report in self:
            report.total_taxable_value = sum(
                line.taxable_value for line in report.line_ids
            )
            report.total_vat_amount = sum(
                line.vat_amount for line in report.line_ids
            )
            report.total_sd_amount = sum(
                line.sd_amount for line in report.line_ids
            )
    
    def action_generate(self):
        self.ensure_one()
        self.line_ids.unlink()
        
        if self.report_type == 'mushak_9_1':
            self._generate_mushak_9_1()
        elif self.report_type == 'mushak_9_2':
            self._generate_mushak_9_2()
        elif self.report_type == 'mushak_6_3':
            self._generate_mushak_6_3()
        
        self.state = 'generated'
    
    def _generate_mushak_9_1(self):
        """Generate Mushak 9.1 Purchase Report"""
        moves = self.env['account.move'].search([
            ('move_type', '=', 'in_invoice'),
            ('state', '=', 'posted'),
            ('date', '>=', self.date_from),
            ('date', '<=', self.date_to),
            ('company_id', '=', self.company_id.id),
        ])
        
        lines = []
        for move in moves:
            for line in move.invoice_line_ids:
                vat_taxes = line.tax_ids.filtered(
                    lambda t: t.tax_group_id.name == 'VAT'
                )
                
                if not vat_taxes:
                    continue
                
                vat_rate = sum(vat_taxes.mapped('amount'))
                vat_amount = line.price_subtotal * vat_rate / 100
                
                supply_type = 'registered' if move.partner_id.is_registered_vat else 'unregistered'
                
                lines.append((0, 0, {
                    'report_id': self.id,
                    'date': move.date,
                    'invoice_number': move.name,
                    'partner_id': move.partner_id.id,
                    'partner_name': move.partner_id.name,
                    'partner_bin': move.partner_id.bd_bin,
                    'partner_nid': move.partner_id.bd_nid,
                    'product_id': line.product_id.id,
                    'hs_code': line.product_id.hs_code,
                    'quantity': line.quantity,
                    'uom': line.product_uom_id.name,
                    'taxable_value': line.price_subtotal,
                    'vat_rate': vat_rate,
                    'vat_amount': vat_amount,
                    'total_amount': line.price_total,
                    'supply_type': supply_type,
                }))
        
        self.env['smart_dairy.bd.vat.report.line'].create(lines)
    
    def action_export_excel(self):
        self.ensure_one()
        
        output = io.BytesIO()
        workbook = xlsxwriter.Workbook(output)
        worksheet = workbook.add_worksheet()
        
        # Headers
        headers = [
            'Date', 'Invoice No', 'Supplier Name', 'BIN', 'NID',
            'Product', 'HS Code', 'Qty', 'UOM', 'Taxable Value',
            'VAT Rate', 'VAT Amount', 'Total', 'Supply Type'
        ]
        
        header_format = workbook.add_format({
            'bold': True,
            'bg_color': '#4472C4',
            'font_color': 'white'
        })
        
        for col, header in enumerate(headers):
            worksheet.write(0, col, header, header_format)
        
        # Data
        for row, line in enumerate(self.line_ids, start=1):
            worksheet.write(row, 0, line.date)
            worksheet.write(row, 1, line.invoice_number)
            worksheet.write(row, 2, line.partner_name)
            worksheet.write(row, 3, line.partner_bin or '')
            worksheet.write(row, 4, line.partner_nid or '')
            worksheet.write(row, 5, line.product_id.name)
            worksheet.write(row, 6, line.hs_code or '')
            worksheet.write(row, 7, line.quantity)
            worksheet.write(row, 8, line.uom)
            worksheet.write(row, 9, line.taxable_value)
            worksheet.write(row, 10, line.vat_rate)
            worksheet.write(row, 11, line.vat_amount)
            worksheet.write(row, 12, line.total_amount)
            worksheet.write(row, 13, line.supply_type)
        
        workbook.close()
        output.seek(0)
        
        attachment = self.env['ir.attachment'].create({
            'name': '%s_%s.xlsx' % (self.report_type, self.date_from),
            'type': 'binary',
            'datas': base64.b64encode(output.read()),
            'res_model': self._name,
            'res_id': self.id,
        })
        
        return {
            'type': 'ir.actions.act_url',
            'url': '/web/content/%s?download=1' % attachment.id,
            'target': 'self',
        }


class BdVatReportLine(models.Model):
    _name = 'smart_dairy.bd.vat.report.line'
    _description = 'VAT Report Line'
    
    report_id = fields.Many2one(
        'smart_dairy.bd.vat.report.base',
        required=True,
        ondelete='cascade'
    )
    
    date = fields.Date(string='Date')
    invoice_number = fields.Char(string='Invoice Number')
    partner_id = fields.Many2one('res.partner')
    partner_name = fields.Char(string='Partner Name')
    partner_bin = fields.Char(string='BIN')
    partner_nid = fields.Char(string='NID')
    
    product_id = fields.Many2one('product.product')
    hs_code = fields.Char(string='HS Code')
    quantity = fields.Float(string='Quantity')
    uom = fields.Char(string='UOM')
    
    taxable_value = fields.Monetary(string='Taxable Value')
    vat_rate = fields.Float(string='VAT Rate (%)')
    vat_amount = fields.Monetary(string='VAT Amount')
    sd_rate = fields.Float(string='SD Rate (%)')
    sd_amount = fields.Monetary(string='SD Amount')
    total_amount = fields.Monetary(string='Total Amount')
    
    supply_type = fields.Selection([
        ('registered', 'Registered'),
        ('unregistered', 'Unregistered'),
        ('import', 'Import'),
        ('export', 'Export'),
    ], string='Supply Type')
    
    currency_id = fields.Many2one(related='report_id.currency_id')
```

#### 10.1.3 Payroll Calculation Model

```python
# smart_dairy_hr_payroll_bd/models/bd_payroll.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError

class HrPayslipBd(models.Model):
    _inherit = 'hr.payslip'
    
    bd_tax_deducted = fields.Monetary(
        string='Income Tax Deducted',
        currency_field='company_currency_id'
    )
    pf_employee_contribution = fields.Monetary(
        string='PF (Employee 10%)',
        currency_field='company_currency_id'
    )
    pf_employer_contribution = fields.Monetary(
        string='PF (Employer 10%)',
        currency_field='company_currency_id'
    )
    total_pf_contribution = fields.Monetary(
        compute='_compute_total_pf',
        currency_field='company_currency_id'
    )
    
    overtime_hours = fields.Float(string='Overtime Hours')
    overtime_amount = fields.Monetary(
        string='Overtime Amount',
        currency_field='company_currency_id'
    )
    
    festival_bonus = fields.Monetary(
        string='Festival Bonus',
        currency_field='company_currency_id'
    )
    bonus_eligible = fields.Boolean(string='Bonus Eligible')
    
    @api.depends('pf_employee_contribution', 'pf_employer_contribution')
    def _compute_total_pf(self):
        for slip in self:
            slip.total_pf_contribution = (
                slip.pf_employee_contribution + 
                slip.pf_employer_contribution
            )
    
    def compute_sheet(self):
        for payslip in self:
            # Calculate Bangladesh-specific components
            payslip._calculate_bd_components()
        return super(HrPayslipBd, self).compute_sheet()
    
    def _calculate_bd_components(self):
        self.ensure_one()
        contract = self.contract_id
        
        if not contract:
            return
        
        # Get basic salary
        basic = contract.wage
        
        # Calculate PF (10% of basic by both)
        if contract.structure_type_id.applies_pf and self.employee_id.is_pf_eligible:
            pf_amount = basic * 0.10
            self.pf_employee_contribution = pf_amount
            self.pf_employer_contribution = pf_amount
        
        # Calculate Income Tax
        annual_taxable = self._calculate_annual_taxable_income()
        annual_tax = self._calculate_bd_annual_tax(annual_taxable)
        self.bd_tax_deducted = annual_tax / 12
        
        # Calculate Overtime (2x hourly rate)
        if contract.structure_type_id.applies_overtime and self.overtime_hours > 0:
            hourly_rate = basic / (30 * 8)
            self.overtime_amount = self.overtime_hours * hourly_rate * 2
        
        # Calculate Festival Bonus (if applicable month)
        current_month = self.date_from.month
        if contract.structure_type_id.applies_bonus and current_month in [4, 11]:
            self.bonus_eligible = True
            self.festival_bonus = basic
    
    def _calculate_annual_taxable_income(self):
        contract = self.contract_id
        basic = contract.wage
        
        # Get allowances
        house_rent = contract.house_rent_allowance or (basic * 0.50)
        medical = contract.medical_allowance or min(basic * 0.10, 120000/12)
        conveyance = contract.conveyance_allowance or 2500
        
        gross_monthly = basic + house_rent + medical + conveyance
        
        # Exemptions (as per BD tax rules)
        # House rent: exempt up to 50% of basic or 25,000/month
        house_rent_exempt = min(house_rent, basic * 0.50, 25000)
        
        # Medical: exempt up to 10% of basic or 120,000/year
        medical_exempt = min(medical, basic * 0.10, 10000)
        
        # Conveyance: exempt up to 30,000/year
        conveyance_exempt = min(conveyance, 2500)
        
        taxable_monthly = (
            gross_monthly - 
            house_rent_exempt - 
            medical_exempt - 
            conveyance_exempt -
            self.pf_employee_contribution
        )
        
        return taxable_monthly * 12
    
    def _calculate_bd_annual_tax(self, annual_income):
        # Tax-free threshold
        threshold = 350000
        
        if annual_income <= threshold:
            return 0
        
        taxable = annual_income - threshold
        tax = 0
        
        # Slabs
        if taxable > 0:
            slab = min(taxable, 100000)
            tax += slab * 0.05
            taxable -= slab
        
        if taxable > 0:
            slab = min(taxable, 300000)
            tax += slab * 0.10
            taxable -= slab
        
        if taxable > 0:
            slab = min(taxable, 400000)
            tax += slab * 0.15
            taxable -= slab
        
        if taxable > 0:
            slab = min(taxable, 500000)
            tax += slab * 0.20
            taxable -= slab
        
        if taxable > 0:
            tax += taxable * 0.25
        
        # Minimum tax
        if tax > 0 and tax < 5000:
            tax = 5000
        
        return tax
```

### 10.2 Business Logic

#### 10.2.1 Inventory Valuation Logic

```python
# smart_dairy_inventory/models/stock_valuation.py

from odoo import models, fields, api

class StockValuationLayer(models.Model):
    _inherit = 'stock.valuation.layer'
    
    lot_id = fields.Many2one('stock.lot', string='Lot/Serial Number')
    expiration_date = fields.Date(related='lot_id.expiration_date', store=True)
    
    def action_revalue_inventory(self, new_value):
        """Revalue inventory based on market price"""
        self.ensure_one()
        
        old_value = self.value
        value_diff = new_value - old_value
        
        # Create journal entry for revaluation
        journal = self.env['account.journal'].search([
            ('type', '=', 'general')
        ], limit=1)
        
        move_vals = {
            'journal_id': journal.id,
            'ref': 'Inventory Revaluation: %s' % self.product_id.name,
            'line_ids': [
                (0, 0, {
                    'account_id': self.product_id.categ_id.property_stock_valuation_account_id.id,
                    'name': 'Inventory Revaluation',
                    'debit': value_diff if value_diff > 0 else 0,
                    'credit': abs(value_diff) if value_diff < 0 else 0,
                }),
                (0, 0, {
                    'account_id': self.product_id.categ_id.property_stock_account_output_categ_id.id,
                    'name': 'Inventory Revaluation',
                    'debit': abs(value_diff) if value_diff < 0 else 0,
                    'credit': value_diff if value_diff > 0 else 0,
                }),
            ]
        }
        
        account_move = self.env['account.move'].create(move_vals)
        account_move.action_post()
        
        self.value = new_value
        
        return account_move


class StockQuant(models.Model):
    _inherit = 'stock.quant'
    
    def get_fefo_candidates(self, quantity_needed):
        """Get stock quants sorted by FEFO for picking"""
        self.ensure_one()
        
        candidates = self.search([
            ('product_id', '=', self.product_id.id),
            ('location_id', '=', self.location_id.id),
            ('quantity', '>', 0),
            ('quality_status', 'in', ['approved', False]),
        ], order='expiration_date ASC, in_date ASC')
        
        selected_quants = []
        remaining_qty = quantity_needed
        
        for quant in candidates:
            if remaining_qty <= 0:
                break
            
            available = quant.available_quantity
            take_qty = min(available, remaining_qty)
            
            selected_quants.append({
                'quant': quant,
                'quantity': take_qty,
            })
            
            remaining_qty -= take_qty
        
        return selected_quants
```

#### 10.2.2 MRP Planning Logic

```python
# smart_dairy_mrp/models/mrp_planning.py

from odoo import models, fields, api
from datetime import datetime, timedelta

class MrpPlanningEngine(models.Model):
    _name = 'smart_dairy.mrp.planning'
    _description = 'Dairy MRP Planning Engine'
    
    def run_mrp(self, product_ids=None, warehouse_ids=None):
        """Run MRP calculations for dairy products"""
        
        if not product_ids:
            products = self.env['product.product'].search([
                ('type', '=', 'product'),
                ('is_dairy_product', '=', True),
            ])
        else:
            products = self.env['product.product'].browse(product_ids)
        
        if not warehouse_ids:
            warehouses = self.env['stock.warehouse'].search([])
        else:
            warehouses = self.env['stock.warehouse'].browse(warehouse_ids)
        
        mrp_results = []
        
        for product in products:
            for warehouse in warehouses:
                result = self._calculate_mrp(product, warehouse)
                if result:
                    mrp_results.append(result)
        
        return mrp_results
    
    def _calculate_mrp(self, product, warehouse):
        """Calculate MRP for a single product"""
        
        # Get current stock
        qty_available = self.env['stock.quant']._get_available_quantity(
            product, warehouse.lot_stock_id
        )
        
        # Get reorder rules
        orderpoint = self.env['stock.warehouse.orderpoint'].search([
            ('product_id', '=', product.id),
            ('warehouse_id', '=', warehouse.id),
        ], limit=1)
        
        if not orderpoint:
            return None
        
        # Calculate requirements
        min_qty = orderpoint.product_min_qty
        max_qty = orderpoint.product_max_qty
        
        if qty_available >= min_qty:
            return None
        
        net_requirement = max_qty - qty_available
        
        # Determine supply method
        routes = product.route_ids
        if routes.filtered(lambda r: r.name == 'Manufacture'):
            supply_method = 'manufacture'
            bom = self.env['mrp.bom']._bom_find(product)
            lead_time = product.produce_delay or 1
        else:
            supply_method = 'buy'
            bom = None
            lead_time = product.sale_delay or 1
        
        # Calculate dates
        required_date = fields.Date.today() + timedelta(days=int(lead_time))
        order_date = fields.Date.today()
        
        return {
            'product_id': product.id,
            'product_name': product.name,
            'warehouse_id': warehouse.id,
            'warehouse_name': warehouse.name,
            'current_stock': qty_available,
            'min_stock': min_qty,
            'net_requirement': net_requirement,
            'supply_method': supply_method,
            'bom_id': bom.id if bom else None,
            'order_date': order_date,
            'required_date': required_date,
        }
    
    def create_production_plan(self, mrp_results):
        """Create manufacturing orders from MRP results"""
        
        mo_model = self.env['mrp.production']
        created_mos = []
        
        for result in mrp_results:
            if result['supply_method'] != 'manufacture':
                continue
            
            mo_vals = {
                'product_id': result['product_id'],
                'product_qty': result['net_requirement'],
                'bom_id': result['bom_id'],
                'date_planned_start': result['order_date'],
                'date_planned_finished': result['required_date'],
                'origin': 'MRP Planning',
            }
            
            mo = mo_model.create(mo_vals)
            created_mos.append(mo)
        
        return created_mos
```

### 10.3 Report Generators

#### 10.3.1 Financial Report Generator

```python
# smart_dairy_accounting_bd/models/financial_reports.py

from odoo import models, fields, api
from odoo.tools import float_compare

class SmartDairyFinancialReport(models.AbstractModel):
    _name = 'smart_dairy.financial.report'
    _description = 'Financial Report Generator'
    
    def generate_profit_loss(self, date_from, date_to, company_id):
        """Generate Profit and Loss Statement"""
        
        # Revenue accounts
        revenue_accounts = self.env['account.account'].search([
            ('account_type', 'in', ['income', 'income_other']),
            ('company_id', '=', company_id),
        ])
        
        revenue = sum(
            revenue_accounts.mapped(lambda a: 
                a.with_context(date_from=date_from, date_to=date_to).balance
            )
        )
        
        # Cost of goods sold
        cogs_accounts = self.env['account.account'].search([
            ('account_type', '=', 'expense_direct_cost'),
            ('company_id', '=', company_id),
        ])
        
        cogs = abs(sum(
            cogs_accounts.mapped(lambda a: 
                a.with_context(date_from=date_from, date_to=date_to).balance
            )
        ))
        
        gross_profit = revenue - cogs
        
        # Operating expenses
        opex_accounts = self.env['account.account'].search([
            ('account_type', '=', 'expense'),
            ('company_id', '=', company_id),
        ])
        
        operating_expenses = abs(sum(
            opex_accounts.mapped(lambda a: 
                a.with_context(date_from=date_from, date_to=date_to).balance
            )
        ))
        
        # Net profit before tax
        net_profit_before_tax = gross_profit - operating_expenses
        
        # Tax expense
        tax_accounts = self.env['account.account'].search([
            ('account_type', '=', 'expense_depreciation'),
            ('name', 'ilike', 'tax'),
            ('company_id', '=', company_id),
        ])
        
        tax_expense = abs(sum(
            tax_accounts.mapped(lambda a: 
                a.with_context(date_from=date_from, date_to=date_to).balance
            )
        ))
        
        net_profit_after_tax = net_profit_before_tax - tax_expense
        
        return {
            'revenue': revenue,
            'cogs': cogs,
            'gross_profit': gross_profit,
            'gross_margin_percent': (gross_profit / revenue * 100) if revenue else 0,
            'operating_expenses': operating_expenses,
            'net_profit_before_tax': net_profit_before_tax,
            'tax_expense': tax_expense,
            'net_profit_after_tax': net_profit_after_tax,
            'net_margin_percent': (net_profit_after_tax / revenue * 100) if revenue else 0,
        }
```

#### 10.3.2 Inventory Aging Report

```python
# smart_dairy_inventory/models/inventory_reports.py

from odoo import models, fields, api
from datetime import datetime, timedelta

class InventoryAgingReport(models.TransientModel):
    _name = 'smart_dairy.inventory.aging.report'
    _description = 'Inventory Aging Report'
    
    date = fields.Date(default=fields.Date.today)
    warehouse_id = fields.Many2one('stock.warehouse')
    product_category_id = fields.Many2one('product.category')
    
    def generate_report(self):
        """Generate inventory aging analysis"""
        
        domain = [
            ('quantity', '>', 0),
        ]
        
        if self.warehouse_id:
            domain.append(('location_id.warehouse_id', '=', self.warehouse_id.id))
        
        quants = self.env['stock.quant'].search(domain)
        
        aging_buckets = {
            '0_30': {'value': 0, 'quantity': 0},
            '31_60': {'value': 0, 'quantity': 0},
            '61_90': {'value': 0, 'quantity': 0},
            '91_180': {'value': 0, 'quantity': 0},
            '180_plus': {'value': 0, 'quantity': 0},
        }
        
        for quant in quants:
            if not quant.lot_id or not quant.lot_id.production_date:
                continue
            
            age_days = (self.date - quant.lot_id.production_date).days
            value = quant.quantity * quant.product_id.standard_price
            
            if age_days <= 30:
                bucket = '0_30'
            elif age_days <= 60:
                bucket = '31_60'
            elif age_days <= 90:
                bucket = '61_90'
            elif age_days <= 180:
                bucket = '91_180'
            else:
                bucket = '180_plus'
            
            aging_buckets[bucket]['value'] += value
            aging_buckets[bucket]['quantity'] += quant.quantity
        
        total_value = sum(b['value'] for b in aging_buckets.values())
        
        result = []
        for bucket, data in aging_buckets.items():
            result.append({
                'bucket': bucket,
                'quantity': data['quantity'],
                'value': data['value'],
                'percentage': (data['value'] / total_value * 100) if total_value else 0,
            })
        
        return result
```

### 10.4 Workflow Definitions

#### 10.4.1 Manufacturing Order Workflow

```xml
<!-- smart_dairy_mrp/data/mrp_workflow.xml -->
<odoo>
    <data>
        <!-- Manufacturing Order Workflow -->
        <record id="mrp_production_workflow" model="workflow">
            <field name="name">Manufacturing Order Workflow</field>
            <field name="osv">mrp.production</field>
            <field name="on_create">True</field>
        </record>
        
        <!-- Activities -->
        <record id="act_mo_draft" model="workflow.activity">
            <field name="wkf_id" ref="mrp_production_workflow"/>
            <field name="name">Draft</field>
            <field name="kind">function</field>
            <field name="action">write({'state': 'draft'})</field>
        </record>
        
        <record id="act_mo_confirmed" model="workflow.activity">
            <field name="wkf_id" ref="mrp_production_workflow"/>
            <field name="name">Confirmed</field>
            <field name="kind">function</field>
            <field name="action">action_confirm()</field>
        </record>
        
        <record id="act_mo_ready" model="workflow.activity">
            <field name="wkf_id" ref="mrp_production_workflow"/>
            <field name="name">Ready</field>
            <field name="kind">function</field>
            <field name="action">write({'state': 'ready'})</field>
        </record>
        
        <record id="act_mo_in_progress" model="workflow.activity">
            <field name="wkf_id" ref="mrp_production_workflow"/>
            <field name="name">In Progress</field>
            <field name="kind">function</field>
            <field name="action">action_assign()</field>
        </record>
        
        <record id="act_mo_qc_hold" model="workflow.activity">
            <field name="wkf_id" ref="mrp_production_workflow"/>
            <field name="name">QC Hold</field>
            <field name="kind">function</field>
            <field name="action">write({'state': 'qc_hold'})</field>
        </record>
        
        <record id="act_mo_done" model="workflow.activity">
            <field name="wkf_id" ref="mrp_production_workflow"/>
            <field name="name">Done</field>
            <field name="kind">function</field>
            <field name="action">button_mark_done()</field>
        </record>
        
        <!-- Transitions -->
        <record id="trans_mo_draft_confirmed" model="workflow.transition">
            <field name="act_from" ref="act_mo_draft"/>
            <field name="act_to" ref="act_mo_confirmed"/>
            <field name="signal">button_confirm</field>
        </record>
        
        <record id="trans_mo_confirmed_ready" model="workflow.transition">
            <field name="act_from" ref="act_mo_confirmed"/>
            <field name="act_to" ref="act_mo_ready"/>
            <field name="signal">button_ready</field>
        </record>
        
        <record id="trans_mo_ready_in_progress" model="workflow.transition">
            <field name="act_from" ref="act_mo_ready"/>
            <field name="act_to" ref="act_mo_in_progress"/>
            <field name="signal">button_start</field>
        </record>
        
        <record id="trans_mo_in_progress_qc" model="workflow.transition">
            <field name="act_from" ref="act_mo_in_progress"/>
            <field name="act_to" ref="act_mo_qc_hold"/>
            <field name="signal">button_qc_hold</field>
        </record>
        
        <record id="trans_mo_qc_done" model="workflow.transition">
            <field name="act_from" ref="act_mo_qc_hold"/>
            <field name="act_to" ref="act_mo_done"/>
            <field name="signal">button_qc_pass</field>
        </record>
    </data>
</odoo>
```

#### 10.4.2 Purchase Approval Workflow

```python
# smart_dairy_procurement/models/purchase_workflow.py

from odoo import models, fields, api

class PurchaseOrder(models.Model):
    _inherit = 'purchase.order'
    
    state = fields.Selection(selection_add=[
        ('department_approval', 'Department Approval'),
        ('finance_approval', 'Finance Approval'),
        ('director_approval', 'Director Approval'),
    ])
    
    approver_ids = fields.Many2many('res.users', string='Approvers')
    current_approver = fields.Many2one('res.users', string='Current Approver')
    
    def button_submit_for_approval(self):
        self.ensure_one()
        
        # Determine approval route based on amount
        if self.amount_total < 50000:
            self.state = 'department_approval'
            self.current_approver = self.department_manager_id.user_id
        elif self.amount_total < 200000:
            self.state = 'finance_approval'
            self.current_approver = self.env.user.company_id.financial_manager_id
        else:
            self.state = 'director_approval'
            self.current_approver = self.env.user.company_id.director_id
        
        # Send notification
        self._send_approval_notification()
    
    def button_approve(self):
        self.ensure_one()
        
        if self.state == 'department_approval':
            self.state = 'finance_approval'
            self.current_approver = self.env.user.company_id.financial_manager_id
        elif self.state == 'finance_approval':
            if self.amount_total >= 200000:
                self.state = 'director_approval'
                self.current_approver = self.env.user.company_id.director_id
            else:
                self.button_approve_final()
        elif self.state == 'director_approval':
            self.button_approve_final()
        
        self.approver_ids |= self.env.user
        self._send_approval_notification()
    
    def button_approve_final(self):
        self.state = 'purchase'
        self.current_approver = False
    
    def button_reject(self):
        self.state = 'cancel'
        self.current_approver = False
        self._send_rejection_notification()
```

---

## 11. Appendices

### Appendix A: Configuration Guides

#### A.1 Initial Company Setup

```python
# Configuration script for initial setup

# 1. Create Chart of Accounts
def setup_chart_of_accounts(company):
    chart_template = env.ref('smart_dairy_accounting_bd.chart_template_bd')
    chart_template.with_context(company_id=company.id).try_loading()

# 2. Configure VAT Taxes
def setup_vat_taxes(company):
    vat_rates = [5, 7.5, 10, 15]
    for rate in vat_rates:
        env['account.tax'].create({
            'name': 'VAT %s%%' % rate,
            'amount': rate,
            'type_tax_use': 'sale',
            'company_id': company.id,
        })

# 3. Configure Warehouses
def setup_warehouses(company):
    warehouse_types = [
        ('farm', 'Dairy Farm - Main'),
        ('collection_center', 'Milk Collection - Dhaka'),
        ('processing_plant', 'Processing Plant - Gazipur'),
        ('cold_storage', 'Cold Storage - Ashulia'),
        ('distribution_center', 'Distribution - Mohakhali'),
    ]
    
    for wtype, name in warehouse_types:
        env['stock.warehouse'].create({
            'name': name,
            'code': name[:5].upper(),
            'company_id': company.id,
            'warehouse_type': wtype,
        })

# 4. Configure Employee Categories
def setup_employee_categories():
    categories = [
        ('Permanent', 'Permanent Staff'),
        ('Contractual', 'Contractual Staff'),
        ('Temporary', 'Temporary Workers'),
    ]
    
    for name, description in categories:
        env['hr.employee.category'].create({
            'name': name,
            'description': description,
        })
```

#### A.2 Bangladesh Compliance Checklist

**Tax Compliance:**
- [ ] BIN registration with NBR
- [ ] Monthly VAT return (Mushak 6.1)
- [ ] Quarterly TDS returns
- [ ] Annual income tax return
- [ ] Advance income tax (AIT) compliance

**Labor Compliance:**
- [ ] Factory license
- [ ] Fire safety certificate
- [ ] Boilers certificate (if applicable)
- [ ] Worker registration with labor department
- [ ] Provident fund registration
- [ ] Group insurance compliance

**Food Safety Compliance:**
- [ ] BSTI license
- [ ] Food safety management system (ISO 22000)
- [ ] HACCP certification
- [ ] Dairy plant license
- [ ] Water quality testing
- [ ] Product registration

**Environmental Compliance:**
- [ ] Environmental clearance
- [ ] ETP (Effluent Treatment Plant) compliance
- [ ] Waste disposal authorization
- [ ] Air quality monitoring

### Appendix B: Code Samples

#### B.1 Custom Widget for Temperature Display

```javascript
// smart_dairy_base/static/src/js/temperature_widget.js

odoo.define('smart_dairy.TemperatureWidget', function (require) {
    "use strict";
    
    var AbstractField = require('web.AbstractField');
    var field_registry = require('web.field_registry');
    
    var TemperatureWidget = AbstractField.extend({
        template: 'smart_dairy.TemperatureWidget',
        
        _renderReadonly: function () {
            var temp = this.value;
            var color = 'green';
            
            if (temp > 8) {
                color = 'red';
            } else if (temp > 6) {
                color = 'orange';
            }
            
            this.$el.html(
                '<span style="color:' + color + '">' + 
                temp.toFixed(1) + ' degrees C</span>'
            );
        },
    });
    
    field_registry.add('temperature', TemperatureWidget);
    
    return TemperatureWidget;
});
```

#### B.2 API Endpoint for External Integration

```python
# smart_dairy_base/controllers/api.py

from odoo import http
from odoo.http import request
import json

class SmartDairyApi(http.Controller):
    
    @http.route('/api/v1/inventory/stock', type='json', auth='api_key')
    def get_stock_levels(self, product_code=None, warehouse_code=None):
        """Get current stock levels via API"""
        
        domain = [('quantity', '>', 0)]
        
        if product_code:
            product = request.env['product.product'].search([
                ('default_code', '=', product_code)
            ], limit=1)
            if product:
                domain.append(('product_id', '=', product.id))
        
        if warehouse_code:
            warehouse = request.env['stock.warehouse'].search([
                ('code', '=', warehouse_code)
            ], limit=1)
            if warehouse:
                domain.append(('location_id.warehouse_id', '=', warehouse.id))
        
        quants = request.env['stock.quant'].search(domain)
        
        result = []
        for quant in quants:
            result.append({
                'product_code': quant.product_id.default_code,
                'product_name': quant.product_id.name,
                'warehouse': quant.location_id.warehouse_id.name,
                'location': quant.location_id.name,
                'lot_number': quant.lot_id.name if quant.lot_id else None,
                'expiration_date': str(quant.lot_id.expiration_date) if quant.lot_id else None,
                'quantity': quant.quantity,
                'available_quantity': quant.available_quantity,
                'unit': quant.product_uom_id.name if hasattr(quant, 'product_uom_id') else 'Unit',
            })
        
        return {'status': 'success', 'data': result}
    
    @http.route('/api/v1/manufacturing/orders', type='json', auth='api_key')
    def create_manufacturing_order(self, product_code, quantity, date_planned):
        """Create manufacturing order via API"""
        
        product = request.env['product.product'].search([
            ('default_code', '=', product_code)
        ], limit=1)
        
        if not product:
            return {'status': 'error', 'message': 'Product not found'}
        
        bom = request.env['mrp.bom']._bom_find(product)
        
        if not bom:
            return {'status': 'error', 'message': 'BOM not found'}
        
        mo = request.env['mrp.production'].create({
            'product_id': product.id,
            'product_qty': quantity,
            'bom_id': bom.id,
            'date_planned_start': date_planned,
        })
        
        return {
            'status': 'success',
            'data': {
                'mo_number': mo.name,
                'product': product.name,
                'quantity': quantity,
                'status': mo.state,
            }
        }
```

### Appendix C: Reference Documentation

#### C.1 Database Schema Diagram

```
[Entity Relationship Diagram - Key Tables]

res_company ||--o{ res_users : employs
res_company ||--o{ account_move : posts
res_company ||--o{ stock_warehouse : operates
res_company ||--o{ hr_employee : employs

account_move ||--o{ account_move_line : contains
account_move_line }o--|| account_account : debits_credits
account_move_line }o--|| res_partner : involves

product_template ||--o{ product_product : variants
product_template ||--o{ mrp_bom : manufactured_by

mrp_bom ||--o{ mrp_bom_line : components
mrp_production }o--|| mrp_bom : uses
mrp_production ||--o{ stock_move : produces

stock_warehouse ||--o{ stock_location : contains
stock_location ||--o{ stock_quant : holds
stock_quant }o--|| product_product : quantifies
stock_quant }o--|| stock_lot : tracks

hr_employee }o--|| hr_department : belongs_to
hr_employee ||--o{ hr_payslip : receives
hr_payslip }o--|| hr_contract : based_on
```

#### C.2 Glossary

| Term | Definition |
|------|------------|
| BOM | Bill of Materials - List of components required to produce a product |
| BSTI | Bangladesh Standards and Testing Institution |
| CAPA | Corrective and Preventive Action |
| CIP | Clean In Place - Automated cleaning system |
| CoA | Certificate of Analysis - Lab test results |
| FEFO | First Expired First Out - Inventory rotation method |
| FIFO | First In First Out - Inventory rotation method |
| HACCP | Hazard Analysis Critical Control Points |
| MRP | Material Requirements Planning |
| Mushak | VAT return forms in Bangladesh |
| NBR | National Board of Revenue (Bangladesh) |
| PF | Provident Fund - Retirement savings scheme |
| QC | Quality Control |
| SNF | Solids Not Fat - Milk component measurement |
| TDS | Tax Deducted at Source |
| VAT | Value Added Tax |
| WIP | Work In Progress |

#### C.3 External References

1. **Bangladesh Laws and Regulations:**
   - Income Tax Ordinance 1984
   - Value Added Tax Act 1991
   - Bangladesh Labor Law 2006
   - Bangladesh Food Safety Act 2013

2. **International Standards:**
   - ISO 22000 - Food Safety Management
   - ISO 9001 - Quality Management
   - HACCP Guidelines
   - Codex Alimentarius

3. **Industry Best Practices:**
   - Dairy Processing Handbook (Tetra Pak)
   - IDF Bulletins
   - FAO Dairy Guidelines

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-02 | Smart Dairy Dev Team | Initial release |

## Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Project Manager | | | |
| Technical Lead | | | |
| QA Manager | | | |
| Client Representative | | | |

---

*End of Milestone 37 - Advanced ERP Modules Document*

*Smart Dairy Smart Portal + ERP System*
*Phase 4 - Enterprise Resource Planning*
