# Milestone 11: Manufacturing MRP for Dairy Operations

## Smart Dairy Digital Smart Portal + ERP — Phase 2: ERP Core Configuration

| Field            | Detail                                                        |
|------------------|---------------------------------------------------------------|
| **Milestone**    | 11 of 20 (1 of 10 in Phase 2)                                |
| **Title**        | Manufacturing MRP for Dairy Operations                        |
| **Phase**        | Phase 2 — ERP Core Configuration (Part A: Advanced ERP)       |
| **Days**         | Days 101–110 (of 200)                                        |
| **Version**      | 1.0                                                          |
| **Status**       | Draft                                                        |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend/Mobile Lead) |
| **Last Updated** | 2026-02-03                                                   |

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

Configure and customize Odoo 19 CE Manufacturing (MRP) module for Smart Dairy's dairy processing operations. Implement Bill of Materials (BOMs) for all 6 dairy product categories (Fresh Milk, UHT Milk, Yogurt, Cheese, Butter, Ghee), configure work centers for dairy processing operations (pasteurization, homogenization, fermentation, packaging), and establish production workflows with yield tracking, co-product handling, and cost analysis.

### 1.2 Objectives

1. Configure Odoo Manufacturing module for dairy-specific production workflows
2. Create Bill of Materials (BOMs) for 6 dairy product families with multi-level structures
3. Set up Work Centers representing dairy processing equipment and stations
4. Implement Production Order workflow with state machine and approval gates
5. Develop yield tracking and variance analysis for batch processing
6. Configure co-product and by-product handling (whey, cream, buttermilk)
7. Implement shelf-life based production scheduling with expiry constraints
8. Integrate raw material consumption with inventory deductions
9. Establish production costing with material, labor, and overhead allocation
10. Create production dashboards with OWL components for real-time monitoring

### 1.3 Key Deliverables

| # | Deliverable | Owner | Day |
|---|-------------|-------|-----|
| D11.1 | MRP module configuration and dairy customization | Dev 1 | 101 |
| D11.2 | Work Center setup (6 stations) | Dev 1 | 101-102 |
| D11.3 | Fresh Milk BOM and routing | Dev 1 | 102 |
| D11.4 | UHT Milk BOM with sterilization process | Dev 1 | 103 |
| D11.5 | Yogurt BOM with fermentation workflow | Dev 1 | 103-104 |
| D11.6 | Cheese BOM with aging process | Dev 1 | 104 |
| D11.7 | Butter and Ghee BOMs | Dev 1 | 105 |
| D11.8 | Co-product handling (whey, cream, buttermilk) | Dev 1 | 105-106 |
| D11.9 | Yield tracking and variance module | Dev 1 | 106-107 |
| D11.10 | Production scheduling engine | Dev 2 | 107-108 |
| D11.11 | Production costing configuration | Dev 1 | 108 |
| D11.12 | Production dashboard (OWL) | Dev 3 | 109 |
| D11.13 | Integration testing and documentation | All | 110 |

### 1.4 Success Criteria

- [ ] All 6 dairy product BOMs configured and validated with correct material quantities
- [ ] Work Centers reflect actual dairy processing capacity and time standards
- [ ] Production orders complete end-to-end from raw milk receipt to finished goods
- [ ] Yield variance calculated accurately within 0.5% of actual
- [ ] Co-products (whey, cream) automatically generated during production
- [ ] Shelf-life constraints enforced in production scheduling
- [ ] Production cost includes material, labor, and overhead components
- [ ] Dashboard displays real-time production KPIs

### 1.5 Prerequisites

- **From Phase 1:**
  - Core Odoo modules installed (mrp, stock, product, account)
  - smart_farm_mgmt module with milk production tracking
  - Database optimized with proper indexing
  - CI/CD pipeline operational

---

## 2. Requirement Traceability Matrix

### 2.1 BRD Requirements Mapping

| BRD Req ID | Requirement Description | SRS Trace | Day | Owner |
|-----------|------------------------|-----------|-----|-------|
| BRD-MFG-001 | Product recipe/BOM management | SRS-MRP-001 | 102-105 | Dev 1 |
| BRD-MFG-002 | Multi-level BOM support | SRS-MRP-002 | 102-105 | Dev 1 |
| BRD-MFG-003 | Work center configuration | SRS-MRP-003 | 101-102 | Dev 1 |
| BRD-MFG-004 | Production order management | SRS-MRP-004 | 103-106 | Dev 1 |
| BRD-MFG-005 | Batch/lot production tracking | SRS-MRP-005 | 106-107 | Dev 1 |
| BRD-MFG-006 | Yield management and variance | SRS-MRP-006 | 106-107 | Dev 1 |
| BRD-MFG-007 | By-product handling | SRS-MRP-007 | 105-106 | Dev 1 |
| BRD-MFG-008 | Production scheduling | SRS-MRP-008 | 107-108 | Dev 2 |
| BRD-MFG-009 | Capacity planning | SRS-MRP-009 | 107-108 | Dev 2 |
| BRD-MFG-010 | Production costing | SRS-MRP-010 | 108 | Dev 1 |
| BRD-MFG-011 | Quality integration points | SRS-MRP-011 | 106 | Dev 1 |
| BRD-MFG-012 | Shelf-life management | SRS-MRP-012 | 107-108 | Dev 2 |
| BRD-MFG-013 | Production reports | SRS-MRP-013 | 109 | Dev 3 |
| BRD-MFG-014 | Real-time dashboard | SRS-MRP-014 | 109 | Dev 3 |
| BRD-MFG-015 | Mobile production entry | SRS-MRP-015 | 109 | Dev 3 |

### 2.2 Smart Dairy Product Specifications

| Product | Daily Volume | Yield % | Shelf Life | Key Ingredients |
|---------|-------------|---------|------------|-----------------|
| Fresh Pasteurized Milk | 500 L | 98% | 5 days | Raw milk |
| UHT Milk | 200 L | 97% | 180 days | Raw milk |
| Sweet Yogurt (Misti Doi) | 100 kg | 95% | 14 days | Milk, sugar, culture |
| Paneer Cheese | 20 kg | 18% | 7 days | Milk, citric acid |
| Butter | 15 kg | 4.5% | 30 days | Cream |
| Ghee | 10 kg | 85% | 365 days | Butter |

---

## 3. Day-by-Day Breakdown

---

### Day 101 — MRP Module Configuration & Work Centers

**Objective:** Configure the Manufacturing module for dairy operations and set up Work Centers representing processing equipment.

#### Dev 1 — Backend Lead (8h)

**Task 101.1.1: MRP Module Configuration (3h)**

```python
# smart_dairy_addons/smart_mrp_dairy/__manifest__.py
{
    'name': 'Smart Dairy Manufacturing',
    'version': '19.0.1.0.0',
    'category': 'Manufacturing/Dairy',
    'summary': 'Dairy-specific manufacturing extensions for Smart Dairy Ltd',
    'description': """
Smart Dairy Manufacturing Module
================================
Extensions to Odoo MRP for dairy processing:
- Dairy-specific BOMs with yield tracking
- Co-product and by-product handling
- Shelf-life based production scheduling
- Quality integration points
- Production costing for dairy operations
    """,
    'author': 'Smart Dairy Ltd',
    'website': 'https://smartdairy.com.bd',
    'license': 'LGPL-3',
    'depends': [
        'mrp',
        'mrp_workorder',
        'stock',
        'product',
        'account',
        'smart_farm_mgmt',
    ],
    'data': [
        'security/ir.model.access.csv',
        'security/mrp_dairy_security.xml',
        'data/mrp_dairy_data.xml',
        'data/work_center_data.xml',
        'views/mrp_bom_dairy_views.xml',
        'views/mrp_production_dairy_views.xml',
        'views/mrp_workcenter_dairy_views.xml',
        'views/mrp_dairy_menu.xml',
        'reports/production_report_templates.xml',
    ],
    'assets': {
        'web.assets_backend': [
            'smart_mrp_dairy/static/src/js/production_dashboard.js',
            'smart_mrp_dairy/static/src/xml/production_dashboard.xml',
            'smart_mrp_dairy/static/src/scss/production_dashboard.scss',
        ],
    },
    'installable': True,
    'application': False,
    'auto_install': False,
}
```

**Task 101.1.2: BOM Extension Model (2h)**

