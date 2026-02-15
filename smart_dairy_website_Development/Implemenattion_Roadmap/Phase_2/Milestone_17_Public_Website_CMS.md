# Milestone 17: Public Website Foundation with CMS

## Smart Dairy Digital Portal + ERP Implementation
### Phase 2, Part B: Commerce Foundation
### Days 161-170 (10-Day Sprint)

---

## Document Control

| Attribute | Value |
|-----------|-------|
| **Document ID** | SD-P2-MS17-WEB-v1.0 |
| **Version** | 1.0 |
| **Last Updated** | 2025-01-15 |
| **Status** | Draft |
| **Owner** | Dev 3 (Frontend/Mobile Lead) |
| **Reviewers** | Technical Architect, Project Manager |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Build the Smart Dairy public corporate website with CMS capabilities including company pages (About, Leadership, Farms, Sustainability), product catalog with nutritional information, store locator with Google Maps integration, blog/news management, and multi-language support (English/Bengali).

### 1.2 Objectives

| # | Objective | Priority | BRD Ref |
|---|-----------|----------|---------|
| 1 | Build corporate website structure | Critical | FR-WEB-001 |
| 2 | Create About Us, Leadership, Company pages | Critical | FR-WEB-002 |
| 3 | Implement Farms showcase section | High | FR-WEB-003 |
| 4 | Build product catalog with nutrition info | Critical | FR-WEB-004 |
| 5 | Implement store/dealer locator | High | FR-WEB-005 |
| 6 | Create blog/news management system | Medium | FR-WEB-006 |
| 7 | Implement multi-language (EN/BN) | Critical | FR-WEB-007 |
| 8 | Build contact/inquiry forms | Medium | FR-WEB-008 |
| 9 | Implement SEO optimization | High | FR-WEB-009 |
| 10 | Create responsive mobile design | Critical | FR-WEB-010 |

### 1.3 Key Deliverables

| Deliverable | Owner | Day |
|-------------|-------|-----|
| Website theme and layout | Dev 3 | 161-162 |
| Home page design | Dev 3 | 162 |
| About/Company pages | Dev 3 | 163 |
| Leadership/Team section | Dev 3 | 163 |
| Farms showcase | Dev 3 | 164 |
| Product catalog | Dev 2 | 165-166 |
| Store locator with maps | Dev 2 | 167 |
| Blog/News CMS | Dev 3 | 168 |
| Multi-language implementation | Dev 1 | 169 |
| SEO & Performance | Dev 2 | 170 |

### 1.4 Prerequisites

- [x] Milestone 16: B2B Portal Foundation completed
- [x] Odoo Website module installed
- [x] Product catalog configured
- [x] Google Maps API key obtained
- [x] Bengali translation resources available

### 1.5 Success Criteria

| Criteria | Target | Measurement |
|----------|--------|-------------|
| Page load time | <3 seconds | Google PageSpeed |
| Mobile responsiveness | 100% | Device testing |
| SEO score | >90 | Lighthouse audit |
| Language coverage | 95% Bengali | Translation audit |
| Store locator accuracy | 100% | GPS verification |

---

## 2. Requirement Traceability Matrix

### 2.1 BRD Requirements

| BRD Req ID | Description | Implementation | Day |
|------------|-------------|----------------|-----|
| FR-WEB-001 | Corporate website | `smart_website` theme | 161 |
| FR-WEB-002 | Company pages | Static page templates | 163 |
| FR-WEB-003 | Farm showcase | `smart_website.farms` | 164 |
| FR-WEB-004 | Product catalog | Enhanced product pages | 165 |
| FR-WEB-005 | Store locator | Google Maps integration | 167 |
| FR-WEB-006 | Blog CMS | Odoo Blog + extensions | 168 |
| FR-WEB-007 | Multi-language | Website translations | 169 |

---

## 3. Day-by-Day Breakdown

---

### Day 161-162: Website Theme & Layout

#### Dev 3 Tasks (16h) - Theme Development

**Task 3.1: Custom Theme Structure (8h)**

```python
# smart_website/__manifest__.py
{
    'name': 'Smart Dairy Website Theme',
    'version': '19.0.1.0.0',
    'category': 'Website/Theme',
    'summary': 'Corporate Website Theme for Smart Dairy',
    'depends': [
        'website',
        'website_sale',
        'website_blog',
        'website_crm',
        'smart_crm_dairy',
    ],
    'data': [
        'views/website_templates.xml',
        'views/header_footer.xml',
        'views/homepage.xml',
        'views/about_pages.xml',
        'views/product_pages.xml',
        'views/store_locator.xml',
        'views/blog_templates.xml',
        'views/contact_pages.xml',
        'views/snippets/snippets.xml',
        'data/website_menu.xml',
        'data/website_pages.xml',
    ],
    'assets': {
        'web.assets_frontend': [
            'smart_website/static/src/scss/theme.scss',
            'smart_website/static/src/scss/components/*.scss',
            'smart_website/static/src/js/main.js',
            'smart_website/static/src/js/store_locator.js',
        ],
    },
    'images': [
        'static/description/banner.png',
        'static/description/theme_screenshot.png',
    ],
    'installable': True,
    'application': False,
    'license': 'LGPL-3',
}
```

**Task 3.2: Main Theme SCSS (8h)**

