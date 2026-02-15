# Milestone 81: B2B Customer Portal

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field                | Details                                                      |
| -------------------- | ------------------------------------------------------------ |
| **Document ID**      | SD-IMPL-P9-M81-v1.0                                          |
| **Milestone**        | 81 - B2B Customer Portal                                     |
| **Phase**            | Phase 9: Commerce - B2B Portal Foundation                    |
| **Days**             | 551-560                                                      |
| **Duration**         | 10 Working Days                                              |
| **Status**           | Draft                                                        |
| **Version**          | 1.0.0                                                        |
| **Last Updated**     | 2026-02-04                                                   |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 81 establishes the foundational B2B customer management infrastructure for Smart Dairy's wholesale and institutional sales channels. This milestone implements multi-tier customer registration, KYC verification workflows, approval processes, and the core customer portal interface that will support all subsequent B2B functionality.

### 1.2 Scope

- B2B Customer Model with tier classification (Bronze/Silver/Gold/Platinum)
- Portal User Model for multi-user business accounts
- Delivery Address Management for multiple business locations
- Document Management for KYC verification
- Approval Workflow Engine with configurable stages
- Customer Dashboard with order history
- Tier Benefits Configuration
- Email/SMS Notification integration
- OWL-based Registration Forms
- REST API endpoints for B2B customer operations

### 1.3 Key Outcomes

| Outcome                        | Target                                    |
| ------------------------------ | ----------------------------------------- |
| B2B Registration Time          | < 15 minutes to complete                  |
| KYC Verification               | Document upload and validation working    |
| Approval Workflow              | Multi-stage approval operational          |
| Customer Dashboard             | Order history, profile management live    |
| Tier Benefits                  | Automatic benefit assignment by tier      |
| API Response Time              | < 500ms for all endpoints                 |

---

## 2. Objectives

1. **Create B2B Customer Model** - Design and implement the core B2B customer data model extending Odoo's res.partner with B2B-specific fields
2. **Implement Tier System** - Build Bronze/Silver/Gold/Platinum tier classification with automatic and manual tier assignment
3. **Build Portal User Management** - Enable businesses to have multiple portal users with role-based access
4. **Develop KYC Workflow** - Create document upload, verification, and approval workflow
5. **Design Customer Dashboard** - Build comprehensive OWL dashboard for B2B customers
6. **Implement Notifications** - Set up email/SMS notifications for registration and approval events

---

## 3. Key Deliverables

| #  | Deliverable                          | Owner  | Priority |
| -- | ------------------------------------ | ------ | -------- |
| 1  | B2B Customer Model (b2b.customer)    | Dev 1  | Critical |
| 2  | Customer Tier Model                  | Dev 1  | Critical |
| 3  | Portal User Model (b2b.portal.user)  | Dev 1  | High     |
| 4  | Delivery Address Model               | Dev 2  | High     |
| 5  | Document Model (b2b.customer.document)| Dev 2  | High     |
| 6  | Approval Workflow Engine             | Dev 1  | Critical |
| 7  | KYC Verification Service             | Dev 2  | High     |
| 8  | OWL Registration Form                | Dev 3  | Critical |
| 9  | Customer Dashboard OWL Component     | Dev 3  | High     |
| 10 | Notification Service                 | Dev 2  | Medium   |
| 11 | REST API Endpoints                   | Dev 2  | High     |
| 12 | Unit & Integration Tests             | All    | High     |

---

## 4. Prerequisites

| Prerequisite                       | Source      | Status   |
| ---------------------------------- | ----------- | -------- |
| Phase 8 Payment Integration        | Phase 8     | Complete |
| Odoo res.partner model access      | Odoo Core   | Ready    |
| Email/SMS gateway configuration    | Phase 8     | Complete |
| PostgreSQL 16 database             | Phase 1     | Ready    |
| Redis caching infrastructure       | Phase 1     | Ready    |
| OWL 2.0 framework setup            | Phase 4     | Ready    |

---

## 5. Requirement Traceability

| Req ID       | Description                              | Source | Priority |
| ------------ | ---------------------------------------- | ------ | -------- |
| FR-B2B-001   | Business Registration and Verification   | BRD    | Must     |
| FR-B2B-002   | Multi-user Account Management            | BRD    | Must     |
| FR-B2B-004   | B2B Dashboard                            | BRD    | Must     |
| FR-B2B-009   | Approval Workflows                       | BRD    | Must     |
| B2B-REG-001  | Business Registration                    | RFP    | Must     |
| SRS-B2B-001  | Multi-tenant data isolation              | SRS    | Must     |

---

## 6. Day-by-Day Development Plan

### Day 551 (Day 1): B2B Customer Model Foundation

#### Dev 1 (Backend Lead) - 8 hours

**Task 1.1: Create B2B Customer Model (4h)**

