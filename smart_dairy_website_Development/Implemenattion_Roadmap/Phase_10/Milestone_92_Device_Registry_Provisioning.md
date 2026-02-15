# Milestone 92: Device Registry & Provisioning

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field                | Details                                                      |
| -------------------- | ------------------------------------------------------------ |
| **Document ID**      | SD-IMPL-P10-M92-v1.0                                         |
| **Milestone**        | 92 - Device Registry & Provisioning                          |
| **Phase**            | Phase 10: Commerce - IoT Integration Core                    |
| **Days**             | 661-670                                                      |
| **Duration**         | 10 Working Days                                              |
| **Status**           | Draft                                                        |
| **Version**          | 1.0.0                                                        |
| **Last Updated**     | 2026-02-04                                                   |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 92 builds a comprehensive device lifecycle management system for Smart Dairy's IoT infrastructure. This milestone implements device registration workflows, provisioning wizards, firmware management, OTA (Over-The-Air) update capabilities, and health monitoring services that ensure reliable device operations across all farm locations.

### 1.2 Scope

- Device Registry Odoo Module Extension (iot.device)
- Device Provisioning Wizard with credential generation
- Firmware Version Management System
- OTA (Over-The-Air) Update Framework
- Device Health Monitoring Service
- Device Groups and Locations Management
- Bulk Device Import via CSV
- Device Diagnostics API
- OWL Device Management UI enhancements
- Mobile Device Scanner (Flutter) for field provisioning

### 1.3 Key Outcomes

| Outcome                        | Target                                    |
| ------------------------------ | ----------------------------------------- |
| Device Registration Time       | < 5 minutes per device                    |
| Bulk Import Capacity           | 500+ devices per batch                    |
| OTA Update Success Rate        | > 99%                                     |
| Health Check Frequency         | Every 60 seconds                          |
| Firmware Management            | Version tracking with rollback            |
| Mobile Provisioning            | QR code + NFC support                     |

---

## 2. Objectives

1. **Extend Device Registry** - Enhance iot.device model with complete lifecycle management
2. **Build Provisioning Wizard** - Guided device setup with credential generation
3. **Implement Firmware Management** - Version tracking, rollback, compatibility checks
4. **Create OTA Framework** - Secure firmware updates with staged rollout
5. **Develop Health Monitoring** - Continuous device status tracking and alerting
6. **Enable Bulk Import** - CSV upload with validation and error handling
7. **Build Mobile Scanner** - Flutter app for field device provisioning

---

## 3. Key Deliverables

| #  | Deliverable                                | Owner  | Priority |
| -- | ------------------------------------------ | ------ | -------- |
| 1  | Device Registry Extension (iot.device)     | Dev 1  | Critical |
| 2  | Device Provisioning Wizard                 | Dev 1  | Critical |
| 3  | Firmware Version Model                     | Dev 1  | High     |
| 4  | OTA Update Service                         | Dev 2  | High     |
| 5  | Health Monitoring Scheduler                | Dev 2  | Critical |
| 6  | Device Group Model                         | Dev 1  | High     |
| 7  | Bulk Import CSV Handler                    | Dev 2  | High     |
| 8  | Device Diagnostics API                     | Dev 2  | High     |
| 9  | OWL Device Management Enhancements         | Dev 3  | High     |
| 10 | Mobile Device Scanner (Flutter)            | Dev 3  | High     |
| 11 | QR Code Generator for Devices              | Dev 3  | Medium   |
| 12 | Unit & Integration Tests                   | All    | High     |

---

## 4. Prerequisites

| Prerequisite                       | Source      | Status   |
| ---------------------------------- | ----------- | -------- |
| Milestone 91 MQTT Infrastructure   | Milestone 91| Complete |
| Device Authentication Service      | Milestone 91| Complete |
| ACL Manager Service                | Milestone 91| Complete |
| MQTT-to-Odoo Bridge                | Milestone 91| Complete |
| OWL Device List Component          | Milestone 91| Complete |

---

## 5. Requirement Traceability

| Req ID       | Description                              | Source | Priority |
| ------------ | ---------------------------------------- | ------ | -------- |
| IOT-002      | Device registration and provisioning     | RFP    | Must     |
| FR-IOT-007   | Device health monitoring                 | BRD    | Must     |
| SRS-IOT-011  | OTA firmware updates                     | SRS    | Should   |
| SRS-IOT-012  | Bulk device import capability            | SRS    | Should   |
| FR-IOT-008   | Device lifecycle management              | BRD    | Must     |

---

## 6. Day-by-Day Development Plan

### Day 661 (Day 1): Device Registry Extension & Firmware Model

#### Dev 1 (Backend Lead) - 8 hours

**Task 1.1: Extend Device Registry Model (4h)**

