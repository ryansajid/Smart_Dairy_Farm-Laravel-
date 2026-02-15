# Milestone 39: Multilingual Support (Bangla)

## Document Control
| Field | Value |
|-------|-------|
| **Milestone Number** | 39 |
| **Title** | Multilingual Support (Bangla) |
| **Phase** | 4 - Public Website & CMS |
| **Timeline** | Days 231-240 (10 working days) |
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
Implement comprehensive Bangla (Bengali) language support for the Smart Dairy website, enabling full bilingual content delivery for the Bangladeshi market.

### 1.2 Objectives
1. Configure Odoo multilingual framework for English and Bangla
2. Implement translation workflow for CMS content
3. Create language switcher component
4. Translate all static UI elements
5. Setup translation memory for consistency
6. Configure Bangla-specific typography and fonts
7. Implement hreflang tags for SEO

### 1.3 Key Deliverables
- Bangla language module configuration
- Translation management interface
- Language switcher in header/footer
- 100% UI string translations
- Homepage and key pages translated
- Translation workflow documentation
- Bangla typography optimization

### 1.4 Prerequisites
- Milestone 38 completed (SEO infrastructure)
- All English content finalized
- Noto Sans Bengali font available
- Translation team identified

### 1.5 Success Criteria
| Criteria | Target | Measurement |
|----------|--------|-------------|
| UI Translations | 100% | Translation coverage |
| Key Pages Translated | 15+ pages | Content audit |
| Language Switcher | Functional | User testing |
| Hreflang Tags | All pages | SEO validation |
| Font Rendering | Correct display | Visual QA |

---

## 2. Requirement Traceability Matrix

| Req ID | Source | Requirement | Implementation |
|--------|--------|-------------|----------------|
| RFP-LANG-001 | RFP 4.3 | Bangla language support | Full translation system |
| BRD-MKT-003 | BRD 3.5 | Local market appeal | Bangla content |
| SRS-I18N-001 | SRS 5.4 | Multi-language CMS | Translation workflow |
| SRS-I18N-002 | SRS 5.4 | Language switcher | Header component |
| SRS-SEO-003 | SRS 5.3 | Hreflang implementation | SEO tags |

---

## 3. Day-by-Day Breakdown

### Day 231: Multilingual Framework Setup

**Sprint Focus**: Configure Odoo i18n infrastructure

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Configure Bangla language in Odoo | 2 | Language activated |
| Setup translation module structure | 3 | Module skeleton |
| Create translation model extensions | 2 | Translatable fields |
| Configure language detection | 1 | URL/cookie detection |

**Code Sample - Language Configuration**:
```python
# smart_website/models/res_config_settings.py
from odoo import models, fields, api

class ResConfigSettings(models.TransientModel):
    _inherit = 'res.config.settings'

    website_default_lang_id = fields.Many2one(
        'res.lang',
        string='Default Website Language',
        related='website_id.default_lang_id',
        readonly=False
    )
    website_language_ids = fields.Many2many(
        'res.lang',
        string='Website Languages',
        related='website_id.language_ids',
        readonly=False
    )

    def _setup_bangla_language(self):
        """Activate and configure Bangla language"""
        Lang = self.env['res.lang']

        # Activate Bangla if not already active
        bangla = Lang.search([('code', '=', 'bn_BD')], limit=1)
        if not bangla:
            Lang._activate_lang('bn_BD')
            bangla = Lang.search([('code', '=', 'bn_BD')], limit=1)

        # Configure Bangla settings
        bangla.write({
            'name': 'Bangla (Bangladesh)',
            'direction': 'ltr',  # Bangla is LTR
            'date_format': '%d/%m/%Y',
            'time_format': '%H:%M:%S',
            'decimal_point': '.',
            'thousands_sep': ',',
        })

        return bangla


# smart_website/models/website.py
class Website(models.Model):
    _inherit = 'website'

    def _get_current_language(self):
        """Get current language from URL or cookie"""
        lang_code = request.httprequest.cookies.get('frontend_lang')
        if not lang_code:
            # Check URL prefix
            path = request.httprequest.path
            if path.startswith('/bn/'):
                lang_code = 'bn_BD'
            else:
                lang_code = 'en_US'

        return self.env['res.lang'].search([('code', '=', lang_code)], limit=1)

    def get_alternate_languages(self):
        """Get list of alternate language URLs for hreflang"""
        base_url = self.env['ir.config_parameter'].sudo().get_param('web.base.url')
        current_path = request.httprequest.path

        # Remove language prefix if present
        clean_path = current_path
        for prefix in ['/en/', '/bn/']:
            if current_path.startswith(prefix):
                clean_path = current_path[3:]
                break

        alternates = []
        for lang in self.language_ids:
            if lang.code == 'en_US':
                url = f"{base_url}/en{clean_path}"
                hreflang = 'en'
            elif lang.code == 'bn_BD':
                url = f"{base_url}/bn{clean_path}"
                hreflang = 'bn'
            else:
                continue

            alternates.append({
                'hreflang': hreflang,
                'url': url
            })

        # Add x-default
        alternates.append({
            'hreflang': 'x-default',
            'url': f"{base_url}{clean_path}"
        })

        return alternates
```

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Setup URL routing for languages | 3 | /en/, /bn/ prefixes |
| Implement language cookie handler | 2 | Preference storage |
| Create language redirect logic | 2 | Geo/browser detection |
| Configure session language | 1 | User preference |

