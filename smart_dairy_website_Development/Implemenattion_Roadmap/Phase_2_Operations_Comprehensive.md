# Smart Dairy Smart Portal + ERP System
## Phase 2: Operations Implementation Roadmap
### B2C E-commerce, Mobile Applications & Advanced Operations

---

**Document Information**

| Attribute | Details |
|-----------|---------|
| **Document Version** | 2.0 - Comprehensive Edition |
| **Release Date** | February 2026 |
| **Classification** | Implementation Guide - Internal Use |
| **Total Duration** | 100 Days (10 Milestones x 10 Days) |
| **Timeline** | Days 101-200 |
| **Team Size** | 3 Full Stack Developers |
| **Budget** | BDT 1.35 Crore |

---

## 1. EXECUTIVE SUMMARY

### 1.1 Phase 2 Vision

Phase 2 transforms Smart Dairy foundational platform into a fully operational digital ecosystem by launching the B2C E-commerce Platform, deploying Mobile Applications for customers and field staff, implementing complete Payment Gateway Integration, and expanding Farm Management capabilities.

### 1.2 Primary Objectives

| Objective | Target | Success Metric |
|-----------|--------|----------------|
| B2C E-commerce | Full-featured online store | First order within 24h |
| Subscription Engine | Recurring delivery system | 100+ active subscriptions |
| Payment Integration | All MFS plus Cards | 99.9% success rate |
| Customer Mobile App | iOS and Android | 500+ downloads |
| Field Sales App | Order taking and CRM | 10+ staff using daily |
| Advanced Farm Mgmt | Health, breeding, genetics | All events tracked |
| Reporting Suite | Operational dashboards | 20+ reports |

### 1.3 Budget Summary

| Category | Amount (BDT) |
|----------|--------------|
| Human Resources | 48,00,000 |
| Cloud Infrastructure | 20,00,000 |
| Mobile Development Tools | 8,00,000 |
| Payment Gateway Integration | 15,00,000 |
| Third-party APIs | 10,00,000 |
| App Store Compliance | 5,00,000 |
| Training & Change Mgmt | 12,00,000 |
| Contingency (15%) | 17,55,000 |
| **Total** | **1,35,55,000** |

---

## 2. PHASE 2 DEPENDENCIES

| Phase 1 Deliverable | Status Required |
|--------------------|-----------------|
| Odoo 19 CE Core | Operational |
| Product Catalog | Complete |
| Customer Database | Migrated |
| Payment Gateway Foundation | Ready |
| Farm Management Module | Base working |
| Infrastructure | Scaled up |

---

## 3. TEAM STRUCTURE

### 3.1 Developer Allocation

| Role | Focus Area | Key Responsibilities |
|------|------------|---------------------|
| **Lead Dev** | Architecture, Backend, DevOps | E-commerce backend, Payment APIs, Mobile BFF, Elasticsearch |
| **Backend Dev** | Odoo modules, Database | Subscription engine, Farm advanced, Reports, Payment connectors |
| **Frontend Dev** | Mobile Apps, UI/UX | Flutter apps, Customer portal, Field app, App store submission |

### 3.2 Technology Stack Additions

| Layer | Technology | Version |
|-------|-----------|---------|
| Mobile Framework | Flutter | 3.16+ |
| Mobile Language | Dart | 3.2+ |
| State Management | Provider/Riverpod | Latest |
| Local DB | Hive/SQLite | Latest |
| Push Notifications | Firebase FCM | Latest |
| API Gateway | FastAPI | 0.104+ |
| Search Engine | Elasticsearch | 8.x |
| Cache | Redis Cluster | 7.x |

---


## 4. MILESTONE 11: B2C E-commerce Foundation (Days 101-110)

### 4.1 Objective Statement

Install and configure Odoo e-commerce module (website_sale), setup product catalog for online sales, implement shopping cart functionality, configure checkout workflow with delivery address management and delivery slot selection.

### 4.2 Success Criteria
- Odoo website_sale module installed and configured
- Product catalog displaying 15+ products online with images and descriptions
- Shopping cart functional with add/remove/update capabilities
- Guest checkout enabled for non-registered customers
- Delivery address management with geocoding
- Delivery time slot selection (Morning/Evening slots)

### 4.3 Deliverables Table

| ID | Deliverable | Owner | Verification Method | Status |
|----|-------------|-------|---------------------|--------|
| M11-D1 | website_sale module installation | Lead Dev | Module active in Odoo | ⬜ |
| M11-D2 | E-commerce theme customization | Frontend Dev | Theme matches brand guidelines | ⬜ |
| M11-D3 | Product catalog configuration | Backend Dev | 15+ products online | ⬜ |
| M11-D4 | Shopping cart implementation | Frontend Dev | Cart functional test passed | ⬜ |
| M11-D5 | Checkout workflow setup | Backend Dev | Checkout flow working | ⬜ |
| M11-D6 | Guest checkout enabled | Backend Dev | Guest order placed | ⬜ |
| M11-D7 | Delivery address management | Backend Dev | Multiple addresses saved | ⬜ |
| M11-D8 | Delivery slot selection | Backend Dev | Time slots configured | ⬜ |
| M11-D9 | Order confirmation system | Backend Dev | Email/SMS sent | ⬜ |
| M11-D10 | E-commerce testing complete | All Dev | Test cases passed | ⬜ |

### 4.4 Technical Implementation Details

#### Odoo E-commerce Module Configuration

```python
# __manifest__.py for E-commerce Extension
{
    'name': 'Smart Dairy E-commerce Extension',
    'version': '1.0.0',
    'category': 'Website',
    'summary': 'B2C E-commerce for Smart Dairy',
    'depends': ['website', 'website_sale', 'product', 'delivery'],
    'data': [
        'views/product_template_views.xml',
        'views/website_sale_templates.xml',
        'views/delivery_slot_views.xml',
        'data/product_data.xml',
        'data/delivery_slots.xml',
    ],
    'assets': {
        'web.assets_frontend': [
            'smart_dairy_ecommerce/static/src/scss/shop.scss',
            'smart_dairy_ecommerce/static/src/js/shop.js',
        ],
    },
    'installable': True,
    'application': False,
    'license': 'LGPL-3',
}
```

#### Product Model Extension

```python
# models/product_template.py
from odoo import models, fields, api

class ProductTemplate(models.Model):
    _inherit = 'product.template'
    
    # Smart Dairy specific fields
    is_dairy_product = fields.Boolean('Is Dairy Product', default=False)
    is_subscription_eligible = fields.Boolean('Eligible for Subscription', default=False)
    shelf_life_days = fields.Integer('Shelf Life (Days)')
    storage_temperature = fields.Char('Storage Temperature')
    fat_percentage = fields.Float('Fat %')
    snf_percentage = fields.Float('SNF %')
    protein_percentage = fields.Float('Protein %')
    
    # Nutritional information
    calories_per_serving = fields.Integer('Calories per Serving')
    serving_size = fields.Char('Serving Size')
    allergens = fields.Text('Allergens')
    
    # Subscription options
    subscription_frequencies = fields.Many2many(
        'product.subscription.frequency',
        string='Available Subscription Frequencies'
    )
    
    # Delivery slots
    available_delivery_slots = fields.Many2many(
        'delivery.time.slot',
        string='Available Delivery Slots'
    )
```

#### Delivery Slot Model

```python
# models/delivery_slot.py
from odoo import models, fields, api

class DeliveryTimeSlot(models.Model):
    _name = 'delivery.time.slot'
    _description = 'Delivery Time Slot'
    _order = 'sequence'
    
    name = fields.Char('Slot Name', required=True)
    sequence = fields.Integer('Sequence', default=10)
    start_time = fields.Float('Start Time', required=True)
    end_time = fields.Float('End Time', required=True)
    
    # Time slot options
    slot_type = fields.Selection([
        ('morning_early', 'Morning (6:00 AM - 8:00 AM)'),
        ('morning_late', 'Morning (8:00 AM - 10:00 AM)'),
        ('evening_early', 'Evening (4:00 PM - 6:00 PM)'),
        ('evening_late', 'Evening (6:00 PM - 8:00 PM)'),
    ], string='Slot Type', required=True)
    
    max_orders = fields.Integer('Maximum Orders per Slot', default=50)
    active = fields.Boolean('Active', default=True)
    
    # Computed fields
    display_name = fields.Char('Display Name', compute='_compute_display_name')
    
    @api.depends('start_time', 'end_time')
    def _compute_display_name(self):
        for slot in self:
            start = self._format_time(slot.start_time)
            end = self._format_time(slot.end_time)
            slot.display_name = f"{start} - {end}"
    
    def _format_time(self, time_float):
        hours = int(time_float)
        minutes = int((time_float - hours) * 60)
        period = 'AM' if hours < 12 else 'PM'
        display_hours = hours if hours <= 12 else hours - 12
        return f"{display_hours}:{minutes:02d} {period}"
```

### 4.5 Daily Task Allocation (Days 101-110)

| Day | Lead Dev Tasks | Backend Dev Tasks | Frontend Dev Tasks |
|-----|----------------|-------------------|-------------------|
| 101 | Install website_sale module; Configure base settings | Assist installation; Document issues | Review theme requirements; Prepare design assets |
| 102 | Configure e-commerce settings; Tax integration | Setup product categories; Configure taxes | Customize theme colors; Brand integration |
| 103 | Review product data integration | Import 15+ product data; Setup variants | Design product card component; Image optimization |
| 104 | Test product display; Configure image sizes | Configure product images; SEO metadata | Implement product grid layout; Responsive design |
| 105 | Review cart requirements; Architecture review | Implement cart backend; Session management | Develop cart UI; Add to cart functionality |
| 106 | Test cart functionality; Fix backend issues | Add cart calculations; Promotions logic | Implement cart actions; Quantity update |
| 107 | Review checkout flow; Security audit | Configure checkout steps; Address validation | Design checkout UI; Form validation |
| 108 | Test checkout process; Performance check | Implement delivery slot logic; Payment connector | Implement checkout forms; Delivery slot selection |
| 109 | Configure email/SMS notifications | Setup email templates; SMS triggers | Design confirmation UI; Success page |
| 110 | E2E testing; Bug fixes; M11 Review | Fix backend issues; Data validation | Fix UI issues; Cross-browser testing |

---

## 5. MILESTONE 12: Product Catalog & Shopping Experience (Days 111-120)

### 5.1 Objective Statement

Enhance product catalog with advanced features including Elasticsearch integration for fast product search, category filters, product reviews and ratings, wishlist functionality, related products recommendation engine, and product comparison feature.

### 5.2 Success Criteria
- Elasticsearch installed and integrated with Odoo
- Product search with autocomplete and suggestions working
- Category and attribute filters functional
- Product reviews and ratings system operational
- Wishlist functionality for logged-in users
- Related products displayed on product pages

### 5.3 Deliverables Table

| ID | Deliverable | Owner | Verification Method | Status |
|----|-------------|-------|---------------------|--------|
| M12-D1 | Elasticsearch installation | Lead Dev | ES running on port 9200 | ⬜ |
| M12-D2 | Product search implementation | Backend Dev | Search returning results | ⬜ |
| M12-D3 | Category filters | Frontend Dev | Filters working | ⬜ |
| M12-D4 | Product reviews system | Backend Dev | Reviews can be posted | ⬜ |
| M12-D5 | Wishlist functionality | Backend Dev | Items can be saved | ⬜ |
| M12-D6 | Related products engine | Backend Dev | Recommendations showing | ⬜ |
| M12-D7 | Product comparison | Frontend Dev | Compare feature working | ⬜ |
| M12-D8 | Recently viewed products | Backend Dev | History tracking | ⬜ |
| M12-D9 | Product sharing | Frontend Dev | Social sharing working | ⬜ |
| M12-D10 | Catalog testing | All Dev | All features tested | ⬜ |

### 5.4 Technical Implementation

#### Elasticsearch Integration

```python
# models/product_template.py
from odoo import models, fields, api
import json

class ProductTemplate(models.Model):
    _inherit = 'product.template'
    
    def _prepare_elasticsearch_doc(self):
        self.ensure_one()
        return {
            'id': self.id,
            'name': self.name,
            'description': self.description or '',
            'description_sale': self.description_sale or '',
            'list_price': self.list_price,
            'standard_price': self.standard_price,
            'categ_id': self.categ_id.name if self.categ_id else '',
            'categ_ids': [c.name for c in self.categ_ids],
            'attribute_line_ids': [
                {
                    'attribute_id': al.attribute_id.name,
                    'value_ids': [v.name for v in al.value_ids]
                }
                for al in self.attribute_line_ids
            ],
            'is_published': self.is_published,
            'website_url': self.website_url or '',
        }
    
    def index_to_elasticsearch(self):
        es_doc = self._prepare_elasticsearch_doc()
        # Elasticsearch indexing logic
        return True
```

#### Product Review Model

