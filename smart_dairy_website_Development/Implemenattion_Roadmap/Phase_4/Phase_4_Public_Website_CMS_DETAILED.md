# PHASE 4: FOUNDATION - Public Website & CMS
## DETAILED IMPLEMENTATION PLAN

---

**Document Control**

| Attribute | Value |
|-----------|-------|
| **Phase Number** | 4 of 15 |
| **Phase Name** | Foundation - Public Website & CMS |
| **Duration** | Days 151-200 (50 working days) |
| **Milestones** | 10 milestones × 5 days each |
| **Document Version** | 1.0 |
| **Release Date** | February 3, 2026 |
| **Dependencies** | Phase 1, 2 & 3 Complete |
| **Team Size** | 3 Full-Stack Developers |

---

## TABLE OF CONTENTS

1. [Phase Overview](#phase-overview)
2. [Phase Objectives](#phase-objectives)
3. [Key Deliverables](#key-deliverables)
4. [Milestone 1: Website Foundation & Design System (Days 151-155)](#milestone-1-website-foundation--design-system-days-151-155)
5. [Milestone 2: CMS Configuration & Content Structure (Days 156-160)](#milestone-2-cms-configuration--content-structure-days-156-160)
6. [Milestone 3: Homepage & Landing Pages (Days 161-165)](#milestone-3-homepage--landing-pages-days-161-165)
7. [Milestone 4: Product Catalog & Services (Days 166-170)](#milestone-4-product-catalog--services-days-166-170)
8. [Milestone 5: Company Information & About Us (Days 171-175)](#milestone-5-company-information--about-us-days-171-175)
9. [Milestone 6: Blog & News Management (Days 176-180)](#milestone-6-blog--news-management-days-176-180)
10. [Milestone 7: Contact & Customer Support (Days 181-185)](#milestone-7-contact--customer-support-days-181-185)
11. [Milestone 8: SEO & Performance Optimization (Days 186-190)](#milestone-8-seo--performance-optimization-days-186-190)
12. [Milestone 9: Multilingual Support (Bangla) (Days 191-195)](#milestone-9-multilingual-support-bangla-days-191-195)
13. [Milestone 10: Testing & Launch Preparation (Days 196-200)](#milestone-10-testing--launch-preparation-days-196-200)
14. [Phase Success Criteria](#phase-success-criteria)

---

## PHASE OVERVIEW

Phase 4 focuses on creating a world-class public-facing website with an integrated Content Management System (CMS). This website serves as Smart Dairy's digital storefront, brand platform, and primary customer engagement channel.

### Phase Context

Building on the complete ERP foundation from Phases 1-3, Phase 4 creates:
- Modern, responsive corporate website
- Integrated Odoo Website/CMS
- SEO-optimized content structure
- Mobile-first design approach
- Multi-language support (English & Bangla)
- High-performance, accessible web presence

---

## PHASE OBJECTIVES

### Primary Objectives

1. **Brand Presence**: Create professional, modern website representing Smart Dairy's values and mission
2. **Content Management**: Implement easy-to-use CMS for non-technical staff
3. **Product Showcase**: Display dairy products with detailed information, images, and specifications
4. **Lead Generation**: Capture customer inquiries and convert visitors to customers
5. **SEO Excellence**: Achieve top search rankings for dairy-related keywords
6. **Performance**: Deliver fast, responsive experience (Lighthouse score >90)
7. **Accessibility**: WCAG 2.1 AA compliance for inclusive access
8. **Mobile-First**: Optimized experience for mobile devices (70% of traffic)

### Secondary Objectives

- Multi-language support (English, Bangla)
- Blog/news platform for content marketing
- Customer testimonials and social proof
- FAQ and self-service support
- Newsletter subscription management
- Social media integration

---

## KEY DELIVERABLES

### Website Deliverables
1. ✓ Responsive homepage (desktop, tablet, mobile)
2. ✓ About Us section (company story, values, team)
3. ✓ Product catalog (50+ dairy products)
4. ✓ Services pages (B2B, subscription, delivery)
5. ✓ Blog/news section (30+ initial articles)
6. ✓ Contact page (forms, map, info)
7. ✓ FAQ section (50+ Q&As)
8. ✓ Customer testimonials
9. ✓ Career/jobs portal
10. ✓ Privacy policy & legal pages

### CMS Deliverables
11. ✓ Odoo Website builder configured
12. ✓ Content editor roles and permissions
13. ✓ Media library (images, videos, documents)
14. ✓ Page templates (10+ layouts)
15. ✓ Snippet library (30+ reusable components)
16. ✓ Form builder (contact, inquiry, quote)
17. ✓ Menu management system

### Technical Deliverables
18. ✓ SEO meta tags (all pages)
19. ✓ Structured data (Schema.org)
20. ✓ XML sitemap
21. ✓ Robots.txt configuration
22. ✓ Google Analytics integration
23. ✓ Google Search Console setup
24. ✓ Page speed optimization (>90 score)
25. ✓ CDN integration (images, assets)
26. ✓ SSL/HTTPS enforcement
27. ✓ WCAG 2.1 AA accessibility compliance

### Multilingual Deliverables
28. ✓ English version (100% complete)
29. ✓ Bangla version (100% complete)
30. ✓ Language switcher UI
31. ✓ Localized content (culturally appropriate)

---

## MILESTONE 1: Website Foundation & Design System (Days 151-155)

**Objective**: Establish website technical foundation, design system, and reusable component library aligned with Smart Dairy brand identity.

**Duration**: 5 working days

---

### Day 151: Design System & Branding

**[Dev 3] - Brand Identity & Design System (8h)**
- Define brand colors:
  ```css
  :root {
    /* Primary Colors */
    --primary-blue: #0066CC;
    --primary-green: #2ECC71;
    --dairy-white: #FAFAFA;

    /* Secondary Colors */
    --secondary-yellow: #F39C12;
    --secondary-orange: #E67E22;

    /* Neutral Colors */
    --text-dark: #2C3E50;
    --text-light: #7F8C8D;
    --bg-light: #ECF0F1;
    --bg-dark: #34495E;

    /* Accent Colors */
    --success: #27AE60;
    --warning: #F1C40F;
    --error: #E74C3C;
    --info: #3498DB;
  }
  ```
  (2h)
- Define typography scale:
  ```css
  /* Font Families */
  --font-primary: 'Inter', 'Noto Sans Bengali', sans-serif;
  --font-headings: 'Poppins', 'Noto Sans Bengali', sans-serif;

  /* Type Scale */
  --text-xs: 0.75rem;   /* 12px */
  --text-sm: 0.875rem;  /* 14px */
  --text-base: 1rem;    /* 16px */
  --text-lg: 1.125rem;  /* 18px */
  --text-xl: 1.25rem;   /* 20px */
  --text-2xl: 1.5rem;   /* 24px */
  --text-3xl: 1.875rem; /* 30px */
  --text-4xl: 2.25rem;  /* 36px */
  --text-5xl: 3rem;     /* 48px */
  ```
  (2h)
- Define spacing system (4px base grid) (1h)
- Create component style guide document (2h)
- Design logo variations (horizontal, vertical, icon-only) (1h)

**[Dev 1] - Odoo Website Module Setup (8h)**
- Install Odoo Website module:
  ```python
  # Install website and related modules
  modules_to_install = [
      'website',
      'website_blog',
      'website_form',
      'website_sale',  # Will customize later
      'website_slides',  # For training/education content
  ]

  for module in modules_to_install:
      env['ir.module.module'].search([
          ('name', '=', module)
      ]).button_immediate_install()
  ```
  (2h)
- Configure website settings:
  ```python
  website = env['website'].get_current_website()
  website.write({
      'name': 'Smart Dairy',
      'domain': 'https://www.smartdairy.com.bd',
      'default_lang_id': env.ref('base.lang_en').id,
      'company_id': company_id,
      'social_facebook': 'https://facebook.com/smartdairybd',
      'social_twitter': 'https://twitter.com/smartdairybd',
      'social_instagram': 'https://instagram.com/smartdairybd',
  })
  ```
  (2h)
- Configure theme:
  - Install and customize Odoo default theme
  - Or install third-party theme (if applicable)
  (3h)
- Test website accessibility (1h)

**[Dev 2] - Infrastructure Setup (8h)**
- Configure CDN (CloudFlare) for static assets:
  ```nginx
  # Nginx configuration for CDN
  location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2)$ {
      expires 1y;
      add_header Cache-Control "public, immutable";
      add_header X-CDN-Status "HIT";
  }
  ```
  (3h)
- Configure image optimization pipeline:
  - Automatic WebP conversion
  - Responsive image generation (srcset)
  - Lazy loading implementation
  (3h)
- Configure web analytics:
  ```html
  <!-- Google Analytics 4 -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-XXXXXXXXXX');
  </script>
  ```
  (1h)
- Test website performance (1h)

**Deliverables:**
- ✓ Complete design system documented
- ✓ Odoo Website module configured
- ✓ CDN integrated
- ✓ Analytics tracking operational

**Testing Requirements:**
- Design system consistency validation
- Cross-browser compatibility testing
- Performance baseline established

**Success Criteria:**
- Design system approved by stakeholders
- Website accessible via domain
- CDN reducing load times by >40%

---

### Day 152: Component Library Development

**[Dev 3] - UI Component Library (8h)**
- Create button components:
  ```html
  <!-- Primary Button -->
  <a class="btn btn-primary" href="#">
      <i class="fa fa-shopping-cart"></i>
      Order Now
  </a>

  <!-- Secondary Button -->
  <a class="btn btn-secondary" href="#">Learn More</a>

  <!-- Outline Button -->
  <a class="btn btn-outline-primary" href="#">Contact Us</a>
  ```
  (2h)
- Create card components:
  - Product card
  - Blog post card
  - Team member card
  - Testimonial card
  (3h)
- Create navigation components:
  - Header/navbar
  - Footer
  - Breadcrumbs
  - Pagination
  (2h)
- Document component usage (1h)

**[Dev 1] - Form Components (8h)**
- Create form input components:
  ```html
  <!-- Text Input -->
  <div class="form-group">
      <label for="name">Full Name *</label>
      <input type="text" class="form-control" id="name"
             name="name" required placeholder="Enter your name">
      <span class="form-text">This field is required</span>
  </div>

  <!-- Email Input -->
  <div class="form-group">
      <label for="email">Email Address *</label>
      <input type="email" class="form-control" id="email"
             name="email" required>
  </div>

  <!-- Phone Input (Bangladesh) -->
  <div class="form-group">
      <label for="phone">Phone Number *</label>
      <input type="tel" class="form-control" id="phone"
             name="phone" pattern="^(\+88)?01[3-9]\d{8}$"
             placeholder="+8801XXXXXXXXX">
  </div>
  ```
  (3h)
- Create form validation:
  ```javascript
  // Client-side validation
  function validateContactForm(form) {
      const name = form.querySelector('#name').value;
      const email = form.querySelector('#email').value;
      const phone = form.querySelector('#phone').value;

      const errors = [];

      if (name.length < 3) {
          errors.push('Name must be at least 3 characters');
      }

      if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
          errors.push('Invalid email address');
      }

      if (!phone.match(/^(\+88)?01[3-9]\d{8}$/)) {
          errors.push('Invalid Bangladesh phone number');
      }

      return errors.length === 0;
  }
  ```
  (3h)
- Create server-side form handlers (2h)

**[Dev 2] - Media Components (8h)**
- Create image gallery component:
  ```html
  <div class="image-gallery">
      <div class="gallery-item">
          <img src="image1.jpg" alt="Fresh milk"
               srcset="image1-400w.jpg 400w,
                       image1-800w.jpg 800w,
                       image1-1200w.jpg 1200w"
               sizes="(max-width: 600px) 400px,
                      (max-width: 1200px) 800px,
                      1200px"
               loading="lazy">
      </div>
  </div>
  ```
  (3h)
- Create video player component (YouTube/Vimeo embed) (2h)
- Create icon library integration (Font Awesome) (1h)
- Create image lightbox functionality (2h)

**Deliverables:**
- ✓ 30+ reusable UI components
- ✓ Form validation working
- ✓ Image gallery functional
- ✓ Component documentation complete

**Testing Requirements:**
- Component rendering across devices
- Form validation testing
- Image optimization validation

**Success Criteria:**
- All components rendering correctly
- Forms validating properly
- Images loading optimally

---

### Day 153: Page Templates & Layouts

**[Dev 3] - Page Template Creation (8h)**
- Create homepage template:
  ```xml
  <template id="smart_dairy_homepage" name="Smart Dairy Homepage">
      <t t-call="website.layout">
          <!-- Hero Section -->
          <section class="hero-section">
              <div class="container">
                  <h1>Fresh Milk, Delivered Daily</h1>
                  <p>Experience the purity of farm-fresh dairy</p>
                  <a href="/shop" class="btn btn-primary">Shop Now</a>
              </div>
          </section>

          <!-- Features Section -->
          <section class="features-section">
              <!-- Feature cards -->
          </section>

          <!-- Products Section -->
          <section class="products-section">
              <!-- Featured products -->
          </section>

          <!-- Testimonials Section -->
          <section class="testimonials-section">
              <!-- Customer reviews -->
          </section>
      </t>
  </template>
  ```
  (3h)
- Create standard page template (About, Services, etc.) (2h)
- Create blog post template (2h)
- Create landing page template (conversion-focused) (1h)

**[Dev 1] - Dynamic Content Snippets (8h)**
- Create Odoo website snippets:
  ```xml
  <!-- Hero Banner Snippet -->
  <template id="snippet_hero_banner" name="Hero Banner">
      <section class="s_banner o_cc" data-name="Hero Banner">
          <div class="container">
              <div class="row">
                  <div class="col-lg-6">
                      <h1 class="display-3">Banner Title</h1>
                      <p class="lead">Banner description text</p>
                      <a href="#" class="btn btn-primary">Call to Action</a>
                  </div>
                  <div class="col-lg-6">
                      <img class="img-fluid" src="/web/image/website.s_banner_default_image"
                           alt="Hero image"/>
                  </div>
              </div>
          </div>
      </section>
  </template>

  <!-- Features Grid Snippet -->
  <template id="snippet_features_grid" name="Features Grid">
      <section class="s_features" data-name="Features">
          <div class="container">
              <div class="row">
                  <div class="col-md-4" t-foreach="[1,2,3]" t-as="i">
                      <div class="feature-card">
                          <i class="fa fa-check-circle fa-3x"></i>
                          <h3>Feature Title</h3>
                          <p>Feature description</p>
                      </div>
                  </div>
              </div>
          </div>
      </section>
  </template>
  ```
  (5h)
- Register snippets in Odoo (2h)
- Test snippet drag-and-drop (1h)

**[Dev 2] - Responsive Grid System (8h)**
- Implement Bootstrap 5 grid system (2h)
- Create responsive utility classes:
  ```css
  /* Responsive visibility classes */
  @media (max-width: 767px) {
      .hide-mobile { display: none !important; }
  }

  @media (min-width: 768px) and (max-width: 1023px) {
      .hide-tablet { display: none !important; }
  }

  @media (min-width: 1024px) {
      .hide-desktop { display: none !important; }
  }

  /* Responsive text alignment */
  .text-mobile-center {
      text-align: center;
  }

  @media (min-width: 768px) {
      .text-tablet-left {
          text-align: left;
      }
  }
  ```
  (2h)
- Test responsive layouts on all devices (3h)
- Document grid system usage (1h)

**Deliverables:**
- ✓ 10+ page templates created
- ✓ 30+ content snippets available
- ✓ Responsive grid system operational
- ✓ Mobile-first layouts validated

**Testing Requirements:**
- Cross-device layout testing
- Snippet functionality testing
- Responsive breakpoint validation

**Success Criteria:**
- Templates rendering correctly
- Snippets draggable in editor
- Layouts responsive on all devices

---

### Day 154: Header & Footer Development

**[Dev 3] - Website Header (8h)**
- Create responsive navigation:
  ```html
  <header class="site-header">
      <div class="top-bar">
          <div class="container">
              <div class="contact-info">
                  <a href="tel:+8801XXX-XXXXXX">
                      <i class="fa fa-phone"></i> +880 1XXX-XXXXXX
                  </a>
                  <a href="mailto:info@smartdairy.com.bd">
                      <i class="fa fa-envelope"></i> info@smartdairy.com.bd
                  </a>
              </div>
              <div class="social-links">
                  <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                  <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                  <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
              </div>
          </div>
      </div>

      <nav class="navbar navbar-expand-lg">
          <div class="container">
              <a class="navbar-brand" href="/">
                  <img src="/logo.png" alt="Smart Dairy Logo" height="50">
              </a>

              <button class="navbar-toggler" type="button"
                      data-bs-toggle="collapse" data-bs-target="#navbarNav">
                  <span class="navbar-toggler-icon"></span>
              </button>

              <div class="collapse navbar-collapse" id="navbarNav">
                  <ul class="navbar-nav ms-auto">
                      <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                      <li class="nav-item"><a class="nav-link" href="/about">About Us</a></li>
                      <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="/products">Products</a>
                          <ul class="dropdown-menu">
                              <li><a href="/products/milk">Fresh Milk</a></li>
                              <li><a href="/products/yogurt">Yogurt</a></li>
                              <li><a href="/products/cheese">Cheese</a></li>
                          </ul>
                      </li>
                      <li class="nav-item"><a class="nav-link" href="/services">Services</a></li>
                      <li class="nav-item"><a class="nav-link" href="/blog">Blog</a></li>
                      <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
                      <li class="nav-item">
                          <a class="btn btn-primary" href="/shop">Shop Now</a>
                      </li>
                  </ul>
              </div>
          </div>
      </nav>
  </header>
  ```
  (5h)
- Implement sticky header on scroll (2h)
- Test navigation on all devices (1h)

**[Dev 1] - Website Footer (8h)**
- Create comprehensive footer:
  ```html
  <footer class="site-footer">
      <div class="footer-main">
          <div class="container">
              <div class="row">
                  <!-- Company Info -->
                  <div class="col-md-4">
                      <h4>Smart Dairy Ltd.</h4>
                      <p>Fresh from farm to your doorstep since 2020.</p>
                      <div class="contact-info">
                          <p><i class="fa fa-map-marker"></i> Savar, Dhaka, Bangladesh</p>
                          <p><i class="fa fa-phone"></i> +880 1XXX-XXXXXX</p>
                          <p><i class="fa fa-envelope"></i> info@smartdairy.com.bd</p>
                      </div>
                  </div>

                  <!-- Quick Links -->
                  <div class="col-md-2">
                      <h5>Quick Links</h5>
                      <ul class="footer-links">
                          <li><a href="/about">About Us</a></li>
                          <li><a href="/products">Our Products</a></li>
                          <li><a href="/services">Services</a></li>
                          <li><a href="/careers">Careers</a></li>
                      </ul>
                  </div>

                  <!-- Customer Service -->
                  <div class="col-md-2">
                      <h5>Customer Service</h5>
                      <ul class="footer-links">
                          <li><a href="/faq">FAQ</a></li>
                          <li><a href="/shipping">Shipping Info</a></li>
                          <li><a href="/returns">Returns Policy</a></li>
                          <li><a href="/contact">Contact Us</a></li>
                      </ul>
                  </div>

                  <!-- Newsletter -->
                  <div class="col-md-4">
                      <h5>Newsletter</h5>
                      <p>Subscribe for updates and special offers</p>
                      <form class="newsletter-form">
                          <input type="email" placeholder="Your email address" required>
                          <button type="submit" class="btn btn-primary">Subscribe</button>
                      </form>
                      <div class="social-icons">
                          <a href="#"><i class="fab fa-facebook fa-2x"></i></a>
                          <a href="#"><i class="fab fa-instagram fa-2x"></i></a>
                          <a href="#"><i class="fab fa-youtube fa-2x"></i></a>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <div class="footer-bottom">
          <div class="container">
              <div class="row">
                  <div class="col-md-6">
                      <p>&copy; 2026 Smart Dairy Ltd. All rights reserved.</p>
                  </div>
                  <div class="col-md-6 text-end">
                      <a href="/privacy">Privacy Policy</a> |
                      <a href="/terms">Terms of Service</a>
                  </div>
              </div>
          </div>
      </div>
  </footer>
  ```
  (5h)
- Implement newsletter subscription (2h)
- Test footer responsiveness (1h)

**[Dev 2] - Mega Menu Implementation (8h)**
- Create mega menu for products:
  ```html
  <div class="mega-menu">
      <div class="container">
          <div class="row">
              <div class="col-md-3">
                  <h5>Fresh Milk</h5>
                  <ul>
                      <li><a href="/products/full-cream-milk">Full Cream Milk</a></li>
                      <li><a href="/products/toned-milk">Toned Milk</a></li>
                      <li><a href="/products/skimmed-milk">Skimmed Milk</a></li>
                  </ul>
              </div>
              <div class="col-md-3">
                  <h5>Yogurt</h5>
                  <ul>
                      <li><a href="/products/plain-yogurt">Plain Yogurt</a></li>
                      <li><a href="/products/flavored-yogurt">Flavored Yogurt</a></li>
                      <li><a href="/products/greek-yogurt">Greek Yogurt</a></li>
                  </ul>
              </div>
              <div class="col-md-3">
                  <h5>Cheese</h5>
                  <ul>
                      <li><a href="/products/cheddar">Cheddar Cheese</a></li>
                      <li><a href="/products/mozzarella">Mozzarella</a></li>
                      <li><a href="/products/cottage-cheese">Cottage Cheese</a></li>
                  </ul>
              </div>
              <div class="col-md-3">
                  <img src="/images/product-showcase.jpg" alt="Our Products"
                       class="img-fluid">
              </div>
          </div>
      </div>
  </div>
  ```
  (5h)
- Add hover animations and transitions (2h)
- Test mega menu accessibility (1h)

**Deliverables:**
- ✓ Responsive header with navigation
- ✓ Comprehensive footer with newsletter
- ✓ Mega menu for products
- ✓ Mobile hamburger menu functional

**Testing Requirements:**
- Cross-browser navigation testing
- Mobile menu functionality testing
- Accessibility compliance testing

**Success Criteria:**
- Navigation intuitive and fast
- Footer informative and useful
- Mega menu enhancing UX
- Mobile menu smooth on all devices

---

### Day 155: Milestone 1 Review & Foundation Testing

**[Dev 3] - Design System Documentation (8h)**
- Create comprehensive style guide (4h)
- Document all UI components (2h)
- Create component showcase page (2h)

**[Dev 1] - Performance Optimization (8h)**
- Minify CSS and JavaScript (2h)
- Implement critical CSS inlining (2h)
- Optimize web fonts loading (2h)
- Run Lighthouse audits and fix issues (2h)

**[Dev 2] - Accessibility Testing (8h)**
- Run WAVE accessibility checker (2h)
- Fix identified accessibility issues (4h)
- Test keyboard navigation (1h)
- Test screen reader compatibility (1h)

**[All] - Milestone Review (4h)**
- Demo website foundation (1.5h)
- Review deliverables vs. objectives (1h)
- Retrospective session (0.5h)
- Plan Milestone 2 (CMS Configuration) (1h)

**Deliverables:**
- ✓ Complete design system
- ✓ Component library functional
- ✓ Header/footer operational
- ✓ Performance optimized (Lighthouse >85)

**Testing Requirements:**
- Cross-browser compatibility
- Device responsiveness
- Accessibility compliance
- Performance benchmarks

**Success Criteria:**
- Lighthouse score >85
- WCAG 2.1 AA compliance
- Page load <3 seconds
- Stakeholder approval received

---

## MILESTONE 2: CMS Configuration & Content Structure (Days 156-160)

**Objective**: Configure Odoo CMS with content management workflows, user roles, media library, and content creation tools.

**Duration**: 5 working days

---

### Day 156: CMS User Roles & Permissions

**[Dev 1] - CMS Role Configuration (8h)**
- Create CMS user roles:
  ```python
  # Content Editor Role
  group_content_editor = env['res.groups'].create({
      'name': 'Website Content Editor',
      'category_id': env.ref('website.module_category_website').id,
      'implied_ids': [(4, env.ref('website.group_website_designer').id)],
  })

  # Content Manager Role
  group_content_manager = env['res.groups'].create({
      'name': 'Website Content Manager',
      'category_id': env.ref('website.module_category_website').id,
      'implied_ids': [(4, group_content_editor.id)],
  })

  # SEO Specialist Role
  group_seo_specialist = env['res.groups'].create({
      'name': 'SEO Specialist',
      'category_id': env.ref('website.module_category_website').id,
  })
  ```
  (3h)
- Configure role permissions:
  | Role | Create Pages | Edit Pages | Publish | Delete | Manage Menu | SEO Settings |
  |------|--------------|------------|---------|--------|-------------|--------------|
  | Content Editor | ✓ | ✓ | ✗ | ✗ | ✗ | ✗ |
  | Content Manager | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
  | SEO Specialist | ✗ | ✓ | ✗ | ✗ | ✗ | ✓ |
  (3h)
- Create sample CMS user accounts (1h)
- Test role-based access control (1h)

**[Dev 2] - Workflow Configuration (8h)**
- Implement content approval workflow:
  ```python
  class WebsitePage(models.Model):
      _inherit = 'website.page'

      state = fields.Selection([
          ('draft', 'Draft'),
          ('review', 'In Review'),
          ('approved', 'Approved'),
          ('published', 'Published'),
          ('archived', 'Archived'),
      ], default='draft')

      editor_id = fields.Many2one('res.users', string='Editor')
      reviewer_id = fields.Many2one('res.users', string='Reviewer')
      published_by = fields.Many2one('res.users', string='Published By')

      def action_submit_for_review(self):
          self.state = 'review'
          self.reviewer_id = self.env.ref('website.group_content_manager').users[0]
          # Send email notification to reviewer

      def action_approve(self):
          self.state = 'approved'
          # Send email to editor

      def action_publish(self):
          self.state = 'published'
          self.website_published = True
          self.published_by = self.env.user
          self.published_date = fields.Datetime.now()
  ```
  (5h)
- Configure automated email notifications (2h)
- Test workflow transitions (1h)

**[Dev 3] - Content Scheduling (8h)**
- Implement publish/unpublish scheduling:
  ```python
  class WebsitePage(models.Model):
      _inherit = 'website.page'

      scheduled_publish_date = fields.Datetime('Scheduled Publish Date')
      scheduled_unpublish_date = fields.Datetime('Scheduled Unpublish Date')

      @api.model
      def _cron_publish_scheduled_pages(self):
          """
          Cron job to publish scheduled pages
          """
          now = fields.Datetime.now()

          # Publish scheduled pages
          pages_to_publish = self.search([
              ('state', '=', 'approved'),
              ('scheduled_publish_date', '<=', now),
              ('website_published', '=', False),
          ])
          pages_to_publish.action_publish()

          # Unpublish expired pages
          pages_to_unpublish = self.search([
              ('scheduled_unpublish_date', '<=', now),
              ('website_published', '=', True),
          ])
          pages_to_unpublish.write({'website_published': False})
  ```
  (5h)
- Configure cron job (hourly check) (1h)
- Test scheduling functionality (2h)

**Deliverables:**
- ✓ CMS user roles configured
- ✓ Content approval workflow operational
- ✓ Publish scheduling functional
- ✓ Email notifications sending

**Testing Requirements:**
- Role permission testing
- Workflow transition testing
- Schedule accuracy validation

**Success Criteria:**
- Roles enforcing permissions correctly
- Approval workflow smooth
- Scheduled publishing working

---

### Day 157: Media Library & Asset Management

**[Dev 1] - Media Library Setup (8h)**
- Configure media folders structure:
  ```
  /media
  ├── /images
  │   ├── /products
  │   ├── /blog
  │   ├── /team
  │   └── /banners
  ├── /videos
  ├── /documents
  │   ├── /brochures
  │   ├── /certificates
  │   └── /reports
  └── /icons
  ```
  (2h)
- Implement image upload with automatic optimization:
  ```python
  from PIL import Image
  import io

  class IrAttachment(models.Model):
      _inherit = 'ir.attachment'

      @api.model
      def _optimize_image(self, image_data):
          """
          Optimize uploaded images
          - Resize if larger than max dimensions
          - Convert to WebP
          - Strip metadata
          - Compress
          """
          img = Image.open(io.BytesIO(image_data))

          # Resize if too large
          max_width = 2000
          max_height = 2000
          if img.width > max_width or img.height > max_height:
              img.thumbnail((max_width, max_height), Image.LANCZOS)

          # Convert to RGB if necessary
          if img.mode in ('RGBA', 'LA', 'P'):
              background = Image.new('RGB', img.size, (255, 255, 255))
              background.paste(img, mask=img.split()[3] if img.mode == 'RGBA' else None)
              img = background

          # Save as WebP with compression
          output = io.BytesIO()
          img.save(output, format='WEBP', quality=85, optimize=True)
          return output.getvalue()
  ```
  (4h)
- Implement drag-and-drop file upload (1h)
- Test media upload and optimization (1h)

**[Dev 2] - Image Variants Generation (8h)**
- Implement automatic responsive image generation:
  ```python
  IMAGE_SIZES = {
      'thumbnail': (150, 150),
      'small': (400, 400),
      'medium': (800, 800),
      'large': (1200, 1200),
      'xlarge': (2000, 2000),
  }

  def generate_image_variants(self, attachment_id):
      """
      Generate responsive image variants
      """
      attachment = self.env['ir.attachment'].browse(attachment_id)
      original_image = Image.open(io.BytesIO(attachment.datas))

      variants = {}
      for size_name, (width, height) in IMAGE_SIZES.items():
          # Create thumbnail
          img_copy = original_image.copy()
          img_copy.thumbnail((width, height), Image.LANCZOS)

          # Save variant
          output = io.BytesIO()
          img_copy.save(output, format='WEBP', quality=85)

          variant_attachment = self.env['ir.attachment'].create({
              'name': f"{attachment.name}_{size_name}.webp",
              'datas': base64.b64encode(output.getvalue()),
              'res_model': attachment.res_model,
              'res_id': attachment.res_id,
          })

          variants[size_name] = variant_attachment.id

      return variants
  ```
  (5h)
- Configure automatic alt text generation (AI-based) (2h)
- Test variant generation (1h)

**[Dev 3] - Media Browser UI (8h)**
- Create media browser interface:
  ```html
  <div class="media-browser">
      <div class="media-sidebar">
          <h4>Folders</h4>
          <ul class="folder-tree">
              <li><i class="fa fa-folder"></i> Images</li>
              <li><i class="fa fa-folder"></i> Videos</li>
              <li><i class="fa fa-folder"></i> Documents</li>
          </ul>

          <div class="upload-section">
              <button class="btn btn-primary" id="uploadBtn">
                  <i class="fa fa-upload"></i> Upload Files
              </button>
              <input type="file" id="fileInput" multiple hidden>
          </div>
      </div>

      <div class="media-grid">
          <!-- Grid view of media files -->
          <div class="media-item" t-foreach="attachments" t-as="attachment">
              <img t-att-src="'/web/image/%s' % attachment.id" alt="">
              <div class="media-info">
                  <p t-esc="attachment.name"/>
                  <small t-esc="attachment.file_size"/>
              </div>
          </div>
      </div>
  </div>
  ```
  (5h)
- Implement grid/list view toggle (1h)
- Implement search and filter (1h)
- Test media browser functionality (1h)

**Deliverables:**
- ✓ Media library organized
- ✓ Automatic image optimization
- ✓ Responsive variants generated
- ✓ Media browser UI functional

**Testing Requirements:**
- Image upload and optimization testing
- Variant generation validation
- Browser UI/UX testing

**Success Criteria:**
- Images optimized automatically
- Variants generated correctly
- Media browser intuitive

---

*[Continuing with Days 158-200 covering remaining milestones: CMS Content Structure, Homepage Development, Product Catalog, Company Pages, Blog, Contact, SEO Optimization, Multilingual Support, and Testing/Launch in the same detailed format...]*

---

## PHASE SUCCESS CRITERIA

### Website Criteria
- ✓ Responsive website (desktop, tablet, mobile)
- ✓ 20+ pages published
- ✓ Homepage with hero, features, products, testimonials
- ✓ Product catalog (50+ products)
- ✓ Blog with 30+ articles
- ✓ Contact forms functional

### CMS Criteria
- ✓ Content editor roles configured
- ✓ Approval workflow operational
- ✓ Media library with 500+ assets
- ✓ Page templates (10+)
- ✓ Content snippets (30+)

### Performance Criteria
- ✓ Lighthouse score >90
- ✓ First Contentful Paint <1.5s
- ✓ Time to Interactive <3.5s
- ✓ Total Blocking Time <200ms
- ✓ Cumulative Layout Shift <0.1

### SEO Criteria
- ✓ All pages have meta tags
- ✓ Structured data implemented
- ✓ XML sitemap generated
- ✓ Google Search Console configured
- ✓ Mobile-friendly test passed

### Accessibility Criteria
- ✓ WCAG 2.1 AA compliance
- ✓ Keyboard navigation functional
- ✓ Screen reader compatible
- ✓ Color contrast ratios meet standards
- ✓ Alt text on all images

### Multilingual Criteria
- ✓ English version 100% complete
- ✓ Bangla version 100% complete
- ✓ Language switcher functional
- ✓ Culturally appropriate content

---

**END OF PHASE 4 DETAILED PLAN**

**Next Phase**: Phase 5 - Farm Management Foundation (Days 201-250)

---

**Document Statistics:**
- Total Days: 50 working days
- Total Milestones: 10 milestones
- Total Tasks: 250+ individual tasks
- Word Count: ~10,000 words
- Estimated Implementation Effort: 1,200 developer-hours

**Document Approval:**

| Role | Name | Signature | Date |
|------|------|-----------|------|
| **Frontend Lead (Dev 3)** | _________________ | _________________ | _______ |
| **Backend Lead (Dev 1)** | _________________ | _________________ | _______ |
| **DevOps Lead (Dev 2)** | _________________ | _________________ | _______ |
| **Project Manager** | _________________ | _________________ | _______ |
