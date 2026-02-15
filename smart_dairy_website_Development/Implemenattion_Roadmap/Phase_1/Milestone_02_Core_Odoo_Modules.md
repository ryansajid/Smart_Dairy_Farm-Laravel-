# Milestone 2: Core Odoo 19 CE Modules Installation & Configuration

## Smart Dairy Digital Smart Portal + ERP — Phase 1: Foundation

| Field            | Detail                                                        |
|------------------|---------------------------------------------------------------|
| **Milestone**    | 2 of 10                                                       |
| **Title**        | Core Odoo 19 CE Modules Installation & Configuration           |
| **Phase**        | Phase 1 — Foundation (Part A: Infrastructure & Core Setup)     |
| **Days**         | Days 11–20 (of 100)                                           |
| **Version**      | 1.0                                                           |
| **Status**       | Draft                                                         |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend/Mobile Lead) |
| **Last Updated** | 2026-02-03                                                    |

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

Install, configure, and validate all 13+ core Odoo 19 CE modules that form the ERP backbone for Smart Dairy, with Bangladesh-specific localization, dairy-industry configurations, and OWL-based custom dashboard components.

### 1.2 Objectives

1. Install and configure Sales & CRM modules with dairy product categories and pricelists
2. Set up Inventory & Warehouse with FEFO, cold chain zones, and lot tracking
3. Configure Purchase & Procurement with supplier management and vendor rating
4. Establish Manufacturing & MRP with dairy-specific BOMs and work centers
5. Implement Accounting & Finance with Bangladesh VAT/Mushak compliance
6. Configure HR & Payroll per Bangladesh labor law
7. Set up Project Management, Quality Management, and compliance modules
8. Create OWL dashboard widgets for each major module
9. Perform cross-module integration testing
10. Conduct Milestone 2 review with stakeholder demo

### 1.3 Key Deliverables

| # | Deliverable                              | Owner  | Format              |
|---|------------------------------------------|--------|---------------------|
| 1 | Sales & CRM configured                   | Dev 1  | Odoo module config   |
| 2 | Inventory with FEFO & cold chain          | Dev 1  | Odoo module config   |
| 3 | Purchase & supplier management            | Dev 1  | Odoo module config   |
| 4 | Manufacturing with dairy BOMs             | Dev 1  | Odoo module config   |
| 5 | Accounting with Bangladesh localization   | Dev 1  | Odoo module config   |
| 6 | HR & Payroll (Bangladesh labor law)       | Dev 2  | Odoo module config   |
| 7 | Project & Quality Management              | Dev 2  | Odoo module config   |
| 8 | OWL dashboard widgets per module          | Dev 3  | JavaScript/XML       |
| 9 | Bangladesh localization data              | Dev 2  | XML data files       |
| 10| Integration test results                  | All    | Test report          |

### 1.4 Prerequisites

- Milestone 1 complete: Docker environment, Odoo 19 CE running, PostgreSQL 16, Redis 7, CI/CD pipeline, monitoring stack

### 1.5 Success Criteria

- [ ] All 13+ core modules installed and accessible in Odoo menu
- [ ] Sales cycle works end-to-end (quotation → order → delivery → invoice → payment)
- [ ] Inventory tracks lots with FEFO and expiry alerts
- [ ] Manufacturing produces from BOM with quality checks
- [ ] Accounting generates Bangladesh-compliant VAT returns and Mushak forms
- [ ] HR Payroll calculates salaries per Bangladesh tax slabs
- [ ] All OWL dashboards render with sample data
- [ ] Cross-module integration tests pass

---

## 2. Requirement Traceability Matrix

| Req ID        | Source      | Requirement Description                          | Day(s)  |
|---------------|-------------|--------------------------------------------------|---------|
| BRD-ERP-001   | BRD Mod 5   | Sales order management                            | 11      |
| BRD-ERP-002   | BRD Mod 5   | CRM pipeline management                           | 11      |
| BRD-ERP-005   | BRD Mod 5   | Multi-location inventory management                | 12      |
| BRD-ERP-006   | BRD Mod 5   | Lot tracking and FEFO                              | 12      |
| BRD-ERP-007   | BRD Mod 5   | Expiry management for perishables                  | 12      |
| BRD-ERP-010   | BRD Mod 5   | Purchase order automation                          | 13      |
| BRD-ERP-012   | BRD Mod 5   | Supplier management and rating                     | 13      |
| BRD-ERP-015   | BRD Mod 5   | BOM management for dairy products                  | 14      |
| BRD-ERP-016   | BRD Mod 5   | Production planning and yield tracking             | 14      |
| BRD-ERP-020   | BRD Mod 5   | Bangladesh Chart of Accounts                       | 15      |
| BRD-ERP-021   | BRD Mod 5   | VAT/Mushak compliance                              | 15      |
| BRD-ERP-025   | BRD Mod 5   | AR/AP management                                   | 16      |
| BRD-ERP-030   | BRD Mod 5   | Bangladesh labor law compliance                    | 17      |
| BRD-ERP-035   | BRD Mod 5   | Quality management at check points                 | 19      |
| B-005          | Impl Guide  | Bangladesh Localization Module                     | 15–17   |
| B-001 §3      | Impl Guide  | TDD — Module architecture                          | All     |

