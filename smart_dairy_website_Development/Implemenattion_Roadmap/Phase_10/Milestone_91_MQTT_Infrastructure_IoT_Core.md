# Milestone 91: MQTT Infrastructure & IoT Core

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field                | Details                                                      |
| -------------------- | ------------------------------------------------------------ |
| **Document ID**      | SD-IMPL-P10-M91-v1.0                                         |
| **Milestone**        | 91 - MQTT Infrastructure & IoT Core                          |
| **Phase**            | Phase 10: Commerce - IoT Integration Core                    |
| **Days**             | 651-660                                                      |
| **Duration**         | 10 Working Days                                              |
| **Status**           | Draft                                                        |
| **Version**          | 1.0.0                                                        |
| **Last Updated**     | 2026-02-04                                                   |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 91 establishes the foundational MQTT (Message Queuing Telemetry Transport) infrastructure and core IoT platform for Smart Dairy's precision farming initiative. This milestone implements the message broker infrastructure, device authentication system, topic hierarchy, and gateway framework that will support all subsequent IoT device integrations including milk meters, temperature sensors, activity collars, and feed dispensers.

### 1.2 Scope

- EMQX 5.x MQTT Broker Cluster deployment with Docker/Kubernetes
- MQTT Topic Hierarchy design and implementation
- Device Authentication System (X.509 certificates, PostgreSQL credentials)
- Access Control List (ACL) configuration and management
- MQTT-to-Odoo Bridge Service for message processing
- IoT Gateway Python Framework for protocol translation
- Message Validation and Routing Service
- Monitoring Integration (Prometheus/Grafana)
- Security Hardening (TLS 1.3, rate limiting, DDoS protection)
- Unit and Integration Tests

### 1.3 Key Outcomes

| Outcome                        | Target                                    |
| ------------------------------ | ----------------------------------------- |
| MQTT Broker Uptime             | 99.9% availability                        |
| Concurrent Connections         | 10,000+ devices supported                 |
| Message Latency                | < 100ms end-to-end                        |
| Authentication Security        | X.509 + credential-based                  |
| Topic Structure                | Hierarchical, farm-device-type-id pattern |
| Monitoring Coverage            | 100% broker metrics visible               |

---

## 2. Objectives

1. **Deploy EMQX Cluster** - Production-grade MQTT broker with high availability and clustering
2. **Design Topic Hierarchy** - Scalable topic structure supporting multi-farm, multi-device scenarios
3. **Implement Device Authentication** - Secure device onboarding with X.509 certificates and database credentials
4. **Configure ACL System** - Fine-grained access control per device type and farm
5. **Build MQTT-Odoo Bridge** - Message processing service connecting IoT data to Odoo models
6. **Create IoT Gateway Framework** - Extensible Python framework for protocol translation
7. **Enable Monitoring** - Prometheus metrics and Grafana dashboards for broker health

---

## 3. Key Deliverables

| #  | Deliverable                                | Owner  | Priority |
| -- | ------------------------------------------ | ------ | -------- |
| 1  | EMQX 5.x Cluster Deployment                | Dev 2  | Critical |
| 2  | MQTT Topic Hierarchy Design                | Dev 1  | Critical |
| 3  | Device Authentication Service              | Dev 2  | Critical |
| 4  | ACL Configuration and Management           | Dev 1  | High     |
| 5  | MQTT-to-Odoo Bridge Service                | Dev 1  | Critical |
| 6  | IoT Gateway Python Framework               | Dev 1  | High     |
| 7  | Message Validation Service                 | Dev 1  | High     |
| 8  | Odoo iot.device Model                      | Dev 1  | High     |
| 9  | TLS/SSL Certificate Management             | Dev 2  | High     |
| 10 | Prometheus/Grafana Monitoring              | Dev 2  | High     |
| 11 | OWL Device Management UI                   | Dev 3  | High     |
| 12 | Unit & Integration Tests                   | All    | High     |

---

## 4. Prerequisites

| Prerequisite                       | Source      | Status   |
| ---------------------------------- | ----------- | -------- |
| Phase 9 B2B Portal Complete        | Phase 9     | Complete |
| Docker/Kubernetes Infrastructure   | Phase 1     | Ready    |
| PostgreSQL 16 Database             | Phase 1     | Ready    |
| Redis Caching Infrastructure       | Phase 1     | Ready    |
| Prometheus/Grafana Stack           | Phase 1     | Ready    |
| SSL Certificates (Wildcard)        | Phase 2     | Ready    |
| OWL 2.0 Framework Setup            | Phase 4     | Ready    |

---

## 5. Requirement Traceability

| Req ID       | Description                              | Source | Priority |
| ------------ | ---------------------------------------- | ------ | -------- |
| IOT-001      | MQTT broker infrastructure               | RFP    | Must     |
| SRS-IOT-001  | 10,000+ concurrent connections           | SRS    | Must     |
| SRS-IOT-006  | Device authentication with X.509         | SRS    | Must     |
| FR-IOT-007   | Device health monitoring                 | BRD    | Must     |
| SEC-IOT-001  | End-to-end encryption for IoT data       | SRS    | Must     |
| PERF-IOT-001 | Message latency < 100ms                  | SRS    | Must     |

---

## 6. Day-by-Day Development Plan

### Day 651 (Day 1): EMQX Broker Deployment & Topic Design

#### Dev 1 (Backend Lead) - 8 hours

**Task 1.1: Design MQTT Topic Hierarchy (4h)**

```python
# odoo/addons/smart_dairy_iot/models/iot_topic_hierarchy.py
"""
MQTT Topic Hierarchy for Smart Dairy IoT Platform

Root Structure:
    dairy/{farm_id}/{device_type}/{device_id}/{message_type}

Examples:
    dairy/farm001/milk_meters/MM001/data         - Sensor data
    dairy/farm001/milk_meters/MM001/status       - Device status
    dairy/farm001/milk_meters/MM001/commands     - Commands to device
    dairy/farm001/milk_meters/MM001/config       - Configuration
    dairy/farm001/milk_meters/MM001/alerts       - Device alerts

Device Types:
    - milk_meters    : Milk production meters
    - temperature    : Temperature sensors
    - collars        : Activity collars
    - feeders        : Feed dispensers
    - gates          : Automated gates
    - waterers       : Water dispensers
    - environmental  : Environmental sensors

System Topics:
    dairy/system/health              - Platform health
    dairy/system/alerts              - System-wide alerts
    dairy/{farm_id}/aggregated/#     - Aggregated data

Wildcards:
    dairy/+/milk_meters/+/data       - All milk meter data
    dairy/farm001/#                  - All farm001 messages
"""

from odoo import models, fields, api
from odoo.exceptions import ValidationError
import re

class IoTTopicHierarchy(models.Model):
    _name = 'iot.topic.hierarchy'
    _description = 'MQTT Topic Hierarchy Definition'
    _order = 'sequence, name'

    name = fields.Char(string='Topic Name', required=True)
    code = fields.Char(string='Topic Code', required=True)
    sequence = fields.Integer(string='Sequence', default=10)

    topic_pattern = fields.Char(
        string='Topic Pattern',
        required=True,
        help='MQTT topic pattern with placeholders: {farm_id}, {device_type}, {device_id}'
    )

    device_type = fields.Selection([
        ('milk_meter', 'Milk Meter'),
        ('temperature', 'Temperature Sensor'),
        ('collar', 'Activity Collar'),
        ('feeder', 'Feed Dispenser'),
        ('gate', 'Automated Gate'),
        ('waterer', 'Water Dispenser'),
        ('environmental', 'Environmental Sensor'),
        ('system', 'System'),
    ], string='Device Type', required=True)

    message_type = fields.Selection([
        ('data', 'Sensor Data'),
        ('status', 'Device Status'),
        ('command', 'Command'),
        ('config', 'Configuration'),
        ('alert', 'Alert'),
        ('aggregated', 'Aggregated Data'),
    ], string='Message Type', required=True)

    qos_level = fields.Selection([
        ('0', 'QoS 0 - At most once'),
        ('1', 'QoS 1 - At least once'),
        ('2', 'QoS 2 - Exactly once'),
    ], string='QoS Level', default='1', required=True)

    retain = fields.Boolean(
        string='Retain Message',
        default=False,
        help='Last message retained for new subscribers'
    )

    description = fields.Text(string='Description')

    payload_schema = fields.Text(
        string='Payload JSON Schema',
        help='Expected JSON schema for messages on this topic'
    )

    is_active = fields.Boolean(string='Active', default=True)

    _sql_constraints = [
        ('code_unique', 'UNIQUE(code)', 'Topic code must be unique!'),
    ]

    @api.constrains('topic_pattern')
    def _check_topic_pattern(self):
        """Validate topic pattern format"""
        valid_placeholders = ['{farm_id}', '{device_type}', '{device_id}']
        for record in self:
            # Check for valid MQTT topic characters
            pattern = record.topic_pattern
            cleaned = pattern
            for ph in valid_placeholders:
                cleaned = cleaned.replace(ph, 'placeholder')
            if not re.match(r'^[a-zA-Z0-9_/+#]+$', cleaned):
                raise ValidationError(
                    f'Invalid topic pattern. Use only alphanumeric, _, /, +, # characters.'
                )

    def generate_topic(self, farm_id=None, device_id=None):
        """Generate actual topic from pattern"""
        self.ensure_one()
        topic = self.topic_pattern
        if farm_id:
            topic = topic.replace('{farm_id}', str(farm_id))
        if device_id:
            topic = topic.replace('{device_id}', str(device_id))
        topic = topic.replace('{device_type}', self._get_device_type_path())
        return topic

    def _get_device_type_path(self):
        """Convert device type to path segment"""
        type_map = {
            'milk_meter': 'milk_meters',
            'temperature': 'temperature',
            'collar': 'collars',
            'feeder': 'feeders',
            'gate': 'gates',
            'waterer': 'waterers',
            'environmental': 'environmental',
            'system': 'system',
        }
        return type_map.get(self.device_type, 'unknown')


class IoTTopicSchema(models.Model):
    _name = 'iot.topic.schema'
    _description = 'MQTT Message Payload Schema'

    name = fields.Char(string='Schema Name', required=True)
    topic_hierarchy_id = fields.Many2one(
        'iot.topic.hierarchy',
        string='Topic Hierarchy',
        required=True,
        ondelete='cascade'
    )
    version = fields.Char(string='Schema Version', default='1.0')
    json_schema = fields.Text(string='JSON Schema', required=True)
    example_payload = fields.Text(string='Example Payload')
    is_active = fields.Boolean(string='Active', default=True)
```

**Task 1.2: Create Topic Schema Definitions (2h)**

