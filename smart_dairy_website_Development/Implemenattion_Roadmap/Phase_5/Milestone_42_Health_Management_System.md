# Milestone 42: Health Management System

## Smart Dairy Digital Smart Portal + ERP - Phase 5: Farm Management Foundation

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 42 of 150 (2 of 10 in Phase 5)                                         |
| **Title**        | Health Management System                                               |
| **Phase**        | Phase 5 - Farm Management Foundation                                   |
| **Days**         | Days 261-270 (of 350 total)                                            |
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

Implement a comprehensive health management system enabling Smart Dairy to track medical records, manage vaccination schedules, document treatments, monitor disease outbreaks, and maintain quarantine protocols for all farm animals.

### 1.2 Objectives

1. Design and implement FarmHealthRecord model for medical events
2. Create vaccination schedule management with automated reminders
3. Build treatment protocol library with standard protocols
4. Implement disease outbreak tracking and reporting
5. Create quarantine management workflow
6. Build medicine inventory tracking system
7. Implement health alert notification engine
8. Create veterinary integration foundation
9. Build health report generators
10. Develop mobile health entry interface

### 1.3 Key Deliverables

| Deliverable                     | Owner  | Format            | Due Day |
| ------------------------------- | ------ | ----------------- | ------- |
| FarmHealthRecord Model          | Dev 1  | Python module     | 261     |
| FarmVaccination Model           | Dev 1  | Python module     | 262     |
| TreatmentProtocol Model         | Dev 1  | Python module     | 263     |
| DiseaseOutbreak Model           | Dev 1  | Python module     | 264     |
| QuarantineRecord Model          | Dev 1  | Python module     | 265     |
| Medicine Inventory Model        | Dev 1  | Python module     | 266     |
| Health Alert Engine             | Dev 2  | Python service    | 267     |
| Health Record Views             | Dev 3  | OWL components    | 268     |
| Health Reports                  | Dev 1  | Report templates  | 269     |
| Flutter Health Entry UI         | Dev 3  | Dart/Flutter      | 270     |

### 1.4 Prerequisites

- Milestone 41 complete (FarmAnimal model available)
- Farm location hierarchy established
- Animal status system operational
- Notification system configured (from Phase 3)

### 1.5 Success Criteria

- [ ] Health records linked to animals with complete history
- [ ] Vaccination schedules generate automated reminders
- [ ] Treatment protocols searchable and applicable
- [ ] Disease outbreaks tracked with affected animal lists
- [ ] Quarantine workflow enforces movement restrictions
- [ ] Medicine inventory tracks stock with expiry alerts
- [ ] Health alerts delivered via push/SMS within 5 minutes
- [ ] Unit test coverage >80% for all models
- [ ] No critical bugs open

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference        |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------- |
| RFP-FARM-004 | RFP    | Health record management                 | 261     | FarmHealthRecord      |
| RFP-FARM-005 | RFP    | Vaccination scheduling                   | 262     | FarmVaccination       |
| RFP-FARM-006 | RFP    | Disease outbreak tracking                | 264     | DiseaseOutbreak       |
| BRD-FM-002   | BRD    | 25% reduction in disease incidence       | 261-270 | Complete milestone    |
| SRS-FM-004   | SRS    | FarmHealthRecord model                   | 261     | Health record class   |
| SRS-FM-005   | SRS    | Vaccination scheduling cron              | 262     | Reminder scheduler    |

---

## 3. Day-by-Day Breakdown

### Day 261 - Health Record Model & Core Structure

**Objective:** Create the foundational health record model for tracking all medical events and health status changes.

#### Dev 1 - Backend Lead (8h)

**Task 1: Create FarmHealthRecord Model (6h)**

