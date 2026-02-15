# Milestone 31: Website Foundation & Design System

## Smart Dairy Digital Smart Portal + ERP — Phase 4: Foundation - Public Website & CMS

| Field                | Detail                                                           |
| -------------------- | ---------------------------------------------------------------- |
| **Milestone**        | 31 of 40 (1 of 10 in Phase 4)                                    |
| **Title**            | Website Foundation & Design System                               |
| **Phase**            | Phase 4 — Foundation (Public Website & CMS)                      |
| **Days**             | Days 151–160 (of 250)                                            |
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

Establish the complete website technical foundation including Smart Dairy brand identity implementation, design system with reusable components, Odoo Website module configuration, CDN integration, and analytics setup. By Day 160, the website skeleton will be operational with all base components ready for content development.

### 1.2 Objectives

1. Define and implement Smart Dairy brand identity (colors, typography, spacing)
2. Create comprehensive design token system using CSS custom properties
3. Build reusable UI component library (buttons, cards, forms, navigation)
4. Install and configure Odoo 19 CE Website module
5. Integrate CloudFlare CDN for static asset delivery
6. Set up Google Analytics 4 and tracking infrastructure
7. Create base page templates and layouts
8. Implement responsive grid system with Bootstrap 5.3
9. Configure image optimization pipeline
10. Conduct Milestone 31 review, demo, and retrospective

### 1.3 Key Deliverables

| # | Deliverable                           | Owner  | Format                    |
|---|---------------------------------------|--------|---------------------------|
| 1 | Design system documentation           | Dev 3  | Markdown + CSS            |
| 2 | CSS design tokens file                | Dev 3  | SCSS/CSS variables        |
| 3 | Button component library              | Dev 3  | HTML/CSS/JS               |
| 4 | Form component library                | Dev 1  | OWL/HTML/CSS              |
| 5 | Card component library                | Dev 3  | HTML/CSS                  |
| 6 | Navigation components                 | Dev 3  | HTML/CSS/JS               |
| 7 | Odoo Website module configured        | Dev 1  | Odoo configuration        |
| 8 | CDN integration complete              | Dev 2  | CloudFlare/Nginx config   |
| 9 | Google Analytics 4 setup              | Dev 2  | GA4 tracking code         |
| 10| Base page templates                   | Dev 3  | QWeb templates            |
| 11| Responsive grid system                | Dev 3  | SCSS/CSS                  |
| 12| Milestone 31 review report            | All    | Markdown document         |

### 1.4 Prerequisites

- Phase 3 completed successfully
- Odoo 19 CE environment operational
- Docker development environment running
- Smart Dairy brand assets (logo, color palette) from marketing team
- CloudFlare account provisioned
- Google Analytics 4 property created

### 1.5 Success Criteria

- [ ] Design system documented and approved by stakeholders
- [ ] All UI components rendering correctly across Chrome, Firefox, Safari, Edge
- [ ] Odoo Website module accessible at configured domain
- [ ] CDN serving static assets with >40% load time reduction
- [ ] Google Analytics tracking page views and events
- [ ] Lighthouse Performance score >85 on base templates
- [ ] All responsive breakpoints tested (mobile, tablet, desktop)

---

## 2. Requirement Traceability Matrix

| Req ID        | Source     | Requirement Description                           | Day(s)  | Task Reference              |
| ------------- | ---------- | ------------------------------------------------- | ------- | --------------------------- |
| RFP-WEB-001   | RFP §2     | Professional corporate website                    | 151-160 | Design system, templates    |
| RFP-WEB-003   | RFP §3     | Page load time <2 seconds                         | 156-158 | CDN, optimization           |
| RFP-WEB-006   | RFP §4     | >60% mobile traffic support                       | 153,159 | Responsive design           |
| BRD-BRAND-001 | BRD §2     | Professional brand representation                 | 151-152 | Design system               |
| SRS-WEB-001   | SRS §4.1   | Odoo Website module implementation                | 154-155 | Odoo configuration          |
| SRS-WEB-003   | SRS §4.3   | Responsive design with Bootstrap                  | 153,159 | Grid system                 |
| SRS-PERF-001  | SRS §5.1   | Lighthouse score >90                              | 156-158 | Performance optimization    |
| NFR-PERF-01   | BRD §3     | Page load time <3 seconds                         | 156-158 | CDN, caching                |
| D-003 §1      | Impl Guide | Docker container configuration                    | 154     | Odoo Docker setup           |
| B-014 §2      | Impl Guide | Frontend coding standards                         | 151-152 | Component library           |

---

## 3. Day-by-Day Breakdown

---

### Day 151 — Design System Foundation & Brand Identity

**Objective:** Define Smart Dairy brand identity and establish the foundational design system with color palette, typography, and spacing scales.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Review Phase 3 completion status and verify ERP readiness — 1h
2. Plan Odoo Website module installation requirements — 1.5h
3. Design database schema for website content extensions — 2h
4. Document CMS data model requirements — 2h
5. Prepare server-side rendering configuration — 1.5h

```python
# Website content extension model planning
class WebsiteContentExtension(models.Model):
    _name = 'website.content.extension'
    _description = 'Smart Dairy Website Content Extensions'

    name = fields.Char('Content Name', required=True)
    content_type = fields.Selection([
        ('page', 'Static Page'),
        ('product', 'Product Content'),
        ('blog', 'Blog Content'),
        ('faq', 'FAQ Content'),
    ], string='Content Type', required=True)

    # SEO fields
    meta_title = fields.Char('Meta Title', size=70)
    meta_description = fields.Text('Meta Description')
    meta_keywords = fields.Char('Meta Keywords')
    canonical_url = fields.Char('Canonical URL')

    # Content fields
    content_html = fields.Html('HTML Content', sanitize=False)
    content_json = fields.Text('JSON Content')

    # Multilingual
    lang = fields.Selection([
        ('en_US', 'English'),
        ('bn_BD', 'Bangla'),
    ], string='Language', default='en_US')

    # Tracking
    published = fields.Boolean('Published', default=False)
    publish_date = fields.Datetime('Publish Date')
    author_id = fields.Many2one('res.users', string='Author')
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Set up CloudFlare account and configure DNS — 2h
2. Configure Nginx for CDN origin — 2h
3. Create Google Analytics 4 property for Smart Dairy — 1.5h
4. Set up Google Tag Manager container — 1.5h
5. Document analytics tracking plan — 1h

```nginx
# Nginx configuration for CDN origin
server {
    listen 80;
    server_name origin.smartdairy.com.bd;

    # Static assets with long cache
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2|svg|webp)$ {
        root /var/www/smartdairy/static;
        expires 1y;
        add_header Cache-Control "public, immutable";
        add_header X-Content-Type-Options nosniff;
        add_header X-Frame-Options DENY;

        # CORS headers for CDN
        add_header Access-Control-Allow-Origin "https://www.smartdairy.com.bd";
    }

    # Dynamic content proxy to Odoo
    location / {
        proxy_pass http://odoo:8069;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;

        # Cache control for HTML
        add_header Cache-Control "no-cache, must-revalidate";
    }
}
```

```javascript
// Google Analytics 4 configuration
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'G-SMARTDAIRY01', {
    'page_title': document.title,
    'page_location': window.location.href,
    'custom_map': {
        'dimension1': 'user_type',
        'dimension2': 'language',
        'dimension3': 'product_category'
    }
});

// Custom event tracking
function trackEvent(category, action, label, value) {
    gtag('event', action, {
        'event_category': category,
        'event_label': label,
        'value': value
    });
}

// Page view tracking
function trackPageView(pagePath, pageTitle) {
    gtag('event', 'page_view', {
        'page_path': pagePath,
        'page_title': pageTitle
    });
}
```

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Define Smart Dairy brand color palette — 2h
2. Define typography scale and font families — 2h
3. Define spacing system (4px base grid) — 1.5h
4. Create design tokens documentation — 1.5h
5. Set up SCSS project structure — 1h

```scss
// smart_dairy_design_tokens.scss

// ==============================================
// SMART DAIRY DESIGN SYSTEM - DESIGN TOKENS
// ==============================================

// ---------------------------------------------
// 1. COLOR PALETTE
// ---------------------------------------------

// Primary Colors - Smart Dairy Brand
$color-primary-blue: #0066CC;        // Primary brand blue
$color-primary-green: #2ECC71;       // Fresh/organic green
$color-dairy-white: #FAFAFA;         // Clean dairy white

// Secondary Colors
$color-secondary-yellow: #F39C12;    // Warmth, energy
$color-secondary-orange: #E67E22;    // Call to action

// Neutral Colors
$color-text-dark: #2C3E50;           // Primary text
$color-text-light: #7F8C8D;          // Secondary text
$color-bg-light: #ECF0F1;            // Light backgrounds
$color-bg-dark: #34495E;             // Dark backgrounds
$color-border: #BDC3C7;              // Borders, dividers

// Semantic Colors
$color-success: #27AE60;
$color-warning: #F1C40F;
$color-error: #E74C3C;
$color-info: #3498DB;

// CSS Custom Properties Export
:root {
    // Primary
    --color-primary: #{$color-primary-blue};
    --color-primary-light: #{lighten($color-primary-blue, 15%)};
    --color-primary-dark: #{darken($color-primary-blue, 15%)};

    --color-secondary: #{$color-primary-green};
    --color-secondary-light: #{lighten($color-primary-green, 15%)};
    --color-secondary-dark: #{darken($color-primary-green, 15%)};

    --color-accent: #{$color-secondary-orange};

    // Text
    --color-text-primary: #{$color-text-dark};
    --color-text-secondary: #{$color-text-light};
    --color-text-inverse: #{$color-dairy-white};

    // Backgrounds
    --color-bg-primary: #{$color-dairy-white};
    --color-bg-secondary: #{$color-bg-light};
    --color-bg-dark: #{$color-bg-dark};

    // Semantic
    --color-success: #{$color-success};
    --color-warning: #{$color-warning};
    --color-error: #{$color-error};
    --color-info: #{$color-info};

    // Borders
    --color-border: #{$color-border};
    --color-border-light: #{lighten($color-border, 10%)};
}

// ---------------------------------------------
// 2. TYPOGRAPHY
// ---------------------------------------------

// Font Families
$font-family-primary: 'Inter', 'Noto Sans Bengali', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
$font-family-headings: 'Poppins', 'Noto Sans Bengali', -apple-system, BlinkMacSystemFont, sans-serif;
$font-family-mono: 'JetBrains Mono', 'Fira Code', Consolas, monospace;

