# Milestone 26: IoT Sensor Integration
## Smart Dairy Digital Smart Portal + ERP Implementation
### Days 251-260 | Phase 3 Part B: Advanced Features & Optimization

---

## Document Control

| Attribute | Value |
|-----------|-------|
| **Document ID** | SD-P3-MS26-001 |
| **Version** | 1.0 |
| **Last Updated** | 2025-01-15 |
| **Status** | Implementation Ready |
| **Owner** | Dev 1 (Backend Lead) |
| **Reviewers** | Dev 2, Dev 3, Project Manager |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Implement comprehensive IoT sensor integration enabling real-time cold chain monitoring, milk collection tracking, and automated alerting for the Smart Dairy platform.**

This milestone establishes the IoT infrastructure that connects physical dairy operations with the digital ERP system, providing real-time visibility into critical parameters like temperature, milk volume, and equipment status.

### 1.2 Objectives

| # | Objective | Priority | Success Measure |
|---|-----------|----------|-----------------|
| 1 | Configure MQTT broker for IoT communication | Critical | Broker accepting 1000+ concurrent connections |
| 2 | Implement TimescaleDB for time-series data | Critical | Hypertables storing sensor readings efficiently |
| 3 | Integrate temperature sensors for cold chain | Critical | Real-time temp monitoring with <30s latency |
| 4 | Connect milk meters for volume tracking | High | Accurate volume readings within 0.5% error |
| 5 | Build real-time alert engine | Critical | Alerts triggered within 60s of threshold breach |
| 6 | Create WebSocket live monitoring dashboard | High | Dashboard updating in real-time |
| 7 | Implement historical analytics | Medium | 90-day trend analysis available |
| 8 | Develop mobile IoT alerts | High | Push notifications for critical alerts |
| 9 | Configure device management system | Medium | Register/manage 500+ devices |
| 10 | Establish data retention policies | Medium | Automated archival and cleanup |

### 1.3 Key Deliverables

| Deliverable | Owner | Day | Acceptance Criteria |
|-------------|-------|-----|---------------------|
| MQTT Broker Configuration | Dev 1 | 251-252 | Mosquitto/AWS IoT operational |
| TimescaleDB Schema | Dev 1 | 252-253 | Hypertables with compression |
| IoT Consumer Service | Dev 1 | 253-254 | Processing 10K messages/min |
| Temperature Sensor Module | Dev 1 | 254-255 | Cold chain monitoring live |
| Milk Meter Integration | Dev 1 | 255-256 | Volume tracking operational |
| Alert Engine | Dev 2 | 254-256 | Multi-level alerts working |
| WebSocket Dashboard | Dev 2 | 256-258 | Real-time visualization |
| Mobile IoT Alerts | Dev 3 | 256-258 | Push notifications functional |
| Historical Analytics | Dev 2 | 258-259 | Trend charts and reports |
| Device Management UI | Dev 3 | 259-260 | Admin device portal |
| Integration Testing | All | 260 | End-to-end verification |

### 1.4 Prerequisites

| Prerequisite | Source | Status |
|--------------|--------|--------|
| PostgreSQL 16 operational | Phase 1 | Required |
| Redis cache configured | Phase 1 | Required |
| API Gateway functional | Phase 2 | Required |
| WebSocket infrastructure | Phase 2 | Required |
| Mobile app foundation | Milestone 22 | Required |
| IoT hardware procurement | External | Required |

### 1.5 Success Criteria

- [ ] MQTT broker handling 1000+ concurrent device connections
- [ ] Sensor data latency < 30 seconds from device to dashboard
- [ ] Alert generation within 60 seconds of threshold breach
- [ ] TimescaleDB compression achieving 10:1 ratio
- [ ] 99.9% message delivery reliability
- [ ] Mobile push notifications delivered within 5 seconds
- [ ] Dashboard rendering 100+ sensors without performance degradation

---

## 2. Requirements Traceability Matrix

### 2.1 BRD Requirements Mapping

| BRD Req ID | Requirement Description | Implementation | Day |
|------------|------------------------|----------------|-----|
| I-001 | Real-time sensor data collection | MQTT consumer service | 253-254 |
| I-002 | Temperature monitoring for cold chain | Temp sensor module | 254-255 |
| I-003 | Milk volume tracking | Milk meter integration | 255-256 |
| I-004 | Alert and notification system | Alert engine | 254-256 |
| I-005 | Historical data storage | TimescaleDB schema | 252-253 |
| I-006 | Dashboard visualization | WebSocket dashboard | 256-258 |
| I-007 | Mobile alerts | Push notifications | 256-258 |
| I-008 | Device management | Device registry | 259-260 |
| I-009 | Data retention policies | Archival automation | 259 |
| I-010 | Integration with ERP | Odoo IoT module | 254-260 |

### 2.2 SRS Requirements Mapping

| SRS Req ID | Technical Requirement | Component | Owner |
|------------|----------------------|-----------|-------|
| SRS-IOT-001 | MQTT 3.1.1 protocol support | Mosquitto broker | Dev 1 |
| SRS-IOT-002 | Time-series data at 1s intervals | TimescaleDB | Dev 1 |
| SRS-IOT-003 | QoS Level 1 message delivery | MQTT config | Dev 1 |
| SRS-IOT-004 | TLS 1.3 encryption for IoT | SSL certificates | Dev 1 |
| SRS-IOT-005 | Device authentication via certificates | X.509 certs | Dev 1 |
| SRS-IOT-006 | WebSocket for live updates | FastAPI WS | Dev 2 |
| SRS-IOT-007 | Configurable alert thresholds | Alert engine | Dev 2 |
| SRS-IOT-008 | Firebase push notifications | Mobile integration | Dev 3 |
| SRS-IOT-009 | 90-day hot data retention | TimescaleDB policies | Dev 1 |
| SRS-IOT-010 | Grafana dashboards | Monitoring stack | Dev 2 |

---

## 3. Technical Architecture

### 3.1 IoT Data Flow Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           IoT SENSOR LAYER                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    │
│  │ Temperature  │  │  Milk Meter  │  │   pH Sensor  │  │ Flow Sensor  │    │
│  │   Sensors    │  │   Sensors    │  │              │  │              │    │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘    │
│         │                 │                 │                 │            │
│         └────────────┬────┴─────────────────┴────────┬────────┘            │
│                      │                               │                      │
│              ┌───────▼───────┐               ┌───────▼───────┐             │
│              │  IoT Gateway  │               │  IoT Gateway  │             │
│              │   (Farm 1)    │               │   (Farm 2)    │             │
│              └───────┬───────┘               └───────┬───────┘             │
└──────────────────────┼───────────────────────────────┼──────────────────────┘
                       │           MQTT/TLS            │
                       └───────────────┬───────────────┘
                                       ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                          MESSAGE BROKER LAYER                                │
├─────────────────────────────────────────────────────────────────────────────┤
│                    ┌─────────────────────────┐                              │
│                    │    Mosquitto Broker     │                              │
│                    │    (MQTT 3.1.1/TLS)     │                              │
│                    │   Port 8883 (Secure)    │                              │
│                    └────────────┬────────────┘                              │
└─────────────────────────────────┼────────────────────────────────────────────┘
                                  │
                                  ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                        DATA PROCESSING LAYER                                 │
├─────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                      IoT Consumer Service                            │   │
│  │                    (Python + asyncio-mqtt)                           │   │
│  └───────────┬─────────────────────┬─────────────────────┬─────────────┘   │
│              │                     │                     │                  │
│              ▼                     ▼                     ▼                  │
│  ┌───────────────────┐  ┌───────────────────┐  ┌───────────────────┐      │
│  │   TimescaleDB     │  │   Alert Engine    │  │  WebSocket Hub    │      │
│  │  (Time-Series)    │  │  (Rule Processor) │  │  (Live Updates)   │      │
│  └───────────────────┘  └─────────┬─────────┘  └───────────────────┘      │
│                                   │                                         │
│                                   ▼                                         │
│                       ┌───────────────────────┐                            │
│                       │  Notification Service │                            │
│                       │ (Email/SMS/Push/Odoo) │                            │
│                       └───────────────────────┘                            │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Technology Stack

| Component | Technology | Version | Purpose |
|-----------|------------|---------|---------|
| MQTT Broker | Mosquitto | 2.0.18 | Message broker |
| Time-Series DB | TimescaleDB | 2.13+ | Sensor data storage |
| Consumer | Python + asyncio-mqtt | 3.11+ | Message processing |
| Cache | Redis | 7.2+ | Real-time state cache |
| WebSocket | FastAPI + websockets | 0.100+ | Live dashboard updates |
| Monitoring | Prometheus + Grafana | Latest | System metrics |
| Push Notifications | Firebase Cloud Messaging | Latest | Mobile alerts |

---

## 4. Day-by-Day Implementation

### Day 251: MQTT Broker Setup & Configuration

#### Dev 1 Tasks (8 hours) - Backend Lead

**Task 1: Mosquitto Broker Installation (2h)**

```yaml
# docker-compose.iot.yml
version: '3.8'

services:
  mosquitto:
    image: eclipse-mosquitto:2.0.18
    container_name: smartdairy_mqtt
    ports:
      - "1883:1883"   # MQTT
      - "8883:8883"   # MQTT over TLS
      - "9001:9001"   # WebSocket
    volumes:
      - ./mosquitto/config:/mosquitto/config
      - ./mosquitto/data:/mosquitto/data
      - ./mosquitto/log:/mosquitto/log
      - ./mosquitto/certs:/mosquitto/certs
    restart: unless-stopped
    networks:
      - smartdairy_network

  timescaledb:
    image: timescale/timescaledb:latest-pg16
    container_name: smartdairy_timescaledb
    environment:
      POSTGRES_USER: ${TIMESCALE_USER}
      POSTGRES_PASSWORD: ${TIMESCALE_PASSWORD}
      POSTGRES_DB: smartdairy_iot
    ports:
      - "5433:5432"
    volumes:
      - timescaledb_data:/var/lib/postgresql/data
    restart: unless-stopped
    networks:
      - smartdairy_network

volumes:
  timescaledb_data:

networks:
  smartdairy_network:
    external: true
```

**Task 2: Mosquitto Configuration (2h)**

```conf
# mosquitto/config/mosquitto.conf

# =============================================================================
# Smart Dairy MQTT Broker Configuration
# =============================================================================

# Persistence
persistence true
persistence_location /mosquitto/data/

# Logging
log_dest file /mosquitto/log/mosquitto.log
log_type error
log_type warning
log_type notice
log_type information
log_timestamp true
log_timestamp_format %Y-%m-%dT%H:%M:%S

# =============================================================================
# Listener Configuration
# =============================================================================

# Plain MQTT (internal network only)
listener 1883 0.0.0.0
protocol mqtt
allow_anonymous false

# MQTT over TLS (external devices)
listener 8883 0.0.0.0
protocol mqtt
cafile /mosquitto/certs/ca.crt
certfile /mosquitto/certs/server.crt
keyfile /mosquitto/certs/server.key
require_certificate true
use_identity_as_username true
tls_version tlsv1.3

# WebSocket for dashboard
listener 9001 0.0.0.0
protocol websockets
allow_anonymous false

# =============================================================================
# Authentication & Authorization
# =============================================================================

password_file /mosquitto/config/passwd
acl_file /mosquitto/config/acl

# =============================================================================
# Performance Tuning
# =============================================================================

max_connections 2000
max_inflight_messages 100
max_queued_messages 1000
message_size_limit 10240

# Keepalive
max_keepalive 120

# =============================================================================
# Bridge Configuration (Optional - for cloud sync)
# =============================================================================

# connection aws-iot-bridge
# address your-iot-endpoint.iot.region.amazonaws.com:8883
# topic smartdairy/# out 1
# bridge_cafile /mosquitto/certs/aws-root-ca.pem
# bridge_certfile /mosquitto/certs/device.crt
# bridge_keyfile /mosquitto/certs/device.key
```

**Task 3: ACL Configuration (2h)**

```conf
# mosquitto/config/acl

# =============================================================================
# Smart Dairy MQTT Access Control List
# =============================================================================

# Pattern substitutions:
# %c = Client ID
# %u = Username

# =============================================================================
# Admin Users - Full Access
# =============================================================================
user admin
topic readwrite #

user iot_consumer
topic read smartdairy/#
topic write smartdairy/alerts/#

# =============================================================================
# Device Access Patterns
# =============================================================================

# Temperature sensors can only publish to their specific topic
pattern write smartdairy/sensors/temperature/%c/data
pattern read smartdairy/sensors/temperature/%c/config

# Milk meters
pattern write smartdairy/sensors/milkmeter/%c/data
pattern read smartdairy/sensors/milkmeter/%c/config

# pH sensors
pattern write smartdairy/sensors/ph/%c/data
pattern read smartdairy/sensors/ph/%c/config

# Flow sensors
pattern write smartdairy/sensors/flow/%c/data
pattern read smartdairy/sensors/flow/%c/config

# =============================================================================
# Gateway Access
# =============================================================================

# Gateways can publish for all connected devices
pattern write smartdairy/gateway/%c/#
pattern read smartdairy/gateway/%c/commands

# =============================================================================
# Dashboard Access (WebSocket)
# =============================================================================
user dashboard
topic read smartdairy/sensors/#
topic read smartdairy/alerts/#
topic read smartdairy/status/#

# =============================================================================
# Mobile App Access
# =============================================================================
pattern read smartdairy/user/%u/alerts
pattern read smartdairy/user/%u/notifications
```

**Task 4: SSL Certificate Generation (2h)**

