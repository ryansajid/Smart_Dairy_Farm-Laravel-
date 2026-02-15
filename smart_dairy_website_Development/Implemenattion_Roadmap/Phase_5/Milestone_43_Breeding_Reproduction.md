# Milestone 43: Breeding & Reproduction

## Smart Dairy Digital Smart Portal + ERP - Phase 5: Farm Management Foundation

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 43 of 150 (3 of 10 in Phase 5)                                         |
| **Title**        | Breeding & Reproduction Management                                     |
| **Phase**        | Phase 5 - Farm Management Foundation                                   |
| **Days**         | Days 271-280 (of 350 total)                                            |
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

Implement a comprehensive breeding and reproduction management system that enables Smart Dairy to track heat cycles, manage artificial insemination (AI) programs, monitor pregnancies, and optimize calving outcomes for genetic improvement.

### 1.2 Objectives

1. Create FarmBreedingRecord model for all breeding events
2. Implement heat detection and tracking system
3. Build AI scheduling with semen inventory management
4. Create semen inventory tracking with straw management
5. Implement pregnancy diagnosis and tracking
6. Build calving management and records
7. Create bull management and performance tracking
8. Implement breeding performance analytics
9. Build embryo transfer (ET) tracking
10. Develop mobile breeding entry interface

### 1.3 Key Deliverables

| Deliverable                     | Owner  | Format            | Due Day |
| ------------------------------- | ------ | ----------------- | ------- |
| FarmBreedingRecord Model        | Dev 1  | Python module     | 271     |
| FarmHeatDetection Model         | Dev 1  | Python module     | 272     |
| AI Scheduling System            | Dev 1  | Python module     | 273     |
| SemenInventory Model            | Dev 1  | Python module     | 274     |
| PregnancyDiagnosis Model        | Dev 1  | Python module     | 275     |
| CalvingRecord Model             | Dev 1  | Python module     | 276     |
| Bull Management System          | Dev 1  | Python module     | 277     |
| Breeding Analytics Dashboard    | Dev 2  | Python service    | 278     |
| EmbryoTransfer Tracking         | Dev 1  | Python module     | 279     |
| Flutter Breeding Entry UI       | Dev 3  | Dart/Flutter      | 280     |

### 1.4 Prerequisites

- Milestone 41 complete (FarmAnimal model with genealogy)
- Milestone 42 complete (Health status integration)
- Animal gender and age calculations working
- Notification system configured

### 1.5 Success Criteria

- [ ] Heat detection alerts generated within 1 hour of observation
- [ ] AI scheduling integrates with semen inventory
- [ ] Pregnancy tracking calculates expected calving dates
- [ ] Calving records link newborn calves to dams
- [ ] Breeding KPIs (conception rate, calving interval) calculated
- [ ] Bull performance metrics track offspring quality
- [ ] ET records maintain complete embryo lineage
- [ ] Unit test coverage >80% for all models
- [ ] No critical bugs open

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference        |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------- |
| RFP-FARM-007 | RFP    | Heat detection system                    | 272     | FarmHeatDetection     |
| RFP-FARM-008 | RFP    | AI scheduling and records                | 273-274 | AI scheduling         |
| RFP-FARM-009 | RFP    | Pregnancy tracking                       | 275     | PregnancyDiagnosis    |
| BRD-FM-003   | BRD    | 10% improvement in conception rates      | 271-280 | Complete milestone    |
| SRS-FM-006   | SRS    | FarmBreedingRecord model                 | 271     | Breeding record class |
| SRS-FM-007   | SRS    | Heat detection algorithm                 | 272     | Heat detection logic  |

---

## 3. Day-by-Day Breakdown

### Day 271 - Breeding Record Core Model

**Objective:** Create the foundational breeding record model for tracking all reproductive events.

#### Dev 1 - Backend Lead (8h)

**Task 1: Create FarmBreedingRecord Model (6h)**

