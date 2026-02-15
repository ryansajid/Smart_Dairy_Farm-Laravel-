# Milestone 98: Real-time Alerts & Notifications

## Smart Dairy Digital Smart Portal + ERP System (Odoo 19 CE)

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P10-M98-v1.0 |
| **Milestone** | 98 of 100 |
| **Phase** | Phase 10: Commerce - IoT Integration Core |
| **Days** | 721-730 (10 working days) |
| **Duration** | 2 weeks |
| **Developers** | 3 Full Stack Developers |
| **Total Effort** | 240 person-hours |
| **Status** | Planned |
| **Version** | 1.0 |
| **Last Updated** | 2026-02-04 |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 98 implements the comprehensive real-time alert and notification engine for the Smart Dairy IoT platform. This milestone creates a unified alerting system that monitors all IoT data streams, evaluates alert rules, and delivers notifications through multiple channels including push notifications, SMS, email, and WebSocket for immediate user response.

### 1.2 Scope

This milestone covers:
- Alert rule engine with condition evaluation
- Alert configuration and management model
- Multi-channel notification dispatcher
- Firebase Cloud Messaging (FCM) integration
- SMS alert service (SSL Wireless)
- Email alert templates
- WebSocket real-time push
- Escalation workflow system
- Alert dashboard and analytics
- Alert suppression and deduplication

### 1.3 Key Outcomes

| Outcome | Success Measure |
|---------|-----------------|
| Alert Delivery | <30 second latency |
| Multi-Channel | 4+ notification channels |
| Escalation | 3-tier escalation workflow |
| Reliability | 99.9% delivery success |
| Deduplication | No duplicate alerts within window |
| Analytics | Alert metrics and trends |

---

## 2. Objectives

| # | Objective | Priority | Measurable Target |
|---|-----------|----------|-------------------|
| O1 | Implement alert rule engine | Critical | Support 10+ condition types |
| O2 | Create alert configuration model | Critical | Unlimited rules per farm |
| O3 | Build multi-channel dispatcher | Critical | 4+ channels supported |
| O4 | Integrate Firebase Cloud Messaging | High | <5 second push delivery |
| O5 | Implement SMS service | High | <10 second SMS delivery |
| O6 | Create email alert templates | High | Branded, responsive emails |
| O7 | Build WebSocket real-time push | Critical | <1 second latency |
| O8 | Develop escalation workflow | High | Configurable tiers |

---

## 3. Key Deliverables

| # | Deliverable | Description | Owner | Priority |
|---|-------------|-------------|-------|----------|
| D1 | Alert Rule Engine | Condition evaluation service | Dev 1 | Critical |
| D2 | Alert Configuration Model | `iot.alert_config` Odoo model | Dev 1 | Critical |
| D3 | Notification Dispatcher | Multi-channel routing | Dev 2 | Critical |
| D4 | FCM Integration | Push notification service | Dev 2 | High |
| D5 | SMS Service | SSL Wireless integration | Dev 2 | High |
| D6 | Email Templates | Responsive alert emails | Dev 3 | High |
| D7 | WebSocket Service | Real-time browser push | Dev 2 | Critical |
| D8 | Escalation Engine | Timed escalation workflow | Dev 1 | High |
| D9 | Alert Dashboard | OWL alert management | Dev 3 | High |
| D10 | Alert Analytics | Metrics and reporting | Dev 3 | Medium |
| D11 | Mobile Alert Screen | Flutter notification UI | Dev 3 | High |
| D12 | Suppression Rules | Deduplication logic | Dev 1 | Medium |

---

## 4. Day-by-Day Development Plan

### Day 721: Alert Rule Engine Design

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-2 | Design alert rule schema | Rule specification |
| 2-5 | Implement rule condition parser | Condition parser |
| 5-8 | Create rule evaluation engine | Evaluation engine |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Design notification dispatcher architecture | Architecture doc |
| 3-6 | Setup notification queue (Redis) | Queue infrastructure |
| 6-8 | Create dispatcher base service | Dispatcher scaffold |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-2 | Design alert dashboard wireframes | Figma mockups |
| 2-5 | Create alert rule builder component | Rule builder UI |
| 5-8 | Implement condition selector | Condition UI |

---

### Day 722: Alert Configuration Model

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement `iot.alert_config` model | Alert config model |
| 3-5 | Create alert instance model | Alert instance |
| 5-8 | Build alert history model | Alert history |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement notification channel model | Channel model |
| 3-5 | Create channel configuration | Config model |
| 5-8 | Build channel health monitoring | Health checks |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create alert config form view | Config form |
| 3-6 | Implement recipient selector | Recipient UI |
| 6-8 | Build channel configuration UI | Channel config |

---

### Day 723: Rule Evaluation & Triggering

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement threshold evaluation | Threshold logic |
| 3-5 | Create compound condition logic | AND/OR evaluation |
| 5-8 | Build rate-of-change detection | Rate detection |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create alert trigger service | Trigger service |
| 3-5 | Implement alert creation pipeline | Alert pipeline |
| 5-8 | Build alert state machine | State management |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create rule testing interface | Test interface |
| 3-6 | Implement condition preview | Preview UI |
| 6-8 | Build rule simulation tool | Simulation tool |

---

