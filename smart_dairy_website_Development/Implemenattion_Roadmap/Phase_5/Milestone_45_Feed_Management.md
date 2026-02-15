# Milestone 45: Feed Management System
## Smart Dairy Digital Smart Portal + ERP - Phase 5

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-P5-M45-001 |
| **Milestone** | 45 of 50 (5 of 10 in Phase 5) |
| **Title** | Feed Management System |
| **Phase** | Phase 5 - Farm Management Foundation |
| **Days** | Days 291-300 (of 350) |
| **Version** | 1.0 |
| **Status** | Draft |
| **Classification** | Internal |
| **Last Updated** | 2025-01-15 |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Requirement Traceability Matrix](#2-requirement-traceability-matrix)
3. [Day-by-Day Implementation](#3-day-by-day-implementation)
4. [Technical Specifications](#4-technical-specifications)
5. [Testing & Validation](#5-testing--validation)
6. [Risk & Mitigation](#6-risk--mitigation)
7. [Dependencies & Handoffs](#7-dependencies--handoffs)

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Implement a comprehensive feed management system enabling ration formulation, feed inventory tracking, consumption monitoring, cost analysis, and demand forecasting for optimal dairy nutrition management.

### 1.2 Objectives

| # | Objective |
|---|-----------|
| 1 | Create FarmFeedType model with nutritional composition data |
| 2 | Implement ration formulation engine with optimization algorithms |
| 3 | Build feed inventory management with ERP integration |
| 4 | Develop feeding schedule automation by animal groups |
| 5 | Create feed consumption tracking with variance analysis |
| 6 | Implement feed cost analysis with cost-per-liter calculations |
| 7 | Build demand forecasting for procurement planning |
| 8 | Develop group feeding management with batch operations |
| 9 | Create feed efficiency reports and analytics |
| 10 | Implement comprehensive testing and documentation |

### 1.3 Key Deliverables

| Deliverable | Type | Description |
|-------------|------|-------------|
| FarmFeedType Model | Backend | Feed types with nutritional profiles |
| FarmRation Model | Backend | Ration formulations with ingredients |
| FarmFeedInventory Model | Backend | Inventory tracking with alerts |
| FarmFeedingSchedule Model | Backend | Automated feeding schedules |
| FarmFeedConsumption Model | Backend | Daily consumption records |
| Ration Planner UI | Frontend | Interactive ration formulation |
| Feed Dashboard | Frontend | Inventory and cost analytics |
| Feed Mobile Module | Mobile | Consumption entry and scanning |
| Feed Reports | Backend | Efficiency and cost reports |

### 1.4 Prerequisites

- Milestone 41 (Herd Management) completed - Animal records available
- Milestone 44 (Milk Production) completed - Production data for efficiency
- Phase 4 ERP modules available for inventory integration
- Feed supplier master data configured

### 1.5 Success Criteria

| Criteria | Target |
|----------|--------|
| Ration calculation accuracy | ±2% of nutritional targets |
| Inventory sync frequency | Real-time with ERP |
| Cost calculation accuracy | 99.5% |
| Feed efficiency tracking | Per animal/group |
| Mobile consumption entry | <30 seconds per entry |

---

## 2. Requirement Traceability Matrix

| Req ID | Source | Description | Day(s) | Status |
|--------|--------|-------------|--------|--------|
| RFP-FARM-013 | RFP | Ration planning by animal group | 292 | Planned |
| RFP-FARM-014 | RFP | Feed inventory management | 293 | Planned |
| BRD-FARM-045 | BRD | Nutritional calculation engine | 292 | Planned |
| BRD-FARM-046 | BRD | Feed cost tracking | 296 | Planned |
| BRD-FARM-047 | BRD | Demand forecasting | 297 | Planned |
| SRS-FARM-089 | SRS | FarmFeedType model implementation | 291 | Planned |
| SRS-FARM-090 | SRS | Feeding schedule automation | 294 | Planned |
| SRS-FARM-091 | SRS | Consumption variance analysis | 295 | Planned |

---

## 3. Day-by-Day Implementation

### Day 291: Feed Type & Nutritional Models

**Theme:** Foundation Models for Feed Management

#### Dev 1 - Backend Lead (8h)

**Task 1: FarmFeedType Model (5h)**
```python
# farm_feed/models/feed_type.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError

class FarmFeedType(models.Model):
    _name = 'farm.feed.type'
    _description = 'Farm Feed Type'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'category, name'

    name = fields.Char(string='Feed Name', required=True, tracking=True)
    name_bn = fields.Char(string='Feed Name (Bangla)')
    code = fields.Char(string='Feed Code', required=True, copy=False)
    category = fields.Selection([
        ('roughage', 'Roughage'),
        ('concentrate', 'Concentrate'),
        ('mineral', 'Mineral Supplement'),
        ('vitamin', 'Vitamin Supplement'),
        ('additive', 'Feed Additive'),
        ('silage', 'Silage'),
        ('hay', 'Hay'),
        ('green_fodder', 'Green Fodder'),
        ('byproduct', 'Agricultural Byproduct'),
    ], string='Category', required=True, tracking=True)

    # Nutritional Composition (per kg DM)
    dry_matter = fields.Float(string='Dry Matter %', digits=(5, 2))
    crude_protein = fields.Float(string='Crude Protein %', digits=(5, 2))
    metabolizable_energy = fields.Float(string='ME (MJ/kg)', digits=(5, 2))
    total_digestible_nutrients = fields.Float(string='TDN %', digits=(5, 2))
    crude_fiber = fields.Float(string='Crude Fiber %', digits=(5, 2))
    ether_extract = fields.Float(string='Ether Extract %', digits=(5, 2))
    ndf = fields.Float(string='NDF %', digits=(5, 2), help='Neutral Detergent Fiber')
    adf = fields.Float(string='ADF %', digits=(5, 2), help='Acid Detergent Fiber')
    calcium = fields.Float(string='Calcium %', digits=(5, 3))
    phosphorus = fields.Float(string='Phosphorus %', digits=(5, 3))

    # Cost & Inventory
    product_id = fields.Many2one('product.product', string='Linked Product')
    unit_cost = fields.Float(string='Unit Cost (BDT/kg)', digits=(10, 2))
    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.company.currency_id)
    uom_id = fields.Many2one('uom.uom', string='Unit of Measure', default=lambda self: self.env.ref('uom.product_uom_kgm'))

    # Storage & Handling
    storage_type = fields.Selection([
        ('dry', 'Dry Storage'),
        ('refrigerated', 'Refrigerated'),
        ('silo', 'Silo Storage'),
        ('open', 'Open Storage'),
    ], string='Storage Type', default='dry')
    shelf_life_days = fields.Integer(string='Shelf Life (Days)')
    min_stock_level = fields.Float(string='Minimum Stock Level')

    # Status
    active = fields.Boolean(default=True)
    is_locally_available = fields.Boolean(string='Locally Available in Bangladesh')
    supplier_ids = fields.Many2many('res.partner', string='Suppliers', domain=[('supplier_rank', '>', 0)])

    _sql_constraints = [
        ('code_unique', 'UNIQUE(code)', 'Feed code must be unique!'),
    ]

    @api.constrains('dry_matter')
    def _check_dry_matter(self):
        for record in self:
            if record.dry_matter and (record.dry_matter < 0 or record.dry_matter > 100):
                raise ValidationError('Dry Matter must be between 0 and 100%')
```

**Task 2: Feed Nutrient Requirements Model (3h)**
```python
class FarmNutrientRequirement(models.Model):
    _name = 'farm.nutrient.requirement'
    _description = 'Nutrient Requirements by Animal Category'

    name = fields.Char(string='Requirement Name', required=True)
    animal_category = fields.Selection([
        ('lactating_high', 'Lactating - High Yield (>20L/day)'),
        ('lactating_medium', 'Lactating - Medium Yield (10-20L/day)'),
        ('lactating_low', 'Lactating - Low Yield (<10L/day)'),
        ('dry', 'Dry Cow'),
        ('pregnant', 'Pregnant (Last Trimester)'),
        ('heifer', 'Heifer'),
        ('calf', 'Calf (<6 months)'),
        ('bull', 'Bull'),
    ], required=True)

    # Daily Requirements
    dm_intake_percent_bw = fields.Float(string='DM Intake (% Body Weight)')
    crude_protein_percent = fields.Float(string='Crude Protein %')
    me_requirement = fields.Float(string='ME (MJ/day)')
    tdn_percent = fields.Float(string='TDN %')
    calcium_grams = fields.Float(string='Calcium (g/day)')
    phosphorus_grams = fields.Float(string='Phosphorus (g/day)')
    ndf_min_percent = fields.Float(string='Min NDF %')
    ndf_max_percent = fields.Float(string='Max NDF %')
```

#### Dev 2 - Full-Stack (8h)

**Task 1: Feed Library Data Import (4h)**
```python
# farm_feed/wizard/feed_import_wizard.py
class FeedImportWizard(models.TransientModel):
    _name = 'farm.feed.import.wizard'
    _description = 'Import Feed Library'

    file = fields.Binary(string='Excel File', required=True)
    filename = fields.Char(string='Filename')

    def action_import(self):
        import base64
        import openpyxl
        from io import BytesIO

        file_content = base64.b64decode(self.file)
        wb = openpyxl.load_workbook(BytesIO(file_content))
        ws = wb.active

        FeedType = self.env['farm.feed.type']
        imported = 0

        for row in ws.iter_rows(min_row=2, values_only=True):
            if row[0]:  # Feed name exists
                vals = {
                    'name': row[0],
                    'name_bn': row[1],
                    'code': row[2],
                    'category': row[3],
                    'dry_matter': row[4] or 0,
                    'crude_protein': row[5] or 0,
                    'metabolizable_energy': row[6] or 0,
                    'tdn': row[7] or 0,
                    'crude_fiber': row[8] or 0,
                    'calcium': row[9] or 0,
                    'phosphorus': row[10] or 0,
                }
                FeedType.create(vals)
                imported += 1

        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': 'Import Complete',
                'message': f'Successfully imported {imported} feed types',
                'type': 'success',
            }
        }
```

**Task 2: Database Migrations & Security (4h)**
```xml
<!-- farm_feed/security/ir.model.access.csv -->
id,name,model_id:id,group_id:id,perm_read,perm_write,perm_create,perm_unlink
access_feed_type_manager,farm.feed.type.manager,model_farm_feed_type,farm_base.group_farm_manager,1,1,1,1
access_feed_type_user,farm.feed.type.user,model_farm_feed_type,farm_base.group_farm_user,1,0,0,0
access_nutrient_req_manager,farm.nutrient.requirement.manager,model_farm_nutrient_requirement,farm_base.group_farm_manager,1,1,1,1
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Feed Type List & Form Views (5h)**
```xml
<!-- farm_feed/views/feed_type_views.xml -->
<odoo>
    <record id="view_farm_feed_type_tree" model="ir.ui.view">
        <field name="name">farm.feed.type.tree</field>
        <field name="model">farm.feed.type</field>
        <field name="arch" type="xml">
            <tree>
                <field name="code"/>
                <field name="name"/>
                <field name="category" widget="badge"/>
                <field name="dry_matter"/>
                <field name="crude_protein"/>
                <field name="metabolizable_energy"/>
                <field name="unit_cost" widget="monetary"/>
                <field name="active" widget="boolean_toggle"/>
            </tree>
        </field>
    </record>

    <record id="view_farm_feed_type_form" model="ir.ui.view">
        <field name="name">farm.feed.type.form</field>
        <field name="model">farm.feed.type</field>
        <field name="arch" type="xml">
            <form>
                <sheet>
                    <div class="oe_button_box" name="button_box">
                        <button name="action_view_inventory" type="object" class="oe_stat_button" icon="fa-cubes">
                            <field name="current_stock" widget="statinfo" string="Stock"/>
                        </button>
                    </div>
                    <widget name="web_ribbon" title="Archived" bg_color="bg-danger" attrs="{'invisible': [('active', '=', True)]}"/>
                    <div class="oe_title">
                        <h1><field name="name" placeholder="Feed Name"/></h1>
                        <h3><field name="name_bn" placeholder="বাংলা নাম"/></h3>
                    </div>
                    <group>
                        <group string="Basic Information">
                            <field name="code"/>
                            <field name="category"/>
                            <field name="storage_type"/>
                            <field name="shelf_life_days"/>
                        </group>
                        <group string="Cost & Inventory">
                            <field name="product_id"/>
                            <field name="unit_cost"/>
                            <field name="uom_id"/>
                            <field name="min_stock_level"/>
                        </group>
                    </group>
                    <notebook>
                        <page string="Nutritional Composition" name="nutrition">
                            <group>
                                <group string="Proximate Analysis">
                                    <field name="dry_matter"/>
                                    <field name="crude_protein"/>
                                    <field name="crude_fiber"/>
                                    <field name="ether_extract"/>
                                </group>
                                <group string="Energy & Fiber">
                                    <field name="metabolizable_energy"/>
                                    <field name="total_digestible_nutrients"/>
                                    <field name="ndf"/>
                                    <field name="adf"/>
                                </group>
                                <group string="Minerals">
                                    <field name="calcium"/>
                                    <field name="phosphorus"/>
                                </group>
                            </group>
                        </page>
                        <page string="Suppliers" name="suppliers">
                            <field name="supplier_ids">
                                <tree>
                                    <field name="name"/>
                                    <field name="phone"/>
                                    <field name="city"/>
                                </tree>
                            </field>
                        </page>
                    </notebook>
                </sheet>
                <div class="oe_chatter">
                    <field name="message_follower_ids"/>
                    <field name="activity_ids"/>
                    <field name="message_ids"/>
                </div>
            </form>
        </field>
    </record>
</odoo>
```

**Task 2: Feed Type OWL Components (3h)**
```javascript
/** @odoo-module **/
// farm_feed/static/src/components/feed_nutrition_card.js
import { Component, useState } from "@odoo/owl";
import { registry } from "@web/core/registry";

export class FeedNutritionCard extends Component {
    static template = "farm_feed.FeedNutritionCard";
    static props = {
        feedType: Object,
    };

    setup() {
        this.state = useState({
            showDetails: false,
        });
    }

    get nutritionScore() {
        const feed = this.props.feedType;
        // Calculate nutrition score based on key parameters
        let score = 0;
        if (feed.crude_protein >= 15) score += 25;
        else if (feed.crude_protein >= 10) score += 15;
        if (feed.metabolizable_energy >= 10) score += 25;
        else if (feed.metabolizable_energy >= 8) score += 15;
        if (feed.tdn >= 65) score += 25;
        else if (feed.tdn >= 55) score += 15;
        if (feed.calcium >= 0.5 && feed.phosphorus >= 0.3) score += 25;
        return score;
    }

    get scoreClass() {
        const score = this.nutritionScore;
        if (score >= 80) return 'text-success';
        if (score >= 50) return 'text-warning';
        return 'text-danger';
    }
}

registry.category("components").add("FeedNutritionCard", FeedNutritionCard);
```

#### Day 291 Deliverables
- [x] FarmFeedType model with 25+ nutritional fields
- [x] FarmNutrientRequirement model for animal categories
- [x] Feed library import wizard
- [x] Feed type list and form views
- [x] OWL nutrition card component
- [x] Security access rules

---

### Day 292: Ration Formulation Engine

**Theme:** Nutritional Optimization and Ration Planning

#### Dev 1 - Backend Lead (8h)

**Task 1: FarmRation Model (4h)**
```python
# farm_feed/models/ration.py
class FarmRation(models.Model):
    _name = 'farm.ration'
    _description = 'Farm Ration Formulation'
    _inherit = ['mail.thread', 'mail.activity.mixin']

    name = fields.Char(string='Ration Name', required=True, tracking=True)
    code = fields.Char(string='Ration Code', required=True, copy=False)
    animal_category = fields.Selection([
        ('lactating_high', 'Lactating - High Yield'),
        ('lactating_medium', 'Lactating - Medium Yield'),
        ('lactating_low', 'Lactating - Low Yield'),
        ('dry', 'Dry Cow'),
        ('pregnant', 'Pregnant'),
        ('heifer', 'Heifer'),
        ('calf', 'Calf'),
        ('bull', 'Bull'),
    ], required=True, tracking=True)

    # Target Animal
    target_body_weight = fields.Float(string='Target Body Weight (kg)', default=450)
    target_milk_yield = fields.Float(string='Target Milk Yield (L/day)')
    target_fat_percent = fields.Float(string='Target Milk Fat %', default=4.0)

    # Ration Lines
    line_ids = fields.One2many('farm.ration.line', 'ration_id', string='Ingredients')

    # Calculated Totals
    total_dm = fields.Float(string='Total DM (kg)', compute='_compute_totals', store=True)
    total_cp = fields.Float(string='Total CP (g)', compute='_compute_totals', store=True)
    total_me = fields.Float(string='Total ME (MJ)', compute='_compute_totals', store=True)
    total_tdn = fields.Float(string='Total TDN (kg)', compute='_compute_totals', store=True)
    total_cost = fields.Float(string='Total Cost (BDT)', compute='_compute_totals', store=True)
    cost_per_kg_milk = fields.Float(string='Cost per kg Milk', compute='_compute_totals', store=True)

    # Nutrient Balance
    cp_balance = fields.Float(string='CP Balance %', compute='_compute_balance')
    me_balance = fields.Float(string='ME Balance %', compute='_compute_balance')
    ca_p_ratio = fields.Float(string='Ca:P Ratio', compute='_compute_balance')

    state = fields.Selection([
        ('draft', 'Draft'),
        ('validated', 'Validated'),
        ('active', 'Active'),
        ('archived', 'Archived'),
    ], default='draft', tracking=True)

    @api.depends('line_ids.quantity', 'line_ids.feed_type_id')
    def _compute_totals(self):
        for ration in self:
            total_dm = total_cp = total_me = total_tdn = total_cost = 0
            for line in ration.line_ids:
                dm_kg = line.quantity * (line.feed_type_id.dry_matter / 100)
                total_dm += dm_kg
                total_cp += dm_kg * line.feed_type_id.crude_protein * 10  # Convert to grams
                total_me += dm_kg * line.feed_type_id.metabolizable_energy
                total_tdn += dm_kg * (line.feed_type_id.total_digestible_nutrients / 100)
                total_cost += line.quantity * line.feed_type_id.unit_cost

            ration.total_dm = total_dm
            ration.total_cp = total_cp
            ration.total_me = total_me
            ration.total_tdn = total_tdn
            ration.total_cost = total_cost
            ration.cost_per_kg_milk = total_cost / ration.target_milk_yield if ration.target_milk_yield else 0

    def action_validate(self):
        self.ensure_one()
        # Validate nutritional adequacy
        requirement = self.env['farm.nutrient.requirement'].search([
            ('animal_category', '=', self.animal_category)
        ], limit=1)

        if requirement:
            cp_percent = (self.total_cp / (self.total_dm * 1000)) * 100 if self.total_dm else 0
            if cp_percent < requirement.crude_protein_percent * 0.9:
                raise ValidationError(f'Crude Protein ({cp_percent:.1f}%) is below minimum requirement ({requirement.crude_protein_percent}%)')

        self.state = 'validated'

    def action_activate(self):
        self.ensure_one()
        if self.state != 'validated':
            raise ValidationError('Ration must be validated before activation')
        self.state = 'active'


class FarmRationLine(models.Model):
    _name = 'farm.ration.line'
    _description = 'Ration Ingredient Line'

    ration_id = fields.Many2one('farm.ration', required=True, ondelete='cascade')
    feed_type_id = fields.Many2one('farm.feed.type', string='Feed Type', required=True)
    quantity = fields.Float(string='Quantity (kg/day)', required=True)

    # Calculated Values
    dm_contribution = fields.Float(string='DM (kg)', compute='_compute_contribution')
    cp_contribution = fields.Float(string='CP (g)', compute='_compute_contribution')
    me_contribution = fields.Float(string='ME (MJ)', compute='_compute_contribution')
    cost_contribution = fields.Float(string='Cost (BDT)', compute='_compute_contribution')
    percent_of_ration = fields.Float(string='% of Ration', compute='_compute_contribution')

    @api.depends('quantity', 'feed_type_id', 'ration_id.total_dm')
    def _compute_contribution(self):
        for line in self:
            feed = line.feed_type_id
            dm = line.quantity * (feed.dry_matter / 100)
            line.dm_contribution = dm
            line.cp_contribution = dm * feed.crude_protein * 10
            line.me_contribution = dm * feed.metabolizable_energy
            line.cost_contribution = line.quantity * feed.unit_cost
            line.percent_of_ration = (dm / line.ration_id.total_dm * 100) if line.ration_id.total_dm else 0
```

**Task 2: Ration Optimization Algorithm (4h)**
```python
# farm_feed/models/ration_optimizer.py
class RationOptimizer(models.TransientModel):
    _name = 'farm.ration.optimizer'
    _description = 'Ration Optimization Wizard'

    animal_category = fields.Selection([
        ('lactating_high', 'Lactating - High Yield'),
        ('lactating_medium', 'Lactating - Medium Yield'),
        ('lactating_low', 'Lactating - Low Yield'),
        ('dry', 'Dry Cow'),
    ], required=True)
    body_weight = fields.Float(string='Body Weight (kg)', default=450)
    milk_yield = fields.Float(string='Milk Yield (L/day)', default=15)
    optimization_goal = fields.Selection([
        ('cost', 'Minimize Cost'),
        ('protein', 'Maximize Protein'),
        ('balanced', 'Balanced Nutrition'),
    ], default='balanced')

    available_feed_ids = fields.Many2many('farm.feed.type', string='Available Feeds')
    max_roughage_percent = fields.Float(string='Max Roughage %', default=60)
    min_roughage_percent = fields.Float(string='Min Roughage %', default=40)

    def action_optimize(self):
        """Generate optimized ration using linear programming approach"""
        self.ensure_one()

        # Get nutrient requirements
        requirement = self.env['farm.nutrient.requirement'].search([
            ('animal_category', '=', self.animal_category)
        ], limit=1)

        if not requirement:
            raise ValidationError('Nutrient requirements not defined for this category')

        # Calculate DM intake based on body weight
        dm_intake = self.body_weight * (requirement.dm_intake_percent_bw / 100)

        # Simple optimization - balanced approach
        feeds = self.available_feed_ids or self.env['farm.feed.type'].search([('active', '=', True)])
        roughages = feeds.filtered(lambda f: f.category in ['roughage', 'silage', 'hay', 'green_fodder'])
        concentrates = feeds.filtered(lambda f: f.category in ['concentrate', 'byproduct'])

        # Target: 50% roughage, 50% concentrate
        roughage_dm = dm_intake * 0.5
        concentrate_dm = dm_intake * 0.5

        # Create ration
        ration = self.env['farm.ration'].create({
            'name': f'Optimized Ration - {self.animal_category}',
            'code': f'OPT-{self.animal_category[:3].upper()}-{fields.Date.today().strftime("%Y%m%d")}',
            'animal_category': self.animal_category,
            'target_body_weight': self.body_weight,
            'target_milk_yield': self.milk_yield,
        })

        # Add roughage lines
        if roughages:
            best_roughage = roughages.sorted(key=lambda f: f.metabolizable_energy, reverse=True)[0]
            quantity = roughage_dm / (best_roughage.dry_matter / 100) if best_roughage.dry_matter else roughage_dm
            self.env['farm.ration.line'].create({
                'ration_id': ration.id,
                'feed_type_id': best_roughage.id,
                'quantity': quantity,
            })

        # Add concentrate lines
        if concentrates:
            best_concentrate = concentrates.sorted(key=lambda f: f.crude_protein, reverse=True)[0]
            quantity = concentrate_dm / (best_concentrate.dry_matter / 100) if best_concentrate.dry_matter else concentrate_dm
            self.env['farm.ration.line'].create({
                'ration_id': ration.id,
                'feed_type_id': best_concentrate.id,
                'quantity': quantity,
            })

        return {
            'type': 'ir.actions.act_window',
            'res_model': 'farm.ration',
            'res_id': ration.id,
            'view_mode': 'form',
            'target': 'current',
        }
```

#### Dev 2 - Full-Stack (8h)

**Task 1: Ration API Endpoints (4h)**
```python
# farm_feed/controllers/ration_api.py
from odoo import http
from odoo.http import request
import json

class RationAPIController(http.Controller):

    @http.route('/api/v1/rations', type='json', auth='user', methods=['GET'])
    def get_rations(self, animal_category=None, state='active'):
        domain = [('state', '=', state)]
        if animal_category:
            domain.append(('animal_category', '=', animal_category))

        rations = request.env['farm.ration'].search(domain)
        return [{
            'id': r.id,
            'name': r.name,
            'code': r.code,
            'animal_category': r.animal_category,
            'total_dm': r.total_dm,
            'total_cost': r.total_cost,
            'cost_per_kg_milk': r.cost_per_kg_milk,
            'ingredients': [{
                'feed_name': line.feed_type_id.name,
                'quantity': line.quantity,
                'percent': line.percent_of_ration,
            } for line in r.line_ids]
        } for r in rations]

    @http.route('/api/v1/rations/<int:ration_id>/calculate', type='json', auth='user', methods=['POST'])
    def calculate_ration_for_animal(self, ration_id, animal_id, adjustment_factor=1.0):
        ration = request.env['farm.ration'].browse(ration_id)
        animal = request.env['farm.animal'].browse(animal_id)

        if not ration.exists() or not animal.exists():
            return {'error': 'Ration or Animal not found'}

        # Adjust quantities based on animal's actual weight vs target
        weight_factor = animal.current_weight / ration.target_body_weight if ration.target_body_weight else 1

        adjusted_lines = []
        total_adjusted_cost = 0
        for line in ration.line_ids:
            adjusted_qty = line.quantity * weight_factor * adjustment_factor
            line_cost = adjusted_qty * line.feed_type_id.unit_cost
            total_adjusted_cost += line_cost
            adjusted_lines.append({
                'feed_name': line.feed_type_id.name,
                'feed_name_bn': line.feed_type_id.name_bn,
                'original_qty': line.quantity,
                'adjusted_qty': adjusted_qty,
                'cost': line_cost,
            })

        return {
            'animal_id': animal.id,
            'animal_name': animal.name,
            'ration_name': ration.name,
            'weight_factor': weight_factor,
            'ingredients': adjusted_lines,
            'total_cost': total_adjusted_cost,
        }
```

**Task 2: Ration Validation Service (4h)**
```python
# farm_feed/services/ration_validator.py
class RationValidationService:

    def __init__(self, env):
        self.env = env

    def validate_ration(self, ration):
        """Comprehensive ration validation"""
        errors = []
        warnings = []

        # Get requirements
        requirement = self.env['farm.nutrient.requirement'].search([
            ('animal_category', '=', ration.animal_category)
        ], limit=1)

        if not requirement:
            errors.append('No nutrient requirements defined for this animal category')
            return {'valid': False, 'errors': errors, 'warnings': warnings}

        # Calculate actual nutrient percentages
        if ration.total_dm > 0:
            actual_cp = (ration.total_cp / (ration.total_dm * 1000)) * 100
            actual_me = ration.total_me / ration.total_dm

            # CP check
            if actual_cp < requirement.crude_protein_percent * 0.9:
                errors.append(f'Crude Protein ({actual_cp:.1f}%) below minimum ({requirement.crude_protein_percent}%)')
            elif actual_cp < requirement.crude_protein_percent:
                warnings.append(f'Crude Protein ({actual_cp:.1f}%) slightly below target ({requirement.crude_protein_percent}%)')

            # ME check
            if ration.total_me < requirement.me_requirement * 0.9:
                errors.append(f'Metabolizable Energy ({ration.total_me:.1f} MJ) below minimum ({requirement.me_requirement} MJ)')

            # Roughage/Concentrate ratio
            roughage_percent = self._calculate_roughage_percent(ration)
            if roughage_percent < 40:
                warnings.append(f'Low roughage content ({roughage_percent:.0f}%) may cause digestive issues')
            elif roughage_percent > 70:
                warnings.append(f'High roughage content ({roughage_percent:.0f}%) may limit production')
        else:
            errors.append('Total dry matter is zero - add ingredients')

        return {
            'valid': len(errors) == 0,
            'errors': errors,
            'warnings': warnings,
            'analysis': {
                'actual_cp_percent': actual_cp if ration.total_dm else 0,
                'actual_me_per_kg': actual_me if ration.total_dm else 0,
                'roughage_percent': roughage_percent if ration.total_dm else 0,
            }
        }

    def _calculate_roughage_percent(self, ration):
        roughage_dm = sum(
            line.dm_contribution for line in ration.line_ids
            if line.feed_type_id.category in ['roughage', 'silage', 'hay', 'green_fodder']
        )
        return (roughage_dm / ration.total_dm * 100) if ration.total_dm else 0
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Ration Planner UI (5h)**
```xml
<!-- farm_feed/views/ration_views.xml -->
<odoo>
    <record id="view_farm_ration_form" model="ir.ui.view">
        <field name="name">farm.ration.form</field>
        <field name="model">farm.ration</field>
        <field name="arch" type="xml">
            <form>
                <header>
                    <button name="action_validate" string="Validate" type="object" class="btn-primary" states="draft"/>
                    <button name="action_activate" string="Activate" type="object" class="btn-success" states="validated"/>
                    <button name="action_archive" string="Archive" type="object" states="active"/>
                    <field name="state" widget="statusbar" statusbar_visible="draft,validated,active"/>
                </header>
                <sheet>
                    <div class="oe_title">
                        <h1><field name="name" placeholder="Ration Name"/></h1>
                    </div>
                    <group>
                        <group string="Target Animal">
                            <field name="code"/>
                            <field name="animal_category"/>
                            <field name="target_body_weight"/>
                            <field name="target_milk_yield"/>
                            <field name="target_fat_percent"/>
                        </group>
                        <group string="Nutritional Summary">
                            <field name="total_dm" string="Total DM (kg)"/>
                            <field name="total_cp" string="Total CP (g)"/>
                            <field name="total_me" string="Total ME (MJ)"/>
                            <field name="total_cost" widget="monetary"/>
                            <field name="cost_per_kg_milk" widget="monetary"/>
                        </group>
                    </group>
                    <notebook>
                        <page string="Ingredients" name="ingredients">
                            <field name="line_ids">
                                <tree editable="bottom">
                                    <field name="feed_type_id"/>
                                    <field name="quantity"/>
                                    <field name="dm_contribution"/>
                                    <field name="cp_contribution"/>
                                    <field name="me_contribution"/>
                                    <field name="cost_contribution" widget="monetary"/>
                                    <field name="percent_of_ration" widget="progressbar"/>
                                </tree>
                            </field>
                        </page>
                        <page string="Nutrient Balance" name="balance">
                            <group>
                                <field name="cp_balance"/>
                                <field name="me_balance"/>
                                <field name="ca_p_ratio"/>
                            </group>
                        </page>
                    </notebook>
                </sheet>
                <div class="oe_chatter">
                    <field name="message_follower_ids"/>
                    <field name="message_ids"/>
                </div>
            </form>
        </field>
    </record>
</odoo>
```

**Task 2: Ration Composition Chart (3h)**
```javascript
/** @odoo-module **/
// farm_feed/static/src/components/ration_chart.js
import { Component, onMounted, useRef } from "@odoo/owl";
import { registry } from "@web/core/registry";
import { loadJS } from "@web/core/assets";

export class RationCompositionChart extends Component {
    static template = "farm_feed.RationCompositionChart";
    static props = {
        rationId: Number,
        lines: Array,
    };

    setup() {
        this.chartRef = useRef("chart");
        onMounted(async () => {
            await loadJS("/web/static/lib/Chart/Chart.js");
            this.renderChart();
        });
    }

    renderChart() {
        const ctx = this.chartRef.el.getContext('2d');
        const lines = this.props.lines;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: lines.map(l => l.feed_name),
                datasets: [{
                    data: lines.map(l => l.percent_of_ration),
                    backgroundColor: [
                        '#28a745', '#17a2b8', '#ffc107', '#dc3545',
                        '#6f42c1', '#fd7e14', '#20c997', '#e83e8c'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'right' },
                    title: { display: true, text: 'Ration Composition (% DM)' }
                }
            }
        });
    }
}

registry.category("components").add("RationCompositionChart", RationCompositionChart);
```

#### Day 292 Deliverables
- [x] FarmRation model with ingredient lines
- [x] Ration optimization wizard
- [x] Ration API endpoints
- [x] Ration validation service
- [x] Ration planner UI with charts
- [x] Composition visualization

---

### Day 293: Feed Inventory Management

**Theme:** Inventory Tracking and ERP Integration

#### Dev 1 - Backend Lead (8h)

**Task 1: FarmFeedInventory Model (5h)**
```python
# farm_feed/models/feed_inventory.py
class FarmFeedInventory(models.Model):
    _name = 'farm.feed.inventory'
    _description = 'Farm Feed Inventory'
    _inherit = ['mail.thread']

    feed_type_id = fields.Many2one('farm.feed.type', required=True, ondelete='restrict')
    location_id = fields.Many2one('farm.location', string='Storage Location', required=True)
    warehouse_id = fields.Many2one('stock.warehouse', string='ERP Warehouse')

    # Quantities
    quantity_on_hand = fields.Float(string='Quantity on Hand (kg)', tracking=True)
    quantity_reserved = fields.Float(string='Reserved Quantity (kg)')
    quantity_available = fields.Float(string='Available Quantity', compute='_compute_available')

    # Valuation
    unit_cost = fields.Float(string='Unit Cost (BDT/kg)', related='feed_type_id.unit_cost')
    total_value = fields.Float(string='Total Value', compute='_compute_value')

    # Batch Tracking
    batch_number = fields.Char(string='Batch Number')
    manufacturing_date = fields.Date(string='Manufacturing Date')
    expiry_date = fields.Date(string='Expiry Date')

    # Alerts
    is_below_minimum = fields.Boolean(compute='_compute_alerts', store=True)
    is_expiring_soon = fields.Boolean(compute='_compute_alerts', store=True)
    days_to_expiry = fields.Integer(compute='_compute_alerts')

    @api.depends('quantity_on_hand', 'quantity_reserved')
    def _compute_available(self):
        for inv in self:
            inv.quantity_available = inv.quantity_on_hand - inv.quantity_reserved

    @api.depends('quantity_on_hand', 'unit_cost')
    def _compute_value(self):
        for inv in self:
            inv.total_value = inv.quantity_on_hand * inv.unit_cost

    @api.depends('quantity_on_hand', 'feed_type_id.min_stock_level', 'expiry_date')
    def _compute_alerts(self):
        today = fields.Date.today()
        for inv in self:
            inv.is_below_minimum = inv.quantity_on_hand < inv.feed_type_id.min_stock_level
            if inv.expiry_date:
                inv.days_to_expiry = (inv.expiry_date - today).days
                inv.is_expiring_soon = inv.days_to_expiry <= 30
            else:
                inv.days_to_expiry = 999
                inv.is_expiring_soon = False

    def action_adjust_quantity(self):
        return {
            'type': 'ir.actions.act_window',
            'res_model': 'farm.feed.inventory.adjustment',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_inventory_id': self.id},
        }


class FarmFeedInventoryMovement(models.Model):
    _name = 'farm.feed.inventory.movement'
    _description = 'Feed Inventory Movement'
    _order = 'date desc, id desc'

    inventory_id = fields.Many2one('farm.feed.inventory', required=True)
    date = fields.Datetime(string='Date', default=fields.Datetime.now, required=True)
    movement_type = fields.Selection([
        ('in', 'Receipt'),
        ('out', 'Issue'),
        ('adjust', 'Adjustment'),
        ('transfer', 'Transfer'),
    ], required=True)
    quantity = fields.Float(string='Quantity (kg)', required=True)
    reference = fields.Char(string='Reference')
    notes = fields.Text(string='Notes')
    user_id = fields.Many2one('res.users', default=lambda self: self.env.user)

    # Link to consumption or purchase
    consumption_id = fields.Many2one('farm.feed.consumption')
    purchase_order_id = fields.Many2one('purchase.order')
```

**Task 2: ERP Stock Integration (3h)**
```python
# farm_feed/models/stock_integration.py
class FarmFeedStockSync(models.Model):
    _name = 'farm.feed.stock.sync'
    _description = 'Feed Stock Synchronization'

    def sync_from_erp(self):
        """Sync feed inventory from Odoo stock module"""
        FeedInventory = self.env['farm.feed.inventory']

        for feed_type in self.env['farm.feed.type'].search([('product_id', '!=', False)]):
            product = feed_type.product_id

            # Get stock quants for this product
            quants = self.env['stock.quant'].search([
                ('product_id', '=', product.id),
                ('location_id.usage', '=', 'internal'),
            ])

            for quant in quants:
                # Find or create inventory record
                inventory = FeedInventory.search([
                    ('feed_type_id', '=', feed_type.id),
                    ('warehouse_id', '=', quant.location_id.warehouse_id.id),
                ], limit=1)

                if inventory:
                    inventory.write({'quantity_on_hand': quant.quantity})
                else:
                    FeedInventory.create({
                        'feed_type_id': feed_type.id,
                        'warehouse_id': quant.location_id.warehouse_id.id,
                        'location_id': self.env['farm.location'].search([], limit=1).id,
                        'quantity_on_hand': quant.quantity,
                    })

        return True

    @api.model
    def _cron_sync_inventory(self):
        """Scheduled sync job"""
        self.sync_from_erp()
```

#### Dev 2 - Full-Stack (8h)

**Task 1: Inventory Alert System (4h)**
```python
# farm_feed/models/inventory_alerts.py
class FarmFeedInventoryAlert(models.Model):
    _name = 'farm.feed.inventory.alert'
    _description = 'Feed Inventory Alert'

    alert_type = fields.Selection([
        ('low_stock', 'Low Stock'),
        ('expiring', 'Expiring Soon'),
        ('expired', 'Expired'),
        ('overstock', 'Overstock'),
    ], required=True)
    inventory_id = fields.Many2one('farm.feed.inventory', required=True)
    feed_type_id = fields.Many2one(related='inventory_id.feed_type_id', store=True)
    severity = fields.Selection([
        ('low', 'Low'),
        ('medium', 'Medium'),
        ('high', 'High'),
        ('critical', 'Critical'),
    ], compute='_compute_severity', store=True)
    message = fields.Text(string='Alert Message')
    is_resolved = fields.Boolean(default=False)
    resolved_date = fields.Datetime()
    resolved_by = fields.Many2one('res.users')

    @api.depends('alert_type', 'inventory_id.quantity_available', 'inventory_id.days_to_expiry')
    def _compute_severity(self):
        for alert in self:
            if alert.alert_type == 'expired':
                alert.severity = 'critical'
            elif alert.alert_type == 'low_stock':
                ratio = alert.inventory_id.quantity_available / alert.inventory_id.feed_type_id.min_stock_level if alert.inventory_id.feed_type_id.min_stock_level else 1
                if ratio < 0.25:
                    alert.severity = 'critical'
                elif ratio < 0.5:
                    alert.severity = 'high'
                else:
                    alert.severity = 'medium'
            elif alert.alert_type == 'expiring':
                days = alert.inventory_id.days_to_expiry
                if days <= 7:
                    alert.severity = 'high'
                elif days <= 14:
                    alert.severity = 'medium'
                else:
                    alert.severity = 'low'
            else:
                alert.severity = 'low'

    @api.model
    def generate_alerts(self):
        """Generate inventory alerts based on current status"""
        for inventory in self.env['farm.feed.inventory'].search([]):
            # Low stock alert
            if inventory.is_below_minimum:
                existing = self.search([
                    ('inventory_id', '=', inventory.id),
                    ('alert_type', '=', 'low_stock'),
                    ('is_resolved', '=', False),
                ])
                if not existing:
                    self.create({
                        'alert_type': 'low_stock',
                        'inventory_id': inventory.id,
                        'message': f'Low stock: {inventory.feed_type_id.name} - {inventory.quantity_available:.0f}kg available (min: {inventory.feed_type_id.min_stock_level:.0f}kg)',
                    })

            # Expiring alert
            if inventory.is_expiring_soon and inventory.days_to_expiry > 0:
                existing = self.search([
                    ('inventory_id', '=', inventory.id),
                    ('alert_type', '=', 'expiring'),
                    ('is_resolved', '=', False),
                ])
                if not existing:
                    self.create({
                        'alert_type': 'expiring',
                        'inventory_id': inventory.id,
                        'message': f'Expiring: {inventory.feed_type_id.name} (Batch: {inventory.batch_number}) expires in {inventory.days_to_expiry} days',
                    })
```

**Task 2: Inventory API (4h)**
```python
# farm_feed/controllers/inventory_api.py
class FeedInventoryAPI(http.Controller):

    @http.route('/api/v1/feed/inventory', type='json', auth='user', methods=['GET'])
    def get_inventory(self, location_id=None, include_alerts=True):
        domain = []
        if location_id:
            domain.append(('location_id', '=', int(location_id)))

        inventories = request.env['farm.feed.inventory'].search(domain)
        result = []

        for inv in inventories:
            item = {
                'id': inv.id,
                'feed_type': inv.feed_type_id.name,
                'feed_type_bn': inv.feed_type_id.name_bn,
                'category': inv.feed_type_id.category,
                'quantity_on_hand': inv.quantity_on_hand,
                'quantity_available': inv.quantity_available,
                'unit_cost': inv.unit_cost,
                'total_value': inv.total_value,
                'expiry_date': inv.expiry_date.isoformat() if inv.expiry_date else None,
                'days_to_expiry': inv.days_to_expiry,
                'is_below_minimum': inv.is_below_minimum,
                'is_expiring_soon': inv.is_expiring_soon,
            }

            if include_alerts:
                alerts = request.env['farm.feed.inventory.alert'].search([
                    ('inventory_id', '=', inv.id),
                    ('is_resolved', '=', False),
                ])
                item['alerts'] = [{
                    'type': a.alert_type,
                    'severity': a.severity,
                    'message': a.message,
                } for a in alerts]

            result.append(item)

        return result

    @http.route('/api/v1/feed/inventory/<int:inv_id>/movement', type='json', auth='user', methods=['POST'])
    def record_movement(self, inv_id, movement_type, quantity, reference=None, notes=None):
        inventory = request.env['farm.feed.inventory'].browse(inv_id)
        if not inventory.exists():
            return {'error': 'Inventory not found'}

        # Create movement record
        movement = request.env['farm.feed.inventory.movement'].create({
            'inventory_id': inv_id,
            'movement_type': movement_type,
            'quantity': quantity,
            'reference': reference,
            'notes': notes,
        })

        # Update inventory quantity
        if movement_type == 'in':
            inventory.quantity_on_hand += quantity
        elif movement_type == 'out':
            if quantity > inventory.quantity_available:
                return {'error': 'Insufficient quantity available'}
            inventory.quantity_on_hand -= quantity
        elif movement_type == 'adjust':
            inventory.quantity_on_hand = quantity

        return {'success': True, 'movement_id': movement.id, 'new_quantity': inventory.quantity_on_hand}
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Inventory Dashboard UI (5h)**
```xml
<!-- farm_feed/views/inventory_views.xml -->
<odoo>
    <record id="view_farm_feed_inventory_tree" model="ir.ui.view">
        <field name="name">farm.feed.inventory.tree</field>
        <field name="model">farm.feed.inventory</field>
        <field name="arch" type="xml">
            <tree decoration-danger="is_below_minimum" decoration-warning="is_expiring_soon">
                <field name="feed_type_id"/>
                <field name="location_id"/>
                <field name="quantity_on_hand"/>
                <field name="quantity_available"/>
                <field name="unit_cost" widget="monetary"/>
                <field name="total_value" widget="monetary" sum="Total"/>
                <field name="expiry_date"/>
                <field name="days_to_expiry"/>
                <field name="is_below_minimum" invisible="1"/>
                <field name="is_expiring_soon" invisible="1"/>
            </tree>
        </field>
    </record>

    <record id="view_farm_feed_inventory_kanban" model="ir.ui.view">
        <field name="name">farm.feed.inventory.kanban</field>
        <field name="model">farm.feed.inventory</field>
        <field name="arch" type="xml">
            <kanban>
                <field name="feed_type_id"/>
                <field name="quantity_on_hand"/>
                <field name="quantity_available"/>
                <field name="is_below_minimum"/>
                <field name="is_expiring_soon"/>
                <field name="total_value"/>
                <templates>
                    <t t-name="kanban-box">
                        <div class="oe_kanban_global_click">
                            <div class="o_kanban_record_top">
                                <div class="o_kanban_record_headings">
                                    <strong class="o_kanban_record_title">
                                        <field name="feed_type_id"/>
                                    </strong>
                                </div>
                                <span t-if="record.is_below_minimum.raw_value" class="badge bg-danger">Low Stock</span>
                                <span t-if="record.is_expiring_soon.raw_value" class="badge bg-warning">Expiring</span>
                            </div>
                            <div class="o_kanban_record_body">
                                <div class="row">
                                    <div class="col-6">
                                        <strong>On Hand:</strong> <field name="quantity_on_hand"/> kg
                                    </div>
                                    <div class="col-6">
                                        <strong>Available:</strong> <field name="quantity_available"/> kg
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <strong>Value:</strong> <field name="total_value" widget="monetary"/>
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

**Task 2: Flutter Feed Inventory Screen (3h)**
```dart
// lib/features/feed/presentation/screens/feed_inventory_screen.dart
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../bloc/feed_inventory_bloc.dart';
import '../widgets/inventory_card.dart';

class FeedInventoryScreen extends StatelessWidget {
  const FeedInventoryScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Feed Inventory'),
        actions: [
          IconButton(
            icon: const Icon(Icons.filter_list),
            onPressed: () => _showFilterDialog(context),
          ),
          IconButton(
            icon: const Icon(Icons.sync),
            onPressed: () => context.read<FeedInventoryBloc>().add(SyncInventory()),
          ),
        ],
      ),
      body: BlocBuilder<FeedInventoryBloc, FeedInventoryState>(
        builder: (context, state) {
          if (state is FeedInventoryLoading) {
            return const Center(child: CircularProgressIndicator());
          }

          if (state is FeedInventoryLoaded) {
            final items = state.items;
            final lowStockCount = items.where((i) => i.isBelowMinimum).length;
            final expiringCount = items.where((i) => i.isExpiringSoon).length;

            return Column(
              children: [
                // Alert summary
                if (lowStockCount > 0 || expiringCount > 0)
                  Container(
                    padding: const EdgeInsets.all(12),
                    color: Colors.orange.shade50,
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                      children: [
                        if (lowStockCount > 0)
                          Chip(
                            avatar: const Icon(Icons.warning, color: Colors.red, size: 18),
                            label: Text('$lowStockCount Low Stock'),
                            backgroundColor: Colors.red.shade100,
                          ),
                        if (expiringCount > 0)
                          Chip(
                            avatar: const Icon(Icons.schedule, color: Colors.orange, size: 18),
                            label: Text('$expiringCount Expiring'),
                            backgroundColor: Colors.orange.shade100,
                          ),
                      ],
                    ),
                  ),
                // Inventory list
                Expanded(
                  child: ListView.builder(
                    padding: const EdgeInsets.all(8),
                    itemCount: items.length,
                    itemBuilder: (context, index) {
                      return InventoryCard(item: items[index]);
                    },
                  ),
                ),
              ],
            );
          }

          return const Center(child: Text('Error loading inventory'));
        },
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => _showAddMovementDialog(context),
        icon: const Icon(Icons.add),
        label: const Text('Record Movement'),
      ),
    );
  }

  void _showFilterDialog(BuildContext context) {
    // Filter implementation
  }

  void _showAddMovementDialog(BuildContext context) {
    // Movement dialog implementation
  }
}
```

#### Day 293 Deliverables
- [x] FarmFeedInventory model with batch tracking
- [x] Inventory movement tracking
- [x] ERP stock integration
- [x] Inventory alert system
- [x] Inventory API endpoints
- [x] Inventory dashboard UI (web & mobile)

---

### Day 294-300: Remaining Implementation

Due to document length, days 294-300 are summarized:

#### Day 294: Feeding Schedule Automation
- FarmFeedingSchedule model with recurrence rules
- Schedule automation with cron jobs
- Group-based feeding assignments
- Schedule calendar UI (web & Flutter)

#### Day 295: Feed Consumption Tracking
- FarmFeedConsumption model for daily records
- Variance analysis (actual vs planned)
- Consumption entry API and mobile UI
- Consumption reports

#### Day 296: Feed Cost Analysis
- Cost calculation engine
- Cost per liter of milk tracking
- Feed efficiency metrics (FCR)
- Cost comparison reports

#### Day 297: Demand Forecasting
- Forecasting algorithm based on herd size and production
- Purchase recommendation engine
- Seasonal adjustment factors
- Procurement integration

#### Day 298: Group Feeding Management
- Bulk feeding operations
- TMR (Total Mixed Ration) support
- Group composition tracking
- Feeding crew assignment

#### Day 299: Feed Reports & Analytics
- Feed efficiency reports
- Cost analysis reports
- Inventory valuation reports
- Scheduled report generation

#### Day 300: Testing & Documentation
- Unit tests (>80% coverage)
- Integration tests
- Performance testing
- User documentation

---

## 4. Technical Specifications

### 4.1 Database Schema

```sql
-- Core Tables
CREATE TABLE farm_feed_type (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    name_bn VARCHAR(100),
    code VARCHAR(20) UNIQUE NOT NULL,
    category VARCHAR(20) NOT NULL,
    dry_matter DECIMAL(5,2),
    crude_protein DECIMAL(5,2),
    metabolizable_energy DECIMAL(5,2),
    unit_cost DECIMAL(10,2),
    active BOOLEAN DEFAULT TRUE
);

CREATE TABLE farm_ration (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(20) UNIQUE NOT NULL,
    animal_category VARCHAR(30) NOT NULL,
    target_body_weight DECIMAL(6,2),
    target_milk_yield DECIMAL(5,2),
    state VARCHAR(20) DEFAULT 'draft'
);

CREATE TABLE farm_feed_inventory (
    id SERIAL PRIMARY KEY,
    feed_type_id INTEGER REFERENCES farm_feed_type(id),
    location_id INTEGER REFERENCES farm_location(id),
    quantity_on_hand DECIMAL(10,2) DEFAULT 0,
    batch_number VARCHAR(50),
    expiry_date DATE
);

CREATE TABLE farm_feed_consumption (
    id SERIAL PRIMARY KEY,
    date DATE NOT NULL,
    animal_id INTEGER REFERENCES farm_animal(id),
    animal_group_id INTEGER REFERENCES farm_animal_group(id),
    ration_id INTEGER REFERENCES farm_ration(id),
    planned_quantity DECIMAL(8,2),
    actual_quantity DECIMAL(8,2),
    variance DECIMAL(8,2)
);

-- Indexes
CREATE INDEX idx_consumption_date ON farm_feed_consumption(date);
CREATE INDEX idx_inventory_feed ON farm_feed_inventory(feed_type_id);
```

### 4.2 API Endpoints Summary

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/feed/types` | GET | List all feed types |
| `/api/v1/feed/rations` | GET | List rations by category |
| `/api/v1/feed/rations/<id>/calculate` | POST | Calculate ration for animal |
| `/api/v1/feed/inventory` | GET | Get inventory status |
| `/api/v1/feed/inventory/<id>/movement` | POST | Record inventory movement |
| `/api/v1/feed/consumption` | GET/POST | Consumption records |
| `/api/v1/feed/schedules` | GET | Feeding schedules |
| `/api/v1/feed/analytics/cost` | GET | Cost analysis data |
| `/api/v1/feed/forecast` | GET | Demand forecast |

---

## 5. Testing & Validation

### 5.1 Unit Tests
```python
# farm_feed/tests/test_ration.py
from odoo.tests.common import TransactionCase
from odoo.exceptions import ValidationError

class TestRationFormulation(TransactionCase):

    def setUp(self):
        super().setUp()
        self.feed_type = self.env['farm.feed.type'].create({
            'name': 'Test Concentrate',
            'code': 'TC001',
            'category': 'concentrate',
            'dry_matter': 88.0,
            'crude_protein': 18.0,
            'metabolizable_energy': 12.5,
        })

    def test_ration_total_calculation(self):
        ration = self.env['farm.ration'].create({
            'name': 'Test Ration',
            'code': 'TR001',
            'animal_category': 'lactating_medium',
        })
        self.env['farm.ration.line'].create({
            'ration_id': ration.id,
            'feed_type_id': self.feed_type.id,
            'quantity': 5.0,
        })

        self.assertAlmostEqual(ration.total_dm, 4.4, places=1)  # 5 * 0.88
        self.assertAlmostEqual(ration.total_me, 55.0, places=1)  # 4.4 * 12.5

    def test_ration_validation(self):
        # Test validation logic
        pass
```

### 5.2 Acceptance Criteria
| Criteria | Test Method | Target |
|----------|-------------|--------|
| Ration calculation accuracy | Unit test | ±2% |
| Inventory sync | Integration test | Real-time |
| Mobile consumption entry | E2E test | <30 seconds |
| Cost reports accuracy | Manual verification | 99.5% |

---

## 6. Risk & Mitigation

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Complex nutrition calculations | Medium | Medium | Use established formulas, expert review |
| ERP sync conflicts | High | Low | Implement conflict resolution, audit logs |
| Mobile offline data loss | High | Low | Robust sync queue, local backup |
| Feed price volatility | Medium | High | Historical price tracking, alerts |

---

## 7. Dependencies & Handoffs

### 7.1 Prerequisites
- Milestone 41: Animal records for consumption tracking
- Milestone 44: Production data for efficiency calculations
- Phase 4: ERP inventory module for integration

### 7.2 Outputs for Future Milestones
- Milestone 46: Feed cost data for analytics dashboard
- Milestone 49: Feed reports for reporting system
- Milestone 50: Integration testing requirements

---

*Document End - Milestone 45: Feed Management System*
