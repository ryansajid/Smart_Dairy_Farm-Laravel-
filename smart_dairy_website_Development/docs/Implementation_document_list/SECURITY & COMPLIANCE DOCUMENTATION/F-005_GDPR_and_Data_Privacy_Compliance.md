# F-005: GDPR & Data Privacy Compliance

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | F-005 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Security Lead |
| **Owner** | DPO (Data Protection Officer) |
| **Reviewer** | Legal Counsel |
| **Classification** | Internal - Confidential |
| **Review Cycle** | Annual or upon regulatory change |
| **Next Review** | January 31, 2027 |

---

## Distribution List

| Role | Name/Department | Date Distributed |
|------|-----------------|------------------|
| Data Protection Officer | DPO Office | January 31, 2026 |
| Security Lead | IT Security | January 31, 2026 |
| Legal Counsel | Legal Department | January 31, 2026 |
| CTO | Technology | January 31, 2026 |
| CMO | Marketing | January 31, 2026 |
| HR Director | Human Resources | January 31, 2026 |

---

## Document History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Security Lead | Initial release |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Lawful Basis for Processing](#2-lawful-basis-for-processing)
3. [Data Subject Rights](#3-data-subject-rights)
4. [Privacy by Design](#4-privacy-by-design)
5. [Privacy Impact Assessment](#5-privacy-impact-assessment-piadpi)
6. [Consent Management](#6-consent-management)
7. [Data Breach Notification](#7-data-breach-notification)
8. [Cross-Border Data Transfers](#8-cross-border-data-transfers)
9. [Records of Processing Activities](#9-records-of-processing-activities-ropa)
10. [DPO Requirements](#10-dpo-requirements)
11. [Data Processing Agreements](#11-data-processing-agreements-dpa)
12. [Cookie Compliance](#12-cookie-compliance)
13. [Marketing Communications](#13-marketing-communications)
14. [Third-Party Processors](#14-third-party-processors)
15. [Compliance Monitoring](#15-compliance-monitoring)
16. [Appendices](#16-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document establishes the General Data Protection Regulation (GDPR) compliance framework for Smart Dairy Ltd., ensuring the protection of personal data of individuals within the European Union (EU) and the European Economic Area (EEA).

### 1.2 Scope

| Aspect | Coverage |
|--------|----------|
| **Geographic** | All EU/EEA data subjects regardless of Smart Dairy's location |
| **Organizational** | All departments, employees, contractors, and processors |
| **Technical** | All systems, applications, databases processing EU personal data |
| **Data Types** | Customer, employee, vendor, and prospect personal data |

### 1.3 GDPR Applicability to Smart Dairy

Despite being headquartered in Bangladesh, Smart Dairy Ltd. is subject to GDPR because:

| Factor | Application |
|--------|-------------|
| **Article 3(2)(a)** | Offers goods/services to EU residents (e-commerce platform) |
| **Article 3(2)(b)** | Monitors behavior of EU residents (analytics, cookies) |
| **International Sales** | Direct shipping to EU countries enabled |
| **Marketing** | EU-targeted digital marketing campaigns |

### 1.4 Regulatory Framework

| Regulation | Jurisdiction | Key Requirements |
|------------|--------------|------------------|
| GDPR | EU/EEA | Comprehensive data protection |
| ePrivacy Directive | EU/EEA | Cookie and electronic communications |
| UK GDPR | United Kingdom | Post-Brexit data protection |
| Bangladesh DPA 2023 | Bangladesh | Local data protection compliance |

### 1.5 Definitions

| Term | Definition |
|------|------------|
| **Personal Data** | Any information relating to an identified or identifiable natural person |
| **Data Subject** | The individual whose personal data is processed |
| **Controller** | Smart Dairy Ltd. - determines purposes and means of processing |
| **Processor** | Third parties processing data on behalf of Smart Dairy |
| **DPO** | Data Protection Officer - oversight and compliance role |
| **DPIA** | Data Protection Impact Assessment - risk assessment for high-risk processing |
| **ROPA** | Record of Processing Activities - Article 30 documentation |

### 1.6 Compliance Objectives

| Objective | Target |
|-----------|--------|
| Data Subject Rights Response | Within 30 days (72 hours for urgent) |
| Breach Notification to Authorities | Within 72 hours |
| Breach Notification to Data Subjects | Without undue delay |
| Consent Records | 100% auditable and timestamped |
| DPO Availability | Business hours + emergency contact |
| Training Completion | 100% annual for relevant staff |

---

## 2. Lawful Basis for Processing

### 2.1 Overview

Smart Dairy must identify and document a lawful basis under Article 6 GDPR for all processing activities. Only one lawful basis is required, but multiple may apply.

### 2.2 Lawful Bases Matrix

| Lawful Basis | Article | When Used | Documentation Required |
|--------------|---------|-----------|----------------------|
| **Consent** | 6(1)(a) | Marketing, cookies, non-essential processing | Consent record with timestamp, scope, withdrawal method |
| **Contract** | 6(1)(b) | Order processing, account management, delivery | Contract reference, necessity assessment |
| **Legal Obligation** | 6(1)(c) | Tax records, regulatory reporting, fraud prevention | Legal reference, retention schedule |
| **Vital Interests** | 6(1)(d) | Emergency situations involving life safety | Incident record, justification |
| **Public Task** | 6(1)(e) | Not applicable to Smart Dairy | N/A |
| **Legitimate Interest** | 6(1)(f) | Analytics, fraud prevention, internal operations | Legitimate Interest Assessment (LIA) |

### 2.3 Consent (Article 6(1)(a))

#### 2.3.1 Valid Consent Requirements

| Requirement | Implementation |
|-------------|----------------|
| **Freely Given** | No pre-ticked boxes, no conditioning service on non-essential consent |
| **Specific** | Granular consent for each distinct purpose |
| **Informed** | Clear explanation of what data, for what purpose, by whom |
| **Unambiguous** | Clear affirmative action required |
| **Withdrawable** | As easy to withdraw as to give |
| **Documented** | Record of who consented, when, how, what told |

#### 2.3.2 Consent Applications

| Processing Activity | Consent Type | Mechanism |
|--------------------|--------------|-----------|
| Marketing emails | Opt-in | Checkbox on registration/checkout |
| Marketing SMS | Separate opt-in | Separate checkbox from email |
| Analytics cookies | Consent | Cookie banner with categories |
| Personalized advertising | Consent | Separate opt-in for profiling |
| Third-party sharing | Consent | Named third parties disclosed |

### 2.4 Contract (Article 6(1)(b))

#### 2.4.1 Contractual Necessity

| Processing Activity | Legal Basis | Justification |
|--------------------|-------------|---------------|
| Account creation | Contract | Required to provide user account |
| Order processing | Contract | Necessary for purchase fulfillment |
| Payment processing | Contract | Necessary to complete transaction |
| Delivery/shipping | Contract | Necessary to deliver goods |
| Customer service | Contract | Necessary for support obligations |
| Transaction communications | Contract | Order confirmations, shipping updates |

#### 2.4.2 Contract vs Consent Distinction

```
CONTRACT BASIS:
- Processing NECESSARY for the specific contract
- Cannot refuse service if data is truly necessary
- No separate consent required

CONSENT BASIS:
- Processing NOT strictly necessary for service
- Service must be available without consent
- Can be withdrawn at any time
```

### 2.5 Legal Obligation (Article 6(1)(c))

| Processing Activity | Legal Reference | Retention Period |
|--------------------|-----------------|------------------|
| Financial records | Tax laws, company law | 7 years (Bangladesh) |
| Fraud prevention | Anti-money laundering laws | 5 years post-transaction |
| Product safety recalls | Consumer protection laws | Duration of product lifecycle |
| Legal claims defense | Limitation periods | Until statute of limitations expires |

### 2.6 Legitimate Interest (Article 6(1)(f))

#### 2.6.1 Legitimate Interest Assessment (LIA) Process

```
┌─────────────────────────────────────────────────────────────┐
│              LEGITIMATE INTEREST ASSESSMENT (LIA)           │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  STEP 1: IDENTIFY LEGITIMATE INTEREST                       │
│  • What is the specific business purpose?                   │
│  • Is it a legitimate interest of Smart Dairy or third      │
│    party?                                                   │
│  • Is processing necessary to achieve that interest?        │
│                                                             │
│  STEP 2: NECESSITY AND PROPORTIONALITY                      │
│  • Is processing necessary for the purpose?                 │
│  • Is less intrusive means available?                       │
│  • Is data minimized to what's necessary?                   │
│                                                             │
│  STEP 3: BALANCING TEST                                     │
│  • What is the impact on data subjects?                     │
│  • Do individuals expect this processing?                   │
│  • Are vulnerable individuals involved?                     │
│  • Can individuals object easily?                           │
│  • Is there a compelling justification to override          │
│    individual rights?                                       │
│                                                             │
│  STEP 4: DECISION AND SAFEGUARDS                            │
│  • Approved / Rejected / Approved with conditions           │
│  • Document safeguards implemented                          │
│  • Review date established                                  │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

#### 2.6.2 Legitimate Interest Applications

| Processing Activity | Status | Safeguards |
|--------------------|--------|------------|
| Website analytics | Approved | Anonymization, retention limits, opt-out available |
| Internal reporting | Approved | Aggregated data only, access controls |
| Fraud prevention | Approved | Limited access, retention schedules, accuracy checks |
| Network security | Approved | IP logging with short retention, access logs |
| Direct marketing (existing customers) | Under Review | Soft opt-in analysis required |

#### 2.6.3 Prohibited Legitimate Interest Uses

| Processing Activity | Reason for Prohibition |
|--------------------|------------------------|
| Sensitive data processing | Requires explicit consent |
| Automated decision-making with significant effects | Requires consent or contract basis |
| Children's data (under 16) | Requires parental consent |
| Profiling for marketing without opt-out | Violates individual rights |

### 2.7 Special Category Data (Article 9)

| Category | Examples | Lawful Basis Required |
|----------|----------|----------------------|
| Health data | Allergies from orders | Explicit consent OR substantial public interest |
| Biometric data | Not currently collected | Explicit consent |
| Religious beliefs | Dietary preferences | Explicit consent |
| Racial/ethnic origin | Not collected | N/A |

**Policy**: Smart Dairy avoids collecting special category data. Where unavoidable (e.g., allergy information), explicit consent is obtained with clear opt-in.

---

## 3. Data Subject Rights

### 3.1 Rights Overview

| Right | Article | Response Time | Fee |
|-------|---------|---------------|-----|
| Right to be Informed | 13, 14 | At collection | Free |
| Right of Access | 15 | 30 days | Free (reasonable copies) |
| Right to Rectification | 16 | 30 days | Free |
| Right to Erasure | 17 | 30 days | Free |
| Right to Restrict Processing | 18 | 30 days | Free |
| Right to Data Portability | 20 | 30 days | Free |
| Right to Object | 21 | Immediate effect | Free |
| Rights re: Automated Decision-Making | 22 | 30 days | Free |

### 3.2 Right to be Informed (Articles 13-14)

#### 3.2.1 Information Requirements at Collection (Article 13)

| Information Element | Where Provided |
|--------------------|----------------|
| Controller identity and contact details | Privacy Policy, checkout page |
| DPO contact details | Privacy Policy |
| Purposes and lawful basis | Privacy Policy, consent forms |
| Legitimate interests (if applicable) | Privacy Policy |
| Recipients or categories of recipients | Privacy Policy |
| International transfers and safeguards | Privacy Policy |
| Retention periods | Privacy Policy, retention schedule |
| Data subject rights | Privacy Policy |
| Right to complain to supervisory authority | Privacy Policy |
| Whether provision is statutory/contractual | Terms of Service |
| Existence of automated decision-making | Privacy Policy |

#### 3.2.2 Information for Data Not Collected from Subject (Article 14)

When personal data is obtained from third parties, Smart Dairy provides:

| Timing | Method |
|--------|--------|
| Within 1 month | Email notification |
| At first communication | Included in message |
| Before disclosure | Prior notice |

### 3.3 Right of Access (Article 15)

#### 3.3.1 Access Request Process

```
┌────────────────────────────────────────────────────────────────┐
│                   ACCESS REQUEST WORKFLOW                       │
├────────────────────────────────────────────────────────────────┤
│                                                                 │
│  1. RECEIVE REQUEST                                            │
│     • Email: privacy@smartdairy.com                            │
│     • Portal: Account > Privacy > Download Data               │
│     • Form: Appendix D (Data Subject Request Form)            │
│     • Verbal: Logged and confirmed in writing                 │
│                                                                 │
│  2. VERIFY IDENTITY                                            │
│     • Email verification link                                  │
│     • Account login confirmation                               │
│     • Government ID (for sensitive requests)                  │
│     • Proof of address (if required)                          │
│                                                                 │
│  3. LOCATE DATA                                                │
│     • Primary database: Customer records                      │
│     • Secondary: Marketing lists, support tickets            │
│     • Tertiary: Backups, logs, processor systems              │
│                                                                 │
│  4. PREPARE RESPONSE                                           │
│     • Structured format (JSON, CSV, or PDF)                   │
│     • Plain language explanations                              │
│     • Clear organization by category                           │
│                                                                 │
│  5. DELIVER RESPONSE                                           │
│     • Secure download link (encrypted, expiry)                │
│     • Password-protected file                                  │
│     • Email notification when ready                           │
│                                                                 │
│  6. DOCUMENT & CLOSE                                           │
│     • Log in DSR tracking system                              │
│     • Record metrics (time, complexity, outcome)              │
│     • Archive for compliance audit                            │
│                                                                 │
└────────────────────────────────────────────────────────────────┘
```

#### 3.3.2 Information Provided in Access Response

```json
{
  "data_subject": {
    "request_id": "DSR-2026-001234",
    "request_date": "2026-01-15",
    "response_date": "2026-02-10",
    "verification_method": "email_confirmation"
  },
  "personal_data": {
    "account_information": {
      "registration_date": "2025-03-10",
      "last_login": "2026-01-14",
      "account_status": "active"
    },
    "contact_details": {
      "email": "customer@example.com",
      "phone": "+49-XXX-XXX-XXXX",
      "address": "[REDACTED - FULL IN RESPONSE]"
    },
    "order_history": [...],
    "marketing_preferences": {...},
    "communication_history": [...],
    "technical_data": {
      "ip_addresses": [...],
      "device_info": [...],
      "cookies": [...]
    }
  },
  "processing_information": {
    "purposes": [...],
    "recipients": [...],
    "retention_periods": [...],
    "international_transfers": [...]
  }
}
```

#### 3.3.3 Access Request Exemptions

| Ground | Application |
|--------|-------------|
| Manifestly unfounded | Repeated identical requests, requests intended to harass |
| Excessive | Disproportionate effort (fee may be charged) |
| Third party data | Information about other individuals |
| Legal privilege | Communications with legal counsel |
| Confidential references | Employment/personnel references |

### 3.4 Right to Rectification (Article 16)

| Scenario | Process |
|----------|---------|
| Customer-initiated | Self-service in account settings |
| DSR request | Verified request processed within 30 days |
| Inaccurate data | Corrected and propagated to processors |
| Incomplete data | Supplementary information added |

**Process**:
1. Receive rectification request with supporting evidence
2. Verify requestor identity
3. Assess validity of correction
4. Update records in all systems
5. Notify any recipients of the data
6. Confirm completion to data subject

### 3.5 Right to Erasure ("Right to be Forgotten") (Article 17)

#### 3.5.1 Grounds for Erasure

| Ground | Example |
|--------|---------|
| No longer necessary | Order completed and retention period expired |
| Consent withdrawn | Marketing consent withdrawn, no other basis |
| Objection upheld | Legitimate interest objection successful |
| Unlawful processing | Data collected without valid basis |
| Legal obligation | Court order for deletion |
| Children's data | Online service for child, parental consent withdrawn |

#### 3.5.2 Erasure Process

```
┌────────────────────────────────────────────────────────────────┐
│                   ERASURE REQUEST WORKFLOW                      │
├────────────────────────────────────────────────────────────────┤
│                                                                 │
│  1. RECEIVE & VERIFY                                           │
│     • Verify identity of requestor                            │
│     • Assess grounds for erasure                              │
│                                                                 │
│  2. ASSESS EXEMPTIONS                                          │
│     ├─ Legal obligation to retain? (Tax, legal claims)       │
│     ├─ Exercise of legal claims?                              │
│     ├─ Public interest (research with safeguards)?           │
│     └─ Freedom of expression?                                 │
│                                                                 │
│  3. IF EXEMPTION APPLIES:                                      │
│     • Restrict processing instead                             │
│     • Maintain minimal data for legal purpose                 │
│     • Document justification                                  │
│                                                                 │
│  4. IF NO EXEMPTION:                                           │
│     • Delete from primary systems                             │
│     • Flag for deletion from backups (next cycle)            │
│     • Instruct processors to delete                           │
│     • Remove from analytics/ML training data                  │
│                                                                 │
│  5. CONFIRMATION                                               │
│     • Send deletion confirmation                              │
│     • List any data retained with justification               │
│     • Inform of right to complain                             │
│                                                                 │
└────────────────────────────────────────────────────────────────┘
```

#### 3.5.3 Data Retention Despite Erasure Request

| Category | Retention Period | Justification |
|----------|------------------|---------------|
| Order records | 7 years | Tax/legal obligation |
| Fraud prevention data | 5 years | Legal obligation to prevent fraud |
| Legal claim defense | Until statute expires | Establishment/exercise of legal claims |
| Anonymized analytics | Indefinite | No longer personal data |

### 3.6 Right to Restrict Processing (Article 18)

| Ground for Restriction | Action Taken |
|------------------------|--------------|
| Accuracy contested | Processing restricted until verified |
| Unlawful processing | Restrict instead of delete (subject requests) |
| No longer needed | Restrict for legal claims establishment |
| Objection pending | Restrict until legitimate interest assessment |

**Restricted Processing Measures**:
- Data marked as "restricted - do not process"
- Removed from marketing/active processing
- Retained only for legal reference
- Access limited to authorized personnel

### 3.7 Right to Data Portability (Article 20)

#### 3.7.1 Portability Scope

| Included | Excluded |
|----------|----------|
| Data provided by data subject | Derived/inferred data |
| Data from observed activity | Anonymized data |
| Processing based on consent or contract | Processing based on other lawful bases |

#### 3.7.2 Portability Format

| Format | Use Case |
|--------|----------|
| JSON | Machine-readable, API transfer |
| CSV | Spreadsheet analysis, portability |
| PDF | Human-readable reference |

#### 3.7.3 Portability Process

1. Receive and verify request
2. Identify portable data scope
3. Extract in structured, machine-readable format
4. Provide secure download or direct transmission
5. Include data dictionary explaining fields
6. Complete within 30 days

### 3.8 Right to Object (Article 21)

| Processing Type | Objection Right |
|-----------------|-----------------|
| Direct marketing | Absolute right - must stop immediately |
| Profiling for marketing | Absolute right |
| Legitimate interest processing | Must stop unless compelling grounds |
| Scientific/historical research | Limited right |

#### 3.8.1 Marketing Objection Process

```
┌────────────────────────────────────────────────────────────────┐
│              MARKETING OBJECTION WORKFLOW                       │
├────────────────────────────────────────────────────────────────┤
│                                                                 │
│  OBJECTION RECEIVED                                             │
│       │                                                         │
│       ▼                                                         │
│  ┌─────────────────┐                                            │
│  │ ADD TO SUPPRESSION│                                          │
│  │   LIST IMMEDIATELY │                                         │
│  │   (Within 24 hours) │                                        │
│  └─────────────────┘                                            │
│       │                                                         │
│       ▼                                                         │
│  ┌─────────────────┐                                            │
│  │  STOP ALL ACTIVE   │                                         │
│  │  MARKETING TO USER  │                                        │
│  └─────────────────┘                                            │
│       │                                                         │
│       ▼                                                         │
│  ┌─────────────────┐                                            │
│  │  PROPAGATE TO ALL  │                                         │
│  │  PROCESSORS & TOOLS │                                        │
│  └─────────────────┘                                            │
│       │                                                         │
│       ▼                                                         │
│  ┌─────────────────┐                                            │
│  │  SEND CONFIRMATION │                                         │
│  │  TO DATA SUBJECT   │                                         │
│  └─────────────────┘                                            │
│                                                                 │
└────────────────────────────────────────────────────────────────┘
```

### 3.9 Automated Decision-Making Rights (Article 22)

| Aspect | Smart Dairy Implementation |
|--------|---------------------------|
| Current automated decisions | Fraud scoring, credit limit assignment |
| Significant effects | Financial impact, service denial |
| Human intervention | Request via privacy@smartdairy.com |
| Algorithmic transparency | General logic disclosed, not specific algorithm |

**Process for Contestation**:
1. Receive request for human intervention
2. Assign to trained staff member
3. Review automated decision factors
4. Consider additional information provided
5. Make new decision
6. Communicate outcome with reasoning

### 3.10 Rights Request Tracking

| Metric | Target | Measurement |
|--------|--------|-------------|
| Average response time | <15 days | Days from receipt to response |
| On-time completion | >95% | Within 30 days |
| Identity verification success | >98% | First attempt verification |
| Requestor satisfaction | >90% | Post-resolution survey |
| Escalation rate | <5% | To supervisory authority |

---

## 4. Privacy by Design

### 4.1 Privacy by Design Principles

| Principle | Implementation |
|-----------|----------------|
| **Proactive not Reactive** | Privacy integrated from project inception |
| **Privacy as Default** | Maximum privacy settings by default |
| **Privacy Embedded** | Built into design, not bolted on |
| **Full Functionality** | Positive-sum, not zero-sum approach |
| **End-to-End Protection** | Throughout data lifecycle |
| **Visibility & Transparency** | Clear privacy practices |
| **Respect for User Privacy** | User-centric approach |

### 4.2 Privacy by Design Implementation

#### 4.2.1 Development Lifecycle Integration

```
┌────────────────────────────────────────────────────────────────┐
│              PRIVACY BY DESIGN LIFECYCLE                        │
├────────────────────────────────────────────────────────────────┤
│                                                                 │
│  CONCEPT      DESIGN       DEVELOPMENT      TESTING    DEPLOY  │
│    │            │             │              │          │       │
│    ▼            ▼             ▼              ▼          ▼       │
│  ┌─────┐    ┌─────┐      ┌─────┐        ┌─────┐    ┌─────┐    │
│  │DPIA │    │Data │      │Secure│        │Privacy│   │Privacy│   │
│  │Screen│───▶│Flow │─────▶│Coding│───────▶│Testing│──▶│Config │   │
│  │     │    │Maps │      │     │        │      │   │      │   │
│  └─────┘    └─────┘      └─────┘        └─────┘    └─────┘    │
│    │            │             │              │          │       │
│    ▼            ▼             ▼              ▼          ▼       │
│  Go/No-Go   Architecture   Code Review   Pen Test   Monitoring │
│                                                                 │
└────────────────────────────────────────────────────────────────┘
```

#### 4.2.2 Privacy Requirements Checklist

| Category | Requirement | Verification |
|----------|-------------|--------------|
| **Data Minimization** | Collect only necessary data | Data inventory review |
| **Purpose Limitation** | Process only for specified purposes | Processing log review |
| **Storage Limitation** | Delete when no longer needed | Retention policy enforcement |
| **Accuracy** | Keep data accurate and up-to-date | Validation checks |
| **Integrity** | Protect against unauthorized alteration | Security controls |
| **Confidentiality** | Protect against unauthorized access | Encryption, access controls |

### 4.3 Privacy-Enhancing Technologies (PETs)

| Technology | Application | Status |
|------------|-------------|--------|
| **Pseudonymization** | Analytics datasets, research data | Implemented |
| **Encryption at Rest** | Database encryption, file storage | Implemented |
| **Encryption in Transit** | TLS 1.3 for all communications | Implemented |
| **Tokenization** | Payment card data | Implemented via Stripe |
| **Differential Privacy** | Aggregate analytics with noise | Planned Q3 2026 |
| **Homomorphic Encryption** | Secure computation on encrypted data | Under evaluation |
| **Zero-Knowledge Proofs** | Identity verification | Future consideration |

### 4.4 Default Privacy Settings

| Feature | Default Setting | Rationale |
|---------|-----------------|-----------|
| Marketing emails | Opt-out | Legitimate interest for existing customers |
| Marketing SMS | Opt-in | Higher privacy expectation |
| Analytics cookies | Opt-in (required consent) | Non-essential processing |
| Personalized ads | Opt-in | Profiling requires consent |
| Data sharing with affiliates | Opt-in | Third-party sharing |
| Location tracking | Disabled | Not necessary for service |
| Profile visibility | Private | Default data protection |

### 4.5 Data Minimization Practices

| Processing Activity | Minimization Measure |
|--------------------|---------------------|
| Registration | Email only (optional: name, phone) |
| Checkout | Shipping address only when needed |
| Payment | Tokenized, no card storage |
| Analytics | IP anonymization, event sampling |
| Support | Minimal data collection per ticket |
| Surveys | Anonymous where possible |

### 4.6 Privacy Architecture Patterns

```
┌─────────────────────────────────────────────────────────────────┐
│                 DATA ARCHITECTURE - PRIVACY LAYERED             │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │                    APPLICATION LAYER                     │   │
│  │         (Consent management, user controls)             │   │
│  └─────────────────────────────────────────────────────────┘   │
│                             │                                   │
│  ┌──────────────────────────┴──────────────────────────────┐   │
│  │                   PROCESSING LAYER                       │   │
│  │     (Purpose limitation, access controls, logging)      │   │
│  └─────────────────────────────────────────────────────────┘   │
│                             │                                   │
│  ┌──────────────────────────┴──────────────────────────────┐   │
│  │                    DATA LAYER                            │   │
│  │  (Encryption, pseudonymization, retention management)   │   │
│  └─────────────────────────────────────────────────────────┘   │
│                             │                                   │
│  ┌──────────────────────────┴──────────────────────────────┐   │
│  │                   INFRASTRUCTURE LAYER                   │   │
│  │       (Network segmentation, monitoring, backups)       │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## 5. Privacy Impact Assessment (PIA/DPIA)

### 5.1 DPIA Requirements

#### 5.1.1 Mandatory DPIA Triggers (WP248 Guidelines)

| Processing Type | Smart Dairy Application |
|-----------------|------------------------|
| Systematic profiling | Marketing personalization system |
| Automated decision-making with legal effects | Fraud detection, credit scoring |
| Large-scale special category data | Health/allergy information collection |
| Large-scale systematic monitoring | Website analytics, behavioral tracking |
| Vulnerable subjects (children) | If services offered to children |
| New technologies | Biometrics, AI/ML processing |

#### 5.1.2 Smart Dairy DPIA Inventory

| System/Process | DPIA Status | Last Review | Next Review |
|----------------|-------------|-------------|-------------|
| E-commerce Platform | Completed | Q4 2025 | Q4 2026 |
| Marketing Automation | Completed | Q3 2025 | Q3 2026 |
| Customer Analytics | Completed | Q4 2025 | Q4 2026 |
| Fraud Detection System | Completed | Q2 2025 | Q2 2026 |
| AI Recommendation Engine | In Progress | - | Q2 2026 |
| Mobile Application | Planned | - | Q3 2026 |

### 5.2 DPIA Process

```
┌────────────────────────────────────────────────────────────────┐
│                DPIA PROCESS FLOW                                │
├────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────┐                                            │
│  │ 1. IDENTIFY NEED │                                           │
│  │   (Screening Qs) │                                           │
│  └────────┬────────┘                                            │
│           │                                                     │
│           ▼                                                     │
│  ┌─────────────────┐     YES     ┌─────────────────┐           │
│  │ DPIA REQUIRED?  │────────────▶│  2. PRE-ASSESS  │           │
│  │                 │             │  (Data mapping) │           │
│  └────────┬────────┘             └────────┬────────┘           │
│           │ NO                            │                    │
│           ▼                               ▼                    │
│  ┌─────────────────┐             ┌─────────────────┐           │
│  │ Document reason │             │ 3. RISK ASSESS  │           │
│  │ File for audit  │             │  (Likelihood/   │           │
│  └─────────────────┘             │   Impact)       │           │
│                                  └────────┬────────┘           │
│                                           │                    │
│                                           ▼                    │
│                                  ┌─────────────────┐           │
│                                  │ 4. MITIGATION   │           │
│                                  │  (Controls/     │           │
│                                  │   Safeguards)   │           │
│                                  └────────┬────────┘           │
│                                           │                    │
│                                           ▼                    │
│                                  ┌─────────────────┐           │
│                                  │ 5. RESIDUAL RISK│           │
│                                  │  (Acceptable?   │           │
│                                  │   Consult DPA?) │           │
│                                  └────────┬────────┘           │
│                                           │                    │
│              ┌────────────────────────────┼────────────────┐   │
│              ▼ NO                         ▼ YES            ▼   │
│       ┌─────────────┐              ┌─────────────┐    ┌────────┐│
│       │ REDESIGN OR │              │ 6. APPROVAL │    │ CONSULT││
│       │  DO NOT     │              │   (DPO +    │───▶│  DPA   ││
│       │  PROCEED    │              │  Legal)     │    │        ││
│       └─────────────┘              └──────┬──────┘    └────────┘│
│                                           │                    │
│                                           ▼                    │
│                                  ┌─────────────────┐           │
│                                  │ 7. IMPLEMENT &  │           │
│                                  │    MONITOR      │           │
│                                  │  (Regular review│           │
│                                  │   scheduled)    │           │
│                                  └─────────────────┘           │
│                                                                 │
└────────────────────────────────────────────────────────────────┘
```

### 5.3 DPIA Screening Questions

| # | Question | If YES |
|---|----------|--------|
| 1 | Does processing involve systematic monitoring of publicly accessible areas? | DPIA Required |
| 2 | Does processing involve large-scale processing of special category data? | DPIA Required |
| 3 | Does processing involve automated decision-making with legal/significant effects? | DPIA Required |
| 4 | Does processing involve systematic profiling on a large scale? | DPIA Required |
| 5 | Does processing involve vulnerable subjects (children, employees) on large scale? | DPIA Required |
| 6 | Does processing involve innovative use/application of new technological solutions? | DPIA Required |
| 7 | Does processing prevent data subjects from exercising rights/using services? | DPIA Required |

### 5.4 DPIA Template Structure

| Section | Content |
|---------|---------|
| 1. Project Description | Purpose, scope, stakeholders |
| 2. Data Flow Mapping | Data sources, flows, storage, deletion |
| 3. Risk Assessment | Threats, vulnerabilities, likelihood, impact |
| 4. Risk Mitigation | Controls, safeguards, residual risk |
| 5. Stakeholder Consultation | Internal and external consultation |
| 6. Residual Risk Assessment | Acceptability determination |
| 7. Approval | Sign-off by DPO and project owner |
| 8. Review Schedule | Regular review dates |

### 5.5 Risk Assessment Matrix

| Likelihood \ Impact | Minimal (1) | Limited (2) | Significant (3) | Severe (4) |
|---------------------|-------------|-------------|-----------------|------------|
| **Unlikely (1)** | Low (1) | Low (2) | Medium (3) | Medium (4) |
| **Possible (2)** | Low (2) | Medium (4) | Medium (6) | High (8) |
| **Likely (3)** | Medium (3) | Medium (6) | High (9) | Critical (12) |
| **Very Likely (4)** | Medium (4) | High (8) | Critical (12) | Critical (16) |

**Risk Treatment**:
- Low (1-3): Accept with monitoring
- Medium (4-6): Implement additional controls
- High (8-9): Major redesign or enhanced safeguards
- Critical (12-16): Do not proceed without fundamental redesign

---

## 6. Consent Management

### 6.1 Consent Framework

#### 6.1.1 Valid Consent Criteria

| Criterion | Requirement | Implementation |
|-----------|-------------|----------------|
| **Freely given** | No coercion, conditional service | Separate consent from terms |
| **Specific** | One purpose per consent | Granular checkboxes |
| **Informed** | Clear explanation | Plain language descriptions |
| **Unambiguous** | Clear affirmative action | No pre-ticked boxes |
| **Withdrawable** | Easy withdrawal | One-click unsubscribe |

#### 6.1.2 Consent Records

| Data Element | Stored | Retention |
|--------------|--------|-----------|
| Consent timestamp | Yes | Duration of relationship + 2 years |
| Consent mechanism | Yes | Duration of relationship + 2 years |
| Consent wording version | Yes | Permanent (audit trail) |
| IP address | Yes (hashed) | Duration of relationship + 2 years |
| Device identifier | Yes (hashed) | Duration of relationship + 2 years |
| Withdrawal timestamp | Yes | Permanent (audit trail) |

### 6.2 Granular Consent

#### 6.2.1 Consent Categories

```
┌─────────────────────────────────────────────────────────────────┐
│                 GRANULAR CONSENT STRUCTURE                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ESSENTIAL (No consent required - contractual necessity)        │
│  ☑ Account creation and management                              │
│  ☑ Order processing and fulfillment                             │
│  ☑ Payment processing                                           │
│  ☑ Customer service communications                              │
│  ☑ Legal compliance                                             │
│  [Required - cannot opt out]                                    │
│                                                                 │
│  ─────────────────────────────────────────────────────────────  │
│                                                                 │
│  FUNCTIONAL (Consent recommended - better service)              │
│  ☐ Remember preferences (language, currency)                   │
│  ☐ Save shopping cart across sessions                          │
│  ☐ Wishlist functionality                                       │
│  [Optional - service works without]                             │
│                                                                 │
│  ─────────────────────────────────────────────────────────────  │
│                                                                 │
│  ANALYTICS (Consent required - not essential)                   │
│  ☐ Website usage analytics (improve experience)                │
│  ☐ Error reporting and debugging                               │
│  [Optional - you can say no]                                    │
│                                                                 │
│  ─────────────────────────────────────────────────────────────  │
│                                                                 │
│  MARKETING (Separate consent for each channel)                  │
│  ☐ Marketing emails (product updates, offers)                  │
│  ☐ Marketing SMS (promotional messages)                        │
│  ☐ Personalized advertising (based on browsing)                │
│  ☐ Third-party marketing partners                              │
│  [Optional - you can say no and still use service]             │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 6.3 Consent Mechanisms

#### 6.3.1 Registration Consent

```html
<!-- Example: Registration Form Consent -->
<fieldset>
  <legend>Marketing Preferences</legend>
  
  <label class="consent-checkbox">
    <input type="checkbox" name="consent_email_marketing" value="yes">
    <span class="consent-text">
      I agree to receive marketing emails about products, promotions, and updates. 
      <a href="/privacy/marketing-consent" target="_blank">Learn more</a>
    </span>
  </label>
  
  <label class="consent-checkbox">
    <input type="checkbox" name="consent_sms_marketing" value="yes">
    <span class="consent-text">
      I agree to receive marketing SMS messages. 
      Message and data rates may apply.
      <a href="/privacy/sms-consent" target="_blank">Learn more</a>
    </span>
  </label>
  
  <p class="consent-notice">
    You can change your preferences or withdraw consent at any time in your 
    <a href="/account/preferences">account settings</a> or by clicking 
    "unsubscribe" in our emails.
  </p>
</fieldset>
```

#### 6.3.2 Cookie Consent Banner

```javascript
// Example: Cookie Consent Implementation
const cookieConsent = {
  categories: {
    essential: {
      required: true,
      description: "Necessary for website functionality"
    },
    analytics: {
      required: false,
      description: "Help us improve our website",
      processors: ["Google Analytics", "Mixpanel"]
    },
    marketing: {
      required: false,
      description: "Personalized advertisements",
      processors: ["Facebook Pixel", "Google Ads"]
    }
  },
  
  recordConsent: function(preferences) {
    const consentRecord = {
      timestamp: new Date().toISOString(),
      preferences: preferences,
      version: "2.1",
      ipHash: hashIp(this.getIp()),
      userAgent: navigator.userAgent
    };
    this.saveToDatabase(consentRecord);
    this.setCookie("consent_preferences", JSON.stringify(preferences), 365);
  }
};
```

### 6.4 Consent Withdrawal

#### 6.4.1 Withdrawal Mechanisms

| Channel | Withdrawal Method | Processing Time |
|---------|-------------------|-----------------|
| Marketing emails | Unsubscribe link in email | Immediate (technical), 48 hours (full propagation) |
| Marketing SMS | Reply "STOP" | Immediate |
| All marketing | Account preferences page | Immediate |
| All marketing | Email to privacy@smartdairy.com | 48 hours |
| Cookies | Cookie settings panel | Immediate (browser) |

#### 6.4.2 Withdrawal Process

```
┌────────────────────────────────────────────────────────────────┐
│              CONSENT WITHDRAWAL WORKFLOW                        │
├────────────────────────────────────────────────────────────────┤
│                                                                 │
│  1. DETECT WITHDRAWAL                                           │
│     • Unsubscribe link click                                    │
│     • Account preference change                                 │
│     • Email request                                             │
│     • SMS "STOP" reply                                          │
│                                                                 │
│  2. IMMEDIATE ACTION                                            │
│     • Add to suppression list (within 24 hours)                │
│     • Stop active campaigns                                     │
│     • Log withdrawal timestamp                                  │
│                                                                 │
│  3. PROPAGATION                                                 │
│     • Update CRM/marketing systems (within 48 hours)           │
│     • Notify processors (within 48 hours)                      │
│     • Update analytics profiles                                 │
│                                                                 │
│  4. CONFIRMATION                                                │
│     • Send confirmation message (if not via account)           │
│     • Inform of other processing that continues                 │
│     • Provide contact for questions                             │
│                                                                 │
│  5. AUDIT & RECORD                                              │
│     • Maintain withdrawal record                                │
│     • Include in compliance reports                             │
│     • Available for regulatory inspection                       │
│                                                                 │
└────────────────────────────────────────────────────────────────┘
```

### 6.5 Consent Audit Trail

| Record Type | Retention | Location |
|-------------|-----------|----------|
| Consent grant | 6 years | Encrypted database |
| Consent withdrawal | Permanent | Immutable audit log |
| Consent version history | Permanent | Document management |
| Processor consent records | Duration + 2 years | DPA files |

---

## 7. Data Breach Notification

### 7.1 Breach Response Framework

#### 7.1.1 Breach Classification

| Severity | Definition | Examples |
|----------|------------|----------|
| **Critical** | High risk to rights and freedoms | Database exfiltration, credential compromise of all users |
| **High** | Significant risk, limited scope | Single system breach, limited data exposure |
| **Medium** | Limited risk, contained | Internal misdirected email, temporary exposure |
| **Low** | Minimal risk, no notification required | Encrypted data exposure (keys secure), anonymized data |

#### 7.1.2 Breach Response Timeline

| Time | Action | Owner |
|------|--------|-------|
| 0-1 hour | Detection and initial assessment | SOC / Security Team |
| 1-4 hours | Containment and evidence preservation | Security Lead |
| 4-24 hours | Investigation and risk assessment | DPO + Security |
| 24-48 hours | Decision on notification requirement | DPO + Legal |
| 48-72 hours | Supervisory authority notification | DPO |
| As soon as practicable | Data subject notification | DPO + Communications |

### 7.2 72-Hour Notification

#### 7.2.1 Authority Notification Requirements

| Information Element | Source |
|--------------------|--------|
| Nature of breach | Incident report |
| Categories and approximate number of data subjects | Database queries |
| Categories and approximate number of records | Database queries |
| Likely consequences | Risk assessment |
| Measures taken or proposed | Incident response team |
| Contact point | DPO details |

#### 7.2.2 Lead Supervisory Authority Determination

| Criteria | Lead Authority |
|----------|----------------|
| Main establishment in EU | Authority of main establishment |
| No EU establishment | Authority of place of processing |
| Multiple affected countries | Lead authority coordinating |

**Smart Dairy Status**: No EU establishment. Lead authority will be determined by:
1. Place of habitual residence of most affected data subjects
2. Location of processor with primary breach
3. First authority notified in case of uncertainty

### 7.3 Data Subject Notification

#### 7.3.1 When Required

Notification to data subjects is required when breach is "likely to result in high risk" considering:

| Factor | High Risk Indicator |
|--------|---------------------|
| Nature of data | Special categories, financial, credentials |
| Severity of potential impact | Identity theft, financial loss, discrimination |
| Vulnerability of individuals | Children, employees, vulnerable groups |
| Number affected | Large-scale exposure |
| Sensitivity of context | Health, financial services |

#### 7.3.2 Notification Templates

##### Template A: Critical Breach - Immediate Notification

```
SUBJECT: Important Security Notice - Action Required

Dear [Customer Name],

We are writing to inform you of a security incident that may have affected 
your personal information held by Smart Dairy.

WHAT HAPPENED
On [DATE], we detected unauthorized access to our [SYSTEM]. We immediately 
secured the system and launched an investigation.

WHAT INFORMATION WAS INVOLVED
The accessed information may have included:
• Your name and email address
• Your shipping address
• Your order history
• [Other relevant data]

Payment card information was NOT accessed as we do not store full card 
details (processed securely by Stripe).

WHAT WE ARE DOING
• We have secured the vulnerability
• We have engaged cybersecurity experts
• We have notified the relevant data protection authorities
• We are implementing additional security measures

WHAT YOU SHOULD DO
• Change your Smart Dairy password immediately
• Monitor your accounts for unusual activity
• Be cautious of phishing emails claiming to be from us
• Consider enabling two-factor authentication

We sincerely apologize for this incident and any inconvenience caused.

For questions, contact: privacy@smartdairy.com or +[PHONE]

Sincerely,
[Name]
Data Protection Officer
Smart Dairy Ltd.
```

##### Template B: High Breach - Informational

```
SUBJECT: Important Information About Your Account Security

Dear [Customer Name],

We are notifying you of a security incident involving Smart Dairy systems.

On [DATE], we identified [BRIEF DESCRIPTION]. Your information was among 
data that may have been visible during this incident.

The information potentially affected includes [DATA TYPES]. No action is 
required on your part, but we wanted to inform you as a precaution.

We have taken the following steps:
• [REMEDIATION ACTIONS]
• Enhanced monitoring implemented
• [Additional measures]

If you have questions, please contact privacy@smartdairy.com.

Sincerely,
Smart Dairy Security Team
```

### 7.4 Breach Documentation

| Document | Purpose | Retention |
|----------|---------|-----------|
| Incident report | Full incident details | 6 years |
| Risk assessment | Notification decision rationale | 6 years |
| Authority notification | Regulatory compliance | 6 years |
| Data subject communications | Proof of notification | 6 years |
| Lessons learned | Process improvement | Permanent |

### 7.5 Breach Metrics and KPIs

| Metric | Target | Current |
|--------|--------|---------|
| Mean time to detect (MTTD) | <4 hours | [To be measured] |
| Mean time to respond (MTTR) | <1 hour | [To be measured] |
| Notification within 72 hours | 100% | Target |
| False positive rate | <10% | Target |
| Breach recurrence rate | 0% | Target |

---

## 8. Cross-Border Data Transfers

### 8.1 Transfer Mechanisms

#### 8.1.1 Transfer Scenarios

| Scenario | Mechanism | Status |
|----------|-----------|--------|
| Bangladesh → EU | Not applicable | Data importer |
| EU → Bangladesh | SCCs + TIA | Implemented |
| EU → USA (AWS) | SCCs + DPF | Implemented |
| EU → UK | Adequacy decision | Implemented |
| EU → Other third countries | SCCs + TIA | Case-by-case |

#### 8.1.2 Transfer Impact Assessment (TIA)

For each transfer to a third country, Smart Dairy conducts a TIA:

```
┌────────────────────────────────────────────────────────────────┐
│              TRANSFER IMPACT ASSESSMENT (TIA)                  │
├────────────────────────────────────────────────────────────────┤
│                                                                 │
│  1. DOCUMENT THE TRANSFER                                       │
│     • Data exporter (Smart Dairy or processor)                 │
│     • Data importer (processor location)                       │
│     • Data categories and subjects                             │
│     • Purpose of transfer                                       │
│     • Transfer mechanism (SCCs/BCRs/Ad-hoc)                    │
│                                                                 │
│  2. ASSESS THIRD COUNTRY LAWS                                   │
│     • Access laws (national security, law enforcement)         │
│     • Surveillance frameworks                                  │
│     • Data protection laws                                      │
│     • Redress mechanisms for data subjects                     │
│                                                                 │
│  3. IDENTIFY SUPPLEMENTARY MEASURES                             │
│     • Technical: Encryption, pseudonymization                  │
│     • Contractual: Additional SCC clauses                      │
│     • Organizational: Access controls, policies                │
│                                                                 │
│  4. EVALUATE EFFECTIVENESS                                      │
│     • Can measures ensure EU-level protection?                 │
│     • Are there gaps that cannot be mitigated?                 │
│                                                                 │
│  5. DECISION                                                    │
│     • Proceed with measures                                     │
│     • Proceed with additional safeguards                        │
│     • Do not proceed (if protection not achievable)            │
│                                                                 │
│  6. MONITOR & REVIEW                                            │
│     • Annual review of third country laws                      │
│     • Update measures as needed                                │
│     • Document changes                                          │
│                                                                 │
└────────────────────────────────────────────────────────────────┘
```

### 8.2 Standard Contractual Clauses (SCCs)

#### 8.2.1 SCC Implementation

| Module | Application |
|--------|-------------|
| Module 1 (Controller to Controller) | Affiliate data sharing |
| Module 2 (Controller to Processor) | Primary processor contracts |
| Module 3 (Processor to Processor) | Sub-processor arrangements |
| Module 4 (Processor to Controller) | Return of processed data |

#### 8.2.2 SCC Annexes

| Annex | Content |
|-------|---------|
| Annex I | Parties description, data subjects, categories, sensitive data |
| Annex II | Technical and organizational measures (security) |
| Annex III | List of sub-processors (Module 2 & 3) |

### 8.3 Adequacy Decisions

| Country/Region | Status | Application |
|----------------|--------|-------------|
| United Kingdom | Adequate | Post-Brexit transfers |
| Switzerland | Adequate | Swiss customer data |
| Japan | Adequate | Japanese operations |
| South Korea | Adequate | Vendor relationships |
| Canada (commercial) | Adequate | Canadian vendors |
| USA (EU-US Data Privacy Framework) | Adequate | Certified organizations only |

### 8.4 Supplementary Measures

#### 8.4.1 Technical Measures

| Measure | Implementation | Effectiveness |
|---------|----------------|---------------|
| End-to-end encryption | TLS 1.3 + AES-256 | High |
| Pseudonymization | Data tokenization | Medium |
| Split processing | Processing in EU, storage encrypted | High |
| Encryption key management | Keys held in EU | High |

#### 8.4.2 Organizational Measures

| Measure | Description |
|--------|-------------|
| Data minimization | Only necessary data transferred |
| Purpose limitation | Strict adherence to transfer purpose |
| Access logging | All access monitored and logged |
| Regular audits | Quarterly transfer audits |

---

## 9. Records of Processing Activities (ROPA)

### 9.1 Article 30 Requirements

Smart Dairy maintains comprehensive records of processing activities including:

| Section | Required Information |
|---------|---------------------|
| 9.1 | Controller/processor name and contact |
| 9.2 | Joint processing arrangements |
| 9.3 | DPO contact details |
| 9.4 | Processing purposes |
| 9.5 | Categories of data subjects |
| 9.6 | Categories of personal data |
| 9.7 | Categories of recipients |
| 9.8 | International transfers |
| 9.9 | Retention periods |
| 9.10 | Security measures |

### 9.2 ROPA Structure

#### 9.2.1 Processing Activity Record

| Field | Example |
|-------|---------|
| **Activity ID** | PA-001 |
| **Activity Name** | E-commerce Order Processing |
| **Department** | Sales & Operations |
| **Data Controller** | Smart Dairy Ltd. |
| **Data Protection Officer** | dpo@smartdairy.com |
| **Purpose** | Process customer orders, deliver products, handle payments |
| **Legal Basis** | Contract (Art. 6(1)(b)) |
| **Data Subjects** | Customers |
| **Data Categories** | Name, address, email, phone, order details, payment info (tokenized) |
| **Special Categories** | Health data (allergies) - explicit consent |
| **Data Sources** | Direct from data subject |
| **Recipients** | Shipping providers, payment processors, tax authorities |
| **Third Country Transfers** | AWS (USA - DPF certified) |
| **Retention Period** | 7 years (tax records) |
| **Security Measures** | Encryption, access controls, audit logs |
| **Last Reviewed** | 2026-01-15 |
| **Next Review** | 2027-01-15 |

### 9.3 ROPA Inventory

| Category | Number of Activities | Review Frequency |
|----------|---------------------|------------------|
| Sales & Marketing | 12 | Annual |
| Customer Service | 8 | Annual |
| Finance & Legal | 6 | Annual |
| HR & Recruitment | 10 | Annual |
| IT & Security | 15 | Annual |
| Operations & Logistics | 7 | Annual |

### 9.4 ROPA Maintenance

| Process | Frequency | Owner |
|---------|-----------|-------|
| Review existing activities | Annual | DPO |
| Add new activities | As needed | Process Owner + DPO |
| Update changed activities | Within 30 days of change | Process Owner |
| Archive obsolete activities | Upon process retirement | DPO |
| Compliance audit | Annual | Internal Audit |

---

## 10. DPO Requirements

### 10.1 DPO Appointment

#### 10.1.1 Mandatory Appointment Triggers

| Trigger | Smart Dairy Application |
|---------|------------------------|
| Public authority processing | Not applicable (private company) |
| Large-scale systematic monitoring | Applicable (website analytics, profiling) |
| Large-scale special category processing | Applicable (allergy/health data collection) |

**Conclusion**: DPO appointment is mandatory for Smart Dairy.

#### 10.1.2 DPO Profile

| Requirement | Specification |
|-------------|---------------|
| Expertise | Data protection law and practice |
| Independence | Reports to Board, not line management |
| Resources | Dedicated time, budget, authority |
| Contact | DPO@smartdairy.com |

### 10.2 DPO Responsibilities

| Area | Responsibilities |
|------|------------------|
| **Inform and Advise** | Guide organization on GDPR compliance |
| **Monitor Compliance** | Oversee policies, training, audits |
| **Cooperate with Supervisory Authority** | Liaison for regulatory matters |
| **Data Subject Contact** | Handle rights requests and complaints |
| **DPIA Oversight** | Review and approve impact assessments |
| **Incident Response** | Lead breach notification process |

### 10.3 DPO Operating Model

```
┌────────────────────────────────────────────────────────────────┐
│                    DPO ORGANIZATIONAL STRUCTURE                 │
├────────────────────────────────────────────────────────────────┤
│                                                                 │
│                    ┌─────────────────┐                         │
│                    │    BOARD OF     │                         │
│                    │    DIRECTORS    │                         │
│                    │  (DPO reports)  │                         │
│                    └────────┬────────┘                         │
│                             │                                   │
│              ┌──────────────┼──────────────┐                   │
│              │              │              │                    │
│              ▼              ▼              ▼                    │
│       ┌──────────┐   ┌──────────┐   ┌──────────┐              │
│       │   DPO    │   │   CTO    │   │   CMO    │              │
│       │  Office  │   │          │   │          │              │
│       └────┬─────┘   └──────────┘   └──────────┘              │
│            │                                                    │
│    ┌───────┴───────┐                                            │
│    │               │                                            │
│    ▼               ▼                                            │
│ ┌────────┐    ┌────────┐                                       │
│ │Privacy │    │Privacy │                                       │
│ │Officer │    │Officer │                                       │
│ │(Legal) │    │(Tech)  │                                       │
│ └────────┘    └────────┘                                       │
│                                                                 │
└────────────────────────────────────────────────────────────────┘
```

### 10.4 DPO Contact Information

| Channel | Details |
|---------|---------|
| Email | dpo@smartdairy.com |
| Postal | Data Protection Officer, Smart Dairy Ltd., [Address], Bangladesh |
| Response Time | 48 hours (standard), 24 hours (urgent) |
| Languages | English, Bangla |
| Availability | Business hours (BST) + emergency contact |

---

## 11. Data Processing Agreements (DPA)

### 11.1 Processor Management

#### 11.1.1 Key Processors

| Processor | Service | Location | SCCs Status |
|-----------|---------|----------|-------------|
| Amazon Web Services (AWS) | Cloud infrastructure | USA (global) | DPF + SCCs |
| Stripe | Payment processing | USA | DPF + SCCs |
| SendGrid (Twilio) | Email delivery | USA | SCCs |
| Google Analytics | Web analytics | USA | SCCs (user consent required) |
| Meta Platforms | Advertising | USA | SCCs (user consent required) |
| Cloudflare | CDN & Security | USA | DPF + SCCs |

#### 11.1.2 DPA Requirements

| Article 28 Requirement | Implementation |
|------------------------|----------------|
| Process only on documented instructions | DPA with processing instructions |
| Ensure confidentiality commitments | Confidentiality clauses in contracts |
| Implement security measures | Annex II security specifications |
| Engage sub-processors only with authorization | Sub-processor list + notification |
| Assist with data subject rights | Response time SLAs |
| Assist with security and breach notification | 24-hour notification requirement |
| Delete or return data after termination | Data deletion certificate |
| Make available information for compliance | Audit rights, annual reports |

### 11.2 DPA Template Structure

See Appendix C for full template. Key sections include:

| Section | Content |
|---------|---------|
| 1. Definitions | GDPR-aligned terminology |
| 2. Processing Details | Nature, purpose, duration, data types |
| 3. Controller Obligations | Lawful basis, data subject rights |
| 4. Processor Obligations | Article 28 compliance |
| 5. Sub-processors | List and notification procedures |
| 6. Security Measures | Technical and organizational |
| 7. Data Subject Rights | Assistance and response times |
| 8. Personal Data Breaches | Notification procedures |
| 9. Audits | Audit rights and procedures |
| 10. Data Return/Deletion | Post-termination procedures |
| 11. Liability | Insurance and liability caps |
| Annex A - Processing Details | Specific to engagement |
| Annex B - Security Measures | Technical specifications |
| Annex C - Sub-processors | Current list |

### 11.3 Processor Due Diligence

| Assessment Area | Verification Method |
|-----------------|---------------------|
| Certifications | ISO 27001, SOC 2, DPF certification |
| Security practices | Security questionnaire, penetration test reports |
| GDPR compliance | DPA review, privacy policy review |
| Data location | Data center locations, transfer mechanisms |
| Sub-processor transparency | Full sub-processor list |
| Incident history | Breach notification history |
| Financial stability | Credit check for critical processors |

---

## 12. Cookie Compliance

### 12.1 Cookie Classification

| Category | Examples | Consent Required |
|----------|----------|------------------|
| **Strictly Necessary** | Session cookies, CSRF tokens, cart cookies | No |
| **Functional** | Language preference, currency, login state | Yes (soft opt-out) |
| **Analytics** | Google Analytics, Mixpanel, heatmaps | Yes |
| **Marketing** | Facebook Pixel, Google Ads, retargeting | Yes |
| **Third-Party** | Social media widgets, embedded content | Yes |

### 12.2 Cookie Banner Implementation

```
┌─────────────────────────────────────────────────────────────────┐
│                  COOKIE CONSENT BANNER                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  🍪 We value your privacy                                       │
│                                                                 │
│  We use cookies to enhance your browsing experience, serve      │
│  personalized ads or content, and analyze our traffic.          │
│  By clicking "Accept All", you consent to our use of cookies.   │
│                                                                 │
│  [Manage Preferences]      [Accept All]      [Reject All]      │
│                                                                 │
│  Read our Cookie Policy for more information.                   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 12.3 Cookie Preference Center

```
┌─────────────────────────────────────────────────────────────────┐
│              COOKIE PREFERENCES                                 │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Strictly Necessary (Always Active)                            │
│  ─────────────────────────────────────────────                  │
│  These cookies are essential for the website to function        │
│  properly. They cannot be switched off.                         │
│  ☑ Session management                                          │
│  ☑ Security                                                    │
│                                                                 │
│  Functional                                                     │
│  ─────────────────────────────────────────────                  │
│  These cookies enable enhanced functionality and                │
│  personalization.                                                                               │
│  ☐ Remember my preferences                                     │
│  ☐ Save my location for shipping estimates                     │
│                                                                 │
│  Analytics                                                      │
│  ─────────────────────────────────────────────                  │
│  These cookies help us understand how visitors interact         │
│  with our website.                                              │
│  ☐ Google Analytics                                            │
│  ☐ Website performance monitoring                              │
│                                                                 │
│  Marketing                                                      │
│  ─────────────────────────────────────────────                  │
│  These cookies are used to deliver relevant advertisements.     │
│  ☐ Personalized advertising                                    │
│  ☐ Social media integration                                    │
│                                                                 │
│  [Save Preferences]                                             │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 12.4 Cookie Policy Requirements

| Element | Description |
|---------|-------------|
| What are cookies | Plain language explanation |
| Types used | Categories with examples |
| Purpose | Why each type is used |
| Duration | Session vs. persistent |
| Third parties | Who sets cookies |
| How to manage | Browser settings, preference center |
| Changes | How changes are communicated |

### 12.5 Cookie Audit and Inventory

| Field | Example |
|-------|---------|
| Cookie Name | _ga |
| Provider | Google |
| Purpose | Analytics - distinguish users |
| Duration | 2 years |
| Category | Analytics |
| Consent Required | Yes |

---

## 13. Marketing Communications

### 13.1 Marketing Consent Framework

#### 13.1.1 Consent Requirements by Channel

| Channel | Consent Required | Soft Opt-in Permitted |
|---------|------------------|----------------------|
| Email marketing | Yes | Yes (for existing customers) |
| SMS marketing | Yes | No (separate explicit consent) |
| Push notifications | Yes | No |
| Direct mail | Legitimate interest assessment | Case-by-case |
| Telephone (live) | TPS check required | No cold calling |
| Telephone (automated) | Prior consent required | No |

#### 13.1.2 Soft Opt-in Conditions (Article 13 ePrivacy)

| Condition | Requirement |
|-----------|-------------|
| Existing relationship | Customer has purchased or negotiated to purchase |
| Similar products | Marketing relates to similar products/services |
| Clear opportunity to object | Opt-out provided at collection and in each message |
| Identity disclosure | Clear identification of sender |

### 13.2 Unsubscribe Mechanisms

| Method | Implementation | Processing Time |
|--------|----------------|-----------------|
| Email unsubscribe | One-click link in email footer | Immediate |
| SMS unsubscribe | Reply "STOP" | Immediate |
| Account preferences | Self-service in account settings | Immediate |
| Contact form | privacy@smartdairy.com | 48 hours |
| Postal request | DPO address | 7 days |

### 13.3 Marketing Compliance Checklist

| Requirement | Verification |
|-------------|--------------|
| Valid consent obtained | Consent records verified |
| Consent withdrawal respected | Suppression lists maintained |
| Sender identification | Clear "From" name and address |
| Subject line accuracy | No misleading content |
| Unsubscribe link | Functional, prominent |
| Physical address | Included in email |

---

## 14. Third-Party Processors

### 14.1 Due Diligence Process

```
┌────────────────────────────────────────────────────────────────┐
│              THIRD-PARTY DUE DILIGENCE PROCESS                  │
├────────────────────────────────────────────────────────────────┤
│                                                                 │
│  1. INITIAL ASSESSMENT                                          │
│     • Data protection questionnaire                             │
│     • Privacy policy review                                     │
│     • Certification review (ISO 27001, SOC 2, DPF)            │
│                                                                 │
│  2. RISK EVALUATION                                             │
│     • Data sensitivity classification                          │
│     • Volume assessment                                         │
│     • Geographic risk (third country transfers)                │
│     • Sub-processor exposure                                    │
│                                                                 │
│  3. CONTRACT NEGOTIATION                                        │
│     • DPA with Article 28 clauses                              │
│     • SCCs for international transfers                         │
│     • Security requirements                                     │
│     • Audit rights                                              │
│                                                                 │
│  4. ONBOARDING                                                  │
│     • Technical integration review                              │
│     • Data flow mapping                                         │
│     • Incident response procedures                              │
│     • Training for relevant staff                               │
│                                                                 │
│  5. ONGOING MONITORING                                          │
│     • Annual security reviews                                   │
│     • Sub-processor change notifications                        │
│     • Incident reporting review                                  │
│     • Compliance audits                                          │
│                                                                 │
└────────────────────────────────────────────────────────────────┘
```

### 14.2 Audit Rights

| Audit Type | Frequency | Scope |
|------------|-----------|-------|
| Self-assessment questionnaire | Annual | All processors |
| Security certification review | Annual | Critical processors |
| On-site audit | Every 2-3 years | High-risk processors |
| Penetration test review | Annual | Infrastructure processors |
| Incident response drill | Annual | Critical processors |

### 14.3 Sub-processor Management

| Requirement | Implementation |
|-------------|----------------|
| Prior authorization | General authorization in DPA for listed sub-processors |
| New sub-processor notification | 30 days prior notice required |
| Objection right | Right to object to new sub-processors |
| Liability | Processor remains liable for sub-processor acts |

---

## 15. Compliance Monitoring

### 15.1 Internal Audit Program

| Audit Type | Frequency | Scope |
|------------|-----------|-------|
| GDPR compliance audit | Annual | All processing activities |
| DPIA review | Quarterly | High-risk processing |
| DSR handling audit | Quarterly | Rights request procedures |
| Processor audit | Annual | Key processor contracts |
| Security audit | Semi-annual | Technical safeguards |
| Training compliance | Annual | Staff training completion |

### 15.2 Compliance Metrics

| Metric | Target | Measurement |
|--------|--------|-------------|
| DSR response time | <15 days average | Tracking system |
| DSR completion rate | >95% on-time | Tracking system |
| Breach notification timeliness | 100% within 72h | Incident reports |
| Consent validity rate | 100% auditable | Audit sampling |
| Training completion | 100% relevant staff | LMS reports |
| Processor DPA coverage | 100% | Contract review |
| DPIA completion | 100% high-risk | DPIA register |

### 15.3 Continuous Improvement

| Process | Action | Owner |
|---------|--------|-------|
| Regulatory monitoring | Track GDPR guidance and case law | DPO |
| Industry best practices | Participate in privacy forums | DPO |
| Technology review | Assess new privacy technologies | CTO + DPO |
| Incident lessons learned | Update procedures post-incident | Security Lead |
| Stakeholder feedback | Customer and employee feedback | DPO |

---

## 16. Appendices

---

### Appendix A: Privacy Policy Template

```markdown
# Privacy Policy

**Last Updated:** [Date]
**Version:** [Version Number]

## 1. Introduction

Smart Dairy Ltd. ("we," "us," or "our") respects your privacy and is committed 
to protecting your personal data. This privacy policy explains how we collect, 
use, store, and protect your personal information when you:

- Visit our website (smartdairy.com)
- Make a purchase from us
- Subscribe to our marketing communications
- Contact our customer service

## 2. Data Controller

Smart Dairy Ltd. is the data controller responsible for your personal data.

**Contact Details:**
- Email: privacy@smartdairy.com
- Postal Address: [Full Address], Bangladesh
- Data Protection Officer: dpo@smartdairy.com

## 3. Information We Collect

### 3.1 Information You Provide

| Category | Examples |
|----------|----------|
| Account Information | Name, email address, password |
| Contact Information | Phone number, shipping/billing address |
| Payment Information | Payment card details (processed by Stripe) |
| Order Information | Purchase history, product preferences |
| Communications | Customer service inquiries, feedback |
| Marketing Preferences | Consent choices, unsubscribe requests |

### 3.2 Information Automatically Collected

| Category | Examples |
|----------|----------|
| Device Information | IP address, browser type, device type |
| Usage Information | Pages visited, time spent, clicks |
| Location Information | Country, city (from IP) |
| Cookies | See our Cookie Policy |

## 4. How We Use Your Information

| Purpose | Legal Basis |
|---------|-------------|
| Process and fulfill orders | Contract |
| Manage your account | Contract |
| Provide customer support | Contract |
| Send service communications | Contract |
| Send marketing (with consent) | Consent |
| Improve our website | Legitimate Interest |
| Prevent fraud | Legitimate Interest |
| Comply with legal obligations | Legal Obligation |

## 5. Data Sharing

We share your data with:

| Category | Examples | Purpose |
|----------|----------|---------|
| Service Providers | AWS, Stripe, SendGrid | Service delivery |
| Shipping Partners | DHL, FedEx | Order fulfillment |
| Marketing Partners | Google, Meta | Advertising (with consent) |
| Legal Authorities | Tax authorities, courts | Legal compliance |

## 6. International Transfers

Your data may be transferred to countries outside the EU/EEA. We ensure 
appropriate safeguards are in place, including:
- Standard Contractual Clauses
- EU-US Data Privacy Framework certification (where applicable)
- Adequacy decisions (UK, etc.)

## 7. Your Rights

Under GDPR, you have the following rights:

| Right | How to Exercise |
|-------|-----------------|
| Access | Request via privacy@smartdairy.com or account settings |
| Rectification | Update in account settings or contact us |
| Erasure | Request via privacy@smartdairy.com |
| Restriction | Request via privacy@smartdairy.com |
| Portability | Request data export in account settings |
| Objection | Unsubscribe link or privacy@smartdairy.com |
| Withdraw Consent | Any time via account settings or unsubscribe |

## 8. Data Retention

We retain your data for:

| Data Type | Retention Period |
|-----------|------------------|
| Account data | Until account deletion |
| Order data | 7 years (tax requirement) |
| Marketing consent | Until withdrawal + 2 years |
| Marketing suppression | Permanently (if opted out) |

## 9. Security

We implement appropriate technical and organizational measures:
- Encryption in transit (TLS 1.3) and at rest (AES-256)
- Access controls and authentication
- Regular security assessments
- Staff training on data protection

## 10. Cookies

See our Cookie Policy for details on cookie usage.

## 11. Children's Privacy

Our services are not intended for children under 16. We do not knowingly 
collect data from children.

## 12. Changes to This Policy

We may update this policy. Significant changes will be notified via email 
or prominent notice on our website.

## 13. Contact Us

For privacy questions or to exercise your rights:
- Email: privacy@smartdairy.com
- DPO: dpo@smartdairy.com
- Postal: [Address]

## 14. Complaints

You have the right to complain to your local data protection authority.
```

---

### Appendix B: Data Processing Agreement (DPA) Template

```
DATA PROCESSING AGREEMENT

This Data Processing Agreement ("DPA") is entered into between:

DATA CONTROLLER:
Smart Dairy Ltd.
[Address]
Bangladesh
("Controller")

and

DATA PROCESSOR:
[Processor Name]
[Address]
("Processor")

together referred to as the "Parties."

1. DEFINITIONS

1.1 Terms used but not defined in this DPA have the meanings given in GDPR.

1.2 "Applicable Data Protection Law" means GDPR and any implementing legislation.

1.3 "Personal Data Breach" means a breach of security leading to accidental 
or unlawful destruction, loss, alteration, unauthorized disclosure of, or 
access to Personal Data.

2. PROCESSING DETAILS

2.1 Subject Matter: [Description of services]

2.2 Duration: [Duration of processing]

2.3 Nature and Purpose: [Specific purposes]

2.4 Types of Personal Data: [List data categories]

2.5 Categories of Data Subjects: [e.g., customers, employees]

3. PROCESSOR OBLIGATIONS

3.1 Process only on documented instructions from Controller.

3.2 Ensure persons authorized to process are under confidentiality obligations.

3.3 Implement appropriate technical and organizational security measures.

3.4 Respect conditions for engaging sub-processors.

3.5 Assist Controller with data subject rights requests.

3.6 Assist Controller with security and breach notification obligations.

3.7 Delete or return Personal Data after service termination.

3.8 Make available information necessary for compliance demonstration.

4. SUB-PROCESSORS

4.1 Authorized Sub-processors: [List attached as Annex C]

4.2 Processor may engage additional sub-processors with 30 days prior notice.

4.3 Controller may object to new sub-processors on reasonable grounds.

5. SECURITY MEASURES

[Detailed security requirements as per Annex B]

6. PERSONAL DATA BREACHES

6.1 Processor shall notify Controller without undue delay after becoming 
aware of a Personal Data Breach (within 24 hours).

6.2 Notification shall include:
(a) Description of nature of breach
(b) Categories and approximate number of affected data subjects
(c) Likely consequences
(d) Measures taken or proposed

7. AUDITS

7.1 Controller has the right to audit Processor's compliance annually.

7.2 Processor shall provide all necessary information and assistance.

8. DATA RETURN AND DELETION

8.1 Upon termination, Processor shall return or delete all Personal Data.

8.2 Processor shall provide written certification of deletion.

9. STANDARD CONTRACTUAL CLAUSES

[Attach EU Commission SCCs where applicable]

10. GOVERNING LAW

This DPA shall be governed by [Applicable Law].

SIGNED for and on behalf of:

SMART DAIRY LTD.                    [PROCESSOR NAME]

_______________________            _______________________
Signature                          Signature

_______________________            _______________________
Name                               Name

_______________________            _______________________
Title                              Title

_______________________            _______________________
Date                               Date


ANNEX A - PROCESSING DETAILS
[Specific details for this engagement]

ANNEX B - TECHNICAL AND ORGANIZATIONAL MEASURES
[Detailed security requirements]

ANNEX C - SUB-PROCESSORS
[List of authorized sub-processors]
```

---

### Appendix C: Data Subject Rights Request Form

```
DATA SUBJECT RIGHTS REQUEST FORM

Reference Number: DSR-[YYYY]-[NNNN] (to be assigned by Smart Dairy)
Date Received: _______________

SECTION 1: REQUESTOR INFORMATION

Are you making this request for yourself or on behalf of someone else?
☐ Myself    ☐ On behalf of another (please provide authority: _________)

Full Name: ___________________________________________________

Email Address: _______________________________________________

Phone Number: ________________________________________________

Mailing Address: _____________________________________________

Account Number (if applicable): ________________________________

SECTION 2: RIGHT BEING EXERCISED

Please select the right you wish to exercise:

☐ Right of Access (Article 15)
   I would like to receive a copy of my personal data.

☐ Right to Rectification (Article 16)
   The following information is inaccurate or incomplete:
   ___________________________________________________________
   ___________________________________________________________
   Correct information: ________________________________________

☐ Right to Erasure ("Right to be Forgotten") (Article 17)
   I would like my personal data to be deleted for the following reason:
   ☐ Data no longer necessary
   ☐ Withdrawing consent
   ☐ Objecting to processing
   ☐ Unlawful processing
   ☐ Legal obligation to erase
   ☐ Children's data
   Additional information: _____________________________________

☐ Right to Restrict Processing (Article 18)
   I would like processing restricted for the following reason:
   ☐ Accuracy contested
   ☐ Unlawful processing (prefer restriction to deletion)
   ☐ No longer needed by controller but needed by me
   ☐ Objection pending

☐ Right to Data Portability (Article 20)
   Preferred format: ☐ JSON  ☐ CSV  ☐ PDF

☐ Right to Object (Article 21)
   I object to the following processing:
   ☐ Direct marketing
   ☐ Profiling for marketing
   ☐ Processing based on legitimate interests (please specify): ___
   ___________________________________________________________

☐ Right regarding Automated Decision-Making (Article 22)
   I would like human intervention regarding:
   ___________________________________________________________

SECTION 3: IDENTITY VERIFICATION

To protect your privacy, we need to verify your identity. Please provide:

☐ Government-issued ID (passport, driver's license)
☐ Proof of address (utility bill, bank statement - last 3 months)
☐ Email verification (we will send a link to your registered email)
☐ Account login (if you have an online account)

SECTION 4: ADDITIONAL INFORMATION

Please provide any additional information relevant to your request:

_______________________________________________________________
_______________________________________________________________
_______________________________________________________________

SECTION 5: DECLARATION

I confirm that the information provided in this form is accurate and complete. 
I understand that Smart Dairy may need to contact me for additional information 
to process this request.

Signature: _________________________ Date: _________________


FOR OFFICE USE ONLY

Received by: _____________________ Date: _________________

Reference Number: DSR-_____-_______

Identity Verified: ☐ Yes  ☐ No  ☐ Pending

Verification Method: ______________________________________

Assigned to: _____________________________________________

Target Response Date: ____________________________________

Status: ☐ New  ☐ In Progress  ☐ Completed  ☐ Rejected

Notes: ___________________________________________________
___________________________________________________________
```

---

### Appendix D: Data Breach Notification Templates

#### D.1 Supervisory Authority Notification Template

```
DATA BREACH NOTIFICATION TO SUPERVISORY AUTHORITY

1. NOTIFYING PARTY
   Organization: Smart Dairy Ltd.
   Contact: Data Protection Officer
   Email: dpo@smartdairy.com
   Phone: [Phone Number]

2. BREACH DETAILS
   Date/Time Discovered: [DATE/TIME]
   Date/Time of Breach: [DATE/TIME or Estimated Period]
   Discovery Method: [How breach was detected]

3. NATURE OF BREACH
   Type: ☐ Confidentiality  ☐ Integrity  ☐ Availability
   Description: [Detailed description of what happened]
   Cause: [Root cause if known]

4. AFFECTED DATA
   Categories of Personal Data: [e.g., contact details, order history]
   Special Categories: ☐ Yes  ☐ No  [If yes, specify]
   Categories of Data Subjects: [e.g., customers, employees]
   Approximate Number of Data Subjects: [Number]
   Approximate Number of Records: [Number]

5. LIKELY CONSEQUENCES
   [Description of potential adverse effects]

6. MEASURES TAKEN/PROPOSED
   Containment: [Actions taken to stop breach]
   Recovery: [Actions to recover data/systems]
   Assessment: [Forensic or other assessment]
   Long-term: [Measures to prevent recurrence]

7. COMMUNICATIONS
   Data Subject Notification Required: ☐ Yes  ☐ No
   If yes, planned date: [DATE]
   If no, reasons: [Justification]

Submitted by: ___________________ Date: ______________
```

#### D.2 Data Subject Breach Notification Template

See Section 7.3.2 for full templates.

---

### Appendix E: Records of Processing Activities (ROPA) Template

```
RECORD OF PROCESSING ACTIVITIES

Controller: Smart Dairy Ltd.
DPO: dpo@smartdairy.com
Last Updated: [DATE]

┌─────────────────────────────────────────────────────────────────┐
│ PROCESSING ACTIVITY RECORD                                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Activity ID: _______________                                   │
│  Activity Name: _____________________________________________  │
│  Department/Function: ________________________________________  │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ PURPOSES OF PROCESSING                                   │   │
│  │ _______________________________________________________ │   │
│  │ _______________________________________________________ │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ DATA SUBJECTS                                           │   │
│  │ ☐ Customers  ☐ Employees  ☐ Prospects  ☐ Suppliers    │   │
│  │ ☐ Children   ☐ Vulnerable  ☐ Other: ________________  │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ CATEGORIES OF PERSONAL DATA                             │   │
│  │ ☐ Identity  ☐ Contact  ☐ Financial  Transactional      │   │
│  │ ☐ Technical ☐ Profile  ☐ Usage      ☐ Communications  │   │
│  │ ☐ Special categories: ________________________________  │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ RECIPIENTS/CATEGORIES OF RECIPIENTS                     │   │
│  │ Internal: _____________________________________________ │   │
│  │ External: _____________________________________________ │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ INTERNATIONAL TRANSFERS                                 │   │
│  │ ☐ None                                                  │   │
│  │ ☐ Yes: To _____________ via _________________           │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ RETENTION PERIODS                                       │   │
│  │ _______________________________________________________ │   │
│  │ Deletion Method: _____________________________________  │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ SECURITY MEASURES                                       │   │
│  │ ☐ Encryption  ☐ Access controls  ☐ Audit logs          │   │
│  │ ☐ Pseudonymization  ☐ Other: ________________________   │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                 │
│  DPIA Required: ☐ Yes  ☐ No  ☐ Completed (Ref: ________)     │
│                                                                 │
│  Review Date: _______________                                   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

### Appendix F: Legitimate Interest Assessment (LIA) Template

```
LEGITIMATE INTEREST ASSESSMENT

Assessment ID: LIA-_______
Date: ___________
Assessor: ___________

1. PURPOSE TEST - Is there a legitimate interest?

1.1 What is the specific business objective?
_______________________________________________________________

1.2 Who benefits from the processing?
☐ Smart Dairy Ltd.  ☐ Third party  ☐ Society generally

1.3 Is this a legitimate interest (legal, contractual, ethical)?
☐ Yes  ☐ No
Justification: _________________________________________________

2. NECESSITY TEST - Is processing necessary?

2.1 Is processing necessary to achieve the objective?
☐ Yes  ☐ No

2.2 Is there a less intrusive way to achieve the same result?
☐ No alternatives identified
☐ Alternatives considered: ____________________________________
  Why not suitable: __________________________________________

3. BALANCING TEST - Do individual rights override?

3.1 What is the nature of the personal data?
☐ Public data  ☐ Private data  ☐ Special category data

3.2 Is data subject in a vulnerable position?
☐ No  ☐ Yes (children, employees, etc.)

3.3 Would data subjects reasonably expect this processing?
☐ Yes - clear relationship/context
☐ Maybe - related to service but not explicit
☐ No - would be unexpected

3.4 What is the impact on individuals?
☐ Minimal - no adverse effects
☐ Limited - minor inconvenience
☐ Significant - financial, reputational, or other harm

3.5 Are there safeguards in place?
☐ Opt-out mechanism  ☐ Data minimization  ☐ Anonymization
☐ Aggregation  ☐ Limited retention  ☐ Other: _______________

4. DECISION

☐ Processing APPROVED with described safeguards
☐ Processing APPROVED with additional conditions: _____________
☐ Processing REJECTED - cannot be justified

5. REVIEW

Review Date: ___________

Approved by DPO: ___________________ Date: ___________
```

---

### Appendix G: Glossary of Terms

| Term | Definition |
|------|------------|
| **BCR** | Binding Corporate Rules - internal rules for international data transfers within corporate groups |
| **Biometric Data** | Personal data resulting from specific technical processing relating to physical, physiological, or behavioral characteristics |
| **Consent** | Freely given, specific, informed, and unambiguous indication of data subject's wishes |
| **Controller** | Natural or legal person determining purposes and means of processing |
| **DPIA** | Data Protection Impact Assessment |
| **DPO** | Data Protection Officer |
| **DSR** | Data Subject Rights Request |
| **Encryption** | Process of encoding data to prevent unauthorized access |
| **GDPR** | General Data Protection Regulation |
| **Hashing** | Converting data to fixed-size value, one-way function |
| **ICO** | Information Commissioner's Office (UK supervisory authority) |
| **Personal Data** | Any information relating to an identified or identifiable natural person |
| **Processor** | Natural or legal person processing personal data on behalf of controller |
| **Profiling** | Automated processing to evaluate personal aspects |
| **Pseudonymization** | Processing such that data can no longer be attributed without additional information |
| **ROPA** | Record of Processing Activities |
| **SCCs** | Standard Contractual Clauses |
| **Special Categories** | Sensitive data (health, biometrics, religion, etc.) requiring additional protection |
| **Supervisory Authority** | Independent public authority responsible for monitoring GDPR application |
| **TIA** | Transfer Impact Assessment |

---

### Appendix H: Reference Documents

| Document ID | Title | Location |
|-------------|-------|----------|
| F-001 | Information Security Policy | Security Documentation |
| F-002 | Access Control Policy | Security Documentation |
| F-003 | Incident Response Plan | Security Documentation |
| F-004 | Business Continuity Plan | Security Documentation |
| F-005 | GDPR & Data Privacy Compliance | This Document |
| F-006 | Cookie Policy | Website Legal Pages |
| F-007 | Terms of Service | Website Legal Pages |
| F-008 | Acceptable Use Policy | Security Documentation |
| T-001 | DPO Job Description | HR Documentation |
| T-002 | Privacy Training Materials | Training Portal |

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Security Lead | _________________ | _______ |
| Owner | DPO | _________________ | _______ |
| Reviewer | Legal Counsel | _________________ | _______ |
| Approved | CTO | _________________ | _______ |
| Approved | CEO | _________________ | _______ |

---

**END OF DOCUMENT F-005**

---

*This document is the property of Smart Dairy Ltd. and contains confidential information. Unauthorized distribution is prohibited.*