// Font Sizes (Modular Scale: 1.25 ratio)
$font-size-xs: 0.75rem;      // 12px
$font-size-sm: 0.875rem;     // 14px
$font-size-base: 1rem;       // 16px
$font-size-lg: 1.125rem;     // 18px
$font-size-xl: 1.25rem;      // 20px
$font-size-2xl: 1.5rem;      // 24px
$font-size-3xl: 1.875rem;    // 30px
$font-size-4xl: 2.25rem;     // 36px
$font-size-5xl: 3rem;        // 48px
$font-size-6xl: 3.75rem;     // 60px

// Font Weights
$font-weight-light: 300;
$font-weight-normal: 400;
$font-weight-medium: 500;
$font-weight-semibold: 600;
$font-weight-bold: 700;

// Line Heights
$line-height-tight: 1.25;
$line-height-normal: 1.5;
$line-height-relaxed: 1.75;

// CSS Custom Properties Export
:root {
    // Font Families
    --font-family-primary: #{$font-family-primary};
    --font-family-headings: #{$font-family-headings};
    --font-family-mono: #{$font-family-mono};

    // Font Sizes
    --font-size-xs: #{$font-size-xs};
    --font-size-sm: #{$font-size-sm};
    --font-size-base: #{$font-size-base};
    --font-size-lg: #{$font-size-lg};
    --font-size-xl: #{$font-size-xl};
    --font-size-2xl: #{$font-size-2xl};
    --font-size-3xl: #{$font-size-3xl};
    --font-size-4xl: #{$font-size-4xl};
    --font-size-5xl: #{$font-size-5xl};
    --font-size-6xl: #{$font-size-6xl};

    // Line Heights
    --line-height-tight: #{$line-height-tight};
    --line-height-normal: #{$line-height-normal};
    --line-height-relaxed: #{$line-height-relaxed};
}

// ---------------------------------------------
// 3. SPACING SYSTEM (4px Base Grid)
// ---------------------------------------------

$spacing-unit: 4px;

$spacing-0: 0;
$spacing-1: $spacing-unit * 1;    // 4px
$spacing-2: $spacing-unit * 2;    // 8px
$spacing-3: $spacing-unit * 3;    // 12px
$spacing-4: $spacing-unit * 4;    // 16px
$spacing-5: $spacing-unit * 5;    // 20px
$spacing-6: $spacing-unit * 6;    // 24px
$spacing-8: $spacing-unit * 8;    // 32px
$spacing-10: $spacing-unit * 10;  // 40px
$spacing-12: $spacing-unit * 12;  // 48px
$spacing-16: $spacing-unit * 16;  // 64px
$spacing-20: $spacing-unit * 20;  // 80px
$spacing-24: $spacing-unit * 24;  // 96px

:root {
    --spacing-0: #{$spacing-0};
    --spacing-1: #{$spacing-1};
    --spacing-2: #{$spacing-2};
    --spacing-3: #{$spacing-3};
    --spacing-4: #{$spacing-4};
    --spacing-5: #{$spacing-5};
    --spacing-6: #{$spacing-6};
    --spacing-8: #{$spacing-8};
    --spacing-10: #{$spacing-10};
    --spacing-12: #{$spacing-12};
    --spacing-16: #{$spacing-16};
    --spacing-20: #{$spacing-20};
    --spacing-24: #{$spacing-24};
}

// ---------------------------------------------
// 4. BREAKPOINTS
// ---------------------------------------------

$breakpoint-xs: 0;
$breakpoint-sm: 576px;
$breakpoint-md: 768px;
$breakpoint-lg: 992px;
$breakpoint-xl: 1200px;
$breakpoint-xxl: 1400px;

:root {
    --breakpoint-sm: #{$breakpoint-sm};
    --breakpoint-md: #{$breakpoint-md};
    --breakpoint-lg: #{$breakpoint-lg};
    --breakpoint-xl: #{$breakpoint-xl};
    --breakpoint-xxl: #{$breakpoint-xxl};
}

// Mixins for responsive design
@mixin respond-to($breakpoint) {
    @if $breakpoint == 'sm' {
        @media (min-width: $breakpoint-sm) { @content; }
    } @else if $breakpoint == 'md' {
        @media (min-width: $breakpoint-md) { @content; }
    } @else if $breakpoint == 'lg' {
        @media (min-width: $breakpoint-lg) { @content; }
    } @else if $breakpoint == 'xl' {
        @media (min-width: $breakpoint-xl) { @content; }
    } @else if $breakpoint == 'xxl' {
        @media (min-width: $breakpoint-xxl) { @content; }
    }
}

// ---------------------------------------------
// 5. SHADOWS
// ---------------------------------------------

$shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
$shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
$shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
$shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);

:root {
    --shadow-sm: #{$shadow-sm};
    --shadow-md: #{$shadow-md};
    --shadow-lg: #{$shadow-lg};
    --shadow-xl: #{$shadow-xl};
}

// ---------------------------------------------
// 6. BORDER RADIUS
// ---------------------------------------------

$radius-sm: 4px;
$radius-md: 8px;
$radius-lg: 12px;
$radius-xl: 16px;
$radius-full: 9999px;

:root {
    --radius-sm: #{$radius-sm};
    --radius-md: #{$radius-md};
    --radius-lg: #{$radius-lg};
    --radius-xl: #{$radius-xl};
    --radius-full: #{$radius-full};
}

// ---------------------------------------------
// 7. TRANSITIONS
// ---------------------------------------------

$transition-fast: 150ms ease-in-out;
$transition-normal: 300ms ease-in-out;
$transition-slow: 500ms ease-in-out;

:root {
    --transition-fast: #{$transition-fast};
    --transition-normal: #{$transition-normal};
    --transition-slow: #{$transition-slow};
}

// ---------------------------------------------
// 8. Z-INDEX SCALE
// ---------------------------------------------

$z-index-dropdown: 1000;
$z-index-sticky: 1020;
$z-index-fixed: 1030;
$z-index-modal-backdrop: 1040;
$z-index-modal: 1050;
$z-index-popover: 1060;
$z-index-tooltip: 1070;

:root {
    --z-dropdown: #{$z-index-dropdown};
    --z-sticky: #{$z-index-sticky};
    --z-fixed: #{$z-index-fixed};
    --z-modal-backdrop: #{$z-index-modal-backdrop};
    --z-modal: #{$z-index-modal};
    --z-popover: #{$z-index-popover};
    --z-tooltip: #{$z-index-tooltip};
}
```

**End-of-Day 151 Deliverables:**

- [ ] Design tokens documentation complete
- [ ] Color palette defined with CSS custom properties
- [ ] Typography scale established
- [ ] Spacing system documented
- [ ] CloudFlare DNS configured
- [ ] Google Analytics 4 property created

---

### Day 152 — Component Library: Buttons & Form Elements

**Objective:** Create comprehensive button component library and form input components following the design system.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create Odoo form field extensions for custom validation — 3h
2. Implement server-side form validation helpers — 2.5h
3. Design CSRF protection for all forms — 1.5h
4. Create email notification templates for form submissions — 1h

```python
# Form validation helpers for website forms
from odoo import models, fields, api
from odoo.exceptions import ValidationError
import re

class WebsiteFormValidator(models.AbstractModel):
    _name = 'website.form.validator'
    _description = 'Website Form Validation Helpers'

    @api.model
    def validate_email(self, email):
        """Validate email format"""
        pattern = r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$'
        if not re.match(pattern, email):
            raise ValidationError('Invalid email address format')
        return True

    @api.model
    def validate_phone_bd(self, phone):
        """Validate Bangladesh phone number"""
        # Bangladesh mobile: +880 1X-XXXXXXXX
        pattern = r'^(\+?880)?01[3-9]\d{8}$'
        cleaned = re.sub(r'[\s\-\(\)]', '', phone)
        if not re.match(pattern, cleaned):
            raise ValidationError('Invalid Bangladesh phone number')
        return True

    @api.model
    def validate_required_fields(self, data, required_fields):
        """Validate required fields are present and non-empty"""
        missing = []
        for field in required_fields:
            if field not in data or not data[field]:
                missing.append(field)
        if missing:
            raise ValidationError(f'Required fields missing: {", ".join(missing)}')
        return True

    @api.model
    def sanitize_html(self, html_content):
        """Sanitize HTML content to prevent XSS"""
        from lxml.html.clean import Cleaner
        cleaner = Cleaner(
            scripts=True,
            javascript=True,
            embedded=True,
            meta=True,
            page_structure=True,
            processing_instructions=True,
            remove_unknown_tags=True,
            safe_attrs_only=True,
        )
        return cleaner.clean_html(html_content)


# CSRF Protection Mixin
class WebsiteFormCSRF(models.AbstractModel):
    _name = 'website.form.csrf'
    _description = 'CSRF Protection for Website Forms'

    @api.model
    def generate_csrf_token(self, session_id):
        """Generate CSRF token for form"""
        import hashlib
        import time
        secret = self.env['ir.config_parameter'].sudo().get_param('database.secret')
        data = f"{session_id}{time.time()}{secret}"
        return hashlib.sha256(data.encode()).hexdigest()

    @api.model
    def validate_csrf_token(self, token, session_id):
        """Validate CSRF token"""
        # In production, compare with stored token
        if not token or len(token) != 64:
            raise ValidationError('Invalid security token')
        return True
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Implement client-side form validation framework — 3h
2. Create form submission AJAX handlers — 2h
3. Set up form analytics tracking — 1.5h
4. Configure rate limiting for form submissions — 1.5h