```scss
// smart_website/static/src/scss/theme.scss

// Smart Dairy Brand Colors
$sd-primary: #2c5530;      // Forest Green
$sd-secondary: #f5a623;    // Golden Yellow
$sd-accent: #4a90d9;       // Sky Blue
$sd-light: #f8f9fa;
$sd-dark: #1a1a1a;
$sd-cream: #fff8e7;

// Typography
$font-family-base: 'Noto Sans Bengali', 'Roboto', sans-serif;
$font-family-heading: 'Hind Siliguri', 'Poppins', sans-serif;

// Import Bootstrap overrides
$primary: $sd-primary;
$secondary: $sd-secondary;

// Header Styles
.smart-header {
    background: linear-gradient(135deg, $sd-primary 0%, darken($sd-primary, 10%) 100%);
    padding: 0;

    .navbar-brand {
        img {
            height: 60px;
            transition: height 0.3s ease;
        }
    }

    .navbar-nav {
        .nav-link {
            color: rgba(white, 0.9);
            font-weight: 500;
            padding: 1rem 1.25rem;
            position: relative;

            &::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 50%;
                width: 0;
                height: 3px;
                background: $sd-secondary;
                transition: all 0.3s ease;
                transform: translateX(-50%);
            }

            &:hover, &.active {
                color: white;
                &::after {
                    width: 80%;
                }
            }
        }
    }

    // Sticky header
    &.scrolled {
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);

        .navbar-brand img {
            height: 45px;
        }
    }
}

// Hero Section
.smart-hero {
    position: relative;
    min-height: 80vh;
    display: flex;
    align-items: center;
    background-size: cover;
    background-position: center;
    color: white;

    &::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            135deg,
            rgba($sd-primary, 0.85) 0%,
            rgba($sd-primary, 0.6) 100%
        );
    }

    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 700px;

        h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);

            @media (max-width: 768px) {
                font-size: 2.5rem;
            }
        }

        p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
    }

    .hero-cta {
        .btn {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            margin-right: 1rem;
            margin-bottom: 1rem;

            &.btn-primary {
                background: $sd-secondary;
                border-color: $sd-secondary;
                color: $sd-dark;

                &:hover {
                    background: darken($sd-secondary, 10%);
                }
            }

            &.btn-outline-light {
                border-width: 2px;
            }
        }
    }
}

// Product Cards
.smart-product-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;

    &:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    .product-image {
        position: relative;
        padding-top: 100%;
        background: $sd-light;

        img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-height: 80%;
            max-width: 80%;
            object-fit: contain;
        }

        .product-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: $sd-secondary;
            color: $sd-dark;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
    }

    .product-info {
        padding: 1.5rem;

        .product-category {
            color: $sd-primary;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .product-name {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: $sd-dark;

            a {
                color: inherit;
                text-decoration: none;

                &:hover {
                    color: $sd-primary;
                }
            }
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: $sd-primary;

            .currency {
                font-size: 1rem;
            }

            .old-price {
                font-size: 1rem;
                color: #999;
                text-decoration: line-through;
                margin-left: 0.5rem;
            }
        }
    }
}

// Section Styles
.smart-section {
    padding: 5rem 0;

    &.bg-light {
        background: $sd-light;
    }

    &.bg-primary {
        background: $sd-primary;
        color: white;
    }

    &.bg-cream {
        background: $sd-cream;
    }

    .section-header {
        text-align: center;
        margin-bottom: 3rem;

        .section-subtitle {
            color: $sd-secondary;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: $sd-dark;
            margin-bottom: 1rem;

            .bg-primary & {
                color: white;
            }
        }

        .section-description {
            max-width: 600px;
            margin: 0 auto;
            color: #666;
            font-size: 1.1rem;

            .bg-primary & {
                color: rgba(white, 0.8);
            }
        }
    }
}

// Farm Cards
.smart-farm-card {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    height: 400px;

    img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    &:hover img {
        transform: scale(1.1);
    }

    .farm-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 2rem;
        background: linear-gradient(
            transparent,
            rgba(0,0,0,0.8)
        );
        color: white;

        .farm-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .farm-location {
            opacity: 0.8;
            font-size: 0.9rem;

            i {
                margin-right: 0.5rem;
            }
        }

        .farm-stats {
            margin-top: 1rem;
            display: flex;
            gap: 1.5rem;

            .stat {
                .stat-value {
                    font-size: 1.5rem;
                    font-weight: 700;
                    color: $sd-secondary;
                }
                .stat-label {
                    font-size: 0.8rem;
                    opacity: 0.8;
                }
            }
        }
    }
}

// Store Locator
.store-locator {
    .map-container {
        height: 500px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .store-list {
        max-height: 500px;
        overflow-y: auto;

        .store-item {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background 0.3s ease;

            &:hover, &.active {
                background: $sd-light;
            }

            .store-name {
                font-weight: 600;
                color: $sd-dark;
                margin-bottom: 0.5rem;
            }

            .store-address {
                color: #666;
                font-size: 0.9rem;
                margin-bottom: 0.5rem;
            }

            .store-distance {
                color: $sd-primary;
                font-weight: 500;
                font-size: 0.85rem;
            }
        }
    }

    .store-search {
        margin-bottom: 1rem;

        .form-control {
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            border: 2px solid #eee;

            &:focus {
                border-color: $sd-primary;
            }
        }
    }
}

// Footer
.smart-footer {
    background: $sd-dark;
    color: rgba(white, 0.8);
    padding: 4rem 0 0;

    .footer-brand {
        margin-bottom: 1.5rem;

        img {
            height: 50px;
            filter: brightness(0) invert(1);
        }
    }

    .footer-description {
        font-size: 0.95rem;
        line-height: 1.8;
        margin-bottom: 1.5rem;
    }

    .footer-title {
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;

        li {
            margin-bottom: 0.75rem;

            a {
                color: rgba(white, 0.7);
                text-decoration: none;
                transition: color 0.3s ease;

                &:hover {
                    color: $sd-secondary;
                }
            }
        }
    }

    .footer-contact {
        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1rem;

            i {
                color: $sd-secondary;
                margin-right: 1rem;
                margin-top: 0.25rem;
            }
        }
    }

    .social-links {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;

        a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(white, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s ease;

            &:hover {
                background: $sd-secondary;
                color: $sd-dark;
            }
        }
    }

    .footer-bottom {
        border-top: 1px solid rgba(white, 0.1);
        padding: 1.5rem 0;
        margin-top: 3rem;
        text-align: center;
        font-size: 0.9rem;
    }
}

// Bengali Typography
html[lang="bn"] {
    .navbar-nav .nav-link,
    .section-title,
    .product-name,
    h1, h2, h3, h4, h5, h6 {
        font-family: $font-family-heading;
    }

    body {
        font-family: $font-family-base;
    }
}

// Responsive
@media (max-width: 991px) {
    .smart-header {
        .navbar-collapse {
            background: $sd-primary;
            padding: 1rem;
            margin-top: 1rem;
            border-radius: 10px;
        }
    }

    .smart-section {
        padding: 3rem 0;

        .section-title {
            font-size: 2rem;
        }
    }

    .smart-hero .hero-content h1 {
        font-size: 2rem;
    }
}
```

---

### Day 163: About & Company Pages

#### Dev 3 Tasks (8h) - Static Pages

**Task 3.1: About Page Template (4h)**

