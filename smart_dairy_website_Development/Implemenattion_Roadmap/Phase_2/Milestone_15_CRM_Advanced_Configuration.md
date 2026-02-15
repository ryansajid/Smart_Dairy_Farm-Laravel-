# Milestone 15: CRM Advanced Configuration

## Smart Dairy Digital Portal + ERP Implementation
### Phase 2, Part A: Advanced ERP Configuration
### Days 141-150 (10-Day Sprint)

---

## Document Control

| Attribute | Value |
|-----------|-------|
| **Document ID** | SD-P2-MS15-CRM-v1.0 |
| **Version** | 1.0 |
| **Last Updated** | 2025-01-15 |
| **Status** | Draft |
| **Owner** | Dev 2 (Full-Stack Developer) |
| **Reviewers** | Technical Architect, Project Manager |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Configure Odoo 19 CE CRM module with advanced customer segmentation, sales pipeline management, territory assignment, and customer 360-degree view dashboards tailored for Smart Dairy's multi-channel B2B/B2C business model.

### 1.2 Objectives

| # | Objective | Priority | BRD Ref |
|---|-----------|----------|---------|
| 1 | Configure customer segmentation (B2C Individual, B2B Distributor/Retailer/HORECA/Institutional) | Critical | BRD §3.1 |
| 2 | Implement sales pipeline with dairy-specific stages | Critical | SRS-REQ-CRM-001 |
| 3 | Configure lead scoring model for dairy B2B prospects | High | SRS-REQ-CRM-002 |
| 4 | Implement customer tier/loyalty management | High | SRS-REQ-CRM-003 |
| 5 | Configure sales team and territory assignment | High | SRS-REQ-CRM-004 |
| 6 | Build Customer 360 view dashboard | Critical | SRS-REQ-CRM-005 |
| 7 | Implement communication history tracking | Medium | FR-CRM-007 |
| 8 | Configure automated follow-up workflows | Medium | FR-CRM-008 |
| 9 | Set up CRM reporting and analytics | High | FR-CRM-009 |
| 10 | Integrate CRM with Quality and Inventory modules | High | FR-CRM-010 |

### 1.3 Key Deliverables

| Deliverable | Owner | Day |
|-------------|-------|-----|
| Customer segmentation data model | Dev 2 | 141 |
| B2B customer types configuration | Dev 1 | 141 |
| Sales pipeline stages | Dev 2 | 142 |
| Lead scoring engine | Dev 2 | 143 |
| Customer tier management | Dev 1 | 144 |
| Territory management module | Dev 1 | 145 |
| Sales team assignment | Dev 2 | 145 |
| Customer 360 dashboard | Dev 3 | 146-147 |
| Communication tracking | Dev 2 | 148 |
| Automated workflows | Dev 1 | 148 |
| CRM analytics reports | Dev 2 | 149 |
| Module integration & testing | All | 150 |

### 1.4 Prerequisites

- [x] Milestone 14: Bangladesh Localization completed
- [x] Base CRM module installed and configured
- [x] Contact management operational
- [x] User roles and permissions defined
- [x] Smart Farm module integrated

### 1.5 Success Criteria

| Criteria | Target | Measurement |
|----------|--------|-------------|
| Customer segments operational | 5 types | Configuration test |
| Pipeline stages configured | 7 stages | Workflow test |
| Lead scoring accuracy | >85% | Historical data validation |
| Customer 360 load time | <2 seconds | Performance test |
| Territory coverage | 100% of sales regions | Configuration audit |

---

## 2. Requirement Traceability Matrix

### 2.1 BRD Requirements

| BRD Req ID | Description | Implementation | Day |
|------------|-------------|----------------|-----|
| BRD §3.1 | Customer segmentation | `smart_crm_dairy.customer_segment` | 141 |
| BRD §3.1 | B2B customer types | `smart_crm_dairy.b2b_type` | 141 |
| BRD §3.2 | Sales pipeline | `smart_crm_dairy.crm_stage` | 142 |
| BRD §3.3 | Lead management | `smart_crm_dairy.crm_lead` | 143 |
| BRD §3.4 | Customer loyalty | `smart_crm_dairy.customer_tier` | 144 |

### 2.2 SRS Requirements

| SRS Req ID | Description | Implementation | Owner |
|------------|-------------|----------------|-------|
| SRS-REQ-CRM-001 | Sales pipeline with 7 stages | Pipeline configuration | Dev 2 |
| SRS-REQ-CRM-002 | Lead scoring 0-100 scale | Scoring algorithm | Dev 2 |
| SRS-REQ-CRM-003 | 4-tier loyalty system | Tier management | Dev 1 |
| SRS-REQ-CRM-004 | Territory assignment | Geography mapping | Dev 1 |
| SRS-REQ-CRM-005 | Customer 360 dashboard | OWL components | Dev 3 |

---

## 3. Day-by-Day Breakdown

---

### Day 141: Customer Segmentation & B2B Types

#### Dev 1 Tasks (8h) - B2B Customer Configuration

**Task 1.1: B2B Customer Type Model (4h)**

```python
# smart_crm_dairy/models/b2b_customer_type.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError

class B2BCustomerType(models.Model):
    _name = 'smart.b2b.customer.type'
    _description = 'B2B Customer Type'
    _order = 'sequence, name'

    name = fields.Char(string='Type Name', required=True, translate=True)
    code = fields.Char(string='Code', required=True, size=10)
    sequence = fields.Integer(string='Sequence', default=10)
    active = fields.Boolean(string='Active', default=True)

    # Classification
    category = fields.Selection([
        ('distributor', 'Distributor'),
        ('retailer', 'Retailer'),
        ('horeca', 'HORECA'),
        ('institutional', 'Institutional'),
    ], string='Category', required=True)

    # Business Rules
    min_order_value = fields.Float(string='Minimum Order Value (BDT)')
    default_payment_term_id = fields.Many2one(
        'account.payment.term',
        string='Default Payment Terms'
    )
    default_credit_limit = fields.Float(string='Default Credit Limit (BDT)')
    requires_approval = fields.Boolean(
        string='Requires Order Approval',
        default=False
    )
    approval_threshold = fields.Float(string='Approval Threshold (BDT)')

    # Pricing
    default_pricelist_id = fields.Many2one(
        'product.pricelist',
        string='Default Pricelist'
    )
    discount_percentage = fields.Float(
        string='Base Discount %',
        digits=(5, 2)
    )

    # Documents Required
    requires_trade_license = fields.Boolean(string='Trade License Required')
    requires_tin = fields.Boolean(string='TIN Required')
    requires_vat_certificate = fields.Boolean(string='VAT Certificate Required')
    requires_bank_guarantee = fields.Boolean(string='Bank Guarantee Required')

    # Related Partners
    partner_ids = fields.One2many(
        'res.partner',
        'b2b_customer_type_id',
        string='Partners'
    )
    partner_count = fields.Integer(
        string='Partner Count',
        compute='_compute_partner_count'
    )

    _sql_constraints = [
        ('code_unique', 'UNIQUE(code)', 'Customer type code must be unique!')
    ]

    @api.depends('partner_ids')
    def _compute_partner_count(self):
        for record in self:
            record.partner_count = len(record.partner_ids)

    @api.constrains('discount_percentage')
    def _check_discount(self):
        for record in self:
            if record.discount_percentage < 0 or record.discount_percentage > 50:
                raise ValidationError(
                    "Discount percentage must be between 0 and 50%"
                )


class B2BSubType(models.Model):
    _name = 'smart.b2b.subtype'
    _description = 'B2B Customer Subtype'

    name = fields.Char(string='Subtype Name', required=True)
    code = fields.Char(string='Code', required=True)
    parent_type_id = fields.Many2one(
        'smart.b2b.customer.type',
        string='Parent Type',
        required=True,
        ondelete='cascade'
    )

    # Subtype specific rules
    additional_discount = fields.Float(string='Additional Discount %')
    special_terms = fields.Text(string='Special Terms')
```

**Task 1.2: B2B Type Data Configuration (2h)**