### Day 724: FCM Push Notifications

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement FCM service class | FCM service |
| 3-5 | Create device token management | Token model |
| 5-8 | Build notification payload builder | Payload builder |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Configure Firebase project | Firebase setup |
| 3-5 | Implement batch notification sending | Batch sender |
| 5-8 | Create delivery tracking | Delivery tracking |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create Flutter FCM handler | FCM handler |
| 3-6 | Implement notification display | Notification UI |
| 6-8 | Build notification actions | Action handlers |

---

### Day 725: SMS & Email Channels

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement SMS service (SSL Wireless) | SMS service |
| 3-5 | Create SMS template model | SMS templates |
| 5-8 | Build SMS delivery tracking | SMS tracking |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create email notification service | Email service |
| 3-5 | Build responsive email templates | Email templates |
| 5-8 | Implement email delivery verification | Delivery check |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create email template preview | Template preview |
| 3-6 | Implement SMS preview | SMS preview |
| 6-8 | Build delivery status display | Status display |

---

### Day 726: WebSocket Real-time Push

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement WebSocket server | WS server |
| 3-5 | Create channel subscription manager | Subscription mgr |
| 5-8 | Build user connection tracking | Connection tracking |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement alert broadcast service | Broadcast service |
| 3-5 | Create WebSocket authentication | WS auth |
| 5-8 | Build reconnection handling | Reconnection logic |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create OWL WebSocket client | WS client |
| 3-6 | Implement real-time alert toast | Alert toast |
| 6-8 | Build alert sound notifications | Sound alerts |

---

### Day 727: Escalation Workflow

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement escalation model | Escalation model |
| 3-5 | Create escalation scheduler | Scheduler |
| 5-8 | Build escalation notification logic | Escalation logic |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement acknowledgment workflow | Ack workflow |
| 3-5 | Create escalation history tracking | History tracking |
| 5-8 | Build escalation reports | Reports |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create escalation config UI | Config UI |
| 3-6 | Implement escalation timeline | Timeline view |
| 6-8 | Build acknowledgment interface | Ack interface |

---

### Day 728: Alert Dashboard & Analytics

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create alert statistics API | Stats API |
| 3-5 | Implement alert trends calculation | Trends calc |
| 5-8 | Build alert export functionality | Export service |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create deduplication service | Dedup service |
| 3-5 | Implement alert suppression rules | Suppression |
| 5-8 | Build alert correlation | Correlation |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create main alert dashboard | Dashboard |
| 3-6 | Implement alert analytics charts | Analytics charts |
| 6-8 | Build alert filtering and search | Filter/search |

---

### Day 729: Testing & Integration

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write unit tests (>80% coverage) | Unit tests |
| 3-5 | Create integration tests | Integration tests |
| 5-8 | Test escalation workflows | Workflow tests |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Test notification delivery | Delivery tests |
| 3-5 | Load test notification service | Load tests |
| 5-8 | Security testing | Security audit |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | UI component testing | Component tests |
| 3-5 | Mobile notification testing | Mobile tests |
| 5-8 | End-to-end testing | E2E tests |

---

### Day 730: Documentation & Demo

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write API documentation | API docs |
| 3-5 | Create rule configuration guide | Config guide |
| 5-8 | Bug fixes and optimization | Stable release |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write channel integration guide | Integration guide |
| 3-5 | Create troubleshooting guide | Troubleshooting |
| 5-8 | Prepare demo environment | Demo setup |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write user guide | User guide |
| 3-5 | Create demo scenarios | Demo materials |
| 5-8 | Conduct milestone demo | Demo presentation |

---

## 5. Technical Specifications

### 5.1 Alert Configuration Model

