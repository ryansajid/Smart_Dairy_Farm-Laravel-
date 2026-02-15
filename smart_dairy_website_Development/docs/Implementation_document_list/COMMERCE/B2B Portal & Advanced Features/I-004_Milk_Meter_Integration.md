# SMART DAIRY LTD.
## MILK METER INTEGRATION GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | I-004 |
| **Version** | 1.0 |
| **Date** | July 21, 2026 |
| **Author** | IoT Engineer |
| **Owner** | IoT Engineer |
| **Reviewer** | Farm Manager |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Milk Meter Overview](#2-milk-meter-overview)
3. [System Architecture](#3-system-architecture)
4. [Hardware Integration](#4-hardware-integration)
5. [Software Integration](#5-software-integration)
6. [Data Processing](#6-data-processing)
7. [Calibration & Maintenance](#7-calibration--maintenance)
8. [Troubleshooting](#8-troubleshooting)
9. [Security Considerations](#9-security-considerations)
10. [Appendices](#10-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides comprehensive technical specifications for integrating milk meters into the Smart Dairy IoT ecosystem. Milk meters enable automated, accurate measurement of milk yield per cow, providing real-time data for production optimization, animal health monitoring, and herd management.

### 1.2 Scope

| Component | Description |
|-----------|-------------|
| **Milk Meters** | Electronic volumetric meters (DeLaval MM27BC, Afimilk, GEA) |
| **RFID Readers** | Cow identification at milking points |
| **Gateway Devices** | Raspberry Pi / Industrial IoT gateways |
| **Communication** | Modbus RTU/TCP, MQTT, WiFi/Ethernet |
| **Integration** | Odoo ERP Farm Management Module |

### 1.3 Business Benefits

| Benefit | Metric |
|---------|--------|
| **Accuracy** | ±2% measurement precision vs ±10% manual |
| **Efficiency** | 50% reduction in milking time per cow |
| **Health Detection** | Early mastitis detection via conductivity |
| **Production Optimization** | 15% improvement in per-cow yield |
| **Labor Savings** | 2 hours/day manual recording eliminated |

---

## 2. MILK METER OVERVIEW

### 2.1 Supported Milk Meter Models

| Model | Manufacturer | Protocol | Accuracy | Features |
|-------|-------------|----------|----------|----------|
| **MM27BC** | DeLaval | Modbus RTU | ±2% | Basic volume, conductivity |
| **MM27BC Pro** | DeLaval | Modbus RTU/TCP | ±1.5% | Volume, conductivity, temperature |
| **AfimilkMeter** | Afimilk | Proprietary/Ethernet | ±1% | Volume, fat%, conductivity |
| **DairyPro** | GEA | Modbus TCP | ±2% | Volume, conductivity, flow rate |
| **Metatron 12** | Westfalia | RS-485 | ±2% | Volume, temperature |

### 2.2 Key Specifications

#### DeLaval MM27BC (Recommended)

| Parameter | Specification |
|-----------|--------------|
| **Measurement Range** | 0.5 - 50 liters per milking |
| **Accuracy** | ±2% (±0.1L for volumes <5L) |
| **Measurement Principle** | Electronic volumetric with optical sensors |
| **Conductivity Range** | 2.0 - 10.0 mS/cm |
| **Temperature Range** | 5°C - 45°C |
| **Power Supply** | 12-24V DC |
| **Power Consumption** | < 5W |
| **Communication** | RS-485 (Modbus RTU) |
| **Baud Rate** | 9600-115200 bps |
| **Cable Length** | Up to 1000m with proper termination |
| **IP Rating** | IP65 (dust/water resistant) |
| **Operating Temperature** | -10°C to +50°C |
| **Weight** | 0.8 kg |
| **Dimensions** | 180mm × 120mm × 80mm |
| **Certifications** | CE, ISO 9001 |

---

## 3. SYSTEM ARCHITECTURE

### 3.1 Integration Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         MILK METER INTEGRATION ARCHITECTURE                  │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         MILKING PARLOR                                 │  │
│  │                                                                          │  │
│  │   Stall 1        Stall 2        Stall 3        Stall 4                │  │
│  │  ┌───────┐     ┌───────┐     ┌───────┐     ┌───────┐                │  │
│  │  │Cow #1 │     │Cow #2 │     │Cow #3 │     │Cow #4 │                │  │
│  │  └───┬───┘     └───┬───┘     └───┬───┘     └───┬───┘                │  │
│  │      │             │             │             │                      │  │
│  │  ┌───▼───┐     ┌───▼───┐     ┌───▼───┐     ┌───▼───┐                │  │
│  │  │ RFID  │     │ RFID  │     │ RFID  │     │ RFID  │                │  │
│  │  │Reader │     │Reader │     │Reader │     │Reader │                │  │
│  │  └───┬───┘     └───┬───┘     └───┬───┘     └───┬───┘                │  │
│  │      │             │             │             │                      │  │
│  │  ┌───▼───┐     ┌───▼───┐     ┌───▼───┐     ┌───▼───┐                │  │
│  │  │ Milk  │     │ Milk  │     │ Milk  │     │ Milk  │                │  │
│  │  │Meter 1│     │Meter 2│     │Meter 3│     │Meter 4│                │  │
│  │  │MM27BC │     │MM27BC │     │MM27BC │     │MM27BC │                │  │
│  │  └───┬───┘     └───┬───┘     └───┬───┘     └───┬───┘                │  │
│  │      │             │             │             │                      │  │
│  │      └─────────────┴─────────────┴─────────────┘                      │  │
│  │                      │                                                │  │
│  │                      │ RS-485 Bus                                     │  │
│  └──────────────────────┼────────────────────────────────────────────────┘  │
│                         │                                                    │
│  ┌──────────────────────▼────────────────────────────────────────────────┐  │
│  │                    EDGE GATEWAY                                        │  │
│  │  ┌───────────────────────────────────────────────────────────────────┐ │  │
│  │  │  Raspberry Pi 4 / Industrial Gateway                              │ │  │
│  │  │                                                                    │ │  │
│  │  │  • Modbus RTU Client (USB-RS485 adapter)                          │ │  │
│  │  │  • RFID Reader Integration (TCP/IP)                               │ │  │
│  │  │  • MQTT Client (paho-mqtt)                                        │ │  │
│  │  │  • Local Data Buffer (SQLite)                                     │ │  │
│  │  │  • Edge Analytics                                                 │ │  │
│  │  └───────────────────────────────────────────────────────────────────┘ │  │
│  │                          │                                             │  │
│  │                          │ WiFi/Ethernet                              │  │
│  └──────────────────────────┼─────────────────────────────────────────────┘  │
│                             │                                                │
│  ┌──────────────────────────▼─────────────────────────────────────────────┐  │
│  │                         MQTT BROKER                                     │  │
│  │                    (smartdairy.local:1883)                              │  │
│  └──────────────────────────┬─────────────────────────────────────────────┘  │
│                             │                                                │
│  ┌──────────────────────────▼─────────────────────────────────────────────┐  │
│  │                      DATA PROCESSOR                                     │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐                │  │
│  │  │ MQTT         │  │ Data         │  │ Odoo         │                │  │
│  │  │ Consumer     │──▶ Validator   │──▶ Integration │                │  │
│  │  │ (Python)     │  │ (Pydantic)   │  │ (XML-RPC)    │                │  │
│  │  └──────────────┘  └──────────────┘  └──────────────┘                │  │
│  └─────────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Data Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           DATA FLOW SEQUENCE                                 │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  1. COW IDENTIFICATION                                                       │
│     Cow enters milking parlor → RFID reader scans ear tag                    │
│     → Tag ID sent to gateway → Animal lookup                                  │
│                                                                              │
│  2. MILKING SESSION                                                          │
│     Milking starts → Milk flows through meter                                │
│     → Volume measured (pulse count) → Data buffered locally                  │
│                                                                              │
│  3. SESSION COMPLETION                                                       │
│     Milking ends → Final volume calculated                                   │
│     → Conductivity measured → Temperature recorded                           │
│     → Data packet assembled                                                  │
│                                                                              │
│  4. DATA TRANSMISSION                                                        │
│     Gateway receives data → MQTT message published                           │
│     → Topic: smartdairy/farm/barn_a/milking_parlor/milk_meter/001/data       │
│                                                                              │
│  5. DATA PROCESSING                                                          │
│     MQTT broker receives → Consumer processes                                │
│     → Validation → Enrichment (cow details)                                  │
│     → Odoo ERP update                                                        │
│                                                                              │
│  6. REAL-TIME ALERTS                                                         │
│     Threshold checks → Alert generation                                      │
│     → Notifications (SMS/App)                                                │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 4. HARDWARE INTEGRATION

### 4.1 Wiring Diagram - DeLaval MM27BC

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    DE LAVAL MM27BC WIRING DIAGRAM                            │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  MILK METER (MM27BC)                          GATEWAY (Raspberry Pi)         │
│  ┌─────────────────────┐                     ┌───────────────────┐          │
│  │                     │                     │                   │          │
│  │  Terminal Block     │                     │  USB-RS485        │          │
│  │  ┌───────────────┐  │                     │  Adapter          │          │
│  │  │ 1: +12V (Red) │──┼────────────────────▶│  VCC (Red)        │          │
│  │  │ 2: GND (Black)│──┼────────────────────▶│  GND (Black)      │          │          
│  │  │ 3: A+ (Green) │──┼────────────────────▶│  A+ (Green)       │          │
│  │  │ 4: B- (White) │──┼────────────────────▶│  B- (White)       │          │
│  │  │ 5: SHIELD     │──┼──(Ground only at    │  Shield           │          │
│  │  │    (Bare)     │  │    one end)         │                   │          │
│  │  └───────────────┘  │                     └─────────┬─────────┘          │
│  │                     │                               │                    │
│  └─────────────────────┘                               │ USB                │
│                                                        ▼                    │
│                                               ┌───────────────────┐         │
│                                               │  Raspberry Pi 4   │         │
│                                               │  USB Port         │         │
│                                               └───────────────────┘         │
│                                                                              │
│  RS-485 BUS CONFIGURATION:                                                   │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                                                                     │    │
│  │   Meter 1    Meter 2    Meter 3    Meter 4         Gateway          │    │
│  │  ┌──────┐   ┌──────┐   ┌──────┐   ┌──────┐       ┌──────┐         │    │
│  │  │A+  B-│◄─▶│A+  B-│◄─▶│A+  B-│◄─▶│A+  B-│◄─────▶│A+  B-│         │    │
│  │  └──┬───┘   └──┬───┘   └──┬───┘   └──┬───┘       └──┬───┘         │    │
│  │     │          │          │          │              │             │    │
│  │     └──────────┴──────────┴──────────┘              │             │    │
│  │                    BUS LINE                         │             │    │
│  │                                                     │             │    │
│  │  [120Ω] Termination Resistor at last device         │             │    │
│  │  [120Ω] Termination Resistor at gateway             │             │    │
│  │                                                     │             │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  NOTE: Maximum 32 devices per RS-485 bus                                     │
│        Use shielded twisted pair cable (CAT5/CAT6)                           │
│        Maximum cable length: 1000m at 9600 baud                              │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 4.2 RFID Integration

```python
# hardware/rfid_reader.py

import socket
import json
from typing import Optional, Callable

class RFIDReader:
    """
    UHF RFID Reader Integration
    Supports Impinj, Zebra, Alien readers
    """
    
    def __init__(self, host: str, port: int = 14150):
        self.host = host
        self.port = port
        self.socket: Optional[socket.socket] = None
        self.on_tag_read: Optional[Callable] = None
        
    def connect(self):
        """Establish TCP connection to RFID reader"""
        self.socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.socket.settimeout(5.0)
        self.socket.connect((self.host, self.port))
        
        # Configure reader mode
        self._send_command({
            'command': 'SET_MODE',
            'mode': 'INVENTORY',
            'session': 2,  # S2 session for fast switching
            'inventory_duration': 500,  # 500ms inventory cycles
        })
        
    def start_inventory(self, callback: Callable):
        """Start continuous inventory mode"""
        self.on_tag_read = callback
        
        while True:
            try:
                data = self.socket.recv(1024)
                if data:
                    tag_data = self._parse_tag_data(data)
                    if tag_data and self.on_tag_read:
                        self.on_tag_read(tag_data)
            except socket.timeout:
                continue
            except Exception as e:
                print(f"RFID read error: {e}")
                break
                
    def _parse_tag_data(self, raw_data: bytes) -> Optional[dict]:
        """Parse RFID tag data"""
        try:
            # Example: E200341502001080 (96-bit EPC)
            epc = raw_data.hex().upper()
            
            return {
                'epc': epc,
                'timestamp': time.time(),
                'rssi': -65,  # Signal strength
                'antenna': 1,
            }
        except:
            return None
    
    def _send_command(self, command: dict):
        """Send configuration command to reader"""
        self.socket.send(json.dumps(command).encode() + b'\n')
```

---

## 5. SOFTWARE INTEGRATION

### 5.1 Modbus Communication

```python
# integration/modbus_client.py

from pymodbus.client import ModbusSerialClient
from pymodbus.exceptions import ModbusIOException
import struct
from typing import Optional, Dict

class MilkMeterModbusClient:
    """
    Modbus RTU client for DeLaval MM27BC milk meters
    """
    
    # Modbus register addresses for MM27BC
    REGISTERS = {
        'device_id': (0x0000, 2),        # Device ID (4 bytes)
        'firmware_version': (0x0002, 1),  # Firmware version
        'status': (0x0010, 1),           # Device status
        'volume_total': (0x0020, 2),      # Total volume (L) - 32-bit float
        'volume_session': (0x0022, 2),    # Session volume (L) - 32-bit float
        'conductivity': (0x0024, 2),      # Conductivity (mS/cm) - 32-bit float
        'temperature': (0x0026, 2),       # Temperature (°C) - 32-bit float
        'flow_rate': (0x0028, 2),         # Flow rate (L/min) - 32-bit float
        'milkings_count': (0x0030, 1),    # Total milkings count
        'alarm_status': (0x0040, 1),      # Alarm/status flags
    }
    
    def __init__(self, port: str = '/dev/ttyUSB0', baudrate: int = 9600):
        self.client = ModbusSerialClient(
            port=port,
            baudrate=baudrate,
            bytesize=8,
            parity='N',
            stopbits=1,
            timeout=1
        )
        
    def connect(self) -> bool:
        """Connect to Modbus RTU bus"""
        return self.client.connect()
    
    def read_meter_data(self, slave_id: int) -> Optional[Dict]:
        """
        Read all data from milk meter
        
        Args:
            slave_id: Modbus slave address (1-32)
            
        Returns:
            Dictionary with meter readings or None if error
        """
        try:
            data = {}
            
            # Read volume (32-bit float, 2 registers)
            volume_result = self.client.read_holding_registers(
                address=self.REGISTERS['volume_session'][0],
                count=2,
                slave=slave_id
            )
            if volume_result.isError():
                return None
            data['volume_liters'] = self._registers_to_float(volume_result.registers)
            
            # Read conductivity (32-bit float, 2 registers)
            conductivity_result = self.client.read_holding_registers(
                address=self.REGISTERS['conductivity'][0],
                count=2,
                slave=slave_id
            )
            if not conductivity_result.isError():
                data['conductivity_ms_cm'] = self._registers_to_float(
                    conductivity_result.registers
                )
            
            # Read temperature (32-bit float, 2 registers)
            temp_result = self.client.read_holding_registers(
                address=self.REGISTERS['temperature'][0],
                count=2,
                slave=slave_id
            )
            if not temp_result.isError():
                data['temperature_celsius'] = self._registers_to_float(
                    temp_result.registers
                )
            
            # Read status
            status_result = self.client.read_holding_registers(
                address=self.REGISTERS['status'][0],
                count=1,
                slave=slave_id
            )
            if not status_result.isError():
                data['status_code'] = status_result.registers[0]
            
            data['slave_id'] = slave_id
            data['timestamp'] = time.time()
            
            return data
            
        except ModbusIOException as e:
            print(f"Modbus IO error (slave {slave_id}): {e}")
            return None
        except Exception as e:
            print(f"Error reading meter {slave_id}: {e}")
            return None
    
    def _registers_to_float(self, registers: list) -> float:
        """Convert two 16-bit registers to 32-bit float"""
        # Modbus returns registers in big-endian order
        packed = struct.pack('>HH', registers[0], registers[1])
        return struct.unpack('>f', packed)[0]
    
    def reset_session_volume(self, slave_id: int) -> bool:
        """Reset session volume counter after milking"""
        try:
            # Write 0 to session volume registers
            result = self.client.write_register(
                address=self.REGISTERS['volume_session'][0],
                value=0,
                slave=slave_id
            )
            return not result.isError()
        except Exception as e:
            print(f"Error resetting meter {slave_id}: {e}")
            return False
    
    def close(self):
        """Close Modbus connection"""
        self.client.close()
```

### 5.2 MQTT Data Publisher

```python
# integration/mqtt_publisher.py

import paho.mqtt.client as mqtt
import json
from datetime import datetime

class MilkMeterDataPublisher:
    """
    Publishes milk meter data to MQTT broker
    """
    
    def __init__(self, broker_host: str, broker_port: int = 1883):
        self.broker_host = broker_host
        self.broker_port = broker_port
        self.client = mqtt.Client(client_id=f"milk_meter_gateway_{uuid.uuid4().hex[:8]}")
        
        # Set up callbacks
        self.client.on_connect = self._on_connect
        self.client.on_publish = self._on_publish
        
    def _on_connect(self, client, userdata, flags, rc):
        """Connection callback"""
        if rc == 0:
            print(f"Connected to MQTT broker: {self.broker_host}")
        else:
            print(f"Connection failed with code: {rc}")
    
    def _on_publish(self, client, userdata, mid):
        """Publish callback"""
        print(f"Message {mid} published")
    
    def connect(self, username: str = None, password: str = None):
        """Connect to MQTT broker"""
        if username and password:
            self.client.username_pw_set(username, password)
        
        self.client.connect(self.broker_host, self.broker_port, keepalive=60)
        self.client.loop_start()
    
    def publish_milking_data(self, 
                             meter_id: str, 
                             cow_rfid: str,
                             data: dict,
                             barn_id: str = 'barn_a',
                             stall_id: str = 'stall_01'):
        """
        Publish milking session data
        
        Topic structure: smartdairy/farm/{barn_id}/milking_parlor/milk_meter/{meter_id}/data
        """
        topic = f"smartdairy/farm/{barn_id}/milking_parlor/milk_meter/{meter_id}/data"
        
        payload = {
            'schema_version': '1.0',
            'device_id': meter_id,
            'device_type': 'milk_meter',
            'timestamp': datetime.utcnow().isoformat() + 'Z',
            'location': {
                'barn_id': barn_id,
                'stall_id': stall_id,
            },
            'data': {
                'animal_rfid': cow_rfid,
                'session': self._get_milking_session(),
                'volume_liters': round(data['volume_liters'], 2),
                'conductivity_ms_cm': round(data.get('conductivity_ms_cm', 0), 2),
                'temperature_celsius': round(data.get('temperature_celsius', 0), 1),
                'flow_rate_avg': round(data.get('flow_rate', 0), 2),
                'milking_duration_seconds': data.get('duration_seconds', 0),
            },
            'quality': {
                'conductivity_status': self._check_conductivity(data.get('conductivity_ms_cm', 0)),
                'volume_status': self._check_volume(data['volume_liters']),
            },
            'metadata': {
                'firmware_version': data.get('firmware_version', 'unknown'),
                'signal_strength': data.get('rssi', 0),
                'gateway_id': data.get('gateway_id', 'unknown'),
            }
        }
        
        result = self.client.publish(
            topic=topic,
            payload=json.dumps(payload),
            qos=1,  # At least once delivery
            retain=False
        )
        
        return result.mid
    
    def _get_milking_session(self) -> str:
        """Determine milking session based on time"""
        hour = datetime.now().hour
        if 4 <= hour < 12:
            return 'morning'
        elif 12 <= hour < 16:
            return 'midday'
        elif 16 <= hour < 22:
            return 'evening'
        else:
            return 'night'
    
    def _check_conductivity(self, conductivity: float) -> str:
        """Check if conductivity indicates mastitis risk"""
        if conductivity > 6.5:
            return 'ALERT_HIGH'  # Possible mastitis
        elif conductivity > 5.5:
            return 'WARNING_ELEVATED'
        else:
            return 'NORMAL'
    
    def _check_volume(self, volume: float) -> str:
        """Check if volume is within expected range"""
        if volume < 5.0:
            return 'LOW'
        elif volume > 40.0:
            return 'HIGH'
        else:
            return 'NORMAL'
```

---

## 6. DATA PROCESSING

### 6.1 Data Consumer Service

```python
# processing/data_consumer.py

import paho.mqtt.client as mqtt
import json
from typing import Dict

class MilkMeterDataConsumer:
    """
    Consumes milk meter data from MQTT and processes into Odoo
    """
    
    def __init__(self, broker_host: str, odoo_url: str, odoo_db: str):
        self.broker_host = broker_host
        self.odoo_url = odoo_url
        self.odoo_db = odoo_db
        self.client = mqtt.Client(client_id="milk_meter_processor")
        
        self.client.on_connect = self._on_connect
        self.client.on_message = self._on_message
        
    def _on_connect(self, client, userdata, flags, rc):
        """Subscribe to milk meter topics on connect"""
        if rc == 0:
            print("Connected to MQTT broker")
            # Subscribe to all milk meter data topics
            client.subscribe("smartdairy/farm/+/milking_parlor/milk_meter/+/data")
            print("Subscribed to milk meter topics")
        
    def _on_message(self, client, userdata, msg):
        """Process incoming MQTT message"""
        try:
            payload = json.loads(msg.payload.decode())
            
            # Validate schema
            if not self._validate_payload(payload):
                print(f"Invalid payload received: {msg.topic}")
                return
            
            # Process data
            self._process_milking_data(payload)
            
        except json.JSONDecodeError as e:
            print(f"JSON decode error: {e}")
        except Exception as e:
            print(f"Error processing message: {e}")
    
    def _validate_payload(self, payload: Dict) -> bool:
        """Validate incoming data payload"""
        required_fields = [
            'schema_version', 'device_id', 'timestamp', 
            'location', 'data', 'data.animal_rfid', 'data.volume_liters'
        ]
        
        for field in required_fields:
            if '.' in field:
                parts = field.split('.')
                current = payload
                for part in parts:
                    if not isinstance(current, dict) or part not in current:
                        return False
                    current = current[part]
            else:
                if field not in payload:
                    return False
        
        return True
    
    def _process_milking_data(self, payload: Dict):
        """Process and store milking data"""
        data = payload['data']
        location = payload['location']
        
        # Create Odoo record
        odoo_data = {
            'animal_rfid': data['animal_rfid'],
            'device_id': payload['device_id'],
            'milking_date': payload['timestamp'],
            'session': data['session'],
            'volume_liters': data['volume_liters'],
            'conductivity_ms_cm': data.get('conductivity_ms_cm', 0),
            'temperature_celsius': data.get('temperature_celsius', 0),
            'barn_id': location['barn_id'],
            'stall_id': location['stall_id'],
            'quality_status': payload.get('quality', {}).get('conductivity_status', 'UNKNOWN'),
        }
        
        # Send to Odoo
        self._create_odoo_record(odoo_data)
        
        # Check for alerts
        self._check_alerts(payload)
    
    def _create_odoo_record(self, data: Dict):
        """Create milk production record in Odoo"""
        # Implementation using Odoo XML-RPC
        pass
    
    def _check_alerts(self, payload: Dict):
        """Check for conditions requiring alerts"""
        quality = payload.get('quality', {})
        data = payload['data']
        
        # High conductivity alert (mastitis indicator)
        if quality.get('conductivity_status') == 'ALERT_HIGH':
            self._send_alert(
                alert_type='HIGH_CONDUCTIVITY',
                animal_rfid=data['animal_rfid'],
                value=data['conductivity_ms_cm'],
                message=f"High conductivity detected: {data['conductivity_ms_cm']} mS/cm"
            )
        
        # Low volume alert
        if quality.get('volume_status') == 'LOW' and data['volume_liters'] < 3.0:
            self._send_alert(
                alert_type='LOW_MILK_VOLUME',
                animal_rfid=data['animal_rfid'],
                value=data['volume_liters'],
                message=f"Low milk volume: {data['volume_liters']} L"
            )
    
    def _send_alert(self, alert_type: str, animal_rfid: str, value: float, message: str):
        """Send alert notification"""
        alert_payload = {
            'type': alert_type,
            'animal_rfid': animal_rfid,
            'value': value,
            'message': message,
            'timestamp': datetime.utcnow().isoformat(),
        }
        
        self.client.publish(
            topic="smartdairy/alerts/milk_quality",
            payload=json.dumps(alert_payload),
            qos=1
        )
    
    def start(self):
        """Start the consumer"""
        self.client.connect(self.broker_host, 1883, 60)
        self.client.loop_forever()
```

---

## 7. CALIBRATION & MAINTENANCE

### 7.1 Calibration Procedure

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    MILK METER CALIBRATION PROCEDURE                          │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  FREQUENCY: Monthly or when accuracy suspected to be off                    │
│  TOOLS REQUIRED: Certified measuring container (±0.1L accuracy)             │
│                  Cleaning solution, Soft brush                              │
│                                                                              │
│  PROCEDURE:                                                                  │
│                                                                              │
│  1. PREPARATION                                                              │
│     □ Ensure meter is clean and free from debris                            │
│     □ Connect laptop with Modbus diagnostic tool                            │
│     □ Prepare 20L of clean water at ~35°C                                   │
│                                                                              │
│  2. ZERO CALIBRATION                                                         │
│     □ Run command: CALIBRATE_ZERO                                           │
│     □ Verify zero reading: 0.0 ± 0.1 L                                      │
│     □ If outside range, repeat or contact service                           │
│                                                                              │
│  3. SPAN CALIBRATION                                                         │
│     □ Pour exactly 10L of water through meter                               │
│     □ Record meter reading                                                  │
│     □ Calculate error: (Reading - 10.0) / 10.0 × 100%                       │
│     □ If error > 2%, perform span calibration                               │
│                                                                              │
│  4. DOCUMENTATION                                                            │
│     □ Record calibration date and results                                   │
│     □ Update calibration log in Odoo                                        │
│     □ Apply calibration sticker with date                                   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 7.2 Maintenance Schedule

| Frequency | Task | Procedure |
|-----------|------|-----------|
| **Daily** | Visual inspection | Check for leaks, damage, loose connections |
| **Weekly** | Cleaning | Flush with warm water and dairy detergent |
| **Monthly** | Calibration | Perform accuracy calibration |
| **Quarterly** | Deep clean | Disassemble and sanitize all parts |
| **Annually** | Professional service | Manufacturer service and certification |

---

## 8. TROUBLESHOOTING

### 8.1 Common Issues

| Issue | Possible Cause | Solution |
|-------|---------------|----------|
| No volume reading | Clogged sensor | Clean optical sensor |
 | | Power failure | Check 12V supply |
| Inaccurate readings | Calibration drift | Recalibrate meter |
| | Air bubbles in line | Check for leaks, reprime |
| High conductivity | Mastitis in cow | Alert vet, segregate milk |
| | Sensor contamination | Clean and recalibrate |
| Communication error | Wiring fault | Check RS-485 connections |
| | Address conflict | Verify unique slave ID |

---

## 9. SECURITY CONSIDERATIONS

### 9.1 Device Security

- Change default Modbus slave addresses
- Enable MQTT authentication
- Use TLS for MQTT communication
- Implement device certificate validation
- Regular firmware updates

---

## 10. APPENDICES

### Appendix A: Modbus Register Map

| Register | Address | Type | Description |
|----------|---------|------|-------------|
| Device ID | 0x0000 | uint32 | Unique device identifier |
| Status | 0x0010 | uint16 | Device status flags |
| Volume Total | 0x0020 | float32 | Cumulative volume (L) |
| Volume Session | 0x0022 | float32 | Current session volume (L) |
| Conductivity | 0x0024 | float32 | Conductivity (mS/cm) |
| Temperature | 0x0026 | float32 | Milk temperature (°C) |

### Appendix B: Bill of Materials

| Item | Quantity | Unit Price (৳) | Total (৳) |
|------|----------|----------------|-----------|
| DeLaval MM27BC Milk Meter | 16 | 85,000 | 1,360,000 |
| RFID Reader (Impinj) | 16 | 45,000 | 720,000 |
| Raspberry Pi 4 (8GB) | 4 | 12,000 | 48,000 |
| RS-485 USB Adapter | 4 | 3,500 | 14,000 |
| Shielded CAT6 Cable (100m) | 4 | 8,000 | 32,000 |
| Electrical Installation | 1 | 150,000 | 150,000 |
| **TOTAL** | | | **2,324,000** |

---

**END OF MILK METER INTEGRATION GUIDE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | July 21, 2026 | IoT Engineer | Initial version |
