# SMART DAIRY LTD.
## ODOO 19 CUSTOM MODULE DEVELOPMENT GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | B-002 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Odoo Lead |
| **Owner** | Odoo Lead |
| **Reviewer** | Tech Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Development Environment](#2-development-environment)
3. [Module Structure](#3-module-structure)
4. [Models Development](#4-models-development)
5. [Views Development](#5-views-development)
6. [Security](#6-security)
7. [Testing](#7-testing)
8. [Best Practices](#8-best-practices)
9. [Deployment](#9-deployment)

---

## 1. INTRODUCTION

### 1.1 Purpose

This guide provides comprehensive standards and practices for developing custom Odoo 19 modules for the Smart Dairy ERP system.

### 1.2 Technology Stack

| Component | Version | Purpose |
|-----------|---------|---------|
| Odoo | 19.0 CE | ERP Framework |
| Python | 3.11+ | Programming Language |
| PostgreSQL | 16 | Database |
| XML | - | Views & Data |
| JavaScript (OWL) | 2.0 | Frontend |

---

## 2. DEVELOPMENT ENVIRONMENT

### 2.1 Docker Development Setup

```yaml
# docker-compose.dev.yml
version: '3.8'

services:
  odoo:
    image: odoo:19.0
    ports:
      - "8069:8069"
    environment:
      - HOST=db
      - USER=odoo
      - PASSWORD=odoo
      - DEBUG=1
    volumes:
      - ./custom_addons:/mnt/extra-addons
      - ./config/odoo.conf:/etc/odoo/odoo.conf
      - odoo-web-data:/var/lib/odoo
    depends_on:
      - db
    command: odoo --dev=reload,qweb,xml

  db:
    image: postgres:16
    environment:
      - POSTGRES_DB=postgres
      - POSTGRES_PASSWORD=odoo
      - POSTGRES_USER=odoo
    volumes:
      - odoo-db-data:/var/lib/postgresql/data

volumes:
  odoo-web-data:
  odoo-db-data:
```

### 2.2 IDE Configuration

**VS Code Settings (.vscode/settings.json):**
```json
{
    "python.defaultInterpreterPath": "/usr/bin/python3",
    "python.analysis.extraPaths": [
        "/usr/lib/python3/dist-packages/odoo"
    ],
    "files.exclude": {
        "**/__pycache__": true,
        "**/*.pyc": true
    },
    "editor.formatOnSave": true,
    "python.formatting.provider": "black",
    "python.linting.pylintEnabled": true
}
```

---

## 3. MODULE STRUCTURE

### 3.1 Standard Module Layout

```
smart_farm_mgmt/
├── __init__.py                    # Module initialization
├── __manifest__.py                # Module manifest
├── models/
│   ├── __init__.py
│   ├── farm_animal.py
│   ├── farm_breed.py
│   ├── farm_barn.py
│   ├── milk_production.py
│   ├── breeding_record.py
│   └── animal_health.py
├── controllers/
│   ├── __init__.py
│   └── main.py
├── views/
│   ├── farm_animal_views.xml
│   ├── farm_dashboard_views.xml
│   └── menu_views.xml
├── data/
│   ├── animal_breeds_data.xml
│   └── farm_locations_data.xml
├── security/
│   ├── ir.model.access.csv
│   └── security_groups.xml
├── static/
│   └── src/
│       ├── css/
│       ├── js/
│       └── xml/
├── tests/
│   ├── __init__.py
│   └── test_farm_animal.py
└── i18n/
    └── bn.po
```

### 3.2 Module Manifest

```python
# __manifest__.py
{
    'name': 'Smart Dairy Farm Management',
    'version': '19.0.1.0.0',
    'category': 'Agriculture',
    'summary': 'Comprehensive farm management for Smart Dairy',
    'description': """
        Smart Dairy Farm Management Module
        ==================================
        
        This module provides comprehensive farm management capabilities:
        - Animal lifecycle management
        - Milk production tracking
        - Health and breeding records
        - Feed management
        - IoT integration
        
        Author: Smart Dairy Technical Team
    """,
    'author': 'Smart Dairy Ltd.',
    'website': 'https://smartdairybd.com',
    'license': 'LGPL-3',
    
    # Dependencies
    'depends': [
        'base',
        'mail',
        'web',
        'stock',
        'purchase',
    ],
    
    # Data files
    'data': [
        # Security
        'security/security_groups.xml',
        'security/ir.model.access.csv',
        
        # Data
        'data/animal_breeds_data.xml',
        'data/farm_locations_data.xml',
        'data/ir_sequence_data.xml',
        
        # Views
        'views/farm_animal_views.xml',
        'views/farm_breed_views.xml',
        'views/farm_barn_views.xml',
        'views/milk_production_views.xml',
        'views/breeding_record_views.xml',
        'views/animal_health_views.xml',
        'views/farm_dashboard_views.xml',
        'views/menu_views.xml',
        
        # Reports
        'reports/farm_reports.xml',
    ],
    
    # Demo data
    'demo': [
        'demo/farm_demo.xml',
    ],
    
    # Assets
    'assets': {
        'web.assets_backend': [
            'smart_farm_mgmt/static/src/css/farm_dashboard.css',
            'smart_farm_mgmt/static/src/js/farm_dashboard.js',
        ],
    },
    
    # Installation
    'installable': True,
    'application': True,
    'auto_install': False,
    
    # External dependencies
    'external_dependencies': {
        'python': ['pandas', 'numpy'],
    },
}
```

---

## 4. MODELS DEVELOPMENT

### 4.1 Model Definition Best Practices

```python
# models/farm_animal.py
from odoo import models, fields, api, _
from odoo.exceptions import ValidationError, UserError
from datetime import date, timedelta
import logging

_logger = logging.getLogger(__name__)


class FarmAnimal(models.Model):
    """
    Farm Animal Entity
    
    Represents an individual animal in the farm with complete lifecycle
    tracking including birth, breeding, health, and production records.
    """
    
    _name = 'farm.animal'
    _description = 'Farm Animal'
    _order = 'name'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _rec_name = 'display_name'
    
    # ========================================
    # Identification Fields
    # ========================================
    name = fields.Char(
        string='Animal ID',
        required=True,
        index=True,
        tracking=True,
        help='Unique identifier for the animal (e.g., SD001)'
    )
    
    display_name = fields.Char(
        string='Display Name',
        compute='_compute_display_name',
        store=True
    )
    
    rfid_tag = fields.Char(
        string='RFID Tag',
        index=True,
        tracking=True,
        help='RFID tag number for automatic identification'
    )
    
    ear_tag = fields.Char(
        string='Ear Tag Number',
        help='Visible ear tag number'
    )
    
    # ========================================
    # Basic Information
    # ========================================
    species = fields.Selection([
        ('cattle', 'Cattle'),
        ('buffalo', 'Buffalo'),
    ], string='Species', default='cattle', required=True)
    
    breed_id = fields.Many2one(
        'farm.breed',
        string='Breed',
        required=True,
        ondelete='restrict',
        tracking=True
    )
    
    gender = fields.Selection([
        ('female', 'Female'),
        ('male', 'Male'),
    ], string='Gender', required=True)
    
    birth_date = fields.Date(
        string='Birth Date',
        tracking=True
    )
    
    age_months = fields.Integer(
        string='Age (Months)',
        compute='_compute_age',
        store=True
    )
    
    age_display = fields.Char(
        string='Age',
        compute='_compute_age_display'
    )
    
    # ========================================
    # Lifecycle Status
    # ========================================
    status = fields.Selection([
        ('calf', 'Calf'),
        ('heifer', 'Heifer'),
        ('lactating', 'Lactating'),
        ('dry', 'Dry'),
        ('pregnant', 'Pregnant'),
        ('sold', 'Sold'),
        ('deceased', 'Deceased'),
    ], string='Status', default='calf', required=True, tracking=True)
    
    # ========================================
    # Location
    # ========================================
    barn_id = fields.Many2one(
        'farm.barn',
        string='Barn/Location',
        ondelete='set null',
        tracking=True
    )
    
    pen_number = fields.Char(string='Pen Number')
    
    # ========================================
    # Production Metrics
    # ========================================
    current_lactation = fields.Integer(
        string='Lactation Number',
        default=0
    )
    
    daily_milk_avg = fields.Float(
        string='Daily Milk Average (L)',
        compute='_compute_milk_avg',
        digits=(8, 2)
    )
    
    total_lifetime_milk = fields.Float(
        string='Total Lifetime Milk (L)',
        compute='_compute_total_milk',
        digits=(10, 2)
    )
    
    # ========================================
    # Relationships
    # ========================================
    mother_id = fields.Many2one(
        'farm.animal',
        string='Mother',
        domain="[('gender', '=', 'female'), ('species', '=', species)]",
        ondelete='set null'
    )
    
    father_id = fields.Many2one(
        'farm.animal',
        string='Father (Sire)',
        domain="[('gender', '=', 'male'), ('species', '=', species)]",
        ondelete='set null'
    )
    
    offspring_ids = fields.One2many(
        'farm.animal',
        'mother_id',
        string='Offspring'
    )
    
    offspring_count = fields.Integer(
        string='Number of Offspring',
        compute='_compute_offspring_count'
    )
    
    # ========================================
    # Health Status
    # ========================================
    health_status = fields.Selection([
        ('healthy', 'Healthy'),
        ('sick', 'Sick'),
        ('treatment', 'Under Treatment'),
        ('quarantine', 'Quarantine'),
    ], string='Health Status', default='healthy', tracking=True)
    
    last_health_check = fields.Date(
        string='Last Health Check',
        compute='_compute_last_health_check'
    )
    
    # ========================================
    # Financial Information
    # ========================================
    purchase_date = fields.Date(string='Purchase Date')
    purchase_price = fields.Monetary(string='Purchase Price')
    
    supplier_id = fields.Many2one(
        'res.partner',
        string='Supplier',
        domain="[('supplier_rank', '>', 0)]"
    )
    
    sale_date = fields.Date(string='Sale Date')
    sale_price = fields.Monetary(string='Sale Price')
    
    currency_id = fields.Many2one(
        'res.currency',
        related='company_id.currency_id',
        store=True
    )
    
    # ========================================
    # Related Records
    # ========================================
    milk_production_ids = fields.One2many(
        'farm.milk.production',
        'animal_id',
        string='Milk Production Records'
    )
    
    health_record_ids = fields.One2many(
        'farm.animal.health',
        'animal_id',
        string='Health Records'
    )
    
    breeding_record_ids = fields.One2many(
        'farm.breeding.record',
        'animal_id',
        string='Breeding Records'
    )
    
    # ========================================
    # Image & Notes
    # ========================================
    image = fields.Binary(string='Photo')
    
    notes = fields.Text(string='Notes')
    
    # ========================================
    # System Fields
    # ========================================
    company_id = fields.Many2one(
        'res.company',
        string='Company',
        default=lambda self: self.env.company,
        required=True
    )
    
    active = fields.Boolean(default=True)
    
    # ========================================
    # Compute Methods
    # ========================================
    @api.depends('name', 'breed_id')
    def _compute_display_name(self):
        for animal in self:
            if animal.breed_id:
                animal.display_name = f"[{animal.name}] {animal.breed_id.name}"
            else:
                animal.display_name = animal.name
    
    @api.depends('birth_date')
    def _compute_age(self):
        today = date.today()
        for animal in self:
            if animal.birth_date:
                delta = today - animal.birth_date
                animal.age_months = int(delta.days / 30.44)
            else:
                animal.age_months = 0
    
    @api.depends('age_months')
    def _compute_age_display(self):
        for animal in self:
            months = animal.age_months
            years = months // 12
            remaining_months = months % 12
            if years > 0:
                animal.age_display = f"{years}y {remaining_months}m"
            else:
                animal.age_display = f"{months}m"
    
    @api.depends('milk_production_ids.quantity')
    def _compute_milk_avg(self):
        for animal in self:
            productions = animal.milk_production_ids[-30:]  # Last 30 days
            if productions:
                animal.daily_milk_avg = sum(
                    p.quantity for p in productions
                ) / len(productions)
            else:
                animal.daily_milk_avg = 0.0
    
    @api.depends('milk_production_ids.quantity')
    def _compute_total_milk(self):
        for animal in self:
            animal.total_lifetime_milk = sum(
                p.quantity for p in animal.milk_production_ids
            )
    
    @api.depends('offspring_ids')
    def _compute_offspring_count(self):
        for animal in self:
            animal.offspring_count = len(animal.offspring_ids)
    
    @api.depends('health_record_ids.date')
    def _compute_last_health_check(self):
        for animal in self:
            last_check = animal.health_record_ids.sorted(
                'date', reverse=True
            )[:1]
            animal.last_health_check = last_check.date if last_check else False
    
    # ========================================
    # Constraints
    # ========================================
    @api.constrains('birth_date')
    def _check_birth_date(self):
        for animal in self:
            if animal.birth_date and animal.birth_date > date.today():
                raise ValidationError(_(
                    'Birth date cannot be in the future.'
                ))
    
    @api.constrains('rfid_tag')
    def _check_rfid_unique(self):
        for animal in self:
            if animal.rfid_tag:
                existing = self.search([
                    ('rfid_tag', '=', animal.rfid_tag),
                    ('id', '!=', animal.id)
                ])
                if existing:
                    raise ValidationError(_(
                        'RFID tag %(tag)s is already assigned to animal %(animal)s',
                        tag=animal.rfid_tag,
                        animal=existing.name
                    ))
    
    @api.constrains('mother_id', 'father_id')
    def _check_parent_gender(self):
        for animal in self:
            if animal.mother_id and animal.mother_id.gender != 'female':
                raise ValidationError(_('Mother must be female.'))
            if animal.father_id and animal.father_id.gender != 'male':
                raise ValidationError(_('Father must be male.'))
    
    # ========================================
    # Onchange Methods
    # ========================================
    @api.onchange('birth_date')
    def _onchange_birth_date(self):
        if self.birth_date:
            today = date.today()
            age_days = (today - self.birth_date).days
            
            # Auto-update status based on age
            if age_days < 180:  # < 6 months
                self.status = 'calf'
            elif age_days < 730 and self.gender == 'female':  # < 2 years, female
                self.status = 'heifer'
    
    # ========================================
    # Business Methods
    # ========================================
    def action_mark_as_sick(self):
        """Mark animal as sick and create health record."""
        self.ensure_one()
        self.write({'health_status': 'sick'})
        
        return {
            'type': 'ir.actions.act_window',
            'name': _('Create Health Record'),
            'res_model': 'farm.animal.health',
            'view_mode': 'form',
            'target': 'new',
            'context': {
                'default_animal_id': self.id,
                'default_date': fields.Date.today(),
            }
        }
    
    def action_record_milk(self):
        """Quick action to record milk production."""
        self.ensure_one()
        return {
            'type': 'ir.actions.act_window',
            'name': _('Record Milk Production'),
            'res_model': 'farm.milk.production',
            'view_mode': 'form',
            'target': 'new',
            'context': {
                'default_animal_id': self.id,
                'default_production_date': fields.Date.today(),
            }
        }
    
    def get_production_report(self, start_date, end_date):
        """
        Generate production report for date range.
        
        Args:
            start_date: Report start date
            end_date: Report end date
            
        Returns:
            dict: Production statistics
        """
        self.ensure_one()
        productions = self.milk_production_ids.filtered(
            lambda p: start_date <= p.production_date <= end_date
        )
        
        if not productions:
            return {
                'total_volume': 0.0,
                'average_daily': 0.0,
                'session_count': 0,
                'fat_avg': 0.0,
                'snf_avg': 0.0,
            }
        
        total_volume = sum(p.quantity for p in productions)
        days = (end_date - start_date).days + 1
        
        return {
            'total_volume': total_volume,
            'average_daily': total_volume / days if days > 0 else 0,
            'session_count': len(productions),
            'fat_avg': sum(p.fat_percentage or 0 for p in productions) / len(productions),
            'snf_avg': sum(p.snf_percentage or 0 for p in productions) / len(productions),
        }
    
    # ========================================
    # CRUD Overrides
    # ========================================
    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            if not vals.get('name'):
                vals['name'] = self.env['ir.sequence'].next_by_code('farm.animal')
        return super(FarmAnimal, self).create(vals_list)
    
    def write(self, vals):
        if 'status' in vals:
            for animal in self:
                animal.message_post(
                    body=_("Status changed to %(status)s",
                          status=dict(self._fields['status'].selection).get(vals['status']))
                )
        return super(FarmAnimal, self).write(vals)
    
    def unlink(self):
        for animal in self:
            if animal.milk_production_ids:
                raise UserError(_(
                    'Cannot delete animal with milk production records. '
                    'Please archive instead.'
                ))
        return super(FarmAnimal, self).unlink()
```

---

## 5. VIEWS DEVELOPMENT

### 5.1 Form View

```xml
<!-- views/farm_animal_views.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<odoo>
    <!-- Form View -->
    <record id="view_farm_animal_form" model="ir.ui.view">
        <field name="name">farm.animal.form</field>
        <field name="model">farm.animal</field>
        <field name="arch" type="xml">
            <form string="Animal">
                <header>
                    <button name="action_mark_as_sick" 
                            string="Mark as Sick" 
                            type="object"
                            class="btn-warning"
                            invisible="health_status == 'sick'"/>
                    <button name="action_record_milk" 
                            string="Record Milk" 
                            type="object"
                            class="btn-primary"
                            invisible="status not in ('lactating', 'dry')"/>
                    <field name="status" widget="statusbar" 
                           statusbar_visible="calf,heifer,lactating,dry,pregnant"/>
                </header>
                
                <sheet>
                    <div class="oe_button_box" name="button_box">
                        <button type="action" 
                                name="%(action_milk_production_list)d"
                                class="oe_stat_button" 
                                icon="fa-flask">
                            <field string="Milk Records" name="milk_production_count" widget="statinfo"/>
                        </button>
                    </div>
                    
                    <widget name="web_ribbon" 
                            title="Archived" 
                            bg_color="bg-danger" 
                            invisible="active"/>
                    
                    <div class="oe_title">
                        <h1>
                            <field name="name" placeholder="Animal ID (e.g., SD001)"/>
                        </h1>
                    </div>
                    
                    <group>
                        <group string="Basic Information">
                            <field name="image" widget="image" class="oe_avatar"/>
                            <field name="rfid_tag"/>
                            <field name="ear_tag"/>
                            <field name="species"/>
                            <field name="breed_id"/>
                            <field name="gender"/>
                            <field name="birth_date"/>
                            <field name="age_display"/>
                        </group>
                        
                        <group string="Location &amp; Status">
                            <field name="status"/>
                            <field name="health_status"/>
                            <field name="barn_id"/>
                            <field name="pen_number"/>
                            <field name="current_lactation"/>
                            <field name="daily_milk_avg"/>
                        </group>
                    </group>
                    
                    <notebook>
                        <page string="Family">
                            <group>
                                <field name="mother_id"/>
                                <field name="father_id"/>
                                <field name="offspring_count"/>
                            </group>
                            <field name="offspring_ids" 
                                   readonly="1">
                                <tree>
                                    <field name="name"/>
                                    <field name="birth_date"/>
                                    <field name="gender"/>
                                    <field name="status"/>
                                </tree>
                            </field>
                        </page>
                        
                        <page string="Milk Production">
                            <field name="milk_production_ids">
                                <tree editable="bottom">
                                    <field name="production_date"/>
                                    <field name="session"/>
                                    <field name="quantity_liters"/>
                                    <field name="fat_percentage"/>
                                    <field name="snf_percentage"/>
                                </tree>
                            </field>
                        </page>
                        
                        <page string="Health Records">
                            <field name="health_record_ids">
                                <tree>
                                    <field name="date"/>
                                    <field name="record_type"/>
                                    <field name="diagnosis"/>
                                    <field name="status"/>
                                </tree>
                            </field>
                        </page>
                        
                        <page string="Notes">
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
    
    <!-- Tree View -->
    <record id="view_farm_animal_tree" model="ir.ui.view">
        <field name="name">farm.animal.tree</field>
        <field name="model">farm.animal</field>
        <field name="arch" type="xml">
            <tree string="Animals" 
                  decoration-muted="status == 'deceased'"
                  decoration-success="health_status == 'healthy'"
                  decoration-danger="health_status == 'sick'">
                <field name="name"/>
                <field name="rfid_tag" optional="hide"/>
                <field name="breed_id"/>
                <field name="gender"/>
                <field name="age_display"/>
                <field name="status"/>
                <field name="health_status"/>
                <field name="barn_id"/>
                <field name="daily_milk_avg"/>
                <field name="last_health_check"/>
            </tree>
        </field>
    </record>
    
    <!-- Search View -->
    <record id="view_farm_animal_search" model="ir.ui.view">
        <field name="name">farm.animal.search</field>
        <field name="model">farm.animal</field>
        <field name="arch" type="xml">
            <search string="Search Animals">
                <field name="name" filter_domain="['|', ('name', 'ilike', self), ('rfid_tag', 'ilike', self)]"/>
                <field name="rfid_tag"/>
                <field name="breed_id"/>
                <field name="barn_id"/>
                
                <filter string="Cattle" name="cattle" domain="[('species', '=', 'cattle')]"/>
                <filter string="Buffalo" name="buffalo" domain="[('species', '=', 'buffalo')]"/>
                <filter string="Female" name="female" domain="[('gender', '=', 'female')]"/>
                <filter string="Male" name="male" domain="[('gender', '=', 'male')]"/>
                <filter string="Active" name="active" domain="[('status', 'not in', ('sold', 'deceased'))]"/>
                
                <group expand="0" string="Group By">
                    <filter string="Breed" name="breed" context="{'group_by': 'breed_id'}"/>
                    <filter string="Status" name="status" context="{'group_by': 'status'}"/>
                    <filter string="Barn" name="barn" context="{'group_by': 'barn_id'}"/>
                </group>
            </search>
        </field>
    </record>
    
    <!-- Actions -->
    <record id="action_farm_animal" model="ir.actions.act_window">
        <field name="name">Animals</field>
        <field name="res_model">farm.animal</field>
        <field name="view_mode">tree,form,kanban</field>
        <field name="search_view_id" ref="view_farm_animal_search"/>
        <field name="help" type="html">
            <p class="o_view_nocontent_smiling_face">
                Create your first animal record
            </p>
        </field>
    </record>
    
    <!-- Menu -->
    <menuitem id="menu_farm_root" 
              name="Farm Management" 
              sequence="20"/>
    
    <menuitem id="menu_farm_animals" 
              name="Animals" 
              parent="menu_farm_root"
              action="action_farm_animal" 
              sequence="10"/>
</odoo>
```

---

## 6. SECURITY

### 6.1 Access Control List

```csv
# security/ir.model.access.csv
id,name,model_id:id,group_id:id,perm_read,perm_write,perm_create,perm_unlink
access_farm_animal_user,farm.animal.user,model_farm_animal,base.group_user,1,0,0,0
access_farm_animal_manager,farm.animal.manager,model_farm_animal,group_farm_manager,1,1,1,1
access_farm_animal_worker,farm.animal.worker,model_farm_animal,group_farm_worker,1,1,1,0
access_farm_breed_all,farm.breed.all,model_farm_breed,base.group_user,1,0,0,0
access_farm_breed_manager,farm.breed.manager,model_farm_breed,group_farm_manager,1,1,1,1
```

### 6.2 Security Groups

```xml
<!-- security/security_groups.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<odoo>
    <record id="group_farm_manager" model="res.groups">
        <field name="name">Farm Manager</field>
        <field name="category_id" ref="base.module_category_agriculture"/>
        <field name="implied_ids" eval="[(4, ref('base.group_user'))]"/>
    </record>
    
    <record id="group_farm_worker" model="res.groups">
        <field name="name">Farm Worker</field>
        <field name="category_id" ref="base.module_category_agriculture"/>
        <field name="implied_ids" eval="[(4, ref('base.group_user'))]"/>
    </record>
</odoo>
```

---

## 7. TESTING

### 7.1 Unit Tests

```python
# tests/test_farm_animal.py
from odoo.tests.common import TransactionCase
from odoo.exceptions import ValidationError

class TestFarmAnimal(TransactionCase):
    
    def setUp(self):
        super(TestFarmAnimal, self).setUp()
        self.Animal = self.env['farm.animal']
        self.Breed = self.env['farm.breed']
        
        # Create test breed
        self.breed = self.Breed.create({
            'name': 'Holstein Friesian',
            'species': 'cattle',
        })
    
    def test_create_animal(self):
        """Test animal creation."""
        animal = self.Animal.create({
            'name': 'TEST001',
            'breed_id': self.breed.id,
            'gender': 'female',
            'species': 'cattle',
        })
        self.assertEqual(animal.name, 'TEST001')
        self.assertEqual(animal.status, 'calf')
    
    def test_rfid_unique(self):
        """Test RFID uniqueness constraint."""
        self.Animal.create({
            'name': 'TEST001',
            'rfid_tag': 'RFID123',
            'breed_id': self.breed.id,
            'gender': 'female',
        })
        
        with self.assertRaises(ValidationError):
            self.Animal.create({
                'name': 'TEST002',
                'rfid_tag': 'RFID123',
                'breed_id': self.breed.id,
                'gender': 'female',
            })
    
    def test_future_birth_date(self):
        """Test birth date cannot be in future."""
        from datetime import datetime, timedelta
        
        with self.assertRaises(ValidationError):
            self.Animal.create({
                'name': 'TEST003',
                'breed_id': self.breed.id,
                'gender': 'female',
                'birth_date': datetime.now().date() + timedelta(days=1),
            })
```

---

## 8. BEST PRACTICES

### 8.1 Naming Conventions

| Element | Convention | Example |
|---------|-----------|---------|
| Model name | snake_case | `farm.animal` |
| Field name | snake_case | `birth_date` |
| Method name | snake_case | `get_production_report` |
| XML ID | snake_case | `view_farm_animal_form` |
| Class name | CamelCase | `FarmAnimal` |
| Constants | UPPER_CASE | `MAX_LACTATION` |

### 8.2 Performance Guidelines

1. **Always use `store=True` for computed fields used in search/group**
2. **Add indexes for frequently searched fields**
3. **Use `api.model_create_multi` for batch creation**
4. **Avoid `search` inside loops**
5. **Use `read_group` for aggregations**

---

## 9. DEPLOYMENT

### 9.1 Module Installation

```bash
# Update module list
./odoo-bin -u all -d smart_dairy --stop-after-init

# Install specific module
./odoo-bin -i smart_farm_mgmt -d smart_dairy --stop-after-init

# Update module
./odoo-bin -u smart_farm_mgmt -d smart_dairy --stop-after-init
```

---

**END OF ODOO DEVELOPMENT GUIDE**
