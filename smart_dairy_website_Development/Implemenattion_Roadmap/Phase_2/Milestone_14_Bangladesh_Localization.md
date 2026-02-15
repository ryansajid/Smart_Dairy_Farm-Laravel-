# Milestone 14: Bangladesh Localization Deep Implementation

## Smart Dairy Digital Smart Portal + ERP — Phase 2: ERP Core Configuration

| Field            | Detail                                                        |
|------------------|---------------------------------------------------------------|
| **Milestone**    | 14 of 20 (4 of 10 in Phase 2)                                |
| **Title**        | Bangladesh Localization Deep Implementation                   |
| **Phase**        | Phase 2 — ERP Core Configuration (Part A: Advanced ERP)       |
| **Days**         | Days 131–140 (of 200)                                        |
| **Version**      | 1.0                                                          |
| **Status**       | Draft                                                        |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend/Mobile Lead) |
| **Last Updated** | 2026-02-03                                                   |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Implement comprehensive Bangladesh localization for Smart Dairy's ERP system, including Bangladesh-specific Chart of Accounts (IFRS compliant), VAT configuration with 15% standard rate and exempt categories, automated Mushak form generation (Mushak-9.1 VAT Return, Mushak-6.3 VAT Invoice, Mushak-6.10 Monthly Summary), Withholding Tax (TDS) calculation and reporting, Bengali (Bangla) language pack with 95%+ UI coverage, BDT currency formatting, Bangladesh fiscal year (July-June), and payroll localization compliant with Bangladesh Labor Act 2006.

### 1.2 Objectives

1. Configure Bangladesh Chart of Accounts following IFRS standards
2. Set up VAT rates (15% standard, exempt categories for raw milk)
3. Implement Mushak-9.1 VAT Return form generation
4. Implement Mushak-6.3 VAT Invoice generation
5. Implement Mushak-6.10 Monthly Summary report
6. Configure TDS/Withholding Tax calculation and deduction
7. Complete Bengali language translation (95%+ coverage)
8. Configure BDT currency formatting and display
9. Set up Bangladesh fiscal year (July 1 - June 30)
10. Implement Bangladesh payroll rules (PF, gratuity, tax slabs)

### 1.3 Key Deliverables

| # | Deliverable | Owner | Day |
|---|-------------|-------|-----|
| D14.1 | Bangladesh Chart of Accounts | Dev 1 | 131 |
| D14.2 | VAT configuration (15%, exemptions) | Dev 1 | 131-132 |
| D14.3 | Mushak-9.1 VAT Return | Dev 1 | 132-133 |
| D14.4 | Mushak-6.3 VAT Invoice | Dev 1 | 133-134 |
| D14.5 | Mushak-6.10 Monthly Summary | Dev 1 | 134 |
| D14.6 | TDS/Withholding Tax module | Dev 1 | 135 |
| D14.7 | Bengali language pack | Dev 3 | 135-137 |
| D14.8 | BDT currency configuration | Dev 2 | 137 |
| D14.9 | Fiscal year (July-June) | Dev 2 | 138 |
| D14.10 | Payroll localization | Dev 1 | 138-139 |
| D14.11 | Local bank formats | Dev 2 | 139 |
| D14.12 | Validation and testing | All | 140 |

### 1.4 Success Criteria

- [ ] Mushak-9.1, 6.3, 6.10 forms generate correctly (verified by accountant)
- [ ] VAT calculations accurate for all product categories
- [ ] Bengali UI 95%+ complete with language toggle
- [ ] TDS deductions calculate correctly per Bangladesh tax law
- [ ] Payroll generates compliant pay slips with statutory deductions
- [ ] Fiscal year reports align to July-June calendar
- [ ] BDT formatting displays correctly (৳ symbol, comma placement)

---

## 2. Requirement Traceability Matrix

### 2.1 BRD Requirements Mapping

