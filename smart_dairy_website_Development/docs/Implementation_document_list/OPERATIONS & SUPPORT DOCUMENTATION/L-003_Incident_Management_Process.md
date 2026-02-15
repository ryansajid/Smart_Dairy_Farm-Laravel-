# SMART DAIRY LTD.
## INCIDENT MANAGEMENT PROCESS
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | L-003 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | IT Operations Manager |
| **Owner** | IT Operations Manager |
| **Reviewer** | IT Director |
| **Classification** | INTERNAL - OPERATIONS |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Incident Definition and Types](#2-incident-definition-and-types)
3. [Incident Severity Levels](#3-incident-severity-levels)
4. [Incident Lifecycle](#4-incident-lifecycle)
5. [Roles and Responsibilities](#5-roles-and-responsibilities)
6. [Communication Plan](#6-communication-plan)
7. [Major Incident Process](#7-major-incident-process)
8. [War Room Procedures](#8-war-room-procedures)
9. [Post-Incident Review](#9-post-incident-review)
10. [Metrics and KPIs](#10-metrics-and-kpis)
11. [Integration with Problem Management](#11-integration-with-problem-management)
12. [Appendices](#12-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This Incident Management Process document establishes the standardized procedures for managing and resolving incidents within the Smart Dairy Smart Web Portal System and Integrated ERP. The process ensures rapid restoration of normal service operations while minimizing business impact.

### 1.2 Scope

This process applies to:
- All production systems and services (Tier 1, Tier 2, Tier 3)
- Infrastructure components (servers, network, databases)
- Application services (ERP, B2B Portal, B2C E-commerce)
- IoT infrastructure and connected devices
- Third-party integrations and services
- Security-related service disruptions

### 1.3 Objectives

| Objective | Target |
|-----------|--------|
| Minimize business disruption | Restore service within SLA targets |
| Ensure consistent response | Standardized procedures across all incidents |
| Improve customer satisfaction | Transparent communication and rapid resolution |
| Enable continuous improvement | Learn from every incident |
| Maintain service quality | Prevent recurrence of similar incidents |

### 1.4 Document Hierarchy

```
Service Level Agreement (L-001)
    └── Incident Management Process (L-003) ← THIS DOCUMENT
            ├── Incident Response Plan (F-011)
            ├── Service Desk Procedures
            ├── Escalation Procedures
            └── Communication Templates
```

---

## 2. INCIDENT DEFINITION AND TYPES

### 2.1 What is an Incident?

An **incident** is an unplanned interruption to or reduction in the quality of an IT service. This includes events that disrupt or could disrupt normal service operations, resulting in or potentially resulting in business impact.

### 2.2 Incident Types

#### 2.2.1 Service Disruption

| Sub-Type | Description | Examples |
|----------|-------------|----------|
| **Complete Outage** | Total unavailability of service | ERP system down, B2B portal inaccessible |
| **Partial Outage** | Some functionality unavailable | Payment processing failure, report generation unavailable |
| **Intermittent Issues** | Service flickering or unstable | Connection drops, timeout errors |
| **Scheduled Service Failure** | Service degradation during maintenance | Backup operations causing slowdowns |

#### 2.2.2 Security Incidents

| Sub-Type | Description | Examples |
|----------|-------------|----------|
| **Data Breach** | Unauthorized data access/exposure | Customer data exposure, credential theft |
| **Malware Infection** | Virus, ransomware, trojans | Ransomware on farm management workstations |
| **Unauthorized Access** | Account compromise | Admin account takeover, insider threat |
| **DDoS Attack** | Denial of service attacks | Volumetric attacks on e-commerce portal |
| **Web Application Attacks** | Exploitation of application vulnerabilities | SQL injection, XSS attacks |

#### 2.2.3 Performance Degradation

| Sub-Type | Description | Examples |
|----------|-------------|----------|
| **Slow Response Times** | Performance below SLA thresholds | Page load > 10 seconds, API latency high |
| **Resource Exhaustion** | CPU, memory, or storage issues | Database connection pool exhausted |
| **Throughput Issues** | Unable to handle expected load | Order processing bottleneck during peak hours |
| **Queue Buildup** | Message or job queue overflow | Background job queue growing uncontrollably |

#### 2.2.4 Data Loss/Corruption

| Sub-Type | Description | Examples |
|----------|-------------|----------|
| **Accidental Deletion** | Unintentional data removal | Deleted customer records, removed configurations |
| **Data Corruption** | Data integrity compromised | Corrupted milk production records |
| **Replication Failures** | Data synchronization issues | Primary-replica lag causing stale data |
| **Backup Failures** | Inability to restore from backup | Corrupted backup files, missing restore points |

### 2.3 Incident vs. Problem vs. Change

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    INCIDENT vs PROBLEM vs CHANGE                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  INCIDENT                    PROBLEM                      CHANGE             │
│  ─────────                   ───────                      ──────             │
│  "Service is down"           "Why did it happen?"         "Fix the cause"    │
│  Restore service NOW         Root cause analysis          Planned fix        │
│  Immediate action required   Long-term resolution         Controlled timing  │
│  Incident Management         Problem Management           Change Management  │
│                                                                              │
│  Example Flow:                                                               │
│  1. Website is slow → INCIDENT (Fix: Restart server)                        │
│  2. Why was it slow? → PROBLEM (Memory leak identified)                     │
│  3. Deploy patch → CHANGE (Code fix during maintenance window)              │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 3. INCIDENT SEVERITY LEVELS

### 3.1 Severity Matrix

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        INCIDENT SEVERITY MATRIX                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│                     IMPACT SCOPE                                            │
│                  Low        Medium      High        Critical                │
│              ┌─────────┬───────────┬───────────┬─────────────┐              │
│    Critical  │  SEV 3  │   SEV 2   │   SEV 1   │    SEV 1    │              │
│   URGENCY    │         │           │           │             │              │
│    High      │  SEV 3  │   SEV 3   │   SEV 2   │    SEV 1    │              │
│              ├─────────┼───────────┼───────────┼─────────────┤              │
│    Medium    │  SEV 4  │   SEV 3   │   SEV 3   │    SEV 2    │              │
│              ├─────────┼───────────┼───────────┼─────────────┤              │
│    Low       │  SEV 4  │   SEV 4   │   SEV 3   │    SEV 3    │              │
│              └─────────┴───────────┴───────────┴─────────────┘              │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Severity Level Definitions

#### SEV 1 - Critical (Production Down)

| Attribute | Definition |
|-----------|------------|
| **Impact** | Complete service outage affecting all users or critical business functions |
| **Urgency** | Immediate business impact, revenue loss, or safety concerns |
| **Response Time** | 15 minutes |
| **Resolution Target** | 4 hours |
| **Examples** | ERP completely down, payment processing failure, all IoT devices offline, data breach in progress |
| **Escalation** | CEO notification within 1 hour |

#### SEV 2 - Major (Major Feature Impacted)

| Attribute | Definition |
|-----------|------------|
| **Impact** | Significant functionality impaired affecting many users |
| **Urgency** | High business impact, workaround may exist |
| **Response Time** | 1 hour |
| **Resolution Target** | 8 hours |
| **Examples** | B2B ordering down, mobile apps not syncing, major reports failing, performance severely degraded |
| **Escalation** | CTO notification within 2 hours |

#### SEV 3 - Minor (Limited Impact)

| Attribute | Definition |
|-----------|------------|
| **Impact** | Limited functionality affected, subset of users impacted |
| **Urgency** | Moderate impact, workarounds available |
| **Response Time** | 4 hours |
| **Resolution Target** | 3 days |
| **Examples** | Single feature not working, specific report errors, minor UI issues affecting some users |
| **Escalation** | Team Lead notification |

#### SEV 4 - Low (Minimal Impact)

| Attribute | Definition |
|-----------|------------|
| **Impact** | Minimal or no business impact |
| **Urgency** | Low priority, cosmetic or enhancement issues |
| **Response Time** | 24 hours |
| **Resolution Target** | 2 weeks |
| **Examples** | Typos, cosmetic UI issues, enhancement requests, documentation updates |
| **Escalation** | No escalation required |

### 3.3 Severity Determination Guide

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SEVERITY DETERMINATION FLOWCHART                          │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  START                                                                       │
│    │                                                                         │
│    ▼                                                                         │
│  Is production completely down? ──YES──▶ SEV 1                               │
│    │ NO                                                                      │
│    ▼                                                                         │
│  Is there an active security breach? ──YES──▶ SEV 1                          │
│    │ NO                                                                      │
│    ▼                                                                         │
│  Is revenue being lost RIGHT NOW? ──YES──▶ SEV 1                             │
│    │ NO                                                                      │
│    ▼                                                                         │
│  Is a major feature unavailable for many users? ──YES──▶ SEV 2               │
│    │ NO                                                                      │
│    ▼                                                                         │
│  Is performance severely degraded? ──YES──▶ SEV 2                            │
│    │ NO                                                                      │
│    ▼                                                                         │
│  Is a feature unavailable with workaround? ──YES──▶ SEV 3                    │
│    │ NO                                                                      │
│    ▼                                                                         │
│  SEV 4                                                                       │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 4. INCIDENT LIFECYCLE

### 4.1 Incident Response Workflow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      INCIDENT RESPONSE WORKFLOW                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐               │
│  │          │    │          │    │          │    │          │               │
│  │DETECTION │───▶│ LOGGING  │───▶│ DIAGNOSIS│───▶│ESCALATION│               │
│  │          │    │          │    │          │    │(if needed)│               │
│  └──────────┘    └──────────┘    └──────────┘    └────┬─────┘               │
│       │                                               │                      │
│       │         ┌──────────────┐                     │                      │
│       │         │              │◀────────────────────┘                      │
│       │         ▼              │                                            │
│       │    ┌──────────┐        │                                            │
│       └───▶│ CLOSURE  │◀───────┤                                            │
│            │          │        │                                            │
│            └────┬─────┘        │                                            │
│                 │              │                                            │
│                 ▼              │                                            │
│            ┌──────────┐        │                                            │
│            │POST-     │◀───────┘                                            │
│            │INCIDENT  │                                                     │
│            │REVIEW    │                                                     │
│            └──────────┘                                                     │
│                                                                              │
│  Resolution Path: ESCALATION ──▶ RESOLUTION ──▶ CLOSURE                     │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 4.2 Detection

#### 4.2.1 Detection Sources

| Source | Method | Coverage |
|--------|--------|----------|
| **Automated Monitoring** | Uptime Kuma, CloudWatch alerts | Infrastructure, services |
| **Application Monitoring** | APM tools (New Relic/Datadog) | Application performance |
| **Log Analysis** | ELK Stack, Splunk | Error patterns, anomalies |
| **User Reports** | Support portal, phone, email | End-user issues |
| **IoT Alerts** | MQTT monitoring, sensor thresholds | Farm equipment, sensors |
| **Security Monitoring** | SIEM, IDS/IPS | Security incidents |
| **Scheduled Checks** | Automated health checks | Proactive detection |

#### 4.2.2 Detection Checklist

- [ ] Alert received from monitoring system OR user report submitted
- [ ] Initial validation that this is a genuine incident (not false positive)
- [ ] Scope assessment - how many users/systems affected?
- [ ] Initial severity assessment
- [ ] Incident Commander notified (for SEV 1/2)

### 4.3 Logging

#### 4.3.1 Incident Ticket Requirements

| Field | Required | Description |
|-------|----------|-------------|
| **Incident ID** | Auto | IMP-YYYY-MM-NNNN format |
| **Title** | Yes | Brief description of the issue |
| **Severity** | Yes | SEV 1/2/3/4 |
| **Status** | Yes | New, In Progress, Resolved, Closed |
| **Detection Time** | Auto | Timestamp when incident detected |
| **Reporter** | Yes | Who reported the incident |
| **Affected Services** | Yes | List of impacted systems |
| **Description** | Yes | Detailed description of symptoms |
| **Business Impact** | Yes | Impact on operations, customers, revenue |
| **Assigned To** | Yes | Initial assignee |

#### 4.3.2 Incident Logging Template

```
INCIDENT ID: IMP-2026-01-0001
TITLE: [SEV 1] ERP Production Database Connection Failure

DETECTION:
- Time Detected: 2026-01-31 14:30:00 IST
- Detection Method: Automated Alert (Uptime Kuma)
- Reporter: monitoring@smartdairy.com

AFFECTED SERVICES:
- ERP Core System (Tier 1)
- B2B Portal (Tier 1)
- Farm Management Module (Tier 1)

IMPACT ASSESSMENT:
- Users Affected: ~150 internal users
- Business Functions: Order processing halted, milk collection recording stopped
- Estimated Revenue Impact: BDT 500,000/hour
- Customer Impact: B2B customers unable to place orders

INITIAL SYMPTOMS:
- Database connection timeout errors
- Application returning 500 errors
- Load balancer health checks failing

SEVERITY: SEV 1 - Critical
ASSIGNED TO: Database Administrator (L2)
```

### 4.4 Initial Diagnosis

#### 4.4.1 Diagnosis Steps

| Step | Action | Time Limit |
|------|--------|------------|
| 1 | Acknowledge incident and confirm receipt | 15 minutes |
| 2 | Gather initial information (logs, metrics, recent changes) | 30 minutes |
| 3 | Determine if issue is known (check knowledge base) | 15 minutes |
| 4 | Identify potential root cause | 1 hour |
| 5 | Assess if escalation is needed | 30 minutes |
| 6 | Determine resolution approach | 30 minutes |

#### 4.4.2 Diagnosis Checklist

**Infrastructure Issues:**
- [ ] Check system resource utilization (CPU, memory, disk, network)
- [ ] Review recent infrastructure changes/deployments
- [ ] Verify connectivity (network, database, external services)
- [ ] Check for hardware failures

**Application Issues:**
- [ ] Review application error logs
- [ ] Check recent code deployments
- [ ] Verify configuration settings
- [ ] Review database query performance

**Security Issues:**
- [ ] Isolate affected systems
- [ ] Preserve evidence (logs, memory dumps)
- [ ] Assess scope of compromise
- [ ] Check for ongoing malicious activity

### 4.5 Escalation

#### 4.5.1 Escalation Triggers

| Trigger | Action | Timeline |
|---------|--------|----------|
| Initial diagnosis inconclusive | Escalate to next support level | 30 minutes |
| Resolution time exceeds 50% of target | Escalate to senior engineer | Per severity |
| Multiple systems affected | Activate Incident Manager | Immediately |
| Security incident suspected | Escalate to Security Lead | Immediately |
| Customer data potentially exposed | Escalate to Legal/Compliance | Immediately |

#### 4.5.2 Escalation Matrix

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         ESCALATION MATRIX                                    │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  SEV 1 - CRITICAL                                                            │
│  ─────────────────                                                           │
│  T+0 min    → L1 acknowledges incident                                       │
│  T+15 min   → Escalate to L2 if not resolved                                 │
│  T+30 min   → Incident Manager activated, war room opened                    │
│  T+1 hour   → Escalate to L3, CTO notified                                   │
│  T+2 hours  → Vendor escalation if applicable                                │
│  T+4 hours  → Executive escalation, consider DR activation                   │
│                                                                              │
│  SEV 2 - MAJOR                                                               │
│  ────────────────                                                            │
│  T+0 min    → L1 acknowledges incident                                       │
│  T+1 hour   → Escalate to L2 if not resolved                                 │
│  T+4 hours  → Escalate to L3                                                 │
│  T+8 hours  → Management notification if not resolved                        │
│                                                                              │
│  SEV 3 - MINOR                                                               │
│  ────────────────                                                            │
│  T+0 hours  → Ticket created                                                 │
│  T+4 hours  → L1 acknowledges and begins investigation                       │
│  T+24 hours → Escalate to L2 if not resolved                                 │
│                                                                              │
│  SEV 4 - LOW                                                                 │
│  ────────────                                                                │
│  T+0 hours  → Ticket created                                                 │
│  T+24 hours → L1 acknowledges                                                │
│  T+72 hours → Scheduled for resolution                                       │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 4.6 Resolution

#### 4.6.1 Resolution Approaches

| Approach | When to Use | Examples |
|----------|-------------|----------|
| **Immediate Fix** | Simple, low-risk resolution | Restart service, clear cache |
| **Workaround** | Temporary solution needed quickly | Manual process, bypass failing component |
| **Rollback** | Recent change caused issue | Revert deployment, restore previous version |
| **Configuration Change** | Settings need adjustment | Increase timeout, adjust limits |
| **Patch/Update** | Bug fix required | Apply hotfix, security patch |
| **Infrastructure Change** | Resource issue | Scale up, add capacity |

#### 4.6.2 Resolution Approval Requirements

| Action | SEV 1 | SEV 2 | SEV 3 | SEV 4 |
|--------|-------|-------|-------|-------|
| Service restart | Incident Manager | L2 Engineer | L1 Engineer | L1 Engineer |
| Configuration change | Incident Manager | L2 Engineer | L1 Engineer | L1 Engineer |
| Code deployment | CTO approval | Incident Manager | Change Advisory Board | Standard Change |
| Database modification | DBA + Incident Manager | DBA | DBA | DBA |
| Failover/DR activation | CTO approval | Incident Manager | N/A | N/A |

### 4.7 Closure

#### 4.7.1 Closure Criteria

- [ ] Service fully restored and verified operational
- [ ] All affected users notified of resolution
- [ ] Monitoring confirms stability (minimum 30 minutes)
- [ ] Incident ticket updated with resolution details
- [ ] Root cause documented (if known)
- [ ] Post-incident review scheduled (SEV 1/2)
- [ ] Customer confirmation of resolution (if applicable)

#### 4.7.2 Closure Documentation

| Field | Description |
|-------|-------------|
| **Resolution Time** | Timestamp when service restored |
| **Resolution Summary** | Brief description of fix applied |
| **Root Cause** | Identified cause (or "Under Investigation") |
| **Actions Taken** | Step-by-step resolution actions |
| **Service Restoration Verification** | How restoration was confirmed |
| **Follow-up Required** | Any pending items |
| **Lessons Learned** | Initial observations |

### 4.8 Post-Incident Review

See Section 9 for detailed post-incident review procedures.

---

## 5. ROLES AND RESPONSIBILITIES

### 5.1 Incident Management Team Structure

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    INCIDENT MANAGEMENT TEAM STRUCTURE                        │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│                    ┌───────────────────────┐                                 │
│                    │   INCIDENT MANAGER    │                                 │
│                    │   (SEV 1/2 incidents) │                                 │
│                    │   • Overall ownership  │                                 │
│                    │   • Major decisions    │                                 │
│                    │   • Stakeholder comms  │                                 │
│                    └───────────┬───────────┘                                 │
│                                │                                             │
│            ┌───────────────────┼───────────────────┐                         │
│            │                   │                   │                         │
│            ▼                   ▼                   ▼                         │
│    ┌───────────────┐   ┌───────────────┐   ┌───────────────┐                │
│    │ TECHNICAL LEAD│   │COMMUNICATIONS │   │    SME POOL   │                │
│    │               │   │     LEAD      │   │               │                │
│    │ • Technical   │   │               │   │ • Database    │                │
│    │   strategy    │   │ • Status      │   │ • Security    │                │
│    │ • Resource    │   │   updates     │   │ • Network     │                │
│    │   coordination│   │ • Stakeholder │   │ • Application │                │
│    │ • Technical   │   │   management  │   │ • Infrastructure│              │
│    │   decisions   │   │ • Customer    │   │ • Third-party │                │
│    │               │   │   comms       │   │               │                │
│    └───────────────┘   └───────────────┘   └───────────────┘                │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 5.2 Role Definitions

#### 5.2.1 Incident Manager

| Aspect | Details |
|--------|---------|
| **Primary** | IT Operations Manager |
| **Secondary** | Senior IT Operations Engineer |
| **Activation** | All SEV 1 incidents, SEV 2 at discretion |

**Responsibilities:**
- Own the incident from detection through closure
- Activate and coordinate the incident response team
- Make go/no-go decisions on major actions (failover, rollback, etc.)
- Manage stakeholder communications
- Ensure SLA compliance
- Authorize emergency changes
- Decide when to activate war room procedures

**Authority:**
- Can direct any IT resource to incident resolution
- Can approve emergency changes outside normal windows
- Can engage vendors and external resources
- Can authorize overtime and emergency procurement

#### 5.2.2 Technical Lead

| Aspect | Details |
|--------|---------|
| **Primary** | Senior System Administrator / DevOps Lead |
| **Secondary** | Infrastructure Team Lead |
| **Activation** | All SEV 1/2 incidents |

**Responsibilities:**
- Lead technical investigation and diagnosis
- Coordinate technical resources
- Make technical decisions and recommendations
- Ensure technical documentation of incident
- Oversee implementation of technical fixes
- Validate service restoration

**Authority:**
- Can direct technical team members
- Can approve technical workarounds
- Can request vendor technical support
- Can authorize service restarts (within policy)

#### 5.2.3 Communication Lead

| Aspect | Details |
|--------|---------|
| **Primary** | Customer Success Manager |
| **Secondary** | Marketing Communications Manager |
| **Activation** | All SEV 1 incidents, SEV 2 if customer-impacting |

**Responsibilities:**
- Manage all internal and external communications
- Draft and approve messaging
- Coordinate with PR/Legal on external communications
- Maintain incident status page
- Conduct stakeholder briefings
- Ensure communication templates are followed

**Authority:**
- Can approve customer communications
- Can engage PR agency
- Can authorize status page updates
- Can direct communication team resources

#### 5.2.4 Subject Matter Experts (SMEs)

| SME Type | Primary | Expertise Area |
|----------|---------|----------------|
| **Database SME** | DBA | PostgreSQL, TimescaleDB, replication |
| **Security SME** | Security Lead | Cybersecurity, forensics, compliance |
| **Network SME** | Network Engineer | VPC, WAF, CDN, connectivity |
| **Application SME** | Lead Developer | Odoo ERP, custom applications |
| **Infrastructure SME** | DevOps Engineer | Kubernetes, AWS, monitoring |
| **Integration SME** | Integration Lead | APIs, third-party services |

**Responsibilities:**
- Provide deep technical expertise in their domain
- Perform specialized diagnostic procedures
- Implement domain-specific fixes
- Advise on technical risks and mitigation
- Document domain-specific findings

### 5.3 Support Level Responsibilities

| Level | Role | Responsibilities | Escalation Trigger |
|-------|------|------------------|-------------------|
| **L1 - Help Desk** | Service Desk Analyst | Initial triage, basic troubleshooting, ticket creation | Cannot resolve within 30 min |
| **L2 - Technical** | System Administrator | Advanced troubleshooting, configuration changes | Requires code change or vendor |
| **L3 - Engineering** | Senior Developer/Architect | Code fixes, complex architecture issues | Vendor or external expertise |
| **L4 - Vendor/External** | Vendor Support / Consultants | Product-specific issues, specialized expertise | N/A |

---

## 6. COMMUNICATION PLAN

### 6.1 Communication Matrix

#### 6.1.1 Internal Communication

| Stakeholder | SEV 1 | SEV 2 | SEV 3 | SEV 4 |
|-------------|-------|-------|-------|-------|
| **CEO/MD** | Immediate | Within 2 hours | Daily digest | Weekly summary |
| **CTO** | Immediate | Within 1 hour | Daily digest | Weekly summary |
| **IT Director** | Immediate | Immediate | Daily report | Weekly summary |
| **Department Heads** | Within 1 hour | Within 4 hours | Email notification | Portal update |
| **All Employees** | Within 1 hour | Same day | As needed | N/A |
| **IR Team** | Real-time | Hourly updates | As needed | As needed |

#### 6.1.2 Stakeholder Updates

| Stakeholder | SEV 1 | SEV 2 | SEV 3 | SEV 4 |
|-------------|-------|-------|-------|-------|
| **B2B Customers** | Email + SMS within 30 min | Email within 4 hours | Portal notice | N/A |
| **B2C Customers** | Status page + social media | Status page | Status page | N/A |
| **Partners/Vendors** | Direct contact | Email notification | As contract requires | N/A |
| **Board Members** | Within 2 hours | Within 24 hours | N/A | N/A |

### 6.2 Communication Templates

#### 6.2.1 Internal Notification - Initial (SEV 1)

```
Subject: [INCIDENT ALERT - SEV 1] Production System Outage - IMP-2026-01-0001

INCIDENT ALERT

A critical incident has been declared affecting Smart Dairy production systems.

INCIDENT DETAILS:
Incident ID: IMP-2026-01-0001
Severity: SEV 1 - Critical
Detected: [DATE/TIME]
Affected Systems: [LIST]
Impact: [DESCRIPTION]

CURRENT STATUS:
[ ] Investigation ongoing
[ ] Service restoration in progress
[ ] Workaround identified
[ ] Service restored (pending verification)

RESPONSE ACTIVATED:
✓ Incident Manager: [NAME]
✓ Technical Lead: [NAME]
✓ Communications Lead: [NAME]
✓ War Room: [LOCATION/BRIDGE]

NEXT UPDATE: [TIME] (every 30 minutes until resolved)

DO NOT:
× Discuss on social media
× Contact customers directly
× Make unauthorized changes

INCIDENT MANAGER: [NAME] | [PHONE] | [EMAIL]
```

#### 6.2.2 Customer Notification - Service Disruption (SEV 1)

```
Subject: Service Alert: [System Name] Currently Experiencing Issues

Dear [Customer Name],

We are currently experiencing technical difficulties with our [System Name] that 
may be affecting your ability to [affected functionality].

WHAT IS HAPPENING:
Our team has identified an issue with [brief description] and is working urgently 
to restore full service.

AFFECTED SERVICES:
• [Service 1]
• [Service 2]

CURRENT STATUS:
Our technical team is actively working on a resolution. We will provide updates 
every 30 minutes until the issue is resolved.

NEXT UPDATE: [TIME]

WORKAROUND:
[If applicable, provide workaround]

We sincerely apologize for any inconvenience this may cause and appreciate your 
patience as we work to resolve this matter.

For urgent inquiries, please contact:
Phone: +880-XXXX-XXXXXX
Email: support@smartdairybd.com

Status Page: https://status.smartdairybd.com

Smart Dairy IT Operations Team
```

#### 6.2.3 Status Update Template

```
Subject: [UPDATE] Incident IMP-2026-01-0001 - [Brief Status]

INCIDENT UPDATE #[NUMBER]
Incident ID: IMP-2026-01-0001
Time: [TIMESTAMP]

STATUS: [Investigating/Identified/Monitoring/Resolved]

PROGRESS SUMMARY:
• [Action taken and result]
• [Current situation]
• [Next steps]

ESTIMATED RESOLUTION: [Time or "Under Assessment"]

NEXT UPDATE: [TIME]

Incident Manager: [NAME]
```

#### 6.2.4 Resolution Notification

```
Subject: [RESOLVED] Incident IMP-2026-01-0001 - Service Restored

INCIDENT RESOLVED
Incident ID: IMP-2026-01-0001

We are pleased to confirm that the issue affecting [System Name] has been resolved 
and all services are now operating normally.

RESOLUTION TIME: [TIMESTAMP]
TOTAL DURATION: [DURATION]

WHAT HAPPENED:
[Brief description of root cause]

WHAT WE DID:
[Summary of resolution actions]

WHAT WE ARE DOING NEXT:
[Follow-up actions, post-mortem schedule]

We apologize for any inconvenience caused. A detailed post-incident review will be 
conducted to prevent recurrence.

If you continue to experience any issues, please contact:
support@smartdairybd.com | +880-XXXX-XXXXXX

Smart Dairy IT Operations Team
```

### 6.3 Communication Channels

| Channel | Purpose | Audience |
|---------|---------|----------|
| **Slack #incidents** | Real-time team coordination | IR Team |
| **Slack #incident-updates** | Status updates for broader team | All IT staff |
| **Email** | Formal notifications | Management, stakeholders |
| **SMS** | Urgent alerts | On-call staff, executives |
| **Phone/Conference Bridge** | War room coordination | IR Team |
| **Status Page** | Public status updates | Customers |
| **Support Portal** | Ticket updates | Internal users |
| **WhatsApp Business** | Customer notifications (Bangladesh) | B2B customers |

---

## 7. MAJOR INCIDENT PROCESS

### 7.1 Major Incident Definition

A **Major Incident** is defined as:
- Any SEV 1 incident (Critical)
- Any SEV 2 incident with significant business impact
- Any incident affecting multiple Tier 1 services
- Any security incident involving data exposure
- Any incident requiring external communications

### 7.2 Major Incident Activation

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    MAJOR INCIDENT ACTIVATION FLOW                            │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Major Incident Detected                                                     │
│         │                                                                    │
│         ▼                                                                    │
│  ┌─────────────┐                                                            │
│  │   PAGE      │                                                            │
│  │   ON-CALL   │────▶ Incident Manager receives alert                       │
│  │   ENGINEER  │                                                            │
│  └─────────────┘                                                            │
│         │                                                                    │
│         ▼                                                                    │
│  ┌─────────────┐     ┌─────────────┐     ┌─────────────┐                    │
│  │  ASSESS     │────▶│  DECLARE    │────▶│  ACTIVATE   │                    │
│  │  SEVERITY   │     │  MAJOR      │     │  IRT        │                    │
│  │             │     │  INCIDENT   │     │             │                    │
│  └─────────────┘     └─────────────┘     └─────────────┘                    │
│                                                │                             │
│              ┌─────────────────────────────────┼─────────────────┐           │
│              │                                 │                 │           │
│              ▼                                 ▼                 ▼           │
│       ┌──────────┐                      ┌──────────┐      ┌──────────┐      │
│       │ OPEN WAR │                      │ INITIATE │      │  NOTIFY  │      │
│       │   ROOM   │                      │  COMMS   │      │STAKEHOLD│      │
│       └──────────┘                      └──────────┘      └──────────┘      │
│                                                                              │
│  Timeline: All activation steps completed within 30 minutes of detection     │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 7.3 Major Incident Response Checklist

**Immediate Actions (0-15 minutes):**
- [ ] Incident Manager paged and acknowledges
- [ ] Technical Lead assigned and engaged
- [ ] Incident ticket created/updated with SEV 1 classification
- [ ] War room opened (virtual or physical)
- [ ] Initial status page update published

**Short-term Actions (15-60 minutes):**
- [ ] Full IRT assembled
- [ ] Initial stakeholder notifications sent
- [ ] Technical investigation commenced
- [ ] Customer notifications sent (if applicable)
- [ ] First status update published

**Ongoing Actions:**
- [ ] Status updates every 30 minutes
- [ ] Technical progress logged in ticket
- [ ] Stakeholder briefings as scheduled
- [ ] Escalation to L3/vendor if needed

### 7.4 Major Incident Governance

| Meeting | Frequency | Participants | Purpose |
|---------|-----------|--------------|---------|
| **Technical Standup** | Every 30 min | Technical team | Sync on progress, blockers |
| **Stakeholder Briefing** | Every 2 hours | Management, business | Business impact, ETAs |
| **Command Update** | As needed | Incident Manager, leads | Strategic decisions |

---

## 8. WAR ROOM PROCEDURES

### 8.1 War Room Activation

A War Room is activated for:
- All SEV 1 incidents
- Complex SEV 2 incidents requiring multi-team coordination
- Security incidents requiring forensics
- Any incident where Incident Manager deems it necessary

### 8.2 War Room Types

| Type | Description | Use Case |
|------|-------------|----------|
| **Virtual War Room** | Video conference bridge | Standard SEV 1, distributed teams |
| **Physical War Room** | Dedicated conference room | Extended incidents, sensitive issues |
| **Hybrid** | Combination of both | Complex incidents requiring both |

### 8.3 War Room Setup

#### 8.3.1 Virtual War Room

| Component | Details |
|-----------|---------|
| **Platform** | Zoom / Google Meet / Microsoft Teams |
| **Bridge Number** | [Defined per incident] |
| **Persistent Room** | Available 24/7 for emergencies |
| **Recording** | Enabled for post-incident review |
| **Screen Sharing** | Available for all participants |

#### 8.3.2 Physical War Room

| Item | Specification |
|------|---------------|
| **Location** | IT Operations Center, 3rd Floor |
| **Capacity** | 12 people |
| **Displays** | 3 large screens (monitoring, video, documentation) |
| **Communication** | Conference phone, video conferencing |
| **Whiteboard** | Available for diagramming |
| **Network** | Dedicated high-speed connection |

### 8.4 War Room Roles

| Role | Responsibility |
|------|----------------|
| **Incident Commander** | Facilitates war room, makes decisions |
| **Scribe** | Documents all actions, decisions, timeline |
| **Technical Lead** | Coordinates technical activities |
| **Communication Lead** | Manages external communications |
| **Liaison** | Coordinates with external vendors/support |

### 8.5 War Room Protocols

**Entry Protocol:**
1. Join virtual bridge or arrive at physical room
2. Announce yourself and your role
3. Mute when not speaking (virtual)
4. Review current status with scribe

**During Session:**
- One conversation at a time
- Direct all communication through Incident Commander
- Technical work happens outside war room (breakout channels)
- Status updates every 15 minutes
- No side conversations

**Exit Protocol:**
- Announce departure if leaving temporarily
- Hand off any active tasks
- Check with Incident Commander before leaving for extended period

### 8.6 War Room Tools

| Tool | Purpose |
|------|---------|
| **Incident Dashboard** | Real-time metrics and status |
| **Collaborative Document** | Shared notes (Google Docs/Confluence) |
| **Timeline Tracker** | Chronological event tracking |
| **Communication Log** | Record of all communications |
| **Action Item Tracker** | Outstanding tasks and owners |

---

## 9. POST-INCIDENT REVIEW

### 9.1 Review Triggers

| Incident Type | Review Required | Timeline |
|---------------|-----------------|----------|
| SEV 1 | Mandatory | Within 48 hours |
| SEV 2 | Recommended | Within 1 week |
| SEV 3/4 | Optional | As needed |
| Repeated incidents | Mandatory | Within 1 week |
| Security incidents | Mandatory | Within 24 hours |
| Data loss incidents | Mandatory | Within 24 hours |

### 9.2 Post-Incident Review Process

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    POST-INCIDENT REVIEW PROCESS                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌──────────────┐                                                            │
│  │   SCHEDULE   │                                                            │
│  │   REVIEW     │────▶ Invite participants                                   │
│  │   MEETING    │       Prepare timeline                                     │
│  └──────────────┘       Gather evidence                                      │
│         │                                                                    │
│         ▼                                                                    │
│  ┌──────────────┐                                                            │
│  │   CONDUCT    │────▶ Review timeline                                       │
│  │   REVIEW     │       Identify root cause(s)                               │
│  │   MEETING    │       Discuss what went well                               │
│  │              │       Identify improvements                                │
│  └──────────────┘                                                            │
│         │                                                                    │
│         ▼                                                                    │
│  ┌──────────────┐                                                            │
│  │   CREATE     │────▶ Document findings                                     │
│  │   POST-      │       Assign action items                                  │
│  │   MORTEM     │       Set due dates                                        │
│  │              │       Share with stakeholders                              │
│  └──────────────┘                                                            │
│         │                                                                    │
│         ▼                                                                    │
│  ┌──────────────┐                                                            │
│  │   TRACK      │────▶ Monitor action item completion                        │
│  │   ACTIONS    │       Update problem management                            │
│  │              │       Close the loop                                       │
│  └──────────────┘                                                            │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 9.3 Post-Mortem Template

See Appendix A for full Post-Mortem Template.

### 9.4 Review Meeting Participants

| Role | Required | Purpose |
|------|----------|---------|
| Incident Manager | Yes | Lead the review |
| Technical Lead | Yes | Technical perspective |
| SME(s) involved | Yes | Domain expertise |
| Scribe | Yes | Document findings |
| Communications Lead | Yes (SEV 1) | Communication review |
| Management representative | Yes (SEV 1) | Business perspective |
| Customer representative | Optional | Customer impact |

### 9.5 Review Discussion Topics

**Timeline Review:**
- Detection time - when was it first noticed?
- Response time - how quickly did we respond?
- Diagnosis time - how long to identify the issue?
- Resolution time - how long to fix?
- Communication effectiveness

**What Went Well:**
- Quick detection methods
- Effective troubleshooting steps
- Good communication practices
- Successful workarounds

**What Could Be Improved:**
- Delays or bottlenecks
- Communication gaps
- Missing tools or access
- Knowledge gaps

**Action Items:**
- Specific improvements
- Owners assigned
- Due dates set
- Tracking mechanism

---

## 10. METRICS AND KPIs

### 10.1 Key Performance Indicators

| KPI | Definition | Target | Measurement |
|-----|------------|--------|-------------|
| **MTTD** | Mean Time To Detect | < 5 minutes | Automated + manual |
| **MTTA** | Mean Time To Acknowledge | Per severity | Alert to assignment |
| **MTTR** | Mean Time To Resolve | Per severity SLA | Detection to closure |
| **MTTR (SEV 1)** | Mean Time To Resolve Critical | < 4 hours | Monthly average |
| **MTTR (SEV 2)** | Mean Time To Resolve Major | < 8 hours | Monthly average |
| **First Contact Resolution** | % resolved by L1 | > 40% | Tickets closed by L1 |
| **Incident Recurrence** | % of repeat incidents | < 10% | Same root cause |
| **Customer Satisfaction** | Post-incident survey | > 4.0/5.0 | Survey scores |
| **SLA Compliance** | % meeting SLA | > 95% | All severities |

### 10.2 Incident Metrics Dashboard

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    INCIDENT MANAGEMENT DASHBOARD                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  CURRENT MONTH SUMMARY                                                       │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐        │
│  │   Total     │  │   SEV 1     │  │   SEV 2     │  │   SEV 3/4   │        │
│  │  Incidents  │  │  Critical   │  │   Major     │  │  Minor/Low  │        │
│  │             │  │             │  │             │  │             │        │
│  │    [XX]     │  │    [XX]     │  │    [XX]     │  │    [XX]     │        │
│  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘        │
│                                                                              │
│  RESPONSE TIME PERFORMANCE                                                   │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │ SEV 1 Target: < 15 min │ Actual: [XX] min │ Status: [✓/✗]          │    │
│  │ SEV 2 Target: < 1 hour │ Actual: [XX] min │ Status: [✓/✗]          │    │
│  │ SEV 3 Target: < 4 hours│ Actual: [XX] min │ Status: [✓/✗]          │    │
│  │ SEV 4 Target: < 24 hour│ Actual: [XX] min │ Status: [✓/✗]          │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  MTTR BY SERVICE                                                             │
│  ┌─────────────────┬──────────────┬──────────────┬──────────────┐           │
│  │ Service         │ SEV 1        │ SEV 2        │ SEV 3        │           │
│  ├─────────────────┼──────────────┼──────────────┼──────────────┤           │
│  │ ERP Core        │ [Xh Xm]      │ [Xh Xm]      │ [Xh Xm]      │           │
│  │ B2B Portal      │ [Xh Xm]      │ [Xh Xm]      │ [Xh Xm]      │           │
│  │ B2C E-commerce  │ [Xh Xm]      │ [Xh Xm]      │ [Xh Xm]      │           │
│  │ IoT Platform    │ [Xh Xm]      │ [Xh Xm]      │ [Xh Xm]      │           │
│  └─────────────────┴──────────────┴──────────────┴──────────────┘           │
│                                                                              │
│  TOP INCIDENT CATEGORIES                                                     │
│  1. Infrastructure Issues: [XX%]                                            │
│  2. Application Errors: [XX%]                                               │
│  3. Security Incidents: [XX%]                                               │
│  4. Third-party Failures: [XX%]                                             │
│  5. Data Issues: [XX%]                                                      │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 10.3 Reporting Schedule

| Report | Frequency | Audience | Content |
|--------|-----------|----------|---------|
| **Daily Incident Summary** | Daily | IT Operations | Incidents from last 24 hours |
| **Weekly Incident Report** | Weekly | IT Management | Trends, metrics, action items |
| **Monthly Incident Review** | Monthly | Executive Team | KPIs, major incidents, improvements |
| **Quarterly Trend Analysis** | Quarterly | Board | Strategic trends, risk assessment |

---

## 11. INTEGRATION WITH PROBLEM MANAGEMENT

### 11.1 Incident to Problem Escalation

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    INCIDENT TO PROBLEM ESCALATION                            │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  INCIDENT IDENTIFIED                                                         │
│         │                                                                    │
│         ▼                                                                    │
│  ┌─────────────┐     ┌─────────────┐     ┌─────────────┐                    │
│  │  IS ROOT    │──NO─▶│   RAISE     │────▶│  PROBLEM    │                    │
│  │  CAUSE      │     │   PROBLEM   │     │  RECORD     │                    │
│  │  KNOWN?     │     │   TICKET    │     │             │                    │
│  └──────┬──────┘     └─────────────┘     └─────────────┘                    │
│         │ YES                                                               │
│         ▼                                                                    │
│  ┌─────────────┐     ┌─────────────┐                                        │
│  │   CAN WE    │──NO─▶│   RAISE     │                                        │
│  │   FIX NOW?  │     │   PROBLEM   │                                        │
│  └──────┬──────┘     └─────────────┘                                        │
│         │ YES                                                               │
│         ▼                                                                    │
│  ┌─────────────┐     ┌─────────────┐                                        │
│  │   REPEATED  │──YES─▶│   RAISE     │                                       │
│  │  INCIDENT?  │     │   PROBLEM   │                                        │
│  └──────┬──────┘     └─────────────┘                                        │
│         │ NO                                                                │
│         ▼                                                                    │
│  ┌─────────────┐                                                            │
│  │  INCIDENT   │                                                            │
│  │   CLOSED    │                                                            │
│  └─────────────┘                                                            │
│                                                                              │
│  Problem Ticket Created For:                                                 │
│  • Unknown root cause incidents                                              │
│  • Incidents requiring significant investigation                             │
│  • Recurring incidents (> 2 in 30 days)                                      │
│  • Major incidents (SEV 1 always reviewed)                                   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 11.2 Problem Management Handoff

| Handoff Item | From | To | Content |
|--------------|------|-----|---------|
| **Incident Details** | Incident Manager | Problem Manager | Full incident timeline |
| **Technical Findings** | Technical Lead | Problem Analyst | Diagnostic information |
| **Known Errors** | SMEs | Knowledge Base | Workarounds, fixes |
| **Action Items** | Post-mortem | Problem Record | Pending improvements |

### 11.3 Known Error Database

A Known Error Database (KEDB) is maintained to document:
- Known issues with workarounds
- Common incident patterns
- Resolution procedures
- Configuration problems

| Field | Description |
|-------|-------------|
| **Known Error ID** | KEB-YYYY-NNNN format |
| **Title** | Brief description |
| **Root Cause** | Known cause |
| **Workaround** | Temporary solution |
| **Permanent Fix** | Final solution if available |
| **Related Incidents** | List of related incident IDs |
| **Status** | Open/Resolved |

---

## 12. APPENDICES

### Appendix A: Post-Mortem Template

```
================================================================================
                    POST-INCIDENT REVIEW (POST-MORTEM)
================================================================================

INCIDENT REFERENCE INFORMATION
───────────────────────────────────────────────────────────────────────────────
Incident ID: IMP-YYYY-MM-NNNN
Date/Time of Incident: [DATE/TIME]
Duration: [DURATION]
Severity: SEV [1/2/3/4]

Incident Manager: [NAME]
Technical Lead: [NAME]
Participants: [LIST OF ATTENDEES]

Review Date: [DATE]
Document Owner: [NAME]

EXECUTIVE SUMMARY
───────────────────────────────────────────────────────────────────────────────
[1-2 paragraph summary of what happened, impact, and resolution]

INCIDENT TIMELINE
───────────────────────────────────────────────────────────────────────────────
Time (IST)      Event
──────────────  ─────────────────────────────────────────────────────────────
[HH:MM]         [Event description]
[HH:MM]         [Event description]
[HH:MM]         [Event description]

DETAILED DESCRIPTION
───────────────────────────────────────────────────────────────────────────────
[Detailed technical description of the incident]

IMPACT ASSESSMENT
───────────────────────────────────────────────────────────────────────────────
Systems Affected: [List]
Users Affected: [Number/Description]
Business Impact: [Revenue, operations, reputation]
Data Impact: [Any data loss or corruption]

ROOT CAUSE ANALYSIS
───────────────────────────────────────────────────────────────────────────────
Root Cause: [What caused the incident]

Contributing Factors:
• [Factor 1]
• [Factor 2]
• [Factor 3]

5 Whys Analysis:
Why? [Answer]
Why? [Answer]
Why? [Answer]
Why? [Answer]
Why? [Root Cause]

WHAT WENT WELL
───────────────────────────────────────────────────────────────────────────────
• [Positive aspect 1]
• [Positive aspect 2]
• [Positive aspect 3]

WHAT COULD BE IMPROVED
───────────────────────────────────────────────────────────────────────────────
• [Area for improvement 1]
• [Area for improvement 2]
• [Area for improvement 3]

ACTION ITEMS
───────────────────────────────────────────────────────────────────────────────
ID      Action Item                    Owner           Due Date    Priority
──────  ─────────────────────────────  ──────────────  ──────────  ─────────
[A-1]   [Description]                  [Name]          [Date]      [H/M/L]
[A-2]   [Description]                  [Name]          [Date]      [H/M/L]
[A-3]   [Description]                  [Name]          [Date]      [H/M/L]

LESSONS LEARNED
───────────────────────────────────────────────────────────────────────────────
[Key lessons to apply to future incidents]

APPENDICES
───────────────────────────────────────────────────────────────────────────────
• [Logs, screenshots, additional data]

APPROVAL
───────────────────────────────────────────────────────────────────────────────
Prepared by: _________________________ Date: __________

Reviewed by: _________________________ Date: __________

Approved by: _________________________ Date: __________
================================================================================
```

### Appendix B: Incident Response Checklist

```
INCIDENT RESPONSE CHECKLIST
═══════════════════════════════════════════════════════════════════════════════

DETECTION PHASE
□ Alert received or incident reported
□ Initial validation (not false positive)
□ Severity assessment completed
□ Incident Manager notified (SEV 1/2)

LOGGING PHASE
□ Incident ticket created
□ Incident ID assigned (IMP-YYYY-MM-NNNN)
□ Initial details documented
□ Affected services identified
□ Business impact assessed

DIAGNOSIS PHASE
□ Assigned engineer acknowledged
□ Initial information gathered (logs, metrics)
□ Knowledge base checked for known errors
□ Root cause hypothesis formed
□ Escalation decision made

ESCALATION PHASE (if needed)
□ Next support level engaged
□ SMEs involved as needed
□ Vendor support contacted (if applicable)
□ War room opened (SEV 1)
□ Incident Manager activated (SEV 1/2)

RESOLUTION PHASE
□ Resolution approach determined
□ Changes approved per policy
□ Fix implemented
□ Service restoration verified
□ Monitoring confirms stability

CLOSURE PHASE
□ All affected users notified
□ Incident ticket updated
□ Root cause documented
□ Post-mortem scheduled (SEV 1/2)
□ Ticket closed

POST-INCIDENT PHASE
□ Post-mortem conducted
□ Action items assigned
□ Knowledge base updated
□ Metrics updated
□ Process improvements implemented
═══════════════════════════════════════════════════════════════════════════════
```

### Appendix C: Severity Assessment Matrix

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SEVERITY ASSESSMENT WORKSHEET                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  IMPACT ASSESSMENT                                                           │
│  ────────────────                                                            │
│  □ Critical: Complete service unavailability or data breach                  │
│  □ High:      Major functionality impaired, significant user impact          │
│  □ Medium:    Limited functionality affected, workarounds exist              │
│  □ Low:       Minimal impact, cosmetic issues                                │
│                                                                              │
│  URGENCY ASSESSMENT                                                          │
│  ──────────────────                                                          │
│  □ Critical: Immediate business impact, safety concerns, revenue loss        │
│  □ High:      Significant impact requiring prompt attention                  │
│  □ Medium:    Moderate impact, can be addressed during business hours        │
│  □ Low:       Can be addressed during planned maintenance                    │
│                                                                              │
│  SCOPE ASSESSMENT                                                            │
│  ────────────────                                                            │
│  Number of users affected: _______________                                   │
│  Business functions impacted: _______________                                │
│  Revenue impact per hour: BDT _______________                                │
│  Customer-facing: □ Yes □ No                                                 │
│                                                                              │
│  DETERMINED SEVERITY: SEV _____                                              │
│                                                                              │
│  Justification: ________________________________________________             │
│  _______________________________________________________________             │
│                                                                              │
│  Assessed by: _________________ Date/Time: _________________                 │
│  Reviewed by: _________________ Date/Time: _________________                 │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Appendix D: Communication Templates Quick Reference

| Template | Use Case | Location |
|----------|----------|----------|
| INC-TPL-001 | Internal SEV 1 Initial Alert | Section 6.2.1 |
| INC-TPL-002 | Customer Service Disruption | Section 6.2.2 |
| INC-TPL-003 | Status Update | Section 6.2.3 |
| INC-TPL-004 | Resolution Notification | Section 6.2.4 |
| INC-TPL-005 | Security Incident Notice | F-011 Section 5.2 |
| INC-TPL-006 | Post-Incident Customer Follow-up | Support Portal |

### Appendix E: Contact Information

| Role | Primary | Secondary | After Hours |
|------|---------|-----------|-------------|
| Incident Manager | +880-XXXX-XXXXXX | +880-XXXX-XXXXXX | +880-XXXX-XXXXXX |
| Technical Lead | +880-XXXX-XXXXXX | +880-XXXX-XXXXXX | +880-XXXX-XXXXXX |
| Communications | +880-XXXX-XXXXXX | +880-XXXX-XXXXXX | +880-XXXX-XXXXXX |
| Security Lead | +880-XXXX-XXXXXX | +880-XXXX-XXXXXX | +880-XXXX-XXXXXX |
| DBA On-Call | +880-XXXX-XXXXXX | +880-XXXX-XXXXXX | +880-XXXX-XXXXXX |
| AWS Support | Enterprise Hotline | - | 24/7 Available |

---

**END OF INCIDENT MANAGEMENT PROCESS**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | IT Operations Manager | Initial version |

---

**Document Control**

| | |
|---|---|
| **Next Review Date** | July 31, 2026 |
| **Document Owner** | IT Operations Manager |
| **Distribution** | IT Operations, Support Teams, Management |
| **Classification** | INTERNAL - OPERATIONS |
