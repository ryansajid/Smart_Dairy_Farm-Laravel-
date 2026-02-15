# I-013: IoT Device Management

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | I-013 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | IoT Engineer |
| **Owner** | IoT Architect |
| **Reviewer** | DevOps Lead |
| **Status** | Draft |
| **Classification** | Internal Use |

### Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | 2026-01-31 | IoT Engineer | Initial document creation |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Device Provisioning](#2-device-provisioning)
3. [Device Registry](#3-device-registry)
4. [OTA Firmware Updates](#4-ota-firmware-updates)
5. [Configuration Management](#5-configuration-management)
6. [Monitoring & Diagnostics](#6-monitoring--diagnostics)
7. [Remote Troubleshooting](#7-remote-troubleshooting)
8. [Device Retirement](#8-device-retirement)
9. [Inventory Management](#9-inventory-management)
10. [Geolocation](#10-geolocation)
11. [Cost Management](#11-cost-management)
12. [Integration with ERP](#12-integration-with-erp)
13. [Appendices](#13-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the comprehensive IoT Device Management framework for Smart Dairy Ltd., covering the entire device lifecycle from manufacturing to retirement. It establishes standards, procedures, and technical specifications for managing diverse IoT devices deployed across dairy farm operations.

### 1.2 Scope

This document applies to all IoT devices within the Smart Dairy ecosystem:

- **Milk Meters**: Automated milk yield measurement devices
- **Environmental Sensors**: Temperature, humidity, air quality monitors
- **RFID Readers**: Animal identification and tracking systems
- **Wearables**: Health monitoring collars and tags for livestock
- **Gateways**: Edge computing and connectivity hubs

### 1.3 Device Lifecycle Overview

```
+-------------------------------------------------------------------------+
|                    IoT DEVICE LIFECYCLE                                 |
+-------------------------------------------------------------------------+
|                                                                         |
|  +----------+   +----------+   +----------+   +----------+   +--------+ |
|  |Manufact. |-->|Provision |-->| Configure|-->| Monitor  |-->|Maintain| |
|  |  Phase   |   |   & Reg  |   |  & Deploy|   |  & Operate|   | & Update| |
|  +----------+   +----------+   +----------+   +----------+   +--------+ |
|       |              |              |              |              |     |
|       v              v              v              v              v     |
|   +----------------------------------------------------------------+    |
|   |                    DECOMMISSIONING                              |    |
|   |  - Data archival  - Certificate revocation  - Asset disposal   |    |
|   +----------------------------------------------------------------+    |
|                                                                         |
+-------------------------------------------------------------------------+
```

### 1.4 Key Principles

| Principle | Description |
|-----------|-------------|
| **Security First** | All devices use X.509 certificates and secure communication |
| **Scalability** | Architecture supports 10,000+ devices across multiple farms |
| **Zero-Touch Provisioning** | Devices provision automatically without manual intervention |
| **Resilience** | Graceful handling of connectivity issues and partial failures |
| **Auditability** | Complete audit trail for all device operations |

---

## 2. Device Provisioning

### 2.1 Manufacturing Provisioning

#### 2.1.1 Factory Provisioning Process

During manufacturing, each device receives:

1. **Unique Device Identity**
   - Device Serial Number (format: `SD-{TYPE}-{YEAR}-{SEQUENCE}`)
   - Hardware ID burned into secure element
   - Initial firmware version

2. **Security Credentials**
   - Device-specific X.509 certificate
   - Private key in hardware security module (HSM)
   - Root CA certificate chain

3. **Baseline Configuration**
   - Default communication parameters
   - Bootstrap server endpoints
   - Initial sensor calibration data

#### 2.1.2 Manufacturing Data Package

```json
{
  "manufacturing_data": {
    "device_id": "SD-MM-2026-001234",
    "device_type": "milk_meter",
    "hardware_version": "2.1.0",
    "firmware_version": "1.0.0-factory",
    "manufacturing_date": "2026-01-15T08:30:00Z",
    "batch_number": "BATCH-2026-Q1-0892",
    "factory_location": "Shenzhen, China",
    "certificates": {
      "device_cert_pem": "-----BEGIN CERTIFICATE-----",
      "public_key_pem": "-----BEGIN PUBLIC KEY-----",
      "root_ca_cert": "-----BEGIN CERTIFICATE-----"
    },
    "sensors": [
      {
        "type": "flow_sensor",
        "model": "FS-2026-A",
        "calibration_date": "2026-01-15T08:25:00Z",
        "calibration_coefficients": {
          "offset": 0.02,
          "gain": 1.0015
        }
      }
    ],
    "compliance": {
      "ce_mark": true,
      "fcc_id": "ABC123456",
      "ip_rating": "IP67"
    }
  }
}
```

### 2.2 Just-in-Time Registration

#### 2.2.1 JIT Registration Flow

```python
# device_provisioning_service.py
"""
Smart Dairy Device Provisioning Service
Handles Just-in-Time device registration and onboarding
"""

import asyncio
import json
import logging
from datetime import datetime, timedelta
from typing import Dict, Optional, List
from dataclasses import dataclass
from enum import Enum

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)


class DeviceStatus(Enum):
    MANUFACTURED = "manufactured"
    PROVISIONED = "provisioned"
    REGISTERED = "registered"
    ACTIVE = "active"
    SUSPENDED = "suspended"
    DECOMMISSIONED = "decommissioned"


class DeviceType(Enum):
    MILK_METER = "milk_meter"
    ENV_SENSOR = "environmental_sensor"
    RFID_READER = "rfid_reader"
    WEARABLE = "wearable"
    GATEWAY = "gateway"


@dataclass
class DeviceIdentity:
    device_id: str
    device_type: DeviceType
    hardware_version: str
    serial_number: str
    manufacturing_date: datetime
    certificate_thumbprint: str


class JITProvisioningService:
    """
    Handles Just-in-Time device registration for Smart Dairy IoT devices.
    """
    
    def __init__(self, config: Dict):
        self.config = config
        self.iot_backend = config.get('iot_backend', 'aws')
        
    async def register_device(self, device_identity: DeviceIdentity, 
                             farm_id: str,
                             claim_token: str) -> Dict:
        """
        Register a new device using Just-in-Time provisioning.
        """
        try:
            # Validate claim token
            if not await self._validate_claim_token(device_identity.device_id, claim_token):
                raise ValueError(f"Invalid claim token for device {device_identity.device_id}")
            
            # Create device in IoT platform
            if self.iot_backend == 'aws':
                result = await self._register_aws_iot(device_identity, farm_id)
            else:
                result = await self._register_azure_iot(device_identity, farm_id)
            
            # Update device registry
            await self._update_device_registry(device_identity, farm_id, DeviceStatus.REGISTERED)
            
            # Initialize device state
            await self._initialize_device_state(device_identity, farm_id)
            
            logger.info(f"Device {device_identity.device_id} registered successfully")
            return result
            
        except Exception as e:
            logger.error(f"Device registration failed: {e}")
            raise
    
    async def _register_aws_iot(self, device_identity: DeviceIdentity, 
                                farm_id: str) -> Dict:
        """Register device in AWS IoT Core"""
        
        # Returns connection credentials
        return {
            'device_id': device_identity.device_id,
            'certificate_pem': "-----BEGIN CERTIFICATE-----",
            'private_key': "-----BEGIN RSA PRIVATE KEY-----",
            'endpoint': self.config.get('aws_iot_endpoint'),
            'status': DeviceStatus.REGISTERED.value
        }
    
    async def _register_azure_iot(self, device_identity: DeviceIdentity,
                                  farm_id: str) -> Dict:
        """Register device in Azure IoT Hub"""
        
        return {
            'device_id': device_identity.device_id,
            'connection_string': "HostName=...",
            'primary_key': "device_key_placeholder",
            'hub_hostname': self.config.get('azure_iot_hub_hostname'),
            'status': DeviceStatus.REGISTERED.value
        }
    
    async def _validate_claim_token(self, device_id: str, claim_token: str) -> bool:
        """Validate the device claim token"""
        return True
    
    async def _update_device_registry(self, device_identity: DeviceIdentity,
                                      farm_id: str, status: DeviceStatus):
        """Update centralized device registry"""
        registry_entry = {
            'device_id': device_identity.device_id,
            'device_type': device_identity.device_type.value,
            'farm_id': farm_id,
            'status': status.value,
            'registration_timestamp': datetime.utcnow().isoformat()
        }
        logger.info(f"Registry updated for {device_identity.device_id}")
    
    async def _initialize_device_state(self, device_identity: DeviceIdentity,
                                       farm_id: str):
        """Initialize device shadow with default configuration"""
        initial_state = {
            'telemetry_interval': 60,
            'heartbeat_interval': 300,
            'sensor_calibration': self._get_default_calibration(device_identity.device_type)
        }
        logger.info(f"Device state initialized for {device_identity.device_id}")
    
    def _get_default_calibration(self, device_type: DeviceType) -> Dict:
        """Get default calibration parameters for device type"""
        calibrations = {
            DeviceType.MILK_METER: {
                'flow_offset': 0.0,
                'flow_gain': 1.0,
                'temperature_offset': 0.0
            },
            DeviceType.ENV_SENSOR: {
                'temperature_offset': 0.0,
                'humidity_offset': 0.0,
                'pressure_offset': 0.0
            },
            DeviceType.RFID_READER: {
                'read_power': 20.0,
                'sensitivity': -70.0
            },
            DeviceType.WEARABLE: {
                'accelerometer_range': 4,
                'gps_update_interval': 60
            },
            DeviceType.GATEWAY: {
                'max_devices': 100,
                'uplink_mode': 'automatic'
            }
        }
        return calibrations.get(device_type, {})


class ProvisioningWorkflow:
    """Orchestrates the complete device provisioning workflow"""
    
    def __init__(self, provisioning_service: JITProvisioningService):
        self.service = provisioning_service
        
    async def execute_provisioning_workflow(self, 
                                           device_package: Dict,
                                           farm_assignment: Dict) -> Dict:
        """
        Execute complete provisioning workflow:
        1. Validate device package integrity
        2. Verify manufacturing certificates
        3. Check device against approved inventory
        4. Create device identity
        5. Register with IoT platform
        6. Generate connection credentials
        7. Configure initial device state
        8. Send activation notification
        """
        workflow_result = {
            'workflow_id': f"prov-{datetime.utcnow().strftime('%Y%m%d%H%M%S')}",
            'device_id': device_package['device_id'],
            'status': 'started',
            'steps': []
        }
        
        try:
            # Step 1: Validate package
            step1 = await self._validate_package(device_package)
            workflow_result['steps'].append({'step': 'validate', 'result': step1})
            
            # Step 2: Verify certificates
            step2 = await self._verify_certificates(device_package['certificates'])
            workflow_result['steps'].append({'step': 'verify_certs', 'result': step2})
            
            # Step 3: Check inventory
            step3 = await self._check_inventory(device_package['device_id'])
            workflow_result['steps'].append({'step': 'inventory_check', 'result': step3})
            
            # Step 4: Create identity
            device_identity = DeviceIdentity(
                device_id=device_package['device_id'],
                device_type=DeviceType(device_package['device_type']),
                hardware_version=device_package['hardware_version'],
                serial_number=device_package['serial_number'],
                manufacturing_date=datetime.fromisoformat(
                    device_package['manufacturing_date']
                ),
                certificate_thumbprint="thumbprint_placeholder"
            )
            
            # Step 5-7: Register device
            registration = await self.service.register_device(
                device_identity=device_identity,
                farm_id=farm_assignment['farm_id'],
                claim_token=farm_assignment['claim_token']
            )
            workflow_result['registration'] = registration
            
            # Step 8: Send notification
            await self._send_activation_notification(
                device_package['device_id'],
                farm_assignment['farm_id']
            )
            
            workflow_result['status'] = 'completed'
            
        except Exception as e:
            workflow_result['status'] = 'failed'
            workflow_result['error'] = str(e)
            logger.error(f"Provisioning workflow failed: {e}")
            
        return workflow_result
    
    async def _validate_package(self, package: Dict) -> Dict:
        """Validate device package structure"""
        required_fields = ['device_id', 'device_type', 'hardware_version', 
                          'certificates', 'manufacturing_date']
        
        missing = [f for f in required_fields if f not in package]
        if missing:
            raise ValueError(f"Missing required fields: {missing}")
        
        return {'valid': True, 'checked_fields': len(required_fields)}
    
    async def _verify_certificates(self, certificates: Dict) -> Dict:
        """Verify certificate chain and validity"""
        return {'verified': True, 'cert_chain_valid': True}
    
    async def _check_inventory(self, device_id: str) -> Dict:
        """Check device against approved inventory"""
        return {'in_inventory': True, 'approved_for_deployment': True}
    
    async def _send_activation_notification(self, device_id: str, farm_id: str):
        """Send device activation notification"""
        logger.info(f"Activation notification sent for {device_id} at farm {farm_id}")
```

### 2.3 Claiming Process

#### 2.3.1 Device Claiming Workflow

```
+-----------------------------------------------------------------------------+
|                        DEVICE CLAIMING WORKFLOW                              |
+-----------------------------------------------------------------------------+
|                                                                             |
|   MANUFACTURER          LOGISTICS           FARM OPERATOR        CLOUD      |
|       |                      |                    |               |         |
|       | 1. Ship Device       |                    |               |         |
|       |--------------------->|                    |               |         |
|       |                      |                    |               |         |
|       |                      | 2. Deliver Device  |               |         |
|       |                      |------------------->|               |         |
|       |                      |                    |               |         |
|       |                      |                    | 3. Scan QR    |         |
|       |                      |                    |   (Optional)  |         |
|       |                      |                    |               |         |
|       |                      |                    | 4. Power On --|-------> |
|       |                      |                    |               |         |
|       |                      |                    |               |5. Claim  |
|       |                      |                    |               |   Req   |
|       |                      |                    |               |<--------|
|       |                      |                    |               |         |
|       |                      |                    |6. Approve     |         |
|       |                      |                    |  Claim--------|-------->|
|       |                      |                    |               |         |
|       |                      |                    |               |7. Issue |
|       |                      |                    |               |  Creds  |
|       |                      |                    |               |-------->|
|       |                      |                    |               |         |
|       |                      |                    |               |8. Connect|
|       |                      |                    |               |<--------|
|                                                                             |
+-----------------------------------------------------------------------------+
```

#### 2.3.2 Claim Token Generation

```python
# claiming_service.py

import secrets
import hashlib
import base64
from datetime import datetime, timedelta


class DeviceClaimingService:
    """Manages device claiming process for farm operators"""
    
    TOKEN_VALIDITY_DAYS = 30
    
    def __init__(self, encryption_key: bytes):
        self.encryption_key = encryption_key
    
    def generate_claim_token(self, device_id: str, farm_id: str,
                            operator_id: str) -> str:
        """
        Generate secure claim token for device onboarding
        """
        # Create claim data
        claim_data = {
            'device_id': device_id,
            'farm_id': farm_id,
            'operator_id': operator_id,
            'created_at': datetime.utcnow().isoformat(),
            'expires_at': (datetime.utcnow() + 
                          timedelta(days=self.TOKEN_VALIDITY_DAYS)).isoformat(),
            'nonce': secrets.token_hex(16)
        }
        
        # Return base64-encoded token
        claim_json = json.dumps(claim_data).encode()
        return base64.urlsafe_b64encode(claim_json).decode()
    
    def validate_claim_token(self, token: str, device_id: str) -> Dict:
        """Validate and decode claim token"""
        try:
            # Decode token
            claim_json = base64.urlsafe_b64decode(token.encode())
            claim_data = json.loads(claim_json.decode())
            
            # Validate device ID
            if claim_data['device_id'] != device_id:
                raise ValueError("Token does not match device")
            
            # Check expiration
            expires_at = datetime.fromisoformat(claim_data['expires_at'])
            if datetime.utcnow() > expires_at:
                raise ValueError("Claim token has expired")
            
            return claim_data
            
        except Exception as e:
            raise ValueError(f"Invalid claim token: {e}")
    
    def generate_qr_code_data(self, device_id: str, claim_token: str) -> str:
        """Generate QR code data for quick claiming"""
        return f"smartdairy://claim?d={device_id}&t={claim_token[:32]}"
```


---

## 3. Device Registry

### 3.1 Configuration Management Database (CMDB)

#### 3.1.1 Registry Architecture

```
+-----------------------------------------------------------------------------+
|                        DEVICE REGISTRY ARCHITECTURE                          |
+-----------------------------------------------------------------------------+
|                                                                             |
|  +-------------------------------------------------------------------------+|
|  |                      DEVICE REGISTRY (PostgreSQL)                        ||
|  |  +--------------+  +--------------+  +--------------+  +-----------+   ||
|  |  |   devices    |  |device_states |  |device_events |  |relations  |   ||
|  |  |   (master)   |  |  (shadow)    |  |   (audit)    |  |(hierarchy)|   ||
|  |  +--------------+  +--------------+  +--------------+  +-----------+   ||
|  +-------------------------------------------------------------------------+|
|                                     |                                       |
|           +-------------------------+-------------------------+              |
|           |                         |                         |              |
|           v                         v                         v              |
|  +-----------------+      +-----------------+      +-----------------+     |
|  |  Redis Cache    |      |  Elasticsearch  |      |   Time-Series   |     |
|  |  (Hot Data)     |      |  (Search/Index) |      |   DB (Metrics)  |     |
|  +-----------------+      +-----------------+      +-----------------+     |
|                                                                             |
|  +-------------------------------------------------------------------------+|
|  |                      AWS IoT Core / Azure IoT Hub                       ||
|  |                    (Device Shadow / Digital Twin)                       ||
|  +-------------------------------------------------------------------------+|
|                                                                             |
+-----------------------------------------------------------------------------+
```

#### 3.1.2 Device Registry Schema

```sql
-- device_registry_schema.sql
-- Smart Dairy Device Registry Database Schema
-- PostgreSQL with TimescaleDB extension

-- Enable required extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- Device Types Enumeration
CREATE TYPE device_type AS ENUM (
    'milk_meter',
    'environmental_sensor',
    'rfid_reader',
    'wearable',
    'gateway'
);

-- Device Status Enumeration
CREATE TYPE device_status AS ENUM (
    'manufactured',
    'provisioned',
    'registered',
    'active',
    'suspended',
    'maintenance',
    'decommissioned'
);

-- Connection Status Enumeration
CREATE TYPE connection_status AS ENUM (
    'online',
    'offline',
    'connecting',
    'error',
    'unknown'
);

-- Main Devices Table
CREATE TABLE devices (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    device_id VARCHAR(50) UNIQUE NOT NULL,
    device_type device_type NOT NULL,
    device_name VARCHAR(100),
    
    -- Hardware Information
    hardware_version VARCHAR(20) NOT NULL,
    firmware_version VARCHAR(20) NOT NULL,
    serial_number VARCHAR(50) UNIQUE NOT NULL,
    
    -- Manufacturing Information
    manufacturing_date TIMESTAMP WITH TIME ZONE,
    batch_number VARCHAR(50),
    factory_location VARCHAR(100),
    
    -- Organization
    farm_id VARCHAR(20) NOT NULL,
    zone_id VARCHAR(20),
    barn_id VARCHAR(20),
    
    -- Status
    status device_status DEFAULT 'manufactured',
    connection_status connection_status DEFAULT 'unknown',
    
    -- Security
    certificate_thumbprint VARCHAR(64),
    last_authentication TIMESTAMP WITH TIME ZONE,
    
    -- Geolocation
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    altitude DECIMAL(10, 2),
    location_accuracy DECIMAL(5, 2),
    location_updated_at TIMESTAMP WITH TIME ZONE,
    
    -- Timestamps
    registered_at TIMESTAMP WITH TIME ZONE,
    activated_at TIMESTAMP WITH TIME ZONE,
    last_seen_at TIMESTAMP WITH TIME ZONE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    
    -- Metadata
    metadata JSONB DEFAULT '{}',
    tags TEXT[] DEFAULT '{}',
    
    -- Soft delete
    deleted_at TIMESTAMP WITH TIME ZONE,
    deleted_by UUID
);

-- Device Configuration Table
CREATE TABLE device_configurations (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    device_id VARCHAR(50) NOT NULL REFERENCES devices(device_id) ON DELETE CASCADE,
    config_version INTEGER NOT NULL DEFAULT 1,
    config_data JSONB NOT NULL DEFAULT '{}',
    
    -- Configuration metadata
    applied_at TIMESTAMP WITH TIME ZONE,
    applied_by UUID,
    
    -- Status
    status VARCHAR(20) DEFAULT 'pending',
    
    -- Audit
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE(device_id, config_version)
);

-- Device State Shadow Table (reported vs desired)
CREATE TABLE device_states (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    device_id VARCHAR(50) NOT NULL REFERENCES devices(device_id) ON DELETE CASCADE,
    
    -- Desired state (from cloud)
    desired_state JSONB DEFAULT '{}',
    desired_version INTEGER DEFAULT 0,
    desired_updated_at TIMESTAMP WITH TIME ZONE,
    
    -- Reported state (from device)
    reported_state JSONB DEFAULT '{}',
    reported_version INTEGER DEFAULT 0,
    reported_updated_at TIMESTAMP WITH TIME ZONE,
    
    -- Sync status
    sync_status VARCHAR(20) DEFAULT 'in_sync',
    last_sync_at TIMESTAMP WITH TIME ZONE,
    
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE(device_id)
);

-- Device Relationships Table (Hierarchical)
CREATE TABLE device_relationships (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    parent_device_id VARCHAR(50) NOT NULL REFERENCES devices(device_id),
    child_device_id VARCHAR(50) NOT NULL REFERENCES devices(device_id),
    relationship_type VARCHAR(30) NOT NULL,
    
    metadata JSONB DEFAULT '{}',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE(parent_device_id, child_device_id, relationship_type)
);

-- Device Events Audit Log
CREATE TABLE device_events (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    device_id VARCHAR(50) NOT NULL REFERENCES devices(device_id),
    event_type VARCHAR(50) NOT NULL,
    event_category VARCHAR(30) NOT NULL,
    
    event_data JSONB DEFAULT '{}',
    severity VARCHAR(20) DEFAULT 'info',
    
    source_ip INET,
    triggered_by UUID,
    
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Convert device_events to hypertable for time-series optimization
SELECT create_hypertable('device_events', 'created_at', chunk_time_interval => INTERVAL '1 day');

-- Device Telemetry Table (Time-series data)
CREATE TABLE device_telemetry (
    time TIMESTAMP WITH TIME ZONE NOT NULL,
    device_id VARCHAR(50) NOT NULL,
    
    -- Telemetry data
    telemetry_type VARCHAR(30) NOT NULL,
    data JSONB NOT NULL,
    
    -- Metadata
    sequence_number BIGINT,
    received_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

SELECT create_hypertable('device_telemetry', 'time', chunk_time_interval => INTERVAL '7 days');

-- Device Inventory and Asset Management
CREATE TABLE device_inventory (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    device_id VARCHAR(50) NOT NULL REFERENCES devices(device_id),
    
    -- Asset tracking
    purchase_order VARCHAR(50),
    purchase_date DATE,
    warranty_start DATE,
    warranty_end DATE,
    warranty_status VARCHAR(20) DEFAULT 'active',
    
    -- Vendor information
    vendor_id VARCHAR(30),
    vendor_name VARCHAR(100),
    vendor_sku VARCHAR(50),
    
    -- Cost tracking
    unit_cost DECIMAL(10, 2),
    currency VARCHAR(3) DEFAULT 'USD',
    
    -- Maintenance schedule
    last_maintenance_date DATE,
    next_maintenance_date DATE,
    maintenance_interval_days INTEGER DEFAULT 90,
    
    -- Condition
    condition VARCHAR(20) DEFAULT 'new',
    
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Indexes for performance
CREATE INDEX idx_devices_farm ON devices(farm_id);
CREATE INDEX idx_devices_type ON devices(device_type);
CREATE INDEX idx_devices_status ON devices(status);
CREATE INDEX idx_devices_connection ON devices(connection_status);
CREATE INDEX idx_devices_last_seen ON devices(last_seen_at);

CREATE INDEX idx_devices_metadata ON devices USING GIN(metadata);
CREATE INDEX idx_devices_tags ON devices USING GIN(tags);

CREATE INDEX idx_device_events_device_time ON device_events(device_id, created_at DESC);
CREATE INDEX idx_device_events_type ON device_events(event_type);

CREATE INDEX idx_device_telemetry_device_time ON device_telemetry(device_id, time DESC);
CREATE INDEX idx_device_telemetry_type ON device_telemetry(telemetry_type);

-- Views for common queries
CREATE VIEW active_devices AS
SELECT * FROM devices
WHERE status = 'active' 
  AND deleted_at IS NULL;

-- Farm Device Summary View
CREATE VIEW farm_device_summary AS
SELECT 
    farm_id,
    device_type,
    COUNT(*) as device_count,
    COUNT(*) FILTER (WHERE connection_status = 'online') as online_count,
    COUNT(*) FILTER (WHERE connection_status = 'offline') as offline_count
FROM devices
WHERE status = 'active' AND deleted_at IS NULL
GROUP BY farm_id, device_type;

-- Triggers for updated_at
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_devices_updated_at 
    BEFORE UPDATE ON devices 
    FOR EACH ROW 
    EXECUTE FUNCTION update_updated_at_column();
```

### 3.2 Device Metadata Model

```json
{
  "metadata_schemas": {
    "milk_meter": {
      "sensors": {
        "flow_sensor": {
          "model": "FS-2026-A",
          "calibration": {
            "offset": 0.02,
            "gain": 1.0015,
            "last_calibrated": "2026-01-15T08:25:00Z"
          },
          "accuracy": "+-0.5%",
          "range": "0-50 L/min"
        },
        "temperature_sensor": {
          "model": "TMP-100",
          "calibration": {
            "offset": -0.1,
            "last_calibrated": "2026-01-15T08:25:00Z"
          },
          "accuracy": "+-0.1C",
          "range": "0-50C"
        }
      },
      "milking_parlor": {
        "parlor_id": "P-001",
        "stall_number": 5,
        "side": "left"
      },
      "measurement_settings": {
        "sampling_rate_hz": 10,
        "buffer_size": 1000,
        "auto_zero_enabled": true
      }
    },
    
    "environmental_sensor": {
      "sensors": {
        "temperature": {
          "model": "SHT30",
          "accuracy": "+-0.2C",
          "update_interval_sec": 60
        },
        "humidity": {
          "model": "SHT30",
          "accuracy": "+-2% RH",
          "update_interval_sec": 60
        },
        "co2": {
          "model": "SCD30",
          "accuracy": "+-30 ppm",
          "update_interval_sec": 120
        },
        "ammonia": {
          "model": "MICS-6814",
          "accuracy": "+-10%",
          "update_interval_sec": 300
        }
      },
      "location": {
        "building": "Barn-A",
        "zone": "Feeding-Area-1",
        "mounting_height_m": 2.5,
        "ventilation_zone": "Zone-3"
      },
      "thresholds": {
        "temperature_high": 25.0,
        "temperature_low": 10.0,
        "humidity_high": 80.0,
        "co2_high": 3000.0,
        "ammonia_high": 25.0
      }
    },
    
    "rfid_reader": {
      "antenna_config": {
        "antenna_count": 4,
        "antennas": [
          {"id": 1, "power_dbm": 20.0, "location": "entrance"},
          {"id": 2, "power_dbm": 20.0, "location": "exit"},
          {"id": 3, "power_dbm": 18.0, "location": "milking"},
          {"id": 4, "power_dbm": 18.0, "location": "feeding"}
        ]
      },
      "read_settings": {
        "session": 2,
        "inventory_mode": "fast",
        "filter_duplicates": true,
        "tag_population_estimate": 50
      },
      "location": {
        "gate_id": "G-001",
        "reader_type": "portal",
        "coverage_area_m2": 25.0
      }
    },
    
    "wearable": {
      "animal_assignment": {
        "animal_id": "COW-12345",
        "animal_tag": "RFID-ABCD1234",
        "attachment_date": "2026-01-20T10:00:00Z",
        "attachment_location": "neck",
        "expected_removal_date": "2027-01-20T10:00:00Z"
      },
      "sensors": {
        "accelerometer": {
          "model": "MPU-6050",
          "range": "+-4g",
          "odr_hz": 50
        },
        "gps": {
          "model": "NEO-M8N",
          "update_rate_hz": 1,
          "accuracy_m": 2.5
        },
        "temperature": {
          "model": "internal",
          "accuracy": "+-0.5C"
        }
      },
      "battery": {
        "capacity_mah": 5000,
        "expected_life_days": 365,
        "solar_assisted": true
      },
      "behaviour_settings": {
        "activity_threshold_steps": 2000,
        "rumination_threshold_min": 300,
        "rest_threshold_min": 600,
        "estrus_detection_enabled": true
      }
    },
    
    "gateway": {
      "connectivity": {
        "primary_uplink": "ethernet",
        "backup_uplink": "4g_lte",
        "cellular_imei": "123456789012345",
        "sim_iccid": "8901234567890123456"
      },
      "edge_capabilities": {
        "max_devices": 100,
        "supported_protocols": ["zigbee", "lora", "ble", "wifi"],
        "local_storage_gb": 32,
        "edge_compute_enabled": true
      },
      "network_config": {
        "subnet": "192.168.100.0/24",
        "dhcp_enabled": true,
        "dhcp_range_start": "192.168.100.10",
        "dhcp_range_end": "192.168.100.200"
      },
      "managed_devices": [
        "SD-MM-2026-001234",
        "SD-MM-2026-001235",
        "SD-ES-2026-005678"
      ]
    }
  }
}
```


---

## 4. OTA Firmware Updates

### 4.1 Update Campaign Management

#### 4.1.1 OTA Architecture

```
+-----------------------------------------------------------------------------+
|                     OTA FIRMWARE UPDATE ARCHITECTURE                        |
+-----------------------------------------------------------------------------+
|                                                                             |
|   +------------------+                                                      |
|   |  FIRMWARE STORE  |  (S3 / Blob Storage)                                 |
|   |  - Signed images |                                                      |
|   |  - Delta updates |                                                      |
|   |  - Manifests     |                                                      |
|   +--------+---------+                                                      |
|            |                                                                |
|   +--------v-----------------------------------------------------------+    |
|   |                    OTA UPDATE SERVICE                               |    |
|   |  +-------------+  +-------------+  +-------------+  +-----------+ |    |
|   |  |   Campaign  |  |   Rollout   |  |   Device    |  |  Version  | |    |
|   |  |   Manager   |  |   Engine    |  |   Queue     |  |  Control  | |    |
|   |  +-------------+  +-------------+  +-------------+  +-----------+ |    |
|   +--------+-----------------------------------------------------------+    |
|            |                                                                |
|   +--------v-----------------------------------------------------------+    |
|   |                    MESSAGE QUEUE                                    |    |
|   |  (MQTT / IoT Hub / AWS IoT Jobs)                                    |    |
|   +--------+-----------------------------------------------------------+    |
|            |                                                                |
|    +-------+-------+-----------+-----------+-----------+                    |
|    |       |       |           |           |           |                    |
|    v       v       v           v           v           v                    |
| +-----+ +-----+ +-----+   +-----+   +-----+   +-----+                      |
| |MM-1 | |MM-2 | |ES-1 |   |RF-1 |   |GW-1 |   |...  |                      |
| +-----+ +-----+ +-----+   +-----+   +-----+   +-----+                      |
|                                                                             |
+-----------------------------------------------------------------------------+
```

#### 4.1.2 OTA Update Service Implementation

```python
# ota_update_service.py
"""
Smart Dairy OTA Firmware Update Service
Manages firmware updates across all device types
"""

import asyncio
import json
import hashlib
import logging
from datetime import datetime, timedelta
from dataclasses import dataclass
from typing import Dict, List, Optional
from enum import Enum

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)


class UpdateStatus(Enum):
    PENDING = "pending"
    DOWNLOADING = "downloading"
    DOWNLOADED = "downloaded"
    INSTALLING = "installing"
    INSTALLED = "installed"
    REBOOTING = "rebooting"
    COMPLETED = "completed"
    FAILED = "failed"
    ROLLED_BACK = "rolled_back"


class CampaignStatus(Enum):
    DRAFT = "draft"
    SCHEDULED = "scheduled"
    ACTIVE = "active"
    PAUSED = "paused"
    COMPLETED = "completed"
    CANCELLED = "cancelled"


class RolloutStrategy(Enum):
    ALL_AT_ONCE = "all_at_once"
    ROLLOUT = "rollout"
    CANARY = "canary"
    SCHEDULED = "scheduled"


@dataclass
class FirmwareImage:
    version: str
    device_type: str
    hardware_version: str
    
    file_name: str
    file_size: int
    download_url: str
    
    sha256_hash: str
    signature: str
    signing_key_id: str
    
    base_version: Optional[str] = None
    delta_url: Optional[str] = None
    
    release_notes: str = ""
    release_date: datetime = None
    min_battery_percent: int = 30


@dataclass
class UpdateCampaign:
    campaign_id: str
    name: str
    description: str
    
    firmware_image: FirmwareImage
    target_device_type: str
    target_hardware_versions: List[str]
    
    rollout_strategy: RolloutStrategy
    rollout_percentage: float
    rollout_speed: int
    
    status: CampaignStatus
    scheduled_start: Optional[datetime] = None
    auto_rollback: bool = True
    
    total_devices: int = 0
    completed_devices: int = 0
    failed_devices: int = 0
    
    created_at: datetime = None
    started_at: Optional[datetime] = None
    completed_at: Optional[datetime] = None


class OTAUpdateService:
    """Manages OTA firmware updates for Smart Dairy IoT devices"""
    
    def __init__(self, config: Dict):
        self.config = config
        self.campaigns: Dict[str, UpdateCampaign] = {}
        self.jobs: Dict[str, Dict] = {}
        
    async def create_campaign(self, 
                             name: str,
                             description: str,
                             firmware_image: FirmwareImage,
                             target_criteria: Dict,
                             rollout_config: Dict) -> UpdateCampaign:
        """
        Create a new firmware update campaign
        """
        campaign_id = f"campaign-{datetime.utcnow().strftime('%Y%m%d%H%M%S')}"
        
        # Find target devices
        target_devices = await self._find_target_devices(
            target_criteria['device_type'],
            target_criteria['hardware_versions'],
            target_criteria.get('farms', [])
        )
        
        campaign = UpdateCampaign(
            campaign_id=campaign_id,
            name=name,
            description=description,
            firmware_image=firmware_image,
            target_device_type=target_criteria['device_type'],
            target_hardware_versions=target_criteria['hardware_versions'],
            rollout_strategy=RolloutStrategy(rollout_config['strategy']),
            rollout_percentage=rollout_config.get('percentage', 100),
            rollout_speed=rollout_config.get('speed', 10),
            status=CampaignStatus.DRAFT,
            scheduled_start=rollout_config.get('scheduled_start'),
            auto_rollback=rollout_config.get('auto_rollback', True),
            total_devices=len(target_devices),
            created_at=datetime.utcnow()
        )
        
        self.campaigns[campaign_id] = campaign
        
        logger.info(f"Created campaign {campaign_id} targeting {len(target_devices)} devices")
        return campaign
    
    async def start_campaign(self, campaign_id: str) -> UpdateCampaign:
        """Start a firmware update campaign"""
        campaign = self.campaigns.get(campaign_id)
        if not campaign:
            raise ValueError(f"Campaign {campaign_id} not found")
        
        # Determine devices to update based on rollout strategy
        devices = await self._get_campaign_devices(campaign)
        
        if campaign.rollout_strategy == RolloutStrategy.CANARY:
            canary_count = max(1, int(len(devices) * 0.05))
            canary_devices = devices[:canary_count]
            await self._create_update_jobs(campaign, canary_devices)
            logger.info(f"Started canary phase with {canary_count} devices")
            
        elif campaign.rollout_strategy == RolloutStrategy.ROLLOUT:
            initial_count = max(1, int(len(devices) * campaign.rollout_percentage / 100))
            initial_devices = devices[:initial_count]
            await self._create_update_jobs(campaign, initial_devices)
            logger.info(f"Started rollout with {initial_count} devices")
            
        else:
            await self._create_update_jobs(campaign, devices)
        
        campaign.status = CampaignStatus.ACTIVE
        campaign.started_at = datetime.utcnow()
        
        return campaign
    
    async def process_job_update(self, device_id: str, status_update: Dict):
        """Process status update from device"""
        job_id = status_update.get('job_id')
        new_status = UpdateStatus(status_update['status'])
        
        job = self.jobs.get(job_id)
        if not job:
            logger.warning(f"Received update for unknown job {job_id}")
            return
        
        job['status'] = new_status.value
        job['progress'] = status_update.get('progress', 0)
        
        if new_status == UpdateStatus.COMPLETED:
            await self._handle_job_completion(job)
        elif new_status == UpdateStatus.FAILED:
            await self._handle_job_failure(job, status_update.get('error'))
    
    async def _handle_job_completion(self, job: Dict):
        """Handle successful job completion"""
        campaign = self.campaigns.get(job['campaign_id'])
        if campaign:
            campaign.completed_devices += 1
            logger.info(f"Job {job['job_id']} completed successfully")
    
    async def _handle_job_failure(self, job: Dict, error: str):
        """Handle job failure"""
        campaign = self.campaigns.get(job['campaign_id'])
        if campaign:
            campaign.failed_devices += 1
            
            # Check if auto-rollback threshold reached
            failure_rate = campaign.failed_devices / campaign.total_devices
            if campaign.auto_rollback and failure_rate > 0.2:
                await self._initiate_campaign_rollback(campaign)
                logger.warning(f"Initiated rollback for campaign {campaign.campaign_id}")
    
    async def _initiate_campaign_rollback(self, campaign: UpdateCampaign):
        """Initiate rollback of failed campaign"""
        campaign.status = CampaignStatus.CANCELLED
        # Rollback logic here
    
    async def _find_target_devices(self, device_type: str,
                                   hardware_versions: List[str],
                                   farms: List[str]) -> List[str]:
        """Find devices matching campaign criteria"""
        # Query device registry
        return [f"SD-{device_type[:2].upper()}-2026-{i:06d}" for i in range(1, 101)]
    
    async def _get_campaign_devices(self, campaign: UpdateCampaign) -> List[str]:
        """Get devices for campaign"""
        return await self._find_target_devices(
            campaign.target_device_type,
            campaign.target_hardware_versions,
            []
        )
    
    async def _create_update_jobs(self, campaign: UpdateCampaign, 
                                   devices: List[str]):
        """Create update jobs for devices"""
        for device_id in devices:
            job_id = f"{campaign.campaign_id}-{device_id}"
            
            self.jobs[job_id] = {
                'job_id': job_id,
                'device_id': device_id,
                'campaign_id': campaign.campaign_id,
                'status': UpdateStatus.PENDING.value,
                'progress': 0,
                'created_at': datetime.utcnow().isoformat()
            }
            
            # Create IoT Job
            await self._create_iot_job(job_id, device_id, campaign)
    
    async def _create_iot_job(self, job_id: str, device_id: str, 
                               campaign: UpdateCampaign):
        """Create AWS IoT Job for device update"""
        job_document = {
            "operation": "firmware_update",
            "firmwareUrl": campaign.firmware_image.download_url,
            "firmwareVersion": campaign.firmware_image.version,
            "sha256": campaign.firmware_image.sha256_hash,
            "signature": campaign.firmware_image.signature,
            "fileSize": campaign.firmware_image.file_size
        }
        
        logger.info(f"Created IoT job {job_id} for {device_id}")


# Campaign management API
class CampaignManager:
    """REST API handler for campaign management"""
    
    def __init__(self, ota_service: OTAUpdateService):
        self.ota = ota_service
    
    async def create_update_request(self, request: Dict) -> Dict:
        """Handle campaign creation request"""
        
        firmware = FirmwareImage(
            version=request['firmware_version'],
            device_type=request['device_type'],
            hardware_version=request['hardware_version'],
            file_name=request['file_name'],
            file_size=request['file_size'],
            download_url=request['download_url'],
            sha256_hash=request['sha256_hash'],
            signature=request['signature'],
            signing_key_id=request['signing_key_id'],
            release_notes=request.get('release_notes', ''),
            release_date=datetime.utcnow()
        )
        
        campaign = await self.ota.create_campaign(
            name=request['campaign_name'],
            description=request.get('description', ''),
            firmware_image=firmware,
            target_criteria=request['target_criteria'],
            rollout_config=request['rollout_config']
        )
        
        return {
            'campaign_id': campaign.campaign_id,
            'status': campaign.status.value,
            'target_devices': campaign.total_devices
        }
    
    async def get_campaign_status(self, campaign_id: str) -> Dict:
        """Get campaign status and statistics"""
        campaign = self.ota.campaigns.get(campaign_id)
        if not campaign:
            raise ValueError(f"Campaign {campaign_id} not found")
        
        progress_pct = (campaign.completed_devices / campaign.total_devices * 100) \
                       if campaign.total_devices > 0 else 0
        
        return {
            'campaign_id': campaign.campaign_id,
            'name': campaign.name,
            'status': campaign.status.value,
            'progress': {
                'total': campaign.total_devices,
                'completed': campaign.completed_devices,
                'failed': campaign.failed_devices,
                'pending': campaign.total_devices - campaign.completed_devices - campaign.failed_devices,
                'percentage': round(progress_pct, 2)
            },
            'started_at': campaign.started_at.isoformat() if campaign.started_at else None,
            'completed_at': campaign.completed_at.isoformat() if campaign.completed_at else None
        }
```

### 4.2 Phased Rollout Strategy

#### 4.2.1 Rollout Phases

```yaml
# rollout_config.yaml
rollout_strategies:
  
  canary:
    description: "Deploy to small test group first"
    phases:
      - name: "canary"
        percentage: 5
        success_criteria:
          min_uptime_hours: 24
          max_error_rate: 1.0
          min_successful_heartbeats: 100
        auto_promote: true
        
      - name: "pilot"
        percentage: 25
        success_criteria:
          min_uptime_hours: 48
          max_error_rate: 0.5
        auto_promote: false
        require_approval: true
        
      - name: "production"
        percentage: 100
        rollout_speed: 50
        schedule:
          days: ["tuesday", "wednesday", "thursday"]
          time_window: "02:00-06:00"
  
  staged:
    description: "Progressive rollout by farm groups"
    phases:
      - name: "pilot_farms"
        farm_groups: ["pilot"]
        percentage: 100
        
      - name: "early_adopters"
        farm_groups: ["early_adopter"]
        percentage: 100
        wait_days: 7
        
      - name: "general_availability"
        farm_groups: ["standard", "enterprise"]
        percentage: 100
        rollout_speed: 20
        wait_days: 14
  
  emergency:
    description: "Fast rollout for critical security patches"
    phases:
      - name: "emergency_deploy"
        percentage: 100
        batch_size: 100
        batch_interval_minutes: 5
        skip_health_checks: true
```

### 4.3 Rollback Mechanisms

```python
# rollback_service.py

class RollbackService:
    """Handles OTA update rollbacks"""
    
    ROLLBACK_TRIGGERS = {
        'failure_rate_threshold': 0.20,
        'error_rate_threshold': 0.10,
        'offline_threshold': 0.15,
        'min_devices_for_rollback': 10
    }
    
    async def check_rollback_conditions(self, campaign_id: str) -> Dict:
        """Check if rollback should be triggered"""
        
        campaign = await self._get_campaign(campaign_id)
        stats = await self._get_campaign_stats(campaign_id)
        
        total = stats['total_devices']
        if total < self.ROLLBACK_TRIGGERS['min_devices_for_rollback']:
            return {'should_rollback': False, 'reason': 'insufficient_devices'}
        
        failure_rate = stats['failed_devices'] / total
        if failure_rate > self.ROLLBACK_TRIGGERS['failure_rate_threshold']:
            return {
                'should_rollback': True,
                'reason': 'failure_rate_exceeded',
                'failure_rate': failure_rate,
                'threshold': self.ROLLBACK_TRIGGERS['failure_rate_threshold']
            }
        
        # Check for error spike pattern
        error_rate = await self._calculate_error_rate(campaign_id, window_minutes=30)
        if error_rate > self.ROLLBACK_TRIGGERS['error_rate_threshold']:
            return {
                'should_rollback': True,
                'reason': 'error_rate_spike',
                'error_rate': error_rate
            }
        
        return {'should_rollback': False}
    
    async def execute_rollback(self, campaign_id: str, 
                               target_version: Optional[str] = None) -> Dict:
        """Execute rollback to previous firmware version"""
        
        campaign = await self._get_campaign(campaign_id)
        
        # Determine previous version
        if target_version is None:
            target_version = campaign.firmware_image.base_version
        
        if not target_version:
            raise ValueError("No base version available for rollback")
        
        # Create rollback campaign
        rollback_campaign = await self._create_rollback_campaign(
            original_campaign=campaign,
            target_version=target_version
        )
        
        # Get devices that need rollback (updated + failed)
        devices_to_rollback = await self._get_devices_for_rollback(campaign_id)
        
        # Execute rollback
        results = await self._deploy_rollback(rollback_campaign, devices_to_rollback)
        
        return {
            'rollback_campaign_id': rollback_campaign.campaign_id,
            'target_version': target_version,
            'devices_rolled_back': len(devices_to_rollback),
            'results': results
        }
```


---

## 5. Configuration Management

### 5.1 Remote Configuration

#### 5.1.1 Configuration Service API

```python
# configuration_service.py
"""
Smart Dairy Device Configuration Service
Manages device configuration with versioning and bulk updates
"""

import json
import logging
from datetime import datetime
from typing import Dict, List, Optional, Any
from dataclasses import dataclass
from enum import Enum
from copy import deepcopy

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)


class ConfigValidationError(Exception):
    """Configuration validation error"""
    pass


class ConfigApplyStatus(Enum):
    PENDING = "pending"
    IN_PROGRESS = "in_progress"
    APPLIED = "applied"
    FAILED = "failed"
    ROLLED_BACK = "rolled_back"


@dataclass
class ConfigurationVersion:
    version_id: int
    device_id: str
    config_data: Dict
    created_at: datetime
    created_by: str
    status: ConfigApplyStatus
    applied_at: Optional[datetime] = None
    change_summary: str = ""


class ConfigurationService:
    """Manages device configurations with versioning and validation"""
    
    # Default configurations by device type
    DEFAULT_CONFIGS = {
        'milk_meter': {
            'telemetry': {
                'measurement_interval_sec': 1,
                'batch_size': 60,
                'transmission_interval_sec': 60,
                'compression_enabled': True
            },
            'sensors': {
                'flow_sensor': {
                    'enabled': True,
                    'sampling_rate_hz': 10,
                    'auto_zero_interval_hours': 24
                },
                'temperature_sensor': {
                    'enabled': True,
                    'update_interval_sec': 10
                }
            },
            'thresholds': {
                'min_flow_rate_lpm': 0.1,
                'max_flow_rate_lpm': 50.0,
                'min_temperature_c': 0.0,
                'max_temperature_c': 45.0,
                'temperature_alarm_c': 40.0
            },
            'calibration': {
                'flow_offset': 0.0,
                'flow_gain': 1.0,
                'temperature_offset': 0.0,
                'last_calibration': None
            },
            'network': {
                'connection_timeout_sec': 30,
                'keepalive_interval_sec': 60,
                'retry_attempts': 3,
                'retry_delay_sec': 5
            }
        },
        
        'environmental_sensor': {
            'telemetry': {
                'update_interval_sec': 60,
                'batch_transmission': True,
                'batch_size': 10
            },
            'sensors': {
                'temperature': {'enabled': True, 'update_interval_sec': 60},
                'humidity': {'enabled': True, 'update_interval_sec': 60},
                'co2': {'enabled': True, 'update_interval_sec': 120},
                'ammonia': {'enabled': True, 'update_interval_sec': 300}
            },
            'thresholds': {
                'temperature_high': 25.0,
                'temperature_low': 10.0,
                'humidity_high': 80.0,
                'humidity_low': 40.0,
                'co2_high': 3000,
                'co2_critical': 5000,
                'ammonia_high': 25.0,
                'ammonia_critical': 50.0
            },
            'alarms': {
                'enabled': True,
                'debounce_minutes': 5,
                'escalation_interval_minutes': 15
            }
        },
        
        'rfid_reader': {
            'operation': {
                'mode': 'continuous',
                'inventory_interval_ms': 1000,
                'session': 2,
                'target': 'A'
            },
            'antennas': {
                'auto_power_control': True,
                'default_power_dbm': 20.0,
                'min_power_dbm': 10.0,
                'max_power_dbm': 30.0
            },
            'filtering': {
                'duplicate_filter_window_ms': 5000,
                'rssi_threshold_dbm': -70,
                'tag_population_estimate': 50
            },
            'telemetry': {
                'tag_read_event': True,
                'inventory_summary_interval_sec': 60,
                'statistics_interval_sec': 300
            }
        },
        
        'wearable': {
            'sensors': {
                'accelerometer': {
                    'enabled': True,
                    'odr_hz': 50,
                    'range_g': 4,
                    'activity_threshold_mg': 100
                },
                'gps': {
                    'enabled': True,
                    'update_interval_sec': 300,
                    'sleep_interval_sec': 1800,
                    'accuracy_target_m': 10
                }
            },
            'behaviour_analysis': {
                'enabled': True,
                'activity_window_minutes': 15,
                'rumination_detection': True,
                'rest_detection': True,
                'estrus_detection': True
            },
            'battery': {
                'power_save_mode': 'adaptive',
                'low_battery_threshold_pct': 20,
                'critical_battery_threshold_pct': 10,
                'solar_charging_enabled': True
            },
            'alerts': {
                'inactivity_threshold_hours': 2,
                'fever_threshold_c': 39.5,
                'estrus_alert': True
            }
        },
        
        'gateway': {
            'network': {
                'primary_uplink': 'ethernet',
                'failover_enabled': True,
                'cellular_backup': True,
                'connection_check_interval_sec': 60
            },
            'edge_processing': {
                'enabled': True,
                'max_devices': 100,
                'local_buffer_hours': 24,
                'compression_enabled': True,
                'aggregation_enabled': True
            },
            'protocols': {
                'zigbee': {'enabled': True, 'channel': 15},
                'lora': {'enabled': True, 'frequency_mhz': 923},
                'ble': {'enabled': True, 'scan_interval_ms': 1000}
            },
            'security': {
                'device_auth_required': True,
                'encryption': 'AES-256',
                'certificate_rotation_days': 90
            }
        }
    }
    
    def __init__(self, db_client):
        self.db = db_client
        
    async def get_device_config(self, device_id: str) -> Dict:
        """Get current configuration for a device"""
        # Query from device_states table
        query = """
            SELECT reported_state->'configuration' as config,
                   desired_state->'configuration' as desired_config
            FROM device_states 
            WHERE device_id = %s
        """
        result = await self.db.fetchrow(query, device_id)
        
        if result and result['desired_config']:
            return result['desired_config']
        elif result and result['config']:
            return result['config']
        
        # Return default configuration
        device_info = await self._get_device_info(device_id)
        return self.get_default_config(device_info['device_type'])
    
    async def update_device_config(self, 
                                   device_id: str,
                                   config_updates: Dict,
                                   user_id: str,
                                   apply_immediately: bool = True) -> ConfigurationVersion:
        """
        Update device configuration
        """
        # Get current configuration
        current_config = await self.get_device_config(device_id)
        
        # Get next version number
        version_id = await self._get_next_version(device_id)
        
        # Merge configurations
        new_config = self._deep_merge(deepcopy(current_config), config_updates)
        
        # Validate configuration
        device_info = await self._get_device_info(device_id)
        await self._validate_config(device_info['device_type'], new_config)
        
        # Create configuration version
        config_version = ConfigurationVersion(
            version_id=version_id,
            device_id=device_id,
            config_data=new_config,
            created_at=datetime.utcnow(),
            created_by=user_id,
            status=ConfigApplyStatus.PENDING,
            change_summary=self._generate_change_summary(current_config, new_config)
        )
        
        # Store version
        await self._store_config_version(config_version)
        
        # Update device desired state
        if apply_immediately:
            await self._push_config_to_device(device_id, new_config, version_id)
        
        logger.info(f"Configuration v{version_id} created for {device_id}")
        return config_version
    
    async def bulk_update_config(self,
                                  device_filter: Dict,
                                  config_updates: Dict,
                                  user_id: str,
                                  rollout_config: Optional[Dict] = None) -> Dict:
        """
        Update configuration for multiple devices
        """
        # Find matching devices
        devices = await self._find_devices(device_filter)
        
        if not devices:
            return {'success': False, 'error': 'No devices match criteria'}
        
        bulk_job_id = f"bulk-{datetime.utcnow().strftime('%Y%m%d%H%M%S')}"
        
        results = {
            'bulk_job_id': bulk_job_id,
            'total_devices': len(devices),
            'successful': 0,
            'failed': 0,
            'devices': []
        }
        
        # Apply updates
        for device_id in devices:
            try:
                config_version = await self.update_device_config(
                    device_id=device_id,
                    config_updates=config_updates,
                    user_id=user_id,
                    apply_immediately=True
                )
                results['successful'] += 1
                results['devices'].append({
                    'device_id': device_id,
                    'version': config_version.version_id,
                    'status': 'success'
                })
            except Exception as e:
                results['failed'] += 1
                results['devices'].append({
                    'device_id': device_id,
                    'status': 'failed',
                    'error': str(e)
                })
        
        return results
    
    async def rollback_config(self, device_id: str, 
                              target_version: Optional[int] = None) -> ConfigurationVersion:
        """
        Rollback device configuration to previous version
        """
        current_version = await self._get_current_version(device_id)
        
        if target_version is None:
            target_version = current_version - 1
        
        if target_version < 1:
            raise ConfigValidationError("No previous version to rollback to")
        
        # Get target configuration
        target_config = await self._get_config_version(device_id, target_version)
        
        if not target_config:
            raise ConfigValidationError(f"Version {target_version} not found")
        
        # Create rollback version
        new_version_id = await self._get_next_version(device_id)
        
        rollback_version = ConfigurationVersion(
            version_id=new_version_id,
            device_id=device_id,
            config_data=target_config.config_data,
            created_at=datetime.utcnow(),
            created_by='system_rollback',
            status=ConfigApplyStatus.PENDING,
            change_summary=f"Rollback to version {target_version}",
        )
        
        await self._store_config_version(rollback_version)
        await self._push_config_to_device(device_id, target_config.config_data, new_version_id)
        
        logger.info(f"Rolled back {device_id} to version {target_version}")
        return rollback_version
    
    def _deep_merge(self, base: Dict, updates: Dict) -> Dict:
        """Deep merge configuration dictionaries"""
        result = deepcopy(base)
        
        for key, value in updates.items():
            if key in result and isinstance(result[key], dict) and isinstance(value, dict):
                result[key] = self._deep_merge(result[key], value)
            else:
                result[key] = value
        
        return result
    
    async def _validate_config(self, device_type: str, config: Dict):
        """Validate configuration against schema"""
        # JSON Schema validation would go here
        pass
    
    def _generate_change_summary(self, old_config: Dict, new_config: Dict) -> str:
        """Generate human-readable summary of configuration changes"""
        changes = []
        
        def compare_dicts(old: Dict, new: Dict, path: str = ""):
            for key in set(old.keys()) | set(new.keys()):
                current_path = f"{path}.{key}" if path else key
                
                if key not in old:
                    changes.append(f"+ Added {current_path}")
                elif key not in new:
                    changes.append(f"- Removed {current_path}")
                elif isinstance(old[key], dict) and isinstance(new[key], dict):
                    compare_dicts(old[key], new[key], current_path)
                elif old[key] != new[key]:
                    changes.append(f"~ Changed {current_path}: {old[key]} -> {new[key]}")
        
        compare_dicts(old_config, new_config)
        return "; ".join(changes[:5]) + ("..." if len(changes) > 5 else "")
    
    async def _push_config_to_device(self, device_id: str, 
                                     config: Dict, version_id: int):
        """Push configuration to device via IoT platform"""
        logger.info(f"Pushed config v{version_id} to {device_id}")
    
    def get_default_config(self, device_type: str) -> Dict:
        """Get default configuration for device type"""
        return deepcopy(self.DEFAULT_CONFIGS.get(device_type, {}))
    
    async def _get_device_info(self, device_id: str) -> Dict:
        return {'device_type': 'milk_meter', 'hardware_version': '2.1.0'}
    
    async def _find_devices(self, device_filter: Dict) -> List[str]:
        return ['SD-MM-2026-001234', 'SD-MM-2026-001235']
    
    async def _get_next_version(self, device_id: str) -> int:
        return 1
    
    async def _get_current_version(self, device_id: str) -> int:
        return 1
    
    async def _get_config_version(self, device_id: str, 
                                   version_id: int) -> Optional[ConfigurationVersion]:
        return None
    
    async def _store_config_version(self, version: ConfigurationVersion):
        pass


# Configuration REST API
class ConfigAPI:
    """REST API for configuration management"""
    
    def __init__(self, config_service: ConfigurationService):
        self.service = config_service
    
    async def update_device_config_endpoint(self, device_id: str, request: Dict) -> Dict:
        """Update configuration for a single device"""
        try:
            version = await self.service.update_device_config(
                device_id=device_id,
                config_updates=request['config'],
                user_id=request.get('user_id', 'api_user'),
                apply_immediately=request.get('apply_immediately', True)
            )
            return {
                'device_id': device_id,
                'version': version.version_id,
                'status': version.status.value,
                'change_summary': version.change_summary
            }
        except ConfigValidationError as e:
            return {'error': str(e), 'status': 'validation_failed'}
    
    async def get_device_config_endpoint(self, device_id: str) -> Dict:
        """Get current device configuration"""
        config = await self.service.get_device_config(device_id)
        return {
            'device_id': device_id,
            'configuration': config,
            'timestamp': datetime.utcnow().isoformat()
        }
    
    async def bulk_update_endpoint(self, request: Dict) -> Dict:
        """Update configuration for multiple devices"""
        results = await self.service.bulk_update_config(
            device_filter=request['device_filter'],
            config_updates=request['config'],
            user_id=request.get('user_id', 'api_user'),
            rollout_config=request.get('rollout')
        )
        return results
```

### 5.2 Configuration Versioning

```python
# Configuration Version Control Example
"""
Configuration versions are tracked in the device_configurations table:
- Each change creates a new version
- Versions are immutable
- Rollback is supported to any previous version
- Audit trail maintained for all changes
"""

# Version History Query Example
GET_CONFIG_HISTORY = """
    SELECT 
        config_version,
        config_data,
        created_at,
        created_by,
        status,
        change_summary
    FROM device_configurations
    WHERE device_id = %s
    ORDER BY config_version DESC;
"""

# Configuration Diff Example
async def get_config_diff(device_id: str, version_a: int, version_b: int) -> Dict:
    """Get differences between two configuration versions"""
    
    config_a = await get_config_version(device_id, version_a)
    config_b = await get_config_version(device_id, version_b)
    
    diff = {
        'added': {},
        'removed': {},
        'modified': {}
    }
    
    # Compare and populate diff
    # ... implementation
    
    return diff
```

### 5.3 Bulk Updates

```python
# Bulk Update Strategies

class BulkUpdateStrategies:
    """Strategies for bulk configuration updates"""
    
    @staticmethod
    def create_rolling_update(batch_size: int = 10, 
                               delay_seconds: int = 60) -> Dict:
        """Create rolling update configuration"""
        return {
            'strategy': 'rolling',
            'batch_size': batch_size,
            'delay_seconds': delay_seconds,
            'max_parallel': 5,
            'health_check_between_batches': True
        }
    
    @staticmethod
    def create_canary_update(canary_percent: float = 5,
                              canary_duration_minutes: int = 60) -> Dict:
        """Create canary update configuration"""
        return {
            'strategy': 'canary',
            'canary_percentage': canary_percent,
            'canary_duration_minutes': canary_duration_minutes,
            'auto_promote_on_success': False,
            'require_approval_for_full': True
        }
    
    @staticmethod
    def create_scheduled_update(scheduled_time: datetime,
                                 maintenance_window_hours: int = 4) -> Dict:
        """Create scheduled update configuration"""
        return {
            'strategy': 'scheduled',
            'scheduled_time': scheduled_time.isoformat(),
            'maintenance_window_hours': maintenance_window_hours,
            'auto_rollback_on_failure': True,
            'notification_before_minutes': 30
        }
```


---

## 6. Monitoring & Diagnostics

### 6.1 Health Checks

#### 6.1.1 Health Monitoring Service

```python
# health_monitoring_service.py
"""
Smart Dairy Device Health Monitoring Service
Tracks device health, connection status, and diagnostic metrics
"""

import logging
from datetime import datetime, timedelta
from typing import Dict, List, Optional
from dataclasses import dataclass
from enum import Enum
from collections import defaultdict
import statistics

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)


class HealthStatus(Enum):
    HEALTHY = "healthy"
    WARNING = "warning"
    CRITICAL = "critical"
    UNKNOWN = "unknown"


class ConnectionQuality(Enum):
    EXCELLENT = "excellent"  # > -50 dBm
    GOOD = "good"            # -50 to -60 dBm
    FAIR = "fair"            # -60 to -70 dBm
    POOR = "poor"            # < -70 dBm


@dataclass
class DeviceHealth:
    device_id: str
    timestamp: datetime
    overall_status: HealthStatus
    
    # Connection metrics
    connection_status: str
    connection_quality: ConnectionQuality
    signal_strength_dbm: Optional[float]
    snr_db: Optional[float]
    
    # Performance metrics
    cpu_usage_percent: Optional[float]
    memory_usage_percent: Optional[float]
    storage_usage_percent: Optional[float]
    
    # Battery metrics
    battery_percent: Optional[float]
    battery_voltage: Optional[float]
    
    # Sensor health
    sensor_status: Dict[str, str]
    
    # Error counters
    connection_drops_24h: int
    error_count_24h: int
    reboot_count_24h: int
    
    # Uptime
    uptime_seconds: int
    last_telemetry_age_seconds: Optional[int]


class HealthMonitoringService:
    """Monitors health of all IoT devices"""
    
    THRESHOLDS = {
        'signal_strength': {
            'excellent': -50,
            'good': -60,
            'fair': -70
        },
        'cpu_usage': {'warning': 70, 'critical': 90},
        'memory_usage': {'warning': 80, 'critical': 95},
        'battery': {'warning': 30, 'critical': 15},
        'uptime_hours': {'warning': 24, 'critical': 168},
        'telemetry_age': {'warning': 300, 'critical': 600}
    }
    
    def __init__(self, db_client, alert_service):
        self.db = db_client
        self.alert_service = alert_service
        self.health_cache: Dict[str, DeviceHealth] = {}
        
    async def process_telemetry(self, device_id: str, telemetry: Dict):
        """Process incoming telemetry for health indicators"""
        
        # Extract health metrics from telemetry
        health_data = self._extract_health_metrics(telemetry)
        
        # Calculate health status
        health = self._calculate_health(device_id, health_data)
        
        # Update cache
        self.health_cache[device_id] = health
        
        # Check for alerts
        await self._check_health_alerts(health)
        
        return health
    
    def _extract_health_metrics(self, telemetry: Dict) -> Dict:
        """Extract health-related metrics from telemetry"""
        return {
            'timestamp': telemetry.get('timestamp'),
            'device_diagnostics': telemetry.get('diagnostics', {}),
            'network': telemetry.get('network', {}),
            'sensors': telemetry.get('sensors', {}),
            'system': telemetry.get('system', {})
        }
    
    def _calculate_health(self, device_id: str, metrics: Dict) -> DeviceHealth:
        """Calculate device health status from metrics"""
        
        diag = metrics.get('device_diagnostics', {})
        network = metrics.get('network', {})
        system = metrics.get('system', {})
        
        signal_dbm = network.get('signal_strength_dbm')
        connection_quality = self._calculate_connection_quality(signal_dbm)
        
        # Sensor health
        sensor_status = {}
        for sensor_name, sensor_data in metrics.get('sensors', {}).items():
            if isinstance(sensor_data, dict):
                sensor_status[sensor_name] = sensor_data.get('status', 'unknown')
        
        # Determine overall health
        health_factors = []
        
        # Check signal strength
        if signal_dbm is not None and signal_dbm < self.THRESHOLDS['signal_strength']['fair']:
            health_factors.append(('poor_signal', HealthStatus.WARNING))
        
        # Check CPU
        cpu = system.get('cpu_usage_percent')
        if cpu and cpu > self.THRESHOLDS['cpu_usage']['critical']:
            health_factors.append(('high_cpu', HealthStatus.CRITICAL))
        elif cpu and cpu > self.THRESHOLDS['cpu_usage']['warning']:
            health_factors.append(('high_cpu', HealthStatus.WARNING))
        
        # Check memory
        memory = system.get('memory_usage_percent')
        if memory and memory > self.THRESHOLDS['memory_usage']['critical']:
            health_factors.append(('high_memory', HealthStatus.CRITICAL))
        elif memory and memory > self.THRESHOLDS['memory_usage']['warning']:
            health_factors.append(('high_memory', HealthStatus.WARNING))
        
        # Check battery
        battery = system.get('battery_percent')
        if battery and battery < self.THRESHOLDS['battery']['critical']:
            health_factors.append(('low_battery', HealthStatus.CRITICAL))
        elif battery and battery < self.THRESHOLDS['battery']['warning']:
            health_factors.append(('low_battery', HealthStatus.WARNING))
        
        # Determine overall status
        if any(f[1] == HealthStatus.CRITICAL for f in health_factors):
            overall_status = HealthStatus.CRITICAL
        elif any(f[1] == HealthStatus.WARNING for f in health_factors):
            overall_status = HealthStatus.WARNING
        else:
            overall_status = HealthStatus.HEALTHY
        
        return DeviceHealth(
            device_id=device_id,
            timestamp=datetime.utcnow(),
            overall_status=overall_status,
            connection_status=network.get('status', 'unknown'),
            connection_quality=connection_quality,
            signal_strength_dbm=signal_dbm,
            snr_db=network.get('snr_db'),
            cpu_usage_percent=system.get('cpu_usage_percent'),
            memory_usage_percent=system.get('memory_usage_percent'),
            storage_usage_percent=system.get('storage_usage_percent'),
            battery_percent=battery,
            battery_voltage=system.get('battery_voltage'),
            sensor_status=sensor_status,
            connection_drops_24h=diag.get('connection_drops_24h', 0),
            error_count_24h=diag.get('error_count_24h', 0),
            reboot_count_24h=diag.get('reboot_count_24h', 0),
            uptime_seconds=system.get('uptime_seconds', 0),
            last_telemetry_age_seconds=None
        )
    
    def _calculate_connection_quality(self, signal_dbm: Optional[float]) -> ConnectionQuality:
        """Calculate connection quality from signal strength"""
        if signal_dbm is None:
            return ConnectionQuality.POOR
        if signal_dbm >= self.THRESHOLDS['signal_strength']['excellent']:
            return ConnectionQuality.EXCELLENT
        elif signal_dbm >= self.THRESHOLDS['signal_strength']['good']:
            return ConnectionQuality.GOOD
        elif signal_dbm >= self.THRESHOLDS['signal_strength']['fair']:
            return ConnectionQuality.FAIR
        return ConnectionQuality.POOR
    
    async def _check_health_alerts(self, health: DeviceHealth):
        """Generate alerts based on health status"""
        
        if health.overall_status == HealthStatus.CRITICAL:
            await self.alert_service.send_alert(
                level='critical',
                device_id=health.device_id,
                title=f"Device {health.device_id} health critical",
                details={
                    'signal_strength': health.signal_strength_dbm,
                    'battery': health.battery_percent,
                    'errors_24h': health.error_count_24h
                }
            )
    
    async def get_farm_health_summary(self, farm_id: str) -> Dict:
        """Get health summary for all devices in a farm"""
        
        devices = await self._get_farm_devices(farm_id)
        
        status_counts = defaultdict(int)
        quality_counts = defaultdict(int)
        total_battery = []
        
        for device_id in devices:
            health = self.health_cache.get(device_id)
            if health:
                status_counts[health.overall_status.value] += 1
                quality_counts[health.connection_quality.value] += 1
                if health.battery_percent is not None:
                    total_battery.append(health.battery_percent)
        
        return {
            'farm_id': farm_id,
            'total_devices': len(devices),
            'health_summary': dict(status_counts),
            'connection_quality': dict(quality_counts),
            'battery_statistics': {
                'avg_percent': statistics.mean(total_battery) if total_battery else None,
                'min_percent': min(total_battery) if total_battery else None,
                'devices_reporting': len(total_battery)
            },
            'timestamp': datetime.utcnow().isoformat()
        }
    
    async def _get_farm_devices(self, farm_id: str) -> List[str]:
        """Get all device IDs for a farm"""
        return []


# Health Dashboard Data API
class HealthDashboardAPI:
    """API for health monitoring dashboard"""
    
    def __init__(self, health_service: HealthMonitoringService):
        self.health = health_service
    
    async def get_dashboard_data(self, farm_id: Optional[str] = None) -> Dict:
        """Get data for health monitoring dashboard"""
        
        data = {
            'summary': {},
            'device_health': [],
            'alerts': [],
            'trends': {}
        }
        
        if farm_id:
            data['summary'] = await self.health.get_farm_health_summary(farm_id)
        
        return data
```

### 6.2 Connection Status Monitoring

```python
# Connection Status Monitoring

class ConnectionStatusMonitor:
    """Monitors device connection status"""
    
    CONNECTION_TIMEOUTS = {
        'online': timedelta(minutes=5),
        'warning': timedelta(minutes=15),
        'offline': timedelta(minutes=30)
    }
    
    async def update_connection_status(self, device_id: str):
        """Update connection status based on last seen timestamp"""
        
        device = await self._get_device(device_id)
        last_seen = device.get('last_seen_at')
        
        if not last_seen:
            new_status = 'unknown'
        else:
            time_since_seen = datetime.utcnow() - last_seen
            
            if time_since_seen < self.CONNECTION_TIMEOUTS['online']:
                new_status = 'online'
            elif time_since_seen < self.CONNECTION_TIMEOUTS['warning']:
                new_status = 'degraded'
            else:
                new_status = 'offline'
        
        if new_status != device.get('connection_status'):
            await self._update_device_status(device_id, new_status)
            
            # Log status change
            await self._log_connection_event(device_id, device.get('connection_status'), new_status)
    
    async def get_connection_statistics(self, farm_id: str, 
                                        time_range_hours: int = 24) -> Dict:
        """Get connection statistics for a farm"""
        
        query = """
            SELECT 
                device_id,
                connection_status,
                COUNT(*) as status_count,
                AVG(EXTRACT(EPOCH FROM (received_at - time))) as avg_latency_ms
            FROM device_telemetry
            WHERE device_id IN (SELECT device_id FROM devices WHERE farm_id = %s)
              AND time > NOW() - INTERVAL '%s hours'
            GROUP BY device_id, connection_status
        """
        
        # Process and return statistics
        return {
            'farm_id': farm_id,
            'time_range_hours': time_range_hours,
            'statistics': {}
        }
```

### 6.3 Signal Strength Monitoring

```python
# Signal Strength Monitoring

class SignalStrengthMonitor:
    """Monitors RF signal strength for wireless devices"""
    
    SIGNAL_THRESHOLDS = {
        'excellent': -50,
        'good': -60,
        'fair': -70,
        'poor': -80
    }
    
    async def analyze_signal_trends(self, device_id: str, 
                                    days: int = 7) -> Dict:
        """Analyze signal strength trends over time"""
        
        query = """
            SELECT 
                time,
                data->'network'->>'signal_strength_dbm' as signal_dbm
            FROM device_telemetry
            WHERE device_id = %s
              AND telemetry_type = 'diagnostic'
              AND time > NOW() - INTERVAL '%s days'
            ORDER BY time
        """
        
        # Calculate trends
        return {
            'device_id': device_id,
            'analysis_period_days': days,
            'trend': 'stable',  # improving, deteriorating, stable
            'average_signal_dbm': -65,
            'min_signal_dbm': -75,
            'max_signal_dbm': -55
        }
    
    async def get_farm_coverage_map(self, farm_id: str) -> List[Dict]:
        """Generate coverage map for farm"""
        
        query = """
            SELECT 
                d.device_id,
                d.device_type,
                d.latitude,
                d.longitude,
                t.data->'network'->>'signal_strength_dbm' as signal_dbm
            FROM devices d
            JOIN LATERAL (
                SELECT data->'network'->>'signal_strength_dbm' as signal_dbm
                FROM device_telemetry
                WHERE device_id = d.device_id
                  AND telemetry_type = 'diagnostic'
                ORDER BY time DESC
                LIMIT 1
            ) t ON true
            WHERE d.farm_id = %s
              AND d.latitude IS NOT NULL
        """
        
        return []
```


---

## 7. Remote Troubleshooting

### 7.1 Remote Log Collection

```python
# remote_troubleshooting_service.py
"""
Smart Dairy Remote Troubleshooting Service
Provides remote diagnostics, log collection, and command execution
"""

import logging
from datetime import datetime, timedelta
from typing import Dict, List, Optional
from enum import Enum
from dataclasses import dataclass

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)


class DebugLevel(Enum):
    INFO = "info"
    DEBUG = "debug"
    VERBOSE = "verbose"
    TRACE = "trace"


class CommandStatus(Enum):
    QUEUED = "queued"
    SENT = "sent"
    EXECUTING = "executing"
    COMPLETED = "completed"
    FAILED = "failed"
    TIMEOUT = "timeout"


@dataclass
class RemoteCommand:
    command_id: str
    device_id: str
    command_type: str
    parameters: Dict
    status: CommandStatus
    created_at: datetime
    executed_at: Optional[datetime] = None
    completed_at: Optional[datetime] = None
    result: Optional[Dict] = None
    error_message: Optional[str] = None
    timeout_seconds: int = 60


class RemoteTroubleshootingService:
    """Provides remote troubleshooting capabilities for IoT devices"""
    
    ALLOWED_COMMANDS = {
        'all': [
            'ping',
            'get_status',
            'get_logs',
            'restart',
            'get_config',
            'set_debug_level'
        ],
        'milk_meter': [
            'calibrate_flow',
            'zero_flow',
            'test_valve',
            'get_readings'
        ],
        'environmental_sensor': [
            'read_sensors',
            'calibrate_sensors',
            'reset_thresholds'
        ],
        'rfid_reader': [
            'inventory_scan',
            'test_antenna',
            'clear_buffer'
        ],
        'wearable': [
            'locate_device',
            'force_sync',
            'reset_activity'
        ],
        'gateway': [
            'restart_network',
            'flush_buffer',
            'scan_devices',
            'update_routing'
        ]
    }
    
    def __init__(self, mqtt_client, db_client):
        self.mqtt = mqtt_client
        self.db = db_client
        self.pending_commands: Dict[str, RemoteCommand] = {}
        
    async def enable_debug_mode(self, device_id: str, 
                                 level: DebugLevel,
                                 duration_minutes: int = 30) -> Dict:
        """Enable debug logging on device"""
        return await self.send_command(
            device_id=device_id,
            command_type='set_debug_level',
            parameters={
                'level': level.value,
                'duration_minutes': duration_minutes
            }
        )
    
    async def collect_logs(self, device_id: str,
                          log_types: List[str],
                          time_range_hours: int = 24,
                          include_system_logs: bool = True) -> Dict:
        """Collect logs from device"""
        result = await self.send_command(
            device_id=device_id,
            command_type='get_logs',
            parameters={
                'log_types': log_types,
                'time_range_hours': time_range_hours,
                'include_system_logs': include_system_logs,
                'compression': 'gzip',
                'chunk_size': 4096
            },
            timeout_seconds=120
        )
        
        if result['status'] == 'completed':
            await self._store_log_reference(device_id, result['result'])
        
        return result
    
    async def run_diagnostics(self, device_id: str,
                              diagnostic_suite: str = 'full') -> Dict:
        """Run diagnostic tests on device"""
        return await self.send_command(
            device_id=device_id,
            command_type='run_diagnostics',
            parameters={
                'suite': diagnostic_suite,
                'save_results': True
            },
            timeout_seconds=180
        )
    
    async def send_command(self, device_id: str,
                          command_type: str,
                          parameters: Dict,
                          timeout_seconds: int = 60) -> Dict:
        """Send a remote command to a device"""
        
        # Validate command
        device_type = await self._get_device_type(device_id)
        if not self._is_command_allowed(command_type, device_type):
            raise ValueError(f"Command '{command_type}' not allowed for {device_type}")
        
        # Create command
        command_id = f"cmd-{datetime.utcnow().strftime('%Y%m%d%H%M%S')}-{device_id}"
        
        command = RemoteCommand(
            command_id=command_id,
            device_id=device_id,
            command_type=command_type,
            parameters=parameters,
            status=CommandStatus.QUEUED,
            created_at=datetime.utcnow(),
            timeout_seconds=timeout_seconds
        )
        
        self.pending_commands[command_id] = command
        
        # Publish command to device
        topic = f"devices/{device_id}/commands"
        payload = {
            'command_id': command_id,
            'command': command_type,
            'parameters': parameters,
            'timestamp': datetime.utcnow().isoformat(),
            'reply_to': f"cloud/commands/{command_id}/response"
        }
        
        # Publish via MQTT
        logger.info(f"Sending command {command_type} to {device_id}")
        command.status = CommandStatus.SENT
        
        # Wait for response (simplified)
        return {
            'command_id': command_id,
            'status': 'sent',
            'message': 'Command sent to device'
        }
    
    def _is_command_allowed(self, command_type: str, device_type: str) -> bool:
        """Check if command is allowed for device type"""
        allowed = set(self.ALLOWED_COMMANDS.get('all', []))
        allowed.update(self.ALLOWED_COMMANDS.get(device_type, []))
        return command_type in allowed
    
    async def _get_device_type(self, device_id: str) -> str:
        return 'milk_meter'
    
    async def _store_log_reference(self, device_id: str, log_info: Dict):
        """Store log collection reference"""
        pass


# Troubleshooting Console API
class TroubleshootingConsole:
    """Interactive troubleshooting console"""
    
    def __init__(self, ts_service: RemoteTroubleshootingService):
        self.ts = ts_service
    
    async def quick_diagnose(self, device_id: str) -> Dict:
        """Run quick diagnosis on device"""
        
        # Get device status
        status = await self.ts.send_command(
            device_id, 'get_status', {}, timeout_seconds=30
        )
        
        # Ping test
        ping = await self.ts.send_command(
            device_id, 'ping', {'count': 5}, timeout_seconds=30
        )
        
        # Check recent errors
        recent_errors = await self._get_recent_errors(device_id, hours=24)
        
        return {
            'device_id': device_id,
            'timestamp': datetime.utcnow().isoformat(),
            'connectivity': {
                'status': 'online' if status['status'] == 'completed' else 'unresponsive',
                'ping_result': ping.get('result')
            },
            'device_status': status.get('result'),
            'recent_errors': recent_errors,
            'recommendations': self._generate_recommendations(status, recent_errors)
        }
    
    def _generate_recommendations(self, status: Dict, errors: List) -> List[str]:
        """Generate troubleshooting recommendations"""
        recommendations = []
        
        if status.get('status') != 'completed':
            recommendations.append("Device is not responding to commands. Check physical connectivity.")
        
        if errors:
            recommendations.append(f"Device has {len(errors)} errors in last 24h. Review logs for details.")
        
        return recommendations
    
    async def _get_recent_errors(self, device_id: str, hours: int) -> List[Dict]:
        """Get recent error events for device"""
        return []
```

### 7.2 Debug Mode Activation

```python
# Debug Mode Management

class DebugModeManager:
    """Manages debug mode for devices"""
    
    async def activate_debug_mode(self, device_id: str,
                                   duration_minutes: int = 30,
                                   log_level: str = 'debug') -> Dict:
        """Activate debug mode on device"""
        
        command = {
            'command': 'set_debug_level',
            'parameters': {
                'level': log_level,
                'duration_minutes': duration_minutes,
                'log_to_cloud': True,
                'log_local': True
            }
        }
        
        # Schedule automatic deactivation
        await self._schedule_debug_deactivation(device_id, duration_minutes)
        
        return {
            'device_id': device_id,
            'debug_mode': 'activated',
            'duration_minutes': duration_minutes,
            'expires_at': (datetime.utcnow() + timedelta(minutes=duration_minutes)).isoformat()
        }
    
    async def _schedule_debug_deactivation(self, device_id: str, 
                                           delay_minutes: int):
        """Schedule automatic debug mode deactivation"""
        # Implementation would use a scheduler
        pass
```

---

## 8. Device Retirement

### 8.1 Decommissioning Process

```python
# device_retirement_service.py
"""
Smart Dairy Device Retirement Service
Handles secure device decommissioning and data disposal
"""

import logging
from datetime import datetime
from typing import Dict, Optional
from enum import Enum

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)


class RetirementStatus(Enum):
    PENDING = "pending"
    DATA_BACKUP = "data_backup"
    FACTORY_RESET = "factory_reset"
    CERTIFICATE_REVOKED = "certificate_revoked"
    INVENTORY_UPDATED = "inventory_updated"
    COMPLETED = "completed"
    FAILED = "failed"


class DeviceRetirementService:
    """Manages secure device retirement process"""
    
    def __init__(self, db_client, iot_client, cert_manager):
        self.db = db_client
        self.iot = iot_client
        self.cert_manager = cert_manager
    
    async def initiate_retirement(self, 
                                   device_id: str,
                                   reason: str,
                                   requested_by: str,
                                   data_retention_days: int = 365) -> Dict:
        """
        Initiate device retirement process
        """
        
        retirement_id = f"RET-{device_id}-{datetime.utcnow().strftime('%Y%m%d')}"
        
        retirement_record = {
            'retirement_id': retirement_id,
            'device_id': device_id,
            'reason': reason,
            'requested_by': requested_by,
            'status': RetirementStatus.PENDING.value,
            'data_retention_days': data_retention_days,
            'created_at': datetime.utcnow().isoformat(),
            'steps': []
        }
        
        try:
            # Step 1: Archive device data
            logger.info(f"Step 1: Archiving data for {device_id}")
            backup_info = await self._archive_device_data(device_id, data_retention_days)
            retirement_record['steps'].append({
                'step': 'data_archive',
                'status': 'completed',
                'details': backup_info
            })
            retirement_record['status'] = RetirementStatus.DATA_BACKUP.value
            
            # Step 2: Trigger remote factory reset
            logger.info(f"Step 2: Factory reset {device_id}")
            reset_result = await self._trigger_factory_reset(device_id)
            retirement_record['steps'].append({
                'step': 'factory_reset',
                'status': 'completed' if reset_result['success'] else 'failed',
                'details': reset_result
            })
            retirement_record['status'] = RetirementStatus.FACTORY_RESET.value
            
            # Step 3: Revoke certificates
            logger.info(f"Step 3: Revoking certificates for {device_id}")
            revoke_result = await self._revoke_certificates(device_id)
            retirement_record['steps'].append({
                'step': 'certificate_revocation',
                'status': 'completed' if revoke_result['success'] else 'failed',
                'details': revoke_result
            })
            retirement_record['status'] = RetirementStatus.CERTIFICATE_REVOKED.value
            
            # Step 4: Update inventory
            logger.info(f"Step 4: Updating inventory for {device_id}")
            await self._update_inventory(device_id, reason)
            retirement_record['steps'].append({
                'step': 'inventory_update',
                'status': 'completed'
            })
            retirement_record['status'] = RetirementStatus.INVENTORY_UPDATED.value
            
            # Step 5: Update device registry
            logger.info(f"Step 5: Finalizing retirement for {device_id}")
            await self._finalize_retirement(device_id, retirement_id)
            retirement_record['status'] = RetirementStatus.COMPLETED.value
            retirement_record['completed_at'] = datetime.utcnow().isoformat()
            
            logger.info(f"Device {device_id} retired successfully")
            
        except Exception as e:
            retirement_record['status'] = RetirementStatus.FAILED.value
            retirement_record['error'] = str(e)
            logger.error(f"Device retirement failed for {device_id}: {e}")
            raise
        
        return retirement_record
    
    async def _archive_device_data(self, device_id: str, 
                                    retention_days: int) -> Dict:
        """Archive all device data before retirement"""
        
        return {
            'device_id': device_id,
            'archive_date': datetime.utcnow().isoformat(),
            'retain_until': (datetime.utcnow() + timedelta(days=retention_days)).isoformat(),
            'exports': {
                'telemetry': {'records_exported': 0},
                'events': {'records_exported': 0},
                'config': {'records_exported': 0}
            },
            'storage_location': f"s3://smart-dairy-archives/retired-devices/{device_id}/"
        }
    
    async def _trigger_factory_reset(self, device_id: str) -> Dict:
        """Trigger factory reset on device"""
        
        reset_command = {
            'command': 'factory_reset',
            'wipe_data': True,
            'wipe_certificates': True,
            'wipe_config': True,
            'timestamp': datetime.utcnow().isoformat()
        }
        
        return {'success': True, 'command_sent': True}
    
    async def _revoke_certificates(self, device_id: str) -> Dict:
        """Revoke all device certificates"""
        
        device_info = await self._get_device_info(device_id)
        cert_thumbprint = device_info.get('certificate_thumbprint')
        
        revoked_certs = []
        
        if cert_thumbprint:
            revoked_certs.append(cert_thumbprint)
        
        return {
            'success': True,
            'revoked_certificates': revoked_certs,
            'iot_deactivated': True
        }
    
    async def _update_inventory(self, device_id: str, reason: str):
        """Update inventory record for retired device"""
        
        update_query = """
            UPDATE device_inventory
            SET 
                condition = 'retired',
                retirement_date = %s,
                retirement_reason = %s,
                updated_at = %s
            WHERE device_id = %s
        """
        
        await self.db.execute(update_query, 
                             datetime.utcnow(), reason, datetime.utcnow(), device_id)
    
    async def _finalize_retirement(self, device_id: str, retirement_id: str):
        """Finalize device retirement in registry"""
        
        update_query = """
            UPDATE devices
            SET 
                status = 'decommissioned',
                deleted_at = %s,
                retirement_id = %s,
                connection_status = 'offline'
            WHERE device_id = %s
        """
        
        await self.db.execute(update_query, datetime.utcnow(), retirement_id, device_id)
    
    async def _get_device_info(self, device_id: str) -> Dict:
        return {'certificate_thumbprint': 'abc123...'}


# Retirement Workflow
class RetirementWorkflow:
    """Device retirement workflow orchestration"""
    
    RETIREMENT_REASONS = {
        'upgrade': 'Device replaced with newer model',
        'defective': 'Device found defective',
        'end_of_life': 'Device reached end of life',
        'lost': 'Device lost or stolen',
        'transferred': 'Device transferred to another farm',
        'decommissioned': 'Farm decommissioned'
    }
    
    async def request_retirement(self, device_id: str, 
                                  reason: str,
                                  requested_by: str,
                                  approval_required: bool = True) -> Dict:
        """Request device retirement"""
        
        if reason not in self.RETIREMENT_REASONS:
            return {'error': f'Invalid reason. Valid reasons: {list(self.RETIREMENT_REASONS.keys())}'}
        
        if approval_required:
            # Create approval request
            approval_id = await self._create_approval_request(
                device_id=device_id,
                reason=reason,
                requested_by=requested_by
            )
            
            return {
                'status': 'pending_approval',
                'approval_id': approval_id,
                'message': 'Retirement request submitted for approval'
            }
        
        # Direct retirement
        service = DeviceRetirementService(None, None, None)
        result = await service.initiate_retirement(device_id, reason, requested_by)
        
        return result
    
    async def _create_approval_request(self, device_id: str, 
                                        reason: str,
                                        requested_by: str) -> str:
        """Create approval request for retirement"""
        return f"APV-{datetime.utcnow().strftime('%Y%m%d%H%M%S')}"
```

### 8.2 Data Wipe Procedures

```python
# Data Wipe Procedures

class DataWipeService:
    """Manages secure data wipe procedures"""
    
    WIPE_METHODS = {
        'standard': 'Single overwrite with zeros',
        'enhanced': 'Three-pass overwrite (0x00, 0xFF, random)',
        'crypto': 'Cryptographic erase (key destruction)',
        'physical': 'Physical destruction required'
    }
    
    async def perform_secure_wipe(self, device_id: str,
                                   method: str = 'enhanced') -> Dict:
        """Perform secure data wipe on device"""
        
        if method not in self.WIPE_METHODS:
            raise ValueError(f"Invalid wipe method: {method}")
        
        # Send secure wipe command
        wipe_command = {
            'command': 'secure_wipe',
            'method': method,
            'wipe_config': True,
            'wipe_logs': True,
            'wipe_credentials': True,
            'wipe_firmware': False,  # Keep firmware for reuse
            'verification_required': True
        }
        
        return {
            'device_id': device_id,
            'wipe_method': method,
            'status': 'initiated',
            'verification_required': True
        }
    
    async def verify_wipe(self, device_id: str) -> Dict:
        """Verify data wipe completion"""
        
        # Request verification from device
        verify_command = {
            'command': 'verify_wipe',
            'check_config': True,
            'check_logs': True,
            'check_credentials': True
        }
        
        return {
            'device_id': device_id,
            'verified': True,
            'timestamp': datetime.utcnow().isoformat()
        }
```


---

## 9. Inventory Management

### 9.1 Asset Tracking

```python
# inventory_management_service.py
"""
Smart Dairy Device Inventory Management Service
Tracks device assets, warranty, and maintenance
"""

from datetime import datetime, timedelta
from typing import Dict, List, Optional
from dataclasses import dataclass
from enum import Enum


class WarrantyStatus(Enum):
    ACTIVE = "active"
    EXPIRING = "expiring"
    EXPIRED = "expired"


class MaintenanceStatus(Enum):
    SCHEDULED = "scheduled"
    DUE = "due"
    OVERDUE = "overdue"
    COMPLETED = "completed"


@dataclass
class AssetRecord:
    device_id: str
    serial_number: str
    device_type: str
    
    purchase_order: str
    purchase_date: datetime
    unit_cost: float
    currency: str
    
    vendor_id: str
    vendor_name: str
    
    warranty_start: datetime
    warranty_end: datetime
    warranty_terms: str
    extended_warranty: bool
    
    last_maintenance: Optional[datetime]
    next_maintenance: datetime
    maintenance_interval_days: int
    
    current_location: str
    assigned_farm: str
    condition: str


class InventoryManagementService:
    """Manages device inventory and asset tracking"""
    
    def __init__(self, db_client):
        self.db = db_client
    
    async def create_asset_record(self, device_info: Dict, 
                                   purchase_info: Dict) -> AssetRecord:
        """Create new asset record for device"""
        
        warranty_end = purchase_info['purchase_date'] + timedelta(days=365*2)
        
        record = AssetRecord(
            device_id=device_info['device_id'],
            serial_number=device_info['serial_number'],
            device_type=device_info['device_type'],
            purchase_order=purchase_info['purchase_order'],
            purchase_date=purchase_info['purchase_date'],
            unit_cost=purchase_info['unit_cost'],
            currency=purchase_info.get('currency', 'USD'),
            vendor_id=purchase_info['vendor_id'],
            vendor_name=purchase_info['vendor_name'],
            warranty_start=purchase_info['purchase_date'],
            warranty_end=warranty_end,
            warranty_terms='Standard 2-year manufacturer warranty',
            extended_warranty=False,
            last_maintenance=None,
            next_maintenance=purchase_info['purchase_date'] + timedelta(days=90),
            maintenance_interval_days=90,
            current_location='Warehouse',
            assigned_farm='',
            condition='new'
        )
        
        await self._store_asset_record(record)
        return record
    
    async def get_warranty_status(self, device_id: str) -> Dict:
        """Get warranty status for device"""
        
        record = await self._get_asset_record(device_id)
        
        days_until_expiry = (record.warranty_end - datetime.utcnow()).days
        
        if days_until_expiry < 0:
            status = WarrantyStatus.EXPIRED
        elif days_until_expiry < 30:
            status = WarrantyStatus.EXPIRING
        else:
            status = WarrantyStatus.ACTIVE
        
        return {
            'device_id': device_id,
            'warranty_status': status.value,
            'warranty_start': record.warranty_start.isoformat(),
            'warranty_end': record.warranty_end.isoformat(),
            'days_remaining': max(0, days_until_expiry),
            'extended_warranty': record.extended_warranty
        }
    
    async def schedule_maintenance(self, device_id: str,
                                    maintenance_type: str,
                                    scheduled_date: datetime,
                                    technician: str) -> Dict:
        """Schedule maintenance for device"""
        
        maintenance_record = {
            'device_id': device_id,
            'maintenance_type': maintenance_type,
            'scheduled_date': scheduled_date.isoformat(),
            'technician': technician,
            'status': 'scheduled',
            'created_at': datetime.utcnow().isoformat()
        }
        
        # Update next maintenance date
        await self._update_next_maintenance(device_id, scheduled_date)
        
        return maintenance_record
    
    async def get_maintenance_schedule(self, farm_id: str) -> Dict:
        """Get maintenance schedule for farm"""
        
        query = """
            SELECT 
                d.device_id,
                d.device_type,
                d.device_name,
                di.next_maintenance_date,
                di.last_maintenance_date,
                CASE 
                    WHEN di.next_maintenance_date < CURRENT_DATE THEN 'overdue'
                    WHEN di.next_maintenance_date < CURRENT_DATE + INTERVAL '7 days' THEN 'due_soon'
                    ELSE 'scheduled'
                END as maintenance_status
            FROM devices d
            JOIN device_inventory di ON d.device_id = di.device_id
            WHERE d.farm_id = %s
              AND d.status = 'active'
            ORDER BY di.next_maintenance_date
        """
        
        rows = await self.db.fetch(query, farm_id)
        
        return {
            'farm_id': farm_id,
            'maintenance_items': [
                {
                    'device_id': row['device_id'],
                    'device_type': row['device_type'],
                    'device_name': row['device_name'],
                    'next_maintenance': row['next_maintenance_date'].isoformat() if row['next_maintenance_date'] else None,
                    'status': row['maintenance_status']
                }
                for row in rows
            ]
        }
    
    async def transfer_device(self, device_id: str,
                               from_farm: str,
                               to_farm: str,
                               transferred_by: str) -> Dict:
        """Transfer device between farms"""
        
        transfer_record = {
            'device_id': device_id,
            'from_farm': from_farm,
            'to_farm': to_farm,
            'transferred_by': transferred_by,
            'transferred_at': datetime.utcnow().isoformat(),
            'status': 'completed'
        }
        
        # Update device registry
        await self._update_device_farm(device_id, to_farm)
        
        # Update asset record
        await self._update_asset_location(device_id, to_farm)
        
        return transfer_record
    
    async def generate_inventory_report(self, farm_id: Optional[str] = None) -> Dict:
        """Generate comprehensive inventory report"""
        
        query = """
            SELECT 
                d.device_type,
                COUNT(*) as total_count,
                COUNT(*) FILTER (WHERE d.status = 'active') as active_count,
                COUNT(*) FILTER (WHERE di.warranty_end > CURRENT_DATE) as in_warranty,
                COUNT(*) FILTER (WHERE di.warranty_end <= CURRENT_DATE + INTERVAL '30 days' 
                                 AND di.warranty_end > CURRENT_DATE) as warranty_expiring,
                SUM(di.unit_cost) as total_value
            FROM devices d
            LEFT JOIN device_inventory di ON d.device_id = di.device_id
            WHERE %s IS NULL OR d.farm_id = %s
            GROUP BY d.device_type
        """
        
        rows = await self.db.fetch(query, farm_id, farm_id)
        
        return {
            'report_date': datetime.utcnow().isoformat(),
            'farm_id': farm_id or 'all',
            'summary_by_type': [
                {
                    'device_type': row['device_type'],
                    'total_count': row['total_count'],
                    'active_count': row['active_count'],
                    'in_warranty': row['in_warranty'],
                    'warranty_expiring': row['warranty_expiring'],
                    'total_value': float(row['total_value']) if row['total_value'] else 0
                }
                for row in rows
            ]
        }
    
    async def _store_asset_record(self, record: AssetRecord):
        """Store asset record in database"""
        pass
    
    async def _get_asset_record(self, device_id: str) -> AssetRecord:
        """Get asset record from database"""
        return AssetRecord(
            device_id=device_id,
            serial_number='SN123',
            device_type='milk_meter',
            purchase_order='PO123',
            purchase_date=datetime.utcnow(),
            unit_cost=500.0,
            currency='USD',
            vendor_id='V001',
            vendor_name='Vendor Inc',
            warranty_start=datetime.utcnow(),
            warranty_end=datetime.utcnow() + timedelta(days=365),
            warranty_terms='Standard',
            extended_warranty=False,
            last_maintenance=None,
            next_maintenance=datetime.utcnow() + timedelta(days=90),
            maintenance_interval_days=90,
            current_location='Farm',
            assigned_farm='FARM-001',
            condition='good'
        )
    
    async def _update_next_maintenance(self, device_id: str, 
                                        scheduled_date: datetime):
        pass
    
    async def _update_device_farm(self, device_id: str, farm_id: str):
        pass
    
    async def _update_asset_location(self, device_id: str, location: str):
        pass
```

### 9.2 Warranty Management

```python
# Warranty Management Functions

class WarrantyManager:
    """Manages device warranties"""
    
    async def check_warranty_eligibility(self, device_id: str,
                                          issue_type: str) -> Dict:
        """Check if device repair is covered by warranty"""
        
        warranty = await self._get_warranty_info(device_id)
        
        if warranty['status'] == 'expired':
            return {
                'eligible': False,
                'reason': 'Warranty expired',
                'alternative': 'paid_repair'
            }
        
        # Check if issue type is covered
        excluded_issues = warranty.get('exclusions', [])
        if issue_type in excluded_issues:
            return {
                'eligible': False,
                'reason': f'Issue type "{issue_type}" not covered by warranty',
                'alternative': 'paid_repair'
            }
        
        return {
            'eligible': True,
            'warranty_days_remaining': warranty['days_remaining'],
            'coverage_type': warranty['type']
        }
    
    async def extend_warranty(self, device_id: str,
                              extension_years: int,
                              cost: float) -> Dict:
        """Extend device warranty"""
        
        current_warranty = await self._get_warranty_info(device_id)
        
        new_end_date = current_warranty['end_date'] + timedelta(days=365*extension_years)
        
        return {
            'device_id': device_id,
            'extension_years': extension_years,
            'cost': cost,
            'new_warranty_end': new_end_date.isoformat(),
            'status': 'extended'
        }
    
    async def get_warranty_claims(self, device_id: str) -> List[Dict]:
        """Get warranty claim history for device"""
        
        query = """
            SELECT 
                claim_id,
                claim_date,
                issue_description,
                resolution,
                cost_covered,
                status
            FROM warranty_claims
            WHERE device_id = %s
            ORDER BY claim_date DESC
        """
        
        return []
```

---

## 10. Geolocation

### 10.1 Device Location Tracking

```python
# geolocation_service.py
"""
Smart Dairy Device Geolocation Service
Tracks device locations and provides geofencing capabilities
"""

from typing import Dict, List, Optional, Tuple
from dataclasses import dataclass
from datetime import datetime
import math


@dataclass
class GeoLocation:
    latitude: float
    longitude: float
    altitude: Optional[float] = None
    accuracy: Optional[float] = None
    timestamp: Optional[datetime] = None


@dataclass
class GeoFence:
    fence_id: str
    name: str
    farm_id: str
    # Polygon coordinates (lat, lon)
    boundary: List[Tuple[float, float]]
    fence_type: str  # 'inclusion' or 'exclusion'


class GeolocationService:
    """Manages device geolocation and geofencing"""
    
    EARTH_RADIUS_KM = 6371
    
    def __init__(self, db_client):
        self.db = db_client
    
    async def update_device_location(self, device_id: str,
                                      location: GeoLocation) -> Dict:
        """Update device location"""
        
        # Validate coordinates
        if not (-90 <= location.latitude <= 90):
            raise ValueError(f"Invalid latitude: {location.latitude}")
        if not (-180 <= location.longitude <= 180):
            raise ValueError(f"Invalid longitude: {location.longitude}")
        
        # Update device record
        await self._update_device_location(device_id, location)
        
        # Check geofence violations
        violations = await self._check_geofences(device_id, location)
        
        return {
            'device_id': device_id,
            'location': {
                'lat': location.latitude,
                'lon': location.longitude,
                'accuracy': location.accuracy
            },
            'geofence_violations': violations,
            'timestamp': datetime.utcnow().isoformat()
        }
    
    async def get_device_location(self, device_id: str) -> Optional[GeoLocation]:
        """Get current location of device"""
        
        query = """
            SELECT latitude, longitude, altitude, location_accuracy, location_updated_at
            FROM devices
            WHERE device_id = %s
        """
        
        row = await self.db.fetchrow(query, device_id)
        
        if row and row['latitude']:
            return GeoLocation(
                latitude=float(row['latitude']),
                longitude=float(row['longitude']),
                altitude=float(row['altitude']) if row['altitude'] else None,
                accuracy=float(row['location_accuracy']) if row['location_accuracy'] else None,
                timestamp=row['location_updated_at']
            )
        
        return None
    
    async def create_geofence(self, farm_id: str,
                               name: str,
                               boundary: List[Tuple[float, float]],
                               fence_type: str = 'inclusion') -> GeoFence:
        """Create a geofence for a farm"""
        
        fence_id = f"fence-{farm_id}-{datetime.utcnow().strftime('%Y%m%d%H%M%S')}"
        
        fence = GeoFence(
            fence_id=fence_id,
            name=name,
            farm_id=farm_id,
            boundary=boundary,
            fence_type=fence_type
        )
        
        await self._store_geofence(fence)
        return fence
    
    async def check_location_in_fence(self, location: GeoLocation,
                                       fence: GeoFence) -> bool:
        """Check if location is inside geofence boundary"""
        
        # Ray casting algorithm for point-in-polygon
        x, y = location.longitude, location.latitude
        inside = False
        
        n = len(fence.boundary)
        j = n - 1
        
        for i in range(n):
            xi, yi = fence.boundary[i]
            xj, yj = fence.boundary[j]
            
            if ((yi > y) != (yj > y)) and (x < (xj - xi) * (y - yi) / (yj - yi) + xi):
                inside = not inside
            
            j = i
        
        return inside if fence.fence_type == 'inclusion' else not inside
    
    def calculate_distance(self, loc1: GeoLocation, 
                          loc2: GeoLocation) -> float:
        """Calculate distance between two locations in meters"""
        
        # Haversine formula
        lat1, lon1 = math.radians(loc1.latitude), math.radians(loc1.longitude)
        lat2, lon2 = math.radians(loc2.latitude), math.radians(loc2.longitude)
        
        dlat = lat2 - lat1
        dlon = lon2 - lon1
        
        a = math.sin(dlat/2)**2 + math.cos(lat1) * math.cos(lat2) * math.sin(dlon/2)**2
        c = 2 * math.asin(math.sqrt(a))
        
        distance_km = self.EARTH_RADIUS_KM * c
        return distance_km * 1000  # Convert to meters
    
    async def get_nearby_devices(self, location: GeoLocation,
                                  radius_meters: float,
                                  farm_id: Optional[str] = None) -> List[Dict]:
        """Get devices within radius of location"""
        
        query = """
            SELECT 
                device_id,
                device_type,
                latitude,
                longitude,
                (6371 * acos(
                    cos(radians(%s)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(%s)) +
                    sin(radians(%s)) * sin(radians(latitude))
                )) * 1000 as distance_meters
            FROM devices
            WHERE latitude IS NOT NULL
              AND (%s IS NULL OR farm_id = %s)
            HAVING distance_meters <= %s
            ORDER BY distance_meters
        """
        
        rows = await self.db.fetch(query, 
                                    location.latitude, location.longitude, location.latitude,
                                    farm_id, farm_id, radius_meters)
        
        return [
            {
                'device_id': row['device_id'],
                'device_type': row['device_type'],
                'distance_meters': round(row['distance_meters'], 2)
            }
            for row in rows
        ]
    
    async def _update_device_location(self, device_id: str, 
                                       location: GeoLocation):
        """Update device location in database"""
        pass
    
    async def _check_geofences(self, device_id: str, 
                                location: GeoLocation) -> List[Dict]:
        """Check for geofence violations"""
        return []
    
    async def _store_geofence(self, fence: GeoFence):
        """Store geofence in database"""
        pass
```

### 10.2 Location History

```python
# Location History Tracking

class LocationHistoryService:
    """Tracks device location history"""
    
    async def get_location_history(self, device_id: str,
                                    start_time: datetime,
                                    end_time: datetime) -> List[Dict]:
        """Get location history for device"""
        
        query = """
            SELECT 
                time,
                data->'location'->>'latitude' as lat,
                data->'location'->>'longitude' as lon,
                data->'location'->>'accuracy' as accuracy
            FROM device_telemetry
            WHERE device_id = %s
              AND telemetry_type = 'location'
              AND time BETWEEN %s AND %s
            ORDER BY time
        """
        
        rows = await self.db.fetch(query, device_id, start_time, end_time)
        
        return [
            {
                'timestamp': row['time'].isoformat(),
                'latitude': float(row['lat']),
                'longitude': float(row['lon']),
                'accuracy': float(row['accuracy']) if row['accuracy'] else None
            }
            for row in rows
        ]
    
    async def generate_movement_report(self, device_id: str,
                                        days: int = 7) -> Dict:
        """Generate movement analysis report"""
        
        history = await self.get_location_history(
            device_id,
            datetime.utcnow() - timedelta(days=days),
            datetime.utcnow()
        )
        
        if len(history) < 2:
            return {'error': 'Insufficient location data'}
        
        # Calculate total distance traveled
        total_distance = 0
        for i in range(1, len(history)):
            loc1 = GeoLocation(history[i-1]['latitude'], history[i-1]['longitude'])
            loc2 = GeoLocation(history[i]['latitude'], history[i]['longitude'])
            total_distance += self.calculate_distance(loc1, loc2)
        
        return {
            'device_id': device_id,
            'report_period_days': days,
            'total_readings': len(history),
            'total_distance_meters': round(total_distance, 2),
            'first_seen': history[0]['timestamp'],
            'last_seen': history[-1]['timestamp']
        }
```


---

## 11. Cost Management

### 11.1 Data Usage Monitoring

```python
# cost_management_service.py
"""
Smart Dairy Device Cost Management Service
Tracks data usage, power consumption, and operational costs
"""

from datetime import datetime, timedelta
from typing import Dict, List, Optional
from dataclasses import dataclass


@dataclass
class DataUsageMetrics:
    device_id: str
    timestamp: datetime
    bytes_sent: int
    bytes_received: int
    messages_count: int
    connection_duration_seconds: int


@dataclass
class PowerConsumptionMetrics:
    device_id: str
    timestamp: datetime
    battery_percent: Optional[float]
    power_source: str  # 'battery', 'solar', 'ac', ' Poe'
    estimated_consumption_mw: float


class CostManagementService:
    """Manages device operational costs"""
    
    # Cost rates (example rates)
    DATA_COST_PER_MB = 0.01  # $0.01 per MB
    BATTERY_REPLACEMENT_COST = 50.0  # $50 per battery
    MAINTENANCE_COST_PER_VISIT = 100.0  # $100 per maintenance visit
    
    def __init__(self, db_client):
        self.db = db_client
    
    async def track_data_usage(self, metrics: DataUsageMetrics):
        """Track data usage for a device"""
        
        # Store in time-series database
        await self._store_data_usage(metrics)
        
        # Check for data usage alerts
        await self._check_data_alerts(metrics.device_id)
    
    async def get_data_usage_report(self, device_id: str,
                                     start_date: datetime,
                                     end_date: datetime) -> Dict:
        """Get data usage report for device"""
        
        query = """
            SELECT 
                DATE(time) as date,
                SUM((data->>'bytes_sent')::bigint) as bytes_sent,
                SUM((data->>'bytes_received')::bigint) as bytes_received,
                COUNT(*) as message_count
            FROM device_telemetry
            WHERE device_id = %s
              AND telemetry_type = 'data_usage'
              AND time BETWEEN %s AND %s
            GROUP BY DATE(time)
            ORDER BY date
        """
        
        rows = await self.db.fetch(query, device_id, start_date, end_date)
        
        total_bytes = sum(row['bytes_sent'] + row['bytes_received'] for row in rows)
        total_mb = total_bytes / (1024 * 1024)
        estimated_cost = total_mb * self.DATA_COST_PER_MB
        
        return {
            'device_id': device_id,
            'report_period': {
                'start': start_date.isoformat(),
                'end': end_date.isoformat()
            },
            'daily_usage': [
                {
                    'date': row['date'].isoformat(),
                    'mb_sent': round(row['bytes_sent'] / (1024 * 1024), 2),
                    'mb_received': round(row['bytes_received'] / (1024 * 1024), 2),
                    'messages': row['message_count']
                }
                for row in rows
            ],
            'summary': {
                'total_mb': round(total_mb, 2),
                'total_messages': sum(row['message_count'] for row in rows),
                'estimated_cost_usd': round(estimated_cost, 2)
            }
        }
    
    async def track_power_consumption(self, metrics: PowerConsumptionMetrics):
        """Track power consumption for a device"""
        
        await self._store_power_metrics(metrics)
    
    async def get_power_consumption_report(self, device_id: str,
                                            days: int = 30) -> Dict:
        """Get power consumption analysis"""
        
        query = """
            SELECT 
                AVG((data->>'battery_percent')::float) as avg_battery,
                MIN((data->>'battery_percent')::float) as min_battery,
                data->>'power_source' as power_source,
                COUNT(*) FILTER (WHERE data->>'power_source' = 'battery') as battery_cycles
            FROM device_telemetry
            WHERE device_id = %s
              AND telemetry_type = 'power'
              AND time > NOW() - INTERVAL '%s days'
            GROUP BY data->>'power_source'
        """
        
        rows = await self.db.fetch(query, device_id, days)
        
        # Estimate battery life
        battery_cycles = sum(row['battery_cycles'] for row in rows if row['battery_cycles'])
        
        return {
            'device_id': device_id,
            'analysis_period_days': days,
            'battery_statistics': {
                'average_level': round(rows[0]['avg_battery'], 1) if rows else None,
                'minimum_level': round(rows[0]['min_battery'], 1) if rows else None,
                'estimated_cycles': battery_cycles
            },
            'projected_battery_replacement': self._estimate_battery_replacement(days, battery_cycles)
        }
    
    def _estimate_battery_replacement(self, days_tracked: int, 
                                       cycles_observed: int) -> Dict:
        """Estimate when battery replacement will be needed"""
        
        if cycles_observed == 0:
            return {'estimated_date': None, 'confidence': 'low'}
        
        # Assume 500 cycles is typical battery life
        cycles_per_day = cycles_observed / days_tracked
        days_until_depletion = (500 - cycles_observed) / cycles_per_day
        
        estimated_date = datetime.utcnow() + timedelta(days=days_until_depletion)
        
        return {
            'estimated_date': estimated_date.isoformat(),
            'days_remaining': int(days_until_depletion),
            'estimated_cost': self.BATTERY_REPLACEMENT_COST,
            'confidence': 'medium' if days_tracked > 30 else 'low'
        }
    
    async def get_farm_cost_summary(self, farm_id: str,
                                     month: Optional[int] = None,
                                     year: Optional[int] = None) -> Dict:
        """Get comprehensive cost summary for farm"""
        
        if month is None:
            month = datetime.utcnow().month
        if year is None:
            year = datetime.utcnow().year
        
        # Get device count
        device_count = await self._get_farm_device_count(farm_id)
        
        # Get data usage costs
        data_costs = await self._calculate_data_costs(farm_id, month, year)
        
        # Get maintenance costs
        maintenance_costs = await self._calculate_maintenance_costs(farm_id, month, year)
        
        # Get power costs (if applicable)
        power_costs = await self._calculate_power_costs(farm_id, month, year)
        
        total_cost = data_costs + maintenance_costs + power_costs
        
        return {
            'farm_id': farm_id,
            'period': f"{year}-{month:02d}",
            'device_count': device_count,
            'costs': {
                'data_usage': round(data_costs, 2),
                'maintenance': round(maintenance_costs, 2),
                'power': round(power_costs, 2),
                'total': round(total_cost, 2)
            },
            'cost_per_device': round(total_cost / device_count, 2) if device_count > 0 else 0
        }
    
    async def generate_cost_forecast(self, farm_id: str,
                                      months_ahead: int = 12) -> Dict:
        """Generate cost forecast for farm"""
        
        # Get historical data
        historical = await self._get_historical_costs(farm_id, months=6)
        
        # Simple linear projection
        if len(historical) >= 3:
            avg_monthly = sum(historical) / len(historical)
            trend = (historical[-1] - historical[0]) / len(historical)
        else:
            avg_monthly = sum(historical) / len(historical) if historical else 1000
            trend = 0
        
        forecast = []
        for i in range(1, months_ahead + 1):
            projected = avg_monthly + (trend * i)
            future_date = datetime.utcnow() + timedelta(days=30*i)
            forecast.append({
                'month': future_date.strftime('%Y-%m'),
                'projected_cost': round(projected, 2)
            })
        
        return {
            'farm_id': farm_id,
            'forecast_period_months': months_ahead,
            'forecast': forecast,
            'total_projected': round(sum(f['projected_cost'] for f in forecast), 2)
        }
    
    async def _store_data_usage(self, metrics: DataUsageMetrics):
        pass
    
    async def _check_data_alerts(self, device_id: str):
        pass
    
    async def _store_power_metrics(self, metrics: PowerConsumptionMetrics):
        pass
    
    async def _get_farm_device_count(self, farm_id: str) -> int:
        return 100
    
    async def _calculate_data_costs(self, farm_id: str, month: int, year: int) -> float:
        return 150.0
    
    async def _calculate_maintenance_costs(self, farm_id: str, month: int, year: int) -> float:
        return 500.0
    
    async def _calculate_power_costs(self, farm_id: str, month: int, year: int) -> float:
        return 50.0
    
    async def _get_historical_costs(self, farm_id: str, months: int) -> List[float]:
        return [800, 850, 900, 880, 920, 950]
```

### 11.2 Power Consumption Analysis

```python
# Power Consumption Optimization

class PowerOptimizationService:
    """Provides power consumption optimization recommendations"""
    
    async def analyze_power_efficiency(self, device_id: str) -> Dict:
        """Analyze device power efficiency"""
        
        # Get power consumption patterns
        patterns = await self._get_power_patterns(device_id)
        
        recommendations = []
        
        # Check for high consumption during idle periods
        if patterns.get('idle_consumption_high'):
            recommendations.append({
                'issue': 'High power consumption during idle periods',
                'recommendation': 'Enable sleep mode during inactive hours',
                'potential_savings_percent': 15
            })
        
        # Check transmission frequency
        if patterns.get('transmission_frequency_high'):
            recommendations.append({
                'issue': 'Telemetry transmission too frequent',
                'recommendation': 'Increase telemetry interval from 60s to 120s',
                'potential_savings_percent': 10
            })
        
        # Check signal strength vs power
        if patterns.get('low_signal_high_power'):
            recommendations.append({
                'issue': 'Device using high power due to weak signal',
                'recommendation': 'Consider relocating device or adding repeater',
                'potential_savings_percent': 20
            })
        
        return {
            'device_id': device_id,
            'efficiency_score': patterns.get('efficiency_score', 70),
            'recommendations': recommendations,
            'potential_total_savings_percent': sum(
                r['potential_savings_percent'] for r in recommendations
            )
        }
    
    async def optimize_sleep_schedule(self, device_id: str) -> Dict:
        """Generate optimal sleep schedule for device"""
        
        # Analyze activity patterns
        activity = await self._get_activity_patterns(device_id)
        
        # Identify low-activity periods
        sleep_windows = []
        
        # Example: Sleep between 1 AM and 5 AM
        sleep_windows.append({
            'start_time': '01:00',
            'end_time': '05:00',
            'days': ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
            'sleep_mode': 'deep',
            'wake_interval_minutes': 15  # Wake briefly to check for critical alerts
        })
        
        return {
            'device_id': device_id,
            'recommended_sleep_windows': sleep_windows,
            'expected_battery_life_extension_days': 30
        }
    
    async def _get_power_patterns(self, device_id: str) -> Dict:
        return {}
    
    async def _get_activity_patterns(self, device_id: str) -> Dict:
        return {}
```

---

## 12. Integration with ERP

### 12.1 Asset Synchronization

```python
# erp_integration_service.py
"""
Smart Dairy ERP Integration Service
Synchronizes device data with enterprise resource planning systems
"""

from datetime import datetime
from typing import Dict, List, Optional


class ERPIntegrationService:
    """Handles integration with ERP systems (SAP, Oracle, etc.)"""
    
    def __init__(self, erp_client, db_client):
        self.erp = erp_client
        self.db = db_client
    
    async def sync_asset_to_erp(self, device_id: str) -> Dict:
        """Synchronize device asset to ERP system"""
        
        # Get device information
        device = await self._get_device_with_inventory(device_id)
        
        # Map to ERP asset format
        erp_asset = {
            'asset_id': device['device_id'],
            'asset_type': self._map_device_type_to_erp(device['device_type']),
            'serial_number': device['serial_number'],
            'purchase_date': device['purchase_date'].isoformat(),
            'purchase_cost': device['unit_cost'],
            'currency': device['currency'],
            'vendor_code': device['vendor_id'],
            'location_code': device['farm_id'],
            'warranty_start': device['warranty_start'].isoformat(),
            'warranty_end': device['warranty_end'].isoformat(),
            'status': self._map_status_to_erp(device['status']),
            'depreciation_method': 'straight_line',
            'useful_life_years': 5
        }
        
        # Send to ERP
        result = await self.erp.create_or_update_asset(erp_asset)
        
        return {
            'device_id': device_id,
            'erp_asset_id': result.get('erp_asset_id'),
            'sync_status': 'success',
            'timestamp': datetime.utcnow().isoformat()
        }
    
    async def sync_inventory_movement(self, device_id: str,
                                       movement_type: str,
                                       from_location: str,
                                       to_location: str) -> Dict:
        """Synchronize inventory movement to ERP"""
        
        movement = {
            'transaction_type': movement_type,  # 'transfer', 'deployment', 'retirement'
            'asset_id': device_id,
            'from_location': from_location,
            'to_location': to_location,
            'transaction_date': datetime.utcnow().isoformat(),
            'reference_document': f"IOT-{movement_type.upper()}-{datetime.utcnow().strftime('%Y%m%d')}"
        }
        
        result = await self.erp.record_inventory_movement(movement)
        
        return {
            'movement_recorded': True,
            'erp_transaction_id': result.get('transaction_id')
        }
    
    async def sync_maintenance_to_erp(self, device_id: str,
                                       maintenance_record: Dict) -> Dict:
        """Synchronize maintenance record to ERP"""
        
        erp_maintenance = {
            'asset_id': device_id,
            'maintenance_type': maintenance_record['type'],
            'maintenance_date': maintenance_record['date'].isoformat(),
            'technician_id': maintenance_record['technician'],
            'cost': maintenance_record.get('cost', 0),
            'description': maintenance_record['description'],
            'parts_replaced': maintenance_record.get('parts', []),
            'next_due_date': maintenance_record.get('next_due_date', '').isoformat() if maintenance_record.get('next_due_date') else None
        }
        
        result = await self.erp.create_maintenance_record(erp_maintenance)
        
        return {
            'maintenance_recorded': True,
            'erp_maintenance_id': result.get('maintenance_id')
        }
    
    async def sync_warranty_claim(self, device_id: str,
                                   claim_details: Dict) -> Dict:
        """Synchronize warranty claim to ERP"""
        
        erp_claim = {
            'asset_id': device_id,
            'claim_date': datetime.utcnow().isoformat(),
            'issue_description': claim_details['description'],
            'failure_code': claim_details.get('failure_code'),
            'repair_cost_estimate': claim_details.get('estimated_cost'),
            'warranty_status': 'under_warranty',
            'vendor_rma_required': True
        }
        
        result = await self.erp.create_warranty_claim(erp_claim)
        
        return {
            'claim_submitted': True,
            'erp_claim_id': result.get('claim_id'),
            'rma_number': result.get('rma_number')
        }
    
    async def bulk_sync_to_erp(self, farm_id: Optional[str] = None) -> Dict:
        """Bulk synchronize all devices to ERP"""
        
        # Get devices to sync
        devices = await self._get_devices_for_sync(farm_id)
        
        results = {
            'total_devices': len(devices),
            'synced': 0,
            'failed': 0,
            'errors': []
        }
        
        for device in devices:
            try:
                await self.sync_asset_to_erp(device['device_id'])
                results['synced'] += 1
            except Exception as e:
                results['failed'] += 1
                results['errors'].append({
                    'device_id': device['device_id'],
                    'error': str(e)
                })
        
        return results
    
    async def reconcile_with_erp(self) -> Dict:
        """Reconcile device inventory with ERP records"""
        
        # Get devices from local system
        local_devices = await self._get_all_active_devices()
        
        # Get assets from ERP
        erp_assets = await self.erp.get_assets()
        
        # Find discrepancies
        local_ids = {d['device_id'] for d in local_devices}
        erp_ids = {a['asset_id'] for a in erp_assets}
        
        missing_in_erp = local_ids - erp_ids
        orphaned_in_erp = erp_ids - local_ids
        
        return {
            'reconciliation_date': datetime.utcnow().isoformat(),
            'local_device_count': len(local_devices),
            'erp_asset_count': len(erp_assets),
            'discrepancies': {
                'missing_in_erp': list(missing_in_erp),
                'orphaned_in_erp': list(orphaned_in_erp)
            },
            'status': 'clean' if not missing_in_erp and not orphaned_in_erp else 'needs_attention'
        }
    
    def _map_device_type_to_erp(self, device_type: str) -> str:
        """Map device type to ERP asset type code"""
        mapping = {
            'milk_meter': 'IOT-MM',
            'environmental_sensor': 'IOT-ES',
            'rfid_reader': 'IOT-RFID',
            'wearable': 'IOT-WRL',
            'gateway': 'IOT-GW'
        }
        return mapping.get(device_type, 'IOT-OTHER')
    
    def _map_status_to_erp(self, status: str) -> str:
        """Map device status to ERP asset status"""
        mapping = {
            'active': 'IN_SERVICE',
            'maintenance': 'MAINTENANCE',
            'decommissioned': 'RETIRED',
            'suspended': 'INACTIVE'
        }
        return mapping.get(status, 'UNKNOWN')
    
    async def _get_device_with_inventory(self, device_id: str) -> Dict:
        return {}
    
    async def _get_devices_for_sync(self, farm_id: Optional[str]) -> List[Dict]:
        return []
    
    async def _get_all_active_devices(self) -> List[Dict]:
        return []


# ERP API Interface (Example for generic ERP)
class ERPClient:
    """Generic ERP client interface"""
    
    async def create_or_update_asset(self, asset_data: Dict) -> Dict:
        """Create or update asset in ERP"""
        return {'erp_asset_id': asset_data['asset_id']}
    
    async def record_inventory_movement(self, movement: Dict) -> Dict:
        """Record inventory movement in ERP"""
        return {'transaction_id': 'TXN123'}
    
    async def create_maintenance_record(self, maintenance: Dict) -> Dict:
        """Create maintenance record in ERP"""
        return {'maintenance_id': 'MNT456'}
    
    async def create_warranty_claim(self, claim: Dict) -> Dict:
        """Create warranty claim in ERP"""
        return {'claim_id': 'CLM789', 'rma_number': 'RMA-001'}
    
    async def get_assets(self) -> List[Dict]:
        """Get all assets from ERP"""
        return []
```


---

## 13. Appendices

### Appendix A: Device Management API Reference

#### A.1 REST API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/devices` | GET | List all devices |
| `/api/v1/devices` | POST | Register new device |
| `/api/v1/devices/{id}` | GET | Get device details |
| `/api/v1/devices/{id}` | PUT | Update device |
| `/api/v1/devices/{id}` | DELETE | Delete/retire device |
| `/api/v1/devices/{id}/config` | GET | Get device configuration |
| `/api/v1/devices/{id}/config` | POST | Update device configuration |
| `/api/v1/devices/{id}/logs` | GET | Get device logs |
| `/api/v1/devices/{id}/commands` | POST | Send command to device |
| `/api/v1/ota/campaigns` | GET | List OTA campaigns |
| `/api/v1/ota/campaigns` | POST | Create OTA campaign |
| `/api/v1/ota/campaigns/{id}` | GET | Get campaign status |
| `/api/v1/ota/campaigns/{id}/start` | POST | Start campaign |
| `/api/v1/inventory` | GET | List inventory |
| `/api/v1/inventory/report` | GET | Generate inventory report |

#### A.2 MQTT Topics

| Topic Pattern | Direction | Description |
|---------------|-----------|-------------|
| `devices/{id}/telemetry` | Device -> Cloud | Device telemetry data |
| `devices/{id}/status` | Device -> Cloud | Device status updates |
| `devices/{id}/commands` | Cloud -> Device | Commands to device |
| `devices/{id}/commands/{cmd_id}/response` | Device -> Cloud | Command response |
| `devices/{id}/config` | Cloud -> Device | Configuration updates |
| `devices/{id}/ota/update` | Cloud -> Device | OTA update notifications |
| `devices/{id}/ota/status` | Device -> Cloud | OTA status updates |

#### A.3 API Authentication

All API requests require authentication using one of the following methods:

1. **API Key**: Header `X-API-Key: your-api-key`
2. **OAuth 2.0**: Bearer token in `Authorization: Bearer {token}` header
3. **mTLS**: Client certificate authentication for device connections

### Appendix B: Device Registry Schema Reference

#### B.1 Devices Table

| Column | Type | Description |
|--------|------|-------------|
| id | UUID | Primary key |
| device_id | VARCHAR(50) | Unique device identifier |
| device_type | ENUM | Type of device |
| device_name | VARCHAR(100) | Human-readable name |
| hardware_version | VARCHAR(20) | Hardware version |
| firmware_version | VARCHAR(20) | Current firmware version |
| serial_number | VARCHAR(50) | Manufacturer serial number |
| farm_id | VARCHAR(20) | Associated farm |
| status | ENUM | Device lifecycle status |
| connection_status | ENUM | Current connection state |
| latitude | DECIMAL(10,8) | GPS latitude |
| longitude | DECIMAL(11,8) | GPS longitude |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

#### B.2 Device States Table

| Column | Type | Description |
|--------|------|-------------|
| device_id | VARCHAR(50) | Device reference |
| desired_state | JSONB | Desired configuration state |
| reported_state | JSONB | Reported device state |
| sync_status | VARCHAR(20) | Synchronization status |

### Appendix C: Utility Scripts

#### C.1 Device Bulk Import Script

```python
#!/usr/bin/env python3
"""
Device Bulk Import Script
Imports devices from CSV file into device registry
"""

import csv
import asyncio
import argparse
from datetime import datetime


async def bulk_import_devices(csv_file: str, farm_id: str):
    """Import devices from CSV file"""
    
    # Expected CSV columns:
    # device_id, device_type, hardware_version, serial_number, 
    # manufacturing_date, batch_number
    
    devices = []
    
    with open(csv_file, 'r') as f:
        reader = csv.DictReader(f)
        for row in reader:
            device = {
                'device_id': row['device_id'],
                'device_type': row['device_type'],
                'hardware_version': row['hardware_version'],
                'serial_number': row['serial_number'],
                'manufacturing_date': row['manufacturing_date'],
                'batch_number': row.get('batch_number', ''),
                'farm_id': farm_id,
                'status': 'provisioned',
                'created_at': datetime.utcnow().isoformat()
            }
            devices.append(device)
    
    # Insert into database
    print(f"Importing {len(devices)} devices...")
    
    # Database insertion logic here
    
    return {'imported': len(devices), 'errors': []}


if __name__ == '__main__':
    parser = argparse.ArgumentParser(description='Bulk import devices')
    parser.add_argument('csv_file', help='CSV file with device data')
    parser.add_argument('--farm-id', required=True, help='Target farm ID')
    args = parser.parse_args()
    
    result = asyncio.run(bulk_import_devices(args.csv_file, args.farm_id))
    print(f"Import complete: {result}")
```

#### C.2 Device Health Check Script

```python
#!/usr/bin/env python3
"""
Device Health Check Script
Performs health checks on all devices and generates report
"""

import asyncio
from datetime import datetime, timedelta


async def check_device_health(farm_id: Optional[str] = None):
    """Check health of all devices"""
    
    # Get devices to check
    devices = await get_devices(farm_id)
    
    health_report = {
        'check_time': datetime.utcnow().isoformat(),
        'total_devices': len(devices),
        'healthy': 0,
        'warning': 0,
        'critical': 0,
        'offline': 0,
        'issues': []
    }
    
    for device in devices:
        health = await check_single_device(device['device_id'])
        
        if health['status'] == 'healthy':
            health_report['healthy'] += 1
        elif health['status'] == 'warning':
            health_report['warning'] += 1
        elif health['status'] == 'critical':
            health_report['critical'] += 1
        else:
            health_report['offline'] += 1
        
        if health.get('issues'):
            health_report['issues'].append({
                'device_id': device['device_id'],
                'issues': health['issues']
            })
    
    return health_report


async def check_single_device(device_id: str) -> Dict:
    """Check health of single device"""
    
    issues = []
    
    # Check last seen
    last_seen = await get_last_seen(device_id)
    if datetime.utcnow() - last_seen > timedelta(hours=1):
        issues.append('Device offline for > 1 hour')
    
    # Check error count
    error_count = await get_error_count(device_id, hours=24)
    if error_count > 10:
        issues.append(f'High error count: {error_count}')
    
    # Determine status
    if len(issues) == 0:
        status = 'healthy'
    elif any('offline' in i for i in issues):
        status = 'offline'
    elif any('critical' in i.lower() for i in issues):
        status = 'critical'
    else:
        status = 'warning'
    
    return {
        'device_id': device_id,
        'status': status,
        'issues': issues
    }


if __name__ == '__main__':
    report = asyncio.run(check_device_health())
    print(json.dumps(report, indent=2))
```

#### C.3 Firmware Signing Script

```python
#!/usr/bin/env python3
"""
Firmware Image Signing Script
Signs firmware images for OTA distribution
"""

import hashlib
import argparse
from pathlib import Path
from cryptography.hazmat.primitives import hashes, serialization
from cryptography.hazmat.primitives.asymmetric import padding, rsa


def sign_firmware(firmware_path: str, private_key_path: str, 
                  output_path: str):
    """Sign firmware image"""
    
    # Load firmware
    firmware_data = Path(firmware_path).read_bytes()
    
    # Calculate hash
    firmware_hash = hashlib.sha256(firmware_data).hexdigest()
    
    # Load private key
    private_key = serialization.load_pem_private_key(
        Path(private_key_path).read_bytes(),
        password=None
    )
    
    # Create signature
    signature = private_key.sign(
        firmware_hash.encode(),
        padding.PSS(mgf=padding.MGF1(hashes.SHA256()), salt_length=32),
        hashes.SHA256()
    )
    
    # Create manifest
    manifest = {
        'firmware_file': Path(firmware_path).name,
        'file_size': len(firmware_data),
        'sha256_hash': firmware_hash,
        'signature': signature.hex(),
        'signing_key_id': 'key-2026-001',
        'signed_at': datetime.utcnow().isoformat()
    }
    
    # Save manifest
    Path(output_path).write_text(json.dumps(manifest, indent=2))
    
    print(f"Firmware signed: {output_path}")
    print(f"SHA256: {firmware_hash}")


if __name__ == '__main__':
    parser = argparse.ArgumentParser(description='Sign firmware image')
    parser.add_argument('firmware', help='Firmware binary file')
    parser.add_argument('--key', required=True, help='Private key file')
    parser.add_argument('--output', required=True, help='Output manifest file')
    args = parser.parse_args()
    
    sign_firmware(args.firmware, args.key, args.output)
```

### Appendix D: Configuration Examples

#### D.1 Device Configuration Example

```json
{
  "device_id": "SD-MM-2026-001234",
  "configuration": {
    "telemetry": {
      "measurement_interval_sec": 1,
      "batch_size": 60,
      "transmission_interval_sec": 60,
      "compression_enabled": true
    },
    "sensors": {
      "flow_sensor": {
        "enabled": true,
        "sampling_rate_hz": 10,
        "auto_zero_interval_hours": 24
      },
      "temperature_sensor": {
        "enabled": true,
        "update_interval_sec": 10
      }
    },
    "thresholds": {
      "min_flow_rate_lpm": 0.1,
      "max_flow_rate_lpm": 50.0,
      "temperature_alarm_c": 40.0
    },
    "calibration": {
      "flow_offset": 0.0,
      "flow_gain": 1.0,
      "temperature_offset": 0.0
    },
    "network": {
      "connection_timeout_sec": 30,
      "keepalive_interval_sec": 60,
      "retry_attempts": 3
    }
  },
  "version": 3,
  "updated_at": "2026-01-31T10:00:00Z",
  "updated_by": "admin@smartdairy.com"
}
```

#### D.2 OTA Campaign Configuration

```json
{
  "campaign_name": "Milk Meter v2.1.0 Security Update",
  "description": "Critical security patch for SSL certificate validation",
  "firmware_image": {
    "version": "2.1.0",
    "sha256_hash": "a1b2c3d4e5f6...",
    "signature": "signature_hex...",
    "download_url": "https://firmware.smartdairy.com/v2.1.0/milk-meter.bin"
  },
  "target_criteria": {
    "device_type": "milk_meter",
    "hardware_versions": ["2.0", "2.1"],
    "firmware_versions": ["<2.1.0"],
    "farms": ["FARM-001", "FARM-002"]
  },
  "rollout_config": {
    "strategy": "canary",
    "canary_percentage": 5,
    "canary_duration_hours": 24,
    "auto_promote": false,
    "maintenance_window": {
      "start_time": "02:00",
      "end_time": "06:00",
      "timezone": "Asia/Singapore"
    }
  },
  "rollback_config": {
    "auto_rollback": true,
    "failure_threshold_percent": 10
  }
}
```

### Appendix E: Troubleshooting Guide

#### E.1 Common Issues and Solutions

| Issue | Symptoms | Solution |
|-------|----------|----------|
| Device offline | No telemetry for > 1 hour | Check power, network connectivity |
| High error rate | > 10 errors/hour | Review device logs, check sensor calibration |
| Low battery | < 20% remaining | Schedule battery replacement, check solar panel |
| Poor signal | < -70 dBm | Relocate device or add signal repeater |
| Config sync failed | Desired != Reported | Force config refresh, check device memory |
| OTA failed | Stuck at downloading | Check storage space, retry with smaller batch |

#### E.2 Debug Commands

```bash
# Check device connectivity
./device-cli.py ping SD-MM-2026-001234

# Get device logs
./device-cli.py logs SD-MM-2026-001234 --hours 24

# Force config refresh
./device-cli.py config refresh SD-MM-2026-001234

# Run diagnostics
./device-cli.py diagnose SD-MM-2026-001234 --suite full

# Check OTA status
./device-cli.py ota status SD-MM-2026-001234
```

### Appendix F: Glossary

| Term | Definition |
|------|------------|
| **IoT** | Internet of Things |
| **OTA** | Over-The-Air (firmware updates) |
| **CMDB** | Configuration Management Database |
| **JIT** | Just-In-Time (provisioning) |
| **MQTT** | Message Queuing Telemetry Transport |
| **HSM** | Hardware Security Module |
| **RFID** | Radio Frequency Identification |
| **GPS** | Global Positioning System |
| **dBm** | Decibel-milliwatts (signal strength) |
| **S/N** | Serial Number |
| **SKU** | Stock Keeping Unit |
| **RMA** | Return Merchandise Authorization |
| **CA** | Certificate Authority |
| **mTLS** | Mutual TLS |

---

## Document Sign-off

| Role | Name | Signature | Date |
|------|------|-----------|------|
| IoT Architect | | | |
| DevOps Lead | | | |
| Security Lead | | | |
| Project Manager | | | |

---

## Related Documents

- I-001: Smart Dairy System Architecture
- I-002: Security Implementation Guide
- I-003: API Specification
- I-004: Database Schema Design
- I-005: Deployment Guide

---

*End of Document I-013*