```python
# odoo/addons/smart_dairy_iot/models/iot_device_extended.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError, UserError
import secrets
import hashlib
import json
from datetime import datetime, timedelta

class IoTDeviceExtended(models.Model):
    _inherit = 'iot.device'

    # Firmware Information
    firmware_id = fields.Many2one(
        'iot.firmware.version',
        string='Current Firmware',
        tracking=True
    )
    target_firmware_id = fields.Many2one(
        'iot.firmware.version',
        string='Target Firmware',
        help='Firmware version scheduled for OTA update'
    )
    firmware_update_status = fields.Selection([
        ('none', 'No Update'),
        ('scheduled', 'Update Scheduled'),
        ('downloading', 'Downloading'),
        ('installing', 'Installing'),
        ('completed', 'Update Complete'),
        ('failed', 'Update Failed'),
        ('rolled_back', 'Rolled Back'),
    ], string='Update Status', default='none', tracking=True)
    firmware_update_scheduled = fields.Datetime(string='Update Scheduled At')
    firmware_update_completed = fields.Datetime(string='Update Completed At')
    firmware_update_error = fields.Text(string='Update Error Message')

    # Device Group
    group_id = fields.Many2one(
        'iot.device.group',
        string='Device Group',
        tracking=True
    )
    tag_ids = fields.Many2many(
        'iot.device.tag',
        string='Tags',
        help='Tags for device categorization'
    )

    # Health Monitoring
    health_status = fields.Selection([
        ('healthy', 'Healthy'),
        ('warning', 'Warning'),
        ('critical', 'Critical'),
        ('unknown', 'Unknown'),
    ], string='Health Status', default='unknown', compute='_compute_health_status', store=True)
    health_score = fields.Float(string='Health Score', compute='_compute_health_score', store=True)
    last_health_check = fields.Datetime(string='Last Health Check')
    health_check_interval = fields.Integer(string='Health Check Interval (seconds)', default=60)

    # Battery and Power
    battery_level = fields.Float(string='Battery Level (%)', digits=(5, 2))
    battery_voltage = fields.Float(string='Battery Voltage (V)', digits=(5, 3))
    power_source = fields.Selection([
        ('battery', 'Battery'),
        ('mains', 'Mains Power'),
        ('solar', 'Solar'),
        ('poe', 'Power over Ethernet'),
    ], string='Power Source', default='battery')
    low_battery_threshold = fields.Float(string='Low Battery Threshold (%)', default=20.0)

    # Signal and Connectivity
    signal_strength = fields.Integer(string='Signal Strength (dBm)')
    signal_quality = fields.Selection([
        ('excellent', 'Excellent'),
        ('good', 'Good'),
        ('fair', 'Fair'),
        ('poor', 'Poor'),
        ('none', 'No Signal'),
    ], string='Signal Quality', compute='_compute_signal_quality')
    connection_type = fields.Selection([
        ('wifi', 'WiFi'),
        ('ethernet', 'Ethernet'),
        ('lora', 'LoRaWAN'),
        ('cellular', 'Cellular'),
        ('bluetooth', 'Bluetooth'),
    ], string='Connection Type')
    ssid = fields.Char(string='WiFi SSID')

    # Provisioning
    provisioning_date = fields.Datetime(string='Provisioning Date')
    provisioned_by = fields.Many2one('res.users', string='Provisioned By')
    provisioning_method = fields.Selection([
        ('manual', 'Manual Entry'),
        ('qr_code', 'QR Code Scan'),
        ('nfc', 'NFC Tap'),
        ('csv_import', 'CSV Import'),
        ('api', 'API Registration'),
    ], string='Provisioning Method')
    provisioning_token = fields.Char(string='Provisioning Token', copy=False)
    provisioning_token_expiry = fields.Datetime(string='Token Expiry')

    # Diagnostics
    last_diagnostic = fields.Datetime(string='Last Diagnostic Run')
    diagnostic_report = fields.Text(string='Diagnostic Report (JSON)')
    needs_attention = fields.Boolean(string='Needs Attention', compute='_compute_needs_attention', store=True)

    # Statistics
    uptime_seconds = fields.Integer(string='Uptime (seconds)')
    total_uptime_seconds = fields.Integer(string='Total Uptime (seconds)')
    restart_count = fields.Integer(string='Restart Count', default=0)
    last_restart = fields.Datetime(string='Last Restart')
    data_transmitted_bytes = fields.Float(string='Data Transmitted (MB)', digits=(12, 2))

    # Maintenance
    maintenance_mode = fields.Boolean(string='Maintenance Mode', default=False)
    maintenance_notes = fields.Text(string='Maintenance Notes')
    next_maintenance_date = fields.Date(string='Next Scheduled Maintenance')
    maintenance_history_ids = fields.One2many(
        'iot.device.maintenance',
        'device_id',
        string='Maintenance History'
    )

    # Alerts
    alert_ids = fields.One2many(
        'iot.device.alert',
        'device_id',
        string='Device Alerts'
    )
    active_alert_count = fields.Integer(
        string='Active Alerts',
        compute='_compute_active_alerts'
    )

    @api.depends('last_seen', 'error_count', 'battery_level', 'signal_strength')
    def _compute_health_status(self):
        for device in self:
            if not device.last_seen:
                device.health_status = 'unknown'
                continue

            offline_threshold = datetime.now() - timedelta(minutes=10)

            if device.last_seen < offline_threshold:
                device.health_status = 'critical'
            elif device.error_count > 10:
                device.health_status = 'critical'
            elif device.battery_level and device.battery_level < 10:
                device.health_status = 'critical'
            elif device.error_count > 5 or (device.battery_level and device.battery_level < 20):
                device.health_status = 'warning'
            else:
                device.health_status = 'healthy'

    @api.depends('health_status', 'battery_level', 'signal_strength', 'error_count')
    def _compute_health_score(self):
        for device in self:
            score = 100.0

            # Deduct for offline
            if device.health_status == 'critical':
                score -= 50
            elif device.health_status == 'warning':
                score -= 25

            # Deduct for low battery
            if device.battery_level:
                if device.battery_level < 20:
                    score -= 20
                elif device.battery_level < 50:
                    score -= 10

            # Deduct for poor signal
            if device.signal_strength:
                if device.signal_strength < -90:
                    score -= 20
                elif device.signal_strength < -70:
                    score -= 10

            # Deduct for errors
            score -= min(device.error_count * 2, 30)

            device.health_score = max(0, score)

    @api.depends('signal_strength')
    def _compute_signal_quality(self):
        for device in self:
            if not device.signal_strength:
                device.signal_quality = 'none'
            elif device.signal_strength >= -50:
                device.signal_quality = 'excellent'
            elif device.signal_strength >= -60:
                device.signal_quality = 'good'
            elif device.signal_strength >= -70:
                device.signal_quality = 'fair'
            else:
                device.signal_quality = 'poor'

    @api.depends('health_status', 'calibration_status', 'firmware_update_status', 'battery_level')
    def _compute_needs_attention(self):
        for device in self:
            device.needs_attention = (
                device.health_status in ('warning', 'critical') or
                device.calibration_status in ('due', 'overdue') or
                device.firmware_update_status == 'failed' or
                (device.battery_level and device.battery_level < device.low_battery_threshold)
            )

    def _compute_active_alerts(self):
        for device in self:
            device.active_alert_count = self.env['iot.device.alert'].search_count([
                ('device_id', '=', device.id),
                ('state', '=', 'active')
            ])

    def action_generate_provisioning_token(self):
        """Generate a one-time provisioning token"""
        self.ensure_one()
        token = secrets.token_urlsafe(32)
        self.write({
            'provisioning_token': token,
            'provisioning_token_expiry': datetime.now() + timedelta(hours=24)
        })
        return token

    def action_schedule_firmware_update(self):
        """Open firmware update scheduling wizard"""
        self.ensure_one()
        return {
            'type': 'ir.actions.act_window',
            'name': 'Schedule Firmware Update',
            'res_model': 'iot.firmware.update.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {
                'default_device_ids': [(6, 0, [self.id])],
                'default_current_firmware_id': self.firmware_id.id if self.firmware_id else False,
            }
        }

    def action_run_diagnostic(self):
        """Run diagnostic tests on device"""
        self.ensure_one()
        diagnostic = self.env['iot.device.diagnostic'].create({
            'device_id': self.id,
        })
        diagnostic.run_diagnostic()
        return {
            'type': 'ir.actions.act_window',
            'name': 'Diagnostic Results',
            'res_model': 'iot.device.diagnostic',
            'res_id': diagnostic.id,
            'view_mode': 'form',
            'target': 'new',
        }

    def action_enable_maintenance_mode(self):
        """Enable maintenance mode"""
        self.write({
            'maintenance_mode': True,
            'state': 'maintenance',
        })

    def action_disable_maintenance_mode(self):
        """Disable maintenance mode"""
        self.write({
            'maintenance_mode': False,
            'state': 'active',
        })

    def action_restart_device(self):
        """Send restart command to device"""
        self.ensure_one()
        mqtt_handler = self.env['iot.mqtt.service'].get_handler()
        if mqtt_handler:
            mqtt_handler.publish(
                self.command_topic,
                {'command': 'restart', 'timestamp': datetime.now().isoformat()},
                qos=2
            )
            self.write({'restart_count': self.restart_count + 1})
        return True
```

**Task 1.2: Create Firmware Version Model (2h)**

