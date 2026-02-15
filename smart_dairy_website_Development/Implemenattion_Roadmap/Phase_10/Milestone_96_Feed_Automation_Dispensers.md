# Milestone 96: Feed Automation & Dispensers

## Smart Dairy Digital Smart Portal + ERP System (Odoo 19 CE)

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P10-M96-v1.0 |
| **Milestone** | 96 of 100 |
| **Phase** | Phase 10: Commerce - IoT Integration Core |
| **Days** | 701-710 (10 working days) |
| **Duration** | 2 weeks |
| **Developers** | 3 Full Stack Developers |
| **Total Effort** | 240 person-hours |
| **Status** | Planned |
| **Version** | 1.0 |
| **Last Updated** | 2026-02-04 |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 96 implements automated feeding integration with smart feed dispensers and consumption tracking. This enables precise ration delivery, individual animal consumption monitoring, feed inventory synchronization, and feed efficiency analytics to optimize nutrition management and reduce feed costs.

### 1.2 Scope

This milestone covers:
- Feed dispenser device model with Modbus control
- Ration delivery control system
- Per-animal consumption tracking
- Feed schedule management
- Feed inventory synchronization
- Cost per animal calculation
- Feed efficiency analytics
- Mobile feed management

### 1.3 Key Outcomes

| Outcome | Success Measure |
|---------|-----------------|
| Dispenser Integration | Support for major brands (GEA, DeLaval, Lely) |
| Ration Delivery | ±50g accuracy for concentrates |
| Consumption Tracking | 100% of feeding events captured |
| Inventory Sync | Real-time inventory depletion tracking |
| Cost Analysis | Daily cost per animal reports |
| Feed Efficiency | FCR calculation per animal |

---

## 2. Objectives

| # | Objective | Priority | Measurable Target |
|---|-----------|----------|-------------------|
| O1 | Implement feed dispenser device model | Critical | Multi-vendor Modbus support |
| O2 | Create ration delivery control system | Critical | <±50g delivery accuracy |
| O3 | Build per-animal consumption tracking | Critical | 100% event capture |
| O4 | Develop feed schedule management | High | Unlimited schedules per animal |
| O5 | Implement feed inventory synchronization | High | Real-time inventory updates |
| O6 | Create cost per animal calculation | High | Daily cost breakdowns |
| O7 | Build feed efficiency analytics | Medium | FCR, DMI calculations |
| O8 | Develop mobile feed management | Medium | Schedule management on mobile |

---

## 3. Key Deliverables

| # | Deliverable | Description | Owner | Priority |
|---|-------------|-------------|-------|----------|
| D1 | Feed Dispenser Model | `iot.feed_dispenser` Odoo model | Dev 1 | Critical |
| D2 | Modbus Control Service | Dispenser communication layer | Dev 2 | Critical |
| D3 | Ration Delivery Controller | Precision feeding control | Dev 1 | Critical |
| D4 | Consumption Tracking Model | Per-animal feed records | Dev 1 | High |
| D5 | Feed Schedule Manager | Scheduling and automation | Dev 2 | High |
| D6 | Inventory Sync Service | Real-time stock updates | Dev 2 | High |
| D7 | Cost Calculation Engine | Feed cost analytics | Dev 1 | High |
| D8 | Feed Efficiency Analyzer | FCR and DMI calculations | Dev 2 | Medium |
| D9 | Feed Management Dashboard | OWL dashboard | Dev 3 | High |
| D10 | Dispenser Control Panel | OWL control interface | Dev 3 | High |
| D11 | Mobile Feed Manager | Flutter app screens | Dev 3 | Medium |
| D12 | Feed Reports | PDF/Excel reports | Dev 2 | Medium |

---

## 4. Prerequisites

### 4.1 From Previous Phases
| Prerequisite | Phase/Milestone | Status |
|--------------|-----------------|--------|
| Animal registry | Phase 5 | Complete |
| Feed inventory module | Phase 5 | Complete |
| Feed ration management | Phase 5 | Complete |
| Stock integration | Phase 4 | Complete |

### 4.2 From Phase 10
| Prerequisite | Milestone | Status |
|--------------|-----------|--------|
| MQTT infrastructure | M91 | Complete |
| Device registry | M92 | Complete |
| Modbus patterns (from M93) | M93 | Complete |
| Activity collar (for animal ID) | M95 | Complete |

---

## 5. Requirement Traceability

### 5.1 RFP Requirements
| RFP ID | Requirement | Implementation |
|--------|-------------|----------------|
| IOT-006 | Automated feeding integration | Full implementation |
| IOT-006.1 | Feed dispenser control | Modbus control system |
| IOT-006.2 | Consumption tracking | Per-animal records |
| IOT-006.3 | Feed cost analysis | Cost calculation engine |
| IOT-006.4 | Inventory management | Real-time sync |

### 5.2 BRD Requirements
| BRD ID | Business Requirement | Implementation |
|--------|---------------------|----------------|
| FR-IOT-004 | Feed efficiency optimization | FCR analytics |
| FR-IOT-004.1 | Individual animal rations | Animal-specific schedules |
| FR-IOT-004.2 | Feed cost tracking | Cost per animal |
| FR-IOT-004.3 | Inventory integration | Stock sync |

### 5.3 SRS Technical Requirements
| SRS ID | Technical Requirement | Implementation |
|--------|----------------------|----------------|
| SRS-IOT-014 | Modbus RTU/TCP support | Dual protocol support |
| SRS-IOT-015 | Delivery accuracy ±50g | Precision control |
| SRS-IOT-016 | Support 100+ dispensers | Scalable architecture |
| SRS-IOT-017 | Real-time inventory sync | Event-driven updates |

---

## 6. Day-by-Day Development Plan

### Day 701: Feed Dispenser Model & Modbus Setup

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-2 | Design feed dispenser model schema | UML diagram, specifications |
| 2-5 | Implement `iot.feed_dispenser` model | Python model file |
| 5-7 | Create vendor-specific configurations | Config files |
| 7-8 | Write model unit tests | pytest test file |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement Modbus RTU/TCP service for dispensers | Modbus service |
| 3-6 | Create dispenser register maps (GEA, DeLaval, Lely) | Register maps |
| 6-8 | Build dispenser communication handler | Communication layer |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-2 | Design feed management dashboard wireframes | Figma mockups |
| 2-5 | Create dispenser list view OWL component | List component |
| 5-8 | Implement dispenser registration wizard | Wizard UI |

---

### Day 702: Ration Delivery Control System

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Design ration delivery model | Delivery model |
| 3-6 | Implement delivery controller service | Controller service |
| 6-8 | Create delivery queue manager | Queue system |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement dispenser command protocol | Command protocol |
| 3-5 | Create delivery confirmation handler | Confirmation logic |
| 5-8 | Build delivery retry mechanism | Retry service |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create dispenser control panel UI | Control panel |
| 3-6 | Implement manual feeding trigger | Manual feed button |
| 6-8 | Build delivery status indicators | Status UI |

---