```bash
#!/bin/bash
# scripts/generate_iot_certs.sh

# Smart Dairy IoT Certificate Generation Script
# Generates CA, server, and device certificates

set -e

CERT_DIR="./mosquitto/certs"
DAYS_VALID=3650
KEY_SIZE=4096

mkdir -p $CERT_DIR
cd $CERT_DIR

echo "=== Smart Dairy IoT Certificate Generation ==="

# 1. Generate CA Key and Certificate
echo "Generating CA certificate..."
openssl genrsa -out ca.key $KEY_SIZE

openssl req -new -x509 -days $DAYS_VALID -key ca.key -out ca.crt \
    -subj "/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy/OU=IoT/CN=Smart Dairy CA"

# 2. Generate Server Key and Certificate
echo "Generating server certificate..."
openssl genrsa -out server.key $KEY_SIZE

openssl req -new -key server.key -out server.csr \
    -subj "/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy/OU=IoT/CN=mqtt.smartdairy.com"

# Create server extensions file
cat > server.ext << EOF
authorityKeyIdentifier=keyid,issuer
basicConstraints=CA:FALSE
keyUsage = digitalSignature, nonRepudiation, keyEncipherment, dataEncipherment
subjectAltName = @alt_names

[alt_names]
DNS.1 = mqtt.smartdairy.com
DNS.2 = localhost
IP.1 = 127.0.0.1
EOF

openssl x509 -req -in server.csr -CA ca.crt -CAkey ca.key -CAcreateserial \
    -out server.crt -days $DAYS_VALID -extfile server.ext

# 3. Generate Device Certificate Template Script
cat > generate_device_cert.sh << 'DEVICE_SCRIPT'
#!/bin/bash
# Usage: ./generate_device_cert.sh <device_id> <device_type>
# Example: ./generate_device_cert.sh TEMP001 temperature

DEVICE_ID=$1
DEVICE_TYPE=$2

if [ -z "$DEVICE_ID" ] || [ -z "$DEVICE_TYPE" ]; then
    echo "Usage: $0 <device_id> <device_type>"
    exit 1
fi

mkdir -p devices/$DEVICE_TYPE

openssl genrsa -out devices/$DEVICE_TYPE/$DEVICE_ID.key 2048

openssl req -new -key devices/$DEVICE_TYPE/$DEVICE_ID.key \
    -out devices/$DEVICE_TYPE/$DEVICE_ID.csr \
    -subj "/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy/OU=$DEVICE_TYPE/CN=$DEVICE_ID"

openssl x509 -req -in devices/$DEVICE_TYPE/$DEVICE_ID.csr \
    -CA ca.crt -CAkey ca.key -CAcreateserial \
    -out devices/$DEVICE_TYPE/$DEVICE_ID.crt -days 365

echo "Certificate generated for device: $DEVICE_ID"
echo "Files: devices/$DEVICE_TYPE/$DEVICE_ID.{key,crt}"
DEVICE_SCRIPT

chmod +x generate_device_cert.sh

echo "=== Certificate generation complete ==="
echo "CA Certificate: $CERT_DIR/ca.crt"
echo "Server Certificate: $CERT_DIR/server.crt"
echo "Device cert script: $CERT_DIR/generate_device_cert.sh"
```

#### Dev 2 Tasks (8 hours) - Full-Stack

**Task 1: IoT Module Structure Setup (4h)**

```python
# addons/smartdairy_iot/__manifest__.py

{
    'name': 'Smart Dairy IoT Integration',
    'version': '19.0.1.0.0',
    'category': 'Manufacturing/IoT',
    'summary': 'IoT sensor integration for Smart Dairy operations',
    'description': """
        Smart Dairy IoT Integration Module
        ==================================

        Features:
        - MQTT sensor data collection
        - Real-time temperature monitoring
        - Milk meter integration
        - Alert and notification system
        - Historical data analytics
        - Device management

        Technical:
        - TimescaleDB integration
        - WebSocket live updates
        - Firebase push notifications
    """,
    'author': 'Smart Dairy Development Team',
    'website': 'https://smartdairy.com',
    'license': 'LGPL-3',
    'depends': [
        'base',
        'mail',
        'smartdairy_farm',
        'smartdairy_manufacturing',
    ],
    'data': [
        'security/iot_security.xml',
        'security/ir.model.access.csv',
        'data/iot_data.xml',
        'data/alert_templates.xml',
        'views/iot_device_views.xml',
        'views/iot_sensor_views.xml',
        'views/iot_alert_views.xml',
        'views/iot_dashboard_views.xml',
        'views/iot_menu.xml',
    ],
    'assets': {
        'web.assets_backend': [
            'smartdairy_iot/static/src/js/**/*',
            'smartdairy_iot/static/src/css/**/*',
            'smartdairy_iot/static/src/xml/**/*',
        ],
    },
    'external_dependencies': {
        'python': [
            'asyncio-mqtt',
            'psycopg2-binary',
            'aiohttp',
            'websockets',
        ],
    },
    'installable': True,
    'application': True,
    'auto_install': False,
}
```

**Task 2: IoT Device Model (4h)**

```python
# addons/smartdairy_iot/models/iot_device.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError
import secrets
import hashlib


class IoTDevice(models.Model):
    _name = 'iot.device'
    _description = 'IoT Device'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'name'

    name = fields.Char(
        string='Device Name',
        required=True,
        tracking=True,
    )
    device_id = fields.Char(
        string='Device ID',
        required=True,
        copy=False,
        readonly=True,
        default=lambda self: self._generate_device_id(),
    )
    device_type = fields.Selection([
        ('temperature', 'Temperature Sensor'),
        ('milk_meter', 'Milk Meter'),
        ('ph_sensor', 'pH Sensor'),
        ('flow_sensor', 'Flow Sensor'),
        ('humidity', 'Humidity Sensor'),
        ('pressure', 'Pressure Sensor'),
        ('gateway', 'IoT Gateway'),
    ], string='Device Type', required=True, tracking=True)

    state = fields.Selection([
        ('draft', 'Draft'),
        ('registered', 'Registered'),
        ('active', 'Active'),
        ('inactive', 'Inactive'),
        ('maintenance', 'Maintenance'),
        ('retired', 'Retired'),
    ], string='Status', default='draft', tracking=True)

    # Location
    farm_id = fields.Many2one(
        'farm.farm',
        string='Farm',
        tracking=True,
    )
    location_id = fields.Many2one(
        'stock.location',
        string='Location',
        tracking=True,
    )
    location_description = fields.Char(
        string='Location Description',
        help='Detailed location within the farm (e.g., Cooling Tank #1)',
    )

    # Technical Details
    manufacturer = fields.Char(string='Manufacturer')
    model = fields.Char(string='Model')
    serial_number = fields.Char(string='Serial Number')
    firmware_version = fields.Char(string='Firmware Version')

    # MQTT Configuration
    mqtt_topic = fields.Char(
        string='MQTT Topic',
        compute='_compute_mqtt_topic',
        store=True,
    )
    auth_token = fields.Char(
        string='Auth Token',
        copy=False,
        readonly=True,
    )
    certificate_expiry = fields.Date(
        string='Certificate Expiry',
    )

    # Thresholds and Configuration
    config_json = fields.Text(
        string='Configuration JSON',
        default='{}',
    )

    # Connectivity
    last_seen = fields.Datetime(
        string='Last Seen',
        readonly=True,
    )
    is_online = fields.Boolean(
        string='Online',
        compute='_compute_is_online',
    )
    signal_strength = fields.Integer(
        string='Signal Strength (%)',
    )
    battery_level = fields.Integer(
        string='Battery Level (%)',
    )

    # Relations
    sensor_ids = fields.One2many(
        'iot.sensor.reading',
        'device_id',
        string='Sensor Readings',
    )
    alert_ids = fields.One2many(
        'iot.alert',
        'device_id',
        string='Alerts',
    )

    # Statistics
    reading_count = fields.Integer(
        string='Total Readings',
        compute='_compute_statistics',
    )
    alert_count = fields.Integer(
        string='Active Alerts',
        compute='_compute_statistics',
    )

    _sql_constraints = [
        ('device_id_unique', 'UNIQUE(device_id)',
         'Device ID must be unique!'),
        ('serial_unique', 'UNIQUE(serial_number)',
         'Serial number must be unique!'),
    ]

    @api.model
    def _generate_device_id(self):
        """Generate unique device ID"""
        prefix = 'SD'
        random_part = secrets.token_hex(4).upper()
        return f"{prefix}-{random_part}"

    @api.depends('device_type', 'device_id')
    def _compute_mqtt_topic(self):
        for device in self:
            if device.device_type and device.device_id:
                device.mqtt_topic = f"smartdairy/sensors/{device.device_type}/{device.device_id}/data"
            else:
                device.mqtt_topic = False

    @api.depends('last_seen')
    def _compute_is_online(self):
        from datetime import timedelta
        now = fields.Datetime.now()
        for device in self:
            if device.last_seen:
                device.is_online = (now - device.last_seen) < timedelta(minutes=5)
            else:
                device.is_online = False

    def _compute_statistics(self):
        for device in self:
            device.reading_count = self.env['iot.sensor.reading'].search_count([
                ('device_id', '=', device.id)
            ])
            device.alert_count = self.env['iot.alert'].search_count([
                ('device_id', '=', device.id),
                ('state', 'in', ['new', 'acknowledged'])
            ])

    def action_register(self):
        """Register device and generate auth credentials"""
        self.ensure_one()
        if self.state != 'draft':
            raise ValidationError("Only draft devices can be registered.")

        # Generate auth token
        self.auth_token = secrets.token_urlsafe(32)
        self.state = 'registered'

        # Log registration
        self.message_post(
            body=f"Device registered with ID: {self.device_id}",
            message_type='notification'
        )

        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': 'Device Registered',
                'message': f'Device {self.name} has been registered. Auth token generated.',
                'type': 'success',
            }
        }

    def action_activate(self):
        """Activate device for data collection"""
        for device in self:
            if device.state not in ['registered', 'inactive', 'maintenance']:
                raise ValidationError(
                    f"Device {device.name} cannot be activated from {device.state} state."
                )
            device.state = 'active'
            device.message_post(body="Device activated")

    def action_deactivate(self):
        """Deactivate device"""
        for device in self:
            device.state = 'inactive'
            device.message_post(body="Device deactivated")

    def action_maintenance(self):
        """Put device in maintenance mode"""
        for device in self:
            device.state = 'maintenance'
            device.message_post(body="Device placed in maintenance mode")

    def action_view_readings(self):
        """View sensor readings for this device"""
        self.ensure_one()
        return {
            'type': 'ir.actions.act_window',
            'name': f'Readings - {self.name}',
            'res_model': 'iot.sensor.reading',
            'view_mode': 'tree,graph,pivot',
            'domain': [('device_id', '=', self.id)],
            'context': {'default_device_id': self.id},
        }

    def action_view_alerts(self):
        """View alerts for this device"""
        self.ensure_one()
        return {
            'type': 'ir.actions.act_window',
            'name': f'Alerts - {self.name}',
            'res_model': 'iot.alert',
            'view_mode': 'tree,form',
            'domain': [('device_id', '=', self.id)],
            'context': {'default_device_id': self.id},
        }
```

#### Dev 3 Tasks (8 hours) - Frontend/Mobile

**Task 1: Mobile IoT Service Setup (4h)**

```dart
// lib/features/iot/data/datasources/iot_remote_datasource.dart

import 'package:dio/dio.dart';
import 'package:injectable/injectable.dart';
import '../../../../core/network/api_client.dart';
import '../models/device_model.dart';
import '../models/sensor_reading_model.dart';
import '../models/alert_model.dart';

abstract class IoTRemoteDataSource {
  Future<List<DeviceModel>> getDevices();
  Future<DeviceModel> getDeviceDetails(String deviceId);
  Future<List<SensorReadingModel>> getDeviceReadings(
    String deviceId, {
    DateTime? startDate,
    DateTime? endDate,
    int? limit,
  });
  Future<List<AlertModel>> getAlerts({
    String? deviceId,
    String? severity,
    bool? acknowledged,
  });
  Future<void> acknowledgeAlert(String alertId);
  Future<Map<String, dynamic>> getDashboardSummary();
}

@Injectable(as: IoTRemoteDataSource)
class IoTRemoteDataSourceImpl implements IoTRemoteDataSource {
  final ApiClient _apiClient;

  IoTRemoteDataSourceImpl(this._apiClient);

  @override
  Future<List<DeviceModel>> getDevices() async {
    final response = await _apiClient.get('/api/v1/iot/devices');
    return (response.data['devices'] as List)
        .map((json) => DeviceModel.fromJson(json))
        .toList();
  }

  @override
  Future<DeviceModel> getDeviceDetails(String deviceId) async {
    final response = await _apiClient.get('/api/v1/iot/devices/$deviceId');
    return DeviceModel.fromJson(response.data);
  }

  @override
  Future<List<SensorReadingModel>> getDeviceReadings(
    String deviceId, {
    DateTime? startDate,
    DateTime? endDate,
    int? limit,
  }) async {
    final params = <String, dynamic>{};
    if (startDate != null) params['start_date'] = startDate.toIso8601String();
    if (endDate != null) params['end_date'] = endDate.toIso8601String();
    if (limit != null) params['limit'] = limit;

    final response = await _apiClient.get(
      '/api/v1/iot/devices/$deviceId/readings',
      queryParameters: params,
    );
    return (response.data['readings'] as List)
        .map((json) => SensorReadingModel.fromJson(json))
        .toList();
  }

  @override
  Future<List<AlertModel>> getAlerts({
    String? deviceId,
    String? severity,
    bool? acknowledged,
  }) async {
    final params = <String, dynamic>{};
    if (deviceId != null) params['device_id'] = deviceId;
    if (severity != null) params['severity'] = severity;
    if (acknowledged != null) params['acknowledged'] = acknowledged;

    final response = await _apiClient.get(
      '/api/v1/iot/alerts',
      queryParameters: params,
    );
    return (response.data['alerts'] as List)
        .map((json) => AlertModel.fromJson(json))
        .toList();
  }

  @override
  Future<void> acknowledgeAlert(String alertId) async {
    await _apiClient.post('/api/v1/iot/alerts/$alertId/acknowledge');
  }

  @override
  Future<Map<String, dynamic>> getDashboardSummary() async {
    final response = await _apiClient.get('/api/v1/iot/dashboard/summary');
    return response.data;
  }
}
```

**Task 2: IoT Models (4h)**

```dart
// lib/features/iot/data/models/device_model.dart

import 'package:freezed_annotation/freezed_annotation.dart';
import '../../domain/entities/device.dart';

part 'device_model.freezed.dart';
part 'device_model.g.dart';

@freezed
class DeviceModel with _$DeviceModel {
  const DeviceModel._();

  const factory DeviceModel({
    required String id,
    required String deviceId,
    required String name,
    required String deviceType,
    required String state,
    String? farmId,
    String? farmName,
    String? locationDescription,
    String? manufacturer,
    String? model,
    String? firmwareVersion,
    DateTime? lastSeen,
    @Default(false) bool isOnline,
    int? signalStrength,
    int? batteryLevel,
    double? lastValue,
    String? lastValueUnit,
    @Default(0) int alertCount,
  }) = _DeviceModel;

  factory DeviceModel.fromJson(Map<String, dynamic> json) =>
      _$DeviceModelFromJson(json);

  Device toEntity() => Device(
        id: id,
        deviceId: deviceId,
        name: name,
        deviceType: DeviceType.fromString(deviceType),
        state: DeviceState.fromString(state),
        farmId: farmId,
        farmName: farmName,
        locationDescription: locationDescription,
        manufacturer: manufacturer,
        model: model,
        firmwareVersion: firmwareVersion,
        lastSeen: lastSeen,
        isOnline: isOnline,
        signalStrength: signalStrength,
        batteryLevel: batteryLevel,
        lastValue: lastValue,
        lastValueUnit: lastValueUnit,
        alertCount: alertCount,
      );
}

// lib/features/iot/data/models/sensor_reading_model.dart

@freezed
class SensorReadingModel with _$SensorReadingModel {
  const SensorReadingModel._();

  const factory SensorReadingModel({
    required String id,
    required String deviceId,
    required DateTime timestamp,
    required double value,
    required String unit,
    String? readingType,
    Map<String, dynamic>? metadata,
  }) = _SensorReadingModel;

  factory SensorReadingModel.fromJson(Map<String, dynamic> json) =>
      _$SensorReadingModelFromJson(json);

  SensorReading toEntity() => SensorReading(
        id: id,
        deviceId: deviceId,
        timestamp: timestamp,
        value: value,
        unit: unit,
        readingType: readingType,
        metadata: metadata,
      );
}

// lib/features/iot/data/models/alert_model.dart

@freezed
class AlertModel with _$AlertModel {
  const AlertModel._();

  const factory AlertModel({
    required String id,
    required String deviceId,
    required String deviceName,
    required String alertType,
    required String severity,
    required String message,
    required String state,
    required DateTime createdAt,
    DateTime? acknowledgedAt,
    String? acknowledgedBy,
    double? triggerValue,
    double? thresholdValue,
    String? unit,
  }) = _AlertModel;

  factory AlertModel.fromJson(Map<String, dynamic> json) =>
      _$AlertModelFromJson(json);

  Alert toEntity() => Alert(
        id: id,
        deviceId: deviceId,
        deviceName: deviceName,
        alertType: AlertType.fromString(alertType),
        severity: AlertSeverity.fromString(severity),
        message: message,
        state: AlertState.fromString(state),
        createdAt: createdAt,
        acknowledgedAt: acknowledgedAt,
        acknowledgedBy: acknowledgedBy,
        triggerValue: triggerValue,
        thresholdValue: thresholdValue,
        unit: unit,
      );
}
```