```xml
<!-- smart_website/views/about_pages.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- About Us Page -->
    <template id="about_us_page" name="About Us">
        <t t-call="website.layout">
            <div id="wrap" class="about-page">
                <!-- Hero Section -->
                <section class="about-hero smart-hero"
                         style="background-image: url('/smart_website/static/src/img/about-hero.jpg');">
                    <div class="container">
                        <div class="hero-content text-center mx-auto">
                            <h1 t-if="request.lang == 'bn_BD'">আমাদের সম্পর্কে</h1>
                            <h1 t-else="">About Smart Dairy</h1>
                            <p t-if="request.lang == 'bn_BD'">
                                বাংলাদেশের সবচেয়ে বিশ্বস্ত দুগ্ধ ব্র্যান্ড
                            </p>
                            <p t-else="">
                                Bangladesh's Most Trusted Dairy Brand Since 2010
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Our Story -->
                <section class="smart-section">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <img src="/smart_website/static/src/img/our-story.jpg"
                                     class="img-fluid rounded-lg shadow"
                                     alt="Our Story"/>
                            </div>
                            <div class="col-lg-6">
                                <div class="section-header text-start">
                                    <span class="section-subtitle">Our Story</span>
                                    <h2 class="section-title">
                                        From Farm to Family
                                    </h2>
                                </div>
                                <p class="lead mb-4">
                                    Smart Dairy began with a simple vision: to provide
                                    pure, nutritious dairy products to every household
                                    in Bangladesh.
                                </p>
                                <p>
                                    Founded in 2010 by a group of passionate dairy farmers
                                    and food technologists, we started with a single farm
                                    in Gazipur. Today, we work with over 5,000 farmers
                                    across 8 divisions, processing over 100,000 liters
                                    of milk daily.
                                </p>
                                <p>
                                    Our commitment to quality, from farm to table,
                                    has made us one of Bangladesh's most trusted
                                    dairy brands.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Mission, Vision, Values -->
                <section class="smart-section bg-light">
                    <div class="container">
                        <div class="section-header">
                            <span class="section-subtitle">What Drives Us</span>
                            <h2 class="section-title">Mission, Vision &amp; Values</h2>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center p-4">
                                        <div class="icon-circle bg-primary text-white mb-3 mx-auto">
                                            <i class="fa fa-bullseye fa-2x"/>
                                        </div>
                                        <h4>Our Mission</h4>
                                        <p>
                                            To deliver fresh, pure, and nutritious dairy
                                            products while empowering local farmers and
                                            promoting sustainable agriculture practices.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center p-4">
                                        <div class="icon-circle bg-secondary text-dark mb-3 mx-auto">
                                            <i class="fa fa-eye fa-2x"/>
                                        </div>
                                        <h4>Our Vision</h4>
                                        <p>
                                            To become South Asia's leading dairy company,
                                            recognized for quality, innovation, and
                                            positive impact on communities.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center p-4">
                                        <div class="icon-circle bg-accent text-white mb-3 mx-auto">
                                            <i class="fa fa-heart fa-2x"/>
                                        </div>
                                        <h4>Our Values</h4>
                                        <p>
                                            Quality First, Farmer Partnership,
                                            Customer Focus, Sustainability,
                                            and Continuous Innovation.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Key Statistics -->
                <section class="smart-section bg-primary">
                    <div class="container">
                        <div class="row text-center">
                            <div class="col-6 col-md-3 mb-4">
                                <div class="stat-item">
                                    <div class="stat-number" data-count="5000">5,000+</div>
                                    <div class="stat-label">Partner Farmers</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-4">
                                <div class="stat-item">
                                    <div class="stat-number" data-count="100000">100,000</div>
                                    <div class="stat-label">Liters Daily</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-4">
                                <div class="stat-item">
                                    <div class="stat-number" data-count="64">64</div>
                                    <div class="stat-label">Districts Served</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-4">
                                <div class="stat-item">
                                    <div class="stat-number" data-count="2000000">2M+</div>
                                    <div class="stat-label">Happy Customers</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Certifications -->
                <section class="smart-section">
                    <div class="container">
                        <div class="section-header">
                            <span class="section-subtitle">Quality Assured</span>
                            <h2 class="section-title">Our Certifications</h2>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-4 col-md-2 mb-4 text-center">
                                <img src="/smart_website/static/src/img/cert-bsti.png"
                                     class="img-fluid cert-logo" alt="BSTI"/>
                                <p class="mt-2 small">BSTI Certified</p>
                            </div>
                            <div class="col-4 col-md-2 mb-4 text-center">
                                <img src="/smart_website/static/src/img/cert-haccp.png"
                                     class="img-fluid cert-logo" alt="HACCP"/>
                                <p class="mt-2 small">HACCP</p>
                            </div>
                            <div class="col-4 col-md-2 mb-4 text-center">
                                <img src="/smart_website/static/src/img/cert-iso.png"
                                     class="img-fluid cert-logo" alt="ISO"/>
                                <p class="mt-2 small">ISO 22000</p>
                            </div>
                            <div class="col-4 col-md-2 mb-4 text-center">
                                <img src="/smart_website/static/src/img/cert-halal.png"
                                     class="img-fluid cert-logo" alt="Halal"/>
                                <p class="mt-2 small">Halal Certified</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </t>
    </template>

    <!-- Leadership Page -->
    <template id="leadership_page" name="Our Leadership">
        <t t-call="website.layout">
            <div id="wrap" class="leadership-page">
                <section class="page-header smart-hero"
                         style="min-height: 40vh; background: var(--sd-primary);">
                    <div class="container">
                        <div class="hero-content text-center mx-auto">
                            <h1>Our Leadership</h1>
                            <p>Meet the team driving Smart Dairy forward</p>
                        </div>
                    </div>
                </section>

                <section class="smart-section">
                    <div class="container">
                        <!-- Board of Directors -->
                        <div class="section-header">
                            <span class="section-subtitle">Board of Directors</span>
                            <h2 class="section-title">Guiding Our Vision</h2>
                        </div>
                        <div class="row">
                            <t t-foreach="leaders" t-as="leader">
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="team-card">
                                        <div class="team-image">
                                            <img t-att-src="leader.image_url"
                                                 t-att-alt="leader.name"/>
                                        </div>
                                        <div class="team-info">
                                            <h4 class="team-name">
                                                <t t-esc="leader.name"/>
                                            </h4>
                                            <p class="team-role">
                                                <t t-esc="leader.position"/>
                                            </p>
                                            <p class="team-bio">
                                                <t t-esc="leader.bio"/>
                                            </p>
                                            <div class="team-social">
                                                <a t-if="leader.linkedin"
                                                   t-att-href="leader.linkedin">
                                                    <i class="fab fa-linkedin"/>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </t>
                        </div>
                    </div>
                </section>
            </div>
        </t>
    </template>
</odoo>
```

**Task 3.2: Leadership Data Model (4h)**

