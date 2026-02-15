# SMART DAIRY LTD.
## SERVICE LEVEL AGREEMENT (SLA)
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | L-001 |
| **Version** | 1.0 |
| **Date** | October 28, 2026 |
| **Effective Date** | November 1, 2026 |
| **Author** | PMO |
| **Owner** | IT Director |
| **Reviewer** | Legal Counsel |
| **Classification** | CONFIDENTIAL - CUSTOMER FACING |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Definitions](#2-definitions)
3. [Service Description](#3-service-description)
4. [Service Availability](#4-service-availability)
5. [Performance Standards](#5-performance-standards)
6. [Support Services](#6-support-services)
7. [Incident Management](#7-incident-management)
8. [Change Management](#8-change-management)
9. [Reporting](#9-reporting)
10. [Service Credits](#10-service-credits)
11. [Governance](#11-governance)
12. [Appendices](#12-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This Service Level Agreement (SLA) defines the level of service expected from Smart Dairy's IT Operations team for the Smart Web Portal System and Integrated ERP platform. This document establishes measurable service levels, response times, and remedies for service failures.

### 1.2 Scope

This SLA applies to:
- All Smart Dairy internal users of the ERP system
- B2B customers accessing the wholesale portal
- B2C customers using the e-commerce platform
- IoT device connectivity and data services
- API consumers and integration partners

### 1.3 Agreement Period

| Item | Details |
|------|---------|
| **Effective Date** | November 1, 2026 |
| **Initial Term** | 12 months |
| **Renewal** | Automatic annual renewal unless terminated with 60 days notice |
| **Review Cycle** | Quarterly business reviews |

### 1.4 Document Hierarchy

```
Master Services Agreement (MSA)
    └── Service Level Agreement (SLA) ← THIS DOCUMENT
            ├── Security Policy
            ├── Data Processing Agreement
            └── Acceptable Use Policy
```

---

## 2. DEFINITIONS

### 2.1 Key Terms

| Term | Definition |
|------|------------|
| **Availability** | The percentage of time the service is operational and accessible, excluding scheduled maintenance |
| **Business Hours** | 08:00 - 20:00 IST, Monday through Saturday |
| **Critical Incident** | Complete service outage affecting all users |
| **Downtime** | Period when the service is not available due to system failures |
| **Incident** | Any event that disrupts or could disrupt normal service operation |
| **MTTR** | Mean Time To Recovery - average time to restore service after failure |
| **P1/P2/P3/P4** | Priority levels: Critical, High, Medium, Low |
| **RTO** | Recovery Time Objective - maximum acceptable downtime |
| **RPO** | Recovery Point Objective - maximum acceptable data loss |
| **Scheduled Maintenance** | Pre-planned service interruptions with minimum 48 hours notice |
| **Service Credit** | Financial credit applied for SLA violations |
| **Uptime** | Total time minus downtime, expressed as percentage |

### 2.2 Service Tiers

| Tier | Description | Users | SLA Target |
|------|-------------|-------|------------|
| **Tier 1** | Critical Business Operations | B2B Portal, IoT Core, Payment Processing | 99.9% |
| **Tier 2** | Standard Business Services | ERP, B2C Store, Internal Operations | 99.5% |
| **Tier 3** | Support Services | Reporting, Analytics, Development | 99.0% |

---

## 3. SERVICE DESCRIPTION

### 3.1 Service Catalog

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         SMART DAIRY SERVICE CATALOG                          │
└─────────────────────────────────────────────────────────────────────────────┘

TIER 1 - CRITICAL SERVICES (99.9% Uptime)
├── B2B Wholesale Portal
│   ├── Order placement and management
│   ├── Real-time inventory visibility
│   ├── Credit limit checking
│   └── Invoice access
│
├── Core ERP Functions
│   ├── Farm management (animal tracking, milk recording)
│   ├── Production planning
│   ├── Quality control
│   └── Basic reporting
│
├── IoT Infrastructure
│   ├── MQTT broker connectivity
│   ├── Sensor data ingestion
│   └── Critical alerts (health, equipment)
│
└── Payment Processing
    ├── B2C payment gateway
    ├── B2B credit management
    └── Invoice generation

TIER 2 - STANDARD SERVICES (99.5% Uptime)
├── B2C E-commerce
│   ├── Product catalog browsing
│   ├── Shopping cart and checkout
│   ├── Subscription management
│   └── Order tracking
│
├── Supply Chain Management
│   ├── Procurement
│   ├── Inventory management
│   └── Vendor portal
│
├── CRM Functions
│   ├── Customer database
│   ├── Lead management
│   └── Communication history
│
└── Accounting & Finance
    ├── Bookkeeping
    ├── GST compliance
    └── Financial reporting

TIER 3 - SUPPORT SERVICES (99.0% Uptime)
├── Advanced Analytics
│   ├── Business intelligence dashboards
│   ├── Predictive analytics
│   └── Custom reports
│
├── Integration APIs
│   ├── Third-party connections
│   ├── Mobile app backend
│   └── Webhook services
│
└── Development/Testing Environments
```

### 3.2 Service Hours

| Service Tier | Standard Hours | Extended Hours | 24/7 Support |
|--------------|----------------|----------------|--------------|
| **Tier 1** | Included | Included | Yes |
| **Tier 2** | Included | Available (+20%) | Optional (+30%) |
| **Tier 3** | Included | N/A | N/A |

**Standard Hours:** 08:00 - 20:00 IST, Monday-Saturday
**Extended Hours:** 06:00 - 23:00 IST, Monday-Sunday
**24/7 Support:** Always available for critical issues

---

## 4. SERVICE AVAILABILITY

### 4.1 Availability Targets

| Service Tier | Monthly Target | Annual Target | Max Downtime/Month |
|--------------|----------------|---------------|-------------------|
| **Tier 1** | 99.9% | 99.9% | 43.8 minutes |
| **Tier 2** | 99.5% | 99.5% | 3.6 hours |
| **Tier 3** | 99.0% | 99.0% | 7.2 hours |

### 4.2 Availability Calculation

```
                    Total Time - Scheduled Maintenance - Unscheduled Downtime
Availability % =  ───────────────────────────────────────────────────────────── × 100
                        Total Time - Scheduled Maintenance

Where:
- Total Time = 24 hours × 30 days = 720 hours/month (Tier 1)
- Scheduled Maintenance = Maximum 4 hours/month with 48-hour notice
- Unscheduled Downtime = Any unplanned service interruption
```

### 4.3 Exclusions from Downtime

The following are NOT counted as downtime:

| Exclusion Category | Examples |
|-------------------|----------|
| **Scheduled Maintenance** | Updates, upgrades, preventive maintenance with 48-hour notice |
| **Force Majeure** | Natural disasters, acts of war, government actions |
| **Third-Party Failures** | ISP outages, cloud provider issues beyond our control |
| **Customer Infrastructure** | Customer's internet, hardware, or network issues |
| **Unsupported Configurations** | Use of unsupported browsers or devices |

### 4.4 Maintenance Windows

| Maintenance Type | Frequency | Duration | Notice Required |
|------------------|-----------|----------|-----------------|
| **Standard** | Weekly | 2 hours | 48 hours |
| **Major Updates** | Monthly | 4 hours | 7 days |
| **Emergency** | As needed | Variable | Best effort |

**Standard Maintenance Window:** Sunday 02:00 - 06:00 IST

---

## 5. PERFORMANCE STANDARDS

### 5.1 Response Time Standards

| Operation | Target (p95) | Maximum (p99) | Measurement |
|-----------|--------------|---------------|-------------|
| **Page Load - Login** | < 3 seconds | < 5 seconds | Browser timing |
| **Page Load - Dashboard** | < 4 seconds | < 6 seconds | Browser timing |
| **Product Search** | < 2 seconds | < 4 seconds | API response |
| **Order Submission** | < 3 seconds | < 5 seconds | API response |
| **Report Generation (< 10K records)** | < 10 seconds | < 30 seconds | Server processing |
| **File Upload (< 10MB)** | < 10 seconds | < 20 seconds | Upload completion |
| **API Response (Simple)** | < 200ms | < 500ms | API gateway |
| **API Response (Complex)** | < 1 second | < 2 seconds | API gateway |
| **Database Query** | < 100ms | < 500ms | Query log |

### 5.2 Throughput Standards

| Metric | Target | Peak Capacity |
|--------|--------|---------------|
| **Concurrent Users** | 500 | 1,000 |
| **Orders/Hour (B2C)** | 1,000 | 2,000 |
| **Orders/Hour (B2B)** | 500 | 1,000 |
| **API Requests/Second** | 500 | 1,000 |
| **IoT Messages/Second** | 100 | 250 |
| **Report Generation (Daily)** | 100 | 200 |

### 5.3 Data Integrity Standards

| Metric | Target | Measurement |
|--------|--------|-------------|
| **Data Accuracy** | 99.99% | Automated reconciliation |
| **Transaction Completeness** | 100% | Audit trail verification |
| **Backup Success Rate** | 100% | Daily verification |
| **Recovery Point Objective** | < 15 minutes | Continuous replication |
| **Recovery Time Objective** | < 4 hours | DR drill validation |

---

## 6. SUPPORT SERVICES

### 6.1 Support Channels

| Channel | Availability | Response Time | Best For |
|---------|-------------|---------------|----------|
| **Emergency Hotline** | 24/7 | Immediate | Critical outages |
| **Support Portal** | 24/7 | 4 hours | Non-urgent issues |
| **Email** | 24/7 | 8 hours | General inquiries |
| **Live Chat** | Business Hours | 5 minutes | Quick questions |
| **On-site Support** | Business Hours | Same day | Hardware issues |

### 6.2 Support Levels

| Level | Description | Response Time | Resolution Target |
|-------|-------------|---------------|-------------------|
| **L1 - Help Desk** | First response, basic troubleshooting | Per SLA | 50% of issues |
| **L2 - Technical** | Advanced troubleshooting, configuration | 1 hour | 30% of issues |
| **L3 - Engineering** | Code fixes, database issues | 4 hours | 15% of issues |
| **L4 - Vendor/External** | Third-party escalations | 8 hours | 5% of issues |

### 6.3 Priority Matrix

| Priority | Severity | Impact | Examples | Response Time |
|----------|----------|--------|----------|---------------|
| **P1 - Critical** | Complete service outage | All users affected | System down, payment failure | 15 minutes |
| **P2 - High** | Major functionality impaired | Many users affected | B2B ordering down, report failures | 1 hour |
| **P3 - Medium** | Minor functionality impaired | Some users affected | Feature not working, performance slow | 4 hours |
| **P4 - Low** | Cosmetic or enhancement | Minimal impact | UI issues, feature requests | 24 hours |

### 6.4 Support Escalation

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         SUPPORT ESCALATION MATRIX                            │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  P1 - CRITICAL (Complete Outage)                                            │
│  T+0 min    → Incident reported to Help Desk                                │
│  T+15 min   → L2 engaged if not resolved                                    │
│  T+30 min   → Incident Commander notified                                   │
│  T+1 hour   → L3 engaged, management notified                               │
│  T+2 hours  → Vendor escalation if applicable                               │
│  T+4 hours  → DR activation consideration                                   │
│                                                                              │
│  P2 - HIGH (Major Impairment)                                               │
│  T+0 min    → Incident reported to Help Desk                                │
│  T+1 hour   → L2 engaged if not resolved                                    │
│  T+4 hours  → L3 engaged                                                    │
│  T+8 hours  → Management notification if not resolved                       │
│                                                                              │
│  P3 - MEDIUM (Minor Impairment)                                             │
│  T+0 hours  → Ticket created in Support Portal                              │
│  T+4 hours  → L1 acknowledges and begins investigation                      │
│  T+24 hours → Escalate to L2 if not resolved                                │
│                                                                              │
│  P4 - LOW (Cosmetic/Enhancement)                                            │
│  T+0 hours  → Ticket created in Support Portal                              │
│  T+24 hours → L1 acknowledges                                               │
│  T+72 hours → Scheduled for resolution                                      │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 7. INCIDENT MANAGEMENT

### 7.1 Incident Classification

| Category | Description | Notification |
|----------|-------------|--------------|
| **Security Incident** | Data breach, unauthorized access | Immediate to CISO and Legal |
| **Availability Incident** | Service downtime | Per priority matrix |
| **Performance Incident** | Degraded performance | Per priority matrix |
| **Data Incident** | Data corruption, loss | Immediate to DBA and management |

### 7.2 Incident Response Times

| Phase | P1 | P2 | P3 | P4 |
|-------|-----|-----|-----|-----|
| **Detection** | 5 min | 15 min | 30 min | 2 hours |
| **Acknowledgment** | 15 min | 1 hour | 4 hours | 24 hours |
| **Initial Response** | 30 min | 2 hours | 8 hours | 48 hours |
| **Status Update** | 30 min | 2 hours | Daily | Weekly |
| **Resolution Target** | 4 hours | 8 hours | 3 days | 2 weeks |

### 7.3 Communication During Incidents

| Stakeholder | P1 | P2 | P3 | P4 |
|-------------|-----|-----|-----|-----|
| **Internal Users** | Email + SMS | Email | Email | Portal |
| **B2B Customers** | Email + SMS | Email | Portal | - |
| **B2C Customers** | Status Page | Status Page | - | - |
| **Management** | Immediate | Within 1 hour | Daily summary | Weekly report |
| **Status Page** | Updated every 15 min | Updated hourly | Updated at resolution | - |

---

## 8. CHANGE MANAGEMENT

### 8.1 Change Categories

| Type | Description | Approval | Lead Time |
|------|-------------|----------|-----------|
| **Standard** | Pre-approved, low-risk changes | Auto-approved | 24 hours |
| **Normal** | Requires CAB review | CAB approval | 1 week |
| **Emergency** | Urgent fix for incident | Emergency CAB | 4 hours |
| **Major** | Significant architectural change | Executive + CAB | 2 weeks |

### 8.2 Change Window Schedule

| Day | Time (IST) | Change Types Allowed |
|-----|------------|---------------------|
| **Sunday** | 02:00 - 06:00 | All types |
| **Wednesday** | 02:00 - 04:00 | Standard only |
| **Emergency** | Any time | Emergency only |

### 8.3 Change Success Criteria

| Criteria | Target |
|----------|--------|
| **Success Rate** | 95% |
| **Rollback Rate** | < 5% |
| **Incidents Caused** | < 2% of changes |
| **Average Implementation Time** | Within scheduled window |

---

## 9. REPORTING

### 9.1 Standard Reports

| Report | Frequency | Audience | Content |
|--------|-----------|----------|---------|
| **Availability Report** | Monthly | All customers | Uptime %, downtime events |
| **Performance Report** | Monthly | All customers | Response times, throughput |
| **Incident Report** | Monthly | All customers | Incident count, MTTR, root causes |
| **Security Report** | Quarterly | Management | Security incidents, patches applied |
| **Capacity Report** | Quarterly | Management | Resource utilization, growth trends |
| **Executive Summary** | Quarterly | Executive | SLA compliance, strategic metrics |

### 9.2 Report Distribution

| Report | Distribution Method | Available By |
|--------|-------------------|--------------|
| **Monthly Reports** | Email + Portal | 5th of following month |
| **Quarterly Reports** | Email + Presentation | 10th of following quarter |
| **Incident Updates** | Email + SMS | Real-time during incidents |
| **Maintenance Notices** | Email + Portal | 48 hours before (minimum) |

### 9.3 Real-time Dashboards

| Dashboard | URL | Access |
|-----------|-----|--------|
| **System Status** | status.smartdairy.com | Public |
| **Service Metrics** | grafana.smartdairy.com | Internal |
| **Customer Portal** | erp.smartdairy.com/sla | Customers |

---

## 10. SERVICE CREDITS

### 10.1 Service Credit Calculation

| SLA Violation | Credit % of Monthly Fee | Max Credit |
|---------------|------------------------|------------|
| **Availability < 99.9% (Tier 1)** | 5% per 0.1% below | 50% |
| **Availability < 99.5% (Tier 2)** | 3% per 0.1% below | 30% |
| **Availability < 99.0% (Tier 3)** | 2% per 0.1% below | 20% |
| **Missed RTO** | 10% per incident | 20% |
| **Missed Response Time (P1)** | 5% per incident | 15% |
| **Missed Response Time (P2)** | 3% per incident | 10% |

### 10.2 Credit Request Process

```
1. Customer submits credit request within 15 days of month-end
2. Smart Dairy validates the claim within 5 business days
3. If validated, credit applied to next invoice
4. If disputed, escalation to management within 3 days
5. Maximum monthly credit capped at 50% of monthly fee
```

### 10.3 Credit Exclusions

Service credits will NOT be issued for:
- Downtime during scheduled maintenance windows
- Issues caused by customer misuse or configuration
- Force majeure events
- Third-party service failures beyond our control
- Beta or trial services

---

## 11. GOVERNANCE

### 11.1 Governance Structure

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         GOVERNANCE STRUCTURE                                 │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  EXECUTIVE SPONSOR                                                           │
│  └── IT Director                                                             │
│      └── Reviews strategic alignment and major issues                        │
│                                                                              │
│  SERVICE GOVERNANCE COMMITTEE                                                │
│  ├── IT Director (Chair)                                                     │
│  ├── Operations Manager                                                      │
│  ├── Customer Success Manager                                                │
│  └── Technical Lead                                                          │
│      └── Quarterly review of SLA performance                                 │
│                                                                              │
│  OPERATIONAL REVIEW                                                          │
│  ├── IT Operations Lead                                                      │
│  ├── Support Team Lead                                                       │
│  └── Customer Representatives                                                │
│      └── Monthly operational reviews                                         │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 11.2 Review Schedule

| Review Type | Frequency | Participants | Duration |
|-------------|-----------|--------------|----------|
| **Operational Review** | Monthly | Operations team | 1 hour |
| **SLA Performance Review** | Quarterly | Governance Committee | 2 hours |
| **Strategic Review** | Annually | Executive Sponsor + Committee | Half day |
| **Emergency Review** | As needed | Affected parties | 1 hour |

### 11.3 SLA Change Process

1. **Change Request** - Either party can request SLA changes with 30 days notice
2. **Impact Assessment** - Smart Dairy assesses technical and commercial impact
3. **Negotiation** - Parties negotiate terms within 2 weeks
4. **Approval** - Changes approved by both parties' authorized signatories
5. **Implementation** - Changes effective next billing cycle

---

## 12. APPENDICES

### Appendix A: SLA Summary Card

```
┌─────────────────────────────────────────────────────────────────────┐
│                    SMART DAIRY SLA QUICK REFERENCE                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  AVAILABILITY TARGETS                                                │
│  ├── Tier 1 (Critical): 99.9%  (43.8 min max downtime/month)        │
│  ├── Tier 2 (Standard): 99.5%  (3.6 hours max downtime/month)       │
│  └── Tier 3 (Support):  99.0%  (7.2 hours max downtime/month)       │
│                                                                      │
│  RESPONSE TIMES                                                      │
│  ├── P1 (Critical): 15 minutes                                       │
│  ├── P2 (High): 1 hour                                               │
│  ├── P3 (Medium): 4 hours                                            │
│  └── P4 (Low): 24 hours                                              │
│                                                                      │
│  PERFORMANCE                                                         │
│  ├── Page Load: < 4 seconds (95th percentile)                        │
│  ├── API Response: < 1 second (95th percentile)                      │
│  └── Report Generation: < 10 seconds                                 │
│                                                                      │
│  SUPPORT CONTACTS                                                    │
│  ├── Emergency Hotline: +91-xxx-xxx-0000 (24/7)                     │
│  ├── Support Portal: support.smartdairy.com                          │
│  └── Status Page: status.smartdairy.com                              │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

### Appendix B: Measurement Methodology

| Metric | Tool | Sampling | Reporting |
|--------|------|----------|-----------|
| **Availability** | Uptime Kuma + AWS CloudWatch | Every 60 seconds | Automated |
| **Response Time** | Prometheus + Grafana | Every 10 seconds | Automated |
| **Incident Tracking** | PagerDuty + Jira | Real-time | Automated |
| **Customer Satisfaction** | Post-ticket survey | Per ticket | Monthly |
| **System Performance** | APM (New Relic/Datadog) | Continuous | Real-time |

### Appendix C: Signatures

**Service Provider (Smart Dairy Ltd.)**

| Role | Name | Signature | Date |
|------|------|-----------|------|
| IT Director | _________________ | _________________ | _______ |
| Operations Manager | _________________ | _________________ | _______ |

**Customer (for B2B agreements)**

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Authorized Signatory | _________________ | _________________ | _______ |

---

**END OF SERVICE LEVEL AGREEMENT**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | October 28, 2026 | PMO | Initial version |

---

**Document Control**

| | |
|---|---|
| **Next Review Date** | April 28, 2027 |
| **Document Owner** | IT Director |
| **Distribution** | All Customers, Internal Operations |
| **Classification** | CONFIDENTIAL - CUSTOMER FACING |