```python
# odoo/addons/smart_dairy_farm/models/farm_breeding_record.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
from datetime import date, timedelta

class FarmBreedingRecord(models.Model):
    _name = 'farm.breeding.record'
    _description = 'Farm Breeding Record'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'breeding_date desc, id desc'
    _rec_name = 'display_name'

    # ===== IDENTIFICATION =====
    display_name = fields.Char(compute='_compute_display_name', store=True)
    record_number = fields.Char(
        string='Record Number',
        required=True,
        copy=False,
        readonly=True,
        default=lambda self: self._generate_record_number()
    )

    # ===== ANIMAL REFERENCE =====
    animal_id = fields.Many2one(
        'farm.animal',
        string='Female Animal',
        required=True,
        tracking=True,
        domain="[('gender', '=', 'female'), ('animal_type', 'in', ['dairy_cow', 'dairy_heifer', 'buffalo_cow', 'buffalo_heifer', 'goat_doe'])]"
    )
    animal_ear_tag = fields.Char(related='animal_id.ear_tag', store=True)
    animal_species = fields.Selection(related='animal_id.species', store=True)
    animal_breed = fields.Many2one(related='animal_id.breed_id', store=True)
    farm_id = fields.Many2one(related='animal_id.farm_id', store=True)

    # ===== BREEDING EVENT =====
    breeding_date = fields.Date(
        string='Breeding Date',
        required=True,
        default=fields.Date.today,
        tracking=True
    )
    breeding_time = fields.Float(string='Breeding Time (24h)')
    breeding_type = fields.Selection([
        ('ai', 'Artificial Insemination'),
        ('natural', 'Natural Mating'),
        ('et', 'Embryo Transfer'),
    ], string='Breeding Type', required=True, default='ai', tracking=True)

    # ===== AI DETAILS =====
    semen_straw_id = fields.Many2one(
        'farm.semen.straw',
        string='Semen Straw',
        domain="[('state', '=', 'available')]"
    )
    semen_batch_id = fields.Many2one(
        'farm.semen.batch',
        string='Semen Batch'
    )
    bull_id = fields.Many2one(
        'farm.animal',
        string='Sire/Bull',
        domain="[('gender', '=', 'male')]",
        tracking=True
    )
    bull_external_id = fields.Char(string='External Sire ID')
    bull_name = fields.Char(
        compute='_compute_bull_name',
        store=True
    )

    # ===== SEMEN DETAILS =====
    semen_type = fields.Selection([
        ('conventional', 'Conventional'),
        ('sexed_female', 'Sexed (Female)'),
        ('sexed_male', 'Sexed (Male)'),
    ], string='Semen Type', default='conventional')
    straws_used = fields.Integer(string='Straws Used', default=1)
    semen_quality = fields.Selection([
        ('excellent', 'Excellent'),
        ('good', 'Good'),
        ('fair', 'Fair'),
        ('poor', 'Poor'),
    ], string='Semen Quality at Insemination')

    # ===== HEAT DETECTION =====
    heat_detection_id = fields.Many2one(
        'farm.heat.detection',
        string='Heat Detection Record'
    )
    heat_signs = fields.Selection([
        ('strong', 'Strong Standing Heat'),
        ('moderate', 'Moderate Signs'),
        ('weak', 'Weak Signs'),
        ('none', 'No Clear Signs'),
    ], string='Heat Signs')
    heat_start_datetime = fields.Datetime(string='Heat Start Time')
    hours_after_heat = fields.Float(
        string='Hours After Heat Start',
        compute='_compute_hours_after_heat'
    )

    # ===== TECHNICIAN =====
    technician_id = fields.Many2one(
        'res.users',
        string='AI Technician',
        default=lambda self: self.env.user,
        tracking=True
    )
    technician_notes = fields.Text(string='Technician Notes')

    # ===== BREEDING CONDITIONS =====
    cervix_condition = fields.Selection([
        ('open', 'Open'),
        ('partially_open', 'Partially Open'),
        ('closed', 'Closed'),
    ], string='Cervix Condition')
    mucus_quality = fields.Selection([
        ('clear', 'Clear'),
        ('cloudy', 'Cloudy'),
        ('bloody', 'Bloody'),
        ('purulent', 'Purulent'),
    ], string='Mucus Quality')
    body_condition_score = fields.Selection([
        ('1', '1 - Emaciated'),
        ('2', '2 - Thin'),
        ('3', '3 - Average'),
        ('4', '4 - Fat'),
        ('5', '5 - Obese'),
    ], string='Body Condition Score')
    difficulty_level = fields.Selection([
        ('easy', 'Easy'),
        ('normal', 'Normal'),
        ('difficult', 'Difficult'),
        ('very_difficult', 'Very Difficult'),
    ], string='Insemination Difficulty', default='normal')

    # ===== PREGNANCY DIAGNOSIS =====
    pd_date = fields.Date(string='Pregnancy Check Date')
    pd_result = fields.Selection([
        ('pending', 'Pending'),
        ('positive', 'Pregnant'),
        ('negative', 'Not Pregnant'),
        ('uncertain', 'Uncertain'),
        ('abortion', 'Abortion'),
        ('resorption', 'Early Embryonic Loss'),
    ], string='PD Result', default='pending', tracking=True)
    pd_method = fields.Selection([
        ('rectal', 'Rectal Palpation'),
        ('ultrasound', 'Ultrasound'),
        ('blood_test', 'Blood Test'),
        ('milk_test', 'Milk Progesterone'),
    ], string='PD Method')
    pd_days_post_breeding = fields.Integer(
        compute='_compute_pd_days',
        string='Days Post Breeding (at PD)'
    )
    pregnancy_id = fields.Many2one(
        'farm.pregnancy.diagnosis',
        string='Pregnancy Record'
    )

    # ===== EXPECTED DATES =====
    expected_calving_date = fields.Date(
        compute='_compute_expected_dates',
        store=True,
        string='Expected Calving Date'
    )
    days_to_calving = fields.Integer(
        compute='_compute_days_to_calving',
        string='Days to Calving'
    )
    gestation_days = fields.Integer(
        string='Gestation Period (days)',
        default=280,
        help='Species-specific gestation: Cattle 280, Buffalo 310, Goat 150'
    )

    # ===== CALVING OUTCOME =====
    calving_record_id = fields.Many2one(
        'farm.calving.record',
        string='Calving Record'
    )
    calving_date = fields.Date(
        related='calving_record_id.calving_date',
        store=True
    )
    calf_ids = fields.One2many(
        'farm.animal',
        compute='_compute_calf_ids',
        string='Calves'
    )
    actual_gestation_days = fields.Integer(
        compute='_compute_actual_gestation',
        string='Actual Gestation Days'
    )

    # ===== BREEDING METRICS =====
    service_number = fields.Integer(
        string='Service Number',
        compute='_compute_service_number',
        store=True,
        help='Number of breedings for this conception'
    )
    conception_rate = fields.Float(
        compute='_compute_conception_rate',
        string='Historical Conception Rate %'
    )
    repeat_breeding = fields.Boolean(
        string='Repeat Breeding',
        compute='_compute_repeat_breeding',
        store=True
    )

    # ===== STATE =====
    state = fields.Selection([
        ('draft', 'Draft'),
        ('confirmed', 'Confirmed'),
        ('pd_scheduled', 'PD Scheduled'),
        ('pregnant', 'Pregnant'),
        ('not_pregnant', 'Not Pregnant'),
        ('calved', 'Calved'),
        ('aborted', 'Aborted'),
        ('cancelled', 'Cancelled'),
    ], string='Status', default='draft', tracking=True)

    # ===== COMPANY =====
    company_id = fields.Many2one(
        'res.company',
        default=lambda self: self.env.company
    )

    # ===== NOTES =====
    notes = fields.Html(string='Notes')

    # ===== SQL CONSTRAINTS =====
    _sql_constraints = [
        ('record_number_unique',
         'unique(record_number, company_id)',
         'Breeding record number must be unique!'),
    ]

    # ===== COMPUTED METHODS =====
    @api.depends('animal_id', 'breeding_date', 'breeding_type')
    def _compute_display_name(self):
        for record in self:
            type_short = {'ai': 'AI', 'natural': 'NM', 'et': 'ET'}.get(record.breeding_type, '')
            record.display_name = f"{record.animal_id.ear_tag} - {type_short} ({record.breeding_date})"

    @api.model
    def _generate_record_number(self):
        today = date.today().strftime('%Y%m%d')
        sequence = self.env['ir.sequence'].next_by_code('farm.breeding.record')
        return f"BR-{today}-{sequence or '0001'}"

    @api.depends('bull_id', 'bull_external_id')
    def _compute_bull_name(self):
        for record in self:
            if record.bull_id:
                record.bull_name = record.bull_id.name
            elif record.bull_external_id:
                record.bull_name = f"External: {record.bull_external_id}"
            else:
                record.bull_name = "Unknown"

    @api.depends('breeding_date', 'heat_start_datetime')
    def _compute_hours_after_heat(self):
        for record in self:
            if record.breeding_date and record.heat_start_datetime:
                breeding_dt = fields.Datetime.to_datetime(
                    f"{record.breeding_date} 12:00:00"
                )
                delta = breeding_dt - record.heat_start_datetime
                record.hours_after_heat = delta.total_seconds() / 3600
            else:
                record.hours_after_heat = 0

    @api.depends('breeding_date', 'pd_date')
    def _compute_pd_days(self):
        for record in self:
            if record.breeding_date and record.pd_date:
                record.pd_days_post_breeding = (record.pd_date - record.breeding_date).days
            else:
                record.pd_days_post_breeding = 0

    @api.depends('breeding_date', 'gestation_days', 'pd_result')
    def _compute_expected_dates(self):
        for record in self:
            if record.breeding_date and record.pd_result == 'positive':
                record.expected_calving_date = record.breeding_date + timedelta(
                    days=record.gestation_days
                )
            else:
                record.expected_calving_date = False

    def _compute_days_to_calving(self):
        today = date.today()
        for record in self:
            if record.expected_calving_date:
                record.days_to_calving = (record.expected_calving_date - today).days
            else:
                record.days_to_calving = 0

    def _compute_calf_ids(self):
        for record in self:
            if record.calving_record_id:
                record.calf_ids = record.calving_record_id.calf_ids
            else:
                record.calf_ids = self.env['farm.animal']

    @api.depends('breeding_date', 'calving_date')
    def _compute_actual_gestation(self):
        for record in self:
            if record.breeding_date and record.calving_date:
                record.actual_gestation_days = (record.calving_date - record.breeding_date).days
            else:
                record.actual_gestation_days = 0

    @api.depends('animal_id', 'breeding_date')
    def _compute_service_number(self):
        for record in self:
            # Count previous unsuccessful breedings since last calving
            previous = self.search([
                ('animal_id', '=', record.animal_id.id),
                ('breeding_date', '<', record.breeding_date),
                ('state', 'in', ['not_pregnant', 'aborted']),
            ])
            # Filter to this breeding cycle (since last calving)
            last_calving = self.search([
                ('animal_id', '=', record.animal_id.id),
                ('state', '=', 'calved'),
            ], order='calving_date desc', limit=1)

            if last_calving:
                previous = previous.filtered(
                    lambda r: r.breeding_date > last_calving.calving_date
                )

            record.service_number = len(previous) + 1

    def _compute_conception_rate(self):
        for record in self:
            total = self.search_count([
                ('animal_id', '=', record.animal_id.id),
                ('state', 'not in', ['draft', 'cancelled']),
            ])
            successful = self.search_count([
                ('animal_id', '=', record.animal_id.id),
                ('state', 'in', ['pregnant', 'calved']),
            ])
            record.conception_rate = (successful / total * 100) if total else 0

    @api.depends('service_number')
    def _compute_repeat_breeding(self):
        for record in self:
            record.repeat_breeding = record.service_number > 3

    # ===== ONCHANGE METHODS =====
    @api.onchange('animal_id')
    def _onchange_animal(self):
        if self.animal_id:
            # Set gestation days based on species
            gestation_map = {
                'cattle': 280,
                'buffalo': 310,
                'goat': 150,
            }
            self.gestation_days = gestation_map.get(self.animal_id.species, 280)

    @api.onchange('breeding_type')
    def _onchange_breeding_type(self):
        if self.breeding_type == 'natural':
            self.semen_straw_id = False
            self.semen_type = False

    @api.onchange('semen_straw_id')
    def _onchange_semen_straw(self):
        if self.semen_straw_id:
            self.semen_batch_id = self.semen_straw_id.batch_id
            self.bull_id = self.semen_straw_id.bull_id
            self.semen_type = self.semen_straw_id.semen_type

    # ===== CONSTRAINT METHODS =====
    @api.constrains('animal_id')
    def _check_animal_gender(self):
        for record in self:
            if record.animal_id.gender != 'female':
                raise ValidationError('Breeding records can only be created for female animals!')

    @api.constrains('breeding_date')
    def _check_breeding_date(self):
        for record in self:
            if record.breeding_date > date.today():
                raise ValidationError('Breeding date cannot be in the future!')

    @api.constrains('pd_date', 'breeding_date')
    def _check_pd_date(self):
        for record in self:
            if record.pd_date and record.breeding_date:
                if record.pd_date < record.breeding_date:
                    raise ValidationError('PD date must be after breeding date!')
                if (record.pd_date - record.breeding_date).days < 25:
                    raise ValidationError('PD should be performed at least 25 days after breeding!')

    # ===== ACTION METHODS =====
    def action_confirm(self):
        """Confirm the breeding record"""
        for record in self:
            # Consume semen straw if AI
            if record.breeding_type == 'ai' and record.semen_straw_id:
                record.semen_straw_id.action_use()
            record.write({'state': 'confirmed'})

    def action_schedule_pd(self):
        """Schedule pregnancy diagnosis"""
        for record in self:
            # Default PD at 30 days post breeding
            pd_date = record.breeding_date + timedelta(days=30)
            record.write({
                'state': 'pd_scheduled',
                'pd_date': pd_date
            })
            # Create activity for PD
            record.activity_schedule(
                'smart_dairy_farm.mail_activity_type_pd_check',
                date_deadline=pd_date,
                summary=f'Pregnancy check for {record.animal_id.ear_tag}',
            )

    def action_record_pd_result(self):
        """Open wizard to record PD result"""
        return {
            'name': 'Record PD Result',
            'type': 'ir.actions.act_window',
            'res_model': 'farm.breeding.pd.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_breeding_record_id': self.id}
        }

    def action_mark_pregnant(self):
        """Mark as pregnant"""
        self.write({
            'state': 'pregnant',
            'pd_result': 'positive'
        })
        # Update animal status
        for record in self:
            record.animal_id.action_mark_pregnant()

    def action_mark_not_pregnant(self):
        """Mark as not pregnant"""
        self.write({
            'state': 'not_pregnant',
            'pd_result': 'negative'
        })
        # Return animal to active
        for record in self:
            record.animal_id.action_return_to_active()

    def action_record_calving(self):
        """Open calving record wizard"""
        return {
            'name': 'Record Calving',
            'type': 'ir.actions.act_window',
            'res_model': 'farm.calving.record',
            'view_mode': 'form',
            'target': 'new',
            'context': {
                'default_breeding_record_id': self.id,
                'default_dam_id': self.animal_id.id,
                'default_sire_id': self.bull_id.id if self.bull_id else False,
            }
        }

    def action_view_animal(self):
        """Open animal form"""
        return {
            'type': 'ir.actions.act_window',
            'res_model': 'farm.animal',
            'view_mode': 'form',
            'res_id': self.animal_id.id
        }
```

