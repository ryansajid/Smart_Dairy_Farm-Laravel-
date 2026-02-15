# Milestone 12: Quality Management System Implementation

## Smart Dairy Digital Smart Portal + ERP — Phase 2: ERP Core Configuration

| Field            | Detail                                                        |
|------------------|---------------------------------------------------------------|
| **Milestone**    | 12 of 20 (2 of 10 in Phase 2)                                |
| **Title**        | Quality Management System Implementation                      |
| **Phase**        | Phase 2 — ERP Core Configuration (Part A: Advanced ERP)       |
| **Days**         | Days 111–120 (of 200)                                        |
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

Implement a comprehensive Quality Management System (QMS) for Smart Dairy's dairy operations, including quality control checkpoints at critical production stages (receiving, processing, packaging, dispatch), dairy-specific quality test types (Fat%, SNF%, Protein%, SCC, Temperature), automated Certificate of Analysis (COA) generation, non-conformance handling with corrective actions, and BSTI compliance documentation for Bangladesh regulatory requirements.

### 1.2 Objectives

1. Configure Odoo Quality module for dairy-specific quality management
2. Define quality control points at receiving, production, packaging, and dispatch stages
3. Implement dairy quality test types with acceptance criteria and grading algorithms
4. Create sample collection and testing workflow with mobile entry support
5. Develop automated quality alert system for out-of-specification results
6. Implement non-conformance management with root cause analysis
7. Build Certificate of Analysis (COA) generation with PDF export
8. Configure BSTI compliance documentation and regulatory reporting
9. Integrate supplier quality rating based on incoming inspection results
10. Create quality dashboards and KPI reporting

### 1.3 Key Deliverables

| # | Deliverable | Owner | Day |
|---|-------------|-------|-----|
| D12.1 | Quality module configuration and customization | Dev 1 | 111 |
| D12.2 | Quality control point definitions (4 stages) | Dev 1 | 111-112 |
| D12.3 | Dairy quality test types (Fat, SNF, Protein, SCC, Temp) | Dev 1 | 112 |
| D12.4 | Sample collection workflow | Dev 1 | 113 |
| D12.5 | Quality grading algorithm for milk | Dev 1 | 113-114 |
| D12.6 | Quality alert system | Dev 2 | 114 |
| D12.7 | Non-conformance management | Dev 1 | 115 |
| D12.8 | Certificate of Analysis (COA) template | Dev 1, Dev 3 | 116-117 |
| D12.9 | BSTI compliance documentation | Dev 1 | 117-118 |
| D12.10 | Supplier quality rating | Dev 2 | 118 |
| D12.11 | Traceability integration | Dev 2 | 119 |
| D12.12 | Quality dashboards (OWL) | Dev 3 | 119 |
| D12.13 | Integration testing and documentation | All | 120 |

### 1.4 Success Criteria

- [ ] Quality checkpoints enforced at all 4 production stages
- [ ] Quality tests accurately measure Fat%, SNF%, Protein%, SCC
- [ ] Milk grading algorithm produces correct grades (A, B, C, Reject) based on BSTI standards
- [ ] Quality alerts generated within 1 minute of out-of-spec result entry
- [ ] COA generates automatically for each batch with all required parameters
- [ ] Non-conformance workflow tracks issues to resolution
- [ ] Traceability query returns complete chain from animal to finished product
- [ ] Supplier rating updates automatically based on incoming inspection results

### 1.5 Prerequisites

- **From Milestone 11:**
  - MRP module configured with dairy BOMs
  - Production orders with lot tracking
  - Work centers operational

---

## 2. Requirement Traceability Matrix

### 2.1 BRD Requirements Mapping

| BRD Req ID | Requirement Description | SRS Trace | Day | Owner |
|-----------|------------------------|-----------|-----|-------|
| BRD-QMS-001 | Quality control points definition | SRS-QMS-001 | 111-112 | Dev 1 |
| BRD-QMS-002 | Quality test types configuration | SRS-QMS-002 | 112 | Dev 1 |
| BRD-QMS-003 | Sample collection workflow | SRS-QMS-003 | 113 | Dev 1 |
| BRD-QMS-004 | Quality grading system | SRS-QMS-004 | 113-114 | Dev 1 |
| BRD-QMS-005 | Quality alerts and notifications | SRS-QMS-005 | 114 | Dev 2 |
| BRD-QMS-006 | Non-conformance management | SRS-QMS-006 | 115 | Dev 1 |
| BRD-QMS-007 | Certificate of Analysis generation | SRS-QMS-007 | 116-117 | Dev 1 |
| BRD-QMS-008 | BSTI compliance documentation | SRS-QMS-008 | 117-118 | Dev 1 |
| BRD-QMS-009 | Supplier quality rating | SRS-QMS-009 | 118 | Dev 2 |
| BRD-QMS-010 | Farm-to-product traceability | SRS-QMS-010 | 119 | Dev 2 |

