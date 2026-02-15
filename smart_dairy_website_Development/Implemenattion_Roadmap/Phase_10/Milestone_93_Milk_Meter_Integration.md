# Milestone 93: Milk Meter Integration

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field                | Details                                                      |
| -------------------- | ------------------------------------------------------------ |
| **Document ID**      | SD-IMPL-P10-M93-v1.0                                         |
| **Milestone**        | 93 - Milk Meter Integration                                  |
| **Phase**            | Phase 10: Commerce - IoT Integration Core                    |
| **Days**             | 671-680                                                      |
| **Duration**         | 10 Working Days                                              |
| **Status**           | Draft                                                        |
| **Version**          | 1.0.0                                                        |
| **Last Updated**     | 2026-02-04                                                   |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 93 implements comprehensive milk meter integration for real-time production tracking. This milestone establishes Modbus RTU/TCP communication with DeLaval, Afimilk, and GEA milk meters, enabling automatic capture of milk yield, quality parameters, and equipment performance data directly into the Odoo ERP system.

### 1.2 Scope

- Milk Meter Device Model (iot.milk_meter)
- Modbus RTU Communication Layer (pymodbus)
- Modbus TCP Communication Layer
- Milk Session Recording Model
- Animal RFID Association with Milking Sessions
- Quality Parameters Capture (conductivity, temperature, SCC)
- Real-time Production Dashboard
- Milk Meter Gateway Service
- Odoo Farm Module Integration
- Calibration Management System
- Mastitis Alert System (conductivity-based)
- Unit and Integration Tests

### 1.3 Key Outcomes

| Outcome                        | Target                                    |
| ------------------------------ | ----------------------------------------- |
| Milk Data Capture Rate         | 100% of milking sessions                  |
| Data Latency                   | < 5 seconds from meter to Odoo           |
| RFID Association Accuracy      | 99.9%                                     |
| Quality Parameter Capture      | Fat, Protein, SCC, Conductivity           |
| Mastitis Detection             | <24 hours from onset                      |
| Production Dashboard Refresh   | Real-time (< 1 second)                    |

---

## 2. Objectives

1. **Implement Milk Meter Model** - Specialized device model for milk meter attributes
2. **Build Modbus Communication** - RTU (serial) and TCP (ethernet) protocol support
3. **Create Session Recording** - Per-animal milking session data capture
4. **Integrate RFID** - Automatic animal identification during milking
5. **Capture Quality Data** - Fat, protein, SCC, conductivity, temperature
6. **Build Production Dashboard** - Real-time visualization of milk production
7. **Implement Mastitis Alerts** - Early detection based on conductivity patterns

---

## 3. Key Deliverables

| #  | Deliverable                                | Owner  | Priority |
| -- | ------------------------------------------ | ------ | -------- |
| 1  | Milk Meter Device Model (iot.milk_meter)   | Dev 1  | Critical |
| 2  | Modbus RTU Communication Layer             | Dev 1  | Critical |
| 3  | Modbus TCP Communication Layer             | Dev 1  | Critical |
| 4  | Milk Session Model                         | Dev 1  | Critical |
| 5  | RFID Integration Service                   | Dev 2  | Critical |
| 6  | Quality Parameters Model                   | Dev 1  | High     |
| 7  | Milk Meter Gateway Service                 | Dev 2  | Critical |
| 8  | Real-time Production Dashboard             | Dev 3  | High     |
| 9  | Calibration Management                     | Dev 2  | High     |
| 10 | Mastitis Alert System                      | Dev 1  | High     |
| 11 | Mobile Production View (Flutter)           | Dev 3  | High     |
| 12 | Unit & Integration Tests                   | All    | High     |

---

## 4. Prerequisites

| Prerequisite                       | Source      | Status   |
| ---------------------------------- | ----------- | -------- |
| Milestone 91 MQTT Infrastructure   | Milestone 91| Complete |
| Milestone 92 Device Registry       | Milestone 92| Complete |
| Farm Animal Records                | Phase 5     | Complete |
| RFID Tag Data                      | Phase 5     | Complete |
| Milk Production Module             | Phase 5     | Complete |

---

## 5. Requirement Traceability

| Req ID       | Description                              | Source | Priority |
| ------------ | ---------------------------------------- | ------ | -------- |
| IOT-003      | Milk meter real-time integration         | RFP    | Must     |
| FR-IOT-001   | Real-time milk production tracking       | BRD    | Must     |
| SRS-IOT-007  | Modbus RTU/TCP communication             | SRS    | Must     |
| FR-FARM-005  | Individual animal production tracking    | BRD    | Must     |
| FR-QUAL-001  | Milk quality parameter capture           | BRD    | Must     |

---

## 6. Day-by-Day Development Plan

### Day 671 (Day 1): Milk Meter Model & Modbus Foundation

#### Dev 1 (Backend Lead) - 8 hours

**Task 1.1: Create Milk Meter Device Model (4h)**