```python
# odoo/addons/smart_dairy_iot/data/iot_topic_schemas.py
"""
JSON Schema definitions for IoT message payloads
"""

MILK_METER_DATA_SCHEMA = {
    "$schema": "https://json-schema.org/draft/2020-12/schema",
    "type": "object",
    "required": ["device_id", "timestamp", "animal_id", "yield_liters"],
    "properties": {
        "device_id": {"type": "string", "pattern": "^MM[0-9]{3,6}$"},
        "timestamp": {"type": "string", "format": "date-time"},
        "session_id": {"type": "string"},
        "animal_id": {"type": "string"},
        "rfid_tag": {"type": "string", "pattern": "^[A-F0-9]{16}$"},
        "yield_liters": {"type": "number", "minimum": 0, "maximum": 50},
        "flow_rate": {"type": "number", "minimum": 0},
        "duration_seconds": {"type": "integer", "minimum": 0},
        "quality": {
            "type": "object",
            "properties": {
                "fat_percentage": {"type": "number", "minimum": 0, "maximum": 10},
                "protein_percentage": {"type": "number", "minimum": 0, "maximum": 10},
                "lactose_percentage": {"type": "number", "minimum": 0, "maximum": 10},
                "somatic_cell_count": {"type": "integer", "minimum": 0},
                "temperature_celsius": {"type": "number", "minimum": 0, "maximum": 50},
                "conductivity": {"type": "number", "minimum": 0}
            }
        },
        "equipment": {
            "type": "object",
            "properties": {
                "vacuum_pressure": {"type": "number"},
                "pulsation_rate": {"type": "integer"},
                "milking_position": {"type": "string"}
            }
        }
    }
}

TEMPERATURE_SENSOR_SCHEMA = {
    "$schema": "https://json-schema.org/draft/2020-12/schema",
    "type": "object",
    "required": ["device_id", "timestamp", "temperature"],
    "properties": {
        "device_id": {"type": "string", "pattern": "^TS[0-9]{3,6}$"},
        "timestamp": {"type": "string", "format": "date-time"},
        "temperature": {"type": "number", "minimum": -50, "maximum": 100},
        "humidity": {"type": "number", "minimum": 0, "maximum": 100},
        "location": {"type": "string"},
        "location_type": {
            "type": "string",
            "enum": ["bulk_tank", "cold_storage", "transport", "barn", "processing"]
        },
        "battery_level": {"type": "number", "minimum": 0, "maximum": 100},
        "signal_strength": {"type": "integer", "minimum": -120, "maximum": 0}
    }
}

ACTIVITY_COLLAR_SCHEMA = {
    "$schema": "https://json-schema.org/draft/2020-12/schema",
    "type": "object",
    "required": ["device_id", "timestamp", "animal_id"],
    "properties": {
        "device_id": {"type": "string", "pattern": "^AC[0-9]{3,6}$"},
        "timestamp": {"type": "string", "format": "date-time"},
        "animal_id": {"type": "string"},
        "activity_level": {"type": "integer", "minimum": 0, "maximum": 100},
        "rumination_minutes": {"type": "integer", "minimum": 0},
        "lying_time_minutes": {"type": "integer", "minimum": 0},
        "standing_time_minutes": {"type": "integer", "minimum": 0},
        "steps": {"type": "integer", "minimum": 0},
        "location": {
            "type": "object",
            "properties": {
                "latitude": {"type": "number"},
                "longitude": {"type": "number"},
                "zone": {"type": "string"}
            }
        },
        "temperature": {"type": "number"},
        "heart_rate": {"type": "integer", "minimum": 0},
        "battery_level": {"type": "number", "minimum": 0, "maximum": 100}
    }
}

FEED_DISPENSER_SCHEMA = {
    "$schema": "https://json-schema.org/draft/2020-12/schema",
    "type": "object",
    "required": ["device_id", "timestamp"],
    "properties": {
        "device_id": {"type": "string", "pattern": "^FD[0-9]{3,6}$"},
        "timestamp": {"type": "string", "format": "date-time"},
        "animal_id": {"type": "string"},
        "rfid_tag": {"type": "string"},
        "feed_type": {"type": "string"},
        "quantity_kg": {"type": "number", "minimum": 0},
        "duration_seconds": {"type": "integer", "minimum": 0},
        "bin_level_percent": {"type": "number", "minimum": 0, "maximum": 100},
        "status": {
            "type": "string",
            "enum": ["idle", "dispensing", "error", "maintenance"]
        }
    }
}

DEVICE_STATUS_SCHEMA = {
    "$schema": "https://json-schema.org/draft/2020-12/schema",
    "type": "object",
    "required": ["device_id", "timestamp", "status"],
    "properties": {
        "device_id": {"type": "string"},
        "timestamp": {"type": "string", "format": "date-time"},
        "status": {
            "type": "string",
            "enum": ["online", "offline", "error", "maintenance", "calibrating"]
        },
        "battery_level": {"type": "number", "minimum": 0, "maximum": 100},
        "signal_strength": {"type": "integer"},
        "firmware_version": {"type": "string"},
        "uptime_seconds": {"type": "integer", "minimum": 0},
        "error_code": {"type": "string"},
        "error_message": {"type": "string"},
        "last_calibration": {"type": "string", "format": "date-time"},
        "next_maintenance": {"type": "string", "format": "date"}
    }
}
```

**Task 1.3: Create Odoo IoT Device Base Model (2h)**

```python
# odoo/addons/smart_dairy_iot/models/iot_device.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
import secrets
import hashlib
from datetime import datetime, timedelta

class IoTDevice(models.Model):
    _name = 'iot.device'
    _description = 'IoT Device Registry'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'name'

    name = fields.Char(string='Device Name', required=True, tracking=True)

    device_id = fields.Char(
        string='Device ID',
        required=True,
        copy=False,
        index=True,
        tracking=True,
        help='Unique identifier for the device (e.g., MM001, TS001)'
    )

    device_type = fields.Selection([
        ('milk_meter', 'Milk Meter'),
        ('temperature', 'Temperature Sensor'),
        ('collar', 'Activity Collar'),
        ('feeder', 'Feed Dispenser'),
        ('gate', 'Automated Gate'),
        ('waterer', 'Water Dispenser'),
        ('environmental', 'Environmental Sensor'),
    ], string='Device Type', required=True, tracking=True)

    # Farm Location
    farm_id = fields.Many2one(
        'farm.location',
        string='Farm',
        required=True,
        tracking=True
    )

    location_id = fields.Many2one(
        'farm.pen',
        string='Pen/Location',
        tracking=True
    )

    location_description = fields.Char(string='Physical Location Description')

    # MQTT Credentials
    mqtt_username = fields.Char(
        string='MQTT Username',
        required=True,
        copy=False,
        readonly=True
    )

    mqtt_password_hash = fields.Char(
        string='MQTT Password (Hashed)',
        copy=False,
        readonly=True
    )

    mqtt_client_id = fields.Char(
        string='MQTT Client ID',
        copy=False,
        readonly=True
    )

    # X.509 Certificate
    certificate_cn = fields.Char(string='Certificate CN')
    certificate_expiry = fields.Date(string='Certificate Expiry')
    certificate_status = fields.Selection([
        ('valid', 'Valid'),
        ('expiring', 'Expiring Soon'),
        ('expired', 'Expired'),
        ('revoked', 'Revoked'),
    ], string='Certificate Status', compute='_compute_certificate_status', store=True)

    # Device Information
    manufacturer = fields.Char(string='Manufacturer')
    model = fields.Char(string='Model')
    serial_number = fields.Char(string='Serial Number')
    firmware_version = fields.Char(string='Firmware Version', tracking=True)
    hardware_version = fields.Char(string='Hardware Version')

    # Network Configuration
    mac_address = fields.Char(string='MAC Address')
    ip_address = fields.Char(string='IP Address')

    # GPS Coordinates
    latitude = fields.Float(string='Latitude', digits=(10, 7))
    longitude = fields.Float(string='Longitude', digits=(10, 7))

    # Status
    state = fields.Selection([
        ('provisioning', 'Provisioning'),
        ('active', 'Active'),
        ('inactive', 'Inactive'),
        ('maintenance', 'Maintenance'),
        ('decommissioned', 'Decommissioned'),
    ], string='Status', default='provisioning', tracking=True)

    is_online = fields.Boolean(
        string='Is Online',
        compute='_compute_is_online',
        store=False
    )

    last_seen = fields.Datetime(string='Last Seen')
    last_message_timestamp = fields.Datetime(string='Last Message')

    # Topics
    data_topic = fields.Char(string='Data Topic', compute='_compute_topics', store=True)
    status_topic = fields.Char(string='Status Topic', compute='_compute_topics', store=True)
    command_topic = fields.Char(string='Command Topic', compute='_compute_topics', store=True)
    config_topic = fields.Char(string='Config Topic', compute='_compute_topics', store=True)

    # Statistics
    messages_received = fields.Integer(string='Messages Received', default=0)
    messages_sent = fields.Integer(string='Messages Sent', default=0)
    error_count = fields.Integer(string='Error Count', default=0)

    # Calibration
    calibration_date = fields.Date(string='Last Calibration Date')
    next_calibration_date = fields.Date(string='Next Calibration Date')
    calibration_status = fields.Selection([
        ('valid', 'Valid'),
        ('due', 'Calibration Due'),
        ('overdue', 'Calibration Overdue'),
    ], string='Calibration Status', compute='_compute_calibration_status', store=True)

    # Configuration JSON
    config_json = fields.Text(string='Device Configuration (JSON)')

    # Notes
    notes = fields.Text(string='Notes')

    _sql_constraints = [
        ('device_id_unique', 'UNIQUE(device_id)', 'Device ID must be unique!'),
        ('mac_address_unique', 'UNIQUE(mac_address)', 'MAC Address must be unique!'),
    ]

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            # Generate MQTT credentials
            if not vals.get('mqtt_username'):
                device_type_prefix = vals.get('device_type', 'dev')[:2].upper()
                vals['mqtt_username'] = f"{device_type_prefix}_{vals.get('device_id', 'unknown')}"

            if not vals.get('mqtt_client_id'):
                vals['mqtt_client_id'] = f"sd_{vals.get('device_id', secrets.token_hex(4))}"

            # Generate and hash password
            plain_password = secrets.token_urlsafe(32)
            vals['mqtt_password_hash'] = hashlib.sha256(plain_password.encode()).hexdigest()

        records = super().create(vals_list)
        return records

    @api.depends('last_seen')
    def _compute_is_online(self):
        threshold = datetime.now() - timedelta(minutes=5)
        for record in self:
            record.is_online = (
                record.last_seen and
                record.last_seen >= threshold
            )

    @api.depends('device_type', 'device_id', 'farm_id', 'farm_id.code')
    def _compute_topics(self):
        device_type_map = {
            'milk_meter': 'milk_meters',
            'temperature': 'temperature',
            'collar': 'collars',
            'feeder': 'feeders',
            'gate': 'gates',
            'waterer': 'waterers',
            'environmental': 'environmental',
        }
        for record in self:
            farm_code = record.farm_id.code if record.farm_id else 'unknown'
            device_type_path = device_type_map.get(record.device_type, 'unknown')
            base = f"dairy/{farm_code}/{device_type_path}/{record.device_id}"

            record.data_topic = f"{base}/data"
            record.status_topic = f"{base}/status"
            record.command_topic = f"{base}/commands"
            record.config_topic = f"{base}/config"

    @api.depends('certificate_expiry')
    def _compute_certificate_status(self):
        today = fields.Date.today()
        for record in self:
            if not record.certificate_expiry:
                record.certificate_status = False
            elif record.certificate_expiry < today:
                record.certificate_status = 'expired'
            elif record.certificate_expiry < today + timedelta(days=30):
                record.certificate_status = 'expiring'
            else:
                record.certificate_status = 'valid'

    @api.depends('next_calibration_date')
    def _compute_calibration_status(self):
        today = fields.Date.today()
        for record in self:
            if not record.next_calibration_date:
                record.calibration_status = 'valid'
            elif record.next_calibration_date < today:
                record.calibration_status = 'overdue'
            elif record.next_calibration_date < today + timedelta(days=7):
                record.calibration_status = 'due'
            else:
                record.calibration_status = 'valid'

    def action_provision(self):
        """Provision device - generate credentials and activate"""
        self.ensure_one()
        if self.state != 'provisioning':
            raise ValidationError('Device must be in provisioning state')

        # Generate new credentials
        plain_password = secrets.token_urlsafe(32)
        self.write({
            'mqtt_password_hash': hashlib.sha256(plain_password.encode()).hexdigest(),
            'state': 'active',
        })

        # Return credentials for display (one-time)
        return {
            'type': 'ir.actions.act_window',
            'name': 'Device Credentials',
            'res_model': 'iot.device.credentials.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {
                'default_device_id': self.id,
                'default_mqtt_username': self.mqtt_username,
                'default_mqtt_password': plain_password,
                'default_mqtt_client_id': self.mqtt_client_id,
            }
        }

    def action_decommission(self):
        """Decommission device"""
        self.ensure_one()
        self.write({'state': 'decommissioned'})

    def action_set_maintenance(self):
        """Set device to maintenance mode"""
        self.ensure_one()
        self.write({'state': 'maintenance'})

    def action_reactivate(self):
        """Reactivate device"""
        self.ensure_one()
        if self.state in ('inactive', 'maintenance'):
            self.write({'state': 'active'})

    def update_last_seen(self):
        """Update last seen timestamp (called from MQTT handler)"""
        self.write({
            'last_seen': fields.Datetime.now(),
            'messages_received': self.messages_received + 1,
        })
```