### 2.2 BSTI Milk Quality Standards (Bangladesh)

| Parameter | Grade A | Grade B | Grade C | Reject |
|-----------|---------|---------|---------|--------|
| Fat % | ≥ 4.0 | 3.5-3.99 | 3.0-3.49 | < 3.0 |
| SNF % | ≥ 8.5 | 8.0-8.49 | 7.5-7.99 | < 7.5 |
| Protein % | ≥ 3.2 | 3.0-3.19 | 2.8-2.99 | < 2.8 |
| SCC (cells/ml) | < 200,000 | 200,000-400,000 | 400,001-750,000 | > 750,000 |
| Temperature (°C) | 2-4 | 4.1-6 | 6.1-8 | > 8 |
| Acidity (% LA) | 0.12-0.14 | 0.14-0.16 | 0.16-0.18 | > 0.18 |

---

## 3. Day-by-Day Breakdown

---

### Day 111 — Quality Module Configuration & Control Points

**Objective:** Configure the Quality module and define quality control points for dairy operations.

#### Dev 1 — Backend Lead (8h)

**Task 111.1.1: Quality Module Extension (3h)**

```python
# smart_dairy_addons/smart_quality_dairy/__manifest__.py
{
    'name': 'Smart Dairy Quality Management',
    'version': '19.0.1.0.0',
    'category': 'Quality/Dairy',
    'summary': 'Dairy-specific quality management for Smart Dairy Ltd',
    'description': """
Smart Dairy Quality Management Module
=====================================
Extensions to Odoo Quality for dairy operations:
- Dairy quality test types (Fat%, SNF%, Protein%, SCC)
- Milk grading algorithm (Grade A, B, C, Reject)
- Certificate of Analysis (COA) generation
- BSTI compliance documentation
- Supplier quality rating integration
- Farm-to-product traceability
    """,
    'author': 'Smart Dairy Ltd',
    'website': 'https://smartdairy.com.bd',
    'license': 'LGPL-3',
    'depends': [
        'quality',
        'quality_control',
        'quality_mrp',
        'stock',
        'mrp',
        'smart_mrp_dairy',
        'smart_farm_mgmt',
    ],
    'data': [
        'security/ir.model.access.csv',
        'security/quality_dairy_security.xml',
        'data/quality_test_types.xml',
        'data/quality_points.xml',
        'data/quality_grade_data.xml',
        'views/quality_check_dairy_views.xml',
        'views/quality_point_dairy_views.xml',
        'views/quality_alert_views.xml',
        'views/coa_views.xml',
        'views/supplier_rating_views.xml',
        'views/quality_dairy_menu.xml',
        'reports/coa_report_template.xml',
        'reports/quality_report_templates.xml',
    ],
    'assets': {
        'web.assets_backend': [
            'smart_quality_dairy/static/src/js/quality_dashboard.js',
            'smart_quality_dairy/static/src/xml/quality_dashboard.xml',
            'smart_quality_dairy/static/src/scss/quality_dashboard.scss',
        ],
    },
    'installable': True,
    'application': False,
    'auto_install': False,
}
```

**Task 111.1.2: Quality Check Extension Model (3h)**