### Day 703: Consumption Tracking

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement feed consumption model | `iot.feed_consumption` model |
| 3-5 | Create consumption event processor | Event processor |
| 5-8 | Build consumption aggregation service | Aggregation logic |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create TimescaleDB hypertable for consumption | Database schema |
| 3-5 | Implement continuous aggregates | Materialized views |
| 5-8 | Build consumption API endpoints | REST APIs |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create consumption history component | History view |
| 3-6 | Implement consumption chart | Chart component |
| 6-8 | Build animal feed summary card | Summary card |

---

### Day 704: Feed Schedule Management

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Design feed schedule model | `iot.feed_schedule` model |
| 3-5 | Implement schedule execution engine | Execution engine |
| 5-8 | Create schedule validation rules | Validation logic |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement schedule cron job | Scheduled action |
| 3-5 | Create schedule conflict detection | Conflict detection |
| 5-8 | Build schedule override system | Override logic |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create schedule management form | Schedule form |
| 3-6 | Implement schedule calendar view | Calendar UI |
| 6-8 | Build bulk schedule editor | Bulk editor |

---

### Day 705: Feed Inventory Synchronization

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Design inventory sync model | Sync model |
| 3-5 | Implement automatic stock deduction | Stock deduction |
| 5-8 | Create low stock alert system | Alert system |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Link to Odoo stock.move | Stock integration |
| 3-5 | Implement batch consumption posting | Batch posting |
| 5-8 | Create inventory reconciliation | Reconciliation |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create inventory status panel | Inventory panel |
| 3-6 | Implement low stock warnings | Warning UI |
| 6-8 | Build inventory reports view | Reports view |

---

### Day 706: Cost Calculation Engine

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Design cost calculation model | Cost model |
| 3-6 | Implement cost per animal service | Cost service |
| 6-8 | Create cost allocation rules | Allocation logic |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement feed price integration | Price service |
| 3-5 | Create cost history tracking | Cost history |
| 5-8 | Build cost reporting APIs | Cost APIs |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create cost analysis dashboard | Cost dashboard |
| 3-6 | Implement cost breakdown charts | Cost charts |
| 6-8 | Build cost comparison view | Comparison UI |

---

### Day 707: Feed Efficiency Analytics

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement FCR calculation service | FCR service |
| 3-5 | Create DMI (Dry Matter Intake) calculator | DMI calculator |
| 5-8 | Build efficiency scoring system | Efficiency score |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Link consumption to milk production | Production link |
| 3-5 | Create efficiency benchmarking | Benchmarking |
| 5-8 | Build efficiency report generator | Report generator |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create efficiency dashboard | Efficiency view |
| 3-6 | Implement FCR trend charts | FCR charts |
| 6-8 | Build efficiency comparison widgets | Comparison widgets |

---

### Day 708: Dashboard & Mobile Development

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create dashboard data APIs | API endpoints |
| 3-5 | Implement WebSocket feed updates | Real-time stream |
| 5-8 | Build feed report generator | Report service |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement Redis caching for feed data | Cache layer |
| 3-5 | Create mobile API endpoints | Mobile APIs |
| 5-8 | Build export functionality | Export service |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Finalize feed management dashboard | Complete dashboard |
| 3-5 | Create Flutter feed management screen | Mobile screen |
| 5-8 | Implement mobile schedule editor | Mobile scheduler |

---

### Day 709: Testing & Integration

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write unit tests (>80% coverage) | Test suite |
| 3-5 | Create integration tests | Integration tests |
| 5-8 | Perform code review and optimization | Code quality |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Test Modbus integration | Modbus testing |
| 3-5 | Inventory sync testing | Inventory tests |
| 5-8 | Performance testing | Load tests |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | UI component testing | Component tests |
| 3-5 | Cross-browser testing | Compatibility |
| 5-8 | Mobile app testing | Device testing |

---

### Day 710: Documentation & Demo

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write API documentation | API docs |
| 3-5 | Create dispenser setup guide | Setup guide |
| 5-8 | Bug fixes and final adjustments | Stable release |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write Modbus configuration guide | Modbus docs |
| 3-5 | Create feed management user guide | User guide |
| 5-8 | Prepare demo environment | Demo setup |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write dashboard user guide | Dashboard docs |
| 3-5 | Create demo scenarios | Demo materials |
| 5-8 | Conduct milestone demo | Demo presentation |

---

## 7. Technical Specifications

### 7.1 Feed Dispenser Model

