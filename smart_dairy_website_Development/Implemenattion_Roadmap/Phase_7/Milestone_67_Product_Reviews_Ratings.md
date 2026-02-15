# Milestone 67: Product Reviews & Ratings

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P7-M67-v1.0 |
| **Milestone** | 67 - Product Reviews & Ratings |
| **Phase** | Phase 7: B2C E-Commerce Core |
| **Days** | 411-420 |
| **Duration** | 10 Working Days |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Technical Architecture Team |
| **Last Updated** | 2025-01-15 |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Implement a comprehensive product review and rating system with verified purchase validation, photo/video uploads, moderation queue, helpful voting, and seller responses to build social proof and enhance customer trust.**

### 1.2 Scope Summary

| Area | Included |
|------|----------|
| Star Ratings | 1-5 stars with average calculation |
| Written Reviews | Text reviews with guidelines |
| Verified Purchase | Badge for confirmed buyers |
| Media Uploads | Photo and video attachments |
| Moderation Queue | Admin approval workflow |
| Helpful Voting | Upvote/downvote system |
| Seller Response | Business reply capability |
| Review Analytics | Sentiment analysis |

---

## 2. Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| O1 | Verified purchase reviews | 100% verification accuracy |
| O2 | Review moderation queue | < 24h moderation time |
| O3 | Photo/video uploads | Support 5MB images, 50MB video |
| O4 | Helpful voting system | Vote tracking 100% accurate |
| O5 | Seller responses | Response capability within 48h |
| O6 | Review aggregation | Real-time average calculation |
| O7 | Review display performance | Load time < 1s |

---

## 3. Key Deliverables

| # | Deliverable | Owner | Due Day |
|---|-------------|-------|---------|
| D1 | Product review model | Dev 1 | Day 413 |
| D2 | Review API endpoints | Dev 1 | Day 414 |
| D3 | Review aggregation service | Dev 1 | Day 415 |
| D4 | Moderation workflow | Dev 2 | Day 415 |
| D5 | Media upload service | Dev 2 | Day 416 |
| D6 | Review widget UI | Dev 3 | Day 415 |
| D7 | Review submission form | Dev 3 | Day 416 |
| D8 | Moderation dashboard | Dev 3 | Day 418 |
| D9 | Review analytics | Dev 1 | Day 419 |
| D10 | Integration tests | All | Day 420 |

---

## 4. Requirement Traceability

### 4.1 RFP Requirements

| RFP Req ID | Description | Deliverable |
|------------|-------------|-------------|
| RFP-B2C-4.5.1-001 | Product reviews | D1, D6 |
| RFP-B2C-4.5.1-002 | Star rating system | D1, D6 |
| RFP-B2C-4.5.1-003 | Verified purchase badge | D1 |
| RFP-B2C-4.5.2-001 | Review moderation | D4, D8 |
| RFP-B2C-4.5.2-002 | Media uploads | D5 |
| RFP-B2C-4.5.3-001 | Seller responses | D2 |

---

## 5. Day-by-Day Development Plan

### Day 411-413: Review Model & Core API

#### Dev 1 (Backend Lead)

**Product Review Model**

```python
# File: smart_dairy_ecommerce/models/product_review.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError

class ProductReview(models.Model):
    _name = 'product.review'
    _description = 'Product Review'
    _order = 'create_date desc'

    product_id = fields.Many2one(
        'product.product',
        string='Product',
        required=True,
        ondelete='cascade',
        index=True
    )
    partner_id = fields.Many2one(
        'res.partner',
        string='Reviewer',
        required=True
    )
    order_id = fields.Many2one(
        'sale.order',
        string='Purchase Order'
    )

    # Rating & Content
    rating = fields.Integer(
        string='Rating',
        required=True
    )
    title = fields.Char(
        string='Review Title',
        size=100
    )
    content = fields.Text(
        string='Review Content'
    )

    # Verification
    is_verified_purchase = fields.Boolean(
        string='Verified Purchase',
        compute='_compute_verified',
        store=True
    )

    # Media
    image_ids = fields.Many2many(
        'ir.attachment',
        'review_image_rel',
        string='Images'
    )
    video_url = fields.Char(string='Video URL')

    # Moderation
    state = fields.Selection([
        ('pending', 'Pending Review'),
        ('approved', 'Approved'),
        ('rejected', 'Rejected'),
    ], string='Status', default='pending', index=True)
    moderated_by_id = fields.Many2one('res.users')
    moderated_at = fields.Datetime()
    rejection_reason = fields.Text()

    # Voting
    helpful_count = fields.Integer(default=0)
    not_helpful_count = fields.Integer(default=0)

    # Seller Response
    seller_response = fields.Text()
    seller_response_date = fields.Datetime()

    @api.depends('partner_id', 'product_id')
    def _compute_verified(self):
        for review in self:
            review.is_verified_purchase = bool(
                self.env['sale.order.line'].search([
                    ('order_id.partner_id', '=', review.partner_id.id),
                    ('product_id', '=', review.product_id.id),
                    ('order_id.state', 'in', ['sale', 'done'])
                ], limit=1)
            )

    @api.constrains('rating')
    def _check_rating(self):
        for review in self:
            if review.rating < 1 or review.rating > 5:
                raise ValidationError('Rating must be between 1 and 5')

    def action_approve(self):
        self.write({
            'state': 'approved',
            'moderated_by_id': self.env.user.id,
            'moderated_at': fields.Datetime.now(),
        })


class ProductProduct(models.Model):
    _inherit = 'product.product'

    review_ids = fields.One2many(
        'product.review',
        'product_id',
        string='Reviews'
    )
    review_count = fields.Integer(
        compute='_compute_review_stats',
        store=True
    )
    average_rating = fields.Float(
        compute='_compute_review_stats',
        store=True,
        digits=(2, 1)
    )

    @api.depends('review_ids.rating', 'review_ids.state')
    def _compute_review_stats(self):
        for product in self:
            approved = product.review_ids.filtered(
                lambda r: r.state == 'approved'
            )
            product.review_count = len(approved)
            product.average_rating = (
                sum(approved.mapped('rating')) / len(approved)
                if approved else 0
            )
```

