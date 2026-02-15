# User Access Management Procedures

---

## Document Information

| Field | Value |
|-------|-------|
| **Document ID** | L-012 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | IAM Administrator |
| **Owner** | IAM Administrator |
| **Reviewer** | IT Director |
| **Classification** | Internal Use - Confidential |
| **Status** | Approved |

---

## Document History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | IAM Administrator | Initial document creation |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [User Lifecycle](#2-user-lifecycle)
3. [Access Request Process](#3-access-request-process)
4. [Role Definitions](#4-role-definitions)
5. [Password Policy](#5-password-policy)
6. [Access Reviews](#6-access-reviews)
7. [Privileged Access](#7-privileged-access)
8. [Service Accounts](#8-service-accounts)
9. [De-provisioning](#9-de-provisioning)
10. [Audit and Compliance](#10-audit-and-compliance)
11. [Tools and Systems](#11-tools-and-systems)
12. [Appendices](#12-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document establishes the comprehensive User Access Management (UAM) procedures for Smart Dairy Ltd. It defines the framework for managing user identities, controlling access to systems and data, and ensuring security compliance across all IT resources.

### 1.2 Scope

| Category | Inclusions |
|----------|------------|
| **Users** | All employees, contractors, vendors, partners, and service accounts |
| **Systems** | All applications, databases, servers, network devices, cloud services |
| **Data** | All company data classified as Public, Internal, Confidential, Restricted |
| **Environments** | Production, Staging, Development, Testing, DR |

### 1.3 IAM Principles

Smart Dairy Ltd adheres to the following identity and access management principles:

| Principle | Description |
|-----------|-------------|
| **Least Privilege** | Users receive minimum access necessary to perform their job functions |
| **Need-to-Know** | Access to sensitive data is granted only when business necessity exists |
| **Segregation of Duties** | Critical functions are divided among multiple users to prevent fraud |
| **Defence in Depth** | Multiple layers of security controls protect access to resources |
| **Zero Trust** | No implicit trust; all access requests are verified regardless of source |
| **Lifecycle Management** | Access is provisioned, modified, and revoked according to user lifecycle |

### 1.4 Compliance Requirements

| Standard | Requirement |
|----------|-------------|
| **ISO 27001** | A.9 - Access Control Management |
| **SOC 2** | CC6.1, CC6.2, CC6.3 - Logical Access Controls |
| **GDPR** | Article 32 - Security of Processing |
| **PCI DSS** | Requirement 8 - Identify and Authenticate Access |
| **UAE PDPL** | Data Protection and Access Controls |

### 1.5 Roles and Responsibilities

| Role | Responsibilities |
|------|------------------|
| **IAM Administrator** | Policy definition, role engineering, access provisioning, audit coordination |
| **IT Director** | Policy approval, exception authorization, security oversight |
| **HR Manager** | Employee lifecycle events, termination notifications |
| **Line Managers** | Access approvals, quarterly reviews, team access validation |
| **Data Owners** | Data access approvals, classification authority |
| **Security Team** | Policy enforcement, incident investigation, compliance monitoring |
| **End Users** | Password compliance, access request submission, report suspicious activity |

---

## 2. User Lifecycle

### 2.1 User Onboarding

#### 2.1.1 New Employee Onboarding

```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│   HR        │───▶│   IT        │───▶│  Manager    │───▶│    IAM      │
│ Notification│    │  Ticket      │    │  Approval   │    │ Provisioning│
└─────────────┘    └─────────────┘    └─────────────┘    └─────────────┘
       │                  │                  │                  │
       ▼                  ▼                  ▼                  ▼
  T-7 Days           T-5 Days           T-3 Days           T-1 Day
```

**Timeline:**

| Day | Action | Owner |
|-----|--------|-------|
| **T-7** | HR submits onboarding notification with role details | HR |
| **T-5** | IT creates onboarding ticket and reviews role requirements | IT |
| **T-3** | Line manager approves default role assignments | Manager |
| **T-1** | IAM provisions accounts and distributes credentials | IAM |
| **T-0** | Employee starts; receives access orientation | HR/IT |

#### 2.1.2 Onboarding Checklist

| Item | Description | Completed |
|------|-------------|-----------|
| ☐ | Active Directory account created | |
| ☐ | Email account provisioned (Exchange Online) | |
| ☐ | SSO enabled for applicable applications | |
| ☐ | VPN access configured (if required) | |
| ☐ | MFA enrollment completed | |
| ☐ | Default role assignments applied | |
| ☐ | Security awareness training scheduled | |
| ☐ | Access badge issued | |
| ☐ | User acknowledgment signed | |
| ☐ | Welcome email sent with access instructions | |

#### 2.1.3 Contractor/Third-Party Onboarding

| Step | Requirement | Duration |
|------|-------------|----------|
| 1 | Sponsor (internal employee) identification | N/A |
| 2 | Background verification (for critical systems) | 3-5 days |
| 3 | NDA and acceptable use policy signature | Before access |
| 4 | Limited access scope based on engagement | Engagement term |
| 5 | Sponsor responsible for access and actions | Engagement term |
| 6 | Automatic expiration date set on all accounts | End date |

### 2.2 Role Changes

#### 2.2.1 Internal Transfer Process

```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│  Transfer   │───▶│  Manager    │───▶│   IAM       │───▶│   Access    │
│  Request    │    │  Approval   │    │  Review     │    │  Migration  │
└─────────────┘    └─────────────┘    └─────────────┘    └─────────────┘
```

**Process Steps:**

| Step | Action | Timeline |
|------|--------|----------|
| 1 | HR submits transfer notification | T-14 days |
| 2 | Current manager provides handover checklist | T-10 days |
| 3 | New manager defines required access | T-7 days |
| 4 | IAM reviews and prepares migration plan | T-5 days |
| 5 | Old access revoked (non-transferable items) | T-1 day |
| 6 | New access provisioned | Effective date |
| 7 | Transfer verification with user | T+1 day |

#### 2.2.2 Role Change Access Matrix

| Change Type | Old Access | New Access | Action |
|-------------|------------|------------|--------|
| **Lateral Move** | Retain transferable | Add new role | Review and merge |
| **Promotion** | Retain existing | Add elevated permissions | Upgrade path |
| **Demotion** | Review for removal | Assign appropriate | Downgrade |
| **Department Change** | Remove old dept | Add new dept | Complete swap |
| **Temporary Assignment** | Retain primary | Add temporary | Set expiration |

### 2.3 User Offboarding

#### 2.3.1 Offboarding Triggers

| Trigger | Source | Response Time |
|---------|--------|---------------|
| Voluntary resignation | HR notification | Immediate |
| Involuntary termination | HR + Security notification | Immediate |
| Contract end | HR notification | 24 hours before |
| Long-term leave | HR notification | Based on policy |
| Death | HR notification | Immediate |

#### 2.3.2 Offboarding Timeline

```
HR Notification
      │
      ▼
┌─────────────┐
│ Immediate   │──▶ Disable AD Account
│  (0-15 min) │──▶ Revoke VPN Access
└─────────────┘──▶ Block Email (keep mailbox)
      │
      ▼
┌─────────────┐
│  Same Day   │──▶ Revoke Application Access
│   (4 hrs)   │──▶ Disable MFA Devices
└─────────────┘──▶ Return Hardware
      │
      ▼
┌─────────────┐
│  48 Hours   │──▶ Transfer Data Ownership
│             │──▶ Archive User Data
└─────────────┘──▶ Final Access Review
      │
      ▼
┌─────────────┐
│  30 Days    │──▶ Delete Account (if approved)
│             │──▶ Transfer Email Delegation
└─────────────┘
```

---

## 3. Access Request Process

### 3.1 Request Submission

#### 3.1.1 Access Request Types

| Request Type | Description | Approval Required |
|--------------|-------------|-------------------|
| **New Access** | First-time access to system/resource | Manager + Resource Owner |
| **Additional Access** | Extra permissions on existing system | Manager |
| **Elevated Access** | Privileged or administrative rights | Manager + IT Director |
| **Emergency Access** | Break-glass temporary access | Security Team |
| **Data Access** | Access to confidential/restricted data | Data Owner |
| **Shared Account** | Access to service/shared accounts | IAM Admin + Security |

#### 3.1.2 Access Request Form

See [Appendix A: Access Request Form](#appendix-a-access-request-form)

#### 3.1.3 Request Channels

| Channel | Use Case | SLA |
|---------|----------|-----|
| **IT Service Portal** | Standard access requests | 2 business days |
| **Email (emergency@smartdairy.ae)** | Emergency access | 2 hours |
| **Phone (+971-XXX-XXXX)** | Urgent situations | 30 minutes |
| **HR System Integration** | Onboarding workflows | 1 business day |

### 3.2 Approval Workflow

#### 3.2.1 Approval Hierarchy

```
                    ┌─────────────────┐
                    │  Request        │
                    │  Submitted      │
                    └────────┬────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │  Line Manager   │
                    │  Approval       │
                    └────────┬────────┘
                             │
              ┌──────────────┼──────────────┐
              │              │              │
              ▼              ▼              ▼
       ┌──────────┐   ┌──────────┐   ┌──────────┐
       │ Standard │   │ Elevated │   │  Data    │
       │  Access  │   │  Access  │   │  Access  │
       └────┬─────┘   └────┬─────┘   └────┬─────┘
            │              │              │
            ▼              ▼              ▼
       ┌──────────┐   ┌──────────┐   ┌──────────┐
       │  IAM     │   │  IT      │   │  Data    │
       │Provision │   │ Director │   │  Owner   │
       └──────────┘   └────┬─────┘   └────┬─────┘
                           │              │
                           └──────┬───────┘
                                  ▼
                           ┌──────────┐
                           │  IAM     │
                           │Provision │
                           └──────────┘
```

#### 3.2.2 Approval Requirements by Access Type

| Access Type | Primary Approver | Secondary Approver | SLA |
|-------------|------------------|-------------------|-----|
| Basic System Access | Line Manager | N/A | 1 day |
| Department Application | Line Manager | App Owner | 2 days |
| Cross-Department Data | Line Manager | Data Owner | 3 days |
| Administrative Rights | Line Manager | IT Director | 5 days |
| Production Database | Line Manager + DBA Manager | IT Director | 5 days |
| Financial Systems | Line Manager | CFO/Finance Director | 3 days |
| Emergency Access | Security Team | (Post-hoc) | 30 minutes |

#### 3.2.3 Approval SLAs

| Approver Level | Standard SLA | Escalation SLA |
|----------------|--------------|----------------|
| Line Manager | 24 hours | 48 hours (to Director) |
| Application Owner | 48 hours | 72 hours (to IT Director) |
| Data Owner | 48 hours | 72 hours (to CISO) |
| IT Director | 72 hours | 96 hours (to CEO for critical) |
| Security Team | 2 hours (emergency) | N/A |

### 3.3 Provisioning

#### 3.3.1 Provisioning Process

```
Approved Request
      │
      ▼
┌─────────────────────────────────────┐
│  1. Ticket Created in ServiceNow   │
└─────────────┬───────────────────────┘
              │
              ▼
┌─────────────────────────────────────┐
│  2. IAM Reviews Request Details    │
│     - Validate approval chain       │
│     - Check role compatibility      │
│     - Verify SoD compliance         │
└─────────────┬───────────────────────┘
              │
              ▼
┌─────────────────────────────────────┐
│  3. Automated/Manual Provisioning  │
│     - AD Group Assignment           │
│     - Application Provisioning      │
│     - Email Distribution Lists      │
└─────────────┬───────────────────────┘
              │
              ▼
┌─────────────────────────────────────┐
│  4. Verification & Testing         │
│     - Access validation             │
│     - Functional testing            │
└─────────────┬───────────────────────┘
              │
              ▼
┌─────────────────────────────────────┐
│  5. User Notification              │
│     - Access granted confirmation   │
│     - Usage guidelines              │
└─────────────────────────────────────┘
```

#### 3.3.2 Provisioning SLAs

| Access Type | Provisioning SLA | Method |
|-------------|------------------|--------|
| AD/Email/SSO | 4 hours | Automated |
| SaaS Applications | 24 hours | Semi-automated |
| Custom Applications | 48 hours | Manual |
| Database Access | 48 hours | Manual + Review |
| Network Infrastructure | 72 hours | Manual + Security |
| Privileged Access | 72 hours | PAM Workflow |

---

## 4. Role Definitions

### 4.1 System Roles

#### 4.1.1 Active Directory Role Groups

| Role Group | Description | Permissions |
|------------|-------------|-------------|
| **AD-Users-Standard** | All employees | Basic domain access, password self-service |
| **AD-Users-Remote** | Remote workers | VPN access, additional security requirements |
| **AD-Admins-Workstation** | Desktop support | Workstation management, software deployment |
| **AD-Admins-Server** | Server administrators | Server management, patch deployment |
| **AD-Admins-Domain** | Domain administrators | Full domain control (break-glass only) |

#### 4.1.2 Infrastructure Roles

| Role | Scope | Access Level |
|------|-------|--------------|
| **Network-Operator** | Network monitoring | Read-only monitoring tools |
| **Network-Admin** | Network configuration | Configuration change rights |
| **Cloud-Operator** | AWS/Azure monitoring | Read-only console access |
| **Cloud-Admin** | Cloud infrastructure | Full cloud management |
| **Backup-Operator** | Backup systems | Backup execution and restore |
| **Security-Analyst** | SIEM, security tools | Security monitoring and response |

### 4.2 Application Roles

#### 4.2.1 ERP System Roles

| Role | Business Function | Permissions |
|------|-------------------|-------------|
| **ERP-User-View** | All staff | Read-only access to relevant modules |
| **ERP-User-Transaction** | Operational staff | Create/update transactions in scope |
| **ERP-Manager-Approve** | Department managers | Approval workflows, reports |
| **ERP-Admin-Config** | System administrators | Configuration and customization |
| **ERP-Admin-Super** | IT Director | Full system access |

#### 4.2.2 Smart Dairy Application Roles

| Role | Description | Access |
|------|-------------|--------|
| **SD-Farmer-View** | Dairy farmers | View own farm data, reports |
| **SD-Farmer-Manage** | Farm managers | Manage farm operations, IoT devices |
| **SD-Analytics-User** | Business analysts | Analytics dashboards, reports |
| **SD-Analytics-Admin** | Data team | Data management, ETL operations |
| **SD-Support-L1** | Helpdesk | View customer data, basic troubleshooting |
| **SD-Support-L2** | Technical support | Advanced diagnostics, system access |
| **SD-Developer** | Development team | Development environment, code repository |
| **SD-DevOps** | DevOps engineers | CI/CD, infrastructure management |
| **SD-Admin** | System administrators | Full application administration |

### 4.3 Data Access Levels

#### 4.3.1 Data Classification Matrix

| Classification | Description | Access Control |
|----------------|-------------|----------------|
| **Public** | Marketing materials, published reports | No restrictions |
| **Internal** | Company policies, operational documents | Authentication required |
| **Confidential** | Customer data, financial reports | Role-based + manager approval |
| **Restricted** | Trade secrets, security configs | Need-to-know + data owner approval |

#### 4.3.2 Data Access Roles by Department

| Department | Internal | Confidential | Restricted |
|------------|----------|--------------|------------|
| **Finance** | All staff | Finance team | CFO, Finance Director |
| **HR** | All staff | HR team | HR Director |
| **Sales** | All staff | Sales team | Sales Director |
| **Operations** | All staff | Operations team | Operations Director |
| **IT** | All staff | IT team | IT Director, CISO |
| **Legal** | All staff | Legal team | General Counsel |

### 4.4 Role Matrix

See [Appendix C: Role Matrix](#appendix-c-role-matrix)

---

## 5. Password Policy

### 5.1 Complexity Requirements

#### 5.1.1 Standard User Passwords

| Requirement | Specification |
|-------------|---------------|
| **Minimum Length** | 12 characters |
| **Maximum Length** | 128 characters |
| **Character Types** | Uppercase, lowercase, numbers, special characters |
| **Minimum Types** | 3 of 4 character types |
| **Dictionary Check** | No common passwords or dictionary words |
| **Username Check** | Password cannot contain username |
| **History** | Last 12 passwords cannot be reused |
| **Maximum Age** | 90 days |
| **Lockout Threshold** | 5 failed attempts |
| **Lockout Duration** | 15 minutes (auto-reset) |

#### 5.1.2 Privileged Account Passwords

| Requirement | Specification |
|-------------|---------------|
| **Minimum Length** | 16 characters |
| **Character Types** | All 4 types required |
| **Passphrase Support** | Encouraged (e.g., "Correct-Horse-Battery-Staple!") |
| **Maximum Age** | 60 days |
| **History** | Last 24 passwords cannot be reused |
| **Lockout Threshold** | 3 failed attempts |
| **Lockout Duration** | Manual unlock required |
| **PAM Integration** | All passwords stored in PAM vault |

### 5.2 Password Rotation Schedule

| Account Type | Rotation Frequency | Method |
|--------------|-------------------|--------|
| Standard User | 90 days | User-initiated |
| Administrative | 60 days | Enforced by system |
| Service Accounts | 90 days | Automated rotation |
| Emergency Accounts | After each use | Immediate reset |
| Shared Accounts | 30 days | PAM-managed rotation |
| API Keys | 180 days | Automated with overlap |

### 5.3 Multi-Factor Authentication (MFA)

#### 5.3.1 MFA Requirements

| System/Access Type | MFA Required | Methods |
|-------------------|--------------|---------|
| **All external access** | Yes | Microsoft Authenticator, SMS, Hardware Token |
| **VPN Access** | Yes | Microsoft Authenticator preferred |
| **Email (external)** | Yes | App-based authentication |
| **Privileged accounts** | Yes | Hardware token mandatory |
| **Cloud admin portals** | Yes | Hardware token mandatory |
| **Internal network** | Recommended | Optional enforcement |

#### 5.3.2 MFA Enrollment Process

```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│   Account   │───▶│   MFA       │───▶│   Primary   │───▶│  Backup     │
│  Created    │    │   Prompt    │    │   Method    │    │  Method     │
└─────────────┘    └─────────────┘    └─────────────┘    └─────────────┘
       │                  │                  │                  │
       ▼                  ▼                  ▼                  ▼
  First Login      Setup Required    Microsoft Auth     Phone Number
```

#### 5.3.3 MFA Recovery

| Scenario | Recovery Method | Verification |
|----------|-----------------|--------------|
| Lost phone | Backup codes | Identity verification with HR |
| New phone | Re-enrollment | Manager confirmation + IT verification |
| Travel without phone | Temporary bypass | Security team approval (8-hour limit) |

---

## 6. Access Reviews

### 6.1 Quarterly Reviews

#### 6.1.1 Review Schedule

| Quarter | Review Period | Completion Deadline |
|---------|---------------|---------------------|
| Q1 | January 1 - March 31 | April 15 |
| Q2 | April 1 - June 30 | July 15 |
| Q3 | July 1 - September 30 | October 15 |
| Q4 | October 1 - December 31 | January 15 |

#### 6.1.2 Review Scope

| Review Type | Frequency | Coverage |
|-------------|-----------|----------|
| **User Access Review** | Quarterly | All user accounts and permissions |
| **Privileged Access Review** | Quarterly | All admin and elevated accounts |
| **Service Account Review** | Quarterly | All service accounts and owners |
| **Application Access Review** | Semi-annual | Per-application user lists |
| **Data Access Review** | Annual | Access to confidential/restricted data |
| **Third-Party Access Review** | Quarterly | All vendor and contractor accounts |

### 6.2 Manager Certification

#### 6.2.1 Manager Review Process

```
┌─────────────────────────────────────────────────────────────────┐
│  1. IAM Generates Review Reports                                │
│     - Extract current access for all team members              │
│     - Identify anomalies and SoD violations                     │
│     - Flag inactive accounts                                    │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  2. Manager Review                                              │
│     - Receive notification email                                │
│     - Access review portal                                      │
│     - Validate each user's access                               │
│     - Mark accounts for revocation/modification                 │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  3. IAM Processing                                              │
│     - Process approved access                                   │
│     - Revoke marked permissions                                 │
│     - Generate completion report                                │
└─────────────────────────────────────────────────────────────────┘
```

#### 6.2.2 Manager Review SLA

| Action | Timeline | Escalation |
|--------|----------|------------|
| Review notification sent | Start of review period | - |
| First reminder | Day 7 | - |
| Second reminder | Day 14 | CC: Department Head |
| Escalation | Day 21 | To Department Head |
| Final notice | Day 25 | To IT Director |
| Auto-revocation consideration | Day 30 | Security review |

### 6.3 Automated Reports

#### 6.3.1 Standard Reports

| Report | Frequency | Recipients | Content |
|--------|-----------|------------|---------|
| **Inactive Accounts** | Weekly | IAM Admin, Security | Accounts inactive 30+ days |
| **Password Expiration** | Weekly | Managers, Users | Expiring passwords (7 days) |
| **Failed Login Attempts** | Daily | Security Team | Brute force detection |
| **Privileged Access Log** | Daily | Security Team, IT Director | All privileged access events |
| **Access Provisioning** | Weekly | IAM Admin, Auditors | All access grants and changes |
| **SoD Violations** | Weekly | Compliance, IAM Admin | Segregation of duty conflicts |

#### 6.3.2 Access Review Template

See [Appendix D: Access Review Template](#appendix-d-access-review-template)

---

## 7. Privileged Access

### 7.1 Privileged Access Management (PAM) System

#### 7.1.1 PAM Overview

| Component | Description |
|-----------|-------------|
| **Password Vault** | Secure storage for all privileged credentials |
| **Session Manager** | Controlled access with session recording |
| **Workflow Engine** | Request and approval workflows for privileged access |
| **Discovery Tool** | Automated discovery of privileged accounts |
| **Analytics** | Usage patterns and anomaly detection |

#### 7.1.2 PAM Enrollment

| Account Type | PAM Requirement | Check-out Duration |
|--------------|-----------------|-------------------|
| Domain Administrator | Mandatory | 4 hours maximum |
| Server Administrator | Mandatory | 8 hours maximum |
| Database Administrator | Mandatory | Session-based |
| Application Administrator | Mandatory | Session-based |
| Cloud Administrator | Mandatory | 4 hours maximum |
| Emergency/Break-glass | Mandatory | 2 hours maximum |

### 7.2 Break-Glass Procedures

#### 7.2.1 Emergency Access Scenarios

| Scenario | Authorized Users | Approval |
|----------|------------------|----------|
| **PAM System Failure** | IT Director, CISO | Mutual agreement |
| **Critical System Outage** | Senior IT staff | Security team notification |
| **Security Incident Response** | Incident Response Team | Post-incident review |
| **After-Hours Emergency** | On-call engineer | Manager notification within 2 hours |

#### 7.2.2 Break-Glass Process

```
Emergency Detected
      │
      ▼
┌───────────────────────────────────────────┐
│  1. Obtain Emergency Credentials          │
│     - Physical safe (IT Director office)  │
│     - Split knowledge (2-of-3 controls)   │
│     - Register use with Security          │
└─────────────┬─────────────────────────────┘
              │
              ▼
┌───────────────────────────────────────────┐
│  2. Use Credentials                       │
│     - Time-limited (2 hours)              │
│     - All actions logged                  │
│     - Security team notified              │
└─────────────┬─────────────────────────────┘
              │
              ▼
┌───────────────────────────────────────────┐
│  3. Post-Emergency                        │
│     - Change all used credentials         │
│     - Document actions taken              │
│     - Security review within 24 hours     │
└───────────────────────────────────────────┘
```

### 7.3 Session Recording

#### 7.3.1 Recording Scope

| Access Type | Recording | Retention |
|-------------|-----------|-----------|
| Domain Administrator | Full session | 2 years |
| Server Administrator | Full session | 1 year |
| Database Administrator | Full session | 1 year |
| Production Changes | Full session | 2 years |
| Emergency Access | Full session | 3 years |
| Regular privileged access | Full session | 1 year |

#### 7.3.2 Recording Standards

| Element | Requirement |
|---------|-------------|
| **Screen Capture** | Continuous video recording |
| **Keystroke Logging** | All commands typed |
| **File Transfers** | All uploads and downloads |
| **Command Output** | All command responses |
| **Metadata** | Timestamp, user, source IP, target system |

---

## 8. Service Accounts

### 8.1 Service Account Creation

#### 8.1.1 Creation Process

```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│  Technical  │───▶│  Service    │───▶│  Security   │───▶│   IAM       │
│  Justification│   │  Owner Ident│    │  Review     │    │  Creation   │
└─────────────┘    └─────────────┘    └─────────────┘    └─────────────┘
       │                  │                  │                  │
       ▼                  ▼                  ▼                  ▼
  Application        Named Owner         Risk Assessment    Vault Storage
  Requirement        (Individual)        Check              PAM Integration
```

#### 8.1.2 Service Account Requirements

| Requirement | Description |
|-------------|-------------|
| **Naming Convention** | `svc_[application]_[function]_[env]` |
| **Dedicated Owner** | Named individual responsible |
| **Documentation** | Purpose, permissions, dependencies |
| **Credential Storage** | PAM vault only |
| **Monitoring** | Login alerts, usage tracking |
| **Expiration** | Maximum 1 year before review |
| **No Interactive Login** | Service accounts cannot be used interactively |

### 8.2 Service Account Rotation

#### 8.2.1 Rotation Schedule

| Account Type | Rotation Frequency | Method |
|--------------|-------------------|--------|
| High-risk (domain, cloud admin) | 30 days | Automated |
| Standard service accounts | 90 days | Automated |
| Application-specific | 180 days | Semi-automated |
| Legacy system accounts | 90 days | Manual |

#### 8.2.2 Rotation Process

```
1. Generate New Credential (PAM)
           │
           ▼
2. Update Application Configuration
   (Coordinated with application owner)
           │
           ▼
3. Test Application Functionality
   (Validation period: 24 hours)
           │
           ▼
4. Revoke Old Credential
   (Automated after validation)
           │
           ▼
5. Update Documentation
   (Audit trail maintenance)
```

### 8.3 Service Account Monitoring

#### 8.3.1 Monitoring Controls

| Control | Frequency | Action on Anomaly |
|---------|-----------|-------------------|
| **Login Time Analysis** | Real-time | Alert if outside business hours |
| **Source IP Monitoring** | Real-time | Alert if from unexpected location |
| **Usage Pattern Analysis** | Daily | Alert on volume spikes |
| **Failed Authentication** | Real-time | Account lockout after threshold |
| **Owner Verification** | Quarterly | Confirm owner still valid |
| **Dependency Mapping** | Quarterly | Verify all integrations documented |

---

## 9. De-provisioning

### 9.1 Termination Process

#### 9.1.1 Termination Types and Response

| Termination Type | Notice | Access Action |
|------------------|--------|---------------|
| **Voluntary - Standard** | 30 days | Standard offboarding |
| **Voluntary - Immediate** | Same day | Immediate revocation |
| **Involuntary - With Cause** | Same day | Immediate revocation + forensics hold |
| **Involuntary - Without Cause** | As notified | Standard or immediate based on risk |
| **Contract End** | Per contract | Scheduled de-provisioning |
| **Death** | Immediate | Immediate lockdown, data preservation |

#### 9.1.2 Immediate Revocation Procedure

| Step | Action | Owner | Timeline |
|------|--------|-------|----------|
| 1 | Receive termination notice | HR/Security | T+0 |
| 2 | Disable AD account | IAM Admin | T+0 to T+15 min |
| 3 | Block VPN access | Network Team | T+0 to T+15 min |
| 4 | Revoke MFA tokens | IAM Admin | T+0 to T+15 min |
| 5 | Disable email access | Exchange Admin | T+0 to T+30 min |
| 6 | Revoke application access | IAM Admin | T+0 to T+1 hour |
| 7 | Collect physical assets | HR/Facilities | T+0 to T+4 hours |
| 8 | Forensic image (if required) | Security | T+0 to T+24 hours |
| 9 | Data transfer/archive | IAM Admin | T+1 to T+48 hours |
| 10 | Final access verification | Security | T+48 hours |

### 9.2 Data Transfer

#### 9.2.1 Data Transfer Responsibilities

| Data Type | Transfer Method | Receiving Party |
|-----------|-----------------|-----------------|
| **Work Documents** | Copy to shared folder | Manager/Designee |
| **Email** | Delegate access to manager | Manager |
| **Application Data** | Export and transfer | Department designee |
| **Customer Relationships** | CRM transfer | Sales Manager |
| **Project Files** | Project handover | Project Manager |
| **Personal Data** | Employee copy | HR (upon request) |

#### 9.2.2 Data Preservation

| Data Type | Preservation Period | Location |
|-----------|---------------------|----------|
| **Email** | 7 years | Archive mailbox |
| **Work Files** | Per retention policy | Shared archive |
| **Application Data** | Per application policy | Database backups |
| **Audit Logs** | 3 years | SIEM/Log storage |
| **Communication Records** | 3 years | Compliance archive |

### 9.3 Access Revocation

#### 9.3.1 Revocation Checklist

See [Appendix E: Offboarding Checklist](#appendix-e-offboarding-checklist)

#### 9.3.2 Post-Revocation Verification

| Verification Item | Method | Frequency |
|-------------------|--------|-----------|
| **AD Account Status** | Active Directory query | Daily |
| **VPN Access** | Firewall rule verification | Daily |
| **Email Access** | Exchange Admin Center | Daily |
| **Application Access** | Identity governance report | Weekly |
| **Shared Account Membership** | Group membership audit | Weekly |
| **Physical Access** | Badge deactivation | Immediate |

---

## 10. Audit and Compliance

### 10.1 Audit Trail Requirements

#### 10.1.1 Logged Events

| Event Type | Data Captured | Retention |
|------------|---------------|-----------|
| **Authentication Events** | User, time, source IP, result, MFA status | 2 years |
| **Access Requests** | Requestor, resource, approvers, timestamps | 7 years |
| **Access Grants** | User, resource, grantor, timestamp, duration | 7 years |
| **Access Modifications** | User, resource, change type, authorizer | 7 years |
| **Access Revocations** | User, resource, reason, timestamp | 7 years |
| **Privileged Access** | Full session recording | 2 years |
| **Password Changes** | User, timestamp, method | 2 years |
| **Role Changes** | User, old role, new role, authorizer | 7 years |

#### 10.1.2 Log Protection

| Control | Implementation |
|---------|----------------|
| **Immutable Storage** | Write-once storage for audit logs |
| **Encryption** | AES-256 encryption at rest and in transit |
| **Access Control** | Only Security and Compliance teams |
| **Integrity Verification** | Digital signatures on log files |
| **Backup** | Real-time replication to secondary location |
| **Retention Enforcement** | Automated retention policy management |

### 10.2 Compliance Reporting

#### 10.2.1 Regular Compliance Reports

| Report | Frequency | Audience | Purpose |
|--------|-----------|----------|---------|
| **Access Governance Dashboard** | Monthly | IT Management | Overall access health |
| **Privileged Access Report** | Monthly | IT Director, CISO | Privileged account monitoring |
| **SoD Compliance Report** | Quarterly | Compliance, Auditors | Segregation of duties |
| **User Lifecycle Report** | Quarterly | HR, IT | Onboarding/offboarding metrics |
| **Third-Party Access Report** | Quarterly | Security, Legal | External user access |
| **Access Review Compliance** | Quarterly | Compliance | Review completion rates |
| **Annual IAM Assessment** | Annual | Executive, Board | Strategic IAM review |

#### 10.2.2 Audit Response

| Audit Type | Preparation Timeline | Key Documentation |
|------------|---------------------|-------------------|
| **Internal Audit** | 2 weeks | Process docs, sample access records |
| **External Audit (SOC 2)** | 4 weeks | 12 months of evidence, policies |
| **ISO 27001 Audit** | 4 weeks | Full ISMS documentation, access logs |
| **Regulatory Audit** | As notified | Specific to regulator requirements |

### 10.3 Exception Management

#### 10.3.1 Exception Categories

| Category | Description | Approval |
|----------|-------------|----------|
| **Temporary Access** | Short-term elevated access | Manager + Resource Owner |
| **Policy Deviation** | Non-standard access configuration | IT Director |
| **Compensating Controls** | Alternative controls for non-compliance | CISO |
| **Emergency Override** | Bypass normal process | Security Team (post-hoc review) |

#### 10.3.2 Exception Process

```
Exception Request
       │
       ▼
Business Justification
       │
       ▼
Risk Assessment (Security)
       │
       ▼
Compensating Controls Identification
       │
       ▼
Approval (IT Director/CISO)
       │
       ▼
Time-limited Implementation
       │
       ▼
Regular Review (30/60/90 days)
```

---

## 11. Tools and Systems

### 11.1 IAM Infrastructure

| System | Vendor | Purpose | Integration |
|--------|--------|---------|-------------|
| **Microsoft Entra ID** | Microsoft | Identity provider, SSO | Primary IdP |
| **Active Directory** | Microsoft | On-premises identity | Sync with Entra ID |
| **CyberArk** | CyberArk | Privileged Access Management | All privileged accounts |
| **ServiceNow** | ServiceNow | Access request workflow | IAM integration |
| **Okta** | Okta | SSO and lifecycle management | Cloud applications |
| **Microsoft Defender for Identity** | Microsoft | Identity threat detection | AD integration |

### 11.2 System Owners

| System | Technical Owner | Business Owner |
|--------|-----------------|----------------|
| Microsoft Entra ID | IAM Administrator | IT Director |
| Active Directory | IAM Administrator | IT Director |
| CyberArk PAM | Security Team Lead | CISO |
| ServiceNow IAM | IAM Administrator | IT Director |
| MFA Infrastructure | IAM Administrator | Security Team Lead |

### 11.3 Integration Architecture

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         Identity Providers                              │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐              │
│  │    Entra ID  │◄──►│      AD      │◄──►│    Okta      │              │
│  │   (Primary)  │    │  (On-prem)   │    │  (Cloud Apps)│              │
│  └──────┬───────┘    └──────────────┘    └──────┬───────┘              │
└─────────┼────────────────────────────────────────┼──────────────────────┘
          │                                        │
          ▼                                        ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                      Access Management Layer                            │
│  ┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐     │
│  │     PAM         │    │   SSO Portal    │    │   MFA Service   │     │
│  │   (CyberArk)    │    │  (Entra ID)     │    │ (Entra ID MFA)  │     │
│  └────────┬────────┘    └────────┬────────┘    └─────────────────┘     │
└───────────┼──────────────────────┼──────────────────────────────────────┘
            │                      │
            ▼                      ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                      Target Systems                                     │
│  ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐     │
│  │   ERP  │ │  SD App│ │   AWS  │ │ Azure  │ │ Network│ │  Email │     │
│  └────────┘ └────────┘ └────────┘ └────────┘ └────────┘ └────────┘     │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 12. Appendices

### Appendix A: Access Request Form

```
=============================================================================
                    SMART DAIRY LTD
              ACCESS REQUEST FORM
=============================================================================

Document ID: ARF-001                          Date: _______________

SECTION 1: REQUESTER INFORMATION
-----------------------------------------------------------------------------
Name: _________________________________  Employee ID: _______________
Department: ___________________________  Job Title: __________________
Manager: ______________________________  Email: _____________________
Phone: ________________________________  Location: __________________

Request Type: [ ] New Access  [ ] Additional Access  [ ] Elevated Access
              [ ] Emergency   [ ] Data Access        [ ] Shared Account

SECTION 2: ACCESS DETAILS
-----------------------------------------------------------------------------
System/Application: ___________________________________________________

Environment: [ ] Production  [ ] Staging  [ ] Development  [ ] Test  [ ] DR

Requested Role/Permission Level:
________________________________________________________________________

Business Justification:
________________________________________________________________________
________________________________________________________________________
________________________________________________________________________

Data Classification Access Required:
[ ] Public  [ ] Internal  [ ] Confidential  [ ] Restricted

Duration: [ ] Permanent  [ ] Temporary until: ___________________

SECTION 3: APPROVALS
-----------------------------------------------------------------------------

Line Manager Approval:
Name: _________________________________  Date: _______________
Signature: ____________________________

Additional Approvals (if required):

Data Owner Approval:
Name: _________________________________  Date: _______________
Signature: ____________________________

IT Director Approval (for elevated access):
Name: _________________________________  Date: _______________
Signature: ____________________________

SECTION 4: IAM PROCESSING
-----------------------------------------------------------------------------
Received by: __________________________  Date: _______________

Review Checklist:
[ ] Approval chain validated
[ ] SoD compliance verified
[ ] Role mapping confirmed
[ ] Security policy compliance checked

Provisioning Details:
Account Created/Modified: _______________  By: ______________________

Access Granted:
[ ] Active Directory Groups: _______________________________________
[ ] Application Access: ____________________________________________
[ ] VPN Access: ____________________________________________________
[ ] MFA Enrollment: ________________________________________________
[ ] Other: _________________________________________________________

User Notification Sent: _________________  By: ______________________

SECTION 5: ACKNOWLEDGMENT
-----------------------------------------------------------------------------
I acknowledge that I have received access to the systems listed above and 
understand my responsibilities regarding acceptable use, data protection, 
and security policies.

User Signature: _________________________  Date: _______________

=============================================================================
                         INTERNAL USE ONLY
=============================================================================
Ticket Reference: _______________________
Audit Log ID: __________________________
```

### Appendix B: User Provisioning Workflow

```
┌─────────────────────────────────────────────────────────────────────────┐
│                        USER PROVISIONING WORKFLOW                       │
└─────────────────────────────────────────────────────────────────────────┘

PHASE 1: REQUEST (Day -7)
┌─────────────┐
│  HR System  │──► New Employee Notification
│   Trigger   │    - Position details
└─────────────┘    - Start date
      │            - Department/Manager
      ▼
┌─────────────┐
│    IAM      │──► Review and Validate
│   Review    │    - Check role templates
└─────────────┘    - Identify requirements
      │
      ▼

PHASE 2: APPROVAL (Day -5 to -3)
┌─────────────┐
│   Manager   │──► Approve Default Access
│  Approval   │    - Review role assignments
└─────────────┘    - Add special requirements
      │
      ▼
┌─────────────┐
│  Data Owner │──► Approve Data Access (if needed)
│  Approval   │
└─────────────┘
      │
      ▼

PHASE 3: PROVISIONING (Day -2 to -1)
┌─────────────────────────────────────────┐
│  Active Directory                       │
│  ├── Create user account               │
│  ├── Set initial password              │
│  ├── Assign groups                     │
│  └── Configure attributes              │
└─────────────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────┐
│  Email System (Exchange Online)        │
│  ├── Create mailbox                    │
│  ├── Set quota and retention           │
│  └── Add to distribution lists         │
└─────────────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────┐
│  SSO/Cloud Applications                │
│  ├── Entra ID license assignment       │
│  ├── Application provisioning          │
│  └── MFA enrollment setup              │
└─────────────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────┐
│  Additional Systems                    │
│  ├── VPN access                        │
│  ├── Physical access badge             │
│  └── Specialized applications          │
└─────────────────────────────────────────┘
      │
      ▼

PHASE 4: NOTIFICATION (Day -1)
┌─────────────┐
│  Welcome    │──► Email to new employee
│   Email     │    - Login instructions
└─────────────┘    - First-day checklist
      │            - IT contact info
      ▼
┌─────────────┐
│  Manager    │──► Access confirmation
│ Notification│    - Provisioning complete
└─────────────┘
      │
      ▼

PHASE 5: FIRST DAY (Day 0)
┌─────────────┐
│   IT        │──► Meet with new employee
│  Welcome    │    - Password reset
└─────────────┘    - MFA enrollment
      │            - Security briefing
      ▼
┌─────────────┐
│  Access     │──► Verify all access working
│ Verification│    - Document any issues
└─────────────┘    - Close provisioning ticket

```

### Appendix C: Role Matrix

| Role | AD | Email | VPN | ERP | SD App | Finance | HR | Cloud |
|------|:--:|:-----:|:---:|:---:|:------:|:-------:|:--:|:-----:|
| **Executive** | ✓ | ✓ | ✓ | R | R | R | R | R |
| **Finance-Staff** | ✓ | ✓ | ✓ | RW | - | RW | - | - |
| **Finance-Manager** | ✓ | ✓ | ✓ | RW+A | R | RW+A | - | R |
| **HR-Staff** | ✓ | ✓ | ✓ | R | - | - | RW | - |
| **HR-Manager** | ✓ | ✓ | ✓ | RW | - | R | RW+A | R |
| **Sales-Rep** | ✓ | ✓ | ✓ | RW | R | - | - | - |
| **Sales-Manager** | ✓ | ✓ | ✓ | RW+A | R | R | - | R |
| **Ops-Staff** | ✓ | ✓ | ✓ | RW | RW | - | - | - |
| **Ops-Manager** | ✓ | ✓ | ✓ | RW+A | RW+A | R | R | R |
| **IT-Helpdesk** | ✓ | ✓ | ✓ | R | R | - | - | R |
| **IT-Admin** | ✓A | ✓ | ✓ | RW | RW | R | R | RWA |
| **Developer** | ✓ | ✓ | ✓ | R | RWA | - | - | RWA |
| **Contractor** | ✓ | ✓ | ✓* | R* | R* | - | - | R* |

**Legend:**
- ✓ = Standard access
- ✓A = Administrative access
- R = Read access
- RW = Read/Write access
- RWA = Read/Write/Admin access
- A = Approval workflow
- * = Limited/time-bound
- - = No access

### Appendix D: Access Review Template

```
=============================================================================
                    SMART DAIRY LTD
              QUARTERLY ACCESS REVIEW
=============================================================================

Review Period: Q__ 20__                    Review Date: _______________
Manager Name: ____________________________ Department: ________________

INSTRUCTIONS:
• Review each user's access listed below
• Verify each system access is still required for their current role
• Mark status: [✓] Keep  [✗] Remove  [~] Modify  [?] Review with user
• Add comments for any modifications needed

-----------------------------------------------------------------------------
USER 1: _________________________________ (Employee ID: _______________)
-----------------------------------------------------------------------------
Current Role: ____________________________
Last Review: _______________

| System/Access | Current Level | Status | Comments |
|---------------|---------------|--------|----------|
| Active Directory | Standard User | [ ] | |
| Email | Standard Mailbox | [ ] | |
| VPN | Remote Access | [ ] | |
| ERP System | _____________ | [ ] | |
| Smart Dairy App | _____________ | [ ] | |
| Finance System | _____________ | [ ] | |
| HR System | _____________ | [ ] | |
| Cloud Console | _____________ | [ ] | |
| Shared Folders | _____________ | [ ] | |
| Other: ________ | _____________ | [ ] | |

Manager Notes: ____________________________________________________________

-----------------------------------------------------------------------------
USER 2: _________________________________ (Employee ID: _______________)
-----------------------------------------------------------------------------
[Same structure as above]

=============================================================================
SUMMARY AND CERTIFICATION
=============================================================================

Total Users Reviewed: _______
Access Removals Requested: _______
Access Modifications Requested: _______

I certify that I have reviewed the access for all team members listed above
and confirm that continued access is appropriate for their current job 
responsibilities, or I have marked items for removal/modification as noted.

Manager Signature: ____________________________ Date: _______________

=============================================================================
IAM PROCESSING
=============================================================================

Review Received: _______________     Processed By: ______________________

Actions Taken:
________________________________________________________________________
________________________________________________________________________
________________________________________________________________________

Completion Date: _______________

=============================================================================
```

### Appendix E: Offboarding Checklist

```
=============================================================================
                    SMART DAIRY LTD
              USER OFFBOARDING CHECKLIST
=============================================================================

Employee Name: ___________________________ ID: _______________________
Department: ______________________________ Manager: __________________
Termination Date: ________________________ Type: [ ] Voluntary [ ] Involuntary
Last Working Day: ________________________ Notice: [ ] Standard [ ] Immediate

=============================================================================
IMMEDIATE ACTIONS (Within 15 minutes of notification)
=============================================================================

[ ] 1. DISABLE ACTIVE DIRECTORY ACCOUNT
     Date/Time: _______________    By: ______________________________

[ ] 2. BLOCK VPN ACCESS
     Date/Time: _______________    By: ______________________________

[ ] 3. DISABLE EMAIL ACCESS (block login, preserve mailbox)
     Date/Time: _______________    By: ______________________________

[ ] 4. REVOKE MFA TOKENS/DEVICES
     Date/Time: _______________    By: ______________________________

[ ] 5. DISABLE SSO ACCESS
     Date/Time: _______________    By: ______________________________

[ ] 6. NOTIFICATION TO SECURITY TEAM (if involuntary)
     Date/Time: _______________    By: ______________________________

=============================================================================
SAME DAY ACTIONS (Within 4 hours)
=============================================================================

[ ] 7. REVOKE APPLICATION ACCESS
     Date/Time: _______________    By: ______________________________
     Applications: __________________________________________________

[ ] 8. REMOVE FROM DISTRIBUTION LISTS
     Date/Time: _______________    By: ______________________________

[ ] 9. REMOVE FROM SECURITY GROUPS
     Date/Time: _______________    By: ______________________________

[ ] 10. REVOKE CLOUD PLATFORM ACCESS
     Date/Time: _______________    By: ______________________________

[ ] 11. REVOKE DATABASE ACCESS
     Date/Time: _______________    By: ______________________________

[ ] 12. DISABLE PHYSICAL ACCESS BADGE
     Date/Time: _______________    By: ______________________________
     Badge ID: _______________     Returned: [ ] Yes [ ] No [ ] N/A

=============================================================================
EQUIPMENT COLLECTION
=============================================================================

[ ] 13. LAPTOP/COMPUTER
     Asset Tag: _______________    Returned: [ ] Yes [ ] No
     Condition: [ ] Good [ ] Damaged [ ] Missing

[ ] 14. MOBILE DEVICE
     Asset Tag: _______________    Returned: [ ] Yes [ ] No
     MDM Wipe: [ ] Completed      Date: _______________

[ ] 15. ACCESS CARDS/KEYS
     Items: _________________________________________________________
     Returned: [ ] Yes [ ] No

[ ] 16. OTHER EQUIPMENT
     Items: _________________________________________________________
     Returned: [ ] Yes [ ] No

=============================================================================
DATA MANAGEMENT (Within 48 hours)
=============================================================================

[ ] 17. EMAIL HANDOVER
     Forward to: ____________________________________________________
     Auto-reply set: [ ] Yes [ ] No
     Message: _______________________________________________________

[ ] 18. DATA TRANSFER
     Work files transferred to: _____________________________________
     Method: ________________________________________________________

[ ] 19. APPLICATION DATA HANDOVER
     CRM records transferred: [ ] Yes [ ] N/A
     Project files transferred: [ ] Yes [ ] N/A
     Other: _________________________________________________________

[ ] 20. FORENSIC IMAGE (if required)
     Date: _______________          By: ______________________________
     Location: ______________________________________________________

=============================================================================
FINAL ACTIONS (Within 30 days)
=============================================================================

[ ] 21. ACCOUNT DELETION REVIEW
     Approved for deletion: [ ] Yes [ ] No (Hold for: _____________)
     Date: _______________          By: ______________________________

[ ] 22. EMAIL ARCHIVE
     Archive location: _____________________________________________
     Retention until: _______________

[ ] 23. FINAL ACCESS VERIFICATION
     All access revoked confirmed: [ ] Yes
     Date: _______________          By: ______________________________

[ ] 24. TICKET CLOSURE
     ServiceNow ticket: _______________
     Closed: _______________          By: ______________________________

=============================================================================
SIGN-OFF
=============================================================================

IAM Administrator: _______________________ Date: _______________

Security Team (if applicable): ___________ Date: _______________

HR Representative: _______________________ Date: _______________

=============================================================================
```

### Appendix F: Emergency Contact Information

| Role | Name | Phone | Email | After Hours |
|------|------|-------|-------|-------------|
| IAM Administrator | | +971-XXX-XXXX | iam@smartdairy.ae | +971-XXX-XXXX |
| IT Director | | +971-XXX-XXXX | it.director@smartdairy.ae | +971-XXX-XXXX |
| Security Team Lead | | +971-XXX-XXXX | security@smartdairy.ae | +971-XXX-XXXX |
| CISO | | +971-XXX-XXXX | ciso@smartdairy.ae | +971-XXX-XXXX |
| Emergency Hotline | N/A | +971-XXX-XXXX | emergency@smartdairy.ae | 24/7 |

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| **Author** | IAM Administrator | | January 31, 2026 |
| **Reviewer** | IT Director | | |
| **Approver** | CISO | | |

---

*This document is the property of Smart Dairy Ltd. Unauthorized distribution or modification is prohibited.*

*Document ID: L-012 | Version: 1.0 | Date: January 31, 2026*