```python
# odoo/addons/smart_dairy_farm/models/farm_health_record.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
from datetime import date, timedelta

class FarmHealthRecord(models.Model):
    _name = 'farm.health.record'
    _description = 'Farm Animal Health Record'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'record_date desc, id desc'
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
        string='Animal',
        required=True,
        tracking=True,
        index=True,
        ondelete='cascade'
    )
    animal_ear_tag = fields.Char(
        related='animal_id.ear_tag',
        store=True
    )
    animal_species = fields.Selection(
        related='animal_id.species',
        store=True
    )
    farm_id = fields.Many2one(
        related='animal_id.farm_id',
        store=True
    )

    # ===== RECORD DETAILS =====
    record_date = fields.Date(
        string='Date',
        required=True,
        default=fields.Date.today,
        tracking=True,
        index=True
    )
    record_time = fields.Float(
        string='Time',
        help='Time of observation/treatment (24h format)'
    )
    record_type = fields.Selection([
        ('observation', 'Health Observation'),
        ('symptom', 'Symptom Report'),
        ('diagnosis', 'Diagnosis'),
        ('treatment', 'Treatment'),
        ('vaccination', 'Vaccination'),
        ('checkup', 'Routine Checkup'),
        ('emergency', 'Emergency'),
        ('surgery', 'Surgery'),
        ('follow_up', 'Follow-up'),
        ('recovery', 'Recovery Note'),
        ('death', 'Death Record'),
    ], string='Record Type', required=True, default='observation', tracking=True)

    # ===== HEALTH STATUS =====
    health_status = fields.Selection([
        ('healthy', 'Healthy'),
        ('minor_illness', 'Minor Illness'),
        ('moderate_illness', 'Moderate Illness'),
        ('severe_illness', 'Severe Illness'),
        ('critical', 'Critical'),
        ('recovering', 'Recovering'),
        ('chronic', 'Chronic Condition'),
        ('deceased', 'Deceased'),
    ], string='Health Status', tracking=True)

    severity = fields.Selection([
        ('low', 'Low'),
        ('medium', 'Medium'),
        ('high', 'High'),
        ('critical', 'Critical'),
    ], string='Severity', tracking=True)

    # ===== SYMPTOMS & DIAGNOSIS =====
    symptom_ids = fields.Many2many(
        'farm.health.symptom',
        'farm_health_record_symptom_rel',
        'record_id',
        'symptom_id',
        string='Symptoms'
    )
    symptoms_description = fields.Text(string='Symptoms Description')

    disease_id = fields.Many2one(
        'farm.disease',
        string='Disease/Condition',
        tracking=True
    )
    diagnosis = fields.Text(string='Diagnosis Notes')
    diagnosis_date = fields.Date(string='Diagnosis Date')
    diagnosed_by = fields.Many2one(
        'res.users',
        string='Diagnosed By',
        default=lambda self: self.env.user
    )

    # ===== VITAL SIGNS =====
    temperature_celsius = fields.Float(
        string='Temperature (°C)',
        digits=(4, 1),
        help='Normal cattle temperature: 38.0-39.5°C'
    )
    heart_rate = fields.Integer(
        string='Heart Rate (bpm)',
        help='Normal cattle heart rate: 40-80 bpm'
    )
    respiratory_rate = fields.Integer(
        string='Respiratory Rate (per min)',
        help='Normal cattle respiratory rate: 10-30 per min'
    )
    weight_kg = fields.Float(
        string='Weight (kg)',
        digits=(6, 2)
    )
    body_condition_score = fields.Selection([
        ('1', '1 - Emaciated'),
        ('2', '2 - Thin'),
        ('3', '3 - Average'),
        ('4', '4 - Fat'),
        ('5', '5 - Obese'),
    ], string='Body Condition Score')

    # ===== TREATMENT =====
    treatment_protocol_id = fields.Many2one(
        'farm.treatment.protocol',
        string='Treatment Protocol'
    )
    treatment_description = fields.Text(string='Treatment Given')
    treatment_start_date = fields.Date(string='Treatment Start')
    treatment_end_date = fields.Date(string='Treatment End')
    treatment_duration_days = fields.Integer(
        compute='_compute_treatment_duration',
        store=True
    )

    # ===== MEDICATIONS =====
    medication_line_ids = fields.One2many(
        'farm.health.medication.line',
        'health_record_id',
        string='Medications'
    )
    total_medication_cost = fields.Monetary(
        compute='_compute_medication_cost',
        currency_field='currency_id'
    )

    # ===== WITHDRAWAL PERIODS =====
    milk_withdrawal_days = fields.Integer(
        string='Milk Withdrawal (days)',
        help='Days milk must be discarded'
    )
    meat_withdrawal_days = fields.Integer(
        string='Meat Withdrawal (days)',
        help='Days before animal can be slaughtered'
    )
    withdrawal_end_date = fields.Date(
        compute='_compute_withdrawal_end',
        store=True,
        string='Withdrawal Ends'
    )
    is_in_withdrawal = fields.Boolean(
        compute='_compute_is_in_withdrawal',
        string='In Withdrawal Period'
    )

    # ===== VETERINARIAN =====
    veterinarian_id = fields.Many2one(
        'res.partner',
        string='Veterinarian',
        domain="[('is_veterinarian', '=', True)]"
    )
    vet_visit_id = fields.Many2one(
        'farm.vet.visit',
        string='Vet Visit'
    )

    # ===== OUTCOMES =====
    outcome = fields.Selection([
        ('cured', 'Cured/Recovered'),
        ('improved', 'Improved'),
        ('stable', 'Stable/Ongoing'),
        ('worsened', 'Worsened'),
        ('chronic', 'Became Chronic'),
        ('deceased', 'Animal Deceased'),
        ('pending', 'Pending'),
    ], string='Outcome', tracking=True)
    outcome_date = fields.Date(string='Outcome Date')
    outcome_notes = fields.Text(string='Outcome Notes')

    # ===== FOLLOW-UP =====
    follow_up_required = fields.Boolean(string='Follow-up Required')
    follow_up_date = fields.Date(string='Follow-up Date')
    follow_up_notes = fields.Text(string='Follow-up Notes')
    parent_record_id = fields.Many2one(
        'farm.health.record',
        string='Related to Record'
    )
    follow_up_record_ids = fields.One2many(
        'farm.health.record',
        'parent_record_id',
        string='Follow-up Records'
    )

    # ===== QUARANTINE =====
    quarantine_required = fields.Boolean(string='Quarantine Required')
    quarantine_record_id = fields.Many2one(
        'farm.quarantine.record',
        string='Quarantine Record'
    )

    # ===== ATTACHMENTS =====
    attachment_ids = fields.Many2many(
        'ir.attachment',
        'farm_health_record_attachment_rel',
        'record_id',
        'attachment_id',
        string='Attachments'
    )
    image_ids = fields.One2many(
        'farm.health.image',
        'health_record_id',
        string='Images'
    )

    # ===== NOTES =====
    notes = fields.Html(string='Additional Notes')
    internal_notes = fields.Text(string='Internal Notes')

    # ===== STATE =====
    state = fields.Selection([
        ('draft', 'Draft'),
        ('confirmed', 'Confirmed'),
        ('in_treatment', 'In Treatment'),
        ('follow_up', 'Follow-up'),
        ('closed', 'Closed'),
        ('cancelled', 'Cancelled'),
    ], string='Status', default='draft', tracking=True)

    # ===== COMPANY & CURRENCY =====
    company_id = fields.Many2one(
        'res.company',
        string='Company',
        default=lambda self: self.env.company
    )
    currency_id = fields.Many2one(
        related='company_id.currency_id'
    )

    # ===== SQL CONSTRAINTS =====
    _sql_constraints = [
        ('record_number_unique',
         'unique(record_number, company_id)',
         'Health record number must be unique!'),
    ]

    # ===== COMPUTED METHODS =====
    @api.depends('animal_id', 'record_date', 'record_type')
    def _compute_display_name(self):
        for record in self:
            type_label = dict(self._fields['record_type'].selection).get(record.record_type, '')
            record.display_name = f"{record.animal_id.ear_tag} - {type_label} ({record.record_date})"

    @api.model
    def _generate_record_number(self):
        """Generate unique health record number"""
        today = date.today().strftime('%Y%m%d')
        sequence = self.env['ir.sequence'].next_by_code('farm.health.record')
        return f"HR-{today}-{sequence or '0001'}"

    @api.depends('treatment_start_date', 'treatment_end_date')
    def _compute_treatment_duration(self):
        for record in self:
            if record.treatment_start_date and record.treatment_end_date:
                delta = record.treatment_end_date - record.treatment_start_date
                record.treatment_duration_days = delta.days + 1
            else:
                record.treatment_duration_days = 0

    @api.depends('medication_line_ids', 'medication_line_ids.total_cost')
    def _compute_medication_cost(self):
        for record in self:
            record.total_medication_cost = sum(
                record.medication_line_ids.mapped('total_cost')
            )

    @api.depends('record_date', 'milk_withdrawal_days', 'meat_withdrawal_days')
    def _compute_withdrawal_end(self):
        for record in self:
            max_withdrawal = max(
                record.milk_withdrawal_days or 0,
                record.meat_withdrawal_days or 0
            )
            if record.record_date and max_withdrawal:
                record.withdrawal_end_date = record.record_date + timedelta(days=max_withdrawal)
            else:
                record.withdrawal_end_date = False

    def _compute_is_in_withdrawal(self):
        today = date.today()
        for record in self:
            record.is_in_withdrawal = (
                record.withdrawal_end_date and
                record.withdrawal_end_date >= today
            )

    # ===== CONSTRAINT METHODS =====
    @api.constrains('temperature_celsius')
    def _check_temperature(self):
        for record in self:
            if record.temperature_celsius:
                if record.temperature_celsius < 30 or record.temperature_celsius > 45:
                    raise ValidationError(
                        'Temperature must be between 30°C and 45°C'
                    )

    @api.constrains('treatment_start_date', 'treatment_end_date')
    def _check_treatment_dates(self):
        for record in self:
            if record.treatment_start_date and record.treatment_end_date:
                if record.treatment_end_date < record.treatment_start_date:
                    raise ValidationError(
                        'Treatment end date cannot be before start date'
                    )

    # ===== ONCHANGE METHODS =====
    @api.onchange('disease_id')
    def _onchange_disease(self):
        """Auto-fill treatment protocol based on disease"""
        if self.disease_id and self.disease_id.default_protocol_id:
            self.treatment_protocol_id = self.disease_id.default_protocol_id

    @api.onchange('treatment_protocol_id')
    def _onchange_protocol(self):
        """Auto-fill medication lines from protocol"""
        if self.treatment_protocol_id:
            protocol = self.treatment_protocol_id
            self.treatment_description = protocol.description
            self.milk_withdrawal_days = protocol.milk_withdrawal_days
            self.meat_withdrawal_days = protocol.meat_withdrawal_days

            # Clear and add medications from protocol
            medication_lines = []
            for med_line in protocol.medication_line_ids:
                medication_lines.append((0, 0, {
                    'medicine_id': med_line.medicine_id.id,
                    'dosage': med_line.dosage,
                    'dosage_unit': med_line.dosage_unit,
                    'frequency': med_line.frequency,
                    'duration_days': med_line.duration_days,
                    'route': med_line.route,
                }))
            self.medication_line_ids = medication_lines

    @api.onchange('severity')
    def _onchange_severity(self):
        """Auto-require quarantine for severe cases"""
        if self.severity in ('high', 'critical'):
            self.quarantine_required = True

    # ===== ACTION METHODS =====
    def action_confirm(self):
        """Confirm the health record"""
        self.write({'state': 'confirmed'})
        # Update animal status if needed
        for record in self:
            if record.severity in ('high', 'critical'):
                record.animal_id.action_mark_sick()
            if record.quarantine_required:
                record.animal_id.action_mark_quarantine()

    def action_start_treatment(self):
        """Start treatment"""
        self.write({
            'state': 'in_treatment',
            'treatment_start_date': date.today()
        })

    def action_schedule_followup(self):
        """Open wizard to schedule follow-up"""
        return {
            'name': 'Schedule Follow-up',
            'type': 'ir.actions.act_window',
            'res_model': 'farm.health.followup.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_parent_record_id': self.id}
        }

    def action_close(self):
        """Close the health record"""
        self.write({
            'state': 'closed',
            'outcome_date': date.today()
        })

    def action_create_quarantine(self):
        """Create quarantine record for this health issue"""
        self.ensure_one()
        return {
            'name': 'Create Quarantine',
            'type': 'ir.actions.act_window',
            'res_model': 'farm.quarantine.record',
            'view_mode': 'form',
            'target': 'new',
            'context': {
                'default_animal_id': self.animal_id.id,
                'default_health_record_id': self.id,
                'default_reason': f"Health issue: {self.disease_id.name or self.symptoms_description}"
            }
        }

    def action_view_animal(self):
        """Open animal form"""
        return {
            'name': 'Animal',
            'type': 'ir.actions.act_window',
            'res_model': 'farm.animal',
            'view_mode': 'form',
            'res_id': self.animal_id.id
        }


class FarmHealthMedicationLine(models.Model):
    _name = 'farm.health.medication.line'
    _description = 'Health Record Medication Line'

    health_record_id = fields.Many2one(
        'farm.health.record',
        string='Health Record',
        required=True,
        ondelete='cascade'
    )
    medicine_id = fields.Many2one(
        'farm.medicine',
        string='Medicine',
        required=True
    )
    dosage = fields.Float(string='Dosage', required=True)
    dosage_unit = fields.Selection([
        ('ml', 'ml'),
        ('mg', 'mg'),
        ('g', 'g'),
        ('tablet', 'Tablet'),
        ('capsule', 'Capsule'),
        ('injection', 'Injection'),
    ], string='Unit', required=True, default='ml')
    frequency = fields.Selection([
        ('once', 'Once'),
        ('daily', 'Daily'),
        ('twice_daily', 'Twice Daily'),
        ('three_daily', '3 Times Daily'),
        ('weekly', 'Weekly'),
        ('as_needed', 'As Needed'),
    ], string='Frequency', required=True, default='daily')
    duration_days = fields.Integer(string='Duration (days)')
    route = fields.Selection([
        ('oral', 'Oral'),
        ('intramuscular', 'Intramuscular (IM)'),
        ('intravenous', 'Intravenous (IV)'),
        ('subcutaneous', 'Subcutaneous (SC)'),
        ('topical', 'Topical'),
        ('intramammary', 'Intramammary'),
        ('intrauterine', 'Intrauterine'),
    ], string='Route', default='intramuscular')
    unit_cost = fields.Monetary(
        related='medicine_id.unit_cost',
        currency_field='currency_id'
    )
    total_cost = fields.Monetary(
        compute='_compute_total_cost',
        currency_field='currency_id'
    )
    administered = fields.Boolean(string='Administered', default=False)
    administered_date = fields.Datetime(string='Administered At')
    administered_by = fields.Many2one('res.users', string='Administered By')
    notes = fields.Text(string='Notes')

    currency_id = fields.Many2one(
        related='health_record_id.currency_id'
    )

    @api.depends('dosage', 'duration_days', 'unit_cost', 'frequency')
    def _compute_total_cost(self):
        frequency_multiplier = {
            'once': 1,
            'daily': 1,
            'twice_daily': 2,
            'three_daily': 3,
            'weekly': 0.14,  # 1/7
            'as_needed': 1,
        }
        for line in self:
            multiplier = frequency_multiplier.get(line.frequency, 1)
            days = line.duration_days or 1
            line.total_cost = line.dosage * line.unit_cost * multiplier * days


class FarmHealthSymptom(models.Model):
    _name = 'farm.health.symptom'
    _description = 'Health Symptom'
    _order = 'name'

    name = fields.Char(string='Symptom Name', required=True, translate=True)
    name_bn = fields.Char(string='Symptom (Bangla)')
    code = fields.Char(string='Code')
    description = fields.Text(string='Description')
    body_system = fields.Selection([
        ('respiratory', 'Respiratory'),
        ('digestive', 'Digestive'),
        ('reproductive', 'Reproductive'),
        ('musculoskeletal', 'Musculoskeletal'),
        ('skin', 'Skin/Integumentary'),
        ('nervous', 'Nervous'),
        ('urinary', 'Urinary'),
        ('udder', 'Udder/Mammary'),
        ('general', 'General/Systemic'),
    ], string='Body System')
    severity_indicator = fields.Selection([
        ('low', 'Usually Low'),
        ('medium', 'Usually Medium'),
        ('high', 'Usually High'),
    ], string='Typical Severity')
    active = fields.Boolean(default=True)


class FarmHealthImage(models.Model):
    _name = 'farm.health.image'
    _description = 'Health Record Image'

    health_record_id = fields.Many2one(
        'farm.health.record',
        string='Health Record',
        required=True,
        ondelete='cascade'
    )
    image = fields.Image(
        string='Image',
        max_width=1920,
        max_height=1080,
        required=True
    )
    image_thumbnail = fields.Image(
        related='image',
        max_width=128,
        max_height=128,
        store=True
    )
    caption = fields.Char(string='Caption')
    body_part = fields.Char(string='Body Part/Area')
    capture_date = fields.Datetime(
        string='Captured At',
        default=fields.Datetime.now
    )
    captured_by = fields.Many2one(
        'res.users',
        string='Captured By',
        default=lambda self: self.env.user
    )
```

