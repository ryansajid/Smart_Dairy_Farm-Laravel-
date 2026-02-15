# I-009: IoT Data Processing Pipeline

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | I-009 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Data Engineer |
| **Owner** | IoT Architect |
| **Reviewer** | Solution Architect |
| **Classification** | Internal |
| **Status** | Approved |

## Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | 2026-01-31 | Data Engineer | Initial document creation |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Data Ingestion](#2-data-ingestion)
3. [Data Validation](#3-data-validation)
4. [Data Transformation](#4-data-transformation)
5. [Stream Processing](#5-stream-processing)
6. [Time-Series Storage](#6-time-series-storage)
7. [Data Quality](#7-data-quality)
8. [Real-time Analytics](#8-real-time-analytics)
9. [Batch Processing](#9-batch-processing)
10. [Data Archiving](#10-data-archiving)
11. [Backpressure Handling](#11-backpressure-handling)
12. [Monitoring & Alerting](#12-monitoring--alerting)
13. [Scalability](#13-scalability)
14. [Troubleshooting](#14-troubleshooting)
15. [Appendices](#15-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the IoT Data Processing Pipeline architecture for Smart Dairy Ltd., detailing the end-to-end flow of sensor data from collection to consumption.

### 1.2 Scope

The pipeline processes:
- **Milk Production Data**: Volume, quality metrics, flow rates
- **Environmental Data**: Temperature, humidity, NH3 levels, ventilation
- **Animal Health Data**: Activity levels, rumination patterns, body temperature
- **RFID Events**: Animal location tracking, feeding behavior, milking sessions

### 1.3 Architecture Overview

```
+-----------------------------------------------------------------------------+
|                        IoT Data Processing Pipeline                          |
+-----------------------------------------------------------------------------+
|                                                                              |
|  +----------+    +----------+    +----------+    +----------+    +---------+|
|  |  MQTT    |--->|  Kafka   |--->|  Flink   |--->|Timescale |--->|Dashboard||
|  | Brokers  |    | Topics   |    | Jobs     |    |   DB     |    |  /API   ||
|  +----------+    +----------+    +----------+    +----------+    +---------+|
|       |               |               |               |                     |
|       v               v               v               v                     |
|  +----------+    +----------+    +----------+    +----------+              |
|  |Sensors & |    |  Schema  |    |  Real-   |    |  Data    |              |
|  |  Gateways|    |Registry  |    |time Agg  |    |Retention |              |
|  +----------+    +----------+    +----------+    +----------+              |
|                                                                              |
|  Data Flow: MQTT -> Kafka -> Flink -> TimescaleDB -> Dashboard/API          |
|                                                                              |
+-----------------------------------------------------------------------------+
```

### 1.4 Key Metrics

| Metric | Target | Description |
|--------|--------|-------------|
| Throughput | 100,000 msg/sec | Peak message processing rate |
| Latency (p99) | < 500ms | End-to-end processing latency |
| Availability | 99.95% | Pipeline uptime SLA |
| Data Retention | 2 years | Hot storage retention |
| Recovery Time | < 5 min | Maximum recovery time |

---

## 2. Data Ingestion

### 2.1 MQTT Broker Configuration

```yaml
# mosquitto.conf
listener 1883
protocol mqtt

listener 8883
protocol mqtt
ssl_cafile /etc/mosquitto/ca_certificates/ca.crt
ssl_certfile /etc/mosquitto/certs/server.crt
ssl_keyfile /etc/mosquitto/certs/server.key

# Authentication
allow_anonymous false
password_file /etc/mosquitto/passwd
acl_file /etc/mosquitto/acl

# Persistence
persistence true
persistence_location /var/lib/mosquitto/
autosave_interval 300

# Performance
tcp_nodelay true
max_inflight_messages 100
max_keepalive 300
retry_interval 20
sys_interval 10
```

### 2.2 Kafka Configuration

```bash
# Create Kafka topics for Smart Dairy

# Raw sensor data topics
kafka-topics.sh --create \
  --topic iot.raw.environmental \
  --partitions 12 \
  --replication-factor 3 \
  --config retention.ms=86400000 \
  --config compression.type=lz4

kafka-topics.sh --create \
  --topic iot.raw.milk-production \
  --partitions 8 \
  --replication-factor 3 \
  --config retention.ms=86400000 \
  --config compression.type=lz4

kafka-topics.sh --create \
  --topic iot.raw.animal-health \
  --partitions 16 \
  --replication-factor 3 \
  --config retention.ms=86400000 \
  --config compression.type=lz4

kafka-topics.sh --create \
  --topic iot.raw.rfid-events \
  --partitions 6 \
  --replication-factor 3 \
  --config retention.ms=86400000 \
  --config compression.type=lz4

# Processed data topics
kafka-topics.sh --create \
  --topic iot.processed.metrics \
  --partitions 12 \
  --replication-factor 3 \
  --config retention.ms=604800000 \
  --config compression.type=lz4

# Dead letter queue
kafka-topics.sh --create \
  --topic iot.dlq.errors \
  --partitions 4 \
  --replication-factor 3 \
  --config retention.ms=604800000
```

### 2.3 MQTT to Kafka Bridge (Python)

```python
# mqtt_kafka_bridge.py
import asyncio
import json
import logging
from typing import Optional
import aiokafka
from aiokafka import AIOKafkaProducer
import aiomqtt
from dataclasses import dataclass
from datetime import datetime

@dataclass
class SensorReading:
    farm_id: str
    zone_id: str
    sensor_id: str
    sensor_type: str
    timestamp: datetime
    value: float
    unit: str

class MqttKafkaBridge:
    def __init__(self, config: dict):
        self.config = config
        self.producer: Optional[AIOKafkaProducer] = None
        self.mqtt_client: Optional[aiomqtt.Client] = None
        self.logger = logging.getLogger(__name__)
        self.buffer = []
        self.buffer_size = config.get('buffer_size', 1000)
        
    async def start(self):
        self.producer = AIOKafkaProducer(
            bootstrap_servers=self.config['kafka_brokers'],
            client_id='mqtt-bridge-producer',
            acks='all',
            retries=10,
            enable_idempotence=True,
            compression_type='lz4',
            value_serializer=lambda v: json.dumps(v).encode('utf-8'),
            key_serializer=lambda k: k.encode('utf-8') if k else None
        )
        await self.producer.start()
        
        self.mqtt_client = aiomqtt.Client(
            hostname=self.config['mqtt_host'],
            port=self.config['mqtt_port'],
            username=self.config['mqtt_username'],
            password=self.config['mqtt_password'],
            keepalive=60
        )
    
    async def run(self):
        while True:
            try:
                async with self.mqtt_client as client:
                    await client.subscribe("smart-dairy/farm/+/sensors/#", qos=1)
                    await client.subscribe("smart-dairy/farm/+/rfid/#", qos=1)
                    
                    async for message in client.messages:
                        await self._process_message(message)
            except Exception as e:
                self.logger.error(f"Connection error: {e}")
                await asyncio.sleep(5)
    
    async def _process_message(self, message: aiomqtt.Message):
        try:
            topic = str(message.topic)
            payload = json.loads(message.payload.decode('utf-8'))
            
            enriched = self._enrich_payload(payload, topic)
            kafka_topic = self._route_topic(topic)
            key = self._generate_partition_key(enriched)
            
            await self.producer.send(
                kafka_topic,
                key=key,
                value=enriched
            )
        except Exception as e:
            self.logger.error(f"Processing error: {e}")
    
    def _enrich_payload(self, payload: dict, topic: str) -> dict:
        return {
            'raw_data': payload,
            'metadata': {
                'source_topic': topic,
                'ingestion_timestamp': datetime.utcnow().isoformat(),
                'schema_version': '1.0'
            }
        }
    
    def _route_topic(self, mqtt_topic: str) -> str:
        if 'environmental' in mqtt_topic:
            return 'iot.raw.environmental'
        elif 'milking' in mqtt_topic:
            return 'iot.raw.milk-production'
        elif 'health' in mqtt_topic:
            return 'iot.raw.animal-health'
        else:
            return 'iot.raw.unknown'
    
    def _generate_partition_key(self, payload: dict) -> str:
        farm_id = payload.get('farm_id', 'unknown')
        sensor_id = payload.get('raw_data', {}).get('sensor_id', 'unknown')
        return f"{farm_id}:{sensor_id}"
```

---

## 3. Data Validation

### 3.1 Schema Validation

```python
# validation_rules.py
from typing import Dict, Any, List, Callable
import re
from dataclasses import dataclass

@dataclass
class ValidationResult:
    is_valid: bool
    errors: List[str]
    warnings: List[str]

class DataValidator:
    RANGES = {
        'temperature': {'min': -40.0, 'max': 60.0, 'unit': 'celsius'},
        'humidity': {'min': 0.0, 'max': 100.0, 'unit': 'percent'},
        'nh3': {'min': 0.0, 'max': 100.0, 'unit': 'ppm'},
        'activity_level': {'min': 0, 'max': 1000, 'unit': 'steps'},
        'rumination': {'min': 0, 'max': 1440, 'unit': 'minutes'},
        'body_temp': {'min': 35.0, 'max': 42.0, 'unit': 'celsius'},
        'milk_volume': {'min': 0.0, 'max': 50000.0, 'unit': 'ml'}
    }
    
    def validate_environmental(self, data: Dict) -> ValidationResult:
        errors = []
        warnings = []
        
        metric_type = data.get('metric_type', '').lower()
        value = data.get('value')
        
        range_config = self.RANGES.get(metric_type)
        if range_config:
            if value < range_config['min'] or value > range_config['max']:
                errors.append(f"Value {value} outside valid range")
            
            if metric_type == 'temperature' and (value < -10 or value > 50):
                warnings.append(f"Extreme temperature: {value}")
        
        return ValidationResult(len(errors) == 0, errors, warnings)
    
    def validate_milk_production(self, data: Dict) -> ValidationResult:
        errors = []
        warnings = []
        
        volume = data.get('volume_ml', 0)
        if volume < 0:
            errors.append("Milk volume cannot be negative")
        elif volume > 30000:
            warnings.append(f"Unusually high volume: {volume}ml")
        
        scc = data.get('somatic_cell_count')
        if scc and scc > 200000:
            warnings.append(f"High SCC: {scc} - potential mastitis")
        
        return ValidationResult(len(errors) == 0, errors, warnings)
    
    def validate_animal_health(self, data: Dict) -> ValidationResult:
        errors = []
        warnings = []
        
        temp = data.get('body_temperature')
        if temp and temp > 39.5:
            warnings.append(f"Elevated temperature: {temp}C - potential fever")
        
        rumination = data.get('rumination_minutes')
        if rumination and rumination < 300:
            warnings.append(f"Low rumination: {rumination}min - health issue")
        
        return ValidationResult(len(errors) == 0, errors, warnings)
```

### 3.2 Validation Rules Summary

| Data Type | Rules | Error Handling |
|-----------|-------|----------------|
| Environmental | Range check, timestamp validation | Log warning, store with quality flag |
| Milk Production | Volume range, SCC threshold | Alert if quality threshold exceeded |
| Animal Health | Temperature range, activity bounds | Immediate alert for critical values |
| RFID Events | Tag format, timestamp sequence | Queue for manual review if invalid |

---

## 4. Data Transformation

### 4.1 Unit Normalization

```python
# data_transformation.py
from typing import Dict

class DataTransformer:
    UNIT_CONVERSIONS = {
        ('fahrenheit', 'celsius'): lambda x: (x - 32) * 5/9,
        ('celsius', 'fahrenheit'): lambda x: (x * 9/5) + 32,
        ('gallons', 'milliliters'): lambda x: x * 3785.41,
        ('liters', 'milliliters'): lambda x: x * 1000,
        ('pounds', 'kilograms'): lambda x: x * 0.453592
    }
    
    def normalize_unit(self, value: float, from_unit: str, to_unit: str) -> float:
        if from_unit == to_unit:
            return value
        
        conversion = self.UNIT_CONVERSIONS.get((from_unit.lower(), to_unit.lower()))
        if conversion:
            return round(conversion(value), 4)
        raise ValueError(f"No conversion: {from_unit} to {to_unit}")
    
    def transform_environmental(self, data: Dict) -> Dict:
        transformed = data.copy()
        
        if 'temperature' in data:
            original_unit = data.get('unit', 'celsius').lower()
            if original_unit != 'celsius':
                transformed['temperature_celsius'] = self.normalize_unit(
                    data['temperature'], original_unit, 'celsius'
                )
            else:
                transformed['temperature_celsius'] = data['temperature']
        
        # Calculate dew point
        if 'temperature_celsius' in transformed and 'humidity_percent' in transformed:
            temp = transformed['temperature_celsius']
            humidity = transformed['humidity_percent']
            transformed['dew_point_celsius'] = temp - ((100 - humidity) / 5)
        
        return transformed
```

### 4.2 Data Enrichment

```python
# data_enrichment.py
import redis
from typing import Dict, Optional

class DataEnrichmentService:
    def __init__(self, redis_client: redis.Redis):
        self.redis = redis_client
    
    async def enrich_milk_production(self, data: Dict) -> Dict:
        cow_id = data.get('cow_id')
        
        # Get cow metadata from cache
        cow_info = await self._get_cow_info(cow_id)
        if cow_info:
            data['cow_breed'] = cow_info.get('breed')
            data['cow_lactation'] = cow_info.get('lactation_number')
            data['cow_dim'] = cow_info.get('days_in_milk')
        
        # Calculate quality score
        if all(k in data for k in ['fat_content', 'protein_content']):
            data['quality_score'] = self._calculate_quality(data)
        
        return data
    
    async def _get_cow_info(self, cow_id: str) -> Optional[Dict]:
        cache_key = f"cow:{cow_id}"
        cached = self.redis.get(cache_key)
        if cached:
            import json
            return json.loads(cached)
        return None
    
    def _calculate_quality(self, data: Dict) -> float:
        score = 100
        fat = data.get('fat_content', 4.0)
        protein = data.get('protein_content', 3.2)
        scc = data.get('somatic_cell_count', 100000)
        
        if fat < 3.0 or fat > 5.5:
            score -= 15
        if protein < 2.8 or protein > 3.8:
            score -= 10
        if scc > 200000:
            score -= 15
        
        return max(0, score)
```

---

## 5. Stream Processing

### 5.1 Apache Flink Configuration

```yaml
# flink-conf.yaml
jobmanager.memory.process.size: 4096m
taskmanager.memory.process.size: 8192m
taskmanager.numberOfTaskSlots: 4
parallelism.default: 12

state.backend: rocksdb
state.backend.incremental: true
checkpoints.dir: s3://smart-dairy-flink-checkpoints/

execution.checkpointing.mode: EXACTLY_ONCE
execution.checkpointing.interval: 60s
execution.checkpointing.min-pause-between-checkpoints: 30s

restart-strategy: exponential-delay
restart-strategy.exponential-delay.initial-backoff: 1s
restart-strategy.exponential-delay.max-backoff: 300s

metrics.reporter.prom.factory.class: org.apache.flink.metrics.prometheus.PrometheusReporterFactory
metrics.reporter.prom.port: 9249
```

### 5.2 Flink Job (Java)

```java
// IoTDataProcessingJob.java
package io.smartdairy.flink;

import org.apache.flink.api.common.eventtime.WatermarkStrategy;
import org.apache.flink.connector.kafka.sink.KafkaSink;
import org.apache.flink.connector.kafka.source.KafkaSource;
import org.apache.flink.connector.kafka.source.enumerator.initializer.OffsetsInitializer;
import org.apache.flink.streaming.api.datastream.*;
import org.apache.flink.streaming.api.environment.*;
import org.apache.flink.streaming.api.windowing.assigners.*;
import org.apache.flink.streaming.api.windowing.time.Time;

import java.time.Duration;

public class IoTDataProcessingJob {
    
    public static void main(String[] args) throws Exception {
        StreamExecutionEnvironment env = StreamExecutionEnvironment.getExecutionEnvironment();
        
        env.enableCheckpointing(60000);
        env.getCheckpointConfig().setCheckpointingMode(CheckpointingMode.EXACTLY_ONCE);
        env.setParallelism(12);
        
        // Kafka sources
        KafkaSource<String> envSource = KafkaSource.<String>builder()
            .setBootstrapServers("kafka-1:9092,kafka-2:9092,kafka-3:9092")
            .setTopics("iot.raw.environmental")
            .setGroupId("flink-environmental")
            .setStartingOffsets(OffsetsInitializer.earliest())
            .setValueOnlyDeserializer(new SimpleStringSchema())
            .build();
        
        // Create streams with watermarks
        DataStream<String> envStream = env.fromSource(
            envSource,
            WatermarkStrategy.<String>forBoundedOutOfOrderness(Duration.ofSeconds(30)),
            "Environmental Source"
        );
        
        // Parse and aggregate
        envStream
            .map(new JsonParsingFunction())
            .keyBy(SensorReading::getZoneId)
            .window(TumblingEventTimeWindows.of(Time.minutes(5)))
            .aggregate(new EnvironmentalAggregator())
            .map(Object::toString)
            .sinkTo(KafkaSink.<String>builder()
                .setBootstrapServers("kafka-1:9092")
                .setRecordSerializer(...)
                .build());
        
        env.execute("Smart Dairy IoT Processing");
    }
}
```

### 5.3 PyFlink Implementation

```python
# iot_processing.py
from pyflink.datastream import StreamExecutionEnvironment
from pyflink.datastream.window import TumblingProcessingTimeWindows, Time
from pyflink.datastream.connectors.kafka import KafkaSource, KafkaSink
from pyflink.common.watermark_strategy import WatermarkStrategy
from datetime import timedelta

def create_job():
    env = StreamExecutionEnvironment.get_execution_environment()
    env.enable_checkpointing(60000)
    env.set_parallelism(12)
    
    # Source
    source = KafkaSource.builder() \
        .set_bootstrap_servers("kafka-1:9092,kafka-2:9092,kafka-3:9092") \
        .set_topics("iot.raw.environmental") \
        .set_group_id("pyflink-processor") \
        .build()
    
    stream = env.from_source(
        source,
        WatermarkStrategy.for_bounded_out_of_orderness(timedelta(seconds=30)),
        "Kafka Source"
    )
    
    # Process
    aggregated = stream \
        .map(lambda x: json.loads(x)) \
        .key_by(lambda x: x['zone_id']) \
        .window(TumblingProcessingTimeWindows.of(Time.minutes(5))) \
        .aggregate(lambda values: {
            'zone_id': values[0]['zone_id'],
            'avg': sum(v['value'] for v in values) / len(values),
            'min': min(v['value'] for v in values),
            'max': max(v['value'] for v in values),
            'count': len(values)
        })
    
    env.execute("Smart Dairy Processing")
```

---

## 6. Time-Series Storage

### 6.1 TimescaleDB Schema

```sql
-- Enable TimescaleDB
CREATE EXTENSION IF NOT EXISTS timescaledb;

-- Environmental readings
CREATE TABLE environmental_readings (
    time TIMESTAMPTZ NOT NULL,
    sensor_id VARCHAR(32) NOT NULL,
    farm_id VARCHAR(16) NOT NULL,
    zone_id VARCHAR(16) NOT NULL,
    metric_type VARCHAR(16) NOT NULL,
    value DOUBLE PRECISION NOT NULL,
    unit VARCHAR(8) NOT NULL,
    quality_score FLOAT,
    metadata JSONB
);

SELECT create_hypertable('environmental_readings', 'time', 
    chunk_time_interval => INTERVAL '1 day');

CREATE INDEX idx_env_farm_zone ON environmental_readings (farm_id, zone_id, time DESC);
CREATE INDEX idx_env_metric ON environmental_readings (metric_type, time DESC);

-- Milk production
CREATE TABLE milk_production (
    time TIMESTAMPTZ NOT NULL,
    milking_session_id UUID NOT NULL,
    cow_id VARCHAR(16) NOT NULL,
    farm_id VARCHAR(16) NOT NULL,
    volume_ml DOUBLE PRECISION NOT NULL,
    fat_content_percent DOUBLE PRECISION,
    protein_content_percent DOUBLE PRECISION,
    somatic_cell_count INTEGER,
    quality_score FLOAT
);

SELECT create_hypertable('milk_production', 'time',
    chunk_time_interval => INTERVAL '7 days');

CREATE INDEX idx_milk_cow ON milk_production (cow_id, time DESC);

-- Animal health
CREATE TABLE animal_health (
    time TIMESTAMPTZ NOT NULL,
    collar_id VARCHAR(16) NOT NULL,
    cow_id VARCHAR(16) NOT NULL,
    farm_id VARCHAR(16) NOT NULL,
    activity_index DOUBLE PRECISION,
    rumination_minutes INTEGER,
    body_temperature_celsius DOUBLE PRECISION,
    health_score FLOAT
);

SELECT create_hypertable('animal_health', 'time',
    chunk_time_interval => INTERVAL '7 days');

CREATE INDEX idx_health_cow ON animal_health (cow_id, time DESC);

-- Retention policies
SELECT add_retention_policy('environmental_readings', INTERVAL '2 years');
SELECT add_retention_policy('milk_production', INTERVAL '5 years');
SELECT add_retention_policy('animal_health', INTERVAL '3 years');

-- Compression
ALTER TABLE environmental_readings SET (
    timescaledb.compress,
    timescaledb.compress_segmentby = 'sensor_id,metric_type'
);
SELECT add_compression_policy('environmental_readings', INTERVAL '7 days');
```

### 6.2 Common Queries

```sql
-- Latest readings per zone
SELECT DISTINCT ON (zone_id, metric_type)
    zone_id, metric_type, time, value
FROM environmental_readings
WHERE farm_id = 'FARM-001'
ORDER BY zone_id, metric_type, time DESC;

-- 5-minute averages
SELECT 
    time_bucket('5 minutes', time) AS bucket,
    zone_id,
    avg(value) as avg_value,
    min(value) as min_value,
    max(value) as max_value
FROM environmental_readings
WHERE time > now() - INTERVAL '1 hour'
GROUP BY bucket, zone_id
ORDER BY bucket DESC;

-- Daily milk summary
SELECT 
    time_bucket('1 day', time) AS day,
    cow_id,
    sum(volume_ml) as total_volume,
    avg(fat_content_percent) as avg_fat
FROM milk_production
WHERE time > now() - INTERVAL '30 days'
GROUP BY day, cow_id
ORDER BY day DESC;
```

---

## 7. Data Quality

### 7.1 Duplicate Detection

```python
# duplicate_detection.py
import hashlib
import redis

class DuplicateDetector:
    def __init__(self, redis_client: redis.Redis, window_seconds: int = 300):
        self.redis = redis_client
        self.window = window_seconds
    
    def generate_key(self, data: dict) -> str:
        key_string = f"{data.get('sensor_id')}:{data.get('timestamp')}:{data.get('value')}"
        return hashlib.sha256(key_string.encode()).hexdigest()[:16]
    
    def is_duplicate(self, data: dict) -> bool:
        dedup_key = f"dedup:{self.generate_key(data)}"
        result = self.redis.set(dedup_key, "1", nx=True, ex=self.window)
        return result is None
```

### 7.2 Gap Filling

```python
# gap_filling.py
import numpy as np
from typing import List, Dict

class GapFiller:
    def linear_interpolation(self, data: List[Dict]) -> List[Dict]:
        if len(data) < 2:
            return data
        
        result = []
        for i in range(len(data) - 1):
            result.append(data[i])
            
            gap = (data[i+1]['timestamp'] - data[i]['timestamp']) / 1000
            if gap > 120:  # Gap > 2 minutes
                steps = int(gap / 60)
                for j in range(1, steps):
                    ratio = j / steps
                    interp_value = data[i]['value'] + (data[i+1]['value'] - data[i]['value']) * ratio
                    interp_time = data[i]['timestamp'] + (j * 60000)
                    result.append({
                        'timestamp': int(interp_time),
                        'value': interp_value,
                        'interpolated': True
                    })
        
        result.append(data[-1])
        return result
```

---

## 8. Real-time Analytics

### 8.1 Moving Averages and Trend Detection

```python
# analytics.py
import numpy as np
from collections import deque
from typing import List, Dict

class MovingAverageCalculator:
    def simple_ma(self, values: List[float], window: int) -> List[float]:
        result = []
        for i in range(len(values)):
            if i < window - 1:
                result.append(np.mean(values[:i+1]))
            else:
                result.append(np.mean(values[i-window+1:i+1]))
        return result
    
    def exponential_ma(self, values: List[float], alpha: float = 0.3) -> List[float]:
        result = [values[0]]
        for i in range(1, len(values)):
            ema = alpha * values[i] + (1 - alpha) * result[-1]
            result.append(ema)
        return result

class TrendDetector:
    def detect_trend(self, timestamps: List[int], values: List[float]) -> Dict:
        if len(values) < 10:
            return {'trend': 'INSUFFICIENT', 'slope': 0}
        
        x = np.array([(t - timestamps[0]) / 3600000 for t in timestamps])
        y = np.array(values)
        
        slope, intercept = np.polyfit(x, y, 1)
        
        trend = 'STABLE'
        if slope > 0.05:
            trend = 'UP'
        elif slope < -0.05:
            trend = 'DOWN'
        
        return {'trend': trend, 'slope': slope}
```

### 8.2 Anomaly Detection

```python
# anomaly_detection.py
from collections import deque
import numpy as np

class AnomalyDetector:
    def __init__(self, window_size: int = 100):
        self.window = deque(maxlen=window_size)
    
    def update(self, value: float) -> Dict:
        self.window.append(value)
        
        if len(self.window) < 10:
            return {'is_anomaly': False, 'z_score': 0}
        
        mean = np.mean(self.window)
        std = np.std(self.window)
        
        if std == 0:
            return {'is_anomaly': False, 'z_score': 0}
        
        z_score = abs(value - mean) / std
        
        severity = 'NORMAL'
        if z_score > 4:
            severity = 'CRITICAL'
        elif z_score > 3:
            severity = 'HIGH'
        elif z_score > 2:
            severity = 'MEDIUM'
        
        return {
            'is_anomaly': z_score > 3,
            'z_score': round(z_score, 2),
            'severity': severity,
            'mean': round(mean, 2),
            'std': round(std, 2)
        }
```

---

## 9. Batch Processing

### 9.1 Daily Summaries

```python
# batch_processing.py
from pyspark.sql import SparkSession
from pyspark.sql.functions import *

def create_daily_summaries():
    spark = SparkSession.builder \
        .appName("SmartDairy-DailySummaries") \
        .getOrCreate()
    
    # Read from TimescaleDB
    df = spark.read \
        .format("jdbc") \
        .option("url", "jdbc:postgresql://timescale:5432/smartdairy") \
        .option("dbtable", "milk_production") \
        .option("user", "smartdairy") \
        .load()
    
    # Daily aggregation
    daily = df.groupBy(
        date_trunc('day', col('time')).alias('date'),
        col('cow_id')
    ).agg(
        sum('volume_ml').alias('total_volume'),
        avg('fat_content_percent').alias('avg_fat'),
        avg('protein_content_percent').alias('avg_protein'),
        count('*').alias('milking_count')
    )
    
    # Write to summary table
    daily.write \
        .mode("overwrite") \
        .jdbc("jdbc:postgresql://timescale:5432/smartdairy", 
              "daily_milk_summary", properties={"user": "smartdairy"})
```

### 9.2 ML Training Pipeline

```python
# ml_training.py
import pandas as pd
from sklearn.ensemble import IsolationForest
import joblib

def train_anomaly_models():
    # Load historical data
    df = pd.read_sql("""
        SELECT cow_id, activity_index, rumination_minutes, body_temperature_celsius
        FROM animal_health
        WHERE time > now() - INTERVAL '90 days'
    """, conn)
    
    # Train per cow
    for cow_id in df['cow_id'].unique():
        cow_data = df[df['cow_id'] == cow_id][['activity_index', 'rumination_minutes', 'body_temperature_celsius']].dropna()
        
        if len(cow_data) > 100:
            model = IsolationForest(contamination=0.05, random_state=42)
            model.fit(cow_data)
            
            joblib.dump(model, f"models/anomaly_cow_{cow_id}.joblib")
```

---

## 10. Data Archiving

### 10.1 Cold Storage Configuration

```yaml
# archiving-config.yaml
archiving:
  cold_storage:
    type: s3
    bucket: smart-dairy-archive
    region: ap-southeast-1
    storage_class: GLACIER_IR  # Instant retrieval
  
  compression:
    format: parquet
    codec: zstd
    row_group_size: 100000
  
  schedules:
    environmental:
      older_than: 2 years
      archive_after: 1 year
    
    milk_production:
      older_than: 5 years
      archive_after: 2 years
    
    animal_health:
      older_than: 3 years
      archive_after: 1 year
```

### 10.2 Archive Job

```python
# archive_job.py
import boto3
import pandas as pd
from datetime import datetime, timedelta

def archive_old_data(table: str, archive_date: datetime):
    # Query old data
    query = f"""
        SELECT * FROM {table}
        WHERE time < '{archive_date.isoformat()}'
        AND time >= '{(archive_date - timedelta(days=30)).isoformat()}'
    """
    
    df = pd.read_sql(query, conn)
    
    # Compress and upload
    s3 = boto3.client('s3')
    
    filename = f"{table}/{archive_date.strftime('%Y/%m')}/{table}_{archive_date.strftime('%Y%m%d')}.parquet"
    
    df.to_parquet(
        f"/tmp/{filename}",
        compression='zstd',
        index=False
    )
    
    s3.upload_file(
        f"/tmp/{filename}",
        'smart-dairy-archive',
        filename
    )
    
    # Delete archived data
    cursor.execute(f"DELETE FROM {table} WHERE time < '{archive_date.isoformat()}'")
```

---

## 11. Backpressure Handling

### 11.1 Queue Management

```python
# backpressure_handler.py
import asyncio
from asyncio import Queue

class BackpressureHandler:
    def __init__(self, max_queue_size: int = 10000, rate_limit: int = 1000):
        self.queue = Queue(maxsize=max_queue_size)
        self.rate_limit = rate_limit
        self.tokens = rate_limit
        self.last_update = asyncio.get_event_loop().time()
    
    async def add_message(self, message):
        try:
            self.queue.put_nowait(message)
        except asyncio.QueueFull:
            # Drop oldest message
            try:
                self.queue.get_nowait()
                self.queue.put_nowait(message)
            except asyncio.QueueEmpty:
                pass
    
    async def get_message(self):
        # Token bucket rate limiting
        now = asyncio.get_event_loop().time()
        elapsed = now - self.last_update
        self.tokens = min(self.rate_limit, self.tokens + elapsed * self.rate_limit)
        self.last_update = now
        
        if self.tokens < 1:
            await asyncio.sleep((1 - self.tokens) / self.rate_limit)
            self.tokens = 0
        else:
            self.tokens -= 1
        
        return await self.queue.get()
```

### 11.2 Rate Limiting Configuration

```yaml
# rate-limiting.yaml
rate_limiting:
  mqtt:
    messages_per_second: 10000
    burst_size: 50000
  
  kafka:
    producer_rate: 100000
    consumer_rate: 50000
  
  flink:
    backpressure_threshold: 0.8
    autoscaling:
      enabled: true
      min_parallelism: 4
      max_parallelism: 32
      scale_up_threshold: 0.7
      scale_down_threshold: 0.3
```

---

## 12. Monitoring & Alerting

### 12.1 Pipeline Health Metrics

```python
# monitoring.py
from prometheus_client import Counter, Histogram, Gauge

# Metrics
messages_received = Counter('iot_messages_received_total', 'Total messages', ['topic'])
messages_processed = Counter('iot_messages_processed_total', 'Total processed', ['stage'])
processing_latency = Histogram('iot_processing_latency_seconds', 'Processing latency', ['stage'])
queue_depth = Gauge('iot_queue_depth', 'Current queue depth', ['queue_name'])
error_count = Counter('iot_errors_total', 'Total errors', ['error_type'])

class PipelineMonitor:
    def record_received(self, topic: str):
        messages_received.labels(topic=topic).inc()
    
    def record_processed(self, stage: str, latency: float):
        messages_processed.labels(stage=stage).inc()
        processing_latency.labels(stage=stage).observe(latency)
    
    def update_queue_depth(self, queue_name: str, depth: int):
        queue_depth.labels(queue_name=queue_name).set(depth)
    
    def record_error(self, error_type: str):
        error_count.labels(error_type=error_type).inc()
```

### 12.2 Health Check Queries

```sql
-- Lag monitoring
SELECT 
    consumer_group,
    topic,
    partition,
    committed_offset,
    log_end_offset,
    (log_end_offset - committed_offset) as lag
FROM kafka_consumer_groups;

-- Pipeline throughput
SELECT 
    time_bucket('1 minute', time) as minute,
    count(*) as messages_per_minute
FROM environmental_readings
WHERE time > now() - INTERVAL '1 hour'
GROUP BY minute
ORDER BY minute DESC;

-- Error rate
SELECT 
    error_type,
    count(*) as error_count,
    date_trunc('hour', time) as hour
FROM pipeline_errors
WHERE time > now() - INTERVAL '24 hours'
GROUP BY error_type, hour
ORDER BY hour DESC, error_count DESC;
```

---

## 13. Scalability

### 13.1 Horizontal Scaling Strategy

| Component | Scaling Method | Trigger |
|-----------|---------------|---------|
| MQTT Broker | Cluster mode (3+ nodes) | Connection count |
| Kafka | Partition expansion | Throughput |
| Flink | Parallelism increase | Backpressure |
| TimescaleDB | Read replicas | Query load |

### 13.2 Partitioning Strategy

```python
# partitioning.py
def get_partition_key(data: dict) -> str:
    """Generate partition key for data distribution"""
    farm_id = data.get('farm_id', 'unknown')
    
    # For environmental: partition by zone
    if 'zone_id' in data:
        return f"{farm_id}:{data['zone_id']}"
    
    # For animal data: partition by cow
    if 'cow_id' in data:
        return data['cow_id']
    
    return farm_id

def calculate_partition_count(topic: str, throughput: int) -> int:
    """Calculate optimal partition count"""
    base_partitions = {
        'iot.raw.environmental': 12,
        'iot.raw.milk-production': 8,
        'iot.raw.animal-health': 16,
        'iot.raw.rfid-events': 6
    }
    
    # Scale partitions based on throughput
    base = base_partitions.get(topic, 6)
    if throughput > 50000:
        return base * 2
    if throughput > 100000:
        return base * 4
    
    return base
```

---

## 14. Troubleshooting

### 14.1 Common Issues and Resolution

| Issue | Symptoms | Root Cause | Resolution |
|-------|----------|------------|------------|
| High Lag | Consumer lag increasing | Slow processing | Scale Flink parallelism |
| Data Loss | Missing records | Buffer overflow | Increase buffer size |
| Duplicates | Duplicate alerts | Network retry | Implement idempotency |
| Schema Errors | Parse failures | Schema mismatch | Update schema registry |
| OOM | Container restarts | Memory leak | Check state backend config |

### 14.2 Data Recovery Procedures

```python
# recovery.py
class DataRecovery:
    def replay_from_kafka(self, topic: str, start_offset: int, end_offset: int):
        """Replay messages from Kafka for recovery"""
        consumer = KafkaConsumer(
            topic,
            bootstrap_servers=['kafka:9092'],
            auto_offset_reset='earliest'
        )
        
        consumer.seek_to_offset(start_offset)
        
        for message in consumer:
            if message.offset > end_offset:
                break
            
            # Re-process message
            self.reprocess_message(message.value)
    
    def restore_from_archive(self, table: str, date: datetime):
        """Restore data from cold storage"""
        s3 = boto3.client('s3')
        
        prefix = f"{table}/{date.strftime('%Y/%m')}/"
        
        objects = s3.list_objects_v2(
            Bucket='smart-dairy-archive',
            Prefix=prefix
        )
        
        for obj in objects.get('Contents', []):
            s3.download_file(
                'smart-dairy-archive',
                obj['Key'],
                f"/tmp/{obj['Key']}"
            )
            
            df = pd.read_parquet(f"/tmp/{obj['Key']}")
            df.to_sql(table, conn, if_exists='append', index=False)
```

---

## 15. Appendices

### Appendix A: Complete Flink Job Code

```java
// CompleteFlinkJob.java
// See Section 5.2 for full implementation
```

### Appendix B: Kafka Topic Configuration

```bash
#!/bin/bash
# create-topics.sh

TOPICS=(
  "iot.raw.environmental:12:3"
  "iot.raw.milk-production:8:3"
  "iot.raw.animal-health:16:3"
  "iot.raw.rfid-events:6:3"
  "iot.processed.metrics:12:3"
  "iot.alerts.detected:4:3"
  "iot.dlq.errors:4:3"
)

for topic_config in "${TOPICS[@]}"; do
  IFS=':' read -r topic partitions replicas <<< "$topic_config"
  
  kafka-topics.sh \
    --bootstrap-server kafka:9092 \
    --create \
    --topic "$topic" \
    --partitions "$partitions" \
    --replication-factor "$replicas" \
    --config retention.ms=86400000 \
    --config compression.type=lz4 \
    --config cleanup.policy=delete
done
```

### Appendix C: TimescaleDB Complete Schema

```sql
-- See Section 6.1 for complete schema definition
```

### Appendix D: Configuration Files

```yaml
# application.yaml
spring:
  kafka:
    bootstrap-servers: kafka-1:9092,kafka-2:9092,kafka-3:9092
    producer:
      acks: all
      retries: 10
      compression-type: lz4
    consumer:
      group-id: smart-dairy-processors
      auto-offset-reset: earliest
      enable-auto-commit: false
  
  datasource:
    url: jdbc:postgresql://timescale:5432/smartdairy
    username: smartdairy
    hikari:
      maximum-pool-size: 20
      connection-timeout: 30000
  
  flink:
    checkpointing:
      enabled: true
      interval: 60s
      mode: exactly_once
    parallelism:
      default: 12
```

### Appendix E: Deployment Scripts

```bash
#!/bin/bash
# deploy-pipeline.sh

set -e

echo "Deploying Smart Dairy IoT Pipeline..."

# Deploy Kafka topics
kubectl apply -f k8s/kafka-topics.yaml

# Deploy Flink job
flink run -d \
  -p 12 \
  -c io.smartdairy.flink.IoTDataProcessingJob \
  target/smart-dairy-flink-job.jar

# Deploy MQTT bridge
kubectl apply -f k8s/mqtt-bridge-deployment.yaml

# Verify deployment
kubectl get pods -n smart-dairy
echo "Deployment complete!"
```

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Data Engineer | _________________ | 2026-01-31 |
| Owner | IoT Architect | _________________ | 2026-01-31 |
| Reviewer | Solution Architect | _________________ | 2026-01-31 |

---

*Document ID: I-009 | Version: 1.0 | Page 1 of 1*
