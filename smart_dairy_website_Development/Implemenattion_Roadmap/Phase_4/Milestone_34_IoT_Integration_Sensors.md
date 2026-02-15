

## 6. Implementation Logic

### 6.1 MQTT Message Handler

```python
# mqtt_message_handler.py
import paho.mqtt.client as mqtt
import json
import logging
from datetime import datetime
from typing import Dict, Callable, List
import asyncio
from concurrent.futures import ThreadPoolExecutor

class MQTTMessageHandler:
    def __init__(self, config: Dict, message_processors: Dict[str, Callable]):
        self.config = config
        self.processors = message_processors
        self.client = mqtt.Client(
            client_id=config['client_id'],
            protocol=mqtt.MQTTv5
        )
        self.executor = ThreadPoolExecutor(max_workers=10)
        self.message_queue = asyncio.Queue(maxsize=10000)
        
        # Setup callbacks
        self.client.on_connect = self._on_connect
        self.client.on_message = self._on_message
        self.client.on_disconnect = self._on_disconnect
        self.client.on_subscribe = self._on_subscribe
        
        # Configure TLS
        if config.get('tls_enabled'):
            self.client.tls_set(
                ca_certs=config['ca_cert'],
                certfile=config['client_cert'],
                keyfile=config['client_key'],
                tls_version=mqtt.ssl.PROTOCOL_TLSv1_2
            )
            self.client.tls_insecure_set(False)
        
        # Configure authentication
        if config.get('username'):
            self.client.username_pw_set(
                config['username'],
                config['password']
            )
        
        # Configure reconnection
        self.client.reconnect_delay_set(min_delay=1, max_delay=30)
        
    def connect(self):
        """Connect to MQTT broker"""
        try:
            self.client.connect(
                self.config['broker'],
                self.config['port'],
                keepalive=60
            )
            self.client.loop_start()
            logging.info(f"Connected to MQTT broker at {self.config['broker']}")
        except Exception as e:
            logging.error(f"Failed to connect to MQTT broker: {e}")
            raise
    
    def _on_connect(self, client, userdata, flags, rc, properties=None):
        """Callback for when client connects to broker"""
        if rc == 0:
            logging.info("Connected to MQTT broker successfully")
            
            # Subscribe to topics
            for topic, qos in self.config.get('subscriptions', []):
                client.subscribe(topic, qos=qos)
                logging.info(f"Subscribed to: {topic} (QoS {qos})")
        else:
            logging.error(f"Connection failed with code: {rc}")
    
    def _on_message(self, client, userdata, message):
        """Callback for when message is received"""
        try:
            # Parse topic to determine message type
            topic_parts = message.topic.split('/')
            
            # Put message in queue for async processing
            self.message_queue.put_nowait({
                'topic': message.topic,
                'payload': message.payload,
                'qos': message.qos,
                'retain': message.retain,
                'timestamp': datetime.utcnow()
            })
            
        except asyncio.QueueFull:
            logging.warning("Message queue full, dropping message")
        except Exception as e:
            logging.error(f"Error handling message: {e}")
    
    async def process_messages(self):
        """Main message processing loop"""
        while True:
            try:
                message = await asyncio.wait_for(
                    self.message_queue.get(), 
                    timeout=1.0
                )
                
                # Process in thread pool to avoid blocking
                self.executor.submit(self._process_message, message)
                
            except asyncio.TimeoutError:
                continue
            except Exception as e:
                logging.error(f"Error in message processing loop: {e}")
    
    def _process_message(self, message: Dict):
        """Process individual message"""
        try:
            topic = message['topic']
            payload = message['payload']
            
            # Parse JSON payload
            try:
                data = json.loads(payload.decode('utf-8'))
            except json.JSONDecodeError:
                data = {'raw_payload': payload.decode('utf-8', errors='ignore')}
            
            # Route to appropriate processor
            processor = self._get_processor(topic)
            if processor:
                result = processor(data, topic)
                
                # Acknowledge processing if needed
                if result:
                    logging.debug(f"Successfully processed message on {topic}")
            else:
                logging.warning(f"No processor found for topic: {topic}")
                
        except Exception as e:
            logging.error(f"Error processing message: {e}")
            # Send to dead letter queue
            self._send_to_dlq(message, str(e))
    
    def _get_processor(self, topic: str) -> Callable:
        """Get appropriate processor for topic"""
        topic_mapping = {
            r'dairy/farm/\d+/barn/\d+/zone/\d+/sensor/\w+/telemetry': self.processors.get('environmental'),
            r'dairy/farm/\d+/animal/\w+/activity/\w+': self.processors.get('activity'),
            r'dairy/farm/\d+/milking/parlor/\d+/session/\w+': self.processors.get('milking'),
            r'dairy/farm/\d+/rfid/\w+/\w+/tag': self.processors.get('rfid'),
            r'dairy/farm/\d+/alert/\w+/\w+': self.processors.get('alert')
        }
        
        import re
        for pattern, processor in topic_mapping.items():
            if re.match(pattern, topic):
                return processor
        
        return None
    
    def _send_to_dlq(self, message: Dict, error: str):
        """Send failed message to dead letter queue"""
        dlq_message = {
            'original_message': message,
            'error': error,
            'failed_at': datetime.utcnow().isoformat()
        }
        
        # Publish to DLQ topic
        self.client.publish(
            'system/dlq/messages',
            json.dumps(dlq_message),
            qos=1
        )
    
    def _on_disconnect(self, client, userdata, rc, properties=None):
        """Callback for when client disconnects"""
        if rc != 0:
            logging.warning(f"Unexpected disconnection (rc={rc}), will auto-reconnect")
    
    def _on_subscribe(self, client, userdata, mid, granted_qos, properties=None):
        """Callback for when subscription is acknowledged"""
        logging.info(f"Subscription acknowledged: mid={mid}, qos={granted_qos}")
    
    def publish(self, topic: str, payload: Dict, qos: int = 1, retain: bool = False):
        """Publish message to topic"""
        try:
            message = json.dumps(payload)
            result = self.client.publish(topic, message, qos=qos, retain=retain)
            
            if result.rc == mqtt.MQTT_ERR_SUCCESS:
                return True
            else:
                logging.error(f"Publish failed: {result.rc}")
                return False
                
        except Exception as e:
            logging.error(f"Error publishing message: {e}")
            return False
    
    def disconnect(self):
        """Disconnect from broker"""
        self.client.loop_stop()
        self.client.disconnect()
        self.executor.shutdown(wait=True)
```

### 6.2 Data Ingestion Pipeline

```python
# data_ingestion_pipeline.py
from typing import Dict, List, Optional
from datetime import datetime
import asyncio
import asyncpg
import aiokafka
from dataclasses import dataclass
import logging

@dataclass
class IngestionContext:
    farm_id: int
    device_id: str
    device_type: str
    timestamp: datetime
    raw_data: Dict
    processed_data: Optional[Dict] = None
    quality_score: int = 100
    errors: List[str] = None

class DataIngestionPipeline:
    def __init__(self, db_config: Dict, kafka_config: Dict):
        self.db_config = db_config
        self.kafka_config = kafka_config
        self.db_pool = None
        self.kafka_producer = None
        self.validation_rules = {}
        self.transformers = {}
        
    async def initialize(self):
        """Initialize database and Kafka connections"""
        self.db_pool = await asyncpg.create_pool(**self.db_config)
        self.kafka_producer = aiokafka.AIOKafkaProducer(
            bootstrap_servers=self.kafka_config['servers'],
            value_serializer=lambda v: json.dumps(v).encode('utf-8'),
            compression_type='lz4'
        )
        await self.kafka_producer.start()
        
    async def ingest(self, context: IngestionContext) -> Dict:
        """Main ingestion pipeline"""
        try:
            # Step 1: Validate
            context = await self._validate(context)
            if context.errors:
                return await self._handle_validation_failure(context)
            
            # Step 2: Transform
            context = await self._transform(context)
            
            # Step 3: Enrich
            context = await self._enrich(context)
            
            # Step 4: Store
            storage_result = await self._store(context)
            
            # Step 5: Publish events
            await self._publish_events(context)
            
            return {
                'success': True,
                'context': context,
                'storage': storage_result
            }
            
        except Exception as e:
            logging.error(f"Ingestion failed: {e}")
            return await self._handle_ingestion_failure(context, e)
    
    async def _validate(self, context: IngestionContext) -> IngestionContext:
        """Validate incoming data"""
        if context.errors is None:
            context.errors = []
        
        # Get validation rules for device type
        rules = self.validation_rules.get(context.device_type, [])
        
        for rule in rules:
            try:
                is_valid, error_msg = await self._apply_validation_rule(
                    rule, context.raw_data
                )
                if not is_valid:
                    context.errors.append(error_msg)
                    context.quality_score -= rule.get('penalty', 10)
            except Exception as e:
                logging.error(f"Validation error: {e}")
                context.errors.append(f"Validation failed: {e}")
        
        return context
    
    async def _apply_validation_rule(self, rule: Dict, data: Dict) -> tuple:
        """Apply a single validation rule"""
        field = rule['field']
        rule_type = rule['type']
        
        value = self._get_nested_value(data, field)
        
        if rule_type == 'required':
            return value is not None, f"Required field missing: {field}"
        
        elif rule_type == 'range':
            if value is None:
                return True, None
            min_val = rule.get('min')
            max_val = rule.get('max')
            is_valid = (min_val is None or value >= min_val) and \
                      (max_val is None or value <= max_val)
            return is_valid, f"Value out of range for {field}: {value}"
        
        elif rule_type == 'type':
            expected_type = rule['expected_type']
            type_map = {
                'int': int,
                'float': float,
                'str': str,
                'bool': bool
            }
            is_valid = isinstance(value, type_map.get(expected_type, object))
            return is_valid, f"Invalid type for {field}: expected {expected_type}"
        
        elif rule_type == 'regex':
            import re
            pattern = rule['pattern']
            is_valid = bool(re.match(pattern, str(value)))
            return is_valid, f"Value doesn't match pattern for {field}"
        
        return True, None
    
    async def _transform(self, context: IngestionContext) -> IngestionContext:
        """Transform data to standard format"""
        device_type = context.device_type
        
        if device_type in self.transformers:
            transformer = self.transformers[device_type]
            context.processed_data = await transformer(context.raw_data)
        else:
            # Default passthrough transformation
            context.processed_data = context.raw_data.copy()
        
        # Add metadata
        context.processed_data['_meta'] = {
            'farm_id': context.farm_id,
            'device_id': context.device_id,
            'device_type': device_type,
            'ingestion_timestamp': datetime.utcnow().isoformat(),
            'quality_score': context.quality_score
        }
        
        return context
    
    async def _enrich(self, context: IngestionContext) -> IngestionContext:
        """Enrich data with additional context"""
        # Get device metadata
        async with self.db_pool.acquire() as conn:
            device_info = await conn.fetchrow(
                """
                SELECT barn_id, zone_id, sensor_types, location_description
                FROM iot_devices WHERE device_id = $1
                """,
                context.device_id
            )
        
        if device_info:
            context.processed_data['_meta'].update({
                'barn_id': device_info['barn_id'],
                'zone_id': device_info['zone_id'],
                'location': device_info['location_description']
            })
        
        # Add geolocation if available
        if device_info and 'location_description' in device_info:
            context.processed_data['_meta']['location_description'] = \
                device_info['location_description']
        
        return context
    
    async def _store(self, context: IngestionContext) -> Dict:
        """Store data in database"""
        data = context.processed_data
        meta = data.pop('_meta', {})
        
        async with self.db_pool.acquire() as conn:
            async with conn.transaction():
                # Store based on device type
                if context.device_type == 'environmental_sensor':
                    await self._store_environmental_data(conn, data, meta)
                elif context.device_type == 'activity_collar':
                    await self._store_activity_data(conn, data, meta)
                elif context.device_type == 'milk_meter':
                    await self._store_milking_data(conn, data, meta)
                elif context.device_type == 'rfid_reader':
                    await self._store_rfid_data(conn, data, meta)
                
                return {'stored': True, 'timestamp': datetime.utcnow().isoformat()}
    
    async def _store_environmental_data(self, conn, data: Dict, meta: Dict):
        """Store environmental sensor data"""
        timestamp = datetime.fromisoformat(data.get('timestamp', datetime.utcnow().isoformat()))
        sensor_id = meta['device_id']
        farm_id = meta['farm_id']
        barn_id = meta.get('barn_id')
        zone_id = meta.get('zone_id')
        
        readings = data.get('readings', {})
        
        for metric_name, value in readings.items():
            await conn.execute(
                """
                INSERT INTO sensor_data 
                (time, sensor_id, farm_id, barn_id, zone_id, sensor_type, metric_name, value, unit, quality_score)
                VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10)
                """,
                timestamp,
                sensor_id,
                farm_id,
                barn_id,
                zone_id,
                'environmental',
                metric_name,
                value,
                self._get_unit_for_metric(metric_name),
                meta.get('quality_score', 100)
            )
    
    def _get_unit_for_metric(self, metric_name: str) -> str:
        """Get unit for metric"""
        units = {
            'temperature_c': '°C',
            'humidity_percent': '%RH',
            'pressure_hpa': 'hPa',
            'co2_ppm': 'ppm',
            'nh3_ppm': 'ppm',
            'light_lux': 'lux'
        }
        return units.get(metric_name, '')
    
    async def _publish_events(self, context: IngestionContext):
        """Publish events to Kafka"""
        event = {
            'event_type': 'data_ingested',
            'farm_id': context.farm_id,
            'device_id': context.device_id,
            'device_type': context.device_type,
            'timestamp': datetime.utcnow().isoformat(),
            'data_summary': {
                'quality_score': context.quality_score,
                'metrics_count': len(context.processed_data) if context.processed_data else 0
            }
        }
        
        await self.kafka_producer.send(
            'ingestion-events',
            key=context.device_id.encode(),
            value=event
        )
    
    async def _handle_validation_failure(self, context: IngestionContext) -> Dict:
        """Handle validation failures"""
        logging.warning(f"Validation failed for {context.device_id}: {context.errors}")
        
        # Store in validation failures table
        async with self.db_pool.acquire() as conn:
            await conn.execute(
                """
                INSERT INTO validation_failures 
                (farm_id, device_id, device_type, raw_data, errors, failed_at)
                VALUES ($1, $2, $3, $4, $5, $6)
                """,
                context.farm_id,
                context.device_id,
                context.device_type,
                json.dumps(context.raw_data),
                context.errors,
                datetime.utcnow()
            )
        
        return {'success': False, 'errors': context.errors}
    
    async def _handle_ingestion_failure(self, context: IngestionContext, error: Exception) -> Dict:
        """Handle general ingestion failures"""
        logging.error(f"Ingestion failed for {context.device_id}: {error}")
        
        # Send to dead letter queue
        await self.kafka_producer.send(
            'ingestion-dlq',
            value={
                'context': {
                    'farm_id': context.farm_id,
                    'device_id': context.device_id,
                    'device_type': context.device_type,
                    'raw_data': context.raw_data
                },
                'error': str(error),
                'timestamp': datetime.utcnow().isoformat()
            }
        )
        
        return {'success': False, 'error': str(error)}
    
    def _get_nested_value(self, data: Dict, path: str):
        """Get nested dictionary value"""
        keys = path.split('.')
        value = data
        for key in keys:
            if isinstance(value, dict) and key in value:
                value = value[key]
            else:
                return None
        return value
```

