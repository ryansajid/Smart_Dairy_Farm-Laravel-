# AI AGENT SKILL: IMPLEMENTATION ROADMAP DOCUMENT WRITER
## Instruction Set for Generating Phase-by-Phase Implementation Roadmaps

---

**Document ID:** SD-SKILL-ROADMAP-001
**Version:** 1.0
**Purpose:** Instruct AI agents to write comprehensive, actionable implementation roadmap documents
**Target Output:** Phase-by-phase implementation roadmap documents with clear deliverables, timelines, and dependencies

---

## 1. CORE INSTRUCTIONS

### 1.1 Document Structure Requirements

**MANDATORY SECTIONS** - Include all sections in this order:
1. Executive Summary (1-2 pages)
2. Implementation Approach & Methodology
3. Phase-by-Phase Breakdown (detailed)
4. Resource Allocation & Team Structure
5. Timeline & Milestones (visual + tabular)
6. Risk Assessment & Mitigation
7. Success Criteria & KPIs
8. Dependencies & Prerequisites
9. Go-Live Checklist per Phase
10. Appendices (templates, references)

### 1.2 Writing Standards

**MUST FOLLOW:**
- Use active voice and imperative mood
- Write in present/future tense
- Be specific: use numbers, dates, names
- Avoid ambiguity: "configure database" → "configure PostgreSQL 15 with TimescaleDB extension"
- Include acceptance criteria for every deliverable
- Provide visual diagrams (ASCII or description for Gantt charts, flowcharts)

**FORBIDDEN:**
- Vague language ("approximately", "around", "maybe")
- Passive constructions
- Generic placeholders without context
- Assumptions without documentation
- Missing prerequisites or dependencies

---

## 2. PHASE BREAKDOWN FORMULA

### 2.1 Phase Structure Template

For **EACH PHASE**, structure as follows:

```
## PHASE [NUMBER]: [PHASE NAME] (Month X-Y)

### Phase Objective
[Single paragraph: What will be achieved and why it matters]

### Success Criteria
- [ ] Criterion 1 (measurable)
- [ ] Criterion 2 (measurable)
- [ ] Criterion 3 (measurable)

### Deliverables Matrix

| ID | Deliverable | Owner | Reviewer | Due | Dependencies | Acceptance Criteria |
|----|-------------|-------|----------|-----|--------------|---------------------|
| X.1 | [Name] | [Role] | [Role] | Week N | [IDs] | [Specific, testable] |

### Weekly Timeline

| Week | Activities | Deliverables | Milestones | Blockers/Risks |
|------|-----------|--------------|------------|----------------|

### Resource Requirements

| Role | Allocation | Duration | Skills Required |
|------|------------|----------|-----------------|

### Testing & Validation

| Test Type | Scope | Duration | Success Rate |
|-----------|-------|----------|--------------|

### Phase Exit Criteria
[List of gate review requirements before moving to next phase]
```

### 2.2 Deliverable Specification Rules

**Each deliverable MUST include:**
- Unique ID (format: Phase.Sequence, e.g., 1.3, 2.5)
- Precise name (not "setup system" but "configure Odoo 19 CE with 8 core modules")
- Assigned owner (specific role, not "team")
- Designated reviewer (must be different from owner)
- Target completion date (Week number from project start)
- List of prerequisite deliverables (IDs)
- Measurable acceptance criteria (3-5 specific tests)

**Example - GOOD:**
```
ID: 1.3
Deliverable: PostgreSQL 15 database cluster with TimescaleDB extension and replication
Owner: Database Administrator
Reviewer: Solution Architect
Due: Week 3
Dependencies: 1.1 (Infrastructure provisioned), 1.2 (Network configuration)
Acceptance Criteria:
- Primary and standby nodes operational with <1s replication lag
- TimescaleDB extension installed and tested with sample time-series data
- Backup automated to run daily at 2 AM with 7-day retention
- Connection pooling configured (PgBouncer with max 100 connections)
- Performance baseline documented (>1000 TPS on standard workload)
```

