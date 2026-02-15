# Milestone 95: Activity Collar Integration

## Smart Dairy Digital Smart Portal + ERP System (Odoo 19 CE)

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P10-M95-v1.0 |
| **Milestone** | 95 of 100 |
| **Phase** | Phase 10: Commerce - IoT Integration Core |
| **Days** | 691-700 (10 working days) |
| **Duration** | 2 weeks |
| **Developers** | 3 Full Stack Developers |
| **Total Effort** | 240 person-hours |
| **Status** | Planned |
| **Version** | 1.0 |
| **Last Updated** | 2026-02-04 |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 95 implements activity collar integration for comprehensive animal health and behavior monitoring. Activity collars track movement, rumination, feeding patterns, and rest time to enable early detection of health issues, accurate heat detection, and overall herd welfare optimization.

### 1.2 Scope

This milestone covers:
- Activity collar device model and LoRaWAN integration
- Activity level processing and analysis
- Heat detection algorithm implementation
- Rumination pattern analysis
- Health score calculation system
- Breeding calendar integration
- Veterinary alert system
- Animal activity dashboards

### 1.3 Key Outcomes

| Outcome | Success Measure |
|---------|-----------------|
| Collar Integration | Support for major collar brands (Allflex, Cowlar, Moocall) |
| Heat Detection | >90% accuracy, <6 hour detection window |
| Health Alerts | Early warning 12-24 hours before clinical symptoms |
| Rumination Analysis | Real-time tracking with 15-minute granularity |
| Data Collection | Continuous activity data with 5-minute aggregates |
| Mobile Access | Real-time animal status on mobile app |

---

## 2. Objectives

| # | Objective | Priority | Measurable Target |
|---|-----------|----------|-------------------|
| O1 | Implement activity collar device model with multi-vendor support | Critical | Support 3+ collar brands |
| O2 | Create LoRaWAN integration with ChirpStack gateway | Critical | <30 second message delivery |
| O3 | Develop activity level processing pipeline | High | Process 10K+ data points/day |
| O4 | Implement heat detection algorithm | Critical | >90% detection accuracy |
| O5 | Build rumination analysis system | High | 15-minute interval tracking |
| O6 | Create health score calculation | High | Multi-factor health assessment |
| O7 | Integrate with breeding calendar | Medium | Auto-flag optimal breeding windows |
| O8 | Develop veterinary alert system | High | Priority-based alert routing |

---

## 3. Key Deliverables

| # | Deliverable | Description | Owner | Priority |
|---|-------------|-------------|-------|----------|
| D1 | Activity Collar Model | `iot.activity_collar` Odoo model | Dev 1 | Critical |
| D2 | LoRaWAN Integration | ChirpStack gateway connectivity | Dev 2 | Critical |
| D3 | Activity Processing Service | Raw data to activity metrics | Dev 1 | High |
| D4 | Heat Detection Engine | Algorithm for estrus detection | Dev 1 | Critical |
| D5 | Rumination Analyzer | Rumination pattern tracking | Dev 1 | High |
| D6 | Health Score Calculator | Multi-factor health assessment | Dev 2 | High |
| D7 | Breeding Calendar Link | Integration with reproduction module | Dev 2 | Medium |
| D8 | Veterinary Alert Model | Priority-based vet notifications | Dev 1 | High |
| D9 | Activity Dashboard | Real-time animal activity view | Dev 3 | High |
| D10 | Heat Calendar View | Visual heat cycle tracking | Dev 3 | Medium |
| D11 | Mobile Animal Status | Flutter animal monitoring | Dev 3 | High |
| D12 | Activity Reports | PDF/Excel activity reports | Dev 2 | Medium |

---

## 4. Prerequisites

### 4.1 From Previous Phases
| Prerequisite | Phase/Milestone | Status |
|--------------|-----------------|--------|
| Animal registry (farm.animal) | Phase 5 | Complete |
| Reproduction management | Phase 5 | Complete |
| Notification infrastructure | Phase 6 | Complete |
| Mobile app framework | Phase 7 | Complete |

### 4.2 From Phase 10
| Prerequisite | Milestone | Status |
|--------------|-----------|--------|
| MQTT infrastructure | M91 | Complete |
| Device registry | M92 | Complete |
| IoT device base model | M92 | Complete |
| Environmental sensor patterns | M94 | Complete |

---

## 5. Requirement Traceability

### 5.1 RFP Requirements
| RFP ID | Requirement | Implementation |
|--------|-------------|----------------|
| IOT-005 | Animal activity monitoring | Full implementation |
| IOT-005.1 | Activity collar integration | Multi-vendor support |
| IOT-005.2 | Heat detection | Algorithm-based detection |
| IOT-005.3 | Health monitoring | Health score system |
| IOT-005.4 | Breeding optimization | Calendar integration |

### 5.2 BRD Requirements
| BRD ID | Business Requirement | Implementation |
|--------|---------------------|----------------|
| FR-IOT-003 | Animal health early warning | Health score with alerts |
| FR-IOT-003.1 | Estrus detection | Heat detection algorithm |
| FR-IOT-003.2 | Activity anomaly detection | Baseline comparison |
| FR-IOT-003.3 | Veterinary integration | Alert routing system |

### 5.3 SRS Technical Requirements
| SRS ID | Technical Requirement | Implementation |
|--------|----------------------|----------------|
| SRS-IOT-010 | Support 5000+ collars | Scalable LoRaWAN architecture |
| SRS-IOT-011 | Activity data retention 2+ years | TimescaleDB storage |
| SRS-IOT-012 | Heat detection within 6 hours | Real-time algorithm |
| SRS-IOT-013 | >90% health alert accuracy | Multi-factor validation |

---

## 6. Day-by-Day Development Plan

### Day 691: Activity Collar Model & LoRaWAN Setup

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-2 | Design activity collar model schema | UML diagram, specifications |
| 2-5 | Implement `iot.activity_collar` model | Python model file |
| 5-7 | Create collar configuration options | Vendor-specific settings |
| 7-8 | Write model unit tests | pytest test file |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Setup ChirpStack LoRaWAN gateway | Gateway configuration |
| 3-6 | Implement LoRaWAN decoder functions | Payload decoders |
| 6-8 | Create collar-to-animal mapping service | Mapping logic |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-2 | Design activity dashboard wireframes | Figma mockups |
| 2-5 | Create collar management list view | OWL component |
| 5-8 | Implement collar registration wizard | Multi-step form |

---

### Day 692: Activity Data Processing Pipeline

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Design activity data model for TimescaleDB | Schema design |
| 3-6 | Implement raw activity data processor | Processing service |
| 6-8 | Create activity aggregation service | 5/15/60 minute aggregates |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement LoRaWAN message handler | MQTT bridge |
| 3-5 | Create activity data validation layer | Data validation |
| 5-8 | Build TimescaleDB continuous aggregates | SQL views |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create activity level gauge component | OWL gauge |
| 3-6 | Implement activity timeline chart | Chart.js integration |
| 6-8 | Build collar status indicators | Status UI |

---

### Day 693: Heat Detection Algorithm

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Research heat detection algorithms | Algorithm specification |
| 3-6 | Implement heat detection service | Python service |
| 6-8 | Create heat event model | `iot.heat_event` model |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement baseline activity calculator | Baseline service |
| 3-5 | Create heat probability scoring | Probability engine |
| 5-8 | Build heat confirmation workflow | Confirmation logic |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create heat alert notification component | Alert UI |
| 3-6 | Implement heat calendar view | Calendar widget |
| 6-8 | Build heat history timeline | Timeline component |

---

### Day 694: Rumination Analysis

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement rumination tracking model | Rumination data model |
| 3-6 | Create rumination pattern analyzer | Pattern analysis |
| 6-8 | Build rumination anomaly detection | Anomaly service |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement rumination data pipeline | Data ingestion |
| 3-5 | Create rumination baseline calculation | Baseline logic |
| 5-8 | Build rumination alert triggers | Alert rules |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create rumination chart component | OWL chart |
| 3-6 | Implement rumination comparison view | Comparison UI |
| 6-8 | Build rumination trend analysis display | Trend view |

---

### Day 695: Health Score System

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Design health score calculation model | Score algorithm |
| 3-6 | Implement multi-factor health scorer | Scoring service |
| 6-8 | Create health trend tracking | Trend analysis |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement health score weighting system | Weight configuration |
| 3-5 | Create health score history storage | Historical data |
| 5-8 | Build health score API endpoints | REST APIs |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create health score dashboard widget | Score gauge |
| 3-6 | Implement health factor breakdown view | Factor display |
| 6-8 | Build health trend chart | Trend visualization |

---

### Day 696: Breeding Calendar Integration

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create heat-breeding calendar link | Calendar integration |
| 3-5 | Implement optimal breeding window calculator | Window calculation |
| 5-8 | Build insemination recommendation engine | AI recommendations |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Extend farm.breeding model with collar data | Model extension |
| 3-5 | Create breeding success prediction | Prediction service |
| 5-8 | Implement pregnancy detection support | Detection logic |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create breeding calendar component | Calendar OWL |
| 3-6 | Implement breeding timeline view | Timeline UI |
| 6-8 | Build insemination scheduling wizard | Scheduling form |

---

### Day 697: Veterinary Alert System

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Design veterinary alert model | `iot.vet_alert` model |
| 3-5 | Implement alert priority classification | Priority logic |
| 5-8 | Create alert routing to veterinarians | Routing service |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement veterinary notification dispatch | Notification service |
| 3-5 | Create alert acknowledgment workflow | Ack workflow |
| 5-8 | Build veterinary visit scheduling | Visit scheduling |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create vet alert dashboard | Alert list view |
| 3-6 | Implement alert detail view | Detail component |
| 6-8 | Build veterinary action form | Action form UI |

---

### Day 698: Activity Dashboard & Mobile

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create dashboard data aggregation API | API endpoints |
| 3-5 | Implement WebSocket activity streaming | Real-time stream |
| 5-8 | Build activity report generator | Report service |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement Redis caching for dashboard | Cache layer |
| 3-5 | Create activity export functionality | Export service |
| 5-8 | Build herd-level activity summary | Herd analytics |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create main activity dashboard | Dashboard view |
| 3-5 | Implement Flutter animal activity screen | Mobile screen |
| 5-8 | Build mobile heat alert handler | Mobile alerts |

---

### Day 699: Testing & Integration

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write unit tests (>80% coverage) | Test suite |
| 3-5 | Create integration tests | Integration tests |
| 5-8 | Perform code review and optimization | Code quality |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Test LoRaWAN integration | LoRa testing |
| 3-5 | Security testing for APIs | Security audit |
| 5-8 | Performance testing | Load tests |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | UI component testing | Component tests |
| 3-5 | Cross-browser testing | Compatibility |
| 5-8 | Mobile app testing | Device testing |

---