```python
# smart_dairy_addons/smart_mrp_dairy/models/mrp_bom_dairy.py
from odoo import models, fields, api, _
from odoo.exceptions import ValidationError

class MrpBomDairy(models.Model):
    _inherit = 'mrp.bom'

    # Dairy-specific fields
    is_dairy_product = fields.Boolean(
        string='Is Dairy Product',
        default=False,
        help='Enable dairy-specific features for this BOM'
    )
    dairy_product_type = fields.Selection([
        ('fresh_milk', 'Fresh Pasteurized Milk'),
        ('uht_milk', 'UHT Milk'),
        ('yogurt', 'Yogurt / Fermented'),
        ('cheese', 'Cheese / Paneer'),
        ('butter', 'Butter'),
        ('ghee', 'Ghee / Clarified Butter'),
        ('cream', 'Cream'),
        ('other', 'Other Dairy Product'),
    ], string='Dairy Product Type')

    # Yield Management
    expected_yield_percentage = fields.Float(
        string='Expected Yield %',
        digits=(5, 2),
        default=100.0,
        help='Expected yield percentage from raw materials'
    )
    yield_variance_tolerance = fields.Float(
        string='Yield Variance Tolerance %',
        digits=(5, 2),
        default=2.0,
        help='Acceptable variance from expected yield before alert'
    )

    # Shelf Life
    shelf_life_days = fields.Integer(
        string='Shelf Life (Days)',
        help='Product shelf life in days from production date'
    )
    min_remaining_shelf_life = fields.Integer(
        string='Min Remaining Shelf Life for Dispatch (Days)',
        help='Minimum remaining shelf life required for customer dispatch'
    )

    # Storage Requirements
    storage_temp_min = fields.Float(
        string='Min Storage Temp (°C)',
        digits=(4, 1),
        help='Minimum storage temperature in Celsius'
    )
    storage_temp_max = fields.Float(
        string='Max Storage Temp (°C)',
        digits=(4, 1),
        help='Maximum storage temperature in Celsius'
    )
    requires_cold_chain = fields.Boolean(
        string='Requires Cold Chain',
        default=True
    )

    # Co-product Configuration
    has_co_products = fields.Boolean(
        string='Has Co-Products',
        default=False
    )
    co_product_ids = fields.One2many(
        'mrp.bom.dairy.coproduct',
        'bom_id',
        string='Co-Products'
    )

    # Quality Integration
    quality_check_required = fields.Boolean(
        string='Quality Check Required',
        default=True
    )
    quality_check_point_ids = fields.Many2many(
        'quality.point',
        string='Quality Check Points'
    )

    # Processing Time
    pasteurization_time_minutes = fields.Integer(
        string='Pasteurization Time (min)'
    )
    fermentation_time_hours = fields.Float(
        string='Fermentation Time (hours)'
    )
    aging_time_days = fields.Integer(
        string='Aging Time (days)'
    )

    @api.constrains('expected_yield_percentage')
    def _check_yield_percentage(self):
        for bom in self:
            if bom.expected_yield_percentage <= 0 or bom.expected_yield_percentage > 100:
                raise ValidationError(
                    _("Expected yield percentage must be between 0 and 100")
                )

    def compute_yield_from_production(self, production_id):
        """Calculate actual yield from a production order"""
        production = self.env['mrp.production'].browse(production_id)
        if not production:
            return 0.0

        # Get raw material input quantity
        raw_material_qty = sum(
            move.quantity_done
            for move in production.move_raw_ids
            if move.product_id.is_raw_milk
        )

        # Get finished product output quantity
        finished_qty = production.qty_produced

        if raw_material_qty > 0:
            return (finished_qty / raw_material_qty) * 100
        return 0.0


class MrpBomDairyCoproduct(models.Model):
    _name = 'mrp.bom.dairy.coproduct'
    _description = 'Dairy BOM Co-Product'

    bom_id = fields.Many2one(
        'mrp.bom',
        string='Bill of Materials',
        required=True,
        ondelete='cascade'
    )
    product_id = fields.Many2one(
        'product.product',
        string='Co-Product',
        required=True,
        domain=[('type', '=', 'product')]
    )
    product_qty = fields.Float(
        string='Quantity',
        digits='Product Unit of Measure',
        required=True,
        default=1.0
    )
    product_uom_id = fields.Many2one(
        'uom.uom',
        string='Unit of Measure',
        required=True
    )
    yield_percentage = fields.Float(
        string='Yield %',
        digits=(5, 2),
        help='Percentage of raw material converted to this co-product'
    )
    is_byproduct = fields.Boolean(
        string='Is By-Product',
        default=False,
        help='By-products have zero or negative cost allocation'
    )
    cost_share_percentage = fields.Float(
        string='Cost Share %',
        digits=(5, 2),
        default=0.0,
        help='Percentage of production cost allocated to this co-product'
    )
```

**Task 101.1.3: Work Center Configuration (3h)**

```python
# smart_dairy_addons/smart_mrp_dairy/models/mrp_workcenter_dairy.py
from odoo import models, fields, api

class MrpWorkcenterDairy(models.Model):
    _inherit = 'mrp.workcenter'

    # Dairy-specific work center attributes
    workcenter_type = fields.Selection([
        ('reception', 'Milk Reception'),
        ('testing', 'Quality Testing Lab'),
        ('pasteurization', 'Pasteurization'),
        ('homogenization', 'Homogenization'),
        ('separation', 'Cream Separation'),
        ('fermentation', 'Fermentation'),
        ('packaging', 'Packaging'),
        ('cold_storage', 'Cold Storage'),
        ('general', 'General Processing'),
    ], string='Work Center Type', default='general')

    # Temperature Control
    has_temperature_control = fields.Boolean(
        string='Has Temperature Control',
        default=True
    )
    operating_temp_min = fields.Float(
        string='Operating Temp Min (°C)',
        digits=(4, 1)
    )
    operating_temp_max = fields.Float(
        string='Operating Temp Max (°C)',
        digits=(4, 1)
    )

    # Capacity
    capacity_liters_per_hour = fields.Float(
        string='Capacity (Liters/Hour)',
        digits=(10, 2)
    )
    capacity_kg_per_hour = fields.Float(
        string='Capacity (Kg/Hour)',
        digits=(10, 2)
    )

    # CIP (Clean-in-Place)
    requires_cip = fields.Boolean(
        string='Requires CIP',
        default=True,
        help='Clean-in-Place required between batches'
    )
    cip_time_minutes = fields.Integer(
        string='CIP Time (minutes)',
        default=30
    )

    # Equipment Details
    equipment_id = fields.Many2one(
        'farm.equipment',
        string='Linked Equipment',
        help='Link to farm equipment for maintenance tracking'
    )
    last_maintenance_date = fields.Date(
        string='Last Maintenance'
    )
    next_maintenance_date = fields.Date(
        string='Next Maintenance'
    )

    # Shift Configuration
    shifts_per_day = fields.Integer(
        string='Shifts per Day',
        default=2
    )
    hours_per_shift = fields.Float(
        string='Hours per Shift',
        default=8.0
    )

    def get_available_capacity(self, date_from, date_to):
        """Calculate available capacity for a date range"""
        # Implementation for capacity planning
        total_hours = self.shifts_per_day * self.hours_per_shift
        working_days = (date_to - date_from).days + 1
        return total_hours * working_days * self.capacity_liters_per_hour
```