```python
# odoo/addons/smart_dairy_b2b/models/b2b_customer.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
import re

class B2BCustomer(models.Model):
    _name = 'b2b.customer'
    _description = 'B2B Customer'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'name'

    # Basic Information
    name = fields.Char(string='Business Name', required=True, tracking=True)
    code = fields.Char(string='Customer Code', readonly=True, copy=False)
    partner_id = fields.Many2one('res.partner', string='Partner', required=True, ondelete='cascade')

    # Business Registration
    trade_license = fields.Char(string='Trade License Number', tracking=True)
    trade_license_expiry = fields.Date(string='Trade License Expiry')
    tin_number = fields.Char(string='TIN Number', tracking=True)
    bin_number = fields.Char(string='BIN Number', tracking=True)

    # Business Type
    business_type = fields.Selection([
        ('retailer', 'Retailer'),
        ('wholesaler', 'Wholesaler'),
        ('distributor', 'Distributor'),
        ('restaurant', 'Restaurant/Hotel'),
        ('institution', 'Institution'),
        ('other', 'Other'),
    ], string='Business Type', required=True, default='retailer', tracking=True)

    # Contact Information
    primary_contact_name = fields.Char(string='Primary Contact Name', required=True)
    primary_contact_phone = fields.Char(string='Primary Contact Phone', required=True)
    primary_contact_email = fields.Char(string='Primary Contact Email', required=True)

    # Tier Information
    tier_id = fields.Many2one('b2b.customer.tier', string='Customer Tier', tracking=True)
    tier_assigned_date = fields.Date(string='Tier Assigned Date')

    # Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('pending_kyc', 'Pending KYC'),
        ('pending_approval', 'Pending Approval'),
        ('approved', 'Approved'),
        ('rejected', 'Rejected'),
        ('suspended', 'Suspended'),
    ], string='Status', default='draft', tracking=True)

    # Approval
    approved_by = fields.Many2one('res.users', string='Approved By')
    approved_date = fields.Datetime(string='Approved Date')
    rejection_reason = fields.Text(string='Rejection Reason')

    # Related Records
    portal_user_ids = fields.One2many('b2b.portal.user', 'customer_id', string='Portal Users')
    document_ids = fields.One2many('b2b.customer.document', 'customer_id', string='Documents')
    address_ids = fields.One2many('b2b.delivery.address', 'customer_id', string='Delivery Addresses')

    # Statistics
    total_orders = fields.Integer(string='Total Orders', compute='_compute_statistics', store=True)
    total_spent = fields.Monetary(string='Total Spent', compute='_compute_statistics', store=True)
    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.company.currency_id)

    # Timestamps
    registration_date = fields.Datetime(string='Registration Date', default=fields.Datetime.now)
    last_order_date = fields.Datetime(string='Last Order Date')

    _sql_constraints = [
        ('trade_license_unique', 'UNIQUE(trade_license)', 'Trade License must be unique!'),
        ('tin_unique', 'UNIQUE(tin_number)', 'TIN Number must be unique!'),
    ]

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            if not vals.get('code'):
                vals['code'] = self.env['ir.sequence'].next_by_code('b2b.customer.sequence')
        return super().create(vals_list)

    @api.constrains('primary_contact_email')
    def _check_email(self):
        email_regex = r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$'
        for record in self:
            if record.primary_contact_email and not re.match(email_regex, record.primary_contact_email):
                raise ValidationError('Invalid email format!')

    @api.constrains('primary_contact_phone')
    def _check_phone(self):
        phone_regex = r'^(\+880|880|0)?1[3-9]\d{8}$'
        for record in self:
            if record.primary_contact_phone:
                cleaned = re.sub(r'[\s\-]', '', record.primary_contact_phone)
                if not re.match(phone_regex, cleaned):
                    raise ValidationError('Invalid Bangladesh phone number!')

    @api.depends('partner_id.sale_order_ids')
    def _compute_statistics(self):
        for record in self:
            orders = self.env['sale.order'].search([
                ('partner_id', '=', record.partner_id.id),
                ('state', 'in', ['sale', 'done'])
            ])
            record.total_orders = len(orders)
            record.total_spent = sum(orders.mapped('amount_total'))

    def action_submit_kyc(self):
        """Submit for KYC verification"""
        self.ensure_one()
        if not self.document_ids:
            raise ValidationError('Please upload required documents before submitting for KYC.')
        self.write({'state': 'pending_kyc'})
        self._send_notification('kyc_submitted')

    def action_approve(self):
        """Approve customer registration"""
        self.ensure_one()
        self.write({
            'state': 'approved',
            'approved_by': self.env.user.id,
            'approved_date': fields.Datetime.now(),
        })
        self._assign_default_tier()
        self._send_notification('approved')

    def action_reject(self):
        """Open rejection wizard"""
        return {
            'type': 'ir.actions.act_window',
            'name': 'Reject Customer',
            'res_model': 'b2b.customer.reject.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_customer_id': self.id},
        }

    def _assign_default_tier(self):
        """Assign default Bronze tier to new customers"""
        bronze_tier = self.env['b2b.customer.tier'].search([('code', '=', 'bronze')], limit=1)
        if bronze_tier:
            self.write({
                'tier_id': bronze_tier.id,
                'tier_assigned_date': fields.Date.today(),
            })

    def _send_notification(self, notification_type):
        """Send email/SMS notification"""
        template_map = {
            'kyc_submitted': 'smart_dairy_b2b.email_template_kyc_submitted',
            'approved': 'smart_dairy_b2b.email_template_customer_approved',
            'rejected': 'smart_dairy_b2b.email_template_customer_rejected',
        }
        template_id = self.env.ref(template_map.get(notification_type), raise_if_not_found=False)
        if template_id:
            template_id.send_mail(self.id, force_send=True)
```

**Task 1.2: Create Customer Tier Model (2h)**

```python
# odoo/addons/smart_dairy_b2b/models/b2b_customer_tier.py
from odoo import models, fields, api

class B2BCustomerTier(models.Model):
    _name = 'b2b.customer.tier'
    _description = 'B2B Customer Tier'
    _order = 'sequence'

    name = fields.Char(string='Tier Name', required=True)
    code = fields.Char(string='Tier Code', required=True)
    sequence = fields.Integer(string='Sequence', default=10)
    color = fields.Char(string='Color Code')

    # Tier Benefits
    discount_percentage = fields.Float(string='Default Discount %', default=0.0)
    credit_limit = fields.Monetary(string='Credit Limit')
    payment_term_id = fields.Many2one('account.payment.term', string='Payment Terms')

    # Qualification Criteria
    min_orders = fields.Integer(string='Minimum Orders', help='Min orders to qualify')
    min_spent = fields.Monetary(string='Minimum Spent', help='Min amount spent to qualify')
    min_months = fields.Integer(string='Minimum Active Months', help='Min months as active customer')

    # Features
    priority_support = fields.Boolean(string='Priority Support', default=False)
    dedicated_account_manager = fields.Boolean(string='Dedicated Account Manager', default=False)
    express_delivery = fields.Boolean(string='Express Delivery Available', default=False)
    custom_catalog = fields.Boolean(string='Custom Catalog Access', default=False)

    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.company.currency_id)
    active = fields.Boolean(default=True)

    customer_count = fields.Integer(string='Customer Count', compute='_compute_customer_count')

    @api.depends()
    def _compute_customer_count(self):
        for tier in self:
            tier.customer_count = self.env['b2b.customer'].search_count([('tier_id', '=', tier.id)])
```

**Task 1.3: Create Sequence and Security (2h)**

```xml
<!-- odoo/addons/smart_dairy_b2b/data/b2b_sequence.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <data noupdate="1">
        <record id="seq_b2b_customer" model="ir.sequence">
            <field name="name">B2B Customer Sequence</field>
            <field name="code">b2b.customer.sequence</field>
            <field name="prefix">B2B-</field>
            <field name="padding">5</field>
        </record>

        <!-- Default Tiers -->
        <record id="tier_bronze" model="b2b.customer.tier">
            <field name="name">Bronze</field>
            <field name="code">bronze</field>
            <field name="sequence">10</field>
            <field name="color">#CD7F32</field>
            <field name="discount_percentage">0</field>
            <field name="credit_limit">100000</field>
            <field name="min_orders">0</field>
            <field name="min_spent">0</field>
        </record>

        <record id="tier_silver" model="b2b.customer.tier">
            <field name="name">Silver</field>
            <field name="code">silver</field>
            <field name="sequence">20</field>
            <field name="color">#C0C0C0</field>
            <field name="discount_percentage">5</field>
            <field name="credit_limit">500000</field>
            <field name="min_orders">10</field>
            <field name="min_spent">100000</field>
            <field name="priority_support">True</field>
        </record>

        <record id="tier_gold" model="b2b.customer.tier">
            <field name="name">Gold</field>
            <field name="code">gold</field>
            <field name="sequence">30</field>
            <field name="color">#FFD700</field>
            <field name="discount_percentage">10</field>
            <field name="credit_limit">2000000</field>
            <field name="min_orders">50</field>
            <field name="min_spent">500000</field>
            <field name="priority_support">True</field>
            <field name="express_delivery">True</field>
        </record>

        <record id="tier_platinum" model="b2b.customer.tier">
            <field name="name">Platinum</field>
            <field name="code">platinum</field>
            <field name="sequence">40</field>
            <field name="color">#E5E4E2</field>
            <field name="discount_percentage">15</field>
            <field name="credit_limit">5000000</field>
            <field name="min_orders">100</field>
            <field name="min_spent">2000000</field>
            <field name="priority_support">True</field>
            <field name="dedicated_account_manager">True</field>
            <field name="express_delivery">True</field>
            <field name="custom_catalog">True</field>
        </record>
    </data>
</odoo>
```

#### Dev 2 (Full-Stack) - 8 hours

**Task 2.1: Create Delivery Address Model (4h)**