```python
# odoo/addons/smart_dairy_iot/models/iot_firmware.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
import hashlib

class IoTFirmwareVersion(models.Model):
    _name = 'iot.firmware.version'
    _description = 'IoT Firmware Version'
    _order = 'release_date desc, version desc'

    name = fields.Char(string='Version Name', required=True)
    version = fields.Char(string='Version Number', required=True)

    device_type = fields.Selection([
        ('milk_meter', 'Milk Meter'),
        ('temperature', 'Temperature Sensor'),
        ('collar', 'Activity Collar'),
        ('feeder', 'Feed Dispenser'),
        ('gate', 'Automated Gate'),
        ('waterer', 'Water Dispenser'),
        ('environmental', 'Environmental Sensor'),
    ], string='Device Type', required=True)

    manufacturer = fields.Char(string='Manufacturer')
    model_compatibility = fields.Char(string='Compatible Models', help='Comma-separated list')

    # Binary
    firmware_binary = fields.Binary(string='Firmware Binary', attachment=True)
    firmware_filename = fields.Char(string='Firmware Filename')
    firmware_size = fields.Integer(string='Size (bytes)', compute='_compute_firmware_size')
    firmware_checksum = fields.Char(string='SHA256 Checksum', compute='_compute_checksum', store=True)

    # Download URL for OTA
    download_url = fields.Char(string='Download URL')

    # Release Info
    release_date = fields.Date(string='Release Date', default=fields.Date.today)
    release_notes = fields.Text(string='Release Notes')
    changelog = fields.Text(string='Changelog')

    # Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('testing', 'Testing'),
        ('stable', 'Stable'),
        ('deprecated', 'Deprecated'),
    ], string='Status', default='draft')

    is_mandatory = fields.Boolean(string='Mandatory Update', default=False)
    min_previous_version = fields.Char(string='Minimum Previous Version', help='Required version to upgrade from')

    # Statistics
    device_count = fields.Integer(string='Installed Devices', compute='_compute_device_count')
    successful_updates = fields.Integer(string='Successful Updates', default=0)
    failed_updates = fields.Integer(string='Failed Updates', default=0)

    _sql_constraints = [
        ('version_device_unique', 'UNIQUE(version, device_type)', 'Version must be unique per device type!')
    ]

    @api.depends('firmware_binary')
    def _compute_firmware_size(self):
        for record in self:
            if record.firmware_binary:
                import base64
                record.firmware_size = len(base64.b64decode(record.firmware_binary))
            else:
                record.firmware_size = 0

    @api.depends('firmware_binary')
    def _compute_checksum(self):
        for record in self:
            if record.firmware_binary:
                import base64
                binary_data = base64.b64decode(record.firmware_binary)
                record.firmware_checksum = hashlib.sha256(binary_data).hexdigest()
            else:
                record.firmware_checksum = False

    def _compute_device_count(self):
        for record in self:
            record.device_count = self.env['iot.device'].search_count([
                ('firmware_id', '=', record.id)
            ])

    def action_mark_stable(self):
        """Mark firmware as stable"""
        self.write({'state': 'stable'})

    def action_deprecate(self):
        """Deprecate firmware version"""
        self.write({'state': 'deprecated'})


class IoTFirmwareUpdateJob(models.Model):
    _name = 'iot.firmware.update.job'
    _description = 'Firmware Update Job'
    _order = 'scheduled_at desc'

    name = fields.Char(string='Job Name', required=True, default='New Update Job')

    device_ids = fields.Many2many(
        'iot.device',
        string='Target Devices',
        required=True
    )
    device_count = fields.Integer(string='Device Count', compute='_compute_counts')

    firmware_id = fields.Many2one(
        'iot.firmware.version',
        string='Target Firmware',
        required=True,
        domain="[('state', 'in', ['testing', 'stable'])]"
    )

    scheduled_at = fields.Datetime(string='Scheduled At', required=True)
    started_at = fields.Datetime(string='Started At')
    completed_at = fields.Datetime(string='Completed At')

    state = fields.Selection([
        ('draft', 'Draft'),
        ('scheduled', 'Scheduled'),
        ('in_progress', 'In Progress'),
        ('completed', 'Completed'),
        ('cancelled', 'Cancelled'),
        ('failed', 'Failed'),
    ], string='Status', default='draft')

    # Progress
    success_count = fields.Integer(string='Successful', default=0)
    failed_count = fields.Integer(string='Failed', default=0)
    pending_count = fields.Integer(string='Pending', compute='_compute_counts')
    progress_percentage = fields.Float(string='Progress %', compute='_compute_progress')

    # Options
    force_update = fields.Boolean(string='Force Update', default=False)
    rollback_on_failure = fields.Boolean(string='Rollback on Failure', default=True)
    notify_on_complete = fields.Boolean(string='Notify on Complete', default=True)

    update_log_ids = fields.One2many(
        'iot.firmware.update.log',
        'job_id',
        string='Update Logs'
    )

    @api.depends('device_ids', 'success_count', 'failed_count')
    def _compute_counts(self):
        for job in self:
            job.device_count = len(job.device_ids)
            job.pending_count = job.device_count - job.success_count - job.failed_count

    @api.depends('device_count', 'success_count', 'failed_count')
    def _compute_progress(self):
        for job in self:
            if job.device_count > 0:
                job.progress_percentage = ((job.success_count + job.failed_count) / job.device_count) * 100
            else:
                job.progress_percentage = 0

    def action_schedule(self):
        """Schedule the update job"""
        self.write({'state': 'scheduled'})
        # Schedule cron job

    def action_start(self):
        """Start the update job"""
        self.write({
            'state': 'in_progress',
            'started_at': fields.Datetime.now()
        })
        # Trigger OTA update process

    def action_cancel(self):
        """Cancel the update job"""
        self.write({'state': 'cancelled'})


class IoTFirmwareUpdateLog(models.Model):
    _name = 'iot.firmware.update.log'
    _description = 'Firmware Update Log Entry'
    _order = 'timestamp desc'

    job_id = fields.Many2one('iot.firmware.update.job', string='Update Job', ondelete='cascade')
    device_id = fields.Many2one('iot.device', string='Device', required=True)

    previous_version = fields.Char(string='Previous Version')
    target_version = fields.Char(string='Target Version')

    state = fields.Selection([
        ('pending', 'Pending'),
        ('downloading', 'Downloading'),
        ('installing', 'Installing'),
        ('verifying', 'Verifying'),
        ('completed', 'Completed'),
        ('failed', 'Failed'),
        ('rolled_back', 'Rolled Back'),
    ], string='Status', default='pending')

    timestamp = fields.Datetime(string='Timestamp', default=fields.Datetime.now)
    duration_seconds = fields.Integer(string='Duration (seconds)')
    error_message = fields.Text(string='Error Message')
    log_details = fields.Text(string='Log Details')
```

**Task 1.3: Create Device Group Model (2h)**

```python
# odoo/addons/smart_dairy_iot/models/iot_device_group.py
from odoo import models, fields, api

class IoTDeviceGroup(models.Model):
    _name = 'iot.device.group'
    _description = 'IoT Device Group'
    _order = 'sequence, name'

    name = fields.Char(string='Group Name', required=True)
    code = fields.Char(string='Group Code', required=True)
    sequence = fields.Integer(string='Sequence', default=10)

    description = fields.Text(string='Description')

    farm_id = fields.Many2one(
        'farm.location',
        string='Farm',
        help='Leave empty for cross-farm groups'
    )

    device_type = fields.Selection([
        ('all', 'All Types'),
        ('milk_meter', 'Milk Meters'),
        ('temperature', 'Temperature Sensors'),
        ('collar', 'Activity Collars'),
        ('feeder', 'Feed Dispensers'),
    ], string='Device Type Filter', default='all')

    device_ids = fields.One2many(
        'iot.device',
        'group_id',
        string='Devices'
    )
    device_count = fields.Integer(string='Device Count', compute='_compute_device_count')
    online_count = fields.Integer(string='Online Count', compute='_compute_device_count')

    parent_id = fields.Many2one('iot.device.group', string='Parent Group')
    child_ids = fields.One2many('iot.device.group', 'parent_id', string='Child Groups')

    color = fields.Integer(string='Color Index')
    active = fields.Boolean(default=True)

    # Group Settings
    health_check_interval = fields.Integer(string='Health Check Interval (s)', default=60)
    alert_threshold = fields.Integer(string='Offline Alert Threshold (devices)', default=1)

    _sql_constraints = [
        ('code_unique', 'UNIQUE(code)', 'Group code must be unique!')
    ]

    @api.depends('device_ids', 'device_ids.is_online')
    def _compute_device_count(self):
        for group in self:
            devices = group.device_ids
            group.device_count = len(devices)
            group.online_count = len(devices.filtered(lambda d: d.is_online))

    def action_view_devices(self):
        """View devices in this group"""
        return {
            'type': 'ir.actions.act_window',
            'name': f'Devices - {self.name}',
            'res_model': 'iot.device',
            'view_mode': 'tree,form',
            'domain': [('group_id', '=', self.id)],
            'context': {'default_group_id': self.id},
        }


class IoTDeviceTag(models.Model):
    _name = 'iot.device.tag'
    _description = 'IoT Device Tag'
    _order = 'name'

    name = fields.Char(string='Tag Name', required=True)
    color = fields.Integer(string='Color Index')

    device_count = fields.Integer(string='Device Count', compute='_compute_device_count')

    def _compute_device_count(self):
        for tag in self:
            tag.device_count = self.env['iot.device'].search_count([
                ('tag_ids', 'in', [tag.id])
            ])
```

