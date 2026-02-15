# Milestone 13: Advanced Inventory — Lot Tracking & FEFO

## Smart Dairy Digital Smart Portal + ERP — Phase 2: ERP Core Configuration

| Field            | Detail                                                        |
|------------------|---------------------------------------------------------------|
| **Milestone**    | 13 of 20 (3 of 10 in Phase 2)                                |
| **Title**        | Advanced Inventory — Lot Tracking & FEFO                      |
| **Phase**        | Phase 2 — ERP Core Configuration (Part A: Advanced ERP)       |
| **Days**         | Days 121–130 (of 200)                                        |
| **Version**      | 1.0                                                          |
| **Status**       | Draft                                                        |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend/Mobile Lead) |
| **Last Updated** | 2026-02-03                                                   |

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

Implement advanced inventory management for Smart Dairy's perishable dairy products, including comprehensive lot/batch tracking, FEFO (First Expiry First Out) automatic picking rules, expiry date management with configurable alerts (7/3/1 day warnings), multi-warehouse configuration covering farm, processing facility, and distribution centers, cold chain zone management with temperature tracking integration points, and inventory valuation methods appropriate for perishable goods.

### 1.2 Objectives

1. Configure lot/batch tracking for all dairy products with mandatory lot assignment
2. Implement FEFO picking strategy with automatic selection of earliest expiry lots
3. Develop expiry date management with configurable alert thresholds (7, 3, 1 days)
4. Set up multi-warehouse configuration (Farm Cold Storage, Processing Plant, Distribution Centers)
5. Create cold chain zone management with temperature range definitions
6. Implement inventory valuation methods for perishables (weighted average with write-off)
7. Develop stock aging analysis with automatic write-off proposal workflow
8. Integrate barcode/QR code scanning for lot tracking operations
9. Configure inventory reconciliation and cycle counting procedures
10. Create warehouse management dashboards with real-time stock visibility

### 1.3 Key Deliverables

| # | Deliverable | Owner | Day |
|---|-------------|-------|-----|
| D13.1 | Lot tracking configuration and enforcement | Dev 2 | 121 |
| D13.2 | FEFO picking rules implementation | Dev 2 | 121-122 |
| D13.3 | Expiry alert system (7/3/1 days) | Dev 2 | 122-123 |
| D13.4 | Multi-warehouse setup (3 warehouses) | Dev 2 | 123-124 |
| D13.5 | Cold chain zone configuration | Dev 1 | 124 |
| D13.6 | Temperature tracking integration points | Dev 1 | 125 |
| D13.7 | Inventory valuation for perishables | Dev 1 | 125-126 |
| D13.8 | Stock aging analysis module | Dev 2 | 126-127 |
| D13.9 | Write-off workflow | Dev 2 | 127 |
| D13.10 | Barcode/QR integration | Dev 3 | 128 |
| D13.11 | Cycle counting procedures | Dev 2 | 128-129 |
| D13.12 | Warehouse dashboard (OWL) | Dev 3 | 129 |
| D13.13 | Integration testing and documentation | All | 130 |

### 1.4 Success Criteria

- [ ] 100% FEFO compliance in picking operations (earliest expiry always picked first)
- [ ] Expiry alerts trigger automatically at configured thresholds (7, 3, 1 days)
- [ ] Dispatch blocked for products with < minimum remaining shelf life
- [ ] Multi-warehouse stock transfers complete with full lot traceability
- [ ] Cold chain zones enforce product storage temperature requirements
- [ ] Inventory accuracy verified at 99.5%+ through cycle counting
- [ ] Stock aging report identifies at-risk inventory before expiry

### 1.5 Prerequisites

- **From Milestone 11:** Production lots created from manufacturing
- **From Milestone 12:** Quality grades assigned to lots

---

## 2. Requirement Traceability Matrix

### 2.1 BRD Requirements Mapping