```python
# smart_website/models/leadership.py
from odoo import models, fields

class WebsiteLeadership(models.Model):
    _name = 'smart.website.leadership'
    _description = 'Website Leadership Team'
    _order = 'sequence, id'

    name = fields.Char(string='Name', required=True, translate=True)
    name_bn = fields.Char(string='Name (Bengali)')
    position = fields.Char(string='Position', required=True, translate=True)
    category = fields.Selection([
        ('board', 'Board of Directors'),
        ('executive', 'Executive Team'),
        ('management', 'Management Team'),
    ], string='Category', required=True)

    sequence = fields.Integer(string='Sequence', default=10)
    image = fields.Binary(string='Photo')
    bio = fields.Text(string='Biography', translate=True)
    linkedin = fields.Char(string='LinkedIn URL')
    active = fields.Boolean(string='Active', default=True)

    @property
    def image_url(self):
        if self.image:
            return f'/web/image/smart.website.leadership/{self.id}/image'
        return '/smart_website/static/src/img/placeholder-person.jpg'
```

---

### Day 164: Farms Showcase

#### Dev 3 Tasks (8h) - Farms Section

**Task 3.1: Farm Model & Controller (4h)**

```python
# smart_website/models/website_farm.py
from odoo import models, fields, api

class WebsiteFarm(models.Model):
    _name = 'smart.website.farm'
    _description = 'Farm Showcase for Website'
    _order = 'sequence, name'

    name = fields.Char(string='Farm Name', required=True, translate=True)
    code = fields.Char(string='Code')
    sequence = fields.Integer(default=10)
    active = fields.Boolean(default=True)

    # Location
    address = fields.Text(string='Address')
    district_id = fields.Many2one('smart.sales.territory', string='District')
    division_id = fields.Many2one('smart.sales.territory', string='Division')
    latitude = fields.Float(string='Latitude', digits=(10, 7))
    longitude = fields.Float(string='Longitude', digits=(10, 7))

    # Media
    image_main = fields.Binary(string='Main Image')
    image_gallery_ids = fields.One2many(
        'smart.website.farm.image',
        'farm_id',
        string='Gallery'
    )
    video_url = fields.Char(string='Video URL (YouTube)')

    # Statistics
    area_acres = fields.Float(string='Farm Area (Acres)')
    cattle_count = fields.Integer(string='Number of Cattle')
    daily_milk_liters = fields.Integer(string='Daily Milk Production (L)')
    farmer_count = fields.Integer(string='Partner Farmers')

    # Description
    description = fields.Html(string='Description', translate=True)
    features = fields.Text(string='Key Features', translate=True)

    # Link to farm management
    farm_mgmt_id = fields.Many2one(
        'smart.farm',
        string='Linked Farm (Management)'
    )

    @api.model
    def get_showcase_farms(self, limit=6):
        """Get farms for website showcase"""
        farms = self.search([('active', '=', True)], limit=limit)
        return [{
            'id': f.id,
            'name': f.name,
            'location': f.district_id.name if f.district_id else '',
            'image_url': f'/web/image/smart.website.farm/{f.id}/image_main',
            'cattle_count': f.cattle_count,
            'daily_milk': f.daily_milk_liters,
            'latitude': f.latitude,
            'longitude': f.longitude,
        } for f in farms]


class WebsiteFarmImage(models.Model):
    _name = 'smart.website.farm.image'
    _description = 'Farm Gallery Image'

    farm_id = fields.Many2one('smart.website.farm', required=True, ondelete='cascade')
    image = fields.Binary(string='Image', required=True)
    caption = fields.Char(string='Caption', translate=True)
    sequence = fields.Integer(default=10)
```

**Task 3.2: Farms Page Template (4h)**

```xml
<!-- smart_website/views/farms_page.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <template id="farms_page" name="Our Farms">
        <t t-call="website.layout">
            <div id="wrap" class="farms-page">
                <!-- Hero -->
                <section class="smart-hero farms-hero"
                         style="background-image: url('/smart_website/static/src/img/farms-hero.jpg');">
                    <div class="container">
                        <div class="hero-content text-center mx-auto">
                            <h1>Our Partner Farms</h1>
                            <p>
                                Discover the source of Smart Dairy's fresh,
                                pure milk from farms across Bangladesh
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Farm Map Overview -->
                <section class="smart-section">
                    <div class="container">
                        <div class="section-header">
                            <span class="section-subtitle">Nationwide Network</span>
                            <h2 class="section-title">Farms Across Bangladesh</h2>
                            <p class="section-description">
                                Our network spans 8 divisions with over 5,000 partner
                                farmers committed to quality dairy production.
                            </p>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <div id="farms-map" class="farms-map"
                                     data-farms-url="/website/farms/data"></div>
                            </div>
                            <div class="col-lg-4">
                                <div class="farm-stats-card">
                                    <h4>Farm Network Stats</h4>
                                    <div class="stat-row">
                                        <span class="stat-label">Total Farms</span>
                                        <span class="stat-value">12</span>
                                    </div>
                                    <div class="stat-row">
                                        <span class="stat-label">Partner Farmers</span>
                                        <span class="stat-value">5,000+</span>
                                    </div>
                                    <div class="stat-row">
                                        <span class="stat-label">Divisions Covered</span>
                                        <span class="stat-value">8</span>
                                    </div>
                                    <div class="stat-row">
                                        <span class="stat-label">Daily Collection</span>
                                        <span class="stat-value">100,000L</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Featured Farms Grid -->
                <section class="smart-section bg-light">
                    <div class="container">
                        <div class="section-header">
                            <span class="section-subtitle">Featured</span>
                            <h2 class="section-title">Our Premier Farms</h2>
                        </div>
                        <div class="row">
                            <t t-foreach="farms" t-as="farm">
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="smart-farm-card">
                                        <img t-att-src="'/web/image/smart.website.farm/%d/image_main' % farm.id"
                                             t-att-alt="farm.name"/>
                                        <div class="farm-overlay">
                                            <h3 class="farm-name">
                                                <t t-esc="farm.name"/>
                                            </h3>
                                            <p class="farm-location">
                                                <i class="fa fa-map-marker-alt"/>
                                                <t t-esc="farm.district_id.name"/>
                                            </p>
                                            <div class="farm-stats">
                                                <div class="stat">
                                                    <div class="stat-value">
                                                        <t t-esc="farm.cattle_count"/>
                                                    </div>
                                                    <div class="stat-label">Cattle</div>
                                                </div>
                                                <div class="stat">
                                                    <div class="stat-value">
                                                        <t t-esc="farm.daily_milk_liters"/>L
                                                    </div>
                                                    <div class="stat-label">Daily</div>
                                                </div>
                                            </div>
                                        </div>
                                        <a t-attf-href="/farms/#{farm.id}"
                                           class="stretched-link"/>
                                    </div>
                                </div>
                            </t>
                        </div>
                    </div>
                </section>

                <!-- Quality Assurance -->
                <section class="smart-section">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <div class="section-header text-start">
                                    <span class="section-subtitle">Quality Assurance</span>
                                    <h2 class="section-title">
                                        From Farm to Your Table
                                    </h2>
                                </div>
                                <div class="quality-steps">
                                    <div class="quality-step">
                                        <div class="step-number">1</div>
                                        <div class="step-content">
                                            <h5>Collection</h5>
                                            <p>
                                                Fresh milk collected from healthy,
                                                well-fed cattle within hours of milking.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="quality-step">
                                        <div class="step-number">2</div>
                                        <div class="step-content">
                                            <h5>Testing</h5>
                                            <p>
                                                Every batch tested for fat content,
                                                SNF, adulteration, and bacterial count.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="quality-step">
                                        <div class="step-number">3</div>
                                        <div class="step-content">
                                            <h5>Processing</h5>
                                            <p>
                                                State-of-the-art pasteurization and
                                                UHT processing in hygienic facilities.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="quality-step">
                                        <div class="step-number">4</div>
                                        <div class="step-content">
                                            <h5>Cold Chain</h5>
                                            <p>
                                                Maintained at optimal temperature from
                                                processing to delivery.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <img src="/smart_website/static/src/img/quality-process.jpg"
                                     class="img-fluid rounded-lg shadow"
                                     alt="Quality Process"/>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </t>
    </template>
</odoo>
```

