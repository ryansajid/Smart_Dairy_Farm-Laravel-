# Milestone 4: Public Website Development

## Smart Dairy Smart Portal + ERP System Implementation

---

## Document Information

| **Attribute** | **Value** |
|---------------|-----------|
| **Milestone** | Milestone 04 |
| **Duration** | Days 31-40 (10 Working Days) |
| **Title** | Public Website Development |
| **Version** | 1.0 |
| **Status** | Draft |
| **Last Updated** | February 2026 |

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Milestone Overview and Objectives](#2-milestone-overview-and-objectives)
3. [Website Architecture and Design (Day 31-32)](#3-website-architecture-and-design-day-31-32)
4. [Core Page Development (Day 33-36)](#4-core-page-development-day-33-36)
5. [Multi-language Implementation (Day 37-38)](#5-multi-language-implementation-day-37-38)
6. [SEO and Analytics Integration (Day 39)](#6-seo-and-analytics-integration-day-39)
7. [Milestone Review and Sign-off (Day 40)](#7-milestone-review-and-sign-off-day-40)
8. [Technical Specifications](#8-technical-specifications)
9. [Template Code Reference](#9-template-code-reference)
10. [Testing and Quality Assurance](#10-testing-and-quality-assurance)
11. [Deployment Guide](#11-deployment-guide)
12. [Appendices](#12-appendices)

---

## 1. Executive Summary

The Public Website Development milestone represents a critical phase in the Smart Dairy Smart Portal + ERP System implementation. This milestone focuses on creating a professional, responsive, and feature-rich public-facing website that serves as the primary digital touchpoint for customers, partners, and stakeholders.

### 1.1 Purpose and Scope

The public website serves multiple strategic purposes:
- **Brand Presence**: Establish a professional online identity for Smart Dairy
- **Product Showcase**: Display the complete range of dairy products with detailed information
- **Customer Engagement**: Provide interactive features for customer interaction
- **Information Hub**: Serve as a central repository for company news, updates, and educational content
- **Lead Generation**: Capture potential customer inquiries and convert visitors into leads
- **Career Portal**: Attract talent through a dedicated careers section

### 1.2 Key Deliverables

Upon completion of this milestone, the following deliverables will be available:

1. **Fully Functional Public Website**: Complete website with all required pages and features
2. **Mobile-Responsive Design**: Optimized viewing experience across all device types
3. **Multi-Language Support**: English and Bengali language implementations
4. **SEO Optimization**: Search engine optimized content and technical structure
5. **Analytics Integration**: Google Analytics 4 tracking and reporting
6. **Content Management System**: Training documentation for content administrators
7. **Technical Documentation**: Comprehensive code documentation and deployment guides

### 1.3 Success Criteria

| **Criteria** | **Target** | **Measurement** |
|--------------|------------|-----------------|
| Page Load Speed | < 3 seconds | Google PageSpeed Insights |
| Mobile Responsiveness | 100% compatible | Multiple device testing |
| SEO Score | > 90/100 | SEO audit tools |
| Accessibility | WCAG 2.1 AA | Accessibility testing tools |
| Cross-browser Support | Last 2 versions | Browser compatibility matrix |
| Uptime | 99.9% | Monitoring tools |

---

## 2. Milestone Overview and Objectives

### 2.1 Milestone Timeline

```
Day 31-32: Website Architecture and Design
Day 33-36: Core Page Development
Day 37-38: Multi-language Implementation
Day 39: SEO and Analytics Integration
Day 40: Milestone Review and Sign-off
```

### 2.2 Resource Allocation

| **Role** | **Responsibilities** | **Allocation** |
|----------|---------------------|----------------|
| Dev-Lead | Architecture, Backend Integration, Performance | 100% |
| Dev-1 | Backend Development, API, Security | 100% |
| Dev-2 | Frontend, UI/UX, Localization | 100% |
| QA Engineer | Testing, Quality Assurance | 50% |
| Content Manager | Content strategy, SEO guidance | 25% |

### 2.3 Primary Objectives

#### 2.3.1 Objective 1: Professional Website Presence
- Create a visually appealing and professional website that reflects Smart Dairy's brand identity
- Implement consistent design language across all pages
- Ensure high-quality user experience through intuitive navigation

#### 2.3.2 Objective 2: Technical Excellence
- Build on solid technical foundation using Odoo Website Builder and QWeb templates
- Implement modern frontend technologies (Bootstrap 5, Custom CSS/SCSS)
- Ensure optimal performance through caching and optimization techniques

#### 2.3.3 Objective 3: Content Management
- Develop flexible content management capabilities
- Enable non-technical staff to update website content
- Implement dynamic content modules for product showcasing

#### 2.3.4 Objective 4: Global Reach
- Implement multi-language support for English and Bengali
- Ensure proper localization of all UI elements and content
- Support RTL (Right-to-Left) considerations where applicable

#### 2.3.5 Objective 5: Search Engine Visibility
- Implement comprehensive SEO strategies
- Configure meta tags, structured data, and sitemaps
- Integrate analytics for tracking and optimization

### 2.4 Risk Assessment

| **Risk** | **Impact** | **Probability** | **Mitigation** |
|----------|------------|-----------------|----------------|
| Scope creep | High | Medium | Strict change control process |
| Performance issues | High | Medium | Early performance testing |
| Browser compatibility | Medium | Low | Cross-browser testing matrix |
| Content delays | Medium | Medium | Content templates and placeholders |
| Localization complexity | Medium | Medium | Early prototype testing |

---

## 3. Website Architecture and Design (Day 31-32)

### 3.1 Architecture Overview

The Smart Dairy public website follows a modular architecture built on the Odoo Website Builder framework. This architecture leverages Odoo's QWeb templating engine for server-side rendering while incorporating modern frontend technologies for enhanced user experience.

#### 3.1.1 System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           CLIENT LAYER                                       │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐         │
│  │   Desktop   │  │   Tablet    │  │   Mobile    │  │   Web App   │         │
│  │   Browser   │  │   Browser   │  │   Browser   │  │   (PWA)     │         │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘         │
└─────────┼────────────────┼────────────────┼────────────────┼────────────────┘
          │                │                │                │
          └────────────────┴────────────────┴────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                         PRESENTATION LAYER                                   │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                     Odoo Website Builder                               │  │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐   │  │
│  │  │   QWeb      │  │  Bootstrap  │  │   Custom    │  │  JavaScript │   │  │
│  │  │  Templates  │  │     5       │  │  CSS/SCSS   │  │  Components │   │  │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘   │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                         APPLICATION LAYER                                    │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐         │
│  │   Website   │  │    Blog     │  │   Portal    │  │    Mail     │         │
│  │   Module    │  │   Module    │  │   Module    │  │   Module    │         │
│  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘         │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐         │
│  │   Custom    │  │  Product    │  │   Career    │  │   Contact   │         │
│  │  Controllers│  │  Showcase   │  │   Module    │  │   Module    │         │
│  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘         │
└─────────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                           DATA LAYER                                         │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐         │
│  │  PostgreSQL │  │   Redis     │  │   Static    │  │    CDN      │         │
│  │  Database   │  │   Cache     │  │    Files    │  │   (Future)  │         │
│  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘         │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### 3.1.2 Technology Stack

| **Layer** | **Technology** | **Version** | **Purpose** |
|-----------|----------------|-------------|-------------|
| Backend Framework | Odoo | 16.0/17.0 | Core platform and CMS |
| Template Engine | QWeb | Built-in | Server-side templating |
| CSS Framework | Bootstrap | 5.3.x | Responsive grid and components |
| CSS Preprocessor | SCSS | 3.x | Advanced styling capabilities |
| JavaScript | Vanilla JS/jQuery | ES6+ | Interactivity and DOM manipulation |
| Icons | Font Awesome | 6.x | Icon library |
| Fonts | Google Fonts | Latest | Typography |
| Analytics | Google Analytics 4 | Latest | User tracking and insights |
| SEO | Structured Data | Schema.org | Rich search results |

### 3.2 Navigation Design

#### 3.2.1 Information Architecture

```
Smart Dairy Public Website
│
├── Home
│   ├── Hero Section
│   ├── Featured Products
│   ├── Company Highlights
│   └── Latest News
│
├── About Us
│   ├── Company Profile
│   ├── Mission & Vision
│   ├── Core Values
│   ├── Leadership Team
│   └── History & Milestones
│
├── Products
│   ├── Fresh Milk
│   ├── Yogurt & Curd
│   ├── Cheese
│   ├── Butter & Ghee
│   ├── Flavored Milk
│   └── Packaged Products
│
├── Farm Tour
│   ├── Sustainability
│   ├── Quality Assurance
│   ├── Our Farms
│   └── Production Process
│
├── News & Blog
│   ├── Company News
│   ├── Industry Insights
│   ├── Health Tips
│   └── Recipes
│
├── Careers
│   ├── Current Openings
│   ├── Life at Smart Dairy
│   ├── Benefits
│   └── Application Process
│
├── Contact Us
│   ├── Contact Form
│   ├── Office Locations
│   ├── Distribution Centers
│   └── Customer Support
│
└── Legal
    ├── Privacy Policy
    ├── Terms of Service
    └── Cookie Policy
```

#### 3.2.2 Navigation Components

**Primary Navigation (Header)**

The primary navigation provides quick access to main sections with a mega-menu for Products:

```xml
<!-- Primary Navigation Structure -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="/">
            <img src="/smart_dairy/static/src/img/logo.png" 
                 alt="Smart Dairy" 
                 height="50"/>
        </a>
        
        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#primaryNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation Items -->
        <div class="collapse navbar-collapse" id="primaryNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about-us">About Us</a>
                </li>
                <!-- Mega Menu for Products -->
                <li class="nav-item dropdown has-megamenu">
                    <a class="nav-link dropdown-toggle" href="#" 
                       data-bs-toggle="dropdown">Products</a>
                    <div class="dropdown-menu megamenu">
                        <!-- Mega menu content -->
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/farm-tour">Farm Tour</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/blog">News &amp; Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/careers">Careers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact-us">Contact Us</a>
                </li>
            </ul>
            
            <!-- Language Selector -->
            <div class="language-selector ms-3">
                <select class="form-select form-select-sm" id="languageSwitcher">
                    <option value="en_US">English</option>
                    <option value="bn_BD">বাংলা</option>
                </select>
            </div>
        </div>
    </div>
</nav>
```

**Breadcrumb Navigation**

Breadcrumbs provide hierarchical navigation and improve SEO:

```xml
<!-- Breadcrumb Component -->
<nav aria-label="breadcrumb" class="bg-light py-2">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="/">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="/products">Products</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Fresh Milk
            </li>
        </ol>
    </div>
</nav>
```

**Footer Navigation**

```xml
<!-- Footer Structure -->
<footer class="site-footer bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <!-- Company Info -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5>Smart Dairy</h5>
                <p>Pure. Fresh. Natural. Delivering quality dairy products 
                   straight from our farms to your doorstep.</p>
                <div class="social-links">
                    <a href="#" class="me-2"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="me-2"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h6>Quick Links</h6>
                <ul class="list-unstyled">
                    <li><a href="/about-us">About Us</a></li>
                    <li><a href="/products">Products</a></li>
                    <li><a href="/farm-tour">Farm Tour</a></li>
                    <li><a href="/careers">Careers</a></li>
                </ul>
            </div>
            
            <!-- Products -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h6>Our Products</h6>
                <ul class="list-unstyled">
                    <li><a href="/products/milk">Fresh Milk</a></li>
                    <li><a href="/products/yogurt">Yogurt</a></li>
                    <li><a href="/products/cheese">Cheese</a></li>
                    <li><a href="/products/butter">Butter</a></li>
                </ul>
            </div>
            
            <!-- Support -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h6>Support</h6>
                <ul class="list-unstyled">
                    <li><a href="/faq">FAQ</a></li>
                    <li><a href="/contact-us">Contact Us</a></li>
                    <li><a href="/privacy">Privacy Policy</a></li>
                    <li><a href="/terms">Terms of Service</a></li>
                </ul>
            </div>
            
            <!-- Contact -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h6>Contact</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-phone me-2"></i>+880 1XXX-XXXXXX</li>
                    <li><i class="fas fa-envelope me-2"></i>info@smartdairy.com</li>
                    <li><i class="fas fa-map-marker-alt me-2"></i>Dhaka, Bangladesh</li>
                </ul>
            </div>
        </div>
        
        <hr class="my-4"/>
        
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0">&amp;copy; 2026 Smart Dairy. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0">Designed with <i class="fas fa-heart text-danger"></i> in Bangladesh</p>
            </div>
        </div>
    </div>
</footer>
```

### 3.3 Design System

#### 3.3.1 Color Palette

```scss
// Smart Dairy Brand Colors
// File: static/src/scss/_variables.scss

// Primary Colors
$primary-color: #0066CC;           // Brand Blue
$primary-dark: #004C99;            // Dark Blue
$primary-light: #4D94DB;           // Light Blue

// Secondary Colors
$secondary-color: #FF6B35;         // Accent Orange
$secondary-dark: #CC5529;          // Dark Orange
$secondary-light: #FF9A73;         // Light Orange

// Neutral Colors
$white: #FFFFFF;
$gray-100: #F8F9FA;
$gray-200: #E9ECEF;
$gray-300: #DEE2E6;
$gray-400: #CED4DA;
$gray-500: #ADB5BD;
$gray-600: #6C757D;
$gray-700: #495057;
$gray-800: #343A40;
$gray-900: #212529;
$black: #000000;

// Semantic Colors
$success: #28A745;
$info: #17A2B8;
$warning: #FFC107;
$error: #DC3545;

// Background Colors
$bg-light: #F5F7FA;
$bg-dark: #1A1A2E;

// Overlay Colors
$overlay-light: rgba(255, 255, 255, 0.8);
$overlay-dark: rgba(0, 0, 0, 0.5);
```

#### 3.3.2 Typography

```scss
// Typography Settings
// File: static/src/scss/_typography.scss

// Font Imports
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap');

// Font Families
$font-primary: 'Poppins', sans-serif;
$font-bengali: 'Noto Sans Bengali', sans-serif;

// Font Sizes
$font-size-base: 1rem;              // 16px
$font-size-xs: 0.75rem;             // 12px
$font-size-sm: 0.875rem;            // 14px
$font-size-lg: 1.125rem;            // 18px
$font-size-xl: 1.25rem;             // 20px

// Heading Sizes
$h1-font-size: 3rem;                // 48px
$h2-font-size: 2.5rem;              // 40px
$h3-font-size: 2rem;                // 32px
$h4-font-size: 1.5rem;              // 24px
$h5-font-size: 1.25rem;             // 20px
$h6-font-size: 1rem;                // 16px

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
$line-height-loose: 2;

// Letter Spacing
$letter-spacing-tight: -0.025em;
$letter-spacing-normal: 0;
$letter-spacing-wide: 0.025em;
$letter-spacing-wider: 0.05em;
```

#### 3.3.3 Spacing System

```scss
// Spacing Scale
// File: static/src/scss/_spacing.scss

// Base unit: 4px
$spacing-unit: 0.25rem;  // 4px

// Spacing Scale
$space-0: 0;
$space-1: $spacing-unit;              // 4px
$space-2: $spacing-unit * 2;          // 8px
$space-3: $spacing-unit * 4;          // 16px
$space-4: $spacing-unit * 6;          // 24px
$space-5: $spacing-unit * 8;          // 32px
$space-6: $spacing-unit * 12;         // 48px
$space-7: $spacing-unit * 16;         // 64px
$space-8: $spacing-unit * 24;         // 96px
$space-9: $spacing-unit * 32;         // 128px
$space-10: $spacing-unit * 40;        // 160px

// Section Padding
$section-padding-y: $space-8;
$section-padding-x: $space-4;

// Container Max Widths
$container-max-widths: (
    sm: 540px,
    md: 720px,
    lg: 960px,
    xl: 1140px,
    xxl: 1320px
);
```

#### 3.3.4 Component Library

**Buttons**

```scss
// Button Components
// File: static/src/scss/_buttons.scss

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: $space-3 $space-5;
    font-family: $font-primary;
    font-size: $font-size-base;
    font-weight: $font-weight-medium;
    line-height: $line-height-normal;
    text-align: center;
    text-decoration: none;
    white-space: nowrap;
    vertical-align: middle;
    cursor: pointer;
    user-select: none;
    border: 2px solid transparent;
    border-radius: 8px;
    transition: all 0.3s ease;
    
    &:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    &:focus {
        outline: 0;
        box-shadow: 0 0 0 3px rgba($primary-color, 0.3);
    }
    
    &:disabled {
        opacity: 0.65;
        cursor: not-allowed;
        transform: none;
    }
}

// Button Variants
.btn-primary {
    color: $white;
    background-color: $primary-color;
    border-color: $primary-color;
    
    &:hover {
        background-color: $primary-dark;
        border-color: $primary-dark;
    }
}

.btn-secondary {
    color: $white;
    background-color: $secondary-color;
    border-color: $secondary-color;
    
    &:hover {
        background-color: $secondary-dark;
        border-color: $secondary-dark;
    }
}

.btn-outline-primary {
    color: $primary-color;
    background-color: transparent;
    border-color: $primary-color;
    
    &:hover {
        color: $white;
        background-color: $primary-color;
    }
}

// Button Sizes
.btn-sm {
    padding: $space-2 $space-3;
    font-size: $font-size-sm;
    border-radius: 6px;
}

.btn-lg {
    padding: $space-3 $space-6;
    font-size: $font-size-lg;
    border-radius: 10px;
}

// Button with Icon
.btn-icon {
    i {
        margin-right: $space-2;
    }
    
    &.btn-icon-right i {
        margin-right: 0;
        margin-left: $space-2;
    }
}
```

**Cards**

```scss
// Card Components
// File: static/src/scss/_cards.scss

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    background-color: $white;
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    overflow: hidden;
    
    &:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        transform: translateY(-4px);
    }
}

.card-image {
    position: relative;
    overflow: hidden;
    
    img {
        width: 100%;
        height: auto;
        transition: transform 0.5s ease;
    }
    
    .card:hover & img {
        transform: scale(1.05);
    }
}

.card-body {
    flex: 1 1 auto;
    padding: $space-4;
}

.card-title {
    margin-bottom: $space-2;
    font-size: $h5-font-size;
    font-weight: $font-weight-semibold;
    color: $gray-900;
}

.card-text {
    margin-bottom: $space-3;
    color: $gray-600;
    line-height: $line-height-relaxed;
}

.card-footer {
    padding: $space-3 $space-4;
    background-color: transparent;
    border-top: 1px solid $gray-200;
}

// Product Card
.card-product {
    .card-image {
        height: 240px;
        
        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }
    
    .product-price {
        font-size: $h4-font-size;
        font-weight: $font-weight-bold;
        color: $primary-color;
    }
    
    .product-badge {
        position: absolute;
        top: $space-3;
        left: $space-3;
        padding: $space-1 $space-2;
        font-size: $font-size-xs;
        font-weight: $font-weight-semibold;
        text-transform: uppercase;
        border-radius: 4px;
        
        &.badge-new {
            background-color: $success;
            color: $white;
        }
        
        &.badge-featured {
            background-color: $secondary-color;
            color: $white;
        }
    }
}

// Blog Card
.card-blog {
    .card-image {
        height: 200px;
    }
    
    .blog-meta {
        display: flex;
        align-items: center;
        margin-bottom: $space-2;
        font-size: $font-size-sm;
        color: $gray-500;
        
        .meta-item {
            display: flex;
            align-items: center;
            margin-right: $space-3;
            
            i {
                margin-right: $space-1;
            }
        }
    }
}
```

### 3.4 Day 31-32 Tasks Breakdown

#### 3.4.1 Dev-Lead Tasks

**Day 31: Website Architecture Planning**

| **Time** | **Task** | **Deliverable** |
|----------|----------|-----------------|
| 09:00-12:00 | Review business requirements | Requirements documentation |
| 13:00-15:00 | Design system architecture | Architecture diagram |
| 15:00-17:00 | Define module structure | Module structure document |
| 17:00-18:00 | Resource allocation | Task assignment matrix |

**Day 32: Technical Specifications**

| **Time** | **Task** | **Deliverable** |
|----------|----------|-----------------|
| 09:00-12:00 | API design for dynamic content | API specification document |
| 13:00-15:00 | Database schema for website | Schema design document |
| 15:00-17:00 | Caching strategy | Caching architecture |
| 17:00-18:00 | Review and approval | Approved specifications |

#### 3.4.2 Dev-1 Tasks

**Day 31-32: Page Controller Development**

```python
# controllers/main.py
# Smart Dairy Website Controllers

from odoo import http
from odoo.http import request
from odoo.addons.website.controllers.main import Website
import json
import logging

_logger = logging.getLogger(__name__)


class SmartDairyWebsite(Website):
    """Extended website controller for Smart Dairy specific functionality."""
    
    @http.route('/products', type='http', auth='public', website=True)
    def products_list(self, category=None, search='', **kwargs):
        """
        Display products list page with filtering and search.
        
        :param category: Product category filter
        :param search: Search query string
        :return: Rendered product list template
        """
        ProductTemplate = request.env['product.template']
        ProductCategory = request.env['product.public.category']
        
        # Base domain for published products
        domain = [('is_published', '=', True), ('website_published', '=', True)]
        
        # Apply category filter
        if category:
            try:
                category_id = int(category)
                domain.append(('public_categ_ids', 'in', [category_id]))
            except (ValueError, TypeError):
                _logger.warning(f"Invalid category ID: {category}")
        
        # Apply search filter
        if search:
            domain += [
                '|', '|',
                ('name', 'ilike', search),
                ('description_sale', 'ilike', search),
                ('description', 'ilike', search)
            ]
        
        # Get products with pagination
        page = int(kwargs.get('page', 1))
        products_per_page = 12
        offset = (page - 1) * products_per_page
        
        products = ProductTemplate.sudo().search(
            domain, 
            limit=products_per_page, 
            offset=offset,
            order='website_sequence ASC, name ASC'
        )
        
        product_count = ProductTemplate.sudo().search_count(domain)
        page_count = (product_count + products_per_page - 1) // products_per_page
        
        # Get all categories for filter sidebar
        categories = ProductCategory.sudo().search([
            ('parent_id', '=', False)
        ])
        
        values = {
            'products': products,
            'categories': categories,
            'current_category': int(category) if category else None,
            'search': search,
            'page': page,
            'page_count': page_count,
            'product_count': product_count,
        }
        
        return request.render('smart_dairy.products_list', values)
    
    @http.route('/products/<model("product.template"):product>', 
                type='http', auth='public', website=True)
    def product_detail(self, product, **kwargs):
        """
        Display product detail page.
        
        :param product: Product template record
        :return: Rendered product detail template
        """
        if not product.exists() or not product.is_published:
            return request.not_found()
        
        # Get related products
        related_products = request.env['product.template'].sudo().search([
            ('is_published', '=', True),
            ('id', '!=', product.id),
            ('public_categ_ids', 'in', product.public_categ_ids.ids)
        ], limit=4)
        
        values = {
            'product': product,
            'related_products': related_products,
            'main_object': product,
        }
        
        return request.render('smart_dairy.product_detail', values)
    
    @http.route('/about-us', type='http', auth='public', website=True)
    def about_us(self, **kwargs):
        """Display About Us page with company information."""
        # Get team members
        team_members = request.env['hr.employee'].sudo().search([
            ('is_published', '=', True)
        ], order='sequence ASC')
        
        # Get company milestones
        milestones = request.env['smart_dairy.milestone'].sudo().search([
            ('is_active', '=', True)
        ], order='date ASC')
        
        values = {
            'team_members': team_members,
            'milestones': milestones,
        }
        
        return request.render('smart_dairy.about_us', values)
    
    @http.route('/farm-tour', type='http', auth='public', website=True)
    def farm_tour(self, **kwargs):
        """Display Farm Tour and Sustainability page."""
        # Get farm locations
        farms = request.env['smart_dairy.farm'].sudo().search([
            ('is_active', '=', True)
        ])
        
        # Get sustainability initiatives
        initiatives = request.env['smart_dairy.sustainability'].sudo().search([
            ('is_active', '=', True)
        ])
        
        values = {
            'farms': farms,
            'initiatives': initiatives,
        }
        
        return request.render('smart_dairy.farm_tour', values)
    
    @http.route('/careers', type='http', auth='public', website=True)
    def careers(self, department=None, **kwargs):
        """Display Careers page with job openings."""
        domain = [('is_published', '=', True), ('state', '=', 'recruit')]
        
        if department:
            try:
                department_id = int(department)
                domain.append(('department_id', '=', department_id))
            except (ValueError, TypeError):
                pass
        
        jobs = request.env['hr.job'].sudo().search(domain, order='create_date DESC')
        departments = request.env['hr.department'].sudo().search([])
        
        values = {
            'jobs': jobs,
            'departments': departments,
            'current_department': int(department) if department else None,
        }
        
        return request.render('smart_dairy.careers', values)
    
    @http.route('/careers/apply/<model("hr.job"):job>', 
                type='http', auth='public', website=True, methods=['GET', 'POST'])
    def job_apply(self, job, **kwargs):
        """Handle job application submission."""
        if request.httprequest.method == 'POST':
            return self._handle_job_application(job, kwargs)
        
        values = {
            'job': job,
        }
        return request.render('smart_dairy.job_application_form', values)
    
    def _handle_job_application(self, job, form_data):
        """Process job application form submission."""
        try:
            # Create applicant record
            applicant_vals = {
                'job_id': job.id,
                'name': form_data.get('name'),
                'email_from': form_data.get('email'),
                'partner_phone': form_data.get('phone'),
                'description': form_data.get('cover_letter'),
                'type_id': request.env.ref('hr_recruitment.degree_graduate').id,
            }
            
            applicant = request.env['hr.applicant'].sudo().create(applicant_vals)
            
            # Handle resume upload
            if 'resume' in request.httprequest.files:
                file = request.httprequest.files['resume']
                if file.filename:
                    attachment = request.env['ir.attachment'].sudo().create({
                        'name': file.filename,
                        'datas': base64.b64encode(file.read()),
                        'res_model': 'hr.applicant',
                        'res_id': applicant.id,
                    })
            
            return request.render('smart_dairy.job_application_success', {
                'job': job
            })
            
        except Exception as e:
            _logger.error(f"Job application error: {str(e)}")
            return request.render('smart_dairy.job_application_error', {
                'error_message': str(e)
            })
    
    @http.route('/contact-us', type='http', auth='public', website=True, methods=['GET', 'POST'])
    def contact_us(self, **kwargs):
        """Handle contact form and display contact page."""
        if request.httprequest.method == 'POST':
            return self._handle_contact_form(kwargs)
        
        # Get office locations
        offices = request.env['smart_dairy.office'].sudo().search([
            ('is_active', '=', True)
        ])
        
        values = {
            'offices': offices,
        }
        
        return request.render('smart_dairy.contact_us', values)
    
    def _handle_contact_form(self, form_data):
        """Process contact form submission."""
        try:
            # Create CRM lead from contact form
            lead_vals = {
                'name': f"Website Contact: {form_data.get('subject', 'General Inquiry')}",
                'contact_name': form_data.get('name'),
                'email_from': form_data.get('email'),
                'phone': form_data.get('phone'),
                'description': form_data.get('message'),
                'type': 'opportunity',
                'source_id': request.env.ref('smart_dairy.source_website').id,
            }
            
            lead = request.env['crm.lead'].sudo().create(lead_vals)
            
            # Send notification email
            template = request.env.ref('smart_dairy.email_template_contact_notification')
            if template:
                template.sudo().send_mail(lead.id, force_send=True)
            
            return request.render('smart_dairy.contact_success')
            
        except Exception as e:
            _logger.error(f"Contact form error: {str(e)}")
            return request.render('smart_dairy.contact_error')
    
    @http.route('/faq', type='http', auth='public', website=True)
    def faq(self, category=None, **kwargs):
        """Display FAQ page with categorized questions."""
        FAQ = request.env['smart_dairy.faq']
        FAQCategory = request.env['smart_dairy.faq.category']
        
        domain = [('is_published', '=', True)]
        
        if category:
            try:
                category_id = int(category)
                domain.append(('category_id', '=', category_id))
            except (ValueError, TypeError):
                pass
        
        faqs = FAQ.sudo().search(domain, order='sequence ASC')
        categories = FAQCategory.sudo().search([('is_active', '=', True)])
        
        values = {
            'faqs': faqs,
            'categories': categories,
            'current_category': int(category) if category else None,
        }
        
        return request.render('smart_dairy.faq', values)
```

#### 3.4.3 Dev-2 Tasks

**Day 31-32: Theme Design and CSS Framework Setup**

```scss
// Main SCSS Entry Point
// File: static/src/scss/smart_dairy.scss

// 1. Import Dependencies
@import 'variables';
@import 'mixins';
@import 'typography';
@import 'spacing';

// 2. Bootstrap Customization
@import 'bootstrap_overrides';

// 3. Component Styles
@import 'buttons';
@import 'cards';
@import 'forms';
@import 'navigation';
@import 'hero';
@import 'sections';
@import 'footer';

// 4. Page-Specific Styles
@import 'pages/home';
@import 'pages/products';
@import 'pages/about';
@import 'pages/contact';
@import 'pages/blog';
@import 'pages/careers';

// 5. Utilities
@import 'utilities';
@import 'animations';
@import 'responsive';

// 6. Bengali Language Styles
@import 'lang_bengali';
```

```scss
// Hero Section Styles
// File: static/src/scss/_hero.scss

.hero-section {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    
    // Background with overlay
    .hero-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        
        &::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                135deg,
                rgba($primary-dark, 0.85) 0%,
                rgba($primary-color, 0.6) 100%
            );
        }
    }
    
    // Content positioning
    .hero-content {
        position: relative;
        z-index: 2;
        padding: $space-8 0;
        color: $white;
        
        .hero-subtitle {
            font-size: $font-size-lg;
            font-weight: $font-weight-medium;
            text-transform: uppercase;
            letter-spacing: $letter-spacing-wider;
            margin-bottom: $space-3;
            color: $secondary-color;
        }
        
        .hero-title {
            font-size: $h1-font-size * 1.5;
            font-weight: $font-weight-bold;
            line-height: $line-height-tight;
            margin-bottom: $space-4;
            
            @media (max-width: 768px) {
                font-size: $h1-font-size;
            }
        }
        
        .hero-description {
            font-size: $font-size-lg;
            line-height: $line-height-relaxed;
            margin-bottom: $space-5;
            opacity: 0.9;
            max-width: 600px;
        }
        
        .hero-buttons {
            display: flex;
            gap: $space-3;
            flex-wrap: wrap;
        }
    }
    
    // Hero image/shape
    .hero-image {
        position: relative;
        z-index: 2;
        
        img {
            max-width: 100%;
            height: auto;
            animation: float 6s ease-in-out infinite;
        }
    }
    
    // Decorative elements
    .hero-shapes {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
        
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba($white, 0.1);
            
            &--1 {
                width: 400px;
                height: 400px;
                top: -100px;
                right: -100px;
            }
            
            &--2 {
                width: 200px;
                height: 200px;
                bottom: 50px;
                left: -50px;
            }
        }
    }
}

// Floating animation
@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-20px);
    }
}

// Secondary Hero (for inner pages)
.hero-section--secondary {
    min-height: 50vh;
    
    .hero-content {
        text-align: center;
        
        .hero-title {
            font-size: $h1-font-size * 1.2;
        }
    }
}
```

---

## 4. Core Page Development (Day 33-36)

### 4.1 Page Structure Overview

The Smart Dairy website consists of the following core pages:

| **Page** | **URL** | **Priority** | **Complexity** |
|----------|---------|--------------|----------------|
| Homepage | / | Critical | High |
| About Us | /about-us | High | Medium |
| Products | /products | Critical | High |
| Product Detail | /products/{slug} | High | Medium |
| Farm Tour | /farm-tour | Medium | Medium |
| News & Blog | /blog | High | Medium |
| Blog Post | /blog/{slug} | High | Low |
| Careers | /careers | Medium | High |
| Contact Us | /contact-us | High | Medium |
| FAQ | /faq | Medium | Low |
| Privacy Policy | /privacy | Low | Low |
| Terms of Service | /terms | Low | Low |

### 4.2 Homepage Development

#### 4.2.1 Homepage Template (QWeb)

```xml
<?xml version="1.0" encoding="UTF-8"?>
<odoo>
    <template id="homepage" name="Smart Dairy - Homepage">
        <t t-call="website.layout">
            <!-- SEO Meta Tags -->
            <t t-set="additional_meta">
                <meta name="description" content="Smart Dairy - Pure, Fresh, Natural dairy products straight from our farms to your doorstep. Explore our range of milk, yogurt, cheese, and more."/>
                <meta name="keywords" content="dairy products, fresh milk, yogurt, cheese, Bangladesh dairy, organic milk"/>
                <meta property="og:title" content="Smart Dairy - Pure Fresh Dairy Products"/>
                <meta property="og:description" content="Premium quality dairy products from farm to table"/>
                <meta property="og:image" content="/smart_dairy/static/src/img/og-image.jpg"/>
                <meta property="og:type" content="website"/>
                <link rel="canonical" href="/"/>
            </t>
            
            <div id="wrap" class="homepage">
                <!-- Hero Section -->
                <section class="hero-section" id="home">
                    <div class="hero-bg" style="background-image: url('/smart_dairy/static/src/img/hero-bg.jpg')"></div>
                    <div class="hero-shapes">
                        <div class="shape shape--1"></div>
                        <div class="shape shape--2"></div>
                    </div>
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-7">
                                <div class="hero-content">
                                    <span class="hero-subtitle">Welcome to Smart Dairy</span>
                                    <h1 class="hero-title">
                                        Pure. Fresh. Natural.<br/>
                                        <span class="text-secondary">From Our Farms to You</span>
                                    </h1>
                                    <p class="hero-description">
                                        Experience the taste of purity with our farm-fresh dairy products. 
                                        We bring you the finest quality milk, yogurt, cheese, and more, 
                                        produced with care for your family's health.
                                    </p>
                                    <div class="hero-buttons">
                                        <a href="/products" class="btn btn-secondary btn-lg btn-icon">
                                            <i class="fas fa-shopping-basket"></i>
                                            Explore Products
                                        </a>
                                        <a href="/farm-tour" class="btn btn-outline-light btn-lg btn-icon">
                                            <i class="fas fa-play-circle"></i>
                                            Farm Tour
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 d-none d-lg-block">
                                <div class="hero-image">
                                    <img src="/smart_dairy/static/src/img/hero-products.png" 
                                         alt="Smart Dairy Products"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Scroll Down Indicator -->
                    <div class="scroll-down">
                        <a href="#features">
                            <span></span>
                        </a>
                    </div>
                </section>

                <!-- Features/Highlights Section -->
                <section class="section section-features" id="features">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="feature-card">
                                    <div class="feature-icon">
                                        <i class="fas fa-leaf"></i>
                                    </div>
                                    <h4>100% Organic</h4>
                                    <p>Pure and natural products without any artificial additives</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="feature-card">
                                    <div class="feature-icon">
                                        <i class="fas fa-shipping-fast"></i>
                                    </div>
                                    <h4>Fast Delivery</h4>
                                    <p>Same-day delivery to ensure maximum freshness</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="feature-card">
                                    <div class="feature-icon">
                                        <i class="fas fa-award"></i>
                                    </div>
                                    <h4>Premium Quality</h4>
                                    <p>Certified quality standards for all our products</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="feature-card">
                                    <div class="feature-icon">
                                        <i class="fas fa-headset"></i>
                                    </div>
                                    <h4>24/7 Support</h4>
                                    <p>Round-the-clock customer service for your needs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- About Section -->
                <section class="section section-about bg-light">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <div class="about-images">
                                    <div class="about-img-main">
                                        <img src="/smart_dairy/static/src/img/about-main.jpg" 
                                             alt="Smart Dairy Farm" 
                                             class="img-fluid rounded"/>
                                    </div>
                                    <div class="about-img-sub">
                                        <img src="/smart_dairy/static/src/img/about-sub.jpg" 
                                             alt="Quality Milk Production" 
                                             class="img-fluid rounded"/>
                                        <div class="experience-badge">
                                            <span class="years">15+</span>
                                            <span class="text">Years of Excellence</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="section-header">
                                    <span class="section-subtitle">About Smart Dairy</span>
                                    <h2 class="section-title">
                                        Delivering Quality Dairy Products Since 2011
                                    </h2>
                                </div>
                                <p class="section-description">
                                    Smart Dairy has been at the forefront of the dairy industry in Bangladesh, 
                                    combining traditional farming practices with modern technology to deliver 
                                    the finest dairy products to our customers.
                                </p>
                                <ul class="about-list">
                                    <li>
                                        <i class="fas fa-check-circle text-primary"></i>
                                        State-of-the-art dairy processing facilities
                                    </li>
                                    <li>
                                        <i class="fas fa-check-circle text-primary"></i>
                                        Stringent quality control at every stage
                                    </li>
                                    <li>
                                        <i class="fas fa-check-circle text-primary"></i>
                                        Sustainable and eco-friendly farming practices
                                    </li>
                                    <li>
                                        <i class="fas fa-check-circle text-primary"></i>
                                        Direct farm-to-consumer supply chain
                                    </li>
                                </ul>
                                <a href="/about-us" class="btn btn-primary btn-icon mt-4">
                                    Learn More About Us
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Products Section -->
                <section class="section section-products" id="products">
                    <div class="container">
                        <div class="section-header text-center">
                            <span class="section-subtitle">Our Products</span>
                            <h2 class="section-title">Premium Quality Dairy Products</h2>
                            <p class="section-description mx-auto">
                                Discover our wide range of dairy products, crafted with care and delivered fresh.
                            </p>
                        </div>
                        
                        <div class="row" t-if="products">
                            <t t-foreach="products" t-as="product">
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="card card-product">
                                        <div class="card-image">
                                            <a t-att-href="'/products/' + slug(product)">
                                                <img t-att-src="'/web/image/product.template/' + str(product.id) + '/image_512'"
                                                     t-att-alt="product.name"
                                                     loading="lazy"/>
                                            </a>
                                            <t t-if="product.is_new">
                                                <span class="product-badge badge-new">New</span>
                                            </t>
                                            <t t-if="product.is_featured">
                                                <span class="product-badge badge-featured">Featured</span>
                                            </t>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <a t-att-href="'/products/' + slug(product)" 
                                                   t-esc="product.name"/>
                                            </h5>
                                            <p class="card-text" t-esc="product.description_sale"/>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="product-price">
                                                    <t t-esc="product.website_price" 
                                                       t-options="{'widget': 'monetary', 'display_currency': website.currency_id}"/>
                                                </span>
                                                <a t-att-href="'/products/' + slug(product)" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </t>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="/products" class="btn btn-primary btn-lg">
                                View All Products
                            </a>
                        </div>
                    </div>
                </section>

                <!-- Farm Tour CTA Section -->
                <section class="section section-cta" 
                         style="background-image: url('/smart_dairy/static/src/img/cta-bg.jpg')">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 mx-auto text-center">
                                <h2>Take a Virtual Tour of Our Farms</h2>
                                <p>
                                    See how we maintain the highest standards of quality and sustainability 
                                    at our state-of-the-art dairy farms across Bangladesh.
                                </p>
                                <a href="/farm-tour" class="btn btn-secondary btn-lg btn-icon">
                                    <i class="fas fa-video"></i>
                                    Start Virtual Tour
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Statistics Section -->
                <section class="section section-stats bg-primary text-white">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 text-center mb-4">
                                <div class="stat-item">
                                    <span class="stat-number" data-count="50000">0</span>
                                    <span class="stat-suffix">+</span>
                                    <p class="stat-label">Happy Customers</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 text-center mb-4">
                                <div class="stat-item">
                                    <span class="stat-number" data-count="15">0</span>
                                    <span class="stat-suffix">+</span>
                                    <p class="stat-label">Years Experience</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 text-center mb-4">
                                <div class="stat-item">
                                    <span class="stat-number" data-count="50">0</span>
                                    <span class="stat-suffix">+</span>
                                    <p class="stat-label">Products</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 text-center mb-4">
                                <div class="stat-item">
                                    <span class="stat-number" data-count="500">0</span>
                                    <span class="stat-suffix">+</span>
                                    <p class="stat-label">Team Members</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Testimonials Section -->
                <section class="section section-testimonials bg-light">
                    <div class="container">
                        <div class="section-header text-center">
                            <span class="section-subtitle">Testimonials</span>
                            <h2 class="section-title">What Our Customers Say</h2>
                        </div>
                        
                        <div class="testimonial-slider owl-carousel">
                            <t t-foreach="testimonials" t-as="testimonial">
                                <div class="testimonial-item">
                                    <div class="testimonial-content">
                                        <div class="quote-icon">
                                            <i class="fas fa-quote-left"></i>
                                        </div>
                                        <p t-esc="testimonial.content"/>
                                    </div>
                                    <div class="testimonial-author">
                                        <img t-att-src="'/web/image/smart_dairy.testimonial/' + str(testimonial.id) + '/image_128'"
                                             t-att-alt="testimonial.name"
                                             class="author-img"/>
                                        <div class="author-info">
                                            <h5 t-esc="testimonial.name"/>
                                            <span t-esc="testimonial.designation"/>
                                        </div>
                                    </div>
                                </div>
                            </t>
                        </div>
                    </div>
                </section>

                <!-- Blog Section -->
                <section class="section section-blog">
                    <div class="container">
                        <div class="section-header text-center">
                            <span class="section-subtitle">Latest News</span>
                            <h2 class="section-title">From Our Blog</h2>
                            <p class="section-description mx-auto">
                                Stay updated with the latest news, health tips, and dairy industry insights.
                            </p>
                        </div>
                        
                        <div class="row">
                            <t t-foreach="blog_posts" t-as="post">
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card card-blog">
                                        <div class="card-image">
                                            <a t-att-href="'/blog/' + slug(post)">
                                                <img t-att-src="'/web/image/blog.post/' + str(post.id) + '/cover_properties'"
                                                     t-att-alt="post.name"
                                                     loading="lazy"/>
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <div class="blog-meta">
                                                <span class="meta-item">
                                                    <i class="far fa-calendar"></i>
                                                    <t t-esc="post.post_date" t-options="{'widget': 'date'}"/>
                                                </span>
                                                <span class="meta-item">
                                                    <i class="far fa-user"></i>
                                                    <t t-esc="post.author_id.name"/>
                                                </span>
                                            </div>
                                            <h5 class="card-title">
                                                <a t-att-href="'/blog/' + slug(post)" 
                                                   t-esc="post.name"/>
                                            </h5>
                                            <p class="card-text" t-esc="post.subtitle"/>
                                            <a t-att-href="'/blog/' + slug(post)" 
                                               class="btn btn-link ps-0">
                                                Read More <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </t>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="/blog" class="btn btn-outline-primary btn-lg">
                                View All Posts
                            </a>
                        </div>
                    </div>
                </section>

                <!-- Newsletter Section -->
                <section class="section section-newsletter">
                    <div class="container">
                        <div class="newsletter-box">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <h3>Subscribe to Our Newsletter</h3>
                                    <p>Get the latest updates, exclusive offers, and health tips delivered to your inbox.</p>
                                </div>
                                <div class="col-lg-6">
                                    <form class="newsletter-form" action="/subscribe" method="post">
                                        <div class="input-group">
                                            <input type="email" 
                                                   class="form-control" 
                                                   placeholder="Enter your email"
                                                   required="required"/>
                                            <button class="btn btn-secondary" type="submit">
                                                Subscribe
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </t>
    </template>
</odoo>
```

#### 4.2.2 Homepage CSS Styles

```scss
// Homepage Styles
// File: static/src/scss/pages/_home.scss

.homepage {
    // Hero Section Enhancements
    .hero-section {
        .scroll-down {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            
            a {
                display: block;
                width: 30px;
                height: 50px;
                border: 2px solid rgba($white, 0.5);
                border-radius: 20px;
                position: relative;
                
                span {
                    display: block;
                    width: 6px;
                    height: 6px;
                    background: $white;
                    border-radius: 50%;
                    position: absolute;
                    top: 10px;
                    left: 50%;
                    transform: translateX(-50%);
                    animation: scrollDown 2s infinite;
                }
            }
        }
    }
    
    @keyframes scrollDown {
        0% {
            opacity: 1;
            top: 10px;
        }
        50% {
            opacity: 0.5;
            top: 20px;
        }
        100% {
            opacity: 0;
            top: 30px;
        }
    }
    
    // Features Section
    .section-features {
        padding: $space-6 0;
        margin-top: -$space-6;
        position: relative;
        z-index: 10;
        
        .feature-card {
            background: $white;
            padding: $space-5;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: all 0.3s ease;
            
            &:hover {
                transform: translateY(-8px);
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            }
            
            .feature-icon {
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg, $primary-color, $primary-light);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto $space-3;
                
                i {
                    font-size: 32px;
                    color: $white;
                }
            }
            
            h4 {
                font-size: $h5-font-size;
                font-weight: $font-weight-semibold;
                margin-bottom: $space-2;
                color: $gray-900;
            }
            
            p {
                color: $gray-600;
                margin-bottom: 0;
                font-size: $font-size-sm;
            }
        }
    }
    
    // About Section
    .section-about {
        .about-images {
            position: relative;
            
            .about-img-main {
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            }
            
            .about-img-sub {
                position: absolute;
                bottom: -30px;
                right: -30px;
                width: 60%;
                border: 5px solid $white;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
                
                .experience-badge {
                    position: absolute;
                    bottom: 20px;
                    left: 20px;
                    background: $secondary-color;
                    color: $white;
                    padding: $space-3;
                    border-radius: 8px;
                    text-align: center;
                    
                    .years {
                        display: block;
                        font-size: 36px;
                        font-weight: $font-weight-bold;
                        line-height: 1;
                    }
                    
                    .text {
                        display: block;
                        font-size: $font-size-sm;
                    }
                }
            }
        }
        
        .about-list {
            list-style: none;
            padding: 0;
            margin: $space-4 0;
            
            li {
                display: flex;
                align-items: center;
                margin-bottom: $space-3;
                font-size: $font-size-lg;
                
                i {
                    margin-right: $space-2;
                    font-size: 20px;
                }
            }
        }
    }
    
    // CTA Section
    .section-cta {
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        position: relative;
        
        &::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba($primary-dark, 0.85);
        }
        
        .container {
            position: relative;
            z-index: 1;
        }
        
        h2, p {
            color: $white;
        }
        
        h2 {
            font-size: $h1-font-size;
            margin-bottom: $space-3;
        }
        
        p {
            font-size: $font-size-lg;
            margin-bottom: $space-4;
            opacity: 0.9;
        }
    }
    
    // Stats Section
    .section-stats {
        padding: $space-7 0;
        
        .stat-item {
            .stat-number {
                font-size: 48px;
                font-weight: $font-weight-bold;
                line-height: 1;
            }
            
            .stat-suffix {
                font-size: 32px;
                font-weight: $font-weight-bold;
            }
            
            .stat-label {
                font-size: $font-size-lg;
                margin-top: $space-2;
                margin-bottom: 0;
                opacity: 0.9;
            }
        }
    }
    
    // Testimonials Section
    .section-testimonials {
        .testimonial-item {
            background: $white;
            padding: $space-5;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin: $space-3;
            
            .testimonial-content {
                position: relative;
                margin-bottom: $space-4;
                
                .quote-icon {
                    font-size: 48px;
                    color: $primary-color;
                    opacity: 0.2;
                    margin-bottom: $space-2;
                }
                
                p {
                    font-size: $font-size-lg;
                    font-style: italic;
                    color: $gray-700;
                    line-height: $line-height-relaxed;
                }
            }
            
            .testimonial-author {
                display: flex;
                align-items: center;
                
                .author-img {
                    width: 60px;
                    height: 60px;
                    border-radius: 50%;
                    object-fit: cover;
                    margin-right: $space-3;
                }
                
                .author-info {
                    h5 {
                        font-size: $font-size-lg;
                        font-weight: $font-weight-semibold;
                        margin-bottom: 0;
                    }
                    
                    span {
                        font-size: $font-size-sm;
                        color: $gray-600;
                    }
                }
            }
        }
    }
    
    // Newsletter Section
    .section-newsletter {
        .newsletter-box {
            background: linear-gradient(135deg, $primary-color, $primary-dark);
            padding: $space-6;
            border-radius: 16px;
            color: $white;
            
            h3 {
                font-size: $h2-font-size;
                margin-bottom: $space-2;
            }
            
            p {
                margin-bottom: 0;
                opacity: 0.9;
            }
            
            .newsletter-form {
                .input-group {
                    background: $white;
                    border-radius: 8px;
                    overflow: hidden;
                    
                    .form-control {
                        border: none;
                        padding: $space-3 $space-4;
                        
                        &:focus {
                            box-shadow: none;
                        }
                    }
                    
                    .btn {
                        padding: $space-3 $space-5;
                    }
                }
            }
        }
    }
}
```

### 4.3 Products Page Development

#### 4.3.1 Products List Template

```xml
<?xml version="1.0" encoding="UTF-8"?>
<odoo>
    <template id="products_list" name="Smart Dairy - Products">
        <t t-call="website.layout">
            <t t-set="additional_meta">
                <meta name="description" content="Explore Smart Dairy's premium range of dairy products including fresh milk, yogurt, cheese, butter, and more."/>
                <meta name="keywords" content="dairy products, milk, yogurt, cheese, butter, ghee, fresh milk"/>
                <link rel="canonical" t-att-href="'/products' + ('?category=' + str(current_category) if current_category else '')"/>
            </t>
            
            <div id="wrap">
                <!-- Hero Section -->
                <section class="hero-section hero-section--secondary">
                    <div class="hero-bg" style="background-image: url('/smart_dairy/static/src/img/products-hero.jpg')"></div>
                    <div class="container">
                        <div class="hero-content">
                            <h1 class="hero-title">Our Products</h1>
                            <p class="hero-description">
                                Discover our wide range of premium quality dairy products
                            </p>
                        </div>
                    </div>
                </section>
                
                <!-- Breadcrumb -->
                <t t-call="smart_dairy.breadcrumb">
                    <t t-set="breadcrumbs" t-value="[
                        {'name': 'Home', 'url': '/'},
                        {'name': 'Products', 'url': '/products', 'active': True}
                    ]"/>
                </t>
                
                <!-- Products Section -->
                <section class="section section-products-list">
                    <div class="container">
                        <div class="row">
                            <!-- Sidebar Filters -->
                            <div class="col-lg-3">
                                <aside class="product-sidebar">
                                    <!-- Search -->
                                    <div class="sidebar-widget">
                                        <h5>Search Products</h5>
                                        <form action="/products" method="get">
                                            <div class="input-group">
                                                <input type="text" 
                                                       name="search" 
                                                       class="form-control"
                                                       placeholder="Search..."
                                                       t-att-value="search"/>
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <!-- Categories -->
                                    <div class="sidebar-widget">
                                        <h5>Categories</h5>
                                        <ul class="category-list">
                                            <li t-att-class="'active' if not current_category else ''">
                                                <a href="/products">
                                                    All Products
                                                    <span class="count" t-esc="product_count"/>
                                                </a>
                                            </li>
                                            <t t-foreach="categories" t-as="category">
                                                <li t-att-class="'active' if current_category == category.id else ''">
                                                    <a t-att-href="'/products?category=' + str(category.id)">
                                                        <span t-esc="category.name"/>
                                                        <span class="count" t-esc="category.product_count"/>
                                                    </a>
                                                </li>
                                            </t>
                                        </ul>
                                    </div>
                                    
                                    <!-- Price Range -->
                                    <div class="sidebar-widget">
                                        <h5>Price Range</h5>
                                        <div class="price-range-slider">
                                            <input type="range" class="form-range" min="0" max="1000"/>
                                            <div class="d-flex justify-content-between">
                                                <span>৳0</span>
                                                <span>৳1000+</span>
                                            </div>
                                        </div>
                                    </div>
                                </aside>
                            </div>
                            
                            <!-- Product Grid -->
                            <div class="col-lg-9">
                                <!-- Toolbar -->
                                <div class="products-toolbar d-flex justify-content-between align-items-center mb-4">
                                    <p class="mb-0">
                                        Showing <strong t-esc="len(products)"/> of 
                                        <strong t-esc="product_count"/> products
                                    </p>
                                    <div class="d-flex align-items-center">
                                        <label class="me-2">Sort by:</label>
                                        <select class="form-select form-select-sm" style="width: auto;">
                                            <option>Default</option>
                                            <option>Price: Low to High</option>
                                            <option>Price: High to Low</option>
                                            <option>Name: A-Z</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Products Grid -->
                                <div class="row">
                                    <t t-foreach="products" t-as="product">
                                        <div class="col-lg-4 col-md-6 mb-4">
                                            <article class="card card-product h-100" 
                                                     itemscope="" 
                                                     itemtype="https://schema.org/Product">
                                                <div class="card-image">
                                                    <a t-att-href="'/products/' + slug(product)">
                                                        <img t-att-src="'/web/image/product.template/' + str(product.id) + '/image_512'"
                                                             t-att-alt="product.name"
                                                             class="img-fluid"
                                                             loading="lazy"
                                                             itemprop="image"/>
                                                    </a>
                                                    <t t-if="product.is_new">
                                                        <span class="product-badge badge-new">New</span>
                                                    </t>
                                                    <div class="product-actions">
                                                        <button class="btn-action" title="Add to Wishlist">
                                                            <i class="far fa-heart"></i>
                                                        </button>
                                                        <button class="btn-action" title="Quick View">
                                                            <i class="far fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body d-flex flex-column">
                                                    <meta itemprop="name" t-att-content="product.name"/>
                                                    <h5 class="card-title" itemprop="name">
                                                        <a t-att-href="'/products/' + slug(product)" 
                                                           t-esc="product.name"/>
                                                    </h5>
                                                    <p class="card-text flex-grow-1" 
                                                       itemprop="description"
                                                       t-esc="product.description_sale"/>
                                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                                        <span class="product-price" itemprop="offers" itemscope="" itemtype="https://schema.org/Offer">
                                                            <meta itemprop="priceCurrency" content="BDT"/>
                                                            <span itemprop="price" t-esc="product.website_price" 
                                                                  t-options="{'widget': 'monetary', 'display_currency': website.currency_id}"/>
                                                        </span>
                                                        <a t-att-href="'/products/' + slug(product)" 
                                                           class="btn btn-sm btn-primary">
                                                            View Details
                                                        </a>
                                                    </div>
                                                </div>
                                            </article>
                                        </div>
                                    </t>
                                </div>
                                
                                <!-- Pagination -->
                                <nav t-if="page_count > 1" class="mt-5">
                                    <ul class="pagination justify-content-center">
                                        <li t-att-class="'page-item' + (' disabled' if page == 1 else '')">
                                            <a class="page-link" t-att-href="'/products?page=' + str(page - 1)">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                        <t t-set="p" t-value="1"/>
                                        <t t-foreach="range(1, page_count + 1)" t-as="p">
                                            <li t-att-class="'page-item' + (' active' if p == page else '')">
                                                <a class="page-link" t-att-href="'/products?page=' + str(p)" t-esc="p"/>
                                            </li>
                                        </t>
                                        <li t-att-class="'page-item' + (' disabled' if page == page_count else '')">
                                            <a class="page-link" t-att-href="'/products?page=' + str(page + 1)">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </t>
    </template>
</odoo>
```

### 4.4 About Us Page Development

```xml
<?xml version="1.0" encoding="UTF-8"?>
<odoo>
    <template id="about_us" name="Smart Dairy - About Us">
        <t t-call="website.layout">
            <t t-set="additional_meta">
                <meta name="description" content="Learn about Smart Dairy - Bangladesh's leading dairy company with 15+ years of excellence in producing premium quality dairy products."/>
                <meta name="keywords" content="Smart Dairy, about us, dairy company, Bangladesh, mission, vision"/>
                <meta property="og:title" content="About Smart Dairy"/>
                <meta property="og:type" content="profile"/>
                <link rel="canonical" href="/about-us"/>
            </t>
            
            <div id="wrap">
                <!-- Hero Section -->
                <section class="hero-section hero-section--secondary">
                    <div class="hero-bg" style="background-image: url('/smart_dairy/static/src/img/about-hero.jpg')"></div>
                    <div class="container">
                        <div class="hero-content">
                            <h1 class="hero-title">About Smart Dairy</h1>
                            <p class="hero-description">Our Story of Excellence</p>
                        </div>
                    </div>
                </section>
                
                <!-- Breadcrumb -->
                <t t-call="smart_dairy.breadcrumb">
                    <t t-set="breadcrumbs" t-value="[
                        {'name': 'Home', 'url': '/'},
                        {'name': 'About Us', 'url': '/about-us', 'active': True}
                    ]"/>
                </t>
                
                <!-- Company Overview -->
                <section class="section">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <img src="/smart_dairy/static/src/img/company-overview.jpg" 
                                     alt="Smart Dairy Facilities"
                                     class="img-fluid rounded shadow"/>
                            </div>
                            <div class="col-lg-6">
                                <div class="section-header">
                                    <span class="section-subtitle">Who We Are</span>
                                    <h2 class="section-title">Leading the Dairy Industry in Bangladesh</h2>
                                </div>
                                <p>
                                    Smart Dairy has been a pioneer in the dairy industry since 2011. 
                                    What started as a small family farm has grown into one of Bangladesh's 
                                    most trusted dairy brands, serving millions of families with 
                                    premium quality dairy products.
                                </p>
                                <p>
                                    Our commitment to quality, sustainability, and innovation has 
                                    earned us numerous awards and the trust of our customers across 
                                    the nation.
                                </p>
                                <div class="row mt-4">
                                    <div class="col-6">
                                        <div class="stat-box">
                                            <h3 class="text-primary">15+</h3>
                                            <p>Years Experience</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-box">
                                            <h3 class="text-primary">50K+</h3>
                                            <p>Happy Customers</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Mission & Vision -->
                <section class="section bg-light">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="mission-card">
                                    <div class="icon-box">
                                        <i class="fas fa-bullseye"></i>
                                    </div>
                                    <h3>Our Mission</h3>
                                    <p>
                                        To provide every family in Bangladesh with access to 
                                        pure, fresh, and nutritious dairy products while 
                                        maintaining the highest standards of quality and 
                                        sustainability.
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="mission-card">
                                    <div class="icon-box">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                    <h3>Our Vision</h3>
                                    <p>
                                        To be the most trusted and preferred dairy brand in 
                                        Bangladesh, known for innovation, quality, and 
                                        commitment to the health and well-being of our 
                                        consumers.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Core Values -->
                <section class="section">
                    <div class="container">
                        <div class="section-header text-center">
                            <span class="section-subtitle">What Drives Us</span>
                            <h2 class="section-title">Our Core Values</h2>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="value-card">
                                    <div class="value-icon">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <h4>Quality First</h4>
                                    <p>We never compromise on quality. Every product undergoes rigorous testing.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="value-card">
                                    <div class="value-icon">
                                        <i class="fas fa-leaf"></i>
                                    </div>
                                    <h4>Sustainability</h4>
                                    <p>We're committed to eco-friendly practices and sustainable farming.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="value-card">
                                    <div class="value-icon">
                                        <i class="fas fa-lightbulb"></i>
                                    </div>
                                    <h4>Innovation</h4>
                                    <p>Constantly evolving to bring you better products and services.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="value-card">
                                    <div class="value-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <h4>Community</h4>
                                    <p>Supporting local farmers and contributing to community development.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="value-card">
                                    <div class="value-icon">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <h4>Integrity</h4>
                                    <p>Operating with transparency and honesty in all our dealings.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="value-card">
                                    <div class="value-icon">
                                        <i class="fas fa-hand-holding-heart"></i>
                                    </div>
                                    <h4>Customer Focus</h4>
                                    <p>Putting our customers at the center of everything we do.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Team Section -->
                <section class="section bg-light">
                    <div class="container">
                        <div class="section-header text-center">
                            <span class="section-subtitle">Our Team</span>
                            <h2 class="section-title">Leadership Team</h2>
                        </div>
                        <div class="row">
                            <t t-foreach="team_members" t-as="member">
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="team-card">
                                        <div class="team-image">
                                            <img t-att-src="'/web/image/hr.employee/' + str(member.id) + '/image_256'"
                                                 t-att-alt="member.name"
                                                 class="img-fluid"/>
                                            <div class="team-overlay">
                                                <div class="social-links">
                                                    <a t-if="member.work_email" t-att-href="'mailto:' + member.work_email">
                                                        <i class="fas fa-envelope"></i>
                                                    </a>
                                                    <a t-if="member.linkedin_profile" t-att-href="member.linkedin_profile">
                                                        <i class="fab fa-linkedin"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="team-info">
                                            <h5 t-esc="member.name"/>
                                            <p t-esc="member.job_id.name"/>
                                        </div>
                                    </div>
                                </div>
                            </t>
                        </div>
                    </div>
                </section>
                
                <!-- History/Timeline -->
                <section class="section">
                    <div class="container">
                        <div class="section-header text-center">
                            <span class="section-subtitle">Our Journey</span>
                            <h2 class="section-title">Milestones</h2>
                        </div>
                        <div class="timeline">
                            <t t-foreach="milestones" t-as="milestone">
                                <div class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <span class="timeline-date" t-esc="milestone.date" t-options="{'widget': 'date', 'format': 'yyyy'}"/>
                                        <h4 t-esc="milestone.name"/>
                                        <p t-esc="milestone.description"/>
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

### 4.5 Contact Page Development

```xml
<?xml version="1.0" encoding="UTF-8"?>
<odoo>
    <template id="contact_us" name="Smart Dairy - Contact Us">
        <t t-call="website.layout">
            <t t-set="additional_meta">
                <meta name="description" content="Get in touch with Smart Dairy. Contact us for inquiries, feedback, or support."/>
                <meta name="keywords" content="contact Smart Dairy, customer support, inquiry"/>
                <link rel="canonical" href="/contact-us"/>
            </t>
            
            <div id="wrap">
                <!-- Hero Section -->
                <section class="hero-section hero-section--secondary">
                    <div class="hero-bg" style="background-image: url('/smart_dairy/static/src/img/contact-hero.jpg')"></div>
                    <div class="container">
                        <div class="hero-content">
                            <h1 class="hero-title">Contact Us</h1>
                            <p class="hero-description">We'd love to hear from you</p>
                        </div>
                    </div>
                </section>
                
                <!-- Breadcrumb -->
                <t t-call="smart_dairy.breadcrumb">
                    <t t-set="breadcrumbs" t-value="[
                        {'name': 'Home', 'url': '/'},
                        {'name': 'Contact Us', 'url': '/contact-us', 'active': True}
                    ]"/>
                </t>
                
                <!-- Contact Section -->
                <section class="section">
                    <div class="container">
                        <div class="row">
                            <!-- Contact Information -->
                            <div class="col-lg-4">
                                <div class="contact-info">
                                    <h3>Get in Touch</h3>
                                    <p>
                                        Have a question or inquiry? We're here to help. 
                                        Reach out to us through any of the following channels.
                                    </p>
                                    
                                    <div class="contact-item">
                                        <div class="icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="details">
                                            <h5>Head Office</h5>
                                            <p>
                                                Smart Dairy Headquarters<br/>
                                                Plot 15, Sector 7, Mirpur<br/>
                                                Dhaka 1216, Bangladesh
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="contact-item">
                                        <div class="icon">
                                            <i class="fas fa-phone-alt"></i>
                                        </div>
                                        <div class="details">
                                            <h5>Phone</h5>
                                            <p>
                                                Customer Care: +880 1XXX-XXXXXX<br/>
                                                Corporate: +880 1XXX-XXXXXX
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="contact-item">
                                        <div class="icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="details">
                                            <h5>Email</h5>
                                            <p>
                                                info@smartdairy.com<br/>
                                                support@smartdairy.com
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="contact-item">
                                        <div class="icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="details">
                                            <h5>Working Hours</h5>
                                            <p>
                                                Sunday - Thursday: 9:00 AM - 6:00 PM<br/>
                                                Friday - Saturday: Closed
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="social-links mt-4">
                                        <h5>Follow Us</h5>
                                        <a href="#" class="me-2"><i class="fab fa-facebook-f"></i></a>
                                        <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                                        <a href="#" class="me-2"><i class="fab fa-instagram"></i></a>
                                        <a href="#" class="me-2"><i class="fab fa-linkedin-in"></i></a>
                                        <a href="#"><i class="fab fa-youtube"></i></a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contact Form -->
                            <div class="col-lg-8">
                                <div class="contact-form-wrapper">
                                    <h3>Send us a Message</h3>
                                    <form action="/contact-us" method="post" class="contact-form">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Your Name *</label>
                                                <input type="text" 
                                                       name="name" 
                                                       class="form-control"
                                                       required="required"
                                                       placeholder="John Doe"/>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Email Address *</label>
                                                <input type="email" 
                                                       name="email" 
                                                       class="form-control"
                                                       required="required"
                                                       placeholder="john@example.com"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Phone Number</label>
                                                <input type="tel" 
                                                       name="phone" 
                                                       class="form-control"
                                                       placeholder="+880 1XXX-XXXXXX"/>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Subject *</label>
                                                <select name="subject" class="form-select" required="required">
                                                    <option value="">Select a subject</option>
                                                    <option value="general">General Inquiry</option>
                                                    <option value="product">Product Information</option>
                                                    <option value="order">Order Status</option>
                                                    <option value="feedback">Feedback</option>
                                                    <option value="career">Career</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Your Message *</label>
                                            <textarea name="message" 
                                                      class="form-control" 
                                                      rows="5"
                                                      required="required"
                                                      placeholder="How can we help you?"></textarea>
                                        </div>
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="privacyCheck" required="required"/>
                                            <label class="form-check-label" for="privacyCheck">
                                                I agree to the <a href="/privacy">Privacy Policy</a> and 
                                                processing of my data.
                                            </label>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            Send Message
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Map Section -->
                <section class="section p-0">
                    <div class="map-container">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.1234567890123!2d90.12345678901234!3d23.123456789012345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjPCsDA3JzI0LjQiTiA5MMKwMDcnMjQuNCJF!5e0!3m2!1sen!2sbd!4v1234567890123!5m2!1sen!2sbd"
                            width="100%" 
                            height="450" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>
                </section>
                
                <!-- Office Locations -->
                <section class="section bg-light">
                    <div class="container">
                        <div class="section-header text-center">
                            <span class="section-subtitle">Our Locations</span>
                            <h2 class="section-title">Find Us Near You</h2>
                        </div>
                        <div class="row">
                            <t t-foreach="offices" t-as="office">
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="office-card">
                                        <div class="office-image">
                                            <img t-att-src="'/web/image/smart_dairy.office/' + str(office.id) + '/image'"
                                                 t-att-alt="office.name"
                                                 class="img-fluid"/>
                                        </div>
                                        <div class="office-details">
                                            <h4 t-esc="office.name"/>
                                            <p><i class="fas fa-map-marker-alt"></i> <t t-esc="office.address"/></p>
                                            <p><i class="fas fa-phone"></i> <t t-esc="office.phone"/></p>
                                            <p><i class="fas fa-envelope"></i> <t t-esc="office.email"/></p>
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