| BRD Req ID | Requirement Description | SRS Trace | Day | Owner |
|-----------|------------------------|-----------|-----|-------|
| BRD-INV-001 | Lot/batch tracking | SRS-INV-001 | 121 | Dev 2 |
| BRD-INV-002 | FEFO picking rules | SRS-INV-002 | 121-122 | Dev 2 |
| BRD-INV-003 | Expiry management | SRS-INV-003 | 122-123 | Dev 2 |
| BRD-INV-004 | Multi-warehouse setup | SRS-INV-004 | 123-124 | Dev 2 |
| BRD-INV-005 | Cold chain management | SRS-INV-005 | 124-125 | Dev 1 |
| BRD-INV-006 | Inventory valuation | SRS-INV-006 | 125-126 | Dev 1 |
| BRD-INV-007 | Stock aging analysis | SRS-INV-007 | 126-127 | Dev 2 |
| BRD-INV-008 | Barcode integration | SRS-INV-008 | 128 | Dev 3 |

### 2.2 Warehouse Configuration

| Warehouse | Code | Type | Temperature | Products |
|-----------|------|------|-------------|----------|
| Farm Cold Storage | WH-FARM | Cold | 2-4°C | Raw milk, cream |
| Processing Plant | WH-PROC | Mixed | Various | All stages |
| Dhaka Distribution | WH-DHK | Cold | 2-6°C | Finished goods |
| Chittagong Distribution | WH-CTG | Cold | 2-6°C | Finished goods |

---

## 3. Day-by-Day Breakdown

---

### Day 121 — Lot Tracking Configuration & FEFO Rules

**Objective:** Configure mandatory lot tracking for dairy products and implement FEFO picking rules.

#### Dev 1 — Backend Lead (8h)

**Task 121.1.1: Lot Tracking Extension (4h)**