| BRD Req ID | Requirement Description | SRS Trace | Day | Owner |
|-----------|------------------------|-----------|-----|-------|
| BRD-LOC-001 | Bangladesh Chart of Accounts | SRS-ACC-001 | 131 | Dev 1 |
| BRD-LOC-002 | VAT configuration | SRS-ACC-002 | 131-132 | Dev 1 |
| BRD-LOC-003 | Mushak-9.1 form | SRS-ACC-003 | 132-133 | Dev 1 |
| BRD-LOC-004 | Mushak-6.3 invoice | SRS-ACC-004 | 133-134 | Dev 1 |
| BRD-LOC-005 | Mushak-6.10 summary | SRS-ACC-005 | 134 | Dev 1 |
| BRD-LOC-006 | TDS/Withholding tax | SRS-ACC-006 | 135 | Dev 1 |
| BRD-LOC-007 | Bengali language | SRS-UI-001 | 135-137 | Dev 3 |
| BRD-LOC-008 | BDT currency | SRS-ACC-007 | 137 | Dev 2 |
| BRD-LOC-009 | Fiscal year | SRS-ACC-008 | 138 | Dev 2 |
| BRD-LOC-010 | Payroll localization | SRS-HR-001 | 138-139 | Dev 1 |

### 2.2 Bangladesh VAT Rates

| Category | VAT Rate | Applicable Products |
|----------|----------|---------------------|
| Standard | 15% | Most dairy products |
| Reduced | 5% | Essential food items |
| Exempt | 0% | Raw milk (unprocessed) |
| Zero-rated | 0% | Exports |

### 2.3 Bangladesh TDS Rates

| Payment Type | TDS Rate | Threshold |
|--------------|----------|-----------|
| Salary | 0-30% | Based on tax slab |
| Contractor | 2-10% | > BDT 25,000 |
| Rent | 5% | > BDT 25,000/month |
| Supplier (goods) | 5-7% | > BDT 50,000 |

---

## 3. Day-by-Day Breakdown

### Day 131 — Chart of Accounts & VAT Configuration

**Objective:** Configure Bangladesh-specific Chart of Accounts and VAT rates.

#### Dev 1 — Backend Lead (8h)

**Task 131.1.1: Chart of Accounts Module (4h)**

```python
# smart_dairy_addons/smart_bd_local/__manifest__.py
{
    'name': 'Smart Dairy Bangladesh Localization',
    'version': '19.0.1.0.0',
    'category': 'Localization',
    'summary': 'Bangladesh localization for Smart Dairy - VAT, TDS, Mushak',
    'description': """
Smart Dairy Bangladesh Localization
====================================
- Bangladesh Chart of Accounts (IFRS)
- VAT configuration (15%, exempt categories)
- Mushak forms (9.1, 6.3, 6.10)
- TDS/Withholding Tax
- Bengali language support
- Bangladesh payroll rules
- Local bank formats
    """,
    'author': 'Smart Dairy Ltd',
    'website': 'https://smartdairy.com.bd',
    'license': 'LGPL-3',
    'depends': [
        'account',
        'l10n_generic_coa',
        'hr_payroll',
        'purchase',
        'sale',
    ],
    'data': [
        'security/ir.model.access.csv',
        'data/account_chart_template.xml',
        'data/account_tax_data.xml',
        'data/fiscal_year_data.xml',
        'data/currency_data.xml',
        'views/mushak_views.xml',
        'views/tds_views.xml',
        'views/account_move_bd_views.xml',
        'reports/mushak_9_1_template.xml',
        'reports/mushak_6_3_template.xml',
        'reports/mushak_6_10_template.xml',
    ],
    'installable': True,
    'auto_install': False,
}
```

**Task 131.1.2: Bangladesh Chart of Accounts (4h)**

