# IT Support Procedures

---

## Document Information

| Field | Value |
|-------|-------|
| **Document ID** | L-002 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | IT Support Manager |
| **Owner** | IT Support Manager |
| **Reviewer** | IT Director |
| **Classification** | Internal Use |
| **Status** | Approved |

---

## Document History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | IT Support Manager | Initial document creation |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Support Channels](#2-support-channels)
3. [Ticket Lifecycle](#3-ticket-lifecycle)
4. [Support Tiers](#4-support-tiers)
5. [Categorization](#5-categorization)
6. [Priority Levels](#6-priority-levels)
7. [Escalation Procedures](#7-escalation-procedures)
8. [Communication Standards](#8-communication-standards)
9. [Knowledge Base Usage](#9-knowledge-base-usage)
10. [Tools and Systems](#10-tools-and-systems)
11. [Metrics and Reporting](#11-metrics-and-reporting)
12. [Appendices](#12-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document establishes the standard operating procedures for IT support services at Smart Dairy Ltd. It defines the framework for delivering consistent, efficient, and high-quality technical support to all employees across all business units.

### 1.2 Support Scope

The IT Support team provides assistance for:

| Category | Inclusions |
|----------|------------|
| **Hardware** | Laptops, desktops, printers, scanners, mobile devices, peripherals |
| **Software** | Operating systems, productivity suites, licensed applications |
| **Network** | Wired and wireless connectivity, VPN access, network drives |
| **Applications** | ERP systems, HR systems, finance applications, custom software |
| **Access/Security** | Account management, password resets, access permissions, MFA issues |
| **Collaboration Tools** | Email, Teams, SharePoint, video conferencing |

### 1.3 Objectives

- **First Contact Resolution**: Achieve 70% resolution rate on first contact
- **Response Time**: Meet SLA targets for all priority levels
- **User Satisfaction**: Maintain customer satisfaction score above 90%
- **Availability**: Ensure 99.5% uptime for critical systems
- **Continuous Improvement**: Reduce ticket volume through proactive support

### 1.4 Service Hours

| Support Channel | Standard Hours | Extended Hours |
|-----------------|----------------|----------------|
| Helpdesk Portal | 24/7 | N/A |
| Email Support | 24/7 | N/A |
| Phone Support | 07:00 - 19:00 GST | 24/7 (P1 only) |
| WhatsApp (Urgent) | 07:00 - 22:00 GST | 24/7 (P1 only) |
| Walk-up Support | 08:00 - 17:00 GST | By appointment |

**Working Days**: Sunday - Thursday (UAE Standard)  
**Weekend Coverage**: On-call engineer for P1/P2 issues only

---

## 2. Support Channels

### 2.1 Helpdesk Portal (JIRA Service Desk)

**Primary Channel for All IT Support Requests**

- **URL**: https://smartdairy.atlassian.net/servicedesk
- **Access**: Available to all employees with AD credentials
- **Best For**: Non-urgent requests, detailed issue documentation, tracking history

**Features:**
- Self-service knowledge base
- Automated ticket routing
- Real-time status updates
- SLA tracking
- Satisfaction surveys

**Usage Guidelines:**
1. Log in with your company credentials
2. Select appropriate request type
3. Provide detailed description with screenshots where applicable
4. Submit and note the ticket reference number

### 2.2 Email Support

**Secondary Channel for Non-Urgent Requests**

- **Address**: itsupport@smartdairy.com
- **Response Time**: Within SLA based on priority
- **Best For**: Detailed technical issues, attachments, non-urgent requests

**Email Format:**
```
Subject: [Priority] Brief description of issue

User Details:
- Name: [Full Name]
- Employee ID: [ID Number]
- Department: [Department Name]
- Location: [Office/Farm/Warehouse]
- Contact Number: [Phone Number]

Issue Description:
[Detailed description of the problem]

Impact:
[Business impact and number of users affected]

Steps Taken:
[What has been tried to resolve the issue]

Attachments: [Screenshots, error logs]
```

### 2.3 Phone Support

**For Urgent Issues and Immediate Assistance**

- **Internal Extension**: 8888
- **External Line**: +971-4-XXX-8888
- **Hours**: 07:00 - 19:00 GST (Sunday - Thursday)
- **Best For**: Urgent issues, users unable to access email/portal

**Phone Support Process:**
1. Call the support line
2. Provide employee ID and department
3. Describe the issue briefly
4. Support engineer creates ticket on your behalf
5. Receive ticket number via SMS/email

### 2.4 WhatsApp (Urgent Issues Only)

**Emergency Channel for Critical Issues**

- **Number**: +971-50-XXX-SUPPORT
- **Hours**: 07:00 - 22:00 GST (Extended for P1 issues)
- **Best For**: System outages, critical business impact

**Usage Rules:**
- Use ONLY for P1 (Critical) and P2 (High) priority issues
- Start with "URGENT: [Brief description]"
- Include your name, location, and employee ID
- Do not use for general inquiries

### 2.5 Walk-up Support

**In-Person Assistance**

- **Location**: IT Support Desk, Main Office Building, Ground Floor
- **Hours**: 08:00 - 17:00 GST
- **Best For**: Hardware issues, new device setup, training requests

**Process:**
1. Visit IT Support Desk
2. Check in with reception
3. Describe issue to available engineer
4. Receive service ticket for tracking

---

## 3. Ticket Lifecycle

### 3.1 Ticket Workflow Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         TICKET LIFECYCLE WORKFLOW                           │
└─────────────────────────────────────────────────────────────────────────────┘

    ┌─────────────┐
    │   START     │
    └──────┬──────┘
           │
           ▼
    ┌─────────────────┐     ┌─────────────────┐
    │  Ticket Created │────▶│ Auto-Validation │
    │  (Any Channel)  │     │   & Routing     │
    └─────────────────┘     └────────┬────────┘
                                     │
                    ┌────────────────┼────────────────┐
                    │                │                │
                    ▼                ▼                ▼
            ┌──────────────┐ ┌──────────────┐ ┌──────────────┐
            │   TIER 1     │ │   TIER 2     │ │   TIER 3     │
            │ First-Line   │ │ Technical    │ │ Specialist   │
            │   Support    │ │   Support    │ │   Support    │
            └──────┬───────┘ └──────┬───────┘ └──────┬───────┘
                   │                │                │
                   ▼                ▼                ▼
            ┌──────────────┐ ┌──────────────┐ ┌──────────────┐
            │  Attempt     │ │  Attempt     │ │  Attempt     │
            │  Resolution  │ │  Resolution  │ │  Resolution  │
            └──────┬───────┘ └──────┬───────┘ └──────┬───────┘
                   │                │                │
           ┌───────┴───────┐ ┌───────┴───────┐ ┌───────┴───────┐
           │               │ │               │ │               │
           ▼               │ ▼               │ ▼               │
    ┌──────────────┐      │ ┌──────────────┐│ ┌──────────────┐│
    │   Resolved   │      │ │   Resolved   ││ │   Resolved   ││
    │  First Call  │      │ │  Second Tier ││ │  Third Tier  ││
    └──────┬───────┘      │ └──────┬───────┘│ └──────┬───────┘│
           │              │        │        │        │        │
           │              │        │        │        │        │
           │    ┌─────────┴────────┴────────┴────────┘        │
           │    │                                             │
           │    │    ┌──────────────────────────┐             │
           │    │    │    ESCALATION PATH     │◀──────────────┘
           │    │    │  (If not resolved)       │
           │    │    └────────────┬─────────────┘
           │    │                 │
           │    │                 ▼
           │    │    ┌──────────────────────────┐
           │    └───▶│   Vendor/External        │
           │         │   Support Engagement     │
           │         └────────────┬─────────────┘
           │                      │
           └──────────────────────┼──────────────────┐
                                  │                  │
                                  ▼                  ▼
                         ┌──────────────┐    ┌──────────────┐
                         │   User       │    │   Ticket     │
                         │  Notified    │    │  Reassigned  │
                         └──────┬───────┘    └──────────────┘
                                │
                                ▼
                         ┌──────────────┐
                         │   Solution   │
                         │  Implemented │
                         └──────┬───────┘
                                │
                                ▼
                         ┌──────────────┐
                         │  User        │
                         │  Verification│
                         └──────┬───────┘
                                │
                    ┌───────────┴───────────┐
                    │                       │
                    ▼                       ▼
            ┌──────────────┐        ┌──────────────┐
            │  Verified    │        │  Not Fixed   │
            │  (Closure)   │        │  (Reopen)    │
            └──────┬───────┘        └──────────────┘
                   │
                   ▼
            ┌──────────────┐
            │   Ticket     │
            │   Closed     │
            └──────┬───────┘
                   │
                   ▼
            ┌──────────────┐
            │  Satisfaction│
            │   Survey     │
            └──────────────┘
```

### 3.2 Ticket Creation

**Methods:**
- Self-service via Helpdesk Portal
- Email to itsupport@smartdairy.com
- Phone call (engineer creates on behalf)
- WhatsApp (for urgent issues)
- Walk-up (engineer creates ticket)

**Required Information:**
- Requester name and contact details
- Department and location
- Category and subcategory
- Detailed description
- Business impact
- Attachments (screenshots, error logs)

### 3.3 Triage and Categorization

**Automatic Triage Rules:**

| Keyword/Condition | Auto-Category | Auto-Priority |
|-------------------|---------------|---------------|
| "system down", "outage" | Network/Infrastructure | P1 |
| "cannot login", "password" | Access/Security | P2 |
| "printer not working" | Hardware | P3 |
| "software request" | Software | P4 |
| >10 users affected | Infrastructure | Auto-escalate +1 |
| "CEO", "Director" mentioned | Based on content | Auto-escalate +1 |

**Manual Triage (within 15 minutes of creation):**
- Review auto-categorization
- Adjust priority if needed
- Assign to appropriate tier/team
- Add tags for reporting

### 3.4 Assignment

**Assignment Matrix:**

| Issue Type | Primary Assignee | Backup Assignee |
|------------|------------------|-----------------|
| Hardware - Laptops/Desktops | Tier 1 Team | Tier 2 Team |
| Hardware - Printers/Peripherals | Tier 1 Team | Tier 2 Team |
| Software - Office 365 | Tier 1 Team | Tier 2 Team |
| Software - ERP | Tier 2 Team | Tier 3 Team |
| Network - Connectivity | Tier 2 Team | Network Engineer |
| Network - Infrastructure | Network Engineer | IT Director |
| Security - Access/Permissions | Tier 2 Team | Security Admin |
| Security - Breach/Incident | Security Admin | IT Director |

### 3.5 Resolution

**Resolution Standards:**
- Document all steps taken in ticket
- Record time spent on each activity
- Update ticket status regularly
- Communicate progress to user
- Test solution before marking resolved

**First Contact Resolution (FCR) Focus:**
- Common password resets
- Software installation requests
- Basic troubleshooting
- Account unlocks
- Permission updates

### 3.6 Closure

**Closure Criteria:**
- Issue resolved and verified by user
- No response from user for 5 business days (after 3 follow-ups)
- Duplicate ticket (merged with original)
- Issue escalated to different queue

**Closure Process:**
1. Mark ticket as "Resolved"
2. User receives automated notification
3. User has 5 days to reopen if not satisfied
4. After 5 days, ticket automatically closes
5. Satisfaction survey sent to user

### 3.7 Feedback

**Satisfaction Survey:**
- Sent automatically upon ticket closure
- 3-question format (rating + comments)
- Anonymous option available
- Results reviewed weekly by IT Management

**Survey Questions:**
1. How satisfied were you with the support received? (1-5 scale)
2. Was your issue resolved in a timely manner? (Yes/No)
3. Any additional comments or suggestions?

---

## 4. Support Tiers

### 4.1 Tier 1: First-Line Support

**Team Composition:** IT Support Technicians (3 FTE)

**Responsibilities:**
- Initial ticket triage and categorization
- Password resets and account unlocks
- Software installation and basic configuration
- Printer setup and troubleshooting
- Email and basic application support
- Hardware replacement for standard devices
- New user onboarding (IT equipment)

**Typical Issues:**
- "I forgot my password"
- "I can't connect to WiFi"
- "My email isn't working"
- "I need software installed"
- "My printer is offline"
- "I need a new mouse/keyboard"

**Escalation Triggers:**
- Issue not resolved within 30 minutes
- Requires system admin access
- Complex network troubleshooting
- Application bugs or errors
- Security-related issues

### 4.2 Tier 2: Technical Support

**Team Composition:** Senior IT Support Engineers (2 FTE)

**Responsibilities:**
- Escalated issues from Tier 1
- ERP system support
- Network troubleshooting
- Server administration (basic)
- Application configuration
- Advanced hardware diagnostics
- Vendor coordination for warranty claims

**Typical Issues:**
- ERP login or transaction errors
- VPN connection failures
- Network drive access issues
- Email server problems
- Application integration issues
- Hardware diagnostics and repairs

**Escalation Triggers:**
- Issue requires code changes
- Database-level troubleshooting
- Infrastructure changes needed
- Security incident response
- Vendor escalation required

### 4.3 Tier 3: Specialist Support

**Team Composition:** IT Systems Administrator, Security Admin, IT Director

**Responsibilities:**
- Critical system issues
- Infrastructure management
- Security incidents
- Database administration
- Application development issues
- Architecture and planning
- Vendor management for complex issues

**Typical Issues:**
- Server outages
- Database corruption
- Security breaches
- Major infrastructure failures
- Custom application bugs
- Disaster recovery activation

---

## 5. Categorization

### 5.1 Category Definitions

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                     TICKET CATEGORIZATION STRUCTURE                         │
└─────────────────────────────────────────────────────────────────────────────┘

CATEGORY: HARDWARE
├── Laptops/Desktop
│   ├── Hardware Failure
│   ├── Performance Issue
│   ├── Peripheral Connection
│   └── Replacement Request
├── Mobile Devices
│   ├── Phone Setup
│   ├── Application Issue
│   └── Device Replacement
├── Printers/Scanners
│   ├── Connection Issue
│   ├── Print Quality
│   ├── Supply Request
│   └── Hardware Failure
└── Peripherals
    ├── Keyboard/Mouse
    ├── Monitor
    ├── Webcam/Headset
    └── Docking Station

CATEGORY: SOFTWARE
├── Operating System
│   ├── Windows
│   ├── macOS
│   └── Linux
├── Productivity Suite
│   ├── Microsoft Office
│   └── Google Workspace
├── Licensed Applications
│   ├── Adobe Suite
│   ├── CAD Software
│   └── Specialized Tools
├── Antivirus/Security
│   ├── Scan Issues
│   ├── Quarantine Management
│   └── Update Problems
└── Software Requests
    ├── New Installation
    ├── License Request
    └── Upgrade Request

CATEGORY: NETWORK
├── Connectivity
│   ├── WiFi Issues
│   ├── Wired Connection
│   └── Remote Access
├── VPN
│   ├── Connection Failure
│   ├── Performance Issues
│   └── Access Permissions
├── Network Resources
│   ├── Shared Drives
│   ├── Network Printers
│   └── Server Access
└── Infrastructure
    ├── Switch/Router Issues
    ├── Firewall Rules
    └── DNS/DHCP

CATEGORY: APPLICATION
├── ERP System
│   ├── Login Issues
│   ├── Transaction Errors
│   ├── Report Generation
│   └── User Access
├── HR Systems
│   ├── Leave Management
│   ├── Payroll Access
│   └── Performance Portal
├── Finance Systems
│   ├── SAP Access
│   ├── Invoice Processing
│   └── Budget Reports
├── Collaboration Tools
│   ├── Microsoft Teams
│   ├── SharePoint
│   └── Video Conferencing
└── Custom Applications
    ├── Dairy Management System
    ├── Inventory Tracker
    └── Quality Control App

CATEGORY: ACCESS/SECURITY
├── Account Management
│   ├── Password Reset
│   ├── Account Unlock
│   ├── New Account Creation
│   └── Account Deactivation
├── Access Permissions
│   ├── File/Folder Access
│   ├── Application Access
│   └── Role Changes
├── Multi-Factor Authentication
│   ├── MFA Setup
│   ├── Device Registration
│   └── Recovery Codes
└── Security Incidents
    ├── Phishing Reports
    ├── Suspicious Activity
    └── Data Access Issues
```

### 5.2 Category Assignment Rules

| If Issue Contains... | Assign Category |
|----------------------|-----------------|
| "cannot print", "printer", "scanner" | Hardware > Printers |
| "password", "login", "unlock", "access" | Access/Security |
| "slow", "freeze", "crash" | Software > OS |
| "WiFi", "network", "connection", "internet" | Network > Connectivity |
| "ERP", "SAP", "transaction" | Application > ERP |
| "Teams", "email", "Outlook", "SharePoint" | Application > Collaboration |
| "phone", "mobile", "iPhone", "Android" | Hardware > Mobile Devices |

---

## 6. Priority Levels

### 6.1 SLA Response Matrix

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                     SLA RESPONSE AND RESOLUTION MATRIX                      │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────┬──────────────────┬──────────────────┬─────────────────────────────┐
│ Priority│ Response Time    │ Resolution Time  │ Example Scenarios           │
├─────────┼──────────────────┼──────────────────┼─────────────────────────────┤
│         │                  │                  │ - Complete system outage    │
│   P1    │     15 minutes   │     4 hours      │ - Security breach           │
│CRITICAL │     (24/7)       │    (24/7)        │ - All users affected        │
│         │                  │                  │ - Business halted           │
├─────────┼──────────────────┼──────────────────┼─────────────────────────────┤
│         │                  │                  │ - Department-wide issue     │
│   P2    │     1 hour       │     8 hours      │ - Critical user affected    │
│  HIGH   │   (Business hrs) │  (Business hrs)  │ - Major function impaired   │
│         │                  │                  │ - Revenue impact            │
├─────────┼──────────────────┼──────────────────┼─────────────────────────────┤
│         │                  │                  │ - Single user affected      │
│   P3    │     4 hours      │    24 hours      │ - Workaround available      │
│ MEDIUM  │   (Business hrs) │  (Business hrs)  │ - Non-critical function     │
│         │                  │                  │ - Minor productivity impact │
├─────────┼──────────────────┼──────────────────┼─────────────────────────────┤
│         │                  │                  │ - Service requests          │
│   P4    │    24 hours      │    72 hours      │ - General inquiries         │
│   LOW   │   (Business hrs) │  (Business hrs)  │ - Future enhancements       │
│         │                  │                  │ - Training requests         │
└─────────┴──────────────────┴──────────────────┴─────────────────────────────┘

Business Hours: 07:00 - 19:00 GST, Sunday - Thursday
```

### 6.2 Priority Definitions

**P1 - Critical**
- Complete service outage affecting all users
- Security breach or data compromise
- Critical business system failure
- Multiple departments affected
- Revenue-impacting issue with no workaround

**P2 - High**
- Significant functionality impaired
- Department-wide issue
- Critical user (Executive/Director) affected
- Important deadline at risk
- Workaround available but significantly impacts productivity

**P3 - Medium**
- Single user affected
- Partial functionality affected
- Workaround available with minimal impact
- Non-critical business function impaired
- General support request

**P4 - Low**
- Service request (new hardware/software)
- Information inquiry
- Training request
- Minor cosmetic issues
- Future planning/enhancement requests

### 6.3 Priority Assignment Guidelines

| Condition | Priority Assignment |
|-----------|---------------------|
| All users affected | P1 |
| Single department affected (>10 people) | P2 |
| Single user, cannot work | P2 |
| Single user, workaround exists | P3 |
| Service request | P4 |
| Executive/Director affected | +1 from base |
| Revenue-impacting | P1 or P2 |
| Compliance-related | P1 or P2 |
| Weekend/Holiday critical issue | P1 |

---

## 7. Escalation Procedures

### 7.1 Escalation Path

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        ESCALATION PATH DIAGRAM                              │
└─────────────────────────────────────────────────────────────────────────────┘

LEVEL 1: TECHNICIAN/ENGINEER ESCALATION
═══════════════════════════════════════════════════════════════════════════════

Tier 1 Support Technician
           │
           │ Cannot resolve within 30 minutes
           │ or requires higher access
           ▼
    Tier 2 Engineer
           │
           │ Cannot resolve within 2 hours
           │ or requires specialist knowledge
           ▼
    Tier 3 Specialist
           │
           │ Cannot resolve within 4 hours
           │ or requires vendor/external support
           ▼
    Vendor/External Support


LEVEL 2: MANAGEMENT ESCALATION (Time-Based)
═══════════════════════════════════════════════════════════════════════════════

    P1 (Critical) Issues:
    ─────────────────────
    00:15 ──▶ Response required
    01:00 ──▶ Escalate to Tier 3 if no progress
    02:00 ──▶ Escalate to IT Manager
    04:00 ──▶ Escalate to IT Director
    Issue resolved ──▶ Close escalation

    P2 (High) Issues:
    ─────────────────
    01:00 ──▶ Response required
    04:00 ──▶ Escalate to Tier 3 if no progress
    08:00 ──▶ Escalate to IT Manager
    Issue resolved ──▶ Close escalation

    P3 (Medium) Issues:
    ───────────────────
    04:00 ──▶ Response required
    24:00 ──▶ Escalate to IT Manager if not resolved
    Issue resolved ──▶ Close escalation


LEVEL 3: USER-INITIATED ESCALATION
═══════════════════════════════════════════════════════════════════════════════

    If user is dissatisfied with support:
    
    Step 1: Contact IT Support Manager
            Email: it.manager@smartdairy.com
            Phone: 8889 (Internal)
            
    Step 2: If still unresolved, contact IT Director
            Email: it.director@smartdairy.com
            Phone: 8890 (Internal)
            
    Step 3: For serious concerns, contact HR
            Email: hr@smartdairy.com

```

### 7.2 Escalation Procedures by Priority

**P1 (Critical) Escalation:**
1. Immediately notify on-call engineer
2. Create incident bridge/conference call
3. Notify IT Manager within 15 minutes
4. Notify IT Director if not resolved in 1 hour
5. Post-resolution: Conduct post-mortem within 24 hours

**P2 (High) Escalation:**
1. Assign to Tier 2 immediately
2. Notify Team Lead within 1 hour if no progress
3. Escalate to Tier 3 if not resolved in 4 hours
4. Notify IT Manager if approaching SLA breach

**P3/P4 Escalation:**
1. Standard assignment and tracking
2. Escalate to Team Lead if nearing SLA
3. Re-prioritize if business impact increases

### 7.3 Vendor Escalation

**When to Engage Vendors:**
- Hardware under warranty failure
- Software vendor support required
- Network service provider issues
- Cloud service provider outages

**Vendor Contact Matrix:**

| Vendor | Issue Type | Contact | SLA |
|--------|------------|---------|-----|
| Microsoft | Office 365, Azure | Premier Support | 1 hour |
| Dell | Hardware warranty | ProSupport | 4 hours |
| Cisco | Network equipment | TAC | 1 hour |
| ERP Vendor | ERP issues | Support Portal | 4 hours |
| ISP | Internet connectivity | 24/7 Hotline | 2 hours |

---

## 8. Communication Standards

### 8.1 Communication Guidelines

**Tone and Style:**
- Professional and courteous
- Clear and concise
- Jargon-free (or with explanations)
- Empathetic to user frustration
- Solution-focused

**Response Templates:**

### 8.2 Acknowledgment Template

```
Subject: [Ticket #12345] Acknowledgment - [Issue Summary]

Dear [User Name],

Thank you for contacting IT Support. We have received your request and have 
assigned ticket number #12345.

Issue Summary: [Brief description]
Priority: [P1/P2/P3/P4]
Category: [Category/Subcategory]
Assigned To: [Engineer Name]

Expected Response Time: [Based on SLA]
Expected Resolution Time: [Based on SLA]

You can track the status of your ticket at: https://smartdairy.atlassian.net/servicedesk

We will keep you updated on our progress. If you have any questions, please 
reply to this email or call us at extension 8888.

Best regards,
IT Support Team
Smart Dairy Ltd.
```

### 8.3 Progress Update Template

```
Subject: [Ticket #12345] Progress Update - [Issue Summary]

Dear [User Name],

We are writing to update you on ticket #12345.

Current Status: [In Progress / Waiting for User / Escalated]

Actions Taken:
• [Action 1]
• [Action 2]
• [Action 3]

Next Steps:
• [Next step]
• [Next step]

Expected Resolution: [Date/Time]

If you have any questions or need immediate assistance, please contact us 
at extension 8888.

Best regards,
[Engineer Name]
IT Support Team
```

### 8.4 Resolution Template

```
Subject: [Ticket #12345] Resolved - [Issue Summary]

Dear [User Name],

We are pleased to inform you that ticket #12345 has been resolved.

Issue: [Original issue description]

Resolution: [Detailed explanation of what was done to fix the issue]

To prevent similar issues in the future: [If applicable, preventive measures]

Your ticket will be automatically closed in 5 business days. If you 
experience any further issues or are not satisfied with this resolution, 
please reply to this email or contact us at extension 8888.

Thank you for contacting IT Support.

Best regards,
[Engineer Name]
IT Support Team
Smart Dairy Ltd.

---
Satisfaction Survey: [Link to survey]
```

### 8.5 Escalation Notification Template

```
Subject: [ESCALATION] Ticket #12345 - [Issue Summary]

Priority: P1 - Critical
Ticket: #12345
Escalation Level: [Level 1/2/3]

Issue Summary:
[Description of the issue]

Business Impact:
[Who is affected and how]

Actions Taken So Far:
• [Action]
• [Action]

Current Status:
[Current situation]

Next Steps:
[What needs to happen next]

Escalated By: [Name]
Escalated To: [Name]
Time of Escalation: [Timestamp]

IT Support Team
Smart Dairy Ltd.
```

### 8.6 Communication Frequency

| Priority | Initial Response | Progress Updates | Closure |
|----------|------------------|------------------|---------|
| P1 | Within 15 minutes | Every 30 minutes | Immediate |
| P2 | Within 1 hour | Every 2 hours | Within 1 hour |
| P3 | Within 4 hours | Daily | Within 1 hour |
| P4 | Within 24 hours | Weekly | Within 1 hour |

---

## 9. Knowledge Base Usage

### 9.1 Knowledge Base Structure

```
KNOWLEDGE BASE CATEGORIES
══════════════════════════════════════════════════════════════════════════════

┌─────────────────────────────────────────────────────────────────────────────┐
│ GETTING STARTED                                                             │
├─────────────────────────────────────────────────────────────────────────────┤
│ • IT Support Overview              • How to Submit a Ticket                │
│ • Contact Information              • Service Hours                         │
│ • New Employee Onboarding          • IT Policies Overview                  │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│ PASSWORDS & ACCESS                                                          │
├─────────────────────────────────────────────────────────────────────────────┤
│ • Reset Your Password              • Unlock Your Account                   │
│ • Set Up Multi-Factor Auth         • Password Policy Requirements          │
│ • Request System Access            • Manage Your MFA Devices               │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│ EMAIL & COMMUNICATION                                                       │
├─────────────────────────────────────────────────────────────────────────────┤
│ • Outlook Setup Guide              • Teams Basics and Tips                 │
│ • Email Signature Setup            • Teams Meeting Best Practices          │
│ • Out of Office Setup              • SharePoint Navigation                 │
│ • Distribution List Request        • Video Conferencing Setup              │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│ HARDWARE & DEVICES                                                          │
├─────────────────────────────────────────────────────────────────────────────┤
│ • Connect to WiFi                  • Printer Setup and Troubleshooting     │
│ • Laptop Docking Station Guide     • Order IT Equipment                    │
│ • Mobile Email Setup               • Hardware Return Process               │
│ • VPN Installation and Use         • Request Hardware Upgrade              │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│ SOFTWARE & APPLICATIONS                                                     │
├─────────────────────────────────────────────────────────────────────────────┤
│ • Software Installation Request    • Office 365 Apps Guide                 │
│ • ERP System Access                • Common ERP Issues and Fixes           │
│ • Adobe License Request            • Approved Software List                │
│ • Application Troubleshooting      • Software Update Procedures            │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│ SECURITY                                                                    │
├─────────────────────────────────────────────────────────────────────────────┤
│ • Report a Phishing Email          • Security Best Practices               │
│ • Suspicious Activity Reporting    • Data Handling Guidelines              │
│ • Laptop Encryption                • Remote Work Security                  │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 9.2 Knowledge Base Article Standards

**Article Structure:**
1. **Title** - Clear, searchable title
2. **Overview** - Brief description of the topic
3. **Audience** - Who this article is for
4. **Prerequisites** - What is needed before starting
5. **Step-by-Step Instructions** - Numbered, clear steps
6. **Screenshots** - Visual aids where helpful
7. **Troubleshooting** - Common issues and solutions
8. **Related Articles** - Links to related content
9. **Last Updated** - Date of last revision

**Writing Guidelines:**
- Use simple language (aim for 8th-grade reading level)
- Include screenshots for all visual procedures
- Keep articles under 500 words when possible
- Use bullet points and numbered lists
- Bold important terms and warnings
- Include "Was this helpful?" feedback option

### 9.3 Knowledge Base Maintenance

**Monthly Review:**
- Review articles with low helpful ratings
- Update outdated screenshots
- Verify all links are working
- Check for new common issues to document

**Quarterly Audit:**
- Remove obsolete articles
- Merge duplicate content
- Update based on system changes
- Review search analytics for gaps

---

## 10. Tools and Systems

### 10.1 Support Tools Inventory

| Tool | Purpose | Access Level |
|------|---------|--------------|
| **JIRA Service Desk** | Ticket management, SLAs, reporting | All IT Staff |
| **Confluence** | Knowledge base documentation | All IT Staff |
| **Active Directory** | User and group management | Tier 2+ |
| **Microsoft 365 Admin** | Email, Teams, SharePoint admin | Tier 2+ |
| **Intune/SCCM** | Device management | Tier 2+ |
| **NinjaRMM** | Remote monitoring and management | All IT Staff |
| **Wireshark** | Network troubleshooting | Tier 2+ |
| **ERP Admin Console** | ERP user and permissions management | Tier 3 |
| **Firewall Management** | Security rule management | Tier 3 |
| **Backup Systems** | Backup verification and restore | Tier 3 |

### 10.2 Tool Usage Guidelines

**JIRA Service Desk:**
- All tickets must be logged in JIRA
- Update ticket status in real-time
- Use canned responses for common issues
- Link related tickets for tracking
- Generate reports from built-in dashboards

**Remote Access (NinjaRMM):**
- Obtain user consent before remote connection
- Notify user when connecting
- Log all remote sessions
- Never access without valid ticket
- Disconnect immediately after resolution

**Active Directory:**
- Follow change control for all modifications
- Document all changes in ticket
- Never delete accounts (disable instead)
- Use delegation for least privilege access

### 10.3 System Access Matrix

| System | Tier 1 | Tier 2 | Tier 3 | IT Manager |
|--------|--------|--------|--------|------------|
| JIRA (Agent) | ✓ | ✓ | ✓ | ✓ |
| JIRA (Admin) | ✗ | ✗ | ✗ | ✓ |
| AD (Read) | ✓ | ✓ | ✓ | ✓ |
| AD (Write) | ✗ | ✓ | ✓ | ✓ |
| M365 Admin | ✗ | ✓ | ✓ | ✓ |
| ERP Admin | ✗ | ✗ | ✓ | ✓ |
| Firewall | ✗ | ✗ | ✓ | ✓ |
| Backup Systems | ✗ | ✗ | ✓ | ✓ |

---

## 11. Metrics and Reporting

### 11.1 Key Performance Indicators (KPIs)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    IT SUPPORT KPI DASHBOARD                                 │
└─────────────────────────────────────────────────────────────────────────────┘

TICKET VOLUME METRICS
══════════════════════
┌─────────────────────────┬────────────┬────────────┬────────────┐
│ Metric                  │ Target     │ Last Month │ YTD Avg    │
├─────────────────────────┼────────────┼────────────┼────────────┤
│ Total Tickets           │ N/A        │     450    │    425     │
│ Tickets per Agent       │ < 150/mo   │     112    │    106     │
│ Tickets per User        │ < 1/mo     │    0.75    │   0.70     │
│ Self-Service Rate       │ > 20%      │    22%     │    19%     │
└─────────────────────────┴────────────┴────────────┴────────────┘

RESPONSE & RESOLUTION METRICS
══════════════════════════════
┌─────────────────────────┬────────────┬────────────┬────────────┐
│ Metric                  │ Target     │ Last Month │ YTD Avg    │
├─────────────────────────┼────────────┼────────────┼────────────┤
│ First Response Time     │ < 1 hour   │   45 min   │   52 min   │
│ First Contact Resolution│ > 70%      │    73%     │    71%     │
│ Average Resolution Time │ < 8 hours  │   6.5 hrs  │   7.2 hrs  │
│ SLA Compliance          │ > 95%      │    97%     │    96%     │
└─────────────────────────┴────────────┴────────────┴────────────┘

QUALITY METRICS
═══════════════
┌─────────────────────────┬────────────┬────────────┬────────────┐
│ Metric                  │ Target     │ Last Month │ YTD Avg    │
├─────────────────────────┼────────────┼────────────┼────────────┤
│ Customer Satisfaction   │ > 90%      │    94%     │    92%     │
│ Tickets Reopened        │ < 10%      │     8%     │     9%     │
│ Escalation Rate         │ < 15%      │    12%     │    14%     │
│ Knowledge Base Usage    │ > 30%      │    32%     │    28%     │
└─────────────────────────┴────────────┴────────────┴────────────┘
```

### 11.2 Reporting Schedule

| Report | Frequency | Audience | Distribution |
|--------|-----------|----------|--------------|
| Daily Ticket Summary | Daily | IT Team | Email |
| Weekly Performance Report | Weekly | IT Management | Email + Meeting |
| Monthly KPI Dashboard | Monthly | IT Director + Dept Heads | Email + Presentation |
| Quarterly Business Review | Quarterly | Executive Team | Presentation |
| Annual IT Support Report | Annually | All Staff | Internal Portal |

### 11.3 Monthly Report Template

```
SMART DAIRY LTD - IT SUPPORT MONTHLY REPORT
Month: [Month Year]
Prepared by: IT Support Manager
Date: [Date]

═══════════════════════════════════════════════════════════════════════════════
EXECUTIVE SUMMARY
═══════════════════════════════════════════════════════════════════════════════

• Total tickets this month: XXX (↑/↓ X% from last month)
• Average resolution time: X.X hours
• Customer satisfaction: XX%
• SLA compliance: XX%
• Key highlights: [2-3 bullet points]

═══════════════════════════════════════════════════════════════════════════════
TICKET VOLUME ANALYSIS
═══════════════════════════════════════════════════════════════════════════════

By Category:
• Hardware: XX tickets (XX%)
• Software: XX tickets (XX%)
• Network: XX tickets (XX%)
• Applications: XX tickets (XX%)
• Access/Security: XX tickets (XX%)

By Priority:
• P1 (Critical): XX tickets
• P2 (High): XX tickets
• P3 (Medium): XX tickets
• P4 (Low): XX tickets

By Department:
• Operations: XX tickets
• Finance: XX tickets
• HR: XX tickets
• Sales: XX tickets
• [Other departments...]

═══════════════════════════════════════════════════════════════════════════════
PERFORMANCE METRICS
═══════════════════════════════════════════════════════════════════════════════

[Insert KPI table from section 11.1]

Trends:
• [Metric] is trending [up/down] due to [reason]
• [Metric] improvement due to [action taken]

═══════════════════════════════════════════════════════════════════════════════
TOP ISSUES & RESOLUTIONS
═══════════════════════════════════════════════════════════════════════════════

Top 5 Issues This Month:
1. [Issue] - XX tickets - [Resolution/KB article]
2. [Issue] - XX tickets - [Resolution/KB article]
3. [Issue] - XX tickets - [Resolution/KB article]
4. [Issue] - XX tickets - [Resolution/KB article]
5. [Issue] - XX tickets - [Resolution/KB article]

═══════════════════════════════════════════════════════════════════════════════
PROJECTS & INITIATIVES
═══════════════════════════════════════════════════════════════════════════════

Completed:
• [Project] - [Brief description] - [Impact]

In Progress:
• [Project] - [Status] - [Expected completion]

Planned:
• [Project] - [Timeline]

═══════════════════════════════════════════════════════════════════════════════
RECOMMENDATIONS
═══════════════════════════════════════════════════════════════════════════════

1. [Recommendation] - [Justification]
2. [Recommendation] - [Justification]
3. [Recommendation] - [Justification]

═══════════════════════════════════════════════════════════════════════════════
APPENDIX: DETAILED METRICS
═══════════════════════════════════════════════════════════════════════════════

[Additional charts and data as needed]
```

---

## 12. Appendices

### Appendix A: Ticket Workflow Process Map

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    DETAILED TICKET WORKFLOW                                 │
└─────────────────────────────────────────────────────────────────────────────┘

PHASE 1: INTAKE
═══════════════

User submits request
        │
        ├──▶ Helpdesk Portal (Self-Service)
        │    • User fills out form
        │    • System auto-categorizes
        │    • Ticket created automatically
        │    • Confirmation email sent
        │
        ├──▶ Email Support
        │    • Email received by system
        │    • Auto-reply sent to user
        │    • Ticket created by system
        │    • Routed to triage queue
        │
        ├──▶ Phone Support
        │    • User calls support line
        │    • Engineer answers and documents
        │    • Ticket created manually
        │    • Immediate triage if urgent
        │
        ├──▶ WhatsApp (Urgent)
        │    • Message received
        │    • On-call engineer notified
        │    • Ticket created manually
        │    • Marked as P1/P2 priority
        │
        └──▶ Walk-up
             • User visits support desk
             • Engineer documents issue
             • Ticket created manually
             • Attempt immediate resolution


PHASE 2: TRIAGE
═══════════════

Ticket enters queue
        │
        ▼
┌─────────────────┐
│ Auto-Triage     │
│ • Check keywords│
│ • Assign category│
│ • Set priority  │
│ • Route ticket  │
└────────┬────────┘
         │
         ▼
┌─────────────────┐     ┌─────────────────┐
│ Manual Review   │────▶│ Priority        │
│ (Within 15 min) │     │ Adjustment      │
│ • Verify category│    │ Needed?         │
│ • Confirm priority│   │                 │
│ • Check for     │     │ If yes: Adjust  │
│   duplicates    │     │ and document    │
└─────────────────┘     └─────────────────┘


PHASE 3: ASSIGNMENT & RESOLUTION
════════════════════════════════

Ticket Assigned
        │
        ▼
┌─────────────────────────────────────────────────────────────┐
│ TIER 1 ASSIGNMENT                                           │
│ • First-line support notified                               │
│ • Initial contact within SLA                                │
│ • Attempt resolution                                        │
└─────────────────────────────────────────────────────────────┘
        │
        ├──▶ RESOLVED ──▶ User Notification ──▶ User Verification ──▶ CLOSE
        │
        └──▶ ESCALATION REQUIRED
                    │
                    ▼
        ┌─────────────────────────────────────────────────────────────┐
        │ TIER 2 ASSIGNMENT                                           │
        │ • Technical engineer notified                               │
        │ • Review Tier 1 notes                                       │
        │ • Advanced troubleshooting                                  │
        │ • Escalate to Tier 3 if needed                              │
        └─────────────────────────────────────────────────────────────┘
                    │
                    ├──▶ RESOLVED ──▶ User Notification ──▶ CLOSE
                    │
                    └──▶ ESCALATION REQUIRED
                                │
                                ▼
                ┌─────────────────────────────────────────────────────────────┐
                │ TIER 3 ASSIGNMENT                                           │
                │ • Specialist notified                                       │
                │ • Deep technical analysis                                   │
                │ • Vendor engagement if needed                               │
                │ • Infrastructure changes                                    │
                └─────────────────────────────────────────────────────────────┘
                                │
                                ├──▶ RESOLVED ──▶ User Notification ──▶ CLOSE
                                │
                                └──▶ VENDOR ESCALATION
                                            │
                                            ▼
                                ┌─────────────────────────────┐
                                │ VENDOR ENGAGEMENT           │
                                │ • Vendor ticket created     │
                                │ • Internal coordination     │
                                │ • Progress tracking         │
                                │ • Resolution implementation │
                                └─────────────────────────────┘
                                            │
                                            ▼
                                RESOLVED ──▶ User Notification ──▶ CLOSE


PHASE 4: CLOSURE
═══════════════

Resolution Verified
        │
        ▼
┌─────────────────┐
│ Ticket Marked   │
│ "Resolved"      │
│ • Solution      │
│   documented    │
│ • Time logged   │
│ • User notified │
└────────┬────────┘
         │
         ▼
┌─────────────────┐     ┌─────────────────┐
│ 5-Day Window    │────▶│ User Response?  │
│ for User to     │     │                 │
│ Reopen          │     │ NO ──▶ CLOSE    │
└─────────────────┘     │                 │
                        │ YES ──▶ REOPEN  │
                        └─────────────────┘


PHASE 5: POST-CLOSURE
═════════════════════

Ticket Closed
        │
        ├──▶ Satisfaction Survey Sent (Automated)
        │
        ├──▶ Knowledge Base Update (If applicable)
        │
        ├──▶ Metrics Updated
        │
        └──▶ Reporting Data Captured
```

### Appendix B: SLA Response Matrix Reference

| Priority | Response Time | Update Frequency | Resolution Target | Escalation Point |
|----------|---------------|------------------|-------------------|------------------|
| P1 - Critical | 15 min | Every 30 min | 4 hours | IT Director at 2 hours |
| P2 - High | 1 hour | Every 2 hours | 8 hours | IT Manager at 4 hours |
| P3 - Medium | 4 hours | Daily | 24 hours | Team Lead at 24 hours |
| P4 - Low | 24 hours | Weekly | 72 hours | N/A |

### Appendix C: Escalation Contact List

| Level | Role | Name | Email | Phone (Internal) | Phone (Mobile) |
|-------|------|------|-------|------------------|----------------|
| 1 | On-Call Engineer | Rotating | oncall@smartdairy.com | 8888 | WhatsApp Group |
| 2 | IT Support Manager | TBD | it.manager@smartdairy.com | 8889 | +971-50-XXX-XXXX |
| 3 | IT Director | TBD | it.director@smartdairy.com | 8890 | +971-50-XXX-XXXX |
| Emergency | CEO Office | TBD | executive@smartdairy.com | 8900 | +971-50-XXX-XXXX |

### Appendix D: Communication Templates Quick Reference

**Template 1: Initial Acknowledgment**
```
Dear [Name],

We have received your support request (Ticket #[Number]). 
Priority: [Priority]
Expected response: [Timeframe]

We will update you on progress.

IT Support
```

**Template 2: Status Update**
```
Dear [Name],

Update on Ticket #[Number]:
Status: [Status]
Progress: [Details]
Next step: [Action]
ETA: [Time]

IT Support
```

**Template 3: Resolution**
```
Dear [Name],

Ticket #[Number] has been resolved.

Solution: [Brief description]

Please reply if you need further assistance.

IT Support
```

**Template 4: Escalation**
```
[ESCALATION] Ticket #[Number] - [Priority]

Issue: [Summary]
Impact: [Users affected]
Actions taken: [List]
Current status: [Status]
Escalated to: [Name]

[Name], [Title]
IT Support
```

### Appendix E: Category and Priority Assignment Guide

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    QUICK REFERENCE: PRIORITY ASSIGNMENT                     │
└─────────────────────────────────────────────────────────────────────────────┘

START: Assess the situation
        │
        ▼
┌────────────────────────────────────────┐
│ Q1: Is there a complete service outage │────YES────▶ P1 (Critical)
│     affecting ALL users?               │
└────────────────────────────────────────┘
        │ NO
        ▼
┌────────────────────────────────────────┐
│ Q2: Is a critical business system      │────YES────▶ P1 or P2
│     down OR is security compromised?   │             (Assess scope)
└────────────────────────────────────────┘
        │ NO
        ▼
┌────────────────────────────────────────┐
│ Q3: Is a single department OR          │────YES────▶ P2 (High)
│     multiple users affected?           │
└────────────────────────────────────────┘
        │ NO
        ▼
┌────────────────────────────────────────┐
│ Q4: Is a single user completely        │────YES────▶ P2 (High)
│     unable to work?                    │
└────────────────────────────────────────┘
        │ NO
        ▼
┌────────────────────────────────────────┐
│ Q5: Is a single user affected with     │────YES────▶ P3 (Medium)
│     a workaround available?            │
└────────────────────────────────────────┘
        │ NO
        ▼
┌────────────────────────────────────────┐
│ Q6: Is this a service request,         │────YES────▶ P4 (Low)
│     inquiry, or enhancement?           │
└────────────────────────────────────────┘

ADJUSTMENT FACTORS:
• Executive/Director affected → Increase by 1 level
• Revenue-impacting → Minimum P2
• Compliance-related → Minimum P2
• Public-facing issue → Increase by 1 level
• Workaround difficult → Increase by 1 level
```

### Appendix F: Vendor Contact Directory

| Vendor | Service | Support Portal | Phone | SLA |
|--------|---------|----------------|-------|-----|
| Microsoft | Office 365, Azure | https://admin.microsoft.com | 800-XXX-XXXX | 1 hour |
| Dell | Hardware Support | https://dell.com/support | 800-XXX-XXXX | 4 hours NBD |
| Cisco | Network Equipment | https://cisco.com/tac | 800-XXX-XXXX | 1 hour |
| [ERP Vendor] | ERP System | [Portal URL] | +971-X-XXX-XXXX | 4 hours |
| du/Etisalat | Internet Connectivity | N/A | 101 | 2 hours |
| [Security Vendor] | Security Solutions | [Portal URL] | +971-X-XXX-XXXX | 1 hour |

### Appendix G: Glossary of Terms

| Term | Definition |
|------|------------|
| **AD** | Active Directory - Microsoft's directory service for user and resource management |
| **FCR** | First Contact Resolution - Resolving an issue on the first interaction |
| **GST** | Gulf Standard Time - Time zone for UAE (UTC+4) |
| **IT** | Information Technology |
| **KB** | Knowledge Base - Repository of support articles |
| **MFA** | Multi-Factor Authentication - Security verification requiring multiple proofs |
| **NBD** | Next Business Day |
| **P1/P2/P3/P4** | Priority levels (Critical, High, Medium, Low) |
| **RMM** | Remote Monitoring and Management |
| **SLA** | Service Level Agreement - Defined response and resolution times |
| **SOP** | Standard Operating Procedure |
| **VPN** | Virtual Private Network - Secure remote access |

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | IT Support Manager | _________________ | _______________ |
| Reviewer | IT Director | _________________ | _______________ |
| Approver | CTO | _________________ | _______________ |

---

## Distribution List

- IT Support Team (All Members)
- IT Management Team
- Department Heads
- HR Department
- Document Control

---

**End of Document L-002: IT Support Procedures**

---

*This document is the property of Smart Dairy Ltd. Unauthorized distribution or modification is prohibited.*

*Next Review Date: July 31, 2026*
