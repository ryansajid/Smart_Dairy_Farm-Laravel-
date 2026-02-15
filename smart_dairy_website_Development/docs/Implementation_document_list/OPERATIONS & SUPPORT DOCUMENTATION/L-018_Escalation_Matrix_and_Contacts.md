# L-018: Escalation Matrix & Contacts

## Smart Dairy Ltd. - Smart Web Portal System

---

## Document Control

| Field | Details |
|-------|---------|
| **Document ID** | L-018 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Operations Manager |
| **Owner** | Operations Manager |
| **Reviewer** | IT Director |
| **Classification** | Internal Use Only |
| **Status** | Draft |

---

## Document History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-31 | Operations Manager | Initial document creation |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Escalation Levels](#2-escalation-levels)
3. [Escalation Triggers](#3-escalation-triggers)
4. [Escalation Matrix by Issue Type](#4-escalation-matrix-by-issue-type)
5. [Contact Information](#5-contact-information)
6. [Communication Methods](#6-communication-methods)
7. [Escalation Procedures](#7-escalation-procedures)
8. [Vendor Escalation](#8-vendor-escalation)
9. [After-Hours Escalation](#9-after-hours-escalation)
10. [Appendices](#10-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document establishes the formal escalation matrix and contact procedures for Smart Dairy Ltd.'s Smart Web Portal System. It provides clear guidelines for:

- Routing technical issues through appropriate support tiers
- Ensuring timely resolution of critical incidents
- Maintaining accountability throughout the escalation chain
- Facilitating communication between internal teams and external vendors
- Minimizing business impact through structured response protocols

### 1.2 Scope

This escalation matrix applies to:

- **Systems Covered:**
  - Smart Dairy Web Portal (www.smartdairybd.com)
  - Odoo ERP System
  - AWS Cloud Infrastructure
  - Payment Gateway Systems (bKash, Nagad, SSLCommerz)
  - Database Systems (PostgreSQL, Redis)
  - Network and Security Infrastructure
  - Third-party integrations and APIs

- **Personnel Covered:**
  - IT Operations Team
  - Development Team
  - Farm Operations Staff
  - Customer Support Team
  - Management Personnel
  - External Vendors and Partners

### 1.3 Document Objectives

1. Define clear escalation paths for different types of incidents
2. Establish response time expectations at each level
3. Provide comprehensive contact information for all support tiers
4. Standardize communication protocols during escalations
5. Ensure business continuity through rapid incident resolution

### 1.4 Related Documents

- L-017: Incident Management Procedures
- L-019: Disaster Recovery Plan
- L-020: Business Continuity Plan
- L-021: SLA Definitions and Monitoring

---

## 2. Escalation Levels

### 2.1 Escalation Framework Overview

```
┌─────────────────────────────────────────────────────────────────────────┐
│                    ESCALATION PYRAMID                                   │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│                    ┌─────────────────┐                                  │
│                    │   Level 4       │  Executive                       │
│                    │   Executive     │  Response: 1 hour                │
│                    └────────┬────────┘  Authority: CEO/MD               │
│                             │                                          │
│              ┌──────────────┴──────────────┐                           │
│              ▼                              ▼                           │
│     ┌─────────────────┐          ┌─────────────────┐                   │
│     │   Level 3       │          │   Vendor        │                   │
│     │   IT Management │◄────────►│   Escalation    │                   │
│     │   Response: 30m │          │   Response: Varies                   │
│     │   Authority: IT Director    │   Authority: Vendor L3              │
│     └────────┬────────┘          └─────────────────┘                   │
│              │                                                          │
│     ┌────────┴────────┐                                                 │
│     ▼                 ▼                                                 │
│ ┌─────────────┐  ┌─────────────┐                                        │
│ │  Level 2    │  │  Database   │                                        │
│ │  Technical  │  │  Admin      │                                        │
│ │  Specialist │  │  Security   │                                        │
│ │  Response: 15m    │  Response: 20m                                   │
│ │  Authority: Senior Engineer   │  Authority: DBA/SecOps                │
│ └──────┬──────┘  └──────┬──────┘                                        │
│        │                │                                               │
│ ┌──────┴────────────────┴──────┐                                        │
│ ▼                              ▼                                        │
│ ┌─────────────────────────────────────┐                                 │
│ │          Level 1                    │                                 │
│ │   First-Line Support                │                                 │
│ │   Response: 5 minutes               │                                 │
│ │   Authority: IT Support Staff       │                                 │
│ └─────────────────────────────────────┘                                 │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Level 1: First-Line Support

| Attribute | Details |
|-----------|---------|
| **Role Name** | First-Line Support / Help Desk |
| **Response Time** | 5 minutes (critical), 15 minutes (high) |
| **Resolution Target** | 80% of issues resolved at this level |
| **Availability** | 24/7 (on-call rotation) |
| **Authority** | Tier 1 troubleshooting, basic incident logging |

**Responsibilities:**
- Initial incident triage and classification
- Basic troubleshooting and user support
- Password resets and account unlocks
- Standard operational queries
- Incident logging and ticket creation
- Initial communication with affected users

**Escalation Criteria:**
- Issue cannot be resolved within 15 minutes
- Technical expertise beyond Level 1 required
- System-wide impact identified
- Security-related concerns
- Database or infrastructure issues

**Personnel:**
| Name | Position | Primary Contact | Shift |
|------|----------|-----------------|-------|
| TBD | IT Support Officer | +880 XXXX-XXXXXX | Day Shift |
| TBD | IT Support Officer | +880 XXXX-XXXXXX | Night Shift |
| On-Call | Rotating Support | +880 XXXX-XXXXXX | After Hours |

### 2.3 Level 2: Technical Specialists

| Attribute | Details |
|-----------|---------|
| **Role Name** | Technical Specialists |
| **Response Time** | 15 minutes (critical), 30 minutes (high) |
| **Resolution Target** | 70% of escalated issues resolved |
| **Availability** | Business hours + on-call for critical |
| **Authority** | System administration, application support |

**Specialist Areas:**

#### 2.3.1 Application Specialist (Odoo)
- Odoo ERP configuration and troubleshooting
- Module customization issues
- Integration failures
- Report generation problems

#### 2.3.2 Infrastructure Specialist (AWS)
- EC2 instance management
- Load balancer configuration
- Auto-scaling issues
- Storage and backup problems

#### 2.3.3 Database Administrator
- PostgreSQL performance tuning
- Database connectivity issues
- Backup/restore operations
- Data integrity problems

#### 2.3.4 Network & Security Specialist
- Firewall configuration
- SSL certificate issues
- Network connectivity
- Security incident response

**Personnel:**
| Name | Specialization | Primary Contact | Email |
|------|----------------|-----------------|-------|
| TBD | Application Specialist | +880 XXXX-XXXXXX | appsupport@smartdairybd.com |
| TBD | AWS Infrastructure | +880 XXXX-XXXXXX | infra@smartdairybd.com |
| TBD | Database Administrator | +880 XXXX-XXXXXX | dba@smartdairybd.com |
| TBD | Security Specialist | +880 XXXX-XXXXXX | security@smartdairybd.com |

### 2.4 Level 3: IT Management

| Attribute | Details |
|-----------|---------|
| **Role Name** | IT Management |
| **Response Time** | 30 minutes (critical), 1 hour (high) |
| **Resolution Target** | Business decisions, vendor coordination |
| **Availability** | Business hours + emergency contact |
| **Authority** | Resource allocation, vendor escalation, major decisions |

**Responsibilities:**
- Coordination of complex multi-team incidents
- Vendor escalation and management
- Resource allocation for incident resolution
- Communication with senior management
- Business impact assessment
- Decision authority for system changes during incidents

**Personnel:**
| Name | Position | Primary Contact | Email | Emergency |
|------|----------|-----------------|-------|-----------|
| TBD | IT Director | +880 XXXX-XXXXXX | it.director@smartgroup.com.bd | +880 XXXX-XXXXXX |
| TBD | Operations Manager | +880 XXXX-XXXXXX | operations@smartdairybd.com | +880 XXXX-XXXXXX |

### 2.5 Level 4: Executive

| Attribute | Details |
|-----------|---------|
| **Role Name** | Executive Response |
| **Response Time** | 1 hour (critical business impact) |
| **Resolution Target** | Strategic decisions, external communication |
| **Availability** | Emergency contact basis |
| **Authority** | Business continuity decisions, external communications |

**Trigger Conditions:**
- Revenue impact exceeding BDT 500,000 per hour
- Data breach or security incident
- Complete system outage exceeding 4 hours
- Regulatory compliance issues
- Media or public attention incidents

**Personnel:**
| Name | Position | Primary Contact | Emergency Contact |
|------|----------|-----------------|-------------------|
| Mohammad Zahirul Islam | Managing Director | +880 XXXX-XXXXXX | +880 XXXX-XXXXXX |
| Mohd. Mazharul Islam | Chairman | +880 XXXX-XXXXXX | +880 XXXX-XXXXXX |

---

## 3. Escalation Triggers

### 3.1 Time-Based Triggers

| Severity | Level 1 Response | Escalate to L2 | Escalate to L3 | Escalate to L4 |
|----------|------------------|----------------|----------------|----------------|
| **P1 - Critical** | 5 min | 15 min | 30 min | 1 hour |
| **P2 - High** | 15 min | 30 min | 1 hour | 2 hours |
| **P3 - Medium** | 30 min | 2 hours | 4 hours | 8 hours |
| **P4 - Low** | 1 hour | 4 hours | 8 hours | 24 hours |

**Time-Based Escalation Rules:**

1. **Automatic Escalation:**
   - System automatically notifies next level if no acknowledgment within threshold
   - SMS alerts sent to backup personnel if primary doesn't respond
   - Escalation timer pauses only when incident is acknowledged

2. **Working Hours Consideration:**
   - Business Hours: 9:00 AM - 6:00 PM (Sunday - Thursday)
   - Friday/Saturday: Weekend rates apply (2x response time)
   - Public Holidays: Critical issues only, 4x response time

3. **Clock Stoppage Conditions:**
   - Waiting for user input
   - Vendor response pending
   - Scheduled maintenance windows
   - Force majeure events

### 3.2 Severity-Based Triggers

#### Priority 1 (P1) - Critical

**Definition:** Complete service outage affecting all users or critical business functions

**Examples:**
- Website completely inaccessible
- Payment processing down
- Database corruption or failure
- Security breach in progress
- Data center connectivity loss

**Immediate Actions:**
- Auto-escalate to Level 2 within 5 minutes
- Conference bridge activated
- Executive notification within 30 minutes
- Vendor escalation initiated immediately

#### Priority 2 (P2) - High

**Definition:** Major functionality impaired, significant business impact

**Examples:**
- Order processing delays (>30 minutes)
- Partial system performance degradation
- Specific module unavailable (e.g., reporting)
- Intermittent payment failures
- Backup failures

**Response Protocol:**
- Level 1 attempts resolution for 15 minutes
- Escalate to Level 2 if unresolved
- Management notification if >1 hour

#### Priority 3 (P3) - Medium

**Definition:** Minor functionality affected, workaround available

**Examples:**
- Non-critical feature unavailable
- Performance slower than usual but acceptable
- Minor UI/display issues
- Single user problems

**Response Protocol:**
- Standard queue processing
- Escalate if workaround fails

#### Priority 4 (P4) - Low

**Definition:** Minimal impact, cosmetic issues, or enhancement requests

**Examples:**
- Spelling errors on website
- Cosmetic UI improvements
- Documentation requests
- Feature enhancement suggestions

### 3.3 Complexity-Based Triggers

| Complexity Factor | Escalation Level | Reason |
|-------------------|------------------|--------|
| Multi-system impact | L3 | Requires coordination across teams |
| Database corruption | L2 (DBA) + L3 | Specialized recovery needed |
| Security incident | L2 (Security) + L3 + L4 | Compliance and legal implications |
| Vendor dependency | L3 | Vendor engagement required |
| Code deployment rollback | L2 | Technical expertise required |
| Infrastructure scaling | L2 (Infrastructure) | AWS expertise needed |
| Data migration issues | L2 (DBA) | Database expertise required |

---

## 4. Escalation Matrix by Issue Type

### 4.1 Infrastructure Issues

```
┌────────────────────────────────────────────────────────────────────────┐
│                    INFRASTRUCTURE ESCALATION PATH                      │
├────────────────────────────────────────────────────────────────────────┤
│                                                                        │
│  Issue Detected ──► L1: Helpdesk                                       │
│       │              (5 min response)                                  │
│       │                    │                                           │
│       │         ┌──────────┴──────────┐                                │
│       │         ▼                     ▼                                │
│       │    [Resolved]            [Escalate]                            │
│       │                            │                                   │
│       │                            ▼                                   │
│       │              L2: Infrastructure Specialist                     │
│       │              (15 min response)                                 │
│       │              Contact: +880 XXXX-XXXXXX                         │
│       │                    │                                           │
│       │         ┌──────────┴──────────┐                                │
│       │         ▼                     ▼                                │
│       │    [Resolved]     ┌───────[Escalate]───────┐                  │
│       │                   ▼                        ▼                  │
│       │        L2: AWS Support            L3: IT Management           │
│       │        (Vendor)                   (30 min response)           │
│       │                                   Contact: IT Director        │
│       │                                                             │
│       └──────────────────────────────────────────────────────────────►│
│                                  │                                    │
│                                  ▼                                    │
│                       ┌───────────────────┐                          │
│                       ▼                   ▼                          │
│                [Resolved]          [Critical Outage]                 │
│                                         │                            │
│                                         ▼                            │
│                                  L4: Executive                       │
│                                  (1 hour response)                   │
│                                                                        │
└────────────────────────────────────────────────────────────────────────┘
```

| Issue Type | Level 1 | Level 2 | Level 3 | Level 4 | Vendor |
|------------|---------|---------|---------|---------|--------|
| Server Down | Acknowledge, basic checks | Infrastructure Specialist | IT Director | MD | AWS Support |
| Load Balancer Issue | Monitor, restart attempt | Infrastructure Specialist | IT Director | MD | AWS Support |
| SSL Certificate Expired | Notify, temporary bypass | Security + Infrastructure | IT Director | - | SSL Provider |
| Disk Space Critical | Monitor, cleanup temp | Infrastructure Specialist | IT Director | - | AWS Support |
| Network Connectivity | Basic diagnostics | Infrastructure Specialist | IT Director | MD | ISP |

### 4.2 Application Issues

| Issue Type | Level 1 | Level 2 | Level 3 | Level 4 | Vendor |
|------------|---------|---------|---------|---------|--------|
| Odoo Crash | Restart attempt, log collection | Application Specialist | IT Director | MD | Odoo Partner |
| Web Portal Error | Basic troubleshooting | Application Specialist | IT Director | MD | Development Team |
| Integration Failure | Verify endpoints | Application Specialist | IT Director | - | API Provider |
| Report Generation Failed | Re-run attempt | Application Specialist | IT Director | - | Odoo Partner |
| Mobile App Issue | User guidance | Application Specialist | IT Director | - | App Developer |

### 4.3 Security Issues

| Issue Type | Level 1 | Level 2 | Level 3 | Level 4 | Vendor |
|------------|---------|---------|---------|---------|--------|
| Suspected Breach | Isolate, preserve logs | Security Specialist | IT Director + Legal | MD/Chairman | Security Vendor |
| DDoS Attack | Monitor, enable protection | Security + Infrastructure | IT Director | MD | AWS Shield |
| Malware Detection | Quarantine affected systems | Security Specialist | IT Director | - | Antivirus Vendor |
| Unauthorized Access | Disable accounts | Security Specialist | IT Director + HR | MD | - |
| SSL/TLS Issues | Document symptoms | Security Specialist | IT Director | - | Certificate Authority |

### 4.4 Database Issues

| Issue Type | Level 1 | Level 2 | Level 3 | Level 4 | Vendor |
|------------|---------|---------|---------|---------|--------|
| Database Down | Check connectivity | Database Administrator | IT Director | MD | AWS RDS Support |
| Slow Queries | Monitor, document | Database Administrator | IT Director | - | - |
| Backup Failure | Retry, log error | Database Administrator | IT Director | - | AWS Support |
| Data Corruption | Preserve state | Database Administrator | IT Director | MD | AWS Premium Support |
| Replication Lag | Monitor | Database Administrator | IT Director | - | - |

### 4.5 Network Issues

| Issue Type | Level 1 | Level 2 | Level 3 | Level 4 | Vendor |
|------------|---------|---------|---------|---------|--------|
| Complete Internet Down | Check router status | Infrastructure Specialist | IT Director | MD | ISP |
| Slow Internet | Speed test, document | Infrastructure Specialist | IT Director | - | ISP |
| VPN Issues | Credential verification | Infrastructure Specialist | IT Director | - | - |
| DNS Resolution | Test alternate DNS | Infrastructure Specialist | IT Director | - | DNS Provider |
| Firewall Blocking | Review rules | Security Specialist | IT Director | - | - |

---

## 5. Contact Information

### 5.1 Internal Contacts Directory

#### Level 1 - First-Line Support

| Name | Role | Phone | Email | WhatsApp | Availability |
|------|------|-------|-------|----------|--------------|
| IT Support Hotline | General Support | +880 XXXX-XXXXXX | support@smartdairybd.com | +880 XXXX-XXXXXX | 24/7 |
| Farm IT Support | Farm Location | +880 XXXX-XXXXXX | farm.it@smartdairybd.com | +880 XXXX-XXXXXX | 6 AM - 10 PM |
| Customer Care | Customer Issues | +880 XXXX-XXXXXX | care@smartdairybd.com | +880 XXXX-XXXXXX | 9 AM - 9 PM |

#### Level 2 - Technical Specialists

| Name | Specialization | Primary Phone | Secondary Phone | Email | WhatsApp |
|------|----------------|---------------|-----------------|-------|----------|
| TBD | Odoo Application Specialist | +880 1XXX-XXXXXX | +880 1XXX-XXXXXX | appsupport@smartdairybd.com | +880 1XXX-XXXXXX |
| TBD | AWS Infrastructure Lead | +880 1XXX-XXXXXX | +880 1XXX-XXXXXX | infra@smartdairybd.com | +880 1XXX-XXXXXX |
| TBD | Database Administrator | +880 1XXX-XXXXXX | +880 1XXX-XXXXXX | dba@smartdairybd.com | +880 1XXX-XXXXXX |
| TBD | Security Specialist | +880 1XXX-XXXXXX | +880 1XXX-XXXXXX | security@smartdairybd.com | +880 1XXX-XXXXXX |
| TBD | Network Administrator | +880 1XXX-XXXXXX | +880 1XXX-XXXXXX | network@smartdairybd.com | +880 1XXX-XXXXXX |
| TBD | Web Developer Lead | +880 1XXX-XXXXXX | +880 1XXX-XXXXXX | devlead@smartdairybd.com | +880 1XXX-XXXXXX |

#### Level 3 - IT Management

| Name | Position | Office Phone | Mobile | Email | Emergency Contact |
|------|----------|--------------|--------|-------|-------------------|
| TBD | IT Director | +880-2-XXXX-XXXX | +880 1XXX-XXXXXX | it.director@smartgroup.com.bd | +880 1XXX-XXXXXX |
| TBD | Operations Manager | +880-2-XXXX-XXXX | +880 1XXX-XXXXXX | operations@smartdairybd.com | +880 1XXX-XXXXXX |
| TBD | IT Manager | +880-2-XXXX-XXXX | +880 1XXX-XXXXXX | it.manager@smartdairybd.com | +880 1XXX-XXXXXX |

#### Level 4 - Executive

| Name | Position | Office | Mobile | Email | Emergency |
|------|----------|--------|--------|-------|-----------|
| Mohammad Zahirul Islam | Managing Director | +880-2-XXXX-XXXX | +880 1XXX-XXXXXX | md@smartgroup.com.bd | +880 1XXX-XXXXXX |
| Mohd. Mazharul Islam | Chairman | +880-2-XXXX-XXXX | +880 1XXX-XXXXXX | chairman@smartgroup.com.bd | +880 1XXX-XXXXXX |

### 5.2 Vendor Contacts

#### AWS (Amazon Web Services)

| Support Type | Contact Method | Details | Availability |
|--------------|----------------|---------|--------------|
| General Support | AWS Console | https://console.aws.amazon.com/support | 24/7 |
| Phone Support | +1-206-266-4064 | Account required | 24/7 |
| Business Support | AWS Console | Case severity: High, Urgent | 24/7 |
| Enterprise Support | Dedicated TAM | Assigned Technical Account Manager | 24/7 |
| Emergency | AWS Emergency | Via AWS Console - Critical severity | 24/7 |

**AWS Account Details:**
- Account ID: [REDACTED - See Appendix B]
- Support Plan: Business/Enterprise
- TAM Name: [To be assigned]
- TAM Email: [To be assigned]

#### Odoo Partner

| Contact Type | Name/Department | Phone | Email | Availability |
|--------------|-----------------|-------|-------|--------------|
| General Support | Odoo Support Team | +32 X XXX XX XX | support@odoo.com | 24/7 (Online) |
| Partner Support | Local Partner | +880 XXXX-XXXXXX | partner@example.com | Business Hours |
| Technical Escalation | Technical Team | +32 X XXX XX XX | technical@odoo.com | Business Hours |
| Account Manager | Account Team | +32 X XXX XX XX | account@odoo.com | Business Hours |

#### Payment Gateways

##### bKash

| Contact Type | Details | Availability |
|--------------|---------|--------------|
| Merchant Support | support@bkash.com | Business Hours |
| Technical Integration | integration@bkash.com | Business Hours |
| Emergency Hotline | +880 XXXX-XXXXXX | 24/7 |
| Account Manager | [To be assigned] | Business Hours |

##### Nagad

| Contact Type | Details | Availability |
|--------------|---------|--------------|
| Merchant Support | merchant@nagad.com.bd | Business Hours |
| Technical Support | tech@nagad.com.bd | Business Hours |
| Emergency Contact | +880 XXXX-XXXXXX | 24/7 |

##### SSLCommerz

| Contact Type | Details | Availability |
|--------------|---------|--------------|
| Technical Support | support@sslcommerz.com | 24/7 |
| Integration Support | integration@sslcommerz.com | Business Hours |
| Emergency Contact | +880 XXXX-XXXXXX | 24/7 |

#### ISP (Internet Service Provider)

| ISP Name | Support Contact | Emergency Contact | Account Number |
|----------|-----------------|-------------------|----------------|
| [Primary ISP] | +880 XXXX-XXXXXX | +880 XXXX-XXXXXX | [To be added] |
| [Backup ISP] | +880 XXXX-XXXXXX | +880 XXXX-XXXXXX | [To be added] |

#### Domain & DNS Provider

| Provider | Support Type | Contact | Availability |
|----------|--------------|---------|--------------|
| [Registrar] | Domain Issues | support@registrar.com | 24/7 |
| Cloudflare | DNS/CDN | https://support.cloudflare.com | 24/7 |
| Cloudflare | Emergency | +1-650-xxx-xxxx | 24/7 |

### 5.3 Emergency Contacts

#### Internal Emergency Chain

```
Incident Detected
       │
       ▼
┌──────────────────┐
│ On-Call Engineer │ ──► No response in 5 min
│ +880 XXXX-XXXXXX │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│ IT Manager       │ ──► No response in 10 min
│ +880 XXXX-XXXXXX │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│ IT Director      │ ──► No response in 15 min
│ +880 XXXX-XXXXXX │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│ Managing Director│
│ +880 XXXX-XXXXXX │
└──────────────────┘
```

#### External Emergency Services

| Service | Contact | Purpose |
|---------|---------|---------|
| Bangladesh CERT | cert@bcc.gov.bd | Cybersecurity incidents |
| BTRC (Telecom) | info@btrc.gov.bd | ISP-related issues |
| Police Cyber Crime | [Local number] | Criminal cyber activity |
| Fire Service | 9555555 | Data center emergencies |
| Ambulance | 199 | Medical emergencies at facility |

---

## 6. Communication Methods

### 6.1 Phone

**Primary Communication for:**
- P1 Critical incidents (immediate voice contact)
- Escalation notifications
- Emergency conference bridges
- Vendor urgent communications

**Phone Protocol:**
1. Call primary contact number
2. If no answer within 3 rings, call secondary
3. Leave voicemail with incident reference number
4. Send follow-up SMS/WhatsApp
5. Call next person in chain if no response in threshold time

**Conference Bridge:**
- Primary Bridge: [To be configured]
- Backup Bridge: [To be configured]
- PIN: [To be configured]

### 6.2 Email

**Email Distribution Lists:**

| List Name | Members | Purpose |
|-----------|---------|---------|
| support-team@smartdairybd.com | All L1, L2 staff | General support communications |
| escalation@smartdairybd.com | L2, L3, L4 | Escalation notifications |
| critical-incidents@smartdairybd.com | All IT + Management | P1/P2 incidents |
| on-call@smartdairybd.com | Current on-call engineer | Direct on-call contact |
| vendors@smartdairybd.com | Vendor contacts | Vendor communications |

**Email Templates:**

See Appendix C for standardized email templates including:
- Initial escalation notification
- Status update template
- Resolution notification
- Executive summary template

### 6.3 SMS/WhatsApp

**Use Cases:**
- Initial alert for critical incidents
- Escalation reminders
- Out-of-hours contact
- Quick status updates

**WhatsApp Business Group:**
- Group Name: "Smart Dairy IT Emergency"
- Members: All IT staff, Management
- Purpose: Immediate alerts and coordination

**SMS Gateway:**
- Provider: [To be configured]
- API Endpoint: [To be configured]
- Rate Limit: [To be configured]

### 6.4 PagerDuty

**PagerDuty Configuration:**

| Service | Escalation Policy | On-Call Schedule |
|---------|-------------------|------------------|
| Web Portal | Web Team Escalation | 24/7 Rotation |
| Infrastructure | Infrastructure Escalation | 24/7 Rotation |
| Database | DBA Escalation | Business Hours + On-Call |
| Security | Security Escalation | 24/7 Rotation |
| Payment Systems | Payment Escalation | 24/7 Rotation |

**Escalation Policies:**

```
Web Portal Escalation Policy:
├── Tier 1: Primary On-Call Engineer (5 min)
├── Tier 2: Secondary On-Call Engineer (5 min)
├── Tier 3: IT Manager (10 min)
├── Tier 4: IT Director (15 min)
└── Tier 5: Managing Director (30 min)
```

**Notification Channels:**
- Mobile Push
- SMS
- Phone Call
- Email

---

## 7. Escalation Procedures

### 7.1 How to Escalate

#### Step-by-Step Escalation Process

```
┌─────────────────────────────────────────────────────────────────────┐
│                    ESCALATION PROCEDURE FLOW                        │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  1. IDENTIFY                                                        │
│     ├── Assess incident severity (P1-P4)                            │
│     ├── Determine issue type (Infrastructure/Application/etc.)      │
│     └── Check current assignment and time elapsed                   │
│                                                                     │
│  2. PREPARE                                                         │
│     ├── Gather incident ticket number                               │
│     ├── Collect summary of actions taken                            │
│     ├── Document business impact                                    │
│     └── Prepare relevant logs/screenshots                           │
│                                                                     │
│  3. NOTIFY                                                          │
│     ├── Contact next level per escalation matrix                    │
│     ├── Use appropriate communication method (Phone for P1/P2)      │
│     └── Send written summary via email/SMS                          │
│                                                                     │
│  4. HANDOVER                                                        │
│     ├── Transfer ticket ownership in system                         │
│     ├── Provide detailed briefing to receiving party                │
│     ├── Update escalation timestamp                                 │
│     └── Remain available for 15 minutes for questions               │
│                                                                     │
│  5. MONITOR                                                         │
│     ├── Track progress at new level                                 │
│     ├── Prepare for next escalation if needed                       │
│     └── Document any additional learnings                           │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

#### Escalation Checklist

**Before Escalating:**
- [ ] Incident properly classified and documented
- [ ] All troubleshooting steps taken at current level recorded
- [ ] Business impact clearly stated
- [ ] Time threshold for current level exceeded (or severity requires immediate escalation)
- [ ] Correct escalation path verified
- [ ] Contact information for next level confirmed

**During Escalation:**
- [ ] Initial contact made via appropriate channel
- [ ] Incident summary provided verbally
- [ ] Written summary sent within 10 minutes
- [ ] Ticket ownership transferred
- [ ] All relevant logs/artifacts shared
- [ ] Acknowledgment received from next level

**After Escalation:**
- [ ] Escalation logged in incident ticket
- [ ] Management notified (for P1/P2)
- [ ] Status page updated if customer-impacting
- [ ] Next escalation timer started

### 7.2 What to Include in Escalation

#### Required Information

**1. Incident Identification:**
```
Incident ID: INC-2026-XXXX
Severity: [P1/P2/P3/P4]
Detected: [Date/Time]
Escalated From: [Level X]
Escalated To: [Level Y]
Escalation Time: [Date/Time]
```

**2. Issue Summary:**
```
Title: [Brief description]
System Affected: [Specific system/component]
Symptoms: [What is happening]
Expected Behavior: [What should happen]
Actual Behavior: [What is actually happening]
```

**3. Business Impact:**
```
Users Affected: [Number/Percentage]
Revenue Impact: [BDT per hour if known]
SLA Impact: [Whether SLA is at risk/breached]
Regulatory Impact: [If applicable]
Reputation Impact: [If public/customer facing]
```

**4. Actions Taken:**
```
Investigation Steps:
1. [Step taken and result]
2. [Step taken and result]
...

Resolution Attempts:
1. [Attempt made and outcome]
2. [Attempt made and outcome]
...
```

**5. Current Status:**
```
Current State: [In Progress/Blocked/Waiting]
Next Planned Action: [What will be tried next]
Additional Resources Needed: [What is required]
Estimated Time to Resolution: [If known]
```

**6. Attachments:**
- Error logs
- Screenshots
- Monitoring graphs
- Related incident numbers

### 7.3 Tracking Escalations

#### Escalation Tracking Log

| Incident ID | Escalation Time | From Level | To Level | Reason | Status | Resolved Time |
|-------------|-----------------|------------|----------|--------|--------|---------------|
| INC-XXXX | YYYY-MM-DD HH:MM | L1 | L2 | Time exceeded | Resolved | YYYY-MM-DD HH:MM |
| INC-XXXX | YYYY-MM-DD HH:MM | L2 | L3 | Complexity | In Progress | - |

#### Metrics Tracked

1. **Escalation Rate by Level:**
   - L1 to L2: Target <20% of incidents
   - L2 to L3: Target <15% of L2 incidents
   - L3 to L4: Target <5% of L3 incidents

2. **Escalation Time:**
   - Average time before escalation
   - Escalations within SLA vs. overdue

3. **Escalation Effectiveness:**
   - Resolution rate at each level
   - Re-escalation rate (escalations that come back)

4. **Escalation Quality:**
   - Completeness of escalation information
   - Time to acknowledge escalations
   - Customer satisfaction post-escalation

---

## 8. Vendor Escalation

### 8.1 AWS Escalation

#### AWS Support Severity Levels

| Severity | Definition | Response Time | Escalation Trigger |
|----------|------------|---------------|-------------------|
| Critical | Production system down | 15 minutes | Any production outage |
| Urgent | Production system impaired | 1 hour | Major performance degradation |
| High | Production system issue | 4 hours | Non-critical issues |
| Normal | General guidance | 24 hours | Questions, planning |
| Low | Development questions | 24 hours | Pre-production issues |

#### AWS Escalation Procedure

```
AWS Issue Detected
       │
       ▼
┌──────────────────┐
│ Check AWS Status │──► AWS-wide issue?──► Monitor status page
│ Page             │
└────────┬─────────┘
         │ No
         ▼
┌──────────────────┐
│ Open AWS Support │──► Severity: Critical/Urgent
│ Case in Console  │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│ Document Case ID │
│ in Incident Ticket│
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│ If no response   │
│ within SLA:      │
│ Call AWS Support │──► +1-206-266-4064
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│ If still blocked:│
│ Request TAM      │
│ involvement      │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│ If business      │
│ critical:        │
│ Escalate to      │──► IT Director involved
│ Account Team     │
└──────────────────┘
```

**AWS Emergency Escalation Contacts:**
- General Support: +1-206-266-4064
- Enterprise Support: Via TAM
- Account Manager: [To be assigned]

### 8.2 Odoo Partner Escalation

#### Odoo Support Levels

| Level | Scope | Contact | Response Time |
|-------|-------|---------|---------------|
| L1 | General queries | support@odoo.com | 24 hours |
| L2 | Technical issues | technical@odoo.com | 8 business hours |
| L3 | Partner escalation | partner@odoo.com | 4 business hours |
| L4 | Critical/Outage | +32 X XXX XX XX | 2 hours |

#### Odoo Escalation Path

```
Odoo Issue Detected
       │
       ▼
┌──────────────────┐
│ Attempt in-house │
│ resolution       │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│ Check Odoo Status│──► Known issue?──► Follow advisory
│ Page             │
└────────┬─────────┘
         │ No
         ▼
┌──────────────────┐
│ Contact Local    │──► Business hours support
│ Partner          │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│ If critical or   │
│ partner unable:  │
│ Contact Odoo     │──► support@odoo.com
│ Direct           │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│ If production    │
│ down:            │
│ Phone escalation │──► +32 X XXX XX XX
└──────────────────┘
```

### 8.3 Payment Gateway Escalation

#### bKash Escalation

| Priority | Issue Type | Contact | Response |
|----------|------------|---------|----------|
| P1 | Complete payment failure | Emergency Hotline | 30 min |
| P2 | Intermittent failures | Merchant Support | 4 hours |
| P3 | Reconciliation issues | Account Manager | 1 business day |

**Escalation Path:**
1. Technical Support (integration@bkash.com)
2. Merchant Support Manager
3. Emergency Hotline (+880 XXXX-XXXXXX)
4. Account Director

#### Nagad Escalation

| Priority | Issue Type | Contact | Response |
|----------|------------|---------|----------|
| P1 | Payment system down | Emergency | 1 hour |
| P2 | Transaction failures | Tech Support | 4 hours |
| P3 | Settlement delays | Merchant Support | 1 business day |

#### SSLCommerz Escalation

| Priority | Issue Type | Contact | Response |
|----------|------------|---------|----------|
| P1 | Gateway down | 24/7 Emergency | 30 min |
| P2 | Integration issues | Technical Support | 4 hours |
| P3 | Reporting issues | Support | 1 business day |

### 8.4 ISP Escalation

#### ISP Escalation Matrix

| Level | Contact | Response Time | Escalation Criteria |
|-------|---------|---------------|---------------------|
| L1 | Technical Support | 1 hour | Initial report |
| L2 | Network Operations Center | 30 min | If L1 cannot resolve |
| L3 | Account Manager | 1 hour | If outage continues |
| L4 | Regional Manager | 30 min | For extended outages |

**ISP Escalation Procedure:**
1. Call ISP support with circuit ID
2. Request ticket number
3. Set escalation timer
4. If unresolved in SLA, request NOC
5. For >2 hour outages, involve Account Manager
6. For >4 hour outages, executive escalation

---

## 9. After-Hours Escalation

### 9.1 On-Call Schedule

#### Weekly On-Call Rotation

| Week | Primary On-Call | Secondary On-Call | Contact Numbers |
|------|-----------------|-------------------|-----------------|
| W1 | [Engineer A] | [Engineer B] | Primary: +880 XXXX-XXXXXX |
|    |                |                  | Secondary: +880 XXXX-XXXXXX |
| W2 | [Engineer B] | [Engineer C] | Primary: +880 XXXX-XXXXXX |
|    |                |                  | Secondary: +880 XXXX-XXXXXX |
| W3 | [Engineer C] | [Engineer D] | Primary: +880 XXXX-XXXXXX |
|    |                |                  | Secondary: +880 XXXX-XXXXXX |
| W4 | [Engineer D] | [Engineer A] | Primary: +880 XXXX-XXXXXX |
|    |                |                  | Secondary: +880 XXXX-XXXXXX |

#### Weekend Coverage

| Day | Day Coverage | Night Coverage |
|-----|--------------|----------------|
| Friday | [Engineer X] | [Engineer Y] |
| Saturday | [Engineer Y] | [Engineer Z] |

### 9.2 After-Hours Contact Protocol

```
After-Hours Incident Reported
             │
             ▼
┌──────────────────────────┐
│ PageDuty Auto-Notifies   │
│ Primary On-Call          │
└────────────┬─────────────┘
             │
     ┌───────┴───────┐
     │               │
     ▼               ▼
┌─────────┐     ┌─────────┐
│Acknowledged│  │No Response│
│in 5 min │     │in 5 min   │
└────┬────┘     └────┬────┘
     │               │
     ▼               ▼
┌─────────┐     ┌─────────┐
│ Engineer│     │ Page    │
│ Handles │     │Secondary│
│ Incident│     │ On-Call │
└─────────┘     └────┬────┘
                     │
             ┌───────┴───────┐
             │               │
             ▼               ▼
        ┌─────────┐     ┌─────────┐
        │Acknowledged│  │No Response│
        │in 5 min │     │in 5 min   │
        └────┬────┘     └────┬────┘
             │               │
             ▼               ▼
        ┌─────────┐     ┌─────────┐
        │ Engineer│     │ Page IT │
        │ Handles │     │ Manager │
        │ Incident│     │         │
        └─────────┘     └─────────┘
```

### 9.3 After-Hours Severity Handling

| Severity | Action | Notification |
|----------|--------|--------------|
| P1 - Critical | Immediate page, voice call | Primary → Secondary → Manager → Director |
| P2 - High | Page with voice call backup | Primary → Secondary → Manager |
| P3 - Medium | Page only, queue for business hours | Primary only |
| P4 - Low | Email only, business hours response | Email to on-call |

### 9.4 On-Call Compensation

| Day Type | Compensation |
|----------|--------------|
| Weekday On-Call | [To be defined per company policy] |
| Weekend On-Call | [To be defined per company policy] |
| Holiday On-Call | [To be defined per company policy] |
| Incident Response | [To be defined per company policy] |

### 9.5 On-Call Responsibilities

**Primary On-Call:**
- Answer pages within 5 minutes
- Acknowledge incidents in system
- Attempt resolution or escalate
- Remain available until resolved or handed off
- Document all actions taken

**Secondary On-Call:**
- Available if primary doesn't respond
- Assist primary if additional help needed
- Take over if primary becomes unavailable
- Shadow for complex incidents

---

## 10. Appendices

### Appendix A: Complete Contact Directory

#### A.1 Internal Contacts Summary

| Role | Name | Phone | Email | WhatsApp | Emergency |
|------|------|-------|-------|----------|-----------|
| **Executive** |||||
| Chairman | Mohd. Mazharul Islam | TBD | chairman@smartgroup.com.bd | TBD | TBD |
| Managing Director | Mohammad Zahirul Islam | TBD | md@smartgroup.com.bd | TBD | TBD |
| **Management** |||||
| IT Director | [TBD] | TBD | it.director@smartgroup.com.bd | TBD | TBD |
| Operations Manager | [TBD] | TBD | operations@smartdairybd.com | TBD | TBD |
| IT Manager | [TBD] | TBD | it.manager@smartdairybd.com | TBD | TBD |
| **Technical Specialists** |||||
| Application Lead | [TBD] | TBD | appsupport@smartdairybd.com | TBD | - |
| Infrastructure Lead | [TBD] | TBD | infra@smartdairybd.com | TBD | - |
| Database Administrator | [TBD] | TBD | dba@smartdairybd.com | TBD | - |
| Security Specialist | [TBD] | TBD | security@smartdairybd.com | TBD | TBD |
| Network Administrator | [TBD] | TBD | network@smartdairybd.com | TBD | - |
| Development Lead | [TBD] | TBD | devlead@smartdairybd.com | TBD | - |
| **Support Staff** |||||
| IT Support Hotline | - | TBD | support@smartdairybd.com | TBD | - |
| Farm IT Support | [TBD] | TBD | farm.it@smartdairybd.com | TBD | - |
| Customer Care | - | TBD | care@smartdairybd.com | TBD | - |

#### A.2 Vendor Contacts Summary

| Vendor | Support Type | Contact | Email | Emergency |
|--------|--------------|---------|-------|-----------|
| **AWS** |||||
| | General Support | +1-206-266-4064 | https://console.aws.amazon.com/support | - |
| | Business Support | Console | - | 24/7 |
| **Odoo** |||||
| | General | +32 X XXX XX XX | support@odoo.com | - |
| | Technical | +32 X XXX XX XX | technical@odoo.com | - |
| | Partner | +32 X XXX XX XX | partner@odoo.com | - |
| **bKash** |||||
| | Merchant Support | TBD | support@bkash.com | TBD |
| | Integration | TBD | integration@bkash.com | TBD |
| **Nagad** |||||
| | Merchant Support | TBD | merchant@nagad.com.bd | TBD |
| | Technical | TBD | tech@nagad.com.bd | TBD |
| **SSLCommerz** |||||
| | Support | TBD | support@sslcommerz.com | 24/7 |
| | Integration | TBD | integration@sslcommerz.com | - |
| **Primary ISP** |||||
| | Support | TBD | - | TBD |
| | Emergency | TBD | - | TBD |
| **Backup ISP** |||||
| | Support | TBD | - | TBD |
| | Emergency | TBD | - | TBD |
| **Domain Registrar** |||||
| | Support | - | support@registrar.com | - |
| **Cloudflare** |||||
| | Support | - | https://support.cloudflare.com | 24/7 |

### Appendix B: Escalation Forms

#### B.1 Escalation Request Form

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        ESCALATION REQUEST FORM                              │
│                         Smart Dairy Ltd.                                    │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  Escalation ID: ESC-[YYYY]-[NNNN]                    Date: ___________     │
│                                                                             │
│  INCIDENT INFORMATION                                                       │
│  ─────────────────                                                          │
│  Incident ID: _______________________    Severity: □ P1  □ P2  □ P3  □ P4 │
│                                                                             │
│  System Affected: ________________________________________________________ │
│                                                                             │
│  Issue Summary: __________________________________________________________ │
│                                                                             │
│  ESCALATION DETAILS                                                         │
│  ─────────────────                                                          │
│  Escalating From Level: ______    To Level: ______                         │
│                                                                             │
│  Reason for Escalation:                                                     │
│  □ Time threshold exceeded      □ Complexity requires specialist           │
│  □ Severity increased           □ Current level unable to resolve          │
│  □ Vendor involvement needed    □ Other: _______________                   │
│                                                                             │
│  Business Impact:                                                           │
│  Users Affected: ______    Revenue Impact: BDT ______/hour                 │
│  SLA Breach Risk: □ Yes  □ No    ETA Breach: ___________                   │
│                                                                             │
│  ACTIONS TAKEN                                                              │
│  ─────────────────                                                          │
│  1. ____________________________________________________________________   │
│  2. ____________________________________________________________________   │
│  3. ____________________________________________________________________   │
│  4. ____________________________________________________________________   │
│                                                                             │
│  ATTACHMENTS                                                                │
│  ─────────────────                                                          │
│  □ Error logs      □ Screenshots      □ Monitoring graphs                  │
│  □ Network traces  □ Vendor tickets   □ Other: ___________                 │
│                                                                             │
│  ESCALATION HANDOVER                                                        │
│  ─────────────────                                                          │
│  Escalated By: _______________________    Time: ___________                │
│                                                                             │
│  Received By: ________________________    Time: ___________                │
│                                                                             │
│  Signature: __________________________    Signature: __________________    │
│                                                                             │
│  NOTES                                                                      │
│  ─────────────────                                                          │
│  ________________________________________________________________________  │
│  ________________________________________________________________________  │
│  ________________________________________________________________________  │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### B.2 Escalation Log Template

| Escalation ID | Date/Time | Incident ID | From | To | Reason | Status | Closed |
|---------------|-----------|-------------|------|----|--------|--------|--------|
| | | | | | | | |
| | | | | | | | |
| | | | | | | | |
| | | | | | | | |
| | | | | | | | |

#### B.3 After-Hours Report Form

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                     AFTER-HOURS INCIDENT REPORT                             │
│                         Smart Dairy Ltd.                                    │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  Report ID: AHR-[YYYY]-[NNNN]                                              │
│                                                                             │
│  INCIDENT DETAILS                                                           │
│  ─────────────────                                                          │
│  Date/Time Detected: ___________________                                   │
│  Date/Time Resolved: ___________________                                   │
│  Total Duration: _______________________                                   │
│                                                                             │
│  On-Call Engineer: _____________________                                   │
│  Assisted By: __________________________                                   │
│                                                                             │
│  Issue Description:                                                         │
│  ________________________________________________________________________  │
│  ________________________________________________________________________  │
│                                                                             │
│  RESOLUTION                                                                 │
│  ─────────────────                                                          │
│  Root Cause: ____________________________________________________________  │
│                                                                             │
│  Actions Taken:                                                             │
│  ________________________________________________________________________  │
│  ________________________________________________________________________  │
│  ________________________________________________________________________  │
│                                                                             │
│  FOLLOW-UP REQUIRED                                                         │
│  ─────────────────                                                          │
│  □ Permanent fix needed     □ Documentation update required                │
│  □ Process improvement      □ Training needed                              │
│  □ Vendor ticket opened     □ Other: ________________                      │
│                                                                             │
│  Follow-up Actions:                                                         │
│  ________________________________________________________________________  │
│                                                                             │
│  Reported By: _________________________    Date: ___________               │
│                                                                             │
│  Reviewed By (Manager): _______________    Date: ___________               │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Appendix C: Communication Templates

#### C.1 Initial Escalation Notification Email

**Subject:** [ESCALATION] [P1/P2/P3/P4] - [Brief Issue Description] - INC-[XXXX]

```
ESCALATION NOTIFICATION

Incident ID: INC-[XXXX]
Escalation ID: ESC-[XXXX]
Severity: [P1/P2/P3/P4]
Escalated: [Date/Time]
From: [Level X] / [Name]
To: [Level Y] / [Name]

ISSUE SUMMARY
─────────────
System: [Affected System]
Description: [Brief description of the issue]
Detected: [Date/Time]
Impact: [Number of users affected / Business impact]

CURRENT STATUS
──────────────
Status: [In Progress/Blocked/Waiting]
Current Assignee: [Name]
Time at Current Level: [X minutes/hours]

BUSINESS IMPACT
───────────────
• Users Affected: [X users / X%]
• Revenue Impact: [BDT X/hour or N/A]
• SLA Status: [On track / At risk / Breached]
• Customer Visible: [Yes/No]

ACTIONS TAKEN
─────────────
1. [Action taken and result]
2. [Action taken and result]
3. [Action taken and result]

NEXT STEPS
──────────
[What needs to happen next]

ATTACHMENTS
───────────
• [List of attached logs, screenshots, etc.]

For questions, contact [Name] at [Phone/Email].

This is an automated escalation notification.
```

#### C.2 Executive Summary Template

**Subject:** [EXECUTIVE ALERT] Critical Incident - [System] - [Time]

```
EXECUTIVE INCIDENT SUMMARY

Incident Reference: INC-[XXXX]
Report Time: [Date/Time]
Reporting Manager: [Name]

EXECUTIVE SUMMARY
─────────────────
[System Name] is experiencing [brief description]. The issue was detected 
at [time] and has impacted [scope of impact].

BUSINESS IMPACT
───────────────
• Services Affected: [List]
• Customers Impacted: [Number/Estimate]
• Revenue Impact: [Amount per hour or "Under Assessment"]
• Regulatory Risk: [Yes/No - Details if Yes]
• Media/Public Attention: [Yes/No - Details if Yes]

CURRENT RESPONSE
────────────────
• Response Team: [Names/Departments engaged]
• Current Status: [Status]
• Estimated Resolution: [ETA or "Under Assessment"]

ACTIONS TAKEN
─────────────
• [Key action 1]
• [Key action 2]
• [Key action 3]

NEXT UPDATE
───────────
[Time of next scheduled update]

For immediate questions, contact [Name] at [Phone].
```

#### C.3 Status Update Template

**Subject:** [UPDATE] [P1/P2/P3/P4] - [Issue] - INC-[XXXX] - [Status]

```
INCIDENT STATUS UPDATE

Incident ID: INC-[XXXX]
Update Time: [Date/Time]
Elapsed Time: [X hours Y minutes]

STATUS: [IN PROGRESS / RESOLVED / CLOSED]

PROGRESS SUMMARY
────────────────
[2-3 sentences summarizing current state vs. last update]

ACTIONS SINCE LAST UPDATE
─────────────────────────
• [Action completed]
• [Action completed]
• [Action in progress]

CURRENT PLAN
────────────
[Next steps planned]

ETA UPDATE
──────────
Previous ETA: [Previous estimate]
Current ETA: [Updated estimate or "Unchanged"]
Reason for Change: [If applicable]

IMPACT UPDATE
─────────────
Users Affected: [Updated numbers]
Revenue Impact: [Updated estimate]
SLA Status: [Updated status]

Next Update: [Time]
```

#### C.4 Resolution Notification Template

**Subject:** [RESOLVED] [P1/P2/P3/P4] - [Issue] - INC-[XXXX]

```
INCIDENT RESOLVED

Incident ID: INC-[XXXX]
Resolution Time: [Date/Time]
Total Duration: [X hours Y minutes]
Resolved By: [Name/Team]

ISSUE SUMMARY
─────────────
[System] experienced [brief description] starting at [time].

ROOT CAUSE
──────────
[Technical description of root cause]

RESOLUTION
──────────
[How the issue was resolved]

IMPACT SUMMARY
──────────────
• Total Downtime: [Duration]
• Users Affected: [Numbers]
• Revenue Impact: [Amount or "To be calculated"]
• Data Loss: [None/Details if any]

LESSONS LEARNED
───────────────
[Key takeaways for future prevention]

FOLLOW-UP ACTIONS
─────────────────
• [Action item 1 - Owner - Due date]
• [Action item 2 - Owner - Due date]

Post-Incident Review Meeting: [Date/Time]

Thank you for your patience.
```

### Appendix D: Escalation Matrix Quick Reference

#### D.1 One-Page Escalation Matrix

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                    ESCALATION MATRIX - QUICK REFERENCE                              │
│                         Smart Dairy Ltd.                                            │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                     │
│  SEVERITY │ L1 Response │ L2 Escalation │ L3 Escalation │ L4 Escalation │ Vendor   │
│  ─────────┼─────────────┼───────────────┼───────────────┼───────────────┼──────────┤
│  P1       │ 5 min       │ 15 min        │ 30 min        │ 1 hour        │ Immediate│
│  Critical │             │               │               │               │          │
│  ─────────┼─────────────┼───────────────┼───────────────┼───────────────┼──────────┤
│  P2       │ 15 min      │ 30 min        │ 1 hour        │ 2 hours       │ 1 hour   │
│  High     │             │               │               │               │          │
│  ─────────┼─────────────┼───────────────┼───────────────┼───────────────┼──────────┤
│  P3       │ 30 min      │ 2 hours       │ 4 hours       │ 8 hours       │ 4 hours  │
│  Medium   │             │               │               │               │          │
│  ─────────┼─────────────┼───────────────┼───────────────┼───────────────┼──────────┤
│  P4       │ 1 hour      │ 4 hours       │ 8 hours       │ 24 hours      │ N/A      │
│  Low      │             │               │               │               │          │
│                                                                                     │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                     │
│  ISSUE TYPE            │ L1           │ L2           │ L3           │ Vendor       │
│  ──────────────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│  Infrastructure        │ Helpdesk     │ Infra Team   │ IT Director  │ AWS          │
│  Application (Odoo)    │ Helpdesk     │ App Team     │ IT Director  │ Odoo Partner │
│  Database              │ Helpdesk     │ DBA          │ IT Director  │ AWS RDS      │
│  Security              │ Helpdesk     │ Security     │ IT Director  │ CERT/Vendor  │
│  Network               │ Helpdesk     │ Network      │ IT Director  │ ISP          │
│  Payment Gateway       │ Helpdesk     │ App Team     │ IT Director  │ bKash/Nagad  │
│                                                                                     │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                     │
│  EMERGENCY CONTACTS                                                                 │
│  ─────────────────                                                                  │
│  IT Support Hotline:        [TBD]                                                   │
│  On-Call Engineer:          [TBD - Rotating]                                        │
│  IT Manager:                [TBD]                                                   │
│  IT Director:               [TBD]                                                   │
│  Managing Director:         [TBD]                                                   │
│                                                                                     │
│  VENDOR EMERGENCY                                                                   │
│  ────────────────                                                                   │
│  AWS Emergency:             +1-206-266-4064                                         │
│  Odoo Emergency:            +32 X XXX XX XX                                         │
│  bKash Emergency:           [TBD]                                                   │
│  Nagad Emergency:           [TBD]                                                   │
│  SSLCommerz:                [TBD - 24/7]                                            │
│                                                                                     │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

### Appendix E: Definitions and Acronyms

| Term | Definition |
|------|------------|
| **AWS** | Amazon Web Services |
| **CERT** | Computer Emergency Response Team |
| **DBA** | Database Administrator |
| **ERP** | Enterprise Resource Planning |
| **ISP** | Internet Service Provider |
| **L1, L2, L3, L4** | Escalation Levels 1-4 |
| **NOC** | Network Operations Center |
| **P1, P2, P3, P4** | Priority levels 1-4 (Critical to Low) |
| **RDS** | Relational Database Service (AWS) |
| **SLA** | Service Level Agreement |
| **SSL** | Secure Sockets Layer |
| **TAM** | Technical Account Manager |
| **TLS** | Transport Layer Security |
| **VPN** | Virtual Private Network |

### Appendix F: Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| **Author** | Operations Manager | ___________________ | _________ |
| **Reviewer** | IT Director | ___________________ | _________ |
| **Approver** | Managing Director | ___________________ | _________ |

---

## Document Control Information

**Distribution List:**
- IT Operations Team
- Development Team
- Management Team
- Support Staff
- On-Call Engineers

**Review Schedule:**
- Regular Review: Quarterly
- Emergency Review: As needed
- Next Scheduled Review: April 30, 2026

**Change Control:**
All changes to this document must be approved by the IT Director and communicated to all stakeholders within 48 hours.

---

*End of Document L-018*

*Document Classification: Internal Use Only*
*Version 1.0 | January 31, 2026*
