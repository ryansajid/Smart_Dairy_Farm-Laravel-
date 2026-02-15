# Milestone 37: Contact & Customer Support

## Smart Dairy Digital Smart Portal + ERP — Phase 4: Foundation - Public Website & CMS

| Field                | Detail                                                           |
| -------------------- | ---------------------------------------------------------------- |
| **Milestone**        | 37 of 40 (7 of 10 in Phase 4)                                    |
| **Title**            | Contact & Customer Support                                       |
| **Phase**            | Phase 4 — Foundation (Public Website & CMS)                      |
| **Days**             | Days 211–220 (of 250)                                            |
| **Version**          | 1.0                                                              |
| **Status**           | Draft                                                            |
| **Authors**          | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend Lead) |
| **Last Updated**     | 2026-02-03                                                       |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Build comprehensive contact and customer support infrastructure including multiple contact forms, FAQ section with search, Google Maps integration, and customer inquiry management. By Day 220, visitors can contact Smart Dairy through various channels, find answers in the FAQ, and receive timely email confirmations.

### 1.2 Objectives

1. Create main contact page with company information
2. Build contact form with validation and spam protection
3. Implement multiple inquiry type handling (general, B2B, support, careers)
4. Integrate Google Maps showing farm/office locations
5. Create office locations component with hours and contact info
6. Build FAQ section with 50+ Q&As organized by category
7. Implement FAQ search functionality
8. Create callback request form
9. Set up email notifications and confirmations
10. Conduct Milestone 37 review, demo, and retrospective

### 1.3 Key Deliverables

| # | Deliverable                           | Owner  | Format                    |
|---|---------------------------------------|--------|---------------------------|
| 1 | Contact page design                   | Dev 3  | QWeb + CSS                |
| 2 | Contact form with validation          | Dev 1  | Python + JS               |
| 3 | Multiple inquiry types                | Dev 1  | Model + workflow          |
| 4 | Google Maps integration               | Dev 2  | Maps API + JS             |
| 5 | Office locations component            | Dev 3  | QWeb + CSS                |
| 6 | FAQ page structure                    | Dev 3  | QWeb + CSS                |
| 7 | FAQ content (50+ Q&As)                | Dev 1  | Content import            |
| 8 | FAQ search functionality              | Dev 2  | JS + API                  |
| 9 | Callback request form                 | Dev 1  | Python + Form             |
| 10| Email notification templates          | Dev 2  | Email templates           |
| 11| Spam protection (reCAPTCHA)           | Dev 2  | Integration               |
| 12| Milestone 37 review report            | All    | Markdown                  |

### 1.4 Success Criteria

- [ ] Contact forms submitting and storing inquiries
- [ ] Form validation working (client + server side)
- [ ] Google Maps showing farm/office locations
- [ ] FAQ section with 50+ Q&As searchable
- [ ] Email notifications sending on form submit
- [ ] Spam protection active (reCAPTCHA)
- [ ] All forms accessible (WCAG 2.1 AA)

---

## 2. Requirement Traceability Matrix

| Req ID        | Source     | Requirement Description                           | Day(s)  | Task Reference              |
| ------------- | ---------- | ------------------------------------------------- | ------- | --------------------------- |
| RFP-WEB-005   | RFP §4     | 100+ B2B leads per month via forms                | 211-214 | Contact forms               |
| BRD-SUPP-001  | BRD §5     | Customer support infrastructure                   | 211-220 | FAQ, contact forms          |
| SRS-FORM-001  | SRS §4.9   | Contact form with validation                      | 212-213 | Form components             |
| SRS-FORM-002  | SRS §4.9   | Spam protection                                   | 213     | reCAPTCHA                   |
| SRS-FAQ-001   | SRS §4.10  | FAQ with 50+ questions                            | 216-218 | FAQ system                  |

---

## 3. Day-by-Day Breakdown

---

### Day 211 — Contact Page Design