**Task 2: Create Disease Master Data Model (2h)**

```python
# odoo/addons/smart_dairy_farm/models/farm_disease.py
from odoo import models, fields, api

class FarmDisease(models.Model):
    _name = 'farm.disease'
    _description = 'Animal Disease/Condition'
    _order = 'name'

    name = fields.Char(string='Disease Name', required=True, translate=True)
    name_bn = fields.Char(string='Disease (Bangla)')
    code = fields.Char(string='Code', required=True)
    description = fields.Html(string='Description')

    # Classification
    disease_type = fields.Selection([
        ('infectious', 'Infectious'),
        ('parasitic', 'Parasitic'),
        ('metabolic', 'Metabolic'),
        ('reproductive', 'Reproductive'),
        ('nutritional', 'Nutritional'),
        ('genetic', 'Genetic'),
        ('environmental', 'Environmental'),
        ('other', 'Other'),
    ], string='Type', required=True)

    species_ids = fields.Many2many(
        'farm.species',
        string='Affected Species'
    )
    zoonotic = fields.Boolean(
        string='Zoonotic',
        help='Can be transmitted to humans'
    )
    notifiable = fields.Boolean(
        string='Notifiable Disease',
        help='Must be reported to authorities'
    )

    # Clinical Information
    incubation_period_days = fields.Integer(string='Incubation Period (days)')
    common_symptoms = fields.Text(string='Common Symptoms')
    symptom_ids = fields.Many2many(
        'farm.health.symptom',
        string='Associated Symptoms'
    )
    diagnostic_methods = fields.Text(string='Diagnostic Methods')

    # Treatment
    default_protocol_id = fields.Many2one(
        'farm.treatment.protocol',
        string='Default Treatment Protocol'
    )
    treatment_notes = fields.Text(string='Treatment Notes')
    typical_recovery_days = fields.Integer(string='Typical Recovery (days)')
    mortality_rate_percent = fields.Float(
        string='Mortality Rate %',
        digits=(5, 2)
    )

    # Prevention
    vaccination_available = fields.Boolean(string='Vaccine Available')
    vaccination_schedule = fields.Text(string='Vaccination Schedule')
    prevention_measures = fields.Text(string='Prevention Measures')

    # Quarantine
    quarantine_required = fields.Boolean(string='Quarantine Required')
    quarantine_days = fields.Integer(string='Quarantine Period (days)')

    active = fields.Boolean(default=True)

    _sql_constraints = [
        ('code_unique', 'unique(code)', 'Disease code must be unique!'),
    ]
```