```python
# odoo/addons/smart_dairy_b2b/models/b2b_delivery_address.py
from odoo import models, fields, api

class B2BDeliveryAddress(models.Model):
    _name = 'b2b.delivery.address'
    _description = 'B2B Delivery Address'
    _order = 'is_default desc, name'

    name = fields.Char(string='Address Name', required=True)
    customer_id = fields.Many2one('b2b.customer', string='Customer', required=True, ondelete='cascade')
    partner_id = fields.Many2one('res.partner', string='Partner Address')

    # Address Fields
    street = fields.Char(string='Street Address', required=True)
    street2 = fields.Char(string='Street Address 2')
    city = fields.Char(string='City', required=True)
    district = fields.Char(string='District')
    division = fields.Selection([
        ('dhaka', 'Dhaka'),
        ('chittagong', 'Chittagong'),
        ('rajshahi', 'Rajshahi'),
        ('khulna', 'Khulna'),
        ('barisal', 'Barisal'),
        ('sylhet', 'Sylhet'),
        ('rangpur', 'Rangpur'),
        ('mymensingh', 'Mymensingh'),
    ], string='Division', required=True)
    zip_code = fields.Char(string='Postal Code')
    country_id = fields.Many2one('res.country', string='Country',
                                  default=lambda self: self.env.ref('base.bd'))

    # Contact
    contact_name = fields.Char(string='Contact Person')
    contact_phone = fields.Char(string='Contact Phone', required=True)

    # Delivery Settings
    is_default = fields.Boolean(string='Default Address', default=False)
    delivery_zone_id = fields.Many2one('delivery.zone', string='Delivery Zone')
    delivery_instructions = fields.Text(string='Delivery Instructions')

    # GPS Coordinates
    latitude = fields.Float(string='Latitude', digits=(10, 7))
    longitude = fields.Float(string='Longitude', digits=(10, 7))

    active = fields.Boolean(default=True)

    @api.model_create_multi
    def create(self, vals_list):
        records = super().create(vals_list)
        for record in records:
            if record.is_default:
                # Unset other default addresses
                self.search([
                    ('customer_id', '=', record.customer_id.id),
                    ('id', '!=', record.id),
                    ('is_default', '=', True)
                ]).write({'is_default': False})
            # Create corresponding res.partner address
            record._create_partner_address()
        return records

    def _create_partner_address(self):
        """Create corresponding partner address for Odoo integration"""
        self.ensure_one()
        if not self.partner_id:
            partner = self.env['res.partner'].create({
                'name': self.name,
                'type': 'delivery',
                'parent_id': self.customer_id.partner_id.id,
                'street': self.street,
                'street2': self.street2,
                'city': self.city,
                'zip': self.zip_code,
                'country_id': self.country_id.id,
                'phone': self.contact_phone,
            })
            self.partner_id = partner.id
```

**Task 2.2: Create Document Model (4h)**

```python
# odoo/addons/smart_dairy_b2b/models/b2b_customer_document.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
import base64
import hashlib

class B2BCustomerDocument(models.Model):
    _name = 'b2b.customer.document'
    _description = 'B2B Customer Document'
    _order = 'create_date desc'

    name = fields.Char(string='Document Name', required=True)
    customer_id = fields.Many2one('b2b.customer', string='Customer', required=True, ondelete='cascade')

    document_type = fields.Selection([
        ('trade_license', 'Trade License'),
        ('tin_certificate', 'TIN Certificate'),
        ('bin_certificate', 'BIN Certificate'),
        ('bank_statement', 'Bank Statement'),
        ('nid', 'National ID'),
        ('incorporation', 'Certificate of Incorporation'),
        ('memorandum', 'Memorandum of Association'),
        ('other', 'Other'),
    ], string='Document Type', required=True)

    # File
    file = fields.Binary(string='Document File', required=True, attachment=True)
    file_name = fields.Char(string='File Name')
    file_size = fields.Integer(string='File Size', compute='_compute_file_size')
    file_hash = fields.Char(string='File Hash', compute='_compute_file_hash', store=True)

    # Verification
    state = fields.Selection([
        ('pending', 'Pending Review'),
        ('verified', 'Verified'),
        ('rejected', 'Rejected'),
    ], string='Status', default='pending')

    verified_by = fields.Many2one('res.users', string='Verified By')
    verified_date = fields.Datetime(string='Verified Date')
    rejection_reason = fields.Text(string='Rejection Reason')

    # Validity
    issue_date = fields.Date(string='Issue Date')
    expiry_date = fields.Date(string='Expiry Date')
    is_expired = fields.Boolean(string='Is Expired', compute='_compute_is_expired')

    notes = fields.Text(string='Notes')

    @api.depends('file')
    def _compute_file_size(self):
        for record in self:
            if record.file:
                record.file_size = len(base64.b64decode(record.file))
            else:
                record.file_size = 0

    @api.depends('file')
    def _compute_file_hash(self):
        for record in self:
            if record.file:
                record.file_hash = hashlib.sha256(base64.b64decode(record.file)).hexdigest()
            else:
                record.file_hash = False

    @api.depends('expiry_date')
    def _compute_is_expired(self):
        today = fields.Date.today()
        for record in self:
            record.is_expired = record.expiry_date and record.expiry_date < today

    @api.constrains('file')
    def _check_file_size(self):
        max_size = 10 * 1024 * 1024  # 10 MB
        for record in self:
            if record.file_size > max_size:
                raise ValidationError('File size cannot exceed 10 MB!')

    def action_verify(self):
        """Verify document"""
        self.write({
            'state': 'verified',
            'verified_by': self.env.user.id,
            'verified_date': fields.Datetime.now(),
        })
        # Check if all required documents are verified
        self._check_kyc_complete()

    def action_reject(self):
        """Open rejection wizard"""
        return {
            'type': 'ir.actions.act_window',
            'name': 'Reject Document',
            'res_model': 'b2b.document.reject.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_document_id': self.id},
        }

    def _check_kyc_complete(self):
        """Check if KYC is complete and move customer to pending approval"""
        required_types = ['trade_license', 'tin_certificate']
        customer = self.customer_id
        verified_types = customer.document_ids.filtered(
            lambda d: d.state == 'verified'
        ).mapped('document_type')

        if all(t in verified_types for t in required_types):
            if customer.state == 'pending_kyc':
                customer.write({'state': 'pending_approval'})
```

#### Dev 3 (Frontend Lead) - 8 hours

**Task 3.1: Create Basic OWL Registration Component (8h)**

