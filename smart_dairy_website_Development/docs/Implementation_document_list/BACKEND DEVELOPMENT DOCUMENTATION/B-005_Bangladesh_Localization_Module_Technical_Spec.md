# SMART DAIRY LTD.
## BANGLADESH LOCALIZATION MODULE - TECHNICAL SPECIFICATION
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | B-005 |
| **Version** | 1.0 |
| **Date** | February 15, 2026 |
| **Author** | Odoo Lead / Localization Specialist |
| **Owner** | Odoo Lead |
| **Reviewer** | Solution Architect |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Regulatory Context](#2-regulatory-context)
3. [Module Architecture](#3-module-architecture)
4. [VAT Implementation](#4-vat-implementation)
5. [Tax Configuration](#5-tax-configuration)
6. [Payroll & HR Compliance](#6-payroll--hr-compliance)
7. [Accounting Localization](#7-accounting-localization)
8. [Report & Compliance Documents](#8-report--compliance-documents)
9. [Data Localization Requirements](#9-data-localization-requirements)
10. [Appendices](#10-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document specifies the technical implementation of the Bangladesh Localization Module (`smart_bd_localization`) for Smart Dairy's Odoo 19 CE ERP system. The module ensures full compliance with Bangladesh tax regulations, labor laws, and accounting standards.

### 1.2 Scope

The localization module covers:
- Value Added Tax (VAT) computation and reporting
- Tax Deduction at Source (TDS) management
- Bangladesh Payroll with compliance to Labor Act 2006
- Chart of Accounts per Bangladesh Financial Reporting Standards (BFRS)
- Regulatory reporting (NBR, BIDA, RJSC)
- Data localization requirements

### 1.3 Reference Regulations

| Regulation | Authority | Description |
|------------|-----------|-------------|
| VAT Act 1991 (as amended) | NBR | Value Added Tax regulations |
| Income Tax Ordinance 1984 | NBR | Income tax and TDS rules |
| Labor Act 2006 | Ministry of Labor | Employment and payroll regulations |
| Bangladesh Financial Reporting Standards | ICAB | Accounting standards |
| Digital Security Act 2018 | BTRC | Data protection requirements |
| Foreign Exchange Regulation Act 1947 | Bangladesh Bank | Foreign currency transactions |

---

## 2. REGULATORY CONTEXT

### 2.1 Bangladesh Tax Structure

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    BANGLADESH TAX STRUCTURE OVERVIEW                         │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  VALUE ADDED TAX (VAT)                                                      │
│  ├── Standard Rate: 15% (most goods and services)                          │
│  ├── Reduced Rate: 5% (essential commodities)                              │
│  ├── Zero Rate: 0% (exports, certain medicines)                            │
│  └── Exempt: Fresh milk, agricultural products                             │
│                                                                              │
│  SUPPLEMENTARY DUTY (SD)                                                    │
│  ├── Luxury goods: 20-350%                                                 │
│  ├── Processed foods: 5-20%                                                │
│  └── Beverages: 25-100%                                                    │
│                                                                              │
│  TAX DEDUCTION AT SOURCE (TDS)                                              │
│  ├── Supplier payments: 3-10%                                              │
│  ├── Contractor payments: 7.5-10%                                          │
│  ├── Professional fees: 10%                                                │
│  └── Employee salaries: Progressive slab                                   │
│                                                                              │
│  ADVANCE INCOME TAX (AIT)                                                   │
│  └── Import stage: 3-5% on CIF value                                       │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Dairy Industry Specific Tax Treatment

| Product Category | VAT Rate | SD Rate | Remarks |
|-----------------|----------|---------|---------|
| Fresh Milk (raw) | Exempt | 0% | Unprocessed milk |
| Pasteurized Milk | 15% | 0% | Standard VAT applies |
| UHT Milk | 15% | 0% | Long-life milk |
| Flavored Milk | 15% | 10% | Processed beverage |
| Yogurt/Curd | 15% | 0% | Fermented milk |
| Cheese | 15% | 0% | Processed dairy |
| Butter/Ghee | 15% | 0% | Dairy fat products |
| Ice Cream | 15% | 25% | Luxury classification |
| Whey Powder | 15% | 0% | Industrial use |

---

## 3. MODULE ARCHITECTURE

### 3.1 Module Structure

```
smart_bd_localization/
├── __init__.py
├── __manifest__.py
├── models/
│   ├── __init__.py
│   ├── res_company.py              # Company BD-specific fields
│   ├── res_partner.py              # Partner BIN/TIN management
│   ├── account_tax.py              # BD VAT/Tax configuration
│   ├── account_move.py             # Journal entry enhancements
│   ├── account_invoice.py          # Invoice VAT handling
│   ├── bd_vat_return.py            # VAT return computation
│   ├── bd_mushak.py                # Mushak forms
│   ├── hr_employee.py              # Employee tax info
│   ├── hr_payslip.py               # BD payroll rules
│   ├── hr_contract.py              # Contract compliance
│   └── bd_tax_config.py            # Tax configuration master
├── wizard/
│   ├── __init__.py
│   ├── vat_adjustment_wizard.py    # VAT adjustment entries
│   ├── tds_computation_wizard.py   # TDS calculation
│   └── vat_return_wizard.py        # VAT return generation
├── report/
│   ├── __init__.py
│   ├── vat_reports.py              # VAT report generators
│   ├── mushak_reports.py           # Mushak form generators
│   ├── tds_reports.py              # TDS certificates
│   └── payroll_reports.py          # Payroll compliance reports
├── data/
│   ├── chart_of_accounts.xml       # BD Chart of Accounts
│   ├── account_tax_data.xml        # BD Tax templates
│   ├── hr_salary_rules.xml         # BD Salary rules
│   ├── hr_payroll_structure.xml    # Payroll structures
│   └── mushak_templates.xml        # Mushak form templates
├── views/
│   ├── res_company_views.xml
│   ├── res_partner_views.xml
│   ├── account_tax_views.xml
│   ├── bd_vat_return_views.xml
│   ├── bd_mushak_views.xml
│   ├── hr_employee_views.xml
│   ├── hr_payslip_views.xml
│   └── menu_views.xml
├── security/
│   ├── ir.model.access.csv
│   └── bd_security_groups.xml
└── static/
    └── src/
        └── xml/
            └── mushak_templates.xml
```

### 3.2 Module Manifest

```python
# __manifest__.py
{
    'name': 'Smart Dairy - Bangladesh Localization',
    'version': '19.0.1.0.0',
    'category': 'Localization',
    'summary': 'Bangladesh Tax, Accounting, and Payroll Localization',
    'description': '''
        Bangladesh Localization Module for Smart Dairy
        ================================================
        
        Comprehensive localization for Bangladesh compliance:
        - VAT 15% with Mushak reporting (6.1, 6.2.1, 6.3, 6.10, 9.1)
        - TDS/TCS management per Income Tax Ordinance 1984
        - Payroll with Labor Act 2006 compliance
        - Bangladesh Chart of Accounts (BFRS aligned)
        - Regulatory reports for NBR, BIDA, RJSC
        - Data localization support
        
        Dependencies:
        - Odoo l10n_bd (base Bangladesh localization)
        - account, hr_payroll, stock
    ''',
    'author': 'Smart Dairy Ltd.',
    'website': 'https://smartdairybd.com',
    'depends': [
        'base',
        'l10n_bd',           # Odoo base BD localization
        'account',
        'account_accountant',
        'hr',
        'hr_payroll',
        'hr_payroll_account',
        'stock',
        'purchase',
        'sale',
    ],
    'data': [
        # Security
        'security/bd_security_groups.xml',
        'security/ir.model.access.csv',
        
        # Data
        'data/chart_of_accounts.xml',
        'data/account_tax_data.xml',
        'data/account_fiscal_position_data.xml',
        'data/hr_payroll_structure.xml',
        'data/hr_salary_rules.xml',
        'data/bd_vat_notes.xml',
        'data/mushak_templates.xml',
        
        # Views
        'views/res_company_views.xml',
        'views/res_partner_views.xml',
        'views/account_tax_views.xml',
        'views/account_move_views.xml',
        'views/bd_vat_return_views.xml',
        'views/bd_mushak_views.xml',
        'views/hr_employee_views.xml',
        'views/hr_payslip_views.xml',
        'views/hr_contract_views.xml',
        'views/menu_views.xml',
        
        # Wizards
        'wizard/vat_adjustment_wizard_view.xml',
        'wizard/tds_computation_wizard_view.xml',
        'wizard/vat_return_wizard_view.xml',
        
        # Reports
        'report/vat_report_templates.xml',
        'report/mushak_report_templates.xml',
        'report/tds_report_templates.xml',
        'report/payroll_report_templates.xml',
    ],
    'demo': [
        'demo/bd_demo_data.xml',
    ],
    'installable': True,
    'application': False,
    'auto_install': False,
    'license': 'LGPL-3',
}
```

---

## 4. VAT IMPLEMENTATION

### 4.1 VAT Model Architecture

```python
# models/bd_vat_return.py
from odoo import models, fields, api, _
from odoo.exceptions import ValidationError, UserError
from datetime import datetime, timedelta
import json

class BdVatReturn(models.Model):
    """
    Bangladesh VAT Return (Form VAT 9.1)
    Implements monthly/quarterly VAT return computation
    """
    _name = 'bd.vat.return'
    _description = 'Bangladesh VAT Return'
 _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'period_start desc'
    
    # Header Information
    name = fields.Char(
        string='Reference',
        required=True,
        copy=False,
        readonly=True,
        default=lambda self: _('New')
    )
    
    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )
    
    vat_registration_no = fields.Char(
        string='VAT Registration No (BIN)',
        related='company_id.vat',
        store=True,
        readonly=True
    )
    
    # Return Period
    return_type = fields.Selection([
        ('monthly', 'Monthly'),
        ('quarterly', 'Quarterly'),
    ], string='Return Type', required=True, default='monthly')
    
    period_start = fields.Date(string='Period Start', required=True)
    period_end = fields.Date(string='Period End', required=True)
    
    fiscal_year = fields.Char(
        string='Fiscal Year',
        compute='_compute_fiscal_year',
        store=True
    )
    
    # Return Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('computed', 'Computed'),
        ('submitted', 'Submitted to NBR'),
        ('approved', 'Approved'),
        ('amended', 'Amended'),
    ], string='Status', default='draft', tracking=True)
    
    # ========================================
    # INPUT TAX (Purchase Side)
    # ========================================
    # Local Purchases
    local_purchase_value = fields.Monetary(
        string='Local Purchase Value',
        currency_field='company_currency_id',
        compute='_compute_local_purchase'
    )
    local_purchase_vat = fields.Monetary(
        string='Local Purchase VAT',
        currency_field='company_currency_id',
        compute='_compute_local_purchase'
    )
    
    # Import Stage
    import_value = fields.Monetary(
        string='Import Value (CIF)',
        currency_field='company_currency_id'
    )
    import_vat = fields.Monetary(
        string='Import VAT',
        currency_field='company_currency_id'
    )
    import_sd = fields.Monetary(
        string='Import Supplementary Duty',
        currency_field='company_currency_id'
    )
    import_ait = fields.Monetary(
        string='Advance Income Tax (AIT)',
        currency_field='company_currency_id'
    )
    
    # Deferred VAT (Import)
    deferred_vat_value = fields.Monetary(
        string='Deferred VAT Value',
        currency_field='company_currency_id'
    )
    deferred_vat_amount = fields.Monetary(
        string='Deferred VAT Amount',
        currency_field='company_currency_id'
    )
    
    # Total Input Tax
    total_input_tax = fields.Monetary(
        string='Total Input Tax',
        currency_field='company_currency_id',
        compute='_compute_totals'
    )
    
    # ========================================
    # OUTPUT TAX (Sales Side)
    # ========================================
    # Local Sales
    local_sales_value = fields.Monetary(
        string='Local Sales Value',
        currency_field='company_currency_id',
        compute='_compute_local_sales'
    )
    local_sales_vat = fields.Monetary(
        string='Local Sales VAT',
        currency_field='company_currency_id',
        compute='_compute_local_sales'
    )
    local_sales_sd = fields.Monetary(
        string='Local Sales SD',
        currency_field='company_currency_id',
        compute='_compute_local_sales'
    )
    
    # Export Sales
    export_sales_value = fields.Monetary(
        string='Export Value (FOB)',
        currency_field='company_currency_id'
    )
    export_sales_vat = fields.Monetary(
        string='Export VAT (Zero Rated)',
        currency_field='company_currency_id',
        default=0.0
    )
    
    # Exempt Sales
    exempt_sales_value = fields.Monetary(
        string='Exempt Sales Value',
        currency_field='company_currency_id'
    )
    exempt_sales_vat = fields.Monetary(
        string='Exempt VAT',
        currency_field='company_currency_id',
        default=0.0
    )
    
    # Total Output Tax
    total_output_tax = fields.Monetary(
        string='Total Output Tax',
        currency_field='company_currency_id',
        compute='_compute_totals'
    )
    
    # ========================================
    # NET VAT COMPUTATION
    # ========================================
    vat_payable = fields.Monetary(
        string='VAT Payable (Output - Input)',
        currency_field='company_currency_id',
        compute='_compute_totals'
    )
    
    vat_credits_bf = fields.Monetary(
        string='VAT Credits Brought Forward',
        currency_field='company_currency_id'
    )
    
    vat_adjustments = fields.Monetary(
        string='VAT Adjustments (+/-)',
        currency_field='company_currency_id'
    )
    
    net_vat_payable = fields.Monetary(
        string='Net VAT Payable',
        currency_field='company_currency_id',
        compute='_compute_totals'
    )
    
    # SD Payable
    sd_payable = fields.Monetary(
        string='SD Payable',
        currency_field='company_currency_id',
        compute='_compute_totals'
    )
    
    # Currency
    company_currency_id = fields.Many2one(
        'res.currency',
        related='company_id.currency_id',
        store=True,
        string='Company Currency'
    )
    
    # Mushak Forms
    mushak_6_1_ids = fields.One2many(
        'bd.mushak.6.1',
        'vat_return_id',
        string='Mushak 6.1 (Sales)'
    )
    mushak_6_2_ids = fields.One2many(
        'bd.mushak.6.2',
        'vat_return_id',
        string='Mushak 6.2 (Purchase)'
    )
    
    @api.depends('period_start')
    def _compute_fiscal_year(self):
        for rec in self:
            if rec.period_start:
                year = rec.period_start.year
                if rec.period_start.month < 7:  # BD FY: July-June
                    rec.fiscal_year = f"{year-1}-{year}"
                else:
                    rec.fiscal_year = f"{year}-{year+1}"
    
    @api.depends('mushak_6_2_ids')
    def _compute_local_purchase(self):
        for rec in self:
            rec.local_purchase_value = sum(rec.mushak_6_2_ids.mapped('value'))
            rec.local_purchase_vat = sum(rec.mushak_6_2_ids.mapped('vat_amount'))
    
    @api.depends('mushak_6_1_ids')
    def _compute_local_sales(self):
  for rec in self:
            rec.local_sales_value = sum(rec.mushak_6_1_ids.mapped('value'))
            rec.local_sales_vat = sum(rec.mushak_6_1_ids.mapped('vat_amount'))
            rec.local_sales_sd = sum(rec.mushak_6_1_ids.mapped('sd_amount'))
    
    @api.depends('local_purchase_vat', 'import_vat', 'deferred_vat_amount',
                 'local_sales_vat', 'export_sales_vat', 'local_sales_sd')
    def _compute_totals(self):
        for rec in self:
            rec.total_input_tax = (
                rec.local_purchase_vat + 
                rec.import_vat + 
                rec.deferred_vat_amount
            )
            rec.total_output_tax = (
       rec.local_sales_vat + 
                rec.export_sales_vat
            )
            rec.vat_payable = rec.total_output_tax - rec.total_input_tax
            rec.net_vat_payable = (
                rec.vat_payable + 
         rec.vat_credits_bf + 
                rec.vat_adjustments
            )
   rec.sd_payable = rec.local_sales_sd + rec.import_sd
    
    def action_compute_vat(self):
        """Compute VAT return from invoices"""
        self.ensure_one()
        
  # Clear existing mushak entries
        self.mushak_6_1_ids.unlink()
        self.mushak_6_2_ids.unlink()
        
        # Get invoices for period
        invoices = self.env['account.move'].search([
 ('move_type', 'in', ['out_invoice', 'out_refund']),
            ('invoice_date', '>=', self.period_start),
       ('invoice_date', '<=', self.period_end),
            ('state', '=', 'posted'),
        ])
        
        # Generate Mushak 6.1 entries
        for inv in invoices:
            self.env['bd.mushak.6.1'].create({
   'vat_return_id': self.id,
      'invoice_id': inv.id,
         'partner_id': inv.partner_id.id,
                'partner_bin': inv.partner_id.vat,
    'value': inv.amount_untaxed,
                'vat_amount': inv.amount_tax,
      'sd_amount': inv.supplementary_duty or 0.0,
      })
        
        # Similar for purchase invoices (Mushak 6.2)
        bills = self.env['account.move'].search([
            ('move_type', 'in', ['in_invoice', 'in_refund']),
            ('invoice_date', '>=', self.period_start),
       ('invoice_date', '<=', self.period_end),
            ('state', '=', 'posted'),
        ])
        
        self.state = 'computed'
    
    def action_generate_mushak_9_1(self):
        """Generate Mushak 9.1 (VAT Return Form) PDF"""
        self.ensure_one()
        return self.env.ref('smart_bd_localization.action_report_mushak_9_1').report_action(self)
```

### 4.2 Mushak Forms Implementation

```python
# models/bd_mushak.py

class BdMushak61(models.Model):
    """Mushak 6.1 - Local Sales Register"""
    _name = 'bd.mushak.6.1'
    _description = 'Mushak 6.1 - Sales Register'
    _order = 'date desc'
    
    vat_return_id = fields.Many2one('bd.vat.return', string='VAT Return')
    date = fields.Date(string='Date', required=True)
    invoice_id = fields.Many2one('account.move', string='Invoice')
    partner_id = fields.Many2one('res.partner', string='Customer')
    partner_bin = fields.Char(string='Customer BIN')
    partner_address = fields.Char(string='Customer Address')
    product_description = fields.Char(string='Product Description')
    hscode = fields.Char(string='HS Code')
    value = fields.Monetary(string='Value (Excl. VAT)')
    vat_rate = fields.Float(string='VAT Rate %', default=15.0)
    vat_amount = fields.Monetary(string='VAT Amount')
    sd_rate = fields.Float(string='SD Rate %', default=0.0)
    sd_amount = fields.Monetary(string='SD Amount')
    total_amount = fields.Monetary(string='Total Amount')


class BdMushak62(models.Model):
    """Mushak 6.2 - Local Purchase Register"""
    _name = 'bd.mushak.6.2'
    _description = 'Mushak 6.2 - Purchase Register'
    _order = 'date desc'
    
    vat_return_id = fields.Many2one('bd.vat.return', string='VAT Return')
    date = fields.Date(string='Date', required=True)
    bill_id = fields.Many2one('account.move', string='Bill')
    partner_id = fields.Many2one('res.partner', string='Supplier')
    partner_bin = fields.Char(string='Supplier BIN')
    value = fields.Monetary(string='Value (Excl. VAT)')
    vat_rate = fields.Float(string='VAT Rate %', default=15.0)
    vat_amount = fields.Monetary(string='VAT Amount')
    vat_deducted = fields.Monetary(string='VAT Deducted at Source')


class BdMushak63(models.Model):
    """Mushak 6.3 - Sales/Credit Note"""
    _name = 'bd.mushak.6.3'
    _description = 'Mushak 6.3 - Credit Notes'
    
    date = fields.Date(string='Date')
    original_invoice_id = fields.Many2one('account.move', string='Original Invoice')
    credit_note_id = fields.Many2one('account.move', string='Credit Note')
    partner_id = fields.Many2one('res.partner', string='Customer')
    reason = fields.Text(string='Reason for Return')
    value_adjusted = fields.Monetary(string='Value Adjusted')
    vat_adjusted = fields.Monetary(string='VAT Adjusted')
```

---

## 5. TAX CONFIGURATION

### 5.1 TDS (Tax Deduction at Source)

```python
# models/bd_tax_config.py

class BdTdsRate(models.Model):
    """TDS Rate Configuration"""
    _name = 'bd.tds.rate'
    _description = 'TDS Rate Configuration'
    
    name = fields.Char(string='Description', required=True)
    section = fields.Char(string='Income Tax Section', required=True)
    
    transaction_type = fields.Selection([
        ('supplier', 'Supplier Payment'),
        ('contractor', 'Contractor Payment'),
        ('professional', 'Professional Fee'),
        ('rent', 'Rent'),
        ('salary', 'Salary/Wages'),
        ('commission', 'Commission'),
        ('interest', 'Interest'),
        ('dividend', 'Dividend'),
    ], string='Transaction Type', required=True)
    
    threshold_amount = fields.Monetary(
        string='Threshold Amount',
        help='TDS applies only if payment exceeds this amount'
    )
    
    company_type = fields.Selection([
        ('company', 'Company'),
        ('individual', 'Individual/Firm/Trust'),
        ('non_resident', 'Non-Resident'),
    ], string='Payee Type', required=True)
    
    rate = fields.Float(string='TDS Rate %', required=True)
    
    is_active = fields.Boolean(string='Active', default=True)
    effective_date = fields.Date(string='Effective From', required=True)
    
    _sql_constraints = [
        ('unique_section_company', 
         'UNIQUE(section, company_type)', 
         'TDS rate already defined for this section and company type!')
    ]


# Standard TDS Rates for Bangladesh
TDS_RATES_BD = [
    # Section 50 - Salary (handled in payroll)
    {'section': '50', 'type': 'salary', 'rate_company': 'slab', 'rate_individual': 'slab'},
    
    # Section 51 - Interest on securities
    {'section': '51', 'type': 'interest', 'rate_company': 10.0, 'rate_individual': 10.0},
    
    # Section 52 - Payment to suppliers
    {'section': '52', 'type': 'supplier', 'rate_company': 7.5, 'rate_individual': 7.5, 'threshold': 50000},
    
    # Section 53 - Payment to contractors
    {'section': '53', 'type': 'contractor', 'rate_company': 10.0, 'rate_individual': 10.0, 'threshold': 25000},
    
    # Section 53AA - Commission
    {'section': '53AA', 'type': 'commission', 'rate_company': 10.0, 'rate_individual': 10.0},
    
    # Section 53B - Professional fee
    {'section': '53B', 'type': 'professional', 'rate_company': 12.0, 'rate_individual': 12.0, 'threshold': 25000},
    
    # Section 53C - Rental power
    {'section': '53C', 'type': 'rent', 'rate_company': 5.0, 'rate_individual': 5.0},
]
```

### 5.2 Tax Configuration Data

```xml
<!-- data/account_tax_data.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<odoo>
    <!-- VAT 15% - Standard Rate -->
    <record id="vat_15" model="account.tax">
        <field name="name">VAT 15%</field>
        <field name="type_tax_use">sale</field>
        <field name="amount_type">percent</field>
        <field name="amount">15</field>
        <field name="tax_group_id" ref="tax_group_vat"/>
        <field name="invoice_repartition_line_ids" eval="[
            (0,0, {'factor_percent': 100, 'repartition_type': 'base'}),
            (0,0, {'factor_percent': 100, 'repartition_type': 'tax', 'account_id': ref('vat_output_account')}),
        ]"/>
    </record>
    
    <!-- VAT 5% - Reduced Rate -->
    <record id="vat_5" model="account.tax">
        <field name="name">VAT 5%</field>
        <field name="type_tax_use">sale</field>
        <field name="amount_type">percent</field>
        <field name="amount">5</field>
        <field name="tax_group_id" ref="tax_group_vat"/>
    </record>
    
    <!-- VAT 0% - Zero Rated (Exports) -->
    <record id="vat_0_export" model="account.tax">
        <field name="name">VAT 0% (Export)</field>
        <field name="type_tax_use">sale</field>
        <field name="amount_type">percent</field>
        <field name="amount">0</field>
        <field name="tax_group_id" ref="tax_group_vat"/>
        <field name="description">Zero rated for exports</field>
    </record>
    
    <!-- VAT Exempt -->
    <record id="vat_exempt" model="account.tax">
        <field name="name">VAT Exempt</field>
        <field name="type_tax_use">sale</field>
        <field name="amount_type">percent</field>
        <field name="amount">0</field>
        <field name="tax_group_id" ref="tax_group_vat_exempt"/>
        <field name="description">Exempt - Fresh milk, agricultural products</field>
    </record>
    
    <!-- Supplementary Duty 25% (Ice Cream) -->
    <record id="sd_25" model="account.tax">
        <field name="name">SD 25%</field>
        <field name="type_tax_use">sale</field>
        <field name="amount_type">percent</field>
        <field name="amount">25</field>
        <field name="tax_group_id" ref="tax_group_sd"/>
        <field name="description">Supplementary Duty for luxury items</field>
        <field name="sequence">10</field>
    </record>
    
    <!-- Purchase VAT 15% -->
    <record id="vat_15_purchase" model="account.tax">
        <field name="name">VAT 15% (Purchase)</field>
        <field name="type_tax_use">purchase</field>
        <field name="amount_type">percent</field>
        <field name="amount">15</field>
        <field name="tax_group_id" ref="tax_group_vat"/>
        <field name="invoice_repartition_line_ids" eval="[
            (0,0, {'factor_percent': 100, 'repartition_type': 'base'}),
            (0,0, {'factor_percent': 100, 'repartition_type': 'tax', 'account_id': ref('vat_input_account')}),
        ]"/>
    </record>
    
    <!-- Import VAT 15% -->
    <record id="vat_15_import" model="account.tax">
        <field name="name">Import VAT 15%</field>
        <field name="type_tax_use">purchase</field>
        <field name="amount_type">percent</field>
        <field name="amount">15</field>
        <field name="tax_group_id" ref="tax_group_vat_import"/>
        <field name="description">VAT on imports</field>
    </record>
    
    <!-- Advance Income Tax (AIT) on Import -->
    <record id="ait_3_import" model="account.tax">
        <field name="name">AIT 3% (Import)</field>
        <field name="type_tax_use">purchase</field>
        <field name="amount_type">percent</field>
        <field name="amount">3</field>
        <field name="tax_group_id" ref="tax_group_ait"/>
        <field name="description">Advance Income Tax on imports</field>
    </record>
</odoo>
```

---

## 6. PAYROLL & HR COMPLIANCE

### 6.1 Bangladesh Payroll Structure

```python
# models/hr_payslip.py
from odoo import models, fields, api, _

class HrPayslipBd(models.Model):
    _inherit = 'hr.payslip'
    
    # Bangladesh-specific fields
    is_bonus_month = fields.Boolean(string='Bonus Month')
    festival_bonus = fields.Monetary(string='Festival Bonus')
    attendance_bonus = fields.Monetary(string='Attendance Bonus')
    overtime_hours = fields.Float(string='Overtime Hours')
    overtime_amount = fields.Monetary(string='Overtime Amount')
    
    # Provident Fund
    employee_pf_contribution = fields.Monetary(
        string='Employee PF Contribution',
        compute='_compute_pf_contribution'
    )
    employer_pf_contribution = fields.Monetary(
        string='Employer PF Contribution',
        compute='_compute_pf_contribution'
    )
    
    # Tax Computation
    taxable_income = fields.Monetary(string='Taxable Income')
    tds_amount = fields.Monetary(string='TDS (Income Tax)')
    rebate_amount = fields.Monetary(string='Investment Rebate')
    
    @api.depends('contract_id')
    def _compute_pf_contribution(self):
        for slip in self:
            if slip.contract_id and slip.contract_id.pf_eligible:
                basic = slip.contract_id.wage
                slip.employee_pf_contribution = basic * 0.10  # 10% employee
                slip.employer_pf_contribution = basic * 0.10  # 10% employer
            else:
                slip.employee_pf_contribution = 0.0
                slip.employer_pf_contribution = 0.0
    
    def compute_tds_bd(self):
        """Compute TDS per Bangladesh Income Tax Slabs"""
        self.ensure_one()
        
        # Annual taxable income
        annual_income = self.taxable_income * 12
        
        # 2023-2024 Tax Slabs (Male)
        tax = 0.0
        remaining = annual_income
        
        # First 3,50,000 - Nil
        if remaining <= 350000:
            return 0.0
        remaining -= 350000
        
        # Next 1,00,000 - 5%
        slab = min(remaining, 100000)
        tax += slab * 0.05
        remaining -= slab
        if remaining <= 0:
            return tax / 12
        
        # Next 4,00,000 - 10%
        slab = min(remaining, 400000)
        tax += slab * 0.10
        remaining -= slab
        if remaining <= 0:
            return tax / 12
        
        # Next 5,00,000 - 15%
        slab = min(remaining, 500000)
        tax += slab * 0.15
        remaining -= slab
        if remaining <= 0:
            return tax / 12
        
        # Next 5,00,000 - 20%
        slab = min(remaining, 500000)
        tax += slab * 0.20
        remaining -= slab
        if remaining <= 0:
            return tax / 12
        
        # Above 14,50,000 - 25%
        tax += remaining * 0.25
        
        # Investment rebate (up to 3,00,000 at 15%)
        max_rebate = min(annual_income * 0.20, 300000) * 0.15
        tax = max(0, tax - max_rebate)
        
        # Monthly TDS
        return tax / 12
```

### 6.2 Salary Rules Configuration

```xml
<!-- data/hr_salary_rules.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<odoo>
    <!-- Basic Salary -->
    <record id="hr_rule_basic_bd" model="hr.salary.rule">
        <field name="name">Basic Salary</field>
        <field name="code">BASIC</field>
        <field name="category_id" ref="hr_payroll.BASIC"/>
        <field name="sequence">1</field>
        <field name="amount_select">code</field>
        <field name="amount_python_compute">
result = contract.wage
        </field>
    </record>
    
    <!-- House Rent Allowance (50% of basic or 25,000, whichever is lower) -->
    <record id="hr_rule_hra_bd" model="hr.salary.rule">
        <field name="name">House Rent Allowance</field>
        <field name="code">HRA</field>
        <field name="category_id" ref="hr_payroll.ALW"/>
        <field name="sequence">2</field>
        <field name="amount_select">code</field>
        <field name="amount_python_compute">
result = min(categories.BASIC * 0.50, 25000)
        </field>
    </record>
    
    <!-- Medical Allowance (10% of basic or 10,000) -->
    <record id="hr_rule_medical_bd" model="hr.salary.rule">
        <field name="name">Medical Allowance</field>
        <field name="code">MED</field>
        <field name="category_id" ref="hr_payroll.ALW"/>
        <field name="sequence">3</field>
        <field name="amount_select">code</field>
        <field name="amount_python_compute">
result = min(categories.BASIC * 0.10, 10000)
        </field>
    </record>
    
    <!-- Conveyance Allowance (Non-taxable up to 2,500) -->
    <record id="hr_rule_conveyance_bd" model="hr.salary.rule">
        <field name="name">Conveyance Allowance</field>
        <field name="code">CONV</field>
        <field name="category_id" ref="hr_payroll.ALW"/>
        <field name="sequence">4</field>
        <field name="amount_select">fix</field>
        <field name="amount_fix">2500</field>
    </record>
    
    <!-- Employee PF Contribution (10%) -->
    <record id="hr_rule_pf_employee_bd" model="hr.salary.rule">
        <field name="name">PF Contribution (Employee)</field>
        <field name="code">PF_EMP</field>
        <field name="category_id" ref="hr_payroll.DED"/>
        <field name="sequence">50</field>
        <field name="amount_select">code</field>
        <field name="amount_python_compute">
if contract.pf_eligible:
    result = categories.BASIC * 0.10
else:
    result = 0
        </field>
    </record>
    
    <!-- TDS on Salary (Section 50) -->
    <record id="hr_rule_tds_bd" model="hr.salary.rule">
        <field name="name">Income Tax (TDS)</field>
        <field name="code">TDS</field>
        <field name="category_id" ref="hr_payroll.DED"/>
        <field name="sequence">60</field>
        <field name="amount_select">code</field>
        <field name="amount_python_compute">
result = payslip.compute_tds_bd()
        </field>
    </record>
    
    <!-- Net Salary -->
    <record id="hr_rule_net_bd" model="hr.salary.rule">
        <field name="name">Net Salary</field>
        <field name="code">NET</field>
        <field name="category_id" ref="hr_payroll.NET"/>
        <field name="sequence">100</field>
        <field name="amount_select">code</field>
        <field name="amount_python_compute">
result = categories.BASIC + categories.ALW - categories.DED
        </field>
    </record>
</odoo>
```

---

## 7. ACCOUNTING LOCALIZATION

### 7.1 Bangladesh Chart of Accounts

```xml
<!-- data/chart_of_accounts.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<odoo>
    <!-- ASSETS -->
    <!-- Current Assets -->
    <record id="account_cash_bd" model="account.account.template">
        <field name="code">110010</field>
        <field name="name">Cash in Hand</field>
        <field name="account_type">asset_current</field>
    </record>
    
    <record id="account_bank_bd" model="account.account.template">
        <field name="code">110020</field>
        <field name="name">Bank Accounts</field>
        <field name="account_type">asset_current</field>
    </record>
    
    <record id="account_receivable_bd" model="account.account.template">
        <field name="code">110100</field>
        <field name="name">Accounts Receivable</field>
        <field name="account_type">asset_receivable</field>
        <field name="reconcile">True</field>
    </record>
    
    <!-- VAT Accounts -->
    <record id="account_vat_input_bd" model="account.account.template">
        <field name="code">110210</field>
        <field name="name">VAT Input (Recoverable)</field>
        <field name="account_type">asset_current</field>
    </record>
    
    <record id="account_vat_deferred_bd" model="account.account.template">
        <field name="code">110220</field>
        <field name="name">Deferred VAT (Imports)</field>
        <field name="account_type">asset_current</field>
    </record>
    
    <!-- LIABILITIES -->
    <record id="account_payable_bd" model="account.account.template">
        <field name="code">210100</field>
        <field name="name">Accounts Payable</field>
        <field name="account_type">liability_payable</field>
        <field name="reconcile">True</field>
    </record>
    
    <record id="account_vat_output_bd" model="account.account.template">
        <field name="code">210210</field>
        <field name="name">VAT Output Payable</field>
        <field name="account_type">liability_current</field>
    </record>
    
    <record id="account_vat_payable_bd" model="account.account.template">
        <field name="code">210220</field>
        <field name="name">VAT Payable to NBR</field>
        <field name="account_type">liability_current</field>
    </record>
    
    <record id="account_tds_payable_bd" model="account.account.template">
        <field name="code">210230</field>
        <field name="name">TDS Payable to NBR</field>
        <field name="account_type">liability_current</field>
    </record>
    
    <record id="account_sd_payable_bd" model="account.account.template">
        <field name="code">210240</field>
        <field name="name">SD Payable to NBR</field>
        <field name="account_type">liability_current</field>
    </record>
    
    <!-- INCOME -->
    <record id="account_sales_local_bd" model="account.account.template">
        <field name="code">410100</field>
        <field name="name">Sales - Local (15% VAT)</field>
        <field name="account_type">income</field>
    </record>
    
    <record id="account_sales_export_bd" model="account.account.template">
        <field name="code">410200</field>
        <field name="name">Sales - Export (Zero Rated)</field>
        <field name="account_type">income</field>
    </record>
    
    <record id="account_sales_exempt_bd" model="account.account.template">
        <field name="code">410300</field>
        <field name="name">Sales - Exempt (Fresh Milk)</field>
        <field name="account_type">income</field>
    </record>
    
    <!-- EXPENSES -->
    <record id="account_cogs_bd" model="account.account.template">
        <field name="code">510100</field>
        <field name="name">Cost of Goods Sold</field>
        <field name="account_type">expense_direct_cost</field>
    </record>
    
    <record id="account_salary_bd" model="account.account.template">
        <field name="code">610100</field>
        <field name="name">Salary & Wages</field>
        <field name="account_type">expense</field>
    </record>
</odoo>
```

---

## 8. REPORT & COMPLIANCE DOCUMENTS

### 8.1 NBR Returns

| Form | Description | Frequency | Due Date |
|------|-------------|-----------|----------|
| Mushak 6.1 | Sales Register | Monthly | 15th of next month |
| Mushak 6.2 | Purchase Register | Monthly | 15th of next month |
| Mushak 6.3 | Credit Notes | As required | With return |
| Mushak 6.10 | Challan | Payment | Payment date |
| Mushak 9.1 | VAT Return | Monthly/Quarterly | 15th of next month |
| Mushak 9.2 | Turnover Declaration | Annually | 30th June |
| TDS Return | TDS Summary | Quarterly | 15th of next month |

### 8.2 Report Generation

```python
# report/vat_reports.py
from odoo import models, api

class ReportVatReturn(models.AbstractModel):
    _name = 'report.smart_bd_localization.report_mushak_9_1'
    _description = 'Mushak 9.1 Report'
    
    @api.model
    def _get_report_values(self, docids, data=None):
        docs = self.env['bd.vat.return'].browse(docids)
        return {
            'doc_ids': docids,
            'doc_model': 'bd.vat.return',
            'docs': docs,
            'data': data,
        }
```

---

## 9. DATA LOCALIZATION REQUIREMENTS

### 9.1 Bangladesh Data Protection

As per Digital Security Act 2018 and upcoming Data Protection Act:

| Requirement | Implementation |
|-------------|----------------|
| **Data Location** | Primary database hosted in Bangladesh |
| **Personal Data** | Encryption at rest and in transit |
| **Financial Data** | Minimum 7 years retention |
| **Cross-border Transfer** | Consent required for sensitive data |
| **Breach Notification** | Within 72 hours to authorities |

### 9.2 Backup & Archival

```python
# Data retention policy
RETENTION_POLICY = {
    'vat_returns': '7 years',
    'invoice_data': '7 years',
    'payroll_records': '7 years',
    'audit_trails': '10 years',
    'monthly_backups': '3 years',
    'daily_backups': '30 days',
}
```

---

## 10. APPENDICES

### Appendix A: BIN/TIN Registration

| Document | Description | Format |
|----------|-------------|--------|
| BIN | Business Identification Number | 13 digits (XXX-XXXX-XXXX) |
| TIN | Taxpayer Identification Number | 12 digits |
| RJSC Registration | Company registration number | Varies |

### Appendix B: Compliance Calendar

| Month | Activity | Responsibility |
|-------|----------|----------------|
| Monthly | VAT Return (15th) | Accounts |
| Monthly | TDS Payment (15th) | Accounts |
| Quarterly | TDS Return | Accounts |
| July | Annual turnover declaration | Management |
| January | Income tax return | Tax Consultant |

---

**END OF BANGLADESH LOCALIZATION MODULE TECHNICAL SPECIFICATION**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | February 15, 2026 | Odoo Lead | Initial version |