```python
# smart_dairy_iot/models/iot_alert_config.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError
import json

class IoTAlertConfig(models.Model):
    _name = 'iot.alert_config'
    _description = 'IoT Alert Configuration'
    _order = 'priority, name'

    name = fields.Char(string='Alert Name', required=True)
    active = fields.Boolean(default=True)
    priority = fields.Selection([
        ('1', 'Critical'),
        ('2', 'High'),
        ('3', 'Medium'),
        ('4', 'Low'),
    ], string='Priority', default='3', required=True)

    # Scope
    farm_id = fields.Many2one(
        'res.partner',
        string='Farm',
        domain=[('is_farm', '=', True)]
    )
    device_type = fields.Selection([
        ('all', 'All Devices'),
        ('milk_meter', 'Milk Meters'),
        ('temp_sensor', 'Temperature Sensors'),
        ('collar', 'Activity Collars'),
        ('dispenser', 'Feed Dispensers'),
    ], string='Device Type', default='all')
    specific_device_ids = fields.Many2many(
        'iot.device',
        string='Specific Devices'
    )

    # Condition Type
    condition_type = fields.Selection([
        ('threshold', 'Threshold'),
        ('rate_of_change', 'Rate of Change'),
        ('missing_data', 'Missing Data'),
        ('pattern', 'Pattern Detection'),
        ('compound', 'Compound Condition'),
    ], string='Condition Type', required=True, default='threshold')

    # Threshold Conditions
    metric_name = fields.Char(string='Metric Name')
    operator = fields.Selection([
        ('>', 'Greater Than'),
        ('>=', 'Greater Than or Equal'),
        ('<', 'Less Than'),
        ('<=', 'Less Than or Equal'),
        ('==', 'Equal To'),
        ('!=', 'Not Equal To'),
        ('between', 'Between'),
        ('outside', 'Outside Range'),
    ], string='Operator', default='>')
    threshold_value = fields.Float(string='Threshold Value')
    threshold_min = fields.Float(string='Min Value (for range)')
    threshold_max = fields.Float(string='Max Value (for range)')

    # Rate of Change
    change_window_minutes = fields.Integer(
        string='Change Window (minutes)',
        default=5
    )
    change_threshold = fields.Float(string='Change Threshold')
    change_direction = fields.Selection([
        ('increase', 'Increase'),
        ('decrease', 'Decrease'),
        ('any', 'Any Change'),
    ], string='Change Direction', default='any')

    # Missing Data
    missing_timeout_minutes = fields.Integer(
        string='Missing Data Timeout (minutes)',
        default=15
    )

    # Compound Conditions
    condition_logic = fields.Selection([
        ('and', 'All Conditions (AND)'),
        ('or', 'Any Condition (OR)'),
    ], string='Condition Logic', default='and')
    sub_condition_ids = fields.One2many(
        'iot.alert_sub_condition',
        'parent_config_id',
        string='Sub-Conditions'
    )

    # Timing
    evaluation_interval = fields.Integer(
        string='Evaluation Interval (seconds)',
        default=60
    )
    cooldown_minutes = fields.Integer(
        string='Cooldown Period (minutes)',
        default=30,
        help='Minimum time between repeated alerts'
    )
    active_hours_start = fields.Float(string='Active Hours Start')
    active_hours_end = fields.Float(string='Active Hours End')
    active_days = fields.Char(
        string='Active Days (JSON)',
        default='[1,2,3,4,5,6,7]'
    )

    # Notification Channels
    enable_push = fields.Boolean(string='Push Notification', default=True)
    enable_sms = fields.Boolean(string='SMS Alert', default=False)
    enable_email = fields.Boolean(string='Email Alert', default=True)
    enable_websocket = fields.Boolean(string='Real-time Push', default=True)

    # Recipients
    recipient_user_ids = fields.Many2many(
        'res.users',
        string='Recipients'
    )
    recipient_role = fields.Selection([
        ('farm_manager', 'Farm Manager'),
        ('veterinarian', 'Veterinarian'),
        ('technician', 'Technician'),
        ('all', 'All Assigned Users'),
    ], string='Recipient Role')

    # Escalation
    enable_escalation = fields.Boolean(string='Enable Escalation')
    escalation_config_id = fields.Many2one(
        'iot.escalation_config',
        string='Escalation Configuration'
    )

    # Message Template
    alert_title_template = fields.Char(
        string='Alert Title Template',
        default='[{priority}] {alert_name}'
    )
    alert_message_template = fields.Text(
        string='Alert Message Template',
        default='{metric_name} is {value} {unit} (threshold: {threshold})'
    )

    # Statistics
    total_alerts = fields.Integer(
        string='Total Alerts',
        compute='_compute_statistics'
    )
    last_triggered = fields.Datetime(
        string='Last Triggered',
        readonly=True
    )

    @api.constrains('threshold_min', 'threshold_max')
    def _check_range_values(self):
        for config in self:
            if config.operator in ('between', 'outside'):
                if config.threshold_min >= config.threshold_max:
                    raise ValidationError(
                        'Max value must be greater than min value'
                    )

    def _compute_statistics(self):
        for config in self:
            config.total_alerts = self.env['iot.alert_instance'].search_count([
                ('config_id', '=', config.id)
            ])

    def evaluate_condition(self, reading):
        """Evaluate if reading triggers this alert"""
        self.ensure_one()

        if self.condition_type == 'threshold':
            return self._evaluate_threshold(reading)
        elif self.condition_type == 'rate_of_change':
            return self._evaluate_rate_of_change(reading)
        elif self.condition_type == 'missing_data':
            return self._evaluate_missing_data(reading)
        elif self.condition_type == 'compound':
            return self._evaluate_compound(reading)

        return False

    def _evaluate_threshold(self, reading):
        """Evaluate threshold condition"""
        value = reading.get(self.metric_name)
        if value is None:
            return False

        if self.operator == '>':
            return value > self.threshold_value
        elif self.operator == '>=':
            return value >= self.threshold_value
        elif self.operator == '<':
            return value < self.threshold_value
        elif self.operator == '<=':
            return value <= self.threshold_value
        elif self.operator == '==':
            return value == self.threshold_value
        elif self.operator == '!=':
            return value != self.threshold_value
        elif self.operator == 'between':
            return self.threshold_min <= value <= self.threshold_max
        elif self.operator == 'outside':
            return value < self.threshold_min or value > self.threshold_max

        return False

    def _evaluate_rate_of_change(self, reading):
        """Evaluate rate of change condition"""
        # Get historical readings
        device_uid = reading.get('device_uid')
        metric_name = self.metric_name

        self.env.cr.execute("""
            SELECT metric_value
            FROM iot.sensor_data
            WHERE device_uid = %s
              AND metric_name = %s
              AND time >= NOW() - INTERVAL '%s minutes'
            ORDER BY time DESC
            LIMIT 2
        """, (device_uid, metric_name, self.change_window_minutes))

        rows = self.env.cr.fetchall()
        if len(rows) < 2:
            return False

        current_value = rows[0][0]
        previous_value = rows[1][0]
        change = current_value - previous_value

        if self.change_direction == 'increase':
            return change >= self.change_threshold
        elif self.change_direction == 'decrease':
            return change <= -self.change_threshold
        else:  # any
            return abs(change) >= self.change_threshold

    def _evaluate_missing_data(self, reading):
        """Evaluate missing data condition"""
        device_uid = reading.get('device_uid')

        self.env.cr.execute("""
            SELECT MAX(time) as last_seen
            FROM iot.sensor_data
            WHERE device_uid = %s
        """, (device_uid,))

        row = self.env.cr.fetchone()
        if not row or not row[0]:
            return True

        from datetime import datetime, timedelta
        last_seen = row[0]
        timeout = timedelta(minutes=self.missing_timeout_minutes)

        return datetime.now() - last_seen > timeout

    def _evaluate_compound(self, reading):
        """Evaluate compound condition"""
        results = []
        for sub_condition in self.sub_condition_ids:
            results.append(sub_condition.evaluate(reading))

        if self.condition_logic == 'and':
            return all(results)
        else:  # or
            return any(results)

    def trigger_alert(self, reading, context=None):
        """Create alert instance and dispatch notifications"""
        self.ensure_one()

        # Check cooldown
        if not self._check_cooldown():
            return None

        # Check active hours/days
        if not self._is_active_time():
            return None

        # Create alert instance
        alert = self.env['iot.alert_instance'].create({
            'config_id': self.id,
            'device_uid': reading.get('device_uid'),
            'farm_id': reading.get('farm_id'),
            'metric_name': self.metric_name,
            'metric_value': reading.get(self.metric_name),
            'threshold_value': self.threshold_value,
            'reading_data': json.dumps(reading),
        })

        # Update last triggered
        self.last_triggered = fields.Datetime.now()

        # Dispatch notifications
        self.env['iot.notification.dispatcher'].dispatch_alert(alert)

        return alert

    def _check_cooldown(self):
        """Check if alert is in cooldown period"""
        if not self.last_triggered:
            return True

        from datetime import timedelta
        cooldown = timedelta(minutes=self.cooldown_minutes)
        return fields.Datetime.now() - self.last_triggered >= cooldown

    def _is_active_time(self):
        """Check if current time is within active hours/days"""
        from datetime import datetime
        now = datetime.now()

        # Check active days
        if self.active_days:
            active_days = json.loads(self.active_days)
            if now.isoweekday() not in active_days:
                return False

        # Check active hours
        if self.active_hours_start and self.active_hours_end:
            current_hour = now.hour + now.minute / 60
            if not (self.active_hours_start <= current_hour <= self.active_hours_end):
                return False

        return True


class IoTAlertSubCondition(models.Model):
    _name = 'iot.alert_sub_condition'
    _description = 'Alert Sub-Condition'

    parent_config_id = fields.Many2one(
        'iot.alert_config',
        string='Parent Configuration',
        required=True,
        ondelete='cascade'
    )
    metric_name = fields.Char(string='Metric Name', required=True)
    operator = fields.Selection([
        ('>', 'Greater Than'),
        ('>=', 'Greater Than or Equal'),
        ('<', 'Less Than'),
        ('<=', 'Less Than or Equal'),
        ('==', 'Equal To'),
        ('!=', 'Not Equal To'),
    ], string='Operator', required=True)
    threshold_value = fields.Float(string='Threshold Value', required=True)

    def evaluate(self, reading):
        """Evaluate this sub-condition"""
        value = reading.get(self.metric_name)
        if value is None:
            return False

        if self.operator == '>':
            return value > self.threshold_value
        elif self.operator == '>=':
            return value >= self.threshold_value
        elif self.operator == '<':
            return value < self.threshold_value
        elif self.operator == '<=':
            return value <= self.threshold_value
        elif self.operator == '==':
            return value == self.threshold_value
        elif self.operator == '!=':
            return value != self.threshold_value

        return False
```