```javascript
// Form validation framework
class SmartDairyFormValidator {
    constructor(form) {
        this.form = form;
        this.errors = {};
        this.validators = {
            required: this.validateRequired.bind(this),
            email: this.validateEmail.bind(this),
            phone_bd: this.validatePhoneBD.bind(this),
            min_length: this.validateMinLength.bind(this),
            max_length: this.validateMaxLength.bind(this),
            pattern: this.validatePattern.bind(this),
        };
    }

    validateRequired(value, fieldName) {
        if (!value || value.trim() === '') {
            return `${fieldName} is required`;
        }
        return null;
    }

    validateEmail(value, fieldName) {
        if (!value) return null;
        const pattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!pattern.test(value)) {
            return 'Please enter a valid email address';
        }
        return null;
    }

    validatePhoneBD(value, fieldName) {
        if (!value) return null;
        const cleaned = value.replace(/[\s\-\(\)]/g, '');
        const pattern = /^(\+?880)?01[3-9]\d{8}$/;
        if (!pattern.test(cleaned)) {
            return 'Please enter a valid Bangladesh phone number';
        }
        return null;
    }

    validateMinLength(value, fieldName, minLen) {
        if (!value) return null;
        if (value.length < minLen) {
            return `${fieldName} must be at least ${minLen} characters`;
        }
        return null;
    }

    validateMaxLength(value, fieldName, maxLen) {
        if (!value) return null;
        if (value.length > maxLen) {
            return `${fieldName} must be no more than ${maxLen} characters`;
        }
        return null;
    }

    validatePattern(value, fieldName, pattern) {
        if (!value) return null;
        const regex = new RegExp(pattern);
        if (!regex.test(value)) {
            return `${fieldName} format is invalid`;
        }
        return null;
    }

    validateField(field) {
        const value = field.value;
        const fieldName = field.dataset.fieldName || field.name;
        const validations = (field.dataset.validate || '').split(',');

        for (const validation of validations) {
            const [rule, param] = validation.split(':');
            if (this.validators[rule]) {
                const error = this.validators[rule](value, fieldName, param);
                if (error) {
                    this.showError(field, error);
                    return false;
                }
            }
        }

        this.clearError(field);
        return true;
    }

    validateForm() {
        const fields = this.form.querySelectorAll('[data-validate]');
        let isValid = true;

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    showError(field, message) {
        field.classList.add('is-invalid');
        field.classList.remove('is-valid');

        let errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            field.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }

    clearError(field) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');

        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.textContent = '';
        }
    }
}

// AJAX Form Submission
class SmartDairyFormSubmitter {
    constructor(form, options = {}) {
        this.form = form;
        this.options = {
            url: form.action,
            method: form.method || 'POST',
            onSuccess: options.onSuccess || this.defaultSuccess,
            onError: options.onError || this.defaultError,
            onProgress: options.onProgress || null,
        };
        this.validator = new SmartDairyFormValidator(form);
    }

    async submit() {
        if (!this.validator.validateForm()) {
            return false;
        }

        const formData = new FormData(this.form);
        const submitBtn = this.form.querySelector('[type="submit"]');

        // Disable submit button
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Submitting...';
        }

        try {
            const response = await fetch(this.options.url, {
                method: this.options.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const data = await response.json();

            if (response.ok) {
                this.options.onSuccess(data);

                // Track form submission
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'form_submission', {
                        'event_category': 'forms',
                        'event_label': this.form.dataset.formName || 'unknown',
                    });
                }
            } else {
                this.options.onError(data);
            }
        } catch (error) {
            this.options.onError({ error: error.message });
        } finally {
            // Re-enable submit button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Submit';
            }
        }
    }

    defaultSuccess(data) {
        alert('Form submitted successfully!');
    }

    defaultError(data) {
        alert('An error occurred. Please try again.');
    }
}
```

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Create button component variations — 3h
2. Create form input components — 3h
3. Document button usage guidelines — 1h
4. Create interactive button showcase page — 1h

```scss
// _buttons.scss - Smart Dairy Button Components

// Button Base
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-2);
    padding: var(--spacing-3) var(--spacing-6);
    font-family: var(--font-family-primary);
    font-size: var(--font-size-base);
    font-weight: 500;
    line-height: 1.5;
    text-decoration: none;
    border: 2px solid transparent;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all var(--transition-fast);
    white-space: nowrap;

    &:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba($color-primary-blue, 0.25);
    }

    &:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    // Icon inside button
    .btn-icon {
        width: 1.25em;
        height: 1.25em;
    }
}

// Primary Button
.btn-primary {
    background-color: var(--color-primary);
    border-color: var(--color-primary);
    color: var(--color-text-inverse);

    &:hover:not(:disabled) {
        background-color: var(--color-primary-dark);
        border-color: var(--color-primary-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    &:active:not(:disabled) {
        transform: translateY(0);
        box-shadow: none;
    }
}

// Secondary Button
.btn-secondary {
    background-color: var(--color-secondary);
    border-color: var(--color-secondary);
    color: var(--color-text-inverse);

    &:hover:not(:disabled) {
        background-color: var(--color-secondary-dark);
        border-color: var(--color-secondary-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }
}

// Outline Buttons
.btn-outline-primary {
    background-color: transparent;
    border-color: var(--color-primary);
    color: var(--color-primary);

    &:hover:not(:disabled) {
        background-color: var(--color-primary);
        color: var(--color-text-inverse);
    }
}

.btn-outline-secondary {
    background-color: transparent;
    border-color: var(--color-secondary);
    color: var(--color-secondary);

    &:hover:not(:disabled) {
        background-color: var(--color-secondary);
        color: var(--color-text-inverse);
    }
}

// Ghost Button
.btn-ghost {
    background-color: transparent;
    border-color: transparent;
    color: var(--color-primary);

    &:hover:not(:disabled) {
        background-color: rgba($color-primary-blue, 0.1);
    }
}

// Button Sizes
.btn-sm {
    padding: var(--spacing-2) var(--spacing-4);
    font-size: var(--font-size-sm);
}

.btn-lg {
    padding: var(--spacing-4) var(--spacing-8);
    font-size: var(--font-size-lg);
}

.btn-xl {
    padding: var(--spacing-5) var(--spacing-10);
    font-size: var(--font-size-xl);
}

// Full Width Button
.btn-block {
    display: flex;
    width: 100%;
}

// Button with Icon
.btn-icon-left {
    .btn-icon {
        margin-right: var(--spacing-2);
    }
}

.btn-icon-right {
    .btn-icon {
        margin-left: var(--spacing-2);
    }
}

// Icon Only Button
.btn-icon-only {
    padding: var(--spacing-3);

    &.btn-sm { padding: var(--spacing-2); }
    &.btn-lg { padding: var(--spacing-4); }
}

// Loading State
.btn-loading {
    position: relative;
    color: transparent;
    pointer-events: none;

    &::after {
        content: '';
        position: absolute;
        width: 1em;
        height: 1em;
        border: 2px solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: btn-spinner 0.75s linear infinite;
    }
}

@keyframes btn-spinner {
    to { transform: rotate(360deg); }
}

// Button Group
.btn-group {
    display: inline-flex;

    .btn {
        border-radius: 0;

        &:first-child {
            border-top-left-radius: var(--radius-md);
            border-bottom-left-radius: var(--radius-md);
        }

        &:last-child {
            border-top-right-radius: var(--radius-md);
            border-bottom-right-radius: var(--radius-md);
        }

        &:not(:last-child) {
            border-right-width: 1px;
        }
    }
}
```

```scss
// _forms.scss - Smart Dairy Form Components

// Form Group
.form-group {
    margin-bottom: var(--spacing-4);
}

// Labels
.form-label {
    display: block;
    margin-bottom: var(--spacing-2);
    font-weight: 500;
    color: var(--color-text-primary);

    .required {
        color: var(--color-error);
        margin-left: var(--spacing-1);
    }
}

// Form Control Base
.form-control {
    display: block;
    width: 100%;
    padding: var(--spacing-3) var(--spacing-4);
    font-family: var(--font-family-primary);
    font-size: var(--font-size-base);
    line-height: 1.5;
    color: var(--color-text-primary);
    background-color: var(--color-bg-primary);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    transition: border-color var(--transition-fast), box-shadow var(--transition-fast);

    &::placeholder {
        color: var(--color-text-secondary);
        opacity: 0.7;
    }

    &:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba($color-primary-blue, 0.15);
    }

    &:disabled {
        background-color: var(--color-bg-secondary);
        cursor: not-allowed;
    }

    // Validation States
    &.is-valid {
        border-color: var(--color-success);

        &:focus {
            box-shadow: 0 0 0 3px rgba($color-success, 0.15);
        }
    }

    &.is-invalid {
        border-color: var(--color-error);

        &:focus {
            box-shadow: 0 0 0 3px rgba($color-error, 0.15);
        }
    }
}

// Textarea
textarea.form-control {
    min-height: 120px;
    resize: vertical;
}

// Select
select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%237F8C8D' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right var(--spacing-4) center;
    padding-right: var(--spacing-10);
}

// Checkbox & Radio
.form-check {
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-2);
    margin-bottom: var(--spacing-2);

    .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        margin-top: 0.125rem;
        border: 2px solid var(--color-border);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all var(--transition-fast);

        &[type="radio"] {
            border-radius: 50%;
        }

        &:checked {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }

        &:focus {
            box-shadow: 0 0 0 3px rgba($color-primary-blue, 0.15);
        }
    }

    .form-check-label {
        cursor: pointer;
        user-select: none;
    }
}

// Input Group
.input-group {
    display: flex;

    .form-control {
        flex: 1;
        border-radius: 0;

        &:first-child {
            border-top-left-radius: var(--radius-md);
            border-bottom-left-radius: var(--radius-md);
        }

        &:last-child {
            border-top-right-radius: var(--radius-md);
            border-bottom-right-radius: var(--radius-md);
        }
    }

    .input-group-text {
        display: flex;
        align-items: center;
        padding: var(--spacing-3) var(--spacing-4);
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);

        &:first-child {
            border-right: 0;
            border-top-left-radius: var(--radius-md);
            border-bottom-left-radius: var(--radius-md);
        }

        &:last-child {
            border-left: 0;
            border-top-right-radius: var(--radius-md);
            border-bottom-right-radius: var(--radius-md);
        }
    }
}

// Validation Feedback
.valid-feedback,
.invalid-feedback {
    display: none;
    margin-top: var(--spacing-1);
    font-size: var(--font-size-sm);
}

.valid-feedback {
    color: var(--color-success);
}

.invalid-feedback {
    color: var(--color-error);
}

.is-valid ~ .valid-feedback,
.is-invalid ~ .invalid-feedback {
    display: block;
}

// Form Text (Help Text)
.form-text {
    display: block;
    margin-top: var(--spacing-1);
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
}

// Floating Labels
.form-floating {
    position: relative;

    .form-control {
        padding-top: var(--spacing-6);
        padding-bottom: var(--spacing-2);

        &::placeholder {
            color: transparent;
        }

        &:focus,
        &:not(:placeholder-shown) {
            ~ .form-label {
                transform: translateY(-0.5rem) scale(0.85);
                opacity: 0.75;
            }
        }
    }

    .form-label {
        position: absolute;
        top: 50%;
        left: var(--spacing-4);
        transform: translateY(-50%);
        pointer-events: none;
        transition: all var(--transition-fast);
        transform-origin: left top;
    }
}
```

