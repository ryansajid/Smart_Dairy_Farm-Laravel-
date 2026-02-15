# SMART DAIRY LTD.
## ANIMATION & INTERACTION DESIGN GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | A-014 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Motion Design Lead |
| **Owner** | UI/UX Director |
| **Reviewer** | Frontend Lead |
| **Status** | Final |
| **Related Documents** | A-001 (Design System), A-008-A-010 (Mobile Specs), A-012 (Accessibility) |
| **Technology Stack** | CSS Animations, Framer Motion (React), Flutter Animations, Lottie |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Animation Principles](#2-animation-principles)
3. [Timing & Easing](#3-timing--easing)
4. [Micro-interactions](#4-micro-interactions)
5. [Page Transitions](#5-page-transitions)
6. [Loading & Progress States](#6-loading--progress-states)
7. [Gesture & Touch Feedback](#7-gesture--touch-feedback)
8. [Accessibility & Motion](#8-accessibility--motion)
9. [Implementation Guidelines](#9-implementation-guidelines)
10. [Appendices](#10-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines the animation and interaction design language for Smart Dairy digital products. It establishes principles, patterns, and technical specifications for creating meaningful, purposeful motion that enhances user experience while maintaining performance and accessibility.

### 1.2 Animation Philosophy

```
┌─────────────────────────────────────────────────────────────────┐
│                  ANIMATION DESIGN PHILOSOPHY                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  "Motion should guide, inform, and delight - never distract.   │
│   Every animation serves a purpose: to show relationships,      │
│   provide feedback, demonstrate change, or create hierarchy."   │
│                                                                  │
│  CORE VALUES:                                                    │
│  ├── Purposeful - Every animation has a reason                  │
│  ├── Subtle - Motion supports content, doesn't dominate         │
│  ├── Consistent - Unified timing and easing across platform     │
│  ├── Performant - 60fps smooth animations                       │
│  └── Accessible - Respects user preferences for reduced motion  │
│                                                                  │
│  INSPIRATION SOURCES:                                            │
│  • Natural organic movement (farm, dairy, nature)               │
│  • Smooth liquid flows (milk, water)                            │
│  • Growth and life cycles (plants, animals)                     │
│  • Bangladeshi cultural patterns                                │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 1.3 Animation Categories

| Category | Purpose | Examples |
|----------|---------|----------|
| **Feedback** | Confirm user action | Button press, success check |
| **Status** | Show system state | Loading, syncing, errors |
| **Navigation** | Orient user in space | Page transitions, scroll |
| **Educational** | Teach interface | Onboarding, tooltips |
| **Delight** | Create emotional connection | Celebrations, Easter eggs |

---

## 2. ANIMATION PRINCIPLES

### 2.1 The 12 Principles Applied to UI

```
┌─────────────────────────────────────────────────────────────────┐
│               DISNEY PRINCIPLES IN UI CONTEXT                    │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  1. SQUASH & STRETCH                                            │
│     UI: Button press scaling, elastic overshoot                 │
│     Use: Add life to interactions, show force                   │
│                                                                  │
│  2. ANTICIPATION                                                │
│     UI: Pre-animation hint, hold before action                  │
│     Use: Prepare user for change, build expectation             │
│                                                                  │
│  3. STAGING                                                     │
│     UI: Focus management, spotlight effect                      │
│     Use: Guide attention to important elements                  │
│                                                                  │
│  4. STRAIGHT AHEAD & POSE TO POSE                               │
│     UI: Physics-based vs keyframe animations                    │
│     Use: Natural movement vs controlled transitions             │
│                                                                  │
│  5. FOLLOW THROUGH & OVERLAPPING ACTION                         │
│     UI: Staggered list items, cascading effects                 │
│     Use: Create rhythm, show relationships                      │
│                                                                  │
│  6. SLOW IN & SLOW OUT                                          │
│     UI: Easing curves, acceleration/deceleration                │
│     Use: Natural motion, reduce mechanical feel                 │
│                                                                  │
│  7. ARCS                                                        │
│     UI: Curved motion paths, orbital menus                      │
│     Use: Organic, natural movement                              │
│                                                                  │
│  8. SECONDARY ACTION                                            │
│     UI: Accompanying micro-interactions                         │
│     Use: Add richness without distraction                       │
│                                                                  │
│  9. TIMING                                                      │
│     UI: Animation duration, frame counts                        │
│     Use: Control perceived weight, importance                   │
│                                                                  │
│  10. EXAGGERATION                                               │
│     UI: Emphasized states, bouncy effects                       │
│     Use: Clarify intent, add personality                        │
│                                                                  │
│  11. SOLID DRAWING                                              │
│     UI: Spatial consistency, 3D transforms                      │
│     Use: Ground elements in realistic space                     │
│                                                                  │
│  12. APPEAL                                                     │
│     UI: Brand personality in motion                             │
│     Use: Create emotional connection                            │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 Smart Dairy Motion Characteristics

| Characteristic | Description | Implementation |
|----------------|-------------|----------------|
| **Organic** | Natural, fluid movement | Custom easing curves, spring physics |
| **Confident** | Decisive, purposeful | Short durations, clear direction |
| **Smooth** | No jarring transitions | Consistent timing, proper easing |
| **Responsive** | Immediate feedback | <100ms response to input |

---

## 3. TIMING & EASING

### 3.1 Duration Guidelines

| Animation Type | Duration | Use Case |
|----------------|----------|----------|
| **Micro-interaction** | 100-200ms | Button press, toggle |
| **Component state** | 200-300ms | Expand/collapse, fade |
| **Page transition** | 300-500ms | Route change, modal |
| **Complex animation** | 500-1000ms | Onboarding, celebration |
| **Ambient/Background** | 3000ms+ | Loading, idle states |

### 3.2 Easing Functions

```css
/* Standard Easing Library */

/* ease-in-out: Smooth acceleration and deceleration */
--ease-default: cubic-bezier(0.4, 0.0, 0.2, 1);

/* ease-out: Quick start, smooth landing */
--ease-decelerate: cubic-bezier(0.0, 0.0, 0.2, 1);

/* ease-in: Smooth start, quick end */
--ease-accelerate: cubic-bezier(0.4, 0.0, 1, 1);

/* Sharp: Quick movement with snap */
--ease-sharp: cubic-bezier(0.4, 0.0, 0.6, 1);

/* Elastic: Bouncy, organic feel */
--ease-elastic: cubic-bezier(0.68, -0.55, 0.265, 1.55);

/* Spring: Natural spring physics */
--ease-spring: cubic-bezier(0.175, 0.885, 0.32, 1.275);

/* Usage examples */
.button-press {
  transition: transform 150ms var(--ease-default);
}

.page-enter {
  animation: slideIn 300ms var(--ease-decelerate);
}

.success-check {
  animation: popIn 400ms var(--ease-elastic);
}
```

### 3.3 Flutter Easing Equivalents

```dart
// Flutter animation curves matching CSS easing
class SmartDairyCurves {
  static const defaultEase = Curves.easeInOut;
  static const decelerate = Curves.easeOut;
  static const accelerate = Curves.easeIn;
  static const sharp = Curves.easeInOutQuad;
  static const elastic = Curves.elasticOut;
  static const spring = Curves.bounceOut;
}

// Usage
AnimatedContainer(
  duration: Duration(milliseconds: 300),
  curve: SmartDairyCurves.decelerate,
  child: child,
);
```

---

## 4. MICRO-INTERACTIONS

### 4.1 Button Interactions

```
┌─────────────────────────────────────────────────────────────────┐
│                    BUTTON MICRO-INTERACTIONS                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  DEFAULT STATE                                                  │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │              [    Add to Cart    ]                      │   │
│  │                                                         │   │
│  │  Background: Primary Green                              │   │
│  │  Scale: 1.0                                             │   │
│  │  Shadow: Elevation 2                                    │   │
│  └─────────────────────────────────────────────────────────┘   │
│                              │                                   │
│         Hover/Tap            ▼                                   │
│                              │                                   │
│  PRESSED STATE                                                  │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │              [    Add to Cart    ]                      │   │
│  │                                                         │   │
│  │  Background: Darker Green                               │   │
│  │  Scale: 0.96 (4% reduction)                             │   │
│  │  Shadow: Elevation 0                                    │   │
│  │  Duration: 100ms                                        │   │
│  │  Easing: ease-out                                       │   │
│  └─────────────────────────────────────────────────────────┘   │
│                              │                                   │
│         Release              ▼                                   │
│                              │                                   │
│  SUCCESS STATE                                                  │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │              [    ✓  Added!    ]                        │   │
│  │                                                         │   │
│  │  Background: Success Green                              │   │
│  │  Scale: 1.0 → 1.05 → 1.0 (pulse)                        │   │
│  │  Icon: Morph cart to check                              │   │
│  │  Duration: 300ms                                        │   │
│  │  Easing: elastic                                        │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 4.2 Toggle Switch Animation

```dart
// Flutter Animated Toggle Switch
class AnimatedToggle extends StatefulWidget {
  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () => setState(() => isOn = !isOn),
      child: AnimatedContainer(
        duration: Duration(milliseconds: 200),
        curve: Curves.easeInOut,
        width: 56,
        height: 32,
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(16),
          color: isOn ? Colors.green : Colors.grey,
        ),
        child: AnimatedAlign(
          duration: Duration(milliseconds: 200),
          curve: Curves.easeInOut,
          alignment: isOn ? Alignment.centerRight : Alignment.centerLeft,
          child: AnimatedContainer(
            duration: Duration(milliseconds: 150),
            curve: Curves.easeOut,
            margin: EdgeInsets.all(2),
            width: isOn ? 28 : 24,
            height: 28,
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              color: Colors.white,
              boxShadow: [
                BoxShadow(
                  color: Colors.black26,
                  blurRadius: 4,
                  offset: Offset(0, 2),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
```

### 4.3 Pull-to-Refresh Animation

```dart
// Custom pull-to-refresh indicator
class MilkDropRefreshIndicator extends StatelessWidget {
  final double progress;

  @override
  Widget build(BuildContext context) {
    return CustomPaint(
      size: Size(40, 60),
      painter: MilkDropPainter(
        progress: progress,
        color: Theme.of(context).primaryColor,
      ),
    );
  }
}

// Animation sequence:
// 1. User pulls down (0-50%): Drop shape forms, grows
// 2. Release to refresh (50-100%): Drop elongates
// 3. Refreshing: Drop detaches and bounces
// 4. Complete: Drop splashes, checkmark appears
```

---

## 5. PAGE TRANSITIONS

### 5.1 Web Page Transitions (React/Framer Motion)

```jsx
// Page transition wrapper
import { motion, AnimatePresence } from 'framer-motion';

const pageVariants = {
  initial: {
    opacity: 0,
    x: 20,
  },
  in: {
    opacity: 1,
    x: 0,
    transition: {
      duration: 0.3,
      ease: [0.4, 0, 0.2, 1], // ease-out
    },
  },
  out: {
    opacity: 0,
    x: -20,
    transition: {
      duration: 0.2,
      ease: [0.4, 0, 1, 1], // ease-in
    },
  },
};

function PageTransition({ children }) {
  return (
    <motion.div
      initial="initial"
      animate="in"
      exit="out"
      variants={pageVariants}
    >
      {children}
    </motion.div>
  );
}

// Staggered content entrance
const containerVariants = {
  hidden: { opacity: 0 },
  visible: {
    opacity: 1,
    transition: {
      staggerChildren: 0.1,
      delayChildren: 0.2,
    },
  },
};

const itemVariants = {
  hidden: { opacity: 0, y: 20 },
  visible: {
    opacity: 1,
    y: 0,
    transition: {
      duration: 0.4,
      ease: [0, 0, 0.2, 1],
    },
  },
};
```

### 5.2 Mobile Screen Transitions (Flutter)

```dart
// Custom page route with shared element transition
class ProductDetailRoute extends PageRouteBuilder {
  final Widget page;
  final String heroTag;

  ProductDetailRoute({this.page, this.heroTag})
      : super(
          pageBuilder: (context, animation, secondaryAnimation) => page,
          transitionsBuilder: (context, animation, secondaryAnimation, child) {
            return FadeTransition(
              opacity: Tween<double>(begin: 0, end: 1).animate(
                CurvedAnimation(
                  parent: animation,
                  curve: Interval(0, 0.5, curve: Curves.easeOut),
                ),
              ),
              child: SlideTransition(
                position: Tween<Offset>(
                  begin: Offset(0, 0.1),
                  end: Offset.zero,
                ).animate(
                  CurvedAnimation(
                    parent: animation,
                    curve: Curves.easeOut,
                  ),
                ),
                child: child,
              ),
            );
          },
          transitionDuration: Duration(milliseconds: 300),
        );
}
```

---

## 6. LOADING & PROGRESS STATES

### 6.1 Skeleton Loading

```jsx
// Skeleton screen component
function SkeletonCard() {
  return (
    <div className="skeleton-card">
      <div className="skeleton-image shimmer" />
      <div className="skeleton-text shimmer" style={{ width: '80%' }} />
      <div className="skeleton-text shimmer" style={{ width: '60%' }} />
    </div>
  );
}

// CSS shimmer animation
@keyframes shimmer {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}

.shimmer {
  background: linear-gradient(
    90deg,
    var(--skeleton-base) 25%,
    var(--skeleton-highlight) 50%,
    var(--skeleton-base) 75%
  );
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
}
```

### 6.2 Progress Indicators

```
┌─────────────────────────────────────────────────────────────────┐
│                    PROGRESS INDICATOR TYPES                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  1. DETERMINATE (Known duration)                                │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  Uploading...  65%                                      │   │
│  │  ┌████████████████████████████████░░░░░░░░░░░░░░░░░░┐  │   │
│  │  12.5 MB of 19.2 MB | 30 seconds remaining            │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                  │
│  2. INDETERMINATE (Unknown duration)                            │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  Syncing data...                                        │   │
│  │  ┌───────────────────────────────────────────────────┐  │   │
│  │  │ ▓▓▓▓▓▓▓▓▓▓░░░░░░░░░░▓▓▓▓▓▓▓▓▓▓░░░░░░░░░░      │  │   │
│  │  └───────────────────────────────────────────────────┘  │   │
│  │  Please don't close the app                             │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                  │
│  3. CIRCULAR (Compact spaces)                                   │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │        ↻                                                │   │
│  │      /    \                                             │   │
│  │     |  75% |    300ms rotation, 4-color segments       │   │
│  │      \    /                                             │   │
│  │        ↻                                                │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 7. GESTURE & TOUCH FEEDBACK

### 7.1 Touch Response Guidelines

| Gesture | Visual Feedback | Duration | Notes |
|---------|-----------------|----------|-------|
| **Tap** | Ripple from touch point | 300ms | Color opacity change |
| **Long Press** | Scale up + shadow | 200ms | Haptic feedback |
| **Swipe** | Follow finger + resistance | Real-time | Snap to dismiss |
| **Pinch** | Smooth scaling | Real-time | Elastic bounds |
| **Drag** | Elevate + shadow | Immediate | Ghost preview |

### 7.2 Ripple Effect Implementation

```dart
// Flutter Material ripple with customization
InkWell(
  onTap: onTap,
  splashColor: Colors.green.withOpacity(0.2),
  highlightColor: Colors.green.withOpacity(0.1),
  borderRadius: BorderRadius.circular(8),
  child: Container(
    padding: EdgeInsets.all(16),
    child: Text('Tap Me'),
  ),
);

// Custom ripple animation
class CustomRipple extends StatefulWidget {
  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTapDown: (details) => _startRipple(details.localPosition),
      child: Stack(
        children: [
          child,
          ...ripples.map((ripple) => AnimatedBuilder(
            animation: ripple.controller,
            builder: (context, child) {
              return Positioned(
                left: ripple.position.dx - ripple.radius,
                top: ripple.position.dy - ripple.radius,
                child: Container(
                  width: ripple.radius * 2,
                  height: ripple.radius * 2,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: ripple.color.withOpacity(
                      0.3 * (1 - ripple.controller.value),
                    ),
                  ),
                ),
              );
            },
          )),
        ],
      ),
    );
  }
}
```

---

## 8. ACCESSIBILITY & MOTION

### 8.1 Reduced Motion Support

```css
/* Respect user preferences for reduced motion */
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
  
  /* Keep essential motion */
  .essential-motion {
    animation-duration: 150ms !important;
    transition-duration: 150ms !important;
  }
}

/* Flutter implementation */
class AccessibleAnimation extends StatelessWidget {
  final Widget child;
  final Animation<double> animation;
  
  @override
  Widget build(BuildContext context) {
    final mediaQuery = MediaQuery.of(context);
    final disableAnimations = mediaQuery.disableAnimations;
    
    if (disableAnimations) {
      return child;
    }
    
    return AnimatedBuilder(
      animation: animation,
      builder: (context, child) => child,
      child: child,
    );
  }
}
```

### 8.2 Seizure-Safe Animations

```
┌─────────────────────────────────────────────────────────────────┐
│                    SEIZURE-SAFE GUIDELINES                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  FLASHING CONTENT RESTRICTIONS (WCAG 2.3.1)                     │
│  ├── Max 3 flashes per second                                   │
│  ├── No saturated red flashing                                  │
│  └── Max flash area: 25% of screen                              │
│                                                                  │
│  FORBIDDEN PATTERNS:                                            │
│  ✗ Rapid alternating high-contrast colors                       │
│  ✗ Flashing error states                                        │
│  ✗ Animated backgrounds with high contrast                      │
│                                                                  │
│  SAFE ALTERNATIVES:                                             │
│  ✓ Solid color state changes                                    │
│  ✓ Fade transitions                                             │
│  ✓ Position animations (not color)                              │
│  ✓ Opacity changes (gentle)                                     │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 9. IMPLEMENTATION GUIDELINES

### 9.1 Performance Optimization

```
┌─────────────────────────────────────────────────────────────────┐
│                    ANIMATION PERFORMANCE                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  DO:                                                            │
│  ✓ Use transform and opacity only (GPU accelerated)             │
│  ✓ Use will-change before animation, remove after               │
│  ✓ Animate at 60fps (16ms per frame)                            │
│  ✓ Batch DOM reads and writes                                   │
│  ✓ Use CSS animations for simple effects                        │
│  ✓ Use requestAnimationFrame for JavaScript animations          │
│                                                                  │
│  DON'T:                                                         │
│  ✗ Animate width, height, top, left (causes reflow)             │
│  ✗ Use blur during scroll                                       │
│  ✗ Animate multiple properties simultaneously                   │
│  ✗ Run heavy calculations during animation                      │
│                                                                  │
│  FLUTTER SPECIFIC:                                              │
│  ✓ Use AnimatedContainer for size/color changes                 │
│  ✓ Use Transform widget for position/scale                      │
│  ✓ Pre-cache images before animation                            │
│  ✓ Use RepaintBoundary for complex widgets                      │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 10. APPENDICES

### Appendix A: Animation Tokens

```json
{
  "animation": {
    "duration": {
      "fast": "100ms",
      "normal": "200ms",
      "slow": "300ms",
      "slower": "500ms"
    },
    "easing": {
      "default": "cubic-bezier(0.4, 0.0, 0.2, 1)",
      "decelerate": "cubic-bezier(0.0, 0.0, 0.2, 1)",
      "accelerate": "cubic-bezier(0.4, 0.0, 1, 1)",
      "elastic": "cubic-bezier(0.68, -0.55, 0.265, 1.55)"
    },
    "stagger": {
      "fast": "50ms",
      "normal": "100ms",
      "slow": "150ms"
    }
  }
}
```

### Appendix B: Lottie Animation Specifications

| Animation | File Size | Use Case |
|-----------|-----------|----------|
| Success check | < 15 KB | Form submission, order complete |
| Loading spinner | < 10 KB | General loading states |
| Empty state | < 30 KB | No orders, no products |
| Error state | < 20 KB | 404, connection error |
| Celebration | < 50 KB | Order milestone, referral |

---

## DOCUMENT REVISION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Motion Design Lead | Initial release |

---

*This document is the property of Smart Dairy Ltd. and contains confidential information.*
*Unauthorized distribution or reproduction is prohibited.*
