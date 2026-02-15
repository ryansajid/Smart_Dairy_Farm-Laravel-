# I-005: RFID Reader Integration Guide

## Smart Dairy Ltd. - IoT & Smart Farming Documentation

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | I-005 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | IoT Engineer |
| **Owner** | IoT Architect |
| **Reviewer** | Solution Architect |
| **Status** | Approved |
| **Classification** | Internal Use |

---

## Document Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | IoT Engineer | Initial Release |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [RFID Standards](#2-rfid-standards)
3. [Hardware Selection](#3-hardware-selection)
4. [Tag Specifications](#4-tag-specifications)
5. [System Architecture](#5-system-architecture)
6. [Data Format](#6-data-format)
7. [Integration Methods](#7-integration-methods)
8. [Installation Guidelines](#8-installation-guidelines)
9. [Software Integration](#9-software-integration)
10. [Calibration & Testing](#10-calibration--testing)
11. [Troubleshooting](#11-troubleshooting)
12. [Maintenance](#12-maintenance)
13. [Appendices](#13-appendices)

---

## 1. Introduction

### 1.1 RFID Technology Overview

Radio Frequency Identification (RFID) is a wireless technology that uses electromagnetic fields to automatically identify and track tags attached to objects. In livestock management, RFID provides a reliable, permanent, and automated method for individual animal identification, enabling precision dairy farming operations.

### 1.2 Use Cases for Smart Dairy

| Use Case | Description | Business Value |
|----------|-------------|----------------|
| **Individual Animal Identification** | Unique ID for each cattle in the herd | Eliminates manual record-keeping errors |
| **Milking Parlor Automation** | Automatic cow identification during milking | Links milk yield to individual animals |
| **Health Record Management** | Track vaccinations, treatments, breeding | Complete medical history per animal |
| **Feed Management** | Individual feeding based on production | Optimized feed costs, better yields |
| **Reproduction Tracking** | Heat detection, breeding, pregnancy | Improved breeding efficiency |
| **Traceability** | Farm-to-fork tracking for compliance | Export readiness, quality certification |
| **Asset Management** | Track equipment, tools, vehicles | Reduced loss, better maintenance |

### 1.3 Smart Dairy RFID Scope

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      SMART DAIRY RFID ECOSYSTEM                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│   ┌──────────────┐    ┌──────────────┐    ┌──────────────┐                 │
│   │   EAR TAGS   │    │ INJECTABLE   │    │   VISUAL ID  │                 │
│   │  (Primary)   │◄──►│  (Backup)    │◄──►│  (Human Read)│                 │
│   └──────┬───────┘    └──────────────┘    └──────────────┘                 │
│          │                                                                   │
│          ▼                                                                   │
│   ┌────────────────────────────────────────────────────────────┐            │
│   │                    RFID READERS                             │            │
│   │  ┌────────────┐  ┌────────────┐  ┌────────────┐           │            │
│   │  │   FIXED    │  │  HANDHELD  │  │   MOBILE   │           │            │
│   │  │  (Parlor)  │  │  (Field)   │  │   (NFC)    │           │            │
│   │  └────────────┘  └────────────┘  └────────────┘           │            │
│   └────────────────────┬───────────────────────────────────────┘            │
│                        │                                                     │
│                        ▼                                                     │
│   ┌────────────────────────────────────────────────────────────┐            │
│   │              MQTT/IoT GATEWAY                               │            │
│   │         (Local Edge Processing)                             │            │
│   └────────────────────┬───────────────────────────────────────┘            │
│                        │                                                     │
│                        ▼                                                     │
│   ┌────────────────────────────────────────────────────────────┐            │
│   │                    ERP SYSTEM                               │            │
│   │     (Odoo/ERPNext Herd Management Module)                   │            │
│   └────────────────────────────────────────────────────────────┘            │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 1.4 Bangladesh Context

Smart Dairy operates in tropical climate conditions that require special considerations:

- **High Humidity**: 60-90% year-round, monsoon season challenges
- **Temperature Range**: 15°C - 40°C ambient conditions
- **Power Reliability**: Grid instability requires solar backup systems
- **Local Supply Chain**: Preference for locally available equipment and support

---

## 2. RFID Standards

### 2.1 ISO 11784/11785 Standards

These international standards govern electronic animal identification:

#### ISO 11784 - Code Structure

| Field | Bits | Description |
|-------|------|-------------|
| Header | 16 | Identifies data content (animal ID) |
| Country Code | 10 | ISO 3166 country code (050 for Bangladesh) |
| Reserved | 6 | Future use, set to zero |
| National ID | 38 | Unique animal identifier (max 274,877,906,943) |
| Reserved | 6 | Future use, set to zero |

**Example Bangladesh Animal ID**: `050 000123456789` (Country Code 050 = Bangladesh)

#### ISO 11785 - Technical Concept

| Parameter | Specification |
|-----------|---------------|
| **Operating Frequency** | 134.2 kHz (LF - Low Frequency) |
| **Communication Protocol** | Half-duplex (HDX) or Full-duplex (FDX-B) |
| **Read Range** | 10 cm - 100 cm (depending on technology) |
| **Data Rate** | FDX-B: ~400 bps, HDX: ~2000 bps |

### 2.2 FDX-B vs HDX Comparison

| Feature | FDX-B (Full Duplex) | HDX (Half Duplex) |
|---------|---------------------|-------------------|
| **Power Supply** | Continuous power from reader | Stored energy in capacitor |
| **Read Speed** | Faster, continuous reading | Slightly slower, pulsed |
| **Range** | Shorter (10-30 cm typical) | Longer (up to 100 cm) |
| **Interference** | More susceptible to metal | Better noise immunity |
| **Cost** | Lower tag cost | Higher tag cost |
| **Battery** | Passive (no battery) | Passive (no battery) |
| **Recommendation** | **Standard for Smart Dairy** | Use for long-range gates |

### 2.3 Tag Types

| Type | Technology | Use Case | Range |
|------|------------|----------|-------|
| **FDX-B Ear Tags** | Passive LF 134.2 kHz | Standard cattle identification | 15-30 cm |
| **HDX Ear Tags** | Passive LF 134.2 kHz | Long-range gate reading | 50-100 cm |
| **Injectable Transponders** | Glass-encapsulated FDX-B | Permanent ID backup | 5-10 cm |
| **UHF Tags** | 860-960 MHz | Asset tracking (non-animal) | 1-10 m |

---

## 3. Hardware Selection

### 3.1 Fixed RFID Readers

Recommended for milking parlor and automated gates.

| Model | Manufacturer | Protocol | Interface | Bangladesh Availability |
|-------|--------------|----------|-----------|------------------------|
| **ID110** | NingBO PanDa | FDX-B/HDX | RS232/USB | ✓ Local distributor |
| **LPR-100** | Lifang Electronics | FDX-B | RS485/Ethernet | ✓ Available |
| **RFID134** | Arduino Compatible | FDX-B | TTL/USB | ✓ Online import |
| **Allflex RS420** | Allflex | FDX-B/HDX | RS232/485 | ✓ Veterinary suppliers |

**Recommended Configuration for Smart Dairy:**

```
┌─────────────────────────────────────────────────────────────────┐
│              MILKING PARLOR RFID SETUP                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│   Entry Gate                              Exit Gate             │
│   ┌─────────┐                            ┌─────────┐           │
│   │READER 1 │──┐                    ┌───│READER 2 │           │
│   │(Entry)  │  │    ┌──────────┐    │   │(Exit)   │           │
│   └────┬────┘  │    │  COW     │    │   └────┬────┘           │
│        │       └───►│ POSITION │◄───┘        │                │
│        │            └──────────┘             │                │
│        │                                     │                │
│   ┌────▼─────────────────────────────────────▼────┐          │
│   │           LOCAL CONTROLLER (Raspberry Pi)      │          │
│   │              MQTT Client + Edge Processing     │          │
│   └──────────────────┬────────────────────────────┘          │
│                      │                                         │
│                      ▼                                         │
│              ┌─────────────┐                                  │
│              │  ERP SYSTEM │                                  │
│              │   (Odoo)    │                                  │
│              └─────────────┘                                  │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 3.2 Handheld Readers

For field use by veterinary and farm staff.

| Model | Manufacturer | Features | Price Range (BDT) |
|-------|--------------|----------|-------------------|
| **LPR-A10** | Lifang | FDX-B, Bluetooth, LCD | 8,000-12,000 |
| **PT160** | NingBO PanDa | FDX-B/HDX, USB, 1000 tag memory | 6,000-9,000 |
| **Allflex HHR3000** | Allflex | FDX-B/HDX, Bluetooth, Android app | 25,000-35,000 |
| **Rupertag R901** | Generic | FDX-B, USB, lightweight | 4,000-6,000 |

**Recommended for Smart Dairy**: LPR-A10 or Allflex HHR3000 with Bluetooth connectivity for mobile app integration.

### 3.3 Mobile App NFC Integration

Modern smartphones can read ISO 15693 (NFC) tags, but **not ISO 11784/11785** (animal RFID). Solutions:

| Option | Description | Cost |
|--------|-------------|------|
| **Bluetooth Handheld** | Connect to smartphone via Bluetooth | Medium |
| **NFC Conversion Tags** | Secondary NFC sticker on ear tag | Low |
| **QR Code Backup** | Visual QR code for manual scanning | Minimal |

### 3.4 Antennas

| Type | Application | Specifications |
|------|-------------|--------------|
| **Panel Antenna** | Gate/passage reading | 30x30 cm, 50-80 cm range |
| **Stick Antenna** | Milking parlor | 50 cm rod, 20-30 cm range |
| **Loop Antenna** | Confinement areas | 1m diameter, 100 cm range |

---

## 4. Tag Specifications

### 4.1 Ear Tags

#### Primary Recommendation: FDX-B Visual Ear Tags

| Specification | Details |
|---------------|---------|
| **Standard** | ISO 11784/11785 FDX-B |
| **Frequency** | 134.2 kHz |
| **Material** | TPU (Thermoplastic Polyurethane) |
| **Temperature Range** | -30°C to +60°C |
| **UV Resistance** | 5+ years tropical exposure |
| **Dimensions** | Male: 30mm x 30mm, Female: 70mm x 50mm |
| **Weight** | ~4 grams per set |
| **Read Range** | 15-30 cm with standard reader |

#### Recommended Suppliers (Bangladesh)

| Supplier | Location | Contact | Products |
|----------|----------|---------|----------|
| **Livestock Services BD** | Dhaka | +880 XXXX | FDX-B ear tags, applicators |
| **Agricultural Solutions Ltd** | Gazipur | +880 XXXX | Allflex, generic tags |
| **Vet World Bangladesh** | Chittagong | +880 XXXX | Veterinary equipment |

### 4.2 Injectable Tags (Backup Identification)

For permanent identification in case of ear tag loss.

| Specification | Details |
|---------------|---------|
| **Standard** | ISO 11784/11785 FDX-B |
| **Encapsulation** | Bioglass 8625 |
| **Dimensions** | 2.12 mm x 12 mm |
| **Injection Site** | Base of left ear or neck |
| **Lifespan** | Animal lifetime (25+ years) |
| **Scanner Required** | Close-proximity reader (5-10 cm) |

**Note**: Injectable tags require trained veterinary staff for implantation.

### 4.3 Visual ID Pairing

Each RFID tag must have a corresponding visual ID for manual verification.

| Visual ID Format | Example | Usage |
|------------------|---------|-------|
| **Farm Code + Sequential Number** | SD-0001 to SD-1000 | Permanent ID |
| **Birth Year + Sequence** | 25-001 (born 2025) | Age identification |
| **Herd Group + Number** | L1-050 (Lactation Group 1) | Management grouping |

### 4.4 Tag Application Guidelines

```
┌─────────────────────────────────────────────────────────────────┐
│                    EAR TAG APPLICATION                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│   Correct Position:                                             │
│                                                                  │
│        LEFT EAR (Standard)                                      │
│     ┌─────────────────────┐                                     │
│     │    ◉                │  ← Tag placement: 1/3 from head    │
│     │   /│\               │     on back side of ear             │
│     │  / │ \              │                                     │
│     │    │                │  Avoid:                             │
│     └─────────────────────┘  • Ear cartilage ridges             │
│                              • Blood vessels                     │
│                              • Previous tag holes               │
│                                                                  │
│   Application Steps:                                            │
│   1. Clean ear with antiseptic                                  │
│   2. Verify tag number matches database                         │
│   3. Position applicator perpendicular to ear                   │
│   4. Apply firm, quick pressure                                 │
│   5. Verify secure attachment                                   │
│   6. Scan to confirm RFID readability                           │
│   7. Record in system with timestamp                            │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 5. System Architecture

### 5.1 RFID Integration Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SMART DAIRY RFID ARCHITECTURE                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                         DEVICE LAYER                                 │    │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌────────────┐ │    │
│  │  │ RFID Reader │  │ RFID Reader │  │  Handheld   │  │  Mobile    │ │    │
│  │  │ (Parlor 1)  │  │ (Parlor 2)  │  │   Reader    │  │   App      │ │    │
│  │  │ 134.2 kHz   │  │ 134.2 kHz   │  │  Bluetooth  │  │  NFC/QR    │ │    │
│  │  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └─────┬──────┘ │    │
│  │         │                │                │               │        │    │
│  │         └────────────────┴────────────────┴───────────────┘        │    │
│  │                              │                                      │    │
│  └──────────────────────────────┼──────────────────────────────────────┘    │
│                                 │                                            │
│                                 ▼                                            │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                      EDGE/ GATEWAY LAYER                             │    │
│  │  ┌─────────────────────────────────────────────────────────────┐    │    │
│  │  │              Raspberry Pi / Industrial Gateway               │    │    │
│  │  │  • MQTT Publisher (Tag Events)                              │    │    │
│  │  │  • Local Database (SQLite - offline cache)                  │    │    │
│  │  │  • Data Validation & Filtering                              │    │    │
│  │  │  • Solar Power Management                                   │    │    │
│  │  └────────────────────────┬────────────────────────────────────┘    │    │
│  │                           │                                         │    │
│  └───────────────────────────┼─────────────────────────────────────────┘    │
│                              │                                              │
│         ┌────────────────────┼────────────────────┐                         │
│         │                    │                    │                         │
│         ▼                    ▼                    ▼                         │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐                  │
│  │   LOCAL      │◄──►│  MQTT BROKER │◄──►│   CLOUD      │                  │
│  │   ERP        │    │  (Mosquitto) │    │   BACKUP     │                  │
│  │  (Odoo CE)   │    │              │    │              │                  │
│  └──────────────┘    └──────────────┘    └──────────────┘                  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 5.2 ERP Integration Points

| ERP Module | RFID Integration | Data Flow |
|------------|------------------|-----------|
| **Herd Management** | Animal identification | RFID → Animal Profile |
| **Milk Production** | Yield per cow | RFID → Milk Volume Record |
| **Health Management** | Treatment records | RFID → Medical History |
| **Feed Management** | Individual feeding | RFID → Feed Allocation |
| **Breeding Management** | Heat detection, AI | RFID → Reproduction Records |
| **Inventory** | Equipment tracking | RFID Asset Tag → Equipment Record |

### 5.3 Mobile App Integration

| Feature | RFID Role | Mobile Action |
|---------|-----------|---------------|
| **Animal Lookup** | Scan ear tag | Display animal profile |
| **Health Check** | Verify identity | Record temperature, symptoms |
| **Milk Recording** | Identify cow | Input milk volume manually |
| **Movement Recording** | Track location | Update paddock/location |
| **Offline Mode** | Cache scan data | Sync when connected |

---

## 6. Data Format

### 6.1 Tag ID Structure

#### Standard Format (ISO 11784 Compliant)

```
┌─────────────────────────────────────────────────────────────────────┐
│                    RFID DATA STRUCTURE                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│   Raw Hexadecimal: 3E900000012345678                                 │
│                      │││└────┬─────┘└─ National ID (38 bits)        │
│                      ││└──────┘          Reserved (6 bits)           │
│                      │└──────────────── Country Code: 050 (BD)       │
│                      └───────────────── Header: Animal ID            │
│                                                                      │
│   Parsed:                                                            │
│   • Country: Bangladesh (050)                                        │
│   • National ID: 12345678                                            │
│   • Farm ID: SD-1234                                                 │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

#### Smart Dairy Tag Mapping

| RFID National ID | Farm Visual ID | Animal Name | Breed | Birth Date |
|------------------|----------------|-------------|-------|------------|
| 0000000001 | SD-0001 | Rani | Holstein-Friesian | 2023-03-15 |
| 0000000002 | SD-0002 | Laxmi | Jersey | 2023-04-20 |
| 0000000003 | SD-0003 | Ganga | Sahiwal | 2023-02-10 |

### 6.2 MQTT Message Format

#### Topic Structure

```
smartdairy/{location}/{reader_id}/{event_type}
```

| Component | Values | Description |
|-----------|--------|-------------|
| `{location}` | parlor1, parlor2, gate_main, field_a, etc. | Physical location |
| `{reader_id}` | reader_001, reader_002, etc. | Unique reader identifier |
| `{event_type}` | tag_detected, tag_lost, heartbeat, error | Event classification |

#### Example Topics

```
smartdairy/parlor1/reader_001/tag_detected
smartdairy/parlor1/reader_001/tag_lost
smartdairy/gate_main/reader_003/tag_detected
smartdairy/field/reader_005/heartbeat
```

#### Message Payload Schema

```json
{
  "timestamp": "2026-01-31T08:30:15.123+06:00",
  "reader_id": "reader_001",
  "location": "parlor1",
  "event_type": "tag_detected",
  "tag_data": {
    "raw_hex": "3E900000012345678",
    "country_code": "050",
    "national_id": "12345678",
    "animal_id": "SD-1234"
  },
  "signal_strength": -45,
  "read_count": 1,
  "session_id": "550e8400-e29b-41d4-a716-446655440000"
}
```

#### Tag Lost Event

```json
{
  "timestamp": "2026-01-31T08:35:22.456+06:00",
  "reader_id": "reader_001",
  "location": "parlor1",
  "event_type": "tag_lost",
  "tag_data": {
    "raw_hex": "3E900000012345678",
    "animal_id": "SD-1234"
  },
  "duration_seconds": 307,
  "session_id": "550e8400-e29b-41d4-a716-446655440000"
}
```

### 6.3 Database Schema (ERP Integration)

#### RFID Events Table

```sql
CREATE TABLE rfid_events (
    id SERIAL PRIMARY KEY,
    event_timestamp TIMESTAMP WITH TIME ZONE NOT NULL,
    reader_id VARCHAR(50) NOT NULL,
    location VARCHAR(100) NOT NULL,
    event_type VARCHAR(50) NOT NULL,
    tag_hex VARCHAR(20) NOT NULL,
    animal_id VARCHAR(20),
    signal_strength INTEGER,
    session_id UUID,
    processed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_rfid_events_timestamp ON rfid_events(event_timestamp);
CREATE INDEX idx_rfid_events_animal ON rfid_events(animal_id);
CREATE INDEX idx_rfid_events_processed ON rfid_events(processed);
```

#### Animal RFID Mapping Table

```sql
CREATE TABLE animal_rfid_mapping (
    id SERIAL PRIMARY KEY,
    animal_id VARCHAR(20) PRIMARY KEY,
    tag_hex VARCHAR(20) UNIQUE NOT NULL,
    country_code VARCHAR(3) DEFAULT '050',
    national_id VARCHAR(20),
    tag_type VARCHAR(20), -- 'ear_tag', 'injectable'
    tag_applied_date DATE,
    tag_status VARCHAR(20) DEFAULT 'active',
    replaced_from VARCHAR(20), -- Previous tag if replaced
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 7. Integration Methods

### 7.1 Fixed Reader at Milking Parlor

#### Hardware Setup

```
┌─────────────────────────────────────────────────────────────────┐
│              MILKING PARLOR RFID INTEGRATION                    │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│   Entry Side                    Milking Position               │
│   ┌─────────┐                  ┌───────────────┐               │
│   │  RFID   │    ┌────────┐   │    COW        │               │
│   │ READER  │───►│ GATE   │──►│   STALL       │               │
│   │  (1m)   │    │CONTROL │   │  ┌─────────┐  │               │
│   └─────────┘    └────────┘   │  │  RFID   │  │               │
│                               │  │  TAG    │  │               │
│                               │  │ (EAR)   │  │               │
│   Components:                 │  └─────────┘  │               │
│   • RFID Reader (FDX-B)       └───────────────┘               │
│   • Raspberry Pi Gateway                                      │
│   • Gate Control Relay                                        │
│   • MQTT Client                                               │
│   • 12V Battery + Solar                                       │
│                                                                  │
│   Workflow:                                                     │
│   1. Cow approaches entry                                       │
│   2. RFID reader detects tag                                    │
│   3. System validates animal ID                                 │
│   4. Gate opens if authorized                                   │
│   5. Session starts in ERP                                      │
│   6. Milk yield recorded against cow ID                         │
│   7. Cow exits, session ends                                    │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

#### Configuration Parameters

| Parameter | Value | Description |
|-----------|-------|-------------|
| `read_interval_ms` | 100 | Polling interval for tag detection |
| `confirm_reads` | 3 | Required consecutive reads for confirmation |
| `session_timeout_sec` | 600 | Auto-close session after inactivity |
| `gate_open_duration_sec` | 10 | Gate stays open after detection |

### 7.2 Handheld Reader for Field Use

#### Bluetooth Integration

```python
# Handheld Reader Bluetooth Connection Example
import asyncio
from bleak import BleakClient, BleakScanner

RFID_SERVICE_UUID = "0000fff0-0000-1000-8000-00805f9b34fb"
RFID_CHAR_UUID = "0000fff1-0000-1000-8000-00805f9b34fb"

async def connect_handheld_reader():
    """Connect to LPR-A10 handheld RFID reader via Bluetooth"""
    devices = await BleakScanner.discover()
    reader = None
    
    for device in devices:
        if "RFID" in str(device.name) or "LPR" in str(device.name):
            reader = device
            break
    
    if not reader:
        raise Exception("No RFID reader found")
    
    async with BleakClient(reader.address) as client:
        def notification_handler(sender, data):
            tag_data = parse_rfid_data(data)
            print(f"Tag detected: {tag_data}")
            # Send to ERP via API
            sync_to_erp(tag_data)
        
        await client.start_notify(RFID_CHAR_UUID, notification_handler)
        await asyncio.sleep(300)  # Listen for 5 minutes
```

#### Field Workflow

1. **Veterinary Check**
   - Scan ear tag
   - View animal health history
   - Record temperature, symptoms
   - Update treatment records

2. **Breeding Management**
   - Scan cow for heat detection
   - Record AI (Artificial Insemination)
   - Update pregnancy status

3. **Movement Recording**
   - Scan animal
   - Select destination paddock
   - Update location in ERP

### 7.3 Mobile App NFC/RFID Integration

#### Option A: Bluetooth Handheld + Mobile App

```dart
// Flutter Mobile App - RFID Integration
import 'package:flutter_blue_plus/flutter_blue_plus.dart';

class RFIDService {
  BluetoothDevice? connectedReader;
  
  Future<void> connectToReader() async {
    // Scan for BLE devices
    FlutterBluePlus.scanResults.listen((results) {
      for (ScanResult result in results) {
        if (result.device.name.contains('RFID')) {
          connectedReader = result.device;
          result.device.connect();
          _startListening();
        }
      }
    });
  }
  
  void _startListening() {
    connectedReader?.discoverServices().then((services) {
      for (var service in services) {
        for (var characteristic in service.characteristics) {
          if (characteristic.properties.notify) {
            characteristic.setNotifyValue(true);
            characteristic.value.listen((data) {
              String tagId = parseTagData(data);
              lookupAnimal(tagId);
            });
          }
        }
      }
    });
  }
}
```

#### Option B: QR Code Scanning (Backup)

```dart
// QR Code Scanner for Visual ID Backup
import 'package:mobile_scanner/mobile_scanner.dart';

class QRScannerScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MobileScanner(
      onDetect: (capture) {
        final List<Barcode> barcodes = capture.barcodes;
        for (final barcode in barcodes) {
          // QR contains visual ID: SD-0001
          String visualId = barcode.rawValue;
          lookupAnimalByVisualId(visualId);
        }
      },
    );
  }
}
```

---

## 8. Installation Guidelines

### 8.1 Mounting Requirements

#### Fixed Reader Mounting

| Parameter | Specification |
|-----------|---------------|
| **Height** | 80-120 cm from ground (cattle ear level) |
| **Angle** | 45° downward toward animal passage |
| **Distance** | 20-50 cm from expected tag position |
| **Enclosure** | IP65 rated for dust/moisture protection |
| **Vibration** | Anti-vibration mounts recommended |

#### Antenna Positioning

```
┌─────────────────────────────────────────────────────────────────┐
│              OPTIMAL ANTENNA POSITIONING                        │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│   Side View:                                                    │
│                                                                  │
│           ANTENNA                                               │
│         ┌───────┐                                               │
│         │       │ 45°                                           │
│         │   ◉   │╲                                              │
│         │       │  ╲  Reading Zone                               │
│         └───┬───┘    ╲  ┌──────────┐                            │
│             │    ──────►│    COW   │                            │
│          100cm          │  ◉ EAR   │                            │
│                         └──────────┘                            │
│                                                                  │
│   Top View:                                                     │
│                                                                  │
│   Entry ──►  ┌───────────────┐  ──► Exit                        │
│              │   PASSAGE     │                                  │
│              │      ◉        │  ◉ = Antenna (one side)          │
│              │    COW ──►    │                                  │
│              └───────────────┘                                  │
│                                                                  │
│   Reading Zone: 30-50 cm width                                  │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 8.2 Wiring Guidelines

#### Power Requirements

| Component | Voltage | Current | Cable Specification |
|-----------|---------|---------|---------------------|
| RFID Reader | 12V DC | 500mA | 2-core, 1.5 mm² |
| Raspberry Pi | 5V DC | 2.5A | USB-C / Micro USB |
| Gate Relay | 12V DC | 200mA | 2-core, 0.75 mm² |

#### Signal Wiring

| Connection | Cable | Length Limit | Notes |
|------------|-------|--------------|-------|
| Reader to Controller | RS232/RS485 | 15m (RS232), 1200m (RS485) | Shielded cable |
| Ethernet | Cat6 | 100m | Standard network |
| Antenna Coax | 50Ω RG58 | 10m | Minimize loss |

### 8.3 Range Optimization

#### Factors Affecting Read Range

| Factor | Impact | Mitigation |
|--------|--------|------------|
| **Metal Interference** | -50% range | Use ferrite shields, increase distance |
| **Moisture/Humidity** | -20% range | Waterproof enclosures, regular maintenance |
| **Electrical Noise** | Intermittent reads | Shielded cables, ferrite cores |
| **Tag Orientation** | -30% if perpendicular | Position antennas for optimal angle |
| **Speed of Movement** | Missed reads at high speed | Multiple antennas, slower passages |

#### Range Optimization Checklist

- [ ] Antenna positioned at optimal height (ear level)
- [ ] No metal objects within 30 cm of antenna
- [ ] Cable runs separated from power cables
- [ ] Reader grounded properly
- [ ] Tags applied correctly on ear
- [ ] Reading zone clearly defined
- [ ] Gate speed adjusted for reliable reading

---

## 9. Software Integration

### 9.1 MQTT Topics

#### Complete Topic Structure

```
smartdairy/
├── parlor1/
│   ├── reader_001/
│   │   ├── tag_detected
│   │   ├── tag_lost
│   │   ├── heartbeat
│   │   └── error
│   └── reader_002/
│       └── ...
├── parlor2/
│   └── ...
├── gate_main/
│   └── ...
├── field/
│   └── ...
├── system/
│   ├── heartbeat
│   └── config
└── commands/
    ├── reader_001/
    │   └── config
    └── ...
```

#### Topic QoS Levels

| Topic Pattern | QoS | Retain | Reason |
|---------------|-----|--------|--------|
| `*/tag_detected` | 1 | No | Ensure delivery, but not retained |
| `*/heartbeat` | 0 | No | Frequent, non-critical |
| `*/error` | 2 | Yes | Critical, must be delivered |
| `system/config` | 1 | Yes | Configuration persistence |

### 9.2 API Endpoints

#### REST API for RFID Integration

```
Base URL: https://erp.smartdairybd.com/api/v1/rfid
Authentication: Bearer Token
```

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/animals/{animal_id}/rfid` | GET | Get RFID details for animal |
| `/animals/lookup/{tag_hex}` | GET | Lookup animal by RFID tag |
| `/animals/{animal_id}/rfid` | POST | Assign new RFID tag |
| `/events` | POST | Submit RFID event |
| `/events/bulk` | POST | Submit multiple events |
| `/readers` | GET | List all RFID readers |
| `/readers/{reader_id}/status` | GET | Get reader status |

#### API Request/Response Examples

**Lookup Animal by RFID:**

```http
GET /api/v1/rfid/animals/lookup/3E900000012345678
Authorization: Bearer {token}
```

```json
{
  "status": "success",
  "data": {
    "animal_id": "SD-1234",
    "name": "Rani",
    "breed": "Holstein-Friesian",
    "birth_date": "2023-03-15",
    "current_lactation": 2,
    "current_status": "lactating",
    "last_milking": "2026-01-31T06:00:00+06:00",
    "location": "Parlor 1"
  }
}
```

**Submit RFID Event:**

```http
POST /api/v1/rfid/events
Authorization: Bearer {token}
Content-Type: application/json

{
  "timestamp": "2026-01-31T08:30:15.123+06:00",
  "reader_id": "reader_001",
  "location": "parlor1",
  "event_type": "tag_detected",
  "tag_hex": "3E900000012345678"
}
```

### 9.3 Data Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        DATA FLOW DIAGRAM                                     │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│   ┌──────────┐    MQTT Publish     ┌──────────────┐                         │
│   │  RFID    │────────────────────►│ MQTT Broker  │                         │
│   │ Reader   │   smartdairy/...    │  (Mosquitto) │                         │
│   └──────────┘                     └──────┬───────┘                         │
│                                           │                                  │
│                    ┌──────────────────────┘                                  │
│                    │                                                         │
│                    ▼                                                         │
│   ┌─────────────────────────────────┐                                       │
│   │      IoT Gateway Service        │                                       │
│   │  • Subscribe to MQTT topics     │                                       │
│   │  • Validate & transform data    │                                       │
│   │  • Local buffering (SQLite)     │                                       │
│   │  • ERP API calls                │                                       │
│   └──────────────┬──────────────────┘                                       │
│                  │                                                           │
│      ┌───────────┴───────────┐                                               │
│      │                       │                                               │
│      ▼                       ▼                                               │
│  ┌──────────┐          ┌──────────┐                                         │
│  │  LOCAL   │◄────────►│  CLOUD   │                                         │
│  │  ERP DB  │   Sync   │  BACKUP  │                                         │
│  └──────────┘          └──────────┘                                         │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 9.4 Python Code for RFID Reader Integration

#### Complete RFID Reader Integration Module

```python
#!/usr/bin/env python3
"""
Smart Dairy RFID Integration Module
Compatible with FDX-B/HDX ISO 11784/11785 readers
Bangladesh Smart Dairy Implementation
"""

import serial
import json
import time
import logging
import sqlite3
from datetime import datetime
from typing import Optional, Dict, Callable
import paho.mqtt.client as mqtt
import requests
from dataclasses import dataclass
from threading import Thread, Event

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)


@dataclass
class RFIDConfig:
    """RFID Reader Configuration"""
    reader_id: str
    location: str
    serial_port: str = '/dev/ttyUSB0'
    baud_rate: int = 9600
    mqtt_broker: str = 'localhost'
    mqtt_port: int = 1883
    mqtt_username: str = ''
    mqtt_password: str = ''
    erp_api_url: str = 'https://erp.smartdairybd.com/api/v1'
    erp_api_token: str = ''
    local_db_path: str = '/var/lib/smartdairy/rfid_cache.db'
    read_interval_ms: int = 100
    confirm_reads: int = 3


class RFIDTag:
    """Represents an RFID tag detection"""
    
    def __init__(self, raw_hex: str, signal_strength: int = 0):
        self.raw_hex = raw_hex.upper()
        self.signal_strength = signal_strength
        self.timestamp = datetime.now().isoformat()
        self._parse_iso11784()
    
    def _parse_iso11784(self):
        """Parse ISO 11784 compliant tag data"""
        if len(self.raw_hex) >= 16:
            # Extract country code (bits 16-25)
            header = self.raw_hex[:4]
            country_national = self.raw_hex[4:]
            
            # Country code is 10 bits
            country_bits = int(self.raw_hex[3:6], 16) >> 6
            self.country_code = f"{country_bits:03d}"
            
            # National ID is remaining bits
            self.national_id = str(int(country_national, 16))
        else:
            self.country_code = "000"
            self.national_id = self.raw_hex
    
    def to_dict(self) -> Dict:
        return {
            'raw_hex': self.raw_hex,
            'country_code': self.country_code,
            'national_id': self.national_id,
            'signal_strength': self.signal_strength
        }


class LocalDatabase:
    """Local SQLite cache for offline operation"""
    
    def __init__(self, db_path: str):
        self.db_path = db_path
        self._init_db()
    
    def _init_db(self):
        """Initialize database schema"""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS rfid_events (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                timestamp TEXT NOT NULL,
                reader_id TEXT NOT NULL,
                location TEXT NOT NULL,
                event_type TEXT NOT NULL,
                tag_hex TEXT NOT NULL,
                signal_strength INTEGER,
                synced BOOLEAN DEFAULT 0,
                retry_count INTEGER DEFAULT 0
            )
        ''')
        
        cursor.execute('''
            CREATE INDEX IF NOT EXISTS idx_timestamp 
            ON rfid_events(timestamp)
        ''')
        
        cursor.execute('''
            CREATE INDEX IF NOT EXISTS idx_synced 
            ON rfid_events(synced)
        ''')
        
        conn.commit()
        conn.close()
    
    def store_event(self, event: Dict):
        """Store event locally"""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            INSERT INTO rfid_events 
            (timestamp, reader_id, location, event_type, tag_hex, signal_strength)
            VALUES (?, ?, ?, ?, ?, ?)
        ''', (
            event['timestamp'],
            event['reader_id'],
            event['location'],
            event['event_type'],
            event['tag_data']['raw_hex'],
            event.get('signal_strength', 0)
        ))
        
        conn.commit()
        conn.close()
    
    def get_unsynced_events(self, limit: int = 100) -> list:
        """Get unsynced events for batch upload"""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            SELECT id, timestamp, reader_id, location, event_type, tag_hex
            FROM rfid_events
            WHERE synced = 0 AND retry_count < 5
            ORDER BY timestamp
            LIMIT ?
        ''', (limit,))
        
        events = cursor.fetchall()
        conn.close()
        return events
    
    def mark_synced(self, event_ids: list):
        """Mark events as synced"""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.executemany(
            'UPDATE rfid_events SET synced = 1 WHERE id = ?',
            [(id,) for id in event_ids]
        )
        
        conn.commit()
        conn.close()


class SmartDairyRFIDReader:
    """Main RFID Reader Integration Class"""
    
    def __init__(self, config: RFIDConfig):
        self.config = config
        self.serial_conn: Optional[serial.Serial] = None
        self.mqtt_client: Optional[mqtt.Client] = None
        self.local_db = LocalDatabase(config.local_db_path)
        self._stop_event = Event()
        self._tag_callbacks: list[Callable] = []
        
        # Tag tracking for session management
        self._current_tags: Dict[str, dict] = {}
        self._read_confirmations: Dict[str, int] = {}
        
        self._setup_mqtt()
    
    def _setup_mqtt(self):
        """Initialize MQTT client"""
        self.mqtt_client = mqtt.Client(client_id=f"rfid_{self.config.reader_id}")
        
        if self.config.mqtt_username:
            self.mqtt_client.username_pw_set(
                self.config.mqtt_username,
                self.config.mqtt_password
            )
        
        self.mqtt_client.on_connect = self._on_mqtt_connect
        self.mqtt_client.on_disconnect = self._on_mqtt_disconnect
        
        try:
            self.mqtt_client.connect(
                self.config.mqtt_broker,
                self.config.mqtt_port,
                keepalive=60
            )
            self.mqtt_client.loop_start()
            logger.info(f"MQTT connected to {self.config.mqtt_broker}")
        except Exception as e:
            logger.error(f"MQTT connection failed: {e}")
    
    def _on_mqtt_connect(self, client, userdata, flags, rc):
        """MQTT connect callback"""
        if rc == 0:
            logger.info("MQTT connected successfully")
        else:
            logger.error(f"MQTT connection failed with code: {rc}")
    
    def _on_mqtt_disconnect(self, client, userdata, rc):
        """MQTT disconnect callback"""
        logger.warning(f"MQTT disconnected with code: {rc}")
    
    def connect_serial(self) -> bool:
        """Connect to RFID reader via serial port"""
        try:
            self.serial_conn = serial.Serial(
                port=self.config.serial_port,
                baudrate=self.config.baud_rate,
                bytesize=serial.EIGHTBITS,
                parity=serial.PARITY_NONE,
                stopbits=serial.STOPBITS_ONE,
                timeout=1
            )
            logger.info(f"Connected to RFID reader on {self.config.serial_port}")
            return True
        except serial.SerialException as e:
            logger.error(f"Failed to connect to serial port: {e}")
            return False
    
    def _read_tag(self) -> Optional[RFIDTag]:
        """Read tag from serial port"""
        if not self.serial_conn or not self.serial_conn.is_open:
            return None
        
        try:
            # Read available data
            if self.serial_conn.in_waiting > 0:
                data = self.serial_conn.readline()
                
                # Parse based on reader protocol
                # FDX-B format: varies by manufacturer
                # Common format: 16 hex characters
                tag_hex = data.decode('ascii', errors='ignore').strip()
                
                # Validate hex format
                if len(tag_hex) >= 16 and all(c in '0123456789ABCDEFabcdef' for c in tag_hex[:16]):
                    return RFIDTag(tag_hex[:16])
                    
        except Exception as e:
            logger.error(f"Error reading from serial: {e}")
        
        return None
    
    def _publish_event(self, event_type: str, tag: RFIDTag, duration: int = 0):
        """Publish RFID event to MQTT"""
        timestamp = datetime.now().isoformat()
        
        event = {
            'timestamp': timestamp,
            'reader_id': self.config.reader_id,
            'location': self.config.location,
            'event_type': event_type,
            'tag_data': tag.to_dict(),
            'signal_strength': tag.signal_strength,
            'session_id': self._generate_session_id(tag)
        }
        
        if duration > 0:
            event['duration_seconds'] = duration
        
        # Publish to MQTT
        topic = f"smartdairy/{self.config.location}/{self.config.reader_id}/{event_type}"
        
        try:
            self.mqtt_client.publish(
                topic,
                json.dumps(event),
                qos=1
            )
            logger.debug(f"Published to {topic}: {tag.raw_hex}")
        except Exception as e:
            logger.error(f"MQTT publish failed: {e}")
        
        # Store locally for backup
        self.local_db.store_event(event)
        
        # Call registered callbacks
        for callback in self._tag_callbacks:
            try:
                callback(event)
            except Exception as e:
                logger.error(f"Callback error: {e}")
    
    def _generate_session_id(self, tag: RFIDTag) -> str:
        """Generate unique session ID for tag detection"""
        import hashlib
        data = f"{tag.raw_hex}_{datetime.now().strftime('%Y%m%d')}"
        return hashlib.md5(data.encode()).hexdigest()
    
    def register_callback(self, callback: Callable):
        """Register callback for tag events"""
        self._tag_callbacks.append(callback)
    
    def run(self):
        """Main reading loop"""
        logger.info("Starting RFID reader loop")
        
        if not self.connect_serial():
            logger.error("Cannot start without serial connection")
            return
        
        while not self._stop_event.is_set():
            try:
                tag = self._read_tag()
                
                if tag:
                    self._process_tag_detection(tag)
                else:
                    # Check for tag losses
                    self._check_tag_losses()
                
                time.sleep(self.config.read_interval_ms / 1000)
                
            except Exception as e:
                logger.error(f"Error in reading loop: {e}")
                time.sleep(1)
    
    def _process_tag_detection(self, tag: RFIDTag):
        """Process tag detection with confirmation logic"""
        tag_id = tag.raw_hex
        
        if tag_id not in self._read_confirmations:
            self._read_confirmations[tag_id] = 0
        
        self._read_confirmations[tag_id] += 1
        
        # Confirm after required number of reads
        if self._read_confirmations[tag_id] == self.config.confirm_reads:
            if tag_id not in self._current_tags:
                # New tag detection
                self._current_tags[tag_id] = {
                    'first_seen': datetime.now(),
                    'tag': tag
                }
                self._publish_event('tag_detected', tag)
                logger.info(f"Tag detected: {tag_id}")
    
    def _check_tag_losses(self):
        """Check for tags that are no longer detected"""
        current_time = datetime.now()
        lost_tags = []
        
        for tag_id, data in self._current_tags.items():
            # If not seen in last 5 seconds, consider lost
            if (current_time - data['first_seen']).seconds > 5:
                if self._read_confirmations.get(tag_id, 0) < self.config.confirm_reads:
                    # False positive, not confirmed
                    lost_tags.append(tag_id)
                else:
                    # Valid tag loss
                    duration = (current_time - data['first_seen']).seconds
                    self._publish_event('tag_lost', data['tag'], duration)
                    logger.info(f"Tag lost: {tag_id}, duration: {duration}s")
                    lost_tags.append(tag_id)
        
        # Cleanup
        for tag_id in lost_tags:
            del self._current_tags[tag_id]
            if tag_id in self._read_confirmations:
                del self._read_confirmations[tag_id]
    
    def stop(self):
        """Stop the reader"""
        logger.info("Stopping RFID reader")
        self._stop_event.set()
        
        if self.serial_conn:
            self.serial_conn.close()
        
        if self.mqtt_client:
            self.mqtt_client.loop_stop()
            self.mqtt_client.disconnect()


class ERPSyncService:
    """Background service to sync events to ERP"""
    
    def __init__(self, local_db: LocalDatabase, erp_api_url: str, api_token: str):
        self.local_db = local_db
        self.erp_api_url = erp_api_url
        self.api_token = api_token
        self._stop_event = Event()
    
    def run(self):
        """Background sync loop"""
        while not self._stop_event.is_set():
            try:
                self._sync_events()
            except Exception as e:
                logger.error(f"Sync error: {e}")
            
            time.sleep(30)  # Sync every 30 seconds
    
    def _sync_events(self):
        """Sync unsynced events to ERP"""
        events = self.local_db.get_unsynced_events(limit=100)
        
        if not events:
            return
        
        synced_ids = []
        
        headers = {
            'Authorization': f'Bearer {self.api_token}',
            'Content-Type': 'application/json'
        }
        
        for event in events:
            event_id, timestamp, reader_id, location, event_type, tag_hex = event
            
            payload = {
                'timestamp': timestamp,
                'reader_id': reader_id,
                'location': location,
                'event_type': event_type,
                'tag_hex': tag_hex
            }
            
            try:
                response = requests.post(
                    f"{self.erp_api_url}/rfid/events",
                    json=payload,
                    headers=headers,
                    timeout=10
                )
                
                if response.status_code == 200:
                    synced_ids.append(event_id)
                else:
                    logger.warning(f"ERP sync failed: {response.status_code}")
                    
            except requests.RequestException as e:
                logger.error(f"ERP request failed: {e}")
                break  # Stop syncing if network is down
        
        if synced_ids:
            self.local_db.mark_synced(synced_ids)
            logger.info(f"Synced {len(synced_ids)} events to ERP")
    
    def stop(self):
        """Stop sync service"""
        self._stop_event.set()


# Example usage
def main():
    """Main entry point"""
    config = RFIDConfig(
        reader_id='reader_001',
        location='parlor1',
        serial_port='/dev/ttyUSB0',
        mqtt_broker='localhost',
        erp_api_url='https://erp.smartdairybd.com/api/v1',
        erp_api_token='your_api_token_here'
    )
    
    # Create reader
    reader = SmartDairyRFIDReader(config)
    
    # Add custom callback
    def on_tag_detected(event):
        print(f"Custom handler: {event['tag_data']['raw_hex']}")
    
    reader.register_callback(on_tag_detected)
    
    # Create and start sync service
    sync_service = ERPSyncService(
        reader.local_db,
        config.erp_api_url,
        config.erp_api_token
    )
    
    sync_thread = Thread(target=sync_service.run)
    sync_thread.daemon = True
    sync_thread.start()
    
    try:
        reader.run()
    except KeyboardInterrupt:
        print("\nShutting down...")
        reader.stop()
        sync_service.stop()


if __name__ == '__main__':
    main()
```

### 9.5 Mobile App Integration Code

#### Flutter RFID Service with Bluetooth

```dart
// lib/services/rfid_service.dart

import 'dart:async';
import 'dart:convert';
import 'dart:typed_data';
import 'package:flutter_blue_plus/flutter_blue_plus.dart';
import 'package:http/http.dart' as http;

class RFIDTag {
  final String rawHex;
  final String countryCode;
  final String nationalId;
  final int signalStrength;
  final DateTime timestamp;

  RFIDTag({
    required this.rawHex,
    required this.countryCode,
    required this.nationalId,
    this.signalStrength = 0,
    required this.timestamp,
  });

  factory RFIDTag.fromBytes(Uint8List data) {
    // Parse FDX-B RFID data from Bluetooth
    String hexString = data.map((b) => b.toRadixString(16).padLeft(2, '0')).join();
    
    // Extract country code and national ID
    String countryCode = '050'; // Bangladesh
    String nationalId = hexString.length >= 16 
        ? int.parse(hexString.substring(4, 16), radix: 16).toString()
        : hexString;

    return RFIDTag(
      rawHex: hexString.toUpperCase(),
      countryCode: countryCode,
      nationalId: nationalId,
      timestamp: DateTime.now(),
    );
  }
}

class RFIDService {
  static final RFIDService _instance = RFIDService._internal();
  factory RFIDService() => _instance;
  RFIDService._internal();

  BluetoothDevice? _connectedReader;
  StreamController<RFIDTag>? _tagStreamController;
  bool _isScanning = false;

  Stream<RFIDTag> get tagStream => _tagStreamController?.stream ?? const Stream.empty();
  bool get isConnected => _connectedReader != null;
  BluetoothDevice? get connectedDevice => _connectedReader;

  Future<void> initialize() async {
    _tagStreamController = StreamController<RFIDTag>.broadcast();
    
    // Check Bluetooth state
    FlutterBluePlus.adapterState.listen((state) {
      if (state == BluetoothAdapterState.off) {
        _tagStreamController?.addError('Bluetooth is disabled');
      }
    });
  }

  Future<List<BluetoothDevice>> scanForReaders({Duration timeout = const Duration(seconds: 10)}) async {
    List<BluetoothDevice> readers = [];
    
    await FlutterBluePlus.startScan(
      withServices: [], // Add specific service UUID if known
      timeout: timeout,
    );

    await for (var results in FlutterBluePlus.scanResults) {
      for (ScanResult result in results) {
        String name = result.device.name.toLowerCase();
        if (name.contains('rfid') || name.contains('lpr') || name.contains('reader')) {
          if (!readers.any((d) => d.remoteId == result.device.remoteId)) {
            readers.add(result.device);
          }
        }
      }
    }

    await FlutterBluePlus.stopScan();
    return readers;
  }

  Future<bool> connectToReader(BluetoothDevice device) async {
    try {
      await device.connect();
      _connectedReader = device;
      
      // Discover services and characteristics
      List<BluetoothService> services = await device.discoverServices();
      
      for (var service in services) {
        for (var characteristic in service.characteristics) {
          if (characteristic.properties.notify) {
            await characteristic.setNotifyValue(true);
            characteristic.value.listen(_onDataReceived);
          }
        }
      }
      
      return true;
    } catch (e) {
      print('Connection error: $e');
      return false;
    }
  }

  void _onDataReceived(List<int> data) {
    if (data.isEmpty) return;
    
    try {
      Uint8List bytes = Uint8List.fromList(data);
      RFIDTag tag = RFIDTag.fromBytes(bytes);
      _tagStreamController?.add(tag);
    } catch (e) {
      print('Data parsing error: $e');
    }
  }

  Future<void> disconnect() async {
    if (_connectedReader != null) {
      await _connectedReader!.disconnect();
      _connectedReader = null;
    }
  }

  // Lookup animal in ERP
  Future<Map<String, dynamic>?> lookupAnimal(String tagHex) async {
    try {
      final response = await http.get(
        Uri.parse('https://erp.smartdairybd.com/api/v1/rfid/animals/lookup/$tagHex'),
        headers: {
          'Authorization': 'Bearer YOUR_API_TOKEN',
          'Content-Type': 'application/json',
        },
      );

      if (response.statusCode == 200) {
        return json.decode(response.body)['data'];
      }
      return null;
    } catch (e) {
      print('Lookup error: $e');
      return null;
    }
  }

  void dispose() {
    _tagStreamController?.close();
    disconnect();
  }
}
```

#### RFID Scanner Screen

```dart
// lib/screens/rfid_scanner_screen.dart

import 'package:flutter/material.dart';
import '../services/rfid_service.dart';

class RFIDScannerScreen extends StatefulWidget {
  @override
  _RFIDScannerScreenState createState() => _RFIDScannerScreenState();
}

class _RFIDScannerScreenState extends State<RFIDScannerScreen> {
  final RFIDService _rfidService = RFIDService();
  List<BluetoothDevice> _availableReaders = [];
  bool _isScanning = false;
  RFIDTag? _lastScannedTag;
  Map<String, dynamic>? _animalData;

  @override
  void initState() {
    super.initState();
    _rfidService.initialize();
    _startTagListener();
  }

  void _startTagListener() {
    _rfidService.tagStream.listen((tag) async {
      setState(() {
        _lastScannedTag = tag;
      });
      
      // Lookup animal in ERP
      final animal = await _rfidService.lookupAnimal(tag.rawHex);
      setState(() {
        _animalData = animal;
      });
      
      // Play success sound
      // SoundPlayer.playBeep();
    }, onError: (error) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: $error')),
      );
    });
  }

  Future<void> _scanForReaders() async {
    setState(() => _isScanning = true);
    
    final readers = await _rfidService.scanForReaders();
    
    setState(() {
      _availableReaders = readers;
      _isScanning = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('RFID Scanner'),
        actions: [
          IconButton(
            icon: Icon(_rfidService.isConnected ? Icons.bluetooth_connected : Icons.bluetooth),
            onPressed: _showReaderSelection,
          ),
        ],
      ),
      body: Column(
        children: [
          // Connection Status
          Container(
            padding: EdgeInsets.all(16),
            color: _rfidService.isConnected ? Colors.green[100] : Colors.orange[100],
            child: Row(
              children: [
                Icon(
                  _rfidService.isConnected ? Icons.check_circle : Icons.warning,
                  color: _rfidService.isConnected ? Colors.green : Colors.orange,
                ),
                SizedBox(width: 8),
                Text(
                  _rfidService.isConnected 
                    ? 'Connected: ${_rfidService.connectedDevice?.name}'
                    : 'No reader connected',
                ),
              ],
            ),
          ),
          
          // Last Scanned Tag
          if (_lastScannedTag != null)
            Card(
              margin: EdgeInsets.all(16),
              child: Padding(
                padding: EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Last Scanned Tag',
                      style: Theme.of(context).textTheme.titleMedium,
                    ),
                    SizedBox(height: 8),
                    Text('Tag ID: ${_lastScannedTag!.rawHex}'),
                    Text('Country: ${_lastScannedTag!.countryCode}'),
                    Text('National ID: ${_lastScannedTag!.nationalId}'),
                    Text('Time: ${_lastScannedTag!.timestamp.toString()}'),
                  ],
                ),
              ),
            ),
          
          // Animal Data
          if (_animalData != null)
            Card(
              margin: EdgeInsets.symmetric(horizontal: 16),
              color: Colors.blue[50],
              child: Padding(
                padding: EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Animal Profile',
                      style: Theme.of(context).textTheme.titleMedium,
                    ),
                    SizedBox(height: 8),
                    Text('ID: ${_animalData!['animal_id']}'),
                    Text('Name: ${_animalData!['name']}'),
                    Text('Breed: ${_animalData!['breed']}'),
                    Text('Status: ${_animalData!['current_status']}'),
                    SizedBox(height: 8),
                    ElevatedButton(
                      onPressed: () => _showAnimalDetails(_animalData!),
                      child: Text('View Details'),
                    ),
                  ],
                ),
              ),
            ),
          
          // Instructions
          Expanded(
            child: Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(
                    Icons.nfc,
                    size: 64,
                    color: Colors.grey,
                  ),
                  SizedBox(height: 16),
                  Text(
                    'Scan an animal ear tag',
                    style: TextStyle(color: Colors.grey),
                  ),
                  if (!_rfidService.isConnected)
                    Padding(
                      padding: EdgeInsets.only(top: 16),
                      child: ElevatedButton(
                        onPressed: _showReaderSelection,
                        child: Text('Connect Reader'),
                      ),
                    ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  void _showReaderSelection() {
    showModalBottomSheet(
      context: context,
      builder: (context) => Container(
        padding: EdgeInsets.all(16),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Text(
              'Select RFID Reader',
              style: Theme.of(context).textTheme.titleLarge,
            ),
            SizedBox(height: 16),
            if (_isScanning)
              CircularProgressIndicator()
            else
              ElevatedButton(
                onPressed: _scanForReaders,
                child: Text('Scan for Readers'),
              ),
            SizedBox(height: 16),
            if (_availableReaders.isNotEmpty)
              Expanded(
                child: ListView.builder(
                  itemCount: _availableReaders.length,
                  itemBuilder: (context, index) {
                    final device = _availableReaders[index];
                    return ListTile(
                      title: Text(device.name),
                      subtitle: Text(device.remoteId.toString()),
                      trailing: ElevatedButton(
                        onPressed: () async {
                          Navigator.pop(context);
                          await _rfidService.connectToReader(device);
                          setState(() {});
                        },
                        child: Text('Connect'),
                      ),
                    );
                  },
                ),
              ),
          ],
        ),
      ),
    );
  }

  void _showAnimalDetails(Map<String, dynamic> animal) {
    Navigator.pushNamed(
      context,
      '/animal-details',
      arguments: animal,
    );
  }

  @override
  void dispose() {
    _rfidService.dispose();
    super.dispose();
  }
}
```

---

## 10. Calibration & Testing

### 10.1 Read Range Testing

#### Test Procedure

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Hold test tag at 10 cm | Consistent read |
| 2 | Hold test tag at 30 cm | Consistent read |
| 3 | Hold test tag at 50 cm | Read (HDX) / May fail (FDX-B) |
| 4 | Move tag quickly through zone | Read captured |
| 5 | Test with dirty/wet tag | Read successful |

#### Range Calibration Checklist

- [ ] Minimum range: 15 cm guaranteed
- [ ] Optimal range: 25-30 cm achieved
- [ ] No false reads from adjacent zones
- [ ] Consistent reading at different angles
- [ ] Reliable reading at animal walking speed

### 10.2 Accuracy Testing

#### Test Scenarios

| Scenario | Test Count | Success Rate Target |
|----------|------------|---------------------|
| Single cow passage | 50 | >98% |
| Multiple cows (2-3) | 50 | >95% |
| Cow with wet ear tag | 20 | >95% |
| Cow with dirty ear tag | 20 | >90% |
| Fast moving cow | 20 | >90% |

### 10.3 Interference Testing

#### Sources of Interference

| Source | Test Method | Mitigation |
|--------|-------------|------------|
| **Metal gates** | Test near/around metal | Ferrite shield, reposition |
| **Electric motors** | Test near milking equipment | Shielded cables, distance |
| **Mobile phones** | Test with active calls | Not significant at 134.2 kHz |
| **Other RFID readers** | Test with multiple active | Frequency separation |
| **Power lines** | Test near electrical panels | Proper grounding |

---

## 11. Troubleshooting

### 11.1 Common Issues

| Issue | Possible Causes | Solution |
|-------|-----------------|----------|
| **No tag reads** | Power, connection, configuration | Check LED indicators, cables, settings |
| **Intermittent reads** | Range, interference, speed | Adjust antenna, check for metal, slow gate |
| **False reads** | Duplicate IDs, collision | Verify unique tags, reduce readers |
| **Missed cows** | Speed, range, tag position | Optimize gate speed, adjust antenna |
| **Cannot write to ERP** | Network, API, authentication | Check connectivity, verify token |

### 11.2 Diagnostics

#### Reader LED Indicators

| LED Pattern | Meaning | Action |
|-------------|---------|--------|
| **Green steady** | Powered, ready | Normal operation |
| **Green blinking** | Tag detected | Monitor reads |
| **Red steady** | Error state | Check logs |
| **Red blinking** | Communication error | Check connections |
| **Off** | No power | Check power supply |

#### Log Analysis

```bash
# View RFID reader logs
sudo journalctl -u smartdairy-rfid -f

# Check MQTT connection
mosquitto_sub -h localhost -t "smartdairy/#" -v

# Test ERP API connectivity
curl -H "Authorization: Bearer TOKEN" \
     https://erp.smartdairybd.com/api/v1/health
```

### 11.3 Diagnostic Commands

```python
# Diagnostic script for RFID system
import serial
import requests

def diagnose_rfid_system():
    """Run comprehensive diagnostics"""
    
    print("=" * 50)
    print("SMART DAIRY RFID DIAGNOSTICS")
    print("=" * 50)
    
    # 1. Serial Port Check
    print("\n[1] Serial Port Check")
    try:
        ser = serial.Serial('/dev/ttyUSB0', 9600, timeout=1)
        print("   ✓ Serial port accessible")
        ser.close()
    except Exception as e:
        print(f"   ✗ Serial port error: {e}")
    
    # 2. MQTT Check
    print("\n[2] MQTT Broker Check")
    try:
        import paho.mqtt.client as mqtt
        client = mqtt.Client()
        client.connect('localhost', 1883, 5)
        print("   ✓ MQTT broker accessible")
        client.disconnect()
    except Exception as e:
        print(f"   ✗ MQTT error: {e}")
    
    # 3. ERP API Check
    print("\n[3] ERP API Check")
    try:
        response = requests.get(
            'https://erp.smartdairybd.com/api/v1/health',
            timeout=10
        )
        if response.status_code == 200:
            print("   ✓ ERP API accessible")
        else:
            print(f"   ✗ ERP API returned: {response.status_code}")
    except Exception as e:
        print(f"   ✗ ERP API error: {e}")
    
    # 4. Database Check
    print("\n[4] Local Database Check")
    try:
        import sqlite3
        conn = sqlite3.connect('/var/lib/smartdairy/rfid_cache.db')
        cursor = conn.cursor()
        cursor.execute('SELECT COUNT(*) FROM rfid_events')
        count = cursor.fetchone()[0]
        print(f"   ✓ Database accessible ({count} events stored)")
        conn.close()
    except Exception as e:
        print(f"   ✗ Database error: {e}")
    
    # 5. Tag Test
    print("\n[5] Tag Detection Test")
    print("   Waiting for tag scan (10 seconds)...")
    try:
        ser = serial.Serial('/dev/ttyUSB0', 9600, timeout=10)
        data = ser.readline()
        if data:
            print(f"   ✓ Tag detected: {data.decode().strip()}")
        else:
            print("   ✗ No tag detected")
        ser.close()
    except Exception as e:
        print(f"   ✗ Tag test error: {e}")
    
    print("\n" + "=" * 50)

if __name__ == '__main__':
    diagnose_rfid_system()
```

---

## 12. Maintenance

### 12.1 Tag Replacement

#### Replacement Triggers

| Condition | Action |
|-----------|--------|
| Tag lost | Replace immediately, record in system |
| Tag damaged | Replace before next breeding cycle |
| Tag faded | Replace during routine check |
| Tag number illegible | Replace with new visible ID |

#### Replacement Procedure

1. Scan old tag (if readable) or use visual ID
2. Remove old tag completely
3. Clean ear with antiseptic
4. Apply new tag with same or new ID
5. Scan new tag to verify
6. Update ERP system with new tag mapping
7. Record replacement reason and date

### 12.2 Reader Maintenance

#### Weekly Checks

- [ ] Clean antenna surface
- [ ] Check cable connections
- [ ] Verify LED indicators
- [ ] Test with known tag
- [ ] Check log files for errors

#### Monthly Maintenance

- [ ] Clean reader enclosure
- [ ] Inspect power supply
- [ ] Update firmware if available
- [ ] Backup configuration
- [ ] Test emergency procedures

#### Quarterly Maintenance

- [ ] Calibrate read range
- [ ] Test all tags in herd
- [ ] Review and optimize settings
- [ ] Update documentation
- [ ] Train staff on new features

### 12.3 Cleaning Procedures

| Component | Method | Frequency |
|-----------|--------|-----------|
| **Antenna** | Damp cloth, mild detergent | Weekly |
| **Enclosure** | Dry cloth, compressed air | Monthly |
| **Cables** | Visual inspection, secure connections | Monthly |
| **Solar Panel** | Water, soft brush | Weekly |

---

## 13. Appendices

### Appendix A: Code Examples

#### A.1 Tag Registration Workflow

```python
#!/usr/bin/env python3
"""
Smart Dairy Tag Registration Workflow
Bangladesh ISO 11784/11785 Implementation
"""

from dataclasses import dataclass
from datetime import datetime
from typing import Optional
import requests

@dataclass
class Animal:
    animal_id: str
    name: str
    breed: str
    birth_date: str
    gender: str

class TagRegistrationService:
    """Service for registering new RFID tags"""
    
    def __init__(self, erp_api_url: str, api_token: str):
        self.erp_api_url = erp_api_url
        self.headers = {
            'Authorization': f'Bearer {api_token}',
            'Content-Type': 'application/json'
        }
    
    def register_new_animal(
        self,
        tag_hex: str,
        animal: Animal,
        tag_type: str = 'ear_tag'
    ) -> dict:
        """
        Complete workflow for registering new animal with RFID tag
        
        Args:
            tag_hex: Raw hexadecimal RFID tag ID
            animal: Animal details
            tag_type: 'ear_tag' or 'injectable'
        
        Returns:
            Registration result
        """
        
        # Step 1: Verify tag is not already registered
        if self._check_tag_exists(tag_hex):
            raise ValueError(f"Tag {tag_hex} is already registered to another animal")
        
        # Step 2: Parse ISO 11784 tag data
        tag_data = self._parse_iso11784(tag_hex)
        
        # Step 3: Create animal record
        animal_record = {
            'animal_id': animal.animal_id,
            'name': animal.name,
            'breed': animal.breed,
            'birth_date': animal.birth_date,
            'gender': animal.gender,
            'status': 'active',
            'registration_date': datetime.now().isoformat()
        }
        
        # Step 4: Create RFID mapping
        rfid_mapping = {
            'animal_id': animal.animal_id,
            'tag_hex': tag_hex.upper(),
            'country_code': tag_data['country_code'],
            'national_id': tag_data['national_id'],
            'tag_type': tag_type,
            'tag_applied_date': datetime.now().isoformat(),
            'tag_status': 'active'
        }
        
        # Step 5: Submit to ERP
        result = self._submit_to_erp(animal_record, rfid_mapping)
        
        return {
            'success': True,
            'animal_id': animal.animal_id,
            'tag_hex': tag_hex,
            'message': 'Animal registered successfully'
        }
    
    def _check_tag_exists(self, tag_hex: str) -> bool:
        """Check if tag is already registered"""
        try:
            response = requests.get(
                f"{self.erp_api_url}/rfid/animals/lookup/{tag_hex}",
                headers=self.headers,
                timeout=10
            )
            return response.status_code == 200
        except requests.RequestException:
            return False
    
    def _parse_iso11784(self, tag_hex: str) -> dict:
        """Parse ISO 11784 tag data"""
        tag_hex = tag_hex.upper().replace(' ', '')
        
        if len(tag_hex) < 16:
            raise ValueError("Invalid tag length")
        
        # Extract header (4 hex chars = 16 bits)
        header = tag_hex[:4]
        
        # Extract country code and national ID
        # Country code: 10 bits starting at bit 16
        # Simplified parsing for Bangladesh tags
        country_code = "050"  # Bangladesh
        national_id = str(int(tag_hex[4:16], 16))
        
        return {
            'header': header,
            'country_code': country_code,
            'national_id': national_id,
            'raw_hex': tag_hex
        }
    
    def _submit_to_erp(self, animal: dict, rfid: dict) -> dict:
        """Submit records to ERP"""
        
        # Create animal
        animal_response = requests.post(
            f"{self.erp_api_url}/animals",
            json=animal,
            headers=self.headers,
            timeout=30
        )
        animal_response.raise_for_status()
        
        # Create RFID mapping
        rfid_response = requests.post(
            f"{self.erp_api_url}/rfid/animals/{animal['animal_id']}/rfid",
            json=rfid,
            headers=self.headers,
            timeout=30
        )
        rfid_response.raise_for_status()
        
        return {
            'animal': animal_response.json(),
            'rfid': rfid_response.json()
        }
    
    def replace_tag(
        self,
        animal_id: str,
        old_tag_hex: str,
        new_tag_hex: str,
        reason: str
    ) -> dict:
        """
        Replace existing tag with new one
        
        Args:
            animal_id: Animal identifier
            old_tag_hex: Current tag being replaced
            new_tag_hex: New tag to assign
            reason: Reason for replacement
        """
        
        # Step 1: Verify old tag matches animal
        old_lookup = requests.get(
            f"{self.erp_api_url}/rfid/animals/lookup/{old_tag_hex}",
            headers=self.headers
        )
        
        if old_lookup.status_code != 200:
            raise ValueError("Old tag not found in system")
        
        old_data = old_lookup.json()
        if old_data['data']['animal_id'] != animal_id:
            raise ValueError("Old tag does not match specified animal")
        
        # Step 2: Verify new tag is not in use
        if self._check_tag_exists(new_tag_hex):
            raise ValueError("New tag is already registered")
        
        # Step 3: Deactivate old tag
        deactivate_data = {
            'tag_status': 'replaced',
            'replaced_date': datetime.now().isoformat(),
            'replaced_reason': reason,
            'replaced_by': new_tag_hex
        }
        
        requests.put(
            f"{self.erp_api_url}/rfid/animals/{animal_id}/rfid",
            json=deactivate_data,
            headers=self.headers
        )
        
        # Step 4: Register new tag
        new_tag_data = self._parse_iso11784(new_tag_hex)
        new_mapping = {
            'animal_id': animal_id,
            'tag_hex': new_tag_hex.upper(),
            'country_code': new_tag_data['country_code'],
            'national_id': new_tag_data['national_id'],
            'tag_type': 'ear_tag',
            'tag_applied_date': datetime.now().isoformat(),
            'tag_status': 'active',
            'replaced_from': old_tag_hex
        }
        
        response = requests.post(
            f"{self.erp_api_url}/rfid/animals/{animal_id}/rfid",
            json=new_mapping,
            headers=self.headers
        )
        response.raise_for_status()
        
        return {
            'success': True,
            'animal_id': animal_id,
            'old_tag': old_tag_hex,
            'new_tag': new_tag_hex,
            'message': 'Tag replaced successfully'
        }


# Example usage
def main():
    """Example tag registration workflow"""
    
    service = TagRegistrationService(
        erp_api_url='https://erp.smartdairybd.com/api/v1',
        api_token='your_api_token_here'
    )
    
    # Register new animal
    new_animal = Animal(
        animal_id='SD-1234',
        name='Rani',
        breed='Holstein-Friesian',
        birth_date='2023-03-15',
        gender='female'
    )
    
    try:
        result = service.register_new_animal(
            tag_hex='3E900000012345678',
            animal=new_animal,
            tag_type='ear_tag'
        )
        print(f"Registration successful: {result}")
    except Exception as e:
        print(f"Registration failed: {e}")
    
    # Replace lost tag
    try:
        result = service.replace_tag(
            animal_id='SD-1234',
            old_tag_hex='3E900000012345678',
            new_tag_hex='3E900000087654321',
            reason='Tag lost in field'
        )
        print(f"Tag replacement successful: {result}")
    except Exception as e:
        print(f"Tag replacement failed: {e}")


if __name__ == '__main__':
    main()
```

#### A.2 Batch Tag Registration

```python
#!/usr/bin/env python3
"""
Batch Tag Registration for Smart Dairy
Import multiple tags from CSV file
"""

import csv
from datetime import datetime
from tag_registration import TagRegistrationService, Animal

def batch_register_tags(csv_file: str, api_url: str, api_token: str):
    """
    Register multiple animals from CSV file
    
    CSV Format:
    tag_hex,animal_id,name,breed,birth_date,gender,tag_type
    3E900000012345678,SD-0001,Rani,Holstein,2023-03-15,female,ear_tag
    """
    
    service = TagRegistrationService(api_url, api_token)
    
    results = {
        'success': [],
        'failed': []
    }
    
    with open(csv_file, 'r') as f:
        reader = csv.DictReader(f)
        
        for row in reader:
            try:
                animal = Animal(
                    animal_id=row['animal_id'],
                    name=row['name'],
                    breed=row['breed'],
                    birth_date=row['birth_date'],
                    gender=row['gender']
                )
                
                result = service.register_new_animal(
                    tag_hex=row['tag_hex'],
                    animal=animal,
                    tag_type=row.get('tag_type', 'ear_tag')
                )
                
                results['success'].append({
                    'animal_id': row['animal_id'],
                    'tag_hex': row['tag_hex']
                })
                
                print(f"✓ Registered: {row['animal_id']}")
                
            except Exception as e:
                results['failed'].append({
                    'animal_id': row['animal_id'],
                    'tag_hex': row['tag_hex'],
                    'error': str(e)
                })
                
                print(f"✗ Failed: {row['animal_id']} - {e}")
    
    # Summary report
    print("\n" + "=" * 50)
    print("BATCH REGISTRATION SUMMARY")
    print("=" * 50)
    print(f"Total: {len(results['success']) + len(results['failed'])}")
    print(f"Success: {len(results['success'])}")
    print(f"Failed: {len(results['failed'])}")
    
    if results['failed']:
        print("\nFailed registrations:")
        for fail in results['failed']:
            print(f"  - {fail['animal_id']}: {fail['error']}")
    
    return results


if __name__ == '__main__':
    import sys
    
    if len(sys.argv) != 2:
        print("Usage: python batch_register.py <csv_file>")
        sys.exit(1)
    
    batch_register_tags(
        csv_file=sys.argv[1],
        api_url='https://erp.smartdairybd.com/api/v1',
        api_token='your_api_token_here'
    )
```

### Appendix B: Wiring Diagrams

#### B.1 Milking Parlor RFID Setup

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    MILKING PARLOR RFID WIRING DIAGRAM                        │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                         POWER SUPPLY (12V)                          │   │
│  │                        Battery + Solar Panel                        │   │
│  │                              12V 10A                                │   │
│  │                               │                                     │   │
│  │           ┌───────────────────┼───────────────────┐                 │   │
│  │           │                   │                   │                 │   │
│  │           ▼                   ▼                   ▼                 │   │
│  │    ┌──────────┐       ┌──────────┐       ┌──────────┐             │   │
│  │    │RFID READER│       │Raspberry │       │  GATE    │             │   │
│  │    │  12V DC  │       │  Pi 4    │       │  RELAY   │             │   │
│  │    │  500mA   │       │  5V 3A   │       │  12V     │             │   │
│  │    └────┬─────┘       └────┬─────┘       └────┬─────┘             │   │
│  │         │                  │                  │                    │   │
│  │         │    RS232/USB     │     GPIO         │                    │   │
│  │         └──────────────────┘     Control      │                    │   │
│  │                    │                          │                    │   │
│  │                    │                          │                    │   │
│  │                    ▼                          ▼                    │   │
│  │           ┌──────────────────────────────────────────┐             │   │
│  │           │              ANTENNA                     │             │   │
│  │           │           (Panel/Stick)                  │             │   │
│  │           └──────────────────────────────────────────┘             │   │
│  │                              │                                     │   │
│  │                              │                                     │   │
│  │                    ┌─────────┴─────────┐                           │   │
│  │                    │                   │                           │   │
│  │                    ▼                   ▼                           │   │
│  │            ┌──────────┐       ┌──────────┐                        │   │
│  │            │  ENTRY   │       │   EXIT   │                        │   │
│  │            │  GATE    │       │  GATE    │                        │   │
│  │            └──────────┘       └──────────┘                        │   │
│  │                                                                    │   │
│  └────────────────────────────────────────────────────────────────────┘   │
│                                                                            │
│  NETWORK CONNECTION:                                                        │
│  Raspberry Pi ──Ethernet/WiFi──► Local Network ──► ERP Server              │
│                                                                            │
│  MQTT BROKER:                                                               │
│  Raspberry Pi (local) or Central Server                                     │
│                                                                            │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### B.2 Cable Specifications

| Connection | Cable Type | Gauge | Length | Connector |
|------------|------------|-------|--------|-----------|
| Power (12V) | 2-core | 16 AWG | <10m | Terminal block |
| Power (5V Pi) | USB-C | - | - | USB-C |
| Serial RS232 | Shielded | 24 AWG | <15m | DB9 |
| Serial RS485 | Twisted pair | 24 AWG | <1200m | Terminal block |
| Ethernet | Cat6 | - | <100m | RJ45 |
| Antenna Coax | RG58 | 50Ω | <10m | SMA/BNC |

### Appendix C: Vendor List

#### C.1 RFID Hardware Suppliers

**Bangladesh Suppliers**

| Supplier | Location | Products | Contact |
|----------|----------|----------|---------|
| Smart Technologies BD Ltd. | Dhaka | Complete IoT solutions | info@smartgroup.com.bd |
| Livestock Services BD | Dhaka | Ear tags, readers | livestock@example.com |
| Vet World Bangladesh | Chittagong | Veterinary equipment | vetworld@example.com |
| Agricultural Solutions | Gazipur | Farm equipment | agri_sol@example.com |

**International Suppliers**

| Supplier | Country | Products | Website |
|----------|---------|----------|---------|
| Allflex | France/USA | Premium livestock RFID | www.allflex.global |
| Lifang Electronics | China | RFID readers, tags | www.lifangelec.com |
| NingBO PanDa | China | RFID equipment | www.pandarfid.com |
| HID Global | USA | Industrial RFID | www.hidglobal.com |

#### C.2 Local Support Contacts

| Role | Contact | Phone | Email |
|------|---------|-------|-------|
| **IoT Architect** | [Name] | [Phone] | [Email] |
| **System Administrator** | [Name] | [Phone] | [Email] |
| **Farm Manager** | [Name] | [Phone] | [Email] |
| **Technical Support** | Smart Group | +880 XXXX | support@smartgroup.com.bd |

### Appendix D: Solar Power Sizing

```
┌─────────────────────────────────────────────────────────────────┐
│              SOLAR POWER CALCULATION                            │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Daily Power Requirement:                                       │
│                                                                  │
│  Component          Power    Hours/Day    Daily Energy          │
│  ─────────────────────────────────────────────────────          │
│  RFID Reader        6W       24h          144 Wh                │
│  Raspberry Pi       7.5W     24h          180 Wh                │
│  Gate Relay         2.4W     2h           4.8 Wh                │
│  Network Switch     5W       24h          120 Wh                │
│  ─────────────────────────────────────────────────────          │
│  Total Daily Need                           448.8 Wh            │
│                                                                  │
│  With 50% safety margin: 448.8 × 1.5 = 673 Wh/day               │
│                                                                  │
│  Solar Panel Sizing (Bangladesh):                               │
│  • Average sun hours: 5 hours/day                               │
│  • Required panel: 673 Wh ÷ 5 h = 135 W                         │
│  • Recommendation: 200W panel (allows for cloudy days)          │
│                                                                  │
│  Battery Sizing:                                                │
│  • Required: 673 Wh ÷ 12V = 56 Ah                               │
│  • Depth of discharge: 50%                                      │
│  • Required capacity: 56 Ah × 2 = 112 Ah                        │
│  • Recommendation: 150 Ah deep cycle battery                    │
│                                                                  │
│  Charge Controller: 20A MPPT                                    │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### Appendix E: Compliance Notes

#### E.1 Bangladesh Animal Identification Regulations

| Requirement | Status | Notes |
|-------------|--------|-------|
| **DLS Registration** | Required | Department of Livestock Services |
| **Movement Permit** | RFID tracked | Inter-district transport |
| **Vaccination Records** | Linked to RFID | FMD, Anthrax, etc. |
| **Breeding Records** | RFID tracked | Genetic improvement program |

#### E.2 Data Privacy

- Animal RFID data is operational data, not personally identifiable
- ERP access controls restrict data visibility
- Audit trail maintained for all tag registrations
- Data backup required per company policy

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | I-005 |
| **Version** | 1.0 |
| **Author** | IoT Engineer |
| **Owner** | IoT Architect |
| **Reviewer** | Solution Architect |
| **Approval Date** | January 31, 2026 |
| **Next Review** | July 31, 2026 |

### Distribution List

| Role | Name | Department |
|------|------|------------|
| IoT Architect | [Name] | IT |
| Solution Architect | [Name] | IT |
| Farm Manager | [Name] | Operations |
| Veterinary Lead | [Name] | Animal Health |

---

*End of Document I-005: RFID Reader Integration Guide*

**Smart Dairy Ltd.**  
*Technology for Sustainable Farming*
