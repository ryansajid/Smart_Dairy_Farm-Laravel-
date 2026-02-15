# K-011: Training Materials - eLearning Modules

## Smart Dairy Ltd. - Smart Web Portal System Implementation

---

| Document Control | |
|------------------|---|
| **Document ID** | K-011 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Instructional Designer |
| **Owner** | Training Lead |
| **Reviewer** | HR Manager |
| **Status** | Draft |
| **Classification** | Internal Use |

---

## Document History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | 2026-01-31 | Instructional Designer | Initial version - eLearning modules framework |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [eLearning Platform](#2-elearning-platform)
3. [Module Structure](#3-module-structure)
4. [Course Catalog by Role](#4-course-catalog-by-role)
5. [Content Development](#5-content-development)
6. [Module Examples](#6-module-examples)
7. [Assessments](#7-assessments)
8. [Tracking & Reporting](#8-tracking--reporting)
9. [Content Maintenance](#9-content-maintenance)
10. [Appendices](#10-appendices)

---

## 1. Introduction

### 1.1 Overview

The Smart Dairy Ltd. eLearning program provides comprehensive, self-paced digital training for all employees using the Smart Web Portal System. This program ensures consistent knowledge transfer across all roles while accommodating different learning styles, technical proficiencies, and language requirements.

### 1.2 eLearning Strategy

Our eLearning strategy is built on the following principles:

| Principle | Description |
|-----------|-------------|
| **Microlearning** | Bite-sized modules (10-20 minutes) for better retention |
| **Role-Based** | Tailored content for specific job functions |
| **Multilingual** | Bengali support for frontline workers |
| **Mobile-First** | Responsive design for smartphone access |
| **Blended Learning** | Combination of self-paced and instructor-led elements |
| **Continuous Improvement** | Regular content updates based on feedback |

### 1.3 Learning Objectives

By completing the eLearning program, employees will be able to:

1. **Navigate** the Smart Web Portal System confidently
2. **Perform** role-specific tasks efficiently and accurately
3. **Identify** and troubleshoot common issues
4. **Generate** and interpret relevant reports
5. **Follow** company procedures and compliance requirements
6. **Support** colleagues in system usage

### 1.4 Target Audience

| Audience | Size (Est.) | Primary Language | Technical Level |
|----------|-------------|------------------|-----------------|
| Farm Workers | 150+ | Bengali | Beginner |
| Farm Supervisors | 25 | English/Bengali | Intermediate |
| Sales Team | 30 | English | Intermediate |
| Warehouse Staff | 40 | Bengali/English | Beginner-Intermediate |
| Accounting | 15 | English | Intermediate-Advanced |
| System Admins | 8 | English | Advanced |

### 1.5 Success Metrics

- **Completion Rate**: 95% of assigned users within 30 days
- **Pass Rate**: 90% achieving 80% or higher on assessments
- **Satisfaction**: 4.0/5.0 average course rating
- **Knowledge Retention**: 85% score on follow-up assessments at 90 days

---

## 2. eLearning Platform

### 2.1 Platform Selection

**Primary Platform**: Moodle 4.3+ (Open Source LMS)

| Feature | Specification |
|---------|---------------|
| Platform | Moodle 4.3 or higher |
| Hosting | Cloud-hosted (AWS/Azure) |
| SCORM Version | 1.2 and 2004 4th Edition |
| xAPI (Tin Can) | Supported for advanced tracking |
| Mobile App | Moodle Mobile 4.3+ |
| Offline Access | Supported via mobile app |

### 2.2 Technical Requirements

#### Server Requirements

| Component | Minimum | Recommended |
|-----------|---------|-------------|
| PHP Version | 8.0 | 8.1 or 8.2 |
| Database | MySQL 5.7 | MariaDB 10.6+ |
| RAM | 4 GB | 8 GB |
| Storage | 100 GB | 500 GB SSD |
| Bandwidth | 100 Mbps | 1 Gbps |

#### Client Requirements

| Device Type | Specification |
|-------------|---------------|
| **Desktop** | Windows 10/11, macOS 12+, Chrome OS |
| **Browsers** | Chrome 110+, Firefox 110+, Edge 110+, Safari 16+ |
| **Mobile** | Android 10+, iOS 15+ |
| **Screen** | Minimum 360px width for mobile, 1024px for desktop |
| **Internet** | 2 Mbps minimum, 5 Mbps recommended |

### 2.3 Platform Configuration

#### Authentication
- Single Sign-On (SSO) integration with Smart Dairy AD/LDAP
- Multi-factor authentication for admin roles
- Self-service password reset

#### Course Organization
```
Smart Dairy LMS
├── Farm Operations
│   ├── Farm Workers (Bengali)
│   └── Farm Supervisors
├── Commercial Operations
│   ├── Sales Team
│   └── Warehouse Staff
├── Finance & Admin
│   ├── Accounting
│   └── System Administration
├── Compliance & Safety
│   ├── Food Safety
│   ├── Animal Welfare
│   └── Data Privacy
└── Professional Development
    ├── Leadership
    └── Technical Skills
```

### 2.4 SCORM/xAPI Compliance

#### SCORM 2004 4th Edition Support

| Element | Implementation |
|---------|----------------|
| Content Packaging | IMS Manifest 1.2 compliant |
| Runtime Environment | JavaScript API adapter |
| Data Model | Full CMI data model support |
| Sequencing & Navigation | Flow-based navigation |

#### xAPI (Experience API) Integration

**Statement Structure**:
```json
{
  "actor": {
    "mbox": "mailto:user@smartdairy.com",
    "name": "Employee Name"
  },
  "verb": {
    "id": "http://adlnet.gov/expapi/verbs/completed",
    "display": {"en-US": "completed"}
  },
  "object": {
    "id": "http://smartdairy.com/modules/FW-001",
    "definition": {
      "name": {"en-US": "Daily Data Entry"}
    }
  },
  "result": {
    "score": {"scaled": 0.85},
    "success": true,
    "completion": true
  },
  "context": {
    "platform": "Moodle Mobile",
    "language": "bn"
  }
}
```

---

## 3. Module Structure

### 3.1 Standard Module Template

Every eLearning module follows a consistent structure to ensure familiarity and ease of use.

#### Module Components

| Section | Duration | Description |
|---------|----------|-------------|
| **Pre-Assessment** (Optional) | 3-5 min | Gauge prior knowledge |
| **Learning Objectives** | 1 min | Clear statement of what learners will achieve |
| **Content Sections** | 10-15 min | Main learning content in segments |
| **Interactive Elements** | 3-5 min | Simulations, drag-drop, scenario-based |
| **Knowledge Check** | 3-5 min | Formative assessment questions |
| **Summary** | 2 min | Key takeaways recap |
| **Post-Assessment** | 5-10 min | Summative assessment (graded) |
| **Resources** | - | Downloadable job aids, references |

#### Module Metadata Template

```yaml
module_id: "XX-###"
title: "Module Title"
version: "1.0"
language: "en" | "bn"
duration_minutes: 20
difficulty: "Beginner" | "Intermediate" | "Advanced"
role: "Farm Worker" | "Supervisor" | "Sales" | "Warehouse" | "Accounting" | "Admin"
prerequisites: ["module_id_1", "module_id_2"]
objectives:
  - "Objective 1"
  - "Objective 2"
  - "Objective 3"
tags: ["data-entry", "reports", "compliance"]
scorm_version: "2004_4th"
certificate_eligible: true
```

### 3.2 Duration and Format Standards

| Module Type | Duration | Format | Use Case |
|-------------|----------|--------|----------|
| **Micro-Learning** | 5-10 min | Video + Quiz | Quick updates, refreshers |
| **Standard Module** | 15-25 min | Interactive + Assessment | Core skills |
| **Comprehensive** | 30-45 min | Multi-scenario + Case study | Complex topics |
| **Certification** | 60+ min | Full exam | Role certification |

### 3.3 Content Format Guidelines

#### Video Specifications

| Parameter | Specification |
|-----------|---------------|
| Resolution | 1080p (1920x1080) |
| Aspect Ratio | 16:9 |
| Frame Rate | 30 fps |
| Audio | AAC 128 kbps stereo |
| Maximum Length | 5 minutes per video |
| Captions | Required (Bengali + English) |
| File Format | MP4 (H.264) |

#### Interactive Elements

| Element Type | Tool | Usage |
|--------------|------|-------|
| Drag and Drop | H5P | Matching, sequencing |
| Hotspots | Articulate Storyline | System navigation training |
| Branching Scenarios | Storyline/Rise | Decision-making practice |
| Simulations | Screen recordings | Software practice |
| Fill in Blanks | H5P | Data entry practice |
| Multiple Choice | Native Moodle | Knowledge checks |

---

## 4. Course Catalog by Role

### 4.1 Farm Workers (Bengali Language)

**Target Audience**: 150+ field workers, predominantly Bengali-speaking
**Delivery**: Mobile-first, offline-capable
**Total Duration**: 8 hours

| Code | Module Title | Duration | Description |
|------|--------------|----------|-------------|
| **FW-101** | স্মার্ট ডেইরি সিস্টেম পরিচিতি (Introduction to Smart Dairy System) | 45 min | System overview, login, basic navigation |
| **FW-102** | দৈনিক তথ্য ইনপুট (Daily Data Entry) | 60 min | Milk collection, quality checks, farmer records |
| **FW-103** | গাভী ব্যবস্থাপনা (Cattle Management) | 45 min | Health records, breeding, vaccination tracking |
| **FW-104** | সমস্যা সমাধান (Troubleshooting Basics) | 30 min | Common issues, who to contact, basic fixes |
| **FW-105** | নিরাপত্তা ও স্বাস্থ্য (Safety and Health) | 30 min | Workplace safety, hygiene, emergency procedures |

**Learning Path**:
```
FW-101 → FW-102 → FW-103 → FW-104 → FW-105
       ↘ (Optional: Food Safety Certification)
```

### 4.2 Farm Supervisors

**Target Audience**: 25 supervisors
**Delivery**: Desktop + Tablet
**Total Duration**: 12 hours

| Code | Module Title | Duration | Prerequisites |
|------|--------------|----------|---------------|
| **FS-201** | Supervisor Dashboard Overview | 45 min | FW-101 equivalent |
| **FS-202** | Team Management & Assignments | 60 min | FS-201 |
| **FS-203** | Quality Control Procedures | 60 min | FS-201 |
| **FS-204** | Report Generation and Analysis | 75 min | FS-201 |
| **FS-205** | Performance Monitoring | 60 min | FS-202 |
| **FS-206** | Advanced Troubleshooting | 90 min | FS-201, FW-104 |

**Learning Path**:
```
FW-101 (Review) → FS-201 → FS-202 → FS-203 → FS-204 → FS-205 → FS-206
                                           ↓
                                    [Supervisor Certification]
```

### 4.3 Sales Team

**Target Audience**: 30 sales representatives and managers
**Delivery**: Desktop/Laptop focused
**Total Duration**: 10 hours

| Code | Module Title | Duration | Focus Area |
|------|--------------|----------|------------|
| **SL-301** | CRM Fundamentals | 60 min | Customer management basics |
| **SL-302** | Lead Management & Pipeline | 90 min | Lead tracking, qualification |
| **SL-303** | Order Processing | 60 min | Order entry, modification, tracking |
| **SL-304** | Customer Analytics | 75 min | Reports, customer insights |
| **SL-305** | Sales Performance Dashboard | 45 min | KPIs, goal tracking |

**Learning Path**:
```
System Basics → SL-301 → SL-302 → SL-303 → SL-304 → SL-305
                                        ↓
                              [Sales Certification]
```

### 4.4 Warehouse Staff

**Target Audience**: 40 warehouse personnel
**Delivery**: Tablet + Desktop
**Total Duration**: 8 hours

| Code | Module Title | Duration | Description |
|------|--------------|----------|-------------|
| **WH-401** | Inventory Management Basics | 60 min | Stock tracking, locations |
| **WH-402** | Receiving and Put-away | 75 min | Goods receipt, storage procedures |
| **WH-403** | Picking and Dispatch | 75 min | Order fulfillment, shipping |
| **WH-404** | Cycle Counting and Audits | 60 min | Stock checks, variance handling |

**Learning Path**:
```
System Basics → WH-401 → WH-402 → WH-403 → WH-404
                                    ↓
                          [Warehouse Operations Certification]
```

### 4.5 Accounting

**Target Audience**: 15 accounting and finance staff
**Delivery**: Desktop focused
**Total Duration**: 14 hours

| Code | Module Title | Duration | Complexity |
|------|--------------|----------|------------|
| **AC-501** | Financial Module Overview | 60 min | Intermediate |
| **AC-502** | Accounts Payable | 90 min | Intermediate |
| **AC-503** | Accounts Receivable | 90 min | Intermediate |
| **AC-504** | General Ledger Operations | 90 min | Advanced |
| **AC-505** | Financial Reporting | 90 min | Advanced |
| **AC-506** | Month-end & Year-end Procedures | 90 min | Advanced |

**Learning Path**:
```
System Basics → AC-501 → AC-502 → AC-503 → AC-504 → AC-505 → AC-506
                                                    ↓
                                        [Finance System Certification]
```

### 4.6 System Administrators

**Target Audience**: 8 IT administrators
**Delivery**: Desktop + Lab environment
**Total Duration**: 24 hours

| Code | Module Title | Duration | Type |
|------|--------------|----------|------|
| **AD-601** | System Architecture & Infrastructure | 120 min | Theory |
| **AD-602** | User Management & Security | 120 min | Hands-on |
| **AD-603** | Backup and Recovery | 120 min | Hands-on |
| **AD-604** | Integration Management | 150 min | Hands-on |
| **AD-605** | Performance Tuning | 120 min | Hands-on |
| **AD-606** | Troubleshooting Advanced Issues | 150 min | Case-based |
| **AD-607** | Database Administration | 180 min | Hands-on |
| **AD-608** | Security Hardening & Compliance | 120 min | Theory + Practice |

**Learning Path**:
```
AD-601 → AD-602 → AD-603 → AD-604 → AD-605 → AD-606 → AD-607 → AD-608
                                                        ↓
                                        [System Administrator Certification]
```

---

## 5. Content Development

### 5.1 Script Writing Guidelines

#### Script Structure

```
SCENE: [Scene Number]
TITLE: [Scene Title]
DURATION: [MM:SS]
VISUAL: [Description of what appears on screen]
AUDIO: [Voiceover text]
ON-SCREEN TEXT: [Text overlays]
INTERACTION: [Interactive element description]
NOTES: [Developer notes]
```

#### Writing Style Guide

| Element | Guideline | Example |
|---------|-----------|---------|
| **Tone** | Conversational, professional | "Let's explore the dashboard together" |
| **Sentence Length** | Max 20 words | "Click the Save button to store your changes" |
| **Vocabulary** | Simple, jargon-free | Use "start" not "initiate" |
| **Active Voice** | Always | "The system saves the data" not "Data is saved" |
| **Pronouns** | Second person (you/your) | "You will see the confirmation message" |
| **Instructions** | Imperative, step-by-step | "Click Settings. Then select Preferences" |

#### Bengali Localization Guidelines

| Aspect | Approach |
|--------|----------|
| **Translation** | Professional translators + domain experts |
| **Transliteration** | English terms for technical concepts: "ড্যাশবোর্ড" (Dashboard) |
| **Cultural Adaptation** | Local examples, familiar contexts |
| **Voice Talent** | Native Bengali speakers, clear pronunciation |
| **Text Expansion** | Allow 20-30% more space for Bengali text |
| **RTL/LTR** | Maintain LTR for mixed content |

### 5.2 Storyboard Template

```
┌─────────────────────────────────────────────────────────────────┐
│  MODULE STORYBOARD - [Module ID]: [Title]                       │
├─────────────────────────────────────────────────────────────────┤
│  Slide [X] of [Y]        │  Duration: [MM:SS]                   │
├──────────────────────────┼──────────────────────────────────────┤
│                          │                                      │
│   VISUAL LAYOUT          │  AUDIO SCRIPT                        │
│   ┌────────────────┐     │                                      │
│   │                │     │  [Voiceover text here]               │
│   │   [Sketch or   │     │                                      │
│   │   description] │     │                                      │
│   │                │     │  ON-SCREEN TEXT:                     │
│   └────────────────┘     │  - Bullet 1                          │
│                          │  - Bullet 2                          │
│   Navigation:            │                                      │
│   [ ] Next  [ ] Back     │  NOTES:                              │
│   [ ] Menu  [ ] Exit     │  [Developer/Designer notes]          │
│                          │                                      │
├──────────────────────────┴──────────────────────────────────────┤
│  INTERACTION: [Description of interactive elements]             │
│  ANIMATION: [Animation notes]                                   │
│  ASSETS NEEDED: [Images, videos, audio files]                   │
└─────────────────────────────────────────────────────────────────┘
```

### 5.3 Assessment Design

#### Question Types by Bloom's Taxonomy

| Level | Question Type | Example |
|-------|---------------|---------|
| **Remember** | Multiple Choice, Matching | "Which menu contains the Reports option?" |
| **Understand** | Multiple Choice, True/False | "What does this error message indicate?" |
| **Apply** | Scenario-based MC, Simulation | "How would you handle this customer request?" |
| **Analyze** | Case study, Drag-drop sequence | "What is wrong with this process flow?" |
| **Evaluate** | Scenario judgment, Ranking | "Which solution is most appropriate?" |
| **Create** | Open response, Project | "Design a report for this requirement" |

#### Question Writing Best Practices

1. **Stem Clarity**: Write clear, unambiguous question stems
2. **Distractor Quality**: Wrong answers should be plausible
3. **Avoid Negatives**: Minimize "not" and "except" questions
4. **One Correct Answer**: Avoid "all of the above" options
5. **Consistent Length**: Correct answers shouldn't be consistently longer
6. **Visual Aids**: Use screenshots for system-related questions

#### Example Question Bank Entry

```yaml
question_id: "FW-101-Q05"
module: "FW-101"
difficulty: "Easy"
blooms_level: "Apply"
type: "Multiple Choice"
question_en: "A farmer reports that yesterday's milk collection is not showing in the system. What should you do first?"
question_bn: "একজন কৃষক রিপোর্ট করেছেন যে গতকালের দুগ্ধ সংগ্রহ সিস্টেমে দেখাচ্ছে না। আপনি প্রথমে কী করবেন?"
options_en:
  - A: Check the internet connection
  - B: Verify the date filter is set correctly
  - C: Call the supervisor immediately
  - D: Re-enter the data from yesterday
correct_answer: "B"
explanation_en: "The most common reason for missing data is an incorrect date filter. Always verify filters before taking other actions."
explanation_bn: "তথ্য অনুপস্থিতির সবচেয়ে সাধারণ কারণ ভুল তারিখ ফিল্টার। অন্য কিছু করার আগে সর্বদা ফিল্টার যাচাই করুন।"
tags: ["troubleshooting", "data-filtering"]
```

### 5.4 Gamification Elements

#### Gamification Framework

| Element | Implementation | Purpose |
|---------|----------------|---------|
| **Points** | 10-100 points per activity | Progress tracking |
| **Badges** | Visual achievements | Milestone recognition |
| **Levels** | Bronze → Silver → Gold → Platinum | Progression system |
| **Leaderboards** | Team and individual rankings | Healthy competition |
| **Streaks** | Consecutive learning days | Habit formation |
| **Certificates** | Digital credentials | Formal recognition |

#### Badge System

| Badge | Criteria | Icon Description |
|-------|----------|------------------|
| 🌟 First Steps | Complete first module | Footprint |
| 📚 Fast Learner | Complete 5 modules in a week | Lightning bolt |
| 🎯 Perfect Score | 100% on any assessment | Bullseye |
| 🔥 Streak Master | 7 consecutive learning days | Flame |
| 🏆 Role Certified | Complete role-based certification | Trophy |
| 🌐 Polyglot | Complete modules in 2+ languages | Globe |
| 💡 Troubleshooter | Complete all troubleshooting modules | Wrench |
| 📊 Data Expert | Complete all reporting modules | Chart |

#### Points System

| Activity | Points | Frequency |
|----------|--------|-----------|
| Module Completion | 50 | Per module |
| Assessment Pass (80%+) | 25 | Per assessment |
| Perfect Score (100%) | 50 | Per assessment |
| Daily Login | 5 | Once per day |
| Forum Contribution | 10 | Per post |
| Help Peer | 20 | Per verified help |
| Complete Learning Path | 200 | Per path |

---

## 6. Module Examples

### 6.1 Module 1: Introduction to Smart Dairy System

#### Module Overview

| Attribute | Value |
|-----------|-------|
| **Module ID** | FW-101 / GEN-101 |
| **Title** | Introduction to Smart Dairy System |
| **Duration** | 45 minutes |
| **Languages** | English, Bengali (বাংলা) |
| **Prerequisites** | None |
| **Certificate** | Yes |

#### Learning Objectives

Upon completion, learners will be able to:
1. Explain the purpose and benefits of the Smart Dairy System
2. Navigate to the login page and access the system
3. Identify the main dashboard components
4. Access the user profile and settings
5. Locate help resources and support contacts

#### Module Outline

| Section | Duration | Content Type |
|---------|----------|--------------|
| Welcome and Overview | 3 min | Video |
| System Benefits | 5 min | Interactive presentation |
| Logging In | 8 min | Simulation + Practice |
| Dashboard Tour | 10 min | Interactive hotspots |
| User Profile & Settings | 7 min | Demonstration |
| Getting Help | 5 min | Information + Quiz |
| Module Summary | 2 min | Recap |
| Assessment | 5 min | 10 questions |

#### Sample Content - Login Simulation

```
SCENE 3: LOGGING INTO THE SYSTEM
DURATION: 08:00

VISUAL:
[Screen recording of login process with callouts]

AUDIO (English):
"Welcome back. In this section, we'll walk through the login process 
step by step. First, open your web browser and navigate to 
portal.smartdairy.com.bd. You'll see the login screen."

AUDIO (Bengali):
"স্বাগতম। এই অংশে, আমরা ধাপে ধাপে লগইন প্রক্রিয়াটি দেখব। প্রথমে, 
আপনার ওয়েব ব্রাউজার খুলুন এবং portal.smartdairy.com.bd এ যান। 
আপনি লগইন স্ক্রিনটি দেখতে পাবেন।"

INTERACTION:
[Hotspot activity: Learner clicks the correct URL field]
[Input field: Learner enters username]
[Input field: Learner enters password]
[Button click: Login button]

FEEDBACK:
Correct: "Excellent! You've successfully logged in."
Incorrect: "That's not quite right. Remember to use the URL shown 
on screen. Try again."
```

### 6.2 Module 2: Daily Operations

#### Module Overview

| Attribute | Value |
|-----------|-------|
| **Module ID** | FW-102 |
| **Title** | Daily Data Entry Operations |
| **Duration** | 60 minutes |
| **Languages** | English, Bengali |
| **Prerequisites** | FW-101 |
| **Certificate** | Yes |

#### Learning Objectives

1. Record daily milk collection data accurately
2. Enter quality test results into the system
3. Update farmer visit logs
4. Generate daily summary reports
5. Identify and correct data entry errors

#### Module Outline

| Section | Duration | Content Type |
|---------|----------|--------------|
| Daily Workflow Overview | 5 min | Video |
| Milk Collection Entry | 15 min | Simulation + Practice |
| Quality Testing Input | 15 min | Demonstration + Exercise |
| Farmer Visit Logging | 10 min | Interactive scenario |
| Daily Report Generation | 8 min | Walkthrough |
| Error Correction | 5 min | Troubleshooting guide |
| Summary | 2 min | Key points recap |

#### Sample Scenario - Quality Testing

```
SCENARIO: Quality Test Entry

SITUATION:
You have just completed quality testing for a batch of milk from 
Farmer Abdul Karim. The results are:
- Fat Content: 4.2%
- SNF: 8.5%
- Temperature: 4°C
- pH: 6.6

TASK:
Enter these results into the system and determine if the milk 
meets quality standards.

INTERACTION:
[Form fields for each measurement]
[Submit button]
[Automated quality check feedback]

FEEDBACK:
If correct: "Perfect! The milk meets all quality standards. 
The system has recorded the data and calculated the price."

If incorrect: "Check your entries. Remember:
- Fat should be between 3.5% and 5.0%
- SNF should be above 8.0%
- Temperature should be below 8°C"
```

### 6.3 Module 3: Troubleshooting Basics

#### Module Overview

| Attribute | Value |
|-----------|-------|
| **Module ID** | FW-104 / FS-206 |
| **Title** | Troubleshooting Common Issues |
| **Duration** | 30 min (FW) / 90 min (FS) |
| **Languages** | English, Bengali |
| **Prerequisites** | FW-101, FW-102 |
| **Certificate** | Yes |

#### Learning Objectives

1. Identify common system errors and their meanings
2. Apply basic troubleshooting steps
3. Know when to escalate issues
4. Access and use the knowledge base
5. Document issues for support team

#### Module Outline

| Section | Duration | Content Type |
|---------|----------|--------------|
| Common Issues Overview | 5 min | Interactive list |
| Connectivity Problems | 10 min | Decision tree |
| Data Entry Errors | 8 min | Scenario-based practice |
| Report Discrepancies | 8 min | Case study |
| Escalation Procedures | 5 min | Process flow |
| Knowledge Base Usage | 4 min | Demonstration |
| Summary & Assessment | 5 min | Quiz |

#### Troubleshooting Decision Tree

```
START: Issue Reported
    ↓
Can you access the login page?
    ↓ YES                      ↓ NO
Can you log in?           Check internet connection
    ↓ YES                      ↓
Is the specific           Contact supervisor/
feature working?          IT support
    ↓ NO
Check error message
    ↓
Is error in knowledge base?
    ↓ YES                      ↓ NO
Follow documented       Document issue and
solution                escalate to support
    ↓                           ↓
RESOLVED                SUPPORT TICKET
```

### 6.4 Module 4: Reports and Analytics

#### Module Overview

| Attribute | Value |
|-----------|-------|
| **Module ID** | FS-204 / SL-304 / WH-404 |
| **Title** | Reports and Analytics |
| **Duration** | 75 minutes |
| **Languages** | English |
| **Prerequisites** | Role-specific basics |
| **Certificate** | Yes |

#### Learning Objectives

1. Navigate to the reports section
2. Generate standard reports
3. Apply filters and date ranges
4. Export reports in multiple formats
5. Interpret key metrics and KPIs
6. Schedule automated reports

#### Report Types by Role

| Role | Standard Reports | Custom Reports |
|------|------------------|----------------|
| **Farm Supervisor** | Daily collection, Quality summary, Farmer performance | Custom date ranges, Comparison reports |
| **Sales** | Sales pipeline, Conversion rates, Customer analysis | Territory reports, Product performance |
| **Warehouse** | Inventory levels, Stock movements, Audit reports | Location-based, Product category |
| **Accounting** | Financial statements, Transaction logs, Reconciliation | Custom GL reports, Tax reports |

#### Sample Report Generation Walkthrough

```
ACTIVITY: Generate Weekly Milk Collection Report

STEPS:
1. Navigate to Reports → Farm Operations → Milk Collection
2. Select "Weekly Summary" template
3. Set Date Range: Last 7 days
4. Select Center(s): Your assigned centers
5. Choose Format: PDF + Excel
6. Click "Generate Report"
7. Review on-screen preview
8. Download or email report

ASSESSMENT QUESTION:
"You need to compare this month's milk collection with last month. 
Which steps would you take?"

OPTIONS:
A. Generate two separate weekly reports
B. Use the "Comparison Report" template with custom date range
C. Export daily data and calculate in Excel
D. Request from supervisor

CORRECT ANSWER: B
```

---

## 7. Assessments

### 7.1 Quiz Formats

#### Format Specifications

| Format | Use Case | Max Questions | Time Limit |
|--------|----------|---------------|------------|
| **Quick Check** | End-of-module verification | 5-10 | 10 min |
| **Knowledge Test** | Role module completion | 15-20 | 30 min |
| **Certification Exam** | Full role certification | 40-60 | 90 min |
| **Recertification** | Annual renewal | 20-30 | 45 min |

#### Question Type Distribution

| Type | Quick Check | Knowledge Test | Certification |
|------|-------------|----------------|---------------|
| Multiple Choice (Single) | 60% | 50% | 40% |
| Multiple Choice (Multi) | 20% | 20% | 20% |
| True/False | 10% | 10% | 5% |
| Matching | 5% | 10% | 10% |
| Scenario-Based | 5% | 10% | 25% |

### 7.2 Passing Criteria

#### Standard Grading

| Assessment Type | Passing Score | Attempts Allowed | Retry Period |
|-----------------|---------------|------------------|--------------|
| Module Quizzes | 80% | Unlimited | Immediate |
| Knowledge Tests | 80% | 3 | 24 hours |
| Certification Exams | 80% | 3 | 7 days |
| Recertification | 80% | Unlimited | Immediate |

#### Feedback Levels

| Score Range | Feedback | Action Required |
|-------------|----------|-----------------|
| 100% | "Excellent! Perfect score!" | None |
| 90-99% | "Great job! You have strong understanding." | None |
| 80-89% | "Good work. You passed. Review missed topics." | Review recommended |
| 70-79% | "You need more practice. Please review the module." | Re-study required |
| Below 70% | "Let's try again. Review the material first." | Module restart |

### 7.3 Certification Program

#### Certification Levels

| Level | Requirements | Validity | Badge |
|-------|--------------|----------|-------|
| **Bronze** | Complete 50% of role modules | N/A | 🥉 |
| **Silver** | Complete all role modules, 80% avg | 1 year | 🥈 |
| **Gold** | Complete all modules, 90% avg, +1 elective | 1 year | 🥇 |
| **Platinum** | Gold + mentor another employee | 2 years | 💎 |

#### Certificate Template

```
┌────────────────────────────────────────────────────────────┐
│                    SMART DAIRY LTD.                        │
│                                                            │
│                  ★ CERTIFICATE OF COMPLETION ★            │
│                                                            │
│                    [Smart Dairy Logo]                      │
│                                                            │
│  This is to certify that                                   │
│                                                            │
│              [EMPLOYEE FULL NAME]                          │
│                                                            │
│  has successfully completed the                            │
│                                                            │
│        [ROLE] Certification Program                        │
│                                                            │
│  including all required training modules and assessments   │
│  with a final score of [PERCENTAGE]%                       │
│                                                            │
│  Certification Level: [LEVEL]                              │
│  Issue Date: [DATE]                                        │
│  Valid Until: [DATE]                                       │
│  Certificate ID: [CERT-XXXX-XXXX]                          │
│                                                            │
│  ─────────────────────────    ─────────────────────────    │
│    Training Lead                 HR Manager                │
│                                                            │
│  Verify: smartdairy.com.bd/verify/[CERT-ID]                │
└────────────────────────────────────────────────────────────┘
```

#### Digital Credentialing

- **Platform**: Credly or similar badge platform
- **Verification**: QR code + unique certificate ID
- **Sharing**: LinkedIn, email signature, internal profile
- **Blockchain**: Optional blockchain verification for external credibility

---

## 8. Tracking & Reporting

### 8.1 Progress Tracking

#### Individual Learner Dashboard

| Metric | Display | Update Frequency |
|--------|---------|------------------|
| Overall Progress | Percentage + Progress bar | Real-time |
| Modules Completed | Count / Total | Real-time |
| Time Spent | Hours:Minutes | Real-time |
| Average Score | Percentage | Per assessment |
| Current Streak | Days | Daily |
| Next Due Date | Date countdown | Daily |
| Certificates Earned | Badge display | On completion |

#### Learning Path Visualization

```
Your Learning Journey: Farm Worker

[✓] FW-101 ──► [✓] FW-102 ──► [🔄] FW-103 ──► [○] FW-104 ──► [○] FW-105
 Completed      Completed       In Progress      Locked        Locked
   95%            88%            45%

Overall: 56% Complete | Time: 2h 15m | Score Avg: 91%

Next Actions:
► Continue FW-103 (15 min remaining)
► Review FW-102 assessment feedback
```

### 8.2 Completion Certificates

#### Certificate Management

| Feature | Description |
|---------|-------------|
| **Auto-Generation** | Certificates generated upon passing |
| **PDF Download** | High-resolution PDF for printing |
| **Digital Badge** | Shareable digital credential |
| **LinkedIn Integration** | One-click add to profile |
| **Email Delivery** | Automatic email with certificate |
| **Verification** | Online verification portal |

#### Certificate Repository

Employees can access:
- All earned certificates
- Download history
- Expiration dates
- Renewal reminders
- Transcript of all learning activities

### 8.3 Manager Dashboards

#### Team Overview Dashboard

| Widget | Data Shown |
|--------|------------|
| **Team Completion Rate** | % of team who completed assigned training |
| **Overdue Alerts** | List of employees past due date |
| **Performance Summary** | Average scores by module/team |
| **Time Investment** | Total learning hours this month |
| **Certification Status** | Who holds current certifications |
| **Engagement Metrics** | Login frequency, streaks, participation |

#### Sample Manager Report

```
FARM OPERATIONS - TRAINING DASHBOARD
Report Period: January 1-31, 2026

Team: Farm Workers (Center A)
Manager: Supervisor Khan

COMPLETION SUMMARY
─────────────────────────────────────────
Total Assigned:     25 employees
Completed:          22 (88%)
In Progress:        2 (8%)
Not Started:        1 (4%)

OVERDUE ALERTS
─────────────────────────────────────────
⚠️  Rahim, Ahmed - FW-102 (Due: Jan 25)

PERFORMANCE BY MODULE
─────────────────────────────────────────
FW-101 (Introduction):    92% avg
FW-102 (Data Entry):      87% avg
FW-103 (Cattle Mgmt):     85% avg

TOP PERFORMERS
─────────────────────────────────────────
1. Karim, Hassan - 98% avg, Gold cert
2. Islam, Faruk - 96% avg, Gold cert
3. Ahmed, Jamal - 94% avg, Silver cert

NEEDS ATTENTION
─────────────────────────────────────────
• Rahim, Ahmed - 2 overdue modules
• Hossain, Ali - 68% avg score
```

---

## 9. Content Maintenance

### 9.1 Update Procedures

#### Update Triggers

| Trigger | Action Required | Timeline |
|---------|-----------------|----------|
| System Feature Change | Update affected modules | Within 1 week |
| Process Change | Update procedure content | Within 2 weeks |
| New Regulation | Add compliance content | Within 1 week |
| Bug Fix | Update troubleshooting | Within 3 days |
| User Feedback | Review and revise | Monthly review |
| Annual Review | Full content audit | Annually |

#### Update Workflow

```
1. IDENTIFY
   ↓ Stakeholder identifies need
2. LOG
   ↓ Create content update ticket
3. ASSESS
   ↓ Instructional Designer evaluates impact
4. REVISE
   ↓ Update content, scripts, media
5. REVIEW
   ↓ Subject Matter Expert approval
6. TEST
   ↓ QA testing in staging environment
7. DEPLOY
   ↓ Publish to production
8. COMMUNICATE
   ↓ Notify affected learners
9. TRACK
   ↓ Monitor for issues
```

### 9.2 Version Control

#### Version Numbering

Format: `Major.Minor.Patch`

| Version Type | Change | Example |
|--------------|--------|---------|
| **Major (X.0.0)** | Complete redesign, new structure | 2.0.0 |
| **Minor (x.X.0)** | Content updates, new sections | 1.2.0 |
| **Patch (x.x.X)** | Bug fixes, typo corrections | 1.2.1 |

#### Version History Documentation

Each module maintains:
- Version changelog
- Update date
- Author of changes
- Reason for update
- Impact assessment
- Rollback procedure

#### Archive Policy

| Version Age | Action |
|-------------|--------|
| Current + 1 version | Available for reference |
| Current + 2 versions | Archived (admin access only) |
| Older versions | Removed after 2 years |

### 9.3 Feedback Integration

#### Feedback Collection Methods

| Method | Timing | Data Collected |
|--------|--------|----------------|
| End-of-Module Survey | Post-completion | Rating, comments, time feedback |
| In-Module Feedback | Anytime | Specific slide/section issues |
| Assessment Comments | Post-fail | What was confusing |
| Manager Input | Monthly | Team learning gaps |
| Support Tickets | Ongoing | Technical/content issues |
| Analytics Review | Weekly | Drop-off points, time spent |

#### Feedback Analysis Framework

| Metric | Threshold | Action |
|--------|-----------|--------|
| Rating < 3.5/5 | Any module | Immediate review |
| Completion Rate < 80% | Any module | Content review |
| Assessment Pass < 75% | Any module | Difficulty review |
| Time Spent > 150% expected | Any module | Content length review |
| Help requests > 5 on topic | Any topic | Add clarification |

#### Continuous Improvement Cycle

```
    ┌─────────────┐
    │   DEPLOY    │
    └──────┬──────┘
           ↓
    ┌─────────────┐
    │   GATHER    │
    │  FEEDBACK   │
    └──────┬──────┘
           ↓
    ┌─────────────┐
    │   ANALYZE   │
    │    DATA     │
    └──────┬──────┘
           ↓
    ┌─────────────┐
    │   REVISE    │
    │  CONTENT    │
    └──────┬──────┘
           ↓
    ┌─────────────┐
    │    TEST     │
    │   CHANGES   │
    └─────────────┘
```

---

## 10. Appendices

### Appendix A: Storyboard Template

```
================================================================================
                     SMART DAIRY ELEARNING - STORYBOARD
================================================================================

PROJECT INFORMATION
─────────────────────────────────────────────────────────────────────────────────
Module ID:        [XX-###]
Module Title:     [Title]
Slide Number:     [X] of [Y]
Version:          [X.Y.Z]
Date Created:     [YYYY-MM-DD]
Last Modified:    [YYYY-MM-DD]
Author:           [Name]
Reviewer:         [Name]

SLIDE SPECIFICATIONS
─────────────────────────────────────────────────────────────────────────────────
Duration:         [MM:SS]
Transition:       [Fade/Slide/None]
Background:       [Description/Color]
Template:         [Standard/Video/Quiz/Interactive]

VISUAL LAYOUT
─────────────────────────────────────────────────────────────────────────────────
┌─────────────────────────────────────────────────────────────────────────────┐
│                                                                             │
│   [Detailed description or sketch of screen layout]                         │
│                                                                             │
│   • Element positions (header, content, navigation)                         │
│   • Image/video placement                                                   │
│   • Text locations                                                          │
│   • Interactive element positions                                           │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘

AUDIO SCRIPT
─────────────────────────────────────────────────────────────────────────────────
Voiceover:        [Script text - exactly as to be recorded]
                  
Pronunciation     [Special pronunciation notes]
Notes:            

Sound Effects:    [SFX description and timing]

Music:            [Background music - track name and volume level]

ON-SCREEN TEXT
─────────────────────────────────────────────────────────────────────────────────
Title:            [Slide title text]

Body Text:        [Main content text]
                  
Bullet Points:    • [Point 1]
                  • [Point 2]
                  • [Point 3]

Callouts:         [Text overlays on visuals]

TRANSLATION (Bengali)
─────────────────────────────────────────────────────────────────────────────────
Title:            [Bengali title]

Body Text:        [Bengali body text]

INTERACTION DETAILS
─────────────────────────────────────────────────────────────────────────────────
Type:             [Click/Hover/Drag/Drop/Text Input/Quiz]

Trigger:          [What activates the interaction]

Behavior:         [What happens when triggered]

Feedback:         • Correct: [Feedback text]
                  • Incorrect: [Feedback text]
                  • Attempt 2: [Alternative feedback]

Branching:        [If applicable - where does each option lead]

ASSETS REQUIRED
─────────────────────────────────────────────────────────────────────────────────
Images:           • [Filename] - [Description]
                  • [Filename] - [Description]

Videos:           • [Filename] - [Description]

Audio:            • [Filename] - [Description]

Graphics:         • [Description of custom graphics needed]

Navigation:       ☐ Previous  ☐ Next  ☐ Menu  ☐ Exit  ☐ Replay
                  ☐ Progress Bar  ☐ Page Counter

PROGRAMMING NOTES
─────────────────────────────────────────────────────────────────────────────────
[Technical notes for developers]

REVIEW COMMENTS
─────────────────────────────────────────────────────────────────────────────────
Date:             [Review date]
Reviewer:         [Name]
Comments:         [Feedback]
Status:           ☐ Approved  ☐ Revise  ☐ Rejected

================================================================================
```

### Appendix B: Script Template

```
================================================================================
                     SMART DAIRY ELEARNING - VIDEO SCRIPT
================================================================================

PRODUCTION INFORMATION
─────────────────────────────────────────────────────────────────────────────────
Project:          [Module ID] - [Module Title]
Scene:            [Scene Number]
Title:            [Scene Title]
Duration:         [MM:SS]
Talent:           [Voice actor name]
Language:         ☐ English  ☐ Bengali  ☐ Both
Recording Date:   [Scheduled date]
Location:         [Studio/Remote]

APPROVALS
─────────────────────────────────────────────────────────────────────────────────
Writer:           _________________  Date: _______
SME Review:       _________________  Date: _______
Instructional:    _________________  Date: _______
Final Approval:   _________________  Date: _______

SCRIPT
─────────────────────────────────────────────────────────────────────────────────

SCENE [X]: [SCENE TITLE]
ESTIMATED DURATION: [MM:SS]

─────────────────────────────────────────────────────────────────────────────────
SLATE: [Take number, date]

VIDEO:
[Detailed description of what appears on screen. Include camera movements,
zooms, transitions, and visual elements. Be specific about colors, positions,
and timing.]

AUDIO:
[Exact voiceover script. Read aloud to verify timing matches estimate.
Include pronunciation guides for technical terms.]

PRONUNCIATION GUIDE:
• [Term]: [Phonetic spelling]
• [Term]: [Phonetic spelling]

ON-SCREEN TEXT:
[Text overlays, lower thirds, captions. Include timing for each element.]

[00:00-00:05] Title: [Text]
[00:05-00:15] Bullet 1: [Text]
[00:15-00:20] Bullet 2: [Text]

GRAPHICS/ANIMATION:
[Description of motion graphics, animations, or special effects.]

SOUND EFFECTS:
[00:08] [SFX description - e.g., "Button click sound"]
[00:15] [SFX description]

MUSIC:
[Description of background music track, volume level, fade in/out timing.]

NOTES FOR EDITOR:
[Special instructions for post-production.]

─────────────────────────────────────────────────────────────────────────────────

BENGALI VERSION (if applicable)
─────────────────────────────────────────────────────────────────────────────────

AUDIO (Bengali):
[Bengali voiceover script]

TRANSLITERATION NOTES:
[Guidance for voice actor on specific terms.]

TEXT EXPANSION NOTES:
[Bengali text may be 20-30% longer. Layout adjustments needed.]

─────────────────────────────────────────────────────────────────────────────────

TIMING VERIFICATION
─────────────────────────────────────────────────────────────────────────────────
Script Word Count:      [Count]
Estimated Duration:     [MM:SS] (at 130 words/minute)
Target Duration:        [MM:SS]
Variance:               [+/- seconds]

READ-THROUGH APPROVAL:
☐ Script reads naturally within time limit
☐ Technical terms are clear and correctly pronounced
☐ Bengali translation maintains meaning and tone
☐ On-screen text timing allows for comfortable reading

================================================================================
```

### Appendix C: Assessment Question Bank

#### Question Bank Format

```yaml
# Question Bank Entry Template

metadata:
  question_id: "[CATEGORY]-[NUMBER]"
  version: "1.0"
  created_date: "2026-01-31"
  last_modified: "2026-01-31"
  author: "[Name]"
  reviewer: "[Name]"
  status: "Approved"  # Draft, Review, Approved, Archived

classification:
  module: "[Module ID]"
  role: "[Farm Worker/Supervisor/Sales/Warehouse/Accounting/Admin]"
  difficulty: "[Easy/Medium/Hard]"
  blooms_level: "[Remember/Understand/Apply/Analyze/Evaluate/Create]"
  topic: "[Specific topic area]"
  tags: ["tag1", "tag2", "tag3"]
  learning_objective: "[Reference to module objective]"

content:
  question_type: "[MC-Single/MC-Multi/True-False/Matching/Scenario]"
  
  question_text_en: |
    [English question text]
    
  question_text_bn: |
    [Bengali question text]
    
  # For Multiple Choice
  options_en:
    - A: "[Option A]"
    - B: "[Option B]"
    - C: "[Option C]"
    - D: "[Option D]"
    
  options_bn:
    - A: "[Option A Bengali]"
    - B: "[Option B Bengali]"
    - C: "[Option C Bengali]"
    - D: "[Option D Bengali]"
    
  correct_answer: "[A/B/C/D]"
  
  # For True/False
  correct_answer_tf: true  # or false
  
  # For Matching
  pairs:
    - left: "[Item 1]"
      right: "[Match 1]"
    - left: "[Item 2]"
      right: "[Match 2]"

feedback:
  correct_en: "[Correct answer feedback]"
  correct_bn: "[Correct answer feedback Bengali]"
  
  incorrect_en: "[Incorrect answer feedback]"
  incorrect_bn: "[Incorrect answer feedback Bengali]"
  
  explanation_en: |
    [Detailed explanation of the correct answer]
    
  explanation_bn: |
    [Detailed explanation in Bengali]

media:
  has_image: false
  image_path: ""
  image_alt_text: ""
  
  has_video: false
  video_path: ""
  video_start_time: 0
  video_end_time: 0

usage:
  times_used: 0
  average_score: 0
  discrimination_index: 0  # Statistical measure of question quality
  
  # Usage tracking
  assessments_used:
    - assessment_id: "[ID]"
      date_added: "[Date]"
      
notes: |
  [Any additional notes for instructors or developers]
```

#### Sample Question Entries

```yaml
# Sample 1: Farm Worker - Easy
question_id: "FW-E-001"
module: "FW-101"
difficulty: "Easy"
blooms_level: "Remember"
question_type: "MC-Single"

question_text_en: "Which button should you click to save your work in the Smart Dairy system?"
question_text_bn: "স্মার্ট ডেইরি সিস্টেমে আপনার কাজ সংরক্ষণ করতে কোন বোতামে ক্লিক করবেন?"

options_en:
  - A: "The green Save button"
  - B: "The red Delete button"
  - C: "The blue Home button"
  - D: "The yellow Refresh button"
  
options_bn:
  - A: "সবুজ সংরক্ষণ বোতাম (Save)"
  - B: "লাল মুছে ফেলা বোতাম (Delete)"
  - C: "নীল হোম বোতাম (Home)"
  - D: "হলুদ রিফ্রেশ বোতাম (Refresh)"

correct_answer: "A"

feedback_correct_en: "Correct! The green Save button stores your data."
feedback_correct_bn: "সঠিক! সবুজ সংরক্ষণ বোতাম আপনার তথ্য সংরক্ষণ করে।"

feedback_incorrect_en: "Not quite. Look for the green button labeled 'Save'."
feedback_incorrect_bn: "ঠিক নয়। 'সংরক্ষণ' লেবেলযুক্ত সবুজ বোতামটি দেখুন।"

---

# Sample 2: Farm Supervisor - Medium
question_id: "FS-M-012"
module: "FS-204"
difficulty: "Medium"
blooms_level: "Apply"
question_type: "Scenario"

scenario_en: |
  You are reviewing the weekly milk collection report. You notice that 
  Center A shows 20% less collection than the previous week, but the 
  number of farmers is the same. What is your first action?

scenario_bn: |
  আপনি সাপ্তাহিক দুগ্ধ সংগ্রহ রিপোর্ট পর্যালোচনা করছেন। আপনি লক্ষ্য করেছেন যে 
  কেন্দ্র A গত সপ্তাহের তুলনায় ২০% কম সংগ্রহ দেখাচ্ছে, কিন্তু কৃষকের সংখ্যা 
  একই। আপনার প্রথম পদক্ষেপ কী?

options_en:
  - A: "Contact the IT support team immediately"
  - B: "Check if any data entry errors occurred"
  - C: "Report to senior management"
  - D: "Wait and see if next week improves"
  
options_bn:
  - A: "অবিলম্বে আইটি সাপোর্ট দলের সাথে যোগাযোগ করুন"
  - B: "কোনো তথ্য ইনপুট ত্রুটি হয়েছে কিনা পরীক্ষা করুন"
  - C: "সিনিয়র ম্যানেজমেন্টকে রিপোর্ট করুন"
  - D: "অপেক্ষা করুন এবং দেখুন পরের সপ্তাহে উন্নতি হয় কিনা"

correct_answer: "B"

feedback_correct_en: |
  Correct! Always verify data entry first before escalating. 
  Many discrepancies are due to simple entry errors.
  
feedback_correct_bn: |
  সঠিক! এসকেলেট করার আগে সর্বদা তথ্য ইনপুট যাচাই করুন। 
  অনেক অমিল সাধারণ ইনপুট ত্রুটির কারণে হয়।

explanation_en: |
  The best practice is to investigate simple causes first. Check:
  1. Date range filters
  2. Missing entries
  3. Data entry errors
  4. System synchronization issues
  Only escalate to IT if no local cause is found.
```

### Appendix D: Certificate Template

#### Digital Certificate Specifications

```
================================================================================
                     SMART DAIRY CERTIFICATE SPECIFICATION
================================================================================

PHYSICAL SPECIFICATIONS
─────────────────────────────────────────────────────────────────────────────────
Orientation:      Landscape
Dimensions:       297mm x 210mm (A4 Landscape)
Resolution:       300 DPI for print, 150 DPI for web
Color Mode:       CMYK for print, RGB for web
File Formats:     PDF (primary), PNG (preview), SVG (vector elements)

COLOR PALETTE
─────────────────────────────────────────────────────────────────────────────────
Primary:          #1E5631 (Smart Dairy Green)
Secondary:        #2E7D32 (Accent Green)
Gold:             #C9A227 (Certification Level)
Silver:           #A8A9AD (Certification Level)
Bronze:           #CD7F32 (Certification Level)
Text Primary:     #1A1A1A (Almost Black)
Text Secondary:   #4A4A4A (Dark Gray)
Background:       #FAFAFA (Off White)
Border:           #1E5631 (Primary Green)

TYPOGRAPHY
─────────────────────────────────────────────────────────────────────────────────
Header Font:      Montserrat Bold / Noto Sans Bengali Bold
Title Font:       Playfair Display / Noto Serif Bengali
Body Font:        Open Sans Regular / Noto Sans Bengali Regular
Certificate ID:   Courier New (monospace)

LAYOUT STRUCTURE
─────────────────────────────────────────────────────────────────────────────────
┌─────────────────────────────────────────────────────────────────────────────┐
│ ┌─────────────────────────────────────────────────────────────────────────┐ │
│ │ BORDER: 5mm double line (outer: 2pt, inner: 1pt, spacing: 3mm)          │ │
│ │ Color: Primary Green (#1E5631)                                          │ │
│ └─────────────────────────────────────────────────────────────────────────┘ │
│                                                                             │
│   [Logo: Smart Dairy - Top Center - 40mm width]                           │
│                                                                             │
│   ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                                             │
│                    CERTIFICATE OF COMPLETION                              │
│                    ─────────────────────────                              │
│                                                                             │
│   This is to certify that                                                 │
│                                                                             │
│                    [EMPLOYEE NAME]                                        │
│                    ─────────────────                                      │
│                                                                             │
│   has successfully completed training and assessment for:                 │
│                                                                             │
│                    [CERTIFICATION TITLE]                                  │
│                    ─────────────────────                                  │
│                                                                             │
│   ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                                             │
│   Certification Level: [LEVEL BADGE]    Date: [DATE]                      │
│                                                                             │
│   Certificate ID: CERT-[ROLE]-[YYYY]-[XXXX]                               │
│   Valid Through: [EXPIRY DATE]                                            │
│                                                                             │
│   ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                                             │
│   ┌─────────────────────┐              ┌─────────────────────┐            │
│   │                     │              │                     │            │
│   │    [Signature]      │              │    [Signature]      │            │
│   │                     │              │                     │            │
│   ├─────────────────────┤              ├─────────────────────┤            │
│   │   Training Lead     │              │     HR Manager      │            │
│   └─────────────────────┘              └─────────────────────┘            │
│                                                                             │
│   ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                                             │
│   Verify: https://smartdairy.com.bd/verify/[CERT-ID]                      │
│   [QR Code - 20mm x 20mm]                                                 │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘

CERTIFICATION LEVEL BADGES
─────────────────────────────────────────────────────────────────────────────────
Bronze:   🥉 Bronze Star badge, Bronze accent color (#CD7F32)
Silver:   🥈 Silver Star badge, Silver accent color (#A8A9AD)
Gold:     🥇 Gold Star badge, Gold accent color (#C9A227)
Platinum: 💎 Diamond badge, Gradient silver/platinum

VARIABLE FIELDS
─────────────────────────────────────────────────────────────────────────────────
[EMPLOYEE NAME]     - Full legal name of employee
[CERTIFICATION]     - Specific certification name
[LEVEL]             - Badge icon + text
[DATE]              - Issue date (DD Month YYYY)
[CERT-ID]           - Unique certificate identifier
[EXPIRY DATE]       - Valid until date (DD Month YYYY)
[ROLE CODE]         - Two-letter role code (FW, FS, SL, WH, AC, AD)

SECURITY FEATURES
─────────────────────────────────────────────────────────────────────────────────
• Watermark: Subtle Smart Dairy logo pattern (background)
• Security Border: Micro-text border pattern
• QR Code: Links to verification page
• Certificate ID: Unique identifier with checksum
• Digital Signature: PDF signing certificate
• Verification URL: Public verification endpoint

LOCALIZATION NOTES
─────────────────────────────────────────────────────────────────────────────────
English Version:
- All text in English
- Date format: DD Month YYYY

Bengali Version:
- Certificate text in Bengali
- Employee name in both scripts
- Date in Bengali numerals and calendar
- Right-to-left considerations for mixed content
================================================================================
```

---

## Document Control

### Approval Status

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Instructional Designer | _________________ | _______ |
| Reviewer | HR Manager | _________________ | _______ |
| Owner | Training Lead | _________________ | _______ |
| Approver | Project Manager | _________________ | _______ |

### Distribution List

| Recipient | Department | Purpose |
|-----------|------------|---------|
| Training Team | HR | Content development reference |
| IT Team | Technology | Platform configuration |
| Department Heads | Operations | Training planning |
| All Employees | All | Training resource (relevant sections) |

---

**Document End**

*Smart Dairy Ltd. - Smart Web Portal System Implementation*
*Document ID: K-011 | Version: 1.0 | Date: January 31, 2026*