```xml
<!-- smart_dairy_addons/smart_bd_local/data/account_chart_template.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo noupdate="1">
    <!-- Chart of Accounts Template -->
    <record id="bd_chart_template" model="account.chart.template">
        <field name="name">Bangladesh - Smart Dairy Chart of Accounts</field>
        <field name="currency_id" ref="base.BDT"/>
        <field name="bank_account_code_prefix">1120</field>
        <field name="cash_account_code_prefix">1110</field>
        <field name="transfer_account_code_prefix">1199</field>
    </record>

    <!-- Assets -->
    <record id="account_1000" model="account.account.template">
        <field name="name">Assets</field>
        <field name="code">1000</field>
        <field name="account_type">asset_current</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
    </record>

    <!-- Current Assets -->
    <record id="account_1100" model="account.account.template">
        <field name="name">Current Assets</field>
        <field name="code">1100</field>
        <field name="account_type">asset_current</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
    </record>

    <!-- Cash and Bank -->
    <record id="account_1110" model="account.account.template">
        <field name="name">Cash in Hand</field>
        <field name="code">1110</field>
        <field name="account_type">asset_cash</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
    </record>

    <record id="account_1120" model="account.account.template">
        <field name="name">Bank Accounts</field>
        <field name="code">1120</field>
        <field name="account_type">asset_cash</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
    </record>

    <!-- Accounts Receivable -->
    <record id="account_1200" model="account.account.template">
        <field name="name">Accounts Receivable</field>
        <field name="code">1200</field>
        <field name="account_type">asset_receivable</field>
        <field name="reconcile">True</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
    </record>

    <!-- Inventory -->
    <record id="account_1300" model="account.account.template">
        <field name="name">Inventory - Raw Materials</field>
        <field name="code">1310</field>
        <field name="account_type">asset_current</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
    </record>

    <record id="account_1320" model="account.account.template">
        <field name="name">Inventory - Finished Goods</field>
        <field name="code">1320</field>
        <field name="account_type">asset_current</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
    </record>

    <!-- Fixed Assets -->
    <record id="account_1500" model="account.account.template">
        <field name="name">Property, Plant and Equipment</field>
        <field name="code">1500</field>
        <field name="account_type">asset_fixed</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
    </record>

    <!-- Liabilities -->
    <record id="account_2000" model="account.account.template">
        <field name="name">Liabilities</field>
        <field name="code">2000</field>
        <field name="account_type">liability_current</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
    </record>

    <!-- Accounts Payable -->
    <record id="account_2100" model="account.account.template">
        <field name="name">Accounts Payable</field>
        <field name="code">2100</field>
        <field name="account_type">liability_payable</field>
        <field name="reconcile">True</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
    </record>

    <!-- VAT Payable -->
    <record id="account_2200" model="account.account.template">
        <field name="name">VAT Payable</field>
        <field name="code">2200</field>
        <field name="account_type">liability_current</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
    </record>

    <!-- TDS Payable -->
    <record id="account_2210" model="account.account.template">
        <field name="name">TDS Payable</field>
        <field name="code">2210</field>
        <field name="account_type">liability_current</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
    </record>

    <!-- Revenue -->
    <record id="account_4000" model="account.account.template">
        <field name="name">Sales Revenue</field>
        <field name="code">4000</field>
        <field name="account_type">income</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
    </record>

    <!-- Cost of Goods Sold -->
    <record id="account_5000" model="account.account.template">
        <field name="name">Cost of Goods Sold</field>
        <field name="code">5000</field>
        <field name="account_type">expense_direct_cost</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
    </record>

    <!-- Operating Expenses -->
    <record id="account_6000" model="account.account.template">
        <field name="name">Operating Expenses</field>
        <field name="code">6000</field>
        <field name="account_type">expense</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
    </record>

    <!-- Equity -->
    <record id="account_3000" model="account.account.template">
        <field name="name">Equity</field>
        <field name="code">3000</field>
        <field name="account_type">equity</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
    </record>
</odoo>
```

**Task 131.1.3: VAT Tax Configuration (Continued from 131.1.2)**

