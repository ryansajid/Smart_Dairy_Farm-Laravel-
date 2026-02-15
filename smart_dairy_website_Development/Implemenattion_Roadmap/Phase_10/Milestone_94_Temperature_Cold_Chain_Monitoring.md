# Milestone 94: Temperature & Cold Chain Monitoring

## Smart Dairy Digital Smart Portal + ERP System (Odoo 19 CE)

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P10-M94-v1.0 |
| **Milestone** | 94 of 100 |
| **Phase** | Phase 10: Commerce - IoT Integration Core |
| **Days** | 681-690 (10 working days) |
| **Duration** | 2 weeks |
| **Developers** | 3 Full Stack Developers |
| **Total Effort** | 240 person-hours |
| **Status** | Planned |
| **Version** | 1.0 |
| **Last Updated** | 2026-02-04 |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 94 implements comprehensive temperature and cold chain monitoring for the Smart Dairy platform, ensuring BSTI regulatory compliance and food safety. This milestone establishes real-time environmental monitoring across the entire cold chain from milk collection through storage and transport.

### 1.2 Scope

This milestone covers:
- Environmental sensor device model and integration
- TimescaleDB hypertable for temperature readings
- Cold storage zone configuration and management
- Alert thresholds with escalation workflows
- BSTI compliance reporting and documentation
- HACCP integration points
- Environmental monitoring dashboards
- Mobile temperature alert system

### 1.3 Key Outcomes

| Outcome | Success Measure |
|---------|-----------------|
| Sensor Integration | 100% of cold storage zones monitored |
| Data Collection | 1-minute temperature readings |
| Alert Response | <30 second alert delivery |
| BSTI Compliance | Automated compliance reporting |
| Dashboard | Real-time temperature visualization |
| Mobile Alerts | Push notifications within 1 minute |

---

## 2. Objectives

| # | Objective | Priority | Measurable Target |
|---|-----------|----------|-------------------|
| O1 | Implement environmental sensor model with multi-type support | Critical | Support temperature, humidity, CO2, NH3 sensors |
| O2 | Create TimescaleDB hypertable for high-frequency readings | Critical | Handle 100K+ readings/day with <10ms insert |
| O3 | Build cold storage zone management system | High | Configure unlimited zones with custom thresholds |
| O4 | Develop alert threshold and escalation system | Critical | <30 second alert delivery, 3-tier escalation |
| O5 | Implement BSTI compliance reporting | High | Automated daily/weekly/monthly reports |
| O6 | Create HACCP integration for food safety | High | Critical Control Point documentation |
| O7 | Build real-time environmental dashboard | High | 5-second refresh, multi-zone view |
| O8 | Develop mobile temperature alert system | Medium | Push notifications with acknowledge |

---

## 3. Key Deliverables

| # | Deliverable | Description | Owner | Priority |
|---|-------------|-------------|-------|----------|
| D1 | Environmental Sensor Model | `iot.environmental_sensor` Odoo model | Dev 1 | Critical |
| D2 | Temperature Reading Hypertable | TimescaleDB time-series storage | Dev 2 | Critical |
| D3 | Cold Storage Zone Model | Zone configuration with thresholds | Dev 1 | High |
| D4 | Alert Threshold Engine | Configurable multi-level alerts | Dev 1 | Critical |
| D5 | Alert Escalation Workflow | Timed escalation to managers | Dev 2 | High |
| D6 | BSTI Compliance Reports | Regulatory compliance documents | Dev 2 | High |
| D7 | HACCP CCP Integration | Critical Control Point tracking | Dev 1 | High |
| D8 | Environmental Dashboard | Real-time OWL visualization | Dev 3 | High |
| D9 | Zone Map Visualization | Spatial temperature display | Dev 3 | Medium |
| D10 | Mobile Alert Component | Flutter push notification handler | Dev 3 | High |
| D11 | Temperature Trend Analysis | Historical trend charts | Dev 3 | Medium |
| D12 | Cold Chain Report Generator | PDF/Excel compliance reports | Dev 2 | High |

---

## 4. Prerequisites

### 4.1 From Previous Phases
| Prerequisite | Phase/Milestone | Status |
|--------------|-----------------|--------|
| PostgreSQL 16 database | Phase 1 | Complete |
| Odoo 19 CE installation | Phase 1 | Complete |
| User authentication system | Phase 2 | Complete |
| Farm and location models | Phase 5 | Complete |
| Notification infrastructure | Phase 6 | Complete |
| Mobile app framework | Phase 7 | Complete |
| B2B portal base | Phase 9 | Complete |

### 4.2 From Phase 10
| Prerequisite | Milestone | Status |
|--------------|-----------|--------|
| MQTT infrastructure | M91 | Complete |
| Device registry | M92 | Complete |
| IoT device base model | M92 | Complete |
| TimescaleDB setup | M91 | Complete |

---

## 5. Requirement Traceability

### 5.1 RFP Requirements
| RFP ID | Requirement | Implementation |
|--------|-------------|----------------|
| IOT-004 | Cold chain temperature monitoring | Full implementation |
| IOT-004.1 | Real-time temperature tracking | 1-minute readings |
| IOT-004.2 | Multi-zone monitoring | Zone model with hierarchy |
| IOT-004.3 | Temperature alerts | Multi-threshold alerting |
| IOT-004.4 | Compliance reporting | BSTI format reports |

### 5.2 BRD Requirements
| BRD ID | Business Requirement | Implementation |
|--------|---------------------|----------------|
| FR-IOT-002 | Cold chain compliance monitoring | BSTI/HACCP integration |
| FR-IOT-002.1 | Temperature excursion tracking | Alert history and resolution |
| FR-IOT-002.2 | Regulatory audit support | Exportable compliance data |
| FR-IOT-002.3 | Real-time visibility | Dashboard and mobile alerts |

### 5.3 SRS Technical Requirements
| SRS ID | Technical Requirement | Implementation |
|--------|----------------------|----------------|
| SRS-IOT-006 | Temperature data retention 2+ years | TimescaleDB with compression |
| SRS-IOT-007 | Alert latency <1 minute | WebSocket + FCM push |
| SRS-IOT-008 | Support 1000+ sensors | Scalable hypertable design |
| SRS-IOT-009 | 99.9% data capture | Redundant communication paths |

---

## 6. Day-by-Day Development Plan

### Day 681: Environmental Sensor Model & Base Setup

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-2 | Design environmental sensor model schema | UML diagram, field definitions |
| 2-5 | Implement `iot.environmental_sensor` model | Python model file |
| 5-7 | Create sensor type enumeration and validation | Sensor type configs |
| 7-8 | Write model unit tests | pytest test file |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Design TimescaleDB hypertable schema | SQL migration scripts |
| 3-6 | Implement temperature reading hypertable | Database tables created |
| 6-8 | Create continuous aggregates for hourly/daily | Materialized views |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-2 | Design environmental dashboard wireframes | Figma mockups |
| 2-5 | Create dashboard OWL component structure | Component scaffolding |
| 5-8 | Implement base temperature display widget | OWL component |

---

### Day 682: Cold Storage Zone Management

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement cold storage zone model | `iot.cold_storage_zone` model |
| 3-5 | Create zone-sensor relationship | Many2many linkage |
| 5-7 | Implement zone hierarchy (location > zone > sub-zone) | Hierarchical structure |
| 7-8 | Write zone management unit tests | Test coverage |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create zone configuration API endpoints | RESTful APIs |
| 3-5 | Implement zone threshold configuration | Threshold models |
| 5-8 | Build bulk zone import wizard | CSV import functionality |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create zone management form view | OWL form component |
| 3-6 | Implement zone list with status indicators | Zone listing page |
| 6-8 | Build zone configuration wizard UI | Multi-step wizard |

---

### Day 683: Alert Threshold Configuration

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Design alert threshold model | `iot.temperature_threshold` model |
| 3-5 | Implement multi-level threshold (warning/critical/emergency) | Threshold levels |
| 5-7 | Create threshold evaluation service | Python service class |
| 7-8 | Unit tests for threshold logic | Test cases |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement MQTT temperature message handler | Message processor |
| 3-5 | Create real-time threshold checking service | Threshold checker |
| 5-8 | Build alert generation pipeline | Alert creation flow |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create threshold configuration form | OWL form component |
| 3-5 | Implement visual threshold editor | Range slider UI |
| 5-8 | Build threshold preview visualization | Chart preview |

---

### Day 684: Alert Escalation Workflow

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Design escalation workflow model | `iot.alert_escalation` model |
| 3-5 | Implement escalation rules and timing | Rule engine |
| 5-7 | Create escalation scheduler (cron) | Scheduled action |
| 7-8 | Build alert acknowledgment system | Ack workflow |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement notification dispatcher for alerts | Multi-channel dispatch |
| 3-5 | Create SMS alert integration (SSL Wireless) | SMS service |
| 5-8 | Build WebSocket alert push service | Real-time push |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create alert notification component | Toast/banner alerts |
| 3-5 | Implement alert acknowledgment UI | Acknowledge dialog |
| 5-8 | Build escalation timeline visualization | Timeline component |

---

### Day 685: BSTI Compliance Framework

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Research BSTI temperature compliance requirements | Compliance specs |
| 3-6 | Implement BSTI compliance rule model | `iot.bsti_compliance_rule` |
| 6-8 | Create compliance validation service | Validation logic |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Design BSTI report templates | Report structure |
| 3-6 | Implement daily compliance report generator | Report service |
| 6-8 | Create compliance data export (PDF/Excel) | Export functionality |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create compliance dashboard section | Dashboard widget |
| 3-5 | Implement compliance status indicators | Status badges |
| 5-8 | Build compliance calendar view | Calendar component |

---

### Day 686: HACCP Integration

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement HACCP Critical Control Point model | `iot.haccp_ccp` model |
| 3-5 | Create CCP monitoring linkage to sensors | Sensor-CCP mapping |
| 5-7 | Build corrective action workflow | Action tracking |
| 7-8 | Write HACCP integration tests | Test coverage |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement HACCP audit log model | Audit trail |
| 3-5 | Create CCP deviation tracking | Deviation records |
| 5-8 | Build HACCP verification reports | Verification docs |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create HACCP monitoring dashboard | CCP overview |
| 3-5 | Implement CCP status visualization | Status indicators |
| 5-8 | Build corrective action form | Action form UI |

---

### Day 687: Environmental Dashboard Development

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create dashboard data API endpoints | REST APIs |
| 3-5 | Implement WebSocket data streaming | Real-time stream |
| 5-8 | Build historical data query service | Query optimization |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement Redis caching for dashboard data | Cache layer |
| 3-5 | Create dashboard widget configuration model | Widget configs |
| 5-8 | Build dashboard export functionality | Screenshot/PDF |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement main environmental dashboard | Multi-zone view |
| 3-5 | Create temperature gauge components | Gauge widgets |
| 5-8 | Build zone map with heat overlay | Spatial visualization |

---

### Day 688: Mobile Temperature Alerts

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement FCM push notification for alerts | Firebase integration |
| 3-5 | Create mobile-specific alert payload format | Payload structure |
| 5-8 | Build alert preference model per user | User preferences |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement offline alert queue | Queue system |
| 3-5 | Create alert delivery confirmation tracking | Delivery status |
| 5-8 | Build alert statistics and analytics | Alert metrics |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create Flutter temperature alert screen | Mobile UI |
| 3-5 | Implement push notification handler | FCM handler |
| 5-8 | Build mobile alert history and acknowledgment | Mobile workflow |