```python
# smart_dairy_addons/smart_quality_dairy/models/quality_check_dairy.py
from odoo import models, fields, api, _
from odoo.exceptions import UserError, ValidationError
from datetime import datetime

class QualityCheckDairy(models.Model):
    _inherit = 'quality.check'

    # Dairy-specific fields
    is_dairy_check = fields.Boolean(
        string='Is Dairy Check',
        default=False
    )
    check_stage = fields.Selection([
        ('receiving', 'Receiving Inspection'),
        ('in_process', 'In-Process Check'),
        ('packaging', 'Packaging Check'),
        ('dispatch', 'Dispatch Check'),
    ], string='Check Stage')

    # Sample Information
    sample_id = fields.Many2one(
        'quality.sample.dairy',
        string='Sample'
    )
    sample_date = fields.Datetime(
        string='Sample Date',
        default=fields.Datetime.now
    )
    sample_temperature = fields.Float(
        string='Sample Temp at Collection (°C)',
        digits=(4, 1)
    )

    # Milk Quality Parameters
    fat_percentage = fields.Float(
        string='Fat %',
        digits=(4, 2),
        help='Milk fat percentage'
    )
    snf_percentage = fields.Float(
        string='SNF %',
        digits=(4, 2),
        help='Solid-Not-Fat percentage'
    )
    protein_percentage = fields.Float(
        string='Protein %',
        digits=(4, 2)
    )
    lactose_percentage = fields.Float(
        string='Lactose %',
        digits=(4, 2)
    )
    somatic_cell_count = fields.Integer(
        string='SCC (cells/ml)',
        help='Somatic Cell Count'
    )
    total_plate_count = fields.Integer(
        string='TPC (CFU/ml)',
        help='Total Plate Count (Colony Forming Units)'
    )
    acidity_percentage = fields.Float(
        string='Acidity (% LA)',
        digits=(4, 3),
        help='Acidity as Lactic Acid percentage'
    )
    temperature = fields.Float(
        string='Temperature (°C)',
        digits=(4, 1)
    )
    ph_value = fields.Float(
        string='pH Value',
        digits=(4, 2)
    )

    # Adulteration Tests
    water_added = fields.Boolean(string='Water Added')
    urea_detected = fields.Boolean(string='Urea Detected')
    starch_detected = fields.Boolean(string='Starch Detected')
    neutralizer_detected = fields.Boolean(string='Neutralizer Detected')
    antibiotic_residue = fields.Boolean(string='Antibiotic Residue')

    # Grading
    milk_grade = fields.Selection([
        ('A', 'Grade A (Premium)'),
        ('B', 'Grade B (Standard)'),
        ('C', 'Grade C (Economy)'),
        ('reject', 'Reject'),
    ], string='Milk Grade', compute='_compute_milk_grade', store=True)

    grade_reason = fields.Text(
        string='Grade Reason',
        compute='_compute_milk_grade',
        store=True
    )

    # Source Tracking
    farm_id = fields.Many2one(
        'farm.farm',
        string='Source Farm'
    )
    animal_id = fields.Many2one(
        'farm.animal',
        string='Source Animal'
    )
    milk_production_id = fields.Many2one(
        'farm.milk.production',
        string='Milk Production Record'
    )

    # Test Equipment
    test_equipment_id = fields.Many2one(
        'farm.equipment',
        string='Test Equipment',
        domain=[('equipment_type', '=', 'lab')]
    )
    tested_by = fields.Many2one(
        'res.users',
        string='Tested By',
        default=lambda self: self.env.user
    )

    # COA Link
    coa_id = fields.Many2one(
        'quality.coa.dairy',
        string='Certificate of Analysis'
    )

    @api.depends('fat_percentage', 'snf_percentage', 'protein_percentage',
                 'somatic_cell_count', 'temperature', 'acidity_percentage',
                 'water_added', 'urea_detected', 'starch_detected',
                 'neutralizer_detected', 'antibiotic_residue')
    def _compute_milk_grade(self):
        """Compute milk grade based on BSTI standards"""
        for check in self:
            if not check.is_dairy_check:
                check.milk_grade = False
                check.grade_reason = ''
                continue

            # Check for adulteration (automatic reject)
            adulterants = []
            if check.water_added:
                adulterants.append('Water')
            if check.urea_detected:
                adulterants.append('Urea')
            if check.starch_detected:
                adulterants.append('Starch')
            if check.neutralizer_detected:
                adulterants.append('Neutralizer')
            if check.antibiotic_residue:
                adulterants.append('Antibiotic')

            if adulterants:
                check.milk_grade = 'reject'
                check.grade_reason = f"Adulteration detected: {', '.join(adulterants)}"
                continue

            # Grade based on quality parameters
            grades = []
            reasons = []

            # Fat %
            if check.fat_percentage >= 4.0:
                grades.append('A')
            elif check.fat_percentage >= 3.5:
                grades.append('B')
            elif check.fat_percentage >= 3.0:
                grades.append('C')
            else:
                grades.append('reject')
                reasons.append(f"Fat {check.fat_percentage}% < 3.0%")

            # SNF %
            if check.snf_percentage >= 8.5:
                grades.append('A')
            elif check.snf_percentage >= 8.0:
                grades.append('B')
            elif check.snf_percentage >= 7.5:
                grades.append('C')
            else:
                grades.append('reject')
                reasons.append(f"SNF {check.snf_percentage}% < 7.5%")

            # SCC
            if check.somatic_cell_count and check.somatic_cell_count > 0:
                if check.somatic_cell_count < 200000:
                    grades.append('A')
                elif check.somatic_cell_count <= 400000:
                    grades.append('B')
                elif check.somatic_cell_count <= 750000:
                    grades.append('C')
                else:
                    grades.append('reject')
                    reasons.append(f"SCC {check.somatic_cell_count} > 750,000")

            # Temperature
            if check.temperature:
                if check.temperature <= 4.0:
                    grades.append('A')
                elif check.temperature <= 6.0:
                    grades.append('B')
                elif check.temperature <= 8.0:
                    grades.append('C')
                else:
                    grades.append('reject')
                    reasons.append(f"Temperature {check.temperature}°C > 8°C")

            # Determine overall grade (lowest grade wins)
            grade_priority = {'reject': 0, 'C': 1, 'B': 2, 'A': 3}
            min_grade = min(grades, key=lambda g: grade_priority.get(g, 0))

            check.milk_grade = min_grade
            if reasons:
                check.grade_reason = '; '.join(reasons)
            else:
                check.grade_reason = f"All parameters within Grade {min_grade} limits"

    def action_pass(self):
        """Override pass action to generate COA if configured"""
        res = super().action_pass()
        for check in self:
            if check.is_dairy_check and check.point_id.generate_coa:
                check._generate_coa()
        return res

    def _generate_coa(self):
        """Generate Certificate of Analysis"""
        self.ensure_one()
        coa_vals = {
            'quality_check_id': self.id,
            'product_id': self.product_id.id,
            'lot_id': self.lot_id.id if self.lot_id else False,
            'production_id': self.production_id.id if self.production_id else False,
            'test_date': self.test_datetime or fields.Datetime.now(),
            'fat_percentage': self.fat_percentage,
            'snf_percentage': self.snf_percentage,
            'protein_percentage': self.protein_percentage,
            'somatic_cell_count': self.somatic_cell_count,
            'temperature': self.temperature,
            'milk_grade': self.milk_grade,
        }
        self.coa_id = self.env['quality.coa.dairy'].create(coa_vals)
        return self.coa_id


class QualitySampleDairy(models.Model):
    _name = 'quality.sample.dairy'
    _description = 'Dairy Quality Sample'
    _order = 'sample_date desc'

    name = fields.Char(
        string='Sample Reference',
        required=True,
        copy=False,
        default=lambda self: _('New')
    )
    sample_date = fields.Datetime(
        string='Sample Date',
        default=fields.Datetime.now,
        required=True
    )
    sample_type = fields.Selection([
        ('raw_milk', 'Raw Milk'),
        ('in_process', 'In-Process'),
        ('finished', 'Finished Product'),
    ], string='Sample Type', required=True)

    # Source
    lot_id = fields.Many2one('stock.lot', string='Lot/Batch')
    production_id = fields.Many2one('mrp.production', string='Production Order')
    picking_id = fields.Many2one('stock.picking', string='Receipt/Delivery')
    farm_id = fields.Many2one('farm.farm', string='Source Farm')

    # Sample Details
    quantity = fields.Float(string='Sample Quantity (ml)', default=100.0)
    collection_temp = fields.Float(string='Collection Temp (°C)', digits=(4, 1))
    collected_by = fields.Many2one('res.users', string='Collected By',
                                   default=lambda self: self.env.user)

    # Status
    state = fields.Selection([
        ('collected', 'Collected'),
        ('testing', 'In Testing'),
        ('tested', 'Tested'),
        ('archived', 'Archived'),
    ], string='Status', default='collected')

    # Quality Checks
    quality_check_ids = fields.One2many(
        'quality.check',
        'sample_id',
        string='Quality Checks'
    )

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            if vals.get('name', _('New')) == _('New'):
                vals['name'] = self.env['ir.sequence'].next_by_code(
                    'quality.sample.dairy'
                ) or _('New')
        return super().create(vals_list)
```