### 6.3 Alert Rule Engine

```python
# alert_rule_engine.py
from typing import Dict, List, Optional, Callable
from datetime import datetime, timedelta
import json
import asyncio
from dataclasses import dataclass, field
import operator

@dataclass
class AlertCondition:
    field: str
    operator: str
    value: any
    logic: str = 'AND'
    
@dataclass
class AlertRule:
    rule_id: str
    name: str
    description: str
    priority: str
    conditions: List[AlertCondition]
    actions: List[Dict]
    enabled: bool = True
    suppression_window: int = 900  # seconds
    max_alerts_per_hour: int = 10
    created_at: datetime = field(default_factory=datetime.utcnow)

class AlertRuleEngine:
    def __init__(self, db_pool, notification_service):
        self.db_pool = db_pool
        self.notifier = notification_service
        self.rules: Dict[str, AlertRule] = {}
        self.alert_history: Dict[str, List[datetime]] = {}
        self.operator_map = {
            'eq': operator.eq,
            'ne': operator.ne,
            'gt': operator.gt,
            'lt': operator.lt,
            'gte': operator.ge,
            'lte': operator.le,
            'in': lambda x, y: x in y,
            'contains': lambda x, y: y in x if x else False
        }
        
    async def load_rules(self):
        """Load rules from database"""
        async with self.db_pool.acquire() as conn:
            rows = await conn.fetch(
                """
                SELECT rule_id, name, description, priority, 
                       conditions, actions, enabled, suppression, created_at
                FROM alert_rules WHERE enabled = true
                """
            )
            
            for row in rows:
                conditions = [
                    AlertCondition(**c) for c in json.loads(row['conditions'])
                ]
                suppression = json.loads(row['suppression']) if row['suppression'] else {}
                
                self.rules[row['rule_id']] = AlertRule(
                    rule_id=row['rule_id'],
                    name=row['name'],
                    description=row['description'],
                    priority=row['priority'],
                    conditions=conditions,
                    actions=json.loads(row['actions']),
                    enabled=row['enabled'],
                    suppression_window=suppression.get('window_seconds', 900),
                    max_alerts_per_hour=suppression.get('max_per_hour', 10),
                    created_at=row['created_at']
                )
    
    async def evaluate(self, event_data: Dict, source_type: str, source_id: str) -> List[Dict]:
        """Evaluate all rules against event data"""
        triggered_alerts = []
        
        for rule in self.rules.values():
            if not rule.enabled:
                continue
                
            # Check if rule should be evaluated for this source
            if not self._applies_to_source(rule, source_type, source_id):
                continue
            
            # Evaluate conditions
            if await self._evaluate_conditions(rule.conditions, event_data):
                # Check suppression
                if not self._is_suppressed(rule, source_id):
                    alert = await self._create_alert(rule, event_data, source_type, source_id)
                    triggered_alerts.append(alert)
                    await self._execute_actions(rule.actions, alert)
        
        return triggered_alerts
    
    async def _evaluate_conditions(self, conditions: List[AlertCondition], data: Dict) -> bool:
        """Evaluate rule conditions"""
        if not conditions:
            return True
        
        results = []
        for condition in conditions:
            actual_value = self._get_field_value(data, condition.field)
            expected_value = condition.value
            
            op_func = self.operator_map.get(condition.operator)
            if op_func:
                try:
                    result = op_func(actual_value, expected_value)
                    results.append((result, condition.logic))
                except Exception as e:
                    results.append((False, condition.logic))
            else:
                results.append((False, condition.logic))
        
        # Combine results based on logic operators
        if not results:
            return True
        
        # Start with first result
        final_result = results[0][0]
        
        for i in range(1, len(results)):
            current_result, logic = results[i]
            if logic == 'AND':
                final_result = final_result and current_result
            elif logic == 'OR':
                final_result = final_result or current_result
        
        return final_result
    
    def _is_suppressed(self, rule: AlertRule, source_id: str) -> bool:
        """Check if alert should be suppressed"""
        key = f"{rule.rule_id}:{source_id}"
        now = datetime.utcnow()
        
        if key not in self.alert_history:
            self.alert_history[key] = []
        
        history = self.alert_history[key]
        
        # Remove old entries outside suppression window
        window_start = now - timedelta(seconds=rule.suppression_window)
        history[:] = [t for t in history if t > window_start]
        
        # Check suppression window
        if history:
            time_since_last = (now - history[-1]).total_seconds()
            if time_since_last < rule.suppression_window:
                return True
        
        # Check max alerts per hour
        hour_ago = now - timedelta(hours=1)
        alerts_last_hour = len([t for t in history if t > hour_ago])
        if alerts_last_hour >= rule.max_alerts_per_hour:
            return True
        
        # Record this alert
        history.append(now)
        return False
    
    async def _create_alert(self, rule: AlertRule, event_data: Dict, 
                           source_type: str, source_id: str) -> Dict:
        """Create alert record"""
        alert = {
            'alert_id': f"ALT-{datetime.utcnow().strftime('%Y%m%d%H%M%S')}-{rule.rule_id}",
            'rule_id': rule.rule_id,
            'rule_name': rule.name,
            'priority': rule.priority,
            'status': 'open',
            'source_type': source_type,
            'source_id': source_id,
            'message': self._format_alert_message(rule, event_data),
            'details': {
                'event_data': event_data,
                'triggered_at': datetime.utcnow().isoformat()
            },
            'created_at': datetime.utcnow().isoformat()
        }
        
        # Store in database
        async with self.db_pool.acquire() as conn:
            await conn.execute(
                """
                INSERT INTO alerts 
                (alert_id, rule_id, priority, status, source_type, source_id, 
                 message, details, created_at)
                VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)
                """,
                alert['alert_id'], alert['rule_id'], alert['priority'],
                alert['status'], alert['source_type'], alert['source_id'],
                alert['message'], json.dumps(alert['details']),
                datetime.utcnow()
            )
        
        return alert
    
    async def _execute_actions(self, actions: List[Dict], alert: Dict):
        """Execute alert actions"""
        for action in actions:
            action_type = action.get('type')
            
            try:
                if action_type == 'notification':
                    await self._send_notification(action, alert)
                elif action_type == 'webhook':
                    await self._send_webhook(action, alert)
                elif action_type == 'command':
                    await self._execute_command(action, alert)
                elif action_type == 'escalate':
                    await self._schedule_escalation(action, alert)
            except Exception as e:
                logging.error(f"Action execution failed: {e}")
    
    def _get_field_value(self, data: Dict, field: str):
        """Get field value from nested dictionary"""
        parts = field.split('.')
        value = data
        for part in parts:
            if isinstance(value, dict) and part in value:
                value = value[part]
            else:
                return None
        return value
    
    def _format_alert_message(self, rule: AlertRule, data: Dict) -> str:
        """Format alert message with data substitution"""
        message = rule.description
        # Simple template substitution
        for key, value in data.items():
            placeholder = f"{{{key}}}"
            if placeholder in message:
                message = message.replace(placeholder, str(value))
        return message
    
    def _applies_to_source(self, rule: AlertRule, source_type: str, source_id: str) -> bool:
        """Check if rule applies to source"""
        # This would typically check rule scope configuration
        return True
```

