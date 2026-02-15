# Document I-006: Environmental Sensor Integration

## Smart Dairy Ltd. - Smart Web Portal System

---

| **Field** | **Value** |
|-----------|-----------|
| **Document ID** | I-006 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | IoT Engineer |
| **Owner** | IoT Architect |
| **Reviewer** | Farm Operations Manager |
| **Status** | Draft |
| **Classification** | Internal Use |

---

## Document History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 31, 2026 | IoT Engineer | Initial document creation |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Sensor Types](#2-sensor-types)
3. [Sensor Specifications](#3-sensor-specifications)
4. [Placement Strategy](#4-placement-strategy)
5. [Communication Protocols](#5-communication-protocols)
6. [Data Collection](#6-data-collection)
7. [Calibration](#7-calibration)
8. [Alert Thresholds](#8-alert-thresholds)
9. [Integration with Control Systems](#9-integration-with-control-systems)
10. [Dashboard Visualization](#10-dashboard-visualization)
11. [Maintenance](#11-maintenance)
12. [Troubleshooting](#12-troubleshooting)
13. [Appendices](#13-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides comprehensive guidelines for the integration of environmental sensors within Smart Dairy Ltd.'s dairy farming operations. It covers the selection, installation, configuration, and maintenance of sensor networks designed to monitor critical environmental parameters affecting dairy cattle health, productivity, and welfare.

### 1.2 Environmental Monitoring Importance

Environmental conditions directly impact:

- **Milk Production**: Heat stress can reduce milk yield by 10-35%
- **Reproductive Performance**: High temperatures decrease conception rates
- **Animal Health**: Poor air quality causes respiratory issues
- **Feed Efficiency**: Stressed animals require more feed per kg of milk
- **Longevity**: Chronic stress shortens productive lifespan

### 1.3 Bangladesh Climate Context

The sensor network must be designed for Bangladesh's challenging climate:

| Parameter | Range | Impact on Operations |
|-----------|-------|---------------------|
| Temperature | 15°C - 40°C | Heat stress risk 8 months/year |
| Humidity | 60% - 90% | Amplifies heat stress, corrosion risk |
| Rainfall | 1,500-2,500mm/year | Monsoon protection critical |
| Dust | High during dry season | Sensor contamination risk |
| Power Grid | Unstable | Backup power essential |

### 1.4 Scope

This document covers:
- All environmental sensors in barns, milking parlors, and calf housing
- Communication infrastructure and protocols
- Data collection and processing systems
- Integration with environmental control systems
- Maintenance procedures and troubleshooting

---

## 2. Sensor Types

### 2.1 Temperature Sensors (Air & Water)

#### 2.1.1 Air Temperature

**Purpose**: Monitor ambient temperature to detect heat stress conditions

**Recommended Sensors**:
- **SHT30/SHT40** (Sensirion) - Primary choice
- **DS18B20** (Maxim) - Backup/secondary
- **PT100** (Industrial grade) - High accuracy zones

**Key Features**:
- Accuracy: ±0.2°C (SHT30), ±0.5°C (DS18B20)
- Range: -40°C to +125°C
- Response time: <10 seconds
- Low power consumption (<1μA standby)

#### 2.1.2 Water Temperature

**Purpose**: Monitor drinking water temperature for optimal intake

**Recommended Sensors**:
- **DS18B20 Waterproof** - Immersion probes
- **PT100 with 4-20mA transmitter** - Industrial applications

**Installation Notes**:
- Install in water tanks and drinking troughs
- Ensure food-grade stainless steel housing
- Position away from direct sunlight

### 2.2 Humidity Sensors

**Purpose**: Measure relative humidity (RH) to calculate Temperature-Humidity Index (THI)

**Recommended Sensors**:
- **SHT30/SHT40** (Combined temp/humidity)
- **DHT22** (Economical alternative)
- **HMP110** (Vaisala - High accuracy)

**Specifications**:
- Accuracy: ±2% RH (SHT30), ±5% RH (DHT22)
- Range: 0-100% RH
- Important for Bangladesh's high humidity environment

**THI Calculation**:
```
THI = (1.8 × T + 32) - [(0.55 - 0.0055 × RH) × (1.8 × T - 26)]
Where:
T = Temperature in °C
RH = Relative Humidity in %
```

### 2.3 Ammonia (NH3) Sensors

**Purpose**: Detect ammonia buildup from urine and manure decomposition

**Recommended Sensors**:
- **MICS-6814** (Multi-gas, includes NH3)
- **MQ-137** (Dedicated NH3 sensor)
- **ZE03-NH3** (Electrochemical, higher accuracy)

**Critical Thresholds**:
- 25 ppm: Warning level
- 50 ppm: Action required
- 100 ppm: Health risk to animals and workers

**Calibration Notes**:
- Electrochemical sensors require 24-48 hour warm-up
- Zero calibration in fresh air required monthly
- Span calibration with known gas concentration quarterly

### 2.4 CO2 Sensors

**Purpose**: Monitor ventilation adequacy and air quality

**Recommended Sensors**:
- **SCD30** (Sensirion - NDIR technology)
- **MH-Z19B** (Low cost NDIR)
- **K30** (Industrial grade)

**Specifications**:
- Range: 0-5,000 ppm (up to 10,000 ppm for NDIR)
- Accuracy: ±(30 ppm + 3%)
- Automatic Baseline Correction (ABC) enabled

**Typical Levels**:
- Outdoor: 400 ppm
- Well-ventilated barn: 600-800 ppm
- Poor ventilation: >1,500 ppm
- Critical: >3,000 ppm

### 2.5 Light Intensity Sensors

**Purpose**: Monitor lighting conditions for photoperiod management

**Recommended Sensors**:
- **BH1750** (Digital light sensor)
- **TSL2561** (Broad spectrum)
- **Li-Cor Quantum Sensor** (Research grade)

**Specifications**:
- Range: 1 - 65,535 lux (BH1750)
- Resolution: 1 lux
- Spectral response: Approximately human eye

**Photoperiod Requirements**:
- Lactating cows: 16-18 hours light, 6-8 hours dark
- Light intensity: Minimum 150-200 lux at cow eye level

### 2.6 Airflow/Wind Sensors

**Purpose**: Monitor natural and mechanical ventilation effectiveness

**Recommended Sensors**:
- **Hot-wire Anemometers** (Low airflow measurement)
- **Vane Anemometers** (Higher airflow, >0.5 m/s)
- **Ultrasonic Anemometers** (No moving parts)

**Specifications**:
- Range: 0.1 - 30 m/s
- Accuracy: ±0.1 m/s or ±2% of reading
- Temperature compensation required

**Placement**:
- Inlet airflow measurement
- Outlet/exhaust airflow
- Cross-ventilation zones

---

## 3. Sensor Specifications

### 3.1 Complete Specifications Table

| Parameter | Sensor Model | Accuracy | Range | Power | Response Time |
|-----------|--------------|----------|-------|-------|---------------|
| Air Temp | SHT30 | ±0.2°C | -40 to 125°C | 2.7-5.5V, 150μA | <8s |
| Humidity | SHT30 | ±2% RH | 0-100% RH | 2.7-5.5V, 150μA | <8s |
| NH3 | ZE03-NH3 | ±5% | 0-100 ppm | 5V, <150mA | <60s |
| CO2 | SCD30 | ±(30ppm+3%) | 0-10,000 ppm | 3.3-5.5V, 60mA | 20s |
| Light | BH1750 | ±20% | 1-65,535 lux | 2.4-3.6V, 260μA | 120ms |
| Airflow | YGC-FS | ±0.5m/s | 0-30 m/s | 12-24V, 50mA | <1s |
| Water Temp | DS18B20 | ±0.5°C | -55 to 125°C | 3-5.5V, 1mA | <1s |

### 3.2 Power Requirements

#### 3.2.1 Power Budget Calculation

**Per Sensor Node (Typical 7 sensors)**:

| Component | Active Current | Duty Cycle | Average Current |
|-----------|---------------|------------|-----------------|
| Microcontroller (ESP32) | 120mA | 5% | 6mA |
| SHT30 (Temp/Hum) | 150μA | 100% | 0.15mA |
| SCD30 (CO2) | 60mA | 10% | 6mA |
| ZE03-NH3 | 150mA | 5% | 7.5mA |
| BH1750 (Light) | 260μA | 1% | 0.003mA |
| LoRa Module | 40mA TX | 0.1% | 0.04mA |
| **Total** | | | **~20mA** |

**Battery Life Calculation**:
- 12V, 20Ah Battery = 240Wh
- At 20mA, 5V = 100mW
- Battery life: 240Wh / 0.1W = 2,400 hours ≈ 100 days
- With solar charging: Continuous operation

### 3.3 Bangladesh Climate Adaptations

#### 3.3.1 Environmental Protection Requirements

| Challenge | Solution | Specification |
|-----------|----------|---------------|
| High Humidity | Conformal coating | IPC-CC-830 compliant |
| Dust | IP65+ enclosure | NEMA 4X equivalent |
| Monsoon Rain | Waterproof housing | IP67 minimum |
| Temperature | Industrial grade components | -40°C to +85°C rated |
| Corrosion | Stainless steel hardware | 316 grade |
| UV Exposure | UV-resistant materials | UV stabilized plastics |

#### 3.3.2 Power Backup Strategy

```
Primary Power: Grid 220V AC → SMPS 12V DC
                    ↓
            Charge Controller
                    ↓
            ┌──────┴──────┐
            ↓             ↓
       Battery 12V    Solar Panel
       20Ah AGM       50W, 18V
            ↓
       DC-DC Buck Converter
            ↓
       5V Regulated Output
```

---

## 4. Placement Strategy

### 4.1 Barn Zone Division

```
┌─────────────────────────────────────────────────────────────────┐
│                        BARN LAYOUT - TOP VIEW                    │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────┐  ┌─────────────────────────────────────────┐       │
│  │ FEED    │  │                                         │       │
│  │ AREA    │  │      COW RESTING/FEEDING ZONE           │       │
│  │   F     │  │      (16m × 30m)                        │       │
│  │         │  │                                         │       │
│  └─────────┘  │  ┌─────┐  ┌─────┐  ┌─────┐  ┌─────┐    │       │
│               │  │ S1  │  │ S2  │  │ S3  │  │ S4  │    │       │
│               │  │     │  │     │  │     │  │     │    │       │
│               │  └─────┘  └─────┘  └─────┘  └─────┘    │       │
│               │                                         │       │
│  ┌─────────┐  │  ┌─────┐  ┌─────┐  ┌─────┐  ┌─────┐    │       │
│  │ WATER   │  │  │ S5  │  │ S6  │  │ S7  │  │ S8  │    │       │
│  │ TANKS   │  │  │     │  │     │  │     │  │     │    │       │
│  │   W     │  │  └─────┘  └─────┘  └─────┘  └─────┘    │       │
│  └─────────┘  │                                         │       │
│               │  Sensor Spacing: 6m × 6m Grid            │       │
│               │                                         │       │
│  ┌─────────┐  └─────────────────────────────────────────┘       │
│  │ MANURE  │                                                    │
│  │ CHANNEL │                    ENTRANCE                        │
│  │    M    │                      E                             │
│  └─────────┘                                                    │
│                                                                  │
│  Legend: S1-S8 = Environmental Sensor Nodes                      │
│          F = Feed Area Monitoring Point                          │
│          W = Water System Monitoring                             │
│          M = Manure Pit NH3 Monitoring                           │
│          E = Entrance/Transition Zone                            │
└─────────────────────────────────────────────────────────────────┘
```

### 4.2 Height Specifications

| Sensor Type | Height from Floor | Rationale |
|-------------|-------------------|-----------|
| Temperature | 1.5m (cow height) | Represents animal experience |
| Humidity | 1.5m (cow height) | Represents animal experience |
| NH3 | 0.3m and 1.5m | Gas accumulation + breathing zone |
| CO2 | 1.5m | Breathing zone |
| Light | 2.5m | Uniform coverage, avoid shadows |
| Airflow | Multiple: 0.5m, 1.5m, 3m | Vertical gradient mapping |

### 4.3 Spacing Guidelines

**Grid Pattern**:
- Standard barn: 6m × 6m spacing between sensor nodes
- High-density zones: 4m × 4m spacing
- Transition zones (doors, curtains): 3m spacing

**Special Locations**:
- **Inlets**: 1 sensor per 50m² inlet area
- **Outlets**: 1 sensor per exhaust fan
- **Dead Zones**: Corners, under platforms (additional sensors)
- **Water Sources**: Within 2m of drinking points

### 4.4 Cross-Section View

```
                    BARN CROSS-SECTION
    
    5m ├──────────────────────────────────────┤
       │░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░│ ← Roof: Light sensor (2.5m)
    4m │░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░│
       │░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░│
    3m │    ↑      ↑      ↑      ↑          │ ← Airflow sensors (3m)
       │   AF1    AF2    AF3    AF4         │
    2m │░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░│
       │                                      │
  1.5m ├────[T/H/CO2/Light]────[T/H/CO2]────┤ ← Main sensors (cow height)
       │         ↓                ↓          │
    1m │      COW LEVEL         COW LEVEL    │
       │                                      │
  0.5m │    ↑              ↑                │ ← Lower airflow (0.5m)
       │   AF-low         AF-low             │
    0m ├──────────────────────────────────────┤
       │    [NH3-low]        [NH3-low]       │ ← NH3 near floor (0.3m)
       │  ≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋  │ ← Manure channel
       └──────────────────────────────────────┘
       
    T/H = Temperature/Humidity
    AF = Airflow sensor
    NH3 = Ammonia sensor
```

---

## 5. Communication Protocols

### 5.1 Protocol Selection Matrix

| Application | Primary Protocol | Backup | Range | Data Rate |
|-------------|------------------|--------|-------|-----------|
| Barn Sensors → Gateway | LoRaWAN | WiFi | 2-5 km | 0.3-50 kbps |
| Gateway → Cloud | MQTT over 4G/Ethernet | WiFi | Unlimited | Broadband |
| Control Commands | MQTT | Modbus TCP | Unlimited | Real-time |
| Local HMI | Modbus TCP | HTTP REST | LAN | Real-time |

### 5.2 LoRaWAN Configuration

#### 5.2.1 Network Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        LORAWAN NETWORK                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│   ┌─────────┐    ┌─────────┐    ┌─────────┐    ┌─────────┐    │
│   │ Node S1 │    │ Node S2 │    │ Node S3 │    │ Node S4 │    │
│   │ (LoRa)  │    │ (LoRa)  │    │ (LoRa)  │    │ (LoRa)  │    │
│   └────┬────┘    └────┬────┘    └────┬────┘    └────┬────┘    │
│        │              │              │              │          │
│        └──────────────┴──────┬───────┴──────────────┘          │
│                              ↓                                  │
│                       ┌─────────────┐                          │
│                       │   Gateway   │                          │
│                       │ (Indoor)    │                          │
│                       │ ChirpStack  │                          │
│                       └──────┬──────┘                          │
│                              │                                  │
│                    ┌─────────┴─────────┐                       │
│                    ↓                   ↓                       │
│            ┌──────────────┐    ┌──────────────┐               │
│            │   4G/LTE     │    │   Ethernet   │               │
│            │   Backup     │    │   Primary    │               │
│            └──────┬───────┘    └──────┬───────┘               │
│                   └─────────┬──────────┘                       │
│                             ↓                                  │
│                    ┌─────────────────┐                         │
│                    │   MQTT Broker   │                         │
│                    │  (Cloud/Local)  │                         │
│                    └─────────────────┘                         │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

#### 5.2.2 LoRaWAN Parameters

```yaml
Network Configuration:
  Region: AS923-2 (Bangladesh)
  Frequency Plan:
    - Uplink: 923.2 MHz - 924.6 MHz
    - Downlink: 923.2 MHz - 924.6 MHz
  Spreading Factor: SF7-SF12 (Adaptive Data Rate)
  Bandwidth: 125 kHz
  Coding Rate: 4/5
  
Device Configuration:
  Class: A (Battery optimized)
  TX Power: 14 dBm (max 16 dBm)
  Duty Cycle: 1% per channel
  
Transmission Schedule:
  Normal Mode: Every 5 minutes
  Alert Mode: Immediate + backoff
  Heartbeat: Every 60 minutes (even if no data change)
```

### 5.3 WiFi Configuration

#### 5.3.1 Network Segmentation

| SSID | Purpose | VLAN | Security |
|------|---------|------|----------|
| Dairy_IoT_Primary | Sensor data | VLAN 10 | WPA3-Enterprise |
| Dairy_IoT_Guest | Visitor access | VLAN 20 | WPA2-Personal |
| Dairy_Control | Critical controls | VLAN 30 | WPA3-Enterprise + Certificate |

#### 5.3.2 WiFi Coverage Plan

- **Access Points**: Every 30m in barns
- **Power**: 18 dBm (regulated limit)
- **Channels**: 1, 6, 11 (2.4GHz) for minimal interference
- **Backup**: Automatic failover to 4G

### 5.4 MQTT Protocol

#### 5.4.1 MQTT Broker Configuration

```yaml
Broker: Mosquitto 2.x or EMQX
Port: 8883 (TLS encrypted)
Authentication: Username/Password + Client Certificate
QoS Levels:
  - Sensor Data: QoS 1 (At least once)
  - Control Commands: QoS 2 (Exactly once)
  - Heartbeats: QoS 0 (At most once)
Keep Alive: 60 seconds
Clean Session: False (persistent sessions)
```

#### 5.4.2 Topic Structure

```
smart-dairy/
├── farm/
│   └── {farm_id}/
│       ├── sensors/
│       │   ├── barn-{barn_id}/
│       │   │   ├── zone-{zone_id}/
│       │   │   │   ├── temperature
│       │   │   │   ├── humidity
│       │   │   │   ├── nh3
│       │   │   │   ├── co2
│       │   │   │   ├── light
│       │   │   │   └── airflow
│       │   │   └── status
│       │   └── water/
│       │       └── temperature
│       ├── controls/
│       │   ├── fans/
│       │   ├── misters/
│       │   └── curtains/
│       ├── alerts/
│       │   ├── critical
│       │   ├── warning
│       │   └── info
│       └── system/
│           ├── heartbeat
│           └── diagnostics
```

---

## 6. Data Collection

### 6.1 Sampling Rates

| Parameter | Normal Mode | Alert Mode | Burst Mode |
|-----------|-------------|------------|------------|
| Temperature | 1 min | 10 sec | 1 sec |
| Humidity | 1 min | 10 sec | 1 sec |
| NH3 | 5 min | 1 min | 10 sec |
| CO2 | 1 min | 10 sec | 1 sec |
| Light | 5 min | 1 min | N/A |
| Airflow | 30 sec | 5 sec | 1 sec |
| System Status | 5 min | 1 min | 10 sec |

### 6.2 Data Buffering Strategy

#### 6.2.1 Local Storage (Edge)

```python
# Circular buffer configuration
BUFFER_CONFIG = {
    "capacity": 1000,  # Maximum readings per sensor
    "retention_hours": 72,  # Local retention
    "compression": "gzip",  # Compress before storage
    "storage_location": "/data/sensor_buffer.db"
}
```

#### 6.2.2 Buffering Logic

```
Normal Operation:
  Collect → Buffer → Transmit every 5 min → Clear buffer

Connectivity Loss:
  Collect → Buffer → Retry with exponential backoff
  Buffer full → Overwrite oldest (circular)

Connectivity Restored:
  Transmit buffered data (timestamped) → Clear buffer
  Resume normal operation
```

### 6.3 Transmission Protocol

#### 6.3.1 Transmission Schedule

| Condition | Action | Priority |
|-----------|--------|----------|
| Time-based (5 min) | Send all accumulated data | Normal |
| Threshold exceeded | Immediate transmission | High |
| Sensor fault | Immediate transmission | Critical |
| Connectivity restored | Send buffered history | Low |
| Heartbeat timeout | Send status only | Normal |

#### 6.3.2 Data Compression

For LoRaWAN (payload < 51 bytes):
- Use Cayenne LPP format
- Delta encoding for time-series
- Only transmit changed values
- Batch multiple readings when possible

---

## 7. Calibration

### 7.1 Factory vs Field Calibration

| Sensor Type | Factory Cal | Field Zero | Field Span | Frequency |
|-------------|-------------|------------|------------|-----------|
| Temperature | Yes | N/A | N/A | Annual verification |
| Humidity | Yes | Required | Required | Monthly |
| NH3 | Yes | Required | Required | Monthly/Quarterly |
| CO2 | Yes | ABC enabled | Recommended | Quarterly |
| Light | Yes | N/A | N/A | Annual |
| Airflow | Yes | Required | Recommended | Bi-annually |

### 7.2 Field Calibration Procedures

#### 7.2.1 Temperature Calibration

**Single-Point Verification (Ice Bath Method)**:
1. Prepare ice bath (distilled water + crushed ice)
2. Immerse sensor in waterproof container
3. Wait 10 minutes for stabilization
4. Expected reading: 0.0°C ± 0.1°C
5. Record offset if outside tolerance

**Two-Point Calibration**:
1. Ice bath (0°C)
2. Boiling water (100°C at sea level)
3. Adjust span and offset in firmware

#### 7.2.2 Humidity Calibration

**Salt Solution Method**:
| Salt | Expected RH | Tolerance |
|------|-------------|-----------|
| Lithium Chloride | 11.3% | ±0.5% |
| Magnesium Chloride | 33.1% | ±0.5% |
| Sodium Chloride | 75.3% | ±0.5% |
| Potassium Sulfate | 97.3% | ±0.5% |

**Procedure**:
1. Place sensor in sealed container with saturated salt solution
2. Wait 4 hours (or overnight)
3. Compare reading with expected value
4. Apply calibration coefficients

#### 7.2.3 Gas Sensor Calibration (NH3, CO2)

**Zero Calibration**:
1. Place sensor in fresh outdoor air
2. Wait 30 minutes
3. Set zero point in software
4. Record ambient baseline

**Span Calibration**:
1. Use certified gas mixture
2. NH3: 50 ppm standard
3. CO2: 1,000 ppm standard
4. Flow gas over sensor at 0.5 L/min
5. Wait for stabilization (5-10 min)
6. Adjust span coefficient

### 7.3 Drift Correction

#### 7.3.1 Automatic Drift Compensation

```python
# Pseudo-code for drift correction
class DriftCompensator:
    def __init__(self, sensor_type, baseline_period_days=7):
        self.baseline = None
        self.drift_rate = 0
        self.last_calibration = datetime.now()
        self.baseline_period = baseline_period_days
    
    def update_baseline(self, readings):
        # Calculate baseline during known good conditions
        if self.is_optimal_conditions():
            self.baseline = median(readings[-100:])
    
    def compensate(self, raw_value):
        if self.baseline is None:
            return raw_value
        
        # Apply drift correction
        time_since_calib = (datetime.now() - self.last_calibration).days
        drift = self.drift_rate * time_since_calib
        return raw_value - drift
```

#### 7.3.2 Drift Monitoring

| Sensor | Expected Drift | Action Threshold |
|--------|---------------|------------------|
| Temperature | <0.1°C/year | Recalibrate if >0.5°C |
| Humidity | <1%/year | Recalibrate if >3% |
| NH3 (electrochemical) | 2-5%/month | Monthly calibration required |
| CO2 (NDIR) | <0.5%/year | ABC handles most drift |

---

## 8. Alert Thresholds

### 8.1 Critical Thresholds Table

| Parameter | Normal | Warning | Critical | Emergency | Unit |
|-----------|--------|---------|----------|-----------|------|
| Air Temp | 10-24 | 24-28 | 28-32 | >32 | °C |
| Humidity | 40-70 | 70-80 | 80-90 | >90 | %RH |
| THI | <68 | 68-72 | 72-78 | >78 | Index |
| NH3 | <10 | 10-25 | 25-50 | >50 | ppm |
| CO2 | <800 | 800-1500 | 1500-3000 | >3000 | ppm |
| Light (day) | 150-300 | 100-150 | <100 | - | lux |
| Light (night) | <5 | 5-20 | >20 | - | lux |
| Airflow | 0.5-2.0 | 0.2-0.5 | <0.2 | - | m/s |
| Water Temp | 10-20 | 20-25 | >25 | <5 | °C |

### 8.2 THI-Based Alert System

```python
# Temperature-Humidity Index Classification
def calculate_thi(temperature_c, humidity_percent):
    """
    Calculate THI for dairy cattle
    Source: National Research Council
    """
    thi = (1.8 * temperature_c + 32) - \
          ((0.55 - 0.0055 * humidity_percent) * (1.8 * temperature_c - 26.8))
    return thi

def get_thi_alert_level(thi):
    if thi < 68:
        return "NORMAL", "green"
    elif thi < 72:
        return "MILD_STRESS", "yellow"
    elif thi < 78:
        return "MODERATE_STRESS", "orange"
    elif thi < 82:
        return "SEVERE_STRESS", "red"
    else:
        return "EMERGENCY", "critical"

# Alert Actions by Level
ALERT_ACTIONS = {
    "NORMAL": {
        "fans": "auto",
        "misters": "off",
        "notification": None
    },
    "MILD_STRESS": {
        "fans": "increase_25",
        "misters": "standby",
        "notification": "dashboard_only"
    },
    "MODERATE_STRESS": {
        "fans": "increase_50",
        "misters": "on_cycle",
        "notification": "sms_to_manager"
    },
    "SEVERE_STRESS": {
        "fans": "maximum",
        "misters": "on_continuous",
        "curtains": "open",
        "notification": "sms_and_call"
    },
    "EMERGENCY": {
        "fans": "maximum",
        "misters": "on_continuous",
        "curtains": "open",
        "notification": "all_staff_alert",
        "veterinarian": "notify"
    }
}
```

### 8.3 Alert Escalation Matrix

| Level | Detection Delay | Notification | Auto-Action | Escalation |
|-------|-----------------|--------------|-------------|------------|
| Warning | 5 minutes | Dashboard | Log only | - |
| Critical | Immediate | SMS + Email | Auto-controls | If persists 15 min → Emergency |
| Emergency | Immediate | SMS + Call + Siren | Full automation | If persists 30 min → Vet call |

### 8.4 Bangladesh-Specific Considerations

**Monsoon Season (June-October)**:
- Humidity rarely below 80%
- Focus on ventilation over cooling
- NH3 monitoring critical (reduced air exchange)

**Summer Peak (April-May)**:
- THI >78 common during day
- Pre-cooling protocols essential
- Night cooling critical (THI may remain >72)

**Winter (December-January)**:
- Night temperatures can drop to 10°C
- Draft prevention important
- Minimum ventilation must be maintained

---

## 9. Integration with Control Systems

### 9.1 Control System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    CONTROL SYSTEM ARCHITECTURE                   │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌───────────────────────────────────────────────────────────┐ │
│  │              ENVIRONMENTAL SENSORS                         │ │
│  │  [Temp] [Humidity] [NH3] [CO2] [Light] [Airflow]          │ │
│  └───────────────────────┬───────────────────────────────────┘ │
│                          ↓                                      │
│  ┌───────────────────────────────────────────────────────────┐ │
│  │              SENSOR GATEWAY (Edge Controller)              │ │
│  │         Raspberry Pi 4 / Industrial PLC                    │ │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐        │ │
│  │  │  Data Proc  │  │  Logic Eng  │  │  Scheduler  │        │ │
│  │  └─────────────┘  └─────────────┘  └─────────────┘        │ │
│  └───────────────────────┬───────────────────────────────────┘ │
│                          ↓                                      │
│          ┌───────────────┼───────────────┐                     │
│          ↓               ↓               ↓                     │
│  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐           │
│  │    FANS      │ │   MISTERS    │ │   CURTAINS   │           │
│  │  (Variable   │ │  (Solenoid   │ │  (Motor      │           │
│  │   Speed)     │ │   Valves)    │ │   Drive)     │           │
│  │              │ │              │ │              │           │
│  │ 0-100% VFD   │ │ Zone Control │ │ Open/Close   │           │
│  │ Control      │ │ Pulse/Cont.  │ │ % Position   │           │
│  └──────────────┘ └──────────────┘ └──────────────┘           │
│                                                                  │
│  ┌───────────────────────────────────────────────────────────┐ │
│  │              SAFETY INTERLOCKS                             │ │
│  │  • Emergency stop overrides all                          │ │
│  │  • Fire alarm → All fans ON, misters OFF                 │ │
│  │  • Power failure → Battery backup mode                   │ │
│  │  • Communication loss → Fail-safe mode                   │ │
│  └───────────────────────────────────────────────────────────┘ │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 9.2 Fan Control Logic

#### 9.2.1 Variable Speed Control

```python
class FanController:
    def __init__(self, num_zones=4):
        self.zones = {i: FanZone(i) for i in range(num_zones)}
        self.min_speed = 20  # % (for minimum ventilation)
        self.max_speed = 100  # %
    
    def calculate_fan_speed(self, zone_id, thi, nh3_ppm, outside_temp):
        zone = self.zones[zone_id]
        
        # Base speed from THI
        if thi < 68:
            base_speed = self.min_speed
        elif thi < 72:
            base_speed = 40
        elif thi < 76:
            base_speed = 60
        elif thi < 80:
            base_speed = 80
        else:
            base_speed = self.max_speed
        
        # NH3 boost
        if nh3_ppm > 25:
            base_speed = max(base_speed, 70)
        if nh3_ppm > 50:
            base_speed = self.max_speed
        
        # Outside temperature consideration
        if outside_temp < 10 and base_speed > 50:
            # Reduce speed to prevent overcooling
            base_speed = max(base_speed * 0.7, self.min_speed)
        
        return int(base_speed)
    
    def apply_control(self, zone_id, speed_percent):
        # Send command to VFD
        modbus.write_register(zone_id, speed_percent)
        self.zones[zone_id].current_speed = speed_percent
```

#### 9.2.2 Staging Control (for multiple fans)

| Stage | THI Range | Fans ON | Speed |
|-------|-----------|---------|-------|
| 1 | <68 | 1 (every 4th) | 30% |
| 2 | 68-72 | 2 (every 2nd) | 40% |
| 3 | 72-76 | 3 | 60% |
| 4 | 76-80 | All | 80% |
| 5 | >80 | All | 100% |

### 9.3 Mister/Soaker Control

#### 9.3.1 Pulse Timing (Bangladesh conditions)

```python
MISTER_SCHEDULE = {
    # Format: (on_seconds, off_seconds)
    "MILD": (30, 180),      # THI 68-72
    "MODERATE": (60, 120),  # THI 72-76
    "SEVERE": (120, 60),    # THI 76-80
    "EMERGENCY": (180, 30), # THI >80
}

class MisterController:
    def __init__(self):
        self.active_cycle = None
        self.last_switch = time.time()
    
    def update(self, thi, humidity):
        # Don't run if humidity >90% (no evaporative cooling)
        if humidity > 90:
            self.turn_off()
            return
        
        # Determine cycle
        if thi < 72:
            cycle = "MILD"
        elif thi < 76:
            cycle = "MODERATE"
        elif thi < 80:
            cycle = "SEVERE"
        else:
            cycle = "EMERGENCY"
        
        on_time, off_time = MISTER_SCHEDULE[cycle]
        
        # Apply timing
        elapsed = time.time() - self.last_switch
        if self.is_on() and elapsed > on_time:
            self.turn_off()
            self.last_switch = time.time()
        elif not self.is_on() and elapsed > off_time:
            self.turn_on()
            self.last_switch = time.time()
```

### 9.4 Curtain Control

```python
class CurtainController:
    """
    Natural ventilation curtain control
    """
    def __init__(self):
        self.position = 0  # 0 = closed, 100 = fully open
    
    def calculate_position(self, inside_temp, outside_temp, wind_speed, thi):
        # Start with THI-based opening
        if thi < 65:
            target = 0  # Closed
        elif thi < 72:
            target = 30
        elif thi < 78:
            target = 60
        else:
            target = 100  # Fully open
        
        # Adjust for outside conditions
        if outside_temp > inside_temp:
            # Outside is hotter - limit opening
            target = min(target, 50)
        
        if outside_temp < 5:
            # Cold outside - prevent freezing
            target = min(target, 30)
        
        if wind_speed > 8:  # m/s
            # High wind - reduce opening to prevent drafts
            target = target * 0.7
        
        return int(target)
```

---

## 10. Dashboard Visualization

### 10.1 Dashboard Layout

```
┌─────────────────────────────────────────────────────────────────────────────┐
│  SMART DAIRY - ENVIRONMENTAL MONITORING           [Alerts: 2]  [Settings]  │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌─────────────────────────┐  ┌─────────────────────────────────────────┐  │
│  │   CURRENT CONDITIONS    │  │         BARN HEAT MAP                   │  │
│  │                         │  │                                         │  │
│  │  Temperature: 28.5°C    │  │    [24.2] [25.1] [26.8] [25.9]         │  │
│  │  Humidity:    78%       │  │    [25.8] [27.2] [28.5] [27.1] ← Hot   │  │
│  │  THI:         76        │  │    [26.1] [28.0] [29.2] [28.4]         │  │
│  │  NH3:         18 ppm    │  │    [25.5] [26.9] [27.8] [26.7]         │  │
│  │  CO2:         950 ppm   │  │                                         │  │
│  │  Light:       185 lux   │  │    Legend:  🟢<68  🟡68-72  🟠72-78  🔴>78 │  │
│  │  Airflow:     1.2 m/s   │  │                                         │  │
│  │                         │  │                                         │  │
│  │  Status: ⚠️ MODERATE    │  │                                         │  │
│  │       STRESS            │  │                                         │  │
│  └─────────────────────────┘  └─────────────────────────────────────────┘  │
│                                                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    REAL-TIME TRENDS (Last 24 Hours)                  │   │
│  │                                                                      │   │
│  │   Temp ┤           ╱╲       ╱╲    ╱╲        ╱╲                      │   │
│  │   (°C) │    ╱╲    ╱  ╲     ╱  ╲  ╱  ╲  ╱╲ ╱  ╲  ╱╲                   │   │
│  │   32 ──┤   ╱  ╲  ╱    ╲   ╱    ╲╱    ╲╱  ╲╱   ╲╱  ╲────             │   │
│  │   28 ──┤  ╱    ╲╱      ╲ ╱                        ╱                  │   │
│  │   24 ──┤─╱                                              ═════        │   │
│  │   20 ──┤══════════════════════════════════════════════════════      │   │
│  │        └────┬────┬────┬────┬────┬────┬────┬────┬────┬────┬────     │   │
│  │            00  02  04  06  08  10  12  14  16  18  20  22          │   │
│  │                                                                      │   │
│  │   ━━━ Temperature  ─── Humidity  ═══ THI  --- NH3                   │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                             │
│  ┌─────────────────────────┐  ┌─────────────────────────────────────────┐  │
│  │   ACTIVE CONTROLS       │  │         ALERT HISTORY                    │  │
│  │                         │  │                                         │  │
│  │  Fans:      ████████░░  │  │  [14:32] ⚠️  THI >72 in Zone 3          │  │
│  │             80% Speed   │  │  [13:15] ℹ️  Daily report generated      │  │
│  │                         │  │  [12:08] ⚠️  NH3 spike detected          │  │
│  │  Misters:   ●○○ Pulse   │  │  [09:45] ⚠️  Fan #2 maintenance due     │  │
│  │  Curtains:  60% Open    │  │  [06:00] ℹ️  System health check OK     │  │
│  │                         │  │                                         │  │
│  │  [Override Controls]    │  │  [View All Alerts]                      │  │
│  └─────────────────────────┘  └─────────────────────────────────────────┘  │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 10.2 Grafana Dashboard Configuration

See Appendix C for complete Grafana dashboard JSON configuration.

---

## 11. Maintenance

### 11.1 Maintenance Schedule

| Task | Frequency | Duration | Responsible |
|------|-----------|----------|-------------|
| Visual inspection | Weekly | 30 min | Farm staff |
| Sensor cleaning | Monthly | 2 hours | Technician |
| Calibration check | Monthly | 4 hours | Technician |
| Battery voltage check | Monthly | 30 min | Technician |
| Full calibration | Quarterly | 8 hours | Specialist |
| Firmware update | Bi-annually | 4 hours | IT/IoT Engineer |
| Sensor replacement | As needed | 1 hour | Technician |
| Complete system audit | Annually | 2 days | External auditor |

### 11.2 Sensor Cleaning Procedures

#### 11.2.1 Temperature/Humidity Sensors

1. **Power off** sensor node
2. **Remove** sensor from housing
3. **Blow** compressed air to remove dust
4. **Wipe** with lint-free cloth dampened with isopropyl alcohol (70%)
5. **Dry** completely before reassembly
6. **Verify** reading against reference

#### 11.2.2 Gas Sensors (NH3, CO2)

1. **Power off** and allow to cool
2. **Remove** protective membrane if present
3. **Gently brush** dust with soft brush
4. **Do NOT use** liquids on sensor element
5. **Check** for insect nests (common issue)
6. **Verify** response with test gas

#### 11.2.3 Airflow Sensors

1. **Inspect** for cobwebs and dust
2. **Clean** vane or hot-wire element carefully
3. **Check** bearings on vane anemometers
4. **Lubricate** if necessary (silicone oil)
5. **Verify** against calibrated reference

### 11.3 Battery Replacement

| Component | Battery Type | Life | Replacement Schedule |
|-----------|--------------|------|---------------------|
| Sensor node (backup) | 18650 Li-Ion | 2-3 years | Annual check |
| Sensor node (primary) | 12V AGM | 3-5 years | Every 3 years |
| RTC backup | CR2032 | 5-10 years | Every 5 years |

**Replacement Procedure**:
1. Schedule replacement during low-stress period
2. Have backup sensor ready
3. Power down node safely
4. Replace battery (note polarity)
5. Dispose of old battery per regulations
6. Power on and verify all functions
7. Record replacement in log

### 11.4 Recalibration Schedule

| Sensor | Method | Frequency | Records |
|--------|--------|-----------|---------|
| Temperature | Ice bath verification | Monthly | Calibration log |
| Humidity | Salt solution | Monthly | Calibration log |
| NH3 | Zero + span gas | Monthly | Certificate |
| CO2 | ABC + span check | Quarterly | Certificate |
| Light | Lux meter comparison | Quarterly | Comparison sheet |
| Airflow | Anemometer comparison | Bi-annual | Certificate |

---

## 12. Troubleshooting

### 12.1 Sensor Failure Diagnostics

#### 12.1.1 Temperature Sensor Issues

| Symptom | Possible Cause | Solution |
|---------|---------------|----------|
| Reading stuck at fixed value | Sensor failure | Replace sensor |
| Reading too high | Direct sunlight | Relocate or shield |
| Reading too low | Water infiltration | Dry and seal housing |
| Erratic readings | Loose connection | Check wiring |
| Slow response | Dust coating | Clean sensor |

#### 12.1.2 Humidity Sensor Issues

| Symptom | Possible Cause | Solution |
|---------|---------------|----------|
| Always 100% | Condensation inside | Dry housing, check seals |
| Always 0% | Sensor disconnected | Check wiring |
| Reading too low | Sensor drift | Recalibrate |
| Slow response | Contamination | Clean or replace |

#### 12.1.3 Gas Sensor Issues (NH3, CO2)

| Symptom | Possible Cause | Solution |
|---------|---------------|----------|
| No response to gas | Sensor end of life | Replace sensor |
| Negative readings | Zero drift | Recalibrate zero |
| Reading too high | Span drift | Recalibrate span |
| Slow response | Aging sensor | Replace sensor |
| Unstable readings | Electrical noise | Shield cables |

### 12.2 Connectivity Issues

#### 12.2.1 LoRaWAN Connectivity

| Symptom | Diagnostic Steps | Solution |
|---------|------------------|----------|
| No uplink received | Check gateway status | Restart gateway |
| Intermittent packets | Check signal strength (RSSI) | Reposition node or add repeater |
| High packet loss | Check for interference | Change frequency/channel |
| Downlink not working | Check gateway downlink capability | Verify gateway configuration |

**Signal Strength Guidelines**:
- RSSI > -100 dBm: Good
- RSSI -100 to -115 dBm: Fair
- RSSI < -115 dBm: Poor (reposition needed)

#### 12.2.2 WiFi Connectivity

| Symptom | Diagnostic Steps | Solution |
|---------|------------------|----------|
| Cannot connect | Check SSID and password | Reconfigure credentials |
| Frequent disconnects | Check signal strength | Add access point |
| Slow data rate | Check for interference | Change channel |
| No IP address | Check DHCP server | Restart AP or use static IP |

### 12.3 Data Quality Issues

| Issue | Detection | Resolution |
|-------|-----------|------------|
| Stale data | Timestamp not updating | Check sensor power/connection |
| Out of range values | Validation rules | Check calibration, replace if faulty |
| Missing data | Sequence gaps | Check connectivity, buffer overflow |
| Duplicate data | Sequence numbers | Check ACK mechanism |
| Incorrect timestamps | Clock drift | Enable NTP sync |

### 12.4 Emergency Procedures

#### 12.4.1 Complete Sensor Network Failure

1. **Immediate Actions**:
   - Switch to manual control mode
   - Set all fans to maximum
   - Open all curtains
   - Activate all misters (if THI >72)
   - Notify farm manager

2. **Diagnostics**:
   - Check gateway power
   - Check internet connection
   - Verify MQTT broker status
   - Check sensor node batteries

3. **Recovery**:
   - Restore power if needed
   - Restart services in sequence
   - Verify data flow
   - Return to automatic control

#### 12.4.2 False Alert Storm

1. **Identify cause**:
   - Sensor malfunction
   - Calibration drift
   - Threshold set too tight

2. **Mitigation**:
   - Temporarily widen thresholds
   - Disable problematic sensor
   - Switch to backup sensor
   - Schedule maintenance

---

## 13. Appendices

### Appendix A: Sensor Datasheets Summary

#### A.1 SHT30 Temperature/Humidity Sensor

```
Manufacturer: Sensirion
Model: SHT30-DIS
Interface: I2C
Voltage: 2.4V - 5.5V
Current: 150μA (measuring), 0.2μA (idle)
Accuracy Temp: ±0.2°C (typical)
Accuracy RH: ±2% RH (typical)
Response Time: 8 seconds
Package: DFN8
```

#### A.2 SCD30 CO2 Sensor

```
Manufacturer: Sensirion
Model: SCD30
Technology: NDIR
Interface: I2C/UART
Voltage: 3.3V - 5.5V
Current: 60mA (average)
Accuracy: ±(30 ppm + 3% MV)
Range: 400 - 10,000 ppm
Response Time: 20 seconds
Auto Calibration: ABC enabled
```

#### A.3 ZE03-NH3 Electrochemical Sensor

```
Manufacturer: Winsen
Model: ZE03-NH3
Technology: Electrochemical
Interface: UART/Analog
Voltage: 5V ± 0.15V
Current: < 150mA
Accuracy: ±5% FS
Range: 0 - 100 ppm
Response Time: < 60 seconds
Resolution: 0.1 ppm
```

### Appendix B: Wiring Diagrams

#### B.1 Sensor Node Wiring Schematic

```
                              SENSOR NODE WIRING
┌─────────────────────────────────────────────────────────────────────────────┐
│                                                                             │
│   POWER SUPPLY SECTION                              COMMUNICATION           │
│   ┌─────────────────────────┐                     ┌─────────────────┐      │
│   │  12V Battery/Adapter    │                     │  LoRa Module    │      │
│   │  ┌─────────────────┐    │                     │  ┌───────────┐  │      │
│   │  │  +12V        GND│────┼─────────────────────┼──│VCC      GND│──┤      │
│   │  └─────────────────┘    │                     │  │           │  │      │
│   └─────────────────────────┘                     │  │  SX1276   │  │      │
│            │                                      │  │           │  │      │
│            ↓                                      │  │ANT    TX/RX│──┤      │
│   ┌─────────────────────────┐                     │  └───────────┘  │      │
│   │  Buck Converter         │                     └─────────────────┘      │
│   │  (12V → 5V/3.3V)        │                              │               │
│   │  ┌─────────────────┐    │                              │               │
│   └──│ 5V OUT     3V3  │────┼──────────────────────────────┤               │
│      │     GND         │────┼──────────────────────────────┤               │
│      └─────────────────┘    │                              │               │
│                             │                              │               │
│   MICROCONTROLLER SECTION   │                     SENSORS                  │
│   ┌─────────────────────────┐                     ┌─────────────────┐      │
│   │  ESP32 / STM32          │                     │  SHT30 (I2C)    │      │
│   │  ┌─────────────────┐    │                     │  ┌───────────┐  │      │
│   │  │ 3V3        GND  │◄───┼─────────────────────┼──│VCC      GND│  │      │
│   │  │ 21 (SDA)        │◄───┼─────────────────────┼──│SDA         │  │      │
│   │  │ 22 (SCL)        │◄───┼─────────────────────┼──│SCL         │  │      │
│   │  │ 16 (TX)    17(RX)◄───┼─────────────────────┼──│            │  │      │
│   │  │ 34 (ADC)        │◄───┼──────┐              │  └───────────┘  │      │
│   │  │                 │    │      │              │                 │      │
│   │  │                 │    │      │              │  SCD30 (I2C)    │      │
│   │  │ 21 (SDA)        │◄───┼──────┼──────────────┼──│SDA            │      │
│   │  │ 22 (SCL)        │◄───┼──────┼──────────────┼──│SCL            │      │
│   │  └─────────────────┘    │      │              │  │VCC        GND │      │
│   └─────────────────────────┘      │              │  └───────────────┘      │
│                                     │              │                         │
│                                     │              │  ZE03-NH3 (UART)        │
│                                     │              │  ┌───────────────┐      │
│                                     │              │  │VCC          GND│      │
│                                     │              │  │TX ◄───────────┤      │
│                                     │              │  │RX ───────────►│      │
│                                     │              │  └───────────────┘      │
│                                     │              │                         │
│                                     │              │  BH1750 (I2C)           │
│                                     │              │  ┌───────────────┐      │
│                                     │              │  │VCC      GND   │      │
│                                     │              │  │SDA ◄──────────┤      │
│                                     │              │  │SCL ◄──────────┤      │
│                                     │              │  └───────────────┘      │
│                                     │              │                         │
│                                     │              │  DS18B20 (1-Wire)       │
│                                     │              │  ┌───────────────┐      │
│                                     │              │  │VCC      GND   │      │
│                                     │              │  │DATA ◄─────────┤      │
│                                     │              │  │   [4.7kΩ]     │      │
│                                     │              │  │   to VCC      │      │
│                                     │              │  └───────────────┘      │
│                                     │              └─────────────────────────┘
│                                     │
│                                     │
│   ┌─────────────────────────────────┴──────────────────────────────┐         │
│   │  PROTECTION CIRCUITS                                            │         │
│   │  • TVS diodes on all I/O                                        │         │
│   │  • Ferrite beads on power lines                                 │         │
│   │  • ESD protection                                               │         │
│   │  • Fuse on 12V input                                            │         │
│   └─────────────────────────────────────────────────────────────────┘         │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Appendix C: Python Data Collection Script

```python
#!/usr/bin/env python3
"""
Smart Dairy Environmental Sensor Data Collector
Document: I-006
Author: IoT Engineer
Date: January 31, 2026
"""

import json
import time
import logging
import paho.mqtt.client as mqtt
from datetime import datetime
from typing import Dict, List, Optional
import board
import busio
import adafruit_sht31d
import adafruit_scd30
import adafruit_bh1750
from digitalio import DigitalInOut
import serial

# Configuration
CONFIG = {
    "farm_id": "SD001",
    "barn_id": "BARN_A",
    "zone_id": "ZONE_1",
    "node_id": "NODE_001",
    "mqtt_broker": "mqtt.smartdairy.local",
    "mqtt_port": 8883,
    "mqtt_username": "sensor_node",
    "mqtt_password": "secure_password_here",
    "publish_interval": 300,  # 5 minutes
    "alert_thresholds": {
        "temperature": {"warning": 28, "critical": 32},
        "humidity": {"warning": 80, "critical": 90},
        "nh3": {"warning": 25, "critical": 50},
        "co2": {"warning": 1500, "critical": 3000},
        "thi": {"warning": 72, "critical": 78}
    }
}

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('/var/log/sensor_node.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)


class SensorNode:
    """Environmental sensor node for Smart Dairy"""
    
    def __init__(self, config: Dict):
        self.config = config
        self.i2c = None
        self.sht30 = None
        self.scd30 = None
        self.bh1750 = None
        self.nh3_sensor = None
        self.mqtt_client = None
        self.running = False
        
        # Initialize sensors
        self._init_i2c_sensors()
        self._init_nh3_sensor()
        self._init_mqtt()
        
    def _init_i2c_sensors(self):
        """Initialize I2C sensors"""
        try:
            self.i2c = busio.I2C(board.SCL, board.SDA)
            
            # SHT30 - Temperature and Humidity
            self.sht30 = adafruit_sht31d.SHT31D(self.i2c)
            self.sht30.heater = False  # Disable heater for normal operation
            logger.info("SHT30 initialized successfully")
            
            # SCD30 - CO2
            self.scd30 = adafruit_scd30.SCD30(self.i2c)
            self.scd30.measurement_interval = 60  # Measure every 60 seconds
            logger.info("SCD30 initialized successfully")
            
            # BH1750 - Light
            self.bh1750 = adafruit_bh1750.BH1750(self.i2c)
            logger.info("BH1750 initialized successfully")
            
        except Exception as e:
            logger.error(f"Failed to initialize I2C sensors: {e}")
            raise
    
    def _init_nh3_sensor(self):
        """Initialize ZE03-NH3 sensor via UART"""
        try:
            self.nh3_sensor = serial.Serial(
                port='/dev/ttyS0',
                baudrate=9600,
                timeout=1
            )
            logger.info("ZE03-NH3 initialized successfully")
        except Exception as e:
            logger.error(f"Failed to initialize NH3 sensor: {e}")
            self.nh3_sensor = None
    
    def _init_mqtt(self):
        """Initialize MQTT client"""
        try:
            self.mqtt_client = mqtt.Client(
                client_id=f"{self.config['node_id']}_{int(time.time())}"
            )
            self.mqtt_client.username_pw_set(
                self.config['mqtt_username'],
                self.config['mqtt_password']
            )
            self.mqtt_client.tls_set()  # Enable TLS
            self.mqtt_client.on_connect = self._on_mqtt_connect
            self.mqtt_client.on_disconnect = self._on_mqtt_disconnect
            self.mqtt_client.on_publish = self._on_mqtt_publish
            
            self.mqtt_client.connect(
                self.config['mqtt_broker'],
                self.config['mqtt_port'],
                keepalive=60
            )
            self.mqtt_client.loop_start()
            logger.info("MQTT client initialized")
            
        except Exception as e:
            logger.error(f"Failed to initialize MQTT: {e}")
            raise
    
    def _on_mqtt_connect(self, client, userdata, flags, rc):
        """MQTT connect callback"""
        if rc == 0:
            logger.info("Connected to MQTT broker")
        else:
            logger.error(f"MQTT connection failed with code {rc}")
    
    def _on_mqtt_disconnect(self, client, userdata, rc):
        """MQTT disconnect callback"""
        logger.warning(f"Disconnected from MQTT broker (rc={rc})")
    
    def _on_mqtt_publish(self, client, userdata, mid):
        """MQTT publish callback"""
        logger.debug(f"Message {mid} published")
    
    def read_sht30(self) -> Dict[str, float]:
        """Read temperature and humidity from SHT30"""
        try:
            temp = self.sht30.temperature
            humidity = self.sht30.relative_humidity
            return {
                "temperature": round(temp, 2),
                "humidity": round(humidity, 2)
            }
        except Exception as e:
            logger.error(f"SHT30 read error: {e}")
            return {"temperature": None, "humidity": None}
    
    def read_scd30(self) -> Dict[str, float]:
        """Read CO2 from SCD30"""
        try:
            if self.scd30.data_available:
                co2 = self.scd30.CO2
                temp = self.scd30.temperature
                humidity = self.scd30.relative_humidity
                return {
                    "co2": round(co2, 1),
                    "co2_temperature": round(temp, 2),
                    "co2_humidity": round(humidity, 2)
                }
            return {"co2": None, "co2_temperature": None, "co2_humidity": None}
        except Exception as e:
            logger.error(f"SCD30 read error: {e}")
            return {"co2": None, "co2_temperature": None, "co2_humidity": None}
    
    def read_bh1750(self) -> Dict[str, float]:
        """Read light intensity from BH1750"""
        try:
            lux = self.bh1750.lux
            return {"light": round(lux, 1)}
        except Exception as e:
            logger.error(f"BH1750 read error: {e}")
            return {"light": None}
    
    def read_nh3(self) -> Dict[str, float]:
        """Read NH3 from ZE03 sensor"""
        if self.nh3_sensor is None:
            return {"nh3": None}
        
        try:
            # ZE03 command to read gas concentration
            command = bytes([0xFF, 0x01, 0x86, 0x00, 0x00, 0x00, 0x00, 0x00, 0x79])
            self.nh3_sensor.write(command)
            response = self.nh3_sensor.read(9)
            
            if response and len(response) == 9:
                # Parse response (ZE03 format)
                high_byte = response[2]
                low_byte = response[3]
                nh3_ppb = (high_byte << 8) | low_byte
                nh3_ppm = nh3_ppb / 1000.0
                return {"nh3": round(nh3_ppm, 2)}
            return {"nh3": None}
        except Exception as e:
            logger.error(f"NH3 read error: {e}")
            return {"nh3": None}
    
    def calculate_thi(self, temp_c: float, humidity: float) -> float:
        """Calculate Temperature-Humidity Index"""
        thi = (1.8 * temp_c + 32) - ((0.55 - 0.0055 * humidity) * (1.8 * temp_c - 26.8))
        return round(thi, 2)
    
    def check_alerts(self, readings: Dict) -> List[Dict]:
        """Check readings against alert thresholds"""
        alerts = []
        thresholds = self.config['alert_thresholds']
        
        # Check temperature
        if readings.get('temperature'):
            temp = readings['temperature']
            if temp >= thresholds['temperature']['critical']:
                alerts.append({
                    'parameter': 'temperature',
                    'level': 'critical',
                    'value': temp,
                    'threshold': thresholds['temperature']['critical']
                })
            elif temp >= thresholds['temperature']['warning']:
                alerts.append({
                    'parameter': 'temperature',
                    'level': 'warning',
                    'value': temp,
                    'threshold': thresholds['temperature']['warning']
                })
        
        # Check THI
        if readings.get('thi'):
            thi = readings['thi']
            if thi >= thresholds['thi']['critical']:
                alerts.append({
                    'parameter': 'thi',
                    'level': 'critical',
                    'value': thi,
                    'threshold': thresholds['thi']['critical']
                })
            elif thi >= thresholds['thi']['warning']:
                alerts.append({
                    'parameter': 'thi',
                    'level': 'warning',
                    'value': thi,
                    'threshold': thresholds['thi']['warning']
                })
        
        # Check NH3
        if readings.get('nh3'):
            nh3 = readings['nh3']
            if nh3 >= thresholds['nh3']['critical']:
                alerts.append({
                    'parameter': 'nh3',
                    'level': 'critical',
                    'value': nh3,
                    'threshold': thresholds['nh3']['critical']
                })
            elif nh3 >= thresholds['nh3']['warning']:
                alerts.append({
                    'parameter': 'nh3',
                    'level': 'warning',
                    'value': nh3,
                    'threshold': thresholds['nh3']['warning']
                })
        
        return alerts
    
    def collect_data(self) -> Dict:
        """Collect data from all sensors"""
        timestamp = datetime.utcnow().isoformat() + 'Z'
        
        # Read all sensors
        sht30_data = self.read_sht30()
        scd30_data = self.read_scd30()
        bh1750_data = self.read_bh1750()
        nh3_data = self.read_nh3()
        
        # Combine readings
        readings = {
            **sht30_data,
            **scd30_data,
            **bh1750_data,
            **nh3_data
        }
        
        # Calculate THI if we have temperature and humidity
        if readings.get('temperature') and readings.get('humidity'):
            readings['thi'] = self.calculate_thi(
                readings['temperature'],
                readings['humidity']
            )
        
        # Check for alerts
        alerts = self.check_alerts(readings)
        
        # Build payload
        payload = {
            "metadata": {
                "farm_id": self.config['farm_id'],
                "barn_id": self.config['barn_id'],
                "zone_id": self.config['zone_id'],
                "node_id": self.config['node_id'],
                "timestamp": timestamp,
                "version": "1.0"
            },
            "sensors": readings,
            "alerts": alerts,
            "status": {
                "sensor_node": "online",
                "mqtt_connected": self.mqtt_client.is_connected() if self.mqtt_client else False,
                "battery_voltage": self._read_battery(),
                "signal_strength": self._read_signal_strength()
            }
        }
        
        return payload
    
    def _read_battery(self) -> Optional[float]:
        """Read battery voltage (placeholder)"""
        # Implement based on hardware
        return 12.4  # Example value
    
    def _read_signal_strength(self) -> Optional[int]:
        """Read LoRa signal strength (placeholder)"""
        # Implement based on hardware
        return -85  # Example RSSI value
    
    def publish_data(self, payload: Dict):
        """Publish data to MQTT broker"""
        try:
            topic = f"smart-dairy/farm/{self.config['farm_id']}/sensors/{self.config['barn_id']}/{self.config['zone_id']}"
            
            message = json.dumps(payload)
            result = self.mqtt_client.publish(topic, message, qos=1)
            
            if result.rc == mqtt.MQTT_ERR_SUCCESS:
                logger.info(f"Published to {topic}")
            else:
                logger.error(f"Failed to publish (rc={result.rc})")
                
        except Exception as e:
            logger.error(f"Publish error: {e}")
    
    def run(self):
        """Main loop"""
        logger.info("Sensor node started")
        self.running = True
        
        while self.running:
            try:
                # Collect and publish data
                payload = self.collect_data()
                self.publish_data(payload)
                
                # Log summary
                sensors = payload['sensors']
                logger.info(f"Data: T={sensors.get('temperature')}°C, "
                          f"H={sensors.get('humidity')}%, "
                          f"THI={sensors.get('thi')}, "
                          f"CO2={sensors.get('co2')}ppm, "
                          f"NH3={sensors.get('nh3')}ppm")
                
                # Wait for next interval
                time.sleep(self.config['publish_interval'])
                
            except KeyboardInterrupt:
                logger.info("Shutdown requested")
                self.running = False
            except Exception as e:
                logger.error(f"Main loop error: {e}")
                time.sleep(10)  # Retry after short delay
        
        # Cleanup
        self.mqtt_client.loop_stop()
        logger.info("Sensor node stopped")


def main():
    """Main entry point"""
    node = SensorNode(CONFIG)
    node.run()


if __name__ == "__main__":
    main()
```

### Appendix D: MQTT Payload Format

```json
{
  "metadata": {
    "farm_id": "SD001",
    "barn_id": "BARN_A",
    "zone_id": "ZONE_1",
    "node_id": "NODE_001",
    "timestamp": "2026-01-31T08:30:00.000Z",
    "version": "1.0",
    "sequence": 12345
  },
  "sensors": {
    "temperature": 28.5,
    "humidity": 78.0,
    "thi": 76.3,
    "co2": 950.0,
    "co2_temperature": 28.2,
    "co2_humidity": 79.0,
    "nh3": 18.5,
    "light": 185.0
  },
  "alerts": [
    {
      "parameter": "thi",
      "level": "warning",
      "value": 76.3,
      "threshold": 72,
      "message": "THI exceeds warning threshold"
    }
  ],
  "status": {
    "sensor_node": "online",
    "mqtt_connected": true,
    "battery_voltage": 12.4,
    "signal_strength": -85,
    "uptime_seconds": 86400,
    "firmware_version": "1.2.3"
  }
}
```

### Appendix E: Grafana Dashboard Configuration

```json
{
  "dashboard": {
    "id": null,
    "title": "Smart Dairy - Environmental Monitoring",
    "tags": ["environmental", "sensors", "dairy"],
    "timezone": "Asia/Dhaka",
    "panels": [
      {
        "id": 1,
        "title": "Temperature Heatmap",
        "type": "heatmap",
        "datasource": "influxdb",
        "targets": [
          {
            "query": "SELECT mean(\"temperature\") FROM \"sensors\" WHERE $timeFilter GROUP BY time($__interval), \"zone_id\" fill(null)"
          }
        ],
        "gridPos": {"h": 8, "w": 12, "x": 0, "y": 0}
      },
      {
        "id": 2,
        "title": "THI Trend",
        "type": "graph",
        "datasource": "influxdb",
        "targets": [
          {
            "query": "SELECT mean(\"thi\") FROM \"sensors\" WHERE $timeFilter GROUP BY time($__interval) fill(null)"
          }
        ],
        "alert": {
          "conditions": [
            {
              "evaluator": {"type": "gt", "params": [78]},
              "operator": {"type": "and"},
              "query": {"params": ["A", "5m", "now"]},
              "reducer": {"type": "avg"},
              "type": "query"
            }
          ],
          "executionErrorState": "alerting",
          "frequency": "1m",
          "handler": 1,
          "name": "THI Critical Alert"
        },
        "gridPos": {"h": 8, "w": 12, "x": 12, "y": 0}
      },
      {
        "id": 3,
        "title": "Current Conditions",
        "type": "stat",
        "datasource": "influxdb",
        "targets": [
          {
            "query": "SELECT last(\"temperature\") as \"Temp\", last(\"humidity\") as \"Humidity\", last(\"thi\") as \"THI\", last(\"co2\") as \"CO2\", last(\"nh3\") as \"NH3\" FROM \"sensors\" WHERE $timeFilter"
          }
        ],
        "gridPos": {"h": 4, "w": 24, "x": 0, "y": 8}
      },
      {
        "id": 4,
        "title": "Gas Levels (NH3 & CO2)",
        "type": "graph",
        "datasource": "influxdb",
        "targets": [
          {
            "query": "SELECT mean(\"nh3\") FROM \"sensors\" WHERE $timeFilter GROUP BY time($__interval) fill(null)"
          },
          {
            "query": "SELECT mean(\"co2\") FROM \"sensors\" WHERE $timeFilter GROUP BY time($__interval) fill(null)"
          }
        ],
        "gridPos": {"h": 8, "w": 12, "x": 0, "y": 12}
      },
      {
        "id": 5,
        "title": "Sensor Status",
        "type": "table",
        "datasource": "influxdb",
        "targets": [
          {
            "query": "SELECT last(\"battery_voltage\") as \"Battery\", last(\"signal_strength\") as \"Signal\", last(\"uptime_seconds\") as \"Uptime\" FROM \"status\" WHERE $timeFilter GROUP BY \"node_id\""
          }
        ],
        "gridPos": {"h": 8, "w": 12, "x": 12, "y": 12}
      }
    ],
    "time": {"from": "now-24h", "to": "now"},
    "refresh": "30s"
  }
}
```

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author (IoT Engineer) | _________________ | _________________ | _______ |
| Reviewer (Farm Operations Manager) | _________________ | _________________ | _______ |
| Owner (IoT Architect) | _________________ | _________________ | _______ |

---

## Related Documents

- I-001: System Architecture Overview
- I-002: Network Infrastructure
- I-003: Database Schema
- I-004: API Specification
- I-005: Security Implementation

---

*Document generated for Smart Dairy Ltd. - Smart Web Portal System Implementation*