### Day 700: Documentation & Demo

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write API documentation | API docs |
| 3-5 | Create algorithm documentation | Algorithm specs |
| 5-8 | Bug fixes and final adjustments | Stable release |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write LoRaWAN setup guide | Setup docs |
| 3-5 | Create collar onboarding guide | User guide |
| 5-8 | Prepare demo environment | Demo setup |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write user guide for dashboards | User docs |
| 3-5 | Create demo scenarios | Demo materials |
| 5-8 | Conduct milestone demo | Demo presentation |

---

## 7. Technical Specifications

### 7.1 Activity Collar Model

```python
# smart_dairy_iot/models/iot_activity_collar.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError
from datetime import datetime, timedelta
import json

class IoTActivityCollar(models.Model):
    _name = 'iot.activity_collar'
    _description = 'Animal Activity Collar'
    _inherit = ['iot.device']
    _order = 'animal_id, name'

    # Collar Vendor
    collar_vendor = fields.Selection([
        ('allflex', 'Allflex SenseHub'),
        ('cowlar', 'Cowlar'),
        ('moocall', 'Moocall'),
        ('nedap', 'Nedap'),
        ('scr', 'SCR by Allflex'),
        ('generic', 'Generic LoRaWAN Collar'),
    ], string='Collar Vendor', required=True, default='allflex')
    collar_model = fields.Char(string='Collar Model')

    # Animal Link
    animal_id = fields.Many2one(
        'farm.animal',
        string='Animal',
        required=True,
        ondelete='restrict',
        domain=[('animal_type', 'in', ['cow', 'buffalo', 'goat'])]
    )
    farm_id = fields.Many2one(
        'res.partner',
        string='Farm',
        related='animal_id.farm_id',
        store=True
    )

    # LoRaWAN Configuration
    dev_eui = fields.Char(
        string='DevEUI',
        required=True,
        help='LoRaWAN Device EUI (16 hex characters)'
    )
    app_eui = fields.Char(string='AppEUI')
    app_key = fields.Char(string='AppKey')
    lorawan_profile = fields.Selection([
        ('class_a', 'Class A (Battery Optimized)'),
        ('class_c', 'Class C (Always Listening)'),
    ], string='LoRaWAN Class', default='class_a')

    # Sensor Capabilities
    has_accelerometer = fields.Boolean(
        string='Has Accelerometer',
        default=True
    )
    has_temperature = fields.Boolean(
        string='Has Temperature Sensor',
        default=True
    )
    has_gps = fields.Boolean(
        string='Has GPS',
        default=False
    )
    has_rumination = fields.Boolean(
        string='Has Rumination Sensor',
        default=True
    )

    # Current Activity Metrics
    current_activity_level = fields.Float(
        string='Current Activity Level',
        digits=(6, 2),
        readonly=True,
        help='Activity units per hour (0-500)'
    )
    activity_status = fields.Selection([
        ('resting', 'Resting'),
        ('low', 'Low Activity'),
        ('normal', 'Normal Activity'),
        ('high', 'High Activity'),
        ('very_high', 'Very High Activity'),
    ], string='Activity Status', readonly=True, default='normal')

    # Current Rumination
    current_rumination = fields.Float(
        string='Current Rumination (min/hr)',
        digits=(5, 2),
        readonly=True
    )
    rumination_status = fields.Selection([
        ('low', 'Below Normal'),
        ('normal', 'Normal'),
        ('high', 'Above Normal'),
    ], string='Rumination Status', readonly=True, default='normal')

    # Rest Time
    current_rest_time = fields.Float(
        string='Rest Time (hours/day)',
        digits=(4, 2),
        readonly=True
    )

    # Eating Time
    current_eating_time = fields.Float(
        string='Eating Time (hours/day)',
        digits=(4, 2),
        readonly=True
    )

    # Health Score
    health_score = fields.Float(
        string='Health Score',
        digits=(5, 2),
        readonly=True,
        help='0-100 health score based on activity patterns'
    )
    health_status = fields.Selection([
        ('critical', 'Critical'),
        ('poor', 'Poor'),
        ('fair', 'Fair'),
        ('good', 'Good'),
        ('excellent', 'Excellent'),
    ], string='Health Status', readonly=True, compute='_compute_health_status')

    # Heat Detection
    heat_probability = fields.Float(
        string='Heat Probability (%)',
        digits=(5, 2),
        readonly=True
    )
    in_heat = fields.Boolean(
        string='In Heat',
        readonly=True,
        default=False
    )
    last_heat_date = fields.Date(
        string='Last Heat Date',
        readonly=True
    )
    predicted_next_heat = fields.Date(
        string='Predicted Next Heat',
        compute='_compute_next_heat'
    )
    days_since_last_heat = fields.Integer(
        string='Days Since Last Heat',
        compute='_compute_days_since_heat'
    )

    # Baseline Metrics (for comparison)
    baseline_activity = fields.Float(
        string='Baseline Activity',
        digits=(6, 2),
        help='Normal activity level for this animal'
    )
    baseline_rumination = fields.Float(
        string='Baseline Rumination',
        digits=(5, 2)
    )
    baseline_rest = fields.Float(
        string='Baseline Rest',
        digits=(4, 2)
    )
    baseline_computed_date = fields.Date(
        string='Baseline Computed Date'
    )

    # Alerts
    active_alert_ids = fields.One2many(
        'iot.vet_alert',
        'collar_id',
        string='Active Alerts',
        domain=[('state', 'in', ['active', 'acknowledged'])]
    )
    alert_count = fields.Integer(
        string='Alert Count',
        compute='_compute_alert_count'
    )

    @api.constrains('dev_eui')
    def _check_dev_eui(self):
        for collar in self:
            if collar.dev_eui and len(collar.dev_eui) != 16:
                raise ValidationError('DevEUI must be 16 hexadecimal characters')

    @api.depends('health_score')
    def _compute_health_status(self):
        for collar in self:
            if collar.health_score >= 90:
                collar.health_status = 'excellent'
            elif collar.health_score >= 75:
                collar.health_status = 'good'
            elif collar.health_score >= 60:
                collar.health_status = 'fair'
            elif collar.health_score >= 40:
                collar.health_status = 'poor'
            else:
                collar.health_status = 'critical'

    @api.depends('last_heat_date')
    def _compute_next_heat(self):
        for collar in self:
            if collar.last_heat_date and collar.animal_id:
                # Typical cycle is 21 days for cows
                cycle_length = 21
                collar.predicted_next_heat = collar.last_heat_date + timedelta(days=cycle_length)
            else:
                collar.predicted_next_heat = False

    @api.depends('last_heat_date')
    def _compute_days_since_heat(self):
        today = fields.Date.today()
        for collar in self:
            if collar.last_heat_date:
                collar.days_since_last_heat = (today - collar.last_heat_date).days
            else:
                collar.days_since_last_heat = -1

    @api.depends('active_alert_ids')
    def _compute_alert_count(self):
        for collar in self:
            collar.alert_count = len(collar.active_alert_ids)

    def process_lorawan_message(self, payload, metadata=None):
        """Process incoming LoRaWAN message from collar"""
        self.ensure_one()

        data = self._decode_payload(payload)
        if not data:
            return False

        # Update device status
        self.write({
            'last_communication': fields.Datetime.now(),
            'device_status': 'online',
        })

        # Store raw activity data
        self._store_activity_data(data, metadata)

        # Update current metrics
        self._update_current_metrics(data)

        # Check for heat
        if data.get('activity'):
            self._check_heat_detection()

        # Check health
        self._update_health_score()

        # Check for alerts
        self._check_alert_conditions()

        return True

    def _decode_payload(self, payload):
        """Decode vendor-specific payload"""
        decoder_map = {
            'allflex': self._decode_allflex,
            'cowlar': self._decode_cowlar,
            'moocall': self._decode_moocall,
            'generic': self._decode_generic,
        }

        decoder = decoder_map.get(self.collar_vendor, self._decode_generic)
        return decoder(payload)

    def _decode_allflex(self, payload):
        """Decode Allflex SenseHub payload"""
        # Allflex uses specific byte structure
        if isinstance(payload, str):
            payload = bytes.fromhex(payload)

        return {
            'activity': int.from_bytes(payload[0:2], 'big'),
            'rumination': int.from_bytes(payload[2:4], 'big') / 10,
            'eating': int.from_bytes(payload[4:6], 'big') / 10,
            'rest': int.from_bytes(payload[6:8], 'big') / 10,
            'temperature': (int.from_bytes(payload[8:10], 'big') - 400) / 10,
            'battery': payload[10],
        }

    def _decode_cowlar(self, payload):
        """Decode Cowlar payload (JSON format)"""
        if isinstance(payload, str):
            data = json.loads(payload)
        else:
            data = payload

        return {
            'activity': data.get('act', 0),
            'rumination': data.get('rum', 0),
            'eating': data.get('eat', 0),
            'rest': data.get('rest', 0),
            'temperature': data.get('temp', 0),
            'battery': data.get('bat', 100),
            'steps': data.get('steps', 0),
        }

    def _decode_moocall(self, payload):
        """Decode Moocall payload"""
        # Moocall focuses on calving detection
        if isinstance(payload, str):
            data = json.loads(payload)
        else:
            data = payload

        return {
            'activity': data.get('activity_level', 0),
            'tail_movements': data.get('tail_movements', 0),
            'temperature': data.get('body_temp', 0),
            'battery': data.get('battery', 100),
            'calving_alert': data.get('calving_alert', False),
        }

    def _decode_generic(self, payload):
        """Decode generic LoRaWAN collar payload"""
        if isinstance(payload, str):
            try:
                data = json.loads(payload)
                return data
            except json.JSONDecodeError:
                payload = bytes.fromhex(payload)

        # Generic binary format
        return {
            'activity': int.from_bytes(payload[0:2], 'big'),
            'rumination': int.from_bytes(payload[2:4], 'big') / 10,
            'battery': payload[4] if len(payload) > 4 else 100,
        }

    def _store_activity_data(self, data, metadata=None):
        """Store activity data in TimescaleDB"""
        rssi = metadata.get('rssi', 0) if metadata else 0
        snr = metadata.get('snr', 0) if metadata else 0

        self.env.cr.execute("""
            INSERT INTO iot_animal_activity (
                time, collar_id, animal_id, farm_id,
                activity_level, rumination_minutes, eating_minutes,
                rest_minutes, body_temperature, battery_level,
                rssi, snr
            ) VALUES (
                NOW(), %s, %s, %s,
                %s, %s, %s, %s, %s, %s, %s, %s
            )
        """, (
            self.id,
            self.animal_id.id,
            self.farm_id.id,
            data.get('activity', 0),
            data.get('rumination', 0),
            data.get('eating', 0),
            data.get('rest', 0),
            data.get('temperature', 0),
            data.get('battery', 100),
            rssi,
            snr,
        ))

    def _update_current_metrics(self, data):
        """Update current metric fields"""
        vals = {}

        if 'activity' in data:
            activity = data['activity']
            vals['current_activity_level'] = activity

            if activity < 50:
                vals['activity_status'] = 'resting'
            elif activity < 100:
                vals['activity_status'] = 'low'
            elif activity < 200:
                vals['activity_status'] = 'normal'
            elif activity < 350:
                vals['activity_status'] = 'high'
            else:
                vals['activity_status'] = 'very_high'

        if 'rumination' in data:
            rumination = data['rumination']
            vals['current_rumination'] = rumination

            baseline = self.baseline_rumination or 30
            if rumination < baseline * 0.7:
                vals['rumination_status'] = 'low'
            elif rumination > baseline * 1.3:
                vals['rumination_status'] = 'high'
            else:
                vals['rumination_status'] = 'normal'

        if 'rest' in data:
            vals['current_rest_time'] = data['rest']

        if 'eating' in data:
            vals['current_eating_time'] = data['eating']

        if vals:
            self.write(vals)

    def _check_heat_detection(self):
        """Check for heat based on activity patterns"""
        heat_service = self.env['iot.heat.detection.service']
        probability = heat_service.calculate_heat_probability(self)

        self.heat_probability = probability

        # Heat threshold: 70%
        if probability >= 70 and not self.in_heat:
            self._trigger_heat_event()
        elif probability < 30 and self.in_heat:
            self.in_heat = False

    def _trigger_heat_event(self):
        """Create heat event and trigger notifications"""
        self.write({
            'in_heat': True,
            'last_heat_date': fields.Date.today(),
        })

        # Create heat event record
        self.env['iot.heat_event'].create({
            'collar_id': self.id,
            'animal_id': self.animal_id.id,
            'detection_time': fields.Datetime.now(),
            'probability': self.heat_probability,
            'activity_increase': self._calculate_activity_increase(),
        })

        # Create alert
        self.env['iot.vet_alert'].create({
            'collar_id': self.id,
            'animal_id': self.animal_id.id,
            'alert_type': 'heat',
            'priority': 'medium',
            'message': f'Heat detected for {self.animal_id.name} with {self.heat_probability:.1f}% probability',
        })

    def _calculate_activity_increase(self):
        """Calculate activity increase compared to baseline"""
        if not self.baseline_activity or self.baseline_activity == 0:
            return 0
        return ((self.current_activity_level - self.baseline_activity)
                / self.baseline_activity * 100)

    def _update_health_score(self):
        """Calculate and update health score"""
        health_service = self.env['iot.health.score.service']
        score = health_service.calculate_health_score(self)
        self.health_score = score

    def _check_alert_conditions(self):
        """Check various conditions that should trigger alerts"""
        alerts_to_create = []

        # Low rumination alert
        if (self.rumination_status == 'low' and
            self.current_rumination < (self.baseline_rumination or 30) * 0.5):
            alerts_to_create.append({
                'collar_id': self.id,
                'animal_id': self.animal_id.id,
                'alert_type': 'health',
                'priority': 'high',
                'message': f'Low rumination detected: {self.current_rumination:.1f} min/hr (baseline: {self.baseline_rumination:.1f})',
            })

        # Low activity alert (possible illness)
        if (self.activity_status == 'resting' and
            self.current_activity_level < (self.baseline_activity or 100) * 0.3):
            alerts_to_create.append({
                'collar_id': self.id,
                'animal_id': self.animal_id.id,
                'alert_type': 'health',
                'priority': 'high',
                'message': f'Abnormally low activity: {self.current_activity_level:.0f} (baseline: {self.baseline_activity:.0f})',
            })

        # Poor health score
        if self.health_score < 40:
            alerts_to_create.append({
                'collar_id': self.id,
                'animal_id': self.animal_id.id,
                'alert_type': 'health',
                'priority': 'critical',
                'message': f'Critical health score: {self.health_score:.0f}/100',
            })

        for alert_vals in alerts_to_create:
            # Check if similar alert already exists
            existing = self.env['iot.vet_alert'].search([
                ('collar_id', '=', self.id),
                ('alert_type', '=', alert_vals['alert_type']),
                ('state', 'in', ['active', 'acknowledged']),
            ], limit=1)

            if not existing:
                self.env['iot.vet_alert'].create(alert_vals)

    def action_compute_baseline(self):
        """Compute baseline metrics from last 7 days"""
        self.ensure_one()

        self.env.cr.execute("""
            SELECT
                AVG(activity_level) as avg_activity,
                AVG(rumination_minutes) as avg_rumination,
                AVG(rest_minutes) as avg_rest
            FROM iot_animal_activity
            WHERE collar_id = %s
              AND time >= NOW() - INTERVAL '7 days'
              AND activity_level > 0
        """, (self.id,))

        result = self.env.cr.dictfetchone()

        if result and result['avg_activity']:
            self.write({
                'baseline_activity': result['avg_activity'],
                'baseline_rumination': result['avg_rumination'],
                'baseline_rest': result['avg_rest'],
                'baseline_computed_date': fields.Date.today(),
            })

    def action_view_activity_history(self):
        """Open activity history dashboard"""
        self.ensure_one()
        return {
            'type': 'ir.actions.client',
            'tag': 'activity_collar_dashboard',
            'context': {'collar_id': self.id},
        }
```