#### Dev 2 (Full-Stack) - 8 hours

**Task 2.1: EMQX Docker Deployment (4h)**

```yaml
# docker/emqx/docker-compose.yml
version: '3.8'

services:
  emqx1:
    image: emqx/emqx:5.4.0
    container_name: emqx1
    hostname: emqx1
    environment:
      - EMQX_NAME=emqx1
      - EMQX_HOST=emqx1
      - EMQX_CLUSTER__DISCOVERY_STRATEGY=static
      - EMQX_CLUSTER__STATIC__SEEDS=[emqx1,emqx2]
      - EMQX_DASHBOARD__DEFAULT_USERNAME=admin
      - EMQX_DASHBOARD__DEFAULT_PASSWORD=${EMQX_ADMIN_PASSWORD}
      - EMQX_LISTENERS__TCP__DEFAULT__MAX_CONNECTIONS=10000
      - EMQX_LISTENERS__SSL__DEFAULT__SSL_OPTIONS__CACERTFILE=/opt/emqx/etc/certs/ca.crt
      - EMQX_LISTENERS__SSL__DEFAULT__SSL_OPTIONS__CERTFILE=/opt/emqx/etc/certs/server.crt
      - EMQX_LISTENERS__SSL__DEFAULT__SSL_OPTIONS__KEYFILE=/opt/emqx/etc/certs/server.key
    volumes:
      - ./config/emqx.conf:/opt/emqx/etc/emqx.conf
      - ./certs:/opt/emqx/etc/certs
      - emqx1_data:/opt/emqx/data
      - emqx1_log:/opt/emqx/log
    ports:
      - "1883:1883"    # MQTT
      - "8883:8883"    # MQTT/SSL
      - "8083:8083"    # WebSocket
      - "8084:8084"    # WebSocket/SSL
      - "18083:18083"  # Dashboard
    networks:
      - emqx_network
    healthcheck:
      test: ["CMD", "emqx", "ping"]
      interval: 30s
      timeout: 10s
      retries: 5

  emqx2:
    image: emqx/emqx:5.4.0
    container_name: emqx2
    hostname: emqx2
    environment:
      - EMQX_NAME=emqx2
      - EMQX_HOST=emqx2
      - EMQX_CLUSTER__DISCOVERY_STRATEGY=static
      - EMQX_CLUSTER__STATIC__SEEDS=[emqx1,emqx2]
      - EMQX_DASHBOARD__DEFAULT_USERNAME=admin
      - EMQX_DASHBOARD__DEFAULT_PASSWORD=${EMQX_ADMIN_PASSWORD}
    volumes:
      - ./config/emqx.conf:/opt/emqx/etc/emqx.conf
      - ./certs:/opt/emqx/etc/certs
      - emqx2_data:/opt/emqx/data
      - emqx2_log:/opt/emqx/log
    networks:
      - emqx_network
    depends_on:
      - emqx1

  # PostgreSQL for device authentication
  postgres_auth:
    image: postgres:16
    container_name: emqx_postgres_auth
    environment:
      - POSTGRES_USER=emqx
      - POSTGRES_PASSWORD=${POSTGRES_PASSWORD}
      - POSTGRES_DB=emqx_auth
    volumes:
      - ./init-scripts:/docker-entrypoint-initdb.d
      - postgres_auth_data:/var/lib/postgresql/data
    networks:
      - emqx_network

networks:
  emqx_network:
    driver: bridge

volumes:
  emqx1_data:
  emqx1_log:
  emqx2_data:
  emqx2_log:
  postgres_auth_data:
```

**Task 2.2: EMQX Configuration (2h)**

```hocon
# docker/emqx/config/emqx.conf
## Smart Dairy IoT - EMQX Configuration

## Cluster Configuration
cluster {
    name = smart_dairy_emqx
    discovery_strategy = static
    static {
        seeds = ["emqx1", "emqx2"]
    }
}

## MQTT Listeners
listeners.tcp.default {
    bind = "0.0.0.0:1883"
    max_connections = 10000
    max_conn_rate = "1000/s"
}

listeners.ssl.default {
    bind = "0.0.0.0:8883"
    max_connections = 10000
    ssl_options {
        cacertfile = "/opt/emqx/etc/certs/ca.crt"
        certfile = "/opt/emqx/etc/certs/server.crt"
        keyfile = "/opt/emqx/etc/certs/server.key"
        verify = verify_peer
        fail_if_no_peer_cert = false
    }
}

## WebSocket Listeners
listeners.ws.default {
    bind = "0.0.0.0:8083"
    max_connections = 5000
}

listeners.wss.default {
    bind = "0.0.0.0:8084"
    max_connections = 5000
    ssl_options {
        cacertfile = "/opt/emqx/etc/certs/ca.crt"
        certfile = "/opt/emqx/etc/certs/server.crt"
        keyfile = "/opt/emqx/etc/certs/server.key"
    }
}

## Authentication - PostgreSQL
authentication = [
    {
        backend = postgresql
        mechanism = password_based
        server = "postgres_auth:5432"
        database = "emqx_auth"
        username = "emqx"
        password = "${POSTGRES_PASSWORD}"
        query = "SELECT password_hash FROM mqtt_user WHERE username = ${username} AND is_active = true LIMIT 1"
        password_hash_algorithm {
            name = sha256
            salt_position = disable
        }
    }
]

## Authorization - PostgreSQL ACL
authorization {
    sources = [
        {
            type = postgresql
            server = "postgres_auth:5432"
            database = "emqx_auth"
            username = "emqx"
            password = "${POSTGRES_PASSWORD}"
            query = "SELECT permission, action, topic FROM mqtt_acl WHERE username = ${username}"
        }
    ]
    no_match = deny
    deny_action = disconnect
    cache {
        enable = true
        max_size = 32
        ttl = "1m"
    }
}

## Message Retention
retainer {
    enable = true
    msg_expiry_interval = "1h"
    max_payload_size = "1MB"
    flow_control {
        batch_read_number = 100
        batch_deliver_number = 100
    }
    backend {
        type = built_in_database
        storage_type = disc
        max_retained_messages = 100000
    }
}

## Rate Limiting
limiter {
    client {
        rate = "100/s"
        burst = 0
    }
    connection {
        rate = "1000/s"
        burst = 0
    }
}

## Monitoring
prometheus {
    push_gateway_server = ""
    interval = "15s"
    headers {}
}

## Logging
log {
    console_handler {
        enable = true
        level = info
        time_offset = "+06:00"
    }
    file_handlers.default {
        enable = true
        level = info
        file = "/opt/emqx/log/emqx.log"
        rotation_count = 10
        rotation_size = "50MB"
    }
}

## Dashboard
dashboard {
    listeners.http {
        bind = "0.0.0.0:18083"
    }
}
```

**Task 2.3: PostgreSQL Auth Schema (2h)**

```sql
-- docker/emqx/init-scripts/01-auth-schema.sql
-- EMQX Authentication and Authorization Schema

-- Users table for device authentication
CREATE TABLE IF NOT EXISTS mqtt_user (
    id SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(128) NOT NULL,
    device_id VARCHAR(100),
    device_type VARCHAR(50),
    farm_id VARCHAR(50),
    is_active BOOLEAN DEFAULT true,
    is_superuser BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP,
    CONSTRAINT idx_mqtt_user_username UNIQUE (username)
);

-- ACL table for authorization
CREATE TABLE IF NOT EXISTS mqtt_acl (
    id SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    permission VARCHAR(10) NOT NULL CHECK (permission IN ('allow', 'deny')),
    action VARCHAR(20) NOT NULL CHECK (action IN ('publish', 'subscribe', 'all')),
    topic VARCHAR(500) NOT NULL,
    qos VARCHAR(10) DEFAULT '0,1,2',
    retain BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Index for faster lookups
CREATE INDEX IF NOT EXISTS idx_mqtt_acl_username ON mqtt_acl(username);
CREATE INDEX IF NOT EXISTS idx_mqtt_user_device ON mqtt_user(device_id);

-- Function to update timestamp
CREATE OR REPLACE FUNCTION update_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger for updated_at
CREATE TRIGGER mqtt_user_updated_at
    BEFORE UPDATE ON mqtt_user
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at();

-- Insert default admin user
INSERT INTO mqtt_user (username, password_hash, is_superuser)
VALUES ('admin', '${ADMIN_PASSWORD_HASH}', true)
ON CONFLICT (username) DO NOTHING;

-- Insert default system user for backend
INSERT INTO mqtt_user (username, password_hash, device_type, is_superuser)
VALUES ('backend_service', '${BACKEND_PASSWORD_HASH}', 'system', true)
ON CONFLICT (username) DO NOTHING;

-- Default ACL for backend service (full access)
INSERT INTO mqtt_acl (username, permission, action, topic)
VALUES
    ('backend_service', 'allow', 'all', 'dairy/#'),
    ('backend_service', 'allow', 'all', '$SYS/#');

-- Template ACL entries for device types
-- These will be created dynamically when devices are provisioned

-- View for active devices
CREATE OR REPLACE VIEW v_active_devices AS
SELECT
    u.username,
    u.device_id,
    u.device_type,
    u.farm_id,
    u.last_login,
    COUNT(a.id) as acl_rules
FROM mqtt_user u
LEFT JOIN mqtt_acl a ON u.username = a.username
WHERE u.is_active = true
GROUP BY u.id;
```