**Code Sample - Language Router**:
```python
# smart_website/controllers/language.py
from odoo import http
from odoo.http import request

class LanguageController(http.Controller):

    @http.route('/set-language/<string:lang_code>', type='http', auth='public', website=True)
    def set_language(self, lang_code, redirect_url='/'):
        """Set user language preference"""
        valid_codes = ['en_US', 'bn_BD']

        if lang_code not in valid_codes:
            lang_code = 'en_US'

        # Set cookie for 1 year
        response = request.redirect(redirect_url)
        response.set_cookie(
            'frontend_lang',
            lang_code,
            max_age=31536000,  # 1 year
            httponly=True,
            secure=True
        )

        return response

    @http.route(['/', '/<path:page>'], type='http', auth='public', website=True)
    def route_with_language(self, page='', **kwargs):
        """Route handler with language prefix support"""
        # This is handled by Odoo's built-in routing
        # Custom logic for language detection
        pass

    @http.route('/api/v1/languages', type='json', auth='public')
    def get_available_languages(self):
        """API endpoint for available languages"""
        website = request.website
        languages = []

        for lang in website.language_ids:
            languages.append({
                'code': lang.code,
                'name': lang.name,
                'url_code': 'bn' if lang.code == 'bn_BD' else 'en',
                'is_current': lang == request.env.lang,
                'flag_url': f'/smart_website/static/img/flags/{lang.code}.png'
            })

        return languages
```

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Setup Noto Sans Bengali font | 2 | Font integration |
| Create typography styles for Bangla | 3 | CSS adjustments |
| Implement font loading optimization | 2 | Performance |
| Test Bangla rendering | 1 | Visual verification |

**Code Sample - Bangla Typography**:
```scss
/* smart_website/static/src/scss/i18n/_bangla.scss */

// Bangla Font Family
@font-face {
    font-family: 'Noto Sans Bengali';
    font-style: normal;
    font-weight: 400;
    font-display: swap;
    src: url('/smart_website/static/fonts/NotoSansBengali-Regular.woff2') format('woff2');
    unicode-range: U+0980-09FF;
}

@font-face {
    font-family: 'Noto Sans Bengali';
    font-style: normal;
    font-weight: 600;
    font-display: swap;
    src: url('/smart_website/static/fonts/NotoSansBengali-SemiBold.woff2') format('woff2');
    unicode-range: U+0980-09FF;
}

@font-face {
    font-family: 'Noto Sans Bengali';
    font-style: normal;
    font-weight: 700;
    font-display: swap;
    src: url('/smart_website/static/fonts/NotoSansBengali-Bold.woff2') format('woff2');
    unicode-range: U+0980-09FF;
}

// Language-specific styles
:root {
    --font-family-bangla: 'Noto Sans Bengali', 'Kalpurush', sans-serif;
}

html[lang="bn"],
html[lang="bn-BD"],
.lang-bn {
    // Font family with Bangla fallback
    font-family: var(--font-family-primary), var(--font-family-bangla);

    // Slightly larger base font for Bangla readability
    font-size: 17px;

    // Adjusted line height for Bangla script
    line-height: 1.8;

    // Headings
    h1, h2, h3, h4, h5, h6 {
        line-height: 1.5;
        font-weight: 600;
    }

    // Adjust letter spacing for Bangla
    p, li, span {
        letter-spacing: 0.01em;
    }

    // Form inputs
    input, textarea, select {
        font-family: inherit;
        font-size: 16px;
    }

    // Buttons
    .btn {
        font-weight: 600;
        letter-spacing: 0;
    }
}

// Mixed content handling (English + Bangla)
.mixed-content {
    font-family: var(--font-family-primary), var(--font-family-bangla);

    // English text spans
    .en-text {
        font-family: var(--font-family-primary);
    }

    // Bangla text spans
    .bn-text {
        font-family: var(--font-family-bangla);
    }
}

// Number formatting for Bangla
.bn-numbers {
    // Use Bengali numerals
    font-feature-settings: "tnum" on;
}
```

**Day 231 Deliverables**:
- [x] Bangla language activated in Odoo
- [x] URL routing configured (/en/, /bn/)
- [x] Noto Sans Bengali font integrated
- [x] Language cookie handling

---

### Day 232: Translation Management System

**Sprint Focus**: Build translation workflow interface

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Create translation manager model | 3 | `translation.manager` |
| Implement translation status tracking | 2 | Workflow states |
| Build batch translation API | 2 | Bulk operations |
| Add translation memory | 1 | Consistency tool |