**Task 2: Create Supporting Models (2h)**

```python
# Additional breeding-related models

class FarmHeatDetection(models.Model):
    _name = 'farm.heat.detection'
    _description = 'Heat Detection Record'
    _order = 'detection_datetime desc'

    animal_id = fields.Many2one('farm.animal', required=True)
    detection_datetime = fields.Datetime(required=True, default=fields.Datetime.now)
    detection_method = fields.Selection([
        ('visual', 'Visual Observation'),
        ('activity_monitor', 'Activity Monitor'),
        ('tail_paint', 'Tail Paint'),
        ('heat_mount', 'Heat Mount Detector'),
        ('pedometer', 'Pedometer'),
    ], required=True, default='visual')
    intensity = fields.Selection([
        ('weak', 'Weak'),
        ('moderate', 'Moderate'),
        ('strong', 'Strong'),
    ], required=True)
    signs_observed = fields.Many2many('farm.heat.sign', string='Signs Observed')
    mucus_quality = fields.Selection([
        ('clear', 'Clear'),
        ('cloudy', 'Cloudy'),
        ('bloody', 'Bloody'),
    ])
    standing_heat = fields.Boolean(string='Standing Heat Observed')
    optimal_ai_window_start = fields.Datetime(compute='_compute_ai_window')
    optimal_ai_window_end = fields.Datetime(compute='_compute_ai_window')
    detected_by = fields.Many2one('res.users', default=lambda self: self.env.user)
    breeding_record_id = fields.Many2one('farm.breeding.record')
    notes = fields.Text()

    @api.depends('detection_datetime', 'intensity')
    def _compute_ai_window(self):
        for record in self:
            if record.detection_datetime:
                # AM/PM rule: If detected in AM, breed in PM. If detected in PM, breed next AM.
                # Optimal window is typically 12-18 hours after standing heat
                record.optimal_ai_window_start = record.detection_datetime + timedelta(hours=12)
                record.optimal_ai_window_end = record.detection_datetime + timedelta(hours=18)
            else:
                record.optimal_ai_window_start = False
                record.optimal_ai_window_end = False


class FarmHeatSign(models.Model):
    _name = 'farm.heat.sign'
    _description = 'Heat Sign'

    name = fields.Char(required=True)
    name_bn = fields.Char(string='Name (Bangla)')
    description = fields.Text()
    reliability = fields.Selection([
        ('primary', 'Primary Sign'),
        ('secondary', 'Secondary Sign'),
    ])


class FarmSemenBatch(models.Model):
    _name = 'farm.semen.batch'
    _description = 'Semen Batch'
    _order = 'arrival_date desc'

    name = fields.Char(required=True)
    batch_number = fields.Char(required=True)
    bull_id = fields.Many2one('farm.animal', domain="[('gender', '=', 'male')]")
    bull_external_id = fields.Char(string='External Bull ID')
    bull_name = fields.Char()
    breed_id = fields.Many2one('farm.breed')
    supplier_id = fields.Many2one('res.partner')
    arrival_date = fields.Date(required=True)
    expiry_date = fields.Date()
    total_straws = fields.Integer()
    available_straws = fields.Integer(compute='_compute_available')
    used_straws = fields.Integer(compute='_compute_available')
    semen_type = fields.Selection([
        ('conventional', 'Conventional'),
        ('sexed_female', 'Sexed (Female)'),
        ('sexed_male', 'Sexed (Male)'),
    ], default='conventional')
    motility_percent = fields.Float(string='Motility %')
    concentration = fields.Float(string='Concentration (million/ml)')
    storage_tank_id = fields.Many2one('farm.semen.tank')
    canister_position = fields.Char()
    cost_per_straw = fields.Monetary(currency_field='currency_id')
    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.company.currency_id)
    straw_ids = fields.One2many('farm.semen.straw', 'batch_id')
    notes = fields.Text()
    state = fields.Selection([
        ('available', 'Available'),
        ('depleted', 'Depleted'),
        ('expired', 'Expired'),
    ], default='available')

    def _compute_available(self):
        for record in self:
            record.used_straws = len(record.straw_ids.filtered(lambda s: s.state == 'used'))
            record.available_straws = record.total_straws - record.used_straws


class FarmSemenStraw(models.Model):
    _name = 'farm.semen.straw'
    _description = 'Semen Straw'

    name = fields.Char(compute='_compute_name', store=True)
    batch_id = fields.Many2one('farm.semen.batch', required=True)
    bull_id = fields.Many2one(related='batch_id.bull_id')
    bull_name = fields.Char(related='batch_id.bull_name')
    semen_type = fields.Selection(related='batch_id.semen_type')
    straw_number = fields.Char()
    state = fields.Selection([
        ('available', 'Available'),
        ('reserved', 'Reserved'),
        ('used', 'Used'),
        ('damaged', 'Damaged'),
        ('expired', 'Expired'),
    ], default='available')
    breeding_record_id = fields.Many2one('farm.breeding.record')
    used_date = fields.Date()
    used_for_animal_id = fields.Many2one('farm.animal')

    @api.depends('batch_id', 'straw_number')
    def _compute_name(self):
        for record in self:
            record.name = f"{record.batch_id.name or ''} - {record.straw_number or ''}"

    def action_use(self):
        """Mark straw as used"""
        self.write({
            'state': 'used',
            'used_date': date.today()
        })


class FarmPregnancyDiagnosis(models.Model):
    _name = 'farm.pregnancy.diagnosis'
    _description = 'Pregnancy Diagnosis'
    _order = 'pd_date desc'

    animal_id = fields.Many2one('farm.animal', required=True)
    breeding_record_id = fields.Many2one('farm.breeding.record')
    pd_date = fields.Date(required=True, default=fields.Date.today)
    pd_method = fields.Selection([
        ('rectal', 'Rectal Palpation'),
        ('ultrasound', 'Ultrasound'),
        ('blood_test', 'Blood Test'),
        ('milk_test', 'Milk Progesterone'),
    ], required=True, default='rectal')
    result = fields.Selection([
        ('positive', 'Pregnant'),
        ('negative', 'Not Pregnant'),
        ('uncertain', 'Uncertain'),
    ], required=True)
    days_pregnant = fields.Integer()
    fetal_count = fields.Integer(string='Number of Fetuses', default=1)
    fetal_viability = fields.Selection([
        ('viable', 'Viable'),
        ('questionable', 'Questionable'),
        ('non_viable', 'Non-viable'),
    ])
    expected_calving_date = fields.Date()
    veterinarian_id = fields.Many2one('res.partner')
    performed_by = fields.Many2one('res.users', default=lambda self: self.env.user)
    notes = fields.Text()


class FarmCalvingRecord(models.Model):
    _name = 'farm.calving.record'
    _description = 'Calving Record'
    _inherit = ['mail.thread']
    _order = 'calving_date desc'

    breeding_record_id = fields.Many2one('farm.breeding.record')
    dam_id = fields.Many2one('farm.animal', string='Dam (Mother)', required=True)
    sire_id = fields.Many2one('farm.animal', string='Sire (Father)')
    sire_external_id = fields.Char()
    calving_date = fields.Date(required=True, default=fields.Date.today)
    calving_time = fields.Float()
    gestation_days = fields.Integer(compute='_compute_gestation')
    calving_ease = fields.Selection([
        ('1', '1 - No Assistance'),
        ('2', '2 - Minor Assistance'),
        ('3', '3 - Mechanical Assistance'),
        ('4', '4 - Veterinary Assistance'),
        ('5', '5 - Cesarean Section'),
    ], required=True, default='1')
    presentation = fields.Selection([
        ('normal', 'Normal (Anterior)'),
        ('posterior', 'Posterior (Backwards)'),
        ('breech', 'Breech'),
        ('transverse', 'Transverse'),
    ], default='normal')
    calf_count = fields.Integer(string='Number of Calves', default=1)
    calf_ids = fields.One2many('farm.animal', 'calving_record_id', string='Calves')
    stillborn_count = fields.Integer(default=0)
    complications = fields.Text()
    dam_health_status = fields.Selection([
        ('normal', 'Normal'),
        ('retained_placenta', 'Retained Placenta'),
        ('milk_fever', 'Milk Fever'),
        ('metritis', 'Metritis'),
        ('other', 'Other'),
    ], default='normal')
    placenta_expelled = fields.Boolean()
    placenta_expelled_hours = fields.Float()
    assisted_by = fields.Many2one('res.users', default=lambda self: self.env.user)
    veterinarian_id = fields.Many2one('res.partner')
    notes = fields.Html()

    @api.depends('breeding_record_id', 'calving_date')
    def _compute_gestation(self):
        for record in self:
            if record.breeding_record_id and record.calving_date:
                record.gestation_days = (
                    record.calving_date - record.breeding_record_id.breeding_date
                ).days
            else:
                record.gestation_days = 0

    @api.model
    def create(self, vals):
        record = super().create(vals)
        # Update breeding record state
        if record.breeding_record_id:
            record.breeding_record_id.write({
                'state': 'calved',
                'calving_record_id': record.id
            })
        # Update dam status
        record.dam_id.action_mark_status('lactating')
        return record
```