#### End of Day 251 Checklist

- [ ] Mosquitto broker installed and running
- [ ] TLS configuration complete with certificates
- [ ] ACL rules configured for device access
- [ ] Certificate generation scripts created
- [ ] IoT Odoo module structure created
- [ ] IoT Device model implemented
- [ ] Mobile IoT data source setup
- [ ] Mobile models defined (Device, SensorReading, Alert)

---

### Day 252: TimescaleDB Setup & IoT Consumer Foundation

#### Dev 1 Tasks (8 hours) - Backend Lead

**Task 1: TimescaleDB Schema Design (4h)**

```sql
-- migrations/timescaledb/001_create_iot_schema.sql

-- =============================================================================
-- Smart Dairy IoT TimescaleDB Schema
-- =============================================================================

-- Enable TimescaleDB extension
CREATE EXTENSION IF NOT EXISTS timescaledb;

-- =============================================================================
-- Sensor Readings Hypertable
-- =============================================================================

CREATE TABLE IF NOT EXISTS sensor_readings (
    id BIGSERIAL,
    time TIMESTAMPTZ NOT NULL,
    device_id VARCHAR(50) NOT NULL,
    device_type VARCHAR(30) NOT NULL,
    reading_type VARCHAR(50) NOT NULL,
    value DOUBLE PRECISION NOT NULL,
    unit VARCHAR(20) NOT NULL,
    quality INTEGER DEFAULT 100,  -- Data quality score 0-100
    metadata JSONB DEFAULT '{}',
    PRIMARY KEY (id, time)
);

-- Convert to hypertable with 1-day chunks
SELECT create_hypertable(
    'sensor_readings',
    'time',
    chunk_time_interval => INTERVAL '1 day',
    if_not_exists => TRUE
);

-- Create indexes for common queries
CREATE INDEX idx_sensor_readings_device_time
    ON sensor_readings (device_id, time DESC);

CREATE INDEX idx_sensor_readings_type_time
    ON sensor_readings (device_type, reading_type, time DESC);

CREATE INDEX idx_sensor_readings_metadata
    ON sensor_readings USING GIN (metadata);

-- =============================================================================
-- Temperature Readings (Specific table for cold chain)
-- =============================================================================

CREATE TABLE IF NOT EXISTS temperature_readings (
    id BIGSERIAL,
    time TIMESTAMPTZ NOT NULL,
    device_id VARCHAR(50) NOT NULL,
    location_id INTEGER,
    temperature_celsius DOUBLE PRECISION NOT NULL,
    humidity_percent DOUBLE PRECISION,
    is_alarm BOOLEAN DEFAULT FALSE,
    alarm_type VARCHAR(30),
    PRIMARY KEY (id, time)
);

SELECT create_hypertable(
    'temperature_readings',
    'time',
    chunk_time_interval => INTERVAL '1 day',
    if_not_exists => TRUE
);

CREATE INDEX idx_temp_readings_device_time
    ON temperature_readings (device_id, time DESC);

CREATE INDEX idx_temp_readings_alarm
    ON temperature_readings (is_alarm, time DESC)
    WHERE is_alarm = TRUE;

-- =============================================================================
-- Milk Meter Readings
-- =============================================================================

CREATE TABLE IF NOT EXISTS milk_meter_readings (
    id BIGSERIAL,
    time TIMESTAMPTZ NOT NULL,
    device_id VARCHAR(50) NOT NULL,
    animal_id INTEGER,
    session_id VARCHAR(50),
    volume_liters DOUBLE PRECISION NOT NULL,
    flow_rate_lpm DOUBLE PRECISION,
    conductivity DOUBLE PRECISION,
    temperature_celsius DOUBLE PRECISION,
    fat_percent DOUBLE PRECISION,
    protein_percent DOUBLE PRECISION,
    somatic_cell_count INTEGER,
    is_complete BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (id, time)
);

SELECT create_hypertable(
    'milk_meter_readings',
    'time',
    chunk_time_interval => INTERVAL '1 day',
    if_not_exists => TRUE
);

CREATE INDEX idx_milk_readings_device_time
    ON milk_meter_readings (device_id, time DESC);

CREATE INDEX idx_milk_readings_animal
    ON milk_meter_readings (animal_id, time DESC);

CREATE INDEX idx_milk_readings_session
    ON milk_meter_readings (session_id);

-- =============================================================================
-- Device Status History
-- =============================================================================

CREATE TABLE IF NOT EXISTS device_status (
    id BIGSERIAL,
    time TIMESTAMPTZ NOT NULL,
    device_id VARCHAR(50) NOT NULL,
    is_online BOOLEAN NOT NULL,
    signal_strength INTEGER,
    battery_level INTEGER,
    firmware_version VARCHAR(30),
    ip_address INET,
    metadata JSONB DEFAULT '{}',
    PRIMARY KEY (id, time)
);

SELECT create_hypertable(
    'device_status',
    'time',
    chunk_time_interval => INTERVAL '7 days',
    if_not_exists => TRUE
);

-- =============================================================================
-- Alerts History
-- =============================================================================

CREATE TABLE IF NOT EXISTS alert_history (
    id BIGSERIAL,
    time TIMESTAMPTZ NOT NULL,
    alert_id VARCHAR(50) NOT NULL,
    device_id VARCHAR(50) NOT NULL,
    alert_type VARCHAR(50) NOT NULL,
    severity VARCHAR(20) NOT NULL,
    message TEXT,
    trigger_value DOUBLE PRECISION,
    threshold_value DOUBLE PRECISION,
    state VARCHAR(20) NOT NULL,
    acknowledged_at TIMESTAMPTZ,
    acknowledged_by VARCHAR(100),
    resolved_at TIMESTAMPTZ,
    PRIMARY KEY (id, time)
);

SELECT create_hypertable(
    'alert_history',
    'time',
    chunk_time_interval => INTERVAL '7 days',
    if_not_exists => TRUE
);

-- =============================================================================
-- Continuous Aggregates for Analytics
-- =============================================================================

-- Hourly temperature statistics
CREATE MATERIALIZED VIEW temperature_hourly
WITH (timescaledb.continuous) AS
SELECT
    time_bucket('1 hour', time) AS bucket,
    device_id,
    location_id,
    AVG(temperature_celsius) AS avg_temp,
    MIN(temperature_celsius) AS min_temp,
    MAX(temperature_celsius) AS max_temp,
    STDDEV(temperature_celsius) AS stddev_temp,
    COUNT(*) AS reading_count,
    SUM(CASE WHEN is_alarm THEN 1 ELSE 0 END) AS alarm_count
FROM temperature_readings
GROUP BY bucket, device_id, location_id
WITH NO DATA;

-- Refresh policy for hourly aggregates
SELECT add_continuous_aggregate_policy('temperature_hourly',
    start_offset => INTERVAL '3 hours',
    end_offset => INTERVAL '1 hour',
    schedule_interval => INTERVAL '1 hour');

-- Daily milk production statistics
CREATE MATERIALIZED VIEW milk_production_daily
WITH (timescaledb.continuous) AS
SELECT
    time_bucket('1 day', time) AS bucket,
    device_id,
    animal_id,
    SUM(volume_liters) AS total_volume,
    AVG(fat_percent) AS avg_fat,
    AVG(protein_percent) AS avg_protein,
    AVG(conductivity) AS avg_conductivity,
    COUNT(DISTINCT session_id) AS session_count
FROM milk_meter_readings
WHERE is_complete = TRUE
GROUP BY bucket, device_id, animal_id
WITH NO DATA;

SELECT add_continuous_aggregate_policy('milk_production_daily',
    start_offset => INTERVAL '2 days',
    end_offset => INTERVAL '1 day',
    schedule_interval => INTERVAL '1 day');

-- =============================================================================
-- Data Retention Policies
-- =============================================================================

-- Raw sensor data: 90 days
SELECT add_retention_policy('sensor_readings', INTERVAL '90 days');

-- Temperature readings: 90 days
SELECT add_retention_policy('temperature_readings', INTERVAL '90 days');

-- Milk meter readings: 180 days
SELECT add_retention_policy('milk_meter_readings', INTERVAL '180 days');

-- Device status: 30 days
SELECT add_retention_policy('device_status', INTERVAL '30 days');

-- Alert history: 365 days
SELECT add_retention_policy('alert_history', INTERVAL '365 days');

-- =============================================================================
-- Compression Policies
-- =============================================================================

-- Enable compression on older data
ALTER TABLE sensor_readings SET (
    timescaledb.compress,
    timescaledb.compress_segmentby = 'device_id, device_type',
    timescaledb.compress_orderby = 'time DESC'
);

SELECT add_compression_policy('sensor_readings', INTERVAL '7 days');

ALTER TABLE temperature_readings SET (
    timescaledb.compress,
    timescaledb.compress_segmentby = 'device_id',
    timescaledb.compress_orderby = 'time DESC'
);

SELECT add_compression_policy('temperature_readings', INTERVAL '7 days');

ALTER TABLE milk_meter_readings SET (
    timescaledb.compress,
    timescaledb.compress_segmentby = 'device_id, animal_id',
    timescaledb.compress_orderby = 'time DESC'
);

SELECT add_compression_policy('milk_meter_readings', INTERVAL '14 days');
```

**Task 2: IoT Consumer Service Foundation (4h)**

```python
# services/iot_consumer/app/main.py

import asyncio
import logging
import json
from datetime import datetime
from contextlib import asynccontextmanager

import asyncio_mqtt as aiomqtt
import asyncpg
from fastapi import FastAPI
from redis import asyncio as aioredis

from app.config import settings
from app.handlers import (
    TemperatureHandler,
    MilkMeterHandler,
    GenericSensorHandler,
)
from app.alert_engine import AlertEngine
from app.websocket_hub import WebSocketHub

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)


class IoTConsumerService:
    """Main IoT Consumer Service"""

    def __init__(self):
        self.db_pool: asyncpg.Pool = None
        self.redis: aioredis.Redis = None
        self.mqtt_client: aiomqtt.Client = None
        self.alert_engine: AlertEngine = None
        self.ws_hub: WebSocketHub = None
        self.handlers = {}
        self._running = False

    async def initialize(self):
        """Initialize all connections and handlers"""
        logger.info("Initializing IoT Consumer Service...")

        # Database connection pool
        self.db_pool = await asyncpg.create_pool(
            host=settings.TIMESCALE_HOST,
            port=settings.TIMESCALE_PORT,
            user=settings.TIMESCALE_USER,
            password=settings.TIMESCALE_PASSWORD,
            database=settings.TIMESCALE_DB,
            min_size=5,
            max_size=20,
        )
        logger.info("TimescaleDB pool created")

        # Redis connection
        self.redis = await aioredis.from_url(
            settings.REDIS_URL,
            encoding="utf-8",
            decode_responses=True,
        )
        logger.info("Redis connected")

        # Initialize components
        self.alert_engine = AlertEngine(self.db_pool, self.redis)
        self.ws_hub = WebSocketHub(self.redis)

        # Register handlers
        self.handlers = {
            'temperature': TemperatureHandler(
                self.db_pool, self.redis, self.alert_engine, self.ws_hub
            ),
            'milk_meter': MilkMeterHandler(
                self.db_pool, self.redis, self.alert_engine, self.ws_hub
            ),
            'generic': GenericSensorHandler(
                self.db_pool, self.redis, self.alert_engine, self.ws_hub
            ),
        }

        logger.info("IoT Consumer Service initialized")

    async def shutdown(self):
        """Cleanup connections"""
        logger.info("Shutting down IoT Consumer Service...")
        self._running = False

        if self.db_pool:
            await self.db_pool.close()
        if self.redis:
            await self.redis.close()

        logger.info("IoT Consumer Service shutdown complete")

    async def start_mqtt_consumer(self):
        """Start consuming MQTT messages"""
        self._running = True

        while self._running:
            try:
                async with aiomqtt.Client(
                    hostname=settings.MQTT_HOST,
                    port=settings.MQTT_PORT,
                    username=settings.MQTT_USER,
                    password=settings.MQTT_PASSWORD,
                    tls_context=self._get_tls_context() if settings.MQTT_TLS else None,
                ) as client:
                    logger.info(f"Connected to MQTT broker at {settings.MQTT_HOST}")

                    # Subscribe to all sensor topics
                    await client.subscribe("smartdairy/sensors/#", qos=1)
                    await client.subscribe("smartdairy/gateway/#", qos=1)

                    logger.info("Subscribed to sensor topics")

                    async for message in client.messages:
                        try:
                            await self._process_message(message)
                        except Exception as e:
                            logger.error(f"Error processing message: {e}")

            except aiomqtt.MqttError as e:
                logger.error(f"MQTT connection error: {e}")
                if self._running:
                    logger.info("Reconnecting in 5 seconds...")
                    await asyncio.sleep(5)

    def _get_tls_context(self):
        """Create TLS context for secure MQTT"""
        import ssl
        context = ssl.create_default_context()
        context.load_cert_chain(
            certfile=settings.MQTT_CERT_FILE,
            keyfile=settings.MQTT_KEY_FILE,
        )
        context.load_verify_locations(cafile=settings.MQTT_CA_FILE)
        return context

    async def _process_message(self, message):
        """Route message to appropriate handler"""
        topic = str(message.topic)
        payload = message.payload.decode('utf-8')

        try:
            data = json.loads(payload)
        except json.JSONDecodeError:
            logger.warning(f"Invalid JSON in message: {payload[:100]}")
            return

        # Parse topic: smartdairy/sensors/{type}/{device_id}/data
        parts = topic.split('/')
        if len(parts) < 4:
            logger.warning(f"Invalid topic format: {topic}")
            return

        sensor_type = parts[2] if parts[1] == 'sensors' else 'generic'
        device_id = parts[3]

        # Add metadata
        data['_device_id'] = device_id
        data['_topic'] = topic
        data['_received_at'] = datetime.utcnow().isoformat()

        # Route to handler
        handler = self.handlers.get(sensor_type, self.handlers['generic'])
        await handler.handle(data)

        # Update device last seen
        await self._update_device_status(device_id)

    async def _update_device_status(self, device_id: str):
        """Update device last seen timestamp"""
        await self.redis.hset(
            f"device:{device_id}:status",
            mapping={
                'last_seen': datetime.utcnow().isoformat(),
                'is_online': 'true',
            }
        )
        await self.redis.expire(f"device:{device_id}:status", 600)  # 10 min TTL


# FastAPI app for health checks and metrics
service = IoTConsumerService()


@asynccontextmanager
async def lifespan(app: FastAPI):
    await service.initialize()
    asyncio.create_task(service.start_mqtt_consumer())
    yield
    await service.shutdown()


app = FastAPI(
    title="Smart Dairy IoT Consumer",
    version="1.0.0",
    lifespan=lifespan,
)


@app.get("/health")
async def health_check():
    return {
        "status": "healthy",
        "service": "iot-consumer",
        "mqtt_connected": service._running,
    }


@app.get("/metrics")
async def get_metrics():
    # Get processing stats from Redis
    stats = await service.redis.hgetall("iot:stats")
    return {
        "messages_processed": int(stats.get("messages_processed", 0)),
        "alerts_generated": int(stats.get("alerts_generated", 0)),
        "errors": int(stats.get("errors", 0)),
    }
```

