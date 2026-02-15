# Phase 2: ERP Core - Herd, Milk, Health & Breeding
## Months 7-10 (July - October 2026)

**Phase Goal:** Digitize core farm operations: animal registration, daily milk recording, health management, and breeding tracking. Deploy mobile app MVP for farm workers.
**Key Deliverable:** Farm staff recording daily operations via mobile app with offline support.
**Revenue Impact:** Operational efficiency; real data begins flowing to website's technology dashboard.

---

## Sprint Breakdown

### Sprint 2.1-2.2 (Weeks 1-4): Herd Management

**Objectives:**
- Build animal registration and profile system
- Implement group management (lactating, heifers, bulls, calves, dry)
- Animal movement tracking between groups
- Weight and body condition scoring
- 3-generation pedigree display

**Deliverables:**
- [ ] Animal registration form with photo upload
- [ ] Animal profile page with full history
- [ ] Group management with animal counts
- [ ] Group transfer with reason tracking
- [ ] Weight recording with BCS
- [ ] Pedigree tree view (sire, dam, grandparents)
- [ ] Animal search by tag number or name
- [ ] Herd composition summary dashboard widget

### Sprint 2.3-2.4 (Weeks 5-8): Milk Production Recording

**Objectives:**
- Build individual cow milk recording (morning/evening sessions)
- Implement batch recording for multiple cows
- Daily/weekly/monthly production summaries
- Top/low producer identification
- Anomaly detection (>20% drop from 7-day average triggers alert)

**Deliverables:**
- [ ] Individual milk recording form
- [ ] Batch milk recording (mobile-optimized)
- [ ] Bulk tank recording
- [ ] Quality test recording (fat%, SNF%, SCC)
- [ ] Daily summary: total, average, target vs actual
- [ ] Weekly/monthly production charts (Recharts)
- [ ] Low producer alerts (SMS to farm manager)
- [ ] Production data feeding website's farm dashboard (replacing placeholders)

### Sprint 2.5-2.6 (Weeks 9-12): Health Management

**Objectives:**
- Health event recording (illness, injury, treatment, surgery)
- Bangladesh vaccination protocol (FMD, Anthrax, BQ, HS, Rabies)
- Automated vaccination scheduling with SMS reminders
- Disease incident tracking with quarantine

**Deliverables:**
- [ ] Health event form with symptom selection
- [ ] Treatment recording with withdrawal period tracking
- [ ] Vaccination schedule auto-calculation
- [ ] Vaccination recording (batch: multiple animals at once)
- [ ] Overdue vaccination alerts (SMS, in-app)
- [ ] Disease incident reporting with quarantine flag
- [ ] Veterinary visit logging
- [ ] Health history timeline per animal

### Sprint 2.7-2.8 (Weeks 13-16): Breeding Management + Mobile MVP

**Objectives:**
- Breeding event recording (natural, AI, embryo transfer)
- Pregnancy detection tracking
- Calving recording with calf registration
- Expected calving calendar
- **React Native mobile app MVP**: milk recording + health events with offline

**Deliverables:**
- [ ] Breeding record form (AI with semen batch, inseminator)
- [ ] Pregnancy check recording
- [ ] Calving record with calf details (auto-registers new animal)
- [ ] Expected calving calendar
- [ ] Breeding KPIs dashboard (calving interval, conception rate)
- [ ] **Mobile app**: Login, animal list, milk recording screen
- [ ] **Mobile app**: Health event recording screen
- [ ] **Mobile app**: Offline storage with expo-sqlite
- [ ] **Mobile app**: Sync pending records when online
- [ ] **Mobile app**: Push notification setup (Expo Notifications)

---

## Phase 2 Verification Checklist

- [ ] Register new animal -> appears in herd count and correct group
- [ ] Record morning milking for 75 cows (batch) -> daily summary shows correct total
- [ ] Mobile: Record milking offline -> come online -> data syncs to server
- [ ] Log vaccination for 10 animals -> next due date auto-calculated -> reminder appears
- [ ] Record AI breeding -> pregnancy check positive -> expected calving date displayed
- [ ] Record calving -> new calf auto-registered in calves group
- [ ] Farm dashboard on website shows real production data from ERP
- [ ] SMS sent for overdue vaccinations

---

**Phase 2 Duration:** 16 weeks (8 sprints)
**Dependencies:** Phase 0 (common services, auth)
**Next Phase:** Phase 3 (ERP Advanced)