#### Dev 2 - Full-Stack (8h)

**Task 1: Breeding Sequences and Data (3h)**

```xml
<!-- odoo/addons/smart_dairy_farm/data/breeding_sequence_data.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <data noupdate="1">
        <record id="seq_farm_breeding_record" model="ir.sequence">
            <field name="name">Farm Breeding Record</field>
            <field name="code">farm.breeding.record</field>
            <field name="prefix">%(year)s</field>
            <field name="padding">6</field>
        </record>

        <!-- Heat Signs -->
        <record id="heat_sign_mounting" model="farm.heat.sign">
            <field name="name">Mounting Other Cows</field>
            <field name="name_bn">অন্য গরুর উপর চড়া</field>
            <field name="reliability">primary</field>
        </record>
        <record id="heat_sign_standing" model="farm.heat.sign">
            <field name="name">Standing to be Mounted</field>
            <field name="name_bn">চড়ার জন্য দাঁড়িয়ে থাকা</field>
            <field name="reliability">primary</field>
        </record>
        <record id="heat_sign_mucus" model="farm.heat.sign">
            <field name="name">Clear Mucus Discharge</field>
            <field name="name_bn">পরিষ্কার শ্লেষ্মা নিঃসরণ</field>
            <field name="reliability">primary</field>
        </record>
        <record id="heat_sign_restless" model="farm.heat.sign">
            <field name="name">Restlessness</field>
            <field name="name_bn">অস্থিরতা</field>
            <field name="reliability">secondary</field>
        </record>
        <record id="heat_sign_bellowing" model="farm.heat.sign">
            <field name="name">Bellowing/Vocalization</field>
            <field name="name_bn">চিৎকার করা</field>
            <field name="reliability">secondary</field>
        </record>
        <record id="heat_sign_reduced_milk" model="farm.heat.sign">
            <field name="name">Reduced Milk Yield</field>
            <field name="name_bn">দুধ কমে যাওয়া</field>
            <field name="reliability">secondary</field>
        </record>
        <record id="heat_sign_swollen_vulva" model="farm.heat.sign">
            <field name="name">Swollen Vulva</field>
            <field name="name_bn">যোনি ফোলা</field>
            <field name="reliability">secondary</field>
        </record>
    </data>
</odoo>
```