**Objective:** Create the main contact page layout with all information and design elements.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create contact inquiry model — 3h
2. Design inquiry types and routing — 2.5h
3. Build contact page controller — 2.5h

```python
# Contact Inquiry Model
from odoo import models, fields, api
from odoo.exceptions import ValidationError
import re

class ContactInquiry(models.Model):
    _name = 'website.contact.inquiry'
    _description = 'Contact Inquiry'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'create_date desc'

    name = fields.Char('Full Name', required=True)
    email = fields.Char('Email', required=True)
    phone = fields.Char('Phone')
    company = fields.Char('Company Name')

    inquiry_type = fields.Selection([
        ('general', 'General Inquiry'),
        ('product', 'Product Information'),
        ('b2b', 'B2B Partnership'),
        ('wholesale', 'Wholesale Inquiry'),
        ('support', 'Customer Support'),
        ('complaint', 'Complaint'),
        ('feedback', 'Feedback'),
        ('careers', 'Career Inquiry'),
        ('media', 'Media/Press'),
    ], string='Inquiry Type', required=True, default='general')

    subject = fields.Char('Subject', required=True)
    message = fields.Text('Message', required=True)

    # Tracking
    source = fields.Selection([
        ('contact_page', 'Contact Page'),
        ('product_page', 'Product Page'),
        ('footer', 'Footer Form'),
        ('popup', 'Popup Form'),
        ('callback', 'Callback Request'),
    ], string='Source', default='contact_page')

    # Status
    state = fields.Selection([
        ('new', 'New'),
        ('assigned', 'Assigned'),
        ('in_progress', 'In Progress'),
        ('responded', 'Responded'),
        ('closed', 'Closed'),
    ], default='new', string='Status', tracking=True)

    assigned_to = fields.Many2one('res.users', string='Assigned To')
    response_date = fields.Datetime('Response Date')
    response_time_hours = fields.Float('Response Time (Hours)', compute='_compute_response_time')

    # Anti-spam
    ip_address = fields.Char('IP Address')
    user_agent = fields.Char('User Agent')
    recaptcha_score = fields.Float('reCAPTCHA Score')

    # Related
    product_id = fields.Many2one('product.template', string='Related Product')
    order_ref = fields.Char('Order Reference')

    @api.constrains('email')
    def _check_email(self):
        for record in self:
            if record.email:
                pattern = r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$'
                if not re.match(pattern, record.email):
                    raise ValidationError('Please enter a valid email address.')

    @api.depends('create_date', 'response_date')
    def _compute_response_time(self):
        for record in self:
            if record.create_date and record.response_date:
                delta = record.response_date - record.create_date
                record.response_time_hours = delta.total_seconds() / 3600
            else:
                record.response_time_hours = 0

    @api.model
    def create(self, vals):
        record = super().create(vals)
        record._auto_assign()
        record._send_confirmation_email()
        record._notify_team()
        return record

    def _auto_assign(self):
        """Auto-assign based on inquiry type"""
        self.ensure_one()
        assignment_map = {
            'b2b': 'sales_team',
            'wholesale': 'sales_team',
            'support': 'support_team',
            'complaint': 'support_team',
            'careers': 'hr_team',
            'media': 'marketing_team',
        }
        team = assignment_map.get(self.inquiry_type, 'general_team')
        # Assign to team (implementation depends on team structure)

    def _send_confirmation_email(self):
        """Send confirmation email to customer"""
        template = self.env.ref('smart_dairy_contact.email_template_inquiry_confirmation')
        template.send_mail(self.id, force_send=True)

    def _notify_team(self):
        """Notify internal team about new inquiry"""
        template = self.env.ref('smart_dairy_contact.email_template_new_inquiry_internal')
        template.send_mail(self.id, force_send=True)

    def action_respond(self):
        """Mark as responded"""
        self.write({
            'state': 'responded',
            'response_date': fields.Datetime.now(),
        })
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Set up Google Maps API key — 2h
2. Create maps embed component — 3h
3. Configure email sending — 3h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design contact page layout — 4h
2. Create contact info cards — 2h
3. Style page sections — 2h

```scss
// _contact.scss - Contact Page Styling