```javascript
/** @odoo-module */
// odoo/addons/smart_dairy_b2b/static/src/js/b2b_registration_form.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";
import { registry } from "@web/core/registry";

export class B2BRegistrationForm extends Component {
    static template = "smart_dairy_b2b.B2BRegistrationForm";
    static props = {};

    setup() {
        this.orm = useService("orm");
        this.notification = useService("notification");
        this.state = useState({
            step: 1,
            totalSteps: 4,
            isLoading: false,
            errors: {},
            formData: {
                // Step 1: Business Information
                businessName: "",
                businessType: "retailer",
                tradeLicense: "",
                tradeLicenseExpiry: "",
                tinNumber: "",
                binNumber: "",

                // Step 2: Contact Information
                primaryContactName: "",
                primaryContactPhone: "",
                primaryContactEmail: "",

                // Step 3: Address
                street: "",
                street2: "",
                city: "",
                district: "",
                division: "dhaka",
                zipCode: "",

                // Step 4: Documents
                documents: [],
            },
            divisions: [
                { value: "dhaka", label: "Dhaka" },
                { value: "chittagong", label: "Chittagong" },
                { value: "rajshahi", label: "Rajshahi" },
                { value: "khulna", label: "Khulna" },
                { value: "barisal", label: "Barisal" },
                { value: "sylhet", label: "Sylhet" },
                { value: "rangpur", label: "Rangpur" },
                { value: "mymensingh", label: "Mymensingh" },
            ],
            businessTypes: [
                { value: "retailer", label: "Retailer" },
                { value: "wholesaler", label: "Wholesaler" },
                { value: "distributor", label: "Distributor" },
                { value: "restaurant", label: "Restaurant/Hotel" },
                { value: "institution", label: "Institution" },
                { value: "other", label: "Other" },
            ],
        });
    }

    validateStep(step) {
        const errors = {};
        const data = this.state.formData;

        switch (step) {
            case 1:
                if (!data.businessName?.trim()) {
                    errors.businessName = "Business name is required";
                }
                if (!data.tradeLicense?.trim()) {
                    errors.tradeLicense = "Trade license is required";
                }
                break;
            case 2:
                if (!data.primaryContactName?.trim()) {
                    errors.primaryContactName = "Contact name is required";
                }
                if (!data.primaryContactPhone?.trim()) {
                    errors.primaryContactPhone = "Phone number is required";
                } else if (!this.validateBangladeshPhone(data.primaryContactPhone)) {
                    errors.primaryContactPhone = "Invalid Bangladesh phone number";
                }
                if (!data.primaryContactEmail?.trim()) {
                    errors.primaryContactEmail = "Email is required";
                } else if (!this.validateEmail(data.primaryContactEmail)) {
                    errors.primaryContactEmail = "Invalid email format";
                }
                break;
            case 3:
                if (!data.street?.trim()) {
                    errors.street = "Street address is required";
                }
                if (!data.city?.trim()) {
                    errors.city = "City is required";
                }
                break;
        }

        this.state.errors = errors;
        return Object.keys(errors).length === 0;
    }

    validateEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    validateBangladeshPhone(phone) {
        const cleaned = phone.replace(/[\s\-]/g, "");
        const regex = /^(\+880|880|0)?1[3-9]\d{8}$/;
        return regex.test(cleaned);
    }

    nextStep() {
        if (this.validateStep(this.state.step)) {
            this.state.step++;
        }
    }

    prevStep() {
        if (this.state.step > 1) {
            this.state.step--;
        }
    }

    updateField(field, event) {
        this.state.formData[field] = event.target.value;
        if (this.state.errors[field]) {
            delete this.state.errors[field];
        }
    }

    async handleFileUpload(event, docType) {
        const file = event.target.files[0];
        if (file) {
            if (file.size > 10 * 1024 * 1024) {
                this.notification.add("File size must be less than 10MB", { type: "danger" });
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                const base64 = e.target.result.split(",")[1];
                this.state.formData.documents.push({
                    type: docType,
                    name: file.name,
                    data: base64,
                });
            };
            reader.readAsDataURL(file);
        }
    }

    removeDocument(index) {
        this.state.formData.documents.splice(index, 1);
    }

    async submitRegistration() {
        if (!this.validateStep(this.state.step)) {
            return;
        }

        this.state.isLoading = true;
        try {
            const result = await this.orm.call(
                "b2b.customer",
                "create_from_registration",
                [this.state.formData]
            );

            this.notification.add("Registration submitted successfully!", { type: "success" });
            window.location.href = "/b2b/registration/success";
        } catch (error) {
            this.notification.add(error.message || "Registration failed", { type: "danger" });
        } finally {
            this.state.isLoading = false;
        }
    }
}

registry.category("public_components").add("B2BRegistrationForm", B2BRegistrationForm);
```

**End of Day 551 Deliverables:**
- [ ] B2B Customer Model with all fields
- [ ] Customer Tier Model with 4 default tiers
- [ ] Delivery Address Model
- [ ] Document Model with verification workflow
- [ ] Basic OWL registration form component
- [ ] Database sequences and initial data

---

### Day 552 (Day 2): Approval Workflow & KYC

#### Dev 1 (Backend Lead) - 8 hours

**Task 1.1: Create Approval Workflow Engine (5h)**

