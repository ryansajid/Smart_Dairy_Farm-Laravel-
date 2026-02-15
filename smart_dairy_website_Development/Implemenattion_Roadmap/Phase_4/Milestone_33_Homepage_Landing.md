# Milestone 33: Homepage & Landing Pages

## Smart Dairy Digital Smart Portal + ERP — Phase 4: Foundation - Public Website & CMS

| Field                | Detail                                                           |
| -------------------- | ---------------------------------------------------------------- |
| **Milestone**        | 33 of 40 (3 of 10 in Phase 4)                                    |
| **Title**            | Homepage & Landing Pages                                         |
| **Phase**            | Phase 4 — Foundation (Public Website & CMS)                      |
| **Days**             | Days 171–180 (of 250)                                            |
| **Version**          | 1.0                                                              |
| **Status**           | Draft                                                            |
| **Authors**          | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend Lead) |
| **Last Updated**     | 2026-02-03                                                       |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Requirement Traceability Matrix](#2-requirement-traceability-matrix)
3. [Day-by-Day Breakdown](#3-day-by-day-breakdown)
4. [Technical Specifications](#4-technical-specifications)
5. [Testing & Validation](#5-testing--validation)
6. [Risk & Mitigation](#6-risk--mitigation)
7. [Dependencies & Handoffs](#7-dependencies--handoffs)

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Develop a compelling, high-converting homepage and reusable landing page templates that effectively communicate Smart Dairy's brand story, showcase products, and drive user engagement. By Day 180, the homepage will be fully responsive with animated hero sections, product carousels, testimonials, newsletter signup, and optimized for <2 second load time.

### 1.2 Objectives

1. Design and implement hero section with animated banners and CTAs
2. Create features/benefits showcase section highlighting key differentiators
3. Build product highlights carousel with dynamic data integration
4. Implement customer testimonials slider with star ratings
5. Integrate newsletter subscription with email validation
6. Create reusable CTA components with A/B testing capability
7. Develop 4+ landing page templates for marketing campaigns
8. Optimize homepage for mobile-first experience
9. Implement smooth scroll animations and micro-interactions
10. Conduct Milestone 33 review, demo, and retrospective

### 1.3 Key Deliverables

| # | Deliverable                           | Owner  | Format                    |
|---|---------------------------------------|--------|---------------------------|
| 1 | Hero section with animations          | Dev 3  | QWeb + CSS + JS           |
| 2 | Features/benefits grid section        | Dev 3  | QWeb + CSS                |
| 3 | Product carousel component            | Dev 2  | OWL + API                 |
| 4 | Testimonials slider                   | Dev 3  | JS + CSS                  |
| 5 | Newsletter subscription form          | Dev 1  | Python + JS               |
| 6 | CTA button variants                   | Dev 3  | CSS components            |
| 7 | Landing page template: Product Launch | Dev 3  | QWeb template             |
| 8 | Landing page template: Campaign       | Dev 2  | QWeb template             |
| 9 | Landing page template: Lead Capture   | Dev 1  | QWeb + form               |
| 10| Landing page template: Event          | Dev 2  | QWeb template             |
| 11| Mobile homepage optimization          | Dev 3  | Responsive CSS            |
| 12| Milestone 33 review report            | All    | Markdown document         |

### 1.4 Prerequisites

- Milestone 32 completed — CMS, templates, snippets operational
- Design system and component library available
- Product data imported or placeholder data ready
- Brand assets (images, icons) from marketing team

### 1.5 Success Criteria

- [ ] Homepage fully responsive across all breakpoints (320px - 2560px)
- [ ] Hero section with smooth CSS/JS animations
- [ ] Product carousel displaying real or placeholder products
- [ ] Newsletter form capturing emails with validation
- [ ] Page load time <2 seconds on 4G connection
- [ ] Lighthouse Performance score >85
- [ ] 4+ landing page templates available for marketing use

---

## 2. Requirement Traceability Matrix

| Req ID        | Source     | Requirement Description                           | Day(s)  | Task Reference              |
| ------------- | ---------- | ------------------------------------------------- | ------- | --------------------------- |
| RFP-WEB-001   | RFP §2     | Professional corporate homepage                   | 171-175 | Hero, features, products    |
| RFP-WEB-003   | RFP §3     | Page load time <2 seconds                         | 178-179 | Performance optimization    |
| RFP-WEB-004   | RFP §3     | >3 minute average session time                    | 171-177 | Engaging content, animations|
| RFP-WEB-006   | RFP §4     | >60% mobile traffic support                       | 178     | Mobile optimization         |
| BRD-BRAND-001 | BRD §2     | Strong brand presence                             | 171-172 | Hero, brand messaging       |
| BRD-CUST-001  | BRD §4     | Customer engagement features                      | 174-176 | Testimonials, newsletter    |
| SRS-WEB-003   | SRS §4.3   | Responsive design                                 | 171-180 | All sections                |
| SRS-PERF-001  | SRS §5.1   | Lighthouse score >90                              | 178-179 | Performance optimization    |
| NFR-PERF-01   | BRD §3     | Fast page load performance                        | 178-179 | CDN, lazy load, optimization|

---

## 3. Day-by-Day Breakdown

---

### Day 171 — Hero Section Development

**Objective:** Create an impactful hero section with animated content, background media, and prominent CTAs.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create hero content management model — 3h
2. Implement hero API for dynamic content — 2.5h
3. Build hero slide management backend — 2.5h

```python
# Hero Section Content Management
from odoo import models, fields, api

class WebsiteHeroSlide(models.Model):
    _name = 'website.hero.slide'
    _description = 'Website Hero Slide'
    _order = 'sequence, id'

    name = fields.Char('Slide Name', required=True)
    sequence = fields.Integer('Sequence', default=10)
    active = fields.Boolean('Active', default=True)

    # Content
    headline = fields.Char('Headline', required=True, translate=True)
    subheadline = fields.Text('Subheadline', translate=True)
    badge_text = fields.Char('Badge Text', translate=True)

    # Media
    background_type = fields.Selection([
        ('image', 'Image'),
        ('video', 'Video'),
        ('gradient', 'Gradient'),
    ], string='Background Type', default='image')
    background_image = fields.Binary('Background Image', attachment=True)
    background_video_url = fields.Char('Video URL')
    gradient_start = fields.Char('Gradient Start Color', default='#0066CC')
    gradient_end = fields.Char('Gradient End Color', default='#2ECC71')

    # Foreground image
    foreground_image = fields.Binary('Foreground Image', attachment=True)
    foreground_position = fields.Selection([
        ('left', 'Left'),
        ('right', 'Right'),
        ('center', 'Center'),
    ], default='right')

    # CTAs
    cta_primary_text = fields.Char('Primary CTA Text', translate=True)
    cta_primary_url = fields.Char('Primary CTA URL')
    cta_primary_style = fields.Selection([
        ('solid', 'Solid'),
        ('outline', 'Outline'),
    ], default='solid')
    cta_secondary_text = fields.Char('Secondary CTA Text', translate=True)
    cta_secondary_url = fields.Char('Secondary CTA URL')

    # Styling
    text_alignment = fields.Selection([
        ('left', 'Left'),
        ('center', 'Center'),
        ('right', 'Right'),
    ], default='left')
    text_color = fields.Selection([
        ('light', 'Light (White)'),
        ('dark', 'Dark'),
    ], default='light')
    overlay_opacity = fields.Float('Overlay Opacity', default=0.3)

    # Scheduling
    start_date = fields.Datetime('Start Date')
    end_date = fields.Datetime('End Date')

    @api.model
    def get_active_slides(self):
        """Get currently active hero slides"""
        now = fields.Datetime.now()
        domain = [
            ('active', '=', True),
            '|', ('start_date', '=', False), ('start_date', '<=', now),
            '|', ('end_date', '=', False), ('end_date', '>=', now),
        ]
        slides = self.search(domain, order='sequence')
        return [{
            'id': s.id,
            'headline': s.headline,
            'subheadline': s.subheadline,
            'badge_text': s.badge_text,
            'background_type': s.background_type,
            'background_image': f'/web/image/website.hero.slide/{s.id}/background_image' if s.background_image else None,
            'foreground_image': f'/web/image/website.hero.slide/{s.id}/foreground_image' if s.foreground_image else None,
            'foreground_position': s.foreground_position,
            'cta_primary': {'text': s.cta_primary_text, 'url': s.cta_primary_url} if s.cta_primary_text else None,
            'cta_secondary': {'text': s.cta_secondary_text, 'url': s.cta_secondary_url} if s.cta_secondary_text else None,
            'text_alignment': s.text_alignment,
            'text_color': s.text_color,
        } for s in slides]
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Implement hero image preloading strategy — 2.5h
2. Create hero video background handler — 2.5h
3. Set up hero analytics tracking — 1.5h
4. Configure hero image CDN caching — 1.5h

```javascript
// Hero Section Controller
class SmartDairyHero {
    constructor(element) {
        this.hero = element;
        this.slides = this.hero.querySelectorAll('.hero-slide');
        this.indicators = this.hero.querySelectorAll('.hero-indicator');
        this.currentSlide = 0;
        this.autoplayInterval = null;
        this.autoplayDelay = 6000;
        this.isAnimating = false;

        this.init();
    }

    init() {
        if (this.slides.length <= 1) return;

        this.preloadImages();
        this.bindEvents();
        this.startAutoplay();
        this.initVideoBackgrounds();
    }

    preloadImages() {
        this.slides.forEach((slide, index) => {
            if (index === 0) return;
            const bgImage = slide.dataset.bgImage;
            if (bgImage) {
                const img = new Image();
                img.src = bgImage;
            }
        });
    }

    initVideoBackgrounds() {
        this.slides.forEach(slide => {
            const video = slide.querySelector('video');
            if (video) {
                video.muted = true;
                video.playsInline = true;
                if (slide.classList.contains('active')) {
                    video.play().catch(() => {});
                }
            }
        });
    }

    bindEvents() {
        // Indicator clicks
        this.indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => this.goToSlide(index));
        });

        // Navigation arrows
        const prevBtn = this.hero.querySelector('.hero-prev');
        const nextBtn = this.hero.querySelector('.hero-next');
        if (prevBtn) prevBtn.addEventListener('click', () => this.prevSlide());
        if (nextBtn) nextBtn.addEventListener('click', () => this.nextSlide());

        // Pause on hover
        this.hero.addEventListener('mouseenter', () => this.pauseAutoplay());
        this.hero.addEventListener('mouseleave', () => this.startAutoplay());

        // Touch swipe
        this.initTouchSwipe();

        // Keyboard navigation
        this.hero.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') this.prevSlide();
            if (e.key === 'ArrowRight') this.nextSlide();
        });
    }

    initTouchSwipe() {
        let touchStartX = 0;
        let touchEndX = 0;

        this.hero.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        this.hero.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) this.nextSlide();
                else this.prevSlide();
            }
        }, { passive: true });
    }

    goToSlide(index) {
        if (this.isAnimating || index === this.currentSlide) return;
        this.isAnimating = true;

        const currentSlide = this.slides[this.currentSlide];
        const nextSlide = this.slides[index];

        // Pause current video
        const currentVideo = currentSlide.querySelector('video');
        if (currentVideo) currentVideo.pause();

        // Animate out current
        currentSlide.classList.remove('active');
        currentSlide.classList.add('exiting');

        // Animate in next
        nextSlide.classList.add('active', 'entering');

        // Play next video
        const nextVideo = nextSlide.querySelector('video');
        if (nextVideo) nextVideo.play().catch(() => {});

        // Update indicators
        this.indicators[this.currentSlide].classList.remove('active');
        this.indicators[index].classList.add('active');

        this.currentSlide = index;

        // Track slide view
        this.trackSlideView(index);

        setTimeout(() => {
            currentSlide.classList.remove('exiting');
            nextSlide.classList.remove('entering');
            this.isAnimating = false;
        }, 600);
    }

    nextSlide() {
        const next = (this.currentSlide + 1) % this.slides.length;
        this.goToSlide(next);
    }

    prevSlide() {
        const prev = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.goToSlide(prev);
    }

    startAutoplay() {
        if (this.autoplayInterval) return;
        this.autoplayInterval = setInterval(() => this.nextSlide(), this.autoplayDelay);
    }

    pauseAutoplay() {
        if (this.autoplayInterval) {
            clearInterval(this.autoplayInterval);
            this.autoplayInterval = null;
        }
    }

    trackSlideView(index) {
        if (typeof gtag !== 'undefined') {
            const slide = this.slides[index];
            gtag('event', 'hero_slide_view', {
                'event_category': 'homepage',
                'event_label': slide.dataset.slideId || `slide_${index}`,
                'slide_index': index
            });
        }
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    const heroElements = document.querySelectorAll('.sd-hero');
    heroElements.forEach(hero => new SmartDairyHero(hero));
});
```

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design hero section HTML structure — 2h
2. Create hero CSS animations — 3h
3. Implement hero responsive layouts — 2h
4. Build hero indicator and navigation styles — 1h

```scss
// _hero.scss - Hero Section Styles

