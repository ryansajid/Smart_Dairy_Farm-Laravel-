# Milestone 3: Custom Farm Management Module (`smart_farm_mgmt`)

## Smart Dairy Digital Smart Portal + ERP — Phase 1: Foundation

| Field            | Detail                                                        |
|------------------|---------------------------------------------------------------|
| **Milestone**    | 3 of 10                                                       |
| **Title**        | Custom Farm Management Module Development                      |
| **Phase**        | Phase 1 — Foundation (Part A: Infrastructure & Core Setup)     |
| **Days**         | Days 21–30 (of 100)                                           |
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

Design, develop, and deploy the **`smart_farm_mgmt`** custom Odoo 19 CE module — the core farm-operations module that manages animal records, health tracking, milk production recording, breeding management, feed & nutrition planning, and equipment maintenance for Smart Dairy Ltd's 255-head cattle operation producing 900 L/day.

### 1.2 Objectives

1. Scaffold the `smart_farm_mgmt` module with proper Odoo 19 manifest, directory structure, and dependency declarations
2. Implement the **Animal Management** subsystem (`farm.animal`, `farm.breed`, `farm.farm`) with lifecycle state machine
3. Build **Health Record Management** (`farm.health.record`) with vaccination schedules, treatment logs, and veterinary tracking
4. Create **Milk Production Recording** (`farm.milk.production`) with quality metrics (Fat%, SNF%, Protein%, SCC) and grading
5. Develop **Breeding Management** (`farm.breeding.record`) with heat detection, AI records, pregnancy tracking, and gestation calculator
6. Implement **Feed & Nutrition Management** (`farm.feed.record`, `farm.feed.ration`) with ration planning and cost analysis
7. Build **Equipment & Asset Management** (`farm.equipment`) with maintenance schedules and depreciation tracking
8. Create **OWL Dashboard Components** (MilkProductionChart, AnimalHealthSummary, BreedingCalendar, FeedCostAnalysis)
9. Write comprehensive unit tests and integration tests for all business logic
10. Conduct Milestone 3 review with stakeholder demo of full farm management workflow

### 1.3 Key Deliverables

| # | Deliverable | Owner | Day |
|---|-------------|-------|-----|
| D3.1 | Module scaffold with `__manifest__.py` | Dev 1 | 21 |
| D3.2 | `farm.animal`, `farm.breed`, `farm.farm` models | Dev 1 | 21-22 |
| D3.3 | Animal management views (tree, form, kanban) | Dev 3 | 22 |
| D3.4 | `farm.health.record` model + views | Dev 1, Dev 3 | 23 |
| D3.5 | `farm.milk.production` model + quality grading | Dev 1 | 24 |
| D3.6 | `farm.breeding.record` model + gestation calculator | Dev 1 | 25 |
| D3.7 | `farm.feed.record`, `farm.feed.ration` models | Dev 1 | 26 |
| D3.8 | `farm.equipment` model + depreciation | Dev 1 | 27 |
| D3.9 | OWL Farm Dashboard with 4 widget components | Dev 3 | 28 |
| D3.10 | Security access rules (`ir.model.access.csv`) | Dev 2 | 22-23 |
| D3.11 | Full test suite (unit + integration) | Dev 2 | 29 |
| D3.12 | Milestone review & stakeholder demo | All | 30 |

### 1.4 Success Criteria

- [ ] All 9 models pass `odoo --test-enable` without errors
- [ ] ≥ 80% code coverage on business logic (computed fields, constraints, workflows)
- [ ] Animal lifecycle state machine transitions verified (active → dry → pregnant → sick → sold → deceased)
- [ ] Milk quality grading algorithm produces correct grades for known test data
- [ ] Gestation calculator accurate to ± 1 day against veterinary reference tables
- [ ] All OWL dashboard components render with sample data
- [ ] Feed cost per animal per day computes correctly with multi-ingredient rations
- [ ] RBAC enforced: Farm Manager full access, Vet read/write health only, Milk Collector write milk only

### 1.5 Prerequisites

- **From M1:** Docker environment running, Git repo with GitFlow, CI/CD pipeline active
- **From M2:** Core Odoo modules (Sales, Inventory, Accounting, HR) installed and configured

---

## 2. Requirement Traceability Matrix

### BRD Module 4 — Smart Farm Management (26 Requirements)

| BRD Req ID | Requirement Description | SRS Trace | Day | Owner |
|-----------|------------------------|-----------|-----|-------|
| BRD-FM-001 | Animal registration with unique ID | SRS-4.1.1 | 21 | Dev 1 |
| BRD-FM-002 | RFID tag assignment and tracking | SRS-4.1.2 | 21-22 | Dev 1 |
| BRD-FM-003 | Breed management database | SRS-4.1.3 | 21 | Dev 1 |
| BRD-FM-004 | Animal lifecycle state tracking | SRS-4.1.4 | 22 | Dev 1 |
| BRD-FM-005 | Parentage and lineage tracking | SRS-4.1.5 | 21 | Dev 1 |
| BRD-FM-006 | Animal photo and document storage | SRS-4.1.6 | 22 | Dev 3 |
| BRD-FM-007 | Health checkup recording | SRS-4.2.1 | 23 | Dev 1 |
| BRD-FM-008 | Vaccination schedule management | SRS-4.2.2 | 23 | Dev 1 |
| BRD-FM-009 | Treatment and medication logging | SRS-4.2.3 | 23 | Dev 1 |
| BRD-FM-010 | Veterinary visit tracking | SRS-4.2.4 | 23 | Dev 1 |
| BRD-FM-011 | Daily milk yield recording | SRS-4.3.1 | 24 | Dev 1 |
| BRD-FM-012 | Milk quality metrics (Fat, SNF, Protein, SCC) | SRS-4.3.2 | 24 | Dev 1 |
| BRD-FM-013 | Quality grade classification | SRS-4.3.3 | 24 | Dev 1 |
| BRD-FM-014 | Morning/evening session tracking | SRS-4.3.4 | 24 | Dev 1 |
| BRD-FM-015 | Milk production analytics | SRS-4.3.5 | 28 | Dev 3 |
| BRD-FM-016 | Heat detection recording | SRS-4.4.1 | 25 | Dev 1 |
| BRD-FM-017 | Artificial insemination logging | SRS-4.4.2 | 25 | Dev 1 |
| BRD-FM-018 | Pregnancy confirmation tracking | SRS-4.4.3 | 25 | Dev 1 |
| BRD-FM-019 | Gestation period calculator | SRS-4.4.4 | 25 | Dev 1 |
| BRD-FM-020 | Calving event recording | SRS-4.4.5 | 25 | Dev 1 |
| BRD-FM-021 | Feed ration planning | SRS-4.5.1 | 26 | Dev 1 |
| BRD-FM-022 | Feed inventory integration | SRS-4.5.2 | 26 | Dev 2 |
| BRD-FM-023 | Feed cost per animal per day | SRS-4.5.3 | 26 | Dev 1 |
| BRD-FM-024 | Equipment registration and tracking | SRS-4.6.1 | 27 | Dev 1 |
| BRD-FM-025 | Maintenance schedule management | SRS-4.6.2 | 27 | Dev 1 |
| BRD-FM-026 | Farm dashboard and KPI reporting | SRS-4.7.1 | 28 | Dev 3 |

---

## 3. Day-by-Day Breakdown

---

### Day 21 — Module Scaffolding & Core Data Models

**Objective:** Create the `smart_farm_mgmt` module skeleton and implement the foundational models (`farm.animal`, `farm.breed`, `farm.farm`).

#### Dev 1 — Backend Lead (8h)

**Task 21.1.1: Module scaffold and manifest (2h)**

```
custom_addons/
└── smart_farm_mgmt/
    ├── __init__.py
    ├── __manifest__.py
    ├── models/
    │   ├── __init__.py
    │   ├── farm_animal.py
    │   ├── farm_breed.py
    │   ├── farm_farm.py
    │   ├── farm_health_record.py
    │   ├── farm_milk_production.py
    │   ├── farm_breeding_record.py
    │   ├── farm_feed.py
    │   └── farm_equipment.py
    ├── views/
    │   ├── farm_animal_views.xml
    │   ├── farm_breed_views.xml
    │   ├── farm_farm_views.xml
    │   ├── farm_health_views.xml
    │   ├── farm_milk_views.xml
    │   ├── farm_breeding_views.xml
    │   ├── farm_feed_views.xml
    │   ├── farm_equipment_views.xml
    │   └── farm_menu.xml
    ├── security/
    │   ├── ir.model.access.csv
    │   └── farm_security.xml
    ├── data/
    │   ├── farm_breed_data.xml
    │   └── farm_sequence_data.xml
    ├── static/
    │   └── src/
    │       ├── js/
    │       │   └── farm_dashboard.js
    │       ├── xml/
    │       │   └── farm_dashboard.xml
    │       └── scss/
    │           └── farm_dashboard.scss
    ├── tests/
    │   ├── __init__.py
    │   ├── test_farm_animal.py
    │   ├── test_milk_production.py
    │   ├── test_breeding.py
    │   └── test_feed.py
    └── report/
        └── farm_report_templates.xml
```

**`__manifest__.py`:**

```python
# -*- coding: utf-8 -*-
{
    'name': 'Smart Farm Management',
    'version': '19.0.1.0.0',
    'category': 'Agriculture/Farm Management',
    'summary': 'Complete farm management for Smart Dairy Ltd — animals, health, '
               'milk production, breeding, feed, and equipment.',
    'description': """
Smart Farm Management Module (smart_farm_mgmt)
================================================
Core farm operations module for Smart Dairy Ltd:
- Animal registration with RFID and lifecycle management (255 cattle)
- Health records: checkups, vaccinations, treatments, surgeries
- Milk production recording with quality metrics (900 L/day target)
- Breeding management: heat detection, AI, pregnancy tracking
- Feed & nutrition: ration planning, inventory, cost analysis
- Equipment & assets: maintenance schedules, depreciation
- Farm dashboard with real-time KPIs (OWL components)

Part of the Smart Dairy Digital Smart Portal + ERP system.
    """,
    'author': 'Smart Dairy Ltd',
    'website': 'https://smartdairy.com.bd',
    'license': 'LGPL-3',
    'depends': [
        'base',
        'mail',
        'product',
        'stock',
        'account',
        'hr',
        'web',
    ],
    'data': [
        # Security
        'security/farm_security.xml',
        'security/ir.model.access.csv',
        # Data
        'data/farm_breed_data.xml',
        'data/farm_sequence_data.xml',
        # Views
        'views/farm_animal_views.xml',
        'views/farm_breed_views.xml',
        'views/farm_farm_views.xml',
        'views/farm_health_views.xml',
        'views/farm_milk_views.xml',
        'views/farm_breeding_views.xml',
        'views/farm_feed_views.xml',
        'views/farm_equipment_views.xml',
        'views/farm_menu.xml',
    ],
    'assets': {
        'web.assets_backend': [
            'smart_farm_mgmt/static/src/js/farm_dashboard.js',
            'smart_farm_mgmt/static/src/xml/farm_dashboard.xml',
            'smart_farm_mgmt/static/src/scss/farm_dashboard.scss',
        ],
    },
    'demo': [],
    'installable': True,
    'application': True,
    'auto_install': False,
    'sequence': 10,
}
```

**Task 21.1.2: `farm.breed` model (1h)**

```python
# models/farm_breed.py
from odoo import models, fields, api

class FarmBreed(models.Model):
    _name = 'farm.breed'
    _description = 'Animal Breed'
    _order = 'name'

    name = fields.Char(string='Breed Name', required=True, index=True)
    species = fields.Selection([
        ('cattle', 'Cattle'),
        ('buffalo', 'Buffalo'),
        ('goat', 'Goat'),
    ], string='Species', required=True, default='cattle')
    origin_country = fields.Char(string='Origin Country')
    avg_milk_yield = fields.Float(
        string='Avg Daily Milk Yield (L)',
        digits=(8, 2),
        help='Average daily milk yield in liters for this breed'
    )
    avg_fat_percentage = fields.Float(
        string='Avg Fat %', digits=(4, 2),
        help='Average milk fat percentage for this breed'
    )
    avg_gestation_days = fields.Integer(
        string='Avg Gestation (days)', default=283,
        help='Average gestation period in days'
    )
    description = fields.Text(string='Description')
    image = fields.Binary(string='Breed Image')
    active = fields.Boolean(default=True)
    animal_count = fields.Integer(
        string='Animal Count', compute='_compute_animal_count'
    )

    @api.depends()
    def _compute_animal_count(self):
        animal_data = self.env['farm.animal'].read_group(
            [('breed_id', 'in', self.ids)],
            ['breed_id'], ['breed_id']
        )
        mapped = {d['breed_id'][0]: d['breed_id_count'] for d in animal_data}
        for rec in self:
            rec.animal_count = mapped.get(rec.id, 0)
```