**Code Sample - Translation Manager**:
```python
# smart_website/models/translation_manager.py
from odoo import models, fields, api

class TranslationManager(models.Model):
    _name = 'translation.manager'
    _description = 'Translation Management'
    _order = 'priority desc, create_date desc'

    name = fields.Char(required=True)
    model_name = fields.Char(required=True, index=True)
    record_id = fields.Integer(required=True, index=True)
    field_name = fields.Char(required=True)

    source_text = fields.Text(required=True)
    source_lang = fields.Selection([
        ('en_US', 'English'),
        ('bn_BD', 'Bangla'),
    ], default='en_US')

    translation_ids = fields.One2many(
        'translation.manager.line',
        'manager_id',
        string='Translations'
    )

    state = fields.Selection([
        ('pending', 'Pending Translation'),
        ('in_progress', 'In Progress'),
        ('review', 'Under Review'),
        ('approved', 'Approved'),
        ('published', 'Published'),
    ], default='pending', index=True)

    priority = fields.Selection([
        ('low', 'Low'),
        ('medium', 'Medium'),
        ('high', 'High'),
        ('urgent', 'Urgent'),
    ], default='medium')

    translator_id = fields.Many2one('res.users', string='Translator')
    reviewer_id = fields.Many2one('res.users', string='Reviewer')

    @api.model
    def create_translation_request(self, model, record_id, field_name, source_text):
        """Create a translation request for a field"""
        existing = self.search([
            ('model_name', '=', model),
            ('record_id', '=', record_id),
            ('field_name', '=', field_name),
        ], limit=1)

        if existing:
            existing.write({'source_text': source_text})
            return existing

        return self.create({
            'name': f"Translate {model}/{record_id}/{field_name}",
            'model_name': model,
            'record_id': record_id,
            'field_name': field_name,
            'source_text': source_text,
        })

    def action_submit_translation(self):
        """Submit translation for review"""
        self.write({'state': 'review'})

    def action_approve(self):
        """Approve translation and apply to record"""
        for record in self:
            for line in record.translation_ids:
                if line.state == 'approved':
                    record._apply_translation(line)
            record.write({'state': 'approved'})

    def action_publish(self):
        """Publish approved translations"""
        self.filtered(lambda r: r.state == 'approved').write({'state': 'published'})

    def _apply_translation(self, translation_line):
        """Apply translation to the actual record"""
        Model = self.env[self.model_name]
        record = Model.browse(self.record_id)

        if record.exists():
            # Use Odoo's built-in translation mechanism
            record.with_context(lang=translation_line.target_lang).write({
                self.field_name: translation_line.translated_text
            })


class TranslationManagerLine(models.Model):
    _name = 'translation.manager.line'
    _description = 'Translation Line'

    manager_id = fields.Many2one('translation.manager', required=True, ondelete='cascade')
    target_lang = fields.Selection([
        ('en_US', 'English'),
        ('bn_BD', 'Bangla'),
    ], required=True)
    translated_text = fields.Text()
    state = fields.Selection([
        ('draft', 'Draft'),
        ('pending_review', 'Pending Review'),
        ('approved', 'Approved'),
        ('rejected', 'Rejected'),
    ], default='draft')

    translator_notes = fields.Text()
    reviewer_notes = fields.Text()

    @api.model
    def suggest_from_memory(self, source_text, target_lang):
        """Suggest translation from translation memory"""
        # Search for similar translations
        similar = self.search([
            ('target_lang', '=', target_lang),
            ('state', '=', 'approved'),
        ])

        # Simple matching - could be enhanced with fuzzy matching
        for trans in similar:
            if trans.manager_id.source_text == source_text:
                return trans.translated_text

        return None
```

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Create translation admin interface | 4 | Backend views |
| Implement translation export/import | 2 | PO file support |
| Add translation progress dashboard | 2 | Status overview |

**Code Sample - Translation Views**:
```xml
<!-- smart_website/views/translation_manager_views.xml -->
<odoo>
    <!-- Translation Manager Tree View -->
    <record id="translation_manager_tree" model="ir.ui.view">
        <field name="name">translation.manager.tree</field>
        <field name="model">translation.manager</field>
        <field name="arch" type="xml">
            <tree>
                <field name="name"/>
                <field name="model_name"/>
                <field name="field_name"/>
                <field name="priority" decoration-danger="priority == 'urgent'" decoration-warning="priority == 'high'"/>
                <field name="state" widget="badge"
                       decoration-info="state == 'pending'"
                       decoration-warning="state == 'in_progress'"
                       decoration-success="state == 'published'"/>
                <field name="translator_id"/>
                <field name="create_date"/>
            </tree>
        </field>
    </record>

    <!-- Translation Manager Form View -->
    <record id="translation_manager_form" model="ir.ui.view">
        <field name="name">translation.manager.form</field>
        <field name="model">translation.manager</field>
        <field name="arch" type="xml">
            <form>
                <header>
                    <button name="action_submit_translation" type="object"
                            string="Submit for Review" class="btn-primary"
                            states="pending,in_progress"/>
                    <button name="action_approve" type="object"
                            string="Approve" class="btn-success"
                            states="review" groups="smart_website.group_translation_reviewer"/>
                    <button name="action_publish" type="object"
                            string="Publish" class="btn-primary"
                            states="approved"/>
                    <field name="state" widget="statusbar"
                           statusbar_visible="pending,in_progress,review,approved,published"/>
                </header>
                <sheet>
                    <group>
                        <group>
                            <field name="name"/>
                            <field name="model_name" readonly="1"/>
                            <field name="record_id" readonly="1"/>
                            <field name="field_name" readonly="1"/>
                        </group>
                        <group>
                            <field name="priority"/>
                            <field name="translator_id"/>
                            <field name="reviewer_id"/>
                        </group>
                    </group>

                    <notebook>
                        <page string="Source Text">
                            <field name="source_lang"/>
                            <field name="source_text" readonly="1"/>
                        </page>
                        <page string="Translations">
                            <field name="translation_ids">
                                <tree editable="bottom">
                                    <field name="target_lang"/>
                                    <field name="translated_text"/>
                                    <field name="state" widget="badge"/>
                                    <field name="translator_notes"/>
                                </tree>
                            </field>
                        </page>
                    </notebook>
                </sheet>
            </form>
        </field>
    </record>

    <!-- Translation Dashboard Action -->
    <record id="action_translation_dashboard" model="ir.actions.act_window">
        <field name="name">Translation Dashboard</field>
        <field name="res_model">translation.manager</field>
        <field name="view_mode">tree,form,kanban</field>
        <field name="context">{'search_default_pending': 1}</field>
    </record>

    <!-- Menu Item -->
    <menuitem id="menu_translation_management"
              name="Translation Management"
              parent="website.menu_website_configuration"
              sequence="50"/>
    <menuitem id="menu_translation_dashboard"
              name="Translation Dashboard"
              parent="menu_translation_management"
              action="action_translation_dashboard"/>
</odoo>
```

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Create language switcher component | 4 | Dropdown widget |
| Add language switcher to header | 2 | Template integration |
| Add language switcher to footer | 1 | Footer placement |
| Style language switcher | 1 | CSS styling |