### 7.2 Heat Detection Service

```python
# smart_dairy_iot/services/heat_detection_service.py

from odoo import models, api, fields
from datetime import datetime, timedelta
import statistics

class HeatDetectionService(models.AbstractModel):
    _name = 'iot.heat.detection.service'
    _description = 'Heat Detection Service'

    # Heat detection parameters
    ACTIVITY_INCREASE_THRESHOLD = 50  # 50% increase
    ACTIVITY_WEIGHT = 0.4
    RUMINATION_DECREASE_WEIGHT = 0.25
    REST_DECREASE_WEIGHT = 0.15
    CYCLE_TIMING_WEIGHT = 0.2

    @api.model
    def calculate_heat_probability(self, collar):
        """
        Calculate heat probability based on multiple factors:
        1. Activity increase compared to baseline
        2. Rumination decrease
        3. Rest time decrease
        4. Days since last heat (cycle timing)
        """
        probability = 0.0
        factors = {}

        # Factor 1: Activity increase
        activity_score = self._calculate_activity_score(collar)
        factors['activity'] = activity_score
        probability += activity_score * self.ACTIVITY_WEIGHT

        # Factor 2: Rumination decrease
        rumination_score = self._calculate_rumination_score(collar)
        factors['rumination'] = rumination_score
        probability += rumination_score * self.RUMINATION_DECREASE_WEIGHT

        # Factor 3: Rest decrease
        rest_score = self._calculate_rest_score(collar)
        factors['rest'] = rest_score
        probability += rest_score * self.REST_DECREASE_WEIGHT

        # Factor 4: Cycle timing
        cycle_score = self._calculate_cycle_timing_score(collar)
        factors['cycle'] = cycle_score
        probability += cycle_score * self.CYCLE_TIMING_WEIGHT

        # Apply recent activity pattern analysis
        pattern_modifier = self._analyze_recent_pattern(collar)
        probability *= pattern_modifier

        # Normalize to 0-100
        probability = min(100, max(0, probability))

        return probability

    def _calculate_activity_score(self, collar):
        """Score based on activity increase from baseline"""
        if not collar.baseline_activity or collar.baseline_activity == 0:
            return 0

        increase_pct = ((collar.current_activity_level - collar.baseline_activity)
                        / collar.baseline_activity * 100)

        if increase_pct >= 100:
            return 100
        elif increase_pct >= 70:
            return 90
        elif increase_pct >= 50:
            return 75
        elif increase_pct >= 30:
            return 50
        elif increase_pct >= 15:
            return 25
        else:
            return 0

    def _calculate_rumination_score(self, collar):
        """Score based on rumination decrease (animals ruminate less during heat)"""
        if not collar.baseline_rumination or collar.baseline_rumination == 0:
            return 50  # Neutral if no baseline

        decrease_pct = ((collar.baseline_rumination - collar.current_rumination)
                        / collar.baseline_rumination * 100)

        if decrease_pct >= 40:
            return 100
        elif decrease_pct >= 30:
            return 80
        elif decrease_pct >= 20:
            return 60
        elif decrease_pct >= 10:
            return 40
        else:
            return 0

    def _calculate_rest_score(self, collar):
        """Score based on rest time decrease"""
        if not collar.baseline_rest or collar.baseline_rest == 0:
            return 50

        decrease_pct = ((collar.baseline_rest - collar.current_rest_time)
                        / collar.baseline_rest * 100)

        if decrease_pct >= 30:
            return 100
        elif decrease_pct >= 20:
            return 70
        elif decrease_pct >= 10:
            return 40
        else:
            return 0

    def _calculate_cycle_timing_score(self, collar):
        """Score based on expected cycle timing (21 days for cows)"""
        if not collar.last_heat_date:
            return 50  # Unknown, neutral score

        days_since = collar.days_since_last_heat
        expected_cycle = 21  # Standard estrous cycle

        # Most likely: days 19-23
        if 19 <= days_since <= 23:
            return 100
        # Possible: days 17-18 or 24-25
        elif 17 <= days_since <= 18 or 24 <= days_since <= 25:
            return 70
        # Less likely: days 14-16 or 26-28
        elif 14 <= days_since <= 16 or 26 <= days_since <= 28:
            return 40
        # Unlikely: other times
        else:
            return 20

    def _analyze_recent_pattern(self, collar):
        """
        Analyze activity pattern over last 24 hours
        Heat typically shows sustained elevated activity
        """
        collar.env.cr.execute("""
            SELECT
                time_bucket('1 hour', time) as hour,
                AVG(activity_level) as avg_activity
            FROM iot_animal_activity
            WHERE collar_id = %s
              AND time >= NOW() - INTERVAL '24 hours'
            GROUP BY hour
            ORDER BY hour DESC
            LIMIT 24
        """, (collar.id,))

        hourly_data = collar.env.cr.fetchall()

        if len(hourly_data) < 6:
            return 1.0  # Not enough data

        activities = [row[1] for row in hourly_data if row[1]]

        if not activities:
            return 1.0

        # Check for sustained elevation (at least 4 of last 6 hours above baseline)
        baseline = collar.baseline_activity or statistics.mean(activities)
        elevated_hours = sum(1 for a in activities[:6] if a > baseline * 1.3)

        if elevated_hours >= 4:
            return 1.2  # Boost probability
        elif elevated_hours >= 2:
            return 1.0  # Normal
        else:
            return 0.8  # Reduce probability

    @api.model
    def confirm_heat_event(self, heat_event_id, confirmed=True):
        """Confirm or reject a heat detection"""
        heat_event = self.env['iot.heat_event'].browse(heat_event_id)

        if confirmed:
            heat_event.write({
                'state': 'confirmed',
                'confirmed_by': self.env.user.id,
                'confirmed_time': fields.Datetime.now(),
            })

            # Update collar and breeding calendar
            heat_event.collar_id.write({
                'last_heat_date': heat_event.detection_time.date(),
            })

            # Link to breeding management
            self._update_breeding_calendar(heat_event)
        else:
            heat_event.write({
                'state': 'false_positive',
                'confirmed_by': self.env.user.id,
                'confirmed_time': fields.Datetime.now(),
            })

    def _update_breeding_calendar(self, heat_event):
        """Update breeding calendar with heat event"""
        # Find or create breeding record
        breeding = self.env['farm.breeding'].search([
            ('animal_id', '=', heat_event.animal_id.id),
            ('state', '=', 'open'),
        ], limit=1)

        if breeding:
            breeding.write({
                'last_heat_date': heat_event.detection_time.date(),
                'heat_detected_by': 'collar',
                'optimal_insemination_start': heat_event.detection_time + timedelta(hours=12),
                'optimal_insemination_end': heat_event.detection_time + timedelta(hours=24),
            })
```