```python
# smart_dairy_iot/models/iot_feed_dispenser.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError, UserError
import json

class IoTFeedDispenser(models.Model):
    _name = 'iot.feed_dispenser'
    _description = 'Automated Feed Dispenser'
    _inherit = ['iot.device']
    _order = 'location_id, name'

    # Dispenser Type
    dispenser_vendor = fields.Selection([
        ('gea', 'GEA'),
        ('delaval', 'DeLaval'),
        ('lely', 'Lely'),
        ('cormall', 'Cormall'),
        ('generic', 'Generic Modbus'),
    ], string='Vendor', required=True, default='gea')
    dispenser_model = fields.Char(string='Model')
    dispenser_type = fields.Selection([
        ('concentrate', 'Concentrate Dispenser'),
        ('tmr', 'TMR Mixer/Dispenser'),
        ('mineral', 'Mineral Dispenser'),
        ('liquid', 'Liquid Feed Dispenser'),
        ('calf', 'Calf Feeder'),
    ], string='Dispenser Type', required=True, default='concentrate')

    # Location
    location_id = fields.Many2one(
        'stock.location',
        string='Location',
        help='Farm location where dispenser is installed'
    )
    farm_id = fields.Many2one(
        'res.partner',
        string='Farm',
        domain=[('is_farm', '=', True)],
        required=True
    )
    station_number = fields.Integer(
        string='Station Number',
        help='Physical station number for identification'
    )

    # Modbus Configuration
    modbus_mode = fields.Selection([
        ('rtu', 'Modbus RTU (Serial)'),
        ('tcp', 'Modbus TCP/IP'),
    ], string='Modbus Mode', default='tcp')
    modbus_address = fields.Integer(
        string='Modbus Slave Address',
        default=1
    )
    modbus_ip = fields.Char(string='IP Address')
    modbus_port = fields.Integer(string='Port', default=502)
    serial_port = fields.Char(string='Serial Port', default='/dev/ttyUSB0')
    serial_baudrate = fields.Selection([
        ('9600', '9600'),
        ('19200', '19200'),
        ('38400', '38400'),
        ('57600', '57600'),
        ('115200', '115200'),
    ], string='Baud Rate', default='9600')

    # Capacity & Specifications
    hopper_capacity_kg = fields.Float(
        string='Hopper Capacity (kg)',
        digits=(10, 2),
        default=500.0
    )
    min_dispensing_amount_g = fields.Float(
        string='Min Dispensing (g)',
        digits=(8, 2),
        default=50.0
    )
    max_dispensing_amount_g = fields.Float(
        string='Max Dispensing (g)',
        digits=(8, 2),
        default=5000.0
    )
    dispensing_rate_g_per_sec = fields.Float(
        string='Dispensing Rate (g/s)',
        digits=(6, 2),
        default=50.0
    )
    accuracy_tolerance_pct = fields.Float(
        string='Accuracy Tolerance (%)',
        digits=(4, 2),
        default=2.0
    )

    # Current Status
    current_hopper_level_kg = fields.Float(
        string='Current Hopper Level (kg)',
        digits=(10, 2),
        readonly=True
    )
    hopper_level_pct = fields.Float(
        string='Hopper Level (%)',
        compute='_compute_hopper_level_pct'
    )
    dispenser_status = fields.Selection([
        ('idle', 'Idle'),
        ('dispensing', 'Dispensing'),
        ('error', 'Error'),
        ('maintenance', 'Maintenance'),
        ('low_level', 'Low Level'),
        ('empty', 'Empty'),
    ], string='Dispenser Status', readonly=True, default='idle')
    last_dispense_time = fields.Datetime(
        string='Last Dispense Time',
        readonly=True
    )
    total_dispensed_today_kg = fields.Float(
        string='Total Dispensed Today (kg)',
        compute='_compute_daily_total'
    )

    # Feed Product Link
    default_feed_product_id = fields.Many2one(
        'product.product',
        string='Default Feed Product',
        domain=[('is_feed', '=', True)]
    )
    current_feed_product_id = fields.Many2one(
        'product.product',
        string='Current Feed Product',
        domain=[('is_feed', '=', True)]
    )
    feed_lot_id = fields.Many2one(
        'stock.lot',
        string='Current Feed Lot'
    )

    # Assigned Animals/Groups
    assigned_animal_ids = fields.Many2many(
        'farm.animal',
        string='Assigned Animals'
    )
    assigned_group_id = fields.Many2one(
        'farm.animal.group',
        string='Assigned Animal Group'
    )

    # Maintenance
    last_calibration_date = fields.Date(string='Last Calibration')
    next_calibration_date = fields.Date(string='Next Calibration Due')
    total_cycles = fields.Integer(
        string='Total Dispense Cycles',
        readonly=True
    )
    cycles_since_maintenance = fields.Integer(
        string='Cycles Since Maintenance',
        readonly=True
    )

    # Statistics
    daily_dispense_count = fields.Integer(
        string='Daily Dispense Count',
        compute='_compute_daily_total'
    )

    @api.depends('current_hopper_level_kg', 'hopper_capacity_kg')
    def _compute_hopper_level_pct(self):
        for dispenser in self:
            if dispenser.hopper_capacity_kg:
                dispenser.hopper_level_pct = (
                    dispenser.current_hopper_level_kg /
                    dispenser.hopper_capacity_kg * 100
                )
            else:
                dispenser.hopper_level_pct = 0

    def _compute_daily_total(self):
        today = fields.Date.today()
        for dispenser in self:
            consumptions = self.env['iot.feed_consumption'].search([
                ('dispenser_id', '=', dispenser.id),
                ('consumption_time', '>=', fields.Datetime.to_string(
                    fields.Datetime.start_of(fields.Datetime.now(), 'day')
                )),
            ])
            dispenser.total_dispensed_today_kg = sum(
                c.amount_kg for c in consumptions
            )
            dispenser.daily_dispense_count = len(consumptions)

    @api.constrains('min_dispensing_amount_g', 'max_dispensing_amount_g')
    def _check_dispensing_limits(self):
        for dispenser in self:
            if dispenser.min_dispensing_amount_g >= dispenser.max_dispensing_amount_g:
                raise ValidationError(
                    'Minimum dispensing amount must be less than maximum'
                )

    def dispense_feed(self, animal_id, amount_g, feed_product_id=None):
        """
        Command dispenser to dispense feed for an animal
        """
        self.ensure_one()

        # Validate amount
        if amount_g < self.min_dispensing_amount_g:
            raise UserError(
                f'Amount {amount_g}g is below minimum {self.min_dispensing_amount_g}g'
            )
        if amount_g > self.max_dispensing_amount_g:
            raise UserError(
                f'Amount {amount_g}g exceeds maximum {self.max_dispensing_amount_g}g'
            )

        # Check hopper level
        amount_kg = amount_g / 1000
        if self.current_hopper_level_kg < amount_kg:
            raise UserError(
                f'Insufficient feed in hopper. Available: {self.current_hopper_level_kg}kg'
            )

        # Create delivery request
        delivery = self.env['iot.feed_delivery'].create({
            'dispenser_id': self.id,
            'animal_id': animal_id,
            'requested_amount_g': amount_g,
            'feed_product_id': feed_product_id or self.current_feed_product_id.id,
            'state': 'pending',
        })

        # Send command to dispenser
        modbus_service = self.env['iot.modbus.service']
        success = modbus_service.send_dispense_command(
            dispenser=self,
            amount_g=amount_g,
            animal_id=animal_id
        )

        if success:
            delivery.state = 'dispensing'
            self.dispenser_status = 'dispensing'
        else:
            delivery.state = 'failed'
            delivery.error_message = 'Failed to send dispense command'

        return delivery

    def process_dispense_complete(self, actual_amount_g, animal_rfid=None):
        """
        Process dispense completion callback from dispenser
        """
        self.ensure_one()

        # Find pending delivery
        delivery = self.env['iot.feed_delivery'].search([
            ('dispenser_id', '=', self.id),
            ('state', '=', 'dispensing'),
        ], limit=1, order='create_date desc')

        if not delivery:
            # Log unexpected dispense
            self._log_unexpected_dispense(actual_amount_g, animal_rfid)
            return False

        # Update delivery
        delivery.write({
            'actual_amount_g': actual_amount_g,
            'completed_time': fields.Datetime.now(),
            'state': 'completed',
        })

        # Create consumption record
        self._create_consumption_record(delivery, actual_amount_g)

        # Update dispenser status
        self.write({
            'dispenser_status': 'idle',
            'last_dispense_time': fields.Datetime.now(),
            'current_hopper_level_kg': self.current_hopper_level_kg - (actual_amount_g / 1000),
            'total_cycles': self.total_cycles + 1,
            'cycles_since_maintenance': self.cycles_since_maintenance + 1,
        })

        # Check hopper level
        if self.hopper_level_pct < 10:
            self.dispenser_status = 'low_level'
            self._trigger_low_level_alert()

        return True

    def _create_consumption_record(self, delivery, actual_amount_g):
        """Create feed consumption record"""
        self.env['iot.feed_consumption'].create({
            'dispenser_id': self.id,
            'animal_id': delivery.animal_id.id,
            'feed_product_id': delivery.feed_product_id.id,
            'feed_lot_id': self.feed_lot_id.id,
            'amount_kg': actual_amount_g / 1000,
            'consumption_time': fields.Datetime.now(),
            'delivery_id': delivery.id,
            'schedule_id': delivery.schedule_id.id if delivery.schedule_id else False,
        })

        # Update inventory
        self._update_inventory(delivery.feed_product_id, actual_amount_g / 1000)

    def _update_inventory(self, product_id, amount_kg):
        """Update stock inventory with consumed amount"""
        stock_move = self.env['stock.move'].create({
            'name': f'Feed consumption - {self.name}',
            'product_id': product_id.id,
            'product_uom_qty': amount_kg,
            'product_uom': self.env.ref('uom.product_uom_kgm').id,
            'location_id': self.location_id.id,
            'location_dest_id': self.env.ref(
                'smart_dairy_iot.stock_location_feed_consumed'
            ).id,
            'origin': f'Dispenser: {self.name}',
        })
        stock_move._action_confirm()
        stock_move._action_done()

    def _trigger_low_level_alert(self):
        """Trigger low hopper level alert"""
        self.env['iot.dispenser_alert'].create({
            'dispenser_id': self.id,
            'alert_type': 'low_level',
            'message': f'Hopper level at {self.hopper_level_pct:.1f}%',
        })

    def _log_unexpected_dispense(self, amount_g, animal_rfid):
        """Log dispense that occurred without matching delivery"""
        self.env['iot.feed_consumption'].create({
            'dispenser_id': self.id,
            'amount_kg': amount_g / 1000,
            'consumption_time': fields.Datetime.now(),
            'is_unscheduled': True,
            'notes': f'Unexpected dispense. RFID: {animal_rfid}',
        })

    def action_refill_hopper(self):
        """Record hopper refill"""
        return {
            'type': 'ir.actions.act_window',
            'name': 'Refill Hopper',
            'res_model': 'iot.dispenser.refill.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_dispenser_id': self.id},
        }

    def action_calibrate(self):
        """Initiate dispenser calibration"""
        self.ensure_one()
        modbus_service = self.env['iot.modbus.service']
        modbus_service.send_calibration_command(self)
        self.last_calibration_date = fields.Date.today()

    def action_view_consumption_history(self):
        """View consumption history for this dispenser"""
        return {
            'type': 'ir.actions.act_window',
            'name': f'Consumption - {self.name}',
            'res_model': 'iot.feed_consumption',
            'view_mode': 'tree,pivot,graph',
            'domain': [('dispenser_id', '=', self.id)],
        }
```

