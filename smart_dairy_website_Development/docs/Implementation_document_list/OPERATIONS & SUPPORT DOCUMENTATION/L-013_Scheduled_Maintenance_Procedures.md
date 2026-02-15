# L-013: Scheduled Maintenance Procedures

## Smart Dairy Ltd. - Smart Web Portal System

---

| **Document Control** | |
|:---|:---|
| **Document ID** | L-013 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Operations Manager |
| **Owner** | Operations Manager |
| **Reviewer** | IT Director |
| **Classification** | Internal |
| **Status** | Approved |

---

## Revision History

| Version | Date | Author | Description | Approved By |
|:---|:---|:---|:---|:---|
| 1.0 | January 31, 2026 | Operations Manager | Initial Release | IT Director |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Maintenance Types](#2-maintenance-types)
3. [Maintenance Schedule](#3-maintenance-schedule)
4. [Maintenance Windows](#4-maintenance-windows)
5. [Change Freeze Periods](#5-change-freeze-periods)
6. [Maintenance Planning](#6-maintenance-planning)
7. [Maintenance Execution](#7-maintenance-execution)
8. [Maintenance Communication](#8-maintenance-communication)
9. [Rollback Procedures](#9-rollback-procedures)
10. [Maintenance Records](#10-maintenance-records)
11. [Appendices](#11-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document establishes the scheduled maintenance procedures for the Smart Dairy Ltd. Smart Web Portal System. It defines the framework for planning, executing, and documenting all maintenance activities to ensure system availability, reliability, and performance while minimizing business disruption.

### 1.2 Scope

This procedure applies to:
- All production systems supporting the Smart Web Portal
- Development and testing environments
- Infrastructure components (servers, network, storage)
- Database systems and backups
- Security systems and monitoring tools
- Third-party integrations (payment gateways, SMS services)

### 1.3 Maintenance Strategy

Smart Dairy Ltd. employs a **proactive maintenance strategy** combining three approaches:

1. **Prevention-Focused**: Regular preventive maintenance to avoid failures
2. **Data-Driven**: Predictive maintenance based on monitoring data and trends
3. **Responsive**: Corrective maintenance for identified issues

### 1.4 Objectives

- **Availability**: Maintain 99.9% system uptime during business hours
- **Reliability**: Prevent unplanned outages through proactive maintenance
- **Performance**: Ensure optimal system response times (< 2 seconds)
- **Security**: Keep security patches current within defined SLAs
- **Compliance**: Maintain audit trail for all maintenance activities

### 1.5 Reference Documents

- L-012: Incident Management Procedures
- L-014: Change Management Procedures
- L-015: Release Management Procedures
- M-001: System Architecture Document
- M-003: Disaster Recovery Plan

---

## 2. Maintenance Types

### 2.1 Preventive Maintenance

Preventive maintenance consists of planned activities performed to prevent system failures and maintain optimal performance.

| Category | Activities | Frequency |
|:---|:---|:---|
| **System Health** | Disk cleanup, log rotation, temp file removal | Daily/Weekly |
| **Performance** | Index optimization, cache clearing, query tuning | Weekly/Monthly |
| **Security** | Patch installation, vulnerability scanning | Monthly/Quarterly |
| **Backup** | Full and incremental backups verification | Daily/Weekly |
| **Capacity** | Storage monitoring, resource utilization review | Monthly |

**Key Preventive Tasks for Smart Dairy Portal:**
- Database index rebuilding for order and inventory tables
- Cleanup of expired user sessions and cart data
- Optimization of milk delivery route calculation queries
- Archival of historical transaction data (> 2 years)

### 2.2 Predictive Maintenance

Predictive maintenance uses monitoring data and analytics to identify potential issues before they cause failures.

| Indicator | Threshold | Action Trigger |
|:---|:---|:---|
| CPU Utilization | > 80% for 15 minutes | Scale resources or investigate |
| Memory Usage | > 85% sustained | Memory leak investigation |
| Disk Space | < 20% free | Cleanup or expansion |
| Database Connections | > 80% of max | Connection pool optimization |
| API Response Time | > 3 seconds | Performance tuning |
| Error Rate | > 1% of requests | Immediate investigation |

**Predictive Tools:**
- Application Performance Monitoring (APM)
- Infrastructure monitoring dashboards
- Database performance analyzers
- Log aggregation and analysis systems

### 2.3 Corrective Maintenance

Corrective maintenance addresses identified defects, bugs, or system failures.

| Priority | Response Time | Resolution Target |
|:---|:---|:---|
| Critical (P1) | Immediate | 4 hours |
| High (P2) | 4 hours | 24 hours |
| Medium (P3) | 24 hours | 72 hours |
| Low (P4) | 72 hours | Next maintenance window |

**Corrective Maintenance Triggers:**
- Automated monitoring alerts
- User-reported issues
- Security vulnerability disclosures
- Performance degradation detection
- Failed backup or replication events

---

## 3. Maintenance Schedule

### 3.1 Daily Tasks (Automated)

| Time (AST) | Task | System | Owner |
|:---|:---|:---|:---|
| 00:00 | Full database backup | Database Server | Automated |
| 01:00 | Incremental file backup | Application Server | Automated |
| 02:00 | Log rotation and archival | All Servers | Automated |
| 03:00 | Temporary file cleanup | All Servers | Automated |
| 04:00 | Database integrity check | Database Server | Automated |
| 05:00 | Health check report generation | Monitoring System | Automated |
| Every 15 min | Backup verification check | Backup System | Automated |

**Daily Manual Tasks:**

| Task | Time | Owner | Duration |
|:---|:---|:---|:---|
| Review overnight backup reports | 08:00 | Operations Team | 15 min |
| Check system health dashboard | 08:30 | Operations Team | 15 min |
| Verify payment gateway status | 09:00 | Operations Team | 10 min |
| Review error logs and alerts | 10:00 | Operations Team | 30 min |
| Check SSL certificate expiry | 16:00 | Operations Team | 10 min |

### 3.2 Weekly Tasks

| Day | Task | System | Duration | Window |
|:---|:---|:---|:---|:---|
| Sunday | Database index maintenance | SQL Server | 2 hours | 02:00-04:00 |
| Sunday | Application log analysis | All Systems | 1 hour | Business hours |
| Monday | Security patch review | All Systems | 1 hour | Business hours |
| Tuesday | Performance baseline review | All Systems | 2 hours | Business hours |
| Wednesday | Backup restoration test (sample) | Backup System | 1 hour | 02:00-03:00 |
| Thursday | Capacity utilization report | Infrastructure | 2 hours | Business hours |
| Friday | Maintenance activity review | All Systems | 1 hour | Business hours |

### 3.3 Monthly Tasks

| Task | Description | Duration | Window |
|:---|:---|:---|:---|
| Security patching | OS and middleware security updates | 4 hours | 4th Sunday 00:00-04:00 |
| Database maintenance | Statistics update, integrity checks | 3 hours | 2nd Sunday 02:00-05:00 |
| Full system backup test | End-to-end restoration validation | 4 hours | 3rd Saturday 22:00-02:00 |
| User access review | Audit user accounts and permissions | 2 hours | Business hours |
| SSL/TLS certificate check | Review expiry dates, plan renewals | 1 hour | Business hours |
| Disaster recovery drill | DR site failover test | 4 hours | Coordinated window |
| Performance optimization | Query tuning, configuration review | 4 hours | Planned window |

### 3.4 Quarterly Tasks

| Task | Description | Duration | Typical Window |
|:---|:---|:---|:---|
| Major version updates | Framework and platform updates | 8 hours | Planned weekend |
| Infrastructure health audit | Comprehensive system review | 16 hours | Spread over quarter |
| Security penetration testing | Vulnerability assessment | 40 hours | External vendor |
| Disaster recovery full test | Complete DR validation | 8 hours | Weekend |
| Capacity planning review | Resource forecasting | 4 hours | Business hours |
| Documentation update | Technical documentation refresh | 8 hours | Business hours |
| Third-party integration audit | API and service review | 4 hours | Business hours |

### 3.5 Annual Tasks

| Task | Description | Duration | Window |
|:---|:---|:---|:---|
| Infrastructure lifecycle review | Hardware refresh planning | 1 week | Q4 |
| Disaster recovery plan update | DR plan revision and testing | 1 week | Q1 |
| Security compliance audit | Full security assessment | 2 weeks | Q2 |
| Business continuity test | End-to-end BCP validation | 2 days | Planned |
| License and contract renewal | Software license management | 1 week | Q4 |
| Architecture review | System architecture evaluation | 1 week | Q3 |
| Maintenance procedure audit | Procedure effectiveness review | 2 days | Q4 |

### 3.6 Maintenance Calendar

```
SMART DAIRY LTD. - 2026 MAINTENANCE CALENDAR
=============================================

JANUARY 2026
------------
04 Jan (Sun) 02:00-05:00 - Monthly Database Maintenance
12 Jan (Sun) 00:00-04:00 - Security Patching Window
18 Jan (Sat) 22:00-02:00 - Backup Restoration Test

FEBRUARY 2026
-------------
01 Feb (Sun) 02:00-05:00 - Monthly Database Maintenance
08 Feb (Sun) 00:00-04:00 - Security Patching Window
21 Feb (Sat) 22:00-02:00 - Backup Restoration Test

MARCH 2026 (Q1)
---------------
01 Mar (Sun) 02:00-05:00 - Monthly Database Maintenance
08 Mar (Sun) 00:00-04:00 - Security Patching Window
15-22 Mar - Quarterly Major Updates (Planned)
20-27 Mar - Annual DR Plan Update
28 Mar (Sat) 22:00-02:00 - Quarterly DR Test

APRIL 2026
----------
05 Apr (Sun) 02:00-05:00 - Monthly Database Maintenance
12 Apr (Sun) 00:00-04:00 - Security Patching Window
25 Apr (Sat) 22:00-02:00 - Backup Restoration Test

MAY 2026
--------
03 May (Sun) 02:00-05:00 - Monthly Database Maintenance
10 May (Sun) 00:00-04:00 - Security Patching Window
23 May (Sat) 22:00-02:00 - Backup Restoration Test

JUNE 2026 (Q2)
--------------
07 Jun (Sun) 02:00-05:00 - Monthly Database Maintenance
14 Jun (Sun) 00:00-04:00 - Security Patching Window
15-30 Jun - Annual Security Compliance Audit
20-27 Jun - Quarterly Major Updates (Planned)
27 Jun (Sat) 22:00-02:00 - Quarterly DR Test

JULY 2026
---------
05 Jul (Sun) 02:00-05:00 - Monthly Database Maintenance
12 Jul (Sun) 00:00-04:00 - Security Patching Window
25 Jul (Sat) 22:00-02:00 - Backup Restoration Test

AUGUST 2026
-----------
02 Aug (Sun) 02:00-05:00 - Monthly Database Maintenance
09 Aug (Sun) 00:00-04:00 - Security Patching Window
22 Aug (Sat) 22:00-02:00 - Backup Restoration Test

SEPTEMBER 2026 (Q3)
-------------------
06 Sep (Sun) 02:00-05:00 - Monthly Database Maintenance
13 Sep (Sun) 00:00-04:00 - Security Patching Window
14-21 Sep - Annual Architecture Review
19-26 Sep - Quarterly Major Updates (Planned)
26 Sep (Sat) 22:00-02:00 - Quarterly DR Test

OCTOBER 2026
------------
04 Oct (Sun) 02:00-05:00 - Monthly Database Maintenance
11 Oct (Sun) 00:00-04:00 - Security Patching Window
24 Oct (Sat) 22:00-02:00 - Backup Restoration Test

NOVEMBER 2026
-------------
01 Nov (Sun) 02:00-05:00 - Monthly Database Maintenance
08 Nov (Sun) 00:00-04:00 - Security Patching Window
21 Nov (Sat) 22:00-02:00 - Backup Restoration Test

DECEMBER 2026 (Q4)
------------------
06 Dec (Sun) 02:00-05:00 - Monthly Database Maintenance
13 Dec (Sun) 00:00-04:00 - Security Patching Window
14-21 Dec - Infrastructure Lifecycle Review
19-26 Dec - Maintenance Procedure Audit
28 Dec (Mon) - CHANGE FREEZE BEGINS (Year-End)

HOLIDAY CHANGE FREEZES
----------------------
Eid al-Fitr 2026: TBD (3-day freeze)
Eid al-Adha 2026: TBD (5-day freeze)
Year-End 2026: December 28 - January 5, 2027
```

---

## 4. Maintenance Windows

### 4.1 Standard Windows

Standard maintenance windows are pre-approved time slots for routine maintenance activities.

| Window | Time (AST) | Duration | Approved Activities |
|:---|:---|:---|:---|
| **Daily** | 02:00-04:00 | 2 hours | Automated backups, cleanup, monitoring |
| **Weekly** | Sunday 00:00-04:00 | 4 hours | Database maintenance, patching, minor updates |
| **Monthly** | 4th Sunday 00:00-04:00 | 4 hours | Security patches, system updates |
| **Quarterly** | Planned weekend | 8 hours | Major updates, infrastructure changes |

**Business Hours:** Sunday - Thursday, 08:00-18:00 AST

### 4.2 Emergency Windows

Emergency maintenance windows are activated for critical issues requiring immediate attention.

| Severity | Activation Authority | Max Duration | Notification Lead Time |
|:---|:---|:---|:---|
| Critical (P1) | IT Director | 4 hours | Immediate |
| High (P2) | Operations Manager | 2 hours | 30 minutes |

**Emergency Window Procedure:**
1. Operations Manager assesses and approves emergency window
2. Immediate notification to stakeholders (see Section 8)
3. Maintenance executed with continuous status updates
4. Post-maintenance validation and communication
5. Incident report filed within 24 hours

### 4.3 Blackout Periods

Blackout periods are times when NO maintenance is permitted except for critical emergencies.

| Period | Dates | Reason |
|:---|:---|:---|
| **Daily Peak** | 08:00-10:00 AST | Morning order rush |
| **Daily Peak** | 17:00-19:00 AST | Evening subscription management |
| **Weekend Peak** | Friday 14:00-18:00 | Weekend delivery planning |
| **Month-End** | Last 3 days of month | Financial closing |
| **Eid al-Fitr** | 3 days (TBD by moon sighting) | Major holiday |
| **Eid al-Adha** | 5 days (TBD by moon sighting) | Major holiday |
| **Year-End** | December 28 - January 5 | Financial year closing |

**Blackout Period Exemptions:**
- Critical security vulnerabilities (CVSS > 9.0)
- Active system compromise
- Data loss scenarios
- Regulatory compliance requirements

---

## 5. Change Freeze Periods

### 5.1 Eid Holidays

| Holiday | Duration | Freeze Period | Exceptions |
|:---|:---|:---|:---|
| **Eid al-Fitr** | 3 days | 1 day before to 1 day after | Security critical only |
| **Eid al-Adha** | 5 days | 1 day before to 1 day after | Security critical only |

**Eid Freeze Procedures:**
- All non-essential changes must be completed 48 hours before freeze
- Emergency CAB approval required for any changes during freeze
- Enhanced monitoring activated throughout freeze period
- On-call team on standby

### 5.2 Month-End Freeze

| Period | Duration | Activities Affected |
|:---|:---|:---|
| **Month-End** | Last 3 calendar days | All non-critical changes |

**Month-End Considerations:**
- Financial reporting systems locked
- Order processing must remain stable
- No database schema changes
- No payment gateway modifications

### 5.3 Year-End Freeze

| Period | Duration | Activities Affected |
|:---|:---|:---|
| **Year-End** | December 28 - January 5 | All changes except critical security |

**Year-End Procedures:**
- Complete system freeze for non-essential activities
- Annual audit preparations
- Data archival activities
- Financial system stability priority

---

## 6. Maintenance Planning

### 6.1 RFC Creation

All scheduled maintenance requires a Request for Change (RFC) document.

**RFC Requirements:**

| Field | Description | Mandatory |
|:---|:---|:---:|
| RFC ID | Unique identifier (Format: RFC-YYYY-NNNN) | Yes |
| Title | Brief description of change | Yes |
| Requester | Person requesting the change | Yes |
| Date Submitted | RFC submission date | Yes |
| Proposed Date/Time | Scheduled maintenance window | Yes |
| Duration | Estimated duration | Yes |
| System(s) Affected | List of impacted systems | Yes |
| Change Type | Standard/Normal/Emergency | Yes |
| Change Category | Hardware/Software/Network/Security | Yes |
| Description | Detailed change description | Yes |
| Business Justification | Why change is needed | Yes |
| Risk Assessment | Identified risks and mitigations | Yes |
| Backout Plan | Rollback procedure | Yes |
| Testing Plan | Validation approach | Yes |
| Resources Required | Personnel and tools needed | Yes |
| Communication Plan | Stakeholder notification plan | Yes |

**RFC Submission Timeline:**
- Standard Changes: 5 business days before maintenance
- Normal Changes: 10 business days before maintenance
- Emergency Changes: As soon as identified

### 6.2 CAB Approval

The Change Advisory Board (CAB) reviews and approves all maintenance RFCs.

| Change Type | CAB Review | Approval Authority |
|:---|:---|:---|
| Standard (pre-approved) | Record only | Operations Manager |
| Normal | Full CAB review | IT Director |
| Emergency | Emergency CAB | IT Director + Operations Manager |

**CAB Meeting Schedule:**
- Regular CAB: Every Tuesday 10:00 AST
- Emergency CAB: Within 4 hours of request

**CAB Approval Criteria:**
1. Business justification is valid
2. Risk assessment is complete
3. Backout plan is documented and tested
4. Testing plan is adequate
5. Maintenance window is appropriate
6. No conflicts with blackout periods
7. Resources are available
8. Communication plan is complete

### 6.3 Communication Plan

Every maintenance RFC must include a communication plan.

| Stakeholder | Notification Timing | Method | Content |
|:---|:---|:---|:---|
| **Internal IT Team** | CAB approval + 48 hours | Email + Ticket | Full RFC details |
| **Business Users** | 72 hours before | Email + Portal banner | Impact summary |
| **External Users** | 48 hours before | Portal banner + SMS | Service availability |
| **Management** | CAB approval + 24 hours | Email summary | High-level impact |
| **Vendors** | As required per contract | Email/Phone | Coordination needs |

---

## 7. Maintenance Execution

### 7.1 Pre-Maintenance Checklist

**24 Hours Before:**

| # | Checklist Item | Status | Initials | Time |
|:---|:---|:---:|:---:|:---:|
| 1 | RFC approved and CAB authorized | ☐ | | |
| 2 | Communication sent to all stakeholders | ☐ | | |
| 3 | Portal maintenance banner activated | ☐ | | |
| 4 | Backup completed and verified | ☐ | | |
| 5 | Rollback procedure reviewed | ☐ | | |
| 6 | Testing plan confirmed | ☐ | | |
| 7 | Required resources available | ☐ | | |
| 8 | Vendor support confirmed (if needed) | ☐ | | |

**1 Hour Before:**

| # | Checklist Item | Status | Initials | Time |
|:---|:---|:---:|:---:|:---:|
| 1 | All stakeholders reminded of maintenance | ☐ | | |
| 2 | Active user sessions identified | ☐ | | |
| 3 | Critical transactions completed/quiesced | ☐ | | |
| 4 | Monitoring alerts configured for maintenance | ☐ | | |
| 5 | War room/bridge line established (if major) | ☐ | | |
| 6 | Rollback materials prepared and accessible | ☐ | | |

**Immediately Before:**

| # | Checklist Item | Status | Initials | Time |
|:---|:---|:---:|:---:|:---:|
| 1 | Final backup snapshot taken | ☐ | | |
| 2 | Configuration baseline recorded | ☐ | | |
| 3 | Maintenance mode activated (if applicable) | ☐ | | |
| 4 | User access restricted | ☐ | | |
| 5 | Team assembled and roles confirmed | ☐ | | |

### 7.2 Execution Procedures

**Phase 1: Preparation (T-30 minutes)**
```
1. Conduct final readiness check
2. Confirm all team members in position
3. Verify rollback capability
4. Document starting state
5. Begin maintenance log
```

**Phase 2: Implementation (T-0 to T+N)**
```
1. Execute change according to RFC
2. Document all actions in maintenance log
3. Monitor system metrics continuously
4. Communicate status every 30 minutes
5. Escalate immediately if issues arise
```

**Phase 3: Verification (Post-implementation)**
```
1. Execute testing plan
2. Verify system functionality
3. Check all integrated systems
4. Confirm monitoring is active
5. Validate backup systems
```

**Phase 4: Closure**
```
1. Remove maintenance mode
2. Restore user access
3. Update portal status
4. Send completion notification
5. Close maintenance log
6. Update RFC status
```

### 7.3 Post-Maintenance Verification

**Technical Verification:**

| # | Verification Item | Method | Pass Criteria |
|:---|:---|:---|:---|
| 1 | System startup | Visual check | All services running |
| 2 | Application functionality | Smoke tests | Core functions work |
| 3 | Database connectivity | Query test | Connection successful |
| 4 | API endpoints | Automated test | All endpoints respond |
| 5 | Payment processing | Test transaction | Success response |
| 6 | SMS notifications | Test message | Delivery confirmed |
| 7 | Monitoring systems | Dashboard check | All metrics reporting |
| 8 | Backup systems | Status check | Backup jobs running |
| 9 | Security systems | Log review | No security alerts |
| 10 | Performance baseline | Load test | Within normal parameters |

**Business Verification:**

| # | Verification Item | Method | Owner |
|:---|:---|:---|:---|
| 1 | Order placement | End-to-end test | Business User |
| 2 | Subscription management | Function test | Business User |
| 3 | Payment reconciliation | Report check | Finance |
| 4 | Delivery scheduling | Workflow test | Operations |
| 5 | Customer portal | User journey test | Support Team |

**Verification Timeline:**
- Immediate: Technical verification (0-30 minutes post-maintenance)
- Short-term: Business verification (30-60 minutes post-maintenance)
- Extended: Stability monitoring (24-72 hours post-maintenance)

---

## 8. Maintenance Communication

### 8.1 Stakeholder Notification

**Notification Matrix:**

| Stakeholder Group | Roles | Primary Contact Method |
|:---|:---|:---|
| **Executive** | CEO, COO, CFO | Email + SMS for critical |
| **IT Leadership** | IT Director, Managers | Email + Teams/Slack |
| **Operations** | Operations Manager, Team | Teams/Slack + Email |
| **Business Users** | Department Heads, Supervisors | Email + Portal |
| **End Users** | All portal users | Portal banner + SMS |
| **External Partners** | Payment gateway, SMS provider | Email + Phone |

### 8.2 User Communication

**Advance Notice Template:**

```
=================================================================
SMART DAIRY LTD. - SCHEDULED MAINTENANCE NOTIFICATION
=================================================================

Dear Valued Customer,

We would like to inform you of an upcoming scheduled maintenance 
for the Smart Dairy Web Portal.

MAINTENANCE DETAILS:
--------------------
Date: [DATE]
Time: [START TIME] to [END TIME] (AST)
Duration: [DURATION]
Impact: [BRIEF DESCRIPTION OF IMPACT]

SERVICES AFFECTED:
------------------
- [List affected services]

SERVICES NOT AFFECTED:
----------------------
- [List unaffected services, if any]

WHAT YOU NEED TO DO:
--------------------
- Complete pending orders before maintenance begins
- Save any unsaved work
- Plan your activities accordingly

We apologize for any inconvenience and appreciate your patience.

For assistance, contact our 24/7 support:
Phone: [SUPPORT PHONE]
Email: [SUPPORT EMAIL]

Thank you for choosing Smart Dairy Ltd.
=================================================================
```

**In-Progress Update Template:**

```
=================================================================
MAINTENANCE IN PROGRESS - UPDATE #[N]
=================================================================

Status: [IN PROGRESS / EXTENDED / COMPLETING SOON]

Current Time: [TIME]
Expected Completion: [UPDATED TIME]

Progress: [PERCENTAGE]%
Current Activity: [DESCRIPTION]

Next Update: [TIME]

We apologize for any inconvenience caused.
=================================================================
```

**Completion Notice Template:**

```
=================================================================
MAINTENANCE COMPLETED - SMART DAIRY WEB PORTAL
=================================================================

Dear Valued Customer,

We are pleased to inform you that the scheduled maintenance has 
been completed successfully.

COMPLETION DETAILS:
-------------------
Completed At: [TIME]
Actual Duration: [DURATION]

SERVICES RESTORED:
------------------
All Smart Dairy Web Portal services are now fully operational.

CHANGES IMPLEMENTED:
--------------------
- [Brief list of improvements/updates]

If you experience any issues, please contact our support team:
Phone: [SUPPORT PHONE]
Email: [SUPPORT EMAIL]

Thank you for your patience.
=================================================================
```

### 8.3 Status Updates

**Update Frequency:**

| Maintenance Duration | Update Frequency |
|:---|:---|
| < 1 hour | Start and completion only |
| 1-4 hours | Every 30 minutes |
| 4-8 hours | Every 30 minutes |
| > 8 hours | Every 1 hour |

**Status Update Channels:**
- Internal: Teams/Slack channel #maintenance-updates
- External: Portal status page
- Critical: Direct phone/SMS to executives

---

## 9. Rollback Procedures

### 9.1 Rollback Decision Criteria

Rollback should be initiated when:

| Condition | Action |
|:---|:---|
| System fails to start after maintenance | Immediate rollback |
| Critical functionality not working after 30 min | Initiate rollback |
| Performance degraded beyond acceptable limits | Evaluate rollback |
| Data integrity issues detected | Immediate rollback |
| Security compromise suspected | Immediate rollback |
| Exceeded maintenance window by 50% | Evaluate rollback |

**Rollback Authority:**
- Operations Manager: Can authorize rollback
- IT Director: Must be notified of all rollbacks
- On-call Engineer: Can initiate emergency rollback

### 9.2 Rollback Procedures

**Database Rollback:**

```
DATABASE ROLLBACK PROCEDURE
===========================

Prerequisites:
- Verified backup available
- Maintenance window still active or emergency approved
- Rollback team assembled

Steps:
1. STOP all application services
   → Execute: Stop-Service SmartDairy-*

2. Document current (failed) state
   → Capture logs, error messages, screenshots

3. Restore database from pre-maintenance backup
   → RESTORE DATABASE SmartDairy FROM DISK = '[backup_path]'
   → WITH REPLACE, RECOVERY;

4. Verify database restoration
   → DBCC CHECKDB (SmartDairy);
   → SELECT COUNT(*) FROM critical_tables;

5. Restore application configuration
   → Copy backup config files to application directory

6. Restart application services
   → Start-Service SmartDairy-*

7. Verify functionality
   → Execute smoke tests
   → Verify user login
   → Test critical transactions

8. Notify stakeholders of rollback completion

9. Document rollback in maintenance log

10. Schedule post-incident review
```

**Application Rollback:**

```
APPLICATION ROLLBACK PROCEDURE
==============================

Prerequisites:
- Previous version package available
- Configuration backups available
- Rollback window approved

Steps:
1. Enable maintenance mode
   → Update web.config: MaintenanceMode = true

2. Stop application pool
   → Stop-WebAppPool -Name "SmartDairyPortal"

3. Backup current (failed) version
   → Rename current folder to: Application_[timestamp]_failed

4. Restore previous version
   → Copy backup folder to application directory

5. Restore configuration
   → Restore web.config, appsettings.json from backup

6. Start application pool
   → Start-WebAppPool -Name "SmartDairyPortal"

7. Verify application
   → Check application logs
   → Execute health check endpoint
   → Verify database connectivity

8. Disable maintenance mode
   → Update web.config: MaintenanceMode = false

9. Monitor for 30 minutes

10. Notify stakeholders
```

**Infrastructure Rollback:**

```
INFRASTRUCTURE ROLLBACK PROCEDURE
=================================

Prerequisites:
- Infrastructure-as-code backups
- Network configuration backups
- Emergency CAB approval (if outside window)

Steps:
1. Isolate affected infrastructure

2. Document current state

3. Execute rollback based on change type:
   
   For VM changes:
   → Restore from snapshot
   → Reconfigure network settings
   
   For network changes:
   → Restore switch/router configs
   → Verify connectivity
   
   For storage changes:
   → Restore storage configuration
   → Verify data accessibility

4. Verify all systems operational

5. Update monitoring configuration

6. Notify stakeholders
```

### 9.3 Rollback Testing

**Quarterly Rollback Drills:**
- Test database restoration procedures
- Verify application version rollback
- Validate infrastructure recovery
- Document lessons learned

**Rollback Success Criteria:**
- System restored to pre-maintenance state within 1 hour
- No data loss (within RPO)
- All services operational
- Users can access system

---

## 10. Maintenance Records

### 10.1 Documentation Requirements

All maintenance activities must be documented in the Maintenance Log.

**Required Documentation:**

| Document | Purpose | Retention |
|:---|:---|:---|
| RFC Form | Change authorization | 7 years |
| Maintenance Log | Activity record | 7 years |
| Pre-Maintenance Checklist | Readiness verification | 3 years |
| Post-Maintenance Verification | Validation record | 3 years |
| Rollback Log | Recovery documentation | 7 years |
| CAB Meeting Minutes | Approval record | 7 years |

### 10.2 Maintenance Log Format

```
=================================================================
MAINTENANCE LOG
=================================================================

RFC ID: [RFC-YYYY-NNNN]
Maintenance Title: [TITLE]
Date: [DATE]
Window: [START] - [END]

PERSONNEL:
----------
Maintenance Lead: [NAME]
Technical Lead: [NAME]
Business Liaison: [NAME]

PRE-MAINTENANCE:
----------------
Backup Completed: [TIME] | By: [NAME] | Verified: [YES/NO]
Stakeholders Notified: [TIME] | Method: [EMAIL/SMS/BANNER]
Checklist Complete: [YES/NO] | Time: [TIME]

EXECUTION LOG:
--------------
[TIME] - [ACTION] - [PERSON] - [RESULT]
[TIME] - [ACTION] - [PERSON] - [RESULT]
...

ISSUES ENCOUNTERED:
-------------------
[TIME] - [ISSUE DESCRIPTION] - [RESOLUTION]

VERIFICATION:
-------------
Technical Tests: [PASS/FAIL] | Time: [TIME] | By: [NAME]
Business Tests: [PASS/FAIL] | Time: [TIME] | By: [NAME]

COMPLETION:
-----------
Actual End Time: [TIME]
Total Duration: [DURATION]
Status: [SUCCESSFUL / ROLLED BACK / PARTIAL]

POST-MAINTENANCE:
-----------------
Stakeholders Notified: [TIME]
Portal Status Updated: [TIME]
RFC Closed: [TIME]

SIGN-OFF:
---------
Maintenance Lead: _________________ Date: _______
Operations Manager: _________________ Date: _______

=================================================================
```

### 10.3 Reporting and Metrics

**Monthly Maintenance Report:**

| Metric | Target | Actual |
|:---|:---|:---|
| Maintenance Windows Scheduled | As planned | [Count] |
| Maintenance Windows Completed | 100% | [Count] |
| Successful Completions | > 95% | [Percentage] |
| Rollbacks Required | < 5% | [Count] |
| Average Duration Variance | < 10% | [Percentage] |
| Stakeholder Complaints | 0 | [Count] |

**Quarterly Review Agenda:**
1. Maintenance schedule adherence
2. Success/rollback rate analysis
3. Duration variance analysis
4. Stakeholder feedback review
5. Process improvement opportunities
6. Resource utilization review

---

## 11. Appendices

### Appendix A: Pre-Maintenance Checklist Template

```
PRE-MAINTENANCE CHECKLIST
RFC ID: ___________________
Maintenance: _________________________________________
Date: ___________ Window: ___________ to ___________

PREPARATION (24-48 Hours Before):
---------------------------------
☐ RFC approved by CAB
☐ Communication plan executed
☐ All stakeholders notified
☐ Backup completed and verified
☐ Rollback procedure documented
☐ Testing plan prepared
☐ Resources confirmed available
☐ Vendor support engaged (if required)

PREPARATION (1-2 Hours Before):
-------------------------------
☐ Reminder notifications sent
☐ Active sessions identified
☐ Pending transactions completed
☐ Monitoring alerts configured
☐ Rollback materials accessible
☐ Team assembled
☐ War room ready (if major maintenance)

FINAL CHECKS (15 Minutes Before):
---------------------------------
☐ Final backup/snapshot taken
☐ Configuration baseline recorded
☐ Database transaction log backed up
☐ Application logs reviewed
☐ Maintenance mode ready to activate
☐ Rollback decision authority confirmed
☐ Communication channels active

SIGN-OFF:
---------
Prepared By: _________________ Date/Time: ___________
Reviewed By: _________________ Date/Time: ___________
Approved By: _________________ Date/Time: ___________
```

### Appendix B: Post-Maintenance Verification Checklist

```
POST-MAINTENANCE VERIFICATION CHECKLIST
RFC ID: ___________________
Maintenance: _________________________________________
Completion Time: ___________

TECHNICAL VERIFICATION:
-----------------------
☐ All services started successfully
☐ No critical errors in system logs
☐ Database connectivity verified
☐ API endpoints responding correctly
☐ Payment gateway integration functional
☐ SMS service operational
☐ Email notifications working
☐ File uploads/downloads functional
☐ Search functionality operational
☐ Reporting services running
☐ Monitoring agents reporting
☐ Backup jobs scheduled

PERFORMANCE VERIFICATION:
-------------------------
☐ Page load times < 2 seconds
☐ API response times < 1 second
☐ Database query performance normal
☐ CPU utilization < 50% baseline
☐ Memory utilization < 70% baseline
☐ Disk I/O within normal parameters
☐ Network latency acceptable

FUNCTIONAL VERIFICATION:
------------------------
☐ User registration/login
☐ Product catalog browsing
☐ Shopping cart operations
☐ Order placement and confirmation
☐ Payment processing
☐ Order tracking
☐ Subscription management
☐ Customer profile updates
☐ Delivery address management
☐ Notification preferences
☐ Invoice generation
☐ Report downloads

INTEGRATION VERIFICATION:
-------------------------
☐ Payment gateway (KNET/Visa/Mastercard)
☐ SMS notification service
☐ Email service provider
☐ GPS/Location services
☐ External inventory system
☐ Accounting system sync

BUSINESS VERIFICATION:
----------------------
☐ End-to-end order workflow
☐ Delivery route optimization
☐ Inventory updates
☐ Financial reconciliation

SIGN-OFF:
---------
Technical Verified By: _________________ Date/Time: ___________
Business Verified By: _________________ Date/Time: ___________
```

### Appendix C: Communication Templates

**Template C1: Initial Maintenance Announcement (72 hours)**

```
Subject: Scheduled Maintenance - Smart Dairy Web Portal - [DATE]

Dear Team,

Please be informed that scheduled maintenance will be performed on 
the Smart Dairy Web Portal as detailed below:

MAINTENANCE WINDOW:
Date: [DAY, DATE MONTH YEAR]
Time: [START TIME] to [END TIME] (AST)
Duration: [X hours]

PURPOSE:
[Brief description of maintenance purpose]

IMPACT:
- The Smart Dairy Web Portal will be unavailable during this window
- Mobile applications will be offline
- Order placement will be suspended
- Subscription modifications will be unavailable

WORKAROUND:
- Emergency orders: Contact customer service at [PHONE]
- Subscription changes: Will be processed after maintenance

We will provide updates at [URL] and via SMS.

Please plan accordingly.

Regards,
Operations Team
Smart Dairy Ltd.
```

**Template C2: Maintenance Reminder (24 hours)**

```
Subject: REMINDER: Scheduled Maintenance Tomorrow - Smart Dairy Portal

Dear Valued Customer,

This is a friendly reminder that scheduled maintenance for the 
Smart Dairy Web Portal will begin in 24 hours.

Date: [DATE]
Time: [TIME] to [TIME] (AST)

Please complete any pending orders before the maintenance window.

Thank you for your cooperation.

Smart Dairy Team
```

**Template C3: Maintenance Start Notification**

```
Subject: MAINTENANCE STARTED - Smart Dairy Web Portal

Dear Valued Customer,

The scheduled maintenance has begun as planned.

Start Time: [TIME]
Expected Completion: [TIME]

The Smart Dairy Web Portal is currently unavailable.

We will notify you once services are restored.

Thank you for your patience.

Smart Dairy Team
```

**Template C4: Maintenance Completion**

```
Subject: MAINTENANCE COMPLETED - Smart Dairy Web Portal

Dear Valued Customer,

We are pleased to announce that the scheduled maintenance has been 
successfully completed.

Completion Time: [TIME]
Actual Duration: [DURATION]

All Smart Dairy Web Portal services are now fully operational.

[Include brief summary of improvements if applicable]

If you experience any issues, please contact our support team:
Phone: [PHONE]
Email: [EMAIL]

Thank you for your patience.

Smart Dairy Team
```

**Template C5: Maintenance Extension Notice**

```
Subject: MAINTENANCE EXTENDED - Smart Dairy Web Portal

Dear Valued Customer,

Due to [REASON], the scheduled maintenance is taking longer than 
anticipated.

Original Completion: [TIME]
Revised Completion: [NEW TIME]
Additional Duration: [DURATION]

We sincerely apologize for the extended downtime and any 
inconvenience this may cause.

Our team is working diligently to complete the maintenance as 
quickly as possible.

Next update: [TIME]

Thank you for your continued patience.

Smart Dairy Team
```

### Appendix D: Rollback Decision Flowchart

```
                         MAINTENANCE EXECUTION
                                 |
                                 v
                    +------------------------+
                    |   Issue Detected?      |
                    +------------------------+
                          |          |
                       YES |          | NO
                          |          |
                          v          v
               +-------------+    +-------------+
               |   CRITICAL  |    |   SUCCESS   |
               |   ISSUE?    |    |   Continue  |
               +-------------+    +-------------+
                    |    |
                 YES|    |NO
                    |    |
                    v    v
         +----------------------+
         | Assess Issue Severity|
         +----------------------+
                    |
       +------------+------------+
       |                         |
       v                         v
+----------------+      +------------------+
| Can fix within |      | Cannot fix within|
| 30 minutes?    |      | time/effort      |
+----------------+      +------------------+
       |                         |
    YES|                         |NO
       |                         |
       v                         v
+----------------+      +------------------+
| Attempt fix    |      | INITIATE ROLLBACK|
| Continue       |      |                  |
+----------------+      +------------------+
       |
       v
+----------------+
| Fix successful?|
+----------------+
       |      |
    YES|      |NO
       |      |
       v      v
+----------+ +----------+
| Continue | | ROLLBACK |
|          | |          |
+----------+ +----------+
```

### Appendix E: Emergency Contact List

| Role | Name | Primary Phone | Secondary Phone | Email |
|:---|:---|:---|:---|:---|
| IT Director | [TBD] | +965 XXXX XXXX | +965 XXXX XXXX | [email] |
| Operations Manager | [TBD] | +965 XXXX XXXX | +965 XXXX XXXX | [email] |
| Infrastructure Lead | [TBD] | +965 XXXX XXXX | +965 XXXX XXXX | [email] |
| Database Administrator | [TBD] | +965 XXXX XXXX | +965 XXXX XXXX | [email] |
| Application Lead | [TBD] | +965 XXXX XXXX | +965 XXXX XXXX | [email] |
| Security Officer | [TBD] | +965 XXXX XXXX | +965 XXXX XXXX | [email] |
| Vendor Support - Cloud | [TBD] | +XXX XXXX XXXX | - | [email] |
| Vendor Support - Payment | [TBD] | +XXX XXXX XXXX | - | [email] |
| Vendor Support - SMS | [TBD] | +XXX XXXX XXXX | - | [email] |

### Appendix F: Maintenance Window Summary

| Window Type | Schedule | Lead Time | Approval |
|:---|:---|:---|:---|
| Daily Automated | 02:00-04:00 AST | N/A | Pre-approved |
| Weekly | Sunday 00:00-04:00 | 5 days | Operations Manager |
| Monthly | 4th Sunday 00:00-04:00 | 10 days | IT Director (CAB) |
| Quarterly | Planned weekend | 30 days | IT Director (CAB) |
| Emergency | As needed | Immediate | IT Director |

### Appendix G: Key Performance Indicators

| KPI | Target | Measurement |
|:---|:---|:---|
| Planned Maintenance Success Rate | > 95% | (Successful / Total) × 100 |
| Emergency Maintenance Frequency | < 2/month | Count per month |
| Rollback Rate | < 5% | (Rollbacks / Total) × 100 |
| Maintenance Window Overrun | < 10% | (Actual - Planned) / Planned |
| Stakeholder Satisfaction | > 4.0/5.0 | Post-maintenance survey |
| Mean Time To Repair (MTTR) | < 4 hours | Average from failure to fix |
| Change Freeze Violations | 0 | Count of unauthorized changes |

---

## Document Approval

| Role | Name | Signature | Date |
|:---|:---|:---:|:---:|
| **Author** | Operations Manager | _________________ | ___________ |
| **Owner** | Operations Manager | _________________ | ___________ |
| **Reviewer** | IT Director | _________________ | ___________ |
| **Approver** | IT Director | _________________ | ___________ |

---

*Document Control: This document is controlled. All copies must bear the current revision number and date. Obsolete copies must be destroyed.*

**Next Review Date: January 31, 2027**

---
*End of Document L-013: Scheduled Maintenance Procedures*