```python
# models/product_review.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError

class ProductReview(models.Model):
    _name = 'product.review'
    _description = 'Product Review'
    _order = 'create_date desc'
    
    product_id = fields.Many2one('product.template', 'Product', required=True, index=True)
    partner_id = fields.Many2one('res.partner', 'Customer', required=True)
    user_id = fields.Many2one('res.users', 'User', required=True, default=lambda self: self.env.user)
    
    rating = fields.Integer('Rating', required=True, default=5)
    title = fields.Char('Review Title', required=True)
    review_text = fields.Text('Review', required=True)
    
    # Review status
    state = fields.Selection([
        ('pending', 'Pending Approval'),
        ('approved', 'Approved'),
        ('rejected', 'Rejected'),
    ], string='Status', default='pending', required=True)
    
    # Helpful counts
    helpful_count = fields.Integer('Helpful Votes', default=0)
    not_helpful_count = fields.Integer('Not Helpful Votes', default=0)
    
    # Verified purchase
    is_verified_purchase = fields.Boolean('Verified Purchase', default=False)
    order_id = fields.Many2one('sale.order', 'Order Reference')
    
    @api.constrains('rating')
    def _check_rating(self):
        for review in self:
            if review.rating < 1 or review.rating > 5:
                raise ValidationError('Rating must be between 1 and 5 stars.')
```

---


## 6. MILESTONE 13: Subscription Management System (Days 121-130)

### 6.1 Objective Statement

Develop comprehensive subscription engine for recurring milk delivery including daily, weekly, and monthly subscription options, pause and resume functionality, skip delivery capability, auto-renewal with automatic payment processing, and customer subscription dashboard.

### 6.2 Success Criteria
- Subscription model created with all required fields
- Daily, weekly, monthly, and alternate day frequencies available
- Pause and resume subscription functionality working
- Skip delivery feature with reason capture
- Auto-renewal with automatic payment processing
- Subscription dashboard for customers

### 6.3 Deliverables Table

| ID | Deliverable | Owner | Verification Method | Status |
|----|-------------|-------|---------------------|--------|
| M13-D1 | Subscription module creation | Backend Dev | Module installed | ⬜ |
| M13-D2 | Subscription model definition | Backend Dev | Model with all fields | ⬜ |
| M13-D3 | Frequency configuration | Backend Dev | All frequencies available | ⬜ |
| M13-D4 | Delivery schedule management | Backend Dev | Calendar generation working | ⬜ |
| M13-D5 | Pause/resume functionality | Backend Dev | Status changes working | ⬜ |
| M13-D6 | Skip delivery feature | Backend Dev | Skip reason captured | ⬜ |
| M13-D7 | Auto-renewal logic | Backend Dev | Renewals processed | ⬜ |
| M13-D8 | Subscription dashboard UI | Frontend Dev | Dashboard accessible | ⬜ |
| M13-D9 | Cron job for order generation | Lead Dev | Orders auto-generated | ⬜ |
| M13-D10 | Subscription testing | All Dev | All scenarios tested | ⬜ |

### 6.4 Technical Implementation

#### Subscription Model

```python
# models/sale_subscription.py
from odoo import models, fields, api, _
from odoo.exceptions import UserError, ValidationError
from dateutil.relativedelta import relativedelta
from datetime import datetime, timedelta

class SaleSubscription(models.Model):
    _name = 'sale.subscription'
    _description = 'Sale Subscription'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'create_date desc'
    
    # Identification
    name = fields.Char('Subscription Reference', required=True, copy=False, 
                       readonly=True, default=lambda self: _('New'))
    
    # Customer Information
    partner_id = fields.Many2one('res.partner', 'Customer', required=True, 
                                  domain=[('customer_rank', '>', 0)])
    partner_invoice_id = fields.Many2one('res.partner', 'Invoice Address')
    partner_shipping_id = fields.Many2one('res.partner', 'Delivery Address')
    
    # Product Information
    product_id = fields.Many2one('product.product', 'Product', required=True,
                                  domain=[('is_subscription_eligible', '=', True)])
    quantity = fields.Float('Quantity', required=True, default=1.0)
    uom_id = fields.Many2one('uom.uom', 'Unit of Measure', related='product_id.uom_id')
    
    # Pricing
    price_unit = fields.Float('Unit Price', required=True, digits='Product Price')
    subtotal = fields.Float('Subtotal', compute='_compute_amounts', store=True)
    tax_id = fields.Many2many('account.tax', string='Taxes')
    total = fields.Float('Total', compute='_compute_amounts', store=True)
    
    # Subscription Configuration
    frequency = fields.Selection([
        ('daily', 'Daily'),
        ('alternate', 'Alternate Days'),
        ('weekly', 'Weekly'),
        ('bi_weekly', 'Bi-Weekly'),
        ('monthly', 'Monthly'),
    ], string='Frequency', required=True, default='daily')
    
    delivery_time_slot = fields.Many2one('delivery.time.slot', 'Preferred Delivery Time')
    
    # Schedule
    start_date = fields.Date('Start Date', required=True, default=fields.Date.today)
    end_date = fields.Date('End Date')
    next_delivery_date = fields.Date('Next Delivery Date', compute='_compute_next_delivery', store=True)
    next_billing_date = fields.Date('Next Billing Date', compute='_compute_next_billing', store=True)
    
    # Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('active', 'Active'),
        ('paused', 'Paused'),
        ('cancelled', 'Cancelled'),
        ('expired', 'Expired'),
    ], string='Status', default='draft', tracking=True)
    
    # Payment
    payment_method = fields.Selection([
        ('bkash', 'bKash'),
        ('nagad', 'Nagad'),
        ('rocket', 'Rocket'),
        ('card', 'Credit/Debit Card'),
        ('cod', 'Cash on Delivery'),
        ('wallet', 'Smart Wallet'),
    ], string='Payment Method', required=True)
    
    auto_renew = fields.Boolean('Auto-Renew', default=True)
    
    # Skip Management
    skip_count = fields.Integer('Total Skips', compute='_compute_skip_count')
    subscription_line_ids = fields.One2many('sale.subscription.line', 'subscription_id', 
                                            'Subscription Lines')
    
    # Computed fields
    @api.depends('quantity', 'price_unit', 'tax_id')
    def _compute_amounts(self):
        for subscription in self:
            subtotal = subscription.quantity * subscription.price_unit
            taxes = subscription.tax_id.compute_all(subtotal)
            subscription.subtotal = subtotal
            subscription.total = taxes['total_included']
    
    @api.depends('frequency', 'start_date', 'subscription_line_ids')
    def _compute_next_delivery(self):
        for subscription in self:
            if subscription.state != 'active':
                subscription.next_delivery_date = False
                continue
            # Logic to calculate next delivery date
            last_delivery = subscription.subscription_line_ids.filtered(
                lambda l: l.delivery_date
            ).sorted('delivery_date', reverse=True)[:1]
            
            if last_delivery:
                base_date = last_delivery.delivery_date
            else:
                base_date = subscription.start_date
            
            subscription.next_delivery_date = subscription._get_next_date(base_date)
    
    def _get_next_date(self, base_date):
        self.ensure_one()
        if self.frequency == 'daily':
            return base_date + timedelta(days=1)
        elif self.frequency == 'alternate':
            return base_date + timedelta(days=2)
        elif self.frequency == 'weekly':
            return base_date + timedelta(weeks=1)
        elif self.frequency == 'bi_weekly':
            return base_date + timedelta(weeks=2)
        elif self.frequency == 'monthly':
            return base_date + relativedelta(months=1)
        return base_date
    
    def action_pause(self):
        self.write({'state': 'paused'})
    
    def action_resume(self):
        self.write({'state': 'active'})
    
    def action_cancel(self):
        self.write({'state': 'cancelled'})
    
    def generate_orders(self):
        # Cron job method to generate sale orders from subscriptions
        subscriptions = self.search([
            ('state', '=', 'active'),
            ('next_delivery_date', '<=', fields.Date.today()),
        ])
        for subscription in subscriptions:
            # Create sale order logic
            pass


class SaleSubscriptionLine(models.Model):
    _name = 'sale.subscription.line'
    _description = 'Subscription Line'
    _order = 'delivery_date desc'
    
    subscription_id = fields.Many2one('sale.subscription', 'Subscription', required=True)
    delivery_date = fields.Date('Delivery Date', required=True)
    state = fields.Selection([
        ('scheduled', 'Scheduled'),
        ('skipped', 'Skipped'),
        ('delivered', 'Delivered'),
        ('cancelled', 'Cancelled'),
    ], string='Status', default='scheduled')
    skip_reason = fields.Text('Skip Reason')
    sale_order_id = fields.Many2one('sale.order', 'Sale Order')
```

#### Subscription Cron Job

```python
# data/ir_cron.xml
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <record id="ir_cron_generate_subscription_orders" model="ir.cron">
        <field name="name">Generate Subscription Orders</field>
        <field name="model_id" ref="model_sale_subscription"/>
        <field name="state">code</field>
        <field name="code">model.generate_orders()</field>
        <field name="interval_number">1</field>
        <field name="interval_type">days</field>
        <field name="numbercall">-1</field>
        <field name="doall" eval="False"/>
        <field name="active" eval="True"/>
    </record>
</odoo>
```

---

## 7. MILESTONE 14: Payment Gateway Integration (Days 131-140)

### 7.1 Objective Statement

Complete integration of all payment methods including bKash, Nagad, Rocket, Credit/Debit Cards through SSLCommerz, Cash on Delivery, and Smart Wallet. Implement PCI DSS compliance, payment reconciliation, and refund processing.

### 7.2 Success Criteria
- bKash payment gateway fully integrated with sandbox and production
- Nagad payment gateway integrated
- Rocket payment gateway integrated
- Credit/Debit card payments through SSLCommerz working
- Cash on Delivery option available
- Smart Wallet for refunds and credits operational
- PCI DSS compliance achieved
- Payment reconciliation reports available

### 7.3 Deliverables Table

| ID | Deliverable | Owner | Verification Method | Status |
|----|-------------|-------|---------------------|--------|
| M14-D1 | bKash payment integration | Backend Dev | bKash payments working | ⬜ |
| M14-D2 | Nagad payment integration | Backend Dev | Nagad payments working | ⬜ |
| M14-D3 | Rocket payment integration | Backend Dev | Rocket payments working | ⬜ |
| M14-D4 | SSLCommerz card integration | Backend Dev | Card payments working | ⬜ |
| M14-D5 | Cash on Delivery setup | Backend Dev | COD orders processed | ⬜ |
| M14-D6 | Smart Wallet implementation | Backend Dev | Wallet credits working | ⬜ |
| M14-D7 | Refund processing | Backend Dev | Refunds processed | ⬜ |
| M14-D8 | Payment reconciliation | Lead Dev | Reconciliation report | ⬜ |
| M14-D9 | PCI DSS compliance | Lead Dev | Compliance audit passed | ⬜ |
| M14-D10 | Payment testing | All Dev | All gateways tested | ⬜ |

### 7.4 Technical Implementation

#### Payment Gateway Base Model

```python
# models/payment_gateway.py
from odoo import models, fields, api, _
from odoo.exceptions import UserError
import requests
import json
import hashlib
import hmac

class PaymentGateway(models.Model):
    _name = 'payment.gateway'
    _description = 'Payment Gateway'
    
    name = fields.Char('Gateway Name', required=True)
    code = fields.Selection([
        ('bkash', 'bKash'),
        ('nagad', 'Nagad'),
        ('rocket', 'Rocket'),
        ('sslcommerz', 'SSLCommerz'),
        ('cod', 'Cash on Delivery'),
        ('wallet', 'Smart Wallet'),
    ], string='Gateway Code', required=True)
    
    # Configuration
    sandbox_mode = fields.Boolean('Sandbox Mode', default=True)
    merchant_id = fields.Char('Merchant ID')
    merchant_key = fields.Char('Merchant Key', password=True)
    merchant_secret = fields.Char('Merchant Secret', password=True)
    
    # API Endpoints
    sandbox_url = fields.Char('Sandbox URL')
    production_url = fields.Char('Production URL')
    
    # Status
    active = fields.Boolean('Active', default=True)
    
    def get_base_url(self):
        self.ensure_one()
        return self.sandbox_url if self.sandbox_mode else self.production_url
    
    def process_payment(self, order, **kwargs):
        self.ensure_one()
        if self.code == 'bkash':
            return self._process_bkash(order, **kwargs)
        elif self.code == 'nagad':
            return self._process_nagad(order, **kwargs)
        elif self.code == 'rocket':
            return self._process_rocket(order, **kwargs)
        elif self.code == 'sslcommerz':
            return self._process_sslcommerz(order, **kwargs)
        elif self.code == 'cod':
            return self._process_cod(order, **kwargs)
        return False
    
    def _process_bkash(self, order, **kwargs):
        # bKash payment integration
        url = f"{self.get_base_url()}/checkout/create"
        headers = {
            'Content-Type': 'application/json',
            'Authorization': self._get_bkash_token(),
            'X-APP-Key': self.merchant_key,
        }
        payload = {
            'amount': str(order.amount_total),
            'currency': 'BDT',
            'intent': 'sale',
            'merchantInvoiceNumber': order.name,
        }
        response = requests.post(url, headers=headers, json=payload)
        return response.json()
    
    def _get_bkash_token(self):
        # Token generation logic for bKash
        url = f"{self.get_base_url()}/token/grant"
        payload = {
            'app_key': self.merchant_key,
            'app_secret': self.merchant_secret,
        }
        response = requests.post(url, json=payload)
        if response.status_code == 200:
            return response.json().get('id_token')
        return None
```