#### Dev 3 (Frontend Lead) - 8 hours

**Task 3.1: IoT Dashboard Wireframes & Planning (4h)**

- Design wireframes for IoT device management interface
- Plan OWL component architecture for device views
- Define design tokens for IoT status indicators
- Create mockups for device list, detail, and monitoring views

**Task 3.2: OWL Device Management Component Foundation (4h)**

```javascript
/** @odoo-module */
// odoo/addons/smart_dairy_iot/static/src/js/iot_device_list.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { registry } from "@web/core/registry";
import { useService } from "@web/core/utils/hooks";

export class IoTDeviceList extends Component {
    static template = "smart_dairy_iot.DeviceList";
    static props = {
        farmId: { type: Number, optional: true },
        deviceType: { type: String, optional: true },
    };

    setup() {
        this.orm = useService("orm");
        this.notification = useService("notification");

        this.state = useState({
            devices: [],
            loading: true,
            filter: {
                status: 'all',
                type: this.props.deviceType || 'all',
                farm: this.props.farmId || null,
                search: '',
            },
            stats: {
                total: 0,
                online: 0,
                offline: 0,
                maintenance: 0,
            }
        });

        onWillStart(async () => {
            await this.loadDevices();
        });
    }

    async loadDevices() {
        this.state.loading = true;
        try {
            const domain = this.buildDomain();
            const devices = await this.orm.searchRead(
                "iot.device",
                domain,
                [
                    "name", "device_id", "device_type", "state",
                    "is_online", "last_seen", "farm_id", "firmware_version",
                    "messages_received", "error_count", "calibration_status"
                ],
                { order: "is_online DESC, name ASC" }
            );

            this.state.devices = devices;
            this.calculateStats();
        } catch (error) {
            this.notification.add("Failed to load devices", { type: "danger" });
            console.error("Error loading devices:", error);
        } finally {
            this.state.loading = false;
        }
    }

    buildDomain() {
        const domain = [];

        if (this.state.filter.status !== 'all') {
            if (this.state.filter.status === 'online') {
                domain.push(['is_online', '=', true]);
            } else if (this.state.filter.status === 'offline') {
                domain.push(['is_online', '=', false]);
            } else {
                domain.push(['state', '=', this.state.filter.status]);
            }
        }

        if (this.state.filter.type !== 'all') {
            domain.push(['device_type', '=', this.state.filter.type]);
        }

        if (this.state.filter.farm) {
            domain.push(['farm_id', '=', this.state.filter.farm]);
        }

        if (this.state.filter.search) {
            domain.push('|', '|',
                ['name', 'ilike', this.state.filter.search],
                ['device_id', 'ilike', this.state.filter.search],
                ['serial_number', 'ilike', this.state.filter.search]
            );
        }

        return domain;
    }

    calculateStats() {
        const devices = this.state.devices;
        this.state.stats = {
            total: devices.length,
            online: devices.filter(d => d.is_online).length,
            offline: devices.filter(d => !d.is_online && d.state === 'active').length,
            maintenance: devices.filter(d => d.state === 'maintenance').length,
        };
    }

    onFilterChange(filterType, value) {
        this.state.filter[filterType] = value;
        this.loadDevices();
    }

    onSearchInput(event) {
        this.state.filter.search = event.target.value;
        // Debounce search
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => this.loadDevices(), 300);
    }

    getDeviceTypeIcon(deviceType) {
        const icons = {
            'milk_meter': 'fa-tint',
            'temperature': 'fa-thermometer-half',
            'collar': 'fa-paw',
            'feeder': 'fa-utensils',
            'gate': 'fa-door-open',
            'waterer': 'fa-water',
            'environmental': 'fa-cloud',
        };
        return icons[deviceType] || 'fa-microchip';
    }

    getStatusClass(device) {
        if (device.state === 'maintenance') return 'warning';
        if (device.state === 'decommissioned') return 'secondary';
        if (device.is_online) return 'success';
        return 'danger';
    }

    formatLastSeen(lastSeen) {
        if (!lastSeen) return 'Never';
        const date = new Date(lastSeen);
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);

        if (diffMins < 1) return 'Just now';
        if (diffMins < 60) return `${diffMins}m ago`;
        if (diffMins < 1440) return `${Math.floor(diffMins / 60)}h ago`;
        return date.toLocaleDateString();
    }

    async onDeviceClick(device) {
        // Navigate to device detail view
        this.env.services.action.doAction({
            type: 'ir.actions.act_window',
            res_model: 'iot.device',
            res_id: device.id,
            views: [[false, 'form']],
            target: 'current',
        });
    }

    async refreshDevices() {
        await this.loadDevices();
        this.notification.add("Devices refreshed", { type: "success" });
    }
}

IoTDeviceList.template = "smart_dairy_iot.DeviceList";

registry.category("actions").add("iot_device_list", IoTDeviceList);
```

```xml
<!-- odoo/addons/smart_dairy_iot/static/src/xml/iot_device_list.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<templates xml:space="preserve">
    <t t-name="smart_dairy_iot.DeviceList">
        <div class="iot-device-list container-fluid p-3">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">
                            <i class="fa fa-microchip me-2"/>
                            IoT Device Management
                        </h2>
                        <button class="btn btn-primary" t-on-click="refreshDevices">
                            <i class="fa fa-refresh me-1"/>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Devices</h5>
                            <h2 class="mb-0" t-esc="state.stats.total"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Online</h5>
                            <h2 class="mb-0" t-esc="state.stats.online"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5 class="card-title">Offline</h5>
                            <h2 class="mb-0" t-esc="state.stats.offline"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h5 class="card-title">Maintenance</h5>
                            <h2 class="mb-0" t-esc="state.stats.maintenance"/>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search devices..."
                        t-on-input="onSearchInput"
                    />
                </div>
                <div class="col-md-2">
                    <select class="form-select" t-on-change="(e) => this.onFilterChange('status', e.target.value)">
                        <option value="all">All Status</option>
                        <option value="online">Online</option>
                        <option value="offline">Offline</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" t-on-change="(e) => this.onFilterChange('type', e.target.value)">
                        <option value="all">All Types</option>
                        <option value="milk_meter">Milk Meters</option>
                        <option value="temperature">Temperature</option>
                        <option value="collar">Activity Collars</option>
                        <option value="feeder">Feed Dispensers</option>
                    </select>
                </div>
            </div>

            <!-- Device Grid -->
            <div class="row">
                <t t-if="state.loading">
                    <div class="col-12 text-center py-5">
                        <i class="fa fa-spinner fa-spin fa-3x"/>
                        <p class="mt-3">Loading devices...</p>
                    </div>
                </t>
                <t t-elif="state.devices.length === 0">
                    <div class="col-12 text-center py-5">
                        <i class="fa fa-microchip fa-3x text-muted"/>
                        <p class="mt-3 text-muted">No devices found</p>
                    </div>
                </t>
                <t t-else="">
                    <t t-foreach="state.devices" t-as="device" t-key="device.id">
                        <div class="col-md-4 col-lg-3 mb-3">
                            <div
                                class="card device-card h-100"
                                t-att-class="{'border-success': device.is_online, 'border-danger': !device.is_online}"
                                t-on-click="() => this.onDeviceClick(device)"
                                style="cursor: pointer;"
                            >
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <i t-att-class="'fa ' + getDeviceTypeIcon(device.device_type) + ' fa-2x text-primary'"/>
                                        </div>
                                        <span
                                            t-att-class="'badge bg-' + getStatusClass(device)"
                                        >
                                            <t t-if="device.is_online">Online</t>
                                            <t t-elif="device.state === 'maintenance'">Maintenance</t>
                                            <t t-else="">Offline</t>
                                        </span>
                                    </div>
                                    <h6 class="card-title" t-esc="device.name"/>
                                    <p class="card-text text-muted small mb-2">
                                        <code t-esc="device.device_id"/>
                                    </p>
                                    <div class="small text-muted">
                                        <div>
                                            <i class="fa fa-clock-o me-1"/>
                                            <t t-esc="formatLastSeen(device.last_seen)"/>
                                        </div>
                                        <div>
                                            <i class="fa fa-envelope me-1"/>
                                            <t t-esc="device.messages_received"/> messages
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </t>
                </t>
            </div>
        </div>
    </t>
</templates>
```

---

### Day 652 (Day 2): Topic Hierarchy Implementation & Device Registration API

#### Dev 1 (Backend Lead) - 8 hours

**Task 1.1: MQTT Topic Hierarchy Data Setup (3h)**

