# Milestone 33 - Smart Farm Management Core Implementation

## Smart Dairy Smart Portal + ERP System

---

**Phase:** Phase 4 - Advanced Operations & Intelligence  
**Milestone:** 33  
**Duration:** Days 321-330 (10 Days)  
**Status:** Implementation & Development  
**Last Updated:** February 2026

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Farm Management Architecture](#2-farm-management-architecture)
3. [Daily Task Allocation](#3-daily-task-allocation)
4. [Database Schema](#4-database-schema)
5. [Implementation Logic](#5-implementation-logic)
6. [Mobile App Specifications](#6-mobile-app-specifications)
7. [API Documentation](#7-api-documentation)
8. [Testing Protocols](#8-testing-protocols)
9. [Deliverables](#9-deliverables)
10. [Risk & Compliance](#10-risk--compliance)
11. [Appendices](#11-appendices)

---

## 1. Executive Summary

### 1.1 Milestone Vision

Milestone 33 represents a pivotal advancement in the Smart Dairy ecosystem, establishing the foundational infrastructure for comprehensive Smart Farm Management. This milestone bridges the gap between basic farm management capabilities established in Phase 2 and the sophisticated IoT infrastructure deployed in Phase 3. The objective is to create an integrated, intelligent farm management system that leverages real-time data, predictive analytics, and mobile accessibility to transform dairy farm operations.

The Smart Farm Management Core Implementation serves as the central nervous system for all farm-related activities, encompassing herd management, health monitoring, reproduction tracking, milk production analysis, and feed optimization. By the completion of this milestone, Smart Dairy will possess a world-class farm management platform capable of supporting operations ranging from small family farms to large-scale commercial dairy enterprises with thousands of animals.

### 1.2 Farm Management Objectives

#### Primary Objectives

1. **Comprehensive Herd Management System**
   - Complete animal lifecycle tracking from birth to culling
   - Advanced genetics and pedigree management
   - Performance metrics and analytics
   - Automated classification and grouping

2. **Advanced Health Management**
   - Veterinary workflow automation
   - Vaccination schedule management
   - Treatment tracking with withdrawal period calculations
   - Health alert engine with predictive capabilities

3. **Reproduction Management Excellence**
   - Heat detection algorithms and alerts
   - Artificial insemination (AI) record management
   - Pregnancy checking and monitoring
   - Calving event management and offspring registration

4. **Milk Production Intelligence**
   - Daily production recording and validation
   - Quality parameter tracking (fat, protein, SCC, etc.)
   - Yield prediction models
   - Comparative analytics and benchmarking

5. **Feed Management Optimization**
   - Total Mixed Ration (TMR) calculations
   - Feed conversion efficiency tracking
   - Nutritional requirement calculations
   - Cost optimization algorithms

6. **Mobile-First Farm Operations**
   - Native Flutter mobile application
   - Offline-first architecture for field operations
   - RFID/Barcode scanning integration
   - Voice input and photo capture capabilities

7. **RFID Integration Infrastructure**
   - Real-time animal identification
   - Automated data capture
   - Location tracking capabilities
   - Event-triggered workflows

### 1.3 Success Criteria

#### Quantitative Metrics

| Metric | Target | Measurement Method |
|--------|--------|-------------------|
| Herd Management Data Accuracy | >99.5% | Audit sampling of 1000 records |
| Health Alert Response Time | <30 seconds | System performance monitoring |
| Mobile App Sync Success Rate | >99% | Automated sync logging |
| RFID Scan Accuracy | >99.9% | Hardware testing protocols |
| Milk Yield Prediction Accuracy | ±5% | Historical data validation |
| System Uptime | >99.9% | Infrastructure monitoring |
| API Response Time (p95) | <200ms | Load testing metrics |
| Offline Mode Data Retention | 30 days | Storage capacity testing |

#### Qualitative Criteria

1. **User Experience Excellence**
   - Intuitive interface requiring minimal training
   - Consistent experience across web and mobile platforms
   - Accessibility compliance (WCAG 2.1 AA)
   - Multi-language support (minimum 5 languages)

2. **Integration Completeness**
   - Seamless data flow between all farm modules
   - Real-time synchronization between mobile and backend
   - Third-party hardware compatibility (RFID readers, milk meters)
   - ERP financial module integration

3. **Operational Efficiency**
   - 50% reduction in manual data entry time
   - 30% improvement in health issue detection speed
   - 25% reduction in reproductive management overhead
   - Automated reporting reducing administrative time by 40%

4. **Compliance and Safety**
   - Full traceability for food safety regulations
   - Animal welfare documentation compliance
   - Veterinary medicine withdrawal period enforcement
   - Audit-ready record keeping

### 1.4 Strategic Impact

The completion of Milestone 33 positions Smart Dairy as a comprehensive solution provider in the agricultural technology space. The farm management capabilities developed during this milestone provide the foundation for:

- **Data-Driven Decision Making**: Farm managers can make informed decisions based on comprehensive analytics and predictive insights
- **Operational Scalability**: The system architecture supports growth from hundreds to tens of thousands of animals
- **Regulatory Compliance**: Automated compliance tracking reduces regulatory risk and audit preparation time
- **Supply Chain Integration**: Quality and traceability data enables premium market access
- **Sustainability Metrics**: Feed efficiency and resource optimization support environmental sustainability goals

---

## 2. Farm Management Architecture

### 2.1 System Architecture Overview

The Smart Farm Management system follows a microservices-oriented architecture built upon the robust Odoo ERP framework, extended with custom modules for dairy-specific functionality. The architecture emphasizes scalability, reliability, and real-time data processing capabilities essential for modern farm operations.

```
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│                                    PRESENTATION LAYER                                        │
├─────────────────────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────┐  ┌─────────────────────┐  ┌─────────────────────────────────────┐  │
│  │   Web Application   │  │   Mobile App        │  │   RFID/Hardware Interfaces          │  │
│  │   (Odoo Frontend)   │  │   (Flutter)         │  │   (IoT Gateway)                     │  │
│  └─────────────────────┘  └─────────────────────┘  └─────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────────────────────────────────┘
                                            │
                                            ▼
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│                                      API GATEWAY LAYER                                       │
├─────────────────────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────┐  ┌─────────────────────┐  ┌─────────────────────────────────────┐  │
│  │   REST API          │  │   GraphQL           │  │   WebSocket (Real-time)             │  │
│  │   (Odoo Controllers)│  │   (Custom Endpoint) │  │   (Live Updates)                    │  │
│  └─────────────────────┘  └─────────────────────┘  └─────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────────────────────────────────┘
                                            │
                                            ▼
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│                                    BUSINESS LOGIC LAYER                                      │
├─────────────────────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────────────────┐ │
│  │   Herd      │ │   Health    │ │Reproduction │ │    Milk     │ │        Feed             │ │
│  │ Management  │ │ Management  │ │ Management  │ │  Production │ │     Management          │ │
│  │   Service   │ │   Service   │ │   Service   │ │   Service   │ │      Service            │ │
│  └─────────────┘ └─────────────┘ └─────────────┘ └─────────────┘ └─────────────────────────┘ │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────────────────┐ │
│  │   Genetics  │ │  Veterinary │ │   Alert     │ │  Analytics  │ │    Mobile Sync          │ │
│  │  Tracking   │ │  Workflow   │ │   Engine    │ │   Engine    │ │      Engine             │ │
│  │   Service   │ │   Service   │ │   Service   │ │   Service   │ │      Service            │ │
│  └─────────────┘ └─────────────┘ └─────────────┘ └─────────────┘ └─────────────────────────┘ │
└─────────────────────────────────────────────────────────────────────────────────────────────┘
                                            │
                                            ▼
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│                                      DATA ACCESS LAYER                                       │
├─────────────────────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────┐  ┌─────────────────────┐  ┌─────────────────────────────────────┐  │
│  │   Odoo ORM          │  │   Raw SQL           │  │   Cache Layer                       │  │
│  │   (Object-Relational│  │   (Complex Queries) │  │   (Redis)                           │  │
│  │    Mapping)         │  │                     │  │                                     │  │
│  └─────────────────────┘  └─────────────────────┘  └─────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────────────────────────────────┘
                                            │
                                            ▼
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│                                       DATA STORAGE LAYER                                     │
├─────────────────────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────┐  ┌─────────────────────┐  ┌─────────────────────────────────────┐  │
│  │   PostgreSQL        │  │   Time-Series DB    │  │   Document Store                    │  │
│  │   (Primary Data)    │  │   (Metrics/Historical│  │   (Files/Images)                    │  │
│  │                     │  │    Data)            │  │                                     │  │
│  └─────────────────────┘  └─────────────────────┘  └─────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Herd Management System Architecture

The Herd Management system serves as the central registry for all farm animals, maintaining comprehensive records that span the entire lifecycle from birth through productive life to culling.

#### Core Components

**Animal Master Data Management**

The animal master data model captures all essential identification and classification information:

```python
# Core Animal Model Structure (Odoo)
class FarmAnimal(models.Model):
    _name = 'farm.animal'
    _description = 'Farm Animal Master Record'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    
    # Identification Fields
    internal_id = fields.Char('Internal ID', required=True, index=True)
    electronic_id = fields.Char('EID/RFID', index=True, tracking=True)
    visual_id = fields.Char('Visual Tag/Ear Number', tracking=True)
    name = fields.Char('Animal Name')
    
    # Classification
    species = fields.Selection([
        ('cattle', 'Cattle'),
        ('goat', 'Goat'),
        ('sheep', 'Sheep'),
        ('buffalo', 'Buffalo')
    ], required=True, default='cattle')
    
    breed_id = fields.Many2one('farm.breed', string='Breed')
    animal_type = fields.Selection([
        ('dairy_cow', 'Dairy Cow'),
        ('beef_cow', 'Beef Cow'),
        ('heifer', 'Heifer'),
        ('calf_female', 'Female Calf'),
        ('calf_male', 'Male Calf'),
        ('bull', 'Bull'),
        ('steer', 'Steer')
    ], string='Animal Type', tracking=True)
    
    # Lifecycle Status
    status = fields.Selection([
        ('active', 'Active'),
        ('dry', 'Dry'),
        ('sold', 'Sold'),
        ('died', 'Died'),
        ('culled', 'Culled'),
        ('transferred', 'Transferred')
    ], default='active', tracking=True)
    
    # Location Tracking
    location_id = fields.Many2one('farm.location', string='Current Location')
    group_id = fields.Many2one('farm.animal.group', string='Animal Group')
    
    # Key Dates
    birth_date = fields.Date('Date of Birth', tracking=True)
    entry_date = fields.Date('Entry Date', default=fields.Date.today)
    exit_date = fields.Date('Exit Date')
    
    # Parentage (Genetics)
    sire_id = fields.Many2one('farm.animal', string='Sire (Father)')
    dam_id = fields.Many2one('farm.animal', string='Dam (Mother)')
    
    # Computed Fields
    age_days = fields.Integer('Age (Days)', compute='_compute_age')
    age_display = fields.Char('Age', compute='_compute_age_display')
    lactation_number = fields.Integer('Lactation Number', compute='_compute_lactation')
    days_in_milk = fields.Integer('Days in Milk', compute='_compute_dim')
```

**Genetics and Pedigree Management**

The genetics tracking system maintains detailed breeding records and calculates inbreeding coefficients:

```python
class GeneticsTracking(models.Model):
    _name = 'farm.animal.genetics'
    _description = 'Animal Genetics and Breeding Values'
    
    animal_id = fields.Many2one('farm.animal', required=True, ondelete='cascade')
    
    # Pedigree Information
    generation_depth = fields.Integer('Pedigree Depth', default=4)
    pedigree_chart = fields.Text('Pedigree JSON', compute='_compute_pedigree')
    
    # Inbreeding Analysis
    inbreeding_coefficient = fields.Float('Inbreeding Coefficient (%)')
    relationship_matrix = fields.Text('Relationship Coefficients')
    
    # Breeding Values (Estimated from various sources)
    ebv_milk_kg = fields.Float('EBV Milk (kg)')
    ebv_fat_pct = fields.Float('EBV Fat (%)')
    ebv_protein_pct = fields.Float('EBV Protein (%)')
    ebv_somatic_cell_score = fields.Float('EBV SCS')
    ebv_productive_life = fields.Float('EBV Productive Life')
    ebv_daughter_pregnancy_rate = fields.Float('EBV DPR')
    
    # Genetic Markers (if available)
    genetic_marker_data = fields.Text('Genetic Marker JSON')
    genetic_conditions = fields.One2many('farm.genetic.condition', 'genetics_id')
    
    def calculate_inbreeding_coefficient(self, generations=5):
        """
        Calculate inbreeding coefficient using Wright's path coefficient method
        """
        animal = self.animal_id
        sire_ancestors = self._get_ancestors(animal.sire_id, generations)
        dam_ancestors = self._get_ancestors(animal.dam_id, generations)
        
        common_ancestors = set(sire_ancestors.keys()) & set(dam_ancestors.keys())
        
        f_x = 0.0
        for ancestor in common_ancestors:
            # Path through sire
            path_sire = sire_ancestors[ancestor]['path']
            # Path through dam
            path_dam = dam_ancestors[ancestor]['path']
            
            n = len(path_sire) + len(path_dam) + 1
            f_a = ancestor.inbreeding_coefficient / 100.0 if ancestor.inbreeding_coefficient else 0
            
            f_x += (0.5 ** n) * (1 + f_a)
        
        self.inbreeding_coefficient = f_x * 100
        return f_x * 100
```

**Performance Analytics Engine**

The performance tracking system captures and analyzes key production indicators:

```python
class AnimalPerformance(models.Model):
    _name = 'farm.animal.performance'
    _description = 'Animal Performance Metrics'
    
    animal_id = fields.Many2one('farm.animal', required=True)
    date = fields.Date('Date', required=True)
    
    # Production Metrics
    milk_yield_kg = fields.Float('Milk Yield (kg)')
    milk_fat_pct = fields.Float('Fat %')
    milk_protein_pct = fields.Float('Protein %')
    milk_lactose_pct = fields.Float('Lactose %')
    somatic_cell_count = fields.Integer('SCC (cells/ml)')
    
    # Body Condition
    body_condition_score = fields.Float('BCS (1-5 scale)')
    body_weight_kg = fields.Float('Body Weight (kg)')
    
    # Efficiency Metrics
    feed_intake_kg_dm = fields.Float('DMI (kg/day)')
    feed_conversion_ratio = fields.Float('FCR', compute='_compute_fcr')
    milk_per_kg_dm = fields.Float('Milk/DMI', compute='_compute_efficiency')
    
    # Computed Aggregations
    lactation_total_milk = fields.Float('Lactation Total (kg)')
    lactation_305_day_milk = fields.Float('305-day Milk (kg)')
    peak_yield = fields.Float('Peak Yield (kg)')
    peak_day = fields.Integer('Peak Day')
    
    # Ranking Percentiles
    herd_milk_percentile = fields.Float('Herd Milk %ile')
    breed_milk_percentile = fields.Float('Breed Milk %ile')
```

#### Workflow Architecture

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                        HERD MANAGEMENT WORKFLOW ENGINE                           │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                 │
│   ┌──────────────┐    ┌──────────────┐    ┌──────────────┐    ┌──────────────┐ │
│   │   ANIMAL     │    │   STATUS     │    │   GROUP      │    │   LOCATION   │ │
│   │  REGISTRATION│───▶│  TRANSITION  │───▶│  ASSIGNMENT  │───▶│   TRANSFER   │ │
│   └──────────────┘    └──────────────┘    └──────────────┘    └──────────────┘ │
│          │                   │                   │                   │          │
│          ▼                   ▼                   ▼                   ▼          │
│   ┌──────────────────────────────────────────────────────────────────────┐     │
│   │                     EVENT NOTIFICATION SYSTEM                         │     │
│   │  • Status Change Alerts  • Group Change Notifications               │     │
│   │  • Location Updates      • Breeding Readiness Alerts                │     │
│   └──────────────────────────────────────────────────────────────────────┘     │
│          │                                                                      │
│          ▼                                                                      │
│   ┌──────────────────────────────────────────────────────────────────────┐     │
│   │                        AUDIT TRAIL LOGGER                             │     │
│   │  • All changes tracked  • User attribution  • Timestamp precision    │     │
│   └──────────────────────────────────────────────────────────────────────┘     │
│                                                                                 │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### 2.3 Health Management Workflow

The Health Management system provides comprehensive veterinary workflow automation, ensuring optimal animal welfare and regulatory compliance.

#### Veterinary Workflow Architecture

```
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│                              HEALTH MANAGEMENT WORKFLOW                                      │
├─────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                             │
│  ┌─────────────────┐                                                                       │
│  │  HEALTH EVENT   │                                                                       │
│  │   DETECTION     │                                                                       │
│  │                 │                                                                       │
│  │ • Visual signs  │                                                                       │
│  │ • IoT alerts    │                                                                       │
│  │ • Milk data     │                                                                       │
│  │ • Behavior      │                                                                       │
│  └────────┬────────┘                                                                       │
│           │                                                                                 │
│           ▼                                                                                 │
│  ┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐                       │
│  │   HEALTH CHECK  │────▶│  DIAGNOSIS &    │────▶│  TREATMENT      │                       │
│  │                 │     │  ASSESSMENT     │     │  PLANNING       │                       │
│  │ • Examination   │     │                 │     │                 │                       │
│  │ • Vitals        │     │ • Symptoms      │     │ • Medication    │                       │
│  │ • Scoring       │     │ • Diagnosis     │     │ • Dosage        │                       │
│  │ • Photos        │     │ • Severity      │     │ • Duration      │                       │
│  └─────────────────┘     └─────────────────┘     └────────┬────────┘                       │
│                                                           │                                 │
│                                                           ▼                                 │
│  ┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐                       │
│  │   FOLLOW-UP     │◀────│  TREATMENT      │◀────│  WITHDRAWAL     │                       │
│  │   MONITORING    │     │  EXECUTION      │     │  CALCULATION    │                       │
│  │                 │     │                 │     │                 │                       │
│  │ • Recovery check│     │ • Administration│     │ • Milk date     │                       │
│  │ • Retest        │     │ • Dose logging  │     │ • Meat date     │                       │
│  │ • Outcome       │     │ • Vet sign-off  │     │ • Alerts        │                       │
│  └─────────────────┘     └─────────────────┘     └─────────────────┘                       │
│                                                                                             │
└─────────────────────────────────────────────────────────────────────────────────────────────┘
```

#### Health Record Management

```python
class HealthRecord(models.Model):
    _name = 'farm.health.record'
    _description = 'Animal Health Record'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    
    # Identification
    name = fields.Char('Case Number', default=lambda self: self.env['ir.sequence'].next_by_code('farm.health.case'))
    animal_id = fields.Many2one('farm.animal', required=True, tracking=True)
    
    # Event Details
    event_date = fields.Datetime('Event Date', default=fields.Datetime.now, tracking=True)
    event_type = fields.Selection([
        ('examination', 'Routine Examination'),
        ('illness', 'Illness/Disease'),
        ('injury', 'Injury'),
        ('mastitis', 'Mastitis'),
        ('lameness', 'Lameness'),
        ('metabolic', 'Metabolic Disorder'),
        ('reproductive', 'Reproductive Issue'),
        ('vaccination', 'Vaccination'),
        ('deworming', 'Deworming'),
        ('surgery', 'Surgery'),
        ('other', 'Other')
    ], required=True, tracking=True)
    
    # Assessment
    body_temperature = fields.Float('Temperature (°C)')
    pulse_rate = fields.Integer('Pulse (bpm)')
    respiratory_rate = fields.Integer('Respiration (rpm)')
    body_condition_score = fields.Float('BCS')
    
    # Diagnosis
    symptoms = fields.Text('Symptoms Observed')
    diagnosis = fields.Text('Diagnosis')
    severity = fields.Selection([
        ('mild', 'Mild'),
        ('moderate', 'Moderate'),
        ('severe', 'Severe'),
        ('critical', 'Critical')
    ], tracking=True)
    
    # Treatment
    treatment_plan = fields.Text('Treatment Plan')
    treatment_ids = fields.One2many('farm.health.treatment', 'health_record_id')
    
    # Outcome
    status = fields.Selection([
        ('open', 'Open'),
        ('improving', 'Improving'),
        ('resolved', 'Resolved'),
        ('chronic', 'Chronic'),
        ('fatal', 'Fatal')
    ], default='open', tracking=True)
    
    # Responsible Party
    veterinarian_id = fields.Many2one('res.partner', string='Veterinarian')
    technician_id = fields.Many2one('res.users', string='Technician')
    
    # Documentation
    attachment_ids = fields.Many2many('ir.attachment', string='Photos/Documents')
    notes = fields.Text('Additional Notes')


class HealthTreatment(models.Model):
    _name = 'farm.health.treatment'
    _description = 'Treatment Administration'
    
    health_record_id = fields.Many2one('farm.health.record', required=True, ondelete='cascade')
    
    # Treatment Details
    treatment_date = fields.Datetime('Treatment Date', default=fields.Datetime.now)
    treatment_type = fields.Selection([
        ('medication', 'Medication'),
        ('procedure', 'Procedure'),
        ('therapy', 'Therapy'),
        ('surgery', 'Surgery')
    ])
    
    # Medication (if applicable)
    product_id = fields.Many2one('farm.medicine.product', string='Product')
    batch_number = fields.Char('Batch Number')
    quantity = fields.Float('Quantity')
    unit_id = fields.Many2one('uom.uom', string='Unit')
    route = fields.Selection([
        ('oral', 'Oral'),
        ('im', 'Intramuscular'),
        ('iv', 'Intravenous'),
        ('sc', 'Subcutaneous'),
        ('topical', 'Topical'),
        ('intramammary', 'Intramammary'),
        ('other', 'Other')
    ])
    
    # Withdrawal Periods
    withdrawal_milk_days = fields.Integer('Milk Withdrawal (days)')
    withdrawal_meat_days = fields.Integer('Meat Withdrawal (days)')
    withdrawal_milk_until = fields.Datetime('Milk Safe After', compute='_compute_withdrawal_dates')
    withdrawal_meat_until = fields.Datetime('Meat Safe After', compute='_compute_withdrawal_dates')
    
    # Administration
    administrator_id = fields.Many2one('res.users', string='Administered By')
    administration_site = fields.Char('Site of Administration')
    notes = fields.Text('Notes')
    
    @api.depends('treatment_date', 'withdrawal_milk_days', 'withdrawal_meat_days')
    def _compute_withdrawal_dates(self):
        for record in self:
            if record.treatment_date:
                base_date = fields.Datetime.from_string(record.treatment_date)
                if record.withdrawal_milk_days:
                    record.withdrawal_milk_until = base_date + timedelta(days=record.withdrawal_milk_days)
                if record.withdrawal_meat_days:
                    record.withdrawal_meat_until = base_date + timedelta(days=record.withdrawal_meat_days)
```

#### Vaccination Schedule Management

```python
class VaccinationSchedule(models.Model):
    _name = 'farm.vaccination.schedule'
    _description = 'Vaccination Schedule Template'
    
    name = fields.Char('Schedule Name', required=True)
    species = fields.Selection([
        ('cattle', 'Cattle'),
        ('goat', 'Goat'),
        ('sheep', 'Sheep'),
        ('all', 'All Species')
    ])
    
    # Schedule Lines
    line_ids = fields.One2many('farm.vaccination.schedule.line', 'schedule_id')
    
    # Applicability
    applicable_animal_types = fields.Many2many('farm.animal.type', string='Applies To')
    active = fields.Boolean('Active', default=True)


class VaccinationScheduleLine(models.Model):
    _name = 'farm.vaccination.schedule.line'
    _description = 'Vaccination Schedule Detail'
    
    schedule_id = fields.Many2one('farm.vaccination.schedule', required=True, ondelete='cascade')
    sequence = fields.Integer('Sequence')
    
    # Vaccine
    vaccine_id = fields.Many2one('farm.medicine.product', string='Vaccine', required=True)
    
    # Timing
    timing_type = fields.Selection([
        ('age_days', 'Age (Days)'),
        ('age_months', 'Age (Months)'),
        ('event', 'Event-Based'),
        ('date', 'Fixed Date'),
        ('interval', 'Interval from Previous')
    ])
    timing_value = fields.Float('Timing Value')
    timing_event = fields.Selection([
        ('birth', 'Birth'),
        ('weaning', 'Weaning'),
        ('first_service', 'First Service'),
        ('calving', 'Calving'),
        ('dry_off', 'Dry Off')
    ])
    
    # Dosing
    dose_quantity = fields.Float('Dose Quantity')
    dose_unit_id = fields.Many2one('uom.uom', string='Dose Unit')
    route = fields.Selection([
        ('oral', 'Oral'),
        ('im', 'Intramuscular'),
        ('sc', 'Subcutaneous'),
        ('intranasal', 'Intranasal')
    ])
    
    # Boosters
    requires_booster = fields.Boolean('Requires Booster')
    booster_interval_days = fields.Integer('Booster Interval (Days)')
    annual_booster = fields.Boolean('Annual Booster Required')


class VaccinationRecord(models.Model):
    _name = 'farm.vaccination.record'
    _description = 'Vaccination Administration Record'
    
    animal_id = fields.Many2one('farm.animal', required=True)
    vaccine_id = fields.Many2one('farm.medicine.product', required=True)
    
    # Administration
    administration_date = fields.Date('Date', required=True)
    administrator_id = fields.Many2one('res.users', string='Administered By')
    veterinarian_id = fields.Many2one('res.partner', string='Veterinarian')
    
    # Details
    batch_number = fields.Char('Batch Number')
    expiry_date = fields.Date('Expiry Date')
    dose_given = fields.Float('Dose Given')
    site = fields.Char('Injection Site')
    
    # Next Dose
    next_due_date = fields.Date('Next Dose Due')
    is_booster = fields.Boolean('Is Booster')
    
    # Documentation
    certificate_issued = fields.Boolean('Certificate Issued')
    certificate_number = fields.Char('Certificate Number')
```

### 2.4 Reproduction Tracking System

The Reproduction Management system provides complete lifecycle tracking from heat detection through calving and offspring registration.

#### Reproduction Workflow Architecture

```
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│                            REPRODUCTION MANAGEMENT FLOW                                      │
├─────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                             │
│   ┌─────────────────────────────────────────────────────────────────────────────────┐       │
│   │                          HEAT DETECTION MODULE                                   │       │
│   │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐             │       │
│   │  │  Behavioral │  │  Physical   │  │   Activity  │  │   Milk      │             │       │
│   │  │   Signs     │  │   Signs     │  │  Monitoring │  │  Progesterone│            │       │
│   │  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘             │       │
│   │         └─────────────────┴─────────────────┴─────────────────┘                  │       │
│   │                              │                                                   │       │
│   │                              ▼                                                   │       │
│   │                    ┌─────────────────┐                                           │       │
│   │                    │  HEAT ALERT     │                                           │       │
│   │                    │  GENERATION     │                                           │       │
│   │                    └────────┬────────┘                                           │       │
│   └─────────────────────────────┼────────────────────────────────────────────────────┘       │
│                                 │                                                           │
│                                 ▼                                                           │
│   ┌─────────────────────────────────────────────────────────────────────────────────┐       │
│   │                     ARTIFICIAL INSEMINATION MODULE                               │       │
│   │                                                                                  │       │
│   │   ┌───────────────┐    ┌───────────────┐    ┌───────────────┐                  │       │
│   │   │  AI Planning  │───▶│ Semen Select  │───▶│  AI Execution │                  │       │
│   │   │               │    │               │    │               │                  │       │
│   │   │ • Optimal     │    │ • Bull Match  │    │ • Technician  │                  │       │
│   │   │   timing      │    │ • Genetics    │    │ • Insemination│                  │       │
│   │   │ • Technician  │    │ • Inventory   │    │   details     │                  │       │
│   │   │   assignment  │    │   check       │    │ • Quality     │                  │       │
│   │   └───────────────┘    └───────────────┘    └───────┬───────┘                  │       │
│   │                                                     │                          │       │
│   └─────────────────────────────────────────────────────┼──────────────────────────┘       │
│                                                         │                                   │
│                                                         ▼                                   │
│   ┌─────────────────────────────────────────────────────────────────────────────────┐       │
│   │                     PREGNANCY MANAGEMENT MODULE                                  │       │
│   │                                                                                  │       │
│   │   ┌───────────────┐    ┌───────────────┐    ┌───────────────┐                  │       │
│   │   │ Pregnancy     │───▶│  Pregnancy    │───▶│  Gestation    │                  │       │
│   │   │ Check         │    │  Confirmation │    │  Monitoring   │                  │       │
│   │   │               │    │               │    │               │                  │       │
│   │   │ • Ultrasound  │    │ • Status      │    │ • Due date    │                  │       │
│   │   │ • Palpation   │    │   update      │    │   calc        │                  │       │
│   │   │ • Blood test  │    │ • Breeding    │    │ • Health      │                  │       │
│   │   │               │    │   value       │    │   monitoring  │                  │       │
│   │   └───────────────┘    └───────────────┘    └───────┬───────┘                  │       │
│   │                                                     │                          │       │
│   └─────────────────────────────────────────────────────┼──────────────────────────┘       │
│                                                         │                                   │
│                                                         ▼                                   │
│   ┌─────────────────────────────────────────────────────────────────────────────────┐       │
│   │                        CALVING MANAGEMENT MODULE                                 │       │
│   │                                                                                  │       │
│   │   ┌───────────────┐    ┌───────────────┐    ┌───────────────┐                  │       │
│   │   │  Calving      │───▶│  Offspring    │───▶│   Dam         │                  │       │
│   │   │  Event        │    │  Registration │    │   Recovery    │                  │       │
│   │   │               │    │               │    │               │                  │       │
│   │   │ • Date/Time   │    │ • Calf details│    │ • Health      │                  │       │
│   │   │ • Ease score  │    │ • Parentage   │    │   check       │                  │       │
│   │   │ • Assistance  │    │ • Colostrum   │    │ • Lactation   │                  │       │
│   │   │   level       │    │   protocol    │    │   start       │                  │       │
│   │   └───────────────┘    └───────────────┘    └───────────────┘                  │       │
│   │                                                                                  │       │
│   └──────────────────────────────────────────────────────────────────────────────────┘       │
│                                                                                             │
└─────────────────────────────────────────────────────────────────────────────────────────────┘
```

#### Reproduction Models

```python
class ReproductionEvent(models.Model):
    _name = 'farm.reproduction.event'
    _description = 'Reproduction Event Record'
    _inherit = ['mail.thread']
    
    animal_id = fields.Many2one('farm.animal', required=True, tracking=True)
    event_type = fields.Selection([
        ('heat', 'Heat/Oestrus'),
        ('ai', 'Artificial Insemination'),
        ('natural_service', 'Natural Service'),
        ('pregnancy_check', 'Pregnancy Check'),
        ('calving', 'Calving'),
        ('abortion', 'Abortion'),
        ('embryo_transfer', 'Embryo Transfer')
    ], required=True, tracking=True)
    
    event_date = fields.Datetime('Event Date', required=True, tracking=True)
    status = fields.Selection([
        ('pending', 'Pending'),
        ('confirmed', 'Confirmed'),
        ('completed', 'Completed'),
        ('cancelled', 'Cancelled')
    ], default='pending', tracking=True)
    
    # For Heat Events
    heat_signs = fields.Many2many('farm.heat.sign', string='Heat Signs Observed')
    heat_intensity = fields.Selection([
        ('weak', 'Weak'),
        ('moderate', 'Moderate'),
        ('strong', 'Strong'),
        ('very_strong', 'Very Strong')
    ])
    
    # For AI/Service Events
    sire_id = fields.Many2one('farm.animal', string='Sire (Natural)')
    semen_id = fields.Many2one('farm.semen.straw', string='Semen Straw')
    technician_id = fields.Many2one('res.users', string='Technician')
    insemination_score = fields.Selection([
        ('1', '1 - Difficult'),
        ('2', '2 - Fair'),
        ('3', '3 - Good'),
        ('4', '4 - Excellent')
    ], string='Insemination Score')
    
    # For Pregnancy Checks
    pregnancy_status = fields.Selection([
        ('pregnant', 'Pregnant'),
        ('not_pregnant', 'Not Pregnant'),
        ('doubtful', 'Doubtful'),
        ('early_embryonic_death', 'Early Embryonic Death')
    ])
    pregnancy_check_method = fields.Selection([
        ('ultrasound', 'Ultrasound'),
        ('palpation', 'Rectal Palpation'),
        ('blood_test', 'Blood Test (PAG)'),
        ('milk_test', 'Milk Test')
    ])
    days_pregnant = fields.Integer('Days Pregnant')
    expected_calving_date = fields.Date('Expected Calving Date')
    
    # For Calving Events
    calving_ease = fields.Selection([
        ('1', '1 - No Assistance'),
        ('2', '2 - Slight Assistance'),
        ('3', '3 - Needed Traction'),
        ('4', '4 - Considerable Force'),
        ('5', '5 - Cesarean Section')
    ])
    calf_ids = fields.One2many('farm.animal', 'birth_event_id', string='Calves Born')
    number_of_calves = fields.Integer('Number of Calves', compute='_compute_calf_count')
    
    # Calculated Fields
    days_since_last_event = fields.Integer('Days Since Last Event', compute='_compute_intervals')
    gestation_length = fields.Integer('Gestation Length (days)', compute='_compute_gestation')


class HeatDetectionAlert(models.Model):
    _name = 'farm.heat.detection.alert'
    _description = 'Heat Detection Alert'
    
    animal_id = fields.Many2one('farm.animal', required=True)
    alert_date = fields.Datetime('Alert Generated', default=fields.Datetime.now)
    expected_heat_date = fields.Date('Expected Heat Date')
    
    # Detection Method
    detection_sources = fields.Many2many('farm.heat.source', string='Detection Sources')
    confidence_score = fields.Float('Confidence Score (0-100)')
    
    # Recommendation
    recommended_action = fields.Selection([
        ('observe', 'Continue Observation'),
        ('ai_ready', 'Ready for AI'),
        ('ai_soon', 'AI within 12 hours'),
        ('check_vet', 'Veterinary Check')
    ])
    
    # Status
    status = fields.Selection([
        ('active', 'Active'),
        ('confirmed', 'Heat Confirmed'),
        ('false_positive', 'False Positive'),
        ('expired', 'Expired')
    ], default='active')
    
    # Action Taken
    action_date = fields.Datetime('Action Date')
    action_taken = fields.Text('Action Taken')
    action_by = fields.Many2one('res.users', string='Action By')


class SemenInventory(models.Model):
    _name = 'farm.semen.straw'
    _description = 'Semen Straw Inventory'
    
    name = fields.Char('Straw ID', required=True)
    
    # Bull Information
    bull_id = fields.Many2one('farm.animal', string='Bull', domain=[('animal_type', '=', 'bull')])
    bull_registration = fields.Char('Bull Registration Number')
    bull_name = fields.Char('Bull Name')
    
    # Genetics
    breed_id = fields.Many2one('farm.breed', string='Breed')
    
    # Straw Details
    collection_date = fields.Date('Collection Date')
    batch_code = fields.Char('Batch Code')
    straw_volume = fields.Float('Volume (ml)')
    concentration = fields.Float('Concentration (million/ml)')
    total_sperm = fields.Float('Total Sperm (million)', compute='_compute_total_sperm')
    motility_pct = fields.Float('Motility %')
    morphology_pct = fields.Float('Normal Morphology %')
    
    # Storage
    tank_id = fields.Many2one('farm.semen.tank', string='Storage Tank')
    canister = fields.Char('Canister')
    cane = fields.Char('Cane')
    position = fields.Char('Position')
    
    # Status
    status = fields.Selection([
        ('available', 'Available'),
        ('reserved', 'Reserved'),
        ('used', 'Used'),
        ('discarded', 'Discarded'),
        ('expired', 'Expired')
    ], default='available')
    
    # Usage
    usage_date = fields.Date('Usage Date')
    used_for_animal_id = fields.Many2one('farm.animal', string='Used For')
    technician_id = fields.Many2one('res.users', string='Technician')
```

### 2.5 Milk Production Pipeline

The Milk Production system captures, validates, and analyzes daily milk production data with comprehensive quality tracking.

#### Milk Production Architecture

```
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│                              MILK PRODUCTION PIPELINE                                        │
├─────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                             │
│  ┌─────────────────────────────────────────────────────────────────────────────────────┐    │
│  │                              DATA CAPTURE LAYER                                      │    │
│  │                                                                                      │    │
│  │   ┌───────────────┐   ┌───────────────┐   ┌───────────────┐   ┌───────────────┐    │    │
│  │   │  Manual Entry │   │  RFID Auto    │   │  Milk Meter   │   │  Mobile App   │    │    │
│  │   │               │   │  Capture      │   │  Integration  │   │  Scanner      │    │    │
│  │   │ • Web form    │   │ • Auto ID     │   │ • Real-time   │   │ • Offline     │    │    │
│  │   │ • Validation  │   │ • Weight      │   │   data        │   │   sync        │    │    │
│  │   │ • Error check │   │ • Timestamp   │   │ • Precision   │   │ • Voice       │    │    │
│  │   └───────┬───────┘   └───────┬───────┘   └───────┬───────┘   └───────┬───────┘    │    │
│  │           └───────────────────┴───────────────────┴───────────────────┘              │    │
│  │                                   │                                                  │    │
│  └───────────────────────────────────┼──────────────────────────────────────────────────┘    │
│                                      │                                                      │
│                                      ▼                                                      │
│  ┌─────────────────────────────────────────────────────────────────────────────────────┐    │
│  │                            VALIDATION & PROCESSING LAYER                             │    │
│  │                                                                                      │    │
│  │   ┌──────────────────────────────────────────────────────────────────────────┐      │    │
│  │   │                       DATA VALIDATION ENGINE                              │      │    │
│  │   │  • Range checks (min/max yield)  • Animal state validation              │      │    │
│  │   │  • Time consistency              • Duplicate detection                  │      │    │
│  │   │  • Quality parameter ranges      • Flag anomalies                       │      │    │
│  │   └─────────────────────────────────┬────────────────────────────────────────┘      │    │
│  │                                     │                                               │    │
│  │                                     ▼                                               │    │
│  │   ┌──────────────────────────────────────────────────────────────────────────┐      │    │
│  │   │                      DATA ENRICHMENT ENGINE                               │      │    │
│  │   │  • Calculate 305-day projection  • Rolling averages                       │      │    │
│  │   │  • Lactation curves            • Comparative rankings                     │      │    │
│  │   │  • Fat/Protein ratios          • SCC trends                               │      │    │
│  │   └─────────────────────────────────┬────────────────────────────────────────┘      │    │
│  │                                     │                                               │    │
│  └─────────────────────────────────────┼───────────────────────────────────────────────┘    │
│                                        │                                                    │
│                                        ▼                                                    │
│  ┌─────────────────────────────────────────────────────────────────────────────────────┐    │
│  │                           ANALYTICS & REPORTING LAYER                                │    │
│  │                                                                                      │    │
│  │   ┌───────────────┐   ┌───────────────┐   ┌───────────────┐   ┌───────────────┐    │    │
│  │   │  Individual   │   │  Group/Herd   │   │  Predictive   │   │  Financial    │    │    │
│  │   │  Analytics    │   │  Analytics    │   │  Models       │   │  Analysis     │    │    │
│  │   │               │   │               │   │               │   │               │    │    │
│  │   │ • Lactation   │   │ • Daily total │   │ • Yield       │   │ • Revenue     │    │    │
│  │   │   curves      │   │ • Tank totals │   │   forecast    │   │   per cow     │    │    │
│  │   │ • Peak yield  │   │ • Trend       │   │ • Persistency │   │ • Component   │    │    │
│  │   │ • Persistency │   │   analysis    │   │   prediction  │   │   values      │    │    │
│  │   └───────────────┘   └───────────────┘   └───────────────┘   └───────────────┘    │    │
│  │                                                                                      │    │
│  └─────────────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                             │
└─────────────────────────────────────────────────────────────────────────────────────────────┘
```

#### Milk Production Models

```python
class MilkProductionRecord(models.Model):
    _name = 'farm.milk.production'
    _description = 'Milk Production Record'
    
    # Identification
    animal_id = fields.Many2one('farm.animal', required=True, index=True)
    milking_date = fields.Date('Milking Date', required=True, index=True)
    milking_number = fields.Integer('Milking Number', help='1 for AM, 2 for PM, etc.')
    
    # Production Data
    milk_yield_kg = fields.Float('Milk Yield (kg)', digits=(8, 2))
    milk_yield_l = fields.Float('Milk Yield (L)', compute='_compute_volume')
    
    # Quality Parameters
    fat_percentage = fields.Float('Fat %', digits=(4, 2))
    protein_percentage = fields.Float('Protein %', digits=(4, 2))
    lactose_percentage = fields.Float('Lactose %', digits=(4, 2))
    solids_non_fat = fields.Float('SNF %', digits=(4, 2))
    total_solids = fields.Float('Total Solids %', compute='_compute_total_solids')
    
    # Somatic Cell Count
    somatic_cell_count = fields.Integer('SCC (cells/ml)')
    somatic_cell_score = fields.Integer('SCS', compute='_compute_scs')
    scc_category = fields.Selection([
        ('low', 'Low (<100k)'),
        ('medium', 'Medium (100-200k)'),
        ('high', 'High (200-400k)'),
        ('very_high', 'Very High (>400k)')
    ], compute='_compute_scc_category')
    
    # Additional Parameters
    urea_mg_dl = fields.Float('Milk Urea (mg/dl)')
    freezing_point = fields.Float('Freezing Point (°C)')
    conductivity = fields.Float('Conductivity')
    
    # Collection Info
    collector_id = fields.Many2one('res.users', string='Recorded By')
    collection_method = fields.Selection([
        ('manual', 'Manual'),
        ('auto_meter', 'Automatic Meter'),
        ('rfid_auto', 'RFID Auto')
    ])
    
    # Validation
    validated = fields.Boolean('Validated', default=False)
    validation_notes = fields.Text('Validation Notes')
    
    # Computed Lactation Data
    days_in_milk = fields.Integer('DIM', compute='_compute_dim')
    lactation_number = fields.Integer('Lactation', related='animal_id.lactation_number')
    
    # Flags
    is_peak = fields.Boolean('Is Peak Yield', compute='_compute_peak')
    anomaly_flag = fields.Boolean('Anomaly Detected', compute='_compute_anomaly')


class LactationPeriod(models.Model):
    _name = 'farm.lactation.period'
    _description = 'Lactation Period Record'
    
    animal_id = fields.Many2one('farm.animal', required=True)
    lactation_number = fields.Integer('Lactation Number', required=True)
    
    # Dates
    calving_date = fields.Date('Calving Date', required=True)
    dry_date = fields.Date('Dry Date')
    end_date = fields.Date('End Date')
    
    # Duration
    days_in_milk = fields.Integer('Days in Milk', compute='_compute_duration')
    days_dry = fields.Integer('Days Dry', compute='_compute_duration')
    
    # Production Totals
    total_milk_kg = fields.Float('Total Milk (kg)', compute='_compute_totals')
    milk_305d_kg = fields.Float('305-day Milk (kg)', compute='_compute_305')
    fat_total_kg = fields.Float('Total Fat (kg)', compute='_compute_totals')
    protein_total_kg = fields.Float('Total Protein (kg)', compute='_compute_totals')
    
    # Peak Metrics
    peak_yield_kg = fields.Float('Peak Yield (kg/day)')
    peak_day = fields.Integer('Peak Day')
    
    # Averages
    avg_fat_pct = fields.Float('Average Fat %')
    avg_protein_pct = fields.Float('Average Protein %')
    avg_scc = fields.Float('Average SCC')
    
    # Persistency
    persistency_ratio = fields.Float('Persistency Ratio', compute='_compute_persistency')
    
    # Status
    status = fields.Selection([
        ('in_progress', 'In Progress'),
        ('dried_off', 'Dried Off'),
        ('completed', 'Completed')
    ], compute='_compute_status')
```

### 2.6 Mobile App Architecture

The Smart Dairy Farm Mobile App is built using Flutter framework with an offline-first architecture to ensure reliable operation in farm environments with intermittent connectivity.

#### Mobile App Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│                           SMART DAIRY FARM MOBILE APP                                        │
│                              (Flutter - Offline First)                                       │
├─────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                             │
│  ┌─────────────────────────────────────────────────────────────────────────────────────┐    │
│  │                              PRESENTATION LAYER                                      │    │
│  │                                                                                      │    │
│  │   ┌───────────────┐   ┌───────────────┐   ┌───────────────┐   ┌───────────────┐    │    │
│  │   │    Animal     │   │    Health     │   │   Reproduction│   │   Milk        │    │    │
│  │   │  Management   │   │  Management   │   │   Tracking    │   │   Recording   │    │    │
│  │   │               │   │               │   │               │   │               │    │    │
│  │   │ • Animal list │   │ • Case entry  │   │ • Heat check  │   │ • Yield entry │    │    │
│  │   │ • Detail view │   │ • Treatment   │   │ • AI record   │   │ • Quality     │    │    │
│  │   │ • Search/Scan │   │ • Vaccination │   │ • Calving     │   │   input       │    │    │
│  │   └───────┬───────┘   └───────┬───────┘   └───────┬───────┘   └───────┬───────┘    │    │
│  │           │                   │                   │                   │            │    │
│  │           └───────────────────┴───────────────────┴───────────────────┘            │    │
│  │                                   │                                                │    │
│  │                                   ▼                                                │    │
│  │   ┌──────────────────────────────────────────────────────────────────────────┐     │    │
│  │   │                      UI STATE MANAGEMENT (BLoC)                           │     │    │
│  │   │  • AnimalBloc  • HealthBloc  • ReproductionBloc  • MilkBloc              │     │    │
│  │   └─────────────────────────────────┬────────────────────────────────────────┘     │    │
│  │                                     │                                              │    │
│  └─────────────────────────────────────┼──────────────────────────────────────────────┘    │
│                                        │                                                    │
│  ┌─────────────────────────────────────┼───────────────────────────────────────────────┐    │
│  │                              DOMAIN LAYER                                            │    │
│  │                                     │                                                │    │
│  │                                     ▼                                                │    │
│  │   ┌──────────────────────────────────────────────────────────────────────────┐     │    │
│  │   │                      REPOSITORY PATTERN                                   │     │    │
│  │   │                                                                             │     │    │
│  │   │  ┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐       │     │    │
│  │   │  │  AnimalRepo     │    │  HealthRepo     │    │  MilkRepo       │       │     │    │
│  │   │  │  Interface      │    │  Interface      │    │  Interface      │       │     │    │
│  │   │  └────────┬────────┘    └────────┬────────┘    └────────┬────────┘       │     │    │
│  │   │           │                      │                      │                  │     │    │
│  │   │           └──────────────────────┼──────────────────────┘                  │     │    │
│  │   │                                  │                                         │     │    │
│  │   │                    ┌─────────────┴─────────────┐                           │     │    │
│  │   │                    ▼                           ▼                           │     │    │
│  │   │           ┌─────────────────┐    ┌─────────────────┐                       │     │    │
│  │   │           │  Local Repo     │    │  Remote Repo    │                       │     │    │
│  │   │           │  (SQLite)       │    │  (REST API)     │                       │     │    │
│  │   │           └─────────────────┘    └─────────────────┘                       │     │    │
│  │   └──────────────────────────────────────────────────────────────────────────┘     │    │
│  │                                                                                      │    │
│  └─────────────────────────────────────────────────────────────────────────────────────┘    │
│                                        │                                                    │
│  ┌─────────────────────────────────────┼───────────────────────────────────────────────┐    │
│  │                           INFRASTRUCTURE LAYER                                       │    │
│  │                                     │                                                │    │
│  │                                     ▼                                                │    │
│  │   ┌──────────────────────────────────────────────────────────────────────────┐     │    │
│  │   │                      DATA SOURCES                                           │     │    │
│  │   │                                                                             │     │    │
│  │   │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐           │     │    │
│  │   │  │  SQLite DB      │  │  REST API       │  │  RFID Scanner   │           │     │    │
│  │   │  │  (sqflite)      │  │  (dio/http)     │  │  (native)       │           │     │    │
│  │   │  └─────────────────┘  └─────────────────┘  └─────────────────┘           │     │    │
│  │   │                                                                             │     │    │
│  │   │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐           │     │    │
│  │   │  │  Sync Engine    │  │  Connectivity   │  │  Local Storage  │           │     │    │
│  │   │  │  (offline-first)│  │  Monitor        │  │  (shared_prefs) │           │     │    │
│  │   │  └─────────────────┘  └─────────────────┘  └─────────────────┘           │     │    │
│  │   └──────────────────────────────────────────────────────────────────────────┘     │    │
│  │                                                                                      │    │
│  └─────────────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                             │
└─────────────────────────────────────────────────────────────────────────────────────────────┘
```

#### Mobile App Core Components

```dart
// Main App Structure (Flutter)
// lib/main.dart

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:smart_dairy_farm/app.dart';
import 'package:smart_dairy_farm/blocs/animal/animal_bloc.dart';
import 'package:smart_dairy_farm/blocs/health/health_bloc.dart';
import 'package:smart_dairy_farm/blocs/milk/milk_bloc.dart';
import 'package:smart_dairy_farm/blocs/reproduction/reproduction_bloc.dart';
import 'package:smart_dairy_farm/blocs/sync/sync_bloc.dart';
import 'package:smart_dairy_farm/repositories/animal_repository.dart';
import 'package:smart_dairy_farm/repositories/health_repository.dart';
import 'package:smart_dairy_farm/repositories/milk_repository.dart';
import 'package:smart_dairy_farm/repositories/reproduction_repository.dart';
import 'package:smart_dairy_farm/services/connectivity_service.dart';
import 'package:smart_dairy_farm/services/database_service.dart';
import 'package:smart_dairy_farm/services/api_service.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  
  // Initialize services
  final databaseService = DatabaseService();
  final apiService = ApiService();
  final connectivityService = ConnectivityService();
  
  await databaseService.initialize();
  await connectivityService.initialize();
  
  runApp(SmartDairyFarmApp(
    databaseService: databaseService,
    apiService: apiService,
    connectivityService: connectivityService,
  ));
}

class SmartDairyFarmApp extends StatelessWidget {
  final DatabaseService databaseService;
  final ApiService apiService;
  final ConnectivityService connectivityService;
  
  const SmartDairyFarmApp({
    Key? key,
    required this.databaseService,
    required this.apiService,
    required this.connectivityService,
  }) : super(key: key);
  
  @override
  Widget build(BuildContext context) {
    return MultiRepositoryProvider(
      providers: [
        RepositoryProvider<AnimalRepository>(
          create: (_) => AnimalRepository(
            databaseService: databaseService,
            apiService: apiService,
          ),
        ),
        RepositoryProvider<HealthRepository>(
          create: (_) => HealthRepository(
            databaseService: databaseService,
            apiService: apiService,
          ),
        ),
        RepositoryProvider<MilkRepository>(
          create: (_) => MilkRepository(
            databaseService: databaseService,
            apiService: apiService,
          ),
        ),
        RepositoryProvider<ReproductionRepository>(
          create: (_) => ReproductionRepository(
            databaseService: databaseService,
            apiService: apiService,
          ),
        ),
      ],
      child: MultiBlocProvider(
        providers: [
          BlocProvider<SyncBloc>(
            create: (context) => SyncBloc(
              connectivityService: connectivityService,
              databaseService: databaseService,
              apiService: apiService,
            )..add(const SyncEvent.initialize()),
          ),
          BlocProvider<AnimalBloc>(
            create: (context) => AnimalBloc(
              repository: context.read<AnimalRepository>(),
            ),
          ),
          BlocProvider<HealthBloc>(
            create: (context) => HealthBloc(
              repository: context.read<HealthRepository>(),
            ),
          ),
          BlocProvider<MilkBloc>(
            create: (context) => MilkBloc(
              repository: context.read<MilkRepository>(),
            ),
          ),
          BlocProvider<ReproductionBloc>(
            create: (context) => ReproductionBloc(
              repository: context.read<ReproductionRepository>(),
            ),
          ),
        ],
        child: MaterialApp(
          title: 'Smart Dairy Farm',
          debugShowCheckedModeBanner: false,
          theme: AppTheme.lightTheme,
          darkTheme: AppTheme.darkTheme,
          themeMode: ThemeMode.system,
          home: const SplashScreen(),
          routes: AppRoutes.routes,
        ),
      ),
    );
  }
}
```

---

## 3. Daily Task Allocation

### 3.1 Development Team Structure

| Role | Name | Primary Focus | Secondary Focus |
|------|------|---------------|-----------------|
| Developer 1 (Lead Dev - Farm Systems) | Senior Farm Architect | System architecture, genetics tracking, integration design | Performance analytics, workflow engine |
| Developer 2 (Backend Dev - Farm Logic) | Backend Specialist | Animal models, breeding logic, milk calculations, feed algorithms | Health records, API design |
| Developer 3 (Frontend/Mobile Dev) | Mobile/UI Developer | Flutter app, RFID interface, data entry forms | Dashboard visualizations, offline sync |

### 3.2 Day 321 (Monday) - Foundation & Architecture

#### Developer 1 (Lead Dev - Farm Systems) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Herd Management Architecture Design | Design core animal master data model with complete field specifications, inheritance patterns, and relationship mappings | Architecture document with ERD |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Genetics Tracking System Design | Design pedigree calculation engine, inbreeding coefficient algorithms, relationship matrix storage, and EBV integration patterns | Technical specification document |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Performance Analytics Architecture | Design performance metrics calculation engine, lactation curve modeling, and percentile ranking systems | Analytics architecture doc |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Integration Design Session | Design integration patterns between herd management and other modules (health, reproduction, milk production), define event bus architecture, and message queue specifications | Integration specification |

**End of Day Deliverables:**
1. Complete Herd Management System Architecture Document (5+ pages)
2. Genetics Tracking System Technical Specification (3+ pages)
3. Performance Analytics Architecture Diagram
4. Integration Pattern Document with sequence diagrams

**Code Commitments:**
```
- Create farm_animal/__manifest__.py with complete module definition
- Create models/animal.py with base structure
- Create models/genetics.py with inheritance calculation stub
- Create models/performance.py with metric definitions
```

---

#### Developer 2 (Backend Dev - Farm Logic) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Animal Model Implementation | Implement FarmAnimal model with all identification fields, status tracking, lifecycle management, and computed fields (age, lactation number) | Working animal model with 50+ fields |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Location and Group Management | Implement FarmLocation and FarmAnimalGroup models with hierarchy support, capacity tracking, and movement history | Location/group models complete |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Breeding Logic Implementation | Implement breeding-related computed fields, parentage validation, breeding value calculations, and animal type determination logic | Breeding logic functions |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Milk Production Calculations | Implement milk yield calculations, component value computations (fat, protein, lactose), SCC scoring, and basic lactation metrics | Milk calculation utilities |

**End of Day Deliverables:**
1. Complete FarmAnimal model with 50+ fields and computed properties
2. FarmLocation model with hierarchical structure
3. FarmAnimalGroup model with capacity management
4. Initial breeding calculation methods
5. Milk production calculation utilities

**Code Commitments:**
```python
# Models created:
- farm.animal (50+ fields)
- farm.location (hierarchical)
- farm.animal.group
- farm.breed

# Methods implemented:
- _compute_age()
- _compute_lactation_number()
- _compute_days_in_milk()
- _validate_parentage()
- calculate_milk_components()
```

---

#### Developer 3 (Frontend/Mobile Dev) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Flutter Project Setup | Initialize Flutter project with clean architecture, setup BLoC pattern, configure dependency injection, and establish folder structure | Project skeleton ready |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Offline Database Setup | Implement SQLite database schema using sqflite, create entity models, DAO interfaces, and database helper classes | Local database layer ready |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Repository Pattern Implementation | Implement repository pattern with local and remote data sources, create abstract repository interfaces for Animal, Health, Milk, and Reproduction | Repository layer complete |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Connectivity Service | Implement connectivity monitoring service, sync queue management, and conflict resolution strategies for offline-first architecture | Sync infrastructure ready |

**End of Day Deliverables:**
1. Flutter project with clean architecture structure
2. SQLite database with tables for animals, health records, milk records
3. Repository pattern implementation with local/remote sources
4. Connectivity service with sync queue
5. BLoC state management setup

**Code Commitments:**
```dart
// Created:
- lib/models/animal.dart
- lib/models/health_record.dart
- lib/models/milk_record.dart
- lib/data/database/database_helper.dart
- lib/data/repositories/animal_repository.dart
- lib/services/connectivity_service.dart
- lib/blocs/animal/animal_bloc.dart
```

---

### 3.3 Day 322 (Tuesday) - Genetics & Health Foundation

#### Developer 1 (Lead Dev - Farm Systems) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Pedigree Calculation Algorithm Design | Design recursive pedigree traversal algorithm, common ancestor detection, path coefficient calculation methodology | Algorithm specification document |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Inbreeding Coefficient Implementation | Implement Wright's path coefficient method for inbreeding calculation, optimize for performance with caching, handle up to 10 generations | Working inbreeding calculator |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | EBV Integration Design | Design Estimated Breeding Value integration from external sources (DHIA, breed associations), data import formats, and storage optimization | EBV integration spec |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Health Management Workflow Design | Design comprehensive veterinary workflow from detection through follow-up, define health event types, severity classifications, and notification rules | Health workflow document |

**End of Day Deliverables:**
1. Pedigree calculation algorithm implementation (Python)
2. Inbreeding coefficient calculator with test cases
3. EBV integration specification document
4. Health management workflow design with state transitions

**Code Commitments:**
```python
# Implemented:
class GeneticsCalculator:
    def calculate_inbreeding_coefficient(animal_id, generations=5):
        # Wright's path coefficient implementation
        pass
    
    def find_common_ancestors(sire_ancestors, dam_ancestors):
        # Common ancestor detection
        pass
    
    def compute_relationship_matrix(animal_ids):
        # Relationship coefficient matrix
        pass

class EBVImporter:
    def parse_dhia_format(file_data):
        pass
    
    def validate_ebv_values(values):
        pass
```

---

#### Developer 2 (Backend Dev - Farm Logic) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Health Record Model | Implement comprehensive HealthRecord model with all examination fields, diagnosis tracking, treatment planning, and outcome monitoring | Complete health record model |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Treatment Administration Model | Implement HealthTreatment model with medication tracking, withdrawal period calculations, dosage validation, and administration logging | Treatment model complete |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Vaccination Schedule System | Implement VaccinationSchedule and VaccinationScheduleLine models with age-based and event-based triggering, booster tracking, and completion monitoring | Vaccination system ready |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Health Alert Engine Foundation | Implement basic health alert engine with rule-based detection, alert generation, and notification dispatch framework | Alert engine foundation |

**End of Day Deliverables:**
1. HealthRecord model with 30+ fields
2. HealthTreatment model with withdrawal calculations
3. VaccinationSchedule and VaccinationRecord models
4. Basic health alert rule engine

**Code Commitments:**
```python
# Models implemented:
- farm.health.record (30+ fields)
- farm.health.treatment (with withdrawal calculations)
- farm.vaccination.schedule
- farm.vaccination.schedule.line
- farm.vaccination.record

# Methods:
- calculate_withdrawal_dates()
- check_vaccination_due()
- generate_health_alerts()
```

---

#### Developer 3 (Frontend/Mobile Dev) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Animal List Screen | Implement animal list screen with search, filter, sort capabilities, lazy loading for large herds, and pull-to-refresh functionality | Animal list screen |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Animal Detail Screen | Implement animal detail screen with tabs for overview, health history, reproduction history, and milk production | Animal detail screen |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Health Entry Forms | Implement health record entry forms with symptom selection, diagnosis input, treatment planning, and photo attachment capability | Health entry forms |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Voice Input Integration | Implement voice-to-text functionality for health notes using speech-to-text packages, with offline capability | Voice input feature |

**End of Day Deliverables:**
1. Animal list screen with search/filter
2. Animal detail screen with tabbed interface
3. Health record entry forms
4. Voice input integration for notes

**Code Commitments:**
```dart
// Created:
- lib/ui/screens/animal/animal_list_screen.dart
- lib/ui/screens/animal/animal_detail_screen.dart
- lib/ui/screens/health/health_entry_screen.dart
- lib/ui/widgets/animal/animal_card.dart
- lib/ui/widgets/health/symptom_selector.dart
- lib/services/voice_service.dart
```

---

### 3.4 Day 323 (Wednesday) - Reproduction & Milk Production

#### Developer 1 (Lead Dev - Farm Systems) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Reproduction System Architecture | Design comprehensive reproduction tracking system covering heat detection, AI management, pregnancy checking, and calving event workflows | Reproduction architecture doc |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Heat Detection Algorithm Design | Design multi-factor heat detection algorithm combining behavioral signs, activity data, milk progesterone, and temperature patterns | Heat detection algorithm |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Gestation Calculation Logic | Implement gestation length calculation with breed-specific adjustments, calving prediction with confidence intervals, and due date alerts | Gestation calculator |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Milk Production Analysis Design | Design lactation curve modeling, 305-day projection calculations, persistency metrics, and comparative percentile rankings | Milk analysis spec |

**End of Day Deliverables:**
1. Reproduction system architecture document
2. Heat detection algorithm with weighting factors
3. Gestation calculation engine with breed adjustments
4. Milk production analysis specification

**Code Commitments:**
```python
# Implemented:
class HeatDetectionEngine:
    def calculate_heat_probability(animal_id, factors):
        # Multi-factor scoring
        behavioral_score = self.score_behavioral_signs(...)
        activity_score = self.score_activity_data(...)
        progesterone_score = self.score_milk_progesterone(...)
        return weighted_average([behavioral_score, activity_score, progesterone_score])

class GestationCalculator:
    BREED_GESTATION_DAYS = {
        'holstein': 279,
        'jersey': 278,
        'guernsey': 283,
        # ...
    }
    
    def calculate_expected_calving_date(service_date, breed, confidence=0.95):
        pass
```

---

#### Developer 2 (Backend Dev - Farm Logic) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Reproduction Event Model | Implement ReproductionEvent model with all event types (heat, AI, pregnancy check, calving), status tracking, and computed intervals | Reproduction event model |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Semen Inventory Model | Implement SemenInventory model with bull genetics tracking, storage location management, quality parameters, and usage tracking | Semen inventory model |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Milk Production Models | Implement MilkProductionRecord and LactationPeriod models with quality parameters, yield calculations, and lactation curve data | Milk production models |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Milk Validation Engine | Implement milk data validation rules, anomaly detection based on historical patterns, and quality threshold alerts | Milk validation engine |

**End of Day Deliverables:**
1. ReproductionEvent model with all event types
2. SemenInventory model with storage tracking
3. MilkProductionRecord model with 20+ fields
4. LactationPeriod model with curve calculations
5. Milk data validation rules

**Code Commitments:**
```python
# Models implemented:
- farm.reproduction.event (all event types)
- farm.heat.detection.alert
- farm.semen.straw
- farm.semen.tank
- farm.milk.production (20+ fields)
- farm.lactation.period

# Methods:
- validate_milk_yield()
- detect_milk_anomaly()
- calculate_lactation_curve()
- compute_305_day_projection()
```

---

#### Developer 3 (Frontend/Mobile Dev) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Reproduction Entry Screens | Implement screens for heat detection entry, AI recording, pregnancy check input, and calving event registration | Reproduction screens |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Milk Recording Interface | Implement milk yield entry screen with quick input modes, quality parameter input, and validation feedback | Milk recording screen |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Dashboard Widgets | Implement dashboard widgets for key farm metrics: total milk production, animals in heat, pending health checks, upcoming calvings | Dashboard widgets |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Data Visualization Charts | Implement charts for milk production trends, lactation curves, and reproduction statistics using fl_chart package | Chart components |

**End of Day Deliverables:**
1. Reproduction entry screens (heat, AI, pregnancy, calving)
2. Milk recording interface with validation
3. Dashboard widgets for key metrics
4. Chart components for data visualization

**Code Commitments:**
```dart
// Created:
- lib/ui/screens/reproduction/heat_entry_screen.dart
- lib/ui/screens/reproduction/ai_entry_screen.dart
- lib/ui/screens/reproduction/calving_entry_screen.dart
- lib/ui/screens/milk/milk_entry_screen.dart
- lib/ui/widgets/dashboard/milk_production_card.dart
- lib/ui/widgets/dashboard/heat_alert_card.dart
- lib/ui/widgets/charts/lactation_curve_chart.dart
- lib/ui/widgets/charts/milk_trend_chart.dart
```

---

### 3.5 Day 324 (Thursday) - Feed Management & Algorithms

#### Developer 1 (Lead Dev - Farm Systems) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Feed Management Architecture | Design Total Mixed Ration (TMR) calculation system, nutritional requirement models, and feed conversion efficiency tracking | Feed system architecture |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Nutritional Requirement Calculations | Implement NRC-based nutritional requirement calculations for different animal categories (lactating, dry, growing) | Nutritional calculator |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Feed Conversion Algorithms | Design feed conversion ratio calculations, feed efficiency benchmarking, and cost optimization algorithms | Feed conversion logic |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Mobile Sync Architecture | Design mobile synchronization protocol, conflict resolution strategies, and offline data retention policies | Sync architecture doc |

**End of Day Deliverables:**
1. Feed management system architecture
2. Nutritional requirement calculation engine
3. Feed conversion ratio algorithms
4. Mobile sync architecture specification

**Code Commitments:**
```python
# Implemented:
class NutritionalCalculator:
    def calculate_requirements(animal, production_level, body_weight):
        # NRC 2001 based calculations
        requirements = {
            'nem': self.calculate_nem(body_weight),
            'neg': self.calculate_neg(body_weight, adg),
            'nel': self.calculate_nel(milk_yield, fat_pct),
            'cp': self.calculate_cp(ruminal_microbial_protein, ...),
            'ndf': self.calculate_ndf(dmi, ...),
            # ...
        }
        return requirements

class FeedConversionCalculator:
    def calculate_fcr(feed_intake, milk_yield, component_corrected=True):
        if component_corrected:
            return feed_intake / self.component_corrected_milk(milk_yield, fat, protein)
        return feed_intake / milk_yield
```

---

#### Developer 2 (Backend Dev - Farm Logic) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Feed Management Models | Implement Feed, FeedIngredient, and FeedRation models with nutritional composition tracking and cost management | Feed models |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | TMR Calculation Engine | Implement TMR calculation logic with ingredient optimization, nutritional balancing, and batch sizing | TMR calculator |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Feed Intake Tracking | Implement daily feed intake recording, feed conversion calculations, and efficiency trend analysis | Feed intake system |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | API Design - Farm Management | Design and implement REST API endpoints for farm management operations with proper authentication and validation | Farm management APIs |

**End of Day Deliverables:**
1. Feed, FeedIngredient, FeedRation models
2. TMR calculation engine
3. Feed intake tracking system
4. REST API endpoints for farm operations

**Code Commitments:**
```python
# Models implemented:
- farm.feed
- farm.feed.ingredient
- farm.feed.ration
- farm.feed.intake

# APIs implemented:
- GET /api/farm/animals
- POST /api/farm/animals
- GET /api/farm/animals/{id}
- PUT /api/farm/animals/{id}
- GET /api/farm/health-records
- POST /api/farm/health-records
- GET /api/farm/milk-production
- POST /api/farm/milk-production
- GET /api/farm/reproduction-events
- POST /api/farm/reproduction-events
```

---

#### Developer 3 (Frontend/Mobile Dev) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Feed Entry Screens | Implement feed intake recording screens, TMR batch viewing, and ration comparison interface | Feed screens |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Sync Engine Implementation | Implement synchronization engine with queue management, retry logic, and conflict resolution | Sync engine |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | RFID Scanning Integration | Implement RFID scanner integration using native platform channels, handle scanned data, and auto-populate forms | RFID integration |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Photo Capture Feature | Implement camera integration for health record photos, animal identification photos, and document scanning | Photo capture |

**End of Day Deliverables:**
1. Feed management screens
2. Sync engine with queue and retry
3. RFID scanner integration
4. Photo capture functionality

**Code Commitments:**
```dart
// Created:
- lib/ui/screens/feed/feed_intake_screen.dart
- lib/ui/screens/feed/tmr_view_screen.dart
- lib/blocs/sync/sync_bloc.dart
- lib/services/sync_service.dart
- lib/services/rfid_service.dart
- lib/services/camera_service.dart
- lib/ui/widgets/rfid/rfid_scanner_overlay.dart
```

---

### 3.6 Day 325 (Friday) - Mobile App Core Features

#### Developer 1 (Lead Dev - Farm Systems) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Workflow Engine Implementation | Implement automated workflow engine for farm operations with trigger conditions, action sequences, and notification dispatch | Workflow engine |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Report Generation System | Design automated report generation for herd performance, health summaries, and reproduction statistics | Report system design |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Audit Trail Implementation | Implement comprehensive audit logging for all farm data changes with user attribution and timestamp precision | Audit trail system |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Integration Testing Setup | Setup integration test framework for farm management workflows, create test data fixtures, and write initial tests | Testing framework |

**End of Day Deliverables:**
1. Automated workflow engine
2. Report generation system design
3. Audit trail implementation
4. Integration testing framework

---

#### Developer 2 (Backend Dev - Farm Logic) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Health Alert Rules | Implement comprehensive health alert rules for mastitis detection, lameness scoring, metabolic disorder indicators, and vaccination reminders | Health alert rules |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Mobile Sync APIs | Implement specialized mobile synchronization APIs with delta sync, conflict resolution endpoints, and batch operations | Mobile sync APIs |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | RFID Data Ingestion | Implement RFID data ingestion endpoints for real-time animal identification, event capture, and location updates | RFID ingestion API |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | API Documentation | Generate comprehensive API documentation with Swagger/OpenAPI specifications for all farm management endpoints | API documentation |

**End of Day Deliverables:**
1. Health alert rule engine with 20+ rules
2. Mobile sync APIs with delta sync
3. RFID data ingestion endpoints
4. Complete API documentation

---

#### Developer 3 (Frontend/Mobile Dev) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Quick Action Interface | Implement quick action buttons for common operations: record milk, health check, heat detection, AI recording | Quick actions |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Notification System | Implement local notification system for due tasks, alerts, and reminders with custom sound and vibration patterns | Notifications |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Settings and Configuration | Implement settings screens for sync preferences, notification preferences, farm configuration, and user profile | Settings screens |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Offline Mode Polish | Enhance offline mode with visual indicators, sync status display, and graceful degradation of features | Offline mode polish |

**End of Day Deliverables:**
1. Quick action interface
2. Notification system
3. Settings and configuration screens
4. Polished offline mode experience

---

### 3.7 Day 326 (Monday) - Integration & Testing (Week 2 Start)

#### Developer 1 (Lead Dev - Farm Systems) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | System Integration | Integrate all farm management modules ensuring data consistency and proper event propagation between modules | Integrated system |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Performance Optimization | Optimize database queries, implement caching strategies, and tune algorithms for large herd sizes (1000+ animals) | Performance improvements |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | End-to-End Testing | Conduct end-to-end testing of complete workflows from animal registration through milk production tracking | E2E testing |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Documentation Review | Review all technical documentation, update specifications based on implementation, and prepare developer guides | Updated documentation |

---

#### Developer 2 (Backend Dev - Farm Logic) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Backend Integration Testing | Write and execute comprehensive backend integration tests for all farm management APIs | Backend tests |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Algorithm Validation | Validate breeding calculations, milk yield predictions, and feed conversion algorithms against known test cases | Validated algorithms |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Security Review | Conduct security review of all APIs, implement additional validation, and sanitize inputs | Security enhancements |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Load Testing | Perform load testing on critical endpoints to ensure system handles expected concurrent users | Load test results |

---

#### Developer 3 (Frontend/Mobile Dev) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Mobile-Backend Integration | Complete integration between mobile app and backend APIs, ensure all data flows correctly | Full integration |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | UI/UX Polish | Refine UI components, ensure consistent styling, improve accessibility, and add loading states | UI polish |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Mobile Testing | Conduct comprehensive mobile app testing on Android and iOS devices, test offline scenarios | Mobile testing |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Bug Fixes | Address bugs identified during testing, fix sync issues, and resolve UI glitches | Bug fixes |

---

### 3.8 Day 327 (Tuesday) - Advanced Features

#### Developer 1 (Lead Dev - Farm Systems) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Advanced Analytics | Implement predictive analytics for milk production forecasting, reproduction success prediction, and health risk assessment | Predictive analytics |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Genetic Evaluation Integration | Complete EBV integration with external genetic evaluation systems, implement data import/export | Genetic integration |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Multi-Farm Support | Enhance architecture to support multi-farm deployments with data isolation and farm-specific configurations | Multi-farm support |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Data Migration Tools | Create data migration tools for importing historical data from legacy systems and spreadsheets | Migration tools |

---

#### Developer 2 (Backend Dev - Farm Logic) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Advanced Alert Engine | Enhance alert engine with machine learning-based anomaly detection and predictive alerts | Advanced alerts |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Reporting API | Implement comprehensive reporting API with filtered exports, scheduled reports, and custom report builder | Reporting API |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Data Export Features | Implement data export functionality for compliance reporting, herd transfers, and backup operations | Data export |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Database Optimization | Optimize database schema, add appropriate indexes, and tune query performance | DB optimization |

---

#### Developer 3 (Frontend/Mobile Dev) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Advanced Chart Features | Implement interactive charts with zoom, pan, data point selection, and comparative overlays | Advanced charts |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Bulk Operations | Implement bulk operation interfaces for batch updates, group movements, and mass data entry | Bulk operations |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Barcode Scanner Support | Add barcode scanner support for supplementing RFID scanning with visual tag reading | Barcode support |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Localization | Implement multi-language support with at least 5 languages, ensure all UI text is localizable | Localization |

---

### 3.9 Day 328 (Wednesday) - Testing & Quality Assurance

#### Developer 1 (Lead Dev - Farm Systems) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | System Testing | Comprehensive system testing covering all farm management workflows and edge cases | System test results |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Code Review | Conduct detailed code review of all farm management modules, ensure coding standards compliance | Code review report |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Documentation Finalization | Finalize all technical documentation, API docs, and developer guides | Final documentation |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Issue Triage | Triage and prioritize remaining issues, assign fixes to appropriate developers | Issue backlog |

---

#### Developer 2 (Backend Dev - Farm Logic) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Unit Test Completion | Achieve >80% code coverage for all farm management backend code | Test coverage report |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Integration Test Suite | Complete integration test suite for all API endpoints and module interactions | Integration tests |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Regression Testing | Execute full regression test suite to ensure no regressions in existing functionality | Regression results |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Performance Benchmarking | Benchmark critical operations and document performance characteristics | Benchmark report |

---

#### Developer 3 (Frontend/Mobile Dev) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Mobile UI Testing | Comprehensive UI testing on multiple device sizes and orientations | UI test results |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Offline Sync Testing | Extensive testing of offline mode, sync behavior, and conflict resolution | Sync test results |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Usability Testing | Conduct usability testing with sample users, gather feedback on workflow efficiency | Usability feedback |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Final Bug Fixes | Address all critical and high-priority bugs identified during testing | Bug fixes complete |

---

### 3.10 Day 329 (Thursday) - Documentation & Deployment Prep

#### Developer 1 (Lead Dev - Farm Systems) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | User Documentation | Create comprehensive user documentation for farm management features | User manual |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Deployment Guide | Write deployment guide for production installation and configuration | Deployment guide |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Training Materials | Develop training materials for farm staff including videos and quick reference guides | Training materials |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Final Review | Final review of all deliverables, sign-off preparation | Review complete |

---

#### Developer 2 (Backend Dev - Farm Logic) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | Database Migration Scripts | Create database migration scripts for production deployment | Migration scripts |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Backup and Recovery | Implement backup and recovery procedures for farm data | Backup procedures |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Monitoring Setup | Configure monitoring for farm management services and database | Monitoring configured |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Final Deployment Package | Prepare complete deployment package with all components | Deployment package |

---

#### Developer 3 (Frontend/Mobile Dev) - 8 hours

**Morning (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 08:00-09:30 | App Store Preparation | Prepare app store listings, screenshots, descriptions for Google Play and Apple App Store | Store materials |
| 09:30-10:00 | Break | | |
| 10:00-12:00 | Build Configuration | Configure release builds, signing certificates, and version management | Release builds |

**Afternoon (4 hours):**

| Time | Task | Details | Deliverable |
|------|------|---------|-------------|
| 13:00-14:30 | Beta Distribution | Setup beta testing distribution for internal and select external testers | Beta distribution |
| 14:30-15:00 | Break | | |
| 15:00-17:00 | Final QA | Final quality assurance check on mobile application | QA sign-off |

---

### 3.11 Day 330 (Friday) - Final Review & Sign-Off

#### All Developers - Collaborative Day - 8 hours

**Morning (4 hours):**

| Time | Activity | Details |
|------|----------|---------|
| 08:00-09:30 | Demo Preparation | Prepare comprehensive demo of all farm management features |
| 09:30-10:00 | Break | |
| 10:00-12:00 | Feature Demo | Internal demo to stakeholders, gather final feedback |

**Afternoon (4 hours):**

| Time | Activity | Details |
|------|----------|---------|
| 13:00-14:30 | Issue Resolution | Address any final issues identified during demo |
| 14:30-15:00 | Break | |
| 15:00-16:30 | Documentation Sign-Off | Final review and sign-off on all documentation |
| 16:30-17:00 | Milestone Completion | Milestone 33 completion ceremony, lessons learned, planning for next milestone |

**Final Deliverables:**
1. Complete Smart Farm Management Core system
2. Mobile application (iOS and Android)
3. All documentation (technical, user, deployment)
4. Test reports with >80% coverage
5. Deployment packages ready for production
6. Training materials for farm staff

---


      );

      if (photo == null) return null;

      // Process and compress the image
      final processedImage = await _processImage(File(photo.path));
      
      return CapturedImage(
        file: processedImage,
        originalPath: photo.path,
        captureTime: DateTime.now(),
      );
    } catch (e) {
      print('Error taking photo: $e');
      return null;
    }
  }

  /// Select photo from gallery
  Future<CapturedImage?> selectFromGallery({
    int maxWidth = 1920,
    int maxHeight = 1080,
    int quality = 85,
  }) async {
    try {
      final XFile? image = await _picker.pickImage(
        source: ImageSource.gallery,
        maxWidth: maxWidth.toDouble(),
        maxHeight: maxHeight.toDouble(),
        imageQuality: quality,
      );

      if (image == null) return null;

      final processedImage = await _processImage(File(image.path));
      
      return CapturedImage(
        file: processedImage,
        originalPath: image.path,
        captureTime: DateTime.now(),
      );
    } catch (e) {
      print('Error selecting photo: $e');
      return null;
    }
  }

  /// Process image - resize and compress
  Future<File> _processImage(File originalFile) async {
    // Read the image
    final bytes = await originalFile.readAsBytes();
    img.Image? image = img.decodeImage(bytes);
    
    if (image == null) return originalFile;

    // Resize if too large
    if (image.width > 1920 || image.height > 1080) {
      image = img.copyResize(
        image,
        width: image.width > image.height ? 1920 : null,
        height: image.height >= image.width ? 1080 : null,
      );
    }

    // Save processed image
    final directory = await getTemporaryDirectory();
    final fileName = 'processed_${DateTime.now().millisecondsSinceEpoch}.jpg';
    final processedPath = '${directory.path}/$fileName';
    
    final processedFile = File(processedPath);
    await processedFile.writeAsBytes(img.encodeJpg(image, quality: 85));

    return processedFile;
  }

  /// Attach photo to a record and queue for upload
  Future<String?> attachPhotoToRecord({
    required CapturedImage photo,
    required String entityType,
    required int entityId,
  }) async {
    // Save to app documents
    final directory = await getApplicationDocumentsDirectory();
    final attachmentsDir = Directory('${directory.path}/attachments');
    if (!await attachmentsDir.exists()) {
      await attachmentsDir.create(recursive: true);
    }

    final fileName = '${entityType}_${entityId}_${DateTime.now().millisecondsSinceEpoch}.jpg';
    final savedPath = '${attachmentsDir.path}/$fileName';
    
    await photo.file.copy(savedPath);

    // Queue for upload when online
    await SyncService().queueChange(
      entityType: 'attachment',
      operation: 'create',
      data: {
        'localPath': savedPath,
        'entityType': entityType,
        'entityId': entityId,
        'fileName': fileName,
      },
      priority: 3, // Lower priority than data
    );

    return savedPath;
  }
}

class CapturedImage {
  final File file;
  final String originalPath;
  final DateTime captureTime;

  CapturedImage({
    required this.file,
    required this.originalPath,
    required this.captureTime,
  });

  int get sizeBytes => file.lengthSync();
  String get sizeFormatted => _formatBytes(sizeBytes);

  String _formatBytes(int bytes) {
    if (bytes < 1024) return '$bytes B';
    if (bytes < 1024 * 1024) return '${(bytes / 1024).toStringAsFixed(1)} KB';
    return '${(bytes / (1024 * 1024)).toStringAsFixed(1)} MB';
  }
}
```

---

## 7. API Documentation

### 7.1 Farm Management APIs

#### Animal Management Endpoints

```yaml
openapi: 3.0.0
info:
  title: Smart Dairy Farm Management API
  version: 1.0.0
  description: API for managing farm animals, health records, milk production, and reproduction

paths:
  /api/v1/farm/animals:
    get:
      summary: List animals
      parameters:
        - name: limit
          in: query
          schema:
            type: integer
            default: 50
        - name: offset
          in: query
          schema:
            type: integer
            default: 0
        - name: status
          in: query
          schema:
            type: string
            enum: [active, dry, sold, died, culled]
        - name: group_id
          in: query
          schema:
            type: integer
        - name: location_id
          in: query
          schema:
            type: integer
        - name: search
          in: query
          description: Search by internal_id, name, or electronic_id
          schema:
            type: string
        - name: since
          in: query
          description: Filter by updated date (ISO 8601)
          schema:
            type: string
            format: date-time
      responses:
        200:
          description: List of animals
          content:
            application/json:
              schema:
                type: object
                properties:
                  total:
                    type: integer
                  offset:
                    type: integer
                  limit:
                    type: integer
                  animals:
                    type: array
                    items:
                      $ref: '#/components/schemas/Animal'

    post:
      summary: Create new animal
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/AnimalInput'
      responses:
        201:
          description: Animal created
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/Animal'
        400:
          description: Validation error

  /api/v1/farm/animals/{id}:
    get:
      summary: Get animal by ID
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      responses:
        200:
          description: Animal details
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/Animal'
        404:
          description: Animal not found

    put:
      summary: Update animal
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/AnimalInput'
      responses:
        200:
          description: Animal updated
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/Animal'

    delete:
      summary: Delete animal (soft delete)
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      responses:
        204:
          description: Animal deleted

  /api/v1/farm/animals/by-rfid/{rfid}:
    get:
      summary: Get animal by RFID tag
      parameters:
        - name: rfid
          in: path
          required: true
          schema:
            type: string
      responses:
        200:
          description: Animal found
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/Animal'
        404:
          description: Animal not found

  /api/v1/farm/animals/{id}/pedigree:
    get:
      summary: Get animal pedigree
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
        - name: generations
          in: query
          schema:
            type: integer
            default: 4
            maximum: 10
      responses:
        200:
          description: Pedigree data
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/Pedigree'

components:
  schemas:
    Animal:
      type: object
      properties:
        id:
          type: integer
        internal_id:
          type: string
        electronic_id:
          type: string
          nullable: true
        visual_id:
          type: string
          nullable: true
        name:
          type: string
          nullable: true
        species:
          type: string
        breed_id:
          type: integer
          nullable: true
        breed_name:
          type: string
          nullable: true
        animal_type:
          type: string
        status:
          type: string
        location_id:
          type: integer
          nullable: true
        group_id:
          type: integer
          nullable: true
        birth_date:
          type: string
          format: date
          nullable: true
        sire_id:
          type: integer
          nullable: true
        dam_id:
          type: integer
          nullable: true
        lactation_number:
          type: integer
        is_pregnant:
          type: boolean
        expected_calving_date:
          type: string
          format: date
          nullable: true
        days_in_milk:
          type: integer
          nullable: true
        current_milk_yield:
          type: number
          nullable: true
        created_at:
          type: string
          format: date-time
        updated_at:
          type: string
          format: date-time

    AnimalInput:
      type: object
      required:
        - internal_id
        - species
      properties:
        internal_id:
          type: string
          maxLength: 50
        electronic_id:
          type: string
          maxLength: 50
        visual_id:
          type: string
          maxLength: 50
        name:
          type: string
          maxLength: 100
        species:
          type: string
          enum: [cattle, goat, sheep, buffalo]
        breed_id:
          type: integer
        animal_type:
          type: string
          enum: [dairy_cow, beef_cow, heifer, calf_female, calf_male, bull, steer]
        status:
          type: string
          enum: [active, dry, sold, died, culled, transferred]
        location_id:
          type: integer
        group_id:
          type: integer
        birth_date:
          type: string
          format: date
        sire_id:
          type: integer
        dam_id:
          type: integer
```

#### Milk Production Endpoints

```yaml
  /api/v1/farm/milk-production:
    get:
      summary: List milk production records
      parameters:
        - name: animal_id
          in: query
          schema:
            type: integer
        - name: from_date
          in: query
          schema:
            type: string
            format: date
        - name: to_date
          in: query
          schema:
            type: string
            format: date
        - name: limit
          in: query
          schema:
            type: integer
            default: 100
      responses:
        200:
          description: List of milk records
          content:
            application/json:
              schema:
                type: object
                properties:
                  records:
                    type: array
                    items:
                      $ref: '#/components/schemas/MilkRecord'

    post:
      summary: Record milk production
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/MilkRecordInput'
      responses:
        201:
          description: Record created
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/MilkRecord'

  /api/v1/farm/milk-production/bulk:
    post:
      summary: Bulk insert milk records
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              properties:
                records:
                  type: array
                  items:
                    $ref: '#/components/schemas/MilkRecordInput'
      responses:
        201:
          description: Records created
          content:
            application/json:
              schema:
                type: object
                properties:
                  created:
                    type: integer
                  errors:
                    type: array
                    items:
                      type: object

  /api/v1/farm/milk-production/daily-summary:
    get:
      summary: Get daily milk production summary
      parameters:
        - name: date
          in: query
          schema:
            type: string
            format: date
        - name: location_id
          in: query
          schema:
            type: integer
      responses:
        200:
          description: Daily summary
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/DailyMilkSummary'

components:
  schemas:
    MilkRecord:
      type: object
      properties:
        id:
          type: integer
        animal_id:
          type: integer
        milking_date:
          type: string
          format: date
        milking_number:
          type: integer
        milk_yield_kg:
          type: number
        fat_percentage:
          type: number
        protein_percentage:
          type: number
        lactose_percentage:
          type: number
        somatic_cell_count:
          type: integer
        urea_mg_dl:
          type: number
        validated:
          type: boolean
        created_at:
          type: string
          format: date-time

    MilkRecordInput:
      type: object
      required:
        - animal_id
        - milking_date
        - milk_yield_kg
      properties:
        animal_id:
          type: integer
        milking_date:
          type: string
          format: date
        milking_number:
          type: integer
          default: 1
        milk_yield_kg:
          type: number
        fat_percentage:
          type: number
        protein_percentage:
          type: number
        lactose_percentage:
          type: number
        somatic_cell_count:
          type: integer
        urea_mg_dl:
          type: number
        collection_method:
          type: string
          enum: [manual, auto_meter, rfid_auto]

    DailyMilkSummary:
      type: object
      properties:
        date:
          type: string
          format: date
        total_milk_kg:
          type: number
        total_animals:
          type: integer
        avg_yield_per_animal:
          type: number
        avg_fat_pct:
          type: number
        avg_protein_pct:
          type: number
        avg_scc:
          type: integer
```

#### Health Records Endpoints

```yaml
  /api/v1/farm/health-records:
    get:
      summary: List health records
      parameters:
        - name: animal_id
          in: query
          schema:
            type: integer
        - name: event_type
          in: query
          schema:
            type: string
        - name: status
          in: query
          schema:
            type: string
        - name: open_only
          in: query
          schema:
            type: boolean
            default: false
      responses:
        200:
          description: Health records list

    post:
      summary: Create health record
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/HealthRecordInput'
      responses:
        201:
          description: Record created

  /api/v1/farm/health-records/{id}/treatments:
    post:
      summary: Add treatment to health record
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/TreatmentInput'
      responses:
        201:
          description: Treatment added

  /api/v1/farm/vaccinations/due:
    get:
      summary: Get due vaccinations
      parameters:
        - name: animal_id
          in: query
          schema:
            type: integer
        - name: group_id
          in: query
          schema:
            type: integer
        - name: overdue_only
          in: query
          schema:
            type: boolean
            default: false
      responses:
        200:
          description: List of due vaccinations

components:
  schemas:
    HealthRecordInput:
      type: object
      required:
        - animal_id
        - event_type
      properties:
        animal_id:
          type: integer
        event_date:
          type: string
          format: date-time
        event_type:
          type: string
          enum: [examination, illness, injury, mastitis, lameness, metabolic, reproductive, vaccination, deworming, surgery, other]
        body_temperature:
          type: number
        pulse_rate:
          type: integer
        respiratory_rate:
          type: integer
        body_condition_score:
          type: number
        symptoms:
          type: string
        diagnosis:
          type: string
        severity:
          type: string
          enum: [mild, moderate, severe, critical]
        treatment_plan:
          type: string
        veterinarian_id:
          type: integer
        notes:
          type: string

    TreatmentInput:
      type: object
      required:
        - treatment_date
      properties:
        treatment_date:
          type: string
          format: date-time
        treatment_type:
          type: string
        product_id:
          type: integer
        batch_number:
          type: string
        quantity:
          type: number
        unit_id:
          type: integer
        route:
          type: string
          enum: [oral, im, iv, sc, topical, intramammary, other]
        withdrawal_milk_days:
          type: integer
        withdrawal_meat_days:
          type: integer
        notes:
          type: string
```

#### Reproduction Endpoints

```yaml
  /api/v1/farm/reproduction-events:
    get:
      summary: List reproduction events
      parameters:
        - name: animal_id
          in: query
          schema:
            type: integer
        - name: event_type
          in: query
          schema:
            type: string
        - name: upcoming_calvings
          in: query
          description: Get animals with expected calving in next N days
          schema:
            type: integer
      responses:
        200:
          description: Reproduction events

    post:
      summary: Record reproduction event
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/ReproductionEventInput'
      responses:
        201:
          description: Event recorded

  /api/v1/farm/heat-alerts:
    get:
      summary: Get heat detection alerts
      parameters:
        - name: status
          in: query
          schema:
            type: string
            default: active
        - name: min_confidence
          in: query
          schema:
            type: number
            default: 60
      responses:
        200:
          description: Heat alerts

components:
  schemas:
    ReproductionEventInput:
      type: object
      required:
        - animal_id
        - event_type
        - event_date
      properties:
        animal_id:
          type: integer
        event_type:
          type: string
          enum: [heat, ai, natural_service, pregnancy_check, calving, abortion, embryo_transfer]
        event_date:
          type: string
          format: date-time
        heat_intensity:
          type: string
        heat_signs:
          type: array
          items:
            type: string
        sire_id:
          type: integer
        semen_id:
          type: integer
        technician_id:
          type: integer
        insemination_score:
          type: string
        pregnancy_status:
          type: string
          enum: [pregnant, not_pregnant, doubtful, early_embryonic_death]
        pregnancy_check_method:
          type: string
        days_pregnant:
          type: integer
        calving_ease:
          type: string
        notes:
          type: string
```

### 7.2 Mobile Sync APIs

```yaml
  /api/v1/mobile/sync:
    post:
      summary: Synchronize mobile data
      description: |
        Bidirectional sync endpoint for mobile clients.
        Sends local changes and receives server updates.
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              properties:
                last_sync_time:
                  type: string
                  format: date-time
                changes:
                  type: array
                  items:
                    type: object
                    properties:
                      entity_type:
                        type: string
                      operation:
                        type: string
                        enum: [create, update, delete]
                      data:
                        type: object
                      local_id:
                        type: string
      responses:
        200:
          description: Sync completed
          content:
            application/json:
              schema:
                type: object
                properties:
                  server_changes:
                    type: array
                    items:
                      type: object
                  conflicts:
                    type: array
                    items:
                      type: object
                  new_sync_time:
                    type: string
                    format: date-time

  /api/v1/mobile/delta-sync:
    get:
      summary: Get delta changes since last sync
      parameters:
        - name: since
          in: query
          required: true
          schema:
            type: string
            format: date-time
        - name: entities
          in: query
          description: Comma-separated list of entity types
          schema:
            type: string
            example: "animals,milk_records,health_records"
      responses:
        200:
          description: Delta changes
          content:
            application/json:
              schema:
                type: object
                properties:
                  changes:
                    type: array
                  deleted:
                    type: array

  /api/v1/mobile/batch-upload:
    post:
      summary: Batch upload multiple records
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              properties:
                animals:
                  type: array
                  items:
                    $ref: '#/components/schemas/AnimalInput'
                milk_records:
                  type: array
                  items:
                    $ref: '#/components/schemas/MilkRecordInput'
                health_records:
                  type: array
                  items:
                    $ref: '#/components/schemas/HealthRecordInput'
      responses:
        200:
          description: Batch processed
          content:
            application/json:
              schema:
                type: object
                properties:
                  results:
                    type: object
                    properties:
                      animals:
                        type: object
                      milk_records:
                        type: object
                      health_records:
                        type: object
```

### 7.3 RFID Data Ingestion

```yaml
  /api/v1/rfid/scan:
    post:
      summary: Record RFID scan event
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              required:
                - rfid_tag
                - device_id
                - timestamp
              properties:
                rfid_tag:
                  type: string
                device_id:
                  type: string
                timestamp:
                  type: string
                  format: date-time
                signal_strength:
                  type: number
                location_id:
                  type: integer
                event_type:
                  type: string
                  enum: [entry, exit, milking, weighing, inspection]
      responses:
        201:
          description: Scan recorded
          content:
            application/json:
              schema:
                type: object
                properties:
                  scan_id:
                    type: integer
                  animal_id:
                    type: integer
                    nullable: true
                  animal_found:
                    type: boolean
                  matched_animal:
                    $ref: '#/components/schemas/Animal'
                    nullable: true

  /api/v1/rfid/bulk-scan:
    post:
      summary: Bulk RFID scan upload
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              properties:
                device_id:
                  type: string
                scans:
                  type: array
                  items:
                    type: object
                    properties:
                      rfid_tag:
                        type: string
                      timestamp:
                        type: string
                        format: date-time
                      signal_strength:
                        type: number
      responses:
        201:
          description: Scans processed

  /api/v1/rfid/devices:
    get:
      summary: List registered RFID devices
      responses:
        200:
          description: List of devices

    post:
      summary: Register new RFID device
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              properties:
                name:
                  type: string
                device_code:
                  type: string
                device_type:
                  type: string
                location_id:
                  type: integer
      responses:
        201:
          description: Device registered
```

### 7.4 Health Alert APIs

```yaml
  /api/v1/health-alerts:
    get:
      summary: Get health alerts
      parameters:
        - name: status
          in: query
          schema:
            type: string
            default: active
        - name: severity
          in: query
          schema:
            type: string
        - name: animal_id
          in: query
          schema:
            type: integer
      responses:
        200:
          description: Health alerts

  /api/v1/health-alerts/{id}/acknowledge:
    post:
      summary: Acknowledge alert
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                notes:
                  type: string
      responses:
        200:
          description: Alert acknowledged

  /api/v1/health-alerts/{id}/resolve:
    post:
      summary: Resolve alert
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                resolution_notes:
                  type: string
      responses:
        200:
          description: Alert resolved
```

---

## 8. Testing Protocols

### 8.1 Farm Workflow Testing

#### Test Scenarios

| Test ID | Scenario | Preconditions | Steps | Expected Result |
|---------|----------|---------------|-------|-----------------|
| FWT-001 | New Animal Registration | User logged in with create permissions | 1. Navigate to Animals > New<br>2. Enter internal ID<br>3. Select breed and type<br>4. Save | Animal created with unique ID |
| FWT-002 | Animal Parentage Validation | Sire and dam exist in system | 1. Edit animal<br>2. Set sire and dam<br>3. Save | Parentage validated, inbreeding calculated |
| FWT-003 | Animal Location Transfer | Animal exists, target location exists | 1. Select animal<br>2. Choose Move action<br>3. Select new location<br>4. Confirm | Location updated, movement logged |
| FWT-004 | Calving Event Registration | Pregnant animal exists | 1. Record calving event<br>2. Enter calving ease<br>3. Register calf(s) | Calving saved, calf registered, lactation started |
| FWT-005 | Daily Milk Recording | Animals exist in lactation | 1. Go to Milk Recording<br>2. Scan/Select animal<br>3. Enter yield<br>4. Save | Milk record created, totals updated |

### 8.2 Mobile App Testing

#### Functional Testing Checklist

| Feature | Test Case | Expected Result | Status |
|---------|-----------|-----------------|--------|
| **Login** | Valid credentials | Dashboard displayed | ☐ |
| **Login** | Invalid credentials | Error message shown | ☐ |
| **Animal List** | Scroll through 1000+ animals | Smooth scrolling, no lag | ☐ |
| **Animal Search** | Search by ID | Results filtered correctly | ☐ |
| **RFID Scan** | Scan valid tag | Animal details displayed | ☐ |
| **RFID Scan** | Scan unknown tag | "Animal not found" message | ☐ |
| **Milk Entry** | Enter valid yield | Record saved, sync queued | ☐ |
| **Milk Entry** | Enter invalid yield | Validation error shown | ☐ |
| **Health Record** | Create with photo | Photo attached, record saved | ☐ |
| **Offline Mode** | Create record offline | Record queued for sync | ☐ |
| **Sync** | Restore connection | Pending records sync | ☐ |

### 8.3 RFID Integration Tests

```python
# tests/test_rfid_integration.py

import pytest
from datetime import datetime
from unittest.mock import Mock, patch

class TestRFIDIntegration:
    """Test suite for RFID integration."""
    
    def test_rfid_scan_creates_event(self):
        """Test that RFID scan creates appropriate event."""
        # Arrange
        rfid_tag = "E200341502001080"
        device_id = "RFID-001"
        
        # Act
        result = self.rfid_service.process_scan({
            'rfid_tag': rfid_tag,
            'device_id': device_id,
            'timestamp': datetime.now(),
        })
        
        # Assert
        assert result['scan_id'] is not None
        assert result['animal_found'] == True
    
    def test_rfid_scan_unknown_tag(self):
        """Test handling of unknown RFID tag."""
        # Arrange
        rfid_tag = "UNKNOWN999999"
        
        # Act
        result = self.rfid_service.process_scan({
            'rfid_tag': rfid_tag,
            'device_id': 'RFID-001',
            'timestamp': datetime.now(),
        })
        
        # Assert
        assert result['animal_found'] == False
        assert 'unmatched_scan_id' in result
    
    def test_rfid_milking_parlor_workflow(self):
        """Test complete milking parlor RFID workflow."""
        # Simulate cow entering milking parlor
        entry_scan = self.rfid_service.process_scan({
            'rfid_tag': 'E200341502001080',
            'device_id': 'MP-ENTRY-01',
            'event_type': 'entry',
        })
        
        assert entry_scan['animal_id'] == 123
        
        # Record milk yield
        milk_record = self.milk_service.record_production({
            'animal_id': 123,
            'yield_kg': 28.5,
            'milking_number': 1,
        })
        
        assert milk_record['id'] is not None
        
        # Cow exits
        exit_scan = self.rfid_service.process_scan({
            'rfid_tag': 'E200341502001080',
            'device_id': 'MP-EXIT-01',
            'event_type': 'exit',
        })
        
        assert exit_scan['animal_id'] == 123
```

### 8.4 Offline Sync Testing

```python
# tests/test_offline_sync.py

class TestOfflineSync:
    """Test offline synchronization functionality."""
    
    def test_record_created_offline_queued(self):
        """Test that offline records are queued for sync."""
        # Simulate offline state
        self.sync_service.set_offline(True)
        
        # Create record
        record = self.animal_service.create({
            'internal_id': 'TEST001',
            'species': 'cattle',
        })
        
        # Check queue
        queue = self.sync_service.get_pending_queue()
        assert len(queue) == 1
        assert queue[0]['entity_type'] == 'animal'
        assert queue[0]['operation'] == 'create'
    
    def test_sync_on_connection_restore(self):
        """Test sync triggers when connection restored."""
        # Add items to queue
        self.sync_service.queue_change({
            'entity_type': 'milk_production',
            'operation': 'create',
            'data': {'animal_id': 1, 'yield': 25.0},
        })
        
        # Simulate connection restore
        self.sync_service.set_offline(False)
        
        # Verify sync initiated
        assert self.sync_service.sync_status == 'syncing'
    
    def test_conflict_resolution(self):
        """Test conflict resolution during sync."""
        # Create conflicting updates
        local_update = {'id': 1, 'name': 'Local Name', 'updated_at': '2026-01-15T10:00:00'}
        server_update = {'id': 1, 'name': 'Server Name', 'updated_at': '2026-01-15T11:00:00'}
        
        # Resolve conflict (server wins based on timestamp)
        result = self.sync_service.resolve_conflict(local_update, server_update)
        
        assert result['name'] == 'Server Name'
    
    def test_sync_retry_on_failure(self):
        """Test retry mechanism for failed syncs."""
        # Queue item with failed status
        item = self.sync_service.queue.add({
            'entity_type': 'animal',
            'operation': 'update',
            'data': {'id': 1},
            'retry_count': 2,
        })
        
        # Attempt sync (will fail)
        with patch.object(self.api, 'update_animal', side_effect=Exception('Network error')):
            self.sync_service.process_sync_item(item)
        
        # Verify retry count incremented
        assert item.retry_count == 3
        assert item.status == 'pending'  # Will retry
    
    def test_max_retries_exceeded(self):
        """Test item marked as failed after max retries."""
        item = self.sync_service.queue.add({
            'entity_type': 'animal',
            'operation': 'update',
            'data': {'id': 1},
            'retry_count': 3,
        })
        
        # Attempt sync (will fail)
        with patch.object(self.api, 'update_animal', side_effect=Exception('Network error')):
            self.sync_service.process_sync_item(item)
        
        # Verify marked as failed
        assert item.status == 'failed'
```

---

## 9. Deliverables

### 9.1 Feature Completeness Checklist

#### Herd Management
- [x] Animal master data with complete lifecycle tracking
- [x] Genetics and pedigree management
- [x] Inbreeding coefficient calculation
- [x] Estimated Breeding Value (EBV) integration
- [x] Location and group management
- [x] Movement history tracking
- [x] Performance analytics and rankings

#### Health Management
- [x] Health record creation and management
- [x] Treatment administration tracking
- [x] Withdrawal period calculations
- [x] Vaccination scheduling
- [x] Alert engine with rule-based detection
- [x] Veterinary workflow support

#### Reproduction Management
- [x] Heat detection and recording
- [x] AI and natural service recording
- [x] Pregnancy checking
- [x] Calving event management
- [x] Offspring registration
- [x] Gestation tracking
- [x] Semen inventory management

#### Milk Production
- [x] Daily milk recording
- [x] Quality parameter tracking
- [x] Lactation curve analysis
- [x] 305-day projection calculations
- [x] Persistency calculations
- [x] Daily summaries and reporting

#### Feed Management
- [x] Feed product management
- [x] TMR calculation engine
- [x] Nutritional requirement calculations
- [x] Feed intake recording
- [x] Feed conversion ratio tracking

#### Mobile Application
- [x] Flutter app for Android and iOS
- [x] Offline-first architecture
- [x] RFID scanning integration
- [x] Voice input support
- [x] Photo capture and attachment
- [x] Data synchronization

### 9.2 Performance Requirements

| Metric | Requirement | Test Method |
|--------|-------------|-------------|
| API Response Time (p95) | < 200ms | Load testing with 1000 concurrent users |
| Mobile App Launch Time | < 3 seconds | Manual testing on mid-range device |
| Database Query Time | < 100ms for 10k records | EXPLAIN ANALYZE on representative queries |
| Sync Throughput | > 100 records/minute | Bulk upload testing |
| RFID Scan Response | < 1 second | Hardware integration testing |
| Report Generation | < 5 seconds for monthly data | Automated testing |
| Concurrent Users | Support 100+ users | Load testing simulation |

### 9.3 Sign-Off Criteria

#### Technical Sign-Off
- [ ] All unit tests passing (>80% coverage)
- [ ] All integration tests passing
- [ ] Security audit completed
- [ ] Performance benchmarks met
- [ ] Code review completed
- [ ] Documentation complete

#### Functional Sign-Off
- [ ] All features implemented per specification
- [ ] User acceptance testing completed
- [ ] Mobile app published to stores (beta)
- [ ] Data migration tested
- [ ] Backup/recovery tested

#### Business Sign-Off
- [ ] Stakeholder demo completed
- [ ] Training materials approved
- [ ] Go-live plan approved
- [ ] Support procedures documented

---

## 10. Risk & Compliance

### 10.1 Data Accuracy Risks

| Risk | Likelihood | Impact | Mitigation |
|------|------------|--------|------------|
| Incorrect animal identification | Medium | High | RFID validation, visual confirmation, audit trails |
| Data entry errors | High | Medium | Validation rules, voice input, photo evidence |
| Sync conflicts | Medium | Medium | Conflict resolution rules, manual review queue |
| Calculation errors | Low | High | Unit testing, benchmark validation, peer review |
| Duplicate records | Medium | Medium | Unique constraints, duplicate detection algorithms |

### 10.2 Animal Welfare Compliance

The system ensures compliance with animal welfare regulations:

#### Tracking Requirements
- Complete treatment history with withdrawal periods
- Pain scoring for procedures
- Housing condition monitoring
- Movement and access tracking

#### Reporting Capabilities
- Welfare audit reports
- Treatment frequency analysis
- Mortality tracking
- Veterinary intervention logs

#### Alerts
- Extended withdrawal period violations
- Unusual mortality patterns
- Housing capacity exceeded
- Missed health checks

### 10.3 Mitigation Strategies

1. **Data Validation Layers**
   - Client-side validation in mobile app
   - Server-side validation on API
   - Database constraints
   - Business rule validation

2. **Audit Trail**
   - Complete change history
   - User attribution
   - Before/after values
   - Timestamp precision

3. **Backup and Recovery**
   - Automated daily backups
   - Point-in-time recovery
   - Geographic redundancy
   - Regular restore testing

4. **Security Measures**
   - Role-based access control
   - API authentication
   - Data encryption at rest
   - Secure transmission

---

## 11. Appendices

### Appendix A: Code Samples

#### A.1 Odoo Farm Animal Model (Complete)

```python
# farm_animal/models/animal.py
# Complete implementation of the FarmAnimal model

from odoo import models, fields, api, _, exceptions
from datetime import datetime, date
from dateutil.relativedelta import relativedelta

class FarmAnimal(models.Model):
    """
    Farm Animal Master Record
    
    This model serves as the central registry for all farm animals,
    tracking their complete lifecycle from birth through productive
    life to culling or sale.
    """
    _name = 'farm.animal'
    _description = 'Farm Animal Master Record'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'internal_id'
    
    # =========================================================================
    # IDENTIFICATION FIELDS
    # =========================================================================
    internal_id = fields.Char(
        string='Internal ID',
        required=True,
        index=True,
        tracking=True,
        help='Unique internal identifier for the animal'
    )
    
    electronic_id = fields.Char(
        string='EID/RFID',
        index=True,
        tracking=True,
        help='Electronic identification number (RFID tag)'
    )
    
    visual_id = fields.Char(
        string='Visual Tag/Ear Number',
        tracking=True,
        help='Visual identification number on ear tag'
    )
    
    name = fields.Char(
        string='Animal Name',
        tracking=True,
        help='Optional name for the animal'
    )
    
    # =========================================================================
    # CLASSIFICATION
    # =========================================================================
    species = fields.Selection([
        ('cattle', 'Cattle'),
        ('goat', 'Goat'),
        ('sheep', 'Sheep'),
        ('buffalo', 'Buffalo'),
    ], string='Species', required=True, default='cattle', tracking=True)
    
    breed_id = fields.Many2one(
        'farm.breed',
        string='Breed',
        tracking=True
    )
    
    animal_type = fields.Selection([
        ('dairy_cow', 'Dairy Cow'),
        ('beef_cow', 'Beef Cow'),
        ('heifer', 'Heifer'),
        ('calf_female', 'Female Calf'),
        ('calf_male', 'Male Calf'),
        ('bull', 'Bull'),
        ('steer', 'Steer'),
    ], string='Animal Type', tracking=True)
    
    # =========================================================================
    # LIFECYCLE STATUS
    # =========================================================================
    status = fields.Selection([
        ('active', 'Active'),
        ('dry', 'Dry'),
        ('sold', 'Sold'),
        ('died', 'Died'),
        ('culled', 'Culled'),
        ('transferred', 'Transferred'),
    ], string='Status', default='active', tracking=True)
    
    status_date = fields.Date(
        string='Status Date',
        help='Date when current status was set'
    )
    
    # =========================================================================
    # LOCATION TRACKING
    # =========================================================================
    location_id = fields.Many2one(
        'farm.location',
        string='Current Location',
        tracking=True
    )
    
    group_id = fields.Many2one(
        'farm.animal.group',
        string='Animal Group',
        tracking=True
    )
    
    # =========================================================================
    # KEY DATES
    # =========================================================================
    birth_date = fields.Date(
        string='Date of Birth',
        tracking=True
    )
    
    entry_date = fields.Date(
        string='Entry Date',
        default=fields.Date.today,
        help='Date when animal entered the farm'
    )
    
    exit_date = fields.Date(
        string='Exit Date',
        help='Date when animal left the farm'
    )
    
    # =========================================================================
    # PARENTAGE (GENETICS)
    # =========================================================================
    sire_id = fields.Many2one(
        'farm.animal',
        string='Sire (Father)',
        domain=[('animal_type', '=', 'bull')],
        tracking=True
    )
    
    dam_id = fields.Many2one(
        'farm.animal',
        string='Dam (Mother)',
        domain=[('animal_type', 'in', ['dairy_cow', 'beef_cow'])],
        tracking=True
    )
    
    offspring_ids = fields.One2many(
        'farm.animal',
        'dam_id',
        string='Offspring (as Dam)'
    )
    
    sire_offspring_ids = fields.One2many(
        'farm.animal',
        'sire_id',
        string='Offspring (as Sire)'
    )
    
    # =========================================================================
    # COMPUTED FIELDS
    # =========================================================================
    age_days = fields.Integer(
        string='Age (Days)',
        compute='_compute_age',
        store=True
    )
    
    age_display = fields.Char(
        string='Age',
        compute='_compute_age_display'
    )
    
    lactation_number = fields.Integer(
        string='Lactation Number',
        compute='_compute_lactation',
        store=True
    )
    
    days_in_milk = fields.Integer(
        string='Days in Milk (DIM)',
        compute='_compute_dim'
    )
    
    is_pregnant = fields.Boolean(
        string='Is Pregnant',
        compute='_compute_pregnancy_status',
        store=True
    )
    
    expected_calving_date = fields.Date(
        string='Expected Calving Date',
        compute='_compute_expected_calving',
        store=True
    )
    
    current_milk_yield = fields.Float(
        string='Current Milk Yield (kg)',
        compute='_compute_current_milk'
    )
    
    # =========================================================================
    # PHYSICAL CHARACTERISTICS
    # =========================================================================
    birth_weight_kg = fields.Float(
        string='Birth Weight (kg)',
        digits=(6, 2)
    )
    
    mature_weight_kg = fields.Float(
        string='Mature Weight (kg)',
        digits=(6, 2)
    )
    
    color_markings = fields.Text(
        string='Color/Markings'
    )
    
    # =========================================================================
    # HEALTH STATUS
    # =========================================================================
    health_status = fields.Selection([
        ('healthy', 'Healthy'),
        ('sick', 'Sick'),
        ('recovering', 'Recovering'),
        ('critical', 'Critical'),
    ], string='Health Status', default='healthy', tracking=True)
    
    last_health_check = fields.Date(
        string='Last Health Check'
    )
    
    # =========================================================================
    # FINANCIAL
    # =========================================================================
    purchase_price = fields.Float(
        string='Purchase Price',
        digits=(12, 2)
    )
    
    current_value = fields.Float(
        string='Current Value',
        digits=(12, 2)
    )
    
    # =========================================================================
    # RELATED RECORDS
    # =========================================================================
    genetics_id = fields.One2many(
        'farm.animal.genetics',
        'animal_id',
        string='Genetics'
    )
    
    health_record_ids = fields.One2many(
        'farm.health.record',
        'animal_id',
        string='Health Records'
    )
    
    reproduction_event_ids = fields.One2many(
        'farm.reproduction.event',
        'animal_id',
        string='Reproduction Events'
    )
    
    milk_production_ids = fields.One2many(
        'farm.milk.production',
        'animal_id',
        string='Milk Production Records'
    )
    
    lactation_ids = fields.One2many(
        'farm.lactation.period',
        'animal_id',
        string='Lactation Periods'
    )
    
    # =========================================================================
    # COMPUTE METHODS
    # =========================================================================
    @api.depends('birth_date')
    def _compute_age(self):
        """Calculate age in days from birth date."""
        for animal in self:
            if animal.birth_date:
                delta = date.today() - animal.birth_date
                animal.age_days = delta.days
            else:
                animal.age_days = 0
    
    @api.depends('age_days')
    def _compute_age_display(self):
        """Display age in years and months."""
        for animal in self:
            if animal.birth_date:
                delta = relativedelta(date.today(), animal.birth_date)
                if delta.years > 0:
                    animal.age_display = f"{delta.years}y {delta.months}m"
                else:
                    animal.age_display = f"{delta.months}m {delta.days}d"
            else:
                animal.age_display = "Unknown"
    
    @api.depends('lactation_ids')
    def _compute_lactation(self):
        """Calculate current lactation number."""
        for animal in self:
            completed = animal.lactation_ids.filtered(lambda l: l.status == 'completed')
            in_progress = animal.lactation_ids.filtered(lambda l: l.status == 'in_progress')
            animal.lactation_number = len(completed) + (1 if in_progress else 0)
    
    @api.depends('lactation_ids')
    def _compute_dim(self):
        """Calculate days in milk for current lactation."""
        for animal in self:
            current = animal.lactation_ids.filtered(lambda l: l.status == 'in_progress')
            if current:
                calving_date = current[0].calving_date
                animal.days_in_milk = (date.today() - calving_date).days
            else:
                animal.days_in_milk = 0
    
    @api.depends('reproduction_event_ids')
    def _compute_pregnancy_status(self):
        """Determine pregnancy status from reproduction events."""
        for animal in self:
            # Look for confirmed pregnancy
            preg_check = animal.reproduction_event_ids.filtered(
                lambda e: e.event_type == 'pregnancy_check' and 
                         e.pregnancy_status == 'pregnant'
            )
            
            if preg_check:
                # Check if calving has occurred since pregnancy check
                latest_preg = max(preg_check, key=lambda x: x.event_date)
                subsequent_calving = animal.reproduction_event_ids.filtered(
                    lambda e: e.event_type == 'calving' and 
                             e.event_date > latest_preg.event_date
                )
                animal.is_pregnant = not bool(subsequent_calving)
            else:
                animal.is_pregnant = False
    
    @api.depends('is_pregnant', 'reproduction_event_ids')
    def _compute_expected_calving(self):
        """Calculate expected calving date."""
        for animal in self:
            if animal.is_pregnant:
                preg_check = animal.reproduction_event_ids.filtered(
                    lambda e: e.event_type == 'pregnancy_check' and 
                             e.pregnancy_status == 'pregnant'
                )
                if preg_check:
                    latest = max(preg_check, key=lambda x: x.event_date)
                    animal.expected_calving_date = latest.expected_calving_date
                else:
                    animal.expected_calving_date = False
            else:
                animal.expected_calving_date = False
    
    @api.depends('milk_production_ids')
    def _compute_current_milk(self):
        """Get most recent milk yield."""
        for animal in self:
            recent = animal.milk_production_ids.sorted('milking_date', reverse=True)[:1]
            animal.current_milk_yield = recent[0].milk_yield_kg if recent else 0.0
    
    # =========================================================================
    # CONSTRAINTS
    # =========================================================================
    _sql_constraints = [
        ('internal_id_unique', 'UNIQUE(internal_id)', 
         'Internal ID must be unique!'),
        ('electronic_id_unique', 'UNIQUE(electronic_id)', 
         'Electronic ID must be unique!'),
    ]
    
    @api.constrains('sire_id', 'dam_id')
    def _check_parentage(self):
        """Validate parentage relationships."""
        for animal in self:
            if animal.sire_id and animal.sire_id.id == animal.id:
                raise exceptions.ValidationError("Animal cannot be its own sire!")
            if animal.dam_id and animal.dam_id.id == animal.id:
                raise exceptions.ValidationError("Animal cannot be its own dam!")
            if animal.sire_id and animal.dam_id and animal.sire_id == animal.dam_id:
                raise exceptions.ValidationError("Sire and dam cannot be the same animal!")
    
    @api.constrains('birth_date', 'entry_date')
    def _check_dates(self):
        """Validate date logic."""
        for animal in self:
            if animal.birth_date and animal.entry_date:
                if animal.entry_date < animal.birth_date:
                    raise exceptions.ValidationError(
                        "Entry date cannot be before birth date!"
                    )
    
    # =========================================================================
    # CREATE/WRITE OVERRIDE
    # =========================================================================
    @api.model_create_multi
    def create(self, vals_list):
        """Override create to set sequence for internal_id if not provided."""
        for vals in vals_list:
            if not vals.get('internal_id'):
                vals['internal_id'] = self.env['ir.sequence'].next_by_code('farm.animal')
        return super(FarmAnimal, self).create(vals_list)
    
    def write(self, vals):
        """Override write to track location changes."""
        if 'location_id' in vals or 'group_id' in vals:
            self._log_movement(vals)
        return super(FarmAnimal, self).write(vals)
    
    def _log_movement(self, vals):
        """Log animal movement to history."""
        for animal in self:
            self.env['farm.animal.movement'].create({
                'animal_id': animal.id,
                'from_location_id': animal.location_id.id if animal.location_id else False,
                'to_location_id': vals.get('location_id'),
                'from_group_id': animal.group_id.id if animal.group_id else False,
                'to_group_id': vals.get('group_id'),
                'movement_date': datetime.now(),
                'reason': vals.get('status') and f"Status change: {vals.get('status')}" or 'Manual update',
            })
```

### Appendix B: Database Scripts

```sql
-- Complete Database Setup Script for Smart Dairy Farm Management
-- Run this script to create all necessary tables and indexes

-- Enable required extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";  -- For text search

-- Create schema if not exists
CREATE SCHEMA IF NOT EXISTS farm;

-- Set search path
SET search_path TO farm, public;

-- Create sequences
CREATE SEQUENCE IF NOT EXISTS farm_animal_id_seq START 1;
CREATE SEQUENCE IF NOT EXISTS farm_health_record_id_seq START 1;

-- Run all table creation scripts (from section 4)
-- ... [Include all CREATE TABLE statements from section 4] ...

-- Create indexes for performance
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_animal_active_species 
    ON farm_animal(status, species) WHERE status = 'active';

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_milk_production_animal_date 
    ON farm_milk_production(animal_id, milking_date);

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_repro_animal_date_type 
    ON farm_reproduction_event(animal_id, event_date, event_type);

-- Create views for common queries
CREATE OR REPLACE VIEW v_animal_summary AS
SELECT 
    a.id,
    a.internal_id,
    a.name,
    a.species,
    a.animal_type,
    a.status,
    b.name as breed_name,
    l.name as location_name,
    g.name as group_name,
    a.age_display,
    a.lactation_number,
    a.days_in_milk,
    a.is_pregnant,
    a.expected_calving_date,
    a.current_milk_yield,
    a.health_status
FROM farm_animal a
LEFT JOIN farm_breed b ON a.breed_id = b.id
LEFT JOIN farm_location l ON a.location_id = l.id
LEFT JOIN farm_animal_group g ON a.group_id = g.id
WHERE a.status = 'active';

CREATE OR REPLACE VIEW v_milk_daily_summary AS
SELECT 
    milking_date,
    COUNT(DISTINCT animal_id) as animals_milked,
    SUM(milk_yield_kg) as total_milk_kg,
    AVG(milk_yield_kg) as avg_yield_kg,
    AVG(fat_percentage) as avg_fat_pct,
    AVG(protein_percentage) as avg_protein_pct,
    AVG(somatic_cell_count) as avg_scc
FROM farm_milk_production
GROUP BY milking_date
ORDER BY milking_date DESC;

-- Create functions
CREATE OR REPLACE FUNCTION calculate_age(birth_date DATE)
RETURNS INTEGER AS $$
BEGIN
    RETURN EXTRACT(YEAR FROM AGE(CURRENT_DATE, birth_date));
END;
$$ LANGUAGE plpgsql;

-- Create triggers
CREATE OR REPLACE FUNCTION update_modified_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.write_date = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_animal_modtime 
    BEFORE UPDATE ON farm_animal 
    FOR EACH ROW 
    EXECUTE FUNCTION update_modified_column();
```

### Appendix C: Mobile App Screens

```yaml
# Mobile App Screen Specifications

screens:
  - name: Dashboard
    route: /
    components:
      - MilkProductionCard
      - HeatAlertCard
      - HealthAlertCard
      - QuickActionGrid
    features:
      - Real-time metrics display
      - Pull-to-refresh
      - Offline indicator

  - name: AnimalList
    route: /animals
    components:
      - SearchBar
      - FilterChips
      - AnimalListView
      - RFIDScanButton
    features:
      - Infinite scroll pagination
      - Multi-select for bulk actions
      - Swipe actions

  - name: AnimalDetail
    route: /animals/{id}
    components:
      - AnimalHeaderCard
      - TabBar (Overview, Health, Reproduction, Milk)
      - ActionButtons
    features:
      - Pull-to-refresh
      - Photo gallery
      - Quick action sheet

  - name: MilkEntry
    route: /milk/entry
    components:
      - AnimalSelector
      - YieldInput
      - QualityInputs
      - VoiceInputButton
    features:
      - Quick entry mode
      - Batch entry
      - Barcode/RFID scan

  - name: HealthEntry
    route: /health/entry
    components:
      - SymptomSelector
      - DiagnosisInput
      - TreatmentPlan
      - PhotoCapture
    features:
      - Voice notes
      - Photo attachment
      - Treatment calculator

  - name: ReproductionEntry
    route: /reproduction/entry
    components:
      - EventTypeSelector
      - DateTimePicker
      - AI/SeminarSelection
      - CalvingDetails
    features:
      - Due date calculator
      - Semen inventory lookup
```

---

## Document Information

| Property | Value |
|----------|-------|
| **Document ID** | MS33-SFM-2026-001 |
| **Version** | 1.0 |
| **Status** | Draft |
| **Author** | Smart Dairy Development Team |
| **Created** | February 2026 |
| **Last Updated** | February 2, 2026 |
| **Review Cycle** | Weekly during implementation |
| **Approval** | Pending |

## Change Log

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-02 | Development Team | Initial document creation |

## Approvals

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Lead Developer | | | |
| Project Manager | | | |
| QA Lead | | | |
| Product Owner | | | |

---

**END OF DOCUMENT**

*This document is confidential and proprietary to Smart Dairy Technologies. Unauthorized distribution is prohibited.*