#### Dev 2 (Full-Stack) - 8 hours

**Task 2.1: OTA Update Service (4h)**

```python
# odoo/addons/smart_dairy_iot/services/ota_service.py
import logging
import json
from datetime import datetime
from odoo import api, models, fields
from odoo.exceptions import UserError

_logger = logging.getLogger(__name__)

class IoTOTAService(models.AbstractModel):
    _name = 'iot.ota.service'
    _description = 'IoT OTA Update Service'

    @api.model
    def schedule_update(self, device_ids, firmware_id, scheduled_at, options=None):
        """
        Schedule firmware update for devices
        """
        options = options or {}

        firmware = self.env['iot.firmware.version'].browse(firmware_id)
        if not firmware.exists():
            raise UserError('Firmware version not found')

        devices = self.env['iot.device'].browse(device_ids)

        # Validate compatibility
        incompatible = devices.filtered(lambda d: d.device_type != firmware.device_type)
        if incompatible:
            raise UserError(f'Incompatible devices: {", ".join(incompatible.mapped("device_id"))}')

        # Create update job
        job = self.env['iot.firmware.update.job'].create({
            'name': f'OTA Update - {firmware.version} - {datetime.now().strftime("%Y%m%d_%H%M%S")}',
            'device_ids': [(6, 0, device_ids)],
            'firmware_id': firmware_id,
            'scheduled_at': scheduled_at,
            'force_update': options.get('force_update', False),
            'rollback_on_failure': options.get('rollback_on_failure', True),
            'notify_on_complete': options.get('notify_on_complete', True),
        })

        # Update device status
        devices.write({
            'target_firmware_id': firmware_id,
            'firmware_update_status': 'scheduled',
            'firmware_update_scheduled': scheduled_at,
        })

        job.action_schedule()

        _logger.info(f'Scheduled OTA update job {job.id} for {len(devices)} devices')

        return job.id

    @api.model
    def process_update_job(self, job_id):
        """
        Process a scheduled update job
        """
        job = self.env['iot.firmware.update.job'].browse(job_id)
        if not job.exists():
            _logger.error(f'Update job {job_id} not found')
            return False

        if job.state != 'scheduled':
            _logger.warning(f'Update job {job_id} not in scheduled state')
            return False

        job.action_start()

        mqtt_handler = self.env['iot.mqtt.service'].get_handler()

        for device in job.device_ids:
            try:
                self._send_update_command(mqtt_handler, device, job.firmware_id)

                # Create log entry
                self.env['iot.firmware.update.log'].create({
                    'job_id': job.id,
                    'device_id': device.id,
                    'previous_version': device.firmware_version,
                    'target_version': job.firmware_id.version,
                    'state': 'downloading',
                })

                device.write({'firmware_update_status': 'downloading'})

            except Exception as e:
                _logger.error(f'Failed to send update to device {device.device_id}: {str(e)}')
                self.env['iot.firmware.update.log'].create({
                    'job_id': job.id,
                    'device_id': device.id,
                    'state': 'failed',
                    'error_message': str(e),
                })
                job.write({'failed_count': job.failed_count + 1})

        return True

    def _send_update_command(self, mqtt_handler, device, firmware):
        """
        Send OTA update command to device via MQTT
        """
        update_payload = {
            'command': 'firmware_update',
            'timestamp': datetime.now().isoformat(),
            'firmware': {
                'version': firmware.version,
                'download_url': firmware.download_url,
                'checksum': firmware.firmware_checksum,
                'size': firmware.firmware_size,
                'mandatory': firmware.is_mandatory,
            }
        }

        mqtt_handler.publish(
            device.command_topic,
            update_payload,
            qos=2,
            retain=False
        )

        _logger.info(f'Sent OTA update command to device {device.device_id}')

    @api.model
    def handle_update_status(self, device_id, status_data):
        """
        Handle firmware update status report from device
        """
        device = self.env['iot.device'].search([
            ('device_id', '=', device_id)
        ], limit=1)

        if not device:
            _logger.warning(f'Device {device_id} not found for update status')
            return

        status = status_data.get('status')
        error = status_data.get('error')

        # Update device status
        update_vals = {
            'firmware_update_status': status,
        }

        if status == 'completed':
            update_vals.update({
                'firmware_id': device.target_firmware_id.id,
                'firmware_version': device.target_firmware_id.version,
                'firmware_update_completed': datetime.now(),
                'target_firmware_id': False,
            })

            # Update job statistics
            log = self.env['iot.firmware.update.log'].search([
                ('device_id', '=', device.id),
                ('state', 'in', ['downloading', 'installing', 'verifying'])
            ], limit=1, order='timestamp desc')

            if log:
                log.write({
                    'state': 'completed',
                    'duration_seconds': (datetime.now() - log.timestamp).seconds,
                })
                if log.job_id:
                    log.job_id.write({
                        'success_count': log.job_id.success_count + 1
                    })

        elif status == 'failed':
            update_vals['firmware_update_error'] = error

            log = self.env['iot.firmware.update.log'].search([
                ('device_id', '=', device.id),
                ('state', 'in', ['downloading', 'installing', 'verifying'])
            ], limit=1, order='timestamp desc')

            if log:
                log.write({
                    'state': 'failed',
                    'error_message': error,
                })
                if log.job_id:
                    log.job_id.write({
                        'failed_count': log.job_id.failed_count + 1
                    })

        device.write(update_vals)

    @api.model
    def rollback_device(self, device_id, target_version=None):
        """
        Rollback device to previous firmware version
        """
        device = self.env['iot.device'].browse(device_id)
        if not device.exists():
            raise UserError('Device not found')

        # Get previous version from logs
        if not target_version:
            last_log = self.env['iot.firmware.update.log'].search([
                ('device_id', '=', device_id),
                ('state', '=', 'completed'),
            ], limit=1, order='timestamp desc')

            if last_log:
                target_version = last_log.previous_version

        if not target_version:
            raise UserError('No previous version found for rollback')

        # Find firmware version
        firmware = self.env['iot.firmware.version'].search([
            ('version', '=', target_version),
            ('device_type', '=', device.device_type),
        ], limit=1)

        if not firmware:
            raise UserError(f'Firmware version {target_version} not found')

        # Schedule rollback
        return self.schedule_update(
            [device_id],
            firmware.id,
            datetime.now(),
            {'force_update': True}
        )
```

**Task 2.2: Health Monitoring Scheduler (2h)**

