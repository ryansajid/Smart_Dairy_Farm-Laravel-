# Document J-015: Agile Ceremonies Guidelines

## Smart Dairy Ltd. Smart Web Portal System Implementation

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | J-015 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Scrum Master |
| **Owner** | Project Manager |
| **Reviewer** | CTO |
| **Status** | Approved |
| **Classification** | Internal Use |

### Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 31, 2026 | Scrum Master | Initial version |

### Distribution List

| Role | Name | Department |
|------|------|------------|
| Project Manager | TBD | Project Management Office |
| Scrum Master | TBD | Agile Team |
| Development Lead | TBD | Engineering |
| QA Lead | TBD | Quality Assurance |
| Product Owner | TBD | Product Management |
| CTO | TBD | Technology |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Daily Standup](#2-daily-standup)
3. [Sprint Planning](#3-sprint-planning)
4. [Sprint Review](#4-sprint-review)
5. [Sprint Retrospective](#5-sprint-retrospective)
6. [Backlog Refinement](#6-backlog-refinement)
7. [Ceremony Schedule](#7-ceremony-schedule)
8. [Remote Ceremony Tips](#8-remote-ceremony-tips)
9. [Tools](#9-tools)
10. [Metrics](#10-metrics)
11. [Anti-Patterns](#11-anti-patterns)
12. [Appendices](#12-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document establishes the standardized guidelines for conducting Agile ceremonies within the Smart Dairy Ltd. Smart Web Portal System implementation project. It serves as a comprehensive reference for all team members participating in Scrum ceremonies, ensuring consistency, effectiveness, and continuous improvement across all sprints.

### 1.2 Scope

These guidelines apply to:
- All development team members
- Scrum Master
- Product Owner
- Project stakeholders
- Remote team members (Bangladesh)

### 1.3 Agile Ceremonies Overview

Agile ceremonies are structured events that provide rhythm, transparency, and opportunities for inspection and adaptation throughout the sprint cycle. For Smart Dairy Ltd., we follow the standard Scrum framework with the following ceremonies:

| Ceremony | Frequency | Duration | Participants |
|----------|-----------|----------|--------------|
| Daily Standup | Daily | 15 minutes | Development Team, Scrum Master, Product Owner (optional) |
| Sprint Planning | Per Sprint | 4 hours (2-week sprint) | Full Scrum Team |
| Sprint Review | Per Sprint | 1 hour | Full Scrum Team + Stakeholders |
| Sprint Retrospective | Per Sprint | 1 hour | Scrum Master + Development Team |
| Backlog Refinement | Weekly | 2 hours | Product Owner + Development Team |

### 1.4 Principles

All ceremonies at Smart Dairy Ltd. adhere to these core principles:

1. **Transparency** - All work and progress are visible to stakeholders
2. **Inspection** - Regular checkpoints to detect undesirable variances
3. **Adaptation** - Adjusting processes and plans based on inspection
4. **Time-boxing** - Strict adherence to ceremony time limits
5. **Collaboration** - Active participation from all attendees
6. **Continuous Improvement** - Learning from each sprint to improve the next

---

## 2. Daily Standup

### 2.1 Purpose

The Daily Standup (Daily Scrum) is a 15-minute time-boxed event for the Development Team to synchronize activities and create a plan for the next 24 hours. It optimizes collaboration and performance by inspecting progress toward the Sprint Goal.

### 2.2 Format: Yesterday, Today, Blockers

Each team member answers three questions:

#### Question 1: What did I complete yesterday?
- Focus on work related to the Sprint Goal
- Mention specific user stories or tasks
- Highlight any completed deliverables

#### Question 2: What will I work on today?
- Identify specific tasks for the day
- Connect daily work to Sprint Goal
- Mention any dependencies on other team members

#### Question 3: Are there any blockers or impediments?
- Raise issues preventing progress
- Identify dependencies on external teams
- Highlight resource constraints

### 2.3 Standup Template

```
┌─────────────────────────────────────────────────────────────────┐
│           SMART DAIRY LTD. - DAILY STANDUP TEMPLATE            │
├─────────────────────────────────────────────────────────────────┤
│ Date: ___________  Sprint: ___________  Day: ___ of 10         │
├─────────────────────────────────────────────────────────────────┤
│ TEAM MEMBER: _______________________________________________  │
│                                                                 │
│ □ YESTERDAY:                                                  │
│   • Completed: ______________________________________________ │
│   • Story/Task ID: __________________________________________ │
│                                                                 │
│ □ TODAY:                                                       │
│   • Working on: _____________________________________________ │
│   • Story/Task ID: __________________________________________ │
│   • Estimated completion: ____________________________________ │
│                                                                 │
│ □ BLOCKERS:                                                    │
│   • None / [Describe blocker]: ______________________________ │
│   • Support needed from: _____________________________________ │
│                                                                 │
├─────────────────────────────────────────────────────────────────┤
│ NOTES: _______________________________________________________ │
└─────────────────────────────────────────────────────────────────┘
```

### 2.4 Anti-Patterns to Avoid

| Anti-Pattern | Description | Solution |
|--------------|-------------|----------|
| **Status Report to SM/PO** | Team reporting to Scrum Master instead of each other | Rotate facilitation; remind team they're updating peers |
| **Problem-Solving Session** | Attempting to solve blockers during standup | Take discussions offline; schedule follow-up |
| **Late Arrivals** | Team members consistently joining after start time | Start on time regardless; enforce punctuality |
| **Too Long** | Standup extending beyond 15 minutes | Use a timer; park topics for later discussion |
| **Story-Level Detail** | Discussing implementation details instead of progress | Focus on "what," not "how" |
| **No Blockers Mentioned** | Team hiding impediments | Create psychological safety; celebrate raising issues |
| **People Not Listening** | Members disengaged until their turn | Use passing mechanism (ball/token); vary format |
| **Skipping Standup** | Canceling standup on "busy" days | Make it non-negotiable; mark as critical meeting |

### 2.5 Facilitation Tips

1. **Start on Time** - Begin exactly at scheduled time, regardless of attendance
2. **Same Time, Same Place** - Maintain consistency for habit formation
3. **Visual Board Reference** - Conduct standup in front of physical/digital board
4. **Parking Lot** - Capture items needing deeper discussion
5. **Rotate Facilitation** - Let different team members lead
6. **Time-box Strictly** - Use timer visible to all

---

## 3. Sprint Planning

### 3.1 Two-Part Structure

Sprint Planning consists of two distinct parts:

#### Part 1: The WHAT (2 hours)
- Product Owner presents highest-priority Product Backlog items
- Team discusses what can be delivered in the Sprint
- Sprint Goal is crafted collaboratively
- Selection of items for Sprint Backlog

#### Part 2: The HOW (2 hours)
- Development Team designs the work needed to meet the Sprint Goal
- Tasks are created and estimated
- Team capacity is considered
- Final commitment to Sprint Backlog

### 3.2 Duration: 4 Hours for 2-Week Sprint

| Duration | Ceremony Length | Recommendation |
|----------|-----------------|----------------|
| 1-week sprint | 2 hours | Reduce proportionally |
| 2-week sprint | 4 hours | Standard for Smart Dairy |
| 3-week sprint | 6 hours | Avoid if possible |
| 4-week sprint | 8 hours | Not recommended |

### 3.3 Inputs and Outputs

#### Inputs Required

| Input | Description | Owner |
|-------|-------------|-------|
| Product Backlog | Prioritized, refined items ready for sprint | Product Owner |
| Team Velocity | Historical performance data | Scrum Master |
| Team Capacity | Availability, holidays, planned absences | Team |
| Definition of Ready (DoR) | Criteria for items to enter sprint | Team |
| Previous Sprint Retrospective Actions | Improvements to incorporate | Scrum Master |
| Technical/Architectural Constraints | System limitations to consider | Tech Lead |

#### Outputs Produced

| Output | Description |
|--------|-------------|
| Sprint Goal | Single objective for the sprint |
| Sprint Backlog | Selected Product Backlog Items + Sprint Plan |
| Task Breakdown | Detailed tasks with estimates |
| Team Commitment | Mutual agreement on deliverables |
| Initial Burndown | Starting point for tracking |

### 3.4 Sprint Planning Checklist

```
┌─────────────────────────────────────────────────────────────────┐
│         SPRINT PLANNING CHECKLIST - SMART DAIRY LTD.           │
├─────────────────────────────────────────────────────────────────┤
│ PRE-PLANNING (Day Before)                                      │
│ □ Product Backlog refined and prioritized                      │
│ □ Top items meet Definition of Ready                           │
│ □ Velocity calculated from previous sprints                    │
│ □ Team capacity confirmed (absences, holidays)                 │
│ □ Stakeholder input collected                                  │
│ □ Technical dependencies identified                            │
│                                                                 │
│ PART 1: THE WHAT (2 hours)                                     │
│ □ Product Owner presents sprint vision                         │
│ □ Business value of top items explained                        │
│ □ Team discusses each candidate item                           │
│ □ Questions clarified with PO                                  │
│ □ Sprint Goal drafted and agreed                               │
│ □ Items selected for Sprint Backlog                            │
│                                                                 │
│ PART 2: THE HOW (2 hours)                                      │
│ □ Technical approach discussed                                 │
│ □ Tasks created for each PBI                                   │
│ □ Task estimates provided (hours/story points)                 │
│ □ Capacity vs. commitment validated                            │
│ □ Risks and dependencies identified                            │
│ □ Final Sprint Backlog confirmed                               │
│ □ Team commits to Sprint Goal                                  │
│                                                                 │
│ POST-PLANNING                                                  │
│ □ Sprint Backlog updated in JIRA                               │
│ □ Burndown chart initialized                                   │
│ □ Sprint announcement communicated                             │
│ □ Action items from Retro incorporated                         │
└─────────────────────────────────────────────────────────────────┘
```

---

## 4. Sprint Review

### 4.1 Purpose

The Sprint Review is held at the end of the Sprint to inspect the Increment and adapt the Product Backlog if needed. It is an informal working session, not a status meeting.

### 4.2 Demo Format

#### Recommended Structure (1 hour)

| Time | Activity | Owner |
|------|----------|-------|
| 0:00 - 0:05 | Welcome and Sprint Goal recap | Product Owner |
| 0:05 - 0:10 | Sprint metrics overview | Scrum Master |
| 0:10 - 0:40 | Working software demonstration | Development Team |
| 0:40 - 0:55 | Stakeholder feedback and discussion | All |
| 0:55 - 1:00 | Product Backlog preview and close | Product Owner |

#### Demo Guidelines

1. **Show Working Software** - Demonstrate actual functionality, not slides
2. **Tell a Story** - Walk through user scenarios and journeys
3. **Keep It Interactive** - Encourage stakeholders to try features
4. **Focus on Value** - Explain business benefit, not just technical features
5. **Be Honest** - Acknowledge what's not complete or has known issues

### 4.3 Stakeholder Involvement

#### Required Stakeholders
- Product Owner
- Scrum Master
- Development Team
- Key business representatives

#### Recommended Stakeholders
- End users or user representatives
- UX/UI designers
- Operations/IT support
- Marketing team
- Executive sponsors

#### Stakeholder Engagement Tips

1. **Pre-Review Communication** - Share agenda and expected deliverables
2. **Context Setting** - Remind stakeholders of Sprint Goal at start
3. **Active Participation** - Encourage questions and feedback
4. **Feedback Capture** - Document all input for consideration
5. **Follow-up Plan** - Explain how feedback will be processed

### 4.4 Feedback Collection

```
┌─────────────────────────────────────────────────────────────────┐
│           SPRINT REVIEW FEEDBACK FORM                          │
│           Smart Dairy Ltd. - Smart Web Portal                  │
├─────────────────────────────────────────────────────────────────┤
│ Sprint: ___________  Date: ___________  Reviewer: ___________  │
├─────────────────────────────────────────────────────────────────┤
│ FEATURE DEMONSTRATED: ________________________________________ │
│                                                                 │
│ FEEDBACK TYPE:                                                 │
│ □ Praise / Works as expected                                  │
│ □ Enhancement Request                                         │
│ □ Bug/Issue Identified                                        │
│ □ Question/Clarification Needed                               │
│ □ New Idea/Suggestion                                         │
│                                                                 │
│ DETAILED FEEDBACK:                                             │
│ ______________________________________________________________ │
│ ______________________________________________________________ │
│ ______________________________________________________________ │
│                                                                 │
│ PRIORITY (if applicable):                                      │
│ □ Critical - Must be addressed immediately                    │
│ □ High - Include in next sprint                               │
│ □ Medium - Add to backlog for prioritization                  │
│ □ Low - Nice to have, consider for future                     │
│                                                                 │
│ ACTION REQUIRED:                                               │
│ □ None - Informational only                                   │
│ □ Product Owner to follow up                                  │
│ □ Immediate team discussion                                   │
│ □ Add to Product Backlog                                      │
│                                                                 │
│ SUBMITTED TO: Product Owner                                    │
│ DATE SUBMITTED: ___________                                    │
└─────────────────────────────────────────────────────────────────┘
```

---

## 5. Sprint Retrospective

### 5.1 Purpose

The Sprint Retrospective provides an opportunity for the Scrum Team to inspect itself and create a plan for improvements to be enacted during the next Sprint. It occurs after the Sprint Review and before the next Sprint Planning.

### 5.2 Formats

#### Format 1: Start-Stop-Continue

| Category | Questions | Time |
|----------|-----------|------|
| **Start** | What should we begin doing? | 10 min |
| **Stop** | What should we stop doing? | 10 min |
| **Continue** | What should we keep doing? | 10 min |
| **Action Planning** | Prioritize and assign actions | 20 min |
| **Wrap-up** | Review commitments | 10 min |

**Best for:** Teams new to retrospectives, seeking simple structure

#### Format 2: The 4Ls (Liked, Learned, Lacked, Longed For)

| Category | Focus Area | Time |
|----------|------------|------|
| **Liked** | What went well? | 8 min |
| **Learned** | What did we discover? | 8 min |
| **Lacked** | What was missing? | 8 min |
| **Longed For** | What did we wish for? | 8 min |
| **Grouping & Voting** | Cluster similar items | 10 min |
| **Action Planning** | Create improvement actions | 18 min |

**Best for:** Mature teams, balanced positive and constructive focus

#### Format 3: Sailboat Retrospective

```
                    🌬️ WIND (What pushed us forward?)
                           ↓
                     ⛵  SAILBOAT
                    ╱    ╲
                   ╱      ╲
                  ╱   🏝️   ╲      ← ISLAND (Our goal)
                 ╱__________╲
                ╱            ╲
               ⚓              ↓
           ANCHOR         ROCKS
    (What held us back?)   (What risks do we see?)
```

| Element | Meaning | Time |
|---------|---------|------|
| Wind | What helped us move forward? | 8 min |
| Anchor | What slowed us down? | 8 min |
| Rocks | What risks/impediments ahead? | 8 min |
| Island | Sprint Goal alignment | 6 min |
| Action Planning | Address anchors and rocks | 20 min |
| Closing | Commitments | 10 min |

**Best for:** Visual learners, forward-looking focus

### 5.3 Creating Action Items

#### Action Item Template

```
┌─────────────────────────────────────────────────────────────────┐
│              RETROSPECTIVE ACTION ITEM                         │
├─────────────────────────────────────────────────────────────────┤
│ ID: RETRO-XXX                                                   │
│                                                                 │
│ DESCRIPTION:                                                    │
│ [Clear, actionable description of the improvement]              │
│                                                                 │
│ CATEGORY: □ Process  □ Technical  □ Communication              │
│           □ Tools    □ Team Dynamics  □ Other                  │
│                                                                 │
│ ORIGIN: [Related retrospective feedback item]                   │
│                                                                 │
│ OWNER: _________________________                               │
│                                                                 │
│ TARGET COMPLETION: □ Next Sprint  □ Within 30 days             │
│                    □ Ongoing      □ Specific Date: _____       │
│                                                                 │
│ SUCCESS CRITERIA:                                               │
│ [How will we know this is complete?]                            │
│ ______________________________________________________________ │
│                                                                 │
│ STATUS: □ Not Started  □ In Progress  □ Complete               │
│                                                                 │
│ NOTES: _______________________________________________________ │
└─────────────────────────────────────────────────────────────────┘
```

#### Action Item Guidelines

1. **SMART Criteria** - Specific, Measurable, Achievable, Relevant, Time-bound
2. **Single Owner** - Each item has one person accountable
3. **Limited Quantity** - Focus on 2-3 improvements per sprint
4. **Track Visibility** - Display actions in team space
5. **Follow-up** - Review progress in next retrospective

### 5.4 Psychological Safety

#### Creating a Safe Environment

| Principle | Implementation |
|-----------|----------------|
| **Vegas Rule** | "What happens in retro, stays in retro" |
| **Focus on Process** | Discuss systems, not individuals |
| **Equal Voice** | Ensure everyone participates |
| **Anonymous Input** | Use tools for sensitive topics |
| **Facilitator Neutrality** | Scrum Master remains objective |
| **Celebrate Risk-Taking** | Praise honest feedback |

#### Red Flags to Address

- Domination by senior members
- Silence from quieter team members
- Blame or personal criticism
- Skipping retrospectives when "busy"
- Same issues recurring without action

---

## 6. Backlog Refinement

### 6.1 Purpose

Backlog Refinement (formerly Grooming) is the ongoing process of reviewing and revising Product Backlog items to ensure they are ready for sprint planning. It improves the quality of items entering sprints.

### 6.2 Grooming Sessions

#### Session Structure (2 hours weekly)

| Phase | Activity | Time |
|-------|----------|------|
| Review | Quick scan of new/revised items | 15 min |
| Deep Dive | Detailed discussion of priority items | 60 min |
| Estimation | Size upcoming items | 30 min |
| DoR Check | Verify items meet Definition of Ready | 15 min |

#### Agenda Template

```
BACKLOG REFINEMENT SESSION AGENDA
Sprint: _________  Date: _________  Facilitator: _________

1. PREVIOUS ACTION REVIEW (5 min)
   □ Review items from last refinement

2. NEW ITEMS INTRODUCTION (15 min)
   □ Product Owner presents new requirements
   □ Business context and value explained

3. DETAILED DISCUSSION (60 min)
   Items to review: [List top 5-7 items]
   
   For each item:
   □ User story format validated
   □ Acceptance criteria reviewed
   □ Dependencies identified
   □ Technical approach discussed
   □ Questions clarified

4. ESTIMATION (30 min)
   □ Story points assigned
   □ Complexity factors considered
   □ Comparison to reference stories

5. DEFINITION OF READY CHECK (15 min)
   □ Items checked against DoR checklist
   □ Ready items marked for next sprint
   □ Items needing work identified

6. WRAP-UP (5 min)
   □ Action items assigned
   □ Next session scheduled
```

### 6.3 Estimation

#### Story Points Scale

| Points | Complexity | Time (Reference) | Example |
|--------|------------|------------------|---------|
| 1 | Very Simple | < 2 hours | Text change, config update |
| 2 | Simple | 2-4 hours | Simple UI component |
| 3 | Moderate | 4-8 hours | Standard API endpoint |
| 5 | Complex | 1-2 days | Feature with multiple components |
| 8 | Very Complex | 2-3 days | Cross-system integration |
| 13 | Extremely Complex | 3-5 days | Major architectural change |

**Note:** Points represent relative complexity, not time. Items larger than 13 should be split.

#### Planning Poker Process

1. **Present Item** - Product Owner reads user story
2. **Clarify Questions** - Team asks for details
3. **Individual Estimation** - Each member selects card privately
4. **Reveal Simultaneously** - All cards shown at once
5. **Discuss Discrepancies** - Highest and lowest explain reasoning
6. **Re-estimate** - Team votes again if needed
7. **Record Consensus** - Agreement reached or average taken

#### Factors for Estimation

- **Complexity** - How hard is the work?
- **Uncertainty** - How well do we understand it?
- **Effort** - How much work is involved?
- **Risk** - What could go wrong?
- **Dependencies** - On other teams or systems?

### 6.4 Definition of Ready (DoR) Compliance

#### DoR Checklist

```
┌─────────────────────────────────────────────────────────────────┐
│         DEFINITION OF READY CHECKLIST                          │
├─────────────────────────────────────────────────────────────────┤
│ ITEM: _________________________  ID: _______                   │
│                                                                 │
│ USER STORY FORMAT:                                             │
│ □ Written in "As a... I want... so that..." format            │
│ □ Clear user role identified                                   │
│ □ Desired functionality described                              │
│ □ Business value articulated                                   │
│                                                                 │
│ ACCEPTANCE CRITERIA:                                           │
│ □ Specific and testable                                        │
│ □ Covers happy path and edge cases                             │
│ □ Measurable outcomes defined                                  │
│ □ Minimum 3-5 criteria provided                                │
│                                                                 │
│ TECHNICAL CLARITY:                                             │
│ □ Technical approach understood                                │
│ □ Architecture decisions documented (if needed)                │
│ □ Integration points identified                                │
│ □ Database changes specified (if applicable)                   │
│                                                                 │
│ DEPENDENCIES:                                                  │
│ □ External dependencies identified                             │
│ □ Blockers resolved or mitigation plan in place                │
│ □ UI/UX designs available (if applicable)                      │
│ □ API specifications available (if applicable)                 │
│                                                                 │
│ SIZING:                                                        │
│ □ Story points estimated by team                               │
│ □ Item is appropriately sized (< 13 points)                    │
│ □ Can be completed within single sprint                        │
│                                                                 │
│ BUSINESS CONTEXT:                                              │
│ □ Priority established                                         │
│ □ Business value understood by team                            │
│ □ Stakeholder input incorporated                               │
│                                                                 │
│ STATUS: □ READY FOR SPRINT  □ NEEDS REFINEMENT                 │
│                                                                 │
│ ACTION ITEMS FOR READINESS:                                    │
│ ______________________________________________________________ │
│ OWNER: _____________  DUE: _____________                       │
└─────────────────────────────────────────────────────────────────┘
```

---

## 7. Ceremony Schedule

### 7.1 Weekly Calendar (2-Week Sprint)

```
┌────────────────────────────────────────────────────────────────────────┐
│              SMART DAIRY LTD. - SPRINT CALENDAR                       │
│                 Standard 2-Week Sprint Structure                       │
├────────────────────────────────────────────────────────────────────────┤
│                                                                        │
│  WEEK 1                                                                │
│  ┌──────────┬──────────┬──────────┬──────────┬──────────┐             │
│  │ MONDAY   │ TUESDAY  │WEDNESDAY │ THURSDAY │ FRIDAY   │             │
│  │          │          │          │          │          │             │
│  │ 09:00    │          │          │          │          │             │
│  │ ┌────┐   │          │          │          │          │             │
│  │ │ SP │   │          │  14:00   │          │          │             │
│  │ │ 4h │   │          │  ┌────┐  │          │          │             │
│  │ └──┬─┘   │          │  │ BR │  │          │          │             │
│  │    │     │          │  │ 2h │  │          │          │             │
│  │ 13:00   │          │  └──┬─┘  │          │          │             │
│  │ End     │          │     │    │          │          │             │
│  │         │          │  16:00  │          │          │             │
│  │         │          │  End    │          │          │             │
│  │  Daily  │  Daily   │  Daily   │  Daily   │  Daily   │             │
│  │ Standup │ Standup  │ Standup  │ Standup  │ Standup  │             │
│  │  09:30  │  09:30   │  09:30   │  09:30   │  09:30   │             │
│  │  15min  │  15min   │  15min   │  15min   │  15min   │             │
│  └─────────┴──────────┴──────────┴──────────┴──────────┘             │
│                                                                        │
│  WEEK 2                                                                │
│  ┌──────────┬──────────┬──────────┬──────────┬────────────────────┐   │
│  │ MONDAY   │ TUESDAY  │WEDNESDAY │ THURSDAY │ FRIDAY             │   │
│  │          │          │          │          │                    │   │
│  │          │          │  14:00   │          │  14:00             │   │
│  │          │          │  ┌────┐  │          │  ┌────┐            │   │
│  │          │          │  │ BR │  │          │  │ RV │            │   │
│  │          │          │  │ 2h │  │          │  │ 1h │            │   │
│  │          │          │  └──┬─┘  │          │  └──┬─┘            │   │
│  │          │          │     │    │          │     │              │   │
│  │          │          │  16:00  │          │  15:00             │   │
│  │          │          │  End    │          │  ┌────┐            │   │
│  │          │          │         │          │  │ RT │            │   │
│  │          │          │         │          │  │ 1h │            │   │
│  │          │          │         │          │  └──┬─┘            │   │
│  │          │          │         │          │     │              │   │
│  │          │          │         │          │  16:00             │   │
│  │          │          │         │          │  End               │   │
│  │  Daily  │  Daily   │  Daily   │  Daily   │  Daily             │   │
│  │ Standup │ Standup  │ Standup  │ Standup  │ Standup            │   │
│  │  09:30  │  09:30   │  09:30   │  09:30   │  09:30             │   │
│  │  15min  │  15min   │  15min   │  15min   │  15min             │   │
│  └─────────┴──────────┴──────────┴──────────┴────────────────────┘   │
│                                                                        │
│  LEGEND:                                                               │
│  ┌────┐  SP = Sprint Planning (4 hours)                               │
│  │    │  BR = Backlog Refinement (2 hours)                            │
│  └────┘  RV = Sprint Review (1 hour)                                  │
│          RT = Sprint Retrospective (1 hour)                           │
│                                                                        │
│  Sprint Day: 1      2       3       4       5       6       7       8   │
│              │      │       │       │       │       │       │       │   │
│  Sprint Day: 9      10      └───────┴───────┴───────┴───────┘         │
│              │       │                                                │
│                                                                        │
└────────────────────────────────────────────────────────────────────────┘
```

### 7.2 Time Zone Considerations (Bangladesh Team)

| Location | Local Time | UTC Offset |
|----------|------------|------------|
| Bangladesh | 09:30 BST | UTC+6 |
| Adjusted for international | 03:30 UTC | - |

**Recommendation:** Schedule ceremonies during overlapping business hours. For Smart Dairy, all ceremonies are scheduled in Bangladesh Standard Time (BST).

---

## 8. Remote Ceremony Tips - Bangladesh Team

### 8.1 Technology Requirements

| Ceremony | Primary Tool | Backup Tool | Supporting Tools |
|----------|--------------|-------------|------------------|
| Daily Standup | Google Meet / Zoom | Microsoft Teams | JIRA, Slack |
| Sprint Planning | Zoom | Google Meet | JIRA, Confluence, Miro |
| Sprint Review | Zoom | Google Meet | Demo environment, JIRA |
| Retrospective | Zoom | Google Meet | Miro, FunRetro, Mentimeter |
| Backlog Refinement | Google Meet | Zoom | JIRA, Confluence |

### 8.2 Remote-Specific Best Practices

#### General Tips

1. **Camera On Policy** - All participants enable video
2. **Mute When Not Speaking** - Reduce background noise
3. **Chat for Side Conversations** - Use chat for questions
4. **Screen Sharing** - Facilitator shares relevant tools
5. **Recording** - Record for team members who can't attend (with permission)

#### For Each Ceremony

**Daily Standup:**
- Use digital board (JIRA) visible to all
- Pass the "virtual ball" by naming next person
- Use Slack for async updates when needed

**Sprint Planning:**
- Share Confluence page with agenda
- Use screen sharing for backlog review
- Digital estimation tools (Planning Poker online)

**Sprint Review:**
- Ensure demo environment is accessible
- Have fallback video if live demo fails
- Use screen sharing with annotations

**Retrospective:**
- Digital whiteboard (Miro) for activities
- Anonymous input options for sensitive topics
- Breakout rooms for small group discussions

**Backlog Refinement:**
- Screen share JIRA for item review
- Use digital estimation tools
- Record decisions in Confluence

### 8.3 Communication Protocols

```
REMOTE MEETING ETIQUETTE - SMART DAIRY LTD.

BEFORE THE MEETING:
□ Test audio/video 5 minutes before start
□ Close unnecessary applications
□ Prepare materials in advance
□ Join from quiet location

DURING THE MEETING:
□ Mute when not speaking
□ Use "raise hand" feature for questions
□ Keep camera on during discussions
□ Use chat for technical links/notes
□ Stay focused - avoid multitasking

AFTER THE MEETING:
□ Confirm action items in chat
□ Update tickets immediately
□ Share recording link if applicable
```

### 8.4 Connectivity Contingencies

| Issue | Contingency |
|-------|-------------|
| Poor connection | Dial-in via phone; use chat for participation |
| Complete outage | Async update via Slack/email; buddy system for relay |
| Platform failure | Switch to backup tool immediately |
| Screen sharing issues | Pre-share links; use descriptive verbal updates |

---

## 9. Tools

### 9.1 JIRA

#### Purpose
Primary tool for Agile project management, issue tracking, and sprint management.

#### Key Features Used

| Feature | Use Case | Smart Dairy Configuration |
|---------|----------|---------------------------|
| Scrum Board | Sprint visualization | Columns: Backlog, To Do, In Progress, Code Review, Testing, Done |
| Sprint Management | Sprint planning and tracking | 2-week sprints, velocity tracking |
| User Stories | PBI documentation | Custom fields for acceptance criteria, DoR |
| Reporting | Burndown, velocity charts | Auto-generated reports |
| Dashboards | Team metrics overview | Sprint health, workload distribution |

#### Board Configuration

```
┌─────────────────────────────────────────────────────────────────┐
│              SMART DAIRY JIRA BOARD CONFIGURATION              │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  WORKFLOW STATES:                                               │
│                                                                 │
│  ┌─────────┐   ┌─────────┐   ┌─────────┐   ┌─────────┐         │
│  │  TO DO  │ → │   IN    │ → │  CODE   │ → │ TESTING │         │
│  │         │   │PROGRESS │   │ REVIEW  │   │         │         │
│  └─────────┘   └─────────┘   └─────────┘   └────┬────┘         │
│       ↑                                         │               │
│       └─────────────────────────────────────────┘               │
│                                                 ↓               │
│                                            ┌─────────┐          │
│                                            │  DONE   │          │
│                                            │         │          │
│                                            └─────────┘          │
│                                                                 │
│  ISSUE TYPES:                                                   │
│  • Epic - Major feature or initiative                          │
│  • Story - User-facing functionality                           │
│  • Task - Technical work item                                  │
│  • Bug - Defect to be fixed                                    │
│  • Sub-task - Breakdown of story/task                          │
│                                                                 │
│  CUSTOM FIELDS:                                                 │
│  • Acceptance Criteria (textarea)                              │
│  • Story Points (number)                                       │
│  • Sprint (sprint picker)                                      │
│  • Epic Link (epic link)                                       │
│  • DoR Checklist (checkboxes)                                  │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 9.2 Confluence

#### Purpose
Knowledge management, documentation, and team collaboration.

#### Key Spaces

| Space | Content | Owner |
|-------|---------|-------|
| Product | Requirements, user stories, roadmaps | Product Owner |
| Technical | Architecture, API docs, runbooks | Tech Lead |
| Team | Retrospectives, decisions, team norms | Scrum Master |
| Project | Status reports, meeting notes, decisions | Project Manager |

#### Templates

- Sprint Planning Template
- Retrospective Notes Template
- Decision Log Template
- Meeting Notes Template
- Technical Documentation Template

### 9.3 Miro

#### Purpose
Visual collaboration for retrospectives, planning, and workshop facilitation.

#### Usage

| Ceremony | Miro Application |
|----------|------------------|
| Sprint Planning | Story mapping, dependency visualization |
| Retrospective | Digital whiteboards for activities |
| Backlog Refinement | User story mapping |
| Team Workshops | Process mapping, brainstorming |

#### Template Library

- Start-Stop-Continue Board
- 4Ls Retrospective Board
- Sailboat Retrospective
- User Story Map
- Dependency Map

### 9.4 Supporting Tools

| Tool | Purpose |
|------|---------|
| **Slack** | Team communication, notifications, async standup |
| **Google Meet** | Video conferencing for ceremonies |
| **GitHub/GitLab** | Code repository, pull requests, code review |
| **Jenkins/GitHub Actions** | CI/CD pipeline visibility |
| **SonarQube** | Code quality metrics |

---

## 10. Metrics

### 10.1 Velocity

#### Definition
The amount of work a team can tackle during a single Sprint, measured in story points.

#### Calculation

```
Velocity = Sum of story points completed in sprint

Example:
Sprint 1: 35 points completed
Sprint 2: 42 points completed
Sprint 3: 38 points completed

Average Velocity = (35 + 42 + 38) / 3 = 38.3 ≈ 38 points
```

#### Velocity Chart

```
┌─────────────────────────────────────────────────────────────────┐
│              TEAM VELOCITY TRACKER                             │
│              Smart Dairy Development Team                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│ Points                                                          │
│   50 ┤                                        ╭──── 42         │
│   45 ┤                              ╭────────╯                 │
│   40 ┤              ╭──────────────╯        ╭──── 38           │
│   35 ┤   ╭─────────╯                        │                 │
│   30 ┤   │   28                            │                 │
│   25 ┤   │                                  │                 │
│   20 ┤   │                                  │                 │
│   15 ┤   │                                  │                 │
│   10 ┤   │                                  │                 │
│    5 ┤   │                                  │                 │
│    0 ┼───┴─────────┬─────────┬─────────┬────┴───────────────── │
│       Sprint 1    Sprint 2  Sprint 3  Sprint 4                 │
│       (28 pts)    (35 pts)  (42 pts)  (38 pts)                 │
│                                                                 │
│  Average Velocity: 35.75 points                                 │
│  Trend: ↑ Increasing (stable)                                   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

#### Using Velocity

1. **Sprint Planning** - Commit to ~80-100% of average velocity
2. **Release Forecasting** - Predict completion dates
3. **Capacity Planning** - Adjust for team changes
4. **Trend Analysis** - Identify process improvements

### 10.2 Burndown

#### Definition
A graphical representation of work left to do versus time. Shows whether the team is on track to complete committed work.

#### Types

| Type | Description | Use Case |
|------|-------------|----------|
| **Sprint Burndown** | Daily tracking of remaining work | Sprint health monitoring |
| **Release Burndown** | Tracking progress toward release | Long-term planning |
| **Epic Burndown** | Tracking progress on large feature | Feature completion |

#### Sprint Burndown Chart

```
┌─────────────────────────────────────────────────────────────────┐
│              SPRINT BURNDOWN CHART                             │
│              Sprint 4 - Smart Dairy Web Portal                 │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│ Points                                                          │
│   40 ┤● Ideal                                           ───────│
│      │ ╲                                                  │    │
│   35 ┤  ╲   ● Actual                                     │    │
│      │   ╲  │                                            │    │
│   30 ┤    ╲ │  ●                                         │    │
│      │     ╲│  │    ●                                    │    │
│   25 ┤      │  │    │    ●                               │    │
│      │      │  │    │    │    ●                          │    │
│   20 ┤      │  │    │    │    │    ●                     │    │
│      │      │  │    │    │    │    │    ●                │    │
│   15 ┤      │  │    │    │    │    │    │    ●           │    │
│      │      │  │    │    │    │    │    │    │    ●      │    │
│   10 ┤      │  │    │    │    │    │    │    │    │   ●  │    │
│      │      │  │    │    │    │    │    │    │    │   │  │    │
│    5 ┤      │  │    │    │    │    │    │    │    │   │  │    │
│      │      │  │    │    │    │    │    │    │    │   │  │    │
│    0 ┼──────┴──┴────┴────┴────┴────┴────┴────┴────┴───┴──┴────│
│       Day 1  2    3    4    5    6    7    8    9   10        │
│                                                                 │
│  Legend:  ───── Ideal    ●────● Actual                         │
│                                                                 │
│  Status: ✓ On Track (within 10% of ideal)                      │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

#### Interpreting Burndown

| Pattern | Interpretation | Action |
|---------|----------------|--------|
| Steady decline | Healthy sprint | Continue current approach |
| Flat line | Work not being completed | Investigate blockers |
| Sudden drop | Large items completed | Verify work is "Done" |
| Increasing | Scope added or estimates increased | Review with PO |
| End spike | Rush to finish | Address estimation accuracy |

### 10.3 Additional Metrics

| Metric | Formula | Target |
|--------|---------|--------|
| **Sprint Goal Success** | Sprints with met goal / Total sprints | > 80% |
| **Committed vs Completed** | Points completed / Points committed | 80-100% |
| **Cycle Time** | Time from In Progress to Done | < 5 days |
| **Lead Time** | Time from Backlog to Done | < 10 days |
| **Escaped Defects** | Bugs found post-sprint / Total bugs | < 10% |
| **Team Happiness** | Survey score (1-5) | > 3.5 |
| **Retrospective Actions** | Actions completed / Actions committed | > 70% |

---

## 11. Anti-Patterns

### 11.1 Common Mistakes

#### Daily Standup Anti-Patterns

| Anti-Pattern | Impact | Solution |
|--------------|--------|----------|
| **Reporting to SM** | Defeats self-organization purpose | Team updates team, not manager |
| **Solving problems** | Extends time, loses focus | Use "parking lot" for follow-up |
| **Skipping when busy** | Breaks rhythm, hides issues | Make non-negotiable, prioritize |
| **No blockers reported** | Issues hidden until too late | Create safe space for raising issues |
| **Discussing implementation** | Too detailed for standup | Move to technical discussion post-standup |
| **Updating board later** | Information not visible | Update in real-time during standup |

#### Sprint Planning Anti-Patterns

| Anti-Pattern | Impact | Solution |
|--------------|--------|----------|
| **No Definition of Ready** | Unclear items entering sprint | Enforce DoR checklist |
| **Committing to 100% capacity** | No buffer for surprises | Plan for 80% capacity |
| **PO dictates commitment** | Team doesn't own plan | Team pulls work, commits voluntarily |
| **No Sprint Goal** | Just a collection of tasks | Craft meaningful Sprint Goal first |
| **Skipping Part 2 (How)** | Technical debt, unclear approach | Dedicate time to task breakdown |
| **Unrealistic estimates** | Commitment not achievable | Use historical velocity |

#### Sprint Review Anti-Patterns

| Anti-Pattern | Impact | Solution |
|--------------|--------|----------|
| **PowerPoint instead of demo** | No actual working software | Show real functionality |
| **Only SM/PO present** | Missing stakeholder input | Invite all relevant stakeholders |
| **Demoing unfinished work** | Sets wrong expectations | Only demo "Done" items |
| **No stakeholder feedback** | Missed learning opportunity | Actively solicit feedback |
| **Skipping review** | No inspection of increment | Always conduct review |
| **Developer reads from script** | Not interactive | Engage audience, let them try |

#### Retrospective Anti-Patterns

| Anti-Pattern | Impact | Solution |
|--------------|--------|----------|
| **Skipping when busy** | No improvement happens | Never skip retrospectives |
| **No action items** | Nothing changes | Always create 2-3 actions |
| **Same issues every retro** | Actions not being done | Track and review actions |
| **Blaming individuals** | Psychological safety lost | Focus on systems, not people |
| **Only complaints** | Negative, demotivating | Balance with what went well |
| **Management attending** | Team not open | Closed to Scrum Team only |
| **No follow-through** | Actions forgotten | Review in next retro |

#### Backlog Refinement Anti-Patterns

| Anti-Pattern | Impact | Solution |
|--------------|--------|----------|
| **Refining during planning** | Rushed, poor quality | Dedicated refinement sessions |
| **PO works alone** | Missing technical input | Whole team involvement |
| **No estimation** | Cannot plan sprint | Always estimate refined items |
| **Stories too large** | Cannot complete in sprint | Split > 13 point stories |
| **Too many stories refined** | Wasted effort | Refine 2-3 sprints ahead only |
| **Acceptance criteria missing** | Unclear requirements | Mandatory AC for every story |

### 11.2 Warning Signs

Watch for these indicators that ceremonies need improvement:

- Team members arriving late or skipping ceremonies
- Silent participation (no questions, no input)
- Same issues recurring across retrospectives
- Velocity consistently varying by > 30%
- Stakeholder attendance declining
- Ceremony time consistently exceeding time-box
- Team expressing frustration with process

---

## 12. Appendices

### Appendix A: Standup Template

```
┌─────────────────────────────────────────────────────────────────┐
│           DAILY STANDUP - PARTICIPANT CARD                       │
│           Smart Dairy Ltd. - Sprint: ___________                │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│ NAME: _________________________  DATE: ___________              │
│                                                                 │
│ ┌─────────────────────────────────────────────────────────────┐ │
│ │  YESTERDAY (What did I complete?)                           │ │
│ │  • ________________________________________________________ │ │
│ │  • ________________________________________________________ │ │
│ │  Story/Task IDs: __________________________________________ │ │
│ └─────────────────────────────────────────────────────────────┘ │
│                                                                 │
│ ┌─────────────────────────────────────────────────────────────┐ │
│ │  TODAY (What will I work on?)                               │ │
│ │  • ________________________________________________________ │ │
│ │  • ________________________________________________________ │ │
│ │  Story/Task IDs: __________________________________________ │ │
│ │  Expected to complete: □ Yes  □ Partial  □ No                │ │
│ └─────────────────────────────────────────────────────────────┘ │
│                                                                 │
│ ┌─────────────────────────────────────────────────────────────┐ │
│ │  BLOCKERS (What's preventing progress?)                     │ │
│ │  □ No blockers                                              │ │
│ │  □ Blocker: _______________________________________________ │ │
│ │    Support needed from: ____________________________________ │ │
│ │    Urgency: □ Low  □ Medium  □ High  □ Critical             │ │
│ └─────────────────────────────────────────────────────────────┘ │
│                                                                 │
│ TIME SPOKEN: ___ minutes (Target: < 2 min)                      │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### Appendix B: Retro Formats Guide

#### Quick Reference Card

```
┌─────────────────────────────────────────────────────────────────┐
│         RETROSPECTIVE FORMATS QUICK GUIDE                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│ FORMAT          │ BEST FOR              │ TIME  │ MATERIALS   │
│ ────────────────┼───────────────────────┼───────┼─────────────│
│ Start-Stop-     │ New teams, simple     │ 60min │ Whiteboard, │
│ Continue        │ structure             │       │ stickies    │
│ ────────────────┼───────────────────────┼───────┼─────────────│
│ 4Ls             │ Balanced feedback,    │ 60min │ 4-section   │
│ (Liked, Learned,│ mature teams          │       │ board       │
│ Lacked, Longed) │                       │       │             │
│ ────────────────┼───────────────────────┼───────┼─────────────│
│ Sailboat        │ Visual learners,      │ 60min │ Sailboat    │
│                 │ forward focus         │       │ diagram     │
│ ────────────────┼───────────────────────┼───────┼─────────────│
│ Mad-Sad-Glad    │ Emotional check-in    │ 60min │ 3-section   │
│                 │                       │       │ board       │
│ ────────────────┼───────────────────────┼───────┼─────────────│
│ Timeline        │ Longer retrospectives,│ 90min │ Timeline    │
│                 │ milestone events      │       │ template    │
│ ────────────────┼───────────────────────┼───────┼─────────────│
│ Starfish        │ Broad coverage,       │ 60min │ 5-section   │
│ (Keep, Drop,    │ incremental change    │       │ star        │
│ Add, More, Less)│                       │       │             │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### Appendix C: Ceremony Checklist

#### Master Checklist

```
┌─────────────────────────────────────────────────────────────────┐
│         CEREMONY FACILITATION MASTER CHECKLIST                 │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│ ALL CEREMONIES                                                 │
│ □ Calendar invites sent with agenda                             │
│ □ Video conference link tested                                  │
│ □ Room booked (if in-person)                                    │
│ □ Required tools opened/logged in                               │
│ □ Materials prepared and shared                                 │
│ □ Timer/stopwatch available                                     │
│ □ Note-taking method prepared                                   │
│ □ Previous action items reviewed                                │
│                                                                 │
│ DAILY STANDUP SPECIFIC                                         │
│ □ Board visible (physical or digital)                           │
│ □ Parking flip chart ready                                      │
│ □ Starting prompt prepared                                      │
│                                                                 │
│ SPRINT PLANNING SPECIFIC                                       │
│ □ Product Backlog prioritized                                   │
│ □ Velocity calculated                                           │
│ □ Team capacity confirmed                                       │
│ □ Definition of Ready reviewed                                  │
│ □ Estimation materials ready                                    │
│ □ Sprint Goal template ready                                    │
│                                                                 │
│ SPRINT REVIEW SPECIFIC                                         │
│ □ Demo environment tested                                       │
│ □ Stakeholders invited and confirmed                            │
│ □ Demo script/outline prepared                                  │
│ □ Feedback collection method ready                              │
│ □ Backup plan for demo failures                                 │
│                                                                 │
│ RETROSPECTIVE SPECIFIC                                         │
│ □ Format selected and board prepared                            │
│ □ Activity materials ready                                      │
│ □ Previous retro actions reviewed                               │
│ □ Action item template ready                                    │
│ □ Psychological safety reminders prepared                       │
│                                                                 │
│ BACKLOG REFINEMENT SPECIFIC                                    │
│ □ Items to refine prioritized                                   │
│ □ DoR checklist available                                       │
│ □ Estimation tools ready                                        │
│ □ Reference stories identified                                  │
│ □ Stakeholder input collected                                   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### Appendix D: Facilitation Tips

#### General Facilitation Principles

1. **Be Neutral** - Facilitator doesn't influence content
2. **Stay on Time** - Use visible timer; start and end promptly
3. **Encourage Participation** - Draw out quiet voices
4. **Manage Dominance** - Give everyone equal airtime
5. **Park Distractions** - Capture and return to agenda
6. **Document Visibly** - Everyone sees what's being recorded
7. **Confirm Understanding** - Paraphrase and validate
8. **Drive to Decisions** - Don't leave items unresolved

#### Remote-Specific Tips

| Challenge | Solution |
|-----------|----------|
| Silent participants | Direct questions by name; use chat |
| Technical difficulties | Have backup plan; dial-in numbers |
| Time zone issues | Rotate inconvenient times fairly |
| Fatigue | Shorten ceremonies; add breaks |
| Distractions | Camera-on policy; engagement activities |

#### Time-Boxing Techniques

1. **Visible Timer** - All participants see countdown
2. **Time Keeper Role** - Assign someone to monitor
3. **Parking Lot** - Capture overflow topics
4. **Two-Minute Warning** - Signal approaching end
5. **Hard Stop** - End on time regardless

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author (Scrum Master) | | | |
| Owner (Project Manager) | | | |
| Reviewer (CTO) | | | |

---

## Related Documents

| Document ID | Title | Relationship |
|-------------|-------|--------------|
| J-001 | Project Management Plan | Parent document |
| J-002 | Communication Management Plan | Complementary |
| J-010 | Risk Management Plan | Reference |
| J-014 | Project Schedule | Complementary |

---

*Document End*

**Smart Dairy Ltd.**
**Smart Web Portal System Implementation Project**