**Example - BAD:**
```
ID: 1.3
Deliverable: Setup database
Owner: Team
Due: Week 3
Dependencies: Infrastructure
Acceptance Criteria: Database works
```

---

## 3. CONTEXT ANALYSIS PROTOCOL

### 3.1 Pre-Writing Analysis

**BEFORE writing, analyze these inputs:**

1. **Business Requirements Document (BRD)**
   - Extract: Business objectives, functional requirements, constraints
   - Identify: Critical success factors, stakeholder priorities

2. **Technical Architecture Document**
   - Extract: Technology stack, integration points, infrastructure needs
   - Identify: Technical dependencies, architectural constraints

3. **Resource Availability**
   - Extract: Team size, skill sets, availability constraints
   - Calculate: Realistic effort estimates

4. **Existing Codebase/Systems**
   - Identify: Migration requirements, integration points, legacy constraints

5. **Project Constraints**
   - Timeline: Fixed dates, market windows
   - Budget: Financial limits, resource caps
   - Compliance: Regulatory requirements, standards

### 3.2 Phase Sequencing Logic

**Apply these rules to determine phase order:**

1. **Foundation-First Principle:**
   - Infrastructure before applications
   - Core systems before extensions
   - Backend before frontend
   - Single-tenant before multi-tenant

2. **Risk-Based Sequencing:**
   - High-risk/high-uncertainty items in early phases
   - Critical path items get priority
   - Integration points addressed before dependent features

3. **Value-Based Sequencing:**
   - Quick wins in Phase 1 for stakeholder confidence
   - Revenue-generating features prioritized
   - MVP features before nice-to-haves

4. **Dependency-Driven Sequencing:**
   - Create dependency graph
   - Identify critical path
   - Schedule parallel work where possible

---

## 4. TIMELINE GENERATION RULES

### 4.1 Effort Estimation Guidelines

**Apply these multipliers to base estimates:**

| Complexity | Base Estimate Multiplier | Buffer |
|------------|-------------------------|--------|
| Simple (CRUD, standard config) | 1.0x | 20% |
| Moderate (custom logic, integration) | 1.5x | 30% |
| Complex (algorithmic, ML, IoT) | 2.0x | 40% |
| Novel (new tech, R&D) | 2.5x | 50% |

**Account for:**
- Testing time: 30-40% of development time
- Documentation: 10-15% of development time
- Rework/bugs: 20% of development time
- Integration time: 25% of total when multiple systems involved

### 4.2 Milestone Definition

**Define milestones that are:**
- **Observable:** Can see/verify completion
- **Binary:** Complete or not complete (no partial)
- **Time-bound:** Specific date, not range
- **Significant:** Represents meaningful progress
- **Dependent:** Blocks or enables subsequent work

**Milestone naming convention:**
`[PHASE]-[TYPE]-[COMPONENT]`

Examples:
- P1-GO-LIVE-FOUNDATION
- P2-INTEGRATION-PAYMENT-GATEWAY
- P3-UAT-B2B-PORTAL
- P4-CUTOVER-PRODUCTION

---

## 5. RISK & DEPENDENCY MAPPING

### 5.1 Risk Documentation Format

For each phase, identify 5-10 risks using this structure:

```
Risk ID: R-[Phase].[Number]
Risk: [Concise description of what could go wrong]
Probability: [High/Medium/Low]
Impact: [High/Medium/Low]
Impact Description: [Specific consequences if risk materializes]
Mitigation Strategy: [Specific actions to prevent or reduce]
Contingency Plan: [What to do if risk occurs despite mitigation]
Owner: [Role responsible for monitoring and response]
Status: [Open/Monitoring/Closed]
```

### 5.2 Dependency Types

**Identify and document:**

1. **Technical Dependencies:**
   - "Module X requires API Y to be deployed"
   - Document: Prerequisite, version requirements, configuration needs

2. **Resource Dependencies:**
   - "Feature A requires specialist B available"
   - Document: Resource name/role, availability window, alternatives

3. **Data Dependencies:**
   - "Testing requires production data dump"
   - Document: Data source, volume, masking requirements, refresh frequency