---

### Day 165-166: Product Catalog

#### Dev 2 Tasks (16h) - Enhanced Product Pages

**Task 2.1: Product Extension for Nutrition (8h)**

```python
# smart_website/models/product_nutrition.py
from odoo import models, fields, api

class ProductTemplateNutrition(models.Model):
    _inherit = 'product.template'

    # Nutrition Information
    nutrition_info_ids = fields.One2many(
        'smart.product.nutrition',
        'product_tmpl_id',
        string='Nutrition Information'
    )

    serving_size = fields.Char(string='Serving Size', translate=True)
    servings_per_container = fields.Integer(string='Servings Per Container')

    # Ingredients
    ingredients = fields.Text(string='Ingredients', translate=True)
    allergens = fields.Char(string='Allergens', translate=True)

    # Storage
    storage_instructions = fields.Text(
        string='Storage Instructions',
        translate=True
    )
    shelf_life_days = fields.Integer(string='Shelf Life (Days)')

    # Website Display
    is_featured = fields.Boolean(string='Featured on Website')
    website_sequence = fields.Integer(string='Website Sequence', default=10)

    # Product Badges
    badge_ids = fields.Many2many(
        'smart.product.badge',
        string='Product Badges'
    )

    # Recipe/Usage Ideas
    recipe_ids = fields.Many2many(
        'smart.website.recipe',
        string='Featured Recipes'
    )


class ProductNutrition(models.Model):
    _name = 'smart.product.nutrition'
    _description = 'Product Nutrition Information'
    _order = 'sequence, id'

    product_tmpl_id = fields.Many2one(
        'product.template',
        string='Product',
        required=True,
        ondelete='cascade'
    )

    nutrient_id = fields.Many2one(
        'smart.nutrient.type',
        string='Nutrient',
        required=True
    )

    amount = fields.Float(string='Amount', required=True)
    unit = fields.Char(related='nutrient_id.unit', readonly=True)
    daily_value_pct = fields.Float(string='% Daily Value')

    sequence = fields.Integer(default=10)


class NutrientType(models.Model):
    _name = 'smart.nutrient.type'
    _description = 'Nutrient Type'
    _order = 'sequence, name'

    name = fields.Char(string='Nutrient Name', required=True, translate=True)
    code = fields.Char(string='Code')
    unit = fields.Char(string='Unit', required=True)
    sequence = fields.Integer(default=10)

    # Recommended Daily Values
    daily_value = fields.Float(string='Recommended Daily Value')
    daily_value_unit = fields.Char(string='DV Unit')


class ProductBadge(models.Model):
    _name = 'smart.product.badge'
    _description = 'Product Badge'

    name = fields.Char(string='Badge Name', required=True, translate=True)
    code = fields.Char(string='Code')
    icon = fields.Binary(string='Icon')
    color = fields.Char(string='Color Code', default='#2c5530')
```

**Task 2.2: Product Catalog Controller (4h)**

```python
# smart_website/controllers/product_catalog.py
from odoo import http
from odoo.http import request

class ProductCatalogController(http.Controller):

    @http.route('/products', type='http', auth='public', website=True)
    def product_catalog(self, category=None, **kwargs):
        """Product catalog page"""
        domain = [
            ('website_published', '=', True),
            ('sale_ok', '=', True),
        ]

        if category:
            cat = request.env['product.public.category'].search([
                ('id', '=', int(category))
            ], limit=1)
            if cat:
                domain.append(('public_categ_ids', 'in', [cat.id]))

        products = request.env['product.template'].sudo().search(
            domain,
            order='website_sequence, name'
        )

        categories = request.env['product.public.category'].sudo().search([
            ('parent_id', '=', False)
        ])

        return request.render('smart_website.product_catalog', {
            'products': products,
            'categories': categories,
            'current_category': category,
        })

    @http.route('/products/<model("product.template"):product>',
                type='http', auth='public', website=True)
    def product_detail(self, product, **kwargs):
        """Product detail page with nutrition info"""
        if not product.website_published:
            return request.redirect('/products')

        # Get related products
        related = request.env['product.template'].sudo().search([
            ('public_categ_ids', 'in', product.public_categ_ids.ids),
            ('id', '!=', product.id),
            ('website_published', '=', True),
        ], limit=4)

        return request.render('smart_website.product_detail', {
            'product': product,
            'related_products': related,
        })
```

**Task 2.3: Product Detail Template (4h)**

