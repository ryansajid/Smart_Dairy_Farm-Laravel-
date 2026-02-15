# Milestone 16: B2B Portal Foundation

## Smart Dairy Digital Portal + ERP Implementation
### Phase 2, Part B: Commerce Foundation
### Days 151-160 (10-Day Sprint)

---

## Document Control

| Attribute | Value |
|-----------|-------|
| **Document ID** | SD-P2-MS16-B2B-v1.0 |
| **Version** | 1.0 |
| **Last Updated** | 2025-01-15 |
| **Status** | Draft |
| **Owner** | Dev 1 (Backend Lead) |
| **Reviewers** | Technical Architect, Project Manager |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Implement the B2B Portal foundation including partner registration, verification workflow, tiered pricing engine, credit management, order approval workflows, and standing order (recurring) configuration for Smart Dairy's B2B customers (Distributors, Retailers, HORECA, Institutional).

### 1.2 Objectives

| # | Objective | Priority | BRD Ref |
|---|-----------|----------|---------|
| 1 | Implement B2B partner registration portal | Critical | FR-B2B-001 |
| 2 | Configure partner verification workflow | Critical | FR-B2B-002 |
| 3 | Build tiered pricing engine | Critical | FR-B2B-003 |
| 4 | Implement credit limit management | High | FR-B2B-004 |
| 5 | Configure payment terms by customer type | High | FR-B2B-005 |
| 6 | Build order approval workflow | High | FR-B2B-006 |
| 7 | Implement standing orders (recurring) | Medium | FR-B2B-007 |
| 8 | Create B2B-specific product catalog | High | FR-B2B-008 |
| 9 | Build B2B dashboard and analytics | Medium | FR-B2B-009 |
| 10 | Integrate with CRM module | High | FR-B2B-010 |

### 1.3 Key Deliverables

| Deliverable | Owner | Day |
|-------------|-------|-----|
| B2B registration portal backend | Dev 1 | 151-152 |
| Registration frontend (OWL) | Dev 3 | 152-153 |
| Partner verification workflow | Dev 1 | 153-154 |
| Tiered pricing engine | Dev 1 | 154-155 |
| Credit management system | Dev 2 | 155-156 |
| Order approval workflow | Dev 1 | 156-157 |
| Standing orders module | Dev 2 | 157-158 |
| B2B product catalog | Dev 3 | 158-159 |
| B2B dashboard | Dev 3 | 159 |
| Integration & testing | All | 160 |

### 1.4 Prerequisites

- [x] Milestone 15: CRM Advanced Configuration completed
- [x] Customer segmentation operational
- [x] B2B customer types defined
- [x] Territory management configured
- [x] Base e-commerce module installed

### 1.5 Success Criteria

| Criteria | Target | Measurement |
|----------|--------|-------------|
| Registration to approval time | <48 hours | Process audit |
| Pricing accuracy | 100% | Order validation |
| Credit check performance | <500ms | API response time |
| Standing order reliability | 99.9% | Execution logs |
| B2B portal load time | <3 seconds | Performance test |

---

## 2. Requirement Traceability Matrix

### 2.1 BRD Requirements

| BRD Req ID | Description | Implementation | Day |
|------------|-------------|----------------|-----|
| FR-B2B-001 | Partner registration | `smart_b2b_portal.registration` | 151 |
| FR-B2B-002 | Document verification | `smart_b2b_portal.verification` | 153 |
| FR-B2B-003 | Tiered pricing | `smart_b2b_portal.pricing` | 154 |
| FR-B2B-004 | Credit management | `smart_b2b_portal.credit` | 155 |
| FR-B2B-005 | Payment terms | Standard Odoo + config | 155 |
| FR-B2B-006 | Order approval | `smart_b2b_portal.approval` | 156 |
| FR-B2B-007 | Standing orders | `smart_b2b_portal.standing_order` | 157 |

---

## 3. Day-by-Day Breakdown

---

### Day 151-152: B2B Registration System

#### Dev 1 Tasks (16h) - Registration Backend

**Task 1.1: Registration Request Model (8h)**