**Code Sample - Language Switcher Component**:
```javascript
// smart_website/static/src/js/language_switcher.js
/** @odoo-module */

import { Component, useState } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class LanguageSwitcher extends Component {
    static template = "smart_website.LanguageSwitcher";

    setup() {
        this.state = useState({
            isOpen: false,
            currentLang: this.getCurrentLanguage(),
            languages: this.getAvailableLanguages(),
        });
    }

    getCurrentLanguage() {
        const htmlLang = document.documentElement.lang;
        return htmlLang === 'bn' || htmlLang === 'bn-BD' ? 'bn_BD' : 'en_US';
    }

    getAvailableLanguages() {
        return [
            {
                code: 'en_US',
                name: 'English',
                shortName: 'EN',
                flag: '/smart_website/static/img/flags/en.svg',
                urlPrefix: '/en'
            },
            {
                code: 'bn_BD',
                name: 'বাংলা',
                shortName: 'বাং',
                flag: '/smart_website/static/img/flags/bn.svg',
                urlPrefix: '/bn'
            }
        ];
    }

    get currentLanguageData() {
        return this.state.languages.find(l => l.code === this.state.currentLang) || this.state.languages[0];
    }

    get otherLanguages() {
        return this.state.languages.filter(l => l.code !== this.state.currentLang);
    }

    toggleDropdown() {
        this.state.isOpen = !this.state.isOpen;
    }

    closeDropdown() {
        this.state.isOpen = false;
    }

    switchLanguage(langCode) {
        const currentPath = window.location.pathname;
        const language = this.state.languages.find(l => l.code === langCode);

        if (!language) return;

        // Remove current language prefix and add new one
        let newPath = currentPath;
        for (const lang of this.state.languages) {
            if (currentPath.startsWith(lang.urlPrefix + '/')) {
                newPath = currentPath.substring(lang.urlPrefix.length);
                break;
            }
        }

        // Add new language prefix
        newPath = language.urlPrefix + newPath;

        // Navigate to new URL
        window.location.href = newPath + window.location.search;
    }
}

LanguageSwitcher.template = xml`
    <div class="language-switcher" t-on-click.stop="">
        <button class="language-switcher__toggle"
                t-on-click="toggleDropdown"
                aria-haspopup="true"
                t-att-aria-expanded="state.isOpen">
            <img t-att-src="currentLanguageData.flag"
                 t-att-alt="currentLanguageData.name"
                 class="language-switcher__flag"/>
            <span class="language-switcher__name" t-esc="currentLanguageData.shortName"/>
            <svg class="language-switcher__arrow" viewBox="0 0 24 24">
                <path d="M7 10l5 5 5-5z"/>
            </svg>
        </button>

        <ul t-if="state.isOpen"
            class="language-switcher__dropdown"
            role="menu">
            <t t-foreach="otherLanguages" t-as="lang" t-key="lang.code">
                <li class="language-switcher__item"
                    role="menuitem"
                    t-on-click="() => this.switchLanguage(lang.code)">
                    <img t-att-src="lang.flag"
                         t-att-alt="lang.name"
                         class="language-switcher__flag"/>
                    <span class="language-switcher__name" t-esc="lang.name"/>
                </li>
            </t>
        </ul>
    </div>
`;
```

**Code Sample - Language Switcher Styles**:
```scss
/* smart_website/static/src/scss/components/_language_switcher.scss */
.language-switcher {
    position: relative;
    display: inline-flex;
    align-items: center;

    &__toggle {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        background: transparent;
        border: 1px solid var(--color-border);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s ease;

        &:hover {
            background: var(--color-surface-hover);
            border-color: var(--color-primary);
        }
    }

    &__flag {
        width: 20px;
        height: 15px;
        object-fit: cover;
        border-radius: 2px;
    }

    &__name {
        font-size: 0.875rem;
        font-weight: 500;
    }

    &__arrow {
        width: 16px;
        height: 16px;
        fill: currentColor;
        transition: transform 0.2s ease;

        [aria-expanded="true"] & {
            transform: rotate(180deg);
        }
    }

    &__dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 0.25rem;
        padding: 0.5rem 0;
        background: white;
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        list-style: none;
        min-width: 150px;
        z-index: 1000;

        animation: dropdown-fade-in 0.15s ease-out;
    }

    &__item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        cursor: pointer;
        transition: background 0.15s ease;

        &:hover {
            background: var(--color-surface-hover);
        }
    }
}

@keyframes dropdown-fade-in {
    from {
        opacity: 0;
        transform: translateY(-4px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
```

**Day 232 Deliverables**:
- [x] Translation manager model
- [x] Translation admin interface
- [x] Language switcher component
- [x] Header/footer integration

---