**Task 21.1.3: `farm.farm` model (1h)**

```python
# models/farm_farm.py
from odoo import models, fields, api

class FarmFarm(models.Model):
    _name = 'farm.farm'
    _description = 'Farm / Location'
    _inherit = ['mail.thread', 'mail.activity.mixin']

    name = fields.Char(string='Farm Name', required=True, tracking=True)
    code = fields.Char(string='Farm Code', required=True, copy=False)
    location = fields.Char(string='Location / Address', tracking=True)
    latitude = fields.Float(string='Latitude', digits=(10, 7))
    longitude = fields.Float(string='Longitude', digits=(10, 7))
    area_acres = fields.Float(string='Area (Acres)', digits=(8, 2))
    manager_id = fields.Many2one(
        'res.users', string='Farm Manager', tracking=True
    )
    company_id = fields.Many2one(
        'res.company', string='Company',
        default=lambda self: self.env.company
    )
    animal_ids = fields.One2many('farm.animal', 'farm_id', string='Animals')
    animal_count = fields.Integer(
        string='Total Animals', compute='_compute_animal_count', store=True
    )
    active_animal_count = fields.Integer(
        string='Active Animals', compute='_compute_animal_count', store=True
    )
    active = fields.Boolean(default=True)
    notes = fields.Html(string='Notes')

    @api.depends('animal_ids', 'animal_ids.status')
    def _compute_animal_count(self):
        for farm in self:
            animals = farm.animal_ids
            farm.animal_count = len(animals)
            farm.active_animal_count = len(
                animals.filtered(lambda a: a.status in ('active', 'pregnant', 'dry'))
            )
```

**Task 21.1.4: `farm.animal` model — core implementation (4h)**

```python
# models/farm_animal.py
from odoo import models, fields, api, _
from odoo.exceptions import ValidationError
from dateutil.relativedelta import relativedelta
import datetime

class FarmAnimal(models.Model):
    _name = 'farm.animal'
    _description = 'Farm Animal'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'tag_number'
    _rec_name = 'display_name'

    # --- Identification ---
    name = fields.Char(string='Animal Name', required=True, tracking=True)
    tag_number = fields.Char(
        string='Tag Number', required=True, copy=False, index=True,
        default=lambda self: _('New')
    )
    rfid_tag = fields.Char(
        string='RFID Tag', copy=False, index=True,
        help='Electronic RFID ear tag identifier'
    )
    display_name = fields.Char(compute='_compute_display_name', store=True)

    # --- Classification ---
    breed_id = fields.Many2one('farm.breed', string='Breed', required=True, tracking=True)
    species = fields.Selection(related='breed_id.species', store=True, readonly=True)
    gender = fields.Selection([
        ('female', 'Female (Cow)'),
        ('male', 'Male (Bull)'),
    ], string='Gender', required=True, tracking=True)
    color = fields.Char(string='Color / Markings')
    photo = fields.Binary(string='Photo', attachment=True)

    # --- Dates & Age ---
    date_of_birth = fields.Date(string='Date of Birth', required=True, tracking=True)
    age_years = fields.Float(
        string='Age (Years)', compute='_compute_age', store=True, digits=(4, 1)
    )
    age_display = fields.Char(string='Age', compute='_compute_age', store=True)

    # --- Status & Lifecycle ---
    status = fields.Selection([
        ('active', 'Active / Lactating'),
        ('dry', 'Dry'),
        ('pregnant', 'Pregnant'),
        ('sick', 'Sick'),
        ('heifer', 'Heifer (Young)'),
        ('calf', 'Calf'),
        ('sold', 'Sold'),
        ('deceased', 'Deceased'),
    ], string='Status', default='active', required=True, tracking=True, index=True)
    status_date = fields.Date(
        string='Status Since', default=fields.Date.today, tracking=True
    )

    # --- Weight ---
    weight = fields.Float(string='Current Weight (kg)', digits=(8, 2))
    birth_weight = fields.Float(string='Birth Weight (kg)', digits=(6, 2))

    # --- Farm & Location ---
    farm_id = fields.Many2one('farm.farm', string='Farm', required=True, tracking=True)

    # --- Parentage / Lineage ---
    parent_sire_id = fields.Many2one(
        'farm.animal', string='Sire (Father)',
        domain=[('gender', '=', 'male')]
    )
    parent_dam_id = fields.Many2one(
        'farm.animal', string='Dam (Mother)',
        domain=[('gender', '=', 'female')]
    )
    offspring_ids = fields.One2many(
        'farm.animal', compute='_compute_offspring'
    )

    # --- Milk Production ---
    milk_production_ids = fields.One2many(
        'farm.milk.production', 'animal_id', string='Milk Records'
    )
    total_milk_lifetime = fields.Float(
        string='Lifetime Milk (L)', compute='_compute_milk_stats', store=True
    )
    avg_daily_milk = fields.Float(
        string='Avg Daily Milk (L)', compute='_compute_milk_stats', store=True,
        digits=(8, 2)
    )
    days_in_milk = fields.Integer(
        string='Days in Milk (DIM)', compute='_compute_days_in_milk'
    )
    lactation_number = fields.Integer(
        string='Lactation Number', compute='_compute_lactation_number'
    )

    # --- Related Records ---
    health_record_ids = fields.One2many(
        'farm.health.record', 'animal_id', string='Health Records'
    )
    breeding_record_ids = fields.One2many(
        'farm.breeding.record', 'animal_id', string='Breeding Records'
    )
    feed_record_ids = fields.One2many(
        'farm.feed.record', 'animal_id', string='Feed Records'
    )

    # --- Financial ---
    purchase_cost = fields.Monetary(string='Purchase Cost', currency_field='currency_id')
    current_value = fields.Monetary(string='Current Value', currency_field='currency_id')
    currency_id = fields.Many2one(
        'res.currency', default=lambda self: self.env.company.currency_id
    )

    # --- Notes ---
    notes = fields.Html(string='Internal Notes')

    # ========================
    # Computed Fields
    # ========================

    @api.depends('name', 'tag_number')
    def _compute_display_name(self):
        for animal in self:
            animal.display_name = f"[{animal.tag_number}] {animal.name}"

    @api.depends('date_of_birth')
    def _compute_age(self):
        today = fields.Date.today()
        for animal in self:
            if animal.date_of_birth:
                rd = relativedelta(today, animal.date_of_birth)
                animal.age_years = rd.years + (rd.months / 12.0)
                if rd.years > 0:
                    animal.age_display = f"{rd.years}y {rd.months}m"
                else:
                    total_days = (today - animal.date_of_birth).days
                    if rd.months > 0:
                        animal.age_display = f"{rd.months}m {rd.days}d"
                    else:
                        animal.age_display = f"{total_days}d"
            else:
                animal.age_years = 0
                animal.age_display = ''

    @api.depends('milk_production_ids', 'milk_production_ids.quantity_liters')
    def _compute_milk_stats(self):
        for animal in self:
            records = animal.milk_production_ids
            animal.total_milk_lifetime = sum(records.mapped('quantity_liters'))
            if records:
                dates = set(records.mapped('date'))
                animal.avg_daily_milk = animal.total_milk_lifetime / max(len(dates), 1)
            else:
                animal.avg_daily_milk = 0.0

    def _compute_days_in_milk(self):
        """DIM = days since last calving. 0 if dry or never calved."""
        for animal in self:
            last_calving = self.env['farm.breeding.record'].search([
                ('animal_id', '=', animal.id),
                ('actual_calving_date', '!=', False),
            ], order='actual_calving_date desc', limit=1)
            if last_calving and animal.status in ('active',):
                animal.days_in_milk = (
                    fields.Date.today() - last_calving.actual_calving_date
                ).days
            else:
                animal.days_in_milk = 0

    def _compute_lactation_number(self):
        """Count number of completed calvings."""
        for animal in self:
            animal.lactation_number = self.env['farm.breeding.record'].search_count([
                ('animal_id', '=', animal.id),
                ('actual_calving_date', '!=', False),
            ])

    def _compute_offspring(self):
        for animal in self:
            domain = ['|',
                ('parent_sire_id', '=', animal.id),
                ('parent_dam_id', '=', animal.id),
            ]
            animal.offspring_ids = self.search(domain)

    # ========================
    # Constraints
    # ========================

    _sql_constraints = [
        ('tag_number_unique', 'UNIQUE(tag_number)',
         'Tag number must be unique!'),
        ('rfid_tag_unique', 'UNIQUE(rfid_tag)',
         'RFID tag must be unique!'),
    ]

    @api.constrains('date_of_birth')
    def _check_date_of_birth(self):
        for animal in self:
            if animal.date_of_birth and animal.date_of_birth > fields.Date.today():
                raise ValidationError(
                    _("Date of birth cannot be in the future.")
                )

    # ========================
    # CRUD Overrides
    # ========================

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            if vals.get('tag_number', _('New')) == _('New'):
                vals['tag_number'] = self.env['ir.sequence'].next_by_code(
                    'farm.animal'
                ) or _('New')
        return super().create(vals_list)

    # ========================
    # Actions / Business Logic
    # ========================

    def action_set_active(self):
        self.write({'status': 'active', 'status_date': fields.Date.today()})

    def action_set_dry(self):
        self.write({'status': 'dry', 'status_date': fields.Date.today()})

    def action_set_pregnant(self):
        self.write({'status': 'pregnant', 'status_date': fields.Date.today()})

    def action_set_sick(self):
        self.write({'status': 'sick', 'status_date': fields.Date.today()})

    def action_mark_sold(self):
        self.write({'status': 'sold', 'status_date': fields.Date.today()})

    def action_mark_deceased(self):
        self.write({'status': 'deceased', 'status_date': fields.Date.today()})
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Task 21.2.1: Breed seed data (2h)**

```xml
<!-- data/farm_breed_data.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo noupdate="1">
    <record id="breed_holstein_friesian" model="farm.breed">
        <field name="name">Holstein Friesian</field>
        <field name="species">cattle</field>
        <field name="origin_country">Netherlands</field>
        <field name="avg_milk_yield">25.0</field>
        <field name="avg_fat_percentage">3.5</field>
        <field name="avg_gestation_days">280</field>
        <field name="description">Highest milk-producing breed globally. Black-and-white markings.</field>
    </record>
    <record id="breed_sahiwal" model="farm.breed">
        <field name="name">Sahiwal</field>
        <field name="species">cattle</field>
        <field name="origin_country">Pakistan</field>
        <field name="avg_milk_yield">12.0</field>
        <field name="avg_fat_percentage">4.5</field>
        <field name="avg_gestation_days">285</field>
        <field name="description">Heat-tolerant, high-fat milk. Well-suited for South Asian climate.</field>
    </record>
    <record id="breed_red_chittagong" model="farm.breed">
        <field name="name">Red Chittagong</field>
        <field name="species">cattle</field>
        <field name="origin_country">Bangladesh</field>
        <field name="avg_milk_yield">5.0</field>
        <field name="avg_fat_percentage">5.2</field>
        <field name="avg_gestation_days">283</field>
        <field name="description">Indigenous Bangladeshi breed. Small body, disease resistant, high fat.</field>
    </record>
    <record id="breed_jersey" model="farm.breed">
        <field name="name">Jersey</field>
        <field name="species">cattle</field>
        <field name="origin_country">Jersey, UK</field>
        <field name="avg_milk_yield">18.0</field>
        <field name="avg_fat_percentage">5.0</field>
        <field name="avg_gestation_days">279</field>
        <field name="description">High butterfat content. Efficient feed converters.</field>
    </record>
    <record id="breed_crossbred_local" model="farm.breed">
        <field name="name">Crossbred (Local × Holstein)</field>
        <field name="species">cattle</field>
        <field name="origin_country">Bangladesh</field>
        <field name="avg_milk_yield">15.0</field>
        <field name="avg_fat_percentage">3.8</field>
        <field name="avg_gestation_days">282</field>
        <field name="description">Crossbred for South Asian conditions. Balances yield and heat tolerance.</field>
    </record>

    <!-- Sequence for animal tag numbers -->
    <record id="seq_farm_animal" model="ir.sequence">
        <field name="name">Farm Animal Sequence</field>
        <field name="code">farm.animal</field>
        <field name="prefix">SD-</field>
        <field name="padding">4</field>
        <field name="number_increment">1</field>
    </record>