4. **Approval Dependencies:**
   - "Phase 2 go-live requires steering committee approval"
   - Document: Approver, criteria for approval, lead time needed

5. **External Dependencies:**
   - "Payment integration requires vendor sandbox access"
   - Document: External party, SLA, contact, escalation path

**Dependency notation in tables:**
Use format: `[Deliverable ID] (Status)`

Example: `1.3 (Complete), 2.1 (In Progress), 3.4 (Blocked)`

---

## 6. RESOURCE ALLOCATION INSTRUCTIONS

### 6.1 Team Structure Documentation

**Create organizational chart showing:**
- Governance layer (steering committee)
- Management layer (PM, leads)
- Execution layer (developers, testers)
- Support layer (SMEs, admins)

**For each role, specify:**
```
Role: [Specific role name]
Allocation: [FTE % or person-days]
Phase Duration: [Weeks/Months]
Key Responsibilities: [3-5 specific duties]
Required Skills: [Technical and domain skills]
Reports To: [Manager role]
Interfaces With: [Other roles]
Deliverables Owned: [List of deliverable IDs]
```

### 6.2 Resource Leveling

**Ensure:**
- No single resource over 100% allocated
- Critical skills not single points of failure
- Peak loading does not exceed capacity by >10%
- Resource availability matches critical path needs

**Flag concerns:**
- "⚠️ Database Administrator at 120% allocation in Week 8-10"
- "⚠️ No backup for Mobile Lead if unavailable"

---

## 7. SUCCESS METRICS & KPIs

### 7.1 Metrics Categories

**Include metrics for:**

1. **Schedule Performance:**
   - Planned vs Actual completion dates
   - Milestone achievement rate
   - Critical path variance

2. **Budget Performance:**
   - Planned vs Actual spend by phase
   - Burn rate
   - Cost per deliverable

3. **Quality Performance:**
   - Defect density (bugs per deliverable)
   - Test pass rate
   - Rework percentage

4. **Business Value:**
   - Features delivered vs planned
   - User adoption rate
   - Business KPI improvement

### 7.2 KPI Specification Format

```
KPI: [Name]
Definition: [What is measured and how]
Target: [Specific numeric target]
Measurement Frequency: [Daily/Weekly/Monthly]
Data Source: [Where data comes from]
Owner: [Role responsible for monitoring]
Red Threshold: [Level indicating serious issues]
Yellow Threshold: [Level indicating caution]
Green Threshold: [Level indicating on-track]
```

---

## 8. VISUAL ELEMENTS REQUIREMENTS

### 8.1 Mandatory Diagrams

**Include these visual elements:**

1. **Timeline Gantt Chart (ASCII or description):**
   - Show phases as horizontal bars
   - Indicate dependencies with arrows
   - Mark milestones with symbols
   - Show critical path

2. **Dependency Network Diagram:**
   - Nodes = deliverables
   - Edges = dependencies
   - Highlight critical path
   - Show parallel work opportunities

3. **Resource Loading Chart:**
   - X-axis = time (weeks)
   - Y-axis = resource count or FTE
   - Stacked by role/team
   - Indicate capacity limits

4. **Risk Heat Map:**
   - X-axis = probability
   - Y-axis = impact
   - Plot risks by phase
   - Color-code by severity

### 8.2 ASCII Diagram Standards

**Use consistent formatting:**
```
Timeline format:
Month:    |  1  |  2  |  3  |  4  |  5  |  6  |
Phase 1   |█████████████░░░░░|     |     |     |
Phase 2   |     |     |█████████████░░░░░|     |

Legend: █ Complete  ░ In Progress  ▼ Milestone
```

---

## 9. QUALITY CHECKLIST

### 9.1 Before Finalizing Document

**Verify each item:**

