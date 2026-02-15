# Milestone 136: Accessibility Testing

## Smart Dairy Digital Smart Portal + ERP - Phase 14: Testing & Documentation

| Field | Detail |
|-------|--------|
| Milestone | 136 of 150 (6 of 10 in Phase 14) |
| Title | Accessibility Testing |
| Phase | Phase 14 - Testing & Documentation |
| Days | Days 676-680 (of 750 total) |
| Duration | 5 working days |
| Predecessor | Milestone 135 - Performance Testing |
| Successor | Milestone 137 - Technical Documentation |
| Version | 1.0 |
| Status | Draft |
| Last Updated | 2026-02-05 |
| Authors | Dev 1 (Backend Lead), Dev 2 (Full-Stack), Dev 3 (Frontend Lead) |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Requirement Traceability Matrix](#2-requirement-traceability-matrix)
3. [Day-by-Day Breakdown](#3-day-by-day-breakdown)
4. [Technical Specifications](#4-technical-specifications)
5. [Testing & Validation](#5-testing--validation)
6. [Risk & Mitigation](#6-risk--mitigation)
7. [Dependencies & Handoffs](#7-dependencies--handoffs)
8. [Appendices](#8-appendices)

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Achieve WCAG 2.1 Level AA compliance across all Smart Dairy Digital Portal interfaces, ensuring the application is accessible to users with disabilities including those using screen readers, keyboard navigation, and assistive technologies.

### 1.2 Objectives

| # | Objective | Priority | Success Indicator |
|---|-----------|----------|-------------------|
| 1 | Integrate axe-core automated accessibility testing | Critical | 100% pages scanned |
| 2 | Conduct NVDA screen reader testing | Critical | All workflows accessible |
| 3 | Test JAWS screen reader compatibility | High | Critical paths verified |
| 4 | Verify VoiceOver compatibility (iOS/macOS) | High | Mobile app accessible |
| 5 | Audit keyboard navigation across all interfaces | Critical | Tab order logical |
| 6 | Validate color contrast ratios | Critical | WCAG AA compliance |
| 7 | Implement and test ARIA attributes | High | Semantic markup verified |
| 8 | Test focus management patterns | High | Focus visible at all times |
| 9 | Generate VPAT documentation | High | Compliance documented |
| 10 | Create accessibility remediation tracker | Medium | Issues prioritized |

### 1.3 Key Deliverables

| # | Deliverable | Format | Owner | Day |
|---|-------------|--------|-------|-----|
| 1 | axe-core Integration Scripts | JS/TS | Dev 3 | 676 |
| 2 | Cypress Accessibility Test Suite | JS | Dev 2 | 676 |
| 3 | NVDA Test Results | MD | Dev 3 | 677 |
| 4 | Keyboard Navigation Audit | PDF | Dev 2 | 677 |
| 5 | JAWS Compatibility Report | MD | Dev 3 | 678 |
| 6 | Focus Management Implementation | Code | Dev 2 | 678 |
| 7 | ARIA Implementation Guide | MD | Dev 3 | 679 |
| 8 | VoiceOver Test Results | MD | Dev 3 | 680 |
| 9 | Color Contrast Audit | PDF | Dev 3 | 679 |
| 10 | WCAG 2.1 AA Compliance Report | PDF | Dev 1 | 680 |
| 11 | VPAT Document | DOCX | Dev 1 | 680 |
| 12 | Accessibility Remediation Tracker | Excel | Dev 3 | 680 |

### 1.4 Prerequisites

| # | Prerequisite | Source | Validation |
|---|--------------|--------|------------|
| 1 | Performance testing completed | Milestone 135 | Sign-off document |
| 2 | UI components stable | Development | No pending UI changes |
| 3 | Screen readers installed | Dev Environment | NVDA, JAWS configured |
| 4 | axe-core license active | Procurement | License verified |
| 5 | Testing devices available | IT | iOS/Android devices ready |

### 1.5 Success Criteria

| # | Criterion | Target | Measurement |
|---|-----------|--------|-------------|
| 1 | WCAG 2.1 AA Compliance | 100% | Automated + manual audit |
| 2 | axe-core Critical Issues | 0 | Automated scan |
| 3 | axe-core Serious Issues | 0 | Automated scan |
| 4 | Keyboard Navigation | 100% accessible | Manual testing |
| 5 | Screen Reader Compatibility | All major readers | NVDA, JAWS, VoiceOver |
| 6 | Color Contrast Ratio (Normal Text) | ≥ 4.5:1 | Contrast analyzer |
| 7 | Color Contrast Ratio (Large Text) | ≥ 3:1 | Contrast analyzer |
| 8 | Focus Indicator Visibility | 100% visible | Manual testing |
| 9 | Form Label Association | 100% | Automated + manual |
| 10 | Alt Text Coverage | 100% images | Automated scan |

---

## 2. Requirement Traceability Matrix

### 2.1 RFP Requirements

| Req ID | Requirement Description | Implementation | Day | Verification |
|--------|------------------------|----------------|-----|--------------|
| RFP-ACC-001 | WCAG 2.1 Level AA compliance | Full audit and remediation | 676-680 | Compliance report |
| RFP-ACC-002 | Screen reader compatibility | NVDA, JAWS, VoiceOver testing | 677-680 | Test reports |
| RFP-ACC-003 | Keyboard navigation support | Complete keyboard accessibility | 677-678 | Navigation audit |
| RFP-ACC-004 | Color contrast compliance | Contrast ratio validation | 679 | Contrast audit |
| RFP-ACC-005 | Mobile accessibility | Flutter TalkBack/VoiceOver | 680 | Mobile test report |

### 2.2 BRD Requirements

| Req ID | Requirement Description | Implementation | Day | Verification |
|--------|------------------------|----------------|-----|--------------|
| BRD-ACC-001 | Inclusive design for all users | Universal design principles | 676-680 | Design review |
| BRD-ACC-002 | Bilingual accessibility (EN/BN) | Language-specific testing | 679 | Multilingual audit |
| BRD-ACC-003 | Accessible forms and inputs | Form accessibility testing | 677 | Form audit |
| BRD-ACC-004 | Accessible data visualizations | Chart accessibility | 679 | Chart audit |
| BRD-ACC-005 | Accessible error messaging | Error announcement testing | 678 | Screen reader test |

### 2.3 SRS Requirements

| Req ID | Requirement Description | Implementation | Day | Verification |
|--------|------------------------|----------------|-----|--------------|
| SRS-ACC-001 | Semantic HTML structure | HTML5 semantic elements | 676 | Code review |
| SRS-ACC-002 | ARIA landmarks and labels | ARIA implementation | 679 | axe-core scan |
| SRS-ACC-003 | Skip navigation links | Skip link implementation | 677 | Manual test |
| SRS-ACC-004 | Focus trap management | Modal/dialog focus handling | 678 | Focus audit |
| SRS-ACC-005 | Live region announcements | ARIA live regions | 679 | Screen reader test |
| SRS-ACC-006 | Responsive text sizing | 200% zoom support | 679 | Zoom testing |

### 2.4 Technology Stack Requirements

| Req ID | Requirement Description | Implementation | Day | Verification |
|--------|------------------------|----------------|-----|--------------|
| TECH-ACC-001 | OWL Framework accessibility | OWL component audit | 676 | Component test |
| TECH-ACC-002 | React accessibility | React a11y testing | 677 | Jest-axe tests |
| TECH-ACC-003 | Flutter accessibility | Semantics widget testing | 680 | Flutter a11y test |
| TECH-ACC-004 | Odoo web accessibility | Odoo frontend audit | 676 | Odoo-specific tests |
| TECH-ACC-005 | PDF accessibility | Accessible PDF generation | 679 | PDF/UA compliance |

---

## 3. Day-by-Day Breakdown

### Day 676 - Automated Accessibility Testing Setup
**Date Reference:** Day 676 of 750 | Phase 14, Day 26 of 50

#### Dev 1 - Backend Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Review accessibility requirements from RFP/BRD | Requirements checklist |
| 11:00 | 2h | Configure API error messages for accessibility | Accessible error formats |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Implement accessible PDF generation | PDF/UA compliant templates |
| 16:00 | 1h | Review accessibility headers in API responses | HTTP accessibility audit |
| 17:00 | 1h | Document backend accessibility patterns | Backend a11y guide |

**Key Deliverables:**
- Accessible error message formats
- PDF/UA compliant report templates
- Backend accessibility documentation

#### Dev 2 - Full-Stack (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Integrate axe-core with Cypress | cypress-axe setup |
| 11:00 | 2h | Create accessibility test suite framework | a11y-tests/ directory |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Write automated accessibility tests for auth pages | auth.a11y.spec.js |
| 16:00 | 1h | Configure CI/CD accessibility gates | GitHub Actions config |
| 17:00 | 1h | Document automated testing procedures | Testing guide |

**Key Deliverables:**
- Cypress-axe integration
- Automated accessibility test suite
- CI/CD accessibility pipeline

#### Dev 3 - Frontend/Mobile Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Install and configure NVDA screen reader | NVDA setup complete |
| 11:00 | 2h | Audit OWL component accessibility | OWL a11y report |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Configure pa11y for page-level testing | pa11y configuration |
| 16:00 | 1h | Set up WAVE browser extension | WAVE testing ready |
| 17:00 | 1h | Create accessibility testing checklist | Manual test checklist |

**Key Deliverables:**
- Screen reader environment configured
- OWL component accessibility report
- Accessibility testing toolkit ready

---

### Day 677 - Screen Reader and Keyboard Testing
**Date Reference:** Day 677 of 750 | Phase 14, Day 27 of 50

#### Dev 1 - Backend Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Implement accessible data table exports | Accessible CSV/Excel |
| 11:00 | 2h | Review and fix form validation messages | Accessible validation |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Audit API documentation accessibility | API docs a11y check |
| 16:00 | 1h | Test accessible email templates | Email template audit |
| 17:00 | 1h | Document accessibility patterns used | Pattern documentation |

**Key Deliverables:**
- Accessible data exports
- Accessible form validation messages
- Accessible API documentation

#### Dev 2 - Full-Stack (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Complete keyboard navigation audit - Auth flows | Auth keyboard report |
| 11:00 | 2h | Audit keyboard navigation - Dashboard | Dashboard keyboard report |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Audit keyboard navigation - Forms | Forms keyboard report |
| 16:00 | 1h | Implement skip navigation links | Skip links code |
| 17:00 | 1h | Compile keyboard navigation audit document | Full keyboard audit |

**Key Deliverables:**
- Complete keyboard navigation audit
- Skip navigation implementation
- Keyboard accessibility report

#### Dev 3 - Frontend/Mobile Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | NVDA testing - Login and registration | NVDA login report |
| 11:00 | 2h | NVDA testing - Dashboard and navigation | NVDA dashboard report |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | NVDA testing - Data entry forms | NVDA forms report |
| 16:00 | 1h | NVDA testing - Data tables and lists | NVDA tables report |
| 17:00 | 1h | Compile NVDA test results | NVDA full report |

**Key Deliverables:**
- Complete NVDA screen reader test results
- Issue list with severity ratings
- Remediation recommendations

---

### Day 678 - JAWS Testing and Focus Management
**Date Reference:** Day 678 of 750 | Phase 14, Day 28 of 50

#### Dev 1 - Backend Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Implement accessible notification system | Accessible notifications |
| 11:00 | 2h | Review WebSocket message accessibility | WS a11y patterns |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Audit report generation accessibility | Report a11y check |
| 16:00 | 1h | Test print stylesheet accessibility | Print a11y audit |
| 17:00 | 1h | Document backend fixes implemented | Fix documentation |

**Key Deliverables:**
- Accessible notification system
- WebSocket accessibility patterns
- Print-friendly accessibility

#### Dev 2 - Full-Stack (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Implement focus trap for modals | Modal focus trap code |
| 11:00 | 2h | Implement focus management for dialogs | Dialog focus code |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Fix focus return after modal close | Focus return code |
| 16:00 | 1h | Test focus visibility across themes | Focus visibility audit |
| 17:00 | 1h | Document focus management patterns | Focus guide |

**Key Deliverables:**
- Modal/dialog focus trap implementation
- Focus return patterns
- Focus visibility improvements

#### Dev 3 - Frontend/Mobile Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | JAWS testing - Critical user workflows | JAWS workflow report |
| 11:00 | 2h | JAWS testing - Complex components | JAWS components report |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | JAWS testing - Error handling flows | JAWS error report |
| 16:00 | 1h | Compare NVDA vs JAWS results | Comparison analysis |
| 17:00 | 1h | Compile JAWS compatibility report | JAWS full report |

**Key Deliverables:**
- Complete JAWS screen reader test results
- NVDA vs JAWS comparison
- Cross-reader compatibility analysis

---

### Day 679 - ARIA Implementation and Color Contrast
**Date Reference:** Day 679 of 750 | Phase 14, Day 29 of 50

#### Dev 1 - Backend Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Review ARIA roles in Odoo templates | Odoo ARIA audit |
| 11:00 | 2h | Implement ARIA live regions for updates | Live region code |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Test multilingual accessibility (EN/BN) | Bilingual a11y report |
| 16:00 | 1h | Audit right-to-left (RTL) support | RTL audit |
| 17:00 | 1h | Document ARIA implementation | ARIA documentation |

**Key Deliverables:**
- ARIA live regions implementation
- Multilingual accessibility verification
- ARIA implementation guide

#### Dev 2 - Full-Stack (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Implement ARIA landmarks | Landmarks code |
| 11:00 | 2h | Add ARIA labels to interactive elements | ARIA labels code |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Implement accessible tooltips | Tooltip a11y code |
| 16:00 | 1h | Add ARIA descriptions for complex widgets | Widget ARIA code |
| 17:00 | 1h | Test ARIA implementation with axe-core | ARIA test results |

**Key Deliverables:**
- Complete ARIA landmark implementation
- Accessible tooltips and popovers
- ARIA validation results

#### Dev 3 - Frontend/Mobile Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Color contrast audit - Core UI | Core contrast report |
| 11:00 | 2h | Color contrast audit - Data visualizations | Chart contrast report |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Fix color contrast issues | Contrast fixes code |
| 16:00 | 1h | Test color-blind modes | Color-blind test |
| 17:00 | 1h | Generate color contrast compliance report | Contrast report |

**Key Deliverables:**
- Complete color contrast audit
- Color contrast remediation
- Color-blind accessibility verification

---

### Day 680 - VoiceOver Testing and Compliance Documentation
**Date Reference:** Day 680 of 750 | Phase 14, Day 30 of 50

#### Dev 1 - Backend Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Compile all accessibility test results | Results compilation |
| 11:00 | 2h | Create WCAG 2.1 AA compliance report | Compliance report |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Generate VPAT documentation | VPAT document |
| 16:00 | 1h | Create executive accessibility summary | Executive summary |
| 17:00 | 1h | Handoff documentation for Milestone 137 | Handoff package |

**Key Deliverables:**
- WCAG 2.1 AA Compliance Report
- VPAT (Voluntary Product Accessibility Template)
- Executive summary for stakeholders

#### Dev 2 - Full-Stack (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Final axe-core scan across all pages | Final axe results |
| 11:00 | 2h | Verify all critical fixes implemented | Fix verification |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Update CI/CD with final accessibility gates | CI/CD updates |
| 16:00 | 1h | Document remaining issues for backlog | Backlog items |
| 17:00 | 1h | Team accessibility review meeting | Meeting notes |

**Key Deliverables:**
- Final automated accessibility scan
- CI/CD accessibility gates active
- Issue backlog documentation

#### Dev 3 - Frontend/Mobile Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | VoiceOver testing - macOS Safari | VoiceOver macOS report |
| 11:00 | 2h | VoiceOver testing - iOS mobile app | VoiceOver iOS report |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | TalkBack testing - Android mobile app | TalkBack report |
| 16:00 | 1h | Create accessibility remediation tracker | Remediation tracker |
| 17:00 | 1h | Final documentation and handoff | Final docs |

**Key Deliverables:**
- VoiceOver compatibility report
- TalkBack compatibility report
- Accessibility remediation tracker

---

## 4. Technical Specifications

### 4.1 Cypress-axe Integration

```javascript
// cypress/support/accessibility.js
// Smart Dairy Accessibility Testing Utilities

import 'cypress-axe';

/**
 * Custom accessibility commands for Smart Dairy
 */
Cypress.Commands.add('checkAccessibility', (options = {}) => {
  const defaultOptions = {
    runOnly: {
      type: 'tag',
      values: ['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa'],
    },
    rules: {
      // Disable rules that need manual verification
      'color-contrast': { enabled: true },
      'document-title': { enabled: true },
      'html-has-lang': { enabled: true },
      'landmark-one-main': { enabled: true },
      'page-has-heading-one': { enabled: true },
      'region': { enabled: true },
    },
  };

  const mergedOptions = { ...defaultOptions, ...options };

  cy.injectAxe();
  cy.checkA11y(null, mergedOptions, (violations) => {
    if (violations.length > 0) {
      cy.task('logAccessibilityViolations', violations);

      const violationData = violations.map(({ id, impact, description, nodes }) => ({
        id,
        impact,
        description,
        nodes: nodes.length,
        elements: nodes.map((node) => node.target).join(', '),
      }));

      cy.log('Accessibility Violations:', JSON.stringify(violationData, null, 2));
    }
  });
});

Cypress.Commands.add('checkPageAccessibility', (pageName) => {
  cy.log(`Checking accessibility for: ${pageName}`);

  cy.injectAxe();

  // Check with different configurations
  const checks = [
    { name: 'WCAG 2.1 AA', runOnly: { type: 'tag', values: ['wcag21aa'] } },
    { name: 'Best Practices', runOnly: { type: 'tag', values: ['best-practice'] } },
    { name: 'Section 508', runOnly: { type: 'tag', values: ['section508'] } },
  ];

  checks.forEach((check) => {
    cy.checkA11y(null, { runOnly: check.runOnly }, (violations) => {
      cy.task('saveAccessibilityReport', {
        page: pageName,
        check: check.name,
        violations,
        timestamp: new Date().toISOString(),
      });
    });
  });
});

// Keyboard navigation test helper
Cypress.Commands.add('testKeyboardNavigation', (startSelector, expectedOrder) => {
  cy.get(startSelector).focus();

  expectedOrder.forEach((selector, index) => {
    cy.focused()
      .should('match', selector)
      .then(() => {
        if (index < expectedOrder.length - 1) {
          cy.focused().tab();
        }
      });
  });
});

// Screen reader text verification
Cypress.Commands.add('verifyScreenReaderText', (selector, expectedText) => {
  cy.get(selector).then(($el) => {
    // Check aria-label
    const ariaLabel = $el.attr('aria-label');
    if (ariaLabel) {
      expect(ariaLabel).to.contain(expectedText);
      return;
    }

    // Check aria-labelledby
    const labelledBy = $el.attr('aria-labelledby');
    if (labelledBy) {
      cy.get(`#${labelledBy}`).should('contain', expectedText);
      return;
    }

    // Check aria-describedby
    const describedBy = $el.attr('aria-describedby');
    if (describedBy) {
      cy.get(`#${describedBy}`).should('contain', expectedText);
      return;
    }

    // Check visible text
    cy.wrap($el).should('contain', expectedText);
  });
});

// Focus trap verification
Cypress.Commands.add('verifyFocusTrap', (containerSelector) => {
  cy.get(containerSelector).within(() => {
    // Get all focusable elements
    const focusableSelectors = [
      'button:not([disabled])',
      'a[href]',
      'input:not([disabled])',
      'select:not([disabled])',
      'textarea:not([disabled])',
      '[tabindex]:not([tabindex="-1"])',
    ].join(',');

    cy.get(focusableSelectors).then(($elements) => {
      const firstElement = $elements.first();
      const lastElement = $elements.last();

      // Tab from last should go to first
      cy.wrap(lastElement).focus().tab();
      cy.focused().should('match', firstElement);

      // Shift+Tab from first should go to last
      cy.wrap(firstElement).focus().tab({ shift: true });
      cy.focused().should('match', lastElement);
    });
  });
});
```

### 4.2 Accessibility Test Suite

```javascript
// cypress/e2e/accessibility/pages.a11y.cy.js
// Smart Dairy Page Accessibility Tests

describe('Smart Dairy Accessibility Tests', () => {
  beforeEach(() => {
    cy.visit('/');
  });

  describe('Public Pages', () => {
    it('Home page should be accessible', () => {
      cy.visit('/');
      cy.checkPageAccessibility('Home');
    });

    it('Login page should be accessible', () => {
      cy.visit('/login');
      cy.checkPageAccessibility('Login');

      // Verify form labels
      cy.get('input[name="email"]')
        .should('have.attr', 'aria-label')
        .or('should have associated label');

      cy.get('input[name="password"]')
        .should('have.attr', 'type', 'password')
        .and('have.attr', 'aria-label');
    });

    it('Registration page should be accessible', () => {
      cy.visit('/register');
      cy.checkPageAccessibility('Registration');
    });
  });

  describe('Authenticated Pages', () => {
    beforeEach(() => {
      cy.login('test@smartdairy.com', 'TestPassword123!');
    });

    it('Dashboard should be accessible', () => {
      cy.visit('/dashboard');
      cy.checkPageAccessibility('Dashboard');

      // Verify landmark regions
      cy.get('main').should('exist');
      cy.get('nav').should('exist');
      cy.get('[role="banner"]').should('exist');
    });

    it('Animals list page should be accessible', () => {
      cy.visit('/farm/animals');
      cy.checkPageAccessibility('Animals List');

      // Verify table accessibility
      cy.get('table').should('have.attr', 'aria-label');
      cy.get('th').each(($th) => {
        cy.wrap($th).should('have.attr', 'scope');
      });
    });

    it('Milk collection form should be accessible', () => {
      cy.visit('/collections/new');
      cy.checkPageAccessibility('Milk Collection Form');

      // Verify form field associations
      cy.get('input, select, textarea').each(($field) => {
        const id = $field.attr('id');
        if (id) {
          cy.get(`label[for="${id}"]`).should('exist');
        } else {
          cy.wrap($field).should('have.attr', 'aria-label');
        }
      });
    });

    it('Orders page should be accessible', () => {
      cy.visit('/orders');
      cy.checkPageAccessibility('Orders');
    });

    it('Reports page should be accessible', () => {
      cy.visit('/reports');
      cy.checkPageAccessibility('Reports');

      // Verify chart accessibility
      cy.get('canvas, svg').each(($chart) => {
        cy.wrap($chart)
          .should('have.attr', 'role', 'img')
          .and('have.attr', 'aria-label');
      });
    });

    it('Settings page should be accessible', () => {
      cy.visit('/settings');
      cy.checkPageAccessibility('Settings');
    });
  });

  describe('Keyboard Navigation', () => {
    beforeEach(() => {
      cy.login('test@smartdairy.com', 'TestPassword123!');
    });

    it('should have logical tab order on dashboard', () => {
      cy.visit('/dashboard');

      // Skip link should be first
      cy.get('body').tab();
      cy.focused().should('match', '[data-testid="skip-link"]');

      // After skip link, navigation
      cy.focused().tab();
      cy.focused().should('be.visible');
    });

    it('should trap focus in modal dialogs', () => {
      cy.visit('/farm/animals');
      cy.get('[data-testid="add-animal-btn"]').click();

      // Modal should be open
      cy.get('[role="dialog"]').should('be.visible');

      // Verify focus trap
      cy.verifyFocusTrap('[role="dialog"]');
    });

    it('should return focus after modal close', () => {
      cy.visit('/farm/animals');

      // Open modal
      cy.get('[data-testid="add-animal-btn"]').as('trigger').click();
      cy.get('[role="dialog"]').should('be.visible');

      // Close modal with Escape
      cy.get('body').type('{esc}');

      // Focus should return to trigger
      cy.focused().should('match', '@trigger');
    });

    it('should navigate dropdown menus with arrow keys', () => {
      cy.visit('/dashboard');
      cy.get('[data-testid="user-menu-btn"]').focus().type('{enter}');

      // Menu should open
      cy.get('[role="menu"]').should('be.visible');

      // Arrow down should move focus
      cy.get('body').type('{downarrow}');
      cy.focused().should('have.attr', 'role', 'menuitem');

      // Escape should close menu
      cy.get('body').type('{esc}');
      cy.get('[role="menu"]').should('not.exist');
    });
  });

  describe('Screen Reader Announcements', () => {
    beforeEach(() => {
      cy.login('test@smartdairy.com', 'TestPassword123!');
    });

    it('should announce page navigation', () => {
      cy.visit('/dashboard');

      // Check for live region
      cy.get('[aria-live="polite"]').should('exist');

      // Navigate to new page
      cy.get('a[href="/farm/animals"]').click();

      // Live region should announce change
      cy.get('[aria-live="polite"]')
        .should('contain', 'Animals')
        .or('contain', 'Page loaded');
    });

    it('should announce form errors', () => {
      cy.visit('/collections/new');

      // Submit empty form
      cy.get('form').submit();

      // Error should be announced
      cy.get('[aria-live="assertive"]')
        .should('exist')
        .and('not.be.empty');

      // Error messages should be linked to fields
      cy.get('[aria-invalid="true"]').should('exist');
    });

    it('should announce loading states', () => {
      cy.visit('/farm/animals');

      // Loading indicator should have proper ARIA
      cy.get('[aria-busy="true"]').should('exist');

      // Wait for content
      cy.get('[aria-busy="true"]').should('not.exist');
    });
  });

  describe('Color and Visual', () => {
    it('should maintain contrast in light mode', () => {
      cy.visit('/');
      cy.checkAccessibility({
        rules: {
          'color-contrast': { enabled: true },
        },
      });
    });

    it('should maintain contrast in dark mode', () => {
      cy.visit('/');
      cy.get('[data-testid="theme-toggle"]').click();
      cy.checkAccessibility({
        rules: {
          'color-contrast': { enabled: true },
        },
      });
    });

    it('should not rely solely on color', () => {
      cy.visit('/dashboard');

      // Status indicators should have more than color
      cy.get('[data-status]').each(($el) => {
        cy.wrap($el)
          .should('have.attr', 'aria-label')
          .or('contain.text');
      });
    });

    it('should support 200% text zoom', () => {
      cy.viewport(1920, 1080);
      cy.visit('/dashboard');

      // Simulate 200% zoom
      cy.document().then((doc) => {
        doc.documentElement.style.fontSize = '200%';
      });

      // Content should still be accessible
      cy.get('main').should('be.visible');
      cy.get('nav').should('be.visible');

      // No horizontal scrolling
      cy.window().then((win) => {
        expect(win.document.body.scrollWidth).to.be.lte(win.innerWidth);
      });
    });
  });
});
```

### 4.3 ARIA Implementation Components

```typescript
// src/components/accessibility/AccessibleModal.tsx
// Smart Dairy Accessible Modal Component

import React, { useEffect, useRef, useCallback } from 'react';
import { createPortal } from 'react-dom';
import FocusTrap from 'focus-trap-react';

interface AccessibleModalProps {
  isOpen: boolean;
  onClose: () => void;
  title: string;
  description?: string;
  children: React.ReactNode;
  size?: 'small' | 'medium' | 'large';
  closeOnEscape?: boolean;
  closeOnOverlayClick?: boolean;
  initialFocusRef?: React.RefObject<HTMLElement>;
  returnFocusRef?: React.RefObject<HTMLElement>;
}

export const AccessibleModal: React.FC<AccessibleModalProps> = ({
  isOpen,
  onClose,
  title,
  description,
  children,
  size = 'medium',
  closeOnEscape = true,
  closeOnOverlayClick = true,
  initialFocusRef,
  returnFocusRef,
}) => {
  const modalRef = useRef<HTMLDivElement>(null);
  const titleId = `modal-title-${React.useId()}`;
  const descriptionId = description ? `modal-desc-${React.useId()}` : undefined;
  const previousActiveElement = useRef<HTMLElement | null>(null);

  // Store the previously focused element
  useEffect(() => {
    if (isOpen) {
      previousActiveElement.current = document.activeElement as HTMLElement;
    }
  }, [isOpen]);

  // Return focus when modal closes
  useEffect(() => {
    if (!isOpen && previousActiveElement.current) {
      const returnTarget = returnFocusRef?.current || previousActiveElement.current;
      returnTarget?.focus();
    }
  }, [isOpen, returnFocusRef]);

  // Handle escape key
  const handleKeyDown = useCallback(
    (event: KeyboardEvent) => {
      if (closeOnEscape && event.key === 'Escape') {
        onClose();
      }
    },
    [closeOnEscape, onClose]
  );

  useEffect(() => {
    if (isOpen) {
      document.addEventListener('keydown', handleKeyDown);
      // Prevent body scroll
      document.body.style.overflow = 'hidden';
    }

    return () => {
      document.removeEventListener('keydown', handleKeyDown);
      document.body.style.overflow = '';
    };
  }, [isOpen, handleKeyDown]);

  // Handle overlay click
  const handleOverlayClick = (event: React.MouseEvent) => {
    if (closeOnOverlayClick && event.target === event.currentTarget) {
      onClose();
    }
  };

  // Announce modal opening to screen readers
  useEffect(() => {
    if (isOpen) {
      const announcement = document.createElement('div');
      announcement.setAttribute('role', 'status');
      announcement.setAttribute('aria-live', 'polite');
      announcement.setAttribute('aria-atomic', 'true');
      announcement.className = 'sr-only';
      announcement.textContent = `Dialog opened: ${title}`;
      document.body.appendChild(announcement);

      return () => {
        document.body.removeChild(announcement);
      };
    }
  }, [isOpen, title]);

  if (!isOpen) return null;

  const sizeClasses = {
    small: 'max-w-sm',
    medium: 'max-w-lg',
    large: 'max-w-2xl',
  };

  const modalContent = (
    <div
      className="fixed inset-0 z-50 flex items-center justify-center"
      onClick={handleOverlayClick}
    >
      {/* Overlay */}
      <div
        className="fixed inset-0 bg-black bg-opacity-50"
        aria-hidden="true"
      />

      {/* Modal */}
      <FocusTrap
        active={isOpen}
        focusTrapOptions={{
          initialFocus: initialFocusRef?.current || undefined,
          escapeDeactivates: closeOnEscape,
          allowOutsideClick: closeOnOverlayClick,
        }}
      >
        <div
          ref={modalRef}
          role="dialog"
          aria-modal="true"
          aria-labelledby={titleId}
          aria-describedby={descriptionId}
          className={`relative bg-white rounded-lg shadow-xl p-6 ${sizeClasses[size]} w-full mx-4`}
        >
          {/* Close button */}
          <button
            type="button"
            onClick={onClose}
            className="absolute top-4 right-4 text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 rounded"
            aria-label="Close dialog"
          >
            <svg
              className="w-6 h-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              aria-hidden="true"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth={2}
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>

          {/* Title */}
          <h2
            id={titleId}
            className="text-xl font-semibold text-gray-900 pr-8"
          >
            {title}
          </h2>

          {/* Description */}
          {description && (
            <p
              id={descriptionId}
              className="mt-2 text-gray-600"
            >
              {description}
            </p>
          )}

          {/* Content */}
          <div className="mt-4">{children}</div>
        </div>
      </FocusTrap>
    </div>
  );

  return createPortal(modalContent, document.body);
};

// Accessible Form Field Component
interface AccessibleFieldProps {
  id: string;
  label: string;
  type?: string;
  value: string;
  onChange: (value: string) => void;
  error?: string;
  hint?: string;
  required?: boolean;
  disabled?: boolean;
}

export const AccessibleField: React.FC<AccessibleFieldProps> = ({
  id,
  label,
  type = 'text',
  value,
  onChange,
  error,
  hint,
  required = false,
  disabled = false,
}) => {
  const hintId = hint ? `${id}-hint` : undefined;
  const errorId = error ? `${id}-error` : undefined;
  const describedBy = [hintId, errorId].filter(Boolean).join(' ') || undefined;

  return (
    <div className="mb-4">
      <label
        htmlFor={id}
        className="block text-sm font-medium text-gray-700 mb-1"
      >
        {label}
        {required && (
          <span className="text-red-500 ml-1" aria-hidden="true">
            *
          </span>
        )}
        {required && <span className="sr-only">(required)</span>}
      </label>

      {hint && (
        <p id={hintId} className="text-sm text-gray-500 mb-1">
          {hint}
        </p>
      )}

      <input
        id={id}
        type={type}
        value={value}
        onChange={(e) => onChange(e.target.value)}
        aria-required={required}
        aria-invalid={!!error}
        aria-describedby={describedBy}
        disabled={disabled}
        className={`
          w-full px-3 py-2 border rounded-md shadow-sm
          focus:outline-none focus:ring-2 focus:ring-primary-500
          ${error
            ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
            : 'border-gray-300 focus:border-primary-500'
          }
          ${disabled ? 'bg-gray-100 cursor-not-allowed' : ''}
        `}
      />

      {error && (
        <p
          id={errorId}
          className="mt-1 text-sm text-red-600"
          role="alert"
        >
          {error}
        </p>
      )}
    </div>
  );
};

// Accessible Data Table Component
interface Column {
  key: string;
  header: string;
  sortable?: boolean;
}

interface AccessibleTableProps {
  caption: string;
  columns: Column[];
  data: Record<string, any>[];
  onSort?: (columnKey: string, direction: 'asc' | 'desc') => void;
  sortColumn?: string;
  sortDirection?: 'asc' | 'desc';
}

export const AccessibleTable: React.FC<AccessibleTableProps> = ({
  caption,
  columns,
  data,
  onSort,
  sortColumn,
  sortDirection,
}) => {
  const handleSort = (columnKey: string) => {
    if (onSort) {
      const newDirection =
        sortColumn === columnKey && sortDirection === 'asc' ? 'desc' : 'asc';
      onSort(columnKey, newDirection);
    }
  };

  const getSortLabel = (columnKey: string) => {
    if (sortColumn !== columnKey) return 'Sort by this column';
    return sortDirection === 'asc'
      ? 'Sorted ascending. Click to sort descending'
      : 'Sorted descending. Click to sort ascending';
  };

  return (
    <div className="overflow-x-auto" role="region" aria-label={caption}>
      <table className="min-w-full divide-y divide-gray-200">
        <caption className="sr-only">{caption}</caption>
        <thead className="bg-gray-50">
          <tr>
            {columns.map((column) => (
              <th
                key={column.key}
                scope="col"
                className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                aria-sort={
                  sortColumn === column.key
                    ? sortDirection === 'asc'
                      ? 'ascending'
                      : 'descending'
                    : undefined
                }
              >
                {column.sortable && onSort ? (
                  <button
                    onClick={() => handleSort(column.key)}
                    className="group flex items-center space-x-1 focus:outline-none focus:ring-2 focus:ring-primary-500 rounded"
                    aria-label={`${column.header}, ${getSortLabel(column.key)}`}
                  >
                    <span>{column.header}</span>
                    <span
                      className="text-gray-400 group-hover:text-gray-600"
                      aria-hidden="true"
                    >
                      {sortColumn === column.key ? (
                        sortDirection === 'asc' ? '↑' : '↓'
                      ) : (
                        '⇅'
                      )}
                    </span>
                  </button>
                ) : (
                  column.header
                )}
              </th>
            ))}
          </tr>
        </thead>
        <tbody className="bg-white divide-y divide-gray-200">
          {data.map((row, rowIndex) => (
            <tr key={rowIndex}>
              {columns.map((column, colIndex) => (
                <td
                  key={column.key}
                  className="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                  {...(colIndex === 0 && { scope: 'row' })}
                >
                  {row[column.key]}
                </td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>

      {data.length === 0 && (
        <p className="text-center py-8 text-gray-500" role="status">
          No data available
        </p>
      )}
    </div>
  );
};

// Skip Link Component
export const SkipLink: React.FC<{ targetId: string }> = ({ targetId }) => {
  return (
    <a
      href={`#${targetId}`}
      className="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:px-4 focus:py-2 focus:bg-primary-600 focus:text-white focus:top-0 focus:left-0"
      data-testid="skip-link"
    >
      Skip to main content
    </a>
  );
};

// Live Region Component for Announcements
interface LiveRegionProps {
  message: string;
  politeness?: 'polite' | 'assertive';
  atomic?: boolean;
}

export const LiveRegion: React.FC<LiveRegionProps> = ({
  message,
  politeness = 'polite',
  atomic = true,
}) => {
  return (
    <div
      role="status"
      aria-live={politeness}
      aria-atomic={atomic}
      className="sr-only"
    >
      {message}
    </div>
  );
};
```

### 4.4 Flutter Accessibility Implementation

```dart
// lib/widgets/accessibility/accessible_widgets.dart
// Smart Dairy Flutter Accessibility Widgets

import 'package:flutter/material.dart';
import 'package:flutter/semantics.dart';

/// Accessible button with proper semantics
class AccessibleButton extends StatelessWidget {
  final String label;
  final String? hint;
  final VoidCallback onPressed;
  final bool isLoading;
  final bool isDisabled;
  final IconData? icon;

  const AccessibleButton({
    Key? key,
    required this.label,
    this.hint,
    required this.onPressed,
    this.isLoading = false,
    this.isDisabled = false,
    this.icon,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Semantics(
      button: true,
      enabled: !isDisabled && !isLoading,
      label: label,
      hint: hint,
      onTap: isDisabled || isLoading ? null : onPressed,
      child: MergeSemantics(
        child: ElevatedButton(
          onPressed: isDisabled || isLoading ? null : onPressed,
          child: Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              if (isLoading)
                Padding(
                  padding: const EdgeInsets.only(right: 8),
                  child: SizedBox(
                    width: 16,
                    height: 16,
                    child: CircularProgressIndicator(
                      strokeWidth: 2,
                      valueColor: AlwaysStoppedAnimation<Color>(
                        Theme.of(context).colorScheme.onPrimary,
                      ),
                    ),
                  ),
                )
              else if (icon != null)
                Padding(
                  padding: const EdgeInsets.only(right: 8),
                  child: Icon(icon, size: 18),
                ),
              Text(isLoading ? 'Loading...' : label),
            ],
          ),
        ),
      ),
    );
  }
}

/// Accessible text field with proper labeling
class AccessibleTextField extends StatelessWidget {
  final String label;
  final String? hint;
  final String? errorText;
  final TextEditingController controller;
  final bool obscureText;
  final bool required;
  final TextInputType? keyboardType;
  final ValueChanged<String>? onChanged;
  final FocusNode? focusNode;

  const AccessibleTextField({
    Key? key,
    required this.label,
    this.hint,
    this.errorText,
    required this.controller,
    this.obscureText = false,
    this.required = false,
    this.keyboardType,
    this.onChanged,
    this.focusNode,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final String semanticLabel = required ? '$label (required)' : label;

    return Semantics(
      textField: true,
      label: semanticLabel,
      hint: hint,
      value: controller.text,
      child: TextField(
        controller: controller,
        focusNode: focusNode,
        obscureText: obscureText,
        keyboardType: keyboardType,
        onChanged: onChanged,
        decoration: InputDecoration(
          labelText: label,
          hintText: hint,
          errorText: errorText,
          suffixIcon: required
              ? Semantics(
                  label: 'Required field',
                  child: const Icon(Icons.star, color: Colors.red, size: 8),
                )
              : null,
          border: const OutlineInputBorder(),
          errorBorder: const OutlineInputBorder(
            borderSide: BorderSide(color: Colors.red),
          ),
        ),
      ),
    );
  }
}

/// Accessible card with proper semantics for screen readers
class AccessibleCard extends StatelessWidget {
  final String title;
  final String? subtitle;
  final Widget child;
  final VoidCallback? onTap;
  final String? semanticLabel;

  const AccessibleCard({
    Key? key,
    required this.title,
    this.subtitle,
    required this.child,
    this.onTap,
    this.semanticLabel,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final cardContent = Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              title,
              style: Theme.of(context).textTheme.titleMedium,
            ),
            if (subtitle != null)
              Text(
                subtitle!,
                style: Theme.of(context).textTheme.bodySmall,
              ),
            const SizedBox(height: 12),
            child,
          ],
        ),
      ),
    );

    return Semantics(
      label: semanticLabel ?? title,
      hint: subtitle,
      button: onTap != null,
      child: onTap != null
          ? InkWell(
              onTap: onTap,
              child: cardContent,
            )
          : cardContent,
    );
  }
}

/// Accessible data table for screen readers
class AccessibleDataTable extends StatelessWidget {
  final String caption;
  final List<String> headers;
  final List<List<String>> rows;
  final Function(int)? onRowTap;

  const AccessibleDataTable({
    Key? key,
    required this.caption,
    required this.headers,
    required this.rows,
    this.onRowTap,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Semantics(
      label: 'Data table: $caption',
      child: SingleChildScrollView(
        scrollDirection: Axis.horizontal,
        child: DataTable(
          columns: headers
              .map((header) => DataColumn(
                    label: Semantics(
                      header: true,
                      child: Text(header),
                    ),
                  ))
              .toList(),
          rows: rows.asMap().entries.map((entry) {
            final index = entry.key;
            final row = entry.value;

            return DataRow(
              onSelectChanged: onRowTap != null
                  ? (_) => onRowTap!(index)
                  : null,
              cells: row.asMap().entries.map((cellEntry) {
                final cellIndex = cellEntry.key;
                final cellValue = cellEntry.value;

                return DataCell(
                  Semantics(
                    label: '${headers[cellIndex]}: $cellValue',
                    child: Text(cellValue),
                  ),
                );
              }).toList(),
            );
          }).toList(),
        ),
      ),
    );
  }
}

/// Accessible image with alt text
class AccessibleImage extends StatelessWidget {
  final String imageUrl;
  final String altText;
  final double? width;
  final double? height;
  final BoxFit fit;

  const AccessibleImage({
    Key? key,
    required this.imageUrl,
    required this.altText,
    this.width,
    this.height,
    this.fit = BoxFit.cover,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Semantics(
      image: true,
      label: altText,
      child: Image.network(
        imageUrl,
        width: width,
        height: height,
        fit: fit,
        errorBuilder: (context, error, stackTrace) {
          return Semantics(
            label: 'Image failed to load: $altText',
            child: Container(
              width: width,
              height: height,
              color: Colors.grey[300],
              child: const Icon(Icons.broken_image),
            ),
          );
        },
        loadingBuilder: (context, child, loadingProgress) {
          if (loadingProgress == null) return child;
          return Semantics(
            label: 'Loading image: $altText',
            child: Container(
              width: width,
              height: height,
              color: Colors.grey[200],
              child: const Center(
                child: CircularProgressIndicator(),
              ),
            ),
          );
        },
      ),
    );
  }
}

/// Accessible status indicator
class AccessibleStatusIndicator extends StatelessWidget {
  final String status;
  final Color color;

  const AccessibleStatusIndicator({
    Key? key,
    required this.status,
    required this.color,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Semantics(
      label: 'Status: $status',
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 12,
            height: 12,
            decoration: BoxDecoration(
              color: color,
              shape: BoxShape.circle,
            ),
          ),
          const SizedBox(width: 8),
          Text(status),
        ],
      ),
    );
  }
}

/// Screen reader announcer mixin
mixin ScreenReaderAnnouncer<T extends StatefulWidget> on State<T> {
  void announce(String message, {bool assertive = false}) {
    SemanticsService.announce(
      message,
      assertive ? TextDirection.ltr : TextDirection.ltr,
    );
  }

  void announcePageChange(String pageName) {
    announce('Navigated to $pageName');
  }

  void announceLoadingComplete(String content) {
    announce('$content loaded');
  }

  void announceError(String error) {
    announce('Error: $error', assertive: true);
  }
}
```

### 4.5 VPAT Document Template

```markdown
# Voluntary Product Accessibility Template (VPAT)
## Smart Dairy Digital Smart Portal + ERP

### Product Information

| Field | Value |
|-------|-------|
| Product Name | Smart Dairy Digital Smart Portal |
| Version | 1.0.0 |
| Vendor | Smart Dairy Ltd. |
| Date | [DATE] |
| Contact | accessibility@smartdairy.com |
| Applicable Standards | WCAG 2.1 Level AA |

### Terms

- **Supports**: The functionality of the product has at least one method that meets the criterion without known defects or meets with equivalent facilitation.
- **Partially Supports**: Some functionality of the product does not meet the criterion.
- **Does Not Support**: The majority of product functionality does not meet the criterion.
- **Not Applicable**: The criterion is not relevant to the product.

---

### WCAG 2.1 Level A Compliance

#### Principle 1: Perceivable

| Criterion | Conformance Level | Remarks |
|-----------|------------------|---------|
| 1.1.1 Non-text Content | Supports | All images have alt text |
| 1.2.1 Audio-only and Video-only | Not Applicable | No prerecorded audio/video |
| 1.2.2 Captions | Not Applicable | No prerecorded audio |
| 1.2.3 Audio Description | Not Applicable | No prerecorded video |
| 1.3.1 Info and Relationships | Supports | Semantic HTML used throughout |
| 1.3.2 Meaningful Sequence | Supports | Content reads in logical order |
| 1.3.3 Sensory Characteristics | Supports | Instructions don't rely solely on sensory |
| 1.4.1 Use of Color | Supports | Color is not only means of conveying info |
| 1.4.2 Audio Control | Not Applicable | No auto-playing audio |

#### Principle 2: Operable

| Criterion | Conformance Level | Remarks |
|-----------|------------------|---------|
| 2.1.1 Keyboard | Supports | All functionality keyboard accessible |
| 2.1.2 No Keyboard Trap | Supports | Focus can always be moved |
| 2.1.4 Character Key Shortcuts | Supports | Shortcuts can be disabled |
| 2.2.1 Timing Adjustable | Supports | Session timeout adjustable |
| 2.2.2 Pause, Stop, Hide | Supports | Auto-updating content can be paused |
| 2.3.1 Three Flashes | Supports | No content flashes |
| 2.4.1 Bypass Blocks | Supports | Skip links provided |
| 2.4.2 Page Titled | Supports | All pages have unique titles |
| 2.4.3 Focus Order | Supports | Logical tab order |
| 2.4.4 Link Purpose | Supports | Links have descriptive text |

#### Principle 3: Understandable

| Criterion | Conformance Level | Remarks |
|-----------|------------------|---------|
| 3.1.1 Language of Page | Supports | Lang attribute present |
| 3.2.1 On Focus | Supports | No unexpected changes on focus |
| 3.2.2 On Input | Supports | No unexpected changes on input |
| 3.3.1 Error Identification | Supports | Errors clearly identified |
| 3.3.2 Labels or Instructions | Supports | All inputs have labels |

#### Principle 4: Robust

| Criterion | Conformance Level | Remarks |
|-----------|------------------|---------|
| 4.1.1 Parsing | Supports | Valid HTML throughout |
| 4.1.2 Name, Role, Value | Supports | ARIA properly implemented |

---

### WCAG 2.1 Level AA Compliance

| Criterion | Conformance Level | Remarks |
|-----------|------------------|---------|
| 1.3.4 Orientation | Supports | Content works in any orientation |
| 1.3.5 Identify Input Purpose | Supports | Autocomplete attributes used |
| 1.4.3 Contrast (Minimum) | Supports | 4.5:1 ratio for text |
| 1.4.4 Resize Text | Supports | Text resizable to 200% |
| 1.4.5 Images of Text | Supports | No images of text used |
| 1.4.10 Reflow | Supports | Content reflows at 320px |
| 1.4.11 Non-text Contrast | Supports | UI components have 3:1 contrast |
| 1.4.12 Text Spacing | Supports | No loss of content with adjusted spacing |
| 1.4.13 Content on Hover or Focus | Supports | Tooltips dismissible |
| 2.4.5 Multiple Ways | Supports | Search and navigation provided |
| 2.4.6 Headings and Labels | Supports | Descriptive headings used |
| 2.4.7 Focus Visible | Supports | Focus indicator always visible |
| 3.1.2 Language of Parts | Supports | Language changes marked |
| 3.2.3 Consistent Navigation | Supports | Navigation consistent |
| 3.2.4 Consistent Identification | Supports | Components identified consistently |
| 3.3.3 Error Suggestion | Supports | Error correction suggestions provided |
| 3.3.4 Error Prevention | Supports | Review before submission |
| 4.1.3 Status Messages | Supports | ARIA live regions used |

---

### Testing Methodology

| Method | Tools Used |
|--------|------------|
| Automated Testing | axe-core, pa11y, WAVE |
| Manual Testing | Keyboard navigation, visual inspection |
| Screen Reader Testing | NVDA, JAWS, VoiceOver |
| User Testing | Users with disabilities |

### Known Issues and Remediation Plan

| Issue | Impact | Planned Fix |
|-------|--------|-------------|
| [Issue 1] | [Impact] | [Timeline] |
| [Issue 2] | [Impact] | [Timeline] |

### Legal Disclaimer

This VPAT is a self-assessment by Smart Dairy Ltd. and represents our understanding of the product's accessibility at the time of evaluation. Accessibility conformance is an ongoing effort, and we are committed to continuous improvement.
```

---

## 5. Testing & Validation

### 5.1 Accessibility Test Acceptance Criteria

| Test Category | Metric | Target | Priority |
|--------------|--------|--------|----------|
| Automated Scan (axe-core) | Critical Issues | 0 | Critical |
| Automated Scan (axe-core) | Serious Issues | 0 | Critical |
| Automated Scan (axe-core) | Moderate Issues | < 5 | High |
| Color Contrast | Normal Text Ratio | ≥ 4.5:1 | Critical |
| Color Contrast | Large Text Ratio | ≥ 3:1 | Critical |
| Keyboard Navigation | All Interactive Elements | 100% accessible | Critical |
| Screen Reader | NVDA Compatibility | All workflows | Critical |
| Screen Reader | JAWS Compatibility | Critical paths | High |
| Screen Reader | VoiceOver Compatibility | All platforms | High |
| Focus Management | Visibility | 100% visible | Critical |
| ARIA | Proper Implementation | 100% valid | High |
| Forms | Label Association | 100% | Critical |
| Images | Alt Text Coverage | 100% | Critical |

### 5.2 Test Execution Checklist

#### Day 676 - Setup
- [ ] axe-core integrated with Cypress
- [ ] NVDA screen reader installed
- [ ] pa11y configured
- [ ] WAVE extension installed
- [ ] OWL component audit completed
- [ ] Testing checklist created

#### Day 677 - Screen Reader & Keyboard
- [ ] NVDA testing completed for all pages
- [ ] Keyboard navigation audit completed
- [ ] Skip links implemented
- [ ] Form accessibility verified
- [ ] Data table accessibility verified

#### Day 678 - JAWS & Focus
- [ ] JAWS testing completed
- [ ] Modal focus trap implemented
- [ ] Focus return patterns implemented
- [ ] Focus visibility verified
- [ ] NVDA vs JAWS comparison completed

#### Day 679 - ARIA & Color
- [ ] ARIA landmarks implemented
- [ ] ARIA labels added
- [ ] Live regions implemented
- [ ] Color contrast audit completed
- [ ] Color-blind testing completed

#### Day 680 - Documentation
- [ ] VoiceOver macOS testing completed
- [ ] VoiceOver iOS testing completed
- [ ] TalkBack testing completed
- [ ] WCAG compliance report generated
- [ ] VPAT document completed
- [ ] Remediation tracker created

### 5.3 Screen Reader Test Script

```markdown
# Screen Reader Test Script - NVDA

## Pre-Test Setup
1. Ensure NVDA is installed (latest version)
2. Reset NVDA settings to default
3. Enable speech viewer for documentation
4. Clear browser history

## Test 1: Login Page

### Steps
1. Navigate to login page
2. Press H to navigate by headings
3. Press F to navigate by form fields
4. Fill in email field
5. Fill in password field
6. Submit form

### Expected Behavior
- Page title announced on load
- Main heading (h1) found with H key
- Email field label read when focused
- Password field identified as password type
- Submit button clearly identified
- Success/error message announced

### Result
[ ] Pass / [ ] Fail

### Notes:
_______________________

## Test 2: Dashboard Navigation

### Steps
1. Login as test user
2. Use Tab to navigate main menu
3. Activate menu items
4. Navigate data cards
5. Interact with action buttons

### Expected Behavior
- Landmarks identified (main, nav, etc.)
- Menu items announced with roles
- Active state announced
- Cards have meaningful labels
- Buttons announce their purpose

### Result
[ ] Pass / [ ] Fail

### Notes:
_______________________

## Test 3: Data Table Interaction

### Steps
1. Navigate to Animals list
2. Use Table navigation (T key)
3. Navigate cells with arrow keys
4. Sort columns
5. Select rows

### Expected Behavior
- Table announced with caption
- Column headers read
- Row/column position announced
- Sort state announced
- Selection state announced

### Result
[ ] Pass / [ ] Fail

### Notes:
_______________________

## Test 4: Form Submission

### Steps
1. Navigate to Add Animal form
2. Tab through all fields
3. Leave required field empty
4. Submit form
5. Correct errors

### Expected Behavior
- All fields have labels
- Required fields identified
- Error messages announced
- Focus moves to error
- Success message announced

### Result
[ ] Pass / [ ] Fail

### Notes:
_______________________

## Test 5: Modal Dialog

### Steps
1. Open confirmation modal
2. Navigate within modal
3. Close with Escape
4. Verify focus return

### Expected Behavior
- Modal announced as dialog
- Focus trapped in modal
- Close button accessible
- Focus returns to trigger
- Modal dismissed announced

### Result
[ ] Pass / [ ] Fail

### Notes:
_______________________
```

---

## 6. Risk & Mitigation

### 6.1 Risk Register

| Risk ID | Risk Description | Probability | Impact | Mitigation Strategy | Owner |
|---------|-----------------|-------------|--------|---------------------|-------|
| ACC-R001 | Third-party components not accessible | Medium | High | Audit third-party libs, create wrappers | Dev 3 |
| ACC-R002 | Dynamic content not announced | Medium | High | Implement ARIA live regions | Dev 2 |
| ACC-R003 | Color contrast failures in themes | Medium | Medium | Comprehensive color palette review | Dev 3 |
| ACC-R004 | Complex widgets inaccessible | Medium | High | Follow ARIA Authoring Practices | Dev 2 |
| ACC-R005 | Mobile app accessibility gaps | Medium | Medium | Dedicated mobile a11y testing | Dev 3 |
| ACC-R006 | Screen reader inconsistencies | Medium | Medium | Test multiple screen readers | Dev 3 |
| ACC-R007 | Keyboard focus not visible | Low | High | Enhanced focus styles | Dev 2 |
| ACC-R008 | Form validation not accessible | Medium | High | ARIA error implementation | Dev 1 |

### 6.2 Contingency Plans

| Scenario | Trigger | Response Plan |
|----------|---------|---------------|
| axe-core critical issues | Automated scan fails | Immediate remediation sprint |
| Screen reader incompatibility | Manual testing fails | Implement ARIA workarounds |
| Color contrast failures | Contrast check fails | Update design tokens |
| Third-party component issues | Audit reveals issues | Create accessible wrappers |

---

## 7. Dependencies & Handoffs

### 7.1 Internal Dependencies

| Dependency | Source | Required By | Status |
|------------|--------|-------------|--------|
| Performance testing completed | Milestone 135 | Day 676 | Pending |
| UI components stable | Development | Day 676 | Ready |
| Design tokens finalized | Design Team | Day 676 | Ready |
| Testing environment access | DevOps | Day 676 | Ready |

### 7.2 External Dependencies

| Dependency | Provider | Required By | Status |
|------------|----------|-------------|--------|
| NVDA Screen Reader | NV Access | Day 676 | Installed |
| JAWS License | Freedom Scientific | Day 678 | Active |
| axe-core License | Deque | Day 676 | Active |
| macOS device | IT | Day 680 | Available |
| iOS device | IT | Day 680 | Available |

### 7.3 Milestone 137 Handoff Package

| Item | Description | Location |
|------|-------------|----------|
| WCAG Compliance Report | Full compliance status | `/docs/accessibility/wcag-report.pdf` |
| VPAT Document | Accessibility conformance template | `/docs/accessibility/vpat.docx` |
| Screen Reader Reports | NVDA, JAWS, VoiceOver results | `/docs/accessibility/screen-reader/` |
| Remediation Tracker | Outstanding issues | `/docs/accessibility/remediation.xlsx` |
| ARIA Implementation Guide | Code patterns | `/docs/accessibility/aria-guide.md` |
| Cypress A11y Tests | Automated test suite | `/cypress/e2e/accessibility/` |

---

## 8. Appendices

### 8.1 WCAG 2.1 Quick Reference

| Level | Criteria Count | Focus Areas |
|-------|----------------|-------------|
| A | 30 | Minimum accessibility |
| AA | 20 | Standard accessibility (our target) |
| AAA | 28 | Enhanced accessibility |

### 8.2 Screen Reader Market Share

| Screen Reader | Platform | Market Share |
|---------------|----------|--------------|
| NVDA | Windows | 40.5% |
| JAWS | Windows | 40.1% |
| VoiceOver | macOS/iOS | 12.9% |
| TalkBack | Android | 4.7% |
| Other | Various | 1.8% |

### 8.3 Color Contrast Requirements

| Text Type | Minimum Ratio (AA) | Enhanced (AAA) |
|-----------|-------------------|----------------|
| Normal Text | 4.5:1 | 7:1 |
| Large Text (18pt+) | 3:1 | 4.5:1 |
| UI Components | 3:1 | N/A |
| Graphics | 3:1 | N/A |

### 8.4 Keyboard Shortcuts Reference

| Action | Shortcut | Context |
|--------|----------|---------|
| Skip to content | Alt + 1 | Global |
| Skip to navigation | Alt + 2 | Global |
| Open search | / | Global |
| Close modal | Escape | Modal |
| Next item | Tab | Global |
| Previous item | Shift + Tab | Global |
| Activate | Enter/Space | Buttons |
| Navigate menu | Arrow keys | Menus |

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-05 | Dev Team | Initial document creation |

---

**End of Milestone 136: Accessibility Testing**