</odoo>
```

**Task 21.2.2: Security groups and access CSV (3h)**

```xml
<!-- security/farm_security.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo noupdate="0">
    <record id="module_category_farm" model="ir.module.category">
        <field name="name">Farm Management</field>
        <field name="sequence">50</field>
    </record>

    <record id="group_farm_user" model="res.groups">
        <field name="name">Farm User</field>
        <field name="category_id" ref="module_category_farm"/>
    </record>
    <record id="group_farm_manager" model="res.groups">
        <field name="name">Farm Manager</field>
        <field name="category_id" ref="module_category_farm"/>
        <field name="implied_ids" eval="[(4, ref('group_farm_user'))]"/>
    </record>
    <record id="group_farm_vet" model="res.groups">
        <field name="name">Veterinarian</field>
        <field name="category_id" ref="module_category_farm"/>
        <field name="implied_ids" eval="[(4, ref('group_farm_user'))]"/>
    </record>
    <record id="group_milk_collector" model="res.groups">
        <field name="name">Milk Collector</field>
        <field name="category_id" ref="module_category_farm"/>
        <field name="implied_ids" eval="[(4, ref('group_farm_user'))]"/>
    </record>
</odoo>
```

```csv
id,name,model_id:id,group_id:id,perm_read,perm_write,perm_create,perm_unlink
access_farm_animal_user,farm.animal.user,model_farm_animal,group_farm_user,1,0,0,0
access_farm_animal_manager,farm.animal.manager,model_farm_animal,group_farm_manager,1,1,1,1
access_farm_breed_user,farm.breed.user,model_farm_breed,group_farm_user,1,0,0,0
access_farm_breed_manager,farm.breed.manager,model_farm_breed,group_farm_manager,1,1,1,1
access_farm_farm_user,farm.farm.user,model_farm_farm,group_farm_user,1,0,0,0
access_farm_farm_manager,farm.farm.manager,model_farm_farm,group_farm_manager,1,1,1,1
access_health_record_user,farm.health.record.user,model_farm_health_record,group_farm_user,1,0,0,0
access_health_record_vet,farm.health.record.vet,model_farm_health_record,group_farm_vet,1,1,1,0
access_health_record_manager,farm.health.record.manager,model_farm_health_record,group_farm_manager,1,1,1,1
access_milk_prod_collector,farm.milk.production.collector,model_farm_milk_production,group_milk_collector,1,1,1,0
access_milk_prod_manager,farm.milk.production.manager,model_farm_milk_production,group_farm_manager,1,1,1,1
access_breeding_user,farm.breeding.record.user,model_farm_breeding_record,group_farm_user,1,0,0,0
access_breeding_vet,farm.breeding.record.vet,model_farm_breeding_record,group_farm_vet,1,1,1,0
access_breeding_manager,farm.breeding.record.manager,model_farm_breeding_record,group_farm_manager,1,1,1,1
access_feed_record_user,farm.feed.record.user,model_farm_feed_record,group_farm_user,1,0,0,0
access_feed_record_manager,farm.feed.record.manager,model_farm_feed_record,group_farm_manager,1,1,1,1
access_feed_ration_user,farm.feed.ration.user,model_farm_feed_ration,group_farm_user,1,0,0,0
access_feed_ration_manager,farm.feed.ration.manager,model_farm_feed_ration,group_farm_manager,1,1,1,1
access_feed_ration_line_user,farm.feed.ration.line.user,model_farm_feed_ration_line,group_farm_user,1,0,0,0
access_feed_ration_line_manager,farm.feed.ration.line.manager,model_farm_feed_ration_line,group_farm_manager,1,1,1,1
access_equipment_user,farm.equipment.user,model_farm_equipment,group_farm_user,1,0,0,0
access_equipment_manager,farm.equipment.manager,model_farm_equipment,group_farm_manager,1,1,1,1
```

**Task 21.2.3: CI/CD pipeline update for custom module testing (3h)**

```yaml
# .github/workflows/test-smart-farm.yml
name: Smart Farm Module Tests
on:
  push:
    paths:
      - 'custom_addons/smart_farm_mgmt/**'
  pull_request:
    paths:
      - 'custom_addons/smart_farm_mgmt/**'

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
      - name: Install Odoo dependencies
        run: |
          pip install -r requirements.txt
          pip install pytest pytest-cov
      - name: Run smart_farm_mgmt tests
        run: |
          python odoo-bin --test-enable \
            --stop-after-init \
            --addons-path=addons,custom_addons \
            -d odoo_test \
            -i smart_farm_mgmt \
            --log-level=test
        env:
          PGHOST: localhost
          PGPORT: 5432
          PGUSER: odoo
          PGPASSWORD: odoo_test
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Task 21.3.1: UI/UX wireframes for farm module (4h)**

- Animal list view wireframe with status color coding
- Animal form view with tabbed sections (General, Health, Milk, Breeding, Feed)
- Kanban board for animal status overview
- Dashboard layout with 4 KPI cards + 4 chart widgets

**Task 21.3.2: Base SCSS theme for farm module (4h)**

```scss
// static/src/scss/farm_dashboard.scss
.o_farm_dashboard {
  padding: 16px;

  .farm-kpi-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 24px;

    .farm-kpi-card {
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
      border-left: 4px solid;

      &.animals { border-color: #2196F3; }
      &.milk { border-color: #4CAF50; }
      &.health { border-color: #FF9800; }
      &.breeding { border-color: #9C27B0; }

      .kpi-value {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
      }
      .kpi-label {
        font-size: 0.85rem;
        color: #666;
        text-transform: uppercase;
      }
      .kpi-trend {
        font-size: 0.8rem;
        &.up { color: #4CAF50; }
        &.down { color: #F44336; }
      }
    }
  }

  .farm-charts {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;

    @media (max-width: 768px) {
      grid-template-columns: 1fr;
    }

    .farm-chart-card {
      background: #fff;
      border-radius: 8px;
      padding: 16px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);

      .chart-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 12px;
        color: #333;
      }
    }
  }
}

// Animal status badges
.o_farm_animal_status {
  .badge-active { background-color: #4CAF50; color: #fff; }
  .badge-dry { background-color: #FF9800; color: #fff; }
  .badge-pregnant { background-color: #9C27B0; color: #fff; }
  .badge-sick { background-color: #F44336; color: #fff; }
  .badge-heifer { background-color: #03A9F4; color: #fff; }
  .badge-calf { background-color: #8BC34A; color: #fff; }
  .badge-sold { background-color: #9E9E9E; color: #fff; }
  .badge-deceased { background-color: #607D8B; color: #fff; }
}
```

#### End-of-Day 21 Deliverables

- [x] Module directory structure created with all placeholder files
- [x] `__manifest__.py` with all dependencies and data file references
- [x] `farm.breed` model with 5 Bangladesh-relevant breed seeds
- [x] `farm.farm` model with computed animal counts
- [x] `farm.animal` model with all fields, computed age, DIM, lactation number
- [x] Security groups (Farm User, Farm Manager, Veterinarian, Milk Collector)
- [x] `ir.model.access.csv` with role-based permissions for all models
- [x] CI/CD workflow for module testing
- [x] SCSS theme foundations

---

### Day 22 — Animal Management Views & Lifecycle

**Objective:** Build comprehensive XML views for animal management and implement the lifecycle state machine UI.

#### Dev 1 — Backend Lead (8h)

**Task 22.1.1: Animal form view XML (4h)**

```xml
<!-- views/farm_animal_views.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Animal Form View -->
    <record id="view_farm_animal_form" model="ir.ui.view">
        <field name="name">farm.animal.form</field>
        <field name="model">farm.animal</field>
        <field name="arch" type="xml">
            <form string="Animal">
                <header>
                    <button name="action_set_active" type="object"
                            string="Set Active" class="btn-primary"
                            invisible="status == 'active'"/>
                    <button name="action_set_dry" type="object"
                            string="Set Dry"
                            invisible="status not in ('active',)"/>
                    <button name="action_set_pregnant" type="object"
                            string="Mark Pregnant"
                            invisible="status not in ('active', 'dry')"/>
                    <button name="action_set_sick" type="object"
                            string="Mark Sick"
                            invisible="status in ('sold', 'deceased')"/>
                    <button name="action_mark_sold" type="object"
                            string="Mark Sold" class="btn-warning"
                            invisible="status in ('sold', 'deceased')"
                            confirm="Are you sure you want to mark this animal as sold?"/>
                    <button name="action_mark_deceased" type="object"
                            string="Mark Deceased" class="btn-danger"
                            invisible="status == 'deceased'"
                            confirm="Are you sure? This cannot be undone."/>
                    <field name="status" widget="statusbar"
                           statusbar_visible="calf,heifer,active,pregnant,dry"/>
                </header>
                <sheet>
                    <div class="oe_button_box" name="button_box">
                        <button name="action_view_health_records" type="object"
                                class="oe_stat_button" icon="fa-medkit">
                            <field name="health_record_ids" widget="statinfo"
                                   string="Health Records"/>
                        </button>
                        <button name="action_view_milk_records" type="object"
                                class="oe_stat_button" icon="fa-tint">
                            <field name="total_milk_lifetime" widget="statinfo"
                                   string="Lifetime Milk (L)"/>
                        </button>
                    </div>
                    <field name="photo" widget="image" class="oe_avatar"
                           options="{'size': [128, 128]}"/>
                    <div class="oe_title">
                        <h1>
                            <field name="tag_number" readonly="1"/>
                            — <field name="name" placeholder="Animal Name"/>
                        </h1>
                    </div>
                    <group>
                        <group string="Identification">
                            <field name="breed_id"/>
                            <field name="gender"/>
                            <field name="rfid_tag"/>
                            <field name="color"/>
                            <field name="date_of_birth"/>
                            <field name="age_display" string="Age"/>
                        </group>
                        <group string="Location & Value">
                            <field name="farm_id"/>
                            <field name="weight"/>
                            <field name="purchase_cost"/>
                            <field name="current_value"/>
                            <field name="days_in_milk"/>
                            <field name="lactation_number"/>
                        </group>
                    </group>
                    <notebook>
                        <page string="Lineage" name="lineage">
                            <group>
                                <group>
                                    <field name="parent_sire_id"/>
                                    <field name="parent_dam_id"/>
                                </group>
                            </group>
                        </page>
                        <page string="Health Records" name="health">
                            <field name="health_record_ids" mode="tree">
                                <tree editable="bottom">
                                    <field name="date"/>
                                    <field name="record_type"/>
                                    <field name="diagnosis"/>
                                    <field name="treatment"/>
                                    <field name="vet_id"/>
                                    <field name="cost" sum="Total Cost"/>
                                </tree>
                            </field>
                        </page>
                        <page string="Milk Production" name="milk">
                            <field name="milk_production_ids">
                                <tree>
                                    <field name="date"/>
                                    <field name="session"/>
                                    <field name="quantity_liters" sum="Total"/>
                                    <field name="fat_percentage"/>
                                    <field name="snf_percentage"/>
                                    <field name="quality_grade"/>
                                </tree>
                            </field>
                        </page>
                        <page string="Breeding" name="breeding">
                            <field name="breeding_record_ids">
                                <tree>
                                    <field name="date"/>
                                    <field name="breeding_type"/>
                                    <field name="pregnancy_confirmed"/>
                                    <field name="expected_calving_date"/>
                                    <field name="actual_calving_date"/>
                                </tree>
                            </field>
                        </page>
                        <page string="Feed Records" name="feed">
                            <field name="feed_record_ids">
                                <tree editable="bottom">
                                    <field name="date"/>
                                    <field name="feed_type_id"/>
                                    <field name="quantity_kg"/>
                                    <field name="cost" sum="Total"/>
                                </tree>
                            </field>
                        </page>
                        <page string="Notes" name="notes">
                            <field name="notes"/>
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

    <!-- Animal Tree View -->
    <record id="view_farm_animal_tree" model="ir.ui.view">
        <field name="name">farm.animal.tree</field>
        <field name="model">farm.animal</field>
        <field name="arch" type="xml">
            <tree string="Animals" decoration-danger="status == 'sick'"
                  decoration-muted="status in ('sold', 'deceased')"
                  decoration-success="status == 'active'"
                  decoration-info="status == 'pregnant'">
                <field name="tag_number"/>
                <field name="name"/>
                <field name="breed_id"/>
                <field name="gender"/>
                <field name="age_display" string="Age"/>
                <field name="status" widget="badge"
                       decoration-success="status == 'active'"
                       decoration-warning="status == 'dry'"
                       decoration-info="status == 'pregnant'"
                       decoration-danger="status == 'sick'"/>
                <field name="farm_id"/>
                <field name="avg_daily_milk" string="Avg Milk/Day (L)"/>
                <field name="days_in_milk" string="DIM"/>
                <field name="weight"/>
            </tree>
        </field>
    </record>

    <!-- Animal Kanban View -->
    <record id="view_farm_animal_kanban" model="ir.ui.view">
        <field name="name">farm.animal.kanban</field>
        <field name="model">farm.animal</field>
        <field name="arch" type="xml">
            <kanban default_group_by="status" class="o_kanban_small_column">
                <field name="name"/>
                <field name="tag_number"/>
                <field name="breed_id"/>
                <field name="status"/>
                <field name="photo"/>
                <field name="avg_daily_milk"/>
                <field name="age_display"/>
                <templates>
                    <t t-name="kanban-card">
                        <div class="d-flex">
                            <field name="photo" widget="image"
                                   options="{'size': [48, 48]}"
                                   class="me-2"/>
                            <div>
                                <strong><field name="tag_number"/></strong>
                                <br/><field name="name"/>
                                <br/><small class="text-muted">
                                    <field name="breed_id"/> |
                                    <field name="age_display"/>
                                </small>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-primary">
                                <field name="avg_daily_milk"/> L/day
                            </span>
                        </div>
                    </t>
                </templates>
            </kanban>
        </field>
    </record>

    <!-- Animal Search View -->
    <record id="view_farm_animal_search" model="ir.ui.view">
        <field name="name">farm.animal.search</field>
        <field name="model">farm.animal</field>
        <field name="arch" type="xml">
            <search string="Search Animals">
                <field name="name"/>
                <field name="tag_number"/>
                <field name="rfid_tag"/>
                <field name="breed_id"/>
                <filter name="active_animals" string="Active"
                        domain="[('status', '=', 'active')]"/>
                <filter name="pregnant" string="Pregnant"
                        domain="[('status', '=', 'pregnant')]"/>
                <filter name="sick" string="Sick"
                        domain="[('status', '=', 'sick')]"/>
                <filter name="female" string="Cows"
                        domain="[('gender', '=', 'female')]"/>
                <filter name="male" string="Bulls"
                        domain="[('gender', '=', 'male')]"/>
                <group expand="0" string="Group By">
                    <filter name="group_status" string="Status"
                            context="{'group_by': 'status'}"/>
                    <filter name="group_breed" string="Breed"
                            context="{'group_by': 'breed_id'}"/>
                    <filter name="group_farm" string="Farm"
                            context="{'group_by': 'farm_id'}"/>
                </group>
            </search>
        </field>
    </record>

    <!-- Action -->
    <record id="action_farm_animal" model="ir.actions.act_window">
        <field name="name">Animals</field>
        <field name="res_model">farm.animal</field>
        <field name="view_mode">kanban,tree,form</field>
        <field name="search_view_id" ref="view_farm_animal_search"/>
        <field name="context">{'search_default_active_animals': 1}</field>
        <field name="help" type="html">
            <p class="o_view_nocontent_smiling_face">
                Register your first animal
            </p>
        </field>
    </record>
</odoo>
```