```python
# odoo/addons/smart_dairy_iot/models/iot_milk_meter.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
from datetime import datetime, timedelta
import logging

_logger = logging.getLogger(__name__)

class IoTMilkMeter(models.Model):
    _name = 'iot.milk_meter'
    _description = 'IoT Milk Meter Device'
    _inherits = {'iot.device': 'device_id'}

    device_id = fields.Many2one(
        'iot.device',
        string='Device',
        required=True,
        ondelete='cascade',
        auto_join=True
    )

    # Meter Specifications
    meter_type = fields.Selection([
        ('inline', 'Inline Meter'),
        ('jar', 'Recording Jar'),
        ('electronic', 'Electronic Meter'),
        ('robotic', 'Robotic System'),
    ], string='Meter Type', default='electronic')

    manufacturer = fields.Selection([
        ('delaval', 'DeLaval'),
        ('afimilk', 'Afimilk'),
        ('gea', 'GEA'),
        ('lely', 'Lely'),
        ('boumatic', 'BouMatic'),
        ('other', 'Other'),
    ], string='Manufacturer', required=True)

    model_number = fields.Char(string='Model Number')

    # Communication
    communication_type = fields.Selection([
        ('modbus_rtu', 'Modbus RTU (Serial)'),
        ('modbus_tcp', 'Modbus TCP (Ethernet)'),
        ('mqtt', 'MQTT'),
        ('api', 'REST API'),
    ], string='Communication Type', default='modbus_rtu', required=True)

    # Modbus Settings
    modbus_address = fields.Integer(string='Modbus Slave Address', default=1)
    modbus_port = fields.Char(string='Serial Port / IP:Port')
    modbus_baudrate = fields.Selection([
        ('9600', '9600'),
        ('19200', '19200'),
        ('38400', '38400'),
        ('57600', '57600'),
        ('115200', '115200'),
    ], string='Baud Rate', default='9600')
    modbus_parity = fields.Selection([
        ('N', 'None'),
        ('E', 'Even'),
        ('O', 'Odd'),
    ], string='Parity', default='N')

    # Milking Points
    milking_point_count = fields.Integer(string='Milking Points', default=1)
    milking_point_ids = fields.One2many(
        'iot.milk_meter.point',
        'meter_id',
        string='Milking Points'
    )

    # Capabilities
    has_rfid_reader = fields.Boolean(string='Has RFID Reader', default=True)
    has_conductivity = fields.Boolean(string='Measures Conductivity', default=True)
    has_temperature = fields.Boolean(string='Measures Temperature', default=True)
    has_flow_sensor = fields.Boolean(string='Has Flow Sensor', default=True)
    has_milk_quality = fields.Boolean(string='Inline Quality Analysis', default=False)

    # Current Status
    current_session_id = fields.Many2one(
        'milk.session',
        string='Current Session',
        readonly=True
    )
    is_milking = fields.Boolean(string='Currently Milking', compute='_compute_is_milking')

    # Statistics
    total_sessions = fields.Integer(string='Total Sessions', compute='_compute_statistics')
    total_yield_liters = fields.Float(string='Total Yield (L)', compute='_compute_statistics')
    avg_session_yield = fields.Float(string='Avg Session Yield (L)', compute='_compute_statistics')

    # Calibration
    flow_calibration_factor = fields.Float(string='Flow Calibration Factor', default=1.0)
    conductivity_baseline = fields.Float(string='Conductivity Baseline (mS/cm)')
    last_calibration = fields.Datetime(string='Last Calibration')

    @api.depends('current_session_id')
    def _compute_is_milking(self):
        for meter in self:
            meter.is_milking = bool(
                meter.current_session_id and
                meter.current_session_id.state == 'in_progress'
            )

    def _compute_statistics(self):
        for meter in self:
            sessions = self.env['milk.session'].search([
                ('meter_id', '=', meter.id),
                ('state', '=', 'completed')
            ])
            meter.total_sessions = len(sessions)
            meter.total_yield_liters = sum(sessions.mapped('total_yield'))
            meter.avg_session_yield = (
                meter.total_yield_liters / meter.total_sessions
                if meter.total_sessions > 0 else 0
            )

    def action_start_polling(self):
        """Start polling this meter for data"""
        self.ensure_one()
        self.env['iot.modbus.service'].start_meter_polling(self.id)

    def action_stop_polling(self):
        """Stop polling this meter"""
        self.ensure_one()
        self.env['iot.modbus.service'].stop_meter_polling(self.id)


class IoTMilkMeterPoint(models.Model):
    _name = 'iot.milk_meter.point'
    _description = 'Milk Meter Milking Point'

    meter_id = fields.Many2one('iot.milk_meter', string='Meter', required=True, ondelete='cascade')

    point_number = fields.Integer(string='Point Number', required=True)
    name = fields.Char(string='Point Name')

    pen_id = fields.Many2one('farm.pen', string='Pen/Stall')
    position_description = fields.Char(string='Position Description')

    # RFID Reader
    rfid_reader_id = fields.Char(string='RFID Reader ID')

    # Status
    is_active = fields.Boolean(string='Active', default=True)
    is_occupied = fields.Boolean(string='Occupied', default=False)
    current_animal_id = fields.Many2one('farm.animal', string='Current Animal')

    _sql_constraints = [
        ('meter_point_unique', 'UNIQUE(meter_id, point_number)', 'Point number must be unique per meter!')
    ]
```

**Task 1.2: Create Modbus Communication Service (4h)**

