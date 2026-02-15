# SMART DAIRY LTD.
## ICONOGRAPHY & ILLUSTRATION GUIDELINES
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | A-015 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Visual Design Lead |
| **Owner** | UI/UX Director |
| **Reviewer** | Brand Manager |
| **Status** | Final |
| **Related Documents** | A-001 (Design System), A-002 (Brand Guidelines), A-012 (Accessibility) |
| **Technology Stack** | SVG Icons, Vector Illustrations, Lottie Animations |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Iconography System](#2-iconography-system)
3. [Icon Library](#3-icon-library)
4. [Illustration Style](#4-illustration-style)
5. [Usage Guidelines](#5-usage-guidelines)
6. [Technical Specifications](#6-technical-specifications)
7. [Accessibility Considerations](#7-accessibility-considerations)
8. [Asset Management](#8-asset-management)
9. [Appendices](#9-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document establishes the comprehensive iconography and illustration standards for all Smart Dairy digital products. It defines the visual language, style principles, technical specifications, and usage guidelines for icons and illustrations that support user understanding, enhance brand identity, and create a cohesive visual experience.

### 1.2 Visual Language Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                  ICONOGRAPHY & ILLUSTRATION PHILOSOPHY           │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  DESIGN PRINCIPLES:                                             │
│                                                                  │
│  🎯 CLARITY                                                      │
│     Icons must be immediately recognizable and understandable   │
│     at all sizes. Avoid ambiguity. Prioritize clarity over      │
│     artistic expression.                                        │
│                                                                  │
│  🌱 ORGANIC NATURALNESS                                          │
│     Reflect Smart Dairy's connection to nature, farming, and    │
│     organic products. Use rounded forms, soft edges, and        │
│     natural proportions.                                        │
│                                                                  │
│  📱 CONSISTENCY                                                  │
│     Unified style across all platforms and touchpoints.         │
│     Same icon family, same stroke weights, same visual logic.   │
│                                                                  │
│  ♿ ACCESSIBILITY                                                │
│     Clear distinction for color-blind users. Proper contrast    │
│     ratios. Meaning not conveyed by color alone.                │
│                                                                  │
│  🎨 BRAND ALIGNMENT                                              │
│     Icons should feel like they belong to the Smart Dairy       │
│     family - trustworthy, modern, approachable, Bangladeshi.    │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 1.3 Icon vs Illustration Usage

| Element | Purpose | Usage Context | Format |
|---------|---------|---------------|--------|
| **Icons** | Action indicators, navigation, status | UI elements, buttons, menus | SVG, Icon Font |
| **Spot Illustrations** | Empty states, feature highlights | Pages, modals, onboarding | SVG, PNG |
| **Scene Illustrations** | Storytelling, complex concepts | Landing pages, blog posts | SVG, PNG |
| **Decorative Graphics** | Brand expression, ambiance | Headers, backgrounds, marketing | SVG, PNG |

---

## 2. ICONOGRAPHY SYSTEM

### 2.1 Icon Style Specifications

```
┌─────────────────────────────────────────────────────────────────┐
│                    ICON STYLE SPECIFICATIONS                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  GEOMETRIC FOUNDATION                                           │
│  ├── Grid: 24×24px base grid                                    │
│  ├── Keyline shapes: 20×20px live area                          │
│  ├── Padding: 2px on all sides                                  │
│  └── Alignment: Pixel-perfect on grid                           │
│                                                                  │
│  STROKE CHARACTERISTICS                                         │
│  ├── Weight: 2px consistent stroke                              │
│  ├── Terminals: Rounded caps (2px radius)                       │
│  ├── Corners: Rounded joins (2px radius)                        │
│  └── Gaps: Minimum 2px between strokes                          │
│                                                                  │
│  FILLED VARIANTS                                                │
│  ├── Used for: Active states, primary actions                   │
│  ├── Fill style: Solid color, no gradients                      │
│  └── Same geometry as outlined version                          │
│                                                                  │
│  SIZE SCALE                                                     │
│  ├── XS: 16px (Inline text, compact UI)                         │
│  ├── SM: 20px (Secondary actions)                               │
│  ├── MD: 24px (Default, navigation)                             │
│  ├── LG: 32px (Feature highlights)                              │
│  └── XL: 48px (Empty states, hero sections)                     │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 Icon Grid System

```
┌────────────────────────────────────────┐
│         ICON GRID (24×24px)            │
├────────────────────────────────────────┤
│                                        │
│  ┌────────────────────────────────┐   │
│  │ ░░░░░░░░░░░░░░░░░░░░░░░░░░░░ │   │
│  │ ░░┌──────────────────────┐░░░ │   │
│  │ ░░│                      │░░░ │   │
│  │ ░░│    LIVE AREA (20px)  │░░░ │   │
│  │ ░░│    ┌────────────┐    │░░░ │   │
│  │ ░░│    │   ICON     │    │░░░ │   │
│  │ ░░│    │  CENTER    │    │░░░ │   │
│  │ ░░│    └────────────┘    │░░░ │   │
│  │ ░░│                      │░░░ │   │
│  │ ░░└──────────────────────┘░░░ │   │
│  │ ░░░░░░░░░░░░░░░░░░░░░░░░░░░░ │   │
│  └────────────────────────────────┘   │
│                                        │
│  Key:                                  │
│  ░ = Padding (2px)                     │
│  ─ = Live area boundary                │
│                                        │
│  Center Point: 12,12                   │
│  Safe Zone: Keep strokes within 20px   │
│                                        │
└────────────────────────────────────────┘
```

### 2.3 Color Usage in Icons

| State | Style | Color | Use Case |
|-------|-------|-------|----------|
| **Default** | Outline | Primary Green (#2E7D32) | Inactive navigation |
| **Active** | Filled | Primary Green (#2E7D32) | Active navigation |
| **Secondary** | Outline | Gray 600 (#757575) | Less important actions |
| **Disabled** | Outline | Gray 400 (#BDBDBD) | Unavailable actions |
| **Error** | Filled | Error Red (#D32F2F) | Error states |
| **Success** | Filled | Success Green (#388E3C) | Success states |
| **Warning** | Filled | Warning Orange (#F57C00) | Caution states |

---

## 3. ICON LIBRARY

### 3.1 Core Navigation Icons

| Icon | Name | Usage | Bengali Label |
|------|------|-------|---------------|
| 🏠 | Home | Main dashboard | হোম |
| 🛒 | Cart | Shopping cart | কার্ট |
| 📦 | Orders | Order history | অর্ডার |
| 👤 | Profile | User account | প্রোফাইল |
| 🔍 | Search | Product search | খুঁজুন |
| ☰ | Menu | Navigation drawer | মেনু |
| ← | Back | Navigate back | পেছনে |
| ✕ | Close | Close modal/dialog | বন্ধ |

### 3.2 Action Icons

| Icon | Name | Usage | Accessibility Label |
|------|------|-------|---------------------|
| ✚ | Add | Add to cart, create new | Add item |
| ✎ | Edit | Edit information | Edit |
| 🗑️ | Delete | Remove item | Delete |
| ✓ | Check | Confirm, select | Confirm |
| ⚙️ | Settings | Configuration | Settings |
| 🔃 | Refresh | Reload, sync | Refresh |
| 📤 | Share | Share content | Share |
| ♡ | Favorite | Add to wishlist | Add to favorites |
| ♥ | Favorited | In wishlist | Remove from favorites |

### 3.3 Product & Category Icons

| Icon | Name | Category | Visual Description |
|------|------|----------|-------------------|
| 🥛 | Milk | Dairy | Bottle with liquid |
| 🥣 | Yogurt | Dairy | Bowl with spoon |
| 🧈 | Butter | Dairy | Butter block with knife |
| 🧀 | Cheese | Dairy | Cheese wedge |
| 🥩 | Beef | Meat | Meat cut |
| 🥚 | Egg | Poultry | Egg |
| 🌿 | Organic | Certification | Leaf/sprout |
| 🏔️ | Farm | Location | Mountain with barn |

### 3.4 Farm Management Icons

| Icon | Name | Usage | Context |
|------|------|-------|---------|
| 🐄 | Cow | Animal management | Animal profiles |
| 🐂 | Bull | Breeding | Breeding records |
| 🐮 | Calf | Young stock | Calf management |
| 🥛 | Milk Recording | Production | Daily milk entry |
| 💉 | Vaccination | Health | Medical records |
| 🌡️ | Temperature | Health | Fever monitoring |
| 📊 | Analytics | Reports | Production reports |
| ⚠️ | Alert | Notifications | Health alerts |

---

## 4. ILLUSTRATION STYLE

### 4.1 Illustration Principles

```
┌─────────────────────────────────────────────────────────────────┐
│                    ILLUSTRATION STYLE GUIDE                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  VISUAL CHARACTERISTICS                                         │
│  ├── Flat Design: No shadows, no gradients (or very subtle)    │
│  ├── Bold Outlines: 2-3px strokes defining shapes               │
│  ├── Limited Palette: 3-5 colors maximum per illustration       │
│  ├── Organic Shapes: Rounded, flowing forms                     │
│  ├── Human-Centered: Diverse representation of people            │
│  └── Cultural Context: Bangladeshi settings, clothing           │
│                                                                  │
│  COLOR USAGE                                                    │
│  ├── Primary: Smart Dairy Green (#2E7D32)                       │
│  ├── Secondary: Warm Cream (#FFF8E1)                            │
│  ├── Accent: Earth Brown (#5D4037)                              │
│  ├── Sky Blue: (#E3F2FD) for backgrounds                        │
│  └── Skin Tones: Warm, diverse Bangladeshi tones                │
│                                                                  │
│  COMPOSITION                                                    │
│  ├── Rule of thirds for focal points                            │
│  ├── Generous white space                                       │
│  ├── Clear visual hierarchy                                     │
│  └── Center-aligned for empty states                            │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 4.2 Illustration Types

#### Empty State Illustrations

```
┌─────────────────────────────────────────────────────────────────┐
│                    EMPTY STATE ILLUSTRATION                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  NO ORDERS EXAMPLE                                              │
│                                                                  │
│           ╭────────────╮                                        │
│          ╱            ╱│                                        │
│         │   📦       │ │        "No orders yet"                │
│         │   (empty   │ │        "আপনার কোন অর্ডার নেই"         │
│         │    box)    │╱                                         │
│         ╰────────────╯                                          │
│              │                                                   │
│         ┌────┴────┐                                             │
│         │   🍃    │  ← Small leaf accent                        │
│         └─────────┘                                             │
│                                                                  │
│  Specifications:                                                │
│  • Size: 200×200px (standard), 120×120px (compact)              │
│  • Background: None or subtle circle                             │
│  • Animation: Optional gentle float (2s loop)                   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

#### Feature Illustrations

```
┌─────────────────────────────────────────────────────────────────┐
│                    FEATURE ILLUSTRATION                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  SUBSCRIPTION SERVICE                                           │
│                                                                  │
│      ╭─────────╮                                                 │
│     ╱    🥛    ╲    ← Milk bottle floating                      │
│    │   ╭───╮    │    with delivery motion                       │
│    │   │═══│    │                                               │
│    ╰───┴───┴────╯                                                │
│         │                                                        │
│    ~~~~~🚲~~~~~   ← Delivery person on bike                     │
│    ~~~~~~~~~~~~    (Bangladeshi clothing)                       │
│         🏠                                                       │
│    ╭─────────╮                                                   │
│    │  Home   │   ← Customer house                               │
│    │    🏠   │                                                   │
│    ╰─────────╯                                                   │
│                                                                  │
│  Specifications:                                                │
│  • Size: 400×300px (hero), 300×200px (inline)                   │
│  • Animation: Lottie for hero sections                          │
│  • Responsiveness: Stack vertically on mobile                   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 4.3 Character Design Guidelines

| Element | Specification | Notes |
|---------|---------------|-------|
| **Proportions** | 3-4 heads tall | Friendly, approachable |
| **Faces** | Minimal detail | Dots for eyes, simple smile |
| **Clothing** | Bangladeshi context | Lungi, saree, modest dress |
| **Diversity** | Multiple skin tones | Represent Bangladesh population |
| **Gender** | Balanced representation | Equal visibility |
| **Age** | Mix of ages | Young workers, experienced farmers |

---

## 5. USAGE GUIDELINES

### 5.1 Icon Placement & Sizing

```
┌─────────────────────────────────────────────────────────────────┐
│                    ICON USAGE EXAMPLES                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  NAVIGATION BAR (Bottom)                                        │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  🏠      🛒       📦        💳       👤                │   │
│  │  24px    24px     24px      24px     24px               │   │
│  │  Home    Shop     Orders    Pay      Profile            │   │
│  │  (Label 10px below icon)                                │   │
│  └─────────────────────────────────────────────────────────┘   │
│                              │                                   │
│  BUTTON WITH ICON                                               │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  [🛒 Add to Cart]  ← 20px icon, 8px padding from text   │   │
│  │                                                      │   │
│  │  [→ Checkout]      ← Arrow icon indicates direction     │   │
│  └─────────────────────────────────────────────────────────┘   │
│                              │                                   │
│  LIST ITEM WITH ICON                                            │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  🥛  Saffron Organic Milk                        ৳160   │   │
│  │  32px icon with 16px padding from text                  │   │
│  └─────────────────────────────────────────────────────────┘   │
│                              │                                   │
│  INPUT FIELD WITH ICON                                          │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  🔍  Search products...                                 │   │
│  │  20px icon, left-aligned, 12px padding                  │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 5.2 Icon + Text Combinations

| Pattern | Icon Size | Text Size | Spacing | Usage |
|---------|-----------|-----------|---------|-------|
| **Button** | 20px | 16px | 8px | Actions |
| **List Item** | 24px | 16px | 12px | Navigation items |
| **Card Header** | 32px | 20px | 12px | Feature cards |
| **Input Prefix** | 20px | 16px | 12px | Search fields |
| **Empty State** | 64-120px | 18px | 16px | No data states |

### 5.3 Prohibited Uses

| Don't | Why | Alternative |
|-------|-----|-------------|
| ❌ Rotate icons arbitrarily | Breaks recognition | Use different icon |
| ❌ Stretch/squash icons | Distorts proportions | Scale proportionally |
| ❌ Use icons alone for critical actions | Accessibility issue | Add text label |
| ❌ Mix icon styles | Breaks consistency | Stick to one icon family |
| ❌ Use color alone for meaning | Color blindness | Add icon or text |
| ❌ Animate static icons | Distracting | Reserve animation for feedback |

---

## 6. TECHNICAL SPECIFICATIONS

### 6.1 SVG Format Requirements

```xml
<!-- Optimized SVG Icon Template -->
<svg 
  xmlns="http://www.w3.org/2000/svg"
  width="24" 
  height="24" 
  viewBox="0 0 24 24"
  fill="none"
  aria-hidden="true"
  focusable="false"
>
  <!-- Icon paths -->
  <path 
    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"
    stroke="currentColor"
    stroke-width="2"
    stroke-linecap="round"
    stroke-linejoin="round"
  />
  
  <!-- For filled variants, use fill instead of stroke -->
  <path 
    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"
    fill="currentColor"
  />
</svg>

<!-- Requirements:
     - viewBox="0 0 24 24" for standard icons
     - currentColor for CSS color control
     - No inline styles
     - No width/height in SVG (controlled by CSS)
     - Optimized with SVGO (no unnecessary decimals)
-->
```

### 6.2 Flutter Icon Implementation

```dart
// Custom Icon Font
class SmartDairyIcons {
  static const IconData milk = IconData(
    0xe900,
    fontFamily: 'SmartDairy',
    fontPackage: 'smart_dairy_design',
  );
  
  static const IconData farm = IconData(
    0xe901,
    fontFamily: 'SmartDairy',
    fontPackage: 'smart_dairy_design',
  );
}

// Usage
Icon(
  SmartDairyIcons.milk,
  size: 24,
  color: Theme.of(context).primaryColor,
)

// SVG Icon Widget
class SvgIcon extends StatelessWidget {
  final String assetName;
  final double size;
  final Color? color;

  @override
  Widget build(BuildContext context) {
    return SvgPicture.asset(
      'assets/icons/$assetName.svg',
      width: size,
      height: size,
      color: color ?? Theme.of(context).iconTheme.color,
    );
  }
}
```

### 6.3 File Naming Convention

| Type | Format | Example |
|------|--------|---------|
| Navigation Icon | `icon_[name]_[size].svg` | `icon_home_24.svg` |
| Action Icon | `action_[name]_[size].svg` | `action_add_24.svg` |
| Product Icon | `product_[name]_[size].svg` | `product_milk_32.svg` |
| Illustration | `illus_[context]_[variant].svg` | `illus_empty_cart_default.svg` |
| Animation | `anim_[context].json` | `anim_success_check.json` |

---

## 7. ACCESSIBILITY CONSIDERATIONS

### 7.1 Icon Accessibility

```html
<!-- Decorative icon (hidden from screen readers) -->
<button>
  <svg aria-hidden="true" focusable="false">...</svg>
  Add to Cart
</button>

<!-- Standalone icon with label -->
<button aria-label="Add to favorites">
  <svg aria-hidden="true" focusable="false">...</svg>
</button>

<!-- Icon with visible label -->
<a href="/cart" class="icon-link">
  <svg aria-hidden="true">...</svg>
  <span>Shopping Cart</span>
  <span class="badge" aria-label="3 items">3</span>
</a>

<!-- Interactive icon button -->
<button 
  type="button"
  aria-label="Close dialog"
  aria-describedby="close-desc"
>
  <svg aria-hidden="true">...</svg>
</button>
<div id="close-desc" class="visually-hidden">
  Press to close this dialog and return to the previous screen
</div>
```

### 7.2 Color Blindness Considerations

```
┌─────────────────────────────────────────────────────────────────┐
│                    COLOR-BLIND SAFE ICONS                        │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  PROBLEM: Status indicators using only color                    │
│  ❌ Red circle = Error, Green circle = Success                  │
│                                                                  │
│  SOLUTION: Add shape differentiation                            │
│  ✓ Red X = Error                                                │
│  ✓ Green ✓ = Success                                            │
│  ✓ Yellow ! = Warning                                           │
│  ✓ Blue i = Info                                                │
│                                                                  │
│  SIMULATION TESTING:                                            │
│  ├── Test icons in grayscale                                    │
│  ├── Test with Protanopia filter                                │
│  ├── Test with Deuteranopia filter                              │
│  └── Ensure meaning is preserved                                │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 8. ASSET MANAGEMENT

### 8.1 Icon Library Structure

```
assets/
└── icons/
    ├── navigation/           # Navigation icons
    │   ├── home.svg
    │   ├── cart.svg
    │   └── ...
    ├── actions/              # Action icons
    │   ├── add.svg
    │   ├── edit.svg
    │   └── ...
    ├── products/             # Product category icons
    │   ├── milk.svg
    │   ├── yogurt.svg
    │   └── ...
    ├── farm/                 # Farm management icons
    │   ├── cow.svg
    │   ├── milking.svg
    │   └── ...
    └── status/               # Status icons
        ├── success.svg
        ├── error.svg
        └── ...

assets/
└── illustrations/
    ├── empty-states/         # Empty state illustrations
    │   ├── no-orders.svg
    │   ├── no-products.svg
    │   └── ...
    ├── features/             # Feature illustrations
    │   ├── subscription.svg
    │   ├── delivery.svg
    │   └── ...
    └── onboarding/           # Onboarding illustrations
        ├── step-1.svg
        ├── step-2.svg
        └── ...
```

### 8.2 Asset Delivery Checklist

- [ ] All icons exported at 1x (24px) and 2x (48px)
- [ ] SVG files optimized (SVGO)
- [ ] Icons tested on dark and light backgrounds
- [ ] Illustrations exported in SVG and PNG formats
- [ ] Color palette documented with hex codes
- [ ] Figma library updated with all components
- [ ] Icon font generated (if applicable)
- [ ] Accessibility labels documented
- [ ] Bengali text reviewed by native speaker
- [ ] Animation files exported (Lottie JSON)

---

## 9. APPENDICES

### Appendix A: Icon Request Form

```
┌─────────────────────────────────────────────────────────────────┐
│                    NEW ICON REQUEST FORM                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Requester: ______________________  Date: _______________       │
│                                                                  │
│  Icon Name: _______________________________________________     │
│                                                                  │
│  Usage Context:                                                 │
│  [ ] Navigation    [ ] Action    [ ] Product    [ ] Status      │
│  [ ] Farm          [ ] Custom: ___________________________      │
│                                                                  │
│  Description of what the icon should represent:                 │
│  ____________________________________________________________   │
│  ____________________________________________________________   │
│                                                                  │
│  Similar existing icons: ___________________________________    │
│                                                                  │
│  Priority: [ ] High  [ ] Medium  [ ] Low                        │
│                                                                  │
│  Deadline: _________________________________________________    │
│                                                                  │
│  Approval: _________________________  Date: _______________     │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### Appendix B: Bengali Icon Labels

| English | Bengali | Context |
|---------|---------|---------|
| Home | হোম | Navigation |
| Products | পণ্য | Navigation |
| Cart | কার্ট | Navigation |
| Orders | অর্ডার | Navigation |
| Profile | প্রোফাইল | Navigation |
| Search | খুঁজুন | Action |
| Filter | ফিল্টার | Action |
| Sort | সাজান | Action |
| Add | যোগ করুন | Action |
| Edit | সম্পাদনা | Action |
| Delete | মুছুন | Action |
| Save | সংরক্ষণ | Action |
| Cancel | বাতিল | Action |
| Confirm | নিশ্চিত করুন | Action |
| Back | পেছনে | Navigation |
| Next | পরবর্তী | Navigation |
| Close | বন্ধ | Action |
| Open | খুলুন | Action |
| Download | ডাউনলোড | Action |
| Share | শেয়ার | Action |

---

## DOCUMENT REVISION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Visual Design Lead | Initial release |

---

*This document is the property of Smart Dairy Ltd. and contains confidential information.*
*Unauthorized distribution or reproduction is prohibited.*