### 5.2 Notification Dispatcher

```python
# smart_dairy_iot/services/notification_dispatcher.py

from odoo import models, api, fields
import json
import logging
from datetime import datetime

_logger = logging.getLogger(__name__)


class NotificationDispatcher(models.AbstractModel):
    _name = 'iot.notification.dispatcher'
    _description = 'Multi-channel Notification Dispatcher'

    @api.model
    def dispatch_alert(self, alert):
        """Dispatch alert through all configured channels"""
        config = alert.config_id

        # Build notification payload
        payload = self._build_payload(alert)

        # Dispatch to each enabled channel
        results = {}

        if config.enable_websocket:
            results['websocket'] = self._dispatch_websocket(alert, payload)

        if config.enable_push:
            results['push'] = self._dispatch_push(alert, payload)

        if config.enable_email:
            results['email'] = self._dispatch_email(alert, payload)

        if config.enable_sms:
            results['sms'] = self._dispatch_sms(alert, payload)

        # Log dispatch results
        self._log_dispatch(alert, results)

        # Start escalation if enabled
        if config.enable_escalation:
            self._start_escalation(alert)

        return results

    def _build_payload(self, alert):
        """Build notification payload from alert"""
        config = alert.config_id

        # Build title from template
        title = config.alert_title_template.format(
            priority=dict(config._fields['priority'].selection).get(config.priority),
            alert_name=config.name,
        )

        # Build message from template
        message = config.alert_message_template.format(
            metric_name=alert.metric_name or '',
            value=alert.metric_value or '',
            unit=alert.metric_unit or '',
            threshold=config.threshold_value or '',
            device_uid=alert.device_uid or '',
        )

        return {
            'alert_id': alert.id,
            'title': title,
            'message': message,
            'priority': config.priority,
            'device_uid': alert.device_uid,
            'farm_id': alert.farm_id,
            'metric_name': alert.metric_name,
            'metric_value': alert.metric_value,
            'threshold_value': config.threshold_value,
            'timestamp': alert.create_date.isoformat(),
            'action_url': f'/web#id={alert.id}&model=iot.alert_instance&view_type=form',
        }

    def _get_recipients(self, config):
        """Get notification recipients based on configuration"""
        recipients = self.env['res.users']

        # Direct recipients
        if config.recipient_user_ids:
            recipients |= config.recipient_user_ids

        # Role-based recipients
        if config.recipient_role:
            if config.recipient_role == 'farm_manager':
                if config.farm_id and config.farm_id.user_id:
                    recipients |= config.farm_id.user_id
            elif config.recipient_role == 'veterinarian':
                vets = self.env['res.users'].search([
                    ('is_veterinarian', '=', True)
                ])
                recipients |= vets
            elif config.recipient_role == 'technician':
                techs = self.env['res.users'].search([
                    ('is_technician', '=', True)
                ])
                recipients |= techs
            elif config.recipient_role == 'all':
                if config.farm_id:
                    farm_users = self.env['res.users'].search([
                        ('farm_ids', 'in', config.farm_id.id)
                    ])
                    recipients |= farm_users

        return recipients

    def _dispatch_websocket(self, alert, payload):
        """Send alert via WebSocket"""
        try:
            ws_service = self.env['iot.websocket.service']
            recipients = self._get_recipients(alert.config_id)

            for user in recipients:
                ws_service.send_to_user(user.id, 'alert', payload)

            return {'success': True, 'count': len(recipients)}
        except Exception as e:
            _logger.error(f"WebSocket dispatch error: {e}")
            return {'success': False, 'error': str(e)}

    def _dispatch_push(self, alert, payload):
        """Send push notification via FCM"""
        try:
            fcm_service = self.env['iot.fcm.service']
            recipients = self._get_recipients(alert.config_id)

            success_count = 0
            for user in recipients:
                result = fcm_service.send_notification(
                    user_id=user.id,
                    title=payload['title'],
                    body=payload['message'],
                    data={
                        'alert_id': str(alert.id),
                        'type': 'iot_alert',
                        'priority': alert.config_id.priority,
                        'click_action': 'OPEN_ALERT',
                    },
                    priority='high' if alert.config_id.priority in ('1', '2') else 'normal'
                )
                if result:
                    success_count += 1

            return {'success': True, 'count': success_count}
        except Exception as e:
            _logger.error(f"FCM dispatch error: {e}")
            return {'success': False, 'error': str(e)}

    def _dispatch_email(self, alert, payload):
        """Send email notification"""
        try:
            template = self.env.ref('smart_dairy_iot.email_template_iot_alert')
            recipients = self._get_recipients(alert.config_id)

            success_count = 0
            for user in recipients:
                if user.email:
                    template.send_mail(
                        alert.id,
                        email_values={'email_to': user.email},
                        force_send=True
                    )
                    success_count += 1

            return {'success': True, 'count': success_count}
        except Exception as e:
            _logger.error(f"Email dispatch error: {e}")
            return {'success': False, 'error': str(e)}

    def _dispatch_sms(self, alert, payload):
        """Send SMS notification"""
        try:
            sms_service = self.env['iot.sms.service']
            recipients = self._get_recipients(alert.config_id)

            message = f"{payload['title']}\n{payload['message']}"

            success_count = 0
            for user in recipients:
                if user.mobile:
                    result = sms_service.send_sms(user.mobile, message)
                    if result:
                        success_count += 1

            return {'success': True, 'count': success_count}
        except Exception as e:
            _logger.error(f"SMS dispatch error: {e}")
            return {'success': False, 'error': str(e)}

    def _log_dispatch(self, alert, results):
        """Log notification dispatch results"""
        self.env['iot.notification_log'].create({
            'alert_id': alert.id,
            'dispatch_time': fields.Datetime.now(),
            'results': json.dumps(results),
            'websocket_success': results.get('websocket', {}).get('success', False),
            'push_success': results.get('push', {}).get('success', False),
            'email_success': results.get('email', {}).get('success', False),
            'sms_success': results.get('sms', {}).get('success', False),
        })

    def _start_escalation(self, alert):
        """Start escalation workflow for alert"""
        self.env['iot.escalation.service'].start_escalation(alert)
```