#### bKash Payment Provider

```python
# models/payment_bkash.py
from odoo import models, fields, api
import requests
import json

class PaymentTransactionBkash(models.Model):
    _inherit = 'payment.transaction'
    
    bkash_payment_id = fields.Char('bKash Payment ID')
    bkash_trx_id = fields.Char('bKash Transaction ID')
    bkash_customer_msisdn = fields.Char('Customer Phone')
    
    def _send_payment_request_bkash(self):
        self.ensure_one()
        provider = self.provider_id
        
        # Get auth token
        token = self._get_bkash_auth_token(provider)
        if not token:
            return {'error': 'Failed to get auth token'}
        
        # Create payment
        url = f"{provider.get_base_url()}/create"
        headers = {
            'Authorization': f'Bearer {token}',
            'X-APP-Key': provider.bkash_app_key,
            'Content-Type': 'application/json',
        }
        payload = {
            'amount': str(self.amount),
            'currency': 'BDT',
            'intent': 'sale',
            'merchantInvoiceNumber': self.reference,
            'callbackURL': self._get_callback_url(),
        }
        
        response = requests.post(url, headers=headers, json=payload)
        result = response.json()
        
        if response.status_code == 200 and result.get('paymentID'):
            self.bkash_payment_id = result['paymentID']
            return {
                'payment_id': result['paymentID'],
                'bkash_url': result.get('bkashURL'),
                'status': 'pending',
            }
        return {'error': result.get('errorMessage', 'Unknown error')}
```

---


## 8. MILESTONE 15: Mobile App Architecture & Setup (Days 141-150)

### 8.1 Objective Statement

Setup Flutter development environment, create project structure with Clean Architecture pattern, implement BLoC state management, configure Firebase integration for push notifications and analytics, setup HTTP client with Dio, local storage with Hive, and establish API client for Odoo backend communication.

### 8.2 Success Criteria
- Flutter 3.16+ installed and development environment configured
- Project structure following Clean Architecture established
- BLoC pattern implemented with flutter_bloc package
- Firebase configured with FCM for push notifications
- Dio HTTP client configured with interceptors
- Hive local database setup for offline storage
- API client for Odoo communication working
- CI/CD pipeline for mobile builds established

### 8.3 Deliverables Table

| ID | Deliverable | Owner | Verification Method | Status |
|----|-------------|-------|---------------------|--------|
| M15-D1 | Flutter environment setup | Frontend Dev | flutter doctor passes | ⬜ |
| M15-D2 | Project architecture setup | Frontend Dev | Clean arch structure | ⬜ |
| M15-D3 | BLoC state management | Frontend Dev | State management working | ⬜ |
| M15-D4 | Firebase configuration | Frontend Dev | Firebase connected | ⬜ |
| M15-D5 | Push notification setup | Frontend Dev | FCM working | ⬜ |
| M15-D6 | Dio HTTP client setup | Frontend Dev | API calls working | ⬜ |
| M15-D7 | Hive local storage | Frontend Dev | Offline storage working | ⬜ |
| M15-D8 | Odoo API client | Frontend Dev | Authentication working | ⬜ |
| M15-D9 | Dependency injection | Frontend Dev | GetIt configured | ⬜ |
| M15-D10 | CI/CD pipeline | Lead Dev | Automated builds | ⬜ |

### 8.4 Flutter Project Structure

```
smart_dairy_mobile/
├── android/
├── ios/
├── lib/
│   ├── main.dart
│   ├── app.dart
│   ├── core/
│   │   ├── constants/
│   │   │   ├── api_constants.dart
│   │   │   ├── app_constants.dart
│   │   │   └── storage_keys.dart
│   │   ├── errors/
│   │   │   ├── exceptions.dart
│   │   │   └── failures.dart
│   │   ├── network/
│   │   │   ├── dio_client.dart
│   │   │   ├── network_info.dart
│   │   │   └── api_interceptor.dart
│   │   ├── theme/
│   │   │   ├── app_theme.dart
│   │   │   ├── app_colors.dart
│   │   │   └── app_typography.dart
│   │   └── utils/
│   │       ├── extensions.dart
│   │       ├── helpers.dart
│   │       └── validators.dart
│   ├── features/
│   │   ├── auth/
│   │   │   ├── data/
│   │   │   │   ├── datasources/
│   │   │   │   │   ├── auth_remote_datasource.dart
│   │   │   │   │   └── auth_local_datasource.dart
│   │   │   │   ├── models/
│   │   │   │   │   ├── user_model.dart
│   │   │   │   │   └── token_model.dart
│   │   │   │   └── repositories/
│   │   │   │       └── auth_repository_impl.dart
│   │   │   ├── domain/
│   │   │   │   ├── entities/
│   │   │   │   │   └── user.dart
│   │   │   │   ├── repositories/
│   │   │   │   │   └── auth_repository.dart
│   │   │   │   └── usecases/
│   │   │   │       ├── login.dart
│   │   │   │       ├── register.dart
│   │   │   │       └── logout.dart
│   │   │   └── presentation/
│   │   │       ├── bloc/
│   │   │       │   ├── auth_bloc.dart
│   │   │       │   ├── auth_event.dart
│   │   │       │   └── auth_state.dart
│   │   │       ├── pages/
│   │   │       │   ├── login_page.dart
│   │   │       │   └── register_page.dart
│   │   │       └── widgets/
│   │   │           └── auth_form.dart
│   │   ├── home/
│   │   ├── products/
│   │   ├── cart/
│   │   ├── orders/
│   │   ├── subscription/
│   │   └── profile/
│   └── injection_container.dart
├── test/
└── pubspec.yaml
```

### 8.5 pubspec.yaml Configuration

```yaml
name: smart_dairy_mobile
description: Smart Dairy Customer Mobile Application

publish_to: 'none'

version: 1.0.0+1

environment:
  sdk: '>=3.2.0 <4.0.0'

dependencies:
  flutter:
    sdk: flutter
  
  # State Management
  flutter_bloc: ^8.1.3
  equatable: ^2.0.5
  
  # Dependency Injection
  get_it: ^7.6.4
  injectable: ^2.3.2
  
  # Networking
  dio: ^5.4.0
  retrofit: ^4.0.3
  
  # Local Storage
  hive: ^2.2.3
  hive_flutter: ^1.1.0
  
  # Firebase
  firebase_core: ^2.24.2
  firebase_messaging: ^14.7.10
  firebase_analytics: ^10.8.0
  firebase_crashlytics: ^3.4.9
  
  # UI Components
  flutter_screenutil: ^5.9.0
  shimmer: ^3.0.0
  cached_network_image: ^3.3.1
  flutter_svg: ^2.0.9
  google_fonts: ^6.1.0
  
  # Utilities
  dartz: ^0.10.1
  intl: ^0.18.1
  logger: ^2.0.2+1
  connectivity_plus: ^5.0.2
  permission_handler: ^11.1.0
  geolocator: ^10.1.0
  url_launcher: ^6.2.2
  share_plus: ^7.2.1
  
  # Payment
  webview_flutter: ^4.4.2
  
dev_dependencies:
  flutter_test:
    sdk: flutter
  flutter_lints: ^3.0.1
  build_runner: ^2.4.7
  retrofit_generator: ^8.0.6
  injectable_generator: ^2.4.1
  hive_generator: ^2.0.1

flutter:
  uses-material-design: true
  assets:
    - assets/images/
    - assets/icons/
    - assets/fonts/
```

### 8.6 Dio HTTP Client Configuration

```dart
// lib/core/network/dio_client.dart
import 'package:dio/dio.dart';
import 'package:logger/logger.dart';
import 'api_interceptor.dart';

class DioClient {
  final Dio _dio;
  final Logger _logger;
  
  DioClient({required String baseUrl, required Logger logger})
      : _dio = Dio(BaseOptions(
          baseUrl: baseUrl,
          connectTimeout: const Duration(seconds: 30),
          receiveTimeout: const Duration(seconds: 30),
          sendTimeout: const Duration(seconds: 30),
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
        )),
        _logger = logger {
    _dio.interceptors.addAll([
      ApiInterceptor(logger: _logger),
      LogInterceptor(
        request: true,
        requestHeader: true,
        requestBody: true,
        responseHeader: true,
        responseBody: true,
        error: true,
        logPrint: (object) => _logger.i(object.toString()),
      ),
    ]);
  }
  
  Dio get dio => _dio;
  
  void setAuthToken(String token) {
    _dio.options.headers['Authorization'] = 'Bearer $token';
  }
  
  void clearAuthToken() {
    _dio.options.headers.remove('Authorization');
  }
}

// lib/core/network/api_interceptor.dart
import 'package:dio/dio.dart';
import 'package:logger/logger.dart';

class ApiInterceptor extends Interceptor {
  final Logger _logger;
  
  ApiInterceptor({required Logger logger}) : _logger = logger;
  
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    _logger.i('REQUEST[${options.method}] => PATH: ${options.path}');
    handler.next(options);
  }
  
  @override
  void onResponse(Response response, ResponseInterceptorHandler handler) {
    _logger.i('RESPONSE[${response.statusCode}] => PATH: ${response.requestOptions.path}');
    handler.next(response);
  }
  
  @override
  void onError(DioException err, ErrorInterceptorHandler handler) async {
    _logger.e('ERROR[${err.response?.statusCode}] => PATH: ${err.requestOptions.path}');
    
    if (err.response?.statusCode == 401) {
      // Handle token refresh
      _logger.w('Token expired, attempting refresh...');
    }
    
    handler.next(err);
  }
}
```

---

## 9. MILESTONE 16: Customer Mobile App - Authentication & Home (Days 151-160)

### 9.1 Objective Statement

Implement customer mobile app authentication flow with phone number OTP login, registration, guest browsing, home screen with featured products, promotional banners, quick order options, and category browsing with bottom navigation bar.

### 9.2 Success Criteria
- Phone number OTP login working with bKash/Nagad integration
- User registration with profile creation
- Guest browsing mode for unauthenticated users
- Home screen with featured products carousel
- Promotional banners with click-through
- Quick order for frequent purchases
- Category browsing with grid layout
- Bottom navigation (Home, Categories, Cart, Orders, Profile)

### 9.3 Deliverables Table

| ID | Deliverable | Owner | Verification Method | Status |
|----|-------------|-------|---------------------|--------|
| M16-D1 | Phone login UI | Frontend Dev | Login screens built | ⬜ |
| M16-D2 | OTP verification | Frontend Dev | OTP flow working | ⬜ |
| M16-D3 | User registration | Frontend Dev | Registration working | ⬜ |
| M16-D4 | Guest browsing | Frontend Dev | Guest mode accessible | ⬜ |
| M16-D5 | Home screen UI | Frontend Dev | Home screen complete | ⬜ |
| M16-D6 | Product carousel | Frontend Dev | Carousel functional | ⬜ |
| M16-D7 | Category grid | Frontend Dev | Categories displayed | ⬜ |
| M16-D8 | Bottom navigation | Frontend Dev | Navigation working | ⬜ |
| M16-D9 | Pull-to-refresh | Frontend Dev | Refresh functional | ⬜ |
| M16-D10 | Auth testing | Frontend Dev | All auth flows tested | ⬜ |

### 9.4 Authentication BLoC Implementation