```xml
<!-- smart_crm_dairy/data/b2b_customer_types.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <data noupdate="1">
        <!-- Distributor Types -->
        <record id="b2b_type_distributor_national" model="smart.b2b.customer.type">
            <field name="name">National Distributor</field>
            <field name="code">DIST_NAT</field>
            <field name="category">distributor</field>
            <field name="sequence">1</field>
            <field name="min_order_value">500000</field>
            <field name="default_credit_limit">5000000</field>
            <field name="discount_percentage">15.0</field>
            <field name="requires_approval">True</field>
            <field name="approval_threshold">1000000</field>
            <field name="requires_trade_license">True</field>
            <field name="requires_tin">True</field>
            <field name="requires_vat_certificate">True</field>
            <field name="requires_bank_guarantee">True</field>
        </record>

        <record id="b2b_type_distributor_regional" model="smart.b2b.customer.type">
            <field name="name">Regional Distributor</field>
            <field name="code">DIST_REG</field>
            <field name="category">distributor</field>
            <field name="sequence">2</field>
            <field name="min_order_value">200000</field>
            <field name="default_credit_limit">2000000</field>
            <field name="discount_percentage">12.0</field>
            <field name="requires_approval">True</field>
            <field name="approval_threshold">500000</field>
            <field name="requires_trade_license">True</field>
            <field name="requires_tin">True</field>
            <field name="requires_vat_certificate">True</field>
        </record>

        <!-- Retailer Types -->
        <record id="b2b_type_retailer_chain" model="smart.b2b.customer.type">
            <field name="name">Retail Chain</field>
            <field name="code">RET_CHAIN</field>
            <field name="category">retailer</field>
            <field name="sequence">3</field>
            <field name="min_order_value">100000</field>
            <field name="default_credit_limit">1000000</field>
            <field name="discount_percentage">10.0</field>
            <field name="requires_trade_license">True</field>
            <field name="requires_tin">True</field>
        </record>

        <record id="b2b_type_retailer_independent" model="smart.b2b.customer.type">
            <field name="name">Independent Retailer</field>
            <field name="code">RET_IND</field>
            <field name="category">retailer</field>
            <field name="sequence">4</field>
            <field name="min_order_value">10000</field>
            <field name="default_credit_limit">100000</field>
            <field name="discount_percentage">5.0</field>
            <field name="requires_trade_license">True</field>
        </record>

        <!-- HORECA Types -->
        <record id="b2b_type_horeca_hotel" model="smart.b2b.customer.type">
            <field name="name">Hotel</field>
            <field name="code">HOR_HOTEL</field>
            <field name="category">horeca</field>
            <field name="sequence">5</field>
            <field name="min_order_value">50000</field>
            <field name="default_credit_limit">500000</field>
            <field name="discount_percentage">8.0</field>
            <field name="requires_trade_license">True</field>
        </record>

        <record id="b2b_type_horeca_restaurant" model="smart.b2b.customer.type">
            <field name="name">Restaurant</field>
            <field name="code">HOR_REST</field>
            <field name="category">horeca</field>
            <field name="sequence">6</field>
            <field name="min_order_value">20000</field>
            <field name="default_credit_limit">200000</field>
            <field name="discount_percentage">7.0</field>
            <field name="requires_trade_license">True</field>
        </record>

        <record id="b2b_type_horeca_cafe" model="smart.b2b.customer.type">
            <field name="name">Cafe/Coffee Shop</field>
            <field name="code">HOR_CAFE</field>
            <field name="category">horeca</field>
            <field name="sequence">7</field>
            <field name="min_order_value">10000</field>
            <field name="default_credit_limit">100000</field>
            <field name="discount_percentage">5.0</field>
        </record>

        <!-- Institutional Types -->
        <record id="b2b_type_inst_hospital" model="smart.b2b.customer.type">
            <field name="name">Hospital/Healthcare</field>
            <field name="code">INST_HOSP</field>
            <field name="category">institutional</field>
            <field name="sequence">8</field>
            <field name="min_order_value">50000</field>
            <field name="default_credit_limit">500000</field>
            <field name="discount_percentage">10.0</field>
            <field name="requires_approval">True</field>
        </record>

        <record id="b2b_type_inst_school" model="smart.b2b.customer.type">
            <field name="name">School/Educational</field>
            <field name="code">INST_EDU</field>
            <field name="category">institutional</field>
            <field name="sequence">9</field>
            <field name="min_order_value">30000</field>
            <field name="default_credit_limit">300000</field>
            <field name="discount_percentage">12.0</field>
        </record>

        <record id="b2b_type_inst_corporate" model="smart.b2b.customer.type">
            <field name="name">Corporate Office</field>
            <field name="code">INST_CORP</field>
            <field name="category">institutional</field>
            <field name="sequence">10</field>
            <field name="min_order_value">25000</field>
            <field name="default_credit_limit">250000</field>
            <field name="discount_percentage">6.0</field>
        </record>
    </data>
</odoo>
```

**Task 1.3: Partner Extension for B2B (2h)**

```python
# smart_crm_dairy/models/res_partner_crm.py
from odoo import models, fields, api
from datetime import date, timedelta

class ResPartnerCRM(models.Model):
    _inherit = 'res.partner'

    # Customer Classification
    customer_segment = fields.Selection([
        ('b2c', 'B2C - Individual Consumer'),
        ('b2b', 'B2B - Business Customer'),
    ], string='Customer Segment', default='b2c')

    b2b_customer_type_id = fields.Many2one(
        'smart.b2b.customer.type',
        string='B2B Customer Type'
    )
    b2b_subtype_id = fields.Many2one(
        'smart.b2b.subtype',
        string='B2B Subtype',
        domain="[('parent_type_id', '=', b2b_customer_type_id)]"
    )

    # Customer Tier/Loyalty
    customer_tier_id = fields.Many2one(
        'smart.customer.tier',
        string='Customer Tier',
        compute='_compute_customer_tier',
        store=True
    )
    loyalty_points = fields.Integer(string='Loyalty Points', default=0)
    lifetime_value = fields.Float(
        string='Lifetime Value (BDT)',
        compute='_compute_lifetime_value',
        store=True
    )

    # Territory Assignment
    territory_id = fields.Many2one(
        'smart.sales.territory',
        string='Sales Territory'
    )
    assigned_salesperson_id = fields.Many2one(
        'res.users',
        string='Assigned Salesperson'
    )

    # Business Documents (B2B)
    trade_license_no = fields.Char(string='Trade License No.')
    trade_license_expiry = fields.Date(string='Trade License Expiry')
    tin_no = fields.Char(string='TIN Number')
    vat_registration_no = fields.Char(string='VAT Registration No.')
    bank_guarantee_amount = fields.Float(string='Bank Guarantee Amount')
    bank_guarantee_expiry = fields.Date(string='Bank Guarantee Expiry')

    # Verification Status
    verification_status = fields.Selection([
        ('pending', 'Pending Verification'),
        ('documents_submitted', 'Documents Submitted'),
        ('under_review', 'Under Review'),
        ('verified', 'Verified'),
        ('rejected', 'Rejected'),
    ], string='Verification Status', default='pending')
    verified_date = fields.Date(string='Verified Date')
    verified_by_id = fields.Many2one('res.users', string='Verified By')

    # Communication Preferences
    preferred_contact_method = fields.Selection([
        ('phone', 'Phone Call'),
        ('sms', 'SMS'),
        ('email', 'Email'),
        ('whatsapp', 'WhatsApp'),
    ], string='Preferred Contact Method', default='phone')
    preferred_contact_time = fields.Selection([
        ('morning', 'Morning (9 AM - 12 PM)'),
        ('afternoon', 'Afternoon (12 PM - 5 PM)'),
        ('evening', 'Evening (5 PM - 8 PM)'),
    ], string='Preferred Contact Time')

    # Customer Metrics
    order_frequency = fields.Float(
        string='Avg Orders/Month',
        compute='_compute_order_metrics'
    )
    avg_order_value = fields.Float(
        string='Avg Order Value',
        compute='_compute_order_metrics'
    )
    last_order_date = fields.Date(
        string='Last Order Date',
        compute='_compute_order_metrics'
    )
    days_since_last_order = fields.Integer(
        string='Days Since Last Order',
        compute='_compute_order_metrics'
    )

    @api.depends('sale_order_ids', 'sale_order_ids.amount_total',
                 'sale_order_ids.state')
    def _compute_lifetime_value(self):
        for partner in self:
            confirmed_orders = partner.sale_order_ids.filtered(
                lambda o: o.state in ['sale', 'done']
            )
            partner.lifetime_value = sum(confirmed_orders.mapped('amount_total'))

    @api.depends('lifetime_value', 'loyalty_points')
    def _compute_customer_tier(self):
        Tier = self.env['smart.customer.tier']
        for partner in self:
            tier = Tier.search([
                ('min_lifetime_value', '<=', partner.lifetime_value),
                ('active', '=', True)
            ], order='min_lifetime_value desc', limit=1)
            partner.customer_tier_id = tier.id if tier else False

    @api.depends('sale_order_ids', 'sale_order_ids.date_order',
                 'sale_order_ids.state')
    def _compute_order_metrics(self):
        for partner in self:
            confirmed_orders = partner.sale_order_ids.filtered(
                lambda o: o.state in ['sale', 'done']
            )

            if confirmed_orders:
                # Last order date
                partner.last_order_date = max(
                    confirmed_orders.mapped('date_order')
                ).date()
                partner.days_since_last_order = (
                    date.today() - partner.last_order_date
                ).days

                # Average order value
                partner.avg_order_value = (
                    sum(confirmed_orders.mapped('amount_total')) /
                    len(confirmed_orders)
                )

                # Order frequency (orders per month over last 12 months)
                one_year_ago = date.today() - timedelta(days=365)
                recent_orders = confirmed_orders.filtered(
                    lambda o: o.date_order.date() >= one_year_ago
                )
                partner.order_frequency = len(recent_orders) / 12.0
            else:
                partner.last_order_date = False
                partner.days_since_last_order = 0
                partner.avg_order_value = 0
                partner.order_frequency = 0

    @api.onchange('b2b_customer_type_id')
    def _onchange_b2b_type(self):
        if self.b2b_customer_type_id:
            self.customer_segment = 'b2b'
            btype = self.b2b_customer_type_id
            self.property_payment_term_id = btype.default_payment_term_id
            self.credit_limit = btype.default_credit_limit
            self.property_product_pricelist = btype.default_pricelist_id
```

#### Dev 2 Tasks (8h) - Customer Segmentation Model

**Task 2.1: Customer Segment Configuration (4h)**

```python
# smart_crm_dairy/models/customer_segment.py
from odoo import models, fields, api

class CustomerSegment(models.Model):
    _name = 'smart.customer.segment'
    _description = 'Customer Segment'
    _order = 'sequence, name'

    name = fields.Char(string='Segment Name', required=True, translate=True)
    code = fields.Char(string='Code', required=True)
    sequence = fields.Integer(string='Sequence', default=10)
    active = fields.Boolean(string='Active', default=True)

    segment_type = fields.Selection([
        ('b2c', 'B2C - Consumer'),
        ('b2b', 'B2B - Business'),
    ], string='Segment Type', required=True)

    # Segment Criteria
    criteria_ids = fields.One2many(
        'smart.segment.criteria',
        'segment_id',
        string='Segmentation Criteria'
    )

    # Marketing Preferences
    marketing_enabled = fields.Boolean(string='Marketing Enabled', default=True)
    email_campaign_ids = fields.Many2many(
        'mailing.list',
        string='Email Lists'
    )

    # Pricing & Promotions
    default_pricelist_id = fields.Many2one(
        'product.pricelist',
        string='Default Pricelist'
    )
    eligible_promotion_ids = fields.Many2many(
        'sale.coupon.program',
        string='Eligible Promotions'
    )

    # Statistics
    partner_count = fields.Integer(
        string='Customer Count',
        compute='_compute_partner_count'
    )
    total_revenue = fields.Float(
        string='Total Revenue',
        compute='_compute_revenue'
    )

    color = fields.Integer(string='Color')

    @api.depends('criteria_ids')
    def _compute_partner_count(self):
        Partner = self.env['res.partner']
        for segment in self:
            domain = segment._get_segment_domain()
            segment.partner_count = Partner.search_count(domain)

    def _get_segment_domain(self):
        """Build domain from segment criteria"""
        self.ensure_one()
        domain = [('customer_rank', '>', 0)]

        for criteria in self.criteria_ids:
            if criteria.field_name and criteria.operator and criteria.value:
                if criteria.operator in ['in', 'not in']:
                    value = criteria.value.split(',')
                elif criteria.field_type == 'float':
                    value = float(criteria.value)
                elif criteria.field_type == 'integer':
                    value = int(criteria.value)
                else:
                    value = criteria.value

                domain.append((criteria.field_name, criteria.operator, value))

        return domain

    @api.depends('criteria_ids')
    def _compute_revenue(self):
        for segment in self:
            partners = self.env['res.partner'].search(
                segment._get_segment_domain()
            )
            segment.total_revenue = sum(partners.mapped('lifetime_value'))

    def action_view_customers(self):
        """Open customer list for this segment"""
        self.ensure_one()
        return {
            'name': f'Customers - {self.name}',
            'type': 'ir.actions.act_window',
            'res_model': 'res.partner',
            'view_mode': 'tree,form',
            'domain': self._get_segment_domain(),
            'context': {'default_customer_rank': 1}
        }


class SegmentCriteria(models.Model):
    _name = 'smart.segment.criteria'
    _description = 'Segment Criteria'

    segment_id = fields.Many2one(
        'smart.customer.segment',
        string='Segment',
        required=True,
        ondelete='cascade'
    )

    field_name = fields.Char(string='Field', required=True)
    field_type = fields.Selection([
        ('char', 'Text'),
        ('integer', 'Integer'),
        ('float', 'Decimal'),
        ('selection', 'Selection'),
        ('many2one', 'Reference'),
        ('date', 'Date'),
    ], string='Field Type', default='char')

    operator = fields.Selection([
        ('=', 'Equals'),
        ('!=', 'Not Equals'),
        ('>', 'Greater Than'),
        ('>=', 'Greater Than or Equal'),
        ('<', 'Less Than'),
        ('<=', 'Less Than or Equal'),
        ('like', 'Contains'),
        ('in', 'In List'),
        ('not in', 'Not In List'),
    ], string='Operator', required=True, default='=')

    value = fields.Char(string='Value', required=True)
    sequence = fields.Integer(string='Sequence', default=10)
```