- [ ] Every phase has clear objective and success criteria
- [ ] All deliverables have owners, reviewers, and due dates
- [ ] Every deliverable has 3-5 measurable acceptance criteria
- [ ] Dependencies are documented with specific IDs
- [ ] Timeline includes buffer time (minimum 20%)
- [ ] Resource allocation totals are realistic (no overallocation)
- [ ] At least 5 risks identified per phase with mitigation plans
- [ ] Go-live checklist provided for each phase
- [ ] KPIs defined for project success measurement
- [ ] Visual diagrams included (timeline, dependencies, risks)
- [ ] Document cross-references are accurate
- [ ] All acronyms defined in glossary
- [ ] Assumptions explicitly stated
- [ ] Constraints documented
- [ ] Escalation paths defined

### 9.2 Clarity Tests

**Document passes if:**
- A new team member can understand what to do without asking questions
- Stakeholders can track progress against specific metrics
- Risks and mitigation strategies are actionable
- Timeline is defensible with data-driven estimates
- Success criteria are measurable and time-bound

---

## 10. SPECIAL INSTRUCTIONS FOR COMPLEX PROJECTS

### 10.1 Multi-Portal/Multi-System Projects

**When project includes multiple portals or systems:**

1. **Create Integration Matrix:**
   - Row = System A, Column = System B
   - Cell = Integration type, data flow, protocol
   - Highlight critical integrations

2. **Portal-Specific Sub-Phases:**
   - Within each phase, group deliverables by portal
   - Clearly show which portals go live in which phase
   - Document portal interdependencies

3. **Shared Services First:**
   - Schedule shared components (authentication, payment, notification) early
   - Document which portals depend on which shared services

### 10.2 IoT/Hardware Integration Projects

**When project includes IoT or hardware:**

1. **Procurement Lead Times:**
   - Add hardware procurement phase (typically 8-12 weeks)
   - Buffer for customs/shipping delays
   - Plan for prototype testing before bulk order

2. **Physical Installation:**
   - Schedule site surveys
   - Plan installation windows (may require operational downtime)
   - Include commissioning and calibration time

3. **Connectivity Considerations:**
   - Document network requirements
   - Plan for offline/connectivity-loss scenarios
   - Include connectivity testing in acceptance criteria

### 10.3 Data Migration Projects

**When project includes data migration:**

1. **Multi-Pass Migration Strategy:**
   - Pass 1: Data assessment and cleansing
   - Pass 2: Trial migration and validation
   - Pass 3: Final migration and reconciliation
   - Each pass is a separate deliverable

2. **Parallel Running Period:**
   - Schedule 2-4 weeks of parallel operations
   - Daily reconciliation during this period
   - Cutover decision criteria documented

3. **Rollback Plan:**
   - Document rollback triggers
   - Specify rollback procedures
   - Estimate rollback duration

---

## 11. OUTPUT FORMAT SPECIFICATIONS

### 11.1 File Format