#### Dev 2 Tasks (8 hours) - Full-Stack

**Task 1: Sensor Reading Model (4h)**

```python
# addons/smartdairy_iot/models/iot_sensor_reading.py

from odoo import models, fields, api
from datetime import datetime, timedelta


class IoTSensorReading(models.Model):
    _name = 'iot.sensor.reading'
    _description = 'IoT Sensor Reading'
    _order = 'timestamp desc'
    _rec_name = 'display_name'

    device_id = fields.Many2one(
        'iot.device',
        string='Device',
        required=True,
        ondelete='cascade',
        index=True,
    )
    timestamp = fields.Datetime(
        string='Timestamp',
        required=True,
        default=fields.Datetime.now,
        index=True,
    )
    reading_type = fields.Selection([
        ('temperature', 'Temperature'),
        ('humidity', 'Humidity'),
        ('pressure', 'Pressure'),
        ('volume', 'Volume'),
        ('flow_rate', 'Flow Rate'),
        ('ph', 'pH Level'),
        ('conductivity', 'Conductivity'),
        ('fat_percent', 'Fat Percentage'),
        ('protein_percent', 'Protein Percentage'),
        ('scc', 'Somatic Cell Count'),
        ('battery', 'Battery Level'),
        ('signal', 'Signal Strength'),
    ], string='Reading Type', required=True, index=True)

    value = fields.Float(
        string='Value',
        required=True,
        digits=(16, 4),
    )
    unit = fields.Char(
        string='Unit',
        required=True,
    )
    quality = fields.Integer(
        string='Quality Score',
        default=100,
        help='Data quality score 0-100',
    )

    # Threshold tracking
    is_alarm = fields.Boolean(
        string='Alarm Triggered',
        default=False,
    )
    threshold_id = fields.Many2one(
        'iot.threshold',
        string='Violated Threshold',
    )

    # Related data
    farm_id = fields.Many2one(
        related='device_id.farm_id',
        string='Farm',
        store=True,
    )
    location_id = fields.Many2one(
        related='device_id.location_id',
        string='Location',
        store=True,
    )

    display_name = fields.Char(
        string='Display Name',
        compute='_compute_display_name',
    )

    @api.depends('device_id', 'reading_type', 'value', 'unit')
    def _compute_display_name(self):
        for reading in self:
            reading.display_name = (
                f"{reading.device_id.name} - "
                f"{reading.reading_type}: {reading.value} {reading.unit}"
            )

    @api.model
    def create_from_mqtt(self, device_id, reading_type, value, unit,
                         timestamp=None, quality=100, metadata=None):
        """Create reading from MQTT message"""
        device = self.env['iot.device'].search([
            ('device_id', '=', device_id)
        ], limit=1)

        if not device:
            return False

        # Check thresholds
        threshold = self.env['iot.threshold'].check_threshold(
            device.id, reading_type, value
        )

        vals = {
            'device_id': device.id,
            'timestamp': timestamp or fields.Datetime.now(),
            'reading_type': reading_type,
            'value': value,
            'unit': unit,
            'quality': quality,
            'is_alarm': bool(threshold),
            'threshold_id': threshold.id if threshold else False,
        }

        reading = self.create(vals)

        # Generate alert if threshold violated
        if threshold:
            self.env['iot.alert'].create_from_threshold(
                device, threshold, reading
            )

        return reading

    @api.model
    def get_latest_readings(self, device_id, reading_types=None, limit=10):
        """Get latest readings for a device"""
        domain = [('device_id', '=', device_id)]
        if reading_types:
            domain.append(('reading_type', 'in', reading_types))

        return self.search(domain, limit=limit, order='timestamp desc')

    @api.model
    def get_statistics(self, device_id, reading_type,
                       start_date=None, end_date=None):
        """Calculate statistics for readings"""
        if not start_date:
            start_date = datetime.now() - timedelta(hours=24)
        if not end_date:
            end_date = datetime.now()

        self.env.cr.execute("""
            SELECT
                AVG(value) as avg_value,
                MIN(value) as min_value,
                MAX(value) as max_value,
                STDDEV(value) as stddev_value,
                COUNT(*) as count
            FROM iot_sensor_reading
            WHERE device_id = %s
              AND reading_type = %s
              AND timestamp BETWEEN %s AND %s
        """, (device_id, reading_type, start_date, end_date))

        result = self.env.cr.dictfetchone()
        return result


class IoTThreshold(models.Model):
    _name = 'iot.threshold'
    _description = 'IoT Alert Threshold'
    _order = 'device_type, reading_type'

    name = fields.Char(
        string='Threshold Name',
        required=True,
    )
    device_type = fields.Selection([
        ('temperature', 'Temperature Sensor'),
        ('milk_meter', 'Milk Meter'),
        ('ph_sensor', 'pH Sensor'),
        ('all', 'All Devices'),
    ], string='Device Type', required=True)

    reading_type = fields.Selection([
        ('temperature', 'Temperature'),
        ('humidity', 'Humidity'),
        ('ph', 'pH Level'),
        ('volume', 'Volume'),
        ('fat_percent', 'Fat Percentage'),
        ('protein_percent', 'Protein Percentage'),
        ('scc', 'Somatic Cell Count'),
    ], string='Reading Type', required=True)

    # Threshold values
    min_value = fields.Float(string='Minimum Value')
    max_value = fields.Float(string='Maximum Value')
    use_min = fields.Boolean(string='Check Minimum', default=False)
    use_max = fields.Boolean(string='Check Maximum', default=True)

    # Alert configuration
    severity = fields.Selection([
        ('low', 'Low'),
        ('medium', 'Medium'),
        ('high', 'High'),
        ('critical', 'Critical'),
    ], string='Severity', default='medium', required=True)

    cooldown_minutes = fields.Integer(
        string='Cooldown (minutes)',
        default=15,
        help='Minimum time between alerts for same threshold',
    )

    is_active = fields.Boolean(
        string='Active',
        default=True,
    )

    @api.model
    def check_threshold(self, device_id, reading_type, value):
        """Check if value violates any threshold"""
        device = self.env['iot.device'].browse(device_id)

        thresholds = self.search([
            ('reading_type', '=', reading_type),
            ('is_active', '=', True),
            '|',
            ('device_type', '=', device.device_type),
            ('device_type', '=', 'all'),
        ])

        for threshold in thresholds:
            violated = False

            if threshold.use_min and value < threshold.min_value:
                violated = True
            if threshold.use_max and value > threshold.max_value:
                violated = True

            if violated:
                # Check cooldown
                if self._check_cooldown(device_id, threshold.id):
                    return threshold

        return False

    def _check_cooldown(self, device_id, threshold_id):
        """Check if enough time has passed since last alert"""
        threshold = self.browse(threshold_id)
        cooldown = timedelta(minutes=threshold.cooldown_minutes)
        cutoff = datetime.now() - cooldown

        recent_alert = self.env['iot.alert'].search([
            ('device_id', '=', device_id),
            ('threshold_id', '=', threshold_id),
            ('create_date', '>', cutoff),
        ], limit=1)

        return not recent_alert
```

#### Dev 3 Tasks (8 hours) - Frontend/Mobile

**Task 1: IoT Domain Entities (4h)**

```dart
// lib/features/iot/domain/entities/device.dart

import 'package:equatable/equatable.dart';

enum DeviceType {
  temperature,
  milkMeter,
  phSensor,
  flowSensor,
  humidity,
  pressure,
  gateway;

  static DeviceType fromString(String value) {
    return DeviceType.values.firstWhere(
      (e) => e.name == value || e.name == value.replaceAll('_', ''),
      orElse: () => DeviceType.temperature,
    );
  }

  String get displayName {
    switch (this) {
      case DeviceType.temperature:
        return 'Temperature Sensor';
      case DeviceType.milkMeter:
        return 'Milk Meter';
      case DeviceType.phSensor:
        return 'pH Sensor';
      case DeviceType.flowSensor:
        return 'Flow Sensor';
      case DeviceType.humidity:
        return 'Humidity Sensor';
      case DeviceType.pressure:
        return 'Pressure Sensor';
      case DeviceType.gateway:
        return 'IoT Gateway';
    }
  }

  String get icon {
    switch (this) {
      case DeviceType.temperature:
        return 'thermostat';
      case DeviceType.milkMeter:
        return 'water_drop';
      case DeviceType.phSensor:
        return 'science';
      case DeviceType.flowSensor:
        return 'waves';
      case DeviceType.humidity:
        return 'water';
      case DeviceType.pressure:
        return 'speed';
      case DeviceType.gateway:
        return 'router';
    }
  }
}

enum DeviceState {
  draft,
  registered,
  active,
  inactive,
  maintenance,
  retired;

  static DeviceState fromString(String value) {
    return DeviceState.values.firstWhere(
      (e) => e.name == value,
      orElse: () => DeviceState.draft,
    );
  }
}

class Device extends Equatable {
  final String id;
  final String deviceId;
  final String name;
  final DeviceType deviceType;
  final DeviceState state;
  final String? farmId;
  final String? farmName;
  final String? locationDescription;
  final String? manufacturer;
  final String? model;
  final String? firmwareVersion;
  final DateTime? lastSeen;
  final bool isOnline;
  final int? signalStrength;
  final int? batteryLevel;
  final double? lastValue;
  final String? lastValueUnit;
  final int alertCount;

  const Device({
    required this.id,
    required this.deviceId,
    required this.name,
    required this.deviceType,
    required this.state,
    this.farmId,
    this.farmName,
    this.locationDescription,
    this.manufacturer,
    this.model,
    this.firmwareVersion,
    this.lastSeen,
    this.isOnline = false,
    this.signalStrength,
    this.batteryLevel,
    this.lastValue,
    this.lastValueUnit,
    this.alertCount = 0,
  });

  String get statusText {
    if (state == DeviceState.active) {
      return isOnline ? 'Online' : 'Offline';
    }
    return state.name.toUpperCase();
  }

  bool get hasLowBattery => (batteryLevel ?? 100) < 20;
  bool get hasWeakSignal => (signalStrength ?? 100) < 30;

  @override
  List<Object?> get props => [
        id,
        deviceId,
        name,
        deviceType,
        state,
        isOnline,
        lastSeen,
        alertCount,
      ];
}

// lib/features/iot/domain/entities/sensor_reading.dart

class SensorReading extends Equatable {
  final String id;
  final String deviceId;
  final DateTime timestamp;
  final double value;
  final String unit;
  final String? readingType;
  final Map<String, dynamic>? metadata;

  const SensorReading({
    required this.id,
    required this.deviceId,
    required this.timestamp,
    required this.value,
    required this.unit,
    this.readingType,
    this.metadata,
  });

  String get formattedValue => '${value.toStringAsFixed(2)} $unit';

  @override
  List<Object?> get props => [id, deviceId, timestamp, value];
}

// lib/features/iot/domain/entities/alert.dart

enum AlertSeverity {
  low,
  medium,
  high,
  critical;

  static AlertSeverity fromString(String value) {
    return AlertSeverity.values.firstWhere(
      (e) => e.name == value,
      orElse: () => AlertSeverity.medium,
    );
  }

  int get priority {
    switch (this) {
      case AlertSeverity.critical:
        return 1;
      case AlertSeverity.high:
        return 2;
      case AlertSeverity.medium:
        return 3;
      case AlertSeverity.low:
        return 4;
    }
  }
}

enum AlertType {
  temperatureHigh,
  temperatureLow,
  volumeAnomaly,
  qualityIssue,
  deviceOffline,
  batteryLow,
  connectionLost;

  static AlertType fromString(String value) {
    final map = {
      'temperature_high': AlertType.temperatureHigh,
      'temperature_low': AlertType.temperatureLow,
      'volume_anomaly': AlertType.volumeAnomaly,
      'quality_issue': AlertType.qualityIssue,
      'device_offline': AlertType.deviceOffline,
      'battery_low': AlertType.batteryLow,
      'connection_lost': AlertType.connectionLost,
    };
    return map[value] ?? AlertType.qualityIssue;
  }
}

enum AlertState {
  newAlert,
  acknowledged,
  resolved,
  dismissed;

  static AlertState fromString(String value) {
    if (value == 'new') return AlertState.newAlert;
    return AlertState.values.firstWhere(
      (e) => e.name == value,
      orElse: () => AlertState.newAlert,
    );
  }
}

class Alert extends Equatable {
  final String id;
  final String deviceId;
  final String deviceName;
  final AlertType alertType;
  final AlertSeverity severity;
  final String message;
  final AlertState state;
  final DateTime createdAt;
  final DateTime? acknowledgedAt;
  final String? acknowledgedBy;
  final double? triggerValue;
  final double? thresholdValue;
  final String? unit;

  const Alert({
    required this.id,
    required this.deviceId,
    required this.deviceName,
    required this.alertType,
    required this.severity,
    required this.message,
    required this.state,
    required this.createdAt,
    this.acknowledgedAt,
    this.acknowledgedBy,
    this.triggerValue,
    this.thresholdValue,
    this.unit,
  });

  bool get isActive => state == AlertState.newAlert;
  bool get isCritical => severity == AlertSeverity.critical;

  @override
  List<Object?> get props => [id, state, severity, createdAt];
}
```