```python
# odoo/addons/smart_dairy_iot/services/health_monitor.py
import logging
from datetime import datetime, timedelta
from odoo import api, models, fields

_logger = logging.getLogger(__name__)

class IoTHealthMonitor(models.AbstractModel):
    _name = 'iot.health.monitor'
    _description = 'IoT Device Health Monitor'

    @api.model
    def run_health_checks(self):
        """
        Run health checks on all active devices
        Called by scheduled cron job
        """
        devices = self.env['iot.device'].search([
            ('state', '=', 'active'),
            ('maintenance_mode', '=', False),
        ])

        _logger.info(f'Running health checks for {len(devices)} devices')

        for device in devices:
            try:
                self._check_device_health(device)
            except Exception as e:
                _logger.error(f'Health check failed for device {device.device_id}: {str(e)}')

        return True

    def _check_device_health(self, device):
        """
        Check health of individual device
        """
        issues = []
        now = datetime.now()

        # Check if device is offline
        offline_threshold = now - timedelta(minutes=10)
        if device.last_seen and device.last_seen < offline_threshold:
            issues.append({
                'type': 'offline',
                'severity': 'critical',
                'message': f'Device offline since {device.last_seen}',
            })

        # Check battery level
        if device.battery_level and device.battery_level < device.low_battery_threshold:
            severity = 'critical' if device.battery_level < 10 else 'warning'
            issues.append({
                'type': 'low_battery',
                'severity': severity,
                'message': f'Battery level at {device.battery_level}%',
            })

        # Check signal strength
        if device.signal_strength and device.signal_strength < -90:
            issues.append({
                'type': 'poor_signal',
                'severity': 'warning',
                'message': f'Signal strength at {device.signal_strength} dBm',
            })

        # Check error count
        if device.error_count > 10:
            issues.append({
                'type': 'high_errors',
                'severity': 'critical',
                'message': f'High error count: {device.error_count}',
            })
        elif device.error_count > 5:
            issues.append({
                'type': 'elevated_errors',
                'severity': 'warning',
                'message': f'Elevated error count: {device.error_count}',
            })

        # Check calibration status
        if device.calibration_status == 'overdue':
            issues.append({
                'type': 'calibration_overdue',
                'severity': 'warning',
                'message': 'Device calibration overdue',
            })

        # Update last health check
        device.write({'last_health_check': now})

        # Create alerts for issues
        for issue in issues:
            self._create_or_update_alert(device, issue)

        # Auto-resolve alerts for issues that no longer exist
        self._resolve_stale_alerts(device, issues)

    def _create_or_update_alert(self, device, issue):
        """
        Create or update alert for device issue
        """
        existing_alert = self.env['iot.device.alert'].search([
            ('device_id', '=', device.id),
            ('alert_type', '=', issue['type']),
            ('state', '=', 'active'),
        ], limit=1)

        if existing_alert:
            existing_alert.write({
                'occurrence_count': existing_alert.occurrence_count + 1,
                'last_occurrence': datetime.now(),
                'message': issue['message'],
            })
        else:
            self.env['iot.device.alert'].create({
                'device_id': device.id,
                'alert_type': issue['type'],
                'severity': issue['severity'],
                'message': issue['message'],
            })

    def _resolve_stale_alerts(self, device, current_issues):
        """
        Resolve alerts that no longer apply
        """
        current_types = [i['type'] for i in current_issues]

        stale_alerts = self.env['iot.device.alert'].search([
            ('device_id', '=', device.id),
            ('alert_type', 'not in', current_types),
            ('state', '=', 'active'),
        ])

        for alert in stale_alerts:
            alert.write({
                'state': 'resolved',
                'resolved_at': datetime.now(),
                'resolution_notes': 'Auto-resolved: Issue no longer detected',
            })


class IoTDeviceAlert(models.Model):
    _name = 'iot.device.alert'
    _description = 'IoT Device Alert'
    _order = 'create_date desc'

    device_id = fields.Many2one('iot.device', string='Device', required=True, ondelete='cascade')

    alert_type = fields.Selection([
        ('offline', 'Device Offline'),
        ('low_battery', 'Low Battery'),
        ('poor_signal', 'Poor Signal'),
        ('high_errors', 'High Error Rate'),
        ('elevated_errors', 'Elevated Errors'),
        ('calibration_overdue', 'Calibration Overdue'),
        ('firmware_failed', 'Firmware Update Failed'),
        ('custom', 'Custom Alert'),
    ], string='Alert Type', required=True)

    severity = fields.Selection([
        ('low', 'Low'),
        ('warning', 'Warning'),
        ('critical', 'Critical'),
    ], string='Severity', required=True)

    state = fields.Selection([
        ('active', 'Active'),
        ('acknowledged', 'Acknowledged'),
        ('resolved', 'Resolved'),
    ], string='Status', default='active')

    message = fields.Text(string='Alert Message')

    occurrence_count = fields.Integer(string='Occurrences', default=1)
    first_occurrence = fields.Datetime(string='First Occurrence', default=fields.Datetime.now)
    last_occurrence = fields.Datetime(string='Last Occurrence', default=fields.Datetime.now)

    acknowledged_by = fields.Many2one('res.users', string='Acknowledged By')
    acknowledged_at = fields.Datetime(string='Acknowledged At')

    resolved_at = fields.Datetime(string='Resolved At')
    resolution_notes = fields.Text(string='Resolution Notes')

    def action_acknowledge(self):
        """Acknowledge alert"""
        self.write({
            'state': 'acknowledged',
            'acknowledged_by': self.env.user.id,
            'acknowledged_at': datetime.now(),
        })

    def action_resolve(self):
        """Resolve alert"""
        return {
            'type': 'ir.actions.act_window',
            'name': 'Resolve Alert',
            'res_model': 'iot.alert.resolve.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_alert_id': self.id},
        }
```

**Task 2.3: Bulk Import CSV Handler (2h)**

