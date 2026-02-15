# K-019: Business Process Documentation

## Smart Dairy Ltd. - Business Process Documentation

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | K-019 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Business Analyst |
| **Owner** | Operations Manager |
| **Reviewer** | Managing Director |
| **Status** | Draft |
| **Classification** | Internal Use |
| **Next Review Date** | April 30, 2026 |

---

## Document History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 31, 2026 | Business Analyst | Initial document creation |

---

## Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Operations Manager | [Name] | ________________ | ___________ |
| Managing Director | [Name] | ________________ | ___________ |
| Quality Manager | [Name] | ________________ | ___________ |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Process Hierarchy](#2-process-hierarchy)
3. [Core Business Processes](#3-core-business-processes)
4. [Process Documentation Template](#4-process-documentation-template)
5. [Standard Operating Procedures (SOPs)](#5-standard-operating-procedures-sops)
6. [Workflow Diagrams](#6-workflow-diagrams)
7. [Process Improvement](#7-process-improvement)
8. [Process Governance](#8-process-governance)
9. [Appendices](#9-appendices)

---

## 1. Introduction

### 1.1 Purpose of Process Documentation

This Business Process Documentation serves as the authoritative reference for all operational processes at Smart Dairy Ltd. The document provides:

- **Standardization**: Ensures consistent execution of processes across all departments
- **Training Foundation**: Serves as primary training material for new employees
- **Compliance Support**: Documents processes for regulatory and audit requirements
- **Continuous Improvement**: Baseline for identifying and implementing process enhancements
- **Knowledge Management**: Preserves institutional knowledge and reduces dependency on individuals
- **Quality Assurance**: Establishes clear standards and expectations for process execution

### 1.2 Document Scope

This documentation covers:
- All core business processes from farm operations to customer delivery
- Supporting administrative and management processes
- Cross-functional workflows and handoffs
- Standard Operating Procedures (SOPs)
- Process metrics and Key Performance Indicators (KPIs)

### 1.3 BPMN 2.0 Notation

This document uses Business Process Model and Notation (BPMN) 2.0 for process visualization. Key symbols include:

| Symbol | Name | Description |
|--------|------|-------------|
| ● | Start Event | Initiates a process |
| ○ | End Event | Terminates a process |
| ▭ | Task/Activity | Unit of work performed |
| ◇ | Gateway | Decision or parallel flow |
| — | Sequence Flow | Shows order of activities |
| ◯ | Pool | Major participant/organization |
| — | Lane | Subdivision of a pool (role/department) |
| ☐ | Data Object | Information required/produced |

### 1.4 Document Structure

Processes are documented using a hierarchical approach:
- **Level 1 (L1)**: Process Categories - Strategic business areas
- **Level 2 (L2)**: Process Groups - Major process clusters
- **Level 3 (L3)**: Processes - End-to-end workflows
- **Level 4 (L4)**: Procedures - Detailed step-by-step instructions

---

## 2. Process Hierarchy

### 2.1 Process Architecture Overview

```
┌─────────────────────────────────────────────────────────────────────────┐
│                    SMART DAIRY PROCESS ARCHITECTURE                     │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  ┌─────────────────────────────────────────────────────────────────┐   │
│  │                         L1: PROCESS CATEGORIES                   │   │
│  │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐        │   │
│  │  │  FARM    │  │  SALES   │  │ SUPPLY   │  │ FINANCE  │        │   │
│  │  │OPERATIONS│  │    &     │  │  CHAIN   │  │          │        │   │
│  │  │          │  │    DIST  │  │          │  │          │        │   │
│  │  └──────────┘  └──────────┘  └──────────┘  └──────────┘        │   │
│  └─────────────────────────────────────────────────────────────────┘   │
│                              ▼                                          │
│  ┌─────────────────────────────────────────────────────────────────┐   │
│  │                        L2: PROCESS GROUPS                        │   │
│  │  • Milk Production    • Order Management    • Procurement       │   │
│  │  • Animal Health      • Delivery            • Inventory         │   │
│  │  • Breeding           • Subscription        • Quality           │   │
│  │  • Feed Management    • Customer Service    • Cold Chain        │   │
│  └─────────────────────────────────────────────────────────────────┘   │
│                              ▼                                          │
│  ┌─────────────────────────────────────────────────────────────────┐   │
│  │                        L3: PROCESSES                             │   │
│  │  End-to-end workflows with clear triggers, inputs, and outputs  │   │
│  └─────────────────────────────────────────────────────────────────┘   │
│                              ▼                                          │
│  ┌─────────────────────────────────────────────────────────────────┐   │
│  │                      L4: PROCEDURES                              │   │
│  │  Detailed step-by-step instructions for task execution          │   │
│  └─────────────────────────────────────────────────────────────────┘   │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

### 2.2 L1: Process Categories

#### 2.2.1 Farm Operations (L1.1)
Primary processes related to dairy farm management, animal care, and milk production.

**Scope**: All activities from animal care through raw milk collection
**Owner**: Farm Operations Manager
**Objectives**:
- Maintain herd health and productivity
- Ensure optimal milk quality at source
- Comply with animal welfare standards
- Maximize feed conversion efficiency

#### 2.2.2 Sales & Distribution (L1.2)
Processes covering customer acquisition, order management, and product delivery.

**Scope**: From customer inquiry to successful delivery and payment
**Owner**: Sales & Marketing Manager
**Objectives**:
- Maximize customer satisfaction
- Optimize order fulfillment rate
- Minimize delivery time and cost
- Build long-term customer relationships

#### 2.2.3 Supply Chain (L1.3)
Processes for procurement, inventory management, and quality control.

**Scope**: From supplier selection through quality assurance to warehouse operations
**Owner**: Supply Chain Manager
**Objectives**:
- Ensure continuous supply of quality inputs
- Optimize inventory levels
- Maintain cold chain integrity
- Minimize procurement costs

#### 2.2.4 Finance & Administration (L1.4)
Financial processes including accounting, payroll, and reporting.

**Scope**: All financial transactions, reporting, and compliance activities
**Owner**: Finance Manager
**Objectives**:
- Maintain accurate financial records
- Ensure timely payments and collections
- Provide management with financial insights
- Ensure regulatory compliance

### 2.3 L2: Process Groups

#### 2.3.1 Farm Operations Process Groups

| Process Group | Code | Description | Owner |
|---------------|------|-------------|-------|
| Milk Production | L1.1-P01 | Core milk production and collection | Farm Supervisor |
| Animal Health Management | L1.1-P02 | Veterinary care and health monitoring | Veterinary Officer |
| Breeding Management | L1.1-P03 | Reproduction and genetics | Breeding Manager |
| Feed Management | L1.1-P04 | Nutrition and feeding programs | Nutritionist |
| Farm Maintenance | L1.1-P05 | Infrastructure and equipment upkeep | Farm Supervisor |

#### 2.3.2 Sales & Distribution Process Groups

| Process Group | Code | Description | Owner |
|---------------|------|-------------|-------|
| B2C Order Management | L1.2-P01 | Consumer order processing | Customer Service Lead |
| B2B Order Management | L1.2-P02 | Business customer orders | Key Account Manager |
| Subscription Management | L1.2-P03 | Recurring delivery services | Subscription Manager |
| Delivery Operations | L1.2-P04 | Logistics and distribution | Logistics Manager |
| Customer Service | L1.2-P05 | Support and issue resolution | Customer Service Manager |

#### 2.3.3 Supply Chain Process Groups

| Process Group | Code | Description | Owner |
|---------------|------|-------------|-------|
| Procurement | L1.3-P01 | Supplier and purchasing management | Procurement Manager |
| Inventory Management | L1.3-P02 | Stock control and warehousing | Warehouse Manager |
| Quality Control | L1.3-P03 | Testing and quality assurance | Quality Manager |
| Cold Chain Management | L1.3-P04 | Temperature-controlled logistics | Cold Chain Manager |

#### 2.3.4 Finance Process Groups

| Process Group | Code | Description | Owner |
|---------------|------|-------------|-------|
| Accounts Payable | L1.4-P01 | Supplier payments | Accounts Payable Lead |
| Accounts Receivable | L1.4-P02 | Customer collections | Accounts Receivable Lead |
| Payroll Processing | L1.4-P03 | Employee compensation | Payroll Manager |
| Financial Reporting | L1.4-P04 | Management and statutory reports | Finance Manager |
| Budget Management | L1.4-P05 | Planning and variance analysis | Finance Manager |

### 2.4 L3: Process Catalog Summary

| Category | Process Code | Process Name | Process Owner |
|----------|--------------|--------------|---------------|
| Farm Operations | FO-001 | Daily Milk Production & Collection | Farm Supervisor |
| Farm Operations | FO-002 | Milking Operations | Head Milker |
| Farm Operations | FO-003 | Animal Health Check & Treatment | Veterinary Officer |
| Farm Operations | FO-004 | Vaccination Program | Veterinary Officer |
| Farm Operations | FO-005 | Breeding Cycle Management | Breeding Manager |
| Farm Operations | FO-006 | Calving Management | Farm Supervisor |
| Farm Operations | FO-007 | Feed Planning & Preparation | Nutritionist |
| Farm Operations | FO-008 | Pasture Management | Farm Supervisor |
| Sales & Distribution | SD-001 | B2C Order-to-Cash | Customer Service Lead |
| Sales & Distribution | SD-002 | B2B Order-to-Cash | Key Account Manager |
| Sales & Distribution | SD-003 | Subscription Enrollment & Management | Subscription Manager |
| Sales & Distribution | SD-004 | Daily Delivery Route Planning | Logistics Manager |
| Sales & Distribution | SD-005 | Last-Mile Delivery Execution | Delivery Supervisor |
| Sales & Distribution | SD-006 | Customer Complaint Handling | Customer Service Manager |
| Supply Chain | SC-001 | Supplier Selection & Onboarding | Procurement Manager |
| Supply Chain | SC-002 | Purchase Requisition-to-Order | Procurement Officer |
| Supply Chain | SC-003 | Goods Receipt & Inspection | Warehouse Supervisor |
| Supply Chain | SC-004 | Inventory Count & Reconciliation | Inventory Controller |
| Supply Chain | SC-005 | Cold Chain Monitoring | Cold Chain Manager |
| Supply Chain | SC-006 | Quality Testing & Release | Quality Manager |
| Finance | FI-001 | Invoice Processing & Payment | AP Lead |
| Finance | FI-002 | Customer Billing & Collection | AR Lead |
| Finance | FI-003 | Monthly Payroll Processing | Payroll Manager |
| Finance | FI-004 | Monthly Financial Close | Finance Manager |
| Finance | FI-005 | Budget Preparation & Monitoring | Finance Manager |

### 2.5 L4: Procedure Inventory

Each L3 process decomposes into multiple L4 procedures. Example decomposition for FO-001:

**FO-001: Daily Milk Production & Collection**
- FO-001-P01: Pre-milking preparation
- FO-001-P02: Milking machine setup
- FO-001-P03: Milking execution
- FO-001-P04: Post-milking teat care
- FO-001-P05: Milk quality testing
- FO-001-P06: Milk collection and cooling
- FO-001-P07: Equipment cleaning and sanitation

---

## 3. Core Business Processes

### 3.1 Farm Operations

#### 3.1.1 Process: FO-001 - Daily Milk Production & Collection

**Process Overview**

| Attribute | Details |
|-----------|---------|
| **Process ID** | FO-001 |
| **Process Name** | Daily Milk Production & Collection |
| **Process Owner** | Farm Supervisor |
| **Process Category** | Farm Operations |
| **Frequency** | Daily (2-3 times per day) |
| **Duration** | 4-6 hours per milking session |
| **Trigger** | Scheduled milking time or animal need |
| **Primary Output** | Quality-tested raw milk ready for processing |

**Process Description**

The Daily Milk Production & Collection process encompasses all activities from preparing animals for milking through cooling and storing the collected milk. This process is critical for maintaining milk quality and ensuring food safety standards are met.

**Swimlane Diagram (Text Representation)**

```
┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                        FO-001: DAILY MILK PRODUCTION & COLLECTION                        │
├─────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                         │
│  ┌──────────────┐   ┌──────────────┐   ┌──────────────┐   ┌──────────────┐             │
│  │    COWS      │   │   MILKERS    │   │   QUALITY    │   │   COOLING    │             │
│  │              │   │              │   │    LAB       │   │   SYSTEM     │             │
│  └──────┬───────┘   └──────┬───────┘   └──────┬───────┘   └──────┬───────┘             │
│         │                  │                  │                  │                     │
│         │  Enter milking   │                  │                  │                     │
│         │  parlor          │                  │                  │                     │
│         │─────────────────>│                  │                  │                     │
│         │                  │                  │                  │                     │
│         │                  │  Pre-milking     │                  │                     │
│         │                  │  teat prep       │                  │                     │
│         │<─────────────────│                  │                  │                     │
│         │                  │                  │                  │                     │
│         │  Fore-milk       │                  │                  │                     │
│         │  stripping       │                  │                  │                     │
│         │─────────────────>│                  │                  │                     │
│         │                  │                  │                  │                     │
│         │                  │  Attach milking  │                  │                     │
│         │                  │  clusters        │                  │                     │
│         │<─────────────────│                  │                  │                     │
│         │                  │                  │                  │                     │
│         │  Milk flow       │                  │                  │                     │
│         │  to collection   │                  │                  │                     │
│         │─────────────────>│─────────────────>│                  │                     │
│         │                  │                  │  Sample for      │                     │
│         │                  │                  │  quality test    │                     │
│         │                  │                  ├─────────────────>│                     │
│         │                  │                  │                  │                     │
│         │                  │                  │  Pass/Fail       │                     │
│         │                  │                  │  decision        │                     │
│         │                  │                  │<─────────────────│                     │
│         │                  │                  │                  │                     │
│         │                  │                  │  Transfer to     │                     │
│         │                  │                  │  bulk tank       │                     │
│         │                  │                  ├─────────────────>│                     │
│         │                  │                  │                  │                     │
│         │                  │                  │                  │  Rapid cooling      │
│         │                  │                  │                  │  to 4°C             │
│         │                  │                  │                  │                     │
│         │                  │  Post-milking    │                  │                     │
│         │                  │  teat dip        │                  │                     │
│         │<─────────────────│                  │                  │                     │
│         │                  │                  │                  │                     │
│         │  Return to       │                  │                  │                     │
│         │  housing         │                  │                  │                     │
│         │                  │                  │                  │                     │
│  └──────┴───────┘   └──────┴───────┘   └──────┴───────┘   └──────┴───────┘             │
│                                                                                         │
└─────────────────────────────────────────────────────────────────────────────────────────┘
```

**BPMN 2.0 Flowchart (Text Description)**

```
[Start Event: Milking Time] 
    ↓
[Task: Prepare milking parlor] → [Task: Sanitize equipment]
    ↓
[Task: Bring cows to parlor]
    ↓
<Gateway: All cows present?> ──No──→ [Task: Locate missing cows] → [Task: Bring cows to parlor]
    ↓ Yes
[Task: Pre-milking teat preparation]
    ↓
[Task: Forestrip each teat]
    ↓
<Gateway: Abnormal milk detected?> ──Yes──→ [Task: Segregate cow] → [Task: Flag for veterinary check]
    ↓ No                                                          ↓
[Task: Attach milking clusters]                              [End Event: Cow excluded]
    ↓
[Task: Monitor milking process]
    ↓
<Gateway: Milk flow complete?> ──No──→ [Task: Continue monitoring]
    ↓ Yes
[Task: Remove clusters]
    ↓
[Task: Apply post-milking teat dip]
    ↓
[Task: Release cow]
    ↓
[Task: Collect milk in bulk tank]
    ↓
[Task: Take quality sample]
    ↓
[Task: Test for antibiotics, bacteria, somatic cells]
    ↓
<Gateway: Quality passed?> ──No──→ [Task: Reject milk] → [Task: Investigate cause] → [End Event: Milk rejected]
    ↓ Yes
[Task: Cool milk to 4°C]
    ↓
[Task: Record production data]
    ↓
[Task: Clean and sanitize equipment]
    ↓
[End Event: Milk ready for processing]
```

**Step-by-Step Description**

| Step | Activity | Role | System/Tool | Duration | Input | Output |
|------|----------|------|-------------|----------|-------|--------|
| 1 | Prepare milking parlor | Farm Supervisor | Checklist | 15 min | Schedule | Ready parlor |
| 2 | Sanitize equipment | Milker | CIP system | 20 min | Water, chemicals | Sanitized equipment |
| 3 | Bring cows to parlor | Herdsman | Herding tools | 30 min | Herd list | Cows in parlor |
| 4 | Pre-milking teat prep | Milker | Cleaning solution | 2 min/cow | Soiled teats | Clean teats |
| 5 | Forestrip milk | Milker | Strip cup | 30 sec/cow | - | First milk tested |
| 6 | Attach milking clusters | Milker | Milking machine | 1 min/cow | Prepared cow | Milking in progress |
| 7 | Monitor milking | Milker | Flow sensors | Variable | Real-time data | Complete milking |
| 8 | Remove clusters | Milker | Auto-remover | 30 sec/cow | Empty udder | Cows milked |
| 9 | Post-milking teat dip | Milker | Teat dip cup | 30 sec/cow | - | Protected teats |
| 10 | Release cows | Herdsman | Gates | 15 min | - | Cows returned |
| 11 | Collect milk sample | Quality Tech | Sterile container | 5 min | Bulk tank milk | Lab sample |
| 12 | Quality testing | Lab Technician | Testing equipment | 30 min | Sample | Test results |
| 13 | Cool milk | Cooling System | Refrigeration | Continuous | Warm milk | Chilled milk |
| 14 | Record production | Farm Supervisor | Farm Management System | 10 min | Volume data | Production record |
| 15 | CIP cleaning | CIP System | Automated | 45 min | - | Clean equipment |

**Roles and Responsibilities**

| Role | Responsibilities | Skills Required |
|------|------------------|-----------------|
| Farm Supervisor | Overall coordination, troubleshooting, record keeping | Leadership, dairy management, system operation |
| Head Milker | Direct milking operations, quality oversight | Milking expertise, equipment operation |
| Milker | Execute milking procedures, basic maintenance | Animal handling, milking technique |
| Herdsman | Animal movement, identification, basic health observation | Animal behavior, herding skills |
| Quality Technician | Sample collection, basic testing, documentation | Lab techniques, attention to detail |
| Lab Technician | Detailed quality analysis, reporting | Microbiology, chemistry, lab procedures |

**Systems Used**

| System | Purpose | Integration |
|--------|---------|-------------|
| Farm Management System (FMS) | Record keeping, herd management | ERP, milking equipment |
| Milking Parlor Control System | Automated milking control | FMS, cooling system |
| Cooling System Control | Temperature management | FMS, SCADA |
| Quality Testing Equipment | Lab analysis | FMS, LIMS |
| RFID/Cow Identification | Animal tracking | FMS, access control |

**Key Performance Indicators (KPIs)**

| KPI | Target | Measurement | Frequency |
|-----|--------|-------------|-----------|
| Milk Yield per Cow | ≥ 30 liters/day | Volume ÷ cow count | Daily |
| Somatic Cell Count | < 200,000 cells/ml | Lab testing | Daily |
| Bacterial Count | < 20,000 CFU/ml | Lab testing | Daily |
| Antibiotic Residue | 0% positive | Screening tests | Every collection |
| Milk Temperature | ≤ 4°C within 2 hours | Sensor monitoring | Continuous |
| Milking Time | < 8 minutes/cow | Time tracking | Per session |
| Equipment Uptime | > 98% | Maintenance logs | Weekly |
| Mastitis Detection Rate | 100% of clinical cases | Health records | Monthly |

---

#### 3.1.2 Process: FO-003 - Animal Health Check & Treatment

**Process Overview**

| Attribute | Details |
|-----------|---------|
| **Process ID** | FO-003 |
| **Process Name** | Animal Health Check & Treatment |
| **Process Owner** | Veterinary Officer |
| **Process Category** | Farm Operations |
| **Frequency** | Daily (routine), As needed (treatment) |
| **Trigger** | Scheduled check, observed symptoms, alert from system |
| **Primary Output** | Healthy animals, treatment records |

**Swimlane Diagram (Text Representation)**

```
┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                     FO-003: ANIMAL HEALTH CHECK & TREATMENT                              │
├─────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                         │
│  ┌──────────────┐   ┌──────────────┐   ┌──────────────┐   ┌──────────────┐             │
│  │   HERDSMAN   │   │    COW       │   │  VETERINARY  │   │    FARM      │             │
│  │              │   │              │   │   OFFICER    │   │ SUPERVISOR   │             │
│  └──────┬───────┘   └──────┬───────┘   └──────┬───────┘   └──────┬───────┘             │
│         │                  │                  │                  │                     │
│         │  Observe         │                  │                  │                     │
│         │  abnormal        │                  │                  │                     │
│         │  behavior        │                  │                  │                     │
│         │─────────────────>│                  │                  │                     │
│         │                  │  Isolate         │                  │                     │
│         │                  │  suspect         │                  │                     │
│         │                  │  animal          │                  │                     │
│         │─────────────────>│─────────────────>│                  │                     │
│         │                  │                  │  Clinical        │                     │
│         │                  │                  │  examination     │                     │
│         │                  │                  │                  │                     │
│         │                  │                  │<─────────────────│                     │
│         │                  │                  │  Treatment plan  │                     │
│         │                  │                  ├─────────────────>│                     │
│         │                  │                  │                  │                     │
│         │                  │                  │  Administer      │                     │
│         │                  │                  │  treatment       │                     │
│         │<─────────────────│<─────────────────│                  │                     │
│         │                  │                  │                  │                     │
│         │  Monitor         │                  │                  │                     │
│         │  recovery        │                  │                  │                     │
│         │─────────────────>│                  │                  │                     │
│         │                  │  Record          │                  │                     │
│         │                  │  treatment       │                  │                     │
│         │                  │  in FMS          │                  │                     │
│         │─────────────────>├─────────────────>├─────────────────>│                     │
│         │                  │                  │                  │                     │
│  └──────┴───────┘   └──────┴───────┘   └──────┴───────┘   └──────┴───────┘             │
│                                                                                         │
└─────────────────────────────────────────────────────────────────────────────────────────┘
```

**Step-by-Step Description**

| Step | Activity | Role | Input | Output |
|------|----------|------|-------|--------|
| 1 | Daily visual inspection | Herdsman | Herd in housing | Observation notes |
| 2 | Identify abnormal animals | Herdsman | Visual cues | Flagged animals |
| 3 | Isolate suspect animal | Herdsman | Flagged cow | Isolated cow |
| 4 | Record symptoms in FMS | Herdsman | Observations | Health alert |
| 5 | Clinical examination | Veterinary Officer | Alert, isolated cow | Diagnosis |
| 6 | Order diagnostic tests if needed | Veterinary Officer | Suspected condition | Lab request |
| 7 | Review test results | Veterinary Officer | Lab report | Confirmed diagnosis |
| 8 | Develop treatment plan | Veterinary Officer | Diagnosis | Treatment protocol |
| 9 | Approve treatment | Farm Supervisor | Treatment plan | Authorization |
| 10 | Prepare medications | Veterinary Officer | Prescription | Ready treatment |
| 11 | Administer treatment | Veterinary Officer/Herdsman | Medications | Treated animal |
| 12 | Record treatment in FMS | Veterinary Officer | Treatment details | Medical record |
| 13 | Set withdrawal period | FMS | Medication type | Withdrawal alert |
| 14 | Monitor recovery | Herdsman | Treated cow | Progress notes |
| 15 | Follow-up examination | Veterinary Officer | Progress notes | Release decision |
| 16 | Return to herd or continue treatment | Herdsman | Vet decision | Resolved/ongoing |

**KPIs**

| KPI | Target | Measurement |
|-----|--------|-------------|
| Treatment Response Time | < 4 hours from detection | Time log |
| Recovery Rate | > 95% within protocol timeframe | Health records |
| Mortality Rate | < 2% annually | Death records |
| Antibiotic Use | Trending downward | Treatment logs |
| Average Days to Recovery | By condition type | Health records |

---

#### 3.1.3 Process: FO-005 - Breeding Cycle Management

**Process Overview**

| Attribute | Details |
|-----------|---------|
| **Process ID** | FO-005 |
| **Process Name** | Breeding Cycle Management |
| **Process Owner** | Breeding Manager |
| **Frequency** | Continuous (breeding season peaks) |
| **Trigger** | Heat detection, scheduled AI, vet recommendation |
| **Primary Output** | Successful pregnancies, genetic improvement |

**BPMN Flowchart Description**

```
[Start: Heat Detected or Breeding Schedule] 
    ↓
[Task: Confirm heat signs] → <Gateway: Heat confirmed?> 
    ↓ Yes
[Task: Check breeding eligibility] → <Gateway: Eligible?> 
    ↓ Yes
[Task: Select semen/straw] → [Task: Record breeding decision]
    ↓
[Task: Schedule AI or natural breeding]
    ↓
[Task: Perform insemination/mating]
    ↓
[Task: Record breeding in FMS]
    ↓
[Task: Schedule pregnancy check]
    ↓
[Task: Pregnancy examination (30-45 days)]
    ↓
<Gateway: Pregnant?> 
    ├── Yes → [Task: Calculate due date] → [Task: Schedule pre-calving care] → [End: Confirmed pregnancy]
    └── No → [Task: Analyze reason] → [Task: Plan rebreeding] → [End: Rescheduled]
```

**KPIs**

| KPI | Target | Frequency |
|-----|--------|-----------|
| Conception Rate | > 45% | Per breeding cycle |
| Days Open | < 120 days average | Monthly |
| Calving Interval | 12-14 months | Per cow |
| AI Success Rate | > 40% first service | Monthly |
| Genetic Progress | Positive trend annually | Annual |

---

#### 3.1.4 Process: FO-007 - Feed Management

**Process Overview**

| Attribute | Details |
|-----------|---------|
| **Process ID** | FO-007 |
| **Process Name** | Feed Planning & Preparation |
| **Process Owner** | Nutritionist |
| **Frequency** | Daily feeding, Weekly planning, Monthly formulation |
| **Trigger** | Feeding schedule, inventory levels, production targets |
| **Primary Output** | Balanced rations, optimal feed conversion |

**Process Steps**

1. **Nutritional Assessment**: Analyze cow requirements by production stage
2. **Feed Inventory Check**: Verify available feed stocks
3. **Ration Formulation**: Calculate optimal mix using ration software
4. **Feed Preparation**: Mix ingredients according to formulation
5. **Feed Distribution**: Deliver to feeding areas by schedule
6. **Feed Monitoring**: Track intake and refusals
7. **Adjustment**: Modify rations based on performance

**KPIs**

| KPI | Target |
|-----|--------|
| Feed Conversion Ratio | < 1.5 kg feed per kg milk |
| Feed Cost per Liter | Trending downward |
| Ration Accuracy | > 95% match to formulation |
| Feed Refusal Rate | < 5% |

---

### 3.2 Sales & Distribution

#### 3.2.1 Process: SD-001 - B2C Order-to-Cash

**Process Overview**

| Attribute | Details |
|-----------|---------|
| **Process ID** | SD-001 |
| **Process Name** | B2C Order-to-Cash |
| **Process Owner** | Customer Service Lead |
| **Process Category** | Sales & Distribution |
| **Frequency** | Continuous (business hours) |
| **Trigger** | Customer places order via web/app/phone |
| **Primary Output** | Fulfilled order, collected payment, satisfied customer |

**Swimlane Diagram (Text Representation)**

```
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│                           SD-001: B2C ORDER-TO-CASH                                        │
├─────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                             │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐        │
│  │ CUSTOMER │  │  WEB/    │  │ CUSTOMER │  │ WAREHOUSE│  │ DELIVERY │  │ FINANCE  │        │
│  │          │  │  MOBILE  │  │ SERVICE  │  │          │  │          │  │          │        │
│  └────┬─────┘  └────┬─────┘  └────┬─────┘  └────┬─────┘  └────┬─────┘  └────┬─────┘        │
│       │             │             │             │             │             │              │
│       │ Place Order │             │             │             │             │              │
│       │────────────>│             │             │             │             │              │
│       │             │ Validate    │             │             │             │              │
│       │             │ Order       │             │             │             │              │
│       │             │────────────>│             │             │             │              │
│       │             │             │ Check       │             │             │              │
│       │             │             │ Inventory   │             │             │              │
│       │             │             │────────────>│             │             │              │
│       │             │             │ Confirm     │             │             │              │
│       │             │             │ Stock       │             │             │              │
│       │             │             │<────────────│             │             │              │
│       │             │ Order       │             │             │             │              │
│       │             │ Confirmed   │             │             │             │              │
│       │<────────────│             │             │             │             │              │
│       │ Payment     │             │             │             │             │              │
│       │────────────>│             │             │             │             │              │
│       │             │             │             │ Pick & Pack │             │              │
│       │             │             │────────────>│             │             │              │
│       │             │             │             │             │ Route &     │              │
│       │             │             │             │             │ Deliver     │              │
│       │             │             │             │────────────>│             │              │
│       │             │             │             │             │ Delivery    │              │
│       │             │             │             │             │ Complete    │              │
│       │             │             │             │<────────────│             │              │
│       │             │             │             │             │ Invoice     │              │
│       │             │             │             │             │────────────>│              │
│       │             │             │             │             │             │ Payment      │
│       │             │             │             │             │             │ Processed    │
│       │<────────────<────────────<────────────<────────────<────────────│              │
│       │             │             │             │             │             │              │
│  └────┴─────┘  └────┴─────┘  └────┴─────┘  └────┴─────┘  └────┴─────┘  └────┴─────┘        │
│                                                                                             │
└─────────────────────────────────────────────────────────────────────────────────────────────┘
```

**Step-by-Step Description**

| Step | Activity | Role | System | Input | Output |
|------|----------|------|--------|-------|--------|
| 1 | Customer places order | Customer | Web/Mobile App | Product selection | Order request |
| 2 | Validate customer data | System | ERP/CRM | Order request | Validated order |
| 3 | Check credit/credit limit | System | ERP | Customer ID | Credit approval |
| 4 | Verify product availability | System | Inventory Module | Product SKU | Stock confirmation |
| 5 | Calculate delivery options | System | Delivery Module | Address | Delivery options |
| 6 | Present order summary | System | E-commerce | Calculations | Order summary |
| 7 | Customer confirms order | Customer | Web/Mobile | Summary | Confirmed order |
| 8 | Process payment | System | Payment Gateway | Payment details | Payment confirmation |
| 9 | Generate order confirmation | System | ERP | Order data | Confirmation email/SMS |
| 10 | Allocate inventory | System | WMS | Order line items | Reserved stock |
| 11 | Create picking list | Warehouse System | WMS | Allocated items | Pick list |
| 12 | Pick and pack order | Warehouse Staff | WMS | Pick list | Packed order |
| 13 | Generate delivery note | System | WMS/DMS | Packed order | Delivery documentation |
| 14 | Assign to delivery route | Dispatcher | DMS | Delivery address | Route assignment |
| 15 | Load vehicle | Delivery Staff | DMS | Route manifest | Loaded vehicle |
| 16 | Deliver to customer | Driver | Mobile App | Route | Delivered products |
| 17 | Collect proof of delivery | Driver | Mobile App | Delivery | POD signature/photo |
| 18 | Update order status | System | ERP | POD | Completed order |
| 19 | Generate invoice | System | ERP | Delivery confirmation | Invoice |
| 20 | Send invoice to customer | System | ERP/Email | Invoice | Invoice delivered |
| 21 | Process payment (if COD) | Finance | ERP | Cash/check | Payment recorded |
| 22 | Reconcile payment | Finance | ERP | Bank statement | Closed transaction |

**KPIs**

| KPI | Target | Measurement |
|-----|--------|-------------|
| Order Accuracy | > 99% | Error rate tracking |
| Order Cycle Time | < 24 hours | Order to delivery |
| On-Time Delivery | > 95% | Schedule adherence |
| Customer Satisfaction | > 4.5/5 | Post-delivery survey |
| Payment Collection | < 30 days average | DSO calculation |

---

#### 3.2.2 Process: SD-002 - B2B Order-to-Cash

**Process Overview**

| Attribute | Details |
|-----------|---------|
| **Process ID** | SD-002 |
| **Process Name** | B2B Order-to-Cash |
| **Process Owner** | Key Account Manager |
| **Trigger** | Purchase order from business customer |
| **Primary Output** | Fulfilled B2B order, invoice, payment |

**Key Differences from B2C**:
- Credit terms and approval required
- Volume discounts applied
- Contract pricing enforcement
- Bulk delivery scheduling
- Longer payment terms (30-60 days)
- Relationship management focus

**Process Flow**

```
[Receive PO from Customer]
    ↓
[Verify PO against Contract] → <Valid PO?> 
    ↓ Yes
[Check Credit Limit] → <Credit Available?> 
    ↓ Yes
[Confirm Order with Customer]
    ↓
[Schedule Production/Allocation]
    ↓
[Prepare Delivery Schedule]
    ↓
[Execute Delivery]
    ↓
[Submit Invoice per Terms]
    ↓
[Monitor Payment] → <Payment Received?> 
    ↓ Yes                                    ↓ No
[Reconcile & Close]               [Send Reminder] → [Escalate if overdue]
```

**KPIs**

| KPI | Target |
|-----|--------|
| B2B Order Accuracy | > 98% |
| On-Time-In-Full (OTIF) | > 95% |
| DSO (Days Sales Outstanding) | < 45 days |
| Customer Retention | > 90% annually |

---

#### 3.2.3 Process: SD-003 - Subscription Management

**Process Overview**

| Attribute | Details |
|-----------|---------|
| **Process ID** | SD-003 |
| **Process Name** | Subscription Enrollment & Management |
| **Process Owner** | Subscription Manager |
| **Trigger** | Customer subscription request or system schedule |
| **Primary Output** | Active subscription, recurring revenue |

**Process Flow**

```
[Customer Requests Subscription]
    ↓
[Present Subscription Plans]
    ↓
[Customer Selects Plan]
    ↓
[Configure Delivery Schedule]
    ↓
[Set Payment Method]
    ↓
[Process Initial Payment]
    ↓
[Activate Subscription]
    ↓
[System Generates Recurring Orders]
    ↓
[Execute Regular Deliveries]
    ↓
[Process Recurring Payments]
    ↓
[Monitor Subscription Health] → <Changes Requested?> 
    ↓ Yes
[Process Modification/Pause/Cancellation]
```

**KPIs**

| KPI | Target |
|-----|--------|
| Monthly Recurring Revenue (MRR) | Growth 10% monthly |
| Churn Rate | < 5% monthly |
| Customer Lifetime Value | Trending up |
| Subscription Uptime | 99.9% |

---

#### 3.2.4 Process: SD-004 - Delivery Management

**Process Overview**

| Attribute | Details |
|-----------|---------|
| **Process ID** | SD-004 |
| **Process Name** | Daily Delivery Route Planning & Execution |
| **Process Owner** | Logistics Manager |
| **Trigger** | Cut-off time for orders, daily schedule |
| **Primary Output** | Optimized delivery routes, on-time deliveries |

**Process Flow**

```
[Order Cut-off Time Reached]
    ↓
[Consolidate All Orders by Zone]
    ↓
[Check Vehicle Availability]
    ↓
[Optimize Routes (algorithm)]
    ↓
[Assign Drivers to Routes]
    ↓
[Prepare Load Sheets]
    ↓
[Load Vehicles (cold chain maintained)]
    ↓
[Execute Deliveries]
    ↓
[Real-time Tracking] → [Handle Exceptions]
    ↓
[Collect Proof of Delivery]
    ↓
[Return Undelivered Items]
    ↓
[Complete Route Reconciliation]
```

**KPIs**

| KPI | Target |
|-----|--------|
| On-Time Delivery Rate | > 95% |
| Delivery Cost per Order | < Target budget |
| Route Efficiency | > 85% capacity utilization |
| Failed Delivery Rate | < 2% |
| Cold Chain Breaches | 0 |

---

### 3.3 Supply Chain

#### 3.3.1 Process: SC-001 - Procurement-to-Pay

**Process Overview**

| Attribute | Details |
|-----------|---------|
| **Process ID** | SC-001 |
| **Process Name** | Procurement-to-Pay |
| **Process Owner** | Procurement Manager |
| **Process Category** | Supply Chain |
| **Frequency** | Continuous |
| **Trigger** | Inventory reorder point, planned purchase, emergency need |
| **Primary Output** | Procured goods/services, supplier payment |

**Swimlane Diagram (Text Representation)**

```
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│                          SC-001: PROCUREMENT-TO-PAY                                        │
├─────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                             │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐        │
│  │ REQUESTOR│  │PROCUREMENT│  │SUPPLIER  │  │ WAREHOUSE│  │ QUALITY  │  │ FINANCE  │        │
│  │          │  │          │  │          │  │          │  │          │  │          │        │
│  └────┬─────┘  └────┬─────┘  └────┬─────┘  └────┬─────┘  └────┬─────┘  └────┬─────┘        │
│       │             │             │             │             │             │              │
│       │ Create PR   │             │             │             │             │              │
│       │────────────>│             │             │             │             │              │
│       │             │ Approve PR  │             │             │             │              │
│       │             │────────────>│             │             │             │              │
│       │             │ Issue PO    │             │             │             │              │
│       │             │────────────>│             │             │             │              │
│       │             │             │ Deliver     │             │             │              │
│       │             │             │────────────>│             │             │              │
│       │             │             │             │ Receive     │             │              │
│       │             │             │             ├────────────>│             │              │
│       │             │             │             │             │ Inspect     │              │
│       │             │             │             │<────────────│             │              │
│       │             │             │             │ Accept/     │             │              │
│       │             │             │             │ Reject      │             │              │
│       │             │             │             ├──────────────────────────>│              │
│       │             │             │             │             │             │ Match GR/PO  │
│       │             │             │             │             │             │ Invoice      │
│       │             │             │             │             │             │ Schedule Pay │
│       │<────────────<────────────<────────────<────────────<────────────│              │
│       │             │             │             │             │             │              │
│  └────┴─────┘  └────┴─────┘  └────┴─────┘  └────┴─────┘  └────┴─────┘  └────┴─────┘        │
│                                                                                             │
└─────────────────────────────────────────────────────────────────────────────────────────────┘
```

**Step-by-Step Description**

| Step | Activity | Role | System | Input | Output |
|------|----------|------|--------|-------|--------|
| 1 | Identify need | Requestor | Various | Stock level/plan | Purchase request |
| 2 | Create Purchase Requisition (PR) | Requestor | ERP | Need details | PR document |
| 3 | Route for approval | System | ERP/Workflow | PR | Approval workflow |
| 4 | Review and approve PR | Approver | ERP | PR | Approved PR |
| 5 | Select supplier | Procurement | ERP/SRM | Approved PR | Supplier selected |
| 6 | Request quotations (if needed) | Procurement | Email/Portal | RFQ | Quotations |
| 7 | Evaluate and compare | Procurement | Spreadsheet/ERP | Quotations | Evaluation |
| 8 | Create Purchase Order (PO) | Procurement | ERP | Selected supplier | PO document |
| 9 | Send PO to supplier | System | ERP/Email | PO | PO sent |
| 10 | Acknowledge PO | Supplier | Various | PO | Confirmation |
| 11 | Process delivery | Supplier | - | PO | Shipment |
| 12 | Receive goods | Warehouse | WMS | Delivery | GRN draft |
| 13 | Quality inspection | Quality | QMS | Samples | Inspection report |
| 14 | Accept or reject goods | Quality | QMS | Report | Decision |
| 15 | Complete Goods Receipt Note | Warehouse | WMS | Inspection result | GRN |
| 16 | Update inventory | System | ERP/WMS | GRN | Stock updated |
| 17 | Receive supplier invoice | Finance | ERP | Invoice | Invoice record |
| 18 | Match invoice to GRN and PO | Finance | ERP | Documents | Matched invoice |
| 19 | Verify and approve for payment | Finance | ERP | Matched invoice | Payment approval |
| 20 | Process payment | Finance | Banking | Approval | Payment |
| 21 | Reconcile accounts | Finance | ERP | Payment record | Closed transaction |

**KPIs**

| KPI | Target |
|-----|--------|
| Purchase Order Accuracy | > 98% |
| Supplier On-Time Delivery | > 95% |
| Purchase Price Variance | < 2% |
| Procure-to-Pay Cycle Time | < 5 days (excluding lead time) |
| Supplier Quality Acceptance | > 98% |

---

#### 3.3.2 Process: SC-004 - Inventory Management

**Process Overview**

| Attribute | Details |
|-----------|---------|
| **Process ID** | SC-004 |
| **Process Name** | Inventory Count & Reconciliation |
| **Process Owner** | Warehouse Manager |
| **Trigger** | Scheduled count, discrepancy alert, period-end |
| **Primary Output** | Accurate inventory records |

**Process Flow**

```
[Schedule Physical Count]
    ↓
[Prepare Count Sheets/System] → [Freeze Transactions]
    ↓
[Execute Physical Count]
    ↓
[Enter Count Results]
    ↓
[System Compares: Physical vs. System]
    ↓
<Discrepancies?> 
    ├── No → [Confirm Accuracy] → [Unfreeze & Resume]
    └── Yes → [Investigate Discrepancies] → [Adjust Records] → [Document Reasons]
```

**KPIs**

| KPI | Target |
|-----|--------|
| Inventory Accuracy | > 99% |
| Stockout Rate | < 1% |
| Inventory Turnover | > 12x annually |
| Shrinkage Rate | < 0.5% |

---

#### 3.3.3 Process: SC-005 - Cold Chain Management

**Process Overview**

| Attribute | Details |
|-----------|---------|
| **Process ID** | SC-005 |
| **Process Name** | Cold Chain Management |
| **Process Owner** | Cold Chain Manager |
| **Trigger** | Product entering cold chain, temperature alert |
| **Primary Output** | Temperature-controlled product integrity |

**Process Flow**

```
[Product Receives at Cold Storage]
    ↓
[Record Initial Temperature]
    ↓
[Place in Designated Temperature Zone]
    ↓
[IoT Sensors Monitor Continuously]
    ↓
<Temperature Breach Detected?> 
    ├── No → [Continue Monitoring] → [Log Data]
    └── Yes → [Alert Triggered] → [Immediate Response] → [Document Incident] → [Quarantine if Needed]
    ↓
[Prepare for Dispatch]
    ↓
[Pre-cool Transport Vehicle]
    ↓
[Load with Temperature Logging]
    ↓
[Transit Monitoring via GPS/IoT]
    ↓
[Delivery with Temperature Record]
    ↓
[Customer Receipt Verification]
```

**KPIs**

| KPI | Target |
|-----|--------|
| Cold Chain Breaches | 0 |
| Temperature Compliance | 100% |
| Response Time to Alerts | < 5 minutes |
| Cold Storage Utilization | 75-85% |

---

#### 3.3.4 Process: SC-006 - Quality Control

**Process Overview**

| Attribute | Details |
|-----------|---------|
| **Process ID** | SC-006 |
| **Process Name** | Quality Testing & Release |
| **Process Owner** | Quality Manager |
| **Trigger** | Sample collected, production batch complete |
| **Primary Output** | Quality approved/rejected products |

**Process Flow**

```
[Collect Representative Sample]
    ↓
[Label and Track Sample]
    ↓
[Conduct Required Tests]:
    ├── Microbiological (bacteria count)
    ├── Chemical (fat, protein, antibiotics)
    ├── Physical (temperature, appearance)
    └── Sensory (taste, smell)
    ↓
[Record Test Results]
    ↓
<All Parameters Within Spec?> 
    ├── Yes → [Approve for Release] → [Update Certificate] → [Release Product]
    └── No → [Quarantine Product] → [Investigate Cause] → [Disposition: Reprocess/Reject] → [Document CAPA]
```

**KPIs**

| KPI | Target |
|-----|--------|
| Test Accuracy | 100% (no false negatives) |
| Turnaround Time | < 2 hours for routine tests |
| Quality Complaints | < 0.1% of shipments |
| CAPA Completion Rate | 100% on time |

---

### 3.4 Finance

#### 3.4.1 Process: FI-001 - Accounts Payable

**Process Overview**

| Attribute | Details |
|-----------|---------|
| **Process ID** | FI-001 |
| **Process Name** | Invoice Processing & Payment |
| **Process Owner** | Accounts Payable Lead |
| **Process Category** | Finance |
| **Frequency** | Daily |
| **Trigger** | Receipt of supplier invoice |
| **Primary Output** | Processed payments, accurate records |

**Process Flow**

```
[Receive Supplier Invoice]
    ↓
[Verify Invoice Details] → <Complete?> 
    ↓ Yes
[Match to PO and GRN] → <Match?> 
    ↓ Yes
[Code to Correct GL Account]
    ↓
[Route for Approval] → <Approved?> 
    ↓ Yes
[Schedule Payment per Terms]
    ↓
[Process Payment]
    ↓
[Send Remittance Advice]
    ↓
[Reconcile Bank Statement]
    ↓
[File Document]
```

**KPIs**

| KPI | Target |
|-----|--------|
| Invoice Processing Time | < 3 days |
| Payment Accuracy | 100% |
| Early Payment Discount Capture | > 90% of available |
| Duplicate Payment Prevention | 0 incidents |

---

#### 3.4.2 Process: FI-002 - Accounts Receivable

**Process Overview**

| Attribute | Details |
|-----------|---------|
| **Process ID** | FI-002 |
| **Process Name** | Customer Billing & Collection |
| **Process Owner** | Accounts Receivable Lead |
| **Trigger** | Delivery completed, billing cycle |
| **Primary Output** | Issued invoices, collected payments |

**Process Flow**

```
[Delivery Completed / Billing Trigger]
    ↓
[Generate Invoice]
    ↓
[Verify Invoice Accuracy]
    ↓
[Send Invoice to Customer]
    ↓
[Record in AR Ledger]
    ↓
[Monitor Due Dates] → <Payment Due?> 
    ↓ Yes
[Check for Payment Received] → <Received?> 
    ├── Yes → [Apply Cash] → [Send Receipt] → [Update Records]
    └── No → [Send Reminder] → [Follow-up Call] → [Escalate] → [Collection Actions if Needed]
```

**KPIs**

| KPI | Target |
|-----|--------|
| DSO (Days Sales Outstanding) | < 30 days |
| Collection Effectiveness | > 95% |
| Bad Debt Ratio | < 1% |
| Billing Accuracy | > 99% |

---

#### 3.4.3 Process: FI-003 - Payroll Processing

**Process Overview**

| Attribute | Details |
|-----------|---------|
| **Process ID** | FI-003 |
| **Process Name** | Monthly Payroll Processing |
| **Process Owner** | Payroll Manager |
| **Frequency** | Monthly |
| **Trigger** | Payroll cycle end |
| **Primary Output** | Accurate employee payments |

**Process Flow**

```
[Collect Time & Attendance Data]
    ↓
[Verify Overtime and Leaves]
    ↓
[Calculate Gross Pay]
    ↓
[Calculate Deductions]:
    ├── Tax (PAYE)
    ├── Social Security
    ├── Pension
    ├── Loan Repayments
    └── Other Deductions
    ↓
[Calculate Net Pay]
    ↓
[Verify Calculations] → <Approved?> 
    ↓ Yes
[Process Bank Transfers]
    ↓
[Payslip Generation & Distribution]
    ↓
[Statutory Filings]
    ↓
[Payroll Reconciliation]
    ↓
[Journal Entry to GL]
```

**KPIs**

| KPI | Target |
|-----|--------|
| Payroll Accuracy | 100% |
| On-Time Payment | 100% |
| Compliance | 100% (no penalties) |
| Payroll Processing Time | < 3 days |

---

#### 3.4.4 Process: FI-004 - Financial Reporting

**Process Overview**

| Attribute | Details |
|-----------|---------|
| **Process ID** | FI-004 |
| **Process Name** | Monthly Financial Close & Reporting |
| **Process Owner** | Finance Manager |
| **Frequency** | Monthly |
| **Trigger** | Month-end |
| **Primary Output** | Financial statements, management reports |

**Process Flow**

```
[Month-End Cut-off]
    ↓
[Complete All Transactions]
    ↓
[Reconcile Bank Accounts]
    ↓
[Reconcile AR and AP Sub-ledgers]
    ↓
[Inventory Valuation]
    ↓
[Fixed Assets Depreciation]
    ↓
[Accruals and Prepayments]
    ↓
[Review and Adjust Journal Entries]
    ↓
[Generate Trial Balance]
    ↓
[Prepare Financial Statements]:
    ├── Income Statement
    ├── Balance Sheet
    ├── Cash Flow Statement
    └── Notes to Accounts
    ↓
[Management Review] → <Approved?> 
    ↓ Yes
[Board/Management Reports]
    ↓
[File Statutory Returns]
```

**KPIs**

| KPI | Target |
|-----|--------|
| Close Cycle Time | < 5 business days |
| Report Accuracy | 100% |
| Compliance Deadline | 100% on time |
| Audit Adjustments | Minimal |

---

## 4. Process Documentation Template

### 4.1 Standard Process Documentation Format

All L3 processes in Smart Dairy must be documented using the following template:

---

#### PROCESS DOCUMENT TEMPLATE

**Process Header Section**

| Field | Description |
|-------|-------------|
| Process ID | [Category]-[XXX] format (e.g., FO-001) |
| Process Name | Clear, descriptive name |
| Version | Document version number |
| Effective Date | When this version becomes active |
| Process Owner | Role responsible for process execution |
| Process Category | L1 category |
| Approval Authority | Who can approve changes |

**Process Overview Section**

| Field | Description |
|-------|-------------|
| Purpose | Why this process exists |
| Scope | What is included/excluded |
| Trigger | Event that initiates the process |
| Frequency | How often the process runs |
| Duration | Expected time to complete |
| Primary Output | Main deliverable |

**Process Relationships**

| Relationship Type | Process ID | Process Name |
|-------------------|------------|--------------|
| Triggered By | | |
| Triggers | | |
| Inputs From | | |
| Outputs To | | |
| Related Processes | | |

**Process Flow Section**

*Include BPMN diagram or text description*

**Step-by-Step Procedure**

| Step # | Activity | Role | System | Input | Output | SLA |
|--------|----------|------|--------|-------|--------|-----|
| | | | | | | |

**RACI Matrix**

| Activity | Role A | Role B | Role C | Role D |
|----------|--------|--------|--------|--------|
| Activity 1 | R | A | C | I |
| Activity 2 | C | R | A | I |

*R=Responsible, A=Accountable, C=Consulted, I=Informed*

**Systems and Tools**

| System | Purpose | Data In | Data Out |
|--------|---------|---------|----------|
| | | | |

**Forms and Documents**

| Document | Purpose | Retention |
|----------|---------|-----------|
| | | |

**KPIs and Metrics**

| KPI | Target | Measurement Method | Frequency |
|-----|--------|-------------------|-----------|
| | | | |

**Exception Handling**

| Exception | Trigger | Handling Procedure | Escalation |
|-----------|---------|-------------------|------------|
| | | | |

**Appendices**
- Detailed procedure documents
- System screenshots
- Decision trees
- Contact lists

---

### 4.2 RACI Matrix Template

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                           RACI MATRIX TEMPLATE                                       │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                     │
│  ACTIVITY            │ ROLE A │ ROLE B │ ROLE C │ ROLE D │ ROLE E │                │
│  ────────────────────┼────────┼────────┼────────┼────────┼────────┤                │
│  1. Activity One     │   R    │   A    │   C    │   I    │   I    │                │
│  2. Activity Two     │   C    │   I    │   R    │   A    │   I    │                │
│  3. Activity Three   │   A    │   R    │   C    │   C    │   I    │                │
│  4. Activity Four    │   I    │   I    │   I    │   R    │   A    │                │
│  5. Activity Five    │   R    │   A    │   R    │   C    │   I    │                │
│                      │        │        │        │        │        │                │
│  LEGEND:                                                                            │
│  R = Responsible (Does the work)                                                    │
│  A = Accountable (Ultimately answerable, approves)                                  │
│  C = Consulted (Provides input, two-way communication)                              │
│  I = Informed (Kept updated, one-way communication)                                 │
│                                                                                     │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

### 4.3 Process Metrics Framework

#### 4.3.1 Efficiency Metrics

| Metric | Formula | Target | Data Source |
|--------|---------|--------|-------------|
| Cycle Time | End Time - Start Time | By process | System logs |
| Throughput | Units processed / Time period | Maximum capacity | Production data |
| Resource Utilization | Actual usage / Available capacity | 75-85% | HR/Asset data |
| Cost per Transaction | Total cost / Volume | Benchmark | Financial data |

#### 4.3.2 Quality Metrics

| Metric | Formula | Target | Data Source |
|--------|---------|--------|-------------|
| Error Rate | Errors / Total transactions | < 1% | Quality logs |
| Rework Rate | Reworked items / Total | < 2% | Production data |
| First-Time-Right | Completed correctly first time | > 95% | Quality data |
| Customer Complaints | Complaints / Total orders | < 0.5% | CRM |

#### 4.3.3 Compliance Metrics

| Metric | Formula | Target | Data Source |
|--------|---------|--------|-------------|
| On-Time Completion | On-time / Total | > 95% | System logs |
| SLA Adherence | Met SLAs / Total SLAs | > 98% | Service data |
| Regulatory Compliance | Passed audits / Total audits | 100% | Audit reports |
| Documentation Completeness | Complete records / Total | 100% | Document audit |

---

## 5. Standard Operating Procedures (SOPs)

### 5.1 SOP Template

---

**SOP Document Header**

| Field | Information |
|-------|-------------|
| SOP ID | SOP-[Process Code]-[XXX] |
| SOP Title | |
| Version | |
| Effective Date | |
| Department | |
| Author | |
| Reviewer | |
| Approver | |
| Review Frequency | |

**1. Purpose**
Brief statement of why this SOP exists.

**2. Scope**
Who this SOP applies to and what activities are covered.

**3. Definitions**
Key terms and abbreviations used.

**4. Responsibilities**
Who is responsible for what aspects of this procedure.

**5. Procedure**
Step-by-step instructions.

**6. Safety/Quality Notes**
Critical warnings and precautions.

**7. Related Documents**
References to related SOPs, forms, and policies.

**8. Revision History**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| | | | |

**9. Approvals**

| Role | Name | Signature | Date |
|------|------|-----------|------|
| | | | |

---

### 5.2 SOP-FO-001-001: Daily Milk Collection

**SOP ID**: SOP-FO-001-001  
**Title**: Daily Milk Collection Procedure  
**Version**: 1.0  
**Effective Date**: February 1, 2026  
**Department**: Farm Operations  
**Author**: Farm Supervisor  
**Approver**: Operations Manager

---

#### 1. Purpose
To ensure consistent, hygienic, and safe collection of milk from the milking parlor to the bulk cooling tank while maintaining quality and food safety standards.

#### 2. Scope
This SOP applies to all milkers, farm supervisors, and quality technicians involved in daily milk collection operations.

#### 3. Definitions
- **Bulk Tank**: Stainless steel storage tank for cooling and holding milk
- **CIP**: Clean-in-Place system for automated equipment cleaning
- **Fore-stripping**: First milk expressed to check quality
- **SCC**: Somatic Cell Count, indicator of udder health
- **Milk Line**: Pipeline transporting milk from parlor to tank

#### 4. Responsibilities

| Role | Responsibility |
|------|----------------|
| Farm Supervisor | Overall supervision, quality oversight, record review |
| Head Milker | Milking operations coordination, equipment checks |
| Milker | Execute milking procedures, basic equipment maintenance |
| Quality Technician | Sample collection, basic quality testing |

#### 5. Equipment and Materials

- Milking machines with automatic cluster removers
- Teat cleaning solution and towels
- Teat dip cups and post-milking disinfectant
- Strip cups for fore-milk examination
- Milk sampling bottles (sterile)
- Bulk tank with refrigeration
- CIP cleaning system
- Personal protective equipment (boots, gloves, apron)

#### 6. Procedure

**Pre-Milking Preparation (30 minutes before)**

| Step | Action | Standard | Check |
|------|--------|----------|-------|
| 6.1 | Turn on milking equipment | All systems operational | ☐ |
| 6.2 | Check bulk tank temperature | < 4°C | ☐ |
| 6.3 | Verify cleaning cycle completed | CIP indicator green | ☐ |
| 6.4 | Check milk filter | Clean and properly fitted | ☐ |
| 6.5 | Prepare teat cleaning supplies | Solution at correct concentration | ☐ |
| 6.6 | Review milking list | Identify any special handling | ☐ |

**Cow Preparation**

| Step | Action | Key Point |
|------|--------|-----------|
| 6.7 | Bring cows to holding area | Gentle handling, minimize stress |
| 6.8 | Allow cows to enter parlor | 4-8 cows per side depending on parlor size |
| 6.9 | Visually inspect udder | Check for swelling, injury, or mastitis signs |
| 6.10 | Clean teats | Wipe with individual cloth or spray and wipe |
| 6.11 | Fore-strip each teat | 2-3 squirts into strip cup, check for abnormalities |
| 6.12 | Dry teats thoroughly | Use clean towel, one per cow |

**Milking Operations**

| Step | Action | Key Point |
|------|--------|-----------|
| 6.13 | Attach clusters within 60-90 seconds of stimulation | Timely attachment maximizes milk flow |
| 6.14 | Ensure proper cluster alignment | Check for twists or improper seating |
| 6.15 | Monitor milk flow | Watch for irregular flow or machine issues |
| 6.16 | Wait for automatic cluster removal | Do not force removal |
| 6.17 | Check udder for completeness | Palpate for residual milk |
| 6.18 | Apply post-milking teat disinfectant | Cover entire teat surface |
| 6.19 | Release cow from parlor | Ensure proper exit, no rushing |

**Quality Testing**

| Step | Action | Standard |
|------|--------|----------|
| 6.20 | Collect composite sample | Sterile technique, representative sample |
| 6.21 | Label sample immediately | Date, time, tank number |
| 6.22 | Test for antibiotics | Negative result required |
| 6.23 | Test temperature | < 4°C within 2 hours of first milking |
| 6.24 | Record results in log | Immediate documentation |

**Post-Milking**

| Step | Action | Standard |
|------|--------|----------|
| 6.25 | Rinse milk line with warm water | Remove milk residues |
| 6.26 | Initiate CIP cycle | Follow programmed cycle |
| 6.27 | Record production volume | Accurate measurement |
| 6.28 | Check tank agitation | Ensure proper mixing |
| 6.29 | Secure tank and facility | Lock and alarm system active |
| 6.30 | Complete daily log | All fields completed |

#### 7. Safety and Quality Notes

⚠️ **CRITICAL SAFETY WARNINGS**

- Never mix antibiotic-treated milk with bulk tank milk
- Always wear appropriate PPE
- Report any equipment malfunction immediately
- Do not bypass safety interlocks
- Wash hands thoroughly between handling different animals if infection suspected

⚠️ **QUALITY CRITICAL POINTS**

- Milk must reach 4°C within 2 hours of milking
- All cleaning must follow CIP protocol exactly
- Any abnormal milk must be segregated and investigated
- Documentation must be complete and accurate

#### 8. Related Documents
- K-019 Business Process Documentation (this document)
- Quality Manual QM-001
- Food Safety Plan FSP-001
- Equipment Maintenance Log

#### 9. Forms and Records

| Form | Location | Retention |
|------|----------|-----------|
| Daily Milking Log | Farm Office | 3 years |
| Quality Test Results | Quality Lab | 5 years |
| CIP Verification Log | Farm Office | 2 years |
| Equipment Checklist | Farm Office | 1 year |

---

### 5.3 SOP-SC-006-001: Quality Testing

**SOP ID**: SOP-SC-006-001  
**Title**: Quality Testing Procedure for Raw Milk  
**Version**: 1.0  
**Effective Date**: February 1, 2026  
**Department**: Quality Assurance  
**Author**: Quality Manager  
**Approver**: Operations Manager

---

#### 1. Purpose
To establish standardized procedures for testing raw milk quality to ensure food safety, regulatory compliance, and product integrity.

#### 2. Scope
Applies to all raw milk testing activities including antibiotic screening, bacteriological testing, compositional analysis, and temperature monitoring.

#### 3. Testing Parameters and Standards

| Parameter | Method | Standard | Frequency |
|-----------|--------|----------|-----------|
| Antibiotic Residues | Delvotest or equivalent | Negative | Every collection |
| Somatic Cell Count (SCC) | Direct microscopic or electronic | < 200,000 cells/ml | Daily |
| Total Bacterial Count (TBC) | Standard plate count | < 20,000 CFU/ml | Daily |
| Fat Content | Gerber or infrared | Per contract specs | Daily |
| Protein Content | Kjeldahl or infrared | Per contract specs | Daily |
| Temperature | Thermometer/probe | ≤ 4°C | Every collection |
| pH | pH meter | 6.6 - 6.8 | Spot checks |
| Freezing Point | Cryoscope | > -0.520°C | Weekly |

#### 4. Procedure

**Sample Collection**

1. Use sterile sampling bottles
2. Sample after thorough agitation
3. Take sample from top, middle, and bottom if no agitation
4. Label immediately with: date, time, tank ID, collector initials
5. Transport to lab within 30 minutes or store at 4°C

**Antibiotic Testing**

1. Warm sample to 20-25°C if refrigerated
2. Follow Delvotest kit instructions exactly
3. Incubate for specified time
4. Read result against color chart
5. Record as: NEGATIVE (purple) or POSITIVE (yellow)
6. Any positive result: QUARANTINE milk immediately

**Somatic Cell Count**

1. Mix sample thoroughly
2. Use direct microscopic method or electronic counter
3. Calculate cells per milliliter
4. Record result
5. Flag results > 200,000 for investigation

**Total Bacterial Count**

1. Prepare dilutions as per method
2. Plate appropriate dilutions
3. Incubate at 30°C for 72 hours
4. Count colonies and calculate CFU/ml
5. Report results

#### 5. Actions Based on Results

| Result | Action | Responsibility |
|--------|--------|----------------|
| Antibiotic: Positive | Quarantine milk, notify vet, investigate source | Quality Manager |
| SCC: > 200,000 | Flag cow(s), schedule retest, review mastitis program | Veterinary Officer |
| TBC: > 20,000 | Review hygiene practices, check equipment cleaning | Farm Supervisor |
| Temperature: > 4°C | Check cooling system, assess milk quality | Farm Supervisor |
| pH: Outside range | Test for mastitis, review animal health | Veterinary Officer |

#### 6. Documentation

All test results must be recorded in:
- Laboratory Information Management System (LIMS)
- Daily Quality Control Log
- Monthly Quality Summary Report

---

### 5.4 SOP-SD-006-001: Customer Complaint Handling

**SOP ID**: SOP-SD-006-001  
**Title**: Customer Complaint Handling Procedure  
**Version**: 1.0  
**Effective Date**: February 1, 2026  
**Department**: Customer Service  
**Author**: Customer Service Manager  
**Approver**: Sales & Marketing Manager

---

#### 1. Purpose
To ensure all customer complaints are handled professionally, resolved efficiently, and used as opportunities for improvement.

#### 2. Scope
Applies to all customer complaints received via phone, email, web, social media, or in-person.

#### 3. Complaint Categories

| Category | Examples | Severity |
|----------|----------|----------|
| Product Quality | Spoilage, off-taste, foreign object | High |
| Delivery Issue | Late delivery, wrong item, damaged package | Medium |
| Billing Problem | Incorrect charge, payment issue | Medium |
| Service Issue | Rude staff, poor communication | Medium |
| Subscription | Failed delivery, change request | Low |

#### 4. Procedure

**Step 1: Acknowledge (Within 1 hour)**
- Thank customer for bringing issue to attention
- Apologize for inconvenience
- Provide complaint reference number
- Set expectation for resolution timeline

**Step 2: Record**
- Log complaint in CRM system
- Capture: customer details, product details, issue description, date/time, desired resolution
- Categorize complaint
- Attach any evidence (photos, documents)

**Step 3: Investigate**
- Review order/delivery history
- Check production batch records (if quality issue)
- Interview relevant staff
- Inspect product if returned

**Step 4: Resolve**
- Determine appropriate resolution:
  - Replacement product
  - Refund
  - Credit note
  - Apology and assurance
  - Process improvement
- Escalate if beyond authority limit

**Step 5: Communicate**
- Contact customer with resolution
- Explain findings (without being defensive)
- Confirm acceptance of resolution

**Step 6: Close**
- Document resolution
- Obtain customer confirmation of satisfaction
- Close ticket in system
- Schedule follow-up if appropriate

**Step 7: Analyze**
- Monthly complaint analysis
- Identify trends
- Recommend preventive actions

#### 5. Escalation Matrix

| Severity | First Response | Resolution Target | Escalation To |
|----------|---------------|-------------------|---------------|
| Low | 4 hours | 48 hours | Team Lead |
| Medium | 2 hours | 24 hours | Manager |
| High | 1 hour | Same day | Department Head |
| Critical | Immediate | 4 hours | Managing Director |

#### 6. Authority Limits

| Role | Maximum Resolution Authority |
|------|------------------------------|
| Customer Service Rep | Product replacement up to KSh 1,000 |
| Team Lead | Refund/credit up to KSh 5,000 |
| Manager | Refund/credit up to KSh 20,000 |
| Department Head | Refund/credit up to KSh 50,000 |
| Managing Director | Unlimited + policy changes |

---

### 5.5 SOP-SC-004-001: Inventory Count

**SOP ID**: SOP-SC-004-001  
**Title**: Physical Inventory Count Procedure  
**Version**: 1.0  
**Effective Date**: February 1, 2026  
**Department**: Warehouse  
**Author**: Warehouse Manager  
**Approver**: Supply Chain Manager

---

#### 1. Purpose
To ensure inventory accuracy through systematic physical counting and reconciliation with system records.

#### 2. Types of Inventory Counts

| Type | Frequency | Scope | Purpose |
|------|-----------|-------|---------|
| Cycle Count | Daily/Weekly | Selected items | Continuous accuracy monitoring |
| Full Physical Count | Monthly/Quarterly | All inventory | Complete verification |
| Ad-hoc Count | As needed | Specific items | Investigation or validation |
| Year-end Count | Annually | All inventory | Financial reporting |

#### 3. Cycle Counting Procedure

**Daily Cycle Count**

1. **Preparation**
   - Generate count list from WMS (ABC classification, items due for count)
   - Print count sheets or prepare mobile devices
   - Assign counters (independent pairs)
   - Ensure no transactions during count for selected items

2. **Physical Count**
   - Count physically available stock
   - Count damaged/expired items separately
   - Note location of items
   - Double-check counts
   - Record on count sheet

3. **Verification**
   - Supervisor verifies count methodology
   - Re-count if variance > 5%
   - Sign-off on count sheet

4. **Reconciliation**
   - Enter counts into system
   - System compares to expected quantity
   - Generate variance report

5. **Investigation**
   - Review transaction history for variances
   - Check for unrecorded movements
   - Identify root cause

6. **Adjustment**
   - Approve adjustments per authority matrix
   - Post inventory adjustments
   - Update records

7. **Follow-up**
   - Implement corrective actions
   - Update procedures if needed
   - Track trends by item/location

#### 4. Full Physical Count Procedure

**Pre-Count Preparation (1 week before)**

- Announce count date to all departments
- Plan count teams and assign zones
- Prepare count materials (tags, sheets, scanners)
- Organize warehouse (consolidate, label clearly)
- Freeze incoming/outgoing on count day
- Complete all pending transactions

**Count Day**

- Brief count teams on procedure
- Issue count sheets/tags
- Count teams work independently
- No access to system quantities during count
- Mark counted locations clearly
- Collect and verify all count sheets

**Post-Count**

- Enter all counts into system
- Generate variance report
- Investigate significant variances
- Approve adjustments
- Adjust inventory records
- Prepare count summary report
- Resume normal operations

#### 5. Authority Matrix for Adjustments

| Variance Amount | Approval Required |
|-----------------|-------------------|
| < KSh 1,000 | Warehouse Supervisor |
| KSh 1,000 - 10,000 | Warehouse Manager |
| KSh 10,000 - 50,000 | Supply Chain Manager |
| > KSh 50,000 | Finance Manager + Operations Manager |

---

## 6. Workflow Diagrams

### 6.1 AS-IS vs TO-BE Analysis Methodology

Smart Dairy uses a structured approach to process improvement:

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                     PROCESS IMPROVEMENT LIFECYCLE                            │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│   ┌──────────────┐    ┌──────────────┐    ┌──────────────┐                 │
│   │   AS-IS      │    │     GAP      │    │    TO-BE     │                 │
│   │   ANALYSIS   │───>│   ANALYSIS   │───>│   DESIGN     │                 │
│   │              │    │              │    │              │                 │
│   │ Document     │    │ Identify     │    │ Design       │                 │
│   │ current      │    │ inefficiencies│   │ improved     │                 │
│   │ state        │    │ bottlenecks  │    │ process      │                 │
│   │              │    │ root causes  │    │              │                 │
│   └──────────────┘    └──────────────┘    └──────┬───────┘                 │
│                                                  │                          │
│                                                  ▼                          │
│   ┌──────────────┐    ┌──────────────┐    ┌──────────────┐                 │
│   │   CONTINUOUS │    │    BENEFITS  │    │ IMPLEMENTATION│                │
│   │  IMPROVEMENT │<───│  REALIZATION │<───│   & CHANGE   │                 │
│   │              │    │              │    │   MANAGEMENT │                 │
│   │ Monitor      │    │ Measure      │    │ Execute      │                 │
│   │ metrics      │    │ benefits     │    │ transition   │                 │
│   │ refine       │    │ sustain      │    │ train users  │                 │
│   │              │    │              │    │              │                 │
│   └──────────────┘    └──────────────┘    └──────────────┘                 │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 6.2 Example: Order-to-Cash AS-IS vs TO-BE

#### 6.2.1 AS-IS State (Before Smart Dairy System)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    AS-IS: ORDER-TO-CASH PROCESS                            │
│                    (Manual/Paper-Based)                                     │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  [Customer calls/places order]                                              │
│       ↓                                                                     │
│  [Sales rep writes order on paper form]                                     │
│       ↓                                                                     │
│  [Form walked to warehouse] ──► [Wait for processing]                       │
│       ↓                                                                     │
│  [Manual stock check]                                                       │
│       ↓                                                                     │
│  [Phone call to confirm availability]                                       │
│       ↓                                                                     │
│  [Manual picking from paper list]                                           │
│       ↓                                                                     │
│  [Manual delivery schedule on whiteboard]                                   │
│       ↓                                                                     │
│  [Driver takes paper delivery note]                                         │
│       ↓                                                                     │
│  [Delivery with cash collection]                                            │
│       ↓                                                                     │
│  [Driver returns with paper receipt and cash]                               │
│       ↓                                                                     │
│  [Manual cash reconciliation at end of day]                                 │
│       ↓                                                                     │
│  [Manual invoice creation in accounting system]                             │
│       ↓                                                                     │
│  [File paper records]                                                       │
│                                                                             │
│  PROBLEMS IDENTIFIED:                                                       │
│  • Long order processing time (4-8 hours)                                   │
│  • Frequent stockouts and overselling                                       │
│  • Delivery errors (wrong items/quantities)                                 │
│  • Lost paper records                                                       │
│  • Cash handling risks                                                      │
│  • Limited visibility for customers                                         │
│  • Difficult to track delivery status                                       │
│  • Manual reconciliation errors                                             │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

**AS-IS Metrics**:
- Order cycle time: 6-12 hours
- Order accuracy: 85%
- On-time delivery: 70%
- Customer complaint rate: 8%
- Cash reconciliation errors: 5%

#### 6.2.2 TO-BE State (With Smart Dairy System)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    TO-BE: ORDER-TO-CASH PROCESS                            │
│                    (Integrated Digital System)                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌─────────────┐                                                            │
│  │  Customer   │                                                            │
│  └──────┬──────┘                                                            │
│         │                                                                   │
│         │ Mobile App / Web / Phone                                          │
│         ▼                                                                   │
│  ┌─────────────────────────────────────────────────────────────┐           │
│  │           SMART DAIRY INTEGRATED PLATFORM                    │           │
│  │  ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐         │           │
│  │  │  E-COMM │  │   ERP   │  │   WMS   │  │   DMS   │         │           │
│  │  │  MODULE │  │  CORE   │  │ MODULE  │  │ MODULE  │         │           │
│  │  └────┬────┘  └────┬────┘  └────┬────┘  └────┬────┘         │           │
│  │       └─────────────┴─────────────┴─────────────┘             │           │
│  │                        │                                      │           │
│  │                   Real-time Integration                       │           │
│  └────────────────────────┼─────────────────────────────────────┘           │
│                           │                                                 │
│         ┌─────────────────┼─────────────────┐                               │
│         ▼                 ▼                 ▼                               │
│  ┌─────────────┐   ┌─────────────┐   ┌─────────────┐                       │
│  │   Customer  │   │  Warehouse  │   │   Finance   │                       │
│  │   Portal    │   │    Tablet   │   │   System    │                       │
│  │  (Tracking) │   │  (Picking)  │   │ (Invoicing) │                       │
│  └─────────────┘   └─────────────┘   └─────────────┘                       │
│                                                                             │
│  PROCESS FLOW:                                                              │
│                                                                             │
│  [Order placed via app/web] ──► [Instant validation & stock check]          │
│       ↓                                                                     │
│  [Auto-payment or credit check] ──► [Order confirmed instantly]             │
│       ↓                                                                     │
│  [Auto-allocation in WMS] ──► [Pick list to warehouse tablet]               │
│       ↓                                                                     │
│  [Scan-based picking & packing] ──► [Quality check]                         │
│       ↓                                                                     │
│  [Auto-route optimization] ──► [Driver mobile app assignment]               │
│       ↓                                                                     │
│  [Real-time tracking for customer] ──► [Digital POD collection]             │
│       ↓                                                                     │
│  [Auto-invoice generation] ──► [Payment reconciliation]                     │
│       ↓                                                                     │
│  [Customer satisfaction survey] ──► [Analytics & insights]                  │
│                                                                             │
│  IMPROVEMENTS ACHIEVED:                                                     │
│  • Order processing: Minutes instead of hours                               │
│  • Real-time inventory visibility                                           │
│  • Automated routing reduces delivery time                                  │
│  • Digital records - no lost paperwork                                      │
│  • Electronic payments reduce cash risks                                    │
│  • Customer self-service tracking                                           │
│  • Automated reconciliation                                                 │
│  • Data analytics for continuous improvement                                │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

**TO-BE Metrics (Projected)**:
- Order cycle time: < 1 hour
- Order accuracy: > 99%
- On-time delivery: > 95%
- Customer complaint rate: < 1%
- Cash reconciliation errors: < 0.1%

### 6.3 Gap Analysis Summary

| Aspect | AS-IS | TO-BE | Gap | Priority |
|--------|-------|-------|-----|----------|
| Order Entry | Manual phone/paper | Online/Mobile self-service | High automation need | High |
| Inventory Visibility | Manual checks | Real-time system | System integration | High |
| Order Processing | 4-8 hours | < 30 minutes | Process automation | High |
| Picking | Paper lists | Scan-based digital | Technology deployment | High |
| Routing | Manual whiteboard | AI optimization | Algorithm implementation | Medium |
| Delivery Tracking | None | Real-time GPS | Mobile app deployment | Medium |
| Payment | Cash mostly | Multiple digital options | Payment integration | High |
| Invoicing | Manual entry | Auto-generation | ERP configuration | Medium |
| Reporting | Manual compilation | Real-time dashboards | BI implementation | Medium |

---

## 7. Process Improvement

### 7.1 Continuous Improvement Framework

Smart Dairy adopts the PDCA (Plan-Do-Check-Act) cycle for continuous improvement:

```
                    ┌─────────────┐
                    │    PLAN     │
                    │  • Identify │
                    │    opportunity│
                    │  • Analyze  │
                    │  • Develop  │
                    │    solution │
                    └──────┬──────┘
                           │
           ┌───────────────┘
           │
    ┌──────┴──────┐              ┌─────────────┐
    │     ACT     │              │     DO      │
    │  • Implement│              │  • Execute  │
    │    changes  │◄─────────────│    plan     │
    │  • Standardize│            │  • Train    │
    │  • Communicate│            │  • Document │
    └─────────────┘              └──────┬──────┘
                                        │
                                        └───────────────┐
                                                        │
                                        ┌─────────────┐
                                        │    CHECK    │
                                        │  • Measure  │
                                        │    results  │
                                        │  • Compare  │
                                        │    to target│
                                        │  • Identify │
                                        │    gaps     │
                                        └─────────────┘
```

### 7.2 Lean Principles Application

Smart Dairy applies Lean Six Sigma principles to eliminate waste and improve efficiency:

#### 7.2.1 The 8 Wastes (DOWNTIME) in Dairy Operations

| Waste | Definition | Example in Smart Dairy | Improvement Action |
|-------|------------|------------------------|-------------------|
| **D**efects | Errors requiring rework | Wrong product picked, spoiled milk | Barcode scanning, cold chain monitoring |
| **O**verproduction | Making more than needed | Excess milk production | Demand forecasting, production planning |
| **W**aiting | Idle time | Cows waiting in holding area | Optimized milking schedule |
| **N**on-utilized talent | Underusing skills | Manual data entry by skilled staff | Automation, role redesign |
| **T**ransportation | Unnecessary movement | Multiple handling of products | Direct flow design |
| **I**nventory | Excess stock | Overstocked packaging materials | JIT procurement |
| **M**otion | Unnecessary movement | Walking to fetch tools | 5S workplace organization |
| **E**xtra-processing | Unnecessary steps | Duplicate data entry | System integration |

#### 7.2.2 5S Workplace Organization

| S | Description | Application |
|---|-------------|-------------|
| **Sort** | Remove unnecessary items | Clear expired products, unused tools |
| **Set in Order** | Organize for efficiency | Fixed locations for equipment, labeled storage |
| **Shine** | Clean and inspect | Daily cleaning schedules, equipment checks |
| **Standardize** | Create procedures | Standard layouts, checklists |
| **Sustain** | Maintain discipline | Audits, recognition programs |

### 7.3 Automation Opportunities

#### 7.3.1 Automation Priority Matrix

| Process Area | Automation Opportunity | Impact | Effort | Priority |
|--------------|----------------------|--------|--------|----------|
| Order Entry | Customer self-service portal | High | Medium | 1 |
| Inventory | Auto-reorder points, RFID tracking | High | Medium | 2 |
| Milking | Automated milking systems (robots) | High | High | 3 |
| Quality | Automated sampling and testing | High | Medium | 4 |
| Delivery | Route optimization, auto-dispatch | High | Low | 1 |
| Billing | Auto-invoice, payment reconciliation | Medium | Low | 1 |
| Reporting | Real-time dashboards | Medium | Low | 2 |
| Customer Service | Chatbot for FAQs | Medium | Low | 3 |
| Feed Management | Automated feeding systems | Medium | High | 4 |
| Health Monitoring | IoT sensors for cow health | High | Medium | 2 |

#### 7.3.2 Digital Transformation Roadmap

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                  DIGITAL TRANSFORMATION ROADMAP                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  PHASE 1 (Months 1-3)           PHASE 2 (Months 4-6)                        │
│  ─────────────────────          ─────────────────────                       │
│  • Core ERP implementation      • WMS integration                           │
│  • Order management module      • Mobile apps for drivers                   │
│  • Basic customer portal        • Quality management system                 │
│  • Inventory tracking           • Advanced analytics                        │
│  • Accounting integration       • Customer self-service features            │
│                                                                             │
│  PHASE 3 (Months 7-12)          PHASE 4 (Year 2+)                           │
│  ─────────────────────          ─────────────────────                       │
│  • IoT sensor deployment        • AI/ML for demand forecasting              │
│  • Automated milking systems    • Robotic process automation                │
│  • Predictive maintenance       • Blockchain for traceability               │
│  • Advanced BI & dashboards     • Full supply chain integration             │
│  • Subscription automation      • Smart farm autonomous operations          │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 7.4 Process Improvement Projects

| Project ID | Project Name | Objective | Expected Benefit | Timeline | Owner |
|------------|--------------|-----------|------------------|----------|-------|
| PIP-001 | Order-to-Cash Optimization | Reduce order cycle time by 75% | Faster fulfillment, higher satisfaction | Q1 2026 | Sales Manager |
| PIP-002 | Cold Chain Excellence | Zero temperature breaches | Quality assurance, reduced spoilage | Q1 2026 | Operations Manager |
| PIP-003 | Inventory Accuracy | Achieve 99.5% accuracy | Reduced stockouts, lower carrying cost | Q2 2026 | Warehouse Manager |
| PIP-004 | Automated Milking | Deploy milking robots | Labor efficiency, consistent quality | Q3 2026 | Farm Supervisor |
| PIP-005 | Predictive Maintenance | IoT-based equipment monitoring | Reduced downtime, lower repair costs | Q2 2026 | Maintenance Lead |
| PIP-006 | Customer Self-Service | Full portal functionality | Reduced call volume, 24/7 availability | Q2 2026 | IT Manager |

---

## 8. Process Governance

### 8.1 Process Ownership Structure

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    PROCESS GOVERNANCE STRUCTURE                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│                    ┌─────────────────────────┐                              │
│                    │  MANAGING DIRECTOR      │                              │
│                    │  Executive Sponsor      │                              │
│                    └───────────┬─────────────┘                              │
│                                │                                            │
│              ┌─────────────────┼─────────────────┐                         │
│              │                 │                 │                         │
│    ┌─────────▼────────┐ ┌──────▼──────┐ ┌───────▼────────┐                │
│    │ OPERATIONS       │ │   FINANCE   │ │   SALES &      │                │
│    │ MANAGER          │ │   MANAGER   │ │   MARKETING    │                │
│    │ (Process Owner   │ │ (Process    │ │   MANAGER      │                │
│    │  - Operations)   │ │  Owner -    │ │ (Process Owner │                │
│    │                  │ │  Finance)   │ │  - Sales)      │                │
│    └─────────┬────────┘ └──────┬──────┘ └───────┬────────┘                │
│              │                 │                 │                         │
│    ┌─────────▼────────┐ ┌──────▼──────┐ ┌───────▼────────┐                │
│    │ Farm Supervisor  │ │ AP/AR Leads │ │ Customer       │                │
│    │ Warehouse Mgr    │ │ Payroll Mgr │ │ Service Lead   │                │
│    │ Quality Mgr      │ │ Reporting   │ │ Key Accounts   │                │
│    │ (Process         │ │  Analyst    │ │ (Process       │                │
│    │  Managers)       │ │ (Process    │ │  Managers)     │                │
│    │                  │ │  Managers)  │ │                │                │
│    └──────────────────┘ └─────────────┘ └────────────────┘                │
│                                                                             │
│  ROLES & RESPONSIBILITIES:                                                  │
│                                                                             │
│  Executive Sponsor: Strategic direction, resource approval, escalation      │
│  Process Owner: Process design, performance, improvement initiatives        │
│  Process Manager: Day-to-day execution, procedure maintenance, training     │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 8.2 Process Owner Responsibilities

| Responsibility | Description | Frequency |
|----------------|-------------|-----------|
| Process Design | Define and document process workflows | As needed |
| Performance Monitoring | Review KPIs, identify issues | Weekly/Monthly |
| Improvement Initiatives | Lead continuous improvement projects | Quarterly |
| Training | Ensure staff training on processes | Ongoing |
| Change Management | Approve process changes | As needed |
| Audit Support | Respond to internal/external audits | As scheduled |
| Escalation Point | Handle process-related escalations | As needed |

### 8.3 Process Review Schedule

| Review Type | Scope | Frequency | Participants | Output |
|-------------|-------|-----------|--------------|--------|
| Operational Review | Daily execution issues | Daily | Process Manager, Team Leads | Action items |
| Performance Review | KPIs, metrics | Weekly | Process Owner, Managers | Performance report |
| Process Health Check | End-to-end process efficiency | Monthly | Process Owner, Stakeholders | Improvement plan |
| Strategic Review | Process strategy, capabilities | Quarterly | Executive Sponsor, Owners | Strategic roadmap |
| Annual Process Audit | Compliance, effectiveness | Annually | QA, Internal Audit | Audit report |

### 8.4 Change Management Process

**Process Change Request Procedure**:

1. **Identify Need**
   - Performance gap identified
   - New requirement (regulatory, customer)
   - Improvement opportunity
   - Technology change

2. **Submit Change Request**
   - Use Change Request Form (CRF)
   - Describe current state, proposed change, rationale
   - Estimate impact and resources required

3. **Impact Assessment**
   - Process Owner reviews request
   - Assess impact on:
     - Other processes
     - Systems
     - People/skills
     - Compliance
     - Customers

4. **Approval**

| Change Type | Approval Authority |
|-------------|-------------------|
| Minor (procedural, no system change) | Process Owner |
| Moderate (impacts single department) | Department Head |
| Major (cross-functional, system change) | Operations Committee |
| Strategic (business model change) | Managing Director |

5. **Implementation**
   - Develop implementation plan
   - Update documentation
   - Train affected staff
   - Execute change
   - Monitor results

6. **Review**
   - Post-implementation review
   - Verify expected benefits realized
   - Document lessons learned

### 8.5 Process Documentation Standards

**Version Control**:
- All documents use semantic versioning (Major.Minor)
- Major change: Significant process change affecting workflow
- Minor change: Clarification, correction, format update

**Document Storage**:
- Master copies stored in Document Management System
- Physical copies in controlled locations only
- Obsolete versions archived, not destroyed

**Access Control**:
| Document Type | Access Level |
|---------------|--------------|
| L1-L2 Processes | All employees |
| L3 Processes | Department staff |
| L4 Procedures / SOPs | Role-specific |
| Sensitive/Financial | Authorized only |

---

## 9. Appendices

### 9.1 Appendix A: Complete Process Catalog



#### L1: Process Categories

| Code | Category | Owner | Objective |
|------|----------|-------|-----------|
| L1.1 | Farm Operations | Operations Manager | Efficient, quality milk production |
| L1.2 | Sales & Distribution | Sales Manager | Maximize revenue, customer satisfaction |
| L1.3 | Supply Chain | Supply Chain Manager | Optimize procurement, inventory, logistics |
| L1.4 | Finance & Administration | Finance Manager | Financial control, compliance, reporting |

#### L2: Process Groups

| Code | Group | Category | Owner |
|------|-------|----------|-------|
| L1.1-P01 | Milk Production | L1.1 | Farm Supervisor |
| L1.1-P02 | Animal Health | L1.1 | Veterinary Officer |
| L1.1-P03 | Breeding | L1.1 | Breeding Manager |
| L1.1-P04 | Feed Management | L1.1 | Nutritionist |
| L1.2-P01 | B2C Sales | L1.2 | Customer Service Lead |
| L1.2-P02 | B2B Sales | L1.2 | Key Account Manager |
| L1.2-P03 | Subscription | L1.2 | Subscription Manager |
| L1.2-P04 | Delivery | L1.2 | Logistics Manager |
| L1.3-P01 | Procurement | L1.3 | Procurement Manager |
| L1.3-P02 | Inventory | L1.3 | Warehouse Manager |
| L1.3-P03 | Quality Control | L1.3 | Quality Manager |
| L1.3-P04 | Cold Chain | L1.3 | Cold Chain Manager |
| L1.4-P01 | Accounts Payable | L1.4 | AP Lead |
| L1.4-P02 | Accounts Receivable | L1.4 | AR Lead |
| L1.4-P03 | Payroll | L1.4 | Payroll Manager |
| L1.4-P04 | Reporting | L1.4 | Finance Manager |

#### L3: Process Inventory

| Code | Process Name | Group | Owner | Frequency |
|------|--------------|-------|-------|-----------|
| FO-001 | Daily Milk Production | L1.1-P01 | Farm Supervisor | Daily |
| FO-002 | Milking Operations | L1.1-P01 | Head Milker | Daily (2-3x) |
| FO-003 | Animal Health Check | L1.1-P02 | Veterinary Officer | Daily/As needed |
| FO-004 | Vaccination Program | L1.1-P02 | Veterinary Officer | Scheduled |
| FO-005 | Breeding Cycle Mgmt | L1.1-P03 | Breeding Manager | Continuous |
| FO-006 | Calving Management | L1.1-P03 | Farm Supervisor | As needed |
| FO-007 | Feed Planning | L1.1-P04 | Nutritionist | Weekly/Daily |
| FO-008 | Pasture Management | L1.1-P04 | Farm Supervisor | Seasonal |
| SD-001 | B2C Order-to-Cash | L1.2-P01 | Customer Service Lead | Continuous |
| SD-002 | B2B Order-to-Cash | L1.2-P02 | Key Account Manager | Continuous |
| SD-003 | Subscription Mgmt | L1.2-P03 | Subscription Manager | Continuous |
| SD-004 | Route Planning | L1.2-P04 | Logistics Manager | Daily |
| SD-005 | Last-Mile Delivery | L1.2-P04 | Delivery Supervisor | Daily |
| SD-006 | Complaint Handling | L1.2-P01 | Customer Service Manager | As needed |
| SC-001 | Procurement-to-Pay | L1.3-P01 | Procurement Manager | Continuous |
| SC-002 | Supplier Management | L1.3-P01 | Procurement Manager | Quarterly |
| SC-003 | Goods Receipt | L1.3-P02 | Warehouse Supervisor | Daily |
| SC-004 | Inventory Count | L1.3-P02 | Inventory Controller | Daily/Monthly |
| SC-005 | Cold Chain Mgmt | L1.3-P04 | Cold Chain Manager | Continuous |
| SC-006 | Quality Testing | L1.3-P03 | Quality Manager | Daily |
| FI-001 | Accounts Payable | L1.4-P01 | AP Lead | Daily |
| FI-002 | Accounts Receivable | L1.4-P02 | AR Lead | Daily |
| FI-003 | Payroll Processing | L1.4-P03 | Payroll Manager | Monthly |
| FI-004 | Financial Close | L1.4-P04 | Finance Manager | Monthly |
| FI-005 | Budget Management | L1.4-P04 | Finance Manager | Annual/Quarterly |

---

### 9.2 Appendix B: RACI Matrices

#### B.1 Farm Operations RACI Matrix

| Activity/Decision | Farm Supervisor | Vet Officer | Breeding Mgr | Nutritionist | Milker | Herdsman |
|-------------------|-----------------|-------------|--------------|--------------|--------|----------|
| Daily milking schedule | A/R | C | I | I | R | C |
| Animal health treatment | A | R | I | C | C | I |
| Breeding decision | C | C | A/R | I | I | I |
| Feed formulation | C | C | I | A/R | I | I |
| Milk quality approval | A | R | I | I | C | I |
| Calving assistance | A/R | R | I | I | I | C |
| Equipment maintenance | A | I | I | I | R | I |
| Culling decision | C | R | A | I | I | I |
| Purchase of supplies | A | C | I | R | I | I |
| Staff scheduling | A/R | C | I | I | C | I |

#### B.2 Sales & Distribution RACI Matrix

| Activity/Decision | Sales Mgr | Cust Svc Lead | Key Acct Mgr | Logistics Mgr | Warehouse Mgr | Driver |
|-------------------|-----------|---------------|--------------|---------------|---------------|--------|
| B2C order processing | A | R | I | C | C | I |
| B2B order approval | A/R | I | R | C | C | I |
| Pricing decisions | A | C | C | I | I | I |
| Route planning | C | I | I | A/R | C | C |
| Delivery execution | I | C | I | A | C | R |
| Complaint resolution | A | R | C | C | C | I |
| Credit approval | C | C | A | I | I | I |
| Returns handling | C | R | C | C | A/R | I |

#### B.3 Supply Chain RACI Matrix

| Activity/Decision | SCM Mgr | Procurement Mgr | Warehouse Mgr | Quality Mgr | Cold Chain Mgr |
|-------------------|---------|-----------------|---------------|-------------|----------------|
| Supplier selection | A | R | C | C | I |
| Purchase approval | A | R | C | I | I |
| Goods receipt | C | I | A/R | R | I |
| Quality inspection | C | C | C | A/R | I |
| Cold chain monitoring | C | I | C | C | A/R |
| Inventory adjustments | A | I | R | C | I |
| Quarantine decisions | C | C | R | A | I |
| Waste disposal | C | I | R | A | C |

#### B.4 Finance RACI Matrix

| Activity/Decision | Finance Mgr | AP Lead | AR Lead | Payroll Mgr | Accountant |
|-------------------|-------------|---------|---------|-------------|------------|
| Invoice processing | A | R | I | I | C |
| Payment approval | A/R | R | I | I | C |
| Collection actions | A | I | R | I | C |
| Payroll processing | A | I | I | R | C |
| Month-end close | A/R | R | R | R | R |
| Financial reporting | A/R | C | C | C | R |
| Budget preparation | A/R | C | C | C | R |
| Audit response | A | C | C | C | R |

---

### 9.3 Appendix C: BPMN 2.0 Flowchart Symbols Reference

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    BPMN 2.0 SYMBOL REFERENCE                                │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  EVENTS                                                                     │
│  ───────                                                                    │
│                                                                             │
│     ◯        Start Event - Initiates a process                             │
│    ┌─┐                                                                    │
│    │◯│       Start Event (Message) - Triggered by message                  │
│    └─┘                                                                    │
│     ◯        Start Event (Timer) - Triggered by time                       │
│    ╱╲                                                                    │
│                                                                             │
│     ◉        End Event - Terminates a process                              │
│    ┌─┐                                                                    │
│    │◉│       End Event (Message) - Sends message upon completion           │
│    └─┘                                                                    │
│    ╲╱        End Event (Error) - Process ends with error                   │
│                                                                             │
│    ┌─◯─┐     Intermediate Event - Occurs during process                    │
│    │    │                                                                   │
│    └────┘                                                                   │
│                                                                             │
│  ACTIVITIES                                                                 │
│  ──────────                                                                 │
│                                                                             │
│  ┌────────┐    Task - Unit of work                                         │
│  │ Task   │                                                                 │
│  └────────┘                                                                 │
│                                                                             │
│  ┌────────┐    User Task - Requires human action                           │
│  │ 👤    │                                                                 │
│  │ Task   │                                                                 │
│  └────────┘                                                                 │
│                                                                             │
│  ┌────────┐    Service Task - Automated by system                          │
│  │ ⚙️    │                                                                 │
│  │ Task   │                                                                 │
│  └────────┘                                                                 │
│                                                                             │
│  ┌────────┐    Sub-process - Contains detailed flow                        │
│  │        │    ┌────┐                                                       │
│  │ Process│ or │ +  │ (collapsed)                                           │
│  │        │    └────┘                                                       │
│  └────────┘                                                                 │
│                                                                             │
│  GATEWAYS                                                                   │
│  ─────────                                                                  │
│                                                                             │
│      ◇       Exclusive Gateway (XOR) - One path taken                      │
│      ?       (Decision point - Yes/No, Option A/B)                         │
│                                                                             │
│      ◇       Parallel Gateway (AND) - All paths taken                      │
│      +       (Parallel execution)                                          │
│                                                                             │
│      ◇       Inclusive Gateway (OR) - One or more paths                    │
│      ○       (Based on conditions)                                         │
│                                                                             │
│  CONNECTING OBJECTS                                                         │
│  ───────────────────                                                        │
│                                                                             │
│  ──────▶     Sequence Flow - Order of activities                           │
│                                                                             │
│  ─ ─ ─▶      Conditional Flow - Based on condition                         │
│                                                                             │
│  ──────◯     Default Flow - If no other condition met                      │
│                                                                             │
│  ──────▶     Message Flow - Between separate pools                         │
│                                                                             │
│  SWIMLANES (POOLS)                                                          │
│  ─────────────────                                                            │
│                                                                             │
│  ┌───────────────────────────────────────────────┐                         │
│  │           POOL (Organization/Process)          │                         │
│  ├───────────────────────────────────────────────┤                         │
│  │ LANE 1 │ LANE 2 │ LANE 3 │                    │ (Departments/Roles)     │
│  │        │        │        │                    │                         │
│  │        │        │        │                    │                         │
│  └───────────────────────────────────────────────┘                         │
│                                                                             │
│  ARTIFACTS                                                                  │
│  ─────────                                                                  │
│                                                                             │
│  ┌────┐      Data Object - Information used/produced                       │
│  │📄  │                                                                     │
│  └────┘                                                                     │
│                                                                             │
│  ┌────┐      Data Store - Persistent storage                               │
│  │🗄️ │                                                                     │
│  └────┘                                                                     │
│                                                                             │
│  ══════      Annotation - Comment/explanation                              │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

### 9.4 Appendix D: Template Library

#### D.1 Process Documentation Template (Simplified)

```markdown
# [Process ID]: [Process Name]

## Process Information
- **Process ID**: 
- **Process Name**: 
- **Version**: 
- **Effective Date**: 
- **Process Owner**: 
- **Process Category**: 

## Overview
- **Purpose**: 
- **Scope**: 
- **Trigger**: 
- **Frequency**: 
- **Duration**: 
- **Primary Output**: 

## Process Flow
[Insert flowchart or text description]

## Steps
| Step | Activity | Role | Input | Output | SLA |
|------|----------|------|-------|--------|-----|
| 1 | | | | | |
| 2 | | | | | |

## RACI
| Activity | Role A | Role B | Role C |
|----------|--------|--------|--------|
| Activity 1 | R | A | C |

## Systems
| System | Purpose |
|--------|---------|
| | |

## KPIs
| KPI | Target |
|-----|--------|
| | |

## Exceptions
| Exception | Handling |
|-----------|----------|
| | |
```

#### D.2 SOP Template (Simplified)

```markdown
# SOP-[Code]: [Title]

## Header
| Field | Value |
|-------|-------|
| SOP ID | |
| Version | |
| Effective Date | |
| Department | |
| Author | |
| Approver | |

## 1. Purpose
## 2. Scope
## 3. Definitions
## 4. Responsibilities
## 5. Procedure
| Step | Action | Standard |
|------|--------|----------|
| 1 | | |

## 6. Safety Notes
## 7. Related Documents
## 8. Revision History
```

#### D.3 Change Request Form

```markdown
# CHANGE REQUEST FORM

| Field | Information |
|-------|-------------|
| CR Number | CR-[YYYY]-[NNN] |
| Date Submitted | |
| Submitted By | |
| Process Affected | |
| Change Type | [ ] Minor [ ] Moderate [ ] Major [ ] Strategic |

## Current State
[Describe current process/procedure]

## Proposed Change
[Describe proposed change]

## Rationale
[Why is this change needed?]

## Impact Analysis
| Area | Impact | Mitigation |
|------|--------|------------|
| Other Processes | | |
| Systems | | |
| People | | |
| Compliance | | |
| Customers | | |

## Resources Required
[Time, cost, training needs]

## Implementation Plan
[Timeline and steps]

## Approvals
| Role | Name | Decision | Date |
|------|------|----------|------|
| Process Owner | | [ ] Approve [ ] Reject | |
| Department Head | | [ ] Approve [ ] Reject | |
| (If needed) MD | | [ ] Approve [ ] Reject | |
```

#### D.4 Process Performance Report Template

```markdown
# PROCESS PERFORMANCE REPORT

| Field | Value |
|-------|-------|
| Report Period | |
| Process Name | |
| Process Owner | |
| Report Date | |

## KPI Summary
| KPI | Target | Actual | Variance | Status |
|-----|--------|--------|----------|--------|
| | | | | 🟢🟡🔴 |

## Key Findings
1. 
2. 
3. 

## Issues and Escalations
| Issue | Severity | Owner | Action | Due Date |
|-------|----------|-------|--------|----------|
| | | | | |

## Improvement Opportunities
1. 
2. 

## Action Items
| Action | Owner | Due Date | Status |
|--------|-------|----------|--------|
| | | | |

## Next Review Date
```

---

### 9.5 Appendix E: Process Metrics Dashboard Template

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                 PROCESS PERFORMANCE DASHBOARD                                │
│                    Smart Dairy Ltd. - [Month Year]                           │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  FARM OPERATIONS                    SALES & DISTRIBUTION                    │
│  ────────────────                   ────────────────────                    │
│                                                                             │
│  ┌─────────────┐ ┌─────────────┐    ┌─────────────┐ ┌─────────────┐        │
│  │ Milk Yield  │ │   SCC       │    │ Order Acc.  │ │ On-Time Del │        │
│  │   32.5 L    │ │  185,000    │    │    99.2%    │ │   96.5%     │        │
│  │   🟢 +2.3%  │ │   🟢 -5%    │    │   🟢 +0.5%  │ │   🟢 +1.2%  │        │
│  └─────────────┘ └─────────────┘    └─────────────┘ └─────────────┘        │
│                                                                             │
│  ┌─────────────┐ ┌─────────────┐    ┌─────────────┐ ┌─────────────┐        │
│  │ TBC         │ │ Mastitis    │    │ Cust Sat.   │ │ DSO (days)  │        │
│  │  15,000     │ │    2.1%     │    │    4.7/5    │ │    28       │        │
│  │   🟢 -8%    │ │   🟡 +0.2%  │    │   🟢 +0.1   │ │   🟢 -2     │        │
│  └─────────────┘ └─────────────┘    └─────────────┘ └─────────────┘        │
│                                                                             │
│  SUPPLY CHAIN                       FINANCE                                 │
│  ────────────                       ───────                                 │
│                                                                             │
│  ┌─────────────┐ ┌─────────────┐    ┌─────────────┐ ┌─────────────┐        │
│  │ Inv Accuracy│ │ Cold Chain  │    │ Invoice Proc│ │ DPO (days)  │        │
│  │   99.5%     │ │  100% Compliant│  │   2.1 days  │ │    35       │        │
│  │   🟢 +0.3%  │ │     🟢      │    │   🟢 -0.5   │ │   🟢 +2     │        │
│  └─────────────┘ └─────────────┘    └─────────────┘ └─────────────┘        │
│                                                                             │
│  ┌─────────────┐ ┌─────────────┐    ┌─────────────┐ ┌─────────────┐        │
│  │ Supplier OTD│ │ Stockout %  │    │ Payroll Acc │ │ Close Time  │        │
│  │   97.2%     │ │    0.5%     │    │   100%      │ │  4.5 days   │        │
│  │   🟢 +1.5%  │ │   🟡 +0.1%  │    │     🟢      │ │   🟢 -0.5   │        │
│  └─────────────┘ └─────────────┘    └─────────────┘ └─────────────┘        │
│                                                                             │
│  LEGEND: 🟢 On/Above Target  🟡 Within 10% of Target  🔴 Below Target       │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

### 9.6 Appendix F: Glossary of Terms

| Term | Definition |
|------|------------|
| **AS-IS** | Current state of a process before improvement |
| **BPMN** | Business Process Model and Notation - standard for process modeling |
| **CAPA** | Corrective and Preventive Action |
| **CIP** | Clean-in-Place - automated cleaning system |
| **Cold Chain** | Temperature-controlled supply chain |
| **CRM** | Customer Relationship Management system |
| **DSO** | Days Sales Outstanding - average collection period |
| **ERP** | Enterprise Resource Planning system |
| **FMS** | Farm Management System |
| **IoT** | Internet of Things - connected sensors/devices |
| **KPI** | Key Performance Indicator |
| **LIMS** | Laboratory Information Management System |
| **OTIF** | On-Time-In-Full delivery metric |
| **PDCA** | Plan-Do-Check-Act improvement cycle |
| **POD** | Proof of Delivery |
| **RACI** | Responsible-Accountable-Consulted-Informed matrix |
| **SCC** | Somatic Cell Count - milk quality indicator |
| **SLA** | Service Level Agreement |
| **SOP** | Standard Operating Procedure |
| **TO-BE** | Future state of a process after improvement |
| **TBC** | Total Bacterial Count - milk quality indicator |
| **WMS** | Warehouse Management System |

---

### 9.7 Appendix G: Document Control

#### G.1 Document Distribution List

| Role | Name | Department | Copy | Date Issued |
|------|------|------------|------|-------------|
| Managing Director | | Executive | Electronic | |
| Operations Manager | | Operations | Electronic + Hard | |
| Sales Manager | | Sales | Electronic | |
| Finance Manager | | Finance | Electronic + Hard | |
| Supply Chain Manager | | Supply Chain | Electronic | |
| Quality Manager | | Quality | Electronic + Hard | |
| Farm Supervisor | | Farm | Hard | |
| Warehouse Manager | | Warehouse | Hard | |
| HR Manager | | HR | Electronic | |
| IT Manager | | IT | Electronic | |

#### G.2 Related Documents

| Document ID | Document Name | Relationship |
|-------------|---------------|--------------|
| K-005 | End User Manual - Farm Supervisors | Reference for FO procedures |
| K-006 | End User Manual - Sales Team | Reference for SD procedures |
| K-007 | End User Manual - Warehouse Staff | Reference for SC procedures |
| K-008 | End User Manual - Accounting | Reference for FI procedures |
| I-001 | Implementation Plan | Implementation context |
| F-001 | Functional Requirements | System requirements reference |

#### G.3 Review and Approval

This document must be reviewed:
- **Quarterly** for operational accuracy
- **Annually** for comprehensive update
- **As needed** when significant process changes occur

**Approval required from:**
- Operations Manager (primary owner)
- Managing Director (executive sponsor)
- Affected Process Owners

---

## Document End

---

*This Business Process Documentation (K-019) is the property of Smart Dairy Ltd. and contains confidential information. Unauthorized distribution or copying is prohibited.*

**© 2026 Smart Dairy Ltd. All Rights Reserved.**

---

**Document Control Information:**

| Field | Value |
|-------|-------|
| Document ID | K-019 |
| Version | 1.0 |
| Total Pages | 50+ |
| Classification | Internal Use |
| Next Review Date | April 30, 2026 |
| Document Owner | Operations Manager |

---

*End of Document K-019: Business Process Documentation*