.sd-contact-page {
    padding: var(--spacing-12) 0;
}

.sd-contact-hero {
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
    color: white;
    padding: var(--spacing-16) 0;
    text-align: center;
    margin-bottom: var(--spacing-12);

    .contact-hero-title {
        font-size: var(--font-size-4xl);
        font-weight: 700;
        margin-bottom: var(--spacing-4);
    }

    .contact-hero-subtitle {
        font-size: var(--font-size-lg);
        opacity: 0.9;
    }
}

.sd-contact-info-grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: var(--spacing-6);
    margin-bottom: var(--spacing-12);

    @include respond-to('md') {
        grid-template-columns: repeat(2, 1fr);
    }

    @include respond-to('lg') {
        grid-template-columns: repeat(4, 1fr);
    }
}

.sd-contact-info-card {
    background: var(--color-bg-primary);
    padding: var(--spacing-8);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    text-align: center;
    transition: transform var(--transition-normal), box-shadow var(--transition-normal);

    &:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .info-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto var(--spacing-4);
        background: var(--color-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--font-size-2xl);
        color: white;
    }

    .info-title {
        font-size: var(--font-size-lg);
        font-weight: 600;
        margin-bottom: var(--spacing-2);
    }

    .info-content {
        color: var(--color-text-secondary);

        a {
            color: var(--color-primary);
            text-decoration: none;

            &:hover {
                text-decoration: underline;
            }
        }
    }
}

.sd-contact-main {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--spacing-10);

    @include respond-to('lg') {
        grid-template-columns: 1fr 1fr;
    }
}

.sd-contact-form-section {
    background: var(--color-bg-primary);
    padding: var(--spacing-10);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);

    .form-section-title {
        font-size: var(--font-size-2xl);
        font-weight: 700;
        margin-bottom: var(--spacing-2);
    }

    .form-section-subtitle {
        color: var(--color-text-secondary);
        margin-bottom: var(--spacing-8);
    }
}

.sd-contact-map-section {
    .map-container {
        height: 400px;
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-lg);

        @include respond-to('lg') {
            height: 100%;
            min-height: 500px;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    }

    .location-details {
        margin-top: var(--spacing-6);
        padding: var(--spacing-6);
        background: var(--color-bg-secondary);
        border-radius: var(--radius-lg);
    }
}