**Task 111.1.3: Quality Control Points Configuration (2h)**

```xml
<!-- smart_dairy_addons/smart_quality_dairy/data/quality_points.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo noupdate="1">
    <!-- Receiving Inspection Point -->
    <record id="quality_point_receiving" model="quality.point">
        <field name="name">Raw Milk Receiving Inspection</field>
        <field name="title">Receiving Quality Check</field>
        <field name="product_tmpl_id" ref="smart_mrp_dairy.product_raw_milk_tmpl"/>
        <field name="picking_type_ids" eval="[(4, ref('stock.picking_type_in'))]"/>
        <field name="test_type_id" ref="quality.test_type_measure"/>
        <field name="measure_on">product</field>
        <field name="is_dairy_point">True</field>
        <field name="check_stage">receiving</field>
        <field name="is_mandatory">True</field>
        <field name="generate_coa">False</field>
        <field name="failure_action">quarantine</field>
    </record>

    <!-- In-Process Quality Point (Pasteurization) -->
    <record id="quality_point_pasteurization" model="quality.point">
        <field name="name">Pasteurization Quality Check</field>
        <field name="title">Post-Pasteurization Verification</field>
        <field name="operation_id" ref="smart_mrp_dairy.operation_fresh_milk_pasteurization"/>
        <field name="test_type_id" ref="quality.test_type_measure"/>
        <field name="is_dairy_point">True</field>
        <field name="check_stage">in_process</field>
        <field name="is_mandatory">True</field>
        <field name="generate_coa">False</field>
        <field name="failure_action">halt_production</field>
    </record>

    <!-- Packaging Quality Point -->
    <record id="quality_point_packaging" model="quality.point">
        <field name="name">Packaging Quality Check</field>
        <field name="title">Final Product Packaging Verification</field>
        <field name="operation_id" ref="smart_mrp_dairy.operation_fresh_milk_packaging"/>
        <field name="test_type_id" ref="quality.test_type_measure"/>
        <field name="is_dairy_point">True</field>
        <field name="check_stage">packaging</field>
        <field name="is_mandatory">True</field>
        <field name="generate_coa">True</field>
        <field name="failure_action">quarantine</field>
    </record>

    <!-- Dispatch Quality Point -->
    <record id="quality_point_dispatch" model="quality.point">
        <field name="name">Dispatch Quality Verification</field>
        <field name="title">Pre-Dispatch Quality Confirmation</field>
        <field name="picking_type_ids" eval="[(4, ref('stock.picking_type_out'))]"/>
        <field name="test_type_id" ref="quality.test_type_passfail"/>
        <field name="is_dairy_point">True</field>
        <field name="check_stage">dispatch</field>
        <field name="is_mandatory">True</field>
        <field name="generate_coa">False</field>
        <field name="failure_action">block_shipment</field>
    </record>
</odoo>
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Task 111.2.1: Quality Point Extension (4h)**

```python
# smart_dairy_addons/smart_quality_dairy/models/quality_point_dairy.py
from odoo import models, fields, api

