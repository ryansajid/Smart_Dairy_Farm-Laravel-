# SMART DAIRY LTD.
## REGRESSION TEST SUITE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | G-020 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | QA Engineer |
| **Owner** | QA Lead |
| **Reviewer** | Development Lead |
| **Approval Status** | Draft |
| **Classification** | Internal |

---

### Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | QA Engineer | Initial document creation |

---

### Distribution List

| Role | Name | Organization |
|------|------|--------------|
| QA Lead | [TBD] | Smart Dairy Ltd. |
| QA Engineer | [TBD] | Smart Dairy Ltd. |
| Development Lead | [TBD] | Implementation Vendor |
| DevOps Engineer | [TBD] | Implementation Vendor |
| Project Manager | [TBD] | Smart Dairy Ltd. |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Regression Scope](#2-regression-scope)
3. [Test Case Selection Criteria](#3-test-case-selection-criteria)
4. [Regression Test Categories](#4-regression-test-categories)
5. [Automation Strategy](#5-automation-strategy)
6. [Test Execution Schedule](#6-test-execution-schedule)
7. [Test Case Maintenance](#7-test-case-maintenance)
8. [Risk-Based Prioritization](#8-risk-based-prioritization)
9. [Execution Procedures](#9-execution-procedures)
10. [Results Analysis](#10-results-analysis)
11. [Reporting](#11-reporting)
12. [Appendices](#12-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This Regression Test Suite document defines the comprehensive regression testing strategy, test cases, and execution procedures for the Smart Dairy Smart Web Portal System & Integrated ERP. The purpose is to ensure that new code changes, bug fixes, and enhancements do not adversely affect existing functionality across all system modules.

### 1.2 Regression Testing Strategy

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    REGRESSION TESTING STRATEGY OVERVIEW                      │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                         TEST LEVELS                                  │    │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐ │    │
│  │  │   Smoke     │  │  Full       │  │  Selective  │  │  Complete   │ │    │
│  │  │   Tests     │  │  Regression │  │  Regression │  │  Regression │ │    │
│  │  │  (Daily)    │  │  (Weekly)   │  │  (Per Fix)  │  │  (Release)  │ │    │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘ │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                       AUTOMATION PYRAMID                             │    │
│  │                                                                      │    │
│  │                    ┌─────────────────┐                               │    │
│  │                    │   E2E Tests     │  10% - Critical paths         │    │
│  │                    │   (Selenium)    │                               │    │
│  │                    ├─────────────────┤                               │    │
│  │                    │  API Tests      │  30% - Service layer          │    │
│  │                    │  (Postman/k6)   │                               │    │
│  │                    ├─────────────────┤                               │    │
│  │                    │  Unit/Component │  60% - Developer owned        │    │
│  │                    │  (pytest/Jest)  │                               │    │
│  │                    └─────────────────┘                               │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 1.3 Regression Testing Objectives

| Objective ID | Objective Description | Success Criteria |
|--------------|----------------------|------------------|
| REG-OBJ-001 | Validate system stability after changes | Zero critical defects in production |
| REG-OBJ-002 | Ensure business-critical workflows function correctly | 100% critical path tests pass |
| REG-OBJ-003 | Verify integration points remain intact | All API contracts honored |
| REG-OBJ-004 | Confirm data integrity across modules | No data corruption or loss |
| REG-OBJ-005 | Validate UI/UX consistency | Cross-browser compatibility maintained |
| REG-OBJ-006 | Ensure mobile app functionality | All mobile regression tests pass |
| REG-OBJ-007 | Verify performance baselines | Response times within SLA |
| REG-OBJ-008 | Confirm security controls remain effective | No new vulnerabilities introduced |

### 1.4 Definition of Regression Testing

Regression testing is the process of re-testing software that has been modified to ensure that:
- Existing functionality still works as expected
- New bugs have not been introduced
- Previously fixed bugs remain fixed
- Changes have not impacted related functionality
- Integration points continue to function correctly

### 1.5 Regression Testing Principles

1. **Early Detection**: Run regression tests as soon as possible after changes
2. **Automation First**: Automate repetitive regression tests for efficiency
3. **Risk-Based Priority**: Focus on high-risk areas and critical business functions
4. **Continuous Integration**: Integrate regression tests into CI/CD pipeline
5. **Comprehensive Coverage**: Cover functional, integration, UI, API, and performance aspects
6. **Maintainability**: Keep test cases updated with application changes

---

## 2. REGRESSION SCOPE

### 2.1 In-Scope Components

The following components are included in the regression test suite:

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      REGRESSION SCOPE - INCLUSIONS                           │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │  PUBLIC WEBSITE (15 Tests)                                           │    │
│  │  • Homepage functionality      • Navigation & menus                  │    │
│  │  • Content pages               • Multi-language support              │    │
│  │  • SEO elements                • Responsive design                   │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │  B2C E-COMMERCE PORTAL (25 Tests)                                    │    │
│  │  • User registration/login     • Product catalog                     │    │
│  │  • Shopping cart               • Checkout process                    │    │
│  │  • Payment integration         • Subscription management             │    │
│  │  • Order management            • Loyalty program                     │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │  B2B PORTAL (20 Tests)                                               │    │
│  │  • Partner registration        • Bulk ordering                       │    │
│  │  • Credit management           • RFQ workflows                       │    │
│  │  • Contract pricing            • Invoice management                  │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │  FARM MANAGEMENT (20 Tests)                                          │    │
│  │  • Herd management             • Milk production tracking            │    │
│  │  • Health records              • Feed management                     │    │
│  │  • Reproduction tracking       • Task management                     │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │  ERP CORE MODULES (25 Tests)                                         │    │
│  │  • Inventory management        • Sales & CRM                         │    │
│  │  • Purchase & SCM              • Accounting & Finance                │    │
│  │  • Manufacturing MRP           • HR & Payroll                        │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │  MOBILE APPLICATIONS (15 Tests)                                      │    │
│  │  • Customer mobile app         • Field staff app                     │    │
│  │  • Offline functionality       • Push notifications                  │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │  API LAYER (20 Tests)                                                │    │
│  │  • REST API endpoints          • Authentication & authorization      │    │
│  │  • Rate limiting               • Error handling                      │    │
│  │  • API versioning              • Webhook handling                    │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │  INTEGRATIONS (10 Tests)                                             │    │
│  │  • Payment gateways (bKash, Nagad)   • SMS/Email gateways            │    │
│  │  • IoT sensor integration            • Third-party logistics         │    │
│  │  • External accounting systems       • Social media APIs             │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Out-of-Scope Components

| Component | Reason | Future Consideration |
|-----------|--------|---------------------|
| Third-party SaaS services (Google Analytics) | External system responsibility | Monitor integration only |
| Legacy system interfaces | Deprecated | Migration testing only |
| Hardware/IoT device firmware | Separate hardware testing | Integration verification only |
| Browser extensions | Not supported | Feature-specific only |
| Beta features under development | Not yet stable | After feature completion |
| Load balancer configuration | Infrastructure responsibility | Integration testing only |

### 2.3 Test Environment Scope

| Environment | Regression Scope | Execution Frequency |
|-------------|-----------------|---------------------|
| Development | Smoke tests only | Per commit |
| SIT | Full regression suite | Daily |
| UAT | Critical path + selected full | Pre-release |
| Staging | Complete regression suite | Pre-production |
| Production | Smoke tests (post-deployment) | Post-release |

### 2.4 Test Data Requirements

| Data Category | Volume | Refresh Frequency | Source |
|---------------|--------|-------------------|--------|
| Master Data | 1000+ records | Weekly | Production clone (anonymized) |
| Transaction Data | 10,000+ records | Daily | Generated test data |
| User Accounts | 50 accounts | Per release | Synthetic users |
| Product Catalog | All products | On catalog update | Production snapshot |
| Farm Records | 500+ cattle records | Weekly | Anonymized production |

---

## 3. TEST CASE SELECTION CRITERIA

### 3.1 Risk-Based Selection Criteria

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    RISK-BASED TEST SELECTION MATRIX                          │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  RISK FACTORS                          WEIGHT    MEASUREMENT                 │
│  ─────────────────────────────────────────────────────────────────────────  │
│  Business Criticality                  25%      Impact on revenue/operations│
│  Code Change Frequency                 20%      How often component changes │
│  Historical Defect Density             20%      Defects per module          │
│  Technical Complexity                  15%      Cyclomatic complexity       │
│  User Usage Frequency                  15%      Daily active user impact    │
│  Integration Dependencies              5%       Number of connected systems│
│                                                                              │
│  RISK SCORE = Σ(Factor × Weight)                                             │
│                                                                              │
│  RISK LEVELS:                                                               │
│  • HIGH RISK (≥ 80): Include all related test cases                         │
│  • MEDIUM RISK (50-79): Include critical and high priority tests           │
│  • LOW RISK (< 50): Include smoke tests only                                │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Coverage-Based Selection Criteria

| Coverage Type | Target Coverage | Selection Method |
|--------------|-----------------|------------------|
| Requirements Coverage | 100% of critical requirements | Traceability matrix mapping |
| Code Coverage | ≥ 80% for critical modules | Static analysis tools |
| Feature Coverage | 100% of core features | Feature flag validation |
| User Flow Coverage | 100% of critical user journeys | User story mapping |
| API Coverage | 100% of public APIs | OpenAPI/Swagger validation |
| Integration Coverage | 100% of integration points | Dependency mapping |

### 3.3 Test Case Priority Matrix

| Priority | Selection Criteria | Execution Trigger | Percentage of Suite |
|----------|-------------------|-------------------|---------------------|
| **P0 - Critical** | Core business functions, cannot fail | Every build, every deployment | 20% |
| **P1 - High** | Important features, major user impact | Daily regression | 30% |
| **P2 - Medium** | Standard features, moderate impact | Weekly regression | 35% |
| **P3 - Low** | Edge cases, minor features | Release regression only | 15% |

### 3.4 Change Impact Analysis

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      CHANGE IMPACT ANALYSIS FLOW                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐    ┌──────────┐  │
│  │ Code Change  │───▶│ Impact       │───▶│ Select Tests │───▶│ Execute  │  │
│  │ Identified   │    │ Assessment   │    │ Based on     │    │ Selected │  │
│  └──────────────┘    └──────────────┘    └──────────────┘    └──────────┘  │
│         │                  │                                              │
│         ▼                  ▼                                              │
│  ┌──────────────┐    ┌──────────────┐                                     │
│  │ Files        │    │ Dependencies │                                     │
│  │ Modified     │    │ Identified   │                                     │
│  └──────────────┘    └──────────────┘                                     │
│                                                                              │
│  IMPACT LEVELS:                                                             │
│  • Direct Impact: Test the modified component directly                      │
│  • Upstream Impact: Test components that call the modified code             │
│  • Downstream Impact: Test components called by modified code               │
│  • Integration Impact: Test all integration points                          │
│  • Full Impact: Complete regression suite                                   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.5 Historical Defect Analysis

| Module | Defect Density | High-Risk Areas | Regression Focus |
|--------|---------------|-----------------|------------------|
| Payment Processing | High | bKash integration, refund flow | Full regression every change |
| Order Management | Medium | Inventory sync, status updates | Daily regression |
| User Authentication | Medium | Session management, OAuth | Daily regression |
| Product Catalog | Low | Search, filtering | Weekly regression |
| Farm Data Entry | Medium | Mobile sync, offline mode | Daily regression |
| Report Generation | Low | Scheduled reports, exports | Weekly regression |

---

## 4. REGRESSION TEST CATEGORIES

### 4.1 Critical Path Tests (Smoke Tests)

**Purpose:** Quick validation that the system is stable enough for further testing
**Execution Time:** < 15 minutes
**Frequency:** Every build/deployment

#### 4.1.1 Smoke Test Suite - Public Website (5 Tests)

| Test ID | Test Case | Expected Result | Priority |
|---------|-----------|-----------------|----------|
| SMK-WEB-001 | Homepage loads successfully | Page loads in < 3 seconds, no errors | P0 |
| SMK-WEB-002 | Navigation menu functional | All main links accessible | P0 |
| SMK-WEB-003 | Product catalog accessible | Product listing displays | P0 |
| SMK-WEB-004 | Contact page functional | Form loads, validation works | P0 |
| SMK-WEB-005 | Language switch works | EN/BN toggle functional | P0 |

#### 4.1.2 Smoke Test Suite - B2C Portal (8 Tests)

| Test ID | Test Case | Expected Result | Priority |
|---------|-----------|-----------------|----------|
| SMK-B2C-001 | User login functional | Authentication successful | P0 |
| SMK-B2C-002 | Product search works | Returns relevant results | P0 |
| SMK-B2C-003 | Add to cart functional | Item added successfully | P0 |
| SMK-B2C-004 | Checkout process starts | Checkout page loads | P0 |
| SMK-B2C-005 | Payment gateway loads | bKash/Nagad options visible | P0 |
| SMK-B2C-006 | User profile accessible | Profile page loads | P0 |
| SMK-B2C-007 | Order history displays | Past orders visible | P0 |
| SMK-B2C-008 | Subscription management loads | Subscription page accessible | P0 |

#### 4.1.3 Smoke Test Suite - ERP Core (10 Tests)

| Test ID | Test Case | Expected Result | Priority |
|---------|-----------|-----------------|----------|
| SMK-ERP-001 | ERP login functional | Admin access granted | P0 |
| SMK-ERP-002 | Dashboard loads | KPIs and charts display | P0 |
| SMK-ERP-003 | Inventory lookup works | Stock levels accessible | P0 |
| SMK-ERP-004 | Sales order creation | Order created successfully | P0 |
| SMK-ERP-005 | Purchase order creation | PO created successfully | P0 |
| SMK-ERP-006 | Accounting journal entry | Entry posted successfully | P0 |
| SMK-ERP-007 | Report generation | Report generates in < 30s | P0 |
| SMK-ERP-008 | User management | User list accessible | P0 |
| SMK-ERP-009 | Backup system check | Last backup < 24h old | P0 |
| SMK-ERP-010 | API health check | All critical APIs respond 200 | P0 |

#### 4.1.4 Smoke Test Suite - Mobile Apps (5 Tests)

| Test ID | Test Case | Expected Result | Priority |
|---------|-----------|-----------------|----------|
| SMK-MOB-001 | App launches successfully | Splash screen, home loads | P0 |
| SMK-MOB-002 | Login functional | Authentication works | P0 |
| SMK-MOB-003 | Product catalog loads | Grid displays products | P0 |
| SMK-MOB-004 | Cart functionality | Add/remove items works | P0 |
| SMK-MOB-005 | Push notifications | Notification received | P0 |

#### 4.1.5 Smoke Test Automation Script (Cypress)

```javascript
// cypress/e2e/smoke/smoke-test-suite.cy.js
// Smart Dairy Smoke Test Suite

describe('Smart Dairy - Smoke Test Suite', () => {
  
  beforeEach(() => {
    // Reset state before each test
    cy.clearCookies();
    cy.clearLocalStorage();
  });

  describe('Public Website Smoke Tests', () => {
    it('SMK-WEB-001: Homepage loads successfully', () => {
      cy.visit('https://smartdairybd.com', { timeout: 10000 });
      cy.get('header').should('be.visible');
      cy.get('footer').should('be.visible');
      cy.get('.hero-section').should('be.visible');
      cy.window().its('performance.timing.loadEventEnd').should('be.lessThan', 3000);
    });

    it('SMK-WEB-002: Navigation menu functional', () => {
      cy.visit('https://smartdairybd.com');
      const navLinks = ['Home', 'About', 'Products', 'Contact'];
      navLinks.forEach(link => {
        cy.get('nav').contains(link).should('be.visible').and('have.attr', 'href');
      });
    });

    it('SMK-WEB-003: Product catalog accessible', () => {
      cy.visit('https://smartdairybd.com/products');
      cy.get('.product-grid').should('be.visible');
      cy.get('.product-card').should('have.length.at.least', 1);
    });
  });

  describe('B2C Portal Smoke Tests', () => {
    it('SMK-B2C-001: User login functional', () => {
      cy.visit('https://shop.smartdairybd.com/login');
      cy.get('input[name="email"]').type(Cypress.env('TEST_USER_EMAIL'));
      cy.get('input[name="password"]').type(Cypress.env('TEST_USER_PASSWORD'));
      cy.get('button[type="submit"]').click();
      cy.url().should('include', '/dashboard');
      cy.get('.user-profile').should('be.visible');
    });

    it('SMK-B2C-002: Product search works', () => {
      cy.loginAsCustomer(); // Custom command
      cy.get('input[name="search"]').type('milk{enter}');
      cy.get('.search-results').should('be.visible');
      cy.get('.product-card').should('have.length.at.least', 1);
    });

    it('SMK-B2C-003: Add to cart functional', () => {
      cy.loginAsCustomer();
      cy.visit('https://shop.smartdairybd.com/products/fresh-milk');
      cy.get('button[data-testid="add-to-cart"]').click();
      cy.get('.cart-badge').should('contain', '1');
      cy.get('.toast-notification').should('contain', 'Added to cart');
    });
  });

  describe('ERP Core Smoke Tests', () => {
    it('SMK-ERP-001: ERP login functional', () => {
      cy.visit('https://erp.smartdairybd.com/web/login');
      cy.get('input[name="login"]').type(Cypress.env('ERP_ADMIN_USER'));
      cy.get('input[name="password"]').type(Cypress.env('ERP_ADMIN_PASS'));
      cy.get('.oe_login_button').click();
      cy.get('.o_main_navbar').should('be.visible');
      cy.get('.o_apps_menu').should('be.visible');
    });

    it('SMK-ERP-003: Inventory lookup works', () => {
      cy.loginAsERPUser();
      cy.visit('https://erp.smartdairybd.com/web#action=stock.action_product_stock_view');
      cy.get('.o_list_view').should('be.visible');
      cy.get('.o_data_row').should('have.length.at.least', 1);
    });
  });

  describe('API Smoke Tests', () => {
    it('SMK-API-001: Health check endpoints respond', () => {
      cy.request('GET', 'https://api.smartdairybd.com/health')
        .its('status').should('eq', 200);
      cy.request('GET', 'https://api.smartdairybd.com/health/db')
        .its('body.status').should('eq', 'healthy');
    });
  });
});
```

### 4.2 Functional Regression (By Module)

#### 4.2.1 Public Website Regression (15 Tests)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│              PUBLIC WEBSITE - FUNCTIONAL REGRESSION SUITE                    │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  MODULE: Content Management & Display                                        │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-WEB-001: Homepage hero carousel rotation and CTAs                      │
│  REG-WEB-002: Product category navigation and filtering                     │
│  REG-WEB-003: Company information pages (About, Leadership)                 │
│  REG-WEB-004: Farm virtual tour gallery and video playback                  │
│  REG-WEB-005: News and blog post listing and detail pages                   │
│                                                                              │
│  MODULE: Interactive Features                                                │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-WEB-006: Store locator with Google Maps integration                    │
│  REG-WEB-007: Contact forms with validation and CAPTCHA                     │
│  REG-WEB-008: Newsletter subscription with double opt-in                    │
│                                                                              │
│  MODULE: Multi-language & Localization                                       │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-WEB-009: English to Bengali language switch                            │
│  REG-WEB-010: Bengali font rendering and encoding                           │
│  REG-WEB-011: RTL layout verification (if applicable)                       │
│                                                                              │
│  MODULE: Technical Features                                                  │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-WEB-012: SEO meta tags and structured data                             │
│  REG-WEB-013: XML sitemap and robots.txt                                    │
│  REG-WEB-014: Social media sharing integration                              │
│  REG-WEB-015: Page load performance and Core Web Vitals                     │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```


#### 4.2.2 B2C Portal Regression (25 Tests)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                  B2C PORTAL - FUNCTIONAL REGRESSION SUITE                    │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  MODULE: User Authentication & Account                                       │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-B2C-001: User registration with email verification                     │
│  REG-B2C-002: Login with valid/invalid credentials                          │
│  REG-B2C-003: Password reset flow                                           │
│  REG-B2C-004: Social login (Google, Facebook)                               │
│  REG-B2C-005: Profile update and avatar upload                              │
│  REG-B2C-006: Address management (add, edit, delete)                        │
│                                                                              │
│  MODULE: Product Catalog                                                     │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-B2C-007: Category browsing and navigation                              │
│  REG-B2C-008: Product filtering by price, category, attributes              │
│  REG-B2C-009: Product search with autocomplete                              │
│  REG-B2C-010: Product detail page with variants                             │
│  REG-B2C-011: Product reviews and ratings                                   │
│                                                                              │
│  MODULE: Shopping Cart                                                       │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-B2C-012: Add products to cart                                          │
│  REG-B2C-013: Update cart quantities                                        │
│  REG-B2C-014: Remove items from cart                                        │
│  REG-B2C-015: Cart persistence across sessions                              │
│  REG-B2C-016: Apply and remove promo codes                                  │
│                                                                              │
│  MODULE: Checkout & Payment                                                  │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-B2C-017: Guest checkout flow                                           │
│  REG-B2C-018: Registered user checkout                                      │
│  REG-B2C-019: Multiple shipping address selection                           │
│  REG-B2C-020: bKash payment integration                                     │
│  REG-B2C-021: Nagad payment integration                                     │
│  REG-B2C-022: Cash on delivery option                                       │
│                                                                              │
│  MODULE: Subscription & Orders                                               │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-B2C-023: Daily milk subscription setup                                 │
│  REG-B2C-024: Subscription pause/resume/modify                              │
│  REG-B2C-025: Order history and invoice download                            │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### 4.2.3 B2B Portal Regression (20 Tests)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                   B2B PORTAL - FUNCTIONAL REGRESSION SUITE                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  MODULE: Partner Management                                                  │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-B2B-001: Partner registration with approval workflow                   │
│  REG-B2B-002: Partner tier assignment (Distributor, Retailer, HORECA)       │
│  REG-B2B-003: Company profile management                                    │
│  REG-B2B-004: Multiple user accounts per partner                            │
│                                                                              │
│  MODULE: Bulk Ordering                                                       │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-B2B-005: Quick order form functionality                                │
│  REG-B2B-006: CSV order upload and validation                               │
│  REG-B2B-007: Reorder from order history                                    │
│  REG-B2B-008: Order template creation and usage                             │
│  REG-B2B-009: Bulk pricing and volume discounts                             │
│                                                                              │
│  MODULE: Credit & Financial                                                  │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-B2B-010: Credit limit management                                       │
│  REG-B2B-011: Payment terms configuration                                   │
│  REG-B2B-012: Outstanding balance display                                   │
│  REG-B2B-013: Invoice download and statement of accounts                    │
│  REG-B2B-014: Credit note application                                       │
│                                                                              │
│  MODULE: RFQ & Contracts                                                     │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-B2B-015: Request for Quotation submission                              │
│  REG-B2B-016: Quote acceptance/rejection                                    │
│  REG-B2B-017: Contract pricing view and acceptance                          │
│  REG-B2B-018: Delivery schedule management                                  │
│                                                                              │
│  MODULE: API & Integration                                                   │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-B2B-019: API key generation and authentication                         │
│  REG-B2B-020: Webhook event handling                                        │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### 4.2.4 Farm Management Regression (20 Tests)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                FARM MANAGEMENT - FUNCTIONAL REGRESSION SUITE                 │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  MODULE: Herd Management                                                     │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-FARM-001: Cattle profile creation and management                       │
│  REG-FARM-002: RFID/ear tag assignment and scanning                         │
│  REG-FARM-003: Lineage and breeding history tracking                        │
│  REG-FARM-004: Cattle classification and group management                   │
│                                                                              │
│  MODULE: Health & Reproduction                                               │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-FARM-005: Vaccination schedule management                              │
│  REG-FARM-006: Treatment records and medication tracking                    │
│  REG-FARM-007: Breeding records and pregnancy tracking                      │
│  REG-FARM-008: Calving alerts and history                                   │
│  REG-FARM-009: Veterinary visit scheduling                                  │
│                                                                              │
│  MODULE: Milk Production                                                     │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-FARM-010: Daily milk yield recording                                   │
│  REG-FARM-011: Milk quality parameter entry (Fat, SNF)                      │
│  REG-FARM-012: Production trend analysis and reporting                      │
│  REG-FARM-013: Milk collection center integration                           │
│                                                                              │
│  MODULE: Feed & Inventory                                                    │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-FARM-014: Ration planning and feed allocation                          │
│  REG-FARM-015: Feed inventory and consumption tracking                      │
│  REG-FARM-016: Medicine inventory with expiry alerts                        │
│                                                                              │
│  MODULE: Operations                                                          │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-FARM-017: Daily task assignment and tracking                           │
│  REG-FARM-018: Biogas plant monitoring                                      │
│  REG-FARM-019: Equipment maintenance schedules                              │
│  REG-FARM-020: Farm reports and analytics dashboard                         │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### 4.2.5 ERP Core Regression (25 Tests)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                   ERP CORE - FUNCTIONAL REGRESSION SUITE                     │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  MODULE: Inventory Management                                                │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-ERP-001: Multi-location inventory tracking                             │
│  REG-ERP-002: Batch/lot tracking and FEFO/FIFO                            │
│  REG-ERP-003: Barcode/RFID scanning integration                             │
│  REG-ERP-004: Stock valuation and adjustment                                │
│  REG-ERP-005: Reorder point and automatic replenishment                     │
│                                                                              │
│  MODULE: Sales & CRM                                                         │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-ERP-006: Lead management and opportunity tracking                      │
│  REG-ERP-007: Quotation generation and approval                             │
│  REG-ERP-008: Sales order processing                                        │
│  REG-ERP-009: Delivery scheduling and tracking                              │
│  REG-ERP-010: Customer portal integration                                   │
│                                                                              │
│  MODULE: Purchase & SCM                                                      │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-ERP-011: Supplier management and evaluation                            │
│  REG-ERP-012: RFQ creation and comparison                                   │
│  REG-ERP-013: Purchase order processing                                     │
│  REG-ERP-014: Goods receipt and quality check                               │
│  REG-ERP-015: Three-way matching (PO-GR-Invoice)                            │
│                                                                              │
│  MODULE: Accounting & Finance                                                │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-ERP-016: General ledger transactions                                   │
│  REG-ERP-017: Accounts payable processing                                   │
│  REG-ERP-018: Accounts receivable and collections                           │
│  REG-ERP-019: Bank reconciliation                                           │
│  REG-ERP-020: Bangladesh VAT/GST compliance                                 │
│  REG-ERP-021: Financial reporting (P&L, Balance Sheet)                      │
│                                                                              │
│  MODULE: Manufacturing & Quality                                             │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-ERP-022: Bill of Materials (BOM) management                            │
│  REG-ERP-023: Production planning and work orders                           │
│  REG-ERP-024: Quality control checkpoints                                   │
│  REG-ERP-025: Yield tracking and analysis                                   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 4.3 Integration Regression

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                  INTEGRATION REGRESSION SUITE                                │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  REG-INT-001: Portal to ERP data synchronization                            │
│  REG-INT-002: B2C order to ERP sales order flow                             │
│  REG-INT-003: Inventory sync between portals and ERP                        │
│  REG-INT-004: Customer data sync (CRM to all portals)                       │
│  REG-INT-005: Payment gateway transaction reconciliation                    │
│  REG-INT-006: SMS gateway notification delivery                             │
│  REG-INT-007: Email service integration                                     │
│  REG-INT-008: Farm data to ERP inventory update                             │
│  REG-INT-009: Mobile app to backend API communication                       │
│  REG-INT-010: IoT sensor data ingestion pipeline                            │
│  REG-INT-011: Third-party logistics order tracking                          │
│  REG-INT-012: Accounting system bank feed integration                       │
│  REG-INT-013: Social media API connectivity                                 │
│  REG-INT-014: Google Maps API for store locator                             │
│  REG-INT-015: Webhook event handling and retry logic                        │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 4.4 UI Regression (Cross-Browser)

#### 4.4.1 Browser Matrix

| Browser | Version | Platform | Priority | Test Coverage |
|---------|---------|----------|----------|---------------|
| Chrome | Latest | Windows, macOS, Android | Critical | Full regression |
| Chrome | Latest-1 | Windows, macOS | Critical | Smoke + Critical |
| Firefox | Latest | Windows, macOS | Critical | Full regression |
| Safari | Latest | macOS, iOS | Critical | Full regression |
| Edge | Latest | Windows | High | Smoke + Critical |
| Samsung Internet | Latest | Android | Medium | Smoke tests |

#### 4.4.2 Cross-Browser Test Suite

```javascript
// cypress/e2e/ui-regression/cross-browser-tests.cy.js
// Cross-Browser UI Regression Tests

describe('Cross-Browser UI Regression', () => {
  const viewports = [
    { name: 'Desktop 1920x1080', width: 1920, height: 1080 },
    { name: 'Laptop 1366x768', width: 1366, height: 768 },
    { name: 'Tablet 768x1024', width: 768, height: 1024 },
    { name: 'Mobile 375x812', width: 375, height: 812 }
  ];

  viewports.forEach(viewport => {
    context(`Viewport: ${viewport.name}`, () => {
      beforeEach(() => {
        cy.viewport(viewport.width, viewport.height);
      });

      it(`REG-UI-001: Layout renders correctly at ${viewport.name}`, () => {
        cy.visit('https://smartdairybd.com');
        cy.get('header').should('be.visible');
        cy.get('.hero-section').should('be.visible');
        cy.get('footer').should('be.visible');
        
        // Check for horizontal scroll
        cy.window().then(win => {
          expect(win.document.documentElement.scrollWidth).to.equal(
            win.document.documentElement.clientWidth
          );
        });
      });

      it(`REG-UI-002: Navigation functions at ${viewport.name}`, () => {
        cy.visit('https://smartdairybd.com');
        
        if (viewport.width < 768) {
          // Mobile hamburger menu
          cy.get('.hamburger-menu').click();
          cy.get('.mobile-nav').should('be.visible');
          cy.get('.mobile-nav a').first().click();
        } else {
          // Desktop navigation
          cy.get('nav a').first().click();
        }
        
        cy.url().should('not.eq', 'https://smartdairybd.com/');
      });

      it(`REG-UI-003: Forms render and validate at ${viewport.name}`, () => {
        cy.visit('https://smartdairybd.com/contact');
        cy.get('form').should('be.visible');
        cy.get('input[type="email"]').type('invalid-email');
        cy.get('button[type="submit"]').click();
        cy.get('.error-message').should('be.visible');
      });
    });
  });

  describe('Visual Regression', () => {
    it('REG-UI-004: Homepage visual consistency', () => {
      cy.visit('https://smartdairybd.com');
      cy.eyesOpen({
        appName: 'Smart Dairy Website',
        testName: 'Homepage Visual Regression'
      });
      cy.eyesCheckWindow('Homepage');
      cy.eyesClose();
    });
  });
});
```

### 4.5 Mobile App Regression (15 Tests)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    MOBILE APP REGRESSION SUITE                               │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  PLATFORM: iOS & Android (Customer App)                                      │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-MOB-001: App launch and splash screen                                  │
│  REG-MOB-002: Login with biometric (Face ID/Touch ID/Fingerprint)           │
│  REG-MOB-003: Product catalog browsing with pull-to-refresh                 │
│  REG-MOB-004: Product search with voice input                               │
│  REG-MOB-005: Shopping cart management                                      │
│  REG-MOB-006: Checkout with mobile payment (bKash/Nagad apps)               │
│  REG-MOB-007: Push notification handling (foreground/background)            │
│  REG-MOB-008: Deep linking from external sources                            │
│  REG-MOB-009: Offline mode functionality                                    │
│  REG-MOB-010: Order tracking with map integration                           │
│                                                                              │
│  PLATFORM: iOS & Android (Field Staff App)                                   │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-MOB-011: Daily milk collection entry with offline sync                 │
│  REG-MOB-012: Cattle health check data entry                                │
│  REG-MOB-013: Task completion and photo upload                              │
│  REG-MOB-014: GPS location capture for farm visits                          │
│  REG-MOB-015: Background data synchronization                               │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 4.6 API Regression (20 Tests)

#### 4.6.1 API Regression Test Suite

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                     API REGRESSION SUITE                                     │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  MODULE: Authentication & Authorization                                      │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-API-001: OAuth2 token generation and validation                        │
│  REG-API-002: JWT token refresh mechanism                                   │
│  REG-API-003: API key authentication for B2B partners                       │
│  REG-API-004: Role-based access control enforcement                         │
│                                                                              │
│  MODULE: Customer APIs                                                       │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-API-005: Customer registration and profile management                  │
│  REG-API-006: Address CRUD operations                                       │
│  REG-API-007: Order history and status tracking                             │
│  REG-API-008: Subscription management endpoints                             │
│                                                                              │
│  MODULE: Product & Catalog APIs                                              │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-API-009: Product listing with filtering and pagination                 │
│  REG-API-010: Product detail with variants and stock                        │
│  REG-API-011: Category tree and navigation                                  │
│  REG-API-012: Search with autocomplete and suggestions                      │
│                                                                              │
│  MODULE: Order & Payment APIs                                                │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-API-013: Cart management endpoints                                     │
│  REG-API-014: Checkout and order creation                                   │
│  REG-API-015: Payment initiation and callback handling                      │
│  REG-API-016: Order cancellation and refund processing                      │
│                                                                              │
│  MODULE: Farm Management APIs                                                │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-API-017: Cattle data CRUD operations                                   │
│  REG-API-018: Milk production data submission                               │
│  REG-API-019: Health record entry and retrieval                             │
│                                                                              │
│  MODULE: System & Utility APIs                                               │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-API-020: Health check and system status endpoints                      │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### 4.6.2 Postman Collection for API Regression

```json
{
  "info": {
    "_postman_id": "smart-dairy-regression-api",
    "name": "Smart Dairy - API Regression Suite",
    "description": "Complete API regression test collection for Smart Dairy",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Authentication",
      "item": [
        {
          "name": "REG-API-001: OAuth2 Token Generation",
          "request": {
            "method": "POST",
            "header": [],
            "url": {
              "raw": "{{base_url}}/oauth/token",
              "host": ["{{base_url}}"],
              "path": ["oauth", "token"]
            },
            "body": {
              "mode": "urlencoded",
              "urlencoded": [
                {"key": "grant_type", "value": "password"},
                {"key": "username", "value": "{{test_user}}"},
                {"key": "password", "value": "{{test_password}}"},
                {"key": "client_id", "value": "{{client_id}}"},
                {"key": "client_secret", "value": "{{client_secret}}"}
              ]
            }
          },
          "event": [
            {
              "listen": "test",
              "script": {
                "exec": [
                  "pm.test('Status code is 200', function () {",
                  "    pm.response.to.have.status(200);",
                  "});",
                  "",
                  "pm.test('Response has access_token', function () {",
                  "    var jsonData = pm.response.json();",
                  "    pm.expect(jsonData).to.have.property('access_token');",
                  "    pm.environment.set('access_token', jsonData.access_token);",
                  "});",
                  "",
                  "pm.test('Token type is Bearer', function () {",
                  "    var jsonData = pm.response.json();",
                  "    pm.expect(jsonData.token_type).to.eql('Bearer');",
                  "});",
                  "",
                  "pm.test('Expires in is present', function () {",
                  "    var jsonData = pm.response.json();",
                  "    pm.expect(jsonData.expires_in).to.be.a('number');",
                  "    pm.expect(jsonData.expires_in).to.be.greaterThan(0);",
                  "});"
                ]
              }
            }
          ]
        },
        {
          "name": "REG-API-004: RBAC Enforcement",
          "request": {
            "method": "GET",
            "header": [
              {"key": "Authorization", "value": "Bearer {{customer_token}}"}
            ],
            "url": {
              "raw": "{{base_url}}/api/admin/users",
              "host": ["{{base_url}}"],
              "path": ["api", "admin", "users"]
            }
          },
          "event": [
            {
              "listen": "test",
              "script": {
                "exec": [
                  "pm.test('Customer cannot access admin endpoints', function () {",
                  "    pm.response.to.have.status(403);",
                  "});"
                ]
              }
            }
          ]
        }
      ]
    },
    {
      "name": "Product Catalog",
      "item": [
        {
          "name": "REG-API-009: Product Listing with Pagination",
          "request": {
            "method": "GET",
            "header": [
              {"key": "Authorization", "value": "Bearer {{access_token}}"}
            ],
            "url": {
              "raw": "{{base_url}}/api/products?page=1&limit=20&category=fresh-milk",
              "host": ["{{base_url}}"],
              "path": ["api", "products"],
              "query": [
                {"key": "page", "value": "1"},
                {"key": "limit", "value": "20"},
                {"key": "category", "value": "fresh-milk"}
              ]
            }
          },
          "event": [
            {
              "listen": "test",
              "script": {
                "exec": [
                  "pm.test('Status code is 200', function () {",
                  "    pm.response.to.have.status(200);",
                  "});",
                  "",
                  "pm.test('Response has pagination info', function () {",
                  "    var jsonData = pm.response.json();",
                  "    pm.expect(jsonData).to.have.property('data');",
                  "    pm.expect(jsonData).to.have.property('pagination');",
                  "    pm.expect(jsonData.pagination).to.have.property('current_page');",
                  "    pm.expect(jsonData.pagination).to.have.property('total_pages');",
                  "    pm.expect(jsonData.pagination).to.have.property('total_items');",
                  "});",
                  "",
                  "pm.test('Products have required fields', function () {",
                  "    var jsonData = pm.response.json();",
                  "    if (jsonData.data.length > 0) {",
                  "        var product = jsonData.data[0];",
                  "        pm.expect(product).to.have.property('id');",
                  "        pm.expect(product).to.have.property('name');",
                  "        pm.expect(product).to.have.property('price');",
                  "        pm.expect(product).to.have.property('stock_quantity');",
                  "    }",
                  "});"
                ]
              }
            }
          ]
        }
      ]
    },
    {
      "name": "Health Checks",
      "item": [
        {
          "name": "REG-API-020: System Health Check",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{base_url}}/health",
              "host": ["{{base_url}}"],
              "path": ["health"]
            }
          },
          "event": [
            {
              "listen": "test",
              "script": {
                "exec": [
                  "pm.test('Health endpoint returns 200', function () {",
                  "    pm.response.to.have.status(200);",
                  "});",
                  "",
                  "pm.test('Status is healthy', function () {",
                  "    var jsonData = pm.response.json();",
                  "    pm.expect(jsonData.status).to.eql('healthy');",
                  "});",
                  "",
                  "pm.test('Response time is acceptable', function () {",
                  "    pm.expect(pm.response.responseTime).to.be.below(500);",
                  "});"
                ]
              }
            }
          ]
        }
      ]
    }
  ]
}
```

### 4.7 Performance Regression

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                  PERFORMANCE REGRESSION SUITE                                │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  MODULE: Load Testing Baselines                                              │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-PERF-001: Homepage load under 100 concurrent users                     │
│  REG-PERF-002: Product catalog with 500 items under load                    │
│  REG-PERF-003: Checkout flow with 50 concurrent checkouts                   │
│  REG-PERF-004: API response times under normal load                         │
│  REG-PERF-005: Database query performance (p95 < 100ms)                     │
│                                                                              │
│  MODULE: Stress Testing                                                      │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-PERF-006: System behavior at 2x expected peak load                     │
│  REG-PERF-007: System behavior at 3x expected peak load                     │
│  REG-PERF-008: Recovery after overload scenario                             │
│                                                                              │
│  MODULE: Endurance Testing                                                   │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-PERF-009: 4-hour sustained load test                                   │
│  REG-PERF-010: Memory leak detection over extended period                   │
│  REG-PERF-011: Database connection pool stability                           │
│                                                                              │
│  MODULE: Core Web Vitals                                                     │
│  ─────────────────────────────────────────────────────────────────────────  │
│  REG-PERF-012: Largest Contentful Paint (LCP) < 2.5s                        │
│  REG-PERF-013: First Input Delay (FID) < 100ms                              │
│  REG-PERF-014: Cumulative Layout Shift (CLS) < 0.1                          │
│  REG-PERF-015: Time to First Byte (TTFB) < 600ms                            │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### 4.7.1 k6 Performance Test Script

```javascript
// performance-regression-test.js
// k6 Performance Regression Test Script

import http from 'k6/http';
import { check, sleep, group } from 'k6';
import { Rate, Trend } from 'k6/metrics';

// Custom metrics
const errorRate = new Rate('errors');
const apiResponseTime = new Trend('api_response_time');
const pageLoadTime = new Trend('page_load_time');

// Test configuration
export const options = {
  scenarios: {
    smoke: {
      executor: 'shared-iterations',
      vus: 1,
      iterations: 1,
      exec: 'smokeTest',
      tags: { test_type: 'smoke' }
    },
    load: {
      executor: 'ramping-vus',
      startVUs: 0,
      stages: [
        { duration: '2m', target: 50 },   // Ramp up
        { duration: '5m', target: 50 },   // Steady state
        { duration: '2m', target: 100 },  // Peak load
        { duration: '5m', target: 100 },  // Sustained peak
        { duration: '2m', target: 0 },    // Ramp down
      ],
      exec: 'loadTest',
      tags: { test_type: 'load' }
    },
    stress: {
      executor: 'ramping-vus',
      startVUs: 0,
      stages: [
        { duration: '2m', target: 100 },
        { duration: '2m', target: 200 },
        { duration: '2m', target: 300 },
        { duration: '5m', target: 300 },
        { duration: '2m', target: 0 },
      ],
      exec: 'stressTest',
      tags: { test_type: 'stress' }
    }
  },
  thresholds: {
    http_req_duration: ['p(95)<500'],     // 95% of requests under 500ms
    http_req_failed: ['rate<0.01'],        // Error rate under 1%
    errors: ['rate<0.05'],                 // Custom error rate under 5%
  }
};

const BASE_URL = __ENV.BASE_URL || 'https://smartdairybd.com';
const API_URL = __ENV.API_URL || 'https://api.smartdairybd.com';

// Helper function to check response
function checkResponse(response, checks) {
  const result = check(response, checks);
  errorRate.add(!result);
  return result;
}

// Smoke Test - Quick validation
export function smokeTest() {
  group('Smoke Tests', () => {
    // Homepage
    const homepage = http.get(`${BASE_URL}/`);
    checkResponse(homepage, {
      'homepage status is 200': (r) => r.status === 200,
      'homepage loads under 3s': (r) => r.timings.duration < 3000,
    });
    pageLoadTime.add(homepage.timings.duration);

    // API Health
    const health = http.get(`${API_URL}/health`);
    checkResponse(health, {
      'health check status is 200': (r) => r.status === 200,
      'health check response is healthy': (r) => r.json('status') === 'healthy',
    });
    apiResponseTime.add(health.timings.duration);
  });
}

// Load Test - Normal operations
export function loadTest() {
  group('B2C User Journey', () => {
    // Browse products
    const products = http.get(`${API_URL}/api/products?page=1&limit=20`);
    checkResponse(products, {
      'products API status is 200': (r) => r.status === 200,
      'products load under 500ms': (r) => r.timings.duration < 500,
      'products have data': (r) => r.json('data').length > 0,
    });
    apiResponseTime.add(products.timings.duration);

    // View product detail
    const productDetail = http.get(`${API_URL}/api/products/saffron-organic-milk`);
    checkResponse(productDetail, {
      'product detail status is 200': (r) => r.status === 200,
      'product has required fields': (r) => 
        r.json('id') && r.json('name') && r.json('price'),
    });
    apiResponseTime.add(productDetail.timings.duration);

    // Search products
    const search = http.get(`${API_URL}/api/products/search?q=milk`);
    checkResponse(search, {
      'search API status is 200': (r) => r.status === 200,
    });
    apiResponseTime.add(search.timings.duration);

    sleep(1);
  });

  group('Public Website', () => {
    const pages = ['/about', '/products', '/contact'];
    pages.forEach(page => {
      const response = http.get(`${BASE_URL}${page}`);
      checkResponse(response, {
        [`${page} page loads successfully`]: (r) => r.status === 200,
        [`${page} page loads under 3s`]: (r) => r.timings.duration < 3000,
      });
      pageLoadTime.add(response.timings.duration);
    });
  });
}

// Stress Test - System limits
export function stressTest() {
  group('High Load API Tests', () => {
    const requests = [
      { method: 'GET', url: `${API_URL}/api/products` },
      { method: 'GET', url: `${API_URL}/api/categories` },
      { method: 'GET', url: `${API_URL}/health` },
    ];

    const responses = http.batch(requests);
    
    responses.forEach((response, index) => {
      checkResponse(response, {
        [`batch request ${index} succeeds`]: (r) => r.status === 200,
      });
    });
  });

  group('Database Intensive Operations', () => {
    const search = http.get(`${API_URL}/api/products/search?q=organic&limit=100`);
    checkResponse(search, {
      'large search completes': (r) => r.status === 200,
      'large search under 2s': (r) => r.timings.duration < 2000,
    });
  });
}

// Setup and teardown
export function setup() {
  console.log(`Starting performance regression tests against: ${BASE_URL}`);
  return { startTime: Date.now() };
}

export function teardown(data) {
  const duration = (Date.now() - data.startTime) / 1000;
  console.log(`Performance regression tests completed in ${duration}s`);
}
```


---

## 5. AUTOMATION STRATEGY

### 5.1 Automation Tools & Framework

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    AUTOMATION TOOL STACK                                     │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  LAYER: UI/Web Automation                                                    │
│  ─────────────────────────────────────────────────────────────────────────  │
│  Primary Tool: Cypress (v13+)                                               │
│  Alternative: Selenium WebDriver (Python/Java)                              │
│  Use Cases:                                                                 │
│    • Public website end-to-end tests                                        │
│    • B2C/B2B portal user workflows                                          │
│    • Cross-browser regression                                               │
│    • Visual regression with Applitools                                      │
│                                                                              │
│  LAYER: API Automation                                                       │
│  ─────────────────────────────────────────────────────────────────────────  │
│  Primary Tool: Postman + Newman                                             │
│  Alternative: REST Assured (Java), pytest + requests (Python)               │
│  Use Cases:                                                                 │
│    • REST API endpoint validation                                           │
│    • Integration contract testing                                           │
│    • Performance API testing                                                │
│    • Webhook validation                                                     │
│                                                                              │
│  LAYER: Performance Testing                                                  │
│  ─────────────────────────────────────────────────────────────────────────  │
│  Primary Tool: k6 (Grafana Labs)                                            │
│  Alternative: JMeter, Gatling                                               │
│  Use Cases:                                                                 │
│    • Load testing                                                           │
│    • Stress testing                                                         │
│    • Endurance testing                                                      │
│    • Performance regression                                                 │
│                                                                              │
│  LAYER: Mobile Automation                                                    │
│  ─────────────────────────────────────────────────────────────────────────  │
│  Primary Tool: Appium                                                       │
│  Alternative: Detox (React Native)                                          │
│  Use Cases:                                                                 │
│    • iOS app regression                                                     │
│    • Android app regression                                                 │
│    • Cross-platform mobile tests                                            │
│                                                                              │
│  LAYER: Unit/Component Testing                                               │
│  ─────────────────────────────────────────────────────────────────────────  │
│  Backend: pytest (Python) for Odoo modules                                  │
│  Frontend: Jest + React Testing Library                                     │
│  Infrastructure: Terratest (Go) for IaC                                     │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 5.2 Automation Framework Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│              SMART DAIRY AUTOMATION FRAMEWORK ARCHITECTURE                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                      TEST EXECUTION LAYER                            │    │
│  │  ┌───────────┐  ┌───────────┐  ┌───────────┐  ┌───────────┐        │    │
│  │  │  Cypress  │  │  Postman  │  │    k6     │  │  Appium   │        │    │
│  │  │   Tests   │  │   Tests   │  │   Tests   │  │   Tests   │        │    │
│  │  └───────────┘  └───────────┘  └───────────┘  └───────────┘        │    │
│  └───────────────────────────────┬─────────────────────────────────────┘    │
│                                  │                                          │
│  ┌───────────────────────────────▼─────────────────────────────────────┐    │
│  │                    CORE FRAMEWORK LAYER                              │    │
│  │  • Configuration Management    • Test Data Management                │    │
│  │  • Page Object Models          • API Wrappers                        │    │
│  │  • Utility Functions           • Assertion Libraries                 │    │
│  └───────────────────────────────┬─────────────────────────────────────┘    │
│                                  │                                          │
│  ┌───────────────────────────────▼─────────────────────────────────────┐    │
│  │                      INFRASTRUCTURE LAYER                            │    │
│  │  ┌───────────┐  ┌───────────┐  ┌───────────┐  ┌───────────┐        │    │
│  │  │  Docker   │  │  Selenium │  │  Test     │  │  Report   │        │    │
│  │  │  Grid     │  │   Grid    │  │  Data DB  │  │  Portal   │        │    │
│  │  └───────────┘  └───────────┘  └───────────┘  └───────────┘        │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                        CI/CD INTEGRATION                             │    │
│  │              GitHub Actions / Jenkins / GitLab CI                    │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 5.3 CI/CD Pipeline Configuration

#### 5.3.1 GitHub Actions Workflow

```yaml
# .github/workflows/regression-tests.yml
name: Smart Dairy Regression Test Suite

on:
  push:
    branches: [main, develop, 'release/*']
  pull_request:
    branches: [main, develop]
  schedule:
    # Run full regression daily at 2 AM UTC
    - cron: '0 2 * * *'
  workflow_dispatch:
    inputs:
      test_suite:
        description: 'Test suite to run'
        required: true
        default: 'smoke'
        type: choice
        options:
          - smoke
          - regression
          - full
          - performance

env:
  NODE_VERSION: '20'
  PYTHON_VERSION: '3.11'
  TEST_ENV: 'sit'

jobs:
  # Job 1: Smoke Tests (Fast feedback)
  smoke-tests:
    name: Smoke Tests
    runs-on: ubuntu-latest
    if: github.event_name == 'push' || github.event.inputs.test_suite == 'smoke'
    timeout-minutes: 15
    
    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup Node.js
        uses: actions/setup-node@v4
        with:
          node-version: ${{ env.NODE_VERSION }}
          cache: 'npm'

      - name: Install dependencies
        run: npm ci

      - name: Run Cypress smoke tests
        uses: cypress-io/github-action@v6
        with:
          browser: chrome
          spec: 'cypress/e2e/smoke/**/*.cy.js'
          record: true
          parallel: false
        env:
          CYPRESS_RECORD_KEY: ${{ secrets.CYPRESS_RECORD_KEY }}
          CYPRESS_TEST_USER_EMAIL: ${{ secrets.TEST_USER_EMAIL }}
          CYPRESS_TEST_USER_PASSWORD: ${{ secrets.TEST_USER_PASSWORD }}
          CYPRESS_BASE_URL: ${{ vars.SIT_BASE_URL }}

      - name: Upload smoke test results
        uses: actions/upload-artifact@v4
        if: always()
        with:
          name: smoke-test-results
          path: cypress/reports/
          retention-days: 30

  # Job 2: API Regression Tests
  api-regression:
    name: API Regression Tests
    runs-on: ubuntu-latest
    needs: smoke-tests
    if: always() && (needs.smoke-tests.result == 'success' || github.event.inputs.test_suite == 'regression')
    timeout-minutes: 30

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup Node.js
        uses: actions/setup-node@v4
        with:
          node-version: ${{ env.NODE_VERSION }}

      - name: Install Newman
        run: npm install -g newman newman-reporter-htmlextra

      - name: Run Postman API tests
        run: |
          newman run collections/smart-dairy-regression-api.json \
            --environment environments/${{ env.TEST_ENV }}.json \
            --reporters cli,htmlextra,junit \
            --reporter-htmlextra-export newman-report.html \
            --reporter-junit-export newman-report.xml \
            --timeout-request 30000

      - name: Upload API test results
        uses: actions/upload-artifact@v4
        if: always()
        with:
          name: api-test-results
          path: |
            newman-report.html
            newman-report.xml
          retention-days: 30

  # Job 3: UI Regression Tests
  ui-regression:
    name: UI Regression Tests
    runs-on: ubuntu-latest
    needs: smoke-tests
    if: always() && needs.smoke-tests.result == 'success'
    timeout-minutes: 45
    strategy:
      matrix:
        browser: [chrome, firefox, edge]
        viewport: [desktop, mobile]
      fail-fast: false

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup Node.js
        uses: actions/setup-node@v4
        with:
          node-version: ${{ env.NODE_VERSION }}

      - name: Install dependencies
        run: npm ci

      - name: Run Cypress UI tests
        uses: cypress-io/github-action@v6
        with:
          browser: ${{ matrix.browser }}
          spec: 'cypress/e2e/ui-regression/**/*.cy.js'
          config: 'viewportWidth=${{ matrix.viewport == "desktop" && 1920 || 375 }},viewportHeight=${{ matrix.viewport == "desktop" && 1080 || 812 }}'
        env:
          CYPRESS_BASE_URL: ${{ vars.SIT_BASE_URL }}

      - name: Upload UI test results
        uses: actions/upload-artifact@v4
        if: always()
        with:
          name: ui-test-results-${{ matrix.browser }}-${{ matrix.viewport }}
          path: cypress/reports/
          retention-days: 30

  # Job 4: Performance Regression Tests
  performance-tests:
    name: Performance Regression Tests
    runs-on: ubuntu-latest
    needs: smoke-tests
    if: github.event.inputs.test_suite == 'performance' || github.event_name == 'schedule'
    timeout-minutes: 60

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup k6
        uses: grafana/setup-k6-action@v1

      - name: Run k6 performance tests
        run: |
          k6 run \
            --out influxdb=http://localhost:8086/k6 \
            --env BASE_URL=${{ vars.SIT_BASE_URL }} \
            --env API_URL=${{ vars.SIT_API_URL }} \
            --env DURATION=10m \
            performance-tests/performance-regression-test.js

      - name: Upload performance results
        uses: actions/upload-artifact@v4
        if: always()
        with:
          name: performance-test-results
          path: |
            performance-results.json
            performance-summary.html
          retention-days: 90

  # Job 5: Test Results Aggregation
  aggregate-results:
    name: Aggregate Test Results
    runs-on: ubuntu-latest
    needs: [smoke-tests, api-regression, ui-regression]
    if: always()

    steps:
      - name: Download all artifacts
        uses: actions/download-artifact@v4

      - name: Generate test summary
        run: |
          echo "# Smart Dairy Regression Test Results" > test-summary.md
          echo "" >> test-summary.md
          echo "| Test Suite | Status |" >> test-summary.md
          echo "|------------|--------|" >> test-summary.md
          echo "| Smoke Tests | ${{ needs.smoke-tests.result }} |" >> test-summary.md
          echo "| API Regression | ${{ needs.api-regression.result }} |" >> test-summary.md
          echo "| UI Regression | ${{ needs.ui-regression.result }} |" >> test-summary.md
          
      - name: Comment on PR
        if: github.event_name == 'pull_request'
        uses: actions/github-script@v7
        with:
          script: |
            const fs = require('fs');
            const summary = fs.readFileSync('test-summary.md', 'utf8');
            github.rest.issues.createComment({
              issue_number: context.issue.number,
              owner: context.repo.owner,
              repo: context.repo.repo,
              body: summary
            });

      - name: Fail if tests failed
        if: needs.smoke-tests.result == 'failure' || needs.api-regression.result == 'failure'
        run: exit 1
```

#### 5.3.2 Jenkins Pipeline (Alternative)

```groovy
// Jenkinsfile - Regression Test Pipeline
pipeline {
    agent any

    environment {
        NODE_VERSION = '20'
        TEST_ENV = 'sit'
        CYPRESS_CACHE_FOLDER = '${WORKSPACE}/.cache/Cypress'
    }

    options {
        buildDiscarder(logRotator(numToKeepStr: '10'))
        timeout(time: 2, unit: 'HOURS')
        timestamps()
    }

    triggers {
        cron('H 2 * * *')  // Daily at 2 AM
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Setup') {
            parallel {
                stage('Setup Node') {
                    steps {
                        nodejs(nodeJSInstallationName: "Node ${NODE_VERSION}") {
                            sh 'npm ci'
                        }
                    }
                }
                stage('Setup Python') {
                    steps {
                        sh '''
                            python3 -m venv venv
                            . venv/bin/activate
                            pip install -r requirements.txt
                        '''
                    }
                }
            }
        }

        stage('Smoke Tests') {
            steps {
                nodejs(nodeJSInstallationName: "Node ${NODE_VERSION}") {
                    sh '''
                        npx cypress run \
                            --spec "cypress/e2e/smoke/**/*.cy.js" \
                            --browser chrome \
                            --reporter junit \
                            --reporter-options mochaFile=smoke-results.xml
                    '''
                }
            }
            post {
                always {
                    junit 'smoke-results.xml'
                }
            }
        }

        stage('Regression Tests') {
            parallel {
                stage('API Tests') {
                    steps {
                        sh '''
                            newman run collections/smart-dairy-regression-api.json \
                                --environment environments/${TEST_ENV}.json \
                                --reporters cli,junit \
                                --reporter-junit-export api-results.xml
                        '''
                    }
                    post {
                        always {
                            junit 'api-results.xml'
                        }
                    }
                }
                stage('UI Tests - Chrome') {
                    steps {
                        nodejs(nodeJSInstallationName: "Node ${NODE_VERSION}") {
                            sh '''
                                npx cypress run \
                                    --spec "cypress/e2e/ui-regression/**/*.cy.js" \
                                    --browser chrome \
                                    --reporter junit \
                                    --reporter-options mochaFile=ui-chrome-results.xml
                            '''
                        }
                    }
                    post {
                        always {
                            junit 'ui-chrome-results.xml'
                            publishHTML([
                                allowMissing: false,
                                alwaysLinkToLastBuild: true,
                                keepAll: true,
                                reportDir: 'cypress/reports',
                                reportFiles: 'index.html',
                                reportName: 'Cypress Test Report'
                            ])
                        }
                    }
                }
                stage('Performance Tests') {
                    steps {
                        sh '''
                            k6 run \
                                --out json=performance-results.json \
                                performance-tests/performance-regression-test.js
                        '''
                    }
                }
            }
        }

        stage('Generate Report') {
            steps {
                sh '''
                    echo "Generating consolidated test report..."
                    python3 scripts/generate-test-report.py
                '''
            }
        }

        stage('Notify') {
            steps {
                script {
                    if (currentBuild.result == 'SUCCESS') {
                        slackSend(color: 'good', message: "✅ Regression tests passed: ${env.JOB_NAME} #${env.BUILD_NUMBER}")
                    } else {
                        slackSend(color: 'danger', message: "❌ Regression tests failed: ${env.JOB_NAME} #${env.BUILD_NUMBER}")
                    }
                }
            }
        }
    }

    post {
        always {
            cleanWs()
        }
        failure {
            mail to: 'qa-team@smartdairybd.com',
                 subject: "Regression Tests Failed: ${env.JOB_NAME}",
                 body: "Please check the test results at ${env.BUILD_URL}"
        }
    }
}
```

### 5.4 Test Data Management

```javascript
// cypress/support/test-data.js
// Test Data Management for Regression Tests

export const TestData = {
  // Users
  users: {
    customer: {
      email: Cypress.env('TEST_CUSTOMER_EMAIL') || 'test.customer@example.com',
      password: Cypress.env('TEST_CUSTOMER_PASSWORD') || 'TestPass123!',
      name: 'Test Customer'
    },
    b2bPartner: {
      email: Cypress.env('TEST_B2B_EMAIL') || 'test.b2b@example.com',
      password: Cypress.env('TEST_B2B_PASSWORD') || 'TestPass123!',
      company: 'Test Distributors Ltd.'
    },
    admin: {
      email: Cypress.env('TEST_ADMIN_EMAIL') || 'admin@smartdairybd.com',
      password: Cypress.env('TEST_ADMIN_PASSWORD') || 'AdminPass123!',
      name: 'System Administrator'
    },
    farmManager: {
      email: Cypress.env('TEST_FARM_EMAIL') || 'farm.manager@smartdairybd.com',
      password: Cypress.env('TEST_FARM_PASSWORD') || 'FarmPass123!',
      name: 'Farm Manager'
    }
  },

  // Products
  products: {
    freshMilk: {
      id: 'PROD-001',
      name: 'Saffron Organic Milk',
      price: 95.00,
      sku: 'SD-MILK-001',
      category: 'fresh-milk'
    },
    yogurt: {
      id: 'PROD-002',
      name: 'Saffron Sweet Yogurt',
      price: 120.00,
      sku: 'SD-YOG-001',
      category: 'yogurt'
    },
    ghee: {
      id: 'PROD-003',
      name: 'Pure Ghee',
      price: 850.00,
      sku: 'SD-GHEE-001',
      category: 'ghee'
    }
  },

  // Addresses
  addresses: {
    dhaka: {
      street: '123 Farm Road',
      area: 'Gulshan',
      city: 'Dhaka',
      postalCode: '1212',
      country: 'Bangladesh'
    },
    narayanganj: {
      street: 'Islambag Kali',
      area: 'Vulta',
      city: 'Narayanganj',
      postalCode: '1400',
      country: 'Bangladesh'
    }
  },

  // API Test Data
  api: {
    validToken: null, // Set during test run
    invalidToken: 'invalid.token.here',
    expiredToken: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1MDAwMDAwMDB9.invalid'
  }
};

// Data factory for dynamic test data
testData.factory = {
  generateUniqueEmail: () => `test.${Date.now()}@example.com`,
  generateUniquePhone: () => `017${Math.floor(Math.random() * 100000000).toString().padStart(8, '0')}`,
  generateOrderId: () => `ORD-${Date.now()}-${Math.floor(Math.random() * 1000)}`
};

export default TestData;
```

---

## 6. TEST EXECUTION SCHEDULE

### 6.1 Execution Frequency Matrix

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                   REGRESSION TEST EXECUTION SCHEDULE                         │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  FREQUENCY: EVERY BUILD (Continuous Integration)                             │
│  ─────────────────────────────────────────────────────────────────────────  │
│  ┌─────────────────┬──────────────────┬─────────────────────────────────┐   │
│  │ Test Suite      │ Duration         │ Trigger                         │   │
│  ├─────────────────┼──────────────────┼─────────────────────────────────┤   │
│  │ Smoke Tests     │ 5-10 minutes     │ Every commit to develop/main    │   │
│  │ Unit Tests      │ 10-15 minutes    │ Every commit (pre-commit hook)  │   │
│  │ API Health      │ 2 minutes        │ Every 15 minutes (monitoring)   │   │
│  └─────────────────┴──────────────────┴─────────────────────────────────┘   │
│                                                                              │
│  FREQUENCY: DAILY                                                            │
│  ─────────────────────────────────────────────────────────────────────────  │
│  ┌─────────────────┬──────────────────┬─────────────────────────────────┐   │
│  │ Test Suite      │ Duration         │ Schedule                        │   │
│  ├─────────────────┼──────────────────┼─────────────────────────────────┤   │
│  │ Full Regression │ 2-4 hours        │ Daily at 02:00 UTC              │   │
│  │ API Regression  │ 30 minutes       │ Daily at 03:00 UTC              │   │
│  │ UI Regression   │ 1-2 hours        │ Daily at 04:00 UTC              │   │
│  │ Mobile Tests    │ 45 minutes       │ Daily at 05:00 UTC              │   │
│  └─────────────────┴──────────────────┴─────────────────────────────────┘   │
│                                                                              │
│  FREQUENCY: WEEKLY                                                           │
│  ─────────────────────────────────────────────────────────────────────────  │
│  ┌─────────────────┬──────────────────┬─────────────────────────────────┐   │
│  │ Test Suite      │ Duration         │ Schedule                        │   │
│  ├─────────────────┼──────────────────┼─────────────────────────────────┤   │
│  │ Performance     │ 2-3 hours        │ Sunday 00:00 UTC                │   │
│  │ Security Scan   │ 1-2 hours        │ Sunday 02:00 UTC                │   │
│  │ Cross-Browser   │ 3-4 hours        │ Sunday 04:00 UTC                │   │
│  │ Full Mobile     │ 2 hours          │ Sunday 08:00 UTC                │   │
│  └─────────────────┴──────────────────┴─────────────────────────────────┘   │
│                                                                              │
│  FREQUENCY: RELEASE-BASED                                                    │
│  ─────────────────────────────────────────────────────────────────────────  │
│  ┌─────────────────┬──────────────────┬─────────────────────────────────┐   │
│  │ Test Suite      │ Duration         │ Trigger                         │   │
│  ├─────────────────┼──────────────────┼─────────────────────────────────┤   │
│  │ Complete Suite  │ 6-8 hours        │ Before every release            │   │
│  │ UAT Regression  │ 4 hours          │ Before UAT handover             │   │
│  │ Production      │ 30 minutes       │ After production deployment     │   │
│  │   Smoke Tests   │                  │                                 │   │
│  └─────────────────┴──────────────────┴─────────────────────────────────┘   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 6.2 Weekly Test Execution Calendar

| Day | Time (UTC) | Test Suite | Environment | Duration | Owner |
|-----|-----------|------------|-------------|----------|-------|
| Monday | 02:00 | Full Regression | SIT | 4 hours | Automated |
| Monday | 09:00 | Results Review | N/A | 1 hour | QA Lead |
| Tuesday | 02:00 | API + UI Regression | SIT | 3 hours | Automated |
| Wednesday | 02:00 | Full Regression | SIT | 4 hours | Automated |
| Thursday | 02:00 | API + UI Regression | SIT | 3 hours | Automated |
| Friday | 02:00 | Full Regression | SIT | 4 hours | Automated |
| Friday | 10:00 | Weekly Summary | N/A | 30 min | QA Lead |
| Saturday | 02:00 | Mobile Regression | SIT | 2 hours | Automated |
| Sunday | 00:00 | Performance Tests | SIT | 3 hours | Automated |
| Sunday | 03:00 | Security Scan | SIT | 2 hours | Automated |
| Sunday | 06:00 | Cross-Browser | SIT | 4 hours | Automated |

### 6.3 Release Testing Schedule

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    RELEASE TESTING TIMELINE                                  │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  T-7 Days: Code Freeze & Staging Deployment                                  │
│  ─────────────────────────────────────────────────────────────────────────  │
│  • Deploy release candidate to Staging environment                          │
│  • Execute complete regression suite (all 150+ tests)                       │
│  • Run performance baseline tests                                           │
│  • Execute security vulnerability scan                                      │
│                                                                              │
│  T-5 Days: Defect Resolution Round 1                                         │
│  ─────────────────────────────────────────────────────────────────────────  │
│  • Review all failed tests                                                  │
│  • Fix critical and high priority defects                                   │
│  • Re-run affected test cases                                               │
│  • Update release notes                                                     │
│                                                                              │
│  T-3 Days: Final Regression & UAT Preparation                                │
│  ─────────────────────────────────────────────────────────────────────────  │
│  • Execute final full regression                                            │
│  • Prepare UAT environment                                                  │
│  • Create UAT test scripts                                                  │
│  • Validate all integrations                                                │
│                                                                              │
│  T-1 Day: Go/No-Go Decision                                                  │
│  ─────────────────────────────────────────────────────────────────────────  │
│  • Review all test results                                                  │
│  • Verify all P0/P1 tests pass                                              │
│  • Confirm defect resolution                                                │
│  • Obtain stakeholder sign-off                                              │
│                                                                              │
│  T-0: Production Deployment                                                  │
│  ─────────────────────────────────────────────────────────────────────────  │
│  • Execute production smoke tests immediately after deployment              │
│  • Monitor error rates and performance                                      │
│  • Verify critical user journeys                                            │
│  • Enable traffic gradually                                                 │
│                                                                              │
│  T+1 Day: Post-Release Validation                                            │
│  ─────────────────────────────────────────────────────────────────────────  │
│  • Run extended smoke tests in production                                   │
│  • Monitor customer feedback                                                │
│  • Validate business metrics                                                │
│  • Close release testing cycle                                              │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 7. TEST CASE MAINTENANCE

### 7.1 Test Case Lifecycle

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    TEST CASE LIFECYCLE MANAGEMENT                            │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────┐     ┌─────────┐     ┌─────────┐     ┌─────────┐     ┌────────┐│
│  │ DRAFT   │────▶│ REVIEW  │────▶│ ACTIVE  │────▶│ UPDATE  │────▶│ RETIRED││
│  └─────────┘     └─────────┘     └─────────┘     └─────────┘     └────────┘│
│       │                               │              │              │       │
│       │                               │              │              │       │
│       ▼                               ▼              ▼              ▼       │
│  ┌─────────┐                    ┌─────────┐    ┌─────────┐    ┌─────────┐  │
│  │ Author  │                    │ Execute │    │ Version │    │ Archive │  │
│  │ creates │                    │ in test │    │ control │    │ & retain│  │
│  │ test    │                    │ cycles  │    │ changes │    │ history │  │
│  └─────────┘                    └─────────┘    └─────────┘    └─────────┘  │
│                                                                              │
│  STATUS DEFINITIONS:                                                        │
│  • DRAFT: Test case under development                                        │
│  • REVIEW: Pending peer review and approval                                  │
│  • ACTIVE: Approved and part of regression suite                             │
│  • UPDATE: Requires modification due to application changes                  │
│  • RETIRED: No longer applicable, kept for audit                             │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 7.2 Test Case Update Triggers

| Trigger Event | Action Required | Owner | Timeline |
|---------------|-----------------|-------|----------|
| New feature release | Add new test cases | QA Engineer | Within 1 week |
| UI redesign | Update existing test selectors | QA Engineer | Before release |
| API contract change | Update API test assertions | QA Engineer | Within 3 days |
| Defect found in production | Add regression test for defect | QA Engineer | Within 1 week |
| Business process change | Update affected test cases | BA + QA | Within 1 week |
| Test case obsolete | Mark as retired | QA Lead | Immediate |
| Flaky test identified | Debug and fix or retire | QA Engineer | Within 3 days |
| Performance degradation | Update performance baselines | Performance QA | Immediate |

### 7.3 Quarterly Review Process

| Review Activity | Frequency | Participants | Output |
|-----------------|-----------|--------------|--------|
| Test Coverage Analysis | Quarterly | QA Lead, Dev Lead | Coverage report |
| Test Case Effectiveness | Quarterly | QA Team | Effectiveness metrics |
| Automation ROI Review | Quarterly | QA Lead, PM | ROI analysis |
| Test Case Cleanup | Quarterly | QA Team | Retired test cases list |
| New Risk Assessment | Quarterly | All stakeholders | Risk matrix update |

### 7.4 Test Case Version Control

```
Test Case Versioning Format: TC-{MODULE}-{NUMBER}-{VERSION}

Examples:
- TC-B2C-001-v1.0: Initial version
- TC-B2C-001-v1.1: Updated with new checkout flow
- TC-B2C-001-v2.0: Major redesign of test case

Version Change Rules:
- Major version (X.0): Significant test logic change
- Minor version (0.X): Small updates, data changes
- Build number (0.0.X): Typo fixes, formatting
```

---

## 8. RISK-BASED PRIORITIZATION

### 8.1 Risk Assessment Matrix

```
┌─────────────────────────────────────────────────────────────────────────────┐
│              RISK PRIORITIZATION MATRIX FOR REGRESSION TESTING               │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  RISK SCORE CALCULATION:                                                     │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │  Risk Score = (Business Impact × 0.4) + (Technical Risk × 0.3) +    │    │
│  │               (Usage Frequency × 0.2) + (Defect History × 0.1)      │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  SCORING SCALE (1-5):                                                        │
│  1 = Very Low, 2 = Low, 3 = Medium, 4 = High, 5 = Critical                  │
│                                                                              │
│  ┌──────────────────┬──────────────────────────────────────────────────┐    │
│  │ Priority Level   │ Risk Score Range │ Action                        │    │
│  ├──────────────────┼──────────────────┼───────────────────────────────┤    │
│  │ P0 - Critical    │ 4.0 - 5.0        │ Run on every build            │    │
│  │ P1 - High        │ 3.0 - 3.9        │ Run in daily regression       │    │
│  │ P2 - Medium      │ 2.0 - 2.9        │ Run in weekly regression      │    │
│  │ P3 - Low         │ 1.0 - 1.9        │ Run in release regression only│    │
│  └──────────────────┴──────────────────┴───────────────────────────────┘    │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 8.2 Module Risk Assessment

| Module | Business Impact | Technical Risk | Usage Freq | Defect History | Risk Score | Priority |
|--------|----------------|----------------|------------|----------------|------------|----------|
| Payment Processing | 5 | 4 | 5 | 4 | 4.5 | P0 |
| User Authentication | 5 | 4 | 5 | 3 | 4.3 | P0 |
| Order Management | 5 | 3 | 5 | 3 | 4.1 | P0 |
| Inventory Sync | 4 | 4 | 4 | 4 | 4.0 | P0 |
| Product Catalog | 4 | 2 | 5 | 2 | 3.4 | P1 |
| Checkout Flow | 5 | 3 | 4 | 2 | 3.6 | P1 |
| Subscription Mgmt | 4 | 3 | 4 | 3 | 3.5 | P1 |
| Farm Data Entry | 3 | 4 | 3 | 4 | 3.5 | P1 |
| Reporting | 3 | 2 | 3 | 2 | 2.6 | P2 |
| Content Management | 2 | 1 | 3 | 1 | 1.8 | P3 |
| SEO Features | 2 | 1 | 2 | 1 | 1.5 | P3 |

### 8.3 Risk-Based Test Selection Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                  RISK-BASED TEST SELECTION FLOW                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│                         ┌─────────────────────┐                              │
│                         │  Code Change Commit │                              │
│                         └──────────┬──────────┘                              │
│                                    │                                         │
│                                    ▼                                         │
│                    ┌───────────────────────────────┐                         │
│                    │  Identify Changed Components  │                         │
│                    └───────────────┬───────────────┘                         │
│                                    │                                         │
│                    ┌───────────────┼───────────────┐                         │
│                    ▼               ▼               ▼                         │
│             ┌──────────┐    ┌──────────┐    ┌──────────┐                    │
│             │ Business │    │ Technical│    │ Historical│                   │
│             │  Impact  │    │  Risk    │    │  Defects  │                   │
│             └────┬─────┘    └────┬─────┘    └────┬─────┘                    │
│                  │               │               │                           │
│                  └───────────────┼───────────────┘                           │
│                                  ▼                                           │
│                    ┌─────────────────────────┐                               │
│                    │  Calculate Risk Score   │                               │
│                    └───────────┬─────────────┘                               │
│                                │                                             │
│                    ┌───────────┴───────────┐                                 │
│                    ▼                       ▼                                 │
│            ┌─────────────┐         ┌─────────────┐                          │
│            │  Risk ≥ 3.5 │         │  Risk < 3.5 │                          │
│            │  (P0/P1)    │         │  (P2/P3)    │                          │
│            └──────┬──────┘         └──────┬──────┘                          │
│                   │                       │                                  │
│                   ▼                       ▼                                  │
│         ┌──────────────────┐    ┌──────────────────┐                        │
│         │ Include in Daily │    │ Include in       │                        │
│         │ Regression       │    │ Weekly/Release   │                        │
│         └──────────────────┘    └──────────────────┘                        │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 9. EXECUTION PROCEDURES

### 9.1 Manual vs Automated Testing

| Test Category | Automation Level | Manual Testing Focus | Automation Tools |
|---------------|------------------|---------------------|------------------|
| Smoke Tests | 100% | None | Cypress |
| Critical Path | 100% | None | Cypress, Postman |
| Functional Regression | 80% | Complex workflows, Edge cases | Cypress, Selenium |
| API Regression | 100% | None | Postman, REST Assured |
| UI/Cross-Browser | 90% | Visual validation | Cypress, BrowserStack |
| Mobile App | 85% | Device-specific interactions | Appium |
| Performance | 100% | None | k6, JMeter |
| Security | 70% | Penetration testing | OWASP ZAP, Burp Suite |
| Usability | 0% | Full manual validation | None |
| Exploratory | 0% | Full manual testing | None |

### 9.2 Test Execution Checklist

#### Pre-Execution
- [ ] Test environment is available and stable
- [ ] Test data is prepared and validated
- [ ] Test scripts are up to date with latest application version
- [ ] Required credentials and access are available
- [ ] Previous test results have been reviewed
- [ ] Known issues/defects are documented

#### During Execution
- [ ] Execute tests according to priority order
- [ ] Document actual results for all test cases
- [ ] Log defects immediately with reproduction steps
- [ ] Capture screenshots/videos for failed tests
- [ ] Note any unexpected behavior, even if test passes
- [ ] Monitor system logs for errors

#### Post-Execution
- [ ] Generate test execution report
- [ ] Review all failed tests with development team
- [ ] Update test case status in test management tool
- [ ] Archive test evidence (screenshots, logs)
- [ ] Update regression suite if new defects found
- [ ] Communicate results to stakeholders

### 9.3 Defect Logging Template

```markdown
## Defect Report Template

**Defect ID:** BUG-{AUTO_INCREMENT}
**Title:** [Brief description of the issue]
**Severity:** Critical/High/Medium/Low
**Priority:** P0/P1/P2/P3
**Environment:** SIT/UAT/Production
**Module:** [Affected module]
**Regression Test ID:** [Associated test case]

### Description
[Detailed description of the defect]

### Steps to Reproduce
1. Step 1
2. Step 2
3. Step 3

### Expected Result
[What should happen]

### Actual Result
[What actually happened]

### Evidence
- Screenshots: [Attach]
- Video: [Attach if applicable]
- Logs: [Relevant log entries]

### Additional Information
- Browser/Device: [If applicable]
- Frequency: [Always/Intermittent]
- Related Issues: [Links to related bugs]
```

---

## 10. RESULTS ANALYSIS

### 10.1 Pass/Fail Criteria

| Test Category | Pass Criteria | Fail Criteria | Action on Fail |
|---------------|---------------|---------------|----------------|
| Smoke Tests | 100% pass | Any failure | Block deployment |
| Critical Path | 100% pass | Any failure | Block deployment |
| P0 Tests | 100% pass | Any failure | Block deployment |
| P1 Tests | ≥ 95% pass | < 95% pass | Management review |
| P2 Tests | ≥ 90% pass | < 90% pass | QA Lead review |
| P3 Tests | ≥ 85% pass | < 85% pass | Document known issues |
| Performance | Meet baseline | Degradation > 10% | Performance review |
| Security | No critical/high | Any critical/high | Immediate fix |

### 10.2 Defect Triage Process

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      DEFECT TRIAGE PROCESS                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐         │
│  │  Defect Logged  │───▶│  Severity       │───▶│  Triage Queue   │         │
│  │  in Jira        │    │  Assessment     │    │  (Daily 10 AM)  │         │
│  └─────────────────┘    └─────────────────┘    └────────┬────────┘         │
│                                                         │                    │
│                    ┌────────────────────────────────────┼────────────────┐  │
│                    │                                    │                │  │
│                    ▼                                    ▼                │  │
│         ┌──────────────────┐               ┌──────────────────┐          │  │
│         │  SEV 1-2         │               │  SEV 3-4         │          │  │
│         │  (Critical/High) │               │  (Medium/Low)    │          │  │
│         └────────┬─────────┘               └────────┬─────────┘          │  │
│                  │                                  │                    │  │
│                  ▼                                  ▼                    │  │
│         ┌──────────────────┐               ┌──────────────────┐          │  │
│         │  Immediate       │               │  Next Sprint     │          │  │
│         │  Assignment      │               │  Planning        │          │  │
│         └────────┬─────────┘               └────────┬─────────┘          │  │
│                  │                                  │                    │  │
│                  ▼                                  ▼                    │  │
│         ┌──────────────────┐               ┌──────────────────┐          │  │
│         │  Fix & Verify    │               │  Fix & Verify    │          │  │
│         └────────┬─────────┘               └────────┬─────────┘          │  │
│                  │                                  │                    │  │
│                  ▼                                  ▼                    │  │
│         ┌──────────────────┐               ┌──────────────────┐          │  │
│         │  Regression Test │               │  Regression Test │          │  │
│         └──────────────────┘               └──────────────────┘          │  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 10.3 Regression Test Metrics

| Metric | Calculation | Target | Reporting Frequency |
|--------|-------------|--------|---------------------|
| Test Pass Rate | (Passed Tests / Total Tests) × 100 | ≥ 95% | Daily |
| Automation Coverage | (Automated Tests / Total Tests) × 100 | ≥ 80% | Weekly |
| Defect Detection Rate | Defects Found / Tests Executed | Trend improving | Weekly |
| Mean Time to Detect | Time to find critical defect | < 4 hours | Per defect |
| Test Execution Time | Total regression execution time | < 4 hours | Per run |
| Flaky Test Rate | Flaky Tests / Total Automated | < 5% | Weekly |
| Test Maintenance Effort | Hours spent updating tests | < 10% of execution | Monthly |

---

## 11. REPORTING

### 11.1 Regression Test Report Template

```markdown
# Smart Dairy Regression Test Report

**Report Date:** [Date]
**Test Cycle:** [Daily/Weekly/Release]
**Environment:** [SIT/UAT/Production]
**Executed By:** [Name]

## Executive Summary

| Metric | Value |
|--------|-------|
| Total Test Cases | 150 |
| Passed | [Number] ([%]) |
| Failed | [Number] ([%]) |
| Blocked | [Number] ([%]) |
| Skipped | [Number] ([%]) |
| Pass Rate | [%] |
| Execution Duration | [Hours:Minutes] |

## Test Results by Module

| Module | Total | Passed | Failed | Pass Rate |
|--------|-------|--------|--------|-----------|
| Public Website | 15 | 15 | 0 | 100% |
| B2C Portal | 25 | 24 | 1 | 96% |
| B2B Portal | 20 | 20 | 0 | 100% |
| Farm Management | 20 | 19 | 1 | 95% |
| ERP Core | 25 | 25 | 0 | 100% |
| Mobile Apps | 15 | 15 | 0 | 100% |
| APIs | 20 | 20 | 0 | 100% |
| Integrations | 10 | 10 | 0 | 100% |

## Defect Summary

| Defect ID | Severity | Module | Status | Description |
|-----------|----------|--------|--------|-------------|
| BUG-XXX | High | [Module] | Open | [Description] |

## Recommendations
1. [Recommendation 1]
2. [Recommendation 2]

## Appendix
- Detailed Test Results: [Link]
- Defect Reports: [Link]
- Automation Logs: [Link]
```

### 11.2 Regression Trends Dashboard

| Month | Total Tests | Pass Rate | Avg Execution Time | Defects Found | Defects Fixed |
|-------|-------------|-----------|-------------------|---------------|---------------|
| Jan 2026 | 150 | 98% | 3.5 hours | 5 | 5 |
| Feb 2026 | 160 | 97% | 3.2 hours | 8 | 7 |
| Mar 2026 | 170 | 96% | 3.0 hours | 6 | 6 |
| Apr 2026 | 175 | 98% | 2.8 hours | 4 | 4 |
| May 2026 | 175 | 99% | 2.5 hours | 3 | 3 |

---

## 12. APPENDICES

### Appendix A: Complete Test Case List

#### A.1 Public Website (15 Tests)

| Test ID | Test Case | Priority | Automated |
|---------|-----------|----------|-----------|
| REG-WEB-001 | Homepage hero carousel rotation | P1 | Yes |
| REG-WEB-002 | Product category navigation | P1 | Yes |
| REG-WEB-003 | Company information pages | P2 | Yes |
| REG-WEB-004 | Farm virtual tour gallery | P2 | Yes |
| REG-WEB-005 | News and blog post listing | P2 | Yes |
| REG-WEB-006 | Store locator with Google Maps | P1 | Yes |
| REG-WEB-007 | Contact forms with validation | P1 | Yes |
| REG-WEB-008 | Newsletter subscription | P2 | Yes |
| REG-WEB-009 | English to Bengali language switch | P1 | Yes |
| REG-WEB-010 | Bengali font rendering | P2 | Yes |
| REG-WEB-011 | RTL layout verification | P3 | No |
| REG-WEB-012 | SEO meta tags and structured data | P2 | Yes |
| REG-WEB-013 | XML sitemap and robots.txt | P2 | Yes |
| REG-WEB-014 | Social media sharing | P3 | Yes |
| REG-WEB-015 | Page load performance | P1 | Yes |

#### A.2 B2C Portal (25 Tests)

| Test ID | Test Case | Priority | Automated |
|---------|-----------|----------|-----------|
| REG-B2C-001 | User registration with email verification | P0 | Yes |
| REG-B2C-002 | Login with valid/invalid credentials | P0 | Yes |
| REG-B2C-003 | Password reset flow | P0 | Yes |
| REG-B2C-004 | Social login | P1 | Yes |
| REG-B2C-005 | Profile update and avatar upload | P1 | Yes |
| REG-B2C-006 | Address management | P1 | Yes |
| REG-B2C-007 | Category browsing and navigation | P1 | Yes |
| REG-B2C-008 | Product filtering | P1 | Yes |
| REG-B2C-009 | Product search with autocomplete | P1 | Yes |
| REG-B2C-010 | Product detail with variants | P1 | Yes |
| REG-B2C-011 | Product reviews and ratings | P2 | Yes |
| REG-B2C-012 | Add products to cart | P0 | Yes |
| REG-B2C-013 | Update cart quantities | P1 | Yes |
| REG-B2C-014 | Remove items from cart | P1 | Yes |
| REG-B2C-015 | Cart persistence | P1 | Yes |
| REG-B2C-016 | Apply and remove promo codes | P1 | Yes |
| REG-B2C-017 | Guest checkout flow | P0 | Yes |
| REG-B2C-018 | Registered user checkout | P0 | Yes |
| REG-B2C-019 | Multiple shipping address selection | P1 | Yes |
| REG-B2C-020 | bKash payment integration | P0 | Yes |
| REG-B2C-021 | Nagad payment integration | P0 | Yes |
| REG-B2C-022 | Cash on delivery option | P1 | Yes |
| REG-B2C-023 | Daily milk subscription setup | P1 | Yes |
| REG-B2C-024 | Subscription pause/resume/modify | P1 | Yes |
| REG-B2C-025 | Order history and invoice download | P1 | Yes |

#### A.3 B2B Portal (20 Tests)

| Test ID | Test Case | Priority | Automated |
|---------|-----------|----------|-----------|
| REG-B2B-001 | Partner registration with approval | P1 | Yes |
| REG-B2B-002 | Partner tier assignment | P1 | Yes |
| REG-B2B-003 | Company profile management | P2 | Yes |
| REG-B2B-004 | Multiple user accounts per partner | P2 | Yes |
| REG-B2B-005 | Quick order form | P1 | Yes |
| REG-B2B-006 | CSV order upload | P1 | Yes |
| REG-B2B-007 | Reorder from history | P2 | Yes |
| REG-B2B-008 | Order template creation | P2 | Yes |
| REG-B2B-009 | Bulk pricing and volume discounts | P1 | Yes |
| REG-B2B-010 | Credit limit management | P1 | Yes |
| REG-B2B-011 | Payment terms configuration | P1 | Yes |
| REG-B2B-012 | Outstanding balance display | P1 | Yes |
| REG-B2B-013 | Invoice download and statements | P1 | Yes |
| REG-B2B-014 | Credit note application | P2 | Yes |
| REG-B2B-015 | RFQ submission | P1 | Yes |
| REG-B2B-016 | Quote acceptance/rejection | P1 | Yes |
| REG-B2B-017 | Contract pricing view | P1 | Yes |
| REG-B2B-018 | Delivery schedule management | P2 | Yes |
| REG-B2B-019 | API key generation | P1 | Yes |
| REG-B2B-020 | Webhook event handling | P2 | Yes |

#### A.4 Farm Management (20 Tests)

| Test ID | Test Case | Priority | Automated |
|---------|-----------|----------|-----------|
| REG-FARM-001 | Cattle profile creation | P1 | Yes |
| REG-FARM-002 | RFID/ear tag assignment | P1 | Yes |
| REG-FARM-003 | Lineage and breeding history | P2 | Yes |
| REG-FARM-004 | Cattle classification | P2 | Yes |
| REG-FARM-005 | Vaccination schedule | P1 | Yes |
| REG-FARM-006 | Treatment records | P1 | Yes |
| REG-FARM-007 | Breeding records | P1 | Yes |
| REG-FARM-008 | Calving alerts | P1 | Yes |
| REG-FARM-009 | Veterinary visit scheduling | P2 | Yes |
| REG-FARM-010 | Daily milk yield recording | P0 | Yes |
| REG-FARM-011 | Milk quality parameter entry | P0 | Yes |
| REG-FARM-012 | Production trend analysis | P2 | Yes |
| REG-FARM-013 | Milk collection center integration | P1 | Yes |
| REG-FARM-014 | Ration planning | P2 | Yes |
| REG-FARM-015 | Feed inventory tracking | P2 | Yes |
| REG-FARM-016 | Medicine inventory with expiry | P1 | Yes |
| REG-FARM-017 | Daily task assignment | P1 | Yes |
| REG-FARM-018 | Biogas plant monitoring | P2 | Yes |
| REG-FARM-019 | Equipment maintenance | P2 | Yes |
| REG-FARM-020 | Farm reports and analytics | P2 | Yes |

#### A.5 ERP Core (25 Tests)

| Test ID | Test Case | Priority | Automated |
|---------|-----------|----------|-----------|
| REG-ERP-001 | Multi-location inventory tracking | P0 | Yes |
| REG-ERP-002 | Batch/lot tracking | P0 | Yes |
| REG-ERP-003 | Barcode/RFID scanning | P1 | Yes |
| REG-ERP-004 | Stock valuation and adjustment | P1 | Yes |
| REG-ERP-005 | Reorder point and replenishment | P1 | Yes |
| REG-ERP-006 | Lead management | P1 | Yes |
| REG-ERP-007 | Quotation generation | P1 | Yes |
| REG-ERP-008 | Sales order processing | P0 | Yes |
| REG-ERP-009 | Delivery scheduling | P1 | Yes |
| REG-ERP-010 | Customer portal integration | P1 | Yes |
| REG-ERP-011 | Supplier management | P1 | Yes |
| REG-ERP-012 | RFQ creation and comparison | P1 | Yes |
| REG-ERP-013 | Purchase order processing | P0 | Yes |
| REG-ERP-014 | Goods receipt | P1 | Yes |
| REG-ERP-015 | Three-way matching | P1 | Yes |
| REG-ERP-016 | General ledger transactions | P0 | Yes |
| REG-ERP-017 | Accounts payable | P1 | Yes |
| REG-ERP-018 | Accounts receivable | P1 | Yes |
| REG-ERP-019 | Bank reconciliation | P1 | Yes |
| REG-ERP-020 | VAT/GST compliance | P0 | Yes |
| REG-ERP-021 | Financial reporting | P1 | Yes |
| REG-ERP-022 | Bill of Materials | P1 | Yes |
| REG-ERP-023 | Production planning | P1 | Yes |
| REG-ERP-024 | Quality control checkpoints | P1 | Yes |
| REG-ERP-025 | Yield tracking | P1 | Yes |

#### A.6 Mobile Apps (15 Tests)

| Test ID | Test Case | Priority | Automated |
|---------|-----------|----------|-----------|
| REG-MOB-001 | App launch and splash screen | P0 | Yes |
| REG-MOB-002 | Login with biometric | P0 | Yes |
| REG-MOB-003 | Product catalog browsing | P1 | Yes |
| REG-MOB-004 | Product search with voice | P2 | No |
| REG-MOB-005 | Shopping cart management | P1 | Yes |
| REG-MOB-006 | Checkout with mobile payment | P0 | Yes |
| REG-MOB-007 | Push notification handling | P1 | Yes |
| REG-MOB-008 | Deep linking | P1 | Yes |
| REG-MOB-009 | Offline mode | P1 | Yes |
| REG-MOB-010 | Order tracking with map | P1 | Yes |
| REG-MOB-011 | Daily milk collection entry | P0 | Yes |
| REG-MOB-012 | Cattle health check entry | P1 | Yes |
| REG-MOB-013 | Task completion and photo upload | P1 | Yes |
| REG-MOB-014 | GPS location capture | P1 | Yes |
| REG-MOB-015 | Background data sync | P1 | Yes |

#### A.7 APIs (20 Tests)

| Test ID | Test Case | Priority | Automated |
|---------|-----------|----------|-----------|
| REG-API-001 | OAuth2 token generation | P0 | Yes |
| REG-API-002 | JWT token refresh | P0 | Yes |
| REG-API-003 | API key authentication | P1 | Yes |
| REG-API-004 | RBAC enforcement | P0 | Yes |
| REG-API-005 | Customer registration | P1 | Yes |
| REG-API-006 | Address CRUD operations | P1 | Yes |
| REG-API-007 | Order history tracking | P1 | Yes |
| REG-API-008 | Subscription management | P1 | Yes |
| REG-API-009 | Product listing with filtering | P1 | Yes |
| REG-API-010 | Product detail with stock | P1 | Yes |
| REG-API-011 | Category tree navigation | P1 | Yes |
| REG-API-012 | Search with autocomplete | P1 | Yes |
| REG-API-013 | Cart management | P0 | Yes |
| REG-API-014 | Checkout and order creation | P0 | Yes |
| REG-API-015 | Payment callback handling | P0 | Yes |
| REG-API-016 | Order cancellation | P1 | Yes |
| REG-API-017 | Cattle data CRUD | P1 | Yes |
| REG-API-018 | Milk production submission | P0 | Yes |
| REG-API-019 | Health record entry | P1 | Yes |
| REG-API-020 | Health check endpoints | P0 | Yes |

#### A.8 Integrations (10 Tests)

| Test ID | Test Case | Priority | Automated |
|---------|-----------|----------|-----------|
| REG-INT-001 | Portal to ERP data sync | P0 | Yes |
| REG-INT-002 | B2C order to ERP flow | P0 | Yes |
| REG-INT-003 | Inventory sync | P0 | Yes |
| REG-INT-004 | Customer data sync | P1 | Yes |
| REG-INT-005 | Payment gateway reconciliation | P0 | Yes |
| REG-INT-006 | SMS gateway delivery | P1 | Yes |
| REG-INT-007 | Email service | P1 | Yes |
| REG-INT-008 | Farm data to ERP update | P0 | Yes |
| REG-INT-009 | Mobile app to backend | P0 | Yes |
| REG-INT-010 | IoT sensor data ingestion | P1 | Yes |

### Appendix B: Automation Script Examples

#### B.1 Selenium Test Example (Python)

```python
# tests/selenium/test_b2c_checkout.py
"""
Selenium WebDriver Test Example for B2C Checkout Flow
Smart Dairy Regression Test Suite
"""

import pytest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager


class TestB2CCheckout:
    """B2C Checkout Regression Tests"""
    
    @pytest.fixture(scope="function")
    def driver(self):
        """Setup WebDriver for each test"""
        chrome_options = Options()
        chrome_options.add_argument("--headless")
        chrome_options.add_argument("--no-sandbox")
        chrome_options.add_argument("--disable-dev-shm-usage")
        
        driver = webdriver.Chrome(
            service=Service(ChromeDriverManager().install()),
            options=chrome_options
        )
        driver.implicitly_wait(10)
        yield driver
        driver.quit()
    
    def test_complete_checkout_flow(self, driver):
        """
        REG-B2C-017: Complete checkout flow regression test
        """
        # Navigate to B2C portal
        driver.get("https://shop.smartdairybd.com")
        
        # Login
        driver.find_element(By.LINK_TEXT, "Login").click()
        driver.find_element(By.NAME, "email").send_keys("test.customer@example.com")
        driver.find_element(By.NAME, "password").send_keys("TestPass123!")
        driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        
        # Wait for dashboard
        WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.CLASS_NAME, "dashboard"))
        )
        
        # Search and add product to cart
        driver.find_element(By.NAME, "search").send_keys("saffron organic milk")
        driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        
        # Click on product
        WebDriverWait(driver, 10).until(
            EC.element_to_be_clickable((By.CLASS_NAME, "product-card"))
        ).click()
        
        # Add to cart
        driver.find_element(By.CSS_SELECTOR, "[data-testid='add-to-cart']").click()
        
        # Wait for cart update
        WebDriverWait(driver, 5).until(
            EC.text_to_be_present_in_element(
                (By.CLASS_NAME, "cart-badge"), "1"
            )
        )
        
        # Go to checkout
        driver.find_element(By.CLASS_NAME, "cart-icon").click()
        driver.find_element(By.LINK_TEXT, "Checkout").click()
        
        # Fill shipping details
        driver.find_element(By.NAME, "address_line1").send_keys("123 Farm Road")
        driver.find_element(By.NAME, "city").send_keys("Dhaka")
        driver.find_element(By.NAME, "postal_code").send_keys("1212")
        
        # Select payment method
        driver.find_element(By.CSS_SELECTOR, "[value='bkash']").click()
        
        # Submit order
        driver.find_element(By.ID, "place-order-btn").click()
        
        # Verify order confirmation
        confirmation = WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.CLASS_NAME, "order-confirmation"))
        )
        assert "Order Confirmed" in confirmation.text
        assert driver.find_element(By.CLASS_NAME, "order-number").is_displayed()


if __name__ == "__main__":
    pytest.main([__file__, "-v"])
```

#### B.2 Cypress Custom Commands

```javascript
// cypress/support/commands.js
// Custom Cypress Commands for Smart Dairy

Cypress.Commands.add('loginAsCustomer', (email, password) => {
  const userEmail = email || Cypress.env('TEST_CUSTOMER_EMAIL');
  const userPassword = password || Cypress.env('TEST_CUSTOMER_PASSWORD');
  
  cy.session([userEmail, 'customer'], () => {
    cy.visit('/login');
    cy.get('input[name="email"]').type(userEmail);
    cy.get('input[name="password"]').type(userPassword);
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/dashboard');
  });
});

Cypress.Commands.add('loginAsERPUser', (email, password) => {
  const userEmail = email || Cypress.env('ERP_ADMIN_EMAIL');
  const userPassword = password || Cypress.env('ERP_ADMIN_PASSWORD');
  
  cy.session([userEmail, 'erp'], () => {
    cy.visit('/web/login');
    cy.get('input[name="login"]').type(userEmail);
    cy.get('input[name="password"]').type(userPassword);
    cy.get('.oe_login_button').click();
    cy.get('.o_main_navbar', { timeout: 10000 }).should('be.visible');
  });
});

Cypress.Commands.add('addProductToCart', (productSku, quantity = 1) => {
  cy.visit(`/products/${productSku}`);
  cy.get('[data-testid="quantity-selector"]').clear().type(quantity);
  cy.get('[data-testid="add-to-cart"]').click();
  cy.get('.toast-notification').should('contain', 'Added to cart');
});

Cypress.Commands.add('completeCheckout', (paymentMethod = 'cod') => {
  cy.get('.cart-icon').click();
  cy.get('button').contains('Checkout').click();
  
  // Fill shipping address if needed
  cy.get('body').then($body => {
    if ($body.find('[name="address_line1"]').length) {
      cy.get('[name="address_line1"]').type('123 Farm Road');
      cy.get('[name="city"]').type('Dhaka');
      cy.get('[name="postal_code"]').type('1212');
    }
  });
  
  // Select payment method
  cy.get(`[value="${paymentMethod}"]`).check();
  
  // Place order
  cy.get('#place-order-btn').click();
  
  // Verify confirmation
  cy.get('.order-confirmation', { timeout: 10000 }).should('be.visible');
  cy.get('.order-number').should('be.visible');
});

Cypress.Commands.add('switchLanguage', (language) => {
  cy.get('.language-selector').click();
  cy.get(`.language-option[data-lang="${language}"]`).click();
  cy.get('.language-selector').should('contain', language === 'bn' ? 'বাংলা' : 'English');
});

Cypress.Commands.add('waitForLoading', () => {
  cy.get('.loading-spinner').should('not.exist');
  cy.get('.skeleton-loader').should('not.exist');
});

Cypress.Commands.add('verifyApiResponse', (alias, expectedStatus = 200) => {
  cy.wait(alias).its('response.statusCode').should('eq', expectedStatus);
});
```

### Appendix C: CI/CD Pipeline Configuration Files

See Section 5.3 for complete GitHub Actions and Jenkins pipeline configurations.

### Appendix D: Test Prioritization Matrix

| Module | P0 Tests | P1 Tests | P2 Tests | P3 Tests | Total |
|--------|----------|----------|----------|----------|-------|
| Public Website | 2 | 5 | 6 | 2 | 15 |
| B2C Portal | 8 | 10 | 5 | 2 | 25 |
| B2B Portal | 3 | 10 | 5 | 2 | 20 |
| Farm Management | 3 | 10 | 5 | 2 | 20 |
| ERP Core | 5 | 15 | 4 | 1 | 25 |
| Mobile Apps | 3 | 6 | 4 | 2 | 15 |
| APIs | 5 | 10 | 4 | 1 | 20 |
| Integrations | 4 | 4 | 2 | 0 | 10 |
| **TOTAL** | **33** | **70** | **35** | **12** | **150** |

---

## DOCUMENT APPROVAL

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | QA Engineer | _________________ | _________ |
| Owner | QA Lead | _________________ | _________ |
| Reviewer | Development Lead | _________________ | _________ |
| Approver | Project Manager | _________________ | _________ |

---

*Document End*