```xml
<!-- smart_dairy_addons/smart_bd_local/data/account_tax_data.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo noupdate="1">
    <!-- Standard VAT 15% Sales -->
    <record id="tax_vat_15_sale" model="account.tax.template">
        <field name="name">VAT 15% (Sales)</field>
        <field name="type_tax_use">sale</field>
        <field name="amount_type">percent</field>
        <field name="amount">15.0</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
        <field name="tax_group_id" ref="tax_group_vat"/>
        <field name="invoice_repartition_line_ids" eval="[(0, 0, {
            'factor_percent': 100,
            'repartition_type': 'base',
        }), (0, 0, {
            'factor_percent': 100,
            'repartition_type': 'tax',
            'account_id': ref('account_2200'),
        })]"/>
        <field name="refund_repartition_line_ids" eval="[(0, 0, {
            'factor_percent': 100,
            'repartition_type': 'base',
        }), (0, 0, {
            'factor_percent': 100,
            'repartition_type': 'tax',
            'account_id': ref('account_2200'),
        })]"/>
    </record>

    <!-- Standard VAT 15% Purchase -->
    <record id="tax_vat_15_purchase" model="account.tax.template">
        <field name="name">VAT 15% (Purchase)</field>
        <field name="type_tax_use">purchase</field>
        <field name="amount_type">percent</field>
        <field name="amount">15.0</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
        <field name="tax_group_id" ref="tax_group_vat"/>
    </record>

    <!-- VAT Exempt -->
    <record id="tax_vat_exempt" model="account.tax.template">
        <field name="name">VAT Exempt</field>
        <field name="type_tax_use">sale</field>
        <field name="amount_type">percent</field>
        <field name="amount">0.0</field>
        <field name="chart_template_id" ref="bd_chart_template"/>
        <field name="tax_group_id" ref="tax_group_vat_exempt"/>
    </record>

    <!-- Tax Groups -->
    <record id="tax_group_vat" model="account.tax.group">
        <field name="name">VAT</field>
        <field name="sequence">10</field>
    </record>

    <record id="tax_group_vat_exempt" model="account.tax.group">
        <field name="name">VAT Exempt</field>
        <field name="sequence">20</field>
    </record>

    <record id="tax_group_tds" model="account.tax.group">
        <field name="name">TDS/Withholding Tax</field>
        <field name="sequence">30</field>
    </record>
</odoo>
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Task 131.2.1: Fiscal Year Configuration (4h)**

```xml
<!-- smart_dairy_addons/smart_bd_local/data/fiscal_year_data.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo noupdate="1">
    <!-- Fiscal Year Template (July 1 - June 30) -->
    <record id="fiscal_year_template" model="account.fiscal.year">
        <field name="name">FY 2026-27</field>
        <field name="date_from">2026-07-01</field>
        <field name="date_to">2027-06-30</field>
        <field name="company_id" ref="base.main_company"/>
    </record>

    <!-- Set default fiscal year start -->
    <function model="res.company" name="write">
        <function model="res.company" name="search" eval="[[]]"/>
        <value eval="{'fiscalyear_last_day': 30, 'fiscalyear_last_month': '6'}"/>
    </function>
</odoo>
```

**Task 131.2.2: Currency Configuration (4h)**

```python
# smart_dairy_addons/smart_bd_local/models/res_currency_bd.py
from odoo import models, fields, api

class ResCurrencyBD(models.Model):
    _inherit = 'res.currency'

    @api.model
    def _get_bdt_formatting(self):
        """Bangladesh currency formatting rules"""
        return {
            'symbol': '৳',
            'position': 'before',
            'decimal_places': 2,
            'thousands_separator': ',',
            'decimal_separator': '.',
            # Bangladesh uses Indian numbering (lakhs, crores)
            'grouping': [3, 2, 2],  # 1,23,45,678.00
        }

    def amount_to_text_bd(self, amount):
        """Convert amount to Bengali text representation"""
        # For check writing and invoices
        units = ['', 'এক', 'দুই', 'তিন', 'চার', 'পাঁচ', 'ছয়', 'সাত', 'আট', 'নয়']
        tens = ['', 'দশ', 'বিশ', 'ত্রিশ', 'চল্লিশ', 'পঞ্চাশ', 'ষাট', 'সত্তর', 'আশি', 'নব্বই']
        # ... full implementation
        return f"টাকা {amount} মাত্র"
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Task 131.3.1: Bengali Language Translation Setup (8h)**