### 5.3 FCM Service

```python
# smart_dairy_iot/services/fcm_service.py

from odoo import models, api, fields
import firebase_admin
from firebase_admin import credentials, messaging
import logging

_logger = logging.getLogger(__name__)


class FCMService(models.AbstractModel):
    _name = 'iot.fcm.service'
    _description = 'Firebase Cloud Messaging Service'

    _firebase_app = None

    @api.model
    def _get_firebase_app(self):
        """Get or initialize Firebase app"""
        if self._firebase_app is None:
            # Get credentials from system parameters
            cred_json = self.env['ir.config_parameter'].sudo().get_param(
                'smart_dairy.firebase_credentials'
            )
            if cred_json:
                import json
                cred_dict = json.loads(cred_json)
                cred = credentials.Certificate(cred_dict)
                self._firebase_app = firebase_admin.initialize_app(cred)
            else:
                _logger.warning("Firebase credentials not configured")
                return None

        return self._firebase_app

    @api.model
    def send_notification(self, user_id, title, body, data=None, priority='normal'):
        """Send push notification to a user"""
        if not self._get_firebase_app():
            return False

        # Get user's FCM tokens
        tokens = self.env['iot.fcm_token'].search([
            ('user_id', '=', user_id),
            ('active', '=', True),
        ])

        if not tokens:
            _logger.info(f"No FCM tokens for user {user_id}")
            return False

        # Build message
        android_config = messaging.AndroidConfig(
            priority='high' if priority == 'high' else 'normal',
            notification=messaging.AndroidNotification(
                title=title,
                body=body,
                icon='notification_icon',
                color='#FF5722',
                sound='alert_sound',
                channel_id='iot_alerts',
            )
        )

        apns_config = messaging.APNSConfig(
            payload=messaging.APNSPayload(
                aps=messaging.Aps(
                    alert=messaging.ApsAlert(
                        title=title,
                        body=body,
                    ),
                    sound='alert.aiff',
                    badge=1,
                )
            )
        )

        success_count = 0
        for token in tokens:
            try:
                message = messaging.Message(
                    notification=messaging.Notification(
                        title=title,
                        body=body,
                    ),
                    data=data or {},
                    token=token.token,
                    android=android_config,
                    apns=apns_config,
                )

                response = messaging.send(message)
                _logger.info(f"FCM message sent: {response}")
                success_count += 1

            except messaging.UnregisteredError:
                # Token is no longer valid
                token.active = False
                _logger.info(f"Deactivated invalid FCM token: {token.token[:20]}...")

            except Exception as e:
                _logger.error(f"FCM send error: {e}")

        return success_count > 0

    @api.model
    def send_batch_notification(self, user_ids, title, body, data=None):
        """Send notification to multiple users"""
        if not self._get_firebase_app():
            return 0

        # Get all tokens
        tokens = self.env['iot.fcm_token'].search([
            ('user_id', 'in', user_ids),
            ('active', '=', True),
        ])

        if not tokens:
            return 0

        # Build multicast message
        message = messaging.MulticastMessage(
            notification=messaging.Notification(
                title=title,
                body=body,
            ),
            data=data or {},
            tokens=[t.token for t in tokens],
        )

        try:
            response = messaging.send_multicast(message)
            _logger.info(
                f"FCM batch sent: {response.success_count} success, "
                f"{response.failure_count} failures"
            )

            # Handle failed tokens
            for idx, send_response in enumerate(response.responses):
                if not send_response.success:
                    if isinstance(send_response.exception, messaging.UnregisteredError):
                        tokens[idx].active = False

            return response.success_count

        except Exception as e:
            _logger.error(f"FCM batch send error: {e}")
            return 0


class FCMToken(models.Model):
    _name = 'iot.fcm_token'
    _description = 'FCM Device Token'

    user_id = fields.Many2one(
        'res.users',
        string='User',
        required=True,
        ondelete='cascade'
    )
    token = fields.Char(string='FCM Token', required=True)
    device_type = fields.Selection([
        ('android', 'Android'),
        ('ios', 'iOS'),
        ('web', 'Web'),
    ], string='Device Type')
    device_name = fields.Char(string='Device Name')
    active = fields.Boolean(default=True)
    last_used = fields.Datetime(string='Last Used')

    _sql_constraints = [
        ('token_unique', 'unique(token)', 'FCM token must be unique'),
    ]
```