```python
# smart_dairy_addons/smart_inventory_dairy/models/stock_lot_dairy.py
from odoo import models, fields, api, _
from odoo.exceptions import UserError
from datetime import datetime, timedelta

class StockLotDairy(models.Model):
    _inherit = 'stock.lot'

    # Dairy-specific lot fields
    is_dairy_lot = fields.Boolean(
        string='Is Dairy Lot',
        compute='_compute_is_dairy_lot',
        store=True
    )

    # Production Source
    production_id = fields.Many2one(
        'mrp.production',
        string='Production Order'
    )
    production_date = fields.Datetime(
        string='Production Date'
    )

    # Quality Information
    quality_grade = fields.Selection([
        ('A', 'Grade A'),
        ('B', 'Grade B'),
        ('C', 'Grade C'),
        ('reject', 'Rejected'),
    ], string='Quality Grade')
    quality_check_id = fields.Many2one(
        'quality.check',
        string='Quality Check'
    )
    coa_id = fields.Many2one(
        'quality.coa.dairy',
        string='COA'
    )

    # Expiry Management
    days_to_expiry = fields.Integer(
        string='Days to Expiry',
        compute='_compute_days_to_expiry',
        store=True
    )
    expiry_status = fields.Selection([
        ('ok', 'OK'),
        ('warning_7', 'Expiring in 7 days'),
        ('warning_3', 'Expiring in 3 days'),
        ('warning_1', 'Expiring Tomorrow'),
        ('expired', 'Expired'),
    ], string='Expiry Status', compute='_compute_days_to_expiry', store=True)

    # Cold Chain
    storage_temperature = fields.Float(
        string='Required Storage Temp (°C)',
        digits=(4, 1)
    )
    temperature_log_ids = fields.One2many(
        'stock.lot.temperature.log',
        'lot_id',
        string='Temperature Logs'
    )
    temperature_breach = fields.Boolean(
        string='Temperature Breach',
        compute='_compute_temperature_breach'
    )

    # Traceability
    farm_source_id = fields.Many2one('farm.farm', string='Source Farm')
    animal_source_ids = fields.Many2many('farm.animal', string='Source Animals')
    milk_production_ids = fields.Many2many(
        'farm.milk.production',
        string='Milk Production Records'
    )

    @api.depends('product_id', 'product_id.is_dairy_product')
    def _compute_is_dairy_lot(self):
        for lot in self:
            lot.is_dairy_lot = lot.product_id.is_dairy_product

    @api.depends('expiration_date')
    def _compute_days_to_expiry(self):
        today = fields.Date.today()
        for lot in self:
            if lot.expiration_date:
                expiry_date = lot.expiration_date.date() if isinstance(
                    lot.expiration_date, datetime
                ) else lot.expiration_date
                days = (expiry_date - today).days
                lot.days_to_expiry = days

                if days < 0:
                    lot.expiry_status = 'expired'
                elif days <= 1:
                    lot.expiry_status = 'warning_1'
                elif days <= 3:
                    lot.expiry_status = 'warning_3'
                elif days <= 7:
                    lot.expiry_status = 'warning_7'
                else:
                    lot.expiry_status = 'ok'
            else:
                lot.days_to_expiry = 0
                lot.expiry_status = 'ok'

    def _compute_temperature_breach(self):
        for lot in self:
            if lot.temperature_log_ids:
                lot.temperature_breach = any(
                    not log.is_within_range
                    for log in lot.temperature_log_ids
                )
            else:
                lot.temperature_breach = False

    def action_block_expired(self):
        """Block expired lots from being picked"""
        expired_lots = self.filtered(lambda l: l.expiry_status == 'expired')
        if expired_lots:
            # Move to quarantine location
            quarantine_loc = self.env.ref(
                'smart_inventory_dairy.location_quarantine'
            )
            for lot in expired_lots:
                lot._move_to_quarantine(quarantine_loc)

    def _move_to_quarantine(self, quarantine_location):
        """Move lot stock to quarantine"""
        quants = self.env['stock.quant'].search([
            ('lot_id', '=', self.id),
            ('location_id.usage', '=', 'internal'),
        ])
        for quant in quants:
            if quant.quantity > 0:
                self.env['stock.quant']._update_available_quantity(
                    self.product_id,
                    quant.location_id,
                    -quant.quantity,
                    lot_id=self
                )
                self.env['stock.quant']._update_available_quantity(
                    self.product_id,
                    quarantine_location,
                    quant.quantity,
                    lot_id=self
                )


class StockLotTemperatureLog(models.Model):
    _name = 'stock.lot.temperature.log'
    _description = 'Lot Temperature Log'
    _order = 'timestamp desc'

    lot_id = fields.Many2one(
        'stock.lot',
        string='Lot',
        required=True,
        ondelete='cascade'
    )
    timestamp = fields.Datetime(
        string='Timestamp',
        default=fields.Datetime.now,
        required=True
    )
    temperature = fields.Float(
        string='Temperature (°C)',
        digits=(5, 2),
        required=True
    )
    location_id = fields.Many2one(
        'stock.location',
        string='Location'
    )
    is_within_range = fields.Boolean(
        string='Within Range',
        compute='_compute_within_range',
        store=True
    )
    recorded_by = fields.Many2one(
        'res.users',
        string='Recorded By',
        default=lambda self: self.env.user
    )

    @api.depends('temperature', 'lot_id.storage_temperature')
    def _compute_within_range(self):
        for log in self:
            if log.lot_id.storage_temperature:
                # Allow ±2°C tolerance
                tolerance = 2.0
                target = log.lot_id.storage_temperature
                log.is_within_range = (
                    target - tolerance <= log.temperature <= target + tolerance
                )
            else:
                log.is_within_range = True
```

**Task 121.1.2: FEFO Picking Strategy (4h)**