### 7.2 Feed Consumption Model

```python
# smart_dairy_iot/models/iot_feed_consumption.py

from odoo import models, fields, api

class IoTFeedConsumption(models.Model):
    _name = 'iot.feed_consumption'
    _description = 'Feed Consumption Record'
    _order = 'consumption_time desc'

    name = fields.Char(
        string='Reference',
        compute='_compute_name',
        store=True
    )

    # Source
    dispenser_id = fields.Many2one(
        'iot.feed_dispenser',
        string='Dispenser',
        required=True,
        ondelete='cascade'
    )
    farm_id = fields.Many2one(
        'res.partner',
        string='Farm',
        related='dispenser_id.farm_id',
        store=True
    )

    # Animal
    animal_id = fields.Many2one(
        'farm.animal',
        string='Animal',
        ondelete='set null'
    )
    animal_rfid = fields.Char(
        string='Animal RFID',
        help='RFID tag detected during feeding'
    )

    # Feed Details
    feed_product_id = fields.Many2one(
        'product.product',
        string='Feed Product'
    )
    feed_lot_id = fields.Many2one(
        'stock.lot',
        string='Feed Lot/Batch'
    )
    amount_kg = fields.Float(
        string='Amount (kg)',
        digits=(10, 3),
        required=True
    )
    amount_g = fields.Float(
        string='Amount (g)',
        compute='_compute_amount_g'
    )

    # Timing
    consumption_time = fields.Datetime(
        string='Consumption Time',
        required=True,
        default=fields.Datetime.now
    )
    duration_seconds = fields.Integer(
        string='Feeding Duration (s)'
    )

    # Cost
    unit_cost = fields.Float(
        string='Unit Cost (BDT/kg)',
        digits=(10, 2),
        compute='_compute_cost',
        store=True
    )
    total_cost = fields.Float(
        string='Total Cost (BDT)',
        digits=(10, 2),
        compute='_compute_cost',
        store=True
    )

    # Schedule Link
    schedule_id = fields.Many2one(
        'iot.feed_schedule',
        string='Feed Schedule'
    )
    delivery_id = fields.Many2one(
        'iot.feed_delivery',
        string='Delivery Record'
    )

    # Flags
    is_scheduled = fields.Boolean(
        string='Scheduled Feeding',
        compute='_compute_is_scheduled',
        store=True
    )
    is_unscheduled = fields.Boolean(
        string='Unscheduled Feeding',
        default=False
    )

    # Notes
    notes = fields.Text(string='Notes')

    @api.depends('dispenser_id', 'animal_id', 'consumption_time')
    def _compute_name(self):
        for record in self:
            animal_name = record.animal_id.name if record.animal_id else 'Unknown'
            time_str = record.consumption_time.strftime('%Y-%m-%d %H:%M') if record.consumption_time else ''
            record.name = f'{animal_name} - {record.dispenser_id.name} - {time_str}'

    @api.depends('amount_kg')
    def _compute_amount_g(self):
        for record in self:
            record.amount_g = record.amount_kg * 1000

    @api.depends('feed_product_id', 'amount_kg')
    def _compute_cost(self):
        for record in self:
            if record.feed_product_id:
                record.unit_cost = record.feed_product_id.standard_price
                record.total_cost = record.unit_cost * record.amount_kg
            else:
                record.unit_cost = 0
                record.total_cost = 0

    @api.depends('schedule_id')
    def _compute_is_scheduled(self):
        for record in self:
            record.is_scheduled = bool(record.schedule_id)

    @api.model
    def get_animal_daily_consumption(self, animal_id, date=None):
        """Get total consumption for an animal on a specific date"""
        if not date:
            date = fields.Date.today()

        start = fields.Datetime.to_string(
            fields.Datetime.start_of(
                fields.Datetime.from_string(f'{date} 00:00:00'), 'day'
            )
        )
        end = fields.Datetime.to_string(
            fields.Datetime.end_of(
                fields.Datetime.from_string(f'{date} 23:59:59'), 'day'
            )
        )

        consumptions = self.search([
            ('animal_id', '=', animal_id),
            ('consumption_time', '>=', start),
            ('consumption_time', '<=', end),
        ])

        return {
            'total_kg': sum(c.amount_kg for c in consumptions),
            'total_cost': sum(c.total_cost for c in consumptions),
            'feeding_count': len(consumptions),
            'products': {
                c.feed_product_id.name: sum(
                    r.amount_kg for r in consumptions
                    if r.feed_product_id == c.feed_product_id
                ) for c in consumptions if c.feed_product_id
            }
        }

    @api.model
    def get_farm_consumption_summary(self, farm_id, start_date, end_date):
        """Get farm-wide consumption summary for a date range"""
        consumptions = self.search([
            ('farm_id', '=', farm_id),
            ('consumption_time', '>=', start_date),
            ('consumption_time', '<=', end_date),
        ])

        # Group by product
        product_summary = {}
        for consumption in consumptions:
            product_name = consumption.feed_product_id.name if consumption.feed_product_id else 'Unknown'
            if product_name not in product_summary:
                product_summary[product_name] = {
                    'total_kg': 0,
                    'total_cost': 0,
                    'count': 0,
                }
            product_summary[product_name]['total_kg'] += consumption.amount_kg
            product_summary[product_name]['total_cost'] += consumption.total_cost
            product_summary[product_name]['count'] += 1

        # Group by animal
        animal_summary = {}
        for consumption in consumptions:
            if consumption.animal_id:
                animal_name = consumption.animal_id.name
                if animal_name not in animal_summary:
                    animal_summary[animal_name] = {
                        'total_kg': 0,
                        'total_cost': 0,
                    }
                animal_summary[animal_name]['total_kg'] += consumption.amount_kg
                animal_summary[animal_name]['total_cost'] += consumption.total_cost

        return {
            'total_kg': sum(c.amount_kg for c in consumptions),
            'total_cost': sum(c.total_cost for c in consumptions),
            'feeding_events': len(consumptions),
            'by_product': product_summary,
            'by_animal': animal_summary,
        }
```