// Contact Form Styling
.sd-contact-form {
    .form-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: var(--spacing-4);

        @include respond-to('sm') {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .form-group-full {
        grid-column: 1 / -1;
    }

    .form-submit {
        margin-top: var(--spacing-6);
    }
}

// Business Hours
.sd-business-hours {
    margin-top: var(--spacing-8);

    .hours-title {
        font-size: var(--font-size-lg);
        font-weight: 600;
        margin-bottom: var(--spacing-4);
    }

    .hours-list {
        display: grid;
        gap: var(--spacing-2);

        .hours-row {
            display: flex;
            justify-content: space-between;
            padding: var(--spacing-2) 0;
            border-bottom: 1px solid var(--color-border-light);

            &.today {
                font-weight: 600;
                color: var(--color-primary);
            }
        }
    }
}
```

**End-of-Day 211 Deliverables:**

- [ ] Contact page layout complete
- [ ] Contact inquiry model created
- [ ] Google Maps API configured
- [ ] Contact info cards styled

---

### Day 212 — Contact Form Implementation

**Objective:** Build the main contact form with full validation and submission handling.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create form submission controller — 3h
2. Implement server-side validation — 2.5h
3. Build form field sanitization — 2.5h

```python
# Contact Form Controller
from odoo import http
from odoo.http import request
import json

class ContactFormController(http.Controller):

    @http.route('/contact/submit', type='json', auth='public', website=True, csrf=True)
    def submit_contact_form(self, **kwargs):
        """Handle contact form submission"""
        try:
            # Validate required fields
            required_fields = ['name', 'email', 'subject', 'message', 'inquiry_type']
            for field in required_fields:
                if not kwargs.get(field):
                    return {'success': False, 'error': f'{field.title()} is required.'}

            # Validate email
            email = kwargs.get('email', '').strip().lower()
            if not self._validate_email(email):
                return {'success': False, 'error': 'Please enter a valid email address.'}

            # Validate reCAPTCHA
            recaptcha_token = kwargs.get('recaptcha_token')
            if not self._verify_recaptcha(recaptcha_token):
                return {'success': False, 'error': 'Security verification failed. Please try again.'}

            # Sanitize inputs
            values = {
                'name': self._sanitize(kwargs.get('name')),
                'email': email,
                'phone': self._sanitize(kwargs.get('phone', '')),
                'company': self._sanitize(kwargs.get('company', '')),
                'inquiry_type': kwargs.get('inquiry_type'),
                'subject': self._sanitize(kwargs.get('subject')),
                'message': self._sanitize(kwargs.get('message')),
                'source': kwargs.get('source', 'contact_page'),
                'ip_address': request.httprequest.remote_addr,
                'user_agent': request.httprequest.user_agent.string[:255],
            }

            # Optional: related product
            if kwargs.get('product_id'):
                values['product_id'] = int(kwargs.get('product_id'))

            # Create inquiry
            inquiry = request.env['website.contact.inquiry'].sudo().create(values)

            return {
                'success': True,
                'message': 'Thank you for contacting us! We will respond within 24 hours.',
                'inquiry_id': inquiry.id,
            }

        except Exception as e:
            return {'success': False, 'error': 'An error occurred. Please try again later.'}

    def _validate_email(self, email):
        import re
        pattern = r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$'
        return bool(re.match(pattern, email))

    def _verify_recaptcha(self, token):
        """Verify reCAPTCHA v3 token"""
        if not token:
            return False

        import requests
        secret_key = request.env['ir.config_parameter'].sudo().get_param('recaptcha_secret_key')
        if not secret_key:
            return True  # Skip if not configured

        response = requests.post(
            'https://www.google.com/recaptcha/api/siteverify',
            data={'secret': secret_key, 'response': token}
        )
        result = response.json()
        return result.get('success', False) and result.get('score', 0) >= 0.5

    def _sanitize(self, value):
        """Sanitize input to prevent XSS"""
        if not value:
            return ''
        import html
        return html.escape(str(value).strip())
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create contact form JavaScript — 3h
2. Implement client-side validation — 2.5h
3. Build form submission handling — 2.5h

```javascript
// Contact Form Handler
class SmartDairyContactForm {
    constructor(form) {
        this.form = form;
        this.submitBtn = form.querySelector('[type="submit"]');
        this.messageContainer = form.querySelector('.form-message');
        this.init();
    }

    init() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        this.initValidation();
        this.initRecaptcha();
    }

    initValidation() {
        const inputs = this.form.querySelectorAll('[data-validate]');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearError(input));
        });
    }

    async initRecaptcha() {
        // Load reCAPTCHA v3
        const siteKey = this.form.dataset.recaptchaSiteKey;
        if (!siteKey) return;

        await this.loadRecaptchaScript(siteKey);
    }

    loadRecaptchaScript(siteKey) {
        return new Promise((resolve) => {
            const script = document.createElement('script');
            script.src = `https://www.google.com/recaptcha/api.js?render=${siteKey}`;
            script.onload = resolve;
            document.head.appendChild(script);
        });
    }

    validateField(input) {
        const rules = input.dataset.validate.split(',');
        const value = input.value.trim();
        const fieldName = input.dataset.fieldName || input.name;

        for (const rule of rules) {
            let error = null;

            switch (rule.trim()) {
                case 'required':
                    if (!value) error = `${fieldName} is required`;
                    break;
                case 'email':
                    if (value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                        error = 'Please enter a valid email address';
                    }
                    break;
                case 'phone':
                    if (value && !/^[\d\s\-\+\(\)]+$/.test(value)) {
                        error = 'Please enter a valid phone number';
                    }
                    break;
                case 'min:10':
                    if (value && value.length < 10) {
                        error = `${fieldName} must be at least 10 characters`;
                    }
                    break;
            }

            if (error) {
                this.showError(input, error);
                return false;
            }
        }

        this.showSuccess(input);
        return true;
    }

    showError(input, message) {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');

        let feedback = input.parentNode.querySelector('.invalid-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            input.parentNode.appendChild(feedback);
        }
        feedback.textContent = message;
    }

    showSuccess(input) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
    }

    clearError(input) {
        if (input.classList.contains('is-invalid')) {
            input.classList.remove('is-invalid');
        }
    }

    validateForm() {
        const inputs = this.form.querySelectorAll('[data-validate]');
        let isValid = true;

        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    async handleSubmit(e) {
        e.preventDefault();

        if (!this.validateForm()) {
            this.showMessage('Please correct the errors above.', 'error');
            return;
        }

        this.setLoading(true);

        try {
            // Get reCAPTCHA token
            const siteKey = this.form.dataset.recaptchaSiteKey;
            let recaptchaToken = null;
            if (siteKey && window.grecaptcha) {
                recaptchaToken = await grecaptcha.execute(siteKey, { action: 'contact' });
            }

            const formData = new FormData(this.form);
            const data = Object.fromEntries(formData.entries());
            data.recaptcha_token = recaptchaToken;

            const response = await fetch('/contact/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    jsonrpc: '2.0',
                    method: 'call',
                    params: data,
                }),
            });

            const result = await response.json();

            if (result.result?.success) {
                this.showMessage(result.result.message, 'success');
                this.form.reset();
                this.trackSubmission(data.inquiry_type);
            } else {
                this.showMessage(result.result?.error || 'An error occurred.', 'error');
            }
        } catch (error) {
            this.showMessage('Network error. Please try again.', 'error');
        } finally {
            this.setLoading(false);
        }
    }

    setLoading(loading) {
        this.submitBtn.disabled = loading;
        if (loading) {
            this.submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Sending...';
        } else {
            this.submitBtn.innerHTML = 'Send Message';
        }
    }

    showMessage(text, type) {
        if (this.messageContainer) {
            this.messageContainer.className = `form-message alert alert-${type === 'success' ? 'success' : 'danger'}`;
            this.messageContainer.textContent = text;
            this.messageContainer.style.display = 'block';

            if (type === 'success') {
                setTimeout(() => {
                    this.messageContainer.style.display = 'none';
                }, 5000);
            }
        }
    }

    trackSubmission(inquiryType) {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'contact_form_submit', {
                event_category: 'contact',
                event_label: inquiryType,
            });
        }
    }
}