.sd-hero {
    position: relative;
    min-height: 85vh;
    overflow: hidden;
    background-color: var(--color-bg-dark);

    @include respond-to('md') {
        min-height: 90vh;
    }
}

.hero-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.6s ease, visibility 0.6s ease;

    &.active {
        opacity: 1;
        visibility: visible;
        z-index: 1;

        .hero-content {
            animation: heroContentIn 0.8s ease 0.2s forwards;
        }

        .hero-image {
            animation: heroImageIn 1s ease 0.3s forwards;
        }
    }

    &.exiting {
        opacity: 0;
        z-index: 0;
    }
}

.hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;

    img, video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        135deg,
        rgba(0, 0, 0, 0.7) 0%,
        rgba(0, 0, 0, 0.3) 100%
    );
}

.hero-container {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    min-height: 85vh;
    padding: var(--spacing-16) var(--spacing-4);

    @include respond-to('md') {
        min-height: 90vh;
        padding: var(--spacing-20) var(--spacing-8);
    }
}

.hero-content {
    max-width: 600px;
    opacity: 0;
    transform: translateY(30px);

    &.text-light {
        color: white;
    }

    &.text-dark {
        color: var(--color-text-primary);
    }
}

.hero-badge {
    display: inline-block;
    padding: var(--spacing-2) var(--spacing-4);
    background: rgba(255, 255, 255, 0.15);
    border-radius: var(--radius-full);
    font-size: var(--font-size-sm);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: var(--spacing-4);
    backdrop-filter: blur(10px);
}