```python
# odoo/addons/smart_dairy_b2b/models/b2b_approval_workflow.py
from odoo import models, fields, api
from odoo.exceptions import UserError
import logging

_logger = logging.getLogger(__name__)

class B2BApprovalWorkflow(models.Model):
    _name = 'b2b.approval.workflow'
    _description = 'B2B Approval Workflow'
    _order = 'sequence'

    name = fields.Char(string='Stage Name', required=True)
    sequence = fields.Integer(string='Sequence', default=10)

    # Stage Type
    stage_type = fields.Selection([
        ('kyc_review', 'KYC Document Review'),
        ('credit_check', 'Credit Check'),
        ('manager_approval', 'Manager Approval'),
        ('final_approval', 'Final Approval'),
    ], string='Stage Type', required=True)

    # Approvers
    approver_user_ids = fields.Many2many('res.users', string='Approvers')
    approver_group_id = fields.Many2one('res.groups', string='Approver Group')

    # Requirements
    required_documents = fields.Many2many(
        'b2b.document.type',
        string='Required Documents'
    )
    min_approvals = fields.Integer(string='Minimum Approvals', default=1)

    # Auto-approval
    auto_approve = fields.Boolean(string='Auto Approve', default=False)
    auto_approve_condition = fields.Text(string='Auto Approve Condition (Python)')

    # Notifications
    notify_on_entry = fields.Boolean(string='Notify on Stage Entry', default=True)
    notification_template_id = fields.Many2one('mail.template', string='Notification Template')

    active = fields.Boolean(default=True)


class B2BApprovalRequest(models.Model):
    _name = 'b2b.approval.request'
    _description = 'B2B Approval Request'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'create_date desc'

    name = fields.Char(string='Request Number', readonly=True)
    customer_id = fields.Many2one('b2b.customer', string='Customer', required=True, ondelete='cascade')
    workflow_stage_id = fields.Many2one('b2b.approval.workflow', string='Current Stage')

    state = fields.Selection([
        ('pending', 'Pending'),
        ('in_progress', 'In Progress'),
        ('approved', 'Approved'),
        ('rejected', 'Rejected'),
    ], string='Status', default='pending', tracking=True)

    # Approvals
    approval_ids = fields.One2many('b2b.approval.line', 'request_id', string='Approvals')
    current_approvers = fields.Many2many('res.users', compute='_compute_current_approvers')

    # Timeline
    requested_date = fields.Datetime(string='Requested Date', default=fields.Datetime.now)
    completed_date = fields.Datetime(string='Completed Date')

    notes = fields.Text(string='Notes')

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            vals['name'] = self.env['ir.sequence'].next_by_code('b2b.approval.request')
        return super().create(vals_list)

    @api.depends('workflow_stage_id')
    def _compute_current_approvers(self):
        for record in self:
            approvers = self.env['res.users']
            if record.workflow_stage_id:
                stage = record.workflow_stage_id
                approvers |= stage.approver_user_ids
                if stage.approver_group_id:
                    approvers |= stage.approver_group_id.users
            record.current_approvers = approvers

    def action_start_workflow(self):
        """Start the approval workflow"""
        self.ensure_one()
        first_stage = self.env['b2b.approval.workflow'].search([], order='sequence', limit=1)
        if first_stage:
            self.write({
                'workflow_stage_id': first_stage.id,
                'state': 'in_progress',
            })
            self._notify_approvers()
            self._check_auto_approve()

    def action_approve(self, notes=None):
        """Current user approves this stage"""
        self.ensure_one()
        if self.env.user not in self.current_approvers:
            raise UserError('You are not authorized to approve this request.')

        self.env['b2b.approval.line'].create({
            'request_id': self.id,
            'stage_id': self.workflow_stage_id.id,
            'user_id': self.env.user.id,
            'action': 'approve',
            'notes': notes,
        })

        self._check_stage_completion()

    def action_reject(self, reason):
        """Reject the request"""
        self.ensure_one()
        self.env['b2b.approval.line'].create({
            'request_id': self.id,
            'stage_id': self.workflow_stage_id.id,
            'user_id': self.env.user.id,
            'action': 'reject',
            'notes': reason,
        })

        self.write({
            'state': 'rejected',
            'completed_date': fields.Datetime.now(),
        })
        self.customer_id.write({
            'state': 'rejected',
            'rejection_reason': reason,
        })

    def _check_stage_completion(self):
        """Check if current stage is complete and move to next"""
        stage = self.workflow_stage_id
        approvals = self.approval_ids.filtered(
            lambda a: a.stage_id == stage and a.action == 'approve'
        )

        if len(approvals) >= stage.min_approvals:
            self._move_to_next_stage()

    def _move_to_next_stage(self):
        """Move to the next workflow stage"""
        current_seq = self.workflow_stage_id.sequence
        next_stage = self.env['b2b.approval.workflow'].search([
            ('sequence', '>', current_seq)
        ], order='sequence', limit=1)

        if next_stage:
            self.write({'workflow_stage_id': next_stage.id})
            self._notify_approvers()
            self._check_auto_approve()
        else:
            # Workflow complete
            self.write({
                'state': 'approved',
                'completed_date': fields.Datetime.now(),
            })
            self.customer_id.action_approve()

    def _check_auto_approve(self):
        """Check if auto-approval conditions are met"""
        stage = self.workflow_stage_id
        if stage.auto_approve and stage.auto_approve_condition:
            try:
                result = safe_eval(stage.auto_approve_condition, {
                    'customer': self.customer_id,
                    'request': self,
                })
                if result:
                    self.action_approve(notes='Auto-approved by system')
            except Exception as e:
                _logger.warning(f'Auto-approve condition failed: {e}')

    def _notify_approvers(self):
        """Send notifications to current approvers"""
        stage = self.workflow_stage_id
        if stage.notify_on_entry and stage.notification_template_id:
            for approver in self.current_approvers:
                stage.notification_template_id.with_context(
                    approver=approver
                ).send_mail(self.id, force_send=True)


class B2BApprovalLine(models.Model):
    _name = 'b2b.approval.line'
    _description = 'B2B Approval Line'
    _order = 'create_date'

    request_id = fields.Many2one('b2b.approval.request', string='Request', required=True, ondelete='cascade')
    stage_id = fields.Many2one('b2b.approval.workflow', string='Stage')
    user_id = fields.Many2one('res.users', string='User', default=lambda self: self.env.user)

    action = fields.Selection([
        ('approve', 'Approved'),
        ('reject', 'Rejected'),
        ('request_info', 'Requested More Info'),
    ], string='Action', required=True)

    notes = fields.Text(string='Notes')
    action_date = fields.Datetime(string='Action Date', default=fields.Datetime.now)
```

**Task 1.2: KYC Verification Service (3h)**

```python
# odoo/addons/smart_dairy_b2b/services/kyc_service.py
from odoo import models, api
import logging

_logger = logging.getLogger(__name__)

class KYCVerificationService(models.AbstractModel):
    _name = 'b2b.kyc.service'
    _description = 'KYC Verification Service'

    @api.model
    def get_required_documents(self, business_type):
        """Get required documents based on business type"""
        base_docs = ['trade_license', 'tin_certificate']

        additional_docs = {
            'retailer': [],
            'wholesaler': ['bank_statement'],
            'distributor': ['bank_statement', 'incorporation'],
            'restaurant': ['bank_statement'],
            'institution': ['incorporation', 'memorandum'],
        }

        return base_docs + additional_docs.get(business_type, [])

    @api.model
    def check_kyc_status(self, customer_id):
        """Check KYC completion status for a customer"""
        customer = self.env['b2b.customer'].browse(customer_id)
        required = self.get_required_documents(customer.business_type)

        uploaded = customer.document_ids.mapped('document_type')
        verified = customer.document_ids.filtered(
            lambda d: d.state == 'verified'
        ).mapped('document_type')

        return {
            'required': required,
            'uploaded': list(set(uploaded)),
            'verified': list(set(verified)),
            'missing': [d for d in required if d not in uploaded],
            'pending_verification': [d for d in uploaded if d not in verified],
            'is_complete': all(d in verified for d in required),
        }

    @api.model
    def validate_trade_license(self, license_number):
        """Validate trade license format"""
        # Bangladesh trade license format validation
        if not license_number:
            return False, "Trade license number is required"
        if len(license_number) < 5:
            return False, "Trade license number too short"
        return True, None

    @api.model
    def validate_tin(self, tin_number):
        """Validate TIN number format"""
        if not tin_number:
            return False, "TIN number is required"
        # TIN format: 12 digits
        cleaned = ''.join(filter(str.isdigit, tin_number))
        if len(cleaned) != 12:
            return False, "TIN must be 12 digits"
        return True, None
```

#### Dev 2 (Full-Stack) - 8 hours

**Task 2.1: Create Notification Templates (4h)**