### 7.3 Health Score Service

```python
# smart_dairy_iot/services/health_score_service.py

from odoo import models, api
from datetime import timedelta

class HealthScoreService(models.AbstractModel):
    _name = 'iot.health.score.service'
    _description = 'Animal Health Score Service'

    # Health score weights
    WEIGHTS = {
        'activity': 0.25,
        'rumination': 0.30,
        'eating': 0.20,
        'rest': 0.15,
        'consistency': 0.10,
    }

    @api.model
    def calculate_health_score(self, collar):
        """
        Calculate comprehensive health score (0-100)
        based on multiple behavioral factors
        """
        scores = {}

        # Activity score
        scores['activity'] = self._score_activity(collar)

        # Rumination score
        scores['rumination'] = self._score_rumination(collar)

        # Eating score
        scores['eating'] = self._score_eating(collar)

        # Rest score
        scores['rest'] = self._score_rest(collar)

        # Consistency score (day-to-day variation)
        scores['consistency'] = self._score_consistency(collar)

        # Calculate weighted total
        total = sum(
            scores[factor] * weight
            for factor, weight in self.WEIGHTS.items()
        )

        return round(total, 2)

    def _score_activity(self, collar):
        """Score activity level (0-100)"""
        if not collar.baseline_activity:
            return 70  # Default score if no baseline

        ratio = collar.current_activity_level / collar.baseline_activity

        # Ideal: 80-120% of baseline
        if 0.8 <= ratio <= 1.2:
            return 100
        elif 0.6 <= ratio < 0.8 or 1.2 < ratio <= 1.5:
            return 80
        elif 0.4 <= ratio < 0.6 or 1.5 < ratio <= 2.0:
            return 60
        elif 0.2 <= ratio < 0.4:
            return 40
        elif ratio < 0.2:
            return 20
        else:  # Very high activity
            return 50

    def _score_rumination(self, collar):
        """
        Score rumination (0-100)
        Normal cow: 6-9 hours/day = 25-37.5 min/hour
        """
        rumination = collar.current_rumination

        if 25 <= rumination <= 40:
            return 100
        elif 20 <= rumination < 25 or 40 < rumination <= 45:
            return 85
        elif 15 <= rumination < 20 or 45 < rumination <= 50:
            return 70
        elif 10 <= rumination < 15:
            return 50
        elif 5 <= rumination < 10:
            return 30
        else:
            return 10

    def _score_eating(self, collar):
        """Score eating time (0-100)"""
        eating = collar.current_eating_time

        # Normal: 4-7 hours/day
        if 4 <= eating <= 7:
            return 100
        elif 3 <= eating < 4 or 7 < eating <= 8:
            return 80
        elif 2 <= eating < 3 or 8 < eating <= 9:
            return 60
        elif 1 <= eating < 2:
            return 40
        else:
            return 20

    def _score_rest(self, collar):
        """Score rest/lying time (0-100)"""
        rest = collar.current_rest_time

        # Normal: 10-14 hours/day
        if 10 <= rest <= 14:
            return 100
        elif 8 <= rest < 10 or 14 < rest <= 16:
            return 80
        elif 6 <= rest < 8 or 16 < rest <= 18:
            return 60
        elif rest < 6:
            return 40
        else:
            return 50

    def _score_consistency(self, collar):
        """Score day-to-day behavior consistency"""
        collar.env.cr.execute("""
            SELECT
                STDDEV(activity_level) as activity_std,
                STDDEV(rumination_minutes) as rumination_std
            FROM (
                SELECT
                    DATE(time) as day,
                    AVG(activity_level) as activity_level,
                    AVG(rumination_minutes) as rumination_minutes
                FROM iot_animal_activity
                WHERE collar_id = %s
                  AND time >= NOW() - INTERVAL '7 days'
                GROUP BY DATE(time)
            ) daily_avg
        """, (collar.id,))

        result = collar.env.cr.dictfetchone()

        if not result or not result['activity_std']:
            return 70

        # Lower variation = higher score
        activity_cv = (result['activity_std'] / (collar.baseline_activity or 100)) * 100
        rumination_cv = (result['rumination_std'] / (collar.baseline_rumination or 30)) * 100

        avg_cv = (activity_cv + rumination_cv) / 2

        if avg_cv < 10:
            return 100
        elif avg_cv < 20:
            return 85
        elif avg_cv < 30:
            return 70
        elif avg_cv < 40:
            return 55
        else:
            return 40

    @api.model
    def get_health_factors_breakdown(self, collar_id):
        """Get detailed breakdown of health factors"""
        collar = self.env['iot.activity_collar'].browse(collar_id)

        return {
            'activity': {
                'score': self._score_activity(collar),
                'weight': self.WEIGHTS['activity'],
                'current': collar.current_activity_level,
                'baseline': collar.baseline_activity,
                'status': collar.activity_status,
            },
            'rumination': {
                'score': self._score_rumination(collar),
                'weight': self.WEIGHTS['rumination'],
                'current': collar.current_rumination,
                'baseline': collar.baseline_rumination,
                'status': collar.rumination_status,
            },
            'eating': {
                'score': self._score_eating(collar),
                'weight': self.WEIGHTS['eating'],
                'current': collar.current_eating_time,
                'normal_range': '4-7 hours/day',
            },
            'rest': {
                'score': self._score_rest(collar),
                'weight': self.WEIGHTS['rest'],
                'current': collar.current_rest_time,
                'normal_range': '10-14 hours/day',
            },
            'consistency': {
                'score': self._score_consistency(collar),
                'weight': self.WEIGHTS['consistency'],
                'description': 'Day-to-day behavioral consistency',
            },
        }
```

### 7.4 Veterinary Alert Model

```python
# smart_dairy_iot/models/iot_vet_alert.py

from odoo import models, fields, api
from datetime import timedelta

class IoTVetAlert(models.Model):
    _name = 'iot.vet_alert'
    _description = 'Veterinary Alert'
    _order = 'priority_order, create_date desc'
    _inherit = ['mail.thread', 'mail.activity.mixin']

    name = fields.Char(
        string='Alert Reference',
        required=True,
        copy=False,
        readonly=True,
        default='New'
    )

    # Source
    collar_id = fields.Many2one(
        'iot.activity_collar',
        string='Activity Collar',
        ondelete='cascade'
    )
    animal_id = fields.Many2one(
        'farm.animal',
        string='Animal',
        required=True,
        ondelete='cascade'
    )
    farm_id = fields.Many2one(
        'res.partner',
        string='Farm',
        related='animal_id.farm_id',
        store=True
    )

    # Alert Type
    alert_type = fields.Selection([
        ('heat', 'Heat Detection'),
        ('health', 'Health Concern'),
        ('calving', 'Calving Alert'),
        ('low_activity', 'Low Activity'),
        ('low_rumination', 'Low Rumination'),
        ('lameness', 'Possible Lameness'),
        ('fever', 'Elevated Temperature'),
        ('other', 'Other'),
    ], string='Alert Type', required=True, tracking=True)

    # Priority
    priority = fields.Selection([
        ('low', 'Low'),
        ('medium', 'Medium'),
        ('high', 'High'),
        ('critical', 'Critical'),
    ], string='Priority', required=True, default='medium', tracking=True)
    priority_order = fields.Integer(
        string='Priority Order',
        compute='_compute_priority_order',
        store=True
    )

    # State
    state = fields.Selection([
        ('active', 'Active'),
        ('acknowledged', 'Acknowledged'),
        ('in_progress', 'In Progress'),
        ('resolved', 'Resolved'),
        ('dismissed', 'Dismissed'),
    ], string='State', default='active', tracking=True)

    # Alert Details
    message = fields.Text(string='Alert Message', required=True)
    details = fields.Text(string='Additional Details')
    metric_values = fields.Text(
        string='Metric Values (JSON)',
        help='Snapshot of relevant metrics at alert time'
    )

    # Timestamps
    alert_time = fields.Datetime(
        string='Alert Time',
        default=fields.Datetime.now,
        required=True
    )
    acknowledged_time = fields.Datetime(string='Acknowledged Time')
    resolved_time = fields.Datetime(string='Resolved Time')

    # Assignment
    assigned_vet = fields.Many2one(
        'res.users',
        string='Assigned Veterinarian',
        domain=[('is_veterinarian', '=', True)]
    )
    acknowledged_by = fields.Many2one('res.users', string='Acknowledged By')
    resolved_by = fields.Many2one('res.users', string='Resolved By')

    # Resolution
    resolution_notes = fields.Text(string='Resolution Notes')
    diagnosis = fields.Text(string='Diagnosis')
    treatment = fields.Text(string='Treatment Provided')
    follow_up_required = fields.Boolean(string='Follow-up Required')
    follow_up_date = fields.Date(string='Follow-up Date')

    # Related Records
    vet_visit_id = fields.Many2one(
        'farm.vet_visit',
        string='Veterinary Visit'
    )
    heat_event_id = fields.Many2one(
        'iot.heat_event',
        string='Heat Event'
    )

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            if vals.get('name', 'New') == 'New':
                vals['name'] = self.env['ir.sequence'].next_by_code(
                    'iot.vet.alert'
                ) or 'New'

        alerts = super().create(vals_list)

        for alert in alerts:
            alert._send_notifications()

        return alerts

    @api.depends('priority')
    def _compute_priority_order(self):
        priority_map = {'critical': 1, 'high': 2, 'medium': 3, 'low': 4}
        for alert in self:
            alert.priority_order = priority_map.get(alert.priority, 5)

    def action_acknowledge(self):
        """Acknowledge the alert"""
        self.write({
            'state': 'acknowledged',
            'acknowledged_time': fields.Datetime.now(),
            'acknowledged_by': self.env.uid,
        })

    def action_start_work(self):
        """Start working on the alert"""
        self.write({
            'state': 'in_progress',
            'assigned_vet': self.env.uid,
        })

    def action_resolve(self):
        """Open resolution wizard"""
        return {
            'type': 'ir.actions.act_window',
            'name': 'Resolve Alert',
            'res_model': 'iot.vet.alert.resolve.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_alert_id': self.id},
        }

    def action_dismiss(self):
        """Dismiss as false positive"""
        self.write({
            'state': 'dismissed',
            'resolved_by': self.env.uid,
            'resolution_notes': 'Dismissed as not requiring action',
        })

    def action_create_vet_visit(self):
        """Create veterinary visit from alert"""
        return {
            'type': 'ir.actions.act_window',
            'name': 'Schedule Vet Visit',
            'res_model': 'farm.vet_visit',
            'view_mode': 'form',
            'target': 'current',
            'context': {
                'default_animal_id': self.animal_id.id,
                'default_farm_id': self.farm_id.id,
                'default_reason': f'Alert: {self.message}',
                'default_alert_id': self.id,
            },
        }

    def _send_notifications(self):
        """Send alert notifications"""
        self.ensure_one()

        # Determine recipients based on priority
        recipients = self._get_recipients()

        # Email notification
        template = self.env.ref('smart_dairy_iot.email_template_vet_alert')
        for recipient in recipients:
            template.send_mail(self.id, email_values={'email_to': recipient.email})

        # Push notification for critical/high
        if self.priority in ('critical', 'high'):
            self._send_push_notification(recipients)

        # SMS for critical
        if self.priority == 'critical':
            self._send_sms_alert(recipients)

    def _get_recipients(self):
        """Get notification recipients based on farm and priority"""
        users = self.env['res.users']

        # Farm veterinarian
        if self.farm_id.veterinarian_id:
            users |= self.farm_id.veterinarian_id

        # Farm manager
        if self.farm_id.user_id:
            users |= self.farm_id.user_id

        # For critical: add all veterinarians
        if self.priority == 'critical':
            vets = self.env['res.users'].search([
                ('is_veterinarian', '=', True),
            ])
            users |= vets

        return users

    def _send_push_notification(self, recipients):
        """Send FCM push notification"""
        fcm_service = self.env['fcm.notification.service']

        data = {
            'alert_id': str(self.id),
            'alert_type': self.alert_type,
            'priority': self.priority,
            'animal_name': self.animal_id.name,
            'click_action': 'OPEN_VET_ALERT',
        }

        priority_emoji = {
            'critical': '🚨',
            'high': '⚠️',
            'medium': '📢',
            'low': '📋',
        }

        for user in recipients:
            fcm_service.send_notification(
                user_id=user.id,
                title=f'{priority_emoji.get(self.priority, "📢")} Vet Alert - {self.animal_id.name}',
                body=self.message[:100],
                data=data,
                priority='high' if self.priority in ('critical', 'high') else 'normal'
            )

    def _send_sms_alert(self, recipients):
        """Send SMS for critical alerts"""
        sms_service = self.env['sms.api']

        message = (
            f"🚨 CRITICAL VET ALERT\n"
            f"Animal: {self.animal_id.name}\n"
            f"Farm: {self.farm_id.name}\n"
            f"Issue: {self.alert_type}\n"
            f"Please check immediately!"
        )

        for user in recipients:
            if user.mobile:
                sms_service.send_sms(user.mobile, message)
```