---

### Day 689: Testing & Integration

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write comprehensive unit tests | 80%+ coverage |
| 3-5 | Create integration tests for alert flow | Integration tests |
| 5-8 | Perform code review and optimization | Code quality |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Test TimescaleDB performance | Load testing |
| 3-5 | Security testing for APIs | Security audit |
| 5-8 | Integration testing with MQTT | End-to-end tests |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | UI component testing | Component tests |
| 3-5 | Cross-browser testing | Compatibility |
| 5-8 | Mobile app testing on devices | Device testing |

---

### Day 690: Documentation & Demo

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write API documentation | API docs |
| 3-5 | Create deployment guide | Deployment docs |
| 5-8 | Bug fixes and final adjustments | Stable release |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write database documentation | Schema docs |
| 3-5 | Create compliance report templates | Report templates |
| 5-8 | Prepare demo environment | Demo setup |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write user guide for dashboards | User docs |
| 3-5 | Create demo script and scenarios | Demo materials |
| 5-8 | Conduct milestone demo | Demo presentation |

---

## 7. Technical Specifications

### 7.1 Environmental Sensor Model

```python
# smart_dairy_iot/models/iot_environmental_sensor.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError
import json

class IoTEnvironmentalSensor(models.Model):
    _name = 'iot.environmental_sensor'
    _description = 'Environmental Monitoring Sensor'
    _inherit = ['iot.device']
    _order = 'zone_id, name'

    # Sensor Type
    sensor_type = fields.Selection([
        ('temperature', 'Temperature Sensor'),
        ('humidity', 'Humidity Sensor'),
        ('temp_humidity', 'Temperature & Humidity'),
        ('co2', 'CO2 Sensor'),
        ('ammonia', 'Ammonia (NH3) Sensor'),
        ('multi', 'Multi-Parameter Sensor'),
    ], string='Sensor Type', required=True, default='temperature')

    # Location & Zone
    zone_id = fields.Many2one(
        'iot.cold_storage_zone',
        string='Cold Storage Zone',
        required=True,
        ondelete='restrict'
    )
    installation_location = fields.Char(
        string='Installation Location',
        help='Specific location within zone (e.g., "Top shelf, back wall")'
    )
    gps_coordinates = fields.Char(string='GPS Coordinates')

    # Measurement Configuration
    measurement_interval = fields.Integer(
        string='Measurement Interval (seconds)',
        default=60,
        help='How often the sensor reports readings'
    )
    transmission_interval = fields.Integer(
        string='Transmission Interval (seconds)',
        default=60,
        help='How often data is transmitted to server'
    )

    # Calibration
    last_calibration_date = fields.Date(string='Last Calibration')
    next_calibration_date = fields.Date(string='Next Calibration Due')
    calibration_offset = fields.Float(
        string='Calibration Offset',
        digits=(6, 2),
        help='Offset to apply to readings'
    )
    calibration_certificate = fields.Binary(string='Calibration Certificate')

    # Current Readings (cached from MQTT)
    current_temperature = fields.Float(
        string='Current Temperature (°C)',
        digits=(5, 2),
        readonly=True
    )
    current_humidity = fields.Float(
        string='Current Humidity (%)',
        digits=(5, 2),
        readonly=True
    )
    current_co2 = fields.Float(
        string='Current CO2 (ppm)',
        digits=(8, 2),
        readonly=True
    )
    current_ammonia = fields.Float(
        string='Current Ammonia (ppm)',
        digits=(6, 3),
        readonly=True
    )
    last_reading_time = fields.Datetime(
        string='Last Reading Time',
        readonly=True
    )

    # Alert Status
    alert_status = fields.Selection([
        ('normal', 'Normal'),
        ('warning', 'Warning'),
        ('critical', 'Critical'),
        ('emergency', 'Emergency'),
    ], string='Alert Status', default='normal', readonly=True)
    active_alert_ids = fields.One2many(
        'iot.temperature_alert',
        'sensor_id',
        string='Active Alerts',
        domain=[('state', '=', 'active')]
    )

    # Communication
    communication_protocol = fields.Selection([
        ('mqtt', 'MQTT'),
        ('lorawan', 'LoRaWAN'),
        ('modbus', 'Modbus TCP/RTU'),
        ('zigbee', 'ZigBee'),
        ('wifi', 'WiFi Direct'),
    ], string='Communication Protocol', default='mqtt')
    lorawan_dev_eui = fields.Char(string='LoRaWAN DevEUI')
    lorawan_app_key = fields.Char(string='LoRaWAN AppKey')

    # Thresholds (linked or custom)
    use_zone_thresholds = fields.Boolean(
        string='Use Zone Thresholds',
        default=True
    )
    custom_threshold_ids = fields.One2many(
        'iot.temperature_threshold',
        'sensor_id',
        string='Custom Thresholds'
    )

    @api.constrains('measurement_interval')
    def _check_measurement_interval(self):
        for sensor in self:
            if sensor.measurement_interval < 10:
                raise ValidationError(
                    'Measurement interval must be at least 10 seconds'
                )

    def process_mqtt_reading(self, payload):
        """Process incoming MQTT temperature reading"""
        self.ensure_one()

        data = json.loads(payload) if isinstance(payload, str) else payload

        update_vals = {
            'last_reading_time': fields.Datetime.now(),
        }

        # Apply calibration offset
        if 'temperature' in data:
            temp = float(data['temperature']) + (self.calibration_offset or 0)
            update_vals['current_temperature'] = temp

        if 'humidity' in data:
            update_vals['current_humidity'] = float(data['humidity'])

        if 'co2' in data:
            update_vals['current_co2'] = float(data['co2'])

        if 'ammonia' in data:
            update_vals['current_ammonia'] = float(data['ammonia'])

        self.write(update_vals)

        # Store in TimescaleDB
        self._store_reading_timescale(data)

        # Check thresholds
        self._check_thresholds()

        return True

    def _store_reading_timescale(self, data):
        """Store reading in TimescaleDB hypertable"""
        self.env.cr.execute("""
            INSERT INTO iot_environmental_readings (
                time, sensor_id, device_uid, zone_id, farm_id,
                temperature, humidity, co2, ammonia,
                battery_level, signal_strength
            ) VALUES (
                NOW(), %s, %s, %s, %s,
                %s, %s, %s, %s, %s, %s
            )
        """, (
            self.id,
            self.device_uid,
            self.zone_id.id,
            self.zone_id.farm_id.id,
            data.get('temperature'),
            data.get('humidity'),
            data.get('co2'),
            data.get('ammonia'),
            data.get('battery'),
            data.get('rssi'),
        ))

    def _check_thresholds(self):
        """Check current readings against thresholds"""
        self.ensure_one()

        # Get applicable thresholds
        if self.use_zone_thresholds:
            thresholds = self.zone_id.threshold_ids
        else:
            thresholds = self.custom_threshold_ids

        highest_severity = 'normal'

        for threshold in thresholds:
            violated = threshold.check_violation(
                self.current_temperature,
                self.current_humidity
            )
            if violated:
                severity = threshold.severity
                if self._severity_rank(severity) > self._severity_rank(highest_severity):
                    highest_severity = severity

                # Create alert if not already active
                self._create_alert_if_needed(threshold, violated)

        self.alert_status = highest_severity

    def _severity_rank(self, severity):
        """Get numeric rank for severity comparison"""
        ranks = {'normal': 0, 'warning': 1, 'critical': 2, 'emergency': 3}
        return ranks.get(severity, 0)

    def _create_alert_if_needed(self, threshold, violation_type):
        """Create alert if no active alert for this threshold"""
        existing = self.env['iot.temperature_alert'].search([
            ('sensor_id', '=', self.id),
            ('threshold_id', '=', threshold.id),
            ('state', '=', 'active'),
        ], limit=1)

        if not existing:
            self.env['iot.temperature_alert'].create({
                'sensor_id': self.id,
                'zone_id': self.zone_id.id,
                'threshold_id': threshold.id,
                'severity': threshold.severity,
                'violation_type': violation_type,
                'reading_value': self.current_temperature,
                'threshold_value': threshold.max_temperature if violation_type == 'high' else threshold.min_temperature,
            })

    def action_view_readings_history(self):
        """Open historical readings dashboard"""
        self.ensure_one()
        return {
            'type': 'ir.actions.act_window',
            'name': f'Temperature History - {self.name}',
            'res_model': 'iot.environmental_reading.report',
            'view_mode': 'graph,pivot',
            'context': {'default_sensor_id': self.id},
            'domain': [('sensor_id', '=', self.id)],
        }
```

### 7.2 Cold Storage Zone Model