```xml
<!-- smart_website/views/product_pages.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Product Catalog Grid -->
    <template id="product_catalog" name="Product Catalog">
        <t t-call="website.layout">
            <div id="wrap" class="products-page">
                <section class="page-header smart-section bg-primary">
                    <div class="container">
                        <h1 class="text-center text-white">Our Products</h1>
                        <p class="text-center text-white-50">
                            Pure, Fresh, Nutritious - Made in Bangladesh
                        </p>
                    </div>
                </section>

                <section class="smart-section">
                    <div class="container">
                        <div class="row">
                            <!-- Category Filter -->
                            <div class="col-lg-3 mb-4">
                                <div class="category-filter">
                                    <h5>Categories</h5>
                                    <ul class="category-list">
                                        <li t-att-class="'active' if not current_category else ''">
                                            <a href="/products">All Products</a>
                                        </li>
                                        <t t-foreach="categories" t-as="cat">
                                            <li t-att-class="'active' if str(cat.id) == str(current_category) else ''">
                                                <a t-attf-href="/products?category=#{cat.id}">
                                                    <t t-esc="cat.name"/>
                                                </a>
                                            </li>
                                        </t>
                                    </ul>
                                </div>
                            </div>

                            <!-- Product Grid -->
                            <div class="col-lg-9">
                                <div class="row">
                                    <t t-foreach="products" t-as="product">
                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <div class="smart-product-card">
                                                <div class="product-image">
                                                    <img t-att-src="'/web/image/product.template/%d/image_1024' % product.id"
                                                         t-att-alt="product.name"/>
                                                    <t t-if="product.is_featured">
                                                        <span class="product-badge">Featured</span>
                                                    </t>
                                                </div>
                                                <div class="product-info">
                                                    <p class="product-category">
                                                        <t t-esc="product.public_categ_ids[:1].name if product.public_categ_ids else 'Dairy'"/>
                                                    </p>
                                                    <h4 class="product-name">
                                                        <a t-attf-href="/products/#{product.id}">
                                                            <t t-esc="product.name"/>
                                                        </a>
                                                    </h4>
                                                    <p class="product-price">
                                                        <span class="currency">BDT</span>
                                                        <t t-esc="'%.0f' % product.list_price"/>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </t>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </t>
    </template>

    <!-- Product Detail Page -->
    <template id="product_detail" name="Product Detail">
        <t t-call="website.layout">
            <div id="wrap" class="product-detail-page">
                <section class="smart-section">
                    <div class="container">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="/">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="/products">Products</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    <t t-esc="product.name"/>
                                </li>
                            </ol>
                        </nav>

                        <div class="row">
                            <!-- Product Images -->
                            <div class="col-lg-6 mb-4">
                                <div class="product-gallery">
                                    <div class="main-image">
                                        <img t-att-src="'/web/image/product.template/%d/image_1024' % product.id"
                                             t-att-alt="product.name"
                                             class="img-fluid"/>
                                    </div>
                                    <!-- Badges -->
                                    <div class="product-badges mt-3">
                                        <t t-foreach="product.badge_ids" t-as="badge">
                                            <span class="badge rounded-pill"
                                                  t-att-style="'background-color: %s;' % badge.color">
                                                <t t-esc="badge.name"/>
                                            </span>
                                        </t>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="col-lg-6">
                                <h1 class="product-title">
                                    <t t-esc="product.name"/>
                                </h1>

                                <p class="product-price h3 text-primary mb-4">
                                    BDT <t t-esc="'%.0f' % product.list_price"/>
                                    <small class="text-muted">
                                        / <t t-esc="product.uom_id.name"/>
                                    </small>
                                </p>

                                <div class="product-description mb-4">
                                    <t t-raw="product.description_sale or product.description"/>
                                </div>

                                <!-- Quick Facts -->
                                <div class="quick-facts mb-4">
                                    <div class="row">
                                        <t t-if="product.serving_size">
                                            <div class="col-6 mb-2">
                                                <strong>Serving Size:</strong>
                                                <t t-esc="product.serving_size"/>
                                            </div>
                                        </t>
                                        <t t-if="product.shelf_life_days">
                                            <div class="col-6 mb-2">
                                                <strong>Shelf Life:</strong>
                                                <t t-esc="product.shelf_life_days"/> days
                                            </div>
                                        </t>
                                    </div>
                                </div>

                                <!-- Storage Instructions -->
                                <t t-if="product.storage_instructions">
                                    <div class="storage-info mb-4">
                                        <h6><i class="fa fa-snowflake mr-2"/>Storage</h6>
                                        <p class="small text-muted">
                                            <t t-esc="product.storage_instructions"/>
                                        </p>
                                    </div>
                                </t>

                                <!-- Find Near You -->
                                <a href="/store-locator" class="btn btn-primary btn-lg">
                                    <i class="fa fa-map-marker-alt mr-2"/>
                                    Find Near You
                                </a>
                            </div>
                        </div>

                        <!-- Nutrition Information -->
                        <div class="row mt-5">
                            <div class="col-lg-8">
                                <div class="nutrition-panel card">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">Nutrition Facts</h5>
                                    </div>
                                    <div class="card-body">
                                        <t t-if="product.serving_size">
                                            <p class="mb-3">
                                                <strong>Serving Size:</strong>
                                                <t t-esc="product.serving_size"/>
                                            </p>
                                        </t>

                                        <table class="table nutrition-table">
                                            <thead>
                                                <tr>
                                                    <th>Nutrient</th>
                                                    <th class="text-end">Amount</th>
                                                    <th class="text-end">% Daily Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <t t-foreach="product.nutrition_info_ids" t-as="nutr">
                                                    <tr>
                                                        <td>
                                                            <t t-esc="nutr.nutrient_id.name"/>
                                                        </td>
                                                        <td class="text-end">
                                                            <t t-esc="nutr.amount"/>
                                                            <t t-esc="nutr.unit"/>
                                                        </td>
                                                        <td class="text-end">
                                                            <t t-if="nutr.daily_value_pct">
                                                                <t t-esc="'%.0f' % nutr.daily_value_pct"/>%
                                                            </t>
                                                        </td>
                                                    </tr>
                                                </t>
                                            </tbody>
                                        </table>

                                        <t t-if="product.ingredients">
                                            <div class="ingredients mt-4">
                                                <h6>Ingredients</h6>
                                                <p class="small">
                                                    <t t-esc="product.ingredients"/>
                                                </p>
                                            </div>
                                        </t>

                                        <t t-if="product.allergens">
                                            <div class="allergens mt-3">
                                                <p class="small text-warning">
                                                    <i class="fa fa-exclamation-triangle mr-1"/>
                                                    <strong>Allergens:</strong>
                                                    <t t-esc="product.allergens"/>
                                                </p>
                                            </div>
                                        </t>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Related Products -->
                        <t t-if="related_products">
                            <div class="related-products mt-5">
                                <h3 class="mb-4">You May Also Like</h3>
                                <div class="row">
                                    <t t-foreach="related_products" t-as="rel">
                                        <div class="col-6 col-md-3">
                                            <div class="smart-product-card">
                                                <div class="product-image">
                                                    <img t-att-src="'/web/image/product.template/%d/image_512' % rel.id"
                                                         t-att-alt="rel.name"/>
                                                </div>
                                                <div class="product-info">
                                                    <h5 class="product-name">
                                                        <a t-attf-href="/products/#{rel.id}">
                                                            <t t-esc="rel.name"/>
                                                        </a>
                                                    </h5>
                                                    <p class="product-price">
                                                        BDT <t t-esc="'%.0f' % rel.list_price"/>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </t>
                                </div>
                            </div>
                        </t>
                    </div>
                </section>
            </div>
        </t>
    </template>
</odoo>
```

---