---

## 3. Day-by-Day Breakdown

---

### Day 11 — Sales & CRM Module

**Objective:** Install and configure the Sales and CRM modules with Smart Dairy product categories, pricing tiers, sales teams, and CRM pipeline.

#### Dev 1 — Backend Lead (8h)

**Tasks:**
1. Install `sale`, `sale_management`, `crm` modules — 1h
2. Create Smart Dairy product categories — 2h
3. Configure pricelists and sales workflow — 3h
4. Set up CRM pipeline stages — 2h

```python
# smart_dairy_addons/smart_bd_local/data/product_categories.xml
# Smart Dairy Product Category Hierarchy
"""
Dairy Products/
├── Fresh Milk/
│   ├── Full Cream Milk
│   ├── Standardized Milk
│   ├── Toned Milk
│   ├── Lactose-Free Milk
│   └── A2 Milk
├── Fermented Products/
│   ├── Plain Dahi (Curd)
│   ├── Greek Yogurt
│   ├── Misti Doi (Sweet Yogurt)
│   ├── Lassi
│   └── Mattha (Buttermilk)
├── Value-Added Products/
│   ├── Butter
│   ├── Ghee
│   ├── Paneer
│   ├── Cheese
│   └── Ice Cream
└── Premium Organic/
    ├── Organic Full Cream
    ├── Organic Ghee
    └── A2 Desi Milk
"""
```

```xml
<!-- smart_dairy_addons/smart_bd_local/data/product_categories.xml -->
<odoo>
  <data>
    <record id="categ_dairy" model="product.category">
      <field name="name">Dairy Products</field>
    </record>
    <record id="categ_fresh_milk" model="product.category">
      <field name="name">Fresh Milk</field>
      <field name="parent_id" ref="categ_dairy"/>
    </record>
    <record id="categ_fermented" model="product.category">
      <field name="name">Fermented Products</field>
      <field name="parent_id" ref="categ_dairy"/>
    </record>
    <record id="categ_value_added" model="product.category">
      <field name="name">Value-Added Products</field>
      <field name="parent_id" ref="categ_dairy"/>
    </record>
    <record id="categ_premium" model="product.category">
      <field name="name">Premium Organic</field>
      <field name="parent_id" ref="categ_dairy"/>
    </record>

    <!-- Sample Products -->
    <record id="product_full_cream_500ml" model="product.template">
      <field name="name">Saffron Organic Full Cream Milk 500ml</field>
      <field name="categ_id" ref="categ_fresh_milk"/>
      <field name="type">product</field>
      <field name="list_price">80.00</field>
      <field name="uom_id" ref="uom.product_uom_unit"/>
      <field name="tracking">lot</field>
      <field name="use_expiration_date">True</field>
      <field name="expiration_time">7</field>
    </record>
    <record id="product_full_cream_1l" model="product.template">
      <field name="name">Saffron Organic Full Cream Milk 1L</field>
      <field name="categ_id" ref="categ_fresh_milk"/>
      <field name="type">product</field>
      <field name="list_price">160.00</field>
      <field name="tracking">lot</field>
      <field name="use_expiration_date">True</field>
      <field name="expiration_time">7</field>
    </record>

    <!-- Pricelists -->
    <record id="pricelist_b2c" model="product.pricelist">
      <field name="name">B2C Retail (MRP)</field>
      <field name="currency_id" ref="base.BDT"/>
    </record>
    <record id="pricelist_b2b_distributor" model="product.pricelist">
      <field name="name">B2B Distributor (15% discount)</field>
      <field name="currency_id" ref="base.BDT"/>
    </record>
    <record id="pricelist_b2b_retailer" model="product.pricelist">
      <field name="name">B2B Retailer (10% discount)</field>
      <field name="currency_id" ref="base.BDT"/>
    </record>
    <record id="pricelist_horeca" model="product.pricelist">
      <field name="name">HORECA (12% discount)</field>
      <field name="currency_id" ref="base.BDT"/>
    </record>

    <!-- CRM Stages -->
    <record id="crm_stage_new" model="crm.stage">
      <field name="name">New Lead</field>
      <field name="sequence">1</field>
    </record>
    <record id="crm_stage_qualified" model="crm.stage">
      <field name="name">Qualified</field>
      <field name="sequence">2</field>
    </record>
    <record id="crm_stage_proposal" model="crm.stage">
      <field name="name">Proposal Sent</field>
      <field name="sequence">3</field>
    </record>
    <record id="crm_stage_negotiation" model="crm.stage">
      <field name="name">Negotiation</field>
      <field name="sequence">4</field>
    </record>
    <record id="crm_stage_won" model="crm.stage">
      <field name="name">Won</field>
      <field name="sequence">5</field>
      <field name="is_won">True</field>
    </record>
  </data>
</odoo>
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**
1. Configure sales teams (B2C, B2B Wholesale, Institutional, Export) — 2h
2. Set up email templates for quotation and invoice — 2h
3. Configure sales reporting and analytics — 2h
4. Set up Bangladesh-specific tax rules for sales — 2h

```xml
<!-- Tax configuration for Bangladesh -->
<record id="tax_vat_15" model="account.tax">
  <field name="name">VAT 15% (Standard)</field>
  <field name="type_tax_use">sale</field>
  <field name="amount_type">percent</field>
  <field name="amount">15.0</field>
  <field name="description">VAT@15%</field>