```python
# smart_b2b_portal/models/b2b_registration.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
import re
import uuid

class B2BRegistrationRequest(models.Model):
    _name = 'smart.b2b.registration'
    _description = 'B2B Partner Registration Request'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'create_date desc'

    name = fields.Char(
        string='Reference',
        readonly=True,
        copy=False,
        default='New'
    )

    # Business Information
    company_name = fields.Char(
        string='Company/Business Name',
        required=True,
        tracking=True
    )
    company_name_bn = fields.Char(string='Company Name (Bengali)')
    business_type_id = fields.Many2one(
        'smart.b2b.customer.type',
        string='Business Type',
        required=True,
        tracking=True
    )

    # Contact Information
    contact_name = fields.Char(string='Contact Person', required=True)
    contact_designation = fields.Char(string='Designation')
    email = fields.Char(string='Email', required=True)
    phone = fields.Char(string='Phone', required=True)
    mobile = fields.Char(string='Mobile')
    website = fields.Char(string='Website')

    # Address
    street = fields.Char(string='Street Address')
    street2 = fields.Char(string='Street Address 2')
    city = fields.Char(string='City')
    district_id = fields.Many2one(
        'smart.sales.territory',
        string='District',
        domain=[('territory_type', '=', 'district')]
    )
    division_id = fields.Many2one(
        'smart.sales.territory',
        string='Division',
        domain=[('territory_type', '=', 'division')]
    )
    zip = fields.Char(string='ZIP Code')

    # Business Documents
    trade_license_no = fields.Char(string='Trade License No.', required=True)
    trade_license_file = fields.Binary(string='Trade License (Scan)')
    trade_license_filename = fields.Char(string='Trade License Filename')
    trade_license_expiry = fields.Date(string='Trade License Expiry')

    tin_no = fields.Char(string='TIN Number')
    tin_file = fields.Binary(string='TIN Certificate (Scan)')
    tin_filename = fields.Char(string='TIN Filename')

    vat_registration_no = fields.Char(string='VAT Registration No.')
    vat_file = fields.Binary(string='VAT Certificate (Scan)')
    vat_filename = fields.Char(string='VAT Filename')

    bin_no = fields.Char(string='BIN Number')

    # Bank Information
    bank_name = fields.Char(string='Bank Name')
    bank_branch = fields.Char(string='Bank Branch')
    bank_account_no = fields.Char(string='Bank Account No.')
    bank_routing_no = fields.Char(string='Bank Routing No.')

    # Business Profile
    years_in_business = fields.Integer(string='Years in Business')
    annual_turnover = fields.Selection([
        ('below_10l', 'Below 10 Lakh'),
        ('10l_50l', '10-50 Lakh'),
        ('50l_1cr', '50 Lakh - 1 Crore'),
        ('1cr_5cr', '1-5 Crore'),
        ('above_5cr', 'Above 5 Crore'),
    ], string='Annual Turnover (BDT)')
    employee_count = fields.Selection([
        ('1_5', '1-5'),
        ('6_20', '6-20'),
        ('21_50', '21-50'),
        ('51_100', '51-100'),
        ('above_100', 'Above 100'),
    ], string='Employee Count')

    # Expected Business
    expected_monthly_order = fields.Float(
        string='Expected Monthly Order (BDT)'
    )
    primary_products = fields.Many2many(
        'product.category',
        string='Primary Product Interest'
    )
    delivery_frequency = fields.Selection([
        ('daily', 'Daily'),
        ('twice_week', 'Twice a Week'),
        ('weekly', 'Weekly'),
        ('biweekly', 'Bi-weekly'),
        ('monthly', 'Monthly'),
    ], string='Expected Delivery Frequency')

    # Workflow
    state = fields.Selection([
        ('draft', 'Draft'),
        ('submitted', 'Submitted'),
        ('documents_review', 'Documents Under Review'),
        ('field_verification', 'Field Verification'),
        ('credit_review', 'Credit Review'),
        ('approved', 'Approved'),
        ('rejected', 'Rejected'),
    ], string='Status', default='draft', tracking=True)

    # Assignment
    assigned_to_id = fields.Many2one(
        'res.users',
        string='Assigned To',
        tracking=True
    )
    sales_team_id = fields.Many2one(
        'crm.team',
        string='Sales Team'
    )

    # Verification
    verification_notes = fields.Text(string='Verification Notes')
    rejection_reason = fields.Text(string='Rejection Reason')
    field_visit_date = fields.Date(string='Field Visit Date')
    field_visit_notes = fields.Text(string='Field Visit Notes')

    # Credit Assessment
    requested_credit_limit = fields.Float(string='Requested Credit Limit')
    approved_credit_limit = fields.Float(string='Approved Credit Limit')
    payment_term_id = fields.Many2one(
        'account.payment.term',
        string='Approved Payment Terms'
    )

    # Result
    partner_id = fields.Many2one(
        'res.partner',
        string='Created Partner',
        readonly=True
    )
    portal_user_id = fields.Many2one(
        'res.users',
        string='Portal User',
        readonly=True
    )
    portal_access_token = fields.Char(
        string='Portal Access Token',
        readonly=True
    )

    _sql_constraints = [
        ('email_unique', 'UNIQUE(email)',
         'A registration with this email already exists!'),
        ('trade_license_unique', 'UNIQUE(trade_license_no)',
         'A registration with this trade license already exists!'),
    ]

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            if vals.get('name', 'New') == 'New':
                vals['name'] = self.env['ir.sequence'].next_by_code(
                    'smart.b2b.registration'
                ) or 'New'
        return super().create(vals_list)

    @api.constrains('email')
    def _check_email(self):
        email_pattern = r'^[\w\.-]+@[\w\.-]+\.\w+$'
        for record in self:
            if record.email and not re.match(email_pattern, record.email):
                raise ValidationError("Please enter a valid email address.")

    @api.constrains('phone', 'mobile')
    def _check_phone(self):
        bd_phone_pattern = r'^(\+?880|0)?1[3-9]\d{8}$'
        for record in self:
            for field in ['phone', 'mobile']:
                value = getattr(record, field)
                if value:
                    cleaned = re.sub(r'[\s\-]', '', value)
                    if not re.match(bd_phone_pattern, cleaned):
                        raise ValidationError(
                            f"Please enter a valid Bangladesh phone number for {field}."
                        )

    @api.onchange('business_type_id')
    def _onchange_business_type(self):
        if self.business_type_id:
            btype = self.business_type_id
            self.requested_credit_limit = btype.default_credit_limit

    def action_submit(self):
        """Submit registration for review"""
        self.ensure_one()

        # Validate required documents based on business type
        btype = self.business_type_id
        if btype.requires_trade_license and not self.trade_license_file:
            raise ValidationError("Trade License document is required.")
        if btype.requires_tin and not self.tin_file:
            raise ValidationError("TIN Certificate is required.")
        if btype.requires_vat_certificate and not self.vat_file:
            raise ValidationError("VAT Certificate is required.")

        self.write({
            'state': 'submitted',
            'portal_access_token': str(uuid.uuid4()),
        })

        # Create activity for review
        self._create_review_activity()

        # Send confirmation email
        self._send_submission_confirmation()

        return True

    def action_start_document_review(self):
        """Start document review process"""
        self.write({'state': 'documents_review'})

    def action_start_field_verification(self):
        """Schedule field verification"""
        self.write({'state': 'field_verification'})

    def action_start_credit_review(self):
        """Start credit review process"""
        self.write({'state': 'credit_review'})

    def action_approve(self):
        """Approve registration and create partner"""
        self.ensure_one()

        if not self.approved_credit_limit:
            raise ValidationError("Please set approved credit limit.")
        if not self.payment_term_id:
            raise ValidationError("Please set payment terms.")

        # Create partner
        partner = self._create_partner()

        # Create portal user
        user = self._create_portal_user(partner)

        self.write({
            'state': 'approved',
            'partner_id': partner.id,
            'portal_user_id': user.id,
        })

        # Send welcome email with credentials
        self._send_approval_email()

        return {
            'type': 'ir.actions.act_window',
            'res_model': 'res.partner',
            'res_id': partner.id,
            'view_mode': 'form',
            'target': 'current',
        }

    def action_reject(self):
        """Reject registration"""
        return {
            'type': 'ir.actions.act_window',
            'res_model': 'smart.b2b.rejection.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_registration_id': self.id},
        }

    def _create_partner(self):
        """Create partner from registration data"""
        self.ensure_one()

        partner_vals = {
            'name': self.company_name,
            'company_type': 'company',
            'is_company': True,
            'customer_rank': 1,
            'customer_segment': 'b2b',
            'b2b_customer_type_id': self.business_type_id.id,
            'email': self.email,
            'phone': self.phone,
            'mobile': self.mobile,
            'website': self.website,
            'street': self.street,
            'street2': self.street2,
            'city': self.city,
            'zip': self.zip,
            'territory_id': self.district_id.id,
            'trade_license_no': self.trade_license_no,
            'trade_license_expiry': self.trade_license_expiry,
            'tin_no': self.tin_no,
            'vat_registration_no': self.vat_registration_no,
            'credit_limit': self.approved_credit_limit,
            'property_payment_term_id': self.payment_term_id.id,
            'verification_status': 'verified',
            'verified_date': fields.Date.today(),
            'verified_by_id': self.env.uid,
        }

        # Set pricelist based on business type
        if self.business_type_id.default_pricelist_id:
            partner_vals['property_product_pricelist'] = \
                self.business_type_id.default_pricelist_id.id

        partner = self.env['res.partner'].create(partner_vals)

        # Create contact person
        self.env['res.partner'].create({
            'name': self.contact_name,
            'parent_id': partner.id,
            'type': 'contact',
            'function': self.contact_designation,
            'email': self.email,
            'phone': self.phone,
            'mobile': self.mobile,
        })

        return partner

    def _create_portal_user(self, partner):
        """Create portal user for the partner"""
        user_vals = {
            'name': self.contact_name,
            'login': self.email,
            'partner_id': partner.id,
            'groups_id': [(6, 0, [
                self.env.ref('base.group_portal').id
            ])],
        }

        user = self.env['res.users'].with_context(
            no_reset_password=True
        ).create(user_vals)

        # Trigger password reset email
        user.action_reset_password()

        return user

    def _create_review_activity(self):
        """Create activity for registration review"""
        activity_type = self.env.ref('mail.mail_activity_data_todo')

        self.activity_schedule(
            activity_type_id=activity_type.id,
            summary=f'Review B2B Registration: {self.company_name}',
            note='Please review the submitted documents and verify business information.',
            user_id=self.assigned_to_id.id or self.env.uid,
            date_deadline=fields.Date.today(),
        )

    def _send_submission_confirmation(self):
        """Send submission confirmation email"""
        template = self.env.ref(
            'smart_b2b_portal.email_template_registration_submitted',
            raise_if_not_found=False
        )
        if template:
            template.send_mail(self.id, force_send=True)

    def _send_approval_email(self):
        """Send approval email with portal access details"""
        template = self.env.ref(
            'smart_b2b_portal.email_template_registration_approved',
            raise_if_not_found=False
        )
        if template:
            template.send_mail(self.id, force_send=True)
```

**Task 1.2: Registration Sequence & Data (4h)**