**Task 2.2: Customer Tier/Loyalty Model (4h)**

```python
# smart_crm_dairy/models/customer_tier.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError

class CustomerTier(models.Model):
    _name = 'smart.customer.tier'
    _description = 'Customer Tier/Loyalty Level'
    _order = 'min_lifetime_value desc'

    name = fields.Char(string='Tier Name', required=True, translate=True)
    code = fields.Char(string='Code', required=True)
    active = fields.Boolean(string='Active', default=True)

    # Tier Thresholds
    min_lifetime_value = fields.Float(
        string='Min Lifetime Value (BDT)',
        required=True
    )
    min_orders = fields.Integer(string='Min Orders')
    min_loyalty_points = fields.Integer(string='Min Loyalty Points')

    # Benefits
    discount_percentage = fields.Float(
        string='Tier Discount %',
        digits=(5, 2)
    )
    free_delivery = fields.Boolean(string='Free Delivery')
    priority_support = fields.Boolean(string='Priority Support')
    early_access = fields.Boolean(string='Early Access to Products')
    dedicated_manager = fields.Boolean(string='Dedicated Account Manager')

    # Points Multiplier
    points_multiplier = fields.Float(
        string='Points Multiplier',
        default=1.0,
        help="Multiplier for earning loyalty points"
    )

    # Visual
    badge_image = fields.Binary(string='Badge Image')
    color = fields.Char(string='Color Code')

    # Statistics
    customer_count = fields.Integer(
        string='Customer Count',
        compute='_compute_customer_count'
    )

    _sql_constraints = [
        ('code_unique', 'UNIQUE(code)', 'Tier code must be unique!'),
        ('value_unique', 'UNIQUE(min_lifetime_value)',
         'Lifetime value threshold must be unique!')
    ]

    @api.depends('min_lifetime_value')
    def _compute_customer_count(self):
        for tier in self:
            # Find next tier threshold
            next_tier = self.search([
                ('min_lifetime_value', '>', tier.min_lifetime_value),
                ('active', '=', True)
            ], order='min_lifetime_value asc', limit=1)

            domain = [
                ('customer_rank', '>', 0),
                ('lifetime_value', '>=', tier.min_lifetime_value)
            ]
            if next_tier:
                domain.append(('lifetime_value', '<', next_tier.min_lifetime_value))

            tier.customer_count = self.env['res.partner'].search_count(domain)

    @api.constrains('discount_percentage')
    def _check_discount(self):
        for tier in self:
            if tier.discount_percentage < 0 or tier.discount_percentage > 30:
                raise ValidationError(
                    "Tier discount must be between 0% and 30%"
                )


class LoyaltyPointsHistory(models.Model):
    _name = 'smart.loyalty.history'
    _description = 'Loyalty Points History'
    _order = 'create_date desc'

    partner_id = fields.Many2one(
        'res.partner',
        string='Customer',
        required=True,
        ondelete='cascade'
    )

    points = fields.Integer(string='Points', required=True)
    points_type = fields.Selection([
        ('earned', 'Earned'),
        ('redeemed', 'Redeemed'),
        ('expired', 'Expired'),
        ('bonus', 'Bonus'),
        ('adjustment', 'Manual Adjustment'),
    ], string='Type', required=True)

    reference = fields.Char(string='Reference')
    order_id = fields.Many2one('sale.order', string='Related Order')
    expiry_date = fields.Date(string='Expiry Date')

    notes = fields.Text(string='Notes')

    @api.model_create_multi
    def create(self, vals_list):
        records = super().create(vals_list)
        for record in records:
            # Update partner's total points
            partner = record.partner_id
            if record.points_type in ['earned', 'bonus', 'adjustment']:
                partner.loyalty_points += record.points
            elif record.points_type in ['redeemed', 'expired']:
                partner.loyalty_points -= abs(record.points)
        return records
```

#### Dev 3 Tasks (8h) - UI Components & Views

**Task 3.1: Customer Segmentation Views (4h)**

```xml
<!-- smart_crm_dairy/views/customer_segment_views.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Customer Segment Form View -->
    <record id="view_customer_segment_form" model="ir.ui.view">
        <field name="name">smart.customer.segment.form</field>
        <field name="model">smart.customer.segment</field>
        <field name="arch" type="xml">
            <form string="Customer Segment">
                <sheet>
                    <div class="oe_button_box" name="button_box">
                        <button name="action_view_customers" type="object"
                                class="oe_stat_button" icon="fa-users">
                            <field name="partner_count" widget="statinfo"
                                   string="Customers"/>
                        </button>
                    </div>
                    <widget name="web_ribbon" title="Archived"
                            bg_color="bg-danger"
                            invisible="active"/>
                    <div class="oe_title">
                        <h1>
                            <field name="name" placeholder="Segment Name"/>
                        </h1>
                    </div>
                    <group>
                        <group>
                            <field name="code"/>
                            <field name="segment_type"/>
                            <field name="sequence"/>
                            <field name="active"/>
                        </group>
                        <group>
                            <field name="default_pricelist_id"/>
                            <field name="marketing_enabled"/>
                            <field name="total_revenue" widget="monetary"/>
                        </group>
                    </group>
                    <notebook>
                        <page string="Segmentation Criteria" name="criteria">
                            <field name="criteria_ids">
                                <tree editable="bottom">
                                    <field name="sequence" widget="handle"/>
                                    <field name="field_name"/>
                                    <field name="field_type"/>
                                    <field name="operator"/>
                                    <field name="value"/>
                                </tree>
                            </field>
                        </page>
                        <page string="Marketing" name="marketing">
                            <group>
                                <field name="email_campaign_ids"
                                       widget="many2many_tags"/>
                                <field name="eligible_promotion_ids"
                                       widget="many2many_tags"/>
                            </group>
                        </page>
                    </notebook>
                </sheet>
            </form>
        </field>
    </record>

    <!-- Customer Segment Tree View -->
    <record id="view_customer_segment_tree" model="ir.ui.view">
        <field name="name">smart.customer.segment.tree</field>
        <field name="model">smart.customer.segment</field>
        <field name="arch" type="xml">
            <tree string="Customer Segments">
                <field name="sequence" widget="handle"/>
                <field name="name"/>
                <field name="code"/>
                <field name="segment_type" widget="badge"
                       decoration-info="segment_type == 'b2c'"
                       decoration-success="segment_type == 'b2b'"/>
                <field name="partner_count"/>
                <field name="total_revenue" widget="monetary"/>
                <field name="active"/>
            </tree>
        </field>
    </record>

    <!-- Customer Segment Kanban View -->
    <record id="view_customer_segment_kanban" model="ir.ui.view">
        <field name="name">smart.customer.segment.kanban</field>
        <field name="model">smart.customer.segment</field>
        <field name="arch" type="xml">
            <kanban class="o_kanban_mobile">
                <field name="name"/>
                <field name="segment_type"/>
                <field name="partner_count"/>
                <field name="color"/>
                <templates>
                    <t t-name="kanban-box">
                        <div t-attf-class="oe_kanban_color_#{kanban_getcolor(record.color.raw_value)} oe_kanban_card oe_kanban_global_click">
                            <div class="oe_kanban_content">
                                <div class="o_kanban_record_top">
                                    <strong class="o_kanban_record_title">
                                        <field name="name"/>
                                    </strong>
                                </div>
                                <div class="o_kanban_record_bottom">
                                    <span class="badge badge-pill">
                                        <field name="partner_count"/> Customers
                                    </span>
                                </div>
                            </div>
                        </div>
                    </t>
                </templates>
            </kanban>
        </field>
    </record>

    <!-- Action -->
    <record id="action_customer_segment" model="ir.actions.act_window">
        <field name="name">Customer Segments</field>
        <field name="res_model">smart.customer.segment</field>
        <field name="view_mode">tree,kanban,form</field>
        <field name="help" type="html">
            <p class="o_view_nocontent_smiling_face">
                Create your first customer segment
            </p>
        </field>
    </record>
</odoo>
```

**Task 3.2: Customer Tier Views (4h)**