### 7.5 TimescaleDB Schema for Activity Data

```sql
-- TimescaleDB Hypertable for Animal Activity
-- smart_dairy_iot/data/timescaledb_activity.sql

-- Animal activity hypertable
CREATE TABLE IF NOT EXISTS iot_animal_activity (
    time TIMESTAMPTZ NOT NULL,
    collar_id INTEGER NOT NULL,
    animal_id INTEGER NOT NULL,
    farm_id INTEGER NOT NULL,

    -- Activity Metrics
    activity_level DOUBLE PRECISION,
    rumination_minutes DOUBLE PRECISION,
    eating_minutes DOUBLE PRECISION,
    rest_minutes DOUBLE PRECISION,

    -- Physiological
    body_temperature DOUBLE PRECISION,
    steps INTEGER,

    -- Device Status
    battery_level INTEGER,
    rssi INTEGER,
    snr DOUBLE PRECISION,

    CONSTRAINT fk_collar FOREIGN KEY (collar_id)
        REFERENCES iot_activity_collar(id) ON DELETE CASCADE,
    CONSTRAINT fk_animal FOREIGN KEY (animal_id)
        REFERENCES farm_animal(id) ON DELETE CASCADE
);

-- Convert to hypertable
SELECT create_hypertable(
    'iot_animal_activity',
    'time',
    chunk_time_interval => INTERVAL '1 day',
    if_not_exists => TRUE
);

-- Create indexes
CREATE INDEX IF NOT EXISTS idx_activity_collar_time
    ON iot_animal_activity (collar_id, time DESC);

CREATE INDEX IF NOT EXISTS idx_activity_animal_time
    ON iot_animal_activity (animal_id, time DESC);

CREATE INDEX IF NOT EXISTS idx_activity_farm_time
    ON iot_animal_activity (farm_id, time DESC);

-- Continuous aggregate for 15-minute intervals
CREATE MATERIALIZED VIEW IF NOT EXISTS animal_activity_15min
WITH (timescaledb.continuous) AS
SELECT
    time_bucket('15 minutes', time) AS bucket,
    collar_id,
    animal_id,
    farm_id,
    AVG(activity_level) AS avg_activity,
    MAX(activity_level) AS max_activity,
    AVG(rumination_minutes) AS avg_rumination,
    AVG(eating_minutes) AS avg_eating,
    AVG(rest_minutes) AS avg_rest,
    AVG(body_temperature) AS avg_temperature,
    SUM(steps) AS total_steps,
    COUNT(*) AS reading_count
FROM iot_animal_activity
GROUP BY bucket, collar_id, animal_id, farm_id
WITH NO DATA;

-- Refresh policy
SELECT add_continuous_aggregate_policy('animal_activity_15min',
    start_offset => INTERVAL '1 hour',
    end_offset => INTERVAL '15 minutes',
    schedule_interval => INTERVAL '15 minutes',
    if_not_exists => TRUE
);

-- Continuous aggregate for hourly
CREATE MATERIALIZED VIEW IF NOT EXISTS animal_activity_hourly
WITH (timescaledb.continuous) AS
SELECT
    time_bucket('1 hour', time) AS bucket,
    collar_id,
    animal_id,
    farm_id,
    AVG(activity_level) AS avg_activity,
    MAX(activity_level) AS max_activity,
    MIN(activity_level) AS min_activity,
    AVG(rumination_minutes) AS avg_rumination,
    AVG(eating_minutes) AS avg_eating,
    AVG(rest_minutes) AS avg_rest,
    AVG(body_temperature) AS avg_temperature,
    SUM(steps) AS total_steps,
    COUNT(*) AS reading_count
FROM iot_animal_activity
GROUP BY bucket, collar_id, animal_id, farm_id
WITH NO DATA;

SELECT add_continuous_aggregate_policy('animal_activity_hourly',
    start_offset => INTERVAL '3 hours',
    end_offset => INTERVAL '1 hour',
    schedule_interval => INTERVAL '1 hour',
    if_not_exists => TRUE
);

-- Daily summary
CREATE MATERIALIZED VIEW IF NOT EXISTS animal_activity_daily
WITH (timescaledb.continuous) AS
SELECT
    time_bucket('1 day', time) AS bucket,
    collar_id,
    animal_id,
    farm_id,
    AVG(activity_level) AS avg_activity,
    SUM(rumination_minutes) / 60 AS total_rumination_hours,
    SUM(eating_minutes) / 60 AS total_eating_hours,
    SUM(rest_minutes) / 60 AS total_rest_hours,
    AVG(body_temperature) AS avg_temperature,
    MAX(body_temperature) AS max_temperature,
    SUM(steps) AS total_steps,
    COUNT(*) AS reading_count
FROM iot_animal_activity
GROUP BY bucket, collar_id, animal_id, farm_id
WITH NO DATA;

SELECT add_continuous_aggregate_policy('animal_activity_daily',
    start_offset => INTERVAL '3 days',
    end_offset => INTERVAL '1 day',
    schedule_interval => INTERVAL '1 day',
    if_not_exists => TRUE
);

-- Compression policy
SELECT add_compression_policy('iot_animal_activity',
    INTERVAL '7 days',
    if_not_exists => TRUE
);

ALTER TABLE iot_animal_activity SET (
    timescaledb.compress,
    timescaledb.compress_segmentby = 'collar_id, animal_id',
    timescaledb.compress_orderby = 'time DESC'
);

-- Retention: keep raw data for 90 days
SELECT add_retention_policy('iot_animal_activity',
    INTERVAL '90 days',
    if_not_exists => TRUE
);

-- Heat detection events table
CREATE TABLE IF NOT EXISTS iot_heat_events (
    id SERIAL PRIMARY KEY,
    collar_id INTEGER NOT NULL,
    animal_id INTEGER NOT NULL,
    detection_time TIMESTAMPTZ NOT NULL,
    probability DOUBLE PRECISION,
    activity_increase_pct DOUBLE PRECISION,
    state VARCHAR(20) DEFAULT 'detected',
    confirmed_by INTEGER,
    confirmed_time TIMESTAMPTZ,
    notes TEXT,
    created_at TIMESTAMPTZ DEFAULT NOW(),

    CONSTRAINT fk_collar FOREIGN KEY (collar_id)
        REFERENCES iot_activity_collar(id) ON DELETE CASCADE
);

CREATE INDEX idx_heat_events_animal
    ON iot_heat_events (animal_id, detection_time DESC);
```

### 7.6 Activity Dashboard (OWL)

