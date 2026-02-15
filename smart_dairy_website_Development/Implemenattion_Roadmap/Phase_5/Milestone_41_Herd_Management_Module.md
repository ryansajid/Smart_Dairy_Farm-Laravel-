# Milestone 41: Herd Management Module

## Smart Dairy Digital Smart Portal + ERP - Phase 5: Farm Management Foundation

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 41 of 150 (1 of 10 in Phase 5)                                         |
| **Title**        | Herd Management Module                                                 |
| **Phase**        | Phase 5 - Farm Management Foundation                                   |
| **Days**         | Days 251-260 (of 350 total)                                            |
| **Duration**     | 10 working days                                                        |
| **Version**      | 1.0                                                                    |
| **Status**       | Draft                                                                  |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack), Dev 3 (Mobile Lead)          |

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

Create a comprehensive digital herd management system that enables Smart Dairy to register, track, and manage 1000+ animals with complete genealogy, RFID integration, and location-based management capabilities.

### 1.2 Objectives

1. Design and implement the core FarmAnimal model with 50+ fields
2. Create breed and species master data models
3. Implement farm location and pen management hierarchy
4. Build genealogy tracking with pedigree analysis
5. Integrate RFID scanning for animal identification
6. Implement barcode/QR code generation for ear tags
7. Create animal grouping and categorization system
8. Build animal transfer system between locations
9. Implement photo documentation and image management
10. Develop web UI components for animal management

### 1.3 Key Deliverables

| Deliverable                     | Owner  | Format            | Due Day |
| ------------------------------- | ------ | ----------------- | ------- |
| FarmAnimal Odoo Model           | Dev 1  | Python module     | 251     |
| FarmBreed Model                 | Dev 1  | Python module     | 252     |
| FarmLocation & FarmPen Models   | Dev 1  | Python module     | 253     |
| Genealogy & Pedigree System     | Dev 1  | Python module     | 254     |
| RFID Integration API            | Dev 1  | REST endpoints    | 255     |
| Barcode/QR Generation           | Dev 2  | Python library    | 256     |
| Animal Grouping System          | Dev 1  | Python module     | 257     |
| Transfer Management Wizard      | Dev 1  | Python wizard     | 258     |
| Photo Upload System             | Dev 2  | File handling     | 259     |
| Animal List/Form UI             | Dev 3  | OWL components    | 260     |
| Flutter Animal Scanner UI       | Dev 3  | Dart/Flutter      | 259     |

### 1.4 Prerequisites

- Phase 4 complete and stable
- Odoo 19 CE development environment operational
- PostgreSQL 16 database configured
- RFID readers procured (minimum 2 units for testing)
- Flutter development environment set up
- VS Code with Odoo/Flutter extensions installed

### 1.5 Success Criteria

