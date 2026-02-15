# J-002: Sprint Planning Template & Guidelines

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | J-002 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Scrum Master |
| **Owner** | Project Manager |
| **Reviewer** | CTO |
| **Status** | Approved |
| **Classification** | Internal Use |

## Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 31, 2026 | Scrum Master | Initial release |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Sprint Cadence](#2-sprint-cadence)
3. [Pre-Planning Activities](#3-pre-planning-activities)
4. [Sprint Planning Meeting](#4-sprint-planning-meeting)
5. [Estimation Techniques](#5-estimation-techniques)
6. [Capacity Planning](#6-capacity-planning)
7. [Sprint Goal Definition](#7-sprint-goal-definition)
8. [Definition of Ready Checklist](#8-definition-of-ready-checklist)
9. [Task Breakdown](#9-task-breakdown)
10. [Sprint Backlog Creation](#10-sprint-backlog-creation)
11. [Commitment vs Forecast](#11-commitment-vs-forecast)
12. [Sprint Planning Template](#12-sprint-planning-template)
13. [Common Pitfalls](#13-common-pitfalls)
14. [Appendices](#14-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document establishes the Sprint Planning framework and guidelines for Smart Dairy Ltd.'s Smart Web Portal System development. It provides standardized processes, templates, and best practices to ensure consistent and effective sprint planning across all development teams.

### 1.2 Scope

This document applies to:
- All Scrum Teams participating in Smart Dairy Web Portal development
- Scrum Masters facilitating sprint planning
- Product Owners prioritizing backlog items
- Development Team members estimating and committing to work
- Project Managers tracking progress and capacity

### 1.3 Objectives of Sprint Planning

The Sprint Planning ceremony serves to answer two fundamental questions:

1. **What can be delivered** in the upcoming Sprint that increases value for Smart Dairy stakeholders?
2. **How will the work** be accomplished to achieve the Sprint Goal?

### 1.4 Key Outcomes

- Clearly defined Sprint Goal aligned with Smart Dairy business objectives
- Committed Sprint Backlog with properly estimated user stories
- Team understanding of scope, dependencies, and risks
- Updated capacity calculations based on availability
- Task breakdown with initial hour estimates

---

## 2. Sprint Cadence

### 2.1 Sprint Duration

**Standard Duration:** 2 weeks (10 working days)

**Rationale:**
- Balances planning overhead with delivery frequency
- Allows adequate time for complex Smart Dairy features
- Enables quick feedback loops for IoT integration components
- Supports predictable release cadence

### 2.2 Sprint Schedule Overview

| Day | Activity | Time | Duration | Participants |
|-----|----------|------|----------|--------------|
| Monday | **Sprint Planning** | 9:00 AM - 1:00 PM | 4 hours | Full Scrum Team |
| Daily | **Daily Standup** | 9:30 AM | 15 minutes | Full Scrum Team |
| Wednesday | **Mid-Sprint Review** | 2:00 PM | 30 minutes | Scrum Master, Team Leads |
| Friday (Week 2) | **Sprint Review** | 10:00 AM | 1 hour | Full Team + Stakeholders |
| Friday (Week 2) | **Sprint Retrospective** | 2:00 PM | 1.5 hours | Full Scrum Team |

### 2.3 Key Dates Calendar

```
WEEK 1
├── Monday    : Sprint Planning (Day 1)
├── Tuesday   : Development Start
├── Wednesday : Development / Mid-sprint Check (Week 2)
├── Thursday  : Development
├── Friday    : Development

WEEK 2
├── Monday    : Development
├── Tuesday   : Development / Testing Start
├── Wednesday : Testing / Bug Fixes
├── Thursday  : Final Testing / Documentation
└── Friday    : Sprint Review → Retrospective → Release
```

### 2.4 Smart Dairy Sprint Naming Convention

**Format:** `SD-[YYYY]-[Quarter]-S[Number]`

**Examples:**
- SD-2026-Q1-S1 (Sprint 1 of Q1 2026)
- SD-2026-Q1-S2 (Sprint 2 of Q1 2026)
- SD-2026-Q2-S1 (Sprint 1 of Q2 2026)

---

## 3. Pre-Planning Activities

### 3.1 Backlog Refinement (Grooming)

**Schedule:** Weekly, 2 hours (Wednesday 2:00 PM - 4:00 PM)

**Participants:**
- Product Owner (required)
- Development Team (required)
- Scrum Master (facilitator)
- Subject Matter Experts (as needed)

**Activities:**
1. Review and prioritize Product Backlog items
2. Ensure stories meet Definition of Ready
3. Break down large epics into sprint-sized stories
4. Initial estimation of new stories
5. Identify dependencies and technical risks
6. Update acceptance criteria based on feedback

**Outputs:**
- Refined Product Backlog (top items ready for sprint)
- Updated story estimates
- Identified dependencies documented

### 3.2 Definition of Ready (DoR) Check

Before sprint planning, ensure all candidate stories meet DoR criteria:

| Criterion | Description | Verification |
|-----------|-------------|--------------|
| Clear Title | Story has descriptive, action-oriented title | Review |
| User Value | Business value clearly articulated | INVEST check |
| Acceptance Criteria | Specific, testable conditions defined | PO approval |
| Dependencies | External dependencies identified and tracked | Team review |
| Size Appropriate | Story fits within single sprint | Estimation < 13 pts |
| Technical Notes | Initial technical approach documented | Tech lead review |

### 3.3 Pre-Planning Checklist

- [ ] Product Backlog is prioritized and visible
- [ ] Top 2-3 sprints worth of stories are refined
- [ ] Architecture/technical decisions documented
- [ ] Previous sprint velocity data available
- [ ] Team availability confirmed for next sprint
- [ ] Stakeholder input incorporated
- [ ] External dependencies communicated

---

## 4. Sprint Planning Meeting

### 4.1 Meeting Structure

**Total Duration:** 4 hours for 2-week sprint

**Timebox Formula:**
- 2 hours per week of sprint duration
- Adjust based on team maturity and complexity

### 4.2 Part 1: What to Build (2 Hours)

**Objective:** Define Sprint Goal and select Product Backlog items

**Facilitator:** Product Owner

**Agenda:**

| Time | Activity | Description |
|------|----------|-------------|
| 0:00-0:10 | Opening | Scrum Master reviews agenda and timeboxes |
| 0:10-0:30 | Business Context | PO presents market/business updates |
| 0:30-0:50 | Sprint Goal Proposal | PO proposes Sprint Goal for team consideration |
| 0:50-1:30 | Backlog Item Review | PO presents top priority items |
| 1:30-1:50 | Q&A and Clarification | Team asks questions, PO clarifies |
| 1:50-2:00 | Part 1 Wrap-up | Confirm selected items align with Sprint Goal |

**Key Questions to Address:**
- What is the primary objective for this sprint?
- Which Product Backlog items support this objective?
- Are there any critical business deadlines or dependencies?
- What risks or assumptions should we consider?

**Outputs:**
- Draft Sprint Goal
- Candidate Product Backlog items for sprint
- Open questions requiring clarification

### 4.3 Part 2: How to Build (2 Hours)

**Objective:** Create task breakdown and commit to Sprint Backlog

**Facilitator:** Development Team (self-organizing)

**Agenda:**

| Time | Activity | Description |
|------|----------|-------------|
| 2:00-2:15 | Transition | Break, then reconvene for technical planning |
| 2:15-3:00 | Technical Discussion | Architecture, design, and approach discussion |
| 3:00-3:45 | Task Breakdown | Decompose stories into tasks |
| 3:45-4:15 | Estimation | Estimate tasks and validate capacity |
| 4:15-4:45 | Commitment | Team commits to Sprint Backlog |
| 4:45-5:00 | Closure | Document decisions, set expectations |

**Key Questions to Address:**
- How will we implement each selected story?
- What technical tasks are required?
- Do we have the necessary skills and resources?
- Are there any blockers or impediments?

**Outputs:**
- Final Sprint Goal
- Committed Sprint Backlog with task breakdown
- Initial capacity allocation
- Identified risks and mitigation strategies

### 4.4 Meeting Guidelines

**Do's:**
- Start and end on time
- Focus on "what" and "how", not "who"
- Encourage questions and challenge assumptions
- Document decisions visibly
- Ensure everyone has a voice

**Don'ts:**
- Assign tasks to individuals
- Skip the Sprint Goal discussion
- Rush through estimation
- Ignore capacity constraints
- Allow scope creep during planning

---

## 5. Estimation Techniques

### 5.1 Story Points (Fibonacci Scale)

**Scale:** 1, 2, 3, 5, 8, 13, 21, 34, 55, 89

**Reference Stories for Smart Dairy:**

| Points | Reference Story | Description |
|--------|-----------------|-------------|
| 1 | UI Text Update | Simple label or button text change |
| 2 | API Endpoint Addition | New GET endpoint with simple query |
| 3 | Form Validation | Standard form with validation rules |
| 5 | Dashboard Widget | New widget with data visualization |
| 8 | User Authentication Flow | Complete login/logout with session |
| 13 | Payment Integration | Stripe integration with webhooks |
| 21 | IoT Data Pipeline | End-to-end sensor data ingestion |

**Estimation Factors:**
1. **Complexity:** Technical difficulty of the solution
2. **Effort:** Amount of work required
3. **Uncertainty:** Known unknowns and risks
4. **Dependencies:** External integrations or team dependencies

### 5.2 Planning Poker

**Process:**
1. Product Owner reads the story
2. Team discusses and asks questions
3. Each team member privately selects a card
4. Cards are revealed simultaneously
5. Discuss differences (highest and lowest explain)
6. Re-estimate if needed (max 3 rounds)

**Tools:**
- Physical planning poker cards
- JIRA Planning Poker plugin
- Azure DevOps estimation tool
- Online tools (PlanningPoker.com, Parabol)

**Rules:**
- No averaging - consensus required
- Use "?" card when completely uncertain
- Use coffee break card when fatigued
- Timebox discussion: 2 minutes per round

### 5.3 T-Shirt Sizes

**Used for:**
- High-level epic estimation
- Initial backlog sizing
- Release planning

**Scale:**
| Size | Story Points Range | Typical Duration | Use Case |
|------|-------------------|------------------|----------|
| XS | 1-2 points | < 1 day | Simple bug fix |
| S | 3 points | 1-2 days | Small feature |
| M | 5 points | 2-3 days | Medium feature |
| L | 8-13 points | 3-5 days | Large feature |
| XL | 21+ points | > 1 week | Epic (must split) |

**Conversion to Story Points:**
- T-shirt sizes used in initial grooming
- Convert to points during detailed estimation
- XL items must be split before sprint commitment

### 5.4 Velocity Tracking

**Definition:** Average number of story points completed per sprint

**Calculation:**
```
Velocity = Σ(Completed Story Points) / Number of Sprints
```

**Smart Dairy Team Velocity Guidelines:**

| Team Type | Expected Velocity | Notes |
|-----------|------------------|-------|
| Frontend Team | 35-45 points | React/Next.js development |
| Backend Team | 40-50 points | API and microservices |
| IoT Integration | 25-35 points | Hardware dependencies |
| Mobile Team | 30-40 points | React Native development |

**Velocity Adjustment Factors:**
- Team member availability (vacation, training)
- Holiday periods
- New team member onboarding
- Technical debt sprints

---

## 6. Capacity Planning

### 6.1 Team Availability Calculation

**Formula:**
```
Available Capacity = (Team Members × Working Days) - (Time Off + Meetings + Overhead)
```

**Standard Sprint Capacity (2 weeks):**

| Role | Headcount | Capacity % | Effective Days |
|------|-----------|------------|----------------|
| Senior Developers | 4 | 80% | 8 days each |
| Mid-Level Developers | 3 | 85% | 8.5 days each |
| Junior Developers | 2 | 75% | 7.5 days each |
| QA Engineers | 3 | 85% | 8.5 days each |
| DevOps Engineer | 1 | 70% | 7 days |

### 6.2 Capacity Planning Spreadsheet

```
================================================================================
                    SMART DAIRY SPRINT CAPACITY PLANNER
================================================================================
Sprint: SD-2026-Q1-S2          Dates: Feb 2 - Feb 13, 2026

Team Member     | Role          | Available | PTO | Meetings | Dev Days | Focus %
----------------|---------------|-----------|-----|----------|----------|--------
Alice Chen      | Senior Dev    |    10     |  0  |    1.5   |   8.5    |   85%
Bob Martinez    | Senior Dev    |    10     |  2  |    1.5   |   6.5    |   65%
Carol White     | Mid Dev       |    10     |  0  |    1.5   |   8.5    |   85%
David Kim       | Mid Dev       |    10     |  0  |    1.5   |   8.5    |   85%
Eva Patel       | Junior Dev    |    10     |  0  |    2.0   |   8.0    |   80%
Frank Lee       | QA Engineer   |    10     |  0  |    2.0   |   8.0    |   80%
Grace Okafor    | QA Engineer   |    10     |  1  |    2.0   |   7.0    |   70%
Henry Brown     | DevOps        |    10     |  0  |    3.0   |   7.0    |   70%
----------------|---------------|-----------|-----|----------|----------|--------
TOTALS          |               |    80     |  3  |   15.0   |   62.0   |   77.5%

================================================================================
                              VELOCITY FORECAST
================================================================================

Average Velocity (last 5 sprints): 42 story points
Team Availability Factor: 77.5% / 85% = 0.91
Adjusted Capacity: 42 × 0.91 = 38 story points

Buffer (20% for uncertainty): 8 points
Recommended Commitment: 30 story points

================================================================================
```

### 6.3 Capacity Allocation Guidelines

**Recommended Distribution:**

| Category | Allocation | Purpose |
|----------|------------|---------|
| New Features | 60% | Primary sprint objectives |
| Bug Fixes | 15% | Production support |
| Technical Debt | 10% | Code refactoring |
| Learning/Spikes | 10% | Research and exploration |
| Buffer | 5% | Unexpected issues |

### 6.4 Focus Factor

**Definition:** Percentage of time actually spent on sprint work vs. total available time

**Typical Focus Factors:**
- New Team: 50-60%
- Maturing Team: 60-70%
- Experienced Team: 70-80%
- High-Performing Team: 80-85%

**Calculation:**
```
Focus Factor = (Actual Story Points Completed) / (Theoretical Capacity)
```

---

## 7. Sprint Goal Definition

### 7.1 Characteristics of a Good Sprint Goal

**SMART Criteria:**
- **S**pecific: Clearly states what will be achieved
- **M**easurable: Success can be verified
- **A**chievable: Realistic given capacity
- **R**elevant: Aligns with product roadmap
- **T**ime-bound: Achievable within sprint

### 7.2 Sprint Goal Template

```
For [Target User/Persona],
we will [Main Deliverable],
so that [Business Value/Benefit],
validated by [Success Criteria].
```

### 7.3 Smart Dairy Sprint Goal Examples

**Example 1 - IoT Integration Sprint:**
> "For dairy farm operators, we will implement real-time milk production monitoring from IoT sensors to the dashboard, so that farmers can track daily yield trends, validated by successful data ingestion and visualization of test sensor data."

**Example 2 - E-Commerce Sprint:**
> "For online customers, we will complete the product catalog browsing and shopping cart functionality, so that users can add dairy products to cart and proceed to checkout, validated by end-to-end purchase flow testing."

**Example 3 - Authentication Sprint:**
> "For all portal users, we will implement secure multi-factor authentication using SMS and email OTP, so that account security is enhanced while maintaining user experience, validated by penetration testing and user acceptance testing."

### 7.4 Sprint Goal Anti-Patterns

| Anti-Pattern | Example | Better Alternative |
|--------------|---------|-------------------|
| Feature List | "Complete stories 101, 102, 103" | "Enable farmers to view livestock health data" |
| Too Broad | "Improve the system" | "Reduce dashboard load time by 50%" |
| Too Narrow | "Fix button color" | "Complete UI polish for v1.0 release" |
| Technical Focus | "Refactor database layer" | "Enable faster report generation through optimization" |
| No Value | "Implement API endpoints" | "Support mobile app data sync through API completion" |

### 7.5 Sprint Goal Validation Checklist

- [ ] Can the entire team articulate the goal in one sentence?
- [ ] Does it provide value to Smart Dairy or end users?
- [ ] Is it achievable within the sprint?
- [ ] Can success be demonstrated at Sprint Review?
- [ ] Does it align with the current release objectives?
- [ ] Will stakeholders understand the value?

---

## 8. Definition of Ready Checklist

### 8.1 Story-Level DoR

**Must Have (Required):**

| # | Criterion | Check |
|---|-----------|-------|
| 1 | User story follows INVEST principles | [ ] |
| 2 | Clear, concise title describing the feature | [ ] |
| 3 | User story format: "As a [role], I want [feature], so that [benefit]" | [ ] |
| 4 | Business value is explicitly stated | [ ] |
| 5 | Acceptance criteria are specific and testable | [ ] |
| 6 | Story fits within single sprint (≤ 13 points) | [ ] |
| 7 | No unresolved external dependencies | [ ] |
| 8 | UI mockups/wireframes attached (if applicable) | [ ] |
| 9 | Technical approach briefly documented | [ ] |
| 10 | Story estimated by the team | [ ] |

**Should Have (Recommended):**

| # | Criterion | Check |
|---|-----------|-------|
| 11 | Edge cases identified | [ ] |
| 12 | Data requirements specified | [ ] |
| 13 | Performance criteria defined | [ ] |
| 14 | Security requirements noted | [ ] |
| 15 | Analytics/tracking requirements defined | [ ] |

### 8.2 Technical Story DoR

Additional criteria for technical stories:

- [ ] Business justification documented
- [ ] Current state vs. desired state described
- [ ] Success metrics defined
- [ ] Risk assessment completed
- [ ] Rollback plan identified (if applicable)

### 8.3 Bug Fix DoR

- [ ] Steps to reproduce documented
- [ ] Expected vs. actual behavior specified
- [ ] Environment details provided
- [ ] Severity and priority assigned
- [ ] Affected versions noted
- [ ] Screenshots/logs attached

### 8.4 DoR Sign-Off

**Process:**
1. Product Owner ensures business clarity
2. Tech Lead validates technical feasibility
3. QA Lead confirms testability
4. Scrum Master verifies completeness
5. Team collectively approves during grooming

---

## 9. Task Breakdown

### 9.1 Task Definition

**Purpose:** Decompose user stories into executable work units

**Task Characteristics:**
- Duration: 2-8 hours ideally, max 16 hours
- Assignable to single team member
- Clear definition of done
- No business value on its own

### 9.2 Task Breakdown Template

```
================================================================================
                    TASK BREAKDOWN WORKSHEET
================================================================================
User Story: [Story Title]
Story ID: [PROJ-123]
Story Points: [X points]
Assignee: [Primary Developer]

--------------------------------------------------------------------------------
TASK LIST
--------------------------------------------------------------------------------

Task ID | Description                        | Type    | Est (h) | Owner
--------|------------------------------------|---------|---------|--------
T-01    | [Brief task description]           | Dev     |   4     | [Name]
T-02    | [Brief task description]           | Dev     |   6     | [Name]
T-03    | [Brief task description]           | Test    |   4     | [Name]
T-04    | [Brief task description]           | Review  |   2     | [Name]
--------|------------------------------------|---------|---------|--------
TOTAL   |                                    |         |  16     |

--------------------------------------------------------------------------------
DEPENDENCIES
--------------------------------------------------------------------------------
- Depends on: [List blocking items]
- Blocks: [List items blocked by this story]

--------------------------------------------------------------------------------
ACCEPTANCE CRITERIA MAPPING
--------------------------------------------------------------------------------
- AC-01: Covered by tasks T-01, T-02
- AC-02: Covered by tasks T-02, T-03

================================================================================
```

### 9.3 Standard Task Types

| Type | Description | Examples |
|------|-------------|----------|
| **Analysis** | Requirements clarification | Review requirements, spike research |
| **Design** | Technical/architecture design | Database design, API design, UI design |
| **Development** | Code implementation | Frontend coding, backend coding, integration |
| **Testing** | Quality assurance | Unit tests, integration tests, manual testing |
| **Review** | Code and documentation review | Code review, PR review, documentation review |
| **Documentation** | Technical documentation | API docs, deployment guides, runbooks |
| **Deployment** | Release activities | Build, deploy, configuration |

### 9.4 Task Breakdown Examples

**Example 1: User Login Feature**

| Task | Description | Hours | Type |
|------|-------------|-------|------|
| 1 | Create login form UI component | 4 | Dev |
| 2 | Implement form validation | 2 | Dev |
| 3 | Create authentication API endpoint | 4 | Dev |
| 4 | Integrate frontend with backend | 3 | Dev |
| 5 | Write unit tests for components | 3 | Test |
| 6 | Implement integration tests | 3 | Test |
| 7 | Code review and fixes | 2 | Review |
| **Total** | | **21** | |

**Example 2: IoT Sensor Data Integration**

| Task | Description | Hours | Type |
|------|-------------|-------|------|
| 1 | Design message queue architecture | 4 | Design |
| 2 | Set up MQTT broker configuration | 3 | Dev |
| 3 | Create data ingestion service | 6 | Dev |
| 4 | Implement data transformation logic | 4 | Dev |
| 5 | Create database schema for sensor data | 3 | Dev |
| 6 | Write data persistence layer | 4 | Dev |
| 7 | Add error handling and retry logic | 3 | Dev |
| 8 | Performance testing | 4 | Test |
| **Total** | | **31** | |

### 9.5 Task Estimation Guidelines

**Hour Estimation Ranges:**

| Estimation | Meaning | Use When |
|------------|---------|----------|
| 1 hour | Trivial task | Clear, familiar work |
| 2-4 hours | Small task | Well-understood implementation |
| 6-8 hours | Medium task | Some complexity or unknowns |
| 12-16 hours | Large task | Significant complexity |
| > 16 hours | Must split | Task is too large |

**Estimation Tips:**
- Include time for context switching
- Account for testing within each task
- Add buffer for code review fixes
- Consider learning curve for new technologies

---

## 10. Sprint Backlog Creation

### 10.1 JIRA Setup

**Project Configuration:**

| Setting | Value |
|---------|-------|
| Project Key | SD (Smart Dairy) |
| Issue Types | Story, Bug, Task, Sub-task, Epic |
| Workflow | To Do → In Progress → In Review → Testing → Done |
| Estimation | Story Points (Fibonacci) |
| Time Tracking | Enabled (Original/Remaining Estimate) |

**Sprint Creation Steps:**
1. Navigate to Backlog view
2. Click "Create Sprint"
3. Name sprint using convention: "SD-2026-Q1-S2"
4. Set sprint duration dates
5. Add Sprint Goal to description
6. Drag stories from Product Backlog to Sprint
7. Verify total points align with velocity
8. Start Sprint

### 10.2 Azure DevOps Setup

**Project Configuration:**

| Setting | Value |
|---------|-------|
| Process | Agile or Scrum |
| Iteration Path | SmartDairy\2026\Q1\Sprint 2 |
| Work Item Types | User Story, Bug, Task |
| Effort Field | Story Points |

**Sprint Creation Steps:**
1. Go to Project Settings → Boards → Team Configuration
2. Define iteration schedule (2-week cycles)
3. Set team capacity for each member
4. Create new iteration with dates
5. In Backlog view, select current iteration
6. Add stories to iteration
7. Set iteration goal

### 10.3 Sprint Backlog Structure

```
================================================================================
                    SPRINT BACKLOG STRUCTURE
================================================================================

EPIC: Livestock Management Module
│
├── STORY: SD-156 - View livestock health dashboard
│   ├── Sub-task: Create dashboard layout component
│   ├── Sub-task: Implement health metrics API
│   ├── Sub-task: Add data visualization charts
│   └── Sub-task: Write integration tests
│
├── STORY: SD-157 - Record veterinary visits
│   ├── Sub-task: Design visit form UI
│   ├── Sub-task: Create visit API endpoints
│   ├── Sub-task: Implement form validation
│   └── Sub-task: Add notification service
│
└── STORY: SD-158 - Generate health reports
    ├── Sub-task: Design report template
    ├── Sub-task: Implement PDF generation
    ├── Sub-task: Add email delivery
    └── Sub-task: Create report scheduler

================================================================================
```

### 10.4 Sprint Backlog Metrics

**Track Daily:**
- Remaining story points
- Burndown chart progress
- Tasks in progress vs. blocked
- Impediments count

**Health Indicators:**
- **Green:** On track, no blockers
- **Yellow:** At risk, needs attention
- **Red:** Off track, intervention needed

---

## 11. Commitment vs Forecast

### 11.1 Understanding the Difference

| Aspect | Commitment | Forecast |
|--------|------------|----------|
| **Definition** | Team's promise to deliver | Prediction of what might be delivered |
| **Basis** | Capacity and confidence | Historical velocity |
| **Flexibility** | Fixed (barring scope changes) | Adjustable based on new information |
| **Accountability** | Team is accountable | Team is not penalized for variance |
| **Use** | Sprint planning output | Release planning, stakeholder communication |

### 11.2 Commitment-Based Planning

**Team Commitment Criteria:**
- Team has discussed all stories in detail
- Technical approach is understood
- Dependencies are identified and manageable
- Capacity calculations account for availability
- Risk assessment completed

**Commitment Statement Template:**
```
We, the [Team Name], commit to achieving the Sprint Goal:
"[Sprint Goal Statement]"

We will complete the following stories:
- [Story 1] - [X points]
- [Story 2] - [X points]
- [Story 3] - [X points]

Total: [X] story points

We understand our capacity is [X] days and have confidence 
in our ability to deliver based on our average velocity of [Y].
```

### 11.3 Forecast-Based Planning

**Use Cases:**
- Release planning and roadmap projections
- Stakeholder communication about future capabilities
- Resource planning and budgeting
- Identifying scope for future sprints

**Forecast Ranges:**

| Confidence | Calculation | Use For |
|------------|-------------|---------|
| 50% | Average velocity | Best case planning |
| 70% | Average - 10% | Realistic planning |
| 90% | Average - 20% | Conservative planning |

### 11.4 Managing Commitment

**When Scope Changes:**
1. Assess impact on Sprint Goal
2. Discuss with Product Owner
3. Identify items to remove (equal points)
4. Update Sprint Backlog
5. Re-confirm commitment

**When Capacity Changes:**
1. Recalculate available capacity
2. Identify at-risk stories
3. Discuss mitigation options
4. Adjust expectations with PO
5. Document changes

### 11.5 Commitment Tracking

**Sprint Health Dashboard:**

| Metric | Target | Current | Status |
|--------|--------|---------|--------|
| Committed Points | 40 | 40 | ✓ |
| Completed Points | - | 32 | 🟡 |
| Sprint Goal Progress | 100% | 80% | 🟡 |
| Blocked Stories | 0 | 2 | 🔴 |
| Defects Found | < 5 | 3 | ✓ |

---

## 12. Sprint Planning Template

### 12.1 Meeting Agenda Template

```
================================================================================
                    SPRINT PLANNING AGENDA
================================================================================

Sprint: [SD-YYYY-Q#-S#]
Date: [Date]
Time: [Start Time] - [End Time]
Location: [Room/Video Link]
Duration: 4 hours

--------------------------------------------------------------------------------
PARTICIPANTS
--------------------------------------------------------------------------------
Role               | Name              | Status
-------------------|-------------------|----------
Product Owner      | [Name]            | Required
Scrum Master       | [Name]            | Required
Tech Lead          | [Name]            | Required
Senior Developers  | [Names]           | Required
QA Lead            | [Name]            | Required
DevOps Engineer    | [Name]            | Required

--------------------------------------------------------------------------------
PRE-REQUISITES (Complete Before Meeting)
--------------------------------------------------------------------------------
[ ] Product Backlog prioritized and visible
[ ] Top stories meet Definition of Ready
[ ] Team availability confirmed
[ ] Previous velocity data prepared
[ ] Meeting room/tech set up
[ ] Planning poker tools ready

--------------------------------------------------------------------------------
AGENDA
--------------------------------------------------------------------------------

PART 1: WHAT TO BUILD (2 hours)
Time        | Activity                     | Owner      | Duration
------------|------------------------------|------------|----------
0:00-0:10   | Opening & Context Setting    | Scrum Master| 10 min
0:10-0:30   | Business Context & Updates   | Product Owner| 20 min
0:30-0:50   | Proposed Sprint Goal Review  | Product Owner| 20 min
0:50-1:30   | Backlog Item Presentation    | Product Owner| 40 min
1:30-1:50   | Q&A and Clarification        | Team       | 20 min
1:50-2:00   | Break                        | -          | 10 min

PART 2: HOW TO BUILD (2 hours)
Time        | Activity                     | Owner      | Duration
------------|------------------------------|------------|----------
2:00-2:15   | Technical Context Review     | Tech Lead  | 15 min
2:15-3:00   | Technical Approach Discussion| Team       | 45 min
3:00-3:45   | Task Breakdown               | Team       | 45 min
3:45-4:15   | Estimation & Capacity Check  | Team       | 30 min
4:15-4:45   | Sprint Commitment            | Team       | 30 min
4:45-5:00   | Closure & Next Steps         | Scrum Master| 15 min

--------------------------------------------------------------------------------
OUTPUTS
--------------------------------------------------------------------------------
[ ] Final Sprint Goal agreed
[ ] Committed Sprint Backlog created
[ ] Stories broken down into tasks
[ ] Initial estimates validated
[ ] Team commitment documented
[ ] Risks and dependencies noted

================================================================================
```

### 12.2 Sprint Planning Output Template

```
================================================================================
                    SPRINT PLANNING OUTPUT
================================================================================

SPRINT INFORMATION
--------------------------------------------------------------------------------
Sprint Name:        [SD-2026-Q1-S2]
Sprint Dates:       [Start Date] - [End Date] (2 weeks)
Sprint Goal:        [Clear, concise goal statement]

TEAM COMMITMENT
--------------------------------------------------------------------------------
Total Story Points Committed: [XX points]
Total Tasks:                   [XX tasks]
Estimated Hours:               [XXX hours]

COMMITTED STORIES
--------------------------------------------------------------------------------
# | ID    | Title                          | Points | Priority
--|-------|--------------------------------|--------|----------
1 | SD-156| [Story title]                  |   8    | High
2 | SD-157| [Story title]                  |   5    | High
3 | SD-158| [Story title]                  |   5    | Medium
4 | SD-159| [Story title]                  |   3    | Medium
5 | SD-160| [Story title]                  |   3    | Low
--|-------|--------------------------------|--------|----------
  |       | TOTAL                          |  24    |

CAPACITY SUMMARY
--------------------------------------------------------------------------------
Team Members:                  [X people]
Available Days:                [XX days]
Focus Factor:                  [XX%]
Adjusted Capacity:             [XX story points]
Buffer (20%):                  [XX story points]
Net Commitment:                [XX story points]

RISKS & DEPENDENCIES
--------------------------------------------------------------------------------
Risk 1: [Description] | Mitigation: [Action]
Risk 2: [Description] | Mitigation: [Action]

Dependency 1: [Description] | Owner: [Name]
Dependency 2: [Description] | Owner: [Name]

DEFINITION OF DONE REMINDER
--------------------------------------------------------------------------------
[ ] Code complete and peer reviewed
[ ] Unit tests written and passing
[ ] Integration tests passing
[ ] Documentation updated
[ ] QA testing complete
[ ] PO acceptance criteria verified

APPROVALS
--------------------------------------------------------------------------------
Product Owner:    _________________________ Date: _______
Scrum Master:     _________________________ Date: _______
Tech Lead:        _________________________ Date: _______

================================================================================
```

### 12.3 Sprint Planning Checklist

**Before Planning:**
- [ ] Calendar invites sent 1 week in advance
- [ ] Agenda distributed 2 days before
- [ ] Product Backlog prioritized
- [ ] Stories groomed and estimated
- [ ] Team availability confirmed
- [ ] Meeting room booked / video link tested
- [ ] Planning poker tools prepared
- [ ] Previous sprint metrics available

**During Planning - Part 1:**
- [ ] Start on time
- [ ] Review business context
- [ ] Discuss and agree Sprint Goal
- [ ] Review candidate stories
- [ ] Clarify requirements and acceptance criteria
- [ ] Identify dependencies and risks

**During Planning - Part 2:**
- [ ] Discuss technical approach
- [ ] Break stories into tasks
- [ ] Estimate tasks in hours
- [ ] Validate against capacity
- [ ] Team commits to Sprint Backlog
- [ ] Document risks and mitigation

**After Planning:**
- [ ] Update JIRA/Azure DevOps
- [ ] Create sprint in tracking tool
- [ ] Add tasks to stories
- [ ] Set up sprint dashboard
- [ ] Share sprint goal with stakeholders
- [ ] Schedule mid-sprint review
- [ ] Send summary to team

---

## 13. Common Pitfalls

### 13.1 Anti-Patterns to Avoid

| Anti-Pattern | Description | Solution |
|--------------|-------------|----------|
| **Sprint Goal Neglect** | Treating sprint as just a batch of stories | Always start with goal discussion |
| **Commitment Overload** | Committing to more than capacity allows | Use velocity + capacity calculations |
| **Manager-Driven Commitment** | Management dictates what team must complete | Team self-organizes and commits |
| **No Task Breakdown** | Stories without task decomposition | Break down all stories before commitment |
| **Hero Culture** | Relying on individuals to work overtime | Sustainable pace, shared responsibility |
| **Scope Creep** | Adding items during sprint without removal | Strict change control process |
| **Skipping Estimation** | Committing without understanding effort | Always estimate before committing |
| **Ignoring Technical Debt** | Only focusing on new features | Allocate 10-15% for debt reduction |
| **Silent Planning** | Team members not speaking up | Facilitate inclusive discussion |
| **Pre-Assignment** | Deciding who does what during planning | Self-assignment during sprint |

### 13.2 Warning Signs

**During Planning:**
- Team is silent or disengaged
- No questions asked during story review
- Estimates seem arbitrary
- Technical discussions are skipped
- Capacity is ignored

**Early Sprint Indicators:**
- Stories moving to "In Progress" without task breakdown
- Multiple blockers appearing immediately
- Team members working in isolation
- Daily standups revealing confusion

### 13.3 Mitigation Strategies

**For Poor Estimation:**
- Use reference stories for calibration
- Track estimation accuracy over time
- Conduct estimation retrospectives
- Provide training on estimation techniques

**For Overcommitment:**
- Review historical velocity honestly
- Account for all time off and meetings
- Include buffer for unexpected issues
- Use focus factors appropriately

**For Unclear Requirements:**
- Enforce Definition of Ready strictly
- Include business stakeholder in grooming
- Create story templates with required fields
- Conduct story review sessions

### 13.4 When to Stop Planning

**Extend Planning If:**
- Critical stories don't meet DoR
- Sprint Goal is unclear
- Major dependencies discovered
- Team lacks confidence in commitment

**Proceed with Caution If:**
- Some stories need minor clarification
- A few tasks need further breakdown
- Non-critical dependencies identified

**Red Flags - Do Not Start Sprint:**
- No clear Sprint Goal
- Stories significantly larger than velocity
- Critical team members unavailable
- Blocking dependencies unresolved

---

## 14. Appendices

### Appendix A: User Story Template

```markdown
================================================================================
                          USER STORY TEMPLATE
================================================================================

STORY INFORMATION
--------------------------------------------------------------------------------
Story ID:       [PROJ-###]
Title:          [Brief, descriptive title]
Epic:           [Parent epic name]
Sprint:         [Sprint identifier]
Priority:       [Critical/High/Medium/Low]
Story Points:   [Fibonacci number]

USER STORY
--------------------------------------------------------------------------------
As a [type of user/persona],
I want [goal/desire],
so that [benefit/value].

ACCEPTANCE CRITERIA
--------------------------------------------------------------------------------
Given [context/prerequisites],
When [action/event occurs],
Then [expected outcome].

Detailed AC:
1. [ ] [Specific, testable condition]
2. [ ] [Specific, testable condition]
3. [ ] [Specific, testable condition]
4. [ ] [Specific, testable condition]

TECHNICAL NOTES
--------------------------------------------------------------------------------
- Architecture considerations:
- API changes needed:
- Database changes needed:
- Third-party integrations:
- Security considerations:
- Performance requirements:

UI/UX SPECIFICATIONS
--------------------------------------------------------------------------------
- Wireframe/Mockup: [Link to design]
- Responsive behavior:
- Accessibility requirements:

DATA REQUIREMENTS
--------------------------------------------------------------------------------
- Data entities involved:
- Migration needs:
- Analytics/tracking events:

DEPENDENCIES
--------------------------------------------------------------------------------
- Depends on: [Story IDs]
- Blocks: [Story IDs]
- External dependencies:

DEFINITION OF DONE
--------------------------------------------------------------------------------
- [ ] Code implemented
- [ ] Unit tests written (>80% coverage)
- [ ] Integration tests passing
- [ ] Code reviewed and approved
- [ ] QA testing complete
- [ ] PO acceptance verified
- [ ] Documentation updated
- [ ] Deployed to staging

NOTES
--------------------------------------------------------------------------------
[Additional context, assumptions, or notes]

================================================================================
```

### Appendix B: Sprint Planning Checklist (Quick Reference)

```
╔══════════════════════════════════════════════════════════════════════════════╗
║                    SPRINT PLANNING QUICK CHECKLIST                           ║
╚══════════════════════════════════════════════════════════════════════════════╝

BEFORE PLANNING
□ Backlog refined and prioritized
□ Team availability confirmed
□ Previous velocity calculated
□ Meeting scheduled with agenda
□ Tools ready (JIRA, planning poker)

PART 1: WHAT
□ Business context reviewed
□ Sprint Goal proposed and agreed
□ Stories presented by PO
□ Questions answered
□ Scope understood

PART 2: HOW
□ Technical approach discussed
□ Stories broken into tasks
□ Tasks estimated in hours
□ Capacity validated
□ Commitment made

AFTER PLANNING
□ Sprint created in JIRA
□ Tasks added to stories
□ Goal communicated
□ Sprint started
□ Stakeholders notified

╔══════════════════════════════════════════════════════════════════════════════╗
║  SUCCESS CRITERIA: Goal clear + Backlog committed + Team confident           ║
╚══════════════════════════════════════════════════════════════════════════════╝
```

### Appendix C: Capacity Calculation Template

```
================================================================================
                    TEAM CAPACITY CALCULATOR
================================================================================

Sprint: ______________________    Dates: ______________________

AVAILABILITY MATRIX
--------------------------------------------------------------------------------
Team Member     | Role          | Total Days | Time Off | Meeting | Dev Days
----------------|---------------|------------|----------|---------|----------
                |               |            |          |         |
                |               |            |          |         |
                |               |            |          |         |
                |               |            |          |         |
                |               |            |          |         |
----------------|---------------|------------|----------|---------|----------
TOTALS          |               |            |          |         |

CAPACITY CALCULATIONS
--------------------------------------------------------------------------------
Total Team Members:          _____
Total Available Days:        _____
Less: Time Off:              _____
Less: Meetings/Overhead:     _____
Net Development Days:        _____

Average Focus Factor:        _____%
Adjusted Capacity:           _____ days

VELOCITY PROJECTION
--------------------------------------------------------------------------------
Average Velocity (last 5):   _____ points
Team Availability Factor:    _____%
Adjusted Velocity:           _____ points
Less: Buffer (20%):          _____ points
Recommended Commitment:      _____ points

CAPACITY ALLOCATION
--------------------------------------------------------------------------------
Category         | Percentage | Points | Notes
-----------------|------------|--------|-------
New Features     |    60%     |        |
Bug Fixes        |    15%     |        |
Technical Debt   |    10%     |        |
Spikes/Learning  |    10%     |        |
Buffer           |     5%     |        |
-----------------|------------|--------|-------
TOTAL            |   100%     |        |

================================================================================
```

### Appendix D: Task Breakdown Examples by Story Type

**D.1 UI/Frontend Story (5 points)**

| Task ID | Task Description | Hours | Owner |
|---------|-----------------|-------|-------|
| T1 | Review design mockups and clarify requirements | 1 | Dev |
| T2 | Create component structure and layout | 3 | Dev |
| T3 | Implement form controls and validation | 3 | Dev |
| T4 | Integrate with backend API | 2 | Dev |
| T5 | Add responsive styling | 2 | Dev |
| T6 | Write unit tests for components | 2 | Dev |
| T7 | Accessibility testing | 1 | QA |
| T8 | Cross-browser testing | 1 | QA |
| T9 | Code review | 1 | Peer |
| | **Total** | **16** | |

**D.2 Backend API Story (8 points)**

| Task ID | Task Description | Hours | Owner |
|---------|-----------------|-------|-------|
| T1 | API specification design | 2 | Lead |
| T2 | Database schema design | 2 | Lead |
| T3 | Create entity models | 2 | Dev |
| T4 | Implement repository layer | 3 | Dev |
| T5 | Implement service layer | 4 | Dev |
| T6 | Create API controllers | 3 | Dev |
| T7 | Add input validation | 2 | Dev |
| T8 | Write unit tests | 3 | Dev |
| T9 | Write integration tests | 3 | Dev |
| T10 | API documentation | 1 | Dev |
| T11 | Code review | 2 | Peer |
| | **Total** | **27** | |

**D.3 Database Story (5 points)**

| Task ID | Task Description | Hours | Owner |
|---------|-----------------|-------|-------|
| T1 | Schema design and review | 3 | DBA |
| T2 | Create migration script | 2 | Dev |
| T3 | Update entity models | 2 | Dev |
| T4 | Data migration (if needed) | 2 | Dev |
| T5 | Update related queries | 3 | Dev |
| T6 | Performance testing | 2 | QA |
| T7 | Rollback procedure | 1 | Dev |
| T8 | Code review | 1 | Peer |
| | **Total** | **16** | |

**D.4 DevOps/Infrastructure Story (8 points)**

| Task ID | Task Description | Hours | Owner |
|---------|-----------------|-------|-------|
| T1 | Infrastructure requirements gathering | 2 | DevOps |
| T2 | Terraform/CloudFormation scripts | 4 | DevOps |
| T3 | CI/CD pipeline configuration | 3 | DevOps |
| T4 | Environment setup | 2 | DevOps |
| T5 | Monitoring and alerting setup | 3 | DevOps |
| T6 | Security configuration review | 2 | Security |
| T7 | Load testing | 2 | QA |
| T8 | Documentation | 2 | DevOps |
| T9 | Runbook creation | 2 | DevOps |
| | **Total** | **22** | |

### Appendix E: Sprint Calendar Template

```
================================================================================
                    SMART DAIRY SPRINT CALENDAR - TEMPLATE
================================================================================

Sprint: SD-2026-Q1-S2
Duration: 2 weeks (Monday, Feb 2 - Friday, Feb 13, 2026)

WEEK 1
--------------------------------------------------------------------------------
|   | MONDAY        | TUESDAY       | WEDNESDAY     | THURSDAY      | FRIDAY
---|---------------|---------------|---------------|---------------|---------------
09:00| SPRINT      | Development   | Development   | Development   | Development
     | PLANNING    |               |               |               |
     | (4 hours)   |               |               |               |
-----|-------------|---------------|---------------|---------------|---------------
09:30| Standup     | Standup       | Standup       | Standup       | Standup
-----|-------------|---------------|---------------|---------------|---------------
14:00| Development | Development   | BACKLOG       | Development   | Development
     |             |               | REFINEMENT    |               |
     |             |               | (2 hours)     |               |
-----|-------------|---------------|---------------|---------------|---------------
16:00| Development | Development   | Development   | Development   | Team
     |             |               |               |               | Checkpoint

WEEK 2
--------------------------------------------------------------------------------
|   | MONDAY        | TUESDAY       | WEDNESDAY     | THURSDAY      | FRIDAY
---|---------------|---------------|---------------|---------------|---------------
09:00| Development | Development   | Development   | Testing/      | SPRINT
     |             |               |               | Bug Fixes     | REVIEW
     |             |               |               |               | (1 hour)
-----|-------------|---------------|---------------|---------------|---------------
09:30| Standup     | Standup       | Standup       | Standup       | Standup
-----|-------------|---------------|---------------|---------------|---------------
14:00| Development | Testing       | Testing       | Final Testing | RETRO
     |             | Begins        |               |               | (1.5 hrs)
-----|-------------|---------------|---------------|---------------|---------------
16:00| Mid-Sprint  | Bug Fix       | Bug Fix       | Code Freeze   | Release
     | Review      |               |               | (EOD)         | Prep

================================================================================
KEY EVENTS
================================================================================
• Sprint Planning:    Week 1, Monday, 9:00 AM - 1:00 PM
• Daily Standup:      Every day, 9:30 AM - 9:45 AM
• Backlog Refinement: Week 1, Wednesday, 2:00 PM - 4:00 PM
• Mid-Sprint Review:  Week 2, Monday, 4:00 PM - 4:30 PM
• Code Freeze:        Week 2, Thursday, 5:00 PM
• Sprint Review:      Week 2, Friday, 10:00 AM - 11:00 AM
• Retrospective:      Week 2, Friday, 2:00 PM - 3:30 PM
================================================================================
```

### Appendix F: Estimation Reference Cards

**Story Point Reference Guide for Smart Dairy:**

```
╔══════════════════════════════════════════════════════════════════════════════╗
║  1 POINT                          ║  2 POINTS                         ║
║  • Text/label change              ║  • Simple configuration change    ║
║  • Color update                   ║  • Simple API field addition      ║
║  • Bug fix with known cause       ║  • Minor UI layout adjustment     ║
║  • Documentation update           ║  • Unit test addition             ║
╠══════════════════════════════════════════════════════════════════════════════╣
║  3 POINTS                         ║  5 POINTS                         ║
║  • Standard form with validation  ║  • New dashboard widget           ║
║  • Simple data table display      ║  • Complex form with dependencies ║
║  • API integration (existing)     ║  • Report generation (standard)   ║
║  • Filter/search implementation   ║  • Email notification feature     ║
╠══════════════════════════════════════════════════════════════════════════════╣
║  8 POINTS                         ║  13 POINTS                        ║
║  • User authentication flow       ║  • Third-party integration        ║
║  • Multi-step wizard              ║  • Complex workflow engine        ║
║  • File upload with processing    ║  • Data migration with transform  ║
║  • Role-based access control      ║  • Real-time feature (WebSocket)  ║
╠══════════════════════════════════════════════════════════════════════════════╣
║  21 POINTS                        ║  SPLIT REQUIRED                   ║
║  • Payment gateway integration    ║  Stories this large should be     ║
║  • IoT end-to-end pipeline        ║  broken down into smaller,        ║
║  • Complex reporting module       ║  sprint-sized stories before      ║
║  • Multi-system integration       ║  commitment.                      ║
╚══════════════════════════════════════════════════════════════════════════════╝
```

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| **Author** | Scrum Master | _________________________ | _______ |
| **Owner** | Project Manager | _________________________ | _______ |
| **Reviewer** | CTO | _________________________ | _______ |
| **Approved By** | Head of Engineering | _________________________ | _______ |

---

## Related Documents

| Document ID | Title | Location |
|-------------|-------|----------|
| J-001 | Agile Project Charter | PROJECT MANAGEMENT DOCUMENTATION |
| J-003 | Daily Standup Guidelines | PROJECT MANAGEMENT DOCUMENTATION |
| J-004 | Sprint Review & Retrospective Template | PROJECT MANAGEMENT DOCUMENTATION |
| J-005 | Definition of Done | PROJECT MANAGEMENT DOCUMENTATION |
| J-006 | Impediment Management Process | PROJECT MANAGEMENT DOCUMENTATION |

---

*Document Control: This document is maintained by the Scrum Master and updated as processes evolve. All changes must be approved by the Project Manager and CTO.*

**End of Document J-002**