```javascript
/** @odoo-module **/
// smart_dairy_iot/static/src/components/activity_dashboard/activity_dashboard.js

import { Component, useState, onWillStart, onMounted, onWillUnmount } from "@odoo/owl";
import { registry } from "@web/core/registry";
import { useService } from "@web/core/utils/hooks";
import { loadJS } from "@web/core/assets";

export class ActivityCollarDashboard extends Component {
    static template = "smart_dairy_iot.ActivityCollarDashboard";
    static props = {
        collarId: { type: Number, optional: true },
        farmId: { type: Number, optional: true },
    };

    setup() {
        this.orm = useService("orm");
        this.notification = useService("notification");
        this.actionService = useService("action");

        this.state = useState({
            collars: [],
            selectedCollar: null,
            alerts: [],
            heatEvents: [],
            isLoading: true,
            viewMode: "grid",
            timeRange: "24h",
            activityChart: null,
            healthChart: null,
        });

        this.websocket = null;
        this.charts = {};

        onWillStart(async () => {
            await loadJS("/smart_dairy_iot/static/lib/chart.js/chart.min.js");
            await this.loadCollars();
            await this.loadAlerts();
            await this.loadHeatEvents();
        });

        onMounted(() => {
            this.connectWebSocket();
            if (this.state.selectedCollar) {
                this.initializeCharts();
            }
        });

        onWillUnmount(() => {
            this.disconnectWebSocket();
            this.destroyCharts();
        });
    }

    async loadCollars() {
        this.state.isLoading = true;
        try {
            const domain = this.props.farmId
                ? [["farm_id", "=", this.props.farmId]]
                : [];

            this.state.collars = await this.orm.searchRead(
                "iot.activity_collar",
                domain,
                [
                    "name", "animal_id", "collar_vendor",
                    "current_activity_level", "activity_status",
                    "current_rumination", "rumination_status",
                    "health_score", "health_status",
                    "heat_probability", "in_heat",
                    "device_status", "battery_level",
                    "alert_count"
                ]
            );

            if (this.props.collarId) {
                this.state.selectedCollar = this.state.collars.find(
                    c => c.id === this.props.collarId
                );
            }
        } catch (error) {
            this.notification.add("Failed to load collars", { type: "danger" });
        }
        this.state.isLoading = false;
    }

    async loadAlerts() {
        try {
            this.state.alerts = await this.orm.searchRead(
                "iot.vet_alert",
                [["state", "in", ["active", "acknowledged"]]],
                ["name", "animal_id", "alert_type", "priority", "message", "state"],
                { limit: 10, order: "priority_order asc, create_date desc" }
            );
        } catch (error) {
            console.error("Failed to load alerts:", error);
        }
    }

    async loadHeatEvents() {
        try {
            this.state.heatEvents = await this.orm.searchRead(
                "iot.heat_event",
                [["state", "=", "detected"]],
                ["animal_id", "detection_time", "probability"],
                { limit: 5, order: "detection_time desc" }
            );
        } catch (error) {
            console.error("Failed to load heat events:", error);
        }
    }

    async loadCollarHistory(collarId) {
        try {
            const result = await this.orm.call(
                "iot.activity_collar",
                "get_activity_history",
                [collarId],
                { time_range: this.state.timeRange }
            );
            this.updateActivityChart(result);
        } catch (error) {
            console.error("Failed to load collar history:", error);
        }
    }

    connectWebSocket() {
        const protocol = window.location.protocol === "https:" ? "wss:" : "ws:";
        const wsUrl = `${protocol}//${window.location.host}/ws/iot/activity`;

        this.websocket = new WebSocket(wsUrl);

        this.websocket.onmessage = (event) => {
            const data = JSON.parse(event.data);
            this.handleRealtimeUpdate(data);
        };

        this.websocket.onerror = (error) => {
            console.error("WebSocket error:", error);
        };

        this.websocket.onclose = () => {
            setTimeout(() => this.connectWebSocket(), 5000);
        };
    }

    disconnectWebSocket() {
        if (this.websocket) {
            this.websocket.close();
        }
    }

    handleRealtimeUpdate(data) {
        if (data.type === "activity_update") {
            const collar = this.state.collars.find(c => c.id === data.collar_id);
            if (collar) {
                collar.current_activity_level = data.activity;
                collar.activity_status = data.activity_status;
                collar.current_rumination = data.rumination;
                collar.health_score = data.health_score;
            }
        } else if (data.type === "heat_detected") {
            this.notification.add(
                `Heat detected: ${data.animal_name}`,
                {
                    type: "warning",
                    sticky: true,
                    buttons: [{
                        name: "View",
                        onClick: () => this.viewHeatEvent(data.event_id),
                    }]
                }
            );
            this.loadHeatEvents();
        } else if (data.type === "health_alert") {
            this.showHealthAlert(data);
        }
    }

    showHealthAlert(data) {
        const type = data.priority === "critical" ? "danger"
            : data.priority === "high" ? "warning"
            : "info";

        this.notification.add(
            `Health Alert: ${data.animal_name} - ${data.message}`,
            { type, sticky: data.priority === "critical" }
        );
    }

    initializeCharts() {
        this.initActivityChart();
        this.initHealthChart();
    }

    initActivityChart() {
        const ctx = document.getElementById("activityChart");
        if (!ctx) return;

        this.charts.activity = new Chart(ctx, {
            type: "line",
            data: {
                labels: [],
                datasets: [
                    {
                        label: "Activity Level",
                        data: [],
                        borderColor: "#4472C4",
                        fill: false,
                        yAxisID: "y",
                    },
                    {
                        label: "Rumination (min/hr)",
                        data: [],
                        borderColor: "#70AD47",
                        fill: false,
                        yAxisID: "y1",
                    },
                    {
                        label: "Baseline Activity",
                        data: [],
                        borderColor: "#4472C4",
                        borderDash: [5, 5],
                        fill: false,
                        yAxisID: "y",
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: "index",
                    intersect: false,
                },
                scales: {
                    y: {
                        type: "linear",
                        display: true,
                        position: "left",
                        title: { display: true, text: "Activity Level" }
                    },
                    y1: {
                        type: "linear",
                        display: true,
                        position: "right",
                        title: { display: true, text: "Rumination (min/hr)" },
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });
    }

    initHealthChart() {
        const ctx = document.getElementById("healthChart");
        if (!ctx) return;

        this.charts.health = new Chart(ctx, {
            type: "radar",
            data: {
                labels: ["Activity", "Rumination", "Eating", "Rest", "Consistency"],
                datasets: [{
                    label: "Health Factors",
                    data: [0, 0, 0, 0, 0],
                    backgroundColor: "rgba(54, 162, 235, 0.2)",
                    borderColor: "rgb(54, 162, 235)",
                    pointBackgroundColor: "rgb(54, 162, 235)",
                }]
            },
            options: {
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                    }
                }
            }
        });
    }

    updateActivityChart(data) {
        if (!this.charts.activity) return;

        this.charts.activity.data.labels = data.timestamps;
        this.charts.activity.data.datasets[0].data = data.activity;
        this.charts.activity.data.datasets[1].data = data.rumination;
        this.charts.activity.data.datasets[2].data = data.timestamps.map(
            () => data.baseline_activity
        );
        this.charts.activity.update();
    }

    async updateHealthChart(collarId) {
        if (!this.charts.health) return;

        const factors = await this.orm.call(
            "iot.health.score.service",
            "get_health_factors_breakdown",
            [collarId]
        );

        this.charts.health.data.datasets[0].data = [
            factors.activity.score,
            factors.rumination.score,
            factors.eating.score,
            factors.rest.score,
            factors.consistency.score,
        ];
        this.charts.health.update();
    }

    destroyCharts() {
        Object.values(this.charts).forEach(chart => chart?.destroy());
    }

    // UI Actions
    selectCollar(collarId) {
        this.state.selectedCollar = this.state.collars.find(c => c.id === collarId);
        this.loadCollarHistory(collarId);
        this.updateHealthChart(collarId);
    }

    setTimeRange(range) {
        this.state.timeRange = range;
        if (this.state.selectedCollar) {
            this.loadCollarHistory(this.state.selectedCollar.id);
        }
    }

    async acknowledgeAlert(alertId) {
        await this.orm.call("iot.vet_alert", "action_acknowledge", [alertId]);
        this.loadAlerts();
    }

    async confirmHeat(eventId, confirmed) {
        await this.orm.call(
            "iot.heat.detection.service",
            "confirm_heat_event",
            [eventId, confirmed]
        );
        this.loadHeatEvents();
        this.loadCollars();
    }

    viewAnimal(animalId) {
        this.actionService.doAction({
            type: "ir.actions.act_window",
            res_model: "farm.animal",
            res_id: animalId,
            views: [[false, "form"]],
        });
    }

    // Computed
    get inHeatAnimals() {
        return this.state.collars.filter(c => c.in_heat);
    }

    get healthConcernAnimals() {
        return this.state.collars.filter(c => c.health_status in ["critical", "poor"]);
    }

    getHealthColor(status) {
        const colors = {
            "excellent": "bg-success",
            "good": "bg-info",
            "fair": "bg-warning",
            "poor": "bg-orange",
            "critical": "bg-danger",
        };
        return colors[status] || "bg-secondary";
    }

    getActivityIcon(status) {
        const icons = {
            "resting": "fa-bed",
            "low": "fa-walking",
            "normal": "fa-person-walking",
            "high": "fa-person-running",
            "very_high": "fa-bolt",
        };
        return icons[status] || "fa-question";
    }

    formatNumber(num) {
        return num ? num.toFixed(1) : "0";
    }
}

registry.category("actions").add("activity_collar_dashboard", ActivityCollarDashboard);
```

### 7.7 Flutter Animal Activity Screen

```dart
// lib/screens/iot/animal_activity_screen.dart

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:smart_dairy_mobile/providers/iot_provider.dart';
import 'package:smart_dairy_mobile/models/activity_collar.dart';
import 'package:fl_chart/fl_chart.dart';

class AnimalActivityScreen extends StatefulWidget {
  final int collarId;

  const AnimalActivityScreen({Key? key, required this.collarId}) : super(key: key);

  @override
  State<AnimalActivityScreen> createState() => _AnimalActivityScreenState();
}

class _AnimalActivityScreenState extends State<AnimalActivityScreen>
    with SingleTickerProviderStateMixin {
  late TabController _tabController;
  String _timeRange = '24h';

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 3, vsync: this);
    _loadData();
  }

  Future<void> _loadData() async {
    await context.read<IoTProvider>().loadCollarDetails(widget.collarId);
    await context.read<IoTProvider>().loadActivityHistory(
      widget.collarId,
      timeRange: _timeRange,
    );
  }

  @override
  Widget build(BuildContext context) {
    return Consumer<IoTProvider>(
      builder: (context, provider, child) {
        final collar = provider.selectedCollar;

        if (collar == null) {
          return const Scaffold(
            body: Center(child: CircularProgressIndicator()),
          );
        }

        return Scaffold(
          appBar: AppBar(
            title: Text(collar.animalName),
            actions: [
              IconButton(
                icon: const Icon(Icons.refresh),
                onPressed: _loadData,
              ),
            ],
            bottom: TabBar(
              controller: _tabController,
              tabs: const [
                Tab(text: 'Activity'),
                Tab(text: 'Health'),
                Tab(text: 'Reproduction'),
              ],
            ),
          ),
          body: TabBarView(
            controller: _tabController,
            children: [
              _buildActivityTab(collar, provider),
              _buildHealthTab(collar),
              _buildReproductionTab(collar),
            ],
          ),
        );
      },
    );
  }

  Widget _buildActivityTab(ActivityCollar collar, IoTProvider provider) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Current Status Card
          _buildCurrentStatusCard(collar),
          const SizedBox(height: 16),

          // Time Range Selector
          _buildTimeRangeSelector(),
          const SizedBox(height: 16),

          // Activity Chart
          _buildActivityChart(provider.activityHistory),
          const SizedBox(height: 16),

          // Metrics Cards
          _buildMetricsRow(collar),
        ],
      ),
    );
  }

  Widget _buildCurrentStatusCard(ActivityCollar collar) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Row(
              children: [
                _buildActivityIndicator(collar),
                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        collar.animalName,
                        style: Theme.of(context).textTheme.titleLarge,
                      ),
                      Text(
                        collar.collarVendor,
                        style: Theme.of(context).textTheme.bodySmall,
                      ),
                      const SizedBox(height: 8),
                      Row(
                        children: [
                          Icon(
                            collar.deviceStatus == 'online'
                                ? Icons.wifi
                                : Icons.wifi_off,
                            size: 16,
                            color: collar.deviceStatus == 'online'
                                ? Colors.green
                                : Colors.red,
                          ),
                          const SizedBox(width: 4),
                          Text(collar.deviceStatus),
                          const SizedBox(width: 16),
                          Icon(
                            Icons.battery_std,
                            size: 16,
                            color: collar.batteryLevel > 20
                                ? Colors.green
                                : Colors.red,
                          ),
                          const SizedBox(width: 4),
                          Text('${collar.batteryLevel}%'),
                        ],
                      ),
                    ],
                  ),
                ),
              ],
            ),
            const Divider(height: 24),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: [
                _buildStatusMetric(
                  'Activity',
                  '${collar.currentActivityLevel.toStringAsFixed(0)}',
                  collar.activityStatus,
                ),
                _buildStatusMetric(
                  'Rumination',
                  '${collar.currentRumination.toStringAsFixed(1)} min/hr',
                  collar.ruminationStatus,
                ),
                _buildStatusMetric(
                  'Health',
                  '${collar.healthScore.toStringAsFixed(0)}',
                  collar.healthStatus,
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildActivityIndicator(ActivityCollar collar) {
    return Container(
      width: 80,
      height: 80,
      decoration: BoxDecoration(
        shape: BoxShape.circle,
        color: _getActivityColor(collar.activityStatus).withOpacity(0.2),
        border: Border.all(
          color: _getActivityColor(collar.activityStatus),
          width: 3,
        ),
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(
            _getActivityIcon(collar.activityStatus),
            color: _getActivityColor(collar.activityStatus),
            size: 32,
          ),
          Text(
            collar.activityStatus,
            style: TextStyle(
              fontSize: 10,
              color: _getActivityColor(collar.activityStatus),
              fontWeight: FontWeight.bold,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStatusMetric(String label, String value, String status) {
    return Column(
      children: [
        Text(
          label,
          style: const TextStyle(
            color: Colors.grey,
            fontSize: 12,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          value,
          style: const TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.bold,
          ),
        ),
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
          decoration: BoxDecoration(
            color: _getStatusColor(status),
            borderRadius: BorderRadius.circular(10),
          ),
          child: Text(
            status,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 10,
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildTimeRangeSelector() {
    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      child: Row(
        children: ['6h', '24h', '7d', '30d'].map((range) {
          final isSelected = _timeRange == range;
          return Padding(
            padding: const EdgeInsets.only(right: 8),
            child: ChoiceChip(
              label: Text(range),
              selected: isSelected,
              onSelected: (selected) {
                if (selected) {
                  setState(() => _timeRange = range);
                  context.read<IoTProvider>().loadActivityHistory(
                    widget.collarId,
                    timeRange: range,
                  );
                }
              },
            ),
          );
        }).toList(),
      ),
    );
  }

  Widget _buildActivityChart(List<ActivityDataPoint> history) {
    if (history.isEmpty) {
      return const Card(
        child: SizedBox(
          height: 200,
          child: Center(child: Text('No activity data available')),
        ),
      );
    }

    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Activity & Rumination',
              style: Theme.of(context).textTheme.titleMedium,
            ),
            const SizedBox(height: 16),
            SizedBox(
              height: 200,
              child: LineChart(
                LineChartData(
                  gridData: FlGridData(show: true),
                  titlesData: FlTitlesData(
                    leftTitles: AxisTitles(
                      sideTitles: SideTitles(showTitles: true, reservedSize: 40),
                    ),
                    bottomTitles: AxisTitles(
                      sideTitles: SideTitles(
                        showTitles: true,
                        getTitlesWidget: (value, meta) {
                          if (value.toInt() >= history.length) return const Text('');
                          final time = history[value.toInt()].time;
                          return Text(
                            '${time.hour}:${time.minute.toString().padLeft(2, '0')}',
                            style: const TextStyle(fontSize: 10),
                          );
                        },
                        interval: (history.length / 6).ceilToDouble(),
                      ),
                    ),
                    rightTitles: AxisTitles(
                      sideTitles: SideTitles(showTitles: false),
                    ),
                    topTitles: AxisTitles(
                      sideTitles: SideTitles(showTitles: false),
                    ),
                  ),
                  lineBarsData: [
                    LineChartBarData(
                      spots: history.asMap().entries.map((e) {
                        return FlSpot(e.key.toDouble(), e.value.activity);
                      }).toList(),
                      isCurved: true,
                      color: Colors.blue,
                      barWidth: 2,
                      dotData: FlDotData(show: false),
                    ),
                    LineChartBarData(
                      spots: history.asMap().entries.map((e) {
                        return FlSpot(e.key.toDouble(), e.value.rumination * 3);
                      }).toList(),
                      isCurved: true,
                      color: Colors.green,
                      barWidth: 2,
                      dotData: FlDotData(show: false),
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 8),
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                _buildLegendItem(Colors.blue, 'Activity'),
                const SizedBox(width: 16),
                _buildLegendItem(Colors.green, 'Rumination'),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildLegendItem(Color color, String label) {
    return Row(
      children: [
        Container(
          width: 12,
          height: 12,
          decoration: BoxDecoration(
            color: color,
            borderRadius: BorderRadius.circular(2),
          ),
        ),
        const SizedBox(width: 4),
        Text(label, style: const TextStyle(fontSize: 12)),
      ],
    );
  }

  Widget _buildMetricsRow(ActivityCollar collar) {
    return Row(
      children: [
        Expanded(
          child: _buildMetricCard(
            'Eating',
            '${collar.currentEatingTime.toStringAsFixed(1)} hrs',
            Icons.restaurant,
            Colors.orange,
          ),
        ),
        const SizedBox(width: 8),
        Expanded(
          child: _buildMetricCard(
            'Rest',
            '${collar.currentRestTime.toStringAsFixed(1)} hrs',
            Icons.hotel,
            Colors.purple,
          ),
        ),
      ],
    );
  }

  Widget _buildMetricCard(String label, String value, IconData icon, Color color) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Icon(icon, color: color, size: 32),
            const SizedBox(height: 8),
            Text(
              value,
              style: const TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
              ),
            ),
            Text(label, style: const TextStyle(color: Colors.grey)),
          ],
        ),
      ),
    );
  }

  Widget _buildHealthTab(ActivityCollar collar) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          // Health Score Gauge
          _buildHealthScoreGauge(collar),
          const SizedBox(height: 16),

          // Health Factors
          _buildHealthFactors(collar),
          const SizedBox(height: 16),

          // Alerts
          _buildActiveAlerts(collar),
        ],
      ),
    );
  }

  Widget _buildHealthScoreGauge(ActivityCollar collar) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          children: [
            Stack(
              alignment: Alignment.center,
              children: [
                SizedBox(
                  width: 150,
                  height: 150,
                  child: CircularProgressIndicator(
                    value: collar.healthScore / 100,
                    strokeWidth: 12,
                    backgroundColor: Colors.grey[200],
                    valueColor: AlwaysStoppedAnimation(
                      _getHealthColor(collar.healthStatus),
                    ),
                  ),
                ),
                Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Text(
                      collar.healthScore.toStringAsFixed(0),
                      style: const TextStyle(
                        fontSize: 36,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    Text(
                      collar.healthStatus.toUpperCase(),
                      style: TextStyle(
                        color: _getHealthColor(collar.healthStatus),
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
              ],
            ),
            const SizedBox(height: 16),
            const Text(
              'Overall Health Score',
              style: TextStyle(fontSize: 16),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildHealthFactors(ActivityCollar collar) {
    // This would fetch factor breakdown from API
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Health Factors',
              style: Theme.of(context).textTheme.titleMedium,
            ),
            const SizedBox(height: 16),
            _buildFactorRow('Activity', 0.85, Colors.blue),
            _buildFactorRow('Rumination', 0.78, Colors.green),
            _buildFactorRow('Eating', 0.92, Colors.orange),
            _buildFactorRow('Rest', 0.75, Colors.purple),
            _buildFactorRow('Consistency', 0.88, Colors.teal),
          ],
        ),
      ),
    );
  }

  Widget _buildFactorRow(String label, double value, Color color) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        children: [
          SizedBox(
            width: 100,
            child: Text(label),
          ),
          Expanded(
            child: LinearProgressIndicator(
              value: value,
              backgroundColor: color.withOpacity(0.2),
              valueColor: AlwaysStoppedAnimation(color),
            ),
          ),
          const SizedBox(width: 8),
          Text('${(value * 100).toStringAsFixed(0)}%'),
        ],
      ),
    );
  }

  Widget _buildActiveAlerts(ActivityCollar collar) {
    if (collar.alertCount == 0) {
      return Card(
        child: Padding(
          padding: const EdgeInsets.all(24),
          child: Column(
            children: [
              Icon(Icons.check_circle, color: Colors.green[300], size: 48),
              const SizedBox(height: 8),
              const Text('No active health alerts'),
            ],
          ),
        ),
      );
    }

    return Card(
      color: Colors.red[50],
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Row(
          children: [
            Icon(Icons.warning, color: Colors.red[700], size: 32),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    '${collar.alertCount} Active Alerts',
                    style: TextStyle(
                      fontWeight: FontWeight.bold,
                      color: Colors.red[700],
                    ),
                  ),
                  const Text('Tap to view and manage'),
                ],
              ),
            ),
            Icon(Icons.chevron_right, color: Colors.red[700]),
          ],
        ),
      ),
    );
  }

  Widget _buildReproductionTab(ActivityCollar collar) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          // Heat Status Card
          _buildHeatStatusCard(collar),
          const SizedBox(height: 16),

          // Breeding Calendar
          _buildBreedingCalendar(collar),
          const SizedBox(height: 16),

          // Heat History
          _buildHeatHistory(collar),
        ],
      ),
    );
  }

  Widget _buildHeatStatusCard(ActivityCollar collar) {
    return Card(
      color: collar.inHeat ? Colors.pink[50] : null,
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Row(
              children: [
                Icon(
                  collar.inHeat ? Icons.favorite : Icons.favorite_border,
                  color: collar.inHeat ? Colors.pink : Colors.grey,
                  size: 48,
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        collar.inHeat ? 'IN HEAT' : 'Not in Heat',
                        style: TextStyle(
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                          color: collar.inHeat ? Colors.pink : Colors.grey[600],
                        ),
                      ),
                      if (collar.inHeat)
                        Text(
                          'Probability: ${collar.heatProbability.toStringAsFixed(1)}%',
                          style: TextStyle(color: Colors.pink[700]),
                        ),
                    ],
                  ),
                ),
              ],
            ),
            if (collar.inHeat) ...[
              const Divider(height: 24),
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: Colors.pink[100],
                  borderRadius: BorderRadius.circular(8),
                ),
                child: const Row(
                  children: [
                    Icon(Icons.timer, color: Colors.pink),
                    SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        'Optimal insemination window: Next 12-24 hours',
                        style: TextStyle(fontWeight: FontWeight.w500),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ],
        ),
      ),
    );
  }

  Widget _buildBreedingCalendar(ActivityCollar collar) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Breeding Information',
              style: Theme.of(context).textTheme.titleMedium,
            ),
            const SizedBox(height: 16),
            _buildInfoRow('Last Heat', collar.lastHeatDate ?? 'Unknown'),
            _buildInfoRow('Days Since Heat', '${collar.daysSinceLastHeat} days'),
            _buildInfoRow(
              'Predicted Next Heat',
              collar.predictedNextHeat ?? 'Unknown',
            ),
            _buildInfoRow('Cycle Length', '21 days (typical)'),
          ],
        ),
      ),
    );
  }

  Widget _buildInfoRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: const TextStyle(color: Colors.grey)),
          Text(value, style: const TextStyle(fontWeight: FontWeight.w500)),
        ],
      ),
    );
  }

  Widget _buildHeatHistory(ActivityCollar collar) {
    // Would fetch from API
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Heat History',
              style: Theme.of(context).textTheme.titleMedium,
            ),
            const SizedBox(height: 16),
            const Center(
              child: Text(
                'Heat history will be displayed here',
                style: TextStyle(color: Colors.grey),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Color _getActivityColor(String status) {
    switch (status) {
      case 'resting':
        return Colors.grey;
      case 'low':
        return Colors.blue[300]!;
      case 'normal':
        return Colors.green;
      case 'high':
        return Colors.orange;
      case 'very_high':
        return Colors.red;
      default:
        return Colors.grey;
    }
  }

  IconData _getActivityIcon(String status) {
    switch (status) {
      case 'resting':
        return Icons.hotel;
      case 'low':
        return Icons.directions_walk;
      case 'normal':
        return Icons.directions_walk;
      case 'high':
        return Icons.directions_run;
      case 'very_high':
        return Icons.flash_on;
      default:
        return Icons.help;
    }
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'excellent':
        return Colors.green;
      case 'good':
        return Colors.blue;
      case 'fair':
        return Colors.orange;
      case 'poor':
        return Colors.deepOrange;
      case 'critical':
        return Colors.red;
      case 'normal':
        return Colors.green;
      case 'low':
        return Colors.orange;
      case 'high':
        return Colors.blue;
      default:
        return Colors.grey;
    }
  }

  Color _getHealthColor(String status) {
    switch (status) {
      case 'excellent':
        return Colors.green[700]!;
      case 'good':
        return Colors.green;
      case 'fair':
        return Colors.orange;
      case 'poor':
        return Colors.deepOrange;
      case 'critical':
        return Colors.red;
      default:
        return Colors.grey;
    }
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }
}
```

---

## 8. Testing & Validation

### 8.1 Unit Tests

```python
# smart_dairy_iot/tests/test_activity_collar.py