**End-of-Day 152 Deliverables:**

- [ ] Button component library complete (10+ variants)
- [ ] Form input components complete
- [ ] Client-side validation framework operational
- [ ] Server-side validation helpers implemented
- [ ] Button showcase page created

---

### Day 153 — Component Library: Cards & Media Components

**Objective:** Create card components for products, blog posts, team members, and testimonials, along with media components for images and videos.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create product card data model extensions — 3h
2. Implement card data API endpoints — 2.5h
3. Create image serving optimization logic — 2.5h

```python
# Product card data model and API
from odoo import models, fields, api
from odoo.http import request, route, Controller

class ProductCardData(models.Model):
    _inherit = 'product.template'

    # Card display fields
    card_title = fields.Char('Card Title', compute='_compute_card_title', store=True)
    card_description = fields.Text('Card Description', size=150)
    card_badge = fields.Char('Card Badge')  # e.g., "New", "Sale", "Organic"
    card_badge_color = fields.Selection([
        ('primary', 'Primary'),
        ('secondary', 'Secondary'),
        ('success', 'Success'),
        ('warning', 'Warning'),
        ('danger', 'Danger'),
    ], default='primary')

    # Image variants for responsive display
    image_thumbnail = fields.Binary('Thumbnail (150x150)', attachment=True)
    image_small = fields.Binary('Small (400x400)', attachment=True)
    image_medium = fields.Binary('Medium (800x800)', attachment=True)

    @api.depends('name')
    def _compute_card_title(self):
        for record in self:
            # Truncate to 50 characters
            record.card_title = record.name[:50] if record.name else ''

    def get_card_data(self):
        """Return data formatted for card component"""
        self.ensure_one()
        return {
            'id': self.id,
            'title': self.card_title,
            'description': self.card_description or '',
            'price': self.list_price,
            'currency': self.currency_id.symbol,
            'badge': self.card_badge,
            'badge_color': self.card_badge_color,
            'image_url': f'/web/image/product.template/{self.id}/image_medium',
            'url': f'/product/{self.id}',
            'category': self.categ_id.name if self.categ_id else '',
        }


class WebsiteCardController(Controller):

    @route('/api/cards/products', type='json', auth='public', methods=['POST'])
    def get_product_cards(self, category_id=None, limit=12, offset=0):
        """API endpoint for product cards"""
        domain = [('website_published', '=', True)]
        if category_id:
            domain.append(('categ_id', '=', int(category_id)))

        products = request.env['product.template'].sudo().search(
            domain, limit=limit, offset=offset, order='sequence, name'
        )

        return {
            'cards': [p.get_card_data() for p in products],
            'total': request.env['product.template'].sudo().search_count(domain),
            'has_more': offset + limit < request.env['product.template'].sudo().search_count(domain),
        }

    @route('/api/cards/blog', type='json', auth='public', methods=['POST'])
    def get_blog_cards(self, tag_id=None, limit=6, offset=0):
        """API endpoint for blog post cards"""
        domain = [('website_published', '=', True)]
        if tag_id:
            domain.append(('tag_ids', 'in', [int(tag_id)]))

        posts = request.env['blog.post'].sudo().search(
            domain, limit=limit, offset=offset, order='published_date desc'
        )

        return {
            'cards': [{
                'id': p.id,
                'title': p.name[:60],
                'excerpt': p.subtitle or p.teaser[:150] if p.teaser else '',
                'image_url': f'/web/image/blog.post/{p.id}/cover_properties',
                'url': f'/blog/{p.blog_id.id}/post/{p.id}',
                'author': p.author_id.name,
                'date': p.published_date.strftime('%B %d, %Y') if p.published_date else '',
                'read_time': f'{max(1, len((p.content or "").split()) // 200)} min read',
            } for p in posts],
            'total': request.env['blog.post'].sudo().search_count(domain),
        }
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Implement lazy loading for images — 2.5h
2. Create image lightbox component — 2.5h
3. Set up video embed optimization — 2h
4. Configure image CDN caching rules — 1h

```javascript
// Image lazy loading with Intersection Observer
class SmartDairyLazyLoader {
    constructor(options = {}) {
        this.options = {
            rootMargin: '50px 0px',
            threshold: 0.1,
            ...options
        };
        this.observer = null;
        this.init();
    }

    init() {
        if ('IntersectionObserver' in window) {
            this.observer = new IntersectionObserver(
                this.handleIntersect.bind(this),
                this.options
            );
            this.observe();
        } else {
            // Fallback: load all images immediately
            this.loadAllImages();
        }
    }

    observe() {
        const images = document.querySelectorAll('[data-lazy-src]');
        images.forEach(img => this.observer.observe(img));
    }

    handleIntersect(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                this.loadImage(entry.target);
                this.observer.unobserve(entry.target);
            }
        });
    }

    loadImage(img) {
        const src = img.dataset.lazySrc;
        const srcset = img.dataset.lazySrcset;

        // Create temporary image to preload
        const tempImg = new Image();
        tempImg.onload = () => {
            img.src = src;
            if (srcset) img.srcset = srcset;
            img.classList.add('loaded');
            img.removeAttribute('data-lazy-src');
            img.removeAttribute('data-lazy-srcset');
        };
        tempImg.src = src;
    }

    loadAllImages() {
        const images = document.querySelectorAll('[data-lazy-src]');
        images.forEach(img => this.loadImage(img));
    }
}

// Initialize lazy loading
document.addEventListener('DOMContentLoaded', () => {
    new SmartDairyLazyLoader();
});

// Image Lightbox Component
class SmartDairyLightbox {
    constructor() {
        this.lightbox = null;
        this.currentIndex = 0;
        this.images = [];
        this.init();
    }

    init() {
        this.createLightbox();
        this.bindEvents();
    }

