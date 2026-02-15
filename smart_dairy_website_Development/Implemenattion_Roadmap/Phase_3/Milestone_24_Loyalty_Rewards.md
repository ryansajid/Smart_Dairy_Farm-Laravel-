# Milestone 24: Loyalty & Rewards Program

## Smart Dairy Digital Smart Portal + ERP — Phase 3: E-Commerce, Mobile & Analytics

| Field            | Detail                                                        |
|------------------|---------------------------------------------------------------|
| **Milestone**    | 24 of 30 (4 of 10 in Phase 3)                                |
| **Title**        | Loyalty & Rewards Program                                     |
| **Phase**        | Phase 3 — E-Commerce, Mobile & Analytics (Part A: E-Commerce & Mobile) |
| **Days**         | Days 231–240 (of 300)                                        |
| **Version**      | 1.0                                                          |
| **Status**       | Draft                                                        |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend/Mobile Lead) |
| **Last Updated** | 2026-02-03                                                   |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Implement a gamified loyalty and rewards program with tier-based membership (Silver, Gold, Platinum), configurable points earning on purchases, points redemption at checkout, rewards catalog with exclusive items, referral bonus program with unique codes, birthday and special occasion rewards, points expiration management, and mobile loyalty card with points history.

### 1.2 Objectives

1. Design loyalty tier system with Silver (0-4,999), Gold (5,000-14,999), Platinum (15,000+)
2. Implement points earning on purchases with tier-based multipliers
3. Build points redemption engine at checkout (web and mobile)
4. Create rewards catalog with exclusive items and experiences
5. Develop referral program with unique codes and tracking
6. Implement birthday and special occasion rewards automation
7. Build points expiration management with notifications
8. Create mobile loyalty card with QR code for in-store use
9. Develop loyalty dashboard for customers and admins
10. Integrate with subscription module for subscription-based rewards

### 1.3 Key Deliverables

| # | Deliverable | Owner | Day |
|---|-------------|-------|-----|
| D24.1 | Loyalty tier model and configuration | Dev 2 | 231 |
| D24.2 | Points earning rules engine | Dev 2 | 231-232 |
| D24.3 | Points transaction model | Dev 1 | 232 |
| D24.4 | Points redemption at checkout | Dev 2 | 233 |
| D24.5 | Rewards catalog module | Dev 2 | 234 |
| D24.6 | Referral program | Dev 2 | 235 |
| D24.7 | Birthday rewards automation | Dev 1 | 236 |
| D24.8 | Points expiration system | Dev 1 | 236-237 |
| D24.9 | Mobile loyalty card UI | Dev 3 | 237-238 |
| D24.10 | Customer loyalty dashboard | Dev 3 | 238-239 |
| D24.11 | Admin loyalty dashboard | Dev 3 | 239 |
| D24.12 | Integration testing | All | 240 |

### 1.4 Success Criteria

- [ ] Three-tier system (Silver/Gold/Platinum) operational
- [ ] Points earning correctly applying tier multipliers (1x/1.5x/2x)
- [ ] Points redemption working at checkout (1 point = ৳0.10)
- [ ] Referral codes generating and tracking successfully
- [ ] Birthday rewards sent automatically on customer birthdays
- [ ] Points expiring after 12 months with 30-day warning
- [ ] Mobile loyalty card displaying current tier and points
- [ ] 30% of customers enrolled in loyalty program

### 1.5 Tier Structure

| Tier | Points Range | Earning Rate | Benefits |
|------|-------------|--------------|----------|
| Silver | 0 - 4,999 | 1 point per ৳10 | Base benefits |
| Gold | 5,000 - 14,999 | 1.5 points per ৳10 | Free delivery, 5% bonus |
| Platinum | 15,000+ | 2 points per ৳10 | Priority support, 10% bonus, exclusive access |

---

## 2. Requirement Traceability Matrix

