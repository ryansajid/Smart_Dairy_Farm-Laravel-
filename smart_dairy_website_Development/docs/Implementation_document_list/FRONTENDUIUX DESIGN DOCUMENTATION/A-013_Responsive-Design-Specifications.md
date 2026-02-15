# A-013: Responsive Design Specifications

**Document Version:** 1.0
**Last Updated:** 2026-02-01
**Author:** Claude Code
**Status:** Draft
**Project:** Smart Dairy Ltd. - Digital Transformation

---

## Table of Contents

1. [Overview](#1-overview)
2. [Breakpoint System](#2-breakpoint-system)
3. [Mobile-First Approach](#3-mobile-first-approach)
4. [Responsive Grid](#4-responsive-grid)
5. [Responsive Typography](#5-responsive-typography)
6. [Responsive Images](#6-responsive-images)
7. [Responsive Navigation](#7-responsive-navigation)
8. [Touch Targets & Gestures](#8-touch-targets--gestures)
9. [Responsive Tables](#9-responsive-tables)
10. [Responsive Forms](#10-responsive-forms)
11. [Device Testing](#11-device-testing)
12. [Performance Optimization](#12-performance-optimization)
13. [Implementation Guidelines](#13-implementation-guidelines)
14. [Appendix](#14-appendix)

---

## 1. Overview

### 1.1 Document Purpose

This document defines responsive design standards for all Smart Dairy digital properties, ensuring optimal user experience across all device types and screen sizes.

### 1.2 Responsive Design Principles

**Mobile-First** - Design for mobile screens first, then enhance for larger screens
**Content-First** - Prioritize content hierarchy across all breakpoints
**Progressive Enhancement** - Base functionality works everywhere, enhanced features for capable devices
**Performance** - Fast loading on all devices, especially mobile networks
**Touch-Friendly** - Interfaces optimized for touch interaction

### 1.3 Related Documents

- [A-001: UI/UX Design System](A-001_UI-UX-Design-System.md)
- [A-011: Component Library Documentation](A-011_Component-Library-Documentation.md)
- [A-012: Accessibility Compliance Guide](A-012_Accessibility-Compliance-Guide.md)

---

## 2. Breakpoint System

### 2.1 Standard Breakpoints

We use a 6-tier breakpoint system based on common device widths:

| Name | Abbreviation | Min Width | Max Width | Target Devices |
|------|--------------|-----------|-----------|----------------|
| **Extra Small** | XS | 0px | 575px | Small phones (iPhone SE, Android) |
| **Small** | SM | 576px | 767px | Large phones (iPhone 14, Pixel) |
| **Medium** | MD | 768px | 991px | Tablets (iPad, Android tablets) |
| **Large** | LG | 992px | 1199px | Small laptops, landscape tablets |
| **Extra Large** | XL | 1200px | 1439px | Desktops, large laptops |
| **Extra Extra Large** | XXL | 1440px | ∞ | Large desktops, 4K displays |

### 2.2 CSS Media Queries

```css
/* Mobile First: Base styles are for XS (0-575px) */

/* SM: Large phones */
@media (min-width: 576px) {
  /* Styles for 576px and up */
}

/* MD: Tablets */
@media (min-width: 768px) {
  /* Styles for 768px and up */
}

/* LG: Small laptops */
@media (min-width: 992px) {
  /* Styles for 992px and up */
}

/* XL: Desktops */
@media (min-width: 1200px) {
  /* Styles for 1200px and up */
}

/* XXL: Large desktops */
@media (min-width: 1440px) {
  /* Styles for 1440px and up */
}
```

### 2.3 Breakpoint Usage Guidelines

**XS (0-575px) - Mobile:**
- Single column layouts
- Full-width components
- Simplified navigation (hamburger menu)
- Stacked form fields
- Large touch targets (min 44x44px)

**SM (576-767px) - Large Mobile:**
- Still mostly single column
- Slightly increased padding/margins
- May introduce 2-column grids for simple content

**MD (768-991px) - Tablet:**
- 2-column layouts become common
- Sidebar layouts possible
- Desktop-like navigation appears
- Hybrid touch/mouse interaction

**LG (992-1199px) - Desktop:**
- Full multi-column layouts
- Complex navigation (mega menus)
- Sidebar + main content
- Mouse-optimized interactions

**XL (1200-1439px) - Large Desktop:**
- Wider containers
- More whitespace
- 3-4 column product grids

**XXL (1440px+) - Extra Large:**
- Maximum container width
- Generous spacing
- Multi-column grids (4+)

### 2.4 Container Widths

```css
.container {
  width: 100%;
  margin: 0 auto;
  padding: 0 16px; /* XS */
}

@media (min-width: 576px) {
  .container {
    max-width: 540px;
    padding: 0 20px;
  }
}

@media (min-width: 768px) {
  .container {
    max-width: 720px;
    padding: 0 24px;
  }
}

@media (min-width: 992px) {
  .container {
    max-width: 960px;
  }
}

@media (min-width: 1200px) {
  .container {
    max-width: 1140px;
    padding: 0 32px;
  }
}

@media (min-width: 1440px) {
  .container {
    max-width: 1320px;
    padding: 0 48px;
  }
}
```

---

## 3. Mobile-First Approach

### 3.1 Why Mobile-First?

**Advantages:**
- Forces content prioritization
- Better performance (progressive enhancement)
- Easier to scale up than down
- Aligns with mobile-majority traffic in Bangladesh

**Implementation:**
- Write base styles for smallest screen first
- Add complexity with `min-width` media queries
- Avoid `max-width` queries when possible

### 3.2 Mobile-First Example

```css
/* ❌ BAD: Desktop-first (avoid) */
.product-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 32px;
}

@media (max-width: 991px) {
  .product-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
  }
}

@media (max-width: 575px) {
  .product-grid {
    grid-template-columns: 1fr;
  }
}

/* ✅ GOOD: Mobile-first */
.product-grid {
  display: grid;
  grid-template-columns: 1fr; /* Mobile: 1 column */
  gap: 16px;
}

@media (min-width: 576px) {
  .product-grid {
    grid-template-columns: repeat(2, 1fr); /* 2 columns on larger phones */
  }
}

@media (min-width: 992px) {
  .product-grid {
    grid-template-columns: repeat(3, 1fr); /* 3 columns on desktop */
    gap: 24px;
  }
}

@media (min-width: 1440px) {
  .product-grid {
    grid-template-columns: repeat(4, 1fr); /* 4 columns on large screens */
    gap: 32px;
  }
}
```

### 3.3 Content Priority

**Mobile (must-have):**
- Core product information
- Primary CTAs
- Essential navigation
- Basic images (optimized)

**Desktop (nice-to-have):**
- Detailed product specs
- Multiple image views
- Sidebar content
- Decorative elements
- Advanced filters

---

## 4. Responsive Grid

### 4.1 12-Column Grid System

```css
.grid {
  display: grid;
  grid-template-columns: repeat(12, 1fr);
  gap: 24px;
  padding: 0 16px;
}

/* Column spans */
.col-1 { grid-column: span 1; }
.col-2 { grid-column: span 2; }
.col-3 { grid-column: span 3; }
.col-4 { grid-column: span 4; }
.col-6 { grid-column: span 6; }
.col-8 { grid-column: span 8; }
.col-9 { grid-column: span 9; }
.col-12 { grid-column: span 12; }

/* Mobile: all columns full width */
@media (max-width: 767px) {
  .col-1,
  .col-2,
  .col-3,
  .col-4,
  .col-6,
  .col-8,
  .col-9 {
    grid-column: span 12;
  }
}
```

### 4.2 Responsive Column Classes

```css
/* Breakpoint-specific columns */
.col-sm-6 { grid-column: span 12; } /* Default: full width */

@media (min-width: 576px) {
  .col-sm-6 { grid-column: span 6; }
}

.col-md-4 { grid-column: span 12; }

@media (min-width: 768px) {
  .col-md-4 { grid-column: span 4; }
}

.col-lg-3 { grid-column: span 12; }

@media (min-width: 992px) {
  .col-lg-3 { grid-column: span 3; }
}
```

### 4.3 Grid Usage Example

```html
<!-- Responsive 3-column layout -->
<div class="grid">
  <!-- Full width on mobile, 6 cols on tablet, 4 cols on desktop -->
  <div class="col-12 col-md-6 col-lg-4">Column 1</div>
  <div class="col-12 col-md-6 col-lg-4">Column 2</div>
  <div class="col-12 col-md-12 col-lg-4">Column 3</div>
</div>

<!-- Sidebar layout: Full width on mobile, sidebar on desktop -->
<div class="grid">
  <aside class="col-12 col-lg-3">Sidebar</aside>
  <main class="col-12 col-lg-9">Main content</main>
</div>
```

---

## 5. Responsive Typography

### 5.1 Fluid Font Sizing

Typography scales across breakpoints using responsive font sizes:

```css
html {
  font-size: 14px; /* Base for mobile */
}

@media (min-width: 768px) {
  html {
    font-size: 15px; /* Tablet */
  }
}

@media (min-width: 1200px) {
  html {
    font-size: 16px; /* Desktop */
  }
}
```

### 5.2 Responsive Heading Sizes

```css
h1 {
  font-size: 2rem; /* Mobile: 28px (14px base × 2) */
  line-height: 1.2;
}

@media (min-width: 768px) {
  h1 {
    font-size: 2.5rem; /* Tablet: 37.5px */
  }
}

@media (min-width: 1200px) {
  h1 {
    font-size: 3rem; /* Desktop: 48px */
  }
}

h2 {
  font-size: 1.75rem; /* Mobile: ~24px */
}

@media (min-width: 768px) {
  h2 {
    font-size: 2rem; /* Tablet: ~30px */
  }
}

@media (min-width: 1200px) {
  h2 {
    font-size: 2.375rem; /* Desktop: 38px */
  }
}
```

### 5.3 Clamp() for Fluid Typography

Modern approach using CSS `clamp()`:

```css
h1 {
  font-size: clamp(2rem, 5vw, 3rem);
  /* Min: 2rem (32px), Preferred: 5% of viewport, Max: 3rem (48px) */
}

h2 {
  font-size: clamp(1.5rem, 4vw, 2.375rem);
}

p {
  font-size: clamp(0.875rem, 2vw, 1rem);
}
```

### 5.4 Line Length Control

Optimal reading experience: 45-75 characters per line

```css
p, .text-content {
  max-width: 65ch; /* 65 characters */
}

@media (min-width: 1200px) {
  p, .text-content {
    max-width: 75ch;
  }
}
```

---

## 6. Responsive Images

### 6.1 Responsive Image Techniques

#### Fluid Images (Default)
```css
img {
  max-width: 100%;
  height: auto;
  display: block;
}
```

#### Srcset for Resolution Switching
```html
<img
  src="product-800.jpg"
  srcset="
    product-400.jpg 400w,
    product-800.jpg 800w,
    product-1200.jpg 1200w,
    product-1600.jpg 1600w
  "
  sizes="
    (max-width: 575px) 100vw,
    (max-width: 991px) 50vw,
    33vw
  "
  alt="Organic Milk"
>
```

#### Picture Element for Art Direction
```html
<picture>
  <!-- Mobile: Square crop -->
  <source
    media="(max-width: 767px)"
    srcset="product-mobile-square.jpg"
  >

  <!-- Tablet: 4:3 crop -->
  <source
    media="(max-width: 1199px)"
    srcset="product-tablet.jpg"
  >

  <!-- Desktop: Wide crop -->
  <img
    src="product-desktop.jpg"
    alt="Organic Milk"
  >
</picture>
```

### 6.2 WebP with Fallback

```html
<picture>
  <source srcset="image.webp" type="image/webp">
  <source srcset="image.jpg" type="image/jpeg">
  <img src="image.jpg" alt="Product">
</picture>
```

### 6.3 Lazy Loading

```html
<img
  src="placeholder.jpg"
  data-src="full-image.jpg"
  loading="lazy"
  alt="Product"
>
```

### 6.4 Background Images

```css
.hero {
  background-image: url('hero-mobile.jpg');
  background-size: cover;
  background-position: center;
}

@media (min-width: 768px) {
  .hero {
    background-image: url('hero-tablet.jpg');
  }
}

@media (min-width: 1200px) {
  .hero {
    background-image: url('hero-desktop.jpg');
  }
}

/* Or use image-set for resolution */
.hero {
  background-image: image-set(
    url('hero.jpg') 1x,
    url('hero@2x.jpg') 2x
  );
}
```

---

## 7. Responsive Navigation

### 7.1 Mobile Navigation (Hamburger Menu)

```html
<!-- Mobile: Hamburger menu -->
<header class="navbar">
  <a href="/" class="navbar-brand">
    <img src="logo.svg" alt="Smart Dairy">
  </a>

  <button class="navbar-toggle" aria-label="Toggle menu" aria-expanded="false">
    <span class="hamburger"></span>
  </button>

  <nav class="navbar-menu" aria-label="Main navigation">
    <a href="/products" class="nav-link">Products</a>
    <a href="/farm" class="nav-link">The Farm</a>
    <a href="/about" class="nav-link">About</a>
    <a href="/contact" class="nav-link">Contact</a>
  </nav>
</header>
```

```css
/* Mobile: Hidden menu */
.navbar-menu {
  position: fixed;
  top: 0;
  right: -100%;
  width: 80%;
  max-width: 320px;
  height: 100vh;
  background-color: #FFFFFF;
  padding: 80px 24px 24px;
  box-shadow: -2px 0 8px rgba(0, 0, 0, 0.1);
  transition: right 300ms ease;
  z-index: 1000;
  overflow-y: auto;
}

.navbar-menu.open {
  right: 0;
}

.navbar-toggle {
  display: block;
  background: none;
  border: none;
  padding: 8px;
  cursor: pointer;
}

.hamburger {
  display: block;
  width: 24px;
  height: 2px;
  background-color: var(--color-neutral-800);
  position: relative;
  transition: background-color 200ms;
}

.hamburger::before,
.hamburger::after {
  content: '';
  position: absolute;
  width: 24px;
  height: 2px;
  background-color: var(--color-neutral-800);
  transition: transform 200ms;
}

.hamburger::before {
  top: -8px;
}

.hamburger::after {
  bottom: -8px;
}

/* Hamburger animation (open state) */
.navbar-toggle[aria-expanded="true"] .hamburger {
  background-color: transparent;
}

.navbar-toggle[aria-expanded="true"] .hamburger::before {
  transform: translateY(8px) rotate(45deg);
}

.navbar-toggle[aria-expanded="true"] .hamburger::after {
  transform: translateY(-8px) rotate(-45deg);
}

/* Nav links (mobile: stacked) */
.nav-link {
  display: block;
  padding: 16px 0;
  color: var(--color-neutral-800);
  text-decoration: none;
  font-weight: 600;
  border-bottom: 1px solid var(--color-neutral-200);
}

/* Desktop: Horizontal menu */
@media (min-width: 992px) {
  .navbar-toggle {
    display: none;
  }

  .navbar-menu {
    position: static;
    width: auto;
    max-width: none;
    height: auto;
    padding: 0;
    box-shadow: none;
    display: flex;
    gap: 32px;
  }

  .nav-link {
    display: inline-block;
    padding: 8px 0;
    border-bottom: 2px solid transparent;
  }

  .nav-link:hover {
    border-bottom-color: var(--color-primary);
  }
}
```

### 7.2 Mobile Bottom Navigation

For mobile apps and mobile-optimized sites:

```html
<nav class="bottom-nav">
  <a href="/" class="bottom-nav-item active">
    <i class="fas fa-home"></i>
    <span>Home</span>
  </a>
  <a href="/products" class="bottom-nav-item">
    <i class="fas fa-box"></i>
    <span>Products</span>
  </a>
  <a href="/cart" class="bottom-nav-item">
    <i class="fas fa-shopping-cart"></i>
    <span>Cart</span>
  </a>
  <a href="/account" class="bottom-nav-item">
    <i class="fas fa-user"></i>
    <span>Account</span>
  </a>
</nav>
```

```css
.bottom-nav {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  display: flex;
  background-color: #FFFFFF;
  border-top: 1px solid var(--color-neutral-300);
  padding: 8px 0;
  z-index: 100;
}

.bottom-nav-item {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
  padding: 8px;
  color: var(--color-neutral-600);
  text-decoration: none;
  font-size: 0.75rem;
}

.bottom-nav-item i {
  font-size: 20px;
}

.bottom-nav-item.active {
  color: var(--color-primary);
}

/* Hide on desktop */
@media (min-width: 992px) {
  .bottom-nav {
    display: none;
  }
}
```

---

## 8. Touch Targets & Gestures

### 8.1 Minimum Touch Target Sizes

**Requirement:** 44x44px minimum (iOS/Android guidelines)
**Recommendation:** 48x48px for better usability

```css
/* Buttons */
.btn {
  min-height: 44px;
  min-width: 44px;
  padding: 12px 24px;
}

/* Icon buttons */
.btn-icon {
  width: 48px;
  height: 48px;
}

/* Links in navigation */
.nav-link {
  padding: 12px 16px; /* Ensures 44px+ height */
}

/* Form inputs */
.form-input,
.form-select {
  min-height: 44px;
  padding: 12px 16px;
}

/* Checkboxes and radios */
.form-checkbox,
.form-radio-input {
  width: 24px;
  height: 24px;
  /* But add larger clickable area via padding on label */
}

.form-check-label {
  padding: 10px 0; /* Expands clickable area */
}
```

### 8.2 Touch-Friendly Spacing

```css
/* Mobile: Larger gaps between interactive elements */
.button-group {
  gap: 12px; /* Mobile */
}

@media (min-width: 992px) {
  .button-group {
    gap: 8px; /* Desktop: can be tighter */
  }
}

/* Product cards: More spacing on mobile */
.product-grid {
  gap: 16px; /* Mobile */
}

@media (min-width: 992px) {
  .product-grid {
    gap: 24px; /* Desktop */
  }
}
```

### 8.3 Swipe Gestures

```html
<!-- Swipeable image carousel -->
<div class="carousel" data-swipeable>
  <div class="carousel-track">
    <img src="img1.jpg" alt="Product 1">
    <img src="img2.jpg" alt="Product 2">
    <img src="img3.jpg" alt="Product 3">
  </div>
</div>
```

```css
.carousel {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
  scroll-snap-type: x mandatory;
}

.carousel-track {
  display: flex;
  gap: 16px;
}

.carousel-track img {
  scroll-snap-align: start;
  flex-shrink: 0;
  width: 80vw;
}

@media (min-width: 768px) {
  .carousel-track img {
    width: 300px;
  }
}
```

---

## 9. Responsive Tables

### 9.1 Horizontal Scroll (Simple Tables)

```html
<div class="table-container">
  <table class="table">
    <!-- Table content -->
  </table>
</div>
```

```css
.table-container {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.table {
  min-width: 600px; /* Ensure table doesn't crush */
}

@media (min-width: 768px) {
  .table {
    min-width: 100%;
  }
}
```

### 9.2 Stacked Rows (Mobile-Friendly)

```html
<table class="table table-responsive">
  <thead>
    <tr>
      <th>Order ID</th>
      <th>Date</th>
      <th>Total</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td data-label="Order ID">#12345</td>
      <td data-label="Date">2026-01-28</td>
      <td data-label="Total">৳240</td>
      <td data-label="Status"><span class="badge badge-success">Delivered</span></td>
    </tr>
  </tbody>
</table>
```

```css
@media (max-width: 767px) {
  .table-responsive thead {
    display: none;
  }

  .table-responsive tbody,
  .table-responsive tr,
  .table-responsive td {
    display: block;
  }

  .table-responsive tr {
    margin-bottom: 24px;
    padding: 16px;
    border: 1px solid var(--color-neutral-300);
    border-radius: 8px;
  }

  .table-responsive td {
    text-align: right;
    padding: 8px 0;
    border-bottom: 1px solid var(--color-neutral-200);
  }

  .table-responsive td:last-child {
    border-bottom: none;
  }

  .table-responsive td::before {
    content: attr(data-label);
    float: left;
    font-weight: 700;
    color: var(--color-neutral-700);
  }
}
```

### 9.3 Card Layout (Complex Tables)

For very complex tables, convert to card layout on mobile:

```css
@media (max-width: 767px) {
  .data-table {
    display: none; /* Hide table */
  }

  .data-cards {
    display: block; /* Show card layout */
  }
}

@media (min-width: 768px) {
  .data-table {
    display: table;
  }

  .data-cards {
    display: none;
  }
}
```

---

## 10. Responsive Forms

### 10.1 Stacked Form Fields (Mobile)

```html
<form class="form-responsive">
  <div class="form-row">
    <div class="form-col">
      <label class="form-label">First Name</label>
      <input type="text" class="form-input">
    </div>
    <div class="form-col">
      <label class="form-label">Last Name</label>
      <input type="text" class="form-input">
    </div>
  </div>
</form>
```

```css
/* Mobile: Stacked */
.form-row {
  display: flex;
  flex-direction: column;
  gap: 24px;
  margin-bottom: 24px;
}

.form-col {
  flex: 1;
}

/* Desktop: Side-by-side */
@media (min-width: 768px) {
  .form-row {
    flex-direction: row;
    gap: 16px;
  }
}
```

### 10.2 Mobile-Optimized Inputs

```css
/* Prevent zoom on input focus (iOS) */
input[type="text"],
input[type="email"],
input[type="tel"],
input[type="number"],
select,
textarea {
  font-size: 16px; /* Minimum to prevent zoom on iOS */
}

/* Larger touch target */
.form-input {
  min-height: 44px;
  padding: 12px 16px;
}

/* Appropriate keyboard types */
```

```html
<input type="email" inputmode="email"> <!-- Email keyboard -->
<input type="tel" inputmode="tel"> <!-- Phone keyboard -->
<input type="number" inputmode="numeric"> <!-- Numeric keyboard -->
<input type="text" inputmode="search"> <!-- Search keyboard -->
```

---

## 11. Device Testing

### 11.1 Priority Devices for Testing

**Mobile (Must Test):**
- iPhone 14 (390x844)
- iPhone SE (375x667)
- Samsung Galaxy S21 (360x800)
- Google Pixel 6 (412x915)

**Tablet (Must Test):**
- iPad (810x1080)
- iPad Pro (1024x1366)
- Android Tablet (800x1280)

**Desktop (Must Test):**
- 1366x768 (Common laptop)
- 1920x1080 (Full HD)
- 2560x1440 (2K)

### 11.2 Testing Tools

**Browser DevTools:**
- Chrome DevTools (Device Mode)
- Firefox Responsive Design Mode
- Safari Web Inspector

**Online Tools:**
- BrowserStack
- LambdaTest
- Responsively App

**Physical Devices:**
- Maintain device lab with common devices
- Test on actual Bangladesh market devices

### 11.3 Testing Checklist

**Layout:**
- [ ] No horizontal scrolling (unintended)
- [ ] Content readable without zooming
- [ ] Images don't overflow
- [ ] No text cutoff

**Interaction:**
- [ ] All buttons tappable (44x44px min)
- [ ] Forms usable with touch
- [ ] Navigation accessible
- [ ] Gestures work (swipe, pinch)

**Performance:**
- [ ] Page loads < 3 seconds on 3G
- [ ] Images optimized
- [ ] No layout shift (CLS)

**Typography:**
- [ ] Text readable (min 16px body)
- [ ] Appropriate line length
- [ ] Proper contrast ratios

---

## 12. Performance Optimization

### 12.1 Mobile Performance Strategies

**Reduce Payload:**
```html
<!-- Responsive images -->
<img srcset="small.jpg 400w, large.jpg 800w" sizes="100vw">

<!-- Lazy loading -->
<img loading="lazy" src="image.jpg">

<!-- WebP format -->
<picture>
  <source srcset="image.webp" type="image/webp">
  <img src="image.jpg">
</picture>
```

**Critical CSS:**
```html
<!-- Inline critical CSS -->
<style>
  /* Critical above-the-fold styles */
  .header { /* ... */ }
</style>

<!-- Defer non-critical CSS -->
<link rel="preload" href="styles.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
```

**Font Loading:**
```css
/* Display text immediately with fallback */
@font-face {
  font-family: 'Open Sans';
  src: url('opensans.woff2');
  font-display: swap;
}
```

### 12.2 Connection-Aware Loading

```javascript
// Check connection quality
if (navigator.connection) {
  const connection = navigator.connection.effectiveType;

  if (connection === '4g') {
    // Load high-quality images
    loadHighResImages();
  } else {
    // Load optimized images
    loadLowResImages();
  }
}
```

---

## 13. Implementation Guidelines

### 13.1 Viewport Meta Tag

Always include in `<head>`:

```html
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
```

**Don't:**
```html
<!-- ❌ Prevents zooming (accessibility issue) -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
```

### 13.2 CSS Units Best Practices

**Use:**
- `rem` for font sizes (scalable, accessible)
- `%` for widths (relative)
- `vw/vh` for viewport-relative sizing
- `px` for borders, shadows (precision)

**Avoid:**
- `em` for font sizes (compounding issues)
- Fixed `px` widths (except max-width)

### 13.3 Testing Before Launch

**Required:**
1. Test all breakpoints (6 breakpoints)
2. Rotate device (portrait/landscape)
3. Test with slow connection (3G)
4. Verify touch targets (44px min)
5. Check text zoom (200%)
6. Test with screen reader

---

## 14. Appendix

### 14.1 Common Responsive Patterns

**Pattern 1: Column Drop**
```css
.layout {
  display: flex;
  flex-wrap: wrap;
}

.column {
  flex: 1 1 100%; /* Mobile: full width */
}

@media (min-width: 768px) {
  .column {
    flex: 1 1 50%; /* Tablet: 2 columns */
  }
}

@media (min-width: 992px) {
  .column {
    flex: 1 1 33.33%; /* Desktop: 3 columns */
  }
}
```

**Pattern 2: Mostly Fluid**
```css
.container {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 16px;
}
```

**Pattern 3: Off Canvas**
```css
/* Mobile: Hidden sidebar */
.sidebar {
  position: fixed;
  left: -250px;
  width: 250px;
  transition: left 300ms;
}

.sidebar.open {
  left: 0;
}

/* Desktop: Always visible */
@media (min-width: 992px) {
  .sidebar {
    position: static;
    width: 250px;
  }
}
```

### 14.2 Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0 | 2026-02-01 | Initial release | Claude Code |

### 14.3 References

- [A-001: UI/UX Design System](A-001_UI-UX-Design-System.md)
- [A-011: Component Library](A-011_Component-Library-Documentation.md)
- [MDN: Responsive Design](https://developer.mozilla.org/en-US/docs/Learn/CSS/CSS_layout/Responsive_Design)
- [Google: Responsive Web Design Basics](https://web.dev/responsive-web-design-basics/)

---

**End of Document**

*Responsive design is essential for reaching all users. Test thoroughly across devices.*