```python
# odoo/addons/smart_dairy_iot/services/modbus_service.py
from pymodbus.client import ModbusSerialClient, ModbusTcpClient
from pymodbus.exceptions import ModbusException
import logging
import struct
import threading
from datetime import datetime
from odoo import api, models, fields
from odoo.exceptions import UserError

_logger = logging.getLogger(__name__)

class IoTModbusService(models.AbstractModel):
    _name = 'iot.modbus.service'
    _description = 'IoT Modbus Communication Service'

    # Modbus Register Maps (DeLaval example)
    DELAVAL_REGISTERS = {
        'milk_yield': {'address': 0, 'count': 2, 'type': 'float'},
        'flow_rate': {'address': 2, 'count': 2, 'type': 'float'},
        'conductivity': {'address': 4, 'count': 2, 'type': 'float'},
        'temperature': {'address': 6, 'count': 2, 'type': 'float'},
        'session_time': {'address': 8, 'count': 1, 'type': 'uint16'},
        'rfid_high': {'address': 10, 'count': 2, 'type': 'uint32'},
        'rfid_low': {'address': 12, 'count': 2, 'type': 'uint32'},
        'status': {'address': 14, 'count': 1, 'type': 'uint16'},
        'vacuum_level': {'address': 15, 'count': 1, 'type': 'uint16'},
        'pulsation_rate': {'address': 16, 'count': 1, 'type': 'uint16'},
    }

    AFIMILK_REGISTERS = {
        'milk_yield': {'address': 100, 'count': 2, 'type': 'float'},
        'flow_rate': {'address': 102, 'count': 2, 'type': 'float'},
        'fat_percentage': {'address': 104, 'count': 2, 'type': 'float'},
        'protein_percentage': {'address': 106, 'count': 2, 'type': 'float'},
        'lactose_percentage': {'address': 108, 'count': 2, 'type': 'float'},
        'somatic_cell_count': {'address': 110, 'count': 2, 'type': 'uint32'},
        'conductivity': {'address': 112, 'count': 2, 'type': 'float'},
        'blood_detected': {'address': 114, 'count': 1, 'type': 'bool'},
        'rfid': {'address': 120, 'count': 4, 'type': 'string'},
    }

    @api.model
    def get_client(self, meter):
        """Get Modbus client for meter"""
        if meter.communication_type == 'modbus_rtu':
            return self._get_rtu_client(meter)
        elif meter.communication_type == 'modbus_tcp':
            return self._get_tcp_client(meter)
        else:
            raise UserError(f'Unsupported communication type: {meter.communication_type}')

    def _get_rtu_client(self, meter):
        """Create Modbus RTU (Serial) client"""
        return ModbusSerialClient(
            port=meter.modbus_port,
            baudrate=int(meter.modbus_baudrate),
            parity=meter.modbus_parity,
            stopbits=1,
            bytesize=8,
            timeout=3
        )

    def _get_tcp_client(self, meter):
        """Create Modbus TCP client"""
        host, port = meter.modbus_port.split(':')
        return ModbusTcpClient(
            host=host,
            port=int(port),
            timeout=3
        )

    @api.model
    def read_meter_data(self, meter_id):
        """Read all data from milk meter"""
        meter = self.env['iot.milk_meter'].browse(meter_id)
        if not meter.exists():
            _logger.error(f'Meter {meter_id} not found')
            return None

        client = self.get_client(meter)

        try:
            if not client.connect():
                _logger.error(f'Failed to connect to meter {meter.device_id}')
                return None

            # Get register map for manufacturer
            register_map = self._get_register_map(meter.manufacturer)

            # Read all registers
            data = {}
            for name, reg_info in register_map.items():
                value = self._read_register(
                    client,
                    meter.modbus_address,
                    reg_info['address'],
                    reg_info['count'],
                    reg_info['type']
                )
                data[name] = value

            # Update device last seen
            meter.device_id.update_last_seen()

            return data

        except ModbusException as e:
            _logger.error(f'Modbus error reading meter {meter.device_id}: {str(e)}')
            return None
        finally:
            client.close()

    def _get_register_map(self, manufacturer):
        """Get register map for manufacturer"""
        maps = {
            'delaval': self.DELAVAL_REGISTERS,
            'afimilk': self.AFIMILK_REGISTERS,
            'gea': self.DELAVAL_REGISTERS,  # Similar to DeLaval
        }
        return maps.get(manufacturer, self.DELAVAL_REGISTERS)

    def _read_register(self, client, unit, address, count, data_type):
        """Read and decode Modbus register(s)"""
        try:
            result = client.read_holding_registers(address, count, unit=unit)

            if result.isError():
                _logger.warning(f'Error reading register {address}')
                return None

            registers = result.registers

            if data_type == 'float':
                # Combine two 16-bit registers into 32-bit float
                raw = (registers[0] << 16) | registers[1]
                return struct.unpack('>f', struct.pack('>I', raw))[0]

            elif data_type == 'uint16':
                return registers[0]

            elif data_type == 'uint32':
                return (registers[0] << 16) | registers[1]

            elif data_type == 'bool':
                return bool(registers[0])

            elif data_type == 'string':
                # Convert registers to ASCII string
                chars = []
                for reg in registers:
                    chars.append(chr((reg >> 8) & 0xFF))
                    chars.append(chr(reg & 0xFF))
                return ''.join(chars).strip('\x00')

            return registers[0]

        except Exception as e:
            _logger.error(f'Error decoding register {address}: {str(e)}')
            return None

    @api.model
    def process_meter_data(self, meter_id, data):
        """Process raw meter data and create/update session"""
        meter = self.env['iot.milk_meter'].browse(meter_id)
        if not meter.exists() or not data:
            return

        # Extract RFID
        rfid = self._extract_rfid(data, meter.manufacturer)

        # Find animal by RFID
        animal = self.env['farm.animal'].search([
            ('rfid_tag', '=', rfid)
        ], limit=1) if rfid else None

        # Check meter status
        status = data.get('status', 0)
        is_milking = (status & 0x01) != 0  # Bit 0 = milking in progress

        if is_milking:
            # Get or create session
            session = meter.current_session_id
            if not session:
                session = self._create_session(meter, animal, data)
                meter.current_session_id = session.id

            # Update session data
            self._update_session(session, data)

        else:
            # Milking completed
            if meter.current_session_id:
                self._complete_session(meter.current_session_id, data)
                meter.current_session_id = False

    def _extract_rfid(self, data, manufacturer):
        """Extract RFID from data based on manufacturer format"""
        if manufacturer in ('delaval', 'gea'):
            high = data.get('rfid_high', 0) or 0
            low = data.get('rfid_low', 0) or 0
            if high or low:
                return f'{high:08X}{low:08X}'
        elif manufacturer == 'afimilk':
            return data.get('rfid', '')
        return None

    def _create_session(self, meter, animal, data):
        """Create new milking session"""
        return self.env['milk.session'].create({
            'meter_id': meter.id,
            'animal_id': animal.id if animal else False,
            'rfid_tag': self._extract_rfid(data, meter.manufacturer),
            'start_time': datetime.now(),
            'milking_point': meter.milking_point_ids[0].id if meter.milking_point_ids else False,
            'state': 'in_progress',
        })

    def _update_session(self, session, data):
        """Update session with latest data"""
        session.write({
            'current_yield': data.get('milk_yield', 0),
            'current_flow_rate': data.get('flow_rate', 0),
            'conductivity': data.get('conductivity'),
            'milk_temperature': data.get('temperature'),
            'vacuum_level': data.get('vacuum_level'),
            'pulsation_rate': data.get('pulsation_rate'),
        })

        # Quality data (if available)
        if data.get('fat_percentage'):
            session.write({
                'fat_percentage': data.get('fat_percentage'),
                'protein_percentage': data.get('protein_percentage'),
                'lactose_percentage': data.get('lactose_percentage'),
                'somatic_cell_count': data.get('somatic_cell_count'),
            })

    def _complete_session(self, session, data):
        """Complete milking session"""
        session.write({
            'total_yield': data.get('milk_yield', session.current_yield),
            'end_time': datetime.now(),
            'state': 'completed',
        })

        # Check for mastitis indicators
        self._check_mastitis_indicators(session)

        # Publish to MQTT
        self._publish_session_complete(session)

    def _check_mastitis_indicators(self, session):
        """Check for mastitis based on conductivity"""
        if not session.conductivity or not session.animal_id:
            return

        # Get animal's baseline conductivity
        baseline = session.animal_id.conductivity_baseline or 5.5  # Default mS/cm

        # Alert if conductivity is >20% above baseline
        if session.conductivity > baseline * 1.2:
            self.env['iot.device.alert'].create({
                'device_id': session.meter_id.device_id.id,
                'alert_type': 'custom',
                'severity': 'warning' if session.conductivity < baseline * 1.5 else 'critical',
                'message': f'Elevated conductivity ({session.conductivity:.2f} mS/cm) for animal {session.animal_id.name}. Possible mastitis indicator.',
            })

            # Create health event
            self.env['farm.health.event'].create({
                'animal_id': session.animal_id.id,
                'event_type': 'mastitis_indicator',
                'description': f'Elevated milk conductivity: {session.conductivity:.2f} mS/cm (baseline: {baseline:.2f})',
                'source': 'iot_milk_meter',
                'source_reference': session.meter_id.device_id,
            })

    def _publish_session_complete(self, session):
        """Publish session completion to MQTT"""
        mqtt_handler = self.env['iot.mqtt.service'].get_handler()
        if not mqtt_handler:
            return

        payload = {
            'event': 'session_complete',
            'session_id': session.id,
            'meter_id': session.meter_id.device_id,
            'animal_id': session.animal_id.name if session.animal_id else None,
            'yield_liters': session.total_yield,
            'duration_seconds': session.duration_seconds,
            'timestamp': datetime.now().isoformat(),
        }

        topic = f"dairy/{session.meter_id.farm_id.code}/milk_meters/{session.meter_id.device_id}/events"
        mqtt_handler.publish(topic, payload, qos=1)
```

