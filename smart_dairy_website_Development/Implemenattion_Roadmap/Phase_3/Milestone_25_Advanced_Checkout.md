# Milestone 25: Advanced Checkout & Cart

## Smart Dairy Digital Smart Portal + ERP — Phase 3: E-Commerce, Mobile & Analytics

| Field            | Detail                                                        |
|------------------|---------------------------------------------------------------|
| **Milestone**    | 25 of 30 (5 of 10 in Phase 3)                                |
| **Title**        | Advanced Checkout & Cart                                      |
| **Phase**        | Phase 3 — E-Commerce, Mobile & Analytics (Part A: E-Commerce & Mobile) |
| **Days**         | Days 241–250 (of 300)                                        |
| **Version**      | 1.0                                                          |
| **Status**       | Draft                                                        |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend/Mobile Lead) |
| **Last Updated** | 2026-02-03                                                   |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Build an advanced checkout system with promotional engine supporting coupons, BOGO, and bundle deals, stackable discount calculation, multi-address delivery support, flexible delivery scheduling with express option, guest checkout capability, abandoned cart recovery via email/SMS, enhanced mobile checkout experience, and Part A completion testing for Milestones 21-25.

### 1.2 Objectives

1. Design promotional engine with multiple promotion types
2. Implement coupon management system with validation rules
3. Build stackable discount calculation engine
4. Create multi-address delivery support for gift orders
5. Implement flexible delivery scheduling with time slots
6. Add express delivery option with premium pricing
7. Build guest checkout flow with account creation option
8. Implement abandoned cart recovery (1hr, 24hr, 72hr emails)
9. Enhance mobile checkout with one-tap payment
10. Complete Part A integration testing (MS-21 to MS-25)

### 1.3 Key Deliverables

| # | Deliverable | Owner | Day |
|---|-------------|-------|-----|
| D25.1 | Promotional engine model | Dev 2 | 241 |
| D25.2 | Coupon management system | Dev 2 | 241-242 |
| D25.3 | BOGO and bundle promotions | Dev 2 | 242-243 |
| D25.4 | Stackable discount engine | Dev 1 | 243 |
| D25.5 | Multi-address delivery | Dev 1 | 244 |
| D25.6 | Express delivery option | Dev 1 | 244-245 |
| D25.7 | Guest checkout flow | Dev 2 | 245-246 |
| D25.8 | Abandoned cart recovery | Dev 2 | 246-247 |
| D25.9 | Mobile checkout enhancement | Dev 3 | 247-248 |
| D25.10 | Part A integration testing | All | 249-250 |

### 1.4 Success Criteria

- [ ] Promotional engine supporting 5+ promotion types
- [ ] Coupon validation with usage limits and date ranges
- [ ] Stackable discounts calculating correctly
- [ ] Multi-address delivery creating separate shipments
- [ ] Express delivery available with 2-hour window
- [ ] Guest checkout conversion rate > 3%
- [ ] Abandoned cart recovery rate > 15%
- [ ] Mobile checkout completion time < 60 seconds
- [ ] Part A integration tests passing > 95%

---

## 2. Requirement Traceability Matrix

| BRD Req ID | Requirement Description | SRS Trace | Day | Owner |
|-----------|------------------------|-----------|-----|-------|
| BRD-CHK-001 | Promotional engine | SRS-CHK-001 | 241 | Dev 2 |
| BRD-CHK-002 | Coupon management | SRS-CHK-002 | 241-242 | Dev 2 |
| BRD-CHK-003 | Stackable discounts | SRS-CHK-003 | 243 | Dev 1 |
| BRD-CHK-004 | Multi-address delivery | SRS-CHK-004 | 244 | Dev 1 |
| BRD-CHK-005 | Express delivery | SRS-CHK-005 | 244-245 | Dev 1 |
| BRD-CHK-006 | Guest checkout | SRS-CHK-006 | 245-246 | Dev 2 |
| BRD-CHK-007 | Cart recovery | SRS-CHK-007 | 246-247 | Dev 2 |
| BRD-CHK-008 | Mobile checkout | SRS-CHK-008 | 247-248 | Dev 3 |

---

## 3. Technical Implementation

### 3.1 Promotional Engine Model

