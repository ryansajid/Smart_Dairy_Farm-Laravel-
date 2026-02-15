# J-004: Change Request Process

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | J-004 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Project Manager |
| **Owner** | Project Manager |
| **Reviewer** | Steering Committee |
| **Classification** | Internal |
| **Status** | Approved |

### Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 31, 2026 | Project Manager | Initial version |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Change Types](#2-change-types)
3. [Change Request Form](#3-change-request-form)
4. [Impact Assessment](#4-impact-assessment)
5. [Approval Workflow](#5-approval-workflow)
6. [CAB (Change Advisory Board)](#6-cab-change-advisory-board)
7. [Change Log](#7-change-log)
8. [Communication](#8-communication)
9. [Implementation](#9-implementation)
10. [Verification](#10-verification)
11. [Emergency Changes](#11-emergency-changes)
12. [Rejected Changes](#12-rejected-changes)
13. [Metrics](#13-metrics)
14. [Appendices](#14-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the Change Request Process for the Smart Dairy Web Portal System implementation project. The purpose of this process is to:

- Ensure all changes are properly evaluated, approved, and documented
- Minimize disruption to project objectives and deliverables
- Maintain control over project scope, schedule, budget, and quality
- Provide clear accountability and traceability for all changes
- Enable informed decision-making by stakeholders

### 1.2 Scope

This change management process applies to:

- **Project Scope**: All modifications to project deliverables, features, and requirements
- **Timeline**: Adjustments to project milestones, deadlines, and schedules
- **Resources**: Changes to team composition, budget allocation, and external resources
- **Technical Specifications**: Modifications to architecture, design, technology stack, and infrastructure
- **Quality Standards**: Changes to acceptance criteria, testing protocols, and quality metrics

### 1.3 Objectives

1. Establish a standardized approach for submitting and evaluating change requests
2. Define clear roles and responsibilities for change management
3. Implement a tiered approval system based on change impact
4. Maintain comprehensive documentation of all changes
5. Ensure minimal disruption to ongoing project activities
6. Provide visibility and transparency to all stakeholders

### 1.4 Definitions

| Term | Definition |
|------|------------|
| **Change Request (CR)** | A formal proposal to modify any aspect of the project baseline |
| **Change Advisory Board (CAB)** | A group of stakeholders responsible for reviewing and approving major changes |
| **Emergency Change** | A critical change requiring immediate implementation outside normal process |
| **Impact Assessment** | Analysis of the effects a change will have on project constraints |
| **Change Log** | A record of all change requests and their disposition |

---

## 2. Change Types

### 2.1 Scope Changes

Scope changes involve modifications to the project's deliverables, features, or requirements.

#### 2.1.1 Feature Changes
- Addition of new features or functionality
- Modification of existing feature requirements
- Removal or deferral of planned features
- Enhancement of feature capabilities

#### 2.1.2 Requirement Changes
- Business requirement modifications
- User requirement updates
- Technical requirement adjustments
- Regulatory or compliance requirement changes

#### 2.1.3 Deliverable Changes
- Modifications to documented deliverables
- Changes in deliverable format or content
- Addition or removal of project deliverables

### 2.2 Schedule Changes

Schedule changes affect the project timeline and milestone dates.

#### 2.2.1 Timeline Adjustments
- Milestone date modifications
- Phase extension or compression
- Critical path changes
- Dependency modifications

#### 2.2.2 Deadline Changes
- Delivery date adjustments
- Review and approval schedule changes
- Testing window modifications
- Go-live date changes

### 2.3 Resource Changes

Resource changes involve modifications to the project team, budget, or external resources.

#### 2.3.1 Team Changes
- Team member additions or departures
- Role or responsibility modifications
- Skill requirement changes
- Resource allocation adjustments

#### 2.3.2 Budget Changes
- Cost increases or decreases
- Budget reallocation
- Funding source modifications
- Currency or exchange rate adjustments

#### 2.3.3 External Resource Changes
- Vendor or contractor modifications
- Equipment or software procurement changes
- Third-party service adjustments
- Infrastructure resource modifications

### 2.4 Technical Changes

Technical changes affect the system architecture, design, or technology stack.

#### 2.4.1 Architecture Changes
- System architecture modifications
- Integration pattern changes
- Infrastructure design updates
- Scalability approach changes

#### 2.4.2 Design Changes
- UI/UX design modifications
- Database schema changes
- API specification updates
- Security design changes

#### 2.4.3 Technology Changes
- Programming language or framework changes
- Platform or infrastructure changes
- Third-party component modifications
- Version upgrades or downgrades

---

## 3. Change Request Form

### 3.1 Change Request Submission

All change requests must be submitted using the standardized Change Request Form (Appendix A). The form must be completed in its entirety before submission.

### 3.2 Required Information

The following information is mandatory for all change requests:

#### 3.2.1 Requestor Information
| Field | Description |
|-------|-------------|
| CR Number | Auto-generated unique identifier (Format: CR-YYYY-NNNN) |
| Request Date | Date of submission |
| Requestor Name | Full name of person submitting the request |
| Department | Organizational unit of the requestor |
| Contact Information | Email and phone number |

#### 3.2.2 Change Details
| Field | Description |
|-------|-------------|
| Change Title | Brief, descriptive title |
| Change Category | Minor / Moderate / Major |
| Change Type | Scope / Schedule / Resource / Technical |
| Priority | Low / Medium / High / Critical |
| Proposed Implementation Date | Target date for change implementation |

#### 3.2.3 Description
- **Current State**: Description of the existing situation
- **Proposed Change**: Detailed description of the requested change
- **Justification**: Business rationale and expected benefits
- **Alternatives Considered**: Other options evaluated

#### 3.2.4 Supporting Documentation
- Business case or ROI analysis (for major changes)
- Technical specifications or design documents
- Risk assessment and mitigation plans
- Stakeholder impact analysis

### 3.3 Change Categories

Changes are categorized based on effort and budget impact:

| Category | Effort | Budget Impact | Approval Level |
|----------|--------|---------------|----------------|
| **Minor** | < 8 hours | None | Team Lead |
| **Moderate** | 8 - 40 hours | < 5% of budget | Project Manager |
| **Major** | > 40 hours | > 5% of budget | Steering Committee |

---

## 4. Impact Assessment

### 4.1 Assessment Responsibility

The Project Manager is responsible for conducting or coordinating the impact assessment for all change requests. Subject matter experts may be consulted as needed.

### 4.2 Impact Areas

Every change request must be evaluated for impact across the following dimensions:

#### 4.2.1 Scope Impact
| Aspect | Assessment Criteria |
|--------|---------------------|
| Deliverables | Which deliverables are affected? |
| Features | What features are impacted? |
| Requirements | Which requirements change? |
| Dependencies | What other changes are triggered? |

#### 4.2.2 Schedule Impact
| Aspect | Assessment Criteria |
|--------|---------------------|
| Timeline | Days/weeks added or saved |
| Milestones | Which milestones are affected? |
| Critical Path | Is the critical path impacted? |
| Buffer Consumption | How much schedule buffer is used? |

#### 4.2.3 Cost Impact
| Aspect | Assessment Criteria |
|--------|---------------------|
| Direct Costs | Labor, materials, software |
| Indirect Costs | Training, documentation, support |
| Budget Variance | Amount over/under budget |
| Funding Source | Where will funds come from? |

#### 4.2.4 Quality Impact
| Aspect | Assessment Criteria |
|--------|---------------------|
| Acceptance Criteria | Do criteria need updating? |
| Testing Requirements | Additional testing needed? |
| Defect Risk | Potential for introducing issues |
| User Experience | Impact on end-user satisfaction |

#### 4.2.5 Risk Impact
| Aspect | Assessment Criteria |
|--------|---------------------|
| New Risks | Risks introduced by the change |
| Modified Risks | Existing risks that change |
| Mitigation Requirements | New mitigation strategies needed |
| Residual Risk | Risk remaining after mitigation |

### 4.3 Impact Assessment Matrix

| Impact Level | Scope | Schedule | Cost | Quality | Risk |
|--------------|-------|----------|------|---------|------|
| **Low** | Single deliverable, no dependencies | < 1 week | < 2% of budget | No criteria change | Minimal new risk |
| **Medium** | Multiple deliverables, some dependencies | 1-2 weeks | 2-5% of budget | Criteria modification required | Moderate new risk |
| **High** | Major scope area, significant dependencies | > 2 weeks | > 5% of budget | Significant quality impact | High new risk |

### 4.4 Assessment Timeline

| Change Category | Assessment Duration |
|-----------------|---------------------|
| Minor | 1 business day |
| Moderate | 2-3 business days |
| Major | 5-7 business days |

---

## 5. Approval Workflow

### 5.1 Approval Levels

The approval workflow consists of three levels based on change category:

#### 5.1.1 Level 1: Team Lead Approval
**Applies to**: Minor Changes (< 8 hours, no budget impact)

| Responsibility | Action |
|---------------|--------|
| Team Lead | Review and approve minor changes within their area |
| Timeline | Approval within 1 business day |
| Documentation | Update change log upon approval |
| Escalation | Elevate to Level 2 if concerns arise |

#### 5.1.2 Level 2: Project Manager Approval
**Applies to**: Moderate Changes (8-40 hours, < 5% budget impact)

| Responsibility | Action |
|---------------|--------|
| Project Manager | Review impact assessment and approve moderate changes |
| Timeline | Approval within 2-3 business days |
| Documentation | Update change log and project baseline |
| Communication | Notify affected stakeholders |

#### 5.1.3 Level 3: Steering Committee Approval
**Applies to**: Major Changes (> 40 hours, > 5% budget impact)

| Responsibility | Action |
|---------------|--------|
| Steering Committee | Review and approve major changes with significant impact |
| Timeline | Approval within 5-7 business days |
| Documentation | Formal approval minutes and baseline updates |
| Communication | Broad stakeholder communication |

### 5.2 Approval Authority Matrix

| Change Characteristic | Team Lead | Project Manager | Steering Committee |
|----------------------|-----------|-----------------|-------------------|
| Effort < 8 hours | Approve | N/A | N/A |
| Effort 8-40 hours | Review | Approve | N/A |
| Effort > 40 hours | Review | Review | Approve |
| Budget impact < 2% | Approve | N/A | N/A |
| Budget impact 2-5% | Review | Approve | N/A |
| Budget impact > 5% | Review | Review | Approve |
| Critical path impact | Review | Review | Approve |
| Architecture change | Review | Review | Approve |

### 5.3 Approval Process Steps

1. **Submission**: Requestor completes Change Request Form
2. **Logging**: Project Manager assigns CR number and logs request
3. **Categorization**: Change is categorized (Minor/Moderate/Major)
4. **Assessment**: Impact assessment conducted
5. **Review**: Appropriate approver reviews the request
6. **Decision**: Approval, rejection, or deferral decision made
7. **Communication**: Decision communicated to requestor
8. **Documentation**: Change log updated with decision

### 5.4 Approval Decisions

| Decision | Description | Next Steps |
|----------|-------------|------------|
| **Approved** | Change is authorized for implementation | Proceed to implementation phase |
| **Approved with Conditions** | Approved with specific requirements | Implement per conditions |
| **Deferred** | Decision postponed to later date | Re-evaluate at specified date |
| **Rejected** | Change not approved | Document reason and close CR |
| **Need More Information** | Additional details required | Requestor to provide information |

---

## 6. CAB (Change Advisory Board)

### 6.1 Purpose

The Change Advisory Board (CAB) provides governance and oversight for major changes to the Smart Dairy Web Portal project. The CAB ensures that significant changes align with business objectives and project constraints.

### 6.2 CAB Composition

| Role | Responsibility | Voting Rights |
|------|----------------|---------------|
| **Chair** | Project Sponsor | Voting |
| **Vice Chair** | Project Manager | Voting |
| **Technical Lead** | Solution Architect | Voting |
| **Business Representative** | Business Analyst Lead | Voting |
| **Quality Assurance Lead** | QA Manager | Voting |
| **Finance Representative** | Finance Manager | Advisory |
| **Change Manager** | Document changes | Non-voting |

### 6.3 CAB Meeting Schedule

| Meeting Type | Frequency | Duration | Timing |
|-------------|-----------|----------|--------|
| Regular CAB Meeting | Weekly | 1 hour | Every Wednesday, 10:00 AM |
| Emergency CAB Meeting | As needed | 30 minutes | Within 24 hours of request |
| Quarterly Review | Quarterly | 2 hours | First week of quarter |

### 6.4 CAB Meeting Agenda

1. Review of pending change requests
2. Impact assessment presentations
3. Discussion and deliberation
4. Voting and decision recording
5. Review of implemented changes
6. Process improvement discussion

### 6.5 CAB Decision Making

| Decision Type | Requirement |
|---------------|-------------|
| Standard Approval | Simple majority of voting members |
| Budget Increase > 10% | Two-thirds majority required |
| Schedule Extension > 2 weeks | Two-thirds majority required |
| Scope Reduction | Simple majority |
| Emergency Changes | Chair and Vice Chair approval sufficient |

### 6.6 CAB Meeting Outputs

- Meeting minutes with attendance
- Decisions on each change request
- Action items with owners and due dates
- Updated change log
- Communication plan for approved changes

---

## 7. Change Log

### 7.1 Purpose

The Change Log maintains a comprehensive record of all change requests submitted throughout the project lifecycle. It provides traceability and historical reference for project decisions.

### 7.2 Change Log Maintenance

The Project Manager is responsible for maintaining the Change Log. All changes must be logged within 24 hours of submission.

### 7.3 Change Log Fields

| Field | Description |
|-------|-------------|
| CR Number | Unique identifier |
| Title | Brief description |
| Requestor | Name of person submitting |
| Date Submitted | Submission date |
| Category | Minor/Moderate/Major |
| Type | Scope/Schedule/Resource/Technical |
| Status | Open/Approved/Rejected/Implemented/Closed |
| Priority | Low/Medium/High/Critical |
| Impact Summary | Brief impact description |
| Approver | Name of approving authority |
| Approval Date | Date of approval |
| Implementation Date | Date change was implemented |
| Result | Success/Partial/Failed |

### 7.4 Change Log Status Values

| Status | Description |
|--------|-------------|
| **Submitted** | Initial status when CR is logged |
| **Under Review** | Being assessed by appropriate authority |
| **Approved** | Approved and awaiting implementation |
| **Rejected** | Not approved with documented reason |
| **Deferred** | Decision postponed |
| **In Progress** | Being implemented |
| **Implemented** | Change completed, pending verification |
| **Verified** | Implementation verified |
| **Closed** | Complete lifecycle finished |

### 7.5 Change Log Template

See Appendix C for the complete Change Log template.

---

## 8. Communication

### 8.1 Communication Principles

- **Timeliness**: Stakeholders informed promptly of change decisions
- **Transparency**: Clear communication of impact and rationale
- **Consistency**: Standardized communication templates
- **Completeness**: All relevant information provided

### 8.2 Communication Matrix

| Stakeholder Group | Communication | Timing | Method |
|-------------------|---------------|--------|--------|
| Requestor | Decision notification | Within 24 hours of decision | Email |
| Project Team | Approved changes | Weekly team meeting | Meeting/Email |
| Steering Committee | Major changes summary | Monthly report | Report/Meeting |
| End Users | Impact changes | Prior to implementation | Newsletter/Meeting |
| Vendors | Affected contracts | Upon approval | Formal letter |

### 8.3 Communication Templates

Standard templates are provided in Appendix D:
- Change Request Submitted Acknowledgment
- Change Approved Notification
- Change Rejected Notification
- Change Implementation Notice
- Change Complete Notification

### 8.4 Stakeholder Notification Requirements

| Change Category | Required Notifications |
|-----------------|------------------------|
| Minor | Requestor, Team Lead, Project Manager |
| Moderate | Above plus affected team members |
| Major | Above plus Steering Committee, End Users |

---

## 9. Implementation

### 9.1 Implementation Planning

Once a change is approved, an implementation plan must be developed before execution begins.

### 9.2 Implementation Plan Components

| Component | Description |
|-----------|-------------|
| Tasks | Specific activities required |
| Resources | Personnel and materials needed |
| Schedule | Timeline for implementation |
| Dependencies | Prerequisites and dependencies |
| Risks | Potential implementation risks |
| Rollback Plan | Procedure if change fails |

### 9.3 Implementation Responsibilities

| Role | Responsibility |
|------|----------------|
| Project Manager | Overall coordination and oversight |
| Team Lead | Technical implementation execution |
| QA Lead | Testing and quality verification |
| Change Requestor | User acceptance validation |

### 9.4 Implementation Tracking

- Implementation tasks tracked in project management tool
- Progress reported in daily standups (for major changes)
- Issues escalated per project issue management process
- Completion documented in change log

### 9.5 Rollback Procedures

If a change cannot be successfully implemented:

1. **Immediate Stop**: Halt implementation activities
2. **Assessment**: Evaluate impact of partial implementation
3. **Rollback Execution**: Execute predefined rollback plan
4. **Verification**: Confirm system restored to previous state
5. **Documentation**: Record lessons learned
6. **CR Update**: Update change log with outcome

---

## 10. Verification

### 10.1 Verification Purpose

Verification ensures that approved changes have been correctly implemented and achieve the intended objectives without introducing unintended consequences.

### 10.2 Verification Levels

| Level | Description | Responsibility |
|-------|-------------|----------------|
| **Technical Verification** | Code/technical implementation correct | Developer/Team Lead |
| **QA Verification** | Change meets quality standards | QA Lead |
| **User Verification** | Change meets user requirements | Requestor/End User |
| **Business Verification** | Change delivers business value | Business Analyst |

### 10.3 Verification Criteria

| Criterion | Verification Method |
|-----------|---------------------|
| Functional Correctness | Testing against requirements |
| Performance | Performance testing |
| Integration | Integration testing |
| Security | Security review |
| Documentation | Documentation review |
| Training | Training completion verification |

### 10.4 Verification Sign-off

All changes must receive sign-off before being considered complete:

| Category | Required Sign-offs |
|----------|-------------------|
| Minor | Team Lead |
| Moderate | Team Lead, QA Lead |
| Major | Team Lead, QA Lead, Project Manager, Requestor |

### 10.5 Verification Documentation

- Test results and reports
- Sign-off forms
- Updated documentation
- Updated change log status to "Verified"

---

## 11. Emergency Changes

### 11.1 Definition

An Emergency Change is a critical modification required to:
- Resolve a production system outage
- Address a critical security vulnerability
- Fix a severe defect affecting business operations
- Comply with urgent regulatory requirement

### 11.2 Emergency Change Criteria

To qualify as an emergency change, the situation must meet at least one of the following:

| Criterion | Description |
|-----------|-------------|
| System Down | Production system unavailable |
| Data Loss | Risk of significant data loss |
| Security Breach | Active security threat |
| Compliance Violation | Potential regulatory violation |
| Business Continuity | Threat to business operations |

### 11.3 Fast-Track Procedure

#### Step 1: Immediate Notification (0-1 hour)
- Notify Project Manager and Technical Lead immediately
- Provide brief description of emergency
- Indicate estimated impact and urgency

#### Step 2: Assessment (1-4 hours)
- Conduct rapid impact assessment
- Identify immediate mitigations
- Determine if emergency procedure is warranted

#### Step 3: Approval (4-8 hours)
- Emergency CAB (Chair + Vice Chair) approval
- Document rationale for emergency procedure
- Record decision in change log

#### Step 4: Implementation (8+ hours)
- Implement with enhanced monitoring
- Document all actions taken
- Maintain audit trail

#### Step 5: Retrospective (Within 1 week)
- Full retrospective review
- Complete standard documentation
- Process improvement recommendations

### 11.4 Emergency Change Documentation

Post-implementation, the following must be documented:

| Document | Timeline |
|----------|----------|
| Emergency Change Request Form | Within 24 hours |
| Impact Assessment | Within 48 hours |
| Implementation Report | Within 72 hours |
| Retrospective Report | Within 1 week |

### 11.5 Emergency Change Authority

| Role | Emergency Authority |
|------|---------------------|
| Project Sponsor | Can approve any emergency change |
| Project Manager | Can approve emergency changes < 40 hours |
| Technical Lead | Can implement emergency fixes with PM notification |

---

## 12. Rejected Changes

### 12.1 Rejection Reasons

Changes may be rejected for the following reasons:

| Reason | Description |
|--------|-------------|
| **Out of Scope** | Change not aligned with project objectives |
| **Resource Constraints** | Insufficient resources to implement |
| **Budget Constraints** | Budget not available |
| **Schedule Impact** | Unacceptable timeline impact |
| **Risk Excessive** | Risk outweighs benefit |
| **Alternative Exists** | Better solution available |
| **Insufficient Information** | Inadequate information for decision |

### 12.2 Rejected Change Handling

### 12.2.1 Documentation
- Rejection reason documented in change log
- Rejection communication sent to requestor
- Alternative solutions suggested when applicable

### 12.2.2 Appeals Process

Requestors may appeal a rejection by:

1. **Submission**: Submit appeal within 5 business days of rejection
2. **Justification**: Provide additional justification or modified proposal
3. **Review**: Higher authority reviews the appeal
4. **Decision**: Final decision communicated within 3 business days

| Original Decision | Appeal Authority |
|-------------------|------------------|
| Team Lead Rejection | Project Manager |
| Project Manager Rejection | Steering Committee |
| Steering Committee Rejection | Project Sponsor (final) |

### 12.3 Rejected Change Storage

Rejected changes are retained for:
- Historical reference
- Trend analysis
- Future proposal consideration
- Audit requirements

---

## 13. Metrics

### 13.1 Purpose

Change management metrics provide visibility into the change process effectiveness and help identify opportunities for improvement.

### 13.2 Key Metrics

#### 13.2.1 Change Volume
| Metric | Definition | Target |
|--------|------------|--------|
| Total CRs Submitted | Number of change requests received | Baseline |
| CRs by Category | Breakdown by Minor/Moderate/Major | N/A |
| CRs by Type | Breakdown by Scope/Schedule/Resource/Technical | N/A |
| CRs by Month | Trend analysis | N/A |

#### 13.2.2 Approval Metrics
| Metric | Definition | Target |
|--------|------------|--------|
| Approval Rate | (Approved CRs / Total CRs) × 100 | > 70% |
| Rejection Rate | (Rejected CRs / Total CRs) × 100 | < 20% |
| Appeal Rate | (Appeals / Rejected CRs) × 100 | < 15% |

#### 13.2.3 Cycle Time Metrics
| Metric | Definition | Target |
|--------|------------|--------|
| Minor CR Cycle Time | Submission to decision | < 2 days |
| Moderate CR Cycle Time | Submission to decision | < 5 days |
| Major CR Cycle Time | Submission to decision | < 10 days |
| Implementation Time | Approval to completion | Per estimate |

#### 13.2.4 Quality Metrics
| Metric | Definition | Target |
|--------|------------|--------|
| Rework Rate | Changes requiring rework | < 10% |
| Rollback Rate | Changes rolled back | < 5% |
| Post-Implementation Issues | Defects from changes | < 5% |

### 13.3 Metric Reporting

| Report | Frequency | Audience |
|--------|-----------|----------|
| Change Dashboard | Weekly | Project Team |
| Change Summary | Monthly | Steering Committee |
| Trend Analysis | Quarterly | Project Sponsor |

### 13.4 Metric Targets

| Category | Metric | Target | Action if Exceeded |
|----------|--------|--------|-------------------|
| Volume | Major CRs per month | < 5 | Process review |
| Efficiency | Average cycle time | Baseline -10% | Process improvement |
| Quality | Rollback rate | < 5% | Quality review |
| Satisfaction | Stakeholder feedback | > 4.0/5.0 | Satisfaction survey |

---

## 14. Appendices

### Appendix A: Change Request Form Template

```
================================================================================
                    CHANGE REQUEST FORM
================================================================================

CR Number: _______________                    Date Submitted: _______________

SECTION 1: REQUESTOR INFORMATION
--------------------------------
Name: ______________________________________
Department: ______________________________________
Email: ______________________________________
Phone: ______________________________________

SECTION 2: CHANGE CLASSIFICATION
--------------------------------
Change Category:    [ ] Minor    [ ] Moderate    [ ] Major
Change Type:        [ ] Scope    [ ] Schedule    [ ] Resource    [ ] Technical
Priority:           [ ] Low      [ ] Medium      [ ] High        [ ] Critical

Proposed Implementation Date: _______________

SECTION 3: CHANGE DESCRIPTION
-----------------------------
Change Title: _______________________________________________________________

Current State (What exists now):
_____________________________________________________________________________
_____________________________________________________________________________

Proposed Change (What is being requested):
_____________________________________________________________________________
_____________________________________________________________________________

Business Justification (Why is this needed):
_____________________________________________________________________________
_____________________________________________________________________________

Expected Benefits:
_____________________________________________________________________________
_____________________________________________________________________________

Alternatives Considered:
_____________________________________________________________________________
_____________________________________________________________________________

SECTION 4: IMPACT ASSESSMENT (To be completed by Project Manager)
-----------------------------------------------------------------
Scope Impact:       [ ] Low    [ ] Medium    [ ] High
Schedule Impact:    [ ] Low    [ ] Medium    [ ] High    Days: _______
Cost Impact:        [ ] Low    [ ] Medium    [ ] High    Amount: $_______
Quality Impact:     [ ] Low    [ ] Medium    [ ] High
Risk Impact:        [ ] Low    [ ] Medium    [ ] High

Detailed Impact Analysis:
_____________________________________________________________________________
_____________________________________________________________________________

Risk Assessment:
_____________________________________________________________________________
_____________________________________________________________________________

Mitigation Plan:
_____________________________________________________________________________
_____________________________________________________________________________

SECTION 5: APPROVAL
-------------------
Requestor Signature: _________________________ Date: _______________

APPROVAL AUTHORITY:

Team Lead:          [ ] Approved    [ ] Rejected    [ ] Needs More Info
Name: _________________________ Signature: _________________________ Date: _______

Project Manager:    [ ] Approved    [ ] Rejected    [ ] Needs More Info
Name: _________________________ Signature: _________________________ Date: _______

Steering Committee: [ ] Approved    [ ] Rejected    [ ] Needs More Info
Name: _________________________ Signature: _________________________ Date: _______

Approval Comments:
_____________________________________________________________________________
_____________________________________________________________________________

SECTION 6: IMPLEMENTATION
--------------------------
Implementation Start Date: _______________
Implementation Complete Date: _______________

Implemented By: _________________________

Verification Status: [ ] Pass    [ ] Fail    [ ] Partial

Verified By: _________________________ Date: _______________

================================================================================
```

### Appendix B: Impact Assessment Matrix

| Change Type | Scope Impact | Schedule Impact | Cost Impact | Quality Impact | Risk Level |
|-------------|--------------|-----------------|-------------|----------------|------------|
| **Scope - New Feature** | High | Medium | High | Medium | Medium |
| **Scope - Feature Modification** | Medium | Low | Medium | Medium | Low |
| **Scope - Feature Removal** | Medium | Low (saves) | Low (saves) | Low | Low |
| **Schedule - Compression** | Low | High | High | High | High |
| **Schedule - Extension** | Low | Medium | Medium | Low | Low |
| **Resource - Add Team Member** | Low | Low (saves) | Medium | Low | Low |
| **Resource - Remove Team Member** | Medium | High | Low (saves) | Medium | High |
| **Resource - Budget Increase** | Low | Low | High | Low | Low |
| **Technical - Architecture Change** | High | High | High | High | High |
| **Technical - Design Change** | Medium | Medium | Medium | Medium | Medium |
| **Technical - Version Upgrade** | Low | Low | Low | Medium | Medium |

### Appendix C: Change Log Template

```
================================================================================
                           CHANGE LOG
                    Smart Dairy Web Portal Project
================================================================================

| CR #   | Title              | Requestor   | Date    | Cat | Type  | Status  | Priority |
|--------|--------------------|-------------|---------|-----|-------|---------|----------|
|        |                    |             |         |     |       |         |          |

Legend:
  Cat: MI=Minor, MO=Moderate, MA=Major
  Type: SC=Scope, SH=Schedule, RE=Resource, TE=Technical
  Status: S=Submitted, R=Review, A=Approved, X=Rejected, D=Deferred, 
          I=In Progress, P=Implemented, V=Verified, C=Closed

================================================================================
                           DETAILED ENTRIES
================================================================================

CR Number: 
Title: 
Requestor: 
Date Submitted: 
Category: 
Type: 
Status: 
Priority: 

Description:


Impact Summary:


Approver: 
Approval Date: 
Implementation Date: 
Result: 

Comments:


================================================================================
```

### Appendix D: Communication Templates

#### D.1 Change Request Submitted Acknowledgment

```
Subject: Change Request Received - [CR Number]

Dear [Requestor Name],

This email acknowledges receipt of your Change Request:

CR Number: [CR Number]
Title: [Change Title]
Date Submitted: [Date]
Category: [Minor/Moderate/Major]

Your change request has been logged and will be reviewed according to the 
Change Request Process. You can expect:

- Initial assessment within: [Timeframe]
- Decision notification within: [Timeframe]

You will be notified of any updates to your request status.

Thank you,
Project Management Office
Smart Dairy Web Portal Project
```

#### D.2 Change Approved Notification

```
Subject: Change Request Approved - [CR Number]

Dear [Requestor Name],

We are pleased to inform you that your Change Request has been approved:

CR Number: [CR Number]
Title: [Change Title]
Approval Date: [Date]
Approved By: [Approver Name]

Implementation Details:
- Planned Start Date: [Date]
- Expected Completion: [Date]
- Assigned To: [Team/Individual]

Next Steps:
[Specific next steps]

If you have any questions, please contact the Project Manager.

Regards,
Project Management Office
Smart Dairy Web Portal Project
```

#### D.3 Change Rejected Notification

```
Subject: Change Request Decision - [CR Number]

Dear [Requestor Name],

Following review of your Change Request, we regret to inform you that it has 
not been approved at this time:

CR Number: [CR Number]
Title: [Change Title]
Decision Date: [Date]

Reason for Decision:
[Detailed explanation]

Alternative Recommendations:
[If applicable, suggest alternatives]

If you believe additional information may support reconsideration, you may 
appeal this decision within 5 business days by contacting [Contact].

Regards,
Project Management Office
Smart Dairy Web Portal Project
```

#### D.4 Change Implementation Notice

```
Subject: Upcoming Change Implementation - [CR Number]

Dear Stakeholders,

Please be advised that the following approved change will be implemented:

CR Number: [CR Number]
Title: [Change Title]
Implementation Date: [Date]
Duration: [Duration]

Impact to You:
[Description of stakeholder impact]

Actions Required:
[Any required stakeholder actions]

Support:
For questions or concerns, contact [Contact Information].

Regards,
Project Management Office
Smart Dairy Web Portal Project
```

#### D.5 Change Complete Notification

```
Subject: Change Implementation Complete - [CR Number]

Dear Stakeholders,

We are pleased to confirm that the following change has been successfully 
implemented:

CR Number: [CR Number]
Title: [Change Title]
Implementation Date: [Date]
Verification Date: [Date]
Status: [Success/Partial/Failed]

Summary:
[Brief description of what was implemented]

Next Steps:
[Any follow-up activities]

Thank you for your support during this change implementation.

Regards,
Project Management Office
Smart Dairy Web Portal Project
```

### Appendix E: Approval Authority Matrix

| Change Characteristic | Effort | Budget Impact | Critical Path | Technical Complexity | Approval Authority |
|-----------------------|--------|---------------|---------------|----------------------|-------------------|
| Minor | < 8 hrs | None | No | Low | Team Lead |
| Minor + Critical Path | < 8 hrs | None | Yes | Low | Project Manager |
| Moderate | 8-40 hrs | < 2% | No | Low-Med | Team Lead |
| Moderate + Budget | 8-40 hrs | 2-5% | No | Low-Med | Project Manager |
| Moderate + Schedule | 8-40 hrs | < 5% | Yes | Low-Med | Project Manager |
| Major | > 40 hrs | > 5% | Yes | High | Steering Committee |
| Architecture | Any | Any | Any | High | Steering Committee |
| Emergency | Any | Any | Any | Any | Emergency CAB |

### Appendix F: Change Request Workflow Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         CHANGE REQUEST WORKFLOW                              │
└─────────────────────────────────────────────────────────────────────────────┘

    ┌─────────────┐
    │   START     │
    └──────┬──────┘
           │
           ▼
    ┌─────────────────┐
    │  Submit CR Form │
    └────────┬────────┘
             │
             ▼
    ┌─────────────────┐     ┌─────────────┐
    │  Log & Assign   │────▶│  Incomplete │
    │   CR Number     │     │   Return    │
    └────────┬────────┘     └─────────────┘
             │
             ▼
    ┌─────────────────┐
    │  Categorize CR  │
    │ (Minor/Mod/Maj) │
    └────────┬────────┘
             │
     ┌───────┼───────┐
     ▼       ▼       ▼
┌────────┐ ┌────────┐ ┌────────┐
│ Minor  │ │Moderate│ │ Major  │
└───┬────┘ └───┬────┘ └───┬────┘
    │          │          │
    ▼          ▼          ▼
┌────────┐ ┌────────┐ ┌────────┐
│Impact  │ │Impact  │ │Impact  │
│Assess  │ │Assess  │ │Assess  │
│(1 day) │ │(2-3day)│ │(5-7day)│
└───┬────┘ └───┬────┘ └───┬────┘
    │          │          │
    ▼          ▼          ▼
┌────────┐ ┌────────┐ ┌────────┐
│ Team   │ │ Project│ │  CAB   │
│ Lead   │ │Manager │ │Review  │
│Review  │ │Review  │ │        │
└───┬────┘ └───┬────┘ └───┬────┘
    │          │          │
    ▼          ▼          ▼
┌────────┐ ┌────────┐ ┌────────┐
│Decision│ │Decision│ │Decision│
└───┬────┘ └───┬────┘ └───┬────┘
    │          │          │
    ▼          ▼          ▼
┌────────┐ ┌────────┐ ┌────────┐
│Approved│ │Approved│ │Approved│
└───┬────┘ └───┬────┘ └───┬────┘
    │          │          │
    ▼          ▼          ▼
┌─────────────────────────────────┐
│      IMPLEMENT CHANGE           │
└─────────────┬───────────────────┘
              │
              ▼
┌─────────────────────────────────┐
│      VERIFY IMPLEMENTATION      │
└─────────────┬───────────────────┘
              │
              ▼
┌─────────────────────────────────┐
│      UPDATE CHANGE LOG          │
└─────────────┬───────────────────┘
              │
              ▼
┌─────────────┐
│    END      │
└─────────────┘
```

### Appendix G: Emergency Change Procedure

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      EMERGENCY CHANGE PROCEDURE                              │
└─────────────────────────────────────────────────────────────────────────────┘

IMMEDIATE (0-1 Hour)
────────────────────
1. Identify emergency situation
2. Notify Project Manager & Technical Lead
3. Convene emergency assessment

ASSESSMENT (1-4 Hours)
──────────────────────
1. Confirm emergency criteria met
2. Conduct rapid impact assessment
3. Identify immediate mitigations
4. Prepare implementation approach

APPROVAL (4-8 Hours)
────────────────────
1. Emergency CAB review (Chair + Vice Chair)
2. Document approval rationale
3. Log in change tracking system
4. Authorize implementation

IMPLEMENTATION (8+ Hours)
─────────────────────────
1. Execute with enhanced monitoring
2. Document all actions
3. Maintain complete audit trail
4. Communicate status updates

POST-IMPLEMENTATION (24 Hours)
──────────────────────────────
1. Complete emergency CR form
2. Conduct technical verification
3. Update all documentation
4. Notify stakeholders

RETROSPECTIVE (1 Week)
──────────────────────
1. Full impact assessment
2. Review decision quality
3. Identify process improvements
4. Update procedures if needed
```

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Project Manager | _________________ | _______________ |
| Reviewer | Steering Committee | _________________ | _______________ |
| Approver | Project Sponsor | _________________ | _______________ |

---

*End of Document J-004: Change Request Process*

**Smart Dairy Ltd. - Smart Web Portal System Implementation**
**Document Version: 1.0 | Effective Date: January 31, 2026**
