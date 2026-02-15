# SMART DAIRY LTD.
## PROJECT ISSUE LOG TEMPLATE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | J-019 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Project Manager |
| **Owner** | Project Manager |
| **Reviewer** | Tech Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Issue Log Fields](#2-issue-log-fields)
3. [Issue Categories](#3-issue-categories)
4. [Priority vs Severity](#4-priority-vs-severity)
5. [Issue Lifecycle](#5-issue-lifecycle)
6. [Escalation Triggers](#6-escalation-triggers)
7. [Review Process](#7-review-process)
8. [Reporting](#8-reporting)
9. [Sample Issues](#9-sample-issues)
10. [Tool Setup](#10-tool-setup)
11. [Appendices](#11-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

The Issue Log tracks all problems, concerns, and blockers that arise during the project. Unlike risks (potential problems), issues are current problems requiring immediate attention.

### 1.2 Issue vs Risk

| Aspect | Risk | Issue |
|--------|------|-------|
| **Timing** | Future potential | Current reality |
| **Status** | May or may not happen | Has already occurred |
| **Response** | Mitigation planning | Immediate resolution |
| **Impact** | Potential | Actual |

### 1.3 Issue Log Maintenance

| Activity | Frequency | Owner |
|----------|-----------|-------|
| Daily review | Daily | Project Manager |
| Issue triage | Daily | Team Lead |
| Weekly review | Weekly | Project Manager |
| Trend analysis | Monthly | Project Manager |

---

## 2. ISSUE LOG FIELDS

### 2.1 Field Definitions

| Field | Description | Example |
|-------|-------------|---------|
| **Issue ID** | Unique identifier (I-XXX format) | I-001 |
| **Issue Description** | Clear description of the problem | "bKash sandbox API returning 500 errors" |
| **Category** | Type of issue | Technical, Resource, External |
| **Priority** | Urgency (P0, P1, P2, P3) | P1 |
| **Severity** | Impact level (Critical, High, Medium, Low) | High |
| **Raised By** | Person who identified issue | "QA Engineer" |
| **Assigned To** | Person responsible for resolution | "Backend Developer" |
| **Date Raised** | When issue was logged | 2026-02-15 |
| **Target Resolution** | Target date for resolution | 2026-02-17 |
| **Status** | Current state | Open, In Progress, Resolved |
| **Resolution** | How issue was resolved | "Updated API endpoint" |
| **Date Closed** | When issue was closed | 2026-02-16 |

### 2.2 Priority Levels

| Priority | Response Time | Resolution Target | Examples |
|----------|---------------|-------------------|----------|
| **P0 - Critical** | 15 minutes | 4 hours | Production down, data loss, security breach |
| **P1 - High** | 1 hour | 8 hours | Major feature broken, significant blocker |
| **P2 - Medium** | 4 hours | 24 hours | Feature impaired, workaround available |
| **P3 - Low** | 24 hours | 72 hours | Minor issue, cosmetic problem |

### 2.3 Severity Levels

| Severity | Business Impact | Technical Impact | Examples |
|----------|----------------|------------------|----------|
| **Critical** | Operations halted | System unusable | Production outage, data corruption |
| **High** | Major disruption | Core feature broken | Payment failure, login broken |
| **Medium** | Moderate impact | Feature impaired | Performance degradation, UI glitch |
| **Low** | Minimal impact | Cosmetic issue | Typos, minor layout issues |

### 2.4 Status Values

| Status | Definition | Next Status |
|--------|------------|-------------|
| **Open** | Issue identified, not yet assigned | Assigned |
| **Assigned** | Issue assigned to owner | In Progress |
| **In Progress** | Being actively worked on | Pending/Resolved |
| **Pending** | Waiting for information/dependency | In Progress |
| **Resolved** | Fix implemented, pending verification | Verified/Closed |
| **Verified** | Fix confirmed by QA | Closed |
| **Closed** | Issue fully resolved | - |
| **Cancelled** | Issue no longer valid | - |

---

## 3. ISSUE CATEGORIES

### 3.1 Technical Issues

| Code | Sub-category | Examples |
|------|--------------|----------|
| T-API | API/Integration | Third-party API failures |
| T-PERF | Performance | Slow response times |
| T-SEC | Security | Vulnerabilities, access issues |
| T-DATA | Data | Data corruption, migration issues |
| T-INFRA | Infrastructure | Server downtime, network issues |
| T-CODE | Code | Build failures, deployment issues |

### 3.2 Resource Issues

| Code | Sub-category | Examples |
|------|--------------|----------|
| R-PERS | Personnel | Team member unavailable |
| R-SKILL | Skills | Missing expertise |
| R-BUDG | Budget | Cost overruns |
| R-TOOL | Tools | Software license issues |

### 3.3 Schedule Issues

| Code | Sub-category | Examples |
|------|--------------|----------|
| S-DELAY | Delays | Task completion delays |
| S-DEP | Dependencies | Blocked by other tasks |
| S-SCOPE | Scope | Scope creep, unclear requirements |

### 3.4 External Issues

| Code | Sub-category | Examples |
|------|--------------|----------|
| E-VEND | Vendor | Vendor delivery delays |
| E-REG | Regulatory | Compliance requirement changes |
| E-ENV | Environment | Power outages, natural disasters |

---

## 4. PRIORITY VS SEVERITY

### 4.1 Priority/Severity Matrix

| Severity ↓ / Priority → | P0 - Critical | P1 - High | P2 - Medium | P3 - Low |
|-------------------------|---------------|-----------|-------------|----------|
| **Critical** | 🔴 Emergency fix immediately | 🟠 Fix today | 🟡 Fix this sprint | 🟢 Next sprint |
| **High** | 🟠 Escalate immediately | 🟡 Fix within 24h | 🟢 Fix this sprint | 🟢 Backlog |
| **Medium** | 🟡 Escalate to PM | 🟢 Fix this sprint | 🟢 Backlog | 🟢 Backlog |
| **Low** | 🟢 Backlog | 🟢 Backlog | 🟢 Backlog | 🟢 Won't fix |

### 4.2 Assignment Guidelines

| Priority | Assigned To | Escalation |
|----------|-------------|------------|
| P0 | Senior Developer + Tech Lead | Immediate to CTO |
| P1 | Developer + Team Lead | Daily to PM |
| P2 | Developer | Weekly review |
| P3 | Developer | Next sprint planning |

---

## 5. ISSUE LIFECYCLE

### 5.1 Issue Lifecycle Flow

```
┌──────────┐     ┌──────────┐     ┌──────────────┐
│ IDENTIFY │────▶│  LOG     │────▶│   TRIAGE     │
│          │     │          │     │ (Set P/S)    │
└──────────┘     └──────────┘     └──────┬───────┘
                                         │
                                         ▼
┌──────────┐     ┌──────────┐     ┌──────────────┐
│  CLOSE   │◀────│ VERIFY   │◀────│   RESOLVE    │
│          │     │          │     │              │
└──────────┘     └──────────┘     └──────┬───────┘
                                         │
                                         ▼
                                  ┌──────────────┐
                                  │   ASSIGN     │
                                  │              │
                                  └──────────────┘
```

### 5.2 Lifecycle Stages

**1. Identify**:
- Anyone can identify an issue
- Log immediately in issue tracking system

**2. Log**:
- Create issue ticket
- Fill all required fields
- Assign initial priority

**3. Triage**:
- Project Manager/Team Lead reviews
- Confirm priority and severity
- Assign to appropriate owner
- Set target resolution date

**4. Assign**:
- Owner accepts assignment
- Updates status to "In Progress"
- Begins investigation

**5. Resolve**:
- Implement fix
- Update status to "Resolved"
- Document resolution

**6. Verify**:
- QA verifies fix
- Updates status to "Verified"

**7. Close**:
- PM reviews and closes
- Document lessons learned

---

## 6. ESCALATION TRIGGERS

### 6.1 Automatic Escalation

| Trigger | Action |
|---------|--------|
| P0 issue not acknowledged in 15 min | Escalate to Tech Lead |
| P1 issue not resolved in 8 hours | Escalate to Project Manager |
| Issue re-opened 3+ times | Escalate to Tech Lead |
| Issue blocked for > 24 hours | Escalate to Project Manager |
| Multiple related issues | Create incident report |

### 6.2 Escalation Path

```
Level 1: Team Member
    ↓
Level 2: Team Lead / Scrum Master
    ↓
Level 3: Project Manager
    ↓
Level 4: IT Director / CTO
    ↓
Level 5: Steering Committee
```

---

## 7. REVIEW PROCESS

### 7.1 Daily Review

**When**: Daily Standup
**Duration**: 5-10 minutes

**Agenda**:
1. New issues since yesterday
2. Blocked issues needing help
3. Issues approaching target date
4. Issues to escalate

### 7.2 Weekly Review

**When**: Friday afternoon
**Duration**: 30 minutes
**Participants**: Project Manager, Team Leads

**Agenda**:
1. Review all open issues
2. Aging analysis (> 7 days)
3. Trend identification
4. Resource reallocation if needed

### 7.3 Monthly Analysis

**When**: End of month
**Duration**: 1 hour

**Analysis**:
- Issue volume trends
- Category distribution
- Resolution time trends
- Root cause analysis
- Process improvement opportunities

---

## 8. REPORTING

### 8.1 Issue Metrics

| Metric | Target | Frequency |
|--------|--------|-----------|
| Open Issues | < 20 | Daily |
| P0 Issues | 0 | Daily |
| P1 Issues | < 3 | Daily |
| Average Resolution Time | < 48 hours | Weekly |
| Issue Reopen Rate | < 10% | Weekly |

### 8.2 Issue Aging Report

| Age | Count | Action Required |
|-----|-------|-----------------|
| < 24 hours | [X] | Normal |
| 24-48 hours | [X] | Monitor |
| 48-72 hours | [X] | Escalate |
| > 72 hours | [X] | Critical review |

---

## 9. SAMPLE ISSUES

### 9.1 Sample Issue Log

| ID | Description | Category | Priority | Severity | Assigned | Status | Raised | Target |
|----|-------------|----------|----------|----------|----------|--------|--------|--------|
| I-001 | bKash sandbox API returning 500 errors | T-API | P1 | High | Backend Dev | In Progress | 2026-02-15 | 2026-02-17 |
| I-002 | Farm manager unavailable for UAT | R-PERS | P2 | Medium | PM | Open | 2026-02-14 | 2026-02-21 |
| I-003 | Third-party SSL certificate expires in 5 days | T-SEC | P1 | High | DevOps | In Progress | 2026-02-16 | 2026-02-18 |
| I-004 | Performance < 2s response time under 100 users | T-PERF | P2 | Medium | Backend Dev | Assigned | 2026-02-13 | 2026-02-20 |
| I-005 | Odoo partner consultant sick leave | E-VEND | P2 | Medium | PM | Open | 2026-02-15 | 2026-02-28 |

### 9.2 Detailed Issue Example

#### I-001: bKash Sandbox API Errors

**Issue ID**: I-001
**Description**: bKash sandbox API returning 500 errors during payment testing
**Category**: T-API (Technical - API/Integration)
**Priority**: P1 (High)
**Severity**: High
**Raised By**: QA Engineer
**Assigned To**: Backend Developer
**Date Raised**: 2026-02-15 10:30 AM
**Target Resolution**: 2026-02-17

**Impact**:
- Payment integration testing blocked
- Sprint deliverable at risk
- QA cannot complete test cases

**Steps Taken**:
1. Verified API credentials are correct
2. Checked bKash developer portal status
3. Contacted bKash technical support

**Next Steps**:
- Awaiting response from bKash support
- Implementing retry logic as workaround
- Exploring alternative test environment

**Status**: In Progress

---

## 10. TOOL SETUP

### 10.1 JIRA Configuration

**Project**: SMARTDAIRY
**Issue Types**: Bug, Task, Story
**Priorities**: P0, P1, P2, P3
**Status Workflow**: Open → In Progress → Resolved → Closed
**Components**: Backend, Frontend, Mobile, IoT, Database, Infrastructure

### 10.2 Custom Fields

| Field | Type | Purpose |
|-------|------|---------|
| Priority (P0-P3) | Dropdown | Response priority |
| Severity | Dropdown | Impact level |
| Category | Dropdown | Issue classification |
| Raised By | User | Issue reporter |
| Target Date | Date | SLA target |

### 10.3 Automation Rules

1. **P0 Auto-Alert**: When P0 created → Notify Tech Lead + PM
2. **Aging Alert**: When issue open > 48h → Escalate to PM
3. **Daily Digest**: Daily at 9 AM → Email open issues summary
4. **SLA Breach**: When target date passed → Change priority to P0

---

## 11. APPENDICES

### Appendix A: Issue Log Template

```markdown
# PROJECT ISSUE LOG
**Project**: Smart Dairy Web Portal System
**Date**: [Date]
**Owner**: [Project Manager]

| ID | Description | Category | P | S | Assigned | Status | Raised | Target | Closed |
|----|-------------|----------|---|---|----------|--------|--------|--------|--------|
| I-001 | [Description] | [Code] | P# | [S] | [Name] | [Status] | [Date] | [Date] | [Date] |
| I-002 | [Description] | [Code] | P# | [S] | [Name] | [Status] | [Date] | [Date] | [Date] |
| I-003 | [Description] | [Code] | P# | [S] | [Name] | [Status] | [Date] | [Date] | [Date] |
```

### Appendix B: Issue Registration Form

**Issue ID**: ___________

**Date Raised**: ___________

**Raised By**: ___________

**Issue Description**:
_____________________________________________
_____________________________________________

**Category**:
- [ ] Technical (T-API, T-PERF, T-SEC, T-DATA, T-INFRA, T-CODE)
- [ ] Resource (R-PERS, R-SKILL, R-BUDG, R-TOOL)
- [ ] Schedule (S-DELAY, S-DEP, S-SCOPE)
- [ ] External (E-VEND, E-REG, E-ENV)

**Category Code**: ___________

**Priority**:
- [ ] P0 - Critical (Response: 15 min, Resolution: 4 hours)
- [ ] P1 - High (Response: 1 hour, Resolution: 8 hours)
- [ ] P2 - Medium (Response: 4 hours, Resolution: 24 hours)
- [ ] P3 - Low (Response: 24 hours, Resolution: 72 hours)

**Severity**:
- [ ] Critical - Operations halted
- [ ] High - Major disruption
- [ ] Medium - Moderate impact
- [ ] Low - Minimal impact

**Impact Description**:
_____________________________________________

**Assigned To**: ___________

**Target Resolution Date**: ___________

**Initial Assessment**:
_____________________________________________

### Appendix C: Issue Aging Report

```markdown
# WEEKLY ISSUE AGING REPORT
**Week**: [Week Number]
**Date**: [Date]

## Summary
- Total Open Issues: [X]
- New This Week: [X]
- Closed This Week: [X]
- Average Resolution Time: [X] hours

## Aging Breakdown
| Age Range | Count | Percentage |
|-----------|-------|------------|
| < 24 hours | [X] | [X]% |
| 24-48 hours | [X] | [X]% |
| 48-72 hours | [X] | [X]% |
| 72 hours - 1 week | [X] | [X]% |
| > 1 week | [X] | [X]% |

## Issues Requiring Attention (> 48 hours)
| ID | Description | Age | Assigned | Action |
|----|-------------|-----|----------|--------|
| I-XXX | [Description] | [X] days | [Name] | [Action] |

## Category Distribution
- Technical: [X] ([X]%)
- Resource: [X] ([X]%)
- Schedule: [X] ([X]%)
- External: [X] ([X]%)

## Trend Analysis
[Description of trends, patterns, recommendations]
```

### Appendix D: Weekly Issue Summary

```markdown
# WEEKLY ISSUE SUMMARY
**Period**: [Date] to [Date]

## New Issues
| ID | Description | Priority | Assigned | Status |
|----|-------------|----------|----------|--------|
| I-XXX | [Description] | P# | [Name] | [Status] |

## Resolved Issues
| ID | Description | Resolution Time | Resolution |
|----|-------------|-----------------|------------|
| I-XXX | [Description] | [X] hours | [How resolved] |

## Open Issues
| ID | Description | Priority | Days Open | Blocker |
|----|-------------|----------|-----------|---------|
| I-XXX | [Description] | P# | [X] | [Yes/No] |

## Escalations Required
| ID | Description | Reason | Escalate To |
|----|-------------|--------|-------------|
| I-XXX | [Description] | [Reason] | [Role] |
```

---

**END OF PROJECT ISSUE LOG TEMPLATE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Jan 31, 2026 | Project Manager | Initial version |