### 5.4 WebSocket Service

```python
# smart_dairy_iot/services/websocket_service.py

from odoo import models, api, fields
import asyncio
import json
import logging
from collections import defaultdict
import redis

_logger = logging.getLogger(__name__)


class WebSocketService(models.AbstractModel):
    _name = 'iot.websocket.service'
    _description = 'WebSocket Notification Service'

    @api.model
    def _get_redis_client(self):
        """Get Redis client for pub/sub"""
        redis_url = self.env['ir.config_parameter'].sudo().get_param(
            'smart_dairy.redis_url',
            'redis://localhost:6379/0'
        )
        return redis.from_url(redis_url)

    @api.model
    def send_to_user(self, user_id, event_type, data):
        """Send message to specific user via Redis pub/sub"""
        try:
            client = self._get_redis_client()
            channel = f"user:{user_id}:notifications"

            message = json.dumps({
                'type': event_type,
                'data': data,
                'timestamp': fields.Datetime.now().isoformat(),
            })

            client.publish(channel, message)
            _logger.debug(f"Published to {channel}: {event_type}")
            return True

        except Exception as e:
            _logger.error(f"WebSocket send error: {e}")
            return False

    @api.model
    def send_to_farm(self, farm_id, event_type, data):
        """Send message to all users in a farm"""
        try:
            client = self._get_redis_client()
            channel = f"farm:{farm_id}:notifications"

            message = json.dumps({
                'type': event_type,
                'data': data,
                'timestamp': fields.Datetime.now().isoformat(),
            })

            client.publish(channel, message)
            return True

        except Exception as e:
            _logger.error(f"WebSocket farm send error: {e}")
            return False

    @api.model
    def broadcast_alert(self, alert):
        """Broadcast alert to all relevant users"""
        config = alert.config_id
        farm_id = alert.farm_id

        data = {
            'alert_id': alert.id,
            'alert_name': config.name,
            'priority': config.priority,
            'device_uid': alert.device_uid,
            'metric_name': alert.metric_name,
            'metric_value': alert.metric_value,
            'message': alert.message,
        }

        # Send to farm channel
        if farm_id:
            self.send_to_farm(farm_id, 'iot_alert', data)

        # Send to specific users
        recipients = self.env['iot.notification.dispatcher']._get_recipients(config)
        for user in recipients:
            self.send_to_user(user.id, 'iot_alert', data)
```