```python
# smart_dairy_iot/models/iot_cold_storage_zone.py

from odoo import models, fields, api

class IoTColdStorageZone(models.Model):
    _name = 'iot.cold_storage_zone'
    _description = 'Cold Storage Zone'
    _parent_name = 'parent_id'
    _parent_store = True
    _order = 'parent_path, sequence, name'

    name = fields.Char(string='Zone Name', required=True)
    code = fields.Char(string='Zone Code', required=True)
    sequence = fields.Integer(string='Sequence', default=10)
    active = fields.Boolean(default=True)

    # Hierarchy
    parent_id = fields.Many2one(
        'iot.cold_storage_zone',
        string='Parent Zone',
        ondelete='cascade'
    )
    parent_path = fields.Char(index=True, unaccent=False)
    child_ids = fields.One2many(
        'iot.cold_storage_zone',
        'parent_id',
        string='Sub-Zones'
    )

    # Location
    farm_id = fields.Many2one(
        'res.partner',
        string='Farm',
        domain=[('is_farm', '=', True)],
        required=True
    )
    location_id = fields.Many2one(
        'stock.location',
        string='Warehouse Location',
        help='Link to inventory location'
    )
    building = fields.Char(string='Building')
    floor = fields.Char(string='Floor/Level')
    area_sqm = fields.Float(string='Area (sq.m)')
    volume_cubic_m = fields.Float(string='Volume (cubic m)')

    # Zone Type
    zone_type = fields.Selection([
        ('bulk_tank', 'Bulk Tank'),
        ('cold_room', 'Cold Room'),
        ('chiller', 'Chiller Unit'),
        ('freezer', 'Freezer'),
        ('transport', 'Transport Vehicle'),
        ('processing', 'Processing Area'),
        ('packaging', 'Packaging Area'),
        ('loading_dock', 'Loading Dock'),
    ], string='Zone Type', required=True)

    # Temperature Requirements
    target_temperature = fields.Float(
        string='Target Temperature (°C)',
        digits=(5, 2),
        default=4.0
    )
    min_temperature = fields.Float(
        string='Min Temperature (°C)',
        digits=(5, 2),
        default=2.0
    )
    max_temperature = fields.Float(
        string='Max Temperature (°C)',
        digits=(5, 2),
        default=6.0
    )
    temperature_tolerance = fields.Float(
        string='Tolerance (±°C)',
        digits=(4, 2),
        default=0.5
    )

    # Humidity Requirements
    target_humidity = fields.Float(
        string='Target Humidity (%)',
        digits=(5, 2)
    )
    min_humidity = fields.Float(string='Min Humidity (%)', digits=(5, 2))
    max_humidity = fields.Float(string='Max Humidity (%)', digits=(5, 2))

    # Current Status (computed from sensors)
    current_temperature = fields.Float(
        string='Current Avg Temperature',
        compute='_compute_current_status',
        digits=(5, 2)
    )
    current_humidity = fields.Float(
        string='Current Avg Humidity',
        compute='_compute_current_status',
        digits=(5, 2)
    )
    zone_status = fields.Selection([
        ('normal', 'Normal'),
        ('warning', 'Warning'),
        ('critical', 'Critical'),
        ('offline', 'Offline'),
    ], string='Zone Status', compute='_compute_current_status')

    # Sensors
    sensor_ids = fields.One2many(
        'iot.environmental_sensor',
        'zone_id',
        string='Sensors'
    )
    sensor_count = fields.Integer(
        string='Sensor Count',
        compute='_compute_sensor_count'
    )

    # Thresholds
    threshold_ids = fields.One2many(
        'iot.temperature_threshold',
        'zone_id',
        string='Alert Thresholds'
    )

    # Compliance
    bsti_compliant = fields.Boolean(
        string='BSTI Compliant',
        compute='_compute_compliance'
    )
    haccp_ccp_id = fields.Many2one(
        'iot.haccp_ccp',
        string='HACCP Critical Control Point'
    )
    compliance_notes = fields.Text(string='Compliance Notes')

    @api.depends('sensor_ids.current_temperature', 'sensor_ids.current_humidity', 'sensor_ids.alert_status')
    def _compute_current_status(self):
        for zone in self:
            active_sensors = zone.sensor_ids.filtered(
                lambda s: s.device_status == 'online'
            )

            if not active_sensors:
                zone.current_temperature = 0
                zone.current_humidity = 0
                zone.zone_status = 'offline'
            else:
                zone.current_temperature = sum(
                    s.current_temperature for s in active_sensors
                ) / len(active_sensors)
                zone.current_humidity = sum(
                    s.current_humidity for s in active_sensors
                    if s.current_humidity
                ) / len(active_sensors) if any(s.current_humidity for s in active_sensors) else 0

                # Determine worst status
                statuses = active_sensors.mapped('alert_status')
                if 'emergency' in statuses or 'critical' in statuses:
                    zone.zone_status = 'critical'
                elif 'warning' in statuses:
                    zone.zone_status = 'warning'
                else:
                    zone.zone_status = 'normal'

    @api.depends('sensor_ids')
    def _compute_sensor_count(self):
        for zone in self:
            zone.sensor_count = len(zone.sensor_ids)

    @api.depends('current_temperature', 'min_temperature', 'max_temperature')
    def _compute_compliance(self):
        for zone in self:
            if zone.zone_status == 'offline':
                zone.bsti_compliant = False
            else:
                zone.bsti_compliant = (
                    zone.min_temperature <= zone.current_temperature <= zone.max_temperature
                )

    def action_view_sensors(self):
        """Open sensors in this zone"""
        self.ensure_one()
        return {
            'type': 'ir.actions.act_window',
            'name': f'Sensors - {self.name}',
            'res_model': 'iot.environmental_sensor',
            'view_mode': 'tree,form',
            'domain': [('zone_id', '=', self.id)],
            'context': {'default_zone_id': self.id},
        }

    def action_view_alerts(self):
        """Open alerts for this zone"""
        self.ensure_one()
        return {
            'type': 'ir.actions.act_window',
            'name': f'Alerts - {self.name}',
            'res_model': 'iot.temperature_alert',
            'view_mode': 'tree,form',
            'domain': [('zone_id', '=', self.id)],
        }
```

### 7.3 Temperature Alert Model

```python
# smart_dairy_iot/models/iot_temperature_alert.py

from odoo import models, fields, api
from datetime import timedelta

class IoTTemperatureAlert(models.Model):
    _name = 'iot.temperature_alert'
    _description = 'Temperature Alert'
    _order = 'create_date desc'
    _inherit = ['mail.thread', 'mail.activity.mixin']

    name = fields.Char(
        string='Alert Reference',
        required=True,
        copy=False,
        readonly=True,
        default='New'
    )

    # Source
    sensor_id = fields.Many2one(
        'iot.environmental_sensor',
        string='Sensor',
        required=True,
        ondelete='cascade'
    )
    zone_id = fields.Many2one(
        'iot.cold_storage_zone',
        string='Zone',
        related='sensor_id.zone_id',
        store=True
    )
    farm_id = fields.Many2one(
        'res.partner',
        string='Farm',
        related='zone_id.farm_id',
        store=True
    )

    # Alert Details
    threshold_id = fields.Many2one(
        'iot.temperature_threshold',
        string='Triggered Threshold'
    )
    severity = fields.Selection([
        ('warning', 'Warning'),
        ('critical', 'Critical'),
        ('emergency', 'Emergency'),
    ], string='Severity', required=True, tracking=True)
    violation_type = fields.Selection([
        ('high', 'Temperature Too High'),
        ('low', 'Temperature Too Low'),
        ('humidity_high', 'Humidity Too High'),
        ('humidity_low', 'Humidity Too Low'),
        ('rate_of_change', 'Rapid Temperature Change'),
    ], string='Violation Type', required=True)

    # Reading at Alert Time
    reading_value = fields.Float(
        string='Reading Value',
        digits=(6, 2),
        required=True
    )
    threshold_value = fields.Float(
        string='Threshold Value',
        digits=(6, 2)
    )
    deviation = fields.Float(
        string='Deviation',
        compute='_compute_deviation',
        digits=(6, 2)
    )

    # State
    state = fields.Selection([
        ('active', 'Active'),
        ('acknowledged', 'Acknowledged'),
        ('resolved', 'Resolved'),
        ('escalated', 'Escalated'),
    ], string='State', default='active', tracking=True)

    # Timestamps
    alert_time = fields.Datetime(
        string='Alert Time',
        default=fields.Datetime.now,
        required=True
    )
    acknowledged_time = fields.Datetime(string='Acknowledged Time')
    resolved_time = fields.Datetime(string='Resolved Time')
    duration_minutes = fields.Float(
        string='Duration (minutes)',
        compute='_compute_duration'
    )

    # Acknowledgment
    acknowledged_by = fields.Many2one(
        'res.users',
        string='Acknowledged By'
    )
    acknowledgment_notes = fields.Text(string='Acknowledgment Notes')

    # Resolution
    resolved_by = fields.Many2one('res.users', string='Resolved By')
    resolution_notes = fields.Text(string='Resolution Notes')
    corrective_action = fields.Text(string='Corrective Action Taken')
    root_cause = fields.Selection([
        ('equipment_failure', 'Equipment Failure'),
        ('power_outage', 'Power Outage'),
        ('door_left_open', 'Door Left Open'),
        ('overload', 'Storage Overload'),
        ('sensor_malfunction', 'Sensor Malfunction'),
        ('ambient_temp', 'Ambient Temperature'),
        ('other', 'Other'),
    ], string='Root Cause')

    # Escalation
    escalation_level = fields.Integer(string='Escalation Level', default=0)
    escalated_to = fields.Many2one('res.users', string='Escalated To')
    next_escalation_time = fields.Datetime(string='Next Escalation Time')

    # Notifications
    notification_sent = fields.Boolean(string='Notification Sent', default=False)
    sms_sent = fields.Boolean(string='SMS Sent', default=False)
    push_sent = fields.Boolean(string='Push Notification Sent', default=False)

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            if vals.get('name', 'New') == 'New':
                vals['name'] = self.env['ir.sequence'].next_by_code(
                    'iot.temperature.alert'
                ) or 'New'

        alerts = super().create(vals_list)

        # Trigger notifications
        for alert in alerts:
            alert._send_notifications()
            alert._schedule_escalation()

        return alerts

    @api.depends('reading_value', 'threshold_value')
    def _compute_deviation(self):
        for alert in self:
            if alert.threshold_value:
                alert.deviation = abs(
                    alert.reading_value - alert.threshold_value
                )
            else:
                alert.deviation = 0

    @api.depends('alert_time', 'resolved_time')
    def _compute_duration(self):
        for alert in self:
            if alert.resolved_time:
                delta = alert.resolved_time - alert.alert_time
                alert.duration_minutes = delta.total_seconds() / 60
            elif alert.state == 'active':
                delta = fields.Datetime.now() - alert.alert_time
                alert.duration_minutes = delta.total_seconds() / 60
            else:
                alert.duration_minutes = 0

    def action_acknowledge(self):
        """Acknowledge the alert"""
        self.ensure_one()
        self.write({
            'state': 'acknowledged',
            'acknowledged_time': fields.Datetime.now(),
            'acknowledged_by': self.env.uid,
        })
        return True

    def action_resolve(self):
        """Open resolution wizard"""
        self.ensure_one()
        return {
            'type': 'ir.actions.act_window',
            'name': 'Resolve Alert',
            'res_model': 'iot.alert.resolve.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_alert_id': self.id},
        }

    def _send_notifications(self):
        """Send alert notifications"""
        self.ensure_one()

        # Get notification recipients based on severity
        recipients = self._get_notification_recipients()

        # Email notification
        template = self.env.ref(
            'smart_dairy_iot.email_template_temperature_alert'
        )
        for recipient in recipients:
            template.send_mail(self.id, email_values={
                'email_to': recipient.email
            })

        # SMS for critical/emergency
        if self.severity in ('critical', 'emergency'):
            self._send_sms_alert(recipients)

        # Push notification
        self._send_push_notification(recipients)

        self.notification_sent = True

    def _get_notification_recipients(self):
        """Get users to notify based on escalation rules"""
        escalation = self.env['iot.alert_escalation'].search([
            ('zone_id', '=', self.zone_id.id),
            ('severity', '=', self.severity),
            ('level', '=', self.escalation_level),
        ], limit=1)

        if escalation:
            return escalation.user_ids

        # Default: zone manager
        return self.zone_id.farm_id.user_id

    def _send_sms_alert(self, recipients):
        """Send SMS alert via SSL Wireless"""
        sms_service = self.env['sms.api']

        message = (
            f"🚨 TEMPERATURE ALERT\n"
            f"Zone: {self.zone_id.name}\n"
            f"Temp: {self.reading_value}°C\n"
            f"Status: {self.severity.upper()}\n"
            f"Action required!"
        )

        for recipient in recipients:
            if recipient.mobile:
                sms_service.send_sms(recipient.mobile, message)

        self.sms_sent = True

    def _send_push_notification(self, recipients):
        """Send FCM push notification"""
        fcm_service = self.env['fcm.notification.service']

        data = {
            'alert_id': self.id,
            'alert_type': 'temperature',
            'severity': self.severity,
            'zone': self.zone_id.name,
            'value': self.reading_value,
            'click_action': 'OPEN_ALERT_DETAIL',
        }

        for recipient in recipients:
            fcm_service.send_notification(
                user_id=recipient.id,
                title=f'Temperature Alert - {self.severity.title()}',
                body=f'{self.zone_id.name}: {self.reading_value}°C',
                data=data,
                priority='high'
            )

        self.push_sent = True

    def _schedule_escalation(self):
        """Schedule next escalation"""
        self.ensure_one()

        escalation_config = self.env['iot.alert_escalation'].search([
            ('zone_id', '=', self.zone_id.id),
            ('severity', '=', self.severity),
            ('level', '=', self.escalation_level + 1),
        ], limit=1)

        if escalation_config:
            self.next_escalation_time = fields.Datetime.now() + timedelta(
                minutes=escalation_config.escalation_delay_minutes
            )

    @api.model
    def _cron_process_escalations(self):
        """Cron job to process alert escalations"""
        alerts_to_escalate = self.search([
            ('state', 'in', ['active', 'acknowledged']),
            ('next_escalation_time', '<=', fields.Datetime.now()),
        ])

        for alert in alerts_to_escalate:
            alert._escalate()

    def _escalate(self):
        """Escalate alert to next level"""
        self.ensure_one()

        next_level = self.escalation_level + 1
        escalation_config = self.env['iot.alert_escalation'].search([
            ('zone_id', '=', self.zone_id.id),
            ('severity', '=', self.severity),
            ('level', '=', next_level),
        ], limit=1)

        if escalation_config:
            self.write({
                'escalation_level': next_level,
                'state': 'escalated',
                'escalated_to': escalation_config.user_ids[0].id if escalation_config.user_ids else False,
            })

            # Send escalation notifications
            self._send_notifications()
            self._schedule_escalation()
```