```python
# smart_dairy_addons/smart_inventory_dairy/models/stock_move_dairy.py
from odoo import models, fields, api, _
from odoo.exceptions import UserError

class StockMoveDairy(models.Model):
    _inherit = 'stock.move'

    def _get_available_quantity(self, location_id, lot_id=None, package_id=None,
                                owner_id=None, strict=False, allow_negative=False):
        """Override to enforce FEFO for dairy products"""
        res = super()._get_available_quantity(
            location_id, lot_id, package_id, owner_id, strict, allow_negative
        )
        return res

    def _action_assign(self, force_qty=False):
        """Override assignment to use FEFO for dairy products"""
        # Separate dairy and non-dairy moves
        dairy_moves = self.filtered(lambda m: m.product_id.is_dairy_product)
        other_moves = self - dairy_moves

        # Process non-dairy moves normally
        if other_moves:
            super(StockMoveDairy, other_moves)._action_assign(force_qty)

        # Process dairy moves with FEFO
        for move in dairy_moves:
            move._assign_fefo()

    def _assign_fefo(self):
        """Assign stock using FEFO (First Expiry First Out) strategy"""
        self.ensure_one()

        if not self.product_id.is_dairy_product:
            return super()._action_assign()

        # Get available lots ordered by expiry date (earliest first)
        domain = [
            ('product_id', '=', self.product_id.id),
            ('location_id', '=', self.location_id.id),
            ('quantity', '>', 0),
        ]

        quants = self.env['stock.quant'].search(domain)

        # Filter out expired lots and sort by expiry
        valid_quants = quants.filtered(
            lambda q: q.lot_id and q.lot_id.expiry_status != 'expired'
        ).sorted(
            key=lambda q: q.lot_id.expiration_date or fields.Date.max
        )

        # Also check minimum remaining shelf life
        min_shelf_life = self.product_id.min_dispatch_shelf_life or 0
        if min_shelf_life > 0:
            valid_quants = valid_quants.filtered(
                lambda q: q.lot_id.days_to_expiry >= min_shelf_life
            )

        remaining_qty = self.product_uom_qty
        move_line_vals = []

        for quant in valid_quants:
            if remaining_qty <= 0:
                break

            available = min(quant.quantity, remaining_qty)
            if available > 0:
                move_line_vals.append({
                    'move_id': self.id,
                    'product_id': self.product_id.id,
                    'product_uom_id': self.product_uom.id,
                    'location_id': quant.location_id.id,
                    'location_dest_id': self.location_dest_id.id,
                    'lot_id': quant.lot_id.id,
                    'reserved_uom_qty': available,
                })
                remaining_qty -= available

        if move_line_vals:
            self.env['stock.move.line'].create(move_line_vals)

        if remaining_qty > 0:
            # Log warning about insufficient stock
            self.env['mail.message'].create({
                'model': 'stock.move',
                'res_id': self.id,
                'body': f"Warning: Only {self.product_uom_qty - remaining_qty} "
                        f"units available with valid shelf life. "
                        f"Remaining {remaining_qty} units not reserved.",
                'message_type': 'notification',
            })


class StockPickingDairy(models.Model):
    _inherit = 'stock.picking'

    has_expired_lots = fields.Boolean(
        string='Has Expired Lots',
        compute='_compute_has_expired_lots'
    )
    has_expiring_lots = fields.Boolean(
        string='Has Expiring Lots',
        compute='_compute_has_expired_lots'
    )

    @api.depends('move_line_ids', 'move_line_ids.lot_id')
    def _compute_has_expired_lots(self):
        for picking in self:
            lots = picking.move_line_ids.mapped('lot_id')
            picking.has_expired_lots = any(
                lot.expiry_status == 'expired' for lot in lots
            )
            picking.has_expiring_lots = any(
                lot.expiry_status in ('warning_1', 'warning_3', 'warning_7')
                for lot in lots
            )

    def button_validate(self):
        """Override to block expired lot dispatch"""
        for picking in self:
            if picking.picking_type_code == 'outgoing' and picking.has_expired_lots:
                raise UserError(_(
                    "Cannot validate: This delivery contains expired lots. "
                    "Please remove expired products before shipping."
                ))
        return super().button_validate()
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Task 121.2.1: Expiry Alert Scheduled Action (4h)**

```python
# smart_dairy_addons/smart_inventory_dairy/models/stock_lot_expiry_alert.py
from odoo import models, fields, api, _
from datetime import datetime, timedelta

