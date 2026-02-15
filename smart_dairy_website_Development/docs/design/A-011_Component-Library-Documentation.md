# A-011: Component Library Documentation

**Document Version:** 1.0
**Last Updated:** 2026-02-01
**Author:** Claude Code
**Status:** Draft
**Project:** Smart Dairy Ltd. - Digital Transformation

---

## Table of Contents

1. [Overview](#1-overview)
2. [Component Architecture](#2-component-architecture)
3. [Buttons](#3-buttons)
4. [Form Components](#4-form-components)
5. [Navigation Components](#5-navigation-components)
6. [Cards & Containers](#6-cards--containers)
7. [Data Display](#7-data-display)
8. [Feedback Components](#8-feedback-components)
9. [Media Components](#9-media-components)
10. [Layout Components](#10-layout-components)
11. [Utility Components](#11-utility-components)
12. [Usage Guidelines](#12-usage-guidelines)
13. [Accessibility Notes](#13-accessibility-notes)
14. [Appendix](#14-appendix)

---

## 1. Overview

### 1.1 Document Purpose

This Component Library serves as a comprehensive catalog of all reusable UI components for the Smart Dairy digital ecosystem. Each component includes specifications, code examples, variants, states, and accessibility requirements.

### 1.2 Component Principles

**Reusability** - Components are designed to be used across multiple contexts
**Consistency** - All components follow the same design system
**Accessibility** - WCAG 2.1 Level AA compliance built-in
**Responsiveness** - Components adapt across all breakpoints
**Modularity** - Components can be composed together

### 1.3 Related Documents

- [A-001: UI/UX Design System](A-001_UI-UX-Design-System.md)
- [A-012: Accessibility Compliance Guide](A-012_Accessibility-Compliance-Guide.md)
- [A-013: Responsive Design Specifications](A-013_Responsive-Design-Specifications.md)

---

## 2. Component Architecture

### 2.1 Component Structure

All components follow this naming convention:

```
ComponentName
├── Variants (size, color, style variations)
├── States (default, hover, focus, active, disabled, loading, error)
├── Props/Parameters
├── Accessibility Attributes
└── Usage Examples
```

### 2.2 Technology Stack

**HTML** - Semantic HTML5
**CSS** - Custom properties (CSS variables), Flexbox, Grid
**JavaScript** - Vanilla JS, ES6+ (framework-agnostic)
**Frameworks** - Can be adapted for React, Vue, Angular, or Odoo OWL

### 2.3 File Organization

```
/components/
├── /buttons/
│   ├── button.html
│   ├── button.css
│   └── button.js
├── /forms/
│   ├── input.html
│   ├── input.css
│   └── input.js
└── ...
```

---

## 3. Buttons

### 3.1 Button Variants

#### Primary Button

**Purpose:** Main call-to-action, highest emphasis
**Usage:** Form submissions, primary actions, conversions

**Specifications:**
- Background: Primary Green (#2E7D32)
- Text: White (#FFFFFF)
- Border: 2px solid Primary Green
- Padding: 12px 24px
- Border Radius: 8px
- Font: Open Sans SemiBold, 16px
- Min Height: 44px (touch-friendly)

**HTML:**
```html
<button class="btn btn-primary">
  Add to Cart
</button>

<!-- With icon -->
<button class="btn btn-primary">
  <i class="fas fa-shopping-cart"></i>
  Add to Cart
</button>
```

**CSS:**
```css
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px 24px;
  border-radius: 8px;
  font-family: var(--font-body);
  font-size: 1rem;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: all 200ms var(--ease-out);
  min-height: 44px;
  border: 2px solid transparent;
}

.btn-primary {
  background-color: var(--color-primary);
  color: #FFFFFF;
  border-color: var(--color-primary);
}

.btn-primary:hover {
  background-color: var(--color-primary-light);
  border-color: var(--color-primary-light);
  box-shadow: var(--shadow-2);
}

.btn-primary:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.25);
}

.btn-primary:active {
  background-color: var(--color-primary-dark);
  transform: scale(0.98);
}

.btn-primary:disabled {
  background-color: var(--color-neutral-300);
  border-color: var(--color-neutral-300);
  color: var(--color-neutral-500);
  cursor: not-allowed;
  box-shadow: none;
}
```

#### Secondary Button

**Purpose:** Secondary actions, less emphasis than primary
**Usage:** Cancel actions, alternative options

**Specifications:**
- Background: Transparent
- Text: Primary Green (#2E7D32)
- Border: 2px solid Primary Green
- Same padding/sizing as primary

**HTML:**
```html
<button class="btn btn-secondary">
  Learn More
</button>
```

**CSS:**
```css
.btn-secondary {
  background-color: transparent;
  color: var(--color-primary);
  border-color: var(--color-primary);
}

.btn-secondary:hover {
  background-color: rgba(46, 125, 50, 0.08);
  border-color: var(--color-primary-light);
}

.btn-secondary:active {
  background-color: rgba(46, 125, 50, 0.15);
}
```

#### Tertiary Button (Ghost)

**Purpose:** Low emphasis actions
**Usage:** Navigation links, text buttons

**HTML:**
```html
<button class="btn btn-tertiary">
  Skip
</button>
```

**CSS:**
```css
.btn-tertiary {
  background-color: transparent;
  color: var(--color-primary);
  border: none;
  padding: 8px 16px;
}

.btn-tertiary:hover {
  background-color: rgba(46, 125, 50, 0.08);
}
```

#### Danger Button

**Purpose:** Destructive actions
**Usage:** Delete, remove, cancel orders

**HTML:**
```html
<button class="btn btn-danger">
  Delete Account
</button>
```

**CSS:**
```css
.btn-danger {
  background-color: var(--color-error);
  color: #FFFFFF;
  border-color: var(--color-error);
}

.btn-danger:hover {
  background-color: #D32F2F;
}
```

### 3.2 Button Sizes

```html
<!-- Small -->
<button class="btn btn-primary btn-sm">Small</button>

<!-- Default (medium) -->
<button class="btn btn-primary">Default</button>

<!-- Large -->
<button class="btn btn-primary btn-lg">Large</button>
```

```css
.btn-sm {
  padding: 8px 16px;
  font-size: 0.875rem;
  min-height: 36px;
}

.btn-lg {
  padding: 16px 32px;
  font-size: 1.125rem;
  min-height: 52px;
}
```

### 3.3 Button States

```html
<!-- Loading State -->
<button class="btn btn-primary btn-loading">
  <span class="btn-spinner"></span>
  Processing...
</button>

<!-- Disabled State -->
<button class="btn btn-primary" disabled>
  Unavailable
</button>

<!-- Success State (after action) -->
<button class="btn btn-primary btn-success">
  <i class="fas fa-check"></i>
  Added!
</button>
```

**Loading Spinner CSS:**
```css
.btn-loading {
  position: relative;
  color: transparent;
  pointer-events: none;
}

.btn-spinner {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top-color: #FFFFFF;
  border-radius: 50%;
  animation: spin 600ms linear infinite;
}

@keyframes spin {
  to { transform: translate(-50%, -50%) rotate(360deg); }
}
```

### 3.4 Icon Buttons

```html
<!-- Icon only -->
<button class="btn btn-icon" aria-label="Add to wishlist">
  <i class="fas fa-heart"></i>
</button>

<!-- Icon with text -->
<button class="btn btn-primary">
  <i class="fas fa-download"></i>
  Download
</button>
```

```css
.btn-icon {
  width: 44px;
  height: 44px;
  padding: 0;
  border-radius: 50%;
  background-color: transparent;
  color: var(--color-neutral-700);
  border: 1px solid var(--color-neutral-400);
}

.btn-icon:hover {
  background-color: var(--color-neutral-100);
  border-color: var(--color-neutral-500);
}
```

### 3.5 Button Groups

```html
<div class="btn-group">
  <button class="btn btn-secondary active">Day</button>
  <button class="btn btn-secondary">Week</button>
  <button class="btn btn-secondary">Month</button>
</div>
```

```css
.btn-group {
  display: inline-flex;
  gap: 0;
}

.btn-group .btn {
  border-radius: 0;
  border-right-width: 0;
}

.btn-group .btn:first-child {
  border-radius: 8px 0 0 8px;
}

.btn-group .btn:last-child {
  border-radius: 0 8px 8px 0;
  border-right-width: 2px;
}

.btn-group .btn.active {
  background-color: var(--color-primary);
  color: #FFFFFF;
  border-color: var(--color-primary);
  z-index: 1;
}
```

### 3.6 Accessibility

All buttons must include:
- `role="button"` (if not using `<button>` element)
- `aria-label` for icon-only buttons
- `aria-pressed` for toggle buttons
- `disabled` attribute when not interactive
- Visible focus indicator (`:focus` styles)
- Minimum 44x44px touch target

---

## 4. Form Components

### 4.1 Text Input

**HTML:**
```html
<div class="form-group">
  <label for="email" class="form-label">
    Email Address
    <span class="form-required">*</span>
  </label>
  <input
    type="email"
    id="email"
    class="form-input"
    placeholder="you@example.com"
    aria-describedby="email-help"
    required
  >
  <span id="email-help" class="form-helper">
    We'll never share your email with anyone.
  </span>
</div>
```

**CSS:**
```css
.form-group {
  margin-bottom: 24px;
}

.form-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--color-neutral-800);
  margin-bottom: 8px;
}

.form-required {
  color: var(--color-error);
}

.form-input {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid var(--color-neutral-400);
  border-radius: 8px;
  font-family: var(--font-body);
  font-size: 1rem;
  color: var(--color-neutral-900);
  background-color: #FFFFFF;
  transition: border-color 200ms var(--ease-out);
}

.form-input::placeholder {
  color: var(--color-neutral-500);
}

.form-input:focus {
  outline: none;
  border-color: var(--color-primary);
  border-width: 2px;
  padding: 11px 15px; /* Adjust for thicker border */
  box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
}

.form-input:disabled {
  background-color: var(--color-neutral-100);
  color: var(--color-neutral-500);
  cursor: not-allowed;
}

.form-helper {
  display: block;
  font-size: 0.75rem;
  color: var(--color-neutral-600);
  margin-top: 4px;
}
```

### 4.2 Input States

```html
<!-- Error State -->
<div class="form-group">
  <label for="email-error" class="form-label">Email Address</label>
  <input type="email" id="email-error" class="form-input error" aria-invalid="true" aria-describedby="email-error-msg">
  <span id="email-error-msg" class="form-error" role="alert">
    <i class="fas fa-exclamation-circle"></i>
    Please enter a valid email address.
  </span>
</div>

<!-- Success State -->
<div class="form-group">
  <label for="email-success" class="form-label">Email Address</label>
  <input type="email" id="email-success" class="form-input success">
  <span class="form-success">
    <i class="fas fa-check-circle"></i>
    Email is available!
  </span>
</div>
```

```css
.form-input.error {
  border-color: var(--color-error);
}

.form-input.error:focus {
  box-shadow: 0 0 0 3px rgba(244, 67, 54, 0.1);
}

.form-error {
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 0.75rem;
  color: var(--color-error);
  margin-top: 4px;
}

.form-input.success {
  border-color: var(--color-success);
}

.form-success {
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 0.75rem;
  color: var(--color-success);
  margin-top: 4px;
}
```

### 4.3 Textarea

```html
<div class="form-group">
  <label for="message" class="form-label">Message</label>
  <textarea id="message" class="form-input" rows="5" placeholder="Your message..."></textarea>
</div>
```

### 4.4 Select Dropdown

```html
<div class="form-group">
  <label for="product" class="form-label">Select Product</label>
  <select id="product" class="form-select">
    <option value="">Choose a product...</option>
    <option value="milk">Organic Milk</option>
    <option value="yogurt">Natural Yogurt</option>
    <option value="butter">Pure Butter</option>
  </select>
</div>
```

```css
.form-select {
  width: 100%;
  padding: 12px 40px 12px 16px;
  border: 1px solid var(--color-neutral-400);
  border-radius: 8px;
  font-family: var(--font-body);
  font-size: 1rem;
  color: var(--color-neutral-900);
  background-color: #FFFFFF;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23616161' d='M6 8L2 4h8z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 16px center;
  cursor: pointer;
  appearance: none;
}

.form-select:focus {
  outline: none;
  border-color: var(--color-primary);
  border-width: 2px;
  padding: 11px 39px 11px 15px;
  box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
}
```

### 4.5 Checkbox

```html
<div class="form-check">
  <input type="checkbox" id="terms" class="form-checkbox">
  <label for="terms" class="form-check-label">
    I agree to the terms and conditions
  </label>
</div>
```

```css
.form-check {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  margin-bottom: 16px;
}

.form-checkbox {
  width: 20px;
  height: 20px;
  border: 2px solid var(--color-neutral-400);
  border-radius: 4px;
  cursor: pointer;
  appearance: none;
  background-color: #FFFFFF;
  position: relative;
  flex-shrink: 0;
  margin-top: 2px;
}

.form-checkbox:checked {
  background-color: var(--color-primary);
  border-color: var(--color-primary);
}

.form-checkbox:checked::after {
  content: '';
  position: absolute;
  left: 6px;
  top: 2px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.form-checkbox:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.25);
}

.form-check-label {
  font-size: 0.875rem;
  color: var(--color-neutral-800);
  cursor: pointer;
}
```

### 4.6 Radio Buttons

```html
<div class="form-group">
  <label class="form-label">Delivery Frequency</label>

  <div class="form-radio">
    <input type="radio" id="daily" name="frequency" class="form-radio-input" checked>
    <label for="daily" class="form-radio-label">Daily</label>
  </div>

  <div class="form-radio">
    <input type="radio" id="weekly" name="frequency" class="form-radio-input">
    <label for="weekly" class="form-radio-label">Weekly</label>
  </div>

  <div class="form-radio">
    <input type="radio" id="monthly" name="frequency" class="form-radio-input">
    <label for="monthly" class="form-radio-label">Monthly</label>
  </div>
</div>
```

```css
.form-radio {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 12px;
}

.form-radio-input {
  width: 20px;
  height: 20px;
  border: 2px solid var(--color-neutral-400);
  border-radius: 50%;
  cursor: pointer;
  appearance: none;
  background-color: #FFFFFF;
  position: relative;
  flex-shrink: 0;
}

.form-radio-input:checked {
  border-color: var(--color-primary);
}

.form-radio-input:checked::after {
  content: '';
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: var(--color-primary);
}

.form-radio-label {
  font-size: 0.875rem;
  color: var(--color-neutral-800);
  cursor: pointer;
}
```

### 4.7 Toggle Switch

```html
<div class="form-toggle">
  <input type="checkbox" id="notifications" class="toggle-input">
  <label for="notifications" class="toggle-label">
    <span class="toggle-switch"></span>
    <span class="toggle-text">Enable notifications</span>
  </label>
</div>
```

```css
.form-toggle {
  display: flex;
  align-items: center;
  margin-bottom: 16px;
}

.toggle-input {
  display: none;
}

.toggle-label {
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
}

.toggle-switch {
  position: relative;
  width: 48px;
  height: 24px;
  background-color: var(--color-neutral-400);
  border-radius: 12px;
  transition: background-color 200ms var(--ease-out);
}

.toggle-switch::after {
  content: '';
  position: absolute;
  left: 2px;
  top: 2px;
  width: 20px;
  height: 20px;
  background-color: #FFFFFF;
  border-radius: 50%;
  transition: left 200ms var(--ease-out);
}

.toggle-input:checked + .toggle-label .toggle-switch {
  background-color: var(--color-primary);
}

.toggle-input:checked + .toggle-label .toggle-switch::after {
  left: 26px;
}

.toggle-input:focus + .toggle-label .toggle-switch {
  box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.25);
}

.toggle-text {
  font-size: 0.875rem;
  color: var(--color-neutral-800);
}
```

---

## 5. Navigation Components

### 5.1 Header Navigation

```html
<header class="navbar">
  <div class="navbar-container">
    <a href="/" class="navbar-brand">
      <img src="logo.svg" alt="Smart Dairy" height="40">
    </a>

    <nav class="navbar-nav">
      <a href="/products" class="nav-link">Products</a>
      <a href="/farm" class="nav-link">The Farm</a>
      <a href="/about" class="nav-link">About Us</a>
      <a href="/contact" class="nav-link">Contact</a>
    </nav>

    <div class="navbar-actions">
      <button class="btn btn-icon" aria-label="Search">
        <i class="fas fa-search"></i>
      </button>
      <a href="/cart" class="btn btn-icon" aria-label="Shopping cart">
        <i class="fas fa-shopping-cart"></i>
        <span class="badge">3</span>
      </a>
      <a href="/account" class="btn btn-primary">Sign In</a>
    </div>

    <button class="navbar-toggle" aria-label="Toggle menu">
      <i class="fas fa-bars"></i>
    </button>
  </div>
</header>
```

```css
.navbar {
  background-color: #FFFFFF;
  border-bottom: 1px solid var(--color-neutral-300);
  position: sticky;
  top: 0;
  z-index: 100;
}

.navbar-container {
  max-width: 1440px;
  margin: 0 auto;
  padding: 16px 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 32px;
}

.navbar-brand img {
  display: block;
  height: 40px;
}

.navbar-nav {
  display: flex;
  gap: 32px;
  flex: 1;
}

.nav-link {
  color: var(--color-neutral-800);
  text-decoration: none;
  font-weight: 600;
  font-size: 0.875rem;
  padding: 8px 0;
  border-bottom: 2px solid transparent;
  transition: all 200ms var(--ease-out);
}

.nav-link:hover {
  color: var(--color-primary);
  border-bottom-color: var(--color-primary);
}

.nav-link.active {
  color: var(--color-primary);
  border-bottom-color: var(--color-primary);
}

.navbar-actions {
  display: flex;
  align-items: center;
  gap: 16px;
}

.navbar-toggle {
  display: none;
  background: none;
  border: none;
  font-size: 24px;
  color: var(--color-neutral-800);
  cursor: pointer;
}

@media (max-width: 991px) {
  .navbar-nav {
    display: none;
  }

  .navbar-toggle {
    display: block;
  }
}
```

### 5.2 Breadcrumbs

```html
<nav aria-label="Breadcrumb" class="breadcrumb">
  <ol class="breadcrumb-list">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item"><a href="/products">Products</a></li>
    <li class="breadcrumb-item active" aria-current="page">Organic Milk</li>
  </ol>
</nav>
```

```css
.breadcrumb-list {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  list-style: none;
  padding: 0;
  margin: 0;
  font-size: 0.875rem;
}

.breadcrumb-item {
  display: flex;
  align-items: center;
  gap: 8px;
}

.breadcrumb-item a {
  color: var(--color-primary);
  text-decoration: none;
}

.breadcrumb-item a:hover {
  text-decoration: underline;
}

.breadcrumb-item.active {
  color: var(--color-neutral-600);
}

.breadcrumb-item + .breadcrumb-item::before {
  content: '/';
  color: var(--color-neutral-500);
}
```

### 5.3 Tabs

```html
<div class="tabs">
  <div class="tab-list" role="tablist">
    <button class="tab-button active" role="tab" aria-selected="true" aria-controls="tab-1">
      Overview
    </button>
    <button class="tab-button" role="tab" aria-selected="false" aria-controls="tab-2">
      Nutrition
    </button>
    <button class="tab-button" role="tab" aria-selected="false" aria-controls="tab-3">
      Reviews
    </button>
  </div>

  <div class="tab-panels">
    <div id="tab-1" class="tab-panel active" role="tabpanel">
      Overview content...
    </div>
    <div id="tab-2" class="tab-panel" role="tabpanel" hidden>
      Nutrition content...
    </div>
    <div id="tab-3" class="tab-panel" role="tabpanel" hidden>
      Reviews content...
    </div>
  </div>
</div>
```

```css
.tab-list {
  display: flex;
  gap: 8px;
  border-bottom: 1px solid var(--color-neutral-300);
}

.tab-button {
  padding: 12px 24px;
  background: none;
  border: none;
  border-bottom: 2px solid transparent;
  color: var(--color-neutral-700);
  font-weight: 600;
  cursor: pointer;
  transition: all 200ms var(--ease-out);
}

.tab-button:hover {
  color: var(--color-primary);
  background-color: rgba(46, 125, 50, 0.05);
}

.tab-button.active {
  color: var(--color-primary);
  border-bottom-color: var(--color-primary);
}

.tab-panel {
  padding: 24px 0;
}
```

### 5.4 Pagination

```html
<nav aria-label="Pagination" class="pagination">
  <button class="pagination-btn" disabled aria-label="Previous page">
    <i class="fas fa-chevron-left"></i>
  </button>

  <button class="pagination-btn active" aria-current="page">1</button>
  <button class="pagination-btn">2</button>
  <button class="pagination-btn">3</button>
  <span class="pagination-dots">...</span>
  <button class="pagination-btn">10</button>

  <button class="pagination-btn" aria-label="Next page">
    <i class="fas fa-chevron-right"></i>
  </button>
</nav>
```

```css
.pagination {
  display: flex;
  align-items: center;
  gap: 8px;
  justify-content: center;
}

.pagination-btn {
  min-width: 40px;
  height: 40px;
  padding: 8px;
  border: 1px solid var(--color-neutral-400);
  border-radius: 4px;
  background-color: #FFFFFF;
  color: var(--color-neutral-800);
  font-weight: 600;
  cursor: pointer;
  transition: all 200ms var(--ease-out);
}

.pagination-btn:hover:not(:disabled) {
  background-color: var(--color-neutral-100);
  border-color: var(--color-neutral-500);
}

.pagination-btn.active {
  background-color: var(--color-primary);
  color: #FFFFFF;
  border-color: var(--color-primary);
}

.pagination-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination-dots {
  padding: 0 8px;
  color: var(--color-neutral-600);
}
```

---

## 6. Cards & Containers

### 6.1 Product Card

```html
<div class="card card-product">
  <div class="card-image">
    <img src="milk.jpg" alt="Organic Whole Milk">
    <button class="card-wishlist" aria-label="Add to wishlist">
      <i class="far fa-heart"></i>
    </button>
    <span class="card-badge">Organic</span>
  </div>

  <div class="card-content">
    <h3 class="card-title">Organic Whole Milk</h3>
    <p class="card-description">Fresh from our farm, 100% organic</p>

    <div class="card-footer">
      <div class="card-price">
        <span class="price-current">৳120</span>
        <span class="price-unit">/liter</span>
      </div>
      <button class="btn btn-primary btn-sm">
        <i class="fas fa-shopping-cart"></i>
        Add
      </button>
    </div>
  </div>
</div>
```

```css
.card {
  background-color: #FFFFFF;
  border-radius: 12px;
  box-shadow: var(--shadow-1);
  overflow: hidden;
  transition: all 200ms var(--ease-out);
}

.card:hover {
  box-shadow: var(--shadow-2);
  transform: translateY(-4px);
}

.card-image {
  position: relative;
  width: 100%;
  height: 240px;
  overflow: hidden;
}

.card-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 300ms var(--ease-out);
}

.card:hover .card-image img {
  transform: scale(1.05);
}

.card-wishlist {
  position: absolute;
  top: 12px;
  right: 12px;
  width: 40px;
  height: 40px;
  background-color: rgba(255, 255, 255, 0.9);
  border: none;
  border-radius: 50%;
  color: var(--color-error);
  font-size: 18px;
  cursor: pointer;
  transition: all 200ms var(--ease-out);
}

.card-wishlist:hover {
  background-color: #FFFFFF;
  transform: scale(1.1);
}

.card-badge {
  position: absolute;
  top: 12px;
  left: 12px;
  padding: 4px 12px;
  background-color: var(--color-gold);
  color: var(--color-neutral-900);
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  border-radius: 4px;
}

.card-content {
  padding: 20px;
}

.card-title {
  font-family: var(--font-heading);
  font-size: 1.25rem;
  color: var(--color-neutral-900);
  margin-bottom: 8px;
}

.card-description {
  font-size: 0.875rem;
  color: var(--color-neutral-600);
  margin-bottom: 16px;
}

.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card-price {
  display: flex;
  align-items: baseline;
  gap: 4px;
}

.price-current {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--color-primary);
}

.price-unit {
  font-size: 0.875rem;
  color: var(--color-neutral-600);
}
```

### 6.2 Info Card

```html
<div class="card card-info">
  <div class="card-icon">
    <i class="fas fa-truck"></i>
  </div>
  <div class="card-content">
    <h4 class="card-title">Free Delivery</h4>
    <p class="card-description">On orders over ৳500</p>
  </div>
</div>
```

```css
.card-info {
  display: flex;
  gap: 16px;
  padding: 24px;
}

.card-icon {
  width: 48px;
  height: 48px;
  background-color: rgba(46, 125, 50, 0.1);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--color-primary);
  font-size: 24px;
  flex-shrink: 0;
}
```

---

## 7. Data Display

### 7.1 Tables

```html
<div class="table-container">
  <table class="table">
    <thead>
      <tr>
        <th>Order ID</th>
        <th>Date</th>
        <th>Product</th>
        <th>Quantity</th>
        <th class="text-right">Total</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>#12345</td>
        <td>2026-01-28</td>
        <td>Organic Milk</td>
        <td>2 liters</td>
        <td class="text-right">৳240</td>
        <td><span class="badge badge-success">Delivered</span></td>
      </tr>
      <tr>
        <td>#12346</td>
        <td>2026-01-30</td>
        <td>Natural Yogurt</td>
        <td>4 cups</td>
        <td class="text-right">৳360</td>
        <td><span class="badge badge-warning">Processing</span></td>
      </tr>
    </tbody>
  </table>
</div>
```

```css
.table-container {
  overflow-x: auto;
  border-radius: 8px;
  box-shadow: var(--shadow-1);
}

.table {
  width: 100%;
  border-collapse: collapse;
  background-color: #FFFFFF;
}

.table th {
  padding: 16px;
  text-align: left;
  font-size: 0.875rem;
  font-weight: 700;
  color: var(--color-neutral-800);
  background-color: var(--color-neutral-100);
  border-bottom: 2px solid var(--color-neutral-300);
}

.table td {
  padding: 16px;
  font-size: 0.875rem;
  color: var(--color-neutral-700);
  border-bottom: 1px solid var(--color-neutral-200);
}

.table tbody tr:hover {
  background-color: var(--color-neutral-50);
}

.text-right {
  text-align: right;
}
```

### 7.2 Badges

```html
<span class="badge badge-success">Active</span>
<span class="badge badge-warning">Pending</span>
<span class="badge badge-error">Cancelled</span>
<span class="badge badge-info">Processing</span>
```

```css
.badge {
  display: inline-flex;
  align-items: center;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.badge-success {
  background-color: rgba(76, 175, 80, 0.1);
  color: var(--color-success);
}

.badge-warning {
  background-color: rgba(255, 152, 0, 0.1);
  color: var(--color-warning);
}

.badge-error {
  background-color: rgba(244, 67, 54, 0.1);
  color: var(--color-error);
}

.badge-info {
  background-color: rgba(33, 150, 243, 0.1);
  color: var(--color-info);
}
```

### 7.3 Tooltips

```html
<button class="btn btn-icon" data-tooltip="Add to wishlist">
  <i class="fas fa-heart"></i>
</button>
```

```css
[data-tooltip] {
  position: relative;
}

[data-tooltip]::after {
  content: attr(data-tooltip);
  position: absolute;
  bottom: 100%;
  left: 50%;
  transform: translateX(-50%) translateY(-8px);
  padding: 8px 12px;
  background-color: var(--color-neutral-900);
  color: #FFFFFF;
  font-size: 0.75rem;
  white-space: nowrap;
  border-radius: 4px;
  opacity: 0;
  pointer-events: none;
  transition: opacity 200ms var(--ease-out);
}

[data-tooltip]:hover::after {
  opacity: 1;
}
```

---

## 8. Feedback Components

### 8.1 Alerts

```html
<!-- Success Alert -->
<div class="alert alert-success" role="alert">
  <i class="fas fa-check-circle"></i>
  <div class="alert-content">
    <strong>Success!</strong> Your order has been placed.
  </div>
  <button class="alert-close" aria-label="Close alert">
    <i class="fas fa-times"></i>
  </button>
</div>

<!-- Error Alert -->
<div class="alert alert-error" role="alert">
  <i class="fas fa-exclamation-circle"></i>
  <div class="alert-content">
    <strong>Error!</strong> Payment failed. Please try again.
  </div>
  <button class="alert-close" aria-label="Close alert">
    <i class="fas fa-times"></i>
  </button>
</div>
```

```css
.alert {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding: 16px;
  border-radius: 8px;
  border-left: 4px solid;
  margin-bottom: 16px;
}

.alert-success {
  background-color: rgba(76, 175, 80, 0.1);
  border-left-color: var(--color-success);
  color: #2E7D32;
}

.alert-error {
  background-color: rgba(244, 67, 54, 0.1);
  border-left-color: var(--color-error);
  color: #C62828;
}

.alert-warning {
  background-color: rgba(255, 152, 0, 0.1);
  border-left-color: var(--color-warning);
  color: #E65100;
}

.alert-info {
  background-color: rgba(33, 150, 243, 0.1);
  border-left-color: var(--color-info);
  color: #1565C0;
}

.alert-content {
  flex: 1;
  font-size: 0.875rem;
}

.alert-close {
  background: none;
  border: none;
  color: inherit;
  cursor: pointer;
  opacity: 0.7;
  padding: 0;
}

.alert-close:hover {
  opacity: 1;
}
```

### 8.2 Modal Dialog

```html
<div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
  <div class="modal-overlay"></div>
  <div class="modal-container">
    <div class="modal-header">
      <h2 id="modal-title">Confirm Delete</h2>
      <button class="modal-close" aria-label="Close dialog">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="modal-body">
      <p>Are you sure you want to delete this item? This action cannot be undone.</p>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary">Cancel</button>
      <button class="btn btn-danger">Delete</button>
    </div>
  </div>
</div>
```

```css
.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-container {
  position: relative;
  max-width: 500px;
  width: 90%;
  background-color: #FFFFFF;
  border-radius: 12px;
  box-shadow: var(--shadow-4);
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 24px;
  border-bottom: 1px solid var(--color-neutral-300);
}

.modal-header h2 {
  margin: 0;
  font-size: 1.5rem;
}

.modal-close {
  background: none;
  border: none;
  font-size: 24px;
  color: var(--color-neutral-600);
  cursor: pointer;
  padding: 0;
}

.modal-body {
  padding: 24px;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  padding: 24px;
  border-top: 1px solid var(--color-neutral-300);
}
```

### 8.3 Toast Notifications

```html
<div class="toast toast-success">
  <i class="fas fa-check-circle"></i>
  <span>Item added to cart!</span>
</div>
```

```css
.toast {
  position: fixed;
  bottom: 24px;
  right: 24px;
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px 24px;
  background-color: var(--color-neutral-900);
  color: #FFFFFF;
  border-radius: 8px;
  box-shadow: var(--shadow-5);
  z-index: 1100;
  animation: slideIn 300ms var(--ease-out);
}

@keyframes slideIn {
  from {
    transform: translateY(100px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.toast-success {
  background-color: var(--color-success);
}
```

### 8.4 Loading Spinner

```html
<div class="spinner"></div>

<!-- With text -->
<div class="spinner-container">
  <div class="spinner"></div>
  <p>Loading...</p>
</div>
```

```css
.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid rgba(46, 125, 50, 0.2);
  border-top-color: var(--color-primary);
  border-radius: 50%;
  animation: spin 800ms linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.spinner-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
  padding: 48px;
  color: var(--color-neutral-600);
}
```

### 8.5 Progress Bar

```html
<div class="progress-bar">
  <div class="progress-fill" style="width: 65%;" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
    <span class="progress-label">65%</span>
  </div>
</div>
```

```css
.progress-bar {
  width: 100%;
  height: 24px;
  background-color: var(--color-neutral-200);
  border-radius: 12px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background-color: var(--color-primary);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: width 300ms var(--ease-out);
}

.progress-label {
  color: #FFFFFF;
  font-size: 0.75rem;
  font-weight: 700;
}
```

---

## 9. Media Components

### 9.1 Avatar

```html
<div class="avatar avatar-md">
  <img src="user.jpg" alt="John Doe">
</div>

<!-- With initials -->
<div class="avatar avatar-md avatar-initials">
  JD
</div>
```

```css
.avatar {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  overflow: hidden;
  background-color: var(--color-primary);
  color: #FFFFFF;
  font-weight: 700;
}

.avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.avatar-sm {
  width: 32px;
  height: 32px;
  font-size: 0.75rem;
}

.avatar-md {
  width: 48px;
  height: 48px;
  font-size: 1rem;
}

.avatar-lg {
  width: 64px;
  height: 64px;
  font-size: 1.5rem;
}
```

### 9.2 Image Gallery

```html
<div class="gallery">
  <div class="gallery-item">
    <img src="photo1.jpg" alt="Farm view">
  </div>
  <div class="gallery-item">
    <img src="photo2.jpg" alt="Cattle">
  </div>
  <div class="gallery-item">
    <img src="photo3.jpg" alt="Milking">
  </div>
</div>
```

```css
.gallery {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 16px;
}

.gallery-item {
  aspect-ratio: 1;
  overflow: hidden;
  border-radius: 8px;
  cursor: pointer;
}

.gallery-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 300ms var(--ease-out);
}

.gallery-item:hover img {
  transform: scale(1.05);
}
```

---

## 10. Layout Components

### 10.1 Container

```html
<div class="container">
  <!-- Content -->
</div>
```

```css
.container {
  max-width: 1440px;
  margin: 0 auto;
  padding: 0 24px;
}

@media (max-width: 767px) {
  .container {
    padding: 0 16px;
  }
}
```

### 10.2 Grid Layout

```html
<div class="grid">
  <div class="grid-col-4">Column 1</div>
  <div class="grid-col-4">Column 2</div>
  <div class="grid-col-4">Column 3</div>
</div>
```

```css
.grid {
  display: grid;
  grid-template-columns: repeat(12, 1fr);
  gap: 24px;
}

.grid-col-1 { grid-column: span 1; }
.grid-col-2 { grid-column: span 2; }
.grid-col-3 { grid-column: span 3; }
.grid-col-4 { grid-column: span 4; }
.grid-col-6 { grid-column: span 6; }
.grid-col-8 { grid-column: span 8; }
.grid-col-12 { grid-column: span 12; }

@media (max-width: 767px) {
  .grid > * {
    grid-column: span 12 !important;
  }
}
```

---

## 11. Utility Components

### 11.1 Divider

```html
<hr class="divider">
```

```css
.divider {
  border: none;
  border-top: 1px solid var(--color-neutral-300);
  margin: 32px 0;
}
```

### 11.2 Skeleton Loading

```html
<div class="skeleton skeleton-text"></div>
<div class="skeleton skeleton-title"></div>
<div class="skeleton skeleton-avatar"></div>
```

```css
.skeleton {
  background: linear-gradient(90deg, #F5F5F5 25%, #E0E0E0 50%, #F5F5F5 75%);
  background-size: 200% 100%;
  animation: loading 1.5s infinite;
  border-radius: 4px;
}

@keyframes loading {
  0% { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}

.skeleton-text {
  height: 16px;
  margin-bottom: 8px;
}

.skeleton-title {
  height: 24px;
  width: 60%;
  margin-bottom: 16px;
}

.skeleton-avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
}
```

---

## 12. Usage Guidelines

### 12.1 When to Use Which Component

| Need | Component | Example |
|------|-----------|---------|
| Primary action | Primary Button | "Add to Cart", "Submit Order" |
| Secondary action | Secondary Button | "Cancel", "Learn More" |
| Destructive action | Danger Button | "Delete Account" |
| Form input | Text Input | Email, name, address |
| On/off setting | Toggle Switch | Enable notifications |
| Single selection | Radio Buttons | Delivery frequency |
| Multiple selection | Checkboxes | Product filters |
| Display product | Product Card | Product listing page |
| Show data | Table | Order history |
| Success feedback | Success Alert/Toast | "Order placed!" |
| User confirmation | Modal Dialog | "Are you sure?" |

### 12.2 Component Composition

Components can be combined:

```html
<!-- Card with form -->
<div class="card">
  <div class="card-content">
    <h3 class="card-title">Sign Up</h3>
    <form>
      <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" class="form-input">
      </div>
      <button class="btn btn-primary">Submit</button>
    </form>
  </div>
</div>
```

---

## 13. Accessibility Notes

All components are built with accessibility in mind:

**Keyboard Navigation:**
- All interactive elements accessible via Tab
- Escape key closes modals/dropdowns
- Arrow keys navigate within components

**Screen Readers:**
- Proper ARIA labels and roles
- `aria-label` for icon-only buttons
- `aria-describedby` for help text
- `role` attributes where needed

**Focus Management:**
- Visible focus indicators on all components
- Focus trapped in modals
- Focus returned to trigger element when closed

**Color Contrast:**
- All text meets 4.5:1 contrast ratio
- UI components meet 3:1 ratio
- Don't rely on color alone

---

## 14. Appendix

### 14.1 Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari 14+, Chrome Android)

### 14.2 Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0 | 2026-02-01 | Initial release | Claude Code |

### 14.3 References

- [A-001: UI/UX Design System](A-001_UI-UX-Design-System.md)
- [A-012: Accessibility Compliance Guide](A-012_Accessibility-Compliance-Guide.md)
- [MDN Web Docs - HTML](https://developer.mozilla.org/en-US/docs/Web/HTML)
- [WAI-ARIA Authoring Practices](https://www.w3.org/WAI/ARIA/apg/)

---

**End of Document**

*This component library is a living resource. Components will be updated as the design system evolves.*