```dart
// lib/features/auth/presentation/bloc/auth_bloc.dart
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:equatable/equatable.dart';
import '../../domain/entities/user.dart';
import '../../domain/usecases/login.dart';
import '../../domain/usecases/register.dart';
import '../../domain/usecases/logout.dart';
import '../../domain/usecases/verify_otp.dart';

part 'auth_event.dart';
part 'auth_state.dart';

class AuthBloc extends Bloc<AuthEvent, AuthState> {
  final LoginUseCase _loginUseCase;
  final RegisterUseCase _registerUseCase;
  final LogoutUseCase _logoutUseCase;
  final VerifyOtpUseCase _verifyOtpUseCase;
  
  AuthBloc({
    required LoginUseCase loginUseCase,
    required RegisterUseCase registerUseCase,
    required LogoutUseCase logoutUseCase,
    required VerifyOtpUseCase verifyOtpUseCase,
  })  : _loginUseCase = loginUseCase,
        _registerUseCase = registerUseCase,
        _logoutUseCase = logoutUseCase,
        _verifyOtpUseCase = verifyOtpUseCase,
        super(AuthInitial()) {
    on<CheckAuthStatusEvent>(_onCheckAuthStatus);
    on<SendOtpEvent>(_onSendOtp);
    on<VerifyOtpEvent>(_onVerifyOtp);
    on<RegisterEvent>(_onRegister);
    on<LogoutEvent>(_onLogout);
  }
  
  Future<void> _onCheckAuthStatus(
    CheckAuthStatusEvent event,
    Emitter<AuthState> emit,
  ) async {
    emit(AuthLoading());
    // Check if user is already logged in
    // Implementation...
  }
  
  Future<void> _onSendOtp(
    SendOtpEvent event,
    Emitter<AuthState> emit,
  ) async {
    emit(AuthLoading());
    
    final result = await _loginUseCase(LoginParams(phone: event.phoneNumber));
    
    result.fold(
      (failure) => emit(AuthError(message: failure.message)),
      (_) => emit(OtpSentState(phoneNumber: event.phoneNumber)),
    );
  }
  
  Future<void> _onVerifyOtp(
    VerifyOtpEvent event,
    Emitter<AuthState> emit,
  ) async {
    emit(AuthLoading());
    
    final result = await _verifyOtpUseCase(
      VerifyOtpParams(phone: event.phoneNumber, otp: event.otp),
    );
    
    result.fold(
      (failure) => emit(AuthError(message: failure.message)),
      (user) => emit(Authenticated(user: user)),
    );
  }
  
  Future<void> _onRegister(
    RegisterEvent event,
    Emitter<AuthState> emit,
  ) async {
    emit(AuthLoading());
    
    final result = await _registerUseCase(RegisterParams(
      phone: event.phoneNumber,
      name: event.name,
      email: event.email,
    ));
    
    result.fold(
      (failure) => emit(AuthError(message: failure.message)),
      (user) => emit(Authenticated(user: user)),
    );
  }
  
  Future<void> _onLogout(
    LogoutEvent event,
    Emitter<AuthState> emit,
  ) async {
    emit(AuthLoading());
    
    final result = await _logoutUseCase();
    
    result.fold(
      (failure) => emit(AuthError(message: failure.message)),
      (_) => emit(Unauthenticated()),
    );
  }
}
```

---


## 10. MILESTONE 17: Customer Mobile App - Products, Cart & Checkout (Days 161-170)

### 10.1 Objective Statement

Complete product browsing with filters and search, shopping cart with add/remove/update quantities, checkout flow with delivery address selection, delivery time slot selection, payment method selection, order confirmation, and order tracking.

### 10.2 Success Criteria
- Product listing with filters (category, price, sort) functional
- Product detail page with images, variants, reviews
- Shopping cart with real-time updates
- Delivery address selection and management
- Delivery time slot selection
- Multiple payment methods supported
- Order confirmation with summary
- Order history and tracking

### 10.3 Deliverables Table

| ID | Deliverable | Owner | Verification Method | Status |
|----|-------------|-------|---------------------|--------|
| M17-D1 | Product listing UI | Frontend Dev | Product list screen | ⬜ |
| M17-D2 | Product filters | Frontend Dev | Filters working | ⬜ |
| M17-D3 | Product detail page | Frontend Dev | Product detail complete | ⬜ |
| M17-D4 | Shopping cart UI | Frontend Dev | Cart screen built | ⬜ |
| M17-D5 | Cart operations | Frontend Dev | Add/remove/update | ⬜ |
| M17-D6 | Checkout flow | Frontend Dev | Checkout complete | ⬜ |
| M17-D7 | Address management | Frontend Dev | Address selection | ⬜ |
| M17-D8 | Payment UI | Frontend Dev | Payment selection | ⬜ |
| M17-D9 | Order tracking | Frontend Dev | Tracking functional | ⬜ |
| M17-D10 | E2E flow testing | Frontend Dev | Complete flow tested | ⬜ |

### 10.4 Cart BLoC Implementation

```dart
// lib/features/cart/presentation/bloc/cart_bloc.dart
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:equatable/equatable.dart';
import '../../domain/entities/cart_item.dart';
import '../../domain/usecases/add_to_cart.dart';
import '../../domain/usecases/get_cart.dart';
import '../../domain/usecases/remove_from_cart.dart';
import '../../domain/usecases/update_quantity.dart';
import '../../domain/usecases/clear_cart.dart';

part 'cart_event.dart';
part 'cart_state.dart';

class CartBloc extends Bloc<CartEvent, CartState> {
  final AddToCartUseCase _addToCartUseCase;
  final GetCartUseCase _getCartUseCase;
  final RemoveFromCartUseCase _removeFromCartUseCase;
  final UpdateQuantityUseCase _updateQuantityUseCase;
  final ClearCartUseCase _clearCartUseCase;
  
  CartBloc({
    required AddToCartUseCase addToCartUseCase,
    required GetCartUseCase getCartUseCase,
    required RemoveFromCartUseCase removeFromCartUseCase,
    required UpdateQuantityUseCase updateQuantityUseCase,
    required ClearCartUseCase clearCartUseCase,
  })  : _addToCartUseCase = addToCartUseCase,
        _getCartUseCase = getCartUseCase,
        _removeFromCartUseCase = removeFromCartUseCase,
        _updateQuantityUseCase = updateQuantityUseCase,
        _clearCartUseCase = clearCartUseCase,
        super(CartInitial()) {
    on<LoadCartEvent>(_onLoadCart);
    on<AddToCartEvent>(_onAddToCart);
    on<RemoveFromCartEvent>(_onRemoveFromCart);
    on<UpdateQuantityEvent>(_onUpdateQuantity);
    on<ClearCartEvent>(_onClearCart);
  }
  
  Future<void> _onLoadCart(
    LoadCartEvent event,
    Emitter<CartState> emit,
  ) async {
    emit(CartLoading());
    
    final result = await _getCartUseCase();
    
    result.fold(
      (failure) => emit(CartError(message: failure.message)),
      (cart) => emit(CartLoaded(
        items: cart.items,
        subtotal: cart.subtotal,
        tax: cart.tax,
        total: cart.total,
        itemCount: cart.itemCount,
      )),
    );
  }
  
  Future<void> _onAddToCart(
    AddToCartEvent event,
    Emitter<CartState> emit,
  ) async {
    final currentState = state;
    if (currentState is CartLoaded) {
      emit(CartUpdating(items: currentState.items));
    }
    
    final result = await _addToCartUseCase(AddToCartParams(
      productId: event.productId,
      quantity: event.quantity,
      variantId: event.variantId,
    ));
    
    result.fold(
      (failure) => emit(CartError(message: failure.message)),
      (cart) => emit(CartLoaded(
        items: cart.items,
        subtotal: cart.subtotal,
        tax: cart.tax,
        total: cart.total,
        itemCount: cart.itemCount,
        message: 'Item added to cart',
      )),
    );
  }
  
  Future<void> _onUpdateQuantity(
    UpdateQuantityEvent event,
    Emitter<CartState> emit,
  ) async {
    final currentState = state;
    if (currentState is CartLoaded) {
      emit(CartUpdating(items: currentState.items));
    }
    
    final result = await _updateQuantityUseCase(UpdateQuantityParams(
      cartItemId: event.cartItemId,
      quantity: event.quantity,
    ));
    
    result.fold(
      (failure) => emit(CartError(message: failure.message)),
      (cart) => emit(CartLoaded(
        items: cart.items,
        subtotal: cart.subtotal,
        tax: cart.tax,
        total: cart.total,
        itemCount: cart.itemCount,
      )),
    );
  }
  
  Future<void> _onRemoveFromCart(
    RemoveFromCartEvent event,
    Emitter<CartState> emit,
  ) async {
    final currentState = state;
    if (currentState is CartLoaded) {
      emit(CartUpdating(items: currentState.items));
    }
    
    final result = await _removeFromCartUseCase(RemoveFromCartParams(
      cartItemId: event.cartItemId,
    ));
    
    result.fold(
      (failure) => emit(CartError(message: failure.message)),
      (cart) => emit(CartLoaded(
        items: cart.items,
        subtotal: cart.subtotal,
        tax: cart.tax,
        total: cart.total,
        itemCount: cart.itemCount,
        message: 'Item removed from cart',
      )),
    );
  }
  
  Future<void> _onClearCart(
    ClearCartEvent event,
    Emitter<CartState> emit,
  ) async {
    emit(CartLoading());
    
    final result = await _clearCartUseCase();
    
    result.fold(
      (failure) => emit(CartError(message: failure.message)),
      (_) => emit(CartEmpty()),
    );
  }
}
```

### 10.5 Cart Entity and Model

```dart
// lib/features/cart/domain/entities/cart.dart
import 'package:equatable/equatable.dart';
import 'cart_item.dart';

class Cart extends Equatable {
  final String id;
  final List<CartItem> items;
  final double subtotal;
  final double tax;
  final double deliveryCharge;
  final double total;
  final int itemCount;
  
  const Cart({
    required this.id,
    required this.items,
    required this.subtotal,
    required this.tax,
    required this.deliveryCharge,
    required this.total,
    required this.itemCount,
  });
  
  @override
  List<Object?> get props => [id, items, subtotal, tax, deliveryCharge, total, itemCount];
}

// lib/features/cart/domain/entities/cart_item.dart
import 'package:equatable/equatable.dart';

class CartItem extends Equatable {
  final String id;
  final String productId;
  final String name;
  final String image;
  final double price;
  final int quantity;
  final double total;
  final String? variantName;
  final String? unit;
  
  const CartItem({
    required this.id,
    required this.productId,
    required this.name,
    required this.image,
    required this.price,
    required this.quantity,
    required this.total,
    this.variantName,
    this.unit,
  });
  
  CartItem copyWith({
    String? id,
    String? productId,
    String? name,
    String? image,
    double? price,
    int? quantity,
    double? total,
    String? variantName,
    String? unit,
  }) {
    return CartItem(
      id: id ?? this.id,
      productId: productId ?? this.productId,
      name: name ?? this.name,
      image: image ?? this.image,
      price: price ?? this.price,
      quantity: quantity ?? this.quantity,
      total: total ?? this.total,
      variantName: variantName ?? this.variantName,
      unit: unit ?? this.unit,
    );
  }
  
  @override
  List<Object?> get props => [id, productId, name, image, price, quantity, total, variantName, unit];
}
```

---

## 11. MILESTONE 18: Customer Mobile App - Subscription Management (Days 171-180)

### 11.1 Objective Statement

Implement subscription management in mobile app including viewing active subscriptions, subscription details, pause/resume functionality, skip next delivery, modify subscription quantity or products, view delivery calendar, and manage payment methods.

### 11.2 Success Criteria
- Active subscriptions list view
- Subscription detail view with schedule
- Pause and resume subscription
- Skip next delivery with reason
- Modify subscription quantity
- Change delivery time slot
- Delivery calendar view
- Payment method management

### 11.3 Deliverables Table

| ID | Deliverable | Owner | Verification Method | Status |
|----|-------------|-------|---------------------|--------|
| M18-D1 | Subscriptions list | Frontend Dev | List view complete | ⬜ |
| M18-D2 | Subscription detail | Frontend Dev | Detail screen | ⬜ |
| M18-D3 | Pause/Resume UI | Frontend Dev | Actions working | ⬜ |
| M18-D4 | Skip delivery | Frontend Dev | Skip flow complete | ⬜ |
| M18-D5 | Modify quantity | Frontend Dev | Quantity editable | ⬜ |
| M18-D6 | Change time slot | Frontend Dev | Slot selection | ⬜ |
| M18-D7 | Delivery calendar | Frontend Dev | Calendar view | ⬜ |
| M18-D8 | Payment methods | Frontend Dev | Payment management | ⬜ |
| M18-D9 | Push notifications | Frontend Dev | FCM integration | ⬜ |
| M18-D10 | Subscription testing | Frontend Dev | All features tested | ⬜ |

### 11.4 Subscription BLoC