class StockLotExpiryAlert(models.Model):
    _name = 'stock.lot.expiry.alert'
    _description = 'Lot Expiry Alert'
    _order = 'days_to_expiry, create_date desc'

    name = fields.Char(
        string='Alert Reference',
        required=True,
        copy=False,
        default=lambda self: _('New')
    )
    lot_id = fields.Many2one(
        'stock.lot',
        string='Lot',
        required=True
    )
    product_id = fields.Many2one(
        related='lot_id.product_id',
        store=True
    )
    expiration_date = fields.Datetime(
        related='lot_id.expiration_date'
    )
    days_to_expiry = fields.Integer(
        related='lot_id.days_to_expiry'
    )
    quantity_on_hand = fields.Float(
        string='Quantity on Hand',
        compute='_compute_quantity'
    )
    alert_level = fields.Selection([
        ('7', '7 Days Warning'),
        ('3', '3 Days Warning'),
        ('1', '1 Day Warning'),
        ('expired', 'Expired'),
    ], string='Alert Level', required=True)
    state = fields.Selection([
        ('new', 'New'),
        ('acknowledged', 'Acknowledged'),
        ('resolved', 'Resolved'),
    ], string='Status', default='new')
    assigned_to = fields.Many2one(
        'res.users',
        string='Assigned To'
    )
    resolution_action = fields.Selection([
        ('sold', 'Sold (Discounted)'),
        ('transferred', 'Transferred'),
        ('written_off', 'Written Off'),
        ('extended', 'Shelf Life Extended'),
    ], string='Resolution Action')
    notes = fields.Text(string='Notes')

    @api.depends('lot_id')
    def _compute_quantity(self):
        for alert in self:
            quants = self.env['stock.quant'].search([
                ('lot_id', '=', alert.lot_id.id),
                ('location_id.usage', '=', 'internal'),
            ])
            alert.quantity_on_hand = sum(quants.mapped('quantity'))

    @api.model
    def _cron_check_expiry_alerts(self):
        """Scheduled action to check for expiring lots"""
        today = fields.Date.today()
        thresholds = [7, 3, 1]

        for days in thresholds:
            target_date = today + timedelta(days=days)

            # Find lots expiring on target date
            lots = self.env['stock.lot'].search([
                ('expiration_date', '>=', datetime.combine(target_date, datetime.min.time())),
                ('expiration_date', '<', datetime.combine(target_date + timedelta(days=1), datetime.min.time())),
            ])

            for lot in lots:
                # Check if alert already exists
                existing = self.search([
                    ('lot_id', '=', lot.id),
                    ('alert_level', '=', str(days)),
                    ('state', '!=', 'resolved'),
                ])
                if not existing:
                    self.create({
                        'lot_id': lot.id,
                        'alert_level': str(days),
                    })

        # Check for expired lots
        expired_lots = self.env['stock.lot'].search([
            ('expiration_date', '<', datetime.combine(today, datetime.min.time())),
        ])
        for lot in expired_lots:
            existing = self.search([
                ('lot_id', '=', lot.id),
                ('alert_level', '=', 'expired'),
                ('state', '!=', 'resolved'),
            ])
            if not existing:
                self.create({
                    'lot_id': lot.id,
                    'alert_level': 'expired',
                })

    def action_acknowledge(self):
        self.write({'state': 'acknowledged'})

    def action_resolve(self):
        self.write({'state': 'resolved'})