```po
# smart_dairy_addons/smart_bd_local/i18n/bn.po
# Bengali translation for Smart Dairy
#
msgid ""
msgstr ""
"Project-Id-Version: Smart Dairy Bangladesh Localization\n"
"Language: bn\n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: 8bit\n"

#. module: smart_bd_local
msgid "Dashboard"
msgstr "ড্যাশবোর্ড"

#. module: smart_bd_local
msgid "Sales"
msgstr "বিক্রয়"

#. module: smart_bd_local
msgid "Purchase"
msgstr "ক্রয়"

#. module: smart_bd_local
msgid "Inventory"
msgstr "ইনভেন্টরি"

#. module: smart_bd_local
msgid "Accounting"
msgstr "হিসাবরক্ষণ"

#. module: smart_bd_local
msgid "Invoice"
msgstr "চালান"

#. module: smart_bd_local
msgid "Customer"
msgstr "গ্রাহক"

#. module: smart_bd_local
msgid "Supplier"
msgstr "সরবরাহকারী"

#. module: smart_bd_local
msgid "Product"
msgstr "পণ্য"

#. module: smart_bd_local
msgid "Total"
msgstr "মোট"

#. module: smart_bd_local
msgid "VAT"
msgstr "ভ্যাট"

#. module: smart_bd_local
msgid "Amount"
msgstr "পরিমাণ"

#. module: smart_bd_local
msgid "Date"
msgstr "তারিখ"

#. module: smart_bd_local
msgid "Quantity"
msgstr "পরিমাণ"

#. module: smart_bd_local
msgid "Unit Price"
msgstr "একক মূল্য"

#. module: smart_bd_local
msgid "Fresh Milk"
msgstr "তাজা দুধ"

#. module: smart_bd_local
msgid "Yogurt"
msgstr "দই"

#. module: smart_bd_local
msgid "Cheese"
msgstr "পনির"

#. module: smart_bd_local
msgid "Butter"
msgstr "মাখন"

#. module: smart_bd_local
msgid "Ghee"
msgstr "ঘি"
```

---

### Day 132-133 — Mushak Form Implementation

#### Dev 1 — Backend Lead (16h over 2 days)

**Task 132.1.1: Mushak Base Model (4h)**

