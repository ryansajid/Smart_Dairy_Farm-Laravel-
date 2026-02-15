# PHASE 5: OPERATIONS - Farm Management Foundation
## DETAILED IMPLEMENTATION PLAN

---

**Document Control**

| Attribute | Value |
|-----------|-------|
| **Phase Number** | 5 of 15 |
| **Phase Name** | Operations - Farm Management Foundation |
| **Duration** | Days 201-250 (50 working days) |
| **Milestones** | 10 milestones × 5 days each |
| **Document Version** | 1.0 |
| **Release Date** | February 3, 2026 |
| **Dependencies** | Phase 1, 2, 3 & 4 Complete |
| **Team Size** | 3 Full-Stack Developers + 1 Farm Domain Expert |

---

## TABLE OF CONTENTS

1. [Phase Overview](#phase-overview)
2. [Phase Objectives](#phase-objectives)
3. [Key Deliverables](#key-deliverables)
4. [Milestone 1: Herd Management Module (Days 201-205)](#milestone-1-herd-management-module-days-201-205)
5. [Milestone 2: Health Management System (Days 206-210)](#milestone-2-health-management-system-days-206-210)
6. [Milestone 3: Breeding & Reproduction (Days 211-215)](#milestone-3-breeding--reproduction-days-211-215)
7. [Milestone 4: Milk Production Tracking (Days 216-220)](#milestone-4-milk-production-tracking-days-216-220)
8. [Milestone 5: Feed Management (Days 221-225)](#milestone-5-feed-management-days-221-225)
9. [Milestone 6: Farm Analytics Dashboard (Days 226-230)](#milestone-6-farm-analytics-dashboard-days-226-230)
10. [Milestone 7: Mobile App Integration (Days 231-235)](#milestone-7-mobile-app-integration-days-231-235)
11. [Milestone 8: Veterinary Module (Days 236-240)](#milestone-8-veterinary-module-days-236-240)
12. [Milestone 9: Farm Reporting System (Days 241-245)](#milestone-9-farm-reporting-system-days-241-245)
13. [Milestone 10: Phase Integration Testing (Days 246-250)](#milestone-10-phase-integration-testing-days-246-250)
14. [Phase Success Criteria](#phase-success-criteria)

---

## PHASE OVERVIEW

Phase 5 implements a comprehensive Farm Management System (FMS) that digitizes all aspects of dairy farm operations. This system enables Smart Dairy to manage multiple farms, track animal health, optimize production, and make data-driven decisions.

### Phase Context

Building on the complete ERP foundation from Phases 1-4, Phase 5 creates:
- Digital herd management for 1000+ animals
- Complete health and breeding records
- Real-time milk production tracking
- Feed optimization and inventory management
- Mobile data capture from farm workers
- Advanced analytics and reporting
- Veterinary collaboration tools

---

## PHASE OBJECTIVES

### Primary Objectives

1. **Herd Management**: Complete digital records for all animals (registration, genealogy, identification)
2. **Health Tracking**: Comprehensive medical history, vaccinations, and treatment records
3. **Breeding Excellence**: Heat detection, AI scheduling, pregnancy tracking, calving management
4. **Production Optimization**: Daily milk yield tracking, quality monitoring, lactation curves
5. **Feed Efficiency**: Ration planning, inventory management, feeding schedules
6. **Data-Driven Decisions**: Real-time analytics, KPIs, trends, and alerts
7. **Mobile Operations**: Field data entry, offline sync, voice input (Bangla)
8. **Veterinary Integration**: Vet scheduling, treatment protocols, quarantine management

### Secondary Objectives

- RFID/ear tag integration for animal identification
- Automated alerts for health issues and breeding schedules
- Multi-farm management capabilities
- Historical data analysis and benchmarking
- Regulatory compliance (livestock regulations)
- Integration with milk collection and quality systems

---

## KEY DELIVERABLES

### Herd Management Deliverables
1. ✓ Animal registration system (1000+ animals)
2. ✓ Genealogy tracking (pedigree, parents, offspring)
3. ✓ RFID/ear tag integration
4. ✓ Animal groups and categories
5. ✓ Transfer management (between farms/pens)
6. ✓ Culling and disposal tracking

### Health Management Deliverables
7. ✓ Medical records system
8. ✓ Vaccination schedule management
9. ✓ Treatment protocols
10. ✓ Disease outbreak tracking
11. ✓ Quarantine management
12. ✓ Health alerts and notifications

### Breeding Deliverables
13. ✓ Heat detection and tracking
14. ✓ AI scheduling and records
15. ✓ Pregnancy diagnosis tracking
16. ✓ Calving management
17. ✓ Bull management
18. ✓ Breeding performance analytics

### Production Deliverables
19. ✓ Daily milk yield recording
20. ✓ Lactation curve analysis
21. ✓ Quality parameter tracking (fat, protein, SCC)
22. ✓ Individual animal performance
23. ✓ Production alerts (low yield, quality issues)

### Feed Management Deliverables
24. ✓ Ration planning by animal group
25. ✓ Feed inventory management
26. ✓ Feeding schedule automation
27. ✓ Feed cost analysis
28. ✓ Nutritional requirement calculations

### Analytics & Reporting Deliverables
29. ✓ Farm KPI dashboard
30. ✓ Production trend analysis
31. ✓ Health analytics
32. ✓ Breeding performance reports
33. ✓ Feed efficiency reports
34. ✓ Financial performance tracking

### Mobile App Deliverables
35. ✓ Mobile data entry (iOS/Android)
36. ✓ Offline sync capability
37. ✓ Voice input (Bangla language)
38. ✓ Barcode/RFID scanning
39. ✓ Photo capture for documentation

---

## MILESTONE 1: Herd Management Module (Days 201-205)

**Objective**: Create comprehensive digital herd management system for animal registration, identification, genealogy tracking, and lifecycle management.

**Duration**: 5 working days

---

### Day 201: Animal Registration & Database Design

**[Dev 1] - Animal Model & Core Database (8h)**
- Create animal registration model:
  ```python
  # odoo/addons/smart_dairy_farm/models/farm_animal.py
  from odoo import models, fields, api
  from odoo.exceptions import ValidationError
  import re

  class FarmAnimal(models.Model):
      _name = 'farm.animal'
      _description = 'Farm Animal Registration'
      _inherit = ['mail.thread', 'mail.activity.mixin']
      _order = 'registration_number desc'

      # Basic Information
      name = fields.Char(string='Animal Name', required=True, tracking=True)
      registration_number = fields.Char(
          string='Registration Number',
          required=True,
          copy=False,
          readonly=True,
          default=lambda self: self._generate_registration_number()
      )
      ear_tag = fields.Char(
          string='Ear Tag Number',
          required=True,
          copy=False,
          tracking=True,
          help='Physical ear tag identifier (RFID or visual)'
      )
      rfid_tag = fields.Char(
          string='RFID Tag',
          copy=False,
          tracking=True,
          help='Electronic RFID identifier'
      )

      # Classification
      species = fields.Selection([
          ('cattle', 'Cattle'),
          ('buffalo', 'Buffalo'),
          ('goat', 'Goat'),
      ], string='Species', required=True, default='cattle')

      breed_id = fields.Many2one(
          'farm.breed',
          string='Breed',
          required=True,
          tracking=True
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
      ], string='Animal Type', required=True, compute='_compute_animal_type', store=True)

      # Dates
      date_of_birth = fields.Date(
          string='Date of Birth',
          required=True,
          tracking=True
      )
      age_months = fields.Integer(
          string='Age (Months)',
          compute='_compute_age',
          store=True
      )
      age_years = fields.Float(
          string='Age (Years)',
          compute='_compute_age',
          store=True
      )

      date_arrived = fields.Date(
          string='Date Arrived',
          default=fields.Date.today,
          tracking=True,
          help='Date when animal arrived at farm'
      )

      # Genealogy
      dam_id = fields.Many2one(
          'farm.animal',
          string='Dam (Mother)',
          domain="[('gender', '=', 'female')]",
          tracking=True
      )
      sire_id = fields.Many2one(
          'farm.animal',
          string='Sire (Father)',
          domain="[('gender', '=', 'male')]",
          tracking=True
      )
      offspring_ids = fields.One2many(
          'farm.animal',
          'dam_id',
          string='Offspring'
      )
      offspring_count = fields.Integer(
          string='Number of Offspring',
          compute='_compute_offspring_count'
      )

      # Farm Location
      farm_id = fields.Many2one(
          'farm.location',
          string='Farm',
          required=True,
          tracking=True
      )
      pen_id = fields.Many2one(
          'farm.pen',
          string='Pen/Shed',
          tracking=True,
          domain="[('farm_id', '=', farm_id)]"
      )

      # Status
      status = fields.Selection([
          ('active', 'Active'),
          ('pregnant', 'Pregnant'),
          ('sick', 'Sick'),
          ('quarantine', 'Quarantine'),
          ('sold', 'Sold'),
          ('culled', 'Culled'),
          ('dead', 'Dead'),
      ], string='Status', default='active', required=True, tracking=True)

      is_active = fields.Boolean(
          string='Is Active',
          compute='_compute_is_active',
          store=True
      )

      # Physical Attributes
      color_marking = fields.Char(string='Color/Marking')
      weight_kg = fields.Float(string='Current Weight (kg)', tracking=True)
      height_cm = fields.Float(string='Height (cm)')

      # Images
      image = fields.Binary(string='Photo', attachment=True)
      image_medium = fields.Binary(string='Medium Photo', attachment=True)
      image_small = fields.Binary(string='Small Photo', attachment=True)

      # Acquisition
      acquisition_type = fields.Selection([
          ('born', 'Born on Farm'),
          ('purchased', 'Purchased'),
          ('donated', 'Donated'),
          ('inherited', 'Inherited'),
      ], string='Acquisition Type', default='born', tracking=True)

      purchase_price = fields.Monetary(
          string='Purchase Price',
          currency_field='currency_id'
      )
      purchase_date = fields.Date(string='Purchase Date')
      vendor_id = fields.Many2one('res.partner', string='Vendor')

      # Disposal
      disposal_date = fields.Date(string='Disposal Date')
      disposal_type = fields.Selection([
          ('sold', 'Sold'),
          ('culled', 'Culled'),
          ('died', 'Died'),
          ('donated', 'Donated'),
      ], string='Disposal Type')
      disposal_price = fields.Monetary(
          string='Sale Price',
          currency_field='currency_id'
      )
      disposal_reason = fields.Text(string='Disposal Reason')

      # Related Records
      health_record_ids = fields.One2many(
          'farm.health.record',
          'animal_id',
          string='Health Records'
      )
      breeding_record_ids = fields.One2many(
          'farm.breeding.record',
          'animal_id',
          string='Breeding Records'
      )
      milk_production_ids = fields.One2many(
          'farm.milk.production',
          'animal_id',
          string='Milk Production Records'
      )

      # Company & Currency
      company_id = fields.Many2one(
          'res.company',
          string='Company',
          default=lambda self: self.env.company
      )
      currency_id = fields.Many2one(
          related='company_id.currency_id',
          string='Currency'
      )

      # Constraints
      _sql_constraints = [
          ('registration_number_unique', 'unique(registration_number)',
           'Registration number must be unique!'),
          ('ear_tag_unique', 'unique(ear_tag, company_id)',
           'Ear tag number must be unique per company!'),
          ('rfid_tag_unique', 'unique(rfid_tag, company_id)',
           'RFID tag must be unique per company!'),
      ]

      @api.model
      def _generate_registration_number(self):
          """Generate unique registration number"""
          sequence = self.env['ir.sequence'].next_by_code('farm.animal.registration')
          return sequence or 'ANM000001'

      @api.depends('date_of_birth')
      def _compute_age(self):
          """Calculate animal age in months and years"""
          from datetime import date
          for record in self:
              if record.date_of_birth:
                  today = date.today()
                  delta = today - record.date_of_birth
                  record.age_months = int(delta.days / 30.44)  # Average days per month
                  record.age_years = round(delta.days / 365.25, 1)
              else:
                  record.age_months = 0
                  record.age_years = 0.0

      @api.depends('gender', 'age_months', 'status')
      def _compute_animal_type(self):
          """Auto-categorize animal based on gender and age"""
          for record in self:
              if record.gender == 'female':
                  if record.age_months < 12:
                      record.animal_type = 'calf_female'
                  elif record.age_months < 24:
                      record.animal_type = 'dairy_heifer'
                  else:
                      record.animal_type = 'dairy_cow'
              elif record.gender == 'male':
                  if record.age_months < 12:
                      record.animal_type = 'calf_male'
                  else:
                      record.animal_type = 'bull'

      @api.depends('status')
      def _compute_is_active(self):
          """Determine if animal is actively on farm"""
          for record in self:
              record.is_active = record.status not in ['sold', 'culled', 'dead']

      @api.depends('offspring_ids')
      def _compute_offspring_count(self):
          """Count offspring"""
          for record in self:
              record.offspring_count = len(record.offspring_ids)

      @api.constrains('ear_tag')
      def _check_ear_tag_format(self):
          """Validate ear tag format"""
          for record in self:
              if record.ear_tag:
                  # Bangladesh livestock ear tag format: BD-XXXX-XXXXXX
                  if not re.match(r'^BD-\d{4}-\d{6}$', record.ear_tag):
                      raise ValidationError(
                          'Ear tag must follow Bangladesh format: BD-XXXX-XXXXXX'
                      )

      @api.constrains('date_of_birth')
      def _check_date_of_birth(self):
          """Validate date of birth is not in future"""
          from datetime import date
          for record in self:
              if record.date_of_birth > date.today():
                  raise ValidationError('Date of birth cannot be in the future!')

      @api.constrains('dam_id', 'sire_id')
      def _check_genealogy(self):
          """Prevent circular references in genealogy"""
          for record in self:
              if record.dam_id and record.dam_id.id == record.id:
                  raise ValidationError('Animal cannot be its own mother!')
              if record.sire_id and record.sire_id.id == record.id:
                  raise ValidationError('Animal cannot be its own father!')

      def action_view_offspring(self):
          """Open offspring list view"""
          return {
              'name': f'Offspring of {self.name}',
              'type': 'ir.actions.act_window',
              'res_model': 'farm.animal',
              'view_mode': 'tree,form',
              'domain': [('id', 'in', self.offspring_ids.ids)],
              'context': {'default_dam_id': self.id if self.gender == 'female' else False,
                         'default_sire_id': self.id if self.gender == 'male' else False}
          }

      def action_mark_pregnant(self):
          """Mark animal as pregnant"""
          self.write({'status': 'pregnant'})

      def action_mark_sick(self):
          """Mark animal as sick"""
          self.write({'status': 'sick'})

      def action_mark_quarantine(self):
          """Mark animal for quarantine"""
          self.write({'status': 'quarantine'})

      def action_return_to_active(self):
          """Return animal to active status"""
          self.write({'status': 'active'})
  ```
  (6h)

- Create breed master data model:
  ```python
  # odoo/addons/smart_dairy_farm/models/farm_breed.py
  from odoo import models, fields

  class FarmBreed(models.Model):
      _name = 'farm.breed'
      _description = 'Animal Breed'
      _order = 'name'

      name = fields.Char(string='Breed Name', required=True)
      code = fields.Char(string='Breed Code', required=True)
      species = fields.Selection([
          ('cattle', 'Cattle'),
          ('buffalo', 'Buffalo'),
          ('goat', 'Goat'),
      ], string='Species', required=True, default='cattle')

      origin_country = fields.Char(string='Country of Origin')
      characteristics = fields.Text(string='Breed Characteristics')

      # Production Metrics
      avg_milk_yield_liters = fields.Float(string='Avg. Daily Milk Yield (L)')
      avg_lactation_days = fields.Integer(string='Avg. Lactation Period (days)')
      avg_fat_percentage = fields.Float(string='Avg. Milk Fat %')
      avg_protein_percentage = fields.Float(string='Avg. Milk Protein %')

      # Physical Attributes
      avg_mature_weight_kg = fields.Float(string='Avg. Mature Weight (kg)')
      avg_mature_height_cm = fields.Float(string='Avg. Mature Height (cm)')

      active = fields.Boolean(default=True)

      _sql_constraints = [
          ('code_unique', 'unique(code)', 'Breed code must be unique!'),
      ]
  ```
  (2h)

**[Dev 2] - Farm Location & Pen Management (8h)**
- Create farm location model:
  ```python
  # odoo/addons/smart_dairy_farm/models/farm_location.py
  from odoo import models, fields, api

  class FarmLocation(models.Model):
      _name = 'farm.location'
      _description = 'Farm Location'
      _inherit = ['mail.thread', 'mail.activity.mixin']

      name = fields.Char(string='Farm Name', required=True, tracking=True)
      code = fields.Char(string='Farm Code', required=True, tracking=True)

      # Address
      street = fields.Char(string='Street')
      street2 = fields.Char(string='Street 2')
      city = fields.Char(string='City')
      state_id = fields.Many2one('res.country.state', string='State/Division')
      zip = fields.Char(string='Postal Code')
      country_id = fields.Many2one('res.country', string='Country', default=lambda self: self.env.ref('base.bd'))

      # GPS Coordinates
      latitude = fields.Float(string='Latitude', digits=(10, 7))
      longitude = fields.Float(string='Longitude', digits=(10, 7))

      # Farm Details
      total_area_acres = fields.Float(string='Total Area (Acres)')
      establishment_date = fields.Date(string='Establishment Date')
      farm_manager_id = fields.Many2one('res.users', string='Farm Manager')

      # Capacity
      animal_capacity = fields.Integer(string='Animal Capacity')
      current_animal_count = fields.Integer(
          string='Current Animals',
          compute='_compute_current_animal_count'
      )
      occupancy_rate = fields.Float(
          string='Occupancy Rate (%)',
          compute='_compute_occupancy_rate'
      )

      # Related
      pen_ids = fields.One2many('farm.pen', 'farm_id', string='Pens/Sheds')
      animal_ids = fields.One2many('farm.animal', 'farm_id', string='Animals')

      active = fields.Boolean(default=True)
      company_id = fields.Many2one('res.company', default=lambda self: self.env.company)

      _sql_constraints = [
          ('code_unique', 'unique(code, company_id)', 'Farm code must be unique!'),
      ]

      @api.depends('animal_ids')
      def _compute_current_animal_count(self):
          for record in self:
              record.current_animal_count = len(record.animal_ids.filtered(lambda a: a.is_active))

      @api.depends('current_animal_count', 'animal_capacity')
      def _compute_occupancy_rate(self):
          for record in self:
              if record.animal_capacity > 0:
                  record.occupancy_rate = (record.current_animal_count / record.animal_capacity) * 100
              else:
                  record.occupancy_rate = 0.0


  class FarmPen(models.Model):
      _name = 'farm.pen'
      _description = 'Farm Pen/Shed'

      name = fields.Char(string='Pen Name', required=True)
      code = fields.Char(string='Pen Code', required=True)
      farm_id = fields.Many2one('farm.location', string='Farm', required=True)

      pen_type = fields.Selection([
          ('milking', 'Milking Shed'),
          ('dry', 'Dry Cow Pen'),
          ('heifer', 'Heifer Pen'),
          ('calf', 'Calf Pen'),
          ('bull', 'Bull Pen'),
          ('sick', 'Sick Bay'),
          ('maternity', 'Maternity Pen'),
      ], string='Pen Type', required=True)

      capacity = fields.Integer(string='Capacity')
      current_count = fields.Integer(
          string='Current Animals',
          compute='_compute_current_count'
      )

      area_sqm = fields.Float(string='Area (sq meters)')

      animal_ids = fields.One2many('farm.animal', 'pen_id', string='Animals')
      active = fields.Boolean(default=True)

      _sql_constraints = [
          ('code_unique', 'unique(code, farm_id)', 'Pen code must be unique per farm!'),
      ]

      @api.depends('animal_ids')
      def _compute_current_count(self):
          for record in self:
              record.current_count = len(record.animal_ids.filtered(lambda a: a.is_active))
  ```
  (5h)

- Create initial farm and pen data (3h)

**[Dev 3] - Animal Registration UI & Views (8h)**
- Create tree view:
  ```xml
  <!-- odoo/addons/smart_dairy_farm/views/farm_animal_views.xml -->
  <?xml version="1.0" encoding="utf-8"?>
  <odoo>
      <!-- Tree View -->
      <record id="view_farm_animal_tree" model="ir.ui.view">
          <field name="name">farm.animal.tree</field>
          <field name="model">farm.animal</field>
          <field name="arch" type="xml">
              <tree string="Animals"
                    decoration-success="status == 'active'"
                    decoration-warning="status == 'pregnant'"
                    decoration-danger="status in ['sick', 'quarantine']"
                    decoration-muted="status in ['sold', 'culled', 'dead']">
                  <field name="registration_number"/>
                  <field name="name"/>
                  <field name="ear_tag"/>
                  <field name="breed_id"/>
                  <field name="gender"/>
                  <field name="animal_type"/>
                  <field name="age_years"/>
                  <field name="farm_id"/>
                  <field name="pen_id"/>
                  <field name="status" widget="badge"/>
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
                              type="object" class="oe_highlight"
                              attrs="{'invisible': [('gender', '!=', 'female')]}"/>
                      <button name="action_mark_sick" string="Mark Sick"
                              type="object"/>
                      <button name="action_mark_quarantine" string="Quarantine"
                              type="object"/>
                      <button name="action_return_to_active" string="Return to Active"
                              type="object" class="oe_highlight"
                              attrs="{'invisible': [('status', '=', 'active')]}"/>
                      <field name="status" widget="statusbar"
                             statusbar_visible="active,pregnant,sick,quarantine"/>
                  </header>
                  <sheet>
                      <field name="image" widget="image" class="oe_avatar"/>
                      <div class="oe_title">
                          <h1><field name="name" placeholder="Animal Name"/></h1>
                          <h3><field name="registration_number" readonly="1"/></h3>
                      </div>
                      <group>
                          <group string="Basic Information">
                              <field name="ear_tag"/>
                              <field name="rfid_tag"/>
                              <field name="species"/>
                              <field name="breed_id"/>
                              <field name="gender"/>
                              <field name="animal_type"/>
                          </group>
                          <group string="Age & Dates">
                              <field name="date_of_birth"/>
                              <field name="age_months" readonly="1"/>
                              <field name="age_years" readonly="1"/>
                              <field name="date_arrived"/>
                          </group>
                      </group>
                      <group>
                          <group string="Location">
                              <field name="farm_id"/>
                              <field name="pen_id"/>
                          </group>
                          <group string="Physical Attributes">
                              <field name="color_marking"/>
                              <field name="weight_kg"/>
                              <field name="height_cm"/>
                          </group>
                      </group>
                      <notebook>
                          <page string="Genealogy">
                              <group>
                                  <group string="Parents">
                                      <field name="dam_id"/>
                                      <field name="sire_id"/>
                                  </group>
                                  <group string="Offspring">
                                      <field name="offspring_count"/>
                                      <button name="action_view_offspring"
                                              string="View Offspring"
                                              type="object"
                                              class="oe_link"
                                              attrs="{'invisible': [('offspring_count', '=', 0)]}"/>
                                  </group>
                              </group>
                          </page>
                          <page string="Acquisition">
                              <group>
                                  <group>
                                      <field name="acquisition_type"/>
                                      <field name="purchase_date"
                                             attrs="{'invisible': [('acquisition_type', '!=', 'purchased')]}"/>
                                      <field name="vendor_id"
                                             attrs="{'invisible': [('acquisition_type', '!=', 'purchased')]}"/>
                                      <field name="purchase_price"
                                             attrs="{'invisible': [('acquisition_type', '!=', 'purchased')]}"/>
                                  </group>
                              </group>
                          </page>
                          <page string="Disposal"
                                attrs="{'invisible': [('status', 'not in', ['sold', 'culled', 'dead'])]}">
                              <group>
                                  <group>
                                      <field name="disposal_date"/>
                                      <field name="disposal_type"/>
                                      <field name="disposal_price"/>
                                  </group>
                                  <group>
                                      <field name="disposal_reason"/>
                                  </group>
                              </group>
                          </page>
                          <page string="Health Records">
                              <field name="health_record_ids">
                                  <tree>
                                      <field name="date"/>
                                      <field name="record_type"/>
                                      <field name="diagnosis"/>
                                      <field name="treatment"/>
                                  </tree>
                              </field>
                          </page>
                          <page string="Breeding Records">
                              <field name="breeding_record_ids">
                                  <tree>
                                      <field name="breeding_date"/>
                                      <field name="breeding_type"/>
                                      <field name="bull_id"/>
                                      <field name="pregnancy_status"/>
                                  </tree>
                              </field>
                          </page>
                          <page string="Milk Production">
                              <field name="milk_production_ids">
                                  <tree>
                                      <field name="date"/>
                                      <field name="morning_yield"/>
                                      <field name="evening_yield"/>
                                      <field name="total_yield"/>
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

      <!-- Search View -->
      <record id="view_farm_animal_search" model="ir.ui.view">
          <field name="name">farm.animal.search</field>
          <field name="model">farm.animal</field>
          <field name="arch" type="xml">
              <search>
                  <field name="name"/>
                  <field name="registration_number"/>
                  <field name="ear_tag"/>
                  <field name="breed_id"/>
                  <filter name="active_animals" string="Active"
                          domain="[('is_active', '=', True)]"/>
                  <filter name="pregnant" string="Pregnant"
                          domain="[('status', '=', 'pregnant')]"/>
                  <filter name="sick" string="Sick"
                          domain="[('status', '=', 'sick')]"/>
                  <separator/>
                  <filter name="dairy_cows" string="Dairy Cows"
                          domain="[('animal_type', '=', 'dairy_cow')]"/>
                  <filter name="heifers" string="Heifers"
                          domain="[('animal_type', '=', 'dairy_heifer')]"/>
                  <filter name="calves" string="Calves"
                          domain="[('animal_type', 'in', ['calf_female', 'calf_male'])]"/>
                  <group expand="0" string="Group By">
                      <filter name="group_farm" string="Farm"
                              context="{'group_by': 'farm_id'}"/>
                      <filter name="group_breed" string="Breed"
                              context="{'group_by': 'breed_id'}"/>
                      <filter name="group_status" string="Status"
                              context="{'group_by': 'status'}"/>
                      <filter name="group_type" string="Animal Type"
                              context="{'group_by': 'animal_type'}"/>
                  </group>
              </search>
          </field>
      </record>

      <!-- Action -->
      <record id="action_farm_animal" model="ir.actions.act_window">
          <field name="name">Animals</field>
          <field name="res_model">farm.animal</field>
          <field name="view_mode">tree,form,kanban</field>
          <field name="context">{'search_default_active_animals': 1}</field>
          <field name="help" type="html">
              <p class="o_view_nocontent_smiling_face">
                  Register a new animal
              </p>
              <p>
                  Create comprehensive records for all farm animals including identification,
                  genealogy, health, and production data.
              </p>
          </field>
      </record>
  </odoo>
  ```
  (6h)

- Create menu items and sequence (2h)

---

### Day 202: RFID Integration & Identification System

**[Dev 1] - RFID Hardware Integration (8h)**
- Create RFID reader integration module:
  ```python
  # odoo/addons/smart_dairy_farm/controllers/rfid_reader.py
  from odoo import http
  from odoo.http import request
  import json
  import logging

  _logger = logging.getLogger(__name__)

  class RFIDReaderController(http.Controller):

      @http.route('/farm/rfid/scan', type='json', auth='user', methods=['POST'])
      def scan_rfid_tag(self, rfid_tag, **kwargs):
          """
          Endpoint for RFID reader devices to register scans

          Expected payload:
          {
              "rfid_tag": "123456789ABCDEF",
              "reader_id": "READER_001",
              "timestamp": "2026-02-03T10:30:00",
              "location": "GATE_01"
          }
          """
          try:
              # Find animal by RFID tag
              animal = request.env['farm.animal'].sudo().search([
                  ('rfid_tag', '=', rfid_tag),
                  ('is_active', '=', True)
              ], limit=1)

              if not animal:
                  return {
                      'status': 'error',
                      'message': f'No active animal found with RFID tag: {rfid_tag}'
                  }

              # Log RFID scan event
              request.env['farm.rfid.scan'].sudo().create({
                  'animal_id': animal.id,
                  'rfid_tag': rfid_tag,
                  'reader_id': kwargs.get('reader_id'),
                  'scan_timestamp': kwargs.get('timestamp'),
                  'location': kwargs.get('location'),
              })

              return {
                  'status': 'success',
                  'animal': {
                      'id': animal.id,
                      'name': animal.name,
                      'registration_number': animal.registration_number,
                      'ear_tag': animal.ear_tag,
                      'breed': animal.breed_id.name,
                      'age_months': animal.age_months,
                      'status': animal.status,
                      'farm': animal.farm_id.name,
                      'pen': animal.pen_id.name if animal.pen_id else None,
                  }
              }
          except Exception as e:
              _logger.error(f'RFID scan error: {str(e)}')
              return {
                  'status': 'error',
                  'message': str(e)
              }

      @http.route('/farm/rfid/bulk_scan', type='json', auth='user', methods=['POST'])
      def bulk_scan_rfid(self, scans, **kwargs):
          """
          Bulk RFID scan processing for high-speed gates

          Expected payload:
          {
              "scans": [
                  {"rfid_tag": "TAG001", "timestamp": "2026-02-03T10:30:00"},
                  {"rfid_tag": "TAG002", "timestamp": "2026-02-03T10:30:05"}
              ],
              "reader_id": "READER_001",
              "location": "MILKING_PARLOR"
          }
          """
          results = []
          for scan in scans:
              result = self.scan_rfid_tag(
                  rfid_tag=scan['rfid_tag'],
                  reader_id=kwargs.get('reader_id'),
                  timestamp=scan.get('timestamp'),
                  location=kwargs.get('location')
              )
              results.append(result)

          return {
              'status': 'success',
              'total_scans': len(scans),
              'successful': len([r for r in results if r['status'] == 'success']),
              'failed': len([r for r in results if r['status'] == 'error']),
              'results': results
          }


  class FarmRFIDScan(http.Model):
      _name = 'farm.rfid.scan'
      _description = 'RFID Scan Log'
      _order = 'scan_timestamp desc'

      animal_id = fields.Many2one('farm.animal', string='Animal', required=True)
      rfid_tag = fields.Char(string='RFID Tag', required=True)
      reader_id = fields.Char(string='Reader ID')
      scan_timestamp = fields.Datetime(string='Scan Time', default=fields.Datetime.now)
      location = fields.Char(string='Scan Location')
      event_type = fields.Selection([
          ('entry', 'Entry'),
          ('exit', 'Exit'),
          ('milking', 'Milking'),
          ('feeding', 'Feeding'),
          ('weighing', 'Weighing'),
          ('unknown', 'Unknown'),
      ], string='Event Type', default='unknown')

      company_id = fields.Many2one('res.company', default=lambda self: self.env.company)
  ```
  (5h)

- Create barcode/QR code generator for ear tags:
  ```python
  # odoo/addons/smart_dairy_farm/models/farm_animal.py (add to FarmAnimal class)

  from odoo import api, models, fields
  import barcode
  from barcode.writer import ImageWriter
  import qrcode
  from io import BytesIO
  import base64

  # Add to FarmAnimal model:

  barcode_image = fields.Binary(
      string='Barcode',
      compute='_compute_barcode_image',
      store=False
  )

  qr_code_image = fields.Binary(
      string='QR Code',
      compute='_compute_qr_code_image',
      store=False
  )

  @api.depends('ear_tag')
  def _compute_barcode_image(self):
      """Generate barcode image for ear tag"""
      for record in self:
          if record.ear_tag:
              # Generate Code128 barcode
              barcode_class = barcode.get_barcode_class('code128')
              barcode_instance = barcode_class(record.ear_tag, writer=ImageWriter())

              buffer = BytesIO()
              barcode_instance.write(buffer)
              record.barcode_image = base64.b64encode(buffer.getvalue())
          else:
              record.barcode_image = False

  @api.depends('registration_number', 'ear_tag', 'rfid_tag')
  def _compute_qr_code_image(self):
      """Generate QR code with animal information"""
      for record in self:
          if record.registration_number:
              # Create QR code with JSON data
              qr_data = {
                  'reg_no': record.registration_number,
                  'ear_tag': record.ear_tag,
                  'rfid': record.rfid_tag or '',
                  'name': record.name,
                  'breed': record.breed_id.code,
              }

              qr = qrcode.QRCode(version=1, box_size=10, border=4)
              qr.add_data(json.dumps(qr_data))
              qr.make(fit=True)

              img = qr.make_image(fill_color="black", back_color="white")
              buffer = BytesIO()
              img.save(buffer, format='PNG')
              record.qr_code_image = base64.b64encode(buffer.getvalue())
          else:
              record.qr_code_image = False

  def action_print_ear_tag(self):
      """Print ear tag with barcode/QR code"""
      return self.env.ref('smart_dairy_farm.action_report_ear_tag').report_action(self)
  ```
  (3h)

**[Dev 2] - Ear Tag Printing System (8h)**
- Create ear tag print template:
  ```xml
  <!-- odoo/addons/smart_dairy_farm/reports/ear_tag_report.xml -->
  <?xml version="1.0" encoding="utf-8"?>
  <odoo>
      <template id="report_ear_tag_document">
          <t t-call="web.html_container">
              <t t-foreach="docs" t-as="animal">
                  <div class="page" style="width: 60mm; height: 40mm; border: 1px solid black; padding: 2mm;">
                      <div style="text-align: center;">
                          <h3 style="margin: 0; font-size: 14pt;">
                              <t t-esc="animal.farm_id.name"/>
                          </h3>
                          <div style="margin: 2mm 0;">
                              <img t-att-src="'data:image/png;base64,%s' % animal.qr_code_image"
                                   style="width: 25mm; height: 25mm;"/>
                          </div>
                          <div style="font-size: 10pt; font-weight: bold;">
                              <t t-esc="animal.ear_tag"/>
                          </div>
                          <div style="font-size: 8pt;">
                              <t t-esc="animal.name"/> | <t t-esc="animal.registration_number"/>
                          </div>
                          <div style="font-size: 8pt;">
                              <t t-esc="animal.breed_id.name"/>
                          </div>
                      </div>
                  </div>
              </t>
          </t>
      </template>

      <record id="action_report_ear_tag" model="ir.actions.report">
          <field name="name">Ear Tag</field>
          <field name="model">farm.animal</field>
          <field name="report_type">qweb-pdf</field>
          <field name="report_name">smart_dairy_farm.report_ear_tag_document</field>
          <field name="report_file">smart_dairy_farm.report_ear_tag_document</field>
          <field name="binding_model_id" ref="model_farm_animal"/>
          <field name="binding_type">report</field>
      </record>
  </odoo>
  ```
  (4h)

- Create bulk ear tag printing wizard (4h)

**[Dev 3] - Animal Transfer & Movement Tracking (8h)**
- Create animal transfer wizard:
  ```python
  # odoo/addons/smart_dairy_farm/wizard/animal_transfer_wizard.py
  from odoo import models, fields, api
  from odoo.exceptions import ValidationError

  class AnimalTransferWizard(models.TransientModel):
      _name = 'animal.transfer.wizard'
      _description = 'Animal Transfer Wizard'

      animal_ids = fields.Many2many(
          'farm.animal',
          string='Animals to Transfer',
          required=True,
          domain="[('is_active', '=', True)]"
      )

      transfer_type = fields.Selection([
          ('farm', 'Farm Transfer'),
          ('pen', 'Pen Transfer'),
          ('both', 'Farm & Pen Transfer'),
      ], string='Transfer Type', required=True, default='pen')

      from_farm_id = fields.Many2one(
          'farm.location',
          string='From Farm',
          compute='_compute_from_location',
          store=True
      )

      to_farm_id = fields.Many2one(
          'farm.location',
          string='To Farm',
          required=True
      )

      from_pen_id = fields.Many2one(
          'farm.pen',
          string='From Pen',
          compute='_compute_from_location',
          store=True
      )

      to_pen_id = fields.Many2one(
          'farm.pen',
          string='To Pen',
          required=True,
          domain="[('farm_id', '=', to_farm_id)]"
      )

      transfer_date = fields.Datetime(
          string='Transfer Date',
          default=fields.Datetime.now,
          required=True
      )

      reason = fields.Text(string='Reason for Transfer')
      notes = fields.Text(string='Additional Notes')

      @api.depends('animal_ids')
      def _compute_from_location(self):
          for wizard in self:
              if wizard.animal_ids:
                  first_animal = wizard.animal_ids[0]
                  wizard.from_farm_id = first_animal.farm_id
                  wizard.from_pen_id = first_animal.pen_id
              else:
                  wizard.from_farm_id = False
                  wizard.from_pen_id = False

      @api.constrains('animal_ids', 'to_pen_id')
      def _check_pen_capacity(self):
          """Check if destination pen has sufficient capacity"""
          for wizard in self:
              if wizard.to_pen_id:
                  available_capacity = wizard.to_pen_id.capacity - wizard.to_pen_id.current_count
                  transfer_count = len(wizard.animal_ids)

                  if transfer_count > available_capacity:
                      raise ValidationError(
                          f'Insufficient capacity in {wizard.to_pen_id.name}. '
                          f'Available: {available_capacity}, Required: {transfer_count}'
                      )

      def action_transfer_animals(self):
          """Execute animal transfer"""
          self.ensure_one()

          # Create transfer record
          transfer = self.env['farm.animal.transfer'].create({
              'from_farm_id': self.from_farm_id.id,
              'to_farm_id': self.to_farm_id.id,
              'from_pen_id': self.from_pen_id.id if self.from_pen_id else False,
              'to_pen_id': self.to_pen_id.id,
              'transfer_date': self.transfer_date,
              'reason': self.reason,
              'notes': self.notes,
              'animal_ids': [(6, 0, self.animal_ids.ids)],
          })

          # Update animal locations
          self.animal_ids.write({
              'farm_id': self.to_farm_id.id,
              'pen_id': self.to_pen_id.id,
          })

          # Post message to animal chatter
          for animal in self.animal_ids:
              animal.message_post(
                  body=f'Transferred from {self.from_farm_id.name}/{self.from_pen_id.name or "N/A"} '
                       f'to {self.to_farm_id.name}/{self.to_pen_id.name} on {self.transfer_date}. '
                       f'Reason: {self.reason or "Not specified"}',
                  subject='Animal Transfer'
              )

          return {
              'type': 'ir.actions.act_window',
              'res_model': 'farm.animal.transfer',
              'res_id': transfer.id,
              'view_mode': 'form',
              'target': 'current',
          }


  class FarmAnimalTransfer(models.Model):
      _name = 'farm.animal.transfer'
      _description = 'Animal Transfer Record'
      _order = 'transfer_date desc'

      name = fields.Char(string='Transfer Reference', readonly=True, default='New')

      from_farm_id = fields.Many2one('farm.location', string='From Farm', required=True)
      to_farm_id = fields.Many2one('farm.location', string='To Farm', required=True)
      from_pen_id = fields.Many2one('farm.pen', string='From Pen')
      to_pen_id = fields.Many2one('farm.pen', string='To Pen', required=True)

      transfer_date = fields.Datetime(string='Transfer Date', required=True)
      animal_ids = fields.Many2many('farm.animal', string='Animals Transferred')
      animal_count = fields.Integer(string='Number of Animals', compute='_compute_animal_count')

      reason = fields.Text(string='Reason')
      notes = fields.Text(string='Notes')

      state = fields.Selection([
          ('draft', 'Draft'),
          ('confirmed', 'Confirmed'),
          ('cancelled', 'Cancelled'),
      ], default='confirmed', string='Status')

      created_by_id = fields.Many2one('res.users', string='Created By', default=lambda self: self.env.user)
      company_id = fields.Many2one('res.company', default=lambda self: self.env.company)

      @api.model
      def create(self, vals):
          if vals.get('name', 'New') == 'New':
              vals['name'] = self.env['ir.sequence'].next_by_code('farm.animal.transfer') or 'New'
          return super().create(vals)

      @api.depends('animal_ids')
      def _compute_animal_count(self):
          for record in self:
              record.animal_count = len(record.animal_ids)
  ```
  (6h)

- Create movement history tracking view (2h)

---

### Day 203: Genealogy & Family Tree

**[Dev 1] - Pedigree Analysis System (8h)**
- Create pedigree computation engine:
  ```python
  # odoo/addons/smart_dairy_farm/models/farm_pedigree.py
  from odoo import models, fields, api, tools

  class FarmAnimal(models.Model):
      _inherit = 'farm.animal'

      # Pedigree Fields
      pedigree_generation = fields.Integer(
          string='Generation',
          compute='_compute_pedigree_generation',
          store=True,
          help='Generation number in farm genealogy'
      )

      inbreeding_coefficient = fields.Float(
          string='Inbreeding Coefficient (%)',
          compute='_compute_inbreeding_coefficient',
          help='Percentage of inbreeding'
      )

      maternal_lineage = fields.Char(
          string='Maternal Lineage',
          compute='_compute_lineages',
          help='Mother\'s ancestry line'
      )

      paternal_lineage = fields.Char(
          string='Paternal Lineage',
          compute='_compute_lineages',
          help='Father\'s ancestry line'
      )

      @api.depends('dam_id', 'sire_id')
      def _compute_pedigree_generation(self):
          """Calculate generation number based on parents"""
          for animal in self:
              if animal.dam_id or animal.sire_id:
                  dam_gen = animal.dam_id.pedigree_generation if animal.dam_id else 0
                  sire_gen = animal.sire_id.pedigree_generation if animal.sire_id else 0
                  animal.pedigree_generation = max(dam_gen, sire_gen) + 1
              else:
                  animal.pedigree_generation = 1  # Founding animal

      def _get_ancestors(self, generations=3):
          """Recursively get ancestors up to N generations"""
          ancestors = []

          def get_parent_ancestors(animal, current_gen, max_gen):
              if current_gen > max_gen or not animal:
                  return

              ancestors.append({
                  'animal': animal,
                  'generation': current_gen,
                  'lineage': 'maternal' if animal.id == self.dam_id.id else 'paternal'
              })

              if animal.dam_id:
                  get_parent_ancestors(animal.dam_id, current_gen + 1, max_gen)
              if animal.sire_id:
                  get_parent_ancestors(animal.sire_id, current_gen + 1, max_gen)

          if self.dam_id:
              get_parent_ancestors(self.dam_id, 1, generations)
          if self.sire_id:
              get_parent_ancestors(self.sire_id, 1, generations)

          return ancestors

      @api.depends('dam_id', 'sire_id')
      def _compute_inbreeding_coefficient(self):
          """
          Calculate inbreeding coefficient using Wright's formula
          F = Σ[(1/2)^n * (1 + F_a)]
          where n is the number of generations between parents through common ancestor
          """
          for animal in self:
              if not (animal.dam_id and animal.sire_id):
                  animal.inbreeding_coefficient = 0.0
                  continue

              # Get ancestors from both parents
              dam_ancestors = set(a['animal'].id for a in animal.dam_id._get_ancestors(5))
              sire_ancestors = set(a['animal'].id for a in animal.sire_id._get_ancestors(5))

              # Find common ancestors
              common_ancestors = dam_ancestors & sire_ancestors

              if common_ancestors:
                  # Simplified inbreeding calculation
                  # In production, implement full Wright's coefficient
                  animal.inbreeding_coefficient = len(common_ancestors) * 6.25  # Each common ancestor = ~6.25% inbreeding
              else:
                  animal.inbreeding_coefficient = 0.0

      @api.depends('dam_id', 'sire_id')
      def _compute_lineages(self):
          """Generate maternal and paternal lineage strings"""
          for animal in self:
              # Maternal lineage
              maternal = []
              current = animal.dam_id
              for _ in range(5):  # 5 generations
                  if not current:
                      break
                  maternal.append(current.name)
                  current = current.dam_id
              animal.maternal_lineage = ' > '.join(maternal) if maternal else 'Unknown'

              # Paternal lineage
              paternal = []
              current = animal.sire_id
              for _ in range(5):
                  if not current:
                      break
                  paternal.append(current.name)
                  current = current.sire_id
              animal.paternal_lineage = ' > '.join(paternal) if paternal else 'Unknown'

      def action_view_family_tree(self):
          """Open interactive family tree view"""
          return {
              'name': f'Family Tree - {self.name}',
              'type': 'ir.actions.act_window',
              'res_model': 'farm.animal',
              'view_mode': 'graph,tree',
              'domain': [('id', 'in', self._get_family_members())],
              'context': {
                  'graph_mode': 'tree',
                  'graph_groupbys': ['dam_id', 'sire_id'],
              }
          }

      def _get_family_members(self):
          """Get all related family members (ancestors + offspring)"""
          family = set([self.id])

          # Add ancestors
          ancestors = self._get_ancestors(generations=5)
          family.update(a['animal'].id for a in ancestors)

          # Add all offspring recursively
          def add_descendants(animal):
              if animal.offspring_ids:
                  for offspring in animal.offspring_ids:
                      family.add(offspring.id)
                      add_descendants(offspring)

          add_descendants(self)

          return list(family)

      def action_generate_pedigree_report(self):
          """Generate printable pedigree certificate"""
          return self.env.ref('smart_dairy_farm.action_report_pedigree').report_action(self)
  ```
  (6h)

- Create breeding value estimation model (2h)

**[Dev 2] - Family Tree Visualization (8h)**
- Create interactive family tree using JavaScript:
  ```javascript
  // odoo/addons/smart_dairy_farm/static/src/js/family_tree_widget.js
  odoo.define('smart_dairy_farm.FamilyTreeWidget', function (require) {
      'use strict';

      const AbstractField = require('web.AbstractField');
      const fieldRegistry = require('web.field_registry');
      const core = require('web.core');

      const FamilyTreeWidget = AbstractField.extend({
          template: 'FamilyTreeWidget',
          jsLibs: [
              '/smart_dairy_farm/static/lib/d3/d3.min.js',
          ],

          start: function () {
              this._super.apply(this, arguments);
              this._renderFamilyTree();
          },

          _renderFamilyTree: function () {
              const self = this;
              const animalId = this.res_id;

              // Fetch family tree data via RPC
              this._rpc({
                  model: 'farm.animal',
                  method: 'get_family_tree_data',
                  args: [animalId],
              }).then(function (data) {
                  self._drawTree(data);
              });
          },

          _drawTree: function (treeData) {
              const width = 960;
              const height = 600;

              const svg = d3.select(this.$el.find('.family-tree-container')[0])
                  .append('svg')
                  .attr('width', width)
                  .attr('height', height);

              const g = svg.append('g')
                  .attr('transform', 'translate(40,0)');

              const tree = d3.tree()
                  .size([height, width - 160]);

              const root = d3.hierarchy(treeData);
              tree(root);

              // Links
              g.selectAll('.link')
                  .data(root.links())
                  .enter().append('path')
                  .attr('class', 'link')
                  .attr('d', d3.linkHorizontal()
                      .x(d => d.y)
                      .y(d => d.x));

              // Nodes
              const node = g.selectAll('.node')
                  .data(root.descendants())
                  .enter().append('g')
                  .attr('class', d => 'node' + (d.children ? ' node--internal' : ' node--leaf'))
                  .attr('transform', d => `translate(${d.y},${d.x})`);

              node.append('circle')
                  .attr('r', 5)
                  .style('fill', d => d.data.gender === 'female' ? '#ff69b4' : '#4169e1');

              node.append('text')
                  .attr('dy', 3)
                  .attr('x', d => d.children ? -8 : 8)
                  .style('text-anchor', d => d.children ? 'end' : 'start')
                  .text(d => d.data.name);
          },
      });

      fieldRegistry.add('family_tree_widget', FamilyTreeWidget);

      return FamilyTreeWidget;
  });
  ```
  (5h)

- Add widget to form view (1h)
- Create pedigree print template (2h)

**[Dev 3] - Genetic Performance Tracking (8h)**
- Create genetic traits model:
  ```python
  # odoo/addons/smart_dairy_farm/models/farm_genetic_traits.py
  from odoo import models, fields, api

  class FarmGeneticTrait(models.Model):
      _name = 'farm.genetic.trait'
      _description = 'Genetic Trait'

      name = fields.Char(string='Trait Name', required=True)
      code = fields.Char(string='Code', required=True)
      category = fields.Selection([
          ('production', 'Production'),
          ('health', 'Health'),
          ('fertility', 'Fertility'),
          ('conformation', 'Conformation'),
      ], string='Category', required=True)

      unit = fields.Char(string='Unit of Measurement')
      description = fields.Text(string='Description')
      heritability = fields.Float(string='Heritability (%)', help='Percentage of trait variation due to genetics')

      active = fields.Boolean(default=True)


  class FarmAnimalGenetics(models.Model):
      _name = 'farm.animal.genetics'
      _description = 'Animal Genetic Performance'
      _rec_name = 'animal_id'

      animal_id = fields.Many2one('farm.animal', string='Animal', required=True)
      trait_id = fields.Many2one('farm.genetic.trait', string='Genetic Trait', required=True)

      estimated_breeding_value = fields.Float(
          string='EBV (Estimated Breeding Value)',
          help='Genetic merit estimate for this trait'
      )

      phenotypic_value = fields.Float(
          string='Phenotypic Value',
          help='Actual observed performance'
      )

      reliability = fields.Float(
          string='Reliability (%)',
          help='Confidence in EBV estimate (0-100%)'
      )

      evaluation_date = fields.Date(string='Evaluation Date', default=fields.Date.today)
      notes = fields.Text(string='Notes')

      company_id = fields.Many2one('res.company', default=lambda self: self.env.company)
  ```
  (4h)

- Create genetic evaluation reports (4h)

---

### Day 204: Animal Groups & Batch Management

**[Dev 1] - Animal Groups Module (8h)**
- Create animal group model:
  ```python
  # odoo/addons/smart_dairy_farm/models/farm_animal_group.py
  from odoo import models, fields, api, _
  from odoo.exceptions import ValidationError

  class FarmAnimalGroup(models.Model):
      _name = 'farm.animal.group'
      _description = 'Animal Group'
      _inherit = ['mail.thread', 'mail.activity.mixin']

      name = fields.Char(string='Group Name', required=True, tracking=True)
      code = fields.Char(string='Group Code', required=True, tracking=True)

      group_type = fields.Selection([
          ('lactation', 'Lactation Group'),
          ('age', 'Age Group'),
          ('production', 'Production Level'),
          ('health', 'Health Status'),
          ('breeding', 'Breeding Group'),
          ('custom', 'Custom Group'),
      ], string='Group Type', required=True, tracking=True)

      description = fields.Text(string='Description')

      # Group Criteria
      auto_assign = fields.Boolean(
          string='Auto-assign Animals',
          default=False,
          help='Automatically assign animals based on criteria'
      )

      criteria_age_min = fields.Integer(string='Min Age (months)')
      criteria_age_max = fields.Integer(string='Max Age (months)')

      criteria_production_min = fields.Float(string='Min Daily Production (L)')
      criteria_production_max = fields.Float(string='Max Daily Production (L)')

      criteria_lactation_number = fields.Integer(string='Lactation Number')
      criteria_days_in_milk_min = fields.Integer(string='Min Days in Milk')
      criteria_days_in_milk_max = fields.Integer(string='Max Days in Milk')

      criteria_status = fields.Selection([
          ('active', 'Active'),
          ('pregnant', 'Pregnant'),
          ('sick', 'Sick'),
          ('quarantine', 'Quarantine'),
      ], string='Status Criteria')

      # Members
      animal_ids = fields.Many2many(
          'farm.animal',
          'farm_animal_group_rel',
          'group_id',
          'animal_id',
          string='Animals'
      )

      animal_count = fields.Integer(
          string='Number of Animals',
          compute='_compute_animal_count',
          store=True
      )

      # Management
      farm_id = fields.Many2one('farm.location', string='Farm')
      pen_id = fields.Many2one('farm.pen', string='Default Pen')

      manager_id = fields.Many2one('res.users', string='Group Manager')

      # Feeding
      feed_ration_id = fields.Many2one('farm.feed.ration', string='Feed Ration')
      feeding_schedule = fields.Text(string='Feeding Schedule')

      # Statistics
      avg_age_months = fields.Float(
          string='Average Age (months)',
          compute='_compute_statistics'
      )
      avg_milk_production = fields.Float(
          string='Avg. Daily Milk (L)',
          compute='_compute_statistics'
      )
      total_milk_production = fields.Float(
          string='Total Daily Milk (L)',
          compute='_compute_statistics'
      )

      active = fields.Boolean(default=True)
      company_id = fields.Many2one('res.company', default=lambda self: self.env.company)

      _sql_constraints = [
          ('code_unique', 'unique(code, company_id)', 'Group code must be unique!'),
      ]

      @api.depends('animal_ids')
      def _compute_animal_count(self):
          for group in self:
              group.animal_count = len(group.animal_ids)

      @api.depends('animal_ids', 'animal_ids.age_months', 'animal_ids.milk_production_ids')
      def _compute_statistics(self):
          for group in self:
              if group.animal_ids:
                  # Average age
                  group.avg_age_months = sum(group.animal_ids.mapped('age_months')) / len(group.animal_ids)

                  # Milk production (from last 7 days average)
                  from datetime import datetime, timedelta
                  seven_days_ago = (datetime.now() - timedelta(days=7)).date()

                  total_production = 0
                  for animal in group.animal_ids:
                      recent_production = animal.milk_production_ids.filtered(
                          lambda p: p.date >= seven_days_ago
                      )
                      if recent_production:
                          avg = sum(recent_production.mapped('total_yield')) / len(recent_production)
                          total_production += avg

                  group.total_milk_production = total_production
                  group.avg_milk_production = total_production / len(group.animal_ids) if group.animal_ids else 0
              else:
                  group.avg_age_months = 0
                  group.avg_milk_production = 0
                  group.total_milk_production = 0

      @api.model
      def create(self, vals):
          group = super().create(vals)
          if group.auto_assign:
              group._auto_assign_animals()
          return group

      def write(self, vals):
          res = super().write(vals)
          if vals.get('auto_assign') or any(k.startswith('criteria_') for k in vals.keys()):
              for group in self:
                  if group.auto_assign:
                      group._auto_assign_animals()
          return res

      def _auto_assign_animals(self):
          """Automatically assign animals based on criteria"""
          self.ensure_one()

          domain = [('is_active', '=', True)]

          # Age criteria
          if self.criteria_age_min:
              domain.append(('age_months', '>=', self.criteria_age_min))
          if self.criteria_age_max:
              domain.append(('age_months', '<=', self.criteria_age_max))

          # Status criteria
          if self.criteria_status:
              domain.append(('status', '=', self.criteria_status))

          # Farm criteria
          if self.farm_id:
              domain.append(('farm_id', '=', self.farm_id.id))

          # Find matching animals
          matching_animals = self.env['farm.animal'].search(domain)

          # Additional production criteria (requires computation)
          if self.criteria_production_min or self.criteria_production_max:
              filtered_animals = []
              for animal in matching_animals:
                  avg_production = animal._get_avg_daily_production(days=7)
                  if self.criteria_production_min and avg_production < self.criteria_production_min:
                      continue
                  if self.criteria_production_max and avg_production > self.criteria_production_max:
                      continue
                  filtered_animals.append(animal.id)
              matching_animals = self.env['farm.animal'].browse(filtered_animals)

          # Update group membership
          self.animal_ids = [(6, 0, matching_animals.ids)]

      def action_reassign_animals(self):
          """Manually trigger auto-assignment"""
          for group in self:
              if group.auto_assign:
                  group._auto_assign_animals()
          return True

      def action_view_animals(self):
          """View animals in this group"""
          return {
              'name': f'{self.name} - Animals',
              'type': 'ir.actions.act_window',
              'res_model': 'farm.animal',
              'view_mode': 'tree,form',
              'domain': [('id', 'in', self.animal_ids.ids)],
              'context': {'default_group_ids': [(4, self.id)]},
          }
  ```
  (6h)

- Create group assignment wizard (2h)

**[Dev 2] - Batch Operations (8h)**
- Create batch operations wizard:
  ```python
  # odoo/addons/smart_dairy_farm/wizard/batch_operation_wizard.py
  from odoo import models, fields, api
  from odoo.exceptions import UserError

  class BatchOperationWizard(models.TransientModel):
      _name = 'batch.operation.wizard'
      _description = 'Batch Operations on Animals'

      operation_type = fields.Selection([
          ('vaccinate', 'Vaccination'),
          ('treat', 'Treatment'),
          ('weigh', 'Weighing'),
          ('pregnancy_check', 'Pregnancy Check'),
          ('dehorn', 'Dehorning'),
          ('tag', 'Tagging/Re-tagging'),
          ('transfer', 'Transfer'),
          ('change_status', 'Change Status'),
      ], string='Operation Type', required=True)

      animal_ids = fields.Many2many(
          'farm.animal',
          string='Selected Animals',
          required=True
      )
      animal_count = fields.Integer(
          string='Number of Animals',
          compute='_compute_animal_count'
      )

      # Vaccination fields
      vaccine_id = fields.Many2one('farm.vaccine', string='Vaccine')
      vaccination_date = fields.Date(string='Vaccination Date', default=fields.Date.today)
      next_dose_date = fields.Date(string='Next Dose Date')

      # Treatment fields
      treatment_type = fields.Selection([
          ('antibiotic', 'Antibiotic'),
          ('deworming', 'Deworming'),
          ('vitamin', 'Vitamin/Supplement'),
          ('other', 'Other'),
      ], string='Treatment Type')
      medicine_id = fields.Many2one('farm.medicine', string='Medicine')
      dosage = fields.Char(string='Dosage')

      # Weighing fields
      weight_kg = fields.Float(string='Weight (kg)')
      weight_date = fields.Date(string='Weighing Date', default=fields.Date.today)

      # Status change
      new_status = fields.Selection([
          ('active', 'Active'),
          ('pregnant', 'Pregnant'),
          ('sick', 'Sick'),
          ('quarantine', 'Quarantine'),
          ('sold', 'Sold'),
          ('culled', 'Culled'),
      ], string='New Status')

      # Common fields
      notes = fields.Text(string='Notes')
      performed_by_id = fields.Many2one('res.users', string='Performed By', default=lambda self: self.env.user)

      @api.depends('animal_ids')
      def _compute_animal_count(self):
          for wizard in self:
              wizard.animal_count = len(wizard.animal_ids)

      def action_execute_batch(self):
          """Execute the batch operation"""
          self.ensure_one()

          if self.operation_type == 'vaccinate':
              return self._batch_vaccinate()
          elif self.operation_type == 'treat':
              return self._batch_treat()
          elif self.operation_type == 'weigh':
              return self._batch_weigh()
          elif self.operation_type == 'change_status':
              return self._batch_change_status()
          # Add other operations...

          return True

      def _batch_vaccinate(self):
          """Batch vaccination"""
          vaccination_records = []
          for animal in self.animal_ids:
              vaccination_records.append({
                  'animal_id': animal.id,
                  'vaccine_id': self.vaccine_id.id,
                  'vaccination_date': self.vaccination_date,
                  'next_dose_date': self.next_dose_date,
                  'notes': self.notes,
                  'administered_by_id': self.performed_by_id.id,
              })

          self.env['farm.vaccination'].create(vaccination_records)

          return {
              'type': 'ir.actions.client',
              'tag': 'display_notification',
              'params': {
                  'title': 'Batch Vaccination Complete',
                  'message': f'Vaccinated {len(self.animal_ids)} animals with {self.vaccine_id.name}',
                  'type': 'success',
                  'sticky': False,
              }
          }

      def _batch_weigh(self):
          """Batch weighing"""
          # For batch weighing, we typically update individual weights
          # In practice, each animal would have different weight
          # This is a simplified version
          for animal in self.animal_ids:
              animal.write({
                  'weight_kg': self.weight_kg,  # In reality, each animal has different weight
              })

              # Create weight record
              self.env['farm.weight.record'].create({
                  'animal_id': animal.id,
                  'weight_kg': self.weight_kg,
                  'date': self.weight_date,
                  'notes': self.notes,
              })

          return True

      def _batch_change_status(self):
          """Batch status change"""
          self.animal_ids.write({'status': self.new_status})
          return True
  ```
  (5h)

- Create batch operation views and menu (3h)

**[Dev 3] - Animal Search & Filtering (8h)**
- Create advanced search interface (4h)
- Create saved search filters (2h)
- Create animal comparison tool (2h)

---

### Day 205: Testing & Documentation

**[All Devs] - Testing (6h each = 18h total)**
- Unit tests for animal model (2h)
- Integration tests for RFID scanning (2h)
- Genealogy calculation tests (2h)
- Batch operations tests (2h)
- Performance testing with 1000+ animals (2h)
- User acceptance testing (2h)

**[Dev 3] - Documentation (8h)**
- User manual for herd management:
  ```markdown
  # Herd Management System User Guide

  ## 1. Animal Registration

  ### 1.1 Registering a New Animal

  To register a new animal in the system:

  1. Navigate to **Farm** > **Animals** > **Create**
  2. Fill in basic information:
     - **Name**: Give the animal a unique name
     - **Ear Tag**: Enter the physical ear tag number (Format: BD-XXXX-XXXXXX)
     - **RFID Tag**: If available, scan or enter RFID tag
     - **Species**: Select Cattle, Buffalo, or Goat
     - **Breed**: Choose from breed master list
     - **Gender**: Female or Male
     - **Date of Birth**: Enter birth date
     - **Farm**: Select farm location
     - **Pen**: Assign to specific pen/shed

  3. (Optional) Add genealogy:
     - **Dam (Mother)**: Select from female animals
     - **Sire (Father)**: Select from male animals

  4. Upload photo and click **Save**

  ### 1.2 Bulk Import Animals

  For registering multiple animals:

  1. Download Excel template: **Farm** > **Animals** > **Import**
  2. Fill in animal details in Excel
  3. Upload completed file
  4. Review import preview
  5. Confirm import

  ## 2. RFID Tag Management

  ### 2.1 Scanning RFID Tags

  RFID readers automatically sync with the system:
  - When an animal passes through RFID gate, system automatically records scan
  - View scan history: **Farm** > **RFID Scans**

  ### 2.2 Printing Ear Tags

  To print physical ear tags:

  1. Select animals from list view
  2. Click **Print** > **Ear Tags**
  3. System generates printable tags with:
     - Farm name
     - QR code
     - Ear tag number
     - Animal name
     - Breed

  ## 3. Animal Transfer

  ### 3.1 Moving Animals Between Pens

  1. Select animals to transfer
  2. Click **Action** > **Transfer**
  3. Select destination farm/pen
  4. Enter reason and notes
  5. Confirm transfer

  System automatically updates location and logs transfer history.

  ## 4. Family Tree & Genealogy

  ### 4.1 Viewing Family Tree

  1. Open animal record
  2. Click **View Family Tree** button
  3. Interactive tree shows:
     - Parents (2 generations back)
     - Offspring (all descendants)
     - Siblings

  ### 4.2 Inbreeding Analysis

  System automatically calculates inbreeding coefficient:
  - **< 5%**: Low inbreeding (green)
  - **5-10%**: Moderate (yellow)
  - **> 10%**: High inbreeding (red - breeding not recommended)

  ## 5. Animal Groups

  ### 5.1 Creating Animal Groups

  1. Navigate to **Farm** > **Animal Groups** > **Create**
  2. Enter group name and code
  3. Select group type (Lactation, Age, Production, etc.)
  4. Set criteria for auto-assignment (optional)
  5. Save

  ### 5.2 Auto-Assignment Criteria

  Groups can automatically include animals based on:
  - Age range (min/max months)
  - Production level (liters/day)
  - Lactation number
  - Days in milk
  - Status (active, pregnant, sick)

  ## 6. Batch Operations

  ### 6.1 Batch Vaccination

  1. Select multiple animals
  2. Click **Batch Operations**
  3. Choose **Vaccination**
  4. Select vaccine and date
  5. Execute

  ### 6.2 Other Batch Operations

  - Batch weighing
  - Batch treatment
  - Batch pregnancy check
  - Batch status change
  - Batch transfer
  ```
  (6h)

- Create training video scripts (2h)

**[Dev 1 & Dev 2] - Integration Testing (8h each)**
- End-to-end workflow testing (4h)
- Data migration testing (2h)
- Performance optimization (2h)

---

**Milestone 1 Success Criteria:**
- ✓ 1000+ animals registered in system
- ✓ RFID scanning functional with 99%+ read rate
- ✓ Genealogy tracking for 5 generations
- ✓ Family tree visualization working
- ✓ Batch operations processing 100+ animals
- ✓ Animal transfer workflow complete
- ✓ < 2 second page load time with 10,000 animals
- ✓ User documentation complete

---

## MILESTONE 2: Health Management System (Days 206-210)

**Objective**: Implement comprehensive health tracking system for medical records, vaccinations, treatments, disease management, and veterinary coordination.

**Duration**: 5 working days

---

### Day 206: Medical Records Foundation

**[Dev 1] - Health Record Model (8h)**
- Create health record system:
  ```python
  # odoo/addons/smart_dairy_farm/models/farm_health.py
  from odoo import models, fields, api
  from odoo.exceptions import ValidationError
  from datetime import datetime, timedelta

  class FarmHealthRecord(models.Model):
      _name = 'farm.health.record'
      _description = 'Animal Health Record'
      _inherit = ['mail.thread', 'mail.activity.mixin']
      _order = 'date desc'

      name = fields.Char(string='Record Number', readonly=True, default='New')
      animal_id = fields.Many2one(
          'farm.animal',
          string='Animal',
          required=True,
          tracking=True
      )

      date = fields.Datetime(
          string='Date & Time',
          required=True,
          default=fields.Datetime.now,
          tracking=True
      )

      record_type = fields.Selection([
          ('checkup', 'Routine Checkup'),
          ('illness', 'Illness'),
          ('injury', 'Injury'),
          ('vaccination', 'Vaccination'),
          ('treatment', 'Treatment'),
          ('surgery', 'Surgery'),
          ('test', 'Laboratory Test'),
          ('other', 'Other'),
      ], string='Record Type', required=True, tracking=True)

      # Symptoms & Diagnosis
      symptoms = fields.Text(string='Symptoms/Observations')
      body_temperature = fields.Float(string='Body Temperature (°C)')
      heart_rate = fields.Integer(string='Heart Rate (bpm)')
      respiratory_rate = fields.Integer(string='Respiratory Rate')

      diagnosis = fields.Text(string='Diagnosis', tracking=True)
      disease_id = fields.Many2one('farm.disease', string='Disease/Condition')
      severity = fields.Selection([
          ('minor', 'Minor'),
          ('moderate', 'Moderate'),
          ('severe', 'Severe'),
          ('critical', 'Critical'),
      ], string='Severity')

      # Treatment
      treatment = fields.Text(string='Treatment Prescribed')
      medicine_line_ids = fields.One2many(
          'farm.health.medicine.line',
          'health_record_id',
          string='Medicines'
      )

      treatment_start_date = fields.Date(string='Treatment Start Date')
      treatment_end_date = fields.Date(string='Treatment End Date')
      withdrawal_period_days = fields.Integer(
          string='Milk Withdrawal Period (days)',
          help='Days to withhold milk after treatment'
      )
      milk_withdrawal_end_date = fields.Date(
          string='Milk Withdrawal End Date',
          compute='_compute_withdrawal_end_date',
          store=True
      )

      # Follow-up
      requires_followup = fields.Boolean(string='Requires Follow-up')
      followup_date = fields.Date(string='Follow-up Date')
      followup_completed = fields.Boolean(string='Follow-up Completed')
      followup_notes = fields.Text(string='Follow-up Notes')

      # Lab Tests
      lab_test_required = fields.Boolean(string='Lab Test Required')
      lab_test_ids = fields.One2many(
          'farm.lab.test',
          'health_record_id',
          string='Laboratory Tests'
      )

      # Costs
      consultation_fee = fields.Monetary(string='Consultation Fee', currency_field='currency_id')
      medicine_cost = fields.Monetary(
          string='Medicine Cost',
          compute='_compute_medicine_cost',
          store=True
      )
      lab_test_cost = fields.Monetary(
          string='Lab Test Cost',
          compute='_compute_lab_cost',
          store=True
      )
      total_cost = fields.Monetary(
          string='Total Cost',
          compute='_compute_total_cost',
          store=True
      )

      # Personnel
      veterinarian_id = fields.Many2one('res.partner', string='Veterinarian')
      recorded_by_id = fields.Many2one(
          'res.users',
          string='Recorded By',
          default=lambda self: self.env.user
      )

      # Attachments
      attachment_ids = fields.Many2many(
          'ir.attachment',
          string='Attachments',
          help='X-rays, test results, photos, etc.'
      )

      # Status
      status = fields.Selection([
          ('draft', 'Draft'),
          ('ongoing', 'Treatment Ongoing'),
          ('recovered', 'Recovered'),
          ('chronic', 'Chronic'),
          ('deceased', 'Deceased'),
      ], string='Status', default='draft', tracking=True)

      # Related
      farm_id = fields.Many2one(related='animal_id.farm_id', string='Farm', store=True)
      company_id = fields.Many2one('res.company', default=lambda self: self.env.company)
      currency_id = fields.Many2one(related='company_id.currency_id')

      @api.model
      def create(self, vals):
          if vals.get('name', 'New') == 'New':
              vals['name'] = self.env['ir.sequence'].next_by_code('farm.health.record') or 'New'
          return super().create(vals)

      @api.depends('treatment_start_date', 'withdrawal_period_days')
      def _compute_withdrawal_end_date(self):
          for record in self:
              if record.treatment_start_date and record.withdrawal_period_days:
                  start = record.treatment_start_date
                  record.milk_withdrawal_end_date = start + timedelta(days=record.withdrawal_period_days)
              else:
                  record.milk_withdrawal_end_date = False

      @api.depends('medicine_line_ids.total_cost')
      def _compute_medicine_cost(self):
          for record in self:
              record.medicine_cost = sum(record.medicine_line_ids.mapped('total_cost'))

      @api.depends('lab_test_ids.cost')
      def _compute_lab_cost(self):
          for record in self:
              record.lab_test_cost = sum(record.lab_test_ids.mapped('cost'))

      @api.depends('consultation_fee', 'medicine_cost', 'lab_test_cost')
      def _compute_total_cost(self):
          for record in self:
              record.total_cost = record.consultation_fee + record.medicine_cost + record.lab_test_cost

      def action_mark_recovered(self):
          """Mark animal as recovered"""
          self.write({'status': 'recovered'})
          if self.animal_id.status == 'sick':
              self.animal_id.write({'status': 'active'})

      def action_create_followup(self):
          """Create follow-up appointment"""
          return {
              'name': 'Schedule Follow-up',
              'type': 'ir.actions.act_window',
              'res_model': 'farm.health.record',
              'view_mode': 'form',
              'context': {
                  'default_animal_id': self.animal_id.id,
                  'default_record_type': 'checkup',
                  'default_date': self.followup_date,
              },
              'target': 'new',
          }


  class FarmHealthMedicineLine(models.Model):
      _name = 'farm.health.medicine.line'
      _description = 'Medicine Line in Health Record'

      health_record_id = fields.Many2one('farm.health.record', string='Health Record')
      medicine_id = fields.Many2one('farm.medicine', string='Medicine', required=True)

      dosage = fields.Char(string='Dosage', required=True)
      frequency = fields.Char(string='Frequency', help='e.g., Twice daily, Every 8 hours')
      duration_days = fields.Integer(string='Duration (days)')

      quantity = fields.Float(string='Quantity', required=True)
      unit_price = fields.Monetary(string='Unit Price', currency_field='currency_id')
      total_cost = fields.Monetary(
          string='Total Cost',
          compute='_compute_total_cost',
          store=True
      )

      notes = fields.Text(string='Administration Notes')

      currency_id = fields.Many2one(related='health_record_id.currency_id')

      @api.depends('quantity', 'unit_price')
      def _compute_total_cost(self):
          for line in self:
              line.total_cost = line.quantity * line.unit_price


  class FarmDisease(models.Model):
      _name = 'farm.disease'
      _description = 'Disease/Condition'
      _order = 'name'

      name = fields.Char(string='Disease Name', required=True)
      code = fields.Char(string='Code')
      category = fields.Selection([
          ('infectious', 'Infectious'),
          ('parasitic', 'Parasitic'),
          ('metabolic', 'Metabolic'),
          ('reproductive', 'Reproductive'),
          ('nutritional', 'Nutritional'),
          ('injury', 'Injury'),
          ('other', 'Other'),
      ], string='Category')

      symptoms = fields.Text(string='Common Symptoms')
      treatment_protocol = fields.Text(string='Treatment Protocol')
      prevention = fields.Text(string='Prevention Measures')

      is_contagious = fields.Boolean(string='Contagious')
      quarantine_required = fields.Boolean(string='Requires Quarantine')
      reportable = fields.Boolean(string='Reportable to Authority', help='Must be reported to livestock department')

      active = fields.Boolean(default=True)


  class FarmMedicine(models.Model):
      _name = 'farm.medicine'
      _description = 'Veterinary Medicine'

      name = fields.Char(string='Medicine Name', required=True)
      generic_name = fields.Char(string='Generic Name')
      category = fields.Selection([
          ('antibiotic', 'Antibiotic'),
          ('anti_inflammatory', 'Anti-inflammatory'),
          ('antiparasitic', 'Antiparasitic'),
          ('vaccine', 'Vaccine'),
          ('vitamin', 'Vitamin/Supplement'),
          ('hormone', 'Hormone'),
          ('other', 'Other'),
      ], string='Category', required=True)

      manufacturer = fields.Char(string='Manufacturer')
      strength = fields.Char(string='Strength/Concentration')
      form = fields.Selection([
          ('tablet', 'Tablet'),
          ('capsule', 'Capsule'),
          ('injection', 'Injection'),
          ('oral_liquid', 'Oral Liquid'),
          ('powder', 'Powder'),
          ('topical', 'Topical'),
      ], string='Form')

      withdrawal_period_days = fields.Integer(string='Milk Withdrawal Period (days)')
      meat_withdrawal_days = fields.Integer(string='Meat Withdrawal Period (days)')

      dosage_guidelines = fields.Text(string='Dosage Guidelines')
      storage_instructions = fields.Text(string='Storage Instructions')

      unit_price = fields.Monetary(string='Unit Price', currency_field='currency_id')

      active = fields.Boolean(default=True)
      company_id = fields.Many2one('res.company', default=lambda self: self.env.company)
      currency_id = fields.Many2one(related='company_id.currency_id')
  ```
  (6h)

- Create disease and medicine master data (2h)

*[Continued in next response due to length...]*

---

**Note**: This document continues with 8 more days of detailed implementation for Milestone 2-10. Each day includes:
- 3 developers × 8 hours = 24 hours total
- Detailed code examples
- Database models
- UI views
- Business logic
- Testing requirements

Would you like me to continue with the complete Phase 5 document, or should I proceed to create all four phase documents (Phases 5-8)?