```

**Task 121.2.2: Warehouse Configuration Data (2h)**

```xml
<!-- smart_dairy_addons/smart_inventory_dairy/data/warehouse_data.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo noupdate="1">
    <!-- Farm Cold Storage Warehouse -->
    <record id="warehouse_farm" model="stock.warehouse">
        <field name="name">Farm Cold Storage</field>
        <field name="code">FARM</field>
        <field name="partner_id" ref="base.main_partner"/>
        <field name="company_id" ref="base.main_company"/>
    </record>

    <!-- Processing Plant Warehouse -->
    <record id="warehouse_processing" model="stock.warehouse">
        <field name="name">Processing Plant</field>
        <field name="code">PROC</field>
        <field name="partner_id" ref="base.main_partner"/>
        <field name="company_id" ref="base.main_company"/>
    </record>

    <!-- Dhaka Distribution Center -->
    <record id="warehouse_dhaka" model="stock.warehouse">
        <field name="name">Dhaka Distribution Center</field>
        <field name="code">DHK</field>
        <field name="partner_id" ref="base.main_partner"/>
        <field name="company_id" ref="base.main_company"/>
    </record>

    <!-- Quarantine Location -->
    <record id="location_quarantine" model="stock.location">
        <field name="name">Quarantine</field>
        <field name="usage">internal</field>
        <field name="location_id" ref="stock.stock_location_locations"/>
        <field name="scrap_location">False</field>
    </record>

    <!-- Cold Storage Zones -->
    <record id="location_cold_2_4" model="stock.location">
        <field name="name">Cold Zone (2-4°C)</field>
        <field name="usage">internal</field>
        <field name="location_id" ref="warehouse_processing"/>
        <field name="storage_temp_min">2.0</field>
        <field name="storage_temp_max">4.0</field>
        <field name="is_cold_zone">True</field>
    </record>

    <record id="location_cold_4_6" model="stock.location">
        <field name="name">Cold Zone (4-6°C)</field>
        <field name="usage">internal</field>
        <field name="location_id" ref="warehouse_processing"/>
        <field name="storage_temp_min">4.0</field>
        <field name="storage_temp_max">6.0</field>
        <field name="is_cold_zone">True</field>
    </record>

    <record id="location_frozen" model="stock.location">
        <field name="name">Frozen Zone (-18°C)</field>
        <field name="usage">internal</field>
        <field name="location_id" ref="warehouse_processing"/>
        <field name="storage_temp_min">-20.0</field>
        <field name="storage_temp_max">-16.0</field>
        <field name="is_cold_zone">True</field>
    </record>

    <!-- Scheduled Action for Expiry Alerts -->
    <record id="ir_cron_expiry_alert" model="ir.cron">
        <field name="name">Check Lot Expiry Alerts</field>
        <field name="model_id" ref="model_stock_lot_expiry_alert"/>
        <field name="state">code</field>
        <field name="code">model._cron_check_expiry_alerts()</field>
        <field name="interval_number">1</field>
        <field name="interval_type">days</field>
        <field name="numbercall">-1</field>
        <field name="doall">False</field>
    </record>
</odoo>
```

**Task 121.2.3: Unit Tests (2h)**

```python
# smart_dairy_addons/smart_inventory_dairy/tests/test_fefo.py
from odoo.tests import TransactionCase, tagged
from datetime import datetime, timedelta

@tagged('post_install', '-at_install')
class TestFEFO(TransactionCase):

    def test_fefo_picking_order(self):
        """Test FEFO picks earliest expiry first"""
        # Create lots with different expiry dates
        lot_1 = self.env['stock.lot'].create({
            'name': 'LOT-EXPIRY-5',
            'product_id': self.product.id,
            'expiration_date': datetime.now() + timedelta(days=5),
        })
        lot_2 = self.env['stock.lot'].create({
            'name': 'LOT-EXPIRY-10',
            'product_id': self.product.id,
            'expiration_date': datetime.now() + timedelta(days=10),
        })
        lot_3 = self.env['stock.lot'].create({
            'name': 'LOT-EXPIRY-3',
            'product_id': self.product.id,
            'expiration_date': datetime.now() + timedelta(days=3),
        })

        # Add stock for each lot
        for lot in [lot_1, lot_2, lot_3]:
            self._add_stock(lot, 100)

        # Create outgoing move
        move = self._create_move(150)
        move._action_assign()

        # Verify FEFO order (lot_3 first, then lot_1)
        move_lines = move.move_line_ids.sorted(
            key=lambda l: l.lot_id.expiration_date
        )
        self.assertEqual(move_lines[0].lot_id, lot_3)
        self.assertEqual(move_lines[0].reserved_uom_qty, 100)
        self.assertEqual(move_lines[1].lot_id, lot_1)
        self.assertEqual(move_lines[1].reserved_uom_qty, 50)

    def test_expired_lot_blocked(self):
        """Test expired lots are not picked"""
        expired_lot = self.env['stock.lot'].create({
            'name': 'LOT-EXPIRED',
            'product_id': self.product.id,
            'expiration_date': datetime.now() - timedelta(days=1),
        })
        self._add_stock(expired_lot, 100)

        move = self._create_move(50)
        move._action_assign()

        # No reservation should be made from expired lot
        self.assertEqual(len(move.move_line_ids), 0)
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Task 121.3.1: Lot Tracking Views (4h)**