### Day 233: UI String Translation

**Sprint Focus**: Translate all static UI elements

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Extract translatable strings | 3 | PO file generation |
| Setup translation memory database | 2 | Glossary system |
| Create translation API for frontend | 2 | JSON translations |
| Implement string interpolation | 1 | Variable support |

**Code Sample - Translation Utilities**:
```python
# smart_website/models/translation_utils.py
from odoo import models, api, _
import json

class TranslationUtils(models.AbstractModel):
    _name = 'translation.utils'
    _description = 'Translation Utilities'

    @api.model
    def get_frontend_translations(self, lang_code='en_US'):
        """Get all frontend translatable strings as JSON"""
        translations = {}

        # Static UI strings
        ui_strings = {
            # Navigation
            'nav.home': _('Home'),
            'nav.products': _('Products'),
            'nav.about': _('About Us'),
            'nav.blog': _('Blog'),
            'nav.contact': _('Contact'),
            'nav.services': _('Services'),

            # Common actions
            'action.read_more': _('Read More'),
            'action.learn_more': _('Learn More'),
            'action.view_all': _('View All'),
            'action.submit': _('Submit'),
            'action.send': _('Send'),
            'action.search': _('Search'),
            'action.filter': _('Filter'),
            'action.clear': _('Clear'),
            'action.back': _('Back'),
            'action.next': _('Next'),
            'action.previous': _('Previous'),

            # Forms
            'form.name': _('Name'),
            'form.email': _('Email'),
            'form.phone': _('Phone'),
            'form.message': _('Message'),
            'form.subject': _('Subject'),
            'form.required': _('Required'),
            'form.optional': _('Optional'),

            # Products
            'product.category': _('Category'),
            'product.price': _('Price'),
            'product.details': _('Product Details'),
            'product.nutrition': _('Nutritional Information'),
            'product.ingredients': _('Ingredients'),
            'product.related': _('Related Products'),

            # Blog
            'blog.author': _('Author'),
            'blog.published': _('Published'),
            'blog.tags': _('Tags'),
            'blog.share': _('Share'),
            'blog.comments': _('Comments'),

            # Contact
            'contact.address': _('Address'),
            'contact.phone': _('Phone'),
            'contact.email': _('Email'),
            'contact.hours': _('Business Hours'),
            'contact.send_message': _('Send us a message'),

            # Footer
            'footer.copyright': _('© 2026 Smart Dairy. All rights reserved.'),
            'footer.privacy': _('Privacy Policy'),
            'footer.terms': _('Terms of Service'),
            'footer.follow_us': _('Follow Us'),

            # Messages
            'msg.success': _('Success!'),
            'msg.error': _('An error occurred'),
            'msg.loading': _('Loading...'),
            'msg.no_results': _('No results found'),
            'msg.thank_you': _('Thank you for your message'),
        }

        # Get translations for specified language
        with self.env.cr.savepoint():
            self = self.with_context(lang=lang_code)
            for key, value in ui_strings.items():
                translations[key] = str(value)

        return translations

    @api.model
    def export_po_file(self, module_name='smart_website'):
        """Export translations as PO file"""
        IrTranslation = self.env['ir.translation']

        translations = IrTranslation.search([
            ('name', 'like', f'{module_name},%'),
            ('lang', '=', 'bn_BD'),
        ])

        po_content = self._generate_po_header()

        for trans in translations:
            po_content += f'''
#: {trans.name}
msgid "{trans.src}"
msgstr "{trans.value or ''}"
'''

        return po_content

    def _generate_po_header(self):
        return '''# Smart Dairy Website Translation
# Copyright (C) 2026 Smart Dairy
msgid ""
msgstr ""
"Content-Type: text/plain; charset=UTF-8\\n"
"Language: bn_BD\\n"

'''
```

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Create translation JSON endpoint | 2 | API endpoint |
| Implement frontend translation loader | 3 | JS module |
| Add translation caching | 2 | LocalStorage cache |
| Test translation loading | 1 | Verification |

