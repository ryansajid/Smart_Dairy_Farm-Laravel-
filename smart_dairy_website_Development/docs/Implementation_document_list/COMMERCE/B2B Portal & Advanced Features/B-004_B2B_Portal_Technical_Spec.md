# SMART DAIRY LTD.
## B2B PORTAL TECHNICAL SPECIFICATION
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | B-004 |
| **Version** | 1.0 |
| **Date** | July 7, 2026 |
| **Author** | Odoo Lead |
| **Owner** | Odoo Lead |
| **Reviewer** | Sales Manager |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [System Architecture](#2-system-architecture)
3. [Data Models](#3-data-models)
4. [Partner Management](#4-partner-management)
5. [Pricing & Catalog](#5-pricing--catalog)
6. [Order Management](#6-order-management)
7. [Credit Management](#7-credit-management)
8. [API Integration](#8-api-integration)
9. [Reporting & Analytics](#9-reporting--analytics)
10. [Security & Compliance](#10-security--compliance)
11. [Implementation Guide](#11-implementation-guide)
12. [Appendices](#12-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides the comprehensive technical specification for the Smart Dairy B2B Portal, a wholesale e-commerce platform enabling business customers (retailers, distributors, HORECA, institutional buyers) to place bulk orders, manage credit, track deliveries, and access self-service capabilities.

### 1.2 Business Context

| B2B Segment | Description | Typical Order Value | Payment Terms |
|-------------|-------------|---------------------|---------------|
| **Distributors** | Regional product distributors | ৳100,000+ per order | Net 30, Credit Limit |
| **Retailers** | Grocery stores, supermarkets | ৳10,000-50,000 per order | Net 15, COD, or Credit |
| **HORECA** | Hotels, Restaurants, Cafés | ৳20,000-100,000 per order | Net 30, Standing Orders |
| **Institutional** | Schools, hospitals, corporates | ৳50,000+ per order | Net 45, Contract Pricing |

### 1.3 Technical Goals

- Support 500+ active B2B customers by Year 3
- Process 1000+ daily B2B transactions
- Sub-second pricing calculations for tiered structures
- Real-time credit limit validation
- 99.9% uptime for order placement

---

## 2. SYSTEM ARCHITECTURE

### 2.1 B2B Portal Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         B2B PORTAL ARCHITECTURE                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         PRESENTATION LAYER                             │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐               │  │
│  │  │ B2B Web      │  │ Mobile Web   │  │ API Clients  │               │  │
│  │  │ Portal       │  │ (Responsive) │  │ (ERP/3rd)    │               │  │
│  │  └──────────────┘  └──────────────┘  └──────────────┘               │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                    ▲                                         │
│                                    │                                         │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         APPLICATION LAYER                              │  │
│  │  ┌─────────────────────────────────────────────────────────────────┐  │  │
│  │  │              Odoo 19 B2B Module (Custom)                         │  │  │
│  │  │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐       │  │  │
│  │  │  │ Partner  │  │ Pricing  │  │ Order    │  │ Credit   │       │  │  │
│  │  │  │ Mgmt     │  │ Engine   │  │ Service  │  │ Service  │       │  │  │
│  │  │  └──────────┘  └──────────┘  └──────────┘  └──────────┘       │  │  │
│  │  │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐       │  │  │
│  │  │  │ Catalog  │  │ Invoice  │  │ Report   │  │ API      │       │  │  │
│  │  │  │ Service  │  │ Service  │  │ Service  │  │ Gateway  │       │  │  │
│  │  │  └──────────┘  └──────────┘  └──────────┘  └──────────┘       │  │  │
│  │  └─────────────────────────────────────────────────────────────────┘  │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                    ▲                                         │
│                                    │                                         │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         DATA LAYER                                     │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐               │  │
│  │  │ PostgreSQL   │  │ Redis Cache  │  │ MinIO/S3     │               │  │
│  │  │ (Primary)    │  │ (Pricing/    │  │ (Documents)  │               │  │
│  │  │              │  │  Sessions)   │  │              │               │  │
│  │  └──────────────┘  └──────────────┘  └──────────────┘               │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Technology Stack

| Component | Technology | Version | Purpose |
|-----------|------------|---------|---------|
| **Backend** | Odoo 19 CE | 19.0 | Core ERP & B2B |
| **Frontend** | OWL Framework | 2.0+ | Odoo Web Client |
| **Website** | Odoo Website Builder | 19.0 | Portal pages |
| **Cache** | Redis | 7.x | Pricing, sessions |
| **Search** | PostgreSQL FTS | 16 | Product search |
| **API** | FastAPI | 0.104+ | External APIs |

---

## 3. DATA MODELS

### 3.1 Partner Management Models

```python
# models/res_partner_b2b.py

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
    ], string='B2B Partner Type')
    
    # Partner Tier
    partner_tier = fields.Selection([
        ('bronze', 'Bronze'),
        ('silver', 'Silver'),
        ('gold', 'Gold'),
        ('platinum', 'Platinum'),
    ], string='Partner Tier', default='bronze')
    
    # Business Information
    trade_license_number = fields.Char(string='Trade License Number')
    trade_license_file = fields.Binary(string='Trade License Document')
    bin_number = fields.Char(string='BIN Number')
    tin_number = fields.Char(string='TIN Number')
    
    # Credit Management
    credit_limit = fields.Monetary(string='Credit Limit', currency_field='currency_id')
    credit_used = fields.Monetary(string='Credit Used', compute='_compute_credit_used')
    credit_available = fields.Monetary(string='Credit Available', compute='_compute_credit_available')
    credit_term_days = fields.Integer(string='Credit Term (Days)', default=30)
    payment_terms = fields.Many2one('account.payment.term', string='Payment Terms')
    
    # Approval Status
    approval_status = fields.Selection([
        ('draft', 'Draft'),
        ('pending', 'Pending Approval'),
        ('approved', 'Approved'),
        ('rejected', 'Rejected'),
        ('suspended', 'Suspended'),
    ], string='Approval Status', default='draft')
    approved_by = fields.Many2one('res.users', string='Approved By')
    approved_date = fields.Datetime(string='Approved Date')
    rejection_reason = fields.Text(string='Rejection Reason')
    
    # Multi-location Support
    delivery_location_ids = fields.One2many(
        'b2b.delivery.location', 'partner_id', string='Delivery Locations'
    )
    
    # Standing Orders
    standing_order_ids = fields.One2many(
        'b2b.standing.order', 'partner_id', string='Standing Orders'
    )
    
    @api.depends('credit_limit', 'credit_used')
    def _compute_credit_available(self):
        for partner in self:
            partner.credit_available = partner.credit_limit - partner.credit_used
    
    def action_approve(self):
        self.ensure_one()
        self.write({
            'approval_status': 'approved',
            'approved_by': self.env.user.id,
            'approved_date': fields.Datetime.now(),
        })
        # Send approval notification
        self._send_approval_notification()
    
    def action_reject(self, reason):
        self.ensure_one()
        self.write({
            'approval_status': 'rejected',
            'rejection_reason': reason,
        })


class B2BDeliveryLocation(models.Model):
    _name = 'b2b.delivery.location'
    _description = 'B2B Delivery Location'
    
    partner_id = fields.Many2one('res.partner', string='Partner', required=True)
    name = fields.Char(string='Location Name', required=True)
    address = fields.Text(string='Address')
    contact_name = fields.Char(string='Contact Person')
    contact_phone = fields.Char(string='Contact Phone')
    is_default = fields.Boolean(string='Default Delivery Location')
    delivery_instructions = fields.Text(string='Delivery Instructions')


class B2BUser(models.Model):
    _name = 'b2b.user'
    _description = 'B2B Portal User'
    
    partner_id = fields.Many2one('res.partner', string='B2B Partner', required=True)
    user_id = fields.Many2one('res.users', string='User', required=True)
    role = fields.Selection([
        ('admin', 'Administrator'),
        ('manager', 'Manager'),
        ('buyer', 'Buyer'),
        ('viewer', 'Viewer'),
    ], string='Role', default='buyer')
    
    # Permissions
    can_place_orders = fields.Boolean(string='Can Place Orders', default=True)
    can_approve_orders = fields.Boolean(string='Can Approve Orders', default=False)
    order_approval_limit = fields.Monetary(string='Order Approval Limit')
    can_view_invoices = fields.Boolean(string='Can View Invoices', default=True)
    can_view_reports = fields.Boolean(string='Can View Reports', default=False)
    
    # Approval Workflow
    approver_ids = fields.Many2many('b2b.user', 'b2b_user_approver_rel', 
                                     string='Approvers')
```

### 3.2 Pricing & Catalog Models

```python
# models/pricing.py

class B2BPriceList(models.Model):
    _name = 'b2b.pricelist'
    _description = 'B2B Partner Price List'
    
    name = fields.Char(string='Price List Name', required=True)
    partner_tier = fields.Selection([
        ('bronze', 'Bronze'),
        ('silver', 'Silver'),
        ('gold', 'Gold'),
        ('platinum', 'Platinum'),
    ], string='Partner Tier')
    
    line_ids = fields.One2many('b2b.pricelist.line', 'pricelist_id', string='Price Lines')
    
    date_start = fields.Date(string='Valid From')
    date_end = fields.Date(string='Valid Until')
    active = fields.Boolean(string='Active', default=True)


class B2BPriceListLine(models.Model):
    _name = 'b2b.pricelist.line'
    _description = 'B2B Price List Line'
    
    pricelist_id = fields.Many2one('b2b.pricelist', string='Price List', required=True)
    product_id = fields.Many2one('product.product', string='Product', required=True)
    
    # Pricing Types
    price_type = fields.Selection([
        ('fixed', 'Fixed Price'),
        ('percentage', 'Percentage of Base'),
        ('margin', 'Margin on Cost'),
    ], string='Price Type', default='percentage')
    
    # Pricing Values
    base_price = fields.Float(string='Base Price')
    discount_percentage = fields.Float(string='Discount %')
    min_quantity = fields.Float(string='Minimum Quantity', default=1.0)
    max_quantity = fields.Float(string='Maximum Quantity')
    
    # Tier Pricing
    tier_1_price = fields.Float(string='Tier 1 Price (1-10)')  # Default
    tier_2_price = fields.Float(string='Tier 2 Price (11-50)')  # 5% off
    tier_3_price = fields.Float(string='Tier 3 Price (51-100)')  # 10% off
    tier_4_price = fields.Float(string='Tier 4 Price (100+)')  # 15% off
    
    def get_price_for_quantity(self, quantity):
        """Get price based on quantity tier"""
        if quantity >= 100:
            return self.tier_4_price or self.tier_1_price * 0.85
        elif quantity >= 50:
            return self.tier_3_price or self.tier_1_price * 0.90
        elif quantity >= 11:
            return self.tier_2_price or self.tier_1_price * 0.95
        return self.tier_1_price


class B2BProductCatalog(models.Model):
    _name = 'b2b.product.catalog'
    _description = 'B2B Product Catalog Assignment'
    
    partner_id = fields.Many2one('res.partner', string='Partner', required=True)
    product_id = fields.Many2one('product.product', string='Product', required=True)
    
    # Visibility
    is_visible = fields.Boolean(string='Visible to Partner', default=True)
    min_order_qty = fields.Float(string='Minimum Order Quantity', default=1.0)
    order_multiple = fields.Float(string='Order Multiple', default=1.0)
    
    # Custom Pricing
    custom_price = fields.Float(string='Custom Price')
    use_custom_price = fields.Boolean(string='Use Custom Price')
    
    # Restrictions
    max_order_qty = fields.Float(string='Maximum Order Quantity')
    lead_time_days = fields.Integer(string='Lead Time (Days)')
```

### 3.3 Order Management Models

```python
# models/b2b_order.py

class B2BOrder(models.Model):
    _name = 'b2b.order'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _description = 'B2B Order'
    
    name = fields.Char(string='Order Reference', required=True, copy=False,
                       default=lambda self: self.env['ir.sequence'].next_by_code('b2b.order'))
    
    # Partner Information
    partner_id = fields.Many2one('res.partner', string='Partner', required=True)
    partner_tier = fields.Selection(related='partner_id.partner_tier', string='Tier', store=True)
    
    # Order Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('pending_approval', 'Pending Approval'),
        ('approved', 'Approved'),
        ('confirmed', 'Confirmed'),
        ('processing', 'Processing'),
        ('shipped', 'Shipped'),
        ('delivered', 'Delivered'),
        ('cancelled', 'Cancelled'),
    ], string='Status', default='draft', tracking=True)
    
    # Order Details
    order_date = fields.Datetime(string='Order Date', default=fields.Datetime.now)
    requested_delivery_date = fields.Date(string='Requested Delivery Date')
    delivery_location_id = fields.Many2one('b2b.delivery.location', string='Delivery Location')
    
    # Financial
    currency_id = fields.Many2one('res.currency', related='partner_id.currency_id')
    amount_untaxed = fields.Monetary(string='Untaxed Amount', compute='_compute_amounts', store=True)
    amount_tax = fields.Monetary(string='Tax Amount', compute='_compute_amounts', store=True)
    amount_total = fields.Monetary(string='Total Amount', compute='_compute_amounts', store=True)
    
    # Credit
    payment_method = fields.Selection([
        ('credit', 'Credit'),
        ('cod', 'Cash on Delivery'),
        ('bank_transfer', 'Bank Transfer'),
        ('online', 'Online Payment'),
    ], string='Payment Method')
    
    # Approval Workflow
    requires_approval = fields.Boolean(string='Requires Approval', compute='_compute_requires_approval')
    approved_by = fields.Many2one('res.users', string='Approved By')
    approved_date = fields.Datetime(string='Approved Date')
    
    # Line Items
    line_ids = fields.One2many('b2b.order.line', 'order_id', string='Order Lines')
    
    # Linked Documents
    sale_order_id = fields.Many2one('sale.order', string='Linked Sale Order')
    invoice_ids = fields.One2many('account.move', 'b2b_order_id', string='Invoices')
    
    @api.depends('amount_total', 'partner_id.credit_limit')
    def _compute_requires_approval(self):
        for order in self:
            # Approval required if exceeds user's limit or partner's auto-approve threshold
            user_limit = self.env.user.b2b_user_id.order_approval_limit
            if order.amount_total > user_limit:
                order.requires_approval = True
            else:
                order.requires_approval = False
    
    def action_submit(self):
        """Submit order for approval or confirmation"""
        self.ensure_one()
        if self.requires_approval:
            self.write({'state': 'pending_approval'})
            # Notify approvers
            self._notify_approvers()
        else:
            self.action_confirm()
    
    def action_confirm(self):
        """Confirm order and create sale order"""
        self.ensure_one()
        
        # Check credit limit
        if self.payment_method == 'credit':
            if self.amount_total > self.partner_id.credit_available:
                raise ValidationError(_('Insufficient credit limit'))
        
        # Create sale order
        sale_order = self._create_sale_order()
        
        self.write({
            'state': 'confirmed',
            'sale_order_id': sale_order.id,
        })
    
    def _create_sale_order(self):
        """Create Odoo sale order from B2B order"""
        sale_vals = {
            'partner_id': self.partner_id.id,
            'order_line': [(0, 0, {
                'product_id': line.product_id.id,
                'product_uom_qty': line.quantity,
                'price_unit': line.unit_price,
            }) for line in self.line_ids],
        }
        return self.env['sale.order'].create(sale_vals)


class B2BOrderLine(models.Model):
    _name = 'b2b.order.line'
    _description = 'B2B Order Line'
    
    order_id = fields.Many2one('b2b.order', string='Order', required=True)
    product_id = fields.Many2one('product.product', string='Product', required=True)
    
    quantity = fields.Float(string='Quantity', required=True)
    unit_price = fields.Monetary(string='Unit Price', currency_field='currency_id')
    
    # Pricing Details
    base_price = fields.Monetary(string='Base Price')
    discount_amount = fields.Monetary(string='Discount')
    discount_percent = fields.Float(string='Discount %')
    
    # Totals
    price_subtotal = fields.Monetary(string='Subtotal', compute='_compute_totals')
    
    # Custom Fields
    customer_reference = fields.Char(string='Customer Reference')
    special_instructions = fields.Text(string='Special Instructions')
```

---

## 4. PARTNER MANAGEMENT

### 4.1 Registration Workflow

```python
# controllers/registration.py

from odoo import http
from odoo.http import request

class B2BRegistrationController(http.Controller):
    
    @http.route('/b2b/register', type='http', auth='public', website=True)
    def b2b_register(self, **kwargs):
        """B2B Partner Registration Form"""
        return request.render('smart_dairy_b2b.portal_registration', {
            'partner_types': self._get_partner_types(),
        })
    
    @http.route('/b2b/register/submit', type='http', auth='public', methods=['POST'], csrf=True)
    def b2b_register_submit(self, **post):
        """Process B2B Registration"""
        try:
            # Validate required fields
            required_fields = ['company_name', 'contact_name', 'email', 'phone', 
                              'business_type', 'trade_license']
            for field in required_fields:
                if not post.get(field):
                    return request.render('smart_dairy_b2b.registration_error', {
                        'error': f'{field} is required'
                    })
            
            # Create partner record
            partner_vals = {
                'name': post.get('company_name'),
                'is_company': True,
                'is_b2b_partner': True,
                'b2b_partner_type': post.get('business_type'),
                'email': post.get('email'),
                'phone': post.get('phone'),
                'mobile': post.get('mobile'),
                'street': post.get('address'),
                'city': post.get('city'),
                'zip': post.get('postal_code'),
                'country_id': int(post.get('country_id')) if post.get('country_id') else None,
                'trade_license_number': post.get('trade_license'),
                'bin_number': post.get('bin_number'),
                'tin_number': post.get('tin_number'),
                'approval_status': 'pending',
            }
            
            partner = request.env['res.partner'].sudo().create(partner_vals)
            
            # Upload documents
            if post.get('trade_license_file'):
                partner.write({
                    'trade_license_file': base64.b64encode(post.get('trade_license_file').read()),
                })
            
            # Create portal user
            user_vals = {
                'partner_id': partner.id,
                'login': post.get('email'),
                'name': post.get('contact_name'),
                'groups_id': [(4, request.env.ref('base.group_portal').id)],
            }
            user = request.env['res.users'].sudo().create(user_vals)
            
            # Create B2B user record
            request.env['b2b.user'].sudo().create({
                'partner_id': partner.id,
                'user_id': user.id,
                'role': 'admin',
            })
            
            # Send notification to sales team
            self._notify_registration(partner)
            
            return request.render('smart_dairy_b2b.registration_success', {
                'partner': partner,
            })
            
        except Exception as e:
            return request.render('smart_dairy_b2b.registration_error', {
                'error': str(e),
            })
```

---

## 5. PRICING & CATALOG

### 5.1 Dynamic Pricing Engine

```python
# services/pricing_engine.py

class PricingEngine:
    """Dynamic pricing engine for B2B customers"""
    
    def __init__(self, partner):
        self.partner = partner
        self.env = partner.env
    
    def get_product_price(self, product, quantity=1.0, uom=None, date=None):
        """
        Get price for product based on partner tier, quantity, and date
        
        Args:
            product: product.product record
            quantity: Order quantity
            uom: Unit of measure (optional)
            date: Pricing date (optional, default today)
            
        Returns:
            dict: {
                'unit_price': float,
                'discount': float,
                'price_valid_until': date,
                'tier_applied': str,
            }
        """
        date = date or fields.Date.today()
        
        # 1. Check custom price for this partner
        custom_price = self._get_custom_price(product)
        if custom_price:
            return {
                'unit_price': custom_price,
                'discount': 0.0,
                'price_valid_until': None,
                'tier_applied': 'custom',
            }
        
        # 2. Get tier-based pricing
        tier_price = self._get_tier_price(product, quantity)
        if tier_price:
            return {
                'unit_price': tier_price,
                'discount': 0.0,
                'price_valid_until': None,
                'tier_applied': self._get_quantity_tier(quantity),
            }
        
        # 3. Get partner tier discount from base price
        base_price = product.lst_price
        discount = self._get_partner_tier_discount()
        
        return {
            'unit_price': base_price * (1 - discount),
            'discount': discount * 100,
            'price_valid_until': None,
            'tier_applied': self.partner.partner_tier,
        }
    
    def _get_custom_price(self, product):
        """Check for custom pricing for this partner"""
        catalog = self.env['b2b.product.catalog'].search([
            ('partner_id', '=', self.partner.id),
            ('product_id', '=', product.id),
            ('use_custom_price', '=', True),
        ], limit=1)
        return catalog.custom_price if catalog else None
    
    def _get_tier_price(self, product, quantity):
        """Get quantity tier pricing"""
        pricelist_line = self.env['b2b.pricelist.line'].search([
            ('pricelist_id.partner_tier', '=', self.partner.partner_tier),
            ('product_id', '=', product.id),
            ('pricelist_id.active', '=', True),
        ], limit=1)
        
        if pricelist_line:
            return pricelist_line.get_price_for_quantity(quantity)
        return None
    
    def _get_partner_tier_discount(self):
        """Get discount percentage based on partner tier"""
        discounts = {
            'bronze': 0.0,
            'silver': 0.05,
            'gold': 0.10,
            'platinum': 0.15,
        }
        return discounts.get(self.partner.partner_tier, 0.0)
```

---

## 6. ORDER MANAGEMENT

### 6.1 Quick Order Entry

```python
# controllers/quick_order.py

class QuickOrderController(http.Controller):
    
    @http.route('/b2b/quick-order', type='http', auth='user', website=True)
    def quick_order(self, **kwargs):
        """Quick order entry form with SKU input"""
        partner = request.env.user.partner_id
        
        # Get allowed products for this partner
        products = self._get_allowed_products(partner)
        
        return request.render('smart_dairy_b2b.quick_order_form', {
            'products': products,
            'default_delivery_location': partner.delivery_location_ids.filtered('is_default'),
        })
    
    @http.route('/b2b/quick-order/submit', type='json', auth='user')
    def quick_order_submit(self, **post):
        """Process quick order submission"""
        try:
            partner = request.env.user.partner_id
            
            # Parse order lines from JSON
            order_lines = post.get('lines', [])
            
            # Create B2B order
            order_vals = {
                'partner_id': partner.id,
                'requested_delivery_date': post.get('delivery_date'),
                'delivery_location_id': int(post.get('delivery_location_id')),
                'payment_method': post.get('payment_method'),
                'line_ids': [(0, 0, {
                    'product_id': int(line['product_id']),
                    'quantity': float(line['quantity']),
                    'unit_price': float(line['unit_price']),
                }) for line in order_lines],
            }
            
            order = request.env['b2b.order'].sudo().create(order_vals)
            
            # Submit for approval if needed
            order.action_submit()
            
            return {
                'success': True,
                'order_id': order.id,
                'order_name': order.name,
                'state': order.state,
            }
            
        except Exception as e:
            return {
                'success': False,
                'error': str(e),
            }
```

---

## 7. CREDIT MANAGEMENT

### 7.1 Credit Control

```python
# models/credit_management.py

class CreditManagement(models.Model):
    _name = 'b2b.credit.management'
    _description = 'B2B Credit Management'
    
    partner_id = fields.Many2one('res.partner', string='Partner', required=True)
    
    # Credit Details
    credit_limit = fields.Monetary(string='Credit Limit', required=True)
    credit_term_days = fields.Integer(string='Credit Term (Days)', default=30)
    
    # Usage Tracking
    credit_used = fields.Monetary(string='Credit Used', compute='_compute_credit_stats')
    credit_available = fields.Monetary(string='Credit Available', compute='_compute_credit_stats')
    
    # Aging Analysis
    current_amount = fields.Monetary(string='Current (Not Due)')
    overdue_1_30 = fields.Monetary(string='1-30 Days Overdue')
    overdue_31_60 = fields.Monetary(string='31-60 Days Overdue')
    overdue_61_90 = fields.Monetary(string='61-90 Days Overdue')
    overdue_90_plus = fields.Monetary(string='90+ Days Overdue')
    
    @api.depends('partner_id')
    def _compute_credit_stats(self):
        for rec in self:
            # Calculate from outstanding invoices
            invoices = self.env['account.move'].search([
                ('partner_id', '=', rec.partner_id.id),
                ('move_type', '=', 'out_invoice'),
                ('payment_state', '!=', 'paid'),
                ('state', '=', 'posted'),
            ])
            
            rec.credit_used = sum(invoices.mapped('amount_residual'))
            rec.credit_available = rec.credit_limit - rec.credit_used
    
    def check_credit_availability(self, amount):
        """Check if credit is available for order amount"""
        self.ensure_one()
        
        # Check hard limit
        if amount > self.credit_available:
            return {
                'approved': False,
                'reason': 'Credit limit exceeded',
                'available': self.credit_available,
            }
        
        # Check aging (if significant overdue)
        total_overdue = self.overdue_31_60 + self.overdue_61_90 + self.overdue_90_plus
        if total_overdue > self.credit_limit * 0.3:  # 30% overdue threshold
            return {
                'approved': False,
                'reason': 'Account has significant overdue balance',
                'overdue_amount': total_overdue,
            }
        
        return {
            'approved': True,
            'available': self.credit_available,
        }
```

---

## 8. API INTEGRATION

### 8.1 B2B REST API

```python
# FastAPI B2B API
from fastapi import FastAPI, Depends, HTTPException
from pydantic import BaseModel
from typing import List, Optional

app = FastAPI(title="Smart Dairy B2B API")

class OrderLineCreate(BaseModel):
    product_id: int
    quantity: float
    customer_reference: Optional[str] = None

class OrderCreate(BaseModel):
    delivery_location_id: int
    requested_delivery_date: str
    payment_method: str
    lines: List[OrderLineCreate]

@app.post("/b2b/v1/orders")
async def create_order(
    order: OrderCreate,
    current_user: User = Depends(get_current_b2b_user)
):
    """Create B2B order via API"""
    
    # Validate partner credit
    credit_check = check_credit(current_user.partner_id, order.total_amount)
    if not credit_check['approved']:
        raise HTTPException(status_code=400, detail=credit_check['reason'])
    
    # Create order
    b2b_order = await create_b2b_order(order, current_user)
    
    return {
        "order_id": b2b_order.id,
        "order_number": b2b_order.name,
        "status": b2b_order.state,
        "estimated_delivery": b2b_order.estimated_delivery_date,
    }

@app.get("/b2b/v1/products")
async def get_products(
    category: Optional[str] = None,
    search: Optional[str] = None,
    current_user: User = Depends(get_current_b2b_user)
):
    """Get products available to B2B partner with pricing"""
    
    products = await get_b2b_products(
        partner_id=current_user.partner_id,
        category=category,
        search=search,
    )
    
    return {
        "products": [
            {
                "id": p.id,
                "name": p.name,
                "sku": p.default_code,
                "unit_price": p.b2b_price,
                "min_order_qty": p.min_order_qty,
                "available_qty": p.qty_available,
            }
            for p in products
        ]
    }
```

---

## 9. REPORTING & ANALYTICS

### 9.1 B2B Reports

| Report Name | Description | Frequency |
|-------------|-------------|-----------|
| Sales by Partner | Revenue breakdown by B2B customer | Monthly |
| Product Performance | Top selling products by segment | Weekly |
| Credit Aging | Overdue invoice analysis | Weekly |
| Order Fulfillment | Delivery performance metrics | Daily |
| Price Variance | Discount analysis by tier | Monthly |

---

## 10. SECURITY & COMPLIANCE

### 10.1 Access Control

```python
# security/ir.model.access.csv
id,name,model_id:id,group_id:id,perm_read,perm_write,perm_create,perm_unlink
access_b2b_order_user,B2B Order User,model_b2b_order,smart_dairy_b2b.group_b2b_user,1,1,1,0
access_b2b_order_manager,B2B Order Manager,model_b2b_order,smart_dairy_b2b.group_b2b_manager,1,1,1,1
access_b2b_partner_admin,B2B Partner Admin,model_res_partner,smart_dairy_b2b.group_b2b_admin,1,1,1,0
```

---

## 11. IMPLEMENTATION GUIDE

### 11.1 Installation Steps

```bash
# 1. Install B2B module
./odoo-bin -c odoo.conf -i smart_dairy_b2b -d smart_dairy_prod

# 2. Configure partner tiers
# Settings > B2B Portal > Partner Tiers

# 3. Set up price lists
# B2B > Configuration > Price Lists

# 4. Configure approval workflows
# B2B > Configuration > Approval Rules

# 5. Enable portal access
# Settings > Users > Portal Access
```

### 11.2 Configuration Parameters

| Parameter | Default | Description |
|-----------|---------|-------------|
| b2b.auto_approve_limit | 50000 | Auto-approve orders below this amount |
| b2b.credit_check_enabled | True | Enable credit limit validation |
| b2b.min_order_amount | 5000 | Minimum order value |
| b2b.free_delivery_threshold | 20000 | Free delivery above this amount |

---

## 12. APPENDICES

### Appendix A: Database Schema

```sql
-- B2B Orders Table
CREATE TABLE b2b_orders (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    partner_id INTEGER REFERENCES res_partner(id),
    state VARCHAR(20),
    amount_total DECIMAL(15,2),
    payment_method VARCHAR(20),
    created_at TIMESTAMP DEFAULT NOW()
);

-- B2B Price Lists
CREATE TABLE b2b_pricelists (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    partner_tier VARCHAR(20),
    date_start DATE,
    date_end DATE,
    active BOOLEAN DEFAULT TRUE
);
```

### Appendix B: API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| /b2b/v1/products | GET | List available products |
| /b2b/v1/orders | POST | Create new order |
| /b2b/v1/orders/{id} | GET | Get order details |
| /b2b/v1/invoices | GET | List invoices |
| /b2b/v1/account/statement | GET | Account statement |

---

**END OF B2B PORTAL TECHNICAL SPECIFICATION**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | July 7, 2026 | Odoo Lead | Initial version |