**Task 22.1.2: Menu items (2h)**

```xml
<!-- views/farm_menu.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Root Menu -->
    <menuitem id="menu_farm_root" name="Farm Management"
              web_icon="smart_farm_mgmt,static/description/icon.png"
              sequence="45"/>

    <!-- Animals -->
    <menuitem id="menu_farm_animals" name="Animals"
              parent="menu_farm_root" sequence="10"/>
    <menuitem id="menu_farm_animal_list" name="All Animals"
              parent="menu_farm_animals" action="action_farm_animal" sequence="10"/>
    <menuitem id="menu_farm_breed_list" name="Breeds"
              parent="menu_farm_animals" action="action_farm_breed" sequence="20"/>

    <!-- Health -->
    <menuitem id="menu_farm_health" name="Health"
              parent="menu_farm_root" sequence="20"/>
    <menuitem id="menu_farm_health_records" name="Health Records"
              parent="menu_farm_health" action="action_farm_health_record" sequence="10"/>

    <!-- Milk Production -->
    <menuitem id="menu_farm_milk" name="Milk Production"
              parent="menu_farm_root" sequence="30"/>
    <menuitem id="menu_farm_milk_records" name="Daily Records"
              parent="menu_farm_milk" action="action_farm_milk_production" sequence="10"/>

    <!-- Breeding -->
    <menuitem id="menu_farm_breeding" name="Breeding"
              parent="menu_farm_root" sequence="40"/>
    <menuitem id="menu_farm_breeding_records" name="Breeding Records"
              parent="menu_farm_breeding" action="action_farm_breeding_record" sequence="10"/>

    <!-- Feed & Nutrition -->
    <menuitem id="menu_farm_feed" name="Feed &amp; Nutrition"
              parent="menu_farm_root" sequence="50"/>
    <menuitem id="menu_farm_feed_records" name="Feed Records"
              parent="menu_farm_feed" action="action_farm_feed_record" sequence="10"/>
    <menuitem id="menu_farm_rations" name="Ration Plans"
              parent="menu_farm_feed" action="action_farm_feed_ration" sequence="20"/>

    <!-- Equipment -->
    <menuitem id="menu_farm_equipment" name="Equipment"
              parent="menu_farm_root" sequence="60"/>
    <menuitem id="menu_farm_equipment_list" name="All Equipment"
              parent="menu_farm_equipment" action="action_farm_equipment" sequence="10"/>

    <!-- Dashboard -->
    <menuitem id="menu_farm_dashboard" name="Dashboard"
              parent="menu_farm_root" sequence="1"
              action="action_farm_dashboard"/>

    <!-- Configuration -->
    <menuitem id="menu_farm_config" name="Configuration"
              parent="menu_farm_root" sequence="99"/>
    <menuitem id="menu_farm_farms" name="Farms"
              parent="menu_farm_config" action="action_farm_farm" sequence="10"/>
</odoo>
```

#### Dev 2 — Full-Stack / DevOps (8h)

