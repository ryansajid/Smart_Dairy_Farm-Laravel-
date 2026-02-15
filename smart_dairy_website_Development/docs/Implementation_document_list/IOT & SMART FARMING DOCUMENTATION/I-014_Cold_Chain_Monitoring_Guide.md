# SMART DAIRY LTD.
## COLD CHAIN MONITORING GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | I-014 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | IoT Engineer |
| **Owner** | Quality Manager |
| **Reviewer** | Operations Manager |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Cold Chain Stages](#2-cold-chain-stages)
3. [Temperature Monitoring](#3-temperature-monitoring)
4. [Alert Thresholds](#4-alert-thresholds)
5. [HACCP Compliance](#5-haccp-compliance)
6. [Bangladesh Standards](#6-bangladesh-standards)
7. [Real-time Monitoring](#7-real-time-monitoring)
8. [Automated Controls](#8-automated-controls)
9. [Data Logging](#9-data-logging)
10. [Excursion Management](#10-excursion-management)
11. [Equipment Maintenance](#11-equipment-maintenance)
12. [Integration with Traceability](#12-integration-with-traceability)
13. [Reporting](#13-reporting)
14. [Appendices](#14-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides comprehensive guidelines for implementing and managing the cold chain monitoring system for Smart Dairy Ltd. The cold chain ensures dairy products maintain quality, safety, and regulatory compliance from production through consumption.

### 1.2 Cold Chain Importance for Dairy

Dairy products are highly perishable and require strict temperature control to:

| Risk Factor | Impact of Temperature Abuse | Consequence |
|-------------|---------------------------|-------------|
| **Bacterial Growth** | Psychrotrophic bacteria multiply rapidly above 4°C | Spoilage, reduced shelf life |
| **Pathogen Proliferation** | *Listeria*, *Salmonella*, *E. coli* growth | Foodborne illness risk |
| **Nutritional Degradation** | Vitamin loss, protein denaturation | Reduced product quality |
| **Sensory Changes** | Off-flavors, texture changes | Customer dissatisfaction |
| **Economic Loss** | Product rejection, recalls | Financial impact |

### 1.3 Scope

| Component | Coverage |
|-----------|----------|
| **Bulk Milk Cooling** | Farm storage tanks, immediate cooling |
| **Processing Storage** | Raw milk silos, pasteurized milk tanks |
| **Transportation** | Tankers, refrigerated vehicles |
| **Distribution Centers** | Cold rooms, staging areas |
| **Retail Display** | Refrigerated cabinets, chillers |
| **Documentation** | Compliance records, audit trails |

### 1.4 Critical Temperature Standards

| Product Category | Temperature Range | Maximum Exposure |
|-----------------|-------------------|------------------|
| **Raw Milk** | 0°C to 4°C | 2 hours above 4°C |
| **Pasteurized Milk** | 0°C to 4°C | 30 minutes above 4°C |
| **Yogurt** | 2°C to 6°C | 1 hour above 6°C |
| **Cheese (Soft)** | 2°C to 4°C | 30 minutes above 4°C |
| **Cheese (Hard)** | 4°C to 8°C | 2 hours above 8°C |
| **Butter** | 0°C to 4°C | 1 hour above 4°C |
| **Ice Cream** | -18°C to -12°C | 10 minutes above -12°C |
| **Transport** | < 4°C | Continuous monitoring |

---

## 2. COLD CHAIN STAGES

### 2.1 System Overview

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                    SMART DAIRY COLD CHAIN MONITORING SYSTEM                      │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                  │
│  FARM                    PROCESSING           DISTRIBUTION        RETAIL        │
│    │                         │                    │                │            │
│    ▼                         ▼                    ▼                ▼            │
│ ┌──────────┐            ┌──────────┐        ┌──────────┐      ┌──────────┐     │
│ │  MILKING │────────────▶│   RAW    │───────▶│TRANSPORT │─────▶│  COLD    │     │
│ │  PARLOR  │   4°C      │  STORAGE │  4°C   │ TANKERS  │ 4°C  │  STORAGE │     │
│ └────┬─────┘            └────┬─────┘        └────┬─────┘      └────┬─────┘     │
│      │                       │                   │                 │           │
│ ┌────▼─────┐            ┌────▼─────┐        ┌────▼─────┐      ┌────▼─────┐     │
│ │BULK COOL │────────────▶│PASTEUR- │───────▶│REFRIG.  │─────▶│DISPLAY   │     │
│ │  TANK    │   4°C      │  IZATION │  4°C   │ VEHICLES│ 4°C  │ CABINETS│     │
│ │(4°C)     │            │(72°C/15s)│        │         │      │(4°C)    │     │
│ └──────────┘            └────┬─────┘        └──────────┘      └──────────┘     │
│                              │                                                   │
│                         ┌────▼─────┐                                             │
│                         │ PACKAGED │                                             │
│                         │ PRODUCTS │                                             │
│                         │  (4°C)   │                                             │
│                         └──────────┘                                             │
│                                                                                  │
│  MONITORING: ● Temperature  ● Humidity  ● Door Status  ● Compressor Status     │
│                                                                                  │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Stage 1: Bulk Milk Cooling (4°C)

| Parameter | Specification |
|-----------|--------------|
| **Target Temperature** | ≤ 4°C within 2 hours of milking |
| **Cooling Rate** | ≥ 0.5°C per minute |
| **Tank Capacity** | 1,000L - 10,000L |
| **Agitation** | Continuous gentle stirring |
| **Monitoring Points** | 3 sensors per tank (top, middle, bottom) |

**Cooling System Requirements:**

```
┌─────────────────────────────────────────────────────────┐
│              BULK MILK COOLING TANK                      │
├─────────────────────────────────────────────────────────┤
│                                                         │
│   ┌─────────────────────────────────────────┐          │
│   │              INSULATION                  │          │
│   │  ┌─────────────────────────────────┐   │          │
│   │  │                                 │   │          │
│   │  │    ○ SENSOR 1 (Top)             │   │          │
│   │  │         T = 3.8°C               │   │          │
│   │  │                                 │   │          │
│   │  │    ○ SENSOR 2 (Middle)          │   │          │
│   │  │         T = 3.5°C               │   │          │
│   │  │                                 │   │          │
│   │  │    ○ SENSOR 3 (Bottom)          │   │          │
│   │  │         T = 3.2°C               │   │          │
│   │  │                                 │   │          │
│   │  │    ████████████████████████     │   │          │
│   │  │         MILK (4°C)              │   │          │
│   │  │                                 │   │          │
│   │  └─────────────────────────────────┘   │          │
│   │              │ EVAPORATOR │              │          │
│   │              └────────────┘              │          │
│   └─────────────────────────────────────────┘          │
│                   │ COMPRESSOR │                       │
│                   └────────────┘                       │
│                                                         │
│   CONTROLLER ──▶ IoT Gateway ──▶ MQTT Broker          │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

### 2.3 Stage 2: Storage Tanks

| Storage Type | Capacity | Temperature | Monitoring Frequency |
|--------------|----------|-------------|---------------------|
| **Raw Milk Silo** | 20,000-50,000L | 0-4°C | Every 1 minute |
| **Pasteurized Tank** | 10,000-30,000L | 0-4°C | Every 1 minute |
| **Product Storage** | 5,000-15,000L | 0-4°C | Every 1 minute |
| **Ingredient Storage** | 1,000-5,000L | 0-4°C | Every 5 minutes |

### 2.4 Stage 3: Transport Vehicles

| Vehicle Type | Capacity | Temperature Control | Sensor Count |
|--------------|----------|---------------------|--------------|
| **Milk Tanker** | 5,000-20,000L | Refrigerated, insulated | 4 sensors |
| **Distribution Truck** | 2,000-8,000L | Refrigerated unit | 6 sensors |
| **Delivery Van** | 500-2,000L | Portable cooling | 4 sensors |
| **Cold Box** | 50-200L | Gel packs/ice | 2 sensors |

**Transport Monitoring Architecture:**

```
┌─────────────────────────────────────────────────────────────────┐
│                 REFRIGERATED TRANSPORT VEHICLE                   │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐         │
│  │   ZONE 1    │    │   ZONE 2    │    │   ZONE 3    │         │
│  │  (Front)    │    │  (Middle)   │    │   (Rear)    │         │
│  │             │    │             │    │             │         │
│  │  ○ T: 3.5°C │    │  ○ T: 3.8°C │    │  ○ T: 3.6°C │         │
│  │  ○ H: 85%   │    │  ○ H: 82%   │    │  ○ H: 84%   │         │
│  │             │    │             │    │             │         │
│  │  [PRODUCTS] │    │  [PRODUCTS] │    │  [PRODUCTS] │         │
│  └──────┬──────┘    └──────┬──────┘    └──────┬──────┘         │
│         │                  │                  │                │
│         └──────────────────┼──────────────────┘                │
│                            │                                    │
│                    ┌───────▼────────┐                          │
│                    │  IoT GATEWAY   │                          │
│                    │  (GPS + 4G)    │                          │
│                    └───────┬────────┘                          │
│                            │                                    │
│                    ┌───────▼────────┐                          │
│                    │  REFRIGERATION │                          │
│                    │     UNIT       │                          │
│                    │  [COMPRESSOR]  │                          │
│                    └────────────────┘                          │
│                                                                  │
│  Real-time: Temperature ○  Location ○  Door Status ○           │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 2.5 Stage 4: Distribution Centers

| Area Type | Temperature | Humidity | Air Changes |
|-----------|-------------|----------|-------------|
| **Cold Room (Raw)** | 0-4°C | 85-90% | 10-15/hour |
| **Cold Room (Finished)** | 0-4°C | 80-85% | 10-15/hour |
| **Loading Dock** | 4-8°C | 70-80% | 20/hour |
| **Staging Area** | 4-8°C | 70-80% | 15/hour |
| **Blast Freezer** | -35°C | N/A | High velocity |

### 2.6 Stage 5: Retail Displays

| Display Type | Temperature Range | Monitoring |
|--------------|-------------------|------------|
| **Upright Cooler** | 1-5°C | 2 sensors per unit |
| **Open Display** | 2-6°C | 3 sensors per unit |
| **Grab-and-Go** | 2-5°C | 2 sensors per unit |
| **Night Blind** | Automatic closure | Door sensor |

---

## 3. TEMPERATURE MONITORING

### 3.1 Sensor Types

| Sensor Category | Technology | Accuracy | Best Use Case |
|-----------------|------------|----------|---------------|
| **Wired Sensors** | RTD (Pt100) | ±0.1°C | Fixed installations, high accuracy |
| **Wireless Sensors** | LoRaWAN/Zigbee | ±0.2°C | Mobile assets, retrofitting |
| **Data Loggers** | Digital/Analog | ±0.2°C | Transport, audit compliance |
| **Infrared** | Thermal | ±0.5°C | Surface monitoring, spot checks |
| **Thermocouples** | Type T/K | ±0.5°C | Harsh environments |

### 3.2 Recommended Sensor Specifications

```
┌─────────────────────────────────────────────────────────────────────┐
│              TEMPERATURE SENSOR SPECIFICATIONS                       │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  WIRED RTD SENSOR (Primary)                                          │
│  ┌─────────────────────────────────────────────────────────────┐    │
│  │ Model: Pt100 Class A                                        │    │
│  │ Accuracy: ±0.15°C                                           │    │
│  │ Range: -50°C to +150°C                                      │    │
│  │ Response Time: < 10 seconds                                 │    │
│  │ Connection: 4-wire                                          │    │
│  │ Ingress Protection: IP67                                    │    │
│  │ Calibration: Annual NIST traceable                          │    │
│  └─────────────────────────────────────────────────────────────┘    │
│                                                                      │
│  WIRELESS SENSOR (Secondary/Mobile)                                  │
│  ┌─────────────────────────────────────────────────────────────┐    │
│  │ Protocol: LoRaWAN 868MHz (Bangladesh)                       │    │
│  │ Accuracy: ±0.2°C                                            │    │
│  │ Battery Life: 5+ years                                      │    │
│  │ Transmission: Every 1-5 minutes                             │    │
│  │ Range: 2km rural, 500m urban                                │    │
│  │ Ingress Protection: IP65                                    │    │
│  └─────────────────────────────────────────────────────────────┘    │
│                                                                      │
│  TRANSPORT DATA LOGGER                                               │
│  ┌─────────────────────────────────────────────────────────────┐    │
│  │ Type: USB PDF Logger                                        │    │
│  │ Memory: 16,000 readings                                     │    │
│  │ Accuracy: ±0.3°C                                            │    │
│  │ Logging Interval: 1-60 minutes                              │    │
│  │ Battery: Replaceable 3V lithium                             │    │
│  │ Certification: WHO PQS E006                                 │    │
│  └─────────────────────────────────────────────────────────────┘    │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.3 Sensor Placement Guidelines

#### 3.3.1 Storage Tanks

```
┌─────────────────────────────────────────────────────────┐
│                   TANK SENSOR PLACEMENT                  │
├─────────────────────────────────────────────────────────┤
│                                                         │
│                    TOP                                  │
│              ┌─────────────┐                           │
│             ╱   ○ S1      ╲          S1 = Top layer    │
│            │   (1/4 depth) │          (warmest point)   │
│            │               │                           │
│            │   ○ S2        │          S2 = Middle      │
│            │   (1/2 depth) │          (reference)      │
│            │               │                           │
│            │   ○ S3        │          S3 = Bottom      │
│            │   (3/4 depth) │          (coolest point)  │
│            │               │                           │
│             ╲             ╱                            │
│              └─────────────┘                           │
│                   BOTTOM                                │
│                                                         │
│  NOTE: Avoid placement near inlet, outlet, or walls    │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

#### 3.3.2 Cold Rooms

| Location | Sensor Count | Purpose |
|----------|--------------|---------|
| **Geometric Center** | 1 | Representative room temperature |
| **Near Door** | 1 | Detect warm air infiltration |
| **Near Evaporator** | 1 | Monitor cooling performance |
| **Near Product Stack** | 1-2 | Product zone temperature |
| **Return Air** | 1 | System efficiency monitoring |

#### 3.3.3 Transport Vehicles

| Position | Sensor Count | Rationale |
|----------|--------------|-----------|
| **Front (cab side)** | 1 | Warmest zone, engine heat |
| **Middle** | 1-2 | Representative cargo temperature |
| **Rear (door side)** | 1 | Door opening impact |
| **Return air stream** | 1 | Cooling system performance |

### 3.4 Calibration Requirements

| Sensor Type | Calibration Frequency | Method | Tolerance |
|-------------|----------------------|--------|-----------|
| **Fixed RTD** | Every 12 months | Ice bath (0°C) + 1 point | ±0.2°C |
| **Wireless** | Every 12 months | Reference thermometer | ±0.3°C |
| **Data Loggers** | Every 6 months | Certified calibration bath | ±0.3°C |
| **Infrared** | Every 6 months | Black body reference | ±0.5°C |

**Calibration Procedure:**

```
CALIBRATION WORKFLOW
═══════════════════

1. PRE-CALIBRATION
   □ Remove sensor from service
   □ Visual inspection for damage
   □ Check cable/connectors
   □ Record current readings

2. ICE BATH TEST (0°C Reference)
   □ Prepare slush ice (50% ice, 50% distilled water)
   □ Insert sensor to full immersion depth
   □ Wait 5 minutes for stabilization
   □ Record reading at 1-minute intervals (5 readings)
   □ Calculate average and offset

3. SECOND POINT (if required)
   □ Use certified reference bath at 4°C or 25°C
   □ Follow same procedure as ice bath

4. ACCEPTANCE CRITERIA
   □ Ice point: 0°C ± 0.2°C
   □ If outside tolerance: Adjust or Replace

5. DOCUMENTATION
   □ Complete calibration certificate
   □ Update calibration database
   □ Apply calibration sticker
   □ Schedule next calibration

6. POST-CALIBRATION
   □ Reinstall sensor
   □ Verify communication
   □ Record in maintenance log
```

---

## 4. ALERT THRESHOLDS

### 4.1 Alert Classification

| Alert Level | Color Code | Trigger Condition | Response Time |
|-------------|------------|-------------------|---------------|
| **Critical** | 🔴 Red | Temperature > 4°C for > 30 min | Immediate |
| **Warning** | 🟡 Yellow | Temperature > 3°C trending up | 5 minutes |
| **Caution** | 🟠 Orange | Door open > 5 minutes | 10 minutes |
| **Info** | 🔵 Blue | Equipment status changes | Log only |

### 4.2 Temperature Alert Matrix

```
┌─────────────────────────────────────────────────────────────────────┐
│              TEMPERATURE ALERT THRESHOLD MATRIX                      │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  RAW MILK / PASTEURIZED MILK (Target: 0-4°C)                         │
│  ─────────────────────────────────────────────                       │
│                                                                      │
│  Temperature                                                          │
│       │                                                              │
│   8°C ┤                        🔴 CRITICAL                           │
│       │                   (Immediate action required)                │
│   6°C ┤                  ╱                                         │
│       │                 ╱  🟡 WARNING                                │
│   4°C ┤─────────────●──╱────────── Normal Range                      │
│       │            (3.5°C + rising trend)                           │
│   3°C ┤                                                              │
│       │                                                              │
│   0°C ┤──────────────○──────────────────── Target Zone               │
│       │                                                              │
│  -2°C ┤                        (Sensor fault if sustained)           │
│       │                                                              │
│       └──┬─────┬─────┬─────┬─────┬─────┬─────┬─────┬─────▶ Time      │
│         0m   10m   20m   30m   40m   50m   60m                      │
│                                                                      │
│  YOGURT (Target: 2-6°C)                                              │
│  ───────────────────────                                             │
│                                                                      │
│  Critical: > 8°C for > 30 minutes                                    │
│  Warning: > 6°C trending up                                          │
│                                                                      │
│  ICE CREAM (Target: -18°C to -12°C)                                  │
│  ─────────────────────────────────                                   │
│                                                                      │
│  Critical: > -10°C for > 10 minutes                                  │
│  Warning: > -12°C trending up                                        │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

### 4.3 Equipment Failure Alerts

| Equipment Type | Monitored Parameters | Failure Indicators |
|----------------|---------------------|-------------------|
| **Compressor** | Current, pressure, runtime | High current, low pressure, short cycling |
| **Evaporator** | Fan status, frost detection | Fan failure, excessive frost |
| **Condenser** | Fan status, temperature | High discharge temp, fan failure |
| **Door/Gate** | Open/close status | Open > 5 minutes, frequent cycling |
| **Power** | Voltage, frequency | Outage, voltage fluctuations |
| **IoT Gateway** | Connectivity, signal strength | Disconnection > 5 minutes |

### 4.4 Alert Escalation Procedure

```
┌─────────────────────────────────────────────────────────────────────┐
│                 ALERT ESCALATION PROCEDURE                           │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  ALERT TRIGGERED                                                     │
│       │                                                              │
│       ▼                                                              │
│  ┌─────────────────┐                                                 │
│  │ System Detects  │                                                 │
│  │ Temperature     │                                                 │
│  │ Excursion       │                                                 │
│  └────────┬────────┘                                                 │
│           │                                                          │
│           ▼                                                          │
│  ┌─────────────────┐     ┌─────────────────┐                         │
│  │ T+0: Dashboard  │────▶│ T+0: Mobile App │                         │
│  │ Alert           │     │ Push + SMS      │                         │
│  │                 │     │                 │                         │
│  │ Operator on-duty│     │ Operator on-duty│                         │
│  └────────┬────────┘     └─────────────────┘                         │
│           │                                                          │
│           ▼ (If not acknowledged in 5 min)                           │
│  ┌─────────────────┐                                                 │
│  │ T+5: Escalate   │                                                 │
│  │ to Shift        │                                                 │
│  │ Supervisor      │                                                 │
│  │ (SMS + Call)    │                                                 │
│  └────────┬────────┘                                                 │
│           │                                                          │
│           ▼ (If not resolved in 15 min)                              │
│  ┌─────────────────┐                                                 │
│  │ T+20: Escalate  │                                                 │
│  │ to Operations   │                                                 │
│  │ Manager         │                                                 │
│  │ (Call + Email)  │                                                 │
│  └────────┬────────┘                                                 │
│           │                                                          │
│           ▼ (If not resolved in 30 min)                              │
│  ┌─────────────────┐                                                 │
│  │ T+50: Product   │                                                 │
│  │ Quarantine      │                                                 │
│  │ Protocol        │                                                 │
│  │ Activated       │                                                 │
│  └────────┬────────┘                                                 │
│           │                                                          │
│           ▼ (If critical failure)                                    │
│  ┌─────────────────┐                                                 │
│  │ T+60: Quality   │                                                 │
│  │ Manager +       │                                                 │
│  │ Plant Manager   │                                                 │
│  │ (Emergency)     │                                                 │
│  └─────────────────┘                                                 │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

**Escalation Contacts:**

| Level | Role | Contact Method | Response SLA |
|-------|------|----------------|--------------|
| L1 | On-duty Operator | Mobile App + SMS | 5 minutes |
| L2 | Shift Supervisor | SMS + Voice Call | 10 minutes |
| L3 | Operations Manager | Voice Call + Email | 15 minutes |
| L4 | Quality Manager | Voice Call + SMS | 20 minutes |
| L5 | Plant Manager | Emergency Call | 30 minutes |

---

## 5. HACCP COMPLIANCE

### 5.1 Critical Control Points (CCPs)

| CCP | Location | Hazard | Critical Limit | Monitoring | Corrective Action |
|-----|----------|--------|----------------|------------|-------------------|
| **CCP-1** | Bulk Cooling | Bacterial growth | ≤ 4°C within 2h | Continuous, 1-min | Repair/replace cooler |
| **CCP-2** | Raw Milk Storage | Pathogen growth | 0-4°C | Continuous, 1-min | Isolate product |
| **CCP-3** | Pasteurization | Pathogen survival | 72°C × 15 sec | Continuous | Reprocess/reject |
| **CCP-4** | Cold Storage | Quality degradation | 0-4°C | Continuous, 1-min | Repair cooling |
| **CCP-5** | Transport | Temperature abuse | < 4°C | Continuous, 1-min | Quarantine load |
| **CCP-6** | Distribution | Temperature abuse | 0-4°C | Every 5 min | Reject delivery |

### 5.2 HACCP Documentation Templates

#### Template A: CCP Monitoring Log

```
┌─────────────────────────────────────────────────────────────────────┐
│              CCP MONITORING LOG - COLD CHAIN                         │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  Date: _______________  Shift: _______________                       │
│  Location: ___________  Operator: ____________                       │
│  CCP Reference: _______  Product: ____________                       │
│                                                                      │
│  ┌─────────┬──────────┬───────────────┬──────────┬─────────────────┐ │
│  │ Time    │ Temp (°C)│ Within Limit? │ Initials │ Comments        │ │
│  ├─────────┼──────────┼───────────────┼──────────┼─────────────────┤ │
│  │ 06:00   │          │ Yes / No      │          │                 │ │
│  │ 07:00   │          │ Yes / No      │          │                 │ │
│  │ 08:00   │          │ Yes / No      │          │                 │ │
│  │ 09:00   │          │ Yes / No      │          │                 │ │
│  │ 10:00   │          │ Yes / No      │          │                 │ │
│  │ 11:00   │          │ Yes / No      │          │                 │ │
│  │ 12:00   │          │ Yes / No      │          │                 │ │
│  │ 13:00   │          │ Yes / No      │          │                 │ │
│  │ 14:00   │          │ Yes / No      │          │                 │ │
│  │ 15:00   │          │ Yes / No      │          │                 │ │
│  │ 16:00   │          │ Yes / No      │          │                 │ │
│  │ 17:00   │          │ Yes / No      │          │                 │ │
│  │ 18:00   │          │ Yes / No      │          │                 │ │
│  │ 19:00   │          │ Yes / No      │          │                 │ │
│  │ 20:00   │          │ Yes / No      │          │                 │ │
│  │ 21:00   │          │ Yes / No      │          │                 │ │
│  │ 22:00   │          │ Yes / No      │          │                 │ │
│  │ 23:00   │          │ Yes / No      │          │                 │ │
│  │ 24:00   │          │ Yes / No      │          │                 │ │
│  └─────────┴──────────┴───────────────┴──────────┴─────────────────┘ │
│                                                                      │
│  DEVIATIONS RECORDED: ___________________________________________   │
│                                                                      │
│  CORRECTIVE ACTIONS: ____________________________________________   │
│                                                                      │
│  VERIFIED BY (SUPERVISOR): _________________ Date: _____________     │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

#### Template B: Temperature Excursion Report

```
┌─────────────────────────────────────────────────────────────────────┐
│           TEMPERATURE EXCURSION REPORT                               │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  Report ID: ________________  Date: _______________                  │
│  Location: _________________  CCP: _______________                   │
│                                                                      │
│  EXCURSION DETAILS                                                   │
│  ────────────────                                                    │
│  Start Time: _______________  End Time: ____________                 │
│  Duration: _________________  Max Temperature: _______               │
│  Product Affected: __________ Batch/Lot: ____________                │
│  Quantity: _________________  Current Status: ________               │
│                                                                      │
│  ROOT CAUSE ANALYSIS                                                 │
│  ───────────────────                                                 │
│  □ Equipment Failure    □ Power Outage    □ Door Left Open          │
│  □ Overloading          □ Ambient Temp    □ Other: __________       │
│                                                                      │
│  Description: ________________________________________________       │
│  _____________________________________________________________       │
│                                                                      │
│  IMMEDIATE ACTIONS TAKEN                                             │
│  ─────────────────────────                                           │
│  _____________________________________________________________       │
│  _____________________________________________________________       │
│                                                                      │
│  PRODUCT DISPOSITION                                                 │
│  ───────────────────                                                 │
│  □ Approved for Sale    □ Rejected/Destroyed    □ Further Testing   │
│                                                                      │
│  PREVENTIVE ACTIONS                                                  │
│  ───────────────────                                                 │
│  _____________________________________________________________       │
│                                                                      │
│  Reported By: _________________  Date: _______________               │
│  Reviewed By (QA): ____________  Date: _______________               │
│  Approved By (QM): ____________  Date: _______________               │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

### 5.3 Verification Procedures

| Verification Activity | Frequency | Responsibility | Records |
|----------------------|-----------|----------------|---------|
| **Calibration Check** | Monthly | Maintenance | Calibration log |
| **Alarm Test** | Weekly | Operator | Test log |
| **Chart Review** | Daily | Supervisor | Review sign-off |
| **Internal Audit** | Quarterly | QA | Audit report |
| **System Validation** | Annually | QA/External | Validation report |

---

## 6. BANGLADESH STANDARDS

### 6.1 Bangladesh Food Safety Authority (BFSA) Requirements

| Regulation | Requirement | Implementation |
|------------|-------------|----------------|
| **BFSA Cold Chain Guidelines** | Continuous temperature monitoring | IoT sensors, 1-minute intervals |
| **BFSA Licensing** | Record keeping for 2 years | Automated data logging |
| **Import/Export** | Temperature documentation | Digital certificates |
| **Retail Standards** | Display temperature compliance | Retail sensor network |

### 6.2 BSTI Standards

| Standard | Title | Relevance |
|----------|-------|-----------|
| **BDS 1406** | Milk and Milk Products | Quality parameters |
| **BDS 1743** | Food Hygiene - General Principles | HACCP implementation |
| **BDS 22000** | Food Safety Management Systems | System certification |

### 6.3 Compliance Checklist

```
┌─────────────────────────────────────────────────────────────────────┐
│         BFSA/BSTI COMPLIANCE CHECKLIST - COLD CHAIN                  │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  INFRASTRUCTURE                                                      │
│  □ Insulated storage facilities with temperature monitoring          │
│  □ Refrigerated transport vehicles with data loggers                 │
│  □ Backup power systems (minimum 4 hours)                            │
│  □ Temperature alarm systems with SMS alerts                         │
│                                                                      │
│  MONITORING                                                          │
│  □ Calibrated temperature sensors at all critical points             │
│  □ Continuous recording (minimum 5-minute intervals)                 │
│  □ Automated alerts for temperature deviations                       │
│  □ Digital records with audit trail                                  │
│                                                                      │
│  DOCUMENTATION                                                       │
│  □ Standard Operating Procedures (SOPs)                              │
│  □ HACCP plan with critical control points                           │
│  □ Temperature monitoring logs (2-year retention)                    │
│  □ Calibration certificates                                          │
│  □ Training records                                                  │
│                                                                      │
│  TRAINING                                                            │
│  □ Cold chain awareness for all staff                                │
│  □ Temperature monitoring equipment operation                        │
│  □ Emergency response procedures                                     │
│  □ Record keeping requirements                                       │
│                                                                      │
│  AUDIT                                                               │
│  □ Internal audits (quarterly)                                       │
│  □ Third-party audits (annual)                                       │
│  □ BFSA inspections                                                  │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 7. REAL-TIME MONITORING

### 7.1 Dashboard Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│              COLD CHAIN MONITORING DASHBOARD                         │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │  OVERALL STATUS                              Alerts: 2      │   │
│  │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐       │   │
│  │  │   FARM   │ │  PLANT   │ │ TRANSPORT│ │  RETAIL  │       │   │
│  │  │   ✅     │ │   ⚠️     │ │   ✅     │ │   ✅     │       │   │
│  │  │  3.5°C   │ │  4.2°C   │ │  3.2°C   │ │  3.8°C   │       │   │
│  │  │  12 dev  │ │  8 dev   │ │  5 veh   │ │  15 unit │       │   │
│  │  └──────────┘ └──────────┘ └──────────┘ └──────────┘       │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                      │
│  ┌────────────────────────┐  ┌────────────────────────────────┐   │
│  │  TEMPERATURE TREND     │  │  ALERT SUMMARY                 │   │
│  │                        │  │                                │   │
│  │  5°C ┤           ●     │  │  🔴 Critical: Tank B2 > 4°C   │   │
│  │  4°C ┤────●────●──●──  │  │     (Ongoing - 15 min)        │   │
│  │  3°C ┤──●────●────●    │  │                                │   │
│  │  2°C ┤                 │  │  🟡 Warning: Van V3 door open │   │
│  │      └▶▶▶▶▶▶▶▶▶▶▶▶   │  │     (4 min elapsed)           │   │
│  │       00 06 12 18     │  │                                │   │
│  │                        │  │  🔵 Info: Calibration due     │   │
│  │  Legend: ● Tank A1     │  │     (Sensor S47)              │   │
│  │          ○ Tank B2     │  │                                │   │
│  └────────────────────────┘  └────────────────────────────────┘   │
│                                                                      │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │  LIVE SENSOR MAP                                             │   │
│  │                                                              │   │
│  │   FARM                PLANT              RETAIL              │   │
│  │   ┌──────┐           ┌──────┐           ┌──────┐            │   │
│  │   │ T1 ✅│           │ S1 ✅│           │ R1 ✅│            │   │
│  │   │ 3.2°C│           │ 3.8°C│           │ 3.5°C│            │   │
│  │   ├──────┤           ├──────┤           ├──────┤            │   │
│  │   │ T2 ✅│           │ S2 ⚠️│           │ R2 ✅│            │   │
│  │   │ 3.4°C│           │ 4.2°C│           │ 3.6°C│            │   │
│  │   └──────┘           ├──────┤           └──────┘            │   │
│  │                      │ S3 ✅│                               │   │
│  │                      │ 3.6°C│                               │   │
│  │                      └──────┘                               │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

### 7.2 Mobile Alerts

| Alert Type | Notification Channel | Content |
|------------|---------------------|---------|
| **Critical** | Push + SMS + Call | Location, temperature, duration, action required |
| **Warning** | Push + SMS | Location, temperature trend, suggested action |
| **Caution** | Push only | Door open, power fluctuation |
| **Daily Summary** | Email | 24-hour statistics, exceptions, compliance status |
| **Weekly Report** | Email | Trends, maintenance alerts, calibration reminders |

### 7.3 Dashboard Features

| Feature | Description | Access Level |
|---------|-------------|--------------|
| **Real-time View** | Live temperature display | All users |
| **Historical Charts** | Trend analysis, up to 2 years | Supervisor+ |
| **Geolocation Map** | Vehicle tracking | Operations+ |
| **Alert Management** | Acknowledge, escalate, close | Operator+ |
| **Report Generation** | Compliance reports, audits | QA+ |
| **Configuration** | Thresholds, schedules | Admin |

---

## 8. AUTOMATED CONTROLS

### 8.1 Cooling System Integration

```
┌─────────────────────────────────────────────────────────────────────┐
│            AUTOMATED COOLING CONTROL SYSTEM                          │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  ┌───────────────┐     ┌───────────────┐     ┌───────────────┐     │
│  │  TEMPERATURE  │────▶│    PLC/       │────▶│  COMPRESSOR   │     │
│  │   SENSORS     │     │  CONTROLLER   │     │    UNIT       │     │
│  │  (4-20mA)     │     │               │     │               │     │
│  └───────────────┘     └───────┬───────┘     └───────────────┘     │
│                                │                                    │
│                                │                                    │
│                         ┌──────▼───────┐                           │
│                         │  IoT GATEWAY │                           │
│                         │  (Modbus/    │                           │
│                         │   BACnet)    │                           │
│                         └──────┬───────┘                           │
│                                │                                    │
│                                ▼                                    │
│                         ┌───────────────┐                           │
│                         │  SMART DAIRY  │                           │
│                         │    CLOUD      │                           │
│                         │  (MQTT/REST)  │                           │
│                         └───────────────┘                           │
│                                                                      │
│  CONTROL LOGIC:                                                      │
│  ─────────────                                                       │
│                                                                      │
│  IF Temperature > 4.0°C THEN                                         │
│      Start Compressor                                                │
│      Increase Fan Speed                                              │
│      Send Alert (Info)                                               │
│  END IF                                                              │
│                                                                      │
│  IF Temperature > 4.5°C for > 5 min THEN                             │
│      Max Cooling Output                                              │
│      Send Alert (Warning)                                            │
│  END IF                                                              │
│                                                                      │
│  IF Door Open > 5 min THEN                                           │
│      Activate Alarm                                                  │
│      Send Alert (Caution)                                            │
│  END IF                                                              │
│                                                                      │
│  IF Power Failure THEN                                               │
│      Activate Backup Generator                                       │
│      Send Alert (Critical)                                           │
│  END IF                                                              │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

### 8.2 Control Sequences

| Scenario | Sensor Input | Controller Action | Alert |
|----------|--------------|-------------------|-------|
| **Normal Cooling** | T > 3.5°C | Compressor ON | None |
| **High Load** | T > 4.0°C | Max cooling + fan boost | Info |
| **Temperature Rise** | T > 4.0°C for > 5 min | Check door status | Warning |
| **Critical High** | T > 4.5°C for > 10 min | Emergency cooling + notify | Critical |
| **Door Open** | Door sensor = OPEN | Pause cooling timer | Caution |
| **Power Outage** | Power monitor = OFF | Switch to backup + alert | Critical |

### 8.3 Backup Systems

| System Type | Capacity | Activation | Monitoring |
|-------------|----------|------------|------------|
| **UPS** | 30 min runtime | Automatic | Battery level, load |
| **Diesel Generator** | 8-24 hours | Automatic | Fuel level, run hours |
| **Battery Backup (IoT)** | 72 hours | Automatic | Battery voltage |
| **Backup Cooling** | 4 hours capacity | Manual | Temperature, pressure |

---

## 9. DATA LOGGING

### 9.1 Continuous Data Recording

| Data Type | Recording Interval | Retention Period | Storage |
|-----------|-------------------|------------------|---------|
| **Temperature** | 1 minute | 2 years | TimescaleDB |
| **Alerts/Events** | Real-time | 5 years | PostgreSQL |
| **System Status** | 5 minutes | 1 year | PostgreSQL |
| **Calibration Data** | Per event | 10 years | PostgreSQL |
| **Audit Logs** | Real-time | 7 years | Immutable storage |

### 9.2 Database Schema

```sql
-- Cold chain temperature readings table
CREATE TABLE cold_chain.temperature_readings (
    id BIGSERIAL PRIMARY KEY,
    sensor_id VARCHAR(50) NOT NULL REFERENCES cold_chain.sensors(id),
    location_id VARCHAR(50) NOT NULL REFERENCES cold_chain.locations(id),
    timestamp TIMESTAMPTZ NOT NULL,
    temperature_celsius DECIMAL(4,2) NOT NULL,
    humidity_percent DECIMAL(5,2),
    door_status VARCHAR(10),
    compressor_status VARCHAR(10),
    alert_status VARCHAR(20) DEFAULT 'normal',
    product_batch_id VARCHAR(50),
    gps_latitude DECIMAL(10,8),
    gps_longitude DECIMAL(11,8),
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Create hypertable for time-series data
SELECT create_hypertable('cold_chain.temperature_readings', 'timestamp', 
                         chunk_time_interval => INTERVAL '1 day');

-- Temperature alerts table
CREATE TABLE cold_chain.temperature_alerts (
    id SERIAL PRIMARY KEY,
    sensor_id VARCHAR(50) NOT NULL,
    location_id VARCHAR(50) NOT NULL,
    alert_type VARCHAR(20) NOT NULL, -- 'critical', 'warning', 'caution'
    threshold_value DECIMAL(4,2) NOT NULL,
    actual_value DECIMAL(4,2) NOT NULL,
    started_at TIMESTAMPTZ NOT NULL,
    ended_at TIMESTAMPTZ,
    duration_minutes INTEGER,
    acknowledged_at TIMESTAMPTZ,
    acknowledged_by VARCHAR(100),
    resolution_notes TEXT,
    product_impact BOOLEAN DEFAULT FALSE,
    batch_ids TEXT[],
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Sensor calibration log
CREATE TABLE cold_chain.calibration_log (
    id SERIAL PRIMARY KEY,
    sensor_id VARCHAR(50) NOT NULL,
    calibrated_at TIMESTAMPTZ NOT NULL,
    calibrated_by VARCHAR(100) NOT NULL,
    reference_standard VARCHAR(100),
    ice_point_reading DECIMAL(4,2),
    calibration_point_2 DECIMAL(4,2),
    offset_correction DECIMAL(4,2),
    pass_fail VARCHAR(10),
    certificate_number VARCHAR(50),
    next_due_date DATE,
    notes TEXT
);

-- Indexes for performance
CREATE INDEX idx_temp_readings_sensor_time ON cold_chain.temperature_readings(sensor_id, timestamp DESC);
CREATE INDEX idx_temp_readings_location ON cold_chain.temperature_readings(location_id);
CREATE INDEX idx_temp_readings_batch ON cold_chain.temperature_readings(product_batch_id);
CREATE INDEX idx_alerts_active ON cold_chain.temperature_alerts(ended_at) WHERE ended_at IS NULL;
```

### 9.3 Audit Trail Requirements

| Data Element | Requirement | Implementation |
|--------------|-------------|----------------|
| **Timestamp** | UTC, millisecond precision | Server timestamp |
| **User Identity** | Authenticated user ID | JWT token |
| **Action Type** | Create, Read, Update, Delete | Enum field |
| **Data Changes** | Before/after values | JSON diff |
| **IP Address** | Source IP | Request metadata |
| **Integrity** | Tamper-proof | Digital signatures |

---

## 10. EXCURSION MANAGEMENT

### 10.1 Incident Response Protocol

```
┌─────────────────────────────────────────────────────────────────────┐
│           TEMPERATURE EXCURSION RESPONSE PROTOCOL                    │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  PHASE 1: DETECTION (T+0 to T+5 min)                                 │
│  ─────────────────────────────────                                   │
│  □ Alert received and acknowledged                                   │
│  □ Verify sensor reading accuracy                                    │
│  □ Check equipment status (compressor, door, power)                  │
│  □ Document initial findings                                         │
│                                                                      │
│  PHASE 2: CONTAINMENT (T+5 to T+15 min)                              │
│  ─────────────────────────────────────                               │
│  □ Implement immediate corrective action                             │
│      - Close open doors                                              │
│      - Reset/ restart equipment                                      │
│      - Activate backup cooling                                       │
│  □ Mark affected product zone                                        │
│  □ Increase monitoring frequency                                     │
│                                                                      │
│  PHASE 3: ASSESSMENT (T+15 to T+30 min)                              │
│  ─────────────────────────────────────                               │
│  □ Determine product temperature history                             │
│  □ Calculate Time-Temperature exposure                               │
│  □ Identify affected batches/lots                                    │
│  □ Initiate product hold/quarantine                                  │
│                                                                      │
│  PHASE 4: DISPOSITION (T+30 min onwards)                             │
│  ─────────────────────────────────────                               │
│  □ Quality assessment by QA team                                     │
│  □ Microbiological testing (if required)                             │
│  □ Decision: Release / Reprocess / Destroy                           │
│  □ Complete documentation                                            │
│  □ Root cause analysis                                               │
│  □ Preventive action implementation                                  │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

### 10.2 Product Quarantine Procedure

| Step | Action | Responsibility | Documentation |
|------|--------|----------------|---------------|
| 1 | Tag product with "HOLD - DO NOT USE" | Operator | Quarantine tag |
| 2 | Move to quarantine area | Warehouse | Transfer record |
| 3 | Record batch details in system | QA | Quarantine log |
| 4 | Notify QA Manager | System | Alert notification |
| 5 | Quality assessment | QA Lab | Test results |
| 6 | Disposition decision | Quality Manager | Disposition form |
| 7 | Update inventory status | Warehouse | ERP update |

### 10.3 Time-Temperature Tolerance (TTT)

| Product | Base Temp | Max Temp | Max Duration | Cumulative Effect |
|---------|-----------|----------|--------------|-------------------|
| Raw Milk | 4°C | 7°C | 2 hours | Bacterial count doubles every 20 min at 7°C |
| Pasteurized Milk | 4°C | 7°C | 30 min | Quality degradation measurable |
| Yogurt | 4°C | 10°C | 1 hour | Culture viability affected |
| Soft Cheese | 4°C | 8°C | 30 min | Texture breakdown begins |
| Hard Cheese | 4°C | 12°C | 2 hours | Surface mold risk increases |

---

## 11. EQUIPMENT MAINTENANCE

### 11.1 Sensor Maintenance Schedule

| Maintenance Task | Frequency | Procedure | Records |
|-----------------|-----------|-----------|---------|
| **Visual Inspection** | Weekly | Check for damage, corrosion | Inspection log |
| **Cleaning** | Monthly | Clean sensor probe with alcohol | Maintenance log |
| **Calibration Check** | Quarterly | Verify against reference | Calibration log |
| **Full Calibration** | Annually | NIST traceable calibration | Certificate |
| **Replacement** | As needed | Swap failed sensors | Asset register |

### 11.2 Sensor Replacement Criteria

| Condition | Action | Priority |
|-----------|--------|----------|
| Calibration failure | Replace immediately | Critical |
| Physical damage | Replace immediately | Critical |
| Drift > ±0.3°C | Replace within 1 week | High |
| Communication failure | Troubleshoot/replace | High |
| Battery < 20% (wireless) | Replace battery | Medium |
| Age > 5 years | Planned replacement | Low |

### 11.3 Spare Parts Inventory

| Item | Quantity | Storage | Shelf Life |
|------|----------|---------|------------|
| PT100 Sensors | 20 | Climate controlled | 10 years |
| Wireless Sensors | 30 | Original packaging | 5 years (battery) |
| Data Loggers | 15 | Climate controlled | 3 years (battery) |
| Calibration Equipment | 2 sets | Laboratory | Annual calibration |
| Cables/Connectors | Various | Dry storage | Indefinite |

---

## 12. INTEGRATION WITH TRACEABILITY

### 12.1 Batch Tracking Integration

```
┌─────────────────────────────────────────────────────────────────────┐
│         COLD CHAIN + TRACEABILITY INTEGRATION                        │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  PRODUCTION                                                          │
│  ┌─────────┐   ┌─────────┐   ┌─────────┐                           │
│  │  BATCH  │──▶│  COLD   │──▶│  BATCH  │                           │
│  │ CREATED │   │ STORAGE │   │  QR CODE│                           │
│  │  #B001  │   │  4°C    │   │ PRINTED │                           │
│  └────┬────┘   └────┬────┘   └────┬────┘                           │
│       │             │             │                                 │
│       │    ┌────────┴────────┐    │                                 │
│       │    │                 │    │                                 │
│       │    ▼                 ▼    │                                 │
│       │ ┌─────────────────────────┐│                                 │
│       │ │   TEMPERATURE LOG       ││                                 │
│       │ │   Linked to Batch #B001 ││                                 │
│       │ │   - 2026-01-31 06:00: 3.5°C                              │
│       │ │   - 2026-01-31 07:00: 3.2°C                              │
│       │ │   - 2026-01-31 08:00: 3.8°C                              │
│       │ └─────────────────────────┘│                                 │
│       │             │              │                                 │
│       └─────────────┼──────────────┘                                 │
│                     │                                                │
│  TRANSPORT          │                                                │
│  ┌─────────┐        │        ┌─────────┐                            │
│  │ VEHICLE │        │        │  RETAIL │                            │
│  │  LOAD   │────────┴────────│  SCAN   │                            │
│  │  #V001  │                 │  #B001  │                            │
│  └────┬────┘                 └────┬────┘                            │
│       │                           │                                 │
│       ▼                           ▼                                 │
│  ┌─────────────────────────────────────────┐                        │
│  │  CONSUMER QR SCAN                       │                        │
│  │  ┌─────────────────────────────────┐   │                        │
│  │  │  Smart Dairy Pasteurized Milk   │   │                        │
│  │  │  Batch: #B001                   │   │                        │
│  │  │  Production: 2026-01-31         │   │                        │
│  │  │  Expiry: 2026-02-07             │   │                        │
│  │  │                                 │   │                        │
│  │  │  Cold Chain Status: ✅ VERIFIED │   │                        │
│  │  │  Min Temp: 2.8°C | Max: 4.1°C   │   │                        │
│  │  │  Temp Excursions: None          │   │                        │
│  │  │                                 │   │                        │
│  │  │  [View Full Journey]            │   │                        │
│  │  └─────────────────────────────────┘   │                        │
│  └─────────────────────────────────────────┘                        │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

### 12.2 QR Code Data Structure

```json
{
  "batch_id": "B001-20260131",
  "product": "Pasteurized Milk 1L",
  "production_date": "2026-01-31T06:00:00Z",
  "expiry_date": "2026-02-07T06:00:00Z",
  "cold_chain_summary": {
    "min_temperature": 2.8,
    "max_temperature": 4.1,
    "avg_temperature": 3.4,
    "excursions_count": 0,
    "compliance_status": "PASSED"
  },
  "journey": [
    {"stage": "Production", "location": "Plant A", "temp": 3.5, "time": "06:00"},
    {"stage": "Storage", "location": "Cold Room 1", "temp": 3.2, "time": "08:00"},
    {"stage": "Transport", "location": "Van V3", "temp": 3.8, "time": "10:00"},
    {"stage": "Retail", "location": "Store 15", "temp": 3.6, "time": "12:00"}
  ]
}
```

---

## 13. REPORTING

### 13.1 Compliance Reports

| Report Type | Frequency | Audience | Content |
|-------------|-----------|----------|---------|
| **Daily Temperature Log** | Daily | QA/Operations | 24-hour summary, exceptions |
| **Weekly Compliance Summary** | Weekly | Management | KPIs, trends, incidents |
| **Monthly HACCP Review** | Monthly | HACCP Team | CCP monitoring, corrective actions |
| **Quarterly Audit Report** | Quarterly | BFSA/External | Full compliance status |
| **Annual Validation Report** | Annual | Certification Body | System validation, calibrations |

### 13.2 Compliance Reporting Queries

```sql
-- Daily Temperature Compliance Summary
WITH daily_stats AS (
    SELECT 
        location_id,
        DATE(timestamp) as date,
        COUNT(*) as total_readings,
        AVG(temperature_celsius) as avg_temp,
        MIN(temperature_celsius) as min_temp,
        MAX(temperature_celsius) as max_temp,
        COUNT(CASE WHEN temperature_celsius > 4.0 THEN 1 END) as readings_above_4c
    FROM cold_chain.temperature_readings
    WHERE DATE(timestamp) = CURRENT_DATE - 1
    GROUP BY location_id, DATE(timestamp)
)
SELECT 
    location_id,
    date,
    total_readings,
    ROUND(avg_temp::numeric, 2) as avg_temp,
    min_temp,
    max_temp,
    readings_above_4c,
    CASE 
        WHEN readings_above_4c = 0 THEN 'COMPLIANT'
        WHEN readings_above_4c < (total_readings * 0.01) THEN 'MINOR DEVIATION'
        ELSE 'NON-COMPLIANT'
    END as compliance_status
FROM daily_stats
ORDER BY location_id;

-- Temperature Excursion Summary (Monthly)
SELECT 
    alert_type,
    COUNT(*) as incident_count,
    AVG(duration_minutes) as avg_duration_min,
    SUM(CASE WHEN product_impact THEN 1 ELSE 0 END) as product_impact_count,
    COUNT(DISTINCT location_id) as locations_affected
FROM cold_chain.temperature_alerts
WHERE started_at >= DATE_TRUNC('month', CURRENT_DATE)
  AND started_at < DATE_TRUNC('month', CURRENT_DATE + INTERVAL '1 month')
GROUP BY alert_type
ORDER BY incident_count DESC;

-- Sensor Calibration Status
SELECT 
    s.sensor_id,
    s.location_id,
    c.calibrated_at,
    c.next_due_date,
    CASE 
        WHEN c.next_due_date < CURRENT_DATE THEN 'OVERDUE'
        WHEN c.next_due_date < CURRENT_DATE + INTERVAL '30 days' THEN 'DUE SOON'
        ELSE 'CURRENT'
    END as status
FROM cold_chain.sensors s
LEFT JOIN LATERAL (
    SELECT * FROM cold_chain.calibration_log 
    WHERE sensor_id = s.sensor_id 
    ORDER BY calibrated_at DESC 
    LIMIT 1
) c ON true
ORDER BY c.next_due_date;

-- Cold Chain Performance by Product Batch
SELECT 
    product_batch_id,
    MIN(timestamp) as journey_start,
    MAX(timestamp) as journey_end,
    MIN(temperature_celsius) as min_temp,
    MAX(temperature_celsius) as max_temp,
    AVG(temperature_celsius) as avg_temp,
    COUNT(CASE WHEN temperature_celsius > 4.0 THEN 1 END) as excursion_count,
    CASE 
        WHEN MAX(temperature_celsius) <= 4.0 THEN 'PASS'
        WHEN MAX(temperature_celsius) <= 6.0 THEN 'CONDITIONAL'
        ELSE 'FAIL'
    END as cold_chain_status
FROM cold_chain.temperature_readings
WHERE product_batch_id IS NOT NULL
  AND timestamp >= CURRENT_DATE - INTERVAL '30 days'
GROUP BY product_batch_id
ORDER BY journey_start DESC;
```

### 13.3 Temperature Log Report Template

```
┌─────────────────────────────────────────────────────────────────────┐
│           DAILY TEMPERATURE LOG REPORT                               │
│              Smart Dairy Ltd.                                        │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  Report Date: _______________  Generated: _______________            │
│  Facility: ___________________  Shift: _________________             │
│                                                                      │
│  SUMMARY                                                             │
│  ───────                                                             │
│  Total Locations Monitored: ___                                      │
│  Total Readings Recorded: ___                                        │
│  Temperature Excursions: ___                                         │
│  Overall Compliance: ___%                                            │
│                                                                      │
│  LOCATION DETAILS                                                    │
│  ────────────────                                                    │
│  ┌───────────────┬──────────┬──────────┬──────────┬──────────────┐  │
│  │ Location      │ Min (°C) │ Max (°C) │ Avg (°C) │ Status       │  │
│  ├───────────────┼──────────┼──────────┼──────────┼──────────────┤  │
│  │ Raw Tank A1   │  2.5     │  3.8     │  3.2     │ ✅ COMPLIANT │  │
│  │ Raw Tank A2   │  2.3     │  3.9     │  3.1     │ ✅ COMPLIANT │  │
│  │ Pasteurizer   │  0.5     │  3.5     │  2.8     │ ✅ COMPLIANT │  │
│  │ Cold Store B  │  4.1     │  4.5     │  4.3     │ ⚠️ DEVIATION│  │
│  │ Van V001      │  2.8     │  4.2     │  3.5     │ ✅ COMPLIANT │  │
│  └───────────────┴──────────┴──────────┴──────────┴──────────────┘  │
│                                                                      │
│  DEVIATIONS & CORRECTIVE ACTIONS                                     │
│  ─────────────────────────────────                                   │
│  Location: Cold Store B                                              │
│  Issue: Temperature exceeded 4.0°C between 14:30-14:45               │
│  Cause: Door left open during loading                                │
│  Action: Staff retrained on door procedures                          │
│  Product Impact: None - temperature < 4.5°C, duration < 15 min      │
│                                                                      │
│  Prepared By: _________________  Date: _______________               │
│  Reviewed By: _________________  Date: _______________               │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 14. APPENDICES

### Appendix A: Temperature Sensor Placement Map

```
┌─────────────────────────────────────────────────────────────────────┐
│              FACILITY SENSOR PLACEMENT MAP                           │
│                   Smart Dairy Processing Plant                       │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│                    ┌─────────────────────┐                          │
│                    │   OFFICE BUILDING   │                          │
│                    │                     │                          │
│                    └─────────────────────┘                          │
│                           │                                         │
│    ┌──────────────────────┼──────────────────────┐                 │
│    │                      │                      │                 │
│    ▼                      ▼                      ▼                 │
│ ┌─────────┐         ┌─────────────┐        ┌─────────────┐        │
│ │  RAW    │         │  PROCESSING │        │  FINISHED   │        │
│ │ RECEIPT │────────▶│   HALL      │───────▶│  GOODS      │        │
│ │         │         │             │        │  STORE      │        │
│ │ ○ R1    │         │ ○ P1  ○ P2  │        │ ○ F1  ○ F2  │        │
│ │ ○ R2    │         │ ○ P3  ○ P4  │        │ ○ F3  ○ F4  │        │
│ └─────────┘         └─────────────┘        │ ○ F5  ○ F6  │        │
│      │                                     └─────────────┘        │
│      │                                           │                │
│      ▼                                           ▼                │
│ ┌─────────┐                                ┌─────────────┐        │
│ │BULK COOL│                                │LOADING DOCK │        │
│ │  TANKS  │                                │             │        │
│ │ ○ T1-T4 │                                │ ○ L1  ○ L2  │        │
│ │ ○ T5-T8 │                                │ ○ L3  ○ L4  │        │
│ └─────────┘                                └─────────────┘        │
│                                                                      │
│  LEGEND:                                                             │
│  ○ Temperature Sensor    📡 IoT Gateway    🚛 Vehicle Dock           │
│                                                                      │
│  SENSOR COUNT BY ZONE:                                               │
│  ┌────────────────┬──────────┐                                       │
│  │ Zone           │ Sensors  │                                       │
│  ├────────────────┼──────────┤                                       │
│  │ Raw Receipt    │ 4        │                                       │
│  │ Bulk Cool Tanks│ 24 (3×8) │                                       │
│  │ Processing     │ 8        │                                       │
│  │ Finished Store │ 12       │                                       │
│  │ Loading Dock   │ 8        │                                       │
│  │ Transport      │ 20       │                                       │
│  │ Retail         │ 30       │                                       │
│  ├────────────────┼──────────┤                                       │
│  │ TOTAL          │ 106      │                                       │
│  └────────────────┴──────────┘                                       │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

### Appendix B: MQTT Topic Structure for Cold Chain

```
smartdairy/
├── coldchain/
│   ├── farm/
│   │   ├── {farm_id}/
│   │   │   ├── bulktank/{tank_id}/
│   │   │   │   ├── temperature       # Current temperature reading
│   │   │   │   ├── status            # Device health status
│   │   │   │   ├── config            # Configuration commands
│   │   │   │   └── alarm             # Active alarms
│   │   │   └── precooler/{unit_id}/
│   │   │       ├── temperature
│   │   │       └── status
│   │   └── summary                   # Farm-level aggregate data
│   │
│   ├── plant/
│   │   ├── {plant_id}/
│   │   │   ├── silo/{silo_id}/
│   │   │   │   ├── temperature
│   │   │   │   ├── level
│   │   │   │   └── status
│   │   │   ├── coldroom/{room_id}/
│   │   │   │   ├── temperature
│   │   │   │   ├── humidity
│   │   │   │   ├── door
│   │   │   │   └── compressor
│   │   │   ├── pasteurizer/{unit_id}/
│   │   │   │   ├── inlet_temp
│   │   │   │   ├── outlet_temp
│   │   │   │   └── flow_rate
│   │   │   └── blastfreezer/{unit_id}/
│   │   │       └── temperature
│   │   └── summary
│   │
│   ├── transport/
│   │   ├── {vehicle_id}/
│   │   │   ├── zone/{zone_id}/
│   │   │   │   ├── temperature
│   │   │   │   ├── humidity
│   │   │   │   └── door
│   │   │   ├── gps/
│   │   │   │   ├── location        # Lat/Long coordinates
│   │   │   │   └── speed
│   │   │   ├── reefer/
│   │   │   │   ├── status
│   │   │   │   ├── fuel_level
│   │   │   │   └── runtime
│   │   │   └── journey/{journey_id}/
│   │   │       └── events
│   │   └── fleet/summary
│   │
│   ├── distribution/
│   │   ├── {dc_id}/
│   │   │   ├── coldroom/{room_id}/
│   │   │   │   ├── temperature
│   │   │   │   ├── humidity
│   │   │   │   └── door
│   │   │   ├── staging/{area_id}/
│   │   │   │   └── temperature
│   │   │   └── dispatch/{bay_id}/
│   │   │       └── temperature
│   │   └── summary
│   │
│   ├── retail/
│   │   ├── {store_id}/
│   │   │   ├── cabinet/{unit_id}/
│   │   │   │   ├── temperature
│   │   │   │   ├── compressor
│   │   │   │   └── door
│   │   │   └── walkin/{room_id}/
│   │   │       ├── temperature
│   │   │       └── door
│   │   └── summary
│   │
│   ├── alerts/
│   │   ├── critical/{location_type}/{location_id}
│   │   ├── warning/{location_type}/{location_id}
│   │   └── info/{location_type}/{location_id}
│   │
│   ├── calibration/
│   │   ├── due
│   │   ├── overdue
│   │   └── completed
│   │
│   └── reports/
│       ├── daily/{date}
│       ├── weekly/{week}
│       └── monthly/{month}
```

### Appendix C: Alert Escalation Procedure (Detailed)

```
┌─────────────────────────────────────────────────────────────────────┐
│           DETAILED ALERT ESCALATION MATRIX                           │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  ALERT TYPE: CRITICAL (Temperature > 4°C for > 30 minutes)          │
│  ═══════════════════════════════════════════════════════════        │
│                                                                      │
│  T+0:00  ──▶ System detects threshold breach                        │
│              Dashboard alert appears (RED)                           │
│                                                                      │
│  T+0:01  ──▶ Mobile push notification to on-duty operator           │
│              SMS sent to operator                                    │
│              Email to shift supervisor                               │
│                                                                      │
│  T+0:05  ──▶ [If not acknowledged]                                  │
│              Automated voice call to operator                        │
│              SMS to shift supervisor                                 │
│                                                                      │
│  T+0:10  ──▶ [If not acknowledged]                                  │
│              Voice call to shift supervisor                          │
│              SMS to operations manager                               │
│                                                                      │
│  T+0:20  ──▶ [If still critical]                                    │
│              Voice call to operations manager                        │
│              SMS to quality manager                                  │
│              Product quarantine protocol initiated                   │
│                                                                      │
│  T+0:30  ──▶ [If still critical]                                    │
│              Voice call to quality manager                           │
│              Voice call to plant manager                             │
│              Emergency response team activated                       │
│                                                                      │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │  ESCALATION CONTACTS                                         │   │
│  ├─────────────┬─────────────────────┬──────────────────────────┤   │
│  │ Level       │ Role                │ Contact                  │   │
│  ├─────────────┼─────────────────────┼──────────────────────────┤   │
│  │ L1          │ On-duty Operator    │ +880-1XXX-XXXXXX         │   │
│  │ L2          │ Shift Supervisor    │ +880-1XXX-XXXXXX         │   │
│  │ L3          │ Operations Manager  │ +880-1XXX-XXXXXX         │   │
│  │ L4          │ Quality Manager     │ +880-1XXX-XXXXXX         │   │
│  │ L5          │ Plant Manager       │ +880-1XXX-XXXXXX         │   │
│  └─────────────┴─────────────────────┴──────────────────────────┘   │
│                                                                      │
│  ALERT TYPE: WARNING (Temperature > 3°C and rising)                 │
│  ════════════════════════════════════════════════════               │
│                                                                      │
│  T+0:00  ──▶ Dashboard alert (YELLOW)                               │
│              Mobile push to operator                                │
│                                                                      │
│  T+0:05  ──▶ [If trending continues]                                │
│              SMS to operator                                        │
│                                                                      │
│  T+0:15  ──▶ [If threshold reaches critical]                        │
│              Escalate to CRITICAL procedure                         │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

### Appendix D: HACCP Documentation Templates

**Template D1: CCP Monitoring Log**

```
┌─────────────────────────────────────────────────────────────────────┐
│  CCP MONITORING LOG                                                  │
│  CCP Reference: CCP-4 (Cold Storage)                                 │
│  Critical Limit: 0-4°C                                               │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  Date: _____________  Shift: _____________                           │
│  Location: ___________  Product: ____________                        │
│  Operator: ___________  Supervisor: __________                       │
│                                                                      │
│  ┌────────┬───────────┬────────┬───────────┬────────┐               │
│  │ Time   │ Temp (°C) │ OK/Y/N │ Corrective│ Initial│               │
│  ├────────┼───────────┼────────┼───────────┼────────┤               │
│  │ 06:00  │           │        │           │        │               │
│  │ 08:00  │           │        │           │        │               │
│  │ 10:00  │           │        │           │        │               │
│  │ 12:00  │           │        │           │        │               │
│  │ 14:00  │           │        │           │        │               │
│  │ 16:00  │           │        │           │        │               │
│  │ 18:00  │           │        │           │        │               │
│  │ 20:00  │           │        │           │        │               │
│  │ 22:00  │           │        │           │        │               │
│  │ 24:00  │           │        │           │        │               │
│  └────────┴───────────┴────────┴───────────┴────────┘               │
│                                                                      │
│  VERIFICATION:                                                       │
│  □ Calibration check completed: ___________                          │
│  □ Reviewed by Supervisor: ______________ Date: ___________          │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

**Template D2: Corrective Action Record**

```
┌─────────────────────────────────────────────────────────────────────┐
│  CORRECTIVE ACTION RECORD                                            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  CAR Number: _____________  Date: _____________                      │
│  Related CCP: _____________  Deviation Date/Time: ___________        │
│                                                                      │
│  DEVIATION DESCRIPTION:                                              │
│  _________________________________________________________________  │
│  _________________________________________________________________  │
│                                                                      │
│  ROOT CAUSE:                                                         │
│  _________________________________________________________________  │
│                                                                      │
│  IMMEDIATE ACTION TAKEN:                                             │
│  _________________________________________________________________  │
│  _________________________________________________________________  │
│                                                                      │
│  PRODUCT DISPOSITION:                                                │
│  □ Released    □ Reprocessed    □ Rejected    □ Under Review        │
│  Product Details: ________________________________________________   │
│                                                                      │
│  PREVENTIVE ACTION:                                                  │
│  _________________________________________________________________  │
│  Target Completion: _______________  Completed: _______________      │
│                                                                      │
│  VERIFICATION OF EFFECTIVENESS:                                      │
│  _________________________________________________________________  │
│                                                                      │
│  Reported By: _________________  Date: _____________                 │
│  Reviewed By: _________________  Date: _____________                 │
│  Approved By: _________________  Date: _____________                 │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

### Appendix E: Wiring Diagrams

```
┌─────────────────────────────────────────────────────────────────────┐
│         TEMPERATURE SENSOR WIRING DIAGRAM                            │
│                 4-Wire RTD Configuration                             │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  SENSOR (Pt100)                           CONTROLLER/TRANSMITTER     │
│  ┌───────────────┐                        ┌─────────────────────┐   │
│  │               │    Shielded Cable      │                     │   │
│  │   ┌─────┐     │   ┌───────────────┐    │  ┌─────────────┐   │   │
│  │   │ Pt  │     │   │   Red (R+)    │────┼─▶│  Current    │   │   │
│  │   │ 100 │◄────┼───┤   White (R-)  │────┼─▶│  Source     │   │   │
│  │   └─────┘     │   ├───────────────┤    │  │  (I+)       │   │   │
│  │               │   │   Red (S+)    │────┼─▶│             │   │   │
│  │   ┌─────┐     │   │   White (S-)  │────┼─▶│  Sense      │   │   │
│  │   │     │     │   └───────────────┘    │  │  Inputs     │   │   │
│  │   │shield│────┼─────────────────────────┼─▶│  (S+,S-)    │   │   │
│  │   └─────┘     │                        │  └─────────────┘   │   │
│  │               │                        │                     │   │
│  └───────────────┘                        │  ┌─────────────┐   │   │
│                                           │  │  4-20mA     │   │   │
│                                           │  │  Output     │───┼───┼──▶ PLC/IoT
│                                           │  │  (Loop +)   │   │   │
│                                           │  │  (Loop -)   │   │   │
│                                           │  └─────────────┘   │   │
│                                           │                     │   │
│                                           └─────────────────────┘   │
│                                                                      │
│  WIRING SPECIFICATIONS:                                              │
│  ──────────────────────                                              │
│  Cable: 4-core + shield, 0.5mm², PTFE insulated                     │
│  Max Distance: 100m (use transmitter for longer runs)               │
│  Shield: Grounded at controller end only                            │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

### Appendix F: Sensor Specification Sheets

| Parameter | Specification |
|-----------|--------------|
| **Model** | Pt100 Class A RTD |
| **Sensor Type** | Thin Film Platinum |
| **Temperature Range** | -50°C to +200°C |
| **Accuracy** | ±0.15°C (at 0°C) |
| **Response Time** | t₉₀ < 10 seconds (in water) |
| **Probe Diameter** | 6mm |
| **Probe Length** | 150mm (customizable) |
| **Connection** | 4-wire, flying leads |
| **Ingress Protection** | IP67 |
| **Cable Length** | 3m standard |
| **Calibration** | NIST traceable certificate included |

### Appendix G: Forms

**Form G1: Sensor Installation Checklist**

```
SENSOR INSTALLATION CHECKLIST
═════════════════════════════

□ Pre-Installation
  □ Sensor inspected for physical damage
  □ Calibration certificate verified
  □ Location approved by QA
  □ Mounting hardware available
  □ Cable route planned

□ Installation
  □ Sensor mounted at correct depth/position
  □ Cable secured with strain relief
  □ Connection tight and insulated
  □ Shield grounded correctly
  □ Sensor ID label applied

□ Post-Installation
  □ Communication verified
  □ Reading compared to reference thermometer
  □ Deviation within ±0.3°C
  □ Reading appears on dashboard
  □ Alert thresholds configured

□ Documentation
  □ Installation date recorded
  □ Installer name recorded
  □ Location ID assigned
  □ Asset register updated

Installed By: _________________  Date: _______________
Verified By: _________________  Date: _______________
```

**Form G2: Daily System Check**

```
DAILY COLD CHAIN SYSTEM CHECK
═════════════════════════════

Date: _______________  Operator: _______________
Shift: ______________  Facility: _______________

SYSTEM STATUS:
□ All sensors showing online
□ No critical alerts active
□ Dashboard accessible
□ Mobile alerts functioning
□ Backup power tested

TEMPERATURE VERIFICATION (Spot Check):
Location          │ Sensor │ Display │ Actual │ OK?
──────────────────┼────────┼─────────┼────────┼─────
Raw Tank 1        │ T1     │         │        │ □
Cold Room A       │ R1     │         │        │ □
Pasteurizer Out   │ P2     │         │        │ □

ALERT TEST:
□ Test alert triggered
□ SMS received
□ Push notification received
□ Alert acknowledged

ISSUES NOTED:
_______________________________________________
_______________________________________________

Operator Signature: _______________
Supervisor Review: _______________
```

---

**END OF COLD CHAIN MONITORING GUIDE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | IoT Engineer | Initial version |

---

*Document Control: This document is owned by the Quality Manager. Review annually or when significant system changes occur.*