    createLightbox() {
        const html = `
            <div class="sd-lightbox" id="sdLightbox" role="dialog" aria-modal="true">
                <div class="sd-lightbox-overlay"></div>
                <div class="sd-lightbox-content">
                    <button class="sd-lightbox-close" aria-label="Close">&times;</button>
                    <button class="sd-lightbox-prev" aria-label="Previous">&lsaquo;</button>
                    <button class="sd-lightbox-next" aria-label="Next">&rsaquo;</button>
                    <div class="sd-lightbox-image-container">
                        <img class="sd-lightbox-image" src="" alt="">
                    </div>
                    <div class="sd-lightbox-caption"></div>
                    <div class="sd-lightbox-counter"></div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', html);
        this.lightbox = document.getElementById('sdLightbox');
    }

    bindEvents() {
        // Open lightbox on image click
        document.querySelectorAll('[data-lightbox]').forEach(img => {
            img.addEventListener('click', (e) => this.open(e.target));
        });

        // Close button
        this.lightbox.querySelector('.sd-lightbox-close').addEventListener('click', () => this.close());

        // Overlay click to close
        this.lightbox.querySelector('.sd-lightbox-overlay').addEventListener('click', () => this.close());

        // Navigation
        this.lightbox.querySelector('.sd-lightbox-prev').addEventListener('click', () => this.prev());
        this.lightbox.querySelector('.sd-lightbox-next').addEventListener('click', () => this.next());

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!this.lightbox.classList.contains('active')) return;
            if (e.key === 'Escape') this.close();
            if (e.key === 'ArrowLeft') this.prev();
            if (e.key === 'ArrowRight') this.next();
        });
    }

    open(img) {
        const gallery = img.dataset.lightbox;
        this.images = Array.from(document.querySelectorAll(`[data-lightbox="${gallery}"]`));
        this.currentIndex = this.images.indexOf(img);
        this.showImage();
        this.lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    close() {
        this.lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }

    showImage() {
        const img = this.images[this.currentIndex];
        const lightboxImg = this.lightbox.querySelector('.sd-lightbox-image');
        const caption = this.lightbox.querySelector('.sd-lightbox-caption');
        const counter = this.lightbox.querySelector('.sd-lightbox-counter');

        lightboxImg.src = img.dataset.fullSrc || img.src;
        lightboxImg.alt = img.alt;
        caption.textContent = img.dataset.caption || img.alt;
        counter.textContent = `${this.currentIndex + 1} / ${this.images.length}`;

        // Hide nav buttons if only one image
        const prevBtn = this.lightbox.querySelector('.sd-lightbox-prev');
        const nextBtn = this.lightbox.querySelector('.sd-lightbox-next');
        prevBtn.style.display = this.images.length > 1 ? '' : 'none';
        nextBtn.style.display = this.images.length > 1 ? '' : 'none';
    }

    prev() {
        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
        this.showImage();
    }

    next() {
        this.currentIndex = (this.currentIndex + 1) % this.images.length;
        this.showImage();
    }
}

// Initialize lightbox
document.addEventListener('DOMContentLoaded', () => {
    new SmartDairyLightbox();
});
```

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Create product card component — 2.5h
2. Create blog post card component — 2h
3. Create team member card component — 1.5h
4. Create testimonial card component — 2h

```scss
// _cards.scss - Smart Dairy Card Components

// Card Base
.card {
    display: flex;
    flex-direction: column;
    background-color: var(--color-bg-primary);
    border: 1px solid var(--color-border-light);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: transform var(--transition-normal), box-shadow var(--transition-normal);

    &:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }
}

// Card Image
.card-img-top {
    width: 100%;
    height: auto;
    object-fit: cover;
}

.card-img-wrapper {
    position: relative;
    overflow: hidden;

    img {
        transition: transform var(--transition-slow);
    }

    &:hover img {
        transform: scale(1.05);
    }
}

// Card Body
.card-body {
    flex: 1;
    padding: var(--spacing-6);
}

// Card Title
.card-title {
    font-family: var(--font-family-headings);
    font-size: var(--font-size-xl);
    font-weight: 600;
    color: var(--color-text-primary);
    margin-bottom: var(--spacing-2);

    a {
        color: inherit;
        text-decoration: none;

        &:hover {
            color: var(--color-primary);
        }
    }
}

// Card Text
.card-text {
    color: var(--color-text-secondary);
    font-size: var(--font-size-base);
    line-height: var(--line-height-relaxed);
    margin-bottom: var(--spacing-4);
}

// Card Footer
.card-footer {
    padding: var(--spacing-4) var(--spacing-6);
    background-color: var(--color-bg-secondary);
    border-top: 1px solid var(--color-border-light);
}

// Card Badge
.card-badge {
    position: absolute;
    top: var(--spacing-4);
    left: var(--spacing-4);
    padding: var(--spacing-1) var(--spacing-3);
    font-size: var(--font-size-xs);
    font-weight: 600;
    text-transform: uppercase;
    border-radius: var(--radius-sm);

    &.badge-primary {
        background-color: var(--color-primary);
        color: white;
    }

    &.badge-secondary {
        background-color: var(--color-secondary);
        color: white;
    }

    &.badge-success {
        background-color: var(--color-success);
        color: white;
    }

    &.badge-warning {
        background-color: var(--color-warning);
        color: var(--color-text-primary);
    }
}

// ==========================================
// PRODUCT CARD
// ==========================================

.product-card {
    @extend .card;

    .product-card-image {
        height: 200px;

        @include respond-to('md') {
            height: 250px;
        }

        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }

    .product-card-category {
        font-size: var(--font-size-xs);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--color-primary);
        margin-bottom: var(--spacing-1);
    }

    .product-card-title {
        font-size: var(--font-size-lg);
        margin-bottom: var(--spacing-2);
    }

    .product-card-price {
        font-size: var(--font-size-xl);
        font-weight: 700;
        color: var(--color-primary);

        .price-currency {
            font-size: var(--font-size-base);
            font-weight: 500;
        }

        .price-unit {
            font-size: var(--font-size-sm);
            font-weight: 400;
            color: var(--color-text-secondary);
        }
    }

    .product-card-actions {
        display: flex;
        gap: var(--spacing-2);
        margin-top: var(--spacing-4);
    }
}

// ==========================================
// BLOG CARD
// ==========================================

.blog-card {
    @extend .card;

    .blog-card-image {
        height: 180px;

        @include respond-to('md') {
            height: 220px;
        }

        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }

    .blog-card-meta {
        display: flex;
        align-items: center;
        gap: var(--spacing-4);
        font-size: var(--font-size-sm);
        color: var(--color-text-secondary);
        margin-bottom: var(--spacing-3);

        .meta-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-1);
        }
    }

    .blog-card-title {
        font-size: var(--font-size-lg);
        line-height: var(--line-height-tight);
        margin-bottom: var(--spacing-2);

        // Clamp to 2 lines
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .blog-card-excerpt {
        font-size: var(--font-size-sm);
        color: var(--color-text-secondary);

        // Clamp to 3 lines
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .blog-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .blog-card-author {
        display: flex;
        align-items: center;
        gap: var(--spacing-2);

        .author-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        .author-name {
            font-size: var(--font-size-sm);
            font-weight: 500;
        }
    }
}

// ==========================================
// TEAM CARD
// ==========================================

.team-card {
    @extend .card;
    text-align: center;

    .team-card-image {
        height: 280px;

        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: top;
        }
    }

    .team-card-name {
        font-size: var(--font-size-xl);
        font-weight: 600;
        margin-bottom: var(--spacing-1);
    }

    .team-card-role {
        font-size: var(--font-size-sm);
        color: var(--color-primary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: var(--spacing-3);
    }

    .team-card-bio {
        font-size: var(--font-size-sm);
        color: var(--color-text-secondary);
        margin-bottom: var(--spacing-4);
    }

    .team-card-social {
        display: flex;
        justify-content: center;
        gap: var(--spacing-3);

        a {
            color: var(--color-text-secondary);
            font-size: var(--font-size-lg);
            transition: color var(--transition-fast);

            &:hover {
                color: var(--color-primary);
            }
        }
    }
}

// ==========================================
// TESTIMONIAL CARD
// ==========================================

.testimonial-card {
    @extend .card;
    padding: var(--spacing-8);
    text-align: center;

    .testimonial-quote {
        font-size: var(--font-size-5xl);
        color: var(--color-primary);
        line-height: 1;
        margin-bottom: var(--spacing-4);
    }

    .testimonial-text {
        font-size: var(--font-size-lg);
        font-style: italic;
        color: var(--color-text-primary);
        line-height: var(--line-height-relaxed);
        margin-bottom: var(--spacing-6);
    }

    .testimonial-author {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--spacing-4);

        .author-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            object-fit: cover;
        }

        .author-info {
            text-align: left;
        }

        .author-name {
            font-weight: 600;
            color: var(--color-text-primary);
        }

        .author-title {
            font-size: var(--font-size-sm);
            color: var(--color-text-secondary);
        }
    }

    .testimonial-rating {
        display: flex;
        justify-content: center;
        gap: var(--spacing-1);
        margin-bottom: var(--spacing-4);

        .star {
            color: var(--color-warning);
            font-size: var(--font-size-lg);
        }
    }
}

// ==========================================
// CARD GRID LAYOUTS
// ==========================================

.card-grid {
    display: grid;
    gap: var(--spacing-6);

    &.grid-2 {
        grid-template-columns: repeat(1, 1fr);
        @include respond-to('md') { grid-template-columns: repeat(2, 1fr); }
    }

    &.grid-3 {
        grid-template-columns: repeat(1, 1fr);
        @include respond-to('sm') { grid-template-columns: repeat(2, 1fr); }
        @include respond-to('lg') { grid-template-columns: repeat(3, 1fr); }
    }

    &.grid-4 {
        grid-template-columns: repeat(1, 1fr);
        @include respond-to('sm') { grid-template-columns: repeat(2, 1fr); }
        @include respond-to('lg') { grid-template-columns: repeat(3, 1fr); }
        @include respond-to('xl') { grid-template-columns: repeat(4, 1fr); }
    }
}
```

```html
<!-- Product Card HTML Template -->
<div class="product-card">
    <div class="product-card-image card-img-wrapper">
        <span class="card-badge badge-success">Organic</span>
        <img data-lazy-src="/images/products/fresh-milk.webp"
             data-lazy-srcset="/images/products/fresh-milk-400.webp 400w,
                              /images/products/fresh-milk-800.webp 800w"
             sizes="(max-width: 768px) 100vw, 400px"
             alt="Fresh Organic Milk"
             data-lightbox="products">
    </div>
    <div class="card-body">
        <span class="product-card-category">Fresh Milk</span>
        <h3 class="product-card-title">
            <a href="/product/fresh-organic-milk">Fresh Organic Milk</a>
        </h3>
        <p class="card-text">100% pure farm-fresh organic milk from grass-fed cows.</p>
        <div class="product-card-price">
            <span class="price-currency">৳</span>85
            <span class="price-unit">/liter</span>
        </div>
        <div class="product-card-actions">
            <a href="/product/fresh-organic-milk" class="btn btn-primary btn-sm">View Details</a>
            <button class="btn btn-outline-primary btn-sm btn-icon-only" aria-label="Add to wishlist">
                <i class="fa fa-heart"></i>
            </button>
        </div>
    </div>
</div>
```

**End-of-Day 153 Deliverables:**

- [ ] Product card component complete
- [ ] Blog post card component complete
- [ ] Team member card component complete
- [ ] Testimonial card component complete
- [ ] Lazy loading implemented
- [ ] Lightbox component functional

---

### Day 154 — Odoo Website Module Installation & Configuration

**Objective:** Install and configure the Odoo 19 CE Website module with Smart Dairy branding and initial settings.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Install Odoo Website module and dependencies — 2h
2. Configure website settings and domain — 2h
3. Set up website theme with Smart Dairy branding — 2.5h
4. Configure website menus and navigation — 1.5h

```python
# Odoo Website Installation and Configuration Script

from odoo import api, SUPERUSER_ID

def post_init_hook(cr, registry):
    """Post-installation hook for Smart Dairy website setup"""
    env = api.Environment(cr, SUPERUSER_ID, {})

    # Install required modules
    modules_to_install = [
        'website',
        'website_blog',
        'website_form',
        'website_crm',
        'website_sale',  # For product catalog (non-ecommerce)
        'website_slides',  # For training content
    ]

    for module_name in modules_to_install:
        module = env['ir.module.module'].search([('name', '=', module_name)])
        if module and module.state != 'installed':
            module.button_immediate_install()

    # Configure website
    website = env['website'].get_current_website()
    website.write({
        'name': 'Smart Dairy',
        'domain': 'www.smartdairy.com.bd',
        'company_id': env.ref('base.main_company').id,
        'default_lang_id': env.ref('base.lang_en').id,
        'language_ids': [(6, 0, [
            env.ref('base.lang_en').id,
            # Bangla will be added in Milestone 39
        ])],
        'social_facebook': 'https://facebook.com/smartdairybd',
        'social_twitter': 'https://twitter.com/smartdairybd',
        'social_instagram': 'https://instagram.com/smartdairybd',
        'social_youtube': 'https://youtube.com/@smartdairybd',
        'social_linkedin': 'https://linkedin.com/company/smartdairybd',
        'google_analytics_key': 'G-SMARTDAIRY01',
        'google_maps_api_key': env['ir.config_parameter'].sudo().get_param('google_maps_api_key', ''),
    })

    # Configure company details for website
    company = env.ref('base.main_company')
    company.write({
        'website': 'https://www.smartdairy.com.bd',
        'email': 'info@smartdairy.com.bd',
        'phone': '+880 1XXX-XXXXXX',
        'street': 'Islambag Kali, Vulta',
        'street2': 'Rupgonj, Narayanganj',
        'city': 'Dhaka',
        'country_id': env.ref('base.bd').id,
        'zip': '1460',
    })


# Website Menu Configuration
class SmartDairyMenu(models.Model):
    _inherit = 'website.menu'

    @api.model
    def create_smart_dairy_menu(self):
        """Create Smart Dairy website menu structure"""
        website = self.env['website'].get_current_website()

        # Clear existing menus
        self.search([('website_id', '=', website.id)]).unlink()

        # Main menu structure
        menu_structure = [
            {
                'name': 'Home',
                'url': '/',
                'sequence': 10,
            },
            {
                'name': 'About Us',
                'url': '/about',
                'sequence': 20,
                'children': [
                    {'name': 'Our Story', 'url': '/about/story', 'sequence': 10},
                    {'name': 'Vision & Mission', 'url': '/about/vision', 'sequence': 20},
                    {'name': 'Our Team', 'url': '/about/team', 'sequence': 30},
                    {'name': 'Certifications', 'url': '/about/certifications', 'sequence': 40},
                ]
            },
            {
                'name': 'Products',
                'url': '/products',
                'sequence': 30,
                'children': [
                    {'name': 'Fresh Milk', 'url': '/products/fresh-milk', 'sequence': 10},
                    {'name': 'Yogurt', 'url': '/products/yogurt', 'sequence': 20},
                    {'name': 'Cheese', 'url': '/products/cheese', 'sequence': 30},
                    {'name': 'Butter & Ghee', 'url': '/products/butter-ghee', 'sequence': 40},
                    {'name': 'All Products', 'url': '/products', 'sequence': 50},
                ]
            },
            {
                'name': 'Services',
                'url': '/services',
                'sequence': 40,
                'children': [
                    {'name': 'B2B Partnership', 'url': '/services/b2b', 'sequence': 10},
                    {'name': 'Livestock Trading', 'url': '/services/livestock', 'sequence': 20},
                    {'name': 'Equipment', 'url': '/services/equipment', 'sequence': 30},
                    {'name': 'Training & Consultancy', 'url': '/services/training', 'sequence': 40},
                ]
            },
            {
                'name': 'Blog',
                'url': '/blog',
                'sequence': 50,
            },
            {
                'name': 'Contact',
                'url': '/contact',
                'sequence': 60,
            },
        ]

        def create_menu_item(item, parent_id=None):
            menu = self.create({
                'name': item['name'],
                'url': item['url'],
                'sequence': item['sequence'],
                'parent_id': parent_id,
                'website_id': website.id,
            })
            for child in item.get('children', []):
                create_menu_item(child, menu.id)

        for item in menu_structure:
            create_menu_item(item)

        return True
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Configure Nginx for Odoo website — 2.5h
2. Set up SSL certificate with Let's Encrypt — 2h
3. Configure website caching — 2h
4. Test website accessibility — 1.5h

```nginx
# Nginx configuration for Smart Dairy website
# /etc/nginx/sites-available/smartdairy.com.bd

upstream odoo {
    server 127.0.0.1:8069 weight=1 fail_timeout=0;
}

upstream odoo-chat {
    server 127.0.0.1:8072 weight=1 fail_timeout=0;
}

# HTTP to HTTPS redirect
server {
    listen 80;
    listen [::]:80;
    server_name smartdairy.com.bd www.smartdairy.com.bd;

    location /.well-known/acme-challenge/ {
        root /var/www/certbot;
    }

    location / {
        return 301 https://www.smartdairy.com.bd$request_uri;
    }
}

# Main HTTPS server
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name www.smartdairy.com.bd;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/smartdairy.com.bd/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/smartdairy.com.bd/privkey.pem;
    ssl_session_timeout 1d;
    ssl_session_cache shared:SSL:50m;
    ssl_session_tickets off;

    # Modern SSL configuration
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;

    # HSTS
    add_header Strict-Transport-Security "max-age=63072000" always;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Gzip compression
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml;
    gzip_min_length 1000;
    gzip_comp_level 6;

    # Access and error logs
    access_log /var/log/nginx/smartdairy_access.log;
    error_log /var/log/nginx/smartdairy_error.log;

    # Max upload size
    client_max_body_size 64M;

    # Proxy headers
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;

    # Proxy timeouts
    proxy_connect_timeout 720s;
    proxy_send_timeout 720s;
    proxy_read_timeout 720s;

    # Static files with CDN fallback
    location ~* /web/static/ {
        proxy_cache_valid 200 60m;
        proxy_buffering on;
        expires 7d;
        add_header Cache-Control "public, immutable";
        proxy_pass http://odoo;
    }

    # Website images
    location ~* /web/image/ {
        proxy_cache_valid 200 24h;
        proxy_buffering on;
        expires 30d;
        add_header Cache-Control "public";
        proxy_pass http://odoo;
    }

    # Longpolling
    location /longpolling {
        proxy_pass http://odoo-chat;
    }

    # Main application
    location / {
        proxy_pass http://odoo;
        proxy_redirect off;
    }
}
```

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Create Odoo website theme module — 3h
2. Implement design tokens in Odoo theme — 2.5h
3. Configure website header and footer templates — 2.5h

```python
# smart_dairy_theme/__manifest__.py
{
    'name': 'Smart Dairy Website Theme',
    'version': '19.0.1.0.0',
    'category': 'Website/Theme',
    'summary': 'Smart Dairy corporate website theme',
    'description': """
        Custom theme for Smart Dairy corporate website.
        Features:
        - Smart Dairy brand colors and typography
        - Responsive design with Bootstrap 5.3
        - Custom page templates
        - Optimized for performance
    """,
    'author': 'Smart Dairy Ltd.',
    'website': 'https://www.smartdairy.com.bd',
    'license': 'LGPL-3',
    'depends': [
        'website',
        'website_blog',
    ],
    'data': [
        'views/website_templates.xml',
        'views/header_footer.xml',
        'views/snippets.xml',
    ],
    'assets': {
        'web.assets_frontend': [
            # Design tokens and variables
            'smart_dairy_theme/static/src/scss/design_tokens.scss',
            # Base styles
            'smart_dairy_theme/static/src/scss/base.scss',
            # Components
            'smart_dairy_theme/static/src/scss/components/_buttons.scss',
            'smart_dairy_theme/static/src/scss/components/_forms.scss',
            'smart_dairy_theme/static/src/scss/components/_cards.scss',
            'smart_dairy_theme/static/src/scss/components/_navigation.scss',
            # Layout
            'smart_dairy_theme/static/src/scss/layout/_header.scss',
            'smart_dairy_theme/static/src/scss/layout/_footer.scss',
            'smart_dairy_theme/static/src/scss/layout/_grid.scss',
            # JavaScript
            'smart_dairy_theme/static/src/js/main.js',
            'smart_dairy_theme/static/src/js/lazy_load.js',
            'smart_dairy_theme/static/src/js/lightbox.js',
        ],
    },
    'images': [
        'static/description/banner.png',
        'static/description/theme_screenshot.png',
    ],
    'installable': True,
    'auto_install': False,
    'application': False,
}
```

```xml
<!-- smart_dairy_theme/views/header_footer.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Custom Header -->
    <template id="smart_dairy_header" inherit_id="website.layout" name="Smart Dairy Header">
        <xpath expr="//header" position="replace">
            <header class="sd-header">
                <!-- Top Bar -->
                <div class="sd-topbar d-none d-lg-block">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <div class="sd-topbar-contact">
                                    <a href="tel:+8801XXXXXXXXX">
                                        <i class="fa fa-phone"></i>
                                        <span>+880 1XXX-XXXXXX</span>
                                    </a>
                                    <a href="mailto:info@smartdairy.com.bd">
                                        <i class="fa fa-envelope"></i>
                                        <span>info@smartdairy.com.bd</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6 text-end">
                                <div class="sd-topbar-social">
                                    <a t-att-href="website.social_facebook" target="_blank" rel="noopener" aria-label="Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a t-att-href="website.social_instagram" target="_blank" rel="noopener" aria-label="Instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                    <a t-att-href="website.social_youtube" target="_blank" rel="noopener" aria-label="YouTube">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                    <a t-att-href="website.social_linkedin" target="_blank" rel="noopener" aria-label="LinkedIn">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                </div>
                                <!-- Language Switcher -->
                                <div class="sd-lang-switcher d-inline-block ms-4">
                                    <t t-foreach="languages" t-as="lg">
                                        <a t-att-href="lg['url_code']"
                                           t-attf-class="lang-link #{'active' if lg['code'] == lang else ''}">
                                            <t t-esc="lg['code'].upper()"/>
                                        </a>
                                    </t>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Navigation -->
                <nav class="sd-navbar navbar navbar-expand-lg">
                    <div class="container">
                        <!-- Logo -->
                        <a class="navbar-brand" href="/">
                            <img src="/smart_dairy_theme/static/src/img/logo.png"
                                 alt="Smart Dairy"
                                 height="50"
                                 width="auto">
                        </a>