</record>
<record id="tax_vat_7_5" model="account.tax">
  <field name="name">VAT 7.5% (Reduced)</field>
  <field name="type_tax_use">sale</field>
  <field name="amount_type">percent</field>
  <field name="amount">7.5</field>
</record>
<record id="tax_vat_exempt" model="account.tax">
  <field name="name">VAT Exempt (Raw Milk)</field>
  <field name="type_tax_use">sale</field>
  <field name="amount_type">percent</field>
  <field name="amount">0.0</field>
</record>
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**
1. Customize sales order form with Smart Dairy branding — 3h
2. Create OWL Sales KPI dashboard widget — 3h
3. Customize CRM kanban with dairy-specific fields — 2h

```javascript
/** @odoo-module **/
// smart_dairy_addons/smart_bd_local/static/src/components/sales_kpi/sales_kpi.js
import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class SalesKPIDashboard extends Component {
    static template = "smart_bd_local.SalesKPIDashboard";

    setup() {
        this.orm = useService("orm");
        this.state = useState({
            todayOrders: 0,
            todayRevenue: 0,
            monthRevenue: 0,
            topProducts: [],
            loading: true,
        });
        onWillStart(async () => {
            await this.loadData();
        });
    }

    async loadData() {
        const today = new Date().toISOString().split('T')[0];
        const monthStart = today.substring(0, 8) + '01';

        // Today's orders
        const todayOrders = await this.orm.searchCount("sale.order", [
            ["date_order", ">=", today],
            ["state", "in", ["sale", "done"]],
        ]);

        // Today's revenue
        const todayData = await this.orm.readGroup("sale.order", [
            ["date_order", ">=", today],
            ["state", "in", ["sale", "done"]],
        ], ["amount_total:sum"], []);

        // Month revenue
        const monthData = await this.orm.readGroup("sale.order", [
            ["date_order", ">=", monthStart],
            ["state", "in", ["sale", "done"]],
        ], ["amount_total:sum"], []);

        this.state.todayOrders = todayOrders;
        this.state.todayRevenue = todayData[0]?.amount_total || 0;
        this.state.monthRevenue = monthData[0]?.amount_total || 0;
        this.state.loading = false;
    }
}
```

**End-of-Day 11 Deliverables:**
- [ ] Sales & CRM modules installed and configured
- [ ] Product categories created with sample products
- [ ] Pricelists configured (B2C, B2B Distributor, Retailer, HORECA)
- [ ] CRM pipeline stages created
- [ ] Sales teams configured
- [ ] Sales KPI OWL widget functional

---

### Day 12 — Inventory & Warehouse Management

**Objective:** Configure multi-location warehouses with FEFO, cold chain zones, lot tracking, and expiry management for dairy products.

#### Dev 1 — Backend Lead (8h)

```xml
<!-- Warehouse Structure Configuration -->
<record id="warehouse_main" model="stock.warehouse">
  <field name="name">Smart Dairy Main Farm</field>
  <field name="code">SDFM</field>
</record>

<!-- Stock Locations -->
<record id="location_cold_storage" model="stock.location">
  <field name="name">Cold Storage (2-8°C)</field>
  <field name="usage">internal</field>
  <field name="location_id" ref="warehouse_main_lot_stock"/>
</record>
<record id="location_frozen" model="stock.location">
  <field name="name">Frozen Storage (-18°C)</field>
  <field name="usage">internal</field>
  <field name="location_id" ref="warehouse_main_lot_stock"/>
</record>
<record id="location_raw_material" model="stock.location">
  <field name="name">Raw Material Store</field>
  <field name="usage">internal</field>
  <field name="location_id" ref="warehouse_main_lot_stock"/>
</record>
<record id="location_finished_goods" model="stock.location">
  <field name="name">Finished Goods</field>
  <field name="usage">internal</field>
  <field name="location_id" ref="warehouse_main_lot_stock"/>
</record>
<record id="location_distribution" model="stock.location">
  <field name="name">Distribution Center</field>
  <field name="usage">internal</field>
  <field name="location_id" ref="warehouse_main_lot_stock"/>
</record>

<!-- FEFO Removal Strategy -->
<record id="removal_fefo" model="product.removal">
  <field name="name">First Expiry First Out (FEFO)</field>
  <field name="method">fefo</field>
</record>
```