```dart
// lib/features/subscription/presentation/bloc/subscription_bloc.dart
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:equatable/equatable.dart';
import '../../domain/entities/subscription.dart';
import '../../domain/usecases/get_subscriptions.dart';
import '../../domain/usecases/pause_subscription.dart';
import '../../domain/usecases/resume_subscription.dart';
import '../../domain/usecases/skip_delivery.dart';
import '../../domain/usecases/update_subscription.dart';

part 'subscription_event.dart';
part 'subscription_state.dart';

class SubscriptionBloc extends Bloc<SubscriptionEvent, SubscriptionState> {
  final GetSubscriptionsUseCase _getSubscriptionsUseCase;
  final PauseSubscriptionUseCase _pauseSubscriptionUseCase;
  final ResumeSubscriptionUseCase _resumeSubscriptionUseCase;
  final SkipDeliveryUseCase _skipDeliveryUseCase;
  final UpdateSubscriptionUseCase _updateSubscriptionUseCase;
  
  SubscriptionBloc({
    required GetSubscriptionsUseCase getSubscriptionsUseCase,
    required PauseSubscriptionUseCase pauseSubscriptionUseCase,
    required ResumeSubscriptionUseCase resumeSubscriptionUseCase,
    required SkipDeliveryUseCase skipDeliveryUseCase,
    required UpdateSubscriptionUseCase updateSubscriptionUseCase,
  })  : _getSubscriptionsUseCase = getSubscriptionsUseCase,
        _pauseSubscriptionUseCase = pauseSubscriptionUseCase,
        _resumeSubscriptionUseCase = resumeSubscriptionUseCase,
        _skipDeliveryUseCase = skipDeliveryUseCase,
        _updateSubscriptionUseCase = updateSubscriptionUseCase,
        super(SubscriptionInitial()) {
    on<LoadSubscriptionsEvent>(_onLoadSubscriptions);
    on<PauseSubscriptionEvent>(_onPauseSubscription);
    on<ResumeSubscriptionEvent>(_onResumeSubscription);
    on<SkipDeliveryEvent>(_onSkipDelivery);
    on<UpdateSubscriptionEvent>(_onUpdateSubscription);
  }
  
  Future<void> _onLoadSubscriptions(
    LoadSubscriptionsEvent event,
    Emitter<SubscriptionState> emit,
  ) async {
    emit(SubscriptionLoading());
    
    final result = await _getSubscriptionsUseCase();
    
    result.fold(
      (failure) => emit(SubscriptionError(message: failure.message)),
      (subscriptions) => emit(SubscriptionsLoaded(subscriptions: subscriptions)),
    );
  }
  
  Future<void> _onPauseSubscription(
    PauseSubscriptionEvent event,
    Emitter<SubscriptionState> emit,
  ) async {
    emit(SubscriptionActionLoading());
    
    final result = await _pauseSubscriptionUseCase(
      PauseSubscriptionParams(subscriptionId: event.subscriptionId),
    );
    
    result.fold(
      (failure) => emit(SubscriptionError(message: failure.message)),
      (subscription) {
        emit(SubscriptionActionSuccess(message: 'Subscription paused'));
        add(LoadSubscriptionsEvent());
      },
    );
  }
  
  Future<void> _onSkipDelivery(
    SkipDeliveryEvent event,
    Emitter<SubscriptionState> emit,
  ) async {
    emit(SubscriptionActionLoading());
    
    final result = await _skipDeliveryUseCase(SkipDeliveryParams(
      subscriptionId: event.subscriptionId,
      deliveryDate: event.deliveryDate,
      reason: event.reason,
    ));
    
    result.fold(
      (failure) => emit(SubscriptionError(message: failure.message)),
      (_) {
        emit(SubscriptionActionSuccess(message: 'Delivery skipped'));
        add(LoadSubscriptionsEvent());
      },
    );
  }
}
```

---


## 12. MILESTONE 19: Field Sales Mobile App Development (Days 181-190)

### 12.1 Objective Statement

Develop field sales mobile application for sales representatives with offline capability, customer management, route planning, order collection, delivery confirmation, payment collection, and daily reporting features.

### 12.2 Success Criteria
- Field sales app with offline mode functional
- Customer list with search and filtering
- Route optimization with Google Maps
- Order collection with cart functionality
- Delivery confirmation with digital signature
- Payment collection (cash, mobile banking)
- Daily sales report generation
- Sync when internet available

### 12.3 Deliverables Table

| ID | Deliverable | Owner | Verification Method | Status |
|----|-------------|-------|---------------------|--------|
| M19-D1 | Field sales app scaffold | Frontend Dev | App structure ready | ⬜ |
| M19-D2 | Offline mode architecture | Frontend Dev | Sync mechanism | ⬜ |
| M19-D3 | Customer management | Frontend Dev | Customer CRUD | ⬜ |
| M19-D4 | Route planning | Frontend Dev | Map integration | ⬜ |
| M19-D5 | Order collection | Frontend Dev | Order creation | ⬜ |
| M19-D6 | Delivery confirmation | Frontend Dev | Signature capture | ⬜ |
| M19-D7 | Payment collection | Frontend Dev | Payment recording | ⬜ |
| M19-D8 | Daily reports | Frontend Dev | Report generation | ⬜ |
| M19-D9 | Data synchronization | Frontend Dev | Sync working | ⬜ |
| M19-D10 | Field app testing | Frontend Dev | Complete testing | ⬜ |

### 12.4 Offline-First Architecture

```dart
// lib/core/sync/sync_manager.dart
import 'package:connectivity_plus/connectivity_plus.dart';
import 'package:logger/logger.dart';

abstract class SyncManager {
  Future<bool> isOnline();
  Future<void> syncAll();
  Future<void> queueForSync(SyncOperation operation);
  Stream<SyncStatus> get syncStatus;
}

class SyncManagerImpl implements SyncManager {
  final Connectivity _connectivity;
  final Logger _logger;
  final LocalDatabase _localDb;
  final RemoteApi _remoteApi;
  
  SyncManagerImpl({
    required Connectivity connectivity,
    required Logger logger,
    required LocalDatabase localDb,
    required RemoteApi remoteApi,
  })  : _connectivity = connectivity,
        _logger = logger,
        _localDb = localDb,
        _remoteApi = remoteApi;
  
  @override
  Future<bool> isOnline() async {
    final result = await _connectivity.checkConnectivity();
    return result != ConnectivityResult.none;
  }
  
  @override
  Future<void> syncAll() async {
    if (!await isOnline()) {
      _logger.w('Cannot sync: Device is offline');
      return;
    }
    
    final pendingOperations = await _localDb.getPendingSyncOperations();
    
    for (final operation in pendingOperations) {
      try {
        await _executeSyncOperation(operation);
        await _localDb.markSyncCompleted(operation.id);
      } catch (e) {
        _logger.e('Sync failed for operation ${operation.id}: $e');
        await _localDb.incrementRetryCount(operation.id);
      }
    }
  }
  
  Future<void> _executeSyncOperation(SyncOperation operation) async {
    switch (operation.type) {
      case SyncType.createOrder:
        await _remoteApi.createOrder(operation.data);
        break;
      case SyncType.updateCustomer:
        await _remoteApi.updateCustomer(operation.data);
        break;
      case SyncType.recordPayment:
        await _remoteApi.recordPayment(operation.data);
        break;
      case SyncType.confirmDelivery:
        await _remoteApi.confirmDelivery(operation.data);
        break;
    }
  }
  
  @override
  Future<void> queueForSync(SyncOperation operation) async {
    await _localDb.saveSyncOperation(operation);
    
    // Attempt immediate sync if online
    if (await isOnline()) {
      await syncAll();
    }
  }
  
  @override
  Stream<SyncStatus> get syncStatus => _syncStatusController.stream;
}

// lib/core/sync/sync_operation.dart
enum SyncType {
  createOrder,
  updateCustomer,
  recordPayment,
  confirmDelivery,
}

class SyncOperation {
  final String id;
  final SyncType type;
  final Map<String, dynamic> data;
  final DateTime createdAt;
  final int retryCount;
  
  SyncOperation({
    required this.id,
    required this.type,
    required this.data,
    required this.createdAt,
    this.retryCount = 0,
  });
  
  Map<String, dynamic> toJson() => {
    'id': id,
    'type': type.name,
    'data': data,
    'created_at': createdAt.toIso8601String(),
    'retry_count': retryCount,
  };
}
```

### 12.5 Hive Database Models for Offline Storage

```dart
// lib/features/field_sales/data/models/local_customer_model.dart
import 'package:hive/hive.dart';

part 'local_customer_model.g.dart';

@HiveType(typeId: 1)
class LocalCustomerModel extends HiveObject {
  @HiveField(0)
  final String id;
  
  @HiveField(1)
  final String name;
  
  @HiveField(2)
  final String phone;
  
  @HiveField(3)
  final String address;
  
  @HiveField(4)
  final double latitude;
  
  @HiveField(5)
  final double longitude;
  
  @HiveField(6)
  final String routeId;
  
  @HiveField(7)
  final int visitSequence;
  
  @HiveField(8)
  final DateTime lastVisitDate;
  
  @HiveField(9)
  final double outstandingAmount;
  
  LocalCustomerModel({
    required this.id,
    required this.name,
    required this.phone,
    required this.address,
    required this.latitude,
    required this.longitude,
    required this.routeId,
    required this.visitSequence,
    required this.lastVisitDate,
    required this.outstandingAmount,
  });
}

// lib/features/field_sales/data/models/local_order_model.dart
@HiveType(typeId: 2)
class LocalOrderModel extends HiveObject {
  @HiveField(0)
  final String id;
  
  @HiveField(1)
  final String customerId;
  
  @HiveField(2)
  final List<LocalOrderItemModel> items;
  
  @HiveField(3)
  final double total;
  
  @HiveField(4)
  final DateTime orderDate;
  
  @HiveField(5)
  final String paymentMethod;
  
  @HiveField(6)
  final String status;
  
  @HiveField(7)
  final bool isSynced;
  
  @HiveField(8)
  final DateTime? syncedAt;
  
  LocalOrderModel({
    required this.id,
    required this.customerId,
    required this.items,
    required this.total,
    required this.orderDate,
    required this.paymentMethod,
    required this.status,
    this.isSynced = false,
    this.syncedAt,
  });
}
```

### 12.6 Digital Signature Capture

```dart
// lib/features/field_sales/presentation/widgets/signature_pad.dart
import 'package:flutter/material.dart';
import 'package:signature/signature.dart';

class SignaturePad extends StatefulWidget {
  final Function(Uint8List?) onSave;
  final VoidCallback onClear;
  
  const SignaturePad({
    Key? key,
    required this.onSave,
    required this.onClear,
  }) : super(key: key);
  
  @override
  State<SignaturePad> createState() => _SignaturePadState();
}

class _SignaturePadState extends State<SignaturePad> {
  final SignatureController _controller = SignatureController(
    penStrokeWidth: 3,
    penColor: Colors.black,
    exportBackgroundColor: Colors.white,
  );
  
  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }
  
  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Container(
          decoration: BoxDecoration(
            border: Border.all(color: Colors.grey),
            borderRadius: BorderRadius.circular(8),
          ),
          child: Signature(
            controller: _controller,
            height: 200,
            backgroundColor: Colors.white,
          ),
        ),
        const SizedBox(height: 16),
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceEvenly,
          children: [
            ElevatedButton.icon(
              onPressed: () {
                _controller.clear();
                widget.onClear();
              },
              icon: const Icon(Icons.clear),
              label: const Text('Clear'),
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.red,
              ),
            ),
            ElevatedButton.icon(
              onPressed: () async {
                if (_controller.isNotEmpty) {
                  final signature = await _controller.toPngBytes();
                  widget.onSave(signature);
                }
              },
              icon: const Icon(Icons.check),
              label: const Text('Confirm'),
            ),
          ],
        ),
      ],
    );
  }
}
```

---

## 13. MILESTONE 20: Operations Go-Live & Phase 2 Completion (Days 191-200)

### 13.1 Objective Statement

Prepare for operations go-live with comprehensive testing of e-commerce, mobile apps, payment gateways, and subscription system. Conduct UAT with real customers, performance optimization, security hardening, and launch customer mobile app on Google Play Store and Apple App Store.

### 13.2 Success Criteria
- E-commerce platform handling 100+ concurrent users
- Mobile app published on Google Play Store
- Mobile app published on Apple App Store
- Payment gateways processing transactions
- 100+ active subscriptions by end of milestone
- 99.5% uptime achieved
- Customer satisfaction score >4.0/5.0
- All critical bugs resolved

### 13.3 Deliverables Table

| ID | Deliverable | Owner | Verification Method | Status |
|----|-------------|-------|---------------------|--------|
| M20-D1 | UAT with real customers | All Dev | UAT sign-off | ⬜ |
| M20-D2 | Performance optimization | Lead Dev | Load test passed | ⬜ |
| M20-D3 | Security hardening | Lead Dev | Security audit | ⬜ |
| M20-D4 | Play Store submission | Frontend Dev | App published | ⬜ |
| M20-D5 | App Store submission | Frontend Dev | App published | ⬜ |
| M20-D6 | Production deployment | Lead Dev | Live environment | ⬜ |
| M20-D7 | Monitoring setup | Lead Dev | Dashboards active | ⬜ |
| M20-D8 | Training documentation | All Dev | Training complete | ⬜ |
| M20-D9 | Handover documentation | All Dev | Docs delivered | ⬜ |
| M20-D10 | Phase 2 sign-off | PM | Sign-off document | ⬜ |

### 13.4 Production Deployment Checklist

```yaml
# Pre-Deployment Verification
pre_deployment:
  code_quality:
    - Unit test coverage > 80%
    - Integration tests passing
    - Code review completed
    - Static analysis passed
  
  infrastructure:
    - Production servers provisioned
    - Database backups configured
    - Redis cluster ready
    - Load balancer configured
    - SSL certificates installed
    - CDN configured
  
  security:
    - Security scan completed
    - OWASP Top 10 verified
    - Penetration testing passed
    - API rate limiting enabled
    - WAF rules configured
    - DDoS protection active

deployment_steps:
  1_database:
    - Run migration scripts
    - Verify schema changes
    - Update indexes
    - Seed reference data
  
  2_application:
    - Deploy Odoo backend
    - Deploy mobile BFF API
    - Verify service health
    - Check logs for errors
  
  3_mobile_apps:
    - Generate release APK
    - Generate iOS archive
    - Upload to Play Console
    - Upload to App Store Connect
    - Submit for review
  
  4_payment_gateways:
    - Switch to production credentials
    - Test payment flows
    - Verify webhook endpoints
    - Check settlement accounts

post_deployment:
  verification:
    - Smoke tests passing
    - E2E tests passing
    - Payment processing verified
    - Push notifications working
    - Email delivery confirmed
  
  monitoring:
    - Error tracking active
    - Performance monitoring enabled
    - Business metrics dashboard
    - Alert rules configured
```