class QualityPointDairy(models.Model):
    _inherit = 'quality.point'

    # Dairy-specific fields
    is_dairy_point = fields.Boolean(
        string='Is Dairy Point',
        default=False
    )
    check_stage = fields.Selection([
        ('receiving', 'Receiving Inspection'),
        ('in_process', 'In-Process Check'),
        ('packaging', 'Packaging Check'),
        ('dispatch', 'Dispatch Check'),
    ], string='Check Stage')

    # Mandatory Tests
    is_mandatory = fields.Boolean(
        string='Mandatory Check',
        default=True,
        help='If checked, production/shipment cannot proceed without passing'
    )

    # COA Generation
    generate_coa = fields.Boolean(
        string='Generate COA on Pass',
        default=False
    )

    # Failure Action
    failure_action = fields.Selection([
        ('alert', 'Alert Only'),
        ('quarantine', 'Quarantine Product'),
        ('halt_production', 'Halt Production'),
        ('block_shipment', 'Block Shipment'),
    ], string='Failure Action', default='alert')

    # Test Parameters
    test_fat = fields.Boolean(string='Test Fat %', default=True)
    test_snf = fields.Boolean(string='Test SNF %', default=True)
    test_protein = fields.Boolean(string='Test Protein %', default=False)
    test_scc = fields.Boolean(string='Test SCC', default=True)
    test_temperature = fields.Boolean(string='Test Temperature', default=True)
    test_acidity = fields.Boolean(string='Test Acidity', default=False)
    test_adulteration = fields.Boolean(string='Test Adulteration', default=True)

    # Grade Thresholds (can be customized per point)
    min_fat_percentage = fields.Float(string='Min Fat %', default=3.0)
    min_snf_percentage = fields.Float(string='Min SNF %', default=7.5)
    max_temperature = fields.Float(string='Max Temperature (°C)', default=8.0)
    max_scc = fields.Integer(string='Max SCC', default=750000)
```

**Task 111.2.2: Quality Alert System (2h)**

```python
# smart_dairy_addons/smart_quality_dairy/models/quality_alert_dairy.py
from odoo import models, fields, api, _

