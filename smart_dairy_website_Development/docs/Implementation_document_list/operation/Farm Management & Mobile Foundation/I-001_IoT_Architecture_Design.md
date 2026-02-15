# SMART DAIRY LTD.
## IOT ARCHITECTURE DESIGN
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | I-001 |
| **Version** | 1.0 |
| **Date** | February 28, 2026 |
| **Author** | IoT Architect |
| **Owner** | IoT Architect |
| **Reviewer** | Solution Architect |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [IoT Architecture Overview](#2-iot-architecture-overview)
3. [Sensor Types and Specifications](#3-sensor-types-and-specifications)
4. [Communication Protocols](#4-communication-protocols)
5. [MQTT Broker Architecture](#5-mqtt-broker-architecture)
6. [Data Flow and Processing](#6-data-flow-and-processing)
7. [IoT Gateway Design](#7-iot-gateway-design)
8. [Data Storage Strategy](#8-data-storage-strategy)
9. [Real-time Analytics](#9-real-time-analytics)
10. [Alerting System](#10-alerting-system)
11. [Security Architecture](#11-security-architecture)
12. [Device Management](#12-device-management)
13. [Implementation Roadmap](#13-implementation-roadmap)
14. [Appendices](#14-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines the comprehensive IoT architecture for Smart Dairy's smart farming operations. It covers the design of sensor networks, data communication, processing pipelines, and integration with the ERP system to enable precision dairy farming.

### 1.2 IoT Vision

Enable real-time monitoring and automation of:
- **Milk Production** - Automated milk metering and quality analysis
- **Animal Health** - Continuous health monitoring via wearable sensors
- **Environmental Control** - Barn climate monitoring and control
- **Feed Management** - Automated feeding systems integration
- **Cold Chain** - Temperature monitoring from milking to delivery

### 1.3 Scope

| Component | Description |
|-----------|-------------|
| **Edge Devices** | Sensors, meters, RFID readers at farm locations |
| **Connectivity** | MQTT, LoRaWAN, WiFi, cellular networks |
| **Data Platform** - | Ingestion, processing, storage of IoT data |
| **Integration** | ERP system connectivity via APIs |
| **Analytics** | Real-time dashboards and predictive analytics |

### 1.4 Scale Projections

| Metric | Current | Phase 1 (Year 1) | Phase 2 (Year 2) |
|--------|---------|------------------|------------------|
| Lactating Cows | 75 | 150 | 250 |
| Milk Meters | 0 | 16 (parlor) | 32 (parlor) |
| Environmental Sensors | 0 | 20 | 40 |
| Wearable Sensors | 0 | 50 | 100 |
| Data Points/Day | 0 | 500,000 | 2,000,000 |

---

## 2. IOT ARCHITECTURE OVERVIEW

### 2.1 High-Level Architecture

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                              SMART DAIRY IOT PLATFORM                            │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │                         PRESENTATION LAYER                                   ││
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    ││
│  │  │ Farm         │  │ Mobile       │  │ ERP          │  │ Alert        │    ││
│  │  │ Dashboard    │  │ App          │  │ Integration  │  │ Console      │    ││
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘    ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                    ▲                                             │
│                                    │                                             │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │                         APPLICATION LAYER                                    ││
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    ││
│  │  │ Stream       │  │ Analytics    │  │ Rules        │  │ API          │    ││
│  │  │ Processor    │  │ Engine       │  │ Engine       │  │ Gateway      │    ││
│  │  │ (Flink)      │  │ (Python)     │  │ (Drools)     │  │ (FastAPI)    │    ││
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘    ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                    ▲                                             │
│                                    │                                             │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │                         MESSAGE BROKER LAYER                                 ││
│  │  ┌─────────────────────────────────────────────────────────────────────┐    ││
│  │  │                    MQTT BROKER CLUSTER (EMQX)                        │    ││
│  │  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │    ││
│  │  │  │ MQTT over    │  │ WebSocket    │  │ MQTT over    │              │    ││
│  │  │  │ TLS          │  │              │  │ WebSocket    │              │    ││
│  │  │  └──────────────┘  └──────────────┘  └──────────────┘              │    ││
│  │  └─────────────────────────────────────────────────────────────────────┘    ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                    ▲                                             │
│                                    │                                             │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │                         EDGE/GATEWAY LAYER                                   ││
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    ││
│  │  │ Farm Gateway │  │ LoRaWAN      │  │ WiFi Access  │  │ 4G/5G        │    ││
│  │  │ (Raspberry   │  │ Gateway      │  │ Points       │  │ Router       │    ││
│  │  │  Pi/Intel)   │  │              │  │              │  │              │    ││
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘    ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                    ▲                                             │
│                                    │                                             │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │                         DEVICE LAYER                                         ││
│  │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐          ││
│  │  │Milk Meter│ │ Smart    │ │ Environ  │ │ RFID     │ │ Weighing │          ││
│  │  │          │ │ Collar   │ │ Sensor   │ │ Reader   │ │ Scale    │          ││
│  │  └──────────┘ └──────────┘ └──────────┘ └──────────┘ └──────────┘          ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                                                                  │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Technology Stack

| Layer | Technology | Purpose |
|-------|------------|---------|
| **MQTT Broker** | EMQX 5.x | Message broker cluster |
| **Stream Processing** | Apache Flink | Real-time data processing |
| **Time-Series DB** | TimescaleDB | Sensor data storage |
| **Cache** | Redis | Hot data, pub/sub |
| **API Gateway** | FastAPI | REST API for ERP |
| **Analytics** | Python + Pandas | Data analysis |
| **Visualization** | Grafana | Dashboards |
| **Rules Engine** | Drools | Business rules |

---

## 3. SENSOR TYPES AND SPECIFICATIONS

### 3.1 Milk Production Sensors

#### 3.1.1 Milk Meter

| Specification | Value |
|--------------|-------|
| **Type** | Electronic milk meter (volumetric) |
| **Accuracy** | ±2% |
| **Capacity** | 0-50 liters per milking |
| **Communication** | RS-485 / Modbus, WiFi, or LoRaWAN |
| **Power** | 12V DC or battery |
| **Data Points** | Volume, flow rate, milking duration |
| **Examples** | DeLaval MM27, Afimilk AfimilkMeter |

#### 3.1.2 Milk Quality Analyzer

| Specification | Value |
|--------------|-------|
| **Type** | Inline optical sensor |
| **Parameters** | Fat %, Protein %, Lactose %, SNF %, SCC |
| **Accuracy** | Fat ±0.1%, Protein ±0.1% |
| **Communication** | Ethernet, Modbus TCP |
| **Integration** | Real-time during milking |
| **Examples** | DeLaval Herd Navigator, LactoScope |

### 3.2 Animal Wearables

#### 3.2.1 Smart Collar / Ear Tag

| Specification | Value |
|--------------|-------|
| **Type** | Activity monitor + temperature |
| **Sensors** | Accelerometer, temperature, rumination |
| **Communication** | LoRaWAN / Bluetooth 5.0 |
| **Battery Life** | 2-5 years |
| **Data Points** | Activity level, heat detection, rumination time, body temperature |
| **Examples** | Afimilk Silent Herdsman, CowManager |

### 3.3 Environmental Sensors

| Sensor Type | Parameters | Range | Accuracy |
|------------|------------|-------|----------|
| **Temperature** | Air temperature | -10°C to 50°C | ±0.5°C |
| **Humidity** | Relative humidity | 0-100% RH | ±3% |
| **Ammonia (NH3)** | Gas concentration | 0-100 ppm | ±5% |
| **CO2** | Carbon dioxide | 0-5000 ppm | ±50 ppm |
| **Light** | Illuminance | 0-100,000 lux | ±10% |

### 3.4 RFID System

| Specification | Value |
|--------------|-------|
| **Type** | Passive UHF RFID |
| **Frequency** | 860-960 MHz |
| **Read Range** | 1-5 meters |
| **Tags** | ISO 18000-6C compliant |
| **Communication** | Ethernet/WiFi to gateway |
| **Use Case** | Animal identification at milking parlor, gates |

---

## 4. COMMUNICATION PROTOCOLS

### 4.1 Protocol Selection Matrix

| Device Type | Primary Protocol | Backup | Range | Use Case |
|-------------|-----------------|--------|-------|----------|
| Milk Meters | MQTT over WiFi | Ethernet | 100m | Milking parlor |
| Wearables | LoRaWAN | BLE | 2-15km | Field/pasture |
| Environmental | MQTT over WiFi | LoRaWAN | 100m | Barn/shed |
| RFID Readers | MQTT over Ethernet | WiFi | 50m | Parlor/gates |

### 4.2 MQTT Topic Structure

```
smartdairy/
├── farm/
│   ├── {barn_id}/
│   │   ├── milking_parlor/
│   │   │   ├── milk_meter/{device_id}
│   │   │   │   └── data
│   │   │   ├── quality_analyzer/{device_id}
│   │   │   │   └── data
│   │   │   └── rfid_reader/{device_id}
│   │   │       └── scan
│   │   ├── environmental/
│   │   │   ├── temperature/{sensor_id}
│   │   │   ├── humidity/{sensor_id}
│   │   │   ├── ammonia/{sensor_id}
│   │   │   └── co2/{sensor_id}
│   │   └── feeding_station/{station_id}
│   │       └── consumption
│   └── gates/
│       └── rfid_reader/{reader_id}/scan
├── animal/
│   ├── {rfid_tag}/
│   │   ├── activity
│   │   ├── rumination
│   │   ├── temperature
│   │   └── location
│   └── herd/
│       └── location_update
├── cold_storage/
│   ├── {unit_id}/
│   │   ├── temperature
│   │   └── humidity
│   └── alerts
└── system/
    ├── health
    └── config
```

### 4.3 MQTT Message Format

```json
{
  "schema_version": "1.0",
  "device_id": "milk_meter_001",
  "device_type": "milk_meter",
  "timestamp": "2026-02-28T08:30:15.123Z",
  "location": {
    "barn_id": "barn_a",
    "stall_id": "stall_03"
  },
  "data": {
    "animal_rfid": "E200341502001080",
    "session": "morning",
    "volume_liters": 18.5,
    "flow_rate": 2.5,
    "duration_seconds": 450,
    "milk_temperature": 37.2
  },
  "metadata": {
    "firmware_version": "2.1.0",
    "signal_strength": -65,
    "battery_level": 87
  }
}
```

---

## 5. MQTT BROKER ARCHITECTURE

### 5.1 EMQX Cluster Configuration

```yaml
# EMQX Docker Compose Configuration
version: '3.8'

services:
  emqx:
    image: emqx/emqx:5.4.0
    container_name: emqx
    ports:
      - "1883:1883"     # MQTT
      - "8883:8883"     # MQTT over TLS
      - "8083:8083"     # MQTT over WebSocket
      - "8084:8084"     # MQTT over WSS
      - "18083:18083"   # Dashboard
    environment:
      - EMQX_NAME=emqx
      - EMQX_HOST=127.0.0.1
      - EMQX_CLUSTER__DISCOVERY_STRATEGY=static
      - EMQX_CLUSTER__STATIC__SEEDS=emqx@127.0.0.1
      - EMQX_LOAD_MODULES=emqx_mod_acl_internal,emqx_mod_presence
    volumes:
      - emqx-data:/opt/emqx/data
      - emqx-etc:/opt/emqx/etc
      - ./emqx.conf:/opt/emqx/etc/emqx.conf:ro
    networks:
      - iot-network

volumes:
  emqx-data:
  emqx-etc:

networks:
  iot-network:
    driver: bridge
```

### 5.2 EMQX Configuration

```hcl
# emqx.conf
## Authentication
authentication = [
  {
    mechanism = password_based
    backend = postgresql
    password_hash_algorithm {
      name = sha256
      salt_position = suffix
    }
    database = smart_dairy
    username = emqx_auth
    password = "${EMQX_DB_PASSWORD}"
    server = "postgres:5432"
    query = "SELECT password_hash FROM iot.device_credentials WHERE device_id = ${username} AND enabled = true"
  }
]

## Authorization
authorization {
  sources = [
    {
      type = postgresql
      database = smart_dairy
      username = emqx_auth
      password = "${EMQX_DB_PASSWORD}"
      server = "postgres:5432"
      query = "SELECT permission, action, topic FROM iot.device_acl WHERE device_id = ${username}"
    }
  ]
  no_match = deny
  deny_action = disconnect
}

## Listeners
listeners.tcp.default {
  bind = "0.0.0.0:1883"
  max_connections = 10000
}

listeners.ssl.default {
  bind = "0.0.0.0:8883"
  ssl_options {
    certfile = "/opt/emqx/etc/certs/server.crt"
    keyfile = "/opt/emqx/etc/certs/server.key"
    cacertfile = "/opt/emqx/etc/certs/ca.crt"
  }
}

## WebHook for data ingestion
webhook {
  url = "http://iot-processor:8080/api/v1/iot/webhook"
  headers {
    "Authorization" = "Bearer ${WEBHOOK_TOKEN}"
  }
}
```

---

## 6. DATA FLOW AND PROCESSING

### 6.1 Data Pipeline Architecture

```
┌──────────────┐    ┌──────────────┐    ┌──────────────┐    ┌──────────────┐
│   Sensors    │───▶│ MQTT Broker  │───▶│   Stream     │───▶│   Storage    │
│   (Edge)     │    │   (EMQX)     │    │  Processor   │    │              │
└──────────────┘    └──────────────┘    └──────┬───────┘    └──────────────┘
                                               │
                          ┌────────────────────┼────────────────────┐
                          ▼                    ▼                    ▼
                   ┌──────────────┐    ┌──────────────┐    ┌──────────────┐
                   │   Rules      │    │  Real-time   │    │    ERP       │
                   │   Engine     │    │  Dashboard   │    │  Integration │
                   └──────────────┘    └──────────────┘    └──────────────┘
```

### 6.2 Stream Processing with Apache Flink

```python
# iot_stream_processor.py
from pyflink.datastream import StreamExecutionEnvironment
from pyflink.table import StreamTableEnvironment, EnvironmentSettings
from pyflink.datastream.connectors.kafka import FlinkKafkaConsumer, FlinkKafkaProducer
import json

def process_iot_stream():
    env = StreamExecutionEnvironment.get_execution_environment()
    settings = EnvironmentSettings.new_instance().in_streaming_mode().build()
    t_env = StreamTableEnvironment.create(env, settings)
    
    # Configure Kafka source
    kafka_props = {
        'bootstrap.servers': 'kafka:9092',
        'group.id': 'iot-processor',
    }
    
    # Define source
    source = FlinkKafkaConsumer(
        topics='smartdairy.raw.sensor.data',
        deserialization_schema=json_deserializer,
        properties=kafka_props
    )
    
    # Read stream
    stream = env.add_source(source)
    
    # Process milk production data
    milk_production = stream \
        .filter(lambda x: x['device_type'] == 'milk_meter') \
        .map(parse_milk_meter_data) \
        .key_by(lambda x: x['animal_rfid']) \
        .window(TumblingProcessingTimeWindows.of(Time.minutes(5))) \
        .aggregate(AverageAggregate())
    
    # Process environmental data
    environmental = stream \
        .filter(lambda x: x['device_type'] == 'environmental_sensor') \
        .map(parse_environmental_data) \
        .key_by(lambda x: x['barn_id']) \
        .window(SlidingProcessingTimeWindows.of(Time.minutes(5), Time.minutes(1))) \
        .aggregate(EnvironmentalStats())
    
    # Sink to TimescaleDB
    milk_production.addSink(JdbcSink.sink(
        """INSERT INTO iot.milk_production_realtime 
           (animal_rfid, timestamp, volume, barn_id) 
           VALUES (?, ?, ?, ?)""",
        jdbc_connector_options,
        execution_options
    ))
    
    env.execute("Smart Dairy IoT Stream Processor")

# Data parsing functions
def parse_milk_meter_data(raw_data):
    return {
        'device_id': raw_data['device_id'],
        'animal_rfid': raw_data['data']['animal_rfid'],
        'timestamp': raw_data['timestamp'],
        'volume': raw_data['data']['volume_liters'],
        'barn_id': raw_data['location']['barn_id'],
        'session': raw_data['data']['session']
    }

def parse_environmental_data(raw_data):
    return {
        'device_id': raw_data['device_id'],
        'barn_id': raw_data['location']['barn_id'],
        'timestamp': raw_data['timestamp'],
        'temperature': raw_data['data'].get('temperature'),
        'humidity': raw_data['data'].get('humidity'),
        'ammonia': raw_data['data'].get('ammonia_ppm')
    }
```

### 6.3 Data Ingestion Service (Python/FastAPI)

```python
# iot_ingestion_service.py
from fastapi import FastAPI, HTTPException, Depends
from pydantic import BaseModel
from datetime import datetime
import asyncpg
import redis

app = FastAPI(title="Smart Dairy IoT Ingestion Service")

# Database connection pool
pool = None
redis_client = redis.Redis(host='redis', port=6379, db=0)

class SensorReading(BaseModel):
    device_id: str
    device_type: str
    timestamp: datetime
    data: dict
    location: dict
    metadata: dict = {}

@app.on_event("startup")
async def startup():
    global pool
    pool = await asyncpg.create_pool(
        host='postgres',
        database='smart_dairy',
        user='iot_service',
        password='${DB_PASSWORD}'
    )

@app.post("/api/v1/iot/ingest")
async def ingest_reading(reading: SensorReading):
    """Ingest single sensor reading"""
    try:
        # Validate device
        device_valid = await validate_device(reading.device_id)
        if not device_valid:
            raise HTTPException(status_code=401, detail="Invalid device")
        
        # Store raw data
        await store_raw_reading(reading)
        
        # Process based on device type
        if reading.device_type == 'milk_meter':
            await process_milk_meter_data(reading)
        elif reading.device_type == 'environmental_sensor':
            await process_environmental_data(reading)
        elif reading.device_type == 'activity_tracker':
            await process_activity_data(reading)
        
        # Cache for real-time queries
        cache_key = f"iot:{reading.device_type}:{reading.device_id}:latest"
        redis_client.setex(cache_key, 300, reading.json())
        
        return {"status": "success", "id": reading.device_id}
    
    except Exception as e:
        # Log to error queue for retry
        await log_ingestion_error(reading, str(e))
        raise HTTPException(status_code=500, detail=str(e))

async def process_milk_meter_data(reading: SensorReading):
    """Process milk meter reading and update ERP"""
    async with pool.acquire() as conn:
        # Get animal ID from RFID
        animal = await conn.fetchrow(
            "SELECT id FROM farm.animal WHERE rfid_tag = $1",
            reading.data['animal_rfid']
        )
        
        if animal:
            # Insert into milk production
            await conn.execute("""
                INSERT INTO farm.milk_production 
                (animal_id, production_date, session, quantity_liters, 
                 device_id, recording_method, created_at)
                VALUES ($1, $2, $3, $4, $5, 'iot_device', NOW())
                ON CONFLICT (animal_id, production_date, session) 
                DO UPDATE SET quantity_liters = EXCLUDED.quantity_liters
            """, animal['id'], reading.timestamp.date(), 
                 reading.data['session'], reading.data['volume_liters'],
                 reading.device_id)
            
            # Update animal's daily average
            await update_animal_milk_average(conn, animal['id'])

async def process_environmental_data(reading: SensorReading):
    """Process environmental sensor data"""
    async with pool.acquire() as conn:
        await conn.execute("""
            INSERT INTO iot.environmental_readings 
            (barn_id, timestamp, temperature, humidity, ammonia_ppm, device_id)
            VALUES ($1, $2, $3, $4, $5, $6)
        """, reading.location['barn_id'], reading.timestamp,
             reading.data.get('temperature'),
             reading.data.get('humidity'),
             reading.data.get('ammonia_ppm'),
             reading.device_id)
        
        # Check alert thresholds
        await check_environmental_alerts(conn, reading)

async def process_activity_data(reading: SensorReading):
    """Process animal activity/wearable data"""
    async with pool.acquire() as conn:
        # Store activity reading
        await conn.execute("""
            INSERT INTO iot.animal_activity 
            (animal_rfid, timestamp, activity_level, rumination_minutes, body_temp)
            VALUES ($1, $2, $3, $4, $5)
        """, reading.data['animal_rfid'], reading.timestamp,
             reading.data.get('activity_level'),
             reading.data.get('rumination_minutes'),
             reading.data.get('body_temperature'))
        
        # Check for heat detection
        if reading.data.get('activity_level', 0) > 80:  # High activity threshold
            await flag_potential_heat(conn, reading.data['animal_rfid'])
```

---

## 7. IOT GATEWAY DESIGN

### 7.1 Farm Gateway Architecture

```python
# farm_gateway.py
import paho.mqtt.client as mqtt
import serial
import json
import time
from datetime import datetime

class FarmGateway:
    """Local gateway for farm IoT devices"""
    
    def __init__(self, config):
        self.config = config
        self.mqtt_client = mqtt.Client()
        self.mqtt_client.on_connect = self.on_mqtt_connect
        self.mqtt_client.on_message = self.on_mqtt_message
        
        # Device connections
        self.serial_connections = {}
        self.device_handlers = {
            'milk_meter': self.handle_milk_meter,
            'rfid_reader': self.handle_rfid_reader,
            'environmental': self.handle_environmental,
        }
    
    def start(self):
        """Start the gateway"""
        # Connect to cloud MQTT broker
        self.mqtt_client.tls_set(
            ca_certs=self.config['mqtt']['ca_cert'],
            certfile=self.config['mqtt']['client_cert'],
            keyfile=self.config['mqtt']['client_key']
        )
        self.mqtt_client.connect(
            self.config['mqtt']['host'],
            self.config['mqtt']['port']
        )
        self.mqtt_client.loop_start()
        
        # Connect to local serial devices
        self.connect_serial_devices()
        
        # Start reading loops
        while True:
            self.read_serial_devices()
            time.sleep(0.1)
    
    def connect_serial_devices(self):
        """Connect to serial devices (milk meters, etc.)"""
        for device in self.config['serial_devices']:
            try:
                conn = serial.Serial(
                    port=device['port'],
                    baudrate=device['baudrate'],
                    timeout=device.get('timeout', 1)
                )
                self.serial_connections[device['id']] = {
                    'connection': conn,
                    'config': device
                }
                print(f"Connected to {device['id']} on {device['port']}")
            except Exception as e:
                print(f"Failed to connect to {device['id']}: {e}")
    
    def read_serial_devices(self):
        """Read data from serial devices"""
        for device_id, device_info in self.serial_connections.items():
            conn = device_info['connection']
            if conn.in_waiting > 0:
                data = conn.readline().decode().strip()
                self.process_device_data(device_id, data)
    
    def process_device_data(self, device_id, raw_data):
        """Process raw device data and publish to MQTT"""
        device_config = self.serial_connections[device_id]['config']
        device_type = device_config['type']
        
        if device_type in self.device_handlers:
            handler = self.device_handlers[device_type]
            message = handler(device_id, raw_data, device_config)
            
            if message:
                topic = f"smartdairy/farm/{self.config['farm_id']}/{device_type}/{device_id}/data"
                self.mqtt_client.publish(topic, json.dumps(message), qos=1)
    
    def handle_milk_meter(self, device_id, raw_data, config):
        """Parse milk meter data (Modbus/ASCII format)"""
        try:
            # Example parsing for DeLaval format
            parts = raw_data.split(',')
            return {
                'schema_version': '1.0',
                'device_id': device_id,
                'device_type': 'milk_meter',
                'timestamp': datetime.utcnow().isoformat(),
                'location': {
                    'barn_id': config['barn_id'],
                    'stall_id': config['stall_id']
                },
                'data': {
                    'animal_rfid': parts[0],
                    'volume_liters': float(parts[1]),
                    'flow_rate': float(parts[2]),
                    'duration_seconds': int(parts[3]),
                    'session': self.get_current_session()
                },
                'metadata': {
                    'firmware_version': config.get('firmware', 'unknown')
                }
            }
        except Exception as e:
            print(f"Error parsing milk meter data: {e}")
            return None
    
    def handle_rfid_reader(self, device_id, raw_data, config):
        """Handle RFID scan events"""
        return {
            'schema_version': '1.0',
            'device_id': device_id,
            'device_type': 'rfid_reader',
            'timestamp': datetime.utcnow().isoformat(),
            'location': {
                'barn_id': config['barn_id'],
                'reader_location': config['location']
            },
            'data': {
                'rfid_tag': raw_data.strip(),
                'signal_strength': -65  # Placeholder
            }
        }
    
    def get_current_session(self):
        """Determine milking session based on time"""
        hour = datetime.now().hour
        if 4 <= hour < 10:
            return 'morning'
        elif 14 <= hour < 20:
            return 'evening'
        else:
            return 'night'
```

---

## 8. DATA STORAGE STRATEGY

### 8.1 TimescaleDB Schema

```sql
-- Enable TimescaleDB extension
CREATE EXTENSION IF NOT EXISTS timescaledb;

-- Raw sensor readings (high volume)
CREATE TABLE iot.sensor_readings (
    time TIMESTAMPTZ NOT NULL,
    device_id TEXT NOT NULL,
    device_type TEXT NOT NULL,
    location_id TEXT,
    reading_type TEXT,
    value DOUBLE PRECISION,
    unit TEXT,
    raw_data JSONB,
    processed BOOLEAN DEFAULT FALSE
);

SELECT create_hypertable('iot.sensor_readings', 'time', chunk_time_interval => INTERVAL '1 day');

-- Milk production real-time data
CREATE TABLE iot.milk_production_realtime (
    time TIMESTAMPTZ NOT NULL,
    animal_id BIGINT REFERENCES farm.animal(id),
    animal_rfid TEXT,
    barn_id TEXT,
    session TEXT,
    volume_liters DOUBLE PRECISION,
    fat_percent DOUBLE PRECISION,
    snf_percent DOUBLE PRECISION,
    device_id TEXT
);

SELECT create_hypertable('iot.milk_production_realtime', 'time', chunk_time_interval => INTERVAL '1 day');

-- Environmental readings
CREATE TABLE iot.environmental_readings (
    time TIMESTAMPTZ NOT NULL,
    barn_id TEXT NOT NULL,
    device_id TEXT,
    temperature DOUBLE PRECISION,
    humidity DOUBLE PRECISION,
    ammonia_ppm DOUBLE PRECISION,
    co2_ppm DOUBLE PRECISION,
    light_lux DOUBLE PRECISION
);

SELECT create_hypertable('iot.environmental_readings', 'time', chunk_time_interval => INTERVAL '1 day');

-- Animal activity from wearables
CREATE TABLE iot.animal_activity (
    time TIMESTAMPTZ NOT NULL,
    animal_rfid TEXT NOT NULL,
    animal_id BIGINT REFERENCES farm.animal(id),
    activity_level INTEGER,  -- 0-100 scale
    rumination_minutes INTEGER,
    body_temp DOUBLE PRECISION,
    step_count INTEGER
);

SELECT create_hypertable('iot.animal_activity', 'time', chunk_time_interval => INTERVAL '1 day');

-- Continuous aggregates for efficient querying
CREATE MATERIALIZED VIEW iot.hourly_milk_production
WITH (timescaledb.continuous) AS
SELECT 
    time_bucket('1 hour', time) AS bucket,
    animal_id,
    SUM(volume_liters) as total_volume,
    AVG(fat_percent) as avg_fat,
    AVG(snf_percent) as avg_snf,
    COUNT(*) as session_count
FROM iot.milk_production_realtime
GROUP BY bucket, animal_id;

-- Retention policies
SELECT add_retention_policy('iot.sensor_readings', INTERVAL '30 days');
SELECT add_retention_policy('iot.milk_production_realtime', INTERVAL '2 years');
SELECT add_retention_policy('iot.environmental_readings', INTERVAL '1 year');
SELECT add_retention_policy('iot.animal_activity', INTERVAL '1 year');
```

---

## 9. REAL-TIME ANALYTICS

### 9.1 Real-time Dashboard Queries

```python
# analytics_service.py
from fastapi import FastAPI
import asyncpg

app = FastAPI()

@app.get("/api/v1/analytics/milk-production/current")
async def get_current_milk_production():
    """Get today's milk production in real-time"""
    async with asyncpg.create_pool(dsn='postgresql://...') as pool:
        async with pool.acquire() as conn:
            result = await conn.fetchrow("""
                SELECT 
                    SUM(volume_liters) as total_liters,
                    COUNT(DISTINCT animal_id) as cows_milked,
                    AVG(fat_percent) as avg_fat,
                    AVG(snf_percent) as avg_snf
                FROM iot.milk_production_realtime
                WHERE time >= CURRENT_DATE
            """)
            
            return {
                'date': str(datetime.now().date()),
                'total_liters': round(result['total_liters'] or 0, 2),
                'cows_milked': result['cows_milked'],
                'avg_fat': round(result['avg_fat'] or 0, 2),
                'avg_snf': round(result['avg_snf'] or 0, 2),
            }

@app.get("/api/v1/analytics/barn/{barn_id}/environment")
async def get_barn_environment(barn_id: str):
    """Get current environmental conditions for a barn"""
    async with asyncpg.create_pool(dsn='postgresql://...') as pool:
        async with pool.acquire() as conn:
            result = await conn.fetchrow("""
                SELECT 
                    AVG(temperature) as avg_temp,
                    AVG(humidity) as avg_humidity,
                    AVG(ammonia_ppm) as avg_ammonia,
                    MAX(time) as last_reading
                FROM iot.environmental_readings
                WHERE barn_id = $1
                AND time >= NOW() - INTERVAL '15 minutes'
            """, barn_id)
            
            return {
                'barn_id': barn_id,
                'temperature_c': round(result['avg_temp'] or 0, 1),
                'humidity_percent': round(result['avg_humidity'] or 0, 1),
                'ammonia_ppm': round(result['avg_ammonia'] or 0, 2),
                'last_updated': result['last_reading'].isoformat() if result['last_reading'] else None
            }

@app.get("/api/v1/analytics/animal/{rfid}/activity")
async def get_animal_activity(rfid: str, hours: int = 24):
    """Get activity data for a specific animal"""
    async with asyncpg.create_pool(dsn='postgresql://...') as pool:
        async with pool.acquire() as conn:
            rows = await conn.fetch("""
                SELECT 
                    time_bucket('1 hour', time) as hour,
                    AVG(activity_level) as avg_activity,
                    AVG(rumination_minutes) as avg_rumination
                FROM iot.animal_activity
                WHERE animal_rfid = $1
                AND time >= NOW() - INTERVAL '%s hours'
                GROUP BY hour
                ORDER BY hour
            """ % hours, rfid)
            
            return {
                'animal_rfid': rfid,
                'period_hours': hours,
                'hourly_data': [
                    {
                        'hour': row['hour'].isoformat(),
                        'activity_level': round(row['avg_activity'] or 0, 1),
                        'rumination_minutes': round(row['avg_rumination'] or 0, 1)
                    }
                    for row in rows
                ]
            }
```

---

## 10. ALERTING SYSTEM

### 10.1 Alert Rules Configuration

```yaml
# alert_rules.yaml
rules:
  - name: high_temperature
    description: "Barn temperature exceeds safe threshold"
    condition: "environmental.temperature > 30"
    severity: warning
    cooldown: 30m
    actions:
      - type: notification
        channels: [email, sms, app]
      - type: webhook
        url: "https://api.smartdairybd.com/alerts/barn-temp"

  - name: low_milk_production
    description: "Cow production dropped significantly"
    condition: "milk.volume < animal.avg_production * 0.5"
    severity: warning
    cooldown: 1h
    actions:
      - type: notification
        channels: [app]
        recipients: [farm_manager, veterinarian]

  - name: heat_detection
    description: "High activity indicates potential heat"
    condition: "activity.level > 80 AND rumination < 300"
    severity: info
    cooldown: 12h
    actions:
      - type: notification
        channels: [app]
      - type: create_task
        task_type: breeding_check

  - name: health_alert
    description: "Abnormal body temperature detected"
    condition: "body_temp > 39.5 OR body_temp < 38.0"
    severity: critical
    cooldown: 15m
    actions:
      - type: notification
        channels: [sms, app]
        recipients: [veterinarian, farm_manager]

  - name: ammonia_level
    description: "Ammonia level in barn is dangerous"
    condition: "environmental.ammonia_ppm > 25"
    severity: critical
    cooldown: 10m
    actions:
      - type: notification
        channels: [sms, email]
      - type: webhook
        url: "https://api.smartdairybd.com/alerts/ventilation"
```

### 10.2 Alert Processing Service

```python
# alert_processor.py
from dataclasses import dataclass
from typing import List, Dict
import asyncpg
import aiohttp

@dataclass
class AlertRule:
    name: str
    condition: str
    severity: str
    cooldown: int
    actions: List[Dict]

class AlertProcessor:
    def __init__(self, db_pool, redis_client):
        self.db_pool = db_pool
        self.redis = redis_client
        self.rules = self.load_rules()
    
    async def process_reading(self, reading: Dict):
        """Process a sensor reading against alert rules"""
        for rule in self.rules:
            if self.matches_condition(reading, rule.condition):
                # Check cooldown
                if await self.check_cooldown(rule.name, reading):
                    await self.trigger_alert(rule, reading)
    
    def matches_condition(self, reading: Dict, condition: str) -> bool:
        """Evaluate alert condition against reading"""
        # Simple condition parser - in production use a proper expression engine
        try:
            if 'temperature' in condition and 'environmental' in reading.get('device_type', ''):
                temp = reading['data'].get('temperature', 0)
                return temp > 30 if 'temperature > 30' in condition else False
            
            if 'activity' in condition:
                activity = reading['data'].get('activity_level', 0)
                return activity > 80 if 'activity.level > 80' in condition else False
            
            return False
        except:
            return False
    
    async def check_cooldown(self, rule_name: str, reading: Dict) -> bool:
        """Check if enough time has passed since last alert"""
        key = f"alert:cooldown:{rule_name}:{reading.get('device_id', 'unknown')}"
        exists = self.redis.exists(key)
        if not exists:
            self.redis.setex(key, 1800, "1")  # 30 min cooldown
            return True
        return False
    
    async def trigger_alert(self, rule: AlertRule, reading: Dict):
        """Execute alert actions"""
        # Store alert in database
        async with self.db_pool.acquire() as conn:
            alert_id = await conn.fetchval("""
                INSERT INTO iot.alerts 
                (rule_name, severity, device_id, reading_data, created_at)
                VALUES ($1, $2, $3, $4, NOW())
                RETURNING id
            """, rule.name, rule.severity, reading.get('device_id'), 
                 json.dumps(reading))
        
        # Execute actions
        for action in rule.actions:
            if action['type'] == 'notification':
                await self.send_notification(action, rule, reading)
            elif action['type'] == 'webhook':
                await self.call_webhook(action['url'], rule, reading)
            elif action['type'] == 'create_task':
                await self.create_task(action, reading)
    
    async def send_notification(self, action: Dict, rule: AlertRule, reading: Dict):
        """Send notification through configured channels"""
        message = self.format_alert_message(rule, reading)
        
        # Push to mobile app via Firebase
        if 'app' in action.get('channels', []):
            await self.push_to_mobile(message)
        
        # Send SMS
        if 'sms' in action.get('channels', []) and rule.severity in ['critical', 'warning']:
            await self.send_sms(message)
        
        # Send email
        if 'email' in action.get('channels', []):
            await self.send_email(rule, message)
```

---

## 11. SECURITY ARCHITECTURE

### 11.1 Device Security

| Layer | Security Measure |
|-------|-----------------|
| **Authentication** | X.509 certificates per device |
| **Authorization** | Topic-level ACLs |
| **Encryption** | TLS 1.3 for all connections |
| **Key Rotation** | Automatic 90-day rotation |
| **Firmware** | Signed updates only |

### 11.2 Certificate Management

```bash
# Generate device certificates
#!/bin/bash
DEVICE_ID=$1
CA_CERT="/opt/smart-dairy/certs/ca.crt"
CA_KEY="/opt/smart-dairy/certs/ca.key"

# Generate private key
openssl genrsa -out ${DEVICE_ID}.key 2048

# Create CSR
openssl req -new -key ${DEVICE_ID}.key -out ${DEVICE_ID}.csr \
    -subj "/CN=${DEVICE_ID}/O=SmartDairy/C=BD"

# Sign with CA
openssl x509 -req -in ${DEVICE_ID}.csr -CA ${CA_CERT} -CAkey ${CA_KEY} \
    -CAcreateserial -out ${DEVICE_ID}.crt -days 365

# Install on device
# Device must have: ${DEVICE_ID}.crt, ${DEVICE_ID}.key, ca.crt
```

---

## 12. DEVICE MANAGEMENT

### 12.1 Device Lifecycle

```
┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐
│  ONBOARD │───▶│  ACTIVE  │───▶│MAINTENANCE│───▶│ RETIRED  │
│          │    │          │    │           │    │          │
└──────────┘    └──────────┘    └──────────┘    └──────────┘
       ▲                             │
       └─────────────────────────────┘
```

### 12.2 Device Registry API

```python
# device_management_api.py
from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import Optional
import asyncpg

app = FastAPI()

class DeviceRegistration(BaseModel):
    device_id: str
    device_type: str
    device_name: Optional[str]
    barn_id: str
    location_description: Optional[str]
    firmware_version: str

@app.post("/api/v1/devices/register")
async def register_device(registration: DeviceRegistration):
    """Register a new IoT device"""
    async with asyncpg.create_pool(dsn='postgresql://...') as pool:
        async with pool.acquire() as conn:
            # Generate certificate
            cert_path = await generate_device_certificate(registration.device_id)
            
            # Register in database
            await conn.execute("""
                INSERT INTO iot.devices 
                (device_id, device_type, device_name, barn_id, 
                 location_description, firmware_version, 
                 certificate_path, status, registered_at)
                VALUES ($1, $2, $3, $4, $5, $6, $7, 'active', NOW())
            """, registration.device_id, registration.device_type,
                 registration.device_name, registration.barn_id,
                 registration.location_description, registration.firmware_version,
                 cert_path)
            
            # Set up ACL
            await setup_device_acl(conn, registration.device_id, registration.device_type)
            
            return {
                'device_id': registration.device_id,
                'status': 'registered',
                'certificate_url': f'/api/v1/devices/{registration.device_id}/certificate'
            }

@app.post("/api/v1/devices/{device_id}/update")
async def update_firmware(device_id: str, version: str):
    """Trigger firmware update for a device"""
    # Publish OTA update message to device
    mqtt_client.publish(
        f"smartdairy/system/{device_id}/ota",
        json.dumps({
            'action': 'update',
            'version': version,
            'url': f'https://firmware.smartdairybd.com/{version}/{device_id}.bin',
            'checksum': await get_firmware_checksum(version)
        })
    )
    return {'status': 'update_initiated', 'device_id': device_id}
```

---

## 13. IMPLEMENTATION ROADMAP

### 13.1 Phase 1: Foundation (Months 4-5)

| Week | Activity | Deliverable |
|------|----------|-------------|
| 13-14 | MQTT broker setup | EMQX cluster deployed |
| 14-15 | Data ingestion service | Python ingestion API |
| 15-16 | TimescaleDB setup | Database schema |
| 16-17 | Basic dashboard | Real-time milk production view |

### 13.2 Phase 2: Sensor Deployment (Months 6-8)

| Week | Activity | Deliverable |
|------|----------|-------------|
| 17-19 | Milk meter integration | 16 milk meters in parlor |
| 19-21 | RFID system | Entry/exit gate readers |
| 21-23 | Environmental sensors | 20 barn sensors |

### 13.3 Phase 3: Advanced Analytics (Months 9-10)

| Week | Activity | Deliverable |
|------|----------|-------------|
| 25-27 | Wearable deployment | 50 activity monitors |
| 27-29 | ML models | Heat detection, health predictions |
| 29-31 | Alert system | Full alerting platform |

---

## 14. APPENDICES

### Appendix A: Network Requirements

| Component | Bandwidth | Latency |
|-----------|-----------|---------|
| Milk meters (per device) | 10 Kbps | < 1s |
| Environmental sensors | 5 Kbps | < 5s |
| Wearables (aggregated) | 50 Kbps | < 30s |
| Total estimated | 500 Kbps | - |

### Appendix B: Power Requirements

| Device Type | Power | Backup |
|-------------|-------|--------|
| Farm Gateway | 50W | UPS 4hr |
| LoRaWAN Gateway | 20W | UPS 4hr |
| Milk meter | 12V DC | Battery 24hr |
| Environmental sensor | 5V | Battery 1 year |
| Wearable | Battery | - (2 year life) |

---

**END OF IOT ARCHITECTURE DESIGN**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Feb 28, 2026 | IoT Architect | Initial version |