```python
# smart_dairy_addons/smart_bd_local/models/mushak_report.py
from odoo import models, fields, api, _
from odoo.exceptions import UserError
from datetime import datetime

class MushakReport(models.Model):
    _name = 'bd.mushak.report'
    _description = 'Bangladesh Mushak Report'
    _order = 'date_from desc'

    name = fields.Char(
        string='Reference',
        required=True,
        copy=False,
        default=lambda self: _('New')
    )
    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )
    report_type = fields.Selection([
        ('mushak_9_1', 'Mushak 9.1 - VAT Return'),
        ('mushak_6_3', 'Mushak 6.3 - VAT Invoice'),
        ('mushak_6_10', 'Mushak 6.10 - Monthly Summary'),
    ], string='Report Type', required=True)

    # Period
    date_from = fields.Date(string='Period From', required=True)
    date_to = fields.Date(string='Period To', required=True)
    fiscal_year = fields.Char(
        string='Fiscal Year',
        compute='_compute_fiscal_year'
    )

    # Company Information (from BIN)
    bin_number = fields.Char(
        string='BIN Number',
        related='company_id.vat',
        readonly=True
    )
    company_name = fields.Char(
        related='company_id.name',
        readonly=True
    )
    company_address = fields.Text(
        related='company_id.street',
        readonly=True
    )

    # Totals
    total_sales = fields.Monetary(
        string='Total Sales',
        currency_field='currency_id'
    )
    total_vat_collected = fields.Monetary(
        string='VAT Collected',
        currency_field='currency_id'
    )
    total_purchases = fields.Monetary(
        string='Total Purchases',
        currency_field='currency_id'
    )
    total_vat_paid = fields.Monetary(
        string='VAT Paid (Input)',
        currency_field='currency_id'
    )
    net_vat_payable = fields.Monetary(
        string='Net VAT Payable',
        compute='_compute_net_vat',
        currency_field='currency_id'
    )

    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.ref('base.BDT')
    )

    # Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('generated', 'Generated'),
        ('submitted', 'Submitted'),
        ('accepted', 'Accepted'),
    ], string='Status', default='draft')

    # Lines
    line_ids = fields.One2many(
        'bd.mushak.report.line',
        'report_id',
        string='Report Lines'
    )

    @api.depends('date_from')
    def _compute_fiscal_year(self):
        for report in self:
            if report.date_from:
                month = report.date_from.month
                year = report.date_from.year
                if month >= 7:
                    report.fiscal_year = f"{year}-{year + 1}"
                else:
                    report.fiscal_year = f"{year - 1}-{year}"
            else:
                report.fiscal_year = ''

    @api.depends('total_vat_collected', 'total_vat_paid')
    def _compute_net_vat(self):
        for report in self:
            report.net_vat_payable = report.total_vat_collected - report.total_vat_paid

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            if vals.get('name', _('New')) == _('New'):
                report_type = vals.get('report_type', '')
                prefix = {
                    'mushak_9_1': 'M9.1',
                    'mushak_6_3': 'M6.3',
                    'mushak_6_10': 'M6.10',
                }.get(report_type, 'MSK')
                vals['name'] = self.env['ir.sequence'].next_by_code(
                    'bd.mushak.report'
                ) or _('New')
        return super().create(vals_list)

    def action_generate_mushak_9_1(self):
        """Generate Mushak 9.1 VAT Return"""
        self.ensure_one()

        # Get all invoices in period
        invoices = self.env['account.move'].search([
            ('invoice_date', '>=', self.date_from),
            ('invoice_date', '<=', self.date_to),
            ('state', '=', 'posted'),
            ('move_type', 'in', ['out_invoice', 'out_refund', 'in_invoice', 'in_refund']),
            ('company_id', '=', self.company_id.id),
        ])

        # Calculate sales totals
        sales_invoices = invoices.filtered(
            lambda i: i.move_type in ['out_invoice', 'out_refund']
        )
        self.total_sales = sum(
            inv.amount_untaxed if inv.move_type == 'out_invoice'
            else -inv.amount_untaxed
            for inv in sales_invoices
        )
        self.total_vat_collected = sum(
            inv.amount_tax if inv.move_type == 'out_invoice'
            else -inv.amount_tax
            for inv in sales_invoices
        )

        # Calculate purchase totals
        purchase_invoices = invoices.filtered(
            lambda i: i.move_type in ['in_invoice', 'in_refund']
        )
        self.total_purchases = sum(
            inv.amount_untaxed if inv.move_type == 'in_invoice'
            else -inv.amount_untaxed
            for inv in purchase_invoices
        )
        self.total_vat_paid = sum(
            inv.amount_tax if inv.move_type == 'in_invoice'
            else -inv.amount_tax
            for inv in purchase_invoices
        )

        # Generate detail lines
        self.line_ids.unlink()
        for invoice in sales_invoices:
            self.env['bd.mushak.report.line'].create({
                'report_id': self.id,
                'invoice_id': invoice.id,
                'line_type': 'sale',
                'partner_name': invoice.partner_id.name,
                'partner_bin': invoice.partner_id.vat or '',
                'invoice_number': invoice.name,
                'invoice_date': invoice.invoice_date,
                'amount_untaxed': invoice.amount_untaxed,
                'vat_amount': invoice.amount_tax,
            })

        self.state = 'generated'

    def action_print_mushak_9_1(self):
        """Print Mushak 9.1 report"""
        return self.env.ref(
            'smart_bd_local.action_report_mushak_9_1'
        ).report_action(self)


class MushakReportLine(models.Model):
    _name = 'bd.mushak.report.line'
    _description = 'Mushak Report Line'

    report_id = fields.Many2one(
        'bd.mushak.report',
        string='Report',
        required=True,
        ondelete='cascade'
    )
    invoice_id = fields.Many2one(
        'account.move',
        string='Invoice'
    )
    line_type = fields.Selection([
        ('sale', 'Sale'),
        ('purchase', 'Purchase'),
    ], string='Type')
    partner_name = fields.Char(string='Party Name')
    partner_bin = fields.Char(string='Party BIN')
    invoice_number = fields.Char(string='Invoice Number')
    invoice_date = fields.Date(string='Invoice Date')
    amount_untaxed = fields.Monetary(string='Amount', currency_field='currency_id')
    vat_amount = fields.Monetary(string='VAT Amount', currency_field='currency_id')
    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.ref('base.BDT')
    )
```

**Task 133.1.1: Mushak 6.3 Invoice Template (4h)**

