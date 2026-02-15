# PHASE 9: COMMERCE - B2B Portal Foundation
## DETAILED IMPLEMENTATION PLAN

---

**Document Control**

| Attribute | Value |
|-----------|-------|
| **Phase Number** | 9 of 15 |
| **Phase Name** | Commerce - B2B Portal Foundation |
| **Duration** | Days 401-450 (50 working days) |
| **Milestones** | 10 milestones × 5 days each |
| **Document Version** | 1.0 |
| **Release Date** | February 3, 2026 |
| **Dependencies** | Phase 1-8 Complete |
| **Team Size** | 3 Full-Stack Developers + 1 B2B Commerce Specialist |

---

## TABLE OF CONTENTS

1. [Phase Overview](#phase-overview)
2. [Phase Objectives](#phase-objectives)
3. [Key Deliverables](#key-deliverables)
4. [Milestone 1: B2B Customer Portal (Days 401-405)](#milestone-1-b2b-customer-portal-days-401-405)
5. [Milestone 2: B2B Product Catalog (Days 406-410)](#milestone-2-b2b-product-catalog-days-406-410)
6. [Milestone 3: Request for Quotation (Days 411-415)](#milestone-3-request-for-quotation-days-411-415)
7. [Milestone 4: Credit Management (Days 416-420)](#milestone-4-credit-management-days-416-420)
8. [Milestone 5: Bulk Order System (Days 421-425)](#milestone-5-bulk-order-system-days-421-425)
9. [Milestone 6: B2B Checkout & Payment (Days 426-430)](#milestone-6-b2b-checkout--payment-days-426-430)
10. [Milestone 7: Contract Management (Days 431-435)](#milestone-7-contract-management-days-431-435)
11. [Milestone 8: B2B Analytics (Days 436-440)](#milestone-8-b2b-analytics-days-436-440)
12. [Milestone 9: Partner Portal Integration (Days 441-445)](#milestone-9-partner-portal-integration-days-441-445)
13. [Milestone 10: B2B Testing (Days 446-450)](#milestone-10-b2b-testing-days-446-450)
14. [Phase Success Criteria](#phase-success-criteria)

---

## PHASE OVERVIEW

Phase 9 implements a comprehensive B2B (Business-to-Business) portal that enables Smart Dairy to serve wholesale customers, distributors, retailers, and institutional buyers. This system provides tier-based pricing, credit management, bulk ordering, contract management, and specialized B2B workflows.

### Phase Context

Building on the complete ERP and operations foundation from Phases 1-8, Phase 9 creates:
- Multi-tier B2B customer portal with approval workflows
- Bulk pricing and volume discount management
- Request for Quotation (RFQ) and bidding system
- Credit limit and payment terms management
- Bulk order processing with CSV upload
- Contract-based pricing and volume commitments
- B2B-specific analytics and reporting
- Partner portal for collaborative planning

---

## PHASE OBJECTIVES

### Primary Objectives

1. **B2B Customer Management**: Multi-tier registration, approval workflow, tier-based access control
2. **Bulk Pricing Engine**: Volume-based pricing, tier discounts, minimum order quantities (MOQ)
3. **RFQ System**: Complete quotation workflow, supplier bidding, comparative analysis
4. **Credit Operations**: Credit limits, payment terms, aging reports, collection management
5. **Bulk Ordering**: Quick order forms, CSV bulk upload, saved order templates
6. **B2B Payments**: Credit invoicing, payment terms (Net 30/60/90), purchase orders
7. **Contract Management**: Price agreements, volume commitments, renewal tracking
8. **B2B Analytics**: Purchase history, spend analysis, customer trends, forecasting
9. **Partner Collaboration**: Supplier portal, collaborative planning, forecast sharing
10. **B2B Testing**: Complete workflow validation, performance testing, security audits

### Secondary Objectives

- Multi-currency support for international B2B customers
- Multi-warehouse allocation for bulk orders
- Automated credit approval workflows
- B2B-specific promotions and campaigns
- Purchase order integration with ERP
- Document management (contracts, agreements, certificates)
- B2B customer self-service capabilities

---

## KEY DELIVERABLES

### B2B Customer Portal Deliverables
1. ✓ Multi-tier customer registration (Bronze, Silver, Gold, Platinum)
2. ✓ B2B approval workflow with KYC verification
3. ✓ Tier-based access control and permissions
4. ✓ Company profile management
5. ✓ Multi-user accounts per company
6. ✓ Customer dashboard with order history

### B2B Product Catalog Deliverables
7. ✓ Bulk pricing rules engine
8. ✓ Tier-based discount management
9. ✓ Minimum order quantity (MOQ) enforcement
10. ✓ Case/pallet/bulk unit pricing
11. ✓ B2B product categories and filters
12. ✓ Personalized catalogs per customer tier

### RFQ System Deliverables
13. ✓ RFQ creation and submission
14. ✓ Multi-supplier bidding system
15. ✓ Quote comparison and analysis
16. ✓ RFQ approval workflow
17. ✓ Quote-to-order conversion
18. ✓ RFQ templates and history

### Credit Management Deliverables
19. ✓ Customer credit limit assignment
20. ✓ Payment terms configuration (Net 30/60/90)
21. ✓ Credit utilization tracking
22. ✓ Aging reports (30/60/90/120 days)
23. ✓ Automated credit holds
24. ✓ Payment collection workflows

### Bulk Order System Deliverables
25. ✓ Quick order form (SKU + Quantity)
26. ✓ CSV bulk order upload
27. ✓ Saved order templates
28. ✓ Re-order from history
29. ✓ Split shipment management
30. ✓ Partial order fulfillment

### B2B Checkout & Payment Deliverables
31. ✓ Credit-based checkout
32. ✓ Purchase order (PO) integration
33. ✓ Multi-payment methods (Credit, Wire, Check)
34. ✓ Invoice generation with terms
35. ✓ Payment reminders and alerts
36. ✓ Statement of accounts

### Contract Management Deliverables
37. ✓ Price agreement contracts
38. ✓ Volume commitment tracking
39. ✓ Contract renewal workflows
40. ✓ Contract-based pricing override
41. ✓ Contract performance analytics
42. ✓ Document repository

### B2B Analytics Deliverables
43. ✓ Purchase history analysis
44. ✓ Customer spend analytics
45. ✓ Product performance by customer tier
46. ✓ Forecasting and trend analysis
47. ✓ Customer lifetime value (CLV)
48. ✓ Executive B2B dashboards

---

## MILESTONE 1: B2B Customer Portal (Days 401-405)

**Objective**: Create multi-tier B2B customer portal with registration, approval workflow, and tier-based access control.

**Duration**: 5 working days

---

### Day 401: B2B Customer Model & Registration

**[Dev 1] - B2B Customer Core Model (8h)**
- Create B2B customer registration model:
  ```python
  # odoo/addons/smart_dairy_b2b/models/b2b_customer.py
  from odoo import models, fields, api
  from odoo.exceptions import ValidationError
  from datetime import datetime, timedelta

  class B2BCustomer(models.Model):
      _name = 'b2b.customer'
      _description = 'B2B Customer Registration'
      _inherit = ['mail.thread', 'mail.activity.mixin']
      _order = 'customer_code desc'

      # Basic Information
      customer_code = fields.Char(
          string='Customer Code',
          required=True,
          copy=False,
          readonly=True,
          default=lambda self: self._generate_customer_code()
      )

      company_name = fields.Char(
          string='Company Name',
          required=True,
          tracking=True
      )

      trade_name = fields.Char(
          string='Trade Name/DBA',
          tracking=True
      )

      company_type = fields.Selection([
          ('retailer', 'Retailer'),
          ('distributor', 'Distributor'),
          ('wholesaler', 'Wholesaler'),
          ('restaurant', 'Restaurant/Hotel'),
          ('institution', 'Institution (School/Hospital)'),
          ('manufacturer', 'Manufacturer'),
      ], string='Company Type', required=True, tracking=True)

      # Customer Tier
      customer_tier = fields.Selection([
          ('bronze', 'Bronze'),
          ('silver', 'Silver'),
          ('gold', 'Gold'),
          ('platinum', 'Platinum'),
      ], string='Customer Tier', default='bronze', required=True, tracking=True)

      tier_benefits = fields.Text(
          string='Tier Benefits',
          compute='_compute_tier_benefits'
      )

      # Registration Details
      registration_date = fields.Date(
          string='Registration Date',
          default=fields.Date.today,
          readonly=True
      )

      approval_status = fields.Selection([
          ('draft', 'Draft'),
          ('pending', 'Pending Approval'),
          ('kyc_review', 'KYC Under Review'),
          ('credit_review', 'Credit Review'),
          ('approved', 'Approved'),
          ('rejected', 'Rejected'),
          ('suspended', 'Suspended'),
      ], string='Approval Status', default='draft', tracking=True)

      approved_by = fields.Many2one(
          'res.users',
          string='Approved By',
          readonly=True
      )

      approval_date = fields.Date(
          string='Approval Date',
          readonly=True
      )

      # Business Registration
      business_registration_number = fields.Char(
          string='Business Registration Number',
          required=True
      )

      tax_identification_number = fields.Char(
          string='Tax ID/TIN',
          required=True
      )

      vat_number = fields.Char(string='VAT Number')

      trade_license_number = fields.Char(
          string='Trade License Number',
          required=True
      )

      trade_license_expiry = fields.Date(string='Trade License Expiry')

      # Contact Information
      primary_contact_name = fields.Char(
          string='Primary Contact Name',
          required=True
      )

      primary_contact_designation = fields.Char(
          string='Designation'
      )

      primary_contact_phone = fields.Char(
          string='Contact Phone',
          required=True
      )

      primary_contact_email = fields.Char(
          string='Contact Email',
          required=True
      )

      alternate_contact_name = fields.Char(string='Alternate Contact')
      alternate_contact_phone = fields.Char(string='Alternate Phone')

      # Business Address
      street = fields.Char(string='Street')
      street2 = fields.Char(string='Street 2')
      city = fields.Char(string='City', required=True)
      state_id = fields.Many2one('res.country.state', string='State')
      zip = fields.Char(string='ZIP/Postal Code')
      country_id = fields.Many2one('res.country', string='Country', required=True)

      # Delivery Addresses
      delivery_address_ids = fields.One2many(
          'b2b.delivery.address',
          'customer_id',
          string='Delivery Addresses'
      )

      delivery_address_count = fields.Integer(
          string='Number of Delivery Addresses',
          compute='_compute_delivery_address_count'
      )

      # Business Information
      year_established = fields.Integer(string='Year Established')
      number_of_employees = fields.Integer(string='Number of Employees')
      annual_turnover = fields.Float(string='Annual Turnover')

      business_description = fields.Text(string='Business Description')

      warehouse_capacity = fields.Float(
          string='Warehouse Capacity (sq ft)',
          help='For distributors and wholesalers'
      )

      cold_storage_available = fields.Boolean(string='Cold Storage Available')

      number_of_outlets = fields.Integer(
          string='Number of Outlets',
          help='For retailers and restaurant chains'
      )

      # Bank Details
      bank_name = fields.Char(string='Bank Name')
      bank_account_number = fields.Char(string='Account Number')
      bank_account_name = fields.Char(string='Account Name')
      bank_branch = fields.Char(string='Branch')
      bank_routing_number = fields.Char(string='Routing Number')

      # Credit Information
      credit_limit = fields.Float(
          string='Credit Limit',
          tracking=True
      )

      credit_utilized = fields.Float(
          string='Credit Utilized',
          compute='_compute_credit_utilized'
      )

      credit_available = fields.Float(
          string='Credit Available',
          compute='_compute_credit_available'
      )

      payment_terms_id = fields.Many2one(
          'account.payment.term',
          string='Default Payment Terms'
      )

      credit_on_hold = fields.Boolean(
          string='Credit on Hold',
          tracking=True
      )

      # Portal Users
      portal_user_ids = fields.One2many(
          'b2b.portal.user',
          'customer_id',
          string='Portal Users'
      )

      portal_user_count = fields.Integer(
          string='Number of Portal Users',
          compute='_compute_portal_user_count'
      )

      # Documents
      document_ids = fields.One2many(
          'b2b.customer.document',
          'customer_id',
          string='Documents'
      )

      # Relationships
      partner_id = fields.Many2one(
          'res.partner',
          string='Related Partner',
          readonly=True
      )

      user_id = fields.Many2one(
          'res.users',
          string='Account Manager',
          tracking=True
      )

      # Statistics
      total_orders = fields.Integer(
          string='Total Orders',
          compute='_compute_statistics'
      )

      total_order_value = fields.Float(
          string='Total Order Value',
          compute='_compute_statistics'
      )

      average_order_value = fields.Float(
          string='Average Order Value',
          compute='_compute_statistics'
      )

      last_order_date = fields.Date(
          string='Last Order Date',
          compute='_compute_statistics'
      )

      # Computed Fields
      is_active = fields.Boolean(
          string='Is Active',
          compute='_compute_is_active',
          store=True
      )

      @api.model
      def _generate_customer_code(self):
          sequence = self.env['ir.sequence'].next_by_code('b2b.customer')
          return sequence or 'B2B000001'

      @api.depends('approval_status', 'credit_on_hold')
      def _compute_is_active(self):
          for record in self:
              record.is_active = (
                  record.approval_status == 'approved' and
                  not record.credit_on_hold
              )

      @api.depends('customer_tier')
      def _compute_tier_benefits(self):
          benefits = {
              'bronze': '- 5% discount on bulk orders\n- Net 30 payment terms\n- Standard delivery',
              'silver': '- 10% discount on bulk orders\n- Net 45 payment terms\n- Priority delivery\n- Dedicated account manager',
              'gold': '- 15% discount on bulk orders\n- Net 60 payment terms\n- Same-day delivery\n- Dedicated account manager\n- Quarterly business review',
              'platinum': '- 20% discount on bulk orders\n- Net 90 payment terms\n- On-demand delivery\n- Dedicated account manager\n- Monthly business review\n- Custom product development\n- Co-marketing opportunities',
          }
          for record in self:
              record.tier_benefits = benefits.get(record.customer_tier, '')

      @api.depends('delivery_address_ids')
      def _compute_delivery_address_count(self):
          for record in self:
              record.delivery_address_count = len(record.delivery_address_ids)

      @api.depends('portal_user_ids')
      def _compute_portal_user_count(self):
          for record in self:
              record.portal_user_count = len(record.portal_user_ids)

      def _compute_credit_utilized(self):
          for record in self:
              # Sum of unpaid invoices
              invoices = self.env['account.move'].search([
                  ('partner_id', '=', record.partner_id.id),
                  ('move_type', '=', 'out_invoice'),
                  ('state', '=', 'posted'),
                  ('payment_state', 'in', ['not_paid', 'partial'])
              ])
              record.credit_utilized = sum(invoices.mapped('amount_residual'))

      @api.depends('credit_limit', 'credit_utilized')
      def _compute_credit_available(self):
          for record in self:
              record.credit_available = record.credit_limit - record.credit_utilized

      def _compute_statistics(self):
          for record in self:
              orders = self.env['sale.order'].search([
                  ('partner_id', '=', record.partner_id.id),
                  ('state', 'in', ['sale', 'done'])
              ])
              record.total_orders = len(orders)
              record.total_order_value = sum(orders.mapped('amount_total'))
              record.average_order_value = (
                  record.total_order_value / record.total_orders
                  if record.total_orders > 0 else 0
              )
              record.last_order_date = (
                  max(orders.mapped('date_order')).date()
                  if orders else False
              )

      @api.constrains('primary_contact_email')
      def _check_email(self):
          for record in self:
              if record.primary_contact_email:
                  import re
                  if not re.match(r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$',
                                  record.primary_contact_email):
                      raise ValidationError('Invalid email format')

      @api.constrains('credit_limit')
      def _check_credit_limit(self):
          for record in self:
              if record.credit_limit < 0:
                  raise ValidationError('Credit limit cannot be negative')

      def action_submit_for_approval(self):
          self.write({'approval_status': 'pending'})
          # Send notification to approval team
          self._send_approval_notification()

      def action_approve(self):
          self.write({
              'approval_status': 'approved',
              'approved_by': self.env.user.id,
              'approval_date': fields.Date.today()
          })
          # Create res.partner
          self._create_partner()
          # Send welcome email
          self._send_welcome_email()

      def action_reject(self):
          self.write({'approval_status': 'rejected'})
          self._send_rejection_email()

      def action_suspend(self):
          self.write({'approval_status': 'suspended'})

      def _create_partner(self):
          """Create res.partner from B2B customer"""
          for record in self:
              if not record.partner_id:
                  partner = self.env['res.partner'].create({
                      'name': record.company_name,
                      'customer_rank': 1,
                      'is_company': True,
                      'street': record.street,
                      'street2': record.street2,
                      'city': record.city,
                      'state_id': record.state_id.id,
                      'zip': record.zip,
                      'country_id': record.country_id.id,
                      'phone': record.primary_contact_phone,
                      'email': record.primary_contact_email,
                      'vat': record.vat_number,
                      'property_payment_term_id': record.payment_terms_id.id,
                      'credit_limit': record.credit_limit,
                  })
                  record.partner_id = partner.id

      def _send_approval_notification(self):
          # Implementation for approval notification
          pass

      def _send_welcome_email(self):
          # Implementation for welcome email
          pass

      def _send_rejection_email(self):
          # Implementation for rejection email
          pass
  ```

**[Dev 2] - B2B Delivery Address & Portal Users (8h)**
- Create delivery address model:
  ```python
  # odoo/addons/smart_dairy_b2b/models/b2b_delivery_address.py
  from odoo import models, fields, api

  class B2BDeliveryAddress(models.Model):
      _name = 'b2b.delivery.address'
      _description = 'B2B Delivery Address'
      _order = 'sequence, id'

      customer_id = fields.Many2one(
          'b2b.customer',
          string='Customer',
          required=True,
          ondelete='cascade'
      )

      sequence = fields.Integer(string='Sequence', default=10)

      name = fields.Char(
          string='Address Name',
          required=True,
          help='e.g., Main Warehouse, Branch Office, Outlet 1'
      )

      address_type = fields.Selection([
          ('warehouse', 'Warehouse'),
          ('office', 'Office'),
          ('outlet', 'Retail Outlet'),
          ('other', 'Other'),
      ], string='Address Type', default='warehouse')

      contact_person = fields.Char(string='Contact Person')
      contact_phone = fields.Char(string='Contact Phone')

      street = fields.Char(string='Street', required=True)
      street2 = fields.Char(string='Street 2')
      city = fields.Char(string='City', required=True)
      state_id = fields.Many2one('res.country.state', string='State')
      zip = fields.Char(string='ZIP')
      country_id = fields.Many2one('res.country', string='Country', required=True)

      is_default = fields.Boolean(string='Default Address')
      is_active = fields.Boolean(string='Active', default=True)

      delivery_instructions = fields.Text(string='Delivery Instructions')

      latitude = fields.Float(string='Latitude', digits=(10, 7))
      longitude = fields.Float(string='Longitude', digits=(10, 7))

      @api.model
      def create(self, vals):
          if vals.get('is_default'):
              # Unset other default addresses
              self.search([
                  ('customer_id', '=', vals.get('customer_id')),
                  ('is_default', '=', True)
              ]).write({'is_default': False})
          return super().create(vals)

      def write(self, vals):
          if vals.get('is_default'):
              # Unset other default addresses
              self.search([
                  ('customer_id', '=', self.customer_id.id),
                  ('is_default', '=', True),
                  ('id', '!=', self.id)
              ]).write({'is_default': False})
          return super().write(vals)
  ```

- Create B2B portal user model:
  ```python
  # odoo/addons/smart_dairy_b2b/models/b2b_portal_user.py
  from odoo import models, fields, api
  from odoo.exceptions import ValidationError

  class B2BPortalUser(models.Model):
      _name = 'b2b.portal.user'
      _description = 'B2B Portal User'
      _inherit = ['mail.thread']

      customer_id = fields.Many2one(
          'b2b.customer',
          string='Customer',
          required=True,
          ondelete='cascade'
      )

      user_id = fields.Many2one(
          'res.users',
          string='Portal User',
          required=True,
          ondelete='cascade'
      )

      name = fields.Char(string='Full Name', required=True)
      designation = fields.Char(string='Designation')
      department = fields.Char(string='Department')

      email = fields.Char(string='Email', required=True)
      phone = fields.Char(string='Phone')

      user_role = fields.Selection([
          ('admin', 'Administrator'),
          ('buyer', 'Buyer/Purchaser'),
          ('approver', 'Approver'),
          ('viewer', 'Viewer Only'),
      ], string='User Role', default='buyer', required=True)

      is_primary = fields.Boolean(string='Primary Contact')
      is_active = fields.Boolean(string='Active', default=True)

      # Permissions
      can_place_orders = fields.Boolean(string='Can Place Orders', default=True)
      can_approve_orders = fields.Boolean(string='Can Approve Orders')
      can_view_pricing = fields.Boolean(string='Can View Pricing', default=True)
      can_view_invoices = fields.Boolean(string='Can View Invoices', default=True)
      can_make_payments = fields.Boolean(string='Can Make Payments')
      can_manage_users = fields.Boolean(string='Can Manage Users')

      approval_limit = fields.Float(
          string='Approval Limit',
          help='Maximum order value this user can approve'
      )

      last_login = fields.Datetime(string='Last Login', readonly=True)
      login_count = fields.Integer(string='Login Count', readonly=True)

      @api.onchange('user_role')
      def _onchange_user_role(self):
          role_permissions = {
              'admin': {
                  'can_place_orders': True,
                  'can_approve_orders': True,
                  'can_view_pricing': True,
                  'can_view_invoices': True,
                  'can_make_payments': True,
                  'can_manage_users': True,
              },
              'buyer': {
                  'can_place_orders': True,
                  'can_approve_orders': False,
                  'can_view_pricing': True,
                  'can_view_invoices': True,
                  'can_make_payments': False,
                  'can_manage_users': False,
              },
              'approver': {
                  'can_place_orders': False,
                  'can_approve_orders': True,
                  'can_view_pricing': True,
                  'can_view_invoices': True,
                  'can_make_payments': True,
                  'can_manage_users': False,
              },
              'viewer': {
                  'can_place_orders': False,
                  'can_approve_orders': False,
                  'can_view_pricing': True,
                  'can_view_invoices': True,
                  'can_make_payments': False,
                  'can_manage_users': False,
              },
          }

          permissions = role_permissions.get(self.user_role, {})
          for key, value in permissions.items():
              setattr(self, key, value)

      @api.constrains('email')
      def _check_email_unique(self):
          for record in self:
              existing = self.search([
                  ('email', '=', record.email),
                  ('id', '!=', record.id)
              ])
              if existing:
                  raise ValidationError(f'Email {record.email} is already in use')

      def action_create_portal_user(self):
          """Create portal user account"""
          self.ensure_one()
          if not self.user_id:
              user = self.env['res.users'].create({
                  'name': self.name,
                  'login': self.email,
                  'email': self.email,
                  'partner_id': self.customer_id.partner_id.id,
                  'groups_id': [(6, 0, [self.env.ref('base.group_portal').id])],
              })
              self.user_id = user.id
              # Send invitation email
              self._send_invitation_email()

      def _send_invitation_email(self):
          # Implementation for invitation email
          pass
  ```

**[Dev 3] - B2B Customer Document Management (8h)**
- Create document management model:
  ```python
  # odoo/addons/smart_dairy_b2b/models/b2b_customer_document.py
  from odoo import models, fields, api
  from odoo.exceptions import ValidationError
  from datetime import datetime, timedelta

  class B2BCustomerDocument(models.Model):
      _name = 'b2b.customer.document'
      _description = 'B2B Customer Document'
      _inherit = ['mail.thread']
      _order = 'document_date desc'

      customer_id = fields.Many2one(
          'b2b.customer',
          string='Customer',
          required=True,
          ondelete='cascade'
      )

      document_type = fields.Selection([
          ('trade_license', 'Trade License'),
          ('tax_certificate', 'Tax Registration Certificate'),
          ('vat_certificate', 'VAT Certificate'),
          ('bank_statement', 'Bank Statement'),
          ('company_incorporation', 'Company Incorporation Certificate'),
          ('authorized_signatory', 'Authorized Signatory List'),
          ('credit_reference', 'Credit Reference'),
          ('warehouse_proof', 'Warehouse Ownership/Lease Proof'),
          ('other', 'Other Document'),
      ], string='Document Type', required=True, tracking=True)

      document_name = fields.Char(string='Document Name', required=True)
      document_number = fields.Char(string='Document Number')

      document_file = fields.Binary(
          string='Document File',
          required=True,
          attachment=True
      )

      file_name = fields.Char(string='File Name')

      document_date = fields.Date(
          string='Document Date',
          default=fields.Date.today
      )

      expiry_date = fields.Date(string='Expiry Date')

      is_expired = fields.Boolean(
          string='Is Expired',
          compute='_compute_is_expired',
          store=True
      )

      days_to_expiry = fields.Integer(
          string='Days to Expiry',
          compute='_compute_days_to_expiry'
      )

      verification_status = fields.Selection([
          ('pending', 'Pending Verification'),
          ('verified', 'Verified'),
          ('rejected', 'Rejected'),
          ('expired', 'Expired'),
      ], string='Verification Status', default='pending', tracking=True)

      verified_by = fields.Many2one(
          'res.users',
          string='Verified By',
          readonly=True
      )

      verification_date = fields.Date(
          string='Verification Date',
          readonly=True
      )

      verification_notes = fields.Text(string='Verification Notes')

      is_mandatory = fields.Boolean(
          string='Mandatory for Approval',
          default=False
      )

      notes = fields.Text(string='Notes')

      @api.depends('expiry_date')
      def _compute_is_expired(self):
          today = fields.Date.today()
          for record in self:
              record.is_expired = (
                  record.expiry_date and record.expiry_date < today
              )

      @api.depends('expiry_date')
      def _compute_days_to_expiry(self):
          today = fields.Date.today()
          for record in self:
              if record.expiry_date:
                  delta = record.expiry_date - today
                  record.days_to_expiry = delta.days
              else:
                  record.days_to_expiry = 0

      def action_verify(self):
          self.write({
              'verification_status': 'verified',
              'verified_by': self.env.user.id,
              'verification_date': fields.Date.today()
          })

      def action_reject(self):
          self.write({'verification_status': 'rejected'})

      @api.model
      def cron_check_expiring_documents(self):
          """Check for expiring documents and send alerts"""
          expiry_threshold = fields.Date.today() + timedelta(days=30)
          expiring_docs = self.search([
              ('expiry_date', '<=', expiry_threshold),
              ('expiry_date', '>=', fields.Date.today()),
              ('verification_status', '=', 'verified')
          ])

          for doc in expiring_docs:
              # Send expiry notification
              doc._send_expiry_notification()

      def _send_expiry_notification(self):
          # Implementation for expiry notification
          pass
  ```

- Create views and forms
- Setup security and access rights
- Unit tests for models

---

### Day 402: Registration Workflow & Approval System

**[Dev 1] - Registration Views & Forms (8h)**
- Create registration form view:
  ```xml
  <!-- odoo/addons/smart_dairy_b2b/views/b2b_customer_views.xml -->
  <?xml version="1.0" encoding="utf-8"?>
  <odoo>
      <!-- Form View -->
      <record id="view_b2b_customer_form" model="ir.ui.view">
          <field name="name">b2b.customer.form</field>
          <field name="model">b2b.customer</field>
          <field name="arch" type="xml">
              <form string="B2B Customer">
                  <header>
                      <button name="action_submit_for_approval"
                              string="Submit for Approval"
                              type="object"
                              class="btn-primary"
                              attrs="{'invisible': [('approval_status', '!=', 'draft')]}"/>
                      <button name="action_approve"
                              string="Approve"
                              type="object"
                              class="btn-success"
                              groups="smart_dairy_b2b.group_b2b_manager"
                              attrs="{'invisible': [('approval_status', 'not in', ['pending', 'kyc_review', 'credit_review'])]}"/>
                      <button name="action_reject"
                              string="Reject"
                              type="object"
                              class="btn-danger"
                              groups="smart_dairy_b2b.group_b2b_manager"
                              attrs="{'invisible': [('approval_status', 'not in', ['pending', 'kyc_review', 'credit_review'])]}"/>
                      <button name="action_suspend"
                              string="Suspend"
                              type="object"
                              class="btn-warning"
                              groups="smart_dairy_b2b.group_b2b_manager"
                              attrs="{'invisible': [('approval_status', '!=', 'approved')]}"/>
                      <field name="approval_status" widget="statusbar"
                             statusbar_visible="draft,pending,kyc_review,credit_review,approved"/>
                  </header>
                  <sheet>
                      <div class="oe_button_box" name="button_box">
                          <button name="%(action_b2b_delivery_address)d"
                                  type="action"
                                  class="oe_stat_button"
                                  icon="fa-map-marker"
                                  context="{'default_customer_id': id}">
                              <field name="delivery_address_count" widget="statbutton"
                                     string="Delivery Addresses"/>
                          </button>
                          <button name="%(action_b2b_portal_user)d"
                                  type="action"
                                  class="oe_stat_button"
                                  icon="fa-users"
                                  context="{'default_customer_id': id}">
                              <field name="portal_user_count" widget="statbutton"
                                     string="Portal Users"/>
                          </button>
                          <button name="action_view_orders"
                                  type="object"
                                  class="oe_stat_button"
                                  icon="fa-shopping-cart">
                              <field name="total_orders" widget="statbutton" string="Orders"/>
                          </button>
                      </div>

                      <field name="image" widget="image" class="oe_avatar"/>

                      <div class="oe_title">
                          <h1>
                              <field name="company_name" placeholder="Company Name"/>
                          </h1>
                          <h3>
                              <field name="customer_code" readonly="1"/>
                          </h3>
                      </div>

                      <group>
                          <group string="Basic Information">
                              <field name="trade_name"/>
                              <field name="company_type"/>
                              <field name="customer_tier" widget="badge"/>
                              <field name="registration_date"/>
                              <field name="user_id" string="Account Manager"/>
                          </group>
                          <group string="Approval Information">
                              <field name="approval_status" readonly="1"/>
                              <field name="approved_by" attrs="{'invisible': [('approval_status', '!=', 'approved')]}"/>
                              <field name="approval_date" attrs="{'invisible': [('approval_status', '!=', 'approved')]}"/>
                              <field name="is_active" widget="boolean_toggle"/>
                          </group>
                      </group>

                      <notebook>
                          <page string="Business Registration" name="registration">
                              <group>
                                  <group>
                                      <field name="business_registration_number"/>
                                      <field name="tax_identification_number"/>
                                      <field name="vat_number"/>
                                  </group>
                                  <group>
                                      <field name="trade_license_number"/>
                                      <field name="trade_license_expiry"/>
                                  </group>
                              </group>
                          </page>

                          <page string="Contact Information" name="contact">
                              <group>
                                  <group string="Primary Contact">
                                      <field name="primary_contact_name"/>
                                      <field name="primary_contact_designation"/>
                                      <field name="primary_contact_phone"/>
                                      <field name="primary_contact_email"/>
                                  </group>
                                  <group string="Alternate Contact">
                                      <field name="alternate_contact_name"/>
                                      <field name="alternate_contact_phone"/>
                                  </group>
                              </group>

                              <group string="Business Address">
                                  <group>
                                      <field name="street" placeholder="Street..."/>
                                      <field name="street2"/>
                                      <field name="city"/>
                                  </group>
                                  <group>
                                      <field name="state_id"/>
                                      <field name="zip"/>
                                      <field name="country_id"/>
                                  </group>
                              </group>
                          </page>

                          <page string="Business Details" name="business">
                              <group>
                                  <group>
                                      <field name="year_established"/>
                                      <field name="number_of_employees"/>
                                      <field name="annual_turnover"/>
                                      <field name="number_of_outlets"/>
                                  </group>
                                  <group>
                                      <field name="warehouse_capacity"/>
                                      <field name="cold_storage_available"/>
                                  </group>
                              </group>
                              <group>
                                  <field name="business_description" placeholder="Describe your business..."/>
                              </group>
                          </page>

                          <page string="Bank Details" name="bank">
                              <group>
                                  <group>
                                      <field name="bank_name"/>
                                      <field name="bank_account_number"/>
                                      <field name="bank_account_name"/>
                                  </group>
                                  <group>
                                      <field name="bank_branch"/>
                                      <field name="bank_routing_number"/>
                                  </group>
                              </group>
                          </page>

                          <page string="Credit & Payment" name="credit">
                              <group>
                                  <group string="Credit Information">
                                      <field name="credit_limit"/>
                                      <field name="credit_utilized"/>
                                      <field name="credit_available"/>
                                      <field name="credit_on_hold" widget="boolean_toggle"/>
                                  </group>
                                  <group string="Payment Terms">
                                      <field name="payment_terms_id"/>
                                  </group>
                              </group>

                              <group string="Tier Benefits" class="oe_read_only">
                                  <field name="tier_benefits" nolabel="1" widget="text"/>
                              </group>
                          </page>

                          <page string="Documents" name="documents">
                              <field name="document_ids" context="{'default_customer_id': id}">
                                  <tree>
                                      <field name="document_type"/>
                                      <field name="document_name"/>
                                      <field name="document_number"/>
                                      <field name="document_date"/>
                                      <field name="expiry_date"/>
                                      <field name="days_to_expiry"
                                             decoration-danger="days_to_expiry &lt; 30 and days_to_expiry &gt; 0"
                                             decoration-warning="days_to_expiry &lt; 60 and days_to_expiry &gt;= 30"/>
                                      <field name="verification_status" widget="badge"
                                             decoration-success="verification_status == 'verified'"
                                             decoration-danger="verification_status == 'rejected'"
                                             decoration-warning="verification_status == 'pending'"/>
                                  </tree>
                              </field>
                          </page>

                          <page string="Delivery Addresses" name="delivery">
                              <field name="delivery_address_ids" context="{'default_customer_id': id}">
                                  <tree>
                                      <field name="sequence" widget="handle"/>
                                      <field name="name"/>
                                      <field name="address_type"/>
                                      <field name="contact_person"/>
                                      <field name="contact_phone"/>
                                      <field name="city"/>
                                      <field name="is_default" widget="boolean_toggle"/>
                                      <field name="is_active" widget="boolean_toggle"/>
                                  </tree>
                              </field>
                          </page>

                          <page string="Portal Users" name="users">
                              <field name="portal_user_ids" context="{'default_customer_id': id}">
                                  <tree>
                                      <field name="name"/>
                                      <field name="email"/>
                                      <field name="designation"/>
                                      <field name="user_role" widget="badge"/>
                                      <field name="is_primary" widget="boolean_toggle"/>
                                      <field name="is_active" widget="boolean_toggle"/>
                                      <field name="last_login"/>
                                  </tree>
                              </field>
                          </page>

                          <page string="Statistics" name="statistics">
                              <group>
                                  <group string="Order Statistics">
                                      <field name="total_orders"/>
                                      <field name="total_order_value" widget="monetary"/>
                                      <field name="average_order_value" widget="monetary"/>
                                      <field name="last_order_date"/>
                                  </group>
                              </group>
                          </page>
                      </notebook>
                  </sheet>
                  <div class="oe_chatter">
                      <field name="message_follower_ids"/>
                      <field name="activity_ids"/>
                      <field name="message_ids"/>
                  </div>
              </form>
          </field>
      </record>

      <!-- Tree View -->
      <record id="view_b2b_customer_tree" model="ir.ui.view">
          <field name="name">b2b.customer.tree</field>
          <field name="model">b2b.customer</field>
          <field name="arch" type="xml">
              <tree string="B2B Customers"
                    decoration-success="approval_status == 'approved'"
                    decoration-danger="approval_status == 'rejected'"
                    decoration-warning="approval_status in ['pending', 'kyc_review', 'credit_review']"
                    decoration-muted="approval_status == 'suspended'">
                  <field name="customer_code"/>
                  <field name="company_name"/>
                  <field name="company_type"/>
                  <field name="customer_tier" widget="badge"/>
                  <field name="primary_contact_name"/>
                  <field name="primary_contact_phone"/>
                  <field name="city"/>
                  <field name="approval_status" widget="badge"/>
                  <field name="credit_limit" widget="monetary"/>
                  <field name="credit_available" widget="monetary"/>
                  <field name="total_orders"/>
                  <field name="total_order_value" widget="monetary"/>
                  <field name="is_active" widget="boolean_toggle"/>
              </tree>
          </field>
      </record>

      <!-- Search View -->
      <record id="view_b2b_customer_search" model="ir.ui.view">
          <field name="name">b2b.customer.search</field>
          <field name="model">b2b.customer</field>
          <field name="arch" type="xml">
              <search>
                  <field name="company_name"/>
                  <field name="customer_code"/>
                  <field name="primary_contact_name"/>
                  <field name="city"/>
                  <field name="user_id"/>

                  <filter string="Active" name="active"
                          domain="[('is_active', '=', True)]"/>
                  <filter string="Pending Approval" name="pending"
                          domain="[('approval_status', 'in', ['pending', 'kyc_review', 'credit_review'])]"/>
                  <filter string="Approved" name="approved"
                          domain="[('approval_status', '=', 'approved')]"/>
                  <filter string="Credit on Hold" name="credit_hold"
                          domain="[('credit_on_hold', '=', True)]"/>

                  <separator/>
                  <filter string="Bronze Tier" name="bronze"
                          domain="[('customer_tier', '=', 'bronze')]"/>
                  <filter string="Silver Tier" name="silver"
                          domain="[('customer_tier', '=', 'silver')]"/>
                  <filter string="Gold Tier" name="gold"
                          domain="[('customer_tier', '=', 'gold')]"/>
                  <filter string="Platinum Tier" name="platinum"
                          domain="[('customer_tier', '=', 'platinum')]"/>

                  <separator/>
                  <filter string="Retailer" name="retailer"
                          domain="[('company_type', '=', 'retailer')]"/>
                  <filter string="Distributor" name="distributor"
                          domain="[('company_type', '=', 'distributor')]"/>
                  <filter string="Wholesaler" name="wholesaler"
                          domain="[('company_type', '=', 'wholesaler')]"/>

                  <group expand="0" string="Group By">
                      <filter string="Customer Tier" name="group_tier"
                              context="{'group_by': 'customer_tier'}"/>
                      <filter string="Company Type" name="group_type"
                              context="{'group_by': 'company_type'}"/>
                      <filter string="Approval Status" name="group_status"
                              context="{'group_by': 'approval_status'}"/>
                      <filter string="Account Manager" name="group_manager"
                              context="{'group_by': 'user_id'}"/>
                      <filter string="City" name="group_city"
                              context="{'group_by': 'city'}"/>
                  </group>
              </search>
          </field>
      </record>

      <!-- Action -->
      <record id="action_b2b_customer" model="ir.actions.act_window">
          <field name="name">B2B Customers</field>
          <field name="res_model">b2b.customer</field>
          <field name="view_mode">tree,form</field>
          <field name="context">{'search_default_active': 1}</field>
          <field name="help" type="html">
              <p class="o_view_nocontent_smiling_face">
                  Create your first B2B Customer
              </p>
              <p>
                  Register wholesale customers, distributors, and institutional buyers
                  with tier-based access and credit management.
              </p>
          </field>
      </record>

      <!-- Menu -->
      <menuitem id="menu_b2b_root"
                name="B2B Portal"
                sequence="40"/>

      <menuitem id="menu_b2b_customers"
                name="Customers"
                parent="menu_b2b_root"
                action="action_b2b_customer"
                sequence="10"/>
  </odoo>
  ```

**[Dev 2] - Approval Workflow Engine (8h)**
- Create approval workflow:
  ```python
  # odoo/addons/smart_dairy_b2b/models/b2b_approval_workflow.py
  from odoo import models, fields, api
  from odoo.exceptions import ValidationError

  class B2BApprovalWorkflow(models.Model):
      _name = 'b2b.approval.workflow'
      _description = 'B2B Approval Workflow'
      _inherit = ['mail.thread']
      _order = 'create_date desc'

      customer_id = fields.Many2one(
          'b2b.customer',
          string='Customer',
          required=True,
          ondelete='cascade'
      )

      workflow_type = fields.Selection([
          ('registration', 'New Registration'),
          ('tier_upgrade', 'Tier Upgrade'),
          ('credit_increase', 'Credit Limit Increase'),
          ('reactivation', 'Account Reactivation'),
      ], string='Workflow Type', required=True)

      current_stage = fields.Selection([
          ('kyc', 'KYC Verification'),
          ('credit', 'Credit Review'),
          ('final', 'Final Approval'),
          ('completed', 'Completed'),
          ('rejected', 'Rejected'),
      ], string='Current Stage', default='kyc', tracking=True)

      kyc_reviewer_id = fields.Many2one(
          'res.users',
          string='KYC Reviewer'
      )

      kyc_status = fields.Selection([
          ('pending', 'Pending'),
          ('approved', 'Approved'),
          ('rejected', 'Rejected'),
      ], string='KYC Status', default='pending', tracking=True)

      kyc_notes = fields.Text(string='KYC Notes')
      kyc_date = fields.Datetime(string='KYC Review Date')

      credit_reviewer_id = fields.Many2one(
          'res.users',
          string='Credit Reviewer'
      )

      credit_status = fields.Selection([
          ('pending', 'Pending'),
          ('approved', 'Approved'),
          ('rejected', 'Rejected'),
      ], string='Credit Status', default='pending', tracking=True)

      credit_notes = fields.Text(string='Credit Notes')
      credit_date = fields.Datetime(string='Credit Review Date')

      recommended_credit_limit = fields.Float(string='Recommended Credit Limit')
      recommended_tier = fields.Selection([
          ('bronze', 'Bronze'),
          ('silver', 'Silver'),
          ('gold', 'Gold'),
          ('platinum', 'Platinum'),
      ], string='Recommended Tier')

      final_approver_id = fields.Many2one(
          'res.users',
          string='Final Approver'
      )

      final_status = fields.Selection([
          ('pending', 'Pending'),
          ('approved', 'Approved'),
          ('rejected', 'Rejected'),
      ], string='Final Approval Status', default='pending', tracking=True)

      final_notes = fields.Text(string='Final Approval Notes')
      final_date = fields.Datetime(string='Final Approval Date')

      state = fields.Selection([
          ('draft', 'Draft'),
          ('in_progress', 'In Progress'),
          ('completed', 'Completed'),
          ('rejected', 'Rejected'),
      ], string='Workflow State', default='draft', tracking=True)

      def action_start_workflow(self):
          """Start the approval workflow"""
          self.write({
              'state': 'in_progress',
              'current_stage': 'kyc'
          })
          # Assign KYC reviewer automatically
          self._assign_kyc_reviewer()
          # Send notification
          self._notify_kyc_reviewer()

      def action_approve_kyc(self):
          """Approve KYC verification"""
          self.write({
              'kyc_status': 'approved',
              'kyc_reviewer_id': self.env.user.id,
              'kyc_date': fields.Datetime.now(),
              'current_stage': 'credit'
          })
          # Assign credit reviewer
          self._assign_credit_reviewer()
          # Send notification
          self._notify_credit_reviewer()

      def action_reject_kyc(self):
          """Reject KYC verification"""
          self.write({
              'kyc_status': 'rejected',
              'kyc_reviewer_id': self.env.user.id,
              'kyc_date': fields.Datetime.now(),
              'current_stage': 'rejected',
              'state': 'rejected'
          })
          # Update customer status
          self.customer_id.write({'approval_status': 'rejected'})
          # Send rejection notification
          self._send_rejection_notification()

      def action_approve_credit(self):
          """Approve credit review"""
          self.write({
              'credit_status': 'approved',
              'credit_reviewer_id': self.env.user.id,
              'credit_date': fields.Datetime.now(),
              'current_stage': 'final'
          })
          # Assign final approver
          self._assign_final_approver()
          # Send notification
          self._notify_final_approver()

      def action_reject_credit(self):
          """Reject credit review"""
          self.write({
              'credit_status': 'rejected',
              'credit_reviewer_id': self.env.user.id,
              'credit_date': fields.Datetime.now(),
              'current_stage': 'rejected',
              'state': 'rejected'
          })
          # Update customer status
          self.customer_id.write({'approval_status': 'rejected'})
          # Send rejection notification
          self._send_rejection_notification()

      def action_final_approve(self):
          """Final approval"""
          self.write({
              'final_status': 'approved',
              'final_approver_id': self.env.user.id,
              'final_date': fields.Datetime.now(),
              'current_stage': 'completed',
              'state': 'completed'
          })

          # Update customer with approved credit limit and tier
          self.customer_id.write({
              'approval_status': 'approved',
              'credit_limit': self.recommended_credit_limit,
              'customer_tier': self.recommended_tier,
              'approved_by': self.env.user.id,
              'approval_date': fields.Date.today()
          })

          # Create partner if not exists
          self.customer_id._create_partner()
          # Send approval notification
          self._send_approval_notification()

      def action_final_reject(self):
          """Final rejection"""
          self.write({
              'final_status': 'rejected',
              'final_approver_id': self.env.user.id,
              'final_date': fields.Datetime.now(),
              'current_stage': 'rejected',
              'state': 'rejected'
          })
          # Update customer status
          self.customer_id.write({'approval_status': 'rejected'})
          # Send rejection notification
          self._send_rejection_notification()

      def _assign_kyc_reviewer(self):
          """Auto-assign KYC reviewer based on workload"""
          # Find KYC reviewers
          kyc_group = self.env.ref('smart_dairy_b2b.group_b2b_kyc_reviewer')
          reviewers = self.env['res.users'].search([
              ('groups_id', 'in', [kyc_group.id])
          ])

          if reviewers:
              # Find reviewer with least pending reviews
              pending_reviews = {}
              for reviewer in reviewers:
                  count = self.search_count([
                      ('kyc_reviewer_id', '=', reviewer.id),
                      ('kyc_status', '=', 'pending')
                  ])
                  pending_reviews[reviewer.id] = count

              # Assign to reviewer with minimum pending
              min_reviewer_id = min(pending_reviews, key=pending_reviews.get)
              self.kyc_reviewer_id = min_reviewer_id

      def _assign_credit_reviewer(self):
          """Auto-assign credit reviewer"""
          credit_group = self.env.ref('smart_dairy_b2b.group_b2b_credit_reviewer')
          reviewers = self.env['res.users'].search([
              ('groups_id', 'in', [credit_group.id])
          ])

          if reviewers:
              pending_reviews = {}
              for reviewer in reviewers:
                  count = self.search_count([
                      ('credit_reviewer_id', '=', reviewer.id),
                      ('credit_status', '=', 'pending')
                  ])
                  pending_reviews[reviewer.id] = count

              min_reviewer_id = min(pending_reviews, key=pending_reviews.get)
              self.credit_reviewer_id = min_reviewer_id

      def _assign_final_approver(self):
          """Assign final approver (usually manager)"""
          manager_group = self.env.ref('smart_dairy_b2b.group_b2b_manager')
          managers = self.env['res.users'].search([
              ('groups_id', 'in', [manager_group.id])
          ], limit=1)

          if managers:
              self.final_approver_id = managers[0].id

      def _notify_kyc_reviewer(self):
          # Send notification to KYC reviewer
          pass

      def _notify_credit_reviewer(self):
          # Send notification to credit reviewer
          pass

      def _notify_final_approver(self):
          # Send notification to final approver
          pass

      def _send_approval_notification(self):
          # Send approval notification to customer
          pass

      def _send_rejection_notification(self):
          # Send rejection notification to customer
          pass
  ```

**[Dev 3] - KYC Verification System (8h)**
- Create KYC checklist and verification:
  ```python
  # odoo/addons/smart_dairy_b2b/models/b2b_kyc_checklist.py
  from odoo import models, fields, api

  class B2BKYCChecklist(models.Model):
      _name = 'b2b.kyc.checklist'
      _description = 'B2B KYC Checklist'
      _order = 'sequence'

      sequence = fields.Integer(string='Sequence', default=10)

      name = fields.Char(string='Checklist Item', required=True)
      description = fields.Text(string='Description')

      check_type = fields.Selection([
          ('document', 'Document Verification'),
          ('reference', 'Reference Check'),
          ('site_visit', 'Site Visit'),
          ('credit_check', 'Credit Bureau Check'),
          ('background', 'Background Verification'),
      ], string='Check Type', required=True)

      is_mandatory = fields.Boolean(string='Mandatory', default=True)

      is_active = fields.Boolean(string='Active', default=True)

      company_types = fields.Many2many(
          'b2b.company.type',
          string='Applicable for Company Types',
          help='Leave empty for all company types'
      )

  class B2BKYCVerification(models.Model):
      _name = 'b2b.kyc.verification'
      _description = 'B2B KYC Verification'
      _inherit = ['mail.thread']

      customer_id = fields.Many2one(
          'b2b.customer',
          string='Customer',
          required=True,
          ondelete='cascade'
      )

      workflow_id = fields.Many2one(
          'b2b.approval.workflow',
          string='Approval Workflow',
          required=True
      )

      checklist_id = fields.Many2one(
          'b2b.kyc.checklist',
          string='Checklist Item',
          required=True
      )

      status = fields.Selection([
          ('pending', 'Pending'),
          ('in_progress', 'In Progress'),
          ('verified', 'Verified'),
          ('failed', 'Failed'),
          ('not_applicable', 'Not Applicable'),
      ], string='Status', default='pending', tracking=True)

      verified_by = fields.Many2one(
          'res.users',
          string='Verified By'
      )

      verification_date = fields.Datetime(string='Verification Date')

      verification_notes = fields.Text(string='Verification Notes')

      document_ids = fields.Many2many(
          'b2b.customer.document',
          string='Related Documents'
      )

      is_mandatory = fields.Boolean(
          related='checklist_id.is_mandatory',
          string='Mandatory'
      )

      def action_verify(self):
          self.write({
              'status': 'verified',
              'verified_by': self.env.user.id,
              'verification_date': fields.Datetime.now()
          })
          # Check if all mandatory items verified
          self._check_kyc_completion()

      def action_fail(self):
          self.write({
              'status': 'failed',
              'verified_by': self.env.user.id,
              'verification_date': fields.Datetime.now()
          })

      def action_mark_not_applicable(self):
          self.write({
              'status': 'not_applicable',
              'verified_by': self.env.user.id,
              'verification_date': fields.Datetime.now()
          })

      def _check_kyc_completion(self):
          """Check if all mandatory KYC items are completed"""
          all_checks = self.search([
              ('workflow_id', '=', self.workflow_id.id)
          ])

          mandatory_checks = all_checks.filtered(lambda x: x.is_mandatory)
          pending_mandatory = mandatory_checks.filtered(
              lambda x: x.status not in ['verified', 'not_applicable']
          )

          if not pending_mandatory:
              # All mandatory checks completed - auto-approve KYC
              self.workflow_id.action_approve_kyc()
  ```

- Create security groups and rules
- Testing suite

---

### Day 403: Tier Management & Benefits System

**[Dev 1] - Customer Tier Management (8h)**
- Create tier configuration model:
  ```python
  # odoo/addons/smart_dairy_b2b/models/b2b_customer_tier.py
  from odoo import models, fields, api
  from odoo.exceptions import ValidationError

  class B2BCustomerTier(models.Model):
      _name = 'b2b.customer.tier'
      _description = 'B2B Customer Tier Configuration'
      _order = 'sequence'

      sequence = fields.Integer(string='Sequence', default=10)

      name = fields.Char(string='Tier Name', required=True)
      code = fields.Selection([
          ('bronze', 'Bronze'),
          ('silver', 'Silver'),
          ('gold', 'Gold'),
          ('platinum', 'Platinum'),
      ], string='Tier Code', required=True)

      description = fields.Text(string='Description')

      color = fields.Integer(string='Color Index')

      # Tier Requirements
      min_annual_purchase = fields.Float(
          string='Minimum Annual Purchase',
          help='Minimum purchase amount to qualify for this tier'
      )

      min_order_frequency = fields.Integer(
          string='Minimum Order Frequency (per month)',
          help='Minimum number of orders per month'
      )

      min_credit_score = fields.Integer(
          string='Minimum Credit Score',
          help='Minimum credit score required'
      )

      # Tier Benefits
      discount_percentage = fields.Float(
          string='Base Discount %',
          help='Base discount percentage for this tier'
      )

      payment_terms_days = fields.Integer(
          string='Payment Terms (Days)',
          help='Net payment terms in days (e.g., 30 for Net 30)'
      )

      default_credit_limit = fields.Float(
          string='Default Credit Limit',
          help='Default credit limit for new customers in this tier'
      )

      max_credit_limit = fields.Float(
          string='Maximum Credit Limit',
          help='Maximum allowed credit limit for this tier'
      )

      # Service Levels
      delivery_priority = fields.Selection([
          ('standard', 'Standard'),
          ('priority', 'Priority'),
          ('express', 'Express'),
      ], string='Delivery Priority', default='standard')

      dedicated_account_manager = fields.Boolean(
          string='Dedicated Account Manager'
      )

      monthly_business_review = fields.Boolean(
          string='Monthly Business Review'
      )

      quarterly_business_review = fields.Boolean(
          string='Quarterly Business Review'
      )

      custom_product_development = fields.Boolean(
          string='Custom Product Development'
      )

      co_marketing_opportunities = fields.Boolean(
          string='Co-Marketing Opportunities'
      )

      priority_support = fields.Boolean(
          string='24/7 Priority Support'
      )

      # Portal Access
      access_analytics_dashboard = fields.Boolean(
          string='Access to Analytics Dashboard',
          default=True
      )

      access_forecasting_tools = fields.Boolean(
          string='Access to Forecasting Tools'
      )

      access_inventory_visibility = fields.Boolean(
          string='Real-time Inventory Visibility'
      )

      access_price_alerts = fields.Boolean(
          string='Price Change Alerts'
      )

      # Ordering Benefits
      minimum_order_quantity_exempt = fields.Boolean(
          string='MOQ Exempt',
          help='Exempt from minimum order quantity requirements'
      )

      bulk_order_csv_upload = fields.Boolean(
          string='Bulk Order CSV Upload',
          default=True
      )

      saved_order_templates = fields.Boolean(
          string='Saved Order Templates',
          default=True
      )

      auto_reorder_suggestions = fields.Boolean(
          string='Auto Reorder Suggestions'
      )

      # Additional Benefits
      free_shipping_threshold = fields.Float(
          string='Free Shipping Above',
          help='Free shipping for orders above this amount'
      )

      early_access_new_products = fields.Boolean(
          string='Early Access to New Products'
      )

      exclusive_promotions = fields.Boolean(
          string='Exclusive Promotions'
      )

      volume_rebate_eligible = fields.Boolean(
          string='Volume Rebate Eligible'
      )

      # Benefits Summary (formatted)
      benefits_html = fields.Html(
          string='Benefits Summary',
          compute='_compute_benefits_html'
      )

      is_active = fields.Boolean(string='Active', default=True)

      @api.depends('discount_percentage', 'payment_terms_days', 'delivery_priority')
      def _compute_benefits_html(self):
          for record in self:
              benefits = []

              if record.discount_percentage:
                  benefits.append(f"✓ {record.discount_percentage}% base discount on all orders")

              if record.payment_terms_days:
                  benefits.append(f"✓ Net {record.payment_terms_days} payment terms")

              if record.delivery_priority != 'standard':
                  benefits.append(f"✓ {record.delivery_priority.title()} delivery priority")

              if record.default_credit_limit:
                  benefits.append(f"✓ Up to {record.default_credit_limit:,.0f} credit limit")

              if record.dedicated_account_manager:
                  benefits.append("✓ Dedicated account manager")

              if record.monthly_business_review:
                  benefits.append("✓ Monthly business reviews")
              elif record.quarterly_business_review:
                  benefits.append("✓ Quarterly business reviews")

              if record.custom_product_development:
                  benefits.append("✓ Custom product development")

              if record.co_marketing_opportunities:
                  benefits.append("✓ Co-marketing opportunities")

              if record.priority_support:
                  benefits.append("✓ 24/7 priority support")

              if record.access_forecasting_tools:
                  benefits.append("✓ Access to forecasting tools")

              if record.access_inventory_visibility:
                  benefits.append("✓ Real-time inventory visibility")

              if record.minimum_order_quantity_exempt:
                  benefits.append("✓ MOQ exempt")

              if record.auto_reorder_suggestions:
                  benefits.append("✓ Intelligent reorder suggestions")

              if record.free_shipping_threshold:
                  benefits.append(f"✓ Free shipping on orders above {record.free_shipping_threshold:,.0f}")

              if record.early_access_new_products:
                  benefits.append("✓ Early access to new products")

              if record.exclusive_promotions:
                  benefits.append("✓ Exclusive promotions and offers")

              if record.volume_rebate_eligible:
                  benefits.append("✓ Eligible for volume rebates")

              record.benefits_html = '<ul>' + ''.join([f'<li>{b}</li>' for b in benefits]) + '</ul>'

      @api.constrains('discount_percentage')
      def _check_discount(self):
          for record in self:
              if record.discount_percentage < 0 or record.discount_percentage > 100:
                  raise ValidationError('Discount percentage must be between 0 and 100')

  class B2BTierUpgrade(models.Model):
      _name = 'b2b.tier.upgrade'
      _description = 'B2B Tier Upgrade Request'
      _inherit = ['mail.thread']
      _order = 'create_date desc'

      customer_id = fields.Many2one(
          'b2b.customer',
          string='Customer',
          required=True,
          ondelete='cascade'
      )

      current_tier = fields.Selection([
          ('bronze', 'Bronze'),
          ('silver', 'Silver'),
          ('gold', 'Gold'),
          ('platinum', 'Platinum'),
      ], string='Current Tier', required=True)

      requested_tier = fields.Selection([
          ('bronze', 'Bronze'),
          ('silver', 'Silver'),
          ('gold', 'Gold'),
          ('platinum', 'Platinum'),
      ], string='Requested Tier', required=True)

      request_date = fields.Date(
          string='Request Date',
          default=fields.Date.today,
          readonly=True
      )

      reason = fields.Text(
          string='Upgrade Reason',
          required=True
      )

      # Qualification Metrics
      annual_purchase_amount = fields.Float(
          string='Annual Purchase Amount',
          compute='_compute_qualification_metrics'
      )

      monthly_order_frequency = fields.Float(
          string='Monthly Order Frequency',
          compute='_compute_qualification_metrics'
      )

      average_order_value = fields.Float(
          string='Average Order Value',
          compute='_compute_qualification_metrics'
      )

      payment_punctuality_score = fields.Float(
          string='Payment Punctuality Score (%)',
          compute='_compute_qualification_metrics'
      )

      # Requirements Check
      meets_purchase_requirement = fields.Boolean(
          string='Meets Purchase Requirement',
          compute='_compute_requirements_check'
      )

      meets_frequency_requirement = fields.Boolean(
          string='Meets Frequency Requirement',
          compute='_compute_requirements_check'
      )

      meets_credit_requirement = fields.Boolean(
          string='Meets Credit Requirement',
          compute='_compute_requirements_check'
      )

      qualifies_for_upgrade = fields.Boolean(
          string='Qualifies for Upgrade',
          compute='_compute_requirements_check'
      )

      # Approval
      status = fields.Selection([
          ('draft', 'Draft'),
          ('submitted', 'Submitted'),
          ('under_review', 'Under Review'),
          ('approved', 'Approved'),
          ('rejected', 'Rejected'),
      ], string='Status', default='draft', tracking=True)

      reviewed_by = fields.Many2one(
          'res.users',
          string='Reviewed By'
      )

      review_date = fields.Date(string='Review Date')
      review_notes = fields.Text(string='Review Notes')

      @api.depends('customer_id')
      def _compute_qualification_metrics(self):
          for record in self:
              # Calculate metrics from past 12 months
              from datetime import datetime, timedelta
              date_from = datetime.now() - timedelta(days=365)

              orders = self.env['sale.order'].search([
                  ('partner_id', '=', record.customer_id.partner_id.id),
                  ('state', 'in', ['sale', 'done']),
                  ('date_order', '>=', date_from)
              ])

              record.annual_purchase_amount = sum(orders.mapped('amount_total'))
              record.monthly_order_frequency = len(orders) / 12 if orders else 0
              record.average_order_value = (
                  record.annual_purchase_amount / len(orders) if orders else 0
              )

              # Calculate payment punctuality
              invoices = self.env['account.move'].search([
                  ('partner_id', '=', record.customer_id.partner_id.id),
                  ('move_type', '=', 'out_invoice'),
                  ('state', '=', 'posted'),
                  ('invoice_date', '>=', date_from)
              ])

              if invoices:
                  on_time_payments = len(invoices.filtered(
                      lambda inv: inv.payment_state == 'paid' and
                      inv.invoice_date_due >= inv.invoice_payment_term_id
                  ))
                  record.payment_punctuality_score = (
                      (on_time_payments / len(invoices)) * 100
                  )
              else:
                  record.payment_punctuality_score = 0

      @api.depends('requested_tier', 'annual_purchase_amount',
                   'monthly_order_frequency', 'payment_punctuality_score')
      def _compute_requirements_check(self):
          for record in self:
              tier_config = self.env['b2b.customer.tier'].search([
                  ('code', '=', record.requested_tier)
              ], limit=1)

              if tier_config:
                  record.meets_purchase_requirement = (
                      record.annual_purchase_amount >= tier_config.min_annual_purchase
                  )
                  record.meets_frequency_requirement = (
                      record.monthly_order_frequency >= tier_config.min_order_frequency
                  )
                  record.meets_credit_requirement = (
                      record.payment_punctuality_score >= tier_config.min_credit_score
                  )

                  record.qualifies_for_upgrade = (
                      record.meets_purchase_requirement and
                      record.meets_frequency_requirement and
                      record.meets_credit_requirement
                  )
              else:
                  record.meets_purchase_requirement = False
                  record.meets_frequency_requirement = False
                  record.meets_credit_requirement = False
                  record.qualifies_for_upgrade = False

      def action_submit(self):
          self.write({'status': 'submitted'})

      def action_approve(self):
          self.write({
              'status': 'approved',
              'reviewed_by': self.env.user.id,
              'review_date': fields.Date.today()
          })

          # Upgrade customer tier
          self.customer_id.write({'customer_tier': self.requested_tier})

          # Create approval workflow for credit limit increase
          tier_config = self.env['b2b.customer.tier'].search([
              ('code', '=', self.requested_tier)
          ], limit=1)

          if tier_config:
              self.customer_id.write({
                  'credit_limit': tier_config.default_credit_limit
              })

      def action_reject(self):
          self.write({
              'status': 'rejected',
              'reviewed_by': self.env.user.id,
              'review_date': fields.Date.today()
          })
  ```

**[Dev 2] - Tier-Based Access Control (8h)**
- Implement tier-based permissions and access control
- Create portal views with tier-specific features
- Testing

**[Dev 3] - Customer Dashboard & Portal (8h)**
- Create B2B customer dashboard
- Portal interface for B2B customers
- Testing

---

### Day 404: Multi-User Portal & Permissions

**[Dev 1] - Portal User Management Interface (8h)**
- Create portal user management views
- User invitation system
- Password reset and security

**[Dev 2] - Role-Based Permissions (8h)**
- Implement granular permissions system
- Order approval workflows for portal users
- Approval limit management

**[Dev 3] - Portal User Dashboard (8h)**
- Create personalized dashboard for portal users
- Activity tracking and audit logs
- Testing

---

### Day 405: Testing & Documentation

**[All Devs] - Comprehensive Testing (8h each)**
- Unit tests for all models
- Integration tests for workflows
- Security testing for portal access
- Documentation and user guides

---

## MILESTONE 2: B2B Product Catalog (Days 406-410)

**Objective**: Create B2B-specific product catalog with bulk pricing, tier-based discounts, and minimum order quantities.

**Duration**: 5 working days

---

### Day 406: Bulk Pricing Engine

**[Dev 1] - Bulk Pricing Rules Model (8h)**
- Create bulk pricing model:
  ```python
  # odoo/addons/smart_dairy_b2b/models/b2b_product_pricelist.py
  from odoo import models, fields, api
  from odoo.exceptions import ValidationError

  class B2BProductPricelist(models.Model):
      _name = 'b2b.product.pricelist'
      _description = 'B2B Product Pricelist'
      _inherit = ['mail.thread']
      _order = 'priority, name'

      name = fields.Char(string='Pricelist Name', required=True)

      priority = fields.Integer(string='Priority', default=10)

      pricelist_type = fields.Selection([
          ('standard', 'Standard B2B'),
          ('tier_based', 'Tier-Based'),
          ('volume_based', 'Volume-Based'),
          ('contract_based', 'Contract-Based'),
          ('customer_specific', 'Customer-Specific'),
      ], string='Pricelist Type', default='standard', required=True)

      # Applicability
      customer_tier_ids = fields.Many2many(
          'b2b.customer.tier',
          string='Applicable Tiers',
          help='Leave empty for all tiers'
      )

      customer_ids = fields.Many2many(
          'b2b.customer',
          string='Specific Customers',
          help='For customer-specific pricing'
      )

      product_category_ids = fields.Many2many(
          'product.category',
          string='Product Categories',
          help='Leave empty for all categories'
      )

      # Validity
      start_date = fields.Date(string='Valid From')
      end_date = fields.Date(string='Valid Until')

      is_active = fields.Boolean(string='Active', default=True)

      # Pricing Rules
      rule_ids = fields.One2many(
          'b2b.pricelist.rule',
          'pricelist_id',
          string='Pricing Rules'
      )

      # Statistics
      rule_count = fields.Integer(
          string='Number of Rules',
          compute='_compute_rule_count'
      )

      @api.depends('rule_ids')
      def _compute_rule_count(self):
          for record in self:
              record.rule_count = len(record.rule_ids)

      def get_price(self, product, quantity, customer):
          """Calculate price for product based on rules"""
          self.ensure_one()

          applicable_rules = self.rule_ids.filtered(
              lambda r: r.is_applicable(product, quantity, customer)
          ).sorted(key=lambda r: r.priority)

          if applicable_rules:
              return applicable_rules[0].calculate_price(product, quantity)

          return product.list_price

  class B2BPricelistRule(models.Model):
      _name = 'b2b.pricelist.rule'
      _description = 'B2B Pricelist Rule'
      _order = 'priority, min_quantity'

      pricelist_id = fields.Many2one(
          'b2b.product.pricelist',
          string='Pricelist',
          required=True,
          ondelete='cascade'
      )

      name = fields.Char(string='Rule Name', required=True)
      priority = fields.Integer(string='Priority', default=10)

      # Product Selection
      product_tmpl_id = fields.Many2one(
          'product.template',
          string='Product Template'
      )

      product_id = fields.Many2one(
          'product.product',
          string='Product Variant'
      )

      product_category_id = fields.Many2one(
          'product.category',
          string='Product Category'
      )

      # Quantity Tiers
      min_quantity = fields.Float(
          string='Minimum Quantity',
          default=1.0,
          required=True
      )

      max_quantity = fields.Float(
          string='Maximum Quantity',
          help='Leave 0 for unlimited'
      )

      # Pricing Method
      pricing_method = fields.Selection([
          ('fixed_price', 'Fixed Price'),
          ('percentage_discount', 'Percentage Discount'),
          ('fixed_discount', 'Fixed Amount Discount'),
          ('formula', 'Price Formula'),
      ], string='Pricing Method', default='percentage_discount', required=True)

      fixed_price = fields.Float(string='Fixed Price')

      discount_percentage = fields.Float(
          string='Discount %',
          help='Discount percentage off the list price'
      )

      discount_amount = fields.Float(
          string='Discount Amount',
          help='Fixed amount discount per unit'
      )

      price_formula = fields.Char(
          string='Price Formula',
          help='e.g., list_price * 0.85 - 5'
      )

      # Unit of Measure
      uom_id = fields.Many2one(
          'uom.uom',
          string='Unit of Measure',
          help='Pricing unit (case, pallet, etc.)'
      )

      # Display
      pricing_display = fields.Char(
          string='Pricing Display',
          compute='_compute_pricing_display'
      )

      is_active = fields.Boolean(string='Active', default=True)

      @api.depends('pricing_method', 'fixed_price', 'discount_percentage',
                   'discount_amount', 'min_quantity', 'max_quantity')
      def _compute_pricing_display(self):
          for record in self:
              qty_range = f"{record.min_quantity:,.0f}"
              if record.max_quantity:
                  qty_range += f" - {record.max_quantity:,.0f}"
              else:
                  qty_range += "+"

              if record.pricing_method == 'fixed_price':
                  record.pricing_display = f"{qty_range} units: ${record.fixed_price:,.2f} each"
              elif record.pricing_method == 'percentage_discount':
                  record.pricing_display = f"{qty_range} units: {record.discount_percentage}% off"
              elif record.pricing_method == 'fixed_discount':
                  record.pricing_display = f"{qty_range} units: ${record.discount_amount:,.2f} off per unit"
              else:
                  record.pricing_display = f"{qty_range} units: Formula pricing"

      def is_applicable(self, product, quantity, customer):
          """Check if this rule applies to the given product/quantity/customer"""
          self.ensure_one()

          # Check product match
          if self.product_id and self.product_id != product:
              return False

          if self.product_tmpl_id and self.product_tmpl_id != product.product_tmpl_id:
              return False

          if self.product_category_id and product.categ_id != self.product_category_id:
              return False

          # Check quantity range
          if quantity < self.min_quantity:
              return False

          if self.max_quantity and quantity > self.max_quantity:
              return False

          return True

      def calculate_price(self, product, quantity):
          """Calculate price based on this rule"""
          self.ensure_one()

          base_price = product.list_price

          if self.pricing_method == 'fixed_price':
              return self.fixed_price

          elif self.pricing_method == 'percentage_discount':
              return base_price * (1 - (self.discount_percentage / 100))

          elif self.pricing_method == 'fixed_discount':
              return max(0, base_price - self.discount_amount)

          elif self.pricing_method == 'formula':
              try:
                  # Evaluate formula (simplified - add more safety in production)
                  return eval(self.price_formula, {
                      'list_price': base_price,
                      'quantity': quantity
                  })
              except:
                  return base_price

          return base_price

      @api.constrains('min_quantity', 'max_quantity')
      def _check_quantities(self):
          for record in self:
              if record.min_quantity < 0:
                  raise ValidationError('Minimum quantity cannot be negative')

              if record.max_quantity and record.max_quantity < record.min_quantity:
                  raise ValidationError('Maximum quantity must be greater than minimum quantity')

      @api.constrains('discount_percentage')
      def _check_discount_percentage(self):
          for record in self:
              if record.discount_percentage < 0 or record.discount_percentage > 100:
                  raise ValidationError('Discount percentage must be between 0 and 100')
  ```

**[Dev 2] - Tier-Based Discount System (8h)**
- Implement automatic tier discounts
- Customer tier-based pricing calculator
- Discount stacking rules

**[Dev 3] - Case/Pallet Pricing (8h)**
- Multi-unit pricing (case, pallet, bulk)
- Unit conversion and pricing
- Testing

---

### Day 407: Minimum Order Quantity (MOQ)

**[Dev 1] - MOQ Configuration (8h)**
- MOQ per product/category
- MOQ enforcement in cart
- MOQ exemptions for premium tiers

**[Dev 2] - MOQ Validation Engine (8h)**
- Cart validation for MOQ
- Mixed product MOQ handling
- Warning and error messages

**[Dev 3] - MOQ Dashboard & Reports (8h)**
- MOQ compliance reporting
- MOQ optimization suggestions
- Testing

---

### Day 408: B2B Product Catalog UI

**[Dev 1] - B2B Catalog Views (8h)**
- Tier-specific product visibility
- Bulk product browsing
- Quick add to cart

**[Dev 2] - Pricing Display (8h)**
- Tiered pricing display
- Volume discount calculator
- Price per unit vs case

**[Dev 3] - Product Filters & Search (8h)**
- B2B-specific filters
- Bulk order quick search
- Testing

---

### Day 409: Personalized Catalogs

**[Dev 1] - Custom Catalog Builder (8h)**
- Customer-specific product catalogs
- Catalog versioning
- Catalog sharing

**[Dev 2] - Product Recommendations (8h)**
- AI-based product recommendations
- Frequently bought together
- Reorder suggestions

**[Dev 3] - Catalog Analytics (8h)**
- Catalog performance metrics
- Product view tracking
- Testing

---

### Day 410: Testing & Documentation

**[All Devs] - Comprehensive Testing (8h each)**
- Pricing calculation tests
- MOQ validation tests
- Tier-based access tests
- Performance testing
- Documentation

---

## MILESTONE 3: Request for Quotation (Days 411-415)

**Objective**: Implement complete RFQ workflow with supplier bidding, quote comparison, and conversion to orders.

**Duration**: 5 working days

---

### Day 411: RFQ Core System

**[Dev 1] - RFQ Model (8h)**
- Create RFQ data model
- RFQ line items
- RFQ templates

**[Dev 2] - RFQ Workflow Engine (8h)**
- RFQ creation workflow
- RFQ approval process
- RFQ expiration handling

**[Dev 3] - RFQ Forms & Views (8h)**
- RFQ creation interface
- RFQ management dashboard
- Testing

---

### Day 412: Supplier Bidding System

**[Dev 1] - Bidding Model (8h)**
- Supplier bid submission
- Multi-supplier bidding
- Bid versioning

**[Dev 2] - Bid Comparison Engine (8h)**
- Side-by-side bid comparison
- Scoring and ranking
- Best value calculator

**[Dev 3] - Bid Evaluation UI (8h)**
- Bid comparison interface
- Bid acceptance workflow
- Testing

---

### Day 413: Quote Management

**[Dev 1] - Quote Generation (8h)**
- Automated quote creation from RFQ
- Quote templates
- Quote versioning

**[Dev 2] - Quote Approval Workflow (8h)**
- Multi-level quote approval
- Quote modification tracking
- Quote expiration

**[Dev 3] - Quote Presentation (8h)**
- Professional quote PDFs
- Quote email delivery
- Testing

---

### Day 414: RFQ to Order Conversion

**[Dev 1] - Conversion Engine (8h)**
- Quote to order conversion
- Partial quote conversion
- Order confirmation workflow

**[Dev 2] - Contract Generation (8h)**
- Auto-generate contracts from quotes
- Contract templates
- Digital signatures

**[Dev 3] - Integration Testing (8h)**
- End-to-end RFQ workflow
- Order integration
- Testing

---

### Day 415: RFQ Analytics & Testing

**[All Devs] - RFQ Analytics & Testing (8h each)**
- RFQ performance analytics
- Supplier performance metrics
- Comprehensive testing
- Documentation

---

## MILESTONE 4: Credit Management (Days 416-420)

**Objective**: Implement comprehensive credit management with limits, terms, aging reports, and collection workflows.

**Duration**: 5 working days

---

### Day 416: Credit Limit Management

**[Dev 1] - Credit Limit Engine (8h)**
- Credit limit configuration
- Dynamic credit limit updates
- Credit utilization tracking

**[Dev 2] - Credit Hold System (8h)**
- Automatic credit holds
- Credit release workflows
- Manual hold management

**[Dev 3] - Credit Monitoring Dashboard (8h)**
- Real-time credit utilization
- Credit alerts
- Testing

---

### Day 417: Payment Terms

**[Dev 1] - Payment Terms Configuration (8h)**
- Net 30/60/90 setup
- Custom payment terms
- Early payment discounts

**[Dev 2] - Terms Enforcement (8h)**
- Payment term validation
- Invoice generation with terms
- Due date calculations

**[Dev 3] - Terms Modification Workflow (8h)**
- Change payment terms
- Customer notifications
- Testing

---

### Day 418: Accounts Receivable Aging

**[Dev 1] - Aging Report Engine (8h)**
- 30/60/90/120+ day aging
- Customer aging analysis
- Aging bucket configuration

**[Dev 2] - Collection Workflows (8h)**
- Automated collection reminders
- Escalation rules
- Collection tracking

**[Dev 3] - Aging Reports & UI (8h)**
- Aging report interface
- Export capabilities
- Testing

---

### Day 419: Credit Analysis & Scoring

**[Dev 1] - Credit Scoring Model (8h)**
- Payment history scoring
- Credit score calculation
- Risk classification

**[Dev 2] - Credit Risk Dashboard (8h)**
- High-risk customer identification
- Risk trends
- Predictive analytics

**[Dev 3] - Credit Review Workflow (8h)**
- Periodic credit reviews
- Credit limit adjustments
- Testing

---

### Day 420: Collection Management & Testing

**[All Devs] - Collection Management & Testing (8h each)**
- Collection management tools
- Payment plan setup
- Write-off procedures
- Comprehensive testing
- Documentation

---

## MILESTONE 5: Bulk Order System (Days 421-425)

**Objective**: Create efficient bulk ordering capabilities with quick order forms, CSV upload, and order templates.

**Duration**: 5 working days

---

### Day 421: Quick Order Form

**[Dev 1] - Quick Order Model (8h)**
- Quick order data structure
- SKU-based ordering
- Quantity validation

**[Dev 2] - Quick Order UI (8h)**
- Multi-line quick order form
- Real-time price calculation
- Stock availability check

**[Dev 3] - Quick Order Processing (8h)**
- Batch order creation
- Error handling
- Testing

---

### Day 422: CSV Bulk Upload

**[Dev 1] - CSV Parser (8h)**
- CSV file validation
- Product mapping
- Error detection

**[Dev 2] - Bulk Upload Processor (8h)**
- Batch processing
- Validation feedback
- Order creation from CSV

**[Dev 3] - Upload UI & Templates (8h)**
- File upload interface
- CSV template generator
- Testing

---

### Day 423: Order Templates

**[Dev 1] - Template Model (8h)**
- Order template structure
- Template versioning
- Template sharing

**[Dev 2] - Template Management (8h)**
- Create/edit templates
- Template from history
- Template application

**[Dev 3] - Template UI (8h)**
- Template library
- One-click ordering
- Testing

---

### Day 424: Reorder from History

**[Dev 1] - Order History Analysis (8h)**
- Purchase pattern detection
- Reorder suggestions
- Frequency analysis

**[Dev 2] - Reorder Engine (8h)**
- One-click reorder
- Quantity adjustment
- Price update handling

**[Dev 3] - Reorder UI (8h)**
- Order history view
- Reorder interface
- Testing

---

### Day 425: Split Shipment & Testing

**[All Devs] - Split Shipment & Testing (8h each)**
- Split shipment configuration
- Partial fulfillment
- Backorder management
- Comprehensive testing
- Documentation

---

## MILESTONE 6: B2B Checkout & Payment (Days 426-430)

**Objective**: Implement B2B-specific checkout with credit invoicing, payment terms, and purchase orders.

**Duration**: 5 working days

---

### Day 426: Credit-Based Checkout

**[Dev 1] - Credit Checkout Engine (8h)**
- Credit availability check
- Credit reservation
- Credit-based order placement

**[Dev 2] - Checkout Workflow (8h)**
- Multi-step B2B checkout
- Order review and confirmation
- Delivery preferences

**[Dev 3] - Checkout UI (8h)**
- B2B checkout interface
- Credit status display
- Testing

---

### Day 427: Purchase Order Integration

**[Dev 1] - PO Model (8h)**
- Purchase order management
- PO validation
- PO-to-order matching

**[Dev 2] - PO Upload & Processing (8h)**
- PO file upload
- PO number validation
- PO tracking

**[Dev 3] - PO Interface (8h)**
- PO entry forms
- PO status tracking
- Testing

---

### Day 428: Invoice Generation

**[Dev 1] - B2B Invoice Engine (8h)**
- Credit invoice generation
- Payment terms on invoice
- Invoice customization

**[Dev 2] - Invoice Delivery (8h)**
- Email invoice delivery
- Invoice portal access
- Invoice reminders

**[Dev 3] - Invoice Management UI (8h)**
- Invoice history
- Invoice download
- Testing

---

### Day 429: Payment Processing

**[Dev 1] - Payment Methods (8h)**
- Wire transfer setup
- ACH/bank transfer
- Check payments

**[Dev 2] - Payment Recording (8h)**
- Payment entry
- Payment matching
- Partial payments

**[Dev 3] - Payment Dashboard (8h)**
- Payment history
- Outstanding invoices
- Testing

---

### Day 430: Statement of Accounts & Testing

**[All Devs] - SOA & Testing (8h each)**
- Statement generation
- Reconciliation tools
- Payment portal
- Comprehensive testing
- Documentation

---

## MILESTONE 7: Contract Management (Days 431-435)

**Objective**: Implement contract management for price agreements, volume commitments, and renewals.

**Duration**: 5 working days

---

### Day 431: Contract Core System

**[Dev 1] - Contract Model (8h)**
- Contract data structure
- Contract terms
- Contract parties

**[Dev 2] - Contract Workflow (8h)**
- Contract creation
- Approval workflow
- Amendment tracking

**[Dev 3] - Contract Forms (8h)**
- Contract entry interface
- Contract templates
- Testing

---

### Day 432: Price Agreements

**[Dev 1] - Price Agreement Engine (8h)**
- Contract-based pricing
- Price lock periods
- Price escalation clauses

**[Dev 2] - Agreement Enforcement (8h)**
- Price override from contracts
- Agreement validation
- Expiration handling

**[Dev 3] - Agreement UI (8h)**
- Agreement management
- Price comparison
- Testing

---

### Day 433: Volume Commitments

**[Dev 1] - Volume Commitment Model (8h)**
- Commitment tracking
- Progress monitoring
- Shortfall penalties

**[Dev 2] - Volume Analytics (8h)**
- Commitment vs actual
- Forecast to commitment
- Volume alerts

**[Dev 3] - Volume Dashboard (8h)**
- Commitment progress
- Visualization
- Testing

---

### Day 434: Contract Renewal

**[Dev 1] - Renewal Engine (8h)**
- Auto-renewal setup
- Renewal notifications
- Contract negotiation tracking

**[Dev 2] - Renewal Workflow (8h)**
- Renewal approval process
- Terms modification
- Contract versioning

**[Dev 3] - Renewal Management (8h)**
- Renewal calendar
- Renewal reminders
- Testing

---

### Day 435: Contract Analytics & Testing

**[All Devs] - Analytics & Testing (8h each)**
- Contract performance metrics
- Revenue under contract
- Compliance tracking
- Comprehensive testing
- Documentation

---

## MILESTONE 8: B2B Analytics (Days 436-440)

**Objective**: Create comprehensive B2B analytics for purchase history, spend analysis, and customer trends.

**Duration**: 5 working days

---

### Day 436: Purchase History Analytics

**[Dev 1] - Purchase History Model (8h)**
- Historical data aggregation
- Purchase patterns
- Product affinity analysis

**[Dev 2] - History Visualization (8h)**
- Purchase timeline
- Product mix charts
- Trend graphs

**[Dev 3] - History Reports (8h)**
- Detailed purchase reports
- Export capabilities
- Testing

---

### Day 437: Spend Analysis

**[Dev 1] - Spend Analytics Engine (8h)**
- Total spend calculation
- Category spend analysis
- Vendor spend distribution

**[Dev 2] - Spend Trends (8h)**
- Month-over-month trends
- Year-over-year comparison
- Spend forecasting

**[Dev 3] - Spend Dashboard (8h)**
- Executive spend dashboard
- Drill-down capabilities
- Testing

---

### Day 438: Customer Behavior Analytics

**[Dev 1] - Behavior Tracking (8h)**
- Order frequency analysis
- Product preference tracking
- Buying cycle identification

**[Dev 2] - Segmentation Engine (8h)**
- RFM analysis (Recency, Frequency, Monetary)
- Customer segmentation
- Churn prediction

**[Dev 3] - Behavior Dashboard (8h)**
- Customer insights
- Recommendations
- Testing

---

### Day 439: Predictive Analytics

**[Dev 1] - Forecast Models (8h)**
- Demand forecasting
- Reorder prediction
- Churn risk scoring

**[Dev 2] - Recommendation Engine (8h)**
- Product recommendations
- Upsell opportunities
- Cross-sell suggestions

**[Dev 3] - Predictive Dashboard (8h)**
- Forecast visualization
- Recommendation interface
- Testing

---

### Day 440: Executive Dashboards & Testing

**[All Devs] - Executive Dashboards & Testing (8h each)**
- Executive B2B dashboard
- KPI tracking
- Custom report builder
- Comprehensive testing
- Documentation

---

## MILESTONE 9: Partner Portal Integration (Days 441-445)

**Objective**: Create partner portal for suppliers, collaborative planning, and forecast sharing.

**Duration**: 5 working days

---

### Day 441: Partner Portal Foundation

**[Dev 1] - Partner Portal Model (8h)**
- Partner registration
- Partner types (supplier, distributor, etc.)
- Partner permissions

**[Dev 2] - Portal Authentication (8h)**
- Partner login system
- SSO integration
- Security setup

**[Dev 3] - Portal Dashboard (8h)**
- Partner dashboard
- Activity feed
- Testing

---

### Day 442: Supplier Portal

**[Dev 1] - PO Management for Suppliers (8h)**
- View purchase orders
- PO acknowledgment
- Delivery scheduling

**[Dev 2] - Invoice Submission (8h)**
- Supplier invoice upload
- Invoice matching
- Payment status

**[Dev 3] - Supplier Performance (8h)**
- Performance metrics
- Scorecards
- Testing

---

### Day 443: Collaborative Planning

**[Dev 1] - Forecast Sharing (8h)**
- Demand forecast sharing
- Forecast collaboration
- Forecast accuracy tracking

**[Dev 2] - Capacity Planning (8h)**
- Supplier capacity input
- Constraint identification
- Planning optimization

**[Dev 3] - Planning Dashboard (8h)**
- Collaborative planning interface
- Communication tools
- Testing

---

### Day 444: Vendor Managed Inventory

**[Dev 1] - VMI Model (8h)**
- Inventory visibility sharing
- Auto-replenishment rules
- Stock level monitoring

**[Dev 2] - VMI Processing (8h)**
- Auto-order generation
- Approval workflows
- VMI analytics

**[Dev 3] - VMI Dashboard (8h)**
- Inventory levels
- Replenishment history
- Testing

---

### Day 445: Partner Analytics & Testing

**[All Devs] - Partner Analytics & Testing (8h each)**
- Partner performance analytics
- Collaboration metrics
- Integration testing
- Documentation

---

## MILESTONE 10: B2B Testing (Days 446-450)

**Objective**: Comprehensive B2B portal testing, workflow validation, and documentation.

**Duration**: 5 working days

---

### Day 446: Functional Testing

**[Dev 1] - Registration & Approval Testing (8h)**
- Test all registration workflows
- Approval process validation
- KYC verification testing

**[Dev 2] - Ordering Workflow Testing (8h)**
- Quick order testing
- CSV upload validation
- Template ordering tests

**[Dev 3] - Credit & Payment Testing (8h)**
- Credit limit enforcement
- Payment term validation
- Invoice generation tests

---

### Day 447: Integration Testing

**[Dev 1] - ERP Integration Testing (8h)**
- Order to ERP flow
- Invoice integration
- Customer master sync

**[Dev 2] - Portal Integration Testing (8h)**
- Portal to backend sync
- Multi-user testing
- Permission validation

**[Dev 3] - External Integration Testing (8h)**
- Payment gateway integration
- Email notifications
- API testing

---

### Day 448: Performance & Security Testing

**[Dev 1] - Performance Testing (8h)**
- Load testing (1000+ concurrent users)
- Large order processing
- Report generation performance

**[Dev 2] - Security Testing (8h)**
- Authentication testing
- Authorization testing
- Data security audit

**[Dev 3] - Stress Testing (8h)**
- Peak load simulation
- Database stress tests
- Failover testing

---

### Day 449: User Acceptance Testing

**[All Devs] - UAT Coordination (8h each)**
- UAT environment setup
- Test case preparation
- User training
- Issue tracking

---

### Day 450: Documentation & Handover

**[All Devs] - Final Documentation (8h each)**
- User manuals
- Admin guides
- API documentation
- Training materials
- Phase sign-off

---

## PHASE SUCCESS CRITERIA

### Functional Criteria
- ✓ B2B customer registration with 3-stage approval workflow
- ✓ Tier-based access control (Bronze, Silver, Gold, Platinum)
- ✓ Bulk pricing engine with volume discounts
- ✓ MOQ enforcement and exemptions
- ✓ RFQ workflow with multi-supplier bidding
- ✓ Credit limit management with automatic holds
- ✓ Payment terms (Net 30/60/90) enforcement
- ✓ Aging reports (30/60/90/120+ days)
- ✓ Quick order, CSV upload, and templates
- ✓ Credit-based checkout with PO integration
- ✓ Contract management with price agreements
- ✓ Volume commitment tracking
- ✓ Purchase history and spend analytics
- ✓ Customer segmentation and RFM analysis
- ✓ Partner portal with supplier collaboration

### Technical Criteria
- ✓ Support for 1000+ concurrent B2B users
- ✓ Large order processing (10,000+ line items)
- ✓ Real-time credit utilization updates
- ✓ Multi-tier pricing calculation < 500ms
- ✓ CSV bulk upload processing (5000+ SKUs)
- ✓ Automated workflow engine for approvals
- ✓ Comprehensive audit logging
- ✓ API for partner integration
- ✓ Mobile-responsive portal

### Business Criteria
- ✓ 50% reduction in order processing time
- ✓ 80% reduction in pricing errors
- ✓ 90% automation of approval workflows
- ✓ 30% increase in average order value
- ✓ 95% customer portal adoption
- ✓ Complete credit risk visibility
- ✓ Contract compliance tracking
- ✓ Predictive reorder suggestions

### Integration Criteria
- ✓ Seamless ERP integration (Odoo)
- ✓ Customer master data sync
- ✓ Order to cash workflow integration
- ✓ Accounting integration for invoicing
- ✓ Payment gateway integration
- ✓ Email notification system
- ✓ Document management integration

---

**END OF PHASE 9 DETAILED IMPLEMENTATION PLAN**