### 6.4 Sensor Calibration Logic

```python
# sensor_calibration.py
from typing import Dict, List, Tuple, Optional
from datetime import datetime, timedelta
import numpy as np
from dataclasses import dataclass

@dataclass
class CalibrationPoint:
    reference_value: float
    sensor_reading: float
    timestamp: datetime

@dataclass
class CalibrationResult:
    device_id: str
    calibration_date: datetime
    slope: float
    intercept: float
    r_squared: float
    rmse: float
    valid: bool
    points_used: int

class SensorCalibration:
    def __init__(self, db_pool):
        self.db_pool = db_pool
        
    async def perform_calibration(self, device_id: str, 
                                 calibration_points: List[CalibrationPoint]) -> CalibrationResult:
        """Perform linear calibration for sensor"""
        
        if len(calibration_points) < 2:
            return CalibrationResult(
                device_id=device_id,
                calibration_date=datetime.utcnow(),
                slope=1.0,
                intercept=0.0,
                r_squared=0.0,
                rmse=0.0,
                valid=False,
                points_used=0
            )
        
        # Extract values
        x = np.array([p.sensor_reading for p in calibration_points])
        y = np.array([p.reference_value for p in calibration_points])
        
        # Linear regression
        n = len(x)
        sum_x = np.sum(x)
        sum_y = np.sum(y)
        sum_xy = np.sum(x * y)
        sum_x2 = np.sum(x ** 2)
        sum_y2 = np.sum(y ** 2)
        
        # Calculate slope and intercept
        denominator = n * sum_x2 - sum_x ** 2
        if denominator == 0:
            slope = 1.0
            intercept = 0.0
        else:
            slope = (n * sum_xy - sum_x * sum_y) / denominator
            intercept = (sum_y - slope * sum_x) / n
        
        # Calculate R-squared
        ss_res = np.sum((y - (slope * x + intercept)) ** 2)
        ss_tot = np.sum((y - np.mean(y)) ** 2)
        r_squared = 1 - (ss_res / ss_tot) if ss_tot != 0 else 0
        
        # Calculate RMSE
        rmse = np.sqrt(np.mean((y - (slope * x + intercept)) ** 2))
        
        # Validate calibration
        valid = r_squared >= 0.95 and rmse < 0.5
        
        result = CalibrationResult(
            device_id=device_id,
            calibration_date=datetime.utcnow(),
            slope=float(slope),
            intercept=float(intercept),
            r_squared=float(r_squared),
            rmse=float(rmse),
            valid=valid,
            points_used=n
        )
        
        # Store calibration
        await self._store_calibration(result, calibration_points)
        
        return result
    
    def apply_calibration(self, raw_value: float, calibration: CalibrationResult) -> float:
        """Apply calibration to raw sensor reading"""
        return calibration.slope * raw_value + calibration.intercept
    
    async def get_active_calibration(self, device_id: str) -> Optional[CalibrationResult]:
        """Get most recent valid calibration for device"""
        async with self.db_pool.acquire() as conn:
            row = await conn.fetchrow(
                """
                SELECT slope, intercept, r_squared, rmse, calibrated_at
                FROM calibration_history
                WHERE device_id = $1 AND valid = true
                ORDER BY calibrated_at DESC
                LIMIT 1
                """,
                device_id
            )
            
            if row:
                return CalibrationResult(
                    device_id=device_id,
                    calibration_date=row['calibrated_at'],
                    slope=row['slope'],
                    intercept=row['intercept'],
                    r_squared=row['r_squared'],
                    rmse=row['rmse'],
                    valid=True,
                    points_used=0
                )
            return None
    
    async def check_calibration_drift(self, device_id: str, 
                                     tolerance: float = 0.05) -> Dict:
        """Check if sensor has drifted from calibration"""
        # Get current calibration
        calibration = await self.get_active_calibration(device_id)
        if not calibration:
            return {'status': 'no_calibration', 'drift_detected': True}
        
        # Get recent readings
        async with self.db_pool.acquire() as conn:
            rows = await conn.fetch(
                """
                SELECT value, time
                FROM sensor_data
                WHERE sensor_id = $1
                AND time > NOW() - INTERVAL '24 hours'
                ORDER BY time DESC
                LIMIT 100
                """,
                device_id
            )
        
        if len(rows) < 10:
            return {'status': 'insufficient_data', 'drift_detected': False}
        
        # Calculate statistics
        values = [r['value'] for r in rows]
        mean_value = np.mean(values)
        std_value = np.std(values)
        
        # Check if values are within expected range
        # This is simplified - in practice would compare against reference
        drift_detected = std_value > tolerance * mean_value if mean_value != 0 else False
        
        return {
            'status': 'checked',
            'drift_detected': drift_detected,
            'current_mean': float(mean_value),
            'current_std': float(std_value),
            'calibration_date': calibration.calibration_date.isoformat(),
            'recommendation': 'recalibrate' if drift_detected else 'continue_monitoring'
        }
```

### 6.5 Data Aggregation Algorithms

```python
# data_aggregation.py
from typing import List, Dict, Optional, Callable
from datetime import datetime, timedelta
import numpy as np
from dataclasses import dataclass
import asyncio

@dataclass
class AggregationWindow:
    start_time: datetime
    end_time: datetime
    window_type: str  # tumbling, sliding, session

@dataclass
class AggregationResult:
    window: AggregationWindow
    sensor_id: str
    metric_name: str
    count: int
    sum_value: float
    avg_value: float
    min_value: float
    max_value: float
    std_value: float
    p50_value: float
    p95_value: float
    p99_value: float
    first_value: float
    last_value: float

class DataAggregation:
    def __init__(self, db_pool):
        self.db_pool = db_pool
        
    async def aggregate_tumbling(self, sensor_id: str, metric_name: str,
                                 start_time: datetime, end_time: datetime,
                                 window_minutes: int = 15) -> List[AggregationResult]:
        """Create tumbling window aggregations"""
        
        async with self.db_pool.acquire() as conn:
            rows = await conn.fetch(
                """
                SELECT 
                    time_bucket($1, time) as window_start,
                    COUNT(*) as count,
                    AVG(value) as avg_value,
                    MIN(value) as min_value,
                    MAX(value) as max_value,
                    STDDEV(value) as std_value,
                    PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY value) as p50,
                    PERCENTILE_CONT(0.95) WITHIN GROUP (ORDER BY value) as p95,
                    PERCENTILE_CONT(0.99) WITHIN GROUP (ORDER BY value) as p99,
                    FIRST(value, time) as first_value,
                    LAST(value, time) as last_value
                FROM sensor_data
                WHERE sensor_id = $2 
                AND metric_name = $3
                AND time >= $4 AND time < $5
                GROUP BY window_start
                ORDER BY window_start
                """,
                f'{window_minutes} minutes',
                sensor_id,
                metric_name,
                start_time,
                end_time
            )
        
        results = []
        for row in rows:
            window = AggregationWindow(
                start_time=row['window_start'],
                end_time=row['window_start'] + timedelta(minutes=window_minutes),
                window_type='tumbling'
            )
            
            results.append(AggregationResult(
                window=window,
                sensor_id=sensor_id,
                metric_name=metric_name,
                count=row['count'],
                sum_value=row['avg_value'] * row['count'] if row['avg_value'] else 0,
                avg_value=row['avg_value'] or 0,
                min_value=row['min_value'] or 0,
                max_value=row['max_value'] or 0,
                std_value=row['std_value'] or 0,
                p50_value=row['p50'] or 0,
                p95_value=row['p95'] or 0,
                p99_value=row['p99'] or 0,
                first_value=row['first_value'] or 0,
                last_value=row['last_value'] or 0
            ))
        
        return results
    
    async def aggregate_sliding(self, sensor_id: str, metric_name: str,
                                start_time: datetime, end_time: datetime,
                                window_minutes: int = 60, 
                                slide_minutes: int = 15) -> List[AggregationResult]:
        """Create sliding window aggregations"""
        
        results = []
        current_start = start_time
        
        while current_start < end_time:
            window_end = min(current_start + timedelta(minutes=window_minutes), end_time)
            
            async with self.db_pool.acquire() as conn:
                row = await conn.fetchrow(
                    """
                    SELECT 
                        COUNT(*) as count,
                        AVG(value) as avg_value,
                        MIN(value) as min_value,
                        MAX(value) as max_value,
                        STDDEV(value) as std_value
                    FROM sensor_data
                    WHERE sensor_id = $1 
                    AND metric_name = $2
                    AND time >= $3 AND time < $4
                    """,
                    sensor_id,
                    metric_name,
                    current_start,
                    window_end
                )
            
            if row and row['count'] > 0:
                window = AggregationWindow(
                    start_time=current_start,
                    end_time=window_end,
                    window_type='sliding'
                )
                
                results.append(AggregationResult(
                    window=window,
                    sensor_id=sensor_id,
                    metric_name=metric_name,
                    count=row['count'],
                    sum_value=row['avg_value'] * row['count'] if row['avg_value'] else 0,
                    avg_value=row['avg_value'] or 0,
                    min_value=row['min_value'] or 0,
                    max_value=row['max_value'] or 0,
                    std_value=row['std_value'] or 0,
                    p50_value=0,  # Would need separate calculation
                    p95_value=0,
                    p99_value=0,
                    first_value=0,
                    last_value=0
                ))
            
            current_start += timedelta(minutes=slide_minutes)
        
        return results
    
    def calculate_ewma(self, values: List[float], alpha: float = 0.3) -> List[float]:
        """Calculate Exponentially Weighted Moving Average"""
        if not values:
            return []
        
        ewma = [values[0]]
        for value in values[1:]:
            ewma.append(alpha * value + (1 - alpha) * ewma[-1])
        
        return ewma
    
    def detect_trend(self, values: List[float]) -> Dict:
        """Detect trend in time series"""
        if len(values) < 3:
            return {'trend': 'insufficient_data', 'slope': 0, 'confidence': 0}
        
        x = np.arange(len(values))
        y = np.array(values)
        
        # Linear regression
        slope, intercept = np.polyfit(x, y, 1)
        
        # Calculate R-squared
        y_pred = slope * x + intercept
        ss_res = np.sum((y - y_pred) ** 2)
        ss_tot = np.sum((y - np.mean(y)) ** 2)
        r_squared = 1 - (ss_res / ss_tot) if ss_tot != 0 else 0
        
        # Determine trend direction
        if slope > 0.01:
            trend = 'increasing'
        elif slope < -0.01:
            trend = 'decreasing'
        else:
            trend = 'stable'
        
        return {
            'trend': trend,
            'slope': float(slope),
            'r_squared': float(r_squared),
            'confidence': float(r_squared)
        }
    
    async def compare_periods(self, sensor_id: str, metric_name: str,
                             period1_start: datetime, period1_end: datetime,
                             period2_start: datetime, period2_end: datetime) -> Dict:
        """Compare two time periods"""
        
        async with self.db_pool.acquire() as conn:
            # Period 1 stats
            p1 = await conn.fetchrow(
                """
                SELECT 
                    COUNT(*) as count,
                    AVG(value) as avg,
                    STDDEV(value) as std
                FROM sensor_data
                WHERE sensor_id = $1 AND metric_name = $2
                AND time >= $3 AND time < $4
                """,
                sensor_id, metric_name, period1_start, period1_end
            )
            
            # Period 2 stats
            p2 = await conn.fetchrow(
                """
                SELECT 
                    COUNT(*) as count,
                    AVG(value) as avg,
                    STDDEV(value) as std
                FROM sensor_data
                WHERE sensor_id = $1 AND metric_name = $2
                AND time >= $3 AND time < $4
                """,
                sensor_id, metric_name, period2_start, period2_end
            )
        
        # Calculate change
        avg_change = ((p2['avg'] - p1['avg']) / p1['avg'] * 100) if p1['avg'] else 0
        
        return {
            'period1': {
                'start': period1_start.isoformat(),
                'end': period1_end.isoformat(),
                'count': p1['count'],
                'average': float(p1['avg'] or 0),
                'stddev': float(p1['std'] or 0)
            },
            'period2': {
                'start': period2_start.isoformat(),
                'end': period2_end.isoformat(),
                'count': p2['count'],
                'average': float(p2['avg'] or 0),
                'stddev': float(p2['std'] or 0)
            },
            'comparison': {
                'average_change_percent': float(avg_change),
                'trend': 'increased' if avg_change > 5 else 'decreased' if avg_change < -5 else 'stable'
            }
        }
```