**Task 2: IoT BLoC Setup (4h)**

```dart
// lib/features/iot/presentation/bloc/iot_dashboard_bloc.dart

import 'dart:async';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:freezed_annotation/freezed_annotation.dart';
import 'package:injectable/injectable.dart';
import '../../domain/entities/device.dart';
import '../../domain/entities/alert.dart';
import '../../domain/repositories/iot_repository.dart';

part 'iot_dashboard_bloc.freezed.dart';

// Events
@freezed
class IoTDashboardEvent with _$IoTDashboardEvent {
  const factory IoTDashboardEvent.load() = _Load;
  const factory IoTDashboardEvent.refresh() = _Refresh;
  const factory IoTDashboardEvent.filterByType(DeviceType? type) = _FilterByType;
  const factory IoTDashboardEvent.acknowledgeAlert(String alertId) = _AcknowledgeAlert;
  const factory IoTDashboardEvent.deviceStatusUpdated(String deviceId, bool isOnline) =
      _DeviceStatusUpdated;
  const factory IoTDashboardEvent.newAlertReceived(Alert alert) = _NewAlertReceived;
}

// State
@freezed
class IoTDashboardState with _$IoTDashboardState {
  const factory IoTDashboardState({
    @Default([]) List<Device> devices,
    @Default([]) List<Device> filteredDevices,
    @Default([]) List<Alert> activeAlerts,
    @Default(null) DeviceType? selectedFilter,
    @Default(false) bool isLoading,
    @Default(null) String? error,
    @Default(0) int onlineDeviceCount,
    @Default(0) int offlineDeviceCount,
    @Default(0) int alertCount,
  }) = _IoTDashboardState;
}

@injectable
class IoTDashboardBloc extends Bloc<IoTDashboardEvent, IoTDashboardState> {
  final IoTRepository _repository;
  StreamSubscription? _alertSubscription;
  StreamSubscription? _deviceStatusSubscription;

  IoTDashboardBloc(this._repository) : super(const IoTDashboardState()) {
    on<_Load>(_onLoad);
    on<_Refresh>(_onRefresh);
    on<_FilterByType>(_onFilterByType);
    on<_AcknowledgeAlert>(_onAcknowledgeAlert);
    on<_DeviceStatusUpdated>(_onDeviceStatusUpdated);
    on<_NewAlertReceived>(_onNewAlertReceived);

    // Subscribe to real-time updates
    _subscribeToUpdates();
  }

  void _subscribeToUpdates() {
    _alertSubscription = _repository.alertStream.listen((alert) {
      add(IoTDashboardEvent.newAlertReceived(alert));
    });

    _deviceStatusSubscription = _repository.deviceStatusStream.listen((status) {
      add(IoTDashboardEvent.deviceStatusUpdated(
        status['deviceId'],
        status['isOnline'],
      ));
    });
  }

  Future<void> _onLoad(_Load event, Emitter<IoTDashboardState> emit) async {
    emit(state.copyWith(isLoading: true, error: null));

    try {
      final results = await Future.wait([
        _repository.getDevices(),
        _repository.getAlerts(acknowledged: false),
      ]);

      final devices = results[0] as List<Device>;
      final alerts = results[1] as List<Alert>;

      emit(state.copyWith(
        isLoading: false,
        devices: devices,
        filteredDevices: devices,
        activeAlerts: alerts,
        onlineDeviceCount: devices.where((d) => d.isOnline).length,
        offlineDeviceCount: devices.where((d) => !d.isOnline).length,
        alertCount: alerts.length,
      ));
    } catch (e) {
      emit(state.copyWith(
        isLoading: false,
        error: e.toString(),
      ));
    }
  }

  Future<void> _onRefresh(_Refresh event, Emitter<IoTDashboardState> emit) async {
    add(const IoTDashboardEvent.load());
  }

  void _onFilterByType(_FilterByType event, Emitter<IoTDashboardState> emit) {
    final filtered = event.type == null
        ? state.devices
        : state.devices.where((d) => d.deviceType == event.type).toList();

    emit(state.copyWith(
      selectedFilter: event.type,
      filteredDevices: filtered,
    ));
  }

  Future<void> _onAcknowledgeAlert(
    _AcknowledgeAlert event,
    Emitter<IoTDashboardState> emit,
  ) async {
    try {
      await _repository.acknowledgeAlert(event.alertId);

      final updatedAlerts = state.activeAlerts
          .where((a) => a.id != event.alertId)
          .toList();

      emit(state.copyWith(
        activeAlerts: updatedAlerts,
        alertCount: updatedAlerts.length,
      ));
    } catch (e) {
      emit(state.copyWith(error: e.toString()));
    }
  }

  void _onDeviceStatusUpdated(
    _DeviceStatusUpdated event,
    Emitter<IoTDashboardState> emit,
  ) {
    final updatedDevices = state.devices.map((device) {
      if (device.deviceId == event.deviceId) {
        return Device(
          id: device.id,
          deviceId: device.deviceId,
          name: device.name,
          deviceType: device.deviceType,
          state: device.state,
          farmId: device.farmId,
          farmName: device.farmName,
          locationDescription: device.locationDescription,
          isOnline: event.isOnline,
          lastSeen: event.isOnline ? DateTime.now() : device.lastSeen,
          signalStrength: device.signalStrength,
          batteryLevel: device.batteryLevel,
          alertCount: device.alertCount,
        );
      }
      return device;
    }).toList();

    emit(state.copyWith(
      devices: updatedDevices,
      filteredDevices: state.selectedFilter == null
          ? updatedDevices
          : updatedDevices
              .where((d) => d.deviceType == state.selectedFilter)
              .toList(),
      onlineDeviceCount: updatedDevices.where((d) => d.isOnline).length,
      offlineDeviceCount: updatedDevices.where((d) => !d.isOnline).length,
    ));
  }

  void _onNewAlertReceived(
    _NewAlertReceived event,
    Emitter<IoTDashboardState> emit,
  ) {
    final updatedAlerts = [event.alert, ...state.activeAlerts];
    emit(state.copyWith(
      activeAlerts: updatedAlerts,
      alertCount: updatedAlerts.length,
    ));
  }

  @override
  Future<void> close() {
    _alertSubscription?.cancel();
    _deviceStatusSubscription?.cancel();
    return super.close();
  }
}
```

#### End of Day 252 Checklist

- [ ] TimescaleDB schema created with hypertables
- [ ] Continuous aggregates configured
- [ ] Data retention policies set up
- [ ] Compression policies configured
- [ ] IoT consumer service foundation created
- [ ] Sensor reading model implemented in Odoo
- [ ] Threshold model for alert configuration
- [ ] Mobile domain entities defined
- [ ] IoT Dashboard BLoC implemented

---

### Day 253-254: Temperature & Milk Meter Handlers

#### Dev 1 Tasks (16 hours over 2 days)

**Temperature Handler Implementation**

```python
# services/iot_consumer/app/handlers/temperature_handler.py

import asyncio
import logging
from datetime import datetime
from typing import Dict, Any

import asyncpg

from app.handlers.base import BaseHandler
from app.alert_engine import AlertEngine
from app.websocket_hub import WebSocketHub

logger = logging.getLogger(__name__)


class TemperatureHandler(BaseHandler):
    """Handler for temperature sensor data"""

    READING_TYPE = 'temperature'
    TABLE_NAME = 'temperature_readings'

    # Cold chain temperature thresholds (Celsius)
    THRESHOLDS = {
        'raw_milk': {'min': 2, 'max': 4},
        'pasteurized': {'min': 0, 'max': 4},
        'transport': {'min': 0, 'max': 7},
        'storage': {'min': 2, 'max': 6},
        'default': {'min': 0, 'max': 8},
    }

    def __init__(
        self,
        db_pool: asyncpg.Pool,
        redis,
        alert_engine: AlertEngine,
        ws_hub: WebSocketHub,
    ):
        super().__init__(db_pool, redis, alert_engine, ws_hub)

    async def handle(self, data: Dict[str, Any]):
        """Process temperature sensor data"""
        device_id = data.get('_device_id')

        try:
            # Extract reading values
            temperature = float(data.get('temperature', data.get('value', 0)))
            humidity = data.get('humidity')
            location_type = data.get('location_type', 'default')
            timestamp = self._parse_timestamp(data.get('timestamp'))

            # Determine if alarm condition
            thresholds = self.THRESHOLDS.get(location_type, self.THRESHOLDS['default'])
            is_alarm = temperature < thresholds['min'] or temperature > thresholds['max']
            alarm_type = None

            if temperature < thresholds['min']:
                alarm_type = 'low_temperature'
            elif temperature > thresholds['max']:
                alarm_type = 'high_temperature'

            # Store in TimescaleDB
            await self._store_reading(
                device_id=device_id,
                temperature=temperature,
                humidity=humidity,
                is_alarm=is_alarm,
                alarm_type=alarm_type,
                location_id=data.get('location_id'),
                timestamp=timestamp,
            )

            # Update Redis cache for real-time access
            await self._update_cache(device_id, temperature, humidity, is_alarm)

            # Broadcast via WebSocket
            await self.ws_hub.broadcast(
                channel=f"device:{device_id}",
                message={
                    'type': 'temperature_reading',
                    'device_id': device_id,
                    'temperature': temperature,
                    'humidity': humidity,
                    'is_alarm': is_alarm,
                    'timestamp': timestamp.isoformat(),
                }
            )

            # Generate alert if needed
            if is_alarm:
                await self._generate_alert(
                    device_id=device_id,
                    temperature=temperature,
                    thresholds=thresholds,
                    alarm_type=alarm_type,
                )

            # Update stats
            await self.redis.hincrby("iot:stats", "messages_processed", 1)

        except Exception as e:
            logger.error(f"Error processing temperature data: {e}")
            await self.redis.hincrby("iot:stats", "errors", 1)
            raise

    async def _store_reading(
        self,
        device_id: str,
        temperature: float,
        humidity: float = None,
        is_alarm: bool = False,
        alarm_type: str = None,
        location_id: int = None,
        timestamp: datetime = None,
    ):
        """Store temperature reading in TimescaleDB"""
        async with self.db_pool.acquire() as conn:
            await conn.execute("""
                INSERT INTO temperature_readings
                (time, device_id, location_id, temperature_celsius,
                 humidity_percent, is_alarm, alarm_type)
                VALUES ($1, $2, $3, $4, $5, $6, $7)
            """, timestamp or datetime.utcnow(), device_id, location_id,
                temperature, humidity, is_alarm, alarm_type)

    async def _update_cache(
        self,
        device_id: str,
        temperature: float,
        humidity: float = None,
        is_alarm: bool = False,
    ):
        """Update Redis cache with latest reading"""
        cache_key = f"device:{device_id}:latest"
        await self.redis.hset(cache_key, mapping={
            'temperature': str(temperature),
            'humidity': str(humidity) if humidity else '',
            'is_alarm': str(is_alarm).lower(),
            'updated_at': datetime.utcnow().isoformat(),
        })
        await self.redis.expire(cache_key, 3600)  # 1 hour TTL

    async def _generate_alert(
        self,
        device_id: str,
        temperature: float,
        thresholds: Dict[str, float],
        alarm_type: str,
    ):
        """Generate temperature alert"""
        severity = 'high' if abs(temperature - thresholds['max']) > 5 else 'medium'

        if alarm_type == 'high_temperature':
            message = (
                f"Temperature exceeded maximum threshold: "
                f"{temperature}°C (max: {thresholds['max']}°C)"
            )
        else:
            message = (
                f"Temperature below minimum threshold: "
                f"{temperature}°C (min: {thresholds['min']}°C)"
            )

        await self.alert_engine.create_alert(
            device_id=device_id,
            alert_type=alarm_type,
            severity=severity,
            message=message,
            trigger_value=temperature,
            threshold_value=thresholds['max'] if alarm_type == 'high_temperature'
                          else thresholds['min'],
            unit='°C',
        )


# services/iot_consumer/app/handlers/milk_meter_handler.py

class MilkMeterHandler(BaseHandler):
    """Handler for milk meter sensor data"""

    def __init__(
        self,
        db_pool: asyncpg.Pool,
        redis,
        alert_engine: AlertEngine,
        ws_hub: WebSocketHub,
    ):
        super().__init__(db_pool, redis, alert_engine, ws_hub)
        self._active_sessions = {}

    async def handle(self, data: Dict[str, Any]):
        """Process milk meter data"""
        device_id = data.get('_device_id')
        message_type = data.get('type', 'reading')

        try:
            if message_type == 'session_start':
                await self._handle_session_start(device_id, data)
            elif message_type == 'session_end':
                await self._handle_session_end(device_id, data)
            else:
                await self._handle_reading(device_id, data)

            await self.redis.hincrby("iot:stats", "messages_processed", 1)

        except Exception as e:
            logger.error(f"Error processing milk meter data: {e}")
            await self.redis.hincrby("iot:stats", "errors", 1)
            raise

    async def _handle_session_start(self, device_id: str, data: Dict):
        """Handle milking session start"""
        session_id = data.get('session_id', f"{device_id}_{datetime.utcnow().timestamp()}")
        animal_id = data.get('animal_id')

        self._active_sessions[session_id] = {
            'device_id': device_id,
            'animal_id': animal_id,
            'start_time': datetime.utcnow(),
            'total_volume': 0,
            'readings': [],
        }

        logger.info(f"Milking session started: {session_id}")

        await self.ws_hub.broadcast(
            channel=f"device:{device_id}",
            message={
                'type': 'session_start',
                'session_id': session_id,
                'animal_id': animal_id,
                'timestamp': datetime.utcnow().isoformat(),
            }
        )

    async def _handle_reading(self, device_id: str, data: Dict):
        """Handle individual milk reading"""
        session_id = data.get('session_id')
        timestamp = self._parse_timestamp(data.get('timestamp'))

        reading = {
            'volume': float(data.get('volume', 0)),
            'flow_rate': float(data.get('flow_rate', 0)),
            'conductivity': data.get('conductivity'),
            'temperature': data.get('temperature'),
            'fat_percent': data.get('fat_percent'),
            'protein_percent': data.get('protein_percent'),
            'scc': data.get('somatic_cell_count'),
        }

        # Store reading
        async with self.db_pool.acquire() as conn:
            await conn.execute("""
                INSERT INTO milk_meter_readings
                (time, device_id, animal_id, session_id, volume_liters,
                 flow_rate_lpm, conductivity, temperature_celsius,
                 fat_percent, protein_percent, somatic_cell_count, is_complete)
                VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, false)
            """, timestamp, device_id, data.get('animal_id'), session_id,
                reading['volume'], reading['flow_rate'], reading['conductivity'],
                reading['temperature'], reading['fat_percent'],
                reading['protein_percent'], reading['scc'])

        # Update session totals
        if session_id in self._active_sessions:
            self._active_sessions[session_id]['total_volume'] += reading['volume']
            self._active_sessions[session_id]['readings'].append(reading)

        # Check for quality alerts
        await self._check_quality_alerts(device_id, reading)

        # Broadcast
        await self.ws_hub.broadcast(
            channel=f"device:{device_id}",
            message={
                'type': 'milk_reading',
                'session_id': session_id,
                **reading,
                'timestamp': timestamp.isoformat(),
            }
        )

    async def _handle_session_end(self, device_id: str, data: Dict):
        """Handle milking session end"""
        session_id = data.get('session_id')
        session = self._active_sessions.pop(session_id, None)

        if session:
            # Calculate session statistics
            readings = session['readings']
            total_volume = session['total_volume']
            avg_flow = sum(r['flow_rate'] for r in readings) / len(readings) if readings else 0

            # Store final session record
            async with self.db_pool.acquire() as conn:
                await conn.execute("""
                    UPDATE milk_meter_readings
                    SET is_complete = true
                    WHERE session_id = $1 AND device_id = $2
                """, session_id, device_id)

            logger.info(
                f"Milking session ended: {session_id}, "
                f"Total: {total_volume:.2f}L, Avg flow: {avg_flow:.2f} L/min"
            )

            await self.ws_hub.broadcast(
                channel=f"device:{device_id}",
                message={
                    'type': 'session_end',
                    'session_id': session_id,
                    'total_volume': total_volume,
                    'avg_flow_rate': avg_flow,
                    'duration_seconds': (
                        datetime.utcnow() - session['start_time']
                    ).total_seconds(),
                    'timestamp': datetime.utcnow().isoformat(),
                }
            )

    async def _check_quality_alerts(self, device_id: str, reading: Dict):
        """Check for milk quality alerts"""
        alerts = []

        # High SCC indicates mastitis
        if reading['scc'] and reading['scc'] > 400000:
            alerts.append({
                'type': 'high_scc',
                'severity': 'high' if reading['scc'] > 750000 else 'medium',
                'message': f"High somatic cell count detected: {reading['scc']:,}/mL",
                'trigger_value': reading['scc'],
                'threshold_value': 400000,
                'unit': 'cells/mL',
            })

        # Abnormal conductivity may indicate mastitis
        if reading['conductivity'] and reading['conductivity'] > 6.5:
            alerts.append({
                'type': 'abnormal_conductivity',
                'severity': 'medium',
                'message': f"Abnormal milk conductivity: {reading['conductivity']} mS/cm",
                'trigger_value': reading['conductivity'],
                'threshold_value': 6.5,
                'unit': 'mS/cm',
            })

        for alert in alerts:
            await self.alert_engine.create_alert(
                device_id=device_id,
                **alert
            )
```

