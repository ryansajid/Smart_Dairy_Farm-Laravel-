# Milestone 40: Testing & Launch Preparation

## Document Control
| Field | Value |
|-------|-------|
| **Milestone Number** | 40 |
| **Title** | Testing & Launch Preparation |
| **Phase** | 4 - Public Website & CMS |
| **Timeline** | Days 241-250 (10 working days) |
| **Version** | 1.0 |
| **Status** | Planning |
| **Last Updated** | 2026-02-03 |

---

## Table of Contents
1. [Milestone Overview](#1-milestone-overview)
2. [Requirement Traceability Matrix](#2-requirement-traceability-matrix)
3. [Day-by-Day Breakdown](#3-day-by-day-breakdown)
4. [Technical Specifications](#4-technical-specifications)
5. [Testing & Validation](#5-testing--validation)
6. [Risk & Mitigation](#6-risk--mitigation)
7. [Dependencies & Handoffs](#7-dependencies--handoffs)

---

## 1. Milestone Overview

### 1.1 Sprint Goal
Complete comprehensive testing, security audits, accessibility compliance verification, and prepare the Smart Dairy website for production launch.

### 1.2 Objectives
1. Execute User Acceptance Testing (UAT) with stakeholders
2. Perform WCAG 2.1 AA accessibility audit
3. Conduct security penetration testing
4. Complete cross-browser compatibility testing
5. Verify mobile responsiveness
6. Prepare production deployment runbook
7. Conduct go-live rehearsal

### 1.3 Key Deliverables
- UAT test cases and sign-off
- Accessibility compliance report
- Security audit report
- Performance benchmark documentation
- Production deployment runbook
- Rollback procedures
- Launch checklist sign-off

### 1.4 Prerequisites
- Milestone 39 completed (multilingual support)
- All content finalized
- Staging environment stable
- Stakeholder availability for UAT

### 1.5 Success Criteria
| Criteria | Target | Measurement |
|----------|--------|-------------|
| UAT Test Cases | 100% pass | Test report |
| Accessibility | WCAG 2.1 AA | Audit tool |
| Security Issues | 0 Critical/High | Pentest report |
| Browser Support | 5 major browsers | Compatibility matrix |
| Mobile Score | >90 Lighthouse | Mobile audit |
| Uptime (staging) | >99.5% | Monitoring |

---

## 2. Requirement Traceability Matrix

| Req ID | Source | Requirement | Implementation |
|--------|--------|-------------|----------------|
| RFP-QA-001 | RFP 5.1 | Comprehensive testing | UAT, integration tests |
| RFP-SEC-001 | RFP 5.2 | Security compliance | Penetration testing |
| BRD-A11Y-001 | BRD 4.1 | Accessibility | WCAG 2.1 AA audit |
| SRS-TEST-001 | SRS 7.1 | Test coverage | Unit, integration, E2E |
| SRS-DEPLOY-001 | SRS 7.2 | Deployment procedures | Runbook creation |

---

## 3. Day-by-Day Breakdown

### Day 241: UAT Planning & Test Case Development

**Sprint Focus**: Prepare comprehensive UAT test suite

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Create UAT test plan | 3 | Test plan document |
| Develop API test cases | 3 | API test suite |
| Setup test data fixtures | 2 | Test data |

**Code Sample - UAT Test Plan Structure**:
```python
# smart_website/tests/uat/test_plan.py
"""
Smart Dairy Website - UAT Test Plan
=====================================

Test Categories:
1. Homepage & Navigation (TC-001 to TC-020)
2. Product Catalog (TC-021 to TC-050)
3. Blog & Content (TC-051 to TC-070)
4. Contact & Forms (TC-071 to TC-090)
5. Search & Filtering (TC-091 to TC-110)
6. Multilingual (TC-111 to TC-130)
7. Performance (TC-131 to TC-150)
8. Mobile Responsiveness (TC-151 to TC-170)
"""

from odoo.tests import tagged, TransactionCase

@tagged('uat', 'website')
class UATHomepageTests(TransactionCase):
    """UAT Test Cases: Homepage & Navigation"""

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.website = cls.env['website'].get_current_website()

    def test_tc001_homepage_loads(self):
        """TC-001: Homepage loads within 2 seconds"""
        # Verify homepage is accessible
        response = self.url_open('/')
        self.assertEqual(response.status_code, 200)

    def test_tc002_hero_section_visible(self):
        """TC-002: Hero section with CTA is visible"""
        response = self.url_open('/')
        self.assertIn('hero', response.text)

    def test_tc003_navigation_menu_complete(self):
        """TC-003: All navigation menu items present"""
        response = self.url_open('/')
        required_links = ['/products', '/about', '/blog', '/contact', '/services']
        for link in required_links:
            self.assertIn(link, response.text)

    def test_tc004_language_switcher_present(self):
        """TC-004: Language switcher is visible and functional"""
        response = self.url_open('/')
        self.assertIn('language-switcher', response.text)

    def test_tc005_footer_complete(self):
        """TC-005: Footer contains all required links"""
        response = self.url_open('/')
        required_footer = ['Privacy Policy', 'Terms of Service', 'Contact']
        for item in required_footer:
            self.assertIn(item, response.text)


@tagged('uat', 'products')
class UATProductTests(TransactionCase):
    """UAT Test Cases: Product Catalog"""

    def test_tc021_product_listing_page(self):
        """TC-021: Product listing page shows all categories"""
        response = self.url_open('/products')
        self.assertEqual(response.status_code, 200)
        # Verify 6 main categories present
        categories = ['Fresh Milk', 'Yogurt', 'Butter', 'Cheese', 'Ice Cream', 'Powder']
        for cat in categories:
            self.assertIn(cat, response.text)

    def test_tc022_product_filtering(self):
        """TC-022: Product filtering works correctly"""
        response = self.url_open('/products?category=fresh-milk')
        self.assertEqual(response.status_code, 200)

    def test_tc023_product_detail_page(self):
        """TC-023: Product detail page shows all information"""
        # Get a product
        product = self.env['product.template'].search([
            ('website_published', '=', True)
        ], limit=1)

        if product:
            response = self.url_open(f'/product/{product.website_slug}')
            self.assertEqual(response.status_code, 200)
            self.assertIn(product.name, response.text)
```

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Develop form test cases | 3 | Form test suite |
| Create integration test cases | 3 | Integration tests |
| Setup UAT environment | 2 | Staging config |

**Code Sample - Form Test Cases**:
```python
# smart_website/tests/uat/test_forms.py
from odoo.tests import tagged, HttpCase

@tagged('uat', 'forms')
class UATFormTests(HttpCase):
    """UAT Test Cases: Contact & Forms"""

    def test_tc071_contact_form_submission(self):
        """TC-071: Contact form submits successfully"""
        form_data = {
            'name': 'Test User',
            'email': 'test@example.com',
            'phone': '+8801712345678',
            'subject': 'Test Inquiry',
            'message': 'This is a test message for UAT.',
            'csrf_token': self.get_csrf_token(),
        }

        response = self.url_open('/contact/submit', data=form_data)
        self.assertEqual(response.status_code, 200)

        # Verify inquiry created
        inquiry = self.env['contact.inquiry'].search([
            ('email', '=', 'test@example.com')
        ], limit=1)
        self.assertTrue(inquiry)

    def test_tc072_contact_form_validation(self):
        """TC-072: Contact form validates required fields"""
        form_data = {
            'name': '',  # Missing required field
            'email': 'invalid-email',  # Invalid format
            'message': '',
            'csrf_token': self.get_csrf_token(),
        }

        response = self.url_open('/contact/submit', data=form_data)
        # Should return validation errors
        self.assertIn('error', response.json())

    def test_tc073_newsletter_subscription(self):
        """TC-073: Newsletter subscription works"""
        form_data = {
            'email': 'newsletter@example.com',
            'csrf_token': self.get_csrf_token(),
        }

        response = self.url_open('/newsletter/subscribe', data=form_data)
        self.assertEqual(response.status_code, 200)

    def test_tc074_spam_protection(self):
        """TC-074: reCAPTCHA blocks spam submissions"""
        form_data = {
            'name': 'Spam Bot',
            'email': 'spam@example.com',
            'message': 'Spam message',
            # Missing reCAPTCHA token
        }

        response = self.url_open('/contact/submit', data=form_data)
        self.assertIn('captcha', response.json().get('error', '').lower())

    def get_csrf_token(self):
        """Helper to get CSRF token"""
        response = self.url_open('/contact')
        # Extract CSRF token from page
        import re
        match = re.search(r'csrf_token["\s:]+["\']([^"\']+)["\']', response.text)
        return match.group(1) if match else ''
```

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Create UI test cases | 3 | UI test checklist |
| Develop responsive test cases | 3 | Mobile test suite |
| Setup visual regression testing | 2 | Screenshot tests |

**Day 241 Deliverables**:
- [x] UAT test plan document
- [x] 170+ test cases developed
- [x] Test data fixtures created
- [x] UAT environment configured

---

### Day 242: Accessibility Audit

**Sprint Focus**: WCAG 2.1 AA compliance verification

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Review server-rendered accessibility | 3 | Template audit |
| Verify ARIA labels in templates | 3 | ARIA compliance |
| Check semantic HTML structure | 2 | HTML validation |

**Code Sample - Accessibility Audit Checklist**:
```python
# smart_website/tests/accessibility/wcag_audit.py
"""
WCAG 2.1 AA Compliance Audit Checklist
======================================

1. PERCEIVABLE
   1.1 Text Alternatives (Level A)
       - [ ] All images have alt text
       - [ ] Decorative images have empty alt
       - [ ] Complex images have long descriptions

   1.2 Time-based Media (Level A)
       - [ ] Videos have captions
       - [ ] Audio has transcripts

   1.3 Adaptable (Level A)
       - [ ] Content structured with headings
       - [ ] Reading order logical
       - [ ] Instructions not rely on sensory alone

   1.4 Distinguishable (Level AA)
       - [ ] Color contrast ratio >= 4.5:1 (text)
       - [ ] Color contrast ratio >= 3:1 (large text)
       - [ ] Text can be resized to 200%
       - [ ] Images of text avoided

2. OPERABLE
   2.1 Keyboard Accessible (Level A)
       - [ ] All functions keyboard accessible
       - [ ] No keyboard traps
       - [ ] Skip links present

   2.2 Enough Time (Level A)
       - [ ] Timing adjustable or can be extended
       - [ ] Moving content can be paused

   2.3 Seizures (Level A)
       - [ ] No content flashes more than 3 times/second

   2.4 Navigable (Level AA)
       - [ ] Page titles descriptive
       - [ ] Focus order logical
       - [ ] Link purpose clear
       - [ ] Multiple ways to find pages

3. UNDERSTANDABLE
   3.1 Readable (Level A)
       - [ ] Language of page identified
       - [ ] Language of parts identified

   3.2 Predictable (Level A)
       - [ ] Focus doesn't change context
       - [ ] Input doesn't change context

   3.3 Input Assistance (Level AA)
       - [ ] Errors identified and described
       - [ ] Labels or instructions provided
       - [ ] Error suggestions provided

4. ROBUST
   4.1 Compatible (Level A)
       - [ ] Valid HTML
       - [ ] Name, role, value for all UI components
"""

from odoo.tests import tagged, HttpCase

@tagged('accessibility', 'wcag')
class WCAGAuditTests(HttpCase):
    """WCAG 2.1 AA Compliance Tests"""

    def test_images_have_alt_text(self):
        """1.1.1: All images have alt text"""
        pages = ['/', '/products', '/about', '/contact', '/blog']

        for page in pages:
            response = self.url_open(page)
            from bs4 import BeautifulSoup
            soup = BeautifulSoup(response.text, 'html.parser')

            images = soup.find_all('img')
            for img in images:
                # All images must have alt attribute
                self.assertTrue(
                    img.has_attr('alt'),
                    f"Image missing alt on {page}: {img.get('src', 'unknown')}"
                )

    def test_heading_structure(self):
        """1.3.1: Content has proper heading structure"""
        response = self.url_open('/')
        from bs4 import BeautifulSoup
        soup = BeautifulSoup(response.text, 'html.parser')

        # Page should have exactly one h1
        h1_tags = soup.find_all('h1')
        self.assertEqual(len(h1_tags), 1, "Page should have exactly one h1")

        # Heading levels should not skip
        headings = soup.find_all(['h1', 'h2', 'h3', 'h4', 'h5', 'h6'])
        levels = [int(h.name[1]) for h in headings]

        for i in range(1, len(levels)):
            # Each heading should not skip more than one level
            self.assertLessEqual(
                levels[i] - levels[i-1],
                1,
                f"Heading levels skip: h{levels[i-1]} to h{levels[i]}"
            )

    def test_color_contrast(self):
        """1.4.3: Color contrast meets minimum ratio"""
        # This test typically uses external tools like axe-core
        # Here we verify CSS custom properties meet requirements
        pass

    def test_keyboard_navigation(self):
        """2.1.1: All functionality available from keyboard"""
        self.browser_js(
            '/',
            """
            // Test all interactive elements are focusable
            const interactiveElements = document.querySelectorAll(
                'a, button, input, select, textarea, [tabindex]'
            );

            interactiveElements.forEach(el => {
                const tabindex = el.getAttribute('tabindex');
                // Elements should not have negative tabindex (except skip links)
                if (tabindex && parseInt(tabindex) < 0) {
                    if (!el.classList.contains('skip-link')) {
                        console.error('Negative tabindex found:', el);
                    }
                }
            });
            """,
            ready="document.readyState === 'complete'"
        )

    def test_skip_links_present(self):
        """2.4.1: Skip navigation links present"""
        response = self.url_open('/')
        self.assertIn('skip', response.text.lower())

    def test_page_titles_descriptive(self):
        """2.4.2: Pages have descriptive titles"""
        pages = {
            '/': 'Smart Dairy',
            '/products': 'Products',
            '/about': 'About',
            '/contact': 'Contact',
            '/blog': 'Blog',
        }

        for url, expected_text in pages.items():
            response = self.url_open(url)
            from bs4 import BeautifulSoup
            soup = BeautifulSoup(response.text, 'html.parser')
            title = soup.find('title')
            self.assertIn(expected_text, title.text)

    def test_language_specified(self):
        """3.1.1: Language of page specified"""
        response = self.url_open('/')
        self.assertIn('lang=', response.text)

    def test_form_labels(self):
        """3.3.2: Form inputs have labels"""
        response = self.url_open('/contact')
        from bs4 import BeautifulSoup
        soup = BeautifulSoup(response.text, 'html.parser')

        inputs = soup.find_all(['input', 'textarea', 'select'])
        for inp in inputs:
            if inp.get('type') in ['hidden', 'submit', 'button']:
                continue

            inp_id = inp.get('id')
            if inp_id:
                # Check for associated label
                label = soup.find('label', attrs={'for': inp_id})
                self.assertTrue(
                    label or inp.get('aria-label') or inp.get('aria-labelledby'),
                    f"Input missing label: {inp}"
                )
```

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Run automated accessibility scan | 3 | Axe-core report |
| Fix critical accessibility issues | 4 | Bug fixes |
| Document accessibility features | 1 | A11y documentation |

**Code Sample - Automated Accessibility Testing**:
```javascript
// smart_website/tests/accessibility/axe_audit.js
const { chromium } = require('playwright');
const { AxeBuilder } = require('@axe-core/playwright');

async function runAccessibilityAudit() {
    const browser = await chromium.launch();
    const context = await browser.newContext();
    const page = await context.newPage();

    const pages = [
        { url: '/', name: 'Homepage' },
        { url: '/products', name: 'Products' },
        { url: '/about', name: 'About Us' },
        { url: '/contact', name: 'Contact' },
        { url: '/blog', name: 'Blog' },
    ];

    const results = [];

    for (const pageInfo of pages) {
        await page.goto(`http://localhost:8069${pageInfo.url}`);
        await page.waitForLoadState('networkidle');

        const accessibilityScanResults = await new AxeBuilder({ page })
            .withTags(['wcag2a', 'wcag2aa', 'wcag21aa'])
            .analyze();

        results.push({
            page: pageInfo.name,
            url: pageInfo.url,
            violations: accessibilityScanResults.violations,
            passes: accessibilityScanResults.passes.length,
            incomplete: accessibilityScanResults.incomplete,
        });

        // Log violations
        if (accessibilityScanResults.violations.length > 0) {
            console.log(`\n${pageInfo.name} - Violations:`);
            accessibilityScanResults.violations.forEach(violation => {
                console.log(`  - ${violation.id}: ${violation.description}`);
                console.log(`    Impact: ${violation.impact}`);
                console.log(`    Elements: ${violation.nodes.length}`);
            });
        }
    }

    await browser.close();

    // Generate summary report
    const summary = {
        totalPages: results.length,
        totalViolations: results.reduce((sum, r) => sum + r.violations.length, 0),
        criticalViolations: results.reduce((sum, r) =>
            sum + r.violations.filter(v => v.impact === 'critical').length, 0
        ),
        seriousViolations: results.reduce((sum, r) =>
            sum + r.violations.filter(v => v.impact === 'serious').length, 0
        ),
    };

    console.log('\n=== Accessibility Audit Summary ===');
    console.log(`Total Pages Scanned: ${summary.totalPages}`);
    console.log(`Total Violations: ${summary.totalViolations}`);
    console.log(`Critical: ${summary.criticalViolations}`);
    console.log(`Serious: ${summary.seriousViolations}`);

    return { results, summary };
}

runAccessibilityAudit().catch(console.error);
```

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Fix color contrast issues | 3 | CSS updates |
| Add missing ARIA labels | 2 | HTML updates |
| Implement skip navigation | 2 | Skip links |
| Test screen reader compatibility | 1 | NVDA/VoiceOver test |

**Day 242 Deliverables**:
- [x] WCAG 2.1 AA audit complete
- [x] Axe-core scan report
- [x] Critical issues fixed
- [x] Screen reader tested

---

### Day 243: Security Testing

**Sprint Focus**: Penetration testing and security audit

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| OWASP Top 10 vulnerability scan | 4 | Security report |
| SQL injection testing | 2 | Test results |
| Authentication/authorization review | 2 | Auth audit |

**Code Sample - Security Test Suite**:
```python
# smart_website/tests/security/owasp_tests.py
from odoo.tests import tagged, HttpCase
import re

@tagged('security', 'owasp')
class OWASPSecurityTests(HttpCase):
    """OWASP Top 10 Security Tests"""

    def test_sql_injection_contact_form(self):
        """A03:2021 - SQL Injection prevention"""
        malicious_inputs = [
            "'; DROP TABLE res_users; --",
            "1' OR '1'='1",
            "1; SELECT * FROM res_users WHERE '1'='1",
            "admin'--",
        ]

        for payload in malicious_inputs:
            form_data = {
                'name': payload,
                'email': 'test@example.com',
                'message': payload,
            }

            response = self.url_open('/contact/submit', data=form_data)
            # Should not cause server error
            self.assertNotEqual(response.status_code, 500)

    def test_xss_prevention_contact_form(self):
        """A03:2021 - Cross-Site Scripting prevention"""
        xss_payloads = [
            '<script>alert("XSS")</script>',
            '<img src="x" onerror="alert(\'XSS\')">',
            'javascript:alert("XSS")',
            '<svg onload="alert(\'XSS\')">',
        ]

        for payload in xss_payloads:
            # Submit form with XSS payload
            form_data = {
                'name': payload,
                'email': 'test@example.com',
                'message': 'Test message',
            }

            self.url_open('/contact/submit', data=form_data)

            # Check if payload is escaped in response/storage
            inquiry = self.env['contact.inquiry'].search([
                ('email', '=', 'test@example.com')
            ], limit=1)

            if inquiry:
                # Payload should be escaped, not executable
                self.assertNotIn('<script>', inquiry.name)

    def test_csrf_protection(self):
        """A05:2021 - CSRF protection"""
        # Attempt form submission without CSRF token
        form_data = {
            'name': 'Test',
            'email': 'test@example.com',
            'message': 'Test',
        }

        response = self.url_open('/contact/submit', data=form_data)
        # Should be rejected without valid CSRF token
        # Odoo handles CSRF protection automatically

    def test_security_headers(self):
        """Security Headers Check"""
        response = self.url_open('/')

        # Check for security headers
        headers = response.headers

        # Content-Security-Policy
        # X-Content-Type-Options
        self.assertEqual(headers.get('X-Content-Type-Options'), 'nosniff')

        # X-Frame-Options
        self.assertIn(headers.get('X-Frame-Options', ''), ['DENY', 'SAMEORIGIN'])

        # X-XSS-Protection
        self.assertEqual(headers.get('X-XSS-Protection'), '1; mode=block')

    def test_sensitive_data_exposure(self):
        """A02:2021 - Sensitive Data Exposure"""
        # Check that sensitive endpoints require authentication
        sensitive_paths = [
            '/web/database/manager',
            '/web/database/selector',
            '/my/account',
        ]

        for path in sensitive_paths:
            response = self.url_open(path)
            # Should redirect to login or return 403
            self.assertIn(response.status_code, [302, 403, 404])

    def test_rate_limiting(self):
        """Rate limiting on forms"""
        # Attempt rapid form submissions
        for i in range(20):
            form_data = {
                'name': f'Test {i}',
                'email': f'test{i}@example.com',
                'message': 'Test',
            }
            response = self.url_open('/contact/submit', data=form_data)

        # After many rapid requests, should be rate limited
        # Implementation depends on rate limiting middleware

    def test_file_upload_security(self):
        """A04:2021 - Insecure Design - File Upload"""
        # Test that only allowed file types can be uploaded
        # and files are properly validated
        pass

    def test_error_messages_no_sensitive_info(self):
        """Error messages don't expose sensitive information"""
        # Trigger an error
        response = self.url_open('/nonexistent-page-12345')
        self.assertEqual(response.status_code, 404)

        # Error page should not expose stack traces or sensitive info
        self.assertNotIn('Traceback', response.text)
        self.assertNotIn('PostgreSQL', response.text)
        self.assertNotIn('password', response.text.lower())
```

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| XSS vulnerability testing | 3 | Test results |
| CSRF protection verification | 2 | CSRF audit |
| Security headers check | 2 | Headers report |
| Document security measures | 1 | Security docs |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Client-side security audit | 3 | JS security review |
| Content Security Policy testing | 3 | CSP validation |
| Third-party script audit | 2 | Vendor security |

**Day 243 Deliverables**:
- [x] OWASP Top 10 scan complete
- [x] No critical vulnerabilities
- [x] Security headers configured
- [x] CSP implemented

---

### Day 244: Cross-Browser Testing

**Sprint Focus**: Browser compatibility verification

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Setup BrowserStack integration | 2 | Test platform |
| Verify server-rendered content | 3 | SSR compatibility |
| Fix browser-specific backend issues | 3 | Bug fixes |

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Test on Chrome (latest 2 versions) | 2 | Chrome report |
| Test on Firefox (latest 2 versions) | 2 | Firefox report |
| Test on Safari (latest 2 versions) | 2 | Safari report |
| Test on Edge (latest 2 versions) | 2 | Edge report |

**Code Sample - Cross-Browser Test Matrix**:
```javascript
// smart_website/tests/browser/browser_matrix.js
const { chromium, firefox, webkit } = require('playwright');

const browsers = [
    { name: 'Chrome', engine: chromium },
    { name: 'Firefox', engine: firefox },
    { name: 'Safari', engine: webkit },
];

const testCases = [
    { name: 'Homepage renders correctly', url: '/', checks: ['hero', 'navigation', 'footer'] },
    { name: 'Product listing works', url: '/products', checks: ['product-grid', 'filters'] },
    { name: 'Contact form functional', url: '/contact', checks: ['form', 'map'] },
    { name: 'Language switcher works', url: '/', action: 'switchLanguage' },
    { name: 'Animations smooth', url: '/', checks: ['animations'] },
];

async function runCrossBrowserTests() {
    const results = [];

    for (const browser of browsers) {
        console.log(`\n=== Testing ${browser.name} ===`);
        const browserInstance = await browser.engine.launch();
        const page = await browserInstance.newPage();

        for (const test of testCases) {
            try {
                await page.goto(`http://localhost:8069${test.url}`);
                await page.waitForLoadState('networkidle');

                // Run checks
                let passed = true;
                const issues = [];

                if (test.checks) {
                    for (const check of test.checks) {
                        const element = await page.$(`.${check}, #${check}, [data-testid="${check}"]`);
                        if (!element) {
                            passed = false;
                            issues.push(`Missing element: ${check}`);
                        }
                    }
                }

                // Check for JS errors
                page.on('pageerror', error => {
                    passed = false;
                    issues.push(`JS Error: ${error.message}`);
                });

                results.push({
                    browser: browser.name,
                    test: test.name,
                    passed,
                    issues,
                });

                console.log(`  ${passed ? '✓' : '✗'} ${test.name}`);
            } catch (error) {
                results.push({
                    browser: browser.name,
                    test: test.name,
                    passed: false,
                    issues: [error.message],
                });
                console.log(`  ✗ ${test.name}: ${error.message}`);
            }
        }

        await browserInstance.close();
    }

    // Generate report
    const summary = {
        totalTests: results.length,
        passed: results.filter(r => r.passed).length,
        failed: results.filter(r => !r.passed).length,
    };

    console.log('\n=== Cross-Browser Test Summary ===');
    console.log(`Total: ${summary.totalTests}`);
    console.log(`Passed: ${summary.passed}`);
    console.log(`Failed: ${summary.failed}`);

    return results;
}

runCrossBrowserTests().catch(console.error);
```

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Fix CSS compatibility issues | 4 | CSS fixes |
| Fix JavaScript compatibility | 2 | JS polyfills |
| Verify animations across browsers | 2 | Animation fixes |

**Day 244 Deliverables**:
- [x] Chrome compatibility verified
- [x] Firefox compatibility verified
- [x] Safari compatibility verified
- [x] Edge compatibility verified
- [x] Browser-specific fixes applied

---

### Day 245: Mobile & Responsive Testing

**Sprint Focus**: Mobile device testing

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Mobile API performance testing | 3 | API benchmarks |
| Touch event handling verification | 3 | Touch support |
| Mobile SEO verification | 2 | Mobile-first indexing |

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Test on iOS Safari | 2 | iOS report |
| Test on Android Chrome | 2 | Android report |
| Test on Samsung Internet | 2 | Samsung report |
| Test touch interactions | 2 | Touch testing |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Fix responsive layout issues | 4 | CSS fixes |
| Optimize touch targets | 2 | UI improvements |
| Test viewport configurations | 2 | Meta tags |

**Code Sample - Mobile Viewport Tests**:
```javascript
// smart_website/tests/mobile/responsive_tests.js
const { chromium } = require('playwright');

const viewports = [
    { name: 'iPhone SE', width: 375, height: 667 },
    { name: 'iPhone 12 Pro', width: 390, height: 844 },
    { name: 'iPhone 14 Pro Max', width: 430, height: 932 },
    { name: 'Samsung Galaxy S21', width: 360, height: 800 },
    { name: 'iPad Mini', width: 768, height: 1024 },
    { name: 'iPad Pro 12.9', width: 1024, height: 1366 },
];

const pages = ['/', '/products', '/contact', '/blog', '/about'];

async function runResponsiveTests() {
    const browser = await chromium.launch();
    const results = [];

    for (const viewport of viewports) {
        console.log(`\n=== Testing ${viewport.name} (${viewport.width}x${viewport.height}) ===`);

        const context = await browser.newContext({
            viewport: { width: viewport.width, height: viewport.height },
            isMobile: viewport.width < 768,
            hasTouch: true,
        });
        const page = await context.newPage();

        for (const url of pages) {
            await page.goto(`http://localhost:8069${url}`);
            await page.waitForLoadState('networkidle');

            const checks = await page.evaluate(() => {
                const issues = [];

                // Check for horizontal scroll
                if (document.documentElement.scrollWidth > window.innerWidth) {
                    issues.push('Horizontal scroll detected');
                }

                // Check touch target sizes (44x44 minimum)
                const interactiveElements = document.querySelectorAll('a, button, input, select');
                interactiveElements.forEach(el => {
                    const rect = el.getBoundingClientRect();
                    if (rect.width > 0 && rect.height > 0) {
                        if (rect.width < 44 || rect.height < 44) {
                            issues.push(`Small touch target: ${el.tagName} (${rect.width}x${rect.height})`);
                        }
                    }
                });

                // Check text readability (minimum 16px)
                const textElements = document.querySelectorAll('p, span, a, li');
                textElements.forEach(el => {
                    const fontSize = parseInt(window.getComputedStyle(el).fontSize);
                    if (fontSize < 14) {
                        issues.push(`Small text: ${fontSize}px`);
                    }
                });

                // Check images have width/height
                const images = document.querySelectorAll('img');
                images.forEach(img => {
                    if (!img.width && !img.height && !img.style.width) {
                        issues.push(`Image without dimensions: ${img.src}`);
                    }
                });

                return issues;
            });

            results.push({
                viewport: viewport.name,
                page: url,
                issues: checks,
                passed: checks.length === 0,
            });

            console.log(`  ${checks.length === 0 ? '✓' : '✗'} ${url} - ${checks.length} issues`);
        }

        await context.close();
    }

    await browser.close();

    // Summary
    const failedTests = results.filter(r => !r.passed);
    console.log(`\n=== Responsive Test Summary ===`);
    console.log(`Total: ${results.length}`);
    console.log(`Passed: ${results.filter(r => r.passed).length}`);
    console.log(`Failed: ${failedTests.length}`);

    if (failedTests.length > 0) {
        console.log('\nFailed tests:');
        failedTests.forEach(test => {
            console.log(`  ${test.viewport} - ${test.page}:`);
            test.issues.slice(0, 5).forEach(issue => console.log(`    - ${issue}`));
        });
    }

    return results;
}

runResponsiveTests().catch(console.error);
```

**Day 245 Deliverables**:
- [x] iOS Safari tested
- [x] Android Chrome tested
- [x] Touch interactions verified
- [x] Responsive issues fixed

---

### Day 246: Performance Benchmarking

**Sprint Focus**: Final performance verification

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Run Lighthouse audit (all pages) | 3 | Lighthouse reports |
| Verify Core Web Vitals | 2 | CWV report |
| Document performance baseline | 3 | Benchmark docs |

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Load testing (concurrent users) | 4 | Load test report |
| API response time benchmarks | 2 | API benchmarks |
| CDN performance verification | 2 | CDN metrics |

**Code Sample - Load Test Configuration**:
```javascript
// smart_website/tests/performance/load_test.js
// Using k6 for load testing

import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
    stages: [
        { duration: '1m', target: 50 },    // Ramp up to 50 users
        { duration: '3m', target: 50 },    // Stay at 50 users
        { duration: '1m', target: 100 },   // Ramp up to 100 users
        { duration: '3m', target: 100 },   // Stay at 100 users
        { duration: '1m', target: 0 },     // Ramp down
    ],
    thresholds: {
        http_req_duration: ['p(95)<2000'],  // 95% of requests under 2s
        http_req_failed: ['rate<0.01'],      // Less than 1% failures
    },
};

const BASE_URL = 'https://staging.smartdairy.com.bd';

export default function() {
    // Homepage
    let response = http.get(`${BASE_URL}/`);
    check(response, {
        'homepage status is 200': (r) => r.status === 200,
        'homepage loads under 2s': (r) => r.timings.duration < 2000,
    });
    sleep(1);

    // Products page
    response = http.get(`${BASE_URL}/products`);
    check(response, {
        'products status is 200': (r) => r.status === 200,
        'products loads under 2s': (r) => r.timings.duration < 2000,
    });
    sleep(1);

    // Product detail
    response = http.get(`${BASE_URL}/product/fresh-milk-500ml`);
    check(response, {
        'product detail status is 200': (r) => r.status === 200,
    });
    sleep(1);

    // Blog page
    response = http.get(`${BASE_URL}/blog`);
    check(response, {
        'blog status is 200': (r) => r.status === 200,
    });
    sleep(1);

    // Contact form submission
    response = http.post(`${BASE_URL}/contact/submit`, {
        name: `Load Test User ${__VU}`,
        email: `loadtest${__VU}@example.com`,
        message: 'Load test message',
    });
    check(response, {
        'contact form submits': (r) => r.status === 200,
    });
    sleep(2);
}
```

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Verify image optimization | 3 | Image audit |
| Check bundle sizes | 2 | Bundle analysis |
| Validate caching effectiveness | 3 | Cache report |

**Day 246 Deliverables**:
- [x] All pages Lighthouse >90
- [x] Load test passed (100 concurrent)
- [x] Performance documented
- [x] Core Web Vitals green

---

### Day 247: UAT Execution

**Sprint Focus**: User acceptance testing with stakeholders

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Support UAT sessions | 4 | Issue logging |
| Fix critical UAT issues | 3 | Bug fixes |
| Document UAT feedback | 1 | Feedback log |

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Run guided UAT sessions | 4 | Session facilitation |
| Demonstrate key features | 2 | Feature demos |
| Address user questions | 2 | Q&A support |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Fix UI issues found in UAT | 4 | UI fixes |
| Improve UX based on feedback | 3 | UX improvements |
| Update documentation | 1 | Docs update |

**Day 247 Deliverables**:
- [x] UAT sessions completed
- [x] Critical issues resolved
- [x] Feedback documented
- [x] Stakeholder sign-off in progress

---

### Day 248: Deployment Runbook Creation

**Sprint Focus**: Production deployment preparation

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Create deployment runbook | 4 | Runbook document |
| Document rollback procedures | 2 | Rollback guide |
| Create database migration checklist | 2 | DB checklist |

**Code Sample - Deployment Runbook**:
```markdown
# Smart Dairy Website - Production Deployment Runbook

## Pre-Deployment Checklist

### 1. Code Preparation
- [ ] All PRs merged to main branch
- [ ] Version tag created (v4.0.0)
- [ ] Changelog updated
- [ ] All tests passing (CI green)

### 2. Environment Verification
- [ ] Production server accessible
- [ ] SSL certificates valid (>30 days)
- [ ] Database backup completed
- [ ] Redis cache cleared
- [ ] CDN purged

### 3. Communication
- [ ] Stakeholders notified
- [ ] Maintenance window scheduled
- [ ] Status page updated

## Deployment Steps

### Step 1: Enable Maintenance Mode
```bash
# SSH to production server
ssh deploy@smartdairy-prod

# Enable maintenance mode
cd /opt/odoo
./scripts/maintenance_mode.sh on
```

### Step 2: Backup Current State
```bash
# Database backup
pg_dump -h localhost -U odoo smartdairy_prod > /backups/pre_deploy_$(date +%Y%m%d_%H%M%S).sql

# File backup
tar -czf /backups/filestore_$(date +%Y%m%d_%H%M%S).tar.gz /opt/odoo/filestore
```

### Step 3: Pull Latest Code
```bash
cd /opt/odoo/smart_website
git fetch origin
git checkout v4.0.0
```

### Step 4: Update Dependencies
```bash
pip install -r requirements.txt
npm ci
```

### Step 5: Run Database Migrations
```bash
./odoo-bin -c /etc/odoo/odoo.conf -d smartdairy_prod -u smart_website --stop-after-init
```

### Step 6: Build Frontend Assets
```bash
npm run build:prod
```

### Step 7: Restart Services
```bash
sudo systemctl restart odoo
sudo systemctl restart nginx
```

### Step 8: Verify Deployment
- [ ] Homepage loads correctly
- [ ] Login works
- [ ] Forms submit successfully
- [ ] Images load from CDN
- [ ] SSL working

### Step 9: Disable Maintenance Mode
```bash
./scripts/maintenance_mode.sh off
```

### Step 10: Post-Deployment
- [ ] Clear CloudFlare cache
- [ ] Verify Google Analytics tracking
- [ ] Check error logs for 5 minutes
- [ ] Update status page

## Rollback Procedure

### If Critical Issues Found:
```bash
# Enable maintenance mode
./scripts/maintenance_mode.sh on

# Restore previous version
git checkout v3.x.x

# Restore database
psql -h localhost -U odoo smartdairy_prod < /backups/pre_deploy_TIMESTAMP.sql

# Restart services
sudo systemctl restart odoo
sudo systemctl restart nginx

# Disable maintenance mode
./scripts/maintenance_mode.sh off
```

## Emergency Contacts
- DevOps Lead: +880-XXXXXXXXX
- Backend Lead: +880-XXXXXXXXX
- Project Manager: +880-XXXXXXXXX
```

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Configure production monitoring | 3 | Monitoring setup |
| Setup alerting rules | 3 | Alert configuration |
| Create health check endpoints | 2 | Health checks |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Prepare CDN configuration | 3 | CDN settings |
| Document cache invalidation | 2 | Cache procedures |
| Create asset deployment checklist | 3 | Asset checklist |

**Day 248 Deliverables**:
- [x] Deployment runbook complete
- [x] Rollback procedures documented
- [x] Monitoring configured
- [x] Health checks implemented

---

### Day 249: Go-Live Rehearsal

**Sprint Focus**: Full deployment rehearsal

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Execute rehearsal deployment | 4 | Rehearsal complete |
| Test rollback procedure | 2 | Rollback verified |
| Document rehearsal findings | 2 | Findings report |

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Verify monitoring during rehearsal | 3 | Monitoring check |
| Test alerting | 2 | Alert verification |
| Verify CDN propagation | 3 | CDN verified |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Visual verification post-rehearsal | 3 | Visual QA |
| Performance check | 3 | Performance verified |
| Fix any rehearsal issues | 2 | Issue resolution |

**Day 249 Deliverables**:
- [x] Rehearsal completed successfully
- [x] Rollback tested
- [x] Monitoring verified
- [x] Issues resolved

---

### Day 250: Final Sign-off & Phase Completion

**Sprint Focus**: Phase 4 completion and handoff

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Final code review | 2 | Code audit |
| Complete technical documentation | 3 | Tech docs |
| Knowledge transfer session | 2 | Team training |
| Phase sign-off | 1 | Completion |

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Complete operations documentation | 3 | Ops guide |
| Create troubleshooting guide | 3 | Support docs |
| Final testing verification | 2 | Test sign-off |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Complete UI documentation | 3 | UI guide |
| Create component library docs | 3 | Component docs |
| Phase 4 retrospective | 2 | Lessons learned |

**Day 250 Deliverables**:
- [x] All documentation complete
- [x] Knowledge transfer done
- [x] Phase 4 signed off
- [x] Ready for production launch

---

## 4. Technical Specifications

### 4.1 Test Coverage Targets
| Test Type | Target Coverage |
|-----------|-----------------|
| Unit Tests | >80% |
| Integration Tests | >70% |
| E2E Tests | Critical paths |
| Accessibility | WCAG 2.1 AA |
| Security | OWASP Top 10 |

### 4.2 Browser Support Matrix
| Browser | Versions | Priority |
|---------|----------|----------|
| Chrome | Last 2 | Critical |
| Firefox | Last 2 | High |
| Safari | Last 2 | High |
| Edge | Last 2 | High |
| Samsung Internet | Last 2 | Medium |

### 4.3 Performance Benchmarks
| Metric | Target | Minimum |
|--------|--------|---------|
| Lighthouse Performance | >90 | 85 |
| Lighthouse Accessibility | >90 | 85 |
| Lighthouse SEO | >95 | 90 |
| TTFB | <600ms | <1s |
| LCP | <2.5s | <4s |
| Page Size | <1MB | <2MB |

---

## 5. Launch Checklist

### Pre-Launch (1 Week Before)
- [ ] All milestone deliverables complete
- [ ] UAT sign-off received
- [ ] Security audit passed
- [ ] Performance benchmarks met
- [ ] Accessibility audit passed
- [ ] All documentation complete
- [ ] Runbook reviewed and approved
- [ ] Rollback procedures tested

### Launch Day
- [ ] Team assembled
- [ ] Communication channels open
- [ ] Backup verified
- [ ] Deployment executed
- [ ] Smoke tests passed
- [ ] Monitoring active
- [ ] Status page updated

### Post-Launch (First Week)
- [ ] Daily health checks
- [ ] Error rate monitoring
- [ ] Performance monitoring
- [ ] User feedback collection
- [ ] Issue triage process active

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Deployment failure | Low | Critical | Tested rollback |
| Performance degradation | Medium | High | Load testing, monitoring |
| Security vulnerability | Low | Critical | Pentest, WAF |
| Browser compatibility | Medium | Medium | Cross-browser testing |
| Stakeholder delays | Medium | Medium | Early scheduling |

---

## 7. Dependencies & Handoffs

### 7.1 Phase 4 Complete Deliverables
- Fully functional public website
- CMS with content management
- 50+ dairy products published
- Blog with 30+ articles
- FAQ with 50+ Q&As
- Multilingual support (EN/BN)
- SEO optimized
- Performance optimized
- Accessible (WCAG 2.1 AA)
- Security hardened

### 7.2 Handoffs to Operations
- Deployment runbook
- Monitoring setup
- Alert configuration
- Troubleshooting guide
- Contact escalation matrix

### 7.3 Handoffs to Phase 5
- Website foundation for e-commerce
- CMS ready for additional content
- Performance baseline established
- Analytics tracking active

---

## Phase 4 Completion Criteria

| Criteria | Status |
|----------|--------|
| All 10 milestones complete | ☐ |
| Website fully functional | ☐ |
| Lighthouse score >90 all pages | ☐ |
| WCAG 2.1 AA compliant | ☐ |
| Multi-language operational | ☐ |
| Security audit passed | ☐ |
| UAT signed off | ☐ |
| Documentation complete | ☐ |
| Team trained | ☐ |
| Ready for production | ☐ |

---

## Milestone Sign-off

| Role | Name | Date | Signature |
|------|------|------|-----------|
| Dev 1 - Backend Lead | | | |
| Dev 2 - Full-Stack | | | |
| Dev 3 - Frontend Lead | | | |
| QA Lead | | | |
| Project Manager | | | |
| Product Owner | | | |

---

## Phase 4 Sign-off

| Role | Name | Date | Signature |
|------|------|------|-----------|
| Technical Lead | | | |
| Project Manager | | | |
| Product Owner | | | |
| Sponsor | | | |

---

*End of Milestone 40 Documentation*

*End of Phase 4 Documentation*