class QualityAlertDairy(models.Model):
    _inherit = 'quality.alert'

    # Dairy-specific alert fields
    is_dairy_alert = fields.Boolean(
        string='Is Dairy Alert',
        default=False
    )
    alert_category = fields.Selection([
        ('parameter_fail', 'Parameter Out of Spec'),
        ('adulteration', 'Adulteration Detected'),
        ('temperature', 'Temperature Deviation'),
        ('equipment', 'Equipment Calibration'),
        ('process', 'Process Deviation'),
    ], string='Alert Category')

    # Related Quality Check
    quality_check_id = fields.Many2one(
        'quality.check',
        string='Quality Check'
    )

    # Affected Products
    affected_quantity = fields.Float(
        string='Affected Quantity'
    )
    affected_lot_ids = fields.Many2many(
        'stock.lot',
        string='Affected Lots'
    )

    # Root Cause Analysis
    root_cause = fields.Text(string='Root Cause')
    corrective_action = fields.Text(string='Corrective Action')
    preventive_action = fields.Text(string='Preventive Action')

    # Timeline
    detected_date = fields.Datetime(
        string='Detected Date',
        default=fields.Datetime.now
    )
    resolved_date = fields.Datetime(string='Resolved Date')
    resolution_time_hours = fields.Float(
        string='Resolution Time (hours)',
        compute='_compute_resolution_time'
    )

    @api.depends('detected_date', 'resolved_date')
    def _compute_resolution_time(self):
        for alert in self:
            if alert.detected_date and alert.resolved_date:
                delta = alert.resolved_date - alert.detected_date
                alert.resolution_time_hours = delta.total_seconds() / 3600
            else:
                alert.resolution_time_hours = 0.0

    def action_create_from_quality_check(self, quality_check_id):
        """Create alert from failed quality check"""
        check = self.env['quality.check'].browse(quality_check_id)
        alert_vals = {
            'name': f"Quality Alert - {check.name}",
            'is_dairy_alert': True,
            'quality_check_id': check.id,
            'product_id': check.product_id.id,
            'lot_id': check.lot_id.id if check.lot_id else False,
            'partner_id': check.partner_id.id if check.partner_id else False,
            'team_id': check.team_id.id if check.team_id else False,
        }

        # Determine category
        if check.water_added or check.urea_detected or check.starch_detected:
            alert_vals['alert_category'] = 'adulteration'
            alert_vals['priority'] = '3'  # Very High
        elif check.temperature and check.temperature > 8.0:
            alert_vals['alert_category'] = 'temperature'
            alert_vals['priority'] = '2'  # High
        else:
            alert_vals['alert_category'] = 'parameter_fail'
            alert_vals['priority'] = '1'  # Normal

        return self.create(alert_vals)
```

**Task 111.2.3: Unit Tests (2h)**

```python
# smart_dairy_addons/smart_quality_dairy/tests/test_quality_check.py
from odoo.tests import TransactionCase, tagged
from odoo.exceptions import ValidationError

@tagged('post_install', '-at_install')
class TestQualityCheckDairy(TransactionCase):

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.product = cls.env['product.product'].create({
            'name': 'Test Milk',
            'type': 'product',
        })

    def test_milk_grade_a(self):
        """Test Grade A milk classification"""
        check = self.env['quality.check'].create({
            'product_id': self.product.id,
            'is_dairy_check': True,
            'fat_percentage': 4.5,
            'snf_percentage': 8.8,
            'protein_percentage': 3.4,
            'somatic_cell_count': 150000,
            'temperature': 3.5,
        })
        self.assertEqual(check.milk_grade, 'A')

    def test_milk_grade_reject_adulteration(self):
        """Test rejection due to adulteration"""
        check = self.env['quality.check'].create({
            'product_id': self.product.id,
            'is_dairy_check': True,
            'fat_percentage': 4.5,
            'snf_percentage': 8.8,
            'water_added': True,
        })
        self.assertEqual(check.milk_grade, 'reject')
        self.assertIn('Water', check.grade_reason)

    def test_milk_grade_c_low_fat(self):
        """Test Grade C due to low fat"""
        check = self.env['quality.check'].create({
            'product_id': self.product.id,
            'is_dairy_check': True,
            'fat_percentage': 3.2,  # Grade C range
            'snf_percentage': 8.8,
            'somatic_cell_count': 150000,
            'temperature': 3.5,
        })
        self.assertEqual(check.milk_grade, 'C')
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Task 111.3.1: Quality Check Form View Extension (4h)**

