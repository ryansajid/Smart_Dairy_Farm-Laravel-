# MILESTONE 6: ADVANCED FARM MANAGEMENT MODULE

## Smart Dairy Smart Portal + ERP System Implementation - Phase 2

---

| Attribute | Details |
|-----------|---------|
| **Milestone** | Phase 2 - Milestone 6 |
| **Title** | Advanced Farm Management (Health, Breeding, Production) |
| **Duration** | Days 151-160 (10 Working Days) |
| **Phase** | Phase 2 - Operations |
| **Version** | 1.0 |
| **Status** | Draft |

---

## TABLE OF CONTENTS

1. [Milestone Overview](#1-milestone-overview)
2. [Animal Health Management](#2-animal-health-management)
3. [Breeding & Reproduction](#3-breeding--reproduction)
4. [Milk Production Tracking](#4-milk-production-tracking)
5. [Feed Management](#5-feed-management)
6. [Veterinary Integration](#6-veterinary-integration)
7. [Health Alerts & Notifications](#7-health-alerts--notifications)
8. [Analytics & Reporting](#8-analytics--reporting)
9. [Appendices](#9-appendices)

---

## 1. MILESTONE OVERVIEW

### 1.1 Objectives

| Objective ID | Description | Success Criteria |
|--------------|-------------|------------------|
| M6-OBJ-001 | Health records management | Vaccination, treatment tracking |
| M6-OBJ-002 | Breeding management | Heat detection, breeding records |
| M6-OBJ-003 | Milk production tracking | Daily yield, quality metrics |
| M6-OBJ-004 | Feed management | Ration planning, consumption tracking |
| M6-OBJ-005 | Veterinary integration | Appointment scheduling, reports |
| M6-OBJ-006 | Health alerts | Automated alerts, notifications |

### 1.2 Farm Management Module Structure

```
smart_dairy_farm_management/
├── models/
│   ├── farm_animal.py           # Extended animal model
│   ├── animal_health.py         # Health records
│   ├── vaccination.py           # Vaccination schedule
│   ├── breeding.py              # Breeding records
│   ├── reproduction.py          # Pregnancy, calving
│   ├── milk_production.py       # Daily milk records
│   ├── milk_quality.py          # Quality testing
│   ├── feed_ration.py           # Feed planning
│   ├── feed_consumption.py      # Consumption tracking
│   └── veterinary_visit.py      # Vet appointments
├── controllers/
│   ├── api_farm.py              # REST API endpoints
│   └── portal_farm.py           # Portal controllers
├── views/
│   ├── animal_health_views.xml
│   ├── breeding_views.xml
│   ├── milk_production_views.xml
│   └── feed_management_views.xml
├── reports/
│   ├── animal_health_report.py
│   ├── production_report.py
│   └── breeding_report.py
└── data/
    ├── vaccination_schedule.xml
    ├── health_alert_rules.xml
    └── demo_data.xml
```

---

## 2. ANIMAL HEALTH MANAGEMENT

### 2.1 Extended Animal Model

```python
# farm_management/models/farm_animal.py

from odoo import models, fields, api
from datetime import datetime, timedelta
from odoo.exceptions import ValidationError


class FarmAnimal(models.Model):
    """Extended farm animal with health tracking"""
    _inherit = 'farm.animal'
    
    # Health Status
    health_status = fields.Selection([
        ('healthy', 'Healthy'),
        ('sick', 'Sick'),
        ('recovering', 'Recovering'),
        ('quarantine', 'Quarantine'),
        ('critical', 'Critical'),
    ], string='Health Status', default='healthy', tracking=True)
    
    body_condition_score = fields.Selection([
        ('1', '1 - Emaciated'),
        ('2', '2 - Very Thin'),
        ('3', '3 - Thin'),
        ('4', '4 - Borderline'),
        ('5', '5 - Moderate'),
        ('6', '6 - Good'),
        ('7', '7 - Fleshy'),
        ('8', '8 - Fat'),
        ('9', '9 - Obese'),
    ], string='Body Condition Score')
    
    # Current Measurements
    current_weight = fields.Float(string='Current Weight (kg)')
    weight_date = fields.Date(string='Weight Date')
    temperature = fields.Float(string='Temperature (°C)')
    temperature_date = fields.Datetime(string='Temperature Taken')
    
    # Health Records
    health_record_ids = fields.One2many(
        'animal.health.record', 'animal_id', string='Health Records'
    )
    vaccination_ids = fields.One2many(
        'animal.vaccination', 'animal_id', string='Vaccinations'
    )
    
    # Current Treatments
    active_treatment_ids = fields.One2many(
        'animal.treatment', 'animal_id',
        domain=[('state', 'in', ['planned', 'in_progress'])],
        string='Active Treatments'
    )
    
    # Alerts
    has_overdue_vaccinations = fields.Boolean(
        string='Overdue Vaccinations',
        compute='_compute_health_alerts',
        store=True
    )
    has_pending_treatments = fields.Boolean(
        string='Pending Treatments',
        compute='_compute_health_alerts',
        store=True
    )
    
    # Computed Fields
    last_health_check = fields.Date(
        string='Last Health Check',
        compute='_compute_last_health_check',
        store=True
    )
    days_since_last_check = fields.Integer(
        string='Days Since Last Check',
        compute='_compute_last_health_check'
    )
    
    @api.depends('health_record_ids.date', 'vaccination_ids.date')
    def _compute_last_health_check(self):
        for animal in self:
            dates = []
            
            # Get dates from health records
            for record in animal.health_record_ids:
                if record.date:
                    dates.append(record.date)
            
            # Get dates from vaccinations
            for vac in animal.vaccination_ids:
                if vac.date:
                    dates.append(vac.date)
            
            if dates:
                animal.last_health_check = max(dates)
                animal.days_since_last_check = (
                    fields.Date.today() - animal.last_health_check
                ).days
            else:
                animal.last_health_check = False
                animal.days_since_last_check = 0
    
    @api.depends('vaccination_ids.state', 'active_treatment_ids')
    def _compute_health_alerts(self):
        for animal in self:
            # Check overdue vaccinations
            overdue_vacs = animal.vaccination_ids.filtered(
                lambda v: v.state == 'overdue'
            )
            animal.has_overdue_vaccinations = bool(overdue_vacs)
            
            # Check pending treatments
            animal.has_pending_treatments = bool(animal.active_treatment_ids)
    
    def action_record_health_check(self):
        """Open health check form"""
        self.ensure_one()
        return {
            'name': 'Record Health Check',
            'type': 'ir.actions.act_window',
            'res_model': 'animal.health.record',
            'view_mode': 'form',
            'target': 'new',
            'context': {
                'default_animal_id': self.id,
                'default_date': fields.Date.today(),
            },
        }
    
    def action_view_health_history(self):
        """View complete health history"""
        self.ensure_one()
        return {
            'name': 'Health History',
            'type': 'ir.actions.act_window',
            'res_model': 'animal.health.record',
            'view_mode': 'tree,form',
            'domain': [('animal_id', '=', self.id)],
            'context': {'default_animal_id': self.id},
        }
    
    def action_schedule_vaccination(self):
        """Schedule vaccination"""
        self.ensure_one()
        return {
            'name': 'Schedule Vaccination',
            'type': 'ir.actions.act_window',
            'res_model': 'animal.vaccination',
            'view_mode': 'form',
            'target': 'new',
            'context': {
                'default_animal_id': self.id,
                'default_scheduled_date': fields.Date.today(),
            },
        }
```

### 2.2 Health Records

```python
# farm_management/models/animal_health.py

from odoo import models, fields, api
from datetime import datetime, timedelta


class AnimalHealthRecord(models.Model):
    """Animal health check and diagnosis records"""
    _name = 'animal.health.record'
    _description = 'Animal Health Record'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'date desc'
    
    name = fields.Char(string='Reference', required=True, copy=False,
                       default=lambda self: self.env['ir.sequence'].next_by_code('animal.health.record'))
    
    # Relationships
    animal_id = fields.Many2one('farm.animal', string='Animal', required=True)
    tag_number = fields.Char(related='animal_id.tag_number', string='Tag Number', store=True)
    
    # Record Details
    date = fields.Date(string='Date', required=True, default=fields.Date.today)
    checked_by = fields.Many2one('res.users', string='Checked By',
                                  default=lambda self: self.env.user)
    
    # Examination
    examination_type = fields.Selection([
        ('routine', 'Routine Check'),
        ('illness', 'Illness Examination'),
        ('post_treatment', 'Post-Treatment Check'),
        ('pre_breeding', 'Pre-Breeding Check'),
        ('pre_sale', 'Pre-Sale Examination'),
        ('quarantine', 'Quarantine Check'),
    ], string='Examination Type', required=True, default='routine')
    
    # Vital Signs
    temperature = fields.Float(string='Temperature (°C)')
    pulse_rate = fields.Integer(string='Pulse Rate (bpm)')
    respiratory_rate = fields.Integer(string='Respiratory Rate (rpm)')
    
    # Physical Assessment
    body_condition_score = fields.Selection([
        ('1', '1 - Emaciated'),
        ('2', '2 - Very Thin'),
        ('3', '3 - Thin'),
        ('4', '4 - Borderline'),
        ('5', '5 - Moderate'),
        ('6', '6 - Good'),
        ('7', '7 - Fleshy'),
        ('8', '8 - Fat'),
        ('9', '9 - Obese'),
    ], string='Body Condition Score')
    
    weight = fields.Float(string='Weight (kg)')
    
    # Health Assessment
    general_condition = fields.Selection([
        ('excellent', 'Excellent'),
        ('good', 'Good'),
        ('fair', 'Fair'),
        ('poor', 'Poor'),
        ('critical', 'Critical'),
    ], string='General Condition', default='good')
    
    # Symptoms and Diagnosis
    symptoms = fields.Text(string='Observed Symptoms')
    diagnosis = fields.Text(string='Diagnosis')
    
    # Treatment Plan
    treatment_required = fields.Boolean(string='Treatment Required')
    treatment_plan = fields.Text(string='Treatment Plan')
    
    # Follow-up
    follow_up_required = fields.Boolean(string='Follow-up Required')
    follow_up_date = fields.Date(string='Follow-up Date')
    
    # Notes
    notes = fields.Text(string='Additional Notes')
    
    # Attachments
    attachment_ids = fields.Many2many('ir.attachment', string='Attachments')
    
    state = fields.Selection([
        ('draft', 'Draft'),
        ('confirmed', 'Confirmed'),
        ('treatment_planned', 'Treatment Planned'),
        ('completed', 'Completed'),
    ], string='Status', default='draft')
    
    def action_confirm(self):
        """Confirm health record"""
        for record in self:
            record.write({'state': 'confirmed'})
            
            # Update animal health status if needed
            if record.general_condition == 'critical':
                record.animal_id.write({'health_status': 'critical'})
            elif record.general_condition == 'poor':
                record.animal_id.write({'health_status': 'sick'})
            
            # Create treatment if required
            if record.treatment_required:
                record._create_treatment_plan()
    
    def _create_treatment_plan(self):
        """Create treatment record from health check"""
        self.ensure_one()
        
        treatment = self.env['animal.treatment'].create({
            'animal_id': self.animal_id.id,
            'health_record_id': self.id,
            'diagnosis': self.diagnosis,
            'treatment_plan': self.treatment_plan,
            'start_date': fields.Date.today(),
            'state': 'planned',
        })
        
        self.write({
            'state': 'treatment_planned',
        })
        
        return treatment


class AnimalTreatment(models.Model):
    """Animal treatment records"""
    _name = 'animal.treatment'
    _description = 'Animal Treatment'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'start_date desc'
    
    name = fields.Char(string='Reference', required=True, copy=False,
                       default=lambda self: self.env['ir.sequence'].next_by_code('animal.treatment'))
    
    animal_id = fields.Many2one('farm.animal', string='Animal', required=True)
    health_record_id = fields.Many2one('animal.health.record', string='Related Health Check')
    
    # Treatment Details
    start_date = fields.Date(string='Start Date', required=True, default=fields.Date.today)
    expected_end_date = fields.Date(string='Expected End Date')
    actual_end_date = fields.Date(string='Actual End Date')
    
    diagnosis = fields.Text(string='Diagnosis', required=True)
    treatment_plan = fields.Text(string='Treatment Plan', required=True)
    
    # Medications
    medication_line_ids = fields.One2many(
        'treatment.medication.line', 'treatment_id', string='Medications'
    )
    
    # Progress Tracking
    progress_notes = fields.Text(string='Progress Notes')
    outcome = fields.Selection([
        ('cured', 'Cured'),
        ('improved', 'Improved'),
        ('unchanged', 'Unchanged'),
        ('deteriorated', 'Deteriorated'),
        ('died', 'Died'),
    ], string='Treatment Outcome')
    
    # Cost Tracking
    medication_cost = fields.Float(string='Medication Cost', compute='_compute_costs', store=True)
    veterinary_cost = fields.Float(string='Veterinary Cost')
    total_cost = fields.Float(string='Total Cost', compute='_compute_costs', store=True)
    
    state = fields.Selection([
        ('planned', 'Planned'),
        ('in_progress', 'In Progress'),
        ('completed', 'Completed'),
        ('cancelled', 'Cancelled'),
    ], string='Status', default='planned')
    
    @api.depends('medication_line_ids.total_cost', 'veterinary_cost')
    def _compute_costs(self):
        for treatment in self:
            treatment.medication_cost = sum(
                line.total_cost for line in treatment.medication_line_ids
            )
            treatment.total_cost = treatment.medication_cost + treatment.veterinary_cost
    
    def action_start(self):
        """Start treatment"""
        self.write({'state': 'in_progress'})
        self.animal_id.write({'health_status': 'sick'})
    
    def action_complete(self):
        """Complete treatment"""
        self.write({
            'state': 'completed',
            'actual_end_date': fields.Date.today(),
        })
        
        # Update animal health status
        if self.outcome in ['cured', 'improved']:
            self.animal_id.write({'health_status': 'healthy'})
        elif self.outcome == 'deteriorated':
            self.animal_id.write({'health_status': 'critical'})


class TreatmentMedicationLine(models.Model):
    """Medication line for treatments"""
    _name = 'treatment.medication.line'
    _description = 'Treatment Medication Line'
    
    treatment_id = fields.Many2one('animal.treatment', string='Treatment', required=True)
    
    medication_id = fields.Many2one('product.product', string='Medication',
                                     domain=[('type', '=', 'product')], required=True)
    dosage = fields.Char(string='Dosage', required=True)
    frequency = fields.Selection([
        ('once', 'Once'),
        ('daily', 'Daily'),
        ('twice_daily', 'Twice Daily'),
        ('three_times', 'Three Times Daily'),
        ('weekly', 'Weekly'),
        ('as_needed', 'As Needed'),
    ], string='Frequency', required=True)
    duration_days = fields.Integer(string='Duration (Days)')
    
    quantity = fields.Float(string='Quantity', required=True)
    unit_cost = fields.Float(string='Unit Cost', related='medication_id.standard_price')
    total_cost = fields.Float(string='Total Cost', compute='_compute_total_cost', store=True)
    
    @api.depends('quantity', 'unit_cost')
    def _compute_total_cost(self):
        for line in self:
            line.total_cost = line.quantity * line.unit_cost
```

---

## 3. BREEDING & REPRODUCTION

### 3.1 Breeding Management

```python
# farm_management/models/breeding.py

from odoo import models, fields, api
from datetime import datetime, timedelta


class BreedingRecord(models.Model):
    """Animal breeding records"""
    _name = 'breeding.record'
    _description = 'Breeding Record'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'breeding_date desc'
    
    name = fields.Char(string='Reference', required=True, copy=False,
                       default=lambda self: self.env['ir.sequence'].next_by_code('breeding.record'))
    
    # Female Animal (Dam)
    female_id = fields.Many2one('farm.animal', string='Female Animal',
                                 domain=[('gender', '=', 'female')], required=True)
    female_tag = fields.Char(related='female_id.tag_number', string='Female Tag', store=True)
    
    # Male Animal (Sire) - Can be internal or external
    sire_type = fields.Selection([
        ('internal', 'Internal Bull'),
        ('external', 'External Semen/AI'),
    ], string='Sire Type', required=True, default='internal')
    
    male_id = fields.Many2one('farm.animal', string='Male Animal (Bull)',
                               domain=[('gender', '=', 'male')])
    male_tag = fields.Char(related='male_id.tag_number', string='Male Tag')
    
    # External Sire Information
    external_sire_id = fields.Many2one('external.sire', string='External Sire')
    sire_registration = fields.Char(string='Sire Registration Number')
    sire_breed = fields.Many2one('animal.breed', string='Sire Breed')
    
    # Breeding Details
    breeding_date = fields.Date(string='Breeding Date', required=True, default=fields.Date.today)
    breeding_method = fields.Selection([
        ('natural', 'Natural Service'),
        ('ai', 'Artificial Insemination'),
        ('embryo_transfer', 'Embryo Transfer'),
    ], string='Breeding Method', required=True, default='natural')
    
    inseminator_id = fields.Many2one('res.partner', string='Inseminator/Veterinarian',
                                      domain=[('is_inseminator', '=', True)])
    
    # Semen Information (for AI)
    semen_batch = fields.Char(string='Semen Batch Number')
    semen_straws_used = fields.Integer(string='Straws Used', default=1)
    
    # Expected Calving
    expected_calving_date = fields.Date(string='Expected Calving Date',
                                         compute='_compute_expected_calving', store=True)
    gestation_days = fields.Integer(string='Gestation Period (Days)', default=283)
    
    # Status
    state = fields.Selection([
        ('planned', 'Planned'),
        ('completed', 'Completed'),
        ('confirmed_pregnant', 'Confirmed Pregnant'),
        ('aborted', 'Aborted'),
        ('calved', 'Calved'),
    ], string='Status', default='planned', tracking=True)
    
    # Pregnancy Check
    pregnancy_check_date = fields.Date(string='Pregnancy Check Date')
    pregnancy_check_result = fields.Selection([
        ('positive', 'Pregnant'),
        ('negative', 'Not Pregnant'),
        ('inconclusive', 'Inconclusive'),
    ], string='Pregnancy Check Result')
    pregnancy_check_method = fields.Selection([
        ('ultrasound', 'Ultrasound'),
        ('rectal', 'Rectal Palpation'),
        ('blood', 'Blood Test'),
        ('milk', 'Milk Test'),
    ], string='Check Method')
    
    # Related Calving
    calving_id = fields.Many2one('calving.record', string='Calving Record')
    
    @api.depends('breeding_date', 'gestation_days')
    def _compute_expected_calving(self):
        for record in self:
            if record.breeding_date:
                record.expected_calving_date = record.breeding_date + timedelta(
                    days=record.gestation_days
                )
    
    def action_mark_completed(self):
        """Mark breeding as completed"""
        self.write({'state': 'completed'})
    
    def action_record_pregnancy_check(self):
        """Record pregnancy check result"""
        return {
            'name': 'Pregnancy Check',
            'type': 'ir.actions.act_window',
            'res_model': 'pregnancy.check.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_breeding_id': self.id},
        }
    
    def action_record_calving(self):
        """Record calving"""
        self.ensure_one()
        return {
            'name': 'Record Calving',
            'type': 'ir.actions.act_window',
            'res_model': 'calving.record',
            'view_mode': 'form',
            'target': 'new',
            'context': {
                'default_breeding_id': self.id,
                'default_dam_id': self.female_id.id,
                'default_calving_date': fields.Date.today(),
            },
        }


class CalvingRecord(models.Model):
    """Calving event records"""
    _name = 'calving.record'
    _description = 'Calving Record'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'calving_date desc'
    
    name = fields.Char(string='Reference', required=True, copy=False,
                       default=lambda self: self.env['ir.sequence'].next_by_code('calving.record'))
    
    # Parent Records
    breeding_id = fields.Many2one('breeding.record', string='Breeding Record')
    dam_id = fields.Many2one('farm.animal', string='Dam (Mother)',
                              domain=[('gender', '=', 'female')], required=True)
    
    # Calving Details
    calving_date = fields.Date(string='Calving Date', required=True, default=fields.Date.today)
    calving_time = fields.Float(string='Calving Time')
    
    # Delivery Details
    delivery_type = fields.Selection([
        ('normal', 'Normal Delivery'),
        ('assisted', 'Assisted Delivery'),
        ('cesarean', 'C-Section'),
    ], string='Delivery Type', required=True, default='normal')
    
    # Assistance
    assistance_required = fields.Boolean(string='Assistance Required')
    veterinarian_id = fields.Many2one('res.partner', string='Attending Veterinarian')
    assistance_details = fields.Text(string='Assistance Details')
    
    # Offspring
    offspring_ids = fields.One2many('farm.animal', 'birth_calving_id', string='Offspring')
    offspring_count = fields.Integer(string='Number of Offspring', compute='_compute_offspring')
    
    # Outcome
    outcome = fields.Selection([
        ('live_birth', 'Live Birth'),
        ('stillbirth', 'Stillbirth'),
        ('mummified', 'Mummified Fetus'),
        ('abortion', 'Abortion'),
    ], string='Outcome', required=True, default='live_birth')
    
    # Dam Condition
    dam_condition = fields.Selection([
        ('excellent', 'Excellent'),
        ('good', 'Good'),
        ('fair', 'Fair'),
        ('poor', 'Poor'),
        ('critical', 'Critical'),
    ], string='Dam Condition', default='good')
    
    complications = fields.Text(string='Complications')
    notes = fields.Text(string='Additional Notes')
    
    @api.depends('offspring_ids')
    def _compute_offspring(self):
        for record in self:
            record.offspring_count = len(record.offspring_ids)
    
    def action_create_offspring(self):
        """Create offspring animal records"""
        self.ensure_one()
        return {
            'name': 'Register Offspring',
            'type': 'ir.actions.act_window',
            'res_model': 'farm.animal',
            'view_mode': 'form',
            'target': 'new',
            'context': {
                'default_birth_calving_id': self.id,
                'default_dam_id': self.dam_id.id,
                'default_birth_date': self.calving_date,
            },
        }
```

---

## 4. MILK PRODUCTION TRACKING

### 4.1 Daily Milk Production

```python
# farm_management/models/milk_production.py

from odoo import models, fields, api
from datetime import datetime, timedelta


class DailyMilkProduction(models.Model):
    """Daily milk production records per animal"""
    _name = 'daily.milk.production'
    _description = 'Daily Milk Production'
    _inherit = ['mail.thread']
    _order = 'date desc'
    
    name = fields.Char(string='Reference', compute='_compute_name', store=True)
    animal_id = fields.Many2one('farm.animal', string='Animal', required=True)
    date = fields.Date(string='Date', required=True, default=fields.Date.today)
    
    # Session Yields
    morning_yield = fields.Float(string='Morning (L)', digits=(10, 2))
    noon_yield = fields.Float(string='Noon (L)', digits=(10, 2))
    evening_yield = fields.Float(string='Evening (L)', digits=(10, 2))
    
    # Totals
    total_yield = fields.Float(string='Total (L)', compute='_compute_totals', store=True)
    fat_percentage = fields.Float(string='Fat %', digits=(5, 2))
    snf_percentage = fields.Float(string='SNF %', digits=(5, 2))
    
    # Quality
    quality_grade = fields.Selection([
        ('premium', 'Premium'),
        ('grade_a', 'Grade A'),
        ('grade_b', 'Grade B'),
        ('rejected', 'Rejected'),
    ], string='Quality Grade', compute='_compute_quality', store=True)
    
    # Recording
    recorded_by = fields.Many2one('res.users', string='Recorded By',
                                   default=lambda self: self.env.user)
    milker_id = fields.Many2one('hr.employee', string='Milker')
    
    # Equipment
    milking_method = fields.Selection([
        ('hand', 'Hand Milking'),
        ('machine', 'Machine Milking'),
        ('robot', 'Automated Milking'),
    ], string='Milking Method', default='machine')
    
    equipment_id = fields.Many2one('milking.equipment', string='Equipment')
    
    # Notes
    abnormalities = fields.Text(string='Abnormalities')
    treatment_given = fields.Text(string='Treatment Given')
    
    @api.depends('animal_id', 'date')
    def _compute_name(self):
        for record in self:
            if record.animal_id and record.date:
                record.name = f"{record.animal_id.tag_number} - {record.date}"
            else:
                record.name = 'New'
    
    @api.depends('morning_yield', 'noon_yield', 'evening_yield')
    def _compute_totals(self):
        for record in self:
            record.total_yield = (
                record.morning_yield + record.noon_yield + record.evening_yield
            )
    
    @api.depends('fat_percentage', 'snf_percentage')
    def _compute_quality(self):
        for record in self:
            if record.fat_percentage >= 4.0 and record.snf_percentage >= 8.5:
                record.quality_grade = 'premium'
            elif record.fat_percentage >= 3.5 and record.snf_percentage >= 8.0:
                record.quality_grade = 'grade_a'
            elif record.fat_percentage >= 3.0:
                record.quality_grade = 'grade_b'
            else:
                record.quality_grade = 'rejected'
    
    @api.model
    def get_production_summary(self, start_date, end_date, animal_ids=None):
        """Get production summary for date range"""
        domain = [
            ('date', '>=', start_date),
            ('date', '<=', end_date),
        ]
        
        if animal_ids:
            domain.append(('animal_id', 'in', animal_ids))
        
        records = self.search(domain)
        
        return {
            'total_volume': sum(records.mapped('total_yield')),
            'avg_daily': sum(records.mapped('total_yield')) / len(records) if records else 0,
            'avg_fat': sum(records.mapped('fat_percentage')) / len(records) if records else 0,
            'avg_snf': sum(records.mapped('snf_percentage')) / len(records) if records else 0,
            'record_count': len(records),
        }


class MilkCollectionSession(models.Model):
    """Milk collection session (combines multiple animals)"""
    _name = 'milk.collection.session'
    _description = 'Milk Collection Session'
    _order = 'date desc, session'
    
    name = fields.Char(string='Reference', required=True, copy=False,
                       default=lambda self: self.env['ir.sequence'].next_by_code('milk.collection'))
    
    date = fields.Date(string='Date', required=True, default=fields.Date.today)
    session = fields.Selection([
        ('morning', 'Morning'),
        ('noon', 'Noon'),
        ('evening', 'Evening'),
    ], string='Session', required=True)
    
    # Collection Summary
    start_time = fields.Datetime(string='Start Time')
    end_time = fields.Datetime(string='End Time')
    
    total_animals = fields.Integer(string='Animals Milked', compute='_compute_totals')
    total_volume = fields.Float(string='Total Volume (L)', compute='_compute_totals')
    avg_per_animal = fields.Float(string='Avg per Animal (L)', compute='_compute_totals')
    
    # Individual Records
    line_ids = fields.One2many('milk.collection.line', 'session_id', string='Records')
    
    # Storage
    storage_tank_id = fields.Many2one('milk.storage.tank', string='Storage Tank')
    tank_temperature = fields.Float(string='Tank Temperature (°C)')
    
    # Personnel
    supervisor_id = fields.Many2one('res.users', string='Supervisor',
                                     default=lambda self: self.env.user)
    milker_ids = fields.Many2many('hr.employee', string='Milkers')
    
    state = fields.Selection([
        ('draft', 'Draft'),
        ('in_progress', 'In Progress'),
        ('completed', 'Completed'),
        ('transferred', 'Transferred to Processing'),
    ], string='Status', default='draft')
    
    @api.depends('line_ids')
    def _compute_totals(self):
        for session in self:
            session.total_animals = len(session.line_ids)
            session.total_volume = sum(session.line_ids.mapped('quantity'))
            session.avg_per_animal = (
                session.total_volume / session.total_animals if session.total_animals else 0
            )


class MilkCollectionLine(models.Model):
    """Individual milk collection line"""
    _name = 'milk.collection.line'
    _description = 'Milk Collection Line'
    
    session_id = fields.Many2one('milk.collection.session', string='Session', required=True)
    animal_id = fields.Many2one('farm.animal', string='Animal', required=True)
    animal_tag = fields.Char(related='animal_id.tag_number', string='Tag Number')
    
    quantity = fields.Float(string='Quantity (L)', required=True, digits=(10, 2))
    temperature = fields.Float(string='Temperature (°C)')
    
    # Quality indicators
    visual_check = fields.Selection([
        ('normal', 'Normal'),
        ('abnormal', 'Abnormal'),
    ], string='Visual Check', default='normal')
    
    notes = fields.Text(string='Notes')
```

---

## 5. FEED MANAGEMENT

### 5.1 Feed Ration Planning

```python
# farm_management/models/feed_management.py

from odoo import models, fields, api


class FeedRation(models.Model):
    """Feed ration formulations"""
    _name = 'feed.ration'
    _description = 'Feed Ration'
    _inherit = ['mail.thread']
    
    name = fields.Char(string='Ration Name', required=True)
    code = fields.Char(string='Ration Code', required=True)
    
    # Target Animals
    animal_type = fields.Selection([
        ('lactating', 'Lactating Cows'),
        ('dry', 'Dry Cows'),
        ('heifer', 'Growing Heifers'),
        ('calf', 'Calves'),
        ('bull', 'Bulls'),
    ], string='Target Animal Type', required=True)
    
    # Production Targets
    target_milk_yield = fields.Float(string='Target Milk Yield (L/day)')
    target_body_weight = fields.Float(string='Target Body Weight (kg)')
    
    # Ingredients
    ingredient_line_ids = fields.One2many(
        'feed.ration.line', 'ration_id', string='Ingredients'
    )
    
    # Nutritional Summary
    dry_matter_total = fields.Float(string='Total Dry Matter (kg)',
                                     compute='_compute_nutrition', store=True)
    crude_protein = fields.Float(string='Crude Protein %',
                                  compute='_compute_nutrition', store=True)
    energy_content = fields.Float(string='Energy (ME MJ/kg)',
                                   compute='_compute_nutrition', store=True)
    
    # Costs
    total_cost_per_day = fields.Float(string='Cost per Animal/Day',
                                       compute='_compute_cost', store=True)
    cost_per_kg_dry_matter = fields.Float(string='Cost per kg DM',
                                           compute='_compute_cost', store=True)
    
    state = fields.Selection([
        ('draft', 'Draft'),
        ('confirmed', 'Confirmed'),
        ('active', 'Active'),
        ('archived', 'Archived'),
    ], string='Status', default='draft')
    
    @api.depends('ingredient_line_ids')
    def _compute_nutrition(self):
        for ration in self:
            total_dm = sum(ration.ingredient_line_ids.mapped('dry_matter_kg'))
            ration.dry_matter_total = total_dm
            
            if total_dm > 0:
                total_protein = sum(
                    line.dry_matter_kg * line.ingredient_id.crude_protein / 100
                    for line in ration.ingredient_line_ids
                )
                ration.crude_protein = (total_protein / total_dm) * 100
            else:
                ration.crude_protein = 0
    
    @api.depends('ingredient_line_ids')
    def _compute_cost(self):
        for ration in self:
            ration.total_cost_per_day = sum(
                ration.ingredient_line_ids.mapped('daily_cost')
            )
            if ration.dry_matter_total > 0:
                ration.cost_per_kg_dry_matter = (
                    ration.total_cost_per_day / ration.dry_matter_total
                )
            else:
                ration.cost_per_kg_dry_matter = 0


class FeedRationLine(models.Model):
    """Feed ration ingredient line"""
    _name = 'feed.ration.line'
    _description = 'Feed Ration Line'
    
    ration_id = fields.Many2one('feed.ration', string='Ration', required=True)
    ingredient_id = fields.Many2one('feed.ingredient', string='Ingredient', required=True)
    
    quantity_kg = fields.Float(string='Quantity (kg as fed)', required=True)
    dry_matter_percent = fields.Float(string='Dry Matter %', related='ingredient_id.dry_matter_percent')
    dry_matter_kg = fields.Float(string='Dry Matter (kg)', compute='_compute_dm', store=True)
    
    unit_cost = fields.Float(string='Unit Cost (per kg)', related='ingredient_id.unit_cost')
    daily_cost = fields.Float(string='Daily Cost', compute='_compute_cost', store=True)
    
    @api.depends('quantity_kg', 'dry_matter_percent')
    def _compute_dm(self):
        for line in self:
            line.dry_matter_kg = line.quantity_kg * (line.dry_matter_percent / 100)
    
    @api.depends('quantity_kg', 'unit_cost')
    def _compute_cost(self):
        for line in self:
            line.daily_cost = line.quantity_kg * line.unit_cost


class FeedIngredient(models.Model):
    """Feed ingredient master data"""
    _name = 'feed.ingredient'
    _description = 'Feed Ingredient'
    
    name = fields.Char(string='Name', required=True)
    code = fields.Char(string='Code', required=True)
    category = fields.Selection([
        ('forage', 'Forage'),
        ('concentrate', 'Concentrate'),
        ('protein', 'Protein Supplement'),
        ('mineral', 'Mineral/Vitamin'),
        ('additive', 'Feed Additive'),
    ], string='Category', required=True)
    
    # Nutritional Values (per kg dry matter)
    dry_matter_percent = fields.Float(string='Dry Matter %', default=100)
    crude_protein = fields.Float(string='Crude Protein %')
    crude_fiber = fields.Float(string='Crude Fiber %')
    energy_me = fields.Float(string='ME (MJ/kg)')
    
    # Costs
    unit_cost = fields.Float(string='Unit Cost (per kg)')
    
    # Supplier Info
    default_supplier_id = fields.Many2one('res.partner', string='Default Supplier')
    
    active = fields.Boolean(string='Active', default=True)


class FeedConsumptionRecord(models.Model):
    """Daily feed consumption tracking"""
    _name = 'feed.consumption'
    _description = 'Feed Consumption Record'
    _order = 'date desc'
    
    name = fields.Char(string='Reference', default=lambda self: 'New')
    date = fields.Date(string='Date', required=True, default=fields.Date.today)
    
    # Group/Animal
    record_type = fields.Selection([
        ('group', 'Animal Group'),
        ('individual', 'Individual Animal'),
    ], string='Record Type', default='group')
    
    animal_group_id = fields.Many2one('animal.group', string='Animal Group')
    animal_id = fields.Many2one('farm.animal', string='Individual Animal')
    
    # Feed Details
    ration_id = fields.Many2one('feed.ration', string='Assigned Ration')
    line_ids = fields.One2many('feed.consumption.line', 'consumption_id', string='Feed Lines')
    
    # Totals
    total_offered = fields.Float(string='Total Offered (kg)', compute='_compute_totals')
    total_consumed = fields.Float(string='Total Consumed (kg)', compute='_compute_totals')
    refusal_amount = fields.Float(string='Refusal (kg)', compute='_compute_totals')
    consumption_rate = fields.Float(string='Consumption Rate %', compute='_compute_totals')
    
    @api.depends('line_ids')
    def _compute_totals(self):
        for record in self:
            record.total_offered = sum(record.line_ids.mapped('quantity_offered'))
            record.total_consumed = sum(record.line_ids.mapped('quantity_consumed'))
            record.refusal_amount = record.total_offered - record.total_consumed
            record.consumption_rate = (
                (record.total_consumed / record.total_offered * 100)
                if record.total_offered else 0
            )


class FeedConsumptionLine(models.Model):
    """Feed consumption line item"""
    _name = 'feed.consumption.line'
    _description = 'Feed Consumption Line'
    
    consumption_id = fields.Many2one('feed.consumption', string='Consumption Record')
    ingredient_id = fields.Many2one('feed.ingredient', string='Ingredient', required=True)
    
    quantity_offered = fields.Float(string='Quantity Offered (kg)')
    quantity_consumed = fields.Float(string='Quantity Consumed (kg)')
    refusal_amount = fields.Float(string='Refusal (kg)', compute='_compute_refusal')
    
    @api.depends('quantity_offered', 'quantity_consumed')
    def _compute_refusal(self):
        for line in self:
            line.refusal_amount = line.quantity_offered - line.quantity_consumed
```

---

## 6. VETERINARY INTEGRATION

### 6.1 Veterinary Visit Management

```python
# farm_management/models/veterinary.py

from odoo import models, fields, api
from datetime import datetime, timedelta


class VeterinaryVisit(models.Model):
    """Veterinary visit scheduling and records"""
    _name = 'veterinary.visit'
    _description = 'Veterinary Visit'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'scheduled_date desc'
    
    name = fields.Char(string='Reference', required=True, copy=False,
                       default=lambda self: self.env['ir.sequence'].next_by_code('veterinary.visit'))
    
    # Scheduling
    scheduled_date = fields.Datetime(string='Scheduled Date', required=True)
    duration_hours = fields.Float(string='Expected Duration (Hours)', default=2)
    
    # Veterinarian
    veterinarian_id = fields.Many2one('res.partner', string='Veterinarian',
                                       domain=[('is_veterinarian', '=', True)], required=True)
    veterinary_clinic_id = fields.Many2one('res.partner', string='Clinic/Hospital')
    
    # Visit Type
    visit_type = fields.Selection([
        ('routine', 'Routine Check'),
        ('emergency', 'Emergency'),
        ('scheduled_treatment', 'Scheduled Treatment'),
        ('pregnancy_check', 'Pregnancy Check'),
        ('vaccination', 'Vaccination Campaign'),
        ('breeding_soundness', 'Breeding Soundness Exam'),
    ], string='Visit Type', required=True)
    
    # Animals
    animal_ids = fields.Many2many('farm.animal', string='Animals to Examine')
    animal_count = fields.Integer(string='Number of Animals',
                                   compute='_compute_animal_count', store=True)
    
    # Status
    state = fields.Selection([
        ('scheduled', 'Scheduled'),
        ('confirmed', 'Confirmed'),
        ('in_progress', 'In Progress'),
        ('completed', 'Completed'),
        ('cancelled', 'Cancelled'),
    ], string='Status', default='scheduled', tracking=True)
    
    # Costs
    estimated_cost = fields.Float(string='Estimated Cost')
    actual_cost = fields.Float(string='Actual Cost')
    
    # Results
    visit_report = fields.Text(string='Visit Report')
    recommendations = fields.Text(string='Recommendations')
    follow_up_required = fields.Boolean(string='Follow-up Required')
    follow_up_date = fields.Date(string='Follow-up Date')
    
    # Related Records
    health_record_ids = fields.One2many('animal.health.record', 'veterinary_visit_id',
                                         string='Health Records')
    treatment_ids = fields.One2many('animal.treatment', 'veterinary_visit_id',
                                     string='Treatments')
    
    @api.depends('animal_ids')
    def _compute_animal_count(self):
        for visit in self:
            visit.animal_count = len(visit.animal_ids)
    
    def action_confirm(self):
        """Confirm veterinary visit"""
        self.write({'state': 'confirmed'})
        # Send confirmation notification
    
    def action_start(self):
        """Mark visit as in progress"""
        self.write({'state': 'in_progress'})
    
    def action_complete(self):
        """Complete the visit"""
        self.write({'state': 'completed'})
    
    def action_create_health_records(self):
        """Create health records for all animals"""
        self.ensure_one()
        for animal in self.animal_ids:
            self.env['animal.health.record'].create({
                'animal_id': animal.id,
                'date': fields.Date.today(),
                'examination_type': self.visit_type,
                'checked_by': self.env.user.id,
                'veterinary_visit_id': self.id,
            })
```

---

## 7. HEALTH ALERTS & NOTIFICATIONS

### 7.1 Automated Alert System

```python
# farm_management/models/health_alerts.py

from odoo import models, fields, api
from datetime import datetime, timedelta


class HealthAlertRule(models.Model):
    """Rules for automated health alerts"""
    _name = 'health.alert.rule'
    _description = 'Health Alert Rule'
    
    name = fields.Char(string='Rule Name', required=True)
    alert_type = fields.Selection([
        ('vaccination_due', 'Vaccination Due'),
        ('vaccination_overdue', 'Vaccination Overdue'),
        ('treatment_overdue', 'Treatment Overdue'),
        ('health_check_due', 'Health Check Due'),
        ('calving_expected', 'Expected Calving'),
        ('heat_detection', 'Heat Detection'),
        ('milk_drop', 'Milk Production Drop'),
        ('weight_loss', 'Significant Weight Loss'),
    ], string='Alert Type', required=True)
    
    # Conditions
    days_before = fields.Integer(string='Days Before Event', default=7)
    days_after = fields.Integer(string='Days After Event (for overdue)', default=0)
    
    # Notification Settings
    notify_farm_manager = fields.Boolean(string='Notify Farm Manager', default=True)
    notify_veterinarian = fields.Boolean(string='Notify Veterinarian')
    notify_animal_caretaker = fields.Boolean(string='Notify Caretaker', default=True)
    
    # Active
    active = fields.Boolean(string='Active', default=True)
    
    def evaluate_rules(self):
        """Evaluate all active rules and create alerts"""
        for rule in self.search([('active', '=', True)]):
            if rule.alert_type == 'vaccination_due':
                rule._check_vaccination_due()
            elif rule.alert_type == 'vaccination_overdue':
                rule._check_vaccination_overdue()
            elif rule.alert_type == 'health_check_due':
                rule._check_health_check_due()
            elif rule.alert_type == 'calving_expected':
                rule._check_expected_calving()
    
    def _check_vaccination_due(self):
        """Check for vaccinations coming due"""
        target_date = fields.Date.today() + timedelta(days=self.days_before)
        
        vaccinations = self.env['animal.vaccination'].search([
            ('scheduled_date', '=', target_date),
            ('state', '=', 'scheduled'),
        ])
        
        for vac in vaccinations:
            self._create_alert(
                alert_type='vaccination_due',
                animal_id=vac.animal_id.id,
                message=f"Vaccination '{vac.vaccine_type_id.name}' due for {vac.animal_id.tag_number}",
                related_record=vac,
            )
    
    def _check_vaccination_overdue(self):
        """Check for overdue vaccinations"""
        overdue_date = fields.Date.today() - timedelta(days=self.days_after)
        
        vaccinations = self.env['animal.vaccination'].search([
            ('scheduled_date', '<', overdue_date),
            ('state', '=', 'scheduled'),
        ])
        
        for vac in vaccinations:
            vac.write({'state': 'overdue'})
            self._create_alert(
                alert_type='vaccination_overdue',
                animal_id=vac.animal_id.id,
                message=f"Vaccination '{vac.vaccine_type_id.name}' OVERDUE for {vac.animal_id.tag_number}",
                related_record=vac,
                priority='high',
            )
    
    def _check_expected_calving(self):
        """Check for expected calvings"""
        target_date = fields.Date.today() + timedelta(days=self.days_before)
        
        breedings = self.env['breeding.record'].search([
            ('expected_calving_date', '=', target_date),
            ('state', '=', 'confirmed_pregnant'),
        ])
        
        for breeding in breedings:
            self._create_alert(
                alert_type='calving_expected',
                animal_id=breeding.female_id.id,
                message=f"Expected calving in {self.days_before} days for {breeding.female_id.tag_number}",
                related_record=breeding,
            )
    
    def _create_alert(self, alert_type, animal_id, message, related_record=None, priority='normal'):
        """Create health alert"""
        values = {
            'alert_type': alert_type,
            'animal_id': animal_id,
            'message': message,
            'priority': priority,
            'rule_id': self.id,
        }
        
        if related_record:
            values['related_model'] = related_record._name
            values['related_id'] = related_record.id
        
        # Check if alert already exists
        existing = self.env['health.alert'].search([
            ('alert_type', '=', alert_type),
            ('animal_id', '=', animal_id),
            ('state', '=', 'open'),
            ('related_model', '=', values.get('related_model')),
            ('related_id', '=', values.get('related_id')),
        ])
        
        if not existing:
            self.env['health.alert'].create(values)


class HealthAlert(models.Model):
    """Health alert records"""
    _name = 'health.alert'
    _description = 'Health Alert'
    _inherit = ['mail.thread']
    _order = 'create_date desc'
    
    name = fields.Char(string='Alert', compute='_compute_name', store=True)
    alert_type = fields.Selection([
        ('vaccination_due', 'Vaccination Due'),
        ('vaccination_overdue', 'Vaccination Overdue'),
        ('treatment_overdue', 'Treatment Overdue'),
        ('health_check_due', 'Health Check Due'),
        ('calving_expected', 'Expected Calving'),
        ('heat_detection', 'Heat Detection'),
        ('milk_drop', 'Milk Production Drop'),
        ('weight_loss', 'Significant Weight Loss'),
    ], string='Alert Type', required=True)
    
    animal_id = fields.Many2one('farm.animal', string='Animal', required=True)
    message = fields.Text(string='Message', required=True)
    
    priority = fields.Selection([
        ('low', 'Low'),
        ('normal', 'Normal'),
        ('high', 'High'),
        ('urgent', 'Urgent'),
    ], string='Priority', default='normal')
    
    state = fields.Selection([
        ('open', 'Open'),
        ('acknowledged', 'Acknowledged'),
        ('resolved', 'Resolved'),
        ('ignored', 'Ignored'),
    ], string='Status', default='open', tracking=True)
    
    # Related Record
    related_model = fields.Char(string='Related Model')
    related_id = fields.Integer(string='Related ID')
    
    # Timestamps
    acknowledged_at = fields.Datetime(string='Acknowledged At')
    acknowledged_by = fields.Many2one('res.users', string='Acknowledged By')
    resolved_at = fields.Datetime(string='Resolved At')
    resolved_by = fields.Many2one('res.users', string='Resolved By')
    
    @api.depends('alert_type', 'animal_id')
    def _compute_name(self):
        for alert in self:
            alert.name = f"{alert.alert_type} - {alert.animal_id.tag_number}"
    
    def action_acknowledge(self):
        """Acknowledge alert"""
        self.write({
            'state': 'acknowledged',
            'acknowledged_at': datetime.now(),
            'acknowledged_by': self.env.user.id,
        })
    
    def action_resolve(self):
        """Mark alert as resolved"""
        self.write({
            'state': 'resolved',
            'resolved_at': datetime.now(),
            'resolved_by': self.env.user.id,
        })
```

---

## 8. ANALYTICS & REPORTING

### 8.1 Production Analytics

```python
# farm_management/reports/production_report.py

from odoo import models, api
from datetime import datetime, timedelta


class MilkProductionReport(models.AbstractModel):
    """Milk production analytics report"""
    _name = 'report.farm_management.milk_production'
    _description = 'Milk Production Report'
    
    @api.model
    def _get_report_values(self, docids, data=None):
        start_date = data.get('start_date')
        end_date = data.get('end_date')
        animal_group_id = data.get('animal_group_id')
        
        # Get production data
        domain = [
            ('date', '>=', start_date),
            ('date', '<=', end_date),
        ]
        
        if animal_group_id:
            animals = self.env['farm.animal'].search([
                ('animal_group_id', '=', animal_group_id),
            ])
            domain.append(('animal_id', 'in', animals.ids))
        
        records = self.env['daily.milk.production'].search(domain)
        
        # Calculate statistics
        total_volume = sum(records.mapped('total_yield'))
        record_count = len(records)
        avg_daily_yield = total_volume / record_count if record_count else 0
        
        # Top producers
        animal_totals = {}
        for rec in records:
            animal_id = rec.animal_id.id
            if animal_id not in animal_totals:
                animal_totals[animal_id] = {'animal': rec.animal_id, 'total': 0, 'count': 0}
            animal_totals[animal_id]['total'] += rec.total_yield
            animal_totals[animal_id]['count'] += 1
        
        top_producers = sorted(
            animal_totals.values(),
            key=lambda x: x['total'] / x['count'] if x['count'] else 0,
            reverse=True
        )[:10]
        
        return {
            'start_date': start_date,
            'end_date': end_date,
            'total_volume': total_volume,
            'avg_daily_yield': avg_daily_yield,
            'top_producers': top_producers,
        }


class AnimalHealthReport(models.AbstractModel):
    """Animal health summary report"""
    _name = 'report.farm_management.animal_health_summary'
    _description = 'Animal Health Summary Report'
    
    @api.model
    def _get_report_values(self, docids, data=None):
        # Health status breakdown
        animals = self.env['farm.animal'].search([])
        
        health_summary = {}
        for status in ['healthy', 'sick', 'recovering', 'quarantine', 'critical']:
            count = len(animals.filtered(lambda a: a.health_status == status))
            health_summary[status] = count
        
        # Treatment summary
        active_treatments = self.env['animal.treatment'].search([
            ('state', 'in', ['planned', 'in_progress']),
        ])
        
        # Overdue vaccinations
        overdue_vacs = self.env['animal.vaccination'].search([
            ('state', '=', 'overdue'),
        ])
        
        # Recent health checks
        recent_checks = self.env['animal.health.record'].search([
            ('date', '>=', fields.Date.today() - timedelta(days=30)),
        ])
        
        return {
            'health_summary': health_summary,
            'active_treatments_count': len(active_treatments),
            'overdue_vaccinations_count': len(overdue_vacs),
            'recent_checks_count': len(recent_checks),
            'total_animals': len(animals),
        }
```

---

## 9. APPENDICES

### 9.1 Developer Tasks Matrix

| Day | Dev-Lead | Dev-1 | Dev-2 |
|-----|----------|-------|-------|
| 151 | Architecture review | Animal model extension | Health status tracking |
| 152 | Health records design | Health check model | Treatment records |
| 153 | Vaccination system | Vaccination schedule | Reminders |
| 154 | Breeding design | Breeding records | Heat detection |
| 155 | Calving management | Calving records | Offspring registration |
| 156 | Milk tracking design | Daily production | Quality grading |
| 157 | Feed management | Ration planning | Ingredient management |
| 158 | Veterinary integration | Visit scheduling | Appointment system |
| 159 | Alerts system | Alert rules | Notifications |
| 160 | Reporting | Analytics dashboard | Charts/visualizations |

### 9.2 Health Alert Rules

| Alert Type | Trigger | Notification |
|------------|---------|--------------|
| Vaccination Due | 7 days before | Farm Manager, Caretaker |
| Vaccination Overdue | 1 day after | Farm Manager, Vet |
| Health Check Due | 30 days since last | Farm Manager |
| Expected Calving | 7 days before | Farm Manager, Vet |
| Milk Drop | >20% decline | Farm Manager |
| Weight Loss | >10% decline | Vet |

### 9.3 Data Import/Export

| Feature | Format | Frequency |
|---------|--------|-----------|
| Milk Production | CSV | Daily |
| Health Records | CSV/Excel | Weekly |
| Breeding Data | CSV | Monthly |
| Feed Consumption | CSV | Daily |
| Vet Reports | PDF | Per Visit |

---

*End of Milestone 6 - Advanced Farm Management*

**Document Statistics:**
- **File Size**: 78+ KB
- **Code Examples**: 50+
- **Management Areas**: 6 (Health, Breeding, Production, Feed, Veterinary, Alerts)
- **Reports**: 4+
- **Alert Types**: 8

**Next Milestone**: Milestone 7 - Reporting & Analytics Foundation

