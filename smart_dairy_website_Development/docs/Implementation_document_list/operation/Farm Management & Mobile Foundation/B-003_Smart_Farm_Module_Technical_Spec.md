# SMART DAIRY LTD.
## SMART FARM MODULE TECHNICAL SPECIFICATION
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | B-003 |
| **Version** | 1.0 |
| **Date** | February 28, 2026 |
| **Author** | Odoo Lead |
| **Owner** | Odoo Lead |
| **Reviewer** | Solution Architect |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Module Architecture](#2-module-architecture)
3. [Data Models](#3-data-models)
4. [Herd Management](#4-herd-management)
5. [Milk Production Tracking](#5-milk-production-tracking)
6. [Health Management](#6-health-management)
7. [Reproduction Management](#7-reproduction-management)
8. [Feed Management](#8-feed-management)
9. [IoT Integration](#9-iot-integration)
10. [APIs and Web Services](#10-apis-and-web-services)
11. [Reports and Analytics](#11-reports-and-analytics)
12. [Mobile Integration](#12-mobile-integration)
13. [Appendices](#13-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides the comprehensive technical specification for the Smart Farm Management Module, a custom Odoo 19 CE module designed specifically for Smart Dairy's dairy farm operations. It covers all technical aspects of herd management, milk production tracking, health monitoring, reproduction management, and feed optimization.

### 1.2 Scope

The Smart Farm Module encompasses:
- **Herd Management** - Individual animal tracking, lineage, identification
- **Milk Production** - Daily yield recording, quality parameters, trend analysis
- **Health Management** - Medical records, vaccination schedules, treatments
- **Reproduction** - Breeding records, pregnancy tracking, calving management
- **Feed Management** - Ration planning, consumption tracking, cost analysis
- **IoT Integration** - Sensor data ingestion from milk meters, environmental sensors
- **Mobile Access** - Field data entry via Flutter mobile app

### 1.3 Business Context

| Parameter | Current | Target (2 Years) |
|-----------|---------|------------------|
| Total Cattle | 255 | 800 |
| Lactating Cows | 75 | ~250 |
| Daily Production | 900L | 3,000L |
| Farm Workers | 25 | 60+ |

### 1.4 Reference Documents

| Document ID | Document Name | Version |
|-------------|---------------|---------|
| B-001 | Technical Design Document | 1.0 |
| C-001 | Database Design Document | 1.0 |
| E-001 | API Specification Document | 1.0 |
| I-001 | IoT Architecture Design | 1.0 |

---

## 2. MODULE ARCHITECTURE

### 2.1 Module Structure

```
smart_farm_mgmt/
├── __init__.py
├── __manifest__.py
├── models/
│   ├── __init__.py
│   ├── farm_animal.py              # Core animal entity
│   ├── farm_herd.py                # Herd/lot management
│   ├── animal_breed.py             # Breed master data
│   ├── farm_location.py            # Barn/pen/shed locations
│   ├── animal_health.py            # Health records & treatments
│   ├── vaccination_schedule.py     # Vaccination management
│   ├── breeding_record.py          # Reproduction tracking
│   ├── pregnancy_record.py         # Pregnancy monitoring
│   ├── calving_record.py           # Calving events
│   ├── milk_production.py          # Milk yield tracking
│   ├── milk_quality.py             # Quality test results
│   ├── feed_ration.py              # Feed formulation
│   ├── feed_consumption.py         # Daily feed tracking
│   ├── feed_inventory.py           # Feed stock management
│   ├── iot_device.py               # IoT sensor registry
│   ├── sensor_reading.py           # IoT data storage
│   └── farm_task.py                # Daily task management
├── controllers/
│   ├── __init__.py
│   └── main.py                     # API endpoints
├── views/
│   ├── farm_animal_views.xml
│   ├── herd_management_views.xml
│   ├── health_management_views.xml
│   ├── reproduction_views.xml
│   ├── milk_production_views.xml
│   ├── feed_management_views.xml
│   ├── iot_dashboard_views.xml
│   ├── farm_reports_views.xml
│   └── menu_views.xml
├── data/
│   ├── animal_breeds_data.xml
│   ├── feed_types_data.xml
│   ├── vaccination_schedules_data.xml
│   └── farm_locations_data.xml
├── security/
│   ├── ir.model.access.csv
│   └── farm_security_groups.xml
├── static/
│   └── src/
│       ├── css/
│       ├── js/
│       └── xml/
├── reports/
│   ├── farm_animal_report.xml
│   ├── milk_production_report.xml
│   ├── health_report.xml
│   └── reproduction_report.xml
└── tests/
    ├── __init__.py
    └── test_farm_operations.py
```

### 2.2 Dependencies

```python
# __manifest__.py
{
    'name': 'Smart Dairy Farm Management',
    'version': '19.0.1.0.0',
    'category': 'Agriculture/Farm',
    'summary': 'Complete dairy farm management system',
    'description': '''
        Smart Farm Management Module for Smart Dairy Ltd.
        
        Features:
        - Herd management with RFID/ear tag tracking
        - Milk production recording and analysis
        - Health and veterinary management
        - Reproduction and breeding tracking
        - Feed management and optimization
        - IoT sensor integration
        - Mobile app synchronization
    ''',
    'author': 'Smart Dairy IT Team',
    'website': 'https://smartdairybd.com',
    'depends': [
        'base',
        'mail',
        'web',
        'stock',
        'purchase',
        'account',
    ],
    'data': [
        'security/farm_security_groups.xml',
        'security/ir.model.access.csv',
        'data/animal_breeds_data.xml',
        'data/feed_types_data.xml',
        'data/vaccination_schedules_data.xml',
        'views/farm_animal_views.xml',
        'views/herd_management_views.xml',
        'views/health_management_views.xml',
        'views/reproduction_views.xml',
        'views/milk_production_views.xml',
        'views/feed_management_views.xml',
        'views/iot_dashboard_views.xml',
        'views/farm_reports_views.xml',
        'views/menu_views.xml',
        'reports/farm_animal_report.xml',
        'reports/milk_production_report.xml',
        'reports/health_report.xml',
        'reports/reproduction_report.xml',
    ],
    'demo': [],
    'installable': True,
    'application': True,
    'auto_install': False,
    'license': 'LGPL-3',
}
```

### 2.3 Security Groups

```xml
<!-- farm_security_groups.xml -->
<odoo>
    <data>
        <!-- Farm Worker - Basic data entry -->
        <record id="group_farm_worker" model="res.groups">
            <field name="name">Farm Worker</field>
            <field name="category_id" ref="base.module_category_agriculture"/>
            <field name="implied_ids" eval="[(4, ref('base.group_user'))]"/>
            <field name="comment">Can record milk production, feed consumption, and daily tasks.</field>
        </record>

        <!-- Farm Supervisor - Can manage animals and health records -->
        <record id="group_farm_supervisor" model="res.groups">
            <field name="name">Farm Supervisor</field>
            <field name="category_id" ref="base.module_category_agriculture"/>
            <field name="implied_ids" eval="[(4, ref('group_farm_worker'))]"/>
            <field name="comment">Can manage animal records, health records, and breeding.</field>
        </record>

        <!-- Farm Manager - Full farm management access -->
        <record id="group_farm_manager" model="res.groups">
            <field name="name">Farm Manager</field>
            <field name="category_id" ref="base.module_category_agriculture"/>
            <field name="implied_ids" eval="[(4, ref('group_farm_supervisor'))]"/>
            <field name="users" eval="[(4, ref('base.user_root'))]"/>
            <field name="comment">Full access to all farm management features and reports.</field>
        </record>

        <!-- Veterinarian - Health and reproduction focus -->
        <record id="group_veterinarian" model="res.groups">
            <field name="name">Veterinarian</field>
            <field name="category_id" ref="base.module_category_agriculture"/>
            <field name="implied_ids" eval="[(4, ref('base.group_user'))]"/>
            <field name="comment">Can access and manage all health and reproduction records.</field>
        </record>
    </data>
</odoo>
```

---

## 3. DATA MODELS

### 3.1 Core Animal Model

```python
# models/farm_animal.py
from odoo import models, fields, api, _, exceptions
from datetime import datetime, date
from dateutil.relativedelta import relativedelta

class FarmAnimal(models.Model):
    _name = 'farm.animal'
    _description = 'Farm Animal'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'name asc'

    # Identification
    name = fields.Char(string='Animal ID', required=True, index=True, tracking=True)
    rfid_tag = fields.Char(string='RFID Tag', index=True, tracking=True)
    ear_tag = fields.Char(string='Ear Tag Number', index=True)
    electronic_id = fields.Char(string='Electronic ID')
    
    # Basic Information
    species = fields.Selection([
        ('cattle', 'Cattle'),
        ('buffalo', 'Buffalo'),
        ('goat', 'Goat'),
    ], string='Species', required=True, default='cattle', tracking=True)
    
    breed_id = fields.Many2one('animal.breed', string='Breed', required=True, tracking=True)
    gender = fields.Selection([
        ('male', 'Male'),
        ('female', 'Female'),
    ], string='Gender', required=True, tracking=True)
    
    # Dates
    birth_date = fields.Date(string='Birth Date', tracking=True)
    age_months = fields.Integer(string='Age (Months)', compute='_compute_age', store=True)
    age_display = fields.Char(string='Age', compute='_compute_age_display')
    
    purchase_date = fields.Date(string='Purchase Date')
    purchase_price = fields.Float(string='Purchase Price')
    purchase_weight = fields.Float(string='Purchase Weight (kg)')
    
    # Current Status
    status = fields.Selection([
        ('calf', 'Calf'),
        ('heifer', 'Heifer'),
        ('lactating', 'Lactating'),
        ('dry', 'Dry'),
        ('pregnant', 'Pregnant'),
        ('bull', 'Active Bull'),
        ('sold', 'Sold'),
        ('deceased', 'Deceased'),
    ], string='Status', required=True, default='calf', tracking=True)
    
    # Location
    barn_id = fields.Many2one('farm.location', string='Barn/Shed', tracking=True)
    pen_number = fields.Char(string='Pen Number')
    
    # Lactation Information (for females)
    current_lactation = fields.Integer(string='Current Lactation', default=0)
    total_lactations = fields.Integer(string='Total Lactations', default=0)
    days_in_milk = fields.Integer(string='Days in Milk', compute='_compute_days_in_milk')
    
    last_calving_date = fields.Date(string='Last Calving Date')
    last_breeding_date = fields.Date(string='Last Breeding Date')
    expected_calving_date = fields.Date(string='Expected Calving Date', compute='_compute_expected_calving')
    
    # Production Averages
    daily_milk_avg = fields.Float(string='Daily Milk Average (L)', digits=(5, 2))
    lifetime_production = fields.Float(string='Lifetime Production (L)', digits=(10, 2))
    
    # Health Status
    health_status = fields.Selection([
        ('healthy', 'Healthy'),
        ('sick', 'Sick'),
        ('under_treatment', 'Under Treatment'),
        ('quarantine', 'Quarantine'),
    ], string='Health Status', default='healthy', tracking=True)
    
    body_condition_score = fields.Float(string='Body Condition Score (1-5)', digits=(2, 1))
    current_weight = fields.Float(string='Current Weight (kg)', digits=(6, 2))
    
    # Parentage
    mother_id = fields.Many2one('farm.animal', string='Mother', domain="[('gender', '=', 'female')]")
    father_id = fields.Many2one('farm.animal', string='Father', domain="[('gender', '=', 'male')]")
    
    # Offspring
    offspring_ids = fields.One2many('farm.animal', 'mother_id', string='Offspring')
    offspring_count = fields.Integer(string='Offspring Count', compute='_compute_offspring_count')
    
    # Related Records
    milk_production_ids = fields.One2many('milk.production', 'animal_id', string='Milk Records')
    health_record_ids = fields.One2many('animal.health.record', 'animal_id', string='Health Records')
    breeding_record_ids = fields.One2many('breeding.record', 'female_id', string='Breeding Records')
    
    # Images
    image = fields.Binary(string='Photo')
    
    # Metadata
    company_id = fields.Many2one('res.company', string='Company', default=lambda self: self.env.company)
    active = fields.Boolean(string='Active', default=True)
    state = fields.Selection([
        ('active', 'Active'),
        ('inactive', 'Inactive'),
    ], string='State', default='active')
    
    # Smart Dairy Specific
    is_premium_breed = fields.Boolean(string='Premium Breed', compute='_compute_premium_breed')
    genetic_score = fields.Float(string='Genetic Score', digits=(3, 2))
    embryo_transfer_history = fields.Text(string='Embryo Transfer History')

    @api.depends('birth_date')
    def _compute_age(self):
        for animal in self:
            if animal.birth_date:
                delta = relativedelta(date.today(), animal.birth_date)
                animal.age_months = delta.years * 12 + delta.months
            else:
                animal.age_months = 0

    @api.depends('birth_date')
    def _compute_age_display(self):
        for animal in self:
            if animal.birth_date:
                delta = relativedelta(date.today(), animal.birth_date)
                years = delta.years
                months = delta.months
                if years > 0:
                    animal.age_display = f"{years}y {months}m"
                else:
                    animal.age_display = f"{months}m"
            else:
                animal.age_display = "Unknown"

    @api.depends('last_calving_date')
    def _compute_days_in_milk(self):
        for animal in self:
            if animal.last_calving_date and animal.status == 'lactating':
                animal.days_in_milk = (date.today() - animal.last_calving_date).days
            else:
                animal.days_in_milk = 0

    @api.depends('last_breeding_date')
    def _compute_expected_calving(self):
        for animal in self:
            if animal.last_breeding_date and animal.status in ['pregnant', 'lactating']:
                # Average gestation period for cattle: 283 days
                animal.expected_calving_date = animal.last_breeding_date + relativedelta(days=283)
            else:
                animal.expected_calving_date = False

    @api.depends('offspring_ids')
    def _compute_offspring_count(self):
        for animal in self:
            animal.offspring_count = len(animal.offspring_ids)

    @api.depends('breed_id')
    def _compute_premium_breed(self):
        premium_breeds = ['Holstein Friesian', 'Jersey', 'Sahiwal']
        for animal in self:
            animal.is_premium_breed = animal.breed_id.name in premium_breeds if animal.breed_id else False

    # Actions
    def action_view_milk_production(self):
        return {
            'name': _('Milk Production'),
            'type': 'ir.actions.act_window',
            'res_model': 'milk.production',
            'view_mode': 'tree,graph,pivot',
            'domain': [('animal_id', '=', self.id)],
            'context': {'default_animal_id': self.id},
        }

    def action_view_health_records(self):
        return {
            'name': _('Health Records'),
            'type': 'ir.actions.act_window',
            'res_model': 'animal.health.record',
            'view_mode': 'tree,form',
            'domain': [('animal_id', '=', self.id)],
            'context': {'default_animal_id': self.id},
        }

    def action_record_milking(self):
        return {
            'name': _('Record Milking'),
            'type': 'ir.actions.act_window',
            'res_model': 'milk.production',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_animal_id': self.id},
        }

    # Constraints
    _sql_constraints = [
        ('unique_rfid', 'UNIQUE(rfid_tag)', 'RFID Tag must be unique!'),
        ('unique_ear_tag', 'UNIQUE(ear_tag)', 'Ear Tag must be unique!'),
    ]
```

### 3.2 Milk Production Model

```python
# models/milk_production.py
from odoo import models, fields, api, _, exceptions
from datetime import datetime, date

class MilkProduction(models.Model):
    _name = 'milk.production'
    _description = 'Milk Production Record'
    _order = 'production_date desc, session'
    _inherit = ['mail.thread']

    name = fields.Char(string='Reference', compute='_compute_name', store=True)
    
    # Relationships
    animal_id = fields.Many2one('farm.animal', string='Animal', required=True, index=True)
    rfid_tag = fields.Char(string='RFID Tag', related='animal_id.rfid_tag', store=True)
    
    # Production Details
    production_date = fields.Date(string='Date', required=True, default=fields.Date.today, index=True)
    session = fields.Selection([
        ('morning', 'Morning'),
        ('evening', 'Evening'),
        ('night', 'Night'),
    ], string='Milking Session', required=True)
    
    quantity_liters = fields.Float(string='Quantity (Liters)', required=True, digits=(6, 2))
    
    # Quality Parameters
    fat_percentage = fields.Float(string='Fat %', digits=(4, 2))
    snf_percentage = fields.Float(string='SNF %', digits=(4, 2))
    protein_percentage = fields.Float(string='Protein %', digits=(4, 2))
    lactose_percentage = fields.Float(string='Lactose %', digits=(4, 2))
    density = fields.Float(string='Density (kg/L)', digits=(4, 3))
    temperature = fields.Float(string='Temperature (°C)', digits=(4, 1))
    ph_value = fields.Float(string='pH Value', digits=(3, 1))
    
    # Calculated Fields
    total_solids = fields.Float(string='Total Solids %', compute='_compute_total_solids', store=True)
    snf_price_factor = fields.Float(string='SNF Price Factor', compute='_compute_price_factors')
    fat_price_factor = fields.Float(string='Fat Price Factor', compute='_compute_price_factors')
    estimated_value = fields.Float(string='Estimated Value', compute='_compute_estimated_value')
    
    # Device Information
    device_id = fields.Many2one('iot.device', string='Milk Meter Device')
    device_reading_id = fields.Char(string='Device Reading ID')
    
    # Recording Information
    recorded_by = fields.Many2one('res.users', string='Recorded By', default=lambda self: self.env.user)
    recording_method = fields.Selection([
        ('manual', 'Manual Entry'),
        ('iot_device', 'IoT Device'),
        ('mobile_app', 'Mobile App'),
    ], string='Recording Method', default='manual')
    
    # Notes
    notes = fields.Text(string='Notes')
    
    # State
    state = fields.Selection([
        ('draft', 'Draft'),
        ('confirmed', 'Confirmed'),
        ('cancelled', 'Cancelled'),
    ], string='Status', default='draft')
    
    company_id = fields.Many2one('res.company', string='Company', default=lambda self: self.env.company)

    @api.depends('animal_id', 'production_date', 'session')
    def _compute_name(self):
        for record in self:
            record.name = f"{record.animal_id.name or 'Unknown'}/{record.production_date or 'No Date'}/{record.session or 'No Session'}"

    @api.depends('fat_percentage', 'snf_percentage')
    def _compute_total_solids(self):
        for record in self:
            if record.fat_percentage and record.snf_percentage:
                record.total_solids = record.fat_percentage + record.snf_percentage
            else:
                record.total_solids = 0

    @api.depends('fat_percentage', 'snf_percentage')
    def _compute_price_factors(self):
        # Smart Dairy pricing formula based on fat and SNF
        for record in self:
            if record.fat_percentage:
                record.fat_price_factor = record.fat_percentage * 10  # Example: 4% = 40 points
            else:
                record.fat_price_factor = 0
            
            if record.snf_percentage:
                record.snf_price_factor = record.snf_percentage * 8   # Example: 8% = 64 points
            else:
                record.snf_price_factor = 0

    @api.depends('quantity_liters', 'fat_price_factor', 'snf_price_factor')
    def _compute_estimated_value(self):
        # Base price per liter adjusted by quality
        base_price = 60  # BDT per liter base
        for record in self:
            quality_multiplier = 1 + ((record.fat_price_factor + record.snf_price_factor) / 1000)
            record.estimated_value = record.quantity_liters * base_price * quality_multiplier

    def action_confirm(self):
        self.write({'state': 'confirmed'})
        # Update animal lifetime production
        for record in self:
            record.animal_id.lifetime_production += record.quantity_liters

    def action_cancel(self):
        self.write({'state': 'cancelled'})

    # Constraints
    _sql_constraints = [
        ('unique_milking_session', 
         'UNIQUE(animal_id, production_date, session)', 
         'Duplicate milking record for this session!'),
    ]
```

---

## 4. HERD MANAGEMENT

### 4.1 Animal Lifecycle Management

```python
# Animal lifecycle state machine
ANIMAL_LIFECYCLE = {
    'calf': {
        'female': ['heifer', 'sold', 'deceased'],
        'male': ['bull', 'sold', 'deceased'],
    },
    'heifer': {
        'female': ['lactating', 'pregnant', 'sold', 'deceased'],
    },
    'lactating': {
        'female': ['pregnant', 'dry', 'sold', 'deceased'],
    },
    'pregnant': {
        'female': ['lactating', 'dry', 'sold', 'deceased'],
    },
    'dry': {
        'female': ['lactating', 'pregnant', 'sold', 'deceased'],
    },
    'bull': {
        'male': ['sold', 'deceased'],
    },
}

class AnimalLifecycleManager:
    """Manages animal state transitions"""
    
    @staticmethod
    def can_transition(animal, new_status):
        """Check if status transition is valid"""
        current = animal.status
        gender = animal.gender
        
        if current not in ANIMAL_LIFECYCLE:
            return False
        
        allowed = ANIMAL_LIFECYCLE[current].get(gender, [])
        return new_status in allowed
    
    @staticmethod
    def on_calving(animal, calving_date):
        """Handle calving event"""
        animal.write({
            'status': 'lactating',
            'current_lactation': animal.current_lactation + 1,
            'total_lactations': animal.total_lactations + 1,
            'last_calving_date': calving_date,
        })
    
    @staticmethod
    def on_drying_off(animal):
        """Handle drying off event"""
        animal.write({
            'status': 'dry',
        })
    
    @staticmethod
    def on_breeding_confirmed(animal, breeding_date):
        """Handle confirmed pregnancy"""
        animal.write({
            'status': 'pregnant',
            'last_breeding_date': breeding_date,
        })
```

---

## 5. MILK PRODUCTION TRACKING

### 5.1 Daily Production Dashboard

```python
class MilkProductionDashboard(models.Model):
    _name = 'milk.production.dashboard'
    _description = 'Milk Production Dashboard'
    _auto = False
    
    @api.model
    def get_daily_summary(self, date=None):
        """Get daily milk production summary"""
        if not date:
            date = fields.Date.today()
        
        # Morning session
        morning_total = self.env['milk.production'].search([
            ('production_date', '=', date),
            ('session', '=', 'morning'),
            ('state', '=', 'confirmed'),
        ]).mapped('quantity_liters')
        
        # Evening session
        evening_total = self.env['milk.production'].search([
            ('production_date', '=', date),
            ('session', '=', 'evening'),
            ('state', '=', 'confirmed'),
        ]).mapped('quantity_liters')
        
        # Quality averages
        quality_records = self.env['milk.production'].search([
            ('production_date', '=', date),
            ('state', '=', 'confirmed'),
        ])
        
        return {
            'date': date,
            'morning_liters': sum(morning_total),
            'evening_liters': sum(evening_total),
            'total_liters': sum(morning_total) + sum(evening_total),
            'animal_count': len(quality_records.mapped('animal_id')),
            'avg_fat': sum(quality_records.mapped('fat_percentage')) / len(quality_records) if quality_records else 0,
            'avg_snf': sum(quality_records.mapped('snf_percentage')) / len(quality_records) if quality_records else 0,
        }
    
    @api.model
    def get_animal_ranking(self, start_date, end_date, limit=20):
        """Get top producing animals"""
        self.env.cr.execute("""
            SELECT 
                animal_id,
                SUM(quantity_liters) as total_liters,
                AVG(quantity_liters) as avg_per_session,
                COUNT(*) as session_count
            FROM milk_production
            WHERE production_date BETWEEN %s AND %s
                AND state = 'confirmed'
            GROUP BY animal_id
            ORDER BY total_liters DESC
            LIMIT %s
        """, (start_date, end_date, limit))
        
        return self.env.cr.dictfetchall()
```

---

## 6. HEALTH MANAGEMENT

### 6.1 Health Record Model

```python
class AnimalHealthRecord(models.Model):
    _name = 'animal.health.record'
    _description = 'Animal Health Record'
    _order = 'date desc'
    _inherit = ['mail.thread']

    name = fields.Char(string='Reference', default=lambda self: self.env['ir.sequence'].next_by_code('animal.health'))
    
    animal_id = fields.Many2one('farm.animal', string='Animal', required=True)
    date = fields.Date(string='Date', required=True, default=fields.Date.today)
    
    record_type = fields.Selection([
        ('checkup', 'Routine Checkup'),
        ('treatment', 'Treatment'),
        ('vaccination', 'Vaccination'),
        ('deworming', 'Deworming'),
        ('surgery', 'Surgery'),
        ('emergency', 'Emergency'),
    ], string='Type', required=True)
    
    # Diagnosis
    symptoms = fields.Text(string='Symptoms/Observations')
    diagnosis = fields.Text(string='Diagnosis')
    
    # Treatment
    treatment_given = fields.Text(string='Treatment Given')
    medicines = fields.One2many('health.medicine.line', 'health_record_id', string='Medicines')
    
    # Follow-up
    follow_up_required = fields.Boolean(string='Follow-up Required')
    follow_up_date = fields.Date(string='Follow-up Date')
    
    # Cost
    treatment_cost = fields.Float(string='Treatment Cost')
    
    # Veterinarian
    veterinarian_id = fields.Many2one('res.partner', string='Veterinarian', 
                                       domain=[('is_veterinarian', '=', True)])
    
    # State
    state = fields.Selection([
        ('draft', 'Draft'),
        ('in_progress', 'In Progress'),
        ('completed', 'Completed'),
        ('cancelled', 'Cancelled'),
    ], string='Status', default='draft')

class HealthMedicineLine(models.Model):
    _name = 'health.medicine.line'
    
    health_record_id = fields.Many2one('animal.health.record', string='Health Record')
    product_id = fields.Many2one('product.product', string='Medicine', 
                                  domain=[('is_medicine', '=', True)])
    quantity = fields.Float(string='Quantity')
    uom_id = fields.Many2one('uom.uom', string='Unit')
    dosage = fields.Char(string='Dosage Instructions')
    duration_days = fields.Integer(string='Duration (Days)')
```

### 6.2 Vaccination Schedule

```python
class VaccinationSchedule(models.Model):
    _name = 'vaccination.schedule'
    _description = 'Vaccination Schedule Template'
    
    name = fields.Char(string='Vaccine Name', required=True)
    disease = fields.Char(string='Disease Targeted', required=True)
    
    # Schedule
    applicable_species = fields.Selection([
        ('cattle', 'Cattle'),
        ('buffalo', 'Buffalo'),
        ('all', 'All'),
    ], string='Applicable To', default='cattle')
    
    first_dose_age_days = fields.Integer(string='First Dose Age (Days)')
    dose_interval_days = fields.Integer(string='Interval Between Doses (Days)')
    number_of_doses = fields.Integer(string='Number of Doses', default=1)
    booster_interval_months = fields.Integer(string='Booster Interval (Months)')
    
    # Application
    route = fields.Selection([
        ('oral', 'Oral'),
        ('im', 'Intramuscular'),
        ('sc', 'Subcutaneous'),
        ('iv', 'Intravenous'),
    ], string='Route of Administration')
    
    # Auto-generation
    auto_generate = fields.Boolean(string='Auto-generate Reminders', default=True)
    reminder_days_before = fields.Integer(string='Reminder Days Before', default=7)
```

---

## 7. REPRODUCTION MANAGEMENT

### 7.1 Breeding Record Model

```python
class BreedingRecord(models.Model):
    _name = 'breeding.record'
    _description = 'Breeding Record'
    _order = 'breeding_date desc'
    
    name = fields.Char(string='Reference', default=lambda self: self.env['ir.sequence'].next_by_code('breeding.record'))
    
    # Animals
    female_id = fields.Many2one('farm.animal', string='Female', required=True,
                                 domain=[('gender', '=', 'female')])
    male_id = fields.Many2one('farm.animal', string='Male/Bull',
                               domain=[('gender', '=', 'male')])
    
    # Breeding Details
    breeding_date = fields.Date(string='Breeding Date', required=True, default=fields.Date.today)
    breeding_method = fields.Selection([
        ('natural', 'Natural'),
        ('ai', 'Artificial Insemination'),
        ('embryo_transfer', 'Embryo Transfer'),
    ], string='Method', required=True)
    
    # AI Details
    semen_batch = fields.Char(string='Semen Batch Number')
    semen_supplier = fields.Char(string='Semen Supplier')
    
    # Pregnancy Check
    pregnancy_check_date = fields.Date(string='Pregnancy Check Date')
    pregnancy_status = fields.Selection([
        ('pending', 'Pending'),
        ('confirmed', 'Confirmed Pregnant'),
        ('not_pregnant', 'Not Pregnant'),
        ('uncertain', 'Uncertain'),
    ], string='Pregnancy Status', default='pending')
    
    # Expected Calving
    expected_calving_date = fields.Date(string='Expected Calving Date', 
                                         compute='_compute_expected_calving', store=True)
    
    # Actual Calving
    actual_calving_date = fields.Date(string='Actual Calving Date')
    calving_result = fields.Selection([
        ('live_birth', 'Live Birth'),
        ('stillborn', 'Stillborn'),
        ('abortion', 'Abortion'),
        ('not_yet', 'Not Yet Calved'),
    ], string='Calving Result', default='not_yet')
    
    # Offspring
    offspring_ids = fields.One2many('farm.animal', 'breeding_record_id', string='Offspring')
    
    notes = fields.Text(string='Notes')
    
    state = fields.Selection([
        ('draft', 'Draft'),
        ('confirmed', 'Confirmed'),
        ('completed', 'Completed'),
        ('cancelled', 'Cancelled'),
    ], string='Status', default='draft')

    @api.depends('breeding_date')
    def _compute_expected_calving(self):
        for record in self:
            if record.breeding_date:
                record.expected_calving_date = record.breeding_date + relativedelta(days=283)
            else:
                record.expected_calving_date = False
```

---

## 8. FEED MANAGEMENT

### 8.1 Feed Ration Model

```python
class FeedRation(models.Model):
    _name = 'feed.ration'
    _description = 'Feed Ration Formulation'
    
    name = fields.Char(string='Ration Name', required=True)
    code = fields.Char(string='Ration Code')
    
    # Applicability
    applicable_for = fields.Selection([
        ('calf', 'Calf'),
        ('heifer', 'Heifer'),
        ('lactating', 'Lactating Cow'),
        ('dry', 'Dry Cow'),
        ('bull', 'Bull'),
        ('all', 'All'),
    ], string='Applicable For', required=True)
    
    # Nutritional Targets
    target_dm_intake = fields.Float(string='Target DM Intake (kg/day)')
    target_cp_percentage = fields.Float(string='Target CP %')
    target_tdn_percentage = fields.Float(string='Target TDN %')
    target_ca_percentage = fields.Float(string='Target Calcium %')
    target_p_percentage = fields.Float(string='Target Phosphorus %')
    
    # Ingredients
    ingredient_lines = fields.One2many('feed.ration.line', 'ration_id', string='Ingredients')
    
    # Calculated
    total_quantity = fields.Float(string='Total Quantity (kg)', compute='_compute_totals')
    total_cost = fields.Float(string='Total Cost', compute='_compute_totals')
    cost_per_kg = fields.Float(string='Cost per kg', compute='_compute_totals')
    
    is_active = fields.Boolean(string='Active', default=True)

class FeedRationLine(models.Model):
    _name = 'feed.ration.line'
    
    ration_id = fields.Many2one('feed.ration', string='Ration')
    sequence = fields.Integer(string='Sequence')
    
    ingredient_id = fields.Many2one('product.product', string='Ingredient',
                                     domain=[('is_feed', '=', True)])
    quantity_kg = fields.Float(string='Quantity (kg)', digits=(8, 3))
    percentage = fields.Float(string='Percentage %', compute='_compute_percentage')
    
    # Nutritional values
    dm_percentage = fields.Float(string='DM %', related='ingredient_id.dm_percentage')
    cp_percentage = fields.Float(string='CP %', related='ingredient_id.cp_percentage')
    tdn_percentage = fields.Float(string='TDN %', related='ingredient_id.tdn_percentage')
    
    cost_per_kg = fields.Float(string='Cost/kg', related='ingredient_id.standard_price')
    line_cost = fields.Float(string='Line Cost', compute='_compute_line_cost')
```

---

## 9. IOT INTEGRATION

### 9.1 IoT Device Registry

```python
class IoTDevice(models.Model):
    _name = 'iot.device'
    _description = 'IoT Device Registry'
    
    name = fields.Char(string='Device Name', required=True)
    device_code = fields.Char(string='Device Code', required=True, index=True)
    
    device_type = fields.Selection([
        ('milk_meter', 'Milk Meter'),
        ('milk_analyzer', 'Milk Quality Analyzer'),
        ('temperature_sensor', 'Temperature Sensor'),
        ('humidity_sensor', 'Humidity Sensor'),
        ('activity_tracker', 'Activity Tracker'),
        ('rfid_reader', 'RFID Reader'),
        ('weighing_scale', 'Weighing Scale'),
    ], string='Device Type', required=True)
    
    # Location
    barn_id = fields.Many2one('farm.location', string='Installed Location')
    
    # Connection
    connection_type = fields.Selection([
        ('mqtt', 'MQTT'),
        ('http', 'HTTP'),
        ('lora', 'LoRaWAN'),
        ('ble', 'Bluetooth'),
    ], string='Connection Type')
    
    mqtt_topic = fields.Char(string='MQTT Topic')
    last_seen = fields.Datetime(string='Last Communication')
    
    # Status
    status = fields.Selection([
        ('active', 'Active'),
        ('inactive', 'Inactive'),
        ('maintenance', 'Maintenance'),
        ('error', 'Error'),
    ], string='Status', default='active')
    
    firmware_version = fields.Char(string='Firmware Version')
    
    # Associated animal (for individual devices)
    assigned_animal_id = fields.Many2one('farm.animal', string='Assigned Animal')

class SensorReading(models.Model):
    _name = 'sensor.reading'
    _description = 'IoT Sensor Reading'
    _order = 'timestamp desc'
    
    device_id = fields.Many2one('iot.device', string='Device', required=True)
    timestamp = fields.Datetime(string='Timestamp', required=True, default=fields.Datetime.now)
    
    reading_type = fields.Selection([
        ('milk_volume', 'Milk Volume'),
        ('milk_quality', 'Milk Quality'),
        ('temperature', 'Temperature'),
        ('humidity', 'Humidity'),
        ('activity', 'Activity'),
        ('weight', 'Weight'),
        ('rfid', 'RFID Scan'),
    ], string='Reading Type')
    
    # Raw data
    raw_value = fields.Float(string='Raw Value')
    raw_unit = fields.Char(string='Unit')
    raw_data_json = fields.Text(string='Raw JSON Data')
    
    # Processed
    processed = fields.Boolean(string='Processed', default=False)
    processed_record_id = fields.Many2oneReference(
        string='Processed Record',
        model_field='processed_record_model'
    )
    processed_record_model = fields.Char(string='Processed Record Model')
```

---

## 10. APIs AND WEB SERVICES

### 10.1 REST API Controllers

```python
# controllers/main.py
from odoo import http
from odoo.http import request
import json

class FarmAPIController(http.Controller):
    
    # Animal API
    @http.route('/api/v1/farm/animals', type='json', auth='user', methods=['GET'])
    def get_animals(self, limit=100, offset=0, status=None, barn_id=None):
        """Get list of animals"""
        domain = []
        if status:
            domain.append(('status', '=', status))
        if barn_id:
            domain.append(('barn_id', '=', int(barn_id)))
        
        animals = request.env['farm.animal'].search(domain, limit=int(limit), offset=int(offset))
        return {
            'count': len(animals),
            'animals': [{
                'id': a.id,
                'name': a.name,
                'rfid_tag': a.rfid_tag,
                'status': a.status,
                'breed': a.breed_id.name if a.breed_id else None,
                'age_months': a.age_months,
            } for a in animals]
        }
    
    @http.route('/api/v1/farm/animals/<int:animal_id>', type='json', auth='user', methods=['GET'])
    def get_animal_detail(self, animal_id):
        """Get animal details"""
        animal = request.env['farm.animal'].browse(animal_id)
        if not animal.exists():
            return {'error': 'Animal not found'}, 404
        
        return {
            'id': animal.id,
            'name': animal.name,
            'rfid_tag': animal.rfid_tag,
            'ear_tag': animal.ear_tag,
            'species': animal.species,
            'breed': animal.breed_id.name if animal.breed_id else None,
            'gender': animal.gender,
            'birth_date': str(animal.birth_date) if animal.birth_date else None,
            'age_months': animal.age_months,
            'status': animal.status,
            'health_status': animal.health_status,
            'daily_milk_avg': animal.daily_milk_avg,
            'barn': animal.barn_id.name if animal.barn_id else None,
        }
    
    @http.route('/api/v1/farm/milk-production', type='json', auth='user', methods=['POST'])
    def record_milk_production(self, **kwargs):
        """Record milk production"""
        data = request.jsonrequest
        
        record = request.env['milk.production'].create({
            'animal_id': data.get('animal_id'),
            'production_date': data.get('production_date'),
            'session': data.get('session'),
            'quantity_liters': data.get('quantity_liters'),
            'fat_percentage': data.get('fat_percentage'),
            'snf_percentage': data.get('snf_percentage'),
            'recording_method': 'mobile_app',
        })
        
        return {'id': record.id, 'message': 'Record created successfully'}
    
    @http.route('/api/v1/farm/sync', type='json', auth='user', methods=['POST'])
    def sync_mobile_data(self, **kwargs):
        """Sync data from mobile app"""
        data = request.jsonrequest
        
        results = {
            'milk_records_created': 0,
            'health_records_created': 0,
            'errors': []
        }
        
        # Process milk records
        for milk_record in data.get('milk_records', []):
            try:
                request.env['milk.production'].create(milk_record)
                results['milk_records_created'] += 1
            except Exception as e:
                results['errors'].append(str(e))
        
        # Process health records
        for health_record in data.get('health_records', []):
            try:
                request.env['animal.health.record'].create(health_record)
                results['health_records_created'] += 1
            except Exception as e:
                results['errors'].append(str(e))
        
        return results
```

---

## 11. REPORTS AND ANALYTICS

### 11.1 Production Reports

```python
class MilkProductionReport(models.AbstractModel):
    _name = 'report.smart_farm_mgmt.milk_production_report'
    _description = 'Milk Production Report'
    
    @api.model
    def _get_report_values(self, docids, data=None):
        start_date = data.get('start_date')
        end_date = data.get('end_date')
        
        # Get production data
        productions = self.env['milk.production'].search([
            ('production_date', '>=', start_date),
            ('production_date', '<=', end_date),
            ('state', '=', 'confirmed'),
        ])
        
        # Daily totals
        daily_totals = {}
        for p in productions:
            date_str = str(p.production_date)
            if date_str not in daily_totals:
                daily_totals[date_str] = {'morning': 0, 'evening': 0, 'total': 0}
            daily_totals[date_str][p.session] += p.quantity_liters
            daily_totals[date_str]['total'] += p.quantity_liters
        
        return {
            'start_date': start_date,
            'end_date': end_date,
            'daily_totals': daily_totals,
            'total_production': sum(p.quantity_liters for p in productions),
            'avg_fat': sum(p.fat_percentage or 0 for p in productions) / len(productions) if productions else 0,
            'avg_snf': sum(p.snf_percentage or 0 for p in productions) / len(productions) if productions else 0,
        }
```

---

## 12. MOBILE INTEGRATION

### 12.1 Mobile Synchronization

```python
class MobileSyncService(models.Model):
    _name = 'mobile.sync.service'
    _description = 'Mobile Synchronization Service'
    
    @api.model
    def get_sync_data(self, last_sync_timestamp):
        """Get data for mobile sync"""
        last_sync = fields.Datetime.from_string(last_sync_timestamp)
        
        # Get modified animals
        animals = self.env['farm.animal'].search([
            ('write_date', '>=', last_sync),
        ])
        
        # Get new milk production records
        milk_records = self.env['milk.production'].search([
            ('create_date', '>=', last_sync),
        ])
        
        return {
            'animals': animals.read(['id', 'name', 'rfid_tag', 'status']),
            'milk_records': milk_records.read(['id', 'animal_id', 'production_date', 'session', 'quantity_liters']),
            'sync_timestamp': fields.Datetime.now(),
        }
    
    @api.model
    def upload_offline_data(self, user_id, milk_records, health_records):
        """Process offline data from mobile"""
        created_ids = []
        errors = []
        
        for record in milk_records:
            try:
                # Validate RFID and find animal
                animal = self.env['farm.animal'].search([('rfid_tag', '=', record['rfid_tag'])], limit=1)
                if not animal:
                    errors.append(f"Animal not found: {record['rfid_tag']}")
                    continue
                
                new_record = self.env['milk.production'].create({
                    'animal_id': animal.id,
                    'production_date': record['production_date'],
                    'session': record['session'],
                    'quantity_liters': record['quantity_liters'],
                    'recording_method': 'mobile_app',
                    'recorded_by': user_id,
                })
                created_ids.append(new_record.id)
            except Exception as e:
                errors.append(str(e))
        
        return {'created_count': len(created_ids), 'errors': errors}
```

---

## 13. APPENDICES

### Appendix A: Database Schema Summary

| Table | Records (Est.) | Growth Rate |
|-------|---------------|-------------|
| farm.animal | 1,000 | +200/year |
| milk.production | 500,000/year | Linear with herd size |
| animal.health.record | 50,000/year | Based on health events |
| breeding.record | 5,000/year | Based on breeding cycles |
| sensor.reading | 10M/year | Based on IoT deployment |

### Appendix B: Performance Considerations

- **Partitioning**: milk_production table partitioned by month
- **Archiving**: Records older than 2 years archived to cold storage
- **Indexing**: All foreign keys and frequently queried fields indexed
- **Caching**: Redis cache for frequently accessed animal records

---

**END OF SMART FARM MODULE TECHNICAL SPECIFICATION**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Feb 28, 2026 | Odoo Lead | Initial version |
