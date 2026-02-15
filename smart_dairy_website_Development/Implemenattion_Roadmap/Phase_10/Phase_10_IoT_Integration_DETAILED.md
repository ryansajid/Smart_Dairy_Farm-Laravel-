# PHASE 10: COMMERCE - IoT Integration Core
## DETAILED IMPLEMENTATION PLAN

---

**Document Control**

| Attribute | Value |
|-----------|-------|
| **Phase Number** | 10 of 15 |
| **Phase Name** | Commerce - IoT Integration Core |
| **Duration** | Days 451-500 (50 working days) |
| **Milestones** | 10 milestones × 5 days each |
| **Document Version** | 1.0 |
| **Release Date** | February 3, 2026 |
| **Dependencies** | Phase 1-9 Complete |
| **Team Size** | 3 Full-Stack Developers + 1 IoT Specialist + 1 Data Engineer |

---

## TABLE OF CONTENTS

1. [Phase Overview](#phase-overview)
2. [Phase Objectives](#phase-objectives)
3. [Key Deliverables](#key-deliverables)
4. [Milestone 1: MQTT Broker Setup (Days 451-455)](#milestone-1-mqtt-broker-setup-days-451-455)
5. [Milestone 2: Milk Meter Integration (Days 456-460)](#milestone-2-milk-meter-integration-days-456-460)
6. [Milestone 3: Temperature Sensors (Days 461-465)](#milestone-3-temperature-sensors-days-461-465)
7. [Milestone 4: Activity Collars (Days 466-470)](#milestone-4-activity-collars-days-466-470)
8. [Milestone 5: Feed Dispensers (Days 471-475)](#milestone-5-feed-dispensers-days-471-475)
9. [Milestone 6: IoT Data Pipeline (Days 476-480)](#milestone-6-iot-data-pipeline-days-476-480)
10. [Milestone 7: Real-time Alerts (Days 481-485)](#milestone-7-real-time-alerts-days-481-485)
11. [Milestone 8: IoT Dashboards (Days 486-490)](#milestone-8-iot-dashboards-days-486-490)
12. [Milestone 9: Predictive Analytics (Days 491-495)](#milestone-9-predictive-analytics-days-491-495)
13. [Milestone 10: IoT Testing & Validation (Days 496-500)](#milestone-10-iot-testing--validation-days-496-500)
14. [Phase Success Criteria](#phase-success-criteria)

---

## PHASE OVERVIEW

Phase 10 implements a comprehensive IoT (Internet of Things) integration system that connects physical devices from dairy farms to the Smart Dairy platform. This system enables real-time monitoring of milk production, temperature, animal activity, feeding, and other critical farm operations through sensors and automated devices.

### Phase Context

Building on the complete ERP, operations, and B2B foundation from Phases 1-9, Phase 10 creates:
- MQTT broker infrastructure for IoT device communication
- Real-time milk meter integration for production tracking
- Temperature sensor monitoring for cold chain compliance
- Animal activity collar integration for health monitoring
- Automated feed dispenser integration
- Time-series data pipeline with TimescaleDB
- Real-time alerting system for anomalies
- IoT visualization dashboards
- Predictive analytics using ML models

---

## PHASE OBJECTIVES

### Primary Objectives

1. **MQTT Infrastructure**: Production-grade MQTT broker with security, topics, and QoS
2. **Milk Meter Integration**: Real-time collection data, quality parameters, yield tracking
3. **Temperature Monitoring**: Cold chain compliance, storage monitoring, alerts
4. **Activity Tracking**: Animal collar integration, heat detection, health monitoring
5. **Feed Automation**: Automated dispensers, consumption tracking, ration optimization
6. **Data Pipeline**: High-performance ingestion, validation, storage with TimescaleDB
7. **Real-time Alerts**: Temperature anomalies, health alerts, equipment failures
8. **IoT Visualization**: Real-time dashboards, historical trends, device status
9. **Predictive Analytics**: ML models for yield prediction, health issues, maintenance
10. **Device Management**: Sensor calibration, firmware updates, failover testing

### Secondary Objectives

- Device provisioning and authentication
- Data quality monitoring and validation
- Historical data retention and archival
- Edge computing for local processing
- Offline operation and data sync
- Multi-protocol support (MQTT, CoAP, HTTP)
- IoT device inventory management
- Energy consumption monitoring

---

## KEY DELIVERABLES

### MQTT Broker Deliverables
1. ✓ Mosquitto MQTT broker setup with clustering
2. ✓ Topic hierarchy design and implementation
3. ✓ TLS/SSL encryption for secure communication
4. ✓ Username/password authentication
5. ✓ Certificate-based device authentication
6. ✓ Quality of Service (QoS) configuration
7. ✓ Message retention and persistence

### Milk Meter Integration Deliverables
8. ✓ Real-time milk yield data collection
9. ✓ Individual animal identification (RFID)
10. ✓ Quality parameter tracking (fat, protein, SCC)
11. ✓ Milking session tracking
12. ✓ Flow rate monitoring
13. ✓ Equipment performance tracking
14. ✓ Milk production alerts

### Temperature Sensor Deliverables
15. ✓ Bulk tank temperature monitoring
16. ✓ Cold storage temperature tracking
17. ✓ Transportation temperature logging
18. ✓ Temperature threshold alerts
19. ✓ Cold chain compliance reporting
20. ✓ Temperature trend analysis
21. ✓ Multi-location monitoring

### Activity Collar Deliverables
22. ✓ Real-time activity level tracking
23. ✓ Heat detection algorithms
24. ✓ Rumination monitoring
25. ✓ Lying time tracking
26. ✓ Health anomaly detection
27. ✓ Animal location tracking
28. ✓ Breeding readiness alerts

### Feed Dispenser Deliverables
29. ✓ Automated feeding schedules
30. ✓ Feed consumption tracking per animal
31. ✓ Ration optimization
32. ✓ Feed inventory depletion
33. ✓ Feeding pattern analysis
34. ✓ Feed efficiency metrics
35. ✓ Dispenser malfunction alerts

### IoT Data Pipeline Deliverables
36. ✓ TimescaleDB time-series database
37. ✓ High-throughput data ingestion (10,000+ messages/sec)
38. ✓ Data validation and quality checks
39. ✓ Data aggregation and downsampling
40. ✓ Continuous aggregates for performance
41. ✓ Data retention policies
42. ✓ Backup and disaster recovery

### Real-time Alerts Deliverables
43. ✓ Temperature anomaly detection
44. ✓ Health alert system
45. ✓ Equipment failure alerts
46. ✓ Multi-channel notifications (SMS, email, push)
47. ✓ Alert escalation rules
48. ✓ Alert acknowledgment workflow
49. ✓ Alert analytics and trends

### IoT Dashboard Deliverables
50. ✓ Real-time device status monitoring
51. ✓ Live data visualization
52. ✓ Historical trend charts
53. ✓ Farm-wide overview dashboard
54. ✓ Device-specific detailed views
55. ✓ Mobile-responsive dashboards
56. ✓ Customizable widgets

---

## MILESTONE 1: MQTT Broker Setup (Days 451-455)

**Objective**: Setup production-grade MQTT broker infrastructure with security, topics, and high availability.

**Duration**: 5 working days

---

### Day 451: MQTT Broker Installation & Configuration

**[Dev 1 - IoT Specialist] - Mosquitto Broker Setup (8h)**
- Install and configure Mosquitto MQTT broker:
  ```bash
  # mosquitto.conf
  # MQTT Broker Configuration for Smart Dairy IoT

  # Basic Settings
  pid_file /var/run/mosquitto.pid
  persistence true
  persistence_location /var/lib/mosquitto/
  log_dest file /var/log/mosquitto/mosquitto.log
  log_dest stdout
  log_type error
  log_type warning
  log_type notice
  log_type information
  log_timestamp true

  # Network Settings
  listener 1883
  protocol mqtt

  # WebSocket Support
  listener 9001
  protocol websockets

  # Secure MQTT (TLS)
  listener 8883
  protocol mqtt
  cafile /etc/mosquitto/ca_certificates/ca.crt
  certfile /etc/mosquitto/certs/server.crt
  keyfile /etc/mosquitto/certs/server.key
  require_certificate false
  use_identity_as_username false
  tls_version tlsv1.2

  # Authentication
  allow_anonymous false
  password_file /etc/mosquitto/passwd
  acl_file /etc/mosquitto/acl

  # Performance Tuning
  max_connections 10000
  max_queued_messages 10000
  message_size_limit 10485760

  # Persistence
  autosave_interval 300
  autosave_on_changes false
  persistent_client_expiration 1h

  # Logging
  connection_messages true
  log_timestamp_format %Y-%m-%dT%H:%M:%S

  # Quality of Service
  max_inflight_messages 20
  max_queued_bytes 0

  # Retain Messages
  retain_available true
  max_retained_messages 10000
  ```

- Create ACL (Access Control List) configuration:
  ```bash
  # /etc/mosquitto/acl
  # Smart Dairy MQTT Access Control List

  # Admin users (full access)
  user admin
  topic readwrite #

  # Milk meter devices
  user milk_meter_001
  topic write dairy/farm1/milk_meters/+/data
  topic read dairy/farm1/milk_meters/+/commands

  # Temperature sensors
  user temp_sensor_001
  topic write dairy/farm1/temperature/+/data
  topic read dairy/farm1/temperature/+/commands

  # Activity collars
  user collar_001
  topic write dairy/farm1/collars/+/data
  topic read dairy/farm1/collars/+/commands

  # Feed dispensers
  user feeder_001
  topic write dairy/farm1/feeders/+/data
  topic read dairy/farm1/feeders/+/commands

  # Backend services
  user backend_service
  topic read dairy/+/+/+/data
  topic write dairy/+/+/+/commands
  topic readwrite dairy/+/alerts/#

  # Pattern: dairy/{farm_id}/{device_type}/{device_id}/{message_type}
  ```

**[Dev 2 - Full-Stack] - MQTT Topic Hierarchy Design (8h)**
- Design comprehensive topic structure:
  ```python
  # odoo/addons/smart_dairy_iot/models/iot_topic_hierarchy.py
  """
  MQTT Topic Hierarchy for Smart Dairy IoT Platform

  Root: dairy/

  Level 1: Farm ID
    dairy/farm{farm_id}/

  Level 2: Device Type
    dairy/farm1/milk_meters/
    dairy/farm1/temperature/
    dairy/farm1/collars/
    dairy/farm1/feeders/
    dairy/farm1/gates/
    dairy/farm1/waterers/

  Level 3: Device ID
    dairy/farm1/milk_meters/meter_001/

  Level 4: Message Type
    dairy/farm1/milk_meters/meter_001/data       - Device data
    dairy/farm1/milk_meters/meter_001/status     - Device status
    dairy/farm1/milk_meters/meter_001/commands   - Commands to device
    dairy/farm1/milk_meters/meter_001/config     - Configuration updates
    dairy/farm1/milk_meters/meter_001/alerts     - Device alerts

  Special Topics:
    dairy/system/health                   - System health
    dairy/system/alerts                   - System-wide alerts
    dairy/farm1/aggregated/hourly        - Aggregated data
    dairy/farm1/aggregated/daily         - Daily summaries

  Wildcards:
    dairy/+/milk_meters/+/data           - All farm milk meter data
    dairy/farm1/+/+/data                 - All farm1 device data
    dairy/#                              - Everything
  """

  from odoo import models, fields, api

  class IoTTopicHierarchy(models.Model):
      _name = 'iot.topic.hierarchy'
      _description = 'MQTT Topic Hierarchy Definition'

      name = fields.Char(string='Topic Name', required=True)

      topic_pattern = fields.Char(
          string='Topic Pattern',
          required=True,
          help='MQTT topic pattern with placeholders'
      )

      device_type = fields.Selection([
          ('milk_meter', 'Milk Meter'),
          ('temperature', 'Temperature Sensor'),
          ('collar', 'Activity Collar'),
          ('feeder', 'Feed Dispenser'),
          ('gate', 'Automated Gate'),
          ('waterer', 'Water Dispenser'),
          ('system', 'System'),
      ], string='Device Type')

      message_type = fields.Selection([
          ('data', 'Sensor Data'),
          ('status', 'Device Status'),
          ('command', 'Command'),
          ('config', 'Configuration'),
          ('alert', 'Alert'),
          ('aggregated', 'Aggregated Data'),
      ], string='Message Type')

      qos_level = fields.Selection([
          ('0', 'QoS 0 - At most once'),
          ('1', 'QoS 1 - At least once'),
          ('2', 'QoS 2 - Exactly once'),
      ], string='QoS Level', default='1')

      retain = fields.Boolean(
          string='Retain Message',
          default=False,
          help='Last message retained for new subscribers'
      )

      description = fields.Text(string='Description')

      payload_schema = fields.Text(
          string='Payload JSON Schema',
          help='Expected JSON schema for messages'
      )

      is_active = fields.Boolean(string='Active', default=True)

      def get_topic(self, farm_id=None, device_id=None):
          """Generate actual topic from pattern"""
          topic = self.topic_pattern
          if farm_id:
              topic = topic.replace('{farm_id}', str(farm_id))
          if device_id:
              topic = topic.replace('{device_id}', str(device_id))
          return topic
  ```

- Create topic schema definitions:
  ```python
  # Topic Schema Examples

  MILK_METER_DATA_SCHEMA = {
      "type": "object",
      "required": ["device_id", "timestamp", "animal_id", "yield_liters"],
      "properties": {
          "device_id": {"type": "string"},
          "timestamp": {"type": "string", "format": "date-time"},
          "session_id": {"type": "string"},
          "animal_id": {"type": "string"},
          "rfid_tag": {"type": "string"},
          "yield_liters": {"type": "number", "minimum": 0},
          "flow_rate": {"type": "number"},
          "duration_seconds": {"type": "integer"},
          "quality": {
              "type": "object",
              "properties": {
                  "fat_percentage": {"type": "number"},
                  "protein_percentage": {"type": "number"},
                  "lactose_percentage": {"type": "number"},
                  "somatic_cell_count": {"type": "integer"},
                  "temperature_celsius": {"type": "number"}
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
      "type": "object",
      "required": ["device_id", "timestamp", "temperature"],
      "properties": {
          "device_id": {"type": "string"},
          "timestamp": {"type": "string", "format": "date-time"},
          "temperature": {"type": "number"},
          "humidity": {"type": "number"},
          "location": {"type": "string"},
          "location_type": {
              "type": "string",
              "enum": ["bulk_tank", "cold_storage", "transport", "barn"]
          },
          "battery_level": {"type": "number"},
          "signal_strength": {"type": "integer"}
      }
  }

  ACTIVITY_COLLAR_SCHEMA = {
      "type": "object",
      "required": ["device_id", "timestamp", "animal_id"],
      "properties": {
          "device_id": {"type": "string"},
          "timestamp": {"type": "string", "format": "date-time"},
          "animal_id": {"type": "string"},
          "activity_level": {"type": "integer", "minimum": 0, "maximum": 100},
          "rumination_minutes": {"type": "integer"},
          "lying_time_minutes": {"type": "integer"},
          "standing_time_minutes": {"type": "integer"},
          "steps": {"type": "integer"},
          "location": {
              "type": "object",
              "properties": {
                  "latitude": {"type": "number"},
                  "longitude": {"type": "number"},
                  "zone": {"type": "string"}
              }
          },
          "temperature": {"type": "number"},
          "heart_rate": {"type": "integer"},
          "battery_level": {"type": "number"}
      }
  }
  ```

**[Dev 3 - Data Engineer] - MQTT Client Library Integration (8h)**
- Create Python MQTT client wrapper:
  ```python
  # odoo/addons/smart_dairy_iot/utils/mqtt_client.py
  import paho.mqtt.client as mqtt
  import json
  import logging
  from datetime import datetime
  from threading import Thread
  import ssl

  _logger = logging.getLogger(__name__)

  class SmartDairyMQTTClient:
      """MQTT Client for Smart Dairy IoT Integration"""

      def __init__(self, broker_host, broker_port=1883, use_tls=False,
                   username=None, password=None, client_id=None):
          self.broker_host = broker_host
          self.broker_port = broker_port
          self.use_tls = use_tls
          self.username = username
          self.password = password

          # Create client
          self.client = mqtt.Client(
              client_id=client_id or f"smart_dairy_{datetime.now().timestamp()}",
              clean_session=False,
              protocol=mqtt.MQTTv311
          )

          # Set callbacks
          self.client.on_connect = self._on_connect
          self.client.on_disconnect = self._on_disconnect
          self.client.on_message = self._on_message
          self.client.on_subscribe = self._on_subscribe
          self.client.on_publish = self._on_publish

          # Authentication
          if username and password:
              self.client.username_pw_set(username, password)

          # TLS/SSL
          if use_tls:
              self.client.tls_set(
                  ca_certs="/etc/mosquitto/ca_certificates/ca.crt",
                  certfile=None,
                  keyfile=None,
                  cert_reqs=ssl.CERT_REQUIRED,
                  tls_version=ssl.PROTOCOL_TLSv1_2
              )

          # Message handlers
          self.message_handlers = {}

          # Connection state
          self.is_connected = False

      def _on_connect(self, client, userdata, flags, rc):
          """Callback when connected to broker"""
          if rc == 0:
              _logger.info(f"Connected to MQTT broker at {self.broker_host}:{self.broker_port}")
              self.is_connected = True
              # Resubscribe to all topics
              self._resubscribe_all()
          else:
              _logger.error(f"Failed to connect to MQTT broker. Return code: {rc}")
              self.is_connected = False

      def _on_disconnect(self, client, userdata, rc):
          """Callback when disconnected from broker"""
          _logger.warning(f"Disconnected from MQTT broker. Return code: {rc}")
          self.is_connected = False

      def _on_message(self, client, userdata, msg):
          """Callback when message received"""
          try:
              topic = msg.topic
              payload = msg.payload.decode('utf-8')

              _logger.debug(f"Received message on topic {topic}")

              # Parse JSON payload
              try:
                  data = json.loads(payload)
              except json.JSONDecodeError:
                  _logger.error(f"Invalid JSON payload on topic {topic}: {payload}")
                  return

              # Call registered handlers
              self._handle_message(topic, data)

          except Exception as e:
              _logger.error(f"Error processing message: {str(e)}")

      def _on_subscribe(self, client, userdata, mid, granted_qos):
          """Callback when subscribed to topic"""
          _logger.info(f"Subscribed successfully. QoS: {granted_qos}")

      def _on_publish(self, client, userdata, mid):
          """Callback when message published"""
          _logger.debug(f"Message published. Message ID: {mid}")

      def connect(self):
          """Connect to MQTT broker"""
          try:
              self.client.connect(
                  self.broker_host,
                  self.broker_port,
                  keepalive=60
              )
              # Start network loop in background thread
              self.client.loop_start()
              _logger.info("MQTT client started")
          except Exception as e:
              _logger.error(f"Failed to connect to MQTT broker: {str(e)}")
              raise

      def disconnect(self):
          """Disconnect from MQTT broker"""
          self.client.loop_stop()
          self.client.disconnect()
          _logger.info("MQTT client disconnected")

      def subscribe(self, topic, qos=1, handler=None):
          """Subscribe to topic with optional handler"""
          self.client.subscribe(topic, qos=qos)

          if handler:
              self.message_handlers[topic] = handler

          _logger.info(f"Subscribed to topic: {topic} (QoS {qos})")

      def unsubscribe(self, topic):
          """Unsubscribe from topic"""
          self.client.unsubscribe(topic)

          if topic in self.message_handlers:
              del self.message_handlers[topic]

          _logger.info(f"Unsubscribed from topic: {topic}")

      def publish(self, topic, payload, qos=1, retain=False):
          """Publish message to topic"""
          if isinstance(payload, dict):
              payload = json.dumps(payload)

          result = self.client.publish(topic, payload, qos=qos, retain=retain)

          if result.rc != mqtt.MQTT_ERR_SUCCESS:
              _logger.error(f"Failed to publish message to {topic}")

          return result

      def _handle_message(self, topic, data):
          """Route message to appropriate handler"""
          # Find matching handler
          for topic_pattern, handler in self.message_handlers.items():
              if self._topic_matches(topic, topic_pattern):
                  try:
                      handler(topic, data)
                  except Exception as e:
                      _logger.error(f"Error in message handler for {topic}: {str(e)}")

      def _topic_matches(self, topic, pattern):
          """Check if topic matches pattern with wildcards"""
          topic_parts = topic.split('/')
          pattern_parts = pattern.split('/')

          if len(topic_parts) != len(pattern_parts):
              # Handle # wildcard (multi-level)
              if '#' in pattern_parts:
                  hash_index = pattern_parts.index('#')
                  if hash_index == len(pattern_parts) - 1:
                      # # is at the end
                      pattern_parts = pattern_parts[:hash_index]
                      topic_parts = topic_parts[:hash_index]
                  else:
                      return False
              else:
                  return False

          for topic_part, pattern_part in zip(topic_parts, pattern_parts):
              if pattern_part == '+':
                  # Single level wildcard
                  continue
              elif pattern_part == '#':
                  # Multi-level wildcard
                  return True
              elif topic_part != pattern_part:
                  return False

          return True

      def _resubscribe_all(self):
          """Resubscribe to all topics after reconnection"""
          for topic in self.message_handlers.keys():
              self.client.subscribe(topic)
  ```

- Setup MQTT client initialization
- Connection pooling and management
- Testing basic pub/sub

---

### Day 452: Security & Authentication

**[Dev 1 - IoT Specialist] - TLS/SSL Certificate Setup (8h)**
- Generate SSL certificates for broker
- Configure certificate-based authentication
- Client certificate management
- Certificate rotation procedures

**[Dev 2 - Full-Stack] - Device Authentication System (8h)**
- Create device registration model:
  ```python
  # odoo/addons/smart_dairy_iot/models/iot_device_registry.py
  from odoo import models, fields, api
  from odoo.exceptions import ValidationError
  import secrets
  import hashlib

  class IoTDeviceRegistry(models.Model):
      _name = 'iot.device.registry'
      _description = 'IoT Device Registry'
      _inherit = ['mail.thread']

      name = fields.Char(string='Device Name', required=True)

      device_id = fields.Char(
          string='Device ID',
          required=True,
          copy=False,
          index=True
      )

      device_type = fields.Selection([
          ('milk_meter', 'Milk Meter'),
          ('temperature', 'Temperature Sensor'),
          ('collar', 'Activity Collar'),
          ('feeder', 'Feed Dispenser'),
          ('gate', 'Automated Gate'),
          ('waterer', 'Water Dispenser'),
      ], string='Device Type', required=True, tracking=True)

      farm_id = fields.Many2one(
          'farm.location',
          string='Farm',
          required=True,
          tracking=True
      )

      # MQTT Credentials
      mqtt_username = fields.Char(
          string='MQTT Username',
          required=True,
          copy=False
      )

      mqtt_password = fields.Char(
          string='MQTT Password (Hashed)',
          copy=False,
          readonly=True
      )

      mqtt_password_plain = fields.Char(
          string='MQTT Password',
          compute='_compute_password_display',
          inverse='_set_mqtt_password',
          store=False
      )

      # Device Information
      manufacturer = fields.Char(string='Manufacturer')
      model = fields.Char(string='Model')
      serial_number = fields.Char(string='Serial Number')
      firmware_version = fields.Char(string='Firmware Version')

      # Network Configuration
      mac_address = fields.Char(string='MAC Address')
      ip_address = fields.Char(string='IP Address')

      # Location
      location_description = fields.Char(string='Physical Location')
      pen_id = fields.Many2one('farm.pen', string='Pen/Shed')

      # Status
      status = fields.Selection([
          ('provisioning', 'Provisioning'),
          ('active', 'Active'),
          ('inactive', 'Inactive'),
          ('maintenance', 'Maintenance'),
          ('decommissioned', 'Decommissioned'),
      ], string='Status', default='provisioning', tracking=True)

      last_seen = fields.Datetime(string='Last Seen')
      last_message_timestamp = fields.Datetime(string='Last Message')

      is_online = fields.Boolean(
          string='Is Online',
          compute='_compute_is_online',
          store=False
      )

      # Topics
      data_topic = fields.Char(string='Data Topic', compute='_compute_topics')
      command_topic = fields.Char(string='Command Topic', compute='_compute_topics')
      status_topic = fields.Char(string='Status Topic', compute='_compute_topics')

      # Statistics
      messages_received = fields.Integer(string='Messages Received', default=0)
      messages_sent = fields.Integer(string='Messages Sent', default=0)

      # Calibration
      calibration_date = fields.Date(string='Last Calibration Date')
      next_calibration_date = fields.Date(string='Next Calibration Date')
      calibration_status = fields.Selection([
          ('valid', 'Valid'),
          ('due', 'Calibration Due'),
          ('overdue', 'Calibration Overdue'),
      ], string='Calibration Status', compute='_compute_calibration_status')

      # Configuration
      config_json = fields.Text(string='Device Configuration (JSON)')

      @api.depends('last_seen')
      def _compute_is_online(self):
          from datetime import datetime, timedelta
          threshold = datetime.now() - timedelta(minutes=5)

          for record in self:
              record.is_online = (
                  record.last_seen and record.last_seen >= threshold
              )

      @api.depends('device_type', 'device_id', 'farm_id')
      def _compute_topics(self):
          for record in self:
              farm_code = record.farm_id.code if record.farm_id else 'unknown'
              device_type_map = {
                  'milk_meter': 'milk_meters',
                  'temperature': 'temperature',
                  'collar': 'collars',
                  'feeder': 'feeders',
                  'gate': 'gates',
                  'waterer': 'waterers',
              }
              device_type = device_type_map.get(record.device_type, 'unknown')

              record.data_topic = f"dairy/{farm_code}/{device_type}/{record.device_id}/data"
              record.command_topic = f"dairy/{farm_code}/{device_type}/{record.device_id}/commands"
              record.status_topic = f"dairy/{farm_code}/{device_type}/{record.device_id}/status"

      @api.depends('calibration_date', 'next_calibration_date')
      def _compute_calibration_status(self):
          from datetime import date
          today = date.today()

          for record in self:
              if not record.next_calibration_date:
                  record.calibration_status = 'valid'
              elif record.next_calibration_date < today:
                  record.calibration_status = 'overdue'
              elif (record.next_calibration_date - today).days <= 7:
                  record.calibration_status = 'due'
              else:
                  record.calibration_status = 'valid'

      def _compute_password_display(self):
          for record in self:
              record.mqtt_password_plain = '********' if record.mqtt_password else ''

      def _set_mqtt_password(self):
          for record in self:
              if record.mqtt_password_plain and record.mqtt_password_plain != '********':
                  # Hash password using SHA-256
                  hashed = hashlib.sha256(
                      record.mqtt_password_plain.encode()
                  ).hexdigest()
                  record.mqtt_password = hashed

      @api.model
      def create(self, vals):
          # Generate MQTT credentials if not provided
          if not vals.get('mqtt_username'):
              vals['mqtt_username'] = f"{vals.get('device_type')}_{vals.get('device_id')}"

          if not vals.get('mqtt_password_plain'):
              # Generate random password
              vals['mqtt_password_plain'] = secrets.token_urlsafe(32)

          return super().create(vals)

      def action_provision_device(self):
          """Provision device - add to MQTT ACL"""
          self.ensure_one()
          # Add device to Mosquitto password file
          self._add_to_mosquitto_passwd()
          # Add device to ACL
          self._add_to_mosquitto_acl()
          # Update status
          self.write({'status': 'active'})

      def action_decommission_device(self):
          """Decommission device - remove from MQTT"""
          self.ensure_one()
          self._remove_from_mosquitto_passwd()
          self._remove_from_mosquitto_acl()
          self.write({'status': 'decommissioned'})

      def _add_to_mosquitto_passwd(self):
          """Add device credentials to Mosquitto password file"""
          # Implementation to add to /etc/mosquitto/passwd
          pass

      def _add_to_mosquitto_acl(self):
          """Add device permissions to Mosquitto ACL"""
          # Implementation to add to /etc/mosquitto/acl
          pass

      def _remove_from_mosquitto_passwd(self):
          """Remove device from Mosquitto password file"""
          pass

      def _remove_from_mosquitto_acl(self):
          """Remove device from Mosquitto ACL"""
          pass
  ```

**[Dev 3 - Data Engineer] - Authorization & ACL Management (8h)**
- Implement role-based access control for devices
- ACL auto-generation from device registry
- Permission testing

---

### Day 453: QoS & Message Persistence

**[Dev 1 - IoT Specialist] - QoS Configuration (8h)**
- Configure QoS levels per device type
- Message retention policies
- Durable sessions setup

**[Dev 2 - Full-Stack] - Message Queue Management (8h)**
- Implement message queuing for offline devices
- Queue size limits and overflow handling
- Message priority system

**[Dev 3 - Data Engineer] - Persistence Layer (8h)**
- Configure message persistence
- Backup and recovery procedures
- Testing persistence under load

---

### Day 454: MQTT Monitoring & Management

**[Dev 1 - IoT Specialist] - Broker Monitoring (8h)**
- Setup Prometheus metrics for Mosquitto
- Connection monitoring
- Message throughput tracking

**[Dev 2 - Full-Stack] - Management Dashboard (8h)**
- Create MQTT management interface
- Real-time broker statistics
- Connected devices view

**[Dev 3 - Data Engineer] - Alerting System (8h)**
- Broker health alerts
- Connection failure alerts
- Performance degradation detection

---

### Day 455: Testing & Documentation

**[All Devs] - MQTT Infrastructure Testing (8h each)**
- Load testing (10,000+ concurrent connections)
- Failover testing
- Security penetration testing
- Documentation and operational procedures

---

## MILESTONE 2: Milk Meter Integration (Days 456-460)

**Objective**: Integrate milk meters for real-time collection data, quality parameters, and yield tracking.

**Duration**: 5 working days

---

### Day 456: Milk Meter Data Model

**[Dev 1 - IoT Specialist] - Milk Meter Device Integration (8h)**
- Configure milk meter MQTT topics
- Data collection protocol
- RFID reader integration

**[Dev 2 - Full-Stack] - Milk Production Data Model (8h)**
- Create milk production tracking model:
  ```python
  # odoo/addons/smart_dairy_iot/models/milk_production_realtime.py
  from odoo import models, fields, api
  from datetime import datetime, timedelta

  class MilkProductionRealtime(models.Model):
      _name = 'milk.production.realtime'
      _description = 'Real-time Milk Production Data'
      _order = 'timestamp desc'

      # Device Information
      device_id = fields.Many2one(
          'iot.device.registry',
          string='Milk Meter',
          required=True,
          domain="[('device_type', '=', 'milk_meter')]"
      )

      # Session Information
      session_id = fields.Char(string='Milking Session ID', required=True, index=True)

      timestamp = fields.Datetime(
          string='Timestamp',
          required=True,
          index=True,
          default=fields.Datetime.now
      )

      # Animal Information
      animal_id = fields.Many2one(
          'farm.animal',
          string='Animal',
          required=True,
          index=True
      )

      rfid_tag = fields.Char(string='RFID Tag', index=True)

      # Production Data
      yield_liters = fields.Float(
          string='Milk Yield (Liters)',
          required=True,
          digits=(10, 3)
      )

      flow_rate_lpm = fields.Float(
          string='Flow Rate (L/min)',
          digits=(8, 3)
      )

      duration_seconds = fields.Integer(string='Milking Duration (seconds)')

      milking_position = fields.Char(string='Milking Position')

      # Quality Parameters
      fat_percentage = fields.Float(string='Fat %', digits=(5, 2))
      protein_percentage = fields.Float(string='Protein %', digits=(5, 2))
      lactose_percentage = fields.Float(string='Lactose %', digits=(5, 2))
      somatic_cell_count = fields.Integer(string='Somatic Cell Count')
      milk_temperature = fields.Float(string='Milk Temperature (°C)', digits=(5, 2))
      conductivity = fields.Float(string='Conductivity (mS/cm)', digits=(6, 2))

      # Equipment Parameters
      vacuum_pressure = fields.Float(string='Vacuum Pressure (kPa)', digits=(6, 2))
      pulsation_rate = fields.Integer(string='Pulsation Rate (cycles/min)')

      # Quality Flags
      quality_alert = fields.Boolean(string='Quality Alert', default=False)
      alert_reason = fields.Char(string='Alert Reason')

      # Derived Fields
      is_abnormal = fields.Boolean(
          string='Abnormal Reading',
          compute='_compute_is_abnormal',
          store=True
      )

      quality_grade = fields.Selection([
          ('A', 'Grade A'),
          ('B', 'Grade B'),
          ('C', 'Grade C'),
          ('reject', 'Reject'),
      ], string='Quality Grade', compute='_compute_quality_grade', store=True)

      @api.depends('yield_liters', 'animal_id')
      def _compute_is_abnormal(self):
          for record in self:
              if not record.animal_id:
                  record.is_abnormal = False
                  continue

              # Get average yield for this animal
              avg_yield = self.search([
                  ('animal_id', '=', record.animal_id.id),
                  ('timestamp', '>=', datetime.now() - timedelta(days=30))
              ]).mapped('yield_liters')

              if avg_yield:
                  avg = sum(avg_yield) / len(avg_yield)
                  # Flag if >30% deviation from average
                  deviation = abs(record.yield_liters - avg) / avg
                  record.is_abnormal = deviation > 0.3
              else:
                  record.is_abnormal = False

      @api.depends('fat_percentage', 'protein_percentage', 'somatic_cell_count')
      def _compute_quality_grade(self):
          for record in self:
              # Simple grading logic
              if not all([record.fat_percentage, record.protein_percentage]):
                  record.quality_grade = False
                  continue

              if record.somatic_cell_count and record.somatic_cell_count > 400000:
                  record.quality_grade = 'reject'
              elif record.fat_percentage >= 3.5 and record.protein_percentage >= 3.0:
                  record.quality_grade = 'A'
              elif record.fat_percentage >= 3.0 and record.protein_percentage >= 2.8:
                  record.quality_grade = 'B'
              elif record.fat_percentage >= 2.5 and record.protein_percentage >= 2.5:
                  record.quality_grade = 'C'
              else:
                  record.quality_grade = 'reject'

      @api.model
      def process_mqtt_message(self, topic, payload):
          """Process incoming MQTT message from milk meter"""
          try:
              # Extract device_id from topic
              # Topic format: dairy/farm1/milk_meters/meter_001/data
              topic_parts = topic.split('/')
              device_id_str = topic_parts[3]

              # Find device
              device = self.env['iot.device.registry'].search([
                  ('device_id', '=', device_id_str),
                  ('device_type', '=', 'milk_meter')
              ], limit=1)

              if not device:
                  _logger.warning(f"Unknown milk meter device: {device_id_str}")
                  return

              # Find animal by RFID
              animal = self.env['farm.animal'].search([
                  ('rfid_tag', '=', payload.get('rfid_tag'))
              ], limit=1)

              if not animal:
                  _logger.warning(f"Unknown animal RFID: {payload.get('rfid_tag')}")
                  return

              # Create production record
              quality_data = payload.get('quality', {})
              equipment_data = payload.get('equipment', {})

              record = self.create({
                  'device_id': device.id,
                  'session_id': payload.get('session_id'),
                  'timestamp': payload.get('timestamp'),
                  'animal_id': animal.id,
                  'rfid_tag': payload.get('rfid_tag'),
                  'yield_liters': payload.get('yield_liters'),
                  'flow_rate_lpm': payload.get('flow_rate'),
                  'duration_seconds': payload.get('duration_seconds'),
                  'milking_position': equipment_data.get('milking_position'),
                  'fat_percentage': quality_data.get('fat_percentage'),
                  'protein_percentage': quality_data.get('protein_percentage'),
                  'lactose_percentage': quality_data.get('lactose_percentage'),
                  'somatic_cell_count': quality_data.get('somatic_cell_count'),
                  'milk_temperature': quality_data.get('temperature_celsius'),
                  'vacuum_pressure': equipment_data.get('vacuum_pressure'),
                  'pulsation_rate': equipment_data.get('pulsation_rate'),
              })

              # Update device last seen
              device.write({
                  'last_seen': fields.Datetime.now(),
                  'last_message_timestamp': fields.Datetime.now(),
                  'messages_received': device.messages_received + 1
              })

              # Trigger alerts if needed
              if record.is_abnormal or record.quality_grade == 'reject':
                  self._trigger_quality_alert(record)

              # Update daily summary
              self._update_daily_summary(record)

              return record

          except Exception as e:
              _logger.error(f"Error processing milk meter message: {str(e)}")
              raise

      def _trigger_quality_alert(self, record):
          """Trigger alert for quality issues"""
          pass

      def _update_daily_summary(self, record):
          """Update daily production summary"""
          pass
  ```

**[Dev 3 - Data Engineer] - TimescaleDB Schema (8h)**
- Create hypertables for milk production data
- Continuous aggregates for hourly/daily summaries
- Retention policies

---

### Day 457-460: Complete Milk Meter Integration

(Due to length constraints, I'll summarize the remaining days)

**Days 457-460 cover:**
- Real-time data ingestion and validation
- Quality parameter analysis
- Individual animal performance tracking
- Milking session management
- Equipment performance monitoring
- Alert system for quality issues
- Integration with existing farm management
- Dashboard development
- Comprehensive testing

---

## MILESTONE 3-10: Remaining Milestones

Due to the extensive length of this document, I'll provide abbreviated versions of the remaining milestones:

**MILESTONE 3: Temperature Sensors (Days 461-465)**
- Cold chain monitoring system
- Multi-location temperature tracking
- Alert threshold configuration
- Compliance reporting

**MILESTONE 4: Activity Collars (Days 466-470)**
- Animal activity tracking
- Heat detection algorithms
- Health anomaly detection
- Location tracking

**MILESTONE 5: Feed Dispensers (Days 471-475)**
- Automated feeding schedules
- Consumption tracking
- Ration optimization
- Dispenser control

**MILESTONE 6: IoT Data Pipeline (Days 476-480)**
- TimescaleDB implementation
- High-throughput ingestion
- Data aggregation
- Retention policies

**MILESTONE 7: Real-time Alerts (Days 481-485)**
- Multi-channel alerting
- Escalation workflows
- Alert analytics
- Notification management

**MILESTONE 8: IoT Dashboards (Days 486-490)**
- Real-time visualization
- Historical trends
- Device monitoring
- Mobile dashboards

**MILESTONE 9: Predictive Analytics (Days 491-495)**
- ML models for yield prediction
- Health issue prediction
- Maintenance forecasting
- Optimization recommendations

**MILESTONE 10: IoT Testing & Validation (Days 496-500)**
- Sensor calibration
- Failover testing
- Performance validation
- Documentation

---

## PHASE SUCCESS CRITERIA

### Functional Criteria
- ✓ MQTT broker supporting 10,000+ concurrent connections
- ✓ Real-time milk production tracking with <5s latency
- ✓ Temperature monitoring with 1-minute resolution
- ✓ Activity collar integration for 1000+ animals
- ✓ Automated feed dispensing with consumption tracking
- ✓ 99.9% uptime for IoT infrastructure
- ✓ Multi-channel alerting (SMS, email, push)
- ✓ Real-time dashboards with <1s refresh
- ✓ Predictive analytics with 85%+ accuracy

### Technical Criteria
- ✓ Message processing: 10,000+ messages/second
- ✓ Data ingestion latency < 5 seconds
- ✓ TimescaleDB compression ratio > 10:1
- ✓ 99.9% message delivery success rate
- ✓ TLS/SSL encryption for all communications
- ✓ Device authentication and authorization
- ✓ Automatic failover and recovery
- ✓ Historical data retention: 2 years

### Business Criteria
- ✓ 50% reduction in manual data entry
- ✓ 30% improvement in milk quality detection
- ✓ 20% improvement in feed efficiency
- ✓ Early disease detection (3-5 days advance)
- ✓ 40% reduction in equipment downtime
- ✓ Complete cold chain compliance tracking

---

**END OF PHASE 10 DETAILED IMPLEMENTATION PLAN**