```xml
<!-- smart_b2b_portal/data/registration_data.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <data noupdate="1">
        <!-- Registration Sequence -->
        <record id="seq_b2b_registration" model="ir.sequence">
            <field name="name">B2B Registration Sequence</field>
            <field name="code">smart.b2b.registration</field>
            <field name="prefix">B2B-REG-%(year)s-</field>
            <field name="padding">5</field>
        </record>

        <!-- Email Templates -->
        <record id="email_template_registration_submitted" model="mail.template">
            <field name="name">B2B Registration Submitted</field>
            <field name="model_id" ref="model_smart_b2b_registration"/>
            <field name="email_from">{{object.company_id.email or 'noreply@smartdairy.com'}}</field>
            <field name="email_to">{{object.email}}</field>
            <field name="subject">Smart Dairy B2B Registration Received - {{object.name}}</field>
            <field name="body_html" type="html">
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <div style="background: #2c5530; color: white; padding: 20px; text-align: center;">
        <h1 style="margin: 0;">Smart Dairy</h1>
        <p style="margin: 5px 0 0 0;">B2B Partner Portal</p>
    </div>
    <div style="padding: 30px; background: #f9f9f9;">
        <h2>Registration Received</h2>
        <p>Dear {{object.contact_name}},</p>
        <p>Thank you for your interest in becoming a Smart Dairy B2B partner. We have received your registration request.</p>
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Reference:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{object.name}}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Company:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{object.company_name}}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Business Type:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{object.business_type_id.name}}</td>
            </tr>
        </table>
        <p>Our team will review your application and documents within 2-3 business days. You may be contacted for additional information or a field verification visit.</p>
        <p>Track your application status:</p>
        <p style="text-align: center;">
            <a href="/b2b/registration/status/{{object.portal_access_token}}"
               style="background: #2c5530; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Check Status
            </a>
        </p>
    </div>
    <div style="background: #333; color: #999; padding: 15px; text-align: center; font-size: 12px;">
        <p>Smart Dairy Bangladesh Ltd.</p>
        <p>support@smartdairy.com | +880-2-123456</p>
    </div>
</div>
            </field>
        </record>

        <record id="email_template_registration_approved" model="mail.template">
            <field name="name">B2B Registration Approved</field>
            <field name="model_id" ref="model_smart_b2b_registration"/>
            <field name="email_from">{{object.company_id.email or 'noreply@smartdairy.com'}}</field>
            <field name="email_to">{{object.email}}</field>
            <field name="subject">Welcome to Smart Dairy B2B Portal - Registration Approved!</field>
            <field name="body_html" type="html">
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <div style="background: #2c5530; color: white; padding: 20px; text-align: center;">
        <h1 style="margin: 0;">Smart Dairy</h1>
        <p style="margin: 5px 0 0 0;">Welcome to Our B2B Family!</p>
    </div>
    <div style="padding: 30px; background: #f9f9f9;">
        <h2 style="color: #2c5530;">Congratulations!</h2>
        <p>Dear {{object.contact_name}},</p>
        <p>Your B2B partner registration has been <strong style="color: #2c5530;">APPROVED</strong>!</p>
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3 style="margin-top: 0;">Your Account Details</h3>
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 8px 0;"><strong>Partner Code:</strong></td>
                    <td>{{object.partner_id.ref or 'Will be assigned'}}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Business Type:</strong></td>
                    <td>{{object.business_type_id.name}}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Credit Limit:</strong></td>
                    <td>BDT {{'{:,.0f}'.format(object.approved_credit_limit)}}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Payment Terms:</strong></td>
                    <td>{{object.payment_term_id.name}}</td>
                </tr>
            </table>
        </div>
        <p>You will receive a separate email to set up your portal password.</p>
        <p style="text-align: center; margin: 30px 0;">
            <a href="/web/login"
               style="background: #2c5530; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; display: inline-block; font-size: 16px;">
                Access B2B Portal
            </a>
        </p>
        <p>For any questions, contact your dedicated account manager or our support team.</p>
    </div>
</div>
            </field>
        </record>
    </data>
</odoo>
```

**Task 1.3: Rejection Wizard (4h)**

```python
# smart_b2b_portal/wizard/rejection_wizard.py
from odoo import models, fields, api

class B2BRejectionWizard(models.TransientModel):
    _name = 'smart.b2b.rejection.wizard'
    _description = 'B2B Registration Rejection Wizard'

    registration_id = fields.Many2one(
        'smart.b2b.registration',
        string='Registration',
        required=True
    )

    rejection_reason = fields.Selection([
        ('invalid_documents', 'Invalid/Expired Documents'),
        ('failed_verification', 'Failed Field Verification'),
        ('credit_risk', 'Credit Risk - Not Approved'),
        ('duplicate', 'Duplicate Registration'),
        ('incomplete', 'Incomplete Information'),
        ('outside_coverage', 'Outside Service Area'),
        ('other', 'Other'),
    ], string='Rejection Reason', required=True)

    rejection_details = fields.Text(
        string='Detailed Reason',
        required=True
    )

    allow_reapply = fields.Boolean(
        string='Allow Re-application',
        default=True
    )

    reapply_after_days = fields.Integer(
        string='Can Re-apply After (Days)',
        default=30
    )

    def action_confirm_rejection(self):
        """Confirm rejection and update registration"""
        self.ensure_one()

        reason_text = dict(
            self._fields['rejection_reason'].selection
        ).get(self.rejection_reason)

        full_reason = f"{reason_text}\n\n{self.rejection_details}"

        self.registration_id.write({
            'state': 'rejected',
            'rejection_reason': full_reason,
        })

        # Send rejection email
        template = self.env.ref(
            'smart_b2b_portal.email_template_registration_rejected',
            raise_if_not_found=False
        )
        if template:
            template.send_mail(self.registration_id.id, force_send=True)

        return {'type': 'ir.actions.act_window_close'}
```

#### Dev 3 Tasks (16h) - Registration Frontend

**Task 3.1: Public Registration Controller (8h)**

```python
# smart_b2b_portal/controllers/registration.py
from odoo import http
from odoo.http import request
import json

class B2BRegistrationController(http.Controller):

    @http.route('/b2b/register', type='http', auth='public', website=True)
    def registration_form(self, **kwargs):
        """Display B2B registration form"""
        business_types = request.env['smart.b2b.customer.type'].sudo().search([
            ('active', '=', True)
        ])
        divisions = request.env['smart.sales.territory'].sudo().search([
            ('territory_type', '=', 'division')
        ])
        product_categories = request.env['product.category'].sudo().search([
            ('parent_id', '=', False)
        ])

        return request.render('smart_b2b_portal.registration_form', {
            'business_types': business_types,
            'divisions': divisions,
            'product_categories': product_categories,
        })

    @http.route('/b2b/register/submit', type='http', auth='public',
                website=True, methods=['POST'], csrf=True)
    def submit_registration(self, **post):
        """Process registration submission"""
        try:
            # Validate required fields
            required = ['company_name', 'business_type_id', 'contact_name',
                       'email', 'phone', 'trade_license_no']
            for field in required:
                if not post.get(field):
                    return request.redirect(
                        f'/b2b/register?error=missing_{field}'
                    )

            # Check for existing registration
            existing = request.env['smart.b2b.registration'].sudo().search([
                '|',
                ('email', '=', post.get('email')),
                ('trade_license_no', '=', post.get('trade_license_no')),
                ('state', 'not in', ['rejected']),
            ], limit=1)

            if existing:
                return request.redirect(
                    '/b2b/register?error=already_exists'
                )

            # Prepare values
            vals = {
                'company_name': post.get('company_name'),
                'company_name_bn': post.get('company_name_bn'),
                'business_type_id': int(post.get('business_type_id')),
                'contact_name': post.get('contact_name'),
                'contact_designation': post.get('contact_designation'),
                'email': post.get('email'),
                'phone': post.get('phone'),
                'mobile': post.get('mobile'),
                'website': post.get('website'),
                'street': post.get('street'),
                'street2': post.get('street2'),
                'city': post.get('city'),
                'zip': post.get('zip'),
                'trade_license_no': post.get('trade_license_no'),
                'trade_license_expiry': post.get('trade_license_expiry') or False,
                'tin_no': post.get('tin_no'),
                'vat_registration_no': post.get('vat_registration_no'),
                'bin_no': post.get('bin_no'),
                'bank_name': post.get('bank_name'),
                'bank_branch': post.get('bank_branch'),
                'bank_account_no': post.get('bank_account_no'),
                'years_in_business': int(post.get('years_in_business') or 0),
                'annual_turnover': post.get('annual_turnover'),
                'employee_count': post.get('employee_count'),
                'expected_monthly_order': float(
                    post.get('expected_monthly_order') or 0
                ),
                'delivery_frequency': post.get('delivery_frequency'),
            }

            # Handle territory
            if post.get('district_id'):
                vals['district_id'] = int(post.get('district_id'))
            if post.get('division_id'):
                vals['division_id'] = int(post.get('division_id'))

            # Handle file uploads
            for file_field in ['trade_license_file', 'tin_file', 'vat_file']:
                if file_field in request.httprequest.files:
                    file = request.httprequest.files[file_field]
                    if file.filename:
                        import base64
                        vals[file_field] = base64.b64encode(file.read())
                        vals[f'{file_field}name'] = file.filename

            # Handle product categories
            if post.getlist('primary_products'):
                vals['primary_products'] = [
                    (6, 0, [int(p) for p in post.getlist('primary_products')])
                ]

            # Create registration
            registration = request.env['smart.b2b.registration'].sudo().create(vals)
            registration.action_submit()

            return request.redirect(
                f'/b2b/register/success?ref={registration.name}'
            )

        except Exception as e:
            return request.redirect(f'/b2b/register?error={str(e)}')

    @http.route('/b2b/register/success', type='http', auth='public', website=True)
    def registration_success(self, ref=None, **kwargs):
        """Display registration success page"""
        registration = request.env['smart.b2b.registration'].sudo().search([
            ('name', '=', ref)
        ], limit=1)

        return request.render('smart_b2b_portal.registration_success', {
            'registration': registration,
        })

    @http.route('/b2b/registration/status/<token>', type='http',
                auth='public', website=True)
    def registration_status(self, token, **kwargs):
        """Display registration status page"""
        registration = request.env['smart.b2b.registration'].sudo().search([
            ('portal_access_token', '=', token)
        ], limit=1)

        if not registration:
            return request.redirect('/b2b/register?error=not_found')

        return request.render('smart_b2b_portal.registration_status', {
            'registration': registration,
        })

    @http.route('/b2b/districts/<int:division_id>', type='json', auth='public')
    def get_districts(self, division_id, **kwargs):
        """Get districts for a division (AJAX)"""
        districts = request.env['smart.sales.territory'].sudo().search([
            ('territory_type', '=', 'district'),
            ('parent_id', '=', division_id),
        ])
        return [{'id': d.id, 'name': d.name} for d in districts]
```