#### Dev 2 — Full-Stack / DevOps (8h)

- Configure multi-location inventory routes
- Set up FIFO stock valuation for dairy products
- Configure reordering rules and safety stock levels
- Implement barcode/QR code generation for lot tracking

#### Dev 3 — Frontend / Mobile Lead (8h)

- Customize inventory dashboard with real-time stock by category
- Create OWL expiry tracking widget (red/yellow/green indicators)
- Customize stock picking form for dairy fields (temperature at receipt)

```javascript
/** @odoo-module **/
// Expiry Alert Widget
export class ExpiryAlertWidget extends Component {
    static template = "smart_bd_local.ExpiryAlertWidget";

    setup() {
        this.orm = useService("orm");
        this.state = useState({ critical: 0, warning: 0, ok: 0, expired: 0 });
        onWillStart(() => this.loadExpiryData());
    }

    async loadExpiryData() {
        const today = new Date();
        const lots = await this.orm.searchRead("stock.lot", [
            ["expiration_date", "!=", false],
            ["product_qty", ">", 0],
        ], ["expiration_date", "product_qty"]);

        lots.forEach(lot => {
            const expiry = new Date(lot.expiration_date);
            const daysLeft = Math.ceil((expiry - today) / (1000 * 60 * 60 * 24));
            if (daysLeft < 0) this.state.expired++;
            else if (daysLeft <= 2) this.state.critical++;
            else if (daysLeft <= 5) this.state.warning++;
            else this.state.ok++;
        });
    }
}
```

**End-of-Day 12 Deliverables:**
- [ ] Warehouse structure: Main Farm, Cold Storage, Frozen, Raw Material, Finished Goods, Distribution
- [ ] FEFO removal strategy configured for all dairy products
- [ ] Lot tracking mandatory on all dairy products
- [ ] Expiry date management with alerts at 75%, 50%, 25% shelf life
- [ ] Expiry tracking OWL widget functional

---

### Day 13 — Purchase & Procurement

**Objective:** Configure the purchase module with supplier categories, PO workflows, vendor rating, and blanket orders for regular feed supply.

#### Dev 1 — Backend Lead (8h)

```python
# smart_dairy_addons/smart_bd_local/models/vendor_rating.py
from odoo import models, fields, api

class VendorRating(models.Model):
    _name = 'smart.vendor.rating'
    _description = 'Vendor Performance Rating'

    partner_id = fields.Many2one('res.partner', string='Vendor', required=True)
    period_start = fields.Date(string='Period Start', required=True)
    period_end = fields.Date(string='Period End', required=True)
    delivery_score = fields.Float(string='Delivery Score (0-100)', default=0)
    quality_score = fields.Float(string='Quality Score (0-100)', default=0)
    price_score = fields.Float(string='Price Competitiveness (0-100)', default=0)
    communication_score = fields.Float(string='Communication (0-100)', default=0)
    overall_score = fields.Float(string='Overall Score', compute='_compute_overall', store=True)
    rating = fields.Selection([
        ('A', 'Excellent (80-100)'),
        ('B', 'Good (60-79)'),
        ('C', 'Average (40-59)'),
        ('D', 'Poor (0-39)'),
    ], string='Rating', compute='_compute_overall', store=True)

    @api.depends('delivery_score', 'quality_score', 'price_score', 'communication_score')
    def _compute_overall(self):
        for rec in self:
            score = (rec.delivery_score * 0.3 + rec.quality_score * 0.35 +
                     rec.price_score * 0.2 + rec.communication_score * 0.15)
            rec.overall_score = score
            if score >= 80: rec.rating = 'A'
            elif score >= 60: rec.rating = 'B'
            elif score >= 40: rec.rating = 'C'
            else: rec.rating = 'D'
```

#### Dev 2 — Full-Stack / DevOps (8h)

- Configure supplier portal access
- Set up three-way matching (PO → Receipt → Invoice)
- Configure multi-currency for imports (equipment, genetics)

#### Dev 3 — Frontend / Mobile Lead (8h)

- Create supplier scorecard OWL component
- Design procurement dashboard

**End-of-Day 13 Deliverables:**
- [ ] Purchase module installed with supplier categories
- [ ] Vendor rating system implemented
- [ ] Blanket orders configured for feed supply
- [ ] Three-way matching enabled
- [ ] Procurement dashboard functional

---