```xml
<!-- odoo/addons/smart_dairy_iot/data/iot_topic_data.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <data noupdate="1">
        <!-- Milk Meter Topics -->
        <record id="topic_milk_meter_data" model="iot.topic.hierarchy">
            <field name="name">Milk Meter Data</field>
            <field name="code">milk_meter_data</field>
            <field name="topic_pattern">dairy/{farm_id}/milk_meters/{device_id}/data</field>
            <field name="device_type">milk_meter</field>
            <field name="message_type">data</field>
            <field name="qos_level">1</field>
            <field name="retain">False</field>
            <field name="description">Real-time milk production data from milk meters</field>
        </record>

        <record id="topic_milk_meter_status" model="iot.topic.hierarchy">
            <field name="name">Milk Meter Status</field>
            <field name="code">milk_meter_status</field>
            <field name="topic_pattern">dairy/{farm_id}/milk_meters/{device_id}/status</field>
            <field name="device_type">milk_meter</field>
            <field name="message_type">status</field>
            <field name="qos_level">1</field>
            <field name="retain">True</field>
            <field name="description">Device status and health information</field>
        </record>

        <record id="topic_milk_meter_commands" model="iot.topic.hierarchy">
            <field name="name">Milk Meter Commands</field>
            <field name="code">milk_meter_commands</field>
            <field name="topic_pattern">dairy/{farm_id}/milk_meters/{device_id}/commands</field>
            <field name="device_type">milk_meter</field>
            <field name="message_type">command</field>
            <field name="qos_level">2</field>
            <field name="retain">False</field>
            <field name="description">Commands sent to milk meters</field>
        </record>

        <!-- Temperature Sensor Topics -->
        <record id="topic_temperature_data" model="iot.topic.hierarchy">
            <field name="name">Temperature Data</field>
            <field name="code">temperature_data</field>
            <field name="topic_pattern">dairy/{farm_id}/temperature/{device_id}/data</field>
            <field name="device_type">temperature</field>
            <field name="message_type">data</field>
            <field name="qos_level">1</field>
            <field name="retain">False</field>
            <field name="description">Temperature and environmental readings</field>
        </record>

        <record id="topic_temperature_status" model="iot.topic.hierarchy">
            <field name="name">Temperature Sensor Status</field>
            <field name="code">temperature_status</field>
            <field name="topic_pattern">dairy/{farm_id}/temperature/{device_id}/status</field>
            <field name="device_type">temperature</field>
            <field name="message_type">status</field>
            <field name="qos_level">1</field>
            <field name="retain">True</field>
            <field name="description">Temperature sensor status</field>
        </record>

        <!-- Activity Collar Topics -->
        <record id="topic_collar_data" model="iot.topic.hierarchy">
            <field name="name">Activity Collar Data</field>
            <field name="code">collar_data</field>
            <field name="topic_pattern">dairy/{farm_id}/collars/{device_id}/data</field>
            <field name="device_type">collar</field>
            <field name="message_type">data</field>
            <field name="qos_level">1</field>
            <field name="retain">False</field>
            <field name="description">Animal activity and health data</field>
        </record>

        <record id="topic_collar_status" model="iot.topic.hierarchy">
            <field name="name">Activity Collar Status</field>
            <field name="code">collar_status</field>
            <field name="topic_pattern">dairy/{farm_id}/collars/{device_id}/status</field>
            <field name="device_type">collar</field>
            <field name="message_type">status</field>
            <field name="qos_level">1</field>
            <field name="retain">True</field>
            <field name="description">Activity collar battery and connectivity status</field>
        </record>

        <!-- Feed Dispenser Topics -->
        <record id="topic_feeder_data" model="iot.topic.hierarchy">
            <field name="name">Feed Dispenser Data</field>
            <field name="code">feeder_data</field>
            <field name="topic_pattern">dairy/{farm_id}/feeders/{device_id}/data</field>
            <field name="device_type">feeder</field>
            <field name="message_type">data</field>
            <field name="qos_level">1</field>
            <field name="retain">False</field>
            <field name="description">Feed dispensing events and consumption data</field>
        </record>

        <record id="topic_feeder_commands" model="iot.topic.hierarchy">
            <field name="name">Feed Dispenser Commands</field>
            <field name="code">feeder_commands</field>
            <field name="topic_pattern">dairy/{farm_id}/feeders/{device_id}/commands</field>
            <field name="device_type">feeder</field>
            <field name="message_type">command</field>
            <field name="qos_level">2</field>
            <field name="retain">False</field>
            <field name="description">Feed dispenser control commands</field>
        </record>

        <!-- System Topics -->
        <record id="topic_system_health" model="iot.topic.hierarchy">
            <field name="name">System Health</field>
            <field name="code">system_health</field>
            <field name="topic_pattern">dairy/system/health</field>
            <field name="device_type">system</field>
            <field name="message_type">status</field>
            <field name="qos_level">1</field>
            <field name="retain">True</field>
            <field name="description">Overall IoT platform health status</field>
        </record>

        <record id="topic_system_alerts" model="iot.topic.hierarchy">
            <field name="name">System Alerts</field>
            <field name="code">system_alerts</field>
            <field name="topic_pattern">dairy/system/alerts</field>
            <field name="device_type">system</field>
            <field name="message_type">alert</field>
            <field name="qos_level">2</field>
            <field name="retain">False</field>
            <field name="description">Platform-wide alerts and notifications</field>
        </record>
    </data>
</odoo>
```

**Task 1.2: MQTT Message Handler Service (3h)**

```python
# odoo/addons/smart_dairy_iot/services/mqtt_handler.py
import paho.mqtt.client as mqtt
import json
import logging
import threading
from datetime import datetime
from odoo import api, registry, SUPERUSER_ID

_logger = logging.getLogger(__name__)

class SmartDairyMQTTHandler:
    """
    MQTT Message Handler for Smart Dairy IoT Platform
    Handles incoming messages and routes them to appropriate processors
    """

    def __init__(self, db_name, broker_host, broker_port=1883,
                 username=None, password=None, use_tls=False):
        self.db_name = db_name
        self.broker_host = broker_host
        self.broker_port = broker_port
        self.username = username
        self.password = password
        self.use_tls = use_tls

        self.client = None
        self.is_connected = False
        self._message_handlers = {}
        self._lock = threading.Lock()

        # Register default handlers
        self._register_default_handlers()

    def _register_default_handlers(self):
        """Register handlers for different device types"""
        self._message_handlers = {
            'milk_meters': self._handle_milk_meter_message,
            'temperature': self._handle_temperature_message,
            'collars': self._handle_collar_message,
            'feeders': self._handle_feeder_message,
        }

    def connect(self):
        """Connect to MQTT broker"""
        try:
            self.client = mqtt.Client(
                client_id=f"smart_dairy_backend_{datetime.now().timestamp()}",
                clean_session=False,
                protocol=mqtt.MQTTv311
            )

            # Set callbacks
            self.client.on_connect = self._on_connect
            self.client.on_disconnect = self._on_disconnect
            self.client.on_message = self._on_message

            # Authentication
            if self.username and self.password:
                self.client.username_pw_set(self.username, self.password)

            # TLS
            if self.use_tls:
                self.client.tls_set()

            # Connect
            self.client.connect(self.broker_host, self.broker_port, keepalive=60)
            self.client.loop_start()

            _logger.info(f"MQTT client connecting to {self.broker_host}:{self.broker_port}")

        except Exception as e:
            _logger.error(f"Failed to connect to MQTT broker: {str(e)}")
            raise

    def disconnect(self):
        """Disconnect from MQTT broker"""
        if self.client:
            self.client.loop_stop()
            self.client.disconnect()
            _logger.info("MQTT client disconnected")

    def _on_connect(self, client, userdata, flags, rc):
        """Callback when connected to broker"""
        if rc == 0:
            _logger.info("Connected to MQTT broker successfully")
            self.is_connected = True

            # Subscribe to all data topics
            subscriptions = [
                ("dairy/+/milk_meters/+/data", 1),
                ("dairy/+/milk_meters/+/status", 1),
                ("dairy/+/temperature/+/data", 1),
                ("dairy/+/temperature/+/status", 1),
                ("dairy/+/collars/+/data", 1),
                ("dairy/+/collars/+/status", 1),
                ("dairy/+/feeders/+/data", 1),
                ("dairy/+/feeders/+/status", 1),
            ]

            for topic, qos in subscriptions:
                client.subscribe(topic, qos=qos)
                _logger.info(f"Subscribed to {topic}")
        else:
            _logger.error(f"Failed to connect to MQTT broker. RC: {rc}")
            self.is_connected = False

    def _on_disconnect(self, client, userdata, rc):
        """Callback when disconnected from broker"""
        _logger.warning(f"Disconnected from MQTT broker. RC: {rc}")
        self.is_connected = False

    def _on_message(self, client, userdata, msg):
        """Callback when message received"""
        try:
            topic = msg.topic
            payload = msg.payload.decode('utf-8')

            _logger.debug(f"Received message on {topic}")

            # Parse topic: dairy/{farm_id}/{device_type}/{device_id}/{message_type}
            parts = topic.split('/')
            if len(parts) < 5:
                _logger.warning(f"Invalid topic format: {topic}")
                return

            farm_id = parts[1]
            device_type = parts[2]
            device_id = parts[3]
            message_type = parts[4]

            # Parse JSON payload
            try:
                data = json.loads(payload)
            except json.JSONDecodeError:
                _logger.error(f"Invalid JSON payload on {topic}")
                return

            # Route to appropriate handler
            handler = self._message_handlers.get(device_type)
            if handler:
                handler(farm_id, device_id, message_type, data)
            else:
                _logger.warning(f"No handler for device type: {device_type}")

        except Exception as e:
            _logger.error(f"Error processing MQTT message: {str(e)}")

    def _handle_milk_meter_message(self, farm_id, device_id, message_type, data):
        """Handle milk meter messages"""
        with self._lock:
            try:
                db_registry = registry(self.db_name)
                with db_registry.cursor() as cr:
                    env = api.Environment(cr, SUPERUSER_ID, {})

                    if message_type == 'data':
                        # Process milk production data
                        env['milk.production.realtime'].sudo().process_mqtt_message(
                            device_id, data
                        )
                    elif message_type == 'status':
                        # Update device status
                        self._update_device_status(env, device_id, data)

                    cr.commit()

            except Exception as e:
                _logger.error(f"Error handling milk meter message: {str(e)}")

    def _handle_temperature_message(self, farm_id, device_id, message_type, data):
        """Handle temperature sensor messages"""
        with self._lock:
            try:
                db_registry = registry(self.db_name)
                with db_registry.cursor() as cr:
                    env = api.Environment(cr, SUPERUSER_ID, {})

                    if message_type == 'data':
                        env['iot.temperature.reading'].sudo().process_mqtt_message(
                            device_id, data
                        )
                    elif message_type == 'status':
                        self._update_device_status(env, device_id, data)

                    cr.commit()

            except Exception as e:
                _logger.error(f"Error handling temperature message: {str(e)}")

    def _handle_collar_message(self, farm_id, device_id, message_type, data):
        """Handle activity collar messages"""
        with self._lock:
            try:
                db_registry = registry(self.db_name)
                with db_registry.cursor() as cr:
                    env = api.Environment(cr, SUPERUSER_ID, {})

                    if message_type == 'data':
                        env['iot.activity.reading'].sudo().process_mqtt_message(
                            device_id, data
                        )
                    elif message_type == 'status':
                        self._update_device_status(env, device_id, data)

                    cr.commit()

            except Exception as e:
                _logger.error(f"Error handling collar message: {str(e)}")

    def _handle_feeder_message(self, farm_id, device_id, message_type, data):
        """Handle feed dispenser messages"""
        with self._lock:
            try:
                db_registry = registry(self.db_name)
                with db_registry.cursor() as cr:
                    env = api.Environment(cr, SUPERUSER_ID, {})

                    if message_type == 'data':
                        env['iot.feeding.event'].sudo().process_mqtt_message(
                            device_id, data
                        )
                    elif message_type == 'status':
                        self._update_device_status(env, device_id, data)

                    cr.commit()

            except Exception as e:
                _logger.error(f"Error handling feeder message: {str(e)}")

    def _update_device_status(self, env, device_id, data):
        """Update device status in Odoo"""
        device = env['iot.device'].sudo().search([
            ('device_id', '=', device_id)
        ], limit=1)

        if device:
            update_vals = {
                'last_seen': datetime.now(),
            }

            if 'firmware_version' in data:
                update_vals['firmware_version'] = data['firmware_version']

            device.write(update_vals)
            device.update_last_seen()
        else:
            _logger.warning(f"Device not found: {device_id}")

    def publish(self, topic, payload, qos=1, retain=False):
        """Publish message to topic"""
        if not self.is_connected:
            _logger.warning("MQTT client not connected")
            return False

        if isinstance(payload, dict):
            payload = json.dumps(payload)

        result = self.client.publish(topic, payload, qos=qos, retain=retain)
        return result.rc == mqtt.MQTT_ERR_SUCCESS
```

**Task 1.3: ACL Configuration Service (2h)**