---

### Day 255-256: Alert Engine & WebSocket Dashboard

#### Dev 1 Tasks (8 hours) - Day 255

**Alert Engine Implementation**

```python
# services/iot_consumer/app/alert_engine.py

import asyncio
import logging
from datetime import datetime, timedelta
from typing import Dict, Any, Optional
import uuid

import asyncpg
from redis import asyncio as aioredis

from app.notifications import NotificationService

logger = logging.getLogger(__name__)


class AlertEngine:
    """Real-time alert processing engine"""

    SEVERITY_LEVELS = ['low', 'medium', 'high', 'critical']
    COOLDOWN_MINUTES = {
        'low': 60,
        'medium': 30,
        'high': 15,
        'critical': 5,
    }

    def __init__(self, db_pool: asyncpg.Pool, redis: aioredis.Redis):
        self.db_pool = db_pool
        self.redis = redis
        self.notification_service = NotificationService()

    async def create_alert(
        self,
        device_id: str,
        alert_type: str,
        severity: str,
        message: str,
        trigger_value: float = None,
        threshold_value: float = None,
        unit: str = None,
    ) -> Optional[str]:
        """Create new alert if not in cooldown"""

        # Check cooldown
        if await self._is_in_cooldown(device_id, alert_type):
            logger.debug(f"Alert {alert_type} for {device_id} in cooldown")
            return None

        alert_id = str(uuid.uuid4())
        timestamp = datetime.utcnow()

        # Store in TimescaleDB
        async with self.db_pool.acquire() as conn:
            await conn.execute("""
                INSERT INTO alert_history
                (time, alert_id, device_id, alert_type, severity,
                 message, trigger_value, threshold_value, state)
                VALUES ($1, $2, $3, $4, $5, $6, $7, $8, 'new')
            """, timestamp, alert_id, device_id, alert_type, severity,
                message, trigger_value, threshold_value)

        # Store in Redis for quick access
        alert_data = {
            'alert_id': alert_id,
            'device_id': device_id,
            'alert_type': alert_type,
            'severity': severity,
            'message': message,
            'trigger_value': str(trigger_value) if trigger_value else '',
            'threshold_value': str(threshold_value) if threshold_value else '',
            'unit': unit or '',
            'created_at': timestamp.isoformat(),
            'state': 'new',
        }
        await self.redis.hset(f"alert:{alert_id}", mapping=alert_data)
        await self.redis.expire(f"alert:{alert_id}", 86400 * 7)  # 7 days

        # Add to active alerts set
        await self.redis.zadd(
            "alerts:active",
            {alert_id: self._severity_score(severity)}
        )

        # Set cooldown
        await self._set_cooldown(device_id, alert_type, severity)

        # Update stats
        await self.redis.hincrby("iot:stats", "alerts_generated", 1)

        # Send notifications
        await self._send_notifications(alert_data)

        logger.info(f"Alert created: {alert_id} - {alert_type} ({severity})")
        return alert_id

    async def acknowledge_alert(self, alert_id: str, user_id: str):
        """Acknowledge an alert"""
        timestamp = datetime.utcnow()

        # Update TimescaleDB
        async with self.db_pool.acquire() as conn:
            await conn.execute("""
                UPDATE alert_history
                SET state = 'acknowledged',
                    acknowledged_at = $1,
                    acknowledged_by = $2
                WHERE alert_id = $3
            """, timestamp, user_id, alert_id)

        # Update Redis
        await self.redis.hset(f"alert:{alert_id}", mapping={
            'state': 'acknowledged',
            'acknowledged_at': timestamp.isoformat(),
            'acknowledged_by': user_id,
        })

        # Remove from active alerts
        await self.redis.zrem("alerts:active", alert_id)

        logger.info(f"Alert acknowledged: {alert_id} by {user_id}")

    async def resolve_alert(self, alert_id: str):
        """Resolve an alert"""
        timestamp = datetime.utcnow()

        async with self.db_pool.acquire() as conn:
            await conn.execute("""
                UPDATE alert_history
                SET state = 'resolved', resolved_at = $1
                WHERE alert_id = $2
            """, timestamp, alert_id)

        await self.redis.hset(f"alert:{alert_id}", mapping={
            'state': 'resolved',
            'resolved_at': timestamp.isoformat(),
        })
        await self.redis.zrem("alerts:active", alert_id)

    async def get_active_alerts(
        self,
        device_id: str = None,
        severity: str = None,
        limit: int = 100,
    ) -> list:
        """Get active alerts sorted by severity"""
        alert_ids = await self.redis.zrevrange("alerts:active", 0, limit - 1)

        alerts = []
        for alert_id in alert_ids:
            alert_data = await self.redis.hgetall(f"alert:{alert_id}")
            if alert_data:
                if device_id and alert_data.get('device_id') != device_id:
                    continue
                if severity and alert_data.get('severity') != severity:
                    continue
                alerts.append(alert_data)

        return alerts

    async def _is_in_cooldown(self, device_id: str, alert_type: str) -> bool:
        """Check if alert is in cooldown period"""
        key = f"alert:cooldown:{device_id}:{alert_type}"
        return await self.redis.exists(key)

    async def _set_cooldown(self, device_id: str, alert_type: str, severity: str):
        """Set cooldown for alert"""
        key = f"alert:cooldown:{device_id}:{alert_type}"
        minutes = self.COOLDOWN_MINUTES.get(severity, 30)
        await self.redis.setex(key, minutes * 60, "1")

    def _severity_score(self, severity: str) -> int:
        """Get numeric score for severity (higher = more critical)"""
        scores = {'low': 1, 'medium': 2, 'high': 3, 'critical': 4}
        return scores.get(severity, 1)

    async def _send_notifications(self, alert_data: Dict[str, Any]):
        """Send notifications based on severity"""
        severity = alert_data.get('severity')

        # Get notification targets
        targets = await self._get_notification_targets(
            alert_data.get('device_id'),
            severity
        )

        # Send push notifications for high/critical
        if severity in ['high', 'critical']:
            await self.notification_service.send_push(
                targets.get('push', []),
                title=f"IoT Alert: {alert_data.get('alert_type')}",
                body=alert_data.get('message'),
                data={'alert_id': alert_data.get('alert_id')},
            )

        # Send SMS for critical
        if severity == 'critical':
            await self.notification_service.send_sms(
                targets.get('sms', []),
                message=f"CRITICAL: {alert_data.get('message')}",
            )

        # Always send to Odoo for logging
        await self.notification_service.notify_odoo(alert_data)

    async def _get_notification_targets(
        self,
        device_id: str,
        severity: str
    ) -> Dict:
        """Get notification targets from Odoo"""
        # This would query Odoo for users subscribed to alerts
        # For now return empty dict
        return {'push': [], 'sms': [], 'email': []}
```

#### Dev 2 Tasks (16 hours over Days 255-256)

**WebSocket Hub & Live Dashboard**

```python
# services/iot_consumer/app/websocket_hub.py

import asyncio
import logging
import json
from typing import Dict, Set, Any
from datetime import datetime

from fastapi import WebSocket, WebSocketDisconnect
from redis import asyncio as aioredis

logger = logging.getLogger(__name__)


class WebSocketHub:
    """WebSocket hub for real-time IoT updates"""

    def __init__(self, redis: aioredis.Redis):
        self.redis = redis
        self.connections: Dict[str, Set[WebSocket]] = {}
        self._pubsub = None
        self._listener_task = None

    async def start(self):
        """Start the WebSocket hub"""
        self._pubsub = self.redis.pubsub()
        await self._pubsub.psubscribe("ws:*")
        self._listener_task = asyncio.create_task(self._listen())
        logger.info("WebSocket hub started")

    async def stop(self):
        """Stop the WebSocket hub"""
        if self._listener_task:
            self._listener_task.cancel()
        if self._pubsub:
            await self._pubsub.unsubscribe()
            await self._pubsub.close()

    async def connect(self, websocket: WebSocket, channel: str):
        """Connect a WebSocket to a channel"""
        await websocket.accept()

        if channel not in self.connections:
            self.connections[channel] = set()
        self.connections[channel].add(websocket)

        logger.info(f"WebSocket connected to channel: {channel}")

    async def disconnect(self, websocket: WebSocket, channel: str):
        """Disconnect a WebSocket from a channel"""
        if channel in self.connections:
            self.connections[channel].discard(websocket)
            if not self.connections[channel]:
                del self.connections[channel]

        logger.info(f"WebSocket disconnected from channel: {channel}")

    async def broadcast(self, channel: str, message: Dict[str, Any]):
        """Broadcast message to all connections on a channel"""
        # Publish to Redis for distributed scaling
        await self.redis.publish(
            f"ws:{channel}",
            json.dumps(message)
        )

    async def _listen(self):
        """Listen for messages from Redis pubsub"""
        try:
            async for message in self._pubsub.listen():
                if message['type'] == 'pmessage':
                    channel = message['channel'].decode().replace('ws:', '')
                    data = message['data'].decode()

                    await self._send_to_channel(channel, data)
        except asyncio.CancelledError:
            pass

    async def _send_to_channel(self, channel: str, data: str):
        """Send data to all WebSocket connections on a channel"""
        if channel in self.connections:
            dead_connections = set()

            for websocket in self.connections[channel]:
                try:
                    await websocket.send_text(data)
                except Exception:
                    dead_connections.add(websocket)

            # Clean up dead connections
            for ws in dead_connections:
                self.connections[channel].discard(ws)


# services/iot_api/app/routers/websocket.py

from fastapi import APIRouter, WebSocket, WebSocketDisconnect, Depends
from app.dependencies import get_ws_hub, get_current_user

router = APIRouter(prefix="/ws", tags=["websocket"])


@router.websocket("/devices/{device_id}")
async def device_websocket(
    websocket: WebSocket,
    device_id: str,
    ws_hub: WebSocketHub = Depends(get_ws_hub),
):
    """WebSocket endpoint for device real-time updates"""
    channel = f"device:{device_id}"
    await ws_hub.connect(websocket, channel)

    try:
        while True:
            # Keep connection alive, handle client messages
            data = await websocket.receive_text()
            # Handle ping/pong or commands
            if data == "ping":
                await websocket.send_text("pong")
    except WebSocketDisconnect:
        await ws_hub.disconnect(websocket, channel)


@router.websocket("/dashboard")
async def dashboard_websocket(
    websocket: WebSocket,
    ws_hub: WebSocketHub = Depends(get_ws_hub),
):
    """WebSocket endpoint for dashboard overview"""
    channels = ["alerts:active", "devices:status", "sensors:summary"]

    for channel in channels:
        await ws_hub.connect(websocket, channel)

    try:
        while True:
            data = await websocket.receive_text()
            if data == "ping":
                await websocket.send_text("pong")
    except WebSocketDisconnect:
        for channel in channels:
            await ws_hub.disconnect(websocket, channel)


@router.websocket("/alerts")
async def alerts_websocket(
    websocket: WebSocket,
    ws_hub: WebSocketHub = Depends(get_ws_hub),
):
    """WebSocket endpoint for real-time alerts"""
    channel = "alerts:stream"
    await ws_hub.connect(websocket, channel)

    try:
        while True:
            data = await websocket.receive_text()
            if data == "ping":
                await websocket.send_text("pong")
    except WebSocketDisconnect:
        await ws_hub.disconnect(websocket, channel)
```

**FastAPI IoT Dashboard Endpoints**