### Day 14 — Manufacturing & MRP

**Objective:** Configure Manufacturing with dairy-specific Bills of Materials, work centers, routings, and yield tracking.

#### Dev 1 — Backend Lead (8h)

```xml
<!-- Dairy BOMs -->
<record id="bom_pasteurized_milk_1l" model="mrp.bom">
  <field name="product_tmpl_id" ref="product_full_cream_1l"/>
  <field name="product_qty">100</field>
  <field name="product_uom_id" ref="uom.product_uom_unit"/>
  <field name="type">normal</field>
</record>
<!-- BOM Line: Raw Milk (105L input for 100 units, 5% loss) -->
<record id="bom_line_raw_milk" model="mrp.bom.line">
  <field name="bom_id" ref="bom_pasteurized_milk_1l"/>
  <field name="product_id" ref="product_raw_milk"/>
  <field name="product_qty">105</field>
  <field name="product_uom_id" ref="uom.product_uom_litre"/>
</record>
<!-- BOM Line: Packaging (1L carton) -->
<record id="bom_line_carton_1l" model="mrp.bom.line">
  <field name="bom_id" ref="bom_pasteurized_milk_1l"/>
  <field name="product_id" ref="product_carton_1l"/>
  <field name="product_qty">100</field>
</record>

<!-- Work Centers -->
<record id="workcenter_reception" model="mrp.workcenter">
  <field name="name">Milk Reception</field>
  <field name="code">RECV</field>
  <field name="capacity">5000</field>
  <field name="time_start">15</field>
  <field name="time_stop">10</field>
  <field name="costs_hour">500</field>
</record>
<record id="workcenter_pasteurization" model="mrp.workcenter">
  <field name="name">Pasteurization Unit</field>
  <field name="code">PAST</field>
  <field name="capacity">2000</field>
  <field name="time_start">10</field>
  <field name="time_stop">15</field>
  <field name="costs_hour">800</field>
</record>
<record id="workcenter_packaging" model="mrp.workcenter">
  <field name="name">Packaging Line</field>
  <field name="code">PACK</field>
  <field name="capacity">3000</field>
  <field name="costs_hour">600</field>
</record>
```

#### Dev 2 — Full-Stack / DevOps (8h)

- Configure MPS (Master Production Schedule)
- Set up yield tracking (input raw milk vs output, loss %)
- Integrate quality checks at each work center stage

#### Dev 3 — Frontend / Mobile Lead (8h)

- Create manufacturing dashboard OWL (daily production, yields, quality)
- Customize production order form (batch temperature, pH, fat content)

**End-of-Day 14 Deliverables:**
- [ ] BOMs created for Pasteurized Milk, Yogurt, Ghee, Paneer
- [ ] Work centers configured (Reception, Pasteurization, Fermentation, Packaging, Cold Storage)
- [ ] Routings with time and cost defined
- [ ] Yield tracking functional
- [ ] Manufacturing dashboard OWL widget operational

---

### Day 15 — Accounting & Finance Part 1

**Objective:** Install accounting with Bangladesh localization (l10n_bd), configure Chart of Accounts, VAT structure, and Mushak forms.

#### Dev 1 — Backend Lead (8h)

```python
# Bangladesh VAT Configuration
# Standard: 15%, Reduced: 7.5%, Exempt: 0%
# Mushak Forms:
#   6.1 - Purchase Register
#   6.2 - Debit/Credit Notes
#   6.3 - Sales Register (VAT Challan)

# Custom Mushak Report Model
class MushakReport(models.TransientModel):
    _name = 'smart.mushak.report'
    _description = 'Mushak Form Generator'

    form_type = fields.Selection([
        ('6.1', 'Mushak 6.1 - Purchase Register'),
        ('6.2', 'Mushak 6.2 - Debit/Credit Note'),
        ('6.3', 'Mushak 6.3 - Sales VAT Challan'),
    ], required=True)
    date_from = fields.Date(required=True)
    date_to = fields.Date(required=True)

    def generate_report(self):
        if self.form_type == '6.3':
            invoices = self.env['account.move'].search([
                ('move_type', '=', 'out_invoice'),
                ('invoice_date', '>=', self.date_from),
                ('invoice_date', '<=', self.date_to),
                ('state', '=', 'posted'),
            ])
            data = {
                'invoices': invoices.ids,
                'form_type': self.form_type,
                'date_from': self.date_from,
                'date_to': self.date_to,
            }
            return self.env.ref('smart_bd_local.mushak_6_3_report').report_action(self, data=data)
```

#### Dev 2 — Full-Stack / DevOps (8h)

- Configure tax groups and TDS (withholding tax)
- Configure payment methods (bKash, Nagad, Rocket, bank, cash)
- Set up BDT currency and journal automation

#### Dev 3 — Frontend / Mobile Lead (8h)

