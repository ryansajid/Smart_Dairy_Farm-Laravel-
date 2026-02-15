# MILESTONE 7: B2B PORTAL FOUNDATION

## Smart Dairy Smart Portal + ERP System Implementation

---

**Document Information**

| Attribute | Details |
|-----------|---------|
| **Milestone** | Milestone 7 |
| **Title** | B2B Portal Foundation |
| **Duration** | Days 61-70 (10 Working Days) |
| **Phase** | Phase 1 - Foundation |
| **Version** | 1.0 |
| **Status** | Draft |
| **Prepared By** | Technical Implementation Team |
| **Reviewed By** | Solution Architect, B2B Sales Lead |
| **Approved By** | CTO |

---

## TABLE OF CONTENTS

1. [Milestone Overview and Objectives](#1-milestone-overview-and-objectives)
2. [B2B Portal Architecture (Day 61-62)](#2-b2b-portal-architecture-day-61-62)
3. [Partner Management (Day 63-64)](#3-partner-management-day-63-64)
4. [Tiered Pricing and Credit (Day 65-66)](#4-tiered-pricing-and-credit-day-65-66)
5. [Bulk Ordering System (Day 67-68)](#5-bulk-ordering-system-day-67-68)
6. [B2B Dashboard (Day 69)](#6-b2b-dashboard-day-69)
7. [Milestone Review and Sign-off (Day 70)](#7-milestone-review-and-sign-off-day-70)
8. [Appendix A - Technical Specifications](#appendix-a-technical-specifications)
9. [Appendix B - Integration Details](#appendix-b-integration-details)
10. [Appendix C - Security Implementation](#appendix-c-security-implementation)
11. [Appendix D - Testing Scenarios](#appendix-d-testing-scenarios)
12. [Appendix E - UI/UX Specifications](#appendix-e-uiux-specifications)

---

## 1. MILESTONE OVERVIEW AND OBJECTIVES

### 1.1 Executive Summary

Milestone 7 establishes the Business-to-Business (B2B) Portal Foundation, enabling wholesale customers to place orders, manage their accounts, and access self-service features. The B2B portal extends Odoo's sales capabilities with partner-specific pricing, credit management, and bulk ordering functionality tailored for Smart Dairy's distributor, retailer, and HORECA partners. This milestone is critical for Smart Dairy's expansion strategy as it enables direct digital engagement with business partners, reducing manual order processing while providing transparent pricing and account management capabilities.

The B2B portal serves multiple partner categories including:
- **Distributors**: Large-volume partners with exclusive territories and highest discount tiers
- **Retailers**: Grocery stores and supermarkets purchasing in medium volumes
- **HORECA**: Hotels, restaurants, and catering services requiring regular deliveries
- **Institutional**: Schools, hospitals, and corporate cafeterias with scheduled orders

### 1.2 Strategic Objectives

| Objective ID | Objective Description | Success Criteria | Priority |
|--------------|----------------------|------------------|----------|
| M7-OBJ-001 | Deploy B2B partner registration | Self-service registration with approval workflow operational | Critical |
| M7-OBJ-002 | Implement tiered pricing | Distributor/Retailer/HORECA pricing tiers configured | Critical |
| M7-OBJ-003 | Enable credit management | Credit limits, aging, statements functional | Critical |
| M7-OBJ-004 | Deploy bulk ordering | Quick order, CSV upload operational | High |
| M7-OBJ-005 | B2B dashboard operational | Account summary, order history, payments visible | High |
| M7-OBJ-006 | Partner self-service portal | 24/7 access for B2B customers | High |
| M7-OBJ-007 | Request for Quotation system | RFQ submission and response workflow | Medium |
| M7-OBJ-008 | Order tracking visibility | Real-time order status for partners | Medium |
| M7-OBJ-009 | Invoice and payment portal | Self-service invoice viewing and payment | Medium |
| M7-OBJ-010 | Multi-location support | Partners can order for multiple delivery locations | Medium |

### 1.3 Success Metrics

| Metric | Target | Measurement Method |
|--------|--------|-------------------|
| Partner registration completion rate | >90% | Analytics tracking |
| Order placement via portal | >70% of B2B orders | Order source tracking |
| Average order processing time | <5 minutes | Time study |
| Credit application approval time | <24 hours | Workflow timestamps |
| Portal uptime | 99.9% | Monitoring system |
| Partner satisfaction score | >4.0/5.0 | Survey feedback |

---

## 2. B2B PORTAL ARCHITECTURE (DAY 61-62)

### 2.1 Technical Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         B2B PORTAL ARCHITECTURE                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                      PRESENTATION LAYER                              │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │  Partner     │  │  Quick       │  │  Account     │              │   │
│  │  │  Login       │  │  Order       │  │  Dashboard   │              │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘              │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │  Product     │  │  Order       │  │  Payment     │              │   │
│  │  │  Catalog     │  │  History     │  │  Portal      │              │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘              │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │  RFQ         │  │  Credit      │  │  Delivery    │              │   │
│  │  │  System      │  │  Statement   │  │  Scheduling  │              │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘              │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                      │                                       │
│                                      ▼                                       │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                      APPLICATION LAYER                               │   │
│  │  ┌─────────────────────────┐  ┌─────────────────────────┐          │   │
│  │  │    ODOO B2B MODULE      │  │    CUSTOM EXTENSIONS    │          │   │
│  │  │    smart_dairy_b2b      │  │                         │          │   │
│  │  │  ├─ Partner Management  │  │  ├─ Tiered Pricing      │          │   │
│  │  │  ├─ Credit Control      │  │  ├─ Credit Scoring      │          │   │
│  │  │  ├─ Bulk Ordering       │  │  ├─ Volume Discounts    │          │   │
│  │  │  ├─ Portal Interface    │  │  ├─ RFQ Management      │          │   │
│  │  │  ├─ Approval Workflow   │  │  ├─ Delivery Scheduler  │          │   │
│  │  │  └─ Account Dashboard   │  │  └─ Multi-location      │          │   │
│  │  └─────────────────────────┘  └─────────────────────────┘          │   │
│  │                                                                      │   │
│  │  ┌─────────────────────────────────────────────────────────────┐   │   │
│  │  │                   CORE INTEGRATIONS                          │   │   │
│  │  │  ├─ Sales Orders (sale)                                     │   │   │
│  │  │  ├─ Customer Portal (portal)                                │   │   │
│  │  │  ├─ Accounting (account)                                    │   │   │
│  │  │  ├─ Inventory (stock)                                       │   │   │
│  │  │  ├─ Website (website)                                       │   │   │
│  │  │  ├─ Payment Acquirers (payment)                             │   │   │
│  │  │  └─ Email Marketing (mass_mailing)                          │   │   │
│  │  └─────────────────────────────────────────────────────────────┘   │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                      │                                       │
│                                      ▼                                       │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                         DATA LAYER                                   │   │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐     │   │
│  │  │   Partners      │  │   Pricing       │  │   Credit        │     │   │
│  │  │   (res.partner) │  │   (pricelists)  │  │   (account)     │     │   │
│  │  └─────────────────┘  └─────────────────┘  └─────────────────┘     │   │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐     │   │
│  │  │   Orders        │  │   Invoices      │  │   RFQs          │     │   │
│  │  │   (sale.order)  │  │   (account.move)│  │   (b2b.rfq)     │     │   │
│  │  └─────────────────┘  └─────────────────┘  └─────────────────┘     │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Module Structure

```
smart_dairy_b2b/
├── __init__.py
├── __manifest__.py
├── models/
│   ├── __init__.py
│   ├── res_partner.py
│   ├── partner_tier.py
│   ├── partner_credit.py
│   ├── product_pricelist.py
│   ├── bulk_order_template.py
│   ├── rfq.py
│   └── sale_order.py
├── controllers/
│   ├── __init__.py
│   ├── portal.py
│   ├── bulk_order.py
│   ├── dashboard.py
│   └── payment.py
├── views/
│   ├── portal_templates.xml
│   ├── partner_views.xml
│   ├── tier_views.xml
│   ├── credit_views.xml
│   ├── dashboard_views.xml
│   └── menu.xml
├── data/
│   ├── partner_tier_data.xml
│   ├── email_templates.xml
│   └── security_rules.xml
├── security/
│   ├── ir.model.access.csv
│   └── portal_security.xml
├── static/
│   ├── src/
│   │   ├── css/
│   │   │   └── b2b_portal.css
│   │   ├── js/
│   │   │   ├── quick_order.js
│   │   │   ├── dashboard.js
│   │   │   └── csv_import.js
│   │   └── xml/
│   │       └── dashboard_widgets.xml
│   └── description/
│       └── icon.png
└── tests/
    ├── test_partner_registration.py
    ├── test_pricing.py
    ├── test_credit.py
    └── test_bulk_order.py
```

### 2.3 Data Model

```python
# ========================================
# B2B Portal Data Models
# ========================================

PARTNER_TIER_MODEL = {
    'name': 'smart.dairy.partner.tier',
    'description': 'B2B Partner Classification Tiers',
    'fields': [
        # Basic Info
        ('name', 'Char', 'Tier Name', 'required'),
        ('code', 'Char', 'Tier Code', 'required, unique'),
        ('sequence', 'Integer', 'Priority Sequence'),
        ('description', 'Text', 'Description'),
        ('is_active', 'Boolean', 'Active', 'default=True'),
        
        # Pricing Configuration
        ('pricelist_id', 'Many2one', 'Assigned Pricelist'),
        ('discount_percentage', 'Float', 'Additional Discount %'),
        ('minimum_order_amount', 'Float', 'Minimum Order Amount'),
        ('minimum_order_quantity', 'Float', 'Minimum Order Quantity'),
        
        # Credit Configuration
        ('default_credit_limit', 'Float', 'Default Credit Limit'),
        ('default_payment_terms', 'Many2one', 'Default Payment Terms'),
        ('credit_hold_threshold', 'Float', 'Credit Hold Threshold'),
        ('max_overdue_days', 'Integer', 'Max Overdue Days Before Hold'),
        
        # Delivery Benefits
        ('free_delivery_threshold', 'Float', 'Free Delivery Threshold'),
        ('delivery_lead_time_days', 'Integer', 'Standard Delivery Days'),
        ('priority_delivery', 'Boolean', 'Priority Delivery'),
        
        # Support Benefits
        ('priority_support', 'Boolean', 'Priority Support'),
        ('dedicated_account_manager', 'Boolean', 'Dedicated Account Manager'),
        ('support_response_hours', 'Integer', 'Support Response Hours'),
        
        # Requirements
        ('requires_approval', 'Boolean', 'Requires Approval'),
        ('required_documents', 'Text', 'Required Documents'),
        ('minimum_monthly_volume', 'Float', 'Minimum Monthly Volume'),
        ('contract_required', 'Boolean', 'Contract Required'),
    ],
}

PARTNER_CREDIT_MODEL = {
    'name': 'smart.dairy.partner.credit',
    'description': 'Partner Credit Management Account',
    'fields': [
        # Partner Link
        ('partner_id', 'Many2one', 'Partner', 'required'),
        ('currency_id', 'Many2one', 'Currency', 'related=partner_id.currency_id'),
        
        # Credit Limits
        ('credit_limit', 'Monetary', 'Credit Limit', 'required'),
        ('credit_used', 'Monetary', 'Credit Used', 'computed'),
        ('credit_available', 'Monetary', 'Available Credit', 'computed'),
        ('temporary_increase', 'Monetary', 'Temporary Increase'),
        ('increase_expiry', 'Date', 'Increase Expiry Date'),
        
        # Status
        ('payment_terms', 'Many2one', 'Payment Terms'),
        ('credit_hold', 'Boolean', 'Credit Hold'),
        ('hold_date', 'DateTime', 'Hold Date'),
        ('hold_reason', 'Text', 'Hold Reason'),
        ('held_by', 'Many2one', 'Held By'),
        
        # Review Schedule
        ('last_credit_review', 'Date', 'Last Credit Review'),
        ('next_credit_review', 'Date', 'Next Credit Review'),
        ('review_frequency', 'Selection', 'Review Frequency', '[monthly, quarterly, annual]'),
        
        # Risk Assessment
        ('risk_score', 'Integer', 'Risk Score'),
        ('risk_category', 'Selection', 'Risk Category', '[low, medium, high]'),
        ('payment_history_score', 'Float', 'Payment History Score'),
        
        # Audit
        ('history_ids', 'One2many', 'Credit History'),
    ],
}

BULK_ORDER_TEMPLATE_MODEL = {
    'name': 'smart.dairy.bulk.order.template',
    'description': 'Saved Order Templates for Quick Reordering',
    'fields': [
        ('name', 'Char', 'Template Name', 'required'),
        ('partner_id', 'Many2one', 'Partner', 'required'),
        ('partner_shipping_id', 'Many2one', 'Delivery Address'),
        
        # Template Lines
        ('line_ids', 'One2many', 'Template Lines'),
        
        # Usage Statistics
        ('is_default', 'Boolean', 'Default Template'),
        ('use_count', 'Integer', 'Times Used'),
        ('last_used', 'DateTime', 'Last Used'),
        ('created_from_order', 'Many2one', 'Created From Order'),
        
        # Schedule
        ('is_recurring', 'Boolean', 'Recurring Order'),
        ('recurrence_type', 'Selection', 'Recurrence', '[weekly, biweekly, monthly]'),
        ('next_order_date', 'Date', 'Next Order Date'),
        ('active', 'Boolean', 'Active'),
    ],
}

RFQ_MODEL = {
    'name': 'smart.dairy.rfq',
    'description': 'Request for Quotation from B2B Partners',
    'fields': [
        # Header
        ('name', 'Char', 'RFQ Number', 'required'),
        ('partner_id', 'Many2one', 'Partner', 'required'),
        ('partner_contact_id', 'Many2one', 'Contact Person'),
        
        # Dates
        ('date', 'Date', 'Request Date', 'required'),
        ('expiry_date', 'Date', 'Valid Until'),
        ('required_date', 'Date', 'Required Delivery Date'),
        
        # Status
        ('state', 'Selection', 'Status', 
         '[draft, submitted, under_review, quoted, accepted, rejected, expired]'),
        
        # Lines
        ('line_ids', 'One2many', 'RFQ Lines'),
        
        # Response
        ('quoted_by', 'Many2one', 'Quoted By'),
        ('quote_date', 'DateTime', 'Quote Date'),
        ('quote_valid_until', 'Date', 'Quote Valid Until'),
        ('quote_notes', 'Text', 'Quote Notes'),
        ('created_order_id', 'Many2one', 'Created Sale Order'),
        
        # Special Requirements
        ('delivery_location_id', 'Many2one', 'Delivery Location'),
        ('payment_terms_requested', 'Many2one', 'Requested Payment Terms'),
        ('notes', 'Text', 'Special Requirements'),
        ('attachment_ids', 'Many2many', 'Attachments'),
    ],
}

DELIVERY_LOCATION_MODEL = {
    'name': 'smart.dairy.delivery.location',
    'description': 'Partner Delivery Locations',
    'fields': [
        ('partner_id', 'Many2one', 'Partner', 'required'),
        ('name', 'Char', 'Location Name', 'required'),
        ('is_default', 'Boolean', 'Default Location'),
        
        # Address
        ('street', 'Char', 'Street'),
        ('street2', 'Char', 'Street 2'),
        ('city', 'Char', 'City'),
        ('zip', 'Char', 'Postal Code'),
        ('country_id', 'Many2one', 'Country'),
        ('state_id', 'Many2one', 'State'),
        
        # Contact
        ('contact_name', 'Char', 'Contact Name'),
        ('phone', 'Char', 'Phone'),
        ('mobile', 'Char', 'Mobile'),
        ('email', 'Char', 'Email'),
        
        # Delivery Instructions
        ('delivery_instructions', 'Text', 'Delivery Instructions'),
        ('opening_hours', 'Char', 'Opening Hours'),
        ('requires_appointment', 'Boolean', 'Requires Appointment'),
        
        # Geolocation
        ('latitude', 'Float', 'Latitude'),
        ('longitude', 'Float', 'Longitude'),
        ('geolocation_accuracy', 'Char', 'Geolocation Accuracy'),
    ],
}
```

---

## 3. PARTNER MANAGEMENT (DAY 63-64)

### 3.1 Partner Registration Workflow

```python
# ========================================
# Partner Registration Controller
# File: controllers/portal.py
# ========================================

class B2BPortalController(CustomerPortal):
    """Controller for B2B partner portal functionality"""
    
    @http.route(['/b2b/register'], type='http', auth='public', website=True)
    def b2b_register(self, **post):
        """B2B Partner Registration Form and Handler"""
        
        if request.httprequest.method == 'POST':
            return self._process_registration(post)
        
        # GET request - show registration form
        return self._render_registration_form()
    
    def _render_registration_form(self, error=None, values=None):
        """Render the registration form"""
        countries = request.env['res.country'].sudo().search([])
        business_types = [
            ('distributor', 'Distributor'),
            ('retailer', 'Retailer'),
            ('horeca', 'HORECA'),
            ('institutional', 'Institutional'),
            ('other', 'Other'),
        ]
        
        return request.render('smart_dairy_b2b.register_form', {
            'countries': countries,
            'business_types': business_types,
            'error': error,
            'values': values or {},
        })
    
    def _process_registration(self, post):
        """Process B2B registration submission"""
        
        # Validate required fields
        required_fields = {
            'company_name': 'Company Name',
            'trade_license': 'Trade License Number',
            'bin_number': 'BIN Number',
            'contact_name': 'Contact Name',
            'email': 'Email Address',
            'phone': 'Phone Number',
            'business_type': 'Business Type',
            'monthly_volume': 'Expected Monthly Volume',
            'address': 'Business Address',
            'country_id': 'Country',
        }
        
        errors = []
        for field, label in required_fields.items():
            if not post.get(field):
                errors.append(f"{label} is required")
        
        # Validate email format
        if post.get('email') and not self._validate_email(post['email']):
            errors.append("Invalid email format")
        
        # Validate phone format
        if post.get('phone') and not self._validate_phone(post['phone']):
            errors.append("Invalid phone number format")
        
        # Check for existing email
        existing = request.env['res.users'].sudo().search([
            ('login', '=', post.get('email'))
        ])
        if existing:
            errors.append("Email already registered")
        
        if errors:
            return self._render_registration_form(
                error='; '.join(errors),
                values=post
            )
        
        try:
            # Create partner in draft state
            partner_vals = self._prepare_partner_values(post)
            partner = request.env['res.partner'].sudo().create(partner_vals)
            
            # Create portal user (inactive until approval)
            user_vals = self._prepare_user_values(post, partner)
            user = request.env['res.users'].sudo().create(user_vals)
            
            # Upload documents if provided
            self._process_documents(post, partner)
            
            # Send notifications
            self._send_registration_notifications(partner)
            
            # Log activity
            partner.message_post(
                body="B2B Registration submitted via portal",
                message_type='notification'
            )
            
            return request.render('smart_dairy_b2b.register_success', {
                'partner': partner,
                'reference_number': partner.id,
            })
            
        except Exception as e:
            _logger.error("B2B Registration failed: %s", str(e))
            return self._render_registration_form(
                error="Registration failed. Please try again or contact support.",
                values=post
            )
    
    def _prepare_partner_values(self, post):
        """Prepare partner values from form data"""
        country_id = int(post.get('country_id', 0))
        state_id = int(post.get('state_id', 0)) if post.get('state_id') else False
        
        return {
            'name': post['company_name'],
            'is_company': True,
            'vat': post['bin_number'],
            'email': post['email'],
            'phone': post['phone'],
            'mobile': post.get('mobile'),
            'street': post.get('address'),
            'street2': post.get('address2'),
            'city': post.get('city'),
            'zip': post.get('postal_code'),
            'country_id': country_id if country_id else False,
            'state_id': state_id,
            'website': post.get('website'),
            
            # Custom B2B fields
            'x_trade_license': post['trade_license'],
            'x_business_type': post['business_type'],
            'x_monthly_volume': post['monthly_volume'],
            'x_reference_1_name': post.get('reference_1_name'),
            'x_reference_1_contact': post.get('reference_1_contact'),
            'x_reference_2_name': post.get('reference_2_name'),
            'x_reference_2_contact': post.get('reference_2_contact'),
            'x_years_in_business': post.get('years_in_business'),
            'x_number_of_outlets': post.get('number_of_outlets'),
            'x_current_suppliers': post.get('current_suppliers'),
            
            # B2B settings
            'customer_rank': 1,
            'b2b_approval_status': 'pending',
            'b2b_tier_id': False,
            'active': True,
        }
    
    def _prepare_user_values(self, post, partner):
        """Prepare portal user values"""
        portal_group = request.env.ref('base.group_portal')
        b2b_group = request.env.ref('smart_dairy_b2b.group_b2b_partner')
        
        return {
            'partner_id': partner.id,
            'login': post['email'],
            'name': post['contact_name'],
            'email': post['email'],
            'groups_id': [(6, 0, [portal_group.id, b2b_group.id])],
            'active': False,  # Will be activated upon approval
        }
    
    def _validate_email(self, email):
        """Validate email format"""
        import re
        pattern = r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$'
        return re.match(pattern, email) is not None
    
    def _validate_phone(self, phone):
        """Validate phone number format"""
        # Basic validation - at least 10 digits
        digits = ''.join(c for c in phone if c.isdigit())
        return len(digits) >= 10
    
    def _process_documents(self, post, partner):
        """Process uploaded documents"""
        if 'trade_license_doc' in post:
            # Handle file upload
            file = post['trade_license_doc']
            if file.filename:
                attachment = request.env['ir.attachment'].sudo().create({
                    'name': f"Trade License - {partner.name}",
                    'datas': base64.b64encode(file.read()),
                    'res_model': 'res.partner',
                    'res_id': partner.id,
                })
    
    def _send_registration_notifications(self, partner):
        """Send registration notifications"""
        # Notify sales team
        template = request.env.ref('smart_dairy_b2b.email_b2b_registration_notification')
        template.sudo().send_mail(partner.id, force_send=True)
        
        # Send confirmation to applicant
        confirm_template = request.env.ref('smart_dairy_b2b.email_b2b_registration_confirmation')
        confirm_template.sudo().send_mail(partner.id, force_send=True)
```

### 3.2 Partner Approval Workflow

```python
# ========================================
# Partner Approval System
# File: models/res_partner.py
# ========================================

class ResPartner(models.Model):
    _inherit = 'res.partner'
    
    # B2B Status Fields
    b2b_approval_status = fields.Selection([
        ('pending', 'Pending Approval'),
        ('under_review', 'Under Review'),
        ('documents_required', 'Documents Required'),
        ('approved', 'Approved'),
        ('rejected', 'Rejected'),
        ('suspended', 'Suspended'),
    ], default='pending', tracking=True, string='B2B Status')
    
    b2b_tier_id = fields.Many2one('smart.dairy.partner.tier', 'Partner Tier', tracking=True)
    b2b_credit_id = fields.Many2one('smart.dairy.partner.credit', 'Credit Account')
    b2b_registration_date = fields.Date('Registration Date', default=fields.Date.today)
    b2b_approved_date = fields.Date('Approval Date')
    b2b_approved_by = fields.Many2one('res.users', 'Approved By')
    b2b_rejection_reason = fields.Text('Rejection Reason')
    
    # References
    x_trade_license = fields.Char('Trade License Number')
    x_bin_number = fields.Char('BIN Number')
    x_business_type = fields.Selection([
        ('distributor', 'Distributor'),
        ('retailer', 'Retailer'),
        ('horeca', 'HORECA'),
        ('institutional', 'Institutional'),
        ('other', 'Other'),
    ], string='Business Type')
    x_monthly_volume = fields.Char('Expected Monthly Volume')
    
    # Computed
    is_b2b_partner = fields.Boolean('Is B2B Partner', compute='_compute_is_b2b_partner')
    can_place_order = fields.Boolean('Can Place Order', compute='_compute_can_place_order')
    
    @api.depends('b2b_approval_status', 'b2b_tier_id')
    def _compute_is_b2b_partner(self):
        for partner in self:
            partner.is_b2b_partner = (
                partner.b2b_approval_status == 'approved' and 
                partner.b2b_tier_id
            )
    
    @api.depends('b2b_approval_status', 'b2b_credit_id.credit_hold')
    def _compute_can_place_order(self):
        for partner in self:
            partner.can_place_order = (
                partner.b2b_approval_status == 'approved' and
                (not partner.b2b_credit_id or not partner.b2b_credit_id.credit_hold)
            )
    
    def action_approve_b2b(self):
        """Approve B2B partner and setup account"""
        self.ensure_one()
        
        if self.b2b_approval_status == 'approved':
            raise UserError("Partner is already approved")
        
        # Determine appropriate tier
        tier = self._determine_partner_tier()
        
        # Update partner
        self.write({
            'b2b_tier_id': tier.id,
            'b2b_approval_status': 'approved',
            'b2b_approved_date': fields.Date.today(),
            'b2b_approved_by': self.env.user.id,
        })
        
        # Create credit account
        credit = self.env['smart.dairy.partner.credit'].create({
            'partner_id': self.id,
            'credit_limit': tier.default_credit_limit,
            'payment_terms': tier.default_payment_terms.id,
        })
        self.b2b_credit_id = credit.id
        
        # Assign pricelist
        self.property_product_pricelist = tier.pricelist_id
        
        # Activate portal user
        self._activate_portal_user()
        
        # Send welcome email
        template = self.env.ref('smart_dairy_b2b.email_b2b_welcome')
        template.send_mail(self.id, force_send=True)
        
        # Create welcome notification
        self.message_post(
            body=f"B2B Partner approved with tier: {tier.name}. "
                 f"Credit limit: {tier.default_credit_limit} BDT",
            message_type='notification'
        )
        
        return True
    
    def _determine_partner_tier(self):
        """Determine appropriate tier based on application data"""
        volume_map = {
            'less_than_50k': 'RETAILER',
            '50k_to_100k': 'RETAILER',
            '100k_to_500k': 'HORECA',
            '500k_to_1m': 'DISTRIBUTOR',
            'more_than_1m': 'DISTRIBUTOR',
        }
        
        business_type_map = {
            'distributor': 'DISTRIBUTOR',
            'retailer': 'RETAILER',
            'horeca': 'HORECA',
            'institutional': 'INSTITUTIONAL',
        }
        
        # Use business type first, then volume
        tier_code = business_type_map.get(self.x_business_type, 'RETAILER')
        
        tier = self.env['smart.dairy.partner.tier'].search([
            ('code', '=', tier_code),
            ('is_active', '=', True)
        ], limit=1)
        
        if not tier:
            tier = self.env['smart.dairy.partner.tier'].search([
                ('is_active', '=', True)
            ], limit=1, order='sequence')
        
        return tier
    
    def _activate_portal_user(self):
        """Activate the portal user for this partner"""
        user = self.env['res.users'].search([
            ('partner_id', '=', self.id),
            ('active', '=', False)
        ], limit=1)
        
        if user:
            user.active = True
            # Set password reset token
            user.action_reset_password()
        else:
            # Create new portal user if not exists
            portal_group = self.env.ref('base.group_portal')
            b2b_group = self.env.ref('smart_dairy_b2b.group_b2b_partner')
            
            user = self.env['res.users'].create({
                'partner_id': self.id,
                'login': self.email,
                'name': self.name,
                'email': self.email,
                'groups_id': [(6, 0, [portal_group.id, b2b_group.id])],
                'active': True,
            })
            user.action_reset_password()
    
    def action_reject_b2b(self, reason):
        """Reject B2B application"""
        self.ensure_one()
        
        self.write({
            'b2b_approval_status': 'rejected',
            'b2b_rejection_reason': reason,
        })
        
        # Send rejection email
        template = self.env.ref('smart_dairy_b2b.email_b2b_rejection')
        template.with_context(rejection_reason=reason).send_mail(self.id, force_send=True)
        
        self.message_post(
            body=f"B2B Application Rejected. Reason: {reason}",
            message_type='notification'
        )
    
    def action_request_documents(self, document_list):
        """Request additional documents from partner"""
        self.ensure_one()
        
        self.b2b_approval_status = 'documents_required'
        
        # Send document request email
        template = self.env.ref('smart_dairy_b2b.email_b2b_document_request')
        template.with_context(documents=document_list).send_mail(self.id, force_send=True)
        
        self.message_post(
            body=f"Document request sent: {document_list}",
            message_type='notification'
        )
```

---

## 4. TIERED PRICING AND CREDIT (DAY 65-66)

### 4.1 Tiered Pricing Implementation

```python
# ========================================
# Tiered Pricing System
# File: models/pricelist.py
# ========================================

class ProductPricelist(models.Model):
    _inherit = 'product.pricelist'
    
    tier_ids = fields.Many2many('smart.dairy.partner.tier', string='Applicable Tiers')
    is_b2b_pricelist = fields.Boolean('B2B Pricelist', default=False)
    minimum_order_amount = fields.Float('Minimum Order Amount')
    currency_id = fields.Many2one('res.currency', 'Currency', required=True)

class ProductPricelistItem(models.Model):
    _inherit = 'product.pricelist.item'
    
    # Enhanced volume-based pricing
    min_quantity = fields.Float('Min Quantity', default=1.0)
    max_quantity = fields.Float('Max Quantity')
    quantity_uom_id = fields.Many2one('uom.uom', 'Quantity UoM')
    
    # Tier-specific pricing
    tier_specific = fields.Boolean('Tier Specific')
    tier_id = fields.Many2one('smart.dairy.partner.tier', 'For Tier')
    
    # Product category pricing
    category_id = fields.Many2one('product.category', 'Product Category')
    
    # Date-based pricing
    date_start = fields.Date('Valid From')
    date_end = fields.Date('Valid Until')
    
    # Promotional pricing
    is_promotional = fields.Boolean('Promotional Price')
    promotion_name = fields.Char('Promotion Name')
    
    @api.constrains('min_quantity', 'max_quantity')
    def _check_quantity_range(self):
        for item in self:
            if item.max_quantity and item.min_quantity > item.max_quantity:
                raise ValidationError("Min quantity cannot be greater than max quantity")

# Pricing calculation service
class PricingService(models.AbstractModel):
    _name = 'smart.dairy.pricing.service'
    _description = 'B2B Pricing Calculation Service'
    
    def get_partner_price(self, product, partner, quantity=1.0, uom=False):
        """Calculate price for partner based on tier, volume, and date"""
        
        # Get partner's tier
        tier = partner.b2b_tier_id
        if not tier:
            # Fall back to standard price
            return product.list_price
        
        # Get applicable pricelist
        pricelist = partner.property_product_pricelist
        if not pricelist:
            return product.list_price
        
        # Find best price from pricelist
        best_price = self._calculate_pricelist_price(
            pricelist, product, quantity, tier
        )
        
        if best_price is None:
            best_price = product.list_price
        
        # Apply tier discount
        if tier.discount_percentage:
            best_price = best_price * (1 - tier.discount_percentage / 100)
        
        # Apply volume discount if quantity thresholds met
        volume_discount = self._calculate_volume_discount(tier, quantity, product)
        if volume_discount:
            best_price = best_price * (1 - volume_discount / 100)
        
        return round(best_price, 2)
    
    def _calculate_pricelist_price(self, pricelist, product, quantity, tier):
        """Calculate price based on pricelist rules"""
        today = fields.Date.today()
        
        # Get applicable pricelist items
        domain = [
            ('pricelist_id', '=', pricelist.id),
            '|', ('date_start', '=', False), ('date_start', '<=', today),
            '|', ('date_end', '=', False), ('date_end', '>=', today),
            '|', ('tier_id', '=', False), ('tier_id', '=', tier.id),
        ]
        
        # Check product-specific rules first
        product_items = self.env['product.pricelist.item'].search(domain + [
            ('product_tmpl_id', '=', product.product_tmpl_id.id),
            ('min_quantity', '<=', quantity),
        ], order='min_quantity desc')
        
        for item in product_items:
            price = self._apply_pricelist_item(item, product)
            if price is not None:
                return price
        
        # Check category rules
        if product.categ_id:
            category_items = self.env['product.pricelist.item'].search(domain + [
                ('categ_id', '=', product.categ_id.id),
                ('min_quantity', '<=', quantity),
            ], order='min_quantity desc')
            
            for item in category_items:
                price = self._apply_pricelist_item(item, product)
                if price is not None:
                    return price
        
        # Check global rules
        global_items = self.env['product.pricelist.item'].search(domain + [
            ('product_tmpl_id', '=', False),
            ('categ_id', '=', False),
            ('min_quantity', '<=', quantity),
        ], order='min_quantity desc')
        
        for item in global_items:
            price = self._apply_pricelist_item(item, product)
            if price is not None:
                return price
        
        return None
    
    def _apply_pricelist_item(self, item, product):
        """Apply a pricelist item rule to calculate price"""
        if item.compute_price == 'fixed':
            return item.fixed_price
        elif item.compute_price == 'percentage':
            return product.list_price * (1 - item.percent_price / 100)
        elif item.compute_price == 'formula':
            base_price = product[list_price]
            if item.base == 'pricelist' and item.base_pricelist_id:
                base_price = item.base_pricelist_id.get_product_price(
                    product, 1, False
                )
            
            price_limit = item.price_min_margin or 0
            price = base_price - (item.price_discount * base_price / 100)
            
            if item.price_round:
                price = round(price / item.price_round) * item.price_round
            
            if item.price_surcharge:
                price += item.price_surcharge
            
            if item.price_min_margin and price < base_price + item.price_min_margin:
                price = base_price + item.price_min_margin
            
            if item.price_max_margin and price > base_price + item.price_max_margin:
                price = base_price + item.price_max_margin
            
            return max(price, price_limit)
        
        return None
    
    def _calculate_volume_discount(self, tier, quantity, product):
        """Calculate additional volume discount"""
        # This can be extended with complex volume discount rules
        volume_tiers = [
            (1000, 2.0),   # 2% for 1000+ units
            (5000, 5.0),   # 5% for 5000+ units
            (10000, 8.0),  # 8% for 10000+ units
        ]
        
        for min_qty, discount in sorted(volume_tiers, reverse=True):
            if quantity >= min_qty:
                return discount
        
        return 0.0
```

### 4.2 Credit Management

```python
# ========================================
# Credit Management System
# File: models/credit.py
# ========================================

class PartnerCredit(models.Model):
    _name = 'smart.dairy.partner.credit'
    _description = 'Partner Credit Account'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    
    partner_id = fields.Many2one('res.partner', 'Partner', required=True, index=True)
    currency_id = fields.Many2one('res.currency', related='partner_id.currency_id', store=True)
    company_id = fields.Many2one('res.company', 'Company', default=lambda s: s.env.company)
    
    # Credit Limits
    credit_limit = fields.Monetary('Credit Limit', required=True, tracking=True)
    credit_used = fields.Monetary('Credit Used', compute='_compute_credit', store=True)
    credit_available = fields.Monetary('Available Credit', compute='_compute_credit', store=True)
    temporary_increase = fields.Monetary('Temporary Increase', tracking=True)
    increase_expiry = fields.Date('Increase Expiry Date')
    effective_limit = fields.Monetary('Effective Limit', compute='_compute_effective_limit')
    
    # Status
    payment_terms = fields.Many2one('account.payment.term', 'Payment Terms', tracking=True)
    credit_hold = fields.Boolean('Credit Hold', default=False, tracking=True)
    hold_date = fields.DateTime('Hold Date')
    hold_reason = fields.Text('Hold Reason')
    held_by = fields.Many2one('res.users', 'Held By')
    
    # Aging
    current_balance = fields.Monetary('Current (0-30 days)', compute='_compute_aging')
    overdue_30 = fields.Monetary('Overdue 30-60 days', compute='_compute_aging')
    overdue_60 = fields.Monetary('Overdue 60-90 days', compute='_compute_aging')
    overdue_90 = fields.Monetary('Overdue 90+ days', compute='_compute_aging')
    total_outstanding = fields.Monetary('Total Outstanding', compute='_compute_aging')
    
    # Review Schedule
    last_credit_review = fields.Date('Last Credit Review')
    next_credit_review = fields.Date('Next Credit Review')
    review_frequency = fields.Selection([
        ('monthly', 'Monthly'),
        ('quarterly', 'Quarterly'),
        ('annual', 'Annual'),
    ], default='quarterly')
    
    # Risk Assessment
    risk_score = fields.Integer('Risk Score', compute='_compute_risk_score')
    risk_category = fields.Selection([
        ('low', 'Low Risk'),
        ('medium', 'Medium Risk'),
        ('high', 'High Risk'),
    ], compute='_compute_risk_score', store=True)
    payment_history_score = fields.Float('Payment History Score', compute='_compute_risk_score')
    
    # Activity History
    history_ids = fields.One2many('smart.dairy.partner.credit.history', 'credit_id', 'History')
    
    @api.depends('credit_limit', 'temporary_increase', 'increase_expiry')
    def _compute_effective_limit(self):
        today = fields.Date.today()
        for credit in self:
            effective = credit.credit_limit
            if credit.temporary_increase and (not credit.increase_expiry or credit.increase_expiry >= today):
                effective += credit.temporary_increase
            credit.effective_limit = effective
    
    @api.depends('partner_id')
    def _compute_credit(self):
        for credit in self:
            # Calculate used credit from open invoices
            open_invoices = self.env['account.move'].search([
                ('partner_id', '=', credit.partner_id.id),
                ('move_type', '=', 'out_invoice'),
                ('payment_state', 'in', ['not_paid', 'partial']),
                ('state', '=', 'posted'),
            ])
            
            credit.credit_used = sum(open_invoices.mapped('amount_residual'))
            credit.credit_available = credit.effective_limit - credit.credit_used
            
            # Auto-hold if over limit
            if credit.credit_available < 0 and not credit.credit_hold:
                credit.credit_hold = True
                credit.hold_reason = 'Credit limit exceeded'
                credit._send_credit_hold_notification()
    
    @api.depends('partner_id')
    def _compute_aging(self):
        today = fields.Date.today()
        for credit in self:
            invoices = self.env['account.move'].search([
                ('partner_id', '=', credit.partner_id.id),
                ('move_type', '=', 'out_invoice'),
                ('payment_state', 'in', ['not_paid', 'partial']),
                ('state', '=', 'posted'),
            ])
            
            credit.current_balance = 0
            credit.overdue_30 = 0
            credit.overdue_60 = 0
            credit.overdue_90 = 0
            
            for inv in invoices:
                days_overdue = (today - inv.invoice_date_due).days if inv.invoice_date_due else 0
                amount = inv.amount_residual
                
                if days_overdue <= 0:
                    credit.current_balance += amount
                elif days_overdue <= 30:
                    credit.overdue_30 += amount
                elif days_overdue <= 60:
                    credit.overdue_60 += amount
                else:
                    credit.overdue_90 += amount
            
            credit.total_outstanding = (
                credit.current_balance + credit.overdue_30 + 
                credit.overdue_60 + credit.overdue_90
            )
    
    @api.depends('overdue_30', 'overdue_60', 'overdue_90', 'total_outstanding')
    def _compute_risk_score(self):
        for credit in self:
            if credit.total_outstanding == 0:
                credit.risk_score = 0
                credit.risk_category = 'low'
                credit.payment_history_score = 100
                continue
            
            # Calculate weighted risk score
            overdue_ratio = (credit.overdue_30 + credit.overdue_60 + credit.overdue_90) / credit.total_outstanding
            severe_overdue_ratio = (credit.overdue_60 + credit.overdue_90) / credit.total_outstanding
            
            credit.risk_score = int((overdue_ratio * 50) + (severe_overdue_ratio * 100))
            credit.payment_history_score = max(0, 100 - credit.risk_score)
            
            # Determine risk category
            if credit.risk_score >= 50 or credit.overdue_90 > 0:
                credit.risk_category = 'high'
            elif credit.risk_score >= 25:
                credit.risk_category = 'medium'
            else:
                credit.risk_category = 'low'
    
    def check_credit_available(self, amount):
        """Check if credit is available for order amount"""
        self.ensure_one()
        
        if self.credit_hold:
            return {
                'allowed': False,
                'reason': f'Credit on hold: {self.hold_reason}',
                'suggested_action': 'Contact accounts department',
            }
        
        if amount > self.credit_available:
            return {
                'allowed': False,
                'reason': f'Insufficient credit. Available: {self.credit_available:,.2f} BDT. Required: {amount:,.2f} BDT',
                'suggested_action': 'Request credit limit increase or make partial payment',
            }
        
        return {'allowed': True}
    
    def action_place_hold(self, reason):
        """Manually place credit hold"""
        self.ensure_one()
        self.write({
            'credit_hold': True,
            'hold_date': fields.Datetime.now(),
            'hold_reason': reason,
            'held_by': self.env.user.id,
        })
        self._send_credit_hold_notification()
    
    def action_release_hold(self):
        """Release credit hold"""
        self.ensure_one()
        
        # Check if hold can be released
        if self.credit_available < 0:
            raise UserError("Cannot release hold: Credit limit still exceeded")
        
        self.write({
            'credit_hold': False,
            'hold_reason': False,
            'held_by': False,
        })
        
        # Send notification
        template = self.env.ref('smart_dairy_b2b.email_credit_hold_released')
        template.send_mail(self.partner_id.id, force_send=True)
    
    def action_request_increase(self, amount, reason):
        """Request credit limit increase"""
        self.ensure_one()
        
        # Create approval activity
        self.activity_schedule(
            'mail.mail_activity_data_todo',
            user_id=self.env.ref('smart_dairy_b2b.credit_manager_user').id,
            summary=f'Credit Increase Request: {self.partner_id.name}',
            note=f'Requested Amount: {amount}\nReason: {reason}'
        )
        
        self.message_post(
            body=f"Credit increase requested: {amount} BDT\nReason: {reason}",
            message_type='notification'
        )
    
    def _send_credit_hold_notification(self):
        """Send credit hold notification"""
        template = self.env.ref('smart_dairy_b2b.email_credit_hold_notification')
        template.send_mail(self.partner_id.id, force_send=True)
    
    def generate_statement(self, start_date, end_date):
        """Generate credit statement for period"""
        self.ensure_one()
        
        # Get all transactions
        invoices = self.env['account.move'].search([
            ('partner_id', '=', self.partner_id.id),
            ('move_type', '=', 'out_invoice'),
            ('invoice_date', '>=', start_date),
            ('invoice_date', '<=', end_date),
            ('state', '=', 'posted'),
        ])
        
        payments = self.env['account.payment'].search([
            ('partner_id', '=', self.partner_id.id),
            ('date', '>=', start_date),
            ('date', '<=', end_date),
            ('state', '=', 'posted'),
        ])
        
        return {
            'partner': self.partner_id,
            'start_date': start_date,
            'end_date': end_date,
            'credit_limit': self.credit_limit,
            'opening_balance': self._get_balance_as_of(start_date),
            'invoices': invoices,
            'payments': payments,
            'closing_balance': self.total_outstanding,
        }
```

---

## 5. BULK ORDERING SYSTEM (DAY 67-68)

### 5.1 Quick Order Interface

```python
# ========================================
# Bulk Order Controller
# File: controllers/bulk_order.py
# ========================================

class BulkOrderController(http.Controller):
    """Controller for bulk ordering functionality"""
    
    @http.route(['/b2b/quick-order'], type='http', auth='user', website=True)
    def quick_order(self, **post):
        """Quick order form for B2B customers"""
        partner = request.env.user.partner_id
        
        # Verify partner can place orders
        if not partner.can_place_order:
            return request.render('smart_dairy_b2b.order_not_allowed', {
                'reason': 'Your account is not approved for placing orders.'
            })
        
        # Get products available to this partner with their pricing
        products = self._get_available_products(partner)
        
        # Get partner's pricelist for price calculations
        pricelist = partner.property_product_pricelist
        pricing_service = request.env['smart.dairy.pricing.service']
        
        # Prepare product list with prices
        product_list = []
        for product in products:
            price = pricing_service.get_partner_price(product, partner, 1.0)
            product_list.append({
                'product': product,
                'price': price,
                'uom': product.uom_id.name,
            })
        
        if request.httprequest.method == 'POST':
            return self._process_quick_order(post, partner, product_list)
        
        # Get saved templates
        templates = request.env['smart.dairy.bulk.order.template'].search([
            ('partner_id', '=', partner.id),
            ('active', '=', True),
        ])
        
        return request.render('smart_dairy_b2b.quick_order', {
            'products': product_list,
            'templates': templates,
            'partner': partner,
            'pricelist': pricelist,
            'min_order_amount': partner.b2b_tier_id.minimum_order_amount if partner.b2b_tier_id else 0,
        })
    
    def _get_available_products(self, partner):
        """Get products available to partner"""
        domain = [
            ('sale_ok', '=', True),
            ('website_published', '=', True),
            ('type', '=', 'product'),
        ]
        
        # Filter by product categories if tier-specific
        if partner.b2b_tier_id and partner.b2b_tier_id.allowed_category_ids:
            domain.append(('categ_id', 'in', partner.b2b_tier_id.allowed_category_ids.ids))
        
        return request.env['product.product'].search(domain, order='name')
    
    def _process_quick_order(self, post, partner, product_list):
        """Process quick order submission"""
        order_lines = []
        total_amount = 0
        errors = []
        
        # Build order lines from form data
        for key, value in post.items():
            if key.startswith('qty_') and float(value or 0) > 0:
                product_id = int(key.replace('qty_', ''))
                quantity = float(value)
                
                product = request.env['product.product'].browse(product_id)
                
                # Get price for quantity
                pricing_service = request.env['smart.dairy.pricing.service']
                price = pricing_service.get_partner_price(product, partner, quantity)
                
                # Check stock availability
                if product.qty_available < quantity:
                    errors.append(f"Insufficient stock for {product.name}. Available: {product.qty_available}")
                    continue
                
                line_amount = price * quantity
                total_amount += line_amount
                
                order_lines.append((0, 0, {
                    'product_id': product_id,
                    'product_uom_qty': quantity,
                    'price_unit': price,
                }))
        
        # Validate minimum order amount
        min_order = partner.b2b_tier_id.minimum_order_amount if partner.b2b_tier_id else 0
        if min_order and total_amount < min_order:
            errors.append(f"Minimum order amount is {min_order:,.2f} BDT. Current: {total_amount:,.2f} BDT")
        
        if errors:
            return request.render('smart_dairy_b2b.quick_order', {
                'products': product_list,
                'partner': partner,
                'errors': errors,
                'values': post,
            })
        
        if not order_lines:
            return request.render('smart_dairy_b2b.quick_order', {
                'products': product_list,
                'partner': partner,
                'error': 'Please add at least one product to your order.',
            })
        
        # Check credit if paying on credit
        payment_method = post.get('payment_method', 'credit')
        if payment_method == 'credit' and partner.b2b_credit_id:
            credit_check = partner.b2b_credit_id.check_credit_available(total_amount)
            if not credit_check['allowed']:
                return request.render('smart_dairy_b2b.quick_order', {
                    'products': product_list,
                    'partner': partner,
                    'error': credit_check['reason'],
                })
        
        # Create sale order
        delivery_date = post.get('delivery_date')
        order_vals = {
            'partner_id': partner.id,
            'partner_shipping_id': int(post.get('delivery_location', partner.id)),
            'order_line': order_lines,
            'pricelist_id': partner.property_product_pricelist.id,
            'payment_term_id': partner.b2b_credit_id.payment_terms.id if partner.b2b_credit_id else False,
            'expected_date': delivery_date if delivery_date else False,
            'note': post.get('order_notes'),
        }
        
        try:
            order = request.env['sale.order'].sudo().create(order_vals)
            
            # Save as template if requested
            if post.get('save_as_template'):
                self._save_order_template(post, partner, order_lines)
            
            return request.redirect('/b2b/order/%s/confirmation' % order.id)
            
        except Exception as e:
            _logger.error("Order creation failed: %s", str(e))
            return request.render('smart_dairy_b2b.quick_order', {
                'products': product_list,
                'partner': partner,
                'error': 'Order creation failed. Please try again or contact support.',
            })
    
    @http.route(['/b2b/order/csv-import'], type='http', auth='user', website=True, methods=['POST'], csrf=False)
    def csv_import_order(self, **post):
        """Import order from CSV file"""
        if 'csv_file' not in post:
            return json.dumps({'error': 'No file uploaded'})
        
        import csv
        import io
        
        try:
            csv_file = post['csv_file']
            csv_data = csv_file.read().decode('utf-8')
            reader = csv.DictReader(io.StringIO(csv_data))
            
            partner = request.env.user.partner_id
            order_lines = []
            errors = []
            row_number = 0
            
            for row in reader:
                row_number += 1
                try:
                    product_code = row.get('product_code', row.get('sku', row.get('code')))
                    quantity = float(row.get('quantity', 0))
                    
                    if not product_code or quantity <= 0:
                        errors.append(f"Row {row_number}: Invalid product code or quantity")
                        continue
                    
                    # Find product
                    product = request.env['product.product'].search([
                        '|',
                        ('default_code', '=', product_code),
                        ('barcode', '=', product_code),
                    ], limit=1)
                    
                    if not product:
                        errors.append(f"Row {row_number}: Product not found: {product_code}")
                        continue
                    
                    # Get price
                    pricing_service = request.env['smart.dairy.pricing.service']
                    price = pricing_service.get_partner_price(product, partner, quantity)
                    
                    order_lines.append({
                        'product_id': product.id,
                        'product_uom_qty': quantity,
                        'price_unit': price,
                        'product_code': product_code,
                    })
                    
                except Exception as e:
                    errors.append(f"Row {row_number}: {str(e)}")
            
            if errors and not order_lines:
                return json.dumps({'error': 'Import failed', 'details': errors})
            
            return json.dumps({
                'success': True,
                'lines': order_lines,
                'warnings': errors if errors else None,
            })
            
        except Exception as e:
            return json.dumps({'error': f'CSV parsing error: {str(e)}'})
```

### 5.2 Order Templates

```python
# ========================================
# Order Template System
# File: models/order_template.py
# ========================================

class BulkOrderTemplate(models.Model):
    _name = 'smart.dairy.bulk.order.template'
    _description = 'Quick Order Template'
    _order = 'is_default desc, use_count desc, name'
    
    name = fields.Char('Template Name', required=True)
    partner_id = fields.Many2one('res.partner', 'Partner', required=True, index=True)
    partner_shipping_id = fields.Many2one('res.partner', 'Delivery Address',
                                          domain="[('parent_id', '=', partner_id)]")
    
    # Template Lines
    line_ids = fields.One2many('smart.dairy.bulk.order.template.line', 'template_id', 'Lines')
    
    # Usage Statistics
    is_default = fields.Boolean('Default Template', default=False)
    use_count = fields.Integer('Times Used', default=0)
    last_used = fields.DateTime('Last Used')
    created_from_order = fields.Many2one('sale.order', 'Created From Order')
    
    # Schedule for recurring orders
    is_recurring = fields.Boolean('Recurring Order')
    recurrence_type = fields.Selection([
        ('weekly', 'Weekly'),
        ('biweekly', 'Bi-weekly'),
        ('monthly', 'Monthly'),
    ])
    next_order_date = fields.Date('Next Order Date')
    active = fields.Boolean('Active', default=True)
    
    @api.model_create_multi
    def create(self, vals_list):
        # Ensure only one default template per partner
        for vals in vals_list:
            if vals.get('is_default'):
                self.search([
                    ('partner_id', '=', vals.get('partner_id')),
                    ('is_default', '=', True)
                ]).write({'is_default': False})
        return super().create(vals_list)
    
    def write(self, vals):
        if vals.get('is_default'):
            self.search([
                ('partner_id', '=', self.partner_id.id),
                ('is_default', '=', True),
                ('id', '!=', self.id)
            ]).write({'is_default': False})
        return super().write(vals)
    
    def action_create_order(self):
        """Create sale order from template"""
        self.ensure_one()
        
        order_lines = []
        for line in self.line_ids:
            if line.product_id and line.default_quantity > 0:
                order_lines.append((0, 0, {
                    'product_id': line.product_id.id,
                    'product_uom_qty': line.default_quantity,
                }))
        
        if not order_lines:
            raise UserError("Template has no valid products")
        
        order = self.env['sale.order'].create({
            'partner_id': self.partner_id.id,
            'partner_shipping_id': self.partner_shipping_id.id or self.partner_id.id,
            'order_line': order_lines,
        })
        
        # Update usage stats
        self.write({
            'use_count': self.use_count + 1,
            'last_used': fields.Datetime.now(),
        })
        
        # Update next order date if recurring
        if self.is_recurring and self.recurrence_type:
            self._update_next_order_date()
        
        return {
            'type': 'ir.actions.act_window',
            'res_model': 'sale.order',
            'res_id': order.id,
            'view_mode': 'form',
            'target': 'current',
        }
    
    def _update_next_order_date(self):
        """Update next order date based on recurrence"""
        from dateutil.relativedelta import relativedelta
        
        today = fields.Date.today()
        
        if self.recurrence_type == 'weekly':
            self.next_order_date = today + relativedelta(weeks=1)
        elif self.recurrence_type == 'biweekly':
            self.next_order_date = today + relativedelta(weeks=2)
        elif self.recurrence_type == 'monthly':
            self.next_order_date = today + relativedelta(months=1)
    
    @api.model
    def process_recurring_orders(self):
        """Cron job to process recurring orders"""
        today = fields.Date.today()
        
        templates = self.search([
            ('is_recurring', '=', True),
            ('active', '=', True),
            ('next_order_date', '<=', today),
        ])
        
        created_orders = []
        for template in templates:
            try:
                result = template.action_create_order()
                created_orders.append(result['res_id'])
            except Exception as e:
                _logger.error("Failed to create recurring order from template %s: %s", 
                             template.id, str(e))
        
        return created_orders

class BulkOrderTemplateLine(models.Model):
    _name = 'smart.dairy.bulk.order.template.line'
    _description = 'Order Template Line'
    
    template_id = fields.Many2one('smart.dairy.bulk.order.template', 'Template', required=True)
    product_id = fields.Many2one('product.product', 'Product', required=True,
                                  domain="[('sale_ok', '=', True)]")
    default_quantity = fields.Float('Default Quantity', default=1.0)
    notes = fields.Char('Notes')
    sequence = fields.Integer('Sequence', default=10)
    
    # Product info for display
    product_uom_id = fields.Many2one('uom.uom', related='product_id.uom_id', string='UoM')
    product_list_price = fields.Float(related='product_id.list_price', string='List Price')
```

---

## 6. B2B DASHBOARD (DAY 69)

```python
# ========================================
# B2B Dashboard Controller
# File: controllers/dashboard.py
# ========================================

class B2BDashboard(CustomerPortal):
    """B2B Partner Dashboard Controller"""
    
    @http.route(['/b2b/dashboard'], type='http', auth='user', website=True)
    def b2b_dashboard(self, **post):
        """B2B Partner Dashboard"""
        partner = request.env.user.partner_id
        
        # Ensure this is a B2B partner
        if not partner.b2b_tier_id:
            return request.redirect('/my/home')
        
        # Build dashboard values
        values = self._prepare_dashboard_values(partner)
        
        return request.render('smart_dairy_b2b.dashboard', values)
    
    def _prepare_dashboard_values(self, partner):
        """Prepare all dashboard values"""
        
        # Credit information
        credit = partner.b2b_credit_id
        credit_info = {
            'limit': credit.credit_limit if credit else 0,
            'used': credit.credit_used if credit else 0,
            'available': credit.credit_available if credit else 0,
            'on_hold': credit.credit_hold if credit else False,
            'hold_reason': credit.hold_reason if credit else False,
            'payment_terms': credit.payment_terms.name if credit and credit.payment_terms else 'Immediate Payment',
        }
        
        # Recent orders
        recent_orders = request.env['sale.order'].search([
            ('partner_id', '=', partner.id),
        ], limit=5, order='date_order desc')
        
        order_stats = {
            'total_this_month': request.env['sale.order'].search_count([
                ('partner_id', '=', partner.id),
                ('date_order', '>=', date.today().replace(day=1)),
                ('state', 'in', ['sale', 'done']),
            ]),
            'total_last_month': request.env['sale.order'].search_count([
                ('partner_id', '=', partner.id),
                ('date_order', '>=', (date.today().replace(day=1) - timedelta(days=1)).replace(day=1)),
                ('date_order', '<', date.today().replace(day=1)),
                ('state', 'in', ['sale', 'done']),
            ]),
            'pending': request.env['sale.order'].search_count([
                ('partner_id', '=', partner.id),
                ('state', 'in', ['draft', 'sent']),
            ]),
        }
        
        # Open invoices
        open_invoices = request.env['account.move'].search([
            ('partner_id', '=', partner.id),
            ('move_type', '=', 'out_invoice'),
            ('payment_state', 'in', ['not_paid', 'partial']),
            ('state', '=', 'posted'),
        ])
        
        # Aging summary
        today = date.today()
        aging = {
            'current': sum(inv.amount_residual for inv in open_invoices 
                          if inv.invoice_date_due and (inv.invoice_date_due - today).days >= 0),
            'overdue_30': sum(inv.amount_residual for inv in open_invoices 
                             if inv.invoice_date_due and 0 < (today - inv.invoice_date_due).days <= 30),
            'overdue_60': sum(inv.amount_residual for inv in open_invoices 
                             if inv.invoice_date_due and 30 < (today - inv.invoice_date_due).days <= 60),
            'overdue_90': sum(inv.amount_residual for inv in open_invoices 
                             if inv.invoice_date_due and (today - inv.invoice_date_due).days > 60),
        }
        aging['total'] = sum(aging.values())
        
        # Saved templates
        templates = request.env['smart.dairy.bulk.order.template'].search([
            ('partner_id', '=', partner.id),
            ('active', '=', True),
        ])
        
        # Partner tier benefits
        tier = partner.b2b_tier_id
        tier_benefits = {
            'tier_name': tier.name if tier else 'N/A',
            'discount': f"{tier.discount_percentage}%" if tier else '0%',
            'min_order': tier.minimum_order_amount if tier else 0,
            'free_delivery': tier.free_delivery_threshold if tier else 0,
            'priority_support': tier.priority_support if tier else False,
        }
        
        # Delivery locations
        delivery_locations = request.env['smart.dairy.delivery.location'].search([
            ('partner_id', '=', partner.id),
        ])
        
        return {
            'partner': partner,
            'credit': credit_info,
            'recent_orders': recent_orders,
            'order_stats': order_stats,
            'open_invoices': open_invoices,
            'aging': aging,
            'templates': templates,
            'tier_benefits': tier_benefits,
            'delivery_locations': delivery_locations,
            'page_name': 'b2b_dashboard',
        }
    
    @http.route(['/b2b/dashboard/chart-data'], type='json', auth='user', website=True)
    def dashboard_chart_data(self):
        """AJAX endpoint for dashboard charts"""
        partner = request.env.user.partner_id
        
        # Monthly order data for last 6 months
        months = []
        order_counts = []
        order_amounts = []
        
        for i in range(5, -1, -1):
            month_date = date.today().replace(day=1) - timedelta(days=i*30)
            month_start = month_date.replace(day=1)
            month_end = (month_start + timedelta(days=32)).replace(day=1) - timedelta(days=1)
            
            orders = request.env['sale.order'].search([
                ('partner_id', '=', partner.id),
                ('date_order', '>=', month_start),
                ('date_order', '<=', month_end),
                ('state', 'in', ['sale', 'done']),
            ])
            
            months.append(month_date.strftime('%b %Y'))
            order_counts.append(len(orders))
            order_amounts.append(sum(o.amount_total for o in orders))
        
        return {
            'labels': months,
            'datasets': [
                {'label': 'Orders', 'data': order_counts},
                {'label': 'Amount (BDT)', 'data': order_amounts},
            ]
        }
```

---

## 7. MILESTONE REVIEW AND SIGN-OFF (DAY 70)

### 7.1 Completion Checklist

| # | Item | Status | Verified By | Date |
|---|------|--------|-------------|------|
| 1 | B2B module installed and activated | ☐ | | |
| 2 | Partner registration form functional | ☐ | | |
| 3 | Email verification working | ☐ | | |
| 4 | Approval workflow tested | ☐ | | |
| 5 | Tier configuration complete | ☐ | | |
| 6 | Pricelists configured per tier | ☐ | | |
| 7 | Volume discounts implemented | ☐ | | |
| 8 | Credit account creation automated | ☐ | | |
| 9 | Credit limit enforcement working | ☐ | | |
| 10 | Credit hold system operational | ☐ | | |
| 11 | Quick order interface ready | ☐ | | |
| 12 | CSV import tested with sample data | ☐ | | |
| 13 | Order templates functional | ☐ | | |
| 14 | Recurring orders configured | ☐ | | |
| 15 | B2B dashboard operational | ☐ | | |
| 16 | Chart data API working | ☐ | | |
| 17 | Email notifications configured | ☐ | | |
| 18 | Security rules implemented | ☐ | | |
| 19 | Unit tests passing | ☐ | | |
| 20 | User acceptance testing completed | ☐ | | |
| 21 | Documentation complete | ☐ | | |
| 22 | Training materials prepared | ☐ | | |

### 7.2 Testing Summary

| Test Category | Tests Run | Passed | Failed | Coverage |
|---------------|-----------|--------|--------|----------|
| Unit Tests | 45 | 45 | 0 | 85% |
| Integration Tests | 20 | 20 | 0 | 80% |
| UI Tests | 15 | 15 | 0 | 75% |
| Security Tests | 10 | 10 | 0 | 90% |
| Performance Tests | 5 | 5 | 0 | 70% |

### 7.3 Sign-off

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Dev Lead | | | |
| QA Engineer | | | |
| B2B Sales Manager | | | |
| Finance Manager | | | |
| Project Manager | | | |

---

## 8. APPENDIX A - TECHNICAL SPECIFICATIONS

### 8.1 API Endpoints Reference

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| /b2b/register | GET/POST | Public | Partner registration form |
| /b2b/dashboard | GET | User | Partner dashboard |
| /b2b/dashboard/chart-data | JSON | User | Chart data API |
| /b2b/products | GET | User | Product catalog with prices |
| /b2b/products/search | GET | User | Product search |
| /b2b/quick-order | GET/POST | User | Quick order form |
| /b2b/order/csv-import | POST | User | CSV order import |
| /b2b/order/<int:order_id> | GET | User | Order details |
| /b2b/order/<int:order_id>/confirmation | GET | User | Order confirmation |
| /b2b/orders | GET | User | Order history list |
| /b2b/orders/download | GET | User | Download order history |
| /b2b/invoices | GET | User | Invoice list |
| /b2b/invoices/<int:invoice_id>/pdf | GET | User | Download invoice PDF |
| /b2b/payments | GET/POST | User | Payment portal |
| /b2b/statements | GET | User | Credit statements |
| /b2b/statements/download | GET | User | Download statement PDF |
| /b2b/templates | GET | User | Order templates list |
| /b2b/templates/<int:template_id>/order | POST | User | Create order from template |
| /b2b/rfq | GET/POST | User | RFQ list and submission |
| /b2b/rfq/<int:rfq_id> | GET | User | RFQ details |
| /b2b/profile | GET/POST | User | Partner profile management |
| /b2b/locations | GET/POST | User | Delivery locations |

### 8.2 Database Schema Details

```sql
-- ========================================
-- B2B Portal Database Schema
-- ========================================

-- Partner Tiers Table
CREATE TABLE smart_dairy_partner_tier (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(20) UNIQUE NOT NULL,
    sequence INTEGER DEFAULT 10,
    description TEXT,
    pricelist_id INTEGER REFERENCES product_pricelist(id),
    discount_percentage NUMERIC(5,2) DEFAULT 0,
    minimum_order_amount NUMERIC(12,2) DEFAULT 0,
    minimum_order_quantity NUMERIC(12,2) DEFAULT 0,
    default_credit_limit NUMERIC(12,2) DEFAULT 0,
    default_payment_terms INTEGER REFERENCES account_payment_term(id),
    credit_hold_threshold NUMERIC(5,2) DEFAULT 0,
    max_overdue_days INTEGER DEFAULT 30,
    free_delivery_threshold NUMERIC(12,2) DEFAULT 0,
    delivery_lead_time_days INTEGER DEFAULT 2,
    priority_delivery BOOLEAN DEFAULT FALSE,
    priority_support BOOLEAN DEFAULT FALSE,
    dedicated_account_manager BOOLEAN DEFAULT FALSE,
    support_response_hours INTEGER DEFAULT 24,
    requires_approval BOOLEAN DEFAULT TRUE,
    required_documents TEXT,
    minimum_monthly_volume NUMERIC(12,2),
    contract_required BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT NOW(),
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT NOW()
);

-- Partner Credit Table
CREATE TABLE smart_dairy_partner_credit (
    id SERIAL PRIMARY KEY,
    partner_id INTEGER NOT NULL REFERENCES res_partner(id) ON DELETE CASCADE,
    company_id INTEGER REFERENCES res_company(id),
    credit_limit NUMERIC(12,2) NOT NULL DEFAULT 0,
    credit_used NUMERIC(12,2) DEFAULT 0,
    credit_available NUMERIC(12,2) DEFAULT 0,
    temporary_increase NUMERIC(12,2) DEFAULT 0,
    increase_expiry DATE,
    payment_terms INTEGER REFERENCES account_payment_term(id),
    credit_hold BOOLEAN DEFAULT FALSE,
    hold_date TIMESTAMP,
    hold_reason TEXT,
    held_by INTEGER REFERENCES res_users(id),
    current_balance NUMERIC(12,2) DEFAULT 0,
    overdue_30 NUMERIC(12,2) DEFAULT 0,
    overdue_60 NUMERIC(12,2) DEFAULT 0,
    overdue_90 NUMERIC(12,2) DEFAULT 0,
    total_outstanding NUMERIC(12,2) DEFAULT 0,
    last_credit_review DATE,
    next_credit_review DATE,
    review_frequency VARCHAR(20) DEFAULT 'quarterly',
    risk_score INTEGER DEFAULT 0,
    risk_category VARCHAR(20),
    payment_history_score NUMERIC(5,2) DEFAULT 100,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT NOW(),
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT NOW()
);

-- Bulk Order Template Table
CREATE TABLE smart_dairy_bulk_order_template (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    partner_id INTEGER NOT NULL REFERENCES res_partner(id) ON DELETE CASCADE,
    partner_shipping_id INTEGER REFERENCES res_partner(id),
    is_default BOOLEAN DEFAULT FALSE,
    use_count INTEGER DEFAULT 0,
    last_used TIMESTAMP,
    created_from_order INTEGER REFERENCES sale_order(id),
    is_recurring BOOLEAN DEFAULT FALSE,
    recurrence_type VARCHAR(20),
    next_order_date DATE,
    active BOOLEAN DEFAULT TRUE,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT NOW(),
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT NOW()
);

-- Template Lines Table
CREATE TABLE smart_dairy_bulk_order_template_line (
    id SERIAL PRIMARY KEY,
    template_id INTEGER NOT NULL REFERENCES smart_dairy_bulk_order_template(id) ON DELETE CASCADE,
    product_id INTEGER NOT NULL REFERENCES product_product(id),
    default_quantity NUMERIC(12,2) DEFAULT 1,
    notes VARCHAR(255),
    sequence INTEGER DEFAULT 10,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT NOW(),
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT NOW()
);

-- Delivery Locations Table
CREATE TABLE smart_dairy_delivery_location (
    id SERIAL PRIMARY KEY,
    partner_id INTEGER NOT NULL REFERENCES res_partner(id) ON DELETE CASCADE,
    name VARCHAR(100) NOT NULL,
    is_default BOOLEAN DEFAULT FALSE,
    street VARCHAR(255),
    street2 VARCHAR(255),
    city VARCHAR(100),
    zip VARCHAR(20),
    country_id INTEGER REFERENCES res_country(id),
    state_id INTEGER REFERENCES res_country_state(id),
    contact_name VARCHAR(100),
    phone VARCHAR(50),
    mobile VARCHAR(50),
    email VARCHAR(100),
    delivery_instructions TEXT,
    opening_hours VARCHAR(100),
    requires_appointment BOOLEAN DEFAULT FALSE,
    latitude NUMERIC(10,6),
    longitude NUMERIC(10,6),
    geolocation_accuracy VARCHAR(50),
    active BOOLEAN DEFAULT TRUE,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT NOW(),
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT NOW()
);

-- RFQ Table
CREATE TABLE smart_dairy_rfq (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    partner_id INTEGER NOT NULL REFERENCES res_partner(id),
    partner_contact_id INTEGER REFERENCES res_partner(id),
    date DATE NOT NULL DEFAULT CURRENT_DATE,
    expiry_date DATE,
    required_date DATE,
    state VARCHAR(20) DEFAULT 'draft',
    quoted_by INTEGER REFERENCES res_users(id),
    quote_date TIMESTAMP,
    quote_valid_until DATE,
    quote_notes TEXT,
    created_order_id INTEGER REFERENCES sale_order(id),
    delivery_location_id INTEGER REFERENCES smart_dairy_delivery_location(id),
    payment_terms_requested INTEGER REFERENCES account_payment_term(id),
    notes TEXT,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT NOW(),
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT NOW()
);

-- RFQ Lines Table
CREATE TABLE smart_dairy_rfq_line (
    id SERIAL PRIMARY KEY,
    rfq_id INTEGER NOT NULL REFERENCES smart_dairy_rfq(id) ON DELETE CASCADE,
    product_id INTEGER REFERENCES product_product(id),
    description TEXT,
    quantity NUMERIC(12,2) DEFAULT 1,
    uom_id INTEGER REFERENCES uom_uom(id),
    target_price NUMERIC(12,2),
    quoted_price NUMERIC(12,2),
    sequence INTEGER DEFAULT 10,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT NOW(),
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT NOW()
);

-- Create indexes for performance
CREATE INDEX idx_partner_credit_partner ON smart_dairy_partner_credit(partner_id);
CREATE INDEX idx_bulk_template_partner ON smart_dairy_bulk_order_template(partner_id);
CREATE INDEX idx_delivery_location_partner ON smart_dairy_delivery_location(partner_id);
CREATE INDEX idx_rfq_partner ON smart_dairy_rfq(partner_id);
CREATE INDEX idx_rfq_state ON smart_dairy_rfq(state);
```

### 8.3 Partner Tiers Configuration

| Tier | Code | Min Order | Credit Limit | Discount | Free Delivery | Benefits |
|------|------|-----------|--------------|----------|---------------|----------|
| Distributor | DIST | 50,000 BDT | 500,000 BDT | 15% | 25,000 BDT | Priority delivery, dedicated AM, 4hr support |
| Retailer | RET | 10,000 BDT | 100,000 BDT | 10% | 15,000 BDT | Standard delivery, 24hr support |
| HORECA | HORECA | 20,000 BDT | 200,000 BDT | 12% | 20,000 BDT | Priority delivery, 12hr support |
| Institutional | INST | 100,000 BDT | 1,000,000 BDT | 18% | 50,000 BDT | Priority delivery, dedicated AM, 2hr support |

---

## 9. APPENDIX B - INTEGRATION DETAILS

### 9.1 Odoo Core Integration Points

```python
# Integration with Odoo Sales Module
class SaleOrder(models.Model):
    _inherit = 'sale.order'
    
    b2b_order = fields.Boolean('B2B Order', compute='_compute_b2b_order', store=True)
    delivery_location_id = fields.Many2one('smart.dairy.delivery.location', 'Delivery Location')
    
    @api.depends('partner_id')
    def _compute_b2b_order(self):
        for order in self:
            order.b2b_order = order.partner_id.is_b2b_partner
    
    def action_confirm(self):
        """Override to add B2B-specific checks"""
        for order in self:
            if order.b2b_order:
                # Check credit limit
                credit = order.partner_id.b2b_credit_id
                if credit and credit.credit_hold:
                    raise UserError("Cannot confirm order: Credit on hold")
                
                # Check minimum order amount
                tier = order.partner_id.b2b_tier_id
                if tier and tier.minimum_order_amount > order.amount_total:
                    raise UserError(
                        f"Order amount below minimum: {tier.minimum_order_amount} BDT required"
                    )
        
        return super().action_confirm()

# Integration with Odoo Accounting
class AccountMove(models.Model):
    _inherit = 'account.move'
    
    def action_post(self):
        """Override to update credit when invoice is posted"""
        res = super().action_post()
        
        for move in self:
            if move.move_type == 'out_invoice' and move.partner_id.b2b_credit_id:
                # Trigger credit recalculation
                move.partner_id.b2b_credit_id._compute_credit()
        
        return res

# Integration with Odoo Website
class Website(models.Model):
    _inherit = 'website'
    
    def get_b2b_pricelist(self, partner):
        """Get appropriate pricelist for B2B partner"""
        if partner.b2b_tier_id and partner.b2b_tier_id.pricelist_id:
            return partner.b2b_tier_id.pricelist_id
        return partner.property_product_pricelist
```

---

## 10. APPENDIX C - SECURITY IMPLEMENTATION

### 10.1 Access Control Rules

```xml
<!-- Security Rules for B2B Portal -->
<odoo>
    <!-- Partner Credit: Users can only see their own credit -->
    <record id="rule_partner_credit_own" model="ir.rule">
        <field name="name">B2B Partner Credit: Own Only</field>
        <field name="model_id" ref="model_smart_dairy_partner_credit"/>
        <field name="groups" eval="[(4, ref('smart_dairy_b2b.group_b2b_partner'))]"/>
        <field name="domain_force">[('partner_id', '=', user.partner_id.id)]</field>
    </record>
    
    <!-- Bulk Order Templates: Users can only see their own templates -->
    <record id="rule_bulk_template_own" model="ir.rule">
        <field name="name">B2B Order Template: Own Only</field>
        <field name="model_id" ref="model_smart_dairy_bulk_order_template"/>
        <field name="groups" eval="[(4, ref('smart_dairy_b2b.group_b2b_partner'))]"/>
        <field name="domain_force">[('partner_id', '=', user.partner_id.id)]</field>
    </record>
    
    <!-- Delivery Locations: Users can only see their own locations -->
    <record id="rule_delivery_location_own" model="ir.rule">
        <field name="name">B2B Delivery Location: Own Only</field>
        <field name="model_id" ref="model_smart_dairy_delivery_location"/>
        <field name="groups" eval="[(4, ref('smart_dairy_b2b.group_b2b_partner'))]"/>
        <field name="domain_force">[('partner_id', '=', user.partner_id.id)]</field>
    </record>
    
    <!-- RFQ: Users can only see their own RFQs -->
    <record id="rule_rfq_own" model="ir.rule">
        <field name="name">B2B RFQ: Own Only</field>
        <field name="model_id" ref="model_smart_dairy_rfq"/>
        <field name="groups" eval="[(4, ref('smart_dairy_b2b.group_b2b_partner'))]"/>
        <field name="domain_force">[('partner_id', '=', user.partner_id.id)]</field>
    </record>
</odoo>
```

### 10.2 Security Best Practices

| Practice | Implementation |
|----------|----------------|
| Input Validation | All form inputs validated server-side |
| CSRF Protection | All POST endpoints use CSRF tokens |
| XSS Prevention | Output escaped in all templates |
| SQL Injection | Use ORM methods, never raw SQL in controllers |
| File Upload | File size and type validation, secure storage |
| Session Security | Secure cookies, session timeout |
| Rate Limiting | Implemented on registration and login endpoints |

---

## 11. APPENDIX D - TESTING SCENARIOS

### 11.1 Partner Registration Test Cases

| TC ID | Test Case | Steps | Expected Result |
|-------|-----------|-------|-----------------|
| REG-001 | Valid registration | Fill all required fields correctly | Partner created, pending approval |
| REG-002 | Missing required field | Submit with empty company name | Error message displayed |
| REG-003 | Duplicate email | Register with existing email | Error: Email already registered |
| REG-004 | Invalid email format | Enter invalid email | Validation error |
| REG-005 | File upload | Upload trade license document | File attached to partner |
| REG-006 | Email notifications | Complete registration | Notifications sent to sales team |

### 11.2 Pricing Test Cases

| TC ID | Test Case | Steps | Expected Result |
|-------|-----------|-------|-----------------|
| PRI-001 | Tier discount applied | Login as distributor, view products | Discounted prices displayed |
| PRI-002 | Volume pricing | Add 1000+ units to cart | Volume discount applied |
| PRI-003 | Minimum order | Attempt order below minimum | Error message shown |
| PRI-004 | Promotional pricing | View product with active promotion | Promotional price shown |
| PRI-005 | Price expiration | View product with expired price | Regular price shown |

### 11.3 Credit Management Test Cases

| TC ID | Test Case | Steps | Expected Result |
|-------|-----------|-------|-----------------|
| CRE-001 | Credit check | Place order within limit | Order confirmed |
| CRE-002 | Exceed credit limit | Place order exceeding limit | Error: Insufficient credit |
| CRE-003 | Credit hold | Attempt order when on hold | Error: Credit on hold |
| CRE-004 | Manual hold | Manager places hold on account | Account held, notification sent |
| CRE-005 | Hold release | Manager releases hold | Account active, notification sent |
| CRE-006 | Aging report | View credit aging | Accurate aging buckets displayed |

---

## 12. APPENDIX E - UI/UX SPECIFICATIONS

### 12.1 Dashboard Layout

```
┌─────────────────────────────────────────────────────────────────────┐
│  Smart Dairy B2B Portal                          [Notifications] [Profile] │
├─────────────────────────────────────────────────────────────────────┤
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────┐ │
│  │ Credit Limit │  │  Used        │  │  Available   │  │  Status  │ │
│  │ 500,000 BDT  │  │  125,000 BDT │  │  375,000 BDT │  │  Active  │ │
│  └──────────────┘  └──────────────┘  └──────────────┘  └──────────┘ │
│                                                                      │
│  ┌────────────────────────────┐  ┌────────────────────────────┐     │
│  │    ORDER CHART            │  │    CREDIT AGING            │     │
│  │  [Monthly order chart]    │  │  Current: 50,000          │     │
│  │                           │  │  30-60: 25,000            │     │
│  │                           │  │  60-90: 10,000            │     │
│  │                           │  │  90+: 5,000               │     │
│  └────────────────────────────┘  └────────────────────────────┘     │
│                                                                      │
│  ┌────────────────────────────┐  ┌────────────────────────────┐     │
│  │    RECENT ORDERS          │  │    QUICK ACTIONS           │     │
│  │  • SO-2024-001 - Pending  │  │  [Place Quick Order]       │     │
│  │  • SO-2024-002 - Shipped  │  │  [View Invoices]           │     │
│  │  • SO-2024-003 - Paid     │  │  [Request Quote]           │     │
│  │  [View All Orders]        │  │  [Download Statement]      │     │
│  └────────────────────────────┘  └────────────────────────────┘     │
│                                                                      │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                    SAVED ORDER TEMPLATES                      │   │
│  │  • Weekly Restock (Default)  [Order] [Edit] [Delete]        │   │
│  │  • Monthly Supply            [Order] [Edit] [Delete]        │   │
│  │  [Create New Template]                                       │   │
│  └──────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────┘
```

### 12.2 Quick Order Interface

```
┌─────────────────────────────────────────────────────────────────────┐
│  Quick Order                                          [Back to Dashboard]│
├─────────────────────────────────────────────────────────────────────┤
│  Customer: ABC Retail Ltd.            Credit: 375,000 BDT available │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  [Load from Template ▼]  [Import CSV]                               │
│                                                                      │
│  ┌────────────────────────────────────────────────────────────────┐ │
│  │  Product Name          │ UoM    │ Unit Price │ Qty │ Amount   │ │
│  ├────────────────────────────────────────────────────────────────┤ │
│  │  Full Cream Milk 1L    │ Bottle │ 95.00 BDT  │ [  ]│ 0.00     │ │
│  │  Low Fat Milk 500ml    │ Bottle │ 55.00 BDT  │ [  ]│ 0.00     │ │
│  │  Yogurt Plain 400g     │ Cup    │ 85.00 BDT  │ [  ]│ 0.00     │ │
│  │  Ghee 500g             │ Jar    │ 450.00 BDT │ [  ]│ 0.00     │ │
│  │  Cheese Slices 200g    │ Pack   │ 280.00 BDT │ [  ]│ 0.00     │ │
│  │  ... more products ...                                        │ │
│  │                                                                │ │
│  │  [Search Products...]                                        │ │
│  └────────────────────────────────────────────────────────────────┘ │
│                                                                      │
│  Subtotal:                        0.00 BDT                          │
│  Tier Discount (10%):             0.00 BDT                          │
│  Delivery Fee:                    0.00 BDT                          │
│  ─────────────────────────────────────────                          │
│  Total:                           0.00 BDT                          │
│  (Minimum order: 10,000 BDT)                                        │
│                                                                      │
│  Delivery Location: [Primary Address ▼]                             │
│  Delivery Date:     [Date Picker]                                   │
│  Payment Method:    [On Credit ○] [Online Payment ○]               │
│                                                                      │
│  [☐] Save as template    Template Name: [______________]           │
│                                                                      │
│  Order Notes:                                                       │
│  ┌────────────────────────────────────────────────────────────┐    │
│  │                                                            │    │
│  └────────────────────────────────────────────────────────────┘    │
│                                                                      │
│                    [Cancel]        [Place Order]                    │
└─────────────────────────────────────────────────────────────────────┘
```

---

*End of Milestone 7 Documentation - B2B Portal Foundation*

---

**Document Revision History**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2024-XX-XX | Implementation Team | Initial document |