**Task 2: Breeding Calendar Integration (3h)**

```python
# odoo/addons/smart_dairy_farm/models/farm_breeding_calendar.py
from odoo import models, fields, api

class FarmBreedingCalendar(models.Model):
    _name = 'farm.breeding.calendar'
    _description = 'Breeding Calendar Event'
    _inherit = ['mail.thread']

    name = fields.Char(compute='_compute_name', store=True)
    animal_id = fields.Many2one('farm.animal', required=True)
    event_type = fields.Selection([
        ('heat_expected', 'Expected Heat'),
        ('heat_observed', 'Heat Observed'),
        ('ai_scheduled', 'AI Scheduled'),
        ('pd_scheduled', 'PD Scheduled'),
        ('dry_off', 'Dry Off Date'),
        ('calving_expected', 'Expected Calving'),
    ], required=True)
    event_date = fields.Date(required=True)
    event_datetime = fields.Datetime()
    breeding_record_id = fields.Many2one('farm.breeding.record')
    color = fields.Integer(compute='_compute_color')
    notes = fields.Text()

    @api.depends('animal_id', 'event_type', 'event_date')
    def _compute_name(self):
        for record in self:
            record.name = f"{record.animal_id.ear_tag} - {record.event_type} ({record.event_date})"

    def _compute_color(self):
        color_map = {
            'heat_expected': 4,  # Light blue
            'heat_observed': 3,  # Yellow
            'ai_scheduled': 6,   # Red
            'pd_scheduled': 7,   # Purple
            'dry_off': 8,        # Dark blue
            'calving_expected': 10,  # Green
        }
        for record in self:
            record.color = color_map.get(record.event_type, 0)
```