```xml
<!-- smart_dairy_addons/smart_mrp_dairy/data/work_center_data.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo noupdate="1">
    <!-- Milk Reception Work Center -->
    <record id="workcenter_milk_reception" model="mrp.workcenter">
        <field name="name">Milk Reception Station</field>
        <field name="code">WC-RECV</field>
        <field name="workcenter_type">reception</field>
        <field name="capacity_liters_per_hour">500</field>
        <field name="has_temperature_control">True</field>
        <field name="operating_temp_min">2</field>
        <field name="operating_temp_max">6</field>
        <field name="requires_cip">True</field>
        <field name="cip_time_minutes">20</field>
        <field name="time_efficiency">95</field>
        <field name="oee_target">85</field>
        <field name="costs_hour">150</field>
    </record>

    <!-- Quality Testing Lab -->
    <record id="workcenter_quality_lab" model="mrp.workcenter">
        <field name="name">Quality Testing Laboratory</field>
        <field name="code">WC-QLAB</field>
        <field name="workcenter_type">testing</field>
        <field name="capacity_liters_per_hour">100</field>
        <field name="has_temperature_control">True</field>
        <field name="operating_temp_min">20</field>
        <field name="operating_temp_max">25</field>
        <field name="requires_cip">False</field>
        <field name="time_efficiency">90</field>
        <field name="costs_hour">200</field>
    </record>

    <!-- Pasteurization Unit -->
    <record id="workcenter_pasteurization" model="mrp.workcenter">
        <field name="name">HTST Pasteurization Unit</field>
        <field name="code">WC-PAST</field>
        <field name="workcenter_type">pasteurization</field>
        <field name="capacity_liters_per_hour">300</field>
        <field name="has_temperature_control">True</field>
        <field name="operating_temp_min">72</field>
        <field name="operating_temp_max">75</field>
        <field name="requires_cip">True</field>
        <field name="cip_time_minutes">45</field>
        <field name="time_efficiency">92</field>
        <field name="oee_target">88</field>
        <field name="costs_hour">250</field>
    </record>

    <!-- Homogenizer -->
    <record id="workcenter_homogenization" model="mrp.workcenter">
        <field name="name">Homogenizer</field>
        <field name="code">WC-HOMO</field>
        <field name="workcenter_type">homogenization</field>
        <field name="capacity_liters_per_hour">250</field>
        <field name="has_temperature_control">True</field>
        <field name="operating_temp_min">55</field>
        <field name="operating_temp_max">65</field>
        <field name="requires_cip">True</field>
        <field name="cip_time_minutes">30</field>
        <field name="time_efficiency">94</field>
        <field name="costs_hour">180</field>
    </record>

    <!-- Cream Separator -->
    <record id="workcenter_separator" model="mrp.workcenter">
        <field name="name">Cream Separator</field>
        <field name="code">WC-SEP</field>
        <field name="workcenter_type">separation</field>
        <field name="capacity_liters_per_hour">200</field>
        <field name="has_temperature_control">True</field>
        <field name="operating_temp_min">40</field>
        <field name="operating_temp_max">50</field>
        <field name="requires_cip">True</field>
        <field name="cip_time_minutes">25</field>
        <field name="time_efficiency">90</field>
        <field name="costs_hour">150</field>
    </record>

    <!-- Fermentation Tank -->
    <record id="workcenter_fermentation" model="mrp.workcenter">
        <field name="name">Fermentation Tank</field>
        <field name="code">WC-FERM</field>
        <field name="workcenter_type">fermentation</field>
        <field name="capacity_liters_per_hour">50</field>
        <field name="capacity_kg_per_hour">50</field>
        <field name="has_temperature_control">True</field>
        <field name="operating_temp_min">42</field>
        <field name="operating_temp_max">45</field>
        <field name="requires_cip">True</field>
        <field name="cip_time_minutes">40</field>
        <field name="time_efficiency">95</field>
        <field name="costs_hour">120</field>
    </record>

    <!-- Packaging Line -->
    <record id="workcenter_packaging" model="mrp.workcenter">
        <field name="name">Packaging Line</field>
        <field name="code">WC-PACK</field>
        <field name="workcenter_type">packaging</field>
        <field name="capacity_liters_per_hour">400</field>
        <field name="capacity_kg_per_hour">100</field>
        <field name="has_temperature_control">True</field>
        <field name="operating_temp_min">4</field>
        <field name="operating_temp_max">10</field>
        <field name="requires_cip">True</field>
        <field name="cip_time_minutes">15</field>
        <field name="time_efficiency">88</field>
        <field name="oee_target">80</field>
        <field name="costs_hour">200</field>
    </record>
</odoo>
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Task 101.2.1: Production Order Extension Model (4h)**

```python
# smart_dairy_addons/smart_mrp_dairy/models/mrp_production_dairy.py
from odoo import models, fields, api, _
from odoo.exceptions import UserError
from datetime import timedelta

class MrpProductionDairy(models.Model):
    _inherit = 'mrp.production'

    # Dairy Production Fields
    is_dairy_production = fields.Boolean(
        related='bom_id.is_dairy_product',
        store=True
    )
    dairy_product_type = fields.Selection(
        related='bom_id.dairy_product_type',
        store=True
    )

    # Raw Milk Source
    milk_source_ids = fields.Many2many(
        'farm.milk.production',
        string='Milk Source Records',
        help='Link to farm milk production records used in this batch'
    )
    total_raw_milk_liters = fields.Float(
        string='Total Raw Milk (L)',
        compute='_compute_raw_milk_total',
        store=True
    )

    # Yield Tracking
    expected_yield = fields.Float(
        string='Expected Yield (L/Kg)',
        compute='_compute_expected_yield',
        store=True
    )
    actual_yield = fields.Float(
        string='Actual Yield (L/Kg)',
        compute='_compute_actual_yield',
        store=True
    )
    yield_variance = fields.Float(
        string='Yield Variance %',
        compute='_compute_yield_variance',
        store=True
    )
    yield_status = fields.Selection([
        ('pending', 'Pending'),
        ('normal', 'Normal'),
        ('low', 'Below Target'),
        ('high', 'Above Target'),
    ], string='Yield Status', compute='_compute_yield_variance', store=True)

    # Quality Integration
    quality_status = fields.Selection([
        ('pending', 'Pending Check'),
        ('passed', 'Passed'),
        ('failed', 'Failed'),
        ('conditional', 'Conditional Pass'),
    ], string='Quality Status', default='pending')
    quality_check_ids = fields.One2many(
        'quality.check',
        'production_id',
        string='Quality Checks'
    )

    # Shelf Life
    production_date = fields.Datetime(
        string='Production Date',
        default=fields.Datetime.now
    )
    expiry_date = fields.Datetime(
        string='Expiry Date',
        compute='_compute_expiry_date',
        store=True
    )
    shelf_life_days = fields.Integer(
        related='bom_id.shelf_life_days'
    )

    # Batch Information
    batch_number = fields.Char(
        string='Batch Number',
        copy=False,
        default=lambda self: _('New')
    )
    lot_id = fields.Many2one(
        'stock.lot',
        string='Production Lot',
        copy=False
    )

    # Co-products Generated
    coproduct_move_ids = fields.One2many(
        'stock.move',
        'production_coproduct_id',
        string='Co-Product Moves'
    )

    # Temperature Logs
    temperature_log_ids = fields.One2many(
        'mrp.production.temp.log',
        'production_id',
        string='Temperature Logs'
    )

    # Production Metrics
    processing_start_time = fields.Datetime(string='Processing Start')
    processing_end_time = fields.Datetime(string='Processing End')
    total_processing_time = fields.Float(
        string='Total Processing Time (hours)',
        compute='_compute_processing_time'
    )

    @api.depends('move_raw_ids.quantity_done')
    def _compute_raw_milk_total(self):
        for production in self:
            milk_moves = production.move_raw_ids.filtered(
                lambda m: m.product_id.is_raw_milk
            )
            production.total_raw_milk_liters = sum(milk_moves.mapped('quantity_done'))

    @api.depends('bom_id.expected_yield_percentage', 'total_raw_milk_liters')
    def _compute_expected_yield(self):
        for production in self:
            if production.bom_id and production.total_raw_milk_liters:
                yield_pct = production.bom_id.expected_yield_percentage or 100
                production.expected_yield = production.total_raw_milk_liters * (yield_pct / 100)
            else:
                production.expected_yield = 0.0

    @api.depends('qty_produced')
    def _compute_actual_yield(self):
        for production in self:
            production.actual_yield = production.qty_produced

    @api.depends('expected_yield', 'actual_yield', 'bom_id.yield_variance_tolerance')
    def _compute_yield_variance(self):
        for production in self:
            if production.expected_yield > 0:
                variance = ((production.actual_yield - production.expected_yield)
                           / production.expected_yield) * 100
                production.yield_variance = variance

                tolerance = production.bom_id.yield_variance_tolerance or 2.0
                if abs(variance) <= tolerance:
                    production.yield_status = 'normal'
                elif variance < -tolerance:
                    production.yield_status = 'low'
                else:
                    production.yield_status = 'high'
            else:
                production.yield_variance = 0.0
                production.yield_status = 'pending'

    @api.depends('production_date', 'shelf_life_days')
    def _compute_expiry_date(self):
        for production in self:
            if production.production_date and production.shelf_life_days:
                production.expiry_date = production.production_date + timedelta(
                    days=production.shelf_life_days
                )
            else:
                production.expiry_date = False

    @api.depends('processing_start_time', 'processing_end_time')
    def _compute_processing_time(self):
        for production in self:
            if production.processing_start_time and production.processing_end_time:
                delta = production.processing_end_time - production.processing_start_time
                production.total_processing_time = delta.total_seconds() / 3600
            else:
                production.total_processing_time = 0.0

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            if vals.get('batch_number', _('New')) == _('New'):
                vals['batch_number'] = self.env['ir.sequence'].next_by_code(
                    'mrp.production.dairy.batch'
                ) or _('New')
        return super().create(vals_list)

    def action_start_production(self):
        """Start dairy production with timestamp"""
        self.ensure_one()
        self.processing_start_time = fields.Datetime.now()
        return super().action_start_production() if hasattr(super(), 'action_start_production') else True

    def action_complete_production(self):
        """Complete production and generate co-products"""
        self.ensure_one()
        self.processing_end_time = fields.Datetime.now()

        # Generate co-products if configured
        if self.bom_id.has_co_products:
            self._generate_coproducts()

        # Create lot with expiry date
        if not self.lot_id:
            self._create_production_lot()

        return True

    def _generate_coproducts(self):
        """Generate co-product stock moves"""
        for coproduct in self.bom_id.co_product_ids:
            qty = (self.total_raw_milk_liters * coproduct.yield_percentage / 100)

            move_vals = {
                'name': f'Co-product: {coproduct.product_id.name}',
                'product_id': coproduct.product_id.id,
                'product_uom_qty': qty,
                'product_uom': coproduct.product_uom_id.id,
                'location_id': self.location_src_id.id,
                'location_dest_id': self.location_dest_id.id,
                'production_coproduct_id': self.id,
                'origin': self.name,
            }
            self.env['stock.move'].create(move_vals)

    def _create_production_lot(self):
        """Create stock lot for finished product with expiry date"""
        lot_vals = {
            'name': self.batch_number,
            'product_id': self.product_id.id,
            'company_id': self.company_id.id,
            'expiration_date': self.expiry_date,
        }
        self.lot_id = self.env['stock.lot'].create(lot_vals)