- Record rules for row-level security (Farm Manager sees own farm's animals)
- Test data generation script (50 sample animals, randomized breeds/statuses)
- Module installation verification and dependency resolution

#### Dev 3 — Frontend / Mobile Lead (8h)

- Animal Kanban view implementation and styling
- Photo upload widget integration
- Status badge color coding

#### End-of-Day 22 Deliverables

- [x] Complete animal form view with all notebook pages
- [x] Tree view with status-based row decoration
- [x] Kanban view grouped by status with photo thumbnails
- [x] Search view with filters and group-by options
- [x] Full menu structure for Farm Management application
- [x] Record rules for farm-level data isolation
- [x] Test data: 50 animals loaded

---

### Day 23 — Health Record Management

**Objective:** Build the health record subsystem for veterinary tracking, vaccinations, and treatments.

#### Dev 1 — Backend Lead (8h)

```python
# models/farm_health_record.py
from odoo import models, fields, api, _
from odoo.exceptions import ValidationError
from dateutil.relativedelta import relativedelta

class FarmHealthRecord(models.Model):
    _name = 'farm.health.record'
    _description = 'Animal Health Record'
    _inherit = ['mail.thread']
    _order = 'date desc'

    animal_id = fields.Many2one(
        'farm.animal', string='Animal', required=True,
        ondelete='cascade', tracking=True, index=True
    )
    date = fields.Date(
        string='Date', required=True,
        default=fields.Date.today, tracking=True
    )
    record_type = fields.Selection([
        ('checkup', 'Routine Checkup'),
        ('vaccination', 'Vaccination'),
        ('treatment', 'Treatment'),
        ('surgery', 'Surgery'),
        ('deworming', 'Deworming'),
        ('pregnancy_check', 'Pregnancy Check'),
    ], string='Record Type', required=True, tracking=True)

    # --- Clinical Details ---
    symptoms = fields.Text(string='Symptoms / Observations')
    diagnosis = fields.Text(string='Diagnosis')
    treatment = fields.Text(string='Treatment / Procedure')
    medication = fields.Text(
        string='Medication',
        help='Name, dosage, route, frequency, duration'
    )
    body_temperature = fields.Float(
        string='Body Temp (°C)', digits=(4, 1),
        help='Normal cattle: 38.0 - 39.5°C'
    )
    heart_rate = fields.Integer(
        string='Heart Rate (bpm)',
        help='Normal cattle: 48-84 bpm'
    )
    respiratory_rate = fields.Integer(
        string='Respiratory Rate',
        help='Normal cattle: 26-50 breaths/min'
    )
    body_condition_score = fields.Float(
        string='Body Condition Score (1-5)', digits=(3, 1),
        help='BCS: 1=emaciated, 3=ideal, 5=obese'
    )

    # --- Vaccination Specific ---
    vaccine_name = fields.Char(string='Vaccine Name')
    vaccine_batch = fields.Char(string='Vaccine Batch Number')
    vaccine_dose = fields.Float(string='Dose (ml)', digits=(6, 2))
    next_vaccination_date = fields.Date(string='Next Vaccination Due')

    # --- Personnel & Cost ---
    vet_id = fields.Many2one(
        'res.users', string='Veterinarian',
        domain=[('groups_id', 'in', [])],  # Filtered at runtime
        tracking=True
    )
    cost = fields.Monetary(
        string='Cost (BDT)', currency_field='currency_id'
    )
    currency_id = fields.Many2one(
        'res.currency', default=lambda self: self.env.company.currency_id
    )

    # --- Follow-up ---
    next_followup_date = fields.Date(string='Next Follow-up Date')
    is_followup_due = fields.Boolean(
        compute='_compute_followup_due', store=True
    )
    severity = fields.Selection([
        ('low', 'Low'),
        ('medium', 'Medium'),
        ('high', 'High'),
        ('critical', 'Critical'),
    ], string='Severity', default='low')
    outcome = fields.Selection([
        ('recovered', 'Recovered'),
        ('improving', 'Improving'),
        ('stable', 'Stable'),
        ('worsening', 'Worsening'),
        ('deceased', 'Deceased'),
    ], string='Outcome')
    notes = fields.Html(string='Notes')
    attachment_ids = fields.Many2many(
        'ir.attachment', string='Attachments',
        help='Lab reports, X-rays, prescriptions'
    )

    @api.depends('next_followup_date')
    def _compute_followup_due(self):
        today = fields.Date.today()
        for rec in self:
            rec.is_followup_due = (
                rec.next_followup_date and rec.next_followup_date <= today
            )

    @api.constrains('body_condition_score')
    def _check_bcs(self):
        for rec in self:
            if rec.body_condition_score and not (1 <= rec.body_condition_score <= 5):
                raise ValidationError(_("Body Condition Score must be between 1 and 5."))

    @api.onchange('record_type')
    def _onchange_record_type(self):
        """Auto-set next vaccination date for vaccination records."""
        if self.record_type == 'vaccination' and self.date:
            self.next_vaccination_date = self.date + relativedelta(months=6)
```

#### Dev 2 — Full-Stack / DevOps (8h)

- Health record view XML (form with conditional field visibility based on record_type)
- Vaccination schedule report (upcoming vaccinations in next 30 days)
- Seed data: sample health records for test animals

#### Dev 3 — Frontend / Mobile Lead (8h)

- Health record timeline view (chronological health history per animal)
- Vaccination calendar widget (upcoming vaccinations highlighted)
- Health alert banner on animal form (if sick or followup due)

#### End-of-Day 23 Deliverables

- [x] `farm.health.record` model with all clinical fields
- [x] Form view with conditional sections per record type
- [x] Health timeline and vaccination calendar widgets
- [x] Vaccination schedule report
- [x] Sample health records seeded

---

### Day 24 — Milk Production Recording

**Objective:** Implement daily milk yield recording with quality metrics and automated grading.

#### Dev 1 — Backend Lead (8h)

```python
# models/farm_milk_production.py
from odoo import models, fields, api, _
from odoo.exceptions import ValidationError

class FarmMilkProduction(models.Model):
    _name = 'farm.milk.production'
    _description = 'Daily Milk Production Record'
    _order = 'date desc, session'
    _rec_name = 'display_name'

    animal_id = fields.Many2one(
        'farm.animal', string='Animal', required=True,
        ondelete='cascade', index=True,
        domain=[('gender', '=', 'female'), ('status', 'in', ('active',))]
    )
    date = fields.Date(
        string='Date', required=True,
        default=fields.Date.today, index=True
    )
    session = fields.Selection([
        ('morning', 'Morning'),
        ('evening', 'Evening'),
    ], string='Session', required=True, default='morning')

    # --- Quantity ---
    quantity_liters = fields.Float(
        string='Quantity (Liters)', required=True, digits=(8, 2)
    )

    # --- Quality Metrics ---
    fat_percentage = fields.Float(
        string='Fat %', digits=(4, 2),
        help='Milk fat content. Target: 3.5-5.0% for dairy breeds'
    )
    snf_percentage = fields.Float(
        string='SNF %', digits=(4, 2),
        help='Solids-Not-Fat. Target: 8.0-9.5%'
    )
    protein_percentage = fields.Float(
        string='Protein %', digits=(4, 2),
        help='Protein content. Target: 3.0-3.5%'
    )
    somatic_cell_count = fields.Integer(
        string='SCC (×1000/mL)',
        help='Somatic Cell Count. <200 = healthy, 200-400 = subclinical, >400 = mastitis risk'
    )
    temperature = fields.Float(
        string='Temperature (°C)', digits=(4, 1),
        help='Milk temperature at collection. Should be <10°C within 2 hours'
    )
    ph_value = fields.Float(string='pH Value', digits=(3, 2))
    conductivity = fields.Float(
        string='Conductivity (mS/cm)', digits=(5, 2),
        help='Electrical conductivity. Normal: 4.0-5.5 mS/cm. >6.0 = possible mastitis'
    )

    # --- Quality Grade (Computed) ---
    quality_grade = fields.Selection([
        ('A', 'Grade A — Premium'),
        ('B', 'Grade B — Standard'),
        ('C', 'Grade C — Below Standard'),
        ('rejected', 'Rejected'),
    ], string='Quality Grade', compute='_compute_quality_grade', store=True)

    # --- Collection Details ---
    collector_id = fields.Many2one(
        'res.users', string='Collector',
        default=lambda self: self.env.user
    )
    collection_method = fields.Selection([
        ('manual', 'Manual'),
        ('machine', 'Machine'),
    ], string='Collection Method', default='machine')
    tank_id = fields.Char(string='Collection Tank ID')

    # --- Financials ---
    unit_price = fields.Float(
        string='Price per Liter (BDT)', digits=(8, 2)
    )
    total_value = fields.Monetary(
        string='Total Value', compute='_compute_total_value',
        store=True, currency_field='currency_id'
    )
    currency_id = fields.Many2one(
        'res.currency', default=lambda self: self.env.company.currency_id
    )

    notes = fields.Text(string='Notes')
    display_name = fields.Char(compute='_compute_display_name')

    # ========================
    # Quality Grading Algorithm
    # ========================

    @api.depends('fat_percentage', 'snf_percentage', 'somatic_cell_count',
                 'temperature', 'protein_percentage')
    def _compute_quality_grade(self):
        """
        Grading Logic (Smart Dairy Standard):
        Grade A: Fat >= 3.5%, SNF >= 8.5%, SCC < 200k, Temp < 10°C
        Grade B: Fat >= 3.0%, SNF >= 8.0%, SCC < 400k, Temp < 15°C
        Grade C: Fat >= 2.5%, SNF >= 7.5%, SCC < 750k
        Rejected: Any metric below Grade C thresholds, or SCC >= 750k
        """
        for rec in self:
            fat = rec.fat_percentage or 0
            snf = rec.snf_percentage or 0
            scc = rec.somatic_cell_count or 0
            temp = rec.temperature or 0

            if fat >= 3.5 and snf >= 8.5 and scc < 200 and temp < 10:
                rec.quality_grade = 'A'
            elif fat >= 3.0 and snf >= 8.0 and scc < 400 and temp < 15:
                rec.quality_grade = 'B'
            elif fat >= 2.5 and snf >= 7.5 and scc < 750:
                rec.quality_grade = 'C'
            else:
                rec.quality_grade = 'rejected'

    @api.depends('quantity_liters', 'unit_price')
    def _compute_total_value(self):
        for rec in self:
            rec.total_value = rec.quantity_liters * (rec.unit_price or 0)

    @api.depends('animal_id', 'date', 'session')
    def _compute_display_name(self):
        for rec in self:
            animal = rec.animal_id.tag_number or ''
            rec.display_name = f"{animal} — {rec.date} ({rec.session})"

    # ========================
    # Constraints
    # ========================

    _sql_constraints = [
        ('unique_animal_date_session',
         'UNIQUE(animal_id, date, session)',
         'Only one record per animal per session per day!'),
    ]

    @api.constrains('quantity_liters')
    def _check_quantity(self):
        for rec in self:
            if rec.quantity_liters <= 0:
                raise ValidationError(_("Milk quantity must be positive."))
            if rec.quantity_liters > 50:
                raise ValidationError(
                    _("Milk quantity exceeds 50L maximum. Please verify.")
                )

    @api.constrains('fat_percentage')
    def _check_fat(self):
        for rec in self:
            if rec.fat_percentage and not (0 < rec.fat_percentage < 15):
                raise ValidationError(
                    _("Fat percentage must be between 0 and 15.")
                )
```

#### Dev 2 — Full-Stack / DevOps (8h)

- Milk production views (tree with daily totals, form for data entry)
- Bulk milk entry wizard (enter multiple animals in one form for a session)
- Daily milk production report (PDF with totals, averages, quality breakdown)

#### Dev 3 — Frontend / Mobile Lead (8h)

- Quick-entry milk collection form optimized for mobile/tablet
- Milk quality indicator badges (color-coded A/B/C/Rejected)
- Daily production summary card widget

#### End-of-Day 24 Deliverables

- [x] `farm.milk.production` model with quality grading algorithm
- [x] Unique constraint per animal/date/session
- [x] Bulk entry wizard for efficient data collection
- [x] Quality grade auto-computation verified against test cases
- [x] Daily production summary report

---

### Day 25 — Breeding Management

**Objective:** Implement breeding records with heat detection, AI tracking, pregnancy management, and gestation calculator.

#### Dev 1 — Backend Lead (8h)

```python
# models/farm_breeding_record.py
from odoo import models, fields, api, _
from odoo.exceptions import ValidationError
from datetime import timedelta

CATTLE_GESTATION_DAYS = 283  # Average gestation period for cattle

class FarmBreedingRecord(models.Model):
    _name = 'farm.breeding.record'
    _description = 'Breeding Record'
    _inherit = ['mail.thread']
    _order = 'date desc'

    animal_id = fields.Many2one(
        'farm.animal', string='Cow', required=True,
        ondelete='cascade', index=True,
        domain=[('gender', '=', 'female')],
        tracking=True
    )
    date = fields.Date(
        string='Breeding Date', required=True,
        default=fields.Date.today, tracking=True
    )
    breeding_type = fields.Selection([
        ('natural', 'Natural Mating'),
        ('artificial', 'Artificial Insemination (AI)'),
    ], string='Breeding Type', required=True, default='artificial', tracking=True)

    # --- Bull / Semen ---
    bull_id = fields.Many2one(
        'farm.animal', string='Bull',
        domain=[('gender', '=', 'male')],
        help='Bull used for natural mating'
    )
    semen_batch = fields.Char(
        string='Semen Batch / Straw ID',
        help='AI semen straw identification number'
    )
    semen_source = fields.Char(string='Semen Source / Supplier')
    insemination_count = fields.Integer(
        string='Insemination Count', default=1,
        help='Number of insemination attempts in this cycle'
    )
    technician_id = fields.Many2one(
        'res.users', string='AI Technician', tracking=True
    )

    # --- Heat Detection ---
    heat_detected_date = fields.Date(string='Heat Detected Date')
    heat_detection_method = fields.Selection([
        ('visual', 'Visual Observation'),
        ('pedometer', 'Pedometer / Activity Monitor'),
        ('tail_paint', 'Tail Paint'),
        ('mount_detector', 'Mount Detector'),
        ('blood_test', 'Progesterone Blood Test'),
    ], string='Heat Detection Method')
    heat_intensity = fields.Selection([
        ('weak', 'Weak'),
        ('moderate', 'Moderate'),
        ('strong', 'Strong'),
    ], string='Heat Intensity')

    # --- Pregnancy ---
    pregnancy_check_date = fields.Date(string='Pregnancy Check Date')
    pregnancy_check_method = fields.Selection([
        ('rectal', 'Rectal Palpation'),
        ('ultrasound', 'Ultrasound'),
        ('blood_test', 'Blood Test (PAG)'),
    ], string='Check Method')
    pregnancy_confirmed = fields.Boolean(
        string='Pregnancy Confirmed', tracking=True
    )
    pregnancy_confirm_date = fields.Date(string='Confirmation Date')

    # --- Gestation & Calving ---
    gestation_days = fields.Integer(
        string='Gestation Days', compute='_compute_gestation', store=True
    )
    expected_calving_date = fields.Date(
        string='Expected Calving Date',
        compute='_compute_gestation', store=True
    )
    days_to_calving = fields.Integer(
        string='Days to Calving', compute='_compute_days_to_calving'
    )
    actual_calving_date = fields.Date(
        string='Actual Calving Date', tracking=True
    )
    calving_difficulty = fields.Selection([
        ('normal', 'Normal (Unassisted)'),
        ('easy_assist', 'Easy Assist'),
        ('difficult', 'Difficult (Veterinary)'),
        ('cesarean', 'Cesarean Section'),
    ], string='Calving Difficulty')
    calving_outcome = fields.Selection([
        ('live_single', 'Live Single'),
        ('live_twins', 'Live Twins'),
        ('stillborn', 'Stillborn'),
        ('abortion', 'Abortion'),
    ], string='Calving Outcome')
    calf_id = fields.Many2one(
        'farm.animal', string='Calf',
        help='Link to calf record if registered'
    )

    # --- Cost ---
    cost = fields.Monetary(
        string='Cost (BDT)', currency_field='currency_id',
        help='Semen cost + technician fee'
    )
    currency_id = fields.Many2one(
        'res.currency', default=lambda self: self.env.company.currency_id
    )
    notes = fields.Html(string='Notes')

    # ========================
    # Computed Fields
    # ========================

    @api.depends('date', 'pregnancy_confirmed', 'animal_id.breed_id.avg_gestation_days')
    def _compute_gestation(self):
        """
        Calculate expected calving date using breed-specific gestation.
        Default: 283 days for cattle.
        Only computed when pregnancy is confirmed.
        """
        for rec in self:
            if rec.pregnancy_confirmed and rec.date:
                breed_gestation = (
                    rec.animal_id.breed_id.avg_gestation_days
                    or CATTLE_GESTATION_DAYS
                )
                rec.gestation_days = breed_gestation
                rec.expected_calving_date = rec.date + timedelta(days=breed_gestation)
            else:
                rec.gestation_days = 0
                rec.expected_calving_date = False

    def _compute_days_to_calving(self):
        today = fields.Date.today()
        for rec in self:
            if rec.expected_calving_date and not rec.actual_calving_date:
                rec.days_to_calving = (rec.expected_calving_date - today).days
            else:
                rec.days_to_calving = 0

    # ========================
    # Business Logic
    # ========================

    def action_confirm_pregnancy(self):
        """Mark pregnancy as confirmed and update animal status."""
        self.ensure_one()
        self.write({
            'pregnancy_confirmed': True,
            'pregnancy_confirm_date': fields.Date.today(),
        })
        self.animal_id.action_set_pregnant()

    def action_record_calving(self):
        """Record calving event and update animal to active/lactating."""
        self.ensure_one()
        self.write({'actual_calving_date': fields.Date.today()})
        self.animal_id.action_set_active()

    @api.constrains('insemination_count')
    def _check_insemination_count(self):
        for rec in self:
            if rec.insemination_count < 1:
                raise ValidationError(
                    _("Insemination count must be at least 1.")
                )
            if rec.insemination_count > 5:
                raise ValidationError(
                    _("More than 5 insemination attempts is unusual. "
                      "Please consult veterinarian.")
                )
```

#### Dev 2 — Full-Stack / DevOps (8h)

- Breeding record views (form with breeding-type conditional sections, tree with pregnancy status)
- Breeding calendar view (expected calvings, upcoming pregnancy checks)
- Automated reminders: pregnancy check 30 days after AI, expected calving notification

#### Dev 3 — Frontend / Mobile Lead (8h)

- Breeding timeline visualization per animal
- Pregnancy progress bar widget (days elapsed / gestation days)
- Upcoming calvings calendar component

#### End-of-Day 25 Deliverables

- [x] `farm.breeding.record` model with gestation calculator
- [x] Pregnancy confirmation workflow with animal status update
- [x] Calving recording with outcome tracking
- [x] Breeding calendar and reminder system
- [x] Gestation calculator verified: ±1 day accuracy for 5 breed types

---

### Day 26 — Feed & Nutrition Management

**Objective:** Implement feed recording, ration planning, and cost-per-animal analysis.

#### Dev 1 — Backend Lead (8h)

```python
# models/farm_feed.py
from odoo import models, fields, api, _

class FarmFeedRecord(models.Model):
    _name = 'farm.feed.record'
    _description = 'Feed Record'
    _order = 'date desc'

    animal_id = fields.Many2one(
        'farm.animal', string='Animal', required=True,
        ondelete='cascade', index=True
    )
    date = fields.Date(
        string='Date', required=True, default=fields.Date.today, index=True
    )
    feed_type_id = fields.Many2one(
        'product.product', string='Feed Product', required=True,
        domain=[('categ_id.name', 'ilike', 'feed')]
    )
    ration_id = fields.Many2one(
        'farm.feed.ration', string='Ration Plan'
    )
    quantity_kg = fields.Float(
        string='Quantity (kg)', required=True, digits=(8, 2)
    )
    unit_cost = fields.Float(
        string='Unit Cost (BDT/kg)', digits=(8, 2)
    )
    cost = fields.Monetary(
        string='Total Cost', compute='_compute_cost',
        store=True, currency_field='currency_id'
    )
    currency_id = fields.Many2one(
        'res.currency', default=lambda self: self.env.company.currency_id
    )
    notes = fields.Text(string='Notes')

    @api.depends('quantity_kg', 'unit_cost')
    def _compute_cost(self):
        for rec in self:
            rec.cost = rec.quantity_kg * (rec.unit_cost or 0)


class FarmFeedRation(models.Model):
    _name = 'farm.feed.ration'
    _description = 'Feed Ration Plan'

    name = fields.Char(string='Ration Name', required=True)
    animal_category = fields.Selection([
        ('lactating', 'Lactating Cows'),
        ('dry', 'Dry Cows'),
        ('pregnant', 'Pregnant Cows'),
        ('heifer', 'Heifers'),
        ('calf', 'Calves'),
        ('bull', 'Bulls'),
    ], string='Animal Category', required=True)
    season = fields.Selection([
        ('summer', 'Summer (Mar-May)'),
        ('monsoon', 'Monsoon (Jun-Sep)'),
        ('winter', 'Winter (Oct-Feb)'),
        ('all', 'All Year'),
    ], string='Season', default='all')
    line_ids = fields.One2many(
        'farm.feed.ration.line', 'ration_id', string='Feed Ingredients'
    )
    total_quantity_kg = fields.Float(
        string='Total Qty (kg/day)', compute='_compute_totals', store=True
    )
    total_cost_per_day = fields.Monetary(
        string='Cost/Animal/Day', compute='_compute_totals',
        store=True, currency_field='currency_id'
    )
    currency_id = fields.Many2one(
        'res.currency', default=lambda self: self.env.company.currency_id
    )
    notes = fields.Html(string='Notes')
    active = fields.Boolean(default=True)

    @api.depends('line_ids.quantity_kg', 'line_ids.unit_cost')
    def _compute_totals(self):
        for ration in self:
            lines = ration.line_ids
            ration.total_quantity_kg = sum(lines.mapped('quantity_kg'))
            ration.total_cost_per_day = sum(
                l.quantity_kg * l.unit_cost for l in lines
            )


class FarmFeedRationLine(models.Model):
    _name = 'farm.feed.ration.line'
    _description = 'Feed Ration Line'

    ration_id = fields.Many2one(
        'farm.feed.ration', string='Ration', ondelete='cascade'
    )
    product_id = fields.Many2one(
        'product.product', string='Feed Ingredient', required=True
    )
    quantity_kg = fields.Float(
        string='Quantity (kg/day)', required=True, digits=(8, 2)
    )
    unit_cost = fields.Float(
        string='Unit Cost (BDT/kg)', digits=(8, 2)
    )
    line_cost = fields.Float(
        string='Line Cost', compute='_compute_line_cost'
    )
    dry_matter_percentage = fields.Float(
        string='DM %', digits=(4, 1)
    )
    crude_protein_percentage = fields.Float(
        string='CP %', digits=(4, 1)
    )
    notes = fields.Char(string='Notes')

    @api.depends('quantity_kg', 'unit_cost')
    def _compute_line_cost(self):
        for line in self:
            line.line_cost = line.quantity_kg * (line.unit_cost or 0)
```

#### Dev 2 — Full-Stack / DevOps (8h)

- Feed record and ration plan views
- Feed-to-Inventory integration (deduct feed stock when records are created)
- Sample ration plans for Bangladesh context (local feeds: rice straw, mustard oil cake, wheat bran, green grass)

#### Dev 3 — Frontend / Mobile Lead (8h)

- Ration plan builder interface (drag-and-drop ingredients)
- Feed cost analysis charts (cost per animal per day over time)
- Feed inventory alert widget (low stock warnings)

#### End-of-Day 26 Deliverables

- [x] `farm.feed.record` and `farm.feed.ration` models complete
- [x] Ration cost-per-day auto-calculation verified
- [x] Feed-inventory stock deduction integration
- [x] 4 sample ration plans (lactating, dry, heifer, calf) with local feed types

---

### Day 27 — Farm Equipment & Asset Management

**Objective:** Implement equipment tracking with maintenance schedules and depreciation.

#### Dev 1 — Backend Lead (8h)

```python
# models/farm_equipment.py
from odoo import models, fields, api, _
from dateutil.relativedelta import relativedelta

class FarmEquipment(models.Model):
    _name = 'farm.equipment'
    _description = 'Farm Equipment'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'name'

    name = fields.Char(string='Equipment Name', required=True, tracking=True)
    equipment_type = fields.Selection([
        ('milking', 'Milking Machine'),
        ('cooling', 'Milk Cooling Tank'),
        ('feeding', 'Feeding Equipment'),
        ('cleaning', 'Cleaning System'),
        ('transport', 'Transport Vehicle'),
        ('generator', 'Generator / Power'),
        ('water', 'Water System / Pump'),
        ('iot_sensor', 'IoT Sensor'),
        ('other', 'Other'),
    ], string='Type', required=True, tracking=True)
    serial_number = fields.Char(string='Serial Number', copy=False)
    manufacturer = fields.Char(string='Manufacturer')
    model_name = fields.Char(string='Model')
    farm_id = fields.Many2one('farm.farm', string='Farm Location')

    # --- Purchase & Value ---
    purchase_date = fields.Date(string='Purchase Date')
    purchase_cost = fields.Monetary(
        string='Purchase Cost', currency_field='currency_id'
    )
    useful_life_years = fields.Integer(
        string='Useful Life (Years)', default=5
    )
    salvage_value = fields.Monetary(
        string='Salvage Value', currency_field='currency_id'
    )
    depreciation_method = fields.Selection([
        ('straight_line', 'Straight Line'),
        ('declining_balance', 'Declining Balance'),
    ], string='Depreciation Method', default='straight_line')
    current_value = fields.Monetary(
        string='Current Book Value', compute='_compute_depreciation',
        store=True, currency_field='currency_id'
    )
    accumulated_depreciation = fields.Monetary(
        string='Accumulated Depreciation', compute='_compute_depreciation',
        store=True, currency_field='currency_id'
    )
    currency_id = fields.Many2one(
        'res.currency', default=lambda self: self.env.company.currency_id
    )

    # --- Status ---
    status = fields.Selection([
        ('operational', 'Operational'),
        ('maintenance', 'Under Maintenance'),
        ('broken', 'Broken / Repair Needed'),
        ('retired', 'Retired'),
    ], string='Status', default='operational', tracking=True)

    # --- Maintenance ---
    last_maintenance_date = fields.Date(string='Last Maintenance')
    next_maintenance_date = fields.Date(
        string='Next Scheduled Maintenance',
        compute='_compute_next_maintenance', store=True
    )
    maintenance_interval_days = fields.Integer(
        string='Maintenance Interval (Days)', default=90
    )
    maintenance_log_ids = fields.One2many(
        'farm.equipment', 'id', string='Maintenance Log'
        # In production, this would be a separate model
    )
    is_maintenance_due = fields.Boolean(
        compute='_compute_maintenance_due'
    )

    # --- IoT ---
    iot_device_id = fields.Char(string='IoT Device ID')
    iot_enabled = fields.Boolean(string='IoT Enabled')

    notes = fields.Html(string='Notes')

    @api.depends('purchase_cost', 'purchase_date', 'useful_life_years',
                 'salvage_value', 'depreciation_method')
    def _compute_depreciation(self):
        today = fields.Date.today()
        for eq in self:
            if not eq.purchase_date or not eq.purchase_cost or not eq.useful_life_years:
                eq.current_value = eq.purchase_cost or 0
                eq.accumulated_depreciation = 0
                continue

            years_elapsed = (today - eq.purchase_date).days / 365.25
            cost = eq.purchase_cost
            salvage = eq.salvage_value or 0
            life = eq.useful_life_years

            if eq.depreciation_method == 'straight_line':
                annual_dep = (cost - salvage) / life
                total_dep = min(annual_dep * years_elapsed, cost - salvage)
            else:  # declining_balance
                rate = 2.0 / life  # Double declining
                value = cost
                for _ in range(int(years_elapsed)):
                    dep = value * rate
                    value = max(value - dep, salvage)
                total_dep = cost - value

            eq.accumulated_depreciation = max(total_dep, 0)
            eq.current_value = max(cost - total_dep, salvage)

    @api.depends('last_maintenance_date', 'maintenance_interval_days')
    def _compute_next_maintenance(self):
        for eq in self:
            if eq.last_maintenance_date and eq.maintenance_interval_days:
                eq.next_maintenance_date = (
                    eq.last_maintenance_date
                    + relativedelta(days=eq.maintenance_interval_days)
                )
            else:
                eq.next_maintenance_date = False

    def _compute_maintenance_due(self):
        today = fields.Date.today()
        for eq in self:
            eq.is_maintenance_due = (
                eq.next_maintenance_date
                and eq.next_maintenance_date <= today
            )
```

#### Dev 2 — Full-Stack / DevOps (8h)

- Equipment views (form, tree, kanban by status)
- Maintenance schedule cron job (daily check, send reminders)
- Equipment-to-Accounting link (depreciation journal entries preparation)

#### Dev 3 — Frontend / Mobile Lead (8h)

- Equipment status dashboard with maintenance calendar
- Depreciation chart (book value over time)
- QR code label generation for equipment tagging

#### End-of-Day 27 Deliverables

- [x] `farm.equipment` model with depreciation calculation
- [x] Straight-line and declining balance depreciation methods
- [x] Maintenance scheduling with automated reminders
- [x] Equipment status kanban board
- [x] QR code equipment labels

---

### Day 28 — Farm Dashboard & OWL Reports

**Objective:** Build the comprehensive OWL-based farm dashboard with real-time KPIs and interactive charts.

#### Dev 1 — Backend Lead (8h)

**Task 28.1.1: Dashboard data controller (4h)**

```python
# controllers/farm_dashboard.py
from odoo import http
from odoo.http import request
import json
from datetime import date, timedelta

class FarmDashboardController(http.Controller):

    @http.route('/smart_farm/dashboard/data', type='json', auth='user')
    def get_dashboard_data(self, **kwargs):
        Animal = request.env['farm.animal']
        MilkProd = request.env['farm.milk.production']
        Health = request.env['farm.health.record']
        Breeding = request.env['farm.breeding.record']

        today = date.today()
        thirty_days_ago = today - timedelta(days=30)
        seven_days_ago = today - timedelta(days=7)

        # --- Animal KPIs ---
        total_animals = Animal.search_count([
            ('status', 'not in', ('sold', 'deceased'))
        ])
        active_lactating = Animal.search_count([('status', '=', 'active')])
        pregnant_count = Animal.search_count([('status', '=', 'pregnant')])
        sick_count = Animal.search_count([('status', '=', 'sick')])

        # --- Milk KPIs (last 7 days) ---
        milk_records = MilkProd.search([
            ('date', '>=', seven_days_ago),
            ('date', '<=', today),
        ])
        total_milk_7d = sum(milk_records.mapped('quantity_liters'))
        avg_daily_milk = total_milk_7d / 7 if milk_records else 0

        grade_counts = {'A': 0, 'B': 0, 'C': 0, 'rejected': 0}
        for rec in milk_records:
            if rec.quality_grade:
                grade_counts[rec.quality_grade] = grade_counts.get(rec.quality_grade, 0) + 1

        # --- Milk trend (last 30 days, daily aggregates) ---
        milk_trend = []
        for i in range(30):
            d = today - timedelta(days=29 - i)
            day_total = sum(MilkProd.search([
                ('date', '=', d)
            ]).mapped('quantity_liters'))
            milk_trend.append({'date': str(d), 'liters': day_total})

        # --- Upcoming events ---
        upcoming_calvings = Breeding.search([
            ('expected_calving_date', '>=', today),
            ('expected_calving_date', '<=', today + timedelta(days=30)),
            ('actual_calving_date', '=', False),
            ('pregnancy_confirmed', '=', True),
        ], order='expected_calving_date')

        upcoming_vaccinations = Health.search([
            ('next_vaccination_date', '>=', today),
            ('next_vaccination_date', '<=', today + timedelta(days=30)),
        ], order='next_vaccination_date')

        return {
            'animal_kpis': {
                'total': total_animals,
                'lactating': active_lactating,
                'pregnant': pregnant_count,
                'sick': sick_count,
            },
            'milk_kpis': {
                'total_7d': round(total_milk_7d, 1),
                'avg_daily': round(avg_daily_milk, 1),
                'grade_counts': grade_counts,
            },
            'milk_trend': milk_trend,
            'upcoming_calvings': [{
                'animal': r.animal_id.display_name,
                'date': str(r.expected_calving_date),
                'days': r.days_to_calving,
            } for r in upcoming_calvings[:10]],
            'upcoming_vaccinations': [{
                'animal': r.animal_id.display_name,
                'date': str(r.next_vaccination_date),
                'vaccine': r.vaccine_name,
            } for r in upcoming_vaccinations[:10]],
        }
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Task 28.3.1: OWL Farm Dashboard component (8h)**

```javascript
/** @odoo-module **/
// static/src/js/farm_dashboard.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { registry } from "@web/core/registry";
import { useService } from "@web/core/utils/hooks";
import { loadJS } from "@web/core/assets";

class FarmDashboard extends Component {
    static template = "smart_farm_mgmt.FarmDashboard";

    setup() {
        this.rpc = useService("rpc");
        this.action = useService("action");
        this.state = useState({
            animalKpis: { total: 0, lactating: 0, pregnant: 0, sick: 0 },
            milkKpis: { total_7d: 0, avg_daily: 0, grade_counts: {} },
            milkTrend: [],
            upcomingCalvings: [],
            upcomingVaccinations: [],
            loading: true,
        });

        onWillStart(async () => {
            await loadJS("/web/static/lib/Chart/Chart.js");
            await this.loadDashboardData();
        });
    }

    async loadDashboardData() {
        try {
            const data = await this.rpc("/smart_farm/dashboard/data", {});
            Object.assign(this.state, {
                animalKpis: data.animal_kpis,
                milkKpis: data.milk_kpis,
                milkTrend: data.milk_trend,
                upcomingCalvings: data.upcoming_calvings,
                upcomingVaccinations: data.upcoming_vaccinations,
                loading: false,
            });
            this.renderCharts();
        } catch (error) {
            console.error("Dashboard load error:", error);
            this.state.loading = false;
        }
    }

    renderCharts() {
        this.renderMilkTrendChart();
        this.renderQualityPieChart();
    }

    renderMilkTrendChart() {
        const ctx = document.getElementById("milkTrendChart");
        if (!ctx) return;
        new Chart(ctx, {
            type: "line",
            data: {
                labels: this.state.milkTrend.map(d =>
                    new Date(d.date).toLocaleDateString("en-BD", {
                        month: "short", day: "numeric"
                    })
                ),
                datasets: [{
                    label: "Daily Milk (L)",
                    data: this.state.milkTrend.map(d => d.liters),
                    borderColor: "#4CAF50",
                    backgroundColor: "rgba(76, 175, 80, 0.1)",
                    tension: 0.3,
                    fill: true,
                }],
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: "Liters" } },
                },
            },
        });
    }

    renderQualityPieChart() {
        const ctx = document.getElementById("qualityPieChart");
        if (!ctx) return;
        const gc = this.state.milkKpis.grade_counts;
        new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: ["Grade A", "Grade B", "Grade C", "Rejected"],
                datasets: [{
                    data: [gc.A || 0, gc.B || 0, gc.C || 0, gc.rejected || 0],
                    backgroundColor: ["#4CAF50", "#2196F3", "#FF9800", "#F44336"],
                }],
            },
            options: {
                responsive: true,
                plugins: { legend: { position: "bottom" } },
            },
        });
    }

    onViewAnimals() {
        this.action.doAction("smart_farm_mgmt.action_farm_animal");
    }

    onViewMilkRecords() {
        this.action.doAction("smart_farm_mgmt.action_farm_milk_production");
    }
}

