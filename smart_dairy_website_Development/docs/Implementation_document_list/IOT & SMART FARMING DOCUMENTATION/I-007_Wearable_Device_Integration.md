# I-007: Wearable Device Integration

## Smart Dairy Ltd. - Smart Web Portal System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | I-007 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | IoT Engineer |
| **Owner** | IoT Architect |
| **Reviewer** | Veterinarian |
| **Status** | Draft |
| **Classification** | Internal |

### Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | 2026-01-31 | IoT Engineer | Initial document creation |

### Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| IoT Architect | [Name] | _____________ | _______ |
| Veterinarian | [Name] | _____________ | _______ |
| Farm Manager | [Name] | _____________ | _______ |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Device Types](#2-device-types)
3. [Device Specifications](#3-device-specifications)
4. [Attachment Methods](#4-attachment-methods)
5. [Data Collection](#5-data-collection)
6. [Communication](#6-communication)
7. [Data Processing](#7-data-processing)
8. [Health Alerts](#8-health-alerts)
9. [Reproduction Management](#9-reproduction-management)
10. [Integration with Herd Management](#10-integration-with-herd-management)
11. [Battery Management](#11-battery-management)
12. [Troubleshooting](#12-troubleshooting)
13. [Appendices](#13-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides comprehensive guidelines for integrating wearable devices into the Smart Dairy Ltd. livestock management system. It covers device selection, deployment, data collection, processing, and integration with the Smart Web Portal.

### 1.2 Scope

This document applies to all wearable technology deployed for cattle monitoring across Smart Dairy Ltd. farms, including activity monitors, health sensors, GPS trackers, and reproductive management devices.

### 1.3 Wearable Technology in Livestock Monitoring

Wearable devices have revolutionized dairy farm management by providing continuous, real-time monitoring of individual animal health, behavior, and reproductive status. These devices enable:

- **Proactive Health Management**: Early detection of diseases before clinical symptoms appear
- **Optimized Reproduction**: Accurate heat detection and breeding timing
- **Welfare Monitoring**: Continuous assessment of animal well-being
- **Production Optimization**: Data-driven decisions for feeding and management
- **Labor Efficiency**: Automated monitoring reducing manual observation time

### 1.4 System Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         WEARABLE DEVICE ECOSYSTEM                           │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│   ┌──────────────┐    ┌──────────────┐    ┌──────────────┐    ┌──────────┐ │
│   │   Activity   │    │    Health    │    │     GPS      │    │   Heat   │ │
│   │   Collars    │    │   Monitors   │    │   Trackers   │    │ Detection│ │
│   └──────┬───────┘    └──────┬───────┘    └──────┬───────┘    └────┬─────┘ │
│          │                   │                   │                 │       │
│          └───────────────────┴───────────────────┴─────────────────┘       │
│                              │                                              │
│                    ┌─────────▼─────────┐                                    │
│                    │  Gateway/Hub      │                                    │
│                    │  (LoRaWAN/Cellular│                                    │
│                    │   /Bluetooth)     │                                    │
│                    └─────────┬─────────┘                                    │
│                              │                                              │
│                    ┌─────────▼─────────┐                                    │
│                    │  Smart Web Portal │                                    │
│                    │   (Cloud/Edge)    │                                    │
│                    └───────────────────┘                                    │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 1.5 Objectives

- Achieve 95%+ accuracy in heat detection
- Reduce disease detection time by 50%
- Enable 24/7 automated monitoring
- Integrate seamlessly with existing herd management systems
- Provide real-time alerts to farm staff via mobile applications

---

## 2. Device Types

### 2.1 Activity Collars

Activity collars monitor cow movement, rumination, and behavioral patterns to assess health and reproductive status.

#### 2.1.1 Features

| Feature | Description | Purpose |
|---------|-------------|---------|
| Accelerometer | 3-axis motion sensing | Activity tracking, lameness detection |
| Gyroscope | Orientation detection | Lying/standing behavior |
| Magnetometer | Directional sensing | Movement patterns |
| Microphone | Acoustic sensing | Rumination monitoring |
| Temperature Sensor | Surface body temperature | Health monitoring |

#### 2.1.2 Monitored Behaviors

```
┌─────────────────────────────────────────────────────────────────┐
│                    ACTIVITY MONITORING                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐             │
│  │   Eating    │  │  Ruminating │  │   Walking   │             │
│  │  (Feeding)  │  │  (Chewing)  │  │  (Steps)    │             │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘             │
│         │                │                │                    │
│         ▼                ▼                ▼                    │
│  ┌──────────────────────────────────────────────────┐         │
│  │              BEHAVIORAL ANALYSIS                  │         │
│  │  • Feeding time per day: 3-5 hours               │         │
│  │  • Rumination time: 450-550 min/day              │         │
│  │  • Steps per day: 2,000-4,000                    │         │
│  │  • Lying time: 10-14 hours/day                   │         │
│  └──────────────────────────────────────────────────┘         │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

#### 2.1.3 Recommended Devices

| Device | Manufacturer | Key Features | Price Range |
|--------|--------------|--------------|-------------|
| CowManager SensOor | Agis Automatisering | Ear tag, 24/7 monitoring | $$$ |
| HR Tag | SCR/Allflex | Neck collar, rumination | $$ |
| MooMonitor+ | Dairymaster | Neck collar, comprehensive | $$$ |
| CowView | GEA Farm Technologies | Neck/leg options | $$$ |

### 2.2 Health Monitors

Health monitoring devices track vital signs and physiological parameters to detect illness early.

#### 2.2.1 Monitored Parameters

| Parameter | Normal Range | Alert Threshold | Frequency |
|-----------|--------------|-----------------|-----------|
| Body Temperature | 38.0-39.2°C | >39.5°C or <37.5°C | Continuous |
| Heart Rate | 50-80 bpm | >100 bpm or <40 bpm | Continuous |
| Respiratory Rate | 18-28/min | >35/min | Hourly |
| Activity Level | Baseline ±20% | <50% or >150% of baseline | Continuous |

#### 2.2.2 Health Monitor Types

```
┌─────────────────────────────────────────────────────────────────┐
│                   HEALTH MONITORING DEVICES                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐ │
│  │   TEMPERATURE   │  │    HEART RATE   │  │   MULTI-PARAM   │ │
│  │    MONITORS     │  │    MONITORS     │  │    MONITORS     │ │
│  ├─────────────────┤  ├─────────────────┤  ├─────────────────┤ │
│  │ • Reticular     │  │ • Ear tag PPG   │  │ • Temperature   │ │
│  │   bolus         │  │ • Neck collar   │  │ • Activity      │ │
│  │ • Skin patch    │  │   electrodes    │  │ • Rumination    │ │
│  │ • Subcutaneous  │  │ • Ingestible    │  │ • Pulse         │ │
│  │   implant       │  │   pills         │  │ • Location      │ │
│  └─────────────────┘  └─────────────────┘  └─────────────────┘ │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

#### 2.2.3 Reticular Temperature Bolus

A specialized device swallowed by the cow that stays in the rumen, providing core body temperature monitoring.

**Specifications:**
- Temperature Range: 35-45°C
- Accuracy: ±0.1°C
- Battery Life: 3-5 years
- Transmission: Every 15 minutes
- Retention: 99%+ (remains in reticulum)

### 2.3 GPS Trackers

GPS tracking devices monitor animal location, movement patterns, and grazing behavior.

#### 2.3.1 Applications

| Application | Data Collected | Business Value |
|-------------|----------------|----------------|
| Grazing Patterns | Location history, pasture utilization | Optimize pasture rotation |
| Heat Detection | Mounting behavior detection | Accurate breeding timing |
| Calving Alerts | Isolation behavior, movement changes | Timely calving assistance |
| Theft Prevention | Real-time location, geofencing | Asset protection |
| Health Monitoring | Reduced movement indicators | Early illness detection |

#### 2.3.2 GPS Device Specifications

```
┌─────────────────────────────────────────────────────────────────┐
│                     GPS TRACKER SPECIFICATIONS                  │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Position Accuracy:  2-5 meters (GPS)                           │
│                      10-50 meters (LoRa geolocation)            │
│                                                                 │
│  Update Frequency:   5-15 minutes (standard)                    │
│                      1 minute (calving/heat mode)               │
│                                                                 │
│  Battery Life:       1-3 years (depends on update frequency)    │
│                                                                 │
│  Attachment:         Neck collar / Ear tag                      │
│                                                                 │
│  Communication:      Cellular / LoRaWAN / Satellite             │
│                                                                 │
│  Environmental:      IP67 (waterproof)                          │
│                      -20°C to +60°C operating range             │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

#### 2.3.3 Grazing Pattern Analysis

```python
# GPS Data Processing for Grazing Optimization
{
    "cow_id": "SD-2024-0842",
    "date": "2026-01-31",
    "pasture_zone": "North_Paddock_A",
    "grazing_metrics": {
        "total_distance_m": 4520,
        "grazing_time_min": 420,
        "walking_time_min": 180,
        "resting_time_min": 840,
        "area_covered_m2": 8500,
        "utilization_intensity": "medium",
        "preferred_zones": ["Zone_3", "Zone_5", "Zone_7"]
    },
    "recommendations": {
        "rotation_trigger": "Rotate in 2 days",
        "underutilized_areas": ["Zone_1", "Zone_9"],
        "overgrazing_risk": ["Zone_3"]
    }
}
```

### 2.4 Heat Detection Devices

Specialized devices optimized for detecting estrus (heat) in dairy cattle.

#### 2.4.1 Heat Detection Methods

| Method | Technology | Accuracy | Best For |
|--------|-----------|----------|----------|
| Activity Monitoring | Accelerometer | 85-90% | Primary indicator |
| Mounting Detection | Pressure sensor | 95%+ | Secondary confirmation |
| Chin Ball Marker | Paint/markers | 70-80% | Visual backup |
| Temperature Rise | Thermal sensor | 75-85% | Supporting data |
| Vocalization Analysis | Microphone + ML | 80-85% | Behavioral indicator |

#### 2.4.2 Heat Detection Algorithm

```
┌─────────────────────────────────────────────────────────────────┐
│                 HEAT DETECTION DECISION TREE                    │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Start ──► Activity Spike? ──► Yes ──► Check Time Since Last   │
│              (2x baseline)             Heat > 18 days?          │
│                │                                                   │
│               No                                                 │
│                │                                                   │
│                ▼                                                   │
│  ◄───────── Normal Status                                       │
│                                                                   │
│  Yes ◄─── Yes ◄─── Check Mounting Behavior? ◄─── Yes            │
│   │                                        │                      │
│   │                                       No                      │
│   │                                        │                      │
│   │                                        ▼                      │
│   │                              Check Temperature Rise?          │
│   │                                        │                      │
│   │                                       No ──► Monitor          │
│   │                                        │                      │
│   │                                       Yes                     │
│   │                                        │                      │
│   └────────────────────────────────────────┘                      │
│                                            ▼                      │
│                                   HIGH CONFIDENCE HEAT            │
│                                   Trigger Alert                   │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

#### 2.4.3 Heat Alert Priority Levels

| Level | Confidence | Criteria | Action |
|-------|------------|----------|--------|
| HIGH | >90% | Activity + Mounting + Temp | Immediate AI notification |
| MEDIUM | 75-90% | Activity + One secondary | Notify within 2 hours |
| LOW | 60-75% | Activity spike only | Monitor closely |

---

## 3. Device Specifications

### 3.1 Battery Life Requirements

| Device Type | Minimum Life | Target Life | Charging Method |
|-------------|--------------|-------------|-----------------|
| Activity Collar | 2 years | 5 years | Replaceable battery |
| Health Monitor | 3 years | 5 years | Non-replaceable (disposable) |
| GPS Tracker | 1 year | 2 years | Solar assist / Replaceable |
| Heat Detector | 2 years | 3 years | Replaceable battery |

### 3.2 Durability Standards

```
┌─────────────────────────────────────────────────────────────────┐
│                    DURABILITY REQUIREMENTS                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  MECHANICAL STRESS:                                             │
│  • Impact resistance: 100G shock survival                       │
│  • Vibration: Cattle movement simulation tested                 │
│  • Tensile strength: 200kg pull force (collars)                 │
│                                                                 │
│  ENVIRONMENTAL RESISTANCE:                                      │
│  • Temperature range: -20°C to +60°C                            │
│  • Humidity: 0-100% RH non-condensing                           │
│  • UV exposure: 5+ year outdoor rating                          │
│  • Chemical resistance: Detergents, manure, feed acids          │
│                                                                 │
│  OPERATIONAL LIFESPAN:                                          │
│  • Minimum: 3 years in field conditions                         │
│  • Target: 5+ years                                             │
│  • Warranty: 2 years standard                                   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 3.3 Water Resistance Ratings

| Rating | Description | Suitable Environment |
|--------|-------------|---------------------|
| IP54 | Dust protected, water splashing | Indoor only |
| IP65 | Dust tight, water jets | Covered areas |
| IP67 | Dust tight, temporary immersion | Standard dairy (RECOMMENDED) |
| IP68 | Dust tight, continuous immersion | Extreme wet conditions |
| IP69K | Dust tight, high-pressure wash | Wash-down areas |

### 3.4 Size and Weight Limits

| Attachment Point | Maximum Weight | Maximum Dimensions |
|------------------|----------------|-------------------|
| Neck Collar | 500g | 80mm x 60mm x 40mm |
| Ear Tag | 50g | 50mm diameter |
| Leg Band | 200g | 60mm x 40mm x 30mm |
| Reticular Bolus | 200g | 35mm x 130mm (cylindrical) |

### 3.5 Certification Requirements

- **FCC/CE**: Radio equipment compliance
- **IP Rating**: Ingress protection (IP67 minimum)
- **Food Safety**: Materials safe for animal contact
- **Biocompatibility**: ISO 10993 for implants/bolus
- **Radio**: Local frequency regulations compliance

---

## 4. Attachment Methods

### 4.1 Neck Collars

#### 4.1.1 Collar Types

| Type | Material | Adjustment | Best For |
|------|----------|------------|----------|
| Nylon Webbing | Nylon/Polyester | Buckle | Calves, heifers |
| Rubberized | Rubber-coated nylon | Clip system | Adult cows |
| Chain/Leather | Metal/Leather | Link adjustment | Bulls, large breeds |
| Quick-Release | Breakaway design | Fixed | Pasture animals |

#### 4.1.2 Fitting Guidelines

```
┌─────────────────────────────────────────────────────────────────┐
│                    COLLAR FITTING GUIDE                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  PROPER FIT:                                                    │
│  ┌─────────────────────────────────────────┐                   │
│  │                                         │                   │
│  │    ╭─────────────────────────────╮      │                   │
│  │   ╱    ┌───────────────────┐     ╲     │                   │
│  │  │    │    DEVICE UNIT    │      │     │                   │
│  │  │    └───────────────────┘      │     │                   │
│  │  │    ═══════════════════════    │     │                   │
│  │   ╲   Collar - 2 fingers fit    ╱      │                   │
│  │    ╰─────────────────────────────╯      │                   │
│  │                                         │                   │
│  │  ► Fit: Allow 2-3 fingers between      │                   │
│  │    collar and neck                      │                   │
│  │  ► Position: High on neck, behind       │                   │
│  │    the ears                             │                   │
│  │  ► Check: Weekly inspection for         │                   │
│  │    wear and fit                         │                   │
│  │                                         │                   │
│  └─────────────────────────────────────────┘                   │
│                                                                 │
│  INCORRECT FIT:                                                 │
│  • Too tight: Skin irritation, breathing restriction            │
│  • Too loose: Catching on objects, inaccurate readings          │
│  • Wrong position: Jaw interference, premature loss             │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 4.2 Ear Tags

#### 4.2.1 Tag Placement

- **Primary Position**: Center of ear, between veins
- **Secondary Position**: Outer third of ear (for secondary tags)
- **Avoid**: Blood vessels, cartilage ridges

#### 4.2.2 Application Procedure

1. Clean ear with antiseptic
2. Position applicator correctly
3. Apply in one quick motion
4. Verify secure attachment
5. Record tag number in database
6. Monitor for 48 hours for infection signs

#### 4.2.3 Tag Specifications

```
┌─────────────────────────────────────────────────────────────────┐
│                     EAR TAG SPECIFICATIONS                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ACTIVE TAGS (with electronics):                                │
│  ┌──────────────────────────────────────────────────────┐      │
│  │                                                      │      │
│  │      ╭────────╮                                      │      │
│  │     ╱          ╲     Weight: 15-30g                  │      │
│  │    │  SENSOR   │     Dimensions: 40-55mm diameter    │      │
│  │    │   UNIT    │     Thickness: 8-15mm               │      │
│  │     ╲          ╱     Attachment: Button + female     │      │
│  │      ╰────┬───╯                                      │      │
│  │           │                                          │      │
│  │      ┌────┴────┐                                     │      │
│  │      │  SHAFT  │  - Piercing shaft with locking pin  │      │
│  │      └─────────┘                                     │      │
│  │                                                      │      │
│  └──────────────────────────────────────────────────────┘      │
│                                                                 │
│  MATERIAL OPTIONS:                                              │
│  • Standard: UV-stabilized polyurethane                         │
│  • Premium: Reinforced composite                                │
│  • Anti-microbial: Silver-ion embedded                          │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 4.3 Leg Bands

#### 4.3.1 Use Cases

- **Gait Analysis**: Lameness detection
- **Heat Detection**: Mounting behavior
- **Location**: Secondary tracking

#### 4.3.2 Fitting Requirements

- Position: Above fetlock, below hock/knee
- Tightness: Snug but allows circulation
- Rotation: Should not spin freely
- Check frequency: Daily during milking

### 4.4 Reticular Bolus

#### 4.4.1 Administration

Administered using a balling gun, the bolus remains in the reticulum for the life of the animal.

**Procedure:**
1. Restrain animal properly
2. Insert balling gun to back of throat
3. Administer in one swift motion
4. Ensure swallowing (watch neck movement)
5. Verify placement via RFID reader (optional)

#### 4.4.2 Retention Rate

| Cattle Type | Retention Rate | Notes |
|-------------|----------------|-------|
| Adult Cows | 99%+ | Excellent retention |
| Heifers (>12mo) | 97% | Monitor first month |
| Young Stock | 90% | May pass through |

---

## 5. Data Collection

### 5.1 Rumination Patterns

#### 5.1.1 Normal Rumination Parameters

| Parameter | Normal Range | Alert Threshold |
|-----------|--------------|-----------------|
| Daily Rumination Time | 450-550 minutes | <350 or >650 minutes |
| Rumination Bouts | 8-12 per day | <6 or >15 bouts |
| Bout Duration | 40-70 minutes | <20 or >90 minutes |
| Chews per Bolus | 50-70 | <40 or >80 |
| Inter-bout Interval | 60-120 minutes | <30 or >180 minutes |

#### 5.1.2 Rumination Data Format

```json
{
  "rumination_event": {
    "cow_id": "SD-2024-0156",
    "device_id": "RUM-7834-A",
    "timestamp_start": "2026-01-31T08:15:00+00:00",
    "timestamp_end": "2026-01-31T08:58:00+00:00",
    "duration_seconds": 2580,
    "chews_detected": 1645,
    "chews_per_minute": 38.3,
    "confidence_score": 0.94,
    "bout_number": 3,
    "location": "Barn_A_Section_3"
  }
}
```

#### 5.1.3 Rumination Monitoring Algorithm

```python
class RuminationMonitor:
    """
    Processes acoustic and accelerometer data to detect rumination.
    """
    
    def __init__(self):
        self.chew_frequency_range = (0.8, 1.5)  # Hz (chews per second)
        self.min_bout_duration = 300  # seconds (5 min)
        self.regularity_threshold = 0.7
    
    def detect_rumination(self, audio_signal, accel_signal):
        """
        Detect rumination from audio and accelerometer data.
        """
        # Extract jaw movement from accelerometer
        jaw_movement = self.extract_jaw_movement(accel_signal)
        
        # Extract chewing sounds from audio
        chew_sounds = self.analyze_acoustic_pattern(audio_signal)
        
        # Cross-correlation for confidence
        correlation = self.cross_correlate(jaw_movement, chew_sounds)
        
        # Frequency analysis
        dominant_freq = self.get_dominant_frequency(chew_sounds)
        
        is_ruminating = (
            correlation > 0.8 and
            self.chew_frequency_range[0] <= dominant_freq <= self.chew_frequency_range[1]
        )
        
        return {
            'is_ruminating': is_ruminating,
            'confidence': correlation,
            'chew_rate': dominant_freq * 60,  # Convert to per minute
            'jaw_amplitude': np.std(jaw_movement)
        }
```

### 5.2 Activity Levels

#### 5.2.1 Activity Metrics

| Metric | Measurement | Normal Range | High Activity |
|--------|-------------|--------------|---------------|
| Steps per Hour | Step count | 100-200 | >300 |
| Activity Index | Scored 0-100 | 20-40 | >70 |
| Lying Time | Minutes/hour | 30-45 | <15 |
| Walking Time | Minutes/hour | 5-15 | >25 |
| Head Position | Up/Down % | 40-60% up | >80% up |

#### 5.2.2 Activity Data Collection

```python
{
  "activity_record": {
    "cow_id": "SD-2024-0234",
    "timestamp": "2026-01-31T14:00:00+00:00",
    "period": "hourly",
    "metrics": {
      "steps": 156,
      "activity_score": 32,
      "lying_minutes": 38,
      "standing_minutes": 12,
      "walking_minutes": 8,
      "eating_minutes": 2,
      "distance_meters": 145
    },
    "baseline_comparison": {
      "steps_vs_baseline": 1.04,
      "activity_vs_baseline": 1.02,
      "deviation_status": "normal"
    },
    "raw_data": {
      "accelerometer_samples": 3600,
      "sampling_rate_hz": 10
    }
  }
}
```

### 5.3 Body Temperature

#### 5.3.1 Temperature Monitoring Points

| Location | Sensor Type | Accuracy | Response Time |
|----------|-------------|----------|---------------|
| Reticulum | Bolus | ±0.1°C | 1-5 minutes |
| Ear Canal | IR/Contact | ±0.2°C | 30 seconds |
| Skin (neck) | Patch | ±0.3°C | 5-10 minutes |
| Vaginal | Probe | ±0.1°C | Immediate |

#### 5.3.2 Temperature Alert Conditions

```
┌─────────────────────────────────────────────────────────────────┐
│                  TEMPERATURE ALERT MATRIX                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  TEMPERATURE (°C)    │  STATUS              │  ACTION           │
│  ─────────────────────────────────────────────────────────────  │
│  < 37.0              │  CRITICAL LOW        │  Immediate vet    │
│  37.0 - 37.5         │  LOW                 │  Monitor closely  │
│  37.5 - 39.2         │  NORMAL              │  Continue normal  │
│  39.2 - 39.5         │  ELEVATED            │  Monitor          │
│  39.5 - 40.0         │  FEVER               │  Check/Notify vet │
│  40.0 - 41.0         │  HIGH FEVER          │  Vet intervention │
│  > 41.0              │  CRITICAL            │  Emergency        │
│                                                                 │
│  NOTE: Normal temperature varies by:                            │
│  • Time of day (0.5-1.0°C higher in evening)                   │
│  • Activity level (exercise increases temp)                    │
│  • Lactation stage (early lactation slightly higher)           │
│  • Environmental temperature                                   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 5.4 Heat Detection Algorithms

#### 5.4.1 Algorithm Overview

```
┌─────────────────────────────────────────────────────────────────┐
│              HEAT DETECTION ALGORITHM FLOW                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  INPUT DATA:                                                    │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐             │
│  │  ACTIVITY   │  │ RUMINATION  │  │ TEMPERATURE │             │
│  │   (+200%)   │  │   (-50%)    │  │  (+0.5°C)   │             │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘             │
│         │                │                │                    │
│         └────────────────┴────────────────┘                    │
│                          │                                      │
│                          ▼                                      │
│              ┌─────────────────────┐                           │
│              │   FEATURE ENGINE    │                           │
│              │  • Activity slope   │                           │
│              │  • Rumination drop  │                           │
│              │  • Temp curve       │                           │
│              │  • Time patterns    │                           │
│              └──────────┬──────────┘                           │
│                         │                                       │
│                         ▼                                       │
│              ┌─────────────────────┐                           │
│              │   ML CLASSIFIER     │                           │
│              │  (Random Forest/XGB)│                           │
│              └──────────┬──────────┘                           │
│                         │                                       │
│                         ▼                                       │
│              ┌─────────────────────┐                           │
│              │   CONFIDENCE SCORE  │                           │
│              │    (0.0 - 1.0)      │                           │
│              └──────────┬──────────┘                           │
│                         │                                       │
│           ┌─────────────┼─────────────┐                        │
│           ▼             ▼             ▼                        │
│      ┌────────┐   ┌────────┐   ┌────────┐                     │
│      │ < 0.75 │   │ 0.75-  │   │ > 0.90 │                     │
│      │        │   │  0.90  │   │        │                     │
│      └───┬────┘   └───┬────┘   └───┬────┘                     │
│          │            │            │                           │
│          ▼            ▼            ▼                           │
│      ┌────────┐  ┌────────┐  ┌────────┐                       │
│      │ MONITOR│  │ MEDIUM │  │  HIGH  │                       │
│      │        │  │ PRIORITY│  │PRIORITY│                       │
│      └────────┘  └────────┘  └────────┘                       │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## 6. Communication

### 6.1 Communication Protocols

#### 6.1.1 Protocol Comparison

| Protocol | Range | Power | Bandwidth | Best Use Case |
|----------|-------|-------|-----------|---------------|
| **Bluetooth LE** | 100m | Low | 1 Mbps | Close-range, mobile connection |
| **LoRaWAN** | 10km | Very Low | 0.3-50 kbps | Wide-area, low data rate |
| **Cellular (4G/5G)** | Unlimited | High | 1-1000 Mbps | High bandwidth, real-time |
| **WiFi** | 100m | Medium | 11-600 Mbps | Indoor, high data throughput |
| **UWB** | 200m | Low | 6.8 Mbps | Precise indoor positioning |

#### 6.1.2 Smart Dairy Communication Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SMART DAIRY COMMUNICATION ARCHITECTURE                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  LAYER 1: WEARABLE DEVICES                                                  │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐                       │
│  │ Collar   │ │ Ear Tag  │ │ Leg Band │ │  Bolus   │                       │
│  │ BLE/LoRa │ │  LoRa    │ │ BLE/LoRa │ │  LoRa    │                       │
│  └────┬─────┘ └────┬─────┘ └────┬─────┘ └────┬─────┘                       │
│       │            │            │            │                              │
│       └────────────┴────────────┴────────────┘                              │
│                    │                                                        │
│  LAYER 2: GATEWAYS                                                          │
│                    ▼                                                        │
│       ┌────────────────────────────────────┐                               │
│       │      BARN GATEWAY (LoRaWAN)        │                               │
│       │  • Collects data from all devices  │                               │
│       │  • Edge processing capability      │                               │
│       │  • Battery backup (8+ hours)       │                               │
│       └───────────────┬────────────────────┘                               │
│                       │                                                     │
│  LAYER 3: CONNECTIVITY                                                      │
│                       ▼                                                     │
│       ┌────────────────────────────────────┐                               │
│       │      FARM NETWORK                  │                               │
│       │  ┌────────┐  ┌────────┐  ┌──────┐ │                               │
│       │  │ Fiber  │  │ 4G/5G  │  │ WiFi │ │                               │
│       │  │Primary │  │Backup  │  │Local │ │                               │
│       │  └────────┘  └────────┘  └──────┘ │                               │
│       └───────────────┬────────────────────┘                               │
│                       │                                                     │
│  LAYER 4: CLOUD PLATFORM                                                    │
│                       ▼                                                     │
│       ┌────────────────────────────────────┐                               │
│       │      SMART WEB PORTAL CLOUD        │                               │
│       │  • Data storage & analytics        │                               │
│       │  • ML model inference              │                               │
│       │  • Alert management                │                               │
│       └───────────────┬────────────────────┘                               │
│                       │                                                     │
│  LAYER 5: USER INTERFACES                                                   │
│           ┌───────────┼───────────┐                                        │
│           ▼           ▼           ▼                                        │
│       ┌───────┐  ┌───────┐  ┌───────┐                                     │
│       │Mobile │  │ Web   │  │Dashboard│                                    │
│       │  App  │  │Portal │  │Display │                                    │
│       └───────┘  └───────┘  └───────┘                                     │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 6.2 Bluetooth LE

#### 6.2.1 BLE Configuration

```yaml
# BLE Device Configuration
ble_configuration:
  advertising:
    interval_ms: 1000
    tx_power: 0dBm
    channels: [37, 38, 39]
  
  connection:
    min_interval_ms: 15
    max_interval_ms: 30
    slave_latency: 4
    supervision_timeout_ms: 6000
  
  services:
    - uuid: "0x180A"  # Device Information
    - uuid: "0x180F"  # Battery Service
    - uuid: "0x181A"  # Environmental Sensing
    - uuid: "0xFFF0"  # Custom Livestock Data
  
  security:
    mode: LE_SECURE_CONNECTIONS
    bonding: true
    mitm_protection: true
```

### 6.3 LoRaWAN

#### 6.3.1 LoRaWAN Configuration

```yaml
# LoRaWAN Network Configuration
lorawan_configuration:
  region: AS923  # Asia 923 MHz
  
  device_profile:
    device_class: A
    spreading_factor: 7-10
    bandwidth_khz: 125
    coding_rate: 4/5
    tx_power_dbm: 14-20
  
  data_rate:
    dr0: {spreading_factor: 12, bandwidth: 125}  # Longest range
    dr1: {spreading_factor: 11, bandwidth: 125}
    dr2: {spreading_factor: 10, bandwidth: 125}
    dr3: {spreading_factor: 9, bandwidth: 125}
    dr4: {spreading_factor: 8, bandwidth: 125}
    dr5: {spreading_factor: 7, bandwidth: 125}   # Fastest
  
  duty_cycle: 1%  # Regulatory compliance
  
  adr:
    enabled: true
    margin_db: 10
```

#### 6.3.2 Data Packet Format

```python
# LoRaWAN Uplink Packet Structure
{
    "packet_header": {
        "device_eui": "A84041C4B183XXXX",
        "application_eui": "70B3D57ED00XXXXX",
        "f_port": 2,
        "f_cnt": 1234,
        "timestamp": "2026-01-31T14:30:00+08:00"
    },
    "payload": {
        # Base64 encoded binary payload (8-51 bytes)
        "data": "A3f5B8wQJKL...",
        "decoded": {
            "cow_id": 842,
            "activity": 145,
            "rumination": 45,
            "temperature": 385,  # 38.5°C (x10)
            "battery": 87,       # 87%
            "flags": 0b00010010  # Status flags
        }
    },
    "metadata": {
        "rssi": -95,
        "snr": 8.5,
        "gateway": "GW-BARN-001",
        "frequency": 923.2
    }
}
```

### 6.4 Cellular

#### 6.4.1 Cellular Configuration

```yaml
# Cellular IoT Configuration
cellular_configuration:
  technology: LTE-M  # or NB-IoT
  
  network:
    apn: "smartdairy.iot"
    band: [1, 3, 5, 8, 28]
    mode: automatic
  
  power_saving:
    psm: true
    edrx: true
    tau_hours: 1  # Tracking Area Update
    active_time_seconds: 10
  
  data_plan:
    monthly_mb: 10
    burst_allowance: true
    priority: low
```

---

## 7. Data Processing

### 7.1 Edge Processing vs Cloud

#### 7.1.1 Processing Distribution

| Function | Edge (Gateway) | Cloud | Reasoning |
|----------|----------------|-------|-----------|
| Raw Data Collection | ✓ | ✗ | Reduce transmission |
| Signal Filtering | ✓ | ✗ | Noise reduction |
| Feature Extraction | ✓ | ✓ | Balance computation |
| ML Inference (Simple) | ✓ | ✗ | Low latency alerts |
| ML Inference (Complex) | ✗ | ✓ | Heavy computation |
| Data Storage | Partial | ✓ | Long-term analytics |
| Alert Generation | ✓ | ✓ | Redundancy |
| Report Generation | ✗ | ✓ | Batch processing |

#### 7.1.2 Edge Processing Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    EDGE PROCESSING PIPELINE                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │                   GATEWAY DEVICE                          │  │
│  │  (Raspberry Pi 4 / Industrial Gateway)                    │  │
│  │                                                           │  │
│  │  ┌──────────────┐    ┌──────────────┐    ┌────────────┐  │  │
│  │  │   INPUT      │───►│  PROCESSING  │───►│   OUTPUT   │  │  │
│  │  │   LAYER      │    │    LAYER     │    │   LAYER    │  │  │
│  │  └──────────────┘    └──────────────┘    └────────────┘  │  │
│  │                                                           │  │
│  │  • LoRaWAN Receiver    • Signal Processing    • Alerts   │  │
│  │  • BLE Central         • Feature Extraction   • Cloud    │  │
│  │  • Message Queue       • Local ML Models      • Display  │  │
│  │                        • Data Aggregation     • Cache    │  │
│  │                                                           │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                 │
│  PROCESSING STAGES:                                             │
│  1. Data Ingestion → 2. Validation → 3. Filtering              │
│  4. Feature Extraction → 5. Local ML → 6. Alert Decision       │
│  7. Cloud Sync → 8. Local Storage                               │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 7.2 Machine Learning Models

#### 7.2.1 Heat Detection ML Model

```python
#!/usr/bin/env python3
"""
Heat Detection Machine Learning Model
Smart Dairy Ltd. - Wearable Device Integration

This model uses Random Forest classification to detect estrus (heat)
in dairy cattle based on multiple sensor inputs.
"""

import numpy as np
import pandas as pd
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split, cross_val_score
from sklearn.preprocessing import StandardScaler
from sklearn.metrics import classification_report, confusion_matrix
import joblib
from datetime import datetime, timedelta
import warnings
warnings.filterwarnings('ignore')


class HeatDetectionModel:
    """
    Machine Learning model for detecting heat in dairy cattle.
    
    Features used:
    - Activity level (normalized to baseline)
    - Rumination time change (%)
    - Body temperature elevation
    - Time since last heat (days)
    - Lactation stage
    - Mounting behavior indicators
    """
    
    def __init__(self, model_path=None):
        self.model = None
        self.scaler = StandardScaler()
        self.feature_names = [
            'activity_ratio',
            'rumination_change_pct',
            'temp_elevation',
            'days_since_last_heat',
            'lactation_stage',
            'mounting_detected',
            'time_of_day',
            'activity_slope_3h',
            'rumination_slope_3h'
        ]
        
        if model_path:
            self.load_model(model_path)
        else:
            self.model = RandomForestClassifier(
                n_estimators=200,
                max_depth=15,
                min_samples_split=5,
                min_samples_leaf=2,
                class_weight='balanced',
                random_state=42
            )
    
    def extract_features(self, cow_data):
        """
        Extract features from raw sensor data.
        
        Args:
            cow_data: Dictionary containing sensor readings
            
        Returns:
            numpy array of features
        """
        features = []
        
        # Feature 1: Activity ratio to baseline (normalized)
        activity_baseline = cow_data.get('activity_baseline', 100)
        current_activity = cow_data.get('current_activity', 100)
        activity_ratio = current_activity / activity_baseline if activity_baseline > 0 else 1.0
        features.append(activity_ratio)
        
        # Feature 2: Rumination change percentage
        rumination_baseline = cow_data.get('rumination_baseline_min', 500)
        current_rumination = cow_data.get('current_rumination_min', 500)
        rumination_change = ((current_rumination - rumination_baseline) / 
                            rumination_baseline * 100)
        features.append(rumination_change)
        
        # Feature 3: Temperature elevation
        current_temp = cow_data.get('body_temperature_c', 38.5)
        normal_temp = cow_data.get('normal_temp_c', 38.5)
        temp_elevation = current_temp - normal_temp
        features.append(temp_elevation)
        
        # Feature 4: Days since last heat
        last_heat = cow_data.get('last_heat_date')
        if last_heat:
            if isinstance(last_heat, str):
                last_heat = datetime.fromisoformat(last_heat)
            days_since = (datetime.now() - last_heat).days
        else:
            days_since = 999  # Unknown
        features.append(days_since)
        
        # Feature 5: Lactation stage (0-3)
        # 0: Dry, 1: Early lactation (0-60d), 2: Mid lactation, 3: Late lactation
        dim = cow_data.get('days_in_milk', 0)
        if dim == 0:
            lactation_stage = 0
        elif dim <= 60:
            lactation_stage = 1
        elif dim <= 200:
            lactation_stage = 2
        else:
            lactation_stage = 3
        features.append(lactation_stage)
        
        # Feature 6: Mounting behavior detected (binary)
        mounting = 1 if cow_data.get('mounting_events', 0) > 0 else 0
        features.append(mounting)
        
        # Feature 7: Time of day (encoded as sin/cos for cyclical nature)
        hour = cow_data.get('hour_of_day', 12)
        time_encoded = np.sin(2 * np.pi * hour / 24)
        features.append(time_encoded)
        
        # Feature 8: Activity slope (last 3 hours trend)
        activity_history = cow_data.get('activity_history', [100, 100, 100])
        if len(activity_history) >= 3:
            activity_slope = (activity_history[-1] - activity_history[0]) / 3
        else:
            activity_slope = 0
        features.append(activity_slope)
        
        # Feature 9: Rumination slope (last 3 hours trend)
        rumination_history = cow_data.get('rumination_history', [500, 500, 500])
        if len(rumination_history) >= 3:
            rumination_slope = (rumination_history[-1] - rumination_history[0]) / 3
        else:
            rumination_slope = 0
        features.append(rumination_slope)
        
        return np.array(features).reshape(1, -1)
    
    def train(self, X, y):
        """
        Train the heat detection model.
        
        Args:
            X: Feature matrix (n_samples, n_features)
            y: Target labels (0: Not in heat, 1: In heat)
        """
        # Scale features
        X_scaled = self.scaler.fit_transform(X)
        
        # Split data
        X_train, X_test, y_train, y_test = train_test_split(
            X_scaled, y, test_size=0.2, random_state=42, stratify=y
        )
        
        # Train model
        self.model.fit(X_train, y_train)
        
        # Evaluate
        train_score = self.model.score(X_train, y_train)
        test_score = self.model.score(X_test, y_test)
        cv_scores = cross_val_score(self.model, X_scaled, y, cv=5)
        
        print("=" * 50)
        print("HEAT DETECTION MODEL TRAINING RESULTS")
        print("=" * 50)
        print(f"Training Accuracy: {train_score:.4f}")
        print(f"Test Accuracy: {test_score:.4f}")
        print(f"CV Accuracy: {cv_scores.mean():.4f} (+/- {cv_scores.std()*2:.4f})")
        print("\nClassification Report:")
        y_pred = self.model.predict(X_test)
        print(classification_report(y_test, y_pred, 
                                   target_names=['Not Heat', 'Heat']))
        
        # Feature importance
        print("\nFeature Importance:")
        importance = self.model.feature_importances_
        for name, imp in sorted(zip(self.feature_names, importance), 
                               key=lambda x: x[1], reverse=True):
            print(f"  {name}: {imp:.4f}")
        
        return self
    
    def predict(self, cow_data):
        """
        Predict if cow is in heat.
        
        Args:
            cow_data: Dictionary with cow sensor data
            
        Returns:
            Dictionary with prediction results
        """
        if self.model is None:
            raise ValueError("Model not trained or loaded")
        
        # Extract features
        features = self.extract_features(cow_data)
        features_scaled = self.scaler.transform(features)
        
        # Predict
        prediction = self.model.predict(features_scaled)[0]
        probabilities = self.model.predict_proba(features_scaled)[0]
        
        confidence = probabilities[1] if prediction == 1 else probabilities[0]
        
        # Determine priority based on confidence
        if confidence >= 0.90:
            priority = "HIGH"
        elif confidence >= 0.75:
            priority = "MEDIUM"
        else:
            priority = "LOW"
        
        return {
            'cow_id': cow_data.get('cow_id'),
            'in_heat': bool(prediction),
            'confidence': float(confidence),
            'priority': priority,
            'probability_not_heat': float(probabilities[0]),
            'probability_heat': float(probabilities[1]),
            'timestamp': datetime.now().isoformat(),
            'features_used': self.feature_names,
            'recommendation': self._get_recommendation(prediction, confidence)
        }
    
    def _get_recommendation(self, prediction, confidence):
        """Generate recommendation based on prediction."""
        if prediction == 1:
            if confidence >= 0.90:
                return "HIGH CONFIDENCE: Breed immediately or within 6-12 hours"
            elif confidence >= 0.75:
                return "MEDIUM CONFIDENCE: Monitor closely, consider breeding"
            else:
                return "LOW CONFIDENCE: Continue monitoring"
        else:
            return "No heat detected - continue normal monitoring"
    
    def save_model(self, path):
        """Save model and scaler to disk."""
        joblib.dump({
            'model': self.model,
            'scaler': self.scaler,
            'feature_names': self.feature_names
        }, path)
        print(f"Model saved to {path}")
    
    def load_model(self, path):
        """Load model and scaler from disk."""
        data = joblib.load(path)
        self.model = data['model']
        self.scaler = data['scaler']
        self.feature_names = data['feature_names']
        print(f"Model loaded from {path}")


# Example usage and synthetic training data generation
def generate_synthetic_training_data(n_samples=1000):
    """Generate synthetic training data for demonstration."""
    np.random.seed(42)
    
    X = []
    y = []
    
    for _ in range(n_samples):
        # Randomly assign heat or not heat
        is_heat = np.random.choice([0, 1], p=[0.7, 0.3])
        
        if is_heat:
            # Heat indicators
            activity_ratio = np.random.normal(2.2, 0.4)  # 2x normal
            rumination_change = np.random.normal(-40, 10)  # 40% decrease
            temp_elevation = np.random.normal(0.3, 0.15)  # Slight temp rise
            days_since = np.random.randint(18, 24)  # Around 21 days
            mounting = np.random.choice([0, 1], p=[0.3, 0.7])  # Often mounting
            activity_slope = np.random.normal(30, 10)  # Increasing activity
        else:
            # Normal indicators
            activity_ratio = np.random.normal(1.0, 0.2)
            rumination_change = np.random.normal(0, 10)
            temp_elevation = np.random.normal(0, 0.1)
            days_since = np.random.randint(1, 40)
            mounting = np.random.choice([0, 1], p=[0.9, 0.1])
            activity_slope = np.random.normal(0, 5)
        
        # Common features
        lactation_stage = np.random.randint(0, 4)
        hour = np.random.randint(0, 24)
        rumination_slope = np.random.normal(-5 if is_heat else 0, 15)
        
        features = [
            activity_ratio,
            rumination_change,
            temp_elevation,
            days_since,
            lactation_stage,
            mounting,
            np.sin(2 * np.pi * hour / 24),
            activity_slope,
            rumination_slope
        ]
        
        X.append(features)
        y.append(is_heat)
    
    return np.array(X), np.array(y)


# Demo execution
if __name__ == "__main__":
    # Generate synthetic data
    print("Generating synthetic training data...")
    X, y = generate_synthetic_training_data(2000)
    
    # Train model
    print("\nTraining Heat Detection Model...")
    model = HeatDetectionModel()
    model.train(X, y)
    
    # Save model
    model.save_model("heat_detection_model.pkl")
    
    # Test prediction
    print("\n" + "=" * 50)
    print("TEST PREDICTIONS")
    print("=" * 50)
    
    # Test case 1: Likely in heat
    test_cow_heat = {
        'cow_id': 'SD-2024-0156',
        'activity_baseline': 100,
        'current_activity': 240,
        'rumination_baseline_min': 500,
        'current_rumination_min': 280,
        'body_temperature_c': 38.9,
        'normal_temp_c': 38.5,
        'last_heat_date': (datetime.now() - timedelta(days=21)).isoformat(),
        'days_in_milk': 90,
        'mounting_events': 3,
        'hour_of_day': 18,
        'activity_history': [100, 150, 240],
        'rumination_history': [500, 400, 280]
    }
    
    result = model.predict(test_cow_heat)
    print(f"\nTest 1 - Likely Heat:")
    print(f"  Cow ID: {result['cow_id']}")
    print(f"  In Heat: {result['in_heat']}")
    print(f"  Confidence: {result['confidence']:.2%}")
    print(f"  Priority: {result['priority']}")
    print(f"  Recommendation: {result['recommendation']}")
    
    # Test case 2: Normal cow
    test_cow_normal = {
        'cow_id': 'SD-2024-0234',
        'activity_baseline': 100,
        'current_activity': 105,
        'rumination_baseline_min': 500,
        'current_rumination_min': 510,
        'body_temperature_c': 38.6,
        'normal_temp_c': 38.5,
        'last_heat_date': (datetime.now() - timedelta(days=5)).isoformat(),
        'days_in_milk': 90,
        'mounting_events': 0,
        'hour_of_day': 10,
        'activity_history': [100, 102, 105],
        'rumination_history': [500, 505, 510]
    }
    
    result2 = model.predict(test_cow_normal)
    print(f"\nTest 2 - Normal:")
    print(f"  Cow ID: {result2['cow_id']}")
    print(f"  In Heat: {result2['in_heat']}")
    print(f"  Confidence: {result2['confidence']:.2%}")
    print(f"  Priority: {result2['priority']}")
    print(f"  Recommendation: {result2['recommendation']}")
```

### 7.3 Data Pipeline

```python
# Data Processing Pipeline Configuration
{
  "pipeline": {
    "ingestion": {
      "sources": ["lorawan", "bluetooth", "cellular"],
      "buffer_size": 10000,
      "batch_interval_seconds": 60
    },
    "validation": {
      "schema_check": true,
      "range_validation": true,
      "timestamp_validation": true,
      "device_authentication": true
    },
    "transformation": {
      "unit_conversion": true,
      "baseline_calculation": true,
      "anomaly_detection": true,
      "feature_extraction": true
    },
    "storage": {
      "hot_storage": {
        "type": "redis",
        "retention_hours": 24
      },
      "warm_storage": {
        "type": "timescaledb",
        "retention_days": 90
      },
      "cold_storage": {
        "type": "s3",
        "retention_years": 7
      }
    },
    "processing": {
      "real_time": {
        "latency_target_ms": 1000,
        "functions": ["alert_generation", "dashboard_update"]
      },
      "batch": {
        "schedule": "hourly",
        "functions": ["ml_inference", "report_generation", "analytics"]
      }
    }
  }
}
```

---

## 8. Health Alerts

### 8.1 Lameness Detection

#### 8.1.1 Lameness Indicators

| Indicator | Normal | Mild | Moderate | Severe |
|-----------|--------|------|----------|--------|
| Step Count | 3000-5000/day | 2000-3000 | 1000-2000 | <1000 |
| Activity Index | 100% | 70-100% | 40-70% | <40% |
| Lying Time | 10-14 hrs | 14-16 hrs | 16-20 hrs | >20 hrs |
| Step Regularity | Regular | Slight irregular | Irregular | Very irregular |
| Weight Distribution | Balanced | Slight favor | Clear favor | Avoidance |

#### 8.1.2 Lameness Detection Algorithm

```python
class LamenessDetector:
    """
    Detect lameness in dairy cattle using gait analysis and behavioral patterns.
    """
    
    def __init__(self):
        self.step_baseline_window = 7  # days
        self.alert_threshold = 0.7  # 70% of baseline
    
    def detect_lameness(self, cow_id, days_data):
        """
        Analyze movement patterns for lameness indicators.
        
        Returns lameness score 0-3:
        0: Not lame
        1: Mild lameness (monitor)
        2: Moderate lameness (intervention needed)
        3: Severe lameness (immediate treatment)
        """
        indicators = {
            'step_count_ratio': self._analyze_steps(days_data),
            'activity_ratio': self._analyze_activity(days_data),
            'lying_increase': self._analyze_lying_time(days_data),
            'gait_irregularity': self._analyze_gait(days_data)
        }
        
        # Calculate composite score
        score = sum([
            0.5 if indicators['step_count_ratio'] < 0.6 else
            0.25 if indicators['step_count_ratio'] < 0.8 else 0,
            
            0.5 if indicators['activity_ratio'] < 0.5 else
            0.25 if indicators['activity_ratio'] < 0.75 else 0,
            
            0.5 if indicators['lying_increase'] > 1.5 else
            0.25 if indicators['lying_increase'] > 1.2 else 0,
            
            1.0 if indicators['gait_irregularity'] > 0.7 else
            0.5 if indicators['gait_irregularity'] > 0.4 else 0
        ])
        
        if score >= 2.0:
            return {'score': 3, 'level': 'SEVERE', 'indicators': indicators}
        elif score >= 1.0:
            return {'score': 2, 'level': 'MODERATE', 'indicators': indicators}
        elif score >= 0.5:
            return {'score': 1, 'level': 'MILD', 'indicators': indicators}
        else:
            return {'score': 0, 'level': 'NORMAL', 'indicators': indicators}
```

### 8.2 Mastitis Early Detection

#### 8.2.1 Mastitis Indicators

```
┌─────────────────────────────────────────────────────────────────┐
│               MASTITIS EARLY DETECTION SYSTEM                   │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  PRIMARY INDICATORS:                                            │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │                                                          │  │
│  │  1. BODY TEMPERATURE                                      │  │
│  │     • Normal: 38.0-39.2°C                                │  │
│  │     • Alert: >39.5°C sustained >2 hours                  │  │
│  │     • Sensitivity: 70-80% for clinical mastitis          │  │
│  │                                                          │  │
│  │  2. ACTIVITY CHANGES                                      │  │
│  │     • Reduced activity (<70% of baseline)                │  │
│  │     • Decreased feeding time                             │  │
│  │     • Increased lying time                               │  │
│  │                                                          │  │
│  │  3. RUMINATION PATTERNS                                   │  │
│  │     • Drop >30% from baseline                            │  │
│  │     • Reduced eating time                                │  │
│  │                                                          │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                 │
│  CONFIRMATION:                                                  │
│  • Somatic Cell Count (SCC) >200,000 cells/ml                  │
│  • California Mastitis Test (CMT)                              │
│  • Milk conductivity change                                    │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

#### 8.2.2 Mastitis Alert Configuration

```yaml
mastitis_detection:
  temperature_threshold: 39.5
  temperature_duration_minutes: 120
  
  activity_threshold: 0.7
  activity_duration_hours: 6
  
  rumination_threshold: 0.7
  rumination_duration_hours: 4
  
  composite_score:
    weight_temperature: 0.4
    weight_activity: 0.3
    weight_rumination: 0.3
    
  alert_levels:
    watch:
      score_threshold: 0.5
      action: "Monitor closely, check milk appearance"
    warning:
      score_threshold: 0.7
      action: "Perform CMT test, notify milker"
    critical:
      score_threshold: 0.85
      action: "Immediate veterinary attention, withhold milk"
```

### 8.3 Other Disease Alerts

| Disease | Key Indicators | Detection Accuracy | Alert Priority |
|---------|----------------|-------------------|----------------|
| Ketosis | Reduced activity, Temp drop, Rumination drop | 75% | HIGH |
| Milk Fever | Severe activity drop, Recumbency | 85% | CRITICAL |
| Displaced Abomasum | Severe rumination drop, Restlessness | 70% | HIGH |
| Metritis | Temp elevation, Activity drop post-calving | 80% | HIGH |
| Pneumonia | Temp elevation, Respiratory rate increase | 85% | CRITICAL |

---

## 9. Reproduction Management

### 9.1 Heat Detection Accuracy

#### 9.1.1 Detection Methods Comparison

| Method | Sensitivity | Specificity | Cost | Labor |
|--------|-------------|-------------|------|-------|
| Visual Observation | 40-50% | 70% | Low | High |
| Tail Paint | 70-80% | 85% | Low | Medium |
| Activity Monitors | 85-90% | 95% | Medium | Low |
| Pressure Sensors | 90-95% | 90% | Medium | Low |
| Combined Systems | 95-98% | 95%+ | High | Very Low |

#### 9.1.2 Heat Expression Timing

```
┌─────────────────────────────────────────────────────────────────┐
│                   HEAT EXPRESSION TIMELINE                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Hours Before Ovulation:                                        │
│                                                                 │
│  -48h ├──────────────────────────────────────────────────┤     │
│       │                                                  │     │
│       │  ┌───────────┐                                   │     │
│       │  │  STANDING │                                   │     │
│  -24h │  │   HEAT    │  ← PRIMARY BREEDING WINDOW       │     │
│       │  │  (Peak)   │     • 12-18 hours duration        │     │
│       │  └─────┬─────┘     • Best conception rate        │     │
│       │        │                                            │     │
│       │        ▼                                            │     │
│   0h  │  ┌───────────┐  ← OVULATION                        │     │
│       │  │ OVULATION │     • Optimal breeding: -12 to -4h  │     │
│       │  │           │     • Egg viable: 6-8 hours         │     │
│       │  └───────────┘                                    │     │
│       │                                                  │     │
│  +12h └──────────────────────────────────────────────────┘     │
│                                                                 │
│  SENSOR DETECTION:                                              │
│  • Activity increase: 6-12 hours before standing heat          │
│  • Rumination drop: 12-24 hours before standing heat           │
│  • Temperature rise: 0-12 hours after standing heat            │
│  • Mounting detection: During standing heat period             │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 9.2 Breeding Timing Optimization

#### 9.2.1 Optimal Breeding Window

| Heat Stage | Hours from Onset | Action | Expected Conception Rate |
|------------|------------------|--------|-------------------------|
| Early | 0-6 | Wait | 30% |
| Optimal | 6-18 | Breed NOW | 65-75% |
| Late | 18-24 | Breed if not done | 50-60% |
| Post-ovulation | >24 | Too late | <20% |

#### 9.2.2 Breeding Recommendation Algorithm

```python
def get_breeding_recommendation(heat_detection_result):
    """
    Generate breeding timing recommendation based on heat detection.
    """
    confidence = heat_detection_result['confidence']
    detection_time = heat_detection_result['timestamp']
    current_time = datetime.now()
    hours_since_detection = (current_time - detection_time).total_seconds() / 3600
    
    if not heat_detection_result['in_heat']:
        return {
            'breed_now': False,
            'message': 'Heat not detected - continue monitoring',
            'next_check': 'Check again in 4 hours'
        }
    
    if hours_since_detection < 6:
        return {
            'breed_now': False,
            'message': f'Heat detected {hours_since_detection:.1f}h ago - TOO EARLY',
            'breed_at': detection_time + timedelta(hours=8),
            'breed_in_hours': 8 - hours_since_detection,
            'confidence': confidence
        }
    elif hours_since_detection < 18:
        return {
            'breed_now': True,
            'message': f'OPTIMAL BREEDING WINDOW ({hours_since_detection:.1f}h since onset)',
            'urgency': 'HIGH' if hours_since_detection > 12 else 'MEDIUM',
            'expected_conception_rate': '65-75%',
            'confidence': confidence
        }
    elif hours_since_detection < 24:
        return {
            'breed_now': True,
            'message': f'Late breeding window ({hours_since_detection:.1f}h) - BREED NOW if not done',
            'urgency': 'HIGH',
            'expected_conception_rate': '50-60%',
            'confidence': confidence
        }
    else:
        return {
            'breed_now': False,
            'message': 'Breeding window may have passed - consult veterinarian',
            'next_heat_expected': detection_time + timedelta(days=21),
            'confidence': confidence
        }
```

### 9.3 Calving Alerts

#### 9.3.1 Pre-Calving Indicators

```
┌─────────────────────────────────────────────────────────────────┐
│                    PRE-CALVING INDICATORS                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  7-14 DAYS BEFORE:                                              │
│  • Udder begins to fill                                         │
│  • Vulva becomes relaxed                                        │
│  • Pelvic ligaments relax (sunken appearance)                   │
│                                                                 │
│  24-48 HOURS BEFORE (DETECTABLE BY SENSORS):                    │
│  • Activity pattern changes:                                    │
│    - Increased restlessness                                     │
│    - Reduced rumination                                         │
│    - More frequent lying/standing transitions                   │
│  • Body temperature: May drop 0.5-1.0°C                         │
│  • Isolation behavior: Moving away from herd                    │
│                                                                 │
│  2-6 HOURS BEFORE (IMMINENT):                                   │
│  • Intense restlessness                                         │
│  • Tail raising, switching                                      │
│  • Visible contractions                                         │
│  • Mucus discharge                                              │
│                                                                 │
│  SENSOR-BASED ALERT TRIGGERS:                                   │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  Alert Level │ Triggers                                 │   │
│  │  ─────────────────────────────────────────────────────  │   │
│  │  WATCH       │ Activity +50%, Rumination -30%          │   │
│  │  WARNING     │ Activity +100%, Rumination -50%, Temp↓  │   │
│  │  IMMINENT    │ Restlessness pattern, Isolation detected │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## 10. Integration with Herd Management

### 10.1 Animal Records Integration

#### 10.1.1 Data Synchronization

```python
{
  "herd_management_integration": {
    "animal_master_record": {
      "cow_id": "SD-2024-0156",
      "rfid_tag": "840003001234567",
      "wearable_devices": [
        {
          "device_id": "COLLAR-7834-A",
          "device_type": "activity_collar",
          "paired_date": "2024-03-15",
          "status": "active"
        },
        {
          "device_id": "BOLUS-4521-B",
          "device_type": "temperature_bolus",
          "paired_date": "2024-03-15",
          "status": "active"
        }
      ]
    },
    "synchronized_fields": [
      "health_events",
      "reproduction_records",
      "activity_summaries",
      "alert_history"
    ],
    "sync_frequency": "real_time",
    "sync_protocol": "REST API"
  }
}
```

#### 10.1.2 Integration API Endpoints

```yaml
# Herd Management System API Integration
endpoints:
  animal_profile:
    get: /api/v1/animals/{cow_id}
    update: /api/v1/animals/{cow_id}
    
  health_records:
    create_event: /api/v1/health/events
    get_history: /api/v1/health/history/{cow_id}
    
  reproduction:
    record_heat: /api/v1/reproduction/heat-events
    record_breeding: /api/v1/reproduction/breedings
    record_calving: /api/v1/reproduction/calvings
    
  alerts:
    create: /api/v1/alerts
    update_status: /api/v1/alerts/{alert_id}
    get_active: /api/v1/alerts/active
```

### 10.2 Veterinary Alerts

#### 10.2.1 Alert Routing

```
┌─────────────────────────────────────────────────────────────────┐
│                    VETERINARY ALERT SYSTEM                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ALERT GENERATION:                                              │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  Condition Detected → Severity Assessment → Routing      │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                 │
│  NOTIFICATION CHANNELS:                                         │
│                                                                 │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐             │
│  │   MOBILE    │  │    SMS      │  │    EMAIL    │             │
│  │    APP      │  │   MESSAGE   │  │   ALERT     │             │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘             │
│         │                │                │                    │
│         └────────────────┴────────────────┘                    │
│                          │                                      │
│                          ▼                                      │
│              ┌─────────────────────┐                           │
│              │   PRIORITY QUEUE    │                           │
│              └──────────┬──────────┘                           │
│                         │                                       │
│         ┌───────────────┼───────────────┐                      │
│         ▼               ▼               ▼                      │
│    ┌─────────┐    ┌─────────┐    ┌─────────┐                  │
│    │ FARMER  │    │ HERD    │    │    VET    │                 │
│    │  APP    │    │ MANAGER │    │  PORTAL   │                 │
│    └─────────┘    └─────────┘    └─────────┘                  │
│                                                                 │
│  ESCALATION RULES:                                              │
│  • Level 1: Farm staff notification (0-5 minutes)              │
│  • Level 2: Herd manager alert (if unacknowledged 15 min)      │
│  • Level 3: Veterinarian notification (if unacknowledged 30min)│
│  • Level 4: Emergency contact (critical conditions only)       │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

#### 10.2.2 Veterinary Report Template

```json
{
  "veterinary_alert": {
    "alert_id": "VA-2026-0131-0842",
    "timestamp": "2026-01-31T14:30:00+08:00",
    "priority": "HIGH",
    "cow": {
      "cow_id": "SD-2024-0156",
      "name": "Bella",
      "lactation": 2,
      "dim": 45,
      "location": "Barn A, Section 3"
    },
    "condition": {
      "type": "MASTITIS_SUSPECTED",
      "confidence": 0.87,
      "detected_by": ["temperature_elevation", "activity_decrease"],
      "indicators": {
        "temperature_c": 40.2,
        "activity_pct_of_baseline": 45,
        "rumination_pct_of_baseline": 60,
        "duration_hours": 6
      }
    },
    "recommended_actions": [
      "Perform CMT test on all quarters",
      "Check milk for clots/abnormal appearance",
      "Take milk sample for culture",
      "Consider antibiotic treatment if confirmed"
    ],
    "acknowledgment_required": true,
    "escalation_time": "2026-01-31T15:00:00+08:00"
  }
}
```

---

## 11. Battery Management

### 11.1 Battery Life Monitoring

#### 11.1.1 Battery Status Tracking

```python
{
  "battery_monitoring": {
    "reporting": {
      "frequency": "daily",
      "thresholds": {
        "healthy": { "min": 50, "action": "none" },
        "warning": { "min": 25, "action": "schedule_replacement" },
        "critical": { "min": 10, "action": "immediate_replacement" },
        "depleted": { "min": 5, "action": "data_loss_risk" }
      }
    },
    "predictive_analytics": {
      "enabled": true,
      "model": "linear_regression",
      "prediction_window_days": 30,
      "factors": ["usage_pattern", "temperature", "transmission_frequency"]
    }
  }
}
```

#### 11.1.2 Battery Life Estimation

| Device Type | Standard Life | Low Temp (-10°C) | High Temp (+40°C) | High Activity |
|-------------|---------------|------------------|-------------------|---------------|
| Activity Collar | 5 years | 3.5 years | 4 years | 4 years |
| Temperature Bolus | 5 years | 5 years | 3 years | N/A |
| GPS Tracker | 2 years | 1.5 years | 1.5 years | 1 year |
| Ear Tag Sensor | 3 years | 2 years | 2 years | 2.5 years |

### 11.2 Replacement Schedules

#### 11.2.1 Planned Replacement

```
┌─────────────────────────────────────────────────────────────────┐
│                   BATTERY REPLACEMENT SCHEDULE                  │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  PROACTIVE REPLACEMENT (Recommended):                           │
│  • Replace at 70% battery life remaining                        │
│  • Schedule during routine health checks                        │
│  • Order replacements 3 months in advance                       │
│                                                                 │
│  REPLACEMENT CHECKLIST:                                         │
│  □ Record device serial number                                  │
│  □ Download any stored data                                     │
│  □ Remove old device carefully                                  │
│  □ Check attachment site for irritation                         │
│  □ Clean attachment area                                        │
│  □ Apply new device with proper fit                             │
│  □ Test communication and data transmission                     │
│  □ Update records with new device ID                            │
│  □ Dispose of old device per environmental regulations          │
│                                                                 │
│  SCHEDULE TEMPLATE:                                             │
│  ┌──────────────┬──────────────┬──────────────┐                │
│  │   Quarter    │ Devices Due  │   Budget     │                │
│  ├──────────────┼──────────────┼──────────────┤                │
│  │ Q1 2026      │     45       │   $13,500    │                │
│  │ Q2 2026      │     62       │   $18,600    │                │
│  │ Q3 2026      │     38       │   $11,400    │                │
│  │ Q4 2026      │     51       │   $15,300    │                │
│  └──────────────┴──────────────┴──────────────┘                │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 11.3 Charging Infrastructure

#### 11.3.1 Rechargeable Devices

| Device Type | Charging Method | Charge Time | Battery Chemistry |
|-------------|-----------------|-------------|-------------------|
| Collars (select models) | Inductive pad | 2-4 hours | Li-Ion |
| Handheld Readers | USB-C/Wall | 1-2 hours | Li-Po |
| Gateway Devices | Wired power | N/A | Lead-acid backup |

---

## 12. Troubleshooting

### 12.1 False Positives

#### 12.1.1 Common Causes and Solutions

| Alert Type | False Positive Cause | Solution |
|------------|---------------------|----------|
| Heat Detection | Group activity (feed delivery) | Filter by duration (>4 hours) |
| Heat Detection | Sick animal behavior | Health check correlation |
| Lameness | Footbath interference | Temporary flag exclusion |
| Temperature | Drinking cold water | 30-minute averaging window |
| Temperature | External heat source | Sensor placement verification |
| Low Activity | Device malfunction | Device health check |

#### 12.1.2 False Positive Reduction Algorithm

```python
class FalsePositiveFilter:
    """
    Reduces false positive alerts through context analysis.
    """
    
    def filter_heat_alert(self, raw_alert, context_data):
        """
        Apply filters to reduce false heat alerts.
        """
        filters_passed = 0
        total_filters = 4
        
        # Filter 1: Duration check (heat lasts 12-18 hours)
        if raw_alert['duration_hours'] >= 4:
            filters_passed += 1
        
        # Filter 2: Rumination correlation (heat reduces rumination)
        if context_data['rumination_change'] < -20:
            filters_passed += 1
        
        # Filter 3: Time since last heat (should be >18 days)
        if context_data['days_since_last_heat'] >= 18:
            filters_passed += 1
        
        # Filter 4: No concurrent illness indicators
        if not context_data['fever_detected']:
            filters_passed += 1
        
        # Calculate confidence adjustment
        confidence = raw_alert['confidence'] * (filters_passed / total_filters)
        
        return {
            'alert_valid': filters_passed >= 3,
            'adjusted_confidence': confidence,
            'filters_passed': filters_passed,
            'requires_review': filters_passed < 4
        }
```

### 12.2 Missed Detections

#### 12.2.1 Silent Heats

Some cows show minimal or no visible heat signs. Detection strategies:

| Strategy | Implementation | Effectiveness |
|----------|---------------|---------------|
| Activity Sensitivity | Lower threshold to 1.5x baseline | +15% detection |
| Secondary Indicators | Monitor rumination, temperature | +10% detection |
| Group Analysis | Compare to herd baseline | +5% detection |
| Regular PD Checks | Pregnancy diagnosis schedule | 100% confirmation |
| Progesterone Monitoring | Milk progesterone testing | Gold standard |

#### 12.2.2 Device Failure Detection

```python
def detect_device_failure(device_id, last_transmission_time):
    """
    Detect potential device failures based on transmission patterns.
    """
    time_since_last = datetime.now() - last_transmission_time
    
    # Expected transmission intervals by device type
    expected_intervals = {
        'activity_collar': timedelta(minutes=15),
        'temperature_bolus': timedelta(minutes=15),
        'gps_tracker': timedelta(hours=1)
    }
    
    device_type = get_device_type(device_id)
    expected = expected_intervals.get(device_type, timedelta(minutes=30))
    
    # Alert thresholds (missed transmissions)
    if time_since_last > expected * 4:
        return {
            'status': 'CRITICAL',
            'message': 'Device likely failed or lost',
            'action': 'Physical inspection required'
        }
    elif time_since_last > expected * 2:
        return {
            'status': 'WARNING',
            'message': 'Device transmission delayed',
            'action': 'Monitor for next transmission'
        }
    else:
        return {
            'status': 'NORMAL',
            'message': 'Device functioning'
        }
```

### 12.3 Troubleshooting Guide

#### 12.3.1 Device Issues

```
┌─────────────────────────────────────────────────────────────────┐
│                    TROUBLESHOOTING MATRIX                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  SYMPTOM: No data received                                      │
│  ─────────────────────────────────────────────────────────────  │
│  1. Check device LED status                                     │
│     • Blinking: Normal operation                                │
│     • Solid: Possible error state                               │
│     • Off: Battery depleted or device failed                    │
│                                                                 │
│  2. Verify gateway connectivity                                 │
│     • Check gateway power and network                           │
│     • Verify device is within range                             │
│     • Check for interference sources                            │
│                                                                 │
│  3. Inspect device physically                                   │
│     • Check attachment (not too loose/tight)                    │
│     • Look for physical damage                                  │
│     • Clean sensors if dirty                                    │
│                                                                 │
│  SYMPTOM: Erratic data                                          │
│  ─────────────────────────────────────────────────────────────  │
│  1. Check for environmental factors                             │
│     • Temperature extremes                                      │
│     • Electromagnetic interference                              │
│     • Physical obstruction                                      │
│                                                                 │
│  2. Review device calibration                                   │
│     • Baseline may need reset                                   │
│     • Check for firmware updates                                │
│                                                                 │
│  SYMPTOM: False alerts                                          │
│  ─────────────────────────────────────────────────────────────  │
│  1. Review alert thresholds                                     │
│     • May be set too sensitively                                │
│     • Adjust based on herd patterns                             │
│                                                                 │
│  2. Verify baseline calculations                                │
│     • Sufficient historical data?                               │
│     • Recent changes in management?                             │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## 13. Appendices

### Appendix A: Device Comparison Matrix

#### A.1 Activity Monitoring Devices

| Feature | CowManager | SCR HR | MooMonitor+ | CowView | SmartDairy Rec. |
|---------|------------|--------|-------------|---------|-----------------|
| **Manufacturer** | Agis | SCR/Allflex | Dairymaster | GEA | TBD |
| **Attachment** | Ear tag | Neck collar | Neck collar | Neck/Leg | Neck collar |
| **Rumination** | ✓ | ✓ | ✓ | ✓ | ✓ |
| **Activity** | ✓ | ✓ | ✓ | ✓ | ✓ |
| **Eating Time** | ✓ | ✓ | ✓ | ✓ | ✓ |
| **Heat Detection** | ✓ | ✓ | ✓ | ✓ | ✓ |
| **Resting** | ✓ | ✓ | ✓ | ✓ | ✓ |
| **Communication** | LoRa | RF/LoRa | Proprietary | LoRa | LoRa |
| **Battery Life** | 3 years | 5 years | 5 years | 5 years | 5 years |
| **Water Resistance** | IP67 | IP67 | IP68 | IP67 | IP67 |
| **Accuracy** | 95% | 95% | 96% | 94% | 95%+ |
| **Price** | $$$ | $$ | $$$ | $$$ | TBD |

#### A.2 Health Monitoring Devices

| Feature | Medria | Moocall | smaXtec | SmartDairy Rec. |
|---------|--------|---------|---------|-----------------|
| **Temperature** | Bolus | External | Bolus | Bolus |
| **Accuracy** | ±0.1°C | ±0.3°C | ±0.1°C | ±0.1°C |
| **Retention** | 99% | N/A | 99% | 99% |
| **Battery Life** | 5 years | 2 years | 5 years | 5 years |
| **Calving Alert** | ✓ | ✓ | ✓ | ✓ |
| **Heat Detection** | ✓ | - | ✓ | ✓ |
| **Price** | $$$ | $$ | $$$$ | TBD |

#### A.3 GPS Tracking Devices

| Feature | Vence | Halter | SmartDairy Rec. |
|---------|-------|--------|-----------------|
| **Position Accuracy** | 3-5m | 5-10m | 3-5m |
| **Update Frequency** | 5-15 min | 15-60 min | Configurable |
| **Battery Life** | 2-3 years | 1-2 years | 2 years |
| **Virtual Fencing** | ✓ | - | Optional |
| **Grazing Analytics** | ✓ | - | ✓ |
| **Price** | $$$$ | $$$ | TBD |

### Appendix B: Integration Code Samples

#### B.1 Device Registration API

```python
#!/usr/bin/env python3
"""
Device Registration API Client
Smart Dairy Ltd. - Wearable Device Integration
"""

import requests
import json
from datetime import datetime

class WearableDeviceAPI:
    def __init__(self, base_url, api_key):
        self.base_url = base_url
        self.headers = {
            'Authorization': f'Bearer {api_key}',
            'Content-Type': 'application/json'
        }
    
    def register_device(self, device_info):
        """
        Register a new wearable device.
        
        Args:
            device_info: Dictionary with device details
        """
        endpoint = f"{self.base_url}/api/v1/devices"
        
        payload = {
            "device_id": device_info['device_id'],
            "device_type": device_info['device_type'],
            "manufacturer": device_info['manufacturer'],
            "model": device_info['model'],
            "serial_number": device_info['serial_number'],
            "firmware_version": device_info.get('firmware_version', '1.0.0'),
            "communication_protocol": device_info['protocol'],
            "battery_type": device_info.get('battery_type', 'non_rechargeable'),
            "installation_date": datetime.now().isoformat(),
            "warranty_expiry": device_info.get('warranty_expiry'),
            "assigned_cow": device_info.get('cow_id'),
            "location": device_info.get('location', 'barn_a'),
            "status": "active"
        }
        
        response = requests.post(
            endpoint,
            headers=self.headers,
            json=payload
        )
        
        if response.status_code == 201:
            print(f"Device {device_info['device_id']} registered successfully")
            return response.json()
        else:
            print(f"Registration failed: {response.text}")
            return None
    
    def pair_device_to_cow(self, device_id, cow_id):
        """Pair a device to a specific cow."""
        endpoint = f"{self.base_url}/api/v1/devices/{device_id}/pair"
        
        payload = {
            "cow_id": cow_id,
            "pairing_date": datetime.now().isoformat()
        }
        
        response = requests.post(endpoint, headers=self.headers, json=payload)
        return response.status_code == 200
    
    def get_device_data(self, device_id, start_time, end_time):
        """Retrieve historical data from a device."""
        endpoint = f"{self.base_url}/api/v1/devices/{device_id}/data"
        
        params = {
            "start": start_time.isoformat(),
            "end": end_time.isoformat()
        }
        
        response = requests.get(endpoint, headers=self.headers, params=params)
        return response.json() if response.status_code == 200 else None


# Example usage
if __name__ == "__main__":
    api = WearableDeviceAPI(
        base_url="https://api.smartdairy.com",
        api_key="your_api_key_here"
    )
    
    # Register new activity collar
    device = {
        "device_id": "COLLAR-2026-001",
        "device_type": "activity_collar",
        "manufacturer": "SmartDairyTech",
        "model": "SD-AC-2000",
        "serial_number": "SN78451234",
        "protocol": "lorawan",
        "cow_id": "SD-2024-0156"
    }
    
    result = api.register_device(device)
    print(json.dumps(result, indent=2))
```

#### B.2 Real-time Data Ingestion

```python
#!/usr/bin/env python3
"""
Real-time Data Ingestion Handler
Processes incoming data from wearable devices
"""

import json
import asyncio
from datetime import datetime
from typing import Dict, Any
import aioredis

class DataIngestionHandler:
    def __init__(self, redis_url):
        self.redis = None
        self.redis_url = redis_url
        self.processors = {
            'activity_collar': self._process_activity_data,
            'temperature_bolus': self._process_temperature_data,
            'gps_tracker': self._process_gps_data
        }
    
    async def connect(self):
        """Connect to Redis for caching."""
        self.redis = await aioredis.from_url(self.redis_url)
    
    async def ingest_data(self, device_data: Dict[str, Any]):
        """
        Main ingestion pipeline for device data.
        """
        try:
            # 1. Validate data
            if not self._validate_data(device_data):
                return {'status': 'error', 'message': 'Validation failed'}
            
            # 2. Normalize format
            normalized = self._normalize_data(device_data)
            
            # 3. Process by device type
            device_type = normalized['device_type']
            processor = self.processors.get(device_type, self._process_generic)
            processed = await processor(normalized)
            
            # 4. Store in hot cache
            await self._cache_data(processed)
            
            # 5. Trigger alerts if needed
            alerts = await self._check_alerts(processed)
            
            # 6. Queue for long-term storage
            await self._queue_for_storage(processed)
            
            return {
                'status': 'success',
                'processed_at': datetime.now().isoformat(),
                'alerts_generated': len(alerts),
                'alerts': alerts
            }
            
        except Exception as e:
            return {'status': 'error', 'message': str(e)}
    
    def _validate_data(self, data: Dict) -> bool:
        """Validate incoming data structure."""
        required_fields = ['device_id', 'device_type', 'timestamp', 'cow_id']
        return all(field in data for field in required_fields)
    
    def _normalize_data(self, data: Dict) -> Dict:
        """Normalize data to standard format."""
        return {
            'device_id': data['device_id'],
            'device_type': data['device_type'],
            'cow_id': data['cow_id'],
            'timestamp': data['timestamp'],
            'data': data.get('sensor_data', {}),
            'metadata': {
                'firmware': data.get('firmware_version'),
                'battery': data.get('battery_level'),
                'rssi': data.get('signal_strength')
            }
        }
    
    async def _process_activity_data(self, data: Dict) -> Dict:
        """Process activity collar data."""
        sensor_data = data['data']
        
        # Calculate activity metrics
        processed = {
            **data,
            'metrics': {
                'activity_score': sensor_data.get('activity', 0),
                'rumination_minutes': sensor_data.get('rumination', 0),
                'eating_minutes': sensor_data.get('eating', 0),
                'lying_minutes': sensor_data.get('lying', 0),
                'steps': sensor_data.get('steps', 0)
            }
        }
        
        return processed
    
    async def _process_temperature_data(self, data: Dict) -> Dict:
        """Process temperature sensor data."""
        sensor_data = data['data']
        temp_c = sensor_data.get('temperature', 385) / 10  # Convert from x10
        
        processed = {
            **data,
            'metrics': {
                'temperature_c': temp_c,
                'temperature_f': (temp_c * 9/5) + 32,
                'trend': sensor_data.get('trend', 'stable')
            }
        }
        
        return processed
    
    async def _process_gps_data(self, data: Dict) -> Dict:
        """Process GPS tracker data."""
        sensor_data = data['data']
        
        processed = {
            **data,
            'metrics': {
                'latitude': sensor_data.get('lat'),
                'longitude': sensor_data.get('lon'),
                'accuracy_m': sensor_data.get('accuracy'),
                'speed_ms': sensor_data.get('speed', 0),
                'pasture_zone': self._determine_pasture_zone(
                    sensor_data.get('lat'),
                    sensor_data.get('lon')
                )
            }
        }
        
        return processed
    
    def _determine_pasture_zone(self, lat, lon):
        """Determine which pasture zone the cow is in."""
        # Simplified zone determination
        # In production, use proper geofencing
        zones = {
            'north_paddock': {'lat_min': 1.30, 'lat_max': 1.32, 'lon_min': 103.9, 'lon_max': 104.0},
            'south_paddock': {'lat_min': 1.28, 'lat_max': 1.30, 'lon_min': 103.9, 'lon_max': 104.0}
        }
        
        for zone, bounds in zones.items():
            if (bounds['lat_min'] <= lat <= bounds['lat_max'] and 
                bounds['lon_min'] <= lon <= bounds['lon_max']):
                return zone
        
        return 'unknown'
    
    async def _cache_data(self, data: Dict):
        """Store data in Redis hot cache."""
        key = f"cow:{data['cow_id']}:latest"
        await self.redis.setex(key, 86400, json.dumps(data))  # 24 hour expiry
    
    async def _check_alerts(self, data: Dict) -> list:
        """Check if alerts should be generated."""
        alerts = []
        
        # Check temperature alerts
        if 'temperature_c' in data['metrics']:
            temp = data['metrics']['temperature_c']
            if temp > 39.5:
                alerts.append({
                    'type': 'HIGH_TEMPERATURE',
                    'severity': 'HIGH',
                    'cow_id': data['cow_id'],
                    'value': temp
                })
        
        # Check activity alerts
        if 'activity_score' in data['metrics']:
            activity = data['metrics']['activity_score']
            if activity > 200:  # 2x baseline
                alerts.append({
                    'type': 'HIGH_ACTIVITY',
                    'severity': 'MEDIUM',
                    'cow_id': data['cow_id'],
                    'value': activity
                })
        
        return alerts
    
    async def _queue_for_storage(self, data: Dict):
        """Queue data for long-term storage."""
        await self.redis.lpush('data:storage:queue', json.dumps(data))


# Example usage
async def main():
    handler = DataIngestionHandler('redis://localhost:6379')
    await handler.connect()
    
    # Simulate incoming data
    sample_data = {
        'device_id': 'COLLAR-7834-A',
        'device_type': 'activity_collar',
        'cow_id': 'SD-2024-0156',
        'timestamp': datetime.now().isoformat(),
        'sensor_data': {
            'activity': 145,
            'rumination': 45,
            'temperature': 385,
            'steps': 1500
        },
        'battery_level': 87,
        'signal_strength': -85
    }
    
    result = await handler.ingest_data(sample_data)
    print(json.dumps(result, indent=2))

if __name__ == "__main__":
    asyncio.run(main())
```

### Appendix C: Alert Rules Configuration

#### C.1 Alert Rule Definitions

```yaml
# Smart Dairy Alert Rules Configuration
# Document: I-007, Appendix C
# Version: 1.0

alert_rules:
  
  # Heat Detection Alerts
  heat_detection:
    rule_id: ALERT-001
    name: "Heat Detection Alert"
    description: "Detect estrus in dairy cattle"
    priority_levels:
      high:
        confidence_threshold: 0.90
        conditions:
          - activity_ratio > 2.0
          - rumination_change < -30
        notification_channels: [mobile_push, sms, email]
        escalation_minutes: 15
      medium:
        confidence_threshold: 0.75
        conditions:
          - activity_ratio > 1.8
          - rumination_change < -20
        notification_channels: [mobile_push, email]
        escalation_minutes: 60
      low:
        confidence_threshold: 0.60
        conditions:
          - activity_ratio > 1.5
        notification_channels: [mobile_push]
        escalation_minutes: 120
    auto_actions:
      - update_reproduction_calendar
      - notify_breeding_team
      - log_event

  # Health Alerts
  high_temperature:
    rule_id: ALERT-002
    name: "High Body Temperature"
    description: "Elevated body temperature detected"
    conditions:
      - temperature_c > 39.5
      - duration_minutes > 120
    priority: HIGH
    notification_channels: [mobile_push, sms, email]
    recipients:
      - role: farm_manager
      - role: veterinarian
    auto_actions:
      - create_vet_ticket
      - mark_for_inspection
      - log_health_event

  low_activity:
    rule_id: ALERT-003
    name: "Low Activity Alert"
    description: "Significantly reduced activity"
    conditions:
      - activity_pct_baseline < 50
      - duration_hours > 6
      - not_in_heat: true  # Exclude heat-related low activity
    priority: MEDIUM
    notification_channels: [mobile_push, email]
    recipients:
      - role: herd_manager
    auto_actions:
      - flag_for_observation
      - check_for_lameness

  rumination_drop:
    rule_id: ALERT-004
    name: "Significant Rumination Drop"
    description: "Rumination time significantly below normal"
    conditions:
      - rumination_pct_baseline < 60
      - duration_hours > 4
    priority: MEDIUM
    notification_channels: [mobile_push]
    recipients:
      - role: herd_manager
    auto_actions:
      - check_feed_quality
      - observe_eating_behavior

  # Lameness Detection
  lameness_suspected:
    rule_id: ALERT-005
    name: "Lameness Suspected"
    description: "Gait analysis indicates possible lameness"
    conditions:
      - step_count_ratio < 0.6
      - lying_increase_ratio > 1.5
      - gait_irregularity_score > 0.5
    priority: HIGH
    notification_channels: [mobile_push, email]
    recipients:
      - role: hoof_trimmer
      - role: herd_manager
    auto_actions:
      - schedule_hoof_check
      - restrict_to_sick_bay

  # Calving Alerts
  calving_imminent:
    rule_id: ALERT-006
    name: "Calving Imminent"
    description: "Pre-calving behavior detected"
    conditions:
      - days_to_calving <= 0
      - activity_spike > 2.0
      - restlessness_score > 0.8
    priority: HIGH
    notification_channels: [mobile_push, sms]
    recipients:
      - role: calving_staff
      - role: farm_manager
    auto_actions:
      - move_to_calving_pen
      - notify_on_call_staff

  # Device Alerts
  low_battery:
    rule_id: ALERT-007
    name: "Device Low Battery"
    description: "Wearable device battery below threshold"
    conditions:
      - battery_percent < 20
    priority: LOW
    notification_channels: [email, dashboard]
    recipients:
      - role: it_support
    auto_actions:
      - schedule_device_replacement

  device_offline:
    rule_id: ALERT-008
    name: "Device Offline"
    description: "Device not transmitting data"
    conditions:
      - last_transmission_minutes > 60
    priority: MEDIUM
    notification_channels: [email]
    recipients:
      - role: it_support
      - role: herd_manager
    auto_actions:
      - flag_for_inspection

# Notification Templates
notification_templates:
  heat_detection_high:
    title: "🐄 HEAT DETECTED - HIGH CONFIDENCE"
    body: |
      Cow: {{cow_id}} ({{cow_name}})
      Confidence: {{confidence}}%
      Detected: {{timestamp}}
      
      Recommended Action: Breed within 6-12 hours
      
      Tap to view details
    action_url: "/animals/{{cow_id}}/reproduction"

  high_temperature:
    title: "🌡️ HIGH TEMPERATURE ALERT"
    body: |
      Cow: {{cow_id}}
      Temperature: {{temperature_c}}°C
      Duration: {{duration_hours}} hours
      
      Please check animal immediately
    action_url: "/animals/{{cow_id}}/health"

  lameness_suspected:
    title: "🦶 LAMENESS SUSPECTED"
    body: |
      Cow: {{cow_id}}
      Lameness Score: {{lameness_score}}/3
      Step Count: {{step_count}} ({{pct_baseline}}% of normal)
      
      Schedule hoof examination
    action_url: "/animals/{{cow_id}}/health"

# Escalation Rules
escalation:
  enabled: true
  stages:
    - stage: 1
      delay_minutes: 0
      notify: [assigned_recipients]
    - stage: 2
      delay_minutes: 15
      if_unacknowledged: true
      notify: [supervisor]
    - stage: 3
      delay_minutes: 30
      if_unacknowledged: true
      notify: [farm_manager, on_call_vet]
```

#### C.2 Mobile App Notification Integration

```python
#!/usr/bin/env python3
"""
Mobile App Notification Service
Smart Dairy Ltd. - Wearable Device Integration
"""

import firebase_admin
from firebase_admin import messaging, credentials
from datetime import datetime
import json

class MobileNotificationService:
    def __init__(self, firebase_credentials_path):
        """Initialize Firebase Cloud Messaging."""
        cred = credentials.Certificate(firebase_credentials_path)
        firebase_admin.initialize_app(cred)
    
    def send_heat_detection_alert(self, cow_data, confidence, recipients):
        """
        Send heat detection alert to mobile devices.
        """
        priority = "HIGH" if confidence >= 0.90 else "MEDIUM" if confidence >= 0.75 else "LOW"
        
        message = messaging.MulticastMessage(
            tokens=recipients,
            notification=messaging.Notification(
                title=f"🐄 HEAT DETECTED - {priority}",
                body=f"Cow {cow_data['cow_id']} ({cow_data.get('name', 'Unknown')}) - "
                     f"Confidence: {confidence:.0%}"
            ),
            data={
                'alert_type': 'heat_detection',
                'cow_id': str(cow_data['cow_id']),
                'confidence': str(confidence),
                'priority': priority,
                'timestamp': datetime.now().isoformat(),
                'action': 'view_cow_details',
                'breeding_recommendation': 'Breed within 6-12 hours' if confidence >= 0.90 else 'Monitor closely'
            },
            android=messaging.AndroidConfig(
                priority='high',
                notification=messaging.AndroidNotification(
                    channel_id='heat_alerts',
                    priority='high',
                    sound='cow_moo.mp3'
                )
            ),
            apns=messaging.APNSConfig(
                payload=messaging.APNSPayload(
                    aps=messaging.Aps(
                        alert=messaging.ApsAlert(
                            title=f"🐄 HEAT DETECTED - {priority}",
                            body=f"Cow {cow_data['cow_id']} - Confidence: {confidence:.0%}"
                        ),
                        badge=1,
                        sound='cow_moo.aiff'
                    )
                )
            )
        )
        
        response = messaging.send_multicast(message)
        return {
            'success_count': response.success_count,
            'failure_count': response.failure_count,
            'message_id': response.responses[0].message_id if response.success_count > 0 else None
        }
    
    def send_health_alert(self, alert_data, recipients):
        """
        Send health-related alert to mobile devices.
        """
        severity_icons = {
            'CRITICAL': '🔴',
            'HIGH': '🟠',
            'MEDIUM': '🟡',
            'LOW': '🟢'
        }
        
        icon = severity_icons.get(alert_data['severity'], '⚠️')
        
        message = messaging.MulticastMessage(
            tokens=recipients,
            notification=messaging.Notification(
                title=f"{icon} {alert_data['alert_type']}",
                body=f"Cow {alert_data['cow_id']}: {alert_data['message']}"
            ),
            data={
                'alert_type': alert_data['alert_type'],
                'cow_id': str(alert_data['cow_id']),
                'severity': alert_data['severity'],
                'timestamp': datetime.now().isoformat(),
                'action': 'view_health_record',
                'recommended_action': alert_data.get('recommended_action', '')
            },
            android=messaging.AndroidConfig(
                priority='high' if alert_data['severity'] in ['CRITICAL', 'HIGH'] else 'normal',
                notification=messaging.AndroidNotification(
                    channel_id='health_alerts',
                    priority='high' if alert_data['severity'] in ['CRITICAL', 'HIGH'] else 'default'
                )
            )
        )
        
        response = messaging.send_multicast(message)
        return response
    
    def subscribe_to_topic(self, token, topic):
        """Subscribe a device to a notification topic."""
        response = messaging.subscribe_to_topic([token], topic)
        return response
    
    def unsubscribe_from_topic(self, token, topic):
        """Unsubscribe a device from a notification topic."""
        response = messaging.unsubscribe_from_topic([token], topic)
        return response


# Usage example
if __name__ == "__main__":
    # Initialize service
    notification_service = MobileNotificationService(
        'path/to/firebase-credentials.json'
    )
    
    # Example heat detection alert
    cow_info = {
        'cow_id': 'SD-2024-0156',
        'name': 'Bella',
        'location': 'Barn A, Section 3'
    }
    
    device_tokens = [
        'device_token_1',
        'device_token_2'
    ]
    
    result = notification_service.send_heat_detection_alert(
        cow_info,
        confidence=0.94,
        recipients=device_tokens
    )
    
    print(f"Notification sent: {result['success_count']} successful")
```

### Appendix D: Data Format Specifications

#### D.1 Standard Data Format

```json
{
  "smart_dairy_data_format": {
    "version": "1.0",
    "description": "Standard data format for wearable device integration",
    
    "message_types": {
      "telemetry": {
        "description": "Regular sensor data transmission",
        "frequency": "every 15 minutes",
        "fields": {
          "message_id": "string (UUID)",
          "message_type": "enum: telemetry",
          "device_id": "string (device unique identifier)",
          "cow_id": "string (animal identifier)",
          "timestamp": "ISO 8601 datetime",
          "sequence_number": "integer (for ordering)",
          "battery_level": "integer (0-100)",
          "signal_strength": "integer (RSSI in dBm)",
          "sensors": {
            "activity": {
              "score": "integer (0-255, relative activity)",
              "steps": "integer (step count since last transmission)",
              "lying_minutes": "integer",
              "standing_minutes": "integer",
              "walking_minutes": "integer"
            },
            "rumination": {
              "chews": "integer",
              "duration_seconds": "integer",
              "confidence": "float (0.0-1.0)"
            },
            "temperature": {
              "value_celsius": "integer (actual * 10, e.g., 385 = 38.5°C)",
              "sensor_location": "enum: reticulum, ear, skin"
            },
            "location": {
              "latitude": "float (decimal degrees)",
              "longitude": "float (decimal degrees)",
              "accuracy_meters": "integer",
              "speed_ms": "float"
            }
          }
        }
      },
      
      "alert": {
        "description": "Real-time alert transmission",
        "frequency": "immediate",
        "fields": {
          "message_id": "string (UUID)",
          "message_type": "enum: alert",
          "alert_type": "enum: heat_detected, high_temp, low_activity, lameness, calving",
          "priority": "enum: low, medium, high, critical",
          "device_id": "string",
          "cow_id": "string",
          "timestamp": "ISO 8601 datetime",
          "alert_data": {
            "description": "Alert-specific data",
            "confidence": "float (0.0-1.0, for ML-based alerts)",
            "value": "number (triggering value)",
            "threshold": "number (threshold that was exceeded)",
            "duration_minutes": "integer"
          }
        }
      },
      
      "heartbeat": {
        "description": "Device status check",
        "frequency": "every 60 minutes",
        "fields": {
          "message_id": "string (UUID)",
          "message_type": "enum: heartbeat",
          "device_id": "string",
          "timestamp": "ISO 8601 datetime",
          "status": "enum: active, error, maintenance",
          "battery_level": "integer (0-100)",
          "firmware_version": "string",
          "uptime_hours": "integer"
        }
      }
    }
  }
}
```

#### D.2 Binary Payload Format (LoRaWAN)

```python
"""
Binary payload encoding for bandwidth-constrained networks (LoRaWAN).
Max payload: 51 bytes
"""

# Payload format version 1
PAYLOAD_FORMAT_V1 = {
    # Byte 0: Header
    'header': {
        'format_version': '2 bits (0-3)',
        'message_type': '2 bits (0=telemetry, 1=alert, 2=heartbeat)',
        'reserved': '4 bits'
    },
    
    # Bytes 1-4: Timestamp (Unix timestamp, 32-bit unsigned)
    'timestamp': 'uint32 (little endian)',
    
    # Byte 5: Battery level
    'battery': 'uint8 (percentage, 0-100)',
    
    # Bytes 6-7: Activity score (0-65535)
    'activity': 'uint16 (little endian)',
    
    # Bytes 8-9: Steps since last transmission
    'steps': 'uint16 (little endian)',
    
    # Bytes 10-11: Rumination minutes
    'rumination': 'uint16 (little endian)',
    
    # Bytes 12-13: Temperature (x10, e.g., 385 = 38.5°C)
    'temperature': 'uint16 (little endian)',
    
    # Byte 14: Status flags
    'flags': {
        'in_heat': 'bit 0',
        'high_temp': 'bit 1',
        'low_activity': 'bit 2',
        'eating': 'bit 3',
        'ruminating': 'bit 4',
        'lying': 'bit 5',
        'alert_active': 'bit 6',
        'gps_fix': 'bit 7'
    },
    
    # Optional: GPS coordinates (8 bytes, if gps_fix flag set)
    'gps': {
        'latitude': 'int32 (decimal degrees * 1e6, little endian)',
        'longitude': 'int32 (decimal degrees * 1e6, little endian)'
    }
}

# Python encoder/decoder
import struct
from datetime import datetime

class BinaryPayloadCodec:
    @staticmethod
    def encode_telemetry(data):
        """Encode telemetry data to binary payload."""
        # Header byte
        header = 0x00  # Version 0, Type 0 (telemetry)
        
        # Timestamp
        timestamp = int(datetime.fromisoformat(data['timestamp']).timestamp())
        
        # Pack data
        payload = struct.pack('<BI', header, timestamp)
        payload += struct.pack('<B', data['battery_level'])
        payload += struct.pack('<H', data['activity'])
        payload += struct.pack('<H', data['steps'])
        payload += struct.pack('<H', data['rumination_minutes'])
        payload += struct.pack('<H', int(data['temperature'] * 10))
        
        # Flags
        flags = 0
        if data.get('in_heat'): flags |= 0x01
        if data.get('high_temp'): flags |= 0x02
        if data.get('low_activity'): flags |= 0x04
        if data.get('eating'): flags |= 0x08
        if data.get('ruminating'): flags |= 0x10
        if data.get('lying'): flags |= 0x20
        if data.get('alert_active'): flags |= 0x40
        if data.get('gps_fix'): flags |= 0x80
        payload += struct.pack('<B', flags)
        
        # GPS data if available
        if data.get('gps_fix') and 'latitude' in data and 'longitude' in data:
            lat = int(data['latitude'] * 1e6)
            lon = int(data['longitude'] * 1e6)
            payload += struct.pack('<ii', lat, lon)
        
        return payload
    
    @staticmethod
    def decode_payload(payload_bytes):
        """Decode binary payload to dictionary."""
        if len(payload_bytes) < 15:
            raise ValueError("Payload too short")
        
        result = {}
        offset = 0
        
        # Header
        header = payload_bytes[offset]
        result['format_version'] = (header >> 6) & 0x03
        result['message_type'] = (header >> 4) & 0x03
        offset += 1
        
        # Timestamp
        result['timestamp'] = struct.unpack_from('<I', payload_bytes, offset)[0]
        offset += 4
        
        # Battery
        result['battery_level'] = payload_bytes[offset]
        offset += 1
        
        # Activity
        result['activity'] = struct.unpack_from('<H', payload_bytes, offset)[0]
        offset += 2
        
        # Steps
        result['steps'] = struct.unpack_from('<H', payload_bytes, offset)[0]
        offset += 2
        
        # Rumination
        result['rumination_minutes'] = struct.unpack_from('<H', payload_bytes, offset)[0]
        offset += 2
        
        # Temperature
        temp_raw = struct.unpack_from('<H', payload_bytes, offset)[0]
        result['temperature'] = temp_raw / 10.0
        offset += 2
        
        # Flags
        flags = payload_bytes[offset]
        result['in_heat'] = bool(flags & 0x01)
        result['high_temp'] = bool(flags & 0x02)
        result['low_activity'] = bool(flags & 0x04)
        result['eating'] = bool(flags & 0x08)
        result['ruminating'] = bool(flags & 0x10)
        result['lying'] = bool(flags & 0x20)
        result['alert_active'] = bool(flags & 0x40)
        result['gps_fix'] = bool(flags & 0x80)
        offset += 1
        
        # GPS
        if result['gps_fix'] and len(payload_bytes) >= offset + 8:
            lat, lon = struct.unpack_from('<ii', payload_bytes, offset)
            result['latitude'] = lat / 1e6
            result['longitude'] = lon / 1e6
        
        return result
```

---

## Document Control

### Approval Status

| Version | Date | Author | Reviewer | Status |
|---------|------|--------|----------|--------|
| 1.0 | 2026-01-31 | IoT Engineer | Veterinarian | Draft |

### Distribution List

| Role | Name | Department |
|------|------|------------|
| IoT Architect | [Name] | Technology |
| Veterinarian | [Name] | Animal Health |
| Farm Manager | [Name] | Operations |
| IT Manager | [Name] | Technology |
| Procurement | [Name] | Supply Chain |

---

*End of Document I-007: Wearable Device Integration*

*Smart Dairy Ltd. - Smart Web Portal System*
*Confidential - Internal Use Only*
