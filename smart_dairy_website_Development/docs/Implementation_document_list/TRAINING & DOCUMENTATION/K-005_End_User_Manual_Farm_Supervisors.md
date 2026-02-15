# Smart Dairy Web Portal - End User Manual

## Document K-005: Farm Supervisors

---

**Document ID:** K-005  
**Version:** 1.0  
**Date:** January 31, 2026  
**Author:** Technical Writer  
**Owner:** Training Lead  
**Reviewer:** Farm Operations Manager  

---

**Target Audience:** Farm Supervisors  
**Purpose:** This manual provides comprehensive guidance for farm supervisors on using the Smart Dairy Web Portal to manage workers, approve records, view reports, and oversee daily farm operations.

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Getting Started](#2-getting-started)
3. [Herd Management](#3-herd-management)
4. [Milk Production](#4-milk-production)
5. [Health Management](#5-health-management)
6. [Worker Management](#6-worker-management)
7. [Reports & Analytics](#7-reports--analytics)
8. [Mobile App](#8-mobile-app)
9. [Troubleshooting](#9-troubleshooting)
10. [Support](#10-support)

---

## 1. Introduction

### 1.1 Purpose of This Manual

This manual is designed to help **Farm Supervisors** (খামার সুপারভাইজার) at Smart Dairy Ltd. effectively use the Smart Dairy Web Portal system. As a supervisor, you play a critical role in managing daily operations, overseeing workers, and ensuring the highest standards of dairy production.

### 1.2 Who Should Use This Manual

This manual is specifically written for:
- **Farm Supervisors** responsible for managing teams of farm workers
- **Shift Leaders** overseeing specific operational areas
- **Senior Farm Staff** with approval and oversight responsibilities

### 1.3 Key Terms (Glossary)

| English Term | Bengali Translation (বাংলা) | Description |
|--------------|---------------------------|-------------|
| Animal Profile | প্রাণীর প্রোফাইল | Complete record of an individual animal |
| RFID Tag | আরএফআইডি ট্যাগ | Electronic identification tag for cattle |
| Lactation Period | দুগ্ধদানকালীন সময় | Period when a cow produces milk |
| Milking Session | দোহন সেশন | Scheduled time for milking cows |
| Quality Check | গুণমান পরীক্ষা | Inspection of milk quality |
| Vaccination | টিকা প্রদান | Preventive health treatment |
| Timesheet | সময়সূচি | Record of worker hours |
| Dashboard | ড্যাশবোর্ড | Main overview screen |
| Herd | পশুপাল | Group of cattle |

### 1.4 How to Use This Manual

- **Step-by-step instructions:** Look for numbered steps (1., 2., 3.)
- **Screenshots:** Placeholder descriptions indicate where screenshots will be added
- **Tips:** Look for 💡 icons for helpful hints
- **Warnings:** Look for ⚠️ icons for important cautions
- **Best Practices:** Look for ✅ icons for recommended approaches

---

## 2. Getting Started

### 2.1 System Requirements

**Minimum Requirements:**
- Internet connection (minimum 2 Mbps)
- Web browser: Chrome 90+, Firefox 88+, Safari 14+, or Edge 90+
- Screen resolution: 1366 x 768 or higher
- JavaScript enabled in browser

**Recommended:**
- Internet connection: 5 Mbps or higher
- Chrome or Firefox browser (latest version)
- Screen resolution: 1920 x 1080

### 2.2 Login Process

**Step-by-Step Login:**

1. Open your web browser
2. Navigate to: `https://portal.smartdairybd.com`
3. **Screenshot Placeholder:** *[Login Page Screenshot]*
   - Shows: Login form with username/password fields, Smart Dairy logo, "Sign In" button

4. Enter your **Username** (provided by IT)
5. Enter your **Password** (temporary password from IT, change on first login)
6. Click the **"Sign In"** button (সাইন ইন করুন)
7. **Screenshot Placeholder:** *[Two-Factor Authentication Screen]*
   - Shows: SMS code input field or authenticator app prompt

8. If prompted, enter the verification code sent to your mobile
9. Click **"Verify"** (যাচাই করুন)

💡 **Tip:** Bookmark the portal URL for quick access.

⚠️ **Security Warning:** Never share your login credentials. Log out when leaving your workstation.

### 2.3 Dashboard Overview

Upon successful login, you will see the **Supervisor Dashboard** (সুপারভাইজার ড্যাশবোর্ড).

**Screenshot Placeholder:** *[Main Dashboard Screenshot]*
- Shows: Summary cards, quick action buttons, alerts section, navigation sidebar

**Dashboard Sections:**

| Section | Description | Bengali |
|---------|-------------|---------|
| **Quick Stats** | Daily milk production, active workers, alerts | দৈনিক পরিসংখ্যান |
| **Today's Tasks** | Pending approvals and assignments | আজকের কাজ |
| **Alerts** | Health alerts, vaccination reminders | সতর্কতা |
| **Recent Activity** | Latest system activities | সাম্প্রতিক কার্যক্রম |
| **Navigation Menu** | Access to all modules | নেভিগেশন মেনু |

### 2.4 Navigation Menu

The left sidebar contains the main navigation:

**Screenshot Placeholder:** *[Navigation Sidebar Screenshot]*
- Shows: Expanded menu with all options visible

| Menu Item | Icon | Purpose | Bengali |
|-----------|------|---------|---------|
| Dashboard | 🏠 | Return to main dashboard | ড্যাশবোর্ড |
| Herd Management | 🐄 | Manage animal records | পশুপাল ব্যবস্থাপনা |
| Milk Production | 🥛 | Monitor and record milk data | দুধ উৎপাদন |
| Health Management | 🏥 | Health records and schedules | স্বাস্থ্য ব্যবস্থাপনা |
| Worker Management | 👷 | Manage workers and tasks | শ্রমিক ব্যবস্থাপনা |
| Reports | 📊 | View and export reports | প্রতিবেদন |
| Settings | ⚙️ | Personal settings | সেটিংস |
| Help | ❓ | Access help resources | সাহায্য |

### 2.5 User Profile Settings

**To Update Your Profile:**

1. Click your name in the top-right corner
2. Select **"Profile Settings"** (প্রোফাইল সেটিংস)
3. **Screenshot Placeholder:** *[Profile Settings Page]*
   - Shows: Form with name, contact, language preference, password change

4. Update your information:
   - Full Name (পূর্ণ নাম)
   - Mobile Number (মোবাইল নম্বর)
   - Email Address (ইমেইল ঠিকানা)
   - Language Preference: English / বাংলা
5. Click **"Save Changes"** (পরিবর্তন সংরক্ষণ করুন)

**To Change Password:**

1. In Profile Settings, click **"Change Password"** tab
2. Enter current password
3. Enter new password (minimum 8 characters, including uppercase, lowercase, number)
4. Confirm new password
5. Click **"Update Password"**

---

## 3. Herd Management

### 3.1 View All Animals

The Herd Management module allows you to view and manage all cattle records.

**Accessing the Herd List:**

1. Click **"Herd Management"** in the left navigation menu
2. **Screenshot Placeholder:** *[Herd List Page]*
   - Shows: Table with animal ID, name, breed, status, RFID tag, actions

**Understanding the Animal List:**

| Column | Description | Bengali |
|--------|-------------|---------|
| Animal ID | Unique identifier | প্রাণী আইডি |
| Tag Number | Physical ear tag | ট্যাগ নম্বর |
| Name/Photo | Animal name and photo | নাম/ছবি |
| Breed | Cattle breed | জাত |
| Status | Current status (Lactating, Dry, Pregnant, etc.) | অবস্থা |
| RFID Tag | Electronic tag status | আরএফআইডি ট্যাগ |
| Age | Years/Months | বয়স |
| Location | Current shed/area | অবস্থান |

**Filtering and Searching:**

**Screenshot Placeholder:** *[Filter Panel Screenshot]*
- Shows: Filter dropdowns for breed, status, age range, location

1. Use the **Search Box** to find animals by ID, name, or tag number
2. Use **Filters** to narrow results:
   - Breed (জাত): HF, Jersey, Local Cross, etc.
   - Status (অবস্থা): Lactating (দুগ্ধদানকারী), Dry (শুকনো), Pregnant (গর্ভবতী), Calf (বাছুর)
   - Age Range (বয়স পরিসীমা)
   - Location (অবস্থান)
3. Click **"Apply Filters"** (ফিল্টার প্রয়োগ করুন)

**Sorting:**
- Click column headers to sort (ascending/descending)
- Sort by ID, Name, Age, or Production

💡 **Tip:** Use the "Save Filter" option to quickly access frequently used filters.

### 3.2 Add/Edit Animal Records

**Adding a New Animal:**

1. Click **"Add New Animal"** button (নতুন প্রাণী যোগ করুন)
2. **Screenshot Placeholder:** *[Add Animal Form Screenshot]*
   - Shows: Multi-section form with all required fields

3. Fill in **Basic Information**:
   - Animal ID (auto-generated or manual)
   - Local Name/Nickname (স্থানীয় নাম)
   - Date of Birth (জন্ম তারিখ) OR Age
   - Breed (জাত): Select from dropdown
     - Holstein Friesian (HF)
     - Jersey
     - Sahiwal
     - Local Cross (স্থানীয় ক্রস)
     - Other
   - Gender (লিঙ্গ): Female/Heifer/Calf/Male
   - Color/Markings (রং/চিহ্ন)

4. Fill in **Origin Information**:
   - Source (উৎস): Born on Farm / Purchased / Transfer
   - Birth Weight (জন্ম ওজন) - if applicable
   - Parent Information: Mother's ID, Father's ID

5. Fill in **Current Status**:
   - Current Status: Lactating / Dry / Pregnant / Heifer / Calf / Fattening
   - Current Location (বর্তমান অবস্থান): Select shed/area
   - Entry Date to Current Location

6. Upload **Photo** (optional but recommended):
   - Click **"Upload Photo"**
   - Select clear image of the animal
   - Crop if necessary

7. Click **"Save Animal Record"** (প্রাণীর তথ্য সংরক্ষণ করুন)

⚠️ **Important:** Verify all information before saving. The Animal ID cannot be changed after creation.

**Editing an Existing Animal Record:**

1. From the Herd List, find the animal
2. Click the **"Edit"** icon (✏️) in the Actions column
3. **Screenshot Placeholder:** *[Edit Animal Form Screenshot]*
   - Shows: Pre-filled form with all animal details

4. Update the necessary fields
5. Add notes in the **Change Log** section explaining the update
6. Click **"Save Changes"**

### 3.3 RFID Tag Assignment

RFID (Radio-Frequency Identification) tags are essential for automated tracking and identification.

**Assigning a New RFID Tag:**

1. Navigate to the animal's profile
2. Click **"Assign RFID Tag"** (আরএফআইডি ট্যাগ নির্ধারণ করুন)
3. **Screenshot Placeholder:** *[RFID Assignment Screen]*
   - Shows: RFID input field, scanner icon, assignment history

4. **Method 1 - Manual Entry:**
   - Type the RFID tag number (e.g., "BD-SD-2024-001234")
   - Click **"Verify"** to check if tag is available

5. **Method 2 - Scanner:**
   - Click the **"Scan"** button
   - Hold the RFID scanner near the animal's tag
   - The system will automatically capture the tag number

6. Verify the tag details:
   - Tag Number
   - Tag Type (Ear Tag / Neck Collar / Leg Band)
   - Installation Date

7. Click **"Confirm Assignment"** (নির্ধারণ নিশ্চিত করুন)

**Replacing a Lost/Damaged RFID Tag:**

1. Go to the animal's profile
2. Click **"RFID Management"**
3. Select the old tag
4. Click **"Mark as Lost/Damaged"**
5. Enter reason for replacement
6. Follow steps above to assign new tag

💡 **Best Practice:** Always verify the RFID tag is functioning properly by testing it with a scanner before confirming assignment.

### 3.4 Animal Lifecycle Tracking

Track the complete lifecycle and status changes of each animal.

**Recording Status Changes:**

**Screenshot Placeholder:** *[Lifecycle Timeline Screenshot]*
- Shows: Visual timeline of animal's major life events

| Status Change | When to Record | Bengali |
|---------------|----------------|---------|
| First Calving | When heifer has first calf | প্রথম বাচ্চা প্রসব |
| Lactation Start | When milking begins | দুগ্ধদান শুরু |
| Lactation End | When drying off | দুগ্ধদান শেষ |
| Pregnancy Confirmed | After veterinary check | গর্ভধারণ নিশ্চিত |
| Calving | When cow gives birth | প্রসব |
| Sold/Transferred | When animal leaves farm | বিক্রয়/স্থানান্তর |
| Death | Record with cause | মৃত্যু |

**Steps to Record Status Change:**

1. Open animal profile
2. Click **"Update Status"** (অবস্থা আপডেট করুন)
3. Select **New Status** from dropdown
4. Enter **Effective Date**
5. Add relevant details:
   - For Pregnancy: Sire ID, Expected Calving Date
   - For Calving: Calf ID, Birth Weight, Gender
   - For Sale: Buyer, Price, Destination
   - For Death: Cause, Veterinary Report
6. Click **"Record Change"**

**Viewing Lifecycle History:**

1. Open animal profile
2. Click **"Lifecycle History"** tab
3. View complete timeline of all status changes
4. Click any event to see details

### 3.5 Daily Herd Management Checklist

**Daily Tasks (প্রতিদিনের কাজ):**

- [ ] Review new alerts for animals requiring attention
- [ ] Check animals with pending status changes
- [ ] Verify RFID tag reads during morning milking
- [ ] Note any animals showing unusual behavior
- [ ] Update records for any changes observed

**Weekly Tasks (সাপ্তাহিক কাজ):**

- [ ] Review all pregnant animals - update expected calving dates
- [ ] Check dry cows approaching calving
- [ ] Verify all new calves have been registered
- [ ] Review animals approaching sale/transfer dates
- [ ] Generate weekly herd summary report

---

## 4. Milk Production

### 4.1 Daily Production Dashboard

The Milk Production module provides real-time visibility into milking operations.

**Accessing the Dashboard:**

1. Click **"Milk Production"** in the navigation menu
2. **Screenshot Placeholder:** *[Milk Production Dashboard]*
   - Shows: Production charts, session summaries, quality indicators

**Dashboard Components:**

| Section | Description | Bengali |
|---------|-------------|---------|
| **Today's Production** | Total milk produced today | আজকের উৎপাদন |
| **Session Breakdown** | Morning, afternoon, evening yields | সেশন ভিত্তিক উৎপাদন |
| **Cow Performance** | Top and bottom performers | গাভীর কর্মক্ষমতা |
| **Quality Overview** | Fat, SNF, density averages | গুণমানের সারসংক্ষেপ |
| **Comparison Chart** | Today's vs. yesterday's production | তুলনামূলক চার্ট |

**Understanding Production Metrics:**

**Screenshot Placeholder:** *[Production Metrics Panel]*
- Shows: Key performance indicators with color coding

| Metric | Target Range | Notes |
|--------|--------------|-------|
| Total Daily Yield | 900+ liters | Target: 3,000L within 2 years |
| Average per Cow | 10-15 liters | Varies by lactation stage |
| Fat Content | 3.5-4.5% | Premium quality indicator |
| SNF (Solids-Not-Fat) | 8.5%+ | Nutritional value |
| Temperature | 4°C or below | Post-cooling temperature |

### 4.2 Review Worker Entries

As a supervisor, you must review and approve milk production entries recorded by workers.

**Accessing Pending Reviews:**

1. Go to **Milk Production** > **Review Entries** (এন্ট্রি পর্যালোচনা)
2. **Screenshot Placeholder:** *[Pending Reviews List]*
   - Shows: Table of entries awaiting approval with timestamps

**Review Process:**

| Column | Description |
|--------|-------------|
| Entry ID | Unique entry reference |
| Worker Name | Who recorded the entry |
| Session | Morning/Afternoon/Evening |
| Cow ID / Group | Individual or group entry |
| Quantity | Liters recorded |
| Quality Check | Pass/Fail/Pending |
| Timestamp | When recorded |
| Actions | Review/Approve/Reject |

**Steps to Review an Entry:**

1. Click **"Review"** on the entry you want to examine
2. **Screenshot Placeholder:** *[Entry Review Detail Screen]*
   - Shows: Complete entry details, cow information, quality data

3. Verify the following:
   - Animal identification is correct
   - Quantity is reasonable for the cow's stage
   - Quality check was performed
   - No duplicate entries
   - Timestamp is appropriate for the session

4. Compare with historical data if needed
5. **If Approved:**
   - Click **"Approve"** (অনুমোদন করুন)
   - Add optional approval note
   - Entry moves to approved status

6. **If Rejected:**
   - Click **"Reject"** (প্রত্যাখ্যান করুন)
   - Select rejection reason:
     - Incorrect quantity
     - Wrong animal ID
     - Missing quality check
     - Duplicate entry
     - Other (specify)
   - Add detailed explanation
   - Worker will be notified to correct

**Bulk Approval:**

For entries that have passed automated validation:

1. Select multiple entries using checkboxes
2. Click **"Bulk Approve"**
3. Confirm the action

💡 **Tip:** Always spot-check random entries even when using bulk approval.

### 4.3 Quality Check Recording

Record and review milk quality parameters to maintain Smart Dairy's premium standards.

**Recording a Quality Check:**

1. Go to **Milk Production** > **Quality Checks** (গুণমান পরীক্ষা)
2. Click **"New Quality Check"**
3. **Screenshot Placeholder:** *[Quality Check Form]*
   - Shows: Input fields for all quality parameters

4. Select **Sample Type**:
   - Individual Cow Sample
   - Bulk Tank Sample
   - Session Composite

5. Enter **Sample Information**:
   - Sample ID (auto-generated or scan barcode)
   - Source (Cow ID / Tank / Session)
   - Collection Date & Time
   - Collected By (Worker name)

6. Enter **Quality Parameters**:

| Parameter | Acceptable Range | Unit |
|-----------|------------------|------|
| Fat Content | 3.5 - 4.5 | % |
| SNF (Solids-Not-Fat) | ≥ 8.5 | % |
| Density | 1.028 - 1.032 | g/mL |
| pH Level | 6.6 - 6.8 | - |
| Temperature | ≤ 4 | °C |
| Acidity | 0.14 - 0.16 | % |
| Bacterial Count | < 100,000 | CFU/mL |

7. Visual Inspection:
   - Color: □ Normal □ Abnormal
   - Odor: □ Normal □ Abnormal
   - Appearance: □ Normal □ Abnormal

8. Add any **Notes** or observations
9. Click **"Save Quality Record"**

**Quality Check Result:**

- **PASS** (উত্তীর্ণ): All parameters within range - Green indicator
- **WARNING** (সতর্কতা): Some parameters borderline - Yellow indicator
- **FAIL** (ব্যর্থ): Parameters out of range - Red indicator

⚠️ **Critical:** Any FAIL result triggers an alert and requires immediate supervisor action.

**Reviewing Quality Trends:**

1. Go to **Quality Dashboard**
2. View charts showing quality trends over time
3. Identify cows or periods with declining quality
4. Take corrective action as needed

### 4.4 Production Reports

Generate detailed reports for analysis and management review.

**Standard Production Reports:**

**Screenshot Placeholder:** *[Report Generation Screen]*
- Shows: Report type selector, date range picker, format options

| Report Type | Description | Frequency |
|-------------|-------------|-----------|
| Daily Summary | Total production for a day | Daily |
| Session Report | Production by milking session | Daily |
| Individual Cow Report | Per-cow production history | Weekly/Monthly |
| Group Comparison | Compare sheds/groups | Weekly |
| Quality Report | Quality metrics summary | Daily/Weekly |
| Worker Performance | Production by worker | Weekly |

**Generating a Report:**

1. Go to **Milk Production** > **Reports** (প্রতিবেদন)
2. Select **Report Type** from dropdown
3. Set **Date Range**:
   - Start Date (শুরুর তারিখ)
   - End Date (শেষের তারিখ)
4. Select **Filters** (if applicable):
   - Specific cows or groups
   - Sessions
   - Quality status
5. Choose **Output Format**:
   - View on Screen (পর্দায় দেখুন)
   - Export to Excel (এক্সেলে রপ্তানি)
   - Export to PDF (পিডিএফে রপ্তানি)
6. Click **"Generate Report"** (প্রতিবেদন তৈরি করুন)

**Report Export Options:**

- **Excel (.xlsx)**: For data analysis and manipulation
- **PDF (.pdf)**: For sharing and printing
- **CSV (.csv)**: For importing into other systems

### 4.5 Daily Milk Production Checklist

**Morning Shift (সকালের শিফট):**

- [ ] Review overnight production data
- [ ] Check all quality checks from previous evening are recorded
- [ ] Verify morning milking session is complete
- [ ] Approve worker entries from morning session
- [ ] Review any quality alerts
- [ ] Check cooling system temperature logs

**Evening Shift (সন্ধ্যার শিফট):**

- [ ] Review afternoon production data
- [ ] Approve pending entries
- [ ] Verify evening milking is complete
- [ ] Perform quality checks on bulk tank
- [ ] Generate daily summary report
- [ ] Note any anomalies for management

---

## 5. Health Management

### 5.1 Vaccination Schedules

Maintain up-to-date vaccination records to protect herd health.

**Viewing Vaccination Schedule:**

1. Go to **Health Management** > **Vaccination Schedule** (টিকা পরিকল্পনা)
2. **Screenshot Placeholder:** *[Vaccination Calendar View]*
   - Shows: Calendar with scheduled vaccinations, color-coded by priority

**Schedule Views:**

| View | Description | Bengali |
|------|-------------|---------|
| Calendar View | Monthly calendar with scheduled dates | ক্যালেন্ডার ভিউ |
| List View | Chronological list of all vaccinations | তালিকা ভিউ |
| By Animal | Vaccinations grouped by animal | প্রাণী অনুযায়ী |
| By Vaccine | Vaccinations grouped by type | টিকার ধরন অনুযায়ী |

**Standard Vaccination Schedule:**

| Vaccine | Target Animals | Frequency | Bengali |
|---------|----------------|-----------|---------|
| FMD (Foot & Mouth Disease) | All cattle | Every 6 months | খুরা-মুখ রোগ |
| Anthrax | All cattle | Annual | অ্যানথ্রাক্স |
| Black Quarter | Young stock | Annual | ব্ল্যাক কোয়ার্টার |
| Hemorrhagic Septicemia | All cattle | Annual | রক্তপাতী সেপ্টিসেমিয়া |
| Brucellosis | Breeding females | As per vet advice | ব্রুসেলোসিস |

**Adding a Scheduled Vaccination:**

1. Click **"Schedule Vaccination"** (টিকা নির্ধারণ করুন)
2. **Screenshot Placeholder:** *[Schedule Vaccination Form]*
   - Shows: Vaccine dropdown, animal selection, date picker

3. Select **Vaccine Type** from dropdown
4. Select **Target Animals**:
   - Individual selection
   - Group selection (by age, status, location)
   - All herd
5. Set **Scheduled Date**
6. Assign **Responsible Person** (Veterinarian/Worker)
7. Add **Notes** (manufacturer, batch number if known)
8. Click **"Schedule"**

**Recording Vaccination Completion:**

1. Find the scheduled vaccination in the list
2. Click **"Record Administration"** (প্রয়োগ রেকর্ড করুন)
3. **Screenshot Placeholder:** *[Vaccination Record Form]*
   - Shows: Date/time, actual animals vaccinated, batch details

4. Confirm actual animals vaccinated (may differ from plan)
5. Enter **Batch Number** and **Expiry Date**
6. Record **Administrator** (who gave the vaccine)
7. Add any **Reactions** or observations
8. Upload **Photo** of vaccination record (optional)
9. Click **"Mark as Complete"**

### 5.2 Health Alerts

Monitor and respond to health-related alerts and notifications.

**Accessing Health Alerts:**

1. Go to **Health Management** > **Alerts** (স্বাস্থ্য সতর্কতা)
2. Or view the **Alerts Widget** on the main dashboard
3. **Screenshot Placeholder:** *[Health Alerts Panel]*
   - Shows: List of alerts with priority indicators

**Alert Categories:**

| Priority | Color | Examples | Bengali |
|----------|-------|----------|---------|
| **Critical** | 🔴 Red | Disease outbreak, emergency | জরুরি |
| **High** | 🟠 Orange | Vaccination overdue, abnormal health | উচ্চ |
| **Medium** | 🟡 Yellow | Scheduled checks due, approaching dates | মাঝারি |
| **Low** | 🟢 Green | Routine reminders, information | কম |

**Common Health Alerts:**

| Alert Type | Description | Action Required |
|------------|-------------|-----------------|
| Vaccination Due | Animal needs vaccination | Schedule/administer |
| Vaccination Overdue | Missed vaccination date | Urgent scheduling |
| Health Check Due | Regular examination needed | Arrange vet visit |
| Treatment Reminder | Follow-up treatment needed | Administer medicine |
| Abnormal Parameter | Temperature/production irregular | Investigate |
| Quarantine Expiry | Quarantine period ending | Review status |

**Responding to Alerts:**

1. Click on the alert to view details
2. **Screenshot Placeholder:** *[Alert Detail View]*
   - Shows: Alert details, related animal, suggested actions

3. Take appropriate action:
   - **Dismiss:** If alert is resolved or incorrect
   - **Snooze:** Remind me later (set time)
   - **Act:** Go to relevant module to take action
   - **Assign:** Delegate to specific worker

4. Add **Notes** about action taken
5. Mark as **Resolved** when complete

### 5.3 Treatment Records

Document all health treatments and medical interventions.

**Recording a New Treatment:**

1. Go to **Health Management** > **Treatment Records** (চিকিৎসার তথ্য)
2. Click **"New Treatment"** (নতুন চিকিৎসা)
3. **Screenshot Placeholder:** *[Treatment Record Form]*
   - Shows: Animal search, diagnosis, treatment details

4. Select **Animal** (search by ID or name)
5. Enter **Diagnosis Information**:
   - Date Observed (পর্যবেক্ষণের তারিখ)
   - Symptoms (লক্ষণ)
   - Suspected Condition (অনুমানিত রোগ)
   - Severity: Mild / Moderate / Severe / Critical

6. Enter **Treatment Details**:
   - Treatment Date
   - Treatment Type:
     - Medication (ওষুধ)
     - Veterinary Procedure (ভেটেরিনারি পদ্ধতি)
     - Surgery (সার্জারি)
     - Other
   - Medications Given:
     - Medicine name
     - Dosage (পরিমাণ)
     - Route (oral/injection/topical)
     - Duration
   - Veterinarian Name
   - Cost (if applicable)

7. **Follow-up Schedule** (if needed):
   - Set reminder date
   - Add follow-up notes

8. Upload relevant **Documents**:
   - Veterinary prescription
   - Lab reports
   - Photos of condition

9. Click **"Save Treatment Record"**

**Viewing Treatment History:**

1. Open animal profile
2. Click **"Health"** tab
3. View complete treatment history
4. Filter by date range or condition

### 5.4 Veterinary Appointments

Schedule and manage veterinary visits.

**Scheduling an Appointment:**

1. Go to **Health Management** > **Appointments** (ভেটেরিনারি অ্যাপয়েন্টমেন্ট)
2. Click **"New Appointment"**
3. **Screenshot Placeholder:** *[Appointment Scheduling Form]*
   - Shows: Vet selection, date/time, purpose, animals list

4. Select **Veterinarian**:
   - Internal Vet (if available)
   - External Vet (select from directory)
5. Set **Date and Time**
6. Select **Purpose**:
   - Routine Check-up (নিয়মিত পরীক্ষা)
   - Pregnancy Check (গর্ভাবস্থা পরীক্ষা)
   - Treatment (চিকিৎসা)
   - Emergency (জরুরি)
   - Vaccination (টিকা)
   - Other

7. Select **Animals** to be examined:
   - Individual selection
   - Group selection
8. Add **Description** of issues/concerns
9. Click **"Schedule Appointment"**

**Managing Appointments:**

| Status | Description | Action |
|--------|-------------|--------|
| Scheduled | Appointment confirmed | Prepare animals |
| In Progress | Vet currently on farm | Accompany vet |
| Completed | Visit finished | Record outcomes |
| Cancelled | Appointment cancelled | Reschedule if needed |
| No Show | Vet didn't arrive | Follow up |

**Recording Appointment Outcomes:**

1. Open the appointment record
2. Click **"Record Outcomes"**
3. Add examination findings
4. Record any treatments given
5. Upload vet report
6. Mark as **Complete**

### 5.5 Daily Health Management Checklist

**Daily Tasks:**

- [ ] Review all critical and high-priority health alerts
- [ ] Check animals in treatment - verify medication given
- [ ] Review quarantine animals status
- [ ] Check for any new health concerns reported by workers
- [ ] Verify temperature logs for all sheds

**Weekly Tasks:**

- [ ] Review upcoming vaccination schedule
- [ ] Check for any overdue vaccinations
- [ ] Review treatment effectiveness
- [ ] Schedule routine veterinary checks
- [ ] Generate health summary report

---

## 6. Worker Management

### 6.1 Assign Tasks

Create and assign tasks to farm workers.

**Creating a New Task:**

1. Go to **Worker Management** > **Tasks** (কর্মসূচি)
2. Click **"Create Task"** (কাজ তৈরি করুন)
3. **Screenshot Placeholder:** *[Task Creation Form]*
   - Shows: Task details, assignment, scheduling fields

4. Enter **Task Details**:
   - Task Title (কাজের শিরোনাম)
   - Description (বিবরণ)
   - Task Type:
     - Milking (দোহন)
     - Feeding (খাবার)
     - Cleaning (পরিষ্কার)
     - Health Care (স্বাস্থ্যসেবা)
     - Maintenance (রক্ষণাবেক্ষণ)
     - Record Keeping (রেকর্ড রাখা)
     - Other (অন্যান্য)

5. **Assignment**:
   - Assign to: Specific Worker / Worker Group / Open
   - Priority: Low / Medium / High / Urgent
   - Estimated Duration

6. **Scheduling**:
   - Start Date & Time
   - Due Date & Time
   - Recurring: Yes/No (if yes, set frequency)

7. **Location**:
   - Select area/shed where task should be performed

8. Add **Checklist Items** (optional):
   - Break task into sub-steps
   - Worker must check off each item

9. Attach **Files/Photos** if needed
10. Click **"Create and Assign"**

**Task Status Tracking:**

| Status | Description | Bengali |
|--------|-------------|---------|
| Draft | Task created but not assigned | খসড়া |
| Assigned | Worker notified of task | নির্ধারিত |
| In Progress | Worker started task | চলমান |
| Completed | Worker finished task | সম্পন্ন |
| Overdue | Past due date | মেয়াদোত্তীর্ণ |
| Cancelled | Task cancelled | বাতিল |

**Monitoring Task Progress:**

1. Go to **Worker Management** > **Task Board** (কর্মসূচি বোর্ড)
2. **Screenshot Placeholder:** *[Task Board View]*
   - Shows: Kanban-style board with task cards in columns

3. View tasks by status
4. Click any task to see details
5. Add comments or updates
6. Reassign if needed

### 6.2 Review Worker Performance

Track and evaluate worker productivity and performance.

**Accessing Performance Dashboard:**

1. Go to **Worker Management** > **Performance** (কর্মক্ষমতা)
2. **Screenshot Placeholder:** *[Performance Dashboard]*
   - Shows: Worker cards with key metrics, charts

**Performance Metrics:**

| Metric | Description | Bengali |
|--------|-------------|---------|
| Tasks Completed | Number of tasks finished | সম্পন্ন কাজ |
| On-Time Rate | % of tasks completed on time | সময়মতো সম্পন্ন |
| Quality Score | Rating based on task quality | গুণমান স্কোর |
| Attendance | Days present vs. scheduled | উপস্থিতি |
| Production Contribution | Milk records entered / accuracy | উৎপাদন অবদান |
| Issues Reported | Problems identified and reported | প্রতিবেদিত সমস্যা |

**Individual Worker Review:**

1. Click on worker name or **"View Details"**
2. **Screenshot Placeholder:** *[Worker Performance Detail]*
   - Shows: Detailed metrics, recent tasks, activity timeline

3. Review:
   - Task completion history
   - Production data entry accuracy
   - Attendance record
   - Supervisor notes
   - Peer feedback

4. Add **Supervisor Notes**:
   - Performance observations
   - Areas for improvement
   - Recognition for good work

5. Set **Performance Rating** (if required):
   - Excellent (অসামান্য)
   - Good (ভালো)
   - Satisfactory (সন্তোষজনক)
   - Needs Improvement (উন্নতি প্রয়োজন)
   - Unsatisfactory (অসন্তোষজনক)

**Generating Performance Reports:**

1. Go to **Reports** > **Worker Performance**
2. Select **Date Range**
3. Select **Workers** (individual or all)
4. Choose **Format** (PDF/Excel)
5. Click **"Generate Report"**

### 6.3 Approve Timesheets

Review and approve worker timesheets for payroll processing.

**Accessing Timesheets:**

1. Go to **Worker Management** > **Timesheets** (সময়সূচি)
2. **Screenshot Placeholder:** *[Timesheet List View]*
   - Shows: List of workers with timesheet status

3. Select **Period** to review (week/bi-weekly/month)

**Timesheet Review Process:**

**Screenshot Placeholder:** *[Timesheet Detail View]*
- Shows: Daily entries, hours worked, overtime, absences

| Column | Description | Bengali |
|--------|-------------|---------|
| Date | Work date | তারিখ |
| Scheduled Hours | Expected work hours | নির্ধারিত সময় |
| Actual Hours | Hours worked | কাজ করা সময় |
| Overtime | Extra hours | অতিরিক্ত সময় |
| Break Duration | Rest time | বিরতির সময় |
| Status | Present/Absent/Leave | অবস্থা |
| Notes | Any remarks | মন্তব্য |

**Steps to Approve Timesheet:**

1. Click **"Review"** on pending timesheet
2. Verify entries against:
   - Attendance logs
   - Task completion records
   - Production entry timestamps
3. Check for discrepancies:
   - Unexplained absences
   - Excessive overtime
   - Missing entries
4. **If Correct:**
   - Click **"Approve"** (অনুমোদন করুন)
   - Add approval note if needed
5. **If Issues Found:**
   - Click **"Request Correction"**
   - Specify the issues
   - Worker will be notified
6. **Reject** if fraudulent or major errors:
   - Document reason
   - Escalate to management if needed

**Bulk Approval:**

1. Select multiple approved-looking timesheets
2. Click **"Bulk Approve"**
3. Confirm action

⚠️ **Important:** Timesheet approval affects payroll. Review carefully.

### 6.4 Worker Management Checklist

**Daily Tasks:**

- [ ] Review pending task assignments
- [ ] Check for overdue tasks
- [ ] Monitor task completion progress
- [ ] Address worker questions or issues
- [ ] Verify critical tasks are completed

**Weekly Tasks:**

- [ ] Review timesheets and approve by deadline
- [ ] Assess worker performance metrics
- [ ] Schedule one-on-one meetings as needed
- [ ] Plan next week's task assignments
- [ ] Update worker schedules if needed

**Monthly Tasks:**

- [ ] Complete formal performance reviews
- [ ] Generate monthly performance reports
- [ ] Identify training needs
- [ ] Recognize top performers
- [ ] Address persistent performance issues

---

## 7. Reports & Analytics

### 7.1 Production Reports

Generate comprehensive production analysis reports.

**Available Production Reports:**

**Screenshot Placeholder:** *[Reports Menu Screenshot]*
- Shows: All available report categories

| Report Name | Description | Use Case |
|-------------|-------------|----------|
| Daily Production Summary | Total yield by session | Daily management |
| Milk Composition Report | Fat, SNF, density analysis | Quality control |
| Individual Cow Production | Per-cow lifetime production | Breeding decisions |
| Lactation Curve Analysis | Production by lactation stage | Health monitoring |
| Comparative Production | Period-over-period comparison | Trend analysis |
| Peak Production Analysis | Highest yielding periods | Optimization |
| Production Forecast | Projected future yields | Planning |

**Generating a Production Report:**

1. Go to **Reports** > **Production** (উৎপাদন প্রতিবেদন)
2. Select **Report Type**
3. Set **Parameters**:
   - Date range
   - Animal groups (if applicable)
   - Sessions
   - Metrics to include
4. Click **"Preview"** to see on-screen
5. Click **"Export"** for download options

**Report Features:**

- **Charts and Graphs:** Visual representation of data
- **Drill-down:** Click data points for details
- **Filtering:** Apply filters to refine results
- **Comparison:** Compare multiple periods or groups
- **Annotations:** Add comments to reports

### 7.2 Animal Health Reports

Monitor herd health status and trends.

**Health Report Types:**

| Report | Description | Bengali |
|--------|-------------|---------|
| Health Summary | Overall herd health status | স্বাস্থ্য সারসংক্ষেপ |
| Vaccination Status | Up-to-date vs. overdue vaccinations | টিকা অবস্থা |
| Disease Incidence | Cases by type and time | রোগের ঘটনা |
| Treatment Effectiveness | Recovery rates and outcomes | চিকিৎসার কার্যকারিতা |
| Veterinary Costs | Expense analysis | ভেটেরিনারি ব্যয় |
| Mortality Report | Deaths and causes | মৃত্যুর প্রতিবেদন |
| Breeding Success | Conception rates | প্রজনন সফলতা |

**Health Dashboard:**

**Screenshot Placeholder:** *[Health Analytics Dashboard]*
- Shows: Key health KPIs, trend charts, alert summaries

**Key Health KPIs:**

| KPI | Target | Description |
|-----|--------|-------------|
| Vaccination Coverage | 100% | All eligible animals vaccinated |
| Treatment Success Rate | >90% | Animals recovering from illness |
| Disease Incidence Rate | <5% | % of herd affected monthly |
| Mortality Rate | <2% annually | Deaths per year |
| Average Days to Recovery | <7 days | Time to return to production |

### 7.3 Worker Productivity Reports

Analyze workforce efficiency and output.

**Productivity Metrics:**

| Metric | Formula | Purpose |
|--------|---------|---------|
| Tasks per Worker | Total tasks / number of workers | Workload distribution |
| Completion Rate | Completed / Assigned × 100 | Efficiency |
| On-Time Performance | On-time completions / Total × 100 | Reliability |
| Production per Labor Hour | Liters milked / labor hours | Efficiency |
| Error Rate | Incorrect entries / Total entries × 100 | Accuracy |
| Attendance Rate | Days present / Scheduled × 100 | Reliability |

**Generating Productivity Reports:**

1. Go to **Reports** > **Worker Productivity**
2. Select **Time Period**
3. Choose **Grouping**: Individual / Team / Shift
4. Select **Metrics** to include
5. Generate report

**Team Comparison:**

Compare performance across:
- Shifts (Morning/Afternoon/Evening)
- Work areas (Milking/Feeding/Cleaning)
- Experience levels

### 7.4 Export to Excel/PDF

All reports can be exported for external use.

**Export Options:**

**Screenshot Placeholder:** *[Export Options Dialog]*
- Shows: Format selection, page setup, delivery options

| Format | Extension | Best For |
|--------|-----------|----------|
| Excel | .xlsx | Data analysis, manipulation |
| PDF | .pdf | Sharing, printing, presentations |
| CSV | .csv | Import to other systems |
| Word | .docx | Editable reports with notes |

**Export Settings:**

1. **Page Setup** (for PDF):
   - Orientation: Portrait/Landscape
   - Page size: A4/Letter
   - Margins
   - Header/Footer

2. **Content Options**:
   - Include charts: Yes/No
   - Include raw data: Yes/No
   - Include summary: Yes/No

3. **Delivery**:
   - Download immediately
   - Email to recipients
   - Save to document library

**Scheduled Reports:**

Set up automatic report generation:

1. Create report with desired parameters
2. Click **"Schedule"**
3. Set frequency: Daily/Weekly/Monthly
4. Add recipients
5. Choose format
6. Save schedule

### 7.5 Report Checklist

**Daily Reports:**

- [ ] Production summary (previous day)
- [ ] Quality check summary
- [ ] Health alerts report
- [ ] Pending approvals report

**Weekly Reports:**

- [ ] Weekly production analysis
- [ ] Worker productivity summary
- [ ] Health status report
- [ ] Task completion report

**Monthly Reports:**

- [ ] Monthly production report
- [ ] Comprehensive health report
- [ ] Worker performance report
- [ ] Financial summary (veterinary, supplies)

---

## 8. Mobile App

### 8.1 Mobile App Overview

The Smart Dairy mobile app extends portal functionality to smartphones for on-the-go access.

**Supported Platforms:**
- Android 8.0+ (Google Play Store)
- iOS 13+ (Apple App Store)

**Screenshot Placeholder:** *[Mobile App Login Screen]*
- Shows: App login interface, logo, biometric option

### 8.2 Supervisor Features on Mobile

**Available Mobile Features:**

| Feature | Capability | Bengali |
|---------|------------|---------|
| **Dashboard** | View key metrics on mobile | ড্যাশবোর্ড |
| **Notifications** | Receive push alerts | বিজ্ঞপ্তি |
| **Quick Approvals** | Approve entries from phone | দ্রুত অনুমোদন |
| **Animal Lookup** | Scan RFID, view animal info | প্রাণী তথ্য |
| **Task Management** | Create and assign tasks | কাজ ব্যবস্থাপনা |
| **Photo Upload** | Capture and upload photos | ছবি আপলোড |
| **Offline Mode** | Work without internet | অফলাইন মোড |
| **Voice Notes** | Record voice memos | ভয়েস নোট |

### 8.3 Installing and Setting Up

**Installation:**

1. Open **Google Play Store** (Android) or **App Store** (iOS)
2. Search for **"Smart Dairy Portal"**
3. Tap **Install**
4. Wait for download and installation

**First-Time Setup:**

1. Open the app
2. Enter **Server URL**: `portal.smartdairybd.com`
3. Enter your **Username** and **Password**
4. Tap **Login**
5. Enable **Biometric Login** (fingerprint/face) if desired
6. Enable **Push Notifications** for alerts

**Screenshot Placeholder:** *[Mobile App Dashboard]*
- Shows: Mobile-optimized dashboard with cards and quick actions

### 8.4 Using Key Mobile Features

**Quick Approval Workflow:**

1. Receive push notification: "Milk entry pending approval"
2. Tap notification to open app
3. Review entry details
4. Tap **"Approve"** or **"Reject"**
5. Add quick note if rejecting
6. Done!

**RFID Scanning:**

1. Tap **"Scan RFID"** on home screen
2. Hold phone near animal's RFID tag (if NFC-enabled)
3. Or manually enter tag number
4. View animal profile instantly

**Photo Documentation:**

1. Navigate to relevant record
2. Tap **"Add Photo"**
3. Choose: Camera or Gallery
4. Take or select photo
5. Add caption/description
6. Upload

**Offline Mode:**

When internet is unavailable:

1. App automatically switches to offline mode
2. Record data normally
3. Data is saved locally on phone
4. When internet returns, tap **"Sync"**
5. All data uploads to server

⚠️ **Note:** Enable sync before leaving the farm to ensure all data is uploaded.

### 8.5 Mobile App Best Practices

✅ **Best Practices:**

- Keep app updated to latest version
- Use strong password or biometric login
- Enable notifications for critical alerts
- Sync data regularly when in offline mode
- Use Wi-Fi when available to save mobile data
- Report app issues through feedback feature

---

## 9. Troubleshooting

### 9.1 Common Login Problems

| Problem | Cause | Solution |
|---------|-------|----------|
| "Invalid username or password" | Wrong credentials | Check Caps Lock, re-enter carefully |
| "Account locked" | Too many failed attempts | Contact IT to unlock |
| "Session expired" | Inactive too long | Log in again |
| "Two-factor authentication failed" | Wrong code | Check time on phone, re-enter code |
| Page won't load | Internet issue | Check connection, refresh page |
| "Browser not supported" | Old browser | Update browser or use Chrome/Firefox |

### 9.2 Data Entry Issues

| Problem | Cause | Solution |
|---------|-------|----------|
| Form won't submit | Required fields empty | Check for red asterisk (*) fields |
| "Animal ID not found" | Wrong ID entered | Verify ID, use search function |
| Cannot select future date | Date validation | Select today's date or earlier |
| File upload fails | File too large | Reduce image size (<5MB) |
| Dropdown not working | Browser issue | Refresh page, try different browser |
| Changes not saved | Session timeout | Log in again, re-enter data |

### 9.3 Report Generation Issues

| Problem | Cause | Solution |
|---------|-------|----------|
| "No data found" | Wrong date range | Expand date range |
| Report timeout | Too much data | Reduce date range, filter more |
| PDF won't open | No PDF reader | Install Adobe Reader or similar |
| Excel format wrong | Encoding issue | Save as CSV first, then open in Excel |
| Charts not displaying | JavaScript disabled | Enable JavaScript in browser |

### 9.4 Mobile App Issues

| Problem | Cause | Solution |
|---------|-------|----------|
| App crashes | Memory issue | Close other apps, restart phone |
| Won't sync | No internet | Check connection, try again later |
| Can't scan RFID | NFC not enabled | Enable NFC in phone settings |
| Photos won't upload | Storage full | Free up space on phone |
| Notifications not received | Permissions denied | Enable notifications in settings |
| Biometric login fails | Sensor dirty | Clean sensor, try again |

### 9.5 Getting Help

**Before Contacting Support:**

1. Try refreshing the page (F5)
2. Clear browser cache and cookies
3. Try a different browser
4. Log out and log back in
5. Check internet connection

**Information to Provide:**

When reporting issues, include:
- Your username
- Time issue occurred
- Page/module you were using
- Exact error message
- Screenshot if possible
- Steps to reproduce the issue

---

## 10. Support

### 10.1 Contact Information

**Technical Support (প্রযুক্তিগত সহায়তা):**

| Channel | Details | Availability |
|---------|---------|--------------|
| **Help Desk Hotline** | +880-XXXX-XXXXXX | 24/7 for critical issues |
| **IT Support Email** | support@smartdairybd.com | Response within 4 hours |
| **Ticket System** | https://support.smartdairybd.com | Track issue status |
| **WhatsApp Support** | +880-XXXX-XXXXXX | Business hours |

**Farm Operations Support (খামার পরিচালনা সহায়তা):**

| Channel | Details | Availability |
|---------|---------|--------------|
| **Farm Manager** | Mr. [Name] | Office hours |
| **Operations Email** | operations@smartdairybd.com | Response within 1 business day |
| **Training Lead** | [Name] | By appointment |

**Emergency Contacts (জরুরি যোগাযোগ):**

| Situation | Contact | Phone |
|-----------|---------|-------|
| System Down/Critical | IT Emergency | +880-XXXX-XXXXXX |
| Health Emergency | Farm Veterinarian | +880-XXXX-XXXXXX |
| Security Issue | Security Desk | +880-XXXX-XXXXXX |
| Management Escalation | Farm Operations Manager | +880-XXXX-XXXXXX |

### 10.2 Training Resources

**Available Training:**

| Resource | Description | Access |
|----------|-------------|--------|
| **Video Tutorials** | Step-by-step video guides | Portal > Help > Videos |
| **User Guides** | PDF manuals for all roles | Portal > Help > Documents |
| **FAQ Section** | Common questions answered | Portal > Help > FAQ |
| **Webinars** | Live training sessions | Monthly schedule |
| **In-Person Training** | Hands-on training | By arrangement |

**Requesting Training:**

Email: training@smartdairybd.com
Include:
- Your name and role
- Topics you need training on
- Preferred date/time
- Number of participants

### 10.3 Feedback and Suggestions

We value your feedback to improve the system.

**Submitting Feedback:**

1. Go to **Help** > **Feedback** in the portal
2. Or email: feedback@smartdairybd.com

**Feedback Categories:**
- Feature Request (নতুন বৈশিষ্ট্য অনুরোধ)
- Bug Report (ত্রুটি প্রতিবেদন)
- Usability Improvement (ব্যবহারযোগ্যতা উন্নতি)
- General Comment (সাধারণ মন্তব্য)

### 10.4 Document Updates

This manual is updated periodically. Check the version number on page 1.

**Latest Version:** Available at https://docs.smartdairybd.com

---

## Appendices

### Appendix A: Keyboard Shortcuts

| Shortcut | Action |
|----------|--------|
| `Ctrl + H` | Go to Home/Dashboard |
| `Ctrl + S` | Save current form |
| `Ctrl + F` | Open search |
| `Esc` | Close modal/dialog |
| `Alt + N` | Create new record |
| `Alt + R` | Generate report |
| `F5` | Refresh page |
| `?` | Show keyboard shortcuts help |

### Appendix B: Quick Reference Cards

**Daily Supervisor Tasks (দৈনিক তদারকির কাজ):**

```
□ Morning (8:00 AM)
  □ Review overnight alerts
  □ Check production dashboard
  □ Review pending approvals
  
□ Midday (12:00 PM)
  □ Check task completion
  □ Review worker entries
  
□ Evening (6:00 PM)
  □ Approve all pending entries
  □ Generate daily report
  □ Check tomorrow's schedule
```

### Appendix C: Bengali Translation Quick Reference

**Common System Terms:**

| English | Bengali (বাংলা) |
|---------|----------------|
| Save | সংরক্ষণ করুন |
| Cancel | বাতিল করুন |
| Edit | সম্পাদনা করুন |
| Delete | মুছে ফেলুন |
| Search | অনুসন্ধান করুন |
| Filter | ফিল্টার |
| Export | রপ্তানি করুন |
| Import | আমদানি করুন |
| Print | প্রিন্ট করুন |
| Help | সাহায্য |
| Settings | সেটিংস |
| Logout | লগআউট |

---

## Document Control

### Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Technical Writer | Initial release |

### Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Training Lead | [Name] | ____________ | _______ |
| Farm Operations Manager | [Name] | ____________ | _______ |
| IT Manager | [Name] | ____________ | _______ |

### Distribution

| Copy | Holder | Location |
|------|--------|----------|
| Master | Training Lead | Office |
| Copy 1 | Farm Operations Manager | Farm Office |
| Copy 2 | IT Department | IT Office |
| Electronic | All Supervisors | Portal Access |

---

**End of Document K-005**

---

*© 2026 Smart Dairy Ltd. All rights reserved.*
*Smart Dairy Ltd., Islambag Kali, Vulta, Rupgonj, Narayanganj, Bangladesh*
*www.smartdairybd.com*