### 7.3 Feed Schedule Model

```python
# smart_dairy_iot/models/iot_feed_schedule.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError
from datetime import datetime, timedelta

class IoTFeedSchedule(models.Model):
    _name = 'iot.feed_schedule'
    _description = 'Feed Schedule'
    _order = 'sequence, time_of_day'

    name = fields.Char(
        string='Schedule Name',
        required=True
    )
    sequence = fields.Integer(string='Sequence', default=10)
    active = fields.Boolean(default=True)

    # Target
    schedule_type = fields.Selection([
        ('animal', 'Individual Animal'),
        ('group', 'Animal Group'),
        ('pen', 'Pen/Location'),
    ], string='Schedule Type', required=True, default='animal')
    animal_id = fields.Many2one(
        'farm.animal',
        string='Animal',
    )
    group_id = fields.Many2one(
        'farm.animal.group',
        string='Animal Group'
    )
    pen_id = fields.Many2one(
        'farm.pen',
        string='Pen'
    )
    farm_id = fields.Many2one(
        'res.partner',
        string='Farm',
        required=True
    )

    # Dispenser
    dispenser_id = fields.Many2one(
        'iot.feed_dispenser',
        string='Dispenser',
        required=True
    )

    # Feed
    feed_product_id = fields.Many2one(
        'product.product',
        string='Feed Product',
        required=True,
        domain=[('is_feed', '=', True)]
    )
    amount_kg = fields.Float(
        string='Amount (kg)',
        digits=(10, 3),
        required=True
    )
    amount_g = fields.Float(
        string='Amount (g)',
        compute='_compute_amount_g',
        inverse='_inverse_amount_g'
    )

    # Timing
    time_of_day = fields.Float(
        string='Time of Day',
        required=True,
        help='Time in 24-hour format (e.g., 6.5 = 06:30)'
    )
    time_display = fields.Char(
        string='Time',
        compute='_compute_time_display'
    )

    # Recurrence
    recurrence = fields.Selection([
        ('daily', 'Daily'),
        ('weekdays', 'Weekdays Only'),
        ('weekends', 'Weekends Only'),
        ('custom', 'Custom Days'),
    ], string='Recurrence', default='daily')
    monday = fields.Boolean(string='Monday', default=True)
    tuesday = fields.Boolean(string='Tuesday', default=True)
    wednesday = fields.Boolean(string='Wednesday', default=True)
    thursday = fields.Boolean(string='Thursday', default=True)
    friday = fields.Boolean(string='Friday', default=True)
    saturday = fields.Boolean(string='Saturday', default=True)
    sunday = fields.Boolean(string='Sunday', default=True)

    # Validity
    valid_from = fields.Date(string='Valid From', default=fields.Date.today)
    valid_until = fields.Date(string='Valid Until')

    # Execution
    last_execution = fields.Datetime(
        string='Last Execution',
        readonly=True
    )
    next_execution = fields.Datetime(
        string='Next Execution',
        compute='_compute_next_execution'
    )
    execution_count = fields.Integer(
        string='Execution Count',
        readonly=True
    )

    # Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('active', 'Active'),
        ('paused', 'Paused'),
        ('expired', 'Expired'),
    ], string='State', default='draft')

    @api.depends('amount_kg')
    def _compute_amount_g(self):
        for schedule in self:
            schedule.amount_g = schedule.amount_kg * 1000

    def _inverse_amount_g(self):
        for schedule in self:
            schedule.amount_kg = schedule.amount_g / 1000

    @api.depends('time_of_day')
    def _compute_time_display(self):
        for schedule in self:
            hours = int(schedule.time_of_day)
            minutes = int((schedule.time_of_day - hours) * 60)
            schedule.time_display = f'{hours:02d}:{minutes:02d}'

    @api.depends('time_of_day', 'recurrence', 'last_execution')
    def _compute_next_execution(self):
        now = fields.Datetime.now()
        for schedule in self:
            if schedule.state != 'active':
                schedule.next_execution = False
                continue

            next_date = schedule._get_next_execution_date(now)
            if next_date:
                hours = int(schedule.time_of_day)
                minutes = int((schedule.time_of_day - hours) * 60)
                schedule.next_execution = next_date.replace(
                    hour=hours, minute=minutes, second=0
                )
            else:
                schedule.next_execution = False

    def _get_next_execution_date(self, from_datetime):
        """Calculate next execution date based on recurrence"""
        self.ensure_one()

        # Check validity
        if self.valid_until and fields.Date.today() > self.valid_until:
            return False

        # Get applicable days
        if self.recurrence == 'daily':
            days = [0, 1, 2, 3, 4, 5, 6]
        elif self.recurrence == 'weekdays':
            days = [0, 1, 2, 3, 4]
        elif self.recurrence == 'weekends':
            days = [5, 6]
        else:  # custom
            days = []
            if self.monday: days.append(0)
            if self.tuesday: days.append(1)
            if self.wednesday: days.append(2)
            if self.thursday: days.append(3)
            if self.friday: days.append(4)
            if self.saturday: days.append(5)
            if self.sunday: days.append(6)

        if not days:
            return False

        # Find next applicable day
        current = from_datetime.date()
        for i in range(7):
            check_date = current + timedelta(days=i)
            if check_date.weekday() in days:
                # Check if time has passed today
                if i == 0:
                    schedule_time = self.time_of_day
                    current_time = from_datetime.hour + from_datetime.minute / 60
                    if current_time >= schedule_time:
                        continue
                return datetime.combine(check_date, datetime.min.time())

        return False

    @api.constrains('time_of_day')
    def _check_time_of_day(self):
        for schedule in self:
            if not 0 <= schedule.time_of_day < 24:
                raise ValidationError('Time must be between 0 and 24')

    def action_activate(self):
        """Activate the schedule"""
        self.write({'state': 'active'})

    def action_pause(self):
        """Pause the schedule"""
        self.write({'state': 'paused'})

    def action_resume(self):
        """Resume paused schedule"""
        self.write({'state': 'active'})

    @api.model
    def _cron_execute_schedules(self):
        """Cron job to execute due feed schedules"""
        now = fields.Datetime.now()
        current_time = now.hour + now.minute / 60

        # Find schedules due for execution
        # Allow 5-minute window for execution
        time_min = current_time - 0.083  # 5 minutes
        time_max = current_time + 0.083

        schedules = self.search([
            ('state', '=', 'active'),
            ('time_of_day', '>=', time_min),
            ('time_of_day', '<=', time_max),
        ])

        for schedule in schedules:
            # Check if already executed today
            if schedule.last_execution:
                last_exec_date = schedule.last_execution.date()
                if last_exec_date == now.date():
                    continue

            # Check day of week
            if not schedule._is_valid_day(now.weekday()):
                continue

            # Execute schedule
            schedule._execute()

    def _is_valid_day(self, weekday):
        """Check if schedule should run on given weekday"""
        self.ensure_one()

        if self.recurrence == 'daily':
            return True
        elif self.recurrence == 'weekdays':
            return weekday < 5
        elif self.recurrence == 'weekends':
            return weekday >= 5
        else:
            day_map = {
                0: self.monday,
                1: self.tuesday,
                2: self.wednesday,
                3: self.thursday,
                4: self.friday,
                5: self.saturday,
                6: self.sunday,
            }
            return day_map.get(weekday, False)

    def _execute(self):
        """Execute the feed schedule"""
        self.ensure_one()

        # Get animals to feed
        animals = self._get_target_animals()

        for animal in animals:
            try:
                self.dispenser_id.dispense_feed(
                    animal_id=animal.id,
                    amount_g=self.amount_g,
                    feed_product_id=self.feed_product_id.id
                )
            except Exception as e:
                # Log error but continue with other animals
                self.env['iot.dispenser_alert'].create({
                    'dispenser_id': self.dispenser_id.id,
                    'alert_type': 'schedule_error',
                    'message': f'Failed to execute schedule for {animal.name}: {str(e)}',
                })

        self.write({
            'last_execution': fields.Datetime.now(),
            'execution_count': self.execution_count + 1,
        })

    def _get_target_animals(self):
        """Get list of animals for this schedule"""
        self.ensure_one()

        if self.schedule_type == 'animal':
            return self.animal_id
        elif self.schedule_type == 'group':
            return self.group_id.animal_ids
        elif self.schedule_type == 'pen':
            return self.env['farm.animal'].search([
                ('pen_id', '=', self.pen_id.id),
                ('state', '=', 'active'),
            ])
        return self.env['farm.animal']
```

