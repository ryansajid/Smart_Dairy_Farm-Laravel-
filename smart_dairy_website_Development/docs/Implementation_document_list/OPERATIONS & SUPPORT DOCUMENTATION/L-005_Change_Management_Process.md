# Change Management Process

---

## Document Control

| Attribute | Value |
|-----------|-------|
| **Document ID** | L-005 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Change Manager |
| **Owner** | Change Manager |
| **Reviewer** | IT Director |
| **Status** | Approved |

## Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 31, 2026 | Change Manager | Initial version |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Change Types](#2-change-types)
3. [Change Categories](#3-change-categories)
4. [Change Advisory Board (CAB)](#4-change-advisory-board-cab)
5. [Change Lifecycle](#5-change-lifecycle)
6. [Risk Assessment](#6-risk-assessment)
7. [Implementation Planning](#7-implementation-planning)
8. [Communication Plan](#8-communication-plan)
9. [Emergency Changes](#9-emergency-changes)
10. [Change Schedule](#10-change-schedule)
11. [Metrics and Reporting](#11-metrics-and-reporting)
12. [Appendices](#12-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the Change Management Process for Smart Dairy Ltd's Smart Web Portal System and associated IT infrastructure. The purpose is to ensure that all changes are properly assessed, approved, implemented, and reviewed to minimize disruption to business operations while enabling necessary improvements.

### 1.2 Scope

This process applies to:

- **In Scope:**
  - Application changes to Smart Web Portal System
  - Infrastructure modifications (servers, network, storage)
  - Database schema changes
  - Security configuration changes
  - Third-party software updates
  - Hardware replacements and upgrades
  - Process and procedure changes

- **Out of Scope:**
  - Routine operational tasks (monitoring, backups)
  - Standard user account management
  - Document updates not affecting operations
  - Emergency security patches (handled under Emergency Change process)

### 1.3 Objectives

The Change Management Process aims to:

1. Minimize business disruption from changes
2. Ensure changes align with business requirements
3. Maintain service quality and availability
4. Provide clear accountability for all changes
5. Enable efficient resource planning
6. Capture lessons learned for continuous improvement
7. Ensure compliance with regulatory requirements

### 1.4 Definitions

| Term | Definition |
|------|------------|
| **Change** | Addition, modification, or removal of anything that could affect IT services |
| **RFC** | Request for Change - formal submission for a change |
| **CAB** | Change Advisory Board - group that assesses and authorizes changes |
| **ECAB** | Emergency CAB - subset for urgent change authorization |
| **FSC** | Forward Schedule of Changes - upcoming change calendar |

---

## 2. Change Types

### 2.1 Standard Changes

**Definition:** Pre-approved, low-risk changes that follow established procedures.

**Characteristics:**
- Fully documented and tested procedures
- Low risk and minimal business impact
- No requirement for CAB approval
- Can be implemented during business hours

**Examples:**
| Change | Procedure Reference |
|--------|---------------------|
| Adding new user accounts | OPS-001 User Management |
| Scheduled antivirus updates | SEC-003 Security Patching |
| Routine database backups | OPS-005 Backup Procedures |
| Non-critical patch installation | OPS-007 Patch Management |
| Monitoring configuration updates | OPS-009 Monitoring Setup |

**Process:**
1. Create RFC using Standard Change Template
2. Self-assess using Standard Change Checklist
3. Implement per documented procedure
4. Document outcome in change log
5. Close RFC within 24 hours

### 2.2 Normal Changes

**Definition:** Changes that require assessment and authorization through the CAB.

**Characteristics:**
- Requires formal RFC submission
- Assessed by Change Manager
- Reviewed by CAB based on category
- Requires approval before implementation
- Must have rollback plan

**Categories:**
- Minor (Category 3) - CAB Delegate approval
- Significant (Category 2) - Full CAB approval
- Major (Category 1) - Full CAB + Management approval

### 2.3 Emergency Changes

**Definition:** Changes required to restore service or prevent major business impact.

**Characteristics:**
- Expedited approval process
- May be implemented before full documentation
- Post-implementation review mandatory
- ECAB authorization within 2 hours

**Criteria for Emergency Classification:**
- [ ] Service outage affecting critical business function
- [ ] Security vulnerability requiring immediate remediation
- [ ] Regulatory compliance deadline at risk
- [ ] Data loss or corruption prevention
- [ ] Safety or health risk

**Not Emergency:**
- Planned upgrades
- Non-critical patches
- Feature enhancements
- Routine maintenance

---

## 3. Change Categories

### 3.1 Risk-Based Categorization

All Normal Changes are categorized based on risk and business impact:

#### Category 3: Minor Change (Low Risk)

| Attribute | Criteria |
|-----------|----------|
| **Impact** | Single user or small user group |
| **Risk** | Low - tested procedure, minimal dependencies |
| **Downtime** | Less than 30 minutes, off-peak only |
| **Cost** | Under 50,000 PKR |
| **Approval** | Change Manager or Delegate |
| **Lead Time** | 3 business days |

**Examples:**
- Configuration changes to non-production systems
- Minor UI updates
- Report modifications
- Documentation updates with system changes
- Small feature enhancements

#### Category 2: Significant Change (Medium Risk)

| Attribute | Criteria |
|-----------|----------|
| **Impact** | Department or multiple user groups |
| **Risk** | Medium - requires testing, some dependencies |
| **Downtime** | Up to 2 hours, requires scheduled maintenance window |
| **Cost** | 50,000 - 500,000 PKR |
| **Approval** | Full CAB |
| **Lead Time** | 5-10 business days |

**Examples:**
- Module upgrades or updates
- Database schema changes
- Integration modifications
- Security policy updates
- Infrastructure configuration changes

#### Category 1: Major Change (High Risk)

| Attribute | Criteria |
|-----------|----------|
| **Impact** | Organization-wide or external stakeholders |
| **Risk** | High - complex, many dependencies, significant testing required |
| **Downtime** | More than 2 hours or during business hours |
| **Cost** | Over 500,000 PKR |
| **Approval** | Full CAB + IT Director/Business Sponsor |
| **Lead Time** | 15+ business days |

**Examples:**
- Major system upgrades (e.g., ERP, Portal version upgrades)
- Infrastructure platform migrations
- Data center relocations
- Major security architecture changes
- Business process re-engineering

### 3.2 Categorization Matrix

| Factor | Minor (Cat 3) | Significant (Cat 2) | Major (Cat 1) |
|--------|---------------|---------------------|---------------|
| Users Affected | < 10 | 10-100 | > 100 |
| Systems Modified | 1 | 2-5 | > 5 |
| Testing Required | Unit only | Integration | Full regression |
| Rollback Complexity | Simple (< 1 hr) | Moderate (1-4 hr) | Complex (> 4 hr) |
| Business Continuity Risk | Negligible | Low-Moderate | High |
| Regulatory Impact | None | Possible review | Full assessment |

---

## 4. Change Advisory Board (CAB)

### 4.1 CAB Composition

#### Full CAB Members

| Role | Responsibility | Representative |
|------|---------------|----------------|
| **CAB Chair** | Final approval authority, escalation point | Change Manager |
| **Technical Lead** | Technical feasibility assessment | Senior System Architect |
| **Operations Manager** | Operational impact, resource availability | IT Operations Manager |
| **Security Officer** | Security risk assessment | Information Security Officer |
| **Business Analyst** | Business impact, user requirements | Business Analyst |
| **QA Lead** | Testing requirements, quality assurance | Quality Assurance Lead |
| **Service Desk Manager** | Support impact, communication needs | Service Desk Manager |

#### Optional Attendees (as needed)

| Role | When to Include |
|------|-----------------|
| Application Owner | Changes affecting their system |
| Vendor Representative | Third-party product changes |
| Finance Representative | Changes > 1,000,000 PKR |
| Legal/Compliance | Regulatory impact changes |
| HR Representative | Changes affecting personnel |

### 4.2 CAB Meeting Schedule

| Meeting Type | Frequency | Duration | Time |
|--------------|-----------|----------|------|
| Regular CAB | Weekly (Tuesday) | 1 hour | 10:00 AM |
| Emergency CAB | As needed | 30 minutes | On call |
| Major Change Review | Per change | 2 hours | Scheduled |

### 4.3 CAB Agenda (Regular Meeting)

```
1. Opening and Attendance (5 min)
2. Review Previous Decisions (5 min)
3. Failed/Backed-out Changes Review (10 min)
4. New RFC Review (30 min)
   - Category 2 and 3 changes
   - Questions and clarifications
   - Risk assessment discussion
5. Upcoming Major Changes (5 min)
6. Forward Schedule Review (5 min)
```

### 4.4 Decision Making

| Change Category | Approval Required |
|-----------------|-------------------|
| Standard | Self-approved |
| Minor (Cat 3) | Change Manager |
| Significant (Cat 2) | Simple majority of CAB |
| Major (Cat 1) | Unanimous CAB + IT Director |
| Emergency | ECAB Chair + verbal CAB Chair |

### 4.5 CAB Meeting Outcomes

| Decision | Meaning | Next Step |
|----------|---------|-----------|
| **Approved** | Change authorized as presented | Proceed to implementation |
| **Approved with Conditions** | Approved pending modifications | Update RFC, resubmit for final |
| **Deferred** | More information required | RFC returned to submitter |
| **Rejected** | Change not authorized | RFC closed, reconsider if modified |
| **Escalated** | Requires higher authority | To IT Director or Steering Committee |

---

## 5. Change Lifecycle

### 5.1 Change Workflow Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        CHANGE MANAGEMENT WORKFLOW                             │
└─────────────────────────────────────────────────────────────────────────────┘

[START]
   │
   ▼
┌──────────────────────┐
│ 1. REQUEST           │
│ • Identify need      │
│ • Create RFC         │
│ • Initial assessment │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ 2. CATEGORIZE        │ No ──► Standard Change ──► Implement ──► Close
│ Standard/Normal?     │
└──────────┬───────────┘ Yes
           │
           ▼
┌──────────────────────┐
│ 3. ASSESS            │
│ • Technical review   │
│ • Risk analysis      │
│ • Impact assessment  │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ 4. APPROVE           │
│ • CAB review         │ Rejected ──► Close
│ • Authorization      │
└──────────┬───────────┘ Approved
           │
           ▼
┌──────────────────────┐
│ 5. PLAN              │
│ • Schedule           │
│ • Resources          │
│ • Rollback plan      │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ 6. IMPLEMENT         │ Failed ──► Execute Rollback ──► Review
│ • Build/Prepare      │
│ • Test               │
│ • Deploy             │
└──────────┬───────────┘ Success
           │
           ▼
┌──────────────────────┐
│ 7. VERIFY            │
│ • Post-impl testing  │
│ • Validation         │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ 8. REVIEW            │
│ • Outcome assessment │
│ • Lessons learned    │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ 9. CLOSE             │
│ • Documentation      │
│ • Final report       │
└──────────┬───────────┘
           │
           ▼
         [END]
```

### 5.2 Stage 1: Request

**Objective:** Capture and log the proposed change

**Activities:**
1. Change initiator identifies need
2. Complete RFC form (Appendix A)
3. Submit to Change Manager
4. Receive RFC number (format: RFC-YYYY-NNNN)

**Entry Criteria:**
- Change need identified
- Business justification exists

**Exit Criteria:**
- RFC created and logged
- Initial categorization assigned
- Change Manager assigned

**Timeline:** Within 24 hours of submission

### 5.3 Stage 2: Assessment

**Objective:** Evaluate change feasibility, risk, and impact

**Activities:**
1. Technical feasibility review
2. Business impact analysis
3. Resource requirement identification
4. Risk assessment (Section 6)
5. Dependency mapping
6. Security review (if applicable)

**Responsible Parties:**
- Change Manager: Coordination
- Technical Lead: Technical assessment
- Business Analyst: Business impact
- Security Officer: Security review

**Deliverables:**
- Completed risk assessment
- Resource estimates
- Preliminary implementation plan
- CAB presentation (for Cat 2 & 3)

**Timeline:**
- Cat 3: 2 business days
- Cat 2: 5 business days
- Cat 1: 10 business days

### 5.4 Stage 3: Approval

**Objective:** Obtain authorization to proceed

**Activities:**
1. CAB presentation (Cat 2 & 3)
2. Questions and clarifications
3. Vote/decision
4. Conditions documentation (if any)
5. Approval notification

**Documentation Required:**
- Completed RFC
- Risk assessment
- Implementation plan outline
- Resource requirements

**Timeline:**
- Regular CAB: Next scheduled meeting
- Emergency CAB: Within 2 hours

### 5.5 Stage 4: Implementation Planning

**Objective:** Develop detailed execution plan

**Activities:**
1. Finalize schedule
2. Confirm resources
3. Develop detailed rollback plan
4. Prepare communication
5. Coordinate with stakeholders

**Deliverables:**
- Detailed implementation plan
- Rollback procedure
- Communication plan
- Testing strategy

**Timeline:**
- Cat 3: 1 business day
- Cat 2: 3 business days
- Cat 1: 5 business days

### 5.6 Stage 5: Implementation

**Objective:** Execute the approved change

**Activities:**
1. Pre-implementation checks
2. Execute change per plan
3. Monitor progress
4. Document issues
5. Execute rollback if needed

**Responsibilities:**
- Implementation Team: Execute tasks
- Change Manager: Overall coordination
- Technical Lead: Technical oversight
- Service Desk: Monitor for issues

**Communication:**
- Start notification
- Progress updates (for long changes)
- Completion notification

### 5.7 Stage 6: Review

**Objective:** Verify successful implementation and capture lessons

**Activities:**
1. Post-implementation testing
2. Validation against requirements
3. User acceptance
4. Outcome assessment
5. Lessons learned documentation

**Review Questions:**
- Was the change successful?
- Were there any unexpected issues?
- Was the rollback plan adequate?
- Were resources sufficient?
- What can be improved?

**Timeline:** Within 48 hours of implementation

### 5.8 Stage 7: Closure

**Objective:** Complete change record and update systems

**Activities:**
1. Final documentation
2. CMDB updates (if applicable)
3. RFC status update
4. Final report distribution
5. Archive documentation

**Closure Criteria:**
- Change successfully implemented
- Testing complete and passed
- Documentation updated
- Stakeholders notified

**Timeline:** Within 5 business days of implementation

---

## 6. Risk Assessment

### 6.1 Risk Assessment Matrix

```
                    IMPACT
              Low    Med    High   Critical
         ┌────────┬────────┬────────┬────────┐
    High │   4    │   7    │   11   │   15   │
         │  Low   │  Med   │  High  │ Critical
         ├────────┼────────┼────────┼────────┤
   Med-High│   3   │   6    │   10   │   14   │
LIKELIHOOD│  Low   │  Med   │  High  │ Critical
         ├────────┼────────┼────────┼────────┤
  Medium │   2    │   5    │   9    │   13   │
         │  Low   │  Med   │  High  │ Critical
         ├────────┼────────┼────────┼────────┤
    Low  │   1    │   4    │   8    │   12   │
         │  Low   │  Low   │  Med   │  High
         └────────┴────────┴────────┴────────┘

Score: 1-5 (Low/Minor) | 6-9 (Medium) | 10-14 (High) | 15-16 (Critical)
```

### 6.2 Impact Criteria

| Level | Business Impact | Technical Impact | Financial Impact |
|-------|-----------------|------------------|------------------|
| **Critical** | Business operations halted | Complete system failure | > 10,000,000 PKR |
| **High** | Major business disruption | Core function affected | 2,000,000 - 10,000,000 PKR |
| **Medium** | Some business impact | Non-core function affected | 500,000 - 2,000,000 PKR |
| **Low** | Minimal impact | Isolated component affected | < 500,000 PKR |

### 6.3 Likelihood Criteria

| Level | Probability | Description |
|-------|-------------|-------------|
| **High** | > 70% | Similar changes have failed before; complex; untested |
| **Med-High** | 50-70% | Some complexity; limited testing; new technology |
| **Medium** | 30-50% | Moderate complexity; some testing performed |
| **Low** | < 30% | Simple; well tested; proven procedure |

### 6.4 Risk Treatment

| Risk Level | Treatment | Approval Authority |
|------------|-----------|-------------------|
| **Critical (15-16)** | Do not proceed without mitigation | Steering Committee |
| **High (10-14)** | Mitigation required before approval | IT Director |
| **Medium (6-9)** | Standard controls apply | CAB Chair |
| **Low (1-5)** | Standard controls apply | Change Manager |

### 6.5 Risk Assessment Template

| Risk ID | Description | Likelihood | Impact | Score | Treatment | Residual Risk |
|---------|-------------|------------|--------|-------|-----------|---------------|
| R001 | [Risk description] | Low/Med/High/Critical | Low/Med/High/Critical | 1-16 | Accept/Mitigate/Transfer/Avoid | Score after treatment |

### 6.6 Common Change Risks

| Risk | Mitigation Strategy |
|------|---------------------|
| Data loss | Backup verification, phased rollout |
| System unavailability | Maintenance windows, redundancy |
| Performance degradation | Load testing, gradual rollout |
| Integration failure | Integration testing, vendor support |
| Security vulnerability | Security review, penetration testing |
| User resistance | Training, communication, pilot groups |
| Resource unavailability | Resource confirmation, backup resources |
| Vendor delays | SLA enforcement, backup vendors |

---

## 7. Implementation Planning

### 7.1 Schedule Planning

#### Maintenance Windows

| Window | Schedule | Eligible Changes |
|--------|----------|------------------|
| **Standard** | Saturday 22:00 - Sunday 06:00 | Cat 2 & 3 changes |
| **Extended** | Last Saturday of month 20:00 - Sunday 10:00 | Major releases, Cat 1 changes |
| **Emergency** | As required | Emergency changes only |

#### blackout Periods

No changes allowed (except emergency) during:

| Period | Duration | Reason |
|--------|----------|--------|
| Year-end closing | December 25 - January 5 | Financial reporting |
| Peak milk collection | March 15 - April 15 | Critical business period |
| System audit | As notified | Compliance requirements |
| Month-end | Last 2 days of month | Reporting accuracy |

### 7.2 Resource Planning

#### Required Resources Template

| Resource Type | Role | Hours Required | Availability Confirmed |
|---------------|------|----------------|------------------------|
| Technical | System Administrator | 8 | ☐ |
| Technical | Database Administrator | 4 | ☐ |
| Technical | Network Engineer | 2 | ☐ |
| Technical | Application Developer | 6 | ☐ |
| Support | Service Desk | On-call | ☐ |
| Business | User Representative | 2 | ☐ |
| Vendor | Technical Support | As needed | ☐ |

#### Resource Conflicts Resolution

1. Priority to Category 1 changes
2. First-come, first-served for same category
3. Negotiate alternative dates
4. Escalate to Operations Manager if unresolved

### 7.3 Rollback Planning

#### Rollback Criteria

Execute rollback if:
- [ ] Critical functionality not working after 30 minutes
- [ ] Data integrity issues detected
- [ ] Performance degradation > 50%
- [ ] Security vulnerability introduced
- [ ] Business user acceptance failed
- [ ] Unrecoverable errors during implementation

#### Rollback Plan Requirements

| Element | Description |
|---------|-------------|
| **Trigger Point** | Specific conditions that initiate rollback |
| **Steps** | Detailed step-by-step rollback procedure |
| **Time Required** | Estimated duration to complete rollback |
| **Verification** | How to confirm successful rollback |
| **Communication** | Who to notify and when |
| **Escalation** | Path if rollback fails |

#### Rollback Testing

All Category 1 and 2 changes must:
1. Document rollback procedure
2. Test rollback in non-production environment
3. Validate data integrity post-rollback
4. Confirm rollback time meets SLA

### 7.4 Implementation Checklist

#### Pre-Implementation

- [ ] RFC approved and authorization documented
- [ ] Implementation plan reviewed and approved
- [ ] All resources confirmed available
- [ ] Backups completed and verified
- [ ] Rollback plan tested and documented
- [ ] Communication sent to stakeholders
- [ ] Maintenance window confirmed
- [ ] Vendor support confirmed (if applicable)
- [ ] Test environment ready for validation

#### During Implementation

- [ ] Pre-implementation system state documented
- [ ] Each step executed per plan
- [ ] Progress logged in real-time
- [ ] Issues documented immediately
- [ ] Stakeholders updated on progress
- [ ] Go/No-go decision at checkpoint

#### Post-Implementation

- [ ] Functional testing completed
- [ ] Performance baseline verified
- [ ] Integration points tested
- [ ] Security validation passed
- [ ] User acceptance confirmed
- [ ] Documentation updated
- [ ] CMDB updated
- [ ] Communication of completion sent
- [ ] RFC closed with outcome documented

---

## 8. Communication Plan

### 8.1 Communication Matrix

| Stakeholder | RFC Submission | Approval | Implementation Start | Completion | Issues |
|-------------|----------------|----------|---------------------|------------|--------|
| **Requestor** | Acknowledge | Notify | Notify | Notify | Immediate |
| **CAB Members** | - | Invite | - | Summary | - |
| **Service Desk** | Log | - | Notify | Notify | Immediate |
| **Affected Users** | - | - | 48hr notice | Notify | As needed |
| **IT Management** | Weekly report | - | Cat 1 & 2 only | Summary | Escalation |
| **Business Management** | - | Cat 1 only | Cat 1 only | Summary | Escalation |

### 8.2 Communication Templates

#### Change Notification (48 hours before)

```
Subject: Scheduled Maintenance - [System Name] - [Date/Time]

Dear Users,

Please be informed that [System Name] will be unavailable for maintenance:

Date: [Date]
Time: [Start Time] to [End Time] (PKT)
Duration: [Duration]
Impact: [Description of impact]

Services Affected:
- [List affected services]

Alternative Arrangements:
- [If applicable]

We apologize for any inconvenience.

For queries, contact Service Desk at ext. 2000
```

#### Change Completion Notice

```
Subject: Maintenance Completed - [System Name]

Dear Users,

The scheduled maintenance for [System Name] has been completed.

Status: [Successful/Failed - Rolled Back]
Actual Duration: [Duration]
Changes Implemented:
- [List changes]

Please report any issues to Service Desk at ext. 2000
```

### 8.3 Emergency Communication

For emergency changes:

| Timing | Action | Responsible |
|--------|--------|-------------|
| Immediate | Notify Service Desk and IT Management | Change Manager |
| Within 30 min | User notification (if service impact) | Service Desk |
| During | Progress updates every 30 min | Implementation Lead |
| Within 1 hr post | Completion notification | Change Manager |

---

## 9. Emergency Changes

### 9.1 Emergency Change Process

```
[Emergency Identified]
        │
        ▼
┌───────────────────┐
│ Notify Change     │
│ Manager & ECAB    │
│ Chair             │
└─────────┬─────────┘
          │
          ▼
┌───────────────────┐
│ ECAB Assessment   │──► Rejected ──► Document & Close
│ (Within 1 hour)   │
└─────────┬─────────┘ Approved
          │
          ▼
┌───────────────────┐
│ Verbal/Written    │
│ Authorization     │
└─────────┬─────────┘
          │
          ▼
┌───────────────────┐
│ Implement Change  │
│ (Document as you  │
│  go)              │
└─────────┬─────────┘
          │
          ▼
┌───────────────────┐
│ Post-Implement    │
│ Review & Retro    │
│ RFC Documentation │
└─────────┬─────────┘
          │
          ▼
┌───────────────────┐
│ Close & Report    │
│ to Full CAB       │
└───────────────────┘
```

### 9.2 ECAB Composition

| Role | Member | Availability |
|------|--------|--------------|
| ECAB Chair | Change Manager | 24/7 on-call |
| Technical Lead | Senior System Architect | Business hours + on-call |
| Operations | IT Operations Manager | Business hours + on-call |
| Security | Information Security Officer | Business hours + on-call |

### 9.3 Emergency Change Requirements

**Minimum Documentation:**
1. Incident number or emergency justification
2. Description of change
3. Risk assessment (can be verbal, documented afterward)
4. Authorization record (email, SMS, or call log)
5. Implementation actions taken
6. Outcome and validation

**Post-Implementation Requirements:**
- Complete RFC within 24 hours
- Post-implementation review within 48 hours
- Report to next full CAB meeting

### 9.4 Emergency Change Limits

| Metric | Target | Maximum |
|--------|--------|---------|
| ECAB Response Time | 30 minutes | 1 hour |
| Authorization Decision | 1 hour | 2 hours |
| Emergency changes per month | < 5 | 10 |
| Emergency changes per system/year | < 3 | 5 |

---

## 10. Change Schedule

### 10.1 Forward Schedule of Changes (FSC)

The FSC is a rolling 90-day view of all planned changes.

#### FSC Contents

| Field | Description |
|-------|-------------|
| RFC Number | Unique identifier |
| Change Title | Brief description |
| Category | Cat 1, 2, or 3 |
| Scheduled Date | Planned implementation |
| Duration | Expected downtime |
| Systems Affected | List of systems |
| Status | Approved, Planned, Pending |
| Change Manager | Contact person |

#### FSC Distribution

- **Weekly:** Distributed every Friday
- **Recipients:** IT staff, business stakeholders, vendors
- **Format:** Email and SharePoint

### 10.2 Change Calendar

```
EXAMPLE: February 2026 Change Calendar

Week of Feb 2-6:
┌─────────────────────────────────────────────────────────┐
│ Sat 7th (22:00-06:00)                                    │
│ • RFC-2026-0042: Database patch (Cat 2)                 │
│ • RFC-2026-0043: Security update (Cat 3)                │
└─────────────────────────────────────────────────────────┘

Week of Feb 9-13:
┌─────────────────────────────────────────────────────────┐
│ Sat 14th (22:00-06:00)                                   │
│ • RFC-2026-0045: Portal module update (Cat 2)           │
└─────────────────────────────────────────────────────────┘

Week of Feb 23-27 (Extended Window):
┌─────────────────────────────────────────────────────────┐
│ Sat 28th (20:00-10:00) - MAJOR CHANGE WINDOW            │
│ • RFC-2026-0050: Portal version upgrade v2.1 (Cat 1)    │
│   NO OTHER CHANGES SCHEDULED                            │
└─────────────────────────────────────────────────────────┘
```

### 10.3 Change Freeze Periods

| Period | Dates | Exception Process |
|--------|-------|-------------------|
| Q1 Peak Season | Mar 15 - Apr 15 | CEO approval only |
| Year-End | Dec 25 - Jan 5 | CFO approval only |
| Audit Period | As notified | Audit Committee approval |

---

## 11. Metrics and Reporting

### 11.1 Key Performance Indicators (KPIs)

| KPI | Target | Measurement |
|-----|--------|-------------|
| **Change Success Rate** | > 95% | Changes completed without rollback |
| **Emergency Change Ratio** | < 10% | Emergency changes / Total changes |
| **CAB Decision Time** | < 5 days | Submission to decision for Cat 2&3 |
| **Schedule Adherence** | > 90% | Changes implemented as scheduled |
| **RFC Quality** | > 90% | RFCs accepted without revision |
| **Back-out Rate** | < 5% | Changes requiring rollback |
| **Customer Satisfaction** | > 4.0/5 | Post-change survey scores |

### 11.2 Change Statistics Report

**Monthly Report Contents:**

```
CHANGE MANAGEMENT MONTHLY REPORT
Month: [Month Year]

SUMMARY
- Total RFCs Submitted: XX
- Total RFCs Approved: XX
- Total RFCs Rejected: XX
- Total RFCs Implemented: XX
- Success Rate: XX%
- Emergency Changes: XX

BY CATEGORY
- Category 1 (Major): XX
- Category 2 (Significant): XX
- Category 3 (Minor): XX
- Standard: XX

BY STATUS
- Approved: XX
- In Progress: XX
- Completed: XX
- Backed Out: XX
- Closed: XX

TOP 5 SYSTEMS CHANGED
1. [System Name]: XX changes
...

INCIDENTS CAUSED BY CHANGES
- Total: XX
- Details: [List]

LESSONS LEARNED
- [Summary]

RECOMMENDATIONS
- [List]
```

### 11.3 CAB Meeting Metrics

| Metric | Target | Review Frequency |
|--------|--------|------------------|
| Meeting attendance | > 80% | Per meeting |
| Agenda items covered | 100% | Per meeting |
| RFCs reviewed per meeting | > 5 | Per meeting |
| Decision time per RFC | < 15 min | Per meeting |

### 11.4 Continuous Improvement

**Quarterly Review Activities:**
1. Analyze change success/failure trends
2. Review emergency change patterns
3. Assess process compliance
4. Identify training needs
5. Update standard change catalog
6. Review and update risk assessment matrix

---

## 12. Appendices

---

### Appendix A: Change Request Form (RFC) Template

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    REQUEST FOR CHANGE (RFC)                                 │
│                    Smart Dairy Ltd.                                         │
├─────────────────────────────────────────────────────────────────────────────┤
│ RFC Number: RFC-YYYY-NNNN          Date Submitted: [DD/MM/YYYY]            │
│ Requestor: [Name]                  Department: [Department]                │
│ Contact: [Email/Phone]                                                      │
├─────────────────────────────────────────────────────────────────────────────┤
│ CHANGE DETAILS                                                              │
├─────────────────────────────────────────────────────────────────────────────┤
│ Title: [Brief descriptive title]                                           │
│                                                                             │
│ Description: [Detailed description of the change]                          │
│                                                                             │
│ Business Justification: [Why is this change needed?]                       │
│                                                                             │
│ Proposed Implementation Date: [Date/Time]                                  │
│ Duration Required: [Hours/Minutes]                                         │
├─────────────────────────────────────────────────────────────────────────────┤
│ CATEGORIZATION (To be completed by Change Manager)                          │
├─────────────────────────────────────────────────────────────────────────────┤
│ Change Type: ☐ Standard  ☐ Normal  ☐ Emergency                             │
│                                                                             │
│ If Normal, Category: ☐ Cat 1 (Major)  ☐ Cat 2 (Significant)                │
│                      ☐ Cat 3 (Minor)                                        │
├─────────────────────────────────────────────────────────────────────────────┤
│ IMPACT ASSESSMENT                                                           │
├─────────────────────────────────────────────────────────────────────────────┤
│ Systems Affected: [List all systems/components]                            │
│                                                                             │
│ Users Affected: ☐ < 10  ☐ 10-100  ☐ > 100  ☐ All                           │
│                                                                             │
│ Business Services Affected: [List]                                         │
│                                                                             │
│ Estimated Downtime: [Duration]                                             │
│                                                                             │
│ Maintenance Window Required: ☐ Yes  ☐ No                                    │
│                                                                             │
│ Regulatory Impact: ☐ None  ☐ Low  ☐ Medium  ☐ High                         │
├─────────────────────────────────────────────────────────────────────────────┤
│ RESOURCES REQUIRED                                                          │
├─────────────────────────────────────────────────────────────────────────────┤
│ Internal Resources: [Roles and hours required]                             │
│                                                                             │
│ External Resources: [Vendors/consultants required]                         │
│                                                                             │
│ Estimated Cost: [Amount in PKR]                                            │
├─────────────────────────────────────────────────────────────────────────────┤
│ RISK ASSESSMENT                                                             │
├─────────────────────────────────────────────────────────────────────────────┤
│ Risk Level: ☐ Low  ☐ Medium  ☐ High  ☐ Critical                            │
│                                                                             │
│ Identified Risks:                                                          │
│ 1. [Risk description] - Mitigation: [Strategy]                             │
│ 2. [Risk description] - Mitigation: [Strategy]                             │
│                                                                             │
│ Testing Required: ☐ Unit  ☐ Integration  ☐ UAT  ☐ Performance              │
├─────────────────────────────────────────────────────────────────────────────┤
│ ROLLBACK PLAN                                                               │
├─────────────────────────────────────────────────────────────────────────────┤
│ Rollback Method: [Description]                                             │
│                                                                             │
│ Estimated Rollback Time: [Duration]                                        │
│                                                                             │
│ Rollback Tested: ☐ Yes  ☐ No  ☐ N/A                                        │
├─────────────────────────────────────────────────────────────────────────────┤
│ APPROVALS                                                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│ Submitted By: _________________ Date: _______                              │
│                                                                             │
│ Assessed By (Change Manager): _________________ Date: _______              │
│                                                                             │
│ CAB Decision: ☐ Approved  ☐ Approved with Conditions  ☐ Rejected           │
│               ☐ Deferred                                                    │
│                                                                             │
│ Approved By: _________________ Date: _______                               │
│                                                                             │
│ Conditions/Comments:                                                       │
│ _______________________________________________________________________    │
├─────────────────────────────────────────────────────────────────────────────┤
│ IMPLEMENTATION RECORD                                                       │
├─────────────────────────────────────────────────────────────────────────────┤
│ Actual Implementation Date: [Date/Time]                                    │
│ Implemented By: [Name(s)]                                                  │
│                                                                             │
│ Outcome: ☐ Successful  ☐ Partial Success  ☐ Failed - Rolled Back           │
│                                                                             │
│ Issues Encountered: [Description]                                          │
│                                                                             │
│ Post-Implementation Testing: ☐ Passed  ☐ Failed  ☐ Partial                 │
│                                                                             │
│ Verified By: _________________ Date: _______                               │
├─────────────────────────────────────────────────────────────────────────────┤
│ CLOSURE                                                                     │
├─────────────────────────────────────────────────────────────────────────────┤
│ Final Status: ☐ Closed - Successful  ☐ Closed - Failed                     │
│               ☐ Closed - Cancelled                                        │
│                                                                             │
│ Lessons Learned: [Key learnings from this change]                          │
│                                                                             │
│ Documentation Updated: ☐ Yes  ☐ No  ☐ N/A                                  │
│                                                                             │
│ Closed By: _________________ Date: _______                                 │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

### Appendix B: CAB Agenda Template

```
CHANGE ADVISORY BOARD MEETING AGENDA

Meeting Details:
Date: [Date]
Time: [Time]
Location: [Location/Video link]
Chair: [Change Manager Name]

ATTENDEES
│ Role                  │ Name              │ Status    │
├───────────────────────┼───────────────────┼───────────┤
│ CAB Chair             │                   │ Present   │
│ Technical Lead        │                   │           │
│ Operations Manager    │                   │           │
│ Security Officer      │                   │           │
│ Business Analyst      │                   │           │
│ QA Lead               │                   │           │
│ Service Desk Manager  │                   │           │

1. PREVIOUS MEETING FOLLOW-UP (5 minutes)
   • Action items from [previous date]
   • Outstanding decisions

2. BACKED-OUT CHANGES REVIEW (10 minutes)
   • RFC-XXXX: [Summary] - Lessons learned
   • RFC-XXXX: [Summary] - Lessons learned

3. NEW CHANGE REQUESTS (30 minutes)

   3.1 Category 1 Changes (Major)
   ┌─────────────────────────────────────────────────────┐
   │ RFC: RFC-2026-00XX                                  │
   │ Title: [Title]                                      │
   │ Requestor: [Name]                                   │
   │ Risk: [Low/Medium/High/Critical]                    │
   │ Impact: [Description]                               │
   │ Recommendation: [Approve/Reject/Defer]              │
   │ Discussion:                                         │
   │ Decision: ☐ Approve ☐ Reject ☐ Defer ☐ Escalate   │
   │ Conditions:                                         │
   └─────────────────────────────────────────────────────┘

   3.2 Category 2 Changes (Significant)
   [List each RFC with summary and decision]

   3.3 Category 3 Changes (Minor)
   [List for information - pre-approved by Change Manager]

4. FORWARD SCHEDULE REVIEW (5 minutes)
   • Conflicts identified
   • Resource availability

5. ANY OTHER BUSINESS (5 minutes)

6. NEXT MEETING
   Date: [Next meeting date]

ACTION ITEMS
│ Item │ Owner │ Due Date │ Status │
├──────┼───────┼──────────┼────────┤
│      │       │          │        │
```

---

### Appendix C: Risk Assessment Worksheet

```
CHANGE RISK ASSESSMENT WORKSHEET
RFC: [Number]                    Date: [Date]
Change Title: [Title]

LIKELIHOOD EVALUATION

Factor 1: Complexity
☐ Low    - Well understood, simple change
☐ Medium - Moderate complexity, some unknowns
☐ High   - Complex, many components
☐ Critical - Highly complex, cutting edge

Factor 2: Testing
☐ Low    - Comprehensive testing completed
☐ Medium - Limited testing, some assumptions
☐ High   - Minimal testing, many assumptions
☐ Critical - Untested or cannot test

Factor 3: Past Experience
☐ Low    - Similar changes successful
☐ Medium - Some similar changes, mixed results
☐ High   - Limited experience with this type
☐ Critical - New type of change, no experience

Factor 4: Environment Stability
☐ Low    - Stable environment, good monitoring
☐ Medium - Generally stable, some issues
☐ High   - Unstable environment, known issues
☐ Critical - Very unstable, frequent problems

Overall Likelihood: ☐ Low ☐ Med-High ☐ Medium ☐ High

IMPACT EVALUATION

Factor 1: Business Impact
☐ Low    - Single user, minimal business impact
☐ Medium - Department affected, some disruption
☐ High   - Multiple departments, significant impact
☐ Critical - Organization-wide, critical processes

Factor 2: Technical Impact
☐ Low    - Isolated component, easily recovered
☐ Medium - Subsystem affected, recovery possible
☐ High   - Major system affected, complex recovery
☐ Critical - Core infrastructure, catastrophic failure

Factor 3: Financial Impact
☐ Low    - < 500,000 PKR
☐ Medium - 500,000 - 2,000,000 PKR
☐ High   - 2,000,000 - 10,000,000 PKR
☐ Critical - > 10,000,000 PKR

Factor 4: Regulatory/Compliance Impact
☐ Low    - No regulatory implications
☐ Medium - Possible review or notification
☐ High   - Likely investigation or fine
☐ Critical - License revocation, legal action

Overall Impact: ☐ Low ☐ Medium ☐ High ☐ Critical

RISK SCORE CALCULATION
Likelihood: [Level] × Impact: [Level] = Score: [1-16]
Risk Level: ☐ Low (1-5) ☐ Medium (6-9) ☐ High (10-14) ☐ Critical (15-16)

RECOMMENDATION
☐ Proceed as planned
☐ Proceed with additional controls
☐ Do not proceed without mitigation
☐ Reject change

Additional Controls Required:
_________________________________________________________________

Assessed By: _________________ Date: _______
```

---

### Appendix D: Post-Implementation Review Template

```
POST-IMPLEMENTATION REVIEW

RFC: [Number]
Change Title: [Title]
Implementation Date: [Date]
Review Date: [Date]

1. IMPLEMENTATION SUMMARY
   Planned Duration: [Time]
   Actual Duration: [Time]
   Variance: [Time]
   
   Outcome: ☐ Successful ☐ Partial ☐ Failed ☐ Rolled Back

2. SCHEDULE ADHERENCE
   ☐ Implemented as scheduled
   ☐ Delayed (reason: _____________________)
   ☐ Implemented early (reason: ____________)

3. RESOURCE UTILIZATION
   Role               │ Planned │ Actual │ Variance
   ───────────────────┼─────────┼────────┼──────────
   System Admin       │         │        │
   DBA                │         │        │
   Network Engineer   │         │        │
   Developer          │         │        │
   
   Comments: _______________________________________

4. TECHNICAL OUTCOME
   4.1 Functionality
   ☐ All requirements met
   ☐ Partially met (issues: _____________________)
   ☐ Not met (reason: _________________________)
   
   4.2 Performance
   ☐ Met targets
   ☐ Below target but acceptable
   ☐ Unacceptable (action: ____________________)
   
   4.3 Issues Encountered
   Issue │ Severity │ Resolution │ Time to Resolve
   ──────┼──────────┼────────────┼────────────────
   

5. ROLLBACK PLAN EFFECTIVENESS
   ☐ Not tested (successful implementation)
   ☐ Tested and worked as designed
   ☐ Partially effective (issues: ____________)
   ☐ Failed (issues: ________________________)

6. COMMUNICATION EFFECTIVENESS
   ☐ Timely and adequate
   ☐ Adequate but could be improved
   ☐ Insufficient (issues: __________________)

7. USER SATISFACTION
   Feedback received: ☐ Yes ☐ No
   Satisfaction level: ☐ High ☐ Medium ☐ Low
   Comments: __________________________________

8. LESSONS LEARNED
   What went well:
   _____________________________________________
   
   What could be improved:
   _____________________________________________
   
   Recommendations for future similar changes:
   _____________________________________________

9. FOLLOW-UP ACTIONS
   Action Item │ Owner │ Due Date │ Status
   ────────────┼───────┼──────────┼────────

10. APPROVAL
   Reviewed By: _________________ Date: _______
   
   ☐ Close RFC - Successful
   ☐ Close RFC - Failed (document in lessons learned)
   ☐ Keep RFC open - Follow-up actions required
```

---

### Appendix E: Standard Change Catalog

| ID | Change Type | Procedure | Frequency | Eligible Roles |
|----|-------------|-----------|-----------|----------------|
| SC001 | User Account Creation | IAM-PROC-001 | As needed | Service Desk |
| SC002 | User Password Reset | IAM-PROC-002 | As needed | Service Desk |
| SC003 | Routine Backup Execution | BKP-PROC-001 | Daily | Backup Operator |
| SC004 | Antivirus Update | SEC-PROC-003 | Weekly | Security Admin |
| SC005 | Monitoring Alert Configuration | MON-PROC-002 | As needed | Monitoring Team |
| SC006 | Certificate Renewal (automated) | SEC-PROC-007 | As scheduled | Security Admin |
| SC007 | Patch Installation (tested) | PATCH-PROC-001 | Monthly | System Admin |
| SC008 | Log Rotation | OPS-PROC-005 | Weekly | System Admin |
| SC009 | Printer Configuration | DESK-PROC-003 | As needed | Desktop Support |
| SC010 | Email Distribution List Update | COL-PROC-002 | As needed | Service Desk |

---

### Appendix F: Change Management RACI Matrix

| Activity | Requestor | Change Manager | CAB | Implementer | Service Desk |
|----------|:---------:|:--------------:|:---:|:-----------:|:------------:|
| Submit RFC | R | A | - | - | - |
| Assess RFC | C | R/A | C | I | I |
| Schedule CAB | I | R/A | C | I | I |
| Present to CAB | C | R/A | C | I | I |
| Approve Change | I | R | A | I | I |
| Plan Implementation | C | A | I | R | C |
| Communicate Change | I | R/A | I | C | C |
| Implement Change | I | C | - | R/A | C |
| Verify Implementation | C | R | - | C | A |
| Conduct PIR | C | R/A | I | C | I |
| Close RFC | I | R/A | I | C | I |

*R = Responsible, A = Accountable, C = Consulted, I = Informed*

---

### Appendix G: Glossary

| Term | Definition |
|------|------------|
| **Back-out** | Process of reversing a change to restore service |
| **CAB** | Change Advisory Board |
| **Change** | Addition, modification, or removal of IT service component |
| **CMDB** | Configuration Management Database |
| **ECAB** | Emergency Change Advisory Board |
| **FSC** | Forward Schedule of Changes |
| **Normal Change** | Change requiring CAB assessment and authorization |
| **PIR** | Post-Implementation Review |
| **RFC** | Request for Change |
| **Rollback** | Process to return to previous state |
| **Standard Change** | Pre-approved, low-risk change |
| **Emergency Change** | Urgent change requiring expedited process |

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Change Manager | _________________ | _______ |
| Reviewer | IT Director | _________________ | _______ |
| Approver | IT Director | _________________ | _______ |

---

**Document Control:**
- This document is controlled. Unauthorized modification is prohibited.
- Master copy maintained by Change Manager.
- Review Date: January 31, 2027

---

*End of Document L-005: Change Management Process*