```xml
<!-- smart_crm_dairy/views/customer_tier_views.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Customer Tier Form View -->
    <record id="view_customer_tier_form" model="ir.ui.view">
        <field name="name">smart.customer.tier.form</field>
        <field name="model">smart.customer.tier</field>
        <field name="arch" type="xml">
            <form string="Customer Tier">
                <sheet>
                    <div class="oe_button_box" name="button_box">
                        <button type="object" name="action_view_customers"
                                class="oe_stat_button" icon="fa-users">
                            <field name="customer_count" widget="statinfo"
                                   string="Customers"/>
                        </button>
                    </div>
                    <field name="badge_image" widget="image"
                           class="oe_avatar"/>
                    <div class="oe_title">
                        <h1>
                            <field name="name" placeholder="Tier Name"/>
                        </h1>
                    </div>
                    <group>
                        <group string="Qualification Criteria">
                            <field name="code"/>
                            <field name="min_lifetime_value"/>
                            <field name="min_orders"/>
                            <field name="min_loyalty_points"/>
                        </group>
                        <group string="Benefits">
                            <field name="discount_percentage"/>
                            <field name="points_multiplier"/>
                            <field name="free_delivery"/>
                            <field name="priority_support"/>
                            <field name="early_access"/>
                            <field name="dedicated_manager"/>
                        </group>
                    </group>
                    <group>
                        <field name="color" widget="color"/>
                        <field name="active"/>
                    </group>
                </sheet>
            </form>
        </field>
    </record>

    <!-- Customer Tier Tree View -->
    <record id="view_customer_tier_tree" model="ir.ui.view">
        <field name="name">smart.customer.tier.tree</field>
        <field name="model">smart.customer.tier</field>
        <field name="arch" type="xml">
            <tree string="Customer Tiers">
                <field name="name"/>
                <field name="code"/>
                <field name="min_lifetime_value" widget="monetary"/>
                <field name="discount_percentage"/>
                <field name="points_multiplier"/>
                <field name="customer_count"/>
                <field name="free_delivery" widget="boolean_toggle"/>
                <field name="active"/>
            </tree>
        </field>
    </record>

    <!-- Action -->
    <record id="action_customer_tier" model="ir.actions.act_window">
        <field name="name">Customer Tiers</field>
        <field name="res_model">smart.customer.tier</field>
        <field name="view_mode">tree,form</field>
    </record>
</odoo>
```

#### Day 141 Deliverables Checklist

- [ ] B2B customer type model created
- [ ] 10 B2B customer types configured (data)
- [ ] Partner model extended with B2B fields
- [ ] Customer segment model created
- [ ] Customer tier/loyalty model created
- [ ] Loyalty points history tracking
- [ ] All views and actions created
- [ ] Unit tests passing

---

### Day 142: Sales Pipeline Configuration

#### Dev 1 Tasks (8h) - Pipeline Stages & Workflow

**Task 1.1: CRM Stage Enhancement (4h)**

```python
# smart_crm_dairy/models/crm_stage_dairy.py
from odoo import models, fields, api

class CrmStageDairy(models.Model):
    _inherit = 'crm.stage'

    # Dairy-specific fields
    is_dairy_stage = fields.Boolean(
        string='Dairy Sales Stage',
        default=True
    )
    stage_type = fields.Selection([
        ('lead', 'Lead Stage'),
        ('opportunity', 'Opportunity Stage'),
        ('negotiation', 'Negotiation Stage'),
        ('closing', 'Closing Stage'),
    ], string='Stage Type')

    # Automation
    auto_assign_team = fields.Boolean(
        string='Auto-assign Sales Team',
        default=False
    )
    required_fields = fields.Char(
        string='Required Fields',
        help="Comma-separated list of required fields"
    )

    # Time Limits
    max_days_in_stage = fields.Integer(
        string='Max Days in Stage',
        help="Alert if opportunity stays longer"
    )

    # Actions
    on_enter_action_id = fields.Many2one(
        'ir.actions.server',
        string='On Enter Action'
    )
    on_exit_action_id = fields.Many2one(
        'ir.actions.server',
        string='On Exit Action'
    )

    # B2B Specific
    b2b_only = fields.Boolean(
        string='B2B Customers Only',
        default=False
    )
    min_deal_value = fields.Float(
        string='Min Deal Value for Stage'
    )


class CrmLeadDairy(models.Model):
    _inherit = 'crm.lead'

    # Dairy-specific classification
    business_type = fields.Selection([
        ('b2c', 'B2C - Consumer'),
        ('b2b_distributor', 'B2B - Distributor'),
        ('b2b_retailer', 'B2B - Retailer'),
        ('b2b_horeca', 'B2B - HORECA'),
        ('b2b_institutional', 'B2B - Institutional'),
    ], string='Business Type')

    # Lead Source
    lead_source = fields.Selection([
        ('website', 'Website'),
        ('referral', 'Customer Referral'),
        ('cold_call', 'Cold Call'),
        ('trade_show', 'Trade Show'),
        ('social_media', 'Social Media'),
        ('advertisement', 'Advertisement'),
        ('partner', 'Partner Referral'),
    ], string='Lead Source')

    # Territory
    territory_id = fields.Many2one(
        'smart.sales.territory',
        string='Territory'
    )

    # Product Interest
    product_interest_ids = fields.Many2many(
        'product.template',
        string='Products of Interest'
    )

    # Lead Scoring
    lead_score = fields.Integer(
        string='Lead Score',
        compute='_compute_lead_score',
        store=True
    )
    score_details = fields.Text(
        string='Score Breakdown',
        compute='_compute_lead_score'
    )

    # B2B Specific
    estimated_monthly_volume = fields.Float(
        string='Est. Monthly Volume (Liters)'
    )
    current_supplier = fields.Char(string='Current Supplier')
    reason_to_switch = fields.Text(string='Reason to Switch')

    # Follow-up
    next_followup_date = fields.Date(string='Next Follow-up Date')
    followup_type = fields.Selection([
        ('call', 'Phone Call'),
        ('meeting', 'Meeting'),
        ('email', 'Email'),
        ('visit', 'Site Visit'),
        ('demo', 'Product Demo'),
    ], string='Follow-up Type')

    # Stage Dates
    stage_entry_date = fields.Datetime(
        string='Stage Entry Date',
        default=fields.Datetime.now
    )
    days_in_stage = fields.Integer(
        string='Days in Stage',
        compute='_compute_days_in_stage'
    )

    @api.depends('stage_entry_date')
    def _compute_days_in_stage(self):
        from datetime import datetime
        for lead in self:
            if lead.stage_entry_date:
                delta = datetime.now() - lead.stage_entry_date
                lead.days_in_stage = delta.days
            else:
                lead.days_in_stage = 0

    @api.depends('partner_id', 'business_type', 'expected_revenue',
                 'probability', 'email_from', 'phone', 'lead_source')
    def _compute_lead_score(self):
        """Calculate lead score based on multiple factors"""
        for lead in self:
            score = 0
            details = []

            # Business type scoring
            business_scores = {
                'b2b_distributor': 30,
                'b2b_retailer': 25,
                'b2b_horeca': 20,
                'b2b_institutional': 20,
                'b2c': 10,
            }
            if lead.business_type:
                points = business_scores.get(lead.business_type, 0)
                score += points
                details.append(f"Business Type: +{points}")

            # Expected revenue scoring
            if lead.expected_revenue:
                if lead.expected_revenue >= 1000000:
                    score += 25
                    details.append("High Value (>10L): +25")
                elif lead.expected_revenue >= 500000:
                    score += 20
                    details.append("Medium Value (5-10L): +20")
                elif lead.expected_revenue >= 100000:
                    score += 15
                    details.append("Standard Value (1-5L): +15")
                else:
                    score += 5
                    details.append("Low Value (<1L): +5")

            # Contact completeness
            if lead.email_from:
                score += 5
                details.append("Email provided: +5")
            if lead.phone:
                score += 5
                details.append("Phone provided: +5")
            if lead.partner_id:
                score += 10
                details.append("Linked to customer: +10")

            # Lead source quality
            source_scores = {
                'referral': 15,
                'partner': 12,
                'trade_show': 10,
                'website': 8,
                'social_media': 5,
                'cold_call': 3,
                'advertisement': 3,
            }
            if lead.lead_source:
                points = source_scores.get(lead.lead_source, 0)
                score += points
                details.append(f"Source ({lead.lead_source}): +{points}")

            # Probability boost
            if lead.probability:
                prob_score = int(lead.probability * 0.1)
                score += prob_score
                details.append(f"Probability ({lead.probability}%): +{prob_score}")

            lead.lead_score = min(score, 100)
            lead.score_details = "\n".join(details)

    def write(self, vals):
        # Track stage changes
        if 'stage_id' in vals:
            vals['stage_entry_date'] = fields.Datetime.now()
        return super().write(vals)
```

**Task 1.2: Pipeline Stages Data (2h)**

```xml
<!-- smart_crm_dairy/data/crm_stages.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <data noupdate="0">
        <!-- Delete default stages and create dairy-specific ones -->

        <!-- Stage 1: New Lead -->
        <record id="crm_stage_new_lead" model="crm.stage">
            <field name="name">New Lead</field>
            <field name="sequence">1</field>
            <field name="is_dairy_stage">True</field>
            <field name="stage_type">lead</field>
            <field name="max_days_in_stage">3</field>
            <field name="fold">False</field>
        </record>

        <!-- Stage 2: Qualified -->
        <record id="crm_stage_qualified" model="crm.stage">
            <field name="name">Qualified</field>
            <field name="sequence">2</field>
            <field name="is_dairy_stage">True</field>
            <field name="stage_type">lead</field>
            <field name="max_days_in_stage">5</field>
            <field name="required_fields">business_type,expected_revenue</field>
        </record>

        <!-- Stage 3: Needs Analysis -->
        <record id="crm_stage_needs_analysis" model="crm.stage">
            <field name="name">Needs Analysis</field>
            <field name="sequence">3</field>
            <field name="is_dairy_stage">True</field>
            <field name="stage_type">opportunity</field>
            <field name="max_days_in_stage">7</field>
            <field name="required_fields">product_interest_ids</field>
        </record>

        <!-- Stage 4: Proposal/Quotation -->
        <record id="crm_stage_proposal" model="crm.stage">
            <field name="name">Proposal Sent</field>
            <field name="sequence">4</field>
            <field name="is_dairy_stage">True</field>
            <field name="stage_type">opportunity</field>
            <field name="max_days_in_stage">10</field>
        </record>

        <!-- Stage 5: Negotiation -->
        <record id="crm_stage_negotiation" model="crm.stage">
            <field name="name">Negotiation</field>
            <field name="sequence">5</field>
            <field name="is_dairy_stage">True</field>
            <field name="stage_type">negotiation</field>
            <field name="max_days_in_stage">14</field>
            <field name="b2b_only">True</field>
        </record>

        <!-- Stage 6: Contract Review -->
        <record id="crm_stage_contract" model="crm.stage">
            <field name="name">Contract Review</field>
            <field name="sequence">6</field>
            <field name="is_dairy_stage">True</field>
            <field name="stage_type">closing</field>
            <field name="max_days_in_stage">7</field>
            <field name="b2b_only">True</field>
            <field name="min_deal_value">100000</field>
        </record>

        <!-- Stage 7: Won -->
        <record id="crm_stage_won" model="crm.stage">
            <field name="name">Won</field>
            <field name="sequence">7</field>
            <field name="is_dairy_stage">True</field>
            <field name="stage_type">closing</field>
            <field name="is_won">True</field>
            <field name="fold">True</field>
        </record>
    </data>
</odoo>
```