- Customize accounting dashboard with KPIs
- Design Mushak form PDF templates
- Customize invoice templates with Smart Dairy branding

**End-of-Day 15 Deliverables:**
- [ ] Bangladesh Chart of Accounts configured
- [ ] VAT rates configured (15%, 7.5%, exempt)
- [ ] Mushak form generator implemented (6.1, 6.2, 6.3)
- [ ] Payment methods registered (bKash, Nagad, Rocket, bank, cash)
- [ ] Fiscal year and periods configured

---

### Day 16 — Accounting & Finance Part 2

**Objective:** Configure AR/AP workflows, bank reconciliation, budget management, and cost center accounting.

#### Dev 1 — Backend Lead (8h)

- Configure AR: B2C immediate payment, B2B Net 15/30/45 credit terms
- Set up AP with approval levels (≤BDT 50K auto, >50K manager, >200K director)
- Configure bank reconciliation rules
- Set up aged receivable/payable reports

#### Dev 2 — Full-Stack / DevOps (8h)

- Configure bank statement import (CSV/OFX for Bangladesh banks)
- Set up automatic reconciliation rules
- Configure budget management (annual/quarterly/monthly by department)
- Implement cost center accounting (Farm, Processing, Sales divisions)

#### Dev 3 — Frontend / Mobile Lead (8h)

- Create AR/AP aging dashboard OWL component
- Design budget vs actual variance reports
- Create financial analytics charts (revenue trends, cost breakdown)

**End-of-Day 16 Deliverables:**
- [ ] AR/AP workflows with credit terms and approval levels
- [ ] Bank reconciliation rules configured
- [ ] Budget management by department operational
- [ ] Cost center accounting for 3 divisions active
- [ ] Financial dashboard OWL widgets functional

---

### Day 17 — Human Resources & Payroll

**Objective:** Configure HR and Payroll modules compliant with Bangladesh labor law, including leave policies, tax slabs, and provident fund.

#### Dev 1 — Backend Lead (8h)

```python
# Bangladesh Payroll Structure
# Basic Salary: Base amount
# House Rent Allowance: 50% of Basic
# Medical Allowance: 10% of Basic
# Conveyance Allowance: Fixed BDT 3,000
# Festival Bonus: 2x Basic per year (Eid-ul-Fitr + Eid-ul-Adha)

class HRPayrollBangladesh(models.Model):
    _inherit = 'hr.payroll.structure'

    # Bangladesh-specific salary rules
    """
    Salary Structure:
    1. Gross = Basic + HRA(50%) + Medical(10%) + Conveyance(3000)
    2. Deductions:
       - Provident Fund (PF): 10% of Basic (employee) + 10% (employer)
       - Income Tax: Per Bangladesh tax slabs
       - Professional Tax: BDT 200/month
    3. Net = Gross - Deductions
    """

# Bangladesh Income Tax Slabs (2025-2026)
TAX_SLABS = [
    (350000, 0),       # First BDT 3,50,000: NIL
    (100000, 0.05),    # Next BDT 1,00,000: 5%
    (300000, 0.10),    # Next BDT 3,00,000: 10%
    (400000, 0.15),    # Next BDT 4,00,000: 15%
    (500000, 0.20),    # Next BDT 5,00,000: 20%
    (None, 0.25),      # Remaining: 25%
]

# Leave Types per Bangladesh Labor Act 2006
LEAVE_TYPES = {
    'annual': {'days': 14, 'carry_forward': True, 'max_carry': 28},
    'casual': {'days': 10, 'carry_forward': False},
    'sick': {'days': 14, 'carry_forward': False, 'medical_cert': True},
    'maternity': {'weeks': 16, 'paid': True},  # 8 weeks pre + 8 weeks post
    'paternity': {'days': 5, 'paid': True},
    'festival': {'days': 11, 'paid': True},
}
```

#### Dev 2 — Full-Stack / DevOps (8h)

- Configure salary rules and computation
- Set up provident fund and gratuity
- Configure overtime rules and bank salary disbursement

#### Dev 3 — Frontend / Mobile Lead (8h)

- Customize HR dashboard with workforce analytics
- Create payslip template (Bengali + English)
- Design attendance calendar view

**End-of-Day 17 Deliverables:**
- [ ] Department structure configured (Farm Ops, Processing, Sales, Finance, IT, Admin)
- [ ] Leave types per Bangladesh Labor Act
- [ ] Payroll structure with Bangladesh tax slabs
- [ ] Provident fund and gratuity rules
- [ ] Bilingual payslip template

---

### Day 18 — Project Management & Services

**Objective:** Configure Project module for internal operations and service products for Consultancy vertical.

#### Dev 1 — Backend Lead (8h)

- Install `project`, `timesheet` modules
- Configure project types (Software Dev, Farm Ops, Equipment Maintenance, Vet Services, Training)
- Set up recurring tasks for daily farm operations
- Create service products for consultancy (Vertical 4)

