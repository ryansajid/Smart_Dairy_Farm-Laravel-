# SMART DAIRY LTD.
## TEST CASES - FARM MANAGEMENT MODULE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | G-009 |
| **Version** | 1.0 |
| **Date** | June 15, 2026 |
| **Author** | QA Engineer |
| **Owner** | QA Engineer |
| **Reviewer** | QA Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Test Case Overview](#2-test-case-overview)
3. [Herd Management Test Cases](#3-herd-management-test-cases)
4. [Milk Production Test Cases](#4-milk-production-test-cases)
5. [Health Management Test Cases](#5-health-management-test-cases)
6. [Reproduction Management Test Cases](#6-reproduction-management-test-cases)
7. [Feed Management Test Cases](#7-feed-management-test-cases)
8. [Mobile App Test Cases](#8-mobile-app-test-cases)
9. [Offline Functionality Test Cases](#9-offline-functionality-test-cases)
10. [IoT Integration Test Cases](#10-iot-integration-test-cases)
11. [Reporting & Analytics Test Cases](#11-reporting--analytics-test-cases)
12. [Appendices](#12-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides comprehensive test cases for the Smart Dairy Farm Management Module. The test cases ensure that all farm operations, including herd management, milk production tracking, health monitoring, breeding management, and mobile field operations function correctly and reliably.

### 1.2 Scope

| Component | Coverage |
|-----------|----------|
| Herd Management | Animal registration, profiles, lifecycle tracking |
| Milk Production | Daily recording, quality tracking, trends |
| Health Management | Vaccinations, treatments, veterinary records |
| Reproduction | Breeding, pregnancy, calving records |
| Feed Management | Inventory, rations, consumption tracking |
| Mobile App | Field data entry, RFID scanning, offline mode |
| IoT Integration | Milk meters, sensors, automated data |
| Reporting | Production reports, analytics, dashboards |

### 1.3 Test Environment

| Environment | URL | Purpose |
|-------------|-----|---------|
| QA Web | https://farm-qa.smartdairybd.com | Web portal testing |
| QA Mobile | APK/IPA Test Build | Mobile app testing |
| Staging | https://farm-staging.smartdairybd.com | UAT |
| IoT Sandbox | MQTT Test Broker | Sensor testing |

---

## 2. TEST CASE OVERVIEW

### 2.1 Test Case Summary

| Module | Total Cases | Critical | High | Medium | Low |
|--------|-------------|----------|------|--------|-----|
| Herd Management | 35 | 8 | 14 | 10 | 3 |
| Milk Production | 30 | 8 | 12 | 8 | 2 |
| Health Management | 25 | 6 | 10 | 7 | 2 |
| Reproduction Management | 25 | 6 | 10 | 7 | 2 |
| Feed Management | 20 | 4 | 8 | 6 | 2 |
| Mobile App | 30 | 8 | 12 | 8 | 2 |
| Offline Functionality | 20 | 5 | 8 | 5 | 2 |
| IoT Integration | 20 | 5 | 8 | 5 | 2 |
| Reporting & Analytics | 15 | 3 | 6 | 4 | 2 |
| **TOTAL** | **240** | **53** | **98** | **60** | **19** |

### 2.2 User Roles for Testing

| Role | Permissions | Test Focus |
|------|-------------|------------|
| Farm Manager | Full access | All modules, reports |
| Farm Supervisor | Limited admin | Herd, health, approvals |
| Farm Worker | Data entry only | Mobile app, milk recording |
| Veterinarian | Health records | Health, breeding |
| Accountant | Financial data | Costs, inventory |

---

## 3. HERD MANAGEMENT TEST CASES

### 3.1 Animal Registration

#### TC-FARM-001: New Animal Registration - Web
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-001 |
| **Title** | Verify Animal Registration via Web Portal |
| **Priority** | Critical |

**Test Steps:**
1. Login as Farm Supervisor
2. Navigate to Herd > Animals
3. Click "Add New Animal"
4. Fill registration form:
   - Animal ID: SD-2026-0100
   - RFID Tag: 123456789012
   - Name: Laxmi (optional)
   - Breed: Holstein Friesian
   - Gender: Female
   - Birth Date: 15/03/2023
   - Weight: 450kg
   - Source: Farm Born
5. Upload photo
6. Save record

**Expected Results:**
- [ ] Form validates all required fields
- [ ] RFID uniqueness check passes
- [ ] Animal ID auto-generated if blank
- [ ] Photo uploads and displays
- [ ] Success message: "Animal SD-2026-0100 registered successfully"
- [ ] Record appears in animal list
- [ ] QR code generated for animal tag

---

#### TC-FARM-002: RFID Tag Scanning - Mobile
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-001 |
| **Title** | Verify RFID Scanning in Mobile App |
| **Priority** | Critical |

**Test Steps:**
1. Open Smart Dairy Farm mobile app
2. Login as Farm Worker
3. Navigate to "Register Animal"
4. Tap "Scan RFID"
5. Hold phone near RFID tag
6. Verify data capture

**Expected Results:**
- [ ] Camera/RFID reader activates
- [ ] "Beep" sound on successful scan
- [ ] RFID number auto-fills in form
- [ ] Animal details load if existing tag
- [ ] Option to register new if not found
- [ ] Works in low-light conditions

---

#### TC-FARM-003: Animal Profile View
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-001 |
| **Title** | Verify Complete Animal Profile Display |
| **Priority** | High |

**Test Steps:**
1. Search for animal: SD-2024-0012
2. Click on animal record
3. Review all profile tabs

**Expected Results:**
- [ ] **Overview Tab:**
  - Animal photo, ID, name
  - Current status (Active/Pregnant/Lactating)
  - Age calculation (2 years, 4 months)
  - Current weight, last weigh date
  - Location (Barn A, Pen 3)
  
- [ ] **Production Tab:**
  - Today's milk yield
  - 30-day average
  - 305-day projected yield
  - Lactation curve graph
  
- [ ] **Health Tab:**
  - Last health check date
  - Vaccination status
  - Current treatments (if any)
  - Health alerts
  
- [ ] **Breeding Tab:**
  - Breeding history
  - Current pregnancy status
  - Calving history
  - Heat detection alerts
  
- [ ] **Timeline:**
  - Chronological history
  - All events (births, treatments, moves)

---

#### TC-FARM-004: Animal Status Updates
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-002 |
| **Title** | Verify Animal Status Lifecycle |
| **Priority** | High |

**Test Steps:**
1. Open animal profile
2. Update status through lifecycle stages
3. Record each transition

**Expected Results:**
| Transition | Required Fields | Alerts |
|------------|-----------------|--------|
| Calf → Heifer | Date, weight | Feeding schedule |
| Heifer → Cow | First heat date, breeding | Breeding reminder |
| Cow → Pregnant | Breeding date, sire | Due date calculation |
| Pregnant → Dry | Dry-off date | Pre-calving prep |
| Cow → Sold | Sale date, buyer, price | Inventory update |
| Any → Deceased | Death date, cause, disposal | Mortality report |

---

#### TC-FARM-005: Bulk Animal Import
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-001 |
| **Title** | Verify CSV Bulk Import |
| **Priority** | Medium |

**Test Steps:**
1. Download template CSV
2. Fill with 50 animal records
3. Upload CSV
4. Verify import
5. Check error handling

**Expected Results:**
- [ ] Template has all required columns
- [ ] Validation errors highlighted
- [ ] Successful import confirmation
- [ ] Summary: 48 imported, 2 failed
- [ ] Error report downloadable
- [ ] Imported animals appear in list

---

### 3.2 Animal Movement & Location

#### TC-FARM-010: Animal Location Transfer
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-002 |
| **Title** | Verify Animal Location Tracking |
| **Priority** | High |

**Test Steps:**
1. Select multiple animals
2. Click "Move Animals"
3. Select destination: Barn B, Pen 2
4. Record move reason
5. Confirm transfer

**Expected Results:**
- [ ] Move date defaults to today
- [ ] Reason selection: Rotation, Health, Maintenance
- [ ] Previous location logged in history
- [ ] Animals no longer show in old location
- [ ] Movement report generated

---

#### TC-FARM-011: Group Management
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-002 |
| **Title** | Verify Animal Grouping |
| **Priority** | Medium |

**Test Steps:**
1. Create new group: "High Producers"
2. Add animals to group
3. Apply group filter
4. Run group report

**Expected Results:**
- [ ] Groups: By lactation, By health status, Custom groups
- [ ] Animals can be in multiple groups
- [ ] Group-based feeding plans
- [ ] Group performance comparison

---

## 4. MILK PRODUCTION TEST CASES

### 4.1 Daily Recording

#### TC-FARM-030: Morning Milk Recording - Mobile
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-003 |
| **Title** | Verify Morning Milk Recording |
| **Priority** | Critical |

**Test Steps:**
1. Open mobile app at 6:00 AM
2. Navigate to "Record Milk"
3. Select session: Morning
4. Scan cow RFID: SD-2024-0012
5. Enter yield: 15.5 liters
6. Enter fat %: 3.8
7. Enter SNF %: 8.5
8. Record temperature: 38.5°C
9. Save record

**Expected Results:**
- [ ] Session auto-suggested by time
- [ ] Cow details display after scan
- [ ] Quick entry with +/- buttons
- [ ] Previous day's yield shown for reference
- [ ] Offline save if no connectivity
- [ ] Sync status indicator
- [ ] Success confirmation

---

#### TC-FARM-031: Milk Quality Recording
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-003 |
| **Title** | Verify Milk Quality Parameters |
| **Priority** | High |

**Test Steps:**
1. Record milk production
2. Enter quality test results
3. Save and verify

**Expected Results:**
| Parameter | Valid Range | Alert Threshold |
|-----------|-------------|-----------------|
| Fat % | 3.0 - 5.0% | < 2.5% |
| SNF % | 7.5 - 9.5% | < 7.0% |
| Density | 1.028 - 1.034 | Outside range |
| pH | 6.4 - 6.8 | < 6.3 or > 6.9 |
| Temperature | 35-39°C | > 40°C |
| Somatic Cell Count | < 200,000/ml | > 400,000/ml |

- [ ] Alert generated if values outside normal
- [ ] Quality trend graph updated
- [ ] Payment calculation uses quality data

---

#### TC-FARM-032: Bulk Tank Recording
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-003 |
| **Title** | Verify Bulk Milk Recording |
| **Priority** | High |

**Test Steps:**
1. Navigate to Milk Recording > Bulk
2. Select tank: Tank A
3. Enter total volume: 450 liters
4. Record quality (composite sample)
5. Save

**Expected Results:**
- [ ] Tank inventory updated
- [ ] Individual cow sum vs bulk comparison
- [ ] Discrepancy alert if > 5% difference
- [ ] Cold storage temperature logged

---

### 4.2 Production Analysis

#### TC-FARM-040: Production Dashboard
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-003 |
| **Title** | Verify Production Analytics Dashboard |
| **Priority** | High |

**Test Steps:**
1. Login as Farm Manager
2. Open Dashboard
3. Review production widgets
4. Change date range

**Expected Results:**
- [ ] **Today's Summary:**
  - AM milking: 425 liters (90% complete)
  - PM milking: Pending
  - Top cow: SD-2024-0012 (18L)
  - Bottom 10% flagged
  
- [ ] **Trends:**
  - 7-day rolling average
  - Comparison to last week
  - Year-over-year comparison
  
- [ ] **Alerts:**
  - Sudden yield drop cows
  - Cows not milked today
  - Quality issues

---

#### TC-FARM-041: Lactation Curve
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-003 |
| **Title** | Verify Lactation Curve Display |
| **Priority** | Medium |

**Test Steps:**
1. Open cow profile
2. View lactation curve
3. Compare to standard curve

**Expected Results:**
- [ ] Curve shows daily yield over lactation
- [ ] Peak yield marked
- [ ] Persistency calculated
- [ ] 305-day projection
- [ ] Comparison to breed average
- [ ] Previous lactation overlay (if available)

---

## 5. HEALTH MANAGEMENT TEST CASES

### 5.1 Health Events

#### TC-FARM-050: Health Incident Recording
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-004 |
| **Title** | Verify Health Issue Recording |
| **Priority** | Critical |

**Test Steps:**
1. Navigate to cow profile
2. Click "Add Health Event"
3. Record incident:
   - Date: Today
   - Type: Mastitis
   - Severity: Moderate
   - Symptoms: Swollen udder, clots in milk
   - Temperature: 39.8°C
   - Photo: Uploaded
4. Start treatment protocol
5. Assign veterinarian

**Expected Results:**
- [ ] Health alert generated
- [ ] Milk withdrawal period calculated
- [ ] Treatment protocol suggested
- [ ] Vet notification sent
- [ ] Follow-up scheduled
- [ ] Animal flagged in milking list

---

#### TC-FARM-051: Treatment Recording
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-004 |
| **Title** | Verify Treatment Administration |
| **Priority** | High |

**Test Steps:**
1. Open active health case
2. Add treatment:
   - Medicine: Antibiotic X
   - Dosage: 20ml
   - Route: Intramuscular
   - Site: Neck
   - Administrator: Farm Worker A
3. Record administration
4. Set follow-up

**Expected Results:**
- [ ] Medicine deducted from inventory
- [ ] Batch number recorded
- [ ] Withdrawal period calculated
- [ ] Next dose reminder set
- [ ] Cost added to animal expenses

---

### 5.2 Vaccination Management

#### TC-FARM-060: Vaccination Schedule
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-005 |
| **Title** | Verify Vaccination Schedule |
| **Priority** | Critical |

**Test Steps:**
1. View herd vaccination calendar
2. Check upcoming vaccinations
3. Mark vaccination as done
4. Verify updates

**Expected Results:**
| Vaccine | Frequency | Due Date Alert |
|---------|-----------|----------------|
| FMD | Every 6 months | 30 days before |
| BVD | Annually | 30 days before |
| IBR | Annually | 30 days before |
| HS | Annually | 30 days before |
| Anthrax | Annually | 14 days before |

- [ ] Color-coded calendar view
- [ ] Overdue vaccinations highlighted red
- [ ] Batch number tracking
- [ ] Next due date auto-calculated

---

#### TC-FARM-061: Mass Vaccination Entry
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-005 |
| **Title** | Verify Bulk Vaccination Recording |
| **Priority** | Medium |

**Test Steps:**
1. Select FMD vaccination
2. Select all eligible animals
3. Record batch number: FMD-2026-001
4. Record date
5. Mass save

**Expected Results:**
- [ ] All selected animals updated
- [ ] Next due dates calculated (+6 months)
- [ ] Vaccination report generated
- [ ] Compliance certificate updated

---

## 6. REPRODUCTION MANAGEMENT TEST CASES

### 6.1 Breeding Records

#### TC-FARM-070: Heat Detection Recording
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-REPRO-001 |
| **Title** | Verify Heat Detection Entry |
| **Priority** | High |

**Test Steps:**
1. Observe heat signs in cow SD-2024-0015
2. Record heat detection:
   - Date/time: Today 6:00 AM
   - Signs: Mounting, bellowing, clear mucus
   - Intensity: Strong
   - Days since calving: 65
3. Save record

**Expected Results:**
- [ ] Heat alert generated
- [ ] Optimal breeding window calculated (12-24 hours)
- [ ] SMS/notification to breeding manager
- [ ] AI technician assignment option
- [ ] Record appears in breeding calendar

---

#### TC-B2C-071: Artificial Insemination Recording
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-REPRO-001 |
| **Title** | Verify AI Service Recording |
| **Priority** | High |

**Test Steps:**
1. Navigate to breeding records
2. Add AI entry:
   - Cow: SD-2024-0015
   - Date: Today
   - Time: 4:00 PM
   - Sire: HF-Bull-2024-089 (Semen code: ABC123)
   - Technician: Dr. Karim
   - Method: AI
   - Cost: ৳2,500
3. Save record

**Expected Results:**
- [ ] Sire information auto-populated from semen code
- [ ] Expected calving date calculated (+283 days)
- [ ] Pregnancy check reminder set (45 days)
- [ ] Breeding cost added to animal
- [ ] Conception rate tracking updated

---

#### TC-FARM-072: Pregnancy Diagnosis
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-REPRO-002 |
| **Title** | Verify Pregnancy Check Recording |
| **Priority** | High |

**Test Steps:**
1. 45 days post-AI, vet performs PD
2. Record result:
   - Cow: SD-2024-0015
   - Check date: Today
   - Method: Ultrasound
   - Result: Pregnant
   - Days pregnant: 45
   - Expected calving: Calculated
   - Vet: Dr. Ahmed
3. Confirm dry-off date

**Expected Results:**
- [ ] Pregnancy status updated to "Confirmed"
- [ ] Expected calving date: [Calculated]
- [ ] Pregnancy timeline displayed
- [ ] Dry-off reminder set (60 days before calving)
- [ ] Close-up period alerts scheduled

---

#### TC-FARM-073: Calving Record
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-REPRO-003 |
| **Title** | Verify Calving Event Recording |
| **Priority** | Critical |

**Test Steps:**
1. Cow SD-2024-0015 gives birth
2. Record calving:
   - Date/time: Today 3:30 AM
   - Calving ease: Normal (Score 2)
   - Assistance: None
   - Calf sex: Female
   - Calf weight: 35kg
   - Calf health: Good
   - Colostrum given: Yes
   - Placenta expelled: Yes
3. Register calf

**Expected Results:**
- [ ] New calf auto-registered with ID
- [ ] Dam moved to fresh cow list
- [ ] Lactation number incremented
- [ ] Calving interval calculated
- [ ] Calf linked to dam record
- [ ] Breeding availability calculated (50-60 days)

---

## 7. FEED MANAGEMENT TEST CASES

### 7.1 Feed Inventory

#### TC-FARM-080: Feed Stock Entry
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-FEED-001 |
| **Title** | Verify Feed Inventory Recording |
| **Priority** | High |

**Test Steps:**
1. Receive 5000kg wheat bran
2. Record in system:
   - Feed type: Wheat Bran
   - Batch: WB-2026-045
   - Quantity: 5000kg
   - Supplier: ABC Feed Ltd.
   - Date received: Today
   - Expiry: 6 months
   - Cost: ৳45,000
   - Location: Feed Store A
3. Save

**Expected Results:**
- [ ] Stock level updated
- [ ] Batch tracked separately
- [ ] Expiry alert set (30 days before)
- [ ] Cost per kg calculated
- [ ] Supplier performance logged

---

#### TC-FARM-081: Daily Feed Consumption
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-FEED-003 |
| **Title** | Verify Daily Feed Recording |
| **Priority** | High |

**Test Steps:**
1. Record morning feeding:
   - Group: Lactating Cows
   - Feed: TMR Mix
   - Quantity offered: 2500kg
   - Quantity refused: 150kg
   - Date: Today
2. Record for other groups

**Expected Results:**
- [ ] Actual consumption calculated (2350kg)
- [ ] Per cow consumption calculated
- [ ] Feed efficiency calculated (kg milk/kg feed)
- [ ] Inventory deducted
- [ ] Reorder alert if below threshold

---

## 8. MOBILE APP TEST CASES

### 8.1 Field Data Entry

#### TC-FARM-100: Mobile Milk Recording
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | UR-FARM-004.1 |
| **Title** | Verify Complete Mobile Recording Flow |
| **Priority** | Critical |

**Test Steps:**
1. Worker logs into mobile app
2. Selects milk recording
3. Scans cow RFID
4. Enters milk yield
5. Takes photo of milk meter
6. Saves (offline)
7. Syncs when online

**Expected Results:**
- [ ] Login with PIN or biometric
- [ ] RFID scan works in farm lighting
- [ ] Photo evidence attached
- [ ] Offline save confirmation
- [ ] Auto-sync when network available
- [ ] Conflict resolution if duplicate

---

#### TC-FARM-101: Voice Notes
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | UR-FARM-005.3 |
| **Title** | Verify Voice Input for Observations |
| **Priority** | Medium |

**Test Steps:**
1. During health check
2. Tap microphone icon
3. Record: "Cow looks lethargic, reduced appetite"
4. Save recording

**Expected Results:**
- [ ] Voice recording saved locally
- [ ] Transcription generated (optional)
- [ ] Recording linked to animal record
- [ ] Playback available in web portal

---

## 9. OFFLINE FUNCTIONALITY TEST CASES

### 9.1 Offline Mode

#### TC-FARM-120: Offline Data Entry
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | UR-FARM-005.2 |
| **Title** | Verify Offline Recording and Sync |
| **Priority** | Critical |

**Test Steps:**
1. Enable airplane mode
2. Record 10 milk entries
3. Record 2 health events
4. Disable airplane mode
5. Wait for sync

**Expected Results:**
- [ ] Offline indicator visible
- [ ] Data saved to local SQLite database
- [ ] Queue count displayed
- [ ] Auto-sync on connection restore
- [ ] Sync progress indicator
- [ ] Conflict handling if server has newer data

---

#### TC-FARM-121: Conflict Resolution
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | UR-FARM-005.2 |
| **Title** | Verify Data Conflict Resolution |
| **Priority** | High |

**Test Steps:**
1. Worker A records offline: Cow 12, 15L
2. Worker B records online: Cow 12, 14L
3. Worker A syncs

**Expected Results:**
- [ ] Duplicate detection triggered
- [ ] Conflict alert to supervisor
- [ ] Both values visible for comparison
- [ ] Manual resolution required
- [ ] Audit trail preserved

---

## 10. IOT INTEGRATION TEST CASES

### 10.1 Milk Meter Integration

#### TC-FARM-140: Automated Milk Recording
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-003 |
| **Title** | Verify IoT Milk Meter Data |
| **Priority** | High |

**Test Steps:**
1. Cow enters milking parlor
2. RFID reader identifies cow
3. Milk meter records volume
4. Data sent via MQTT
5. Verify in system

**Expected Results:**
- [ ] Cow auto-identified
- [ ] Milk volume recorded to 0.1L precision
- [ ] Duration recorded
- [ ] Quality sensor data (if equipped)
- [ ] Alert if conductivity high (mastitis indicator)

---

#### TC-FARM-141: Environmental Sensors
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-IOT-001 |
| **Title** | Verify Barn Environment Monitoring |
| **Priority** | Medium |

**Test Steps:**
1. Check dashboard sensor readings
2. Trigger threshold breach
3. Verify alert

**Expected Results:**
| Sensor | Normal Range | Alert Threshold |
|--------|--------------|-----------------|
| Temperature | 15-25°C | > 28°C or < 10°C |
| Humidity | 50-70% | > 80% or < 40% |
| Ammonia | < 25 ppm | > 25 ppm |
| Air Quality | Good | Poor/Very Poor |

- [ ] Real-time dashboard updates
- [ ] SMS/email alerts on threshold breach
- [ ] Historical trend graphs
- [ ] Sensor battery level monitoring

---

## 11. REPORTING & ANALYTICS TEST CASES

### 11.1 Standard Reports

#### TC-FARM-160: Daily Production Report
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-003 |
| **Title** | Verify Daily Production Report Generation |
| **Priority** | High |

**Test Steps:**
1. Go to Reports > Production
2. Select date range: Last 7 days
3. Generate report
4. Export to PDF

**Expected Results:**
- [ ] Report sections:
  - Summary: Total milk, Avg per cow
  - AM/PM breakdown
  - Top 10 producers
  - Bottom 10 producers
  - New calvers this week
  - Dry cows list
  - Cows with yield drop > 10%
- [ ] PDF export formatted
- [ ] Excel export available

---

#### TC-FARM-161: Herd Health Report
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-HERD-004 |
| **Title** | Verify Health Status Report |
| **Priority** | High |

**Test Steps:**
1. Generate health report
2. Review all sections
3. Share with vet

**Expected Results:**
- [ ] Current treatments list
- [ ] Upcoming vaccinations
- [ ] Overdue vaccinations
- [ ] Mastitis cases this month
- [ ] Mortality summary
- [ ] Medicine usage
- [ ] Vet visit schedule

---

## 12. APPENDICES

### Appendix A: Sample Animal Data

| Animal ID | RFID | Breed | DOB | Status |
|-----------|------|-------|-----|--------|
| SD-2024-0012 | 123456789012 | HF | 15/03/2022 | Lactating |
| SD-2024-0015 | 123456789013 | Jersey | 20/04/2022 | Pregnant |
| SD-2026-0020 | 123456789014 | HF | 10/01/2026 | Calf |

### Appendix B: MQTT Test Topics

| Topic | Purpose |
|-------|---------|
| farm/milk-meter/data | Milk meter readings |
| farm/rfid/reader | RFID scan events |
| farm/sensor/temp | Temperature readings |
| farm/sensor/humidity | Humidity readings |

### Appendix C: Mobile Test Devices

| Device | OS | Screen | Priority |
|--------|-----|--------|----------|
| Samsung A14 | Android 14 | 6.6" | High |
| Redmi Note 12 | Android 13 | 6.67" | High |
| iPhone 13 | iOS 17 | 6.1" | High |
| iPhone SE | iOS 17 | 4.7" | Medium |

---

**END OF TEST CASES - FARM MANAGEMENT**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | June 15, 2026 | QA Engineer | Initial version |