```xml
<!-- odoo/addons/smart_dairy_b2b/data/email_templates.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <data noupdate="1">
        <!-- Registration Submitted -->
        <record id="email_template_registration_submitted" model="mail.template">
            <field name="name">B2B: Registration Submitted</field>
            <field name="model_id" ref="model_b2b_customer"/>
            <field name="subject">Registration Received - Smart Dairy B2B Portal</field>
            <field name="email_from">noreply@smartdairy.com</field>
            <field name="email_to">{{ object.primary_contact_email }}</field>
            <field name="body_html" type="html">
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <h2 style="color: #2c5282;">Welcome to Smart Dairy B2B Portal</h2>
    <p>Dear {{ object.primary_contact_name }},</p>
    <p>Thank you for registering <strong>{{ object.name }}</strong> on the Smart Dairy B2B Portal.</p>
    <p>Your registration has been received and is pending KYC verification. Please upload the following documents:</p>
    <ul>
        <li>Trade License</li>
        <li>TIN Certificate</li>
    </ul>
    <p>Once verified, your account will be reviewed for approval.</p>
    <p><strong>Your Customer Code:</strong> {{ object.code }}</p>
    <p>Best regards,<br/>Smart Dairy Team</p>
</div>
            </field>
        </record>

        <!-- KYC Submitted -->
        <record id="email_template_kyc_submitted" model="mail.template">
            <field name="name">B2B: KYC Submitted for Review</field>
            <field name="model_id" ref="model_b2b_customer"/>
            <field name="subject">KYC Documents Submitted - {{ object.name }}</field>
            <field name="email_from">noreply@smartdairy.com</field>
            <field name="email_to">{{ object.primary_contact_email }}</field>
            <field name="body_html" type="html">
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <h2 style="color: #2c5282;">KYC Documents Received</h2>
    <p>Dear {{ object.primary_contact_name }},</p>
    <p>We have received your KYC documents for <strong>{{ object.name }}</strong>.</p>
    <p>Our team will review your documents within 24-48 hours. You will receive a notification once the review is complete.</p>
    <p>Best regards,<br/>Smart Dairy Team</p>
</div>
            </field>
        </record>

        <!-- Customer Approved -->
        <record id="email_template_customer_approved" model="mail.template">
            <field name="name">B2B: Registration Approved</field>
            <field name="model_id" ref="model_b2b_customer"/>
            <field name="subject">Congratulations! Your B2B Account is Approved</field>
            <field name="email_from">noreply@smartdairy.com</field>
            <field name="email_to">{{ object.primary_contact_email }}</field>
            <field name="body_html" type="html">
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <h2 style="color: #38a169;">Registration Approved!</h2>
    <p>Dear {{ object.primary_contact_name }},</p>
    <p>We are pleased to inform you that your B2B account for <strong>{{ object.name }}</strong> has been approved!</p>
    <p><strong>Account Details:</strong></p>
    <ul>
        <li>Customer Code: {{ object.code }}</li>
        <li>Tier: {{ object.tier_id.name }}</li>
        <li>Credit Limit: BDT {{ object.tier_id.credit_limit }}</li>
    </ul>
    <p>You can now log in to the B2B portal and start placing orders.</p>
    <a href="/b2b/portal" style="background-color: #2c5282; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Access B2B Portal</a>
    <p>Best regards,<br/>Smart Dairy Team</p>
</div>
            </field>
        </record>

        <!-- Customer Rejected -->
        <record id="email_template_customer_rejected" model="mail.template">
            <field name="name">B2B: Registration Rejected</field>
            <field name="model_id" ref="model_b2b_customer"/>
            <field name="subject">Registration Update - Smart Dairy B2B</field>
            <field name="email_from">noreply@smartdairy.com</field>
            <field name="email_to">{{ object.primary_contact_email }}</field>
            <field name="body_html" type="html">
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <h2 style="color: #c53030;">Registration Not Approved</h2>
    <p>Dear {{ object.primary_contact_name }},</p>
    <p>We regret to inform you that your B2B registration for <strong>{{ object.name }}</strong> could not be approved at this time.</p>
    <p><strong>Reason:</strong> {{ object.rejection_reason }}</p>
    <p>If you believe this is an error or wish to provide additional information, please contact our support team.</p>
    <p>Best regards,<br/>Smart Dairy Team</p>
</div>
            </field>
        </record>
    </data>
</odoo>
```

**Task 2.2: Create API Endpoints (4h)**

```python
# odoo/addons/smart_dairy_b2b/controllers/api.py
from odoo import http
from odoo.http import request
import json
import logging

_logger = logging.getLogger(__name__)

class B2BCustomerAPI(http.Controller):

    @http.route('/api/v1/b2b/register', type='json', auth='public', methods=['POST'], csrf=False)
    def register_customer(self, **kwargs):
        """Register a new B2B customer"""
        try:
            data = request.jsonrequest

            # Validate required fields
            required = ['businessName', 'businessType', 'tradeLicense',
                       'primaryContactName', 'primaryContactPhone', 'primaryContactEmail',
                       'street', 'city', 'division']
            missing = [f for f in required if not data.get(f)]
            if missing:
                return {'success': False, 'error': f'Missing fields: {", ".join(missing)}'}

            # Create partner
            partner = request.env['res.partner'].sudo().create({
                'name': data['businessName'],
                'is_company': True,
                'email': data['primaryContactEmail'],
                'phone': data['primaryContactPhone'],
                'street': data['street'],
                'street2': data.get('street2', ''),
                'city': data['city'],
                'zip': data.get('zipCode', ''),
            })

            # Create B2B customer
            customer = request.env['b2b.customer'].sudo().create({
                'name': data['businessName'],
                'partner_id': partner.id,
                'business_type': data['businessType'],
                'trade_license': data['tradeLicense'],
                'trade_license_expiry': data.get('tradeLicenseExpiry'),
                'tin_number': data.get('tinNumber'),
                'bin_number': data.get('binNumber'),
                'primary_contact_name': data['primaryContactName'],
                'primary_contact_phone': data['primaryContactPhone'],
                'primary_contact_email': data['primaryContactEmail'],
                'state': 'draft',
            })

            # Create delivery address
            request.env['b2b.delivery.address'].sudo().create({
                'name': 'Primary Address',
                'customer_id': customer.id,
                'street': data['street'],
                'street2': data.get('street2', ''),
                'city': data['city'],
                'district': data.get('district', ''),
                'division': data['division'],
                'zip_code': data.get('zipCode', ''),
                'contact_name': data['primaryContactName'],
                'contact_phone': data['primaryContactPhone'],
                'is_default': True,
            })

            # Handle document uploads
            for doc in data.get('documents', []):
                request.env['b2b.customer.document'].sudo().create({
                    'name': doc.get('name', 'Document'),
                    'customer_id': customer.id,
                    'document_type': doc['type'],
                    'file': doc['data'],
                    'file_name': doc.get('name'),
                })

            # Send notification
            customer._send_notification('registration_submitted')

            return {
                'success': True,
                'customer_id': customer.id,
                'customer_code': customer.code,
                'message': 'Registration submitted successfully',
            }

        except Exception as e:
            _logger.exception('B2B registration failed')
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/b2b/customer/<int:customer_id>', type='json', auth='user', methods=['GET'])
    def get_customer(self, customer_id):
        """Get customer details"""
        customer = request.env['b2b.customer'].browse(customer_id)
        if not customer.exists():
            return {'success': False, 'error': 'Customer not found'}

        return {
            'success': True,
            'data': {
                'id': customer.id,
                'code': customer.code,
                'name': customer.name,
                'businessType': customer.business_type,
                'tier': customer.tier_id.name if customer.tier_id else None,
                'state': customer.state,
                'primaryContact': {
                    'name': customer.primary_contact_name,
                    'phone': customer.primary_contact_phone,
                    'email': customer.primary_contact_email,
                },
                'totalOrders': customer.total_orders,
                'totalSpent': customer.total_spent,
            }
        }

    @http.route('/api/v1/b2b/customer/<int:customer_id>/documents', type='json', auth='user', methods=['POST'])
    def upload_document(self, customer_id, **kwargs):
        """Upload a document for a customer"""
        try:
            data = request.jsonrequest
            customer = request.env['b2b.customer'].browse(customer_id)

            if not customer.exists():
                return {'success': False, 'error': 'Customer not found'}

            document = request.env['b2b.customer.document'].create({
                'name': data.get('name', 'Document'),
                'customer_id': customer_id,
                'document_type': data['documentType'],
                'file': data['file'],
                'file_name': data.get('fileName'),
                'issue_date': data.get('issueDate'),
                'expiry_date': data.get('expiryDate'),
            })

            return {
                'success': True,
                'document_id': document.id,
                'message': 'Document uploaded successfully',
            }

        except Exception as e:
            _logger.exception('Document upload failed')
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/b2b/kyc/status/<int:customer_id>', type='json', auth='user', methods=['GET'])
    def get_kyc_status(self, customer_id):
        """Get KYC verification status"""
        customer = request.env['b2b.customer'].browse(customer_id)
        if not customer.exists():
            return {'success': False, 'error': 'Customer not found'}

        kyc_service = request.env['b2b.kyc.service']
        status = kyc_service.check_kyc_status(customer_id)

        return {
            'success': True,
            'data': status,
        }
```