**Task 3.2: Registration Form Template (8h)**

```xml
<!-- smart_b2b_portal/views/templates/registration_form.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <template id="registration_form" name="B2B Registration Form">
        <t t-call="website.layout">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h3 class="mb-0">
                                    <i class="fa fa-building mr-2"/>
                                    B2B Partner Registration
                                </h3>
                                <p class="mb-0 opacity-75">
                                    Join Smart Dairy's B2B Network
                                </p>
                            </div>
                            <div class="card-body">
                                <form action="/b2b/register/submit" method="post"
                                      enctype="multipart/form-data"
                                      class="needs-validation" novalidate="">

                                    <input type="hidden" name="csrf_token"
                                           t-att-value="request.csrf_token()"/>

                                    <!-- Progress Steps -->
                                    <div class="registration-steps mb-4">
                                        <div class="step active" data-step="1">
                                            <span class="step-number">1</span>
                                            <span class="step-label">Business Info</span>
                                        </div>
                                        <div class="step" data-step="2">
                                            <span class="step-number">2</span>
                                            <span class="step-label">Documents</span>
                                        </div>
                                        <div class="step" data-step="3">
                                            <span class="step-number">3</span>
                                            <span class="step-label">Banking</span>
                                        </div>
                                        <div class="step" data-step="4">
                                            <span class="step-number">4</span>
                                            <span class="step-label">Submit</span>
                                        </div>
                                    </div>

                                    <!-- Step 1: Business Information -->
                                    <div class="step-content" id="step-1">
                                        <h5 class="border-bottom pb-2 mb-3">
                                            Business Information
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Company Name *
                                                </label>
                                                <input type="text" name="company_name"
                                                       class="form-control" required=""/>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Company Name (Bengali)
                                                </label>
                                                <input type="text" name="company_name_bn"
                                                       class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Business Type *
                                                </label>
                                                <select name="business_type_id"
                                                        class="form-select" required="">
                                                    <option value="">Select Type</option>
                                                    <t t-foreach="business_types" t-as="btype">
                                                        <option t-att-value="btype.id">
                                                            <t t-esc="btype.name"/>
                                                        </option>
                                                    </t>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Years in Business
                                                </label>
                                                <input type="number" name="years_in_business"
                                                       class="form-control" min="0"/>
                                            </div>
                                        </div>

                                        <h6 class="mt-4 mb-3">Contact Person</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Contact Name *
                                                </label>
                                                <input type="text" name="contact_name"
                                                       class="form-control" required=""/>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Designation
                                                </label>
                                                <input type="text" name="contact_designation"
                                                       class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Email *</label>
                                                <input type="email" name="email"
                                                       class="form-control" required=""/>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Phone *</label>
                                                <input type="tel" name="phone"
                                                       class="form-control" required=""
                                                       placeholder="+880-1XXX-XXXXXX"/>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Mobile</label>
                                                <input type="tel" name="mobile"
                                                       class="form-control"/>
                                            </div>
                                        </div>

                                        <h6 class="mt-4 mb-3">Business Address</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Division *</label>
                                                <select name="division_id" id="division_id"
                                                        class="form-select" required="">
                                                    <option value="">Select Division</option>
                                                    <t t-foreach="divisions" t-as="div">
                                                        <option t-att-value="div.id">
                                                            <t t-esc="div.name"/>
                                                        </option>
                                                    </t>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">District *</label>
                                                <select name="district_id" id="district_id"
                                                        class="form-select" required="">
                                                    <option value="">Select District</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label class="form-label">Street Address</label>
                                                <input type="text" name="street"
                                                       class="form-control"/>
                                            </div>
                                        </div>

                                        <div class="text-end mt-4">
                                            <button type="button" class="btn btn-primary btn-next">
                                                Next <i class="fa fa-arrow-right ml-2"/>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Step 2: Documents -->
                                    <div class="step-content d-none" id="step-2">
                                        <h5 class="border-bottom pb-2 mb-3">
                                            Business Documents
                                        </h5>
                                        <div class="alert alert-info">
                                            <i class="fa fa-info-circle mr-2"/>
                                            Please upload clear scans or photos of your documents.
                                            Accepted formats: PDF, JPG, PNG (Max 5MB each)
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Trade License No. *
                                                </label>
                                                <input type="text" name="trade_license_no"
                                                       class="form-control" required=""/>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Trade License Expiry
                                                </label>
                                                <input type="date" name="trade_license_expiry"
                                                       class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Trade License (Scan) *
                                            </label>
                                            <input type="file" name="trade_license_file"
                                                   class="form-control"
                                                   accept=".pdf,.jpg,.jpeg,.png"/>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">TIN Number</label>
                                                <input type="text" name="tin_no"
                                                       class="form-control"/>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">TIN Certificate</label>
                                                <input type="file" name="tin_file"
                                                       class="form-control"
                                                       accept=".pdf,.jpg,.jpeg,.png"/>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    VAT Registration No.
                                                </label>
                                                <input type="text" name="vat_registration_no"
                                                       class="form-control"/>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">VAT Certificate</label>
                                                <input type="file" name="vat_file"
                                                       class="form-control"
                                                       accept=".pdf,.jpg,.jpeg,.png"/>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between mt-4">
                                            <button type="button" class="btn btn-outline-secondary btn-prev">
                                                <i class="fa fa-arrow-left mr-2"/> Previous
                                            </button>
                                            <button type="button" class="btn btn-primary btn-next">
                                                Next <i class="fa fa-arrow-right ml-2"/>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Step 3: Banking -->
                                    <div class="step-content d-none" id="step-3">
                                        <h5 class="border-bottom pb-2 mb-3">
                                            Banking Information
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Bank Name</label>
                                                <input type="text" name="bank_name"
                                                       class="form-control"/>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Branch</label>
                                                <input type="text" name="bank_branch"
                                                       class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Account Number</label>
                                                <input type="text" name="bank_account_no"
                                                       class="form-control"/>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Routing Number</label>
                                                <input type="text" name="bank_routing_no"
                                                       class="form-control"/>
                                            </div>
                                        </div>

                                        <h6 class="mt-4 mb-3">Expected Business</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Expected Monthly Order (BDT)
                                                </label>
                                                <input type="number" name="expected_monthly_order"
                                                       class="form-control" min="0"/>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Delivery Frequency
                                                </label>
                                                <select name="delivery_frequency" class="form-select">
                                                    <option value="">Select</option>
                                                    <option value="daily">Daily</option>
                                                    <option value="twice_week">Twice a Week</option>
                                                    <option value="weekly">Weekly</option>
                                                    <option value="biweekly">Bi-weekly</option>
                                                    <option value="monthly">Monthly</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between mt-4">
                                            <button type="button" class="btn btn-outline-secondary btn-prev">
                                                <i class="fa fa-arrow-left mr-2"/> Previous
                                            </button>
                                            <button type="button" class="btn btn-primary btn-next">
                                                Next <i class="fa fa-arrow-right ml-2"/>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Step 4: Review & Submit -->
                                    <div class="step-content d-none" id="step-4">
                                        <h5 class="border-bottom pb-2 mb-3">
                                            Review &amp; Submit
                                        </h5>
                                        <div class="alert alert-warning">
                                            <i class="fa fa-exclamation-triangle mr-2"/>
                                            Please review your information before submitting.
                                        </div>

                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input"
                                                   id="terms" required=""/>
                                            <label class="form-check-label" for="terms">
                                                I confirm that all information provided is accurate
                                                and I agree to Smart Dairy's
                                                <a href="/terms" target="_blank">Terms &amp; Conditions</a>
                                            </label>
                                        </div>

                                        <div class="d-flex justify-content-between mt-4">
                                            <button type="button" class="btn btn-outline-secondary btn-prev">
                                                <i class="fa fa-arrow-left mr-2"/> Previous
                                            </button>
                                            <button type="submit" class="btn btn-success btn-lg">
                                                <i class="fa fa-paper-plane mr-2"/>
                                                Submit Registration
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Multi-step form handling
                document.addEventListener('DOMContentLoaded', function() {
                    const steps = document.querySelectorAll('.step');
                    const contents = document.querySelectorAll('.step-content');
                    let currentStep = 1;

                    function showStep(step) {
                        contents.forEach((c, i) => {
                            c.classList.toggle('d-none', i + 1 !== step);
                        });
                        steps.forEach((s, i) => {
                            s.classList.toggle('active', i + 1 <= step);
                        });
                        currentStep = step;
                    }

                    document.querySelectorAll('.btn-next').forEach(btn => {
                        btn.addEventListener('click', () => showStep(currentStep + 1));
                    });

                    document.querySelectorAll('.btn-prev').forEach(btn => {
                        btn.addEventListener('click', () => showStep(currentStep - 1));
                    });

                    // Division/District cascade
                    document.getElementById('division_id').addEventListener('change', function() {
                        const divisionId = this.value;
                        const districtSelect = document.getElementById('district_id');
                        districtSelect.innerHTML = '<option value="">Loading...</option>';

                        if (divisionId) {
                            fetch('/b2b/districts/' + divisionId, {
                                method: 'POST',
                                headers: {'Content-Type': 'application/json'},
                                body: JSON.stringify({jsonrpc: '2.0', method: 'call', params: {}})
                            })
                            .then(r => r.json())
                            .then(data => {
                                districtSelect.innerHTML = '<option value="">Select District</option>';
                                data.result.forEach(d => {
                                    districtSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`;
                                });
                            });
                        }
                    });
                });
            </script>
        </t>
    </template>