#### Dev 2 — Full-Stack / DevOps (8h)

- Configure project billing (time & material, fixed price)
- Integrate project with HR (employee assignments, utilization)
- Configure project reporting

#### Dev 3 — Frontend / Mobile Lead (8h)

- Customize project kanban with farm-specific views
- Create project dashboard OWL widget
- Design task form with dairy-specific custom fields

**End-of-Day 18 Deliverables:**
- [ ] Project types configured with task stages
- [ ] Recurring tasks set up for daily milking, feeding, health checks
- [ ] Consultancy service products created
- [ ] Project dashboard OWL widget functional

---

### Day 19 — Quality Management & Compliance

**Objective:** Configure quality check points for dairy processing pipeline and compliance checklists.

#### Dev 1 — Backend Lead (8h)

```python
# Quality Check Points for Dairy Processing
QUALITY_CHECKPOINTS = {
    'milk_reception': {
        'name': 'Milk Reception QC',
        'tests': [
            {'name': 'Temperature', 'min': 2.0, 'max': 8.0, 'unit': '°C'},
            {'name': 'Fat Content', 'min': 3.0, 'max': 6.0, 'unit': '%'},
            {'name': 'SNF Content', 'min': 8.0, 'max': 10.0, 'unit': '%'},
            {'name': 'Acidity', 'min': 0.12, 'max': 0.18, 'unit': '% lactic acid'},
            {'name': 'Antibiotics Test', 'type': 'pass_fail'},
            {'name': 'Adulteration Test', 'type': 'pass_fail'},
        ]
    },
    'pasteurization': {
        'name': 'Pasteurization QC',
        'tests': [
            {'name': 'Pasteurization Temp', 'min': 72.0, 'max': 75.0, 'unit': '°C'},
            {'name': 'Hold Time', 'min': 15, 'max': None, 'unit': 'seconds'},
            {'name': 'pH Level', 'min': 6.5, 'max': 6.8, 'unit': 'pH'},
            {'name': 'Phosphatase Test', 'type': 'pass_fail'},
        ]
    },
    'packaging': {
        'name': 'Packaging QC',
        'tests': [
            {'name': 'Seal Integrity', 'type': 'pass_fail'},
            {'name': 'Label Accuracy', 'type': 'pass_fail'},
            {'name': 'Net Weight', 'min': 495, 'max': 510, 'unit': 'ml'},
            {'name': 'Expiry Date Print', 'type': 'pass_fail'},
        ]
    },
    'storage': {
        'name': 'Cold Storage QC',
        'tests': [
            {'name': 'Storage Temperature', 'min': 2.0, 'max': 4.0, 'unit': '°C'},
            {'name': 'Shelf Life Check', 'type': 'pass_fail'},
        ]
    },
}
```

#### Dev 2 — Full-Stack / DevOps (8h)

- Integrate quality with manufacturing workflow (auto-trigger per stage)
- Non-conformance management (quarantine, rework, disposal)
- Implement traceability chain (raw material → finished product → customer)

#### Dev 3 — Frontend / Mobile Lead (8h)

- Create quality dashboard OWL (pass/fail rates, trending metrics)
- Quality check form with input validation for acceptable ranges
- Traceability lookup interface (lot number → full chain)

**End-of-Day 19 Deliverables:**
- [ ] Quality check points configured (Reception, Pasteurization, Packaging, Storage)
- [ ] Quality integrated with manufacturing workflow
- [ ] Non-conformance management workflow
- [ ] Traceability chain operational
- [ ] Quality dashboard OWL widget functional
- [ ] BSTI compliance checklist created

---

### Day 20 — Integration Testing & Milestone 2 Review

**Objective:** Verify all modules work together seamlessly. Conduct stakeholder demo and retrospective.

#### All Developers — Collaborative Day (8h each)

**Morning (4h) — End-to-End Integration Testing:**

| Test Case | Flow | Expected Result |
|-----------|------|-----------------|
| TC-M2-01 | Sales → Inventory → Accounting | Order creates delivery, invoice, payment posts to journal |
| TC-M2-02 | Purchase → Inventory → Accounting | PO receipt creates stock move, vendor bill matches |
| TC-M2-03 | Manufacturing → Inventory → Quality | Production consumes raw materials, quality checks trigger |
| TC-M2-04 | HR → Payroll → Accounting | Payroll run creates journal entries |
| TC-M2-05 | Sales → MRP | Sales forecast triggers production planning |