**Code Sample - Frontend Translation Loader**:
```javascript
// smart_website/static/src/js/i18n.js
/** @odoo-module */

class I18n {
    constructor() {
        this.translations = {};
        this.currentLang = 'en_US';
        this.loaded = false;
    }

    async init(langCode = null) {
        this.currentLang = langCode || this.detectLanguage();
        await this.loadTranslations();
        this.loaded = true;
    }

    detectLanguage() {
        // Check HTML lang attribute
        const htmlLang = document.documentElement.lang;
        if (htmlLang === 'bn' || htmlLang === 'bn-BD') {
            return 'bn_BD';
        }

        // Check cookie
        const cookie = document.cookie.split(';')
            .find(c => c.trim().startsWith('frontend_lang='));
        if (cookie) {
            return cookie.split('=')[1];
        }

        return 'en_US';
    }

    async loadTranslations() {
        // Check cache first
        const cacheKey = `translations_${this.currentLang}`;
        const cached = localStorage.getItem(cacheKey);
        const cacheTime = localStorage.getItem(`${cacheKey}_time`);

        // Use cache if less than 1 hour old
        if (cached && cacheTime && (Date.now() - parseInt(cacheTime)) < 3600000) {
            this.translations = JSON.parse(cached);
            return;
        }

        // Fetch from server
        try {
            const response = await fetch(`/api/v1/translations/${this.currentLang}`);
            this.translations = await response.json();

            // Cache the translations
            localStorage.setItem(cacheKey, JSON.stringify(this.translations));
            localStorage.setItem(`${cacheKey}_time`, Date.now().toString());
        } catch (error) {
            console.error('Failed to load translations:', error);
            this.translations = {};
        }
    }

    t(key, params = {}) {
        let text = this.translations[key] || key;

        // Replace parameters {name} with values
        Object.keys(params).forEach(param => {
            text = text.replace(new RegExp(`\\{${param}\\}`, 'g'), params[param]);
        });

        return text;
    }

    // Alias for t()
    _(key, params = {}) {
        return this.t(key, params);
    }

    formatNumber(num) {
        if (this.currentLang === 'bn_BD') {
            return new Intl.NumberFormat('bn-BD').format(num);
        }
        return new Intl.NumberFormat('en-US').format(num);
    }

    formatDate(date) {
        if (this.currentLang === 'bn_BD') {
            return new Intl.DateTimeFormat('bn-BD', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }).format(date);
        }
        return new Intl.DateTimeFormat('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }).format(date);
    }

    formatCurrency(amount) {
        if (this.currentLang === 'bn_BD') {
            return new Intl.NumberFormat('bn-BD', {
                style: 'currency',
                currency: 'BDT'
            }).format(amount);
        }
        return new Intl.NumberFormat('en-BD', {
            style: 'currency',
            currency: 'BDT'
        }).format(amount);
    }
}

// Create singleton instance
const i18n = new I18n();

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    i18n.init();
});

export { i18n, I18n };
```

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Translate navigation strings | 2 | Nav translations |
| Translate footer strings | 2 | Footer translations |
| Translate form labels | 2 | Form translations |
| Translate common UI elements | 2 | Button/message translations |

**Day 233 Deliverables**:
- [x] PO file generation
- [x] Frontend translation loader
- [x] Navigation translated
- [x] Common UI strings translated

---

### Day 234: Content Translation - Homepage & About

**Sprint Focus**: Translate key landing pages

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Translate homepage hero section | 2 | Bangla hero content |
| Translate homepage features | 2 | Feature translations |
| Translate About Us page | 3 | About translations |
| Review translation accuracy | 1 | Quality check |

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Translate Vision/Mission/Values | 2 | Company values |
| Translate Team section | 2 | Team bios |
| Translate Company Timeline | 2 | History content |
| Translate Certifications page | 2 | Quality certifications |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Test Bangla text rendering | 3 | Visual QA |
| Fix typography issues | 3 | CSS adjustments |
| Test responsive layouts with Bangla | 2 | Mobile testing |

**Day 234 Deliverables**:
- [x] Homepage fully translated
- [x] About Us pages translated
- [x] Typography verified
- [x] Responsive layout tested

---

### Day 235: Content Translation - Products & Services

**Sprint Focus**: Product catalog translation

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Create product translation workflow | 2 | Batch translation |
| Translate product categories | 2 | 6 category translations |
| Translate product names (50+ items) | 3 | Product title translations |
| Translate product descriptions | 1 | Start descriptions |

**Code Sample - Product Translation Batch**:
```python
# smart_website/wizards/product_translation_wizard.py
from odoo import models, fields, api

class ProductTranslationWizard(models.TransientModel):
    _name = 'product.translation.wizard'
    _description = 'Batch Product Translation'

    product_ids = fields.Many2many(
        'product.template',
        string='Products to Translate'
    )
    target_lang = fields.Selection([
        ('bn_BD', 'Bangla'),
    ], default='bn_BD', required=True)

    def action_create_translation_requests(self):
        """Create translation requests for selected products"""
        TranslationManager = self.env['translation.manager']

        for product in self.product_ids:
            # Name translation
            TranslationManager.create_translation_request(
                'product.template',
                product.id,
                'name',
                product.name
            )

            # Description translation
            if product.description_sale:
                TranslationManager.create_translation_request(
                    'product.template',
                    product.id,
                    'description_sale',
                    product.description_sale
                )

            # Website description
            if product.website_description:
                TranslationManager.create_translation_request(
                    'product.template',
                    product.id,
                    'website_description',
                    product.website_description
                )

        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': 'Translation Requests Created',
                'message': f'{len(self.product_ids)} product translation requests created.',
                'type': 'success',
            }
        }
```

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Translate Services pages | 4 | 4 service translations |
| Translate nutritional info labels | 2 | Product details |
| Translate filter/sort labels | 2 | UI elements |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Test product cards with Bangla | 3 | Card layout |
| Test product detail pages | 3 | Detail layout |
| Fix text overflow issues | 2 | CSS fixes |

**Day 235 Deliverables**:
- [x] Product categories translated
- [x] Product names translated
- [x] Services pages translated
- [x] Layout issues fixed

---

### Day 236: Content Translation - Blog & FAQ

**Sprint Focus**: Blog and support content translation

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Translate blog categories | 2 | Blog taxonomy |
| Translate 10 key blog posts | 4 | Priority articles |
| Create translation queue for remaining | 2 | Backlog |

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Translate FAQ categories | 2 | FAQ taxonomy |
| Translate 25 priority FAQs | 4 | Key FAQs |
| Translate remaining FAQs | 2 | Complete FAQ set |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Test blog layout with Bangla | 3 | Blog pages |
| Test FAQ accordion with Bangla | 3 | FAQ component |
| Fix any rendering issues | 2 | Visual fixes |

**Day 236 Deliverables**:
- [x] Blog categories translated
- [x] 10 blog posts translated
- [x] 50 FAQs translated
- [x] Layout verified