```xml
<!-- smart_dairy_addons/smart_bd_local/reports/mushak_6_3_template.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <template id="report_mushak_6_3">
        <t t-call="web.html_container">
            <t t-foreach="docs" t-as="o">
                <t t-call="web.external_layout">
                    <div class="page">
                        <style>
                            .mushak-header { text-align: center; margin-bottom: 20px; }
                            .mushak-title { font-size: 16pt; font-weight: bold; }
                            .mushak-subtitle { font-size: 12pt; }
                            .mushak-table { width: 100%; border-collapse: collapse; }
                            .mushak-table th, .mushak-table td {
                                border: 1px solid #000;
                                padding: 5px;
                            }
                            .mushak-table th { background: #f0f0f0; }
                            .text-right { text-align: right; }
                            .text-center { text-align: center; }
                        </style>

                        <!-- Header -->
                        <div class="mushak-header">
                            <div class="mushak-title">মূসক-৬.৩</div>
                            <div class="mushak-subtitle">কর চালানপত্র / Tax Invoice</div>
                            <div class="mushak-subtitle">
                                [বিধি ৪০ এর উপ-বিধি (১) এর দফা (গ) ও দফা (চ) দ্রষ্টব্য]
                            </div>
                        </div>

                        <!-- Seller Information -->
                        <table class="mushak-table" style="margin-bottom: 15px;">
                            <tr>
                                <td width="30%"><strong>নিবন্ধিত ব্যক্তির নাম</strong></td>
                                <td><t t-esc="o.company_id.name"/></td>
                            </tr>
                            <tr>
                                <td><strong>BIN</strong></td>
                                <td><t t-esc="o.company_id.vat or 'N/A'"/></td>
                            </tr>
                            <tr>
                                <td><strong>ঠিকানা</strong></td>
                                <td><t t-esc="o.company_id.street or ''"/>
                                    <t t-if="o.company_id.city">, <t t-esc="o.company_id.city"/></t>
                                </td>
                            </tr>
                        </table>

                        <!-- Buyer Information -->
                        <table class="mushak-table" style="margin-bottom: 15px;">
                            <tr>
                                <td width="30%"><strong>ক্রেতার নাম</strong></td>
                                <td><t t-esc="o.partner_id.name"/></td>
                            </tr>
                            <tr>
                                <td><strong>ক্রেতার BIN (যদি থাকে)</strong></td>
                                <td><t t-esc="o.partner_id.vat or 'N/A'"/></td>
                            </tr>
                            <tr>
                                <td><strong>ক্রেতার ঠিকানা</strong></td>
                                <td><t t-esc="o.partner_id.street or ''"/></td>
                            </tr>
                        </table>

                        <!-- Invoice Details -->
                        <table class="mushak-table" style="margin-bottom: 15px;">
                            <tr>
                                <td width="30%"><strong>চালানপত্র নম্বর</strong></td>
                                <td><t t-esc="o.name"/></td>
                                <td width="20%"><strong>তারিখ</strong></td>
                                <td><t t-esc="o.invoice_date" t-options="{'widget': 'date'}"/></td>
                            </tr>
                        </table>

                        <!-- Invoice Lines -->
                        <table class="mushak-table">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">ক্রমিক</th>
                                    <th>পণ্য/সেবার বিবরণ</th>
                                    <th class="text-center" width="10%">পরিমাণ</th>
                                    <th class="text-center" width="10%">একক</th>
                                    <th class="text-right" width="15%">একক মূল্য (৳)</th>
                                    <th class="text-right" width="15%">মোট মূল্য (৳)</th>
                                    <th class="text-right" width="10%">SD হার</th>
                                    <th class="text-right" width="10%">SD পরিমাণ</th>
                                    <th class="text-right" width="10%">VAT হার</th>
                                    <th class="text-right" width="15%">VAT পরিমাণ (৳)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <t t-set="line_num" t-value="1"/>
                                <t t-foreach="o.invoice_line_ids" t-as="line">
                                    <tr>
                                        <td class="text-center"><t t-esc="line_num"/></td>
                                        <td><t t-esc="line.product_id.name or line.name"/></td>
                                        <td class="text-center"><t t-esc="line.quantity"/></td>
                                        <td class="text-center"><t t-esc="line.product_uom_id.name"/></td>
                                        <td class="text-right">
                                            <t t-esc="line.price_unit" t-options="{'widget': 'monetary', 'display_currency': o.currency_id}"/>
                                        </td>
                                        <td class="text-right">
                                            <t t-esc="line.price_subtotal" t-options="{'widget': 'monetary', 'display_currency': o.currency_id}"/>
                                        </td>
                                        <td class="text-right">0%</td>
                                        <td class="text-right">0.00</td>
                                        <td class="text-right">
                                            <t t-foreach="line.tax_ids" t-as="tax">
                                                <t t-esc="tax.amount"/>%
                                            </t>
                                        </td>
                                        <td class="text-right">
                                            <t t-esc="line.price_total - line.price_subtotal" t-options="{'widget': 'monetary', 'display_currency': o.currency_id}"/>
                                        </td>
                                    </tr>
                                    <t t-set="line_num" t-value="line_num + 1"/>
                                </t>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-right"><strong>মোট</strong></td>
                                    <td class="text-right">
                                        <strong><t t-esc="o.amount_untaxed" t-options="{'widget': 'monetary', 'display_currency': o.currency_id}"/></strong>
                                    </td>
                                    <td colspan="2"></td>
                                    <td></td>
                                    <td class="text-right">
                                        <strong><t t-esc="o.amount_tax" t-options="{'widget': 'monetary', 'display_currency': o.currency_id}"/></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="9" class="text-right"><strong>সর্বমোট (VAT সহ)</strong></td>
                                    <td class="text-right">
                                        <strong><t t-esc="o.amount_total" t-options="{'widget': 'monetary', 'display_currency': o.currency_id}"/></strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Footer -->
                        <div style="margin-top: 30px;">
                            <table width="100%">
                                <tr>
                                    <td width="50%">
                                        <p>___________________________</p>
                                        <p>বিক্রেতার স্বাক্ষর ও সীল</p>
                                    </td>
                                    <td width="50%" style="text-align: right;">
                                        <p>___________________________</p>
                                        <p>ক্রেতার স্বাক্ষর</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </t>
            </t>
        </t>
    </template>

    <record id="action_report_mushak_6_3" model="ir.actions.report">
        <field name="name">Mushak 6.3 VAT Invoice</field>
        <field name="model">account.move</field>
        <field name="report_type">qweb-pdf</field>
        <field name="report_name">smart_bd_local.report_mushak_6_3</field>
        <field name="report_file">smart_bd_local.report_mushak_6_3</field>
        <field name="binding_model_id" ref="account.model_account_move"/>
        <field name="binding_type">report</field>
    </record>
</odoo>
```