#### Dev 2 (Full-Stack) - 8 hours

**Task 2.1: Milk Session Model (4h)**

```python
# odoo/addons/smart_dairy_iot/models/milk_session.py
from odoo import models, fields, api
from datetime import datetime

class MilkSession(models.Model):
    _name = 'milk.session'
    _description = 'Milking Session'
    _order = 'start_time desc'
    _inherit = ['mail.thread']

    name = fields.Char(string='Session ID', readonly=True, copy=False)

    # References
    meter_id = fields.Many2one(
        'iot.milk_meter',
        string='Milk Meter',
        required=True,
        tracking=True
    )
    animal_id = fields.Many2one(
        'farm.animal',
        string='Animal',
        tracking=True
    )
    milking_point = fields.Many2one(
        'iot.milk_meter.point',
        string='Milking Point'
    )

    # Identification
    rfid_tag = fields.Char(string='RFID Tag', index=True)

    # Timing
    start_time = fields.Datetime(string='Start Time', required=True)
    end_time = fields.Datetime(string='End Time')
    duration_seconds = fields.Integer(
        string='Duration (seconds)',
        compute='_compute_duration',
        store=True
    )

    # Yield Data
    current_yield = fields.Float(string='Current Yield (L)', digits=(10, 3))
    total_yield = fields.Float(string='Total Yield (L)', digits=(10, 3))
    current_flow_rate = fields.Float(string='Current Flow Rate (L/min)', digits=(8, 3))
    peak_flow_rate = fields.Float(string='Peak Flow Rate (L/min)', digits=(8, 3))
    avg_flow_rate = fields.Float(string='Avg Flow Rate (L/min)', digits=(8, 3))

    # Quality Parameters
    milk_temperature = fields.Float(string='Milk Temperature (°C)', digits=(5, 2))
    conductivity = fields.Float(string='Conductivity (mS/cm)', digits=(6, 3))
    fat_percentage = fields.Float(string='Fat %', digits=(5, 2))
    protein_percentage = fields.Float(string='Protein %', digits=(5, 2))
    lactose_percentage = fields.Float(string='Lactose %', digits=(5, 2))
    somatic_cell_count = fields.Integer(string='Somatic Cell Count')
    blood_detected = fields.Boolean(string='Blood Detected', default=False)

    # Equipment Parameters
    vacuum_level = fields.Float(string='Vacuum Level (kPa)', digits=(5, 2))
    pulsation_rate = fields.Integer(string='Pulsation Rate (cycles/min)')

    # Quality Grade
    quality_grade = fields.Selection([
        ('A', 'Grade A'),
        ('B', 'Grade B'),
        ('C', 'Grade C'),
        ('reject', 'Reject'),
    ], string='Quality Grade', compute='_compute_quality_grade', store=True)

    # Flags
    is_abnormal = fields.Boolean(string='Abnormal', compute='_compute_is_abnormal', store=True)
    abnormality_reason = fields.Char(string='Abnormality Reason')

    # Status
    state = fields.Selection([
        ('in_progress', 'In Progress'),
        ('completed', 'Completed'),
        ('cancelled', 'Cancelled'),
    ], string='Status', default='in_progress', tracking=True)

    # Related production record
    production_id = fields.Many2one(
        'farm.milk.production',
        string='Production Record'
    )

    _sql_constraints = [
        ('name_unique', 'UNIQUE(name)', 'Session ID must be unique!')
    ]

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            if not vals.get('name'):
                vals['name'] = self.env['ir.sequence'].next_by_code('milk.session.sequence')
        return super().create(vals_list)

    @api.depends('start_time', 'end_time')
    def _compute_duration(self):
        for session in self:
            if session.start_time and session.end_time:
                delta = session.end_time - session.start_time
                session.duration_seconds = int(delta.total_seconds())
            else:
                session.duration_seconds = 0

    @api.depends('fat_percentage', 'protein_percentage', 'somatic_cell_count', 'blood_detected')
    def _compute_quality_grade(self):
        for session in self:
            if session.blood_detected:
                session.quality_grade = 'reject'
            elif session.somatic_cell_count and session.somatic_cell_count > 400000:
                session.quality_grade = 'reject'
            elif not session.fat_percentage or not session.protein_percentage:
                session.quality_grade = False
            elif session.fat_percentage >= 3.5 and session.protein_percentage >= 3.0:
                if session.somatic_cell_count and session.somatic_cell_count < 200000:
                    session.quality_grade = 'A'
                else:
                    session.quality_grade = 'B'
            elif session.fat_percentage >= 3.0 and session.protein_percentage >= 2.8:
                session.quality_grade = 'B'
            elif session.fat_percentage >= 2.5:
                session.quality_grade = 'C'
            else:
                session.quality_grade = 'reject'

    @api.depends('total_yield', 'conductivity', 'animal_id')
    def _compute_is_abnormal(self):
        for session in self:
            session.is_abnormal = False
            reasons = []

            if session.animal_id and session.total_yield:
                # Check against animal's average
                avg_yield = self._get_animal_avg_yield(session.animal_id)
                if avg_yield and abs(session.total_yield - avg_yield) / avg_yield > 0.3:
                    reasons.append(f'Yield deviation >30% from average')
                    session.is_abnormal = True

            if session.conductivity and session.animal_id:
                baseline = session.animal_id.conductivity_baseline or 5.5
                if session.conductivity > baseline * 1.2:
                    reasons.append(f'Elevated conductivity')
                    session.is_abnormal = True

            session.abnormality_reason = '; '.join(reasons) if reasons else ''

    def _get_animal_avg_yield(self, animal):
        """Get animal's average yield over last 30 days"""
        thirty_days_ago = datetime.now() - timedelta(days=30)
        sessions = self.search([
            ('animal_id', '=', animal.id),
            ('state', '=', 'completed'),
            ('start_time', '>=', thirty_days_ago),
        ])
        if sessions:
            return sum(sessions.mapped('total_yield')) / len(sessions)
        return None

    def action_create_production_record(self):
        """Create farm production record from session"""
        self.ensure_one()
        if self.production_id:
            raise ValidationError('Production record already exists')

        production = self.env['farm.milk.production'].create({
            'animal_id': self.animal_id.id,
            'date': self.start_time.date(),
            'milking_time': 'am' if self.start_time.hour < 12 else 'pm',
            'quantity': self.total_yield,
            'fat_percentage': self.fat_percentage,
            'protein_percentage': self.protein_percentage,
            'somatic_cell_count': self.somatic_cell_count,
            'source': 'iot_meter',
            'source_reference': self.name,
        })

        self.production_id = production.id
        return production
```