```python
# odoo/addons/smart_dairy_iot/wizards/device_import_wizard.py
from odoo import models, fields, api
from odoo.exceptions import UserError, ValidationError
import base64
import csv
import io
import logging

_logger = logging.getLogger(__name__)

class IoTDeviceImportWizard(models.TransientModel):
    _name = 'iot.device.import.wizard'
    _description = 'IoT Device Bulk Import Wizard'

    csv_file = fields.Binary(string='CSV File', required=True)
    filename = fields.Char(string='Filename')

    farm_id = fields.Many2one(
        'farm.location',
        string='Default Farm',
        help='Used if farm not specified in CSV'
    )

    import_mode = fields.Selection([
        ('create', 'Create Only'),
        ('update', 'Update Existing'),
        ('create_update', 'Create and Update'),
    ], string='Import Mode', default='create', required=True)

    skip_errors = fields.Boolean(string='Skip Errors', default=False)

    preview_lines = fields.Text(string='Preview', readonly=True)
    result_summary = fields.Text(string='Import Results', readonly=True)

    state = fields.Selection([
        ('upload', 'Upload'),
        ('preview', 'Preview'),
        ('import', 'Import'),
        ('done', 'Done'),
    ], string='State', default='upload')

    # Statistics
    total_rows = fields.Integer(string='Total Rows')
    created_count = fields.Integer(string='Created')
    updated_count = fields.Integer(string='Updated')
    skipped_count = fields.Integer(string='Skipped')
    error_count = fields.Integer(string='Errors')
    error_log = fields.Text(string='Error Log')

    @api.onchange('csv_file')
    def _onchange_csv_file(self):
        """Generate preview when file is uploaded"""
        if self.csv_file:
            try:
                content = base64.b64decode(self.csv_file).decode('utf-8')
                reader = csv.DictReader(io.StringIO(content))

                preview_lines = []
                for i, row in enumerate(reader):
                    if i >= 5:  # Preview first 5 rows
                        break
                    preview_lines.append(str(row))

                self.preview_lines = '\n'.join(preview_lines)
                self.state = 'preview'
            except Exception as e:
                self.preview_lines = f'Error reading file: {str(e)}'

    def action_preview(self):
        """Show import preview"""
        self.ensure_one()

        if not self.csv_file:
            raise UserError('Please upload a CSV file')

        content = base64.b64decode(self.csv_file).decode('utf-8')
        reader = csv.DictReader(io.StringIO(content))

        rows = list(reader)
        self.total_rows = len(rows)

        self.state = 'preview'
        return self._reopen_wizard()

    def action_import(self):
        """Execute the import"""
        self.ensure_one()

        if not self.csv_file:
            raise UserError('Please upload a CSV file')

        content = base64.b64decode(self.csv_file).decode('utf-8')
        reader = csv.DictReader(io.StringIO(content))

        created = 0
        updated = 0
        skipped = 0
        errors = []

        required_fields = ['device_id', 'name', 'device_type']

        for row_num, row in enumerate(reader, start=2):
            try:
                # Validate required fields
                for field in required_fields:
                    if not row.get(field):
                        raise ValidationError(f'Missing required field: {field}')

                # Check if device exists
                existing = self.env['iot.device'].search([
                    ('device_id', '=', row['device_id'])
                ], limit=1)

                if existing:
                    if self.import_mode == 'create':
                        skipped += 1
                        continue

                    # Update existing device
                    vals = self._prepare_update_vals(row)
                    existing.write(vals)
                    updated += 1

                else:
                    if self.import_mode == 'update':
                        skipped += 1
                        continue

                    # Create new device
                    vals = self._prepare_create_vals(row)
                    self.env['iot.device'].create(vals)
                    created += 1

            except Exception as e:
                error_msg = f'Row {row_num}: {str(e)}'
                errors.append(error_msg)
                _logger.error(f'Import error - {error_msg}')

                if not self.skip_errors:
                    raise UserError(f'Import failed at row {row_num}: {str(e)}')

        self.write({
            'created_count': created,
            'updated_count': updated,
            'skipped_count': skipped,
            'error_count': len(errors),
            'error_log': '\n'.join(errors) if errors else 'No errors',
            'result_summary': f'Created: {created}, Updated: {updated}, Skipped: {skipped}, Errors: {len(errors)}',
            'state': 'done',
        })

        return self._reopen_wizard()

    def _prepare_create_vals(self, row):
        """Prepare values for device creation"""
        farm_id = self._get_farm_id(row.get('farm_code', ''))

        return {
            'device_id': row['device_id'],
            'name': row['name'],
            'device_type': row['device_type'],
            'farm_id': farm_id or self.farm_id.id,
            'manufacturer': row.get('manufacturer', ''),
            'model': row.get('model', ''),
            'serial_number': row.get('serial_number', ''),
            'mac_address': row.get('mac_address', ''),
            'location_description': row.get('location', ''),
            'provisioning_method': 'csv_import',
        }

    def _prepare_update_vals(self, row):
        """Prepare values for device update"""
        vals = {}

        if row.get('name'):
            vals['name'] = row['name']
        if row.get('manufacturer'):
            vals['manufacturer'] = row['manufacturer']
        if row.get('model'):
            vals['model'] = row['model']
        if row.get('serial_number'):
            vals['serial_number'] = row['serial_number']
        if row.get('mac_address'):
            vals['mac_address'] = row['mac_address']
        if row.get('location'):
            vals['location_description'] = row['location']

        return vals

    def _get_farm_id(self, farm_code):
        """Get farm ID from code"""
        if not farm_code:
            return False
        farm = self.env['farm.location'].search([('code', '=', farm_code)], limit=1)
        return farm.id if farm else False

    def _reopen_wizard(self):
        """Reopen wizard with current state"""
        return {
            'type': 'ir.actions.act_window',
            'res_model': self._name,
            'res_id': self.id,
            'view_mode': 'form',
            'target': 'new',
        }
```

#### Dev 3 (Frontend Lead) - 8 hours

**Task 3.1: Device Provisioning Wizard UI (4h)**

```javascript
/** @odoo-module */
// odoo/addons/smart_dairy_iot/static/src/js/device_provisioning_wizard.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { registry } from "@web/core/registry";
import { useService } from "@web/core/utils/hooks";

export class DeviceProvisioningWizard extends Component {
    static template = "smart_dairy_iot.ProvisioningWizard";

    setup() {
        this.orm = useService("orm");
        this.notification = useService("notification");
        this.action = useService("action");

        this.state = useState({
            step: 1,
            totalSteps: 4,
            loading: false,
            farms: [],
            deviceTypes: [
                { value: 'milk_meter', label: 'Milk Meter', icon: 'fa-tint' },
                { value: 'temperature', label: 'Temperature Sensor', icon: 'fa-thermometer-half' },
                { value: 'collar', label: 'Activity Collar', icon: 'fa-paw' },
                { value: 'feeder', label: 'Feed Dispenser', icon: 'fa-utensils' },
            ],
            device: {
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
            credentials: null,
            errors: {},
            createdDevice: null,
        });

        onWillStart(async () => {
            await this.loadFarms();
        });
    }

    async loadFarms() {
        const farms = await this.orm.searchRead(
            "farm.location",
            [],
            ["id", "name", "code"]
        );
        this.state.farms = farms;
    }

    // Step Navigation
    canProceed() {
        const { step, device } = this.state;

        switch (step) {
            case 1: // Device Type Selection
                return !!device.device_type;
            case 2: // Basic Info
                return device.name && device.device_id && device.farm_id;
            case 3: // Hardware Info
                return true; // Optional fields
            case 4: // Review
                return true;
            default:
                return false;
        }
    }

    nextStep() {
        if (this.canProceed() && this.state.step < this.state.totalSteps) {
            this.state.step++;
        }
    }

    prevStep() {
        if (this.state.step > 1) {
            this.state.step--;
        }
    }

    // Field Updates
    updateField(field, value) {
        this.state.device[field] = value;
        if (this.state.errors[field]) {
            delete this.state.errors[field];
        }
    }

    selectDeviceType(type) {
        this.state.device.device_type = type;

        // Generate device ID prefix based on type
        const prefixes = {
            'milk_meter': 'MM',
            'temperature': 'TS',
            'collar': 'AC',
            'feeder': 'FD',
        };

        if (!this.state.device.device_id) {
            this.state.device.device_id = prefixes[type] || 'DV';
        }
    }

    // Validation
    validateStep2() {
        const errors = {};
        const { device } = this.state;

        if (!device.name?.trim()) {
            errors.name = 'Device name is required';
        }

        if (!device.device_id?.trim()) {
            errors.device_id = 'Device ID is required';
        } else if (!/^[A-Z]{2}[0-9]{3,6}$/.test(device.device_id)) {
            errors.device_id = 'Format: XX### (e.g., MM001)';
        }

        if (!device.farm_id) {
            errors.farm_id = 'Farm is required';
        }

        this.state.errors = errors;
        return Object.keys(errors).length === 0;
    }

    // Create Device
    async createDevice() {
        if (!this.validateStep2()) {
            this.state.step = 2;
            return;
        }

        this.state.loading = true;

        try {
            // Create device
            const deviceId = await this.orm.create("iot.device", [{
                ...this.state.device,
                provisioning_method: 'manual',
            }]);

            // Provision device to get credentials
            const result = await this.orm.call(
                "iot.device",
                "action_provision",
                [[deviceId]]
            );

            // Get created device details
            const device = await this.orm.read("iot.device", [deviceId], [
                'name', 'device_id', 'mqtt_username', 'mqtt_client_id',
                'data_topic', 'status_topic', 'command_topic', 'config_topic'
            ]);

            this.state.createdDevice = device[0];

            // Extract credentials from wizard result if available
            if (result && result.context) {
                this.state.credentials = {
                    username: result.context.default_mqtt_username,
                    password: result.context.default_mqtt_password,
                    client_id: result.context.default_mqtt_client_id,
                };
            }

            this.notification.add("Device provisioned successfully!", { type: "success" });
            this.state.step = 5; // Success step

        } catch (error) {
            console.error("Error creating device:", error);
            this.notification.add("Failed to create device: " + error.message, { type: "danger" });
        } finally {
            this.state.loading = false;
        }
    }

    // Utility Methods
    getStepTitle() {
        const titles = {
            1: 'Select Device Type',
            2: 'Device Information',
            3: 'Hardware Details',
            4: 'Review & Confirm',
            5: 'Device Created',
        };
        return titles[this.state.step] || '';
    }

    getDeviceTypeIcon(type) {
        const dt = this.state.deviceTypes.find(t => t.value === type);
        return dt ? dt.icon : 'fa-microchip';
    }

    getDeviceTypeLabel(type) {
        const dt = this.state.deviceTypes.find(t => t.value === type);
        return dt ? dt.label : type;
    }

    getFarmName(farmId) {
        const farm = this.state.farms.find(f => f.id === farmId);
        return farm ? farm.name : '';
    }

    downloadCredentials() {
        const { createdDevice, credentials } = this.state;
        if (!createdDevice || !credentials) return;

        const content = `