### 13.5 Launch Marketing Integration

```python
# Odoo module for marketing campaign integration
class MarketingCampaign(models.Model):
    _name = 'marketing.campaign'
    _description = 'Marketing Campaign'
    
    name = fields.Char('Campaign Name', required=True)
    campaign_type = fields.Selection([
        ('launch', 'App Launch'),
        ('promotion', 'Promotion'),
        ('referral', 'Referral Program'),
        ('loyalty', 'Loyalty Program'),
    ], string='Campaign Type', required=True)
    
    start_date = fields.Date('Start Date', required=True)
    end_date = fields.Date('End Date')
    
    # Promotion details
    discount_percentage = fields.Float('Discount %')
    discount_amount = fields.Float('Discount Amount')
    min_order_amount = fields.Float('Minimum Order Amount')
    max_discount = fields.Float('Maximum Discount')
    
    # Targeting
    target_customer_segment = fields.Selection([
        ('all', 'All Customers'),
        ('new', 'New Customers'),
        ('existing', 'Existing Customers'),
        ('vip', 'VIP Customers'),
    ], string='Target Segment', default='all')
    
    # Tracking
    promo_code = fields.Char('Promo Code')
    usage_limit = fields.Integer('Usage Limit')
    usage_count = fields.Integer('Usage Count', compute='_compute_usage_count')
    
    # Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('active', 'Active'),
        ('paused', 'Paused'),
        ('completed', 'Completed'),
    ], string='Status', default='draft')
    
    def apply_promotion(self, order):
        # Apply promotion logic
        if self.discount_percentage:
            discount = order.amount_untaxed * (self.discount_percentage / 100)
            if self.max_discount and discount > self.max_discount:
                discount = self.max_discount
            return discount
        elif self.discount_amount:
            return self.discount_amount
        return 0.0
```

### 13.6 KPI Dashboard Configuration

```json
{
  "dashboards": {
    "ecommerce_metrics": {
      "daily_orders": {
        "type": "counter",
        "query": "SELECT COUNT(*) FROM sale_order WHERE date_order = CURRENT_DATE",
        "alert_threshold": {"min": 50}
      },
      "daily_revenue": {
        "type": "currency",
        "query": "SELECT SUM(amount_total) FROM sale_order WHERE date_order = CURRENT_DATE",
        "alert_threshold": {"min": 50000}
      },
      "conversion_rate": {
        "type": "percentage",
        "query": "SELECT (COUNT(*) FILTER (WHERE state != 'draft') * 100.0 / NULLIF(COUNT(*), 0)) FROM sale_order WHERE date_order = CURRENT_DATE"
      },
      "average_order_value": {
        "type": "currency",
        "query": "SELECT AVG(amount_total) FROM sale_order WHERE date_order = CURRENT_DATE AND state != 'draft'"
      }
    },
    "subscription_metrics": {
      "active_subscriptions": {
        "type": "counter",
        "query": "SELECT COUNT(*) FROM sale_subscription WHERE state = 'active'"
      },
      "subscription_revenue": {
        "type": "currency",
        "query": "SELECT SUM(total) FROM sale_subscription WHERE state = 'active'"
      },
      "churn_rate": {
        "type": "percentage",
        "query": "SELECT (COUNT(*) FILTER (WHERE state = 'cancelled' AND write_date >= CURRENT_DATE - INTERVAL '30 days') * 100.0 / NULLIF(COUNT(*) FILTER (WHERE state = 'active'), 0)) FROM sale_subscription"
      }
    },
    "mobile_app_metrics": {
      "daily_active_users": {
        "type": "counter",
        "source": "firebase_analytics"
      },
      "app_crash_rate": {
        "type": "percentage",
        "source": "firebase_crashlytics",
        "alert_threshold": {"max": 1.0}
      },
      "avg_session_duration": {
        "type": "duration",
        "source": "firebase_analytics"
      }
    }
  }
}
```

---


## 14. TECHNICAL APPENDICES

### Appendix A: Database Schema Extensions

#### A.1 E-commerce Tables

```sql
-- Product Catalog Extensions
CREATE TABLE product_subscription_eligibility (
    id SERIAL PRIMARY KEY,
    product_id INTEGER REFERENCES product_template(id) ON DELETE CASCADE,
    is_subscription_eligible BOOLEAN DEFAULT FALSE,
    min_subscription_days INTEGER DEFAULT 7,
    max_subscription_days INTEGER DEFAULT 365,
    subscription_discount_percent DECIMAL(5,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_product_subscription_eligibility_product 
ON product_subscription_eligibility(product_id);

-- Delivery Time Slots
CREATE TABLE delivery_time_slot (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    sequence INTEGER DEFAULT 10,
    start_time DECIMAL(4,2) NOT NULL,
    end_time DECIMAL(4,2) NOT NULL,
    slot_type VARCHAR(50) NOT NULL,
    max_orders INTEGER DEFAULT 50,
    active BOOLEAN DEFAULT TRUE,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_delivery_time_slot_active ON delivery_time_slot(active);
CREATE INDEX idx_delivery_time_slot_sequence ON delivery_time_slot(sequence);

-- Product Reviews
CREATE TABLE product_review (
    id SERIAL PRIMARY KEY,
    product_id INTEGER REFERENCES product_template(id) ON DELETE CASCADE,
    partner_id INTEGER REFERENCES res_partner(id) ON DELETE CASCADE,
    user_id INTEGER REFERENCES res_users(id) ON DELETE CASCADE,
    rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(255) NOT NULL,
    review_text TEXT NOT NULL,
    state VARCHAR(50) DEFAULT 'pending',
    helpful_count INTEGER DEFAULT 0,
    not_helpful_count INTEGER DEFAULT 0,
    is_verified_purchase BOOLEAN DEFAULT FALSE,
    order_id INTEGER REFERENCES sale_order(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT unique_review_per_user_product UNIQUE (product_id, user_id)
);

CREATE INDEX idx_product_review_product ON product_review(product_id);
CREATE INDEX idx_product_review_state ON product_review(state);
CREATE INDEX idx_product_review_rating ON product_review(rating);
```

#### A.2 Subscription Management Tables

```sql
-- Subscription Master Table
CREATE TABLE sale_subscription (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    partner_id INTEGER NOT NULL REFERENCES res_partner(id) ON DELETE CASCADE,
    partner_invoice_id INTEGER REFERENCES res_partner(id),
    partner_shipping_id INTEGER REFERENCES res_partner(id),
    product_id INTEGER NOT NULL REFERENCES product_product(id),
    quantity DECIMAL(12,2) NOT NULL DEFAULT 1.0,
    price_unit DECIMAL(12,2) NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    tax_id INTEGER[],
    total DECIMAL(12,2) NOT NULL,
    frequency VARCHAR(50) NOT NULL DEFAULT 'daily',
    delivery_time_slot INTEGER REFERENCES delivery_time_slot(id),
    start_date DATE NOT NULL,
    end_date DATE,
    next_delivery_date DATE,
    next_billing_date DATE,
    state VARCHAR(50) DEFAULT 'draft',
    payment_method VARCHAR(50) NOT NULL,
    auto_renew BOOLEAN DEFAULT TRUE,
    skip_count INTEGER DEFAULT 0,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_sale_subscription_partner ON sale_subscription(partner_id);
CREATE INDEX idx_sale_subscription_state ON sale_subscription(state);
CREATE INDEX idx_sale_subscription_next_delivery ON sale_subscription(next_delivery_date);
CREATE INDEX idx_sale_subscription_frequency ON sale_subscription(frequency);

-- Subscription Lines (Individual Deliveries)
CREATE TABLE sale_subscription_line (
    id SERIAL PRIMARY KEY,
    subscription_id INTEGER NOT NULL REFERENCES sale_subscription(id) ON DELETE CASCADE,
    delivery_date DATE NOT NULL,
    state VARCHAR(50) DEFAULT 'scheduled',
    skip_reason TEXT,
    sale_order_id INTEGER REFERENCES sale_order(id),
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_sale_subscription_line_subscription ON sale_subscription_line(subscription_id);
CREATE INDEX idx_sale_subscription_line_delivery_date ON sale_subscription_line(delivery_date);
CREATE INDEX idx_sale_subscription_line_state ON sale_subscription_line(state);

-- Customer Wishlist
CREATE TABLE customer_wishlist (
    id SERIAL PRIMARY KEY,
    partner_id INTEGER NOT NULL REFERENCES res_partner(id) ON DELETE CASCADE,
    product_id INTEGER NOT NULL REFERENCES product_template(id) ON DELETE CASCADE,
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT unique_wishlist_item UNIQUE (partner_id, product_id)
);

CREATE INDEX idx_customer_wishlist_partner ON customer_wishlist(partner_id);
CREATE INDEX idx_customer_wishlist_product ON customer_wishlist(product_id);
```

#### A.3 Payment Gateway Tables

```sql
-- Payment Gateway Configuration
CREATE TABLE payment_gateway (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    sandbox_mode BOOLEAN DEFAULT TRUE,
    merchant_id VARCHAR(255),
    merchant_key TEXT,
    merchant_secret TEXT,
    sandbox_url VARCHAR(500),
    production_url VARCHAR(500),
    active BOOLEAN DEFAULT TRUE,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Payment Transaction Extensions
ALTER TABLE payment_transaction ADD COLUMN IF NOT EXISTS bkash_payment_id VARCHAR(255);
ALTER TABLE payment_transaction ADD COLUMN IF NOT EXISTS bkash_trx_id VARCHAR(255);
ALTER TABLE payment_transaction ADD COLUMN IF NOT EXISTS bkash_customer_msisdn VARCHAR(20);
ALTER TABLE payment_transaction ADD COLUMN IF NOT EXISTS nagad_payment_ref_id VARCHAR(255);
ALTER TABLE payment_transaction ADD COLUMN IF NOT EXISTS nagad_merchant_tx_id VARCHAR(255);
ALTER TABLE payment_transaction ADD COLUMN IF NOT EXISTS sslcommerz_tran_id VARCHAR(255);
ALTER TABLE payment_transaction ADD COLUMN IF NOT EXISTS sslcommerz_val_id VARCHAR(255);
ALTER TABLE payment_transaction ADD COLUMN IF NOT EXISTS wallet_transaction_id INTEGER;

CREATE INDEX idx_payment_transaction_bkash_payment_id ON payment_transaction(bkash_payment_id);
CREATE INDEX idx_payment_transaction_nagad_ref ON payment_transaction(nagad_payment_ref_id);

-- Customer Smart Wallet
CREATE TABLE customer_wallet (
    id SERIAL PRIMARY KEY,
    partner_id INTEGER NOT NULL UNIQUE REFERENCES res_partner(id) ON DELETE CASCADE,
    balance DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    hold_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    is_active BOOLEAN DEFAULT TRUE,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Wallet Transaction History
CREATE TABLE wallet_transaction (
    id SERIAL PRIMARY KEY,
    wallet_id INTEGER NOT NULL REFERENCES customer_wallet(id) ON DELETE CASCADE,
    transaction_type VARCHAR(50) NOT NULL, -- 'credit', 'debit', 'refund', 'transfer'
    amount DECIMAL(12,2) NOT NULL,
    balance_after DECIMAL(12,2) NOT NULL,
    reference_type VARCHAR(50), -- 'order', 'refund', 'subscription', 'manual'
    reference_id INTEGER,
    description TEXT,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_wallet_transaction_wallet ON wallet_transaction(wallet_id);
CREATE INDEX idx_wallet_transaction_create_date ON wallet_transaction(create_date);
```

### Appendix B: API Specifications

#### B.1 Mobile Backend API (FastAPI)