**Task 1.3: Lost Reasons Configuration (2h)**

```xml
<!-- smart_crm_dairy/data/crm_lost_reasons.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <data noupdate="1">
        <record id="lost_reason_price" model="crm.lost.reason">
            <field name="name">Price too high</field>
        </record>
        <record id="lost_reason_competitor" model="crm.lost.reason">
            <field name="name">Chose competitor</field>
        </record>
        <record id="lost_reason_quality" model="crm.lost.reason">
            <field name="name">Quality concerns</field>
        </record>
        <record id="lost_reason_delivery" model="crm.lost.reason">
            <field name="name">Delivery terms not acceptable</field>
        </record>
        <record id="lost_reason_credit" model="crm.lost.reason">
            <field name="name">Credit terms not approved</field>
        </record>
        <record id="lost_reason_no_response" model="crm.lost.reason">
            <field name="name">No response/Lost contact</field>
        </record>
        <record id="lost_reason_not_ready" model="crm.lost.reason">
            <field name="name">Not ready to buy</field>
        </record>
        <record id="lost_reason_location" model="crm.lost.reason">
            <field name="name">Outside service area</field>
        </record>
        <record id="lost_reason_product" model="crm.lost.reason">
            <field name="name">Product not available</field>
        </record>
        <record id="lost_reason_internal" model="crm.lost.reason">
            <field name="name">Internal decision - no pursue</field>
        </record>
    </data>
</odoo>
```

#### Dev 2 Tasks (8h) - Sales Team & Assignment

**Task 2.1: Sales Team Extension (4h)**

```python
# smart_crm_dairy/models/crm_team_dairy.py
from odoo import models, fields, api

class CrmTeamDairy(models.Model):
    _inherit = 'crm.team'

    # Team Classification
    team_type = fields.Selection([
        ('b2c', 'B2C Sales'),
        ('b2b_distribution', 'B2B Distribution'),
        ('b2b_horeca', 'B2B HORECA'),
        ('b2b_institutional', 'B2B Institutional'),
        ('key_accounts', 'Key Accounts'),
        ('telesales', 'Telesales'),
    ], string='Team Type', default='b2c')

    # Territory Assignment
    territory_ids = fields.Many2many(
        'smart.sales.territory',
        string='Assigned Territories'
    )

    # Targets
    monthly_revenue_target = fields.Float(string='Monthly Revenue Target')
    monthly_lead_target = fields.Integer(string='Monthly Lead Target')
    monthly_conversion_target = fields.Float(
        string='Monthly Conversion Target %',
        default=25.0
    )

    # Performance
    current_month_revenue = fields.Float(
        string='Current Month Revenue',
        compute='_compute_performance'
    )
    current_month_leads = fields.Integer(
        string='Current Month Leads',
        compute='_compute_performance'
    )
    conversion_rate = fields.Float(
        string='Conversion Rate %',
        compute='_compute_performance'
    )

    # Team Members Enhanced
    team_member_ids = fields.One2many(
        'smart.team.member',
        'team_id',
        string='Team Members'
    )

    @api.depends('opportunity_ids', 'opportunity_ids.stage_id',
                 'opportunity_ids.expected_revenue')
    def _compute_performance(self):
        from datetime import date
        for team in self:
            today = date.today()
            first_of_month = today.replace(day=1)

            # This month's opportunities
            monthly_opps = team.opportunity_ids.filtered(
                lambda o: o.create_date.date() >= first_of_month
            )

            team.current_month_leads = len(monthly_opps)

            won_opps = monthly_opps.filtered(
                lambda o: o.stage_id.is_won
            )
            team.current_month_revenue = sum(
                won_opps.mapped('expected_revenue')
            )

            if monthly_opps:
                team.conversion_rate = (
                    len(won_opps) / len(monthly_opps) * 100
                )
            else:
                team.conversion_rate = 0


class TeamMember(models.Model):
    _name = 'smart.team.member'
    _description = 'Sales Team Member'

    team_id = fields.Many2one(
        'crm.team',
        string='Team',
        required=True,
        ondelete='cascade'
    )
    user_id = fields.Many2one(
        'res.users',
        string='User',
        required=True
    )

    role = fields.Selection([
        ('member', 'Team Member'),
        ('senior', 'Senior Sales'),
        ('lead', 'Team Lead'),
        ('manager', 'Sales Manager'),
    ], string='Role', default='member')

    # Individual Targets
    personal_revenue_target = fields.Float(string='Personal Revenue Target')
    personal_lead_target = fields.Integer(string='Personal Lead Target')

    # Territory Assignment
    territory_ids = fields.Many2many(
        'smart.sales.territory',
        string='Assigned Territories'
    )

    # Customer Assignment
    max_active_leads = fields.Integer(
        string='Max Active Leads',
        default=50
    )
    max_active_customers = fields.Integer(
        string='Max Active Customers',
        default=100
    )

    # Performance Tracking
    active_lead_count = fields.Integer(
        string='Active Leads',
        compute='_compute_performance'
    )
    active_customer_count = fields.Integer(
        string='Active Customers',
        compute='_compute_performance'
    )

    @api.depends('user_id')
    def _compute_performance(self):
        Lead = self.env['crm.lead']
        Partner = self.env['res.partner']

        for member in self:
            member.active_lead_count = Lead.search_count([
                ('user_id', '=', member.user_id.id),
                ('stage_id.is_won', '=', False),
                ('active', '=', True),
            ])
            member.active_customer_count = Partner.search_count([
                ('user_id', '=', member.user_id.id),
                ('customer_rank', '>', 0),
            ])
```

**Task 2.2: Lead Assignment Rules (4h)**

```python
# smart_crm_dairy/models/lead_assignment.py
from odoo import models, fields, api
import logging

_logger = logging.getLogger(__name__)

class LeadAssignmentRule(models.Model):
    _name = 'smart.lead.assignment.rule'
    _description = 'Lead Assignment Rule'
    _order = 'sequence, id'

    name = fields.Char(string='Rule Name', required=True)
    sequence = fields.Integer(string='Sequence', default=10)
    active = fields.Boolean(string='Active', default=True)

    # Conditions
    business_type = fields.Selection([
        ('b2c', 'B2C - Consumer'),
        ('b2b_distributor', 'B2B - Distributor'),
        ('b2b_retailer', 'B2B - Retailer'),
        ('b2b_horeca', 'B2B - HORECA'),
        ('b2b_institutional', 'B2B - Institutional'),
    ], string='Business Type')

    territory_id = fields.Many2one(
        'smart.sales.territory',
        string='Territory'
    )
    lead_source = fields.Selection([
        ('website', 'Website'),
        ('referral', 'Customer Referral'),
        ('cold_call', 'Cold Call'),
        ('trade_show', 'Trade Show'),
        ('social_media', 'Social Media'),
    ], string='Lead Source')

    min_expected_revenue = fields.Float(string='Min Expected Revenue')
    max_expected_revenue = fields.Float(string='Max Expected Revenue')

    # Assignment Target
    assign_to = fields.Selection([
        ('team', 'Sales Team'),
        ('user', 'Specific User'),
        ('round_robin', 'Round Robin in Team'),
    ], string='Assign To', required=True, default='team')

    team_id = fields.Many2one('crm.team', string='Sales Team')
    user_id = fields.Many2one('res.users', string='User')

    # Statistics
    assigned_count = fields.Integer(
        string='Leads Assigned',
        compute='_compute_assigned_count'
    )

    @api.depends('name')
    def _compute_assigned_count(self):
        Lead = self.env['crm.lead']
        for rule in self:
            rule.assigned_count = Lead.search_count([
                ('assignment_rule_id', '=', rule.id)
            ])

    def apply_rule(self, lead):
        """Apply assignment rule to lead"""
        self.ensure_one()

        if self.assign_to == 'team':
            lead.team_id = self.team_id
        elif self.assign_to == 'user':
            lead.user_id = self.user_id
            lead.team_id = self.team_id
        elif self.assign_to == 'round_robin':
            user = self._get_round_robin_user()
            if user:
                lead.user_id = user
                lead.team_id = self.team_id

        lead.assignment_rule_id = self.id
        return True

    def _get_round_robin_user(self):
        """Get next user in round robin rotation"""
        if not self.team_id:
            return False

        members = self.env['smart.team.member'].search([
            ('team_id', '=', self.team_id.id)
        ])

        if not members:
            return False

        # Find member with least active leads
        min_leads = float('inf')
        selected_member = None

        for member in members:
            if member.active_lead_count < member.max_active_leads:
                if member.active_lead_count < min_leads:
                    min_leads = member.active_lead_count
                    selected_member = member

        return selected_member.user_id if selected_member else False


class CrmLeadAssignment(models.Model):
    _inherit = 'crm.lead'

    assignment_rule_id = fields.Many2one(
        'smart.lead.assignment.rule',
        string='Assignment Rule'
    )

    @api.model_create_multi
    def create(self, vals_list):
        leads = super().create(vals_list)

        for lead in leads:
            if not lead.user_id and not lead.team_id:
                self._auto_assign_lead(lead)

        return leads

    def _auto_assign_lead(self, lead):
        """Auto-assign lead based on rules"""
        rules = self.env['smart.lead.assignment.rule'].search([
            ('active', '=', True)
        ])

        for rule in rules:
            if self._check_rule_match(lead, rule):
                rule.apply_rule(lead)
                _logger.info(
                    f"Lead {lead.id} assigned by rule {rule.name}"
                )
                return True

        return False

    def _check_rule_match(self, lead, rule):
        """Check if lead matches rule conditions"""
        if rule.business_type and lead.business_type != rule.business_type:
            return False

        if rule.territory_id and lead.territory_id != rule.territory_id:
            return False

        if rule.lead_source and lead.lead_source != rule.lead_source:
            return False

        if rule.min_expected_revenue:
            if not lead.expected_revenue or \
               lead.expected_revenue < rule.min_expected_revenue:
                return False

        if rule.max_expected_revenue:
            if lead.expected_revenue and \
               lead.expected_revenue > rule.max_expected_revenue:
                return False

        return True
```

