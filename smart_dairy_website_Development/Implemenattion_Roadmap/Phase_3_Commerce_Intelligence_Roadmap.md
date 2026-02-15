# Smart Dairy Smart Portal + ERP System
## Phase 3: Commerce & Intelligence Implementation Roadmap
### B2B Marketplace, IoT Integration, Advanced Analytics & Business Intelligence

---

**Document Information**

| Attribute | Details |
|-----------|---------|
| **Document Version** | 1.0 - Comprehensive Edition |
| **Release Date** | February 2026 |
| **Classification** | Implementation Guide - Internal Use |
| **Total Duration** | 100 Days (10 Milestones x 10 Days) |
| **Timeline** | Days 201-300 |
| **Team Size** | 3 Full Stack Developers |
| **Budget** | BDT 1.45 Crore (~$132,000 USD) |

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Phase 3 Dependencies & Prerequisites](#2-phase-3-dependencies--prerequisites)
3. [Team Structure & Developer Allocation](#3-team-structure--developer-allocation)
4. [Technical Architecture Evolution](#4-technical-architecture-evolution)
5. [Milestone Roadmap - Phase 3](#5-milestone-roadmap---phase-3)
6. [Quality Assurance Framework](#6-quality-assurance-framework)
7. [Budget Breakdown](#7-budget-breakdown)
8. [Technical Appendices](#8-technical-appendices)

---

## 1. EXECUTIVE SUMMARY

### 1.1 Phase 3 Vision

Phase 3 transforms Smart Dairy into a comprehensive B2B commerce and intelligent farming enterprise by launching the B2B Marketplace Portal for wholesale customers, implementing IoT-driven Smart Farm Management with real-time sensor integration, and deploying Business Intelligence capabilities for data-driven decision making.

### 1.2 Primary Objectives

| Objective | Target | Success Metric |
|-----------|--------|----------------|
| B2B Marketplace | Full-featured wholesale portal | 100+ B2B partners registered |
| Partner Tier System | Bronze/Silver/Gold/Platinum tiers | Tier-based pricing active |
| Credit Management | Automated credit control | <2% default rate |
| IoT Sensor Network | 50+ sensors deployed | 99.5% uptime |
| Smart Farm AI | Predictive health alerts | 25% reduction in disease |
| Real-time Dashboard | Live farm monitoring | <5 second data latency |
| Business Intelligence | Executive dashboards | 50+ KPIs tracked |
| Multi-Warehouse | 3+ locations managed | Inventory sync <1 min |

---


## 2. PHASE 3 DEPENDENCIES & PREREQUISITES

### 2.1 Phase 2 Deliverables Required

| Deliverable | Status Required | Verification Method |
|-------------|-----------------|---------------------|
| E-commerce Platform | Operational | 100+ active subscriptions |
| Payment Gateways | Production | >95% success rate |
| Mobile Apps | Published | Available on Play/App Store |
| Customer Database | Complete | 1,000+ registered customers |
| Product Catalog | Mature | 50+ SKUs with pricing |
| Farm Management Module | Base operational | 255 animals registered |
| Subscription Engine | Working | Auto-renewal processing |
| Elasticsearch | Running | Product search <500ms |

### 2.2 New Technology Stack Additions

| Layer | Technology | Version | Purpose |
|-------|-----------|---------|---------|
| IoT Gateway | Eclipse Mosquitto | 2.0+ | MQTT Broker |
| Time-Series DB | TimescaleDB | 2.12+ | Sensor data storage |
| Stream Processing | Apache Kafka | 3.6+ | Real-time data streams |
| BI Platform | Apache Superset | 3.0+ | Dashboards & reporting |
| Data Warehouse | ClickHouse | 23.x+ | Analytics storage |
| ML Framework | scikit-learn | 1.3+ | Predictive models |
| Cache | Redis Cluster | 7.x+ | Distributed caching |

---

## 3. TEAM STRUCTURE & DEVELOPER ALLOCATION

### 3.1 Development Team Composition

| Role | Count | Experience Level | Primary Focus |
|------|-------|------------------|---------------|
| Technical Lead / Senior Full Stack Developer | 1 | 5+ years | B2B Architecture, IoT Backend, DevOps |
| Backend-Focused Full Stack Developer | 1 | 3+ years | Odoo B2B modules, BI, Data pipelines |
| Frontend-Focused Full Stack Developer | 1 | 3+ years | B2B Portal UI, Dashboards, IoT visualization |

### 3.2 Developer Profiles & Responsibilities

#### Developer 1: Technical Lead (Lead Dev)

**Phase 3 Responsibilities:**
- B2B Portal architecture and integration design
- IoT infrastructure setup (MQTT broker, sensor integration)
- TimescaleDB and data pipeline architecture
- Real-time streaming infrastructure (Kafka)
- DevOps for new components
- Security architecture for B2B and IoT
- Technical documentation

**Key Deliverables:**
- B2B API gateway design
- IoT data ingestion pipeline
- TimescaleDB schema and retention policies
- Kafka stream processing topology
- Security policies for B2B portal

#### Developer 2: Backend Developer (Backend Dev)

**Phase 3 Responsibilities:**
- Custom Odoo B2B module development
- Partner management and credit control
- Pricing engine and tier management
- BI data models and ETL processes
- ML model integration for predictive analytics
- Approval workflow engine
- Standing orders and recurring B2B orders

**Key Deliverables:**
- smart_dairy_b2b module
- Pricing engine with tier calculations
- Credit management system
- Apache Superset integration
- Predictive health models
- Standing order automation

#### Developer 3: Frontend Developer (Frontend Dev)

**Phase 3 Responsibilities:**
- B2B Portal frontend development
- Real-time dashboard implementation
- IoT sensor visualization
- BI dashboard and chart components
- Data table components for large datasets
- Mobile-responsive B2B interface
- Real-time notification system

**Key Deliverables:**
- B2B portal responsive UI
- Real-time farm dashboard
- Sensor data visualization charts
- Executive BI dashboards
- Quick Order Pad interface
- Approval workflow UI

---

## 4. TECHNICAL ARCHITECTURE EVOLUTION

### 4.1 Phase 3 System Architecture

```
+-----------------------------------------------------------------------------+
|                         PHASE 3 SYSTEM ARCHITECTURE                          |
|                       (B2B + IoT + BI Integration)                          |
+-----------------------------------------------------------------------------+
|                                                                              |
|   PRESENTATION LAYER                                                         |
|   +-------------------+  +-------------------+  +-------------------+       |
|   |   B2B Web Portal  |  |  BI Dashboard     |  |  Farm Dashboard   |       |
|   |   (Wholesale)     |  |  (Analytics)      |  |  (Real-time)      |       |
|   +-------------------+  +-------------------+  +-------------------+       |
|                                                                              |
|   APPLICATION LAYER                                                          |
|   +---------------------------------------------------------------------+   |
|   |                    Odoo 19 + Custom Modules                          |   |
|   |  +----------------+  +----------------+  +----------------------+  |   |
|   |  | smart_dairy_b2b|  | smart_iot_mgmt |  | smart_bi_analytics   |  |   |
|   |  | - Partner Mgmt |  | - Sensor Mgmt  |  | - KPI Engine         |  |   |
|   |  | - Pricing Eng  |  | - Alert Rules  |  | - Report Builder     |  |   |
|   |  | - Credit Ctrl  |  | - Data Ingest  |  | - Predictive Models  |  |   |
|   |  +----------------+  +----------------+  +----------------------+  |   |
|   +---------------------------------------------------------------------+   |
|                                                                              |
|   INTEGRATION LAYER                                                          |
|   +----------------+  +----------------+  +----------------+               |
|   | MQTT Broker    |  | Kafka Streams  |  | API Gateway    |               |
|   | (Mosquitto)    |  | (Real-time)    |  | (B2B/IoT)      |               |
|   +----------------+  +----------------+  +----------------+               |
|                                                                              |
|   DATA LAYER                                                                 |
|   +----------------+  +----------------+  +----------------+               |
|   | PostgreSQL     |  | TimescaleDB    |  | ClickHouse     |               |
|   | (Primary)      |  | (IoT Data)     |  | (Analytics)    |               |
|   +----------------+  +----------------+  +----------------+               |
|   +----------------+  +----------------+  +----------------+               |
|   | Redis Cluster  |  | MinIO/S3       |  | Elasticsearch  |               |
|   | (Cache)        |  | (Files)        |  | (Search)       |               |
|   +----------------+  +----------------+  +----------------+               |
|                                                                              |
|   DEVICE LAYER                                                               |
|   +----------------+  +----------------+  +----------------+               |
|   | RFID Readers   |  | Temp Sensors   |  | Activity       |               |
|   | Milk Meters    |  | Env Sensors    |  | Collars        |               |
|   +----------------+  +----------------+  +----------------+               |
|                                                                              |
+-----------------------------------------------------------------------------+
```

---


## 5. MILESTONE ROADMAP - PHASE 3

### MILESTONE 21: B2B Portal Foundation (Days 201-210)

#### 21.1 Objective Statement

Establish the foundational B2B portal infrastructure including partner registration workflows, basic catalog views, and portal authentication. Create the B2B module structure in Odoo and implement the core data models for partner management.

#### 21.2 Success Criteria
- B2B module created and installed in Odoo
- Partner registration form with document upload functional
- Basic B2B catalog view with product listing
- Partner approval workflow implemented
- Multi-user account structure for B2B partners
- Role-based access control for B2B portal

#### 21.3 Deliverables Table

| ID | Deliverable | Owner | Verification Method | Status |
|----|-------------|-------|---------------------|--------|
| M21-D1 | B2B module scaffold | Lead Dev | Module structure complete | Pending |
| M21-D2 | Partner data models | Backend Dev | Models with all fields | Pending |
| M21-D3 | Registration workflow | Backend Dev | End-to-end registration | Pending |
| M21-D4 | Document upload system | Lead Dev | File storage working | Pending |
| M21-D5 | Approval workflow | Backend Dev | Approve/reject working | Pending |
| M21-D6 | B2B portal frontend | Frontend Dev | Portal pages built | Pending |
| M21-D7 | Multi-user accounts | Backend Dev | Sub-user creation | Pending |
| M21-D8 | RBAC implementation | Lead Dev | Permissions working | Pending |
| M21-D9 | B2B catalog view | Frontend Dev | Product grid display | Pending |
| M21-D10 | M21 Testing & Review | All Dev | Test cases passed | Pending |

#### 21.4 Technical Implementation

##### B2B Module Structure

```python
# smart_dairy_b2b/__manifest__.py
{
    'name': 'Smart Dairy B2B Portal',
    'version': '1.0.0',
    'category': 'Sales',
    'summary': 'B2B Wholesale Portal for Smart Dairy',
    'description': '''
        B2B Portal Features:
        - Partner registration and approval workflow
        - Tier-based pricing and catalogs
        - Credit management and payment terms
        - Bulk ordering and quick order pad
        - Approval workflows for large orders
        - Standing orders and recurring deliveries
    ''',
    'depends': [
        'base',
        'sale',
        'account',
        'stock',
        'website',
        'portal',
        'smart_website_ext',
    ],
    'data': [
        # Security
        'security/b2b_security.xml',
        'security/ir.model.access.csv',
        
        # Data
        'data/b2b_partner_tiers.xml',
        'data/approval_workflow.xml',
        'data/ir_sequence.xml',
        
        # Views
        'views/res_partner_views.xml',
        'views/b2b_partner_views.xml',
        'views/b2b_pricelist_views.xml',
        'views/b2b_order_views.xml',
        'views/portal_templates.xml',
        
        # Reports
        'reports/b2b_partner_report.xml',
        'reports/b2b_order_report.xml',
    ],
    'assets': {
        'web.assets_frontend': [
            'smart_dairy_b2b/static/src/scss/b2b_portal.scss',
            'smart_dairy_b2b/static/src/js/b2b_portal.js',
        ],
    },
    'installable': True,
    'application': False,
    'license': 'LGPL-3',
}
```

##### B2B Partner Model

```python
# models/b2b_partner.py
from odoo import models, fields, api, _
from odoo.exceptions import ValidationError

class ResPartnerB2B(models.Model):
    _inherit = 'res.partner'
    
    # B2B Partner Classification
    is_b2b_partner = fields.Boolean(string='Is B2B Partner', default=False)
    b2b_partner_type = fields.Selection([
        ('distributor', 'Distributor'),
        ('retailer', 'Retailer'),
        ('horeca', 'HORECA'),
        ('institutional', 'Institutional'),
    ], string='B2B Partner Type', tracking=True)
    
    # Partner Tier System
    partner_tier = fields.Selection([
        ('bronze', 'Bronze'),
        ('silver', 'Silver'),
        ('gold', 'Gold'),
        ('platinum', 'Platinum'),
    ], string='Partner Tier', default='bronze', tracking=True,
    help='Tier determines pricing level and credit terms')
    
    # Business Information
    trade_license_number = fields.Char(string='Trade License Number')
    trade_license_file = fields.Binary(string='Trade License Document')
    trade_license_filename = fields.Char(string='Trade License Filename')
    bin_number = fields.Char(string='BIN Number', help='Business Identification Number')
    tin_number = fields.Char(string='TIN Number', help='Tax Identification Number')
    vat_registration = fields.Char(string='VAT Registration Number')
    
    # Approval Status
    approval_status = fields.Selection([
        ('draft', 'Draft'),
        ('pending', 'Pending Approval'),
        ('under_review', 'Under Review'),
        ('approved', 'Approved'),
        ('rejected', 'Rejected'),
        ('suspended', 'Suspended'),
    ], string='Approval Status', default='draft', tracking=True)
    
    approval_stage = fields.Selection([
        ('documents', 'Document Verification'),
        ('credit_check', 'Credit Assessment'),
        ('final_approval', 'Final Approval'),
    ], string='Approval Stage')
    
    approved_by = fields.Many2one('res.users', string='Approved By', readonly=True)
    approved_date = fields.Datetime(string='Approved Date', readonly=True)
    rejection_reason = fields.Text(string='Rejection Reason')
    reviewer_notes = fields.Text(string='Reviewer Notes')
    
    # Application Information
    application_date = fields.Datetime(string='Application Date', 
                                       default=lambda self: fields.Datetime.now())
    application_source = fields.Selection([
        ('website', 'Website'),
        ('referral', 'Referral'),
        ('sales_team', 'Sales Team'),
        ('event', 'Event'),
    ], string='Application Source', default='website')
    
    # Multi-location Support
    delivery_location_ids = fields.One2many(
        'b2b.delivery.location', 'partner_id', string='Delivery Locations')
    
    # B2B User Management
    b2b_user_ids = fields.One2many('b2b.portal.user', 'partner_id', string='Portal Users')
    primary_contact_id = fields.Many2one('b2b.portal.user', string='Primary Contact')
    
    # Order Statistics
    total_b2b_orders = fields.Integer(string='Total B2B Orders', compute='_compute_order_stats')
    total_b2b_revenue = fields.Monetary(string='Total B2B Revenue', compute='_compute_order_stats')
    last_order_date = fields.Date(string='Last Order Date', compute='_compute_order_stats')
    
    @api.depends('b2b_order_ids')
    def _compute_order_stats(self):
        for partner in self:
            orders = partner.b2b_order_ids.filtered(lambda o: o.state == 'delivered')
            partner.total_b2b_orders = len(orders)
            partner.total_b2b_revenue = sum(orders.mapped('amount_total'))
            partner.last_order_date = orders and max(orders.mapped('order_date')) or False
    
    def action_submit_for_approval(self):
        self.ensure_one()
        if not self.trade_license_number or not self.bin_number:
            raise ValidationError(_('Trade License and BIN Number are required for approval.'))
        self.write({'approval_status': 'pending', 'approval_stage': 'documents'})
        # Send notification to approval team
        self._notify_approval_team()
    
    def action_approve(self):
        self.ensure_one()
        self.write({
            'approval_status': 'approved',
            'approved_by': self.env.user.id,
            'approved_date': fields.Datetime.now(),
        })
        # Create default price list based on tier
        self._create_tier_pricelist()
        # Send welcome email
        self._send_approval_notification()
    
    def action_reject(self, reason):
        self.ensure_one()
        self.write({
            'approval_status': 'rejected',
            'rejection_reason': reason,
        })
        self._send_rejection_notification(reason)
    
    def _create_tier_pricelist(self):
        # Create partner-specific price list based on tier
        pass


class B2BDeliveryLocation(models.Model):
    _name = 'b2b.delivery.location'
    _description = 'B2B Delivery Location'
    _order = 'sequence, id'
    
    partner_id = fields.Many2one('res.partner', string='Partner', required=True, 
                                  domain=[('is_b2b_partner', '=', True)], ondelete='cascade')
    sequence = fields.Integer(string='Sequence', default=10)
    name = fields.Char(string='Location Name', required=True)
    
    # Address
    address = fields.Text(string='Full Address', required=True)
    district = fields.Char(string='District')
    thana = fields.Char(string='Thana/Upazila')
    postcode = fields.Char(string='Postcode')
    
    # Contact
    contact_name = fields.Char(string='Contact Person', required=True)
    contact_phone = fields.Char(string='Contact Phone', required=True)
    contact_email = fields.Char(string='Contact Email')
    
    # Delivery Preferences
    is_default = fields.Boolean(string='Default Delivery Location', default=False)
    delivery_instructions = fields.Text(string='Delivery Instructions')
    preferred_delivery_time = fields.Selection([
        ('morning', 'Morning (8AM-12PM)'),
        ('afternoon', 'Afternoon (12PM-4PM)'),
        ('evening', 'Evening (4PM-8PM)'),
    ], string='Preferred Delivery Time')
    
    # Geolocation
    latitude = fields.Float(string='Latitude', digits=(10, 7))
    longitude = fields.Float(string='Longitude', digits=(10, 7))
    
    # Operating Hours (for commercial locations)
    opening_time = fields.Float(string='Opening Time')
    closing_time = fields.Float(string='Closing Time')
    
    active = fields.Boolean(string='Active', default=True)


class B2BPortalUser(models.Model):
    _name = 'b2b.portal.user'
    _description = 'B2B Portal User'
    _inherits = {'res.users': 'user_id'}
    
    user_id = fields.Many2one('res.users', string='User', required=True, ondelete='cascade')
    partner_id = fields.Many2one('res.partner', string='B2B Partner', required=True,
                                  domain=[('is_b2b_partner', '=', True)], ondelete='cascade')
    
    # Role and Permissions
    role = fields.Selection([
        ('admin', 'Administrator'),
        ('manager', 'Manager'),
        ('buyer', 'Buyer'),
        ('viewer', 'Viewer'),
    ], string='Role', default='buyer', required=True)
    
    # Permissions
    can_place_orders = fields.Boolean(string='Can Place Orders', default=True)
    can_approve_orders = fields.Boolean(string='Can Approve Orders', default=False)
    order_approval_limit = fields.Monetary(string='Order Approval Limit', 
                                           currency_field='currency_id', default=0.0)
    can_view_invoices = fields.Boolean(string='Can View Invoices', default=True)
    can_make_payments = fields.Boolean(string='Can Make Payments', default=False)
    can_view_reports = fields.Boolean(string='Can View Reports', default=False)
    can_manage_users = fields.Boolean(string='Can Manage Users', default=False)
    
    # Approval Workflow
    approver_ids = fields.Many2many('b2b.portal.user', 'b2b_user_approver_rel',
                                     'user_id', 'approver_id', string='Approvers')
    
    # Contact Information
    phone = fields.Char(string='Phone', related='user_id.phone', readonly=False)
    mobile = fields.Char(string='Mobile', related='user_id.mobile', readonly=False)
    
    # Status
    is_primary = fields.Boolean(string='Primary Contact', default=False)
    active = fields.Boolean(string='Active', default=True)
```

#### 21.5 Daily Task Allocation (Days 201-210)

| Day | Lead Dev Tasks | Backend Dev Tasks | Frontend Dev Tasks |
|-----|----------------|-------------------|-------------------|
| 201 | Create B2B module structure; Setup security framework | Define partner model schema; Create data models | Design B2B portal wireframes |
| 202 | Configure module dependencies; Setup access rights | Implement partner tier fields; Create tier data | Build portal header/footer components |
| 203 | Implement document upload system; File storage | Create delivery location model; Location fields | Design registration form UI |
| 204 | Setup approval workflow framework; State machine | Implement approval status logic; State transitions | Build registration form frontend |
| 205 | Configure email notification system; Templates | Create B2B portal user model; User management | Implement file upload UI components |
| 206 | Implement RBAC system; Permission framework | Setup multi-user account logic; Role definitions | Build login/auth for B2B portal |
| 207 | Create API endpoints for partner CRUD | Implement approval workflow methods; Business logic | Design partner dashboard layout |
| 208 | Integration testing with existing modules | Create demo B2B partners; Test data | Build catalog grid view component |
| 209 | Security audit; Performance optimization | Unit tests for B2B models; Fix bugs | Responsive testing; Mobile optimization |
| 210 | M21 review; Documentation; Bug fixes | Backend documentation; API docs | Frontend documentation; M21 demo |

---


### MILESTONE 22: B2B Partner & Credit Management (Days 211-220)

#### 22.1 Objective Statement

Implement comprehensive credit management system for B2B partners including credit limit assignment, credit utilization tracking, aging reports, and automated credit hold functionality. Build tier-based pricing engine with volume discounts.

#### 22.2 Success Criteria
- Credit limit assignment and management working
- Real-time credit utilization calculation
- Automated credit hold on exceeded limits
- Aging report generation (30/60/90 days)
- Tier-based pricing (Bronze/Silver/Gold/Platinum) implemented
- Volume-based discount calculations functional

#### 22.3 Deliverables Table

| ID | Deliverable | Owner | Verification Method | Status |
|----|-------------|-------|---------------------|--------|
| M22-D1 | Credit limit model | Backend Dev | Credit fields working | Pending |
| M22-D2 | Credit utilization engine | Lead Dev | Real-time calculation | Pending |
| M22-D3 | Credit hold automation | Backend Dev | Auto-hold on exceeded | Pending |
| M22-D4 | Aging reports | Backend Dev | 30/60/90 day reports | Pending |
| M22-D5 | Tier pricing models | Backend Dev | Tier structure complete | Pending |
| M22-D6 | Volume discount engine | Lead Dev | Tier pricing calculation | Pending |
| M22-D7 | Credit dashboard UI | Frontend Dev | Credit status display | Pending |
| M22-D8 | Pricing display UI | Frontend Dev | Tier prices visible | Pending |
| M22-D9 | Collection workflow | Backend Dev | Follow-up reminders | Pending |
| M22-D10 | M22 Testing | All Dev | Test cases passed | Pending |

#### 22.4 Technical Implementation

##### Credit Management Model

```python
# models/b2b_credit.py
from odoo import models, fields, api, _
from odoo.exceptions import ValidationError

class ResPartnerCredit(models.Model):
    _inherit = 'res.partner'
    
    # Credit Management Fields
    credit_limit = fields.Monetary(string='Credit Limit', currency_field='currency_id',
                                   default=0.0, tracking=True)
    credit_used = fields.Monetary(string='Credit Used', currency_field='currency_id',
                                  compute='_compute_credit_usage', store=True)
    credit_available = fields.Monetary(string='Credit Available', currency_field='currency_id',
                                       compute='_compute_credit_usage', store=True)
    credit_utilization_percent = fields.Float(string='Credit Utilization %',
                                              compute='_compute_credit_usage', store=True)
    
    # Credit Terms
    credit_term_days = fields.Integer(string='Credit Term (Days)', default=30,
                                      help='Number of days for payment')
    payment_terms = fields.Many2one('account.payment.term', string='Payment Terms')
    credit_status = fields.Selection([
        ('active', 'Active'),
        ('on_hold', 'On Hold'),
        ('suspended', 'Suspended'),
        ('cash_only', 'Cash Only'),
    ], string='Credit Status', default='active', tracking=True)
    
    # Credit History
    credit_application_date = fields.Date(string='Credit Application Date')
    credit_approved_date = fields.Date(string='Credit Approved Date')
    credit_approved_by = fields.Many2one('res.users', string='Credit Approved By')
    credit_review_date = fields.Date(string='Next Credit Review Date')
    
    # Aging Buckets
    aging_current = fields.Monetary(string='Current (0-30 days)', 
                                    compute='_compute_aging', store=True)
    aging_31_60 = fields.Monetary(string='31-60 Days', 
                                  compute='_compute_aging', store=True)
    aging_61_90 = fields.Monetary(string='61-90 Days', 
                                  compute='_compute_aging', store=True)
    aging_over_90 = fields.Monetary(string='Over 90 Days', 
                                    compute='_compute_aging', store=True)
    
    @api.depends('b2b_invoice_ids', 'b2b_invoice_ids.amount_residual', 
                 'b2b_invoice_ids.invoice_date_due')
    def _compute_aging(self):
        today = fields.Date.today()
        for partner in self:
            invoices = partner.b2b_invoice_ids.filtered(
                lambda i: i.state == 'posted' and i.amount_residual > 0)
            
            partner.aging_current = sum(
                inv.amount_residual for inv in invoices 
                if (today - inv.invoice_date_due).days <= 30
            )
            partner.aging_31_60 = sum(
                inv.amount_residual for inv in invoices 
                if 30 < (today - inv.invoice_date_due).days <= 60
            )
            partner.aging_61_90 = sum(
                inv.amount_residual for inv in invoices 
                if 60 < (today - inv.invoice_date_due).days <= 90
            )
            partner.aging_over_90 = sum(
                inv.amount_residual for inv in invoices 
                if (today - inv.invoice_date_due).days > 90
            )
    
    @api.depends('aging_current', 'aging_31_60', 'aging_61_90', 'aging_over_90', 'credit_limit')
    def _compute_credit_usage(self):
        for partner in self:
            partner.credit_used = (partner.aging_current + partner.aging_31_60 + 
                                   partner.aging_61_90 + partner.aging_over_90)
            partner.credit_available = partner.credit_limit - partner.credit_used
            partner.credit_utilization_percent = (
                (partner.credit_used / partner.credit_limit * 100) 
                if partner.credit_limit > 0 else 0
            )
    
    def action_update_credit_status(self):
        for partner in self:
            if partner.credit_utilization_percent >= 100:
                partner.credit_status = 'on_hold'
            elif partner.credit_utilization_percent >= 90:
                partner.credit_status = 'cash_only'
            else:
                partner.credit_status = 'active'
    
    def check_credit_available(self, amount):
        self.ensure_one()
        if self.credit_status in ['on_hold', 'suspended']:
            return False, _('Account is on credit hold')
        if amount > self.credit_available:
            return False, _('Insufficient credit limit')
        return True, _('Credit approved')


class B2BCreditHistory(models.Model):
    _name = 'b2b.credit.history'
    _description = 'B2B Credit History'
    _order = 'date desc'
    
    partner_id = fields.Many2one('res.partner', string='Partner', required=True)
    date = fields.Datetime(string='Date', default=lambda self: fields.Datetime.now())
    user_id = fields.Many2one('res.users', string='User', default=lambda self: self.env.user)
    
    change_type = fields.Selection([
        ('increase', 'Increase'),
        ('decrease', 'Decrease'),
        ('hold', 'Hold'),
        ('release', 'Release'),
        ('review', 'Review'),
    ], string='Change Type', required=True)
    
    old_limit = fields.Monetary(string='Old Limit', currency_field='currency_id')
    new_limit = fields.Monetary(string='New Limit', currency_field='currency_id')
    reason = fields.Text(string='Reason', required=True)
```

##### Tier-Based Pricing Engine

```python
# models/b2b_pricing.py
from odoo import models, fields, api, _

class B2BPriceList(models.Model):
    _name = 'b2b.pricelist'
    _description = 'B2B Partner Price List'
    _order = 'sequence'
    
    name = fields.Char(string='Price List Name', required=True)
    sequence = fields.Integer(string='Sequence', default=10)
    
    # Tier Assignment
    partner_tier = fields.Selection([
        ('bronze', 'Bronze'),
        ('silver', 'Silver'),
        ('gold', 'Gold'),
        ('platinum', 'Platinum'),
    ], string='Partner Tier', required=True)
    
    # Validity
    date_start = fields.Date(string='Valid From', required=True)
    date_end = fields.Date(string='Valid Until')
    active = fields.Boolean(string='Active', default=True)
    
    # Price Lines
    line_ids = fields.One2many('b2b.pricelist.line', 'pricelist_id', string='Price Lines')
    
    # Volume Discount Configuration
    volume_discount_enabled = fields.Boolean(string='Enable Volume Discounts', default=True)
    
    _sql_constraints = [
        ('unique_tier_per_period', 
         'UNIQUE(partner_tier, date_start, date_end)', 
         'Only one price list per tier for a given period!')
    ]


class B2BPriceListLine(models.Model):
    _name = 'b2b.pricelist.line'
    _description = 'B2B Price List Line'
    
    pricelist_id = fields.Many2one('b2b.pricelist', string='Price List', required=True, ondelete='cascade')
    product_id = fields.Many2one('product.product', string='Product', required=True)
    product_uom = fields.Many2one('uom.uom', string='Unit of Measure', 
                                   related='product_id.uom_id', readonly=True)
    
    # Base Pricing
    base_price = fields.Float(string='Base Price', required=True, digits='Product Price')
    cost_price = fields.Float(string='Cost Price', digits='Product Price')
    
    # Tier Pricing (Quantity Breaks)
    tier_1_min_qty = fields.Float(string='Tier 1 Min Qty', default=1.0)
    tier_1_price = fields.Float(string='Tier 1 Price', digits='Product Price')
    
    tier_2_min_qty = fields.Float(string='Tier 2 Min Qty', default=10.0)
    tier_2_price = fields.Float(string='Tier 2 Price', digits='Product Price')
    tier_2_discount = fields.Float(string='Tier 2 Discount %', default=3.0)
    
    tier_3_min_qty = fields.Float(string='Tier 3 Min Qty', default=50.0)
    tier_3_price = fields.Float(string='Tier 3 Price', digits='Product Price')
    tier_3_discount = fields.Float(string='Tier 3 Discount %', default=5.0)
    
    tier_4_min_qty = fields.Float(string='Tier 4 Min Qty', default=100.0)
    tier_4_price = fields.Float(string='Tier 4 Price', digits='Product Price')
    tier_4_discount = fields.Float(string='Tier 4 Discount %', default=8.0)
    
    # Special Pricing
    promotional_price = fields.Float(string='Promotional Price', digits='Product Price')
    promo_date_start = fields.Date(string='Promo Start Date')
    promo_date_end = fields.Date(string='Promo End Date')
    
    # Custom pricing for specific partners
    is_custom_price = fields.Boolean(string='Custom Price', default=False)
    partner_id = fields.Many2one('res.partner', string='Specific Partner',
                                  domain=[('is_b2b_partner', '=', True)])
    
    @api.onchange('base_price', 'tier_2_discount', 'tier_3_discount', 'tier_4_discount')
    def _onchange_calculate_tier_prices(self):
        if self.base_price:
            self.tier_1_price = self.base_price
            self.tier_2_price = self.base_price * (1 - self.tier_2_discount / 100)
            self.tier_3_price = self.base_price * (1 - self.tier_3_discount / 100)
            self.tier_4_price = self.base_price * (1 - self.tier_4_discount / 100)
    
    def get_price_for_quantity(self, quantity, check_promo=True):
        self.ensure_one()
        
        # Check promotional price first
        if check_promo and self.promotional_price:
            today = fields.Date.today()
            if (not self.promo_date_start or self.promo_date_start <= today) and \
               (not self.promo_date_end or self.promo_date_end >= today):
                return self.promotional_price
        
        # Return tier-based price
        if quantity >= self.tier_4_min_qty:
            return self.tier_4_price
        elif quantity >= self.tier_3_min_qty:
            return self.tier_3_price
        elif quantity >= self.tier_2_min_qty:
            return self.tier_2_price
        return self.tier_1_price
```

---


### MILESTONE 23: B2B Ordering & Approval Workflows (Days 221-230)

#### 23.1 Objective Statement

Implement the complete B2B ordering system including quick order pad, bulk CSV upload, approval workflows for large orders, and standing orders for recurring deliveries. Integrate B2B orders with the main sales order system.

#### 23.2 Success Criteria
- Quick order pad (spreadsheet-like interface) functional
- CSV/Excel bulk order upload working
- Multi-level approval workflow for orders
- Standing orders with auto-generation
- Order templates for frequent purchases
- Split shipment capability
- Backorder management

#### 23.3 Technical Implementation

##### B2B Order Model

```python
# models/b2b_order.py
from odoo import models, fields, api, _
from odoo.exceptions import ValidationError, UserError

class B2BOrder(models.Model):
    _name = 'b2b.order'
    _description = 'B2B Order'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'order_date desc, id desc'
    
    name = fields.Char(string='Order Reference', required=True, copy=False,
                       readonly=True, default=lambda self: _('New'))
    
    # Partner Information
    partner_id = fields.Many2one('res.partner', string='Partner', required=True,
                                  domain=[('is_b2b_partner', '=', True),
                                          ('approval_status', '=', 'approved')])
    partner_tier = fields.Selection(related='partner_id.partner_tier', string='Tier', store=True)
    
    # Order Type
    order_type = fields.Selection([
        ('regular', 'Regular Order'),
        ('standing', 'Standing Order'),
        ('template', 'Order Template'),
    ], string='Order Type', default='regular')
    
    # Order Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('pending_approval', 'Pending Approval'),
        ('approved', 'Approved'),
        ('confirmed', 'Confirmed'),
        ('processing', 'Processing'),
        ('partially_shipped', 'Partially Shipped'),
        ('shipped', 'Shipped'),
        ('delivered', 'Delivered'),
        ('cancelled', 'Cancelled'),
    ], string='Status', default='draft', tracking=True)
    
    # Dates
    order_date = fields.Datetime(string='Order Date', required=True,
                                  default=lambda self: fields.Datetime.now())
    requested_delivery_date = fields.Date(string='Requested Delivery Date')
    confirmed_date = fields.Datetime(string='Confirmed Date')
    shipped_date = fields.Datetime(string='Shipped Date')
    delivered_date = fields.Datetime(string='Delivered Date')
    
    # Delivery
    delivery_location_id = fields.Many2one('b2b.delivery.location', string='Delivery Location')
    delivery_address = fields.Text(string='Delivery Address', compute='_compute_delivery_address')
    delivery_instructions = fields.Text(string='Delivery Instructions')
    
    # Financial
    currency_id = fields.Many2one('res.currency', related='partner_id.currency_id')
    pricelist_id = fields.Many2one('b2b.pricelist', string='Price List')
    
    amount_untaxed = fields.Monetary(string='Untaxed Amount', compute='_compute_amounts', store=True)
    amount_tax = fields.Monetary(string='Tax Amount', compute='_compute_amounts', store=True)
    amount_total = fields.Monetary(string='Total Amount', compute='_compute_amounts', store=True)
    
    # Payment
    payment_method = fields.Selection([
        ('credit', 'Credit'),
        ('cod', 'Cash on Delivery'),
        ('bank_transfer', 'Bank Transfer'),
        ('online', 'Online Payment'),
    ], string='Payment Method', required=True, default='credit')
    
    # Approval Workflow
    requires_approval = fields.Boolean(string='Requires Approval', 
                                       compute='_compute_requires_approval', store=True)
    approver_id = fields.Many2one('b2b.portal.user', string='Assigned Approver')
    approved_by = fields.Many2one('res.users', string='Approved By')
    approved_date = fields.Datetime(string='Approved Date')
    approval_notes = fields.Text(string='Approval Notes')
    
    # Line Items
    line_ids = fields.One2many('b2b.order.line', 'order_id', string='Order Lines')
    
    # Linked Documents
    sale_order_id = fields.Many2one('sale.order', string='Linked Sale Order')
    invoice_ids = fields.One2many('account.move', 'b2b_order_id', string='Invoices')
    picking_ids = fields.One2many('stock.picking', 'b2b_order_id', string='Deliveries')
    
    # Standing Order
    frequency = fields.Selection([
        ('daily', 'Daily'),
        ('weekly', 'Weekly'),
        ('bi_weekly', 'Bi-Weekly'),
        ('monthly', 'Monthly'),
    ], string='Standing Order Frequency')
    next_standing_order_date = fields.Date(string='Next Standing Order Date')
    standing_order_parent_id = fields.Many2one('b2b.order', string='Parent Standing Order')
    standing_order_child_ids = fields.One2many('b2b.order', 'standing_order_parent_id',
                                                string='Generated Orders')
    
    @api.model
    def create(self, vals):
        if vals.get('name', _('New')) == _('New'):
            vals['name'] = self.env['ir.sequence'].next_by_code('b2b.order') or _('New')
        return super(B2BOrder, self).create(vals)
    
    @api.depends('amount_total', 'partner_id')
    def _compute_requires_approval(self):
        for order in self:
            # Check if order amount exceeds partner tier auto-approve limit
            tier_limits = {
                'bronze': 10000,
                'silver': 50000,
                'gold': 100000,
                'platinum': 250000,
            }
            limit = tier_limits.get(order.partner_tier, 10000)
            order.requires_approval = order.amount_total > limit
    
    @api.depends('line_ids.price_subtotal')
    def _compute_amounts(self):
        for order in self:
            order.amount_untaxed = sum(order.line_ids.mapped('price_subtotal'))
            order.amount_tax = order.amount_untaxed * 0.15  # 15% VAT
            order.amount_total = order.amount_untaxed + order.amount_tax
    
    def action_submit(self):
        self.ensure_one()
        if not self.line_ids:
            raise ValidationError(_('Cannot submit empty order'))
        
        # Check credit limit if using credit
        if self.payment_method == 'credit':
            approved, message = self.partner_id.check_credit_available(self.amount_total)
            if not approved:
                raise ValidationError(message)
        
        if self.requires_approval:
            self.write({'state': 'pending_approval'})
            self._notify_approvers()
        else:
            self.action_confirm()
    
    def action_approve(self):
        self.ensure_one()
        self.write({
            'state': 'approved',
            'approved_by': self.env.user.id,
            'approved_date': fields.Datetime.now(),
        })
        self.action_confirm()
    
    def action_confirm(self):
        self.ensure_one()
        # Create sale order
        sale_order = self._create_sale_order()
        self.write({
            'state': 'confirmed',
            'confirmed_date': fields.Datetime.now(),
            'sale_order_id': sale_order.id,
        })
        return sale_order
    
    def _create_sale_order(self):
        self.ensure_one()
        sale_vals = {
            'partner_id': self.partner_id.id,
            'b2b_order_id': self.id,
            'order_line': [(0, 0, {
                'product_id': line.product_id.id,
                'product_uom_qty': line.quantity,
                'price_unit': line.unit_price,
            }) for line in self.line_ids],
        }
        return self.env['sale.order'].create(sale_vals)
    
    @api.model
    def generate_standing_orders(self):
        today = fields.Date.today()
        standing_orders = self.search([
            ('order_type', '=', 'standing'),
            ('state', '=', 'confirmed'),
            ('next_standing_order_date', '<=', today),
        ])
        
        for parent_order in standing_orders:
            # Create child order from template
            child_vals = {
                'order_type': 'regular',
                'partner_id': parent_order.partner_id.id,
                'delivery_location_id': parent_order.delivery_location_id.id,
                'payment_method': parent_order.payment_method,
                'standing_order_parent_id': parent_order.id,
                'line_ids': [(0, 0, {
                    'product_id': line.product_id.id,
                    'quantity': line.quantity,
                    'unit_price': line.unit_price,
                }) for line in parent_order.line_ids],
            }
            child_order = self.create(child_vals)
            child_order.action_submit()
            
            # Update next date
            next_date = parent_order._calculate_next_standing_date()
            parent_order.write({'next_standing_order_date': next_date})


class B2BOrderLine(models.Model):
    _name = 'b2b.order.line'
    _description = 'B2B Order Line'
    
    order_id = fields.Many2one('b2b.order', string='Order', required=True, ondelete='cascade')
    sequence = fields.Integer(string='Sequence', default=10)
    
    product_id = fields.Many2one('product.product', string='Product', required=True)
    product_uom = fields.Many2one('uom.uom', string='Unit of Measure', 
                                   related='product_id.uom_id', readonly=True)
    
    quantity = fields.Float(string='Quantity', required=True, default=1.0)
    unit_price = fields.Monetary(string='Unit Price', currency_field='currency_id')
    
    # Pricing Details
    base_price = fields.Monetary(string='Base Price')
    discount_amount = fields.Monetary(string='Discount Amount')
    discount_percent = fields.Float(string='Discount %')
    
    # Calculated
    price_subtotal = fields.Monetary(string='Subtotal', 
                                     compute='_compute_subtotal', store=True)
    
    # Availability
    available_qty = fields.Float(string='Available Quantity', 
                                  related='product_id.qty_available', readonly=True)
    backorder_qty = fields.Float(string='Backorder Quantity', compute='_compute_backorder')
    
    @api.depends('quantity', 'unit_price', 'discount_amount')
    def _compute_subtotal(self):
        for line in self:
            line.price_subtotal = (line.quantity * line.unit_price) - line.discount_amount
    
    @api.onchange('product_id', 'quantity')
    def _onchange_product_price(self):
        if self.product_id and self.order_id.partner_id:
            # Get price from B2B price list
            price_list = self.env['b2b.pricelist'].get_for_partner(
                self.order_id.partner_id, self.product_id
            )
            if price_list:
                self.unit_price = price_list.get_price_for_quantity(self.quantity)
                self.base_price = price_list.base_price
```

---

### MILESTONE 24: IoT Infrastructure & Sensor Integration (Days 231-240)

#### 24.1 Objective Statement

Deploy the complete IoT infrastructure including MQTT broker setup, sensor data ingestion pipeline, TimescaleDB configuration, and integration with the Smart Farm Management module. Install and configure RFID readers, temperature sensors, and activity monitoring devices.

#### 24.2 Success Criteria
- MQTT broker (Mosquitto) deployed and operational
- TimescaleDB extension configured in PostgreSQL
- IoT data ingestion API functional
- RFID readers integrated with animal identification
- Temperature sensors logging every 5 minutes
- Activity collars transmitting data every 15 minutes
- Real-time alert system for threshold violations

#### 24.3 Technical Implementation

##### MQTT Broker Configuration

```yaml
# docker-compose.mqtt.yml
version: '3.8'

services:
  mosquitto:
    image: eclipse-mosquitto:2.0
    container_name: smart_dairy_mqtt
    ports:
      - "1883:1883"    # MQTT port
      - "9001:9001"    # WebSocket port
    volumes:
      - ./mosquitto/config:/mosquitto/config
      - ./mosquitto/data:/mosquitto/data
      - ./mosquitto/log:/mosquitto/log
    environment:
      - TZ=Asia/Dhaka
    restart: unless-stopped
    networks:
      - smart_dairy_network

networks:
  smart_dairy_network:
    external: true
```

```
# mosquitto.conf
listener 1883
protocol mqtt

listener 9001
protocol websockets

# Authentication
allow_anonymous false
password_file /mosquitto/config/passwd
acl_file /mosquitto/config/acl

# Persistence
persistence true
persistence_location /mosquitto/data/

# Logging
log_dest file /mosquitto/log/mosquitto.log
log_dest stdout
connection_messages true
log_timestamp true

# Security
require_certificate false
tls_version tlsv1.2

# Message retention
max_queued_messages 1000
max_inflight_messages 20
```

##### TimescaleDB Configuration

```python
# migrations/setup_timescaledb.py
"""
TimescaleDB setup for IoT sensor data
Run this after installing TimescaleDB extension
"""

SETUP_TIMESCALEDB_SQL = """
-- Enable TimescaleDB extension
CREATE EXTENSION IF NOT EXISTS timescaledb;

-- Create hypertable for sensor readings
CREATE TABLE iot_sensor_readings (
    time TIMESTAMPTZ NOT NULL,
    sensor_id VARCHAR(50) NOT NULL,
    sensor_type VARCHAR(50) NOT NULL,
    animal_id INTEGER,
    location VARCHAR(100),
    value DOUBLE PRECISION NOT NULL,
    unit VARCHAR(20),
    quality_score INTEGER DEFAULT 100,
    metadata JSONB
);

-- Convert to hypertable
SELECT create_hypertable('iot_sensor_readings', 'time', 
                         chunk_time_interval => INTERVAL '1 day');

-- Create indexes
CREATE INDEX idx_sensor_readings_sensor_id ON iot_sensor_readings(sensor_id, time DESC);
CREATE INDEX idx_sensor_readings_animal ON iot_sensor_readings(animal_id, time DESC);
CREATE INDEX idx_sensor_readings_type ON iot_sensor_readings(sensor_type, time DESC);

-- Create materialized view for hourly aggregates
CREATE MATERIALIZED VIEW sensor_readings_hourly AS
SELECT 
    time_bucket('1 hour', time) AS bucket,
    sensor_id,
    sensor_type,
    AVG(value) as avg_value,
    MIN(value) as min_value,
    MAX(value) as max_value,
    COUNT(*) as reading_count
FROM iot_sensor_readings
GROUP BY bucket, sensor_id, sensor_type
WITH DATA;

-- Create continuous aggregate policy
SELECT add_continuous_aggregate_policy('sensor_readings_hourly',
    start_offset => INTERVAL '1 month',
    end_offset => INTERVAL '1 hour',
    schedule_interval => INTERVAL '1 hour');

-- Create hypertable for milk production
CREATE TABLE iot_milk_production (
    time TIMESTAMPTZ NOT NULL,
    animal_id INTEGER NOT NULL,
    session VARCHAR(10) NOT NULL, -- 'morning', 'evening'
    volume_ml DOUBLE PRECISION NOT NULL,
    fat_percent DOUBLE PRECISION,
    snf_percent DOUBLE PRECISION,
    conductivity DOUBLE PRECISION,
    temperature DOUBLE PRECISION
);

SELECT create_hypertable('iot_milk_production', 'time',
                         chunk_time_interval => INTERVAL '7 days');

-- Create hypertable for animal activity
CREATE TABLE iot_animal_activity (
    time TIMESTAMPTZ NOT NULL,
    animal_id INTEGER NOT NULL,
    activity_score INTEGER NOT NULL, -- 0-100
    rumination_minutes INTEGER,
    resting_minutes INTEGER,
    eating_minutes INTEGER,
    walking_minutes INTEGER,
    location_lat DOUBLE PRECISION,
    location_lon DOUBLE PRECISION
);

SELECT create_hypertable('iot_animal_activity', 'time',
                         chunk_time_interval => INTERVAL '7 days');

-- Data retention policies
SELECT add_retention_policy('iot_sensor_readings', INTERVAL '2 years');
SELECT add_retention_policy('iot_milk_production', INTERVAL '5 years');
SELECT add_retention_policy('iot_animal_activity', INTERVAL '2 years');
"""
```

##### IoT Data Ingestion Service

```python
# services/iot_ingestion.py
"""
IoT Data Ingestion Service
Receives MQTT messages and stores in TimescaleDB
"""

import json
import logging
from datetime import datetime
from typing import Dict, Any

import paho.mqtt.client as mqtt
import psycopg2
from psycopg2.extras import execute_values

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)


class IoTIngestionService:
    def __init__(self, mqtt_host: str, mqtt_port: int, 
                 db_config: Dict[str, Any]):
        self.mqtt_host = mqtt_host
        self.mqtt_port = mqtt_port
        self.db_config = db_config
        
        # MQTT topics to subscribe
        self.topics = [
            ('farm/+/sensors/temperature', 0),
            ('farm/+/sensors/humidity', 0),
            ('farm/+/sensors/ammonia', 0),
            ('farm/+/animals/+/activity', 0),
            ('farm/+/animals/+/location', 0),
            ('farm/+/milking/+/production', 0),
            ('farm/+/rfid/scan', 0),
        ]
        
        self.db_pool = None
        self.mqtt_client = None
    
    def connect_database(self):
        """Initialize database connection pool"""
        self.db_pool = psycopg2.pool.ThreadedConnectionPool(
            minconn=5, maxconn=20, **self.db_config
        )
        logger.info("Database connection pool initialized")
    
    def on_connect(self, client, userdata, flags, rc):
        """MQTT connection callback"""
        if rc == 0:
            logger.info("Connected to MQTT broker")
            for topic, qos in self.topics:
                client.subscribe(topic, qos)
                logger.info(f"Subscribed to {topic}")
        else:
            logger.error(f"MQTT connection failed with code {rc}")
    
    def on_message(self, client, userdata, msg):
        """Process incoming MQTT message"""
        try:
            topic_parts = msg.topic.split('/')
            payload = json.loads(msg.payload.decode())
            
            # Route to appropriate handler
            if 'temperature' in msg.topic or 'humidity' in msg.topic:
                self._process_environmental_reading(topic_parts, payload)
            elif 'activity' in msg.topic or 'location' in msg.topic:
                self._process_animal_reading(topic_parts, payload)
            elif 'milking' in msg.topic:
                self._process_milk_production(topic_parts, payload)
            elif 'rfid' in msg.topic:
                self._process_rfid_scan(topic_parts, payload)
                
        except Exception as e:
            logger.error(f"Error processing message: {e}")
    
    def _process_environmental_reading(self, topic_parts: list, payload: dict):
        """Store environmental sensor reading"""
        sensor_id = payload.get('sensor_id')
        sensor_type = topic_parts[-1]  # temperature, humidity, etc.
        location = payload.get('location', 'unknown')
        
        data = {
            'time': payload.get('timestamp', datetime.utcnow().isoformat()),
            'sensor_id': sensor_id,
            'sensor_type': sensor_type,
            'location': location,
            'value': payload.get('value'),
            'unit': payload.get('unit'),
            'quality_score': payload.get('quality', 100),
            'metadata': json.dumps(payload.get('metadata', {}))
        }
        
        self._insert_reading(data)
        
        # Check thresholds and trigger alerts
        self._check_thresholds(sensor_id, sensor_type, data['value'])
    
    def _process_animal_reading(self, topic_parts: list, payload: dict):
        """Store animal activity/location reading"""
        animal_id = int(topic_parts[3])  # farm/+/animals/{id}/activity
        
        conn = self.db_pool.getconn()
        try:
            with conn.cursor() as cur:
                cur.execute("""
                    INSERT INTO iot_animal_activity 
                    (time, animal_id, activity_score, rumination_minutes,
                     resting_minutes, eating_minutes, walking_minutes,
                     location_lat, location_lon)
                    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)
                """, (
                    payload.get('timestamp', datetime.utcnow()),
                    animal_id,
                    payload.get('activity_score', 0),
                    payload.get('rumination_minutes'),
                    payload.get('resting_minutes'),
                    payload.get('eating_minutes'),
                    payload.get('walking_minutes'),
                    payload.get('latitude'),
                    payload.get('longitude')
                ))
            conn.commit()
        finally:
            self.db_pool.putconn(conn)
    
    def _process_milk_production(self, topic_parts: list, payload: dict):
        """Store milk production reading"""
        animal_id = int(topic_parts[3])
        session = payload.get('session', 'morning')
        
        conn = self.db_pool.getconn()
        try:
            with conn.cursor() as cur:
                cur.execute("""
                    INSERT INTO iot_milk_production
                    (time, animal_id, session, volume_ml, fat_percent,
                     snf_percent, conductivity, temperature)
                    VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
                """, (
                    payload.get('timestamp', datetime.utcnow()),
                    animal_id,
                    session,
                    payload.get('volume_ml', 0),
                    payload.get('fat_percent'),
                    payload.get('snf_percent'),
                    payload.get('conductivity'),
                    payload.get('temperature')
                ))
            conn.commit()
        finally:
            self.db_pool.putconn(conn)
    
    def _insert_reading(self, data: dict):
        """Insert sensor reading into database"""
        conn = self.db_pool.getconn()
        try:
            with conn.cursor() as cur:
                cur.execute("""
                    INSERT INTO iot_sensor_readings
                    (time, sensor_id, sensor_type, location, value, unit,
                     quality_score, metadata)
                    VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
                """, (
                    data['time'], data['sensor_id'], data['sensor_type'],
                    data['location'], data['value'], data['unit'],
                    data['quality_score'], data['metadata']
                ))
            conn.commit()
        finally:
            self.db_pool.putconn(conn)
    
    def _check_thresholds(self, sensor_id: str, sensor_type: str, value: float):
        """Check sensor value against configured thresholds"""
        # Query Odoo for alert rules (simplified)
        thresholds = {
            'temperature': {'min': 15, 'max': 30},
            'humidity': {'min': 40, 'max': 80},
            'ammonia': {'min': 0, 'max': 25},
        }
        
        if sensor_type in thresholds:
            threshold = thresholds[sensor_type]
            if value < threshold['min'] or value > threshold['max']:
                self._trigger_alert(sensor_id, sensor_type, value, threshold)
    
    def _trigger_alert(self, sensor_id: str, sensor_type: str, 
                       value: float, threshold: dict):
        """Send alert notification"""
        logger.warning(
            f"ALERT: {sensor_type} sensor {sensor_id} "
            f"value {value} outside threshold {threshold}"
        )
        # TODO: Integrate with Odoo alert system
    
    def start(self):
        """Start the ingestion service"""
        self.connect_database()
        
        self.mqtt_client = mqtt.Client()
        self.mqtt_client.on_connect = self.on_connect
        self.mqtt_client.on_message = self.on_message
        
        self.mqtt_client.connect(self.mqtt_host, self.mqtt_port, 60)
        logger.info("IoT Ingestion Service started")
        
        self.mqtt_client.loop_forever()


if __name__ == '__main__':
    service = IoTIngestionService(
        mqtt_host='localhost',
        mqtt_port=1883,
        db_config={
            'host': 'localhost',
            'database': 'smart_dairy',
            'user': 'odoo',
            'password': 'odoo',
        }
    )
    service.start()
```

---


### MILESTONE 25: Smart Farm Intelligence (Days 241-250)

#### 25.1 Objective Statement

Implement predictive analytics and machine learning models for early disease detection, heat detection, milk yield forecasting, and feed optimization. Create real-time dashboards for farm managers with actionable insights.

#### 25.2 Success Criteria
- Health prediction model with >80% accuracy
- Heat detection algorithm operational
- Milk yield forecasting for next 7 days
- Feed optimization recommendations
- Real-time farm dashboard with <5s latency
- Automated alert system for anomalies

#### 25.3 Technical Implementation

##### Health Prediction Model

```python
# ml/health_prediction.py
"""
Predictive Health Analytics for Dairy Cattle
Uses sensor data and historical records to predict health issues
"""

import numpy as np
import pandas as pd
from sklearn.ensemble import RandomForestClassifier, IsolationForest
from sklearn.preprocessing import StandardScaler
from sklearn.model_selection import train_test_split
import joblib
from datetime import datetime, timedelta

class HealthPredictionModel:
    def __init__(self):
        self.risk_classifier = RandomForestClassifier(
            n_estimators=100, max_depth=10, random_state=42
        )
        self.anomaly_detector = IsolationForest(
            contamination=0.1, random_state=42
        )
        self.scaler = StandardScaler()
        self.is_trained = False
    
    def prepare_features(self, animal_data: pd.DataFrame) -> pd.DataFrame:
        """Extract features from raw sensor and health data"""
        features = pd.DataFrame()
        
        # Activity-based features
        features['activity_mean_7d'] = animal_data['activity_score'].rolling(7).mean()
        features['activity_std_7d'] = animal_data['activity_score'].rolling(7).std()
        features['activity_trend'] = (
            animal_data['activity_score'].iloc[-1] - 
            animal_data['activity_score'].iloc[-7]
        )
        
        # Rumination features
        features['rumination_mean_7d'] = animal_data['rumination_minutes'].rolling(7).mean()
        features['rumination_decline'] = (
            animal_data['rumination_minutes'].iloc[-1] < 
            animal_data['rumination_minutes'].rolling(7).mean().iloc[-1] * 0.8
        )
        
        # Temperature features (if available)
        if 'body_temperature' in animal_data.columns:
            features['temp_mean_3d'] = animal_data['body_temperature'].rolling(3).mean()
            features['temp_spike'] = animal_data['body_temperature'] > 39.5
        
        # Milk production features
        if 'milk_volume' in animal_data.columns:
            features['milk_trend'] = (
                animal_data['milk_volume'].iloc[-3:].mean() - 
                animal_data['milk_volume'].rolling(7).mean().iloc[-1]
            ) / animal_data['milk_volume'].rolling(7).mean().iloc[-1]
            features['milk_decline'] = features['milk_trend'] < -0.15
        
        # Time-based features
        features['days_in_milk'] = animal_data['days_in_milk']
        features['days_since_calving'] = animal_data['days_since_calving']
        features['lactation_number'] = animal_data['lactation_number']
        
        # Historical health features
        features['days_since_last_treatment'] = animal_data['days_since_last_treatment']
        features['treatment_count_30d'] = animal_data['treatment_count_30d']
        
        return features.fillna(0)
    
    def train(self, training_data: pd.DataFrame, labels: np.ndarray):
        """Train the health prediction model"""
        features = self.prepare_features(training_data)
        X = self.scaler.fit_transform(features)
        
        # Train risk classifier
        X_train, X_test, y_train, y_test = train_test_split(
            X, labels, test_size=0.2, random_state=42
        )
        self.risk_classifier.fit(X_train, y_train)
        
        # Train anomaly detector on healthy samples
        healthy_samples = X[labels == 0]
        self.anomaly_detector.fit(healthy_samples)
        
        self.is_trained = True
        
        # Evaluate
        accuracy = self.risk_classifier.score(X_test, y_test)
        print(f"Model trained. Test accuracy: {accuracy:.2%}")
        
        return self
    
    def predict_health_risk(self, animal_data: pd.DataFrame) -> dict:
        """Predict health risk for an animal"""
        if not self.is_trained:
            raise ValueError("Model must be trained before prediction")
        
        features = self.prepare_features(animal_data)
        X = self.scaler.transform(features.iloc[-1:])
        
        # Get risk prediction
        risk_prob = self.risk_classifier.predict_proba(X)[0]
        risk_class = self.risk_classifier.predict(X)[0]
        
        # Get anomaly score
        anomaly_score = self.anomaly_detector.decision_function(X)[0]
        is_anomaly = self.anomaly_detector.predict(X)[0] == -1
        
        # Determine risk level
        risk_levels = {0: 'low', 1: 'medium', 2: 'high'}
        risk_level = risk_levels.get(risk_class, 'unknown')
        
        # Calculate confidence
        confidence = max(risk_prob)
        
        return {
            'risk_level': risk_level,
            'risk_probability': float(risk_prob[risk_class]),
            'is_anomaly': bool(is_anomaly),
            'anomaly_score': float(anomaly_score),
            'confidence': float(confidence),
            'prediction_time': datetime.utcnow().isoformat(),
            'features_used': features.iloc[-1].to_dict()
        }
    
    def get_risk_factors(self, animal_data: pd.DataFrame) -> list:
        """Identify contributing risk factors"""
        features = self.prepare_features(animal_data)
        X = self.scaler.transform(features.iloc[-1:])
        
        # Get feature importance
        importances = self.risk_classifier.feature_importances_
        feature_names = features.columns
        
        # Create risk factor list
        risk_factors = []
        for name, importance in zip(feature_names, importances):
            if importance > 0.05:  # Only significant factors
                value = features.iloc[-1][name]
                risk_factors.append({
                    'factor': name,
                    'importance': float(importance),
                    'current_value': float(value)
                })
        
        return sorted(risk_factors, key=lambda x: x['importance'], reverse=True)
    
    def save_model(self, filepath: str):
        """Save trained model to disk"""
        joblib.dump({
            'classifier': self.risk_classifier,
            'anomaly_detector': self.anomaly_detector,
            'scaler': self.scaler,
            'is_trained': self.is_trained
        }, filepath)
    
    def load_model(self, filepath: str):
        """Load trained model from disk"""
        data = joblib.load(filepath)
        self.risk_classifier = data['classifier']
        self.anomaly_detector = data['anomaly_detector']
        self.scaler = data['scaler']
        self.is_trained = data['is_trained']
        return self


class HeatDetectionModel:
    """Detect estrus/heat in dairy cows using activity and sensor data"""
    
    def __init__(self):
        self.activity_threshold = 1.5  # 150% of baseline
        self.rumination_threshold = 0.7  # 70% of baseline
        
    def detect_heat(self, animal_data: pd.DataFrame) -> dict:
        """Analyze data for heat detection"""
        # Calculate baseline (21-day average excluding previous heats)
        baseline_activity = animal_data['activity_score'].rolling(21).mean().iloc[-1]
        baseline_rumination = animal_data['rumination_minutes'].rolling(21).mean().iloc[-1]
        
        # Current values
        current_activity = animal_data['activity_score'].iloc[-1]
        current_rumination = animal_data['rumination_minutes'].iloc[-1]
        
        # Activity spike detection
        activity_ratio = current_activity / baseline_activity if baseline_activity > 0 else 0
        activity_spike = activity_ratio > self.activity_threshold
        
        # Rumination decline detection
        rumination_ratio = current_rumination / baseline_rumination if baseline_rumination > 0 else 0
        rumination_decline = rumination_ratio < self.rumination_threshold
        
        # Combined heat score (0-100)
        heat_score = 0
        if activity_spike:
            heat_score += min(50 * (activity_ratio - 1), 50)
        if rumination_decline:
            heat_score += min(50 * (1 - rumination_ratio), 50)
        
        # Determine status
        if heat_score >= 70:
            status = 'high_probability'
        elif heat_score >= 40:
            status = 'medium_probability'
        else:
            status = 'low_probability'
        
        return {
            'heat_detected': heat_score >= 70,
            'heat_score': heat_score,
            'status': status,
            'activity_ratio': activity_ratio,
            'rumination_ratio': rumination_ratio,
            'recommended_action': self._get_recommendation(status),
            'optimal_insemination_time': self._calculate_insemination_window()
        }
    
    def _get_recommendation(self, status: str) -> str:
        recommendations = {
            'high_probability': 'Schedule AI within 12-18 hours',
            'medium_probability': 'Monitor closely, check again in 6 hours',
            'low_probability': 'Continue normal monitoring'
        }
        return recommendations.get(status, 'Continue monitoring')
    
    def _calculate_insemination_window(self) -> str:
        optimal_time = datetime.utcnow() + timedelta(hours=12)
        return optimal_time.isoformat()


class MilkYieldForecaster:
    """Forecast milk production using time series analysis"""
    
    def __init__(self):
        self.lactation_curve_params = {
            'peak_day': 60,
            'peak_yield_factor': 1.2,
            'persistence': 0.97  # 3% monthly decline after peak
        }
    
    def forecast_yield(self, historical_data: pd.DataFrame, 
                       days_ahead: int = 7) -> dict:
        """Forecast milk yield for upcoming days"""
        
        # Get current lactation info
        days_in_milk = historical_data['days_in_milk'].iloc[-1]
        lactation_number = historical_data['lactation_number'].iloc[-1]
        
        # Calculate expected yield based on lactation curve
        recent_avg = historical_data['milk_volume'].tail(7).mean()
        
        forecasts = []
        for day in range(1, days_ahead + 1):
            future_dim = days_in_milk + day
            
            # Lactation curve adjustment
            if future_dim <= self.lactation_curve_params['peak_day']:
                # Still approaching peak
                trend_factor = 1.0 + (0.005 * day)
            else:
                # Past peak, apply persistence
                months_after_peak = (future_dim - self.lactation_curve_params['peak_day']) / 30
                trend_factor = self.lactation_curve_params['persistence'] ** months_after_peak
            
            # Seasonal adjustment (if data available)
            seasonal_factor = 1.0  # Placeholder
            
            forecasted_yield = recent_avg * trend_factor * seasonal_factor
            
            forecasts.append({
                'date': (datetime.utcnow() + timedelta(days=day)).strftime('%Y-%m-%d'),
                'days_in_milk': future_dim,
                'forecasted_yield': round(forecasted_yield, 1),
                'confidence_lower': round(forecasted_yield * 0.85, 1),
                'confidence_upper': round(forecasted_yield * 1.15, 1)
            })
        
        return {
            'animal_id': historical_data['animal_id'].iloc[-1],
            'forecast_days': days_ahead,
            'current_avg_yield': round(recent_avg, 1),
            'forecasts': forecasts,
            'trend': 'increasing' if forecasts[0]['forecasted_yield'] > recent_avg else 'decreasing',
            'recommended_action': self._get_recommendation(forecasts)
        }
    
    def _get_recommendation(self, forecasts: list) -> str:
        first_week = forecasts[:7]
        avg_forecast = sum(f['forecasted_yield'] for f in first_week) / len(first_week)
        
        if avg_forecast < 15:
            return 'Consider ration adjustment - yield below target'
        elif avg_forecast > 30:
            return 'High producer - ensure adequate nutrition'
        return 'Yield on target - maintain current regimen'
```

---

### MILESTONE 26: Multi-Warehouse & Advanced Inventory (Days 251-260)

#### 26.1 Objective Statement

Implement multi-warehouse inventory management with automated stock transfers, cross-docking, advanced picking strategies, and real-time inventory synchronization across all locations.

#### 26.2 Success Criteria
- 3 warehouses configured and operational
- Automated stock transfer requests
- Cross-docking functionality working
- Batch/lot tracking for dairy products
- FIFO/FEFO picking strategies
- Real-time inventory visibility

#### 26.3 Technical Implementation

```python
# models/multi_warehouse.py
from odoo import models, fields, api, _
from odoo.exceptions import ValidationError, UserError

class StockWarehouseExtension(models.Model):
    _inherit = 'stock.warehouse'
    
    # Warehouse Type
    warehouse_type = fields.Selection([
        ('farm', 'Farm/Fresh Production'),
        ('distribution', 'Distribution Center'),
        ('retail', 'Retail Store'),
        ('cold_storage', 'Cold Storage Facility'),
    ], string='Warehouse Type', default='distribution')
    
    # Cold Chain Specific
    temperature_zones = fields.One2many('stock.temperature.zone', 'warehouse_id',
                                        string='Temperature Zones')
    cold_storage_capacity = fields.Float(string='Cold Storage Capacity (L)')
    freezer_capacity = fields.Float(string='Freezer Capacity (L)')
    
    # Service Area
    service_radius = fields.Float(string='Service Radius (km)')
    coverage_districts = fields.Many2many('res.district', string='Coverage Districts')
    
    # Operations
    is_active = fields.Boolean(string='Active', default=True)
    auto_replenish = fields.Boolean(string='Auto Replenishment', default=True)
    replenishment_threshold = fields.Float(string='Replenishment Threshold %', default=20.0)


class StockTemperatureZone(models.Model):
    _name = 'stock.temperature.zone'
    _description = 'Temperature Control Zone'
    
    warehouse_id = fields.Many2one('stock.warehouse', string='Warehouse', required=True)
    name = fields.Char(string='Zone Name', required=True)
    zone_type = fields.Selection([
        ('ambient', 'Ambient'),
        ('chiller', 'Chiller (2-4C)'),
        ('cold_room', 'Cold Room (0-4C)'),
        ('freezer', 'Freezer (-18C)'),
    ], string='Zone Type', required=True)
    
    min_temperature = fields.Float(string='Min Temperature (C)')
    max_temperature = fields.Float(string='Max Temperature (C)')
    current_temperature = fields.Float(string='Current Temperature')
    
    # Alert thresholds
    alert_low = fields.Float(string='Low Alert Threshold')
    alert_high = fields.Float(string='High Alert Threshold')
    
    # Locations in this zone
    location_ids = fields.One2many('stock.location', 'temperature_zone_id', 
                                   string='Locations')


class StockTransferRequest(models.Model):
    _name = 'stock.transfer.request'
    _description = 'Inter-Warehouse Transfer Request'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    
    name = fields.Char(string='Transfer Reference', required=True, copy=False,
                       default=lambda self: _('New'))
    
    # Source and Destination
    source_warehouse_id = fields.Many2one('stock.warehouse', string='Source Warehouse',
                                          required=True)
    dest_warehouse_id = fields.Many2one('stock.warehouse', string='Destination Warehouse',
                                        required=True)
    
    # Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('pending', 'Pending Approval'),
        ('approved', 'Approved'),
        ('in_transit', 'In Transit'),
        ('received', 'Received'),
        ('cancelled', 'Cancelled'),
    ], string='Status', default='draft', tracking=True)
    
    # Dates
    request_date = fields.Datetime(string='Request Date', default=lambda self: fields.Datetime.now())
    required_date = fields.Date(string='Required Date')
    ship_date = fields.Datetime(string='Ship Date')
    receipt_date = fields.Datetime(string='Receipt Date')
    
    # Transfer Details
    transfer_type = fields.Selection([
        ('replenishment', 'Auto Replenishment'),
        ('manual', 'Manual Request'),
        ('emergency', 'Emergency Transfer'),
        ('cross_dock', 'Cross Dock'),
    ], string='Transfer Type', default='manual')
    
    line_ids = fields.One2many('stock.transfer.request.line', 'transfer_id', 
                               string='Transfer Lines')
    
    # Linked Documents
    picking_ids = fields.One2many('stock.picking', 'transfer_request_id', string='Pickings')
    
    # Cold Chain
    requires_cold_chain = fields.Boolean(string='Requires Cold Chain', compute='_compute_cold_chain')
    transport_temp_required = fields.Float(string='Required Transport Temperature')
    
    @api.model
    def create(self, vals):
        if vals.get('name', _('New')) == _('New'):
            vals['name'] = self.env['ir.sequence'].next_by_code('stock.transfer.request') or _('New')
        return super(StockTransferRequest, self).create(vals)
    
    def action_submit(self):
        self.write({'state': 'pending'})
        # Auto-approve if within limits
        if self._can_auto_approve():
            self.action_approve()
    
    def action_approve(self):
        self.write({'state': 'approved'})
        self._create_transfer_pickings()
    
    def _create_transfer_pickings(self):
        """Create stock pickings for transfer"""
        # Create outgoing picking at source
        picking_out = self.env['stock.picking'].create({
            'picking_type_id': self.source_warehouse_id.out_type_id.id,
            'location_id': self.source_warehouse_id.lot_stock_id.id,
            'location_dest_id': self.env.ref('stock.stock_location_inter_wh').id,
            'transfer_request_id': self.id,
            'move_ids_without_package': [(0, 0, {
                'product_id': line.product_id.id,
                'product_uom_qty': line.quantity,
                'product_uom': line.product_uom.id,
                'name': line.product_id.name,
            }) for line in self.line_ids]
        })
        
        # Create incoming picking at destination
        picking_in = self.env['stock.picking'].create({
            'picking_type_id': self.dest_warehouse_id.in_type_id.id,
            'location_id': self.env.ref('stock.stock_location_inter_wh').id,
            'location_dest_id': self.dest_warehouse_id.lot_stock_id.id,
            'transfer_request_id': self.id,
        })
        
        self.picking_ids = [(4, picking_out.id), (4, picking_in.id)]


class StockTransferRequestLine(models.Model):
    _name = 'stock.transfer.request.line'
    _description = 'Transfer Request Line'
    
    transfer_id = fields.Many2one('stock.transfer.request', string='Transfer', required=True)
    product_id = fields.Many2one('product.product', string='Product', required=True)
    product_uom = fields.Many2one('uom.uom', string='Unit of Measure', 
                                   related='product_id.uom_id', readonly=True)
    quantity = fields.Float(string='Quantity', required=True)
    quantity_transferred = fields.Float(string='Quantity Transferred', default=0.0)
    quantity_received = fields.Float(string='Quantity Received', default=0.0)
    
    # Batch/Lot tracking
    lot_id = fields.Many2one('stock.production.lot', string='Lot/Serial Number')
    expiry_date = fields.Date(string='Expiry Date', related='lot_id.expiration_date')
```

---


### MILESTONE 27: Advanced Financial Management (Days 261-270)

#### 27.1 Objective Statement

Implement advanced financial modules including multi-currency support, bank reconciliation automation, Bangladesh VAT/Mushak compliance, advanced budgeting, and cost center accounting for all four business verticals.

#### 27.2 Success Criteria
- Bangladesh VAT automation (Mushak forms)
- Bank integration for auto-reconciliation
- Multi-currency transactions
- Cost center accounting per vertical
- Budget vs actual reporting
- Automated financial statements

#### 27.3 Technical Implementation

```python
# models/bd_vat_compliance.py
from odoo import models, fields, api, _
from odoo.exceptions import ValidationError

class BangladeshVATReport(models.Model):
    _name = 'bd.vat.report'
    _description = 'Bangladesh VAT Report (Mushak)'
    
    name = fields.Char(string='Report Reference', required=True)
    report_type = fields.Selection([
        ('mushak_9_1', 'Mushak 9.1 - Monthly Return'),
        ('mushak_9_2', 'Mushak 9.2 - Turnover Declaration'),
        ('mushak_4_3', 'Mushak 4.3 - Purchase Details'),
        ('mushak_6_1', 'Mushak 6.1 - Input Tax'),
    ], string='Report Type', required=True)
    
    period_start = fields.Date(string='Period Start', required=True)
    period_end = fields.Date(string='Period End', required=True)
    
    # Tax Summary
    total_sales_vat = fields.Monetary(string='Total Sales VAT')
    total_purchase_vat = fields.Monetary(string='Total Purchase VAT')
    vat_payable = fields.Monetary(string='VAT Payable', compute='_compute_vat_payable')
    
    state = fields.Selection([
        ('draft', 'Draft'),
        ('submitted', 'Submitted to NBR'),
        ('approved', 'Approved'),
    ], string='Status', default='draft')
    
    @api.depends('total_sales_vat', 'total_purchase_vat')
    def _compute_vat_payable(self):
        for report in self:
            report.vat_payable = report.total_sales_vat - report.total_purchase_vat


class AccountMoveLineVAT(models.Model):
    _inherit = 'account.move.line'
    
    bd_vat_rate = fields.Float(string='BD VAT Rate', default=15.0)
    bd_vat_amount = fields.Monetary(string='VAT Amount', compute='_compute_vat')
    mushak_code = fields.Char(string='Mushak Code')
    
    @api.depends('price_subtotal', 'bd_vat_rate')
    def _compute_vat(self):
        for line in self:
            line.bd_vat_amount = line.price_subtotal * (line.bd_vat_rate / 100)


class BankReconciliationAutomation(models.Model):
    _name = 'bank.reconciliation.auto'
    _description = 'Automated Bank Reconciliation'
    
    journal_id = fields.Many2one('account.journal', string='Bank Journal', required=True)
    statement_date = fields.Date(string='Statement Date', required=True)
    
    # Import
    statement_file = fields.Binary(string='Bank Statement File')
    statement_filename = fields.Char(string='Filename')
    
    # Matching Rules
    auto_match_exact = fields.Boolean(string='Auto-match Exact Amounts', default=True)
    auto_match_tolerance = fields.Float(string='Amount Tolerance %', default=1.0)
    auto_match_date_window = fields.Integer(string='Date Window (days)', default=3)
    
    # Results
    matched_count = fields.Integer(string='Matched Transactions', readonly=True)
    unmatched_count = fields.Integer(string='Unmatched Transactions', readonly=True)
    
    def action_import_statement(self):
        """Import and parse bank statement"""
        # Parse CSV/Excel/MT940
        transactions = self._parse_statement_file()
        
        for txn in transactions:
            self._match_transaction(txn)
    
    def _match_transaction(self, txn: dict):
        """Match bank transaction to accounting entries"""
        # Search for matching account move lines
        domain = [
            ('account_id', '=', self.journal_id.default_account_id.id),
            ('reconciled', '=', False),
        ]
        
        # Amount matching with tolerance
        if self.auto_match_exact:
            tolerance = txn['amount'] * (self.auto_match_tolerance / 100)
            domain.append(('debit', '>=', txn['amount'] - tolerance))
            domain.append(('debit', '<=', txn['amount'] + tolerance))
        
        # Date window
        date_start = txn['date'] - timedelta(days=self.auto_match_date_window)
        date_end = txn['date'] + timedelta(days=self.auto_match_date_window)
        domain.append(('date', '>=', date_start))
        domain.append(('date', '<=', date_end))
        
        matches = self.env['account.move.line'].search(domain)
        
        if len(matches) == 1:
            # Single match - auto reconcile
            matches.reconcile()
            return True
        elif len(matches) > 1:
            # Multiple matches - flag for manual review
            self._flag_for_review(txn, matches)
            return False
        
        return False
```

---

### MILESTONE 28: Business Intelligence Platform (Days 271-280)

#### 28.1 Objective Statement

Deploy Apache Superset as the BI platform with custom dashboards for executives, sales teams, farm managers, and operations. Implement real-time KPI tracking and automated report distribution.

#### 28.2 Success Criteria
- Superset deployed and integrated
- 10+ custom dashboards created
- 50+ KPIs defined and calculated
- Real-time data refresh <5 minutes
- Role-based dashboard access
- Automated daily/weekly reports

#### 28.3 Technical Implementation

```python
# bi/superset_integration.py
"""
Apache Superset Integration for Smart Dairy BI
"""

import requests
from typing import Dict, List, Optional
import pandas as pd

class SupersetIntegration:
    def __init__(self, base_url: str, username: str, password: str):
        self.base_url = base_url
        self.username = username
        self.password = password
        self.session = requests.Session()
        self.access_token = None
        self.csrf_token = None
        
    def authenticate(self):
        """Authenticate with Superset API"""
        auth_url = f"{self.base_url}/api/v1/security/login"
        response = self.session.post(auth_url, json={
            "username": self.username,
            "password": self.password,
            "provider": "db"
        })
        self.access_token = response.json()["access_token"]
        self.session.headers["Authorization"] = f"Bearer {self.access_token}"
        
    def create_database_connection(self, name: str, sqlalchemy_uri: str) -> dict:
        """Create database connection in Superset"""
        endpoint = f"{self.base_url}/api/v1/database/"
        data = {
            "database_name": name,
            "sqlalchemy_uri": sqlalchemy_uri,
            "extra": json.dumps({
                "metadata_params": {},
                "engine_params": {},
                "metadata_cache_timeout": {},
                "schemas_allowed_for_file_upload": []
            })
        }
        response = self.session.post(endpoint, json=data)
        return response.json()
    
    def create_dataset(self, database_id: int, table_name: str, 
                       schema: str = "public") -> dict:
        """Create dataset from database table"""
        endpoint = f"{self.base_url}/api/v1/dataset/"
        data = {
            "database": database_id,
            "schema": schema,
            "table_name": table_name
        }
        response = self.session.post(endpoint, json=data)
        return response.json()
    
    def create_dashboard(self, title: str, slug: str, 
                         published: bool = True) -> dict:
        """Create new dashboard"""
        endpoint = f"{self.base_url}/api/v1/dashboard/"
        data = {
            "dashboard_title": title,
            "slug": slug,
            "published": published,
            "json_metadata": json.dumps({
                "timed_refresh_immune_slices": [],
                "expanded_slices": {},
                "refresh_frequency": 300,  # 5 minutes
            })
        }
        response = self.session.post(endpoint, json=data)
        return response.json()
    
    def create_chart(self, title: str, dataset_id: int, 
                     viz_type: str, params: dict) -> dict:
        """Create chart for dashboard"""
        endpoint = f"{self.base_url}/api/v1/chart/"
        data = {
            "slice_name": title,
            "datasource_id": dataset_id,
            "datasource_type": "table",
            "viz_type": viz_type,
            "params": json.dumps(params)
        }
        response = self.session.post(endpoint, json=data)
        return response.json()


# Predefined Smart Dairy Dashboards
SMART_DAIRY_DASHBOARDS = {
    "executive_summary": {
        "title": "Executive Summary Dashboard",
        "slug": "executive-summary",
        "charts": [
            {
                "title": "Revenue by Vertical",
                "viz_type": "pie",
                "dataset": "sales_orders",
                "params": {
                    "groupby": ["business_vertical"],
                    "metric": "sum__amount_total",
                    "row_limit": 100
                }
            },
            {
                "title": "Daily Revenue Trend",
                "viz_type": "line",
                "dataset": "daily_sales",
                "params": {
                    "x": "date",
                    "y": "revenue",
                    "time_range": "LAST_30_DAYS"
                }
            },
            {
                "title": "KPI Cards",
                "viz_type": "big_number_total",
                "dataset": "daily_metrics",
                "params": {
                    "metric": "revenue_today",
                    "compare_lag": 1,
                    "compare_suffix": "vs yesterday"
                }
            }
        ]
    },
    "farm_operations": {
        "title": "Farm Operations Dashboard",
        "slug": "farm-operations",
        "charts": [
            {
                "title": "Milk Production Trend",
                "viz_type": "line",
                "dataset": "milk_production",
                "params": {
                    "x": "date",
                    "y": "total_volume",
                    "groupby": ["session"]
                }
            },
            {
                "title": "Animal Health Status",
                "viz_type": "dist_bar",
                "dataset": "animal_health",
                "params": {
                    "groupby": ["health_status"],
                    "metric": "count"
                }
            },
            {
                "title": "Heat Detection Alerts",
                "viz_type": "table",
                "dataset": "heat_detection",
                "params": {
                    "all_columns": ["animal_id", "heat_score", "optimal_time"],
                    "order_by_cols": [["heat_score", False]]
                }
            }
        ]
    },
    "b2b_analytics": {
        "title": "B2B Sales Analytics",
        "slug": "b2b-analytics",
        "charts": [
            {
                "title": "Top B2B Customers",
                "viz_type": "bar",
                "dataset": "b2b_orders",
                "params": {
                    "groupby": ["partner_name"],
                    "metric": "sum__amount_total",
                    "row_limit": 10
                }
            },
            {
                "title": "Credit Exposure by Tier",
                "viz_type": "pie",
                "dataset": "partner_credit",
                "params": {
                    "groupby": ["partner_tier"],
                    "metric": "sum__credit_used"
                }
            },
            {
                "title": "Order Fulfillment Rate",
                "viz_type": "gauge_chart",
                "dataset": "order_fulfillment",
                "params": {
                    "metric": "fulfillment_rate",
                    "min_val": 0,
                    "max_val": 100
                }
            }
        ]
    },
    "inventory_optimization": {
        "title": "Inventory Optimization Dashboard",
        "slug": "inventory-opt",
        "charts": [
            {
                "title": "Stock Levels by Warehouse",
                "viz_type": "bar",
                "dataset": "stock_levels",
                "params": {
                    "groupby": ["warehouse_name", "product_category"],
                    "metric": "sum__quantity"
                }
            },
            {
                "title": "Products Near Expiry",
                "viz_type": "table",
                "dataset": "expiry_tracking",
                "params": {
                    "all_columns": ["product_name", "lot_number", "expiry_date", "days_left"],
                    "row_limit": 20
                }
            }
        ]
    }
}
```

---

### MILESTONE 29: Advanced Reporting & Analytics (Days 281-290)

#### 29.1 Objective Statement

Build comprehensive report generator with custom report templates, scheduled report distribution, Excel/PDF export capabilities, and drill-down analysis for all business areas.

#### 29.2 Success Criteria
- 50+ standard reports available
- Custom report builder functional
- Scheduled report emails working
- Excel/PDF export with formatting
- Drill-down from summary to detail
- Report caching for performance

---

### MILESTONE 30: Phase 3 Go-Live & Optimization (Days 291-300)

#### 30.1 Objective Statement

Complete Phase 3 deployment with B2B portal launch, IoT sensor network activation, BI dashboard rollout, and comprehensive system optimization for production scale.

#### 30.2 Success Criteria
- 100+ B2B partners registered and active
- 50+ IoT sensors transmitting data
- All BI dashboards operational
- 99.5% system uptime
- <3 second average response time
- Complete documentation delivered
- User training completed

---


## 6. QUALITY ASSURANCE FRAMEWORK

### 6.1 Testing Strategy

| Test Type | Scope | Tools | Owner |
|-----------|-------|-------|-------|
| Unit Testing | Individual functions/modules | pytest, unittest | Backend Dev |
| Integration Testing | Module interactions | pytest, Odoo test framework | Lead Dev |
| E2E Testing | Complete user workflows | Selenium, Playwright | Frontend Dev |
| Performance Testing | Load, stress, endurance | JMeter, Locust | Lead Dev |
| Security Testing | Vulnerability assessment | OWASP ZAP, SonarQube | Lead Dev |
| IoT Testing | Sensor data integrity | Custom MQTT tests | Backend Dev |
| BI Testing | Report accuracy | SQL validation | Backend Dev |

### 6.2 Test Coverage Targets

| Component | Coverage Target | Minimum Coverage |
|-----------|-----------------|------------------|
| B2B Module | 90% | 80% |
| IoT Ingestion | 85% | 75% |
| ML Models | 80% | 70% |
| BI Queries | 95% | 85% |
| Frontend Components | 80% | 70% |

---

## 7. BUDGET BREAKDOWN

### 7.1 Detailed Budget

| Category | Item | Amount (BDT) |
|----------|------|--------------|
| **Human Resources** | | |
| | Lead Developer (100 days) | 25,00,000 |
| | Backend Developer (100 days) | 18,00,000 |
| | Frontend Developer (100 days) | 12,00,000 |
| | **Subtotal HR** | **55,00,000** |
| **Infrastructure** | | |
| | AWS/Azure Cloud (10 months) | 15,00,000 |
| | TimescaleDB/ClickHouse hosting | 5,00,000 |
| | Kafka/Superset infrastructure | 3,00,000 |
| | Backup and storage | 2,00,000 |
| | **Subtotal Infrastructure** | **25,00,000** |
| **IoT Hardware** | | |
| | RFID Readers (10 units) | 3,00,000 |
| | Temperature Sensors (20 units) | 2,00,000 |
| | Activity Collars (50 units) | 6,00,000 |
| | Milk Meters (20 units) | 2,00,000 |
| | IoT Gateways (5 units) | 1,50,000 |
| | Network Equipment | 50,000 |
| | **Subtotal IoT** | **15,00,000** |
| **Software & Tools** | | |
| | B2B Portal development tools | 3,00,000 |
| | BI Platform licenses | 3,00,000 |
| | Monitoring tools (Datadog/New Relic) | 2,00,000 |
| | Security tools | 2,00,000 |
| | Development tools & IDEs | 2,00,000 |
| | **Subtotal Software** | **12,00,000** |
| **Third-Party Services** | | |
| | SMS Gateway (Bulk SMS) | 2,00,000 |
| | Email Service (SendGrid/AWS SES) | 1,00,000 |
| | Payment Gateway fees | 3,00,000 |
| | Map APIs (Google Maps) | 1,00,000 |
| | CDN (CloudFlare) | 1,00,000 |
| | SSL Certificates | 50,000 |
| | Domain renewal | 50,000 |
| | **Subtotal Third-Party** | **8,50,000** |
| **Training & Documentation** | | |
| | User training sessions | 3,00,000 |
| | Documentation creation | 2,00,000 |
| | Video tutorials | 2,00,000 |
| | Knowledge base setup | 1,00,000 |
| | **Subtotal Training** | **8,00,000** |
| **Contingency (12%)** | | **15,48,000** |
| **TOTAL PHASE 3 BUDGET** | | **1,48,48,000** |

---

## 8. TECHNICAL APPENDICES

### Appendix A: Complete Database Schema

#### A.1 B2B Module Tables

```sql
-- B2B Partner Extension
cREATE TABLE res_partner_b2b_ext (
    partner_id INTEGER PRIMARY KEY REFERENCES res_partner(id) ON DELETE CASCADE,
    is_b2b_partner BOOLEAN DEFAULT FALSE,
    b2b_partner_type VARCHAR(50),
    partner_tier VARCHAR(50) DEFAULT 'bronze',
    trade_license_number VARCHAR(100),
    trade_license_file BYTEA,
    bin_number VARCHAR(100),
    tin_number VARCHAR(100),
    approval_status VARCHAR(50) DEFAULT 'draft',
    approved_by INTEGER REFERENCES res_users(id),
    approved_date TIMESTAMP,
    credit_limit DECIMAL(15,2) DEFAULT 0,
    credit_term_days INTEGER DEFAULT 30,
    credit_status VARCHAR(50) DEFAULT 'active',
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- B2B Delivery Locations
CREATE TABLE b2b_delivery_location (
    id SERIAL PRIMARY KEY,
    partner_id INTEGER NOT NULL REFERENCES res_partner(id) ON DELETE CASCADE,
    sequence INTEGER DEFAULT 10,
    name VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    district VARCHAR(100),
    thana VARCHAR(100),
    postcode VARCHAR(20),
    contact_name VARCHAR(255),
    contact_phone VARCHAR(50),
    contact_email VARCHAR(255),
    is_default BOOLEAN DEFAULT FALSE,
    latitude DECIMAL(10,7),
    longitude DECIMAL(10,7),
    preferred_delivery_time VARCHAR(50),
    active BOOLEAN DEFAULT TRUE,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- B2B Portal Users
CREATE TABLE b2b_portal_user (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES res_users(id) ON DELETE CASCADE,
    partner_id INTEGER NOT NULL REFERENCES res_partner(id) ON DELETE CASCADE,
    role VARCHAR(50) DEFAULT 'buyer',
    can_place_orders BOOLEAN DEFAULT TRUE,
    can_approve_orders BOOLEAN DEFAULT FALSE,
    order_approval_limit DECIMAL(15,2) DEFAULT 0,
    can_view_invoices BOOLEAN DEFAULT TRUE,
    can_make_payments BOOLEAN DEFAULT FALSE,
    can_view_reports BOOLEAN DEFAULT FALSE,
    is_primary BOOLEAN DEFAULT FALSE,
    active BOOLEAN DEFAULT TRUE,
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- B2B Price Lists
CREATE TABLE b2b_pricelist (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    sequence INTEGER DEFAULT 10,
    partner_tier VARCHAR(50) NOT NULL,
    date_start DATE NOT NULL,
    date_end DATE,
    active BOOLEAN DEFAULT TRUE,
    volume_discount_enabled BOOLEAN DEFAULT TRUE,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- B2B Price List Lines
CREATE TABLE b2b_pricelist_line (
    id SERIAL PRIMARY KEY,
    pricelist_id INTEGER NOT NULL REFERENCES b2b_pricelist(id) ON DELETE CASCADE,
    product_id INTEGER NOT NULL REFERENCES product_product(id),
    base_price DECIMAL(12,2) NOT NULL,
    tier_1_min_qty DECIMAL(12,2) DEFAULT 1,
    tier_1_price DECIMAL(12,2),
    tier_2_min_qty DECIMAL(12,2) DEFAULT 10,
    tier_2_price DECIMAL(12,2),
    tier_2_discount DECIMAL(5,2) DEFAULT 3,
    tier_3_min_qty DECIMAL(12,2) DEFAULT 50,
    tier_3_price DECIMAL(12,2),
    tier_3_discount DECIMAL(5,2) DEFAULT 5,
    tier_4_min_qty DECIMAL(12,2) DEFAULT 100,
    tier_4_price DECIMAL(12,2),
    tier_4_discount DECIMAL(5,2) DEFAULT 8,
    promotional_price DECIMAL(12,2),
    promo_date_start DATE,
    promo_date_end DATE,
    is_custom_price BOOLEAN DEFAULT FALSE
);

-- B2B Orders
CREATE TABLE b2b_order (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    partner_id INTEGER NOT NULL REFERENCES res_partner(id),
    b2b_partner_type VARCHAR(50),
    partner_tier VARCHAR(50),
    order_type VARCHAR(50) DEFAULT 'regular',
    state VARCHAR(50) DEFAULT 'draft',
    order_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    requested_delivery_date DATE,
    confirmed_date TIMESTAMP,
    shipped_date TIMESTAMP,
    delivered_date TIMESTAMP,
    delivery_location_id INTEGER REFERENCES b2b_delivery_location(id),
    currency_id INTEGER REFERENCES res_currency(id),
    amount_untaxed DECIMAL(15,2),
    amount_tax DECIMAL(15,2),
    amount_total DECIMAL(15,2),
    payment_method VARCHAR(50) DEFAULT 'credit',
    requires_approval BOOLEAN DEFAULT FALSE,
    approved_by INTEGER REFERENCES res_users(id),
    approved_date TIMESTAMP,
    sale_order_id INTEGER REFERENCES sale_order(id),
    frequency VARCHAR(50),
    next_standing_order_date DATE,
    standing_order_parent_id INTEGER REFERENCES b2b_order(id),
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- B2B Order Lines
CREATE TABLE b2b_order_line (
    id SERIAL PRIMARY KEY,
    order_id INTEGER NOT NULL REFERENCES b2b_order(id) ON DELETE CASCADE,
    sequence INTEGER DEFAULT 10,
    product_id INTEGER NOT NULL REFERENCES product_product(id),
    quantity DECIMAL(12,2) NOT NULL,
    unit_price DECIMAL(12,2) NOT NULL,
    base_price DECIMAL(12,2),
    discount_amount DECIMAL(12,2),
    discount_percent DECIMAL(5,2),
    price_subtotal DECIMAL(12,2),
    backorder_qty DECIMAL(12,2),
    customer_reference VARCHAR(255),
    special_instructions TEXT
);

-- Credit History
CREATE TABLE b2b_credit_history (
    id SERIAL PRIMARY KEY,
    partner_id INTEGER NOT NULL REFERENCES res_partner(id),
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INTEGER REFERENCES res_users(id),
    change_type VARCHAR(50),
    old_limit DECIMAL(15,2),
    new_limit DECIMAL(15,2),
    reason TEXT
);
```

#### A.2 IoT Data Tables (TimescaleDB)

```sql
-- Sensor Readings Hypertable
CREATE TABLE iot_sensor_readings (
    time TIMESTAMPTZ NOT NULL,
    sensor_id VARCHAR(50) NOT NULL,
    sensor_type VARCHAR(50) NOT NULL,
    animal_id INTEGER,
    location VARCHAR(100),
    value DOUBLE PRECISION NOT NULL,
    unit VARCHAR(20),
    quality_score INTEGER DEFAULT 100,
    metadata JSONB
);

SELECT create_hypertable('iot_sensor_readings', 'time', chunk_time_interval => INTERVAL '1 day');

CREATE INDEX idx_sensor_readings_sensor_id ON iot_sensor_readings(sensor_id, time DESC);
CREATE INDEX idx_sensor_readings_animal ON iot_sensor_readings(animal_id, time DESC);
CREATE INDEX idx_sensor_readings_type ON iot_sensor_readings(sensor_type, time DESC);

-- Milk Production Hypertable
CREATE TABLE iot_milk_production (
    time TIMESTAMPTZ NOT NULL,
    animal_id INTEGER NOT NULL,
    session VARCHAR(10) NOT NULL,
    volume_ml DOUBLE PRECISION NOT NULL,
    fat_percent DOUBLE PRECISION,
    snf_percent DOUBLE PRECISION,
    conductivity DOUBLE PRECISION,
    temperature DOUBLE PRECISION
);

SELECT create_hypertable('iot_milk_production', 'time', chunk_time_interval => INTERVAL '7 days');

-- Animal Activity Hypertable
CREATE TABLE iot_animal_activity (
    time TIMESTAMPTZ NOT NULL,
    animal_id INTEGER NOT NULL,
    activity_score INTEGER NOT NULL,
    rumination_minutes INTEGER,
    resting_minutes INTEGER,
    eating_minutes INTEGER,
    walking_minutes INTEGER,
    location_lat DOUBLE PRECISION,
    location_lon DOUBLE PRECISION
);

SELECT create_hypertable('iot_animal_activity', 'time', chunk_time_interval => INTERVAL '7 days');

-- Sensor Metadata
CREATE TABLE iot_sensor_metadata (
    sensor_id VARCHAR(50) PRIMARY KEY,
    sensor_type VARCHAR(50) NOT NULL,
    sensor_model VARCHAR(100),
    serial_number VARCHAR(100),
    installation_date DATE,
    location VARCHAR(100),
    associated_animal_id INTEGER REFERENCES herd_animal(id),
    status VARCHAR(50) DEFAULT 'active',
    battery_level INTEGER,
    last_reading_at TIMESTAMP,
    calibration_date DATE,
    firmware_version VARCHAR(50)
);

-- Alert Rules
CREATE TABLE iot_alert_rules (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    sensor_type VARCHAR(50),
    animal_id INTEGER,
    alert_type VARCHAR(50),
    threshold_min DOUBLE PRECISION,
    threshold_max DOUBLE PRECISION,
    duration_minutes INTEGER DEFAULT 0,
    severity VARCHAR(50) DEFAULT 'warning',
    notification_channels JSONB,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Alert History
CREATE TABLE iot_alert_history (
    id SERIAL PRIMARY KEY,
    rule_id INTEGER REFERENCES iot_alert_rules(id),
    sensor_id VARCHAR(50),
    animal_id INTEGER,
    alert_type VARCHAR(50),
    severity VARCHAR(50),
    message TEXT,
    value DOUBLE PRECISION,
    threshold_value DOUBLE PRECISION,
    triggered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    acknowledged_at TIMESTAMP,
    acknowledged_by INTEGER REFERENCES res_users(id),
    resolved_at TIMESTAMP,
    resolution_notes TEXT
);
```

### Appendix B: API Specifications

#### B.1 B2B REST API

```yaml
openapi: 3.0.0
info:
  title: Smart Dairy B2B API
  version: 1.0.0
  description: B2B Portal API for wholesale customers

paths:
  /api/v1/b2b/partners/register:
    post:
      summary: Register new B2B partner
      requestBody:
        content:
          application/json:
            schema:
              type: object
              required: [company_name, contact_name, email, phone, business_type]
              properties:
                company_name: { type: string }
                contact_name: { type: string }
                email: { type: string, format: email }
                phone: { type: string }
                business_type: { type: string, enum: [distributor, retailer, horeca, institutional] }
                trade_license: { type: string }
                bin_number: { type: string }
      responses:
        201:
          description: Registration submitted successfully
          content:
            application/json:
              schema:
                type: object
                properties:
                  partner_id: { type: integer }
                  approval_status: { type: string }
                  message: { type: string }

  /api/v1/b2b/catalog:
    get:
      summary: Get B2B product catalog
      parameters:
        - name: partner_id
          in: query
          required: true
          schema: { type: integer }
      responses:
        200:
          description: Product catalog with tier pricing
          content:
            application/json:
              schema:
                type: object
                properties:
                  partner_tier: { type: string }
                  products:
                    type: array
                    items:
                      type: object
                      properties:
                        product_id: { type: integer }
                        name: { type: string }
                        base_price: { type: number }
                        tier_prices:
                          type: object
                          properties:
                            tier_1: { type: number }
                            tier_2: { type: number }
                            tier_3: { type: number }
                            tier_4: { type: number }

  /api/v1/b2b/orders:
    post:
      summary: Create B2B order
      requestBody:
        content:
          application/json:
            schema:
              type: object
              required: [partner_id, lines, delivery_location_id]
              properties:
                partner_id: { type: integer }
                delivery_location_id: { type: integer }
                payment_method: { type: string }
                requested_delivery_date: { type: string, format: date }
                lines:
                  type: array
                  items:
                    type: object
                    properties:
                      product_id: { type: integer }
                      quantity: { type: number }
                      unit_price: { type: number }
      responses:
        201:
          description: Order created
          content:
            application/json:
              schema:
                type: object
                properties:
                  order_id: { type: integer }
                  order_reference: { type: string }
                  state: { type: string }
                  amount_total: { type: number }
                  requires_approval: { type: boolean }

  /api/v1/b2b/credit/status/{partner_id}:
    get:
      summary: Get credit status
      parameters:
        - name: partner_id
          in: path
          required: true
          schema: { type: integer }
      responses:
        200:
          description: Credit status information
          content:
            application/json:
              schema:
                type: object
                properties:
                  credit_limit: { type: number }
                  credit_used: { type: number }
                  credit_available: { type: number }
                  utilization_percent: { type: number }
                  status: { type: string }
                  aging:
                    type: object
                    properties:
                      current: { type: number }
                      days_31_60: { type: number }
                      days_61_90: { type: number }
                      over_90: { type: number }
```

#### B.2 IoT Data Ingestion API

```yaml
openapi: 3.0.0
info:
  title: Smart Dairy IoT API
  version: 1.0.0
  description: API for IoT sensor data ingestion

paths:
  /api/v1/iot/sensor-reading:
    post:
      summary: Submit sensor reading
      requestBody:
        content:
          application/json:
            schema:
              type: object
              required: [sensor_id, sensor_type, value, timestamp]
              properties:
                sensor_id: { type: string }
                sensor_type: { type: string }
                value: { type: number }
                timestamp: { type: string, format: date-time }
                unit: { type: string }
                location: { type: string }
                animal_id: { type: integer }
                metadata: { type: object }
      responses:
        201:
          description: Reading accepted

  /api/v1/iot/animal-activity:
    post:
      summary: Submit animal activity data
      requestBody:
        content:
          application/json:
            schema:
              type: object
              required: [animal_id, timestamp, activity_score]
              properties:
                animal_id: { type: integer }
                timestamp: { type: string, format: date-time }
                activity_score: { type: integer, minimum: 0, maximum: 100 }
                rumination_minutes: { type: integer }
                resting_minutes: { type: integer }
                eating_minutes: { type: integer }
                walking_minutes: { type: integer }
                latitude: { type: number }
                longitude: { type: number }
      responses:
        201:
          description: Activity data accepted

  /api/v1/iot/milk-production:
    post:
      summary: Submit milk production data
      requestBody:
        content:
          application/json:
            schema:
              type: object
              required: [animal_id, session, volume_ml, timestamp]
              properties:
                animal_id: { type: integer }
                session: { type: string, enum: [morning, evening] }
                volume_ml: { type: number }
                timestamp: { type: string, format: date-time }
                fat_percent: { type: number }
                snf_percent: { type: number }
                conductivity: { type: number }
                temperature: { type: number }
      responses:
        201:
          description: Milk production data accepted
```

### Appendix C: Environment Configuration

#### C.1 Docker Compose for Phase 3

```yaml
version: '3.8'

services:
  # Odoo Application
  odoo:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: smart_dairy_odoo
    ports:
      - "8069:8069"
    environment:
      - HOST=postgres
      - USER=odoo
      - PASSWORD=${DB_PASSWORD}
      - DB_NAME=smart_dairy_prod
    volumes:
      - odoo-data:/var/lib/odoo
      - ./config:/etc/odoo
      - ./addons:/mnt/extra-addons
    depends_on:
      - postgres
      - redis
      - mqtt
    networks:
      - smart-dairy
    restart: unless-stopped

  # PostgreSQL with TimescaleDB
  postgres:
    image: timescale/timescaledb:latest-pg16
    container_name: smart_dairy_postgres
    ports:
      - "5432:5432"
    environment:
      - POSTGRES_DB=smart_dairy_prod
      - POSTGRES_USER=odoo
      - POSTGRES_PASSWORD=${DB_PASSWORD}
    volumes:
      - postgres-data:/var/lib/postgresql/data
      - ./sql/init_timescaledb.sql:/docker-entrypoint-initdb.d/init.sql
    networks:
      - smart-dairy
    restart: unless-stopped

  # Redis Cache
  redis:
    image: redis:7-alpine
    container_name: smart_dairy_redis
    ports:
      - "6379:6379"
    volumes:
      - redis-data:/data
    networks:
      - smart-dairy
    restart: unless-stopped

  # MQTT Broker for IoT
  mqtt:
    image: eclipse-mosquitto:2
    container_name: smart_dairy_mqtt
    ports:
      - "1883:1883"
      - "9001:9001"
    volumes:
      - ./mqtt/config:/mosquitto/config
      - mqtt-data:/mosquitto/data
    networks:
      - smart-dairy
    restart: unless-stopped

  # IoT Data Ingestion Service
  iot-ingestion:
    build:
      context: ./iot
      dockerfile: Dockerfile
    container_name: smart_dairy_iot
    environment:
      - MQTT_HOST=mqtt
      - MQTT_PORT=1883
      - DB_HOST=postgres
      - DB_NAME=smart_dairy_prod
      - DB_USER=odoo
      - DB_PASSWORD=${DB_PASSWORD}
    depends_on:
      - mqtt
      - postgres
    networks:
      - smart-dairy
    restart: unless-stopped

  # Apache Superset for BI
  superset:
    build:
      context: ./superset
      dockerfile: Dockerfile
    container_name: smart_dairy_superset
    ports:
      - "8088:8088"
    environment:
      - DATABASE_DB=superset
      - DATABASE_HOST=postgres
      - DATABASE_PASSWORD=${DB_PASSWORD}
      - DATABASE_USER=superset
      - SECRET_KEY=${SUPERSET_SECRET_KEY}
    volumes:
      - superset-data:/app/superset_home
    depends_on:
      - postgres
    networks:
      - smart-dairy
    restart: unless-stopped

  # Nginx Load Balancer
  nginx:
    image: nginx:alpine
    container_name: smart_dairy_nginx
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx/nginx.conf:/etc/nginx/nginx.conf
      - ./nginx/ssl:/etc/nginx/ssl
    depends_on:
      - odoo
      - superset
    networks:
      - smart-dairy
    restart: unless-stopped

volumes:
  odoo-data:
  postgres-data:
  redis-data:
  mqtt-data:
  superset-data:

networks:
  smart-dairy:
    driver: bridge
```

### Appendix D: Deployment Checklist

#### D.1 Pre-Deployment Verification

```
[ ] All unit tests passing (>90% coverage)
[ ] Integration tests completed successfully
[ ] Security scan passed (no critical vulnerabilities)
[ ] Performance testing completed (<3s response time)
[ ] Database migration scripts tested
[ ] Backup and recovery procedures verified
[ ] SSL certificates installed and valid
[ ] Domain DNS records configured
[ ] Email/SMS service credentials validated
[ ] Payment gateway integration tested in sandbox
```

#### D.2 Production Deployment Steps

1. **Infrastructure Preparation**
   - Provision production servers
   - Configure firewall rules
   - Setup monitoring and alerting
   - Configure automated backups

2. **Database Deployment**
   - Run database migrations
   - Create TimescaleDB hypertables
   - Import reference data
   - Create database indexes

3. **Application Deployment**
   - Deploy Odoo with B2B modules
   - Deploy IoT ingestion service
   - Deploy BI platform (Superset)
   - Configure Nginx reverse proxy

4. **IoT Setup**
   - Configure MQTT broker
   - Register sensors in database
   - Test sensor connectivity
   - Validate data ingestion

5. **Go-Live**
   - Switch DNS to production
   - Enable monitoring
   - Notify stakeholders
   - Begin user training

---

## CONCLUSION

### Phase 3 Summary

Phase 3 of the Smart Dairy Smart Portal + ERP Implementation (Days 201-300) establishes the complete B2B commerce and intelligent farming capabilities. This phase transforms Smart Dairy from a direct-to-consumer platform into a comprehensive wholesale and technology-driven dairy enterprise.

### Key Achievements by End of Phase 3

1. **B2B Marketplace Portal**: Full-featured wholesale platform with tier-based pricing, credit management, and approval workflows
2. **IoT Sensor Network**: 50+ sensors monitoring cattle health, milk production, and environmental conditions
3. **Predictive Analytics**: ML models for health prediction, heat detection, and milk yield forecasting
4. **Business Intelligence**: Real-time dashboards and automated reporting for data-driven decisions
5. **Multi-Warehouse Operations**: Distributed inventory management with automated replenishment

### Next Phase Preview (Phase 4: Days 301-400)

Phase 4 will focus on:
- Machine Learning optimization and model refinement
- Blockchain integration for supply chain traceability
- Advanced AI for feed optimization
- Expansion to franchisee management
- International export capabilities
- Mobile app enhancements with AR/VR features

---

*Document Version: 1.0*
*Last Updated: February 2026*
*Prepared by: Smart Dairy Implementation Team*