// Initialize
document.querySelectorAll('.sd-contact-form').forEach(form => {
    new SmartDairyContactForm(form);
});
```

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Style contact form inputs — 3h
2. Create form validation styles — 2.5h
3. Design success/error messages — 2.5h

**End-of-Day 212 Deliverables:**

- [ ] Contact form functional
- [ ] Client-side validation working
- [ ] Server-side validation active
- [ ] Success/error messaging styled

---

### Day 213 — Multiple Inquiry Types & Spam Protection

**Objective:** Implement specialized forms for different inquiry types and integrate spam protection.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create inquiry type-specific forms — 4h
2. Implement inquiry routing logic — 2h
3. Build email templates per type — 2h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Integrate Google reCAPTCHA v3 — 3h
2. Create honeypot fields — 1.5h
3. Implement rate limiting — 3.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Create B2B inquiry form — 3h
2. Create support inquiry form — 2.5h
3. Style inquiry type selector — 2.5h

**End-of-Day 213 Deliverables:**

- [ ] Multiple inquiry forms working
- [ ] reCAPTCHA integrated
- [ ] Honeypot spam protection
- [ ] Rate limiting active

---

### Day 214 — Google Maps Integration

**Objective:** Fully integrate Google Maps showing Smart Dairy farm and office locations.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create locations model — 2.5h
2. Build locations API — 2.5h
3. Configure map markers data — 3h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create interactive Google Map — 4h
2. Build custom map markers — 2h
3. Implement directions link — 2h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Style map container — 2.5h
2. Create location info windows — 3h
3. Design directions button — 2.5h

**End-of-Day 214 Deliverables:**

- [ ] Google Map showing locations
- [ ] Custom Smart Dairy markers
- [ ] Location info windows
- [ ] Get directions functionality

---

### Day 215 — Office Locations Component

**Objective:** Build a comprehensive office locations component with details and business hours.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create business hours model — 3h
2. Build hours API with timezone — 2.5h
3. Implement "open now" indicator — 2.5h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create location cards component — 3h
2. Build open/closed indicator — 2.5h
3. Implement click-to-call — 2.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design location cards — 3h
2. Style business hours display — 2.5h
3. Create location tabs — 2.5h

**End-of-Day 215 Deliverables:**

- [ ] Location cards displaying
- [ ] Business hours shown
- [ ] Open/closed indicator working
- [ ] Click-to-call functional

---

### Day 216 — FAQ Page Structure

**Objective:** Build the FAQ page structure with category organization and accordion display.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create FAQ model — 3h
2. Build FAQ categories — 2h
3. Create FAQ API — 3h

```python
# FAQ Model
from odoo import models, fields, api

