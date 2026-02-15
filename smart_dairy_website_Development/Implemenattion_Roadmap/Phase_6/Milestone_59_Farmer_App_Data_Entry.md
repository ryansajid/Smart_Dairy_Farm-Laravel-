# Milestone 59: Farmer App Data Entry

## Smart Dairy Digital Smart Portal + ERP System
### Phase 6: Mobile App Foundation

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-P6-M59-2026 |
| **Milestone Number** | 59 |
| **Milestone Title** | Farmer App Data Entry |
| **Phase** | 6 - Mobile App Foundation |
| **Sprint Days** | Days 331-340 (10 working days) |
| **Duration** | 2 weeks |
| **Version** | 1.0 |
| **Status** | Draft |
| **Created Date** | 2026-02-03 |
| **Last Updated** | 2026-02-03 |
| **Author** | Technical Architecture Team |
| **Reviewers** | Project Manager, Mobile Lead, QA Lead |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Requirement Traceability Matrix](#2-requirement-traceability-matrix)
3. [Day-by-Day Breakdown](#3-day-by-day-breakdown)
4. [Technical Specifications](#4-technical-specifications)
5. [Testing & Validation](#5-testing--validation)
6. [Risk & Mitigation](#6-risk--mitigation)
7. [Dependencies & Handoffs](#7-dependencies--handoffs)

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Complete Farmer Mobile Application with comprehensive data entry features including milk production recording (morning/evening), health observations with photo/voice notes, breeding cycle tracking, AI-powered health insights, offline-capable form submission, and Bengali voice-to-text support for low-literacy users.

### 1.2 Objectives

| # | Objective | Priority | Success Metric |
|---|-----------|----------|----------------|
| 1 | Implement quick milk production entry | Critical | <20 seconds per entry |
| 2 | Build health observation recording | Critical | Photo + voice support |
| 3 | Create breeding cycle tracking | High | Heat detection alerts |
| 4 | Implement Bengali voice-to-text | High | >85% accuracy |
| 5 | Build photo capture with compression | Critical | <500KB per image |
| 6 | Create AI health insights (basic) | Medium | Symptom detection |
| 7 | Implement offline form submission | Critical | Queue + auto-sync |
| 8 | Build data summary dashboard | Medium | Daily/weekly views |
| 9 | Create report generation | Medium | PDF export |
| 10 | Implement voice feedback (Bangla) | Low | Audio confirmations |

### 1.3 Key Deliverables

| # | Deliverable | Type | Owner | Acceptance Criteria |
|---|-------------|------|-------|---------------------|
| 1 | Milk Recording APIs | Odoo REST | Dev 1 | Create + bulk sync |
| 2 | Quick Milk Entry Screen | Flutter UI | Dev 3 | Large buttons, defaults |
| 3 | Health Recording APIs | Odoo REST | Dev 1 | Photo + voice upload |
| 4 | Health Observation Screen | Flutter UI | Dev 3 | Camera + voice |
| 5 | Breeding APIs | Odoo REST | Dev 1 | Heat tracking |
| 6 | Breeding Tracker Screen | Flutter UI | Dev 3 | Calendar view |
| 7 | Voice-to-Text Service | Flutter | Dev 2 | Bengali support |
| 8 | Photo Service | Flutter | Dev 2 | Compress + cache |
| 9 | AI Health Insights | Python/ML | Dev 1 | Symptom patterns |
| 10 | Data Dashboard | Flutter UI | Dev 3 | Charts + summaries |

### 1.4 Prerequisites

| # | Prerequisite | Source | Status |
|---|--------------|--------|--------|
| 1 | Farmer authentication | Milestone 58 | Required |
| 2 | Animal database synced | Milestone 58 | Required |
| 3 | RFID/barcode scanning | Milestone 58 | Required |
| 4 | Camera permissions | Configuration | Required |
| 5 | Microphone permissions | Configuration | Required |

### 1.5 Success Criteria

- [ ] Milk entry completed <20 seconds
- [ ] Photos compressed to <500KB
- [ ] Voice notes recorded successfully
- [ ] Bengali voice-to-text >85% accuracy
- [ ] Offline forms sync within 1 minute when online
- [ ] Dashboard loads <2 seconds
- [ ] Battery usage <20% per 8-hour shift
- [ ] Code coverage >80%

---

## 2. Requirement Traceability Matrix

### 2.1 RFP Requirements

| RFP Req ID | Requirement Description | Implementation | Day |
|------------|------------------------|----------------|-----|
| RFP-MOB-008 | Farmer data entry module | All features | 331-340 |
| RFP-MOB-009 | Voice input for low-literacy | Voice-to-text | 335-336 |
| RFP-MOB-027 | Milk production recording | Milk entry | 331-332 |
| RFP-MOB-028 | Health observations | Health recording | 333-334 |
| RFP-MOB-029 | AI health insights | ML integration | 337-338 |

### 2.2 BRD Requirements

| BRD Req ID | Business Requirement | Implementation | Day |
|------------|---------------------|----------------|-----|
| BRD-MOB-004 | 100% farm data digitization | All recording features | 331-340 |
| BRD-MOB-005 | Low-literacy accessibility | Voice input | 335-336 |
| BRD-MOB-015 | Real-time health monitoring | Health observations | 333-334 |
| BRD-MOB-016 | Breeding optimization | Breeding tracker | 335 |

---

## 3. Day-by-Day Breakdown

### Day 331-332: Milk Production Recording

**Objectives:**
- Build milk recording API with validation
- Create quick milk entry screen
- Implement session-based recording (morning/evening)

**Key Features:**
- Large number pad for quantity entry
- Quick defaults (session, date)
- Offline queue with auto-sync
- Daily summary view
- Visual feedback for successful entry

### Day 333-334: Health Observations

**Objectives:**
- Build health recording API
- Create health observation screen
- Implement photo capture with compression
- Add voice note recording

**Key Features:**
- Camera integration for symptoms
- Voice memo recording (up to 2 minutes)
- Pre-defined symptom templates
- Photo compression (<500KB)
- GPS tagging
- Offline support

### Day 335-336: Voice-to-Text Integration

**Objectives:**
- Integrate Bengali speech recognition
- Build voice input UI
- Create voice note playback

**Key Features:**
- Bengali voice-to-text (speech_to_text package)
- Real-time transcription display
- Voice note storage and playback
- Fallback to manual text entry
- Visual recording indicator

### Day 337-338: AI Health Insights

**Objectives:**
- Build basic symptom detection
- Create health alert system
- Implement pattern recognition

**Key Features:**
- Common symptom pattern detection
- Health score calculation
- Alert notifications
- Historical trend analysis
- Veterinary recommendation triggers

### Day 339-340: Dashboard & Reports

**Objectives:**
- Build data summary dashboard
- Create report generation
- Implement export functionality

**Key Features:**
- Daily/weekly/monthly views
- Milk production charts
- Health timeline
- Breeding calendar
- PDF report export
- WhatsApp share integration

---

## 4. Technical Specifications

### 4.1 Data Entry Forms

| Form | Fields | Offline | Validation |
|------|--------|---------|------------|
| Milk Recording | Animal, Session, Quantity, Quality | Yes | Min/max qty |
| Health Observation | Animal, Symptoms, Photo, Voice | Yes | Required fields |
| Breeding Record | Animal, Event Type, Date, Bull ID | Yes | Date logic |
| Weight Entry | Animal, Weight, Date | Yes | Range check |
| Vaccination | Animal, Vaccine, Date, Next Due | Yes | Schedule |

### 4.2 API Endpoints Summary

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/farmer/milk-records` | POST | Record milk production |
| `/api/v1/farmer/milk-records/sync` | POST | Bulk sync records |
| `/api/v1/farmer/health-records` | POST | Record health observation |
| `/api/v1/farmer/health-records/upload-photo` | POST | Upload symptom photo |
| `/api/v1/farmer/health-records/upload-voice` | POST | Upload voice note |
| `/api/v1/farmer/breeding-records` | POST | Record breeding event |
| `/api/v1/farmer/dashboard` | GET | Dashboard summary |
| `/api/v1/farmer/reports/milk` | GET | Milk production report |
| `/api/v1/farmer/reports/health` | GET | Health summary report |

### 4.3 Offline Capabilities

| Feature | Offline Support | Sync Strategy |
|---------|----------------|---------------|
| Milk Recording | Full | Batch upload |
| Health Photos | Full | WiFi-only upload option |
| Voice Notes | Full | Background upload |
| Reports | View cached | Refresh on demand |
| Dashboard | Last synced data | Auto-refresh online |

### 4.4 Bengali Voice Recognition

**Technology:** Flutter `speech_to_text` package with Bengali (bn-BD) locale

**Features:**
- Real-time transcription
- Noise cancellation
- Offline fallback (basic)
- Common veterinary terms dictionary

**Sample Implementation:**

```dart
import 'package:speech_to_text/speech_to_text.dart';

class BengaliVoiceService {
  final SpeechToText _speech = SpeechToText();

  Future<bool> initialize() async {
    return await _speech.initialize(
      onError: (error) => print('Error: $error'),
      onStatus: (status) => print('Status: $status'),
    );
  }

  Future<void> startListening({
    required Function(String) onResult,
  }) async {
    await _speech.listen(
      onResult: (result) => onResult(result.recognizedWords),
      localeId: 'bn_BD', // Bengali Bangladesh
      pauseFor: Duration(seconds: 3),
      partialResults: true,
    );
  }

  Future<void> stopListening() async {
    await _speech.stop();
  }
}
```

### 4.5 Photo Compression

**Target:** Compress to <500KB for faster upload

```dart
import 'package:image/image.dart' as img;

Future<File> compressImage(File imageFile) async {
  final bytes = await imageFile.readAsBytes();
  final image = img.decodeImage(bytes);

  if (image == null) return imageFile;

  // Resize if larger than 1024px
  final resized = image.width > 1024 || image.height > 1024
      ? img.copyResize(image, width: 1024)
      : image;

  // Compress to JPEG with 85% quality
  final compressed = img.encodeJpg(resized, quality: 85);

  // Save to temp file
  final tempDir = await getTemporaryDirectory();
  final tempFile = File('${tempDir.path}/compressed_${DateTime.now().millisecondsSinceEpoch}.jpg');
  await tempFile.writeAsBytes(compressed);

  return tempFile;
}
```

### 4.6 AI Health Insights (Basic)

**Approach:** Rule-based pattern detection + keyword matching

**Sample Rules:**
```python
# odoo/addons/smart_dairy_mobile_api/models/health_insights.py

class HealthInsights(models.AbstractModel):
    _name = 'health.insights'

    def analyze_symptoms(self, animal_id, symptoms_text):
        """Basic symptom analysis."""
        insights = []

        # Fever indicators
        fever_keywords = ['fever', 'hot', 'জ্বর', 'গরম']
        if any(k in symptoms_text.lower() for k in fever_keywords):
            insights.append({
                'type': 'warning',
                'title': 'Possible Fever',
                'recommendation': 'Check temperature and consult vet if >103°F',
                'severity': 'medium'
            })

        # Diarrhea
        diarrhea_keywords = ['diarrhea', 'loose', 'ডায়রিয়া', 'পাতলা']
        if any(k in symptoms_text.lower() for k in diarrhea_keywords):
            insights.append({
                'type': 'warning',
                'title': 'Digestive Issue',
                'recommendation': 'Ensure hydration and monitor for 24 hours',
                'severity': 'medium'
            })

        # Mastitis
        mastitis_keywords = ['swollen', 'udder', 'red', 'ফোলা', 'থানে']
        if any(k in symptoms_text.lower() for k in mastitis_keywords):
            insights.append({
                'type': 'alert',
                'title': 'Possible Mastitis',
                'recommendation': 'Consult vet immediately - may need antibiotics',
                'severity': 'high'
            })

        return insights
```

---

## 5. Testing & Validation

### 5.1 Unit Tests
- Form validation logic
- Photo compression
- Voice transcription accuracy
- Offline queue management

### 5.2 Integration Tests
- End-to-end recording flow
- Photo upload with compression
- Voice note recording and playback
- Sync reliability

### 5.3 Field Tests
- Bengali voice accuracy in noisy environment
- Low-light photo quality
- Battery consumption during full day use
- Network intermittency handling

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Poor voice recognition accuracy | High | Medium | Manual text fallback, pre-trained terms |
| Large photo uploads fail | Medium | Medium | Compression, WiFi-only option |
| Bengali language support gaps | Medium | High | Extensive testing with farmers |
| Battery drain from camera/mic | Medium | Medium | Optimize capture duration, background limits |

---

## 7. Dependencies & Handoffs

### 7.1 Dependencies
- Milestone 58: Animal database, scanner
- Camera/microphone permissions
- Internet for initial sync

### 7.2 Handoffs to Milestone 60
- [ ] Complete Farmer App
- [ ] All three apps ready for integration testing
- [ ] Data entry patterns established
- [ ] Voice/photo infrastructure ready

---

## Document Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-03 | Technical Team | Initial document creation |

---

*End of Milestone 59 Document*