</odoo>
```

---

### Day 153-155: Tiered Pricing Engine

#### Dev 1 Tasks (24h) - Pricing Backend

**Task 1.1: Tiered Pricing Model (12h)**

```python
# smart_b2b_portal/models/tiered_pricing.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
import logging

_logger = logging.getLogger(__name__)

class B2BPricelistExtension(models.Model):
    _inherit = 'product.pricelist'

    is_b2b_pricelist = fields.Boolean(
        string='B2B Pricelist',
        default=False
    )

    b2b_customer_type_ids = fields.Many2many(
        'smart.b2b.customer.type',
        string='Applicable B2B Types'
    )

    # Tier-based discounts
    tier_discount_ids = fields.One2many(
        'smart.b2b.tier.discount',
        'pricelist_id',
        string='Tier Discounts'
    )

    # Volume-based pricing
    volume_discount_ids = fields.One2many(
        'smart.b2b.volume.discount',
        'pricelist_id',
        string='Volume Discounts'
    )


class B2BTierDiscount(models.Model):
    _name = 'smart.b2b.tier.discount'
    _description = 'B2B Tier-based Discount'
    _order = 'customer_tier_id'

    pricelist_id = fields.Many2one(
        'product.pricelist',
        string='Pricelist',
        required=True,
        ondelete='cascade'
    )

    customer_tier_id = fields.Many2one(
        'smart.customer.tier',
        string='Customer Tier',
        required=True
    )

    discount_percentage = fields.Float(
        string='Additional Discount %',
        digits=(5, 2)
    )

    product_category_id = fields.Many2one(
        'product.category',
        string='Product Category',
        help="Leave empty to apply to all products"
    )


class B2BVolumeDiscount(models.Model):
    _name = 'smart.b2b.volume.discount'
    _description = 'B2B Volume-based Discount'
    _order = 'min_qty'

    pricelist_id = fields.Many2one(
        'product.pricelist',
        string='Pricelist',
        required=True,
        ondelete='cascade'
    )

    min_qty = fields.Float(
        string='Min Quantity',
        required=True
    )
    max_qty = fields.Float(
        string='Max Quantity',
        help="Leave 0 for unlimited"
    )

    discount_percentage = fields.Float(
        string='Discount %',
        digits=(5, 2)
    )

    product_id = fields.Many2one(
        'product.product',
        string='Specific Product'
    )
    product_category_id = fields.Many2one(
        'product.category',
        string='Product Category'
    )


class B2BPricingEngine(models.Model):
    _name = 'smart.b2b.pricing.engine'
    _description = 'B2B Pricing Calculation Engine'

    @api.model
    def calculate_price(self, partner_id, product_id, qty=1.0, date=None):
        """
        Calculate B2B price for a product considering:
        1. Base pricelist price
        2. B2B customer type discount
        3. Customer tier discount
        4. Volume discount
        5. Promotional discounts
        """
        partner = self.env['res.partner'].browse(partner_id)
        product = self.env['product.product'].browse(product_id)

        if not partner.exists() or not product.exists():
            return {'error': 'Invalid partner or product'}

        result = {
            'base_price': product.lst_price,
            'discounts': [],
            'final_price': product.lst_price,
            'unit_price': product.lst_price,
            'total_discount_pct': 0,
        }

        # Get partner's pricelist
        pricelist = partner.property_product_pricelist
        if not pricelist:
            return result

        # 1. Base pricelist price
        base_price = pricelist._get_product_price(
            product, qty, partner, date=date
        )
        result['base_price'] = base_price
        result['final_price'] = base_price

        # 2. B2B Customer Type Discount
        if partner.b2b_customer_type_id:
            type_discount = partner.b2b_customer_type_id.discount_percentage
            if type_discount > 0:
                result['discounts'].append({
                    'name': f"B2B Type ({partner.b2b_customer_type_id.name})",
                    'percentage': type_discount,
                    'amount': base_price * (type_discount / 100),
                })

        # 3. Customer Tier Discount
        if partner.customer_tier_id and pricelist.tier_discount_ids:
            tier_discount = pricelist.tier_discount_ids.filtered(
                lambda d: d.customer_tier_id == partner.customer_tier_id
            )
            if tier_discount:
                td = tier_discount[0]
                # Check if category specific
                if td.product_category_id:
                    if product.categ_id == td.product_category_id or \
                       product.categ_id.parent_id == td.product_category_id:
                        result['discounts'].append({
                            'name': f"Tier ({partner.customer_tier_id.name})",
                            'percentage': td.discount_percentage,
                            'amount': base_price * (td.discount_percentage / 100),
                        })
                else:
                    result['discounts'].append({
                        'name': f"Tier ({partner.customer_tier_id.name})",
                        'percentage': td.discount_percentage,
                        'amount': base_price * (td.discount_percentage / 100),
                    })

        # 4. Volume Discount
        if pricelist.volume_discount_ids and qty > 1:
            volume_discounts = pricelist.volume_discount_ids.filtered(
                lambda d: d.min_qty <= qty and (d.max_qty == 0 or d.max_qty >= qty)
            )

            # Filter by product or category
            applicable_vd = None
            for vd in volume_discounts:
                if vd.product_id and vd.product_id == product:
                    applicable_vd = vd
                    break
                elif vd.product_category_id and (
                    product.categ_id == vd.product_category_id or
                    product.categ_id.parent_id == vd.product_category_id
                ):
                    applicable_vd = vd
                elif not vd.product_id and not vd.product_category_id:
                    applicable_vd = vd

            if applicable_vd:
                result['discounts'].append({
                    'name': f"Volume ({qty}+ units)",
                    'percentage': applicable_vd.discount_percentage,
                    'amount': base_price * (applicable_vd.discount_percentage / 100),
                })

        # Calculate final price
        total_discount_amount = sum(d['amount'] for d in result['discounts'])
        total_discount_pct = sum(d['percentage'] for d in result['discounts'])

        # Cap total discount at 40%
        if total_discount_pct > 40:
            _logger.warning(
                f"Discount capped at 40% for partner {partner.id}, "
                f"product {product.id}. Original: {total_discount_pct}%"
            )
            total_discount_pct = 40
            total_discount_amount = base_price * 0.4

        result['total_discount_pct'] = total_discount_pct
        result['total_discount_amount'] = total_discount_amount
        result['unit_price'] = base_price - total_discount_amount
        result['final_price'] = result['unit_price'] * qty

        return result