```xml
<!-- smart_dairy_addons/smart_inventory_dairy/views/stock_lot_dairy_views.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Lot Form View Extension -->
    <record id="stock_lot_dairy_form" model="ir.ui.view">
        <field name="name">stock.lot.dairy.form</field>
        <field name="model">stock.lot</field>
        <field name="inherit_id" ref="stock.view_production_lot_form"/>
        <field name="arch" type="xml">
            <xpath expr="//sheet" position="before">
                <div class="alert alert-danger" role="alert"
                     invisible="expiry_status != 'expired'">
                    <strong><i class="fa fa-exclamation-circle"/> EXPIRED</strong>
                    - This lot has expired and should not be shipped.
                </div>
                <div class="alert alert-warning" role="alert"
                     invisible="expiry_status not in ('warning_1', 'warning_3', 'warning_7')">
                    <strong><i class="fa fa-clock-o"/> EXPIRING SOON</strong>
                    - <field name="days_to_expiry" nolabel="1"/> days remaining
                </div>
                <div class="alert alert-info" role="alert"
                     invisible="not temperature_breach">
                    <strong><i class="fa fa-thermometer-half"/> TEMPERATURE BREACH</strong>
                    - Cold chain violation detected
                </div>
            </xpath>

            <xpath expr="//group[@name='main_group']" position="after">
                <group string="Dairy Information" invisible="not is_dairy_lot">
                    <group>
                        <field name="production_id"/>
                        <field name="production_date"/>
                        <field name="quality_grade" widget="badge"
                               decoration-success="quality_grade == 'A'"
                               decoration-info="quality_grade == 'B'"
                               decoration-warning="quality_grade == 'C'"
                               decoration-danger="quality_grade == 'reject'"/>
                    </group>
                    <group>
                        <field name="days_to_expiry"/>
                        <field name="expiry_status" widget="badge"
                               decoration-success="expiry_status == 'ok'"
                               decoration-warning="expiry_status in ('warning_7', 'warning_3')"
                               decoration-danger="expiry_status in ('warning_1', 'expired')"/>
                        <field name="storage_temperature"/>
                    </group>
                </group>
            </xpath>

            <xpath expr="//notebook" position="inside">
                <page string="Temperature Logs" name="temp_logs"
                      invisible="not is_dairy_lot">
                    <field name="temperature_log_ids">
                        <tree editable="bottom">
                            <field name="timestamp"/>
                            <field name="temperature"/>
                            <field name="location_id"/>
                            <field name="is_within_range"/>
                            <field name="recorded_by"/>
                        </tree>
                    </field>
                </page>
                <page string="Traceability" name="traceability"
                      invisible="not is_dairy_lot">
                    <group>
                        <field name="farm_source_id"/>
                        <field name="animal_source_ids" widget="many2many_tags"/>
                        <field name="milk_production_ids" widget="many2many_tags"/>
                    </group>
                </page>
            </xpath>
        </field>
    </record>

    <!-- Lot Tree View with Expiry Highlighting -->
    <record id="stock_lot_dairy_tree" model="ir.ui.view">
        <field name="name">stock.lot.dairy.tree</field>
        <field name="model">stock.lot</field>
        <field name="inherit_id" ref="stock.view_production_lot_tree"/>
        <field name="arch" type="xml">
            <xpath expr="//field[@name='name']" position="after">
                <field name="expiration_date"/>
                <field name="days_to_expiry"/>
                <field name="expiry_status" widget="badge"
                       decoration-success="expiry_status == 'ok'"
                       decoration-warning="expiry_status in ('warning_7', 'warning_3')"
                       decoration-danger="expiry_status in ('warning_1', 'expired')"/>
                <field name="quality_grade" widget="badge" optional="show"/>
            </xpath>
        </field>
    </record>
</odoo>
```