#### Dev 2 - Full-Stack (8h)

**Task 1: Health Record Sequences and Security (3h)**

```xml
<!-- odoo/addons/smart_dairy_farm/data/health_sequence_data.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <data noupdate="1">
        <record id="seq_farm_health_record" model="ir.sequence">
            <field name="name">Farm Health Record</field>
            <field name="code">farm.health.record</field>
            <field name="prefix">%(year)s</field>
            <field name="padding">6</field>
            <field name="number_next">1</field>
        </record>

        <record id="seq_farm_vaccination" model="ir.sequence">
            <field name="name">Farm Vaccination Record</field>
            <field name="code">farm.vaccination</field>
            <field name="prefix">VAC%(year)s</field>
            <field name="padding">6</field>
        </record>

        <record id="seq_farm_quarantine" model="ir.sequence">
            <field name="name">Farm Quarantine Record</field>
            <field name="code">farm.quarantine.record</field>
            <field name="prefix">QUA%(year)s</field>
            <field name="padding">4</field>
        </record>
    </data>
</odoo>
```

**Task 2: Common Symptoms and Diseases Data (3h)**

```xml
<!-- odoo/addons/smart_dairy_farm/data/health_data.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <data noupdate="1">
        <!-- Common Symptoms -->
        <record id="symptom_fever" model="farm.health.symptom">
            <field name="name">Fever</field>
            <field name="name_bn">জ্বর</field>
            <field name="code">SYM001</field>
            <field name="body_system">general</field>
            <field name="severity_indicator">medium</field>
        </record>

        <record id="symptom_loss_appetite" model="farm.health.symptom">
            <field name="name">Loss of Appetite</field>
            <field name="name_bn">ক্ষুধামন্দা</field>
            <field name="code">SYM002</field>
            <field name="body_system">digestive</field>
            <field name="severity_indicator">medium</field>
        </record>

        <record id="symptom_diarrhea" model="farm.health.symptom">
            <field name="name">Diarrhea</field>
            <field name="name_bn">ডায়রিয়া</field>
            <field name="code">SYM003</field>
            <field name="body_system">digestive</field>
            <field name="severity_indicator">medium</field>
        </record>

        <record id="symptom_coughing" model="farm.health.symptom">
            <field name="name">Coughing</field>
            <field name="name_bn">কাশি</field>
            <field name="code">SYM004</field>
            <field name="body_system">respiratory</field>
            <field name="severity_indicator">medium</field>
        </record>

        <record id="symptom_nasal_discharge" model="farm.health.symptom">
            <field name="name">Nasal Discharge</field>
            <field name="name_bn">নাক দিয়ে পানি পড়া</field>
            <field name="code">SYM005</field>
            <field name="body_system">respiratory</field>
            <field name="severity_indicator">low</field>
        </record>

        <record id="symptom_lameness" model="farm.health.symptom">
            <field name="name">Lameness</field>
            <field name="name_bn">খোঁড়া</field>
            <field name="code">SYM006</field>
            <field name="body_system">musculoskeletal</field>
            <field name="severity_indicator">medium</field>
        </record>

        <record id="symptom_reduced_milk" model="farm.health.symptom">
            <field name="name">Reduced Milk Production</field>
            <field name="name_bn">দুধ কমে যাওয়া</field>
            <field name="code">SYM007</field>
            <field name="body_system">udder</field>
            <field name="severity_indicator">medium</field>
        </record>

        <record id="symptom_udder_swelling" model="farm.health.symptom">
            <field name="name">Udder Swelling</field>
            <field name="name_bn">ওলান ফোলা</field>
            <field name="code">SYM008</field>
            <field name="body_system">udder</field>
            <field name="severity_indicator">high</field>
        </record>

        <record id="symptom_abortion" model="farm.health.symptom">
            <field name="name">Abortion</field>
            <field name="name_bn">গর্ভপাত</field>
            <field name="code">SYM009</field>
            <field name="body_system">reproductive</field>
            <field name="severity_indicator">high</field>
        </record>

        <record id="symptom_bloating" model="farm.health.symptom">
            <field name="name">Bloating</field>
            <field name="name_bn">পেট ফোলা</field>
            <field name="code">SYM010</field>
            <field name="body_system">digestive</field>
            <field name="severity_indicator">high</field>
        </record>

        <!-- Common Diseases -->
        <record id="disease_mastitis" model="farm.disease">
            <field name="name">Mastitis</field>
            <field name="name_bn">ওলান প্রদাহ</field>
            <field name="code">DIS001</field>
            <field name="disease_type">infectious</field>
            <field name="zoonotic" eval="False"/>
            <field name="notifiable" eval="False"/>
            <field name="common_symptoms">Swollen udder, hot udder, abnormal milk, reduced milk production, pain during milking</field>
            <field name="quarantine_required" eval="False"/>
            <field name="vaccination_available" eval="False"/>
            <field name="typical_recovery_days">7</field>
        </record>

        <record id="disease_fmd" model="farm.disease">
            <field name="name">Foot and Mouth Disease (FMD)</field>
            <field name="name_bn">ক্ষুরারোগ</field>
            <field name="code">DIS002</field>
            <field name="disease_type">infectious</field>
            <field name="zoonotic" eval="False"/>
            <field name="notifiable" eval="True"/>
            <field name="incubation_period_days">5</field>
            <field name="common_symptoms">Fever, blisters in mouth and feet, drooling, lameness, loss of appetite</field>
            <field name="quarantine_required" eval="True"/>
            <field name="quarantine_days">21</field>
            <field name="vaccination_available" eval="True"/>
            <field name="vaccination_schedule">Every 6 months</field>
            <field name="typical_recovery_days">14</field>
            <field name="mortality_rate_percent">2.0</field>
        </record>

        <record id="disease_hs" model="farm.disease">
            <field name="name">Hemorrhagic Septicemia (HS)</field>
            <field name="name_bn">গলাফোলা রোগ</field>
            <field name="code">DIS003</field>
            <field name="disease_type">infectious</field>
            <field name="zoonotic" eval="False"/>
            <field name="notifiable" eval="True"/>
            <field name="incubation_period_days">3</field>
            <field name="common_symptoms">High fever, swelling in throat/neck, difficulty breathing, sudden death</field>
            <field name="quarantine_required" eval="True"/>
            <field name="quarantine_days">14</field>
            <field name="vaccination_available" eval="True"/>
            <field name="vaccination_schedule">Annually before monsoon</field>
            <field name="mortality_rate_percent">80.0</field>
        </record>

        <record id="disease_anthrax" model="farm.disease">
            <field name="name">Anthrax</field>
            <field name="name_bn">তড়কা</field>
            <field name="code">DIS004</field>
            <field name="disease_type">infectious</field>
            <field name="zoonotic" eval="True"/>
            <field name="notifiable" eval="True"/>
            <field name="common_symptoms">Sudden death, blood from orifices, high fever, swelling</field>
            <field name="quarantine_required" eval="True"/>
            <field name="quarantine_days">30</field>
            <field name="vaccination_available" eval="True"/>
            <field name="vaccination_schedule">Annually</field>
            <field name="mortality_rate_percent">95.0</field>
        </record>

        <record id="disease_brucellosis" model="farm.disease">
            <field name="name">Brucellosis</field>
            <field name="name_bn">ব্রুসেলোসিস</field>
            <field name="code">DIS005</field>
            <field name="disease_type">infectious</field>
            <field name="zoonotic" eval="True"/>
            <field name="notifiable" eval="True"/>
            <field name="common_symptoms">Abortion, retained placenta, infertility, orchitis in bulls</field>
            <field name="quarantine_required" eval="True"/>
            <field name="quarantine_days">60</field>
            <field name="vaccination_available" eval="True"/>
        </record>

        <record id="disease_milk_fever" model="farm.disease">
            <field name="name">Milk Fever (Hypocalcemia)</field>
            <field name="name_bn">দুধ জ্বর</field>
            <field name="code">DIS006</field>
            <field name="disease_type">metabolic</field>
            <field name="zoonotic" eval="False"/>
            <field name="common_symptoms">Weakness, staggering, lying down, cold ears, muscle tremors</field>
            <field name="quarantine_required" eval="False"/>
            <field name="typical_recovery_days">1</field>
        </record>

        <record id="disease_ketosis" model="farm.disease">
            <field name="name">Ketosis</field>
            <field name="name_bn">কিটোসিস</field>
            <field name="code">DIS007</field>
            <field name="disease_type">metabolic</field>
            <field name="common_symptoms">Loss of appetite, rapid weight loss, decreased milk, sweet breath odor</field>
            <field name="quarantine_required" eval="False"/>
            <field name="typical_recovery_days">7</field>
        </record>

        <record id="disease_bloat" model="farm.disease">
            <field name="name">Bloat (Ruminal Tympany)</field>
            <field name="name_bn">পেট ফোলা</field>
            <field name="code">DIS008</field>
            <field name="disease_type">nutritional</field>
            <field name="common_symptoms">Distended left abdomen, difficulty breathing, discomfort, sudden death</field>
            <field name="quarantine_required" eval="False"/>
            <field name="mortality_rate_percent">10.0</field>
        </record>
    </data>
</odoo>
```