class FAQCategory(models.Model):
    _name = 'website.faq.category'
    _description = 'FAQ Category'
    _order = 'sequence'

    name = fields.Char('Category Name', required=True, translate=True)
    description = fields.Text('Description', translate=True)
    icon = fields.Char('Icon Class')
    sequence = fields.Integer('Sequence', default=10)
    faq_count = fields.Integer('FAQ Count', compute='_compute_faq_count')

    @api.depends()
    def _compute_faq_count(self):
        for cat in self:
            cat.faq_count = self.env['website.faq'].search_count([
                ('category_id', '=', cat.id),
                ('website_published', '=', True),
            ])


class FAQ(models.Model):
    _name = 'website.faq'
    _description = 'Frequently Asked Question'
    _order = 'sequence, id'

    question = fields.Char('Question', required=True, translate=True)
    answer = fields.Html('Answer', required=True, translate=True, sanitize=False)
    category_id = fields.Many2one('website.faq.category', string='Category', required=True)

    sequence = fields.Integer('Sequence', default=10)
    website_published = fields.Boolean('Published', default=True)

    # Tracking
    view_count = fields.Integer('Views', default=0)
    helpful_yes = fields.Integer('Helpful: Yes', default=0)
    helpful_no = fields.Integer('Helpful: No', default=0)

    # SEO
    meta_title = fields.Char('Meta Title', translate=True)
    meta_description = fields.Text('Meta Description', translate=True)

    # Related
    related_faq_ids = fields.Many2many('website.faq', 'faq_related_rel', 'faq_id', 'related_id', string='Related FAQs')
    related_product_ids = fields.Many2many('product.template', string='Related Products')

    def action_helpful(self, helpful):
        """Track helpful feedback"""
        if helpful:
            self.sudo().write({'helpful_yes': self.helpful_yes + 1})
        else:
            self.sudo().write({'helpful_no': self.helpful_no + 1})
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create accordion component — 3h
2. Build category filtering — 2.5h
3. Implement FAQ schema.org — 2.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design FAQ page layout — 3h
2. Style accordion component — 3h
3. Create category navigation — 2h