### 7.4 Feed Efficiency Service

```python
# smart_dairy_iot/services/feed_efficiency_service.py

from odoo import models, api, fields
from datetime import timedelta

class FeedEfficiencyService(models.AbstractModel):
    _name = 'iot.feed.efficiency.service'
    _description = 'Feed Efficiency Calculation Service'

    @api.model
    def calculate_fcr(self, animal_id, start_date, end_date):
        """
        Calculate Feed Conversion Ratio (FCR)
        FCR = Feed Intake (kg) / Milk Production (kg)
        Lower FCR = Better efficiency
        """
        # Get feed consumption
        consumptions = self.env['iot.feed_consumption'].search([
            ('animal_id', '=', animal_id),
            ('consumption_time', '>=', start_date),
            ('consumption_time', '<=', end_date),
        ])
        total_feed_kg = sum(c.amount_kg for c in consumptions)

        # Get milk production
        productions = self.env['farm.milk_production'].search([
            ('animal_id', '=', animal_id),
            ('production_date', '>=', start_date),
            ('production_date', '<=', end_date),
        ])
        total_milk_kg = sum(p.quantity_liters * 1.03 for p in productions)  # Convert to kg

        if total_milk_kg == 0:
            return {
                'fcr': 0,
                'feed_kg': total_feed_kg,
                'milk_kg': 0,
                'status': 'no_production',
            }

        fcr = total_feed_kg / total_milk_kg

        # Benchmark FCR for dairy cattle
        # Excellent: <0.5, Good: 0.5-0.7, Fair: 0.7-0.9, Poor: >0.9
        if fcr < 0.5:
            status = 'excellent'
        elif fcr < 0.7:
            status = 'good'
        elif fcr < 0.9:
            status = 'fair'
        else:
            status = 'poor'

        return {
            'fcr': round(fcr, 3),
            'feed_kg': round(total_feed_kg, 2),
            'milk_kg': round(total_milk_kg, 2),
            'status': status,
        }

    @api.model
    def calculate_dmi(self, animal_id, date=None):
        """
        Calculate Dry Matter Intake (DMI)
        DMI = Sum of (Feed Amount × Dry Matter %)
        """
        if not date:
            date = fields.Date.today()

        consumptions = self.env['iot.feed_consumption'].search([
            ('animal_id', '=', animal_id),
            ('consumption_time', '>=', f'{date} 00:00:00'),
            ('consumption_time', '<=', f'{date} 23:59:59'),
        ])

        total_dmi = 0
        for consumption in consumptions:
            if consumption.feed_product_id:
                # Get dry matter percentage from product (default 88% for concentrates)
                dm_pct = consumption.feed_product_id.dry_matter_percent or 88
                total_dmi += consumption.amount_kg * (dm_pct / 100)
            else:
                total_dmi += consumption.amount_kg * 0.88  # Default

        return {
            'dmi_kg': round(total_dmi, 3),
            'date': date,
            'animal_id': animal_id,
        }

    @api.model
    def calculate_cost_per_liter(self, animal_id, start_date, end_date):
        """
        Calculate feed cost per liter of milk produced
        """
        # Get feed costs
        consumptions = self.env['iot.feed_consumption'].search([
            ('animal_id', '=', animal_id),
            ('consumption_time', '>=', start_date),
            ('consumption_time', '<=', end_date),
        ])
        total_feed_cost = sum(c.total_cost for c in consumptions)

        # Get milk production
        productions = self.env['farm.milk_production'].search([
            ('animal_id', '=', animal_id),
            ('production_date', '>=', start_date),
            ('production_date', '<=', end_date),
        ])
        total_milk_liters = sum(p.quantity_liters for p in productions)

        if total_milk_liters == 0:
            return {
                'cost_per_liter': 0,
                'total_feed_cost': total_feed_cost,
                'total_milk': 0,
                'status': 'no_production',
            }

        cost_per_liter = total_feed_cost / total_milk_liters

        return {
            'cost_per_liter': round(cost_per_liter, 2),
            'total_feed_cost': round(total_feed_cost, 2),
            'total_milk': round(total_milk_liters, 2),
        }

    @api.model
    def get_efficiency_report(self, farm_id, start_date, end_date):
        """
        Generate comprehensive efficiency report for a farm
        """
        animals = self.env['farm.animal'].search([
            ('farm_id', '=', farm_id),
            ('state', '=', 'active'),
            ('animal_type', 'in', ['cow', 'buffalo']),
        ])

        report_data = {
            'farm_id': farm_id,
            'period': {
                'start': start_date,
                'end': end_date,
            },
            'animals': [],
            'summary': {
                'total_feed_kg': 0,
                'total_feed_cost': 0,
                'total_milk_kg': 0,
                'avg_fcr': 0,
                'avg_cost_per_liter': 0,
            }
        }

        fcr_values = []
        cpl_values = []

        for animal in animals:
            fcr_data = self.calculate_fcr(animal.id, start_date, end_date)
            cost_data = self.calculate_cost_per_liter(animal.id, start_date, end_date)
            dmi_data = self.calculate_dmi(animal.id)

            animal_data = {
                'animal_id': animal.id,
                'animal_name': animal.name,
                'fcr': fcr_data['fcr'],
                'fcr_status': fcr_data['status'],
                'feed_kg': fcr_data['feed_kg'],
                'milk_kg': fcr_data['milk_kg'],
                'cost_per_liter': cost_data['cost_per_liter'],
                'total_feed_cost': cost_data['total_feed_cost'],
                'dmi_kg': dmi_data['dmi_kg'],
            }
            report_data['animals'].append(animal_data)

            report_data['summary']['total_feed_kg'] += fcr_data['feed_kg']
            report_data['summary']['total_feed_cost'] += cost_data['total_feed_cost']
            report_data['summary']['total_milk_kg'] += fcr_data['milk_kg']

            if fcr_data['fcr'] > 0:
                fcr_values.append(fcr_data['fcr'])
            if cost_data['cost_per_liter'] > 0:
                cpl_values.append(cost_data['cost_per_liter'])

        # Calculate averages
        if fcr_values:
            report_data['summary']['avg_fcr'] = round(
                sum(fcr_values) / len(fcr_values), 3
            )
        if cpl_values:
            report_data['summary']['avg_cost_per_liter'] = round(
                sum(cpl_values) / len(cpl_values), 2
            )

        # Sort by efficiency
        report_data['animals'].sort(key=lambda x: x['fcr'] if x['fcr'] > 0 else 999)

        return report_data
```