```python
# FastAPI Application for Mobile Backend
from fastapi import FastAPI, Depends, HTTPException, status
from fastapi.security import HTTPBearer, HTTPAuthorizationCredentials
from pydantic import BaseModel
from typing import List, Optional
from datetime import date, datetime

app = FastAPI(
    title="Smart Dairy Mobile API",
    description="Backend for Flutter applications",
    version="1.0.0"
)

security = HTTPBearer()

# Authentication Models
class LoginRequest(BaseModel):
    phone: str
    device_id: str
    fcm_token: Optional[str] = None

class LoginResponse(BaseModel):
    success: bool
    message: str
    otp_sent: bool = False
    session_id: Optional[str] = None

class VerifyOtpRequest(BaseModel):
    phone: str
    otp: str
    session_id: str

class TokenResponse(BaseModel):
    access_token: str
    refresh_token: str
    token_type: str = "bearer"
    expires_in: int = 3600
    user: dict

# Product Models
class ProductResponse(BaseModel):
    id: int
    name: str
    description: Optional[str]
    list_price: float
    image_url: Optional[str]
    is_subscription_eligible: bool
    available_delivery_slots: List[dict]
    rating: float
    review_count: int

class ProductListResponse(BaseModel):
    total: int
    page: int
    per_page: int
    products: List[ProductResponse]

# Subscription Models
class SubscriptionCreateRequest(BaseModel):
    product_id: int
    quantity: float
    frequency: str  # 'daily', 'alternate', 'weekly', 'bi_weekly', 'monthly'
    delivery_time_slot_id: int
    start_date: date
    payment_method: str
    delivery_address_id: int

class SubscriptionResponse(BaseModel):
    id: int
    name: str
    product_name: str
    quantity: float
    frequency: str
    price_unit: float
    total: float
    state: str
    next_delivery_date: Optional[date]
    next_billing_date: Optional[date]

# Order Models
class OrderCreateRequest(BaseModel):
    items: List[dict]
    delivery_address_id: int
    delivery_time_slot_id: int
    payment_method: str
    promo_code: Optional[str] = None
    notes: Optional[str] = None

class OrderResponse(BaseModel):
    id: int
    name: str
    date_order: datetime
    amount_total: float
    state: str
    delivery_date: Optional[date]
    delivery_time_slot: Optional[str]
    tracking_number: Optional[str]

# API Endpoints

@app.post("/auth/login", response_model=LoginResponse)
async def login(request: LoginRequest):
    """
    Initiate phone number login by sending OTP.
    """
    # Implementation
    pass

@app.post("/auth/verify-otp", response_model=TokenResponse)
async def verify_otp(request: VerifyOtpRequest):
    """
    Verify OTP and return authentication tokens.
    """
    # Implementation
    pass

@app.get("/products", response_model=ProductListResponse)
async def list_products(
    category_id: Optional[int] = None,
    search: Optional[str] = None,
    page: int = 1,
    per_page: int = 20,
    sort_by: str = "name",
    sort_order: str = "asc"
):
    """
    List products with filtering, searching, and pagination.
    """
    # Implementation
    pass

@app.get("/products/{product_id}", response_model=ProductResponse)
async def get_product(product_id: int):
    """
    Get detailed product information.
    """
    # Implementation
    pass

@app.get("/subscriptions", response_model=List[SubscriptionResponse])
async def list_subscriptions(
    credentials: HTTPAuthorizationCredentials = Depends(security)
):
    """
    List all subscriptions for authenticated user.
    """
    # Implementation
    pass

@app.post("/subscriptions", response_model=SubscriptionResponse)
async def create_subscription(
    request: SubscriptionCreateRequest,
    credentials: HTTPAuthorizationCredentials = Depends(security)
):
    """
    Create a new subscription.
    """
    # Implementation
    pass

@app.post("/subscriptions/{subscription_id}/pause")
async def pause_subscription(
    subscription_id: int,
    credentials: HTTPAuthorizationCredentials = Depends(security)
):
    """
    Pause an active subscription.
    """
    # Implementation
    pass

@app.post("/subscriptions/{subscription_id}/resume")
async def resume_subscription(
    subscription_id: int,
    credentials: HTTPAuthorizationCredentials = Depends(security)
):
    """
    Resume a paused subscription.
    """
    # Implementation
    pass

@app.post("/subscriptions/{subscription_id}/skip")
async def skip_delivery(
    subscription_id: int,
    delivery_date: date,
    reason: Optional[str] = None,
    credentials: HTTPAuthorizationCredentials = Depends(security)
):
    """
    Skip a scheduled delivery.
    """
    # Implementation
    pass

@app.get("/orders", response_model=List[OrderResponse])
async def list_orders(
    status: Optional[str] = None,
    credentials: HTTPAuthorizationCredentials = Depends(security)
):
    """
    List orders for authenticated user.
    """
    # Implementation
    pass

@app.post("/orders", response_model=OrderResponse)
async def create_order(
    request: OrderCreateRequest,
    credentials: HTTPAuthorizationCredentials = Depends(security)
):
    """
    Create a new order.
    """
    # Implementation
    pass

@app.get("/orders/{order_id}/track")
async def track_order(
    order_id: int,
    credentials: HTTPAuthorizationCredentials = Depends(security)
):
    """
    Get order tracking information.
    """
    # Implementation
    pass

@app.post("/payments/initiate")
async def initiate_payment(
    order_id: int,
    payment_method: str,
    credentials: HTTPAuthorizationCredentials = Depends(security)
):
    """
    Initiate payment for an order.
    Returns payment gateway URL or checkout data.
    """
    # Implementation
    pass

@app.post("/payments/verify/{transaction_id}")
async def verify_payment(
    transaction_id: str,
    gateway_response: dict
):
    """
    Verify payment status from payment gateway callback.
    """
    # Implementation
    pass
```

#### B.2 Payment Gateway Webhooks

```python
# Webhook handlers for payment gateways
from fastapi import FastAPI, Request, HTTPException
import hmac
import hashlib

app = FastAPI()

@app.post("/webhooks/bkash")
async def bkash_webhook(request: Request):
    """
    Handle bKash payment webhooks.
    """
    payload = await request.json()
    
    # Verify webhook signature
    signature = request.headers.get('X-Webhook-Signature')
    if not verify_bkash_signature(payload, signature):
        raise HTTPException(status_code=400, detail="Invalid signature")
    
    # Process payment status update
    payment_id = payload.get('paymentID')
    status = payload.get('transactionStatus')
    
    if status == 'Completed':
        await process_successful_payment('bkash', payment_id, payload)
    elif status == 'Failed':
        await process_failed_payment('bkash', payment_id, payload)
    
    return {"status": "received"}

@app.post("/webhooks/nagad")
async def nagad_webhook(request: Request):
    """
    Handle Nagad payment webhooks.
    """
    payload = await request.json()
    
    # Verify Nagad signature
    if not verify_nagad_signature(payload, request.headers):
        raise HTTPException(status_code=400, detail="Invalid signature")
    
    # Process payment
    merchant_tx_id = payload.get('merchantTxId')
    status = payload.get('status')
    
    if status == 'Success':
        await process_successful_payment('nagad', merchant_tx_id, payload)
    
    return {"status": "received"}

@app.post("/webhooks/sslcommerz")
async def sslcommerz_webhook(request: Request):
    """
    Handle SSLCommerz IPN (Instant Payment Notification).
    """
    form_data = await request.form()
    
    # Validate SSLCommerz response
    if not verify_sslcommerz_ipn(form_data):
        raise HTTPException(status_code=400, detail="Validation failed")
    
    tran_id = form_data.get('tran_id')
    status = form_data.get('status')
    
    if status == 'VALID':
        await process_successful_payment('sslcommerz', tran_id, dict(form_data))
    elif status == 'FAILED':
        await process_failed_payment('sslcommerz', tran_id, dict(form_data))
    
    return {"status": "received"}

def verify_bkash_signature(payload: dict, signature: str) -> bool:
    # Implement bKash signature verification
    pass

def verify_nagad_signature(payload: dict, headers: dict) -> bool:
    # Implement Nagad signature verification
    pass

def verify_sslcommerz_ipn(form_data: dict) -> bool:
    # Implement SSLCommerz IPN validation
    pass
```

---


### Appendix C: Flutter Widget Components

#### C.1 Reusable UI Components

```dart
// lib/core/widgets/custom_button.dart
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

class CustomButton extends StatelessWidget {
  final String text;
  final VoidCallback? onPressed;
  final bool isLoading;
  final bool isOutlined;
  final Color? backgroundColor;
  final Color? textColor;
  final double? width;
  final double height;
  final double borderRadius;
  final IconData? icon;
  
  const CustomButton({
    Key? key,
    required this.text,
    this.onPressed,
    this.isLoading = false,
    this.isOutlined = false,
    this.backgroundColor,
    this.textColor,
    this.width,
    this.height = 48,
    this.borderRadius = 12,
    this.icon,
  }) : super(key: key);
  
  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    
    return SizedBox(
      width: width?.w ?? double.infinity,
      height: height.h,
      child: ElevatedButton(
        onPressed: isLoading ? null : onPressed,
        style: ElevatedButton.styleFrom(
          backgroundColor: isOutlined 
              ? Colors.transparent 
              : (backgroundColor ?? theme.primaryColor),
          foregroundColor: isOutlined 
              ? (textColor ?? theme.primaryColor) 
              : (textColor ?? Colors.white),
          elevation: isOutlined ? 0 : 2,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(borderRadius.r),
            side: isOutlined 
                ? BorderSide(color: backgroundColor ?? theme.primaryColor) 
                : BorderSide.none,
          ),
        ),
        child: isLoading
            ? SizedBox(
                width: 24.w,
                height: 24.h,
                child: CircularProgressIndicator(
                  strokeWidth: 2,
                  valueColor: AlwaysStoppedAnimation<Color>(
                    isOutlined ? (textColor ?? theme.primaryColor) : Colors.white,
                  ),
                ),
              )
            : Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  if (icon != null) ...[
                    Icon(icon, size: 20.w),
                    SizedBox(width: 8.w),
                  ],
                  Text(
                    text,
                    style: TextStyle(
                      fontSize: 16.sp,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ],
              ),
      ),
    );
  }
}

// lib/core/widgets/custom_text_field.dart
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

class CustomTextField extends StatelessWidget {
  final String label;
  final String? hint;
  final TextEditingController? controller;
  final String? Function(String?)? validator;
  final TextInputType keyboardType;
  final bool obscureText;
  final Widget? prefixIcon;
  final Widget? suffixIcon;
  final int maxLines;
  final bool readOnly;
  final VoidCallback? onTap;
  final Function(String)? onChanged;
  final TextInputAction? textInputAction;
  final FocusNode? focusNode;
  
  const CustomTextField({
    Key? key,
    required this.label,
    this.hint,
    this.controller,
    this.validator,
    this.keyboardType = TextInputType.text,
    this.obscureText = false,
    this.prefixIcon,
    this.suffixIcon,
    this.maxLines = 1,
    this.readOnly = false,
    this.onTap,
    this.onChanged,
    this.textInputAction,
    this.focusNode,
  }) : super(key: key);
  
  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: TextStyle(
            fontSize: 14.sp,
            fontWeight: FontWeight.w500,
            color: Colors.grey[700],
          ),
        ),
        SizedBox(height: 8.h),
        TextFormField(
          controller: controller,
          validator: validator,
          keyboardType: keyboardType,
          obscureText: obscureText,
          maxLines: maxLines,
          readOnly: readOnly,
          onTap: onTap,
          onChanged: onChanged,
          textInputAction: textInputAction,
          focusNode: focusNode,
          decoration: InputDecoration(
            hintText: hint,
            prefixIcon: prefixIcon,
            suffixIcon: suffixIcon,
            filled: true,
            fillColor: Colors.grey[100],
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12.r),
              borderSide: BorderSide.none,
            ),
            enabledBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12.r),
              borderSide: BorderSide.none,
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12.r),
              borderSide: BorderSide(color: Theme.of(context).primaryColor, width: 1),
            ),
            errorBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12.r),
              borderSide: const BorderSide(color: Colors.red, width: 1),
            ),
            contentPadding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 16.h),
          ),
        ),
      ],
    );
  }
}

// lib/core/widgets/product_card.dart
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:cached_network_image/cached_network_image.dart';

class ProductCard extends StatelessWidget {
  final String id;
  final String name;
  final String? imageUrl;
  final double price;
  final double? originalPrice;
  final double? rating;
  final int? reviewCount;
  final bool isSubscriptionEligible;
  final VoidCallback onTap;
  final VoidCallback? onAddToCart;
  
  const ProductCard({
    Key? key,
    required this.id,
    required this.name,
    this.imageUrl,
    required this.price,
    this.originalPrice,
    this.rating,
    this.reviewCount,
    this.isSubscriptionEligible = false,
    required this.onTap,
    this.onAddToCart,
  }) : super(key: key);
  
  @override
  Widget build(BuildContext context) {
    final discount = originalPrice != null 
        ? ((originalPrice! - price) / originalPrice! * 100).round() 
        : null;
    
    return GestureDetector(
      onTap: onTap,
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16.r),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.05),
              blurRadius: 10,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Image
            Stack(
              children: [
                ClipRRect(
                  borderRadius: BorderRadius.vertical(top: Radius.circular(16.r)),
                  child: CachedNetworkImage(
                    imageUrl: imageUrl ?? '',
                    height: 120.h,
                    width: double.infinity,
                    fit: BoxFit.cover,
                    placeholder: (context, url) => Container(
                      color: Colors.grey[200],
                      child: const Center(child: CircularProgressIndicator()),
                    ),
                    errorWidget: (context, url, error) => Container(
                      color: Colors.grey[200],
                      child: Icon(Icons.error, color: Colors.grey[400]),
                    ),
                  ),
                ),
                if (discount != null)
                  Positioned(
                    top: 8.h,
                    left: 8.w,
                    child: Container(
                      padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 4.h),
                      decoration: BoxDecoration(
                        color: Colors.red,
                        borderRadius: BorderRadius.circular(8.r),
                      ),
                      child: Text(
                        '-$discount%',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 12.sp,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
                if (isSubscriptionEligible)
                  Positioned(
                    top: 8.h,
                    right: 8.w,
                    child: Container(
                      padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 4.h),
                      decoration: BoxDecoration(
                        color: Colors.green,
                        borderRadius: BorderRadius.circular(8.r),
                      ),
                      child: Text(
                        'Subscribe',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 10.sp,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
              ],
            ),
            
            // Content
            Padding(
              padding: EdgeInsets.all(12.w),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    name,
                    style: TextStyle(
                      fontSize: 14.sp,
                      fontWeight: FontWeight.w600,
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                  SizedBox(height: 4.h),
                  if (rating != null)
                    Row(
                      children: [
                        Icon(Icons.star, size: 14.w, color: Colors.amber),
                        SizedBox(width: 4.w),
                        Text(
                          '$rating',
                          style: TextStyle(
                            fontSize: 12.sp,
                            fontWeight: FontWeight.w500,
                          ),
                        ),
                        if (reviewCount != null)
                          Text(
                            ' ($reviewCount)',
                            style: TextStyle(
                              fontSize: 12.sp,
                              color: Colors.grey,
                            ),
                          ),
                      ],
                    ),
                  SizedBox(height: 8.h),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            '৳${price.toStringAsFixed(0)}',
                            style: TextStyle(
                              fontSize: 16.sp,
                              fontWeight: FontWeight.bold,
                              color: Theme.of(context).primaryColor,
                            ),
                          ),
                          if (originalPrice != null)
                            Text(
                              '৳${originalPrice!.toStringAsFixed(0)}',
                              style: TextStyle(
                                fontSize: 12.sp,
                                color: Colors.grey,
                                decoration: TextDecoration.lineThrough,
                              ),
                            ),
                        ],
                      ),
                      if (onAddToCart != null)
                        GestureDetector(
                          onTap: onAddToCart,
                          child: Container(
                            padding: EdgeInsets.all(8.w),
                            decoration: BoxDecoration(
                              color: Theme.of(context).primaryColor,
                              borderRadius: BorderRadius.circular(8.r),
                            ),
                            child: Icon(
                              Icons.add_shopping_cart,
                              color: Colors.white,
                              size: 20.w,
                            ),
                          ),
                        ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
```