```python
# odoo/addons/smart_dairy_iot/services/acl_manager.py
import psycopg2
import logging
from odoo import api, models

_logger = logging.getLogger(__name__)

class IoTACLManager(models.AbstractModel):
    _name = 'iot.acl.manager'
    _description = 'IoT ACL Manager'

    @api.model
    def create_device_acl(self, device):
        """Create ACL entries for a device"""
        username = device.mqtt_username
        farm_code = device.farm_id.code if device.farm_id else 'unknown'
        device_type = self._get_device_type_path(device.device_type)
        device_id = device.device_id

        # Build topic patterns
        base_topic = f"dairy/{farm_code}/{device_type}/{device_id}"

        acl_rules = [
            # Device can publish to its data topic
            ('allow', 'publish', f"{base_topic}/data"),
            # Device can publish to its status topic
            ('allow', 'publish', f"{base_topic}/status"),
            # Device can subscribe to commands
            ('allow', 'subscribe', f"{base_topic}/commands"),
            # Device can subscribe to config updates
            ('allow', 'subscribe', f"{base_topic}/config"),
        ]

        # Insert ACL rules to PostgreSQL
        self._insert_acl_rules(username, acl_rules)

        _logger.info(f"Created ACL for device {device_id}")

    @api.model
    def remove_device_acl(self, device):
        """Remove ACL entries for a device"""
        username = device.mqtt_username
        self._delete_acl_rules(username)
        _logger.info(f"Removed ACL for device {device.device_id}")

    def _get_device_type_path(self, device_type):
        """Convert device type to topic path segment"""
        type_map = {
            'milk_meter': 'milk_meters',
            'temperature': 'temperature',
            'collar': 'collars',
            'feeder': 'feeders',
            'gate': 'gates',
            'waterer': 'waterers',
            'environmental': 'environmental',
        }
        return type_map.get(device_type, 'unknown')

    def _insert_acl_rules(self, username, rules):
        """Insert ACL rules into PostgreSQL auth database"""
        # Get connection parameters from system parameters
        ICP = self.env['ir.config_parameter'].sudo()
        db_host = ICP.get_param('iot.mqtt_auth_db_host', 'localhost')
        db_port = int(ICP.get_param('iot.mqtt_auth_db_port', '5432'))
        db_name = ICP.get_param('iot.mqtt_auth_db_name', 'emqx_auth')
        db_user = ICP.get_param('iot.mqtt_auth_db_user', 'emqx')
        db_password = ICP.get_param('iot.mqtt_auth_db_password', '')

        try:
            conn = psycopg2.connect(
                host=db_host,
                port=db_port,
                database=db_name,
                user=db_user,
                password=db_password
            )
            cursor = conn.cursor()

            for permission, action, topic in rules:
                cursor.execute("""
                    INSERT INTO mqtt_acl (username, permission, action, topic)
                    VALUES (%s, %s, %s, %s)
                    ON CONFLICT DO NOTHING
                """, (username, permission, action, topic))

            conn.commit()
            cursor.close()
            conn.close()

        except Exception as e:
            _logger.error(f"Failed to insert ACL rules: {str(e)}")
            raise

    def _delete_acl_rules(self, username):
        """Delete ACL rules for a username"""
        ICP = self.env['ir.config_parameter'].sudo()
        db_host = ICP.get_param('iot.mqtt_auth_db_host', 'localhost')
        db_port = int(ICP.get_param('iot.mqtt_auth_db_port', '5432'))
        db_name = ICP.get_param('iot.mqtt_auth_db_name', 'emqx_auth')
        db_user = ICP.get_param('iot.mqtt_auth_db_user', 'emqx')
        db_password = ICP.get_param('iot.mqtt_auth_db_password', '')

        try:
            conn = psycopg2.connect(
                host=db_host,
                port=db_port,
                database=db_name,
                user=db_user,
                password=db_password
            )
            cursor = conn.cursor()

            cursor.execute("""
                DELETE FROM mqtt_acl WHERE username = %s
            """, (username,))

            conn.commit()
            cursor.close()
            conn.close()

        except Exception as e:
            _logger.error(f"Failed to delete ACL rules: {str(e)}")
            raise
```

#### Dev 2 (Full-Stack) - 8 hours

**Task 2.1: Device Registration REST API (4h)**

```python
# odoo/addons/smart_dairy_iot/controllers/iot_api.py
from odoo import http
from odoo.http import request, Response
import json
import logging

_logger = logging.getLogger(__name__)

class IoTDeviceAPI(http.Controller):

    @http.route('/api/v1/iot/devices', type='json', auth='api_key', methods=['GET'])
    def get_devices(self, **kwargs):
        """Get list of IoT devices"""
        try:
            domain = []

            # Filter by farm
            if kwargs.get('farm_id'):
                domain.append(('farm_id', '=', int(kwargs['farm_id'])))

            # Filter by device type
            if kwargs.get('device_type'):
                domain.append(('device_type', '=', kwargs['device_type']))

            # Filter by status
            if kwargs.get('state'):
                domain.append(('state', '=', kwargs['state']))

            # Filter by online status
            if kwargs.get('is_online') is not None:
                domain.append(('is_online', '=', kwargs['is_online']))

            devices = request.env['iot.device'].sudo().search(domain)

            return {
                'success': True,
                'count': len(devices),
                'devices': [self._device_to_dict(d) for d in devices]
            }

        except Exception as e:
            _logger.error(f"Error getting devices: {str(e)}")
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/iot/devices/<string:device_id>', type='json', auth='api_key', methods=['GET'])
    def get_device(self, device_id, **kwargs):
        """Get single device by ID"""
        try:
            device = request.env['iot.device'].sudo().search([
                ('device_id', '=', device_id)
            ], limit=1)

            if not device:
                return {'success': False, 'error': 'Device not found'}

            return {
                'success': True,
                'device': self._device_to_dict(device, detailed=True)
            }

        except Exception as e:
            _logger.error(f"Error getting device: {str(e)}")
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/iot/devices/register', type='json', auth='api_key', methods=['POST'])
    def register_device(self, **kwargs):
        """Register a new IoT device"""
        try:
            required_fields = ['name', 'device_id', 'device_type', 'farm_id']
            for field in required_fields:
                if not kwargs.get(field):
                    return {'success': False, 'error': f'Missing required field: {field}'}

            # Check if device already exists
            existing = request.env['iot.device'].sudo().search([
                ('device_id', '=', kwargs['device_id'])
            ], limit=1)

            if existing:
                return {'success': False, 'error': 'Device ID already exists'}

            # Create device
            device = request.env['iot.device'].sudo().create({
                'name': kwargs['name'],
                'device_id': kwargs['device_id'],
                'device_type': kwargs['device_type'],
                'farm_id': kwargs['farm_id'],
                'manufacturer': kwargs.get('manufacturer'),
                'model': kwargs.get('model'),
                'serial_number': kwargs.get('serial_number'),
                'mac_address': kwargs.get('mac_address'),
                'location_description': kwargs.get('location_description'),
            })

            # Create ACL entries
            request.env['iot.acl.manager'].sudo().create_device_acl(device)

            return {
                'success': True,
                'device': self._device_to_dict(device),
                'mqtt_credentials': {
                    'username': device.mqtt_username,
                    'client_id': device.mqtt_client_id,
                    'topics': {
                        'data': device.data_topic,
                        'status': device.status_topic,
                        'commands': device.command_topic,
                        'config': device.config_topic,
                    }
                }
            }

        except Exception as e:
            _logger.error(f"Error registering device: {str(e)}")
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/iot/devices/<string:device_id>/provision', type='json', auth='api_key', methods=['POST'])
    def provision_device(self, device_id, **kwargs):
        """Provision device and return credentials"""
        try:
            device = request.env['iot.device'].sudo().search([
                ('device_id', '=', device_id)
            ], limit=1)

            if not device:
                return {'success': False, 'error': 'Device not found'}

            # Generate new credentials
            import secrets
            import hashlib

            plain_password = secrets.token_urlsafe(32)
            device.write({
                'mqtt_password_hash': hashlib.sha256(plain_password.encode()).hexdigest(),
                'state': 'active',
            })

            # Update ACL
            request.env['iot.acl.manager'].sudo().create_device_acl(device)

            return {
                'success': True,
                'credentials': {
                    'username': device.mqtt_username,
                    'password': plain_password,  # Only returned once
                    'client_id': device.mqtt_client_id,
                    'broker_host': request.env['ir.config_parameter'].sudo().get_param('iot.mqtt_broker_host'),
                    'broker_port': int(request.env['ir.config_parameter'].sudo().get_param('iot.mqtt_broker_port', '1883')),
                    'use_tls': request.env['ir.config_parameter'].sudo().get_param('iot.mqtt_use_tls', 'false') == 'true',
                },
                'topics': {
                    'data': device.data_topic,
                    'status': device.status_topic,
                    'commands': device.command_topic,
                    'config': device.config_topic,
                }
            }

        except Exception as e:
            _logger.error(f"Error provisioning device: {str(e)}")
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/iot/devices/<string:device_id>/heartbeat', type='json', auth='none', methods=['POST'])
    def device_heartbeat(self, device_id, **kwargs):
        """Handle device heartbeat - lightweight endpoint"""
        try:
            device = request.env['iot.device'].sudo().search([
                ('device_id', '=', device_id)
            ], limit=1)

            if device:
                device.update_last_seen()
                return {'success': True, 'timestamp': str(device.last_seen)}

            return {'success': False, 'error': 'Device not found'}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/iot/devices/<string:device_id>/config', type='json', auth='api_key', methods=['GET', 'PUT'])
    def device_config(self, device_id, **kwargs):
        """Get or update device configuration"""
        try:
            device = request.env['iot.device'].sudo().search([
                ('device_id', '=', device_id)
            ], limit=1)

            if not device:
                return {'success': False, 'error': 'Device not found'}

            if request.httprequest.method == 'GET':
                config = json.loads(device.config_json) if device.config_json else {}
                return {'success': True, 'config': config}

            else:  # PUT
                config = kwargs.get('config', {})
                device.write({'config_json': json.dumps(config)})

                # Publish config to device via MQTT
                mqtt_handler = request.env['iot.mqtt.service'].sudo().get_handler()
                if mqtt_handler:
                    mqtt_handler.publish(
                        device.config_topic,
                        config,
                        qos=2,
                        retain=True
                    )

                return {'success': True, 'message': 'Configuration updated'}

        except Exception as e:
            _logger.error(f"Error with device config: {str(e)}")
            return {'success': False, 'error': str(e)}

    def _device_to_dict(self, device, detailed=False):
        """Convert device record to dictionary"""
        data = {
            'id': device.id,
            'device_id': device.device_id,
            'name': device.name,
            'device_type': device.device_type,
            'state': device.state,
            'is_online': device.is_online,
            'last_seen': str(device.last_seen) if device.last_seen else None,
            'farm_id': device.farm_id.id if device.farm_id else None,
            'farm_name': device.farm_id.name if device.farm_id else None,
        }

        if detailed:
            data.update({
                'manufacturer': device.manufacturer,
                'model': device.model,
                'serial_number': device.serial_number,
                'firmware_version': device.firmware_version,
                'mac_address': device.mac_address,
                'ip_address': device.ip_address,
                'latitude': device.latitude,
                'longitude': device.longitude,
                'location_description': device.location_description,
                'calibration_status': device.calibration_status,
                'calibration_date': str(device.calibration_date) if device.calibration_date else None,
                'next_calibration_date': str(device.next_calibration_date) if device.next_calibration_date else None,
                'messages_received': device.messages_received,
                'messages_sent': device.messages_sent,
                'error_count': device.error_count,
                'topics': {
                    'data': device.data_topic,
                    'status': device.status_topic,
                    'commands': device.command_topic,
                    'config': device.config_topic,
                }
            })

        return data
```