| BRD Req ID | Requirement Description | SRS Trace | Day | Owner |
|-----------|------------------------|-----------|-----|-------|
| BRD-LOY-001 | Tier-based membership | SRS-LOY-001 | 231 | Dev 2 |
| BRD-LOY-002 | Points earning | SRS-LOY-002 | 231-232 | Dev 2 |
| BRD-LOY-003 | Points redemption | SRS-LOY-003 | 233 | Dev 2 |
| BRD-LOY-004 | Rewards catalog | SRS-LOY-004 | 234 | Dev 2 |
| BRD-LOY-005 | Referral program | SRS-LOY-005 | 235 | Dev 2 |
| BRD-LOY-006 | Birthday rewards | SRS-LOY-006 | 236 | Dev 1 |
| BRD-LOY-007 | Points expiration | SRS-LOY-007 | 236-237 | Dev 1 |
| BRD-LOY-008 | Mobile loyalty card | SRS-LOY-008 | 237-238 | Dev 3 |

---

## 3. Day-by-Day Breakdown

### Day 231-232 — Loyalty Tier and Points Earning

```python
# smart_dairy_addons/smart_loyalty/models/loyalty_tier.py
from odoo import models, fields, api

class LoyaltyTier(models.Model):
    _name = 'loyalty.tier'
    _description = 'Loyalty Tier'
    _order = 'min_points'

    name = fields.Char(required=True)
    code = fields.Char(required=True)
    min_points = fields.Integer(string='Minimum Points', required=True)
    max_points = fields.Integer(string='Maximum Points')
    earning_multiplier = fields.Float(string='Earning Multiplier', default=1.0)
    benefits = fields.Html(string='Benefits Description')
    badge_image = fields.Image(string='Badge Image')
    color = fields.Char(string='Color Code', default='#6B7280')

    free_delivery = fields.Boolean(default=False)
    priority_support = fields.Boolean(default=False)
    exclusive_access = fields.Boolean(default=False)
    bonus_percentage = fields.Float(string='Bonus % on Points', default=0.0)


class LoyaltyMember(models.Model):
    _name = 'loyalty.member'
    _description = 'Loyalty Program Member'
    _inherit = ['mail.thread']

    partner_id = fields.Many2one('res.partner', required=True, ondelete='cascade')
    member_code = fields.Char(string='Member Code', readonly=True)
    tier_id = fields.Many2one('loyalty.tier', compute='_compute_tier', store=True)

    total_points = fields.Integer(compute='_compute_points', store=True)
    available_points = fields.Integer(compute='_compute_points', store=True)
    lifetime_points = fields.Integer(compute='_compute_points', store=True)

    referral_code = fields.Char(string='Referral Code', readonly=True)
    referred_by_id = fields.Many2one('loyalty.member', string='Referred By')
    referral_count = fields.Integer(compute='_compute_referrals')

    transaction_ids = fields.One2many('loyalty.transaction', 'member_id')
    join_date = fields.Date(default=fields.Date.today)

    @api.depends('transaction_ids.points', 'transaction_ids.state')
    def _compute_points(self):
        for member in self:
            transactions = member.transaction_ids.filtered(lambda t: t.state == 'confirmed')
            member.lifetime_points = sum(t.points for t in transactions if t.points > 0)
            member.available_points = sum(t.points for t in transactions)
            member.total_points = member.lifetime_points

    @api.depends('lifetime_points')
    def _compute_tier(self):
        tiers = self.env['loyalty.tier'].search([], order='min_points desc')
        for member in self:
            for tier in tiers:
                if member.lifetime_points >= tier.min_points:
                    member.tier_id = tier
                    break


class LoyaltyTransaction(models.Model):
    _name = 'loyalty.transaction'
    _description = 'Loyalty Points Transaction'
    _order = 'create_date desc'

    member_id = fields.Many2one('loyalty.member', required=True)
    transaction_type = fields.Selection([
        ('earn', 'Earned'),
        ('redeem', 'Redeemed'),
        ('expire', 'Expired'),
        ('bonus', 'Bonus'),
        ('referral', 'Referral Bonus'),
        ('birthday', 'Birthday Reward'),
        ('adjustment', 'Manual Adjustment'),
    ], required=True)
    points = fields.Integer(required=True)  # Positive for earn, negative for redeem
    description = fields.Char()
    reference = fields.Char()  # Order reference, etc.
    order_id = fields.Many2one('sale.order')
    expiry_date = fields.Date()
    state = fields.Selection([
        ('pending', 'Pending'),
        ('confirmed', 'Confirmed'),
        ('cancelled', 'Cancelled'),
    ], default='pending')
```

