# SMART DAIRY LTD.
## UI/UX DESIGN SYSTEM
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | A-001 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | UX Lead |
| **Owner** | UX Lead |
| **Reviewer** | Product Manager |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Design Principles](#2-design-principles)
3. [Design Tokens](#3-design-tokens)
4. [Color System](#4-color-system)
5. [Typography System](#5-typography-system)
6. [Spacing & Layout](#6-spacing--layout)
7. [Component Library](#7-component-library)
8. [Iconography](#8-iconography)
9. [Patterns & Interactions](#9-patterns--interactions)
10. [Responsive Design](#10-responsive-design)
11. [Accessibility Guidelines](#11-accessibility-guidelines)
12. [Multi-language Support](#12-multi-language-support)
13. [Design Tools & Assets](#13-design-tools--assets)

---

## 1. INTRODUCTION

### 1.1 Purpose

This UI/UX Design System establishes the comprehensive visual and interaction standards for all Smart Dairy digital products including:
- Public Website & Corporate Portal
- B2C E-commerce Platform
- B2B Marketplace Portal
- Smart Farm Management Interface
- Mobile Applications (iOS & Android)
- Admin Dashboards

### 1.2 Design Philosophy

```
┌─────────────────────────────────────────────────────────────────┐
│                   SMART DAIRY DESIGN PHILOSOPHY                  │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  🌱 NATURAL    │  Inspired by organic farming, clean and fresh  │
│  🥛 PURE       │  Minimalist, honest, transparent               │
│  🏔 TRUSTED     │  Professional, reliable, established           │
│  📱 MODERN      │  Contemporary, innovative, tech-forward        │
│  🤝 ACCESSIBLE  │  Inclusive, easy to use, bilingual             │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 1.3 Target Users

| User Type | Primary Language | Technical Skill | Device Preference |
|-----------|-----------------|-----------------|-------------------|
| B2C Customers | Bengali/English | Medium | Mobile |
| Farm Workers | Bengali | Low | Mobile (Android) |
| Farm Managers | Bengali/English | Medium | Tablet/Desktop |
| B2B Partners | English | Medium | Desktop |
| Sales Team | Bengali/English | Medium | Mobile + Desktop |
| Administrators | English | High | Desktop |

---

## 2. DESIGN PRINCIPLES

### 2.1 Core Principles

| Principle | Description | Implementation |
|-----------|-------------|----------------|
| **Clarity First** | Every element has a clear purpose | Remove visual clutter, use whitespace effectively |
| **Consistency** | Unified experience across all touchpoints | Reuse components, standardize patterns |
| **Efficiency** | Minimize steps to complete tasks | Optimize workflows, provide shortcuts |
| **Empathy** | Design for real user needs | User research-driven decisions |
| **Resilience** | Works under all conditions | Offline capability, error recovery |

### 2.2 User Experience Goals

```
┌─────────────────────────────────────────────────────────────────┐
│                    USER EXPERIENCE HIERARCHY                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Level 5: DELIGHT    │ Memorable experiences, brand loyalty      │
│  Level 4: ENGAGING   │ Enjoyable interactions, repeat visits     │
│  Level 3: USABLE     │ Easy to learn and operate                 │
│  Level 2: RELIABLE   │ Consistent performance, minimal errors    │
│  Level 1: FUNCTIONAL │ Core tasks can be completed               │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 3. DESIGN TOKENS

### 3.1 Token Structure

```
smart-dairy-design-tokens/
├── colors/
│   ├── brand.json          # Brand colors
│   ├── semantic.json       # Functional colors
│   └── system.json         # UI framework colors
├── typography/
│   ├── fonts.json          # Font families
│   ├── scale.json          # Type scale
│   └── weights.json        # Font weights
├── spacing/
│   ├── scale.json          # Spacing scale
│   └── layout.json         # Layout tokens
├── shadows/
│   └── elevation.json      # Shadow/elevation tokens
└── breakpoints/
    └── responsive.json     # Breakpoint definitions
```

### 3.2 Token Naming Convention

```
{category}-{property}-{variant}-{state}

Examples:
- color-brand-primary-default
- color-semantic-success-hover
- spacing-component-padding-lg
- typography-heading-h1-font-size
```

---

## 4. COLOR SYSTEM

### 4.1 Brand Colors

| Token Name | HEX | RGB | Usage |
|------------|-----|-----|-------|
| **brand-primary** | #2E7D32 | rgb(46, 125, 50) | Primary brand color, CTAs |
| **brand-primary-light** | #4CAF50 | rgb(76, 175, 80) | Hover states, accents |
| **brand-primary-dark** | #1B5E20 | rgb(27, 94, 32) | Active states, emphasis |
| **brand-secondary** | #FFD700 | rgb(255, 215, 0) | Premium accents, highlights |
| **brand-secondary-light** | #FFEB3B | rgb(255, 235, 59) | Notifications, badges |
| **brand-secondary-dark** | #FBC02D | rgb(251, 192, 45) | Gold elements |

### 4.2 Semantic Colors

| Token Name | HEX | RGB | Usage |
|------------|-----|-----|-------|
| **semantic-success** | #4CAF50 | rgb(76, 175, 80) | Success messages, confirmations |
| **semantic-warning** | #FF9800 | rgb(255, 152, 0) | Warnings, cautions |
| **semantic-error** | #F44336 | rgb(244, 67, 54) | Errors, critical alerts |
| **semantic-info** | #2196F3 | rgb(33, 150, 243) | Information, help text |

### 4.3 Neutral Colors

| Token Name | HEX | RGB | Usage |
|------------|-----|-----|-------|
| **neutral-900** | #212121 | rgb(33, 33, 33) | Primary text |
| **neutral-800** | #424242 | rgb(66, 66, 66) | Secondary text |
| **neutral-700** | #616161 | rgb(97, 97, 97) | Tertiary text |
| **neutral-600** | #757575 | rgb(117, 117, 117) | Disabled text |
| **neutral-500** | #9E9E9E | rgb(158, 158, 158) | Placeholders |
| **neutral-400** | #BDBDBD | rgb(189, 189, 189) | Borders (light) |
| **neutral-300** | #E0E0E0 | rgb(224, 224, 224) | Dividers |
| **neutral-200** | #EEEEEE | rgb(238, 238, 238) | Background (subtle) |
| **neutral-100** | #F5F5F5 | rgb(245, 245, 245) | Page background |
| **neutral-50** | #FAFAFA | rgb(250, 250, 250) | Card backgrounds |

### 4.4 Color Usage Patterns

```
┌─────────────────────────────────────────────────────────────────┐
│                    COLOR USAGE PATTERNS                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Primary Actions     │ brand-primary with white text             │
│  Secondary Actions   │ neutral-100 with neutral-900 text         │
│  Destructive Actions │ semantic-error with white text            │
│  Success States      │ semantic-success with white text          │
│  Links               │ brand-primary underlined                  │
│  Backgrounds         │ neutral-50 or neutral-100                 │
│  Cards               │ white (#FFFFFF) with subtle shadow        │
│  Borders             │ neutral-300 or neutral-200                │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 5. TYPOGRAPHY SYSTEM

### 5.1 Font Families

| Category | Font Family | Fallback | Usage |
|----------|-------------|----------|-------|
| **Headings (English)** | Playfair Display | Georgia, serif | Hero headings, titles |
| **Body (English)** | Open Sans | Arial, sans-serif | Body text, UI elements |
| **Headings (Bengali)** | Noto Serif Bengali | SolaimanLipi, serif | Bengali headings |
| **Body (Bengali)** | Noto Sans Bengali | Arial Unicode MS, sans-serif | Bengali body text |
| **Monospace** | JetBrains Mono | Consolas, monospace | Code, data values |

### 5.2 Type Scale

| Level | Font | Size | Line Height | Weight | Letter Spacing |
|-------|------|------|-------------|--------|----------------|
| **Display** | Playfair Display | 48-64px | 1.2 | 700 | -0.02em |
| **H1** | Playfair Display | 36-40px | 1.3 | 700 | -0.01em |
| **H2** | Playfair Display | 28-32px | 1.3 | 600 | 0 |
| **H3** | Open Sans | 22-24px | 1.4 | 600 | 0 |
| **H4** | Open Sans | 18-20px | 1.4 | 600 | 0 |
| **H5** | Open Sans | 16px | 1.5 | 600 | 0.01em |
| **H6** | Open Sans | 14px | 1.5 | 600 | 0.01em |
| **Body Large** | Open Sans | 18px | 1.6 | 400 | 0 |
| **Body** | Open Sans | 16px | 1.6 | 400 | 0 |
| **Body Small** | Open Sans | 14px | 1.5 | 400 | 0 |
| **Caption** | Open Sans | 12px | 1.4 | 400 | 0.01em |
| **Overline** | Open Sans | 12px | 1.4 | 600 | 0.08em |

### 5.3 Typography Patterns

```css
/* Example CSS Implementation */

/* Display - Hero sections */
.display {
  font-family: 'Playfair Display', Georgia, serif;
  font-size: clamp(2.5rem, 5vw, 4rem);
  line-height: 1.2;
  font-weight: 700;
  letter-spacing: -0.02em;
  color: var(--color-neutral-900);
}

/* Body text */
.body {
  font-family: 'Open Sans', Arial, sans-serif;
  font-size: 1rem;
  line-height: 1.6;
  font-weight: 400;
  color: var(--color-neutral-800);
}

/* Bengali text override */
[lang="bn"] .display,
[lang="bn"] .heading {
  font-family: 'Noto Serif Bengali', 'SolaimanLipi', serif;
}

[lang="bn"] .body {
  font-family: 'Noto Sans Bengali', 'Arial Unicode MS', sans-serif;
  line-height: 1.8; /* Increased for Bengali script */
}
```

---

## 6. SPACING & LAYOUT

### 6.1 Spacing Scale

| Token | Value | Usage |
|-------|-------|-------|
| **space-0** | 0px | No spacing |
| **space-1** | 4px | Tight padding, icon gaps |
| **space-2** | 8px | Button padding, small gaps |
| **space-3** | 12px | Input padding |
| **space-4** | 16px | Standard gap, card padding |
| **space-5** | 20px | Medium gaps |
| **space-6** | 24px | Section padding |
| **space-8** | 32px | Large gaps, section margins |
| **space-10** | 40px | Container padding |
| **space-12** | 48px | Section spacing |
| **space-16** | 64px | Large section spacing |
| **space-20** | 80px | Page section margins |
| **space-24** | 96px | Major section breaks |

### 6.2 Layout Grid

```
┌─────────────────────────────────────────────────────────────────┐
│                    12-COLUMN GRID SYSTEM                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Container Max Width: 1440px                                     │
│  Columns: 12                                                     │
│  Gutter: 24px (desktop), 16px (tablet), 12px (mobile)           │
│  Margin: 80px (desktop), 40px (tablet), 20px (mobile)           │
│                                                                  │
│  ┌────┬────┬────┬────┬────┬────┬────┬────┬────┬────┬────┬────┐ │
│  │ 1  │ 2  │ 3  │ 4  │ 5  │ 6  │ 7  │ 8  │ 9  │ 10 │ 11 │ 12 │ │
│  └────┴────┴────┴────┴────┴────┴────┴────┴────┴────┴────┴────┘ │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 6.3 Container Sizes

| Container | Max Width | Padding |
|-----------|-----------|---------|
| **Full** | 100% | space-4 to space-6 |
| **XL** | 1440px | space-6 |
| **LG** | 1200px | space-6 |
| **MD** | 960px | space-6 |
| **SM** | 720px | space-6 |
| **XS** | 540px | space-6 |

---

## 7. COMPONENT LIBRARY

### 7.1 Buttons

#### Primary Button
```
┌─────────────────────────────────────────────────────────────────┐
│  PRIMARY BUTTON                                                  │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Default:   ┌──────────────────┐                                │
│             │  Button Text     │  bg: brand-primary            │
│             └──────────────────┘  text: white                   │
│                                   padding: 12px 24px            │
│                                   border-radius: 8px            │
│                                                                  │
│  Hover:     bg: brand-primary-dark                              │
│  Active:    transform: scale(0.98)                              │
│  Disabled:  opacity: 0.5, cursor: not-allowed                   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

#### Button Variants

| Variant | Background | Text | Border | Usage |
|---------|------------|------|--------|-------|
| **Primary** | brand-primary | white | none | Main CTAs |
| **Secondary** | transparent | brand-primary | 2px brand-primary | Alternative actions |
| **Tertiary** | transparent | neutral-700 | none | Low priority |
| **Danger** | semantic-error | white | none | Destructive actions |
| **Ghost** | neutral-100 | neutral-900 | none | Subtle actions |
| **Link** | transparent | brand-primary | none underline | Text links |

#### Button Sizes

| Size | Height | Padding | Font Size |
|------|--------|---------|-----------|
| **Small** | 32px | 8px 16px | 14px |
| **Medium** | 40px | 12px 24px | 16px |
| **Large** | 48px | 16px 32px | 18px |

### 7.2 Form Inputs

#### Text Input
```
┌─────────────────────────────────────────────────────────────────┐
│  TEXT INPUT                                                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Label                                                           │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │ Placeholder text                              │  Icon   │   │
│  └──────────────────────────────────────────────────────────┘   │
│  Helper text / Error message                                     │
│                                                                  │
│  Specifications:                                                 │
│  - Height: 48px (medium), 40px (small)                          │
│  - Border: 1px solid neutral-300                                │
│  - Border-radius: 8px                                           │
│  - Padding: 12px 16px                                           │
│  - Focus: border-color: brand-primary, box-shadow: 0 0 0 3px    │
│    rgba(46, 125, 50, 0.1)                                       │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

#### Input States

| State | Border | Background | Text |
|-------|--------|------------|------|
| **Default** | neutral-300 | white | neutral-900 |
| **Focus** | brand-primary | white | neutral-900 |
| **Error** | semantic-error | white | neutral-900 |
| **Disabled** | neutral-200 | neutral-50 | neutral-500 |
| **Read-only** | neutral-200 | neutral-50 | neutral-700 |

### 7.3 Cards

```
┌─────────────────────────────────────────────────────────────────┐
│  CARD COMPONENT                                                  │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │ ┌────────────────────────────────────────────────────┐   │   │
│  │ │                                                    │   │   │
│  │ │              Image / Media Area                    │   │   │
│  │ │              (16:9 aspect ratio)                   │   │   │
│  │ │                                                    │   │   │
│  │ └────────────────────────────────────────────────────┘   │   │
│  │                                                          │   │
│  │  Card Title                                              │   │
│  │  Card description text goes here. This area contains     │   │
│  │  the main content of the card component.                 │   │
│  │                                                          │   │
│  │  ┌──────────────────┐  ┌──────────────────┐             │   │
│  │  │   Action 1       │  │   Action 2       │             │   │
│  │  └──────────────────┘  └──────────────────┘             │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                  │
│  Specifications:                                                 │
│  - Background: white                                             │
│  - Border-radius: 12px                                           │
│  - Box-shadow: 0 1px 3px rgba(0,0,0,0.1)                        │
│  - Padding: 24px                                                 │
│  - Hover: translateY(-2px), shadow increase                     │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 7.4 Navigation

#### Header Navigation
```
┌─────────────────────────────────────────────────────────────────┐
│  HEADER NAVIGATION                                               │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  [LOGO]    Home  Products  Farm  About    🔍  🛒  👤  🌐 │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                  │
│  Height: 64px                                                    │
│  Background: white                                               │
│  Border-bottom: 1px solid neutral-200                            │
│  Position: sticky top: 0                                         │
│  Z-index: 1000                                                   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

#### Mobile Navigation
```
┌─────────────────────────────────────────────────────────────────┐
│  MOBILE BOTTOM NAVIGATION                                        │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │    🏠      📦      🐄      🛒      👤                   │   │
│  │   Home   Products  Farm    Cart   Account               │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                  │
│  Height: 64px (safe area included)                               │
│  Background: white                                               │
│  Border-top: 1px solid neutral-200                               │
│  Position: fixed bottom: 0                                       │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 7.5 Data Tables

| Element | Specification |
|---------|---------------|
| **Header** | Background: neutral-50, Text: neutral-700, Font-weight: 600 |
| **Row** | Height: 56px, Border-bottom: 1px solid neutral-200 |
| **Cell Padding** | 16px horizontal |
| **Row Hover** | Background: neutral-50 |
| **Selected Row** | Background: rgba(46, 125, 50, 0.05) |
| **Sort Icon** | 16px, neutral-500 (active: brand-primary) |
| **Pagination** | Bottom right, 8 items per page default |

### 7.6 Modals & Dialogs

```
┌─────────────────────────────────────────────────────────────────┐
│  MODAL DIALOG                                                    │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  Modal Title                                    [×]      │   │
│  ├──────────────────────────────────────────────────────────┤   │
│  │                                                          │   │
│  │  Modal content goes here. This can include:              │   │
│  │  - Text content                                          │   │
│  │  - Form elements                                         │   │
│  │  - Confirmation messages                                 │   │
│  │                                                          │   │
│  ├──────────────────────────────────────────────────────────┤   │
│  │  ┌──────────────────┐  ┌──────────────────┐             │   │
│  │  │    Cancel        │  │    Confirm       │             │   │
│  │  └──────────────────┘  └──────────────────┘             │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                  │
│  Overlay: rgba(0, 0, 0, 0.5)                                     │
│  Max-width: 560px (small), 720px (medium), 960px (large)        │
│  Border-radius: 16px                                             │
│  Shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25)                  │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 7.7 Alerts & Notifications

| Type | Icon | Background | Border | Text |
|------|------|------------|--------|------|
| **Success** | ✓ | semantic-success/5% | semantic-success | neutral-900 |
| **Warning** | ⚠ | semantic-warning/5% | semantic-warning | neutral-900 |
| **Error** | ✕ | semantic-error/5% | semantic-error | neutral-900 |
| **Info** | ℹ | semantic-info/5% | semantic-info | neutral-900 |

---

## 8. ICONOGRAPHY

### 8.1 Icon System

| Property | Specification |
|----------|---------------|
| **Icon Library** | Font Awesome 6 + Custom icons |
| **Default Size** | 20px |
| **Stroke Width** | 1.5px (outlined), solid fill |
| **Color** | Inherit from text color |
| **Sizes** | 16px (sm), 20px (md), 24px (lg), 32px (xl) |

### 8.2 Icon Categories

| Category | Examples | Usage |
|----------|----------|-------|
| **Navigation** | home, menu, arrow-left, arrow-right | Navigation controls |
| **Actions** | plus, edit, trash, save, download | Action buttons |
| **Status** | check, x, alert-circle, info | Status indicators |
| **Communication** | mail, phone, message | Contact methods |
| **E-commerce** | cart, heart, star, package | Shopping features |
| **Farm** | cow, milk, grass, barn | Farm-specific icons |

---

## 9. PATTERNS & INTERACTIONS

### 9.1 Animation Principles

| Property | Value | Usage |
|----------|-------|-------|
| **Default Duration** | 200ms | Micro-interactions |
| **Modal Duration** | 300ms | Dialog transitions |
| **Page Transition** | 400ms | Route changes |
| **Easing (Default)** | ease-in-out | Standard transitions |
| **Easing (Enter)** | cubic-bezier(0, 0, 0.2, 1) | Elements appearing |
| **Easing (Exit)** | cubic-bezier(0.4, 0, 1, 1) | Elements disappearing |

### 9.2 Micro-interactions

```css
/* Button Hover */
.btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(46, 125, 50, 0.25);
  transition: all 200ms ease-in-out;
}

/* Card Hover */
.card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
  transition: all 200ms ease-in-out;
}

/* Input Focus */
.input:focus {
  border-color: #2E7D32;
  box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
  transition: all 150ms ease-in-out;
}

/* Loading Spinner */
@keyframes spin {
  to { transform: rotate(360deg); }
}
.spinner {
  animation: spin 1s linear infinite;
}
```

### 9.3 Loading States

| State | Visual | Usage |
|-------|--------|-------|
| **Skeleton** | Animated placeholder blocks | Content loading |
| **Spinner** | Rotating circular indicator | Action in progress |
| **Progress Bar** | Horizontal progress indicator | File uploads, multi-step |
| **Text Shimmer** | Animated gradient text | Placeholder text |

---

## 10. RESPONSIVE DESIGN

### 10.1 Breakpoints

| Name | Min Width | Max Width | Target |
|------|-----------|-----------|--------|
| **XS** | 0px | 575px | Small phones |
| **SM** | 576px | 767px | Large phones |
| **MD** | 768px | 991px | Tablets |
| **LG** | 992px | 1199px | Small laptops |
| **XL** | 1200px | 1439px | Desktops |
| **XXL** | 1440px | ∞ | Large screens |

### 10.2 Responsive Patterns

```
┌─────────────────────────────────────────────────────────────────┐
│                    RESPONSIVE BEHAVIORS                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Desktop (1200px+)                                               │
│  ┌────────────┬───────────────────────────────────────────┐     │
│  │  Sidebar   │          Main Content Area                │     │
│  │  (fixed)   │                                           │     │
│  └────────────┴───────────────────────────────────────────┘     │
│                                                                  │
│  Tablet (768px-1199px)                                           │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  [≡ Menu]  Header Content                                │   │
│  ├──────────────────────────────────────────────────────────┤   │
│  │                  Main Content Area                       │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                  │
│  Mobile (<768px)                                                 │
│  ┌──────────────────────────────────────────────┐               │
│  │  [≡]     Logo      [🔍] [🛒]                 │               │
│  ├──────────────────────────────────────────────┤               │
│  │            Main Content                      │               │
│  │                                              │               │
│  ├──────────────────────────────────────────────┤               │
│  │  🏠    📦    🐄    🛒    👤                 │               │
│  └──────────────────────────────────────────────┘               │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 11. ACCESSIBILITY GUIDELINES

### 11.1 WCAG 2.1 Level AA Compliance

| Guideline | Requirement | Implementation |
|-----------|-------------|----------------|
| **Color Contrast** | 4.5:1 for text | All text meets minimum ratio |
| **Focus Indicators** | Visible focus states | 2px outline, offset 2px |
| **Keyboard Navigation** | Full keyboard support | Tab order logical, no traps |
| **Screen Reader** | ARIA labels | All interactive elements labeled |
| **Text Resizing** | 200% without loss | Responsive text, no fixed widths |
| **Motion** | Respect prefers-reduced-motion | Disable animations if set |

### 11.2 ARIA Patterns

```html
<!-- Button with icon -->
<button aria-label="Add to cart">
  <span aria-hidden="true">🛒</span>
</button>

<!-- Modal -->
<div role="dialog" aria-modal="true" aria-labelledby="modal-title">
  <h2 id="modal-title">Confirm Action</h2>
</div>

<!-- Alert -->
<div role="alert" aria-live="polite">
  Item added to cart
</div>

<!-- Navigation -->
<nav aria-label="Main navigation">
  <ul role="menubar">
    <li role="none">
      <a role="menuitem" href="/">Home</a>
    </li>
  </ul>
</nav>
```

---

## 12. MULTI-LANGUAGE SUPPORT

### 12.1 Bengali (বাংলা) Design Considerations

| Aspect | English | Bengali | Notes |
|--------|---------|---------|-------|
| **Font Size** | 16px | 18px | Bengali needs larger size |
| **Line Height** | 1.6 | 1.8 | More space for complex characters |
| **Word Spacing** | normal | slightly increased | Improves readability |
| **Text Direction** | LTR | LTR | Same as English |
| **Label Length** | Short | Often 20-30% longer | Allow text expansion |

### 12.2 Language Toggle Pattern

```
┌─────────────────────────────────────────────────────────────────┐
│  LANGUAGE SELECTOR                                               │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Active:   ┌──────────┐                                         │
│            │  🇧🇩 BN  │  বাংলা                                  │
│            └──────────┘                                         │
│                                                                  │
│  Options:  ┌─────────────────┐                                  │
│            │  🇧🇩 বাংলা      │                                  │
│            │  🇬🇧 English   │                                  │
│            └─────────────────┘                                  │
│                                                                  │
│  Placement: Top-right corner of header                          │
│  Persistence: Save preference in localStorage                   │
│  Default: Based on browser language                             │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 13. DESIGN TOOLS & ASSETS

### 13.1 Design Tool Setup

| Tool | Purpose | File Location |
|------|---------|---------------|
| **Figma** | UI Design, Prototyping | `/design/figma/` |
| **Adobe XD** | Alternative design tool | `/design/xd/` |
| **Storybook** | Component documentation | Dev environment |
| **Zeplin** | Design handoff | Cloud |

### 13.2 Asset Library Structure

```
assets/
├── logos/
│   ├── logo-primary.svg
│   ├── logo-white.svg
│   ├── logo-mark.svg
│   └── favicon/
├── icons/
│   ├── ui-icons/
│   ├── farm-icons/
│   └── custom-icons.svg
├── illustrations/
│   ├── hero-illustrations/
│   ├── empty-states/
│   └── onboarding/
├── photography/
│   ├── products/
│   ├── farm/
│   └── people/
└── videos/
    ├── hero-videos/
    └── tutorials/
```

### 13.3 Figma Component Library Structure

```
Smart Dairy Design System (Figma)
├── 📁 Foundations
│   ├── 🎨 Colors
│   ├── 🔤 Typography
│   ├── 📐 Spacing
│   └── 🌘 Shadows
├── 📁 Components
│   ├── Buttons
│   ├── Inputs
│   ├── Cards
│   ├── Navigation
│   ├── Tables
│   └── Modals
├── 📁 Patterns
│   ├── Forms
│   ├── Lists
│   ├── Filters
│   └── Search
├── 📁 Templates
│   ├── Page Layouts
│   ├── Email Templates
│   └── Mobile Screens
└── 📁 Prototypes
    ├── B2C Flows
    ├── B2B Flows
    └── Farm App Flows
```

---

## APPENDIX A: Design Token Reference

### CSS Custom Properties (Variables)

```css
:root {
  /* Brand Colors */
  --color-brand-primary: #2E7D32;
  --color-brand-primary-light: #4CAF50;
  --color-brand-primary-dark: #1B5E20;
  --color-brand-secondary: #FFD700;
  
  /* Semantic Colors */
  --color-success: #4CAF50;
  --color-warning: #FF9800;
  --color-error: #F44336;
  --color-info: #2196F3;
  
  /* Neutral Scale */
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
  --font-body: 'Open Sans', Arial, sans-serif;
  --font-bengali-heading: 'Noto Serif Bengali', 'SolaimanLipi', serif;
  --font-bengali-body: 'Noto Sans Bengali', 'Arial Unicode MS', sans-serif;
  
  /* Spacing */
  --space-1: 4px;
  --space-2: 8px;
  --space-3: 12px;
  --space-4: 16px;
  --space-5: 20px;
  --space-6: 24px;
  --space-8: 32px;
  --space-10: 40px;
  --space-12: 48px;
  --space-16: 64px;
  --space-20: 80px;
  --space-24: 96px;
  
  /* Shadows */
  --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
  --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
  --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
  --shadow-xl: 0 20px 25px rgba(0,0,0,0.15);
  
  /* Border Radius */
  --radius-sm: 4px;
  --radius-md: 8px;
  --radius-lg: 12px;
  --radius-xl: 16px;
  --radius-full: 9999px;
  
  /* Transitions */
  --transition-fast: 150ms ease-in-out;
  --transition-base: 200ms ease-in-out;
  --transition-slow: 300ms ease-in-out;
}
```

---

**END OF UI/UX DESIGN SYSTEM DOCUMENT**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Jan 31, 2026 | UX Lead | Initial version |