#### Dev 3 Tasks (8h) - CRM Views Enhancement

**Task 3.1: Lead Form Enhancement (4h)**

```xml
<!-- smart_crm_dairy/views/crm_lead_views.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Enhanced Lead Form View -->
    <record id="view_crm_lead_dairy_form" model="ir.ui.view">
        <field name="name">crm.lead.dairy.form</field>
        <field name="model">crm.lead</field>
        <field name="inherit_id" ref="crm.crm_lead_view_form"/>
        <field name="arch" type="xml">
            <!-- Add Lead Score Badge -->
            <xpath expr="//div[hasclass('oe_title')]" position="before">
                <div class="badge badge-pill badge-primary float-right"
                     style="font-size: 1.2em;">
                    Score: <field name="lead_score" readonly="1"/>
                </div>
            </xpath>

            <!-- Add Business Classification -->
            <xpath expr="//group[@name='opportunity_info']" position="after">
                <group string="Business Classification" name="business_class">
                    <group>
                        <field name="business_type"/>
                        <field name="lead_source"/>
                        <field name="territory_id"/>
                    </group>
                    <group>
                        <field name="estimated_monthly_volume"
                               invisible="business_type == 'b2c'"/>
                        <field name="current_supplier"
                               invisible="business_type == 'b2c'"/>
                    </group>
                </group>
            </xpath>

            <!-- Add Product Interest -->
            <xpath expr="//page[@name='lead']" position="after">
                <page string="Product Interest" name="products">
                    <field name="product_interest_ids" widget="many2many_tags"/>
                    <field name="reason_to_switch"
                           placeholder="Why are they considering switching suppliers?"/>
                </page>
                <page string="Follow-up" name="followup">
                    <group>
                        <group>
                            <field name="next_followup_date"/>
                            <field name="followup_type"/>
                        </group>
                        <group>
                            <field name="days_in_stage"/>
                            <field name="assignment_rule_id" readonly="1"/>
                        </group>
                    </group>
                </page>
                <page string="Score Details" name="score">
                    <field name="score_details" readonly="1"/>
                </page>
            </xpath>
        </field>
    </record>

    <!-- Lead Kanban Enhancement -->
    <record id="view_crm_lead_dairy_kanban" model="ir.ui.view">
        <field name="name">crm.lead.dairy.kanban</field>
        <field name="model">crm.lead</field>
        <field name="inherit_id" ref="crm.crm_case_kanban_view_leads"/>
        <field name="arch" type="xml">
            <xpath expr="//templates//div[hasclass('oe_kanban_bottom_right')]"
                   position="inside">
                <span class="badge badge-info ml-1" t-if="record.lead_score.raw_value">
                    <t t-esc="record.lead_score.value"/>
                </span>
            </xpath>
        </field>
    </record>
</odoo>
```

**Task 3.2: Pipeline Dashboard (4h)**

```xml
<!-- smart_crm_dairy/views/crm_dashboard.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Sales Pipeline Dashboard Action -->
    <record id="action_crm_pipeline_dashboard" model="ir.actions.act_window">
        <field name="name">Sales Pipeline</field>
        <field name="res_model">crm.lead</field>
        <field name="view_mode">kanban,tree,form,pivot,graph,calendar</field>
        <field name="domain">[('type', '=', 'opportunity')]</field>
        <field name="context">{
            'default_type': 'opportunity',
            'search_default_my_opportunities': 1
        }</field>
        <field name="search_view_id" ref="crm.view_crm_case_opportunities_filter"/>
    </record>

    <!-- Pipeline Analysis Pivot -->
    <record id="view_crm_pipeline_pivot" model="ir.ui.view">
        <field name="name">crm.lead.pipeline.pivot</field>
        <field name="model">crm.lead</field>
        <field name="arch" type="xml">
            <pivot string="Pipeline Analysis">
                <field name="stage_id" type="row"/>
                <field name="business_type" type="col"/>
                <field name="expected_revenue" type="measure"/>
                <field name="lead_score" type="measure"/>
            </pivot>
        </field>
    </record>

    <!-- Pipeline Graph View -->
    <record id="view_crm_pipeline_graph" model="ir.ui.view">
        <field name="name">crm.lead.pipeline.graph</field>
        <field name="model">crm.lead</field>
        <field name="arch" type="xml">
            <graph string="Pipeline Value" type="bar">
                <field name="stage_id"/>
                <field name="expected_revenue" type="measure"/>
            </graph>
        </field>
    </record>
</odoo>
```

#### Day 142 Deliverables Checklist

- [ ] CRM stage enhanced with dairy fields
- [ ] 7 pipeline stages configured
- [ ] Lead model extended with scoring
- [ ] Lost reasons configured
- [ ] Sales team model extended
- [ ] Team member model created
- [ ] Lead assignment rules engine
- [ ] Enhanced lead views
- [ ] Pipeline dashboard

---

### Day 143: Lead Scoring Engine

#### Dev 2 Tasks (8h) - Scoring Algorithm

**Task 2.1: Advanced Lead Scoring Model (8h)**

```python
# smart_crm_dairy/models/lead_scoring.py
from odoo import models, fields, api
from datetime import datetime, timedelta
import logging

_logger = logging.getLogger(__name__)

class LeadScoringCriteria(models.Model):
    _name = 'smart.lead.scoring.criteria'
    _description = 'Lead Scoring Criteria'
    _order = 'category, sequence'

    name = fields.Char(string='Criteria Name', required=True)
    code = fields.Char(string='Code', required=True)
    sequence = fields.Integer(string='Sequence', default=10)
    active = fields.Boolean(string='Active', default=True)

    category = fields.Selection([
        ('demographic', 'Demographic'),
        ('firmographic', 'Firmographic'),
        ('behavioral', 'Behavioral'),
        ('engagement', 'Engagement'),
    ], string='Category', required=True)

    # Scoring Logic
    field_name = fields.Char(string='Field to Evaluate')
    evaluation_type = fields.Selection([
        ('exists', 'Field Has Value'),
        ('equals', 'Equals Value'),
        ('contains', 'Contains Value'),
        ('greater', 'Greater Than'),
        ('less', 'Less Than'),
        ('in_list', 'In Value List'),
        ('age_days', 'Record Age (Days)'),
        ('custom', 'Custom Python Expression'),
    ], string='Evaluation Type', required=True)

    comparison_value = fields.Char(string='Comparison Value')
    custom_expression = fields.Text(
        string='Custom Expression',
        help="Python expression. Use 'lead' variable."
    )

    # Points
    points_positive = fields.Integer(
        string='Points if True',
        default=10
    )
    points_negative = fields.Integer(
        string='Points if False',
        default=0
    )

    max_points = fields.Integer(
        string='Max Points',
        default=100,
        help="Maximum points this criteria can contribute"
    )

    def evaluate(self, lead):
        """Evaluate criteria against lead and return points"""
        self.ensure_one()

        try:
            result = self._evaluate_condition(lead)
            points = self.points_positive if result else self.points_negative
            return min(points, self.max_points)
        except Exception as e:
            _logger.error(f"Error evaluating criteria {self.name}: {e}")
            return 0

    def _evaluate_condition(self, lead):
        """Internal evaluation logic"""
        if self.evaluation_type == 'exists':
            return bool(getattr(lead, self.field_name, False))

        elif self.evaluation_type == 'equals':
            field_val = getattr(lead, self.field_name, False)
            return str(field_val) == self.comparison_value

        elif self.evaluation_type == 'contains':
            field_val = str(getattr(lead, self.field_name, '') or '')
            return self.comparison_value.lower() in field_val.lower()

        elif self.evaluation_type == 'greater':
            field_val = getattr(lead, self.field_name, 0) or 0
            return float(field_val) > float(self.comparison_value)

        elif self.evaluation_type == 'less':
            field_val = getattr(lead, self.field_name, 0) or 0
            return float(field_val) < float(self.comparison_value)

        elif self.evaluation_type == 'in_list':
            field_val = str(getattr(lead, self.field_name, ''))
            values = [v.strip() for v in self.comparison_value.split(',')]
            return field_val in values

        elif self.evaluation_type == 'age_days':
            if lead.create_date:
                age = (datetime.now() - lead.create_date).days
                return age <= int(self.comparison_value)
            return False

        elif self.evaluation_type == 'custom':
            # Safe evaluation of custom expression
            local_vars = {'lead': lead, 'datetime': datetime}
            return eval(self.custom_expression, {"__builtins__": {}}, local_vars)

        return False


class LeadScoringProfile(models.Model):
    _name = 'smart.lead.scoring.profile'
    _description = 'Lead Scoring Profile'

    name = fields.Char(string='Profile Name', required=True)
    active = fields.Boolean(string='Active', default=True)

    # Applicability
    business_types = fields.Selection([
        ('all', 'All Business Types'),
        ('b2c', 'B2C Only'),
        ('b2b', 'B2B Only'),
    ], string='Apply To', default='all')

    # Criteria
    criteria_ids = fields.Many2many(
        'smart.lead.scoring.criteria',
        string='Scoring Criteria'
    )

    # Score Thresholds
    hot_threshold = fields.Integer(
        string='Hot Lead Threshold',
        default=80
    )
    warm_threshold = fields.Integer(
        string='Warm Lead Threshold',
        default=50
    )
    cold_threshold = fields.Integer(
        string='Cold Lead Threshold',
        default=20
    )

    def calculate_score(self, lead):
        """Calculate total score for lead using this profile"""
        self.ensure_one()

        total_score = 0
        details = []

        for criteria in self.criteria_ids:
            points = criteria.evaluate(lead)
            total_score += points
            if points != 0:
                details.append(f"{criteria.name}: {points:+d}")

        # Normalize to 0-100
        total_score = max(0, min(100, total_score))

        return {
            'score': total_score,
            'details': details,
            'grade': self._get_grade(total_score)
        }

    def _get_grade(self, score):
        """Get lead grade based on score"""
        if score >= self.hot_threshold:
            return 'hot'
        elif score >= self.warm_threshold:
            return 'warm'
        elif score >= self.cold_threshold:
            return 'cold'
        else:
            return 'frozen'


class CrmLeadScoring(models.Model):
    _inherit = 'crm.lead'

    scoring_profile_id = fields.Many2one(
        'smart.lead.scoring.profile',
        string='Scoring Profile',
        compute='_compute_scoring_profile',
        store=True
    )

    lead_grade = fields.Selection([
        ('hot', 'Hot'),
        ('warm', 'Warm'),
        ('cold', 'Cold'),
        ('frozen', 'Frozen'),
    ], string='Lead Grade', compute='_compute_lead_score_full', store=True)

    score_last_updated = fields.Datetime(
        string='Score Last Updated',
        readonly=True
    )

    @api.depends('business_type')
    def _compute_scoring_profile(self):
        Profile = self.env['smart.lead.scoring.profile']

        for lead in self:
            if lead.business_type and lead.business_type.startswith('b2b'):
                profile = Profile.search([
                    ('business_types', 'in', ['all', 'b2b']),
                    ('active', '=', True)
                ], limit=1)
            else:
                profile = Profile.search([
                    ('business_types', 'in', ['all', 'b2c']),
                    ('active', '=', True)
                ], limit=1)

            lead.scoring_profile_id = profile

    @api.depends('scoring_profile_id', 'business_type', 'expected_revenue',
                 'email_from', 'phone', 'partner_id', 'lead_source',
                 'probability', 'activity_ids')
    def _compute_lead_score_full(self):
        for lead in self:
            if lead.scoring_profile_id:
                result = lead.scoring_profile_id.calculate_score(lead)
                lead.lead_score = result['score']
                lead.score_details = "\n".join(result['details'])
                lead.lead_grade = result['grade']
            else:
                lead.lead_score = 0
                lead.score_details = "No scoring profile assigned"
                lead.lead_grade = 'frozen'

    def action_recalculate_score(self):
        """Manually trigger score recalculation"""
        for lead in self:
            lead._compute_lead_score_full()
            lead.score_last_updated = fields.Datetime.now()

        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'message': 'Lead scores recalculated',
                'type': 'success',
            }
        }

    @api.model
    def _cron_recalculate_scores(self):
        """Scheduled job to recalculate all lead scores"""
        leads = self.search([
            ('stage_id.is_won', '=', False),
            ('active', '=', True),
        ])

        for lead in leads:
            lead._compute_lead_score_full()
            lead.score_last_updated = fields.Datetime.now()

        _logger.info(f"Recalculated scores for {len(leads)} leads")
```