class MrpProductionTempLog(models.Model):
    _name = 'mrp.production.temp.log'
    _description = 'Production Temperature Log'
    _order = 'timestamp desc'

    production_id = fields.Many2one(
        'mrp.production',
        string='Production Order',
        required=True,
        ondelete='cascade'
    )
    workcenter_id = fields.Many2one(
        'mrp.workcenter',
        string='Work Center'
    )
    timestamp = fields.Datetime(
        string='Timestamp',
        default=fields.Datetime.now,
        required=True
    )
    temperature = fields.Float(
        string='Temperature (°C)',
        digits=(5, 2),
        required=True
    )
    is_within_range = fields.Boolean(
        string='Within Range',
        compute='_compute_within_range',
        store=True
    )
    recorded_by = fields.Many2one(
        'res.users',
        string='Recorded By',
        default=lambda self: self.env.user
    )
    notes = fields.Text(string='Notes')

    @api.depends('temperature', 'workcenter_id')
    def _compute_within_range(self):
        for log in self:
            if log.workcenter_id:
                wc = log.workcenter_id
                log.is_within_range = (
                    wc.operating_temp_min <= log.temperature <= wc.operating_temp_max
                )
            else:
                log.is_within_range = True
```

**Task 101.2.2: Security and Access Rights (2h)**

```csv
# smart_dairy_addons/smart_mrp_dairy/security/ir.model.access.csv
id,name,model_id:id,group_id:id,perm_read,perm_write,perm_create,perm_unlink
access_bom_coproduct_user,mrp.bom.dairy.coproduct.user,model_mrp_bom_dairy_coproduct,mrp.group_mrp_user,1,0,0,0
access_bom_coproduct_manager,mrp.bom.dairy.coproduct.manager,model_mrp_bom_dairy_coproduct,mrp.group_mrp_manager,1,1,1,1
access_production_temp_log_user,mrp.production.temp.log.user,model_mrp_production_temp_log,mrp.group_mrp_user,1,1,1,0
access_production_temp_log_manager,mrp.production.temp.log.manager,model_mrp_production_temp_log,mrp.group_mrp_manager,1,1,1,1
```

**Task 101.2.3: CI/CD Pipeline Update (2h)**

```yaml
# .github/workflows/test-mrp-dairy.yml
name: MRP Dairy Module Tests
on:
  push:
    paths:
      - 'smart_dairy_addons/smart_mrp_dairy/**'
  pull_request:
    paths:
      - 'smart_dairy_addons/smart_mrp_dairy/**'

jobs:
  test:
    runs-on: ubuntu-latest
    services:
      postgres:
        image: postgres:16
        env:
          POSTGRES_USER: odoo
          POSTGRES_PASSWORD: odoo_test
          POSTGRES_DB: odoo_test
        ports: ['5432:5432']
        options: >-
          --health-cmd pg_isready
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5
    steps:
      - uses: actions/checkout@v4
      - name: Set up Python 3.11
        uses: actions/setup-python@v5
        with:
          python-version: '3.11'
      - name: Install dependencies
        run: |
          pip install -r requirements.txt
          pip install pytest pytest-cov pytest-odoo
      - name: Run smart_mrp_dairy tests
        run: |
          python odoo-bin --test-enable \
            --stop-after-init \
            --addons-path=addons,smart_dairy_addons \
            -d odoo_test \
            -i smart_mrp_dairy \
            --log-level=test
        env:
          PGHOST: localhost
          PGPORT: 5432
          PGUSER: odoo
          PGPASSWORD: odoo_test
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Task 101.3.1: Work Center Views (4h)**

```xml
<!-- smart_dairy_addons/smart_mrp_dairy/views/mrp_workcenter_dairy_views.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Work Center Form View Extension -->
    <record id="mrp_workcenter_dairy_form" model="ir.ui.view">
        <field name="name">mrp.workcenter.dairy.form</field>
        <field name="model">mrp.workcenter</field>
        <field name="inherit_id" ref="mrp.mrp_workcenter_view_form"/>
        <field name="arch" type="xml">
            <xpath expr="//page[@name='general']" position="after">
                <page string="Dairy Configuration" name="dairy_config">
                    <group>
                        <group string="Work Center Type">
                            <field name="workcenter_type"/>
                        </group>
                        <group string="Temperature Control">
                            <field name="has_temperature_control"/>
                            <field name="operating_temp_min"
                                   invisible="not has_temperature_control"/>
                            <field name="operating_temp_max"
                                   invisible="not has_temperature_control"/>
                        </group>
                    </group>
                    <group>
                        <group string="Capacity">
                            <field name="capacity_liters_per_hour"/>
                            <field name="capacity_kg_per_hour"/>
                        </group>
                        <group string="CIP Configuration">
                            <field name="requires_cip"/>
                            <field name="cip_time_minutes"
                                   invisible="not requires_cip"/>
                        </group>
                    </group>
                    <group>
                        <group string="Equipment Link">
                            <field name="equipment_id"/>
                            <field name="last_maintenance_date"/>
                            <field name="next_maintenance_date"/>
                        </group>
                        <group string="Shift Configuration">
                            <field name="shifts_per_day"/>
                            <field name="hours_per_shift"/>
                        </group>
                    </group>
                </page>
            </xpath>
        </field>
    </record>

    <!-- Work Center Tree View -->
    <record id="mrp_workcenter_dairy_tree" model="ir.ui.view">
        <field name="name">mrp.workcenter.dairy.tree</field>
        <field name="model">mrp.workcenter</field>
        <field name="inherit_id" ref="mrp.mrp_workcenter_tree_view"/>
        <field name="arch" type="xml">
            <xpath expr="//field[@name='name']" position="after">
                <field name="workcenter_type"/>
                <field name="capacity_liters_per_hour"/>
            </xpath>
        </field>
    </record>

    <!-- Work Center Kanban View -->
    <record id="mrp_workcenter_dairy_kanban" model="ir.ui.view">
        <field name="name">mrp.workcenter.dairy.kanban</field>
        <field name="model">mrp.workcenter</field>
        <field name="arch" type="xml">
            <kanban class="o_kanban_mobile">
                <field name="name"/>
                <field name="code"/>
                <field name="workcenter_type"/>
                <field name="capacity_liters_per_hour"/>
                <field name="working_state"/>
                <field name="color"/>
                <templates>
                    <t t-name="kanban-box">
                        <div t-attf-class="oe_kanban_card oe_kanban_global_click"
                             t-att-style="'border-left: 4px solid ' + (record.working_state.raw_value == 'normal' ? '#28a745' : record.working_state.raw_value == 'blocked' ? '#dc3545' : '#ffc107')">
                            <div class="oe_kanban_content">
                                <div class="o_kanban_record_top">
                                    <div class="o_kanban_record_headings">
                                        <strong class="o_kanban_record_title">
                                            <field name="name"/>
                                        </strong>
                                    </div>
                                    <span class="badge" t-att-class="record.working_state.raw_value == 'normal' ? 'bg-success' : record.working_state.raw_value == 'blocked' ? 'bg-danger' : 'bg-warning'">
                                        <field name="working_state"/>
                                    </span>
                                </div>
                                <div class="o_kanban_record_body">
                                    <field name="workcenter_type"/>
                                    <br/>
                                    <span t-if="record.capacity_liters_per_hour.raw_value">
                                        <i class="fa fa-tint"/>
                                        <field name="capacity_liters_per_hour"/> L/hr
                                    </span>
                                </div>
                            </div>
                        </div>
                    </t>
                </templates>
            </kanban>
        </field>
    </record>
</odoo>
```

**Task 101.3.2: Dashboard Wireframes and SCSS (4h)**