```xml
<!-- smart_dairy_addons/smart_quality_dairy/views/quality_check_dairy_views.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Quality Check Form View Extension -->
    <record id="quality_check_dairy_form" model="ir.ui.view">
        <field name="name">quality.check.dairy.form</field>
        <field name="model">quality.check</field>
        <field name="inherit_id" ref="quality_control.quality_check_view_form"/>
        <field name="arch" type="xml">
            <xpath expr="//sheet" position="before">
                <div class="alert alert-warning" role="alert"
                     invisible="milk_grade != 'reject'">
                    <strong><i class="fa fa-exclamation-triangle"/> REJECTED</strong>
                    - This sample does not meet quality standards.
                    <field name="grade_reason" nolabel="1" class="d-block mt-2"/>
                </div>
                <div class="alert alert-success" role="alert"
                     invisible="milk_grade not in ('A', 'B', 'C') or quality_state != 'pass'">
                    <strong><i class="fa fa-check-circle"/>
                    Grade <field name="milk_grade" nolabel="1" class="d-inline"/></strong>
                    - Sample meets quality standards.
                </div>
            </xpath>

            <xpath expr="//notebook" position="inside">
                <page string="Dairy Quality Parameters" name="dairy_params"
                      invisible="not is_dairy_check">
                    <group>
                        <group string="Composition">
                            <field name="fat_percentage"/>
                            <field name="snf_percentage"/>
                            <field name="protein_percentage"/>
                            <field name="lactose_percentage"/>
                        </group>
                        <group string="Hygiene">
                            <field name="somatic_cell_count"/>
                            <field name="total_plate_count"/>
                            <field name="acidity_percentage"/>
                        </group>
                    </group>
                    <group>
                        <group string="Physical">
                            <field name="temperature"/>
                            <field name="ph_value"/>
                            <field name="sample_temperature"/>
                        </group>
                        <group string="Grading">
                            <field name="milk_grade" widget="badge"
                                   decoration-success="milk_grade == 'A'"
                                   decoration-info="milk_grade == 'B'"
                                   decoration-warning="milk_grade == 'C'"
                                   decoration-danger="milk_grade == 'reject'"/>
                            <field name="grade_reason"/>
                        </group>
                    </group>
                    <group string="Adulteration Tests">
                        <group>
                            <field name="water_added"/>
                            <field name="urea_detected"/>
                            <field name="starch_detected"/>
                        </group>
                        <group>
                            <field name="neutralizer_detected"/>
                            <field name="antibiotic_residue"/>
                        </group>
                    </group>
                </page>

                <page string="Source Tracking" name="source_tracking"
                      invisible="not is_dairy_check">
                    <group>
                        <group string="Farm Source">
                            <field name="farm_id"/>
                            <field name="animal_id"/>
                            <field name="milk_production_id"/>
                        </group>
                        <group string="Test Information">
                            <field name="sample_id"/>
                            <field name="test_equipment_id"/>
                            <field name="tested_by"/>
                        </group>
                    </group>
                </page>

                <page string="Certificate of Analysis" name="coa"
                      invisible="not coa_id">
                    <group>
                        <field name="coa_id"/>
                    </group>
                    <button name="action_view_coa" type="object"
                            string="View COA" class="btn-primary"
                            invisible="not coa_id"/>
                    <button name="action_print_coa" type="object"
                            string="Print COA" class="btn-secondary"
                            invisible="not coa_id"/>
                </page>
            </xpath>
        </field>
    </record>
</odoo>
```

**Task 111.3.2: Quality Dashboard SCSS (2h)**

```scss
// smart_dairy_addons/smart_quality_dairy/static/src/scss/quality_dashboard.scss
.o_quality_dashboard {
    padding: 20px;
    background: #f8f9fa;

    .quality-summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;

        .summary-card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;

            &.grade-a { border-top: 4px solid #28a745; }
            &.grade-b { border-top: 4px solid #17a2b8; }
            &.grade-c { border-top: 4px solid #ffc107; }
            &.rejected { border-top: 4px solid #dc3545; }
            &.pending { border-top: 4px solid #6c757d; }

            .card-value {
                font-size: 2.5rem;
                font-weight: 700;
            }

            .card-label {
                color: #6c757d;
                font-size: 0.9rem;
                text-transform: uppercase;
            }

            .card-percentage {
                font-size: 0.85rem;
                margin-top: 8px;

                &.positive { color: #28a745; }
                &.negative { color: #dc3545; }
            }
        }
    }

    .quality-chart-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 16px;
        margin-bottom: 24px;

        .chart-card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);

            h4 {
                margin-bottom: 16px;
                color: #495057;
            }
        }
    }

    .quality-alerts-section {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);

        .alert-item {
            display: flex;
            align-items: center;
            padding: 12px;
            border-bottom: 1px solid #e9ecef;

            &:last-child { border-bottom: none; }

            .alert-icon {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 12px;

                &.critical { background: #f8d7da; color: #721c24; }
                &.warning { background: #fff3cd; color: #856404; }
                &.info { background: #d1ecf1; color: #0c5460; }
            }

            .alert-content {
                flex: 1;

                .alert-title {
                    font-weight: 600;
                    color: #212529;
                }

                .alert-time {
                    font-size: 0.8rem;
                    color: #6c757d;
                }
            }
        }
    }
}
```

**Task 111.3.3: Mobile Entry Wireframes (2h)**

Design wireframes for mobile quality entry interface supporting field workers.

**End-of-Day 111 Deliverables:**
- [ ] smart_quality_dairy module scaffold created
- [ ] Quality check model extended with dairy parameters
- [ ] Milk grading algorithm implemented (Grade A, B, C, Reject)
- [ ] 4 Quality control points configured (Receiving, In-Process, Packaging, Dispatch)
- [ ] Quality point extension with failure actions
- [ ] Quality alert model extended
- [ ] Unit tests for grading algorithm (3 test cases)
- [ ] Quality check form view with dairy parameters
- [ ] Dashboard SCSS foundation

---

### Day 112 — Quality Test Types and Sample Collection