### Day 167: Store Locator

#### Dev 2 Tasks (8h) - Store Locator with Maps

**Task 2.1: Store/Dealer Model (4h)**

```python
# smart_website/models/store_locator.py
from odoo import models, fields, api
from math import radians, sin, cos, sqrt, atan2

class StoreLocation(models.Model):
    _name = 'smart.store.location'
    _description = 'Store/Dealer Location'
    _order = 'name'

    name = fields.Char(string='Store Name', required=True)
    code = fields.Char(string='Store Code')
    active = fields.Boolean(default=True)

    # Type
    location_type = fields.Selection([
        ('own', 'Company Store'),
        ('dealer', 'Authorized Dealer'),
        ('retailer', 'Retail Partner'),
        ('supermarket', 'Supermarket'),
    ], string='Location Type', required=True)

    # Address
    street = fields.Char(string='Street Address')
    city = fields.Char(string='City')
    district_id = fields.Many2one('smart.sales.territory', string='District')
    division_id = fields.Many2one('smart.sales.territory', string='Division')
    zip = fields.Char(string='ZIP Code')

    # Coordinates
    latitude = fields.Float(string='Latitude', digits=(10, 7))
    longitude = fields.Float(string='Longitude', digits=(10, 7))

    # Contact
    phone = fields.Char(string='Phone')
    email = fields.Char(string='Email')
    manager_name = fields.Char(string='Manager Name')

    # Hours
    opening_hours = fields.Text(string='Opening Hours')
    is_24_hours = fields.Boolean(string='Open 24 Hours')

    # Features
    has_parking = fields.Boolean(string='Parking Available')
    has_home_delivery = fields.Boolean(string='Home Delivery')
    has_card_payment = fields.Boolean(string='Card Payment')
    has_mobile_payment = fields.Boolean(string='Mobile Payment')

    # Products Available
    product_category_ids = fields.Many2many(
        'product.category',
        string='Product Categories'
    )

    # Link to Partner
    partner_id = fields.Many2one('res.partner', string='Related Partner')

    @api.model
    def search_nearby(self, lat, lng, radius_km=10, limit=20):
        """
        Find stores within radius of given coordinates.
        Uses Haversine formula for distance calculation.
        """
        stores = self.search([('active', '=', True)])
        nearby = []

        for store in stores:
            if store.latitude and store.longitude:
                distance = self._haversine_distance(
                    lat, lng, store.latitude, store.longitude
                )
                if distance <= radius_km:
                    nearby.append({
                        'id': store.id,
                        'name': store.name,
                        'type': store.location_type,
                        'address': f"{store.street or ''}, {store.city or ''}",
                        'district': store.district_id.name if store.district_id else '',
                        'phone': store.phone,
                        'latitude': store.latitude,
                        'longitude': store.longitude,
                        'distance': round(distance, 1),
                        'hours': store.opening_hours,
                        'features': {
                            'parking': store.has_parking,
                            'delivery': store.has_home_delivery,
                            'card': store.has_card_payment,
                            'mobile': store.has_mobile_payment,
                        }
                    })

        # Sort by distance
        nearby.sort(key=lambda x: x['distance'])
        return nearby[:limit]

    def _haversine_distance(self, lat1, lon1, lat2, lon2):
        """Calculate distance between two points in km"""
        R = 6371  # Earth's radius in km

        lat1, lon1, lat2, lon2 = map(radians, [lat1, lon1, lat2, lon2])

        dlat = lat2 - lat1
        dlon = lon2 - lon1

        a = sin(dlat/2)**2 + cos(lat1) * cos(lat2) * sin(dlon/2)**2
        c = 2 * atan2(sqrt(a), sqrt(1-a))

        return R * c

    @api.model
    def get_all_locations(self):
        """Get all store locations for map display"""
        stores = self.search([('active', '=', True)])
        return [{
            'id': s.id,
            'name': s.name,
            'type': s.location_type,
            'address': f"{s.street or ''}, {s.city or ''}",
            'lat': s.latitude,
            'lng': s.longitude,
            'phone': s.phone,
        } for s in stores if s.latitude and s.longitude]
```

**Task 2.2: Store Locator JavaScript (4h)**

