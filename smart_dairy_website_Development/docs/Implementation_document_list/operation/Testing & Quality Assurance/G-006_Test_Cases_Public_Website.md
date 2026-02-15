# SMART DAIRY LTD.
## TEST CASES - PUBLIC WEBSITE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | G-006 |
| **Version** | 1.0 |
| **Date** | June 1, 2026 |
| **Author** | QA Engineer |
| **Owner** | QA Engineer |
| **Reviewer** | QA Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Test Case Overview](#2-test-case-overview)
3. [Homepage & Navigation Test Cases](#3-homepage--navigation-test-cases)
4. [Company Information Test Cases](#4-company-information-test-cases)
5. [Product Showcase Test Cases](#5-product-showcase-test-cases)
6. [Multi-language & Localization Test Cases](#6-multi-language--localization-test-cases)
7. [Responsive Design Test Cases](#7-responsive-design-test-cases)
8. [Interactive Features Test Cases](#8-interactive-features-test-cases)
9. [SEO & Performance Test Cases](#9-seo--performance-test-cases)
10. [Accessibility Test Cases](#10-accessibility-test-cases)
11. [Security Test Cases](#11-security-test-cases)
12. [Appendices](#12-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides comprehensive test cases for the Smart Dairy Public Website (smartdairybd.com). The test cases cover all functional and non-functional aspects of the corporate website, ensuring it meets business requirements, provides excellent user experience, and maintains high quality standards.

### 1.2 Scope

| Component | Coverage |
|-----------|----------|
| Homepage | Hero section, featured products, CTAs |
| Navigation | Header, footer, breadcrumb, search |
| Company Pages | About, Leadership, Farms, Careers |
| Product Catalog | Product listings, detail pages |
| Interactive Features | Store locator, contact forms, newsletter |
| CMS Features | Content management, blog, media library |
| Technical | SEO, performance, accessibility, security |

### 1.3 Test Case Structure

Each test case follows this format:

| Field | Description |
|-------|-------------|
| **Test Case ID** | Unique identifier (e.g., TC-WEB-001) |
| **Requirement ID** | Traceability to BRD requirement |
| **Title** | Brief description of the test |
| **Priority** | Critical/High/Medium/Low |
| **Preconditions** | Required setup before execution |
| **Test Steps** | Numbered steps to execute |
| **Test Data** | Specific inputs required |
| **Expected Results** | Pass criteria |
| **Actual Results** | Field for test execution |
| **Status** | Pass/Fail/Blocked/Not Run |
| **Notes** | Additional observations |

---

## 2. TEST CASE OVERVIEW

### 2.1 Test Case Summary

| Module | Total Cases | Critical | High | Medium | Low |
|--------|-------------|----------|------|--------|-----|
| Homepage & Navigation | 35 | 8 | 15 | 10 | 2 |
| Company Information | 25 | 5 | 10 | 8 | 2 |
| Product Showcase | 30 | 6 | 12 | 10 | 2 |
| Multi-language | 20 | 5 | 8 | 5 | 2 |
| Responsive Design | 25 | 5 | 10 | 8 | 2 |
| Interactive Features | 30 | 6 | 12 | 10 | 2 |
| SEO & Performance | 20 | 4 | 8 | 6 | 2 |
| Accessibility | 15 | 3 | 6 | 4 | 2 |
| Security | 15 | 5 | 6 | 3 | 1 |
| **TOTAL** | **215** | **47** | **87** | **64** | **17** |

### 2.2 Test Environment Requirements

| Environment | URL | Purpose |
|-------------|-----|---------|
| QA | https://qa.smartdairybd.com | Functional testing |
| Staging | https://staging.smartdairybd.com | UAT preparation |
| Production | https://smartdairybd.com | Final validation |

### 2.3 Browser Matrix

| Browser | Version | Priority |
|---------|---------|----------|
| Google Chrome | Latest, Latest-1 | Critical |
| Mozilla Firefox | Latest, Latest-1 | Critical |
| Safari | Latest (macOS/iOS) | Critical |
| Microsoft Edge | Latest | High |
| Samsung Internet | Latest | Medium |

### 2.4 Device Matrix

| Device Type | Screen Size | Priority |
|-------------|-------------|----------|
| Desktop | 1920x1080 | Critical |
| Laptop | 1366x768 | Critical |
| Tablet (iPad) | 768x1024 | High |
| Mobile (iPhone) | 375x812 | Critical |
| Mobile (Android) | 360x640 | Critical |

---

## 3. HOMEPAGE & NAVIGATION TEST CASES

### 3.1 Homepage Functional Tests

#### TC-WEB-001: Homepage Load
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CMS-002 |
| **Title** | Verify Homepage Loads Successfully |
| **Priority** | Critical |
| **Preconditions** | Website is deployed and accessible |

**Test Steps:**
1. Open browser (Chrome)
2. Navigate to https://smartdairybd.com
3. Wait for page to fully load

**Expected Results:**
- [ ] Page loads within 3 seconds
- [ ] No JavaScript errors in console
- [ ] All images display correctly
- [ ] Header navigation is visible
- [ ] Hero section displays with content
- [ ] Footer is visible at bottom

**Test Data:** N/A

---

#### TC-WEB-002: Hero Section Display
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-WEB-001 |
| **Title** | Verify Hero Section Content |
| **Priority** | Critical |

**Test Steps:**
1. Load homepage
2. Verify hero section visible above fold
3. Check headline text displays
4. Check subheadline displays
5. Verify CTA button is present
6. Click CTA button

**Expected Results:**
- [ ] Hero image/video loads correctly
- [ ] Main headline: "Pure. Fresh. Organic." displays
- [ ] Subheadline about farm-fresh dairy displays
- [ ] Primary CTA button "Shop Now" visible
- [ ] Secondary CTA "Learn More" visible
- [ ] Clicking CTA navigates to correct page

---

#### TC-WEB-003: Navigation Menu - Desktop
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-WEB-001 |
| **Title** | Verify Header Navigation Menu |
| **Priority** | Critical |

**Test Steps:**
1. Load homepage on desktop viewport (1920x1080)
2. Examine header navigation
3. Hover over each menu item
4. Click each primary navigation link

**Expected Results:**
- [ ] Logo displays in top-left corner
- [ ] Navigation items: Home, About, Products, Sustainability, Contact
- [ ] All menu items are visible without scrolling
- [ ] Hover effects work on menu items
- [ ] Clicking each link navigates to correct page
- [ ] Active page is highlighted in menu
- [ ] No broken links (404 errors)

**Test Data:**
| Menu Item | Expected URL |
|-----------|--------------|
| Home | / |
| About | /about |
| Products | /products |
| Sustainability | /sustainability |
| Contact | /contact |

---

#### TC-WEB-004: Mobile Hamburger Menu
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CMS-002 |
| **Title** | Verify Mobile Navigation Menu |
| **Priority** | Critical |

**Test Steps:**
1. Load homepage on mobile viewport (375px width)
2. Observe header navigation
3. Click hamburger menu icon
4. Verify menu expands
5. Click each menu item
6. Verify menu closes after selection

**Expected Results:**
- [ ] Hamburger menu icon visible on mobile
- [ ] Menu expands with smooth animation
- [ ] All navigation items visible in expanded menu
- [ ] Close button (X) visible when expanded
- [ ] Menu closes when item selected
- [ ] Menu closes when clicking outside
- [ ] Menu is scrollable if content overflows

---

#### TC-WEB-005: Footer Content Verification
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-WEB-001 |
| **Title** | Verify Footer Content and Links |
| **Priority** | High |

**Test Steps:**
1. Scroll to bottom of homepage
2. Verify footer displays
3. Check footer sections
4. Click footer links
5. Verify social media icons

**Expected Results:**
- [ ] Footer contains 4 columns: Company, Products, Support, Connect
- [ ] Company address displays correctly
- [ ] Contact phone: +880-XXXX-XXXXXX
- [ ] Contact email: info@smartdairybd.com
- [ ] Quick links navigate correctly
- [ ] Social media icons: Facebook, Instagram, YouTube, LinkedIn
- [ ] Copyright text: "© 2026 Smart Dairy Ltd. All rights reserved."
- [ ] Privacy Policy and Terms of Service links present

---

### 3.2 Search Functionality Tests

#### TC-WEB-006: Site Search - Valid Query
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SHOP-003 |
| **Title** | Verify Site Search with Valid Query |
| **Priority** | High |

**Test Steps:**
1. Click search icon in header
2. Type "milk" in search field
3. Press Enter or click search button
4. Review search results page

**Expected Results:**
- [ ] Search input field expands when icon clicked
- [ ] Autocomplete suggestions appear after 3 characters
- [ ] Search results page loads within 2 seconds
- [ ] Results display with product images
- [ ] Number of results shown
- [ ] Results are relevant to "milk" query
- [ ] Each result has title, description, and link

**Test Data:**
| Search Term | Expected Results |
|-------------|------------------|
| milk | Fresh Milk, UHT Milk, Milk Powder |
| yogurt | Sweet Yogurt, Greek Yogurt |
| ghee | Pure Ghee |
| organic | All organic products |

---

#### TC-WEB-007: Site Search - No Results
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SHOP-003 |
| **Title** | Verify Search with No Results |
| **Priority** | Medium |

**Test Steps:**
1. Enter search term "xyz123nonexistent" 
2. Submit search
3. Observe results page

**Expected Results:**
- [ ] No results message displays: "No results found for 'xyz123nonexistent'"
- [ ] Suggested search terms provided
- [ ] Link to browse all products
- [ ] Contact support option

---

### 3.3 Breadcrumb Navigation

#### TC-WEB-008: Breadcrumb Display
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SHOP-001 |
| **Title** | Verify Breadcrumb Navigation |
| **Priority** | Medium |

**Test Steps:**
1. Navigate to a product detail page
2. Observe breadcrumb trail
3. Click each breadcrumb link

**Expected Results:**
- [ ] Breadcrumb displays: Home > Products > Fresh Milk > Organic Full Cream Milk
- [ ] Each level is clickable except current page
- [ ] Clicking "Products" returns to product listing
- [ ] Clicking "Home" returns to homepage
- [ ] Breadcrumb styled consistently with design system

---

## 4. COMPANY INFORMATION TEST CASES

### 4.1 About Us Page

#### TC-WEB-020: About Page Content
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-WEB-001 |
| **Title** | Verify About Us Page Content |
| **Priority** | High |

**Test Steps:**
1. Navigate to /about
2. Verify page loads
3. Check all content sections
4. Verify images load

**Expected Results:**
- [ ] Page title: "About Smart Dairy | Our Story & Mission"
- [ ] Hero section with company overview
- [ ] Mission statement section
- [ ] Vision statement section
- [ ] Core values section (Quality, Sustainability, Integrity)
- [ ] Company history timeline
- [ ] Farm facilities images
- [ ] Quality certifications displayed

---

#### TC-WEB-021: Leadership Team Page
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-WEB-001 |
| **Title** | Verify Leadership Team Page |
| **Priority** | High |

**Test Steps:**
1. Navigate to /about/leadership
2. Verify team members displayed
3. Click on team member (if expandable)

**Expected Results:**
- [ ] Page displays all leadership members
- [ ] Each member has: Photo, Name, Title, Bio
- [ ] Photos are professional quality
- [ ] Chairman: Mohd. Mazharul Islam displayed
- [ ] Managing Director: Mohammad Zahirul Islam displayed
- [ ] Grid layout responsive on all devices

---

### 4.2 Product Catalog Pages

#### TC-WEB-030: Product Listing Page
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-WEB-002 |
| **Title** | Verify Product Category Page |
| **Priority** | Critical |

**Test Steps:**
1. Navigate to /products
2. Verify all product categories displayed
3. Click on "Fresh Milk" category
4. Verify product grid displays

**Expected Results:**
- [ ] Category filter sidebar visible
- [ ] Categories: Fresh Milk, UHT Milk, Yogurt, Butter, Cheese, Ghee, Ice Cream
- [ ] Product grid displays 12 items per page
- [ ] Each product card shows: Image, Name, Price, "View Details" button
- [ ] Pagination controls at bottom
- [ ] Sort options: Featured, Price Low-High, Price High-Low, Newest

---

#### TC-WEB-031: Product Detail Page
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-WEB-002 |
| **Title** | Verify Product Detail Page |
| **Priority** | Critical |

**Test Steps:**
1. Navigate to any product detail page
2. Verify all product information displays
3. Test image gallery
4. Check nutritional information

**Expected Results:**
- [ ] Product name displays prominently
- [ ] Multiple product images with zoom capability
- [ ] Price displayed with currency (৳)
- [ ] Size/variant selection dropdown
- [ ] Quantity selector
- [ ] "Add to Cart" button (redirects to B2C portal)
- [ ] Product description section
- [ ] Nutritional information table
- [ ] Storage instructions
- [ ] Shelf life information
- [ ] Certification badges (Organic, Halal, BSTI)
- [ ] Related products section

**Test Data - Product: Saffron Organic Milk**
| Attribute | Expected Value |
|-----------|----------------|
| Name | Saffron Organic Milk |
| Price | ৳95.00 |
| Size | 1000ml |
| Fat Content | 3.5% |
| Shelf Life | 5 days (refrigerated) |

---

## 5. MULTI-LANGUAGE & LOCALIZATION TEST CASES

### 5.1 Language Switching

#### TC-WEB-050: English to Bengali Switch
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CMS-001 |
| **Title** | Verify Language Switch to Bengali |
| **Priority** | Critical |

**Test Steps:**
1. Load homepage in English
2. Click language toggle (BN/বাংলা)
3. Verify page reloads in Bengali
4. Navigate to other pages
5. Switch back to English

**Expected Results:**
- [ ] Language toggle visible in header (EN | BN)
- [ ] Page reloads with Bengali content
- [ ] URL may change to /bn/ or stay same with cookie
- [ ] All static content displays in Bengali
- [ ] Navigation menu items in Bengali
- [ ] Product names remain in English (brand names)
- [ ] Switching back to English works correctly
- [ ] Selected language persists across session

---

#### TC-WEB-051: Bengali Typography Check
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CMS-001 |
| **Title** | Verify Bengali Font Rendering |
| **Priority** | High |

**Test Steps:**
1. Switch to Bengali language
2. Check various text elements
3. Verify font consistency

**Expected Results:**
- [ ] Bengali text uses appropriate font (SolaimanLipi/Noto Sans Bengali)
- [ ] Text is legible and properly spaced
- [ ] No character encoding issues (no � symbols)
- [ ] Complex conjuncts render correctly
- [ ] Text direction remains LTR (not RTL)

---

#### TC-WEB-052: Date and Number Localization
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CMS-001 |
| **Title** | Verify Date and Number Format Localization |
| **Priority** | Medium |

**Test Steps:**
1. Switch to Bengali
2. Check product prices
3. Check dates in blog posts
4. Check phone number format

**Expected Results:**
- [ ] Prices display with Bengali numerals (৳৯৫.০০) OR standard numerals with Bengali text
- [ ] Dates display in Bengali format (৩১ জানুয়ারি, ২০২৬)
- [ ] Phone numbers use standard format but support Bengali input
- [ ] Numbers in text use consistent numeral system

---

## 6. RESPONSIVE DESIGN TEST CASES

### 6.1 Breakpoint Testing

#### TC-WEB-070: Desktop Layout (1920px)
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CMS-002 |
| **Title** | Verify Desktop Layout at 1920px |
| **Priority** | Critical |

**Test Steps:**
1. Set viewport to 1920x1080
2. Load homepage
3. Check all sections
4. Verify navigation

**Expected Results:**
- [ ] Full navigation menu visible
- [ ] Hero section uses full width
- [ ] Product grid shows 4 columns
- [ ] Footer shows 4 columns
- [ ] No horizontal scroll bar
- [ ] Content centered with appropriate margins

---

#### TC-WEB-071: Tablet Layout (768px)
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CMS-002 |
| **Title** | Verify Tablet Layout at 768px |
| **Priority** | High |

**Test Steps:**
1. Set viewport to 768x1024 (iPad)
2. Load homepage
3. Check responsive adaptations

**Expected Results:**
- [ ] Navigation may show condensed menu or hamburger
- [ ] Product grid shows 2 columns
- [ ] Footer shows 2 columns
- [ ] Images scale appropriately
- [ ] Touch targets minimum 44px

---

#### TC-WEB-072: Mobile Layout (375px)
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CMS-002 |
| **Title** | Verify Mobile Layout at 375px |
| **Priority** | Critical |

**Test Steps:**
1. Set viewport to 375x812 (iPhone)
2. Load homepage
3. Check mobile adaptations
4. Test touch interactions

**Expected Results:**
- [ ] Hamburger menu for navigation
- [ ] Product grid shows 1-2 columns
- [ ] Footer shows stacked single column
- [ ] Hero text size reduced but readable
- [ ] CTA buttons full width
- [ ] No horizontal scrolling
- [ ] All interactive elements touch-friendly (min 44x44px)

---

#### TC-WEB-073: Image Responsive Loading
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CMS-005 |
| **Title** | Verify Responsive Image Loading |
| **Priority** | High |

**Test Steps:**
1. Load page on mobile connection (throttled)
2. Check Network tab in DevTools
3. Verify image sizes loaded
4. Resize browser window
5. Check if different images load

**Expected Results:**
- [ ] Mobile loads smaller image variants (WebP 400w)
- [ ] Desktop loads larger variants (WebP 1200w)
- [ ] srcset attribute present on images
- [ ] Lazy loading applied to below-fold images
- [ ] Total page weight under 2MB on mobile

---

## 7. INTERACTIVE FEATURES TEST CASES

### 7.1 Store Locator

#### TC-WEB-090: Store Locator - Search by Location
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INT-001 |
| **Title** | Verify Store Locator Functionality |
| **Priority** | High |

**Test Steps:**
1. Navigate to /store-locator
2. Allow location permission
3. Check current location detection
4. Enter "Dhaka" in search
5. Verify results display

**Expected Results:**
- [ ] Google Maps loads correctly
- [ ] Current location marker displays (if permission granted)
- [ ] Search field accepts city/area input
- [ ] Results show nearest stores first
- [ ] Each result: Name, Address, Phone, Hours, Distance
- [ ] Clicking result centers map on location
- [ ] "Get Directions" link opens Google Maps

---

#### TC-WEB-091: Store Locator - Filter by Product
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INT-001 |
| **Title** | Verify Store Filter by Product Availability |
| **Priority** | Medium |

**Test Steps:**
1. Open store locator
2. Select product filter: "Organic Milk"
3. Verify results update

**Expected Results:**
- [ ] Product filter dropdown available
- [ ] Selecting filter updates map markers
- [ ] Only stores with selected product shown
- [ ] Clear filter option available

---

### 7.2 Contact Forms

#### TC-WEB-100: General Inquiry Form - Valid Submission
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INT-002 |
| **Title** | Verify Contact Form Submission |
| **Priority** | Critical |

**Test Steps:**
1. Navigate to /contact
2. Fill in all required fields
3. Submit form
4. Check confirmation message

**Expected Results:**
- [ ] Form displays: Name, Email, Phone, Subject, Message fields
- [ ] All required fields marked with asterisk (*)
- [ ] Email validation accepts valid format
- [ ] Phone validation accepts Bangladesh format
- [ ] CAPTCHA displays and validates
- [ ] Success message: "Thank you for your message. We'll respond within 24 hours."
- [ ] Confirmation email sent to user
- [ ] Notification email sent to admin

**Test Data:**
| Field | Value |
|-------|-------|
| Name | Test User |
| Email | test@example.com |
| Phone | 01712345678 |
| Subject | Product Inquiry |
| Message | I want to know about bulk ordering options. |

---

#### TC-WEB-101: Contact Form - Validation Errors
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INT-002 |
| **Title** | Verify Contact Form Validation |
| **Priority** | High |

**Test Steps:**
1. Navigate to contact form
2. Submit with empty fields
3. Submit with invalid email
4. Submit with invalid phone

**Expected Results:**
- [ ] Empty required fields show error: "This field is required"
- [ ] Invalid email shows: "Please enter a valid email address"
- [ ] Invalid phone shows: "Please enter a valid Bangladesh phone number"
- [ ] Form does not submit with errors
- [ ] Error messages are clearly visible

---

### 7.3 Newsletter Subscription

#### TC-WEB-110: Newsletter Signup
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INT-004 |
| **Title** | Verify Newsletter Subscription |
| **Priority** | Medium |

**Test Steps:**
1. Scroll to footer
2. Enter email in newsletter field
3. Click Subscribe
4. Check confirmation
5. Verify double opt-in email

**Expected Results:**
- [ ] Email field accepts valid email
- [ ] Success message: "Please check your email to confirm subscription"
- [ ] Confirmation email received
- [ ] Clicking confirmation link activates subscription
- [ ] Welcome email sent after confirmation

---

## 8. SEO & PERFORMANCE TEST CASES

### 8.1 SEO Validation

#### TC-WEB-130: Meta Tags Verification
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SEO-001 |
| **Title** | Verify Meta Tags and SEO Elements |
| **Priority** | High |

**Test Steps:**
1. Load homepage
2. View page source
3. Check meta tags
4. Verify structured data

**Expected Results:**
- [ ] Title tag: "Smart Dairy | Pure Organic Milk & Dairy Products in Bangladesh"
- [ ] Meta description present and under 160 characters
- [ ] Canonical URL tag present
- [ ] Open Graph tags present (og:title, og:description, og:image)
- [ ] Twitter Card tags present
- [ ] Schema.org structured data (Organization, Product where applicable)
- [ ] Robots meta tag: index, follow
- [ ] Viewport meta tag present

---

#### TC-WEB-131: XML Sitemap
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SEO-001 |
| **Title** | Verify XML Sitemap |
| **Priority** | Medium |

**Test Steps:**
1. Navigate to /sitemap.xml
2. Verify sitemap loads
3. Check content

**Expected Results:**
- [ ] Sitemap accessible at /sitemap.xml
- [ ] Valid XML format
- [ ] Contains URLs for all public pages
- [ ] Last modified dates present
- [ ] Priority and changefreq tags included
- [ ] Sitemap submitted to Google Search Console

---

### 8.2 Performance Testing

#### TC-WEB-140: Page Load Performance
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CMS-002 |
| **Title** | Verify Page Load Speed |
| **Priority** | Critical |

**Test Steps:**
1. Use Google PageSpeed Insights
2. Test mobile and desktop
3. Record Core Web Vitals

**Expected Results:**
| Metric | Target |
|--------|--------|
| Largest Contentful Paint (LCP) | < 2.5s |
| First Input Delay (FID) | < 100ms |
| Cumulative Layout Shift (CLS) | < 0.1 |
| Time to First Byte (TTFB) | < 600ms |
| First Contentful Paint (FCP) | < 1.8s |
| PageSpeed Score | > 90 |

---

#### TC-WEB-141: Mobile Performance
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CMS-002 |
| **Title** | Verify Mobile Performance on 3G |
| **Priority** | High |

**Test Steps:**
1. Open DevTools
2. Set network throttling to "Fast 3G"
3. Clear cache and reload
4. Measure load time

**Expected Results:**
- [ ] First content visible within 3 seconds
- [ ] Interactive within 5 seconds
- [ ] Total page load under 10 seconds
- [ ] No render-blocking resources
- [ ] Critical CSS inlined
- [ ] Images lazy-loaded

---

## 9. ACCESSIBILITY TEST CASES

### 9.1 WCAG 2.1 AA Compliance

#### TC-WEB-160: Keyboard Navigation
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CMS-002 |
| **Title** | Verify Keyboard Accessibility |
| **Priority** | High |

**Test Steps:**
1. Load homepage
2. Navigate using only Tab key
3. Check focus indicators
4. Verify all interactive elements accessible
5. Test Enter and Space activation

**Expected Results:**
- [ ] All interactive elements reachable via Tab
- [ ] Focus indicator visible (outline/border)
- [ ] Logical tab order (top-to-bottom, left-to-right)
- [ ] Enter activates links and buttons
- [ ] Space activates buttons and checkboxes
- [ ] Escape closes modals and menus
- [ ] No keyboard traps

---

#### TC-WEB-161: Screen Reader Compatibility
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CMS-002 |
| **Title** | Verify Screen Reader Support |
| **Priority** | High |

**Test Steps:**
1. Enable NVDA or VoiceOver
2. Navigate homepage
3. Check heading structure
4. Verify image alt text
5. Test form labels

**Expected Results:**
- [ ] Page title announced on load
- [ ] Heading hierarchy logical (H1→H2→H3)
- [ ] All images have descriptive alt text
- [ ] Form inputs have associated labels
- [ ] ARIA landmarks present (header, nav, main, footer)
- [ ] Skip navigation link available
- [ ] Button purposes clearly announced

---

#### TC-WEB-162: Color Contrast
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CMS-002 |
| **Title** | Verify Color Contrast Compliance |
| **Priority** | High |

**Test Steps:**
1. Use contrast checker tool (WebAIM)
2. Check text on background colors
3. Check button text
4. Verify link colors

**Expected Results:**
- [ ] Normal text contrast ratio ≥ 4.5:1
- [ ] Large text contrast ratio ≥ 3:1
- [ ] UI component borders contrast ratio ≥ 3:1
- [ ] Links distinguishable without color
- [ ] No information conveyed by color alone

---

## 10. SECURITY TEST CASES

### 10.1 Web Security

#### TC-WEB-180: HTTPS Enforcement
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | F-001 |
| **Title** | Verify HTTPS Enforcement |
| **Priority** | Critical |

**Test Steps:**
1. Navigate to http://smartdairybd.com
2. Observe redirect
3. Check certificate

**Expected Results:**
- [ ] HTTP requests redirect to HTTPS
- [ ] SSL certificate valid and not expired
- [ ] Certificate issued by trusted CA
- [ ] HSTS header present
- [ ] Mixed content warnings absent

---

#### TC-WEB-181: Content Security Policy
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | F-001 |
| **Title** | Verify Security Headers |
| **Priority** | Critical |

**Test Steps:**
1. Check response headers using DevTools
2. Verify security headers present

**Expected Results:**
- [ ] Content-Security-Policy header present
- [ ] X-Frame-Options: DENY or SAMEORIGIN
- [ ] X-Content-Type-Options: nosniff
- [ ] Referrer-Policy header present
- [ ] Permissions-Policy header present

---

#### TC-WEB-182: Form Security
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-INT-002 |
| **Title** | Verify Form Security Measures |
| **Priority** | Critical |

**Test Steps:**
1. Navigate to contact form
2. Check for CSRF token
3. Attempt XSS in form fields
4. Submit with SQL injection attempt

**Expected Results:**
- [ ] CSRF token present in form
- [ ] XSS attempt sanitized/escaped
- [ ] SQL injection attempt blocked
- [ ] Rate limiting prevents spam
- [ ] reCAPTCHA v2 or v3 implemented

**Test Data (Security Testing):**
| Attack Type | Input | Expected Behavior |
|-------------|-------|-------------------|
| XSS | `<script>alert('xss')</script>` | Sanitized or rejected |
| SQL Injection | `'; DROP TABLE users; --` | Rejected, no error |

---

## 11. REGRESSION TEST SUITE

### 11.1 Smoke Test Checklist

| # | Test | Expected Result |
|---|------|-----------------|
| 1 | Homepage loads | Success |
| 2 | All navigation links work | No 404 errors |
| 3 | Language switch works | Bengali displays |
| 4 | Contact form validates | Errors display correctly |
| 5 | Mobile menu works | Hamburger expands |
| 6 | Product pages load | Images and content display |
| 7 | Footer links work | Navigate correctly |
| 8 | Search returns results | Results display |

### 11.2 Critical Path Test Cases

| Test Case ID | Description | Priority |
|--------------|-------------|----------|
| TC-WEB-CP001 | Homepage → Products → Product Detail → Add to Cart | Critical |
| TC-WEB-CP002 | Homepage → About → Contact Form → Submit | Critical |
| TC-WEB-CP003 | Homepage → Language Switch (BN) → Browse → Switch Back | Critical |
| TC-WEB-CP004 | Mobile: Hamburger → Products → Product → Footer Links | Critical |

---

## 12. APPENDICES

### Appendix A: Test Data Sets

**Sample Product Data:**
| SKU | Name | Category | Price |
|-----|------|----------|-------|
| SD-MILK-001 | Saffron Organic Milk 1L | Fresh Milk | ৳95 |
| SD-YOG-001 | Saffron Sweet Yogurt 500g | Yogurt | ৳120 |
| SD-GHEE-001 | Pure Desi Ghee 250ml | Ghee | ৳350 |

**Sample Contact Form Data:**
| Field | Valid | Invalid |
|-------|-------|---------|
| Name | John Doe | (empty) |
| Email | john@example.com | invalid-email |
| Phone | 01712345678 | 12345 |

### Appendix B: Defect Severity Guidelines

| Severity | Definition | Examples |
|----------|------------|----------|
| Critical | System unusable, data loss | Site down, payment errors |
| High | Major feature broken | Navigation broken, forms fail |
| Medium | Feature impaired | Layout issues, slow loading |
| Low | Cosmetic issues | Minor alignment, typos |

### Appendix C: Test Execution Log Template

| TC ID | Executed By | Date | Environment | Status | Notes |
|-------|-------------|------|-------------|--------|-------|
| TC-WEB-001 | [Name] | [Date] | QA | Pass | |

---

**END OF TEST CASES - PUBLIC WEBSITE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | June 1, 2026 | QA Engineer | Initial version |