**Task 3: Health Record Tests (2h)**

```python
# odoo/addons/smart_dairy_farm/tests/test_farm_health.py
from odoo.tests import TransactionCase, tagged
from odoo.exceptions import ValidationError
from datetime import date, timedelta

@tagged('post_install', '-at_install', 'farm')
class TestFarmHealth(TransactionCase):

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.FarmHealthRecord = cls.env['farm.health.record']
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
        cls.animal = cls.FarmAnimal.create({
            'name': 'Test Cow',
            'ear_tag': 'BD-2024-000001',
            'species': 'cattle',
            'breed_id': cls.breed.id,
            'gender': 'female',
            'date_of_birth': date.today() - timedelta(days=365*3),
            'farm_id': cls.farm.id,
        })

    def test_health_record_creation(self):
        """Test basic health record creation"""
        record = self.FarmHealthRecord.create({
            'animal_id': self.animal.id,
            'record_type': 'observation',
            'health_status': 'healthy',
        })
        self.assertTrue(record.id)
        self.assertEqual(record.animal_ear_tag, 'BD-2024-000001')

    def test_temperature_validation(self):
        """Test temperature range validation"""
        with self.assertRaises(ValidationError):
            self.FarmHealthRecord.create({
                'animal_id': self.animal.id,
                'record_type': 'checkup',
                'temperature_celsius': 50.0,  # Too high
            })

    def test_withdrawal_calculation(self):
        """Test withdrawal period end date calculation"""
        record = self.FarmHealthRecord.create({
            'animal_id': self.animal.id,
            'record_type': 'treatment',
            'milk_withdrawal_days': 5,
            'meat_withdrawal_days': 14,
        })
        expected_end = date.today() + timedelta(days=14)
        self.assertEqual(record.withdrawal_end_date, expected_end)

    def test_treatment_duration(self):
        """Test treatment duration calculation"""
        record = self.FarmHealthRecord.create({
            'animal_id': self.animal.id,
            'record_type': 'treatment',
            'treatment_start_date': date.today(),
            'treatment_end_date': date.today() + timedelta(days=6),
        })
        self.assertEqual(record.treatment_duration_days, 7)
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Health Record Views (4h)**

```xml
<!-- odoo/addons/smart_dairy_farm/views/farm_health_views.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Health Record Tree View -->
    <record id="view_farm_health_record_tree" model="ir.ui.view">
        <field name="name">farm.health.record.tree</field>
        <field name="model">farm.health.record</field>
        <field name="arch" type="xml">
            <tree decoration-danger="severity in ('high', 'critical')"
                  decoration-warning="severity == 'medium'"
                  decoration-muted="state == 'closed'">
                <field name="record_date"/>
                <field name="animal_id"/>
                <field name="animal_ear_tag"/>
                <field name="record_type" widget="badge"/>
                <field name="disease_id"/>
                <field name="health_status"/>
                <field name="severity" widget="badge"
                       decoration-danger="severity == 'critical'"
                       decoration-warning="severity == 'high'"
                       decoration-info="severity == 'medium'"/>
                <field name="veterinarian_id"/>
                <field name="state" widget="badge"/>
                <field name="is_in_withdrawal" invisible="1"/>
            </tree>
        </field>
    </record>

    <!-- Health Record Form View -->
    <record id="view_farm_health_record_form" model="ir.ui.view">
        <field name="name">farm.health.record.form</field>
        <field name="model">farm.health.record</field>
        <field name="arch" type="xml">
            <form string="Health Record">
                <header>
                    <button name="action_confirm" string="Confirm"
                            type="object" class="btn-primary"
                            invisible="state != 'draft'"/>
                    <button name="action_start_treatment" string="Start Treatment"
                            type="object" class="btn-primary"
                            invisible="state != 'confirmed'"/>
                    <button name="action_schedule_followup" string="Schedule Follow-up"
                            type="object"
                            invisible="state not in ('confirmed', 'in_treatment')"/>
                    <button name="action_create_quarantine" string="Create Quarantine"
                            type="object" class="btn-warning"
                            invisible="quarantine_required == False or quarantine_record_id"/>
                    <button name="action_close" string="Close Record"
                            type="object"
                            invisible="state not in ('in_treatment', 'follow_up')"/>
                    <field name="state" widget="statusbar"
                           statusbar_visible="draft,confirmed,in_treatment,closed"/>
                </header>
                <sheet>
                    <div class="oe_button_box" name="button_box">
                        <button name="action_view_animal" type="object"
                                class="oe_stat_button" icon="fa-paw">
                            <span>View Animal</span>
                        </button>
                    </div>
                    <widget name="web_ribbon" title="In Withdrawal"
                            bg_color="bg-warning"
                            invisible="not is_in_withdrawal"/>
                    <div class="oe_title">
                        <label for="record_number"/>
                        <h1>
                            <field name="record_number"/>
                        </h1>
                    </div>
                    <group>
                        <group string="Animal &amp; Record">
                            <field name="animal_id" options="{'no_create': True}"/>
                            <field name="animal_ear_tag" readonly="1"/>
                            <field name="farm_id" readonly="1"/>
                            <field name="record_date"/>
                            <field name="record_type"/>
                        </group>
                        <group string="Health Status">
                            <field name="health_status"/>
                            <field name="severity"/>
                            <field name="disease_id"/>
                            <field name="veterinarian_id"/>
                        </group>
                    </group>
                    <notebook>
                        <page string="Symptoms &amp; Diagnosis" name="symptoms">
                            <group>
                                <group string="Symptoms">
                                    <field name="symptom_ids" widget="many2many_tags"/>
                                    <field name="symptoms_description"/>
                                </group>
                                <group string="Diagnosis">
                                    <field name="diagnosis"/>
                                    <field name="diagnosis_date"/>
                                    <field name="diagnosed_by"/>
                                </group>
                            </group>
                        </page>
                        <page string="Vital Signs" name="vitals">
                            <group>
                                <group>
                                    <field name="temperature_celsius"/>
                                    <field name="heart_rate"/>
                                    <field name="respiratory_rate"/>
                                </group>
                                <group>
                                    <field name="weight_kg"/>
                                    <field name="body_condition_score"/>
                                </group>
                            </group>
                        </page>
                        <page string="Treatment" name="treatment">
                            <group>
                                <group>
                                    <field name="treatment_protocol_id"/>
                                    <field name="treatment_start_date"/>
                                    <field name="treatment_end_date"/>
                                    <field name="treatment_duration_days"/>
                                </group>
                                <group>
                                    <field name="milk_withdrawal_days"/>
                                    <field name="meat_withdrawal_days"/>
                                    <field name="withdrawal_end_date"/>
                                    <field name="is_in_withdrawal"/>
                                </group>
                            </group>
                            <field name="treatment_description"/>
                            <separator string="Medications"/>
                            <field name="medication_line_ids">
                                <tree editable="bottom">
                                    <field name="medicine_id"/>
                                    <field name="dosage"/>
                                    <field name="dosage_unit"/>
                                    <field name="frequency"/>
                                    <field name="duration_days"/>
                                    <field name="route"/>
                                    <field name="total_cost" sum="Total"/>
                                    <field name="administered"/>
                                </tree>
                            </field>
                            <group>
                                <field name="total_medication_cost"/>
                            </group>
                        </page>
                        <page string="Outcome &amp; Follow-up" name="outcome">
                            <group>
                                <group string="Outcome">
                                    <field name="outcome"/>
                                    <field name="outcome_date"/>
                                    <field name="outcome_notes"/>
                                </group>
                                <group string="Follow-up">
                                    <field name="follow_up_required"/>
                                    <field name="follow_up_date"
                                           invisible="not follow_up_required"/>
                                    <field name="follow_up_notes"
                                           invisible="not follow_up_required"/>
                                    <field name="parent_record_id"/>
                                </group>
                            </group>
                            <field name="follow_up_record_ids" mode="tree"
                                   invisible="not follow_up_record_ids">
                                <tree>
                                    <field name="record_date"/>
                                    <field name="record_type"/>
                                    <field name="health_status"/>
                                    <field name="state"/>
                                </tree>
                            </field>
                        </page>
                        <page string="Quarantine" name="quarantine"
                              invisible="not quarantine_required">
                            <group>
                                <field name="quarantine_required"/>
                                <field name="quarantine_record_id"/>
                            </group>
                        </page>
                        <page string="Images" name="images">
                            <field name="image_ids">
                                <kanban>
                                    <field name="image_thumbnail"/>
                                    <field name="caption"/>
                                    <field name="capture_date"/>
                                    <templates>
                                        <t t-name="kanban-box">
                                            <div class="oe_kanban_card">
                                                <div class="o_kanban_image">
                                                    <img t-att-src="kanban_image('farm.health.image', 'image_thumbnail', record.id.raw_value)"/>
                                                </div>
                                                <div class="oe_kanban_details">
                                                    <field name="caption"/>
                                                    <field name="capture_date"/>
                                                </div>
                                            </div>
                                        </t>
                                    </templates>
                                </kanban>
                            </field>
                        </page>
                        <page string="Notes" name="notes">
                            <field name="notes"/>
                            <field name="internal_notes" groups="smart_dairy_farm.group_farm_manager"/>
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

    <!-- Health Record Search View -->
    <record id="view_farm_health_record_search" model="ir.ui.view">
        <field name="name">farm.health.record.search</field>
        <field name="model">farm.health.record</field>
        <field name="arch" type="xml">
            <search string="Search Health Records">
                <field name="animal_id"/>
                <field name="animal_ear_tag"/>
                <field name="record_number"/>
                <field name="disease_id"/>
                <field name="veterinarian_id"/>
                <separator/>
                <filter name="filter_today" string="Today"
                        domain="[('record_date', '=', context_today().strftime('%Y-%m-%d'))]"/>
                <filter name="filter_this_week" string="This Week"
                        domain="[('record_date', '>=', (context_today() - relativedelta(days=7)).strftime('%Y-%m-%d'))]"/>
                <separator/>
                <filter name="filter_critical" string="Critical"
                        domain="[('severity', '=', 'critical')]"/>
                <filter name="filter_high" string="High Severity"
                        domain="[('severity', '=', 'high')]"/>
                <filter name="filter_in_treatment" string="In Treatment"
                        domain="[('state', '=', 'in_treatment')]"/>
                <filter name="filter_withdrawal" string="In Withdrawal"
                        domain="[('is_in_withdrawal', '=', True)]"/>
                <separator/>
                <group expand="0" string="Group By">
                    <filter name="groupby_animal" string="Animal"
                            context="{'group_by': 'animal_id'}"/>
                    <filter name="groupby_farm" string="Farm"
                            context="{'group_by': 'farm_id'}"/>
                    <filter name="groupby_type" string="Record Type"
                            context="{'group_by': 'record_type'}"/>
                    <filter name="groupby_disease" string="Disease"
                            context="{'group_by': 'disease_id'}"/>
                    <filter name="groupby_severity" string="Severity"
                            context="{'group_by': 'severity'}"/>
                    <filter name="groupby_date" string="Date"
                            context="{'group_by': 'record_date:month'}"/>
                </group>
            </search>
        </field>
    </record>

    <!-- Health Record Action -->
    <record id="action_farm_health_record" model="ir.actions.act_window">
        <field name="name">Health Records</field>
        <field name="res_model">farm.health.record</field>
        <field name="view_mode">tree,form,calendar,pivot</field>
        <field name="search_view_id" ref="view_farm_health_record_search"/>
        <field name="context">{'search_default_filter_this_week': 1}</field>
        <field name="help" type="html">
            <p class="o_view_nocontent_smiling_face">
                Create your first health record
            </p>
            <p>
                Track animal health, treatments, and medical history.
            </p>
        </field>
    </record>