Smart Dairy IoT Device Credentials
==================================

Device ID: ${createdDevice.device_id}
Device Name: ${createdDevice.name}

MQTT Credentials:
-----------------
Username: ${credentials.username}
Password: ${credentials.password}
Client ID: ${credentials.client_id}

Topics:
-------
Data: ${createdDevice.data_topic}
Status: ${createdDevice.status_topic}
Commands: ${createdDevice.command_topic}
Config: ${createdDevice.config_topic}

IMPORTANT: Save these credentials securely.
The password will not be shown again.
`;

        const blob = new Blob([content], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `device_${createdDevice.device_id}_credentials.txt`;
        a.click();
        URL.revokeObjectURL(url);
    }

    close() {
        this.action.doAction({ type: 'ir.actions.act_window_close' });
    }

    createAnother() {
        this.state.step = 1;
        this.state.device = {
            name: '',
            device_id: '',
            device_type: 'milk_meter',
            farm_id: null,
            manufacturer: '',
            model: '',
            serial_number: '',
            mac_address: '',
            location_description: '',
        };
        this.state.credentials = null;
        this.state.createdDevice = null;
        this.state.errors = {};
    }
}

DeviceProvisioningWizard.template = "smart_dairy_iot.ProvisioningWizard";
registry.category("actions").add("iot_device_provisioning_wizard", DeviceProvisioningWizard);
```

**Task 3.2: Mobile Device Scanner (Flutter) (4h)**

```dart
// lib/features/iot/screens/device_scanner_screen.dart
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:mobile_scanner/mobile_scanner.dart';
import 'package:provider/provider.dart';

class DeviceScannerScreen extends StatefulWidget {
  const DeviceScannerScreen({Key? key}) : super(key: key);

  @override
  State<DeviceScannerScreen> createState() => _DeviceScannerScreenState();
}

class _DeviceScannerScreenState extends State<DeviceScannerScreen> {
  MobileScannerController cameraController = MobileScannerController();
  bool isProcessing = false;
  String? lastScannedCode;

  @override
  void dispose() {
    cameraController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Scan Device'),
        actions: [
          IconButton(
            icon: ValueListenableBuilder(
              valueListenable: cameraController.torchState,
              builder: (context, state, child) {
                return Icon(
                  state == TorchState.on ? Icons.flash_on : Icons.flash_off,
                );
              },
            ),
            onPressed: () => cameraController.toggleTorch(),
          ),
          IconButton(
            icon: const Icon(Icons.switch_camera),
            onPressed: () => cameraController.switchCamera(),
          ),
        ],
      ),
      body: Column(
        children: [
          Expanded(
            flex: 3,
            child: Stack(
              children: [
                MobileScanner(
                  controller: cameraController,
                  onDetect: _onDetect,
                ),
                // Scan overlay
                CustomPaint(
                  painter: ScanOverlayPainter(),
                  child: const SizedBox.expand(),
                ),
                // Instructions
                Positioned(
                  bottom: 20,
                  left: 0,
                  right: 0,
                  child: Container(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 24,
                      vertical: 12,
                    ),
                    margin: const EdgeInsets.symmetric(horizontal: 40),
                    decoration: BoxDecoration(
                      color: Colors.black54,
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: const Text(
                      'Point camera at device QR code',
                      textAlign: TextAlign.center,
                      style: TextStyle(color: Colors.white),
                    ),
                  ),
                ),
              ],
            ),
          ),
          // Manual Entry Option
          Expanded(
            flex: 1,
            child: Container(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  const Text(
                    'Or enter device ID manually:',
                    style: TextStyle(fontSize: 14, color: Colors.grey),
                  ),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      Expanded(
                        child: TextField(
                          decoration: const InputDecoration(
                            hintText: 'Device ID (e.g., MM001)',
                            border: OutlineInputBorder(),
                            contentPadding: EdgeInsets.symmetric(
                              horizontal: 16,
                              vertical: 12,
                            ),
                          ),
                          textCapitalization: TextCapitalization.characters,
                          onSubmitted: _onManualEntry,
                        ),
                      ),
                      const SizedBox(width: 12),
                      ElevatedButton(
                        onPressed: () => _showManualEntryDialog(context),
                        child: const Icon(Icons.arrow_forward),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  void _onDetect(BarcodeCapture capture) {
    if (isProcessing) return;

    final List<Barcode> barcodes = capture.barcodes;
    for (final barcode in barcodes) {
      if (barcode.rawValue != null && barcode.rawValue != lastScannedCode) {
        lastScannedCode = barcode.rawValue;
        _processScannedCode(barcode.rawValue!);
        break;
      }
    }
  }

  Future<void> _processScannedCode(String code) async {
    setState(() => isProcessing = true);

    try {
      // Parse QR code data
      final deviceData = _parseQRCode(code);

      if (deviceData != null) {
        // Vibrate feedback
        HapticFeedback.mediumImpact();

        // Navigate to device details/provisioning
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => DeviceProvisioningScreen(
              deviceData: deviceData,
            ),
          ),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Invalid QR code format'),
            backgroundColor: Colors.red,
          ),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Error: ${e.toString()}'),
          backgroundColor: Colors.red,
        ),
      );
    } finally {
      setState(() => isProcessing = false);

      // Reset after delay to allow scanning new code
      Future.delayed(const Duration(seconds: 2), () {
        lastScannedCode = null;
      });
    }
  }

  Map<String, dynamic>? _parseQRCode(String code) {
    try {
      // Expected format: SMARTDAIRY:device_id:device_type:serial_number
      // Or JSON: {"device_id": "MM001", "type": "milk_meter", ...}

      if (code.startsWith('SMARTDAIRY:')) {
        final parts = code.split(':');
        if (parts.length >= 3) {
          return {
            'device_id': parts[1],
            'device_type': parts[2],
            'serial_number': parts.length > 3 ? parts[3] : null,
          };
        }
      } else if (code.startsWith('{')) {
        // JSON format
        return Map<String, dynamic>.from(
          (code as dynamic),
        );
      }

      // Try as plain device ID
      if (RegExp(r'^[A-Z]{2}[0-9]{3,6}$').hasMatch(code)) {
        return {
          'device_id': code,
          'device_type': _inferDeviceType(code),
        };
      }

      return null;
    } catch (e) {
      return null;
    }
  }

  String _inferDeviceType(String deviceId) {
    final prefix = deviceId.substring(0, 2);
    switch (prefix) {
      case 'MM':
        return 'milk_meter';
      case 'TS':
        return 'temperature';
      case 'AC':
        return 'collar';
      case 'FD':
        return 'feeder';
      default:
        return 'unknown';
    }
  }

  void _onManualEntry(String value) {
    if (value.isNotEmpty) {
      _processScannedCode(value.toUpperCase());
    }
  }

  void _showManualEntryDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Enter Device ID'),
        content: TextField(
          autofocus: true,
          textCapitalization: TextCapitalization.characters,
          decoration: const InputDecoration(
            hintText: 'MM001',
            border: OutlineInputBorder(),
          ),
          onSubmitted: (value) {
            Navigator.pop(context);
            _onManualEntry(value);
          },
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cancel'),
          ),
        ],
      ),
    );
  }
}

class ScanOverlayPainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()
      ..color = Colors.black54
      ..style = PaintingStyle.fill;

    final scanAreaSize = size.width * 0.7;
    final scanAreaLeft = (size.width - scanAreaSize) / 2;
    final scanAreaTop = (size.height - scanAreaSize) / 2;

    // Draw overlay with hole
    final path = Path()
      ..addRect(Rect.fromLTWH(0, 0, size.width, size.height))
      ..addRRect(RRect.fromRectAndRadius(
        Rect.fromLTWH(scanAreaLeft, scanAreaTop, scanAreaSize, scanAreaSize),
        const Radius.circular(16),
      ))
      ..fillType = PathFillType.evenOdd;

    canvas.drawPath(path, paint);

    // Draw scan area border
    final borderPaint = Paint()
      ..color = Colors.green
      ..style = PaintingStyle.stroke
      ..strokeWidth = 3;

    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromLTWH(scanAreaLeft, scanAreaTop, scanAreaSize, scanAreaSize),
        const Radius.circular(16),
      ),
      borderPaint,
    );

    // Draw corner accents
    final accentPaint = Paint()
      ..color = Colors.green
      ..style = PaintingStyle.stroke
      ..strokeWidth = 5
      ..strokeCap = StrokeCap.round;

    final cornerSize = 30.0;

    // Top-left
    canvas.drawLine(
      Offset(scanAreaLeft, scanAreaTop + cornerSize),
      Offset(scanAreaLeft, scanAreaTop),
      accentPaint,
    );
    canvas.drawLine(
      Offset(scanAreaLeft, scanAreaTop),
      Offset(scanAreaLeft + cornerSize, scanAreaTop),
      accentPaint,
    );

    // Top-right
    canvas.drawLine(
      Offset(scanAreaLeft + scanAreaSize - cornerSize, scanAreaTop),
      Offset(scanAreaLeft + scanAreaSize, scanAreaTop),
      accentPaint,
    );
    canvas.drawLine(
      Offset(scanAreaLeft + scanAreaSize, scanAreaTop),
      Offset(scanAreaLeft + scanAreaSize, scanAreaTop + cornerSize),
      accentPaint,
    );

    // Bottom-left
    canvas.drawLine(
      Offset(scanAreaLeft, scanAreaTop + scanAreaSize - cornerSize),
      Offset(scanAreaLeft, scanAreaTop + scanAreaSize),
      accentPaint,
    );
    canvas.drawLine(
      Offset(scanAreaLeft, scanAreaTop + scanAreaSize),
      Offset(scanAreaLeft + cornerSize, scanAreaTop + scanAreaSize),
      accentPaint,
    );

    // Bottom-right
    canvas.drawLine(
      Offset(scanAreaLeft + scanAreaSize - cornerSize, scanAreaTop + scanAreaSize),
      Offset(scanAreaLeft + scanAreaSize, scanAreaTop + scanAreaSize),
      accentPaint,
    );
    canvas.drawLine(
      Offset(scanAreaLeft + scanAreaSize, scanAreaTop + scanAreaSize - cornerSize),
      Offset(scanAreaLeft + scanAreaSize, scanAreaTop + scanAreaSize),
      accentPaint,
    );
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}
```