                        <!-- Mobile Toggle -->
                        <button class="navbar-toggler" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#sdNavbar"
                                aria-controls="sdNavbar"
                                aria-expanded="false"
                                aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <!-- Navigation Menu -->
                        <div class="collapse navbar-collapse" id="sdNavbar">
                            <ul class="navbar-nav ms-auto">
                                <t t-foreach="website.menu_id.child_id" t-as="menu">
                                    <t t-if="menu.child_id">
                                        <!-- Dropdown Menu -->
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle"
                                               href="#"
                                               role="button"
                                               data-bs-toggle="dropdown"
                                               aria-expanded="false">
                                                <t t-esc="menu.name"/>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <t t-foreach="menu.child_id" t-as="submenu">
                                                    <li>
                                                        <a class="dropdown-item" t-att-href="submenu.url">
                                                            <t t-esc="submenu.name"/>
                                                        </a>
                                                    </li>
                                                </t>
                                            </ul>
                                        </li>
                                    </t>
                                    <t t-else="">
                                        <!-- Regular Menu Item -->
                                        <li class="nav-item">
                                            <a class="nav-link" t-att-href="menu.url">
                                                <t t-esc="menu.name"/>
                                            </a>
                                        </li>
                                    </t>
                                </t>
                            </ul>

                            <!-- CTA Button -->
                            <div class="sd-navbar-cta ms-lg-4">
                                <a href="/contact" class="btn btn-primary">
                                    Get Quote
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
        </xpath>
    </template>

    <!-- Custom Footer -->
    <template id="smart_dairy_footer" inherit_id="website.layout" name="Smart Dairy Footer">
        <xpath expr="//footer" position="replace">
            <footer class="sd-footer">
                <!-- Main Footer -->
                <div class="sd-footer-main">
                    <div class="container">
                        <div class="row">
                            <!-- Company Info -->
                            <div class="col-lg-4 mb-4 mb-lg-0">
                                <div class="sd-footer-brand">
                                    <img src="/smart_dairy_theme/static/src/img/logo-white.png"
                                         alt="Smart Dairy"
                                         height="40">
                                    <p class="mt-3">
                                        Fresh from farm to your doorstep.
                                        Bangladesh's leading organic dairy producer since 2020.
                                    </p>
                                    <div class="sd-footer-social mt-3">
                                        <a t-att-href="website.social_facebook" aria-label="Facebook">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                        <a t-att-href="website.social_instagram" aria-label="Instagram">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                        <a t-att-href="website.social_youtube" aria-label="YouTube">
                                            <i class="fab fa-youtube"></i>
                                        </a>
                                        <a t-att-href="website.social_linkedin" aria-label="LinkedIn">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Links -->
                            <div class="col-6 col-lg-2 mb-4 mb-lg-0">
                                <h5 class="sd-footer-title">Quick Links</h5>
                                <ul class="sd-footer-links">
                                    <li><a href="/about">About Us</a></li>
                                    <li><a href="/products">Products</a></li>
                                    <li><a href="/services">Services</a></li>
                                    <li><a href="/blog">Blog</a></li>
                                    <li><a href="/careers">Careers</a></li>
                                </ul>
                            </div>