```python
# smart_dairy_addons/smart_checkout/models/promotion.py
from odoo import models, fields, api, _
from odoo.exceptions import ValidationError

class Promotion(models.Model):
    _name = 'checkout.promotion'
    _description = 'Checkout Promotion'
    _order = 'priority, id'

    name = fields.Char(required=True)
    code = fields.Char(string='Promo Code')
    promotion_type = fields.Selection([
        ('percentage', 'Percentage Discount'),
        ('fixed', 'Fixed Amount Discount'),
        ('bogo', 'Buy One Get One'),
        ('bundle', 'Bundle Deal'),
        ('free_shipping', 'Free Shipping'),
        ('free_product', 'Free Product'),
        ('tiered', 'Tiered Discount'),
    ], required=True)

    # Discount values
    discount_percentage = fields.Float()
    discount_amount = fields.Monetary(currency_field='currency_id')
    max_discount = fields.Monetary(currency_field='currency_id')
    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.company.currency_id)

    # Validity
    date_start = fields.Datetime(required=True)
    date_end = fields.Datetime(required=True)
    active = fields.Boolean(default=True)

    # Usage limits
    usage_limit = fields.Integer(default=0)  # 0 = unlimited
    usage_per_customer = fields.Integer(default=1)
    current_usage = fields.Integer(readonly=True)

    # Conditions
    min_order_amount = fields.Monetary(currency_field='currency_id')
    min_quantity = fields.Integer()
    applicable_product_ids = fields.Many2many('product.product')
    applicable_category_ids = fields.Many2many('product.category')
    excluded_product_ids = fields.Many2many('product.product', 'promotion_excluded_products_rel')
    customer_tier_ids = fields.Many2many('loyalty.tier')

    # BOGO specific
    bogo_buy_quantity = fields.Integer(default=1)
    bogo_get_quantity = fields.Integer(default=1)
    bogo_get_product_id = fields.Many2one('product.product')
    bogo_discount_percentage = fields.Float(default=100.0)  # 100 = free

    # Bundle specific
    bundle_product_ids = fields.Many2many('product.product', 'promotion_bundle_products_rel')
    bundle_price = fields.Monetary(currency_field='currency_id')

    # Stackability
    stackable = fields.Boolean(default=False, help='Can be combined with other promotions')
    priority = fields.Integer(default=10, help='Lower = higher priority')

    def validate_promotion(self, order):
        """Validate if promotion can be applied to order"""
        self.ensure_one()
        errors = []

        # Check dates
        now = fields.Datetime.now()
        if not (self.date_start <= now <= self.date_end):
            errors.append(_("Promotion is not currently active"))

        # Check usage limits
        if self.usage_limit > 0 and self.current_usage >= self.usage_limit:
            errors.append(_("Promotion usage limit reached"))

        # Check customer usage
        if self.usage_per_customer > 0:
            customer_usage = self.env['promotion.usage'].search_count([
                ('promotion_id', '=', self.id),
                ('partner_id', '=', order.partner_id.id)
            ])
            if customer_usage >= self.usage_per_customer:
                errors.append(_("You have already used this promotion"))

        # Check minimum order
        if self.min_order_amount and order.amount_untaxed < self.min_order_amount:
            errors.append(_("Minimum order amount not met"))

        # Check customer tier
        if self.customer_tier_ids:
            member = self.env['loyalty.member'].search([('partner_id', '=', order.partner_id.id)])
            if not member or member.tier_id not in self.customer_tier_ids:
                errors.append(_("Promotion not available for your membership tier"))

        return errors

    def calculate_discount(self, order):
        """Calculate discount amount for order"""
        self.ensure_one()

        if self.promotion_type == 'percentage':
            discount = order.amount_untaxed * (self.discount_percentage / 100)
            if self.max_discount:
                discount = min(discount, self.max_discount)
            return discount

        elif self.promotion_type == 'fixed':
            return min(self.discount_amount, order.amount_untaxed)

        elif self.promotion_type == 'free_shipping':
            return order.amount_delivery

        elif self.promotion_type == 'bogo':
            return self._calculate_bogo_discount(order)

        elif self.promotion_type == 'bundle':
            return self._calculate_bundle_discount(order)

        elif self.promotion_type == 'tiered':
            return self._calculate_tiered_discount(order)

        return 0.0

    def _calculate_bogo_discount(self, order):
        """Calculate BOGO discount"""
        applicable_lines = order.order_line.filtered(
            lambda l: l.product_id in self.applicable_product_ids or
            l.product_id.categ_id in self.applicable_category_ids
        )

        total_qty = sum(applicable_lines.mapped('product_uom_qty'))
        sets = int(total_qty // (self.bogo_buy_quantity + self.bogo_get_quantity))

        if sets <= 0:
            return 0.0

        # Get the price of the free items
        free_qty = sets * self.bogo_get_quantity
        sorted_lines = applicable_lines.sorted(key=lambda l: l.price_unit)

        discount = 0.0
        remaining_free = free_qty
        for line in sorted_lines:
            if remaining_free <= 0:
                break
            free_from_line = min(remaining_free, line.product_uom_qty)
            discount += free_from_line * line.price_unit * (self.bogo_discount_percentage / 100)
            remaining_free -= free_from_line

        return discount


class PromotionUsage(models.Model):
    _name = 'promotion.usage'
    _description = 'Promotion Usage Record'

    promotion_id = fields.Many2one('checkout.promotion', required=True, ondelete='cascade')
    partner_id = fields.Many2one('res.partner', required=True)
    order_id = fields.Many2one('sale.order', required=True)
    discount_amount = fields.Monetary(currency_field='currency_id')
    currency_id = fields.Many2one('res.currency')
    usage_date = fields.Datetime(default=fields.Datetime.now)
```