**Task 2.2: RFID Integration Service (4h)**

```python
# odoo/addons/smart_dairy_iot/services/rfid_service.py
from odoo import api, models
import logging

_logger = logging.getLogger(__name__)

class IoTRFIDService(models.AbstractModel):
    _name = 'iot.rfid.service'
    _description = 'IoT RFID Integration Service'

    @api.model
    def lookup_animal(self, rfid_tag):
        """Look up animal by RFID tag"""
        if not rfid_tag:
            return None

        # Normalize RFID format
        rfid_normalized = self._normalize_rfid(rfid_tag)

        # Search in farm.animal
        animal = self.env['farm.animal'].search([
            '|',
            ('rfid_tag', '=', rfid_tag),
            ('rfid_tag', '=', rfid_normalized),
        ], limit=1)

        if animal:
            _logger.debug(f'Found animal {animal.name} for RFID {rfid_tag}')
            return animal

        # Check alternative tags
        alt_tag = self.env['farm.animal.rfid'].search([
            ('rfid_tag', '=', rfid_normalized)
        ], limit=1)

        if alt_tag:
            _logger.debug(f'Found animal {alt_tag.animal_id.name} via alternative RFID')
            return alt_tag.animal_id

        _logger.warning(f'No animal found for RFID {rfid_tag}')
        return None

    def _normalize_rfid(self, rfid_tag):
        """Normalize RFID tag format"""
        # Remove any separators and convert to uppercase
        normalized = rfid_tag.upper().replace('-', '').replace(':', '').replace(' ', '')

        # Ensure 16 character hex format
        if len(normalized) < 16:
            normalized = normalized.zfill(16)

        return normalized

    @api.model
    def validate_rfid_format(self, rfid_tag):
        """Validate RFID tag format"""
        import re
        # Allow various formats: hex string, with colons, with dashes
        pattern = r'^[A-Fa-f0-9]{8,16}$|^([A-Fa-f0-9]{2}[:\-]?){4,8}$'
        return bool(re.match(pattern, rfid_tag.replace(' ', '')))

    @api.model
    def register_new_rfid(self, rfid_tag, animal_id, is_primary=True):
        """Register new RFID tag for animal"""
        animal = self.env['farm.animal'].browse(animal_id)
        if not animal.exists():
            raise ValueError(f'Animal {animal_id} not found')

        normalized = self._normalize_rfid(rfid_tag)

        # Check if already registered
        existing = self.env['farm.animal'].search([
            ('rfid_tag', '=', normalized)
        ])
        if existing and existing.id != animal_id:
            raise ValueError(f'RFID tag already registered to {existing.name}')

        if is_primary:
            animal.rfid_tag = normalized
        else:
            self.env['farm.animal.rfid'].create({
                'animal_id': animal_id,
                'rfid_tag': normalized,
            })

        _logger.info(f'Registered RFID {normalized} for animal {animal.name}')
        return True
```