```python
# tests/test_cross_module_integration.py
class TestCrossModuleIntegration(TransactionCase):

    def test_sales_to_delivery_to_invoice(self):
        """Test full sales cycle: Quotation → SO → Delivery → Invoice → Payment"""
        # Create customer
        customer = self.env['res.partner'].create({
            'name': 'Test Retailer',
            'customer_rank': 1,
        })

        # Create sales order
        order = self.env['sale.order'].create({
            'partner_id': customer.id,
            'order_line': [(0, 0, {
                'product_id': self.env.ref('smart_bd_local.product_full_cream_1l').id,
                'product_uom_qty': 100,
                'price_unit': 160.00,
            })],
        })

        # Confirm order
        order.action_confirm()
        self.assertEqual(order.state, 'sale')

        # Validate delivery
        picking = order.picking_ids[0]
        picking.action_assign()
        picking.move_ids.write({'quantity': 100})
        picking.button_validate()
        self.assertEqual(picking.state, 'done')

        # Create and post invoice
        invoice = order._create_invoices()
        invoice.action_post()
        self.assertEqual(invoice.state, 'posted')
        self.assertAlmostEqual(invoice.amount_total, 18400.00)  # 160 * 100 * 1.15 VAT
```

**Midday (2h) — Bangladesh Configuration Verification:**
- Verify VAT calculation on sample invoices
- Verify Mushak 6.3 report generation
- Verify payroll with Bangladesh tax slabs
- Verify BDT currency formatting

**Afternoon (2h) — Milestone 2 Demo & Retrospective:**
- Walk through each module with sample data
- Collect stakeholder feedback
- Sprint retrospective
- Plan Milestone 3

**Milestone 2 Completion Checklist:**

| # | Module              | Installed | Configured | Tested | Status |
|---|---------------------|-----------|------------|--------|--------|
| 1 | Sales & CRM         | ☐         | ☐          | ☐      |        |
| 2 | Inventory (FEFO)    | ☐         | ☐          | ☐      |        |
| 3 | Purchase            | ☐         | ☐          | ☐      |        |
| 4 | Manufacturing (MRP) | ☐         | ☐          | ☐      |        |
| 5 | Accounting (BD)     | ☐         | ☐          | ☐      |        |
| 6 | HR & Payroll (BD)   | ☐         | ☐          | ☐      |        |
| 7 | Project Management  | ☐         | ☐          | ☐      |        |
| 8 | Quality Management  | ☐         | ☐          | ☐      |        |

---

## 4. Technical Specifications

### 4.1 Module Dependency Tree

```
base
├── web
│   └── website
│       └── website_sale
├── sale
│   ├── sale_management
│   └── sale_stock
├── purchase
│   └── purchase_stock
├── stock (inventory)
│   ├── stock_account
│   └── stock_sms
├── mrp (manufacturing)
│   ├── mrp_account
│   └── quality_control
├── account
│   ├── l10n_bd
│   ├── account_payment
│   └── account_budget
├── hr
│   ├── hr_payroll
│   ├── hr_attendance
│   └── hr_holidays
└── project
    └── project_timesheet
```

### 4.2 Database Tables Created

Each module creates tables. Key dairy-related extensions:
- `product_template`: Added fields for expiry, FEFO, dairy category
- `stock_location`: Temperature zone fields
- `mrp_workcenter`: Dairy-specific capacity fields
- `account_tax`: Bangladesh VAT/Mushak fields
- `hr_payroll_structure`: Bangladesh salary rules

---

## 5. Testing & Validation

- Module installation verification: all modules installed without error
- Cross-module workflow tests: 5 end-to-end scenarios
- Bangladesh localization: VAT, Mushak, tax slabs, currency
- OWL widget rendering: all dashboards load with sample data
- Performance: module installation doesn't degrade page load times

---

## 6. Risk & Mitigation

| # | Risk                                       | Probability | Impact | Mitigation                                    |
|---|--------------------------------------------|-------------|--------|-----------------------------------------------|
| 1 | Module compatibility conflicts             | Medium      | High   | Install one at a time, test after each         |
| 2 | Bangladesh localization gaps in Odoo 19    | High        | Medium | Custom smart_bd_local module fills gaps        |
| 3 | Performance degradation with many modules  | Medium      | Medium | Monitor with Prometheus after each install     |
| 4 | FEFO not working with Odoo stock           | Low         | High   | Implement custom FEFO if needed                |
| 5 | Mushak forms not matching NBR requirements | Medium      | High   | Engage Bangladesh accountant for validation    |

---

## 7. Dependencies & Handoffs

### 7.1 Input from Milestone 1
- Docker environment with Odoo 19 CE running
- PostgreSQL 16 with schemas and extensions
- CI/CD pipeline for validation

### 7.2 Output for Milestone 3
- Fully configured ERP backbone modules
- Product categories and data structures
- Quality management framework (used by farm quality checks)
- Accounting integration points (for farm expense tracking)

---

*End of Milestone 2 Document*
*Next: [Milestone 3 — Custom Module smart_farm_mgmt Part 1](./Milestone_03_Custom_Farm_Module.md)*