```scss
// smart_dairy_addons/smart_mrp_dairy/static/src/scss/production_dashboard.scss
.o_dairy_production_dashboard {
    padding: 16px;
    background: #f8f9fa;

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;

        h2 {
            margin: 0;
            color: #495057;
            font-weight: 600;
        }

        .date-selector {
            display: flex;
            gap: 8px;
        }
    }

    .kpi-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;

        .kpi-card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid;

            &.production { border-color: #007bff; }
            &.yield { border-color: #28a745; }
            &.quality { border-color: #ffc107; }
            &.efficiency { border-color: #17a2b8; }

            .kpi-title {
                font-size: 0.85rem;
                color: #6c757d;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 8px;
            }

            .kpi-value {
                font-size: 2rem;
                font-weight: 700;
                color: #212529;
                line-height: 1.2;
            }

            .kpi-change {
                font-size: 0.85rem;
                margin-top: 8px;

                &.positive { color: #28a745; }
                &.negative { color: #dc3545; }

                i { margin-right: 4px; }
            }
        }
    }

    .production-chart {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;

        h4 {
            margin-bottom: 16px;
            color: #495057;
        }
    }

    .work-center-status {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;

        .wc-card {
            background: #fff;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);

            .wc-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 12px;

                .wc-name {
                    font-weight: 600;
                    color: #212529;
                }

                .wc-status {
                    padding: 4px 8px;
                    border-radius: 4px;
                    font-size: 0.75rem;

                    &.running { background: #d4edda; color: #155724; }
                    &.idle { background: #fff3cd; color: #856404; }
                    &.maintenance { background: #f8d7da; color: #721c24; }
                }
            }

            .wc-metrics {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;

                .metric {
                    .metric-label {
                        font-size: 0.75rem;
                        color: #6c757d;
                    }
                    .metric-value {
                        font-weight: 600;
                        color: #212529;
                    }
                }
            }

            .wc-progress {
                margin-top: 12px;

                .progress {
                    height: 8px;
                    border-radius: 4px;
                    background: #e9ecef;

                    .progress-bar {
                        border-radius: 4px;
                        background: linear-gradient(90deg, #007bff, #00d4ff);
                    }
                }

                .progress-label {
                    display: flex;
                    justify-content: space-between;
                    font-size: 0.75rem;
                    color: #6c757d;
                    margin-top: 4px;
                }
            }
        }
    }
}
```

**End-of-Day 101 Deliverables:**
- [ ] smart_mrp_dairy module scaffold created
- [ ] BOM extension model with dairy fields implemented
- [ ] Work Center extension model with dairy attributes
- [ ] 7 Work Centers configured (Reception through Packaging)
- [ ] Production Order extension model started
- [ ] CI/CD pipeline updated for MRP module
- [ ] Work Center views (form, tree, kanban) created
- [ ] Dashboard SCSS foundation in place

---

### Day 102 — Fresh Milk BOM and Production Routing

**Objective:** Create Bill of Materials for Fresh Pasteurized Milk with complete production routing through work centers.

#### Dev 1 — Backend Lead (8h)

**Task 102.1.1: Fresh Milk BOM Configuration (4h)**

```xml
<!-- smart_dairy_addons/smart_mrp_dairy/data/bom_fresh_milk.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo noupdate="1">
    <!-- Fresh Pasteurized Milk Product -->
    <record id="product_fresh_milk_1l" model="product.product">
        <field name="name">Fresh Pasteurized Milk 1L</field>
        <field name="default_code">MILK-FRESH-1L</field>
        <field name="type">product</field>
        <field name="categ_id" ref="product.product_category_1"/>
        <field name="uom_id" ref="uom.product_uom_litre"/>
        <field name="uom_po_id" ref="uom.product_uom_litre"/>
        <field name="tracking">lot</field>
        <field name="use_expiration_date">True</field>
        <field name="expiration_time">5</field>
        <field name="list_price">80.00</field>
        <field name="standard_price">45.00</field>
    </record>

    <!-- Raw Milk Product -->
    <record id="product_raw_milk" model="product.product">
        <field name="name">Raw Milk</field>
        <field name="default_code">MILK-RAW</field>
        <field name="type">product</field>
        <field name="uom_id" ref="uom.product_uom_litre"/>
        <field name="uom_po_id" ref="uom.product_uom_litre"/>
        <field name="tracking">lot</field>
        <field name="is_raw_milk">True</field>
        <field name="standard_price">50.00</field>
    </record>

    <!-- Fresh Milk BOM -->
    <record id="bom_fresh_milk_1l" model="mrp.bom">
        <field name="product_tmpl_id" ref="product_fresh_milk_1l_tmpl"/>
        <field name="product_id" ref="product_fresh_milk_1l"/>
        <field name="product_qty">1</field>
        <field name="product_uom_id" ref="uom.product_uom_litre"/>
        <field name="type">normal</field>
        <field name="is_dairy_product">True</field>
        <field name="dairy_product_type">fresh_milk</field>
        <field name="expected_yield_percentage">98.0</field>
        <field name="yield_variance_tolerance">1.5</field>
        <field name="shelf_life_days">5</field>
        <field name="min_remaining_shelf_life">3</field>
        <field name="storage_temp_min">2.0</field>
        <field name="storage_temp_max">6.0</field>
        <field name="requires_cold_chain">True</field>
        <field name="quality_check_required">True</field>
        <field name="pasteurization_time_minutes">15</field>
    </record>

    <!-- BOM Line: Raw Milk -->
    <record id="bom_line_fresh_milk_raw" model="mrp.bom.line">
        <field name="bom_id" ref="bom_fresh_milk_1l"/>
        <field name="product_id" ref="product_raw_milk"/>
        <field name="product_qty">1.02</field>
        <field name="product_uom_id" ref="uom.product_uom_litre"/>
    </record>

    <!-- Routing Operations -->
    <record id="routing_fresh_milk" model="mrp.routing">
        <field name="name">Fresh Milk Production Routing</field>
    </record>

    <!-- Operation 1: Reception and Testing -->
    <record id="operation_fresh_milk_reception" model="mrp.routing.workcenter">
        <field name="routing_id" ref="routing_fresh_milk"/>
        <field name="workcenter_id" ref="workcenter_milk_reception"/>
        <field name="name">Milk Reception</field>
        <field name="sequence">10</field>
        <field name="time_mode">manual</field>
        <field name="time_cycle_manual">5</field>
        <field name="bom_id" ref="bom_fresh_milk_1l"/>
    </record>

    <!-- Operation 2: Quality Testing -->
    <record id="operation_fresh_milk_testing" model="mrp.routing.workcenter">
        <field name="routing_id" ref="routing_fresh_milk"/>
        <field name="workcenter_id" ref="workcenter_quality_lab"/>
        <field name="name">Quality Testing</field>
        <field name="sequence">20</field>
        <field name="time_mode">manual</field>
        <field name="time_cycle_manual">10</field>
        <field name="bom_id" ref="bom_fresh_milk_1l"/>
    </record>

    <!-- Operation 3: Pasteurization -->
    <record id="operation_fresh_milk_pasteurization" model="mrp.routing.workcenter">
        <field name="routing_id" ref="routing_fresh_milk"/>
        <field name="workcenter_id" ref="workcenter_pasteurization"/>
        <field name="name">HTST Pasteurization (72°C/15s)</field>
        <field name="sequence">30</field>
        <field name="time_mode">manual</field>
        <field name="time_cycle_manual">15</field>
        <field name="bom_id" ref="bom_fresh_milk_1l"/>
    </record>

    <!-- Operation 4: Homogenization -->
    <record id="operation_fresh_milk_homogenization" model="mrp.routing.workcenter">
        <field name="routing_id" ref="routing_fresh_milk"/>
        <field name="workcenter_id" ref="workcenter_homogenization"/>
        <field name="name">Homogenization</field>
        <field name="sequence">40</field>
        <field name="time_mode">manual</field>
        <field name="time_cycle_manual">10</field>
        <field name="bom_id" ref="bom_fresh_milk_1l"/>
    </record>

    <!-- Operation 5: Packaging -->
    <record id="operation_fresh_milk_packaging" model="mrp.routing.workcenter">
        <field name="routing_id" ref="routing_fresh_milk"/>
        <field name="workcenter_id" ref="workcenter_packaging"/>
        <field name="name">Filling and Packaging</field>
        <field name="sequence">50</field>
        <field name="time_mode">manual</field>
        <field name="time_cycle_manual">8</field>
        <field name="bom_id" ref="bom_fresh_milk_1l"/>
    </record>
</odoo>
```

**Task 102.1.2: BOM Views Extension (2h)**

