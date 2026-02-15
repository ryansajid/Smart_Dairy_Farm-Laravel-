# SMART DAIRY LTD.
## IOT DEVICE INTEGRATION SPECIFICATION
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | I-003 |
| **Version** | 1.0 |
| **Date** | August 15, 2026 |
| **Author** | IoT Engineer |
| **Owner** | IoT Engineer |
| **Reviewer** | Solution Architect |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Device Ecosystem Overview](#2-device-ecosystem-overview)
3. [Device Onboarding Process](#3-device-onboarding-process)
4. [Integration Protocols](#4-integration-protocols)
5. [Device Communication Standards](#5-device-communication-standards)
6. [Security Implementation](#6-security-implementation)
7. [Device Management](#7-device-management)
8. [Edge Gateway Integration](#8-edge-gateway-integration)
9. [Troubleshooting & Diagnostics](#9-troubleshooting--diagnostics)
10. [Appendices](#10-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides comprehensive technical specifications for integrating IoT devices into the Smart Dairy ecosystem. It defines the standards, protocols, and procedures for connecting milk meters, environmental sensors, RFID readers, cattle wearables, and other smart farm devices to the centralized IoT platform.

### 1.2 Scope

| Component | Description |
|-----------|-------------|
| **Milk Meters** | DeLaval, Afimilk, GEA milk metering systems |
| **Environmental Sensors** | Temperature, humidity, ammonia, CO2 sensors |
| **RFID System** | UHF RFID readers and ear tags |
| **Cattle Wearables** | Activity monitors, smart collars, rumination sensors |
| **Quality Analyzers** | Inline milk quality measurement devices |
| **Cold Storage Monitors** | Temperature/humidity sensors for cold chain |
| **Feed Systems** | Automated feeding station controllers |

### 1.3 Integration Scale

| Phase | Devices | Data Points/Day | Timeline |
|-------|---------|-----------------|----------|
| **Phase 1** | 200 devices | 500,000 | Months 4-6 |
| **Phase 2** | 500 devices | 1,500,000 | Months 7-9 |
| **Phase 3** | 1,000 devices | 2,000,000+ | Year 2 |

---

## 2. DEVICE ECOSYSTEM OVERVIEW

### 2.1 Device Categories

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      SMART DAIRY DEVICE ECOSYSTEM                            │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    PRODUCTION MONITORING                             │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │ Milk Meters  │  │   Quality    │  │ Weighing     │              │   │
│  │  │ (Modbus/RTU) │  │   Analyzers  │  │   Scales     │              │   │
│  │  │ 16 units     │  │   (Ethernet) │  │   (RS-232)   │              │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘              │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    ANIMAL MONITORING                                 │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │   Smart      │  │   Activity   │  │     RFID     │              │   │
│  │  │   Collars    │  │   Monitors   │  │    Readers   │              │   │
│  │  │  (LoRaWAN)   │  │   (LoRaWAN)  │  │  (Ethernet)  │              │   │
│  │  │  50 units    │  │   50 units   │  │   20 units   │              │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘              │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    ENVIRONMENTAL MONITORING                          │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │ Temperature  │  │   Humidity   │  │    Gas       │              │   │
│  │  │   Sensors    │  │   Sensors    │  │   Sensors    │              │   │
│  │  │   (WiFi)     │  │    (WiFi)    │  │   (LoRaWAN)  │              │   │
│  │  │   20 units   │  │   20 units   │  │   20 units   │              │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘              │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    INFRASTRUCTURE                                    │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │ Cold Storage │  │    Feed      │  │   Biogas     │              │   │
│  │  │   Monitors   │  │   Stations   │  │   Monitors   │              │   │
│  │  │   (WiFi)     │  │  (Modbus)    │  │    (WiFi)    │              │   │
│  │  │   10 units   │  │    8 units   │  │    4 units   │              │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘              │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Device Specifications Matrix

| Device Type | Manufacturer | Model | Protocol | Frequency | Power |
|-------------|--------------|-------|----------|-----------|-------|
| **Milk Meter** | DeLaval | MM27BC | Modbus RTU | Per milking | 12V DC |
| **Milk Meter** | Afimilk | AfimilkMeter | Ethernet | Per milking | 24V DC |
| **RFID Reader** | Impinj | Speedway R420 | LLRP/Ethernet | Continuous | PoE |
| **RFID Reader** | Zebra | FX9600 | LLRP/Ethernet | Continuous | PoE |
| **Smart Collar** | Afimilk | Silent Herdsman | LoRaWAN | 15 min | Battery 2yr |
| **Activity Monitor** | CowManager | Ear Tag | LoRaWAN | 10 min | Battery 3yr |
| **Env. Sensor** | Bosch | BME680 | WiFi/MQTT | 5 min | 5V DC |
| **Ammonia Sensor** | Sensirion | SGP30 | LoRaWAN | 5 min | Battery 1yr |
| **Cold Monitor** | Emerson | GO Real-Time | Cellular | 15 min | Battery |

---

## 3. DEVICE ONBOARDING PROCESS

### 3.1 Onboarding Workflow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      DEVICE ONBOARDING WORKFLOW                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌────────┐│
│  │  STEP 1  │───▶│  STEP 2  │───▶│  STEP 3  │───▶│  STEP 4  │───▶│ STEP 5 ││
│  │ Physical │    │  ERP     │    │ Network  │    │ Security │    │  Test  ││
│  │ Install  │    │ Register │    │ Config   │    │  Setup   │    │ & Ver  ││
│  └──────────┘    └──────────┘    └──────────┘    └──────────┘    └────────┘│
│       │              │               │               │               │      │
│       ▼              ▼               ▼               ▼               ▼      │
│  ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌────────┐│
│  │ Mount    │    │ Create   │    │ Assign   │    │ Generate │    │ Publish││
│  │ Device   │    │ Device   │    │  IP/     │    │ Certs/   │    │ Test   ││
│  │ Connect  │    │ Record   │    │ Gateway  │    │  ACL     │    │ Message││
│  │ Cables   │    │ in Odoo  │    │  Config  │    │          │    │ Verify ││
│  └──────────┘    └──────────┘    └──────────┘    └──────────┘    └────────┘│
│                                                                              │
│  Time: 30 min    Time: 10 min   Time: 15 min   Time: 20 min   Time: 15 min │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Device Registration API

```python
# device_registration.py
from fastapi import FastAPI, HTTPException
from pydantic import BaseModel, Field
from typing import Optional, Dict, Any
import asyncpg
import uuid
from datetime import datetime

app = FastAPI(title="Smart Dairy Device Registration API")

class DeviceRegistrationRequest(BaseModel):
    """Device registration request payload"""
    device_type: str = Field(..., description="Type: milk_meter, rfid_reader, env_sensor, wearable")
    device_model: str = Field(..., description="Manufacturer model number")
    serial_number: str = Field(..., description="Device serial number")
    barn_id: str = Field(..., description="Barn/location identifier")
    location_description: Optional[str] = Field(None, description="Physical location details")
    communication_protocol: str = Field(..., description="mqtt, modbus, lora, wifi")
    firmware_version: Optional[str] = Field("unknown", description="Current firmware version")
    metadata: Optional[Dict[str, Any]] = Field({}, description="Additional device metadata")

class DeviceRegistrationResponse(BaseModel):
    """Device registration response"""
    device_id: str
    mqtt_client_id: str
    status: str
    certificate_url: Optional[str]
    topics: Dict[str, str]
    registered_at: datetime

@app.post("/api/v1/devices/register", response_model=DeviceRegistrationResponse)
async def register_device(request: DeviceRegistrationRequest):
    """
    Register a new IoT device in the Smart Dairy ecosystem.
    
    This endpoint:
    1. Generates unique device ID
    2. Creates device record in database
    3. Sets up MQTT topics and ACLs
    4. Generates client certificates
    5. Returns configuration for device provisioning
    """
    
    # Generate unique device ID
    device_id = f"{request.device_type}_{request.barn_id}_{uuid.uuid4().hex[:8]}"
    mqtt_client_id = f"smartdairy_{device_id}"
    
    # Database connection
    pool = await asyncpg.create_pool(dsn='postgresql://smart_dairy:password@localhost/smart_dairy')
    
    async with pool.acquire() as conn:
        # Check for duplicate serial number
        existing = await conn.fetchval(
            "SELECT device_id FROM iot.devices WHERE serial_number = $1",
            request.serial_number
        )
        if existing:
            raise HTTPException(status_code=400, detail=f"Device with serial {request.serial_number} already registered")
        
        # Create device record
        await conn.execute("""
            INSERT INTO iot.devices (
                device_id, device_type, device_model, serial_number,
                barn_id, location_description, communication_protocol,
                firmware_version, status, metadata, registered_at
            ) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, 'pending_setup', $9, NOW())
        """, 
            device_id, request.device_type, request.device_model, request.serial_number,
            request.barn_id, request.location_description, request.communication_protocol,
            request.firmware_version, request.metadata
        )
        
        # Set up MQTT topics
        topics = generate_device_topics(device_id, request.device_type, request.barn_id)
        
        # Create ACL entries
        await setup_device_acl(conn, device_id, request.device_type, topics)
        
        # Generate certificates for secure communication
        cert_url = None
        if request.communication_protocol in ['mqtt', 'wifi']:
            cert_url = await generate_device_certificates(device_id)
        
        # Generate LoRaWAN keys if applicable
        if request.communication_protocol == 'lora':
            await provision_lorawan_device(conn, device_id, request)
    
    await pool.close()
    
    return DeviceRegistrationResponse(
        device_id=device_id,
        mqtt_client_id=mqtt_client_id,
        status="registered",
        certificate_url=cert_url,
        topics=topics,
        registered_at=datetime.utcnow()
    )

def generate_device_topics(device_id: str, device_type: str, barn_id: str) -> Dict[str, str]:
    """Generate MQTT topic structure for device"""
    base_topic = f"smartdairy/farm/{barn_id}"
    
    topics = {
        "data": f"{base_topic}/{device_type}/{device_id}/data",
        "status": f"{base_topic}/{device_type}/{device_id}/status",
        "config": f"{base_topic}/{device_type}/{device_id}/config",
        "command": f"smartdairy/commands/device/{device_id}",
        "ota": f"smartdairy/system/{device_id}/ota",
        "heartbeat": f"{base_topic}/{device_type}/{device_id}/heartbeat"
    }
    
    return topics

async def setup_device_acl(conn: asyncpg.Connection, device_id: str, device_type: str, topics: Dict[str, str]):
    """Set up MQTT ACL entries for device"""
    
    # Allow publishing to data, status, heartbeat topics
    for topic_key in ['data', 'status', 'heartbeat']:
        await conn.execute("""
            INSERT INTO iot.device_acl (device_id, permission, action, topic, qos)
            VALUES ($1, 'allow', 'publish', $2, 1)
        """, device_id, topics[topic_key])
    
    # Allow subscribing to config, command, ota topics
    for topic_key in ['config', 'command', 'ota']:
        await conn.execute("""
            INSERT INTO iot.device_acl (device_id, permission, action, topic, qos)
            VALUES ($1, 'allow', 'subscribe', $2, 1)
        """, device_id, topics[topic_key])
    
    # Deny all other topics by default (handled by no_match = deny in EMQX)

async def generate_device_certificates(device_id: str) -> str:
    """Generate X.509 certificates for device authentication"""
    import subprocess
    
    cert_dir = f"/opt/smart-dairy/certs/devices/{device_id}"
    subprocess.run(['mkdir', '-p', cert_dir], check=True)
    
    # Generate private key
    subprocess.run([
        'openssl', 'genrsa', '-out', f'{cert_dir}/device.key', '2048'
    ], check=True)
    
    # Generate CSR
    subprocess.run([
        'openssl', 'req', '-new', '-key', f'{cert_dir}/device.key',
        '-out', f'{cert_dir}/device.csr',
        '-subj', f'/CN={device_id}/O=SmartDairy/C=BD'
    ], check=True)
    
    # Sign with CA
    subprocess.run([
        'openssl', 'x509', '-req', '-in', f'{cert_dir}/device.csr',
        '-CA', '/opt/smart-dairy/certs/ca.crt',
        '-CAkey', '/opt/smart-dairy/certs/ca.key',
        '-CAcreateserial', '-out', f'{cert_dir}/device.crt', '-days', '365'
    ], check=True)
    
    # Store certificate path in database
    return f"/api/v1/devices/{device_id}/certificate/download"

async def provision_lorawan_device(conn: asyncpg.Connection, device_id: str, request: DeviceRegistrationRequest):
    """Provision device in LoRaWAN network server"""
    
    # Generate LoRaWAN keys
    import secrets
    dev_eui = secrets.token_hex(16).upper()
    app_key = secrets.token_hex(32).upper()
    
    await conn.execute("""
        INSERT INTO iot.lorawan_devices (
            device_id, dev_eui, app_key, join_type, device_profile_id
        ) VALUES ($1, $2, $3, 'OTAA', 'smart-dairy-default')
    """, device_id, dev_eui, app_key)
    
    # Trigger ChirpStack API call to provision device
    # (Implementation depends on ChirpStack integration)
```

### 3.3 Device Provisioning Checklist

| Step | Task | Responsible | Verification |
|------|------|-------------|--------------|
| 1 | Physical installation and mounting | Farm Technician | Device securely mounted |
| 2 | Power connection and verification | Farm Technician | LED indicators active |
| 3 | Network connectivity test | IT Technician | Ping test successful |
| 4 | Device registration in ERP | System Admin | Device ID generated |
| 5 | MQTT topic configuration | IoT Engineer | Topics accessible |
| 6 | Certificate installation | Security Admin | TLS handshake successful |
| 7 | Test data transmission | IoT Engineer | Sample message received |
| 8 | Configure alert thresholds | Farm Manager | Alerts triggering |
| 9 | Add to monitoring dashboard | System Admin | Device visible in UI |
| 10 | Documentation update | Tech Writer | Asset register updated |

---

## 4. INTEGRATION PROTOCOLS

### 4.1 Protocol Selection Matrix

| Device Category | Primary Protocol | Backup Protocol | Use Case |
|-----------------|------------------|-----------------|----------|
| Milk Meters | Modbus RTU/TCP | MQTT | Real-time production data |
| RFID Readers | MQTT over Ethernet | HTTP | Animal identification |
| Wearables | LoRaWAN | BLE | Long-range, low-power |
| Environmental | MQTT over WiFi | LoRaWAN | Barn climate monitoring |
| Feed Systems | Modbus TCP | MQTT | Feed station control |
| Cold Storage | MQTT over WiFi | Cellular | Temperature alerts |
| Quality Analyzers | OPC-UA | MQTT | Inline quality data |

### 4.2 Modbus Integration

```python
# modbus_device_manager.py
from pymodbus.client import ModbusSerialClient, ModbusTcpClient
from pymodbus.exceptions import ModbusIOException
from typing import Dict, Optional, List
import asyncio

class ModbusDeviceManager:
    """Manager for Modbus RTU and TCP device communication"""
    
    def __init__(self):
        self.clients: Dict[str, ModbusSerialClient] = {}
        self.device_configs = self._load_device_configs()
    
    def _load_device_configs(self) -> Dict:
        """Load Modbus device configurations"""
        return {
            'milk_meter': {
                'registers': {
                    'volume': {'address': 0x0020, 'count': 2, 'type': 'float'},
                    'conductivity': {'address': 0x0024, 'count': 2, 'type': 'float'},
                    'temperature': {'address': 0x0026, 'count': 2, 'type': 'float'},
                    'flow_rate': {'address': 0x0028, 'count': 2, 'type': 'float'},
                    'status': {'address': 0x0010, 'count': 1, 'type': 'uint16'},
                },
                'slave_id_range': range(1, 33)
            },
            'feed_station': {
                'registers': {
                    'dispensed_amount': {'address': 0x0100, 'count': 2, 'type': 'float'},
                    'remaining_feed': {'address': 0x0102, 'count': 2, 'type': 'float'},
                    'station_status': {'address': 0x0104, 'count': 1, 'type': 'uint16'},
                },
                'slave_id_range': range(41, 49)
            }
        }
    
    async def connect_rtu(self, port: str, baudrate: int = 9600) -> ModbusSerialClient:
        """Connect to Modbus RTU bus"""
        client = ModbusSerialClient(
            port=port,
            baudrate=baudrate,
            bytesize=8,
            parity='N',
            stopbits=1,
            timeout=1
        )
        
        if client.connect():
            self.clients[port] = client
            return client
        else:
            raise ConnectionError(f"Failed to connect to Modbus RTU on {port}")
    
    async def read_device_data(self, client: ModbusSerialClient, 
                               device_type: str, 
                               slave_id: int) -> Optional[Dict]:
        """Read all registers from a Modbus device"""
        
        config = self.device_configs.get(device_type)
        if not config:
            raise ValueError(f"Unknown device type: {device_type}")
        
        data = {
            'device_type': device_type,
            'slave_id': slave_id,
            'timestamp': datetime.utcnow().isoformat(),
            'registers': {}
        }
        
        try:
            for register_name, register_config in config['registers'].items():
                result = client.read_holding_registers(
                    address=register_config['address'],
                    count=register_config['count'],
                    slave=slave_id
                )
                
                if result.isError():
                    data['registers'][register_name] = None
                    continue
                
                # Convert based on type
                if register_config['type'] == 'float':
                    value = self._registers_to_float(result.registers)
                elif register_config['type'] == 'uint16':
                    value = result.registers[0]
                elif register_config['type'] == 'uint32':
                    value = (result.registers[0] << 16) | result.registers[1]
                else:
                    value = result.registers
                
                data['registers'][register_name] = value
            
            return data
            
        except ModbusIOException as e:
            print(f"Modbus IO error for slave {slave_id}: {e}")
            return None
    
    def _registers_to_float(self, registers: List[int]) -> float:
        """Convert two 16-bit registers to 32-bit float"""
        import struct
        packed = struct.pack('>HH', registers[0], registers[1])
        return struct.unpack('>f', packed)[0]
    
    async def scan_bus(self, port: str, device_type: str) -> List[int]:
        """Scan Modbus bus for active devices"""
        
        client = self.clients.get(port)
        if not client:
            raise ValueError(f"No connection on port {port}")
        
        config = self.device_configs[device_type]
        active_slaves = []
        
        for slave_id in config['slave_id_range']:
            try:
                result = client.read_holding_registers(
                    address=0x0000, count=1, slave=slave_id
                )
                if not result.isError():
                    active_slaves.append(slave_id)
            except:
                pass
        
        return active_slaves
    
    async def write_device_config(self, client: ModbusSerialClient,
                                   slave_id: int,
                                   register: str,
                                   value: float) -> bool:
        """Write configuration to device"""
        
        # Convert float to registers
        import struct
        packed = struct.pack('>f', value)
        registers = struct.unpack('>HH', packed)
        
        try:
            result = client.write_registers(
                address=0x1000,  # Config register start
                values=list(registers),
                slave=slave_id
            )
            return not result.isError()
        except Exception as e:
            print(f"Failed to write config to slave {slave_id}: {e}")
            return False
```

### 4.3 LoRaWAN Integration

```python
# lorawan_integration.py
import aiohttp
import asyncpg
from dataclasses import dataclass
from typing import Optional, Dict, Any

@dataclass
class LoRaWANMessage:
    """LoRaWAN uplink message structure"""
    device_eui: str
    timestamp: str
    f_cnt: int
    f_port: int
    data: bytes
    rssi: int
    snr: float
    gateway_id: str
    frequency: float

class LoRaWANIntegration:
    """Integration with ChirpStack LoRaWAN Network Server"""
    
    def __init__(self, api_url: str, api_token: str, db_pool: asyncpg.Pool):
        self.api_url = api_url
        self.api_token = api_token
        self.db_pool = db_pool
        self.application_id = "smart-dairy-farm"
    
    async def handle_uplink(self, message: LoRaWANMessage):
        """Process LoRaWAN uplink message"""
        
        # Decode payload based on device profile
        device_profile = await self._get_device_profile(message.device_eui)
        decoded_data = self._decode_payload(message.data, device_profile)
        
        # Enrich with device metadata
        async with self.db_pool.acquire() as conn:
            device = await conn.fetchrow(
                """SELECT device_id, barn_id, device_type FROM iot.devices d
                   JOIN iot.lorawan_devices l ON d.device_id = l.device_id
                   WHERE l.dev_eui = $1""",
                message.device_eui
            )
        
        if not device:
            print(f"Unknown device: {message.device_eui}")
            return
        
        # Construct MQTT-like message
        iot_message = {
            'schema_version': '1.0',
            'device_id': device['device_id'],
            'device_type': device['device_type'],
            'timestamp': message.timestamp,
            'location': {'barn_id': device['barn_id']},
            'data': decoded_data,
            'metadata': {
                'rssi': message.rssi,
                'snr': message.snr,
                'gateway_id': message.gateway_id,
                'frequency': message.frequency,
                'f_cnt': message.f_cnt
            }
        }
        
        # Publish to MQTT broker
        await self._publish_to_mqtt(iot_message)
    
    def _decode_payload(self, payload: bytes, profile: str) -> Dict[str, Any]:
        """Decode LoRaWAN payload based on device profile"""
        
        if profile == 'wearable_activity':
            # Decode activity monitor payload
            # Example: [activity_level: 1 byte, rumination: 2 bytes, temp: 2 bytes]
            return {
                'activity_level': payload[0],
                'rumination_minutes': int.from_bytes(payload[1:3], 'big'),
                'body_temperature': int.from_bytes(payload[3:5], 'big') / 10.0
            }
        
        elif profile == 'environmental':
            # Decode environmental sensor
            return {
                'temperature': int.from_bytes(payload[0:2], 'big', signed=True) / 100.0,
                'humidity': payload[2],
                'battery': payload[3]
            }
        
        elif profile == 'gas_sensor':
            return {
                'ammonia_ppm': int.from_bytes(payload[0:2], 'big'),
                'co2_ppm': int.from_bytes(payload[2:4], 'big'),
                'battery': payload[4]
            }
        
        else:
            # Raw hex for unknown profiles
            return {'raw': payload.hex()}
    
    async def provision_device(self, device_id: str, device_eui: str, 
                               app_key: str, device_profile_id: str) -> bool:
        """Provision new device in ChirpStack"""
        
        async with aiohttp.ClientSession() as session:
            headers = {
                'Authorization': f'Bearer {self.api_token}',
                'Content-Type': 'application/json'
            }
            
            payload = {
                'device': {
                    'applicationId': self.application_id,
                    'devEui': device_eui,
                    'name': device_id,
                    'deviceProfileId': device_profile_id,
                    'skipFcntCheck': False
                }
            }
            
            async with session.post(
                f'{self.api_url}/api/devices',
                headers=headers,
                json=payload
            ) as response:
                if response.status == 200:
                    # Activate device with APP key
                    await self._activate_device(session, headers, device_eui, app_key)
                    return True
                else:
                    error = await response.text()
                    print(f"Failed to provision device: {error}")
                    return False
    
    async def _activate_device(self, session: aiohttp.ClientSession, 
                               headers: Dict, dev_eui: str, app_key: str):
        """Activate device with OTAA keys"""
        
        payload = {
            'deviceKeys': {
                'devEui': dev_eui,
                'nwkKey': app_key,
                'appKey': app_key
            }
        }
        
        async with session.post(
            f'{self.api_url}/api/devices/{dev_eui}/keys',
            headers=headers,
            json=payload
        ) as response:
            if response.status != 200:
                error = await response.text()
                print(f"Failed to activate device: {error}")
```

---

## 5. DEVICE COMMUNICATION STANDARDS

### 5.1 MQTT Message Schema

```json
{
  "$schema": "http://json-schema.org/draft-07/schema#",
  "title": "Smart Dairy IoT Message",
  "type": "object",
  "required": ["schema_version", "device_id", "device_type", "timestamp", "data"],
  "properties": {
    "schema_version": {
      "type": "string",
      "enum": ["1.0"],
      "description": "Message schema version"
    },
    "device_id": {
      "type": "string",
      "pattern": "^[a-z_]+_[a-z0-9]+_[a-f0-9]{8}$",
      "description": "Unique device identifier"
    },
    "device_type": {
      "type": "string",
      "enum": [
        "milk_meter",
        "rfid_reader",
        "environmental_sensor",
        "activity_tracker",
        "feed_station",
        "cold_storage_monitor",
        "quality_analyzer"
      ]
    },
    "timestamp": {
      "type": "string",
      "format": "date-time",
      "description": "ISO 8601 timestamp in UTC"
    },
    "location": {
      "type": "object",
      "required": ["barn_id"],
      "properties": {
        "barn_id": {"type": "string"},
        "stall_id": {"type": "string"},
        "coordinates": {
          "type": "object",
          "properties": {
            "latitude": {"type": "number"},
            "longitude": {"type": "number"}
          }
        }
      }
    },
    "data": {
      "type": "object",
      "description": "Device-specific data payload"
    },
    "metadata": {
      "type": "object",
      "properties": {
        "firmware_version": {"type": "string"},
        "signal_strength": {"type": "number"},
        "battery_level": {"type": "number", "minimum": 0, "maximum": 100},
        "gateway_id": {"type": "string"}
      }
    }
  }
}
```

### 5.2 Device-Specific Payloads

#### Milk Meter Data

```json
{
  "schema_version": "1.0",
  "device_id": "milk_meter_barn_a_a1b2c3d4",
  "device_type": "milk_meter",
  "timestamp": "2026-08-15T08:30:15.123Z",
  "location": {
    "barn_id": "barn_a",
    "stall_id": "stall_03"
  },
  "data": {
    "animal_rfid": "E200341502001080",
    "session": "morning",
    "volume_liters": 18.5,
    "flow_rate_avg": 2.5,
    "flow_rate_max": 4.2,
    "duration_seconds": 450,
    "milk_temperature": 37.2,
    "conductivity_ms_cm": 4.8,
    "fat_percentage": 4.2,
    "snf_percentage": 8.5,
    "protein_percentage": 3.4
  },
  "metadata": {
    "firmware_version": "2.1.3",
    "signal_strength": -65,
    "device_uptime_seconds": 86400
  }
}
```

#### Environmental Sensor Data

```json
{
  "schema_version": "1.0",
  "device_id": "env_sensor_barn_a_e5f6g7h8",
  "device_type": "environmental_sensor",
  "timestamp": "2026-08-15T08:30:00.000Z",
  "location": {
    "barn_id": "barn_a",
    "zone": "milking_parlor"
  },
  "data": {
    "temperature_celsius": 28.5,
    "humidity_percent": 72.0,
    "dew_point": 22.3,
    "ammonia_ppm": 8.5,
    "co2_ppm": 1200,
    "light_lux": 350,
    "air_pressure_hpa": 1013.25
  },
  "metadata": {
    "firmware_version": "1.4.2",
    "battery_level": 85,
    "sensor_status": "normal"
  }
}
```

#### Activity Tracker Data

```json
{
  "schema_version": "1.0",
  "device_id": "activity_tracker_barn_b_i9j0k1l2",
  "device_type": "activity_tracker",
  "timestamp": "2026-08-15T08:30:00.000Z",
  "location": {
    "barn_id": "barn_b"
  },
  "data": {
    "animal_rfid": "E200341502001081",
    "activity_level": 45,
    "activity_index": "normal",
    "rumination_minutes": 420,
    "rumination_cycles": 12,
    "body_temperature": 38.7,
    "step_count": 2450,
    "lying_time_minutes": 180,
    "eating_time_minutes": 120
  },
  "metadata": {
    "firmware_version": "3.0.1",
    "battery_level": 72,
    "signal_quality": "good"
  }
}
```

---

## 6. SECURITY IMPLEMENTATION

### 6.1 Device Authentication

| Method | Protocol | Use Case |
|--------|----------|----------|
| **X.509 Certificates** | MQTT over TLS | High-security devices (milk meters, RFID) |
| **Username/Password** | MQTT | Standard devices with PostgreSQL auth |
| **Token-based** | HTTP API | Device management and provisioning |
| **LoRaWAN Keys** | LoRaWAN OTAA | Wearable and environmental sensors |

### 6.2 Certificate Lifecycle Management

```python
# certificate_lifecycle.py
from datetime import datetime, timedelta
import asyncpg
import subprocess

class CertificateLifecycleManager:
    """Manage device certificates throughout their lifecycle"""
    
    def __init__(self, db_pool: asyncpg.Pool, ca_cert_path: str, ca_key_path: str):
        self.db_pool = db_pool
        self.ca_cert = ca_cert_path
        self.ca_key = ca_key_path
    
    async def issue_certificate(self, device_id: str, validity_days: int = 365) -> str:
        """Issue new certificate for device"""
        
        cert_dir = f"/opt/smart-dairy/certs/devices/{device_id}"
        
        # Generate private key
        subprocess.run([
            'openssl', 'genrsa', '-out', f'{cert_dir}/device.key', '2048'
        ], check=True, capture_output=True)
        
        # Create CSR
        subprocess.run([
            'openssl', 'req', '-new', '-key', f'{cert_dir}/device.key',
            '-out', f'{cert_dir}/device.csr',
            '-subj', f'/CN={device_id}/O=SmartDairy/C=BD'
        ], check=True, capture_output=True)
        
        # Sign certificate
        not_after = (datetime.utcnow() + timedelta(days=validity_days)).strftime('%Y%m%d%H%M%SZ')
        subprocess.run([
            'openssl', 'x509', '-req', '-in', f'{cert_dir}/device.csr',
            '-CA', self.ca_cert, '-CAkey', self.ca_key,
            '-CAcreateserial', '-out', f'{cert_dir}/device.crt',
            '-enddate', not_after, '-sha256'
        ], check=True, capture_output=True)
        
        # Store in database
        async with self.db_pool.acquire() as conn:
            await conn.execute("""
                INSERT INTO iot.device_certificates 
                (device_id, certificate_path, issued_at, expires_at, status)
                VALUES ($1, $2, NOW(), $3, 'active')
                ON CONFLICT (device_id) DO UPDATE SET
                    certificate_path = EXCLUDED.certificate_path,
                    issued_at = EXCLUDED.issued_at,
                    expires_at = EXCLUDED.expires_at,
                    status = 'active'
            """, device_id, f"{cert_dir}/device.crt", 
                datetime.utcnow() + timedelta(days=validity_days))
        
        return f"{cert_dir}/device.crt"
    
    async def revoke_certificate(self, device_id: str, reason: str = "unspecified"):
        """Revoke device certificate"""
        
        async with self.db_pool.acquire() as conn:
            # Update database
            await conn.execute("""
                UPDATE iot.device_certificates 
                SET status = 'revoked', revoked_at = NOW(), revoke_reason = $2
                WHERE device_id = $1
            """, device_id, reason)
            
            # Add to CRL (Certificate Revocation List)
            cert_path = await conn.fetchval(
                "SELECT certificate_path FROM iot.device_certificates WHERE device_id = $1",
                device_id
            )
            
            if cert_path:
                # Update CRL
                self._update_crl(cert_path, reason)
    
    async def check_certificate_expiry(self, warning_days: int = 30):
        """Check for certificates expiring soon and trigger renewal"""
        
        async with self.db_pool.acquire() as conn:
            expiring_certs = await conn.fetch("""
                SELECT device_id, certificate_path, expires_at
                FROM iot.device_certificates
                WHERE status = 'active'
                AND expires_at < NOW() + INTERVAL '$1 days'
            """, warning_days)
        
        for cert in expiring_certs:
            print(f"Certificate for {cert['device_id']} expires on {cert['expires_at']}")
            # Trigger renewal workflow
            await self._trigger_renewal(cert['device_id'])
    
    def _update_crl(self, cert_path: str, reason: str):
        """Update Certificate Revocation List"""
        # Implementation depends on PKI infrastructure
        pass
    
    async def _trigger_renewal(self, device_id: str):
        """Trigger certificate renewal workflow"""
        # Send notification, schedule renewal, etc.
        pass
```

---

## 7. DEVICE MANAGEMENT

### 7.1 Device Lifecycle States

```
┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐
│  PENDING │───▶│  ACTIVE  │───▶│ MAINTAIN │───▶│ SUSPENDED│───▶│ RETIRED  │
│  SETUP   │    │          │    │   ENANCE │    │          │    │          │
└──────────┘    └────┬─────┘    └────┬─────┘    └──────────┘    └──────────┘
      │              │               │                                   ▲
      │              │               └───────────────────────────────────┤
      │              │                    Return to service             │
      │              │                                                   │
      │              └───────────────────────────────────────────────────┘
      │                              Decommission
      │
      ▼
┌──────────┐
│  ERROR   │
│  STATE   │
└──────────┘
```

### 7.2 Device Health Monitoring

```python
# device_health_monitor.py
import asyncio
import asyncpg
from datetime import datetime, timedelta
from typing import Dict, List

class DeviceHealthMonitor:
    """Monitor device health and connectivity"""
    
    def __init__(self, db_pool: asyncpg.Pool, mqtt_client):
        self.db_pool = db_pool
        self.mqtt = mqtt_client
        self.health_thresholds = {
            'heartbeat_timeout': 300,  # 5 minutes
            'battery_warning': 20,     # 20%
            'signal_warning': -85,     # dBm
            'error_rate_threshold': 10 # errors per hour
        }
    
    async def check_device_health(self):
        """Run periodic health checks on all devices"""
        
        async with self.db_pool.acquire() as conn:
            devices = await conn.fetch(
                "SELECT device_id, device_type, last_heartbeat, status FROM iot.devices WHERE status = 'active'"
            )
        
        for device in devices:
            issues = []
            
            # Check heartbeat
            if device['last_heartbeat']:
                time_since_heartbeat = (datetime.utcnow() - device['last_heartbeat']).total_seconds()
                if time_since_heartbeat > self.health_thresholds['heartbeat_timeout']:
                    issues.append({
                        'type': 'heartbeat_timeout',
                        'severity': 'critical',
                        'message': f"No heartbeat for {time_since_heartbeat} seconds"
                    })
            
            # Check battery if applicable
            battery_level = await self._get_battery_level(device['device_id'])
            if battery_level and battery_level < self.health_thresholds['battery_warning']:
                issues.append({
                    'type': 'low_battery',
                    'severity': 'warning',
                    'message': f"Battery level at {battery_level}%"
                })
            
            # Check signal strength
            signal_strength = await self._get_signal_strength(device['device_id'])
            if signal_strength and signal_strength < self.health_thresholds['signal_warning']:
                issues.append({
                    'type': 'poor_signal',
                    'severity': 'warning',
                    'message': f"Signal strength {signal_strength} dBm"
                })
            
            # Process issues
            if issues:
                await self._handle_device_issues(device['device_id'], issues)
    
    async def update_device_heartbeat(self, device_id: str):
        """Update device heartbeat timestamp"""
        
        async with self.db_pool.acquire() as conn:
            await conn.execute(
                "UPDATE iot.devices SET last_heartbeat = NOW() WHERE device_id = $1",
                device_id
            )
    
    async def _handle_device_issues(self, device_id: str, issues: List[Dict]):
        """Handle identified device issues"""
        
        # Store issues in database
        async with self.db_pool.acquire() as conn:
            for issue in issues:
                await conn.execute("""
                    INSERT INTO iot.device_issues 
                    (device_id, issue_type, severity, message, detected_at)
                    VALUES ($1, $2, $3, $4, NOW())
                """, device_id, issue['type'], issue['severity'], issue['message'])
        
        # Publish alert for critical issues
        critical_issues = [i for i in issues if i['severity'] == 'critical']
        if critical_issues:
            await self.mqtt.publish(
                f"smartdairy/alerts/device/{device_id}",
                {
                    'alert_type': 'device_health',
                    'device_id': device_id,
                    'issues': critical_issues,
                    'timestamp': datetime.utcnow().isoformat()
                }
            )
```

---

## 8. EDGE GATEWAY INTEGRATION

### 8.1 Gateway Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      EDGE GATEWAY ARCHITECTURE                               │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐ │
│  │                    EDGE GATEWAY (Raspberry Pi 4 / Intel NUC)           │ │
│  │                                                                        │ │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐                │ │
│  │  │   Device     │  │   Message    │  │    Edge      │                │ │
│  │  │   Connectors │  │   Router     │  │  Processing  │                │ │
│  │  │              │  │              │  │              │                │ │
│  │  │ • Modbus RTU │  │ • Topic      │  │ • Filtering  │                │ │
│  │  │ • Modbus TCP │  │   Mapping    │  │ • Aggregation│                │ │
│  │  │ • LoRaWAN    │  │ • QoS        │  │ • Compression│                │ │
│  │  │ • Serial     │  │   Management │  │ • Buffering  │                │ │
│  │  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘                │ │
│  │         │                 │                 │                         │ │
│  │         └─────────────────┴─────────────────┘                         │ │
│  │                           │                                           │ │
│  │                  ┌────────▼────────┐                                  │ │
│  │                  │  MQTT Client    │                                  │ │
│  │                  │  (paho-mqtt)    │                                  │ │
│  │                  │                 │                                  │ │
│  │                  │ • TLS 1.3       │                                  │ │
│  │                  │ • Client Certs  │                                  │ │
│  │                  │ • Auto-reconnect│                                  │ │
│  │                  └────────┬────────┘                                  │ │
│  │                           │                                           │ │
│  │                  ┌────────▼────────┐                                  │ │
│  │                  │  Local Storage  │                                  │ │
│  │                  │  (SQLite/Redis) │                                  │ │
│  │                  └─────────────────┘                                  │ │
│  │                                                                        │ │
│  └───────────────────────────────────────────────────────────────────────┘ │
│                                    │                                         │
│                                    │ WiFi/Ethernet/4G                        │
│                                    ▼                                         │
│  ┌───────────────────────────────────────────────────────────────────────┐ │
│  │                         CLOUD MQTT BROKER (EMQX)                       │ │
│  └───────────────────────────────────────────────────────────────────────┘ │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 8.2 Gateway Configuration

```yaml
# gateway_config.yaml
gateway:
  id: "farm_gateway_barn_a"
  location: "barn_a"
  hardware: "raspberry_pi_4_8gb"
  
connectors:
  modbus_rtu:
    enabled: true
    port: "/dev/ttyUSB0"
    baudrate: 9600
    devices:
      - type: "milk_meter"
        slave_id_range: [1, 16]
        poll_interval: 5
      - type: "feed_station"
        slave_id_range: [41, 48]
        poll_interval: 30
  
  lorawan:
    enabled: true
    gateway_id: "lorawan_gw_barn_a"
    server: "lorawan.smartdairy.local"
    devices:
      - profile: "wearable_activity"
        app_id: "smart-dairy-wearables"
      - profile: "environmental"
        app_id: "smart-dairy-env"
  
  ethernet:
    enabled: true
    devices:
      - type: "rfid_reader"
        ip_range: ["192.168.1.101", "192.168.1.120"]
        protocol: "llrp"
      - type: "quality_analyzer"
        ip_range: ["192.168.1.201", "192.168.1.202"]
        protocol: "opc_ua"

mqtt:
  broker: "mqtt.smartdairybd.com"
  port: 8883
  tls:
    enabled: true
    ca_cert: "/opt/smart-dairy/certs/ca.crt"
    client_cert: "/opt/smart-dairy/certs/gateway.crt"
    client_key: "/opt/smart-dairy/certs/gateway.key"
  
  # Buffering configuration
  buffering:
    enabled: true
    max_messages: 10000
    flush_interval: 30
    storage_path: "/var/lib/smart-dairy/buffer"

edge_processing:
  # Data filtering
  filters:
    - type: "range_filter"
      parameter: "temperature"
      min: -10
      max: 50
    - type: "rate_limiter"
      max_messages_per_second: 100
  
  # Data aggregation
  aggregation:
    - type: "average"
      window: 60
      parameters: ["temperature", "humidity"]
  
  # Compression for batch uploads
  compression:
    enabled: true
    algorithm: "gzip"
    threshold_bytes: 1024
```

---

## 9. TROUBLESHOOTING & DIAGNOSTICS

### 9.1 Common Issues

| Issue | Symptoms | Diagnosis | Resolution |
|-------|----------|-----------|------------|
| **Device Offline** | No heartbeat, no data | Check network, power | Restart device, check cables |
| **Intermittent Data** | Missing readings | Check signal strength | Adjust antenna, reposition |
| **Incorrect Values** | Out-of-range data | Calibration check | Recalibrate device |
| **High Latency** | Delayed data arrival | Network congestion | Reduce poll frequency |
| **Auth Failures** | Connection rejected | Certificate expiry | Renew certificate |

### 9.2 Diagnostic Commands

```bash
#!/bin/bash
# device_diagnostics.sh

# Check device connectivity
check_device_ping() {
    local ip=$1
    ping -c 3 $ip > /dev/null 2>&1
    if [ $? -eq 0 ]; then
        echo "✓ Device at $ip is reachable"
    else
        echo "✗ Device at $ip is unreachable"
    fi
}

# Check Modbus communication
check_modbus() {
    local port=$1
    local slave_id=$2
    
    # Use mbpoll or similar tool
    mbpoll -a $slave_id -r 1 -c 1 $port
}

# Check MQTT broker connection
check_mqtt() {
    local broker=$1
    local port=$2
    
    mosquitto_sub -h $broker -p $port -t "test" -W 5
    if [ $? -eq 0 ]; then
        echo "✓ MQTT broker is accessible"
    else
        echo "✗ MQTT broker connection failed"
    fi
}

# Check certificate validity
check_certificate() {
    local cert_file=$1
    
    openssl x509 -in $cert_file -noout -dates
    openssl x509 -in $cert_file -noout -checkend 86400
    if [ $? -eq 0 ]; then
        echo "✓ Certificate is valid for more than 1 day"
    else
        echo "✗ Certificate expires within 1 day"
    fi
}

# Main diagnostic routine
run_diagnostics() {
    echo "=== Smart Dairy Device Diagnostics ==="
    echo "Date: $(date)"
    echo ""
    
    # Check gateway connectivity
    echo "1. Checking Gateway..."
    check_device_ping "localhost"
    
    # Check MQTT broker
    echo ""
    echo "2. Checking MQTT Broker..."
    check_mqtt "mqtt.smartdairybd.com" "8883"
    
    # Check database
    echo ""
    echo "3. Checking Database..."
    psql -h postgres.smartdairy.local -U iot_service -c "SELECT COUNT(*) FROM iot.devices;"
    
    echo ""
    echo "=== Diagnostics Complete ==="
}

run_diagnostics
```

---

## 10. APPENDICES

### Appendix A: Device Database Schema

```sql
-- Complete device management schema

CREATE TABLE iot.devices (
    id SERIAL PRIMARY KEY,
    device_id VARCHAR(100) UNIQUE NOT NULL,
    device_type VARCHAR(50) NOT NULL,
    device_model VARCHAR(100),
    serial_number VARCHAR(100) UNIQUE,
    barn_id VARCHAR(50) NOT NULL,
    location_description TEXT,
    communication_protocol VARCHAR(50),
    firmware_version VARCHAR(50),
    status VARCHAR(50) DEFAULT 'pending_setup',
    last_heartbeat TIMESTAMP,
    registered_at TIMESTAMP DEFAULT NOW(),
    metadata JSONB DEFAULT '{}'::jsonb
);

CREATE TABLE iot.device_certificates (
    id SERIAL PRIMARY KEY,
    device_id VARCHAR(100) REFERENCES iot.devices(device_id),
    certificate_path VARCHAR(500),
    issued_at TIMESTAMP,
    expires_at TIMESTAMP,
    revoked_at TIMESTAMP,
    revoke_reason VARCHAR(100),
    status VARCHAR(50) DEFAULT 'active'
);

CREATE TABLE iot.device_acl (
    id SERIAL PRIMARY KEY,
    device_id VARCHAR(100) REFERENCES iot.devices(device_id),
    permission VARCHAR(10) CHECK (permission IN ('allow', 'deny')),
    action VARCHAR(10) CHECK (action IN ('publish', 'subscribe', 'all')),
    topic VARCHAR(255),
    qos INTEGER DEFAULT 1,
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE iot.device_issues (
    id SERIAL PRIMARY KEY,
    device_id VARCHAR(100) REFERENCES iot.devices(device_id),
    issue_type VARCHAR(100),
    severity VARCHAR(20),
    message TEXT,
    detected_at TIMESTAMP DEFAULT NOW(),
    resolved_at TIMESTAMP,
    resolution TEXT
);

CREATE TABLE iot.lorawan_devices (
    id SERIAL PRIMARY KEY,
    device_id VARCHAR(100) REFERENCES iot.devices(device_id),
    dev_eui VARCHAR(16) UNIQUE,
    app_key VARCHAR(32),
    join_type VARCHAR(10) DEFAULT 'OTAA',
    device_profile_id VARCHAR(100),
    last_joined_at TIMESTAMP
);
```

### Appendix B: Bill of Materials - IoT Devices

| Category | Device | Qty | Unit Price (৳) | Total (৳) |
|----------|--------|-----|----------------|-----------|
| **Milk Meters** | DeLaval MM27BC | 16 | 85,000 | 1,360,000 |
| **RFID Readers** | Impinj Speedway R420 | 20 | 75,000 | 1,500,000 |
| **Smart Collars** | Afimilk Silent Herdsman | 50 | 25,000 | 1,250,000 |
| **Env. Sensors** | Bosch BME680 Kit | 40 | 8,500 | 340,000 |
| **Gas Sensors** | Sensirion SGP30 | 20 | 12,000 | 240,000 |
| **Gateways** | Raspberry Pi 4 (8GB) | 8 | 12,000 | 96,000 |
| **LoRaWAN GW** | Multitech Conduit | 4 | 65,000 | 260,000 |
| **Cold Monitors** | Emerson GO Real-Time | 10 | 18,000 | 180,000 |
| **Installation** | Professional Install | 1 | 500,000 | 500,000 |
| **TOTAL** | | | | **5,726,000** |

---

**END OF IOT DEVICE INTEGRATION SPECIFICATION**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | August 15, 2026 | IoT Engineer | Initial version |