.hero-headline {
    font-family: var(--font-family-headings);
    font-size: var(--font-size-4xl);
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: var(--spacing-4);

    @include respond-to('md') {
        font-size: var(--font-size-5xl);
    }

    @include respond-to('lg') {
        font-size: var(--font-size-6xl);
    }
}

.hero-subheadline {
    font-size: var(--font-size-lg);
    line-height: var(--line-height-relaxed);
    opacity: 0.9;
    margin-bottom: var(--spacing-8);

    @include respond-to('md') {
        font-size: var(--font-size-xl);
    }
}

.hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-4);

    .btn {
        min-width: 160px;
    }

    .btn-primary {
        background: white;
        color: var(--color-primary);
        border-color: white;

        &:hover {
            background: var(--color-primary-light);
            color: white;
            border-color: var(--color-primary-light);
        }
    }

    .btn-outline {
        border-color: rgba(255, 255, 255, 0.5);
        color: white;

        &:hover {
            background: white;
            color: var(--color-primary);
        }
    }
}

.hero-image {
    position: absolute;
    right: 0;
    bottom: 0;
    width: 50%;
    max-width: 600px;
    opacity: 0;
    transform: translateX(50px);

    @media (max-width: 991px) {
        display: none;
    }

    img {
        width: 100%;
        height: auto;
    }
}