*(Days 112-120 continue with detailed breakdowns...)*

---

## 4. Technical Specifications

### 4.1 Data Models Summary

| Model | Description | Key Fields |
|-------|-------------|------------|
| `quality.check` (extended) | Quality Check | fat_percentage, snf_percentage, milk_grade, is_dairy_check |
| `quality.sample.dairy` | Sample Collection | sample_date, sample_type, lot_id, collection_temp |
| `quality.point` (extended) | Quality Point | check_stage, is_mandatory, generate_coa, failure_action |
| `quality.alert` (extended) | Quality Alert | alert_category, root_cause, corrective_action |
| `quality.coa.dairy` | Certificate of Analysis | quality_check_id, test_results, certificate_number |

### 4.2 Milk Grading Algorithm

```python
def calculate_milk_grade(fat, snf, scc, temperature, adulterants):
    """
    Calculate milk grade based on BSTI Bangladesh standards

    Returns: ('A' | 'B' | 'C' | 'reject', reason_string)
    """
    # 1. Check adulterants (auto-reject)
    if any(adulterants.values()):
        return ('reject', 'Adulteration detected')

    # 2. Calculate grade for each parameter
    grades = {
        'fat': grade_fat(fat),
        'snf': grade_snf(snf),
        'scc': grade_scc(scc),
        'temp': grade_temperature(temperature)
    }

    # 3. Overall grade = lowest individual grade
    priority = {'reject': 0, 'C': 1, 'B': 2, 'A': 3}
    final_grade = min(grades.values(), key=lambda g: priority[g])

    return (final_grade, explain_grade(grades))
```

### 4.3 COA Template Fields

| Field | Type | Description |
|-------|------|-------------|
| Certificate Number | Char | Auto-generated unique ID |
| Product | Many2one | Tested product |
| Lot/Batch | Many2one | Production lot |
| Test Date | Datetime | Date of testing |
| Fat % | Float | Measured fat percentage |
| SNF % | Float | Measured SNF percentage |
| Protein % | Float | Measured protein percentage |
| SCC | Integer | Somatic cell count |
| Temperature | Float | Product temperature |
| Overall Grade | Selection | Final milk grade |
| Tested By | Many2one | QC technician |
| Approved By | Many2one | QC manager |
| QR Code | Binary | Verification QR code |

---

## 5. Testing & Validation

### 5.1 Unit Test Cases

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| TC-12-001 | Grade A milk classification | milk_grade = 'A' |
| TC-12-002 | Grade B milk classification | milk_grade = 'B' |
| TC-12-003 | Rejection due to adulteration | milk_grade = 'reject' |
| TC-12-004 | Quality alert generation | Alert created on failure |
| TC-12-005 | COA generation on pass | COA record created |
| TC-12-006 | Sample sequence generation | Unique sample number |
| TC-12-007 | Temperature deviation alert | Alert category = 'temperature' |
| TC-12-008 | Supplier rating update | Rating recalculated |

### 5.2 Integration Test Scenarios

| Scenario | Steps | Expected Outcome |
|----------|-------|------------------|
| Receiving Inspection | Receive milk → Sample → Test → Pass/Fail | Quality record linked to receipt |
| Production Quality | Production → Sample → Test → COA | COA linked to production lot |
| Supplier Quality | Multiple receipts → Calculate rating | Average rating computed |
| Traceability | Query lot → Show full chain | Animal → Milk → Production → Product |

---

## 6. Risk & Mitigation

| Risk ID | Risk | Probability | Impact | Mitigation |
|---------|------|-------------|--------|------------|
| R12-001 | Grading algorithm errors | Low | High | Extensive testing with real data |
| R12-002 | COA template compliance | Medium | Medium | Review with BSTI consultant |
| R12-003 | Alert fatigue | Medium | Low | Configurable thresholds |
| R12-004 | Traceability gaps | Low | High | Enforce mandatory lot tracking |

---

## 7. Dependencies & Handoffs

### 7.1 Prerequisites from Milestone 11

- Production orders with lot tracking
- Work centers with quality checkpoints
- Product master data with dairy attributes

### 7.2 Outputs for Subsequent Milestones

| Output | Consumer Milestone | Description |
|--------|-------------------|-------------|
| Quality grades | MS-13 (Inventory) | FEFO considers quality grade |
| COA records | MS-16 (B2B Portal) | COA download for customers |
| Supplier ratings | MS-16 (B2B) | Supplier selection criteria |
| Traceability data | MS-19 (Reports) | Quality analytics |

---

**Document End**

*Milestone 12: Quality Management System Implementation*
*Smart Dairy Digital Smart Portal + ERP System*
*Phase 2 — ERP Core Configuration*
*Version 1.0 | February 2026*