                            <!-- Customer Service -->
                            <div class="col-6 col-lg-2 mb-4 mb-lg-0">
                                <h5 class="sd-footer-title">Support</h5>
                                <ul class="sd-footer-links">
                                    <li><a href="/contact">Contact Us</a></li>
                                    <li><a href="/faq">FAQ</a></li>
                                    <li><a href="/shipping">Shipping Info</a></li>
                                    <li><a href="/returns">Returns Policy</a></li>
                                    <li><a href="/terms">Terms of Service</a></li>
                                </ul>
                            </div>

                            <!-- Contact Info -->
                            <div class="col-lg-4">
                                <h5 class="sd-footer-title">Contact Us</h5>
                                <ul class="sd-footer-contact">
                                    <li>
                                        <i class="fa fa-map-marker-alt"></i>
                                        <span>Islambag Kali, Vulta, Rupgonj<br/>Narayanganj, Bangladesh</span>
                                    </li>
                                    <li>
                                        <i class="fa fa-phone"></i>
                                        <a href="tel:+8801XXXXXXXXX">+880 1XXX-XXXXXX</a>
                                    </li>
                                    <li>
                                        <i class="fa fa-envelope"></i>
                                        <a href="mailto:info@smartdairy.com.bd">info@smartdairy.com.bd</a>
                                    </li>
                                    <li>
                                        <i class="fa fa-clock"></i>
                                        <span>Sat - Thu: 9:00 AM - 6:00 PM</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Copyright Bar -->
                <div class="sd-footer-bottom">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-6 text-center text-md-start">
                                <p class="mb-0">
                                    © <t t-esc="datetime.date.today().year"/> Smart Dairy Ltd.
                                    All rights reserved.
                                </p>
                            </div>
                            <div class="col-md-6 text-center text-md-end">
                                <ul class="sd-footer-legal">
                                    <li><a href="/privacy">Privacy Policy</a></li>
                                    <li><a href="/terms">Terms of Service</a></li>
                                    <li><a href="/cookies">Cookie Policy</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </xpath>
    </template>
</odoo>
```

**End-of-Day 154 Deliverables:**

- [ ] Odoo Website module installed and operational
- [ ] Website accessible at configured domain
- [ ] SSL/HTTPS configured with Let's Encrypt
- [ ] Smart Dairy theme module created
- [ ] Header and footer templates implemented
- [ ] Website menus configured

---

### Day 155 — Navigation Components & Mega Menu

**Objective:** Complete navigation components including sticky header, mobile navigation, and mega menu for products.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create mega menu data API endpoints — 3h
2. Implement navigation caching — 2.5h
3. Configure menu management backend — 2.5h

```python
# Mega menu API endpoints
from odoo import http
from odoo.http import request

class MegaMenuController(http.Controller):

    @http.route('/api/mega-menu/products', type='json', auth='public', website=True)
    def get_product_mega_menu(self):
        """Get product categories for mega menu"""
        cache_key = 'mega_menu_products'
        cached = request.env['ir.cache'].sudo().get(cache_key)

        if cached:
            return cached

        categories = request.env['product.public.category'].sudo().search([
            ('parent_id', '=', False),
            ('website_published', '=', True),
        ], order='sequence')

        menu_data = []
        for cat in categories:
            cat_data = {
                'id': cat.id,
                'name': cat.name,
                'url': f'/products/{cat.id}',
                'image': f'/web/image/product.public.category/{cat.id}/image_128' if cat.image_128 else None,
                'subcategories': [],
            }

            for subcat in cat.child_id.filtered(lambda c: c.website_published):
                cat_data['subcategories'].append({
                    'id': subcat.id,
                    'name': subcat.name,
                    'url': f'/products/{subcat.id}',
                    'product_count': request.env['product.template'].sudo().search_count([
                        ('public_categ_ids', 'in', subcat.id),
                        ('website_published', '=', True),
                    ])
                })

            menu_data.append(cat_data)

        # Cache for 1 hour
        request.env['ir.cache'].sudo().set(cache_key, menu_data, 3600)

        return menu_data

    @http.route('/api/mega-menu/featured-products', type='json', auth='public', website=True)
    def get_featured_products(self, limit=4):
        """Get featured products for mega menu"""
        products = request.env['product.template'].sudo().search([
            ('website_published', '=', True),
            ('is_featured', '=', True),  # Custom field
        ], limit=limit, order='sequence')