### 7.4 TimescaleDB Schema

```sql
-- TimescaleDB Hypertable for Environmental Readings
-- smart_dairy_iot/data/timescaledb_environmental.sql

-- Enable TimescaleDB extension
CREATE EXTENSION IF NOT EXISTS timescaledb;

-- Environmental readings hypertable
CREATE TABLE IF NOT EXISTS iot_environmental_readings (
    time TIMESTAMPTZ NOT NULL,
    sensor_id INTEGER NOT NULL,
    device_uid VARCHAR(64) NOT NULL,
    zone_id INTEGER NOT NULL,
    farm_id INTEGER NOT NULL,

    -- Temperature
    temperature DOUBLE PRECISION,

    -- Humidity
    humidity DOUBLE PRECISION,

    -- Air Quality
    co2 DOUBLE PRECISION,
    ammonia DOUBLE PRECISION,

    -- Device Health
    battery_level DOUBLE PRECISION,
    signal_strength INTEGER,

    -- Data Quality
    data_quality VARCHAR(20) DEFAULT 'valid',

    CONSTRAINT fk_sensor FOREIGN KEY (sensor_id)
        REFERENCES iot_environmental_sensor(id) ON DELETE CASCADE,
    CONSTRAINT fk_zone FOREIGN KEY (zone_id)
        REFERENCES iot_cold_storage_zone(id) ON DELETE CASCADE
);

-- Convert to hypertable
SELECT create_hypertable(
    'iot_environmental_readings',
    'time',
    chunk_time_interval => INTERVAL '1 day',
    if_not_exists => TRUE
);

-- Create indexes
CREATE INDEX IF NOT EXISTS idx_env_readings_sensor_time
    ON iot_environmental_readings (sensor_id, time DESC);

CREATE INDEX IF NOT EXISTS idx_env_readings_zone_time
    ON iot_environmental_readings (zone_id, time DESC);

CREATE INDEX IF NOT EXISTS idx_env_readings_farm_time
    ON iot_environmental_readings (farm_id, time DESC);

-- Continuous aggregate for hourly averages
CREATE MATERIALIZED VIEW IF NOT EXISTS env_readings_hourly
WITH (timescaledb.continuous) AS
SELECT
    time_bucket('1 hour', time) AS bucket,
    sensor_id,
    zone_id,
    farm_id,
    AVG(temperature) AS avg_temperature,
    MIN(temperature) AS min_temperature,
    MAX(temperature) AS max_temperature,
    AVG(humidity) AS avg_humidity,
    AVG(co2) AS avg_co2,
    COUNT(*) AS reading_count
FROM iot_environmental_readings
GROUP BY bucket, sensor_id, zone_id, farm_id
WITH NO DATA;

-- Refresh policy for hourly aggregates
SELECT add_continuous_aggregate_policy('env_readings_hourly',
    start_offset => INTERVAL '3 hours',
    end_offset => INTERVAL '1 hour',
    schedule_interval => INTERVAL '1 hour',
    if_not_exists => TRUE
);

-- Continuous aggregate for daily averages
CREATE MATERIALIZED VIEW IF NOT EXISTS env_readings_daily
WITH (timescaledb.continuous) AS
SELECT
    time_bucket('1 day', time) AS bucket,
    sensor_id,
    zone_id,
    farm_id,
    AVG(temperature) AS avg_temperature,
    MIN(temperature) AS min_temperature,
    MAX(temperature) AS max_temperature,
    PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY temperature) AS median_temperature,
    AVG(humidity) AS avg_humidity,
    COUNT(*) AS reading_count,
    SUM(CASE WHEN temperature < 2 OR temperature > 6 THEN 1 ELSE 0 END) AS excursion_count
FROM iot_environmental_readings
GROUP BY bucket, sensor_id, zone_id, farm_id
WITH NO DATA;

-- Refresh policy for daily aggregates
SELECT add_continuous_aggregate_policy('env_readings_daily',
    start_offset => INTERVAL '3 days',
    end_offset => INTERVAL '1 day',
    schedule_interval => INTERVAL '1 day',
    if_not_exists => TRUE
);

-- Compression policy (compress data older than 7 days)
SELECT add_compression_policy('iot_environmental_readings',
    INTERVAL '7 days',
    if_not_exists => TRUE
);

ALTER TABLE iot_environmental_readings SET (
    timescaledb.compress,
    timescaledb.compress_segmentby = 'sensor_id, zone_id',
    timescaledb.compress_orderby = 'time DESC'
);

-- Retention policy (keep raw data for 90 days)
SELECT add_retention_policy('iot_environmental_readings',
    INTERVAL '90 days',
    if_not_exists => TRUE
);

-- Temperature excursion tracking table
CREATE TABLE IF NOT EXISTS iot_temperature_excursions (
    id SERIAL PRIMARY KEY,
    zone_id INTEGER NOT NULL,
    sensor_id INTEGER NOT NULL,
    start_time TIMESTAMPTZ NOT NULL,
    end_time TIMESTAMPTZ,
    duration_minutes INTEGER,
    max_deviation DOUBLE PRECISION,
    excursion_type VARCHAR(20), -- 'high', 'low'
    acknowledged BOOLEAN DEFAULT FALSE,
    resolved BOOLEAN DEFAULT FALSE,
    alert_id INTEGER,
    created_at TIMESTAMPTZ DEFAULT NOW(),

    CONSTRAINT fk_zone FOREIGN KEY (zone_id)
        REFERENCES iot_cold_storage_zone(id) ON DELETE CASCADE
);

CREATE INDEX idx_excursions_zone_time
    ON iot_temperature_excursions (zone_id, start_time DESC);
```

### 7.5 BSTI Compliance Report Service