registry.category("actions").add("smart_farm_mgmt.dashboard", FarmDashboard);
```

```xml
<!-- static/src/xml/farm_dashboard.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<templates xml:space="preserve">
    <t t-name="smart_farm_mgmt.FarmDashboard">
        <div class="o_farm_dashboard">
            <div class="farm-kpi-cards">
                <div class="farm-kpi-card animals" t-on-click="onViewAnimals">
                    <div class="kpi-value" t-esc="state.animalKpis.total"/>
                    <div class="kpi-label">Total Animals</div>
                </div>
                <div class="farm-kpi-card milk" t-on-click="onViewMilkRecords">
                    <div class="kpi-value">
                        <t t-esc="state.milkKpis.avg_daily"/> L
                    </div>
                    <div class="kpi-label">Avg Daily Milk</div>
                </div>
                <div class="farm-kpi-card breeding">
                    <div class="kpi-value" t-esc="state.animalKpis.pregnant"/>
                    <div class="kpi-label">Pregnant Cows</div>
                </div>
                <div class="farm-kpi-card health">
                    <div class="kpi-value" t-esc="state.animalKpis.sick"/>
                    <div class="kpi-label">Sick Animals</div>
                </div>
            </div>

            <div class="farm-charts">
                <div class="farm-chart-card">
                    <div class="chart-title">Milk Production Trend (30 Days)</div>
                    <canvas id="milkTrendChart" height="200"/>
                </div>
                <div class="farm-chart-card">
                    <div class="chart-title">Milk Quality Distribution</div>
                    <canvas id="qualityPieChart" height="200"/>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="farm-chart-card">
                        <div class="chart-title">Upcoming Calvings (30 Days)</div>
                        <table class="table table-sm">
                            <thead><tr>
                                <th>Animal</th><th>Expected Date</th><th>Days</th>
                            </tr></thead>
                            <tbody>
                                <t t-foreach="state.upcomingCalvings" t-as="c" t-key="c.animal">
                                    <tr>
                                        <td t-esc="c.animal"/>
                                        <td t-esc="c.date"/>
                                        <td><span class="badge bg-warning" t-esc="c.days"/></td>
                                    </tr>
                                </t>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="farm-chart-card">
                        <div class="chart-title">Upcoming Vaccinations</div>
                        <table class="table table-sm">
                            <thead><tr>
                                <th>Animal</th><th>Vaccine</th><th>Date</th>
                            </tr></thead>
                            <tbody>
                                <t t-foreach="state.upcomingVaccinations" t-as="v" t-key="v.animal">
                                    <tr>
                                        <td t-esc="v.animal"/>
                                        <td t-esc="v.vaccine"/>
                                        <td t-esc="v.date"/>
                                    </tr>
                                </t>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </t>