- [ ] FarmAnimal model supports 1000+ records with <2s query response
- [ ] All animal types (dairy cow, heifer, calf, bull) correctly categorized
- [ ] Genealogy tracks 5+ generations with pedigree calculation
- [ ] RFID scanning returns animal data within 500ms
- [ ] Bangladesh ear tag format (BD-XXXX-XXXXXX) validated
- [ ] Animal transfers maintain complete movement history
- [ ] Photo upload and thumbnail generation working
- [ ] Unit test coverage >80% for all models
- [ ] No critical bugs open

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference        |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------- |
| RFP-FARM-001 | RFP    | Animal registration (1000+ capacity)     | 251     | FarmAnimal model      |
| RFP-FARM-002 | RFP    | Genealogy and pedigree tracking          | 254     | Pedigree system       |
| RFP-FARM-003 | RFP    | RFID/ear tag integration                 | 255-256 | RFID API, QR gen      |
| BRD-FM-001   | BRD    | Manage 1000+ animals digitally           | 251-260 | Complete milestone    |
| SRS-FM-001   | SRS    | FarmAnimal model (50+ fields)            | 251     | FarmAnimal class      |
| SRS-FM-002   | SRS    | RFID REST API endpoints                  | 255     | /farm/rfid/* APIs     |
| SRS-FM-003   | SRS    | Pedigree calculation algorithm           | 254     | _compute_pedigree()   |

---

## 3. Day-by-Day Breakdown

### Day 251 - Animal Core Model & Database Design

**Objective:** Establish the foundational FarmAnimal model with comprehensive field structure supporting all animal lifecycle stages.

#### Dev 1 - Backend Lead (8h)

**Task 1: Create FarmAnimal Core Model (6h)**

```python
# odoo/addons/smart_dairy_farm/models/farm_animal.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
import re
from datetime import date

class FarmAnimal(models.Model):
    _name = 'farm.animal'
    _description = 'Farm Animal Registration'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'registration_number desc'
    _rec_name = 'display_name'

    # ===== BASIC INFORMATION =====
    name = fields.Char(
        string='Animal Name',
        required=True,
        tracking=True,
        help='Common name or identifier for the animal'
    )
    display_name = fields.Char(
        compute='_compute_display_name',
        store=True
    )
    registration_number = fields.Char(
        string='Registration Number',
        required=True,
        copy=False,
        readonly=True,
        default=lambda self: self._generate_registration_number(),
        tracking=True,
        index=True
    )
    ear_tag = fields.Char(
        string='Ear Tag Number',
        required=True,
        copy=False,
        tracking=True,
        index=True,
        help='Physical ear tag identifier (BD-XXXX-XXXXXX format)'
    )
    rfid_tag = fields.Char(
        string='RFID Tag',
        copy=False,
        tracking=True,
        index=True,
        help='Electronic RFID identifier (15-digit ISO standard)'
    )

    # ===== CLASSIFICATION =====
    species = fields.Selection([
        ('cattle', 'Cattle'),
        ('buffalo', 'Buffalo'),
        ('goat', 'Goat'),
    ], string='Species', required=True, default='cattle', tracking=True)

    breed_id = fields.Many2one(
        'farm.breed',
        string='Breed',
        required=True,
        tracking=True,
        domain="[('species', '=', species)]"
    )

    gender = fields.Selection([
        ('female', 'Female'),
        ('male', 'Male'),
    ], string='Gender', required=True, tracking=True)

    animal_type = fields.Selection([
        ('dairy_cow', 'Dairy Cow'),
        ('dairy_heifer', 'Dairy Heifer'),
        ('calf_female', 'Female Calf'),
        ('calf_male', 'Male Calf'),
        ('bull', 'Bull'),
        ('ox', 'Ox'),
        ('buffalo_cow', 'Buffalo Cow'),
        ('buffalo_heifer', 'Buffalo Heifer'),
        ('goat_doe', 'Goat Doe'),
        ('goat_buck', 'Goat Buck'),
    ], string='Animal Type', compute='_compute_animal_type', store=True, tracking=True)

    # ===== DATES & AGE =====
    date_of_birth = fields.Date(
        string='Date of Birth',
        required=True,
        tracking=True
    )
    age_days = fields.Integer(
        string='Age (Days)',
        compute='_compute_age',
        store=True
    )
    age_months = fields.Integer(
        string='Age (Months)',
        compute='_compute_age',
        store=True
    )
    age_years = fields.Float(
        string='Age (Years)',
        compute='_compute_age',
        store=True,
        digits=(4, 1)
    )
    age_display = fields.Char(
        string='Age',
        compute='_compute_age',
        store=True
    )
    date_arrived = fields.Date(
        string='Date Arrived at Farm',
        default=fields.Date.today,
        tracking=True
    )

    # ===== GENEALOGY =====
    dam_id = fields.Many2one(
        'farm.animal',
        string='Dam (Mother)',
        domain="[('gender', '=', 'female'), ('id', '!=', id)]",
        tracking=True
    )
    sire_id = fields.Many2one(
        'farm.animal',
        string='Sire (Father)',
        domain="[('gender', '=', 'male'), ('id', '!=', id)]",
        tracking=True
    )
    sire_external = fields.Char(
        string='External Sire ID',
        help='Sire ID if from external source (AI straw)'
    )
    offspring_ids = fields.One2many(
        'farm.animal',
        compute='_compute_offspring_ids',
        string='Offspring'
    )
    offspring_count = fields.Integer(
        string='Number of Offspring',
        compute='_compute_offspring_count'
    )
    generation = fields.Integer(
        string='Generation',
        compute='_compute_generation',
        store=True
    )
    inbreeding_coefficient = fields.Float(
        string='Inbreeding Coefficient (%)',
        compute='_compute_inbreeding',
        store=True,
        digits=(5, 2)
    )

    # ===== FARM LOCATION =====
    farm_id = fields.Many2one(
        'farm.location',
        string='Farm',
        required=True,
        tracking=True,
        domain="[('location_type', '=', 'farm')]"
    )
    pen_id = fields.Many2one(
        'farm.pen',
        string='Pen/Shed',
        tracking=True,
        domain="[('farm_id', '=', farm_id)]"
    )
    location_history_ids = fields.One2many(
        'farm.animal.transfer',
        'animal_id',
        string='Location History'
    )

    # ===== STATUS =====
    status = fields.Selection([
        ('active', 'Active'),
        ('pregnant', 'Pregnant'),
        ('lactating', 'Lactating'),
        ('dry', 'Dry'),
        ('sick', 'Sick'),
        ('quarantine', 'Quarantine'),
        ('sold', 'Sold'),
        ('culled', 'Culled'),
        ('dead', 'Dead'),
    ], string='Status', default='active', required=True, tracking=True)

    is_active = fields.Boolean(
        string='Is Active',
        compute='_compute_is_active',
        store=True,
        index=True
    )
    active = fields.Boolean(
        string='Active Record',
        default=True
    )

    # ===== PHYSICAL ATTRIBUTES =====
    color_marking = fields.Char(string='Color/Marking')
    weight_kg = fields.Float(
        string='Current Weight (kg)',
        tracking=True,
        digits=(6, 2)
    )
    weight_date = fields.Date(string='Weight Recorded Date')
    height_cm = fields.Float(
        string='Height (cm)',
        digits=(5, 1)
    )
    body_condition_score = fields.Selection([
        ('1', '1 - Emaciated'),
        ('2', '2 - Thin'),
        ('3', '3 - Average'),
        ('4', '4 - Fat'),
        ('5', '5 - Obese'),
    ], string='Body Condition Score')

    # ===== IMAGES =====
    image = fields.Image(
        string='Photo',
        max_width=1920,
        max_height=1080
    )
    image_medium = fields.Image(
        string='Medium Photo',
        related='image',
        max_width=256,
        max_height=256,
        store=True
    )
    image_small = fields.Image(
        string='Thumbnail',
        related='image',
        max_width=64,
        max_height=64,
        store=True
    )

    # ===== ACQUISITION =====
    acquisition_type = fields.Selection([
        ('born', 'Born on Farm'),
        ('purchased', 'Purchased'),
        ('donated', 'Donated'),
        ('inherited', 'Inherited'),
        ('transferred', 'Transferred In'),
    ], string='Acquisition Type', default='born', tracking=True)

    purchase_date = fields.Date(string='Purchase Date')
    purchase_price = fields.Monetary(
        string='Purchase Price',
        currency_field='currency_id'
    )
    vendor_id = fields.Many2one('res.partner', string='Vendor/Source')
    purchase_notes = fields.Text(string='Purchase Notes')

    # ===== DISPOSAL =====
    disposal_date = fields.Date(string='Disposal Date', tracking=True)
    disposal_type = fields.Selection([
        ('sold', 'Sold'),
        ('culled', 'Culled'),
        ('died', 'Died'),
        ('donated', 'Donated'),
        ('transferred', 'Transferred Out'),
    ], string='Disposal Type', tracking=True)
    disposal_price = fields.Monetary(
        string='Sale Price',
        currency_field='currency_id'
    )
    disposal_reason = fields.Text(string='Disposal Reason')
    buyer_id = fields.Many2one('res.partner', string='Buyer')

    # ===== RELATED RECORDS (One2many) =====
    health_record_ids = fields.One2many(
        'farm.health.record',
        'animal_id',
        string='Health Records'
    )
    health_record_count = fields.Integer(
        compute='_compute_record_counts'
    )
    vaccination_ids = fields.One2many(
        'farm.vaccination',
        'animal_id',
        string='Vaccinations'
    )
    breeding_record_ids = fields.One2many(
        'farm.breeding.record',
        'animal_id',
        string='Breeding Records'
    )
    breeding_record_count = fields.Integer(
        compute='_compute_record_counts'
    )
    milk_production_ids = fields.One2many(
        'farm.milk.production',
        'animal_id',
        string='Milk Production Records'
    )
    milk_production_count = fields.Integer(
        compute='_compute_record_counts'
    )
    weight_history_ids = fields.One2many(
        'farm.animal.weight',
        'animal_id',
        string='Weight History'
    )

    # ===== ANIMAL GROUPS =====
    group_ids = fields.Many2many(
        'farm.animal.group',
        'farm_animal_group_rel',
        'animal_id',
        'group_id',
        string='Groups'
    )

    # ===== PRODUCTION METRICS =====
    total_milk_yield = fields.Float(
        string='Total Lifetime Milk (L)',
        compute='_compute_production_metrics',
        store=True
    )
    avg_daily_yield = fields.Float(
        string='Average Daily Yield (L)',
        compute='_compute_production_metrics',
        store=True,
        digits=(5, 2)
    )
    lactation_count = fields.Integer(
        string='Number of Lactations',
        compute='_compute_production_metrics',
        store=True
    )

    # ===== COMPANY & CURRENCY =====
    company_id = fields.Many2one(
        'res.company',
        string='Company',
        default=lambda self: self.env.company,
        required=True
    )
    currency_id = fields.Many2one(
        related='company_id.currency_id',
        string='Currency'
    )

    # ===== NOTES =====
    notes = fields.Html(string='Notes')

    # ===== SQL CONSTRAINTS =====
    _sql_constraints = [
        ('registration_number_unique',
         'unique(registration_number, company_id)',
         'Registration number must be unique per company!'),
        ('ear_tag_unique',
         'unique(ear_tag, company_id)',
         'Ear tag number must be unique per company!'),
        ('rfid_tag_unique',
         'unique(rfid_tag, company_id)',
         'RFID tag must be unique per company!'),
    ]

    # ===== COMPUTED METHODS =====
    @api.depends('name', 'registration_number', 'ear_tag')
    def _compute_display_name(self):
        for record in self:
            record.display_name = f"[{record.ear_tag}] {record.name}"

    @api.model
    def _generate_registration_number(self):
        """Generate unique registration number: ANM-YYYYMMDD-XXXX"""
        today = date.today().strftime('%Y%m%d')
        sequence = self.env['ir.sequence'].next_by_code('farm.animal.registration')
        return f"ANM-{today}-{sequence or '0001'}"

    @api.depends('date_of_birth')
    def _compute_age(self):
        """Calculate animal age in days, months, and years"""
        today = date.today()
        for record in self:
            if record.date_of_birth:
                delta = today - record.date_of_birth
                record.age_days = delta.days
                record.age_months = int(delta.days / 30.44)
                record.age_years = round(delta.days / 365.25, 1)

                # Human-readable age display
                years = int(record.age_years)
                months = record.age_months % 12
                if years > 0:
                    record.age_display = f"{years}y {months}m"
                else:
                    record.age_display = f"{record.age_months}m"
            else:
                record.age_days = 0
                record.age_months = 0
                record.age_years = 0.0
                record.age_display = "Unknown"

    @api.depends('gender', 'age_months', 'species')
    def _compute_animal_type(self):
        """Auto-categorize animal based on gender, age, and species"""
        for record in self:
            if record.species == 'cattle':
                if record.gender == 'female':
                    if record.age_months < 12:
                        record.animal_type = 'calf_female'
                    elif record.age_months < 24:
                        record.animal_type = 'dairy_heifer'
                    else:
                        record.animal_type = 'dairy_cow'
                else:  # male
                    if record.age_months < 12:
                        record.animal_type = 'calf_male'
                    else:
                        record.animal_type = 'bull'
            elif record.species == 'buffalo':
                if record.gender == 'female':
                    if record.age_months < 24:
                        record.animal_type = 'buffalo_heifer'
                    else:
                        record.animal_type = 'buffalo_cow'
                else:
                    record.animal_type = 'bull'
            elif record.species == 'goat':
                if record.gender == 'female':
                    record.animal_type = 'goat_doe'
                else:
                    record.animal_type = 'goat_buck'

    @api.depends('status')
    def _compute_is_active(self):
        """Determine if animal is actively on farm"""
        inactive_statuses = ['sold', 'culled', 'dead']
        for record in self:
            record.is_active = record.status not in inactive_statuses

    def _compute_offspring_ids(self):
        """Get all offspring (both as dam and sire)"""
        for record in self:
            if record.gender == 'female':
                record.offspring_ids = self.search([('dam_id', '=', record.id)])
            else:
                record.offspring_ids = self.search([('sire_id', '=', record.id)])

    @api.depends('dam_id', 'sire_id')
    def _compute_offspring_count(self):
        """Count offspring"""
        for record in self:
            if record.gender == 'female':
                record.offspring_count = self.search_count([('dam_id', '=', record.id)])
            else:
                record.offspring_count = self.search_count([('sire_id', '=', record.id)])

    @api.depends('dam_id', 'sire_id')
    def _compute_generation(self):
        """Calculate generation number"""
        for record in self:
            record.generation = record._get_generation_recursive(set())

    def _get_generation_recursive(self, visited):
        """Recursive generation calculation with cycle detection"""
        if self.id in visited:
            return 0
        visited.add(self.id)

        dam_gen = self.dam_id._get_generation_recursive(visited) if self.dam_id else 0
        sire_gen = self.sire_id._get_generation_recursive(visited) if self.sire_id else 0

        return max(dam_gen, sire_gen) + 1

    @api.depends('dam_id', 'sire_id')
    def _compute_inbreeding(self):
        """Calculate inbreeding coefficient using Wright's formula"""
        for record in self:
            record.inbreeding_coefficient = record._calculate_inbreeding_coefficient()

    def _calculate_inbreeding_coefficient(self, generations=5):
        """
        Calculate inbreeding coefficient using simplified Wright's formula.
        F(x) = sum of [(1/2)^(n1+n2+1) * (1 + F(A))] for each common ancestor A
        """
        if not self.dam_id or not self.sire_id:
            return 0.0

        # Get ancestors for both parents
        dam_ancestors = self._get_ancestors(self.dam_id, generations)
        sire_ancestors = self._get_ancestors(self.sire_id, generations)

        # Find common ancestors
        common = set(dam_ancestors.keys()) & set(sire_ancestors.keys())

        if not common:
            return 0.0

        # Calculate inbreeding coefficient
        coefficient = 0.0
        for ancestor_id in common:
            n1 = dam_ancestors[ancestor_id]  # generations from dam
            n2 = sire_ancestors[ancestor_id]  # generations from sire
            contribution = (0.5 ** (n1 + n2 + 1))
            coefficient += contribution

        return round(coefficient * 100, 2)  # Return as percentage

    def _get_ancestors(self, animal, max_generations, current_gen=1):
        """Get ancestors with generation distances"""
        ancestors = {}
        if current_gen > max_generations or not animal:
            return ancestors

        if animal.dam_id:
            ancestors[animal.dam_id.id] = current_gen
            ancestors.update(self._get_ancestors(animal.dam_id, max_generations, current_gen + 1))

        if animal.sire_id:
            ancestors[animal.sire_id.id] = current_gen
            ancestors.update(self._get_ancestors(animal.sire_id, max_generations, current_gen + 1))

        return ancestors

    def _compute_record_counts(self):
        """Compute counts for related records"""
        for record in self:
            record.health_record_count = len(record.health_record_ids)
            record.breeding_record_count = len(record.breeding_record_ids)
            record.milk_production_count = len(record.milk_production_ids)

    @api.depends('milk_production_ids', 'milk_production_ids.yield_liters')
    def _compute_production_metrics(self):
        """Calculate production metrics from milk records"""
        for record in self:
            productions = record.milk_production_ids
            record.total_milk_yield = sum(productions.mapped('yield_liters'))
            if productions:
                days = len(set(productions.mapped('production_date')))
                record.avg_daily_yield = record.total_milk_yield / days if days else 0
            else:
                record.avg_daily_yield = 0
            record.lactation_count = len(set(productions.mapped('lactation_id')))

    # ===== CONSTRAINT METHODS =====
    @api.constrains('ear_tag')
    def _check_ear_tag_format(self):
        """Validate Bangladesh ear tag format: BD-XXXX-XXXXXX"""
        for record in self:
            if record.ear_tag:
                pattern = r'^BD-\d{4}-\d{6}$'
                if not re.match(pattern, record.ear_tag):
                    raise ValidationError(
                        f'Ear tag must follow Bangladesh format: BD-XXXX-XXXXXX\n'
                        f'Example: BD-2024-000001\n'
                        f'Current value: {record.ear_tag}'
                    )

    @api.constrains('rfid_tag')
    def _check_rfid_format(self):
        """Validate RFID tag format (15-digit ISO standard)"""
        for record in self:
            if record.rfid_tag:
                if not re.match(r'^\d{15}$', record.rfid_tag):
                    raise ValidationError(
                        'RFID tag must be a 15-digit number (ISO 11784/11785 standard)'
                    )

    @api.constrains('date_of_birth')
    def _check_date_of_birth(self):
        """Validate date of birth is not in future"""
        for record in self:
            if record.date_of_birth and record.date_of_birth > date.today():
                raise ValidationError('Date of birth cannot be in the future!')

    @api.constrains('dam_id', 'sire_id')
    def _check_genealogy(self):
        """Prevent circular references and validate parents"""
        for record in self:
            if record.dam_id:
                if record.dam_id.id == record.id:
                    raise ValidationError('Animal cannot be its own mother!')
                if record.dam_id.gender != 'female':
                    raise ValidationError('Dam (mother) must be female!')
            if record.sire_id:
                if record.sire_id.id == record.id:
                    raise ValidationError('Animal cannot be its own father!')
                if record.sire_id.gender != 'male':
                    raise ValidationError('Sire (father) must be male!')

    @api.constrains('weight_kg')
    def _check_weight(self):
        """Validate weight is reasonable"""
        for record in self:
            if record.weight_kg:
                if record.weight_kg < 0:
                    raise ValidationError('Weight cannot be negative!')
                if record.species == 'cattle' and record.weight_kg > 1500:
                    raise ValidationError('Weight exceeds maximum for cattle (1500 kg)')
                if record.species == 'goat' and record.weight_kg > 150:
                    raise ValidationError('Weight exceeds maximum for goat (150 kg)')

    # ===== ACTION METHODS =====
    def action_view_health_records(self):
        """Open health records for this animal"""
        return {
            'name': f'Health Records - {self.name}',
            'type': 'ir.actions.act_window',
            'res_model': 'farm.health.record',
            'view_mode': 'tree,form',
            'domain': [('animal_id', '=', self.id)],
            'context': {'default_animal_id': self.id}
        }

    def action_view_breeding_records(self):
        """Open breeding records for this animal"""
        return {
            'name': f'Breeding Records - {self.name}',
            'type': 'ir.actions.act_window',
            'res_model': 'farm.breeding.record',
            'view_mode': 'tree,form',
            'domain': [('animal_id', '=', self.id)],
            'context': {'default_animal_id': self.id}
        }

    def action_view_milk_production(self):
        """Open milk production records for this animal"""
        return {
            'name': f'Milk Production - {self.name}',
            'type': 'ir.actions.act_window',
            'res_model': 'farm.milk.production',
            'view_mode': 'tree,form,graph',
            'domain': [('animal_id', '=', self.id)],
            'context': {'default_animal_id': self.id}
        }

    def action_view_offspring(self):
        """Open offspring list view"""
        domain = [('dam_id', '=', self.id)] if self.gender == 'female' else [('sire_id', '=', self.id)]
        return {
            'name': f'Offspring of {self.name}',
            'type': 'ir.actions.act_window',
            'res_model': 'farm.animal',
            'view_mode': 'tree,form',
            'domain': domain,
            'context': {
                'default_dam_id': self.id if self.gender == 'female' else False,
                'default_sire_id': self.id if self.gender == 'male' else False,
                'default_farm_id': self.farm_id.id
            }
        }

    def action_view_family_tree(self):
        """Open family tree visualization"""
        return {
            'name': f'Family Tree - {self.name}',
            'type': 'ir.actions.act_window',
            'res_model': 'farm.animal',
            'view_mode': 'form',
            'res_id': self.id,
            'view_id': self.env.ref('smart_dairy_farm.view_animal_family_tree').id,
            'target': 'new'
        }

    def action_transfer(self):
        """Open transfer wizard"""
        return {
            'name': 'Transfer Animal',
            'type': 'ir.actions.act_window',
            'res_model': 'farm.animal.transfer.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {
                'default_animal_ids': [(6, 0, self.ids)],
                'default_source_farm_id': self.farm_id.id,
                'default_source_pen_id': self.pen_id.id
            }
        }

    def action_mark_status(self, new_status):
        """Generic status change action"""
        self.write({'status': new_status})

    def action_mark_pregnant(self):
        return self.action_mark_status('pregnant')

    def action_mark_lactating(self):
        return self.action_mark_status('lactating')

    def action_mark_dry(self):
        return self.action_mark_status('dry')

    def action_mark_sick(self):
        return self.action_mark_status('sick')

    def action_mark_quarantine(self):
        return self.action_mark_status('quarantine')

    def action_return_to_active(self):
        return self.action_mark_status('active')
```

**Task 2: Create IR Sequence for Registration Numbers (1h)**

```xml
<!-- odoo/addons/smart_dairy_farm/data/ir_sequence_data.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <data noupdate="1">
        <record id="seq_farm_animal_registration" model="ir.sequence">
            <field name="name">Farm Animal Registration</field>
            <field name="code">farm.animal.registration</field>
            <field name="prefix">%(year)s</field>
            <field name="padding">6</field>
            <field name="number_next">1</field>
            <field name="number_increment">1</field>
            <field name="company_id" eval="False"/>
        </record>
    </data>
</odoo>
```

**Task 3: Create Module Manifest (1h)**

```python
# odoo/addons/smart_dairy_farm/__manifest__.py
{
    'name': 'Smart Dairy Farm Management',
    'version': '19.0.1.0.0',
    'category': 'Agriculture',
    'summary': 'Comprehensive Farm Management for Smart Dairy',
    'description': """
        Smart Dairy Farm Management Module
        ==================================
        - Animal Registration & Herd Management
        - Health & Vaccination Tracking
        - Breeding & Reproduction Management
        - Milk Production Tracking
        - Feed Management & Ration Planning
        - Farm Analytics & Reporting
    """,
    'author': 'Smart Dairy Ltd.',
    'website': 'https://smartdairybd.com',
    'license': 'LGPL-3',
    'depends': [
        'base',
        'mail',
        'product',
        'stock',
        'hr',
    ],
    'data': [
        'security/farm_security.xml',
        'security/ir.model.access.csv',
        'data/ir_sequence_data.xml',
        'views/farm_animal_views.xml',
        'views/farm_breed_views.xml',
        'views/farm_location_views.xml',
        'views/farm_menu.xml',
    ],
    'demo': [
        'demo/farm_demo_data.xml',
    ],
    'installable': True,
    'application': True,
    'auto_install': False,
}
```

#### Dev 2 - Full-Stack (8h)

**Task 1: Database Migration Setup (3h)**

```python
# odoo/addons/smart_dairy_farm/migrations/19.0.1.0.0/pre-migrate.py
from odoo import SUPERUSER_ID, api

def migrate(cr, version):
    """Pre-migration script for initial setup"""
    env = api.Environment(cr, SUPERUSER_ID, {})

    # Create indexes for performance
    cr.execute("""
        CREATE INDEX IF NOT EXISTS farm_animal_ear_tag_idx
        ON farm_animal(ear_tag);

        CREATE INDEX IF NOT EXISTS farm_animal_rfid_tag_idx
        ON farm_animal(rfid_tag);

        CREATE INDEX IF NOT EXISTS farm_animal_status_idx
        ON farm_animal(status);

        CREATE INDEX IF NOT EXISTS farm_animal_species_idx
        ON farm_animal(species);

        CREATE INDEX IF NOT EXISTS farm_animal_farm_id_idx
        ON farm_animal(farm_id);
    """)
```

**Task 2: Security Groups & Access Rights (3h)**

```xml
<!-- odoo/addons/smart_dairy_farm/security/farm_security.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <data noupdate="1">
        <!-- Module Category -->
        <record id="module_category_farm" model="ir.module.category">
            <field name="name">Farm Management</field>
            <field name="description">Smart Dairy Farm Management</field>
            <field name="sequence">100</field>
        </record>

        <!-- User Groups -->
        <record id="group_farm_user" model="res.groups">
            <field name="name">Farm User</field>
            <field name="category_id" ref="module_category_farm"/>
            <field name="comment">Can view and create basic farm records</field>
        </record>

        <record id="group_farm_manager" model="res.groups">
            <field name="name">Farm Manager</field>
            <field name="category_id" ref="module_category_farm"/>
            <field name="implied_ids" eval="[(4, ref('group_farm_user'))]"/>
            <field name="comment">Full access to farm management</field>
        </record>

        <record id="group_farm_admin" model="res.groups">
            <field name="name">Farm Administrator</field>
            <field name="category_id" ref="module_category_farm"/>
            <field name="implied_ids" eval="[(4, ref('group_farm_manager'))]"/>
            <field name="comment">Administrative access including configuration</field>
        </record>

        <!-- Record Rules -->
        <record id="rule_farm_animal_user" model="ir.rule">
            <field name="name">Farm Animal: User can see own company</field>
            <field name="model_id" ref="model_farm_animal"/>
            <field name="domain_force">[('company_id', 'in', company_ids)]</field>
            <field name="groups" eval="[(4, ref('group_farm_user'))]"/>
        </record>
    </data>
</odoo>
```

```csv
# odoo/addons/smart_dairy_farm/security/ir.model.access.csv
id,name,model_id:id,group_id:id,perm_read,perm_write,perm_create,perm_unlink
access_farm_animal_user,farm.animal.user,model_farm_animal,group_farm_user,1,0,0,0
access_farm_animal_manager,farm.animal.manager,model_farm_animal,group_farm_manager,1,1,1,0
access_farm_animal_admin,farm.animal.admin,model_farm_animal,group_farm_admin,1,1,1,1
access_farm_breed_user,farm.breed.user,model_farm_breed,group_farm_user,1,0,0,0
access_farm_breed_manager,farm.breed.manager,model_farm_breed,group_farm_manager,1,1,1,0
access_farm_breed_admin,farm.breed.admin,model_farm_breed,group_farm_admin,1,1,1,1
access_farm_location_user,farm.location.user,model_farm_location,group_farm_user,1,0,0,0
access_farm_location_manager,farm.location.manager,model_farm_location,group_farm_manager,1,1,1,0
access_farm_location_admin,farm.location.admin,model_farm_location,group_farm_admin,1,1,1,1
access_farm_pen_user,farm.pen.user,model_farm_pen,group_farm_user,1,0,0,0
access_farm_pen_manager,farm.pen.manager,model_farm_pen,group_farm_manager,1,1,1,0
access_farm_pen_admin,farm.pen.admin,model_farm_pen,group_farm_admin,1,1,1,1
```

**Task 3: Setup Unit Test Framework (2h)**

```python
# odoo/addons/smart_dairy_farm/tests/__init__.py
from . import test_farm_animal

# odoo/addons/smart_dairy_farm/tests/test_farm_animal.py
from odoo.tests import TransactionCase, tagged
from odoo.exceptions import ValidationError
from datetime import date, timedelta

@tagged('post_install', '-at_install', 'farm')
class TestFarmAnimal(TransactionCase):

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.FarmAnimal = cls.env['farm.animal']
        cls.FarmBreed = cls.env['farm.breed']
        cls.FarmLocation = cls.env['farm.location']

        # Create test breed
        cls.breed_hf = cls.FarmBreed.create({
            'name': 'Holstein Friesian',
            'code': 'HF',
            'species': 'cattle',
        })

        # Create test farm
        cls.farm = cls.FarmLocation.create({
            'name': 'Test Farm',
            'code': 'TF001',
            'location_type': 'farm',
        })

    def test_animal_creation(self):
        """Test basic animal creation"""
        animal = self.FarmAnimal.create({
            'name': 'Bessie',
            'ear_tag': 'BD-2024-000001',
            'species': 'cattle',
            'breed_id': self.breed_hf.id,
            'gender': 'female',
            'date_of_birth': date.today() - timedelta(days=365*3),
            'farm_id': self.farm.id,
        })
        self.assertTrue(animal.id)
        self.assertEqual(animal.species, 'cattle')
        self.assertEqual(animal.animal_type, 'dairy_cow')

    def test_ear_tag_validation(self):
        """Test ear tag format validation"""
        with self.assertRaises(ValidationError):
            self.FarmAnimal.create({
                'name': 'Invalid Tag',
                'ear_tag': 'INVALID-FORMAT',
                'species': 'cattle',
                'breed_id': self.breed_hf.id,
                'gender': 'female',
                'date_of_birth': date.today() - timedelta(days=365),
                'farm_id': self.farm.id,
            })

    def test_age_calculation(self):
        """Test age computation"""
        dob = date.today() - timedelta(days=365 * 2 + 180)  # 2.5 years
        animal = self.FarmAnimal.create({
            'name': 'Age Test',
            'ear_tag': 'BD-2024-000002',
            'species': 'cattle',
            'breed_id': self.breed_hf.id,
            'gender': 'female',
            'date_of_birth': dob,
            'farm_id': self.farm.id,
        })
        self.assertGreater(animal.age_months, 28)
        self.assertLess(animal.age_months, 32)

    def test_genealogy_validation(self):
        """Test genealogy constraint validation"""
        animal = self.FarmAnimal.create({
            'name': 'Self Parent Test',
            'ear_tag': 'BD-2024-000003',
            'species': 'cattle',
            'breed_id': self.breed_hf.id,
            'gender': 'female',
            'date_of_birth': date.today() - timedelta(days=365*3),
            'farm_id': self.farm.id,
        })

        # Try to set animal as its own parent
        with self.assertRaises(ValidationError):
            animal.write({'dam_id': animal.id})
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Animal List View Wireframe Design (3h)**

```xml
<!-- odoo/addons/smart_dairy_farm/views/farm_animal_views.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Tree View -->
    <record id="view_farm_animal_tree" model="ir.ui.view">
        <field name="name">farm.animal.tree</field>
        <field name="model">farm.animal</field>
        <field name="arch" type="xml">
            <tree decoration-danger="status == 'sick'"
                  decoration-warning="status == 'quarantine'"
                  decoration-muted="not is_active"
                  multi_edit="1">
                <field name="image_small" widget="image" options="{'size': [32, 32]}"/>
                <field name="ear_tag"/>
                <field name="name"/>
                <field name="species"/>
                <field name="breed_id"/>
                <field name="animal_type"/>
                <field name="gender"/>
                <field name="age_display"/>
                <field name="farm_id"/>
                <field name="pen_id"/>
                <field name="status" widget="badge"
                       decoration-success="status == 'active'"
                       decoration-info="status in ('pregnant', 'lactating')"
                       decoration-danger="status == 'sick'"
                       decoration-warning="status == 'quarantine'"/>
                <field name="is_active" column_invisible="1"/>
            </tree>
        </field>
    </record>

    <!-- Form View -->
    <record id="view_farm_animal_form" model="ir.ui.view">
        <field name="name">farm.animal.form</field>
        <field name="model">farm.animal</field>
        <field name="arch" type="xml">
            <form string="Animal">
                <header>
                    <button name="action_mark_pregnant" string="Mark Pregnant"
                            type="object" class="btn-primary"
                            invisible="gender != 'female' or status == 'pregnant'"/>
                    <button name="action_mark_lactating" string="Start Lactation"
                            type="object" class="btn-primary"
                            invisible="gender != 'female' or status == 'lactating'"/>
                    <button name="action_mark_dry" string="Dry Off"
                            type="object"
                            invisible="status != 'lactating'"/>
                    <button name="action_mark_sick" string="Mark Sick"
                            type="object" class="btn-warning"
                            invisible="status == 'sick'"/>
                    <button name="action_mark_quarantine" string="Quarantine"
                            type="object" class="btn-warning"
                            invisible="status == 'quarantine'"/>
                    <button name="action_return_to_active" string="Return to Active"
                            type="object" class="btn-success"
                            invisible="status not in ('sick', 'quarantine', 'dry')"/>
                    <button name="action_transfer" string="Transfer"
                            type="object" class="btn-secondary"/>
                    <field name="status" widget="statusbar"
                           statusbar_visible="active,pregnant,lactating,dry"/>
                </header>
                <sheet>
                    <div class="oe_button_box" name="button_box">
                        <button name="action_view_health_records" type="object"
                                class="oe_stat_button" icon="fa-heartbeat">
                            <field name="health_record_count" widget="statinfo"
                                   string="Health Records"/>
                        </button>
                        <button name="action_view_breeding_records" type="object"
                                class="oe_stat_button" icon="fa-venus-mars"
                                invisible="gender != 'female'">
                            <field name="breeding_record_count" widget="statinfo"
                                   string="Breeding"/>
                        </button>
                        <button name="action_view_milk_production" type="object"
                                class="oe_stat_button" icon="fa-tint"
                                invisible="gender != 'female'">
                            <field name="milk_production_count" widget="statinfo"
                                   string="Milk Records"/>
                        </button>
                        <button name="action_view_offspring" type="object"
                                class="oe_stat_button" icon="fa-users">
                            <field name="offspring_count" widget="statinfo"
                                   string="Offspring"/>
                        </button>
                    </div>
                    <field name="image" widget="image" class="oe_avatar"
                           options="{'preview_image': 'image_medium', 'size': [128, 128]}"/>
                    <div class="oe_title">
                        <label for="name"/>
                        <h1>
                            <field name="name" placeholder="Animal Name"/>
                        </h1>
                        <h3>
                            <field name="ear_tag" placeholder="BD-XXXX-XXXXXX"/>
                        </h3>
                    </div>
                    <group>
                        <group string="Identification">
                            <field name="registration_number"/>
                            <field name="rfid_tag"/>
                            <field name="species"/>
                            <field name="breed_id"/>
                            <field name="gender"/>
                            <field name="animal_type"/>
                        </group>
                        <group string="Age &amp; Dates">
                            <field name="date_of_birth"/>
                            <field name="age_display"/>
                            <field name="date_arrived"/>
                        </group>
                    </group>
                    <notebook>
                        <page string="Genealogy" name="genealogy">
                            <group>
                                <group string="Parents">
                                    <field name="dam_id"/>
                                    <field name="sire_id"/>
                                    <field name="sire_external"
                                           invisible="sire_id"/>
                                </group>
                                <group string="Genetics">
                                    <field name="generation"/>
                                    <field name="inbreeding_coefficient"
                                           widget="progressbar"/>
                                </group>
                            </group>
                            <field name="offspring_ids" mode="tree">
                                <tree>
                                    <field name="ear_tag"/>
                                    <field name="name"/>
                                    <field name="gender"/>
                                    <field name="date_of_birth"/>
                                    <field name="status"/>
                                </tree>
                            </field>
                        </page>
                        <page string="Location" name="location">
                            <group>
                                <group>
                                    <field name="farm_id"/>
                                    <field name="pen_id"/>
                                </group>
                                <group>
                                    <field name="group_ids" widget="many2many_tags"/>
                                </group>
                            </group>
                            <field name="location_history_ids" mode="tree">
                                <tree>
                                    <field name="transfer_date"/>
                                    <field name="source_farm_id"/>
                                    <field name="source_pen_id"/>
                                    <field name="dest_farm_id"/>
                                    <field name="dest_pen_id"/>
                                    <field name="reason"/>
                                </tree>
                            </field>
                        </page>
                        <page string="Physical" name="physical">
                            <group>
                                <group>
                                    <field name="color_marking"/>
                                    <field name="weight_kg"/>
                                    <field name="weight_date"/>
                                </group>
                                <group>
                                    <field name="height_cm"/>
                                    <field name="body_condition_score"/>
                                </group>
                            </group>
                        </page>
                        <page string="Production" name="production"
                              invisible="gender != 'female'">
                            <group>
                                <group>
                                    <field name="total_milk_yield"/>
                                    <field name="avg_daily_yield"/>
                                    <field name="lactation_count"/>
                                </group>
                            </group>
                        </page>
                        <page string="Acquisition" name="acquisition">
                            <group>
                                <group>
                                    <field name="acquisition_type"/>
                                    <field name="purchase_date"
                                           invisible="acquisition_type != 'purchased'"/>
                                    <field name="purchase_price"
                                           invisible="acquisition_type != 'purchased'"/>
                                    <field name="vendor_id"
                                           invisible="acquisition_type != 'purchased'"/>
                                </group>
                                <group>
                                    <field name="purchase_notes"
                                           invisible="acquisition_type != 'purchased'"/>
                                </group>
                            </group>
                        </page>
                        <page string="Disposal" name="disposal"
                              invisible="is_active">
                            <group>
                                <group>
                                    <field name="disposal_type"/>
                                    <field name="disposal_date"/>
                                    <field name="disposal_price"/>
                                    <field name="buyer_id"/>
                                </group>
                                <group>
                                    <field name="disposal_reason"/>
                                </group>
                            </group>
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

    <!-- Search View -->
    <record id="view_farm_animal_search" model="ir.ui.view">
        <field name="name">farm.animal.search</field>
        <field name="model">farm.animal</field>
        <field name="arch" type="xml">
            <search string="Search Animals">
                <field name="name"/>
                <field name="ear_tag"/>
                <field name="rfid_tag"/>
                <field name="registration_number"/>
                <field name="breed_id"/>
                <field name="farm_id"/>
                <separator/>
                <filter name="filter_active" string="Active"
                        domain="[('is_active', '=', True)]"/>
                <filter name="filter_pregnant" string="Pregnant"
                        domain="[('status', '=', 'pregnant')]"/>
                <filter name="filter_lactating" string="Lactating"
                        domain="[('status', '=', 'lactating')]"/>
                <filter name="filter_sick" string="Sick"
                        domain="[('status', '=', 'sick')]"/>
                <filter name="filter_quarantine" string="Quarantine"
                        domain="[('status', '=', 'quarantine')]"/>
                <separator/>
                <filter name="filter_cattle" string="Cattle"
                        domain="[('species', '=', 'cattle')]"/>
                <filter name="filter_buffalo" string="Buffalo"
                        domain="[('species', '=', 'buffalo')]"/>
                <filter name="filter_goat" string="Goat"
                        domain="[('species', '=', 'goat')]"/>
                <separator/>
                <filter name="filter_female" string="Female"
                        domain="[('gender', '=', 'female')]"/>
                <filter name="filter_male" string="Male"
                        domain="[('gender', '=', 'male')]"/>
                <separator/>
                <filter name="filter_dairy_cow" string="Dairy Cows"
                        domain="[('animal_type', '=', 'dairy_cow')]"/>
                <filter name="filter_heifer" string="Heifers"
                        domain="[('animal_type', '=', 'dairy_heifer')]"/>
                <filter name="filter_calf" string="Calves"
                        domain="[('animal_type', 'in', ['calf_female', 'calf_male'])]"/>
                <filter name="filter_bull" string="Bulls"
                        domain="[('animal_type', '=', 'bull')]"/>
                <separator/>
                <group expand="0" string="Group By">
                    <filter name="groupby_species" string="Species"
                            context="{'group_by': 'species'}"/>
                    <filter name="groupby_breed" string="Breed"
                            context="{'group_by': 'breed_id'}"/>
                    <filter name="groupby_farm" string="Farm"
                            context="{'group_by': 'farm_id'}"/>
                    <filter name="groupby_pen" string="Pen"
                            context="{'group_by': 'pen_id'}"/>
                    <filter name="groupby_status" string="Status"
                            context="{'group_by': 'status'}"/>
                    <filter name="groupby_type" string="Animal Type"
                            context="{'group_by': 'animal_type'}"/>
                </group>
            </search>
        </field>
    </record>

    <!-- Kanban View -->
    <record id="view_farm_animal_kanban" model="ir.ui.view">
        <field name="name">farm.animal.kanban</field>
        <field name="model">farm.animal</field>
        <field name="arch" type="xml">
            <kanban class="o_kanban_mobile" sample="1">
                <field name="id"/>
                <field name="name"/>
                <field name="ear_tag"/>
                <field name="image_small"/>
                <field name="species"/>
                <field name="breed_id"/>
                <field name="status"/>
                <field name="is_active"/>
                <templates>
                    <t t-name="kanban-box">
                        <div class="oe_kanban_card oe_kanban_global_click">
                            <div class="o_kanban_image">
                                <img t-att-src="kanban_image('farm.animal', 'image_small', record.id.raw_value)"
                                     alt="Animal" class="o_image_64_cover"/>
                            </div>
                            <div class="oe_kanban_details">
                                <strong class="o_kanban_record_title">
                                    <field name="name"/>
                                </strong>
                                <div class="o_kanban_record_subtitle">
                                    <field name="ear_tag"/>
                                </div>
                                <div>
                                    <field name="breed_id"/>
                                </div>
                                <div class="o_kanban_record_bottom">
                                    <div class="oe_kanban_bottom_left">
                                        <field name="status" widget="badge"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </t>
                </templates>
            </kanban>
        </field>
    </record>

    <!-- Action -->
    <record id="action_farm_animal" model="ir.actions.act_window">
        <field name="name">Animals</field>
        <field name="res_model">farm.animal</field>
        <field name="view_mode">tree,kanban,form</field>
        <field name="search_view_id" ref="view_farm_animal_search"/>
        <field name="context">{'search_default_filter_active': 1}</field>
        <field name="help" type="html">
            <p class="o_view_nocontent_smiling_face">
                Register your first animal
            </p>
            <p>
                Start managing your herd by registering animals with their
                identification, genealogy, and health information.
            </p>
        </field>
    </record>
</odoo>
```

**Task 2: Flutter Project Structure Setup (3h)**

```dart
// lib/main.dart
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:smart_dairy_farm/app.dart';
import 'package:smart_dairy_farm/core/di/injection.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await configureDependencies();
  runApp(const SmartDairyApp());
}

// lib/app.dart
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:smart_dairy_farm/core/theme/app_theme.dart';
import 'package:smart_dairy_farm/features/animal/presentation/bloc/animal_bloc.dart';
import 'package:smart_dairy_farm/core/router/app_router.dart';

class SmartDairyApp extends StatelessWidget {
  const SmartDairyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider(create: (_) => getIt<AnimalBloc>()),
      ],
      child: MaterialApp.router(
        title: 'Smart Dairy Farm',
        theme: AppTheme.lightTheme,
        darkTheme: AppTheme.darkTheme,
        routerConfig: appRouter,
        debugShowCheckedModeBanner: false,
      ),
    );
  }
}

// lib/features/animal/domain/entities/animal.dart
import 'package:equatable/equatable.dart';

class Animal extends Equatable {
  final int id;
  final String name;
  final String earTag;
  final String? rfidTag;
  final String registrationNumber;
  final String species;
  final int breedId;
  final String breedName;
  final String gender;
  final String animalType;
  final DateTime dateOfBirth;
  final int ageMonths;
  final String ageDisplay;
  final int farmId;
  final String farmName;
  final int? penId;
  final String? penName;
  final String status;
  final bool isActive;
  final double? weightKg;
  final String? colorMarking;
  final String? imageUrl;
  final int? damId;
  final int? sireId;
  final DateTime? lastModified;

  const Animal({
    required this.id,
    required this.name,
    required this.earTag,
    this.rfidTag,
    required this.registrationNumber,
    required this.species,
    required this.breedId,
    required this.breedName,
    required this.gender,
    required this.animalType,
    required this.dateOfBirth,
    required this.ageMonths,
    required this.ageDisplay,
    required this.farmId,
    required this.farmName,
    this.penId,
    this.penName,
    required this.status,
    required this.isActive,
    this.weightKg,
    this.colorMarking,
    this.imageUrl,
    this.damId,
    this.sireId,
    this.lastModified,
  });

  @override
  List<Object?> get props => [
        id,
        name,
        earTag,
        rfidTag,
        registrationNumber,
        species,
        breedId,
        gender,
        animalType,
        dateOfBirth,
        farmId,
        penId,
        status,
        isActive,
        weightKg,
        lastModified,
      ];
}

// lib/features/animal/data/models/animal_model.dart
import 'package:smart_dairy_farm/features/animal/domain/entities/animal.dart';

class AnimalModel extends Animal {
  const AnimalModel({
    required super.id,
    required super.name,
    required super.earTag,
    super.rfidTag,
    required super.registrationNumber,
    required super.species,
    required super.breedId,
    required super.breedName,
    required super.gender,
    required super.animalType,
    required super.dateOfBirth,
    required super.ageMonths,
    required super.ageDisplay,
    required super.farmId,
    required super.farmName,
    super.penId,
    super.penName,
    required super.status,
    required super.isActive,
    super.weightKg,
    super.colorMarking,
    super.imageUrl,
    super.damId,
    super.sireId,
    super.lastModified,
  });

  factory AnimalModel.fromJson(Map<String, dynamic> json) {
    return AnimalModel(
      id: json['id'] as int,
      name: json['name'] as String,
      earTag: json['ear_tag'] as String,
      rfidTag: json['rfid_tag'] as String?,
      registrationNumber: json['registration_number'] as String,
      species: json['species'] as String,
      breedId: json['breed_id'] is List ? json['breed_id'][0] : json['breed_id'],
      breedName: json['breed_id'] is List ? json['breed_id'][1] : '',
      gender: json['gender'] as String,
      animalType: json['animal_type'] as String,
      dateOfBirth: DateTime.parse(json['date_of_birth'] as String),
      ageMonths: json['age_months'] as int,
      ageDisplay: json['age_display'] as String,
      farmId: json['farm_id'] is List ? json['farm_id'][0] : json['farm_id'],
      farmName: json['farm_id'] is List ? json['farm_id'][1] : '',
      penId: json['pen_id'] is List ? json['pen_id'][0] : json['pen_id'],
      penName: json['pen_id'] is List ? json['pen_id'][1] : null,
      status: json['status'] as String,
      isActive: json['is_active'] as bool,
      weightKg: json['weight_kg'] as double?,
      colorMarking: json['color_marking'] as String?,
      imageUrl: json['image'] != null ? 'data:image/png;base64,${json['image']}' : null,
      damId: json['dam_id'] is List ? json['dam_id'][0] : json['dam_id'],
      sireId: json['sire_id'] is List ? json['sire_id'][0] : json['sire_id'],
      lastModified: json['write_date'] != null
          ? DateTime.parse(json['write_date'] as String)
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'ear_tag': earTag,
      'rfid_tag': rfidTag,
      'registration_number': registrationNumber,
      'species': species,
      'breed_id': breedId,
      'gender': gender,
      'animal_type': animalType,
      'date_of_birth': dateOfBirth.toIso8601String().split('T')[0],
      'farm_id': farmId,
      'pen_id': penId,
      'status': status,
      'weight_kg': weightKg,
      'color_marking': colorMarking,
      'dam_id': damId,
      'sire_id': sireId,
    };
  }

  // For local SQLite storage
  Map<String, dynamic> toLocalJson() {
    return {
      ...toJson(),
      'breed_name': breedName,
      'farm_name': farmName,
      'pen_name': penName,
      'age_months': ageMonths,
      'age_display': ageDisplay,
      'is_active': isActive ? 1 : 0,
      'last_modified': lastModified?.toIso8601String(),
    };
  }
}
```

**Task 3: Design System Token Setup (2h)**

```dart
// lib/core/theme/app_theme.dart
import 'package:flutter/material.dart';

class AppTheme {
  // Brand Colors - Smart Dairy
  static const Color primaryGreen = Color(0xFF2E7D32);
  static const Color primaryGreenLight = Color(0xFF60AD5E);
  static const Color primaryGreenDark = Color(0xFF005005);

  static const Color secondaryBlue = Color(0xFF1565C0);
  static const Color accentOrange = Color(0xFFFF8F00);

  // Status Colors
  static const Color statusActive = Color(0xFF4CAF50);
  static const Color statusPregnant = Color(0xFF2196F3);
  static const Color statusLactating = Color(0xFF03A9F4);
  static const Color statusSick = Color(0xFFF44336);
  static const Color statusQuarantine = Color(0xFFFF9800);
  static const Color statusDry = Color(0xFF9E9E9E);

  // Species Colors
  static const Color speciesCattle = Color(0xFF8D6E63);
  static const Color speciesBuffalo = Color(0xFF455A64);
  static const Color speciesGoat = Color(0xFFA1887F);

  static ThemeData get lightTheme {
    return ThemeData(
      useMaterial3: true,
      colorScheme: ColorScheme.fromSeed(
        seedColor: primaryGreen,
        brightness: Brightness.light,
      ),
      appBarTheme: const AppBarTheme(
        backgroundColor: primaryGreen,
        foregroundColor: Colors.white,
        elevation: 0,
      ),
      cardTheme: CardTheme(
        elevation: 2,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(12),
        ),
      ),
      inputDecorationTheme: InputDecorationTheme(
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
        ),
        filled: true,
        fillColor: Colors.grey.shade50,
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: primaryGreen,
          foregroundColor: Colors.white,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(8),
          ),
        ),
      ),
    );
  }

  static ThemeData get darkTheme {
    return ThemeData(
      useMaterial3: true,
      colorScheme: ColorScheme.fromSeed(
        seedColor: primaryGreen,
        brightness: Brightness.dark,
      ),
    );
  }

  // Helper to get status color
  static Color getStatusColor(String status) {
    switch (status) {
      case 'active':
        return statusActive;
      case 'pregnant':
        return statusPregnant;
      case 'lactating':
        return statusLactating;
      case 'sick':
        return statusSick;
      case 'quarantine':
        return statusQuarantine;
      case 'dry':
        return statusDry;
      default:
        return Colors.grey;
    }
  }

  // Helper to get species color
  static Color getSpeciesColor(String species) {
    switch (species) {
      case 'cattle':
        return speciesCattle;
      case 'buffalo':
        return speciesBuffalo;
      case 'goat':
        return speciesGoat;
      default:
        return Colors.grey;
    }
  }
}
```

**Day 251 Deliverables:**
- [ ] FarmAnimal model with 50+ fields implemented
- [ ] IR Sequence for registration numbers configured
- [ ] Module manifest created
- [ ] Database migration with indexes
- [ ] Security groups and access rights defined
- [ ] Unit test framework established
- [ ] Animal tree, form, search, kanban views created
- [ ] Flutter project structure initialized
- [ ] Design system tokens defined
- [ ] Daily standup completed
- [ ] Code reviewed and merged

---

### Day 252 - Breed & Species Master Data

**Objective:** Create comprehensive breed and species master data models with production characteristics and standard data.

#### Dev 1 - Backend Lead (8h)

**Task 1: Create FarmBreed Model (4h)**

```python
# odoo/addons/smart_dairy_farm/models/farm_breed.py
from odoo import models, fields, api

class FarmBreed(models.Model):
    _name = 'farm.breed'
    _description = 'Animal Breed'
    _order = 'species, name'
    _rec_name = 'display_name'

    name = fields.Char(
        string='Breed Name',
        required=True,
        translate=True
    )
    display_name = fields.Char(
        compute='_compute_display_name',
        store=True
    )
    code = fields.Char(
        string='Breed Code',
        required=True,
        size=10
    )
    species = fields.Selection([
        ('cattle', 'Cattle'),
        ('buffalo', 'Buffalo'),
        ('goat', 'Goat'),
    ], string='Species', required=True, default='cattle')

    origin_country = fields.Char(string='Country of Origin')
    characteristics = fields.Html(string='Breed Characteristics')
    image = fields.Image(string='Breed Image', max_width=512, max_height=512)

    # Production Characteristics
    avg_milk_yield_liters = fields.Float(
        string='Avg. Daily Milk Yield (L)',
        digits=(5, 1),
        help='Average daily milk production in liters'
    )
    peak_milk_yield_liters = fields.Float(
        string='Peak Daily Yield (L)',
        digits=(5, 1)
    )
    avg_lactation_days = fields.Integer(
        string='Avg. Lactation Period (days)',
        default=305
    )
    avg_fat_percentage = fields.Float(
        string='Avg. Milk Fat %',
        digits=(4, 2)
    )
    avg_protein_percentage = fields.Float(
        string='Avg. Milk Protein %',
        digits=(4, 2)
    )
    avg_snf_percentage = fields.Float(
        string='Avg. SNF %',
        digits=(4, 2),
        help='Solids-Not-Fat percentage'
    )

    # Physical Characteristics
    avg_mature_weight_kg = fields.Float(
        string='Avg. Mature Weight (kg)',
        digits=(6, 1)
    )
    avg_birth_weight_kg = fields.Float(
        string='Avg. Birth Weight (kg)',
        digits=(4, 1)
    )
    color_pattern = fields.Char(string='Typical Color Pattern')
    horn_status = fields.Selection([
        ('horned', 'Horned'),
        ('polled', 'Polled (Naturally Hornless)'),
        ('variable', 'Variable'),
    ], string='Horn Status')

    # Reproduction Characteristics
    avg_age_first_calving_months = fields.Integer(
        string='Avg. Age at First Calving (months)'
    )
    avg_calving_interval_days = fields.Integer(
        string='Avg. Calving Interval (days)'
    )
    avg_gestation_days = fields.Integer(
        string='Avg. Gestation Period (days)',
        default=280
    )
    twin_rate_percent = fields.Float(
        string='Twin Rate %',
        digits=(4, 2)
    )

    # Adaptability
    heat_tolerance = fields.Selection([
        ('low', 'Low'),
        ('medium', 'Medium'),
        ('high', 'High'),
    ], string='Heat Tolerance')
    disease_resistance = fields.Selection([
        ('low', 'Low'),
        ('medium', 'Medium'),
        ('high', 'High'),
    ], string='Disease Resistance')

    # Related Animals
    animal_ids = fields.One2many(
        'farm.animal',
        'breed_id',
        string='Animals'
    )
    animal_count = fields.Integer(
        compute='_compute_animal_count'
    )

    # Notes
    notes = fields.Html(string='Additional Notes')

    active = fields.Boolean(default=True)

    _sql_constraints = [
        ('code_unique', 'unique(code)', 'Breed code must be unique!'),
    ]

    @api.depends('name', 'code')
    def _compute_display_name(self):
        for record in self:
            record.display_name = f"[{record.code}] {record.name}"

    def _compute_animal_count(self):
        for record in self:
            record.animal_count = len(record.animal_ids.filtered('is_active'))

    @api.model
    def get_breeds_by_species(self, species):
        """API method to get breeds for a species"""
        return self.search_read(
            [('species', '=', species), ('active', '=', True)],
            ['id', 'name', 'code', 'avg_milk_yield_liters']
        )
```

**Task 2: Create Default Breed Data (2h)**

```xml
<!-- odoo/addons/smart_dairy_farm/data/farm_breed_data.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <data noupdate="1">
        <!-- Cattle Breeds -->
        <record id="breed_holstein_friesian" model="farm.breed">
            <field name="name">Holstein Friesian</field>
            <field name="code">HF</field>
            <field name="species">cattle</field>
            <field name="origin_country">Netherlands/Germany</field>
            <field name="avg_milk_yield_liters">25.0</field>
            <field name="peak_milk_yield_liters">45.0</field>
            <field name="avg_lactation_days">305</field>
            <field name="avg_fat_percentage">3.5</field>
            <field name="avg_protein_percentage">3.2</field>
            <field name="avg_snf_percentage">8.5</field>
            <field name="avg_mature_weight_kg">680</field>
            <field name="avg_birth_weight_kg">42</field>
            <field name="color_pattern">Black and White</field>
            <field name="horn_status">variable</field>
            <field name="avg_age_first_calving_months">24</field>
            <field name="avg_calving_interval_days">400</field>
            <field name="avg_gestation_days">280</field>
            <field name="heat_tolerance">low</field>
            <field name="disease_resistance">medium</field>
            <field name="characteristics"><![CDATA[
                <ul>
                    <li>World's highest milk-producing dairy breed</li>
                    <li>Distinctive black and white markings</li>
                    <li>Large frame with angular dairy conformation</li>
                    <li>Requires good management and nutrition</li>
                </ul>
            ]]></field>
        </record>

        <record id="breed_jersey" model="farm.breed">
            <field name="name">Jersey</field>
            <field name="code">JER</field>
            <field name="species">cattle</field>
            <field name="origin_country">Jersey Island, UK</field>
            <field name="avg_milk_yield_liters">18.0</field>
            <field name="peak_milk_yield_liters">28.0</field>
            <field name="avg_lactation_days">305</field>
            <field name="avg_fat_percentage">5.0</field>
            <field name="avg_protein_percentage">3.8</field>
            <field name="avg_snf_percentage">9.2</field>
            <field name="avg_mature_weight_kg">450</field>
            <field name="avg_birth_weight_kg">27</field>
            <field name="color_pattern">Light Brown to Fawn</field>
            <field name="horn_status">variable</field>
            <field name="avg_age_first_calving_months">22</field>
            <field name="avg_calving_interval_days">385</field>
            <field name="avg_gestation_days">278</field>
            <field name="heat_tolerance">medium</field>
            <field name="disease_resistance">medium</field>
        </record>

        <record id="breed_sahiwal" model="farm.breed">
            <field name="name">Sahiwal</field>
            <field name="code">SAH</field>
            <field name="species">cattle</field>
            <field name="origin_country">Pakistan/India</field>
            <field name="avg_milk_yield_liters">10.0</field>
            <field name="peak_milk_yield_liters">16.0</field>
            <field name="avg_lactation_days">290</field>
            <field name="avg_fat_percentage">4.5</field>
            <field name="avg_protein_percentage">3.5</field>
            <field name="avg_mature_weight_kg">450</field>
            <field name="avg_birth_weight_kg">22</field>
            <field name="color_pattern">Reddish Brown</field>
            <field name="horn_status">horned</field>
            <field name="avg_age_first_calving_months">36</field>
            <field name="avg_calving_interval_days">450</field>
            <field name="heat_tolerance">high</field>
            <field name="disease_resistance">high</field>
        </record>

        <record id="breed_red_chittagong" model="farm.breed">
            <field name="name">Red Chittagong</field>
            <field name="code">RCH</field>
            <field name="species">cattle</field>
            <field name="origin_country">Bangladesh</field>
            <field name="avg_milk_yield_liters">3.5</field>
            <field name="peak_milk_yield_liters">6.0</field>
            <field name="avg_lactation_days">250</field>
            <field name="avg_fat_percentage">5.5</field>
            <field name="avg_protein_percentage">3.8</field>
            <field name="avg_mature_weight_kg">280</field>
            <field name="avg_birth_weight_kg">16</field>
            <field name="color_pattern">Red/Reddish Brown</field>
            <field name="horn_status">horned</field>
            <field name="heat_tolerance">high</field>
            <field name="disease_resistance">high</field>
        </record>

        <record id="breed_crossbred_hf_local" model="farm.breed">
            <field name="name">HF Crossbred (Local)</field>
            <field name="code">HFX</field>
            <field name="species">cattle</field>
            <field name="origin_country">Bangladesh</field>
            <field name="avg_milk_yield_liters">12.0</field>
            <field name="peak_milk_yield_liters">20.0</field>
            <field name="avg_lactation_days">280</field>
            <field name="avg_fat_percentage">4.0</field>
            <field name="avg_protein_percentage">3.4</field>
            <field name="avg_mature_weight_kg">400</field>
            <field name="heat_tolerance">medium</field>
            <field name="disease_resistance">medium</field>
        </record>

        <!-- Buffalo Breeds -->
        <record id="breed_murrah" model="farm.breed">
            <field name="name">Murrah Buffalo</field>
            <field name="code">MUR</field>
            <field name="species">buffalo</field>
            <field name="origin_country">India</field>
            <field name="avg_milk_yield_liters">10.0</field>
            <field name="peak_milk_yield_liters">18.0</field>
            <field name="avg_lactation_days">310</field>
            <field name="avg_fat_percentage">7.5</field>
            <field name="avg_protein_percentage">4.2</field>
            <field name="avg_mature_weight_kg">550</field>
            <field name="avg_birth_weight_kg">35</field>
            <field name="color_pattern">Jet Black</field>
            <field name="horn_status">horned</field>
            <field name="avg_gestation_days">310</field>
            <field name="heat_tolerance">high</field>
        </record>

        <!-- Goat Breeds -->
        <record id="breed_black_bengal" model="farm.breed">
            <field name="name">Black Bengal</field>
            <field name="code">BBG</field>
            <field name="species">goat</field>
            <field name="origin_country">Bangladesh</field>
            <field name="avg_milk_yield_liters">0.5</field>
            <field name="avg_mature_weight_kg">20</field>
            <field name="avg_birth_weight_kg">1.2</field>
            <field name="color_pattern">Black, Brown, White</field>
            <field name="horn_status">horned</field>
            <field name="avg_gestation_days">150</field>
            <field name="twin_rate_percent">60.0</field>
            <field name="heat_tolerance">high</field>
            <field name="disease_resistance">high</field>
        </record>

        <record id="breed_jamunapari" model="farm.breed">
            <field name="name">Jamunapari</field>
            <field name="code">JAM</field>
            <field name="species">goat</field>
            <field name="origin_country">India</field>
            <field name="avg_milk_yield_liters">2.5</field>
            <field name="avg_mature_weight_kg">65</field>
            <field name="avg_birth_weight_kg">3.0</field>
            <field name="color_pattern">White with Brown Patches</field>
            <field name="horn_status">horned</field>
            <field name="avg_gestation_days">150</field>
            <field name="heat_tolerance">medium</field>
        </record>
    </data>
</odoo>
```

**Task 3: Create Breed Views (2h)**

```xml
<!-- odoo/addons/smart_dairy_farm/views/farm_breed_views.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <record id="view_farm_breed_tree" model="ir.ui.view">
        <field name="name">farm.breed.tree</field>
        <field name="model">farm.breed</field>
        <field name="arch" type="xml">
            <tree>
                <field name="code"/>
                <field name="name"/>
                <field name="species"/>
                <field name="origin_country"/>
                <field name="avg_milk_yield_liters"/>
                <field name="avg_fat_percentage"/>
                <field name="heat_tolerance"/>
                <field name="animal_count"/>
            </tree>
        </field>
    </record>

    <record id="view_farm_breed_form" model="ir.ui.view">
        <field name="name">farm.breed.form</field>
        <field name="model">farm.breed</field>
        <field name="arch" type="xml">
            <form string="Breed">
                <sheet>
                    <div class="oe_button_box" name="button_box">
                        <button class="oe_stat_button" type="object"
                                name="action_view_animals" icon="fa-paw">
                            <field name="animal_count" widget="statinfo"
                                   string="Animals"/>
                        </button>
                    </div>
                    <field name="image" widget="image" class="oe_avatar"
                           options="{'size': [128, 128]}"/>
                    <div class="oe_title">
                        <label for="name"/>
                        <h1>
                            <field name="name" placeholder="Breed Name"/>
                        </h1>
                        <h3>
                            <field name="code" placeholder="Code"/>
                        </h3>
                    </div>
                    <group>
                        <group string="Classification">
                            <field name="species"/>
                            <field name="origin_country"/>
                            <field name="color_pattern"/>
                            <field name="horn_status"/>
                        </group>
                        <group string="Adaptability">
                            <field name="heat_tolerance"/>
                            <field name="disease_resistance"/>
                        </group>
                    </group>
                    <notebook>
                        <page string="Production" name="production">
                            <group>
                                <group string="Milk Production">
                                    <field name="avg_milk_yield_liters"/>
                                    <field name="peak_milk_yield_liters"/>
                                    <field name="avg_lactation_days"/>
                                </group>
                                <group string="Milk Quality">
                                    <field name="avg_fat_percentage"/>
                                    <field name="avg_protein_percentage"/>
                                    <field name="avg_snf_percentage"/>
                                </group>
                            </group>
                        </page>
                        <page string="Physical" name="physical">
                            <group>
                                <group>
                                    <field name="avg_mature_weight_kg"/>
                                    <field name="avg_birth_weight_kg"/>
                                </group>
                            </group>
                        </page>
                        <page string="Reproduction" name="reproduction">
                            <group>
                                <group>
                                    <field name="avg_age_first_calving_months"/>
                                    <field name="avg_calving_interval_days"/>
                                    <field name="avg_gestation_days"/>
                                    <field name="twin_rate_percent"/>
                                </group>
                            </group>
                        </page>
                        <page string="Characteristics" name="characteristics">
                            <field name="characteristics"/>
                        </page>
                        <page string="Notes" name="notes">
                            <field name="notes"/>
                        </page>
                    </notebook>
                </sheet>
            </form>
        </field>
    </record>

    <record id="view_farm_breed_search" model="ir.ui.view">
        <field name="name">farm.breed.search</field>
        <field name="model">farm.breed</field>
        <field name="arch" type="xml">
            <search string="Search Breeds">
                <field name="name"/>
                <field name="code"/>
                <filter name="filter_cattle" string="Cattle"
                        domain="[('species', '=', 'cattle')]"/>
                <filter name="filter_buffalo" string="Buffalo"
                        domain="[('species', '=', 'buffalo')]"/>
                <filter name="filter_goat" string="Goat"
                        domain="[('species', '=', 'goat')]"/>
                <group expand="0" string="Group By">
                    <filter name="groupby_species" string="Species"
                            context="{'group_by': 'species'}"/>
                    <filter name="groupby_heat_tolerance" string="Heat Tolerance"
                            context="{'group_by': 'heat_tolerance'}"/>
                </group>
            </search>
        </field>
    </record>

    <record id="action_farm_breed" model="ir.actions.act_window">
        <field name="name">Breeds</field>
        <field name="res_model">farm.breed</field>
        <field name="view_mode">tree,form</field>
        <field name="search_view_id" ref="view_farm_breed_search"/>
    </record>
</odoo>
```

#### Dev 2 - Full-Stack (8h)

**Task 1: Breed Import Wizard (4h)**

```python
# odoo/addons/smart_dairy_farm/wizard/breed_import_wizard.py
from odoo import models, fields, api
from odoo.exceptions import UserError
import base64
import csv
import io

class BreedImportWizard(models.TransientModel):
    _name = 'farm.breed.import.wizard'
    _description = 'Import Breeds from CSV'

    file = fields.Binary(string='CSV File', required=True)
    filename = fields.Char(string='Filename')
    delimiter = fields.Selection([
        (',', 'Comma (,)'),
        (';', 'Semicolon (;)'),
        ('\t', 'Tab'),
    ], string='Delimiter', default=',')

    def action_import(self):
        """Import breeds from CSV file"""
        if not self.file:
            raise UserError('Please select a file to import.')

        # Decode file
        csv_data = base64.b64decode(self.file)
        csv_file = io.StringIO(csv_data.decode('utf-8'))
        reader = csv.DictReader(csv_file, delimiter=self.delimiter)

        breeds_created = 0
        breeds_updated = 0
        errors = []

        for row_num, row in enumerate(reader, start=2):
            try:
                breed = self._process_row(row)
                if breed._origin:
                    breeds_updated += 1
                else:
                    breeds_created += 1
            except Exception as e:
                errors.append(f"Row {row_num}: {str(e)}")

        # Prepare result message
        message = f"Import completed:\n- Created: {breeds_created}\n- Updated: {breeds_updated}"
        if errors:
            message += f"\n\nErrors ({len(errors)}):\n" + "\n".join(errors[:10])
            if len(errors) > 10:
                message += f"\n... and {len(errors) - 10} more errors"

        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': 'Import Result',
                'message': message,
                'type': 'success' if not errors else 'warning',
                'sticky': True,
            }
        }

    def _process_row(self, row):
        """Process a single CSV row"""
        code = row.get('code', '').strip()
        if not code:
            raise ValueError('Code is required')

        # Find existing or create new
        breed = self.env['farm.breed'].search([('code', '=', code)], limit=1)

        vals = {
            'code': code,
            'name': row.get('name', '').strip(),
            'species': row.get('species', 'cattle').strip(),
            'origin_country': row.get('origin_country', '').strip(),
        }

        # Numeric fields
        for field in ['avg_milk_yield_liters', 'avg_fat_percentage', 'avg_mature_weight_kg']:
            if row.get(field):
                try:
                    vals[field] = float(row[field])
                except ValueError:
                    pass

        if breed:
            breed.write(vals)
        else:
            breed = self.env['farm.breed'].create(vals)

        return breed
```

**Task 2: Breed API Endpoints (2h)**

```python
# odoo/addons/smart_dairy_farm/controllers/breed_controller.py
from odoo import http
from odoo.http import request
import json

class BreedController(http.Controller):

    @http.route('/api/v1/breeds', type='json', auth='user', methods=['GET'])
    def get_breeds(self, species=None, **kwargs):
        """Get list of breeds, optionally filtered by species"""
        domain = [('active', '=', True)]
        if species:
            domain.append(('species', '=', species))

        breeds = request.env['farm.breed'].search_read(
            domain,
            ['id', 'name', 'code', 'species', 'avg_milk_yield_liters',
             'avg_fat_percentage', 'heat_tolerance']
        )
        return {'status': 'success', 'data': breeds}

    @http.route('/api/v1/breeds/<int:breed_id>', type='json', auth='user', methods=['GET'])
    def get_breed(self, breed_id, **kwargs):
        """Get single breed details"""
        breed = request.env['farm.breed'].browse(breed_id)
        if not breed.exists():
            return {'status': 'error', 'message': 'Breed not found'}

        data = {
            'id': breed.id,
            'name': breed.name,
            'code': breed.code,
            'species': breed.species,
            'origin_country': breed.origin_country,
            'avg_milk_yield_liters': breed.avg_milk_yield_liters,
            'peak_milk_yield_liters': breed.peak_milk_yield_liters,
            'avg_lactation_days': breed.avg_lactation_days,
            'avg_fat_percentage': breed.avg_fat_percentage,
            'avg_protein_percentage': breed.avg_protein_percentage,
            'avg_mature_weight_kg': breed.avg_mature_weight_kg,
            'heat_tolerance': breed.heat_tolerance,
            'disease_resistance': breed.disease_resistance,
            'characteristics': breed.characteristics,
            'animal_count': breed.animal_count,
        }
        return {'status': 'success', 'data': data}
```

**Task 3: Write Breed Unit Tests (2h)**

```python
# odoo/addons/smart_dairy_farm/tests/test_farm_breed.py
from odoo.tests import TransactionCase, tagged

@tagged('post_install', '-at_install', 'farm')
class TestFarmBreed(TransactionCase):

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.FarmBreed = cls.env['farm.breed']

    def test_breed_creation(self):
        """Test breed creation"""
        breed = self.FarmBreed.create({
            'name': 'Test Breed',
            'code': 'TST',
            'species': 'cattle',
            'avg_milk_yield_liters': 15.0,
        })
        self.assertTrue(breed.id)
        self.assertEqual(breed.display_name, '[TST] Test Breed')

    def test_breed_code_unique(self):
        """Test breed code uniqueness"""
        self.FarmBreed.create({
            'name': 'Breed 1',
            'code': 'UNQ',
            'species': 'cattle',
        })
        with self.assertRaises(Exception):
            self.FarmBreed.create({
                'name': 'Breed 2',
                'code': 'UNQ',
                'species': 'cattle',
            })

    def test_get_breeds_by_species(self):
        """Test API method for getting breeds by species"""
        # Create test breeds
        self.FarmBreed.create({
            'name': 'Cattle Breed',
            'code': 'CTL',
            'species': 'cattle',
        })
        self.FarmBreed.create({
            'name': 'Goat Breed',
            'code': 'GOT',
            'species': 'goat',
        })

        cattle_breeds = self.FarmBreed.get_breeds_by_species('cattle')
        self.assertTrue(any(b['code'] == 'CTL' for b in cattle_breeds))
        self.assertFalse(any(b['code'] == 'GOT' for b in cattle_breeds))
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Breed Selection Components (4h)**

```dart
// lib/features/breed/presentation/widgets/breed_selector.dart
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:smart_dairy_farm/features/breed/domain/entities/breed.dart';
import 'package:smart_dairy_farm/features/breed/presentation/bloc/breed_bloc.dart';

class BreedSelector extends StatelessWidget {
  final String species;
  final int? selectedBreedId;
  final ValueChanged<Breed?> onBreedSelected;

  const BreedSelector({
    super.key,
    required this.species,
    this.selectedBreedId,
    required this.onBreedSelected,
  });

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<BreedBloc, BreedState>(
      builder: (context, state) {
        if (state is BreedLoading) {
          return const CircularProgressIndicator();
        }

        if (state is BreedLoaded) {
          final breeds = state.breeds
              .where((b) => b.species == species)
              .toList();

          return DropdownButtonFormField<int>(
            value: selectedBreedId,
            decoration: const InputDecoration(
              labelText: 'Breed',
              prefixIcon: Icon(Icons.pets),
            ),
            items: breeds.map((breed) {
              return DropdownMenuItem(
                value: breed.id,
                child: Row(
                  children: [
                    Text('[${breed.code}] '),
                    Text(breed.name),
                    if (breed.avgMilkYield > 0)
                      Text(
                        ' (${breed.avgMilkYield}L/day)',
                        style: TextStyle(
                          color: Colors.grey.shade600,
                          fontSize: 12,
                        ),
                      ),
                  ],
                ),
              );
            }).toList(),
            onChanged: (breedId) {
              final breed = breeds.firstWhere(
                (b) => b.id == breedId,
                orElse: () => breeds.first,
              );
              onBreedSelected(breed);
            },
            validator: (value) {
              if (value == null) {
                return 'Please select a breed';
              }
              return null;
            },
          );
        }

        return const Text('Failed to load breeds');
      },
    );
  }
}

// lib/features/breed/presentation/widgets/breed_card.dart
class BreedCard extends StatelessWidget {
  final Breed breed;
  final VoidCallback? onTap;

  const BreedCard({
    super.key,
    required this.breed,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Row(
            children: [
              // Breed image or icon
              Container(
                width: 60,
                height: 60,
                decoration: BoxDecoration(
                  color: _getSpeciesColor(breed.species).withOpacity(0.1),
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Icon(
                  _getSpeciesIcon(breed.species),
                  color: _getSpeciesColor(breed.species),
                  size: 32,
                ),
              ),
              const SizedBox(width: 16),
              // Breed info
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      breed.name,
                      style: Theme.of(context).textTheme.titleMedium,
                    ),
                    Text(
                      'Code: ${breed.code}',
                      style: Theme.of(context).textTheme.bodySmall,
                    ),
                    if (breed.avgMilkYield > 0)
                      Text(
                        'Avg. Yield: ${breed.avgMilkYield} L/day',
                        style: Theme.of(context).textTheme.bodySmall?.copyWith(
                          color: Colors.green.shade700,
                        ),
                      ),
                  ],
                ),
              ),
              // Heat tolerance indicator
              _HeatToleranceBadge(tolerance: breed.heatTolerance),
            ],
          ),
        ),
      ),
    );
  }

  Color _getSpeciesColor(String species) {
    switch (species) {
      case 'cattle':
        return Colors.brown;
      case 'buffalo':
        return Colors.blueGrey;
      case 'goat':
        return Colors.amber.shade700;
      default:
        return Colors.grey;
    }
  }

  IconData _getSpeciesIcon(String species) {
    switch (species) {
      case 'cattle':
      case 'buffalo':
        return Icons.pets; // Replace with custom cow icon
      case 'goat':
        return Icons.pets;
      default:
        return Icons.help_outline;
    }
  }
}

class _HeatToleranceBadge extends StatelessWidget {
  final String? tolerance;

  const _HeatToleranceBadge({this.tolerance});

  @override
  Widget build(BuildContext context) {
    if (tolerance == null) return const SizedBox.shrink();

    Color color;
    IconData icon;

    switch (tolerance) {
      case 'high':
        color = Colors.green;
        icon = Icons.wb_sunny;
        break;
      case 'medium':
        color = Colors.orange;
        icon = Icons.cloud;
        break;
      case 'low':
        color = Colors.blue;
        icon = Icons.ac_unit;
        break;
      default:
        return const SizedBox.shrink();
    }

    return Tooltip(
      message: 'Heat Tolerance: ${tolerance!.toUpperCase()}',
      child: Container(
        padding: const EdgeInsets.all(8),
        decoration: BoxDecoration(
          color: color.withOpacity(0.1),
          borderRadius: BorderRadius.circular(8),
        ),
        child: Icon(icon, color: color, size: 20),
      ),
    );
  }
}
```

**Task 2: Breed Domain & Data Layer (4h)**

```dart
// lib/features/breed/domain/entities/breed.dart
import 'package:equatable/equatable.dart';

class Breed extends Equatable {
  final int id;
  final String name;
  final String code;
  final String species;
  final String? originCountry;
  final double avgMilkYield;
  final double peakMilkYield;
  final int avgLactationDays;
  final double avgFatPercent;
  final double avgProteinPercent;
  final double avgMatureWeight;
  final String? heatTolerance;
  final String? diseaseResistance;
  final String? colorPattern;
  final int animalCount;

  const Breed({
    required this.id,
    required this.name,
    required this.code,
    required this.species,
    this.originCountry,
    this.avgMilkYield = 0,
    this.peakMilkYield = 0,
    this.avgLactationDays = 305,
    this.avgFatPercent = 0,
    this.avgProteinPercent = 0,
    this.avgMatureWeight = 0,
    this.heatTolerance,
    this.diseaseResistance,
    this.colorPattern,
    this.animalCount = 0,
  });

  @override
  List<Object?> get props => [id, code, species];
}

// lib/features/breed/data/models/breed_model.dart
import 'package:smart_dairy_farm/features/breed/domain/entities/breed.dart';

class BreedModel extends Breed {
  const BreedModel({
    required super.id,
    required super.name,
    required super.code,
    required super.species,
    super.originCountry,
    super.avgMilkYield,
    super.peakMilkYield,
    super.avgLactationDays,
    super.avgFatPercent,
    super.avgProteinPercent,
    super.avgMatureWeight,
    super.heatTolerance,
    super.diseaseResistance,
    super.colorPattern,
    super.animalCount,
  });

  factory BreedModel.fromJson(Map<String, dynamic> json) {
    return BreedModel(
      id: json['id'] as int,
      name: json['name'] as String,
      code: json['code'] as String,
      species: json['species'] as String,
      originCountry: json['origin_country'] as String?,
      avgMilkYield: (json['avg_milk_yield_liters'] as num?)?.toDouble() ?? 0,
      peakMilkYield: (json['peak_milk_yield_liters'] as num?)?.toDouble() ?? 0,
      avgLactationDays: json['avg_lactation_days'] as int? ?? 305,
      avgFatPercent: (json['avg_fat_percentage'] as num?)?.toDouble() ?? 0,
      avgProteinPercent: (json['avg_protein_percentage'] as num?)?.toDouble() ?? 0,
      avgMatureWeight: (json['avg_mature_weight_kg'] as num?)?.toDouble() ?? 0,
      heatTolerance: json['heat_tolerance'] as String?,
      diseaseResistance: json['disease_resistance'] as String?,
      colorPattern: json['color_pattern'] as String?,
      animalCount: json['animal_count'] as int? ?? 0,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'code': code,
      'species': species,
      'origin_country': originCountry,
      'avg_milk_yield_liters': avgMilkYield,
      'heat_tolerance': heatTolerance,
    };
  }
}
```

**Day 252 Deliverables:**
- [ ] FarmBreed model with production characteristics
- [ ] Default breed data for Bangladesh context
- [ ] Breed tree, form, and search views
- [ ] Breed import wizard from CSV
- [ ] Breed API endpoints
- [ ] Breed unit tests
- [ ] Flutter breed selector widget
- [ ] Breed domain and data layer
- [ ] Daily standup completed
- [ ] Code reviewed and merged

---

### Day 253 - Farm Locations & Pen Management

**Objective:** Create hierarchical farm location management with barn/pen structures and capacity tracking.

*[Content continues with similar detailed breakdown for Days 253-260]*

---

## 4. Technical Specifications

### 4.1 Database Schema

#### Core Tables

```sql
-- farm_animal: Main animal registration table
CREATE TABLE farm_animal (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    registration_number VARCHAR(50) UNIQUE NOT NULL,
    ear_tag VARCHAR(20) NOT NULL,
    rfid_tag VARCHAR(15),
    species VARCHAR(20) NOT NULL DEFAULT 'cattle',
    breed_id INTEGER REFERENCES farm_breed(id),
    gender VARCHAR(10) NOT NULL,
    animal_type VARCHAR(20),
    date_of_birth DATE NOT NULL,
    date_arrived DATE,
    dam_id INTEGER REFERENCES farm_animal(id),
    sire_id INTEGER REFERENCES farm_animal(id),
    farm_id INTEGER NOT NULL REFERENCES farm_location(id),
    pen_id INTEGER REFERENCES farm_pen(id),
    status VARCHAR(20) NOT NULL DEFAULT 'active',
    weight_kg DECIMAL(6,2),
    image BYTEA,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    create_date TIMESTAMP DEFAULT NOW(),
    write_date TIMESTAMP DEFAULT NOW()
);

-- Indexes for performance
CREATE INDEX idx_farm_animal_ear_tag ON farm_animal(ear_tag);
CREATE INDEX idx_farm_animal_rfid ON farm_animal(rfid_tag);
CREATE INDEX idx_farm_animal_status ON farm_animal(status);
CREATE INDEX idx_farm_animal_farm ON farm_animal(farm_id);
CREATE INDEX idx_farm_animal_species ON farm_animal(species);

-- farm_breed: Breed master data
CREATE TABLE farm_breed (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(10) UNIQUE NOT NULL,
    species VARCHAR(20) NOT NULL DEFAULT 'cattle',
    origin_country VARCHAR(100),
    avg_milk_yield_liters DECIMAL(5,1),
    avg_fat_percentage DECIMAL(4,2),
    heat_tolerance VARCHAR(20),
    active BOOLEAN DEFAULT TRUE
);

-- farm_location: Farm/barn hierarchy
CREATE TABLE farm_location (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(20) NOT NULL,
    location_type VARCHAR(20) NOT NULL, -- farm, barn, section
    parent_id INTEGER REFERENCES farm_location(id),
    address TEXT,
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    total_area_acres DECIMAL(8,2),
    animal_capacity INTEGER,
    company_id INTEGER NOT NULL REFERENCES res_company(id)
);

-- farm_pen: Individual pens/sheds
CREATE TABLE farm_pen (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(20) NOT NULL,
    farm_id INTEGER NOT NULL REFERENCES farm_location(id),
    pen_type VARCHAR(20), -- milking, dry, heifer, calf, bull, sick, maternity
    capacity INTEGER NOT NULL,
    area_sqm DECIMAL(8,2),
    active BOOLEAN DEFAULT TRUE
);
```

### 4.2 API Endpoints

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| GET | `/api/v1/animals` | List animals with pagination |
| GET | `/api/v1/animals/{id}` | Get animal details |
| POST | `/api/v1/animals` | Create new animal |
| PUT | `/api/v1/animals/{id}` | Update animal |
| DELETE | `/api/v1/animals/{id}` | Archive animal |
| POST | `/api/v1/animals/rfid/scan` | Process RFID scan |
| GET | `/api/v1/animals/{id}/genealogy` | Get family tree |
| POST | `/api/v1/animals/{id}/transfer` | Transfer animal |
| GET | `/api/v1/breeds` | List breeds |
| GET | `/api/v1/farms` | List farms |
| GET | `/api/v1/farms/{id}/pens` | List pens in farm |

### 4.3 UI Components (OWL)

| Component | Type | Description |
| --------- | ---- | ----------- |
| AnimalListView | Tree View | Paginated animal list with filters |
| AnimalFormView | Form View | Complete animal registration form |
| AnimalKanban | Kanban View | Visual animal cards |
| GenealogyTree | Custom Widget | Family tree visualization |
| RFIDScanner | Action Button | Trigger RFID scan |
| TransferWizard | Wizard | Multi-step transfer form |

### 4.4 Mobile Screens (Flutter)

| Screen | Route | Description |
| ------ | ----- | ----------- |
| AnimalListScreen | `/animals` | Searchable animal list |
| AnimalDetailScreen | `/animals/{id}` | Full animal profile |
| AnimalCreateScreen | `/animals/create` | New animal registration |
| RFIDScanScreen | `/scan` | Camera/NFC RFID scanning |
| GenealogyScreen | `/animals/{id}/family` | Family tree view |

---

## 5. Testing & Validation

### 5.1 Unit Tests

| Test Suite | Coverage Target | Key Tests |
| ---------- | --------------- | --------- |
| test_farm_animal | >90% | Creation, validation, age calculation |
| test_farm_breed | >85% | Breed creation, uniqueness |
| test_farm_location | >85% | Hierarchy, capacity |
| test_genealogy | >95% | Pedigree, inbreeding |

### 5.2 Integration Tests

| Test | Description | Pass Criteria |
| ---- | ----------- | ------------- |
| Animal CRUD | Full lifecycle | All operations succeed |
| RFID Scan | Scan and identify | <500ms response |
| Transfer | Move animal | History tracked |
| Genealogy | 5-generation tree | Correct relationships |

### 5.3 Performance Tests

| Test | Metric | Target |
| ---- | ------ | ------ |
| Animal List Load | Response time | <2s for 1000 animals |
| Search | Query time | <500ms |
| RFID Lookup | Response time | <500ms |
| Image Upload | Process time | <3s for 5MB |

### 5.4 Acceptance Criteria

- [ ] Can register animals with all required fields
- [ ] Ear tag validation enforces BD format
- [ ] RFID scan returns animal within 500ms
- [ ] Genealogy displays 5+ generations
- [ ] Transfer creates movement history
- [ ] Photos upload and display correctly
- [ ] Mobile app syncs offline data
- [ ] All unit tests pass (>80% coverage)

---

## 6. Risk & Mitigation

| Risk | Impact | Probability | Mitigation |
| ---- | ------ | ----------- | ---------- |
| RFID reader compatibility | High | Medium | Test multiple reader brands early |
| Performance with 1000+ animals | High | Medium | Implement pagination, caching |
| Bangladesh ear tag format changes | Low | Low | Make format configurable |
| Image storage growth | Medium | High | Implement compression, limits |

---

## 7. Dependencies & Handoffs

### 7.1 Prerequisites from Phase 4

- Odoo 19 CE environment operational
- PostgreSQL 16 database configured
- User authentication working
- Base module permissions set up

### 7.2 Handoffs to Milestone 42

- FarmAnimal model available for health records
- Farm location hierarchy for health tracking
- Animal status system for sick/quarantine
- API authentication patterns established

### 7.3 External Dependencies

- RFID hardware delivery (Day 255)
- Test mobile devices (Day 259)
- Farm domain expert review (Day 260)

---

**Milestone 41 Total Effort:** 240 person-hours (3 developers × 8 hours × 10 days)

**Document Version:** 1.0
**Last Updated:** 2026-02-03