### Appendix D: Testing Strategy

#### D.1 Unit Test Examples

```dart
// test/features/cart/domain/usecases/add_to_cart_test.dart
import 'package:dartz/dartz.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:mockito/mockito.dart';
import 'package:smart_dairy_mobile/features/cart/domain/entities/cart.dart';
import 'package:smart_dairy_mobile/features/cart/domain/entities/cart_item.dart';
import 'package:smart_dairy_mobile/features/cart/domain/repositories/cart_repository.dart';
import 'package:smart_dairy_mobile/features/cart/domain/usecases/add_to_cart.dart';

class MockCartRepository extends Mock implements CartRepository {}

void main() {
  late AddToCartUseCase usecase;
  late MockCartRepository mockCartRepository;

  setUp(() {
    mockCartRepository = MockCartRepository();
    usecase = AddToCartUseCase(mockCartRepository);
  });

  final tCart = Cart(
    id: '1',
    items: [
      CartItem(
        id: '1',
        productId: '101',
        name: 'Fresh Milk 1L',
        image: 'milk.jpg',
        price: 85.0,
        quantity: 2,
        total: 170.0,
      ),
    ],
    subtotal: 170.0,
    tax: 0.0,
    deliveryCharge: 0.0,
    total: 170.0,
    itemCount: 2,
  );

  test('should add item to cart and return updated cart', () async {
    // arrange
    when(mockCartRepository.addToCart(any, any, any))
        .thenAnswer((_) async => Right(tCart));

    // act
    final result = await usecase(AddToCartParams(
      productId: '101',
      quantity: 2,
    ));

    // assert
    expect(result, Right(tCart));
    verify(mockCartRepository.addToCart('101', 2, null));
    verifyNoMoreInteractions(mockCartRepository);
  });
}
```

#### D.2 Widget Test Examples

```dart
// test/features/auth/presentation/pages/login_page_test.dart
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:mocktail/mocktail.dart';
import 'package:smart_dairy_mobile/features/auth/presentation/bloc/auth_bloc.dart';
import 'package:smart_dairy_mobile/features/auth/presentation/pages/login_page.dart';

class MockAuthBloc extends Mock implements AuthBloc {}

void main() {
  late MockAuthBloc mockAuthBloc;

  setUp(() {
    mockAuthBloc = MockAuthBloc();
  });

  Widget makeTestableWidget(Widget body) {
    return BlocProvider<AuthBloc>.value(
      value: mockAuthBloc,
      child: MaterialApp(home: body),
    );
  }

  testWidgets('LoginPage should display phone input and login button', (
    WidgetTester tester,
  ) async {
    // arrange
    when(() => mockAuthBloc.state).thenReturn(AuthInitial());
    when(() => mockAuthBloc.stream).thenAnswer((_) => Stream.value(AuthInitial()));

    // act
    await tester.pumpWidget(makeTestableWidget(const LoginPage()));

    // assert
    expect(find.byType(TextFormField), findsOneWidget);
    expect(find.text('Phone Number'), findsOneWidget);
    expect(find.text('Continue'), findsOneWidget);
  });

  testWidgets('should show loading indicator when state is AuthLoading', (
    WidgetTester tester,
  ) async {
    // arrange
    when(() => mockAuthBloc.state).thenReturn(AuthLoading());
    when(() => mockAuthBloc.stream).thenAnswer((_) => Stream.value(AuthLoading()));

    // act
    await tester.pumpWidget(makeTestableWidget(const LoginPage()));

    // assert
    expect(find.byType(CircularProgressIndicator), findsOneWidget);
  });
}
```

#### D.3 Integration Test Example

```dart
// integration_test/app_test.dart
import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:smart_dairy_mobile/main.dart' as app;

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('End-to-End App Test', () {
    testWidgets('Complete login and browse products flow', (tester) async {
      // Launch app
      app.main();
      await tester.pumpAndSettle();

      // Verify login page is displayed
      expect(find.text('Welcome to Smart Dairy'), findsOneWidget);

      // Enter phone number
      await tester.enterText(find.byType(TextFormField), '01712345678');
      await tester.tap(find.text('Continue'));
      await tester.pumpAndSettle();

      // Should navigate to OTP page
      expect(find.text('Enter OTP'), findsOneWidget);

      // Enter OTP
      await tester.enterText(find.byType(TextFormField).first, '1');
      await tester.enterText(find.byType(TextFormField).at(1), '2');
      await tester.enterText(find.byType(TextFormField).at(2), '3');
      await tester.enterText(find.byType(TextFormField).at(3), '4');
      await tester.tap(find.text('Verify'));
      await tester.pumpAndSettle();

      // Should navigate to home page
      expect(find.text('Home'), findsOneWidget);
      expect(find.byType(ProductCard), findsWidgets);
    });
  });
}
```

### Appendix E: CI/CD Configuration

#### E.1 GitHub Actions Workflow

```yaml
# .github/workflows/mobile_ci.yml
name: Mobile CI/CD

on:
  push:
    branches: [main, develop]
    paths:
      - 'mobile/**'
  pull_request:
    branches: [main, develop]
    paths:
      - 'mobile/**'

jobs:
  test:
    runs-on: ubuntu-latest
    defaults:
      run:
        working-directory: mobile

    steps:
      - uses: actions/checkout@v4

      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'

      - name: Get dependencies
        run: flutter pub get

      - name: Analyze code
        run: flutter analyze

      - name: Run tests
        run: flutter test --coverage

      - name: Upload coverage
        uses: codecov/codecov-action@v3
        with:
          files: mobile/coverage/lcov.info

  build-android:
    needs: test
    runs-on: ubuntu-latest
    if: github.ref == 'refs/heads/main'
    defaults:
      run:
        working-directory: mobile

    steps:
      - uses: actions/checkout@v4

      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'

      - name: Setup Java
        uses: actions/setup-java@v4
        with:
          java-version: '17'
          distribution: 'temurin'

      - name: Get dependencies
        run: flutter pub get

      - name: Build APK
        run: flutter build apk --release

      - name: Build App Bundle
        run: flutter build appbundle --release

      - name: Upload APK
        uses: actions/upload-artifact@v4
        with:
          name: release-apk
          path: mobile/build/app/outputs/flutter-apk/app-release.apk

      - name: Upload AAB
        uses: actions/upload-artifact@v4
        with:
          name: release-aab
          path: mobile/build/app/outputs/bundle/release/app-release.aab

  build-ios:
    needs: test
    runs-on: macos-latest
    if: github.ref == 'refs/heads/main'
    defaults:
      run:
        working-directory: mobile

    steps:
      - uses: actions/checkout@v4

      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'

      - name: Get dependencies
        run: flutter pub get

      - name: Build iOS
        run: flutter build ios --release --no-codesign

      - name: Create IPA
        run: |
          mkdir -p build/ios/ipa
          cd build/ios/iphoneos
          mkdir -p Payload/Runner.app/Frameworks
          cp -r Runner.app Payload/
          zip -r ../ipa/Runner.ipa Payload

      - name: Upload IPA
        uses: actions/upload-artifact@v4
        with:
          name: release-ipa
          path: mobile/build/ios/ipa/Runner.ipa
```

#### E.2 Fastlane Configuration

```ruby
# mobile/android/fastlane/Fastfile
default_platform(:android)

platform :android do
  desc "Run tests"
  lane :test do
    gradle(task: "test")
  end

  desc "Build release APK"
  lane :build_apk do
    gradle(
      task: "assemble",
      build_type: "Release",
      properties: {
        "android.injected.signing.store.file" => ENV["KEYSTORE_FILE"],
        "android.injected.signing.store.password" => ENV["KEYSTORE_PASSWORD"],
        "android.injected.signing.key.alias" => ENV["KEY_ALIAS"],
        "android.injected.signing.key.password" => ENV["KEY_PASSWORD"],
      }
    )
  end

  desc "Build and deploy to Play Store"
  lane :deploy do
    gradle(
      task: "bundle",
      build_type: "Release"
    )
    upload_to_play_store(
      track: 'internal',
      aab: '../build/app/outputs/bundle/release/app-release.aab'
    )
  end
end

# mobile/ios/fastlane/Fastfile
default_platform(:ios)

platform :ios do
  desc "Build and deploy to TestFlight"
  lane :beta do
    increment_build_number(xcodeproj: "Runner.xcodeproj")
    build_app(workspace: "Runner.xcworkspace", scheme: "Runner")
    upload_to_testflight(
      skip_waiting_for_build_processing: true
    )
  end

  desc "Build and deploy to App Store"
  lane :release do
    increment_build_number(xcodeproj: "Runner.xcodeproj")
    build_app(workspace: "Runner.xcworkspace", scheme: "Runner")
    upload_to_app_store(
      submit_for_review: true,
      automatic_release: false
    )
  end
end
```

---

## 15. CONCLUSION

### Phase 2 Summary

Phase 2 of the Smart Dairy Smart Portal + ERP Implementation (Days 101-200) establishes the complete operations infrastructure enabling direct customer engagement through e-commerce and mobile applications. This phase transforms Smart Dairy from a backend operations system into a customer-facing digital platform.

### Key Achievements by End of Phase 2

1. **E-commerce Platform**: Fully functional B2C online store with product catalog, shopping cart, checkout, and subscription management
2. **Mobile Applications**: Customer app (iOS/Android) and Field Sales app with offline capability
3. **Payment Integration**: All major Bangladeshi payment gateways integrated (bKash, Nagad, Rocket, Cards)
4. **Subscription Engine**: Automated recurring order generation with pause/skip/modify capabilities
5. **Advanced Farm Management**: Health records, vaccination tracking, breeding management
6. **Reporting Suite**: Executive dashboards and operational reports

### Budget Summary (Phase 2)

| Category | Amount (BDT) | Amount (USD) |
|----------|--------------|--------------|
| Development Costs | 80,00,000 | $72,727 |
| Infrastructure (Cloud) | 15,00,000 | $13,636 |
| Third-party Services | 12,00,000 | $10,909 |
| Contingency (10%) | 10,70,000 | $9,727 |
| **Total Phase 2 Budget** | **1,17,70,000** | **$107,000** |

### Target Metrics at Phase 2 Completion

- **Active Subscriptions**: 100+ customers
- **Monthly Online Orders**: 500+ orders
- **Mobile App Downloads**: 1,000+ (combined platforms)
- **Customer Satisfaction**: >4.0/5.0
- **System Uptime**: 99.5%
- **Payment Success Rate**: >95%

### Next Phase Preview (Phase 3: Days 201-300)

Phase 3 will focus on:
- Advanced Analytics and Business Intelligence
- IoT Integration for automated milk collection
- AI/ML for demand forecasting
- Advanced Customer Loyalty Programs
- Multi-location warehouse management
- Advanced Financial Management modules

---

*Document Version: 1.0*  
*Last Updated: January 2026*  
*Prepared by: Smart Dairy Implementation Team*