from odoo.tests.common import TransactionCase
from odoo.exceptions import ValidationError
import json

class TestActivityCollar(TransactionCase):

    def setUp(self):
        super().setUp()
        self.farm = self.env['res.partner'].create({
            'name': 'Test Farm',
            'is_farm': True,
        })
        self.animal = self.env['farm.animal'].create({
            'name': 'Test Cow',
            'animal_type': 'cow',
            'farm_id': self.farm.id,
        })
        self.collar = self.env['iot.activity_collar'].create({
            'name': 'Test Collar',
            'device_uid': 'COL001',
            'dev_eui': '0011223344556677',
            'animal_id': self.animal.id,
            'collar_vendor': 'allflex',
            'baseline_activity': 150,
            'baseline_rumination': 30,
        })

    def test_collar_creation(self):
        """Test activity collar is created correctly"""
        self.assertEqual(self.collar.animal_id, self.animal)
        self.assertEqual(self.collar.collar_vendor, 'allflex')
        self.assertEqual(self.collar.activity_status, 'normal')

    def test_dev_eui_validation(self):
        """Test DevEUI must be 16 characters"""
        with self.assertRaises(ValidationError):
            self.collar.dev_eui = '00112233'  # Too short

    def test_activity_status_calculation(self):
        """Test activity status is calculated correctly"""
        self.collar.current_activity_level = 30
        self.collar._update_current_metrics({'activity': 30})
        self.assertEqual(self.collar.activity_status, 'resting')

        self.collar._update_current_metrics({'activity': 300})
        self.assertEqual(self.collar.activity_status, 'high')

    def test_health_status_computation(self):
        """Test health status is computed from score"""
        self.collar.health_score = 95
        self.collar._compute_health_status()
        self.assertEqual(self.collar.health_status, 'excellent')

        self.collar.health_score = 35
        self.collar._compute_health_status()
        self.assertEqual(self.collar.health_status, 'poor')