### 3.2 Coupon Management

```python
# smart_dairy_addons/smart_checkout/models/coupon.py
class Coupon(models.Model):
    _name = 'checkout.coupon'
    _description = 'Coupon Code'

    code = fields.Char(required=True, copy=False)
    promotion_id = fields.Many2one('checkout.promotion', required=True)
    state = fields.Selection([
        ('active', 'Active'),
        ('used', 'Used'),
        ('expired', 'Expired'),
        ('cancelled', 'Cancelled'),
    ], default='active')

    partner_id = fields.Many2one('res.partner', string='Assigned Customer')
    used_by_id = fields.Many2one('res.partner', string='Used By')
    used_order_id = fields.Many2one('sale.order', string='Used in Order')
    used_date = fields.Datetime()

    valid_from = fields.Datetime()
    valid_until = fields.Datetime()

    _sql_constraints = [
        ('code_uniq', 'unique(code)', 'Coupon code must be unique!')
    ]

    def apply_coupon(self, order):
        """Apply coupon to order"""
        self.ensure_one()

        if self.state != 'active':
            raise ValidationError(_("This coupon is not active"))

        if self.partner_id and self.partner_id != order.partner_id:
            raise ValidationError(_("This coupon is not valid for your account"))

        if self.valid_until and self.valid_until < fields.Datetime.now():
            raise ValidationError(_("This coupon has expired"))

        # Validate promotion
        errors = self.promotion_id.validate_promotion(order)
        if errors:
            raise ValidationError('\n'.join(errors))

        # Calculate and apply discount
        discount = self.promotion_id.calculate_discount(order)

        return {
            'promotion_id': self.promotion_id.id,
            'coupon_id': self.id,
            'discount_amount': discount,
        }
```

### 3.3 Abandoned Cart Recovery

```python
# smart_dairy_addons/smart_checkout/models/abandoned_cart.py
class AbandonedCart(models.Model):
    _name = 'abandoned.cart'
    _description = 'Abandoned Cart Tracking'

    partner_id = fields.Many2one('res.partner')
    session_id = fields.Char(string='Session ID')
    email = fields.Char()
    phone = fields.Char()

    cart_data = fields.Json(string='Cart Contents')
    cart_total = fields.Monetary(currency_field='currency_id')
    currency_id = fields.Many2one('res.currency')

    abandoned_at = fields.Datetime()
    recovered = fields.Boolean(default=False)
    recovered_order_id = fields.Many2one('sale.order')

    # Recovery attempts
    recovery_1hr_sent = fields.Boolean(default=False)
    recovery_24hr_sent = fields.Boolean(default=False)
    recovery_72hr_sent = fields.Boolean(default=False)

    recovery_email_opened = fields.Boolean(default=False)
    recovery_link_clicked = fields.Boolean(default=False)

    @api.model
    def process_abandoned_carts(self):
        """Process abandoned carts and send recovery emails"""
        now = fields.Datetime.now()

        # 1 hour recovery
        carts_1hr = self.search([
            ('abandoned_at', '<=', now - timedelta(hours=1)),
            ('abandoned_at', '>', now - timedelta(hours=2)),
            ('recovery_1hr_sent', '=', False),
            ('recovered', '=', False),
        ])
        for cart in carts_1hr:
            cart._send_recovery_email('1hr')
            cart.recovery_1hr_sent = True

        # 24 hour recovery
        carts_24hr = self.search([
            ('abandoned_at', '<=', now - timedelta(hours=24)),
            ('abandoned_at', '>', now - timedelta(hours=25)),
            ('recovery_24hr_sent', '=', False),
            ('recovered', '=', False),
        ])
        for cart in carts_24hr:
            cart._send_recovery_email('24hr')
            cart.recovery_24hr_sent = True

        # 72 hour recovery with incentive
        carts_72hr = self.search([
            ('abandoned_at', '<=', now - timedelta(hours=72)),
            ('abandoned_at', '>', now - timedelta(hours=73)),
            ('recovery_72hr_sent', '=', False),
            ('recovered', '=', False),
        ])
        for cart in carts_72hr:
            cart._send_recovery_email('72hr', include_discount=True)
            cart.recovery_72hr_sent = True

    def _send_recovery_email(self, recovery_type, include_discount=False):
        """Send cart recovery email"""
        self.ensure_one()

        template_map = {
            '1hr': 'smart_checkout.email_template_cart_recovery_1hr',
            '24hr': 'smart_checkout.email_template_cart_recovery_24hr',
            '72hr': 'smart_checkout.email_template_cart_recovery_72hr',
        }

        template = self.env.ref(template_map[recovery_type])

        # Generate recovery link with token
        recovery_token = self._generate_recovery_token()
        recovery_link = f"/shop/cart/recover/{recovery_token}"

        # Create incentive coupon if needed
        coupon_code = None
        if include_discount:
            coupon = self._create_recovery_coupon()
            coupon_code = coupon.code

        template.send_mail(
            self.id,
            email_values={
                'email_to': self.email,
            },
            force_send=True,
        )
```