---

*(Days 134-140 continue with TDS implementation, payroll localization, and testing...)*

---

## 4. Technical Specifications

### 4.1 Data Models Summary

| Model | Description | Key Fields |
|-------|-------------|------------|
| `bd.mushak.report` | Mushak Report | report_type, total_vat_collected, net_vat_payable |
| `bd.mushak.report.line` | Report Line | invoice_id, partner_bin, vat_amount |
| `bd.tds.deduction` | TDS Deduction | payment_type, tds_rate, threshold_amount |
| `hr.payroll.bd` | BD Payroll | pf_contribution, gratuity, tax_slab |

### 4.2 Mushak Form Types

| Form | Purpose | Frequency |
|------|---------|-----------|
| Mushak-9.1 | VAT Return | Monthly |
| Mushak-6.3 | VAT Invoice | Per transaction |
| Mushak-6.10 | Monthly Summary | Monthly |

---

## 5. Testing & Validation

### 5.1 Test Cases

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| TC-14-001 | VAT 15% calculation | Correct VAT on invoice |
| TC-14-002 | Mushak 6.3 generation | Valid PDF with all fields |
| TC-14-003 | Mushak 9.1 totals | Correct VAT summary |
| TC-14-004 | TDS deduction | Correct withholding |
| TC-14-005 | Bengali UI toggle | 95%+ strings translated |
| TC-14-006 | BDT formatting | ৳ symbol, correct grouping |

---

## 6. Risk & Mitigation

| Risk ID | Risk | Probability | Impact | Mitigation |
|---------|------|-------------|--------|------------|
| R14-001 | NBR form changes | Medium | High | Regular updates |
| R14-002 | Translation quality | Medium | Low | Professional review |
| R14-003 | Tax rate changes | Low | Medium | Configurable rates |

---

## 7. Dependencies & Handoffs

### 7.1 Prerequisites
- Chart of accounts from Odoo base
- HR payroll module installed

### 7.2 Outputs
- VAT compliance for MS-18 (Payments)
- Bengali UI for MS-17 (Website)

---

**Document End**

*Milestone 14: Bangladesh Localization Deep Implementation*
*Smart Dairy Digital Smart Portal + ERP System*
*Phase 2 — ERP Core Configuration*
*Version 1.0 | February 2026*