### 5.5 WebSocket Server (FastAPI)

```python
# iot_notification_server/main.py

from fastapi import FastAPI, WebSocket, WebSocketDisconnect, Depends, HTTPException
from fastapi.security import HTTPBearer, HTTPAuthorizationCredentials
import asyncio
import json
import redis.asyncio as redis
from typing import Dict, Set
import logging
import jwt

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = FastAPI(title="Smart Dairy WebSocket Server")
security = HTTPBearer()

# Connection management
class ConnectionManager:
    def __init__(self):
        self.active_connections: Dict[int, Set[WebSocket]] = {}
        self.farm_connections: Dict[int, Set[WebSocket]] = {}

    async def connect(self, websocket: WebSocket, user_id: int, farm_ids: list):
        await websocket.accept()

        # Track user connections
        if user_id not in self.active_connections:
            self.active_connections[user_id] = set()
        self.active_connections[user_id].add(websocket)

        # Track farm connections
        for farm_id in farm_ids:
            if farm_id not in self.farm_connections:
                self.farm_connections[farm_id] = set()
            self.farm_connections[farm_id].add(websocket)

        logger.info(f"User {user_id} connected. Total connections: {len(self.active_connections)}")

    def disconnect(self, websocket: WebSocket, user_id: int, farm_ids: list):
        if user_id in self.active_connections:
            self.active_connections[user_id].discard(websocket)
            if not self.active_connections[user_id]:
                del self.active_connections[user_id]

        for farm_id in farm_ids:
            if farm_id in self.farm_connections:
                self.farm_connections[farm_id].discard(websocket)

        logger.info(f"User {user_id} disconnected")

    async def send_to_user(self, user_id: int, message: dict):
        if user_id in self.active_connections:
            disconnected = []
            for websocket in self.active_connections[user_id]:
                try:
                    await websocket.send_json(message)
                except Exception as e:
                    logger.error(f"Send error: {e}")
                    disconnected.append(websocket)

            for ws in disconnected:
                self.active_connections[user_id].discard(ws)

    async def send_to_farm(self, farm_id: int, message: dict):
        if farm_id in self.farm_connections:
            disconnected = []
            for websocket in self.farm_connections[farm_id]:
                try:
                    await websocket.send_json(message)
                except Exception as e:
                    disconnected.append(websocket)

            for ws in disconnected:
                self.farm_connections[farm_id].discard(ws)

manager = ConnectionManager()

# Redis subscriber
async def redis_subscriber():
    """Subscribe to Redis channels and forward to WebSocket clients"""
    client = redis.from_url("redis://localhost:6379/0")
    pubsub = client.pubsub()

    # Subscribe to all user and farm channels
    await pubsub.psubscribe("user:*:notifications", "farm:*:notifications")

    async for message in pubsub.listen():
        if message['type'] == 'pmessage':
            channel = message['channel'].decode()
            data = json.loads(message['data'])

            if channel.startswith('user:'):
                user_id = int(channel.split(':')[1])
                await manager.send_to_user(user_id, data)
            elif channel.startswith('farm:'):
                farm_id = int(channel.split(':')[1])
                await manager.send_to_farm(farm_id, data)

@app.on_event("startup")
async def startup():
    asyncio.create_task(redis_subscriber())

def verify_token(credentials: HTTPAuthorizationCredentials = Depends(security)):
    """Verify JWT token"""
    try:
        payload = jwt.decode(
            credentials.credentials,
            "your-secret-key",  # Use env variable in production
            algorithms=["HS256"]
        )
        return payload
    except jwt.ExpiredSignatureError:
        raise HTTPException(status_code=401, detail="Token expired")
    except jwt.InvalidTokenError:
        raise HTTPException(status_code=401, detail="Invalid token")

@app.websocket("/ws/notifications")
async def websocket_endpoint(
    websocket: WebSocket,
    token: str = None
):
    """WebSocket endpoint for real-time notifications"""
    # Verify token from query parameter
    if not token:
        await websocket.close(code=4001)
        return

    try:
        payload = jwt.decode(token, "your-secret-key", algorithms=["HS256"])
        user_id = payload['user_id']
        farm_ids = payload.get('farm_ids', [])
    except jwt.InvalidTokenError:
        await websocket.close(code=4001)
        return

    await manager.connect(websocket, user_id, farm_ids)

    try:
        while True:
            # Keep connection alive with ping/pong
            data = await websocket.receive_text()

            if data == "ping":
                await websocket.send_text("pong")
            else:
                # Handle other client messages
                message = json.loads(data)
                if message.get('type') == 'acknowledge':
                    # Handle alert acknowledgment
                    pass

    except WebSocketDisconnect:
        manager.disconnect(websocket, user_id, farm_ids)

@app.get("/health")
async def health():
    return {"status": "healthy", "connections": len(manager.active_connections)}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8002)
```

---

## 6. Testing & Validation