</odoo>
```

**Task 2: Flutter Health Entry Screen (4h)**

```dart
// lib/features/health/presentation/pages/health_record_form_screen.dart
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:smart_dairy_farm/core/theme/app_theme.dart';
import 'package:smart_dairy_farm/features/health/domain/entities/health_record.dart';
import 'package:smart_dairy_farm/features/health/presentation/bloc/health_bloc.dart';
import 'package:smart_dairy_farm/features/health/presentation/widgets/symptom_selector.dart';
import 'package:smart_dairy_farm/features/health/presentation/widgets/vital_signs_form.dart';

class HealthRecordFormScreen extends StatefulWidget {
  final int animalId;
  final String animalEarTag;
  final HealthRecord? existingRecord;

  const HealthRecordFormScreen({
    super.key,
    required this.animalId,
    required this.animalEarTag,
    this.existingRecord,
  });

  @override
  State<HealthRecordFormScreen> createState() => _HealthRecordFormScreenState();
}

class _HealthRecordFormScreenState extends State<HealthRecordFormScreen> {
  final _formKey = GlobalKey<FormState>();
  late String _recordType;
  String? _healthStatus;
  String? _severity;
  int? _diseaseId;
  List<int> _selectedSymptomIds = [];
  String _symptomsDescription = '';
  double? _temperature;
  int? _heartRate;
  int? _respiratoryRate;
  double? _weight;
  String? _bodyConditionScore;
  String _treatmentDescription = '';
  String _notes = '';
  bool _quarantineRequired = false;