```xml
<!-- smart_dairy_addons/smart_mrp_dairy/views/mrp_bom_dairy_views.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- BOM Form View Extension -->
    <record id="mrp_bom_dairy_form" model="ir.ui.view">
        <field name="name">mrp.bom.dairy.form</field>
        <field name="model">mrp.bom</field>
        <field name="inherit_id" ref="mrp.mrp_bom_form_view"/>
        <field name="arch" type="xml">
            <xpath expr="//sheet" position="before">
                <div class="alert alert-info" role="alert"
                     invisible="not is_dairy_product">
                    <i class="fa fa-info-circle"/>
                    This is a dairy product BOM with specialized yield tracking and shelf-life management.
                </div>
            </xpath>

            <xpath expr="//group[@name='main']" position="after">
                <group string="Dairy Configuration"
                       invisible="not is_dairy_product">
                    <group>
                        <field name="is_dairy_product"/>
                        <field name="dairy_product_type"
                               invisible="not is_dairy_product"/>
                    </group>
                </group>
            </xpath>

            <xpath expr="//notebook" position="inside">
                <page string="Dairy Settings" name="dairy_settings"
                      invisible="not is_dairy_product">
                    <group>
                        <group string="Yield Management">
                            <field name="expected_yield_percentage"/>
                            <field name="yield_variance_tolerance"/>
                        </group>
                        <group string="Shelf Life">
                            <field name="shelf_life_days"/>
                            <field name="min_remaining_shelf_life"/>
                        </group>
                    </group>
                    <group>
                        <group string="Storage Requirements">
                            <field name="storage_temp_min"/>
                            <field name="storage_temp_max"/>
                            <field name="requires_cold_chain"/>
                        </group>
                        <group string="Processing Times">
                            <field name="pasteurization_time_minutes"/>
                            <field name="fermentation_time_hours"/>
                            <field name="aging_time_days"/>
                        </group>
                    </group>
                    <group string="Quality Integration">
                        <field name="quality_check_required"/>
                        <field name="quality_check_point_ids" widget="many2many_tags"/>
                    </group>
                </page>

                <page string="Co-Products" name="co_products"
                      invisible="not has_co_products">
                    <field name="has_co_products"/>
                    <field name="co_product_ids"
                           invisible="not has_co_products">
                        <tree editable="bottom">
                            <field name="product_id"/>
                            <field name="product_qty"/>
                            <field name="product_uom_id"/>
                            <field name="yield_percentage"/>
                            <field name="is_byproduct"/>
                            <field name="cost_share_percentage"/>
                        </tree>
                    </field>
                </page>
            </xpath>
        </field>
    </record>

    <!-- BOM Tree View Extension -->
    <record id="mrp_bom_dairy_tree" model="ir.ui.view">
        <field name="name">mrp.bom.dairy.tree</field>
        <field name="model">mrp.bom</field>
        <field name="inherit_id" ref="mrp.mrp_bom_tree_view"/>
        <field name="arch" type="xml">
            <xpath expr="//field[@name='product_tmpl_id']" position="after">
                <field name="is_dairy_product" column_invisible="True"/>
                <field name="dairy_product_type" optional="show"/>
                <field name="expected_yield_percentage" optional="show"/>
                <field name="shelf_life_days" optional="show"/>
            </xpath>
        </field>
    </record>

    <!-- Dairy BOM Search View -->
    <record id="mrp_bom_dairy_search" model="ir.ui.view">
        <field name="name">mrp.bom.dairy.search</field>
        <field name="model">mrp.bom</field>
        <field name="inherit_id" ref="mrp.mrp_bom_search"/>
        <field name="arch" type="xml">
            <xpath expr="//filter[@name='active']" position="after">
                <separator/>
                <filter name="dairy_products" string="Dairy Products"
                        domain="[('is_dairy_product', '=', True)]"/>
                <filter name="fresh_milk" string="Fresh Milk"
                        domain="[('dairy_product_type', '=', 'fresh_milk')]"/>
                <filter name="uht_milk" string="UHT Milk"
                        domain="[('dairy_product_type', '=', 'uht_milk')]"/>
                <filter name="yogurt" string="Yogurt"
                        domain="[('dairy_product_type', '=', 'yogurt')]"/>
                <filter name="cheese" string="Cheese"
                        domain="[('dairy_product_type', '=', 'cheese')]"/>
                <filter name="butter" string="Butter"
                        domain="[('dairy_product_type', '=', 'butter')]"/>
                <filter name="ghee" string="Ghee"
                        domain="[('dairy_product_type', '=', 'ghee')]"/>
            </xpath>
            <xpath expr="//group" position="inside">
                <filter name="group_dairy_type" string="Dairy Type"
                        context="{'group_by': 'dairy_product_type'}"/>
            </xpath>
        </field>
    </record>
</odoo>
```

**Task 102.1.3: Product Extension for Raw Materials (2h)**

```python
# smart_dairy_addons/smart_mrp_dairy/models/product_dairy.py
from odoo import models, fields, api

class ProductProduct(models.Model):
    _inherit = 'product.product'

    # Dairy product attributes
    is_raw_milk = fields.Boolean(
        string='Is Raw Milk',
        default=False,
        help='Mark if this product is raw milk for dairy processing'
    )
    is_dairy_product = fields.Boolean(
        string='Is Dairy Product',
        default=False
    )
    dairy_category = fields.Selection([
        ('raw_material', 'Raw Material'),
        ('intermediate', 'Intermediate Product'),
        ('finished', 'Finished Product'),
        ('byproduct', 'By-Product'),
        ('packaging', 'Packaging Material'),
    ], string='Dairy Category')

    # Quality attributes
    fat_percentage = fields.Float(
        string='Fat %',
        digits=(4, 2),
        help='Standard fat percentage for this product'
    )
    snf_percentage = fields.Float(
        string='SNF %',
        digits=(4, 2),
        help='Standard Solid-Not-Fat percentage'
    )
    protein_percentage = fields.Float(
        string='Protein %',
        digits=(4, 2)
    )

    # Shelf life
    shelf_life_days = fields.Integer(
        string='Shelf Life (days)',
        help='Default shelf life in days'
    )
    requires_refrigeration = fields.Boolean(
        string='Requires Refrigeration',
        default=True
    )


class ProductTemplate(models.Model):
    _inherit = 'product.template'

    is_raw_milk = fields.Boolean(
        string='Is Raw Milk',
        compute='_compute_is_raw_milk',
        inverse='_set_is_raw_milk',
        store=True
    )
    is_dairy_product = fields.Boolean(
        string='Is Dairy Product',
        compute='_compute_is_dairy_product',
        inverse='_set_is_dairy_product',
        store=True
    )

    @api.depends('product_variant_ids', 'product_variant_ids.is_raw_milk')
    def _compute_is_raw_milk(self):
        for template in self:
            template.is_raw_milk = any(
                v.is_raw_milk for v in template.product_variant_ids
            )

    def _set_is_raw_milk(self):
        for template in self:
            for variant in template.product_variant_ids:
                variant.is_raw_milk = template.is_raw_milk

    @api.depends('product_variant_ids', 'product_variant_ids.is_dairy_product')
    def _compute_is_dairy_product(self):
        for template in self:
            template.is_dairy_product = any(
                v.is_dairy_product for v in template.product_variant_ids
            )

    def _set_is_dairy_product(self):
        for template in self:
            for variant in template.product_variant_ids:
                variant.is_dairy_product = template.is_dairy_product
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Task 102.2.1: Unit Tests for BOM Module (4h)**

```python
# smart_dairy_addons/smart_mrp_dairy/tests/test_mrp_bom_dairy.py
from odoo.tests import TransactionCase, tagged
from odoo.exceptions import ValidationError

@tagged('post_install', '-at_install')
class TestMrpBomDairy(TransactionCase):

    @classmethod
    def setUpClass(cls):
        super().setUpClass()

        # Create test products
        cls.product_raw_milk = cls.env['product.product'].create({
            'name': 'Test Raw Milk',
            'type': 'product',
            'uom_id': cls.env.ref('uom.product_uom_litre').id,
            'is_raw_milk': True,
        })

        cls.product_fresh_milk = cls.env['product.product'].create({
            'name': 'Test Fresh Milk',
            'type': 'product',
            'uom_id': cls.env.ref('uom.product_uom_litre').id,
            'is_dairy_product': True,
        })

        # Create test BOM
        cls.bom_fresh_milk = cls.env['mrp.bom'].create({
            'product_tmpl_id': cls.product_fresh_milk.product_tmpl_id.id,
            'product_id': cls.product_fresh_milk.id,
            'product_qty': 1,
            'type': 'normal',
            'is_dairy_product': True,
            'dairy_product_type': 'fresh_milk',
            'expected_yield_percentage': 98.0,
            'yield_variance_tolerance': 2.0,
            'shelf_life_days': 5,
        })

        cls.env['mrp.bom.line'].create({
            'bom_id': cls.bom_fresh_milk.id,
            'product_id': cls.product_raw_milk.id,
            'product_qty': 1.02,
        })

    def test_bom_dairy_fields(self):
        """Test dairy-specific BOM fields are set correctly"""
        self.assertTrue(self.bom_fresh_milk.is_dairy_product)
        self.assertEqual(self.bom_fresh_milk.dairy_product_type, 'fresh_milk')
        self.assertEqual(self.bom_fresh_milk.expected_yield_percentage, 98.0)
        self.assertEqual(self.bom_fresh_milk.shelf_life_days, 5)

    def test_yield_percentage_constraint(self):
        """Test yield percentage must be between 0 and 100"""
        with self.assertRaises(ValidationError):
            self.bom_fresh_milk.expected_yield_percentage = 150.0

        with self.assertRaises(ValidationError):
            self.bom_fresh_milk.expected_yield_percentage = -5.0

    def test_bom_line_creation(self):
        """Test BOM line is created with raw milk"""
        self.assertEqual(len(self.bom_fresh_milk.bom_line_ids), 1)
        line = self.bom_fresh_milk.bom_line_ids[0]
        self.assertEqual(line.product_id, self.product_raw_milk)
        self.assertEqual(line.product_qty, 1.02)

    def test_compute_yield_from_production(self):
        """Test yield calculation from production order"""
        # Create production order
        production = self.env['mrp.production'].create({
            'product_id': self.product_fresh_milk.id,
            'product_qty': 100,
            'bom_id': self.bom_fresh_milk.id,
        })

        # Simulate raw material move
        production.move_raw_ids.quantity_done = 102
        production.qty_produced = 98

        yield_pct = self.bom_fresh_milk.compute_yield_from_production(production.id)
        self.assertAlmostEqual(yield_pct, 96.08, places=2)