### Day 233-234 — Points Redemption and Rewards Catalog

```python
# smart_dairy_addons/smart_loyalty/models/loyalty_reward.py
class LoyaltyReward(models.Model):
    _name = 'loyalty.reward'
    _description = 'Loyalty Reward Item'

    name = fields.Char(required=True)
    reward_type = fields.Selection([
        ('discount', 'Discount Coupon'),
        ('product', 'Free Product'),
        ('delivery', 'Free Delivery'),
        ('experience', 'Experience'),
    ], required=True)

    points_required = fields.Integer(required=True)
    tier_ids = fields.Many2many('loyalty.tier', string='Available to Tiers')

    # For discount type
    discount_percentage = fields.Float()
    max_discount_amount = fields.Monetary(currency_field='currency_id')

    # For product type
    product_id = fields.Many2one('product.product')
    product_qty = fields.Float(default=1.0)

    quantity_available = fields.Integer(default=-1)  # -1 for unlimited
    quantity_redeemed = fields.Integer(readonly=True)

    valid_from = fields.Date()
    valid_until = fields.Date()
    active = fields.Boolean(default=True)
    image = fields.Image()
```

### Day 235 — Referral Program

```python
# smart_dairy_addons/smart_loyalty/models/referral_program.py
class LoyaltyReferral(models.Model):
    _name = 'loyalty.referral'
    _description = 'Referral Record'

    referrer_id = fields.Many2one('loyalty.member', string='Referrer', required=True)
    referee_id = fields.Many2one('loyalty.member', string='Referred Customer')
    referral_code = fields.Char(related='referrer_id.referral_code')

    state = fields.Selection([
        ('pending', 'Pending Signup'),
        ('signed_up', 'Signed Up'),
        ('first_purchase', 'First Purchase Made'),
        ('rewarded', 'Reward Given'),
    ], default='pending')

    referrer_points = fields.Integer(string='Points to Referrer', default=500)
    referee_points = fields.Integer(string='Points to Referee', default=200)

    signup_date = fields.Datetime()
    first_purchase_date = fields.Datetime()
    reward_date = fields.Datetime()

    def action_process_reward(self):
        """Award points to both referrer and referee"""
        self.ensure_one()
        if self.state != 'first_purchase':
            return

        # Award referrer
        self.env['loyalty.transaction'].create({
            'member_id': self.referrer_id.id,
            'transaction_type': 'referral',
            'points': self.referrer_points,
            'description': f'Referral bonus for {self.referee_id.partner_id.name}',
            'state': 'confirmed',
        })

        # Award referee
        self.env['loyalty.transaction'].create({
            'member_id': self.referee_id.id,
            'transaction_type': 'referral',
            'points': self.referee_points,
            'description': 'Welcome bonus for joining via referral',
            'state': 'confirmed',
        })

        self.write({'state': 'rewarded', 'reward_date': fields.Datetime.now()})
```

### Day 236-237 — Birthday Rewards and Points Expiration

```python
# Celery task for birthday rewards
@celery_app.task
def process_birthday_rewards():
    """Send birthday rewards to members with birthdays today"""
    today = date.today()

    members = LoyaltyMember.objects.filter(
        partner_id__birthday__month=today.month,
        partner_id__birthday__day=today.day
    )

    for member in members:
        # Check if already rewarded this year
        existing = LoyaltyTransaction.objects.filter(
            member_id=member.id,
            transaction_type='birthday',
            create_date__year=today.year
        ).exists()

        if not existing:
            birthday_points = 100 * (member.tier_id.earning_multiplier or 1)
            LoyaltyTransaction.objects.create(
                member_id=member.id,
                transaction_type='birthday',
                points=int(birthday_points),
                description='Happy Birthday from Smart Dairy!',
                state='confirmed',
                expiry_date=today + timedelta(days=30)
            )
            # Send birthday email
            send_birthday_email.delay(member.id)

@celery_app.task
def expire_points():
    """Expire points older than 12 months"""
    expiry_date = date.today()

    transactions = LoyaltyTransaction.objects.filter(
        expiry_date__lte=expiry_date,
        state='confirmed',
        points__gt=0
    )

    for transaction in transactions:
        # Create expiration transaction
        LoyaltyTransaction.objects.create(
            member_id=transaction.member_id,
            transaction_type='expire',
            points=-transaction.points,
            description=f'Points expired from {transaction.reference}',
            state='confirmed'
        )
        transaction.state = 'expired'
        transaction.save()
```

