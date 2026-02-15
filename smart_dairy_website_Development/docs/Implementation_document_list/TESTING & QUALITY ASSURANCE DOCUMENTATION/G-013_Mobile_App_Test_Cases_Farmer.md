# Document: G-013

## Mobile App Test Cases - Farmer

**For Smart Dairy Ltd. Farm Management System**

---

## Document Information

| Field | Value |
|-------|-------|
| **Document ID** | G-013 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | QA Engineer |
| **Owner** | QA Lead |
| **Reviewer** | Farm Operations Manager |
| **Status** | Draft |
| **Classification** | Internal Use |

---

## Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | 2026-01-31 | QA Engineer | Initial draft - Farmer Mobile App Test Cases |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Testing Environment](#2-testing-environment)
3. [Farmer User Profile & Special Considerations](#3-farmer-user-profile--special-considerations)
4. [Test Case Summary](#4-test-case-summary)
5. [Detailed Test Cases](#5-detailed-test-cases)
   - 5.1 Daily Milk Recording
   - 5.2 Animal Health Logging
   - 5.3 Feed Management
   - 5.4 Breeding Records
   - 5.5 RFID Tag Scanning
   - 5.6 Photo Documentation
   - 5.7 Voice Input (Bengali)
   - 5.8 Offline Data Entry
   - 5.9 Sync Management
   - 5.10 Dashboard & Alerts
6. [Usability Test Cases](#6-usability-test-cases)
7. [Performance & Battery Test Cases](#7-performance--battery-test-cases)
8. [Security Test Cases](#8-security-test-cases)
9. [Defect Tracking](#9-defect-tracking)
10. [Appendices](#10-appendices)

---

## 1. Introduction

### 1.1 Purpose
This document contains comprehensive test cases for the Smart Dairy Farmer Mobile Application. It covers all functional and non-functional testing scenarios designed specifically for farm workers operating in rural Bangladesh.

### 1.2 Scope

**In Scope:**
- All farmer-facing mobile app features
- Usability testing for farm workers
- Offline functionality testing
- Voice input validation (Bengali)
- RFID scanning functionality
- Photo documentation features
- Performance on low-end devices
- Battery efficiency testing

**Out of Scope:**
- Backend API testing (covered in separate document)
- Admin panel testing
- Integration with third-party hardware
- Network infrastructure testing

### 1.3 Target Audience
- QA Engineers
- Testers
- Farm Operations Manager
- Development Team
- Product Owner

### 1.4 References
- Farmer App Requirements Specification
- UI/UX Design Guidelines for Farmers
- Bengali Voice Input Specifications
- RFID Scanner Integration Guide
- Offline Sync Architecture Document

---

## 2. Testing Environment

### 2.1 Required Test Devices

| Device Category | Specifications | Quantity |
|-----------------|----------------|----------|
| Low-end Android | Android 8+, 2GB RAM, 16GB Storage | 3 |
| Mid-range Android | Android 10+, 4GB RAM, 64GB Storage | 2 |
| Entry-level Device | Android Go, 1GB RAM | 1 |
| Tablet | 10-inch, Android 11+ | 1 |

### 2.2 Test Environment Setup

**Physical Test Environment:**
- Indoor farm office (controlled lighting)
- Outdoor farm area (direct sunlight, variable lighting)
- Shaded barn area (low light)
- Evening/night conditions (artificial lighting)

**Environmental Conditions:**
- Temperature: 25°C - 40°C
- Humidity: 60% - 90%
- Dust exposure simulation

### 2.3 Software Prerequisites

| Component | Version |
|-----------|---------|
| Farmer Mobile App | Latest build |
| RFID Reader Firmware | v2.1+ |
| Test Data Set | Farm Test Dataset v1.0 |
| Bengali Voice Samples | Regional dialects collection |

### 2.4 Test Data Requirements

- Minimum 50 animal records
- 30 days of historical milk data
- 20 health incident records
- Complete feed inventory data
- Breeding records for 15 animals

---

## 3. Farmer User Profile & Special Considerations

### 3.1 Primary User Profile

**Typical Farm Worker:**
- Age: 25-55 years
- Education: Primary to secondary level
- Tech familiarity: Basic smartphone usage
- Primary language: Bengali (various dialects)
- Working conditions: Outdoor, hands often occupied or dirty
- May wear work gloves during operations

### 3.2 Design Considerations for Testing

| Consideration | Testing Implication |
|---------------|---------------------|
| Large touch targets (farmers may wear gloves) | Minimum 48dp touch targets, test with gloves |
| High contrast UI (outdoor sunlight) | Test visibility in direct sunlight |
| Bengali voice input accuracy | Test with various dialects and accents |
| Offline-first architecture | Test without network, validate sync |
| Simple, intuitive navigation | Minimal taps to complete tasks |
| Battery efficiency (rural areas) | Extended battery drain testing |
| Works on low-end devices | Performance testing on entry-level phones |
| Voice announcements | Audio feedback verification |
| Emergency alert buttons | Quick access testing |

---

## 4. Test Case Summary

### 4.1 Test Case Distribution by Feature

| Feature | Total Cases | Priority High | Priority Medium | Priority Low |
|---------|-------------|---------------|-----------------|--------------|
| Daily Milk Recording | 6 | 4 | 2 | 0 |
| Animal Health Logging | 5 | 3 | 2 | 0 |
| Feed Management | 4 | 2 | 2 | 0 |
| Breeding Records | 4 | 3 | 1 | 0 |
| RFID Tag Scanning | 5 | 4 | 1 | 0 |
| Photo Documentation | 4 | 2 | 2 | 0 |
| Voice Input (Bengali) | 6 | 4 | 2 | 0 |
| Offline Data Entry | 5 | 4 | 1 | 0 |
| Sync Management | 5 | 3 | 2 | 0 |
| Dashboard & Alerts | 5 | 3 | 2 | 0 |
| Usability | 5 | 3 | 2 | 0 |
| Performance & Battery | 4 | 2 | 2 | 0 |
| Security | 4 | 3 | 1 | 0 |
| **TOTAL** | **62** | **40** | **22** | **0** |

### 4.2 Test Priority Definitions

- **High (P1)**: Critical functionality - App cannot be released without these passing
- **Medium (P2)**: Important functionality - Should be fixed before release
- **Low (P3)**: Nice to have - Can be addressed in future updates

### 4.3 Test Type Legend

| Code | Type | Description |
|------|------|-------------|
| FT | Functional Test | Core feature functionality |
| UT | Usability Test | User experience and accessibility |
| PT | Performance Test | Speed, responsiveness, resource usage |
| CT | Compatibility Test | Device and environment compatibility |
| ST | Security Test | Data protection and access control |
| LT | Localization Test | Language and regional adaptation |

---

## 5. Detailed Test Cases

### 5.1 Daily Milk Recording

#### TC-MILK-001: Record Morning Milk Yield

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MILK-001 |
| **Feature** | Daily Milk Recording |
| **Type** | FT (Functional Test) |
| **Priority** | High (P1) |
| **Preconditions** | User logged in, RFID scanner connected, animal registered |

**Test Steps:**
1. Navigate to "Milk Recording" screen
2. Tap "Scan RFID" button
3. Scan animal's RFID tag
4. Verify animal details display correctly
5. Enter morning milk yield (e.g., 12.5 liters)
6. Select milk quality grade (A/B/C)
7. Tap "Save Record"

**Expected Result:**
- Record saved successfully
- Confirmation message displayed in Bengali
- Timestamp automatically recorded
- Data appears in today's milk summary
- Voice announcement confirms save

**Pass/Fail Criteria:**
- ✓ Record saved within 3 seconds
- ✓ All data fields captured correctly
- ✓ Timestamp accurate to the minute

---

#### TC-MILK-002: Record Evening Milk Yield with Voice Input

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MILK-002 |
| **Feature** | Daily Milk Recording + Voice Input |
| **Type** | FT + LT |
| **Priority** | High (P1) |
| **Preconditions** | User logged in, microphone access granted |

**Test Steps:**
1. Navigate to "Milk Recording" screen
2. Scan animal RFID tag
3. Tap microphone icon
4. Speak milk quantity in Bengali: "বারো লিটার দুধ" (12 liters milk)
5. Verify voice recognition converts to numeric value
6. Review and confirm entry
7. Save record

**Expected Result:**
- Voice input correctly recognized (12.0 liters)
- Numeric value displayed in input field
- Bengali text transcription shown for verification
- Record saved with voice-captured data

**Pass/Fail Criteria:**
- ✓ Voice recognition accuracy ≥ 90%
- ✓ Numeric conversion correct
- ✓ Response time < 5 seconds

---

#### TC-MILK-003: Validate Milk Yield Range

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MILK-003 |
| **Feature** | Data Validation |
| **Type** | FT |
| **Priority** | High (P1) |
| **Preconditions** | User on milk recording screen |

**Test Steps:**
1. Scan valid animal RFID
2. Enter milk yield value: 0.5 liters
3. Verify warning message for low yield
4. Clear and enter: 45 liters
5. Verify warning message for unusually high yield
6. Clear and enter: 15 liters
7. Verify no warning (within normal range)

**Expected Result:**
- System validates input against breed-specific ranges
- Visual warning for outliers (yellow icon)
- Hard block for impossible values (>50 liters)
- Contextual help text in Bengali

**Validation Ranges:**
| Breed | Min (L) | Normal Max (L) | Hard Max (L) |
|-------|---------|----------------|--------------|
| Local | 2.0 | 8.0 | 15.0 |
| Cross | 5.0 | 15.0 | 30.0 |
| HF/Jersey | 8.0 | 25.0 | 50.0 |

---

#### TC-MILK-004: Record Milk with Gloves (Touch Target Test)

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MILK-004 |
| **Feature** | Usability - Glove Operation |
| **Type** | UT + CT |
| **Priority** | High (P1) |
| **Preconditions** | User wearing work gloves (rubber/leather) |

**Test Steps:**
1. Wear standard farm work gloves
2. Navigate to milk recording screen
3. Tap all interactive elements:
   - RFID scan button (48dp minimum)
   - Numeric keypad buttons (56dp minimum)
   - Save button (56dp minimum)
   - Back button
4. Enter complete milk record

**Expected Result:**
- All buttons respond to gloved touch
- No accidental taps on adjacent elements
- Sufficient spacing between touch targets (minimum 8dp)
- Haptic feedback confirms touches

---

#### TC-MILK-005: View Milk History with Filters

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MILK-005 |
| **Feature** | Milk Records View |
| **Type** | FT |
| **Priority** | Medium (P2) |
| **Preconditions** | Historical milk data exists |

**Test Steps:**
1. Navigate to "Milk History" screen
2. Verify last 7 days displayed by default
3. Tap "Filter" button
4. Select date range (last 30 days)
5. Select specific animal from dropdown
6. Apply filters
7. Verify filtered results display

**Expected Result:**
- Date range filter works correctly
- Animal-specific filter functional
- Summary statistics calculated (total, average, trend)
- Simple bar chart displays daily yields

---

#### TC-MILK-006: Bulk Milk Entry for Multiple Animals

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MILK-006 |
| **Feature** | Bulk Data Entry |
| **Type** | FT |
| **Priority** | Medium (P2) |
| **Preconditions** | Multiple animals registered in system |

**Test Steps:**
1. Navigate to "Bulk Milk Entry"
2. Select milking session (Morning/Evening)
3. Scan first animal RFID
4. Enter milk quantity, tap "Next"
5. Scan second animal RFID
6. Enter quantity, tap "Next"
7. Continue for 5 animals
8. Review summary screen
9. Confirm and save all records

**Expected Result:**
- Streamlined entry flow (no return to main menu)
- Progress indicator shows "3 of 5 completed"
- Summary page lists all entries
- Bulk save completes within 5 seconds

---

### 5.2 Animal Health Logging

#### TC-HEALTH-001: Log Health Incident with Photo

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-HEALTH-001 |
| **Feature** | Health Logging + Photo Documentation |
| **Type** | FT |
| **Priority** | High (P1) |
| **Preconditions** | User logged in, camera permission granted |

**Test Steps:**
1. Navigate to "Health Log" screen
2. Scan animal RFID
3. Select incident type: "Mastitis"
4. Tap "Add Photo"
5. Capture photo of affected udder
6. Enter symptoms description (Bengali text/voice)
7. Select severity: Moderate
8. Tap "Save Health Record"

**Expected Result:**
- Photo captured and compressed (<500KB)
- Health record saved with photo attachment
- Severity indicator color-coded (Red/Yellow/Green)
- Auto-notification sent to veterinarian

---

#### TC-HEALTH-002: Record Vaccination Event

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-HEALTH-002 |
| **Feature** | Vaccination Tracking |
| **Type** | FT |
| **Priority** | High (P1) |
| **Preconditions** | Vaccination schedule configured |

**Test Steps:**
1. Navigate to "Vaccination" screen
2. Scan animal RFID
3. Select vaccine type from dropdown
4. Verify vaccination schedule displayed
5. Enter batch number manually or scan barcode
6. Enter administrator name
7. Set next due date (auto-calculated + editable)
8. Save vaccination record

**Expected Result:**
- Vaccination recorded with all details
- Next due date calculated based on vaccine type
- Animal health card updated
- Reminder scheduled for next dose

---

#### TC-HEALTH-003: View Animal Health History

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-HEALTH-003 |
| **Feature** | Health History |
| **Type** | FT |
| **Priority** | Medium (P2) |
| **Preconditions** | Health records exist for animal |

**Test Steps:**
1. Navigate to animal profile
2. Tap "Health History" tab
3. Verify chronological list of incidents
4. Tap on specific incident
5. View detailed information including photos
6. Scroll through historical records

**Expected Result:**
- Complete health timeline displayed
- Photos viewable in full screen
- Filter by incident type functional
- Export option available (PDF)

---

#### TC-HEALTH-004: Emergency Health Alert

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-HEALTH-004 |
| **Feature** | Emergency Alert |
| **Type** | FT + UT |
| **Priority** | High (P1) |
| **Preconditions** | Emergency contacts configured |

**Test Steps:**
1. Locate red "Emergency" button on home screen
2. Tap emergency button
3. Select emergency type: "Animal Critical Condition"
4. Scan affected animal RFID
5. Tap "Send Emergency Alert"
6. Verify confirmation dialog
7. Confirm to send

**Expected Result:**
- Emergency alert sent within 10 seconds
- SMS sent to veterinarian and farm manager
- Push notification to admin users
- GPS coordinates included in alert
- Visual and audio confirmation of alert sent

---

#### TC-HEALTH-005: Log Treatment Follow-up

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-HEALTH-005 |
| **Feature** | Treatment Tracking |
| **Type** | FT |
| **Priority** | Medium (P2) |
| **Preconditions** | Existing health incident logged |

**Test Steps:**
1. Open existing health incident
2. Tap "Add Treatment"
3. Enter medication name (voice or text)
4. Enter dosage and frequency
5. Set treatment duration
6. Add follow-up notes
7. Mark incident status: "Under Treatment"

**Expected Result:**
- Treatment details linked to original incident
- Medication schedule created
- Withholding period calculated for milk
- Follow-up reminders scheduled

---

### 5.3 Feed Management

#### TC-FEED-001: Record Daily Feed Consumption

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-FEED-001 |
| **Feature** | Feed Consumption |
| **Type** | FT |
| **Priority** | High (P1) |
| **Preconditions** | Feed types configured in system |

**Test Steps:**
1. Navigate to "Feed Management"
2. Select feeding group or scan individual animal
3. Select feed type: "Green Fodder"
4. Enter quantity consumed: 25 kg
5. Select time of feeding: Morning
6. Tap "Record Consumption"

**Expected Result:**
- Feed consumption recorded
- Inventory automatically deducted
- Cost calculated based on feed price
- Daily feed summary updated

---

#### TC-FEED-002: Check Feed Inventory Status

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-FEED-002 |
| **Feature** | Feed Inventory |
| **Type** | FT |
| **Priority** | Medium (P2) |
| **Preconditions** | Feed inventory data exists |

**Test Steps:**
1. Navigate to "Feed Inventory"
2. View current stock levels for all feed types
3. Identify items with low stock (red indicator)
4. Tap on specific feed type
5. View consumption trends
6. Check estimated days remaining

**Expected Result:**
- Current stock displayed for all feed types
- Low stock warnings visible (red < 7 days, yellow < 14 days)
- Consumption trend chart displayed
- Days remaining calculated based on average consumption

---

#### TC-FEED-003: Receive Feed Delivery

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-FEED-003 |
| **Feature** | Feed Procurement |
| **Type** | FT |
| **Priority** | Medium (P2) |
| **Preconditions** | Feed types configured |

**Test Steps:**
1. Navigate to "Feed Inventory"
2. Tap "Receive Delivery"
3. Select feed type from list
4. Enter quantity received: 500 kg
5. Enter purchase price per kg
6. Enter supplier name
7. Capture photo of delivery receipt
8. Save delivery record

**Expected Result:**
- Inventory updated with new stock
- Purchase cost recorded
- Receipt photo attached
- Stock level indicator updated

---

#### TC-FEED-004: Feed Ration Calculator

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-FEED-004 |
| **Feature** | Feed Calculator |
| **Type** | FT |
| **Priority** | High (P1) |
| **Preconditions** | Animal profiles with weight/lactation data |

**Test Steps:**
1. Navigate to "Feed Calculator"
2. Scan animal RFID or select from list
3. Verify animal details (weight, lactation stage)
4. Tap "Calculate Ration"
5. View recommended feed composition
6. View quantities for each feed type
7. Tap "Use This Ration"

**Expected Result:**
- Ration calculated based on:
  - Animal weight
  - Milk production level
  - Lactation stage
  - Body condition score
- Daily quantities displayed in kg
- Cost estimate provided
- Feed mixing instructions shown

---

### 5.4 Breeding Records

#### TC-BREED-001: Record Heat Detection

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-BREED-001 |
| **Feature** | Heat Detection |
| **Type** | FT |
| **Priority** | High (P1) |
| **Preconditions** | Animal in breeding age, not pregnant |

**Test Steps:**
1. Navigate to "Breeding" screen
2. Scan animal RFID
3. Tap "Record Heat Signs"
4. Select observed signs (multiple selection):
   - Mounting other cows
   - Restlessness
   - Clear mucus discharge
   - Bellowing
5. Rate heat intensity: Strong
6. Save heat detection record

**Expected Result:**
- Heat record timestamped
- Alert created for optimal insemination window
- Expected ovulation time calculated
- Notification sent to AI technician
- Animal marked in "Heat" status

---

#### TC-BREED-002: Record Artificial Insemination

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-BREED-002 |
| **Feature** | AI Recording |
| **Type** | FT |
| **Priority** | High (P1) |
| **Preconditions** | Heat detected, AI technician available |

**Test Steps:**
1. Navigate to animal profile
2. Tap "Record AI"
3. Enter semen batch number (scan or manual)
4. Select bull breed/ID
5. Enter AI technician name
6. Record AI date and time
7. Save AI record

**Expected Result:**
- AI record saved with genetic information
- Pregnancy check date auto-calculated (45-60 days)
- Animal status changed to "Inseminated"
- Expected calving date calculated

---

#### TC-BREED-003: Record Pregnancy Check

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-BREED-003 |
| **Feature** | Pregnancy Diagnosis |
| **Type** | FT |
| **Priority** | High (P1) |
| **Preconditions** | AI performed 45+ days ago |

**Test Steps:**
1. Receive pregnancy check reminder
2. Navigate to animal profile
3. Tap "Record Pregnancy Check"
4. Select result: "Pregnant" or "Not Pregnant"
5. If pregnant, enter expected calving date
6. If not pregnant, record reason (optional)
7. Save result

**Expected Result:**
- Pregnancy status updated
- If pregnant: calving countdown begins, dry-off date calculated
- If not pregnant: animal returned to breeding pool, heat tracking resumes
- Appropriate alerts scheduled

---

#### TC-BREED-004: View Breeding Calendar

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-BREED-004 |
| **Feature** | Breeding Calendar |
| **Type** | FT |
| **Priority** | Medium (P2) |
| **Preconditions** | Breeding records exist |

**Test Steps:**
1. Navigate to "Breeding Calendar"
2. View monthly calendar view
3. Identify color-coded events:
   - Red: Heat expected
   - Blue: AI scheduled
   - Green: Pregnancy check due
   - Purple: Expected calving
4. Tap on specific date
5. View list of animals with events

**Expected Result:**
- Calendar displays all breeding-related events
- Color coding consistent and clear
- Drill-down to animal details functional
- Export/print option available

---

### 5.5 RFID Tag Scanning

#### TC-RFID-001: Successful RFID Scan in Good Lighting

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-RFID-001 |
| **Feature** | RFID Scanning |
| **Type** | FT + CT |
| **Priority** | High (P1) |
| **Preconditions** | RFID reader connected, animal tagged |

**Test Steps:**
1. Navigate to any screen requiring animal identification
2. Tap "Scan RFID" button
3. Hold scanner near animal's ear tag (5-10cm)
4. Wait for scan confirmation

**Expected Result:**
- Tag read within 2 seconds
- Animal details displayed immediately
- Beep sound confirms successful scan
- Visual confirmation (green checkmark)

---

#### TC-RFID-002: RFID Scan in Direct Sunlight

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-RFID-002 |
| **Feature** | RFID in Outdoor Conditions |
| **Type** | CT |
| **Priority** | High (P1) |
| **Preconditions** | Test conducted outdoors in direct sunlight (>10000 lux) |

**Test Steps:**
1. Position in direct sunlight
2. Ensure screen is visible (high brightness mode)
3. Tap "Scan RFID"
4. Scan animal tag
5. Verify scan success

**Expected Result:**
- Screen visible in bright sunlight
- RFID scan successful despite lighting
- No screen glare obscuring results
- Auto-brightness adjusts appropriately

---

#### TC-RFID-003: RFID Scan in Low Light (Barn)

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-RFID-003 |
| **Feature** | RFID in Low Light |
| **Type** | CT |
| **Priority** | High (P1) |
| **Preconditions** | Test in barn with minimal lighting (<50 lux) |

**Test Steps:**
1. Enter dimly lit barn area
2. Verify screen readable at low brightness
3. Tap "Scan RFID"
4. Scan animal tag
5. Verify scan success

**Expected Result:**
- Screen visible without eye strain
- RFID scan successful in low light
- No excessive screen brightness causing glare

---

#### TC-RFID-004: Manual Entry When RFID Fails

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-RFID-004 |
| **Feature** | Fallback Manual Entry |
| **Type** | FT |
| **Priority** | High (P1) |
| **Preconditions** | RFID reader disconnected or malfunctioning |

**Test Steps:**
1. Disconnect RFID reader or simulate failure
2. Tap "Scan RFID"
3. Observe error message
4. Tap "Enter ID Manually"
5. Type RFID tag number using numeric keypad
6. Tap "Search"

**Expected Result:**
- Clear error message when RFID unavailable
- Manual entry option prominently displayed
- Large numeric keypad for easy entry
- Animal found and displayed after manual entry

---

#### TC-RFID-005: Scan Damaged or Dirty Tag

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-RFID-005 |
| **Feature** | RFID Error Handling |
| **Type** | FT |
| **Priority** | Medium (P2) |
| **Preconditions** | Animal with partially damaged or dirty tag |

**Test Steps:**
1. Attempt to scan damaged/dirty tag
2. Observe scan failure
3. Try repositioning scanner
4. After 3 failed attempts
5. Use manual entry fallback
6. Report tag issue through app

**Expected Result:**
- Appropriate error message after failed scan
- Suggestion to clean tag or use manual entry
- Tag issue can be reported for maintenance
- Alternative identification methods available

---

### 5.6 Photo Documentation

#### TC-PHOTO-001: Capture Animal Photo in Daylight

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-PHOTO-001 |
| **Feature** | Photo Capture |
| **Type** | FT + CT |
| **Priority** | High (P1) |
| **Preconditions** | Camera permission granted, daylight conditions |

**Test Steps:**
1. Navigate to photo capture screen
2. Position animal in frame
3. Tap capture button
4. Review photo preview
5. Tap "Use Photo" or "Retake"
6. Save photo with record

**Expected Result:**
- Photo captured clearly
- Auto-focus functional
- Preview allows quality check
- Photo compressed and saved (<1MB)
- Timestamp and GPS coordinates embedded

---

#### TC-PHOTO-002: Photo Capture in Various Lighting Conditions

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-PHOTO-002 |
| **Feature** | Camera Adaptability |
| **Type** | CT |
| **Priority** | Medium (P2) |
| **Preconditions** | Test in different lighting scenarios |

**Test Steps:**
1. Test in bright sunlight
2. Test in shade/barn
3. Test at dusk
4. Test with artificial lighting
5. Capture photo in each condition
6. Review photo quality

**Expected Result:**
| Lighting Condition | Expected Quality |
|-------------------|------------------|
| Bright sunlight | Good, minimal overexposure |
| Shade/Barn | Acceptable with auto-adjustment |
| Dusk | Acceptable, flash if needed |
| Artificial light | Good with white balance adjustment |

---

#### TC-PHOTO-003: Attach Photo to Health Record

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-PHOTO-003 |
| **Feature** | Photo Attachment |
| **Type** | FT |
| **Priority** | High (P1) |
| **Preconditions** | Health incident being logged |

**Test Steps:**
1. Create new health record
2. Tap "Attach Photo"
3. Choose "Take New Photo" or "Select from Gallery"
4. If taking new: capture and confirm
5. If gallery: select existing photo
6. Photo attached to record

**Expected Result:**
- Multiple photos can be attached (max 5)
- Gallery access functional
- Photo thumbnail visible in record
- Full-size photo viewable on tap

---

#### TC-PHOTO-004: Photo Storage and Compression

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-PHOTO-004 |
| **Feature** | Photo Optimization |
| **Type** | PT |
| **Priority** | Medium (P2) |
| **Preconditions** | Device with limited storage |

**Test Steps:**
1. Capture high-resolution photo
2. Verify automatic compression
3. Check file size (<1MB)
4. Upload with sync
5. Verify original quality maintained on server
6. Check local storage usage

**Expected Result:**
- Local photo ≤ 1MB
- Upload completes within 30 seconds (3G)
- Original quality preserved on server
- Thumbnail generated for quick viewing
- Option to download original if needed

---

### 5.7 Voice Input (Bengali)

#### TC-VOICE-001: Voice Input for Milk Quantity

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-VOICE-001 |
| **Feature** | Voice Number Recognition |
| **Type** | LT |
| **Priority** | High (P1) |
| **Preconditions** | Microphone access granted, Bengali language selected |

**Test Steps:**
1. Navigate to milk recording screen
2. Tap microphone icon
3. Speak: "দশ দশমিক পাঁচ লিটার" (10.5 liters)
4. Verify recognized text displayed
5. Confirm numeric conversion: 10.5
6. Save using voice input

**Expected Result:**
- Voice recognized correctly (≥ 90% accuracy)
- Both Bengali text and number displayed
- Decimal point handled correctly
- Confirmation audio played

---

#### TC-VOICE-002: Voice Input with Different Bengali Dialects

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-VOICE-002 |
| **Feature** | Dialect Recognition |
| **Type** | LT |
| **Priority** | High (P1) |
| **Preconditions** | Testers with various Bengali dialects |

**Test Steps:**
1. Test with Standard Bengali speaker
2. Test with Rajshahi dialect speaker
3. Test with Chittagong dialect speaker
4. Test with Sylheti dialect speaker
5. Speak same command: "বারো লিটার দুধ"
6. Verify recognition accuracy for each

**Expected Result:**
| Dialect | Minimum Accuracy |
|---------|------------------|
| Standard Bengali | 95% |
| Rajshahi | 90% |
| Chittagong | 90% |
| Sylheti | 85% |

---

#### TC-VOICE-003: Voice Command Navigation

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-VOICE-003 |
| **Feature** | Voice Navigation |
| **Type** | FT + LT |
| **Priority** | Medium (P2) |
| **Preconditions** | Voice navigation enabled |

**Test Steps:**
1. From home screen, tap voice command button
2. Speak: "দুধের রেকর্ড" (Milk Record)
3. Verify navigation to milk recording screen
4. Speak: "হোম" (Home)
5. Verify return to home screen

**Expected Result:**
- Voice commands recognized
- Navigation occurs within 2 seconds
- Visual feedback confirms command
- Alternative text input always available

---

#### TC-VOICE-004: Voice Input in Noisy Environment

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-VOICE-004 |
| **Feature** | Noise Filtering |
| **Type** | LT + CT |
| **Priority** | High (P1) |
| **Preconditions** | Farm environment with background noise (animals, machinery) |

**Test Steps:**
1. Test in barn with animal sounds (60-70 dB)
2. Test during milking machine operation (70-80 dB)
3. Speak commands at normal volume
4. Verify recognition accuracy

**Expected Result:**
- Background noise filtered effectively
- Recognition accuracy ≥ 85% in noisy environment
- Visual indicator shows when to speak
- Retry option available on failure

---

#### TC-VOICE-005: Voice Announcement Confirmation

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-VOICE-005 |
| **Feature** | Audio Feedback |
| **Type** | UT |
| **Priority** | Medium (P2) |
| **Preconditions** | Voice announcements enabled |

**Test Steps:**
1. Complete any action (save record, scan RFID)
2. Listen for audio confirmation
3. Verify Bengali voice announcement
4. Test in different scenarios:
   - Successful save
   - Error condition
   - Warning condition

**Expected Result:**
| Action | Expected Announcement |
|--------|----------------------|
| Record saved | "রেকর্ড সেভ হয়েছে" (Record saved) |
| RFID scanned | "গরু শনাক্ত হয়েছে" (Cow identified) |
| Error | "ত্রুটি হয়েছে, আবার চেষ্টা করুন" (Error, try again) |
| Sync complete | "সিঙ্ক সম্পন্ন" (Sync complete) |

---

#### TC-VOICE-006: Offline Voice Recognition

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-VOICE-006 |
| **Feature** | Offline Voice Processing |
| **Type** | LT |
| **Priority** | High (P1) |
| **Preconditions** | Device in airplane mode/offline |

**Test Steps:**
1. Enable airplane mode
2. Navigate to milk recording
3. Tap microphone icon
4. Speak quantity in Bengali
5. Verify offline recognition works

**Expected Result:**
- Voice recognition functions offline
- Pre-downloaded language model used
- Recognition accuracy maintained (≥ 85%)
- Data queued for sync when online

---

### 5.8 Offline Data Entry

#### TC-OFFLINE-001: Record Data Without Network

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-OFFLINE-001 |
| **Feature** | Offline Data Entry |
| **Type** | FT |
| **Priority** | High (P1) |
| **Preconditions** | Device in airplane mode or no signal area |

**Test Steps:**
1. Disable network connection
2. Navigate to milk recording
3. Scan animal RFID
4. Enter milk quantity
5. Save record
6. Observe offline indicator

**Expected Result:**
- Record saved locally
- "Pending Sync" indicator visible
- No error messages about connectivity
- Record accessible in local history

---

#### TC-OFFLINE-002: Offline Animal Lookup

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-OFFLINE-002 |
| **Feature** | Offline Data Access |
| **Type** | FT |
| **Priority** | High (P1) |
| **Preconditions** | Cached animal data available, offline mode |

**Test Steps:**
1. Ensure animal data synced previously
2. Go offline
3. Scan animal RFID
4. View animal profile and history
5. Access previously synced records

**Expected Result:**
- Animal data accessible offline
- Historical records viewable
- Photos cached locally viewable
- No blank screens or errors

---

#### TC-OFFLINE-003: Multiple Records Queued for Sync

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-OFFLINE-003 |
| **Feature** | Offline Queue Management |
| **Type** | FT |
| **Priority** | Medium (P2) |
| **Preconditions** | Device offline, multiple records to enter |

**Test Steps:**
1. Go offline
2. Record 10 milk entries
3. Record 3 health incidents
4. Record 2 feed consumptions
5. View "Pending Sync" screen
6. Verify all records queued

**Expected Result:**
- All 15 records queued
- Record count displayed
- Individual records reviewable
- Queue persists through app restart

---

#### TC-OFFLINE-004: Sync on Network Restoration

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-OFFLINE-004 |
| **Feature** | Auto Sync Trigger |
| **Type** | FT |
| **Priority** | High (P1) |
| **Preconditions** | Records pending sync, offline mode active |

**Test Steps:**
1. Ensure multiple records pending sync
2. Re-enable network connection
3. Observe sync initiation
4. Monitor sync progress
5. Verify completion notification

**Expected Result:**
- Sync starts automatically within 30 seconds
- Progress indicator shows items syncing
- Successful sync confirmed
- All records now marked as synced
- "Sync Complete" notification displayed

---

#### TC-OFFLINE-005: Conflict Resolution for Offline Edits

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-OFFLINE-005 |
| **Feature** | Data Conflict Handling |
| **Type** | FT |
| **Priority** | Medium (P2) |
| **Preconditions** | Record edited both offline and on server |

**Test Steps:**
1. Edit record while offline
2. Same record edited on server by another user
3. Restore network connection
4. Allow sync to attempt
5. Observe conflict resolution

**Expected Result:**
- Conflict detected during sync
- User prompted to choose version
- Option to merge or select one version
- Clear display of differences
- User's choice applied and synced

---

### 5.9 Sync Management

#### TC-SYNC-001: Manual Sync Trigger

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-SYNC-001 |
| **Feature** | Manual Sync |
| **Type** | FT |
| **Priority** | Medium (P2) |
| **Preconditions** | Network connection available, data pending |

**Test Steps:**
1. Navigate to Settings
2. Tap "Sync Now" button
3. Observe sync progress
4. Wait for completion
5. Verify "Last Sync" timestamp updated

**Expected Result:**
- Sync initiates immediately
- Progress bar shows status
- Upload and download phases indicated
- Success/failure status displayed
- Timestamp updated on success

---

#### TC-SYNC-002: Background Auto-Sync Configuration

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-SYNC-002 |
| **Feature** | Auto Sync Settings |
| **Type** | FT |
| **Priority** | Medium (P2) |
| **Preconditions** | User has settings access |

**Test Steps:**
1. Navigate to Settings > Sync Settings
2. Enable "Auto Sync"
3. Set sync frequency: Every 15 minutes
4. Set "Sync only on WiFi" option
5. Save settings

**Expected Result:**
- Settings saved correctly
- Auto-sync functions as configured
- Respects WiFi-only preference
- Frequency honored (±2 minutes tolerance)

---

#### TC-SYNC-003: Sync with Poor Network Connection

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-SYNC-003 |
| **Feature** | Resilient Sync |
| **Type** | PT |
| **Priority** | High (P1) |
| **Preconditions** | Weak 2G/3G signal (1-2 bars) |

**Test Steps:**
1. Move to area with poor signal
2. Create new records
3. Initiate manual sync
4. Monitor sync behavior
5. Simulate connection drop during sync

**Expected Result:**
- Sync attempts with timeout handling
- Failed uploads retry automatically
- Resumable upload for large files (photos)
- No data corruption on interruption
- Retry count displayed to user

---

#### TC-SYNC-004: Verify Data Integrity After Sync

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-SYNC-004 |
| **Feature** | Data Integrity |
| **Type** | FT |
| **Priority** | High (P1) |
| **Preconditions** | Sync completed with multiple record types |

**Test Steps:**
1. Create records of various types while offline
2. Sync all data
3. Log in to web dashboard
4. Verify all records appear correctly
5. Check photo attachments
6. Verify timestamps accurate

**Expected Result:**
- All record types synced correctly
- No missing or duplicate records
- Photos viewable and complete
- Timestamps preserved accurately
- All data fields intact

---

#### TC-SYNC-005: Large Dataset Sync Performance

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-SYNC-005 |
| **Feature** | Bulk Sync Performance |
| **Type** | PT |
| **Priority** | Medium (P2) |
| **Preconditions** | 100+ records pending sync |

**Test Steps:**
1. Generate 100 offline records
2. Include 20 photos
3. Initiate sync on 3G connection
4. Measure total sync time
5. Monitor battery usage

**Expected Result:**
- Sync completes within 5 minutes (3G)
- Progress updates every 10 records
- Battery drain < 5% during sync
- No app crashes or freezes

---

### 5.10 Dashboard & Alerts

#### TC-DASH-001: View Daily Summary Dashboard

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-DASH-001 |
| **Feature** | Dashboard Display |
| **Type** | FT |
| **Priority** | High (P1) |
| **Preconditions** | User logged in, data exists |

**Test Steps:**
1. Launch app and login
2. View home dashboard
3. Verify displayed elements:
   - Today's total milk
   - Animals milked count
   - Pending tasks
   - Active alerts
4. Tap each metric for details

**Expected Result:**
- Dashboard loads within 3 seconds
- All metrics display current data
- Large, readable numbers
- Color indicators for status (green/red/yellow)
- Drill-down navigation functional

---

#### TC-DASH-002: Heat Detection Alert Notification

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-DASH-002 |
| **Feature** | Heat Alert |
| **Type** | FT |
| **Priority** | High (P1) |
| **Preconditions** | Animal expected to come in heat |

**Test Steps:**
1. Wait for scheduled heat prediction
2. Observe notification received
3. Tap notification
4. View animal details
5. Take action (record heat/schedule AI)

**Expected Result:**
- Push notification received
- Bengali text in notification
- Priority alert (red icon, sound)
- Direct navigation to animal record
- Quick action buttons available

---

#### TC-DASH-003: Low Feed Stock Alert

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-DASH-003 |
| **Feature** | Inventory Alert |
| **Type** | FT |
| **Priority** | Medium (P2) |
| **Preconditions** | Feed stock below threshold |

**Test Steps:**
1. Ensure feed stock below 7-day threshold
2. Open app or refresh dashboard
3. View feed alert on dashboard
4. Tap alert for details
5. View specific feed type and days remaining

**Expected Result:**
- Yellow alert displayed on dashboard
- Specific feed type identified
- Days remaining shown
- Option to order/mark as ordered
- Historical consumption chart visible

---

#### TC-DASH-004: Sync Status Indicator

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-DASH-004 |
| **Feature** | Sync Status |
| **Type** | FT |
| **Priority** | Medium (P2) |
| **Preconditions** | Various sync states |

**Test Steps:**
1. Verify "All Synced" indicator (green)
2. Create offline record
3. Verify "Pending Sync" with count (orange)
4. Initiate sync
5. Verify "Syncing..." indicator (animated)
6. Complete sync
7. Verify return to green status

**Expected Result:**
- Status indicators color-coded correctly
- Pending count accurate
- Sync animation during active sync
- Last sync time displayed
- Tap to force manual sync

---

#### TC-DASH-005: Weather Information Display

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-DASH-005 |
| **Feature** | Weather Widget |
| **Type** | FT |
| **Priority** | Low (P3) |
| **Preconditions** | Location services enabled |

**Test Steps:**
1. View dashboard
2. Locate weather widget
3. Verify current conditions displayed
4. Check 3-day forecast visible
5. Verify temperature in Celsius

**Expected Result:**
- Current weather displayed
- Bengali text for conditions
- Temperature in °C
- Weather-appropriate icons
- Updates every 3 hours

---

## 6. Usability Test Cases

### 6.1 Touch Target and Glove Operation

#### TC-UX-001: Minimum Touch Target Size Verification

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-UX-001 |
| **Feature** | Touch Target Size |
| **Type** | UT |
| **Priority** | High (P1) |
| **Preconditions** | App installed, measurement tool available |

**Test Steps:**
1. Navigate through all screens
2. Measure all interactive elements
3. Verify minimum sizes:
   - Buttons: 48dp × 48dp minimum
   - List items: 56dp height minimum
   - Form fields: 56dp height minimum
4. Verify spacing between elements: 8dp minimum

**Expected Result:**
- All touch targets meet minimum size
- No accidental touches on adjacent elements
- Sufficient spacing throughout app

---

#### TC-UX-002: High Contrast Visibility Test

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-UX-002 |
| **Feature** | Outdoor Visibility |
| **Type** | UT + CT |
| **Priority** | High (P1) |
| **Preconditions** | Test in direct sunlight |

**Test Steps:**
1. Position device in direct sunlight
2. Navigate through all screens
3. Verify text readability
4. Check button visibility
5. Verify icon recognition
6. Test with polarized sunglasses

**Expected Result:**
- Text readable in sunlight
- Contrast ratio ≥ 4.5:1 maintained
- Icons clearly distinguishable
- No glare issues with sunglasses
- Screen brightness auto-adjusts

---

#### TC-UX-003: Simple Navigation Flow

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-UX-003 |
| **Feature** | Navigation Efficiency |
| **Type** | UT |
| **Priority** | High (P1) |
| **Preconditions** | User familiar with basic smartphone use |

**Test Steps:**
1. Time navigation to milk recording: Home → Milk Record
2. Time navigation to animal health: Home → Health Log
3. Count taps required for common tasks:
   - Record milk: ____ taps
   - Log health incident: ____ taps
   - View animal history: ____ taps

**Expected Result:**
| Task | Maximum Taps |
|------|-------------|
| Record milk | 5 taps |
| Log health | 6 taps |
| View animal | 4 taps |
| Emergency alert | 3 taps |

---

#### TC-UX-004: Voice Announcement Clarity

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-UX-004 |
| **Feature** | Audio Feedback |
| **Type** | UT |
| **Priority** | Medium (P2) |
| **Preconditions** | Audio enabled, various environment noise levels |

**Test Steps:**
1. Test indoors (quiet, 40 dB)
2. Test in barn (moderate noise, 65 dB)
3. Test outdoors (windy, 70 dB)
4. Verify voice announcement audibility
5. Check volume auto-adjustment

**Expected Result:**
- Announcements audible in all conditions
- Volume increases in noisy environments
- Clear pronunciation in Bengali
- Option to repeat announcement

---

#### TC-UX-005: Error Message Clarity

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-UX-005 |
| **Feature** | Error Handling UX |
| **Type** | UT |
| **Priority** | Medium (P2) |
| **Preconditions** | Various error conditions |

**Test Steps:**
1. Trigger validation error (invalid input)
2. Trigger network error
3. Trigger RFID read error
4. Trigger sync error
5. Evaluate error messages

**Expected Result:**
- Error messages in simple Bengali
- Clear explanation of what went wrong
- Actionable guidance to resolve
- No technical jargon
- Friendly, non-alarming tone

---

## 7. Performance & Battery Test Cases

#### TC-PERF-001: App Launch Time

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-PERF-001 |
| **Feature** | Launch Performance |
| **Type** | PT |
| **Priority** | High (P1) |
| **Preconditions** | App not in memory |

**Test Steps:**
1. Force close app
2. Tap app icon
3. Measure time to dashboard display
4. Repeat 5 times, average results

**Expected Result:**
- Cold start: < 5 seconds on mid-range device
- Cold start: < 8 seconds on low-end device
- Warm start: < 2 seconds

---

#### TC-PERF-002: Screen Transition Performance

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-PERF-002 |
| **Feature** | Navigation Performance |
| **Type** | PT |
| **Priority** | High (P1) |
| **Preconditions** | App fully loaded |

**Test Steps:**
1. Navigate between screens rapidly
2. Measure transition time
3. Check for frame drops
4. Verify smooth animations

**Expected Result:**
- Screen transitions < 500ms
- 60fps maintained during animations
- No lag or stuttering
- Immediate response to touch

---

#### TC-PERF-003: Battery Drain During Normal Use

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-PERF-003 |
| **Feature** | Battery Efficiency |
| **Type** | PT |
| **Priority** | High (P1) |
| **Preconditions** | Fully charged device, battery monitoring app |

**Test Steps:**
1. Record starting battery level
2. Simulate 4-hour farm work session:
   - 50 RFID scans
   - 30 milk records
   - 10 photos captured
   - 20 voice inputs
   - Periodic dashboard checks
3. Record ending battery level

**Expected Result:**
- Battery drain < 25% for 4-hour session
- No abnormal heating
- Background power usage minimal
- Screen brightness major factor

---

#### TC-PERF-004: Memory Usage on Low-End Device

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-PERF-004 |
| **Feature** | Memory Optimization |
| **Type** | PT |
| **Priority** | Medium (P2) |
| **Preconditions** | 2GB RAM device, memory monitoring |

**Test Steps:**
1. Monitor baseline memory usage
2. Use app for 30 minutes continuously
3. Navigate through all features
4. Capture photos
5. Monitor memory growth
6. Check for memory leaks

**Expected Result:**
- Memory usage < 150MB at all times
- No continuous memory growth
- App remains responsive
- No crashes due to memory

---

## 8. Security Test Cases

#### TC-SEC-001: Session Timeout and Re-authentication

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-SEC-001 |
| **Feature** | Session Management |
| **Type** | ST |
| **Priority** | High (P1) |
| **Preconditions** | User logged in, session timeout configured |

**Test Steps:**
1. Login to app
2. Leave app idle for timeout period (15 minutes)
3. Attempt to perform action
4. Verify lock screen appears
5. Re-authenticate with PIN/biometric

**Expected Result:**
- Session locks after timeout
- Biometric/PIN prompt displayed
- Previous screen state maintained
- Data not lost during re-auth

---

#### TC-SEC-002: Data Encryption at Rest

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-SEC-002 |
| **Feature** | Data Security |
| **Type** | ST |
| **Priority** | High (P1) |
| **Preconditions** | App data stored on device |

**Test Steps:**
1. Access app database file (rooted device/emulator)
2. Attempt to read sensitive data
3. Verify encryption
4. Check key storage security

**Expected Result:**
- Database encrypted (AES-256)
- No plaintext passwords or tokens
- Secure key storage (Keychain/Keystore)
- SQLCipher or equivalent used

---

#### TC-SEC-003: Secure Data Transmission

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-SEC-003 |
| **Feature** | Transport Security |
| **Type** | ST |
| **Priority** | High (P1) |
| **Preconditions** | Network monitoring tools (proxy) |

**Test Steps:**
1. Setup HTTPS proxy
2. Perform sync operation
3. Capture network traffic
4. Verify TLS 1.2+ usage
5. Check for sensitive data exposure

**Expected Result:**
- All traffic over HTTPS
- TLS 1.2 or higher
- Certificate pinning implemented
- No sensitive data in URLs

---

#### TC-SEC-004: Role-Based Access Control

| Field | Value |
|-------|-------|
| **Test Case ID** | TC-SEC-004 |
| **Feature** | Access Control |
| **Type** | ST |
| **Priority** | Medium (P2) |
| **Preconditions** | Different user roles configured |

**Test Steps:**
1. Login as Farm Worker role
2. Verify accessible features
3. Attempt to access admin features
4. Login as Supervisor role
5. Verify additional features accessible

**Expected Result:**
| Role | Accessible Features |
|------|---------------------|
| Farm Worker | Milk, Health, Feed, Basic Reports |
| Supervisor | All Worker features + Breeding, Admin |
| Manager | Full access |

---

## 9. Defect Tracking

### 9.1 Defect Severity Definitions

| Severity | Definition | Example |
|----------|------------|---------|
| Critical | App crash, data loss, security breach | App crashes on launch, sync deletes data |
| High | Major feature unusable | RFID scan not working, offline data lost |
| Medium | Feature partially broken, workaround exists | Voice input slow, photo compression fails occasionally |
| Low | Cosmetic issues, minor inconvenience | Typo in Bengali text, slight UI misalignment |

### 9.2 Defect Log Template

| ID | Description | Severity | Status | Assigned To | Date Found | Date Fixed |
|----|-------------|----------|--------|-------------|------------|------------|
| DEF-001 | | | | | | |
| DEF-002 | | | | | | |
| DEF-003 | | | | | | |

### 9.3 Test Execution Summary

| Test Category | Total | Passed | Failed | Blocked | Not Run |
|---------------|-------|--------|--------|---------|---------|
| Functional | 40 | | | | |
| Usability | 5 | | | | |
| Performance | 4 | | | | |
| Security | 4 | | | | |
| Compatibility | 9 | | | | |
| **TOTAL** | **62** | | | | |

**Pass Rate Target:** ≥ 95% for P1 cases, ≥ 90% overall

---

## 10. Appendices

### Appendix A: Bengali Voice Commands Reference

| English Command | Bengali Command | Purpose |
|-----------------|-----------------|---------|
| Milk record | দুধের রেকর্ড | Open milk recording |
| Health log | স্বাস্থ্য রেকর্ড | Open health logging |
| Save | সেভ করুন | Save current record |
| Cancel | বাতিল | Cancel operation |
| Go back | পেছনে যান | Navigate back |
| Home | হোম | Go to dashboard |
| Scan | স্ক্যান | Trigger RFID scan |
| Emergency | জরুরী | Emergency alert |

### Appendix B: RFID Scanner Specifications

| Parameter | Specification |
|-----------|---------------|
| Frequency | 134.2 kHz (ISO 11784/11785) |
| Read Range | 5-15 cm |
| Scan Rate | 1 scan/second |
| Connectivity | Bluetooth 4.0+ or USB OTG |
| Battery | 8+ hours continuous use |
| Operating Temp | -10°C to +50°C |

### Appendix C: Test Device Specifications

| Device | OS | RAM | Storage | Screen |
|--------|-----|-----|---------|--------|
| Samsung Galaxy M01 | Android 10 | 3GB | 32GB | 5.7" |
| Xiaomi Redmi 9A | Android 10 | 2GB | 32GB | 6.53" |
| Realme C11 | Android 10 | 2GB | 32GB | 6.5" |
| Samsung Tab A | Android 11 | 3GB | 32GB | 10.1" |

### Appendix D: Environmental Test Conditions

| Condition | Temperature | Humidity | Lighting |
|-----------|-------------|----------|----------|
| Indoor Office | 25°C | 50% | 500 lux |
| Barn/Shade | 30°C | 75% | 100 lux |
| Outdoor Morning | 28°C | 70% | 5000 lux |
| Outdoor Midday | 38°C | 60% | 10000+ lux |
| Evening | 26°C | 80% | 50 lux (artificial) |

### Appendix E: Contact Information

| Role | Name | Contact |
|------|------|---------|
| QA Lead | [Name] | [Email] |
| Farm Operations Manager | [Name] | [Email] |
| Development Lead | [Name] | [Email] |
| Product Owner | [Name] | [Email] |

---

## Sign-off

| Role | Name | Signature | Date |
|------|------|-----------|------|
| QA Engineer | | | |
| QA Lead | | | |
| Farm Operations Manager | | | |
| Product Owner | | | |

---

*End of Document G-013*

**Smart Dairy Ltd.**
*Quality Assurance Documentation*
*Confidential - Internal Use Only*