#### Day 143 Deliverables Checklist

- [ ] Lead scoring criteria model
- [ ] Scoring profile model
- [ ] Score calculation engine
- [ ] Lead grade assignment
- [ ] Scheduled score recalculation
- [ ] Manual recalculate action

---

### Day 144-145: Territory & Customer Management

*(Condensed for document length)*

#### Key Deliverables

```python
# smart_crm_dairy/models/sales_territory.py
class SalesTerritory(models.Model):
    _name = 'smart.sales.territory'
    _description = 'Sales Territory'
    _parent_name = 'parent_id'
    _parent_store = True

    name = fields.Char(string='Territory Name', required=True)
    code = fields.Char(string='Code', required=True)

    # Hierarchy
    parent_id = fields.Many2one('smart.sales.territory', string='Parent')
    parent_path = fields.Char(index=True, unaccent=False)
    child_ids = fields.One2many('smart.sales.territory', 'parent_id')

    # Geographic
    territory_type = fields.Selection([
        ('country', 'Country'),
        ('division', 'Division'),
        ('district', 'District'),
        ('upazila', 'Upazila/Sub-district'),
        ('area', 'Area'),
    ], string='Territory Type', required=True)

    # Bangladesh specific
    division_code = fields.Char(string='Division Code')
    district_code = fields.Char(string='District Code')

    # Assignment
    team_id = fields.Many2one('crm.team', string='Sales Team')
    manager_id = fields.Many2one('res.users', string='Territory Manager')

    # Statistics
    customer_count = fields.Integer(compute='_compute_stats')
    total_revenue = fields.Float(compute='_compute_stats')
```

---

### Day 146-147: Customer 360 Dashboard

#### Dev 3 Tasks (16h) - OWL Dashboard Component

```javascript
/** @odoo-module */
// smart_crm_dairy/static/src/js/customer_360.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { registry } from "@web/core/registry";
import { useService } from "@web/core/utils/hooks";

export class Customer360Dashboard extends Component {
    static template = "smart_crm_dairy.Customer360Dashboard";
    static props = {
        partnerId: { type: Number },
    };

    setup() {
        this.orm = useService("orm");
        this.action = useService("action");

        this.state = useState({
            customer: null,
            orders: [],
            leads: [],
            activities: [],
            loading: true,
        });

        onWillStart(async () => {
            await this.loadCustomerData();
        });
    }

    async loadCustomerData() {
        this.state.loading = true;

        try {
            // Load customer details
            const [customer] = await this.orm.read(
                "res.partner",
                [this.props.partnerId],
                [
                    "name", "email", "phone", "mobile",
                    "customer_segment", "b2b_customer_type_id",
                    "customer_tier_id", "loyalty_points",
                    "lifetime_value", "credit_limit",
                    "territory_id", "assigned_salesperson_id",
                    "order_frequency", "avg_order_value",
                    "last_order_date", "days_since_last_order",
                    "image_128"
                ]
            );
            this.state.customer = customer;

            // Load recent orders
            const orderIds = await this.orm.search(
                "sale.order",
                [["partner_id", "=", this.props.partnerId]],
                { limit: 10, order: "date_order desc" }
            );
            this.state.orders = await this.orm.read(
                "sale.order",
                orderIds,
                ["name", "date_order", "amount_total", "state"]
            );

            // Load opportunities
            const leadIds = await this.orm.search(
                "crm.lead",
                [["partner_id", "=", this.props.partnerId]],
                { limit: 5, order: "create_date desc" }
            );
            this.state.leads = await this.orm.read(
                "crm.lead",
                leadIds,
                ["name", "stage_id", "expected_revenue", "probability"]
            );

            // Load activities
            const activityIds = await this.orm.search(
                "mail.activity",
                [["res_id", "=", this.props.partnerId],
                 ["res_model", "=", "res.partner"]],
                { limit: 5, order: "date_deadline asc" }
            );
            this.state.activities = await this.orm.read(
                "mail.activity",
                activityIds,
                ["summary", "activity_type_id", "date_deadline", "user_id"]
            );

        } catch (error) {
            console.error("Error loading customer data:", error);
        }

        this.state.loading = false;
    }

    formatCurrency(value) {
        return new Intl.NumberFormat('en-BD', {
            style: 'currency',
            currency: 'BDT',
            minimumFractionDigits: 0,
        }).format(value || 0);
    }

    getTierBadgeClass() {
        const tier = this.state.customer?.customer_tier_id;
        if (!tier) return 'badge-secondary';

        const tierName = tier[1]?.toLowerCase() || '';
        if (tierName.includes('platinum')) return 'badge-dark';
        if (tierName.includes('gold')) return 'badge-warning';
        if (tierName.includes('silver')) return 'badge-secondary';
        return 'badge-info';
    }

    async openOrder(orderId) {
        await this.action.doAction({
            type: 'ir.actions.act_window',
            res_model: 'sale.order',
            res_id: orderId,
            views: [[false, 'form']],
            target: 'current',
        });
    }

    async createQuotation() {
        await this.action.doAction({
            type: 'ir.actions.act_window',
            res_model: 'sale.order',
            views: [[false, 'form']],
            target: 'current',
            context: {
                default_partner_id: this.props.partnerId,
            },
        });
    }
}

registry.category("actions").add("customer_360_dashboard", Customer360Dashboard);
```

```xml
<!-- smart_crm_dairy/static/src/xml/customer_360.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<templates xml:space="preserve">
    <t t-name="smart_crm_dairy.Customer360Dashboard">
        <div class="customer-360-dashboard p-4">
            <t t-if="state.loading">
                <div class="text-center py-5">
                    <i class="fa fa-spinner fa-spin fa-3x"/>
                    <p class="mt-2">Loading customer data...</p>
                </div>
            </t>
            <t t-else="">
                <!-- Customer Header -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <img t-if="state.customer.image_128"
                                     t-attf-src="data:image/png;base64,#{state.customer.image_128}"
                                     class="rounded-circle" width="80" height="80"/>
                                <div t-else="" class="bg-primary rounded-circle d-flex
                                     align-items-center justify-content-center"
                                     style="width:80px;height:80px;">
                                    <i class="fa fa-user fa-2x text-white"/>
                                </div>
                            </div>
                            <div class="col">
                                <h3 class="mb-1">
                                    <t t-esc="state.customer.name"/>
                                </h3>
                                <p class="text-muted mb-0">
                                    <i class="fa fa-envelope mr-2"/>
                                    <t t-esc="state.customer.email || 'No email'"/>
                                    <span class="mx-2">|</span>
                                    <i class="fa fa-phone mr-2"/>
                                    <t t-esc="state.customer.phone || 'No phone'"/>
                                </p>
                            </div>
                            <div class="col-auto">
                                <span t-attf-class="badge #{getTierBadgeClass()} badge-pill px-3 py-2">
                                    <t t-esc="state.customer.customer_tier_id?.[1] || 'No Tier'"/>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI Cards Row -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 opacity-75">Lifetime Value</h6>
                                <h3 class="card-title mb-0">
                                    <t t-esc="formatCurrency(state.customer.lifetime_value)"/>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 opacity-75">Avg Order Value</h6>
                                <h3 class="card-title mb-0">
                                    <t t-esc="formatCurrency(state.customer.avg_order_value)"/>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 opacity-75">Order Frequency</h6>
                                <h3 class="card-title mb-0">
                                    <t t-esc="(state.customer.order_frequency || 0).toFixed(1)"/>
                                    <small>/month</small>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2">Loyalty Points</h6>
                                <h3 class="card-title mb-0">
                                    <t t-esc="state.customer.loyalty_points || 0"/>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders and Activities Row -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Recent Orders</h5>
                                <button class="btn btn-sm btn-primary"
                                        t-on-click="createQuotation">
                                    <i class="fa fa-plus mr-1"/> New Order
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Order</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <t t-foreach="state.orders" t-as="order" t-key="order.id">
                                            <tr class="cursor-pointer"
                                                t-on-click="() => openOrder(order.id)">
                                                <td><t t-esc="order.name"/></td>
                                                <td><t t-esc="order.date_order"/></td>
                                                <td><t t-esc="formatCurrency(order.amount_total)"/></td>
                                                <td>
                                                    <span t-attf-class="badge badge-#{order.state === 'sale' ? 'success' : 'secondary'}">
                                                        <t t-esc="order.state"/>
                                                    </span>
                                                </td>
                                            </tr>
                                        </t>
                                        <t t-if="!state.orders.length">
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    No orders found
                                                </td>
                                            </tr>
                                        </t>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Upcoming Activities</h5>
                            </div>
                            <div class="card-body">
                                <t t-foreach="state.activities" t-as="activity" t-key="activity.id">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-light rounded p-2 mr-3">
                                            <i class="fa fa-calendar"/>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">
                                                <t t-esc="activity.summary || activity.activity_type_id[1]"/>
                                            </div>
                                            <small class="text-muted">
                                                <t t-esc="activity.date_deadline"/>
                                            </small>
                                        </div>
                                    </div>
                                </t>
                                <t t-if="!state.activities.length">
                                    <p class="text-muted text-center">No upcoming activities</p>
                                </t>
                            </div>
                        </div>
                    </div>
                </div>
            </t>
        </div>
    </t>
</templates>
```