```python
# smart_dairy_iot/services/bsti_compliance_service.py

from odoo import models, api, fields
from datetime import datetime, timedelta
import io
import xlsxwriter

class BSTIComplianceService(models.AbstractModel):
    _name = 'iot.bsti.compliance.service'
    _description = 'BSTI Temperature Compliance Service'

    # BSTI Temperature Requirements for Dairy
    BSTI_REQUIREMENTS = {
        'raw_milk_storage': {
            'max_temperature': 4.0,
            'min_temperature': 0.0,
            'description': 'Raw milk storage (BSTI BDS 1702:2015)',
        },
        'pasteurized_milk': {
            'max_temperature': 4.0,
            'min_temperature': 1.0,
            'description': 'Pasteurized milk storage',
        },
        'transport': {
            'max_temperature': 6.0,
            'min_temperature': 0.0,
            'description': 'Milk transport (BSTI BDS 1862:2014)',
        },
        'processing': {
            'max_temperature': 10.0,
            'min_temperature': 0.0,
            'description': 'Processing area',
        },
    }

    @api.model
    def generate_daily_compliance_report(self, farm_id, date=None):
        """Generate daily BSTI compliance report"""
        if not date:
            date = fields.Date.today() - timedelta(days=1)

        farm = self.env['res.partner'].browse(farm_id)
        zones = self.env['iot.cold_storage_zone'].search([
            ('farm_id', '=', farm_id)
        ])

        report_data = {
            'report_date': date,
            'farm_name': farm.name,
            'farm_id': farm_id,
            'generated_at': datetime.now(),
            'zones': [],
            'overall_compliance': True,
            'total_readings': 0,
            'compliant_readings': 0,
            'excursions': [],
        }

        for zone in zones:
            zone_data = self._analyze_zone_compliance(zone, date)
            report_data['zones'].append(zone_data)
            report_data['total_readings'] += zone_data['total_readings']
            report_data['compliant_readings'] += zone_data['compliant_readings']
            report_data['excursions'].extend(zone_data['excursions'])

            if not zone_data['is_compliant']:
                report_data['overall_compliance'] = False

        # Calculate compliance percentage
        if report_data['total_readings'] > 0:
            report_data['compliance_percentage'] = (
                report_data['compliant_readings'] / report_data['total_readings']
            ) * 100
        else:
            report_data['compliance_percentage'] = 0

        # Store report
        report = self.env['iot.bsti.compliance.report'].create({
            'name': f"BSTI-{farm.name}-{date}",
            'farm_id': farm_id,
            'report_date': date,
            'report_type': 'daily',
            'compliance_percentage': report_data['compliance_percentage'],
            'is_compliant': report_data['overall_compliance'],
            'total_readings': report_data['total_readings'],
            'excursion_count': len(report_data['excursions']),
            'report_data': str(report_data),
        })

        return report

    def _analyze_zone_compliance(self, zone, date):
        """Analyze compliance for a single zone"""
        # Get BSTI requirements for zone type
        requirements = self.BSTI_REQUIREMENTS.get(
            zone.zone_type,
            self.BSTI_REQUIREMENTS['raw_milk_storage']
        )

        # Query readings from TimescaleDB
        self.env.cr.execute("""
            SELECT
                COUNT(*) as total_readings,
                SUM(CASE
                    WHEN temperature >= %s AND temperature <= %s THEN 1
                    ELSE 0
                END) as compliant_readings,
                MIN(temperature) as min_temp,
                MAX(temperature) as max_temp,
                AVG(temperature) as avg_temp
            FROM iot_environmental_readings
            WHERE zone_id = %s
              AND time >= %s::date
              AND time < (%s::date + INTERVAL '1 day')
        """, (
            requirements['min_temperature'],
            requirements['max_temperature'],
            zone.id,
            date,
            date,
        ))

        result = self.env.cr.dictfetchone()

        # Get excursions
        excursions = self._get_excursions(zone, date, requirements)

        return {
            'zone_id': zone.id,
            'zone_name': zone.name,
            'zone_type': zone.zone_type,
            'requirements': requirements,
            'total_readings': result['total_readings'] or 0,
            'compliant_readings': result['compliant_readings'] or 0,
            'min_temperature': result['min_temp'],
            'max_temperature': result['max_temp'],
            'avg_temperature': result['avg_temp'],
            'is_compliant': (result['compliant_readings'] or 0) == (result['total_readings'] or 0),
            'excursions': excursions,
        }

    def _get_excursions(self, zone, date, requirements):
        """Get temperature excursions for the day"""
        self.env.cr.execute("""
            WITH excursion_periods AS (
                SELECT
                    time,
                    temperature,
                    CASE
                        WHEN temperature > %s THEN 'high'
                        WHEN temperature < %s THEN 'low'
                        ELSE 'normal'
                    END as excursion_type,
                    LAG(CASE
                        WHEN temperature > %s THEN 'high'
                        WHEN temperature < %s THEN 'low'
                        ELSE 'normal'
                    END) OVER (ORDER BY time) as prev_type
                FROM iot_environmental_readings
                WHERE zone_id = %s
                  AND time >= %s::date
                  AND time < (%s::date + INTERVAL '1 day')
            )
            SELECT
                MIN(time) as start_time,
                MAX(time) as end_time,
                excursion_type,
                MAX(ABS(temperature - %s)) as max_deviation,
                COUNT(*) as reading_count
            FROM excursion_periods
            WHERE excursion_type != 'normal'
            GROUP BY excursion_type,
                     (CASE WHEN excursion_type != prev_type THEN 1 ELSE 0 END)
            ORDER BY start_time
        """, (
            requirements['max_temperature'],
            requirements['min_temperature'],
            requirements['max_temperature'],
            requirements['min_temperature'],
            zone.id,
            date,
            date,
            (requirements['max_temperature'] + requirements['min_temperature']) / 2,
        ))

        return self.env.cr.dictfetchall()

    @api.model
    def generate_weekly_compliance_report(self, farm_id, week_start=None):
        """Generate weekly BSTI compliance summary"""
        if not week_start:
            today = fields.Date.today()
            week_start = today - timedelta(days=today.weekday() + 7)

        week_end = week_start + timedelta(days=6)

        # Aggregate daily reports
        daily_reports = self.env['iot.bsti.compliance.report'].search([
            ('farm_id', '=', farm_id),
            ('report_type', '=', 'daily'),
            ('report_date', '>=', week_start),
            ('report_date', '<=', week_end),
        ])

        weekly_data = {
            'week_start': week_start,
            'week_end': week_end,
            'daily_compliance': [],
            'avg_compliance': 0,
            'total_excursions': 0,
        }

        for report in daily_reports:
            weekly_data['daily_compliance'].append({
                'date': report.report_date,
                'compliance': report.compliance_percentage,
                'excursions': report.excursion_count,
            })
            weekly_data['total_excursions'] += report.excursion_count

        if daily_reports:
            weekly_data['avg_compliance'] = sum(
                r.compliance_percentage for r in daily_reports
            ) / len(daily_reports)

        return weekly_data

    @api.model
    def export_compliance_excel(self, farm_id, start_date, end_date):
        """Export compliance data to Excel"""
        output = io.BytesIO()
        workbook = xlsxwriter.Workbook(output)

        # Formats
        header_format = workbook.add_format({
            'bold': True,
            'bg_color': '#4472C4',
            'font_color': 'white',
            'border': 1
        })
        compliant_format = workbook.add_format({
            'bg_color': '#C6EFCE',
            'font_color': '#006100'
        })
        non_compliant_format = workbook.add_format({
            'bg_color': '#FFC7CE',
            'font_color': '#9C0006'
        })

        # Summary sheet
        summary_sheet = workbook.add_worksheet('Summary')
        summary_sheet.write(0, 0, 'BSTI Compliance Report', header_format)
        summary_sheet.write(1, 0, f'Period: {start_date} to {end_date}')

        # Daily data sheet
        daily_sheet = workbook.add_worksheet('Daily Readings')
        headers = ['Date', 'Zone', 'Avg Temp', 'Min Temp', 'Max Temp',
                   'Readings', 'Compliant', 'Status']

        for col, header in enumerate(headers):
            daily_sheet.write(0, col, header, header_format)

        # Get data
        reports = self.env['iot.bsti.compliance.report'].search([
            ('farm_id', '=', farm_id),
            ('report_date', '>=', start_date),
            ('report_date', '<=', end_date),
        ])

        row = 1
        for report in reports:
            report_data = eval(report.report_data)
            for zone in report_data.get('zones', []):
                daily_sheet.write(row, 0, str(report.report_date))
                daily_sheet.write(row, 1, zone['zone_name'])
                daily_sheet.write(row, 2, zone.get('avg_temperature', 0))
                daily_sheet.write(row, 3, zone.get('min_temperature', 0))
                daily_sheet.write(row, 4, zone.get('max_temperature', 0))
                daily_sheet.write(row, 5, zone['total_readings'])
                daily_sheet.write(row, 6, zone['compliant_readings'])

                status_format = compliant_format if zone['is_compliant'] else non_compliant_format
                daily_sheet.write(row, 7,
                    'COMPLIANT' if zone['is_compliant'] else 'NON-COMPLIANT',
                    status_format
                )
                row += 1

        workbook.close()
        output.seek(0)

        return output.read()
```

### 7.6 Environmental Dashboard (OWL)

```javascript
/** @odoo-module **/
// smart_dairy_iot/static/src/components/environmental_dashboard/environmental_dashboard.js

import { Component, useState, onWillStart, onMounted, onWillUnmount } from "@odoo/owl";
import { registry } from "@web/core/registry";
import { useService } from "@web/core/utils/hooks";
import { loadJS } from "@web/core/assets";

export class EnvironmentalDashboard extends Component {
    static template = "smart_dairy_iot.EnvironmentalDashboard";
    static props = {
        farmId: { type: Number, optional: true },
    };

    setup() {
        this.orm = useService("orm");
        this.notification = useService("notification");
        this.actionService = useService("action");

        this.state = useState({
            zones: [],
            selectedZone: null,
            alerts: [],
            isLoading: true,
            lastUpdate: null,
            viewMode: "grid", // grid, map, list
            timeRange: "24h",
            chartData: null,
        });

        this.websocket = null;
        this.refreshInterval = null;

        onWillStart(async () => {
            await loadJS("/smart_dairy_iot/static/lib/chart.js/chart.min.js");
            await this.loadZones();
            await this.loadAlerts();
        });

        onMounted(() => {
            this.connectWebSocket();
            this.startAutoRefresh();
            this.initializeCharts();
        });

        onWillUnmount(() => {
            this.disconnectWebSocket();
            this.stopAutoRefresh();
        });
    }

    async loadZones() {
        this.state.isLoading = true;
        try {
            const domain = this.props.farmId
                ? [["farm_id", "=", this.props.farmId]]
                : [];

            this.state.zones = await this.orm.searchRead(
                "iot.cold_storage_zone",
                domain,
                [
                    "name", "code", "zone_type", "current_temperature",
                    "current_humidity", "zone_status", "target_temperature",
                    "min_temperature", "max_temperature", "sensor_count",
                    "bsti_compliant"
                ]
            );
            this.state.lastUpdate = new Date();
        } catch (error) {
            this.notification.add("Failed to load zones", { type: "danger" });
        }
        this.state.isLoading = false;
    }

    async loadAlerts() {
        try {
            this.state.alerts = await this.orm.searchRead(
                "iot.temperature_alert",
                [["state", "in", ["active", "acknowledged"]]],
                ["name", "zone_id", "severity", "reading_value", "alert_time", "state"],
                { limit: 10, order: "create_date desc" }
            );
        } catch (error) {
            console.error("Failed to load alerts:", error);
        }
    }

    async loadZoneHistory(zoneId) {
        try {
            const result = await this.orm.call(
                "iot.cold_storage_zone",
                "get_temperature_history",
                [zoneId],
                { time_range: this.state.timeRange }
            );
            this.state.chartData = result;
            this.updateChart();
        } catch (error) {
            console.error("Failed to load zone history:", error);
        }
    }

    connectWebSocket() {
        const protocol = window.location.protocol === "https:" ? "wss:" : "ws:";
        const wsUrl = `${protocol}//${window.location.host}/ws/iot/environmental`;

        this.websocket = new WebSocket(wsUrl);

        this.websocket.onmessage = (event) => {
            const data = JSON.parse(event.data);
            this.handleRealtimeUpdate(data);
        };

        this.websocket.onerror = (error) => {
            console.error("WebSocket error:", error);
        };

        this.websocket.onclose = () => {
            // Reconnect after 5 seconds
            setTimeout(() => this.connectWebSocket(), 5000);
        };
    }

    disconnectWebSocket() {
        if (this.websocket) {
            this.websocket.close();
            this.websocket = null;
        }
    }

    handleRealtimeUpdate(data) {
        if (data.type === "temperature_update") {
            const zone = this.state.zones.find(z => z.id === data.zone_id);
            if (zone) {
                zone.current_temperature = data.temperature;
                zone.current_humidity = data.humidity;
                zone.zone_status = data.status;
                this.state.lastUpdate = new Date();
            }
        } else if (data.type === "new_alert") {
            this.state.alerts.unshift(data.alert);
            if (this.state.alerts.length > 10) {
                this.state.alerts.pop();
            }
            this.showAlertNotification(data.alert);
        }
    }

    showAlertNotification(alert) {
        const type = alert.severity === "emergency" ? "danger"
            : alert.severity === "critical" ? "warning"
            : "info";

        this.notification.add(
            `Temperature Alert: ${alert.zone_name} - ${alert.reading_value}°C`,
            {
                type,
                sticky: alert.severity === "emergency",
                buttons: [{
                    name: "View",
                    onClick: () => this.viewAlert(alert.id),
                }]
            }
        );
    }

    startAutoRefresh() {
        this.refreshInterval = setInterval(() => {
            this.loadZones();
            this.loadAlerts();
        }, 30000); // Refresh every 30 seconds
    }

    stopAutoRefresh() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
            this.refreshInterval = null;
        }
    }

    initializeCharts() {
        if (!this.state.selectedZone) return;

        const ctx = document.getElementById("temperatureChart");
        if (!ctx) return;

        this.chart = new Chart(ctx, {
            type: "line",
            data: {
                labels: [],
                datasets: [
                    {
                        label: "Temperature",
                        data: [],
                        borderColor: "#4472C4",
                        fill: false,
                        tension: 0.1,
                    },
                    {
                        label: "Max Threshold",
                        data: [],
                        borderColor: "#FF6384",
                        borderDash: [5, 5],
                        fill: false,
                    },
                    {
                        label: "Min Threshold",
                        data: [],
                        borderColor: "#36A2EB",
                        borderDash: [5, 5],
                        fill: false,
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: "Temperature (°C)"
                        }
                    }
                },
                plugins: {
                    annotation: {
                        annotations: {
                            targetLine: {
                                type: "line",
                                yMin: 4,
                                yMax: 4,
                                borderColor: "#28A745",
                                borderWidth: 2,
                                label: {
                                    content: "Target",
                                    enabled: true,
                                }
                            }
                        }
                    }
                }
            }
        });
    }

    updateChart() {
        if (!this.chart || !this.state.chartData) return;

        const data = this.state.chartData;
        this.chart.data.labels = data.timestamps;
        this.chart.data.datasets[0].data = data.temperatures;
        this.chart.data.datasets[1].data = data.timestamps.map(() => data.max_threshold);
        this.chart.data.datasets[2].data = data.timestamps.map(() => data.min_threshold);
        this.chart.update();
    }

    // UI Actions
    selectZone(zoneId) {
        this.state.selectedZone = this.state.zones.find(z => z.id === zoneId);
        this.loadZoneHistory(zoneId);
    }

    setViewMode(mode) {
        this.state.viewMode = mode;
    }

    setTimeRange(range) {
        this.state.timeRange = range;
        if (this.state.selectedZone) {
            this.loadZoneHistory(this.state.selectedZone.id);
        }
    }

    async acknowledgeAlert(alertId) {
        try {
            await this.orm.call("iot.temperature_alert", "action_acknowledge", [alertId]);
            await this.loadAlerts();
            this.notification.add("Alert acknowledged", { type: "success" });
        } catch (error) {
            this.notification.add("Failed to acknowledge alert", { type: "danger" });
        }
    }

    viewAlert(alertId) {
        this.actionService.doAction({
            type: "ir.actions.act_window",
            res_model: "iot.temperature_alert",
            res_id: alertId,
            views: [[false, "form"]],
            target: "current",
        });
    }

    openZoneConfig(zoneId) {
        this.actionService.doAction({
            type: "ir.actions.act_window",
            res_model: "iot.cold_storage_zone",
            res_id: zoneId,
            views: [[false, "form"]],
            target: "current",
        });
    }

    // Computed properties
    get criticalZones() {
        return this.state.zones.filter(z => z.zone_status === "critical");
    }

    get warningZones() {
        return this.state.zones.filter(z => z.zone_status === "warning");
    }

    get normalZones() {
        return this.state.zones.filter(z => z.zone_status === "normal");
    }

    get compliancePercentage() {
        if (!this.state.zones.length) return 0;
        const compliant = this.state.zones.filter(z => z.bsti_compliant).length;
        return Math.round((compliant / this.state.zones.length) * 100);
    }

    getStatusClass(status) {
        return {
            "critical": "bg-danger text-white",
            "warning": "bg-warning",
            "normal": "bg-success text-white",
            "offline": "bg-secondary text-white",
        }[status] || "bg-light";
    }

    getTemperatureClass(zone) {
        if (zone.current_temperature > zone.max_temperature) return "text-danger";
        if (zone.current_temperature < zone.min_temperature) return "text-primary";
        return "text-success";
    }

    formatTemperature(temp) {
        return temp ? `${temp.toFixed(1)}°C` : "N/A";
    }

    formatTime(datetime) {
        if (!datetime) return "N/A";
        return new Date(datetime).toLocaleTimeString();
    }
}