---

### Days 662-670: Remaining Development

**Day 662:** Device Diagnostics API, Health Dashboard, QR Code Generator
**Day 663:** Firmware Update Wizard UI, Update Status Monitoring
**Day 664:** Bulk Import UI, CSV Template Generator, Validation Preview
**Day 665:** Device Group Management UI, Tag Management
**Day 666:** Mobile Provisioning Flow, NFC Integration
**Day 667:** Health Monitoring Dashboard, Real-time Status Updates
**Day 668:** Firmware Rollback UI, Update History View
**Day 669:** Unit Tests, Integration Tests, Load Testing
**Day 670:** Documentation, Security Review, Demo Preparation

---

## 7. Technical Specifications

### 7.1 Database Schema Extensions

```sql
-- Device groups
CREATE TABLE iot_device_group (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    sequence INTEGER DEFAULT 10,
    farm_id INTEGER REFERENCES farm_location(id),
    parent_id INTEGER REFERENCES iot_device_group(id),
    active BOOLEAN DEFAULT true
);

-- Device tags
CREATE TABLE iot_device_tag (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    color INTEGER DEFAULT 0
);

-- Device-tag many2many
CREATE TABLE iot_device_tag_rel (
    device_id INTEGER REFERENCES iot_device(id),
    tag_id INTEGER REFERENCES iot_device_tag(id),
    PRIMARY KEY (device_id, tag_id)
);

-- Firmware versions
CREATE TABLE iot_firmware_version (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    version VARCHAR(50) NOT NULL,
    device_type VARCHAR(50) NOT NULL,
    firmware_binary BYTEA,
    firmware_checksum VARCHAR(64),
    state VARCHAR(20) DEFAULT 'draft',
    release_date DATE,
    UNIQUE(version, device_type)
);

-- Device alerts
CREATE TABLE iot_device_alert (
    id SERIAL PRIMARY KEY,
    device_id INTEGER REFERENCES iot_device(id),
    alert_type VARCHAR(50) NOT NULL,
    severity VARCHAR(20) NOT NULL,
    state VARCHAR(20) DEFAULT 'active',
    message TEXT,
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 7.2 API Endpoints

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| POST | /api/v1/iot/devices/import | Bulk import devices |
| GET | /api/v1/iot/devices/{id}/health | Get device health status |
| POST | /api/v1/iot/devices/{id}/diagnostic | Run device diagnostic |
| POST | /api/v1/iot/firmware/update | Schedule firmware update |
| GET | /api/v1/iot/firmware/versions | List firmware versions |
| POST | /api/v1/iot/devices/{id}/restart | Restart device |

---

## 8. Milestone Sign-off Checklist

- [ ] Device Registry extension complete with all fields
- [ ] Device Provisioning Wizard functional
- [ ] Firmware Version Management working
- [ ] OTA Update Service operational
- [ ] Health Monitoring running on schedule
- [ ] Bulk CSV Import tested with 500+ devices
- [ ] Device Diagnostics API working
- [ ] OWL Device Management UI enhancements complete
- [ ] Mobile Device Scanner (Flutter) functional
- [ ] QR Code generation working
- [ ] Unit tests passing (>80% coverage)
- [ ] Integration tests passing
- [ ] Documentation updated
- [ ] Demo prepared and delivered

---

**Document End**

*Last Updated: 2026-02-04*
*Milestone 92: Device Registry & Provisioning*
