# SMART DAIRY LTD.
## BUSINESS CONTINUITY PROCEDURES
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | L-016 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Business Continuity Manager |
| **Owner** | Business Continuity Manager |
| **Reviewer** | Managing Director |
| **Classification** | CONFIDENTIAL - INTERNAL USE ONLY |
| **Review Cycle** | Annual |

---

## DOCUMENT CONTROL

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Business Continuity Manager | Initial version |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Business Impact Analysis](#2-business-impact-analysis)
3. [BCP Team](#3-bcp-team)
4. [Activation Triggers](#4-activation-triggers)
5. [Continuity Procedures](#5-continuity-procedures)
6. [Communication Plan](#6-communication-plan)
7. [Resource Requirements](#7-resource-requirements)
8. [Recovery Phases](#8-recovery-phases)
9. [Training and Testing](#9-training-and-testing)
10. [Plan Maintenance](#10-plan-maintenance)
11. [Appendices](#11-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This Business Continuity Procedures document provides detailed operational instructions for Smart Dairy Ltd. to maintain essential business functions during and after disruptive incidents. It serves as the operational companion to the Business Continuity Plan (F-012), focusing on specific procedures, workflows, and actions required to ensure business resilience.

### 1.2 Scope

**In Scope:**
- All critical business operations of Smart Dairy Ltd.
- Technology infrastructure and digital services
- Farm operations and production processes
- Supply chain and logistics operations
- Customer-facing services (B2B and B2C)
- Administrative and support functions
- Human resources and personnel management

**Out of Scope:**
- Parent company (Smart Group) operations
- Non-critical development activities
- Long-term strategic planning (handled separately)

### 1.3 BCP Objectives

| Objective | Description | Success Criteria |
|-----------|-------------|------------------|
| **Minimize Downtime** | Ensure critical functions resume within defined RTOs | 99.9% availability for Tier 1 services |
| **Protect Stakeholders** | Safeguard employees, customers, farmers, and partners | Zero safety incidents during disruptions |
| **Preserve Data Integrity** | Maintain accurate and complete business records | RPO compliance: 15 min for critical data |
| **Ensure Compliance** | Meet regulatory and contractual obligations | 100% compliance with notification requirements |
| **Maintain Reputation** | Preserve customer confidence and market position | Customer satisfaction >90% post-incident |
| **Enable Rapid Recovery** | Return to normal operations efficiently | Full restoration within MTD targets |

### 1.4 Document Hierarchy

```
Business Continuity Plan (F-012)
    └── Business Continuity Procedures (L-016) ← THIS DOCUMENT
            ├── Incident Response Plan (F-011)
            ├── Incident Management Process (L-003)
            ├── Disaster Recovery Plan (D-012)
            └── Communication Templates (Appendix C)
```

---

## 2. BUSINESS IMPACT ANALYSIS

### 2.1 Critical Business Functions

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SMART DAIRY CRITICAL FUNCTIONS MATRIX                     │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  TIER 1 - CRITICAL (RTO: 4 hours)                                           │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │ • Payment Processing        • Order Management                     │    │
│  │ • Customer Authentication   • Milk Collection Recording            │    │
│  │ • Cold Chain Monitoring     • Critical IoT Sensors                 │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  TIER 2 - HIGH (RTO: 8 hours)                                               │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │ • Inventory Management      • Delivery Tracking                    │    │
│  │ • Farm Data Collection      • B2B Portal Operations                │    │
│  │ • Customer Service Tools    • Quality Control Systems              │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  TIER 3 - MEDIUM (RTO: 24 hours)                                            │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │ • Reporting & Analytics     • Marketing Campaigns                  │    │
│  │ • HR Management System      • Financial Reporting                  │    │
│  │ • Procurement Processing    • Training Systems                     │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  TIER 4 - LOW (RTO: 72 hours)                                               │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │ • Development Environments  • Non-critical Documentation           │    │
│  │ • Internal Collaboration    • Archive Access                       │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Recovery Priorities

| Priority | Function | Business Justification | Dependencies |
|----------|----------|------------------------|--------------|
| **P1** | Payment Processing | Revenue generation, customer trust | Payment gateways, SSL, Bank APIs |
| **P1** | Order Management | Customer transactions, revenue | Database, Web servers, Mobile APIs |
| **P1** | Customer Authentication | Security, access control | Auth service, Database, SMS gateway |
| **P2** | Milk Collection Recording | Farmer payments, supply tracking | Farm IoT, Mobile network, Database |
| **P2** | Cold Chain Monitoring | Product quality, food safety | Temperature sensors, Alert systems |
| **P2** | Inventory Management | Stock control, order fulfillment | Warehouse systems, Database |
| **P3** | Delivery Tracking | Customer experience, logistics | GPS systems, Mobile network |
| **P3** | Farm Data Collection | Herd management, production | IoT gateway, Data pipeline |

### 2.3 RTO/RPO by Function

| Function | Criticality | RTO | RPO | MTD | Financial Impact/Hour |
|----------|-------------|-----|-----|-----|----------------------|
| **Payment Processing** | Critical | 4h | 15min | 8h | ৳500,000+ |
| **Order Management** | Critical | 4h | 15min | 8h | ৳400,000 |
| **Customer Authentication** | Critical | 4h | 15min | 8h | ৳300,000 |
| **Milk Collection** | High | 8h | 1h | 24h | ৳200,000 |
| **Cold Chain Monitoring** | High | 8h | 1h | 24h | ৳250,000 |
| **Inventory Management** | High | 8h | 1h | 24h | ৳200,000 |
| **Delivery Tracking** | High | 8h | 1h | 24h | ৳100,000 |
| **Farm Data Collection** | High | 8h | 1h | 24h | ৳150,000 |
| **B2B Portal** | Medium | 24h | 4h | 48h | ৳75,000 |
| **Reporting & Analytics** | Medium | 24h | 4h | 48h | ৳50,000 |
| **Marketing Campaigns** | Low | 72h | 24h | 1 week | ৳30,000 |
| **Dev/Test Environments** | Low | 72h | 24h | 1 week | ৳5,000 |

### 2.4 Critical Function Interdependencies

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    CRITICAL FUNCTION INTERDEPENDENCIES                       │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│                        ┌─────────────────┐                                   │
│                        │   CUSTOMERS     │                                   │
│                        │  (B2B & B2C)    │                                   │
│                        └────────┬────────┘                                   │
│                                 │                                            │
│              ┌──────────────────┼──────────────────┐                        │
│              │                  │                  │                        │
│              ▼                  ▼                  ▼                        │
│       ┌──────────┐       ┌──────────┐       ┌──────────┐                   │
│       │  B2C     │       │  B2B     │       │  MOBILE  │                   │
│       │ E-COM    │◄─────►│ PORTAL   │◄─────►│   APP    │                   │
│       └────┬─────┘       └────┬─────┘       └────┬─────┘                   │
│            │                  │                  │                          │
│            └──────────────────┼──────────────────┘                          │
│                               │                                             │
│                               ▼                                             │
│                        ┌──────────────┐                                     │
│                        │ ORDER MGMT   │                                     │
│                        └──────┬───────┘                                     │
│                               │                                             │
│              ┌────────────────┼────────────────┐                           │
│              │                │                │                           │
│              ▼                ▼                ▼                           │
│       ┌──────────┐    ┌──────────┐    ┌──────────────┐                     │
│       │ PAYMENT  │    │INVENTORY │    │FARM OPS MGMT │                     │
│       │PROCESSING│    │  SYSTEM  │    │   SYSTEM     │                     │
│       └────┬─────┘    └────┬─────┘    └──────┬───────┘                     │
│            │               │                 │                             │
│            │               │                 ▼                             │
│            │               │          ┌──────────┐                        │
│            │               │          │  IOT &   │                        │
│            │               │          │ SENSORS  │                        │
│            │               │          └──────────┘                        │
│            │               │                                              │
│            └───────────────┴──────────────────┐                           │
│                                               │                           │
│                                               ▼                           │
│                                        ┌──────────┐                       │
│                                        │DATABASE  │                       │
│                                        │  LAYER   │                       │
│                                        └──────────┘                       │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 3. BCP TEAM

### 3.1 Crisis Management Team (CMT)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    CRISIS MANAGEMENT TEAM STRUCTURE                          │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│                         ┌─────────────────────┐                              │
│                         │   CRISIS DIRECTOR   │                              │
│                         │  (Managing Director)│                              │
│                         │  • Final authority  │                              │
│                         │  • External liaison │                              │
│                         │  • Media approval   │                              │
│                         └──────────┬──────────┘                              │
│                                    │                                         │
│                         ┌──────────▼──────────┐                              │
│                         │   BCP COORDINATOR   │                              │
│                         │  (Operations Mgr)   │                              │
│                         │  • Plan activation  │                              │
│                         │  • Team coordination│                              │
│                         │  • Resource alloc.  │                              │
│                         └──────────┬──────────┘                              │
│                                    │                                         │
│        ┌───────────────────────────┼───────────────────────────┐            │
│        │                           │                           │            │
│        ▼                           ▼                           ▼            │
│ ┌──────────────┐          ┌──────────────┐          ┌──────────────┐       │
│ │   TECHNICAL  │          │  OPERATIONS  │          │COMMUNICATION │       │
│ │    LEAD      │          │    LEAD      │          │    LEAD      │       │
│ │              │          │              │          │              │       │
│ │ • IT systems │          │ • Business   │          │ • Internal   │       │
│ │ • Recovery   │          │   operations │          │   comms      │       │
│ │ • DR exec.   │          │ • Farm ops   │          │ • External   │       │
│ └──────┬───────┘          │ • Supply chain│          │   comms      │       │
│        │                  └──────┬───────┘          │ • Media      │       │
│   ┌────┴────┐                  │                  └──────────────┘       │
│   │         │             ┌────┴────┐                                      │
│   ▼         ▼             ▼         ▼                                      │
│ ┌─────┐  ┌─────┐      ┌─────┐  ┌─────┐                                    │
│ │Infra │  │ App │      │Farm │  │Logi-│                                    │
│ │ Team │  │Team │      │ Ops │  │stics│                                    │
│ └─────┘  └─────┘      └─────┘  └─────┘                                    │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Crisis Management Team Roles

| Role | Primary | Backup | Contact Priority | Authority Level |
|------|---------|--------|------------------|-----------------|
| **Crisis Director** | Managing Director | Chairman | 1 | Full executive authority |
| **BCP Coordinator** | Operations Manager | IT Manager | 2 | Operational authority |
| **Technical Lead** | Head of Engineering | Senior DevOps | 3 | Technical decisions |
| **Operations Lead** | Operations Manager | Farm Manager | 4 | Business operations |
| **Communication Lead** | Marketing Manager | Customer Service Mgr | 5 | Communications approval |

### 3.3 Functional Teams

#### 3.3.1 Technical Recovery Team

| Role | Responsibilities | Primary | Backup |
|------|------------------|---------|--------|
| **Technical Lead** | Overall technical coordination | Head of Engineering | Senior DevOps |
| **Infrastructure Lead** | Servers, network, cloud | DevOps Manager | Senior SysAdmin |
| **Database Administrator** | Data recovery, integrity | DBA Lead | Senior DBA |
| **Application Lead** | ERP, portals, mobile apps | Lead Developer | Senior Developer |
| **Security Lead** | Security, forensics | Security Manager | SOC Analyst |
| **IoT Lead** | Farm sensors, devices | IoT Engineer | Field Technician |

#### 3.3.2 Business Operations Team

| Role | Responsibilities | Primary | Backup |
|------|------------------|---------|--------|
| **Operations Lead** | Overall business continuity | Operations Manager | Assistant Ops Mgr |
| **Farm Operations** | Farm continuity, milk collection | Farm Manager | Assistant Farm Mgr |
| **Supply Chain** | Logistics, distribution | Logistics Manager | Warehouse Supervisor |
| **Customer Service** | Customer support continuity | CS Manager | Team Lead |
| **Finance Lead** | Payment processing, accounting | Finance Manager | Senior Accountant |
| **HR Lead** | Personnel management, welfare | HR Manager | HR Officer |

#### 3.3.3 Communication Team

| Role | Responsibilities | Primary | Backup |
|------|------------------|---------|--------|
| **Communication Lead** | Overall communication strategy | Marketing Manager | PR Manager |
| **Internal Communications** | Employee communications | HR Manager | Internal Comms Officer |
| **Customer Communications** | B2B/B2C customer updates | CS Manager | CS Team Lead |
| **Vendor Communications** | Supplier, partner updates | Procurement Manager | Procurement Officer |
| **Media Relations** | Press, public relations | PR Manager | Marketing Manager |
| **Regulatory Communications** | Authority notifications | Legal Counsel | Compliance Officer |

### 3.4 Team Activation Contact Matrix

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    BCP TEAM ACTIVATION CONTACT MATRIX                        │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  TIER 1 - IMMEDIATE ACTIVATION (Within 15 minutes)                          │
│  ═══════════════════════════════════════════════════                        │
│                                                                              │
│  Crisis Director (Managing Director)                                        │
│  ├── Mobile: +880-XXXX-XXXXXX (Primary)                                     │
│  ├── Mobile: +880-XXXX-XXXXXX (Secondary)                                   │
│  └── Home: +880-XXXX-XXXXXX                                                 │
│                                                                              │
│  BCP Coordinator (Operations Manager)                                       │
│  ├── Mobile: +880-XXXX-XXXXXX                                               │
│  ├── WhatsApp: +880-XXXX-XXXXXX                                             │
│  └── Home: +880-XXXX-XXXXXX                                                 │
│                                                                              │
│  Technical Lead (Head of Engineering)                                       │
│  ├── Mobile: +880-XXXX-XXXXXX                                               │
│  ├── WhatsApp: +880-XXXX-XXXXXX                                             │
│  └── Home: +880-XXXX-XXXXXX                                                 │
│                                                                              │
│  TIER 2 - ACTIVATION WITHIN 1 HOUR                                          │
│  ═══════════════════════════════════                                        │
│                                                                              │
│  Operations Lead, Communication Lead, Infrastructure Lead                   │
│  Database Administrator, Security Lead, Farm Operations                     │
│                                                                              │
│  TIER 3 - ACTIVATION WITHIN 4 HOURS                                         │
│  ═══════════════════════════════════                                        │
│                                                                              │
│  Application Lead, IoT Lead, Supply Chain, Customer Service                 │
│  Finance Lead, HR Lead, Vendor Communications                               │
│                                                                              │
│  TIER 4 - STANDBY ACTIVATION                                                │
│  ═══════════════════════════════════                                        │
│                                                                              │
│  Media Relations, Regulatory Communications, Development Team               │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 4. ACTIVATION TRIGGERS

### 4.1 BCP Activation Criteria

#### 4.1.1 Full BCP Activation Triggers

| Trigger | Threshold | Response Level | Notification Time |
|---------|-----------|----------------|-------------------|
| **Primary Data Center Failure** | >30 minutes unavailability | Full BCP | Immediate |
| **Cyber Attack (Ransomware)** | Confirmed infection/encryption | Full BCP | Immediate |
| **Natural Disaster** | Building evacuation/flood warning | Full BCP | Immediate |
| **Extended Power Outage** | >4 hours without generator | Full BCP | 30 minutes |
| **Mass Personnel Unavailability** | >50% staff unavailable | Full BCP | 1 hour |
| **Critical Vendor Failure** | Payment gateway down >2h | Full BCP | 1 hour |
| **Data Breach** | Confirmed unauthorized access | Full BCP | Immediate |
| **Fire/Explosion** | Facility evacuation required | Full BCP | Immediate |

#### 4.1.2 Partial BCP Activation Triggers

| Trigger | Response Level | Notification Time |
|---------|----------------|-------------------|
| Single critical system failure | Partial BCP | 30 minutes |
| Regional connectivity issue | Partial BCP | 1 hour |
| Non-critical system outage (>8h) | Partial BCP | 2 hours |
| Security incident (contained) | Partial BCP | 30 minutes |
| Minor facility issue | Partial BCP | 4 hours |

#### 4.1.3 Monitor Only (No Activation)

| Trigger | Response Level | Action |
|---------|----------------|--------|
| Single service degradation | Monitor | Standard incident management |
| Planned maintenance | Monitor | Follow change procedures |
| Minor performance issue | Monitor | Troubleshooting mode |
| Non-critical feature unavailable | Monitor | Standard support queue |

### 4.2 Authority Levels

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    BCP ACTIVATION AUTHORITY LEVELS                           │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  LEVEL 1 - CRISIS DIRECTOR (Managing Director)                              │
│  ─────────────────────────────────────────────                              │
│  Authority: Full BCP activation, external communications, media statements  │
│  Activation Triggers:                                                       │
│  • Major disaster affecting multiple sites                                  │
│  • Significant data breach requiring regulatory notification                │
│  • Extended outage (>24 hours)                                              │
│  • Any incident with potential media coverage                               │
│                                                                              │
│  LEVEL 2 - BCP COORDINATOR (Operations Manager)                             │
│  ───────────────────────────────────────────────                            │
│  Authority: Full BCP activation, DR failover, emergency vendor engagement   │
│  Activation Triggers:                                                       │
│  • Primary site failure                                                     │
│  • Critical system outage (>4 hours)                                        │
│  • Confirmed security incident                                              │
│  • Natural disaster affecting operations                                    │
│                                                                              │
│  LEVEL 3 - TECHNICAL LEAD (Head of Engineering)                             │
│  ───────────────────────────────────────────────                            │
│  Authority: Partial BCP activation, technical workarounds, system failover  │
│  Activation Triggers:                                                       │
│  • Single system failure                                                    │
│  • Performance degradation                                                  │
│  • Non-critical security incident                                           │
│                                                                              │
│  LEVEL 4 - TEAM LEADS                                                       │
│  ─────────────────────────                                                  │
│  Authority: Standard incident response, temporary workarounds               │
│  Activation Triggers:                                                       │
│  • Minor system issues                                                      │
│  • Non-critical service degradation                                         │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 4.3 Notification Procedures

#### 4.3.1 BCP Activation Workflow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    BCP ACTIVATION WORKFLOW                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────┐                                                         │
│  │  INCIDENT       │                                                         │
│  │  DETECTED       │                                                         │
│  └────────┬────────┘                                                         │
│           │                                                                  │
│           ▼                                                                  │
│  ┌─────────────────┐     No      ┌─────────────────┐                         │
│  │  BCP TRIGGER?   │────────────►│  STANDARD       │                         │
│  │  (Section 4.1)  │             │  INCIDENT MGMT  │                         │
│  └────────┬────────┘             └─────────────────┘                         │
│           │ Yes                                                              │
│           ▼                                                                  │
│  ┌─────────────────┐                                                         │
│  │  ASSESS SEVERITY│                                                         │
│  │  & IMPACT       │                                                         │
│  └────────┬────────┘                                                         │
│           │                                                                  │
│           ▼                                                                  │
│  ┌─────────────────┐     Level 1   ┌─────────────────┐                       │
│  │  DETERMINE      │──────────────►│  NOTIFY CRISIS  │                       │
│  │  AUTHORITY      │               │  DIRECTOR       │                       │
│  │  LEVEL          │               │  (MD)           │                       │
│  └────────┬────────┘               └─────────────────┘                       │
│           │                                                                  │
│           │ Level 2         ┌─────────────────┐                              │
│           └────────────────►│  NOTIFY BCP     │                              │
│                             │  COORDINATOR    │                              │
│                             │  (Ops Manager)  │                              │
│                             └────────┬────────┘                              │
│                                      │                                       │
│                                      ▼                                       │
│                             ┌─────────────────┐                              │
│                             │  ACTIVATE BCP   │                              │
│                             │  TEAMS          │                              │
│                             │  (Per Section   │                              │
│                             │   4.3.2)        │                              │
│                             └────────┬────────┘                              │
│                                      │                                       │
│                                      ▼                                       │
│                             ┌─────────────────┐                              │
│                             │  ESTABLISH      │                              │
│                             │  COMMAND        │                              │
│                             │  CENTER         │                              │
│                             └─────────────────┘                              │
│                                      │                                       │
│                                      ▼                                       │
│                             ┌─────────────────┐                              │
│                             │  INITIATE       │                              │
│                             │  RECOVERY       │                              │
│                             │  PROCEDURES     │                              │
│                             └─────────────────┘                              │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### 4.3.2 Notification Cascade

| Time | Action | Responsible | Method |
|------|--------|-------------|--------|
| **T+0** | Initial incident detection | Monitoring/On-call | Automated alert |
| **T+5 min** | BCP Coordinator notified | On-call engineer | Phone + WhatsApp |
| **T+15 min** | Initial assessment complete | BCP Coordinator | Conference call |
| **T+30 min** | Crisis Director notified (if Level 1) | BCP Coordinator | Direct call |
| **T+30 min** | Technical team activated | Technical Lead | Phone + Email |
| **T+45 min** | Operations team activated | Operations Lead | Phone + Email |
| **T+60 min** | Communication team activated | Communication Lead | Phone + Email |
| **T+60 min** | Customer notification initiated | Communication Lead | Email + SMS |
| **T+2 hours** | Vendor notifications sent | Procurement/IT | Email + Phone |
| **T+4 hours** | Regulatory notification (if required) | Legal Counsel | Formal channels |

#### 4.3.3 Notification Templates

**Initial Activation Message (BCP Coordinator to Team Leads):**

```
🚨 BCP ACTIVATION ALERT 🚨

BCP has been ACTIVATED due to [INCIDENT TYPE].

INCIDENT DETAILS:
- Type: [Description]
- Detected: [Date/Time]
- Severity: [Level]
- Impact: [Description]

YOUR ACTION:
Report to [Command Center Location/Virtual Bridge] within 30 minutes.
Bring: Laptop, phone charger, access credentials.

COMMAND CENTER:
- Location: [Address/Virtual Link]
- Bridge: [Phone/Video Link]
- Incident ID: BCP-YYYY-MM-NNNN

BCP COORDINATOR: [Name] | [Phone] | [WhatsApp]
```

---

## 5. CONTINUITY PROCEDURES

### 5.1 Work From Home (WFH) Procedures

#### 5.1.1 WFH Activation Criteria

| Scenario | Activation Level | Expected Duration |
|----------|------------------|-------------------|
| Pandemic/Health Emergency | Full WFH | Weeks to months |
| Transportation Strike | Partial WFH | Days to weeks |
| Building Evacuation | Full WFH | Hours to days |
| Security Threat | Partial WFH | Hours to days |
| Weather Emergency | Partial WFH | Hours to days |

#### 5.1.2 WFH Readiness Requirements

| Role | Equipment Required | Pre-requisites |
|------|-------------------|----------------|
| **Management** | Laptop, VPN, Mobile | MFA enabled, cloud access |
| **Developers** | Laptop, VPN, IDE access | Git access, dev environment |
| **Support Staff** | Laptop, VPN, Phone | CRM access, call forwarding |
| **Farm Staff** | Mobile app, Tablet | Offline capability configured |
| **Sales** | Laptop, VPN, Mobile | CRM access, communication tools |
| **Finance** | Laptop, VPN, Secure access | Banking tokens, MFA enabled |

#### 5.1.3 WFH Activation Checklist

- [ ] VPN capacity verified (can support 100% staff)
- [ ] Communication channels activated (Teams/Slack)
- [ ] Phone system call forwarding configured
- [ ] Critical staff confirmed as WFH-ready
- [ ] Document access permissions verified
- [ ] Security monitoring enhanced for remote access
- [ ] Staff notified of WFH procedures
- [ ] Check-in schedule established (daily standups)

### 5.2 Alternate Site Procedures

#### 5.2.1 Alternate Site Options

| Site Type | Location | Capacity | RTO | Use Case |
|-----------|----------|----------|-----|----------|
| **Hot Site** | AWS Singapore | Full capacity | 2 hours | Primary DR |
| **Warm Site** | Smart Group Office | 20 staff | 4 hours | Command center |
| **Cold Site** | Farm Office | 10 staff | 24 hours | Emergency ops |
| **Partner Site** | Vendor facility | Variable | 48 hours | Extended outage |

#### 5.2.2 Alternate Site Activation

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    ALTERNATE SITE ACTIVATION PROCEDURE                       │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  STEP 1: DECISION (BCP Coordinator)                                         │
│  ─────────────────────────────────                                          │
│  □ Assess primary site status                                               │
│  □ Determine alternate site needed (Hot/Warm/Cold)                          │
│  □ Obtain Crisis Director approval (if >72 hours)                           │
│  □ Notify site coordinator                                                  │
│                                                                              │
│  STEP 2: HOT SITE ACTIVATION (AWS Singapore)                                │
│  ───────────────────────────────────────────                                │
│  □ Initiate DR failover (Technical Lead)                                    │
│  □ Update Route 53 DNS records                                              │
│  □ Activate standby ECS tasks                                               │
│  □ Promote RDS read replica                                                 │
│  □ Verify service health checks                                             │
│  □ Notify customers of service restoration                                  │
│  □ Estimated time: 30-60 minutes                                            │
│                                                                              │
│  STEP 3: WARM SITE ACTIVATION (Smart Group Office)                          │
│  ───────────────────────────────────────────────────                        │
│  □ Contact Smart Group facilities                                           │
│  □ Prepare workspace for emergency staff                                    │
│  □ Configure network access and VPN                                         │
│  □ Set up temporary workstations                                            │
│  □ Establish communication lines                                            │
│  □ Estimated time: 2-4 hours                                                │
│                                                                              │
│  STEP 4: STAFF RELOCATION                                                   │
│  ───────────────────────────                                                │
│  □ Identify essential personnel for relocation                              │
│  □ Arrange transportation                                                   │
│  □ Issue temporary access credentials                                       │
│  □ Brief staff on alternate site procedures                                 │
│  □ Establish shift schedules if needed                                      │
│                                                                              │
│  STEP 5: OPERATIONS AT ALTERNATE SITE                                       │
│  ────────────────────────────────────                                       │
│  □ Resume critical operations                                               │
│  □ Monitor service levels                                                   │
│  □ Maintain communication with stakeholders                                 │
│  □ Document all activities                                                  │
│  □ Plan for return to primary site                                          │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 5.3 Manual Procedures

#### 5.3.1 Manual Order Processing

| Step | Action | Responsible | Documentation |
|------|--------|-------------|---------------|
| 1 | Receive order via phone/email/Whatsapp | Customer Service | Order log sheet |
| 2 | Record order details manually | Customer Service | Paper order form |
| 3 | Verify customer credit (B2B) | Finance | Credit check form |
| 4 | Check inventory availability | Warehouse | Physical stock check |
| 5 | Confirm order with customer | Customer Service | Confirmation record |
| 6 | Prepare pick list | Warehouse | Pick list form |
| 7 | Fulfill order | Warehouse | Packing slip |
| 8 | Arrange delivery | Logistics | Delivery schedule |
| 9 | Record transaction for later entry | Finance | Transaction log |
| 10 | Update system when restored | Data Entry | Batch entry |

#### 5.3.2 Manual Milk Collection Recording

| Step | Action | Responsible | Documentation |
|------|--------|-------------|---------------|
| 1 | Farmer delivers milk at collection center | Farmer | Delivery receipt |
| 2 | Measure quantity using manual gauge | Collection Agent | Collection sheet |
| 3 | Test quality (Fat/SNF) using manual tester | Collection Agent | Quality log |
| 4 | Record farmer ID and quantity | Collection Agent | Farmer ledger |
| 5 | Issue temporary receipt to farmer | Collection Agent | Temporary receipt |
| 6 | Transport milk to processing | Logistics | Transport log |
| 7 | Update system when restored | Data Entry | Batch entry |
| 8 | Reconcile manual records with system | Supervisor | Reconciliation report |

#### 5.3.3 Manual Cold Chain Monitoring

| Step | Action | Responsible | Frequency |
|------|--------|-------------|-----------|
| 1 | Check cold storage temperature | Warehouse Staff | Every 2 hours |
| 2 | Record temperature on log sheet | Warehouse Staff | Every 2 hours |
| 3 | Check for any alerts or alarms | Warehouse Staff | Every 2 hours |
| 4 | Report anomalies to supervisor | Warehouse Staff | Immediate |
| 5 | Manual temperature adjustment if needed | Maintenance | As required |
| 6 | Maintain manual log until system restored | Warehouse Staff | Continuous |

### 5.4 Outsourcing Procedures

#### 5.4.1 Outsourcing Partners

| Service | Primary Partner | Backup Partner | Activation Trigger |
|---------|-----------------|----------------|-------------------|
| **Call Center** | Internal team | [Vendor Name] | >50% staff unavailable |
| **Delivery/Logistics** | Internal fleet | [3PL Name] | Fleet disruption |
| **IT Support** | Internal IT | [MSP Name] | IT team unavailable |
| **Payment Processing** | bKash/Nagad | Cash on delivery | Gateway failure |
| **Data Entry** | Internal staff | [BPO Vendor] | Backlog >48 hours |
| **Security Operations** | Internal SOC | [MSSP Name] | Extended incident |

#### 5.4.2 Outsourcing Activation Checklist

- [ ] Contact outsourcing partner (use pre-established emergency contact)
- [ ] Provide incident briefing and expected duration
- [ ] Share necessary access credentials (securely)
- [ ] Transfer relevant documentation and procedures
- [ ] Establish communication protocol
- [ ] Set performance expectations and SLAs
- [ ] Arrange for progress reporting
- [ ] Document handover details

---

## 6. COMMUNICATION PLAN

### 6.1 Internal Communication

#### 6.1.1 Internal Communication Matrix

| Stakeholder | Initial Alert | Progress Updates | All Clear | Channel |
|-------------|---------------|------------------|-----------|---------|
| **Crisis Director** | Immediate | Every 2 hours | Immediate | Direct call |
| **Board Members** | Within 30 min | Every 4 hours | Immediate | Email + Call |
| **Department Heads** | Within 15 min | Every 2 hours | Immediate | WhatsApp + Email |
| **All Employees** | Within 1 hour | Every 8 hours | Immediate | Email + Intranet |
| **BCP Team** | Immediate | Every 30 min | Immediate | Conference bridge |
| **IT Staff** | Immediate | Hourly | Immediate | Slack/Teams |

#### 6.1.2 Internal Communication Templates

**Initial Alert to All Staff:**
```
Subject: URGENT: Business Continuity Plan Activated

Dear Team,

Due to [INCIDENT TYPE], we have activated our Business Continuity Plan.

CURRENT SITUATION:
- Incident: [Brief description]
- Time: [Date/Time]
- Impact: [Affected operations]

IMMEDIATE ACTIONS FOR ALL STAFF:
1. Do not discuss this incident on social media
2. Do not contact customers directly
3. Check your email every 2 hours for updates
4. Report to your supervisor if you haven't been contacted
5. Work from home until further notice (if applicable)

YOUR DEPARTMENT HEAD will contact you with specific instructions.

For emergencies only: [Emergency contact number]

We will update you again at [Next update time].

BCP Coordinator
[Name] | [Phone]
```

### 6.2 Customer Communication

#### 6.2.1 Customer Communication Matrix

| Customer Type | Initial Alert | Progress Updates | All Clear | Channel |
|---------------|---------------|------------------|-----------|---------|
| **B2B Key Accounts** | Within 30 min | Every 4 hours | Immediate | Phone + Email |
| **B2B General** | Within 1 hour | Every 8 hours | Immediate | Email + WhatsApp |
| **B2C Subscribers** | Within 2 hours | Every 12 hours | Immediate | Email + SMS |
| **B2C General** | Status page | Status page | Status page | Website/App |
| **Farmers** | Within 2 hours | Every 12 hours | Immediate | Phone + Field visits |

#### 6.2.2 Customer Communication Templates

**B2B Customer Notification:**
```
Subject: Important Service Update - Smart Dairy B2B Portal

Dear [Customer Name],

We are writing to inform you of a temporary service disruption affecting our 
B2B Portal and related services.

IMPACTED SERVICES:
• [Service 1]
• [Service 2]

WHAT THIS MEANS FOR YOU:
• [Specific impact on their operations]

ALTERNATIVE ARRANGEMENTS:
• Phone orders: 09678-123456
• WhatsApp: +8801XXX-XXXXXX
• Email: orders@smartdairybd.com

YOUR ACCOUNT MANAGER: [Name] | [Phone]

We sincerely apologize for any inconvenience and appreciate your patience.

Next update: [Time]

Smart Dairy Operations Team
```

**B2C Customer Notification:**
```
Subject: Service Alert - Smart Dairy Online Services

Dear Valued Customer,

We are currently experiencing technical difficulties with our online ordering 
system. 

ALTERNATIVE WAYS TO ORDER:
📞 Phone: 09678-123456
💬 WhatsApp: +8801XXX-XXXXXX
📧 Email: orders@smartdairybd.com

Your subscriptions will be fulfilled as normal. If you experience any issues, 
please contact us using the above channels.

Track updates: https://status.smartdairybd.com

We apologize for the inconvenience.

Smart Dairy Customer Care
```

**Farmer/Supplier Notification:**
```
Subject: Urgent: Service Update for Farm Partners

Dear Farm Partner,

This is to inform you of a temporary disruption to our digital platform.

AFFECTED SERVICES:
- Mobile app data submission
- Real-time pricing updates
- Digital payment confirmations

IMMEDIATE ACTIONS:
1. Continue milk collection and delivery as scheduled
2. Record deliveries on paper forms (attached)
3. Contact your field coordinator: [Number]
4. Emergency line: [Number]

YOUR PAYMENTS:
All payments will be processed as soon as systems are restored. 
No farmer will face payment delays due to this technical issue.

Field coordinators will visit farms within 24 hours if needed.

Thank you for your cooperation.

Smart Dairy Farm Operations
```

### 6.3 Vendor Communication

#### 6.3.1 Vendor Communication Matrix

| Vendor Type | Initial Alert | Progress Updates | Channel |
|-------------|---------------|------------------|---------|
| **Critical (Payment, Cloud)** | Immediate | Every 2 hours | Direct call + Email |
| **Important (Logistics, Suppliers)** | Within 2 hours | Every 8 hours | Email + Phone |
| **Standard (Services, Maintenance)** | Within 4 hours | Daily | Email |

#### 6.3.2 Vendor Communication Template

```
Subject: Business Continuity Activation - [Vendor Name]

Dear [Vendor Contact],

Due to [INCIDENT TYPE], Smart Dairy Ltd. has activated its Business Continuity Plan.

IMPACT ON OUR PARTNERSHIP:
[Description of how the incident affects vendor relationship]

REQUIRED ACTIONS:
[Specific requests from the vendor]

ESCALATION CONTACT:
[Name] | [Phone] | [Email]

We appreciate your continued support during this time.

Smart Dairy Procurement Team
```

### 6.4 Media Relations

#### 6.4.1 Media Response Protocol

| Scenario | Response Level | Approval Required | Response Time |
|----------|----------------|-------------------|---------------|
| Media inquiry about incident | Standard | Crisis Director | 2 hours |
| Social media viral issue | Elevated | Crisis Director | 30 minutes |
| Major public impact | Critical | Managing Director | Immediate |
| Regulatory inquiry | Critical | Managing Director + Legal | Immediate |

#### 6.4.2 Media Statement Template

```
FOR IMMEDIATE RELEASE

Smart Dairy Ltd. Statement on [Incident]

[Date], Dhaka, Bangladesh

Smart Dairy Ltd. is currently addressing a technical issue affecting 
[brief description of impact].

We have activated our business continuity procedures and are working 
to restore full service as quickly as possible.

Customer safety and product quality remain our top priorities. 
[Additional relevant information]

We sincerely apologize for any inconvenience caused and appreciate 
our customers' patience.

For updates, please visit: https://status.smartdairybd.com

Contact: [PR Contact Name] | [Phone] | [Email]

###
```

---

## 7. RESOURCE REQUIREMENTS

### 7.1 Personnel Requirements

#### 7.1.1 Minimum Staffing Levels

| Function | Normal Staff | Minimum BCP Staff | Shift Pattern |
|----------|--------------|-------------------|---------------|
| **Management** | 5 | 2 | 24/7 on-call |
| **IT Operations** | 8 | 4 | 24/7 coverage |
| **Customer Service** | 12 | 4 | Extended hours |
| **Farm Operations** | 25 | 15 | Normal shifts |
| **Warehouse** | 10 | 5 | Reduced hours |
| **Logistics** | 8 | 4 | Essential only |
| **Finance** | 6 | 2 | Business hours |
| **Sales** | 10 | 2 | Essential only |

#### 7.1.2 Personnel Backup Matrix

| Role | Primary | Backup 1 | Backup 2 | Cross-Training Status |
|------|---------|----------|----------|----------------------|
| BCP Coordinator | Operations Mgr | IT Manager | Managing Director | Annual training |
| Technical Lead | Head of Eng | Senior DevOps | Cloud Architect | Quarterly drills |
| DBA | DBA Lead | Senior DBA | Vendor Support | Annual training |
| Farm Manager | Farm Mgr | Asst Farm Mgr | Regional Sup | Bi-annual training |
| CS Manager | CS Mgr | Team Lead | Senior Agent | Annual training |

### 7.2 Technology Requirements

#### 7.2.1 Critical Technology Resources

| Resource | Primary | Backup | Recovery Method |
|----------|---------|--------|-----------------|
| **Internet** | ISP-1 (BTCL) | ISP-2 (AmberIT) + 4G | Automatic failover |
| **Power** | Grid + UPS | Diesel Generator | Automatic switch |
| **Cloud Platform** | AWS Mumbai | AWS Singapore | DNS failover |
| **Database** | Primary RDS | Standby RDS | Automatic promotion |
| **DNS** | Route 53 | Cloudflare | Health-based routing |
| **CDN** | Cloudflare | AWS CloudFront | Dual setup |
| **Communication** | MS Teams | WhatsApp + Phone | Parallel setup |

#### 7.2.2 Equipment Requirements

| Equipment | Normal Qty | BCP Reserve | Location |
|-----------|------------|-------------|----------|
| **Laptops** | 50 | 10 | Secure storage |
| **Mobile Phones** | 30 | 10 | Distributed |
| **4G/5G Routers** | 5 | 5 | Emergency kit |
| **Power Banks** | 20 | 30 | Emergency kit |
| **Two-Way Radios** | 10 | 10 | Farm office |
| **Backup Tapes** | - | Full set | Offsite vault |
| **Manual Forms** | - | 1-month supply | Various locations |

### 7.3 Facilities Requirements

| Facility | Primary | Alternate | Capacity | Equipment |
|----------|---------|-----------|----------|-----------|
| **Command Center** | HQ 3rd Floor | Smart Group Office | 12 people | Full AV, network |
| **IT Operations** | Server Room | DR Site | 4 people | Remote access |
| **Customer Service** | Call Center | WFH setup | 4 agents | Soft phone, VPN |
| **Farm Operations** | Farm Office | Field stations | 15 staff | Radios, tablets |
| **Warehouse** | Main DC | Secondary DC | 50% capacity | Manual tools |

### 7.4 Supplies Requirements

| Supply Category | Normal Stock | BCP Reserve | Supplier |
|-----------------|--------------|-------------|----------|
| **Paper Forms** | 1 month | 3 months | Local vendor |
| **Printer Cartridges** | 2 weeks | 2 months | IT supplier |
| **Stationery** | 1 month | 3 months | Office supply |
| **Fuel (Generator)** | 48 hours | 1 week | Fuel supplier |
| **Food/Water (Emergency)** | - | 3 days | Grocery vendor |
| **First Aid** | Standard | Enhanced | Medical supplier |
| **PPE** | Standard | Emergency kit | Safety supplier |

---

## 8. RECOVERY PHASES

### 8.1 Emergency Response Phase (0-4 Hours)

#### 8.1.1 Hour 0-1: Detection and Assessment

| Time | Action | Responsible | Status |
|------|--------|-------------|--------|
| T+0 | Incident detected | Monitoring/On-call | ☐ |
| T+5 min | Initial severity assessment | On-call engineer | ☐ |
| T+10 min | BCP Coordinator notified | On-call engineer | ☐ |
| T+15 min | BCP activation decision | BCP Coordinator | ☐ |
| T+30 min | Crisis Director notified (if Level 1) | BCP Coordinator | ☐ |
| T+45 min | Initial situation assessment | Technical Lead | ☐ |
| T+60 min | Preliminary impact analysis complete | BCP Coordinator | ☐ |

#### 8.1.2 Hour 1-4: Mobilization and Containment

| Time | Action | Responsible | Status |
|------|--------|-------------|--------|
| T+60 min | BCP teams activated | BCP Coordinator | ☐ |
| T+90 min | Command center established | BCP Coordinator | ☐ |
| T+2 hours | Initial customer notification | Communication Lead | ☐ |
| T+2 hours | Vendor notifications sent | Operations Lead | ☐ |
| T+3 hours | Workaround procedures activated | Technical Lead | ☐ |
| T+4 hours | First recovery milestone review | BCP Coordinator | ☐ |

### 8.2 Recovery Phase (4-24 Hours)

#### 8.2.1 Critical Systems Recovery

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    CRITICAL SYSTEMS RECOVERY TIMELINE                        │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  HOURS 4-8: TIER 1 CRITICAL                                                  │
│  ─────────────────────────────                                               │
│  [ ] Payment Gateway restored                                                │
│  [ ] Customer authentication functional                                      │
│  [ ] Core database recovered                                                 │
│  [ ] Web portal accessible                                                   │
│  [ ] Mobile API responding                                                   │
│                                                                              │
│  HOURS 8-12: TIER 2 HIGH                                                     │
│  ─────────────────────                                                       │
│  [ ] Order management restored                                               │
│  [ ] Inventory system online                                                 │
│  [ ] Farm data collection resumed                                            │
│  [ ] Cold chain monitoring active                                            │
│  [ ] Delivery tracking functional                                            │
│                                                                              │
│  HOURS 12-24: TIER 3 MEDIUM                                                  │
│  ──────────────────────────                                                  │
│  [ ] B2B portal restored                                                     │
│  [ ] Reporting systems online                                                │
│  [ ] Analytics platform recovered                                            │
│  [ ] Customer service tools restored                                         │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### 8.2.2 Recovery Checkpoints

| Checkpoint | Time | Criteria | Go/No-Go Decision |
|------------|------|----------|-------------------|
| **CP1** | 4 hours | Critical systems identified | Continue recovery |
| **CP2** | 8 hours | Tier 1 systems operational | Proceed to Tier 2 |
| **CP3** | 12 hours | Tier 2 systems operational | Proceed to Tier 3 |
| **CP4** | 24 hours | All critical functions restored | Begin restoration phase |

### 8.3 Restoration Phase (24-72 Hours)

#### 8.3.1 Service Validation

| System | Validation Test | Acceptance Criteria | Sign-off |
|--------|-----------------|---------------------|----------|
| **Payment Processing** | Test transaction | Success, <2s response | Finance Lead |
| **Order Management** | End-to-end order | Complete flow success | Operations Lead |
| **Customer Auth** | Login test | <1s authentication | Technical Lead |
| **Farm Data** | Sensor reading | Accurate data received | Farm Operations |
| **Cold Chain** | Temperature alert | Alert received | Operations Lead |
| **Inventory** | Stock check | Accurate quantities | Warehouse Mgr |
| **Delivery** | Route optimization | Optimal route generated | Logistics Mgr |

#### 8.3.2 Data Reconciliation

| Data Type | Reconciliation Method | Responsible | Timeline |
|-----------|----------------------|-------------|----------|
| **Transactions** | Compare manual logs vs system | Finance | 24 hours |
| **Orders** | Reconcile paper forms with database | Operations | 24 hours |
| **Inventory** | Physical count vs system | Warehouse | 48 hours |
| **Farm Data** | Manual sheets vs digital records | Farm Mgr | 48 hours |
| **Customer Data** | Validate against backups | DBA | 24 hours |

### 8.4 Normalization Phase (72+ Hours)

#### 8.4.1 Return to Normal Operations

| Step | Activity | Verification | Timeline |
|------|----------|--------------|----------|
| 1 | Primary site readiness check | Infrastructure validation | Day 3-4 |
| 2 | Data synchronization | RPO compliance check | Day 4-5 |
| 3 | Failback execution | Service continuity during transition | Day 5-6 |
| 4 | Full functionality testing | End-to-end transaction testing | Day 6-7 |
| 5 | DNS/Traffic switch | Monitoring for 24 hours | Day 7 |
| 6 | DR site standby restoration | Standby systems validation | Day 7-8 |
| 7 | Staff return to normal locations | All personnel accounted for | Day 7-10 |

#### 8.4.2 Post-Restoration Activities

- [ ] Financial reconciliation complete
- [ ] Customer confirmation of service restoration
- [ ] Vendor notification of normal operations
- [ ] Documentation of all recovery actions
- [ ] BCP deactivation formal approval
- [ ] Post-incident review scheduled
- [ ] Lessons learned documented
- [ ] BCP updates initiated (if needed)

---

## 9. TRAINING AND TESTING

### 9.1 Training Program

#### 9.1.1 Training Schedule

| Training Type | Audience | Frequency | Duration | Method |
|---------------|----------|-----------|----------|--------|
| **BCP Awareness** | All staff | Annual | 2 hours | Online + Classroom |
| **Team Lead Training** | BCP Team | Semi-annual | 4 hours | Workshop |
| **Crisis Management** | CMT | Quarterly | 4 hours | Simulation |
| **Technical Recovery** | IT Team | Quarterly | 8 hours | Hands-on |
| **Tabletop Exercise** | All BCP roles | Semi-annual | 4 hours | Scenario-based |
| **Full BCP Drill** | All staff | Annual | Full day | Live exercise |

#### 9.1.2 Training Curriculum

| Module | Content | Target Audience |
|--------|---------|-----------------|
| **BCP Fundamentals** | Plan overview, roles, activation criteria | All staff |
| **Communication** | Notification procedures, templates | Communication Team |
| **Technical Recovery** | DR procedures, failover processes | IT Team |
| **Manual Procedures** | Paper-based processes, forms | Operations Team |
| **Crisis Leadership** | Decision-making, media handling | CMT |
| **Vendor Management** | Outsourcing activation | Procurement |

### 9.2 Testing Schedule

#### 9.2.1 Annual Testing Calendar

| Quarter | Test Type | Scope | Duration | Participants |
|---------|-----------|-------|----------|--------------|
| **Q1** | Tabletop Exercise | Scenario: Cyber attack | 4 hours | CMT + Team Leads |
| **Q2** | Technical DR Test | Failover to DR site | 8 hours | IT Team |
| **Q3** | Tabletop Exercise | Scenario: Natural disaster | 4 hours | CMT + Operations |
| **Q4** | Full BCP Drill | Multi-scenario exercise | 1 day | All staff |

#### 9.2.2 Test Scenarios

| Scenario ID | Scenario | Systems Tested | RTO Target |
|-------------|----------|----------------|------------|
| **T-001** | Data center fire | DR failover, recovery | 4 hours |
| **T-002** | Ransomware attack | Isolation, recovery, restoration | 8 hours |
| **T-003** | Pandemic outbreak | WFH activation, remote operations | 4 hours |
| **T-004** | Major flood | Alternate site, manual procedures | 8 hours |
| **T-005** | Power grid failure | Generator, UPS, graceful shutdown | 2 hours |
| **T-006** | Key personnel loss | Succession, cross-training | 4 hours |

### 9.3 Testing Procedures

#### 9.3.1 Pre-Test Preparation

- [ ] Test scenario developed and approved
- [ ] Participants notified and briefed
- [ ] Test environment prepared (if applicable)
- [ ] Observers assigned and briefed
- [ ] Success criteria defined
- [ ] Rollback procedures ready

#### 9.3.2 Test Execution

| Phase | Activities | Duration |
|-------|------------|----------|
| **Setup** | Final preparations, team assembly | 30 minutes |
| **Scenario Injection** | Incident announcement | 15 minutes |
| **Response** | Teams execute procedures | Per scenario |
| **Recovery** | Execute recovery actions | Per scenario |
| **Validation** | Verify success criteria | 30 minutes |
| **Rollback** | Return to normal state | 30 minutes |
| **Debrief** | Initial observations | 1 hour |

#### 9.3.3 Test Evaluation

| Evaluation Criteria | Pass Criteria | Score |
|---------------------|---------------|-------|
| **Activation Time** | BCP activated within 15 minutes | /10 |
| **Communication** | All notifications sent on time | /10 |
| **Recovery Time** | RTO targets met | /20 |
| **Procedure Adherence** | Procedures followed correctly | /20 |
| **Decision Quality** | Appropriate decisions made | /20 |
| **Documentation** | Actions properly documented | /10 |
| **Team Coordination** | Effective collaboration | /10 |
| **TOTAL** | **Pass: >70/100** | **/100** |

---

## 10. PLAN MAINTENANCE

### 10.1 Review Schedule

| Review Type | Frequency | Responsible | Scope |
|-------------|-----------|-------------|-------|
| **Quarterly Review** | Every quarter | BCP Coordinator | Contact lists, procedures |
| **Annual Review** | Annually | Business Continuity Mgr | Full plan review |
| **Post-Incident Review** | After each activation | BCP Coordinator | Lessons learned |
| **Post-Test Review** | After each test | Test Coordinator | Test findings |
| **Continuous Update** | Ongoing | All Team Leads | Minor updates |

### 10.2 Maintenance Triggers

| Trigger | Action Required | Timeline |
|---------|-----------------|----------|
| **Organizational Change** | Update roles, contacts | Within 1 week |
| **Technology Change** | Update technical procedures | Before go-live |
| **Vendor Change** | Update vendor contacts, SLAs | Within 2 weeks |
| **Regulatory Change** | Update compliance procedures | Within 1 month |
| **Facility Change** | Update site information | Before occupancy |
| **Post-Incident** | Update based on lessons learned | Within 2 weeks |
| **Post-Test** | Update based on test findings | Within 2 weeks |

### 10.3 Version Control

| Version | Date | Author | Changes | Approval |
|---------|------|--------|---------|----------|
| 1.0 | Jan 31, 2026 | Business Continuity Mgr | Initial version | Managing Director |
| | | | | |

### 10.4 Distribution List

| Recipient | Format | Location | Update Method |
|-----------|--------|----------|---------------|
| **Managing Director** | Hard + Digital | Office + Secure cloud | Direct handover |
| **BCP Coordinator** | Hard + Digital | Office + Secure cloud | Direct handover |
| **Department Heads** | Digital | Secure shared drive | Email notification |
| **BCP Team** | Digital | Secure shared drive | Email notification |
| **All Staff** | Summary | Intranet | Intranet update |
| **Offsite Storage** | Hard copy | Secure offsite | Annual replacement |

---

## 11. APPENDICES

### Appendix A: BCP Activation Workflow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    BCP ACTIVATION WORKFLOW - DETAILED                        │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  START                                                                       │
│    │                                                                         │
│    ▼                                                                         │
│  ┌─────────────────────────┐                                                 │
│  │  INCIDENT DETECTED      │                                                 │
│  │  (Monitoring or Manual) │                                                 │
│  └────────────┬────────────┘                                                 │
│               │                                                              │
│               ▼                                                              │
│  ┌─────────────────────────┐                                                 │
│  │  INITIAL ASSESSMENT     │                                                 │
│  │  • Severity             │                                                 │
│  │  • Impact scope         │                                                 │
│  │  • Activation criteria  │                                                 │
│  └────────────┬────────────┘                                                 │
│               │                                                              │
│    ┌──────────┴──────────┐                                                   │
│    │                     │                                                   │
│    ▼                     ▼                                                   │
│ ┌───────┐          ┌──────────┐                                              │
│ │ NO    │          │   YES    │                                              │
│ │ BCP   │          │ ACTIVATE │                                              │
│ │       │          │   BCP    │                                              │
│ └───┬───┘          └────┬─────┘                                              │
│     │                   │                                                    │
│     ▼                   ▼                                                    │
│ ┌─────────────────┐  ┌─────────────────┐                                     │
│ │ STANDARD        │  │ NOTIFY BCP      │                                     │
│ │ INCIDENT MGMT   │  │ COORDINATOR     │                                     │
│ │ (L-003)         │  │                 │                                     │
│ └─────────────────┘  └────────┬────────┘                                     │
│                               │                                              │
│                               ▼                                              │
│                    ┌─────────────────┐                                       │
│                    │ DETERMINE       │                                       │
│                    │ AUTHORITY LEVEL │                                       │
│                    │ (Section 4.2)   │                                       │
│                    └────────┬────────┘                                       │
│                             │                                                │
│              ┌──────────────┼──────────────┐                                │
│              │              │              │                                │
│              ▼              ▼              ▼                                │
│        ┌─────────┐   ┌─────────┐   ┌─────────────┐                         │
│        │ LEVEL 3 │   │ LEVEL 2 │   │ LEVEL 1     │                         │
│        │ Partial │   │ Full    │   │ Full + Exec │                         │
│        │ BCP     │   │ BCP     │   │             │                         │
│        └────┬────┘   └────┬────┘   └──────┬──────┘                         │
│             │             │               │                                 │
│             ▼             ▼               ▼                                 │
│        ┌────────────────────────────────────────┐                          │
│        │ ACTIVATE RELEVANT TEAMS                │                          │
│        │ (Section 3)                            │                          │
│        └───────────────────┬────────────────────┘                          │
│                            │                                                │
│                            ▼                                                │
│                   ┌─────────────────┐                                        │
│                   │ ESTABLISH       │                                        │
│                   │ COMMAND CENTER  │                                        │
│                   │ (Physical/Virtual)│                                       │
│                   └────────┬────────┘                                        │
│                            │                                                │
│                            ▼                                                │
│                   ┌─────────────────┐                                        │
│                   │ EXECUTE         │                                        │
│                   │ CONTINUITY      │                                        │
│                   │ PROCEDURES      │                                        │
│                   │ (Section 5)     │                                        │
│                   └────────┬────────┘                                        │
│                            │                                                │
│                            ▼                                                │
│                   ┌─────────────────┐                                        │
│                   │ EXECUTE         │                                        │
│                   │ RECOVERY        │                                        │
│                   │ PROCEDURES      │                                        │
│                   │ (Section 8)     │                                        │
│                   └────────┬────────┘                                        │
│                            │                                                │
│                            ▼                                                │
│                   ┌─────────────────┐                                        │
│                   │ BCP DEACTIVATION│                                        │
│                   │ (Crisis Director│                                        │
│                   │  approval)      │                                        │
│                   └────────┬────────┘                                        │
│                            │                                                │
│                            ▼                                                │
│                   ┌─────────────────┐                                        │
│                   │ POST-INCIDENT   │                                        │
│                   │ REVIEW          │                                        │
│                   │ (Section 9)     │                                        │
│                   └────────┬────────┘                                        │
│                            │                                                │
│                            ▼                                                │
│                          END                                                │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Appendix B: Critical Function Matrix

```
┌──────────────────────────────────────────────────────────────────────────────────────────┐
│                           CRITICAL FUNCTION MATRIX                                        │
├──────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                           │
│ Function          │ Criticality │ RTO │ RPO │ Dependencies                    │ Owner    │
│ ──────────────────┼─────────────┼─────┼─────┼─────────────────────────────────┼──────────┤
│                   │             │     │     │                                 │          │
│ Payment           │ CRITICAL    │ 4h  │ 15m │ Payment gateways, SSL, Bank APIs│ Finance  │
│ Processing        │             │     │     │ Database, Network               │ Manager  │
│                   │             │     │     │                                 │          │
│ Order Management  │ CRITICAL    │ 4h  │ 15m │ Database, Web servers,          │ Ops Mgr  │
│                   │             │     │     │ Mobile APIs                     │          │
│                   │             │     │     │                                 │          │
│ Customer Auth     │ CRITICAL    │ 4h  │ 15m │ Auth service, Database,         │ Tech Lead│
│                   │             │     │     │ SMS gateway                     │          │
│                   │             │     │     │                                 │          │
│ Milk Collection   │ HIGH        │ 8h  │ 1h  │ Farm IoT, Mobile network,       │ Farm Mgr │
│ Recording         │             │     │     │ Database                        │          │
│                   │             │     │     │                                 │          │
│ Cold Chain        │ HIGH        │ 8h  │ 1h  │ Temperature sensors, Alert      │ Ops Mgr  │
│ Monitoring        │             │     │     │ systems, Database               │          │
│                   │             │     │     │                                 │          │
│ Inventory         │ HIGH        │ 8h  │ 1h  │ Warehouse systems, Database,    │ Warehouse│
│ Management        │             │     │     │ IoT                             │ Mgr      │
│                   │             │     │     │                                 │          │
│ Delivery Tracking │ HIGH        │ 8h  │ 1h  │ GPS systems, Mobile network,    │ Logistics│
│                   │             │     │     │ Database                        │ Mgr      │
│                   │             │     │     │                                 │          │
│ Farm Data         │ HIGH        │ 8h  │ 1h  │ IoT gateway, Data pipeline,     │ Farm Mgr │
│ Collection        │             │     │     │ Database                        │          │
│                   │             │     │     │                                 │          │
│ B2B Portal        │ MEDIUM      │ 24h │ 4h  │ Database, Application servers,  │ Sales Mgr│
│                   │             │     │     │ APIs                            │          │
│                   │             │     │     │                                 │          │
│ Reporting &       │ MEDIUM      │ 24h │ 4h  │ Data warehouse, BI tools,       │ Finance  │
│ Analytics         │             │     │     │ Database                        │ Mgr      │
│                   │             │     │     │                                 │          │
│ Marketing         │ LOW         │ 72h │ 24h │ Email service, SMS gateway,     │ Marketing│
│ Campaigns         │             │     │     │ Database                        │ Mgr      │
│                   │             │     │     │                                 │          │
│ Dev/Test          │ LOW         │ 72h │ 24h │ Development tools, Test data,   │ Tech Lead│
│ Environments      │             │     │     │ Infrastructure                  │          │
│                   │             │     │     │                                 │          │
└──────────────────────────────────────────────────────────────────────────────────────────┘
```

### Appendix C: Communication Templates

#### Template C-1: Initial BCP Activation Internal

```
PRIORITY: HIGH
FROM: BCP Coordinator
TO: All Staff
SUBJECT: BCP ACTIVATED - [Incident Type]

Business Continuity Plan has been ACTIVATED.

INCIDENT SUMMARY:
• Type: [Description]
• Detected: [Date/Time]
• Severity: [Level]
• Expected Duration: [Estimate]

YOUR ACTIONS:
1. Check your phone/email every 2 hours for updates
2. Do not discuss on social media
3. Wait for instructions from your Department Head
4. If working remotely, ensure VPN is connected

NEXT UPDATE: [Time]

BCP COORDINATOR: [Name] | [Phone] | [WhatsApp]
```

#### Template C-2: Customer Service Disruption

```
Subject: Service Alert - Smart Dairy [Service Name]

Dear Valued Customer,

We are currently experiencing technical difficulties with our [Service Name].

WHAT WE ARE DOING:
• Our technical team is actively working on resolution
• We have activated our business continuity procedures
• Updates will be provided every [X] hours

ALTERNATIVE ARRANGEMENTS:
📞 Phone orders: 09678-123456
💬 WhatsApp: +8801XXX-XXXXXX
📧 Email: orders@smartdairybd.com

Track status: https://status.smartdairybd.com

We apologize for any inconvenience.

Smart Dairy Team
```

#### Template C-3: All Clear Notification

```
Subject: [RESOLVED] Service Restored - Smart Dairy

Dear [Customer/Staff],

We are pleased to confirm that all services have been restored and are 
operating normally.

RESOLUTION DETAILS:
• Incident resolved: [Date/Time]
• Total duration: [Duration]
• Cause: [Brief description]

We sincerely apologize for any inconvenience caused.

If you experience any issues, please contact:
support@smartdairybd.com | 09678-123456

Thank you for your patience.

Smart Dairy Operations Team
```

#### Template C-4: Vendor Notification

```
Subject: Business Continuity Activation - [Vendor Name]

Dear [Contact Name],

Due to [INCIDENT TYPE], Smart Dairy has activated its Business Continuity Plan.

IMPACT:
[Description of impact on vendor relationship]

ACTIONS REQUIRED:
[List specific actions]

ESCALATION CONTACT:
[Name] | [Phone] | [Email]

Expected resolution: [Timeline]

Thank you for your support.

Smart Dairy Procurement Team
```

### Appendix D: Testing Schedule

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         ANNUAL BCP TESTING SCHEDULE                          │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Q1 (JAN-MAR)                                                               │
│  ─────────────                                                              │
│  Month: January                                                             │
│  • Review previous year's test results                                      │
│  • Update test scenarios                                                    │
│  • Schedule tabletop exercise                                               │
│                                                                              │
│  Month: February                                                            │
│  • Tabletop Exercise: Cyber Attack Scenario                                 │
│  • Participants: CMT + Team Leads                                           │
│  • Duration: 4 hours                                                        │
│                                                                              │
│  Month: March                                                               │
│  • Document tabletop exercise results                                       │
│  • Update BCP based on findings                                             │
│                                                                              │
│  ────────────────────────────────────────────────────────────────────────   │
│                                                                              │
│  Q2 (APR-JUN)                                                               │
│  ─────────────                                                              │
│  Month: April                                                               │
│  • Technical DR test planning                                               │
│  • Prepare test environment                                                 │
│                                                                              │
│  Month: May                                                                 │
│  • Technical DR Test: Failover to DR site                                   │
│  • Participants: IT Team                                                    │
│  • Duration: 8 hours                                                        │
│                                                                              │
│  Month: June                                                                │
│  • Document DR test results                                                 │
│  • Update technical recovery procedures                                     │
│                                                                              │
│  ────────────────────────────────────────────────────────────────────────   │
│                                                                              │
│  Q3 (JUL-SEP)                                                               │
│  ─────────────                                                              │
│  Month: July                                                                │
│  • Review first half results                                                │
│  • Plan tabletop exercise                                                   │
│                                                                              │
│  Month: August                                                              │
│  • Tabletop Exercise: Natural Disaster Scenario                             │
│  • Participants: CMT + Operations                                           │
│  • Duration: 4 hours                                                        │
│                                                                              │
│  Month: September                                                           │
│  • Document tabletop exercise results                                       │
│  • Update operational procedures                                            │
│                                                                              │
│  ────────────────────────────────────────────────────────────────────────   │
│                                                                              │
│  Q4 (OCT-DEC)                                                               │
│  ─────────────                                                              │
│  Month: October                                                             │
│  • Full BCP drill planning                                                  │
│  • Scenario development                                                     │
│  • Participant notification                                                 │
│                                                                              │
│  Month: November                                                            │
│  • Full BCP Drill: Multi-scenario exercise                                  │
│  • Participants: All staff                                                  │
│  • Duration: Full day                                                       │
│                                                                              │
│  Month: December                                                            │
│  • Annual BCP review                                                        │
│  • Document all test results                                                │
│  • Update BCP for next year                                                 │
│  • Plan next year's testing schedule                                        │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Appendix E: Contact Lists

#### E.1 Crisis Management Team Contacts

| Role | Name | Primary Phone | Secondary Phone | Email | WhatsApp |
|------|------|---------------|-----------------|-------|----------|
| Crisis Director | [Name] | +880-XXXX-XXXXXX | +880-XXXX-XXXXXX | [email] | [number] |
| BCP Coordinator | [Name] | +880-XXXX-XXXXXX | +880-XXXX-XXXXXX | [email] | [number] |
| Technical Lead | [Name] | +880-XXXX-XXXXXX | +880-XXXX-XXXXXX | [email] | [number] |
| Operations Lead | [Name] | +880-XXXX-XXXXXX | +880-XXXX-XXXXXX | [email] | [number] |
| Communication Lead | [Name] | +880-XXXX-XXXXXX | +880-XXXX-XXXXXX | [email] | [number] |

#### E.2 Emergency Services

| Service | Organization | Contact | Phone |
|---------|--------------|---------|-------|
| Fire Department | Bangladesh Fire Service | Emergency | 999 |
| Police | Local Police Station | Emergency | 999 |
| Ambulance | Emergency Services | Emergency | 999 |
| Cyber Security | Bangladesh Cyber Security Agency | CERT | cert@bccs.gov.bd |
| Disaster Management | DDM | Control Room | +880-2-XXXX-XXXX |

#### E.3 Critical Vendors

| Vendor | Service | Primary Contact | Emergency Phone | Escalation |
|--------|---------|-----------------|-----------------|------------|
| AWS | Cloud Infrastructure | Enterprise Support | [Hotline] | TAM |
| bKash | Payment Gateway | Account Manager | [Number] | Head of Ops |
| [ISP-1] | Internet | Support | [Number] | Technical Director |
| [ISP-2] | Internet | Support | [Number] | Technical Director |
| [3PL] | Logistics | Operations | [Number] | MD |

---

**END OF BUSINESS CONTINUITY PROCEDURES**

---

| Document ID | Version | Date | Owner | Next Review |
|-------------|---------|------|-------|-------------|
| L-016 | 1.0 | January 31, 2026 | Business Continuity Manager | January 31, 2027 |

---

*This document is the property of Smart Dairy Ltd. and is classified as CONFIDENTIAL. Unauthorized distribution is prohibited.*