---

### Day 237: Contact & Legal Pages Translation

**Sprint Focus**: Complete remaining page translations

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Translate Contact page | 2 | Contact content |
| Translate email templates | 3 | Auto-reply emails |
| Translate error messages | 2 | System messages |
| Translate success messages | 1 | Confirmation messages |

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Translate Privacy Policy | 3 | Legal document |
| Translate Terms of Service | 3 | Legal document |
| Translate Cookie Policy | 2 | Legal document |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Test contact form in Bangla | 3 | Form validation |
| Test legal page layouts | 3 | Long text rendering |
| Verify print styles | 2 | Print layout |

**Day 237 Deliverables**:
- [x] Contact page translated
- [x] Legal pages translated
- [x] Email templates translated
- [x] Form messages translated

---

### Day 238: Hreflang & SEO for Multilingual

**Sprint Focus**: SEO optimization for Bangla content

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Implement hreflang tag generator | 3 | Language alternates |
| Update sitemap for multilingual | 3 | Dual-language sitemap |
| Create Bangla meta tag defaults | 2 | SEO configuration |

**Code Sample - Hreflang Implementation**:
```python
# smart_website/models/seo_multilingual.py
from odoo import models, api

class SEOMultilingual(models.AbstractModel):
    _inherit = 'seo.mixin'

    def get_hreflang_tags(self):
        """Generate hreflang tags for multilingual SEO"""
        website = self.env['website'].get_current_website()
        base_url = self.env['ir.config_parameter'].sudo().get_param('web.base.url')

        # Get current page URL without language prefix
        current_url = self._get_canonical_url()

        hreflang_tags = []

        for lang in website.language_ids:
            lang_url = self._build_language_url(current_url, lang)

            hreflang_tags.append({
                'hreflang': self._get_hreflang_code(lang.code),
                'href': f"{base_url}{lang_url}"
            })

        # Add x-default (usually English)
        hreflang_tags.append({
            'hreflang': 'x-default',
            'href': f"{base_url}{current_url}"
        })

        return hreflang_tags

    def _get_hreflang_code(self, lang_code):
        """Convert Odoo lang code to hreflang code"""
        mapping = {
            'en_US': 'en',
            'bn_BD': 'bn',
        }
        return mapping.get(lang_code, lang_code.split('_')[0])

    def _build_language_url(self, url, lang):
        """Build URL with language prefix"""
        if lang.code == 'en_US':
            return f"/en{url}"
        elif lang.code == 'bn_BD':
            return f"/bn{url}"
        return url
```

**Code Sample - Hreflang Template**:
```xml
<!-- smart_website/views/templates/hreflang.xml -->
<template id="hreflang_tags" name="Hreflang Tags">
    <t t-set="hreflang_tags" t-value="page.get_hreflang_tags() if page else []"/>
    <t t-foreach="hreflang_tags" t-as="tag">
        <link rel="alternate"
              t-att-hreflang="tag['hreflang']"
              t-att-href="tag['href']"/>
    </t>
</template>
```

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Update XML sitemap for both languages | 3 | Bilingual sitemap |
| Submit updated sitemap to Search Console | 2 | Google indexing |
| Configure language-specific analytics | 3 | GA4 segments |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Verify hreflang tags on all pages | 3 | SEO audit |
| Test language switching SEO | 2 | URL structure |
| Validate with Google testing tools | 3 | Verification |

**Day 238 Deliverables**:
- [x] Hreflang tags implemented
- [x] Bilingual sitemap generated
- [x] Search Console updated
- [x] SEO validation complete

---

### Day 239: Translation QA & Testing

**Sprint Focus**: Quality assurance for translations

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Run translation completeness audit | 3 | Coverage report |
| Fix missing translations | 3 | Gap filling |
| Verify translation memory | 2 | Consistency check |

**Code Sample - Translation Audit**:
```python
# smart_website/models/translation_audit.py
from odoo import models, api

class TranslationAudit(models.AbstractModel):
    _name = 'translation.audit'
    _description = 'Translation Audit Service'

    @api.model
    def run_coverage_audit(self):
        """Audit translation coverage"""
        results = {
            'total_strings': 0,
            'translated': 0,
            'missing': [],
            'coverage_percent': 0,
        }

        IrTranslation = self.env['ir.translation']

        # Check website module translations
        source_terms = IrTranslation.search([
            ('name', 'like', 'smart_website,%'),
            ('lang', '=', 'en_US'),
            ('type', '=', 'model'),
        ])

        results['total_strings'] = len(source_terms)

        for term in source_terms:
            bn_trans = IrTranslation.search([
                ('name', '=', term.name),
                ('lang', '=', 'bn_BD'),
                ('res_id', '=', term.res_id),
            ], limit=1)

            if bn_trans and bn_trans.value:
                results['translated'] += 1
            else:
                results['missing'].append({
                    'name': term.name,
                    'source': term.src,
                    'res_id': term.res_id,
                })

        if results['total_strings'] > 0:
            results['coverage_percent'] = (
                results['translated'] / results['total_strings'] * 100
            )

        return results

    @api.model
    def get_untranslated_content(self, model_name, limit=50):
        """Get list of untranslated content for a model"""
        Model = self.env[model_name]

        # Get translatable fields
        translatable_fields = [
            fname for fname, field in Model._fields.items()
            if getattr(field, 'translate', False)
        ]

        untranslated = []
        records = Model.search([], limit=limit)

        for record in records:
            for field in translatable_fields:
                en_value = record.with_context(lang='en_US')[field]
                bn_value = record.with_context(lang='bn_BD')[field]

                if en_value and (not bn_value or bn_value == en_value):
                    untranslated.append({
                        'model': model_name,
                        'record_id': record.id,
                        'field': field,
                        'english': en_value,
                    })

        return untranslated
```

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Test language switching flow | 3 | User journey |
| Verify cookie persistence | 2 | Session handling |
| Test browser language detection | 2 | Auto-detection |
| Fix any switching bugs | 1 | Bug fixes |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Visual QA all pages in Bangla | 4 | Page review |
| Fix typography issues | 2 | CSS corrections |
| Test print layouts | 2 | Print verification |