### 6.6 Anomaly Detection

See section 6.2 for the comprehensive AnomalyDetectionService implementation.

---

## 7. Security Implementation

### 7.1 TLS/SSL Configuration

```yaml
# mosquitto-tls.conf
# EMQ X TLS Configuration

# TLS listener for MQTT
listeners.ssl.default {
    bind = "0.0.0.0:8883"
    max_connections = 100000
    
    ssl_options {
        keyfile = "/etc/emqx/certs/server.key"
        certfile = "/etc/emqx/certs/server.crt"
        cacertfile = "/etc/emqx/certs/ca.crt"
        
        # TLS version configuration
        versions = ["tlsv1.3", "tlsv1.2"]
        
        # Cipher suites (TLS 1.3)
        ciphers = [
            "TLS_AES_256_GCM_SHA384",
            "TLS_AES_128_GCM_SHA256",
            "TLS_CHACHA20_POLY1305_SHA256"
        ]
        
        # Cipher suites (TLS 1.2)
        ciphers_tls12 = [
            "ECDHE-ECDSA-AES256-GCM-SHA384",
            "ECDHE-RSA-AES256-GCM-SHA384",
            "ECDHE-ECDSA-AES128-GCM-SHA256",
            "ECDHE-RSA-AES128-GCM-SHA256"
        ]
        
        # Security settings
        verify = verify_peer
        fail_if_no_peer_cert = true
        partial_chain = false
        reuse_sessions = true
        depth = 3
    }
}

# WebSocket TLS listener
listeners.wss.default {
    bind = "0.0.0.0:8084"
    max_connections = 50000
    
    ssl_options {
        keyfile = "/etc/emqx/certs/server.key"
        certfile = "/etc/emqx/certs/server.crt"
        cacertfile = "/etc/emqx/certs/ca.crt"
        verify = verify_peer
        fail_if_no_peer_cert = true
    }
}

# Certificate auto-refresh
certificate_refresh_interval = "1h"
```

### 7.2 Device Authentication

```python
# device_authentication.py
import hashlib
import hmac
import secrets
from typing import Dict, Optional
from datetime import datetime, timedelta
import jwt

class DeviceAuthenticator:
    def __init__(self, db_pool, jwt_secret: str):
        self.db_pool = db_pool
        self.jwt_secret = jwt_secret
        
    async def authenticate_device(self, device_id: str, 
                                  certificate_fingerprint: str) -> Dict:
        """Authenticate device using X.509 certificate"""
        
        async with self.db_pool.acquire() as conn:
            row = await conn.fetchrow(
                """
                SELECT d.device_id, d.farm_id, d.barn_id, d.device_type,
                       d.status, c.certificate_fingerprint
                FROM iot_devices d
                JOIN device_credentials c ON d.device_id = c.device_id
                WHERE d.device_id = $1 
                AND c.certificate_fingerprint = $2
                AND d.status = 'active'
                """,
                device_id, certificate_fingerprint
            )
            
            if not row:
                # Log failed authentication
                await self._log_auth_failure(device_id, certificate_fingerprint)
                return {'authenticated': False, 'error': 'Invalid credentials'}
            
            # Update last authenticated timestamp
            await conn.execute(
                """
                UPDATE device_credentials
                SET last_authenticated_at = NOW(),
                    authentication_failures = 0
                WHERE device_id = $1
                """,
                device_id
            )
            
            # Generate JWT token
            token = self._generate_device_token(row)
            
            return {
                'authenticated': True,
                'device_id': row['device_id'],
                'farm_id': row['farm_id'],
                'barn_id': row['barn_id'],
                'device_type': row['device_type'],
                'token': token
            }
    
    def _generate_device_token(self, device_info: Dict) -> str:
        """Generate JWT token for authenticated device"""
        payload = {
            'sub': device_info['device_id'],
            'farm_id': device_info['farm_id'],
            'barn_id': device_info['barn_id'],
            'device_type': device_info['device_type'],
            'iat': datetime.utcnow(),
            'exp': datetime.utcnow() + timedelta(hours=24),
            'type': 'device_token'
        }
        
        return jwt.encode(payload, self.jwt_secret, algorithm='HS256')
    
    async def verify_token(self, token: str) -> Optional[Dict]:
        """Verify JWT token"""
        try:
            payload = jwt.decode(token, self.jwt_secret, algorithms=['HS256'])
            return payload
        except jwt.ExpiredSignatureError:
            return None
        except jwt.InvalidTokenError:
            return None
    
    async def _log_auth_failure(self, device_id: str, fingerprint: str):
        """Log authentication failure"""
        async with self.db_pool.acquire() as conn:
            # Increment failure count
            await conn.execute(
                """
                UPDATE device_credentials
                SET authentication_failures = authentication_failures + 1,
                    locked_until = CASE 
                        WHEN authentication_failures >= 4 THEN NOW() + INTERVAL '15 minutes'
                        ELSE locked_until
                    END
                WHERE device_id = $1
                """,
                device_id
            )
            
            # Log to security events
            await conn.execute(
                """
                INSERT INTO security_events 
                (event_type, device_id, details, occurred_at)
                VALUES ('AUTH_FAILURE', $1, $2, NOW())
                """,
                device_id,
                {'fingerprint': fingerprint}
            )
```

### 7.3 API Key Management

```python
# api_key_manager.py
import secrets
import hashlib
from datetime import datetime, timedelta
from typing import Dict, Optional

class APIKeyManager:
    def __init__(self, db_pool):
        self.db_pool = db_pool
        
    async def create_api_key(self, user_id: str, 
                            permissions: Dict,
                            expires_days: int = 365) -> Dict:
        """Create new API key"""
        
        # Generate secure random key
        api_key = f"sdk_{secrets.token_urlsafe(32)}"
        key_hash = hashlib.sha256(api_key.encode()).hexdigest()
        
        key_id = secrets.token_hex(16)
        expires_at = datetime.utcnow() + timedelta(days=expires_days)
        
        async with self.db_pool.acquire() as conn:
            await conn.execute(
                """
                INSERT INTO api_keys 
                (key_id, key_hash, user_id, permissions, created_at, expires_at, status)
                VALUES ($1, $2, $3, $4, NOW(), $5, 'active')
                """,
                key_id, key_hash, user_id, 
                json.dumps(permissions), expires_at
            )
        
        return {
            'key_id': key_id,
            'api_key': api_key,  # Only returned once
            'permissions': permissions,
            'expires_at': expires_at.isoformat()
        }
    
    async def validate_api_key(self, api_key: str) -> Optional[Dict]:
        """Validate API key"""
        key_hash = hashlib.sha256(api_key.encode()).hexdigest()
        
        async with self.db_pool.acquire() as conn:
            row = await conn.fetchrow(
                """
                SELECT key_id, user_id, permissions, expires_at
                FROM api_keys
                WHERE key_hash = $1 AND status = 'active' AND expires_at > NOW()
                """,
                key_hash
            )
            
            if row:
                # Update last used
                await conn.execute(
                    """
                    UPDATE api_keys SET last_used_at = NOW()
                    WHERE key_id = $1
                    """,
                    row['key_id']
                )
                
                return {
                    'key_id': row['key_id'],
                    'user_id': row['user_id'],
                    'permissions': row['permissions']
                }
            
            return None
    
    async def revoke_api_key(self, key_id: str, user_id: str) -> bool:
        """Revoke an API key"""
        async with self.db_pool.acquire() as conn:
            result = await conn.execute(
                """
                UPDATE api_keys 
                SET status = 'revoked', revoked_at = NOW()
                WHERE key_id = $1 AND user_id = $2
                """,
                key_id, user_id
            )
            return 'UPDATE 1' in result
```

### 7.4 Data Encryption

```python
# data_encryption.py
from cryptography.fernet import Fernet
from cryptography.hazmat.primitives import hashes
from cryptography.hazmat.primitives.kdf.pbkdf2 import PBKDF2HMAC
import base64
import os
from typing import Union

class DataEncryption:
    def __init__(self, master_key: bytes):
        """Initialize with master encryption key"""
        self.master_key = master_key
        self.fernet = Fernet(base64.urlsafe_b64encode(master_key[:32]))
        
    def encrypt_field(self, data: Union[str, bytes]) -> str:
        """Encrypt sensitive field data"""
        if isinstance(data, str):
            data = data.encode()
        
        encrypted = self.fernet.encrypt(data)
        return base64.urlsafe_b64encode(encrypted).decode()
    
    def decrypt_field(self, encrypted_data: str) -> str:
        """Decrypt sensitive field data"""
        try:
            decoded = base64.urlsafe_b64decode(encrypted_data.encode())
            decrypted = self.fernet.decrypt(decoded)
            return decrypted.decode()
        except Exception:
            return encrypted_data  # Return as-is if not encrypted
    
    def rotate_key(self, new_key: bytes, encrypted_data: List[str]) -> List[str]:
        """Rotate encryption key and re-encrypt data"""
        new_fernet = Fernet(base64.urlsafe_b64encode(new_key[:32]))
        
        reencrypted = []
        for data in encrypted_data:
            # Decrypt with old key
            decrypted = self.decrypt_field(data)
            # Encrypt with new key
            reencrypted.append(
                base64.urlsafe_b64encode(
                    new_fernet.encrypt(decrypted.encode())
                ).decode()
            )
        
        return reencrypted
```

---

## 8. Dashboard & Visualization

### 8.1 Real-time Charts

