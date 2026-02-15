# SMART DAIRY LTD.
## ACCESSIBILITY COMPLIANCE GUIDE (WCAG 2.2)
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | A-012 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Accessibility Specialist |
| **Owner** | UI/UX Director |
| **Reviewer** | QA Lead |
| **Status** | Final |
| **Related Documents** | A-001 (Design System), A-008-A-010 (Mobile Specs), F-005 (GDPR), SRS |
| **Compliance Target** | WCAG 2.2 Level AA (Minimum), Level AAA (Where Feasible) |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Accessibility Standards Overview](#2-accessibility-standards-overview)
3. [Perceivable Guidelines (Principle 1)](#3-perceivable-guidelines-principle-1)
4. [Operable Guidelines (Principle 2)](#4-operable-guidelines-principle-2)
5. [Understandable Guidelines (Principle 3)](#5-understandable-guidelines-principle-3)
6. [Robust Guidelines (Principle 4)](#6-robust-guidelines-principle-4)
7. [Platform-Specific Implementation](#7-platform-specific-implementation)
8. [Testing Procedures](#8-testing-procedures)
9. [Accessibility Statement Template](#9-accessibility-statement-template)
10. [Appendices](#10-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This guide establishes the accessibility requirements and implementation guidelines for all Smart Dairy digital products. It ensures compliance with WCAG 2.2 standards and Bangladesh's commitment to digital inclusion, making the platform usable by people with diverse abilities including visual, auditory, motor, and cognitive impairments.

### 1.2 Accessibility Vision

```
┌─────────────────────────────────────────────────────────────────┐
│                 ACCESSIBILITY COMMITMENT                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  "Smart Dairy is committed to ensuring digital accessibility    │
│   for people with disabilities. We are continually improving    │
│   the user experience for everyone and applying relevant        │
│   accessibility standards."                                     │
│                                                                  │
│  TARGET COMPLIANCE:                                             │
│  ├── WCAG 2.2 Level AA (Required for all features)             │
│  ├── WCAG 2.2 Level AAA (Where technically feasible)           │
│  ├── Bangladesh Disability Rights Act 2013 alignment            │
│  └── EN 301 549 (European standard reference)                   │
│                                                                  │
│  COVERAGE SCOPE:                                                │
│  • Public Website & B2C Portal                                  │
│  • B2B Marketplace Portal                                       │
│  • Farm Management Portal                                       │
│  • Mobile Applications (iOS & Android)                          │
│  • ERP Backend Interfaces                                       │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 1.3 User Disabilities Addressed

| Disability Type | Considerations | Design Response |
|-----------------|----------------|-----------------|
| **Visual** | Blindness, low vision, color blindness | Screen readers, high contrast, scalable text |
| **Auditory** | Deafness, hearing loss | Captions, transcripts, visual alerts |
| **Motor** | Limited dexterity, tremors, paralysis | Large targets, keyboard navigation, voice control |
| **Cognitive** | Learning disabilities, memory issues | Simple language, consistent patterns, error prevention |
| **Speech** | Unable to speak | Visual alternatives to voice commands |
| **Seizure** | Photosensitive epilepsy | No flashing content, reduced motion |

---

## 2. ACCESSIBILITY STANDARDS OVERVIEW

### 2.1 WCAG 2.2 Structure

```
┌─────────────────────────────────────────────────────────────────┐
│                    WCAG 2.2 ORGANIZATION                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  4 PRINCIPLES (POUR)                                            │
│  ├── PERCEIVABLE - Information must be presentable in ways      │
│  │                users can perceive                            │
│  ├── OPERABLE - Interface components must be operable by all    │
│  ├── UNDERSTANDABLE - Information and UI operation must be      │
│  │                    understandable                            │
│  └── ROBUST - Content must work with current and future         │
│               assistive technologies                           │
│                                                                  │
│  CONFORMANCE LEVELS:                                            │
│  ├── Level A (Minimum) - Basic accessibility requirements       │
│  ├── Level AA (Standard) - Addresses major barriers             │
│  └── Level AAA (Enhanced) - Maximum accessibility               │
│                                                                  │
│  SMART DAIRY TARGET: Level AA for all features                 │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 New in WCAG 2.2

| Success Criterion | Level | Description | Implementation |
|-------------------|-------|-------------|----------------|
| 2.4.11 Focus Not Obscured (Minimum) | AA | Focused element not hidden | Scroll into view, z-index management |
| 2.4.12 Focus Not Obscured (Enhanced) | AAA | Fully visible when focused | Advanced scrolling |
| 2.4.13 Focus Appearance | AA | Clear focus indicators | 2px outline, 4:1 contrast |
| 2.5.7 Dragging Movements | AA | Alternatives to dragging | Click/tap alternatives |
| 2.5.8 Target Size (Minimum) | AA | 24×24 CSS px minimum | Button sizing |
| 3.2.6 Consistent Help | A | Help in consistent location | Fixed help button |
| 3.3.7 Redundant Entry | A | Auto-fill known data | Form persistence |
| 3.3.8 Accessible Authentication (Minimum) | AA | No cognitive tests | Biometric, email link |
| 3.3.9 Accessible Authentication (Enhanced) | AAA | No user effort | Alternative methods |

---

## 3. PERCEIVABLE GUIDELINES (PRINCIPLE 1)

### 3.1 Text Alternatives (1.1)

#### 1.1.1 Non-text Content - Level A

```html
<!-- Good: Descriptive alt text -->
<img src="organic-milk.jpg" 
     alt="Saffron Organic Milk 1 liter bottle with green cap">

<!-- Good: Decorative image with empty alt -->
<img src="decorative-border.png" alt="">

<!-- Good: Complex image with detailed description -->
<figure>
  <img src="milk-production-chart.png" 
       alt="Bar chart showing daily milk production for January 2024">
  <figcaption>
    Daily milk production averaged 900 liters with peak on January 15 
    at 1,050 liters. Full data table follows this chart.
  </figcaption>
</figure>

<!-- Bad: Missing alt text -->
<img src="product.jpg"> <!-- FAIL -->

<!-- Bad: Non-descriptive alt text -->
<img src="product.jpg" alt="image"> <!-- FAIL -->
```

### 3.2 Time-based Media (1.2)

#### 1.2.2 Captions (Prerecorded) - Level A

```html
<!-- Video with captions -->
<video controls>
  <source src="milking-process.mp4" type="video/mp4">
  <track kind="captions" 
         src="milking-process-bn.vtt" 
         srclang="bn" 
         label="Bengali">
  <track kind="captions" 
         src="milking-process-en.vtt" 
         srclang="en" 
         label="English">
  Your browser does not support the video tag.
</video>
```

### 3.3 Adaptable (1.3)

#### 1.3.1 Info and Relationships - Level A

```html
<!-- Good: Semantic HTML structure -->
<table>
  <caption>Monthly Milk Production Summary</caption>
  <thead>
    <tr>
      <th scope="col">Month</th>
      <th scope="col">Production (Liters)</th>
      <th scope="col">Target</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">January</th>
      <td>27,000</td>
      <td>25,000</td>
    </tr>
  </tbody>
</table>

<!-- Good: Form with proper labels -->
<form>
  <label for="customer-name">Full Name</label>
  <input type="text" id="customer-name" name="name" 
         required aria-required="true">
  
  <fieldset>
    <legend>Preferred Delivery Time</legend>
    <input type="radio" id="morning" name="delivery" value="morning">
    <label for="morning">Morning (6-9 AM)</label>
    
    <input type="radio" id="evening" name="delivery" value="evening">
    <label for="evening">Evening (4-7 PM)</label>
  </fieldset>
</form>
```

### 3.4 Distinguishable (1.4)

#### 1.4.3 Contrast (Minimum) - Level AA

```css
/* Minimum contrast ratios:
   - Normal text: 4.5:1
   - Large text (18pt+ or 14pt+ bold): 3:1
   - UI components: 3:1
*/

/* Primary text on background */
.text-primary {
  color: #1a472a; /* Dark green */
  background-color: #ffffff;
  /* Ratio: 7.2:1 ✓ */
}

/* Secondary text */
.text-secondary {
  color: #666666;
  background-color: #ffffff;
  /* Ratio: 5.7:1 ✓ */
}

/* Disabled text (not required to meet contrast) */
.text-disabled {
  color: #999999;
  background-color: #ffffff;
  /* Ratio: 2.8:1 - acceptable for disabled */
}

/* Button with sufficient contrast */
.btn-primary {
  background-color: #2e7d32; /* Green 800 */
  color: #ffffff;
  border: 2px solid transparent;
  /* Ratio: 5.9:1 ✓ */
}

/* Focus indicator */
:focus-visible {
  outline: 2px solid #1976d2;
  outline-offset: 2px;
  /* Ratio against white: 4.6:1 ✓ */
}
```

#### 1.4.4 Resize Text - Level AA

```css
/* Support text resizing up to 200% without loss of content */
html {
  font-size: 100%; /* Base 16px */
}

/* Use relative units throughout */
.body-text {
  font-size: 1rem; /* 16px at default */
  line-height: 1.5;
}

.heading-1 {
  font-size: 2rem; /* 32px at default */
  line-height: 1.2;
}

/* Container must handle text expansion */
.card {
  min-height: auto; /* Allow expansion */
  overflow: visible; /* Don't clip enlarged text */
}

/* Mobile text size adjustment prevention */
body {
  -webkit-text-size-adjust: 100%;
  text-size-adjust: 100%;
}
```

#### 1.4.11 Non-text Contrast - Level AA

```css
/* UI components and graphical objects must have 3:1 contrast */

/* Form input borders */
input[type="text"] {
  border: 2px solid #757575; /* Gray with 4.6:1 contrast */
}

input[type="text"]:focus {
  border-color: #1976d2; /* Blue with 4.6:1 contrast */
}

/* Toggle switch */
.toggle {
  background-color: #9e9e9e; /* Gray - 2.9:1, meets minimum */
}

.toggle:checked {
  background-color: #4caf50; /* Green - 3.1:1, meets minimum */
}

/* Chart colors with sufficient contrast */
.chart-series-1 { fill: #1565c0; } /* Blue */
.chart-series-2 { fill: #c62828; } /* Red */
.chart-series-3 { fill: #2e7d32; } /* Green */
/* Each has 3:1+ contrast against white */
```

---

## 4. OPERABLE GUIDELINES (PRINCIPLE 2)

### 4.1 Keyboard Accessible (2.1)

#### 2.1.1 Keyboard - Level A

```jsx
// React component with keyboard accessibility
function DropdownMenu({ items, label }) {
  const [isOpen, setIsOpen] = useState(false);
  const [focusedIndex, setFocusedIndex] = useState(-1);
  const buttonRef = useRef(null);
  const itemRefs = useRef([]);

  const handleKeyDown = (e) => {
    switch(e.key) {
      case 'Enter':
      case 'Space':
        e.preventDefault();
        setIsOpen(!isOpen);
        break;
      case 'ArrowDown':
        e.preventDefault();
        if (!isOpen) {
          setIsOpen(true);
          setFocusedIndex(0);
        } else {
          setFocusedIndex((prev) => 
            Math.min(prev + 1, items.length - 1)
          );
        }
        break;
      case 'ArrowUp':
        e.preventDefault();
        setFocusedIndex((prev) => Math.max(prev - 1, 0));
        break;
      case 'Escape':
        setIsOpen(false);
        buttonRef.current?.focus();
        break;
      case 'Tab':
        if (isOpen) {
          setIsOpen(false);
        }
        break;
    }
  };

  return (
    <div className="dropdown">
      <button
        ref={buttonRef}
        aria-haspopup="listbox"
        aria-expanded={isOpen}
        onClick={() => setIsOpen(!isOpen)}
        onKeyDown={handleKeyDown}
      >
        {label}
      </button>
      {isOpen && (
        <ul role="listbox" tabIndex={-1}>
          {items.map((item, index) => (
            <li
              key={item.id}
              ref={(el) => itemRefs.current[index] = el}
              role="option"
              aria-selected={index === focusedIndex}
              tabIndex={index === focusedIndex ? 0 : -1}
            >
              {item.label}
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
```

### 4.2 Enough Time (2.2)

#### 2.2.1 Timing Adjustable - Level A

```jsx
// Session timeout with warning and extension option
function SessionTimeout() {
  const [timeRemaining, setTimeRemaining] = useState(1200); // 20 minutes
  const [showWarning, setShowWarning] = useState(false);

  useEffect(() => {
    const timer = setInterval(() => {
      setTimeRemaining((prev) => {
        if (prev <= 120 && !showWarning) { // 2 minutes warning
          setShowWarning(true);
        }
        return prev - 1;
      });
    }, 1000);

    return () => clearInterval(timer);
  }, []);

  const extendSession = () => {
    setTimeRemaining(1200);
    setShowWarning(false);
  };

  if (showWarning) {
    return (
      <div 
        role="alertdialog" 
        aria-labelledby="timeout-heading"
        aria-describedby="timeout-message"
      >
        <h2 id="timeout-heading">Session Timeout Warning</h2>
        <p id="timeout-message">
          Your session will expire in 2 minutes due to inactivity.
          Your data will be lost if you don't extend the session.
        </p>
        <button onClick={extendSession}>
          Extend Session (20 more minutes)
        </button>
        <button onClick={() => logout()}>
          Logout Now
        </button>
      </div>
    );
  }

  return null;
}
```

### 4.3 Navigable (2.4)

#### 2.4.4 Link Purpose (In Context) - Level A

```html
<!-- Good: Descriptive link text -->
<a href="/organic-milk">Buy Saffron Organic Milk 1 Liter</a>

<!-- Good: Link with aria-label for context -->
<a href="/product/123" aria-label="View details for Saffron Organic Milk">
  View Details
</a>

<!-- Good: Link with visually hidden text -->
<a href="/order/456">
  Track Order
  <span class="visually-hidden">number 456 for Bengal Traders</span>
</a>

<!-- Bad: Non-descriptive link text -->
<a href="/page">Click here</a> <!-- FAIL -->
<a href="/file.pdf">Download</a> <!-- FAIL -->
```

#### 2.4.7 Focus Visible - Level AA

```css
/* Focus indicator requirements:
   - Minimum 2px outline
   - 4.5:1 contrast ratio
   - Not obscured by other content
*/

/* Custom focus styles */
*:focus-visible {
  outline: 3px solid #1976d2;
  outline-offset: 2px;
  border-radius: 2px;
}

/* For dark backgrounds */
.dark-theme *:focus-visible {
  outline: 3px solid #64b5f6;
  outline-offset: 2px;
}

/* Skip link for keyboard users */
.skip-link {
  position: absolute;
  top: -40px;
  left: 0;
  background: #000;
  color: #fff;
  padding: 8px;
  text-decoration: none;
  z-index: 100;
}

.skip-link:focus {
  top: 0;
}
```

### 4.4 Input Modalities (2.5)

#### 2.5.5 Target Size (Enhanced) - Level AAA

```css
/* Recommended: 44×44 CSS pixels minimum for all targets */

/* Mobile-optimized buttons */
.btn-mobile {
  min-width: 44px;
  min-height: 44px;
  padding: 12px 24px;
}

/* Touch-friendly navigation */
.nav-item {
  min-height: 48px;
  display: flex;
  align-items: center;
  padding: 0 16px;
}

/* Form inputs */
input[type="checkbox"],
input[type="radio"] {
  width: 24px;
  height: 24px;
  margin-right: 12px;
}

/* Inline links in text - exception allowed */
p a {
  /* Can be smaller if in sentence */
  text-decoration: underline;
}
```

#### 2.5.8 Target Size (Minimum) - Level AA (WCAG 2.2)

```css
/* Minimum requirement: 24×24 CSS pixels */

/* Small button variant - minimum compliant */
.btn-small {
  min-width: 24px;
  min-height: 24px;
  padding: 4px 8px;
}

/* Spacing for adjacent targets */
.toolbar-button {
  margin: 2px; /* Ensures 24px spacing total */
}
```

---

## 5. UNDERSTANDABLE GUIDELINES (PRINCIPLE 3)

### 5.1 Readable (3.1)

#### 3.1.2 Language of Parts - Level AA

```html
<!-- Page language declared -->
<html lang="bn">

<!-- English phrases in Bengali content -->
<p>
  আমাদের পণ্য <span lang="en">Saffron Organic Milk</span> 
  এখন উপলব্ধ।
</p>

<!-- Bengali in English content -->
<html lang="en">
<p>
  Our farm is located in 
  <span lang="bn">রুপগঞ্জ, নারায়ণগঞ্জ</span>.
</p>
```

### 5.2 Predictable (3.2)

#### 3.2.4 Consistent Identification - Level AA

```jsx
// Consistent component naming and icons
// Always use the same icon + label combination

// Good: Consistent across all pages
function AddToCartButton({ onClick }) {
  return (
    <button 
      onClick={onClick}
      aria-label="Add to shopping cart"
      className="btn-add-cart"
    >
      <CartIcon aria-hidden="true" />
      <span>Add to Cart</span>
    </button>
  );
}

// Navigation remains consistent
function MainNavigation() {
  const navItems = [
    { label: 'Home', icon: HomeIcon, href: '/' },
    { label: 'Products', icon: ProductIcon, href: '/products' },
    { label: 'Orders', icon: OrderIcon, href: '/orders' },
    { label: 'Profile', icon: ProfileIcon, href: '/profile' },
  ];
  
  return (
    <nav aria-label="Main">
      {navItems.map(item => (
        <a key={item.href} href={item.href}>
          <item.icon aria-hidden="true" />
          {item.label}
        </a>
      ))}
    </nav>
  );
}
```

### 5.3 Input Assistance (3.3)

#### 3.3.1 Error Identification - Level A

```jsx
// Accessible form with error handling
function AccessibleForm() {
  const [errors, setErrors] = useState({});
  const [touched, setTouched] = useState({});

  const validate = (values) => {
    const errors = {};
    if (!values.email) {
      errors.email = 'Email address is required';
    } else if (!/\S+@\S+\.\S+/.test(values.email)) {
      errors.email = 'Please enter a valid email address';
    }
    return errors;
  };

  return (
    <form noValidate>
      <div className="form-group">
        <label htmlFor="email">
          Email Address
          <span aria-label="required">*</span>
        </label>
        <input
          type="email"
          id="email"
          name="email"
          aria-required="true"
          aria-invalid={errors.email ? 'true' : 'false'}
          aria-describedby={errors.email ? 'email-error' : undefined}
          onBlur={() => setTouched({ ...touched, email: true })}
        />
        {errors.email && touched.email && (
          <span id="email-error" className="error" role="alert">
            {errors.email}
          </span>
        )}
      </div>
      
      <button type="submit">Submit</button>
    </form>
  );
}
```

---

## 6. ROBUST GUIDELINES (PRINCIPLE 4)

### 6.1 Compatible (4.1)

#### 4.1.2 Name, Role, Value - Level A

```jsx
// Custom button with proper ARIA
function CustomButton({ onClick, children, pressed }) {
  return (
    <div
      role="button"
      tabIndex={0}
      aria-pressed={pressed}
      onClick={onClick}
      onKeyDown={(e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          onClick();
        }
      }}
    >
      {children}
    </div>
  );
}

// Toggle switch with full accessibility
function ToggleSwitch({ checked, onChange, label }) {
  const id = useId();
  
  return (
    <label htmlFor={id} className="toggle">
      <span className="toggle-label">{label}</span>
      <input
        type="checkbox"
        id={id}
        checked={checked}
        onChange={(e) => onChange(e.target.checked)}
      />
      <span 
        className="toggle-slider"
        aria-hidden="true"
      />
    </label>
  );
}
```

---

## 7. PLATFORM-SPECIFIC IMPLEMENTATION

### 7.1 Flutter Mobile Accessibility

```dart
// Flutter widget accessibility implementation
class AccessibleProductCard extends StatelessWidget {
  final Product product;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return Semantics(
      button: true,
      label: '${product.name}, Price: ${product.price} Taka',
      hint: 'Double tap to view product details',
      child: InkWell(
        onTap: onTap,
        child: Card(
          child: Column(
            children: [
              Image.network(product.imageUrl),
              Text(product.name),
              Text('৳${product.price}'),
            ],
          ),
        ),
      ),
    );
  }
}

// Screen reader announcement
void announceToScreenReader(String message) {
  SemanticsService.announce(
    message,
    TextDirection.ltr,
  );
}

// Focus management
FocusNode _buttonFocus = FocusNode();

// Programmatic focus
void _moveFocusToNext() {
  FocusScope.of(context).nextFocus();
}
```

### 7.2 Web Accessibility (React)

```jsx
// React hook for focus management
function useFocusManagement() {
  const focusRefs = useRef([]);

  const setFocus = (index) => {
    focusRefs.current[index]?.focus();
  };

  const handleArrowNavigation = (e, currentIndex, totalItems) => {
    if (e.key === 'ArrowDown') {
      e.preventDefault();
      setFocus((currentIndex + 1) % totalItems);
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      setFocus((currentIndex - 1 + totalItems) % totalItems);
    }
  };

  return { focusRefs, setFocus, handleArrowNavigation };
}

// Live region for dynamic updates
function LiveRegion() {
  return (
    <div 
      role="status" 
      aria-live="polite" 
      aria-atomic="true"
      className="visually-hidden"
    >
      {/* Dynamic content announced to screen readers */}
    </div>
  );
}
```

---

## 8. TESTING PROCEDURES

### 8.1 Automated Testing Tools

| Tool | Purpose | Usage |
|------|---------|-------|
| **axe DevTools** | Browser extension for WCAG testing | Chrome/Firefox extension |
| **Lighthouse** | Chrome DevTools accessibility audit | Built into Chrome |
| **WAVE** | Web accessibility evaluation tool | Web-based evaluator |
| **Flutter Accessibility Scanner** | Mobile app testing | Android emulator |
| **VoiceOver (iOS)** | Screen reader testing | Built into iOS |
| **TalkBack (Android)** | Screen reader testing | Built into Android |

### 8.2 Manual Testing Checklist

```
┌─────────────────────────────────────────────────────────────────┐
│                    ACCESSIBILITY TESTING CHECKLIST               │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  KEYBOARD NAVIGATION                                             │
│  [ ] All functionality works without mouse                      │
│  [ ] Tab order is logical and follows visual layout             │
│  [ ] Focus indicators are visible on all interactive elements   │
│  [ ] No keyboard traps                                          │
│  [ ] Skip links available for main content                      │
│                                                                  │
│  SCREEN READER TESTING                                           │
│  [ ] All images have meaningful alt text                        │
│  [ ] Form labels are properly associated                        │
│  [ ] Dynamic content is announced                               │
│  [ ] Page title changes on route navigation                     │
│  [ ] Status messages are announced without focus change         │
│                                                                  │
│  VISUAL TESTING                                                  │
│  [ ] Color contrast meets WCAG AA (4.5:1 for text)              │
│  [ ] Information not conveyed by color alone                    │
│  [ ] Text resizes to 200% without loss of content               │
│  [ ] Focus indicators visible and high contrast                 │
│                                                                  │
│  MOBILE TESTING                                                  │
│  [ ] Touch targets minimum 44×44dp                              │
│  [ ] Screen reader announces all elements correctly             │
│  [ ] Landscape and portrait orientations supported              │
│  [ ] Zoom works without horizontal scrolling                    │
│                                                                  │
│  CONTENT TESTING                                                 │
│  [ ] Page language declared                                     │
│  [ ] Language changes marked inline                             │
│  [ ] Error messages are clear and helpful                       │
│  [ ] Form validation prevents errors where possible             │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 9. ACCESSIBILITY STATEMENT TEMPLATE

```html
<!-- Accessibility Statement for Smart Dairy Website -->
<section aria-labelledby="accessibility-heading">
  <h1 id="accessibility-heading">Accessibility Statement</h1>
  
  <p>
    Smart Dairy Ltd. is committed to ensuring digital accessibility 
    for people with disabilities. We are continually improving the 
    user experience for everyone and applying the relevant 
    accessibility standards.
  </p>
  
  <h2>Conformance Status</h2>
  <p>
    The Web Content Accessibility Guidelines (WCAG) defines requirements 
    for designers and developers to improve accessibility for people 
    with disabilities. Smart Dairy's web portals and mobile applications 
    are partially conformant with WCAG 2.2 level AA. Partially conformant 
    means that some parts of the content do not fully conform to the 
    accessibility standard.
  </p>
  
  <h2>Technical Specifications</h2>
  <p>
    Accessibility of Smart Dairy platforms relies on the following 
    technologies to work with the particular combination of web browser 
    and any assistive technologies or plugins installed on your computer:
  </p>
  <ul>
    <li>HTML5</li>
    <li>WAI-ARIA</li>
    <li>CSS3</li>
    <li>JavaScript</li>
  </ul>
  
  <h2>Feedback</h2>
  <p>
    We welcome your feedback on the accessibility of Smart Dairy. 
    Please let us know if you encounter accessibility barriers:
  </p>
  <ul>
    <li>Phone: +880 XXXX-XXXXXX</li>
    <li>E-mail: accessibility@smartdairy.bd</li>
    <li>Postal address: Jahir Smart Tower, Dhaka-1207, Bangladesh</li>
  </ul>
  
  <p>
    We aim to respond to accessibility feedback within 5 business days.
  </p>
  
  <h2>Assessment Approach</h2>
  <p>
    Smart Dairy assessed the accessibility of its platforms by the 
    following approaches:
  </p>
  <ul>
    <li>Self-evaluation using automated testing tools</li>
    <li>External evaluation by accessibility consultants</li>
    <li>User testing with assistive technology users</li>
  </ul>
  
  <p>Date of statement: January 31, 2026</p>
</section>
```

---

## 10. APPENDICES

### Appendix A: Quick Reference - WCAG 2.2 Success Criteria

| SC | Level | Requirement | Test Method |
|----|-------|-------------|-------------|
| 1.1.1 | A | Non-text content | Alt text audit |
| 1.3.1 | A | Info and relationships | Semantic HTML check |
| 1.4.3 | AA | Contrast minimum | Color contrast analyzer |
| 1.4.4 | AA | Resize text | 200% zoom test |
| 2.1.1 | A | Keyboard | Tab navigation test |
| 2.4.4 | A | Link purpose | Link text review |
| 2.4.7 | AA | Focus visible | Visual inspection |
| 2.5.8 | AA | Target size | Measurement tool |
| 3.3.1 | A | Error identification | Form error testing |
| 4.1.2 | A | Name, role, value | Screen reader test |

### Appendix B: Assistive Technology Support Matrix

| Technology | Version | Platform | Support Level |
|------------|---------|----------|---------------|
| JAWS | 2024+ | Windows | Full |
| NVDA | 2023.3+ | Windows | Full |
| VoiceOver | macOS 14+ | macOS | Full |
| VoiceOver | iOS 17+ | iOS | Full |
| TalkBack | Android 14+ | Android | Full |
| Dragon NaturallySpeaking | 16+ | Windows | Partial |

---

## DOCUMENT REVISION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Accessibility Specialist | Initial release with WCAG 2.2 |

---

*This document is the property of Smart Dairy Ltd. and contains confidential information.*
*Unauthorized distribution or reproduction is prohibited.*
