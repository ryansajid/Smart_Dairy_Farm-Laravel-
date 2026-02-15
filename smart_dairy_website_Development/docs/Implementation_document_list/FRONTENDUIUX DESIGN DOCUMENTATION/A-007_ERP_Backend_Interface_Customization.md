# SMART DAIRY LTD.
## ERP Backend Interface Customization
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | A-007 |
| **Version** | 1.0 |
| **Date** | 2026-02-01 |
| **Author** | UI/UX Lead |
| **Owner** | Product Manager |
| **Status** | Final |
| **Related Documents** | A-001 (UI/UX Design System), A-002 (Brand Guidelines), A-011 (Component Library), A-013 (Responsive Design), SRS, URD, BRD |
| **Technology Stack** | Odoo 19 CE, OWL Framework, QWeb Templates, Bootstrap 5, PostgreSQL 16 |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
   - 1.1 Purpose
   - 1.2 Scope
   - 1.3 Target Users
   - 1.4 Design Principles
   - 1.5 Related Documents

2. [Design Foundation](#2-design-foundation)
   - 2.1 Design System Integration
   - 2.2 Brand Guidelines Application
   - 2.3 Technology Constraints

3. [Odoo 19 CE Standard Interface Review](#3-odoo-19-ce-standard-interface-review)
   - 3.1 Current Backend Architecture
   - 3.2 Standard UI Components
   - 3.3 Customization Approach

4. [User Role-Based Customization](#4-user-role-based-customization)
   - 4.1 Farm Manager Dashboard
   - 4.2 Farm Worker Mobile View
   - 4.3 Warehouse Staff Interface
   - 4.4 Accountant/Finance View
   - 4.5 Admin Configuration Panel

5. [Form Redesign Specifications](#5-form-redesign-specifications)
   - 5.1 Animal Profile Forms
   - 5.2 Milk Production Entry
   - 5.3 Sales Order Forms
   - 5.4 Purchase Orders
   - 5.5 Inventory Adjustments

6. [List/Tree View Customizations](#6-listtree-view-customizations)
   - 6.1 Animal List Views
   - 6.2 Sales Order Views
   - 6.3 Inventory Views
   - 6.4 Production Views

7. [Dashboard & Reporting Interfaces](#7-dashboard--reporting-interfaces)
   - 7.1 Executive Dashboard
   - 7.2 Farm Operations Dashboard
   - 7.3 Sales Analytics Dashboard
   - 7.4 Inventory Dashboard

8. [Navigation Restructuring](#8-navigation-restructuring)
   - 8.1 Menu Organization
   - 8.2 Quick Access Features
   - 8.3 Search Enhancement

9. [Bengali Localization Patterns](#9-bengali-localization-patterns)
   - 9.1 Language Switching
   - 9.2 Font Rendering
   - 9.3 Cultural Considerations

10. [Mobile Responsive Adaptations](#10-mobile-responsive-adaptations)
    - 10.1 Touch-Optimized Interface
    - 10.2 Simplified Mobile Views
    - 10.3 Offline Capability

11. [OWL Framework Implementation](#11-owl-framework-implementation)
    - 11.1 Component Structure
    - 11.2 QWeb Templates
    - 11.3 XML View Inheritance

12. [Implementation Guidelines](#12-implementation-guidelines)
    - 12.1 Development Handoff
    - 12.2 Design Tokens
    - 12.3 Component Mapping

13. [Quality Assurance](#13-quality-assurance)
    - 13.1 Design QA Checklist
    - 13.2 Testing Requirements
    - 13.3 Acceptance Criteria

14. [Appendices](#14-appendices)
    - Appendix A: Design Assets Inventory
    - Appendix B: Code Snippets
    - Appendix C: Third-Party Resources

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides comprehensive specifications for customizing the Odoo 19 Community Edition backend interface for Smart Dairy Ltd.'s integrated ERP system. The customizations ensure the interface aligns with Smart Dairy's brand identity, supports diverse user groups (from low-literacy farm workers to professional managers), and implements Bengali language localization.

**Key Objectives:**
- Simplify complex ERP workflows for farm workers with limited computer experience
- Create role-based interfaces optimized for each user group's specific tasks
- Implement Bengali language support with culturally appropriate UI patterns
- Maintain Odoo 19 upgrade compatibility through proper inheritance patterns
- Ensure mobile responsiveness for field workers accessing the system on tablets/phones

### 1.2 Scope

**In Scope:**
- Customization of all farm management module interfaces (animal profiles, milk production, health records, breeding)
- Sales and purchase order form redesigns for B2C and B2B workflows
- Dashboard creation for executives, farm managers, and warehouse staff
- Navigation restructuring for simplified menu access
- Bengali language UI implementation (labels, buttons, help text, error messages)
- Mobile-responsive interface adaptations for tablet/phone access
- OWL Framework component customizations and QWeb template modifications

**Out of Scope:**
- Public-facing website design (covered in A-003)
- B2C e-commerce portal (covered in A-004)
- B2B marketplace portal (covered in A-005)
- Mobile app interfaces (covered in A-008, A-009, A-010)
- New functional development (only UI/UX customization of existing Odoo modules)

### 1.3 Target Users

**Primary User Groups:**

1. **Farm Workers (Low Tech Literacy)**
   - **Persona:** Mizan Rahman, 28 years old, secondary education, limited computer experience
   - **Usage:** Daily milk production entry, animal health logging, task completion
   - **Needs:** Simplified Bengali interface, large touch targets, icon-heavy UI, voice input support
   - **Device:** Tablet (Android 8+), occasionally desktop during training

2. **Farm Manager**
   - **Persona:** Kamal Hossain, 35 years old, diploma in agriculture, moderate tech literacy
   - **Usage:** Herd management oversight, production monitoring, staff task assignment
   - **Needs:** Dashboard with KPIs, real-time alerts, approval workflows, Bengali/English toggle
   - **Device:** Desktop (primary), tablet (field inspections)

3. **Warehouse Staff**
   - **Persona:** Abdul Rahman, 30 years old, high school education, basic computer skills
   - **Usage:** Inventory management, feed stock tracking, purchase requisitions
   - **Needs:** Simplified inventory views, barcode scanning integration, stock alerts
   - **Device:** Desktop (fixed workstation)

4. **Accountant/Finance Team**
   - **Persona:** Farhana Rahman, 32 years old, MBA, high digital literacy
   - **Usage:** Financial reporting, invoice management, VAT calculations, payment tracking
   - **Needs:** Professional accounting interface, advanced filters, export capabilities
   - **Device:** Desktop (dual monitors)

5. **System Administrator**
   - **Persona:** IT Manager, 35 years old, technical background
   - **Usage:** User management, system configuration, module installation, backup management
   - **Needs:** Full Odoo admin interface with enhanced navigation
   - **Device:** Desktop

### 1.4 Design Principles

**NATURAL**
- Use organic farming imagery (green fields, cows, milk) in dashboard illustrations
- Earth-tone color palette (#2E7D32 primary green) throughout interface
- Natural photography in empty states and onboarding

**PURE**
- Clean, uncluttered layouts with ample whitespace
- Honest data presentation (no misleading charts/metrics)
- Transparent workflows with clear next-step guidance

**TRUSTED**
- Consistent UI patterns across all modules (predictable interface)
- Professional typography (Playfair Display headings, Open Sans body)
- Reliable error handling with helpful recovery options

**MODERN**
- Contemporary Odoo 19 OWL Framework implementation
- Smooth animations and transitions (200-300ms)
- Progressive Web App capabilities for offline access

**ACCESSIBLE**
- WCAG 2.1 Level AA compliance (color contrast, keyboard navigation)
- Bengali language full support (not just translation)
- Low-literacy accommodations (icons, voice input, simplified workflows)

### 1.5 Related Documents

| Document | Relevance |
|----------|-----------|
| **A-001: UI/UX Design System** | Color palette, typography, spacing, component library foundation |
| **A-002: Brand Digital Guidelines** | Logo usage, brand voice, photography style for dashboard imagery |
| **A-011: Component Library** | Reusable UI components (buttons, forms, cards) referenced in designs |
| **A-013: Responsive Design Specifications** | Breakpoint definitions, mobile-first patterns for tablet/phone access |
| **SRS (Software Requirements Specification)** | Technical constraints (Odoo 19 CE, PostgreSQL 16, performance requirements <3s page load) |
| **URD (User Requirements Document)** | Functional requirements for farm module, sales, inventory workflows |
| **BRD (Business Requirements Document)** | User personas, business context (255→800 cattle scaling), success metrics |
| **Technology Stack Document** | Odoo 19 OWL Framework, QWeb templates, Bootstrap 5, deployment architecture |

---

## 2. DESIGN FOUNDATION

### 2.1 Design System Integration

**Color Palette (From A-001)**

All ERP interface customizations must use the Smart Dairy design system colors:

| Color Name | Hex Code | RGB | Usage | WCAG Compliance |
|------------|----------|-----|-------|-----------------|
| **Primary Green** | `#2E7D32` | 46, 125, 50 | Primary actions, navigation highlights, success states | AA (5.2:1 on white) |
| **Primary Light** | `#4CAF50` | 76, 175, 80 | Hover states, backgrounds, secondary highlights | AA (4.5:1 on white) |
| **Primary Dark** | `#1B5E20` | 27, 94, 32 | Active/pressed states, dark mode primary | AAA (9.8:1 on white) |
| **Premium Gold** | `#FFD700` | 255, 215, 0 | Premium features, VIP customer badges | FAIL - Use darker variant |
| **Premium Gold (Dark)** | `#B8860B` | 184, 134, 11 | Compliant alternative for text/icons | AA (4.8:1 on white) |
| **Success Green** | `#4CAF50` | 76, 175, 80 | Confirmation messages, completed tasks | AA |
| **Warning Orange** | `#FF9800` | 255, 152, 0 | Alerts, pending approvals | AAA (3.1:1 on white for large text) |
| **Error Red** | `#F44336` | 244, 67, 54 | Errors, critical alerts, delete actions | AA (4.5:1 on white) |
| **Info Blue** | `#2196F3` | 33, 150, 243 | Informational messages, help tooltips | AA (4.5:1 on white) |
| **Text Primary** | `#212121` | 33, 33, 33 | Body text, headings | AAA (16.1:1 on white) |
| **Text Secondary** | `#616161` | 97, 97, 97 | Labels, captions, disabled state | AA (7.0:1 on white) |
| **Background Subtle** | `#F5F5F5` | 245, 245, 245 | Page backgrounds, card backgrounds | - |
| **Border Default** | `#E0E0E0` | 224, 224, 224 | Input borders, dividers, table borders | - |

**Typography (From A-001)**

| Element | Font Family | Weight | Size | Line Height | Letter Spacing |
|---------|-------------|--------|------|-------------|----------------|
| **H1 (Page Titles)** | Playfair Display | 700 Bold | 32px | 40px | -0.5px |
| **H2 (Section Headings)** | Playfair Display | 600 SemiBold | 24px | 32px | -0.25px |
| **H3 (Card Titles)** | Playfair Display | 600 SemiBold | 20px | 28px | 0px |
| **H4 (Form Labels)** | Open Sans | 600 SemiBold | 16px | 24px | 0px |
| **Body Text** | Open Sans | 400 Regular | 14px | 22px | 0px |
| **Small Text** | Open Sans | 400 Regular | 12px | 18px | 0px |
| **Button Text** | Open Sans | 600 SemiBold | 14px | 20px | 0.5px |
| **Bengali Heading** | Noto Serif Bengali | 700 Bold | 34px (+2px) | 44px (1.8x) | 0px |
| **Bengali Body** | Noto Sans Bengali | 400 Regular | 16px (+2px) | 28px (1.8x) | 0px |

**Spacing Scale (8px Base Unit)**

```
4px   (0.5 unit)  - Tight spacing (icon padding, small gaps)
8px   (1 unit)    - Standard spacing (input padding, button padding)
16px  (2 units)   - Section spacing (card padding, form field gaps)
24px  (3 units)   - Component spacing (between cards, list items)
32px  (4 units)   - Layout spacing (between sections)
48px  (6 units)   - Page spacing (top/bottom margins)
64px  (8 units)   - Major section dividers
```

### 2.2 Brand Guidelines Application

**Logo Placement**
- **Top-left corner** of Odoo backend navbar (replace default Odoo logo)
- **Horizontal version** (3:1 ratio) at 180px width
- **Smart Dairy** wordmark + stylized cow icon
- Link to Farm Management Dashboard (default landing page)

**Dashboard Imagery**
- Use **natural farm photography** for empty states and onboarding
- **Illustrations** for error states (friendly cow character, not generic icons)
- **Charts/Graphs** use primary green color scheme with subtle gradients

**Tone of Voice in UI Copy**
- **Friendly yet professional** (e.g., "Great! Your milk production was recorded" vs "Record saved successfully")
- **Supportive error messages** (e.g., "Oops! We couldn't find that animal. Try scanning the ear tag again" vs "Error 404: Record not found")
- **Bengali translations** use respectful formal address ("আপনি" not "তুমি") for all users

### 2.3 Technology Constraints

**Odoo 19 Community Edition Architecture**

According to official Odoo 19 documentation and our research findings:

- **OWL Framework**: Modern component framework (<20kb gzipped), written in TypeScript
- **Component Structure**: Each component consists of 3 files
  - `my_component.js` (JavaScript class definition)
  - `my_component.xml` (QWeb template)
  - `my_component.scss` (styles, added to assets bundle)
- **QWeb Templating**: XML-based with `t-` directives
  - `t-if` (conditionals)
  - `t-foreach` (loops)
  - `t-esc` (safe text output)
  - `t-raw` (HTML output)
  - `t-att-*` (dynamic attributes)

**Customization Approach**

✅ **Recommended Methods:**
1. **XML View Inheritance** (extend existing views without modifying core)
2. **QWeb Template Extension** (inherit and modify specific elements)
3. **CSS Overrides** (via custom assets bundle)
4. **JavaScript Actions** (custom wizards, buttons)
5. **OWL Component Extension** (inherit and customize components)

❌ **Avoid:**
1. **Direct Core File Modification** (breaks upgrade compatibility)
2. **XPath Overwrites** (replace entire views, maintenance nightmare)
3. **Inline Styles** (use CSS classes from design system)

**Performance Requirements** (from SRS):
- Page load < 3 seconds (95th percentile)
- API response < 500ms (database queries)
- Support 1,000+ concurrent users
- Real-time updates via WebSocket (for IoT data, milk production)

---

## 3. ODOO 19 CE STANDARD INTERFACE REVIEW

### 3.1 Current Backend Architecture

**Default Odoo 19 Backend Structure:**

```
┌─────────────────────────────────────────────────────────┐
│  Top Navbar (Purple/Odoo Brand)                        │
│  [Logo] [App Switcher] [Search]  [Messages] [Profile] │
└─────────────────────────────────────────────────────────┘
┌─────────┬───────────────────────────────────────────────┐
│         │  Breadcrumbs                                  │
│ Left    ├───────────────────────────────────────────────┤
│ Menu    │                                               │
│         │  Main Content Area                            │
│ -Sales  │  (List View / Form View / Kanban)            │
│ -Inv.   │                                               │
│ -Mfg    │                                               │
│ -Farm   │                                               │
│ ...     │                                               │
└─────────┴───────────────────────────────────────────────┘
```

**Identified Issues for Smart Dairy:**

1. **Generic Purple Branding** → Must be replaced with Smart Dairy green (#2E7D32)
2. **Complex Menu Structure** → 50+ menu items overwhelming for farm workers
3. **Dense Forms** → Too many fields visible simultaneously (confusing for low-literacy users)
4. **English-Only Labels** → No Bengali support in field labels, buttons, help text
5. **Desktop-Optimized** → Poor mobile/tablet experience (small touch targets, horizontal scrolling)
6. **Generic Icons** → FontAwesome icons don't represent dairy-specific actions
7. **Limited Dashboards** → Default pivots/graphs insufficient for farm KPIs

### 3.2 Standard UI Components

**Odoo 19 Built-In Widgets:**

| Widget Type | Usage | Customization Needed |
|-------------|-------|----------------------|
| `char` | Text input | ✅ Add Bengali placeholder text |
| `text` | Multiline text | ✅ Increase font size for Bengali |
| `integer` | Number input | ✅ Voice input integration |
| `float` | Decimal numbers | ✅ Auto-formatting for liters (milk production) |
| `date` | Date picker | ✅ Bengali month names |
| `datetime` | Date + time | ✅ Simplified time picker (AM/PM) |
| `selection` | Dropdown | ✅ Icon support for low-literacy users |
| `many2one` | Related record lookup | ✅ RFID scan trigger button |
| `one2many` | Child records list | ✅ Simplified grid view |
| `many2many` | Multi-select | ✅ Tag-style visual representation |
| `binary` | File upload | ✅ Camera capture option (photos) |
| `image` | Image upload | ✅ Thumbnail preview, crop tool |
| `statusbar` | Workflow stages | ✅ Color-coded status (green/yellow/red) |
| `kanban` | Card view | ✅ Dairy-specific card templates |
| `graph` | Charts | ✅ Green color scheme, simplified axes |

### 3.3 Customization Approach

**Three-Layer Customization Strategy:**

**Layer 1: Global Theme Customization**
- Replace Odoo purple (#714B67) with Smart Dairy green (#2E7D32) globally
- Inject custom CSS for typography (Playfair headings, Open Sans body, Noto Bengali)
- Override default icon set with dairy-specific SVG icons (from A-015)

**Layer 2: Module-Specific View Inheritance**
- Farm Management module: Heavily customized forms/views
- Sales module: Modified order forms for B2C/B2B workflows
- Inventory module: Simplified views for warehouse staff
- Accounting module: Minimal changes (professional users comfortable with complexity)

**Layer 3: Role-Based UI Switching**
- Farm Worker mode: Simplified single-column forms, large buttons, icon-heavy
- Manager mode: Standard Odoo views with dashboard enhancements
- Admin mode: Full Odoo backend access

**Implementation via Custom Module:**

```python
# smart_dairy_customization/__manifest__.py
{
    'name': 'Smart Dairy UI Customization',
    'version': '1.0',
    'category': 'Customization',
    'depends': ['web', 'farm_management', 'sale', 'stock'],
    'data': [
        'views/web_theme.xml',           # Global theme overrides
        'views/farm_forms.xml',          # Custom farm forms
        'views/dashboard_views.xml',     # Executive dashboards
        'views/menu_restructure.xml',    # Simplified navigation
    ],
    'assets': {
        'web.assets_backend': [
            'smart_dairy_customization/static/src/scss/theme.scss',
            'smart_dairy_customization/static/src/js/components/*.js',
        ],
    },
}
```

---

## 4. USER ROLE-BASED CUSTOMIZATION

### 4.1 Farm Manager Dashboard

**Primary User:** Kamal Hossain (Farm Supervisor, 35yo, diploma in agriculture)

**Dashboard Layout (Desktop: 1920x1080)**

```
┌─────────────────────────────────────────────────────────────────────┐
│ Farm Operations Dashboard                        [Bengali | English]│
├──────────────┬──────────────┬──────────────┬─────────────────────────┤
│ KPI Card 1   │ KPI Card 2   │ KPI Card 3   │ KPI Card 4              │
│ Total Cattle │ Today's Milk │ Health       │ Feed Stock              │
│   255        │   950L       │ Alerts: 3    │ 7 Days Left             │
│ ↑ +5 (2%)    │ ↑ +50L (5%)  │ ⚠️           │ ⚠️ Order Soon            │
└──────────────┴──────────────┴──────────────┴─────────────────────────┘
│ Production Trend (Last 30 Days)                                     │
│ [Line Chart: Milk Production]                                       │
├──────────────────────────────┬───────────────────────────────────────┤
│ Recent Health Alerts         │ Task Assignment                       │
│ • Cow #127 - High Temp (6h)  │ ☐ Vaccinate Group A (Due Today)      │
│ • Cow #089 - Lameness (2d)   │ ☐ Clean Barn 3 (Mizan - Pending)     │
│ • Calf #301 - Low Milk (1d)  │ ✓ Morning Milk Collection (Done)     │
└──────────────────────────────┴───────────────────────────────────────┘
```

**KPI Card Specifications:**

```html
<!-- OWL Component Template -->
<div class="kpi-card" t-att-class="props.alertClass">
  <div class="kpi-icon">
    <img t-att-src="props.iconUrl" alt=""/>
  </div>
  <div class="kpi-content">
    <h3 class="kpi-value"><t t-esc="props.value"/></h3>
    <p class="kpi-label"><t t-esc="props.label"/></p>
    <div class="kpi-change" t-att-class="props.changeClass">
      <span t-if="props.changeDirection === 'up'">↑</span>
      <span t-if="props.changeDirection === 'down'">↓</span>
      <t t-esc="props.changeText"/>
    </div>
  </div>
</div>
```

**CSS Styling:**

```scss
.kpi-card {
  background: #FFFFFF;
  border: 1px solid #E0E0E0;
  border-radius: 8px;
  padding: 24px;
  transition: box-shadow 200ms ease-out;

  &:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  &.alert {
    border-left: 4px solid #FF9800; // Warning orange
  }

  &.critical {
    border-left: 4px solid #F44336; // Error red
  }

  .kpi-value {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 700;
    color: #2E7D32; // Primary green
    margin: 0;
  }

  .kpi-label {
    font-family: 'Open Sans', sans-serif;
    font-size: 14px;
    color: #616161; // Text secondary
    margin: 8px 0 0;
  }

  .kpi-change {
    font-size: 12px;
    margin-top: 8px;

    &.positive {
      color: #4CAF50; // Success green
    }

    &.negative {
      color: #F44336; // Error red
    }
  }
}

// Bengali typography overrides
[lang="bn"] {
  .kpi-value {
    font-family: 'Noto Serif Bengali', serif;
    font-size: 34px; // +2px for Bengali
  }

  .kpi-label {
    font-family: 'Noto Sans Bengali', sans-serif;
    font-size: 16px; // +2px for Bengali
    line-height: 28px; // 1.8x line height
  }
}
```

**Wireframe Annotations:**

- **Grid:** 4-column layout (Desktop XL: 1920px)
- **Card Width:** 450px each (with 24px gutters)
- **Card Height:** 180px (fixed for alignment)
- **Icon Size:** 48x48px (dairy-specific icons from A-015)
- **Responsive:** Collapse to 2-column on MD (768px), 1-column on SM (576px)

### 4.2 Farm Worker Mobile View

**Primary User:** Mizan Rahman (Farm Worker, 28yo, limited computer experience)

**Design Philosophy:**
- **Icon-First**: Every action represented by a large, recognizable icon
- **One Task Per Screen**: No complex multi-step workflows on single page
- **Bengali Primary**: English text secondary or hidden
- **Voice Input**: Microphone button on all numeric/text inputs
- **Large Touch Targets**: Minimum 60x60px (vs WCAG 44x44px)

**Home Screen (Tablet: 768x1024)**

```
┌──────────────────────────────────────────────────────┐
│ স্মার্ট ডেয়ারি (Smart Dairy)              [Mizan] ☰ │
├──────────────────────────────────────────────────────┤
│                                                      │
│  আজকের কাজ (Today's Tasks)                          │
│                                                      │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  │
│  │   🥛        │  │   📝        │  │   💉        │  │
│  │             │  │             │  │             │  │
│  │  দুধ লিখুন  │  │ স্বাস্থ্য    │  │  টিকা দিন   │  │
│  │             │  │             │  │             │  │
│  └─────────────┘  └─────────────┘  └─────────────┘  │
│                                                      │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  │
│  │   🐄        │  │   📊        │  │   🔔        │  │
│  │             │  │             │  │             │  │
│  │ গরু দেখুন   │  │  রিপোর্ট    │  │  সতর্কতা    │  │
│  │             │  │             │  │   (3)       │  │
│  └─────────────┘  └─────────────┘  └─────────────┘  │
│                                                      │
└──────────────────────────────────────────────────────┘
```

**Action Button Specifications:**

```html
<!-- Simplified Farm Worker Button Template -->
<button class="farm-worker-action" t-on-click="props.action">
  <div class="action-icon">
    <img t-att-src="props.iconUrl" alt=""/>
  </div>
  <div class="action-label">
    <h3 t-esc="props.labelBengali"/>
    <p t-esc="props.labelEnglish" class="label-english"/>
  </div>
  <div class="action-badge" t-if="props.badgeCount > 0">
    <span t-esc="props.badgeCount"/>
  </div>
</button>
```

```scss
.farm-worker-action {
  width: 220px;
  height: 200px;
  background: #FFFFFF;
  border: 2px solid #E0E0E0;
  border-radius: 12px;
  padding: 24px;
  margin: 12px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 200ms ease-out;

  // Large touch target for outdoor use
  min-width: 60px;
  min-height: 60px;

  &:hover {
    background: #F5F5F5;
    border-color: #2E7D32;
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
  }

  &:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }

  .action-icon {
    width: 80px;
    height: 80px;
    margin-bottom: 16px;

    img {
      width: 100%;
      height: 100%;
    }
  }

  .action-label {
    text-align: center;

    h3 {
      font-family: 'Noto Sans Bengali', sans-serif;
      font-size: 20px;
      font-weight: 600;
      color: #212121;
      margin: 0 0 4px;
    }

    .label-english {
      font-family: 'Open Sans', sans-serif;
      font-size: 12px;
      color: #9E9E9E;
      margin: 0;
    }
  }

  .action-badge {
    position: absolute;
    top: 16px;
    right: 16px;
    background: #F44336; // Error red for alerts
    color: #FFFFFF;
    width: 32px;
    height: 32px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
  }
}
```

**Milk Production Entry Screen (Simplified)**

```
┌──────────────────────────────────────────────────────┐
│ ← দুধ উৎপাদন লিখুন (Record Milk)                     │
├──────────────────────────────────────────────────────┤
│                                                      │
│  গরু নম্বর (Cow Number)                              │
│  ┌────────────────────────────────────────────────┐  │
│  │  #127                        [RFID Scan]  📷  │  │
│  └────────────────────────────────────────────────┘  │
│                                                      │
│  দুধের পরিমাণ (Milk Quantity - Liters)               │
│  ┌────────────────────────────────────────────────┐  │
│  │  5.5                            [Voice]    🎤 │  │
│  └────────────────────────────────────────────────┘  │
│                                                      │
│  সময় (Time)                                         │
│  ┌────────────┐        ┌────────────┐               │
│  │ ⏰ সকাল    │        │ ⏰ সন্ধ্যা   │               │
│  │  (Selected) │        │            │               │
│  └────────────┘        └────────────┘               │
│                                                      │
│  মান (Quality)                                       │
│  ┌────────┐  ┌────────┐  ┌────────┐                │
│  │ ✅ ভালো │  │  সাধারণ │  │  খারাপ  │                │
│  └────────┘  └────────┘  └────────┘                │
│                                                      │
│  ┌──────────────────────────────────────────────┐   │
│  │  সংরক্ষণ করুন (Save)                        │   │
│  └──────────────────────────────────────────────┘   │
│                                                      │
└──────────────────────────────────────────────────────┘
```

**Voice Input Integration:**

```javascript
// OWL Component with Voice Input
import { Component, useState } from "@odoo/owl";

class MilkProductionEntry extends Component {
  setup() {
    this.state = useState({
      quantity: '',
      isRecording: false,
    });
  }

  startVoiceInput() {
    if (!('webkitSpeechRecognition' in window)) {
      alert('দুঃখিত, ভয়েস ইনপুট সমর্থিত নয়'); // Sorry, voice input not supported
      return;
    }

    const recognition = new webkitSpeechRecognition();
    recognition.lang = 'bn-BD'; // Bengali (Bangladesh)
    recognition.continuous = false;
    recognition.interimResults = false;

    recognition.onstart = () => {
      this.state.isRecording = true;
    };

    recognition.onresult = (event) => {
      const transcript = event.results[0][0].transcript;
      const quantity = this.extractNumberFromBengali(transcript);
      this.state.quantity = quantity;
      this.playConfirmationAudio(`রেকর্ড করা হয়েছে ${quantity} লিটার`); // "Recorded [X] liters"
    };

    recognition.onerror = (event) => {
      console.error('Voice input error:', event.error);
      this.state.isRecording = false;
    };

    recognition.onend = () => {
      this.state.isRecording = false;
    };

    recognition.start();
  }

  extractNumberFromBengali(text) {
    // Convert Bengali numbers to English digits
    const bengaliToEnglish = {
      '০': '0', '১': '1', '২': '2', '৩': '3', '৪': '4',
      '৫': '5', '৬': '6', '৭': '7', '৮': '8', '৯': '9'
    };

    // Also handle spoken words: "পাঁচ" → 5, "দশ" → 10, etc.
    const wordToNumber = {
      'এক': 1, 'দুই': 2, 'তিন': 3, 'চার': 4, 'পাঁচ': 5,
      'ছয়': 6, 'সাত': 7, 'আট': 8, 'নয়': 9, 'দশ': 10
    };

    // Implementation details...
    return parsedNumber;
  }

  playConfirmationAudio(text) {
    const synth = window.speechSynthesis;
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.lang = 'bn-BD';
    utterance.rate = 0.9; // Slightly slower for clarity
    synth.speak(utterance);
  }
}
```

### 4.3 Warehouse Staff Interface

**Primary User:** Abdul Rahman (Warehouse Staff, 30yo, basic computer skills)

**Key Requirements:**
- Simplified inventory views (hide accounting fields)
- Barcode scanning integration (via USB scanner)
- Stock alert notifications (low feed, expiring products)
- Easy requisition creation (drag-and-drop from alerts)

**Inventory Dashboard (Desktop: 1366x768)**

```
┌─────────────────────────────────────────────────────────────┐
│ Inventory Management                      [Abdul Rahman] ☰  │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ Stock Alerts (5)                       [Create Requisition] │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ ⚠️ Feed Type A - 7 days left (150 kg)        [Reorder] │ │
│ │ ⚠️ Medicine X - Low stock (20 units)          [Reorder] │ │
│ │ 🔴 Vaccine Y - Expiring in 3 days             [Action]  │ │
│ └─────────────────────────────────────────────────────────┘ │
│                                                             │
│ Quick Actions                                               │
│ ┌──────────────┐  ┌──────────────┐  ┌──────────────┐       │
│ │ 📦 Receive    │  │ 📤 Issue     │  │ 🔍 Stock     │       │
│ │   Goods       │  │   Goods      │  │   Check      │       │
│ └──────────────┘  └──────────────┘  └──────────────┘       │
│                                                             │
│ Recent Movements                                            │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ Feed A    +500kg   Received   2026-02-01  10:30 AM      │ │
│ │ Milk Cans -50 pcs  Issued      2026-02-01  09:15 AM      │ │
│ └─────────────────────────────────────────────────────────┘ │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

**Stock Take Form (Simplified)**

- **Hide fields:** Cost, valuation, accounting entries
- **Show fields:** Product, location, quantity on hand, theoretical quantity, counted quantity, difference
- **Barcode scanner** integration (auto-fill product on scan)
- **Mobile-responsive** for tablet stocktaking

**QWeb View Inheritance Example:**

```xml
<!-- views/inventory_simplified.xml -->
<odoo>
  <record id="stock_quant_view_simplified_tree" model="ir.ui.view">
    <field name="name">stock.quant.simplified.tree</field>
    <field name="model">stock.quant</field>
    <field name="inherit_id" ref="stock.stock_quant_view_tree"/>
    <field name="arch" type="xml">
      <!-- Hide accounting-related columns for warehouse staff -->
      <xpath expr="//field[@name='value']" position="attributes">
        <attribute name="column_invisible">1</attribute>
      </xpath>
      <xpath expr="//field[@name='cost']" position="attributes">
        <attribute name="column_invisible">1</attribute>
      </xpath>

      <!-- Add barcode scan trigger button -->
      <xpath expr="//field[@name='product_id']" position="before">
        <button name="scan_barcode" type="object" string="📷 Scan"
                class="btn-scan-barcode"/>
      </xpath>

      <!-- Highlight low stock rows -->
      <xpath expr="//tree" position="attributes">
        <attribute name="decoration-warning">quantity &lt; min_quantity</attribute>
        <attribute name="decoration-danger">quantity == 0</attribute>
      </xpath>
    </field>
  </record>
</odoo>
```

### 4.4 Accountant/Finance View

**Primary User:** Farhana Rahman (Finance Manager, 32yo, MBA, high digital literacy)

**Design Approach:**
- **Minimal customization** (professional users expect standard accounting interface)
- **Enhanced reporting** (custom VAT reports for Bangladesh compliance)
- **Dashboard widgets** (AP/AR aging, cash flow forecast)
- **Multi-currency support** (BDT primary, USD for exports)

**Customizations:**

1. **VAT Compliance Dashboard** (Bangladesh-specific)
   - 15% VAT calculation on dairy products
   - Monthly VAT return preparation
   - Export to government e-filing format

2. **Payment Gateway Reconciliation**
   - Auto-match bKash/Nagad/Rocket transactions
   - Bank statement import (CSV format from local banks)

3. **B2B Credit Management**
   - Customer credit limit warnings (before order confirmation)
   - Aging analysis with color coding (30/60/90 days overdue)

**No Bengali translation needed** (finance team English-proficient)

### 4.5 Admin Configuration Panel

**Primary User:** IT Manager (System Administrator)

**Enhanced Settings Interface:**

```
┌─────────────────────────────────────────────────────────────┐
│ Smart Dairy System Configuration              [Admin] ⚙️    │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ Quick Config                                                │
│ ┌──────────────┐  ┌──────────────┐  ┌──────────────┐       │
│ │ 👥 Users &    │  │ 🏢 Company   │  │ 📊 Analytics │       │
│ │   Roles       │  │   Settings   │  │   Setup      │       │
│ └──────────────┘  └──────────────┘  └──────────────┘       │
│                                                             │
│ System Health                                               │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ Database Size: 2.3 GB / 50 GB              [OK]  ✅     │ │
│ │ Active Users:  47 / 1000                   [OK]  ✅     │ │
│ │ API Response:  320ms avg                   [OK]  ✅     │ │
│ │ Backup Status: Last backup 2h ago          [OK]  ✅     │ │
│ └─────────────────────────────────────────────────────────┘ │
│                                                             │
│ Recent Admin Actions                                        │
│ • User 'Mizan Rahman' role changed to 'Farm Worker'        │
│ • Farm Management module updated to v2.1.3                  │
│ • Database backup completed (2.1 GB)                        │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

**Role-Based Access Control UI:**

- Visual role matrix (users × permissions grid)
- Pre-configured role templates: "Farm Worker", "Farm Manager", "Accountant", "Sales Rep", "Admin"
- Bulk user import from CSV (for 50+ farm workers)

---

## 5. FORM REDESIGN SPECIFICATIONS

### 5.1 Animal Profile Forms

**Current Odoo Form Issues:**
- 30+ fields displayed simultaneously (overwhelming)
- No visual hierarchy (all fields same weight)
- No photo prominence (animal identification difficult)
- English-only labels (farm workers need Bengali)

**Redesigned Animal Profile Form**

**Layout Strategy: Progressive Disclosure**
- **Tab 1: Basic Info** (always visible)
- **Tab 2: Health Records** (expandable)
- **Tab 3: Production History** (expandable)
- **Tab 4: Breeding Info** (expandable)

**Tab 1: Basic Information (Form View)**

```
┌────────────────────────────────────────────────────────────────┐
│ Animal Profile: Cow #127                            [Edit] [×] │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│ ┌──────────────┐  ┌──────────────────────────────────────────┐│
│ │              ││  │ Ear Tag: #127                         ││ ││
│ │   [Photo]    ││  │ Name: লক্ষী (Lakshmi)                  ││ ││
│ │              ││  │                                          ││
│ │  300x300px   ││  │ Breed: Holstein Friesian                ││
│ │              ││  │ Gender: Female (গাভী)                   ││
│ │  [Upload]    ││  │ Birth Date: 2022-03-15 (Age: 3y 10m)    ││
│ │              ││  │                                          ││
│ └──────────────┘  │ Health Status: ✅ Healthy (সুস্থ)        ││
│                   │ Lactation: Yes (Currently milking)       ││
│                   │                                          ││
│                   │ Current Weight: 550 kg                   ││
│                   │ Last Weighed: 2026-01-15                 ││
│                   └──────────────────────────────────────────┘│
│                                                                │
│ [📊 Health]  [🥛 Production]  [❤️ Breeding]  [📝 Notes]       │
│                                                                │
│ Quick Actions:                                                 │
│ ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│ │ 💉 Vaccinate  │  │ 🩺 Health Log │  │ 🥛 Record    │         │
│ │              │  │              │  │   Milk       │         │
│ └──────────────┘  └──────────────┘  └──────────────┘         │
│                                                                │
└────────────────────────────────────────────────────────────────┘
```

**XML View Definition:**

```xml
<record id="farm_animal_form_view_customized" model="ir.ui.view">
  <field name="name">farm.animal.form.customized</field>
  <field name="model">farm.animal</field>
  <field name="arch" type="xml">
    <form string="Animal Profile">
      <header>
        <field name="health_status" widget="statusbar"
               statusbar_colors='{"healthy":"green","sick":"red","treatment":"orange"}'/>
      </header>
      <sheet>
        <div class="oe_title">
          <h1>
            <field name="name" placeholder="Animal Name (Bengali/English)"/>
          </h1>
          <h3>
            <field name="ear_tag" placeholder="Ear Tag Number"/>
          </h3>
        </div>

        <group>
          <group>
            <!-- Left column: Photo -->
            <field name="image_1920" widget="image" class="oe_avatar"
                   options='{"preview_image": "image_128", "size": [300, 300]}'/>
            <button name="capture_photo" type="object" string="📷 Take Photo"
                    class="btn-capture-photo"/>
          </group>

          <group>
            <!-- Right column: Basic details -->
            <field name="breed_id"/>
            <field name="gender" widget="selection"/>
            <field name="birth_date"/>
            <field name="age" readonly="1"/>
            <field name="health_status_display" readonly="1"/>
            <field name="is_lactating" readonly="1"/>
            <field name="current_weight"/>
            <field name="last_weighed_date" readonly="1"/>
          </group>
        </group>

        <notebook>
          <page string="📊 Health Records" name="health">
            <field name="health_event_ids">
              <tree decoration-danger="event_type=='illness'">
                <field name="event_date"/>
                <field name="event_type"/>
                <field name="description"/>
                <field name="vet_name"/>
              </tree>
            </field>
          </page>

          <page string="🥛 Production History" name="production">
            <field name="milk_production_ids">
              <graph type="line">
                <field name="production_date"/>
                <field name="quantity_liters" type="measure"/>
              </graph>
            </field>
          </page>

          <page string="❤️ Breeding Information" name="breeding">
            <group>
              <field name="breeding_status"/>
              <field name="last_heat_date"/>
              <field name="last_insemination_date"/>
              <field name="expected_calving_date"/>
              <field name="total_calvings" readonly="1"/>
            </group>
          </page>

          <page string="📝 Notes" name="notes">
            <field name="notes" placeholder="Additional notes about this animal..."/>
          </page>
        </notebook>

        <!-- Quick action buttons -->
        <div class="oe_chatter">
          <field name="message_follower_ids"/>
          <field name="activity_ids"/>
          <field name="message_ids"/>
        </div>
      </sheet>
    </form>
  </field>
</record>
```

**Field-Level Customizations:**

| Field | Widget | Bengali Label | Customization |
|-------|--------|---------------|---------------|
| `image_1920` | `image` | ছবি (Photo) | Camera capture button for mobile |
| `ear_tag` | `char` | কানের ট্যাগ নম্বর | RFID scan integration |
| `name` | `char` | নাম | Voice input for Bengali names |
| `breed_id` | `many2one` | জাত (Breed) | Icon selector with cow images |
| `gender` | `selection` | লিঙ্গ | Icons: 🐄 গাভী (Female), 🐂 ষাঁড় (Bull) |
| `birth_date` | `date` | জন্ম তারিখ | Automatic age calculation |
| `health_status` | `statusbar` | স্বাস্থ্য অবস্থা | Color-coded: Green/Yellow/Red |
| `current_weight` | `float` | ওজন (কেজি) | Unit suffix "kg" auto-added |

### 5.2 Milk Production Entry

**Optimized for Speed** (farm workers enter 100+ records daily)

**List View (Batch Entry Mode)**

```
┌────────────────────────────────────────────────────────────────┐
│ Milk Production - Morning (২০২৬-০২-০১)          [Save All] │
├────────────────────────────────────────────────────────────────┤
│ Cow #  | Name      | Quantity (L) | Quality  | Notes         │
├────────┼───────────┼──────────────┼──────────┼───────────────┤
│ #101   │ মালা       │ [6.5] 🎤     │ ✅ Good  │               │
│ #102   │ কমলা       │ [7.2] 🎤     │ ✅ Good  │               │
│ #103   │ লক্ষী      │ [0.0] 🎤     │ ⚠️ Sick  │ Not milking   │
│ #104   │ পদ্মা      │ [8.1] 🎤     │ ✅ Good  │               │
│ #105   │ গঙ্গা      │ [5.8] 🎤     │ ⚠️ Avg   │               │
│ [Add Row]                                                      │
└────────────────────────────────────────────────────────────────┘
Total: 27.6 L (5 cows milked, 1 skipped)
```

**Features:**
- **Inline editing** (no need to open separate forms)
- **Voice input** on quantity field (microphone icon)
- **Auto-save** after 3 seconds of inactivity
- **Keyboard navigation** (Tab to next field, Enter to save row)
- **Smart defaults** (morning/evening pre-selected based on time of day)
- **Quality dropdown** with icons (✅ Good / ⚠️ Average / ❌ Poor)

**Tree View XML:**

```xml
<record id="milk_production_tree_editable" model="ir.ui.view">
  <field name="name">milk.production.tree.editable</field>
  <field name="model">farm.milk.production</field>
  <field name="arch" type="xml">
    <tree string="Milk Production Entry" editable="bottom"
          decoration-warning="quality=='average'"
          decoration-danger="quality=='poor'">

      <field name="animal_id" options='{"no_create": true, "no_open": true}'/>
      <field name="animal_name" readonly="1"/>
      <field name="quantity_liters" sum="Total Liters"
             widget="float" decoration-bf="1"/>
      <button name="voice_input_quantity" type="object" string="🎤"
              class="btn-voice-input"/>
      <field name="quality" widget="selection"
             options='{"icons": {"good": "✅", "average": "⚠️", "poor": "❌"}}'/>
      <field name="notes" placeholder="Notes (optional)"/>

      <!-- Auto-save after 3s of inactivity -->
      <field name="auto_save_timer" invisible="1"/>
    </tree>
  </field>
</record>
```

**JavaScript Auto-Save Logic:**

```javascript
odoo.define('smart_dairy.milk_production_autosave', function(require) {
  'use strict';

  const ListController = require('web.ListController');
  const core = require('web.core');

  ListController.include({
    custom_events: _.extend({}, ListController.prototype.custom_events, {
      field_changed: '_onFieldChanged',
    }),

    _onFieldChanged: function(event) {
      this._super.apply(this, arguments);

      // Clear existing timeout
      clearTimeout(this.saveTimeout);

      // Set new timeout for auto-save (3 seconds)
      this.saveTimeout = setTimeout(() => {
        this.saveRecord(event.data.dataPointID);
      }, 3000);
    },
  });
});
```

### 5.3 Sales Order Forms

**B2C Order (Individual Customer)**

```
┌────────────────────────────────────────────────────────────────┐
│ Sales Order #SO-2026-0001                        [Confirm] [×] │
├────────────────────────────────────────────────────────────────┤
│ Customer: Sarah Rahman (+880 1712-345678)                      │
│ Delivery Address: House 12, Road 5, Dhanmondi, Dhaka          │
│ Delivery Date: 2026-02-02  Time Slot: 7:00 AM - 9:00 AM      │
│                                                                │
│ Products:                                                      │
│ ┌────────────────────────────────────────────────────────────┐│
│ │ Product          | Qty | Unit Price | Subtotal           ││ │
│ ├──────────────────┼─────┼────────────┼────────────────────┤│
│ │ Fresh Milk 1L    │  2  │  ৳80       │  ৳160              ││
│ │ Yogurt 500g      │  3  │  ৳120      │  ৳360              ││
│ │ Cheese 250g      │  1  │  ৳350      │  ৳350              ││
│ │                                      │                    ││
│ │                               Subtotal:  ৳870              ││
│ │                          Delivery Fee:  ৳50               ││
│ │                                   Total:  ৳920             ││
│ └────────────────────────────────────────────────────────────┘│
│                                                                │
│ Payment Method: bKash (+880 1712-345678)          [Collected] │
│ Order Source: Mobile App                                       │
│ Subscription: No                                               │
│                                                                │
└────────────────────────────────────────────────────────────────┘
```

**B2B Order (Business Customer)**

```
┌────────────────────────────────────────────────────────────────┐
│ Sales Order #SO-2026-0042 (B2B)                  [Confirm] [×] │
├────────────────────────────────────────────────────────────────┤
│ Customer: Rahman Traders Ltd. (Retailer - Tier 2)              │
│ Contact: Kamal Hossain (+880 1812-567890)                      │
│ Delivery Address: Shop 45, Karwan Bazar, Dhaka                 │
│ Delivery Date: 2026-02-03  (Next Day Delivery)                │
│                                                                │
│ Products:                                                      │
│ ┌────────────────────────────────────────────────────────────┐│
│ │ Product          | Qty  | Unit Price | Discount | Subtotal││ │
│ ├──────────────────┼──────┼────────────┼──────────┼─────────┤│
│ │ Fresh Milk 1L    │ 100  │  ৳75       │  5%      │  ৳7,125 ││
│ │ Yogurt 500g      │  50  │  ৳110      │  5%      │  ৳5,225 ││
│ │ Cheese 250g      │  20  │  ৳330      │  5%      │  ৳6,270 ││
│ │                                                             ││
│ │                                        Subtotal:  ৳18,620   ││
│ │                                      VAT (15%):  ৳2,793     ││
│ │                                    Discount (5%):  -৳931    ││
│ │                                          Total:  ৳20,482    ││
│ └────────────────────────────────────────────────────────────┘│
│                                                                │
│ Payment Terms: Net 30 (Credit)                                 │
│ Credit Limit: ৳50,000   Used: ৳25,000   Available: ৳25,000    │
│ Order Source: Field Sales (Farhana Rahman)                     │
│                                                                │
└────────────────────────────────────────────────────────────────┘
```

**Key Differences:**

| Feature | B2C | B2B |
|---------|-----|-----|
| **Pricing** | Standard retail price | Tiered pricing (5-15% discount) |
| **Payment** | Cash/bKash/Nagad/Card (immediate) | Credit terms (Net 15/30/45) |
| **Quantities** | 1-10 units | 50-500 units (bulk) |
| **VAT** | Included in price | Separate line item (15%) |
| **Delivery Slot** | 2-hour window (7-9 AM, 4-6 PM) | Date only (flexible timing) |
| **Order Source** | Website, Mobile App, Phone | Field Sales, B2B Portal |

### 5.4 Purchase Orders

**Feed Purchase Order (for Farm)**

```
┌────────────────────────────────────────────────────────────────┐
│ Purchase Order #PO-2026-0018                     [Confirm] [×] │
├────────────────────────────────────────────────────────────────┤
│ Vendor: Green Valley Feed Suppliers                            │
│ Contact: Abdul Karim (+880 1912-789012)                        │
│ Expected Delivery: 2026-02-05                                  │
│ Payment Terms: Cash on Delivery                                │
│                                                                │
│ Items:                                                         │
│ ┌────────────────────────────────────────────────────────────┐│
│ │ Product              | Qty    | Unit Price | Subtotal     ││ │
│ ├──────────────────────┼────────┼────────────┼──────────────┤│
│ │ Cattle Feed Type A   │ 1000kg │  ৳45/kg    │  ৳45,000     ││
│ │ Mineral Supplement   │  100kg │  ৳120/kg   │  ৳12,000     ││
│ │ Salt Licks           │   50   │  ৳80/pc    │  ৳4,000      ││
│ │                                              │              ││
│ │                                       Subtotal:  ৳61,000    ││
│ │                                     VAT (15%):  ৳9,150      ││
│ │                                         Total:  ৳70,150     ││
│ └────────────────────────────────────────────────────────────┘│
│                                                                │
│ Delivery Location: Main Warehouse (Savar)                      │
│ Requisition By: Farm Manager (Kamal Hossain)                   │
│ Budget Code: FEED-2026-Q1                                      │
│                                                                │
└────────────────────────────────────────────────────────────────┘
```

**Purchase Approval Workflow:**

```
[Draft] → [Manager Approval] → [Finance Approval] → [Confirmed] → [Received]
```

- Farm Manager can create requisitions (Draft status)
- Farm Supervisor approves requisitions < ৳50,000
- Finance Manager approves requisitions > ৳50,000
- Warehouse receives goods and updates inventory

**Statusbar Widget:**

```xml
<field name="state" widget="statusbar"
       statusbar_visible="draft,manager_approval,finance_approval,confirmed,received"
       statusbar_colors='{"draft":"gray","manager_approval":"orange","finance_approval":"orange","confirmed":"blue","received":"green"}'/>
```

### 5.5 Inventory Adjustments

**Stock Take Form (Warehouse)**

```
┌────────────────────────────────────────────────────────────────┐
│ Stock Adjustment #ADJ-2026-0003                  [Validate] [×]│
├────────────────────────────────────────────────────────────────┤
│ Location: Main Warehouse - Feed Storage                        │
│ Adjustment Date: 2026-02-01                                    │
│ Responsible: Abdul Rahman (Warehouse Staff)                     │
│                                                                │
│ ┌────────────────────────────────────────────────────────────┐│
│ │ Product          | System Qty | Counted | Diff | Reason   ││ │
│ ├──────────────────┼────────────┼─────────┼──────┼──────────┤│
│ │ Feed Type A      │  800 kg    │ 785 kg  │ -15  │ Spillage ││
│ │ Feed Type B      │  450 kg    │ 450 kg  │   0  │ OK       ││
│ │ Mineral Supp.    │   80 kg    │  82 kg  │  +2  │ Recount  ││
│ │ Salt Licks       │   30 pcs   │  28 pcs │  -2  │ Broken   ││
│ └────────────────────────────────────────────────────────────┘│
│                                                                │
│ Total Variance: -15 items (-0.9% of total stock)              │
│ Financial Impact: -৳675 (negligible)                           │
│                                                                │
│ Approval Required: No (variance < 2%)                          │
│                                                                │
└────────────────────────────────────────────────────────────────┘
```

**Variance Approval Rules:**
- **< 2% variance**: Auto-approve (warehouse staff can validate)
- **2-5% variance**: Manager approval required
- **> 5% variance**: Finance + Manager approval + investigation report

**Color-Coded Rows:**

```xml
<tree decoration-success="difference == 0"
      decoration-warning="abs(difference) &lt; system_qty * 0.02"
      decoration-danger="abs(difference) &gt;= system_qty * 0.05">
```

- ✅ **Green**: No difference (perfect match)
- ⚠️ **Orange**: Minor variance (< 2%, auto-approve)
- 🔴 **Red**: Significant variance (> 5%, investigation needed)

---

*[Document continues with remaining sections 6-14...]*

**Note:** This document is Part 1 of 2. Due to length, sections 6-14 will be in the next continuation.

---

**DOCUMENT METADATA**
- Current Page Count: 32 pages
- Target Page Count: 60+ pages
- Completion Status: 50% (Sections 1-5 complete)
- Remaining Sections: 6 (List/Tree Views), 7 (Dashboards), 8 (Navigation), 9 (Bengali Localization), 10 (Mobile), 11 (OWL Implementation), 12 (Implementation Guidelines), 13 (QA), 14 (Appendices)

---

**END OF PART 1**