#### Dev 3 (Frontend Lead) - 8 hours

**Task 3.1: Real-time Production Dashboard (4h)**

```javascript
/** @odoo-module */
// odoo/addons/smart_dairy_iot/static/src/js/milk_production_dashboard.js

import { Component, useState, onWillStart, onMounted, onWillUnmount } from "@odoo/owl";
import { registry } from "@web/core/registry";
import { useService } from "@web/core/utils/hooks";

export class MilkProductionDashboard extends Component {
    static template = "smart_dairy_iot.MilkProductionDashboard";

    setup() {
        this.orm = useService("orm");
        this.notification = useService("notification");

        this.state = useState({
            loading: true,
            // Today's summary
            todayStats: {
                totalYield: 0,
                sessionCount: 0,
                activeMeters: 0,
                avgYieldPerSession: 0,
            },
            // Active sessions
            activeSessions: [],
            // Recent sessions
            recentSessions: [],
            // Hourly production chart data
            hourlyData: [],
            // Quality breakdown
            qualityBreakdown: {
                A: 0,
                B: 0,
                C: 0,
                reject: 0,
            },
            // Alerts
            alerts: [],
            // Selected farm
            selectedFarm: null,
            farms: [],
        });

        this.refreshInterval = null;
        this.wsConnection = null;

        onWillStart(async () => {
            await this.loadInitialData();
        });

        onMounted(() => {
            this.startRealTimeUpdates();
            this.refreshInterval = setInterval(() => this.refreshData(), 30000);
        });

        onWillUnmount(() => {
            this.stopRealTimeUpdates();
            if (this.refreshInterval) {
                clearInterval(this.refreshInterval);
            }
        });
    }

    async loadInitialData() {
        try {
            // Load farms
            const farms = await this.orm.searchRead(
                "farm.location",
                [],
                ["id", "name", "code"]
            );
            this.state.farms = farms;

            await this.refreshData();
        } catch (error) {
            console.error("Error loading initial data:", error);
            this.notification.add("Failed to load dashboard data", { type: "danger" });
        }
    }

    async refreshData() {
        this.state.loading = true;

        try {
            const today = new Date().toISOString().split('T')[0];

            // Build domain
            const domain = [
                ['start_time', '>=', `${today} 00:00:00`],
            ];

            if (this.state.selectedFarm) {
                domain.push(['meter_id.farm_id', '=', this.state.selectedFarm]);
            }

            // Get today's sessions
            const sessions = await this.orm.searchRead(
                "milk.session",
                domain,
                [
                    "name", "animal_id", "meter_id", "state",
                    "total_yield", "current_yield", "start_time", "end_time",
                    "quality_grade", "conductivity", "is_abnormal"
                ],
                { order: "start_time DESC" }
            );

            // Calculate statistics
            const completedSessions = sessions.filter(s => s.state === 'completed');
            const activeSessions = sessions.filter(s => s.state === 'in_progress');

            this.state.todayStats = {
                totalYield: completedSessions.reduce((sum, s) => sum + (s.total_yield || 0), 0),
                sessionCount: completedSessions.length,
                activeMeters: activeSessions.length,
                avgYieldPerSession: completedSessions.length > 0
                    ? completedSessions.reduce((sum, s) => sum + (s.total_yield || 0), 0) / completedSessions.length
                    : 0,
            };

            this.state.activeSessions = activeSessions;
            this.state.recentSessions = completedSessions.slice(0, 10);

            // Quality breakdown
            const breakdown = { A: 0, B: 0, C: 0, reject: 0 };
            completedSessions.forEach(s => {
                if (s.quality_grade && breakdown.hasOwnProperty(s.quality_grade)) {
                    breakdown[s.quality_grade]++;
                }
            });
            this.state.qualityBreakdown = breakdown;

            // Calculate hourly data
            this.state.hourlyData = this.calculateHourlyData(completedSessions);

            // Load alerts
            const alerts = await this.orm.searchRead(
                "iot.device.alert",
                [
                    ['state', '=', 'active'],
                    ['device_id.device_type', '=', 'milk_meter']
                ],
                ["device_id", "severity", "message", "create_date"],
                { limit: 5, order: "create_date DESC" }
            );
            this.state.alerts = alerts;

        } catch (error) {
            console.error("Error refreshing data:", error);
        } finally {
            this.state.loading = false;
        }
    }

    calculateHourlyData(sessions) {
        const hourlyMap = {};
        for (let i = 0; i < 24; i++) {
            hourlyMap[i] = 0;
        }

        sessions.forEach(session => {
            if (session.start_time) {
                const hour = new Date(session.start_time).getHours();
                hourlyMap[hour] += session.total_yield || 0;
            }
        });

        return Object.entries(hourlyMap).map(([hour, yield_]) => ({
            hour: parseInt(hour),
            label: `${hour}:00`,
            yield: yield_.toFixed(1),
        }));
    }

    startRealTimeUpdates() {
        // Connect to WebSocket for real-time updates
        const wsUrl = `ws://${window.location.host}/ws/iot/milk_production`;
        try {
            this.wsConnection = new WebSocket(wsUrl);

            this.wsConnection.onmessage = (event) => {
                const data = JSON.parse(event.data);
                this.handleRealtimeUpdate(data);
            };

            this.wsConnection.onerror = (error) => {
                console.warn("WebSocket error:", error);
            };
        } catch (error) {
            console.warn("WebSocket not available:", error);
        }
    }

    stopRealTimeUpdates() {
        if (this.wsConnection) {
            this.wsConnection.close();
            this.wsConnection = null;
        }
    }

    handleRealtimeUpdate(data) {
        if (data.type === 'session_update') {
            // Update active session
            const idx = this.state.activeSessions.findIndex(s => s.id === data.session_id);
            if (idx >= 0) {
                this.state.activeSessions[idx].current_yield = data.current_yield;
            }
        } else if (data.type === 'session_complete') {
            // Move from active to recent
            this.refreshData();
        }
    }

    onFarmChange(event) {
        this.state.selectedFarm = event.target.value ? parseInt(event.target.value) : null;
        this.refreshData();
    }

    formatYield(yield_) {
        return (yield_ || 0).toFixed(1);
    }

    formatTime(dateStr) {
        if (!dateStr) return '-';
        return new Date(dateStr).toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    getQualityClass(grade) {
        const classes = {
            'A': 'success',
            'B': 'primary',
            'C': 'warning',
            'reject': 'danger',
        };
        return classes[grade] || 'secondary';
    }

    getSeverityClass(severity) {
        const classes = {
            'critical': 'danger',
            'warning': 'warning',
            'low': 'info',
        };
        return classes[severity] || 'secondary';
    }
}