</templates>
```

#### Dev 2 — Full-Stack / DevOps (8h)

- Dashboard action registration (ir.actions.client for OWL component)
- Performance optimization: cache dashboard queries with Redis (30s TTL)
- PDF report: Weekly Farm Summary (milk totals, health events, upcoming calvings)

#### End-of-Day 28 Deliverables

- [x] Farm dashboard with 4 KPI cards and 2 interactive charts
- [x] Upcoming calvings and vaccinations tables
- [x] Dashboard controller with optimized queries
- [x] Redis caching for dashboard data
- [x] Weekly farm summary PDF report

---

### Day 29 — Comprehensive Testing

**Objective:** Write and execute unit tests and integration tests for all `smart_farm_mgmt` business logic.

#### Dev 1 — Backend Lead (8h)

```python
# tests/test_farm_animal.py
from odoo.tests.common import TransactionCase
from odoo.exceptions import ValidationError
from datetime import date, timedelta

class TestFarmAnimal(TransactionCase):

    def setUp(self):
        super().setUp()
        self.breed = self.env['farm.breed'].create({
            'name': 'Test Holstein',
            'species': 'cattle',
            'avg_milk_yield': 25.0,
            'avg_fat_percentage': 3.5,
            'avg_gestation_days': 280,
        })
        self.farm = self.env['farm.farm'].create({
            'name': 'Test Farm',
            'code': 'TF01',
            'area_acres': 50,
        })
        self.animal = self.env['farm.animal'].create({
            'name': 'Bessie',
            'breed_id': self.breed.id,
            'gender': 'female',
            'date_of_birth': date.today() - timedelta(days=1095),  # ~3 years
            'farm_id': self.farm.id,
            'status': 'active',
        })

    def test_auto_tag_number(self):
        """Tag number should be auto-generated via sequence."""
        self.assertTrue(self.animal.tag_number.startswith('SD-'))

    def test_age_computation(self):
        """Age should compute correctly from date_of_birth."""
        self.assertAlmostEqual(self.animal.age_years, 3.0, delta=0.1)
        self.assertIn('y', self.animal.age_display)

    def test_future_dob_raises(self):
        """Future date of birth should raise ValidationError."""
        with self.assertRaises(ValidationError):
            self.env['farm.animal'].create({
                'name': 'FutureAnimal',
                'breed_id': self.breed.id,
                'gender': 'female',
                'date_of_birth': date.today() + timedelta(days=10),
                'farm_id': self.farm.id,
            })

    def test_lifecycle_transitions(self):
        """Test animal status transitions."""
        self.animal.action_set_dry()
        self.assertEqual(self.animal.status, 'dry')
        self.animal.action_set_pregnant()
        self.assertEqual(self.animal.status, 'pregnant')
        self.animal.action_set_active()
        self.assertEqual(self.animal.status, 'active')

    def test_unique_tag_number(self):
        """Duplicate tag numbers should raise."""
        with self.assertRaises(Exception):
            self.env['farm.animal'].create({
                'name': 'Duplicate',
                'tag_number': self.animal.tag_number,
                'breed_id': self.breed.id,
                'gender': 'male',
                'date_of_birth': date.today() - timedelta(days=365),
                'farm_id': self.farm.id,
            })