@tagged('post_install', '-at_install')
class TestMrpProductionDairy(TransactionCase):

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        # Setup similar to above...

    def test_batch_number_generation(self):
        """Test automatic batch number generation"""
        production = self.env['mrp.production'].create({
            'product_id': self.product_fresh_milk.id,
            'product_qty': 100,
            'bom_id': self.bom_fresh_milk.id,
        })

        self.assertNotEqual(production.batch_number, 'New')
        self.assertTrue(production.batch_number.startswith('BATCH'))

    def test_expiry_date_calculation(self):
        """Test expiry date is calculated from production date"""
        production = self.env['mrp.production'].create({
            'product_id': self.product_fresh_milk.id,
            'product_qty': 100,
            'bom_id': self.bom_fresh_milk.id,
        })

        self.assertTrue(production.expiry_date)
        expected_expiry = production.production_date + timedelta(days=5)
        self.assertEqual(production.expiry_date.date(), expected_expiry.date())
```

**Task 102.2.2: Integration Test Setup (2h)**

```python
# smart_dairy_addons/smart_mrp_dairy/tests/test_production_workflow.py
from odoo.tests import TransactionCase, tagged
from datetime import datetime, timedelta

@tagged('post_install', '-at_install')
class TestProductionWorkflow(TransactionCase):
    """Integration tests for complete production workflow"""

    def test_fresh_milk_production_complete_workflow(self):
        """Test complete production workflow for fresh milk"""
        # 1. Create production order
        production = self.env['mrp.production'].create({
            'product_id': self.product_fresh_milk.id,
            'product_qty': 100,
            'bom_id': self.bom_fresh_milk.id,
        })

        # 2. Confirm production
        production.action_confirm()
        self.assertEqual(production.state, 'confirmed')

        # 3. Check availability
        production.action_assign()

        # 4. Start production
        production.action_start_production()
        self.assertTrue(production.processing_start_time)

        # 5. Complete work orders
        for workorder in production.workorder_ids:
            workorder.button_start()
            workorder.button_finish()

        # 6. Complete production
        production.qty_produced = 98
        production.action_complete_production()

        # 7. Verify outcomes
        self.assertTrue(production.processing_end_time)
        self.assertTrue(production.lot_id)
        self.assertEqual(production.lot_id.name, production.batch_number)
        self.assertAlmostEqual(production.yield_variance, -2.04, places=2)
```

**Task 102.2.3: Sequence Configuration (2h)**

```xml
<!-- smart_dairy_addons/smart_mrp_dairy/data/mrp_dairy_data.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo noupdate="1">
    <!-- Batch Number Sequence -->
    <record id="seq_mrp_production_dairy_batch" model="ir.sequence">
        <field name="name">Dairy Production Batch</field>
        <field name="code">mrp.production.dairy.batch</field>
        <field name="prefix">BATCH-%(year)s%(month)s-</field>
        <field name="padding">4</field>
        <field name="number_increment">1</field>
        <field name="company_id" eval="False"/>
    </record>

    <!-- Quality Check Sequence -->
    <record id="seq_quality_check_dairy" model="ir.sequence">
        <field name="name">Dairy Quality Check</field>
        <field name="code">quality.check.dairy</field>
        <field name="prefix">QC-%(year)s-</field>
        <field name="padding">5</field>
    </record>
</odoo>
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Task 102.3.1: Production Order Form View (4h)**

```xml
<!-- smart_dairy_addons/smart_mrp_dairy/views/mrp_production_dairy_views.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Production Order Form Extension -->
    <record id="mrp_production_dairy_form" model="ir.ui.view">
        <field name="name">mrp.production.dairy.form</field>
        <field name="model">mrp.production</field>
        <field name="inherit_id" ref="mrp.mrp_production_form_view"/>
        <field name="arch" type="xml">
            <!-- Header Status Bar Extension -->
            <xpath expr="//header" position="inside">
                <field name="quality_status" widget="badge"
                       invisible="not is_dairy_production"
                       decoration-success="quality_status == 'passed'"
                       decoration-danger="quality_status == 'failed'"
                       decoration-warning="quality_status == 'pending'"
                       decoration-info="quality_status == 'conditional'"/>
            </xpath>

            <!-- Smart Buttons -->
            <xpath expr="//div[@name='button_box']" position="inside">
                <button name="action_view_quality_checks" type="object"
                        class="oe_stat_button" icon="fa-check-circle"
                        invisible="not is_dairy_production">
                    <field name="quality_check_count" widget="statinfo"
                           string="Quality Checks"/>
                </button>
                <button name="action_view_temp_logs" type="object"
                        class="oe_stat_button" icon="fa-thermometer-half"
                        invisible="not is_dairy_production">
                    <field name="temp_log_count" widget="statinfo"
                           string="Temp Logs"/>
                </button>
            </xpath>

            <!-- Dairy Information Group -->
            <xpath expr="//group[@name='group_main']" position="after">
                <group string="Dairy Production" name="dairy_info"
                       invisible="not is_dairy_production">
                    <group>
                        <field name="is_dairy_production" invisible="1"/>
                        <field name="dairy_product_type" readonly="1"/>
                        <field name="batch_number"/>
                        <field name="lot_id"/>
                    </group>
                    <group>
                        <field name="production_date"/>
                        <field name="expiry_date"/>
                        <field name="shelf_life_days" readonly="1"/>
                    </group>
                </group>
            </xpath>

            <!-- Yield Tracking Page -->
            <xpath expr="//notebook" position="inside">
                <page string="Yield Tracking" name="yield_tracking"
                      invisible="not is_dairy_production">
                    <group>
                        <group string="Raw Material Input">
                            <field name="total_raw_milk_liters"/>
                            <field name="milk_source_ids" widget="many2many_tags"/>
                        </group>
                        <group string="Yield Analysis">
                            <field name="expected_yield"/>
                            <field name="actual_yield"/>
                            <field name="yield_variance"/>
                            <field name="yield_status" widget="badge"
                                   decoration-success="yield_status == 'normal'"
                                   decoration-danger="yield_status == 'low'"
                                   decoration-info="yield_status == 'high'"/>
                        </group>
                    </group>
                    <group string="Processing Metrics">
                        <group>
                            <field name="processing_start_time"/>
                            <field name="processing_end_time"/>
                            <field name="total_processing_time"/>
                        </group>
                    </group>
                </page>

                <page string="Temperature Logs" name="temp_logs"
                      invisible="not is_dairy_production">
                    <field name="temperature_log_ids">
                        <tree editable="bottom">
                            <field name="timestamp"/>
                            <field name="workcenter_id"/>
                            <field name="temperature"/>
                            <field name="is_within_range" widget="boolean_toggle"/>
                            <field name="recorded_by"/>
                            <field name="notes"/>
                        </tree>
                    </field>
                </page>

                <page string="Co-Products" name="co_products"
                      invisible="not has_co_products">
                    <field name="coproduct_move_ids">
                        <tree>
                            <field name="product_id"/>
                            <field name="product_uom_qty"/>
                            <field name="product_uom"/>
                            <field name="state"/>
                        </tree>
                    </field>
                </page>
            </xpath>
        </field>
    </record>

    <!-- Production Order Tree View Extension -->
    <record id="mrp_production_dairy_tree" model="ir.ui.view">
        <field name="name">mrp.production.dairy.tree</field>
        <field name="model">mrp.production</field>
        <field name="inherit_id" ref="mrp.mrp_production_tree_view"/>
        <field name="arch" type="xml">
            <xpath expr="//field[@name='product_id']" position="after">
                <field name="batch_number" optional="show"/>
                <field name="dairy_product_type" optional="show"/>
                <field name="yield_status" widget="badge" optional="show"
                       decoration-success="yield_status == 'normal'"
                       decoration-danger="yield_status == 'low'"
                       decoration-info="yield_status == 'high'"/>
                <field name="quality_status" widget="badge" optional="show"
                       decoration-success="quality_status == 'passed'"
                       decoration-danger="quality_status == 'failed'"
                       decoration-warning="quality_status == 'pending'"/>
                <field name="expiry_date" optional="hide"/>
            </xpath>
        </field>
    </record>

    <!-- Production Order Kanban View -->
    <record id="mrp_production_dairy_kanban" model="ir.ui.view">
        <field name="name">mrp.production.dairy.kanban</field>
        <field name="model">mrp.production</field>
        <field name="arch" type="xml">
            <kanban class="o_kanban_mobile" default_group_by="state">
                <field name="name"/>
                <field name="product_id"/>
                <field name="batch_number"/>
                <field name="dairy_product_type"/>
                <field name="state"/>
                <field name="quality_status"/>
                <field name="expiry_date"/>
                <field name="color"/>
                <progressbar field="state"
                            colors='{"draft": "secondary", "confirmed": "warning", "progress": "primary", "done": "success", "cancel": "danger"}'/>
                <templates>
                    <t t-name="kanban-box">
                        <div t-attf-class="oe_kanban_card oe_kanban_global_click">
                            <div class="oe_kanban_content">
                                <div class="o_kanban_record_top">
                                    <div class="o_kanban_record_headings">
                                        <strong class="o_kanban_record_title">
                                            <field name="name"/>
                                        </strong>
                                        <span class="badge bg-secondary ms-1">
                                            <field name="batch_number"/>
                                        </span>
                                    </div>
                                </div>
                                <div class="o_kanban_record_body">
                                    <field name="product_id"/>
                                    <br/>
                                    <span class="text-muted">
                                        <i class="fa fa-calendar"/> Expiry:
                                        <field name="expiry_date"/>
                                    </span>
                                </div>
                                <div class="o_kanban_record_bottom">
                                    <div class="oe_kanban_bottom_left">
                                        <field name="state" widget="label_selection"
                                               options="{'classes': {'draft': 'secondary', 'confirmed': 'warning', 'progress': 'primary', 'done': 'success'}}"/>
                                    </div>
                                    <div class="oe_kanban_bottom_right">
                                        <field name="quality_status" widget="badge"
                                               decoration-success="quality_status == 'passed'"
                                               decoration-danger="quality_status == 'failed'"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </t>
                </templates>
            </kanban>
        </field>
    </record>
</odoo>
```