### 6.1 Unit Tests

```python
# smart_dairy_iot/tests/test_alert_system.py

from odoo.tests.common import TransactionCase
from unittest.mock import patch, MagicMock

class TestAlertConfig(TransactionCase):

    def setUp(self):
        super().setUp()
        self.farm = self.env['res.partner'].create({
            'name': 'Test Farm',
            'is_farm': True,
        })
        self.alert_config = self.env['iot.alert_config'].create({
            'name': 'High Temperature Alert',
            'priority': '2',
            'farm_id': self.farm.id,
            'condition_type': 'threshold',
            'metric_name': 'temperature',
            'operator': '>',
            'threshold_value': 8.0,
            'cooldown_minutes': 30,
        })

    def test_threshold_evaluation_greater_than(self):
        """Test greater than threshold evaluation"""
        reading = {'temperature': 10.0, 'device_uid': 'TEST001'}
        result = self.alert_config.evaluate_condition(reading)
        self.assertTrue(result)

        reading = {'temperature': 5.0, 'device_uid': 'TEST001'}
        result = self.alert_config.evaluate_condition(reading)
        self.assertFalse(result)

    def test_threshold_evaluation_between(self):
        """Test between range evaluation"""
        self.alert_config.write({
            'operator': 'between',
            'threshold_min': 2.0,
            'threshold_max': 6.0,
        })

        reading = {'temperature': 4.0, 'device_uid': 'TEST001'}
        result = self.alert_config.evaluate_condition(reading)
        self.assertTrue(result)

        reading = {'temperature': 8.0, 'device_uid': 'TEST001'}
        result = self.alert_config.evaluate_condition(reading)
        self.assertFalse(result)

    def test_cooldown_check(self):
        """Test cooldown period enforcement"""
        from datetime import timedelta

        # No previous trigger - should pass
        self.assertTrue(self.alert_config._check_cooldown())

        # Trigger alert
        self.alert_config.last_triggered = fields.Datetime.now()

        # Should fail during cooldown
        self.assertFalse(self.alert_config._check_cooldown())

        # After cooldown - should pass
        self.alert_config.last_triggered = (
            fields.Datetime.now() - timedelta(minutes=31)
        )
        self.assertTrue(self.alert_config._check_cooldown())


class TestNotificationDispatcher(TransactionCase):

    def setUp(self):
        super().setUp()
        self.farm = self.env['res.partner'].create({
            'name': 'Test Farm',
            'is_farm': True,
        })
        self.user = self.env['res.users'].create({
            'name': 'Test User',
            'login': 'testuser',
            'email': 'test@example.com',
        })
        self.alert_config = self.env['iot.alert_config'].create({
            'name': 'Test Alert',
            'priority': '2',
            'farm_id': self.farm.id,
            'condition_type': 'threshold',
            'metric_name': 'temperature',
            'operator': '>',
            'threshold_value': 8.0,
            'enable_push': True,
            'enable_email': True,
            'enable_sms': False,
            'recipient_user_ids': [(4, self.user.id)],
        })
        self.alert = self.env['iot.alert_instance'].create({
            'config_id': self.alert_config.id,
            'device_uid': 'TEST001',
            'farm_id': self.farm.id,
            'metric_name': 'temperature',
            'metric_value': 10.0,
        })

    @patch('smart_dairy_iot.services.fcm_service.FCMService.send_notification')
    def test_dispatch_push_notification(self, mock_fcm):
        """Test push notification dispatch"""
        mock_fcm.return_value = True

        dispatcher = self.env['iot.notification.dispatcher']
        results = dispatcher.dispatch_alert(self.alert)

        self.assertTrue(results.get('push', {}).get('success'))

    def test_get_recipients(self):
        """Test recipient resolution"""
        dispatcher = self.env['iot.notification.dispatcher']
        recipients = dispatcher._get_recipients(self.alert_config)

        self.assertIn(self.user, recipients)
```

### 6.2 Performance Targets

| Test Case | Target | Method |
|-----------|--------|--------|
| Alert evaluation | <10ms | Timing |
| WebSocket delivery | <100ms | End-to-end |
| Push notification | <5 seconds | FCM timing |
| SMS delivery | <10 seconds | Carrier timing |
| Concurrent alerts | 1000/second | Load test |

---

## 7. Milestone Sign-off Checklist

| # | Criteria | Owner | Status |
|---|----------|-------|--------|
| 1 | Alert rule engine complete | Dev 1 | ☐ |
| 2 | Alert configuration model working | Dev 1 | ☐ |
| 3 | Multi-channel dispatcher functional | Dev 2 | ☐ |
| 4 | FCM push notifications working | Dev 2 | ☐ |
| 5 | SMS alerts functional | Dev 2 | ☐ |
| 6 | Email alerts with templates | Dev 3 | ☐ |
| 7 | WebSocket real-time push working | Dev 2 | ☐ |
| 8 | Escalation workflow implemented | Dev 1 | ☐ |
| 9 | Alert dashboard deployed | Dev 3 | ☐ |
| 10 | Mobile notifications working | Dev 3 | ☐ |
| 11 | Unit tests passing (>80%) | All | ☐ |
| 12 | Demo completed successfully | All | ☐ |

---

**Document Revision History**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-04 | Development Team | Initial creation |

---

*This document is part of the Smart Dairy Digital Smart Portal + ERP System implementation roadmap.*