### 3.4 Guest Checkout

```python
# Guest checkout controller
@http.route('/shop/checkout/guest', type='json', auth='public', website=True)
def guest_checkout(self, **kwargs):
    """Process guest checkout"""
    email = kwargs.get('email')
    phone = kwargs.get('phone')

    # Validate email
    if not email or not re.match(r'^[\w\.-]+@[\w\.-]+\.\w+$', email):
        return {'error': 'Valid email is required'}

    # Check if email already has account
    existing_partner = request.env['res.partner'].sudo().search([
        ('email', '=', email)
    ], limit=1)

    if existing_partner:
        return {
            'has_account': True,
            'message': 'An account with this email exists. Please login.'
        }

    # Create guest partner
    guest_partner = request.env['res.partner'].sudo().create({
        'name': kwargs.get('name', email.split('@')[0]),
        'email': email,
        'phone': phone,
        'is_guest': True,
    })

    # Transfer cart to guest partner
    order = request.website.sale_get_order()
    if order:
        order.sudo().write({'partner_id': guest_partner.id})

    return {
        'success': True,
        'partner_id': guest_partner.id,
        'offer_account': True,  # Offer to create account post-purchase
    }
```

---

## 4. Technical Specifications

### 4.1 Data Models

| Model | Description | Key Fields |
|-------|-------------|------------|
| `checkout.promotion` | Promotion rules | type, conditions, discount values |
| `checkout.coupon` | Coupon codes | code, state, partner_id |
| `promotion.usage` | Usage tracking | promotion_id, order_id |
| `abandoned.cart` | Cart recovery | cart_data, recovery flags |

### 4.2 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/checkout/validate-coupon` | POST | Validate coupon code |
| `/api/v1/checkout/apply-promotion` | POST | Apply promotion to cart |
| `/api/v1/checkout/guest` | POST | Guest checkout |
| `/api/v1/cart/recover/{token}` | GET | Recover abandoned cart |

---

## 5. Part A Integration Testing

### 5.1 Test Scenarios

| Test ID | Scenario | Milestones | Expected |
|---------|----------|------------|----------|
| INT-PA-001 | Search → Add to Cart → Checkout | MS-21, MS-25 | Order created |
| INT-PA-002 | Mobile login → Browse → Purchase | MS-22, MS-25 | Mobile order |
| INT-PA-003 | Subscription → First Delivery | MS-23, MS-25 | Delivery scheduled |
| INT-PA-004 | Earn points → Redeem at checkout | MS-24, MS-25 | Points applied |
| INT-PA-005 | Coupon + Loyalty in single order | MS-24, MS-25 | Both discounts |

---

## 6. Dependencies & Handoffs

### Prerequisites
- E-commerce (MS-21)
- Mobile app (MS-22)
- Subscriptions (MS-23)
- Loyalty (MS-24)

### Outputs
| Output | Consumer | Description |
|--------|----------|-------------|
| Promotional engine | MS-27 (Analytics) | Promotion analytics |
| Cart data | MS-27 (Analytics) | Conversion tracking |

---

**Document End**

*Milestone 25: Advanced Checkout & Cart*
*Smart Dairy Digital Smart Portal + ERP System*
*Phase 3 — E-Commerce, Mobile & Analytics*
*Version 1.0 | February 2026*