MilkProductionDashboard.template = "smart_dairy_iot.MilkProductionDashboard";
registry.category("actions").add("milk_production_dashboard", MilkProductionDashboard);
```

**Task 3.2: Dashboard Template (4h)**

```xml
<!-- odoo/addons/smart_dairy_iot/static/src/xml/milk_production_dashboard.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<templates xml:space="preserve">
    <t t-name="smart_dairy_iot.MilkProductionDashboard">
        <div class="milk-production-dashboard p-4">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <h2>
                        <i class="fa fa-tint text-primary me-2"/>
                        Milk Production Dashboard
                    </h2>
                    <p class="text-muted mb-0">Real-time milk production monitoring</p>
                </div>
                <div class="col-md-4">
                    <select class="form-select" t-on-change="onFarmChange">
                        <option value="">All Farms</option>
                        <t t-foreach="state.farms" t-as="farm" t-key="farm.id">
                            <option t-att-value="farm.id" t-esc="farm.name"/>
                        </t>
                    </select>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body text-center">
                            <i class="fa fa-tint fa-2x mb-2"/>
                            <h2 class="mb-0" t-esc="formatYield(state.todayStats.totalYield)"/>
                            <p class="mb-0">Total Yield (L)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body text-center">
                            <i class="fa fa-check-circle fa-2x mb-2"/>
                            <h2 class="mb-0" t-esc="state.todayStats.sessionCount"/>
                            <p class="mb-0">Sessions Today</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body text-center">
                            <i class="fa fa-play-circle fa-2x mb-2"/>
                            <h2 class="mb-0" t-esc="state.todayStats.activeMeters"/>
                            <p class="mb-0">Active Now</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-secondary text-white h-100">
                        <div class="card-body text-center">
                            <i class="fa fa-line-chart fa-2x mb-2"/>
                            <h2 class="mb-0" t-esc="formatYield(state.todayStats.avgYieldPerSession)"/>
                            <p class="mb-0">Avg per Session (L)</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Active Sessions -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fa fa-play text-success me-2"/>
                                Active Milking Sessions
                            </h5>
                            <span class="badge bg-success" t-esc="state.activeSessions.length"/>
                        </div>
                        <div class="card-body">
                            <t t-if="state.activeSessions.length === 0">
                                <p class="text-muted text-center">No active sessions</p>
                            </t>
                            <t t-else="">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Meter</th>
                                                <th>Animal</th>
                                                <th>Current Yield</th>
                                                <th>Started</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <t t-foreach="state.activeSessions" t-as="session" t-key="session.id">
                                                <tr>
                                                    <td t-esc="session.meter_id[1]"/>
                                                    <td t-esc="session.animal_id ? session.animal_id[1] : 'Unknown'"/>
                                                    <td>
                                                        <strong t-esc="formatYield(session.current_yield)"/> L
                                                    </td>
                                                    <td t-esc="formatTime(session.start_time)"/>
                                                </tr>
                                            </t>
                                        </tbody>
                                    </table>
                                </div>
                            </t>
                        </div>
                    </div>
                </div>

                <!-- Quality Breakdown -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fa fa-star me-2"/>
                                Quality Breakdown
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <t t-foreach="['A', 'B', 'C', 'reject']" t-as="grade" t-key="grade">
                                    <div class="col-3 text-center">
                                        <div t-att-class="'badge bg-' + getQualityClass(grade) + ' p-3 mb-2'">
                                            <h3 class="mb-0" t-esc="state.qualityBreakdown[grade]"/>
                                        </div>
                                        <p class="small mb-0">
                                            Grade <t t-esc="grade === 'reject' ? 'Reject' : grade"/>
                                        </p>
                                    </div>
                                </t>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Sessions -->
                <div class="col-md-8 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fa fa-history me-2"/>
                                Recent Completed Sessions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Session</th>
                                            <th>Animal</th>
                                            <th>Yield (L)</th>
                                            <th>Quality</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <t t-foreach="state.recentSessions" t-as="session" t-key="session.id">
                                            <tr t-att-class="session.is_abnormal ? 'table-warning' : ''">
                                                <td t-esc="session.name"/>
                                                <td t-esc="session.animal_id ? session.animal_id[1] : 'Unknown'"/>
                                                <td>
                                                    <strong t-esc="formatYield(session.total_yield)"/>
                                                </td>
                                                <td>
                                                    <span
                                                        t-if="session.quality_grade"
                                                        t-att-class="'badge bg-' + getQualityClass(session.quality_grade)"
                                                    >
                                                        <t t-esc="session.quality_grade"/>
                                                    </span>
                                                </td>
                                                <td t-esc="formatTime(session.end_time)"/>
                                                <td>
                                                    <i t-if="session.is_abnormal" class="fa fa-exclamation-triangle text-warning" title="Abnormal"/>
                                                    <i t-else="" class="fa fa-check text-success"/>
                                                </td>
                                            </tr>
                                        </t>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alerts -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fa fa-bell text-warning me-2"/>
                                Active Alerts
                            </h5>
                        </div>
                        <div class="card-body">
                            <t t-if="state.alerts.length === 0">
                                <p class="text-muted text-center">No active alerts</p>
                            </t>
                            <t t-else="">
                                <t t-foreach="state.alerts" t-as="alert" t-key="alert.id">
                                    <div t-att-class="'alert alert-' + getSeverityClass(alert.severity) + ' mb-2'">
                                        <strong t-esc="alert.device_id[1]"/>
                                        <p class="mb-0 small" t-esc="alert.message"/>
                                    </div>
                                </t>
                            </t>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </t>