```javascript
// smart_website/static/src/js/store_locator.js

document.addEventListener('DOMContentLoaded', function() {
    const mapContainer = document.getElementById('store-locator-map');
    if (!mapContainer) return;

    let map;
    let markers = [];
    let infoWindow;
    let userMarker;

    // Initialize map
    function initMap() {
        // Center on Bangladesh
        const bangladeshCenter = { lat: 23.6850, lng: 90.3563 };

        map = new google.maps.Map(mapContainer, {
            zoom: 7,
            center: bangladeshCenter,
            styles: getMapStyles(),
        });

        infoWindow = new google.maps.InfoWindow();

        // Load all stores
        loadStores();

        // Setup search
        setupSearch();
    }

    function loadStores() {
        fetch('/api/stores/all', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ jsonrpc: '2.0', method: 'call', params: {} })
        })
        .then(response => response.json())
        .then(data => {
            if (data.result) {
                displayStores(data.result);
            }
        });
    }

    function displayStores(stores) {
        // Clear existing markers
        markers.forEach(m => m.setMap(null));
        markers = [];

        const bounds = new google.maps.LatLngBounds();

        stores.forEach(store => {
            const position = { lat: store.lat, lng: store.lng };

            const marker = new google.maps.Marker({
                position: position,
                map: map,
                title: store.name,
                icon: getMarkerIcon(store.type),
            });

            marker.addListener('click', () => {
                showStoreInfo(store, marker);
            });

            markers.push(marker);
            bounds.extend(position);
        });

        if (stores.length > 0) {
            map.fitBounds(bounds);
        }

        // Update list
        updateStoreList(stores);
    }

    function showStoreInfo(store, marker) {
        const content = `
            <div class="store-info-window">
                <h5>${store.name}</h5>
                <p class="mb-1">${store.address}</p>
                ${store.phone ? `<p class="mb-1"><i class="fa fa-phone"></i> ${store.phone}</p>` : ''}
                ${store.distance ? `<p class="mb-1 text-primary"><strong>${store.distance} km away</strong></p>` : ''}
                <a href="https://www.google.com/maps/dir/?api=1&destination=${store.lat},${store.lng}"
                   target="_blank" class="btn btn-sm btn-primary mt-2">
                    Get Directions
                </a>
            </div>
        `;

        infoWindow.setContent(content);
        infoWindow.open(map, marker);
    }

    function getMarkerIcon(type) {
        const icons = {
            'own': '/smart_website/static/src/img/marker-store.png',
            'dealer': '/smart_website/static/src/img/marker-dealer.png',
            'retailer': '/smart_website/static/src/img/marker-retail.png',
            'supermarket': '/smart_website/static/src/img/marker-super.png',
        };
        return icons[type] || icons['dealer'];
    }

    function setupSearch() {
        const searchInput = document.getElementById('store-search');
        const searchBtn = document.getElementById('search-btn');
        const locationBtn = document.getElementById('location-btn');

        if (searchBtn) {
            searchBtn.addEventListener('click', () => {
                const query = searchInput.value;
                if (query) {
                    geocodeAndSearch(query);
                }
            });
        }

        if (locationBtn) {
            locationBtn.addEventListener('click', () => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        position => {
                            searchNearby(
                                position.coords.latitude,
                                position.coords.longitude
                            );
                        },
                        error => {
                            alert('Unable to get your location. Please search manually.');
                        }
                    );
                }
            });
        }
    }

    function geocodeAndSearch(address) {
        const geocoder = new google.maps.Geocoder();

        geocoder.geocode({ address: address + ', Bangladesh' }, (results, status) => {
            if (status === 'OK' && results[0]) {
                const location = results[0].geometry.location;
                searchNearby(location.lat(), location.lng());
            } else {
                alert('Location not found. Please try a different search.');
            }
        });
    }

    function searchNearby(lat, lng) {
        // Add user marker
        if (userMarker) userMarker.setMap(null);

        userMarker = new google.maps.Marker({
            position: { lat, lng },
            map: map,
            icon: {
                url: '/smart_website/static/src/img/marker-user.png',
                scaledSize: new google.maps.Size(40, 40),
            },
            title: 'Your Location',
        });

        // Search for nearby stores
        fetch('/api/stores/nearby', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                jsonrpc: '2.0',
                method: 'call',
                params: { lat, lng, radius_km: 20 }
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.result) {
                displayStores(data.result.map(s => ({
                    ...s,
                    lat: s.latitude,
                    lng: s.longitude
                })));

                // Center map on user location
                map.setCenter({ lat, lng });
                map.setZoom(12);
            }
        });
    }

    function updateStoreList(stores) {
        const listContainer = document.getElementById('store-list');
        if (!listContainer) return;

        listContainer.innerHTML = stores.map(store => `
            <div class="store-item" data-store-id="${store.id}">
                <div class="store-name">${store.name}</div>
                <div class="store-address">${store.address}</div>
                ${store.distance ? `<div class="store-distance">${store.distance} km away</div>` : ''}
            </div>
        `).join('');

        // Add click handlers
        listContainer.querySelectorAll('.store-item').forEach(item => {
            item.addEventListener('click', () => {
                const storeId = parseInt(item.dataset.storeId);
                const store = stores.find(s => s.id === storeId);
                if (store) {
                    map.setCenter({ lat: store.lat, lng: store.lng });
                    map.setZoom(15);

                    const marker = markers.find(m =>
                        m.getPosition().lat() === store.lat &&
                        m.getPosition().lng() === store.lng
                    );
                    if (marker) {
                        showStoreInfo(store, marker);
                    }
                }
            });
        });
    }

    function getMapStyles() {
        return [
            {
                featureType: 'poi.business',
                stylers: [{ visibility: 'off' }]
            },
            {
                featureType: 'transit',
                elementType: 'labels.icon',
                stylers: [{ visibility: 'off' }]
            }
        ];
    }

    // Initialize when Google Maps is ready
    if (typeof google !== 'undefined') {
        initMap();
    } else {
        window.initMap = initMap;
    }
});
```

---

### Day 168-169: Blog CMS & Multi-language

*(Condensed - key implementations)*

```python
# smart_website/models/blog_extension.py
class BlogPostExtension(models.Model):
    _inherit = 'blog.post'

    # Extended fields
    reading_time = fields.Integer(
        string='Reading Time (min)',
        compute='_compute_reading_time'
    )
    featured_image = fields.Binary(string='Featured Image')
    meta_description = fields.Text(string='Meta Description', translate=True)

    @api.depends('content')
    def _compute_reading_time(self):
        for post in self:
            if post.content:
                word_count = len(post.content.split())
                post.reading_time = max(1, word_count // 200)
            else:
                post.reading_time = 1
```

---

### Day 170: SEO & Performance Optimization

**SEO Configuration:**

```python
# smart_website/models/website_seo.py
class WebsiteSEO(models.Model):
    _inherit = 'website'

    # SEO Settings
    default_meta_title = fields.Char(
        string='Default Meta Title',
        default='Smart Dairy Bangladesh - Fresh, Pure, Nutritious'
    )
    default_meta_description = fields.Text(
        string='Default Meta Description',
        default='Bangladesh\'s leading dairy brand. Fresh milk, UHT, yogurt, butter, cheese from farm to table.'
    )
    google_analytics_id = fields.Char(string='Google Analytics ID')
    google_tag_manager_id = fields.Char(string='GTM Container ID')
    facebook_pixel_id = fields.Char(string='Facebook Pixel ID')

    # Structured Data
    organization_schema = fields.Text(
        string='Organization Schema',
        help='JSON-LD structured data for organization'
    )
```

---

## 4. Module Manifest

```python
# smart_website/__manifest__.py (final)
{
    'name': 'Smart Dairy Website',
    'version': '19.0.1.0.0',
    'category': 'Website',
    'summary': 'Corporate Website for Smart Dairy',
    'depends': [
        'website', 'website_sale', 'website_blog',
        'website_crm', 'smart_crm_dairy',
    ],
    'data': [
        'security/ir.model.access.csv',
        'data/nutrient_types.xml',
        'data/website_menu.xml',
        'views/website_templates.xml',
        'views/header_footer.xml',
        'views/homepage.xml',
        'views/about_pages.xml',
        'views/farms_page.xml',
        'views/product_pages.xml',
        'views/store_locator.xml',
        'views/blog_templates.xml',
        'views/contact_pages.xml',
    ],
    'assets': {
        'web.assets_frontend': [
            ('include', 'smart_website/static/src/scss/theme.scss'),
            'smart_website/static/src/js/*.js',
        ],
    },
    'license': 'LGPL-3',
}
```

---

## 5. Milestone Completion Checklist

- [ ] Website theme and branding complete
- [ ] Home page with hero section
- [ ] About Us, Leadership pages
- [ ] Farms showcase with map
- [ ] Product catalog with nutrition info
- [ ] Store locator with Google Maps
- [ ] Blog/News CMS operational
- [ ] English/Bengali language toggle
- [ ] SEO meta tags configured
- [ ] Mobile responsive design verified
- [ ] Page load time <3 seconds
- [ ] All tests passing

---

**End of Milestone 17 Documentation**