class SaleOrderB2BPricing(models.Model):
    _inherit = 'sale.order.line'

    b2b_discount_details = fields.Text(
        string='B2B Discount Details',
        readonly=True
    )

    @api.onchange('product_id', 'product_uom_qty')
    def _onchange_b2b_pricing(self):
        """Apply B2B pricing when product or quantity changes"""
        if self.product_id and self.order_id.partner_id:
            partner = self.order_id.partner_id

            if partner.customer_segment == 'b2b':
                engine = self.env['smart.b2b.pricing.engine']
                result = engine.calculate_price(
                    partner.id,
                    self.product_id.id,
                    self.product_uom_qty or 1.0,
                    self.order_id.date_order
                )

                if 'error' not in result:
                    self.price_unit = result['unit_price']
                    self.discount = result['total_discount_pct']

                    # Store discount details
                    details = []
                    for d in result['discounts']:
                        details.append(f"- {d['name']}: {d['percentage']}%")
                    self.b2b_discount_details = "\n".join(details) if details else ""
```

**Task 1.2: Pricelist Configuration Data (6h)**

```xml
<!-- smart_b2b_portal/data/b2b_pricelists.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <data noupdate="0">
        <!-- B2B Distributor Pricelist -->
        <record id="pricelist_b2b_distributor" model="product.pricelist">
            <field name="name">B2B Distributor Pricelist</field>
            <field name="is_b2b_pricelist">True</field>
            <field name="currency_id" ref="base.BDT"/>
            <field name="discount_policy">without_discount</field>
        </record>

        <!-- Link to distributor customer types -->
        <record id="pricelist_b2b_distributor" model="product.pricelist">
            <field name="b2b_customer_type_ids"
                   eval="[(6, 0, [ref('smart_crm_dairy.b2b_type_distributor_national'),
                                  ref('smart_crm_dairy.b2b_type_distributor_regional')])]"/>
        </record>

        <!-- Distributor Base Discount (15% off MRP) -->
        <record id="pricelist_item_dist_base" model="product.pricelist.item">
            <field name="pricelist_id" ref="pricelist_b2b_distributor"/>
            <field name="applied_on">3_global</field>
            <field name="compute_price">percentage</field>
            <field name="percent_price">-15</field>
        </record>

        <!-- Tier Discounts for Distributor Pricelist -->
        <record id="tier_discount_dist_gold" model="smart.b2b.tier.discount">
            <field name="pricelist_id" ref="pricelist_b2b_distributor"/>
            <field name="customer_tier_id" ref="smart_crm_dairy.customer_tier_gold"/>
            <field name="discount_percentage">3.0</field>
        </record>

        <record id="tier_discount_dist_platinum" model="smart.b2b.tier.discount">
            <field name="pricelist_id" ref="pricelist_b2b_distributor"/>
            <field name="customer_tier_id" ref="smart_crm_dairy.customer_tier_platinum"/>
            <field name="discount_percentage">5.0</field>
        </record>

        <!-- Volume Discounts -->
        <record id="volume_discount_dist_100" model="smart.b2b.volume.discount">
            <field name="pricelist_id" ref="pricelist_b2b_distributor"/>
            <field name="min_qty">100</field>
            <field name="max_qty">499</field>
            <field name="discount_percentage">2.0</field>
        </record>

        <record id="volume_discount_dist_500" model="smart.b2b.volume.discount">
            <field name="pricelist_id" ref="pricelist_b2b_distributor"/>
            <field name="min_qty">500</field>
            <field name="max_qty">999</field>
            <field name="discount_percentage">3.5</field>
        </record>

        <record id="volume_discount_dist_1000" model="smart.b2b.volume.discount">
            <field name="pricelist_id" ref="pricelist_b2b_distributor"/>
            <field name="min_qty">1000</field>
            <field name="max_qty">0</field>
            <field name="discount_percentage">5.0</field>
        </record>

        <!-- B2B Retailer Pricelist -->
        <record id="pricelist_b2b_retailer" model="product.pricelist">
            <field name="name">B2B Retailer Pricelist</field>
            <field name="is_b2b_pricelist">True</field>
            <field name="currency_id" ref="base.BDT"/>
        </record>

        <record id="pricelist_item_ret_base" model="product.pricelist.item">
            <field name="pricelist_id" ref="pricelist_b2b_retailer"/>
            <field name="applied_on">3_global</field>
            <field name="compute_price">percentage</field>
            <field name="percent_price">-8</field>
        </record>

        <!-- B2B HORECA Pricelist -->
        <record id="pricelist_b2b_horeca" model="product.pricelist">
            <field name="name">B2B HORECA Pricelist</field>
            <field name="is_b2b_pricelist">True</field>
            <field name="currency_id" ref="base.BDT"/>
        </record>

        <record id="pricelist_item_horeca_base" model="product.pricelist.item">
            <field name="pricelist_id" ref="pricelist_b2b_horeca"/>
            <field name="applied_on">3_global</field>
            <field name="compute_price">percentage</field>
            <field name="percent_price">-10</field>
        </record>
    </data>
</odoo>
```

---

### Day 155-156: Credit Management

#### Dev 2 Tasks (16h) - Credit System

**Task 2.1: Credit Management Model (8h)**

```python
# smart_b2b_portal/models/credit_management.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError, UserError
from datetime import date, timedelta