**Day 239 Deliverables**:
- [x] Translation coverage >95%
- [x] Language switching verified
- [x] Visual QA complete
- [x] Typography issues fixed

---

### Day 240: Final Review & Documentation

**Sprint Focus**: Polish and handoff

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Final translation review | 3 | Quality sign-off |
| Create translation guide | 3 | Documentation |
| Knowledge transfer | 2 | Team training |

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Document translation workflow | 3 | Process guide |
| Create glossary document | 2 | Terminology reference |
| Update SEO documentation | 2 | Multilingual SEO |
| Final testing | 1 | Verification |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Document typography guidelines | 3 | Style guide |
| Create component translation guide | 2 | Developer guide |
| Cross-browser final test | 2 | Compatibility |
| Milestone sign-off | 1 | Completion |

**Day 240 Deliverables**:
- [x] Translation guide documentation
- [x] Glossary created
- [x] All documentation updated
- [x] Milestone 39 complete

---

## 4. Technical Specifications

### 4.1 Language Configuration
```yaml
languages:
  english:
    code: en_US
    name: English
    url_prefix: /en
    hreflang: en
    direction: ltr
    default: true

  bangla:
    code: bn_BD
    name: বাংলা
    url_prefix: /bn
    hreflang: bn
    direction: ltr  # Bangla is LTR
    font_family: 'Noto Sans Bengali'
```

### 4.2 Font Configuration
```css
/* Primary fonts */
--font-family-primary: 'Inter', -apple-system, sans-serif;
--font-family-bangla: 'Noto Sans Bengali', 'Kalpurush', sans-serif;

/* Bangla-specific adjustments */
[lang="bn"] {
    font-size: 17px;      /* Slightly larger for readability */
    line-height: 1.8;     /* More line spacing */
    letter-spacing: 0.01em;
}
```

### 4.3 Translation Coverage Targets
| Content Type | Target | Priority |
|--------------|--------|----------|
| UI Strings | 100% | Critical |
| Homepage | 100% | Critical |
| Product Names | 100% | High |
| Product Descriptions | 80% | High |
| Blog Posts | 30% (10 posts) | Medium |
| FAQs | 100% | High |
| Legal Pages | 100% | Critical |
| Error Messages | 100% | Critical |

---

## 5. Testing & Validation

### 5.1 Translation Testing
- [ ] All UI strings translated
- [ ] No untranslated placeholders
- [ ] Grammar and spelling verified
- [ ] Terminology consistent
- [ ] Context-appropriate translations

### 5.2 Functional Testing
- [ ] Language switcher works
- [ ] Cookie persistence verified
- [ ] URL routing correct (/en/, /bn/)
- [ ] Hreflang tags present
- [ ] Sitemap includes both languages

### 5.3 Visual Testing
- [ ] Font rendering correct
- [ ] No text overflow
- [ ] Responsive layouts work
- [ ] Print styles work
- [ ] PDF generation works

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Translation quality issues | Medium | High | Professional review |
| Font loading slow | Low | Medium | Font preloading, subsetting |
| Layout breaks with Bangla | Medium | Medium | Extensive testing |
| Missing translations in production | Low | High | Automated audit |
| SEO ranking drop | Low | Medium | Proper hreflang implementation |

---

## 7. Dependencies & Handoffs

### 7.1 Prerequisites from Milestone 38
- SEO infrastructure ready
- Performance optimization complete
- CDN configured

### 7.2 Handoffs to Milestone 40
- All translations complete
- Language switching functional
- SEO for multilingual verified

### 7.3 External Dependencies
- Professional translators for review
- Noto Sans Bengali font files
- Translation memory tool access

---

## Bangla Translation Glossary

| English | Bangla | Context |
|---------|--------|---------|
| Smart Dairy | স্মার্ট ডেইরি | Brand name (keep English or transliterate) |
| Products | পণ্য | Navigation |
| Services | সেবা | Navigation |
| About Us | আমাদের সম্পর্কে | Navigation |
| Contact | যোগাযোগ | Navigation |
| Blog | ব্লগ | Navigation |
| Fresh Milk | টাটকা দুধ | Product |
| Yogurt | দই | Product |
| Butter | মাখন | Product |
| Cheese | পনির | Product |
| Read More | আরও পড়ুন | CTA |
| Learn More | আরও জানুন | CTA |
| Submit | জমা দিন | Form |
| Search | অনুসন্ধান | UI |

---

## Milestone Sign-off

| Role | Name | Date | Signature |
|------|------|------|-----------|
| Dev 1 - Backend Lead | | | |
| Dev 2 - Full-Stack | | | |
| Dev 3 - Frontend Lead | | | |
| Project Manager | | | |
| Product Owner | | | |

---

*End of Milestone 39 Documentation*