```typescript
// RealTimeChart.tsx
import React, { useEffect, useRef, useState } from 'react';
import { LineChart, Line, XAxis, YAxis, Tooltip, ResponsiveContainer } from 'recharts';
import { useWebSocket } from './hooks/useWebSocket';

interface DataPoint {
  timestamp: number;
  value: number;
}

interface RealTimeChartProps {
  sensorId: string;
  metricName: string;
  maxDataPoints?: number;
  updateInterval?: number;
  color?: string;
  unit?: string;
}

export const RealTimeChart: React.FC<RealTimeChartProps> = ({
  sensorId,
  metricName,
  maxDataPoints = 100,
  updateInterval = 1000,
  color = '#1890ff',
  unit = ''
}) => {
  const [data, setData] = useState<DataPoint[]>([]);
  const bufferRef = useRef<DataPoint[]>([]);
  const { lastMessage } = useWebSocket(`sensor/${sensorId}/${metricName}`);

  // Buffer incoming data
  useEffect(() => {
    if (lastMessage) {
      bufferRef.current.push({
        timestamp: Date.now(),
        value: lastMessage.value
      });
    }
  }, [lastMessage]);

  // Update chart at specified interval
  useEffect(() => {
    const interval = setInterval(() => {
      if (bufferRef.current.length > 0) {
        setData(prevData => {
          const newData = [...prevData, ...bufferRef.current];
          bufferRef.current = [];
          
          // Keep only maxDataPoints
          if (newData.length > maxDataPoints) {
            return newData.slice(-maxDataPoints);
          }
          return newData;
        });
      }
    }, updateInterval);

    return () => clearInterval(interval);
  }, [updateInterval, maxDataPoints]);

  const formatTime = (timestamp: number) => {
    const date = new Date(timestamp);
    return date.toLocaleTimeString('en-US', { 
      hour12: false, 
      hour: '2-digit', 
      minute: '2-digit',
      second: '2-digit'
    });
  };

  return (
    <ResponsiveContainer width="100%" height={300}>
      <LineChart data={data}>
        <XAxis 
          dataKey="timestamp" 
          tickFormatter={formatTime}
          minTickGap={30}
        />
        <YAxis 
          domain={['auto', 'auto']}
          tickFormatter={(value) => `${value}${unit}`}
        />
        <Tooltip 
          labelFormatter={(timestamp) => formatTime(timestamp as number)}
          formatter={(value) => [`${value}${unit}`, metricName]}
        />
        <Line
          type="monotone"
          dataKey="value"
          stroke={color}
          strokeWidth={2}
          dot={false}
          isAnimationActive={false}
        />
      </LineChart>
    </ResponsiveContainer>
  );
};
```

### 8.2 Sensor Status Displays

```typescript
// SensorStatusGrid.tsx
import React from 'react';
import { Card, Badge, Statistic, Row, Col, Tooltip } from 'antd';
import { 
  CheckCircleOutlined, 
  WarningOutlined, 
  CloseCircleOutlined,
  ThunderboltOutlined,
  SignalOutlined
} from '@ant-design/icons';

interface SensorStatus {
  sensorId: string;
  sensorName: string;
  sensorType: string;
  status: 'online' | 'offline' | 'warning' | 'error';
  lastReading: number;
  lastReadingTime: Date;
  unit: string;
  batteryLevel?: number;
  signalStrength?: number;
  alerts: string[];
}

interface SensorStatusGridProps {
  sensors: SensorStatus[];
  onSensorClick?: (sensor: SensorStatus) => void;
}

export const SensorStatusGrid: React.FC<SensorStatusGridProps> = ({
  sensors,
  onSensorClick
}) => {
  const getStatusIcon = (status: string) => {
    switch (status) {
      case 'online':
        return <CheckCircleOutlined style={{ color: '#52c41a' }} />;
      case 'warning':
        return <WarningOutlined style={{ color: '#faad14' }} />;
      case 'error':
        return <CloseCircleOutlined style={{ color: '#f5222d' }} />;
      case 'offline':
        return <CloseCircleOutlined style={{ color: '#d9d9d9' }} />;
      default:
        return null;
    }
  };

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'online': return 'success';
      case 'warning': return 'warning';
      case 'error': return 'error';
      case 'offline': return 'default';
      default: return 'default';
    }
  };

  return (
    <Row gutter={[16, 16]}>
      {sensors.map(sensor => (
        <Col span={8} key={sensor.sensorId}>
          <Card
            hoverable
            onClick={() => onSensorClick?.(sensor)}
            size="small"
            className={`sensor-card sensor-card--${sensor.status}`}
          >
            <div className="sensor-header">
              <div className="sensor-title">
                <Badge status={getStatusColor(sensor.status)} />
                <span className="sensor-name">{sensor.sensorName}</span>
              </div>
              <Tooltip title={`Last seen: ${sensor.lastReadingTime.toLocaleString()}`}>
                {getStatusIcon(sensor.status)}
              </Tooltip>
            </div>

            <div className="sensor-value">
              <Statistic
                value={sensor.lastReading}
                suffix={sensor.unit}
                precision={1}
                valueStyle={{ fontSize: '24px' }}
              />
            </div>

            <div className="sensor-meta">
              {sensor.batteryLevel !== undefined && (
                <Tooltip title={`Battery: ${sensor.batteryLevel}%`}>
                  <ThunderboltOutlined 
                    style={{ 
                      color: sensor.batteryLevel < 20 ? '#f5222d' : '#52c41a'
                    }} 
                  />
                  <span className="meta-value">{sensor.batteryLevel}%</span>
                </Tooltip>
              )}
              
              {sensor.signalStrength !== undefined && (
                <Tooltip title={`Signal: ${sensor.signalStrength}%`}>
                  <SignalOutlined 
                    style={{ 
                      color: sensor.signalStrength < 30 ? '#f5222d' : '#52c41a'
                    }} 
                  />
                  <span className="meta-value">{sensor.signalStrength}%</span>
                </Tooltip>
              )}
            </div>

            {sensor.alerts.length > 0 && (
              <div className="sensor-alerts">
                {sensor.alerts.map((alert, idx) => (
                  <Badge key={idx} status="error" text={alert} />
                ))}
              </div>
            )}
          </Card>
        </Col>
      ))}
    </Row>
  );
};
```

### 8.3 Alert Notifications

```typescript
// AlertNotification.tsx
import React from 'react';
import { notification, Button } from 'antd';
import { 
  AlertOutlined, 
  CheckCircleOutlined,
  ExclamationCircleOutlined,
  InfoCircleOutlined 
} from '@ant-design/icons';
import { useEffect } from 'react';
import { useWebSocket } from './hooks/useWebSocket';

interface AlertMessage {
  alertId: string;
  priority: 'critical' | 'high' | 'medium' | 'low' | 'info';
  message: string;
  source: string;
  timestamp: string;
}

const getNotificationConfig = (alert: AlertMessage) => {
  const configs = {
    critical: {
      type: 'error' as const,
      icon: <ExclamationCircleOutlined style={{ color: '#f5222d' }} />,
      duration: 0, // Don't auto close
      style: { backgroundColor: '#fff1f0', border: '1px solid #ffa39e' }
    },
    high: {
      type: 'warning' as const,
      icon: <AlertOutlined style={{ color: '#faad14' }} />,
      duration: 10,
      style: { backgroundColor: '#fffbe6', border: '1px solid #ffe58f' }
    },
    medium: {
      type: 'warning' as const,
      icon: <InfoCircleOutlined style={{ color: '#faad14' }} />,
      duration: 8,
      style: {}
    },
    low: {
      type: 'info' as const,
      icon: <InfoCircleOutlined />,
      duration: 5,
      style: {}
    },
    info: {
      type: 'info' as const,
      icon: <InfoCircleOutlined />,
      duration: 5,
      style: {}
    }
  };

  return configs[alert.priority] || configs.info;
};

export const AlertNotificationProvider: React.FC = ({ children }) => {
  const { lastMessage } = useWebSocket('alerts/broadcast');

  useEffect(() => {
    if (lastMessage) {
      const alert: AlertMessage = lastMessage;
      const config = getNotificationConfig(alert);

      notification[config.type]({
        message: `[${alert.priority.toUpperCase()}] ${alert.source}`,
        description: alert.message,
        icon: config.icon,
        duration: config.duration,
        style: config.style,
        key: alert.alertId,
        btn: (
          <Button 
            type="primary" 
            size="small"
            onClick={() => acknowledgeAlert(alert.alertId)}
          >
            Acknowledge
          </Button>
        ),
        onClose: () => {
          // Mark as seen
        }
      });
    }
  }, [lastMessage]);

  return <>{children}</>;
};

const acknowledgeAlert = async (alertId: string) => {
  // API call to acknowledge alert
  notification.close(alertId);
};
```

### 8.4 Historical Trending

```typescript
// HistoricalTrendChart.tsx
import React, { useState, useCallback } from 'react';
import { LineChart, Line, XAxis, YAxis, Tooltip, Legend, 
         ResponsiveContainer, Brush, ReferenceLine } from 'recharts';
import { DatePicker, Select, Button, Space } from 'antd';
import { useQuery } from '@tanstack/react-query';
import dayjs from 'dayjs';

const { RangePicker } = DatePicker;
const { Option } = Select;

interface HistoricalTrendChartProps {
  sensorId: string;
  availableMetrics: string[];
}

export const HistoricalTrendChart: React.FC<HistoricalTrendChartProps> = ({
  sensorId,
  availableMetrics
}) => {
  const [dateRange, setDateRange] = useState<[dayjs.Dayjs, dayjs.Dayjs]>([
    dayjs().subtract(7, 'days'),
    dayjs()
  ]);
  const [selectedMetrics, setSelectedMetrics] = useState<string[]>([availableMetrics[0]]);
  const [aggregation, setAggregation] = useState('1h');

  const { data, isLoading } = useQuery({
    queryKey: ['historicalData', sensorId, selectedMetrics, dateRange, aggregation],
    queryFn: () => fetchHistoricalData(sensorId, selectedMetrics, dateRange, aggregation),
    refetchInterval: 60000
  });

  const colors = ['#1890ff', '#52c41a', '#faad14', '#f5222d', '#722ed1'];

  return (
    <div className="historical-trend-chart">
      <Space className="chart-controls" style={{ marginBottom: 16 }}>
        <RangePicker
          value={dateRange}
          onChange={(dates) => dates && setDateRange(dates as [dayjs.Dayjs, dayjs.Dayjs])}
          showTime
        />
        
        <Select
          mode="multiple"
          value={selectedMetrics}
          onChange={setSelectedMetrics}
          style={{ width: 200 }}
          placeholder="Select metrics"
        >
          {availableMetrics.map(metric => (
            <Option key={metric} value={metric}>{metric}</Option>
          ))}
        </Select>
        
        <Select value={aggregation} onChange={setAggregation}>
          <Option value="1m">1 Minute</Option>
          <Option value="5m">5 Minutes</Option>
          <Option value="15m">15 Minutes</Option>
          <Option value="1h">1 Hour</Option>
          <Option value="1d">1 Day</Option>
        </Select>
        
        <Button onClick={() => exportData(sensorId, selectedMetrics, dateRange)}>
          Export
        </Button>
      </Space>

      <ResponsiveContainer width="100%" height={400}>
        <LineChart data={data}>
          <XAxis 
            dataKey="timestamp" 
            tickFormatter={(ts) => dayjs(ts).format('MM-DD HH:mm')}
          />
          <YAxis />
          <Tooltip 
            labelFormatter={(ts) => dayjs(ts).format('YYYY-MM-DD HH:mm:ss')}
          />
          <Legend />
          
          {selectedMetrics.map((metric, idx) => (
            <Line
              key={metric}
              type="monotone"
              dataKey={metric}
              stroke={colors[idx % colors.length]}
              strokeWidth={2}
              dot={false}
              connectNulls
            />
          ))}
          
          <Brush dataKey="timestamp" height={30} />
        </LineChart>
      </ResponsiveContainer>
    </div>
  );
};
```

---

## 9. Testing & Validation