class PartnerCreditManagement(models.Model):
    _inherit = 'res.partner'

    # Credit Configuration
    credit_limit = fields.Float(string='Credit Limit (BDT)')
    credit_limit_approved_date = fields.Date(string='Credit Limit Approved Date')
    credit_limit_review_date = fields.Date(string='Next Review Date')
    credit_limit_approved_by = fields.Many2one(
        'res.users',
        string='Approved By'
    )

    # Credit Status
    credit_used = fields.Float(
        string='Credit Used',
        compute='_compute_credit_status',
        store=True
    )
    credit_available = fields.Float(
        string='Credit Available',
        compute='_compute_credit_status',
        store=True
    )
    credit_utilization = fields.Float(
        string='Credit Utilization %',
        compute='_compute_credit_status',
        store=True
    )

    credit_status = fields.Selection([
        ('good', 'Good Standing'),
        ('warning', 'Warning (>80%)'),
        ('blocked', 'Credit Blocked'),
        ('overdue', 'Overdue Payments'),
    ], string='Credit Status', compute='_compute_credit_status', store=True)

    # Credit Hold
    credit_hold = fields.Boolean(string='Credit Hold', default=False)
    credit_hold_reason = fields.Text(string='Hold Reason')
    credit_hold_date = fields.Date(string='Hold Date')
    credit_hold_by = fields.Many2one('res.users', string='Hold By')

    # Payment History
    avg_payment_days = fields.Float(
        string='Avg Payment Days',
        compute='_compute_payment_history'
    )
    payment_rating = fields.Selection([
        ('excellent', 'Excellent (<15 days)'),
        ('good', 'Good (15-30 days)'),
        ('average', 'Average (30-45 days)'),
        ('poor', 'Poor (>45 days)'),
    ], string='Payment Rating', compute='_compute_payment_history')

    @api.depends('credit_limit', 'invoice_ids', 'invoice_ids.state',
                 'invoice_ids.amount_residual', 'credit_hold')
    def _compute_credit_status(self):
        for partner in self:
            # Calculate credit used (unpaid invoices)
            unpaid_invoices = partner.invoice_ids.filtered(
                lambda i: i.state == 'posted' and
                          i.move_type == 'out_invoice' and
                          i.amount_residual > 0
            )
            credit_used = sum(unpaid_invoices.mapped('amount_residual'))
            partner.credit_used = credit_used

            # Calculate available credit
            if partner.credit_limit > 0:
                partner.credit_available = max(
                    0, partner.credit_limit - credit_used
                )
                partner.credit_utilization = (
                    credit_used / partner.credit_limit * 100
                )
            else:
                partner.credit_available = 0
                partner.credit_utilization = 0

            # Determine status
            if partner.credit_hold:
                partner.credit_status = 'blocked'
            elif self._has_overdue_invoices(partner):
                partner.credit_status = 'overdue'
            elif partner.credit_utilization >= 80:
                partner.credit_status = 'warning'
            else:
                partner.credit_status = 'good'

    def _has_overdue_invoices(self, partner):
        """Check if partner has overdue invoices"""
        overdue = partner.invoice_ids.filtered(
            lambda i: i.state == 'posted' and
                      i.move_type == 'out_invoice' and
                      i.amount_residual > 0 and
                      i.invoice_date_due and
                      i.invoice_date_due < date.today()
        )
        return bool(overdue)

    @api.depends('invoice_ids', 'invoice_ids.state',
                 'invoice_ids.invoice_date', 'invoice_ids.payment_state')
    def _compute_payment_history(self):
        for partner in self:
            paid_invoices = partner.invoice_ids.filtered(
                lambda i: i.state == 'posted' and
                          i.move_type == 'out_invoice' and
                          i.payment_state == 'paid'
            )

            if paid_invoices:
                total_days = 0
                count = 0
                for inv in paid_invoices[-20:]:  # Last 20 invoices
                    if inv.invoice_date and inv.invoice_date_due:
                        # Find last payment date
                        payments = inv._get_reconciled_payments()
                        if payments:
                            last_payment = max(payments.mapped('date'))
                            days = (last_payment - inv.invoice_date).days
                            total_days += days
                            count += 1

                if count > 0:
                    avg_days = total_days / count
                    partner.avg_payment_days = avg_days

                    if avg_days < 15:
                        partner.payment_rating = 'excellent'
                    elif avg_days < 30:
                        partner.payment_rating = 'good'
                    elif avg_days < 45:
                        partner.payment_rating = 'average'
                    else:
                        partner.payment_rating = 'poor'
                else:
                    partner.avg_payment_days = 0
                    partner.payment_rating = False
            else:
                partner.avg_payment_days = 0
                partner.payment_rating = False

    def action_place_credit_hold(self):
        """Place partner on credit hold"""
        return {
            'type': 'ir.actions.act_window',
            'res_model': 'smart.credit.hold.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_partner_id': self.id},
        }

    def action_release_credit_hold(self):
        """Release credit hold"""
        self.write({
            'credit_hold': False,
            'credit_hold_reason': False,
            'credit_hold_date': False,
            'credit_hold_by': False,
        })

    def check_credit_for_order(self, order_amount):
        """
        Check if partner has sufficient credit for an order.
        Returns dict with status and message.
        """
        self.ensure_one()

        # Force recompute
        self._compute_credit_status()

        if self.credit_hold:
            return {
                'allowed': False,
                'reason': 'credit_hold',
                'message': f"Credit is on hold: {self.credit_hold_reason or 'Contact accounts team'}",
            }

        if self.credit_status == 'overdue':
            return {
                'allowed': False,
                'reason': 'overdue',
                'message': "You have overdue invoices. Please clear outstanding payments.",
            }

        if self.credit_limit <= 0:
            # No credit limit - cash only
            return {
                'allowed': True,
                'reason': 'no_credit',
                'message': "No credit facility. Payment required before dispatch.",
                'require_payment': True,
            }

        if order_amount > self.credit_available:
            return {
                'allowed': False,
                'reason': 'insufficient',
                'message': f"Order exceeds available credit. "
                          f"Available: BDT {self.credit_available:,.0f}, "
                          f"Order: BDT {order_amount:,.0f}",
                'available': self.credit_available,
                'shortfall': order_amount - self.credit_available,
            }

        return {
            'allowed': True,
            'reason': 'approved',
            'message': "Credit approved",
            'remaining_after': self.credit_available - order_amount,
        }


class CreditHoldWizard(models.TransientModel):
    _name = 'smart.credit.hold.wizard'
    _description = 'Credit Hold Wizard'

    partner_id = fields.Many2one('res.partner', string='Partner', required=True)
    reason = fields.Selection([
        ('overdue', 'Overdue Payments'),
        ('limit_exceeded', 'Credit Limit Exceeded'),
        ('bounced_cheque', 'Bounced Cheque'),
        ('dispute', 'Payment Dispute'),
        ('review', 'Under Review'),
        ('other', 'Other'),
    ], string='Reason', required=True)
    notes = fields.Text(string='Additional Notes')

    def action_confirm(self):
        reason_text = dict(self._fields['reason'].selection).get(self.reason)
        full_reason = f"{reason_text}"
        if self.notes:
            full_reason += f"\n{self.notes}"

        self.partner_id.write({
            'credit_hold': True,
            'credit_hold_reason': full_reason,
            'credit_hold_date': date.today(),
            'credit_hold_by': self.env.uid,
        })

        return {'type': 'ir.actions.act_window_close'}
```

**Task 2.2: Credit Check in Sales Order (8h)**

```python
# smart_b2b_portal/models/sale_order_credit.py
from odoo import models, fields, api
from odoo.exceptions import UserError

class SaleOrderCreditCheck(models.Model):
    _inherit = 'sale.order'

    # Credit Status Fields
    credit_check_status = fields.Selection([
        ('pending', 'Pending Check'),
        ('approved', 'Credit Approved'),
        ('rejected', 'Credit Rejected'),
        ('override', 'Override Applied'),
    ], string='Credit Status', default='pending', tracking=True)

    credit_check_message = fields.Text(string='Credit Check Message')
    credit_override_reason = fields.Text(string='Override Reason')
    credit_override_by = fields.Many2one('res.users', string='Override By')

    requires_credit_approval = fields.Boolean(
        string='Requires Credit Approval',
        compute='_compute_requires_credit_approval'
    )

    @api.depends('partner_id', 'amount_total', 'partner_id.credit_status')
    def _compute_requires_credit_approval(self):
        for order in self:
            if order.partner_id and order.partner_id.customer_segment == 'b2b':
                result = order.partner_id.check_credit_for_order(order.amount_total)
                order.requires_credit_approval = not result.get('allowed', True)
            else:
                order.requires_credit_approval = False

    def action_check_credit(self):
        """Perform credit check for B2B order"""
        self.ensure_one()

        if self.partner_id.customer_segment != 'b2b':
            self.credit_check_status = 'approved'
            self.credit_check_message = 'B2C order - no credit check required'
            return True

        result = self.partner_id.check_credit_for_order(self.amount_total)

        if result['allowed']:
            self.credit_check_status = 'approved'
            self.credit_check_message = result['message']
            return True
        else:
            self.credit_check_status = 'rejected'
            self.credit_check_message = result['message']
            return False

    def action_credit_override(self):
        """Open wizard for credit override"""
        return {
            'type': 'ir.actions.act_window',
            'res_model': 'smart.credit.override.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_order_id': self.id},
        }

    def action_confirm(self):
        """Override confirm to include credit check"""
        for order in self:
            if order.partner_id.customer_segment == 'b2b':
                if order.credit_check_status == 'pending':
                    order.action_check_credit()

                if order.credit_check_status == 'rejected':
                    raise UserError(
                        f"Cannot confirm order - Credit check failed:\n"
                        f"{order.credit_check_message}\n\n"
                        f"Please request a credit override or adjust the order."
                    )

        return super().action_confirm()