### 7.5 Feed Management Dashboard (OWL)

```javascript
/** @odoo-module **/
// smart_dairy_iot/static/src/components/feed_dashboard/feed_dashboard.js

import { Component, useState, onWillStart, onMounted } from "@odoo/owl";
import { registry } from "@web/core/registry";
import { useService } from "@web/core/utils/hooks";
import { loadJS } from "@web/core/assets";

export class FeedManagementDashboard extends Component {
    static template = "smart_dairy_iot.FeedManagementDashboard";
    static props = {
        farmId: { type: Number, optional: true },
    };

    setup() {
        this.orm = useService("orm");
        this.notification = useService("notification");
        this.actionService = useService("action");

        this.state = useState({
            dispensers: [],
            schedules: [],
            recentConsumptions: [],
            efficiencyData: null,
            isLoading: true,
            selectedDispenser: null,
            dateRange: "7d",
        });

        this.charts = {};

        onWillStart(async () => {
            await loadJS("/smart_dairy_iot/static/lib/chart.js/chart.min.js");
            await this.loadData();
        });

        onMounted(() => {
            this.initializeCharts();
        });
    }

    async loadData() {
        this.state.isLoading = true;
        try {
            await Promise.all([
                this.loadDispensers(),
                this.loadSchedules(),
                this.loadRecentConsumptions(),
                this.loadEfficiencyData(),
            ]);
        } catch (error) {
            this.notification.add("Failed to load data", { type: "danger" });
        }
        this.state.isLoading = false;
    }

    async loadDispensers() {
        const domain = this.props.farmId
            ? [["farm_id", "=", this.props.farmId]]
            : [];

        this.state.dispensers = await this.orm.searchRead(
            "iot.feed_dispenser",
            domain,
            [
                "name", "dispenser_type", "dispenser_status",
                "current_hopper_level_kg", "hopper_level_pct",
                "total_dispensed_today_kg", "daily_dispense_count",
                "device_status", "current_feed_product_id"
            ]
        );
    }

    async loadSchedules() {
        const domain = this.props.farmId
            ? [["farm_id", "=", this.props.farmId], ["state", "=", "active"]]
            : [["state", "=", "active"]];

        this.state.schedules = await this.orm.searchRead(
            "iot.feed_schedule",
            domain,
            [
                "name", "schedule_type", "animal_id", "group_id",
                "feed_product_id", "amount_kg", "time_display",
                "next_execution", "dispenser_id"
            ],
            { order: "time_of_day asc", limit: 10 }
        );
    }

    async loadRecentConsumptions() {
        const domain = this.props.farmId
            ? [["farm_id", "=", this.props.farmId]]
            : [];

        this.state.recentConsumptions = await this.orm.searchRead(
            "iot.feed_consumption",
            domain,
            [
                "name", "animal_id", "feed_product_id",
                "amount_kg", "total_cost", "consumption_time"
            ],
            { order: "consumption_time desc", limit: 20 }
        );
    }

    async loadEfficiencyData() {
        if (!this.props.farmId) return;

        const result = await this.orm.call(
            "iot.feed.efficiency.service",
            "get_efficiency_report",
            [this.props.farmId],
            { start_date: this.getStartDate(), end_date: this.getEndDate() }
        );
        this.state.efficiencyData = result;
    }

    getStartDate() {
        const today = new Date();
        const days = {
            "7d": 7,
            "30d": 30,
            "90d": 90,
        }[this.state.dateRange] || 7;

        const start = new Date(today);
        start.setDate(start.getDate() - days);
        return start.toISOString().split("T")[0];
    }

    getEndDate() {
        return new Date().toISOString().split("T")[0];
    }

    initializeCharts() {
        this.initConsumptionChart();
        this.initCostChart();
    }

    initConsumptionChart() {
        const ctx = document.getElementById("consumptionChart");
        if (!ctx) return;

        this.charts.consumption = new Chart(ctx, {
            type: "bar",
            data: {
                labels: [],
                datasets: [{
                    label: "Feed Consumed (kg)",
                    data: [],
                    backgroundColor: "#4472C4",
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: "kg" }
                    }
                }
            }
        });
    }

    initCostChart() {
        const ctx = document.getElementById("costChart");
        if (!ctx) return;

        this.charts.cost = new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: [
                        "#4472C4", "#ED7D31", "#A5A5A5",
                        "#FFC000", "#5B9BD5", "#70AD47"
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "bottom",
                    }
                }
            }
        });
    }

    // Actions
    async triggerManualFeed(dispenserId) {
        const amount = await this.promptForAmount();
        if (!amount) return;

        try {
            await this.orm.call(
                "iot.feed_dispenser",
                "dispense_feed",
                [dispenserId],
                { animal_id: null, amount_g: amount * 1000 }
            );
            this.notification.add("Feed dispensed successfully", { type: "success" });
            this.loadData();
        } catch (error) {
            this.notification.add(error.message || "Failed to dispense", { type: "danger" });
        }
    }

    async promptForAmount() {
        return new Promise((resolve) => {
            const amount = window.prompt("Enter amount in kg:", "1.0");
            resolve(amount ? parseFloat(amount) : null);
        });
    }

    openDispenserDetail(dispenserId) {
        this.actionService.doAction({
            type: "ir.actions.act_window",
            res_model: "iot.feed_dispenser",
            res_id: dispenserId,
            views: [[false, "form"]],
        });
    }

    openScheduleForm() {
        this.actionService.doAction({
            type: "ir.actions.act_window",
            res_model: "iot.feed_schedule",
            views: [[false, "form"]],
            context: { default_farm_id: this.props.farmId },
        });
    }

    setDateRange(range) {
        this.state.dateRange = range;
        this.loadEfficiencyData();
    }

    // Computed
    get totalDispensedToday() {
        return this.state.dispensers.reduce(
            (sum, d) => sum + d.total_dispensed_today_kg, 0
        ).toFixed(1);
    }

    get totalFeedingEvents() {
        return this.state.dispensers.reduce(
            (sum, d) => sum + d.daily_dispense_count, 0
        );
    }

    get lowLevelDispensers() {
        return this.state.dispensers.filter(d => d.hopper_level_pct < 20);
    }

    getStatusColor(status) {
        const colors = {
            "idle": "bg-success",
            "dispensing": "bg-primary",
            "error": "bg-danger",
            "maintenance": "bg-warning",
            "low_level": "bg-orange",
            "empty": "bg-danger",
        };
        return colors[status] || "bg-secondary";
    }

    getHopperLevelColor(pct) {
        if (pct >= 50) return "bg-success";
        if (pct >= 20) return "bg-warning";
        return "bg-danger";
    }

    formatTime(datetime) {
        if (!datetime) return "N/A";
        return new Date(datetime).toLocaleTimeString();
    }

    formatNumber(num) {
        return num ? num.toFixed(2) : "0.00";
    }
}

registry.category("actions").add("feed_management_dashboard", FeedManagementDashboard);
```

