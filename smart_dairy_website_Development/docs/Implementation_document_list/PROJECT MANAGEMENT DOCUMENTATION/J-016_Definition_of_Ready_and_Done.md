# J-016: Definition of Ready (DoR) & Definition of Done (DoD)

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | J-016 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Scrum Master |
| **Owner** | Project Manager |
| **Reviewer** | Tech Lead |
| **Status** | Approved |
| **Classification** | Internal |

---

## Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 31, 2026 | Scrum Master | Initial version - DoR & DoD establishment |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Definition of Ready (DoR)](#2-definition-of-ready-dor)
3. [Definition of Done (DoD) - Common](#3-definition-of-done-dod---common)
4. [DoD by Work Type](#4-dod-by-work-type)
5. [DoR/DoD Checklists](#5-dordod-checklists)
6. [Verification Process](#6-verification-process)
7. [Exceptions](#7-exceptions)
8. [Evolution](#8-evolution)
9. [Appendices](#9-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the **Definition of Ready (DoR)** and **Definition of Done (DoD)** for Smart Dairy Ltd's Smart Web Portal System project. These definitions serve as quality gates that ensure consistency, transparency, and predictability in our Agile development process.

### 1.2 Objectives

| Objective | Description |
|-----------|-------------|
| **Quality Assurance** | Ensure all work meets consistent quality standards |
| **Clear Communication** | Establish shared understanding among all stakeholders |
| **Predictability** | Improve sprint planning and delivery accuracy |
| **Transparency** | Make progress and completion criteria visible to all |
| **Continuous Improvement** | Provide framework for evolving standards |

### 1.3 Scope

This document applies to:
- All Scrum Teams working on the Smart Dairy Web Portal
- All work items including User Stories, Bugs, Technical Debt, Spikes, and Documentation
- All sprints throughout the project lifecycle

### 1.4 DoR vs. DoD Summary

| Aspect | Definition of Ready (DoR) | Definition of Done (DoD) |
|--------|---------------------------|--------------------------|
| **Timing** | Before sprint starts | At sprint completion |
| **Purpose** | Ensure work can be started | Ensure work is complete |
| **Focus** | Input quality | Output quality |
| **Applies to** | Backlog items entering sprint | Work items leaving sprint |
| **Question Answered** | "Can we start this?" | "Is this truly done?" |

---

## 2. Definition of Ready (DoR)

### 2.1 Overview

A Product Backlog Item (PBI) is considered **Ready** when it meets all the criteria necessary for the Development Team to commit to completing it within a Sprint.

### 2.2 DoR Criteria

#### 2.2.1 Story Clearly Defined

| Requirement | Description | Verification |
|-------------|-------------|--------------|
| Clear Title | Concise, descriptive title | Product Owner review |
| Business Context | Why this story matters | Linked to business objective |
| User Perspective | Written from user viewpoint | Follows "As a... I want... So that..." format |
| Scope Boundaries | What's in and out of scope | Explicitly documented |

#### 2.2.2 Acceptance Criteria Documented

| Requirement | Description | Example |
|-------------|-------------|---------|
| Specific Conditions | Measurable success criteria | "User can complete checkout in < 3 clicks" |
| Edge Cases | Exception handling defined | "System handles invalid payment gracefully" |
| Negative Scenarios | What should NOT happen | "Cart should not clear on page refresh" |
| Gherkin Format | Given-When-Then structure | For complex business rules |

**Acceptance Criteria Template:**
```
AC1: [Specific condition] → [Expected result]
AC2: [Specific condition] → [Expected result]
AC3: [Edge case] → [Expected handling]
```

#### 2.2.3 Dependencies Identified

| Dependency Type | Check | Resolution Required |
|-----------------|-------|---------------------|
| Technical | APIs, infrastructure, libraries | Documented and available |
| Business | Stakeholder decisions, approvals | Obtained before sprint |
| External | Third-party services, vendors | SLA confirmed |
| Team | Other squads, shared resources | Coordination complete |

#### 2.2.4 Design/Mockups Available

| Artifact | Required For | Status |
|----------|--------------|--------|
| UI Mockups | Frontend stories | Approved by UX team |
| Wireframes | Complex user flows | Reviewed with stakeholders |
| API Specifications | Backend integration | Documented in Swagger |
| Database Schema | Data model changes | Approved by Tech Lead |
| Architecture Diagram | Complex features | Reviewed in design session |

#### 2.2.5 Story Points Estimated

| Aspect | Requirement |
|--------|-------------|
| Estimation Method | Planning Poker or Team Estimation |
| Team Consensus | All developers agree on estimate |
| Reference Stories | Compared to baseline stories |
| Capacity Check | Fits within team's velocity |

**Story Point Scale:**
| Points | Complexity | Time (Reference) |
|--------|------------|------------------|
| 1 | Trivial | < 2 hours |
| 2 | Simple | Half day |
| 3 | Medium | 1 day |
| 5 | Complex | 2-3 days |
| 8 | Very Complex | Full sprint |
| 13 | Too Large | Must be broken down |

#### 2.2.6 Fits in Sprint Capacity

| Check | Criteria |
|-------|----------|
| Velocity Consideration | Within team's average velocity |
| Sprint Goal Alignment | Contributes to sprint objective |
| Capacity Planning | Individual capacity accounted for |
| Buffer Room | 20% buffer for unexpected issues |

#### 2.2.7 No Blocking Dependencies

| Blocker Category | Resolution Status |
|------------------|-------------------|
| Technical Blockers | Resolved or work-around available |
| Resource Blockers | Required personnel available |
| Environment Blockers | Dev/test environments ready |
| Approval Blockers | Necessary sign-offs obtained |

### 2.3 DoR Verification Authority

| Role | Responsibility |
|------|----------------|
| **Product Owner** | Ensures business value and acceptance criteria clarity |
| **Tech Lead** | Validates technical feasibility and design completeness |
| **Scrum Master** | Facilitates DoR review during backlog refinement |
| **Development Team** | Confirms understanding and estimates |

---

## 3. Definition of Done (DoD) - Common

### 3.1 Overview

A Product Backlog Item is considered **Done** when it meets all the criteria defined in this section. "Done" means the increment is potentially shippable and meets the quality standards agreed upon by the Scrum Team.

### 3.2 Common DoD Criteria

#### 3.2.1 Code Developed

| Requirement | Standard |
|-------------|----------|
| Functional Implementation | All acceptance criteria implemented |
| Code Quality | Follows project coding standards |
| Static Analysis | No critical/high SonarQube issues |
| Code Comments | Complex logic documented |
| Version Control | Committed to feature branch |

#### 3.2.2 Code Reviewed

| Aspect | Requirement |
|--------|-------------|
| Peer Review | Minimum 1 approval from senior developer |
| Tech Lead Review | Required for architectural changes |
| Review Checklist | All items addressed |
| Feedback Resolution | All comments resolved |
| Merge Approval | PR approved and merged to develop |

**Code Review Checklist:**
- [ ] Code follows style guidelines
- [ ] No obvious bugs or logic errors
- [ ] Proper error handling implemented
- [ ] No hardcoded values (use config)
- [ ] Security considerations addressed
- [ ] Performance implications considered

#### 3.2.3 Unit Tests Passing (>80% Coverage)

| Metric | Minimum Standard |
|--------|------------------|
| Code Coverage | ≥ 80% for new code |
| Test Execution | All tests passing |
| Test Quality | Meaningful assertions |
| Edge Cases | Critical paths covered |
| Mock Usage | External dependencies mocked |

**Coverage Requirements by Component:**
| Component | Target Coverage |
|-----------|-----------------|
| Business Logic | 90% |
| API Controllers | 85% |
| Data Access | 80% |
| UI Components | 70% (critical paths) |

#### 3.2.4 Integration Tests Passing

| Test Type | Coverage |
|-----------|----------|
| API Integration | All endpoints tested |
| Database Integration | Data operations verified |
| External Service | Mock and real integration tests |
| End-to-End (Critical) | Happy path automated |

#### 3.2.5 Documentation Updated

| Documentation Type | Update Required |
|--------------------|-----------------|
| API Documentation | Swagger/OpenAPI updated |
| README Files | Setup/usage instructions |
| Architecture Docs | Design changes documented |
| Deployment Notes | Special deployment steps |
| User Documentation | If user-facing changes |

#### 3.2.6 QA Tested and Approved

| QA Activity | Requirement |
|-------------|-------------|
| Test Case Execution | All cases executed |
| Acceptance Criteria | All criteria verified |
| Regression Testing | No new defects introduced |
| Cross-browser Testing | For UI changes (Chrome, Firefox, Safari, Edge) |
| Mobile Testing | Responsive design verified |
| Defect Resolution | All critical/high bugs fixed |

#### 3.2.7 Product Owner Accepted

| Activity | Outcome |
|----------|---------|
| Demo | Story demonstrated in staging |
| Acceptance | PO signs off on acceptance criteria |
| Feedback | Any feedback documented as new stories |
| Business Value | Confirmed delivered value |

#### 3.2.8 Deployed to Staging

| Deployment Check | Status |
|------------------|--------|
| CI/CD Pipeline | Successfully completed |
| Staging Environment | Deployed and accessible |
| Smoke Tests | Basic functionality verified |
| Configuration | Environment settings correct |
| Monitoring | Logs and metrics flowing |

---

## 4. DoD by Work Type

### 4.1 User Stories

| Criterion | Requirement | Priority |
|-----------|-------------|----------|
| Code Implementation | Feature fully functional | Mandatory |
| Unit Tests | ≥ 80% coverage | Mandatory |
| Integration Tests | API/UI integration verified | Mandatory |
| Code Review | Approved by 1+ senior dev | Mandatory |
| QA Testing | Test cases passed | Mandatory |
| PO Acceptance | Signed off by Product Owner | Mandatory |
| Documentation | User docs updated if needed | Conditional |
| UX Review | UI changes approved by UX | Conditional |
| Accessibility | WCAG 2.1 AA compliance | Mandatory for UI |
| Performance | Meets performance benchmarks | Mandatory |

### 4.2 Bugs

| Criterion | Requirement | Priority |
|-----------|-------------|----------|
| Root Cause | Identified and documented | Mandatory |
| Fix Implementation | Bug resolved | Mandatory |
| Regression Test | Test added to prevent recurrence | Mandatory |
| Verification | QA confirms fix in staging | Mandatory |
| Affected Areas | Related functionality tested | Mandatory |
| Code Review | Approved by peer | Mandatory |
| Documentation | Known issues updated | Conditional |

**Bug Severity DoD Variations:**
| Severity | DoD Variations |
|----------|----------------|
| Critical | Hotfix process, immediate deployment |
| High | Standard DoD, next release |
| Medium | Standard DoD |
| Low | May skip some non-critical tests |

### 4.3 Technical Debt

| Criterion | Requirement | Priority |
|-----------|-------------|----------|
| Refactoring | Code improved as planned | Mandatory |
| Test Coverage | Coverage maintained or improved | Mandatory |
| Regression Testing | Existing functionality intact | Mandatory |
| Metrics | Improvement measurable (SonarQube, etc.) | Mandatory |
| Documentation | Technical debt log updated | Mandatory |
| Code Review | Approved by Tech Lead | Mandatory |

### 4.4 Spikes

| Criterion | Requirement | Priority |
|-----------|-------------|----------|
| Research | Investigation completed | Mandatory |
| Findings Documented | Results captured in wiki/docs | Mandatory |
| Recommendation | Clear next steps identified | Mandatory |
| Prototype | Proof of concept if applicable | Conditional |
| Time Box | Completed within allocated time | Mandatory |
| Review | Findings reviewed with team | Mandatory |

### 4.5 Documentation

| Criterion | Requirement | Priority |
|-----------|-------------|----------|
| Content Complete | All sections written | Mandatory |
| Technical Review | Accuracy verified by SMEs | Mandatory |
| Editorial Review | Grammar/style checked | Mandatory |
| Formatting | Consistent with templates | Mandatory |
| Publication | Published to appropriate location | Mandatory |
| Accessibility | Documents are accessible | Mandatory |

### 4.6 DoD by Work Type Matrix

| Criteria | User Story | Bug | Tech Debt | Spike | Documentation |
|----------|------------|-----|-----------|-------|---------------|
| Code Implemented | ✅ | ✅ | ✅ | ⚪ | ⚪ |
| Unit Tests (>80%) | ✅ | ✅ | ✅ | ⚪ | ⚪ |
| Integration Tests | ✅ | ✅ | ✅ | ⚪ | ⚪ |
| Code Reviewed | ✅ | ✅ | ✅ | ⚪ | ✅ |
| QA Tested | ✅ | ✅ | ✅ | ⚪ | ⚪ |
| PO Accepted | ✅ | ✅ | ✅ | ✅ | ✅ |
| Documentation Updated | ✅ | ⚪ | ✅ | ✅ | N/A |
| Deployed to Staging | ✅ | ✅ | ✅ | ⚪ | ⚪ |
| Root Cause Doc | ⚪ | ✅ | ⚪ | ✅ | ⚪ |
| Findings Documented | ⚪ | ⚪ | ⚪ | ✅ | ✅ |

**Legend:** ✅ = Required | ⚪ = Optional/Conditional | ❌ = Not Applicable

---

## 5. DoR/DoD Checklists

### 5.1 Printable DoR Checklist (User Story)

```
╔══════════════════════════════════════════════════════════════════════════════╗
║                    DEFINITION OF READY CHECKLIST                             ║
║                         User Story - J-016                                   ║
╠══════════════════════════════════════════════════════════════════════════════╣
║ Story ID: _________________  Sprint: _________________  Date: ______________ ║
╠══════════════════════════════════════════════════════════════════════════════╣
║                                                                              ║
║  □ 1. BUSINESS VALUE CLEAR                                                   ║
║     • Business objective documented                                          ║
║     • Success metrics defined                                                ║
║     • Stakeholder value explained                                            ║
║                                                                              ║
║  □ 2. ACCEPTANCE CRITERIA DEFINED                                            ║
║     • All success criteria documented                                        ║
║     • Edge cases identified                                                  ║
║     • Negative scenarios covered                                             ║
║     • Given-When-Then format used (if complex)                               ║
║                                                                              ║
║  □ 3. UI/UX DESIGNS ATTACHED                                                 ║
║     • Mockups/wireframes available                                           ║
║     • Responsive design considered                                           ║
║     • Accessibility requirements noted                                       ║
║     • UX team approval obtained                                              ║
║                                                                              ║
║  □ 4. DEPENDENCIES RESOLVED                                                  ║
║     • Technical dependencies identified and available                        ║
║     • External dependencies confirmed                                        ║
║     • No blocking issues remaining                                           ║
║     • Cross-team coordination complete (if applicable)                       ║
║                                                                              ║
║  □ 5. STORY POINTS ESTIMATED                                                 ║
║     • Team estimation completed                                              ║
║     • Consensus reached                                                      ║
║     • Compared to reference stories                                          ║
║     • Estimate recorded in tracking tool                                     ║
║                                                                              ║
║  □ 6. SMALL ENOUGH FOR SPRINT                                                ║
║     • Can complete within single sprint                                      ║
║     • ≤ 8 story points                                                       ║
║     • Fits within team velocity                                              ║
║     • Buffer for unexpected issues                                           ║
║                                                                              ║
╠══════════════════════════════════════════════════════════════════════════════╣
║ VERIFICATION SIGNATURES                                                      ║
║                                                                              ║
║ Product Owner: _________________________ Date: _________________             ║
║ Tech Lead: _____________________________ Date: _________________             ║
║ Scrum Master: __________________________ Date: _________________             ║
╚══════════════════════════════════════════════════════════════════════════════╝
```

### 5.2 Printable DoD Checklist (User Story)

```
╔══════════════════════════════════════════════════════════════════════════════╗
║                    DEFINITION OF DONE CHECKLIST                              ║
║                         User Story - J-016                                   ║
╠══════════════════════════════════════════════════════════════════════════════╣
║ Story ID: _________________  Sprint: _________________  Date: ______________ ║
╠══════════════════════════════════════════════════════════════════════════════╣
║                                                                              ║
║  □ 1. CODE IMPLEMENTED                                                       ║
║     • All acceptance criteria met                                            ║
║     • Coding standards followed                                              ║
║     • Static analysis passed (SonarQube)                                     ║
║     • No critical/high issues                                                ║
║                                                                              ║
║  □ 2. UNIT TESTS (>80% COVERAGE)                                             ║
║     • Tests written and passing                                              ║
║     • Coverage ≥ 80% for new code                                            ║
║     • Edge cases covered                                                     ║
║     • CI/CD pipeline green                                                   ║
║                                                                              ║
║  □ 3. CODE REVIEWED                                                          ║
║     • Pull request created                                                   ║
║     • Peer review completed                                                  ║
║     • All feedback addressed                                                 ║
║     • Merged to develop branch                                               ║
║                                                                              ║
║  □ 4. QA TESTED                                                              ║
║     • Test cases executed                                                    ║
║     • Acceptance criteria verified                                           ║
║     • Regression testing passed                                              ║
║     • No critical/high defects open                                          ║
║     • Cross-browser/mobile tested (UI)                                       ║
║                                                                              ║
║  □ 5. PO ACCEPTED                                                            ║
║     • Story demo completed                                                   ║
║     • Acceptance criteria signed off                                         ║
║     • Business value confirmed                                               ║
║                                                                              ║
║  □ 6. DOCUMENTATION UPDATED                                                  ║
║     • API documentation updated (if applicable)                              ║
║     • README updated (if needed)                                             ║
║     • User documentation updated (if applicable)                             ║
║     • Architecture docs updated (if applicable)                              ║
║                                                                              ║
║  □ 7. DEPLOYED TO STAGING                                                    ║
║     • CI/CD deployment successful                                            ║
║     • Smoke tests passed                                                     ║
║     • Environment configuration verified                                     ║
║     • Monitoring confirmed                                                   ║
║                                                                              ║
╠══════════════════════════════════════════════════════════════════════════════╣
║ VERIFICATION SIGNATURES                                                      ║
║                                                                              ║
║ Developer: _____________________________ Date: _________________             ║
║ Code Reviewer: _________________________ Date: _________________             ║
║ QA Engineer: __________________________ Date: _________________             ║
║ Product Owner: _________________________ Date: _________________             ║
╚══════════════════════════════════════════════════════════════════════════════╝
```

### 5.3 Quality Gate Checklist

```
╔══════════════════════════════════════════════════════════════════════════════╗
║                         QUALITY GATE CHECKLIST                               ║
║                              J-016                                           ║
╠══════════════════════════════════════════════════════════════════════════════╣
║ Sprint: _________________  Release: _________________  Date: ______________  ║
╠══════════════════════════════════════════════════════════════════════════════╣
║                                                                              ║
║  STATIC ANALYSIS                                                             ║
║  □ SonarQube Quality Gate passed                                             ║
║  □ No new critical/blocker issues                                            ║
║  □ Code coverage maintained (≥ 80%)                                          ║
║  □ Technical debt within acceptable limits                                   ║
║                                                                              ║
║  TESTING                                                                     ║
║  □ All unit tests passing                                                    ║
║  □ Integration tests passing                                                 ║
║  □ No critical test failures                                                 ║
║  □ Performance benchmarks met                                                ║
║                                                                              ║
║  SECURITY                                                                    ║
║  □ Security scan passed (Snyk/Sonar)                                         ║
║  □ No high/critical vulnerabilities                                          ║
║  □ Dependencies up to date                                                   ║
║  □ Sensitive data properly handled                                           ║
║                                                                              ║
║  DEPLOYMENT                                                                  ║
║  □ Staging deployment successful                                             ║
║  □ Smoke tests passed                                                        ║
║  □ Rollback plan documented (if needed)                                      ║
║  □ Monitoring and alerts configured                                          ║
║                                                                              ║
║  DOCUMENTATION                                                               ║
║  □ Release notes prepared                                                    ║
║  □ User-facing changes documented                                            ║
║  □ API changes documented                                                    ║
║  □ Known issues list updated                                                 ║
║                                                                              ║
║  APPROVALS                                                                   ║
║  □ Tech Lead approval                                                        ║
║  □ QA sign-off                                                               ║
║  □ Product Owner approval                                                    ║
║  □ Security review (if applicable)                                           ║
║                                                                              ║
╠══════════════════════════════════════════════════════════════════════════════╣
║ GATE STATUS: ☐ PASSED  ☐ FAILED  ☐ CONDITIONAL                              ║
║                                                                              ║
║ Final Approver: ___________________________ Date: _________________          ║
╚══════════════════════════════════════════════════════════════════════════════╝
```

---

## 6. Verification Process

### 6.1 DoR Verification

| Phase | Activity | Responsible | Timing |
|-------|----------|-------------|--------|
| **Backlog Refinement** | Initial DoR assessment | Scrum Master | Weekly |
| **Sprint Planning** | Final DoR check | Team | Sprint start |
| **Pre-Commitment** | Team readiness confirmation | Dev Team | Before sprint commitment |

### 6.2 DoD Verification

| Phase | Activity | Responsible | Timing |
|-------|----------|-------------|--------|
| **Development** | Self-check by developer | Developer | During development |
| **Code Review** | Peer verification | Reviewer | PR review |
| **Testing** | QA verification | QA Engineer | Post-development |
| **Pre-Demo** | Team internal review | Scrum Master | Before sprint review |
| **Sprint Review** | PO acceptance | Product Owner | Sprint review |
| **Retro** | DoD compliance review | Scrum Master | Sprint retrospective |

### 6.3 Verification Authority Matrix

| Criteria | Developer | Tech Lead | QA | PO | Scrum Master |
|----------|-----------|-----------|-----|-----|--------------|
| Code Implemented | ✅ | ⚪ | ⚪ | ⚪ | ⚪ |
| Unit Tests | ✅ | ✅ | ⚪ | ⚪ | ⚪ |
| Code Review | ✅ | ✅ | ⚪ | ⚪ | ⚪ |
| Integration Tests | ⚪ | ✅ | ✅ | ⚪ | ⚪ |
| QA Testing | ⚪ | ⚪ | ✅ | ⚪ | ⚪ |
| Documentation | ✅ | ✅ | ⚪ | ⚪ | ⚪ |
| PO Acceptance | ⚪ | ⚪ | ⚪ | ✅ | ⚪ |
| Deployed to Staging | ✅ | ✅ | ✅ | ⚪ | ⚪ |
| Final Sign-off | ⚪ | ✅ | ✅ | ✅ | ✅ |

**Legend:** ✅ = Primary authority | ⚪ = Not responsible

### 6.4 Escalation Path

| Issue | Escalate To | Resolution Time |
|-------|-------------|-----------------|
| DoR not met at planning | Product Owner + Tech Lead | Before sprint commitment |
| DoD dispute | Scrum Master facilitation | Within 24 hours |
| Quality gate failure | Tech Lead + Project Manager | Before release |
| Scope creep during sprint | Product Owner + Scrum Master | Within 4 hours |

---

## 7. Exceptions

### 7.1 When Rules Can Be Bent

Exceptions to DoR/DoD may be granted under specific circumstances with proper authorization.

### 7.2 Exception Categories

| Category | Conditions | Authorization Required |
|----------|------------|------------------------|
| **Time-Critical** | Customer-impacting production issue | Project Manager + Tech Lead |
| **Dependency-Delayed** | External dependency causing sprint risk | Scrum Master + Product Owner |
| **Prototype/Spike** | Research/investigation work | Tech Lead |
| **Documentation Deferral** | Non-user-facing changes | Tech Lead + Product Owner |
| **Coverage Exception** | Legacy code difficult to test | Tech Lead |

### 7.3 Exception Process

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        EXCEPTION REQUEST WORKFLOW                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│   ┌──────────────┐    ┌──────────────┐    ┌──────────────┐                 │
│   │   Identify   │───▶│   Document   │───▶│   Request    │                 │
│   │    Need      │    │   Justify    │    │   Approval   │                 │
│   └──────────────┘    └──────────────┘    └──────────────┘                 │
│                                                  │                          │
│                                                  ▼                          │
│   ┌──────────────┐    ┌──────────────┐    ┌──────────────┐                 │
│   │   Implement  │◀───│   Record in  │◀───│   Decision   │                 │
│   │  with Risk   │    │   Sprint     │    │  (Approve/   │                 │
│   │   Mitigation │    │   Notes      │    │   Deny)      │                 │
│   └──────────────┘    └──────────────┘    └──────────────┘                 │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 7.4 Exception Documentation

| Field | Required Information |
|-------|---------------------|
| Exception ID | EX-XXX format |
| Date | When exception requested |
| Requester | Team member seeking exception |
| Work Item | Story/Bug ID |
| Criteria | Which DoR/DoD criteria affected |
| Justification | Business/technical reason |
| Risk Assessment | Impact of exception |
| Mitigation | How risk will be managed |
| Approver | Who authorized the exception |
| Expiration | When exception expires |

### 7.5 Non-Negotiable Items

The following items **CANNOT** be excepted:

| Item | Reason |
|------|--------|
| Security vulnerabilities (Critical/High) | Legal and compliance risk |
| Data privacy compliance | GDPR, industry regulations |
| Production deployment approval | Stability risk |
| Code review for external-facing features | Reputation risk |
| Regression testing for critical paths | Business continuity |

---

## 8. Evolution

### 8.1 Review Schedule

| Review Type | Frequency | Participants |
|-------------|-----------|--------------|
| Informal Feedback | Continuous | Team members |
| Sprint Retrospective | Bi-weekly | Scrum Team |
| Formal Review | Monthly | Extended team |
| Major Revision | Quarterly | All stakeholders |

### 8.2 Change Process

| Step | Activity | Owner |
|------|----------|-------|
| 1 | Proposal drafted | Any team member |
| 2 | Team discussion | Scrum Master |
| 3 | Impact assessment | Tech Lead + PO |
| 4 | Decision | Team consensus |
| 5 | Documentation update | Scrum Master |
| 6 | Communication | Scrum Master |
| 7 | Retrospective review | Whole team |

### 8.3 Version Control

| Version | Date | Changes | Approved By |
|---------|------|---------|-------------|
| 1.0 | January 31, 2026 | Initial version | Project Steering Committee |

### 8.4 Improvement Triggers

| Indicator | Action |
|-----------|--------|
| Consistent DoR failures | Refine backlog refinement process |
| High carry-over rate | Review estimation and DoR criteria |
| Quality issues in production | Strengthen DoD requirements |
| Team feedback | Consider adjustments |
| Process changes | Update to align with new practices |
| Tool changes | Update automation requirements |

---

## 9. Appendices

### Appendix A: DoR Checklist Template

```markdown
## DoR Checklist - [Story ID]: [Story Title]

**Sprint:** _________  **Date:** _________  **Team:** _________

### Essential Criteria
- [ ] Business value is clear and documented
- [ ] Acceptance criteria are defined and testable
- [ ] UI/UX designs are attached and approved
- [ ] All dependencies are identified and resolved
- [ ] Story points are estimated by the team
- [ ] Story is small enough for the sprint

### Additional Checks
- [ ] Technical approach discussed
- [ ] Performance requirements defined
- [ ] Security considerations reviewed
- [ ] Accessibility requirements noted

### Sign-offs
| Role | Name | Signature | Date |
|------|------|-----------|------|
| Product Owner | | | |
| Tech Lead | | | |
| Scrum Master | | | |

**Ready for Sprint:** ☐ Yes  ☐ No
```

### Appendix B: DoD Checklist Template

```markdown
## DoD Checklist - [Story ID]: [Story Title]

**Sprint:** _________  **Completed Date:** _________  **Developer:** _________

### Development
- [ ] Code implemented per acceptance criteria
- [ ] Unit tests written with >80% coverage
- [ ] Code reviewed and approved
- [ ] Integration tests passing

### Quality Assurance
- [ ] QA testing completed
- [ ] Acceptance criteria verified
- [ ] Regression testing passed
- [ ] Cross-browser/mobile testing (if UI)

### Documentation & Deployment
- [ ] Documentation updated
- [ ] PO acceptance obtained
- [ ] Deployed to staging
- [ ] Smoke tests passed

### Sign-offs
| Role | Name | Signature | Date |
|------|------|-----------|------|
| Developer | | | |
| Code Reviewer | | | |
| QA Engineer | | | |
| Product Owner | | | |

**Status:** ☐ Done  ☐ Not Done
```

### Appendix C: DoD by Work Type Matrix (Detailed)

| # | Criteria | Weight | User Story | Bug | Tech Debt | Spike | Docs |
|---|----------|--------|------------|-----|-----------|-------|------|
| 1 | Code implemented | 20% | ✅ | ✅ | ✅ | ⚪ | ❌ |
| 2 | Unit tests (>80%) | 15% | ✅ | ✅ | ✅ | ❌ | ❌ |
| 3 | Integration tests | 10% | ✅ | ✅ | ✅ | ⚪ | ❌ |
| 4 | Code reviewed | 10% | ✅ | ✅ | ✅ | ⚪ | ✅ |
| 5 | QA tested | 15% | ✅ | ✅ | ✅ | ⚪ | ❌ |
| 6 | Documentation updated | 10% | ✅ | ⚪ | ✅ | ✅ | N/A |
| 7 | PO accepted | 10% | ✅ | ✅ | ✅ | ✅ | ✅ |
| 8 | Deployed to staging | 10% | ✅ | ✅ | ✅ | ⚪ | ⚪ |

**Scoring:**
- All required criteria met = 100%
- Missing 1 required criteria = 75%
- Missing 2 required criteria = 50%
- Missing 3+ required criteria = Not Done

### Appendix D: Quality Metrics Dashboard

| Metric | Target | Warning | Critical |
|--------|--------|---------|----------|
| DoR Compliance | ≥ 95% | 90-94% | < 90% |
| DoD Compliance | ≥ 95% | 90-94% | < 90% |
| Code Coverage | ≥ 80% | 75-79% | < 75% |
| Sprint Carry-over | ≤ 10% | 11-20% | > 20% |
| Defect Escape Rate | ≤ 5% | 6-10% | > 10% |
| Review Cycle Time | ≤ 1 day | 2-3 days | > 3 days |

### Appendix E: Glossary

| Term | Definition |
|------|------------|
| **Definition of Ready (DoR)** | Criteria that must be met before a backlog item can be accepted into a sprint |
| **Definition of Done (DoD)** | Criteria that must be met for a backlog item to be considered complete |
| **Acceptance Criteria** | Specific conditions that must be met for a story to be accepted |
| **Story Points** | Relative estimate of effort required to complete a story |
| **Sprint** | Time-boxed iteration (typically 2 weeks) |
| **Velocity** | Amount of work a team can complete in a sprint |
| **Technical Debt** | Code or design that prioritizes short-term delivery over long-term quality |
| **Spike** | Time-boxed research or investigation activity |
| **Quality Gate** | Checkpoint that must be passed before proceeding |
| **Regression Testing** | Testing to ensure changes haven't broken existing functionality |

### Appendix F: Reference Documents

| Document ID | Title | Relationship |
|-------------|-------|--------------|
| J-001 | Project Charter | Parent document |
| J-002 | Project Management Plan | Process alignment |
| J-011 | Change Management Plan | Change process alignment |
| J-015 | Test Strategy | Testing criteria reference |
| J-017 | Sprint Planning Guidelines | Planning process details |

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author (Scrum Master) | | | January 31, 2026 |
| Owner (Project Manager) | | | |
| Reviewer (Tech Lead) | | | |
| Approver (Delivery Manager) | | | |

---

*End of Document J-016: Definition of Ready (DoR) & Definition of Done (DoD)*

---

**Smart Dairy Ltd**  
**Smart Web Portal System Project**  
**Document Classification: Internal**