**Task 3: Breeding Unit Tests (2h)**

```python
# odoo/addons/smart_dairy_farm/tests/test_farm_breeding.py
from odoo.tests import TransactionCase, tagged
from odoo.exceptions import ValidationError
from datetime import date, timedelta

@tagged('post_install', '-at_install', 'farm')
class TestFarmBreeding(TransactionCase):

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.FarmBreedingRecord = cls.env['farm.breeding.record']
        cls.FarmAnimal = cls.env['farm.animal']
        cls.FarmBreed = cls.env['farm.breed']
        cls.FarmLocation = cls.env['farm.location']

        # Create test data
        cls.breed = cls.FarmBreed.create({
            'name': 'Test Breed',
            'code': 'TST',
            'species': 'cattle',
        })
        cls.farm = cls.FarmLocation.create({
            'name': 'Test Farm',
            'code': 'TF001',
            'location_type': 'farm',
        })
        cls.cow = cls.FarmAnimal.create({
            'name': 'Test Cow',
            'ear_tag': 'BD-2024-000001',
            'species': 'cattle',
            'breed_id': cls.breed.id,
            'gender': 'female',
            'date_of_birth': date.today() - timedelta(days=365*3),
            'farm_id': cls.farm.id,
        })
        cls.bull = cls.FarmAnimal.create({
            'name': 'Test Bull',
            'ear_tag': 'BD-2024-000002',
            'species': 'cattle',
            'breed_id': cls.breed.id,
            'gender': 'male',
            'date_of_birth': date.today() - timedelta(days=365*4),
            'farm_id': cls.farm.id,
        })

    def test_breeding_record_creation(self):
        """Test breeding record creation"""
        record = self.FarmBreedingRecord.create({
            'animal_id': self.cow.id,
            'breeding_type': 'ai',
            'bull_id': self.bull.id,
        })
        self.assertTrue(record.id)
        self.assertEqual(record.gestation_days, 280)

    def test_breeding_female_only(self):
        """Test breeding record can only be for female"""
        with self.assertRaises(ValidationError):
            self.FarmBreedingRecord.create({
                'animal_id': self.bull.id,
                'breeding_type': 'ai',
            })

    def test_expected_calving_date(self):
        """Test expected calving date calculation"""
        record = self.FarmBreedingRecord.create({
            'animal_id': self.cow.id,
            'breeding_type': 'ai',
            'bull_id': self.bull.id,
        })
        record.action_mark_pregnant()
        expected = date.today() + timedelta(days=280)
        self.assertEqual(record.expected_calving_date, expected)

    def test_service_number(self):
        """Test service number calculation"""
        # First breeding
        record1 = self.FarmBreedingRecord.create({
            'animal_id': self.cow.id,
            'breeding_type': 'ai',
            'breeding_date': date.today() - timedelta(days=60),
        })
        record1.write({'state': 'not_pregnant'})

        # Second breeding
        record2 = self.FarmBreedingRecord.create({
            'animal_id': self.cow.id,
            'breeding_type': 'ai',
        })
        self.assertEqual(record2.service_number, 2)
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Breeding Record Views (4h)**

```xml
<!-- Views for breeding records - similar structure to health views -->
```

**Task 2: Flutter Breeding Entry Screen (4h)**

```dart
// lib/features/breeding/presentation/pages/breeding_form_screen.dart
// Similar structure to health form with breeding-specific fields
```

*[Days 272-280 continue with similar detailed breakdowns]*

---

## 4. Technical Specifications

### 4.1 Database Schema

```sql
-- farm_breeding_record: Breeding events
CREATE TABLE farm_breeding_record (
    id SERIAL PRIMARY KEY,
    record_number VARCHAR(30) UNIQUE,
    animal_id INTEGER NOT NULL REFERENCES farm_animal(id),
    breeding_date DATE NOT NULL,
    breeding_type VARCHAR(20) NOT NULL,
    semen_straw_id INTEGER REFERENCES farm_semen_straw(id),
    bull_id INTEGER REFERENCES farm_animal(id),
    bull_external_id VARCHAR(50),
    heat_detection_id INTEGER REFERENCES farm_heat_detection(id),
    pd_date DATE,
    pd_result VARCHAR(20),
    expected_calving_date DATE,
    calving_record_id INTEGER REFERENCES farm_calving_record(id),
    state VARCHAR(20) DEFAULT 'draft',
    company_id INTEGER NOT NULL
);