**Task 2.2: TLS Certificate Generation Scripts (2h)**

```bash
#!/bin/bash
# scripts/generate_mqtt_certs.sh
# Generate TLS certificates for MQTT broker

set -e

CERT_DIR="./docker/emqx/certs"
CA_SUBJECT="/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy/OU=IoT/CN=Smart Dairy CA"
SERVER_SUBJECT="/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy/OU=IoT/CN=mqtt.smartdairy.com.bd"
DAYS_VALID=3650

# Create certificate directory
mkdir -p $CERT_DIR

echo "Generating CA certificate..."
# Generate CA private key
openssl genrsa -out $CERT_DIR/ca.key 4096

# Generate CA certificate
openssl req -new -x509 -days $DAYS_VALID -key $CERT_DIR/ca.key \
    -out $CERT_DIR/ca.crt -subj "$CA_SUBJECT"

echo "Generating server certificate..."
# Generate server private key
openssl genrsa -out $CERT_DIR/server.key 2048

# Generate server CSR
openssl req -new -key $CERT_DIR/server.key \
    -out $CERT_DIR/server.csr -subj "$SERVER_SUBJECT"

# Sign server certificate with CA
openssl x509 -req -days $DAYS_VALID -in $CERT_DIR/server.csr \
    -CA $CERT_DIR/ca.crt -CAkey $CERT_DIR/ca.key -CAcreateserial \
    -out $CERT_DIR/server.crt

# Clean up CSR
rm $CERT_DIR/server.csr

# Set permissions
chmod 644 $CERT_DIR/ca.crt $CERT_DIR/server.crt
chmod 600 $CERT_DIR/ca.key $CERT_DIR/server.key

echo "Certificates generated successfully in $CERT_DIR"
echo "CA Certificate: $CERT_DIR/ca.crt"
echo "Server Certificate: $CERT_DIR/server.crt"
echo "Server Key: $CERT_DIR/server.key"
```

**Task 2.3: Device Authentication Integration (2h)**

```python
# odoo/addons/smart_dairy_iot/services/device_auth.py
import psycopg2
import hashlib
import logging
from odoo import api, models

_logger = logging.getLogger(__name__)

class IoTDeviceAuth(models.AbstractModel):
    _name = 'iot.device.auth'
    _description = 'IoT Device Authentication Manager'

    @api.model
    def create_mqtt_user(self, device):
        """Create MQTT user in auth database"""
        ICP = self.env['ir.config_parameter'].sudo()
        db_host = ICP.get_param('iot.mqtt_auth_db_host', 'localhost')
        db_port = int(ICP.get_param('iot.mqtt_auth_db_port', '5432'))
        db_name = ICP.get_param('iot.mqtt_auth_db_name', 'emqx_auth')
        db_user = ICP.get_param('iot.mqtt_auth_db_user', 'emqx')
        db_password = ICP.get_param('iot.mqtt_auth_db_password', '')

        try:
            conn = psycopg2.connect(
                host=db_host,
                port=db_port,
                database=db_name,
                user=db_user,
                password=db_password
            )
            cursor = conn.cursor()

            farm_code = device.farm_id.code if device.farm_id else None

            cursor.execute("""
                INSERT INTO mqtt_user (username, password_hash, device_id, device_type, farm_id, is_active)
                VALUES (%s, %s, %s, %s, %s, %s)
                ON CONFLICT (username) DO UPDATE
                SET password_hash = EXCLUDED.password_hash,
                    is_active = EXCLUDED.is_active
            """, (
                device.mqtt_username,
                device.mqtt_password_hash,
                device.device_id,
                device.device_type,
                farm_code,
                device.state == 'active'
            ))

            conn.commit()
            cursor.close()
            conn.close()

            _logger.info(f"Created MQTT user for device {device.device_id}")

        except Exception as e:
            _logger.error(f"Failed to create MQTT user: {str(e)}")
            raise

    @api.model
    def deactivate_mqtt_user(self, device):
        """Deactivate MQTT user"""
        ICP = self.env['ir.config_parameter'].sudo()
        db_host = ICP.get_param('iot.mqtt_auth_db_host', 'localhost')
        db_port = int(ICP.get_param('iot.mqtt_auth_db_port', '5432'))
        db_name = ICP.get_param('iot.mqtt_auth_db_name', 'emqx_auth')
        db_user = ICP.get_param('iot.mqtt_auth_db_user', 'emqx')
        db_password = ICP.get_param('iot.mqtt_auth_db_password', '')

        try:
            conn = psycopg2.connect(
                host=db_host,
                port=db_port,
                database=db_name,
                user=db_user,
                password=db_password
            )
            cursor = conn.cursor()

            cursor.execute("""
                UPDATE mqtt_user SET is_active = false WHERE username = %s
            """, (device.mqtt_username,))

            conn.commit()
            cursor.close()
            conn.close()

            _logger.info(f"Deactivated MQTT user for device {device.device_id}")

        except Exception as e:
            _logger.error(f"Failed to deactivate MQTT user: {str(e)}")
            raise

    @api.model
    def rotate_password(self, device):
        """Generate new password for device"""
        import secrets

        new_password = secrets.token_urlsafe(32)
        new_hash = hashlib.sha256(new_password.encode()).hexdigest()

        device.write({'mqtt_password_hash': new_hash})

        # Update in auth database
        self.create_mqtt_user(device)

        return new_password
```

#### Dev 3 (Frontend Lead) - 8 hours

**Task 3.1: Device Status Dashboard Component (4h)**

```javascript
/** @odoo-module */
// odoo/addons/smart_dairy_iot/static/src/js/iot_status_dashboard.js

import { Component, useState, onWillStart, onMounted, onWillUnmount } from "@odoo/owl";
import { registry } from "@web/core/registry";
import { useService } from "@web/core/utils/hooks";

export class IoTStatusDashboard extends Component {
    static template = "smart_dairy_iot.StatusDashboard";

    setup() {
        this.orm = useService("orm");
        this.notification = useService("notification");

        this.state = useState({
            loading: true,
            mqttStatus: 'unknown',
            totalDevices: 0,
            onlineDevices: 0,
            offlineDevices: 0,
            errorDevices: 0,
            messagesPerMinute: 0,
            devicesByType: {},
            recentMessages: [],
            alerts: [],
        });

        this.refreshInterval = null;

        onWillStart(async () => {
            await this.loadDashboardData();
        });

        onMounted(() => {
            // Refresh every 30 seconds
            this.refreshInterval = setInterval(() => this.loadDashboardData(), 30000);
        });

        onWillUnmount(() => {
            if (this.refreshInterval) {
                clearInterval(this.refreshInterval);
            }
        });
    }

    async loadDashboardData() {
        try {
            // Load device statistics
            const devices = await this.orm.searchRead(
                "iot.device",
                [['state', '!=', 'decommissioned']],
                ["device_type", "state", "is_online", "error_count"]
            );

            // Calculate statistics
            const online = devices.filter(d => d.is_online).length;
            const offline = devices.filter(d => !d.is_online && d.state === 'active').length;
            const errors = devices.filter(d => d.error_count > 0).length;

            // Group by type
            const byType = {};
            devices.forEach(d => {
                if (!byType[d.device_type]) {
                    byType[d.device_type] = { total: 0, online: 0 };
                }
                byType[d.device_type].total++;
                if (d.is_online) byType[d.device_type].online++;
            });

            // Load recent alerts
            const alerts = await this.orm.searchRead(
                "iot.alert",
                [['state', '=', 'active']],
                ["name", "severity", "device_id", "create_date"],
                { limit: 5, order: "create_date DESC" }
            );

            this.state.totalDevices = devices.length;
            this.state.onlineDevices = online;
            this.state.offlineDevices = offline;
            this.state.errorDevices = errors;
            this.state.devicesByType = byType;
            this.state.alerts = alerts;
            this.state.loading = false;

        } catch (error) {
            console.error("Error loading dashboard:", error);
            this.notification.add("Failed to load dashboard data", { type: "danger" });
        }
    }

    get onlinePercentage() {
        if (this.state.totalDevices === 0) return 0;
        return Math.round((this.state.onlineDevices / this.state.totalDevices) * 100);
    }

    getDeviceTypeLabel(type) {
        const labels = {
            'milk_meter': 'Milk Meters',
            'temperature': 'Temperature Sensors',
            'collar': 'Activity Collars',
            'feeder': 'Feed Dispensers',
        };
        return labels[type] || type;
    }

    getSeverityClass(severity) {
        const classes = {
            'critical': 'danger',
            'high': 'warning',
            'medium': 'info',
            'low': 'secondary',
        };
        return classes[severity] || 'secondary';
    }

    formatDate(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        return date.toLocaleString();
    }
}

IoTStatusDashboard.template = "smart_dairy_iot.StatusDashboard";
registry.category("actions").add("iot_status_dashboard", IoTStatusDashboard);
```

```xml
<!-- odoo/addons/smart_dairy_iot/static/src/xml/iot_status_dashboard.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<templates xml:space="preserve">
    <t t-name="smart_dairy_iot.StatusDashboard">
        <div class="iot-status-dashboard p-4">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <h2>
                        <i class="fa fa-dashboard me-2"/>
                        IoT Platform Status
                    </h2>
                </div>
            </div>

            <t t-if="state.loading">
                <div class="text-center py-5">
                    <i class="fa fa-spinner fa-spin fa-3x"/>
                    <p class="mt-3">Loading dashboard...</p>
                </div>
            </t>

            <t t-else="">
                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body text-center">
                                <i class="fa fa-microchip fa-2x mb-2"/>
                                <h3 t-esc="state.totalDevices"/>
                                <p class="mb-0">Total Devices</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body text-center">
                                <i class="fa fa-check-circle fa-2x mb-2"/>
                                <h3 t-esc="state.onlineDevices"/>
                                <p class="mb-0">Online (<t t-esc="onlinePercentage"/>%)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white h-100">
                            <div class="card-body text-center">
                                <i class="fa fa-times-circle fa-2x mb-2"/>
                                <h3 t-esc="state.offlineDevices"/>
                                <p class="mb-0">Offline</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark h-100">
                            <div class="card-body text-center">
                                <i class="fa fa-exclamation-triangle fa-2x mb-2"/>
                                <h3 t-esc="state.errorDevices"/>
                                <p class="mb-0">With Errors</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Devices by Type -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fa fa-pie-chart me-2"/>
                                    Devices by Type
                                </h5>
                            </div>
                            <div class="card-body">
                                <t t-foreach="Object.entries(state.devicesByType)" t-as="entry" t-key="entry[0]">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span t-esc="getDeviceTypeLabel(entry[0])"/>
                                        <span>
                                            <span class="badge bg-success me-1" t-esc="entry[1].online"/>
                                            /
                                            <span class="badge bg-secondary ms-1" t-esc="entry[1].total"/>
                                        </span>
                                    </div>
                                    <div class="progress mb-3" style="height: 8px;">
                                        <div
                                            class="progress-bar bg-success"
                                            t-att-style="'width: ' + (entry[1].total > 0 ? (entry[1].online / entry[1].total * 100) : 0) + '%'"
                                        />
                                    </div>
                                </t>
                            </div>
                        </div>
                    </div>

                    <!-- Active Alerts -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fa fa-bell me-2"/>
                                    Active Alerts
                                </h5>
                            </div>
                            <div class="card-body">
                                <t t-if="state.alerts.length === 0">
                                    <p class="text-muted text-center">No active alerts</p>
                                </t>
                                <t t-else="">
                                    <t t-foreach="state.alerts" t-as="alert" t-key="alert.id">
                                        <div class="alert mb-2" t-att-class="'alert-' + getSeverityClass(alert.severity)">
                                            <div class="d-flex justify-content-between">
                                                <strong t-esc="alert.name"/>
                                                <small t-esc="formatDate(alert.create_date)"/>
                                            </div>
                                            <small class="text-muted" t-esc="alert.device_id[1]"/>
                                        </div>
                                    </t>
                                </t>
                            </div>
                        </div>
                    </div>
                </div>
            </t>
        </div>
    </t>
</templates>
```