---

### Day 148-149: Communication & Workflows

*(Key deliverables summary)*

- Automated follow-up workflow engine
- Communication history tracking model
- Email/SMS template configuration
- Activity scheduling automation
- CRM analytics reports (conversion funnel, win/loss, team performance)

---

### Day 150: Integration & Testing

#### Integration Test Suite

```python
# smart_crm_dairy/tests/test_crm_integration.py
from odoo.tests import tagged, TransactionCase
from datetime import date, timedelta

@tagged('post_install', '-at_install', 'smart_crm')
class TestCRMIntegration(TransactionCase):

    @classmethod
    def setUpClass(cls):
        super().setUpClass()

        # Create test customer tier
        cls.tier_gold = cls.env['smart.customer.tier'].create({
            'name': 'Gold',
            'code': 'GOLD',
            'min_lifetime_value': 100000,
            'discount_percentage': 10.0,
            'points_multiplier': 1.5,
        })

        # Create B2B customer type
        cls.b2b_distributor = cls.env['smart.b2b.customer.type'].create({
            'name': 'Test Distributor',
            'code': 'TEST_DIST',
            'category': 'distributor',
            'min_order_value': 50000,
            'discount_percentage': 12.0,
        })

        # Create territory
        cls.territory = cls.env['smart.sales.territory'].create({
            'name': 'Dhaka Division',
            'code': 'DHK',
            'territory_type': 'division',
        })

        # Create sales team
        cls.team = cls.env['crm.team'].create({
            'name': 'B2B Distribution Team',
            'team_type': 'b2b_distribution',
        })

    def test_customer_segmentation(self):
        """Test customer segment assignment"""
        partner = self.env['res.partner'].create({
            'name': 'Test B2B Customer',
            'customer_segment': 'b2b',
            'b2b_customer_type_id': self.b2b_distributor.id,
        })

        self.assertEqual(partner.customer_segment, 'b2b')
        self.assertEqual(partner.b2b_customer_type_id, self.b2b_distributor)

    def test_customer_tier_computation(self):
        """Test automatic tier assignment based on lifetime value"""
        partner = self.env['res.partner'].create({
            'name': 'High Value Customer',
            'customer_rank': 1,
        })

        # Create order to generate lifetime value
        order = self.env['sale.order'].create({
            'partner_id': partner.id,
        })
        order.write({
            'order_line': [(0, 0, {
                'product_id': self.env.ref('product.product_product_4').id,
                'product_uom_qty': 100,
                'price_unit': 1500,
            })]
        })
        order.action_confirm()

        # Recompute lifetime value
        partner._compute_lifetime_value()
        partner._compute_customer_tier()

        self.assertGreater(partner.lifetime_value, 0)

    def test_lead_scoring(self):
        """Test lead score calculation"""
        lead = self.env['crm.lead'].create({
            'name': 'Test Lead',
            'business_type': 'b2b_distributor',
            'expected_revenue': 500000,
            'email_from': 'test@example.com',
            'phone': '+8801712345678',
            'lead_source': 'referral',
        })

        # Score should be calculated
        self.assertGreater(lead.lead_score, 0)
        self.assertTrue(lead.score_details)

    def test_lead_assignment(self):
        """Test automatic lead assignment"""
        # Create assignment rule
        rule = self.env['smart.lead.assignment.rule'].create({
            'name': 'Distributor Rule',
            'business_type': 'b2b_distributor',
            'assign_to': 'team',
            'team_id': self.team.id,
        })

        # Create lead matching rule
        lead = self.env['crm.lead'].create({
            'name': 'New Distributor Lead',
            'business_type': 'b2b_distributor',
        })

        # Check assignment
        self.assertEqual(lead.team_id, self.team)

    def test_territory_hierarchy(self):
        """Test territory parent-child relationships"""
        district = self.env['smart.sales.territory'].create({
            'name': 'Dhaka District',
            'code': 'DHK-D',
            'territory_type': 'district',
            'parent_id': self.territory.id,
        })

        self.assertEqual(district.parent_id, self.territory)
        self.assertIn(district, self.territory.child_ids)

    def test_pipeline_stages(self):
        """Test CRM pipeline stage progression"""
        lead = self.env['crm.lead'].create({
            'name': 'Pipeline Test Lead',
            'type': 'opportunity',
        })

        stages = self.env['crm.stage'].search([
            ('is_dairy_stage', '=', True)
        ], order='sequence')

        self.assertTrue(stages, "Dairy pipeline stages should exist")

        # Progress through stages
        for stage in stages[:3]:
            lead.stage_id = stage
            self.assertEqual(lead.stage_id, stage)
```

---

## 4. Technical Specifications Summary

### 4.1 Module Manifest

```python
# smart_crm_dairy/__manifest__.py
{
    'name': 'Smart Dairy CRM',
    'version': '19.0.1.0.0',
    'category': 'Sales/CRM',
    'summary': 'Advanced CRM for Smart Dairy',
    'depends': [
        'crm',
        'sale',
        'sale_crm',
        'contacts',
        'smart_bd_local',  # Bangladesh localization
    ],
    'data': [
        'security/ir.model.access.csv',
        'security/crm_security.xml',
        'data/b2b_customer_types.xml',
        'data/customer_tiers.xml',
        'data/crm_stages.xml',
        'data/crm_lost_reasons.xml',
        'data/scoring_criteria.xml',
        'data/bd_territories.xml',
        'views/b2b_customer_type_views.xml',
        'views/customer_segment_views.xml',
        'views/customer_tier_views.xml',
        'views/sales_territory_views.xml',
        'views/crm_lead_views.xml',
        'views/crm_team_views.xml',
        'views/res_partner_views.xml',
        'views/lead_assignment_views.xml',
        'views/menus.xml',
    ],
    'assets': {
        'web.assets_backend': [
            'smart_crm_dairy/static/src/js/**/*',
            'smart_crm_dairy/static/src/xml/**/*',
            'smart_crm_dairy/static/src/scss/**/*',
        ],
    },
    'installable': True,
    'application': False,
    'auto_install': False,
    'license': 'LGPL-3',
}
```

### 4.2 Security Access Rules

```csv
# smart_crm_dairy/security/ir.model.access.csv
id,name,model_id:id,group_id:id,perm_read,perm_write,perm_create,perm_unlink
access_b2b_type_user,smart.b2b.customer.type.user,model_smart_b2b_customer_type,sales_team.group_sale_salesman,1,0,0,0
access_b2b_type_manager,smart.b2b.customer.type.manager,model_smart_b2b_customer_type,sales_team.group_sale_manager,1,1,1,1
access_customer_segment_user,smart.customer.segment.user,model_smart_customer_segment,sales_team.group_sale_salesman,1,0,0,0
access_customer_segment_manager,smart.customer.segment.manager,model_smart_customer_segment,sales_team.group_sale_manager,1,1,1,1
access_customer_tier_user,smart.customer.tier.user,model_smart_customer_tier,sales_team.group_sale_salesman,1,0,0,0
access_customer_tier_manager,smart.customer.tier.manager,model_smart_customer_tier,sales_team.group_sale_manager,1,1,1,1
access_territory_user,smart.sales.territory.user,model_smart_sales_territory,sales_team.group_sale_salesman,1,0,0,0
access_territory_manager,smart.sales.territory.manager,model_smart_sales_territory,sales_team.group_sale_manager,1,1,1,1
access_scoring_criteria_manager,smart.lead.scoring.criteria.manager,model_smart_lead_scoring_criteria,sales_team.group_sale_manager,1,1,1,1
access_assignment_rule_manager,smart.lead.assignment.rule.manager,model_smart_lead_assignment_rule,sales_team.group_sale_manager,1,1,1,1
```

---

## 5. Risk Register

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Complex scoring algorithm performance | Medium | Medium | Cache scores, batch recalculation |
| Territory data incomplete | Medium | Low | Use GPS fallback for territory assignment |
| Customer 360 dashboard slow | Medium | High | Lazy loading, pagination, caching |
| Lead assignment conflicts | Low | Medium | Rule priority, manual override option |

---

## 6. Dependencies & Handoffs

### From Previous Milestones
- Milestone 14: Bangladesh localization (territories, currency)
- Milestone 12: Quality module (for customer feedback integration)

### To Next Milestones
- Milestone 16: B2B Portal (customer segments, tiers, pricing)
- Milestone 18: Payment Gateway (customer credit limits)
- Milestone 19: Reporting (CRM analytics data)

---

## 7. Milestone Completion Checklist

- [ ] Customer segmentation fully operational
- [ ] All B2B customer types configured
- [ ] Customer tier system functional
- [ ] Sales pipeline with 7 stages operational
- [ ] Lead scoring engine accurate (>85%)
- [ ] Territory hierarchy complete (64 districts)
- [ ] Customer 360 dashboard loads <2 seconds
- [ ] Automated lead assignment working
- [ ] All unit tests passing
- [ ] Integration tests passing
- [ ] Documentation complete

---

**End of Milestone 15 Documentation**