**Task 121.3.2: Inventory Dashboard Wireframes (4h)**

Design warehouse dashboard showing stock levels, expiry alerts, and temperature status.

**End-of-Day 121 Deliverables:**
- [ ] Lot tracking extension with dairy fields
- [ ] FEFO picking strategy implemented
- [ ] Expiry status computed field (ok, warning_7, warning_3, warning_1, expired)
- [ ] Temperature logging model created
- [ ] Expiry alert system with scheduled action
- [ ] 4 warehouses configured (Farm, Processing, Dhaka DC, Chittagong DC)
- [ ] Cold storage zones with temperature ranges
- [ ] Unit tests for FEFO (2 test cases)
- [ ] Lot views extended with expiry highlighting

---

*(Days 122-130 continue with detailed breakdowns for remaining inventory features...)*

---

## 4. Technical Specifications

### 4.1 Data Models Summary

| Model | Description | Key Fields |
|-------|-------------|------------|
| `stock.lot` (extended) | Lot/Batch | days_to_expiry, expiry_status, quality_grade, temperature_breach |
| `stock.lot.temperature.log` | Temperature Log | lot_id, temperature, is_within_range |
| `stock.lot.expiry.alert` | Expiry Alert | lot_id, alert_level, resolution_action |
| `stock.location` (extended) | Location | is_cold_zone, storage_temp_min, storage_temp_max |
| `stock.move` (extended) | Stock Move | FEFO picking logic |

### 4.2 FEFO Algorithm

```python
def fefo_pick(product_id, location_id, required_qty, min_shelf_life=0):
    """
    FEFO picking algorithm for dairy products

    1. Get all quants for product in location
    2. Filter out expired lots
    3. Filter out lots with < min_shelf_life days remaining
    4. Sort by expiration_date ascending (earliest first)
    5. Allocate from earliest expiry until required_qty met
    """
    quants = get_available_quants(product_id, location_id)
    valid_quants = filter_valid_quants(quants, min_shelf_life)
    sorted_quants = sort_by_expiry(valid_quants)
    return allocate_fifo(sorted_quants, required_qty)
```

---

## 5. Testing & Validation

### 5.1 Test Cases

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| TC-13-001 | FEFO picks earliest expiry | Lot with soonest expiry picked first |
| TC-13-002 | Expired lot blocked | No reservation from expired lot |
| TC-13-003 | Min shelf life enforced | Lots below threshold not picked |
| TC-13-004 | Expiry alert at 7 days | Alert created 7 days before expiry |
| TC-13-005 | Temperature breach flag | Breach flagged when out of range |
| TC-13-006 | Quarantine on expiry | Expired stock moved to quarantine |

---

## 6. Risk & Mitigation

| Risk ID | Risk | Probability | Impact | Mitigation |
|---------|------|-------------|--------|------------|
| R13-001 | FEFO performance | Medium | Medium | Index on expiration_date |
| R13-002 | Temperature data gaps | Medium | Low | Alert on missing logs |
| R13-003 | Inventory inaccuracy | Low | High | Regular cycle counts |

---

## 7. Dependencies & Handoffs

### 7.1 Prerequisites
- Production lots from Milestone 11
- Quality grades from Milestone 12

### 7.2 Outputs
- FEFO inventory for MS-16 (B2B Portal)
- Stock aging for MS-19 (Reports)

---

**Document End**

*Milestone 13: Advanced Inventory — Lot Tracking & FEFO*
*Smart Dairy Digital Smart Portal + ERP System*
*Phase 2 — ERP Core Configuration*
*Version 1.0 | February 2026*