  @override
  void initState() {
    super.initState();
    _recordType = widget.existingRecord?.recordType ?? 'observation';
    _healthStatus = widget.existingRecord?.healthStatus;
    _severity = widget.existingRecord?.severity;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(
          widget.existingRecord != null
              ? 'Edit Health Record'
              : 'New Health Record',
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.save),
            onPressed: _saveRecord,
          ),
        ],
      ),
      body: BlocListener<HealthBloc, HealthState>(
        listener: (context, state) {
          if (state is HealthRecordSaved) {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text('Health record saved')),
            );
            Navigator.pop(context, state.record);
          } else if (state is HealthError) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text('Error: ${state.message}')),
            );
          }
        },
        child: Form(
          key: _formKey,
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Animal Info Card
                _buildAnimalInfoCard(),
                const SizedBox(height: 16),

                // Record Type Selection
                _buildRecordTypeSelector(),
                const SizedBox(height: 16),

                // Health Status & Severity
                _buildHealthStatusSection(),
                const SizedBox(height: 16),

                // Symptoms Section
                _buildSymptomsSection(),
                const SizedBox(height: 16),

                // Vital Signs (expandable)
                _buildVitalSignsSection(),
                const SizedBox(height: 16),

                // Treatment (if applicable)
                if (_recordType == 'treatment') ...[
                  _buildTreatmentSection(),
                  const SizedBox(height: 16),
                ],

                // Quarantine Option
                _buildQuarantineOption(),
                const SizedBox(height: 16),

                // Notes
                _buildNotesSection(),
                const SizedBox(height: 24),

                // Save Button
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton.icon(
                    onPressed: _saveRecord,
                    icon: const Icon(Icons.save),
                    label: const Text('Save Health Record'),
                    style: ElevatedButton.styleFrom(
                      padding: const EdgeInsets.symmetric(vertical: 16),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildAnimalInfoCard() {
    return Card(
      child: ListTile(
        leading: CircleAvatar(
          backgroundColor: AppTheme.primaryGreen.withOpacity(0.1),
          child: const Icon(Icons.pets, color: AppTheme.primaryGreen),
        ),
        title: Text(widget.animalEarTag),
        subtitle: Text('Animal ID: ${widget.animalId}'),
        trailing: const Icon(Icons.verified, color: AppTheme.statusActive),
      ),
    );
  }

  Widget _buildRecordTypeSelector() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Record Type',
          style: Theme.of(context).textTheme.titleMedium,
        ),
        const SizedBox(height: 8),
        Wrap(
          spacing: 8,
          runSpacing: 8,
          children: [
            _buildTypeChip('observation', 'Observation', Icons.visibility),
            _buildTypeChip('symptom', 'Symptom', Icons.warning),
            _buildTypeChip('diagnosis', 'Diagnosis', Icons.medical_services),
            _buildTypeChip('treatment', 'Treatment', Icons.medication),
            _buildTypeChip('checkup', 'Checkup', Icons.health_and_safety),
            _buildTypeChip('emergency', 'Emergency', Icons.emergency),
          ],
        ),
      ],
    );
  }

  Widget _buildTypeChip(String value, String label, IconData icon) {
    final isSelected = _recordType == value;
    return FilterChip(
      selected: isSelected,
      label: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 16),
          const SizedBox(width: 4),
          Text(label),
        ],
      ),
      onSelected: (selected) {
        if (selected) {
          setState(() => _recordType = value);
        }
      },
      selectedColor: AppTheme.primaryGreen.withOpacity(0.2),
      checkmarkColor: AppTheme.primaryGreen,
    );
  }

  Widget _buildHealthStatusSection() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Health Assessment',
              style: Theme.of(context).textTheme.titleMedium,
            ),
            const SizedBox(height: 12),
            DropdownButtonFormField<String>(
              value: _healthStatus,
              decoration: const InputDecoration(
                labelText: 'Health Status',
                prefixIcon: Icon(Icons.favorite),
              ),
              items: const [
                DropdownMenuItem(value: 'healthy', child: Text('Healthy')),
                DropdownMenuItem(value: 'minor_illness', child: Text('Minor Illness')),
                DropdownMenuItem(value: 'moderate_illness', child: Text('Moderate Illness')),
                DropdownMenuItem(value: 'severe_illness', child: Text('Severe Illness')),
                DropdownMenuItem(value: 'critical', child: Text('Critical')),
                DropdownMenuItem(value: 'recovering', child: Text('Recovering')),
              ],
              onChanged: (value) => setState(() => _healthStatus = value),
            ),
            const SizedBox(height: 12),
            DropdownButtonFormField<String>(
              value: _severity,
              decoration: const InputDecoration(
                labelText: 'Severity',
                prefixIcon: Icon(Icons.priority_high),
              ),
              items: const [
                DropdownMenuItem(value: 'low', child: Text('Low')),
                DropdownMenuItem(value: 'medium', child: Text('Medium')),
                DropdownMenuItem(value: 'high', child: Text('High')),
                DropdownMenuItem(value: 'critical', child: Text('Critical')),
              ],
              onChanged: (value) {
                setState(() {
                  _severity = value;
                  if (value == 'high' || value == 'critical') {
                    _quarantineRequired = true;
                  }
                });
              },
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSymptomsSection() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Symptoms',
              style: Theme.of(context).textTheme.titleMedium,
            ),
            const SizedBox(height: 12),
            SymptomSelector(
              selectedSymptomIds: _selectedSymptomIds,
              onSymptomsChanged: (ids) {
                setState(() => _selectedSymptomIds = ids);
              },
            ),
            const SizedBox(height: 12),
            TextFormField(
              initialValue: _symptomsDescription,
              decoration: const InputDecoration(
                labelText: 'Symptoms Description',
                hintText: 'Describe observed symptoms...',
                prefixIcon: Icon(Icons.description),
              ),
              maxLines: 3,
              onChanged: (value) => _symptomsDescription = value,
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildVitalSignsSection() {
    return ExpansionTile(
      title: const Text('Vital Signs'),
      leading: const Icon(Icons.monitor_heart),
      children: [
        Padding(
          padding: const EdgeInsets.all(16),
          child: VitalSignsForm(
            temperature: _temperature,
            heartRate: _heartRate,
            respiratoryRate: _respiratoryRate,
            weight: _weight,
            bodyConditionScore: _bodyConditionScore,
            onTemperatureChanged: (v) => _temperature = v,
            onHeartRateChanged: (v) => _heartRate = v,
            onRespiratoryRateChanged: (v) => _respiratoryRate = v,
            onWeightChanged: (v) => _weight = v,
            onBodyConditionChanged: (v) => _bodyConditionScore = v,
          ),
        ),
      ],
    );
  }

  Widget _buildTreatmentSection() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Treatment',
              style: Theme.of(context).textTheme.titleMedium,
            ),
            const SizedBox(height: 12),
            TextFormField(
              initialValue: _treatmentDescription,
              decoration: const InputDecoration(
                labelText: 'Treatment Given',
                hintText: 'Describe the treatment...',
                prefixIcon: Icon(Icons.medication),
              ),
              maxLines: 3,
              onChanged: (value) => _treatmentDescription = value,
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildQuarantineOption() {
    return SwitchListTile(
      title: const Text('Quarantine Required'),
      subtitle: const Text('Isolate animal from the herd'),
      value: _quarantineRequired,
      onChanged: (value) => setState(() => _quarantineRequired = value),
      secondary: Icon(
        Icons.warning_amber,
        color: _quarantineRequired ? Colors.orange : Colors.grey,
      ),
    );
  }

  Widget _buildNotesSection() {
    return TextFormField(
      initialValue: _notes,
      decoration: const InputDecoration(
        labelText: 'Additional Notes',
        hintText: 'Any additional observations...',
        prefixIcon: Icon(Icons.notes),
        border: OutlineInputBorder(),
      ),
      maxLines: 4,
      onChanged: (value) => _notes = value,
    );
  }

  void _saveRecord() {
    if (_formKey.currentState!.validate()) {
      // Create health record data
      final recordData = {
        'animal_id': widget.animalId,
        'record_type': _recordType,
        'health_status': _healthStatus,
        'severity': _severity,
        'disease_id': _diseaseId,
        'symptom_ids': _selectedSymptomIds,
        'symptoms_description': _symptomsDescription,
        'temperature_celsius': _temperature,
        'heart_rate': _heartRate,
        'respiratory_rate': _respiratoryRate,
        'weight_kg': _weight,
        'body_condition_score': _bodyConditionScore,
        'treatment_description': _treatmentDescription,
        'notes': _notes,
        'quarantine_required': _quarantineRequired,
      };

      context.read<HealthBloc>().add(SaveHealthRecord(recordData));
    }
  }
}
```

**Day 261 Deliverables:**
- [ ] FarmHealthRecord model with 30+ fields
- [ ] FarmHealthMedicationLine model
- [ ] FarmHealthSymptom model
- [ ] FarmHealthImage model
- [ ] FarmDisease model
- [ ] Health sequences configured
- [ ] Common symptoms data (10+)
- [ ] Common diseases data (8+)
- [ ] Health record unit tests
- [ ] Health record tree/form/search views
- [ ] Flutter health entry screen
- [ ] Daily standup completed
- [ ] Code reviewed and merged

---

### Day 262-270 - Remaining Health Management Implementation

*[Days 262-270 continue with similar detailed breakdowns for:]*

**Day 262 - Vaccination Schedule Management**
- FarmVaccination model
- VaccinationSchedule templates
- Automated reminder cron jobs
- Vaccination calendar UI

**Day 263 - Treatment Protocols**
- TreatmentProtocol model
- Protocol medication templates
- Withdrawal period calculations
- Protocol selection UI

**Day 264 - Disease Outbreak Tracking**
- DiseaseOutbreak model
- Affected animals tracking
- Outbreak alert notifications
- Outbreak dashboard

**Day 265 - Quarantine Management**
- QuarantineRecord model
- Quarantine zone management
- Movement restriction enforcement
- Quarantine status UI

**Day 266 - Medicine Inventory**
- FarmMedicine model
- Stock level tracking
- Expiry alerts
- Medicine dispensing

**Day 267 - Health Alert Engine**
- Alert trigger configuration
- Push notification integration
- SMS alert delivery
- Alert priority system

**Day 268 - Veterinary Integration Prep**
- Vet portal foundation
- Visit scheduling
- Treatment protocol sharing
- Vet collaboration UI

**Day 269 - Health Reports**
- Health summary reports
- Vaccination status reports
- Disease incidence reports
- PDF/Excel export

**Day 270 - Testing & Polish**
- Integration testing
- Performance optimization
- Bug fixes
- Documentation

---

## 4. Technical Specifications

### 4.1 Database Schema

```sql
-- farm_health_record: Medical events and treatments
CREATE TABLE farm_health_record (
    id SERIAL PRIMARY KEY,
    record_number VARCHAR(30) UNIQUE NOT NULL,
    animal_id INTEGER NOT NULL REFERENCES farm_animal(id),
    record_date DATE NOT NULL,
    record_type VARCHAR(20) NOT NULL,
    health_status VARCHAR(20),
    severity VARCHAR(20),
    disease_id INTEGER REFERENCES farm_disease(id),
    symptoms_description TEXT,
    diagnosis TEXT,
    temperature_celsius DECIMAL(4,1),
    treatment_description TEXT,
    treatment_start_date DATE,
    treatment_end_date DATE,
    milk_withdrawal_days INTEGER,
    meat_withdrawal_days INTEGER,
    veterinarian_id INTEGER REFERENCES res_partner(id),
    outcome VARCHAR(20),
    state VARCHAR(20) DEFAULT 'draft',
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    create_date TIMESTAMP DEFAULT NOW()
);

