# A-001: UI/UX Design System Document

**Document Version:** 1.0
**Last Updated:** 2026-02-01
**Author:** Claude Code
**Status:** Draft
**Project:** Smart Dairy Ltd. - Digital Transformation

---

## Table of Contents

1. [Overview](#1-overview)
2. [Design Principles](#2-design-principles)
3. [Color System](#3-color-system)
4. [Typography](#4-typography)
5. [Spacing & Layout](#5-spacing--layout)
6. [Grid System](#6-grid-system)
7. [Elevation & Shadows](#7-elevation--shadows)
8. [Border Radius & Styling](#8-border-radius--styling)
9. [Iconography](#9-iconography)
10. [Motion & Animation](#10-motion--animation)
11. [Design Tokens](#11-design-tokens)
12. [Component Specifications](#12-component-specifications)
13. [Interaction States](#13-interaction-states)
14. [Accessibility Guidelines](#14-accessibility-guidelines)
15. [Multi-Language Support](#15-multi-language-support)
16. [Implementation Guidelines](#16-implementation-guidelines)
17. [Appendix](#17-appendix)

---

## 1. Overview

### 1.1 Document Purpose

This UI/UX Design System serves as the single source of truth for all visual and interaction design decisions across the Smart Dairy Ltd. digital ecosystem. It ensures consistency, scalability, and accessibility across all platforms including web portals, mobile applications, and ERP interfaces.

### 1.2 Scope and Audience

**Scope:**
- 8+ integrated applications (Public Website, B2C E-commerce, B2B Portal, Farm Management, Admin Portal, 3 Mobile Apps, ERP System)
- Web and mobile platforms (iOS, Android, Web)
- Multi-language support (English, Bengali)

**Audience:**
- UI/UX Designers
- Frontend Developers
- Mobile App Developers
- Product Managers
- QA Engineers

### 1.3 Related Documents

- [A-002: Brand Digital Guidelines](A-002_Brand-Digital-Guidelines.md)
- [A-011: Component Library Documentation](A-011_Component-Library-Documentation.md)
- [A-012: Accessibility Compliance Guide](A-012_Accessibility-Compliance-Guide.md)
- [A-013: Responsive Design Specifications](A-013_Responsive-Design-Specifications.md)

---

## 2. Design Principles

### 2.1 Core Principles

**NATURAL** - Design inspired by organic farming and natural elements
- Use earth tones, green palette, and natural imagery
- Minimize artificial elements and excessive ornamentation
- Embrace whitespace and breathing room

**PURE** - Clean, honest, and transparent design approach
- Clear visual hierarchy
- Straightforward navigation patterns
- No dark patterns or deceptive UI elements
- Honest representation of products and services

**TRUSTED** - Build confidence through professional design
- Consistent design language
- Reliable interaction patterns
- Clear communication of information
- Professional typography and spacing

**MODERN** - Contemporary design that feels current
- Mobile-first approach
- Progressive web capabilities
- Modern CSS/JavaScript frameworks
- Smooth animations and transitions

**ACCESSIBLE** - Inclusive design for all users
- WCAG 2.1 Level AA compliance
- Multi-language support (English, Bengali)
- Support for various literacy levels
- Keyboard and screen reader friendly

### 2.2 Design Philosophy

Our design system embodies the values of Smart Dairy Ltd.:
- **Transparency:** Clear information architecture and honest communication
- **Quality:** Attention to detail in every pixel
- **Sustainability:** Efficient, performant designs that minimize resource usage
- **Community:** Designs that serve diverse user groups from farmers to consumers

---

## 3. Color System

### 3.1 Primary Colors

The primary color palette is rooted in nature and organic farming, with green as the dominant brand color.

#### Primary Green
```css
--color-primary: #2E7D32;
--color-primary-rgb: 46, 125, 50;
```
- **Usage:** Primary CTAs, active states, brand accents
- **Accessibility:** AA compliant on white backgrounds (contrast ratio 5.2:1)
- **Meaning:** Growth, nature, organic, freshness

#### Primary Light
```css
--color-primary-light: #4CAF50;
--color-primary-light-rgb: 76, 175, 80;
```
- **Usage:** Hover states, highlights, success indicators
- **Accessibility:** AA compliant on white (contrast ratio 3.4:1 for large text)

#### Primary Dark
```css
--color-primary-dark: #1B5E20;
--color-primary-dark-rgb: 27, 94, 32;
```
- **Usage:** Active/pressed states, dark mode accents, text on light backgrounds
- **Accessibility:** AAA compliant on white backgrounds (contrast ratio 9.8:1)

### 3.2 Secondary Colors

#### Premium Gold
```css
--color-gold: #FFD700;
--color-gold-rgb: 255, 215, 0;
```
- **Usage:** Premium products, special offers, accent highlights
- **Accessibility:** Use dark text (#212121) on gold backgrounds
- **Meaning:** Premium quality, value, excellence

### 3.3 Semantic Colors

#### Success
```css
--color-success: #4CAF50;
--color-success-light: #81C784;
--color-success-dark: #388E3C;
```
- **Usage:** Success messages, confirmations, positive states

#### Warning
```css
--color-warning: #FF9800;
--color-warning-light: #FFB74D;
--color-warning-dark: #F57C00;
```
- **Usage:** Warnings, important alerts, inventory alerts

#### Error
```css
--color-error: #F44336;
--color-error-light: #E57373;
--color-error-dark: #D32F2F;
```
- **Usage:** Error messages, destructive actions, form validation errors

#### Info
```css
--color-info: #2196F3;
--color-info-light: #64B5F6;
--color-info-dark: #1976D2;
```
- **Usage:** Informational messages, help text, neutral notifications

### 3.4 Neutral Colors

```css
--color-neutral-900: #212121;  /* Primary text */
--color-neutral-800: #424242;  /* Secondary text */
--color-neutral-700: #616161;  /* Tertiary text */
--color-neutral-600: #757575;  /* Placeholder text */
--color-neutral-500: #9E9E9E;  /* Disabled text */
--color-neutral-400: #BDBDBD;  /* Dividers */
--color-neutral-300: #E0E0E0;  /* Borders */
--color-neutral-200: #EEEEEE;  /* Backgrounds */
--color-neutral-100: #F5F5F5;  /* Subtle backgrounds */
--color-neutral-50: #FAFAFA;   /* Page background */
```

### 3.5 Extended Palette (Optional)

#### Earth Tones (Farm-themed accents)
```css
--color-earth-brown: #795548;   /* Soil, organic matter */
--color-earth-tan: #D7CCC8;     /* Natural fibers */
--color-earth-sage: #8BC34A;    /* Pasture, grass */
--color-earth-sky: #87CEEB;     /* Clean air, open sky */
```

### 3.6 Color Usage Guidelines

**Do's:**
- ✅ Use primary green for all main CTAs and brand elements
- ✅ Use semantic colors for their intended purposes (success = green, error = red)
- ✅ Ensure 4.5:1 contrast ratio for text, 3:1 for UI components
- ✅ Use neutral colors for text hierarchy
- ✅ Test colors in both light and dark modes

**Don'ts:**
- ❌ Don't use red or green alone to convey information (colorblind users)
- ❌ Don't use more than 3-4 colors in a single interface
- ❌ Don't use pure black (#000000) for text; use neutral-900 (#212121)
- ❌ Don't override semantic colors (e.g., using red for success)

### 3.7 Color Accessibility

All color combinations must meet WCAG 2.1 standards:

| Contrast Ratio | Level | Usage |
|----------------|-------|-------|
| 4.5:1 | AA | Normal text (< 18pt) |
| 3:1 | AA | Large text (≥ 18pt or 14pt bold) |
| 3:1 | AA | UI components and graphical objects |
| 7:1 | AAA | Enhanced contrast (optional) |

**Tools for Testing:**
- WebAIM Contrast Checker
- Chrome DevTools Color Picker
- Stark plugin for Figma

---

## 4. Typography

### 4.1 Font Families

#### English Typography

**Headings: Playfair Display**
```css
--font-heading: 'Playfair Display', Georgia, serif;
```
- **Weights:** 400 (Regular), 500 (Medium), 600 (SemiBold), 700 (Bold)
- **Character:** Elegant, classic, trustworthy
- **Usage:** H1-H6, hero text, featured content
- **Fallback:** Georgia, Times New Roman, serif

**Body: Open Sans**
```css
--font-body: 'Open Sans', 'Segoe UI', Arial, sans-serif;
```
- **Weights:** 300 (Light), 400 (Regular), 600 (SemiBold), 700 (Bold)
- **Character:** Clean, readable, modern
- **Usage:** Paragraphs, UI text, buttons, forms
- **Fallback:** Segoe UI, Roboto, Arial, sans-serif

#### Bengali Typography

**Headings: Noto Serif Bengali**
```css
--font-heading-bengali: 'Noto Serif Bengali', serif;
```
- **Weights:** 400, 600, 700
- **Usage:** Bengali headings (শিরোনাম)
- **Size Adjustment:** +2px larger than English equivalents

**Body: Noto Sans Bengali**
```css
--font-body-bengali: 'Noto Sans Bengali', sans-serif;
```
- **Weights:** 400, 600, 700
- **Usage:** Bengali body text (মূল লেখা)
- **Size Adjustment:** +2px larger, 1.8 line-height (vs 1.6 for English)

#### Monospace (Code/Data)

**JetBrains Mono**
```css
--font-mono: 'JetBrains Mono', 'Courier New', monospace;
```
- **Weights:** 400, 600
- **Usage:** Code snippets, API responses, data values, order IDs
- **Fallback:** Consolas, Monaco, Courier New, monospace

### 4.2 Type Scale

Our type scale is based on a modular scale with a 1.250 ratio (Major Third).

| Level | Size (px) | Size (rem) | Line Height | Usage |
|-------|-----------|------------|-------------|-------|
| H1 | 48px | 3rem | 1.2 (58px) | Page titles, hero headings |
| H2 | 38px | 2.375rem | 1.3 (49px) | Section headings |
| H3 | 30px | 1.875rem | 1.3 (39px) | Subsection headings |
| H4 | 24px | 1.5rem | 1.4 (34px) | Card titles, widget headings |
| H5 | 20px | 1.25rem | 1.4 (28px) | Small headings |
| H6 | 16px | 1rem | 1.5 (24px) | Smallest headings |
| Body Large | 18px | 1.125rem | 1.6 (29px) | Lead paragraphs, important text |
| Body | 16px | 1rem | 1.6 (26px) | Default body text |
| Body Small | 14px | 0.875rem | 1.5 (21px) | Secondary text, captions |
| Caption | 12px | 0.75rem | 1.5 (18px) | Helper text, meta info |
| Overline | 10px | 0.625rem | 1.5 (15px) | Labels, tags (uppercase) |

### 4.3 Font Weights

```css
--font-weight-light: 300;
--font-weight-regular: 400;
--font-weight-medium: 500;
--font-weight-semibold: 600;
--font-weight-bold: 700;
```

**Usage Guidelines:**
- **300 (Light):** Optional for large headings, use sparingly
- **400 (Regular):** Body text, standard UI elements
- **600 (SemiBold):** Emphasis in body text, button labels
- **700 (Bold):** Headings, important labels

### 4.4 Bengali Typography Adjustments

Bengali text requires special consideration due to different character shapes and reading patterns:

```css
/* Bengali-specific adjustments */
.text-bengali {
  font-size: calc(1em + 2px);  /* 2px larger */
  line-height: 1.8;            /* Increased from 1.6 */
  letter-spacing: 0.01em;      /* Slight spacing */
}
```

**Minimum Sizes:**
- Body text: 18px (vs 16px for English)
- Small text: 16px (vs 14px for English)
- Caption: 14px (vs 12px for English)

### 4.5 Responsive Typography

Typography scales responsively across breakpoints:

```css
/* Mobile-first approach */
html {
  font-size: 14px;  /* Base for mobile */
}

@media (min-width: 768px) {
  html {
    font-size: 15px;  /* Tablet */
  }
}

@media (min-width: 1200px) {
  html {
    font-size: 16px;  /* Desktop */
  }
}
```

### 4.6 Typography Usage Examples

```html
<!-- English Heading -->
<h1 class="text-h1" style="font-family: var(--font-heading); font-size: 3rem; font-weight: 700; color: var(--color-neutral-900);">
  100% Organic Dairy Products
</h1>

<!-- Bengali Heading -->
<h1 class="text-h1 text-bengali" style="font-family: var(--font-heading-bengali); font-size: calc(3rem + 2px); line-height: 1.3;">
  ১০০% জৈব দুগ্ধজাত পণ্য
</h1>

<!-- Body Text -->
<p class="text-body" style="font-family: var(--font-body); font-size: 1rem; line-height: 1.6; color: var(--color-neutral-700);">
  Our organic dairy products are sourced from grass-fed cattle on our sustainable farm in Narayanganj.
</p>

<!-- Code/Data -->
<code class="text-mono" style="font-family: var(--font-mono); font-size: 0.875rem; background: var(--color-neutral-100); padding: 2px 4px; border-radius: 4px;">
  ORDER-2026-001234
</code>
```

---

## 5. Spacing & Layout

### 5.1 Spacing System

Our spacing system is based on an 8px base unit, creating a consistent rhythm throughout the interface.

```css
--spacing-0: 0;
--spacing-1: 4px;   /* 0.25rem - Micro spacing */
--spacing-2: 8px;   /* 0.5rem - Base unit */
--spacing-3: 12px;  /* 0.75rem - Tight spacing */
--spacing-4: 16px;  /* 1rem - Default spacing */
--spacing-5: 24px;  /* 1.5rem - Comfortable spacing */
--spacing-6: 32px;  /* 2rem - Generous spacing */
--spacing-7: 48px;  /* 3rem - Section spacing */
--spacing-8: 64px;  /* 4rem - Large section spacing */
--spacing-9: 96px;  /* 6rem - Extra large spacing */
--spacing-10: 128px; /* 8rem - Hero/feature spacing */
```

### 5.2 Spacing Usage Guidelines

| Spacing | Size | Common Uses |
|---------|------|-------------|
| 4px | spacing-1 | Icon padding, tight inline elements |
| 8px | spacing-2 | Default padding for small components |
| 12px | spacing-3 | Compact card padding |
| 16px | spacing-4 | Default padding for buttons, inputs |
| 24px | spacing-5 | Card padding, component spacing |
| 32px | spacing-6 | Section padding (mobile) |
| 48px | spacing-7 | Section padding (desktop) |
| 64px | spacing-8 | Large section separation |
| 96px | spacing-9 | Major section separation |
| 128px | spacing-10 | Hero section padding |

### 5.3 Padding Examples

```css
/* Button padding */
.button-primary {
  padding: 12px 24px;  /* spacing-3 spacing-5 */
}

/* Card padding */
.card {
  padding: 24px;  /* spacing-5 */
}

/* Section spacing (mobile) */
.section {
  padding: 32px 16px;  /* spacing-6 spacing-4 */
}

/* Section spacing (desktop) */
@media (min-width: 1200px) {
  .section {
    padding: 64px 48px;  /* spacing-8 spacing-7 */
  }
}
```

### 5.4 Margin Examples

```css
/* Heading bottom margin */
h2 {
  margin-bottom: 24px;  /* spacing-5 */
}

/* Paragraph spacing */
p + p {
  margin-top: 16px;  /* spacing-4 */
}

/* Component separation */
.component + .component {
  margin-top: 32px;  /* spacing-6 */
}
```

---

## 6. Grid System

### 6.1 Grid Specifications

We use a 12-column flexible grid system based on CSS Grid and Flexbox.

```css
.container {
  display: grid;
  grid-template-columns: repeat(12, 1fr);
  gap: 24px;  /* Gutter width */
  padding: 0 16px;
  max-width: 1440px;
  margin: 0 auto;
}
```

### 6.2 Container Widths

| Breakpoint | Container Max-Width | Padding |
|------------|---------------------|---------|
| XS (0-575px) | 100% | 16px |
| SM (576-767px) | 540px | 20px |
| MD (768-991px) | 720px | 24px |
| LG (992-1199px) | 960px | 24px |
| XL (1200-1439px) | 1140px | 32px |
| XXL (1440px+) | 1320px | 48px |

### 6.3 Column Examples

```html
<!-- Full width -->
<div style="grid-column: 1 / -1;">Full width content</div>

<!-- Two equal columns -->
<div style="grid-column: span 6;">Column 1</div>
<div style="grid-column: span 6;">Column 2</div>

<!-- Sidebar layout (3 + 9) -->
<aside style="grid-column: span 3;">Sidebar</aside>
<main style="grid-column: span 9;">Main content</main>

<!-- 3-column grid -->
<div style="grid-column: span 4;">Item 1</div>
<div style="grid-column: span 4;">Item 2</div>
<div style="grid-column: span 4;">Item 3</div>
```

### 6.4 Responsive Grid

```css
/* Mobile: 1 column */
.product-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 16px;
}

/* Tablet: 2 columns */
@media (min-width: 768px) {
  .product-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
  }
}

/* Desktop: 3 columns */
@media (min-width: 992px) {
  .product-grid {
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
  }
}

/* Large desktop: 4 columns */
@media (min-width: 1440px) {
  .product-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}
```

---

## 7. Elevation & Shadows

### 7.1 Shadow Levels

We use a 5-level elevation system to create depth and hierarchy.

```css
/* Level 0: Flat (no shadow) */
--shadow-0: none;

/* Level 1: Subtle elevation (cards, inputs) */
--shadow-1: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.08);

/* Level 2: Raised elements (hover cards, dropdowns) */
--shadow-2: 0 3px 6px rgba(0, 0, 0, 0.15), 0 2px 4px rgba(0, 0, 0, 0.12);

/* Level 3: Floating elements (modals, popovers) */
--shadow-3: 0 10px 20px rgba(0, 0, 0, 0.15), 0 3px 6px rgba(0, 0, 0, 0.10);

/* Level 4: Overlays (dialogs, drawers) */
--shadow-4: 0 15px 25px rgba(0, 0, 0, 0.15), 0 5px 10px rgba(0, 0, 0, 0.08);

/* Level 5: Top layer (tooltips, notifications) */
--shadow-5: 0 20px 40px rgba(0, 0, 0, 0.2), 0 8px 16px rgba(0, 0, 0, 0.15);
```

### 7.2 Shadow Usage

| Level | Usage | Examples |
|-------|-------|----------|
| 0 | Flat surfaces | Plain backgrounds, disabled elements |
| 1 | Slightly raised | Cards at rest, input fields, chips |
| 2 | Raised | Hovered cards, dropdown menus, date pickers |
| 3 | Floating | Modals, popovers, tooltips |
| 4 | Overlays | Dialogs, side drawers, bottom sheets |
| 5 | Top layer | Snackbars, toast notifications |

### 7.3 Shadow Examples

```css
/* Card at rest */
.card {
  box-shadow: var(--shadow-1);
}

/* Card on hover */
.card:hover {
  box-shadow: var(--shadow-2);
  transform: translateY(-2px);
  transition: all 0.2s ease;
}

/* Modal dialog */
.modal {
  box-shadow: var(--shadow-4);
}

/* Toast notification */
.toast {
  box-shadow: var(--shadow-5);
}
```

### 7.4 Focus Shadows

For accessibility, focus states use colored shadows:

```css
/* Primary focus */
.input:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.25);
}

/* Error focus */
.input.error:focus {
  box-shadow: 0 0 0 3px rgba(244, 67, 54, 0.25);
}
```

---

## 8. Border Radius & Styling

### 8.1 Border Radius Scale

```css
--radius-0: 0;          /* Sharp corners */
--radius-sm: 4px;       /* Subtle rounding */
--radius-md: 8px;       /* Default rounding */
--radius-lg: 12px;      /* Generous rounding */
--radius-xl: 16px;      /* Large rounding */
--radius-2xl: 24px;     /* Extra large rounding */
--radius-full: 9999px;  /* Fully rounded (pills, avatars) */
```

### 8.2 Border Radius Usage

| Radius | Size | Common Uses |
|--------|------|-------------|
| 0 | Sharp | Tables, alerts, specific designs |
| 4px | Subtle | Chips, tags, small elements |
| 8px | Default | Buttons, inputs, cards |
| 12px | Generous | Large cards, modals |
| 16px | Large | Hero cards, feature sections |
| 24px | XL | Image containers, special cards |
| Full | Rounded | Avatars, pills, icon buttons |

### 8.3 Border Styles

```css
/* Border widths */
--border-width-thin: 1px;
--border-width-medium: 2px;
--border-width-thick: 4px;

/* Border colors */
--border-color-light: var(--color-neutral-300);
--border-color-medium: var(--color-neutral-400);
--border-color-dark: var(--color-neutral-600);
```

### 8.4 Border Examples

```css
/* Default button */
.button {
  border-radius: var(--radius-md);  /* 8px */
  border: 2px solid var(--color-primary);
}

/* Card */
.card {
  border-radius: var(--radius-lg);  /* 12px */
  border: 1px solid var(--color-neutral-300);
}

/* Avatar */
.avatar {
  border-radius: var(--radius-full);  /* Fully rounded */
}

/* Input field */
.input {
  border-radius: var(--radius-md);  /* 8px */
  border: 1px solid var(--color-neutral-400);
}

.input:focus {
  border-color: var(--color-primary);
  border-width: 2px;
}
```

---

## 9. Iconography

### 9.1 Icon System

We use **Font Awesome 6.4** as our primary icon library, supplemented with custom SVG icons for specific needs.

```html
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
```

### 9.2 Icon Sizes

```css
--icon-size-xs: 12px;   /* Inline icons */
--icon-size-sm: 16px;   /* Button icons, small UI */
--icon-size-md: 24px;   /* Default size */
--icon-size-lg: 32px;   /* Large buttons, headers */
--icon-size-xl: 48px;   /* Feature icons */
--icon-size-2xl: 64px;  /* Hero icons */
```

### 9.3 Icon Usage

```html
<!-- Small icon in button -->
<button class="button-primary">
  <i class="fas fa-shopping-cart" style="font-size: 16px; margin-right: 8px;"></i>
  Add to Cart
</button>

<!-- Medium icon standalone -->
<i class="fas fa-check-circle" style="font-size: 24px; color: var(--color-success);"></i>

<!-- Large feature icon -->
<i class="fas fa-cow" style="font-size: 64px; color: var(--color-primary);"></i>
```

### 9.4 Common Icons

| Icon | Font Awesome Class | Usage |
|------|-------------------|-------|
| Cart | `fa-shopping-cart` | Shopping cart, add to cart |
| User | `fa-user` | User profile, account |
| Heart | `fa-heart` | Wishlist, favorites |
| Search | `fa-search` | Search functionality |
| Menu | `fa-bars` | Hamburger menu |
| Close | `fa-times` | Close modals, dismiss |
| Check | `fa-check` | Success, completed |
| Warning | `fa-exclamation-triangle` | Warnings |
| Info | `fa-info-circle` | Information |
| Arrow Right | `fa-arrow-right` | Navigation, CTAs |
| Cow | `fa-cow` | Farm, cattle |
| Milk | `fa-glass-whiskey` | Dairy products |
| Truck | `fa-truck` | Delivery, shipping |
| Phone | `fa-phone` | Contact |
| Email | `fa-envelope` | Email, messages |

For more details, see [A-015: Iconography & Illustration Guidelines](A-015_Iconography-Illustration-Guidelines.md).

---

## 10. Motion & Animation

### 10.1 Animation Principles

1. **Purposeful:** Every animation serves a functional purpose
2. **Performant:** 60fps, GPU-accelerated where possible
3. **Accessible:** Respects `prefers-reduced-motion` setting
4. **Subtle:** Animations enhance, not distract

### 10.2 Timing Functions

```css
--ease-in: cubic-bezier(0.4, 0, 1, 1);           /* Accelerating */
--ease-out: cubic-bezier(0, 0, 0.2, 1);          /* Decelerating */
--ease-in-out: cubic-bezier(0.4, 0, 0.2, 1);     /* Smooth */
--ease-sharp: cubic-bezier(0.4, 0, 0.6, 1);      /* Quick start/end */
```

### 10.3 Duration Scale

```css
--duration-instant: 100ms;   /* Micro-interactions */
--duration-fast: 200ms;      /* Hover states, toggles */
--duration-base: 300ms;      /* Default animations */
--duration-slow: 400ms;      /* Page transitions */
--duration-slower: 600ms;    /* Complex animations */
```

### 10.4 Common Animations

```css
/* Fade in */
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Slide in from bottom */
@keyframes slideInUp {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

/* Scale up (buttons) */
.button:active {
  transform: scale(0.98);
  transition: transform 100ms var(--ease-out);
}

/* Hover lift (cards) */
.card {
  transition: all 200ms var(--ease-out);
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-2);
}
```

### 10.5 Reduced Motion

Always respect user preferences for reduced motion:

```css
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
```

For more details, see [A-014: Animation & Interaction Design](A-014_Animation-Interaction-Design.md).

---

## 11. Design Tokens

### 11.1 CSS Custom Properties

All design tokens should be defined as CSS custom properties for easy theming:

```css
:root {
  /* Colors */
  --color-primary: #2E7D32;
  --color-primary-light: #4CAF50;
  --color-primary-dark: #1B5E20;
  --color-gold: #FFD700;

  /* Semantic colors */
  --color-success: #4CAF50;
  --color-warning: #FF9800;
  --color-error: #F44336;
  --color-info: #2196F3;

  /* Neutral colors */
  --color-neutral-900: #212121;
  --color-neutral-800: #424242;
  --color-neutral-700: #616161;
  --color-neutral-600: #757575;
  --color-neutral-500: #9E9E9E;
  --color-neutral-400: #BDBDBD;
  --color-neutral-300: #E0E0E0;
  --color-neutral-200: #EEEEEE;
  --color-neutral-100: #F5F5F5;
  --color-neutral-50: #FAFAFA;

  /* Typography */
  --font-heading: 'Playfair Display', Georgia, serif;
  --font-body: 'Open Sans', 'Segoe UI', Arial, sans-serif;
  --font-mono: 'JetBrains Mono', 'Courier New', monospace;

  /* Font sizes */
  --text-h1: 3rem;
  --text-h2: 2.375rem;
  --text-h3: 1.875rem;
  --text-h4: 1.5rem;
  --text-h5: 1.25rem;
  --text-h6: 1rem;
  --text-body-lg: 1.125rem;
  --text-body: 1rem;
  --text-body-sm: 0.875rem;
  --text-caption: 0.75rem;

  /* Spacing */
  --spacing-1: 4px;
  --spacing-2: 8px;
  --spacing-3: 12px;
  --spacing-4: 16px;
  --spacing-5: 24px;
  --spacing-6: 32px;
  --spacing-7: 48px;
  --spacing-8: 64px;
  --spacing-9: 96px;
  --spacing-10: 128px;

  /* Shadows */
  --shadow-1: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.08);
  --shadow-2: 0 3px 6px rgba(0, 0, 0, 0.15), 0 2px 4px rgba(0, 0, 0, 0.12);
  --shadow-3: 0 10px 20px rgba(0, 0, 0, 0.15), 0 3px 6px rgba(0, 0, 0, 0.10);
  --shadow-4: 0 15px 25px rgba(0, 0, 0, 0.15), 0 5px 10px rgba(0, 0, 0, 0.08);
  --shadow-5: 0 20px 40px rgba(0, 0, 0, 0.2), 0 8px 16px rgba(0, 0, 0, 0.15);

  /* Border radius */
  --radius-sm: 4px;
  --radius-md: 8px;
  --radius-lg: 12px;
  --radius-xl: 16px;
  --radius-2xl: 24px;
  --radius-full: 9999px;

  /* Animations */
  --duration-fast: 200ms;
  --duration-base: 300ms;
  --duration-slow: 400ms;
  --ease-out: cubic-bezier(0, 0, 0.2, 1);
}
```

### 11.2 JSON Format (for development tools)

```json
{
  "colors": {
    "primary": {
      "base": "#2E7D32",
      "light": "#4CAF50",
      "dark": "#1B5E20"
    },
    "semantic": {
      "success": "#4CAF50",
      "warning": "#FF9800",
      "error": "#F44336",
      "info": "#2196F3"
    }
  },
  "spacing": {
    "1": "4px",
    "2": "8px",
    "3": "12px",
    "4": "16px",
    "5": "24px",
    "6": "32px",
    "7": "48px",
    "8": "64px"
  },
  "typography": {
    "fontFamily": {
      "heading": "'Playfair Display', Georgia, serif",
      "body": "'Open Sans', 'Segoe UI', Arial, sans-serif"
    },
    "fontSize": {
      "h1": "48px",
      "h2": "38px",
      "h3": "30px",
      "body": "16px"
    }
  }
}
```

---

## 12. Component Specifications

This section provides high-level specifications for core components. Detailed documentation is available in [A-011: Component Library Documentation](A-011_Component-Library-Documentation.md).

### 12.1 Buttons

#### Primary Button
```html
<button class="button button-primary">
  Primary Action
</button>
```

```css
.button-primary {
  background-color: var(--color-primary);
  color: #FFFFFF;
  border: 2px solid var(--color-primary);
  border-radius: var(--radius-md);
  padding: 12px 24px;
  font-family: var(--font-body);
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 200ms var(--ease-out);
}

.button-primary:hover {
  background-color: var(--color-primary-light);
  border-color: var(--color-primary-light);
  box-shadow: var(--shadow-2);
}

.button-primary:active {
  background-color: var(--color-primary-dark);
  transform: scale(0.98);
}

.button-primary:disabled {
  background-color: var(--color-neutral-300);
  border-color: var(--color-neutral-300);
  color: var(--color-neutral-500);
  cursor: not-allowed;
}
```

#### Button Variants

**Secondary Button:**
```css
.button-secondary {
  background-color: transparent;
  color: var(--color-primary);
  border: 2px solid var(--color-primary);
}
```

**Tertiary Button (Ghost):**
```css
.button-tertiary {
  background-color: transparent;
  color: var(--color-primary);
  border: none;
}
```

**Danger Button:**
```css
.button-danger {
  background-color: var(--color-error);
  color: #FFFFFF;
  border: 2px solid var(--color-error);
}
```

### 12.2 Input Fields

```html
<div class="form-group">
  <label for="email" class="form-label">Email Address</label>
  <input type="email" id="email" class="form-input" placeholder="you@example.com">
  <span class="form-helper">We'll never share your email.</span>
</div>
```

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

.form-input {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid var(--color-neutral-400);
  border-radius: var(--radius-md);
  font-family: var(--font-body);
  font-size: 1rem;
  color: var(--color-neutral-900);
  transition: border-color 200ms var(--ease-out);
}

.form-input:focus {
  outline: none;
  border-color: var(--color-primary);
  border-width: 2px;
  box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
}

.form-input.error {
  border-color: var(--color-error);
}

.form-helper {
  display: block;
  font-size: 0.75rem;
  color: var(--color-neutral-600);
  margin-top: 4px;
}
```

### 12.3 Cards

```html
<div class="card">
  <img src="product.jpg" alt="Product" class="card-image">
  <div class="card-content">
    <h3 class="card-title">Organic Milk</h3>
    <p class="card-description">100% organic, fresh from our farm.</p>
    <div class="card-footer">
      <span class="card-price">৳120/liter</span>
      <button class="button-primary">Add to Cart</button>
    </div>
  </div>
</div>
```

```css
.card {
  background-color: #FFFFFF;
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-1);
  overflow: hidden;
  transition: all 200ms var(--ease-out);
}

.card:hover {
  box-shadow: var(--shadow-2);
  transform: translateY(-4px);
}

.card-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.card-content {
  padding: 24px;
}

.card-title {
  font-family: var(--font-heading);
  font-size: 1.5rem;
  color: var(--color-neutral-900);
  margin-bottom: 8px;
}

.card-description {
  font-size: 0.875rem;
  color: var(--color-neutral-700);
  margin-bottom: 16px;
}

.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card-price {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--color-primary);
}
```

---

## 13. Interaction States

All interactive elements must have clear visual feedback for different states.

### 13.1 State Specifications

| State | Description | Visual Treatment |
|-------|-------------|------------------|
| **Default** | Resting state | Standard styling |
| **Hover** | Mouse over element | Lighter color, subtle shadow |
| **Focus** | Keyboard focus | Visible outline/shadow |
| **Active** | Being clicked/pressed | Darker color, slight scale down |
| **Disabled** | Not interactive | Grayed out, cursor: not-allowed |
| **Loading** | Processing | Spinner or skeleton |
| **Error** | Invalid state | Red border, error message |
| **Success** | Valid state | Green border/icon |

### 13.2 Button States

```css
/* Default */
.button {
  background-color: var(--color-primary);
  color: #FFFFFF;
  cursor: pointer;
}

/* Hover */
.button:hover {
  background-color: var(--color-primary-light);
  box-shadow: var(--shadow-2);
}

/* Focus (keyboard) */
.button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.25);
}

/* Active (being clicked) */
.button:active {
  background-color: var(--color-primary-dark);
  transform: scale(0.98);
}

/* Disabled */
.button:disabled {
  background-color: var(--color-neutral-300);
  color: var(--color-neutral-500);
  cursor: not-allowed;
  box-shadow: none;
}

/* Loading */
.button.loading {
  position: relative;
  color: transparent;
}

.button.loading::after {
  content: '';
  position: absolute;
  width: 16px;
  height: 16px;
  border: 2px solid #FFFFFF;
  border-top-color: transparent;
  border-radius: 50%;
  animation: spin 600ms linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}
```

### 13.3 Input States

```css
/* Default */
.input {
  border: 1px solid var(--color-neutral-400);
}

/* Focus */
.input:focus {
  border-color: var(--color-primary);
  border-width: 2px;
  box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
}

/* Error */
.input.error {
  border-color: var(--color-error);
}

.input.error:focus {
  box-shadow: 0 0 0 3px rgba(244, 67, 54, 0.1);
}

/* Success */
.input.success {
  border-color: var(--color-success);
}

/* Disabled */
.input:disabled {
  background-color: var(--color-neutral-100);
  color: var(--color-neutral-500);
  cursor: not-allowed;
}
```

---

## 14. Accessibility Guidelines

### 14.1 WCAG 2.1 Compliance

All components must meet **WCAG 2.1 Level AA** standards:

- **Color Contrast:** 4.5:1 for text, 3:1 for UI components
- **Keyboard Navigation:** All interactive elements accessible via keyboard
- **Screen Readers:** Proper ARIA labels and roles
- **Focus Indicators:** Visible focus states (2px outline, 2px offset)
- **Text Resizing:** Supports 200% zoom without loss of functionality
- **Motion:** Respects `prefers-reduced-motion` preference

### 14.2 Keyboard Navigation

```html
<!-- Proper tab order -->
<form>
  <input type="text" tabindex="1">
  <input type="email" tabindex="2">
  <button type="submit" tabindex="3">Submit</button>
</form>

<!-- Skip to content link -->
<a href="#main-content" class="skip-link">Skip to main content</a>
```

```css
/* Visible focus indicator */
*:focus {
  outline: 2px solid var(--color-primary);
  outline-offset: 2px;
}

/* Skip link (visible on focus) */
.skip-link {
  position: absolute;
  top: -40px;
  left: 0;
  background: var(--color-primary);
  color: white;
  padding: 8px;
  text-decoration: none;
  z-index: 100;
}

.skip-link:focus {
  top: 0;
}
```

### 14.3 ARIA Labels

```html
<!-- Icon button with aria-label -->
<button aria-label="Add to cart">
  <i class="fas fa-shopping-cart"></i>
</button>

<!-- Form with aria-describedby -->
<label for="email">Email</label>
<input type="email" id="email" aria-describedby="email-help">
<span id="email-help">We'll never share your email</span>

<!-- Alert with role -->
<div role="alert" aria-live="polite">
  Your order has been placed successfully!
</div>
```

For complete accessibility guidelines, see [A-012: Accessibility Compliance Guide](A-012_Accessibility-Compliance-Guide.md).

---

## 15. Multi-Language Support

### 15.1 Language Toggle

```html
<div class="language-toggle">
  <button class="lang-button active" data-lang="en">English</button>
  <button class="lang-button" data-lang="bn">বাংলা</button>
</div>
```

### 15.2 Bengali-Specific Adjustments

```css
/* Automatic font switching */
html[lang="bn"] {
  font-family: var(--font-body-bengali);
  font-size: 18px;  /* 2px larger */
  line-height: 1.8;  /* Increased from 1.6 */
}

html[lang="bn"] h1,
html[lang="bn"] h2,
html[lang="bn"] h3,
html[lang="bn"] h4,
html[lang="bn"] h5,
html[lang="bn"] h6 {
  font-family: var(--font-heading-bengali);
}
```

### 15.3 Content Examples

```html
<!-- English -->
<h1 lang="en">100% Organic Dairy Products</h1>
<p lang="en">Fresh milk delivered daily from our farm in Narayanganj.</p>

<!-- Bengali -->
<h1 lang="bn">১০০% জৈব দুগ্ধজাত পণ্য</h1>
<p lang="bn">নারায়ণগঞ্জে আমাদের খামার থেকে প্রতিদিন তাজা দুধ সরবরাহ করা হয়।</p>
```

---

## 16. Implementation Guidelines

### 16.1 Getting Started

**1. Include Font Resources**
```html
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Open+Sans:wght@300;400;600;700&family=Noto+Serif+Bengali:wght@400;600;700&family=Noto+Sans+Bengali:wght@400;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
```

**2. Include Design System CSS**
```html
<link rel="stylesheet" href="/css/design-system.css">
```

**3. Apply Base Styles**
```css
/* design-system.css */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html {
  font-size: 16px;  /* Base font size */
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

body {
  font-family: var(--font-body);
  color: var(--color-neutral-900);
  background-color: var(--color-neutral-50);
  line-height: 1.6;
}

h1, h2, h3, h4, h5, h6 {
  font-family: var(--font-heading);
  font-weight: 700;
  line-height: 1.2;
}
```

### 16.2 Component Usage

**Using Design Tokens:**
```css
/* Good - uses design tokens */
.my-button {
  background: var(--color-primary);
  padding: var(--spacing-3) var(--spacing-5);
  border-radius: var(--radius-md);
}

/* Bad - hardcoded values */
.my-button {
  background: #2E7D32;
  padding: 12px 24px;
  border-radius: 8px;
}
```

### 16.3 Tools and Resources

**Design Tools:**
- **Figma:** For UI design and prototyping
- **Adobe XD:** Alternative design tool
- **Sketch:** For macOS users

**Development Tools:**
- **VS Code:** Recommended code editor
- **Chrome DevTools:** For debugging and accessibility testing
- **Lighthouse:** For performance and accessibility audits

**Testing Tools:**
- **WebAIM Contrast Checker:** Color contrast testing
- **axe DevTools:** Accessibility testing
- **WAVE:** Web accessibility evaluation tool

### 16.4 Common Pitfalls

**Don't:**
- ❌ Use hardcoded colors instead of design tokens
- ❌ Skip focus states for keyboard users
- ❌ Use color alone to convey information
- ❌ Ignore responsive breakpoints
- ❌ Use animations without `prefers-reduced-motion` check
- ❌ Forget to test with screen readers

**Do:**
- ✅ Use CSS custom properties (design tokens)
- ✅ Implement visible focus indicators
- ✅ Use icons, text, and color together
- ✅ Test on multiple screen sizes
- ✅ Respect user motion preferences
- ✅ Test with NVDA, JAWS, or VoiceOver

---

## 17. Appendix

### 17.1 Glossary

| Term | Definition |
|------|------------|
| **Design Token** | A named entity storing design decisions (colors, spacing, etc.) |
| **Component** | A reusable UI element (button, card, input) |
| **Semantic Color** | Colors with specific meanings (success, error, warning) |
| **Elevation** | Visual depth created through shadows |
| **ARIA** | Accessible Rich Internet Applications (accessibility attributes) |
| **WCAG** | Web Content Accessibility Guidelines |
| **Type Scale** | A modular system for font sizes |
| **Grid System** | A layout structure based on columns |

### 17.2 Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0 | 2026-02-01 | Initial release | Claude Code |

### 17.3 References

**Internal Documents:**
- [A-002: Brand Digital Guidelines](A-002_Brand-Digital-Guidelines.md)
- [A-011: Component Library Documentation](A-011_Component-Library-Documentation.md)
- [A-012: Accessibility Compliance Guide](A-012_Accessibility-Compliance-Guide.md)
- [A-013: Responsive Design Specifications](A-013_Responsive-Design-Specifications.md)
- [A-014: Animation & Interaction Design](A-014_Animation-Interaction-Design.md)
- [A-015: Iconography & Illustration Guidelines](A-015_Iconography-Illustration-Guidelines.md)

**External Resources:**
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [Material Design](https://material.io/design)
- [Font Awesome Icons](https://fontawesome.com/icons)
- [Google Fonts](https://fonts.google.com/)
- [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/)

### 17.4 Contact

For questions or feedback about this design system:
- **Design Team:** design@smartdairy.com.bd
- **Development Team:** dev@smartdairy.com.bd
- **Project Manager:** pm@smartdairy.com.bd

---

**End of Document**

*This design system is a living document and will be updated as the Smart Dairy digital ecosystem evolves.*