---

## 8. Testing & Validation

### 8.1 Unit Tests

```python
# smart_dairy_iot/tests/test_feed_dispenser.py

from odoo.tests.common import TransactionCase
from odoo.exceptions import ValidationError, UserError

class TestFeedDispenser(TransactionCase):

    def setUp(self):
        super().setUp()
        self.farm = self.env['res.partner'].create({
            'name': 'Test Farm',
            'is_farm': True,
        })
        self.feed_product = self.env['product.product'].create({
            'name': 'Test Concentrate',
            'is_feed': True,
            'standard_price': 50.0,
        })
        self.dispenser = self.env['iot.feed_dispenser'].create({
            'name': 'Test Dispenser',
            'device_uid': 'DISP001',
            'dispenser_vendor': 'gea',
            'dispenser_type': 'concentrate',
            'farm_id': self.farm.id,
            'hopper_capacity_kg': 500,
            'current_hopper_level_kg': 400,
            'min_dispensing_amount_g': 50,
            'max_dispensing_amount_g': 5000,
            'current_feed_product_id': self.feed_product.id,
        })
        self.animal = self.env['farm.animal'].create({
            'name': 'Test Cow',
            'animal_type': 'cow',
            'farm_id': self.farm.id,
        })

    def test_dispenser_creation(self):
        """Test dispenser is created correctly"""
        self.assertEqual(self.dispenser.dispenser_status, 'idle')
        self.assertEqual(self.dispenser.hopper_level_pct, 80.0)

    def test_dispensing_limits_validation(self):
        """Test min/max dispensing validation"""
        with self.assertRaises(ValidationError):
            self.dispenser.min_dispensing_amount_g = 6000

    def test_dispense_below_minimum(self):
        """Test dispensing below minimum fails"""
        with self.assertRaises(UserError):
            self.dispenser.dispense_feed(self.animal.id, 30)

    def test_dispense_above_maximum(self):
        """Test dispensing above maximum fails"""
        with self.assertRaises(UserError):
            self.dispenser.dispense_feed(self.animal.id, 6000)

    def test_dispense_insufficient_level(self):
        """Test dispensing with insufficient hopper level"""
        self.dispenser.current_hopper_level_kg = 1
        with self.assertRaises(UserError):
            self.dispenser.dispense_feed(self.animal.id, 2000)


class TestFeedConsumption(TransactionCase):

    def setUp(self):
        super().setUp()
        self.farm = self.env['res.partner'].create({
            'name': 'Test Farm',
            'is_farm': True,
        })
        self.feed_product = self.env['product.product'].create({
            'name': 'Test Feed',
            'is_feed': True,
            'standard_price': 45.0,
        })
        self.dispenser = self.env['iot.feed_dispenser'].create({
            'name': 'Test Dispenser',
            'device_uid': 'DISP002',
            'dispenser_vendor': 'generic',
            'dispenser_type': 'concentrate',
            'farm_id': self.farm.id,
        })
        self.animal = self.env['farm.animal'].create({
            'name': 'Test Cow',
            'animal_type': 'cow',
            'farm_id': self.farm.id,
        })

    def test_consumption_cost_calculation(self):
        """Test consumption cost is calculated correctly"""
        consumption = self.env['iot.feed_consumption'].create({
            'dispenser_id': self.dispenser.id,
            'animal_id': self.animal.id,
            'feed_product_id': self.feed_product.id,
            'amount_kg': 2.5,
        })

        self.assertEqual(consumption.unit_cost, 45.0)
        self.assertEqual(consumption.total_cost, 112.5)

    def test_daily_consumption_summary(self):
        """Test daily consumption aggregation"""
        for i in range(3):
            self.env['iot.feed_consumption'].create({
                'dispenser_id': self.dispenser.id,
                'animal_id': self.animal.id,
                'feed_product_id': self.feed_product.id,
                'amount_kg': 1.5,
            })

        summary = self.env['iot.feed_consumption'].get_animal_daily_consumption(
            self.animal.id
        )

        self.assertEqual(summary['total_kg'], 4.5)
        self.assertEqual(summary['feeding_count'], 3)
```

### 8.2 Performance Targets

| Test Case | Target | Method |
|-----------|--------|--------|
| Dispense command response | <500ms | Modbus timing |
| Consumption record creation | <100ms | Database insert |
| Schedule execution | <2 seconds per animal | Cron timing |
| Dashboard data load | <1 second | API response time |
| Efficiency calculation | <3 seconds per farm | Algorithm profiling |

---

## 9. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Modbus communication failure | Medium | High | Retry mechanism, offline queue |
| Inaccurate dispensing | Low | Medium | Regular calibration, verification |
| Inventory sync lag | Medium | Medium | Event-driven updates, reconciliation |
| Schedule conflicts | Low | Low | Conflict detection, manual override |
| Hopper level sensor failure | Low | Medium | Manual input fallback, alerts |

---

## 10. Milestone Sign-off Checklist

| # | Criteria | Owner | Status |
|---|----------|-------|--------|
| 1 | Feed dispenser model complete | Dev 1 | ☐ |
| 2 | Modbus control working | Dev 2 | ☐ |
| 3 | Consumption tracking accurate | Dev 1 | ☐ |
| 4 | Schedule execution functional | Dev 2 | ☐ |
| 5 | Inventory sync working | Dev 2 | ☐ |
| 6 | Cost calculation accurate | Dev 1 | ☐ |
| 7 | Efficiency analytics working | Dev 2 | ☐ |
| 8 | Dashboard deployed | Dev 3 | ☐ |
| 9 | Mobile app functional | Dev 3 | ☐ |
| 10 | Unit tests passing (>80%) | All | ☐ |
| 11 | Integration tests passing | Dev 2 | ☐ |
| 12 | Documentation complete | All | ☐ |
| 13 | Demo completed successfully | All | ☐ |
| 14 | Stakeholder sign-off | PM | ☐ |

---

**Document Revision History**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-04 | Development Team | Initial creation |

---

*This document is part of the Smart Dairy Digital Smart Portal + ERP System implementation roadmap.*