-- farm_vaccination: Vaccination records
CREATE TABLE farm_vaccination (
    id SERIAL PRIMARY KEY,
    animal_id INTEGER NOT NULL REFERENCES farm_animal(id),
    vaccine_id INTEGER NOT NULL REFERENCES farm_vaccine(id),
    vaccination_date DATE NOT NULL,
    batch_number VARCHAR(50),
    dose_ml DECIMAL(5,2),
    administered_by INTEGER REFERENCES res_users(id),
    next_due_date DATE,
    notes TEXT
);

-- farm_quarantine_record: Isolation records
CREATE TABLE farm_quarantine_record (
    id SERIAL PRIMARY KEY,
    animal_id INTEGER NOT NULL REFERENCES farm_animal(id),
    start_date DATE NOT NULL,
    end_date DATE,
    reason TEXT,
    quarantine_zone_id INTEGER REFERENCES farm_pen(id),
    health_record_id INTEGER REFERENCES farm_health_record(id),
    state VARCHAR(20) DEFAULT 'active'
);

-- Indexes
CREATE INDEX idx_health_record_animal ON farm_health_record(animal_id);
CREATE INDEX idx_health_record_date ON farm_health_record(record_date);
CREATE INDEX idx_vaccination_animal ON farm_vaccination(animal_id);
CREATE INDEX idx_vaccination_next_due ON farm_vaccination(next_due_date);
```

### 4.2 API Endpoints

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| GET | `/api/v1/health/records` | List health records |
| GET | `/api/v1/health/records/{id}` | Get health record |
| POST | `/api/v1/health/records` | Create health record |
| PUT | `/api/v1/health/records/{id}` | Update health record |
| GET | `/api/v1/animals/{id}/health` | Animal health history |
| GET | `/api/v1/vaccinations/due` | Get due vaccinations |
| POST | `/api/v1/vaccinations` | Record vaccination |
| GET | `/api/v1/quarantine/active` | Active quarantine list |
| POST | `/api/v1/quarantine` | Create quarantine |

---

## 5. Testing & Validation

### 5.1 Unit Tests

| Test Suite | Coverage Target | Key Tests |
| ---------- | --------------- | --------- |
| test_farm_health | >90% | Record creation, validation |
| test_vaccination | >85% | Schedule, reminders |
| test_quarantine | >85% | Workflow, restrictions |
| test_medicine | >80% | Inventory, dispensing |

### 5.2 Acceptance Criteria

- [ ] Health records linked to animals
- [ ] Vaccination reminders generated 7 days before due
- [ ] Treatment protocols auto-fill medications
- [ ] Quarantine enforces movement restrictions
- [ ] Withdrawal periods calculated correctly
- [ ] Health alerts delivered within 5 minutes
- [ ] Mobile health entry works offline

---

## 6. Risk & Mitigation

| Risk | Impact | Probability | Mitigation |
| ---- | ------ | ----------- | ---------- |
| Missing disease data | Medium | Low | Comprehensive initial data load |
| Alert overload | Medium | Medium | Priority-based filtering |
| Withdrawal tracking gaps | High | Medium | Automated calculations |

---

## 7. Dependencies & Handoffs

### 7.1 Prerequisites from Milestone 41

- FarmAnimal model operational
- Farm location hierarchy established
- Animal status system working

### 7.2 Handoffs to Milestone 43

- Health status available for breeding decisions
- Quarantine status for breeding restrictions
- Vaccination history for animal profile

---

**Milestone 42 Total Effort:** 240 person-hours (3 developers × 8 hours × 10 days)

**Document Version:** 1.0
**Last Updated:** 2026-02-03