registry.category("actions").add("environmental_dashboard", EnvironmentalDashboard);
```

### 7.7 Dashboard XML Template

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- smart_dairy_iot/static/src/components/environmental_dashboard/environmental_dashboard.xml -->
<templates xml:space="preserve">
    <t t-name="smart_dairy_iot.EnvironmentalDashboard">
        <div class="environmental-dashboard h-100">
            <!-- Header -->
            <div class="dashboard-header d-flex justify-content-between align-items-center p-3 bg-white border-bottom">
                <div>
                    <h4 class="mb-0">Environmental Monitoring</h4>
                    <small class="text-muted">
                        Last updated: <t t-esc="state.lastUpdate ? formatTime(state.lastUpdate) : 'Loading...'"/>
                    </small>
                </div>

                <div class="d-flex gap-2">
                    <!-- View Mode Buttons -->
                    <div class="btn-group">
                        <button t-attf-class="btn btn-sm {{ state.viewMode === 'grid' ? 'btn-primary' : 'btn-outline-primary' }}"
                                t-on-click="() => setViewMode('grid')">
                            <i class="fa fa-th-large"/>
                        </button>
                        <button t-attf-class="btn btn-sm {{ state.viewMode === 'list' ? 'btn-primary' : 'btn-outline-primary' }}"
                                t-on-click="() => setViewMode('list')">
                            <i class="fa fa-list"/>
                        </button>
                        <button t-attf-class="btn btn-sm {{ state.viewMode === 'map' ? 'btn-primary' : 'btn-outline-primary' }}"
                                t-on-click="() => setViewMode('map')">
                            <i class="fa fa-map"/>
                        </button>
                    </div>

                    <!-- Time Range -->
                    <select class="form-select form-select-sm" style="width: auto;"
                            t-on-change="(ev) => setTimeRange(ev.target.value)">
                        <option value="1h">Last 1 Hour</option>
                        <option value="6h">Last 6 Hours</option>
                        <option value="24h" selected="1">Last 24 Hours</option>
                        <option value="7d">Last 7 Days</option>
                    </select>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row g-3 p-3">
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Normal</h6>
                            <h2 class="mb-0"><t t-esc="normalZones.length"/></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning">
                        <div class="card-body">
                            <h6 class="card-title">Warning</h6>
                            <h2 class="mb-0"><t t-esc="warningZones.length"/></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h6 class="card-title">Critical</h6>
                            <h2 class="mb-0"><t t-esc="criticalZones.length"/></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">BSTI Compliance</h6>
                            <h2 t-attf-class="mb-0 {{ compliancePercentage >= 95 ? 'text-success' : compliancePercentage >= 80 ? 'text-warning' : 'text-danger' }}">
                                <t t-esc="compliancePercentage"/>%
                            </h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 p-3">
                <!-- Zone Grid/List -->
                <div class="col-md-8">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">Cold Storage Zones</h5>
                        </div>
                        <div class="card-body">
                            <t t-if="state.isLoading">
                                <div class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </t>
                            <t t-else="">
                                <div t-if="state.viewMode === 'grid'" class="row g-3">
                                    <t t-foreach="state.zones" t-as="zone" t-key="zone.id">
                                        <div class="col-md-4">
                                            <div t-attf-class="card zone-card cursor-pointer {{ state.selectedZone?.id === zone.id ? 'border-primary' : '' }}"
                                                 t-on-click="() => selectZone(zone.id)">
                                                <div t-attf-class="card-header {{ getStatusClass(zone.zone_status) }}">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span><t t-esc="zone.name"/></span>
                                                        <span class="badge bg-white text-dark">
                                                            <t t-esc="zone.sensor_count"/> sensors
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="card-body text-center">
                                                    <h1 t-attf-class="{{ getTemperatureClass(zone) }}">
                                                        <t t-esc="formatTemperature(zone.current_temperature)"/>
                                                    </h1>
                                                    <div class="text-muted small">
                                                        Target: <t t-esc="formatTemperature(zone.target_temperature)"/>
                                                    </div>
                                                    <div class="progress mt-2" style="height: 5px;">
                                                        <div t-attf-class="progress-bar {{ zone.bsti_compliant ? 'bg-success' : 'bg-danger' }}"
                                                             style="width: 100%"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </t>
                                </div>

                                <table t-if="state.viewMode === 'list'" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Zone</th>
                                            <th>Type</th>
                                            <th>Temperature</th>
                                            <th>Humidity</th>
                                            <th>Status</th>
                                            <th>BSTI</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <t t-foreach="state.zones" t-as="zone" t-key="zone.id">
                                            <tr t-attf-class="{{ state.selectedZone?.id === zone.id ? 'table-primary' : '' }}"
                                                t-on-click="() => selectZone(zone.id)"
                                                style="cursor: pointer;">
                                                <td><strong><t t-esc="zone.name"/></strong></td>
                                                <td><t t-esc="zone.zone_type"/></td>
                                                <td t-attf-class="{{ getTemperatureClass(zone) }}">
                                                    <t t-esc="formatTemperature(zone.current_temperature)"/>
                                                </td>
                                                <td><t t-esc="zone.current_humidity ? zone.current_humidity.toFixed(1) + '%' : 'N/A'"/></td>
                                                <td>
                                                    <span t-attf-class="badge {{ getStatusClass(zone.zone_status) }}">
                                                        <t t-esc="zone.zone_status"/>
                                                    </span>
                                                </td>
                                                <td>
                                                    <i t-attf-class="fa {{ zone.bsti_compliant ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }}"/>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary"
                                                            t-on-click.stop="() => openZoneConfig(zone.id)">
                                                        <i class="fa fa-cog"/>
                                                    </button>
                                                </td>
                                            </tr>
                                        </t>
                                    </tbody>
                                </table>
                            </t>
                        </div>
                    </div>
                </div>

                <!-- Alerts Panel -->
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Active Alerts</h5>
                            <span class="badge bg-danger" t-if="state.alerts.length">
                                <t t-esc="state.alerts.length"/>
                            </span>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <t t-foreach="state.alerts" t-as="alert" t-key="alert.id">
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <span t-attf-class="badge {{ alert.severity === 'emergency' ? 'bg-danger' : alert.severity === 'critical' ? 'bg-warning' : 'bg-info' }} me-2">
                                                    <t t-esc="alert.severity"/>
                                                </span>
                                                <strong><t t-esc="alert.zone_id[1]"/></strong>
                                                <div class="text-muted small">
                                                    <t t-esc="alert.reading_value"/>°C at <t t-esc="formatTime(alert.alert_time)"/>
                                                </div>
                                            </div>
                                            <button t-if="alert.state === 'active'"
                                                    class="btn btn-sm btn-outline-success"
                                                    t-on-click="() => acknowledgeAlert(alert.id)">
                                                ACK
                                            </button>
                                        </div>
                                    </div>
                                </t>
                                <div t-if="!state.alerts.length" class="list-group-item text-center text-muted py-4">
                                    <i class="fa fa-check-circle fa-2x text-success mb-2"/>
                                    <div>No active alerts</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Temperature Chart -->
            <div class="row g-3 p-3" t-if="state.selectedZone">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                Temperature History - <t t-esc="state.selectedZone.name"/>
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="temperatureChart" height="100"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </t>
</templates>
```

### 7.8 Flutter Mobile Alert Screen