**End-of-Day 216 Deliverables:**

- [ ] FAQ page structure complete
- [ ] Accordion displaying Q&As
- [ ] Categories navigable
- [ ] FAQ schema.org markup

---

### Day 217 — FAQ Content Import (50+ Q&As)

**Objective:** Import and organize 50+ frequently asked questions across categories.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create FAQ import script — 2h
2. Import 50+ FAQs — 4h
3. Organize FAQs by category — 2h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create FAQ helpful feedback — 3h
2. Build related FAQs component — 2.5h
3. Implement FAQ tracking — 2.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Review FAQ content display — 3h
2. Style helpful feedback buttons — 2.5h
3. Test all FAQ interactions — 2.5h

**End-of-Day 217 Deliverables:**

- [ ] 50+ FAQs imported
- [ ] FAQs organized in 6+ categories
- [ ] Helpful feedback working
- [ ] All FAQs displaying correctly

---

### Day 218 — FAQ Search Functionality

**Objective:** Implement FAQ search with instant results and highlighting.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create FAQ search API — 3h
2. Implement search indexing — 2.5h
3. Build search result ranking — 2.5h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create instant search component — 3h
2. Implement search highlighting — 2.5h
3. Build search analytics — 2.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design search bar — 2.5h
2. Style search results — 3h
3. Create no results state — 2.5h

**End-of-Day 218 Deliverables:**

- [ ] FAQ search functional
- [ ] Instant search results
- [ ] Search highlighting
- [ ] Search analytics tracking

---

### Day 219 — Callback Request & Email Notifications

**Objective:** Build callback request form and finalize all email notification templates.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create callback request model — 3h
2. Build callback scheduling — 2.5h
3. Finalize email templates — 2.5h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create callback form — 3h
2. Build time slot picker — 2.5h
3. Test all email notifications — 2.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design callback form — 3h
2. Style time slot picker — 2.5h
3. Test all form experiences — 2.5h

**End-of-Day 219 Deliverables:**

- [ ] Callback request functional
- [ ] Time slot picker working
- [ ] All emails sending correctly
- [ ] Email templates branded

---

### Day 220 — Milestone 37 Review & Testing

**Objective:** Comprehensive testing and milestone review.

#### All Developers (8h each)

**Combined Tasks:**

1. Test all contact forms — 2.5h
2. Validate FAQ functionality — 2h
3. Test email deliverability — 1.5h
4. Demo to stakeholders — 1.5h
5. Retrospective and MS-38 planning — 0.5h

**End-of-Day 220 Deliverables:**

- [ ] All forms submitting correctly
- [ ] 50+ FAQs searchable
- [ ] Maps integration working
- [ ] Emails delivering
- [ ] Milestone 37 sign-off

---

## 4. Technical Specifications

### 4.1 Inquiry Types

| Type | Routing | SLA |
| ---- | ------- | --- |
| General | Support Team | 48h |
| B2B | Sales Team | 24h |
| Support | Support Team | 24h |
| Complaint | Support Manager | 4h |
| Careers | HR Team | 72h |

### 4.2 FAQ Categories

| Category | Target Q&As |
| -------- | ----------- |
| Products & Ordering | 10+ |
| Delivery & Shipping | 8+ |
| Quality & Safety | 8+ |
| Nutrition & Health | 8+ |
| B2B & Wholesale | 8+ |
| Account & Payments | 8+ |

---

## 5. Testing & Validation

- [ ] All forms submit without errors
- [ ] Validation catches invalid input
- [ ] Spam protection working
- [ ] Emails deliver to inbox (not spam)
- [ ] FAQ search returns relevant results
- [ ] Maps load and display correctly

---

**END OF MILESTONE 37**

**Next Milestone**: Milestone_38_SEO_Performance.md