**Task 102.3.2: Menu Configuration (2h)**

```xml
<!-- smart_dairy_addons/smart_mrp_dairy/views/mrp_dairy_menu.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Dairy Manufacturing Menu -->
    <menuitem id="menu_dairy_manufacturing_root"
              name="Dairy Manufacturing"
              sequence="15"
              web_icon="smart_mrp_dairy,static/description/icon.png"/>

    <!-- Production Menu -->
    <menuitem id="menu_dairy_production"
              name="Production"
              parent="menu_dairy_manufacturing_root"
              sequence="10"/>

    <menuitem id="menu_dairy_production_orders"
              name="Production Orders"
              parent="menu_dairy_production"
              action="mrp.mrp_production_action"
              sequence="10"/>

    <menuitem id="menu_dairy_work_orders"
              name="Work Orders"
              parent="menu_dairy_production"
              action="mrp.mrp_workorder_todo"
              sequence="20"/>

    <!-- Products Menu -->
    <menuitem id="menu_dairy_products"
              name="Products"
              parent="menu_dairy_manufacturing_root"
              sequence="20"/>

    <menuitem id="menu_dairy_bom"
              name="Bills of Materials"
              parent="menu_dairy_products"
              action="mrp.mrp_bom_form_action"
              sequence="10"/>

    <menuitem id="menu_dairy_products_list"
              name="Products"
              parent="menu_dairy_products"
              action="product.product_template_action"
              sequence="20"/>

    <!-- Configuration Menu -->
    <menuitem id="menu_dairy_config"
              name="Configuration"
              parent="menu_dairy_manufacturing_root"
              sequence="100"/>

    <menuitem id="menu_dairy_workcenters"
              name="Work Centers"
              parent="menu_dairy_config"
              action="mrp.mrp_workcenter_action"
              sequence="10"/>

    <menuitem id="menu_dairy_routings"
              name="Routings"
              parent="menu_dairy_config"
              action="mrp.mrp_routing_action"
              sequence="20"/>
</odoo>
```

**Task 102.3.3: Icon and Static Assets (2h)**

Create module icon and additional static assets for the dashboard.

**End-of-Day 102 Deliverables:**
- [ ] Fresh Milk product created with dairy attributes
- [ ] Raw Milk product marked as raw material
- [ ] Fresh Milk BOM with 98% yield configured
- [ ] 5-step production routing (Reception → Testing → Pasteurization → Homogenization → Packaging)
- [ ] BOM views extended with dairy-specific fields
- [ ] Product extension model for dairy attributes
- [ ] Unit tests for BOM module (5 test cases)
- [ ] Integration test framework setup
- [ ] Sequence configuration for batch numbers
- [ ] Production order views (form, tree, kanban) created
- [ ] Menu structure configured

---

### Day 103 — UHT Milk and Yogurt BOMs

**Objective:** Create BOMs for UHT Milk and Yogurt with extended processing workflows including sterilization and fermentation.

*(Detailed day-by-day breakdown continues for Days 103-110...)*

---

## 4. Technical Specifications

### 4.1 Data Models Summary

| Model | Description | Key Fields |
|-------|-------------|------------|
| `mrp.bom` (extended) | Bill of Materials | dairy_product_type, expected_yield_percentage, shelf_life_days, co_product_ids |
| `mrp.bom.dairy.coproduct` | Co-product configuration | product_id, yield_percentage, cost_share_percentage |
| `mrp.production` (extended) | Production Order | batch_number, yield_variance, quality_status, expiry_date |
| `mrp.production.temp.log` | Temperature logging | production_id, temperature, is_within_range |
| `mrp.workcenter` (extended) | Work Center | workcenter_type, capacity_liters_per_hour, cip_time_minutes |
| `product.product` (extended) | Product | is_raw_milk, is_dairy_product, dairy_category |

### 4.2 API Endpoints (FastAPI)

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/production/orders` | GET | List production orders with filters |
| `/api/v1/production/orders/{id}` | GET | Get production order details |
| `/api/v1/production/orders` | POST | Create new production order |
| `/api/v1/production/orders/{id}/start` | POST | Start production |
| `/api/v1/production/orders/{id}/complete` | POST | Complete production |
| `/api/v1/production/yield-report` | GET | Get yield report |
| `/api/v1/workcenters/status` | GET | Get work center status |

### 4.3 Database Schema Extensions

```sql
-- Yield tracking index
CREATE INDEX idx_mrp_production_yield_status
ON mrp_production (yield_status)
WHERE is_dairy_production = true;

-- Expiry date index for FEFO
CREATE INDEX idx_mrp_production_expiry
ON mrp_production (expiry_date)
WHERE expiry_date IS NOT NULL;

-- Batch number unique constraint
ALTER TABLE mrp_production
ADD CONSTRAINT unique_batch_number
UNIQUE (batch_number);
```

---

## 5. Testing & Validation

### 5.1 Unit Test Cases

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| TC-11-001 | Create dairy BOM with yield tracking | BOM created with dairy fields |
| TC-11-002 | Validate yield percentage constraint | Error for values outside 0-100 |
| TC-11-003 | Create production order | Batch number auto-generated |
| TC-11-004 | Calculate expiry date | Expiry = production date + shelf life |
| TC-11-005 | Compute yield variance | Variance calculated correctly |
| TC-11-006 | Generate co-products | Co-product moves created |
| TC-11-007 | Temperature log within range | Boolean computed correctly |
| TC-11-008 | Work center capacity calculation | Correct capacity returned |

### 5.2 Integration Test Scenarios

| Scenario | Steps | Expected Outcome |
|----------|-------|------------------|
| Fresh Milk Production | Create order → Confirm → Start → Complete | Production completed with lot |
| Yield Variance Alert | Complete with low yield | Yield status = 'low' |
| Co-product Generation | Complete cheese production | Whey co-product created |
| Temperature Deviation | Log temperature outside range | Alert generated |

### 5.3 Performance Benchmarks

| Operation | Target | Measurement |
|-----------|--------|-------------|
| BOM loading | < 500ms | Page load time |
| Production order creation | < 1s | API response time |
| Yield calculation | < 100ms | Compute time |
| Dashboard refresh | < 2s | Full render time |

---

## 6. Risk & Mitigation

| Risk ID | Risk | Probability | Impact | Mitigation |
|---------|------|-------------|--------|------------|
| R11-001 | Complex BOM configurations | Medium | Medium | Start with simple BOMs, iterate |
| R11-002 | Work center capacity planning | Medium | Low | Conservative initial estimates |
| R11-003 | Yield calculation accuracy | Low | Medium | Validate with historical data |
| R11-004 | Integration with existing farm module | Low | High | Incremental integration testing |

---

## 7. Dependencies & Handoffs

### 7.1 Prerequisites from Phase 1

- Core Odoo MRP module installed and configured
- smart_farm_mgmt module with milk production tracking
- Product master data structure
- Inventory module operational

### 7.2 Outputs for Subsequent Milestones

| Output | Consumer Milestone | Description |
|--------|-------------------|-------------|
| Production lots | MS-12 (QMS) | Quality checks linked to lots |
| Finished goods | MS-13 (Inventory) | FEFO picking from production |
| Production costs | MS-14 (BD Local) | VAT on manufacturing |
| Yield reports | MS-19 (Reports) | Production analytics |

---

**Document End**

*Milestone 11: Manufacturing MRP for Dairy Operations*
*Smart Dairy Digital Smart Portal + ERP System*
*Phase 2 — ERP Core Configuration*
*Version 1.0 | February 2026*