class TestHeatDetection(TransactionCase):

    def setUp(self):
        super().setUp()
        self.farm = self.env['res.partner'].create({
            'name': 'Test Farm',
            'is_farm': True,
        })
        self.animal = self.env['farm.animal'].create({
            'name': 'Test Cow',
            'animal_type': 'cow',
            'farm_id': self.farm.id,
        })
        self.collar = self.env['iot.activity_collar'].create({
            'name': 'Test Collar',
            'device_uid': 'COL002',
            'dev_eui': 'AABBCCDDEEFF0011',
            'animal_id': self.animal.id,
            'collar_vendor': 'allflex',
            'baseline_activity': 100,
            'baseline_rumination': 30,
            'current_activity_level': 180,
            'current_rumination': 20,
        })

    def test_heat_probability_calculation(self):
        """Test heat probability is calculated correctly"""
        service = self.env['iot.heat.detection.service']
        probability = service.calculate_heat_probability(self.collar)

        # Should have elevated probability due to high activity
        self.assertGreater(probability, 50)

    def test_heat_event_creation(self):
        """Test heat event is created when probability is high"""
        self.collar.heat_probability = 85
        self.collar._trigger_heat_event()

        self.assertTrue(self.collar.in_heat)
        self.assertEqual(self.collar.last_heat_date, fields.Date.today())

        # Check heat event was created
        heat_event = self.env['iot.heat_event'].search([
            ('collar_id', '=', self.collar.id),
        ], limit=1)
        self.assertTrue(heat_event)


class TestHealthScore(TransactionCase):

    def setUp(self):
        super().setUp()
        self.farm = self.env['res.partner'].create({
            'name': 'Test Farm',
            'is_farm': True,
        })
        self.animal = self.env['farm.animal'].create({
            'name': 'Test Cow',
            'animal_type': 'cow',
            'farm_id': self.farm.id,
        })
        self.collar = self.env['iot.activity_collar'].create({
            'name': 'Test Collar',
            'device_uid': 'COL003',
            'dev_eui': '1122334455667788',
            'animal_id': self.animal.id,
            'collar_vendor': 'allflex',
            'baseline_activity': 150,
            'baseline_rumination': 30,
            'baseline_rest': 12,
            'current_activity_level': 150,
            'current_rumination': 30,
            'current_rest_time': 12,
            'current_eating_time': 5.5,
        })

    def test_health_score_calculation(self):
        """Test health score is calculated correctly"""
        service = self.env['iot.health.score.service']
        score = service.calculate_health_score(self.collar)

        # All metrics at baseline, should be high score
        self.assertGreater(score, 80)

    def test_health_factors_breakdown(self):
        """Test health factors breakdown"""
        service = self.env['iot.health.score.service']
        factors = service.get_health_factors_breakdown(self.collar.id)

        self.assertIn('activity', factors)
        self.assertIn('rumination', factors)
        self.assertIn('eating', factors)
        self.assertIn('rest', factors)
        self.assertIn('consistency', factors)
```

### 8.2 Performance Targets

| Test Case | Target | Method |
|-----------|--------|--------|
| LoRaWAN message processing | <1 second | End-to-end timing |
| Activity data insert | 1000/second | Load test |
| Heat detection calculation | <500ms | Algorithm profiling |
| Health score calculation | <200ms | Profiling |
| Dashboard data query | <500ms | API response time |
| Mobile app refresh | <2 seconds | Network latency test |

---

## 9. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| LoRaWAN coverage gaps | Medium | Medium | Deploy additional gateways |
| False heat detection | Medium | High | Multi-factor validation, confirmation workflow |
| Collar battery drain | Low | Medium | Optimize transmission intervals |
| Animal dislodges collar | Medium | Low | Secure attachment design, alerts |
| Algorithm accuracy issues | Low | High | Continuous model training with confirmed data |

---

## 10. Dependencies & Handoffs

### 10.1 Incoming Dependencies
| Dependency | Source | Required By |
|------------|--------|-------------|
| MQTT infrastructure | M91 | Day 691 |
| Device registry | M92 | Day 691 |
| Animal registry | Phase 5 | Day 691 |
| Breeding module | Phase 5 | Day 696 |

### 10.2 Outgoing Handoffs
| Deliverable | Receiving Milestone | Handoff Date |
|-------------|---------------------|--------------|
| Activity collar model | M97 (Data Pipeline) | Day 700 |
| Health alerts | M98 (Real-time Alerts) | Day 700 |
| Heat detection | M100 (Predictive Analytics) | Day 700 |

---

## 11. Quality Assurance

### 11.1 Test Coverage Targets
| Component | Target Coverage |
|-----------|----------------|
| Activity collar model | 85% |
| Heat detection service | 90% |
| Health score service | 85% |
| LoRaWAN integration | 80% |
| API endpoints | 80% |

### 11.2 Acceptance Criteria
- [ ] LoRaWAN messages processed within 1 second
- [ ] Heat detection accuracy >90% (validated against manual observation)
- [ ] Health score reflects actual animal condition
- [ ] Multi-vendor collar support working
- [ ] Activity dashboard displays real-time data
- [ ] Mobile app shows animal status
- [ ] Breeding calendar integration functional
- [ ] Veterinary alerts delivered within 1 minute

---

## 12. Milestone Sign-off Checklist

| # | Criteria | Owner | Status |
|---|----------|-------|--------|
| 1 | Activity collar model complete | Dev 1 | ☐ |
| 2 | LoRaWAN integration working | Dev 2 | ☐ |
| 3 | Activity processing pipeline functional | Dev 1 | ☐ |
| 4 | Heat detection algorithm implemented | Dev 1 | ☐ |
| 5 | Rumination analysis working | Dev 1 | ☐ |
| 6 | Health score calculation accurate | Dev 2 | ☐ |
| 7 | Breeding calendar integrated | Dev 2 | ☐ |
| 8 | Veterinary alert system working | Dev 1 | ☐ |
| 9 | Activity dashboard deployed | Dev 3 | ☐ |
| 10 | Mobile app functional | Dev 3 | ☐ |
| 11 | Unit tests passing (>80%) | All | ☐ |
| 12 | Integration tests passing | Dev 2 | ☐ |
| 13 | Documentation complete | All | ☐ |
| 14 | Demo completed successfully | All | ☐ |
| 15 | Stakeholder sign-off | PM | ☐ |

---

**Document Revision History**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-04 | Development Team | Initial creation |

---

*This document is part of the Smart Dairy Digital Smart Portal + ERP System implementation roadmap.*