```dart
// lib/screens/iot/temperature_alerts_screen.dart

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:smart_dairy_mobile/providers/iot_provider.dart';
import 'package:smart_dairy_mobile/models/temperature_alert.dart';
import 'package:smart_dairy_mobile/services/notification_service.dart';
import 'package:timeago/timeago.dart' as timeago;

class TemperatureAlertsScreen extends StatefulWidget {
  const TemperatureAlertsScreen({Key? key}) : super(key: key);

  @override
  State<TemperatureAlertsScreen> createState() => _TemperatureAlertsScreenState();
}

class _TemperatureAlertsScreenState extends State<TemperatureAlertsScreen> {
  String _filterSeverity = 'all';
  String _filterState = 'active';

  @override
  void initState() {
    super.initState();
    _loadAlerts();
    NotificationService.instance.setupFirebaseMessaging(_handlePushNotification);
  }

  void _handlePushNotification(Map<String, dynamic> data) {
    if (data['alert_type'] == 'temperature') {
      _loadAlerts();
      _showAlertSnackbar(data);
    }
  }

  void _showAlertSnackbar(Map<String, dynamic> data) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(
          '🚨 ${data['zone']}: ${data['value']}°C',
        ),
        backgroundColor: _getSeverityColor(data['severity']),
        action: SnackBarAction(
          label: 'VIEW',
          textColor: Colors.white,
          onPressed: () => _navigateToAlertDetail(int.parse(data['alert_id'])),
        ),
        duration: const Duration(seconds: 10),
      ),
    );
  }

  Future<void> _loadAlerts() async {
    await context.read<IoTProvider>().loadTemperatureAlerts(
      severity: _filterSeverity != 'all' ? _filterSeverity : null,
      state: _filterState,
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Temperature Alerts'),
        actions: [
          IconButton(
            icon: const Icon(Icons.filter_list),
            onPressed: _showFilterDialog,
          ),
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: _loadAlerts,
          ),
        ],
      ),
      body: Consumer<IoTProvider>(
        builder: (context, provider, child) {
          if (provider.isLoading) {
            return const Center(child: CircularProgressIndicator());
          }

          final alerts = provider.temperatureAlerts;

          if (alerts.isEmpty) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(
                    Icons.check_circle_outline,
                    size: 64,
                    color: Colors.green[300],
                  ),
                  const SizedBox(height: 16),
                  Text(
                    'No active alerts',
                    style: Theme.of(context).textTheme.titleLarge,
                  ),
                  const SizedBox(height: 8),
                  Text(
                    'All temperature readings are within normal range',
                    style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                      color: Colors.grey,
                    ),
                  ),
                ],
              ),
            );
          }

          return RefreshIndicator(
            onRefresh: _loadAlerts,
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: alerts.length,
              itemBuilder: (context, index) {
                return _buildAlertCard(alerts[index]);
              },
            ),
          );
        },
      ),
    );
  }

  Widget _buildAlertCard(TemperatureAlert alert) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      elevation: alert.severity == 'emergency' ? 4 : 2,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
        side: BorderSide(
          color: _getSeverityColor(alert.severity),
          width: alert.state == 'active' ? 2 : 0,
        ),
      ),
      child: InkWell(
        onTap: () => _navigateToAlertDetail(alert.id),
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  _buildSeverityBadge(alert.severity),
                  const SizedBox(width: 8),
                  Expanded(
                    child: Text(
                      alert.zoneName,
                      style: const TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 16,
                      ),
                    ),
                  ),
                  _buildStateBadge(alert.state),
                ],
              ),
              const SizedBox(height: 12),
              Row(
                children: [
                  _buildTemperatureDisplay(alert),
                  const SizedBox(width: 24),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          alert.violationType == 'high'
                              ? 'Temperature Too High'
                              : 'Temperature Too Low',
                          style: TextStyle(
                            color: Colors.grey[700],
                            fontWeight: FontWeight.w500,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          'Threshold: ${alert.thresholdValue?.toStringAsFixed(1)}°C',
                          style: TextStyle(
                            color: Colors.grey[600],
                            fontSize: 13,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          timeago.format(alert.alertTime),
                          style: TextStyle(
                            color: Colors.grey[500],
                            fontSize: 12,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
              if (alert.state == 'active') ...[
                const Divider(height: 24),
                Row(
                  mainAxisAlignment: MainAxisAlignment.end,
                  children: [
                    OutlinedButton(
                      onPressed: () => _acknowledgeAlert(alert),
                      child: const Text('ACKNOWLEDGE'),
                    ),
                    const SizedBox(width: 12),
                    ElevatedButton(
                      onPressed: () => _resolveAlert(alert),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.green,
                      ),
                      child: const Text('RESOLVE'),
                    ),
                  ],
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildTemperatureDisplay(TemperatureAlert alert) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: _getSeverityColor(alert.severity).withOpacity(0.1),
        borderRadius: BorderRadius.circular(12),
      ),
      child: Column(
        children: [
          Text(
            '${alert.readingValue.toStringAsFixed(1)}°',
            style: TextStyle(
              fontSize: 32,
              fontWeight: FontWeight.bold,
              color: _getSeverityColor(alert.severity),
            ),
          ),
          const Text(
            'C',
            style: TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.w500,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSeverityBadge(String severity) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      decoration: BoxDecoration(
        color: _getSeverityColor(severity),
        borderRadius: BorderRadius.circular(4),
      ),
      child: Text(
        severity.toUpperCase(),
        style: const TextStyle(
          color: Colors.white,
          fontSize: 11,
          fontWeight: FontWeight.bold,
        ),
      ),
    );
  }

  Widget _buildStateBadge(String state) {
    Color color;
    IconData icon;

    switch (state) {
      case 'active':
        color = Colors.red;
        icon = Icons.warning;
        break;
      case 'acknowledged':
        color = Colors.orange;
        icon = Icons.check;
        break;
      case 'resolved':
        color = Colors.green;
        icon = Icons.check_circle;
        break;
      default:
        color = Colors.grey;
        icon = Icons.info;
    }

    return Icon(icon, color: color, size: 24);
  }

  Color _getSeverityColor(String severity) {
    switch (severity) {
      case 'emergency':
        return Colors.red[700]!;
      case 'critical':
        return Colors.orange[700]!;
      case 'warning':
        return Colors.amber[700]!;
      default:
        return Colors.blue;
    }
  }

  void _showFilterDialog() {
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
      ),
      builder: (context) => Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Filter Alerts',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 16),
            const Text('Severity'),
            Wrap(
              spacing: 8,
              children: [
                _buildFilterChip('All', 'all', _filterSeverity, (v) {
                  setState(() => _filterSeverity = v);
                }),
                _buildFilterChip('Emergency', 'emergency', _filterSeverity, (v) {
                  setState(() => _filterSeverity = v);
                }),
                _buildFilterChip('Critical', 'critical', _filterSeverity, (v) {
                  setState(() => _filterSeverity = v);
                }),
                _buildFilterChip('Warning', 'warning', _filterSeverity, (v) {
                  setState(() => _filterSeverity = v);
                }),
              ],
            ),
            const SizedBox(height: 16),
            const Text('State'),
            Wrap(
              spacing: 8,
              children: [
                _buildFilterChip('Active', 'active', _filterState, (v) {
                  setState(() => _filterState = v);
                }),
                _buildFilterChip('Acknowledged', 'acknowledged', _filterState, (v) {
                  setState(() => _filterState = v);
                }),
                _buildFilterChip('Resolved', 'resolved', _filterState, (v) {
                  setState(() => _filterState = v);
                }),
              ],
            ),
            const SizedBox(height: 24),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () {
                  Navigator.pop(context);
                  _loadAlerts();
                },
                child: const Text('Apply Filters'),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildFilterChip(
    String label,
    String value,
    String current,
    Function(String) onSelect,
  ) {
    final isSelected = current == value;
    return FilterChip(
      label: Text(label),
      selected: isSelected,
      onSelected: (_) => onSelect(value),
    );
  }

  Future<void> _acknowledgeAlert(TemperatureAlert alert) async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Acknowledge Alert'),
        content: Text(
          'Acknowledge the temperature alert for ${alert.zoneName}?',
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('CANCEL'),
          ),
          ElevatedButton(
            onPressed: () => Navigator.pop(context, true),
            child: const Text('ACKNOWLEDGE'),
          ),
        ],
      ),
    );

    if (confirmed == true) {
      await context.read<IoTProvider>().acknowledgeAlert(alert.id);
      _loadAlerts();
    }
  }

  Future<void> _resolveAlert(TemperatureAlert alert) async {
    Navigator.pushNamed(
      context,
      '/alerts/resolve',
      arguments: alert,
    ).then((_) => _loadAlerts());
  }

  void _navigateToAlertDetail(int alertId) {
    Navigator.pushNamed(
      context,
      '/alerts/detail',
      arguments: alertId,
    );
  }
}
```

---

## 8. Testing & Validation

### 8.1 Unit Tests

```python
# smart_dairy_iot/tests/test_temperature_monitoring.py

from odoo.tests.common import TransactionCase
from odoo.exceptions import ValidationError
from datetime import datetime, timedelta
import json

class TestEnvironmentalSensor(TransactionCase):

    def setUp(self):
        super().setUp()
        self.farm = self.env['res.partner'].create({
            'name': 'Test Farm',
            'is_farm': True,
        })
        self.zone = self.env['iot.cold_storage_zone'].create({
            'name': 'Test Cold Room',
            'code': 'TCR01',
            'zone_type': 'cold_room',
            'farm_id': self.farm.id,
            'target_temperature': 4.0,
            'min_temperature': 2.0,
            'max_temperature': 6.0,
        })
        self.sensor = self.env['iot.environmental_sensor'].create({
            'name': 'Test Sensor',
            'device_uid': 'TEMP001',
            'sensor_type': 'temperature',
            'zone_id': self.zone.id,
        })

    def test_sensor_creation(self):
        """Test environmental sensor is created correctly"""
        self.assertEqual(self.sensor.sensor_type, 'temperature')
        self.assertEqual(self.sensor.zone_id, self.zone)
        self.assertEqual(self.sensor.alert_status, 'normal')

    def test_measurement_interval_validation(self):
        """Test measurement interval minimum validation"""
        with self.assertRaises(ValidationError):
            self.sensor.measurement_interval = 5  # Less than 10

    def test_process_mqtt_reading_normal(self):
        """Test processing normal temperature reading"""
        payload = {
            'temperature': 4.0,
            'humidity': 85.0,
            'battery': 95,
        }
        self.sensor.process_mqtt_reading(payload)

        self.assertEqual(self.sensor.current_temperature, 4.0)
        self.assertEqual(self.sensor.current_humidity, 85.0)
        self.assertEqual(self.sensor.alert_status, 'normal')

    def test_process_mqtt_reading_high_temp(self):
        """Test processing high temperature triggers alert"""
        # Create warning threshold
        self.env['iot.temperature_threshold'].create({
            'zone_id': self.zone.id,
            'name': 'Warning High',
            'severity': 'warning',
            'max_temperature': 6.0,
        })

        payload = {'temperature': 8.0}
        self.sensor.process_mqtt_reading(payload)

        self.assertEqual(self.sensor.current_temperature, 8.0)
        self.assertEqual(self.sensor.alert_status, 'warning')

    def test_calibration_offset_applied(self):
        """Test calibration offset is applied to readings"""
        self.sensor.calibration_offset = -0.5

        payload = {'temperature': 4.5}
        self.sensor.process_mqtt_reading(payload)

        self.assertEqual(self.sensor.current_temperature, 4.0)


class TestColdStorageZone(TransactionCase):

    def setUp(self):
        super().setUp()
        self.farm = self.env['res.partner'].create({
            'name': 'Test Farm',
            'is_farm': True,
        })

    def test_zone_hierarchy(self):
        """Test zone parent-child hierarchy"""
        parent_zone = self.env['iot.cold_storage_zone'].create({
            'name': 'Main Cold Storage',
            'code': 'MCS',
            'zone_type': 'cold_room',
            'farm_id': self.farm.id,
        })
        child_zone = self.env['iot.cold_storage_zone'].create({
            'name': 'Milk Section',
            'code': 'MCS-MILK',
            'zone_type': 'cold_room',
            'farm_id': self.farm.id,
            'parent_id': parent_zone.id,
        })

        self.assertEqual(child_zone.parent_id, parent_zone)
        self.assertIn(child_zone, parent_zone.child_ids)

    def test_zone_status_computed(self):
        """Test zone status is computed from sensors"""
        zone = self.env['iot.cold_storage_zone'].create({
            'name': 'Test Zone',
            'code': 'TZ01',
            'zone_type': 'cold_room',
            'farm_id': self.farm.id,
            'min_temperature': 2.0,
            'max_temperature': 6.0,
        })

        # Create sensors with different statuses
        sensor1 = self.env['iot.environmental_sensor'].create({
            'name': 'Sensor 1',
            'device_uid': 'S001',
            'sensor_type': 'temperature',
            'zone_id': zone.id,
            'device_status': 'online',
            'current_temperature': 4.0,
            'alert_status': 'normal',
        })
        sensor2 = self.env['iot.environmental_sensor'].create({
            'name': 'Sensor 2',
            'device_uid': 'S002',
            'sensor_type': 'temperature',
            'zone_id': zone.id,
            'device_status': 'online',
            'current_temperature': 8.0,
            'alert_status': 'warning',
        })

        zone._compute_current_status()

        self.assertEqual(zone.zone_status, 'warning')
        self.assertEqual(zone.current_temperature, 6.0)  # Average


class TestTemperatureAlert(TransactionCase):

    def setUp(self):
        super().setUp()
        self.farm = self.env['res.partner'].create({
            'name': 'Test Farm',
            'is_farm': True,
        })
        self.zone = self.env['iot.cold_storage_zone'].create({
            'name': 'Test Zone',
            'code': 'TZ01',
            'zone_type': 'cold_room',
            'farm_id': self.farm.id,
        })
        self.sensor = self.env['iot.environmental_sensor'].create({
            'name': 'Test Sensor',
            'device_uid': 'TS01',
            'sensor_type': 'temperature',
            'zone_id': self.zone.id,
        })

    def test_alert_creation(self):
        """Test alert is created with sequence"""
        alert = self.env['iot.temperature_alert'].create({
            'sensor_id': self.sensor.id,
            'severity': 'warning',
            'violation_type': 'high',
            'reading_value': 8.0,
            'threshold_value': 6.0,
        })

        self.assertTrue(alert.name.startswith('ALERT'))
        self.assertEqual(alert.state, 'active')

    def test_alert_acknowledge(self):
        """Test alert acknowledgment"""
        alert = self.env['iot.temperature_alert'].create({
            'sensor_id': self.sensor.id,
            'severity': 'critical',
            'violation_type': 'high',
            'reading_value': 10.0,
        })

        alert.action_acknowledge()

        self.assertEqual(alert.state, 'acknowledged')
        self.assertTrue(alert.acknowledged_time)
        self.assertEqual(alert.acknowledged_by, self.env.user)

    def test_deviation_computed(self):
        """Test deviation is computed correctly"""
        alert = self.env['iot.temperature_alert'].create({
            'sensor_id': self.sensor.id,
            'severity': 'warning',
            'violation_type': 'high',
            'reading_value': 8.5,
            'threshold_value': 6.0,
        })

        self.assertEqual(alert.deviation, 2.5)
```