        return [{
            'id': p.id,
            'name': p.name,
            'price': p.list_price,
            'currency': p.currency_id.symbol,
            'image_url': f'/web/image/product.template/{p.id}/image_256',
            'url': f'/product/{p.id}',
        } for p in products]
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Implement sticky header JavaScript — 2.5h
2. Create mobile navigation drawer — 3h
3. Add navigation analytics tracking — 1.5h
4. Test navigation performance — 1h

```javascript
// Sticky Header Implementation
class SmartDairyStickyHeader {
    constructor() {
        this.header = document.querySelector('.sd-header');
        this.navbar = document.querySelector('.sd-navbar');
        this.topbar = document.querySelector('.sd-topbar');
        this.scrollThreshold = this.topbar ? this.topbar.offsetHeight : 0;
        this.lastScroll = 0;
        this.ticking = false;

        this.init();
    }

    init() {
        window.addEventListener('scroll', () => this.onScroll(), { passive: true });
        this.checkScroll();
    }

    onScroll() {
        if (!this.ticking) {
            window.requestAnimationFrame(() => {
                this.checkScroll();
                this.ticking = false;
            });
            this.ticking = true;
        }
    }

    checkScroll() {
        const currentScroll = window.pageYOffset;

        if (currentScroll > this.scrollThreshold) {
            this.navbar.classList.add('sd-navbar-sticky');
            document.body.style.paddingTop = `${this.navbar.offsetHeight}px`;

            // Hide on scroll down, show on scroll up
            if (currentScroll > this.lastScroll && currentScroll > 200) {
                this.navbar.classList.add('sd-navbar-hidden');
            } else {
                this.navbar.classList.remove('sd-navbar-hidden');
            }
        } else {
            this.navbar.classList.remove('sd-navbar-sticky', 'sd-navbar-hidden');
            document.body.style.paddingTop = '';
        }

        this.lastScroll = currentScroll;
    }
}

// Mobile Navigation Drawer
class SmartDairyMobileNav {
    constructor() {
        this.drawer = document.querySelector('.sd-mobile-drawer');
        this.overlay = document.querySelector('.sd-mobile-overlay');
        this.openBtn = document.querySelector('.navbar-toggler');
        this.closeBtn = document.querySelector('.sd-mobile-close');
        this.body = document.body;

        this.init();
    }

    init() {
        if (this.openBtn) {
            this.openBtn.addEventListener('click', () => this.open());
        }
        if (this.closeBtn) {
            this.closeBtn.addEventListener('click', () => this.close());
        }
        if (this.overlay) {
            this.overlay.addEventListener('click', () => this.close());
        }

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') this.close();
        });

        // Handle submenu toggles
        const submenuToggles = this.drawer?.querySelectorAll('.sd-submenu-toggle');
        submenuToggles?.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                const parent = toggle.closest('.sd-mobile-nav-item');
                parent.classList.toggle('active');
            });
        });
    }

    open() {
        this.drawer?.classList.add('active');
        this.overlay?.classList.add('active');
        this.body.classList.add('nav-open');

        // Trap focus in drawer
        this.trapFocus();

        // Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'mobile_nav_open', {
                'event_category': 'navigation'
            });
        }
    }

    close() {
        this.drawer?.classList.remove('active');
        this.overlay?.classList.remove('active');
        this.body.classList.remove('nav-open');
    }

    trapFocus() {
        const focusableElements = this.drawer?.querySelectorAll(
            'a[href], button, textarea, input[type="text"], input[type="radio"], input[type="checkbox"], select'
        );

        if (focusableElements?.length) {
            focusableElements[0].focus();
        }
    }
}

// Initialize navigation
document.addEventListener('DOMContentLoaded', () => {
    new SmartDairyStickyHeader();
    new SmartDairyMobileNav();
});
```

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Create mega menu HTML/CSS — 3h
2. Style sticky header transitions — 2h
3. Create mobile navigation drawer styles — 2h
4. Test responsive navigation — 1h

```scss
// _navigation.scss - Smart Dairy Navigation Components

// Top Bar
.sd-topbar {
    background-color: var(--color-bg-dark);
    padding: var(--spacing-2) 0;
    font-size: var(--font-size-sm);

    .sd-topbar-contact {
        display: flex;
        gap: var(--spacing-6);

        a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: var(--spacing-2);

            &:hover {
                color: white;
            }
        }
    }

    .sd-topbar-social {
        display: flex;
        gap: var(--spacing-4);

        a {
            color: rgba(255, 255, 255, 0.8);

            &:hover {
                color: var(--color-primary-light);
            }
        }
    }

    .sd-lang-switcher {
        .lang-link {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            padding: 0 var(--spacing-2);
            border-right: 1px solid rgba(255, 255, 255, 0.3);

            &:last-child {
                border-right: none;
            }

            &.active,
            &:hover {
                color: white;
            }
        }
    }
}

// Main Navbar
.sd-navbar {
    background-color: var(--color-bg-primary);
    padding: var(--spacing-4) 0;
    box-shadow: var(--shadow-sm);
    transition: all var(--transition-normal);

    &.sd-navbar-sticky {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: var(--z-sticky);
        box-shadow: var(--shadow-md);
        padding: var(--spacing-3) 0;

        .navbar-brand img {
            height: 40px;
        }
    }

    &.sd-navbar-hidden {
        transform: translateY(-100%);
    }

    .navbar-brand {
        img {
            height: 50px;
            transition: height var(--transition-normal);
        }
    }

    .nav-link {
        color: var(--color-text-primary);
        font-weight: 500;
        padding: var(--spacing-3) var(--spacing-4);
        position: relative;

        &::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: var(--spacing-4);
            right: var(--spacing-4);
            height: 2px;
            background-color: var(--color-primary);
            transform: scaleX(0);
            transition: transform var(--transition-fast);
        }

        &:hover,
        &.active {
            color: var(--color-primary);

            &::after {
                transform: scaleX(1);
            }
        }
    }

    .dropdown-toggle::after {
        margin-left: var(--spacing-2);
    }
}

// Mega Menu
.sd-mega-menu {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background-color: var(--color-bg-primary);
    box-shadow: var(--shadow-lg);
    border-top: 3px solid var(--color-primary);
    padding: var(--spacing-8) 0;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all var(--transition-normal);
    z-index: var(--z-dropdown);

    &.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .mega-menu-inner {
        display: flex;
        gap: var(--spacing-8);
    }

    .mega-menu-column {
        flex: 1;

        .mega-menu-title {
            font-family: var(--font-family-headings);
            font-size: var(--font-size-lg);
            font-weight: 600;
            color: var(--color-text-primary);
            margin-bottom: var(--spacing-4);
            padding-bottom: var(--spacing-2);
            border-bottom: 2px solid var(--color-border-light);
        }

        .mega-menu-links {
            list-style: none;
            padding: 0;
            margin: 0;

            li {
                margin-bottom: var(--spacing-2);
            }

            a {
                color: var(--color-text-secondary);
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: var(--spacing-2);
                padding: var(--spacing-2) 0;
                transition: color var(--transition-fast);

                &:hover {
                    color: var(--color-primary);
                }

                .product-count {
                    font-size: var(--font-size-xs);
                    color: var(--color-text-secondary);
                    background: var(--color-bg-secondary);
                    padding: 2px 6px;
                    border-radius: var(--radius-full);
                }
            }
        }
    }

    .mega-menu-featured {
        width: 300px;
        background: var(--color-bg-secondary);
        border-radius: var(--radius-lg);
        padding: var(--spacing-6);

        .featured-title {
            font-weight: 600;
            margin-bottom: var(--spacing-4);
        }

        .featured-products {
            display: grid;
            gap: var(--spacing-4);
        }

        .featured-product {
            display: flex;
            gap: var(--spacing-3);

            img {
                width: 60px;
                height: 60px;
                object-fit: cover;
                border-radius: var(--radius-md);
            }

            .product-info {
                flex: 1;

                .product-name {
                    font-size: var(--font-size-sm);
                    font-weight: 500;
                    margin-bottom: var(--spacing-1);
                }

                .product-price {
                    font-size: var(--font-size-sm);
                    color: var(--color-primary);
                    font-weight: 600;
                }
            }
        }
    }
}

// Mobile Navigation Drawer
.sd-mobile-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    opacity: 0;
    visibility: hidden;
    transition: all var(--transition-normal);
    z-index: var(--z-modal-backdrop);

    &.active {
        opacity: 1;
        visibility: visible;
    }
}

.sd-mobile-drawer {
    position: fixed;
    top: 0;
    right: 0;
    width: 300px;
    max-width: 85vw;
    height: 100vh;
    background: var(--color-bg-primary);
    transform: translateX(100%);
    transition: transform var(--transition-normal);
    z-index: var(--z-modal);
    overflow-y: auto;

    &.active {
        transform: translateX(0);
    }

    .sd-mobile-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--spacing-4) var(--spacing-6);
        border-bottom: 1px solid var(--color-border-light);

        .sd-mobile-close {
            background: none;
            border: none;
            font-size: var(--font-size-2xl);
            cursor: pointer;
            color: var(--color-text-primary);
        }
    }

    .sd-mobile-nav {
        padding: var(--spacing-4) 0;
    }

    .sd-mobile-nav-item {
        border-bottom: 1px solid var(--color-border-light);

        > a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--spacing-4) var(--spacing-6);
            color: var(--color-text-primary);
            text-decoration: none;
            font-weight: 500;
        }

        .sd-submenu-toggle {
            padding: var(--spacing-2);

            i {
                transition: transform var(--transition-fast);
            }
        }

        &.active {
            .sd-submenu-toggle i {
                transform: rotate(180deg);
            }

            .sd-mobile-submenu {
                display: block;
            }
        }
    }

    .sd-mobile-submenu {
        display: none;
        background: var(--color-bg-secondary);
        padding: var(--spacing-2) 0;

        a {
            display: block;
            padding: var(--spacing-3) var(--spacing-8);
            color: var(--color-text-secondary);
            text-decoration: none;

            &:hover {
                color: var(--color-primary);
            }
        }
    }

    .sd-mobile-cta {
        padding: var(--spacing-6);

        .btn {
            width: 100%;
        }
    }
}

// Prevent body scroll when nav is open
body.nav-open {
    overflow: hidden;
}
```

**End-of-Day 155 Deliverables:**

- [ ] Sticky header implemented and tested
- [ ] Mobile navigation drawer functional
- [ ] Mega menu for products complete
- [ ] Navigation caching implemented
- [ ] All navigation tested across devices

---

### Day 156 — CDN Integration & Image Optimization

**Objective:** Configure CloudFlare CDN for optimal static asset delivery and implement comprehensive image optimization.

*(Detailed daily breakdown continues for Days 156-160...)*

---

### Day 157 — Base Page Templates

*(Detailed tasks for base template creation)*

---

### Day 158 — Responsive Grid System

*(Detailed tasks for grid system implementation)*

---

### Day 159 — Performance Optimization

*(Detailed tasks for performance optimization)*

---

### Day 160 — Milestone Review & Testing

**Objective:** Conduct comprehensive testing, review deliverables, and prepare for Milestone 32.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Code review and documentation — 3h
2. Performance testing backend — 2.5h
3. Security review — 2.5h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Lighthouse audit and optimization — 3h
2. Cross-browser testing — 2.5h
3. CI/CD pipeline verification — 2.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Accessibility audit (WAVE) — 2.5h
2. Responsive design testing — 3h
3. Component documentation finalization — 2.5h

#### All Developers — Milestone Review (4h combined)

**Tasks:**

1. Demo website foundation to stakeholders — 1.5h
2. Review deliverables against success criteria — 1h
3. Retrospective session — 0.5h
4. Plan Milestone 32 (CMS Configuration) — 1h

**End-of-Day 160 Deliverables:**

- [ ] All components tested and documented
- [ ] Lighthouse score >85
- [ ] WCAG 2.1 AA preliminary compliance
- [ ] Stakeholder demo completed
- [ ] Milestone 31 sign-off received

---

## 4. Technical Specifications

### 4.1 Design Token Values

| Token Category | Token Name | Value |
| -------------- | ---------- | ----- |
| Primary Color | `--color-primary` | #0066CC |
| Secondary Color | `--color-secondary` | #2ECC71 |
| Font Primary | `--font-family-primary` | Inter, Noto Sans Bengali |
| Font Headings | `--font-family-headings` | Poppins, Noto Sans Bengali |
| Spacing Unit | `--spacing-unit` | 4px |
| Border Radius | `--radius-md` | 8px |
| Shadow Medium | `--shadow-md` | 0 4px 6px rgba(0,0,0,0.1) |

### 4.2 Component Inventory

| Component | Variants | Status |
| --------- | -------- | ------ |
| Buttons | Primary, Secondary, Outline, Ghost, Sizes | Planned |
| Forms | Input, Textarea, Select, Checkbox, Radio | Planned |
| Cards | Product, Blog, Team, Testimonial | Planned |
| Navigation | Header, Footer, Mobile, Mega Menu | Planned |
| Media | Image, Gallery, Lightbox, Video | Planned |

### 4.3 Browser Support Matrix

| Browser | Minimum Version | Support Level |
| ------- | --------------- | ------------- |
| Chrome | 90+ | Full |
| Firefox | 90+ | Full |
| Safari | 14+ | Full |
| Edge | 90+ | Full |
| Mobile Safari | 14+ | Full |
| Chrome Mobile | 90+ | Full |

---

## 5. Testing & Validation

### 5.1 Testing Checklist

- [ ] Unit tests for form validation
- [ ] Component visual regression tests
- [ ] Cross-browser compatibility tests
- [ ] Mobile responsiveness tests
- [ ] Accessibility audit (WAVE, axe)
- [ ] Performance audit (Lighthouse)
- [ ] Security scan (OWASP ZAP)

### 5.2 Performance Targets

| Metric | Target | Tool |
| ------ | ------ | ---- |
| Lighthouse Performance | >85 | Chrome DevTools |
| First Contentful Paint | <1.5s | Lighthouse |
| Largest Contentful Paint | <2.5s | Lighthouse |
| Time to Interactive | <3.5s | Lighthouse |
| Total Blocking Time | <200ms | Lighthouse |
| Cumulative Layout Shift | <0.1 | Lighthouse |

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
| ---- | ----------- | ------ | ---------- |
| Brand assets delayed | Medium | High | Use placeholder assets, parallel work |
| CDN configuration issues | Low | Medium | Local fallback, manual cache |
| Browser compatibility | Low | Medium | Progressive enhancement |
| Performance targets not met | Medium | High | Early optimization, continuous monitoring |

---

## 7. Dependencies & Handoffs

### 7.1 Incoming Dependencies

| Dependency | Source | Required By |
| ---------- | ------ | ----------- |
| Phase 3 completion | Phase 3 | Day 151 |
| Brand assets | Marketing | Day 151 |
| CloudFlare account | IT | Day 151 |
| Google Analytics property | Marketing | Day 151 |

### 7.2 Outgoing Handoffs

| Deliverable | Recipient | Handoff Date |
| ----------- | --------- | ------------ |
| Design system | All developers | Day 160 |
| Component library | Frontend team | Day 160 |
| Base templates | Content team | Day 160 |
| CDN configuration | DevOps | Day 160 |

---

## Document Approval

| Role | Name | Signature | Date |
| ---- | ---- | --------- | ---- |
| **Frontend Lead (Dev 3)** | _________________ | _________________ | _______ |
| **Backend Lead (Dev 1)** | _________________ | _________________ | _______ |
| **Full-Stack (Dev 2)** | _________________ | _________________ | _______ |
| **Project Manager** | _________________ | _________________ | _______ |

---

**END OF MILESTONE 31**

**Next Milestone**: Milestone_32_CMS_Configuration.md