#### Dev 2 (Full-Stack)

**Moderation Service**

```python
# File: smart_dairy_ecommerce/services/review_moderation.py

from odoo import models, api
import re

class ReviewModerationService(models.AbstractModel):
    _name = 'review.moderation.service'
    _description = 'Review Moderation Service'

    @api.model
    def auto_moderate(self, review):
        blocked_words = self._get_blocked_words()
        content = f"{review.title or ''} {review.content or ''}"

        for word in blocked_words:
            if re.search(rf'\b{re.escape(word)}\b', content, re.I):
                return {'action': 'flag', 'reason': f'Blocked word: {word}'}

        # Auto-approve verified 4-5 star reviews without media
        if review.is_verified_purchase and review.rating >= 4:
            if not review.image_ids and not review.video_url:
                return {'action': 'approve'}

        return {'action': 'pending'}

    def _get_blocked_words(self):
        config = self.env['ir.config_parameter'].sudo()
        words = config.get_param('review.blocked_words', '')
        return [w.strip() for w in words.split(',') if w.strip()]
```

#### Dev 3 (Frontend Lead)

**Review Widget Component**

```javascript
/** @odoo-module **/
// File: smart_dairy_ecommerce/static/src/js/reviews/review_widget.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class ProductReviewWidget extends Component {
    static template = "smart_dairy_ecommerce.ProductReviewWidget";
    static props = {
        productId: { type: Number, required: true },
    };

    setup() {
        this.rpc = useService("rpc");
        this.state = useState({
            reviews: [],
            stats: { count: 0, average: 0, distribution: {} },
            loading: true,
            page: 1,
            filter: 'all',
            showForm: false,
        });

        onWillStart(() => this.loadReviews());
    }

    async loadReviews() {
        this.state.loading = true;
        const result = await this.rpc(
            `/api/v1/products/${this.props.productId}/reviews`,
            { page: this.state.page, filter: this.state.filter }
        );
        if (result.success) {
            this.state.reviews = result.data.reviews;
            this.state.stats = result.data.stats;
        }
        this.state.loading = false;
    }

    async submitReview(data) {
        const result = await this.rpc(
            `/api/v1/products/${this.props.productId}/reviews`,
            data
        );
        if (result.success) {
            this.state.showForm = false;
            await this.loadReviews();
        }
    }

    async voteHelpful(reviewId, helpful) {
        await this.rpc(`/api/v1/reviews/${reviewId}/vote`, { helpful });
        await this.loadReviews();
    }

    renderStars(rating) {
        return Array(5).fill(0).map((_, i) => i < rating ? 'fa-star' : 'fa-star-o');
    }
}
```

---

### Day 414-420: APIs, Moderation UI & Testing

The remaining days cover:
- Review submission and voting APIs
- Moderation dashboard for admins
- Media upload handling
- Review analytics and reporting
- Integration and E2E testing

---

## 6. Technical Specifications

### 6.1 API Endpoints

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/v1/products/<id>/reviews` | GET | Public | List reviews |
| `/api/v1/products/<id>/reviews` | POST | User | Submit review |
| `/api/v1/reviews/<id>/vote` | POST | User | Vote helpful |
| `/api/v1/reviews/<id>/report` | POST | User | Report review |
| `/api/v1/reviews/<id>/respond` | POST | Seller | Add response |
| `/api/v1/admin/reviews/pending` | GET | Admin | Moderation queue |
| `/api/v1/admin/reviews/<id>/approve` | POST | Admin | Approve review |

### 6.2 Media Specifications

| Type | Format | Max Size | Max Count |
|------|--------|----------|-----------|
| Image | JPEG, PNG, WebP | 5MB | 5 |
| Video | MP4, WebM | 50MB | 1 |

---

## 7. Testing Requirements

### 7.1 Unit Test Coverage

| Area | Target |
|------|--------|
| Review CRUD | > 90% |
| Rating calculation | > 95% |
| Moderation workflow | > 85% |
| Voting system | > 90% |

---

## 8. Sign-off Checklist

- [ ] Star rating (1-5) works correctly
- [ ] Verified purchase badge displays
- [ ] Photo upload functional
- [ ] Video upload functional
- [ ] Moderation queue operational
- [ ] Auto-moderation rules work
- [ ] Helpful voting updates counts
- [ ] Seller can respond to reviews
- [ ] Rating average calculates correctly
- [ ] Review distribution shows accurately
- [ ] Mobile responsive design

---

**Document End**

*Last Updated: 2025-01-15*
*Version: 1.0*