CREATE INDEX idx_breeding_animal ON farm_breeding_record(animal_id);
CREATE INDEX idx_breeding_date ON farm_breeding_record(breeding_date);
CREATE INDEX idx_breeding_state ON farm_breeding_record(state);
```

### 4.2 API Endpoints

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| GET | `/api/v1/breeding/records` | List breeding records |
| POST | `/api/v1/breeding/records` | Create breeding record |
| POST | `/api/v1/breeding/heat-detection` | Record heat detection |
| GET | `/api/v1/breeding/pd-due` | Animals due for PD |
| GET | `/api/v1/breeding/calving-expected` | Expected calvings |
| GET | `/api/v1/semen/inventory` | Semen inventory |

---

## 5. Testing & Validation

### 5.1 Acceptance Criteria

- [ ] Heat detection alerts within 1 hour
- [ ] AI scheduling integrates with semen inventory
- [ ] Expected calving dates calculated correctly
- [ ] Calving records create calf animals
- [ ] Breeding KPIs calculated accurately
- [ ] Service number tracked correctly

---

## 6. Risk & Mitigation

| Risk | Impact | Mitigation |
| ---- | ------ | ---------- |
| Heat detection timing | High | Multiple detection methods |
| Semen inventory sync | Medium | Real-time inventory updates |

---

## 7. Dependencies & Handoffs

### 7.1 Prerequisites
- FarmAnimal with gender validation
- Health status system for breeding restrictions

### 7.2 Handoffs to Milestone 44
- Breeding status for production tracking
- Calving dates for lactation calculation

---

**Milestone 43 Total Effort:** 240 person-hours

**Document Version:** 1.0
**Last Updated:** 2026-02-03