**Task 3.2: Device Registration Form OWL Component (4h)**

```javascript
/** @odoo-module */
// odoo/addons/smart_dairy_iot/static/src/js/iot_device_form.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { registry } from "@web/core/registry";
import { useService } from "@web/core/utils/hooks";

export class IoTDeviceRegistrationForm extends Component {
    static template = "smart_dairy_iot.DeviceRegistrationForm";
    static props = {
        onSave: { type: Function, optional: true },
        onCancel: { type: Function, optional: true },
    };

    setup() {
        this.orm = useService("orm");
        this.notification = useService("notification");

        this.state = useState({
            loading: false,
            farms: [],
            formData: {
                name: '',
                device_id: '',
                device_type: 'milk_meter',
                farm_id: null,
                manufacturer: '',
                model: '',
                serial_number: '',
                mac_address: '',
                location_description: '',
            },
            errors: {},
        });

        this.deviceTypes = [
            { value: 'milk_meter', label: 'Milk Meter' },
            { value: 'temperature', label: 'Temperature Sensor' },
            { value: 'collar', label: 'Activity Collar' },
            { value: 'feeder', label: 'Feed Dispenser' },
            { value: 'gate', label: 'Automated Gate' },
            { value: 'waterer', label: 'Water Dispenser' },
            { value: 'environmental', label: 'Environmental Sensor' },
        ];

        onWillStart(async () => {
            await this.loadFarms();
        });
    }

    async loadFarms() {
        try {
            const farms = await this.orm.searchRead(
                "farm.location",
                [],
                ["id", "name", "code"]
            );
            this.state.farms = farms;
        } catch (error) {
            console.error("Error loading farms:", error);
        }
    }

    updateField(field, value) {
        this.state.formData[field] = value;
        // Clear error when field is updated
        if (this.state.errors[field]) {
            delete this.state.errors[field];
        }
    }

    validateForm() {
        const errors = {};
        const { formData } = this.state;

        if (!formData.name?.trim()) {
            errors.name = 'Device name is required';
        }

        if (!formData.device_id?.trim()) {
            errors.device_id = 'Device ID is required';
        } else if (!/^[A-Z]{2}[0-9]{3,6}$/.test(formData.device_id)) {
            errors.device_id = 'Invalid format. Use: XX### (e.g., MM001)';
        }

        if (!formData.farm_id) {
            errors.farm_id = 'Farm is required';
        }

        if (formData.mac_address && !/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/.test(formData.mac_address)) {
            errors.mac_address = 'Invalid MAC address format';
        }

        this.state.errors = errors;
        return Object.keys(errors).length === 0;
    }

    async onSubmit() {
        if (!this.validateForm()) {
            this.notification.add("Please fix the errors in the form", { type: "warning" });
            return;
        }

        this.state.loading = true;

        try {
            const device = await this.orm.create("iot.device", [this.state.formData]);

            this.notification.add("Device registered successfully", { type: "success" });

            if (this.props.onSave) {
                this.props.onSave(device);
            }

        } catch (error) {
            console.error("Error registering device:", error);
            this.notification.add("Failed to register device", { type: "danger" });
        } finally {
            this.state.loading = false;
        }
    }

    onCancel() {
        if (this.props.onCancel) {
            this.props.onCancel();
        }
    }
}

IoTDeviceRegistrationForm.template = "smart_dairy_iot.DeviceRegistrationForm";
registry.category("actions").add("iot_device_registration", IoTDeviceRegistrationForm);
```

---

### Day 653-660: Remaining Development Days

Due to document length constraints, the remaining days (653-660) follow the same detailed structure with:

**Day 653:** IoT Gateway Framework, EMQX Webhooks, Device Status Dashboard
**Day 654:** Odoo IoT Device Model Extensions, TLS Certificate Management, Device Registration Form
**Day 655:** MQTT Client Service for Odoo, Rate Limiting, Device Detail View
**Day 656:** Message Validation Service, Prometheus Monitoring, Mobile IoT Status (Flutter)
**Day 657:** Device Heartbeat Handler, ELK Logging, Device Search and Filtering
**Day 658:** IoT Configuration Wizard, Load Balancing Setup, Admin Configuration Panels
**Day 659:** Unit Tests (pytest), Integration Tests, UI Component Tests
**Day 660:** Documentation, Bug Fixes, Performance/Security Testing, Demo Preparation

---

## 7. Technical Specifications

### 7.1 Database Schema

```sql
-- iot.device table
CREATE TABLE iot_device (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    device_id VARCHAR(100) NOT NULL UNIQUE,
    device_type VARCHAR(50) NOT NULL,
    farm_id INTEGER REFERENCES farm_location(id),
    location_id INTEGER REFERENCES farm_pen(id),
    mqtt_username VARCHAR(255) NOT NULL,
    mqtt_password_hash VARCHAR(128),
    mqtt_client_id VARCHAR(255),
    state VARCHAR(50) DEFAULT 'provisioning',
    manufacturer VARCHAR(255),
    model VARCHAR(255),
    serial_number VARCHAR(255),
    firmware_version VARCHAR(50),
    mac_address VARCHAR(17),
    ip_address VARCHAR(45),
    latitude NUMERIC(10, 7),
    longitude NUMERIC(10, 7),
    last_seen TIMESTAMP,
    messages_received INTEGER DEFAULT 0,
    messages_sent INTEGER DEFAULT 0,
    error_count INTEGER DEFAULT 0,
    calibration_date DATE,
    next_calibration_date DATE,
    config_json TEXT,
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_iot_device_device_id ON iot_device(device_id);
CREATE INDEX idx_iot_device_farm ON iot_device(farm_id);
CREATE INDEX idx_iot_device_type ON iot_device(device_type);
CREATE INDEX idx_iot_device_state ON iot_device(state);
```

### 7.2 API Endpoints

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| GET | /api/v1/iot/devices | List all devices |
| GET | /api/v1/iot/devices/{id} | Get device details |
| POST | /api/v1/iot/devices/register | Register new device |
| POST | /api/v1/iot/devices/{id}/provision | Provision device |
| POST | /api/v1/iot/devices/{id}/heartbeat | Device heartbeat |
| GET/PUT | /api/v1/iot/devices/{id}/config | Get/Update config |
| DELETE | /api/v1/iot/devices/{id} | Decommission device |

---

## 8. Testing & Validation

### 8.1 Unit Tests

```python
# tests/test_iot_device.py
import pytest
from odoo.tests import TransactionCase

class TestIoTDevice(TransactionCase):

    def setUp(self):
        super().setUp()
        self.farm = self.env['farm.location'].create({
            'name': 'Test Farm',
            'code': 'TF001',
        })

    def test_device_creation(self):
        """Test basic device creation"""
        device = self.env['iot.device'].create({
            'name': 'Test Milk Meter',
            'device_id': 'MM001',
            'device_type': 'milk_meter',
            'farm_id': self.farm.id,
        })

        self.assertTrue(device.id)
        self.assertEqual(device.state, 'provisioning')
        self.assertTrue(device.mqtt_username)
        self.assertTrue(device.mqtt_client_id)

    def test_topic_generation(self):
        """Test MQTT topic generation"""
        device = self.env['iot.device'].create({
            'name': 'Test Device',
            'device_id': 'MM002',
            'device_type': 'milk_meter',
            'farm_id': self.farm.id,
        })

        self.assertEqual(
            device.data_topic,
            f"dairy/{self.farm.code}/milk_meters/MM002/data"
        )

    def test_device_provisioning(self):
        """Test device provisioning workflow"""
        device = self.env['iot.device'].create({
            'name': 'Test Device',
            'device_id': 'MM003',
            'device_type': 'milk_meter',
            'farm_id': self.farm.id,
        })

        device.action_provision()

        self.assertEqual(device.state, 'active')
```

### 8.2 Integration Tests

```python
# tests/test_mqtt_integration.py
import pytest
import paho.mqtt.client as mqtt
import json
import time

class TestMQTTIntegration:

    @pytest.fixture
    def mqtt_client(self):
        client = mqtt.Client()
        client.connect("localhost", 1883, 60)
        client.loop_start()
        yield client
        client.loop_stop()
        client.disconnect()

    def test_publish_subscribe(self, mqtt_client):
        """Test basic pub/sub functionality"""
        received = []

        def on_message(client, userdata, msg):
            received.append(json.loads(msg.payload))

        mqtt_client.on_message = on_message
        mqtt_client.subscribe("dairy/test/milk_meters/MM001/data")

        time.sleep(0.5)

        test_payload = {
            "device_id": "MM001",
            "timestamp": "2026-02-04T10:00:00Z",
            "yield_liters": 15.5,
        }

        mqtt_client.publish(
            "dairy/test/milk_meters/MM001/data",
            json.dumps(test_payload)
        )

        time.sleep(0.5)

        assert len(received) == 1
        assert received[0]["yield_liters"] == 15.5
```

---

## 9. Risk & Mitigation

| Risk | Impact | Probability | Mitigation |
| ---- | ------ | ----------- | ---------- |
| EMQX cluster instability | High | Low | Implement health checks, automatic failover |
| Device authentication failures | Medium | Medium | Retry logic, credential rotation |
| Message loss during peak load | High | Low | Message persistence, QoS 1/2 |
| Topic ACL misconfiguration | High | Low | Automated ACL generation, testing |

---

## 10. Milestone Sign-off Checklist

- [ ] EMQX cluster deployed and operational
- [ ] Topic hierarchy implemented and documented
- [ ] Device authentication working (X.509 + credentials)
- [ ] ACL configuration complete
- [ ] MQTT-to-Odoo bridge service operational
- [ ] IoT Gateway framework created
- [ ] Message validation service working
- [ ] Prometheus/Grafana monitoring configured
- [ ] OWL Device Management UI functional
- [ ] Unit tests passing (>80% coverage)
- [ ] Integration tests passing
- [ ] Security review completed
- [ ] Documentation updated
- [ ] Demo prepared and delivered

---

**Document End**

*Last Updated: 2026-02-04*
*Milestone 91: MQTT Infrastructure & IoT Core*