```python
# services/iot_api/app/routers/dashboard.py

from fastapi import APIRouter, Depends, Query
from datetime import datetime, timedelta
from typing import Optional

from app.dependencies import get_db_pool, get_redis, get_alert_engine
from app.schemas import DashboardSummary, DeviceStatus, AlertResponse

router = APIRouter(prefix="/api/v1/iot/dashboard", tags=["iot-dashboard"])


@router.get("/summary", response_model=DashboardSummary)
async def get_dashboard_summary(
    db_pool = Depends(get_db_pool),
    redis = Depends(get_redis),
):
    """Get IoT dashboard summary"""
    async with db_pool.acquire() as conn:
        # Device counts
        device_stats = await conn.fetchrow("""
            SELECT
                COUNT(*) as total_devices,
                SUM(CASE WHEN is_online THEN 1 ELSE 0 END) as online_devices
            FROM device_status
            WHERE time > NOW() - INTERVAL '5 minutes'
        """)

        # Alert counts by severity
        alert_stats = await conn.fetch("""
            SELECT severity, COUNT(*) as count
            FROM alert_history
            WHERE state = 'new'
            AND time > NOW() - INTERVAL '24 hours'
            GROUP BY severity
        """)

        # Average temperature last hour
        temp_avg = await conn.fetchval("""
            SELECT AVG(temperature_celsius)
            FROM temperature_readings
            WHERE time > NOW() - INTERVAL '1 hour'
        """)

        # Total milk collected today
        milk_total = await conn.fetchval("""
            SELECT SUM(volume_liters)
            FROM milk_meter_readings
            WHERE time > NOW() - INTERVAL '24 hours'
            AND is_complete = true
        """)

    return DashboardSummary(
        total_devices=device_stats['total_devices'] or 0,
        online_devices=device_stats['online_devices'] or 0,
        offline_devices=(device_stats['total_devices'] or 0) -
                       (device_stats['online_devices'] or 0),
        alerts_by_severity={
            row['severity']: row['count'] for row in alert_stats
        },
        avg_temperature=round(temp_avg or 0, 2),
        total_milk_today=round(milk_total or 0, 2),
    )


@router.get("/devices/status")
async def get_devices_status(
    device_type: Optional[str] = None,
    farm_id: Optional[int] = None,
    redis = Depends(get_redis),
):
    """Get real-time device status"""
    # Get all device keys from Redis
    device_keys = await redis.keys("device:*:status")

    devices = []
    for key in device_keys:
        status = await redis.hgetall(key)
        if status:
            device_id = key.split(':')[1]

            # Apply filters
            if device_type and status.get('device_type') != device_type:
                continue
            if farm_id and status.get('farm_id') != str(farm_id):
                continue

            devices.append({
                'device_id': device_id,
                'is_online': status.get('is_online') == 'true',
                'last_seen': status.get('last_seen'),
                'latest_value': status.get('latest_value'),
                'battery_level': status.get('battery_level'),
                'signal_strength': status.get('signal_strength'),
            })

    return {'devices': devices}


@router.get("/temperature/chart")
async def get_temperature_chart_data(
    device_id: str,
    period: str = Query("24h", regex="^(1h|6h|24h|7d|30d)$"),
    db_pool = Depends(get_db_pool),
):
    """Get temperature chart data for a device"""
    intervals = {
        '1h': ('1 minute', '1 hour'),
        '6h': ('5 minutes', '6 hours'),
        '24h': ('15 minutes', '24 hours'),
        '7d': ('1 hour', '7 days'),
        '30d': ('6 hours', '30 days'),
    }
    bucket, lookback = intervals[period]

    async with db_pool.acquire() as conn:
        data = await conn.fetch(f"""
            SELECT
                time_bucket('{bucket}', time) as bucket,
                AVG(temperature_celsius) as avg_temp,
                MIN(temperature_celsius) as min_temp,
                MAX(temperature_celsius) as max_temp
            FROM temperature_readings
            WHERE device_id = $1
            AND time > NOW() - INTERVAL '{lookback}'
            GROUP BY bucket
            ORDER BY bucket
        """, device_id)

    return {
        'device_id': device_id,
        'period': period,
        'data': [
            {
                'timestamp': row['bucket'].isoformat(),
                'avg': round(row['avg_temp'], 2),
                'min': round(row['min_temp'], 2),
                'max': round(row['max_temp'], 2),
            }
            for row in data
        ]
    }


@router.get("/milk/production")
async def get_milk_production_data(
    farm_id: Optional[int] = None,
    period: str = Query("7d", regex="^(24h|7d|30d)$"),
    db_pool = Depends(get_db_pool),
):
    """Get milk production data"""
    intervals = {'24h': '1 hour', '7d': '1 day', '30d': '1 day'}
    bucket = intervals[period]
    lookback = period.replace('d', ' days').replace('h', ' hours')

    async with db_pool.acquire() as conn:
        query = f"""
            SELECT
                time_bucket('{bucket}', time) as bucket,
                SUM(volume_liters) as total_volume,
                COUNT(DISTINCT session_id) as sessions,
                AVG(fat_percent) as avg_fat,
                AVG(protein_percent) as avg_protein
            FROM milk_meter_readings
            WHERE is_complete = true
            AND time > NOW() - INTERVAL '{lookback}'
        """
        if farm_id:
            query += f" AND device_id IN (SELECT device_id FROM devices WHERE farm_id = {farm_id})"
        query += " GROUP BY bucket ORDER BY bucket"

        data = await conn.fetch(query)

    return {
        'period': period,
        'data': [
            {
                'timestamp': row['bucket'].isoformat(),
                'volume': round(row['total_volume'] or 0, 2),
                'sessions': row['sessions'],
                'avg_fat': round(row['avg_fat'] or 0, 2),
                'avg_protein': round(row['avg_protein'] or 0, 2),
            }
            for row in data
        ]
    }
```

#### Dev 3 Tasks (16 hours over Days 256-258)

**Mobile IoT Alerts & Push Notifications**

```dart
// lib/features/iot/presentation/pages/iot_dashboard_page.dart

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../bloc/iot_dashboard_bloc.dart';
import '../widgets/device_grid.dart';
import '../widgets/alert_list.dart';
import '../widgets/iot_stats_cards.dart';

class IoTDashboardPage extends StatelessWidget {
  const IoTDashboardPage({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (context) => context.read<IoTDashboardBloc>()
        ..add(const IoTDashboardEvent.load()),
      child: const IoTDashboardView(),
    );
  }
}

class IoTDashboardView extends StatelessWidget {
  const IoTDashboardView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('IoT Dashboard'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () => context
                .read<IoTDashboardBloc>()
                .add(const IoTDashboardEvent.refresh()),
          ),
          IconButton(
            icon: const Icon(Icons.notifications),
            onPressed: () => Navigator.pushNamed(context, '/iot/alerts'),
          ),
        ],
      ),
      body: BlocBuilder<IoTDashboardBloc, IoTDashboardState>(
        builder: (context, state) {
          if (state.isLoading) {
            return const Center(child: CircularProgressIndicator());
          }

          if (state.error != null) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text('Error: ${state.error}'),
                  ElevatedButton(
                    onPressed: () => context
                        .read<IoTDashboardBloc>()
                        .add(const IoTDashboardEvent.refresh()),
                    child: const Text('Retry'),
                  ),
                ],
              ),
            );
          }

          return RefreshIndicator(
            onRefresh: () async {
              context
                  .read<IoTDashboardBloc>()
                  .add(const IoTDashboardEvent.refresh());
            },
            child: CustomScrollView(
              slivers: [
                // Stats Cards
                SliverToBoxAdapter(
                  child: IoTStatsCards(
                    onlineCount: state.onlineDeviceCount,
                    offlineCount: state.offlineDeviceCount,
                    alertCount: state.alertCount,
                  ),
                ),

                // Active Alerts
                if (state.activeAlerts.isNotEmpty) ...[
                  const SliverToBoxAdapter(
                    child: Padding(
                      padding: EdgeInsets.all(16),
                      child: Text(
                        'Active Alerts',
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
                  SliverToBoxAdapter(
                    child: AlertList(
                      alerts: state.activeAlerts.take(5).toList(),
                      onAcknowledge: (alertId) => context
                          .read<IoTDashboardBloc>()
                          .add(IoTDashboardEvent.acknowledgeAlert(alertId)),
                    ),
                  ),
                ],

                // Device Type Filter
                SliverToBoxAdapter(
                  child: DeviceTypeFilter(
                    selectedType: state.selectedFilter,
                    onChanged: (type) => context
                        .read<IoTDashboardBloc>()
                        .add(IoTDashboardEvent.filterByType(type)),
                  ),
                ),

                // Device Grid
                SliverPadding(
                  padding: const EdgeInsets.all(16),
                  sliver: DeviceGrid(devices: state.filteredDevices),
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}

// lib/features/iot/presentation/widgets/alert_list.dart

class AlertList extends StatelessWidget {
  final List<Alert> alerts;
  final Function(String) onAcknowledge;

  const AlertList({
    super.key,
    required this.alerts,
    required this.onAcknowledge,
  });

  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      itemCount: alerts.length,
      itemBuilder: (context, index) {
        final alert = alerts[index];
        return AlertCard(
          alert: alert,
          onAcknowledge: () => onAcknowledge(alert.id),
        );
      },
    );
  }
}

class AlertCard extends StatelessWidget {
  final Alert alert;
  final VoidCallback onAcknowledge;

  const AlertCard({
    super.key,
    required this.alert,
    required this.onAcknowledge,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
      color: _getSeverityColor(alert.severity).withOpacity(0.1),
      child: ListTile(
        leading: Icon(
          _getSeverityIcon(alert.severity),
          color: _getSeverityColor(alert.severity),
        ),
        title: Text(alert.deviceName),
        subtitle: Text(
          alert.message,
          maxLines: 2,
          overflow: TextOverflow.ellipsis,
        ),
        trailing: alert.isActive
            ? IconButton(
                icon: const Icon(Icons.check),
                onPressed: onAcknowledge,
                tooltip: 'Acknowledge',
              )
            : Text(
                _formatTime(alert.createdAt),
                style: Theme.of(context).textTheme.bodySmall,
              ),
      ),
    );
  }

  Color _getSeverityColor(AlertSeverity severity) {
    switch (severity) {
      case AlertSeverity.critical:
        return Colors.red;
      case AlertSeverity.high:
        return Colors.orange;
      case AlertSeverity.medium:
        return Colors.amber;
      case AlertSeverity.low:
        return Colors.blue;
    }
  }

  IconData _getSeverityIcon(AlertSeverity severity) {
    switch (severity) {
      case AlertSeverity.critical:
        return Icons.error;
      case AlertSeverity.high:
        return Icons.warning;
      case AlertSeverity.medium:
        return Icons.info;
      case AlertSeverity.low:
        return Icons.info_outline;
    }
  }

  String _formatTime(DateTime time) {
    final diff = DateTime.now().difference(time);
    if (diff.inMinutes < 60) return '${diff.inMinutes}m ago';
    if (diff.inHours < 24) return '${diff.inHours}h ago';
    return '${diff.inDays}d ago';
  }
}
```

**Push Notification Service**

```dart
// lib/core/services/push_notification_service.dart

import 'dart:convert';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:injectable/injectable.dart';

@singleton
class PushNotificationService {
  final FirebaseMessaging _messaging = FirebaseMessaging.instance;
  final FlutterLocalNotificationsPlugin _localNotifications =
      FlutterLocalNotificationsPlugin();

  Future<void> initialize() async {
    // Request permissions
    await _messaging.requestPermission(
      alert: true,
      badge: true,
      sound: true,
      criticalAlert: true,
    );

    // Initialize local notifications
    const androidSettings = AndroidInitializationSettings('@mipmap/ic_launcher');
    const iosSettings = DarwinInitializationSettings(
      requestAlertPermission: true,
      requestBadgePermission: true,
      requestSoundPermission: true,
      requestCriticalPermission: true,
    );

    await _localNotifications.initialize(
      const InitializationSettings(
        android: androidSettings,
        iOS: iosSettings,
      ),
      onDidReceiveNotificationResponse: _onNotificationTapped,
    );

    // Create notification channels for Android
    await _createNotificationChannels();

    // Handle foreground messages
    FirebaseMessaging.onMessage.listen(_handleForegroundMessage);

    // Handle background messages
    FirebaseMessaging.onBackgroundMessage(_handleBackgroundMessage);

    // Handle notification taps
    FirebaseMessaging.onMessageOpenedApp.listen(_onNotificationOpened);
  }

  Future<String?> getToken() async {
    return await _messaging.getToken();
  }

  Future<void> subscribeToTopic(String topic) async {
    await _messaging.subscribeToTopic(topic);
  }

  Future<void> unsubscribeFromTopic(String topic) async {
    await _messaging.unsubscribeFromTopic(topic);
  }

  Future<void> _createNotificationChannels() async {
    const criticalChannel = AndroidNotificationChannel(
      'iot_critical',
      'Critical IoT Alerts',
      description: 'Critical alerts from IoT sensors',
      importance: Importance.max,
      playSound: true,
      enableVibration: true,
    );

    const highChannel = AndroidNotificationChannel(
      'iot_high',
      'High Priority IoT Alerts',
      description: 'High priority alerts from IoT sensors',
      importance: Importance.high,
    );

    const normalChannel = AndroidNotificationChannel(
      'iot_normal',
      'IoT Notifications',
      description: 'General IoT notifications',
      importance: Importance.defaultImportance,
    );

    final androidPlugin =
        _localNotifications.resolvePlatformSpecificImplementation<
            AndroidFlutterLocalNotificationsPlugin>();

    await androidPlugin?.createNotificationChannel(criticalChannel);
    await androidPlugin?.createNotificationChannel(highChannel);
    await androidPlugin?.createNotificationChannel(normalChannel);
  }

  Future<void> _handleForegroundMessage(RemoteMessage message) async {
    final data = message.data;
    final notification = message.notification;

    if (notification != null) {
      final severity = data['severity'] ?? 'normal';
      final channelId = _getChannelId(severity);

      await _localNotifications.show(
        message.hashCode,
        notification.title,
        notification.body,
        NotificationDetails(
          android: AndroidNotificationDetails(
            channelId,
            _getChannelName(severity),
            importance: _getImportance(severity),
            priority: _getPriority(severity),
          ),
          iOS: DarwinNotificationDetails(
            presentAlert: true,
            presentBadge: true,
            presentSound: true,
            interruptionLevel: severity == 'critical'
                ? InterruptionLevel.critical
                : InterruptionLevel.active,
          ),
        ),
        payload: jsonEncode(data),
      );
    }
  }

  void _onNotificationTapped(NotificationResponse response) {
    if (response.payload != null) {
      final data = jsonDecode(response.payload!);
      _navigateToAlert(data);
    }
  }

  void _onNotificationOpened(RemoteMessage message) {
    _navigateToAlert(message.data);
  }

  void _navigateToAlert(Map<String, dynamic> data) {
    final alertId = data['alert_id'];
    if (alertId != null) {
      // Navigate to alert details
      // This would use your navigation service
    }
  }

  String _getChannelId(String severity) {
    switch (severity) {
      case 'critical':
        return 'iot_critical';
      case 'high':
        return 'iot_high';
      default:
        return 'iot_normal';
    }
  }

  String _getChannelName(String severity) {
    switch (severity) {
      case 'critical':
        return 'Critical IoT Alerts';
      case 'high':
        return 'High Priority IoT Alerts';
      default:
        return 'IoT Notifications';
    }
  }

  Importance _getImportance(String severity) {
    switch (severity) {
      case 'critical':
        return Importance.max;
      case 'high':
        return Importance.high;
      default:
        return Importance.defaultImportance;
    }
  }

  Priority _getPriority(String severity) {
    switch (severity) {
      case 'critical':
        return Priority.max;
      case 'high':
        return Priority.high;
      default:
        return Priority.defaultPriority;
    }
  }
}

@pragma('vm:entry-point')
Future<void> _handleBackgroundMessage(RemoteMessage message) async {
  // Handle background message
  print('Background message: ${message.messageId}');
}
```