### 9.1 Sensor Accuracy Testing

```python
# test_sensor_accuracy.py
import pytest
import asyncio
from datetime import datetime, timedelta
import numpy as np

class TestSensorAccuracy:
    """Test suite for sensor accuracy validation"""
    
    @pytest.mark.asyncio
    async def test_temperature_sensor_accuracy(self, sensor_client, reference_thermometer):
        """Test temperature sensor against calibrated reference"""
        
        test_points = [0, 10, 20, 30, 40]  # Celsius
        errors = []
        
        for temp in test_points:
            # Set environmental chamber to target temperature
            await self.set_chamber_temperature(temp)
            await asyncio.sleep(300)  # Wait for stabilization
            
            # Read sensor and reference
            sensor_reading = await sensor_client.get_temperature()
            reference_reading = await reference_thermometer.read()
            
            error = abs(sensor_reading - reference_reading)
            errors.append(error)
            
            # Assert within specification (±0.2°C)
            assert error <= 0.2, f"Temperature error {error}°C exceeds specification at {temp}°C"
        
        # Calculate overall accuracy statistics
        max_error = max(errors)
        mean_error = np.mean(errors)
        
        print(f"Temperature sensor accuracy: max_error={max_error:.3f}°C, mean_error={mean_error:.3f}°C")
    
    @pytest.mark.asyncio
    async def test_milk_meter_volume_accuracy(self, milk_meter, calibrated_pump):
        """Test milk meter volume measurement accuracy"""
        
        test_volumes = [5.0, 10.0, 15.0, 20.0, 25.0]  # Liters
        
        for volume in test_volumes:
            # Pump known volume through meter
            await calibrated_pump.dispense(volume)
            
            # Read meter measurement
            measured = await milk_meter.get_last_session_volume()
            
            # Calculate error percentage
            error_percent = abs(measured - volume) / volume * 100
            
            # Assert within ±0.5%
            assert error_percent <= 0.5, f"Volume error {error_percent:.2f}% exceeds specification"
    
    @pytest.mark.asyncio
    async def test_rfid_read_range(self, rfid_reader, test_tags):
        """Test RFID reader detection range"""
        
        expected_range = 5.0  # meters
        
        for distance in np.arange(0.5, expected_range + 0.5, 0.5):
            success_count = 0
            attempts = 100
            
            for _ in range(attempts):
                # Position tag at distance
                await self.position_tag_at_distance(distance)
                
                # Attempt read
                read_result = await rfid_reader.read(timeout=1.0)
                if read_result:
                    success_count += 1
            
            success_rate = success_count / attempts
            
            # Should achieve >95% read rate within specified range
            if distance <= expected_range:
                assert success_rate >= 0.95, f"Read rate {success_rate:.2%} too low at {distance}m"
            
            print(f"RFID read rate at {distance}m: {success_rate:.2%}")
    
    @pytest.mark.asyncio
    async def test_activity_collar_battery_life(self, activity_collar):
        """Test activity collar battery consumption"""
        
        # Measure current consumption in different states
        active_current = await activity_collar.measure_active_current()
        sleep_current = await activity_collar.measure_sleep_current()
        transmit_current = await activity_collar.measure_transmit_current()
        
        # Calculate expected battery life
        battery_capacity = 1500  # mAh (CR123A)
        
        # Typical duty cycle: 5 min active per hour, 1 transmission per hour
        hourly_consumption = (
            (5/60) * active_current +
            (55/60) * sleep_current +
            (1) * transmit_current * 0.1  # 100ms transmission
        )
        
        expected_life_hours = battery_capacity / hourly_consumption
        expected_life_years = expected_life_hours / (24 * 365)
        
        # Should last at least 3 years
        assert expected_life_years >= 3.0, f"Battery life {expected_life_years:.1f} years below requirement"
        
        print(f"Estimated battery life: {expected_life_years:.1f} years")
```

### 9.2 Load Testing

```python
# test_load.py
import asyncio
import aiohttp
import time
from statistics import mean, stdev

class LoadTestSuite:
    """Load testing for IoT platform"""
    
    async def test_mqtt_message_throughput(self, mqtt_client, duration_seconds=60):
        """Test MQTT broker message throughput"""
        
        message_count = 0
        start_time = time.time()
        errors = []
        
        async def publish_messages():
            nonlocal message_count
            while time.time() - start_time < duration_seconds:
                try:
                    await mqtt_client.publish(
                        'test/throughput',
                        {'timestamp': time.time(), 'data': 'x' * 100},
                        qos=1
                    )
                    message_count += 1
                except Exception as e:
                    errors.append(e)
        
        # Run multiple concurrent publishers
        tasks = [publish_messages() for _ in range(100)]
        await asyncio.gather(*tasks)
        
        elapsed = time.time() - start_time
        throughput = message_count / elapsed
        
        print(f"MQTT throughput: {throughput:.0f} messages/second")
        print(f"Errors: {len(errors)}")
        
        # Should handle at least 10,000 msg/s
        assert throughput >= 10000, f"Throughput {throughput:.0f} below requirement"
    
    async def test_concurrent_connections(self, mqtt_broker_url):
        """Test maximum concurrent client connections"""
        
        clients = []
        max_connections = 100000
        
        try:
            for i in range(max_connections):
                client = await self.create_mqtt_client(mqtt_broker_url)
                await client.connect()
                clients.append(client)
                
                if i % 1000 == 0:
                    print(f"Connected {i} clients...")
            
            print(f"Successfully connected {len(clients)} clients")
            
        except Exception as e:
            print(f"Connection limit reached at {len(clients)}: {e}")
            assert len(clients) >= 50000, f"Connection limit {len(clients)} below requirement"
        
        finally:
            # Cleanup
            for client in clients:
                await client.disconnect()
    
    async def test_database_write_performance(self, db_pool, duration_seconds=60):
        """Test TimescaleDB write performance"""
        
        async def write_batch():
            async with db_pool.acquire() as conn:
                records = [
                    (datetime.utcnow(), f'SENSOR-{i}', 1, 1, 'temperature', 'temp_c', 20.5 + i*0.1)
                    for i in range(1000)
                ]
                
                await conn.copy_records_to_table(
                    'sensor_data',
                    records=records,
                    columns=['time', 'sensor_id', 'farm_id', 'barn_id', 'sensor_type', 'metric_name', 'value']
                )
        
        start_time = time.time()
        total_records = 0
        
        while time.time() - start_time < duration_seconds:
            await write_batch()
            total_records += 1000
        
        elapsed = time.time() - start_time
        write_rate = total_records / elapsed
        
        print(f"Database write rate: {write_rate:.0f} records/second")
        
        # Should handle at least 100,000 records/second
        assert write_rate >= 100000, f"Write rate {write_rate:.0f} below requirement"
    
    async def test_dashboard_api_response_time(self, base_url):
        """Test dashboard API response times under load"""
        
        async def make_request(session):
            start = time.time()
            async with session.get(f"{base_url}/api/dashboard/overview") as resp:
                await resp.json()
                return time.time() - start
        
        async with aiohttp.ClientSession() as session:
            # Warm up
            await make_request(session)
            
            # Test with concurrent requests
            latencies = []
            for _ in range(100):
                tasks = [make_request(session) for _ in range(50)]
                batch_latencies = await asyncio.gather(*tasks)
                latencies.extend(batch_latencies)
            
            p50 = sorted(latencies)[len(latencies) // 2]
            p95 = sorted(latencies)[int(len(latencies) * 0.95)]
            p99 = sorted(latencies)[int(len(latencies) * 0.99)]
            
            print(f"API latency: p50={p50*1000:.0f}ms, p95={p95*1000:.0f}ms, p99={p99*1000:.0f}ms")
            
            # p95 should be under 200ms
            assert p95 < 0.2, f"p95 latency {p95*1000:.0f}ms exceeds requirement"
```

### 9.3 Failover Testing

```python
# test_failover.py
import pytest
import asyncio
import time

class TestFailover:
    """Test system failover capabilities"""
    
    @pytest.mark.asyncio
    async def test_mqtt_broker_failover(self, mqtt_cluster):
        """Test MQTT cluster failover when node fails"""
        
        # Connect to cluster
        client = await mqtt_cluster.connect()
        
        # Subscribe to test topic
        await client.subscribe('test/failover')
        
        messages_received = []
        client.on_message = lambda msg: messages_received.append(msg)
        
        # Start publishing
        async def publish_loop():
            for i in range(1000):
                await client.publish('test/failover', {'seq': i})
                await asyncio.sleep(0.01)
        
        publish_task = asyncio.create_task(publish_loop())
        
        # Wait for some messages
        await asyncio.sleep(2)
        initial_count = len(messages_received)
        print(f"Messages before failover: {initial_count}")
        
        # Kill primary broker node
        await mqtt_cluster.kill_primary_node()
        
        # Continue publishing
        await asyncio.sleep(5)
        final_count = len(messages_received)
        print(f"Messages after failover: {final_count}")
        
        await publish_task
        
        # Verify continuity
        assert final_count > initial_count, "Message flow stopped after failover"
        
        # Check for message loss (should be minimal with QoS 1)
        loss_rate = (1000 - final_count) / 1000
        assert loss_rate < 0.01, f"Message loss {loss_rate:.2%} exceeds 1%"
    
    @pytest.mark.asyncio
    async def test_database_failover(self, timescale_cluster):
        """Test TimescaleDB failover"""
        
        # Start writing data
        async def write_loop():
            async with timescale_cluster.get_connection() as conn:
                for i in range(10000):
                    await conn.execute(
                        "INSERT INTO test_failover (time, value) VALUES (NOW(), $1)",
                        i
                    )
                    await asyncio.sleep(0.001)
        
        write_task = asyncio.create_task(write_loop())
        await asyncio.sleep(2)
        
        # Kill primary database node
        await timescale_cluster.kill_primary()
        
        # Wait for failover
        await asyncio.sleep(5)
        
        # Verify writes continue
        async with timescale_cluster.get_connection() as conn:
            row = await conn.fetchrow("SELECT COUNT(*) FROM test_failover")
            count_after_failover = row[0]
        
        print(f"Records after failover: {count_after_failover}")
        assert count_after_failover > 0, "No records written after failover"
        
        await write_task
    
    @pytest.mark.asyncio
    async def test_edge_gateway_failover(self, edge_gateway):
        """Test edge gateway store-and-forward during cloud disconnection"""
        
        # Connect gateway
        await edge_gateway.connect()
        
        # Disconnect cloud connection
        await edge_gateway.disconnect_cloud()
        
        # Generate sensor data
        local_messages = []
        for i in range(100):
            msg = {'timestamp': time.time(), 'value': i}
            await edge_gateway.publish_local(msg)
            local_messages.append(msg)
        
        # Verify local storage
        stored_count = await edge_gateway.get_local_storage_count()
        assert stored_count == 100, f"Expected 100 stored messages, got {stored_count}"
        
        # Reconnect cloud
        await edge_gateway.reconnect_cloud()
        
        # Wait for sync
        await asyncio.sleep(5)
        
        # Verify all messages forwarded
        forwarded_count = await edge_gateway.get_forwarded_count()
        assert forwarded_count == 100, f"Expected 100 forwarded messages, got {forwarded_count}"
```

### 9.4 Security Testing