#### Dev 3 (Frontend Lead) - 8 hours

**Task 3.1: Build Registration Form Steps (8h)**

```xml
<!-- odoo/addons/smart_dairy_b2b/static/src/xml/b2b_registration_form.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<templates xml:space="preserve">
    <t t-name="smart_dairy_b2b.B2BRegistrationForm">
        <div class="b2b-registration-container">
            <!-- Progress Steps -->
            <div class="registration-progress mb-4">
                <div class="d-flex justify-content-between">
                    <t t-foreach="[1,2,3,4]" t-as="stepNum" t-key="stepNum">
                        <div t-attf-class="step-indicator #{state.step >= stepNum ? 'active' : ''} #{state.step > stepNum ? 'completed' : ''}">
                            <div class="step-number">
                                <t t-if="state.step > stepNum">
                                    <i class="fa fa-check"/>
                                </t>
                                <t t-else="">
                                    <t t-esc="stepNum"/>
                                </t>
                            </div>
                            <div class="step-label">
                                <t t-if="stepNum === 1">Business Info</t>
                                <t t-if="stepNum === 2">Contact</t>
                                <t t-if="stepNum === 3">Address</t>
                                <t t-if="stepNum === 4">Documents</t>
                            </div>
                        </div>
                    </t>
                </div>
            </div>

            <!-- Step 1: Business Information -->
            <div t-if="state.step === 1" class="registration-step">
                <h3>Business Information</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Business Name *</label>
                        <input type="text"
                               t-attf-class="form-control #{state.errors.businessName ? 'is-invalid' : ''}"
                               t-att-value="state.formData.businessName"
                               t-on-input="(e) => this.updateField('businessName', e)"/>
                        <div t-if="state.errors.businessName" class="invalid-feedback">
                            <t t-esc="state.errors.businessName"/>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Business Type *</label>
                        <select class="form-select"
                                t-on-change="(e) => this.updateField('businessType', e)">
                            <t t-foreach="state.businessTypes" t-as="bt" t-key="bt.value">
                                <option t-att-value="bt.value"
                                        t-att-selected="state.formData.businessType === bt.value">
                                    <t t-esc="bt.label"/>
                                </option>
                            </t>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trade License Number *</label>
                        <input type="text"
                               t-attf-class="form-control #{state.errors.tradeLicense ? 'is-invalid' : ''}"
                               t-att-value="state.formData.tradeLicense"
                               t-on-input="(e) => this.updateField('tradeLicense', e)"/>
                        <div t-if="state.errors.tradeLicense" class="invalid-feedback">
                            <t t-esc="state.errors.tradeLicense"/>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trade License Expiry</label>
                        <input type="date" class="form-control"
                               t-att-value="state.formData.tradeLicenseExpiry"
                               t-on-input="(e) => this.updateField('tradeLicenseExpiry', e)"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">TIN Number</label>
                        <input type="text" class="form-control"
                               t-att-value="state.formData.tinNumber"
                               t-on-input="(e) => this.updateField('tinNumber', e)"
                               placeholder="12-digit TIN"/>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">BIN Number</label>
                        <input type="text" class="form-control"
                               t-att-value="state.formData.binNumber"
                               t-on-input="(e) => this.updateField('binNumber', e)"/>
                    </div>
                </div>
            </div>

            <!-- Step 2: Contact Information -->
            <div t-if="state.step === 2" class="registration-step">
                <h3>Contact Information</h3>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Primary Contact Name *</label>
                        <input type="text"
                               t-attf-class="form-control #{state.errors.primaryContactName ? 'is-invalid' : ''}"
                               t-att-value="state.formData.primaryContactName"
                               t-on-input="(e) => this.updateField('primaryContactName', e)"/>
                        <div t-if="state.errors.primaryContactName" class="invalid-feedback">
                            <t t-esc="state.errors.primaryContactName"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone Number *</label>
                        <input type="tel"
                               t-attf-class="form-control #{state.errors.primaryContactPhone ? 'is-invalid' : ''}"
                               t-att-value="state.formData.primaryContactPhone"
                               t-on-input="(e) => this.updateField('primaryContactPhone', e)"
                               placeholder="+880 1XXX-XXXXXX"/>
                        <div t-if="state.errors.primaryContactPhone" class="invalid-feedback">
                            <t t-esc="state.errors.primaryContactPhone"/>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Address *</label>
                        <input type="email"
                               t-attf-class="form-control #{state.errors.primaryContactEmail ? 'is-invalid' : ''}"
                               t-att-value="state.formData.primaryContactEmail"
                               t-on-input="(e) => this.updateField('primaryContactEmail', e)"/>
                        <div t-if="state.errors.primaryContactEmail" class="invalid-feedback">
                            <t t-esc="state.errors.primaryContactEmail"/>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Address -->
            <div t-if="state.step === 3" class="registration-step">
                <h3>Business Address</h3>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Street Address *</label>
                        <input type="text"
                               t-attf-class="form-control #{state.errors.street ? 'is-invalid' : ''}"
                               t-att-value="state.formData.street"
                               t-on-input="(e) => this.updateField('street', e)"/>
                        <div t-if="state.errors.street" class="invalid-feedback">
                            <t t-esc="state.errors.street"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">City *</label>
                        <input type="text"
                               t-attf-class="form-control #{state.errors.city ? 'is-invalid' : ''}"
                               t-att-value="state.formData.city"
                               t-on-input="(e) => this.updateField('city', e)"/>
                        <div t-if="state.errors.city" class="invalid-feedback">
                            <t t-esc="state.errors.city"/>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Division *</label>
                        <select class="form-select"
                                t-on-change="(e) => this.updateField('division', e)">
                            <t t-foreach="state.divisions" t-as="div" t-key="div.value">
                                <option t-att-value="div.value"
                                        t-att-selected="state.formData.division === div.value">
                                    <t t-esc="div.label"/>
                                </option>
                            </t>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Step 4: Documents -->
            <div t-if="state.step === 4" class="registration-step">
                <h3>Upload Documents</h3>
                <p class="text-muted">Please upload the following required documents:</p>

                <div class="document-upload-section mb-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Trade License *</h5>
                            <input type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png"
                                   t-on-change="(e) => this.handleFileUpload(e, 'trade_license')"/>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>TIN Certificate</h5>
                            <input type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png"
                                   t-on-change="(e) => this.handleFileUpload(e, 'tin_certificate')"/>
                        </div>
                    </div>
                </div>

                <!-- Uploaded Documents List -->
                <t t-if="state.formData.documents.length > 0">
                    <h5>Uploaded Documents:</h5>
                    <ul class="list-group mb-3">
                        <t t-foreach="state.formData.documents" t-as="doc" t-key="doc_index">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-file-o me-2"/><t t-esc="doc.name"/></span>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        t-on-click="() => this.removeDocument(doc_index)">
                                    <i class="fa fa-trash"/>
                                </button>
                            </li>
                        </t>
                    </ul>
                </t>
            </div>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <button t-if="state.step > 1" type="button" class="btn btn-secondary"
                        t-on-click="prevStep">
                    <i class="fa fa-arrow-left me-2"/>Previous
                </button>
                <div t-else=""/>

                <button t-if="state.step &lt; state.totalSteps" type="button" class="btn btn-primary"
                        t-on-click="nextStep">
                    Next<i class="fa fa-arrow-right ms-2"/>
                </button>
                <button t-else="" type="button" class="btn btn-success"
                        t-att-disabled="state.isLoading"
                        t-on-click="submitRegistration">
                    <t t-if="state.isLoading">
                        <i class="fa fa-spinner fa-spin me-2"/>Submitting...
                    </t>
                    <t t-else="">
                        <i class="fa fa-check me-2"/>Submit Registration
                    </t>
                </button>
            </div>
        </div>
    </t>
</templates>
```