---

### Day 259-260: Device Management & Integration Testing

#### Dev 2 Tasks (8 hours) - Day 259

**Historical Analytics API**

```python
# services/iot_api/app/routers/analytics.py

from fastapi import APIRouter, Depends, Query
from datetime import datetime, timedelta
from typing import Optional, List

from app.dependencies import get_db_pool

router = APIRouter(prefix="/api/v1/iot/analytics", tags=["iot-analytics"])


@router.get("/temperature/trends")
async def get_temperature_trends(
    device_ids: List[str] = Query(None),
    location_type: Optional[str] = None,
    days: int = Query(7, ge=1, le=90),
    db_pool = Depends(get_db_pool),
):
    """Get temperature trends with anomaly detection"""
    async with db_pool.acquire() as conn:
        query = """
            WITH hourly_stats AS (
                SELECT
                    time_bucket('1 hour', time) as hour,
                    device_id,
                    AVG(temperature_celsius) as avg_temp,
                    STDDEV(temperature_celsius) as stddev_temp,
                    COUNT(*) as reading_count,
                    SUM(CASE WHEN is_alarm THEN 1 ELSE 0 END) as alarm_count
                FROM temperature_readings
                WHERE time > NOW() - INTERVAL '%s days'
        """
        params = [days]

        if device_ids:
            query += " AND device_id = ANY($2)"
            params.append(device_ids)

        query += """
                GROUP BY hour, device_id
            ),
            daily_trends AS (
                SELECT
                    date_trunc('day', hour) as day,
                    device_id,
                    AVG(avg_temp) as daily_avg,
                    MIN(avg_temp) as daily_min,
                    MAX(avg_temp) as daily_max,
                    SUM(alarm_count) as daily_alarms
                FROM hourly_stats
                GROUP BY day, device_id
            )
            SELECT * FROM daily_trends ORDER BY day, device_id
        """

        data = await conn.fetch(query, *params)

    return {
        'period_days': days,
        'trends': [
            {
                'date': row['day'].isoformat(),
                'device_id': row['device_id'],
                'avg_temp': round(row['daily_avg'], 2),
                'min_temp': round(row['daily_min'], 2),
                'max_temp': round(row['daily_max'], 2),
                'alarm_count': row['daily_alarms'],
            }
            for row in data
        ]
    }


@router.get("/milk/quality-report")
async def get_milk_quality_report(
    farm_id: Optional[int] = None,
    start_date: Optional[datetime] = None,
    end_date: Optional[datetime] = None,
    db_pool = Depends(get_db_pool),
):
    """Get milk quality analysis report"""
    if not start_date:
        start_date = datetime.utcnow() - timedelta(days=30)
    if not end_date:
        end_date = datetime.utcnow()

    async with db_pool.acquire() as conn:
        data = await conn.fetch("""
            SELECT
                date_trunc('day', time) as day,
                AVG(fat_percent) as avg_fat,
                AVG(protein_percent) as avg_protein,
                AVG(somatic_cell_count) as avg_scc,
                AVG(conductivity) as avg_conductivity,
                SUM(volume_liters) as total_volume,
                COUNT(DISTINCT animal_id) as animals_milked
            FROM milk_meter_readings
            WHERE time BETWEEN $1 AND $2
            AND is_complete = true
            GROUP BY day
            ORDER BY day
        """, start_date, end_date)

    # Calculate quality grades
    report_data = []
    for row in data:
        quality_grade = _calculate_quality_grade(
            row['avg_fat'],
            row['avg_protein'],
            row['avg_scc']
        )
        report_data.append({
            'date': row['day'].isoformat(),
            'avg_fat_percent': round(row['avg_fat'] or 0, 2),
            'avg_protein_percent': round(row['avg_protein'] or 0, 2),
            'avg_scc': int(row['avg_scc'] or 0),
            'avg_conductivity': round(row['avg_conductivity'] or 0, 2),
            'total_volume_liters': round(row['total_volume'] or 0, 2),
            'animals_milked': row['animals_milked'],
            'quality_grade': quality_grade,
        })

    return {
        'start_date': start_date.isoformat(),
        'end_date': end_date.isoformat(),
        'report': report_data,
    }


def _calculate_quality_grade(fat: float, protein: float, scc: int) -> str:
    """Calculate milk quality grade based on parameters"""
    if not all([fat, protein, scc]):
        return 'N/A'

    score = 0
    if fat >= 3.5:
        score += 30
    elif fat >= 3.0:
        score += 20
    else:
        score += 10

    if protein >= 3.2:
        score += 30
    elif protein >= 3.0:
        score += 20
    else:
        score += 10

    if scc < 200000:
        score += 40
    elif scc < 400000:
        score += 25
    else:
        score += 10

    if score >= 90:
        return 'A'
    elif score >= 75:
        return 'B'
    elif score >= 60:
        return 'C'
    else:
        return 'D'
```

#### Dev 3 Tasks (8 hours) - Day 259

**Device Management UI**

```dart
// lib/features/iot/presentation/pages/device_management_page.dart

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../bloc/device_management_bloc.dart';
import '../../domain/entities/device.dart';

class DeviceManagementPage extends StatelessWidget {
  const DeviceManagementPage({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (context) => context.read<DeviceManagementBloc>()
        ..add(const DeviceManagementEvent.load()),
      child: Scaffold(
        appBar: AppBar(
          title: const Text('Device Management'),
          actions: [
            IconButton(
              icon: const Icon(Icons.add),
              onPressed: () => _showAddDeviceDialog(context),
            ),
          ],
        ),
        body: BlocBuilder<DeviceManagementBloc, DeviceManagementState>(
          builder: (context, state) {
            return state.when(
              initial: () => const SizedBox(),
              loading: () => const Center(child: CircularProgressIndicator()),
              loaded: (devices) => DeviceListView(devices: devices),
              error: (message) => Center(child: Text('Error: $message')),
            );
          },
        ),
      ),
    );
  }

  void _showAddDeviceDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) => const AddDeviceDialog(),
    );
  }
}

class DeviceListView extends StatelessWidget {
  final List<Device> devices;

  const DeviceListView({super.key, required this.devices});

  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      itemCount: devices.length,
      itemBuilder: (context, index) {
        final device = devices[index];
        return DeviceListTile(device: device);
      },
    );
  }
}

class DeviceListTile extends StatelessWidget {
  final Device device;

  const DeviceListTile({super.key, required this.device});

  @override
  Widget build(BuildContext context) {
    return ListTile(
      leading: CircleAvatar(
        backgroundColor: device.isOnline ? Colors.green : Colors.grey,
        child: Icon(
          _getDeviceIcon(device.deviceType),
          color: Colors.white,
        ),
      ),
      title: Text(device.name),
      subtitle: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text('ID: ${device.deviceId}'),
          if (device.locationDescription != null)
            Text(device.locationDescription!),
        ],
      ),
      trailing: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        crossAxisAlignment: CrossAxisAlignment.end,
        children: [
          _buildStatusChip(device),
          if (device.batteryLevel != null)
            Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(
                  Icons.battery_full,
                  size: 16,
                  color: device.hasLowBattery ? Colors.red : Colors.grey,
                ),
                Text('${device.batteryLevel}%'),
              ],
            ),
        ],
      ),
      onTap: () => Navigator.pushNamed(
        context,
        '/iot/device/${device.id}',
      ),
    );
  }

  IconData _getDeviceIcon(DeviceType type) {
    switch (type) {
      case DeviceType.temperature:
        return Icons.thermostat;
      case DeviceType.milkMeter:
        return Icons.water_drop;
      case DeviceType.phSensor:
        return Icons.science;
      default:
        return Icons.sensors;
    }
  }

  Widget _buildStatusChip(Device device) {
    Color color;
    String text;

    if (device.state == DeviceState.active) {
      color = device.isOnline ? Colors.green : Colors.red;
      text = device.isOnline ? 'Online' : 'Offline';
    } else {
      color = Colors.grey;
      text = device.state.name;
    }

    return Chip(
      label: Text(text, style: const TextStyle(fontSize: 12)),
      backgroundColor: color.withOpacity(0.2),
      labelStyle: TextStyle(color: color),
      padding: EdgeInsets.zero,
      materialTapTargetSize: MaterialTapTargetSize.shrinkWrap,
    );
  }
}
```

#### All Developers (Day 260) - Integration Testing

**End-to-End Test Scenarios**

```python
# tests/integration/test_iot_integration.py

import pytest
import asyncio
import json
from datetime import datetime
import asyncio_mqtt as aiomqtt

from services.iot_consumer.app.main import IoTConsumerService


@pytest.fixture
async def iot_service():
    """Setup IoT consumer service for testing"""
    service = IoTConsumerService()
    await service.initialize()
    yield service
    await service.shutdown()


@pytest.mark.asyncio
async def test_temperature_sensor_flow(iot_service):
    """Test complete temperature sensor data flow"""
    device_id = "TEST-TEMP-001"

    # Publish test data
    async with aiomqtt.Client("localhost", 1883) as client:
        await client.publish(
            f"smartdairy/sensors/temperature/{device_id}/data",
            json.dumps({
                "temperature": 5.5,
                "humidity": 85,
                "location_type": "storage",
                "timestamp": datetime.utcnow().isoformat(),
            }),
            qos=1,
        )

    # Wait for processing
    await asyncio.sleep(1)

    # Verify data stored in TimescaleDB
    async with iot_service.db_pool.acquire() as conn:
        result = await conn.fetchrow("""
            SELECT * FROM temperature_readings
            WHERE device_id = $1
            ORDER BY time DESC LIMIT 1
        """, device_id)

        assert result is not None
        assert result['temperature_celsius'] == 5.5
        assert result['humidity_percent'] == 85

    # Verify Redis cache updated
    cache = await iot_service.redis.hgetall(f"device:{device_id}:latest")
    assert cache['temperature'] == '5.5'


@pytest.mark.asyncio
async def test_temperature_alert_generation(iot_service):
    """Test alert generation for temperature threshold breach"""
    device_id = "TEST-TEMP-002"

    # Publish high temperature data
    async with aiomqtt.Client("localhost", 1883) as client:
        await client.publish(
            f"smartdairy/sensors/temperature/{device_id}/data",
            json.dumps({
                "temperature": 12.0,  # Above threshold
                "location_type": "storage",
            }),
            qos=1,
        )

    await asyncio.sleep(2)

    # Verify alert created
    alerts = await iot_service.alert_engine.get_active_alerts(
        device_id=device_id
    )
    assert len(alerts) > 0
    assert alerts[0]['alert_type'] == 'high_temperature'
    assert alerts[0]['severity'] in ['medium', 'high']


@pytest.mark.asyncio
async def test_milk_meter_session_flow(iot_service):
    """Test complete milking session flow"""
    device_id = "TEST-MILK-001"
    session_id = f"session_{datetime.utcnow().timestamp()}"

    async with aiomqtt.Client("localhost", 1883) as client:
        # Session start
        await client.publish(
            f"smartdairy/sensors/milk_meter/{device_id}/data",
            json.dumps({
                "type": "session_start",
                "session_id": session_id,
                "animal_id": 123,
            }),
            qos=1,
        )

        # Simulate readings
        for i in range(5):
            await asyncio.sleep(0.5)
            await client.publish(
                f"smartdairy/sensors/milk_meter/{device_id}/data",
                json.dumps({
                    "type": "reading",
                    "session_id": session_id,
                    "volume": 0.5,
                    "flow_rate": 2.5,
                    "temperature": 37.5,
                }),
                qos=1,
            )

        # Session end
        await client.publish(
            f"smartdairy/sensors/milk_meter/{device_id}/data",
            json.dumps({
                "type": "session_end",
                "session_id": session_id,
            }),
            qos=1,
        )

    await asyncio.sleep(2)

    # Verify session data
    async with iot_service.db_pool.acquire() as conn:
        results = await conn.fetch("""
            SELECT * FROM milk_meter_readings
            WHERE session_id = $1
            ORDER BY time
        """, session_id)

        assert len(results) == 5
        total_volume = sum(r['volume_liters'] for r in results)
        assert abs(total_volume - 2.5) < 0.01


@pytest.mark.asyncio
async def test_websocket_real_time_updates(iot_service):
    """Test WebSocket real-time data broadcast"""
    import websockets

    device_id = "TEST-WS-001"
    received_messages = []

    async def ws_client():
        async with websockets.connect(
            f"ws://localhost:8000/ws/devices/{device_id}"
        ) as ws:
            try:
                while True:
                    msg = await asyncio.wait_for(ws.recv(), timeout=5)
                    received_messages.append(json.loads(msg))
            except asyncio.TimeoutError:
                pass

    # Start WebSocket client
    ws_task = asyncio.create_task(ws_client())

    await asyncio.sleep(1)

    # Publish sensor data
    async with aiomqtt.Client("localhost", 1883) as client:
        await client.publish(
            f"smartdairy/sensors/temperature/{device_id}/data",
            json.dumps({"temperature": 4.0}),
            qos=1,
        )

    await asyncio.sleep(2)
    ws_task.cancel()

    # Verify WebSocket received the update
    assert len(received_messages) > 0
    assert received_messages[0]['type'] == 'temperature_reading'
    assert received_messages[0]['temperature'] == 4.0
```

---

## 5. Milestone Summary

### Key Deliverables Completed

| # | Deliverable | Status |
|---|-------------|--------|
| 1 | MQTT Broker (Mosquitto) configured with TLS | ✅ |
| 2 | TimescaleDB schema with hypertables | ✅ |
| 3 | IoT Consumer Service | ✅ |
| 4 | Temperature sensor handler | ✅ |
| 5 | Milk meter handler | ✅ |
| 6 | Alert engine with notifications | ✅ |
| 7 | WebSocket real-time updates | ✅ |
| 8 | Mobile IoT dashboard | ✅ |
| 9 | Push notification service | ✅ |
| 10 | Historical analytics API | ✅ |
| 11 | Device management UI | ✅ |
| 12 | Integration tests | ✅ |

### Performance Benchmarks

| Metric | Target | Achieved |
|--------|--------|----------|
| Message throughput | 10,000/min | ✅ |
| Sensor data latency | <30s | ✅ |
| Alert generation | <60s | ✅ |
| WebSocket latency | <1s | ✅ |
| Push notification | <5s | ✅ |

### Dependencies for Next Milestone

- IoT data available for analytics (Milestone 27)
- Device registry for farm management (Milestone 28)
- Real-time monitoring for performance optimization (Milestone 29)

---

## 6. Risk Register

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| MQTT broker overload | Low | High | Auto-scaling, message queuing |
| Sensor data gaps | Medium | Medium | Data quality monitoring, interpolation |
| Alert fatigue | Medium | Medium | Smart alerting with cooldowns |
| Network connectivity | High | Medium | Offline buffering in gateways |

---

*Document Version: 1.0 | Last Updated: Day 260 | Next Review: Day 270*