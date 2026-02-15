# Smart Dairy Smart Portal + ERP System
## Phase 2: Operations Implementation
### Milestone 1: B2B Marketplace Foundation

---

**Document Information**

| Attribute | Details |
|-----------|---------|
| **Document Version** | 1.0 |
| **Milestone** | 1 of 10 |
| **Duration** | Days 1-10 |
| **Timeline** | 10 Days |
| **Team Allocation** | Lead Dev (R/A), Backend Dev (C), Frontend Dev (C) |
| **Status** | Planned |

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Technical Architecture](#2-technical-architecture)
3. [Database Schema Design](#3-database-schema-design)
4. [Module Specifications](#4-module-specifications)
5. [API Specifications](#5-api-specifications)
6. [Daily Task Allocation](#6-daily-task-allocation)
7. [Code Implementation](#7-code-implementation)
8. [Testing Protocols](#8-testing-protocols)
9. [Deliverables and Verification](#9-deliverables-and-verification)
10. [Appendices](#10-appendices)

---

## 1. Executive Summary

### 1.1 Milestone Overview

Milestone 1 establishes the **foundation for the B2B Marketplace Portal**, a critical component of Smart Dairy's wholesale distribution strategy. This milestone delivers the core infrastructure for B2B operations including partner registration workflows, tier-based classification system, foundational pricing engine, and basic catalog views.

### 1.2 Objectives

| Objective | Target | Success Metric |
|-----------|--------|----------------|
| Partner Registration | Functional onboarding workflow | Complete registration flow working |
| Tier Management | 4-tier classification system | Distributor, Retailer, HORECA, Institutional tiers configured |
| Pricing Engine | Base pricing structure | Tier-based pricing calculation working |
| Catalog Foundation | B2B product catalog | Product visibility by tier implemented |
| User Management | Multi-user B2B accounts | Sub-user creation functional |

### 1.3 Scope

**In Scope:**
- B2B partner registration and verification workflow
- Customer tier classification (Distributor, Retailer, HORECA, Institutional)
- Tier-based pricing engine foundation
- B2B product catalog with visibility rules
- Multi-user account management for B2B customers
- Basic B2B dashboard framework

**Out of Scope (Future Milestones):**
- Credit management (Milestone 5)
- Bulk ordering with CSV upload (Milestone 5)
- Standing orders (Milestone 5)
- Advanced analytics (Milestone 9)

### 1.4 Dependencies

| Dependency | Source | Status |
|------------|--------|--------|
| Odoo 19 CE Core | Phase 1 | Required |
| Product Catalog | Phase 1 | Required |
| User Authentication | Phase 1 | Required |
| Website Module | Phase 1 | Required |

---

## 2. Technical Architecture

### 2.1 B2B Marketplace Architecture

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                    B2B MARKETPLACE ARCHITECTURE                                  │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │                         PRESENTATION LAYER                               │    │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐          │    │
│  │  │   B2B Portal    │  │  Registration   │  │    Dashboard    │          │    │
│  │  │     Web UI      │  │     Wizard      │  │     (React)     │          │    │
│  │  └─────────────────┘  └─────────────────┘  └─────────────────┘          │    │
│  └─────────────────────────────────────────────────────────────────────────┘    │
│                                      │                                           │
│                                      ▼                                           │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │                      APPLICATION LAYER (Odoo 19)                         │    │
│  │                                                                          │    │
│  │  ┌─────────────────────────────────────────────────────────────────┐    │    │
│  │  │                    smart_b2b_portal Module                       │    │    │
│  │  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌───────────┐  │    │    │
│  │  │  │   Partner   │ │    Tier     │ │   Pricing   │ │  Catalog  │  │    │    │
│  │  │  │  Management │ │  Management │ │   Engine    │ │ Management│  │    │    │
│  │  │  └─────────────┘ └─────────────┘ └─────────────┘ └───────────┘  │    │    │
│  │  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌───────────┐  │    │    │
│  │  │  │    User     │ │Registration │ │   Approval  │ │ Dashboard │  │    │    │
│  │  │  │  Management │ │   Workflow  │ │   Workflow  │ │  Service  │  │    │    │
│  │  │  └─────────────┘ └─────────────┘ └─────────────┘ └───────────┘  │    │    │
│  │  └─────────────────────────────────────────────────────────────────┘    │    │
│  │                                                                          │    │
│  └─────────────────────────────────────────────────────────────────────────┘    │
│                                      │                                           │
│                                      ▼                                           │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │                         DATA LAYER                                       │    │
│  │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────────┐   │    │
│  │  │  res.    │ │ b2b.     │ │ product. │ │b2b.tier. │ │b2b.pricelist.│   │    │
│  │  │ partner  │ │ partner  │ │ template │ │  config  │ │    item      │   │    │
│  │  └──────────┘ └──────────┘ └──────────┘ └──────────┘ └──────────────┘   │    │
│  │                                                                          │    │
│  └─────────────────────────────────────────────────────────────────────────┘    │
│                                                                                  │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Component Diagram

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                    B2B PORTAL COMPONENT DIAGRAM                                  │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                  │
│  ┌─────────────────┐                                                             │
│  │  B2B Customer   │                                                             │
│  │  (Web Browser)  │                                                             │
│  └────────┬────────┘                                                             │
│           │ HTTP/HTTPS                                                           │
│           ▼                                                                      │
│  ┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐            │
│  │   Nginx Load    │────►│   Odoo 19 CE    │────►│  PostgreSQL 16  │            │
│  │    Balancer     │     │   Application   │     │    Database     │            │
│  └─────────────────┘     └────────┬────────┘     └─────────────────┘            │
│                                   │                                              │
│                    ┌──────────────┼──────────────┐                              │
│                    │              │              │                              │
│                    ▼              ▼              ▼                              │
│            ┌──────────┐   ┌──────────┐   ┌──────────┐                          │
│            │  smart   │   │  Redis   │   │  Email   │                          │
│            │  _b2b_   │   │  Cache   │   │ Service  │                          │
│            │  portal  │   │          │   │          │                          │
│            └──────────┘   └──────────┘   └──────────┘                          │
│                                                                                  │
│  External Integrations:                                                          │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐                                         │
│  │  bKash   │ │  SMS GW  │ │ Document │                                         │
│  │ Gateway  │ │          │ │ Storage  │                                         │
│  └──────────┘ └──────────┘ └──────────┘                                         │
│                                                                                  │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### 2.3 Integration Points

| Integration | Type | Protocol | Purpose |
|-------------|------|----------|---------|
| Odoo Core | Internal | Python ORM | Base functionality |
| Website Module | Internal | XML-RPC | Public catalog sync |
| Email Service | External | SMTP/SendGrid | Notifications |
| SMS Gateway | External | REST API | OTP and alerts |
| Payment Gateway | External | REST API | Future payments |
| Document Storage | Internal | File Store | Document upload |

---

## 3. Database Schema Design

### 3.1 Entity Relationship Diagram

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                    B2B DATABASE SCHEMA - ENTITY RELATIONSHIPS                    │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                  │
│  ┌──────────────────┐                                                            │
│  │   res_partner    │                                                            │
│  │  (Inherited)     │                                                            │
│  ├──────────────────┤                                                            │
│  │ is_b2b_customer  │                                                            │
│  │ b2b_tier_id      │────┐                                                       │
│  │ credit_limit     │    │                                                       │
│  │ trade_license    │    │                                                       │
│  │ bin_tin_number   │    │                                                       │
│  │ approval_status  │    │                                                       │
│  └────────┬─────────┘    │                                                       │
│           │              │                                                       │
│           │ 1:N          │ N:1                                                   │
│           ▼              ▼                                                       │
│  ┌──────────────────┐  ┌──────────────────┐                                     │
│  │   b2b_partner    │  │   b2b_tier_config│                                     │
│  │    (Extension)   │  ├──────────────────┤                                     │
│  ├──────────────────┤  │ name             │                                     │
│  │ partner_id       │  │ code             │                                     │
│  │ sub_partner_ids  │  │ min_order_value  │                                     │
│  │ primary_contact  │  │ credit_days      │                                     │
│  │ business_type    │  │ discount_percent │                                     │
│  │ annual_turnover  │  │ priority         │                                     │
│  │ employee_count   │  │ is_active        │                                     │
│  └────────┬─────────┘  └──────────────────┘                                     │
│           │                                                                      │
│           │ 1:N                                                                  │
│           ▼                                                                      │
│  ┌──────────────────┐                                                            │
│  │  b2b_user_mapping│                                                            │
│  ├──────────────────┤                                                            │
│  │ partner_id       │                                                            │
│  │ user_id          │                                                            │
│  │ role             │                                                            │
│  │ can_order        │                                                            │
│  │ can_approve      │                                                            │
│  │ approval_limit   │                                                            │
│  └──────────────────┘                                                            │
│                                                                                  │
│  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐               │
│  │b2b_pricelist_item│  │b2b_product_visible│ │b2b_registration_ │               │
│  ├──────────────────┤  ├──────────────────┤  │     workflow     │               │
│  │ tier_id          │  │ product_tmpl_id  │  ├──────────────────┤               │
│  │ product_tmpl_id  │  │ tier_ids         │  │ partner_id       │               │
│  │ min_quantity     │  │ is_b2b_eligible  │  │ submission_date  │               │
│  │ price            │  │ moq              │  │ review_date      │               │
│  │ currency_id      │  │ lead_time_days   │  │ approver_id      │               │
│  │ valid_from       │  │ bulk_uom         │  │ status           │               │
│  │ valid_until      │  │                  │  │ rejection_reason │               │
│  └──────────────────┘  └──────────────────┘  └──────────────────┘               │
│                                                                                  │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Table Definitions

#### 3.2.1 b2b_tier_config

```sql
-- B2B Customer Tier Configuration Table
CREATE TABLE b2b_tier_config (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(20) UNIQUE NOT NULL,
    description TEXT,
    
    -- Business Rules
    min_order_value DECIMAL(15,2) DEFAULT 0.00,
    max_order_value DECIMAL(15,2),
    min_monthly_volume DECIMAL(15,2),
    credit_days INTEGER DEFAULT 0,
    credit_limit DECIMAL(15,2) DEFAULT 0.00,
    
    -- Pricing
    discount_percent DECIMAL(5,2) DEFAULT 0.00,
    markup_percent DECIMAL(5,2) DEFAULT 0.00,
    
    -- Features
    allows_credit BOOLEAN DEFAULT FALSE,
    allows_bulk_order BOOLEAN DEFAULT TRUE,
    allows_standing_order BOOLEAN DEFAULT FALSE,
    priority_support BOOLEAN DEFAULT FALSE,
    dedicated_account_manager BOOLEAN DEFAULT FALSE,
    
    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    sequence INTEGER DEFAULT 10,
    
    -- Metadata
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Default Tiers
INSERT INTO b2b_tier_config (name, code, description, min_order_value, credit_days, discount_percent, allows_credit, sequence) VALUES
('Distributor', 'DISTRIBUTOR', 'Regional distributors with high volume', 50000.00, 30, 12.00, TRUE, 10),
('Retailer', 'RETAILER', 'Retail stores and shops', 10000.00, 15, 8.00, TRUE, 20),
('HORECA', 'HORECA', 'Hotels, Restaurants, Catering', 20000.00, 21, 5.00, TRUE, 30),
('Institutional', 'INSTITUTIONAL', 'Schools, Hospitals, Corporate', 30000.00, 30, 6.00, TRUE, 40),
('Government', 'GOVERNMENT', 'Government institutions', 25000.00, 45, 4.00, TRUE, 50);
```

#### 3.2.2 b2b_partner (Extension)

```sql
-- B2B Partner Extension Table
CREATE TABLE b2b_partner (
    id SERIAL PRIMARY KEY,
    partner_id INTEGER NOT NULL UNIQUE REFERENCES res_partner(id) ON DELETE CASCADE,
    
    -- B2B Classification
    b2b_tier_id INTEGER REFERENCES b2b_tier_config(id),
    b2b_status VARCHAR(20) DEFAULT 'pending', -- pending, approved, rejected, suspended
    
    -- Business Information
    business_type VARCHAR(50), -- retailer, distributor, hotel, restaurant, hospital, school, corporate
    business_category VARCHAR(50), -- horeca, institutional, government, other
    year_established INTEGER,
    annual_turnover DECIMAL(15,2),
    employee_count INTEGER,
    
    -- Registration Documents
    trade_license_number VARCHAR(100),
    trade_license_file VARCHAR(255),
    bin_number VARCHAR(50),
    tin_number VARCHAR(50),
    vat_registration VARCHAR(50),
    
    -- Credit Information
    credit_limit DECIMAL(15,2) DEFAULT 0.00,
    credit_used DECIMAL(15,2) DEFAULT 0.00,
    credit_available DECIMAL(15,2) GENERATED ALWAYS AS (credit_limit - credit_used) STORED,
    payment_terms INTEGER DEFAULT 0, -- days
    
    -- Contact Information
    primary_contact_name VARCHAR(100),
    primary_contact_phone VARCHAR(20),
    primary_contact_email VARCHAR(100),
    
    -- Delivery Preferences
    preferred_delivery_time VARCHAR(50),
    special_delivery_instructions TEXT,
    
    -- Approval Workflow
    submitted_by INTEGER REFERENCES res_users(id),
    submitted_date TIMESTAMP,
    reviewed_by INTEGER REFERENCES res_users(id),
    reviewed_date TIMESTAMP,
    approved_by INTEGER REFERENCES res_users(id),
    approved_date TIMESTAMP,
    rejection_reason TEXT,
    
    -- Metadata
    notes TEXT,
    internal_notes TEXT,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes for performance
CREATE INDEX idx_b2b_partner_tier ON b2b_partner(b2b_tier_id);
CREATE INDEX idx_b2b_partner_status ON b2b_partner(b2b_status);
CREATE INDEX idx_b2b_partner_business_type ON b2b_partner(business_type);
```

#### 3.2.3 b2b_user_mapping

```sql
-- B2B User Role Mapping Table
CREATE TABLE b2b_user_mapping (
    id SERIAL PRIMARY KEY,
    partner_id INTEGER NOT NULL REFERENCES res_partner(id) ON DELETE CASCADE,
    user_id INTEGER NOT NULL REFERENCES res_users(id) ON DELETE CASCADE,
    
    -- Role Definition
    role VARCHAR(30) NOT NULL DEFAULT 'user', -- admin, manager, purchaser, viewer
    is_primary BOOLEAN DEFAULT FALSE,
    
    -- Permissions
    can_view_catalog BOOLEAN DEFAULT TRUE,
    can_place_order BOOLEAN DEFAULT TRUE,
    can_view_orders BOOLEAN DEFAULT TRUE,
    can_view_invoices BOOLEAN DEFAULT TRUE,
    can_view_payments BOOLEAN DEFAULT TRUE,
    can_view_reports BOOLEAN DEFAULT FALSE,
    can_manage_users BOOLEAN DEFAULT FALSE,
    can_update_profile BOOLEAN DEFAULT TRUE,
    
    -- Approval Workflow
    can_approve_orders BOOLEAN DEFAULT FALSE,
    approval_limit DECIMAL(15,2) DEFAULT 0.00,
    requires_approval_above DECIMAL(15,2),
    
    -- Notification Preferences
    notify_order_confirmations BOOLEAN DEFAULT TRUE,
    notify_shipments BOOLEAN DEFAULT TRUE,
    notify_invoices BOOLEAN DEFAULT TRUE,
    notify_payments BOOLEAN DEFAULT TRUE,
    notify_promotions BOOLEAN DEFAULT TRUE,
    
    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    last_login_date TIMESTAMP,
    
    -- Metadata
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE(partner_id, user_id)
);

CREATE INDEX idx_b2b_user_mapping_partner ON b2b_user_mapping(partner_id);
CREATE INDEX idx_b2b_user_mapping_user ON b2b_user_mapping(user_id);
CREATE INDEX idx_b2b_user_mapping_active ON b2b_user_mapping(is_active);
```

#### 3.2.4 b2b_pricelist_item

```sql
-- B2B Tier-based Pricing Table
CREATE TABLE b2b_pricelist_item (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    
    -- Relationships
    tier_id INTEGER NOT NULL REFERENCES b2b_tier_config(id) ON DELETE CASCADE,
    product_tmpl_id INTEGER NOT NULL REFERENCES product_template(id) ON DELETE CASCADE,
    product_id INTEGER REFERENCES product_product(id) ON DELETE CASCADE,
    
    -- Pricing Rules
    min_quantity DECIMAL(10,2) DEFAULT 1.00,
    max_quantity DECIMAL(10,2),
    
    -- Price Calculation
    price_type VARCHAR(20) DEFAULT 'fixed', -- fixed, percentage, formula
    fixed_price DECIMAL(15,2),
    percent_discount DECIMAL(5,2),
    percent_markup DECIMAL(5,2),
    
    -- Base Price Reference
    base_pricelist_id INTEGER REFERENCES product_pricelist(id),
    base_price_field VARCHAR(30) DEFAULT 'list_price', -- list_price, standard_price
    
    -- Computed Price (cached)
    computed_price DECIMAL(15,2),
    currency_id INTEGER REFERENCES res_currency(id),
    
    -- Validity
    valid_from DATE DEFAULT CURRENT_DATE,
    valid_until DATE,
    
    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    
    -- Metadata
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE(tier_id, product_tmpl_id, product_id, min_quantity)
);

CREATE INDEX idx_b2b_pricelist_tier ON b2b_pricelist_item(tier_id);
CREATE INDEX idx_b2b_pricelist_product ON b2b_pricelist_item(product_tmpl_id);
CREATE INDEX idx_b2b_pricelist_valid ON b2b_pricelist_item(valid_from, valid_until);
CREATE INDEX idx_b2b_pricelist_active ON b2b_pricelist_item(is_active);
```

#### 3.2.5 b2b_product_visibility

```sql
-- B2B Product Visibility Rules Table
CREATE TABLE b2b_product_visibility (
    id SERIAL PRIMARY KEY,
    product_tmpl_id INTEGER NOT NULL REFERENCES product_template(id) ON DELETE CASCADE,
    
    -- B2B Eligibility
    is_b2b_eligible BOOLEAN DEFAULT TRUE,
    b2b_eligible_from DATE,
    b2b_eligible_until DATE,
    
    -- Tier Visibility (JSON array of tier IDs)
    visible_to_tier_ids JSONB DEFAULT '[]',
    
    -- Minimum Order Quantity
    moq DECIMAL(10,2) DEFAULT 1.00,
    moq_uom_id INTEGER REFERENCES uom_uom(id),
    
    -- Bulk Unit of Measure
    bulk_uom_id INTEGER REFERENCES uom_uom(id),
    bulk_uom_factor DECIMAL(10,4) DEFAULT 1.0000,
    
    -- Lead Time
    lead_time_days INTEGER DEFAULT 1,
    
    -- Special Requirements
    requires_quotation BOOLEAN DEFAULT FALSE,
    quotation_minimum_qty DECIMAL(10,2),
    
    -- Restrictions
    max_order_qty DECIMAL(10,2),
    max_order_per_month DECIMAL(15,2),
    
    -- Metadata
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE(product_tmpl_id)
);

CREATE INDEX idx_b2b_product_visibility_product ON b2b_product_visibility(product_tmpl_id);
CREATE INDEX idx_b2b_product_visibility_eligible ON b2b_product_visibility(is_b2b_eligible);
```

#### 3.2.6 b2b_registration_workflow

```sql
-- B2B Registration Workflow Tracking Table
CREATE TABLE b2b_registration_workflow (
    id SERIAL PRIMARY KEY,
    partner_id INTEGER NOT NULL REFERENCES res_partner(id) ON DELETE CASCADE,
    
    -- Submission
    submitted_by INTEGER REFERENCES res_users(id),
    submitted_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address INET,
    user_agent TEXT,
    
    -- Documents Submitted
    trade_license_submitted BOOLEAN DEFAULT FALSE,
    trade_license_verified BOOLEAN DEFAULT FALSE,
    bin_certificate_submitted BOOLEAN DEFAULT FALSE,
    tin_certificate_submitted BOOLEAN DEFAULT FALSE,
    bank_statement_submitted BOOLEAN DEFAULT FALSE,
    
    -- Review Process
    review_stage VARCHAR(30) DEFAULT 'submitted', -- submitted, documents_verified, credit_check, approved, rejected
    review_notes TEXT,
    
    -- Approval Chain
    document_verifier_id INTEGER REFERENCES res_users(id),
    document_verified_date TIMESTAMP,
    credit_checker_id INTEGER REFERENCES res_users(id),
    credit_checked_date TIMESTAMP,
    approver_id INTEGER REFERENCES res_users(id),
    approval_date TIMESTAMP,
    
    -- Final Status
    final_status VARCHAR(20) DEFAULT 'pending', -- pending, approved, rejected
    rejection_reason TEXT,
    rejection_category VARCHAR(50), -- incomplete_documents, credit_risk, business_type, other
    
    -- Notification Tracking
    notification_emails_sent JSONB DEFAULT '[]',
    
    -- Metadata
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_b2b_registration_partner ON b2b_registration_workflow(partner_id);
CREATE INDEX idx_b2b_registration_status ON b2b_registration_workflow(final_status);
CREATE INDEX idx_b2b_registration_stage ON b2b_registration_workflow(review_stage);
```

---

## 4. Module Specifications

### 4.1 Module Structure

```
smart_b2b_portal/
├── __init__.py
├── __manifest__.py
├── models/
│   ├── __init__.py
│   ├── res_partner.py
│   ├── b2b_tier_config.py
│   ├── b2b_partner.py
│   ├── b2b_user_mapping.py
│   ├── b2b_pricelist.py
│   ├── b2b_product.py
│   ├── b2b_registration.py
│   └── product_template.py
├── controllers/
│   ├── __init__.py
│   ├── main.py
│   ├── portal.py
│   └── api.py
├── views/
│   ├── b2b_tier_config_views.xml
│   ├── b2b_partner_views.xml
│   ├── b2b_registration_views.xml
│   ├── b2b_pricelist_views.xml
│   ├── b2b_product_views.xml
│   ├── b2b_portal_templates.xml
│   └── res_partner_views.xml
├── data/
│   ├── b2b_tier_data.xml
│   ├── b2b_email_templates.xml
│   ├── b2b_sequences.xml
│   └── b2b_cron.xml
├── security/
│   ├── ir.model.access.csv
│   └── b2b_security.xml
├── static/
│   ├── src/
│   │   ├── scss/
│   │   │   └── b2b_portal.scss
│   │   ├── js/
│   │   │   └── b2b_portal.js
│   │   └── xml/
│   │       └── b2b_portal.xml
│   └── description/
│       └── icon.png
└── report/
    ├── b2b_partner_report.xml
    └── b2b_pricelist_report.xml
```

### 4.2 Module Manifest

```python
# __manifest__.py
{
    'name': 'Smart Dairy B2B Portal',
    'version': '1.0.0',
    'category': 'Sales/B2B',
    'summary': 'B2B Marketplace Portal for Smart Dairy',
    'description': '''
        Smart Dairy B2B Portal
        ======================
        
        This module provides comprehensive B2B marketplace functionality including:
        
        * Partner Registration and Verification Workflow
        * Tier-based Customer Classification (Distributor, Retailer, HORECA, Institutional)
        * Dynamic Pricing Engine with Volume Discounts
        * Multi-user Account Management
        * B2B Product Catalog with Visibility Rules
        * Credit Limit Management
        * Bulk Ordering Capabilities
        
        Features:
        ---------
        - Self-service partner registration with document upload
        - Automated approval workflow with email notifications
        - Tier-based pricing calculation
        - Role-based access control for B2B users
        - Integration with Odoo Sales, Inventory, and Accounting
        
        Author: Smart Dairy Development Team
    ''',
    'author': 'Smart Dairy Ltd.',
    'website': 'https://smartdairybd.com',
    'depends': [
        'base',
        'web',
        'website',
        'website_sale',
        'sale',
        'product',
        'account',
        'mail',
        'portal',
    ],
    'data': [
        # Security
        'security/b2b_security.xml',
        'security/ir.model.access.csv',
        
        # Data
        'data/b2b_sequences.xml',
        'data/b2b_tier_data.xml',
        'data/b2b_email_templates.xml',
        'data/b2b_cron.xml',
        
        # Views
        'views/b2b_tier_config_views.xml',
        'views/b2b_partner_views.xml',
        'views/b2b_registration_views.xml',
        'views/b2b_pricelist_views.xml',
        'views/b2b_product_views.xml',
        'views/res_partner_views.xml',
        
        # Portal
        'views/b2b_portal_templates.xml',
        
        # Reports
        'report/b2b_partner_report.xml',
        'report/b2b_pricelist_report.xml',
    ],
    'assets': {
        'web.assets_backend': [
            'smart_b2b_portal/static/src/scss/b2b_portal.scss',
            'smart_b2b_portal/static/src/js/b2b_portal.js',
        ],
        'web.assets_frontend': [
            'smart_b2b_portal/static/src/scss/b2b_portal_public.scss',
            'smart_b2b_portal/static/src/js/b2b_portal_public.js',
        ],
    },
    'installable': True,
    'application': True,
    'auto_install': False,
    'license': 'LGPL-3',
}
```

---

## 5. API Specifications

### 5.1 REST API Endpoints

#### 5.1.1 Partner Registration API

```yaml
# API Specification for B2B Partner Registration

paths:
  /api/b2b/v1/partners/register:
    post:
      summary: Register new B2B partner
      description: Submit B2B partner registration application
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                company_name:
                  type: string
                  required: true
                business_type:
                  type: string
                  enum: [retailer, distributor, hotel, restaurant, hospital, school, corporate]
                contact_name:
                  type: string
                  required: true
                email:
                  type: string
                  format: email
                  required: true
                phone:
                  type: string
                  required: true
                address:
                  type: object
                  properties:
                    street:
                      type: string
                    city:
                      type: string
                    state:
                      type: string
                    zip:
                      type: string
                trade_license_number:
                  type: string
                bin_number:
                  type: string
                tin_number:
                  type: string
                annual_turnover:
                  type: number
                employee_count:
                  type: integer
      responses:
        201:
          description: Registration submitted successfully
          content:
            application/json:
              schema:
                type: object
                properties:
                  registration_id:
                    type: string
                  status:
                    type: string
                    enum: [pending]
                  message:
                    type: string
        400:
          description: Validation error
        409:
          description: Duplicate registration

  /api/b2b/v1/partners/{partner_id}:
    get:
      summary: Get partner details
      parameters:
        - name: partner_id
          in: path
          required: true
          schema:
            type: integer
      responses:
        200:
          description: Partner details retrieved
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/B2BPartner'
    put:
      summary: Update partner information
      parameters:
        - name: partner_id
          in: path
          required: true
          schema:
            type: integer
      requestBody:
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/B2BPartnerUpdate'
      responses:
        200:
          description: Partner updated successfully

  /api/b2b/v1/partners/{partner_id}/status:
    get:
      summary: Get partner approval status
      responses:
        200:
          description: Status retrieved
          content:
            application/json:
              schema:
                type: object
                properties:
                  status:
                    type: string
                    enum: [pending, approved, rejected, suspended]
                  submitted_date:
                    type: string
                    format: date-time
                  approved_date:
                    type: string
                    format: date-time
                  rejection_reason:
                    type: string

components:
  schemas:
    B2BPartner:
      type: object
      properties:
        id:
          type: integer
        company_name:
          type: string
        business_type:
          type: string
        tier:
          type: object
          properties:
            id:
              type: integer
            name:
              type: string
            code:
              type: string
        credit_limit:
          type: number
        credit_used:
          type: number
        status:
          type: string

    B2BPartnerUpdate:
      type: object
      properties:
        contact_name:
          type: string
        phone:
          type: string
        address:
          type: object
```

#### 5.1.2 Pricing API

```yaml
# API Specification for B2B Pricing

paths:
  /api/b2b/v1/pricing/calculate:
    post:
      summary: Calculate price for B2B customer
      description: Returns tier-based pricing for given products
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                partner_id:
                  type: integer
                  required: true
                items:
                  type: array
                  items:
                    type: object
                    properties:
                      product_id:
                        type: integer
                      quantity:
                        type: number
                      uom_id:
                        type: integer
      responses:
        200:
          description: Pricing calculated successfully
          content:
            application/json:
              schema:
                type: object
                properties:
                  items:
                    type: array
                    items:
                      type: object
                      properties:
                        product_id:
                          type: integer
                        base_price:
                          type: number
                        tier_discount:
                          type: number
                        volume_discount:
                          type: number
                        final_price:
                          type: number
                        total:
                          type: number
                  subtotal:
                    type: number
                  discount_total:
                    type: number
                  grand_total:
                    type: number

  /api/b2b/v1/pricing/tiers/{tier_id}/products:
    get:
      summary: Get tier-specific pricing for products
      parameters:
        - name: tier_id
          in: path
          required: true
          schema:
            type: integer
        - name: category_id
          in: query
          schema:
            type: integer
        - name: search
          in: query
          schema:
            type: string
      responses:
        200:
          description: Product pricing list
          content:
            application/json:
              schema:
                type: object
                properties:
                  products:
                    type: array
                    items:
                      type: object
                      properties:
                        product_id:
                          type: integer
                        name:
                          type: string
                        default_code:
                          type: string
                        moq:
                          type: number
                        tier_price:
                          type: number
                        currency:
                          type: string
                        available_qty:
                          type: number
                  total_count:
                    type: integer
                  page:
                    type: integer
```

#### 5.1.3 User Management API

```yaml
# API Specification for B2B User Management

paths:
  /api/b2b/v1/partners/{partner_id}/users:
    get:
      summary: List B2B users for partner
      parameters:
        - name: partner_id
          in: path
          required: true
          schema:
            type: integer
      responses:
        200:
          description: List of users
          content:
            application/json:
              schema:
                type: array
                items:
                  $ref: '#/components/schemas/B2BUser'

    post:
      summary: Create new B2B user
      parameters:
        - name: partner_id
          in: path
          required: true
          schema:
            type: integer
      requestBody:
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/B2BUserCreate'
      responses:
        201:
          description: User created successfully

  /api/b2b/v1/partners/{partner_id}/users/{user_id}:
    put:
      summary: Update B2B user
      parameters:
        - name: partner_id
          in: path
          required: true
          schema:
            type: integer
        - name: user_id
          in: path
          required: true
          schema:
            type: integer
      requestBody:
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/B2BUserUpdate'
      responses:
        200:
          description: User updated successfully

    delete:
      summary: Deactivate B2B user
      responses:
        204:
          description: User deactivated

components:
  schemas:
    B2BUser:
      type: object
      properties:
        id:
          type: integer
        name:
          type: string
        email:
          type: string
        phone:
          type: string
        role:
          type: string
          enum: [admin, manager, purchaser, viewer]
        is_active:
          type: boolean
        permissions:
          type: object
          properties:
            can_place_order:
              type: boolean
            can_approve_orders:
              type: boolean
            approval_limit:
              type: number

    B2BUserCreate:
      type: object
      properties:
        name:
          type: string
          required: true
        email:
          type: string
          required: true
        phone:
          type: string
        role:
          type: string
          required: true
        can_place_order:
          type: boolean
        can_approve_orders:
          type: boolean
        approval_limit:
          type: number
```

---

## 6. Daily Task Allocation

### 6.1 Day 1: Project Setup and Architecture

| Developer | Tasks | Deliverables |
|-----------|-------|--------------|
| **Lead Dev** | • Create module structure<br>• Set up Git repository<br>• Define database schema<br>• Create base model files | Module skeleton, Git repo, Schema documentation |
| **Backend Dev** | • Review Phase 1 codebase<br>• Analyze integration points<br>• Document API requirements | Integration analysis document |
| **Frontend Dev** | • Set up React development environment<br>• Review UI/UX mockups<br>• Create component library structure | Dev environment ready, Component structure |

### 6.2 Day 2: Tier Configuration Module

| Developer | Tasks | Deliverables |
|-----------|-------|--------------|
| **Lead Dev** | • Implement b2b_tier_config model<br>• Create tier configuration views<br>• Add demo data | Tier model, Views, Demo data |
| **Backend Dev** | • Create tier data XML<br>• Write unit tests for tier model<br>• Document tier rules | Unit tests, Documentation |
| **Frontend Dev** | • Create tier selection component<br>• Style tier badges<br>• Build tier comparison UI | Tier components |

### 6.3 Day 3: B2B Partner Extension

| Developer | Tasks | Deliverables |
|-----------|-------|--------------|
| **Lead Dev** | • Extend res_partner model<br>• Create b2b_partner model<br>• Implement partner classification | Partner models, Classification logic |
| **Backend Dev** | • Create partner views<br>• Add partner search filters<br>• Implement partner reports | Partner views, Filters, Reports |
| **Frontend Dev** | • Build partner profile UI<br>• Create partner dashboard widget<br>• Style partner cards | Partner UI components |

### 6.4 Day 4: Registration Workflow

| Developer | Tasks | Deliverables |
|-----------|-------|--------------|
| **Lead Dev** | • Implement registration workflow model<br>• Create approval state machine<br>• Add email notification triggers | Workflow model, State machine, Email triggers |
| **Backend Dev** | • Build registration form<br>• Implement document upload<br>• Create approval views | Registration forms, Upload handlers |
| **Frontend Dev** | • Design registration wizard<br>• Create form validation<br>• Build document upload UI | Registration wizard, Validation |

### 6.5 Day 5: User Management

| Developer | Tasks | Deliverables |
|-----------|-------|--------------|
| **Lead Dev** | • Implement b2b_user_mapping model<br>• Create role-based permissions<br>• Add user invitation system | User mapping, Permissions, Invitations |
| **Backend Dev** | • Create user management views<br>• Implement user CRUD operations<br>• Add audit logging | User views, CRUD, Audit |
| **Frontend Dev** | • Build user list interface<br>• Create role selector<br>• Design permission matrix | User UI, Role selector |

### 6.6 Day 6: Pricing Engine Foundation

| Developer | Tasks | Deliverables |
|-----------|-------|--------------|
| **Lead Dev** | • Design pricing engine architecture<br>• Implement b2b_pricelist_item model<br>• Create price calculation service | Pricing architecture, Model, Service |
| **Backend Dev** | • Build pricing admin views<br>• Implement price import/export<br>• Create pricing reports | Pricing views, Import/Export |
| **Frontend Dev** | • Create price display component<br>• Build tier price comparison<br>• Style price tables | Price components |

### 6.7 Day 7: Product Visibility

| Developer | Tasks | Deliverables |
|-----------|-------|--------------|
| **Lead Dev** | • Implement b2b_product_visibility model<br>• Create visibility rules engine<br>• Add MOQ validation | Visibility model, Rules engine |
| **Backend Dev** | • Extend product template<br>• Create B2B catalog views<br>• Implement product search filters | Product extensions, Catalog |
| **Frontend Dev** | • Build B2B product grid<br>• Create MOQ indicators<br>• Design product cards | Product grid, Indicators |

### 6.8 Day 8: Portal Integration

| Developer | Tasks | Deliverables |
|-----------|-------|--------------|
| **Lead Dev** | • Create portal controllers<br>• Implement authentication checks<br>• Build API endpoints | Controllers, Auth, APIs |
| **Backend Dev** | • Create portal page templates<br>• Implement breadcrumbs<br>• Add SEO meta tags | Templates, Navigation |
| **Frontend Dev** | • Build portal layout<br>• Create navigation menu<br>• Design header/footer | Portal layout, Navigation |

### 6.9 Day 9: Dashboard and Reporting

| Developer | Tasks | Deliverables |
|-----------|-------|--------------|
| **Lead Dev** | • Implement dashboard widgets<br>• Create KPI calculation service<br>• Build report generators | Dashboard, KPIs, Reports |
| **Backend Dev** | • Create report templates<br>• Implement data aggregation<br>• Add export functionality | Reports, Aggregation |
| **Frontend Dev** | • Build dashboard UI<br>• Create chart components<br>• Design report viewer | Dashboard UI, Charts |

### 6.10 Day 10: Testing and Review

| Developer | Tasks | Deliverables |
|-----------|-------|--------------|
| **Lead Dev** | • Write integration tests<br>• Perform code review<br>• Document APIs<br>• Fix critical bugs | Tests, Reviews, API docs |
| **Backend Dev** | • Complete unit tests<br>• Run data migration tests<br>• Document models<br>• Fix bugs | Unit tests, Docs |
| **Frontend Dev** | • Complete UI tests<br>• Cross-browser testing<br>• Document components<br>• Fix UI bugs | UI tests, Docs |

---

## 7. Code Implementation

### 7.1 Core Models

#### 7.1.1 b2b_tier_config.py

```python
# -*- coding: utf-8 -*-
from odoo import models, fields, api, _
from odoo.exceptions import ValidationError


class B2BTierConfig(models.Model):
    """B2B Customer Tier Configuration
    
    Defines the different tiers for B2B customers with their
    associated benefits, pricing rules, and credit terms.
    """
    _name = 'b2b.tier.config'
    _description = 'B2B Tier Configuration'
    _order = 'sequence, id'
    
    # Basic Information
    name = fields.Char(string='Tier Name', required=True, translate=True)
    code = fields.Char(string='Code', required=True, index=True)
    description = fields.Text(string='Description', translate=True)
    
    # Display
    sequence = fields.Integer(string='Sequence', default=10)
    color = fields.Integer(string='Color Index')
    
    # Business Rules
    min_order_value = fields.Monetary(
        string='Minimum Order Value',
        currency_field='currency_id',
        default=0.0
    )
    max_order_value = fields.Monetary(
        string='Maximum Order Value',
        currency_field='currency_id'
    )
    min_monthly_volume = fields.Monetary(
        string='Minimum Monthly Volume',
        currency_field='currency_id',
        help='Minimum monthly purchase volume to maintain tier'
    )
    
    # Credit Terms
    credit_days = fields.Integer(
        string='Credit Days',
        default=0,
        help='Number of days for payment'
    )
    credit_limit = fields.Monetary(
        string='Default Credit Limit',
        currency_field='currency_id',
        default=0.0
    )
    
    # Pricing
    discount_percent = fields.Float(
        string='Discount %',
        default=0.0,
        help='Default discount percentage for this tier'
    )
    markup_percent = fields.Float(
        string='Markup %',
        default=0.0,
        help='Markup percentage for special pricing'
    )
    
    # Features
    allows_credit = fields.Boolean(
        string='Allows Credit',
        default=False,
        help='Whether this tier can purchase on credit'
    )
    allows_bulk_order = fields.Boolean(
        string='Allows Bulk Order',
        default=True
    )
    allows_standing_order = fields.Boolean(
        string='Allows Standing Orders',
        default=False
    )
    priority_support = fields.Boolean(
        string='Priority Support',
        default=False
    )
    dedicated_account_manager = fields.Boolean(
        string='Dedicated Account Manager',
        default=False
    )
    
    # Status
    is_active = fields.Boolean(string='Active', default=True)
    
    # Relations
    currency_id = fields.Many2one(
        'res.currency',
        string='Currency',
        default=lambda self: self.env.company.currency_id
    )
    partner_count = fields.Integer(
        string='Partners',
        compute='_compute_partner_count'
    )
    
    # Metadata
    create_uid = fields.Many2one('res.users', string='Created by')
    create_date = fields.Datetime(string='Created on')
    write_uid = fields.Many2one('res.users', string='Last Updated by')
    write_date = fields.Datetime(string='Last Updated on')
    
    _sql_constraints = [
        ('code_unique', 'UNIQUE(code)', 'Tier code must be unique!'),
    ]
    
    @api.depends('code')
    def _compute_partner_count(self):
        """Compute number of partners in this tier"""
        for tier in self:
            tier.partner_count = self.env['b2b.partner'].search_count([
                ('b2b_tier_id', '=', tier.id),
                ('is_active', '=', True)
            ])
    
    @api.constrains('discount_percent', 'markup_percent')
    def _check_percentages(self):
        """Validate percentage values"""
        for tier in self:
            if tier.discount_percent < 0 or tier.discount_percent > 100:
                raise ValidationError(_('Discount percentage must be between 0 and 100'))
            if tier.markup_percent < 0 or tier.markup_percent > 100:
                raise ValidationError(_('Markup percentage must be between 0 and 100'))
    
    @api.constrains('min_order_value', 'max_order_value')
    def _check_order_values(self):
        """Validate order value constraints"""
        for tier in self:
            if tier.max_order_value and tier.min_order_value > tier.max_order_value:
                raise ValidationError(_('Minimum order value cannot exceed maximum'))
    
    def name_get(self):
        """Return display name with code"""
        result = []
        for tier in self:
            name = f"[{tier.code}] {tier.name}"
            result.append((tier.id, name))
        return result
    
    def toggle_active(self):
        """Toggle active status"""
        for tier in self:
            tier.is_active = not tier.is_active
    
    def get_partners(self):
        """Get all partners in this tier"""
        self.ensure_one()
        return self.env['b2b.partner'].search([
            ('b2b_tier_id', '=', self.id)
        ])
    
    def action_view_partners(self):
        """Action to view partners in this tier"""
        self.ensure_one()
        partners = self.get_partners()
        return {
            'name': _('B2B Partners - %s') % self.name,
            'type': 'ir.actions.act_window',
            'res_model': 'b2b.partner',
            'view_mode': 'tree,form',
            'domain': [('id', 'in', partners.ids)],
            'context': {'default_b2b_tier_id': self.id},
        }
```

#### 7.1.2 b2b_partner.py

```python
# -*- coding: utf-8 -*-
from odoo import models, fields, api, _
from odoo.exceptions import ValidationError, UserError
from datetime import datetime, timedelta


class B2BPartner(models.Model):
    """B2B Partner Extension
    
    Extends res.partner with B2B-specific fields and functionality
    for managing wholesale customers and their specific requirements.
    """
    _name = 'b2b.partner'
    _description = 'B2B Partner'
    _inherits = {'res.partner': 'partner_id'}
    _order = 'create_date desc'
    
    # Inherited partner
    partner_id = fields.Many2one(
        'res.partner',
        string='Partner',
        required=True,
        ondelete='cascade',
        index=True
    )
    
    # B2B Classification
    b2b_tier_id = fields.Many2one(
        'b2b.tier.config',
        string='B2B Tier',
        required=True,
        tracking=True
    )
    b2b_status = fields.Selection([
        ('draft', 'Draft'),
        ('pending', 'Pending Approval'),
        ('documents_required', 'Documents Required'),
        ('under_review', 'Under Review'),
        ('approved', 'Approved'),
        ('rejected', 'Rejected'),
        ('suspended', 'Suspended'),
        ('blacklisted', 'Blacklisted')
    ], string='Status', default='draft', tracking=True)
    
    # Business Information
    business_type = fields.Selection([
        ('retailer', 'Retailer'),
        ('distributor', 'Distributor'),
        ('hotel', 'Hotel'),
        ('restaurant', 'Restaurant'),
        ('catering', 'Catering'),
        ('hospital', 'Hospital'),
        ('school', 'School'),
        ('corporate', 'Corporate Office'),
        ('government', 'Government'),
        ('other', 'Other')
    ], string='Business Type', required=True)
    business_category = fields.Selection([
        ('horeca', 'HORECA'),
        ('institutional', 'Institutional'),
        ('retail', 'Retail'),
        ('government', 'Government'),
        ('other', 'Other')
    ], string='Business Category', compute='_compute_business_category', store=True)
    year_established = fields.Integer(string='Year Established')
    annual_turnover = fields.Monetary(
        string='Annual Turnover',
        currency_field='currency_id'
    )
    employee_count = fields.Integer(string='Number of Employees')
    
    # Registration Documents
    trade_license_number = fields.Char(string='Trade License Number')
    trade_license_file = fields.Binary(string='Trade License')
    trade_license_filename = fields.Char(string='Trade License Filename')
    bin_number = fields.Char(string='BIN Number')
    tin_number = fields.Char(string='TIN Number')
    vat_registration = fields.Char(string='VAT Registration')
    
    # Credit Information
    credit_limit = fields.Monetary(
        string='Credit Limit',
        currency_field='currency_id',
        default=0.0
    )
    credit_used = fields.Monetary(
        string='Credit Used',
        currency_field='currency_id',
        compute='_compute_credit_used',
        store=True
    )
    credit_available = fields.Monetary(
        string='Available Credit',
        currency_field='currency_id',
        compute='_compute_credit_available',
        store=True
    )
    payment_terms = fields.Integer(
        string='Payment Terms (Days)',
        default=0
    )
    payment_method_preferred = fields.Selection([
        ('bank_transfer', 'Bank Transfer'),
        ('check', 'Check'),
        ('cash', 'Cash'),
        ('mobile_banking', 'Mobile Banking')
    ], string='Preferred Payment Method')
    
    # Contact Information
    primary_contact_name = fields.Char(string='Primary Contact Name')
    primary_contact_phone = fields.Char(string='Primary Contact Phone')
    primary_contact_email = fields.Char(string='Primary Contact Email')
    
    # Delivery Preferences
    preferred_delivery_time = fields.Selection([
        ('morning', 'Morning (6AM - 12PM)'),
        ('afternoon', 'Afternoon (12PM - 4PM)'),
        ('evening', 'Evening (4PM - 8PM)'),
        ('any', 'Any Time')
    ], string='Preferred Delivery Time')
    special_delivery_instructions = fields.Text(string='Special Delivery Instructions')
    
    # Approval Workflow
    submitted_by = fields.Many2one('res.users', string='Submitted By')
    submitted_date = fields.Datetime(string='Submitted Date')
    reviewed_by = fields.Many2one('res.users', string='Reviewed By')
    reviewed_date = fields.Datetime(string='Reviewed Date')
    approved_by = fields.Many2one('res.users', string='Approved By')
    approved_date = fields.Datetime(string='Approved Date')
    rejection_reason = fields.Text(string='Rejection Reason')
    
    # User Management
    user_count = fields.Integer(
        string='Number of Users',
        compute='_compute_user_count'
    )
    primary_user_id = fields.Many2one(
        'res.users',
        string='Primary User',
        compute='_compute_primary_user'
    )
    
    # Activity
    last_order_date = fields.Date(string='Last Order Date')
    total_orders = fields.Integer(string='Total Orders', default=0)
    total_revenue = fields.Monetary(
        string='Total Revenue',
        currency_field='currency_id',
        default=0.0
    )
    
    # Status
    is_active = fields.Boolean(string='Active', default=True)
    
    # Notes
    notes = fields.Text(string='Public Notes')
    internal_notes = fields.Text(string='Internal Notes')
    
    @api.depends('business_type')
    def _compute_business_category(self):
        """Compute business category from business type"""
        category_map = {
            'hotel': 'horeca',
            'restaurant': 'horeca',
            'catering': 'horeca',
            'hospital': 'institutional',
            'school': 'institutional',
            'corporate': 'institutional',
            'government': 'government',
            'retailer': 'retail',
            'distributor': 'retail',
            'other': 'other'
        }
        for partner in self:
            partner.business_category = category_map.get(
                partner.business_type, 'other'
            )
    
    @api.depends('partner_id.sale_order_ids')
    def _compute_credit_used(self):
        """Compute credit used from unpaid invoices"""
        for partner in self:
            # Calculate total outstanding amount
            invoices = self.env['account.move'].search([
                ('partner_id', '=', partner.partner_id.id),
                ('move_type', '=', 'out_invoice'),
                ('payment_state', '!=', 'paid'),
                ('state', '=', 'posted')
            ])
            partner.credit_used = sum(invoices.mapped('amount_residual'))
    
    @api.depends('credit_limit', 'credit_used')
    def _compute_credit_available(self):
        """Compute available credit"""
        for partner in self:
            partner.credit_available = partner.credit_limit - partner.credit_used
    
    @api.depends('partner_id')
    def _compute_user_count(self):
        """Compute number of users for this partner"""
        for partner in self:
            partner.user_count = self.env['b2b.user.mapping'].search_count([
                ('partner_id', '=', partner.partner_id.id)
            ])
    
    def _compute_primary_user(self):
        """Compute primary user for this partner"""
        for partner in self:
            primary = self.env['b2b.user.mapping'].search([
                ('partner_id', '=', partner.partner_id.id),
                ('is_primary', '=', True)
            ], limit=1)
            partner.primary_user_id = primary.user_id if primary else False
    
    @api.constrains('credit_limit')
    def _check_credit_limit(self):
        """Validate credit limit against tier configuration"""
        for partner in self:
            if partner.b2b_tier_id and partner.credit_limit > partner.b2b_tier_id.credit_limit * 2:
                raise ValidationError(_(
                    'Credit limit cannot exceed twice the tier default. '
                    'Maximum allowed: %s'
                ) % partner.b2b_tier_id.credit_limit * 2)
    
    @api.model
    def create(self, vals):
        """Override create to set default tier credit limit"""
        if vals.get('b2b_tier_id') and not vals.get('credit_limit'):
            tier = self.env['b2b.tier.config'].browse(vals['b2b_tier_id'])
            vals['credit_limit'] = tier.credit_limit
            vals['payment_terms'] = tier.credit_days
        return super(B2BPartner, self).create(vals)
    
    def write(self, vals):
        """Override write to track status changes"""
        if 'b2b_status' in vals:
            vals.update({
                'reviewed_by': self.env.user.id,
                'reviewed_date': fields.Datetime.now()
            })
            if vals['b2b_status'] == 'approved':
                vals.update({
                    'approved_by': self.env.user.id,
                    'approved_date': fields.Datetime.now()
                })
        return super(B2BPartner, self).write(vals)
    
    def action_submit_for_approval(self):
        """Submit partner for approval"""
        self.ensure_one()
        if self.b2b_status not in ['draft', 'documents_required']:
            raise UserError(_('Partner is already submitted or processed'))
        
        # Validate required documents
        if not self.trade_license_number:
            raise UserError(_('Trade license number is required'))
        
        self.write({
            'b2b_status': 'pending',
            'submitted_by': self.env.user.id,
            'submitted_date': fields.Datetime.now()
        })
        
        # Send notification to approvers
        self._send_approval_notification()
    
    def action_approve(self):
        """Approve B2B partner"""
        self.ensure_one()
        if self.b2b_status != 'under_review':
            raise UserError(_('Partner must be under review to approve'))
        
        self.write({
            'b2b_status': 'approved',
            'approved_by': self.env.user.id,
            'approved_date': fields.Datetime.now()
        })
        
        # Send approval notification
        self._send_approved_notification()
    
    def action_reject(self, reason):
        """Reject B2B partner"""
        self.ensure_one()
        self.write({
            'b2b_status': 'rejected',
            'rejection_reason': reason,
            'reviewed_by': self.env.user.id,
            'reviewed_date': fields.Datetime.now()
        })
        
        # Send rejection notification
        self._send_rejected_notification(reason)
    
    def action_suspend(self, reason):
        """Suspend B2B partner"""
        self.ensure_one()
        self.write({
            'b2b_status': 'suspended',
            'internal_notes': reason
        })
    
    def action_activate(self):
        """Activate suspended partner"""
        self.ensure_one()
        self.write({
            'b2b_status': 'approved'
        })
    
    def _send_approval_notification(self):
        """Send notification to approvers"""
        # Implementation for sending email notification
        template = self.env.ref('smart_b2b_portal.email_template_new_registration')
        if template:
            template.send_mail(self.id, force_send=True)
    
    def _send_approved_notification(self):
        """Send approval notification to partner"""
        template = self.env.ref('smart_b2b_portal.email_template_registration_approved')
        if template:
            template.send_mail(self.id, force_send=True)
    
    def _send_rejected_notification(self, reason):
        """Send rejection notification to partner"""
        template = self.env.ref('smart_b2b_portal.email_template_registration_rejected')
        if template:
            template.send_mail(self.id, force_send=True)
    
    def get_pricing_for_product(self, product_id, quantity=1):
        """Get tier-based pricing for a product"""
        self.ensure_one()
        return self.env['b2b.pricelist.item'].get_price(
            tier_id=self.b2b_tier_id.id,
            product_id=product_id,
            quantity=quantity
        )
    
    def can_place_order(self, amount):
        """Check if partner can place order of given amount"""
        self.ensure_one()
        
        # Check status
        if self.b2b_status != 'approved':
            return False, _('Account is not approved')
        
        # Check tier minimum order
        if amount < self.b2b_tier_id.min_order_value:
            return False, _('Order amount below minimum for tier')
        
        # Check tier maximum order
        if self.b2b_tier_id.max_order_value and amount > self.b2b_tier_id.max_order_value:
            return False, _('Order amount exceeds maximum for tier')
        
        # Check credit
        if self.credit_available < amount and not self.b2b_tier_id.allows_credit:
            return False, _('Insufficient credit')
        
        return True, _('Order can be placed')
    
    def action_view_users(self):
        """Action to view B2B users"""
        self.ensure_one()
        return {
            'name': _('B2B Users'),
            'type': 'ir.actions.act_window',
            'res_model': 'b2b.user.mapping',
            'view_mode': 'tree,form',
            'domain': [('partner_id', '=', self.partner_id.id)],
            'context': {'default_partner_id': self.partner_id.id},
        }
    
    def action_view_orders(self):
        """Action to view B2B orders"""
        self.ensure_one()
        return {
            'name': _('B2B Orders'),
            'type': 'ir.actions.act_window',
            'res_model': 'sale.order',
            'view_mode': 'tree,form',
            'domain': [('partner_id', '=', self.partner_id.id)],
        }
```

---

## 8. Testing Protocols

### 8.1 Unit Testing

```python
# tests/test_b2b_tier.py
from odoo.tests.common import TransactionCase
from odoo.exceptions import ValidationError


class TestB2BTier(TransactionCase):
    """Test cases for B2B Tier Configuration"""
    
    def setUp(self):
        super(TestB2BTier, self).setUp()
        self.tier_obj = self.env['b2b.tier.config']
        
    def test_create_tier(self):
        """Test creating a new B2B tier"""
        tier = self.tier_obj.create({
            'name': 'Test Tier',
            'code': 'TEST',
            'min_order_value': 1000.00,
            'credit_days': 15,
            'discount_percent': 10.0
        })
        self.assertEqual(tier.name, 'Test Tier')
        self.assertEqual(tier.code, 'TEST')
        
    def test_unique_code_constraint(self):
        """Test unique code constraint"""
        self.tier_obj.create({
            'name': 'First Tier',
            'code': 'UNIQUE'
        })
        with self.assertRaises(Exception):
            self.tier_obj.create({
                'name': 'Duplicate Tier',
                'code': 'UNIQUE'
            })
    
    def test_discount_percentage_validation(self):
        """Test discount percentage validation"""
        with self.assertRaises(ValidationError):
            self.tier_obj.create({
                'name': 'Invalid Tier',
                'code': 'INVALID',
                'discount_percent': 150.0
            })
    
    def test_order_value_validation(self):
        """Test order value validation"""
        with self.assertRaises(ValidationError):
            self.tier_obj.create({
                'name': 'Invalid Tier',
                'code': 'INVAL2',
                'min_order_value': 10000.00,
                'max_order_value': 5000.00
            })
```

### 8.2 Integration Testing

```python
# tests/test_b2b_partner_integration.py
from odoo.tests.common import TransactionCase


class TestB2BPartnerIntegration(TransactionCase):
    """Integration tests for B2B Partner workflow"""
    
    def setUp(self):
        super(TestB2BPartnerIntegration, self).setUp()
        self.tier = self.env['b2b.tier.config'].create({
            'name': 'Test Distributor',
            'code': 'TEST_DIST',
            'min_order_value': 5000.00,
            'credit_days': 30,
            'discount_percent': 10.0,
            'allows_credit': True
        })
    
    def test_complete_registration_workflow(self):
        """Test complete partner registration workflow"""
        # Create partner
        partner = self.env['b2b.partner'].create({
            'name': 'Test Business',
            'b2b_tier_id': self.tier.id,
            'business_type': 'distributor',
            'trade_license_number': 'TL123456',
            'email': 'test@example.com',
            'phone': '01712345678'
        })
        
        # Submit for approval
        partner.action_submit_for_approval()
        self.assertEqual(partner.b2b_status, 'pending')
        
        # Approve
        partner.write({'b2b_status': 'under_review'})
        partner.action_approve()
        self.assertEqual(partner.b2b_status, 'approved')
    
    def test_credit_limit_calculation(self):
        """Test credit limit and available credit calculation"""
        partner = self.env['b2b.partner'].create({
            'name': 'Test Business',
            'b2b_tier_id': self.tier.id,
            'business_type': 'distributor',
            'credit_limit': 100000.00
        })
        
        self.assertEqual(partner.credit_available, 100000.00)
```

### 8.3 Test Cases Summary

| Test Category | Number of Tests | Coverage Target |
|---------------|-----------------|-----------------|
| Model Unit Tests | 25 | 100% model methods |
| Controller Tests | 15 | All API endpoints |
| Workflow Tests | 10 | All approval flows |
| Integration Tests | 12 | Cross-module scenarios |
| Security Tests | 8 | Access rights |

---

## 9. Deliverables and Verification

### 9.1 Deliverables Checklist

| ID | Deliverable | Owner | Verification Method | Status |
|----|-------------|-------|---------------------|--------|
| M1-D1 | B2B Tier Configuration Model | Lead Dev | Model tests pass | ⬜ |
| M1-D2 | B2B Partner Extension Model | Lead Dev | Model tests pass | ⬜ |
| M1-D3 | Registration Workflow Model | Lead Dev | Workflow tests pass | ⬜ |
| M1-D4 | User Management Model | Lead Dev | Permission tests pass | ⬜ |
| M1-D5 | Pricing Engine Model | Lead Dev | Price calculation tests pass | ⬜ |
| M1-D6 | Product Visibility Model | Lead Dev | Visibility tests pass | ⬜ |
| M1-D7 | Backend Views and Forms | Backend Dev | UI testing | ⬜ |
| M1-D8 | Portal Templates | Backend Dev | Frontend testing | ⬜ |
| M1-D9 | API Controllers | Lead Dev | API tests pass | ⬜ |
| M1-D10 | Documentation | All Dev | Review complete | ⬜ |

### 9.2 Success Criteria

- [ ] All database tables created with proper indexes
- [ ] All models implemented with unit tests (>80% coverage)
- [ ] Registration workflow functional end-to-end
- [ ] Tier-based pricing calculates correctly
- [ ] Portal pages render without errors
- [ ] API endpoints respond correctly
- [ ] Security rules enforce access control
- [ ] Documentation complete and reviewed

---

## 10. Appendices

### Appendix A: Installation Commands

```bash
# Install Module
./odoo-bin -c odoo.conf -i smart_b2b_portal -d smart_dairy_prod

# Update Module
./odoo-bin -c odoo.conf -u smart_b2b_portal -d smart_dairy_prod

# Run Tests
./odoo-bin -c odoo.conf -u smart_b2b_portal --test-enable -d smart_dairy_test
```

### Appendix B: Sample Data Loading

```bash
# Load Demo Data
./odoo-bin -c odoo.conf -i smart_b2b_portal --load-demo-data -d smart_dairy_dev
```

### Appendix C: Migration Scripts

```python
# migrations/1.0.0/pre-migration.py

def migrate(cr, version):
    """Pre-migration script for B2B Portal"""
    # Create temporary tables if needed
    cr.execute("""
        CREATE TABLE IF NOT EXISTS temp_b2b_migration (
            old_id INTEGER,
            new_id INTEGER,
            model VARCHAR(100)
        )
    """)
```

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | February 2026 | Technical Team | Initial Milestone 1 Documentation |

---

*This document is part of the Smart Dairy Phase 2 Implementation Roadmap.*

*Smart Dairy Ltd. (C) 2026*