class CreditOverrideWizard(models.TransientModel):
    _name = 'smart.credit.override.wizard'
    _description = 'Credit Override Wizard'

    order_id = fields.Many2one('sale.order', string='Order', required=True)
    reason = fields.Selection([
        ('management_approval', 'Management Approval'),
        ('partial_payment', 'Partial Payment Received'),
        ('bank_guarantee', 'Bank Guarantee Provided'),
        ('strategic_customer', 'Strategic Customer'),
        ('one_time', 'One-time Exception'),
    ], string='Override Reason', required=True)
    notes = fields.Text(string='Additional Notes')
    approval_reference = fields.Char(string='Approval Reference')

    def action_apply_override(self):
        """Apply credit override"""
        self.ensure_one()

        reason_text = dict(self._fields['reason'].selection).get(self.reason)
        full_reason = f"{reason_text}"
        if self.approval_reference:
            full_reason += f" (Ref: {self.approval_reference})"
        if self.notes:
            full_reason += f"\n{self.notes}"

        self.order_id.write({
            'credit_check_status': 'override',
            'credit_override_reason': full_reason,
            'credit_override_by': self.env.uid,
        })

        return {'type': 'ir.actions.act_window_close'}
```

---

### Day 156-158: Order Approval & Standing Orders

*(Condensed for document length - key models)*

```python
# smart_b2b_portal/models/order_approval.py
class B2BOrderApproval(models.Model):
    _name = 'smart.b2b.order.approval'
    _description = 'B2B Order Approval Workflow'
    _inherit = ['mail.thread']

    order_id = fields.Many2one('sale.order', required=True)
    approval_type = fields.Selection([
        ('credit', 'Credit Limit'),
        ('discount', 'Discount Level'),
        ('amount', 'Order Amount'),
        ('product', 'Restricted Product'),
    ])
    state = fields.Selection([
        ('pending', 'Pending'),
        ('approved', 'Approved'),
        ('rejected', 'Rejected'),
    ], default='pending')
    approver_id = fields.Many2one('res.users')
    approval_date = fields.Datetime()
    notes = fields.Text()


# smart_b2b_portal/models/standing_order.py
class StandingOrder(models.Model):
    _name = 'smart.standing.order'
    _description = 'B2B Standing Order (Recurring)'
    _inherit = ['mail.thread']

    name = fields.Char(readonly=True, default='New')
    partner_id = fields.Many2one('res.partner', required=True)
    state = fields.Selection([
        ('draft', 'Draft'),
        ('active', 'Active'),
        ('paused', 'Paused'),
        ('cancelled', 'Cancelled'),
    ], default='draft')

    # Schedule
    frequency = fields.Selection([
        ('daily', 'Daily'),
        ('weekly', 'Weekly'),
        ('biweekly', 'Bi-weekly'),
        ('monthly', 'Monthly'),
    ], required=True)
    day_of_week = fields.Selection([
        ('0', 'Monday'), ('1', 'Tuesday'), ('2', 'Wednesday'),
        ('3', 'Thursday'), ('4', 'Friday'), ('5', 'Saturday'), ('6', 'Sunday'),
    ])
    day_of_month = fields.Integer()
    start_date = fields.Date(required=True)
    end_date = fields.Date()
    next_order_date = fields.Date(compute='_compute_next_order')

    # Order Template
    line_ids = fields.One2many('smart.standing.order.line', 'standing_order_id')
    delivery_address_id = fields.Many2one('res.partner')
    notes = fields.Text()

    # Statistics
    order_count = fields.Integer(compute='_compute_order_count')
    total_value = fields.Float(compute='_compute_order_count')
    generated_order_ids = fields.One2many('sale.order', 'standing_order_id')

    @api.model
    def _cron_generate_standing_orders(self):
        """Scheduled job to generate orders from standing orders"""
        today = date.today()
        due_orders = self.search([
            ('state', '=', 'active'),
            ('next_order_date', '<=', today),
            '|',
            ('end_date', '=', False),
            ('end_date', '>=', today),
        ])

        for standing in due_orders:
            standing._generate_order()


class StandingOrderLine(models.Model):
    _name = 'smart.standing.order.line'
    _description = 'Standing Order Line'

    standing_order_id = fields.Many2one('smart.standing.order', ondelete='cascade')
    product_id = fields.Many2one('product.product', required=True)
    quantity = fields.Float(required=True, default=1.0)
    product_uom_id = fields.Many2one('uom.uom')
```

---

## 4. Testing & Validation

### 4.1 Integration Test Suite

```python
# smart_b2b_portal/tests/test_b2b_portal.py
from odoo.tests import tagged, TransactionCase
from datetime import date

@tagged('post_install', '-at_install', 'smart_b2b')
class TestB2BPortal(TransactionCase):

    def test_registration_workflow(self):
        """Test complete registration workflow"""
        # Create registration
        reg = self.env['smart.b2b.registration'].create({
            'company_name': 'Test Distributor Ltd',
            'business_type_id': self.env.ref(
                'smart_crm_dairy.b2b_type_distributor_regional'
            ).id,
            'contact_name': 'Test Contact',
            'email': 'test@distributor.com',
            'phone': '+8801712345678',
            'trade_license_no': 'TL-TEST-001',
        })

        self.assertEqual(reg.state, 'draft')

        # Submit
        reg.action_submit()
        self.assertEqual(reg.state, 'submitted')

    def test_tiered_pricing(self):
        """Test tiered pricing calculation"""
        engine = self.env['smart.b2b.pricing.engine']

        # Create B2B partner
        partner = self.env['res.partner'].create({
            'name': 'B2B Test Partner',
            'customer_segment': 'b2b',
            'b2b_customer_type_id': self.env.ref(
                'smart_crm_dairy.b2b_type_distributor_regional'
            ).id,
        })

        product = self.env.ref('product.product_product_4')

        result = engine.calculate_price(partner.id, product.id, qty=100)

        self.assertIn('final_price', result)
        self.assertIn('discounts', result)

    def test_credit_check(self):
        """Test credit check functionality"""
        partner = self.env['res.partner'].create({
            'name': 'Credit Test Partner',
            'customer_segment': 'b2b',
            'credit_limit': 100000,
        })

        # Check with order within limit
        result = partner.check_credit_for_order(50000)
        self.assertTrue(result['allowed'])

        # Check with order exceeding limit
        result = partner.check_credit_for_order(150000)
        self.assertFalse(result['allowed'])
```

---

## 5. Module Manifest

```python
# smart_b2b_portal/__manifest__.py
{
    'name': 'Smart Dairy B2B Portal',
    'version': '19.0.1.0.0',
    'category': 'Sales',
    'summary': 'B2B Portal for Smart Dairy',
    'depends': [
        'sale_management',
        'website_sale',
        'smart_crm_dairy',
        'smart_bd_local',
    ],
    'data': [
        'security/ir.model.access.csv',
        'security/b2b_security.xml',
        'data/registration_data.xml',
        'data/b2b_pricelists.xml',
        'data/cron_jobs.xml',
        'views/registration_views.xml',
        'views/credit_management_views.xml',
        'views/standing_order_views.xml',
        'views/templates/registration_form.xml',
        'views/menus.xml',
        'wizard/rejection_wizard_views.xml',
        'wizard/credit_wizards_views.xml',
    ],
    'installable': True,
    'application': False,
    'license': 'LGPL-3',
}
```

---

## 6. Milestone Completion Checklist

- [ ] B2B registration portal operational
- [ ] Multi-step registration form functional
- [ ] Partner verification workflow complete
- [ ] Tiered pricing engine accurate
- [ ] Credit management system operational
- [ ] Order approval workflow functional
- [ ] Standing orders generating correctly
- [ ] All unit tests passing
- [ ] Integration with CRM verified
- [ ] Documentation complete

---

**End of Milestone 16 Documentation**