**Generate document in Markdown format with:**
- Clear heading hierarchy (# ## ### ####)
- Tables for structured data
- Code blocks for technical specs
- Bullet points for lists
- ASCII diagrams where appropriate

### 11.2 Naming Convention

**File name format:**
`[Project]_Implementation_Roadmap_Phase[N]_[PhaseName]_v[X.Y].md`

Example:
`Smart_Dairy_Implementation_Roadmap_Phase1_Foundation_v1.0.md`

### 11.3 Version Control

**Include at document start:**
```markdown
| Attribute | Value |
|-----------|-------|
| Document ID | [Unique ID] |
| Version | [X.Y] |
| Date | [YYYY-MM-DD] |
| Author | [Name/Role] |
| Status | [Draft/Review/Approved/Final] |
| Reviewers | [Names/Roles] |
| Approvers | [Names/Roles] |
| Change History | [Version history table] |
```

---

## 12. CONTEXT-SPECIFIC ADAPTATIONS

### 12.1 For Agile Projects

**Modify approach:**
- Phases become "Releases"
- Deliverables become "Epics"
- Include sprint planning guidance
- Add story point estimation
- Document Definition of Ready and Definition of Done
- Include retrospective placeholders

### 12.2 For Waterfall Projects

**Modify approach:**
- Emphasize gate reviews between phases
- More detailed upfront planning
- Strict change control procedures
- Formal sign-off requirements
- Detailed requirements traceability matrix

### 12.3 For Hybrid Projects

**Modify approach:**
- Document which components use which methodology
- Clear interfaces between agile and waterfall workstreams
- Synchronized integration points
- Unified governance framework

---

## 13. CRITICAL SUCCESS FACTORS

### 13.1 What Makes a Good Roadmap

**The roadmap is successful if:**
1. **Actionable:** Every team member knows what to do next
2. **Trackable:** Progress can be measured objectively
3. **Realistic:** Timeline and resources are achievable
4. **Risk-Aware:** Risks identified with mitigation plans
5. **Stakeholder-Aligned:** Meets business objectives and constraints
6. **Dependency-Clear:** All prerequisites and blockers visible
7. **Value-Driven:** Prioritizes high-value deliverables
8. **Flexible:** Can adapt to changes without complete rewrite

### 13.2 Common Pitfalls to Avoid

**DO NOT:**
- Create phases longer than 3 months (break into sub-phases if needed)
- Ignore resource constraints (e.g., schedule 10 weeks of work in 5 weeks)
- Underestimate integration and testing time
- Forget about documentation and training
- Assume perfect execution (always add buffer time)
- Create dependencies that cannot be tracked
- Define vague acceptance criteria
- Skip risk assessment

---

## 14. EXECUTION PROTOCOL

### 14.1 Step-by-Step Generation Process

**Follow this sequence:**

1. **READ** all provided context documents (BRD, SRS, TDD, etc.)
2. **EXTRACT** key requirements, constraints, dependencies
3. **ANALYZE** complexity, risks, and resource needs
4. **STRUCTURE** phases based on logical sequencing
5. **DETAIL** each phase with deliverables and timelines
6. **VALIDATE** against quality checklist (Section 9)
7. **FORMAT** according to output specifications (Section 11)
8. **REVIEW** for completeness, clarity, and consistency
9. **FINALIZE** with version control information

### 14.2 Iterative Refinement

**If uncertain about any element:**
1. State the uncertainty explicitly
2. Provide 2-3 alternative approaches
3. Explain trade-offs of each approach
4. Recommend an approach with rationale
5. Request clarification or approval

**Example:**
```
⚠️ DECISION POINT: Database Migration Strategy

Option 1: Big Bang Migration (1 weekend cutover)
- Pros: Clean break, no data sync complexity
- Cons: High risk, long downtime
- Timeline: 48-hour migration window

Option 2: Phased Migration (4-week parallel running)
- Pros: Lower risk, gradual transition
- Cons: Data sync complexity, higher cost
- Timeline: 4 weeks of parallel operations

Option 3: Parallel Systems (until deprecation)
- Pros: Lowest risk, users choose when to migrate
- Cons: Highest cost, data inconsistency risks
- Timeline: 3-6 months overlap

RECOMMENDATION: Option 2 (Phased Migration)
RATIONALE: Balances risk and cost for a production system
REQUIRES APPROVAL: Steering Committee by Week 4
```

---

## 15. FINAL VALIDATION QUESTIONS

**Before submitting the roadmap, answer these questions:**

1. Can the project manager create a weekly task list from this roadmap?
2. Can stakeholders understand project status by reading phase success criteria?
3. Can the team identify blockers and dependencies from the documentation?
4. Can finance track budget burn rate from the resource allocation?
5. Can QA create test plans from the acceptance criteria?
6. Can risk managers monitor and respond to identified risks?
7. Can the steering committee make go/no-go decisions at phase gates?
8. Can new team members onboard using this roadmap?

**If answer is "NO" to any question, the roadmap is incomplete.**

---

## APPENDIX A: TEMPLATE EXAMPLES

### Example 1: Deliverable Specification
```markdown
### Deliverable 1.5: Payment Gateway Integration - bKash

**ID:** 1.5
**Owner:** Backend Developer (Senior)
**Reviewer:** Solution Architect
**Target:** Week 8
**Effort:** 5 days development + 2 days testing
**Dependencies:**
- 1.3 (API Gateway deployed)
- 1.4 (PostgreSQL transaction tables created)

**Acceptance Criteria:**
1. Successfully process 100 consecutive test transactions with 100% success rate
2. Webhook receiver handles bKash callbacks with <500ms response time
3. Transaction reconciliation report matches bKash settlement report
4. Error scenarios handled gracefully (connection timeout, insufficient balance, etc.)
5. Security audit passed (API key storage, HTTPS enforcement, request signing)

**Testing Requirements:**
- Unit tests: 15+ test cases covering happy path and error scenarios
- Integration tests: End-to-end payment flow in sandbox environment
- Load test: 100 concurrent transactions without failure
- Security test: Penetration test by security team

**Documentation:**
- API integration guide for developers
- Transaction troubleshooting guide for support team
- Configuration parameters documented in deployment guide
```

### Example 2: Weekly Timeline
```markdown
### Phase 1 - Week 5: Database Setup and Configuration

**Sprint Goal:** Production-ready database cluster operational

**Monday-Tuesday:**
- Install PostgreSQL 15 on primary and standby nodes
- Configure streaming replication
- Install TimescaleDB extension
- Run replication lag test (target: <1s)

**Wednesday-Thursday:**
- Create database schemas (using migrations from C-001)
- Set up connection pooling (PgBouncer)
- Configure automated backups (daily at 2 AM)
- Test backup restore procedure

**Friday:**
- Performance baseline testing (target: 1000+ TPS)
- Security hardening (SSL, firewall rules, user permissions)
- Documentation update
- Handover session with DBA team

**Deliverables Due:** 1.3 (Database Cluster)
**Milestone:** DATABASE-READY
**Blockers:** None expected
**Risks:** R-1.2 (Hardware failures - standby node provides redundancy)
```

### Example 3: Risk Registry Entry
```markdown
### Risk R-2.5: Third-Party Payment Gateway API Changes

**Category:** Technical
**Probability:** Medium (30%)
**Impact:** High (could block Phase 2 go-live)

**Impact Description:**
If bKash/Nagad/Rocket change their APIs without notice, payment functionality could break. This would delay Phase 2 go-live by 1-2 weeks while we implement changes and retest.

**Mitigation Strategy:**
1. Subscribe to all payment gateway developer newsletters and API change notifications
2. Implement API version management with fallback to previous version
3. Create abstraction layer so payment provider can be swapped without major code changes
4. Schedule monthly check-ins with payment gateway technical teams
5. Maintain test accounts with all providers for early detection of changes

**Contingency Plan:**
If API breaks unexpectedly:
1. Immediately switch to fallback/previous API version (if available)
2. Activate emergency change process to fast-track new API integration
3. Deploy hotfix within 24 hours (target)
4. Communication plan: notify users of payment issues, provide alternative payment methods
5. Escalate to payment gateway account manager for support

**Owner:** Backend Technical Lead
**Status:** Monitoring (reviewed monthly)
**Last Review:** 2026-01-15
**Next Review:** 2026-02-15
```

---

## APPENDIX B: QUALITY SCORING RUBRIC

**Score each section 1-5:**

| Section | Score 1 | Score 3 | Score 5 |
|---------|---------|---------|---------|
| **Clarity** | Vague, ambiguous | Mostly clear with some ambiguity | Crystal clear, no interpretation needed |
| **Completeness** | Missing key sections | Most sections present | All required sections comprehensive |
| **Actionability** | Can't execute from doc | Can execute with questions | Can execute immediately |
| **Measurability** | No metrics | Some metrics | All KPIs well-defined |
| **Realism** | Unrealistic timelines | Mostly achievable | Realistic with buffer |
| **Risk-Awareness** | No risks identified | Some risks listed | Comprehensive risk management |

**Minimum acceptable score: 4.0 average across all sections**

---

**END OF INSTRUCTION SET**

**This skill set document enables AI agents to generate implementation roadmaps that are:**
✓ Comprehensive and detailed
✓ Actionable and executable
✓ Realistic and achievable
✓ Risk-aware and proactive
✓ Stakeholder-aligned
✓ Quality-assured

**Usage:** Provide this instruction set along with project context (BRD, SRS, TDD) to any AI agent tasked with creating implementation roadmap documents.