```python
# test_security.py
import pytest
import asyncio
import ssl
import hashlib

class TestSecurity:
    """Security testing suite"""
    
    @pytest.mark.asyncio
    async def test_tls_connection(self, mqtt_broker):
        """Test TLS 1.3 connection"""
        
        # Attempt connection with invalid certificate
        with pytest.raises(ssl.SSLError):
            await mqtt_broker.connect(
                tls=True,
                ca_certs='/invalid/path/to/ca.crt'
            )
        
        # Attempt connection with valid certificate
        client = await mqtt_broker.connect(
            tls=True,
            ca_certs='/certs/ca.crt',
            certfile='/certs/client.crt',
            keyfile='/certs/client.key'
        )
        
        assert client.is_connected()
        await client.disconnect()
    
    @pytest.mark.asyncio
    async def test_authentication_failure_lockout(self, auth_service):
        """Test account lockout after failed attempts"""
        
        device_id = 'test-device-001'
        
        # Attempt authentication with wrong credentials 5 times
        for i in range(5):
            result = await auth_service.authenticate(device_id, 'wrong-fingerprint')
            assert not result['authenticated']
        
        # 6th attempt should be locked out
        result = await auth_service.authenticate(device_id, 'valid-fingerprint')
        assert result.get('error') == 'account_locked'
        
        # Wait for lockout period
        await asyncio.sleep(900)  # 15 minutes
        
        # Should work now
        result = await auth_service.authenticate(device_id, 'valid-fingerprint')
        assert result['authenticated']
    
    @pytest.mark.asyncio
    async def test_authorization_enforcement(self, mqtt_broker):
        """Test MQTT topic authorization"""
        
        # Connect as device with limited permissions
        client = await mqtt_broker.connect(
            username='limited-device',
            password='valid-password'
        )
        
        # Should be able to publish to allowed topic
        result = await client.publish('dairy/farm/1/device/data', {'test': 1})
        assert result.rc == 0
        
        # Should NOT be able to publish to unauthorized topic
        result = await client.publish('dairy/farm/2/device/data', {'test': 1})
        assert result.rc != 0  # Permission denied
        
        await client.disconnect()
    
    @pytest.mark.asyncio
    async def test_sql_injection_prevention(self, api_client):
        """Test SQL injection protection"""
        
        malicious_input = "'; DROP TABLE sensor_data; --"
        
        response = await api_client.get(f'/api/sensors?name={malicious_input}')
        
        # Should return error but not crash
        assert response.status != 500
        
        # Verify table still exists
        verify_response = await api_client.get('/api/sensors')
        assert verify_response.status == 200
    
    @pytest.mark.asyncio
    async def test_data_encryption_at_rest(self, db_connection):
        """Verify sensitive data is encrypted"""
        
        # Insert sensitive data
        await db_connection.execute(
            "INSERT INTO test_sensitive (device_id, api_key) VALUES ('dev1', 'secret123')"
        )
        
        # Read raw from database
        row = await db_connection.fetchrow(
            "SELECT api_key FROM test_sensitive WHERE device_id = 'dev1'"
        )
        
        # Should be encrypted, not plaintext
        assert row['api_key'] != 'secret123'
        assert row['api_key'].startswith('gAAAA')  # Fernet prefix
```

---

## 10. Deliverables

### 10.1 Hardware Installation Checklist

```markdown
# IoT Hardware Installation Checklist

## Pre-Installation
- [ ] Site survey completed
- [ ] Network infrastructure verified
- [ ] Power requirements confirmed
- [ ] Mounting locations marked
- [ ] Safety equipment available

## MQTT Broker Cluster
- [ ] Server hardware installed in rack
- [ ] Network connections configured
- [ ] Operating system installed
- [ ] EMQ X installed and configured
- [ ] SSL certificates deployed
- [ ] Cluster nodes joined
- [ ] Load balancer configured
- [ ] Monitoring agents installed

## Edge Gateways
- [ ] Gateway hardware mounted
- [ ] Power connections secured
- [ ] Network connectivity verified
- [ ] Firmware updated to latest version
- [ ] MQTT bridge configured
- [ ] Local buffering tested
- [ ] Failover mechanisms verified

## Milk Meters
- [ ] Meters installed in milking parlor
- [ ] Modbus wiring completed
- [ ] Network connectivity verified
- [ ] Calibration performed
- [ ] Integration with parlor software tested
- [ ] Data flow verified

## Environmental Sensors
- [ ] Sensors mounted in designated zones
- [ ] Sensor network connected
- [ ] Power over Ethernet configured
- [ ] Initial readings verified
- [ ] Calibration performed
- [ ] Alert thresholds configured

## Activity Collars
- [ ] Collars distributed to animals
- [ ] LoRaWAN gateways installed
- [ ] Network coverage verified
- [ ] Collars paired with system
- [ ] Battery levels checked
- [ ] Data transmission verified

## RFID Readers
- [ ] Readers mounted at entry/exit points
- [ ] Antennas positioned and tuned
- [ ] Network connections secured
- [ ] Tag read range tested
- [ ] Direction detection calibrated
- [ ] Integration with gates tested

## Post-Installation
- [ ] All devices connected to dashboard
- [ ] Data flow verified end-to-end
- [ ] Alerts tested
- [ ] Documentation updated
- [ ] Staff training completed
- [ ] Warranty information registered
```

### 10.2 Software Deployment Guide

```markdown
# IoT Software Deployment Guide

## Prerequisites
- Kubernetes cluster (v1.25+)
- Helm 3.x
- kubectl configured
- Access to container registry
- SSL certificates ready

## Step 1: Infrastructure Setup

### Deploy Namespace
```bash
kubectl create namespace smart-dairy-iot
kubectl config set-context --current --namespace=smart-dairy-iot
```

### Deploy TimescaleDB
```bash
helm repo add timescale https://charts.timescale.com
helm install timescale timescale/timescaledb-single \
  --set replicaCount=3 \
  --set persistence.size=500Gi
```

### Deploy Kafka
```bash
helm repo add bitnami https://charts.bitnami.com/bitnami
helm install kafka bitnami/kafka \
  --set replicaCount=3 \
  --set persistence.size=100Gi
```

### Deploy Redis
```bash
helm install redis bitnami/redis-cluster \
  --set cluster.nodes=6 \
  --set persistence.size=50Gi
```

## Step 2: MQTT Broker Deployment

### Create ConfigMap
```bash
kubectl create configmap emqx-config \
  --from-file=emqx.conf
```

### Deploy EMQ X Cluster
```bash
helm repo add emqx https://repos.emqx.io/charts
helm install emqx emqx/emqx \
  --set replicaCount=4 \
  --set service.type=LoadBalancer \
  --set ssl.enabled=true
```

### Verify Deployment
```bash
kubectl get pods -l app=emqx
kubectl logs -l app=emqx --tail=100
```

## Step 3: Data Processing Services

### Build and Push Images
```bash
docker build -t smart-dairy/ingestion-service:latest ./ingestion
docker push smart-dairy/ingestion-service:latest

docker build -t smart-dairy/alert-engine:latest ./alert-engine
docker push smart-dairy/alert-engine:latest
```

### Deploy Services
```bash
kubectl apply -f k8s/ingestion-service.yaml
kubectl apply -f k8s/alert-engine.yaml
kubectl apply -f k8s/aggregation-service.yaml
```

## Step 4: Database Migration

### Run Migrations
```bash
kubectl run migrate \
  --image=smart-dairy/migration:latest \
  --restart=Never \
  -- ./migrate up
```

### Verify Schema
```bash
kubectl run psql \
  --image=postgres:15 \
  --restart=Never \
  -- psql -h timescale -U postgres -d smartdairy -c "\dt"
```

## Step 5: Frontend Deployment

### Build Production Bundle
```bash
cd dashboard
npm ci
npm run build:production
```

### Deploy to CDN
```bash
aws s3 sync build/ s3://smart-dairy-dashboard/
aws cloudfront create-invalidation --distribution-id XXX --paths "/*"
```

## Step 6: Configuration

### Configure Alert Rules
```bash
kubectl apply -f config/default-alert-rules.yaml
```

### Set Up Device Profiles
```bash
python scripts/setup_device_profiles.py --farm-id 1
```

## Step 7: Verification

### Health Checks
```bash
curl https://mqtt.smartdairy.io/health
curl https://api.smartdairy.io/health
```

### End-to-End Test
```bash
python scripts/e2e_test.py --farm-id 1
```

## Rollback Procedure

If deployment fails:
```bash
helm rollback emqx
kubectl rollout undo deployment/ingestion-service
```
```

### 10.3 Acceptance Criteria

```markdown
# Milestone 34 Acceptance Criteria

## Functional Requirements

### MQTT Broker
- [ ] Cluster supports 100,000+ concurrent connections
- [ ] Message throughput exceeds 10,000 msg/s
- [ ] TLS 1.3 encryption enforced
- [ ] Authentication success rate > 99.9%
- [ ] Automatic failover completes within 30 seconds

### Milk Meter Integration
- [ ] All milk meters connected and transmitting
- [ ] Volume measurement accuracy within ±0.5%
- [ ] Data latency < 5 seconds from milking completion
- [ ] Mastitis detection alerts generated correctly
- [ ] Integration with ERP milk production module verified

### Environmental Sensors
- [ ] 100% sensor coverage in all designated zones
- [ ] Temperature accuracy within ±0.2°C
- [ ] Humidity accuracy within ±2% RH
- [ ] Alert generation within 1 minute of threshold breach
- [ ] Historical data retention for 2 years

### Activity Collars
- [ ] All animals equipped with active collars
- [ ] Heat detection accuracy > 85%
- [ ] Health alert generation within 15 minutes
- [ ] Battery life projection > 3 years
- [ ] LoRaWAN coverage > 95% of farm area

### RFID System
- [ ] All entry/exit points equipped with readers
- [ ] Tag read rate > 99% at 5m range
- [ ] Direction detection accuracy > 95%
- [ ] Integration with feeding system verified
- [ ] Animal location tracking accuracy 100%

### Dashboard
- [ ] Real-time updates within 2 seconds
- [ ] Supports 100+ concurrent users
- [ ] Page load time < 3 seconds
- [ ] Mobile-responsive design verified
- [ ] All chart types rendering correctly

### Alert System
- [ ] Alert delivery within 30 seconds
- [ ] Multi-channel notification working (SMS, Email, Push)
- [ ] Alert suppression rules functioning
- [ ] Escalation chains tested
- [ ] False positive rate < 5%

## Non-Functional Requirements

### Performance
- [ ] API p95 response time < 200ms
- [ ] Database write throughput > 100,000 records/s
- [ ] Dashboard time-to-first-byte < 500ms
- [ ] WebSocket message latency < 100ms

### Reliability
- [ ] System uptime > 99.9%
- [ ] Data loss rate < 0.01%
- [ ] Recovery time objective (RTO) < 1 hour
- [ ] Recovery point objective (RPO) < 5 minutes

### Security
- [ ] All connections use TLS 1.2 or higher
- [ ] Certificate validation enforced
- [ ] Access controls tested and verified
- [ ] Security audit passed
- [ ] Penetration test completed with no critical findings

### Scalability
- [ ] System scales to 2x current load
- [ ] Horizontal scaling tested
- [ ] Database partitioning verified
- [ ] Cache performance validated

## Documentation
- [ ] Technical documentation complete
- [ ] User manuals delivered
- [ ] API documentation published
- [ ] Runbooks created
- [ ] Training materials prepared

## Sign-off

| Role | Name | Date | Signature |
|------|------|------|-----------|
| Project Manager | | | |
| Technical Lead | | | |
| QA Lead | | | |
| Security Officer | | | |
| Client Representative | | | |
```