class TestMilkQualityGrading(TransactionCase):

    def setUp(self):
        super().setUp()
        self.breed = self.env['farm.breed'].create({
            'name': 'Grade Test Breed', 'species': 'cattle',
        })
        self.farm = self.env['farm.farm'].create({
            'name': 'GF', 'code': 'GF01',
        })
        self.cow = self.env['farm.animal'].create({
            'name': 'GradeCow', 'breed_id': self.breed.id,
            'gender': 'female', 'farm_id': self.farm.id,
            'date_of_birth': date.today() - timedelta(days=1460),
        })

    def _create_milk(self, fat, snf, scc, temp):
        return self.env['farm.milk.production'].create({
            'animal_id': self.cow.id,
            'date': date.today(),
            'session': 'morning',
            'quantity_liters': 12.0,
            'fat_percentage': fat,
            'snf_percentage': snf,
            'somatic_cell_count': scc,
            'temperature': temp,
        })

    def test_grade_a(self):
        rec = self._create_milk(fat=4.0, snf=8.8, scc=150, temp=6.0)
        self.assertEqual(rec.quality_grade, 'A')

    def test_grade_b(self):
        rec = self._create_milk(fat=3.2, snf=8.2, scc=350, temp=12.0)
        self.assertEqual(rec.quality_grade, 'B')

    def test_grade_c(self):
        rec = self._create_milk(fat=2.6, snf=7.6, scc=600, temp=20.0)
        self.assertEqual(rec.quality_grade, 'C')

    def test_rejected(self):
        rec = self._create_milk(fat=2.0, snf=7.0, scc=800, temp=25.0)
        self.assertEqual(rec.quality_grade, 'rejected')


class TestBreedingGestation(TransactionCase):

    def setUp(self):
        super().setUp()
        self.breed = self.env['farm.breed'].create({
            'name': 'Gestation Breed', 'species': 'cattle',
            'avg_gestation_days': 280,
        })
        self.farm = self.env['farm.farm'].create({
            'name': 'BF', 'code': 'BF01',
        })
        self.cow = self.env['farm.animal'].create({
            'name': 'BreedCow', 'breed_id': self.breed.id,
            'gender': 'female', 'farm_id': self.farm.id,
            'date_of_birth': date.today() - timedelta(days=1095),
        })

    def test_gestation_calculator(self):
        """Expected calving = breeding date + breed gestation days."""
        breeding_date = date(2026, 1, 1)
        rec = self.env['farm.breeding.record'].create({
            'animal_id': self.cow.id,
            'date': breeding_date,
            'breeding_type': 'artificial',
            'pregnancy_confirmed': True,
        })
        expected = breeding_date + timedelta(days=280)
        self.assertEqual(rec.expected_calving_date, expected)
        self.assertEqual(rec.gestation_days, 280)

    def test_no_gestation_without_confirmation(self):
        """Gestation should not compute if pregnancy not confirmed."""
        rec = self.env['farm.breeding.record'].create({
            'animal_id': self.cow.id,
            'date': date.today(),
            'breeding_type': 'artificial',
            'pregnancy_confirmed': False,
        })
        self.assertFalse(rec.expected_calving_date)
        self.assertEqual(rec.gestation_days, 0)


class TestFeedCost(TransactionCase):

    def setUp(self):
        super().setUp()
        self.product_hay = self.env['product.product'].create({
            'name': 'Rice Straw', 'type': 'consu',
        })
        self.product_concentrate = self.env['product.product'].create({
            'name': 'Dairy Concentrate', 'type': 'consu',
        })

    def test_ration_total_cost(self):
        ration = self.env['farm.feed.ration'].create({
            'name': 'Test Lactating Ration',
            'animal_category': 'lactating',
            'line_ids': [
                (0, 0, {'product_id': self.product_hay.id,
                         'quantity_kg': 10.0, 'unit_cost': 5.0}),
                (0, 0, {'product_id': self.product_concentrate.id,
                         'quantity_kg': 4.0, 'unit_cost': 35.0}),
            ],
        })
        # 10×5 + 4×35 = 50 + 140 = 190 BDT/day
        self.assertEqual(ration.total_quantity_kg, 14.0)
        self.assertAlmostEqual(ration.total_cost_per_day, 190.0, places=2)
```

#### Dev 2 — Full-Stack / DevOps (8h)

- Integration test: Animal → Milk → Quality → Inventory flow
- Test coverage report generation and analysis
- Performance profiling of dashboard queries

#### Dev 3 — Frontend / Mobile Lead (8h)

- OWL component QUnit tests for dashboard widgets
- Cross-browser testing (Chrome, Firefox, Edge)
- Responsive design verification (mobile, tablet, desktop)

#### End-of-Day 29 Deliverables

- [x] 20+ unit tests covering all critical business logic
- [x] Milk quality grading tests (4 grades verified)
- [x] Gestation calculator accuracy verified for multiple breeds
- [x] Feed cost computation tests passing
- [x] Integration test for animal-to-milk-to-inventory flow
- [x] Test coverage report: target ≥ 80% on custom code

---

### Day 30 — Milestone 3 Review & Stakeholder Demo

**Objective:** Conduct milestone review, demonstrate the complete farm management module, and retrospective.

#### All Developers (8h each)

**Morning (4h): Final polish and demo preparation**

- Fix any remaining test failures
- Prepare demo script with realistic Smart Dairy data (75 lactating cows, 900L target)
- Set up demo environment with sample data loaded

**Afternoon (4h): Stakeholder demo and retrospective**

Demo Walkthrough Script:
1. **Animal Registry** (15 min): Register new cow, assign RFID, show kanban by status
2. **Health Management** (10 min): Record vaccination, create treatment, show health timeline
3. **Milk Collection** (15 min): Morning session bulk entry, show quality grading, view analytics
4. **Breeding** (10 min): Record AI, confirm pregnancy, show expected calving calendar
5. **Feed Management** (10 min): Show ration plan for lactating cows, record daily feed, cost analysis
6. **Equipment** (5 min): Show equipment registry, maintenance schedule, depreciation
7. **Dashboard** (10 min): Full dashboard walkthrough with charts and KPIs
8. **Q&A** (15 min)

#### End-of-Day 30 Deliverables

- [x] All tests passing (0 failures)
- [x] Stakeholder demo completed with positive feedback
- [x] Sprint retrospective documented
- [x] Known issues logged in GitHub Issues
- [x] Milestone 3 sign-off obtained
- [x] Handoff notes prepared for Milestone 4 (Database Optimization)

---

## 4. Technical Specifications

### 4.1 Model Relationships

```
farm.farm (1) ──── (N) farm.animal
farm.breed (1) ──── (N) farm.animal
farm.animal (1) ──── (N) farm.health.record
farm.animal (1) ──── (N) farm.milk.production
farm.animal (1) ──── (N) farm.breeding.record
farm.animal (1) ──── (N) farm.feed.record
farm.feed.ration (1) ──── (N) farm.feed.ration.line
farm.animal (sire) ──── (N) farm.animal (offspring)
farm.animal (dam) ──── (N) farm.animal (offspring)
```

### 4.2 Database Indexes

```sql
-- Performance-critical indexes for farm module
CREATE INDEX idx_farm_animal_status ON farm_animal(status);
CREATE INDEX idx_farm_animal_farm_id ON farm_animal(farm_id);
CREATE INDEX idx_farm_animal_breed_id ON farm_animal(breed_id);
CREATE INDEX idx_farm_animal_tag ON farm_animal(tag_number);
CREATE INDEX idx_farm_milk_animal_date ON farm_milk_production(animal_id, date);
CREATE INDEX idx_farm_milk_date ON farm_milk_production(date);
CREATE INDEX idx_farm_health_animal_date ON farm_health_record(animal_id, date);
CREATE INDEX idx_farm_breeding_animal ON farm_breeding_record(animal_id);
CREATE INDEX idx_farm_breeding_expected ON farm_breeding_record(expected_calving_date)
    WHERE actual_calving_date IS NULL;
CREATE INDEX idx_farm_feed_animal_date ON farm_feed_record(animal_id, date);
```

### 4.3 API Endpoints (Preview for M9 FastAPI Integration)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/animals` | List animals with pagination/filtering |
| GET | `/api/v1/animals/{id}` | Animal detail with related records |
| POST | `/api/v1/milk-production` | Record milk production |
| GET | `/api/v1/dashboard/kpis` | Dashboard KPIs |
| GET | `/api/v1/breeding/upcoming-calvings` | Upcoming calving events |

---

## 5. Testing & Validation

### 5.1 Test Strategy

| Test Type | Count | Tool | Owner |
|-----------|-------|------|-------|
| Unit Tests | 25+ | pytest / Odoo TestCase | Dev 1, Dev 2 |
| Integration Tests | 8 | Odoo TransactionCase | Dev 2 |
| UI/OWL Tests | 5 | QUnit | Dev 3 |
| Manual Acceptance | 10 scenarios | Demo walkthrough | All |

### 5.2 Acceptance Criteria

1. **Animal CRUD**: Create, read, update, delete animals with all fields
2. **Status Machine**: All state transitions work correctly
3. **Milk Grading**: Correct grades for 4 quality tiers verified with test data
4. **Gestation**: ±1 day accuracy for 5 different breed gestation periods
5. **Feed Cost**: Ration cost computation matches manual calculation
6. **Dashboard**: All 4 KPI cards and 2 charts render with data
7. **Security**: RBAC enforced — unauthorized users cannot access restricted records
8. **Performance**: Animal list with 255 records loads in < 2 seconds

---

## 6. Risk & Mitigation

| # | Risk | Probability | Impact | Mitigation |
|---|------|-------------|--------|------------|
| R3.1 | Complex model relationships cause circular dependencies | Medium | High | Careful model loading order in `__init__.py`, use `@api.depends` judiciously |
| R3.2 | Milk quality grading logic doesn't match stakeholder expectations | Medium | Medium | Validate grading thresholds with farm management during Day 24 before finalizing |
| R3.3 | OWL dashboard performance with large datasets | Low | Medium | Use server-side aggregation in controller, Redis caching, paginated chart data |
| R3.4 | Breed-specific gestation data inaccuracy | Low | High | Cross-reference with Bangladesh Livestock Research Institute data |
| R3.5 | RFID tag integration complexity | Medium | Low | Phase 1 = text field only; hardware integration deferred to Phase 3 (IoT milestone) |
| R3.6 | Feed product categories not aligned with Inventory module | Medium | Medium | Coordinate with M2 inventory categories on Day 26, use `product.product` domain filter |
| R3.7 | Test data insufficient for realistic dashboard | Low | Low | Create comprehensive seed script with 6 months of simulated data |

---

## 7. Dependencies & Handoffs

### 7.1 Inputs (from M1 & M2)

| Dependency | Source | Status Required |
|-----------|--------|-----------------|
| Docker environment operational | M1 | Complete |
| Git repo with GitFlow | M1 | Complete |
| CI/CD pipeline active | M1 | Complete |
| Core Odoo modules installed (Sales, Inventory, Accounting, HR) | M2 | Complete |
| Product categories for feed and dairy products | M2 | Complete |
| Bangladesh tax configuration | M2 | Complete |

### 7.2 Outputs (to M4 & Beyond)

| Deliverable | Consumer | Description |
|------------|----------|-------------|
| `smart_farm_mgmt` module | M4 (DB Optimization) | Optimize farm module queries and indexes |
| Farm data models | M5 (Integration Testing) | Integration test targets |
| Dashboard controller | M9 (API Security) | Secure with OAuth 2.0/JWT |
| RBAC groups | M7 (IAM Implementation) | Extend with MFA and session management |
| OWL components | M10 (Testing) | E2E test targets for farm workflows |

---

## Appendix A: Implementation Guide References

| Guide ID | Guide Title | Relevance |
|----------|-------------|-----------|
| B-001 | Test-Driven Development (TDD) | All test-first development in Day 29 |
| B-005 | Odoo Module Development Standards | Module scaffold, model patterns |
| B-007 | API Gateway Design | Dashboard controller patterns |
| C-001 | Database Design | Index strategy, schema design |
| C-003 | Data Dictionary | Field definitions, data types |
| D-001 | Infrastructure | Docker, CI/CD pipeline |
| E-001 | API Specification | REST endpoint design |
| F-001 | Security Architecture | RBAC groups, access controls |

---

## Appendix B: Glossary

| Term | Definition |
|------|-----------|
| DIM | Days in Milk — days since last calving |
| SCC | Somatic Cell Count — indicator of udder health |
| SNF | Solids-Not-Fat — milk solids excluding fat |
| AI | Artificial Insemination |
| BCS | Body Condition Score (1-5 scale) |
| FEFO | First Expired, First Out — inventory strategy |
| RFID | Radio-Frequency Identification |
| BDT | Bangladeshi Taka (currency) |

---

*Document generated as part of Phase 1: Foundation — Smart Dairy Digital Smart Portal + ERP Implementation Roadmap.*