**End of Day 552 Deliverables:**
- [ ] Approval Workflow Engine complete
- [ ] Approval Request and Line models
- [ ] KYC Verification Service
- [ ] Email notification templates (4 templates)
- [ ] REST API endpoints for registration
- [ ] Multi-step registration form UI

---

### Day 553-560 Summary

Due to document length constraints, here's a summary of the remaining days:

#### Day 553: Credit Assignment & Dashboard Foundation
- **Dev 1**: Credit assignment logic, tier upgrade automation
- **Dev 2**: Dashboard API endpoints, statistics aggregation
- **Dev 3**: Customer Dashboard OWL component structure

#### Day 554: Partner Integration & Portal Users
- **Dev 1**: Partner integration with Odoo res.partner
- **Dev 2**: Email/SMS notification service integration
- **Dev 3**: Portal User Management UI

#### Day 555: Tier Benefits Engine
- **Dev 1**: Tier benefits calculation engine
- **Dev 2**: Activity logging and audit trail
- **Dev 3**: Tier badge and benefits display components

#### Day 556: Bulk Operations & API
- **Dev 1**: Bulk import/export functionality
- **Dev 2**: Complete REST API documentation
- **Dev 3**: Document upload widget with drag-drop

#### Day 557: Background Jobs & Alerts
- **Dev 1**: Cron jobs for expiry alerts, tier review
- **Dev 2**: Integration tests for approval workflow
- **Dev 3**: Mobile-responsive view optimization

#### Day 558: Reporting & Queries
- **Dev 1**: Customer reporting queries, exports
- **Dev 2**: Unit tests for all models
- **Dev 3**: Approval status display components

#### Day 559: Performance & Polish
- **Dev 1**: Query optimization, caching
- **Dev 2**: E2E tests for registration flow
- **Dev 3**: UI polish, accessibility (WCAG 2.1)

#### Day 560: Documentation & Handoff
- **Dev 1**: Technical documentation, code comments
- **Dev 2**: UAT test cases, deployment guide
- **Dev 3**: Component documentation, style guide

---

## 7. Technical Specifications

### 7.1 Database Schema

```sql
-- B2B Customer Table
CREATE TABLE b2b_customer (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(20) UNIQUE,
    partner_id INTEGER REFERENCES res_partner(id),
    trade_license VARCHAR(100) UNIQUE,
    trade_license_expiry DATE,
    tin_number VARCHAR(20) UNIQUE,
    bin_number VARCHAR(20),
    business_type VARCHAR(50) NOT NULL,
    primary_contact_name VARCHAR(255) NOT NULL,
    primary_contact_phone VARCHAR(20) NOT NULL,
    primary_contact_email VARCHAR(255) NOT NULL,
    tier_id INTEGER REFERENCES b2b_customer_tier(id),
    tier_assigned_date DATE,
    state VARCHAR(20) DEFAULT 'draft',
    approved_by INTEGER REFERENCES res_users(id),
    approved_date TIMESTAMP,
    rejection_reason TEXT,
    registration_date TIMESTAMP DEFAULT NOW(),
    last_order_date TIMESTAMP,
    create_date TIMESTAMP DEFAULT NOW(),
    write_date TIMESTAMP DEFAULT NOW()
);

-- Indexes
CREATE INDEX idx_b2b_customer_state ON b2b_customer(state);
CREATE INDEX idx_b2b_customer_tier ON b2b_customer(tier_id);
CREATE INDEX idx_b2b_customer_partner ON b2b_customer(partner_id);
```

### 7.2 API Endpoints

| Endpoint | Method | Description | Auth |
|----------|--------|-------------|------|
| `/api/v1/b2b/register` | POST | Register new B2B customer | Public |
| `/api/v1/b2b/customer/{id}` | GET | Get customer details | User |
| `/api/v1/b2b/customer/{id}` | PUT | Update customer | User |
| `/api/v1/b2b/customer/{id}/approve` | POST | Approve customer | Admin |
| `/api/v1/b2b/customer/{id}/reject` | POST | Reject customer | Admin |
| `/api/v1/b2b/customer/{id}/documents` | GET | List documents | User |
| `/api/v1/b2b/customer/{id}/documents` | POST | Upload document | User |
| `/api/v1/b2b/kyc/status/{id}` | GET | Get KYC status | User |
| `/api/v1/b2b/tiers` | GET | List all tiers | Public |

---

## 8. Testing Requirements

### 8.1 Unit Tests

| Test Case | Description | Priority |
|-----------|-------------|----------|
| test_customer_create | Customer creation with validation | High |
| test_customer_code_sequence | Auto-generated customer code | High |
| test_tier_assignment | Default tier assignment on approval | High |
| test_kyc_workflow | KYC document verification flow | High |
| test_approval_workflow | Multi-stage approval process | High |
| test_email_validation | Email format validation | Medium |
| test_phone_validation | Bangladesh phone validation | Medium |

### 8.2 Integration Tests

| Test Case | Description | Priority |
|-----------|-------------|----------|
| test_registration_flow | Complete registration process | Critical |
| test_approval_flow | From registration to approval | Critical |
| test_document_upload | Document upload and verification | High |
| test_notification_sending | Email/SMS notification delivery | High |

---

## 9. Risk Mitigation

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| KYC verification delays | Medium | Medium | Parallel review process |
| Data validation gaps | High | Low | Comprehensive validation rules |
| Notification failures | Low | Low | Queue-based delivery with retry |
| Performance issues | Medium | Low | Redis caching, query optimization |

---

## 10. Sign-off Checklist

- [ ] B2B Customer Model complete with all fields
- [ ] Customer Tier system with 4 tiers configured
- [ ] Portal User multi-account support working
- [ ] KYC document upload and verification functional
- [ ] Approval workflow with multi-stage support
- [ ] Email/SMS notifications sending correctly
- [ ] REST API endpoints tested and documented
- [ ] OWL registration form complete and responsive
- [ ] Customer dashboard functional
- [ ] Unit tests passing (>80% coverage)
- [ ] Integration tests passing
- [ ] Performance benchmarks met (<500ms API response)
- [ ] Security review passed
- [ ] Documentation complete

---

**Document End**

*Milestone 81: B2B Customer Portal*
*Days 551-560 | Phase 9: Commerce - B2B Portal Foundation*