### Day 237-239 — Mobile Loyalty Card and Dashboards

```dart
// Flutter: Loyalty Card Widget
class LoyaltyCardWidget extends StatelessWidget {
  final LoyaltyMember member;

  const LoyaltyCardWidget({required this.member});

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: EdgeInsets.all(16),
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: [
            Color(int.parse(member.tier.color.replaceFirst('#', '0xFF'))),
            Color(int.parse(member.tier.color.replaceFirst('#', '0xFF'))).withOpacity(0.7),
          ],
        ),
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(color: Colors.black26, blurRadius: 10, offset: Offset(0, 4)),
        ],
      ),
      child: Padding(
        padding: EdgeInsets.all(24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text('Smart Dairy', style: TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold)),
                Image.network(member.tier.badgeImage, height: 40),
              ],
            ),
            SizedBox(height: 20),
            Text(member.partnerName, style: TextStyle(color: Colors.white, fontSize: 18)),
            Text(member.memberCode, style: TextStyle(color: Colors.white70, fontSize: 14)),
            SizedBox(height: 20),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text('Available Points', style: TextStyle(color: Colors.white70, fontSize: 12)),
                    Text('${member.availablePoints}', style: TextStyle(color: Colors.white, fontSize: 28, fontWeight: FontWeight.bold)),
                  ],
                ),
                QrImage(data: member.memberCode, size: 80, backgroundColor: Colors.white),
              ],
            ),
            SizedBox(height: 16),
            LinearProgressIndicator(
              value: member.progressToNextTier,
              backgroundColor: Colors.white30,
              valueColor: AlwaysStoppedAnimation(Colors.white),
            ),
            SizedBox(height: 8),
            Text('${member.pointsToNextTier} points to ${member.nextTierName}',
                 style: TextStyle(color: Colors.white70, fontSize: 12)),
          ],
        ),
      ),
    );
  }
}
```

---

## 4. Technical Specifications

### 4.1 Data Models

| Model | Description | Key Fields |
|-------|-------------|------------|
| `loyalty.tier` | Tier configuration | min_points, multiplier, benefits |
| `loyalty.member` | Member profile | points, tier, referral_code |
| `loyalty.transaction` | Points history | type, points, expiry_date |
| `loyalty.reward` | Rewards catalog | points_required, reward_type |
| `loyalty.referral` | Referral tracking | referrer, referee, state |

### 4.2 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/loyalty/member` | GET | Get member profile |
| `/api/v1/loyalty/transactions` | GET | Get points history |
| `/api/v1/loyalty/rewards` | GET | List available rewards |
| `/api/v1/loyalty/redeem` | POST | Redeem points for reward |
| `/api/v1/loyalty/referral/apply` | POST | Apply referral code |

---

## 5. Testing & Validation

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| TC-24-001 | Points earning calculation | Correct multiplier applied |
| TC-24-002 | Tier upgrade | Member promoted when threshold reached |
| TC-24-003 | Points redemption | Points deducted, discount applied |
| TC-24-004 | Referral reward | Both parties receive points |
| TC-24-005 | Birthday reward | Points awarded on birthday |
| TC-24-006 | Points expiration | Expired points removed |

---

## 6. Dependencies & Handoffs

### Prerequisites
- Customer accounts (Phase 2)
- E-commerce checkout (MS-21)
- Mobile app (MS-22)

### Outputs
| Output | Consumer | Description |
|--------|----------|-------------|
| Points system | MS-25 (Checkout) | Redemption at checkout |
| Member data | MS-27 (Analytics) | Loyalty analytics |

---

**Document End**

*Milestone 24: Loyalty & Rewards Program*
*Smart Dairy Digital Smart Portal + ERP System*
*Phase 3 — E-Commerce, Mobile & Analytics*
*Version 1.0 | February 2026*