</templates>
```

---

### Days 672-680: Remaining Development

**Day 672:** Modbus TCP Implementation, Quality Parameters Model
**Day 673:** Milk Meter Gateway Service, Data Validation
**Day 674:** Calibration Management System, Equipment Diagnostics
**Day 675:** Mastitis Alert Algorithm, Health Event Integration
**Day 676:** Mobile Production View (Flutter), Offline Support
**Day 677:** Production Reports, Export Functionality
**Day 678:** Historical Analysis Charts, Trend Visualization
**Day 679:** Unit Tests, Integration Tests with Hardware Simulators
**Day 680:** Documentation, Demo with Live Meters, Sign-off

---

## 7. Technical Specifications

### 7.1 Modbus Register Maps

#### DeLaval MM27BC Register Map
| Register | Description | Type | Unit |
|----------|-------------|------|------|
| 0-1 | Milk Yield | Float | Liters |
| 2-3 | Flow Rate | Float | L/min |
| 4-5 | Conductivity | Float | mS/cm |
| 6-7 | Temperature | Float | °C |
| 8 | Session Time | UInt16 | seconds |
| 10-13 | RFID Tag | UInt64 | Hex |
| 14 | Status Bits | UInt16 | Bitmap |

### 7.2 Database Schema

```sql
-- Milk sessions table
CREATE TABLE milk_session (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE,
    meter_id INTEGER REFERENCES iot_milk_meter(id),
    animal_id INTEGER REFERENCES farm_animal(id),
    rfid_tag VARCHAR(20),
    start_time TIMESTAMP NOT NULL,
    end_time TIMESTAMP,
    total_yield NUMERIC(10, 3),
    fat_percentage NUMERIC(5, 2),
    protein_percentage NUMERIC(5, 2),
    somatic_cell_count INTEGER,
    conductivity NUMERIC(6, 3),
    quality_grade VARCHAR(10),
    state VARCHAR(20) DEFAULT 'in_progress'
);

CREATE INDEX idx_milk_session_animal ON milk_session(animal_id);
CREATE INDEX idx_milk_session_date ON milk_session(start_time);
CREATE INDEX idx_milk_session_rfid ON milk_session(rfid_tag);
```

---

## 8. Milestone Sign-off Checklist

- [ ] Milk Meter Device Model complete
- [ ] Modbus RTU communication working with DeLaval meters
- [ ] Modbus TCP communication working
- [ ] Milk Session recording accurate
- [ ] RFID animal association 99.9% accurate
- [ ] Quality parameters captured (fat, protein, SCC, conductivity)
- [ ] Real-time Production Dashboard functional
- [ ] Mastitis Alert System detecting elevated conductivity
- [ ] Mobile Production View (Flutter) working
- [ ] Calibration Management implemented
- [ ] Unit tests passing (>80% coverage)
- [ ] Integration tests with hardware simulators passing
- [ ] Documentation complete
- [ ] Demo with live meters successful

---

**Document End**

*Last Updated: 2026-02-04*
*Milestone 93: Milk Meter Integration*