---

## 11. Appendices

### 11.1 MQTT Topic Structures

```
# Standard Topic Hierarchy

# Environmental Sensors
dairy/farm/{farm_id}/barn/{barn_id}/zone/{zone_id}/sensor/{sensor_id}/telemetry
dairy/farm/{farm_id}/barn/{barn_id}/zone/{zone_id}/sensor/{sensor_id}/command
dairy/farm/{farm_id}/barn/{barn_id}/zone/{zone_id}/sensor/{sensor_id}/config
dairy/farm/{farm_id}/barn/{barn_id}/zone/{zone_id}/sensor/{sensor_id}/status

# Activity Collars
dairy/farm/{farm_id}/animal/{animal_id}/activity/telemetry
dairy/farm/{farm_id}/animal/{animal_id}/activity/command
dairy/farm/{farm_id}/animal/{animal_id}/health/status
dairy/farm/{farm_id}/animal/{animal_id}/fertility/heat

# Milk Meters
dairy/farm/{farm_id}/milking/parlor/{parlor_id}/stall/{stall_id}/session/start
dairy/farm/{farm_id}/milking/parlor/{parlor_id}/stall/{stall_id}/session/data
dairy/farm/{farm_id}/milking/parlor/{parlor_id}/stall/{stall_id}/session/end
dairy/farm/{farm_id}/milking/parlor/{parlor_id}/status

# RFID Readers
dairy/farm/{farm_id}/rfid/gate/{gate_id}/tag/read
dairy/farm/{farm_id}/rfid/area/{area_id}/presence
dairy/farm/{farm_id}/rfid/animal/{animal_id}/location

# System Topics
system/farm/{farm_id}/device/{device_id}/status
dairy/farm/{farm_id}/alert/{severity}/{category}
dairy/farm/{farm_id}/config/{config_type}/{target_id}
```

### 11.2 Message Formats

```json
// Environmental Sensor Telemetry
{
  "timestamp": "2026-02-02T12:55:10Z",
  "device_id": "ENV-FARM01-BARN01-Z01",
  "farm_id": 1,
  "barn_id": 1,
  "zone_id": 1,
  "sensor_type": "environmental",
  "readings": {
    "temperature_c": 22.5,
    "humidity_percent": 65.0,
    "pressure_hpa": 1013.25,
    "co2_ppm": 800,
    "nh3_ppm": 5.2
  },
  "quality_score": 98,
  "battery_level": 87,
  "signal_strength": -65,
  "firmware_version": "2.1.0"
}

// Activity Collar Telemetry
{
  "timestamp": "2026-02-02T12:55:10Z",
  "device_id": "AC-FARM01-COW-12345",
  "animal_id": "COW-12345",
  "farm_id": 1,
  "activity_score": 45,
  "activity_index": 0.72,
  "rumination_minutes": 420,
  "eating_minutes": 180,
  "resting_minutes": 840,
  "temperature_c": 38.6,
  "position": "standing",
  "heat_index": 0.23,
  "health_alert": false,
  "fertility_status": "normal",
  "battery_level": 92
}

// Milking Session Data
{
  "timestamp": "2026-02-02T12:55:10Z",
  "session_id": "SES-20260202-001-001",
  "parlor_id": 1,
  "stall_id": 1,
  "animal_id": "COW-12345",
  "session_data": {
    "start_time": "2026-02-02T12:45:00Z",
    "end_time": "2026-02-02T12:55:10Z",
    "duration_seconds": 610
  },
  "milk_data": {
    "total_volume_liters": 28.5,
    "average_flow_lpm": 2.8,
    "peak_flow_lpm": 4.2,
    "temperature_celsius": 37.2,
    "conductivity_ms_cm": 4.5
  },
  "quality_flags": {
    "blood_detected": false,
    "conductivity_alert": false,
    "temperature_alert": false,
    "incomplete_milking": false
  },
  "quality_score": 95
}

// RFID Tag Read
{
  "timestamp": "2026-02-02T12:55:10.123Z",
  "reader_id": "RFID-GATE-01",
  "antenna_id": 1,
  "tag_epc": "E200341502001080",
  "animal_id": "COW-12345",
  "rssi": -52,
  "phase": 0.75,
  "frequency": 866300,
  "read_count": 5,
  "direction": "inbound"
}

// Alert Message
{
  "alert_id": "ALT-20260202125510-001",
  "timestamp": "2026-02-02T12:55:10Z",
  "rule_id": "RULE-TEMP-HIGH-001",
  "rule_name": "High Temperature Alert",
  "priority": "high",
  "severity": "warning",
  "source": {
    "type": "sensor",
    "id": "ENV-FARM01-BARN01-Z01",
    "name": "Barn 1 Zone 1 Temperature"
  },
  "message": "Temperature 28.5°C exceeds threshold 25°C",
  "details": {
    "current_value": 28.5,
    "threshold": 25.0,
    "duration_minutes": 15
  },
  "actions": [
    {
      "type": "notification",
      "channels": ["email", "push"]
    }
  ]
}
```

### 11.3 Configuration Examples

```yaml
# emqx.conf - EMQ X Configuration

## Node Settings
node.name = emqx@127.0.0.1
node.cookie = smart_dairy_secret_cookie
node.max_ets_tables = 500000
node.process_limit = 2048000

## Authentication
authentication.1.mechanism = password_based
authentication.1.backend = postgresql
authentication.1.query = "SELECT password_hash FROM device_credentials WHERE device_id = ${username} LIMIT 1"
authentication.1.database = smart_dairy
authentication.1.username = emqx_auth
authentication.1.password = ${POSTGRES_PASSWORD}
authentication.1.server = "postgres:5432"

## Authorization
authorization.sources.1.type = postgresql
authorization.sources.1.query = "SELECT permission, action, topic FROM device_acls WHERE device_id = ${username}"
authorization.sources.1.database = smart_dairy
authorization.sources.1.server = "postgres:5432"
authorization.no_match = deny
authorization.deny_action = ignore

## MQTT Settings
mqtt.max_packet_size = 1MB
mqtt.max_clientid_len = 65535
mqtt.max_topic_levels = 10
mqtt.max_qos_allowed = 2
mqtt.max_topic_alias = 65535
mqtt.retain_available = true
mqtt.wildcard_subscription = true
mqtt.shared_subscription = true

## Logging
log.file_handlers.default {
    level = warning
    file = "log/emqx.log"
    rotation.size = 100MB
    rotation.count = 10
}
```

```yaml
# sensor-gateway-config.yaml

gateway:
  id: "GW-FARM01-BARN01"
  location:
    farm_id: 1
    barn_id: 1
    coordinates: { lat: 52.5200, lng: 13.4050 }
  
  mqtt:
    broker: "mqtt.smartdairy.io"
    port: 8883
    client_id: "${GATEWAY_ID}"
    tls:
      enabled: true
      ca_cert: "/certs/ca.crt"
      client_cert: "/certs/gateway.crt"
      client_key: "/certs/gateway.key"
    keepalive: 60
    reconnect_interval: 5
  
  buffering:
    enabled: true
    max_size_mb: 1000
    flush_interval_seconds: 30
    retry_attempts: 3
  
  devices:
    - type: "environmental_sensor"
      protocol: "mqtt"
      scan_interval: 60
      
    - type: "milk_meter"
      protocol: "modbus"
      connection:
        host: "192.168.10.100"
        port: 502
        unit_id: 1
      registers:
        - name: "volume"
          address: 40031
          type: "float32"
          scale: 1.0
        - name: "flow_rate"
          address: 40041
          type: "float32"
          scale: 1.0
```

### 11.4 Troubleshooting Guide

```markdown
# IoT Troubleshooting Guide

## MQTT Connection Issues

### Problem: Device cannot connect to MQTT broker

**Symptoms:**
- Connection timeout errors
- SSL handshake failures
- Authentication failures

**Diagnostic Steps:**
1. Check network connectivity: `ping mqtt.smartdairy.io`
2. Verify port accessibility: `telnet mqtt.smartdairy.io 8883`
3. Check certificate validity: `openssl s_client -connect mqtt.smartdairy.io:8883`
4. Verify device credentials in database
5. Check broker logs: `kubectl logs -l app=emqx`

**Common Solutions:**
- Renew expired certificates
- Update device password in database
- Check firewall rules for port 8883
- Verify device ID matches certificate CN

## Data Flow Issues

### Problem: Sensor data not appearing in dashboard

**Diagnostic Steps:**
1. Check device is publishing: Monitor MQTT topic directly
2. Verify ingestion service is running: `kubectl get pods -l app=ingestion`
3. Check Kafka topic lag: `kafka-consumer-groups.sh --describe`
4. Query database directly: `SELECT * FROM sensor_data ORDER BY time DESC LIMIT 10`
5. Check for validation failures: `SELECT * FROM validation_failures`

**Common Solutions:**
- Restart ingestion service if stuck
- Clear Kafka consumer lag
- Fix data format issues
- Update validation rules if too strict

## Alert Issues

### Problem: Alerts not being generated or delivered

**Diagnostic Steps:**
1. Verify alert rules are enabled: `SELECT * FROM alert_rules WHERE enabled = true`
2. Check alert engine logs: `kubectl logs -l app=alert-engine`
3. Test notification channels manually
4. Verify alert suppression rules
5. Check for alert deduplication

**Common Solutions:**
- Enable disabled rules
- Update notification channel credentials
- Adjust suppression windows
- Fix rule condition syntax

## Performance Issues

### Problem: Dashboard loading slowly or timing out

**Diagnostic Steps:**
1. Check database query performance: `EXPLAIN ANALYZE` slow queries
2. Monitor API response times
3. Check Redis cache hit rate
4. Verify TimescaleDB continuous aggregates are current
5. Check for lock contention

**Common Solutions:**
- Add missing indexes
- Refresh continuous aggregates
- Increase cache TTL
- Optimize query patterns
- Scale database resources

## Device-Specific Issues

### Milk Meter: No flow reading
- Check vacuum pressure
- Verify pulsator operation
- Inspect milk line for blockages
- Check electrical connections

### Activity Collar: No data transmission
- Check battery level
- Verify LoRaWAN signal strength
- Check collar is properly attached
- Verify gateway is operational

### Environmental Sensor: Erratic readings
- Check sensor calibration
- Verify sensor location (avoid direct sunlight/drafts)
- Check for electromagnetic interference
- Replace sensor if faulty

### RFID Reader: Missed tag reads
- Adjust antenna positioning
- Check for metal interference
- Verify tag orientation
- Increase RF power if within limits
```

---

## Document Information

**Document Owner:** Smart Dairy Technical Team  
**Review Cycle:** Quarterly  
**Distribution:** Internal Use Only  
**Classification:** Confidential

**Related Documents:**
- Phase 3: IoT Foundation Architecture
- Phase 4: Integration & Intelligence Layer
- Phase 5: Analytics & Machine Learning

**Document History:**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-02 | Smart Dairy Technical Team | Initial comprehensive release |

---

*End of Milestone 34 - IoT Integration & Smart Sensors Document*
