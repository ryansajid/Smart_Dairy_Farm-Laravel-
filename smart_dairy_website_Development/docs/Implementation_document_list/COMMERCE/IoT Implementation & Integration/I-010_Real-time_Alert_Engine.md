# SMART DAIRY LTD.
## REAL-TIME ALERT ENGINE SPECIFICATION
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | I-010 |
| **Version** | 1.0 |
| **Date** | August 22, 2026 |
| **Author** | IoT Engineer |
| **Owner** | IoT Engineer |
| **Reviewer** | Farm Manager |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Alert Engine Architecture](#2-alert-engine-architecture)
3. [Alert Rule Engine](#3-alert-rule-engine)
4. [Notification Channels](#4-notification-channels)
5. [Alert Processing Pipeline](#5-alert-processing-pipeline)
6. [Alert Types & Thresholds](#6-alert-types--thresholds)
7. [Escalation Procedures](#7-escalation-procedures)
8. [Analytics & Reporting](#8-analytics--reporting)
9. [Implementation Guide](#9-implementation-guide)
10. [Appendices](#10-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

The Real-time Alert Engine is a critical component of the Smart Dairy IoT ecosystem that continuously monitors sensor data streams, detects anomalies, and triggers timely notifications to farm management, veterinary staff, and operational teams. This document specifies the architecture, configuration, and operational procedures for the alert engine.

### 1.2 Alert Categories

| Category | Description | Response Time | Recipients |
|----------|-------------|---------------|------------|
| **Critical** | Life-threatening or severe operational issues | Immediate | Farm Manager, Vet, SMS |
| **Warning** | Conditions requiring attention within hours | 15 minutes | Farm Supervisor, App |
| **Info** | Notifications for awareness | 1 hour | Relevant staff, Email |
| **System** | Infrastructure and device health | 30 minutes | IT Admin |

### 1.3 Business Value

| Metric | Target | Impact |
|--------|--------|--------|
| **Mastitis Detection** | < 24 hours | 30% reduction in treatment costs |
| **Heat Detection** | > 85% accuracy | 15% improvement in conception rates |
| **Environmental Response** | < 5 minutes | Reduced animal stress, better yields |
| **Equipment Downtime** | < 2 hours | 20% reduction in production losses |

---

## 2. ALERT ENGINE ARCHITECTURE

### 2.1 High-Level Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      REAL-TIME ALERT ENGINE ARCHITECTURE                     │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                      DATA SOURCES                                    │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │ MQTT Broker  │  │   Timescale  │  │    ERP       │              │   │
│  │  │   (EMQX)     │  │     DB       │  │   System     │              │   │
│  │  │              │  │              │  │              │              │   │
│  │  │ Real-time    │  │ Historical   │  │ Business     │              │   │
│  │  │ telemetry    │  │ data         │  │ rules        │              │   │
│  │  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘              │   │
│  │         │                 │                 │                       │   │
│  └─────────┼─────────────────┼─────────────────┼───────────────────────┘   │
│            │                 │                 │                            │
│            └─────────────────┴─────────────────┘                            │
│                              │                                              │
│  ┌───────────────────────────▼───────────────────────────────────────────┐  │
│  │                      RULE ENGINE (Drools/Custom)                       │  │
│  │                                                                        │  │
│  │  ┌─────────────────────────────────────────────────────────────────┐  │  │
│  │  │  Rule Evaluation Pipeline                                       │  │  │
│  │  │                                                                 │  │  │
│  │  │  1. Data Ingestion → 2. Condition Check → 3. Action Trigger    │  │  │
│  │  │                                                                 │  │  │
│  │  │  Rules:                                                         │  │  │
│  │  │  • Temperature thresholds                                       │  │  │
│  │  │  • Activity patterns                                            │  │  │
│  │  │  • Production anomalies                                         │  │  │
│  │  │  • Health indicators                                            │  │  │
│  │  │  • Equipment status                                             │  │  │
│  │  └─────────────────────────────────────────────────────────────────┘  │  │
│  └─────────────────────────────────┬─────────────────────────────────────┘  │
│                                    │                                         │
│  ┌─────────────────────────────────▼─────────────────────────────────────┐  │
│  │                      ALERT PROCESSOR                                   │  │
│  │                                                                        │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐                │  │
│  │  │   Deduplic   │  │  Enrichment  │  │  Throttling  │                │  │
│  │  │   ation      │  │              │  │   & Cooling  │                │  │
│  │  │              │  │ • Animal Info│  │              │                │  │
│  │  │ • Alert ID   │  │ • Location   │  │ • Rate Limit │                │  │
│  │  │ • Hash Check │  │ • Historical │  │ • Cooldown   │                │  │
│  │  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘                │  │
│  │         │                 │                 │                         │  │
│  │         └─────────────────┴─────────────────┘                         │  │
│  │                           │                                           │  │
│  │                  ┌────────▼────────┐                                  │  │
│  │                  │  Priority Queue │                                  │  │
│  │                  │  (Redis/Rabbit) │                                  │  │
│  │                  └────────┬────────┘                                  │  │
│  │                           │                                           │  │
│  └───────────────────────────┼───────────────────────────────────────────┘  │
│                              │                                              │
│  ┌───────────────────────────▼───────────────────────────────────────────┐  │
│  │                      NOTIFICATION DISPATCHER                           │  │
│  │                                                                        │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌───────────┐ │  │
│  │  │   Mobile     │  │     SMS      │  │    Email     │  │  Webhook  │ │  │
│  │  │  Push (FCM)  │  │   (Twilio)   │  │   (SMTP)     │  │  (REST)   │ │  │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └───────────┘ │  │
│  │                                                                        │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Technology Stack

| Component | Technology | Purpose |
|-----------|------------|---------|
| **Rule Engine** | Drools / Python Rule Engine | Business rule evaluation |
| **Stream Processing** | Apache Flink / Kafka Streams | Real-time data processing |
| **Message Queue** | Redis / RabbitMQ | Alert queuing and buffering |
| **Database** | TimescaleDB | Alert history and metrics |
| **Cache** | Redis | Cooldown tracking, deduplication |
| **Push Notifications** | Firebase Cloud Messaging | Mobile app alerts |
| **SMS Gateway** | Twilio / Local Provider | SMS notifications |
| **Email** | SendGrid / SMTP | Email notifications |

---

## 3. ALERT RULE ENGINE

### 3.1 Rule Definition Format

```yaml
# alert_rules.yaml
alert_rules:
  # Environmental Alerts
  - id: "ENV-001"
    name: "High Barn Temperature"
    category: "environmental"
    severity: "warning"
    enabled: true
    
    condition:
      type: "threshold"
      metric: "environmental.temperature"
      operator: ">"
      value: 30
      duration: "5m"  # Must exceed for 5 minutes
      
    scope:
      barns: ["barn_a", "barn_b", "barn_c"]
      zones: ["milking_parlor", "feeding_area"]
      
    notification:
      channels: ["app", "sms"]
      recipients:
        roles: ["farm_manager", "barn_supervisor"]
      template: "high_temperature"
      
    throttling:
      cooldown: "30m"
      max_alerts_per_hour: 2
      
    escalation:
      enabled: true
      levels:
        - after: "15m"
          severity: "critical"
          additional_recipients: ["operations_manager"]

  - id: "ENV-002"
    name: "Critical Ammonia Level"
    category: "environmental"
    severity: "critical"
    enabled: true
    
    condition:
      type: "threshold"
      metric: "environmental.ammonia_ppm"
      operator: ">"
      value: 25
      
    notification:
      channels: ["app", "sms", "email"]
      immediate: true
      
    actions:
      - type: "webhook"
        url: "https://api.smartdairybd.com/iot/v1/ventilation/boost"
        method: "POST"
        payload:
          barn_id: "{{barn_id}}"
          action: "increase_ventilation"

  # Animal Health Alerts
  - id: "HEALTH-001"
    name: "High Conductivity - Mastitis Risk"
    category: "animal_health"
    severity: "warning"
    enabled: true
    
    condition:
      type: "threshold"
      metric: "milk.conductivity_ms_cm"
      operator: ">"
      value: 6.5
      
    scope:
      animal_types: ["lactating_cow"]
      
    notification:
      channels: ["app"]
      recipients:
        roles: ["veterinarian", "farm_manager"]
      template: "mastitis_risk"
      include_data:
        - "animal_id"
        - "animal_name"
        - "last_milk_production"
        - "conductivity_history"

  - id: "HEALTH-002"
    name: "Heat Detection"
    category: "animal_health"
    severity: "info"
    enabled: true
    
    condition:
      type: "composite"
      operator: "AND"
      conditions:
        - metric: "activity.level"
          operator: ">"
          value: 80
        - metric: "rumination.minutes"
          operator: "<"
          value: 300
          
    notification:
      channels: ["app"]
      recipients:
        roles: ["breeding_manager"]
      template: "heat_detection"

  - id: "HEALTH-003"
    name: "Abnormal Body Temperature"
    category: "animal_health"
    severity: "critical"
    enabled: true
    
    condition:
      type: "range"
      metric: "animal.body_temperature"
      min: 38.0
      max: 39.5
      outside_range: true
      
    notification:
      channels: ["app", "sms"]
      recipients:
        roles: ["veterinarian"]
      template: "abnormal_temperature"

  # Production Alerts
  - id: "PROD-001"
    name: "Low Milk Production"
    category: "production"
    severity: "warning"
    enabled: true
    
    condition:
      type: "deviation"
      metric: "milk.volume_liters"
      comparison: "animal_average"
      operator: "<"
      percentage: 50
      min_samples: 5
      
    notification:
      channels: ["app"]
      recipients:
        roles: ["farm_manager"]
      template: "low_production"

  - id: "PROD-002"
    name: "Milking Session Complete"
    category: "production"
    severity: "info"
    enabled: true
    
    condition:
      type: "event"
      event: "milking.session.end"
      
    notification:
      channels: ["app"]
      recipients:
        roles: ["farm_manager"]
      template: "session_summary"
      aggregate: true
      aggregation_window: "1h"

  # Equipment Alerts
  - id: "EQUIP-001"
    name: "Device Offline"
    category: "system"
    severity: "warning"
    enabled: true
    
    condition:
      type: "heartbeat"
      metric: "device.last_heartbeat"
      timeout: "5m"
      
    notification:
      channels: ["app", "email"]
      recipients:
        roles: ["it_admin", "farm_manager"]
      template: "device_offline"

  - id: "EQUIP-002"
    name: "Low Battery Warning"
    category: "system"
    severity: "info"
    enabled: true
    
    condition:
      type: "threshold"
      metric: "device.battery_level"
      operator: "<"
      value: 20
      
    notification:
      channels: ["app"]
      recipients:
        roles: ["it_admin"]
      template: "low_battery"
```

### 3.2 Rule Engine Implementation

```python
# alert_rule_engine.py
from typing import Dict, List, Optional, Any
from dataclasses import dataclass
from datetime import datetime, timedelta
import json
import asyncio
import asyncpg
import redis

@dataclass
class AlertCondition:
    type: str  # threshold, range, composite, deviation, event, heartbeat
    metric: Optional[str] = None
    operator: Optional[str] = None
    value: Optional[float] = None
    min_value: Optional[float] = None
    max_value: Optional[float] = None
    duration: Optional[str] = None
    conditions: Optional[List['AlertCondition']] = None

@dataclass
class AlertRule:
    id: str
    name: str
    category: str
    severity: str
    enabled: bool
    condition: AlertCondition
    scope: Dict[str, Any]
    notification: Dict[str, Any]
    throttling: Optional[Dict[str, Any]] = None
    escalation: Optional[Dict[str, Any]] = None

class RuleEngine:
    """Alert rule evaluation engine"""
    
    def __init__(self, db_pool: asyncpg.Pool, redis_client: redis.Redis):
        self.db_pool = db_pool
        self.redis = redis_client
        self.rules: List[AlertRule] = []
        self._load_rules()
    
    def _load_rules(self):
        """Load rules from configuration"""
        import yaml
        with open('alert_rules.yaml', 'r') as f:
            config = yaml.safe_load(f)
        
        for rule_dict in config['alert_rules']:
            self.rules.append(self._parse_rule(rule_dict))
    
    def _parse_rule(self, rule_dict: Dict) -> AlertRule:
        """Parse rule dictionary into AlertRule object"""
        condition = AlertCondition(**rule_dict['condition'])
        return AlertRule(
            id=rule_dict['id'],
            name=rule_dict['name'],
            category=rule_dict['category'],
            severity=rule_dict['severity'],
            enabled=rule_dict['enabled'],
            condition=condition,
            scope=rule_dict.get('scope', {}),
            notification=rule_dict['notification'],
            throttling=rule_dict.get('throttling'),
            escalation=rule_dict.get('escalation')
        )
    
    async def evaluate_data_point(self, data_point: Dict) -> Optional[List[Dict]]:
        """
        Evaluate a single data point against all rules.
        Returns list of triggered alerts.
        """
        triggered_alerts = []
        
        for rule in self.rules:
            if not rule.enabled:
                continue
            
            # Check scope
            if not self._matches_scope(data_point, rule.scope):
                continue
            
            # Evaluate condition
            if await self._evaluate_condition(data_point, rule.condition):
                # Check throttling
                if await self._check_throttling(rule, data_point):
                    alert = await self._create_alert(rule, data_point)
                    triggered_alerts.append(alert)
        
        return triggered_alerts if triggered_alerts else None
    
    def _matches_scope(self, data_point: Dict, scope: Dict) -> bool:
        """Check if data point matches rule scope"""
        # Check barn
        if 'barns' in scope:
            barn_id = data_point.get('location', {}).get('barn_id')
            if barn_id not in scope['barns']:
                return False
        
        # Check zones
        if 'zones' in scope:
            zone = data_point.get('location', {}).get('zone')
            if zone not in scope['zones']:
                return False
        
        # Check animal types
        if 'animal_types' in scope:
            # Would need to lookup animal type from RFID
            pass
        
        return True
    
    async def _evaluate_condition(self, data_point: Dict, condition: AlertCondition) -> bool:
        """Evaluate a single condition against data point"""
        
        if condition.type == 'threshold':
            return self._evaluate_threshold(data_point, condition)
        
        elif condition.type == 'range':
            return self._evaluate_range(data_point, condition)
        
        elif condition.type == 'composite':
            return await self._evaluate_composite(data_point, condition)
        
        elif condition.type == 'deviation':
            return await self._evaluate_deviation(data_point, condition)
        
        elif condition.type == 'event':
            return self._evaluate_event(data_point, condition)
        
        elif condition.type == 'heartbeat':
            return await self._evaluate_heartbeat(data_point, condition)
        
        return False
    
    def _evaluate_threshold(self, data_point: Dict, condition: AlertCondition) -> bool:
        """Evaluate threshold condition"""
        value = self._extract_metric(data_point, condition.metric)
        if value is None:
            return False
        
        threshold = condition.value
        
        if condition.operator == '>':
            return value > threshold
        elif condition.operator == '>=':
            return value >= threshold
        elif condition.operator == '<':
            return value < threshold
        elif condition.operator == '<=':
            return value <= threshold
        elif condition.operator == '==':
            return value == threshold
        elif condition.operator == '!=':
            return value != threshold
        
        return False
    
    def _evaluate_range(self, data_point: Dict, condition: AlertCondition) -> bool:
        """Evaluate range condition"""
        value = self._extract_metric(data_point, condition.metric)
        if value is None:
            return False
        
        in_range = condition.min_value <= value <= condition.max_value
        return not in_range if condition.outside_range else in_range
    
    async def _evaluate_composite(self, data_point: Dict, condition: AlertCondition) -> bool:
        """Evaluate composite (AND/OR) condition"""
        if not condition.conditions:
            return False
        
        results = []
        for sub_condition in condition.conditions:
            sub_cond = AlertCondition(**sub_condition)
            result = await self._evaluate_condition(data_point, sub_cond)
            results.append(result)
        
        if condition.operator == 'AND':
            return all(results)
        elif condition.operator == 'OR':
            return any(results)
        
        return False
    
    async def _evaluate_deviation(self, data_point: Dict, condition: AlertCondition) -> bool:
        """Evaluate deviation from average"""
        current_value = self._extract_metric(data_point, condition.metric)
        if current_value is None:
            return False
        
        # Get animal ID from data point
        animal_rfid = data_point.get('data', {}).get('animal_rfid')
        if not animal_rfid:
            return False
        
        # Query historical average
        async with self.db_pool.acquire() as conn:
            avg_value = await conn.fetchval("""
                SELECT AVG(quantity_liters) 
                FROM farm.milk_production mp
                JOIN farm.animal a ON mp.animal_id = a.id
                WHERE a.rfid_tag = $1
                AND mp.production_date >= CURRENT_DATE - INTERVAL '7 days'
            """, animal_rfid)
        
        if avg_value is None or avg_value == 0:
            return False
        
        deviation_percentage = abs(current_value - avg_value) / avg_value * 100
        
        if condition.operator == '<':
            return current_value < avg_value * (1 - condition.percentage / 100)
        elif condition.operator == '>':
            return current_value > avg_value * (1 + condition.percentage / 100)
        
        return False
    
    def _evaluate_event(self, data_point: Dict, condition: AlertCondition) -> bool:
        """Evaluate event-based condition"""
        event_type = data_point.get('event_type')
        return event_type == condition.event
    
    async def _evaluate_heartbeat(self, data_point: Dict, condition: AlertCondition) -> bool:
        """Evaluate heartbeat timeout condition"""
        device_id = data_point.get('device_id')
        last_heartbeat = data_point.get('metadata', {}).get('last_heartbeat')
        
        if not last_heartbeat:
            return True  # No heartbeat = alert
        
        timeout_seconds = self._parse_duration(condition.timeout)
        last_heartbeat_dt = datetime.fromisoformat(last_heartbeat.replace('Z', '+00:00'))
        
        return (datetime.utcnow() - last_heartbeat_dt).total_seconds() > timeout_seconds
    
    def _extract_metric(self, data_point: Dict, metric_path: str) -> Optional[float]:
        """Extract metric value from data point using dot notation"""
        parts = metric_path.split('.')
        value = data_point
        
        for part in parts:
            if isinstance(value, dict):
                value = value.get(part)
            else:
                return None
        
        return float(value) if value is not None else None
    
    async def _check_throttling(self, rule: AlertRule, data_point: Dict) -> bool:
        """Check if alert should be throttled"""
        if not rule.throttling:
            return True
        
        device_id = data_point.get('device_id', 'global')
        cache_key = f"alert:cooldown:{rule.id}:{device_id}"
        
        # Check cooldown
        cooldown_seconds = self._parse_duration(rule.throttling.get('cooldown', '0m'))
        if cooldown_seconds > 0:
            if self.redis.exists(cache_key):
                return False
            
            # Set cooldown
            self.redis.setex(cache_key, cooldown_seconds, '1')
        
        # Check rate limiting
        max_per_hour = rule.throttling.get('max_alerts_per_hour')
        if max_per_hour:
            hour_key = f"alert:count:{rule.id}:{datetime.utcnow().hour}"
            current_count = int(self.redis.get(hour_key) or 0)
            
            if current_count >= max_per_hour:
                return False
            
            self.redis.incr(hour_key)
            self.redis.expire(hour_key, 3600)
        
        return True
    
    async def _create_alert(self, rule: AlertRule, data_point: Dict) -> Dict:
        """Create alert object from triggered rule"""
        return {
            'alert_id': f"{rule.id}_{datetime.utcnow().timestamp()}",
            'rule_id': rule.id,
            'rule_name': rule.name,
            'category': rule.category,
            'severity': rule.severity,
            'device_id': data_point.get('device_id'),
            'location': data_point.get('location'),
            'data': data_point.get('data'),
            'timestamp': datetime.utcnow().isoformat(),
            'notification': rule.notification,
            'escalation': rule.escalation
        }
    
    def _parse_duration(self, duration_str: str) -> int:
        """Parse duration string to seconds"""
        if not duration_str:
            return 0
        
        unit = duration_str[-1]
        value = int(duration_str[:-1])
        
        multipliers = {
            's': 1,
            'm': 60,
            'h': 3600,
            'd': 86400
        }
        
        return value * multipliers.get(unit, 1)
```

---

## 4. NOTIFICATION CHANNELS

### 4.1 Channel Configuration

```python
# notification_dispatcher.py
from abc import ABC, abstractmethod
from typing import Dict, List, Any
import aiohttp
import asyncpg
from firebase_admin import messaging

class NotificationChannel(ABC):
    """Abstract base class for notification channels"""
    
    @abstractmethod
    async def send(self, alert: Dict, recipients: List[str]) -> bool:
        pass

class MobilePushChannel(NotificationChannel):
    """Firebase Cloud Messaging (FCM) notification channel"""
    
    def __init__(self, db_pool: asyncpg.Pool):
        self.db_pool = db_pool
    
    async def send(self, alert: Dict, recipients: List[str]) -> bool:
        """Send push notification to mobile devices"""
        
        # Get FCM tokens for recipients
        tokens = await self._get_fcm_tokens(recipients)
        
        if not tokens:
            return False
        
        # Build notification
        notification = messaging.Notification(
            title=self._format_title(alert),
            body=self._format_body(alert),
            image_url="https://smartdairybd.com/icons/alert-icon.png"
        )
        
        # Build data payload
        data = {
            'alert_id': alert['alert_id'],
            'rule_id': alert['rule_id'],
            'category': alert['category'],
            'severity': alert['severity'],
            'device_id': alert.get('device_id', ''),
            'timestamp': alert['timestamp'],
            'click_action': 'OPEN_ALERT_DETAIL'
        }
        
        # Send to multiple devices
        message = messaging.MulticastMessage(
            notification=notification,
            data=data,
            tokens=tokens,
            android=messaging.AndroidConfig(
                priority='high',
                notification=messaging.AndroidNotification(
                    channel_id='smart_dairy_alerts',
                    sound='alert_sound',
                    priority='high'
                )
            ),
            apns=messaging.APNSConfig(
                payload=messaging.APNSPayload(
                    aps=messaging.Aps(
                        sound='alert_sound.aiff',
                        badge=1,
                        category='ALERT_CATEGORY'
                    )
                )
            )
        )
        
        try:
            response = messaging.send_multicast(message)
            print(f"Successfully sent to {response.success_count} devices")
            return response.success_count > 0
        except Exception as e:
            print(f"Failed to send push notification: {e}")
            return False
    
    async def _get_fcm_tokens(self, recipients: List[str]) -> List[str]:
        """Get FCM tokens for user IDs"""
        async with self.db_pool.acquire() as conn:
            rows = await conn.fetch(
                "SELECT fcm_token FROM user_devices WHERE user_id = ANY($1) AND active = true",
                recipients
            )
            return [row['fcm_token'] for row in rows if row['fcm_token']]
    
    def _format_title(self, alert: Dict) -> str:
        """Format notification title"""
        severity_emoji = {
            'critical': '🚨',
            'warning': '⚠️',
            'info': 'ℹ️'
        }
        
        emoji = severity_emoji.get(alert['severity'], '⚠️')
        return f"{emoji} {alert['rule_name']}"
    
    def _format_body(self, alert: Dict) -> str:
        """Format notification body"""
        location = alert.get('location', {})
        barn_id = location.get('barn_id', 'Unknown')
        
        return f"Alert in {barn_id}. Tap to view details."

class SMSChannel(NotificationChannel):
    """SMS notification channel using Twilio"""
    
    def __init__(self, db_pool: asyncpg.Pool, twilio_client):
        self.db_pool = db_pool
        self.twilio = twilio_client
        self.from_number = "+1234567890"  # Twilio number
    
    async def send(self, alert: Dict, recipients: List[str]) -> bool:
        """Send SMS notification"""
        
        # Get phone numbers for recipients
        phone_numbers = await self._get_phone_numbers(recipients)
        
        if not phone_numbers:
            return False
        
        message_body = self._format_sms(alert)
        
        # Send SMS to each recipient
        success_count = 0
        for phone in phone_numbers:
            try:
                await self.twilio.messages.create(
                    body=message_body,
                    from_=self.from_number,
                    to=phone
                )
                success_count += 1
            except Exception as e:
                print(f"Failed to send SMS to {phone}: {e}")
        
        return success_count > 0
    
    async def _get_phone_numbers(self, recipients: List[str]) -> List[str]:
        """Get phone numbers for user IDs"""
        async with self.db_pool.acquire() as conn:
            rows = await conn.fetch(
                "SELECT phone FROM res_users WHERE id = ANY($1) AND phone IS NOT NULL",
                recipients
            )
            return [row['phone'] for row in rows if row['phone']]
    
    def _format_sms(self, alert: Dict) -> str:
        """Format SMS message"""
        return (
            f"Smart Dairy Alert: {alert['rule_name']}\n"
            f"Severity: {alert['severity'].upper()}\n"
            f"Location: {alert.get('location', {}).get('barn_id', 'Unknown')}\n"
            f"Time: {alert['timestamp'][:16]}\n"
            f"Check app for details."
        )

class EmailChannel(NotificationChannel):
    """Email notification channel"""
    
    def __init__(self, db_pool: asyncpg.Pool, smtp_client):
        self.db_pool = db_pool
        self.smtp = smtp_client
    
    async def send(self, alert: Dict, recipients: List[str]) -> bool:
        """Send email notification"""
        
        # Get email addresses
        emails = await self._get_emails(recipients)
        
        if not emails:
            return False
        
        # Build email content
        subject = f"[Smart Dairy Alert] {alert['rule_name']} - {alert['severity'].upper()}"
        html_body = self._format_email_html(alert)
        text_body = self._format_email_text(alert)
        
        # Send email
        try:
            await self.smtp.send(
                to=emails,
                subject=subject,
                text=text_body,
                html=html_body
            )
            return True
        except Exception as e:
            print(f"Failed to send email: {e}")
            return False
    
    async def _get_emails(self, recipients: List[str]) -> List[str]:
        """Get email addresses for user IDs"""
        async with self.db_pool.acquire() as conn:
            rows = await conn.fetch(
                "SELECT email FROM res_users WHERE id = ANY($1) AND email IS NOT NULL",
                recipients
            )
            return [row['email'] for row in rows if row['email']]
    
    def _format_email_html(self, alert: Dict) -> str:
        """Format HTML email"""
        severity_colors = {
            'critical': '#dc3545',
            'warning': '#ffc107',
            'info': '#17a2b8'
        }
        
        color = severity_colors.get(alert['severity'], '#6c757d')
        
        return f"""
        <html>
        <body style="font-family: Arial, sans-serif;">
            <div style="max-width: 600px; margin: 0 auto; border: 1px solid #ddd;">
                <div style="background-color: {color}; color: white; padding: 20px;">
                    <h2 style="margin: 0;">{alert['rule_name']}</h2>
                    <p style="margin: 10px 0 0 0; text-transform: uppercase;">
                        {alert['severity']} Alert
                    </p>
                </div>
                <div style="padding: 20px;">
                    <p><strong>Location:</strong> {alert.get('location', {}).get('barn_id', 'Unknown')}</p>
                    <p><strong>Time:</strong> {alert['timestamp']}</p>
                    <p><strong>Device:</strong> {alert.get('device_id', 'N/A')}</p>
                    <hr>
                    <p><strong>Alert Data:</strong></p>
                    <pre>{json.dumps(alert.get('data', {}), indent=2)}</pre>
                    <div style="margin-top: 30px; text-align: center;">
                        <a href="https://farm.smartdairybd.com/alerts/{alert['alert_id']}"
                           style="background-color: {color}; color: white; padding: 12px 24px;
                                  text-decoration: none; border-radius: 4px;">
                            View Alert Details
                        </a>
                    </div>
                </div>
            </div>
        </body>
        </html>
        """
    
    def _format_email_text(self, alert: Dict) -> str:
        """Format plain text email"""
        return f"""
Smart Dairy Alert: {alert['rule_name']}
Severity: {alert['severity'].upper()}
Location: {alert.get('location', {}).get('barn_id', 'Unknown')}
Time: {alert['timestamp']}
Device: {alert.get('device_id', 'N/A')}

Alert Data:
{json.dumps(alert.get('data', {}), indent=2)}

View details: https://farm.smartdairybd.com/alerts/{alert['alert_id']}
        """

class WebhookChannel(NotificationChannel):
    """Webhook notification channel for integrations"""
    
    def __init__(self, webhook_configs: Dict[str, str]):
        self.webhook_configs = webhook_configs
        self.session: Optional[aiohttp.ClientSession] = None
    
    async def __aenter__(self):
        self.session = aiohttp.ClientSession()
        return self
    
    async def __aexit__(self, exc_type, exc_val, exc_tb):
        if self.session:
            await self.session.close()
    
    async def send(self, alert: Dict, webhook_url: str) -> bool:
        """Send webhook notification"""
        
        if not self.session:
            self.session = aiohttp.ClientSession()
        
        payload = {
            'alert_id': alert['alert_id'],
            'rule_id': alert['rule_id'],
            'rule_name': alert['rule_name'],
            'category': alert['category'],
            'severity': alert['severity'],
            'timestamp': alert['timestamp'],
            'device_id': alert.get('device_id'),
            'location': alert.get('location'),
            'data': alert.get('data')
        }
        
        try:
            async with self.session.post(
                webhook_url,
                json=payload,
                headers={'Content-Type': 'application/json'},
                timeout=aiohttp.ClientTimeout(total=10)
            ) as response:
                return response.status == 200
        except Exception as e:
            print(f"Webhook delivery failed: {e}")
            return False

class NotificationDispatcher:
    """Main notification dispatcher coordinating all channels"""
    
    def __init__(self, db_pool: asyncpg.Pool, config: Dict):
        self.db_pool = db_pool
        self.channels: Dict[str, NotificationChannel] = {
            'app': MobilePushChannel(db_pool),
            'sms': SMSChannel(db_pool, config['twilio_client']),
            'email': EmailChannel(db_pool, config['smtp_client'])
        }
    
    async def dispatch(self, alert: Dict):
        """Dispatch alert through configured channels"""
        
        notification_config = alert.get('notification', {})
        channels = notification_config.get('channels', [])
        recipients = await self._resolve_recipients(notification_config.get('recipients', {}))
        
        results = {}
        
        for channel_name in channels:
            channel = self.channels.get(channel_name)
            if channel:
                success = await channel.send(alert, recipients)
                results[channel_name] = success
        
        # Handle webhook actions
        for action in alert.get('actions', []):
            if action.get('type') == 'webhook':
                async with WebhookChannel({}) as webhook:
                    success = await webhook.send(alert, action['url'])
                    results[f"webhook_{action['url']}"] = success
        
        # Store delivery status
        await self._store_delivery_status(alert['alert_id'], results)
        
        return results
    
    async def _resolve_recipients(self, recipient_config: Dict) -> List[str]:
        """Resolve recipient roles to user IDs"""
        roles = recipient_config.get('roles', [])
        
        if not roles:
            return []
        
        async with self.db_pool.acquire() as conn:
            rows = await conn.fetch(
                """SELECT u.id FROM res_users u
                   JOIN res_groups_users_rel gur ON u.id = gur.uid
                   JOIN res_groups g ON gur.gid = g.id
                   WHERE g.name = ANY($1)
                   AND u.active = true""",
                roles
            )
            return [str(row['id']) for row in rows]
    
    async def _store_delivery_status(self, alert_id: str, results: Dict):
        """Store notification delivery status"""
        async with self.db_pool.acquire() as conn:
            await conn.execute(
                """INSERT INTO iot.alert_delivery (alert_id, channel_results, delivered_at)
                   VALUES ($1, $2, NOW())""",
                alert_id, json.dumps(results)
            )
```

---

## 5. ALERT PROCESSING PIPELINE

### 5.1 Stream Processing with Apache Flink

```python
# alert_stream_processor.py
from pyflink.datastream import StreamExecutionEnvironment, TimeCharacteristic
from pyflink.table import StreamTableEnvironment, EnvironmentSettings
from pyflink.datastream.window import TumblingProcessingTimeWindows, Time
from pyflink.datastream.functions import ProcessFunction, KeyedProcessFunction
import json

class AlertStreamProcessor:
    """Apache Flink stream processor for real-time alert detection"""
    
    def __init__(self):
        self.env = StreamExecutionEnvironment.get_execution_environment()
        self.env.set_stream_time_characteristic(TimeCharacteristic.ProcessingTime)
        
        settings = EnvironmentSettings.new_instance() \
            .in_streaming_mode() \
            .build()
        
        self.table_env = StreamTableEnvironment.create(self.env, settings)
    
    def define_pipeline(self):
        """Define the alert processing pipeline"""
        
        # Source: MQTT/Kafka topic
        kafka_source = self.env.add_source(KafkaSource())
        
        # Parse JSON messages
        parsed_stream = kafka_source.map(self._parse_message)
        
        # Key by device for stateful processing
        keyed_stream = parsed_stream.key_by(lambda x: x['device_id'])
        
        # Apply alert detection
        alert_stream = keyed_stream.process(AlertDetectionFunction())
        
        # Enrich alerts
        enriched_stream = alert_stream.map(AlertEnrichmentFunction())
        
        # Deduplicate alerts
        deduplicated_stream = enriched_stream \
            .key_by(lambda x: x['alert_id']) \
            .window(TumblingProcessingTimeWindows.of(Time.seconds(60))) \
            .reduce(lambda x, y: x if x['timestamp'] < y['timestamp'] else y)
        
        # Sink to alert topic
        deduplicated_stream.add_sink(KafkaSink(topic='smartdairy.alerts'))
        
        # Sink to database
        deduplicated_stream.add_sink(DatabaseSink())
    
    def _parse_message(self, value):
        """Parse Kafka message to dict"""
        try:
            return json.loads(value)
        except:
            return None

class AlertDetectionFunction(KeyedProcessFunction):
    """Stateful function for alert detection"""
    
    def __init__(self):
        self.rule_engine = RuleEngine()
        self.state = None  # ValueState for maintaining context
    
    def process_element(self, value, ctx):
        """Process each incoming data point"""
        
        # Evaluate against rules
        alerts = self.rule_engine.evaluate_data_point(value)
        
        if alerts:
            for alert in alerts:
                yield alert
    
    def on_timer(self, timestamp, ctx):
        """Handle timer events for time-based rules"""
        pass

class AlertEnrichmentFunction(ProcessFunction):
    """Enrich alerts with additional context"""
    
    def process_element(self, value, ctx):
        """Enrich alert with animal info, location details, etc."""
        
        # Enrich with animal details
        if 'animal_rfid' in value.get('data', {}):
            animal_info = self._get_animal_info(value['data']['animal_rfid'])
            value['animal_info'] = animal_info
        
        # Enrich with historical context
        value['historical_context'] = self._get_historical_context(value)
        
        yield value
    
    def _get_animal_info(self, rfid: str) -> dict:
        """Fetch animal information from database"""
        # Implementation would query ERP database
        return {}
    
    def _get_historical_context(self, alert: dict) -> dict:
        """Get historical context for alert"""
        return {
            'similar_alerts_last_24h': 0,
            'trend': 'stable'
        }
```

---

## 6. ALERT TYPES & THRESHOLDS

### 6.1 Environmental Alert Thresholds

| Parameter | Normal Range | Warning | Critical | Action |
|-----------|--------------|---------|----------|--------|
| **Barn Temperature** | 15-25°C | > 28°C for 10 min | > 32°C | Increase ventilation |
| **Humidity** | 50-70% | > 80% | > 90% | Activate dehumidifiers |
| **Ammonia (NH3)** | < 10 ppm | 10-25 ppm | > 25 ppm | Emergency ventilation |
| **CO2** | < 1500 ppm | 1500-3000 ppm | > 3000 ppm | Check ventilation system |
| **Light Level** | Per schedule | < 100 lux (day) | Complete darkness | Check lighting |

### 6.2 Animal Health Alert Thresholds

| Parameter | Normal Range | Warning | Critical | Action |
|-----------|--------------|---------|----------|--------|
| **Body Temperature** | 38.0-39.5°C | 39.5-40.5°C | > 40.5°C or < 37.5°C | Vet notification |
| **Milk Conductivity** | 3-6 mS/cm | 6-7 mS/cm | > 7 mS/cm | Isolate & test |
| **Activity Level** | Baseline ±20% | > 150% or < 50% | > 200% or < 25% | Check animal status |
| **Rumination** | 400-600 min/day | < 350 min/day | < 200 min/day | Health check |
| **Milk Production** | Within 20% of avg | < 50% of avg | < 25% of avg | Immediate attention |

### 6.3 Production Alert Thresholds

| Metric | Normal | Warning | Critical | Action |
|--------|--------|---------|----------|--------|
| **Daily Production Drop** | < 5% | 5-15% | > 15% vs yesterday | Investigate causes |
| **Quality Variance** | ±0.1% Fat | ±0.3% Fat | ±0.5% Fat | Calibrate equipment |
| **Milking Time** | 5-8 min/cow | > 10 min | > 15 min | Check equipment |
| **Equipment Downtime** | < 1% | 1-5% | > 5% | Maintenance alert |

---

## 7. ESCALATION PROCEDURES

### 7.1 Escalation Matrix

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         ALERT ESCALATION MATRIX                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  CRITICAL ALERTS (Immediate Response Required)                               │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │  T+0 min    →  Farm Manager (SMS + App)                             │   │
│  │  T+5 min    →  Veterinarian / Operations Manager (SMS)              │   │
│  │  T+15 min   →  MD / Farm Director (Call)                            │   │
│  │  T+30 min   →  External Emergency Services (if required)            │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  WARNING ALERTS (Response within 15 minutes)                                 │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │  T+0 min    →  Barn Supervisor (App)                                │   │
│  │  T+15 min   →  Farm Manager (App + Email)                           │   │
│  │  T+1 hour   →  Escalate to Critical (if unacknowledged)             │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  INFO ALERTS (Awareness only)                                                │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │  T+0 min    →  Relevant Staff (App notification)                    │   │
│  │  T+1 hour   →  Daily digest summary                                 │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 7.2 Escalation Engine

```python
# escalation_engine.py
import asyncio
from datetime import datetime, timedelta
import asyncpg

class EscalationEngine:
    """Manage alert escalation procedures"""
    
    def __init__(self, db_pool: asyncpg.Pool, notification_dispatcher):
        self.db_pool = db_pool
        self.dispatcher = notification_dispatcher
        self.escalation_queue = asyncio.Queue()
    
    async def start(self):
        """Start escalation monitoring loop"""
        while True:
            await self._process_escalations()
            await asyncio.sleep(60)  # Check every minute
    
    async def schedule_escalation(self, alert: dict):
        """Schedule escalation for an alert"""
        escalation_config = alert.get('escalation')
        if not escalation_config or not escalation_config.get('enabled'):
            return
        
        for level in escalation_config.get('levels', []):
            escalation_time = datetime.utcnow() + self._parse_duration(level['after'])
            await self.escalation_queue.put({
                'alert_id': alert['alert_id'],
                'escalation_time': escalation_time,
                'level': level
            })
    
    async def _process_escalations(self):
        """Process pending escalations"""
        now = datetime.utcnow()
        
        # Check if alert is still active
        pending = []
        while not self.escalation_queue.empty():
            item = await self.escalation_queue.get()
            
            if item['escalation_time'] <= now:
                await self._execute_escalation(item)
            else:
                pending.append(item)
        
        # Put back pending items
        for item in pending:
            await self.escalation_queue.put(item)
    
    async def _execute_escalation(self, escalation_item: dict):
        """Execute escalation action"""
        alert_id = escalation_item['alert_id']
        level = escalation_item['level']
        
        # Fetch original alert
        async with self.db_pool.acquire() as conn:
            alert = await conn.fetchrow(
                "SELECT * FROM iot.alerts WHERE alert_id = $1 AND status = 'active'",
                alert_id
            )
        
        if not alert:
            return  # Alert already resolved
        
        # Update alert severity
        if level.get('severity'):
            await self._update_alert_severity(alert_id, level['severity'])
        
        # Add additional recipients
        if level.get('additional_recipients'):
            await self._notify_additional_recipients(alert, level['additional_recipients'])
        
        # Log escalation
        await self._log_escalation(alert_id, level)
    
    async def _update_alert_severity(self, alert_id: str, new_severity: str):
        """Update alert severity in database"""
        async with self.db_pool.acquire() as conn:
            await conn.execute(
                "UPDATE iot.alerts SET severity = $1 WHERE alert_id = $2",
                new_severity, alert_id
            )
    
    async def _notify_additional_recipients(self, alert: dict, recipients: list):
        """Notify additional escalation recipients"""
        escalation_alert = dict(alert)
        escalation_alert['notification'] = {
            'channels': ['sms', 'app'],
            'recipients': {'roles': recipients},
            'template': 'escalation'
        }
        
        await self.dispatcher.dispatch(escalation_alert)
    
    def _parse_duration(self, duration_str: str) -> timedelta:
        """Parse duration string to timedelta"""
        # Implementation similar to earlier
        return timedelta(minutes=15)  # Default
```

---

## 8. ANALYTICS & REPORTING

### 8.1 Alert Analytics Dashboard

| Metric | Description | Visualization |
|--------|-------------|---------------|
| **Alert Volume** | Total alerts by category | Time series chart |
| **Response Time** | Time to acknowledge alerts | Histogram |
| **Resolution Time** | Time to resolve alerts | Trend line |
| **False Positive Rate** | % of alerts that were false | Pie chart |
| **Top Alert Types** | Most frequent alert rules | Bar chart |
| **Alert Heatmap** | Alerts by time and location | Heatmap |

### 8.2 Alert Reporting API

```python
# alert_analytics.py
from fastapi import FastAPI, Query
from typing import List, Optional
from datetime import datetime, date

app = FastAPI()

@app.get("/api/v1/analytics/alerts/summary")
async def get_alert_summary(
    start_date: date = Query(...),
    end_date: date = Query(...),
    barn_id: Optional[str] = None,
    category: Optional[str] = None
):
    """Get alert summary statistics"""
    
    async with db_pool.acquire() as conn:
        # Total alerts
        total = await conn.fetchval("""
            SELECT COUNT(*) FROM iot.alerts
            WHERE created_at >= $1 AND created_at < $2
            AND ($3::text IS NULL OR location->>'barn_id' = $3)
            AND ($4::text IS NULL OR category = $4)
        """, start_date, end_date + timedelta(days=1), barn_id, category)
        
        # By severity
        by_severity = await conn.fetch("""
            SELECT severity, COUNT(*) as count
            FROM iot.alerts
            WHERE created_at >= $1 AND created_at < $2
            GROUP BY severity
        """, start_date, end_date + timedelta(days=1))
        
        # By category
        by_category = await conn.fetch("""
            SELECT category, COUNT(*) as count
            FROM iot.alerts
            WHERE created_at >= $1 AND created_at < $2
            GROUP BY category
        """, start_date, end_date + timedelta(days=1))
        
        # Average resolution time
        avg_resolution = await conn.fetchval("""
            SELECT AVG(EXTRACT(EPOCH FROM (resolved_at - created_at)))
            FROM iot.alerts
            WHERE created_at >= $1 AND created_at < $2
            AND resolved_at IS NOT NULL
        """, start_date, end_date + timedelta(days=1))
    
    return {
        'period': {'start': start_date, 'end': end_date},
        'total_alerts': total,
        'by_severity': {row['severity']: row['count'] for row in by_severity},
        'by_category': {row['category']: row['count'] for row in by_category},
        'avg_resolution_time_seconds': avg_resolution
    }
```

---

## 9. IMPLEMENTATION GUIDE

### 9.1 Deployment Steps

1. **Infrastructure Setup**
   ```bash
   # Deploy Redis for caching
   docker run -d --name alert-redis redis:7-alpine
   
   # Deploy RabbitMQ for queuing
   docker run -d --name alert-queue rabbitmq:3-management
   
   # Deploy alert processor
   docker run -d --name alert-engine \
     -e DB_HOST=postgres.smartdairy.local \
     -e REDIS_HOST=alert-redis \
     -e MQTT_HOST=mqtt.smartdairybd.com \
     smart-dairy/alert-engine:latest
   ```

2. **Rule Configuration**
   - Load default alert rules
   - Customize thresholds per barn
   - Configure notification channels
   - Test rule evaluation

3. **Integration Testing**
   - Simulate sensor data
   - Verify alert generation
   - Test notification delivery
   - Validate escalation procedures

### 9.2 Performance Tuning

| Parameter | Default | Tuning Guidance |
|-----------|---------|-----------------|
| **Processing Delay** | < 5 seconds | Increase Flink parallelism if > 5s |
| **Alert Queue Size** | 10,000 | Scale Redis cluster if > 80% |
| **Notification Rate** | 100/sec | Add notification workers if backlog |
| **Database Connections** | 20 | Increase pool size with load |

---

## 10. APPENDICES

### Appendix A: Alert Database Schema

```sql
-- Alerts table
CREATE TABLE iot.alerts (
    id SERIAL PRIMARY KEY,
    alert_id VARCHAR(100) UNIQUE NOT NULL,
    rule_id VARCHAR(50) NOT NULL,
    rule_name VARCHAR(200),
    category VARCHAR(50),
    severity VARCHAR(20),
    device_id VARCHAR(100),
    location JSONB,
    data JSONB,
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT NOW(),
    acknowledged_at TIMESTAMP,
    acknowledged_by INTEGER,
    resolved_at TIMESTAMP,
    resolution_notes TEXT
);

-- Alert delivery log
CREATE TABLE iot.alert_delivery (
    id SERIAL PRIMARY KEY,
    alert_id VARCHAR(100) REFERENCES iot.alerts(alert_id),
    channel VARCHAR(50),
    channel_results JSONB,
    delivered_at TIMESTAMP
);

-- Alert escalation log
CREATE TABLE iot.alert_escalations (
    id SERIAL PRIMARY KEY,
    alert_id VARCHAR(100) REFERENCES iot.alerts(alert_id),
    escalation_level INTEGER,
    escalated_at TIMESTAMP,
    escalated_to VARCHAR(200)
);

-- Create indexes
CREATE INDEX idx_alerts_created_at ON iot.alerts(created_at);
CREATE INDEX idx_alerts_status ON iot.alerts(status);
CREATE INDEX idx_alerts_category ON iot.alerts(category);
CREATE INDEX idx_alerts_severity ON iot.alerts(severity);
```

---

**END OF REAL-TIME ALERT ENGINE SPECIFICATION**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | August 22, 2026 | IoT Engineer | Initial version |