// Navigation
.hero-nav {
    position: absolute;
    bottom: var(--spacing-8);
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    display: flex;
    align-items: center;
    gap: var(--spacing-4);
}

.hero-indicators {
    display: flex;
    gap: var(--spacing-2);
}

.hero-indicator {
    width: 40px;
    height: 4px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: var(--radius-full);
    cursor: pointer;
    transition: all var(--transition-normal);

    &.active {
        background: white;
        width: 60px;
    }

    &:hover:not(.active) {
        background: rgba(255, 255, 255, 0.5);
    }
}

.hero-arrow {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    transition: all var(--transition-fast);
    backdrop-filter: blur(10px);

    &:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.1);
    }

    @media (max-width: 767px) {
        display: none;
    }
}

// Animations
@keyframes heroContentIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes heroImageIn {
    from {
        opacity: 0;
        transform: translateX(50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

// Scroll indicator
.hero-scroll-indicator {
    position: absolute;
    bottom: var(--spacing-6);
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    animation: scrollBounce 2s infinite;

    @media (max-width: 767px) {
        display: none;
    }
}

@keyframes scrollBounce {
    0%, 20%, 50%, 80%, 100% { transform: translateX(-50%) translateY(0); }
    40% { transform: translateX(-50%) translateY(-10px); }
    60% { transform: translateX(-50%) translateY(-5px); }
}
```

**End-of-Day 171 Deliverables:**

- [ ] Hero section HTML/CSS structure complete
- [ ] Hero slide management backend operational
- [ ] Hero animations (fade, slide) working
- [ ] Hero responsive across breakpoints
- [ ] Hero video background support

---

### Day 172 — Features/Benefits Section

**Objective:** Create an engaging features/benefits showcase highlighting Smart Dairy's key differentiators.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create features content management model — 2.5h
2. Build features API endpoint — 2h
3. Implement icon management for features — 2h
4. Create feature analytics tracking — 1.5h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Implement animated counters for statistics — 3h
2. Create scroll-triggered animations — 3h
3. Build feature comparison component — 2h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design features grid layout — 3h
2. Create feature card hover effects — 2.5h
3. Implement icon animations — 2.5h

**End-of-Day 172 Deliverables:**

- [ ] Features grid section complete
- [ ] Feature cards with icons and descriptions
- [ ] Hover animations on feature cards
- [ ] Statistics counter with scroll trigger
- [ ] Responsive features layout

---

### Day 173 — Product Highlights Carousel

**Objective:** Build a dynamic product carousel showcasing featured products with smooth animations.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create featured products query logic — 2.5h
2. Build product carousel API — 2.5h
3. Implement product quick view data — 3h

```python
# Featured Products API
from odoo import http
from odoo.http import request

class FeaturedProductsController(http.Controller):

    @http.route('/api/homepage/featured-products', type='json', auth='public', website=True)
    def get_featured_products(self, limit=8, category_id=None):
        """Get featured products for homepage carousel"""
        domain = [
            ('website_published', '=', True),
            ('is_featured', '=', True),
        ]
        if category_id:
            domain.append(('public_categ_ids', 'in', [int(category_id)]))

        products = request.env['product.template'].sudo().search(
            domain, limit=limit, order='sequence, name'
        )

        return {
            'products': [{
                'id': p.id,
                'name': p.name,
                'description': p.description_short or '',
                'price': p.list_price,
                'compare_price': p.compare_list_price if hasattr(p, 'compare_list_price') else None,
                'currency': p.currency_id.symbol,
                'image_url': f'/web/image/product.template/{p.id}/image_512',
                'url': f'/product/{p.id}',
                'category': p.public_categ_ids[0].name if p.public_categ_ids else '',
                'badge': p.website_ribbon_id.html if p.website_ribbon_id else None,
                'rating': p.rating_avg if hasattr(p, 'rating_avg') else None,
                'review_count': p.rating_count if hasattr(p, 'rating_count') else 0,
            } for p in products],
            'total': len(products),
        }

    @http.route('/api/product/<int:product_id>/quick-view', type='json', auth='public', website=True)
    def get_product_quick_view(self, product_id):
        """Get product data for quick view modal"""
        product = request.env['product.template'].sudo().browse(product_id)
        if not product.exists() or not product.website_published:
            return {'error': 'Product not found'}

        return {
            'id': product.id,
            'name': product.name,
            'description': product.description_sale or product.description or '',
            'price': product.list_price,
            'currency': product.currency_id.symbol,
            'images': [
                f'/web/image/product.template/{product.id}/image_1024'
            ] + [
                f'/web/image/product.image/{img.id}/image_1024'
                for img in product.product_template_image_ids[:4]
            ],
            'url': f'/product/{product.id}',
            'attributes': [{
                'name': attr.attribute_id.name,
                'values': [v.name for v in attr.value_ids]
            } for attr in product.attribute_line_ids],
            'nutritional_info': product.nutritional_info if hasattr(product, 'nutritional_info') else None,
        }
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Build product carousel JavaScript component — 3h
2. Implement carousel touch/swipe support — 2.5h
3. Create product quick view modal — 2.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design product carousel layout — 2.5h
2. Style product cards for carousel — 3h
3. Create carousel navigation styles — 2.5h

**End-of-Day 173 Deliverables:**

- [ ] Product carousel with smooth sliding
- [ ] Touch/swipe support on mobile
- [ ] Product quick view modal functional
- [ ] Carousel navigation (arrows + dots)
- [ ] Carousel responsive across devices

---

### Day 174 — Testimonials Section

**Objective:** Create a compelling testimonials slider with customer reviews and star ratings.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create testimonials model — 2.5h
2. Build testimonials management backend — 3h
3. Implement testimonial moderation workflow — 2.5h

```python
# Testimonials Model
from odoo import models, fields, api

class WebsiteTestimonial(models.Model):
    _name = 'website.testimonial'
    _description = 'Customer Testimonial'
    _order = 'sequence, create_date desc'

    name = fields.Char('Customer Name', required=True)
    title = fields.Char('Title/Designation')
    company = fields.Char('Company')
    location = fields.Char('Location')
    photo = fields.Binary('Photo', attachment=True)

    testimonial_text = fields.Text('Testimonial', required=True, translate=True)
    rating = fields.Selection([
        ('1', '1 Star'),
        ('2', '2 Stars'),
        ('3', '3 Stars'),
        ('4', '4 Stars'),
        ('5', '5 Stars'),
    ], string='Rating', default='5')

    product_id = fields.Many2one('product.template', string='Related Product')
    category = fields.Selection([
        ('product', 'Product Review'),
        ('service', 'Service Review'),
        ('general', 'General'),
    ], default='general')

    state = fields.Selection([
        ('pending', 'Pending Review'),
        ('approved', 'Approved'),
        ('rejected', 'Rejected'),
    ], default='pending', string='Status')

    is_featured = fields.Boolean('Featured', default=False)
    sequence = fields.Integer('Sequence', default=10)
    website_published = fields.Boolean('Published', default=False)

    # Video testimonial
    has_video = fields.Boolean('Has Video')
    video_url = fields.Char('Video URL')

    @api.model
    def get_homepage_testimonials(self, limit=6):
        """Get featured testimonials for homepage"""
        testimonials = self.search([
            ('website_published', '=', True),
            ('state', '=', 'approved'),
            ('is_featured', '=', True),
        ], limit=limit, order='sequence')

        return [{
            'id': t.id,
            'name': t.name,
            'title': t.title,
            'company': t.company,
            'photo': f'/web/image/website.testimonial/{t.id}/photo' if t.photo else '/smart_dairy_theme/static/src/img/avatar-placeholder.png',
            'text': t.testimonial_text,
            'rating': int(t.rating),
            'has_video': t.has_video,
            'video_url': t.video_url,
        } for t in testimonials]
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Build testimonials slider JavaScript — 3h
2. Implement autoplay with pause on hover — 2h
3. Create video testimonial player — 3h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design testimonials card layout — 2.5h
2. Create star rating component — 2h
3. Style testimonials slider — 2h
4. Implement testimonial animations — 1.5h

**End-of-Day 174 Deliverables:**

- [ ] Testimonials slider with smooth transitions
- [ ] Star rating display component
- [ ] Customer photo with fallback avatar
- [ ] Video testimonial support
- [ ] Testimonials responsive layout

---

### Day 175 — Newsletter Subscription

**Objective:** Implement newsletter subscription with email validation, double opt-in, and integration with email marketing.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create newsletter subscriber model — 2.5h
2. Implement double opt-in flow — 3h
3. Build email confirmation template — 2.5h

```python
# Newsletter Subscription
from odoo import models, fields, api
from odoo.exceptions import ValidationError
import hashlib
import secrets

class NewsletterSubscriber(models.Model):
    _name = 'newsletter.subscriber'
    _description = 'Newsletter Subscriber'
    _rec_name = 'email'

    email = fields.Char('Email', required=True, index=True)
    name = fields.Char('Name')

    state = fields.Selection([
        ('pending', 'Pending Confirmation'),
        ('confirmed', 'Confirmed'),
        ('unsubscribed', 'Unsubscribed'),
    ], default='pending', string='Status')

    confirmation_token = fields.Char('Confirmation Token')
    confirmed_date = fields.Datetime('Confirmed Date')
    unsubscribed_date = fields.Datetime('Unsubscribed Date')

    source = fields.Selection([
        ('homepage', 'Homepage'),
        ('footer', 'Footer'),
        ('popup', 'Popup'),
        ('checkout', 'Checkout'),
        ('blog', 'Blog'),
    ], string='Subscription Source')

    preferences = fields.Many2many('newsletter.category', string='Preferences')
    language = fields.Selection([
        ('en_US', 'English'),
        ('bn_BD', 'Bangla'),
    ], default='en_US')

    ip_address = fields.Char('IP Address')
    user_agent = fields.Char('User Agent')

    _sql_constraints = [
        ('email_unique', 'UNIQUE(email)', 'This email is already subscribed.')
    ]

    @api.model
    def subscribe(self, email, name=None, source='homepage', preferences=None):
        """Subscribe to newsletter with double opt-in"""
        # Validate email
        if not self._validate_email(email):
            raise ValidationError('Please enter a valid email address.')

        # Check existing
        existing = self.search([('email', '=', email.lower())], limit=1)
        if existing:
            if existing.state == 'confirmed':
                return {'status': 'already_subscribed'}
            elif existing.state == 'unsubscribed':
                # Re-subscribe
                existing.write({
                    'state': 'pending',
                    'confirmation_token': self._generate_token(),
                })
                existing._send_confirmation_email()
                return {'status': 'resubscribed'}
            else:
                # Resend confirmation
                existing._send_confirmation_email()
                return {'status': 'confirmation_resent'}

        # Create new subscriber
        token = self._generate_token()
        subscriber = self.create({
            'email': email.lower(),
            'name': name,
            'source': source,
            'confirmation_token': token,
            'preferences': [(6, 0, preferences or [])],
        })
        subscriber._send_confirmation_email()

        return {'status': 'success', 'message': 'Please check your email to confirm subscription.'}

    def confirm_subscription(self, token):
        """Confirm subscription via token"""
        subscriber = self.search([
            ('confirmation_token', '=', token),
            ('state', '=', 'pending'),
        ], limit=1)

        if not subscriber:
            return False

        subscriber.write({
            'state': 'confirmed',
            'confirmed_date': fields.Datetime.now(),
            'confirmation_token': False,
        })

        return True

    def unsubscribe(self, token):
        """Unsubscribe using token"""
        subscriber = self.search([
            ('confirmation_token', '=', token),
        ], limit=1)

        if subscriber:
            subscriber.write({
                'state': 'unsubscribed',
                'unsubscribed_date': fields.Datetime.now(),
            })
            return True
        return False

    def _generate_token(self):
        return secrets.token_urlsafe(32)

    def _validate_email(self, email):
        import re
        pattern = r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$'
        return bool(re.match(pattern, email))

    def _send_confirmation_email(self):
        """Send confirmation email"""
        self.ensure_one()
        template = self.env.ref('smart_dairy_newsletter.email_template_confirm')
        template.send_mail(self.id, force_send=True)
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create newsletter form JavaScript with validation — 3h
2. Build confirmation landing page — 2.5h
3. Implement email service integration — 2.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design newsletter section UI — 2.5h
2. Create inline form styling — 2.5h
3. Build success/error state animations — 2h
4. Implement popup newsletter form — 1h

**End-of-Day 175 Deliverables:**

- [ ] Newsletter subscription form functional
- [ ] Email validation (client + server)
- [ ] Double opt-in flow working
- [ ] Confirmation email sending
- [ ] Success/error messaging styled

---

### Day 176 — CTA Components & Conversion Elements

**Objective:** Create reusable call-to-action components optimized for conversion with A/B testing capability.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create CTA content management — 2.5h
2. Build A/B testing framework — 3.5h
3. Implement CTA analytics tracking — 2h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create CTA click tracking — 2.5h
2. Build conversion goal tracking — 3h
3. Implement CTA display rules — 2.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design CTA banner variations — 3h
2. Create floating CTA button — 2h
3. Build exit-intent popup — 3h

**End-of-Day 176 Deliverables:**

- [ ] Multiple CTA component variants
- [ ] CTA tracking integrated with GA4
- [ ] A/B testing framework foundation
- [ ] Exit-intent popup functional
- [ ] Floating CTA button implemented

---

### Day 177 — Landing Page Templates

**Objective:** Create 4 reusable landing page templates for different marketing purposes.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create Product Launch landing page template — 4h
2. Build Lead Capture landing page with form — 4h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create Campaign landing page template — 4h
2. Build Event landing page template — 4h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Style all 4 landing page templates — 4h
2. Create landing page sections (hero, features, CTA) — 4h

**End-of-Day 177 Deliverables:**

- [ ] Product Launch template complete
- [ ] Campaign template complete
- [ ] Lead Capture template complete
- [ ] Event template complete
- [ ] All templates responsive

---

### Day 178 — Mobile Homepage Optimization

**Objective:** Optimize the homepage specifically for mobile devices ensuring excellent performance and UX.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Implement mobile-specific API responses — 3h
2. Create mobile image variant serving — 2.5h
3. Optimize database queries for mobile — 2.5h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Implement critical CSS extraction — 3h
2. Set up service worker for offline support — 3h
3. Configure mobile-specific caching — 2h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Optimize touch interactions — 2.5h
2. Improve mobile navigation UX — 2.5h
3. Reduce mobile layout shifts — 3h

**End-of-Day 178 Deliverables:**

- [ ] Mobile page load <2s on 4G
- [ ] Touch-friendly interactions
- [ ] Mobile-optimized images serving
- [ ] Reduced CLS on mobile
- [ ] Service worker registered

---

### Day 179 — Homepage Animations & Interactions

**Objective:** Add polish with smooth animations, micro-interactions, and scroll-based effects.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Review and optimize all homepage APIs — 4h
2. Implement API response caching — 4h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create scroll-based reveal animations — 3h
2. Implement performance monitoring — 2.5h
3. Optimize JavaScript bundle — 2.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Add section entrance animations — 3h
2. Create button/card hover micro-interactions — 2.5h
3. Implement smooth scroll behavior — 2.5h

**End-of-Day 179 Deliverables:**

- [ ] Smooth scroll-reveal animations
- [ ] Micro-interactions on interactive elements
- [ ] Performance monitoring active
- [ ] JavaScript optimized and bundled

---

### Day 180 — Milestone 33 Review & Testing

**Objective:** Comprehensive testing, performance audit, and milestone review.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. API performance testing — 3h
2. Security review of forms — 2.5h
3. Code review and documentation — 2.5h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Lighthouse performance audit — 3h
2. Cross-browser testing — 2.5h
3. Load testing homepage — 2.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Responsive testing all breakpoints — 3h
2. Accessibility audit — 2.5h
3. Visual regression testing — 2.5h

#### All Developers — Milestone Review (4h combined)

1. Demo homepage to stakeholders — 1.5h
2. Review deliverables checklist — 1h
3. Retrospective — 0.5h
4. Plan Milestone 34 — 1h

**End-of-Day 180 Deliverables:**

- [ ] Lighthouse score >85
- [ ] Page load <2 seconds
- [ ] All browsers tested
- [ ] Accessibility compliant
- [ ] Milestone 33 sign-off received

---

## 4. Technical Specifications

### 4.1 Homepage Section Order

1. Hero Section (with slider)
2. Features/Benefits (Why Choose Us)
3. Product Highlights (Carousel)
4. Statistics Counter
5. About/Story Teaser
6. Testimonials Slider
7. Blog Posts Preview
8. Newsletter Subscription
9. CTA Banner

### 4.2 Performance Targets

| Metric | Target | Tool |
| ------ | ------ | ---- |
| LCP | <2.5s | Lighthouse |
| FID | <100ms | Lighthouse |
| CLS | <0.1 | Lighthouse |
| TTI | <3.5s | Lighthouse |
| Total Bundle Size | <300KB | Webpack |
| Image Sizes | Responsive | Custom |

### 4.3 Landing Page Templates

| Template | Purpose | Key Sections |
| -------- | ------- | ------------ |
| Product Launch | New product announcement | Hero, Features, Gallery, CTA |
| Campaign | Marketing campaigns | Hero, Benefits, Testimonial, Form |
| Lead Capture | B2B lead generation | Hero, Value Props, Form, Trust |
| Event | Webinars, events | Hero, Details, Speakers, Register |

---

## 5. Testing & Validation

### 5.1 Testing Checklist

- [ ] Hero slider navigation (arrows, dots, swipe)
- [ ] Hero autoplay and pause
- [ ] Product carousel functionality
- [ ] Newsletter form validation
- [ ] Newsletter double opt-in flow
- [ ] CTA tracking in Google Analytics
- [ ] All animations smooth (60fps)
- [ ] Mobile responsiveness (5 breakpoints)
- [ ] Cross-browser (Chrome, Firefox, Safari, Edge)
- [ ] Accessibility (keyboard navigation, screen readers)

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
| ---- | ----------- | ------ | ---------- |
| Content not ready | Medium | High | Use high-quality placeholders |
| Animation performance | Medium | Medium | Use CSS over JS, test early |
| Newsletter deliverability | Low | Medium | Use transactional email service |
| Mobile performance | Medium | High | Test continuously, optimize |

---

## 7. Dependencies & Handoffs

### 7.1 Incoming

| Dependency | Source | Status |
| ---------- | ------ | ------ |
| CMS, templates, snippets | MS-32 | Required |
| Product data | Business | Required |
| Testimonial content | Marketing | Required |
| Brand images | Marketing | Required |

### 7.2 Outgoing

| Deliverable | Recipient | Date |
| ----------- | --------- | ---- |
| Homepage | All stakeholders | Day 180 |
| Landing templates | Marketing | Day 180 |
| Newsletter system | Marketing | Day 180 |

---

**END OF MILESTONE 33**

**Next Milestone**: Milestone_34_Product_Catalog.md