### 8.2 Integration Tests

```python
# smart_dairy_iot/tests/test_cold_chain_integration.py

from odoo.tests.common import HttpCase
from datetime import datetime, timedelta
import json

class TestColdChainIntegration(HttpCase):

    def test_mqtt_to_alert_flow(self):
        """Test complete flow from MQTT message to alert generation"""
        # Setup
        farm = self.env['res.partner'].create({
            'name': 'Integration Test Farm',
            'is_farm': True,
        })
        zone = self.env['iot.cold_storage_zone'].create({
            'name': 'Test Cold Room',
            'code': 'ICR01',
            'zone_type': 'cold_room',
            'farm_id': farm.id,
            'max_temperature': 6.0,
        })
        sensor = self.env['iot.environmental_sensor'].create({
            'name': 'Integration Sensor',
            'device_uid': 'INT001',
            'sensor_type': 'temperature',
            'zone_id': zone.id,
        })
        threshold = self.env['iot.temperature_threshold'].create({
            'zone_id': zone.id,
            'name': 'Critical High',
            'severity': 'critical',
            'max_temperature': 6.0,
        })

        # Simulate MQTT message processing
        mqtt_payload = {
            'temperature': 9.5,
            'humidity': 88.0,
            'timestamp': datetime.now().isoformat(),
        }

        sensor.process_mqtt_reading(mqtt_payload)

        # Verify alert was created
        alerts = self.env['iot.temperature_alert'].search([
            ('sensor_id', '=', sensor.id),
            ('state', '=', 'active'),
        ])

        self.assertEqual(len(alerts), 1)
        self.assertEqual(alerts[0].severity, 'critical')
        self.assertEqual(alerts[0].reading_value, 9.5)

    def test_bsti_compliance_report_generation(self):
        """Test BSTI compliance report generation"""
        # Setup zone with readings
        farm = self.env['res.partner'].create({
            'name': 'BSTI Test Farm',
            'is_farm': True,
        })
        zone = self.env['iot.cold_storage_zone'].create({
            'name': 'BSTI Test Zone',
            'code': 'BTZ01',
            'zone_type': 'raw_milk_storage',
            'farm_id': farm.id,
        })
        sensor = self.env['iot.environmental_sensor'].create({
            'name': 'BSTI Sensor',
            'device_uid': 'BSTI01',
            'sensor_type': 'temperature',
            'zone_id': zone.id,
        })

        # Insert test readings directly
        self.env.cr.execute("""
            INSERT INTO iot_environmental_readings
            (time, sensor_id, device_uid, zone_id, farm_id, temperature)
            VALUES
            (NOW() - INTERVAL '1 hour', %s, %s, %s, %s, 3.5),
            (NOW() - INTERVAL '2 hours', %s, %s, %s, %s, 4.0),
            (NOW() - INTERVAL '3 hours', %s, %s, %s, %s, 4.5)
        """, (
            sensor.id, sensor.device_uid, zone.id, farm.id,
            sensor.id, sensor.device_uid, zone.id, farm.id,
            sensor.id, sensor.device_uid, zone.id, farm.id,
        ))

        # Generate report
        service = self.env['iot.bsti.compliance.service']
        report = service.generate_daily_compliance_report(farm.id)

        self.assertTrue(report.is_compliant)
        self.assertEqual(report.total_readings, 3)
```

### 8.3 Performance Tests

| Test Case | Target | Method |
|-----------|--------|--------|
| Reading insert rate | 1000/second | Load test with concurrent inserts |
| Query response time | <100ms | Test dashboard queries |
| Alert generation | <1 second | Measure threshold check to alert |
| Report generation | <10 seconds | Daily report with 10K readings |
| WebSocket latency | <500ms | Real-time update propagation |

---

## 9. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Sensor communication failure | Medium | High | Implement offline buffering, redundant sensors |
| TimescaleDB performance issues | Low | High | Pre-optimize queries, use continuous aggregates |
| False positive alerts | Medium | Medium | Implement debounce logic, sensor validation |
| MQTT message loss | Low | High | Use QoS 1/2, implement acknowledgments |
| Mobile notification delays | Medium | Medium | Use high-priority FCM, implement SMS fallback |

---

## 10. Dependencies & Handoffs

### 10.1 Incoming Dependencies
| Dependency | Source | Required By |
|------------|--------|-------------|
| MQTT broker | Milestone 91 | Day 683 |
| Device registry | Milestone 92 | Day 681 |
| TimescaleDB setup | Milestone 91 | Day 681 |
| FCM configuration | Phase 7 | Day 688 |

### 10.2 Outgoing Handoffs
| Deliverable | Receiving Milestone | Handoff Date |
|-------------|---------------------|--------------|
| Environmental sensor model | M95 (Activity Collars) | Day 690 |
| Alert engine | M98 (Real-time Alerts) | Day 690 |
| Temperature hypertable | M97 (Data Pipeline) | Day 690 |
| Compliance framework | M100 (Testing) | Day 690 |

---

## 11. Quality Assurance

### 11.1 Test Coverage Targets
| Component | Target Coverage |
|-----------|----------------|
| Environmental sensor model | 85% |
| Alert generation | 90% |
| BSTI compliance service | 85% |
| API endpoints | 80% |
| OWL components | 70% |

### 11.2 Acceptance Criteria
- [ ] Temperature readings stored with <10ms latency
- [ ] Alerts generated within 30 seconds of threshold breach
- [ ] BSTI compliance reports generate correctly
- [ ] Dashboard displays real-time data with 5-second refresh
- [ ] Mobile push notifications delivered within 1 minute
- [ ] All zones display correct status indicators
- [ ] Historical data queryable for 90+ days
- [ ] Unit test coverage >80%

---

## 12. Communication

### 12.1 Daily Standups
- Time: 09:00 AM BST
- Duration: 15 minutes
- Format: Blocker-focused, async updates via Slack

### 12.2 Milestone Demo
- Date: Day 690
- Attendees: Development team, Farm Operations Manager
- Demo scenarios:
  1. Real-time temperature monitoring
  2. Alert generation and escalation
  3. BSTI compliance report
  4. Mobile alert workflow

---

## 13. Milestone Sign-off Checklist

| # | Criteria | Owner | Status |
|---|----------|-------|--------|
| 1 | Environmental sensor model complete | Dev 1 | ☐ |
| 2 | TimescaleDB hypertable functional | Dev 2 | ☐ |
| 3 | Zone management working | Dev 1 | ☐ |
| 4 | Alert thresholds configurable | Dev 1 | ☐ |
| 5 | Escalation workflow implemented | Dev 2 | ☐ |
| 6 | BSTI compliance reports generating | Dev 2 | ☐ |
| 7 | HACCP CCP integration complete | Dev 1 | ☐ |
| 8 | Environmental dashboard deployed | Dev 3 | ☐ |
| 9 | Mobile alerts functional | Dev 3 | ☐ |
| 10 | Unit tests passing (>80%) | All | ☐ |
| 11 | Integration tests passing | Dev 2 | ☐ |
| 12 | Documentation complete | All | ☐ |
| 13 | Code review approved | Tech Lead | ☐ |
| 14 | Demo completed successfully | All | ☐ |
| 15 | Stakeholder sign-off obtained | PM | ☐ |

---

## 14. Appendices

### Appendix A: BSTI Standards Reference
- BDS 1702:2015 - Pasteurized Milk
- BDS 1862:2014 - Milk Transport
- BSTI Cold Chain Guidelines 2023

### Appendix B: HACCP Critical Control Points for Dairy
| CCP | Hazard | Critical Limit |
|-----|--------|----------------|
| CCP1 | Milk Reception | <10°C |
| CCP2 | Pasteurization | 72°C for 15 sec |
| CCP3 | Cooling | <4°C within 2 hours |
| CCP4 | Cold Storage | 2-4°C continuous |
| CCP5 | Transport | <6°C throughout |

### Appendix C: Sensor Communication Protocols
| Protocol | Use Case | Range | Power |
|----------|----------|-------|-------|
| MQTT/WiFi | Indoor sensors | Building-wide | Mains |
| LoRaWAN | Remote locations | 5-15 km | Battery |
| Modbus TCP | Industrial chillers | LAN | Mains |
| ZigBee | Mesh network | 100m | Battery |

---

**Document Revision History**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-04 | Development Team | Initial creation |

---

*This document is part of the Smart Dairy Digital Smart Portal + ERP System implementation roadmap.*
