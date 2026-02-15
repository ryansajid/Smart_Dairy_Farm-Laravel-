# Document F-007: Security Policies & Procedures

## Smart Dairy Ltd. - Smart Web Portal System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | F-007 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Security Lead |
| **Owner** | CISO (Chief Information Security Officer) |
| **Reviewer** | IT Director |
| **Classification** | Internal - Confidential |
| **Review Cycle** | Annual |
| **Next Review Date** | January 31, 2027 |

---

## Approval & Acknowledgement

| Role | Name | Signature | Date |
|------|------|-----------|------|
| CISO | [CISO Name] | _________________ | _______ |
| IT Director | [IT Director Name] | _________________ | _______ |
| Managing Director | Mohammad Zahirul Islam | _________________ | _______ |

---

## Document History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 31, 2026 | Security Lead | Initial release of Security Policies & Procedures |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Acceptable Use Policy](#2-acceptable-use-policy)
3. [Access Control Policy](#3-access-control-policy)
4. [Password Policy](#4-password-policy)
5. [Remote Access Policy](#5-remote-access-policy)
6. [Data Classification Policy](#6-data-classification-policy)
7. [Incident Response Policy](#7-incident-response-policy)
8. [Mobile Device Policy](#8-mobile-device-policy)
9. [Email Security Policy](#9-email-security-policy)
10. [Social Engineering Policy](#10-social-engineering-policy)
11. [Vendor Security Policy](#11-vendor-security-policy)
12. [Physical Security Policy](#12-physical-security-policy)
13. [Change Management Policy](#13-change-management-policy)
14. [Monitoring & Privacy Policy](#14-monitoring--privacy-policy)
15. [Policy Enforcement](#15-policy-enforcement)
16. [Appendices](#16-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document establishes the comprehensive security policy framework for Smart Dairy Ltd.'s Smart Web Portal System and all associated information technology resources. These policies are designed to protect the confidentiality, integrity, and availability of Smart Dairy's information assets while ensuring compliance with applicable regulations and industry standards.

### 1.2 Policy Framework Hierarchy

```
┌─────────────────────────────────────────────────────────────┐
│                    MASTER SECURITY POLICY                    │
│         (Governing document - Strategic direction)          │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                   FUNCTIONAL POLICIES                        │
│    (Access Control, Data Protection, Network Security, etc.) │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                STANDARDS & PROCEDURES                        │
│    (Technical specifications, implementation steps, SOPs)    │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    GUIDELINES                                │
│         (Best practices, recommendations, how-tos)          │
└─────────────────────────────────────────────────────────────┘
```

### 1.3 Scope

This policy applies to:

- **All Employees**: Full-time, part-time, temporary, and contract workers
- **Management**: All levels of management and leadership
- **Third Parties**: Vendors, contractors, consultants, and service providers
- **Systems**: All IT systems, networks, applications, and data owned or managed by Smart Dairy
- **Locations**: Corporate office, farm facilities, remote work locations, and any site accessing company resources

### 1.4 Governance Structure

| Role | Responsibility |
|------|---------------|
| **Board of Directors** | Ultimate accountability for information security; approves security strategy |
| **CISO** | Develops and maintains security policies; oversees security operations |
| **IT Director** | Implements technical controls; ensures infrastructure security |
| **Department Heads** | Enforce policies within their departments; report violations |
| **All Users** | Comply with all security policies; report security incidents |

### 1.5 Regulatory Context

Smart Dairy's security policies align with:

- **Bangladesh ICT Act 2006** (as amended)
- **Bangladesh Data Protection Regulations**
- **Payment Card Industry Data Security Standard (PCI DSS)** - for payment processing
- **ISO/IEC 27001:2022** - Information Security Management
- **GDPR** - for any EU customer data handling

### 1.6 Policy Statement

> **Smart Dairy Ltd. is committed to protecting its information assets through a comprehensive security program that encompasses people, processes, and technology. All individuals accessing company resources are required to understand and comply with these security policies. Security is a shared responsibility that requires the active participation of every member of the organization.**

### 1.7 Document Structure

Each policy section contains:
- **Policy Statement**: Official position and requirements
- **Scope**: What and who the policy covers
- **Roles & Responsibilities**: Who does what
- **Procedures**: Step-by-step implementation guidance
- **Compliance**: How compliance is measured
- **Exceptions**: Process for requesting deviations

---

## 2. Acceptable Use Policy

### 2.1 Policy Statement

All users of Smart Dairy's information systems must use these resources responsibly, ethically, and in compliance with all applicable laws, regulations, and company policies. Computing resources are provided for business purposes; limited personal use is permitted when it does not interfere with work responsibilities or security.

### 2.2 Scope

This policy applies to all:
- Company-owned computers, laptops, tablets, and mobile devices
- Network resources (wired and wireless)
- Internet access provided by the company
- Email and communication systems
- Cloud services and SaaS applications
- Software applications and licenses

### 2.3 Permitted Use

Users MAY:

| Category | Permitted Activities |
|----------|---------------------|
| **Business Operations** | Access work-related applications, databases, and files; communicate with colleagues, customers, and partners |
| **Professional Development** | Research industry topics; complete training modules; access educational resources |
| **Limited Personal Use** | Occasional personal email; brief web browsing during breaks; personal calls on mobile devices |
| **System Maintenance** | Run authorized security software; update systems as directed by IT |

### 2.4 Prohibited Activities

Users MUST NOT:

#### 2.4.1 Security Violations

| Prohibition | Description | Consequence |
|-------------|-------------|-------------|
| Unauthorized Access | Attempting to access systems, accounts, or data without proper authorization | Immediate termination; legal action |
| Password Sharing | Sharing passwords, PINs, or authentication credentials with anyone | Disciplinary action; account suspension |
| Circumvention | Bypassing or attempting to bypass security controls | Disciplinary action; termination |
| Malware Distribution | Introducing viruses, worms, trojans, or other malicious software | Termination; legal action |
| Hacking Attempts | Any attempt to hack, crack, or penetrate systems | Immediate termination; legal prosecution |

#### 2.4.2 Network & System Misuse

| Prohibition | Description |
|-------------|-------------|
| Network Scanning | Unauthorized port scanning, vulnerability scanning, or network reconnaissance |
| Bandwidth Abuse | Excessive streaming, large downloads, or activities that degrade network performance |
| Unauthorized Software | Installing unlicensed, pirated, or unauthorized software |
| Personal Servers | Running personal servers, file sharing, or peer-to-peer applications |
| Cryptocurrency Mining | Mining cryptocurrency on company equipment |

#### 2.4.3 Data & Content Violations

| Prohibition | Description |
|-------------|-------------|
| Data Theft | Copying, downloading, or removing confidential data without authorization |
| Copyright Infringement | Downloading, sharing, or storing copyrighted material without license |
| Inappropriate Content | Accessing, storing, or distributing pornographic, offensive, or illegal content |
| Harassment | Using company systems to harass, bully, or discriminate against others |
| Data Exfiltration | Uploading company data to personal cloud storage or external systems |

#### 2.4.4 Communication Violations

| Prohibition | Description |
|-------------|-------------|
| Impersonation | Impersonating another user, customer, or entity |
| Spam | Sending unsolicited bulk messages or spam |
| Chain Letters | Forwarding chain letters, pyramid schemes, or fraudulent messages |
| Misrepresentation | Sending communications that appear to originate from another source |

### 2.5 Monitoring & Privacy

- All use of company systems may be monitored
- Users should have no expectation of privacy when using company resources
- Monitoring includes: email, internet access, file access, keystrokes (in specific circumstances)
- See Section 14 for detailed Monitoring & Privacy Policy

### 2.6 User Acknowledgement

All users must sign the Acceptable Use Acknowledgement Form (Appendix B) before receiving system access.

---

## 3. Access Control Policy

### 3.1 Policy Statement

Access to Smart Dairy's information systems and data shall be granted based on the principle of least privilege, where users receive only the minimum access necessary to perform their job functions. Access rights must be formally authorized, regularly reviewed, and promptly revoked when no longer needed.

### 3.2 Access Control Principles

```
┌────────────────────────────────────────────────────────────────┐
│                 LEAST PRIVILEGE PRINCIPLE                       │
├────────────────────────────────────────────────────────────────┤
│  User Need ──▶ Minimum Access ──▶ Justification ──▶ Approval   │
└────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌────────────────────────────────────────────────────────────────┐
│              NEED-TO-KNOW PRINCIPLE                             │
├────────────────────────────────────────────────────────────────┤
│  Job Function ──▶ Required Information ──▶ Authorized Access   │
└────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌────────────────────────────────────────────────────────────────┐
│              SEGREGATION OF DUTIES                              │
├────────────────────────────────────────────────────────────────┤
│  Critical Functions ──▶ Multiple Roles ──▶ No Single Control   │
└────────────────────────────────────────────────────────────────┘
```

### 3.3 Account Provisioning Process

#### 3.3.1 New User Onboarding

| Step | Action | Responsible | Timeline |
|------|--------|-------------|----------|
| 1 | Manager submits Access Request Form (Appendix C) | Hiring Manager | Upon hire decision |
| 2 | HR validates employment status | HR Department | Within 1 business day |
| 3 | IT reviews and determines appropriate access | IT Security | Within 2 business days |
| 4 | Account creation and provisioning | System Administrator | Within 2 business days |
| 5 | User acknowledgment and training | User | Before first access |
| 6 | Access activation notification | IT Security | Upon completion |

#### 3.3.2 Access Request Requirements

Access requests must specify:
- Employee name, ID, department, and position
- Systems/applications required
- Justification for each access request
- Manager approval signature
- Duration (permanent or temporary with end date)

### 3.4 Role-Based Access Control (RBAC)

| Role Category | Access Level | Example Roles |
|---------------|--------------|---------------|
| **Standard User** | Minimal; application-specific | Farm Workers, Sales Staff |
| **Privileged User** | Elevated; system administration | IT Administrators, DBAs |
| **Manager** | Department-level data access | Department Heads, Supervisors |
| **Executive** | Cross-functional access; reports | C-Level, Directors |
| **External** | Limited; project-specific | Contractors, Vendors |

### 3.5 Privileged Access Management

#### 3.5.1 Privileged Account Types

| Account Type | Usage | Controls |
|--------------|-------|----------|
| Domain Admin | Domain controller management | MFA required; session recording |
| Server Admin | Server configuration | Just-in-time access; approval required |
| Database Admin | Database management | Separate credentials; query logging |
| Application Admin | Application configuration | Time-limited; activity monitoring |
| Root/Superuser | Operating system management | Break-glass procedures only |

#### 3.5.2 Privileged Access Requirements

- **Multi-Factor Authentication (MFA)** mandatory for all privileged accounts
- **Privileged Access Workstation (PAW)** required for administrative tasks
- **Session timeout** after 15 minutes of inactivity
- **Activity logging** of all privileged operations
- **Quarterly access review** by CISO

### 3.6 Access Reviews

| Review Type | Frequency | Scope | Responsible |
|-------------|-----------|-------|-------------|
| User Access Review | Quarterly | All user accounts | Department Managers |
| Privileged Access Review | Quarterly | Admin and service accounts | CISO |
| Application Access Review | Semi-annually | Application-specific access | Application Owners |
| Contractor Access Review | Monthly | External user accounts | IT Security |
| Termination Review | Weekly | Departed employees | HR + IT |

### 3.7 Account Termination

#### 3.7.1 Termination Process

| Trigger | Action | Timeline |
|---------|--------|----------|
| Voluntary resignation | Account disabled upon notice | Same day |
| Involuntary termination | Account disabled immediately | Immediate |
| Contract end | Account disabled on end date | Same day |
| Role change | Access modified to match new role | Within 24 hours |

#### 3.7.2 Termination Checklist

- [ ] User account disabled in Active Directory
- [ ] Email access revoked
- [ ] VPN access disabled
- [ ] Application access removed
- [ ] Remote desktop access revoked
- [ ] Mobile device wiped (if BYOD, remove company data)
- [ ] Physical access badges deactivated
- [ ] Data ownership transferred or archived

### 3.8 Shared Accounts

Shared accounts are PROHIBITED except for:
- Emergency break-glass accounts (with documented procedures)
- Service accounts for automated processes (with password vaulting)

All exceptions require CISO approval.

---

## 4. Password Policy

### 4.1 Policy Statement

Passwords are a critical component of Smart Dairy's security controls. All users must create strong, unique passwords and protect them from unauthorized disclosure. Technical controls will enforce password complexity and rotation requirements.

### 4.2 Password Requirements

#### 4.2.1 Standard User Passwords

| Requirement | Specification |
|-------------|---------------|
| **Minimum Length** | 12 characters |
| **Maximum Length** | 64 characters |
| **Complexity** | Uppercase, lowercase, numbers, and special characters |
| **History** | Last 24 passwords cannot be reused |
| **Expiration** | 90 days |
| **Account Lockout** | 5 failed attempts; 30-minute lockout |

#### 4.2.2 Privileged User Passwords

| Requirement | Specification |
|-------------|---------------|
| **Minimum Length** | 16 characters |
| **Maximum Length** | 64 characters |
| **Complexity** | Uppercase, lowercase, numbers, and special characters |
| **History** | Last 24 passwords cannot be reused |
| **Expiration** | 60 days |
| **Account Lockout** | 3 failed attempts; admin unlock required |
| **MFA** | Required for all privileged access |

#### 4.2.3 Service Account Passwords

| Requirement | Specification |
|-------------|---------------|
| **Minimum Length** | 20 characters |
| **Complexity** | Randomly generated; high entropy |
| **Storage** | Enterprise password vault only |
| **Rotation** | Automatic rotation every 90 days |
| **Usage** | Single service per account |

### 4.3 Password Construction Guidelines

#### 4.3.1 Recommended Approaches

| Approach | Example | Strength |
|----------|---------|----------|
| Passphrase | `CorrectHorseBatteryStaple!47` | Excellent |
| Random Characters | `K9#mP2$vL7@qR4` | Excellent |
| Abbreviated Phrase | `Iwtbts@SD2026!` | Good |

#### 4.3.2 Prohibited Password Patterns

Passwords MUST NOT contain:
- Username or full name
- Company name ("SmartDairy", "Saffron")
- Sequential characters ("123456", "abcdef")
- Repeated characters ("aaaaaa", "111111")
- Dictionary words without modification
- Previous passwords with minor changes
- Personal information (birthdays, phone numbers)

### 4.4 Password Protection

#### 4.4.1 User Responsibilities

Users MUST:
- Never share passwords with anyone (including IT staff)
- Never write passwords on paper or in unsecured files
- Never store passwords in browsers on shared computers
- Never use the same password for work and personal accounts
- Report suspected password compromise immediately
- Log out or lock screens when leaving workstations

#### 4.4.2 Technical Protections

- Passwords stored using bcrypt/Argon2 hashing
- Salting applied to all password hashes
- Transmission only over encrypted channels (TLS 1.2+)
- Password vault available for secure password storage

### 4.5 Password Manager Usage

Smart Dairy provides an enterprise password manager for all employees.

| Feature | Availability |
|---------|-------------|
| Personal password vault | All employees |
| Shared team vaults | By request |
| Emergency access | Designated backup users |
| Mobile app access | Available with MFA |

#### 4.5.1 Password Manager Requirements

- Master password must meet privileged user password requirements
- MFA required for password manager access
- Regular backup of vault contents
- Emergency access procedures documented

### 4.6 Password Reset Procedures

#### 4.6.1 Self-Service Password Reset

1. User initiates reset from login page
2. Identity verification via registered email or SMS
3. MFA challenge (if enabled)
4. Temporary password sent or new password creation allowed
5. Mandatory password change on next login

#### 4.6.2 Assisted Password Reset

For users unable to use self-service:
1. User contacts IT Help Desk
2. Identity verification via employee ID + security question
3. Manager verification for privileged accounts
4. Temporary password issued
5. Mandatory password change on first login
6. Activity logged for security review

---

## 5. Remote Access Policy

### 5.1 Policy Statement

Remote access to Smart Dairy's network and systems is permitted only when necessary for business operations, approved by management, and secured according to established standards. All remote access activities are monitored and logged.

### 5.2 Remote Access Methods

| Method | Purpose | Security Requirements |
|--------|---------|----------------------|
| **VPN** | Full network access for employees | MFA required; client health check |
| **VDI** | Virtual desktop access | MFA required; session recording |
| **Web Applications** | Specific application access | HTTPS only; session timeout |
| **SSH/RDP** | Server administration | Jump host required; MFA |
| **Mobile Apps** | Mobile device access | MDM enrollment; app-level MFA |

### 5.3 VPN Policy

#### 5.3.1 VPN Usage Requirements

- VPN required for all remote access to internal resources
- Split tunneling DISABLED (all traffic through VPN)
- Automatic connection when off corporate network
- Automatic disconnect after 8 hours of continuous use
- Re-authentication required every 24 hours

#### 5.3.2 VPN Client Requirements

| Component | Requirement |
|-----------|-------------|
| **Endpoint Security** | Antivirus with current definitions |
| **OS Patching** | Critical patches within 7 days |
| **Firewall** | Host firewall enabled |
| **Encryption** | AES-256 for data transmission |

### 5.4 Work From Home (WFH) Policy

#### 5.4.1 WFH Eligibility

WFH arrangements require:
- Manager approval
- HR documentation
- IT security assessment of home environment
- Signed WFH Security Agreement (Appendix D)

#### 5.4.2 WFH Security Requirements

| Category | Requirement |
|----------|-------------|
| **Workspace** | Private area; screen not visible to others |
| **Network** | Secured home Wi-Fi (WPA2/WPA3); no public Wi-Fi |
| **Equipment** | Company laptop or approved personal device |
| **Storage** | No company data on personal storage devices |
| **Disposal** | Shred printed documents; secure disposal |

### 5.5 Bring Your Own Device (BYOD) Policy

#### 5.5.1 BYOD Eligibility

BYOD is permitted for:
- Email access (via managed email app)
- Calendar and contacts synchronization
- Approved mobile applications

BYOD is NOT permitted for:
- Direct system administration
- Access to confidential data repositories
- VPN connection to corporate network

#### 5.5.2 BYOD Requirements

| Requirement | Specification |
|-------------|---------------|
| **MDM Enrollment** | Mandatory for any corporate data access |
| **Device Encryption** | Full disk encryption required |
| **OS Version** | Current or previous major version |
| **Passcode** | Minimum 6-digit PIN or biometric |
| **Remote Wipe** | Corporate data only (selective wipe) |

#### 5.5.3 BYOD User Agreement

BYOD users must acknowledge:
- Company can monitor corporate data and applications
- Company can remove corporate data remotely
- Personal data may be visible to IT during support
- Device must be surrendered for legal hold if required

### 5.6 Remote Access Prohibitions

Remote users MUST NOT:
- Allow others to use their remote access credentials
- Access company resources from internet cafes or public computers
- Use unsecured public Wi-Fi without VPN
- Leave devices unattended in public places
- Disable security controls on remote devices
- Transfer company data to personal cloud storage

---

## 6. Data Classification Policy

### 6.1 Policy Statement

All Smart Dairy data must be classified according to its sensitivity and criticality to the business. Classification determines how data is handled, stored, transmitted, and disposed of throughout its lifecycle.

### 6.2 Data Classification Levels

```
┌─────────────────────────────────────────────────────────────────────────┐
│                    DATA CLASSIFICATION HIERARCHY                         │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│   ┌─────────────────────────────────────────────────────────────────┐   │
│   │  RESTRICTED                                                     │   │
│   │  • Customer payment information                                 │   │
│   │  • Authentication credentials                                   │   │
│   │  • Unpublished financial data                                   │   │
│   │  • Trade secrets and IP                                         │   │
│   └─────────────────────────────────────────────────────────────────┘   │
│                                    │                                     │
│                                    ▼                                     │
│   ┌─────────────────────────────────────────────────────────────────┐   │
│   │  CONFIDENTIAL                                                   │   │
│   │  • Customer personal information                                │   │
│   │  • Employee records                                             │   │
│   │  • Business plans and strategies                                │   │
│   │  • Contract and legal documents                                 │   │
│   └─────────────────────────────────────────────────────────────────┘   │
│                                    │                                     │
│                                    ▼                                     │
│   ┌─────────────────────────────────────────────────────────────────┐   │
│   │  INTERNAL                                                       │   │
│   │  • Operational procedures                                       │   │
│   │  • Internal communications                                      │   │
│   │  • Non-sensitive business data                                  │   │
│   │  • Standard operating procedures                                │   │
│   └─────────────────────────────────────────────────────────────────┘   │
│                                    │                                     │
│                                    ▼                                     │
│   ┌─────────────────────────────────────────────────────────────────┐   │
│   │  PUBLIC                                                         │   │
│   │  • Published marketing materials                                │   │
│   │  • Public website content                                       │   │
│   │  • Product catalogs (without pricing)                           │   │
│   │  • Press releases                                               │   │
│   └─────────────────────────────────────────────────────────────────┘   │
│                                                                          │
└─────────────────────────────────────────────────────────────────────────┘
```

### 6.3 Classification Definitions & Handling

#### 6.3.1 RESTRICTED Data

| Attribute | Requirement |
|-----------|-------------|
| **Definition** | Data whose unauthorized disclosure would cause severe harm to Smart Dairy, its customers, or partners |
| **Access** | Need-to-know basis only; approved by data owner |
| **Storage** | Encrypted at rest (AES-256); secure locations only |
| **Transmission** | Encrypted in transit (TLS 1.3); secure file transfer only |
| **Disposal** | Secure deletion with verification; certificate of destruction |
| **Labeling** | "RESTRICTED" watermark; encryption required |

**Examples:**
- Payment card data (PCI-DSS scope)
- Banking credentials and account information
- System authentication credentials
- Encryption keys
- Unpublished quarterly financial results

#### 6.3.2 CONFIDENTIAL Data

| Attribute | Requirement |
|-----------|-------------|
| **Definition** | Data whose unauthorized disclosure would cause significant harm or violate regulations |
| **Access** | Business need basis; role-based access control |
| **Storage** | Encrypted at rest recommended; access logging required |
| **Transmission** | Encrypted in transit; secure channels preferred |
| **Disposal** | Secure deletion; standard disposal procedures |
| **Labeling** | "CONFIDENTIAL" header/footer |

**Examples:**
- Customer personal information (PII)
- Employee personal data and performance records
- Business plans and strategic initiatives
- Contract terms and pricing
- Veterinary and herd health records

#### 6.3.3 INTERNAL Data

| Attribute | Requirement |
|-----------|-------------|
| **Definition** | Data intended for internal use only; disclosure not harmful but not for public release |
| **Access** | All employees unless specifically restricted |
| **Storage** | Standard storage locations; access controls applied |
| **Transmission** | Standard email and file sharing acceptable |
| **Disposal** | Standard deletion procedures |
| **Labeling** | "INTERNAL USE ONLY" recommended |

**Examples:**
- Internal policies and procedures
- Department meeting minutes
- Operational reports
- Training materials
- Non-sensitive farm operational data

#### 6.3.4 PUBLIC Data

| Attribute | Requirement |
|-----------|-------------|
| **Definition** | Data approved for public release; no restrictions |
| **Access** | Unrestricted |
| **Storage** | Any location; no special controls |
| **Transmission** | Any method |
| **Disposal** | No special requirements |
| **Labeling** | None required |

**Examples:**
- Marketing brochures and website content
- Product information (public)
- Published press releases
- Job postings
- General company information

### 6.4 Data Handling Matrix

| Activity | RESTRICTED | CONFIDENTIAL | INTERNAL | PUBLIC |
|----------|------------|--------------|----------|--------|
| **Email** | Encrypted only | Secure channels | Standard | Any |
| **Cloud Storage** | Approved enterprise only | Approved enterprise only | Approved services | Any |
| **USB/Removable** | Prohibited | Encrypted devices | Permitted | Permitted |
| **Printing** | Secure printer only | Secure area | Any printer | Any |
| **Mobile Device** | MDM + encryption required | MDM required | Standard | Any |
| **Retention** | Legal hold + 7 years | 7 years | 3 years | As needed |

### 6.5 Data Classification Responsibilities

| Role | Responsibility |
|------|---------------|
| **Data Owner** | Determines classification; approves access; reviews annually |
| **Data Steward** | Implements controls; monitors compliance |
| **Data Custodian** | Maintains storage; ensures backups; enforces retention |
| **Data User** | Handles according to classification; reports incidents |

### 6.6 Classification Change Process

Data classification may be changed when:
- Data is published (typically downgraded to PUBLIC)
- Legal or regulatory requirements change
- Business sensitivity changes
- Time-bound confidentiality expires

Classification changes require Data Owner approval and documentation.

---

## 7. Incident Response Policy

### 7.1 Policy Statement

Smart Dairy is committed to promptly detecting, analyzing, containing, and recovering from security incidents. All employees are responsible for reporting suspected security incidents immediately. The Incident Response Team (IRT) will coordinate response activities according to established procedures.

### 7.2 Incident Classification

#### 7.2.1 Severity Levels

| Level | Definition | Examples | Response Time |
|-------|------------|----------|---------------|
| **Critical (P1)** | Severe impact; immediate threat to operations or data breach | Ransomware attack; data breach; active system compromise | Immediate; 15-minute acknowledgment |
| **High (P2)** | Significant impact; potential for escalation | Malware outbreak; unauthorized access; DDoS attack | 30-minute acknowledgment; 4-hour containment |
| **Medium (P3)** | Limited impact; localized issue | Phishing attempt; policy violation; isolated malware | 4-hour acknowledgment; 24-hour resolution |
| **Low (P4)** | Minimal impact; informational | Spam; scanning attempts; minor policy violation | Next business day |

#### 7.2.2 Incident Types

| Category | Description |
|----------|-------------|
| **Malware** | Virus, worm, trojan, ransomware infection |
| **Unauthorized Access** | Successful or attempted unauthorized system access |
| **Data Breach** | Unauthorized disclosure or access to sensitive data |
| **Insider Threat** | Malicious or negligent actions by authorized users |
| **Physical Security** | Unauthorized physical access or theft |
| **Denial of Service** | DDoS attacks or resource exhaustion |
| **Social Engineering** | Phishing, pretexting, or other manipulation |
| **Policy Violation** | Security policy breaches |

### 7.3 Incident Response Team (IRT)

| Role | Primary | Responsibilities |
|------|---------|-----------------|
| **Incident Commander** | CISO | Overall incident management; stakeholder communication |
| **Technical Lead** | IT Director | Technical response; containment and recovery |
| **Communications Lead** | Communications Manager | Internal and external communications |
| **Legal Counsel** | Legal Advisor | Regulatory obligations; legal implications |
| **HR Representative** | HR Manager | Employee-related incidents; insider threats |
| **Forensics Lead** | Security Analyst | Evidence collection; forensic analysis |

### 7.4 Incident Response Process

```
┌──────────────────────────────────────────────────────────────────────────┐
│                    INCIDENT RESPONSE LIFECYCLE                            │
└──────────────────────────────────────────────────────────────────────────┘

    ┌──────────────┐
    │  DETECTION   │ ◄── Reporting, monitoring, alerts
    └──────┬───────┘
           │
           ▼
    ┌──────────────┐
    │   ANALYSIS   │ ◄── Triage, classification, scope determination
    └──────┬───────┘
           │
           ▼
    ┌──────────────┐
    │  CONTAINMENT │ ◄── Short-term and long-term containment
    └──────┬───────┘
           │
           ▼
    ┌──────────────┐
    │   ERADICATION│ ◄── Remove threat, patch vulnerabilities
    └──────┬───────┘
           │
           ▼
    ┌──────────────┐
    │   RECOVERY   │ ◄── Restore systems, resume operations
    └──────┬───────┘
           │
           ▼
    ┌──────────────┐
    │  POST-INCIDENT│ ◄── Lessons learned, documentation, improvements
    └──────────────┘
```

### 7.5 Incident Reporting

#### 7.5.1 Reporting Channels

| Method | Contact | When to Use |
|--------|---------|-------------|
| **Hotline** | security@smartdairybd.com | All security incidents |
| **Phone** | +880-XXXX-XXXXXX (24/7 hotline) | Critical/High severity |
| **In Person** | IT Security Office | Business hours |
| **Online Form** | [Internal Portal] | Non-urgent reports |

#### 7.5.2 What to Report

Report immediately if you observe:
- Suspicious emails or phishing attempts
- Unusual system behavior or performance
- Unauthorized access attempts
- Lost or stolen devices
- Suspected data exposure
- Policy violations
- Physical security concerns

#### 7.5.3 Reporting Information

When reporting, provide:
- Your name and contact information
- Date and time of incident
- Systems or data affected
- Description of what occurred
- Actions already taken
- Potential impact assessment

### 7.6 Escalation Procedures

```
┌────────────────────────────────────────────────────────────────┐
│                    ESCALATION MATRIX                            │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│  Detection ──▶ IT Security ──▶ CISO ──▶ Managing Director      │
│                 │                │              │               │
│                 ▼                ▼              ▼               │
│              P3/P4            P1/P2         Critical            │
│             incidents      incidents      Business Impact       │
│                                                                │
│  Additional Notifications:                                     │
│  • Legal Counsel (all P1/P2)                                   │
│  • Board of Directors (P1 with business impact)                │
│  • Regulatory Authorities (as required by law)                 │
│  • Affected Customers (breach notification)                    │
│                                                                │
└────────────────────────────────────────────────────────────────┘
```

### 7.7 Breach Notification Requirements

#### 7.7.1 Internal Notification

| Severity | Internal Notification |
|----------|----------------------|
| P1 | Immediate notification to all executives and department heads |
| P2 | Notification to affected departments within 4 hours |
| P3 | Notification to affected users within 24 hours |
| P4 | Weekly security report inclusion |

#### 7.7.2 External Notification

External notification may be required for:
- **Regulatory Authorities**: Within 72 hours of breach discovery (per applicable regulations)
- **Affected Individuals**: Without undue delay if high risk to rights and freedoms
- **Law Enforcement**: For criminal activity
- **Business Partners**: Per contractual obligations

### 7.8 Incident Documentation

All incidents must be documented with:
- Incident ID and classification
- Timeline of events
- Actions taken and by whom
- Evidence collected
- Root cause analysis
- Lessons learned
- Preventive measures implemented

---

## 8. Mobile Device Policy

### 8.1 Policy Statement

Mobile devices accessing Smart Dairy resources must be secured to protect company data and maintain network integrity. This policy applies to company-owned devices and personally owned devices authorized for business use (BYOD).

### 8.2 Device Categories

| Category | Description | Management |
|----------|-------------|------------|
| **Corporate-Owned** | Devices purchased and owned by Smart Dairy | Full MDM control |
| **BYOD** | Personal devices accessing corporate data | Containerized management |
| **Kiosk/Shared** | Devices for shared use (e.g., farm terminals) | Locked-down configuration |
| **Contractor** | Devices owned by contractors/vendors | Limited access; monitoring |

### 8.3 Mobile Device Management (MDM) Requirements

#### 8.3.1 MDM Enrollment

| Device Type | Enrollment Requirement |
|-------------|----------------------|
| Corporate-Owned | Mandatory before first use |
| BYOD | Mandatory for any corporate data access |
| Contractor | Required for network access |

#### 8.3.2 MDM Capabilities

The MDM solution provides:
- Device inventory and asset tracking
- Configuration management (Wi-Fi, VPN, email)
- Application management (distribution, updates, blacklisting)
- Security policy enforcement (passcode, encryption)
- Remote lock and wipe capabilities
- Compliance monitoring and reporting

### 8.4 Security Requirements

#### 8.4.1 Passcode/Biometric

| Device Type | Requirement |
|-------------|-------------|
| Smartphones | Minimum 6-digit PIN or biometric |
| Tablets | Minimum 6-digit PIN or biometric |
| Laptops | Password per Password Policy |
| **Auto-Lock** | 5 minutes of inactivity |
| **Wipe Attempts** | 10 failed attempts triggers wipe |

#### 8.4.2 Encryption

| Data Type | Encryption Requirement |
|-----------|----------------------|
| Device Storage | Full disk encryption mandatory |
| Removable Storage | Encryption required for corporate data |
| Data in Transit | TLS 1.2 minimum |
| Email | S/MIME or similar encryption for confidential data |

#### 8.4.3 Software Requirements

- Operating system: Current or N-1 version
- Security patches: Applied within 30 days of release
- Antivirus/Mobile security: Current and updated
- Jailbroken/Rooted devices: Prohibited from corporate access

### 8.5 Application Management

#### 8.5.1 Approved Applications

| Category | Approved Apps |
|----------|--------------|
| Email | Microsoft Outlook, native mail (with MDM) |
| Collaboration | Microsoft Teams, approved messaging apps |
| File Storage | OneDrive for Business, SharePoint |
| Security | Company-approved antivirus, authenticator apps |

#### 8.5.2 Prohibited Applications

The following are prohibited on devices with corporate access:
- Unofficial app stores or sideloaded apps
- File sharing/P2P applications
- Unapproved cloud storage (Dropbox personal, Google Drive personal)
- VPN/Proxy applications (unapproved)
- Games (on corporate devices)

### 8.6 Lost or Stolen Device Procedures

| Timeframe | Action |
|-----------|--------|
| Immediate | Report to IT Security |
| Within 1 hour | Device remotely locked |
| If recovery unlikely | Selective wipe of corporate data |
| If data at risk | Full device wipe |

### 8.7 Device Retirement and Disposal

#### 8.7.1 Corporate Device Return

When employment ends or device is replaced:
1. Device returned to IT
2. Corporate data wiped
3. Factory reset performed
4. Device redeployed or disposed of securely

#### 8.7.2 BYOD Disenrollment

When employment ends:
1. MDM profile removed
2. Corporate accounts removed
3. Corporate apps uninstalled
4. Confirmation of data removal

---

## 9. Email Security Policy

### 9.1 Policy Statement

Email is a critical business communication tool and a common attack vector. All users must follow email security best practices to protect against phishing, malware, and data loss.

### 9.2 Email Usage Guidelines

#### 9.2.1 Acceptable Use

Email may be used for:
- Business communications with colleagues, customers, and partners
- Approved personal use (limited, non-commercial)
- Subscription to professional development resources

#### 9.2.2 Prohibited Use

Email must NOT be used for:
- Distribution of confidential information to unauthorized recipients
- Chain letters, pyramid schemes, or fraudulent content
- Harassment, discrimination, or offensive content
- Political campaigning or solicitation
- Personal commercial activities
- Storage of RESTRICTED data (unless encrypted)

### 9.3 Phishing Protection

#### 9.3.1 Phishing Awareness

Users must be alert for:
- Urgent or threatening language
- Requests for passwords or sensitive information
- Unexpected attachments or links
- Spoofed sender addresses
- Generic greetings ("Dear Customer")
- Grammar and spelling errors

#### 9.3.2 Phishing Response

If you suspect a phishing email:
1. **DO NOT** click links or open attachments
2. **DO NOT** reply to the email
3. **DO** report using the "Report Phishing" button
4. **DO** notify IT Security

#### 9.3.3 Technical Protections

| Control | Implementation |
|---------|---------------|
| Spam Filtering | Gateway-based filtering with quarantine |
| Anti-Malware | Attachment scanning and sandboxing |
| Link Protection | URL rewriting and click-time protection |
| DMARC/SPF/DKIM | Email authentication protocols |
| Warning Banners | External email warnings |

### 9.4 Email Attachments

#### 9.4.1 Attachment Restrictions

| File Type | Policy |
|-----------|--------|
| Executable files (.exe, .bat, .msi) | Blocked |
| Script files (.js, .vbs, .ps1) | Blocked |
| Office macros | Disabled by default; warning required |
| Password-protected archives | Blocked or sandboxed |
| Large attachments (>25MB) | Blocked; use file sharing instead |

#### 9.4.2 Safe Attachment Handling

- Scan all attachments with antivirus before opening
- Verify sender identity before opening unexpected attachments
- Contact sender to confirm if attachment is unexpected
- Save attachments to disk before opening (allows scanning)

### 9.5 Email Encryption

#### 9.5.1 When to Encrypt

Encrypt emails containing:
- RESTRICTED data (mandatory)
- CONFIDENTIAL data (recommended)
- Personal information of customers or employees
- Financial information
- Legal or contractual information

#### 9.5.2 Encryption Methods

| Method | Use Case |
|--------|----------|
| TLS | Automatic encryption in transit |
| S/MIME | End-to-end encryption for confidential data |
| Office 365 Message Encryption | External recipient encryption |
| Third-party encryption | High-sensitivity external communications |

### 9.6 Email Retention

| Category | Retention Period |
|----------|-----------------|
| General Business Email | 3 years |
| Financial Records | 7 years |
| Legal Matters | Per legal hold + 7 years |
| Spam/Junk | 30 days |
| Deleted Items | 30 days |

---

## 10. Social Engineering Policy

### 10.1 Policy Statement

Social engineering attacks manipulate individuals into revealing confidential information or performing actions that compromise security. All Smart Dairy employees must be trained to recognize and report social engineering attempts.

### 10.2 Types of Social Engineering

| Type | Description | Example |
|------|-------------|---------|
| **Phishing** | Fraudulent emails appearing legitimate | Fake bank login email |
| **Spear Phishing** | Targeted phishing using personal information | Email referencing specific project |
| **Vishing** | Voice phishing via phone calls | Caller pretending to be IT support |
| **Smishing** | SMS/text message phishing | Fake package delivery text |
| **Pretexting** | Creating false scenario to obtain information | Fake auditor requesting documents |
| **Baiting** | Offering something enticing | Infected USB drive left in parking lot |
| **Tailgating** | Following someone into secure area | Entering behind authorized person |
| **Quid Pro Quo** | Offering service in exchange for information | Fake tech support offering help |

### 10.3 Training Requirements

#### 10.3.1 Initial Training

All new employees must complete:
- Security awareness training within first week
- Social engineering recognition module
- Phishing simulation test

#### 10.3.2 Ongoing Training

| Training Type | Frequency |
|---------------|-----------|
| Security Awareness Refresh | Annual |
| Phishing Simulations | Monthly |
| Social Engineering Updates | As threats emerge |
| Role-Specific Training | Per risk profile |

### 10.4 Reporting Suspicious Activity

#### 10.4.1 Immediate Reporting

Report immediately if you:
- Receive suspicious phone calls requesting information
- Are approached by unknown individuals asking about company operations
- Find unknown USB drives or devices
- Are asked to bypass security procedures
- Receive unusual requests from "executives"

#### 10.4.2 Reporting Process

1. Do not engage further with the potential attacker
2. Document what occurred (time, method, details requested)
3. Report to IT Security via security@smartdairybd.com
4. Report to your manager
5. Forward suspicious emails (without clicking) to security team

### 10.5 Verification Procedures

#### 10.5.1 Identity Verification

Before providing information or access:
- Verify caller identity through callback procedure
- Confirm email sender through alternate channel
- Validate visitor identity through reception
- Confirm executive requests through executive assistant

#### 10.5.2 Out-of-Band Verification

For sensitive requests, verify through alternate channel:
- Phone call to known number (not one provided by requester)
- In-person confirmation
- Internal messaging to verified account

### 10.6 Prevention Measures

| Measure | Implementation |
|---------|---------------|
| **Employee Awareness** | Regular training and simulations |
| **Technical Controls** | Email filtering, web filtering |
| **Physical Security** | Badge access, visitor management |
| **Procedures** | Verification requirements |
| **Testing** | Regular penetration testing |

---

## 11. Vendor Security Policy

### 11.1 Policy Statement

Third-party vendors with access to Smart Dairy systems or data must meet security requirements equivalent to internal standards. Vendor security assessments are required before engagement and periodically throughout the relationship.

### 11.2 Vendor Classification

| Risk Level | Criteria | Examples |
|------------|----------|----------|
| **Critical** | Access to RESTRICTED data; system integration; business-critical services | Cloud providers, payment processors, IT outsourcing |
| **High** | Access to CONFIDENTIAL data; significant system access | SaaS providers, managed services |
| **Medium** | Access to INTERNAL data; limited system access | Marketing agencies, consultants |
| **Low** | No data access; non-critical services | Office supplies, catering |

### 11.3 Security Assessment Requirements

| Risk Level | Assessment Required |
|------------|---------------------|
| Critical | Full security audit; on-site assessment; annual review |
| High | Security questionnaire; evidence review; annual review |
| Medium | Security questionnaire; biennial review |
| Low | Basic questionnaire; contractual requirements |

### 11.4 Vendor Security Requirements

#### 11.4.1 Contractual Requirements

All vendor contracts must include:
- Data protection and confidentiality clauses
- Security control requirements
- Right to audit provisions
- Incident notification requirements (24 hours for Critical/High)
- Data return/destruction on termination
- Liability and indemnification provisions
- Compliance with applicable regulations

#### 11.4.2 Technical Requirements

| Control | Critical | High | Medium |
|---------|----------|------|--------|
| Encryption at Rest | Required | Required | Recommended |
| Encryption in Transit | Required | Required | Required |
| Access Logging | Required | Required | Recommended |
| MFA | Required | Required | Recommended |
| Penetration Testing | Annual | Biennial | As needed |
| SOC 2/ISO 27001 | Required | Preferred | Preferred |

### 11.5 Vendor Management Process

```
┌────────────────────────────────────────────────────────────────┐
│                   VENDOR SECURITY LIFECYCLE                     │
├────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────┐   ┌─────────┐   ┌─────────┐   ┌─────────┐        │
│  │DUE      │──▶│SECURITY │──▶│CONTRACT │──▶│ONGOING  │        │
│  │DILIGENCE│   │ASSESS   │   │REVIEW   │   │MONITOR  │        │
│  └─────────┘   └─────────┘   └─────────┘   └─────────┘        │
│       │            │             │             │               │
│       ▼            ▼             ▼             ▼               │
│  • Business    • Questionnaire • Security    • Periodic        │
│    need        • Evidence      •   clauses    reviews          │
│    validation    review       • Audit        • Incident        │
│  • Risk        • Reference    •   rights       tracking        │
│    assessment    checks       • SLA          • Performance     │
│                • Site visits    terms          monitoring      │
│                                                                 │
└────────────────────────────────────────────────────────────────┘
```

### 11.6 Vendor Incident Notification

Vendors must notify Smart Dairy within:
- **Critical/High Risk Vendors**: 24 hours of security incident discovery
- **Medium Risk Vendors**: 48 hours of security incident discovery
- **All Vendors**: Immediately if Smart Dairy data is affected

### 11.7 Vendor Termination

Upon contract termination:
1. All access revoked immediately
2. Data returned or destroyed per contract
3. Certificates of destruction obtained
4. Final security assessment (for Critical/High vendors)
5. Access review to confirm no lingering access

---

## 12. Physical Security Policy

### 12.1 Policy Statement

Physical security measures protect Smart Dairy's personnel, facilities, equipment, and information from physical threats. All employees, contractors, and visitors must comply with physical security controls.

### 12.2 Facility Classifications

| Classification | Description | Locations |
|----------------|-------------|-----------|
| **Critical** | Data centers; core infrastructure | Primary data center, network rooms |
| **High** | Corporate offices; farm administration | Jahir Smart Tower, Farm office |
| **Standard** | General work areas | Open offices, meeting rooms |
| **Public** | Areas open to public | Reception, retail locations |

### 12.3 Corporate Office Security

#### 12.3.1 Access Control

| Area | Control Method |
|------|---------------|
| Building Perimeter | Key card access; security guard |
| Elevators | Key card access to specific floors |
| Office Suite | Key card + PIN |
| Server Room | Biometric + Key card; dual control |
| Executive Areas | Enhanced access controls |

#### 12.3.2 Visitor Management

| Requirement | Specification |
|-------------|---------------|
| Registration | All visitors must sign in |
| Identification | Government-issued ID required |
| Badge | Visitor badge must be worn visibly |
| Escort | Visitors escorted in secure areas |
| Sign-out | Required upon departure |

### 12.4 Data Center Security

#### 12.4.1 Physical Controls

| Control | Implementation |
|---------|---------------|
| Location | Secure, climate-controlled facility |
| Access | Biometric + dual authentication |
| Monitoring | 24/7 video surveillance |
| Environmental | Fire suppression, climate control |
| Redundancy | Backup power, redundant connectivity |

#### 12.4.2 Data Center Access

- Access limited to authorized IT personnel
- All access logged and audited
- Maintenance by approved vendors only
- No unauthorized photography or devices

### 12.5 Farm Facility Security

#### 12.5.1 Farm-Specific Controls

| Area | Security Measure |
|------|-----------------|
| Farm Perimeter | Fencing; gated access |
| Administrative Building | Key access; alarm system |
| Technology Infrastructure | Locked equipment rooms |
| Quarantine Areas | Controlled access; biosecurity |
| Milk Processing | Hygiene + access controls |

#### 12.5.2 Biosecurity Integration

Physical security integrates with farm biosecurity:
- Visitor screening for biosecurity and security
- Equipment sanitization procedures
- Controlled entry points

### 12.6 Equipment Security

#### 12.6.1 Workstation Security

- Lock screens when away from desk
- Secure workstations at end of day
- Report missing equipment immediately
- No food/drink near electronic equipment

#### 12.6.2 Laptop Security

- Never leave laptops unattended in public
- Store in locked drawer when not in use
- Use cable locks in public places
- Encrypt all laptop hard drives

#### 12.6.3 Mobile Device Security

- Keep devices with you or secured
- Enable remote wipe capability
- Report loss/theft immediately
- Use privacy screens in public areas

### 12.7 Clean Desk Policy

At the end of each workday or when leaving for extended periods:
- Remove all documents from desk
- Lock sensitive documents in drawers
- Secure portable devices
- Lock computer screen
- Clear whiteboards

### 12.8 Physical Security Incidents

Report immediately:
- Unauthorized individuals in secure areas
- Lost or stolen access badges
- Tailgating attempts
- Suspicious packages or mail
- Damage to security equipment
- After-hours unauthorized access attempts

---

## 13. Change Management Policy

### 13.1 Policy Statement

All changes to Smart Dairy's information systems must follow a formal change management process to minimize disruption and security risks. Security review is required for all changes to production systems.

### 13.2 Change Classification

| Type | Definition | Approval Required |
|------|------------|-------------------|
| **Standard** | Pre-approved; low risk; documented procedure | Change Manager |
| **Normal** | Requires assessment and approval | CAB (Change Advisory Board) |
| **Emergency** | Urgent fix to restore service | Emergency CAB |

### 13.3 Change Categories

| Category | Examples |
|----------|----------|
| **Hardware** | Server installation, network equipment, storage |
| **Software** | Application updates, OS patches, new software |
| **Network** | Firewall rules, VLAN changes, DNS updates |
| **Security** | Access controls, encryption settings, certificates |
| **Configuration** | System settings, application parameters |
| **Infrastructure** | Cloud resources, virtualization |

### 13.4 Change Management Process

```
┌────────────────────────────────────────────────────────────────┐
│                    CHANGE MANAGEMENT PROCESS                    │
├────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Request ──▶ Assess ──▶ Plan ──▶ Approve ──▶ Test ──▶ Deploy   │
│    │           │         │          │         │        │       │
│    ▼           ▼         ▼          ▼         ▼        ▼       │
│  RFC       Impact    Schedule      CAB      UAT     Change      │
│  Submitted Analysis   Window    Approval   Testing   Window    │
│                                                     │           │
│                                                     ▼           │
│                                            Post-Implementation │
│                                                 Review          │
│                                                                 │
└────────────────────────────────────────────────────────────────┘
```

### 13.5 Security Review Requirements

#### 13.5.1 Security Assessment

All changes must be assessed for:
- Security impact and risk
- Compliance implications
- Access control changes
- Data protection requirements
- Audit logging requirements

#### 13.5.2 Security Review Checklist

- [ ] Vulnerability assessment completed (for significant changes)
- [ ] Security configuration reviewed
- [ ] Access controls validated
- [ ] Encryption requirements verified
- [ ] Audit logging confirmed
- [ ] Rollback plan includes security considerations

### 13.6 Change Advisory Board (CAB)

| Role | Responsibility |
|------|---------------|
| **CAB Chair** | IT Director; final approval authority |
| **Security Representative** | CISO or delegate; security risk assessment |
| **Operations Representative** | Operations Manager; implementation feasibility |
| **Application Representative** | Application Owner; business impact |
| **Infrastructure Representative** | Infrastructure Lead; technical assessment |

### 13.7 Emergency Changes

#### 13.7.1 Emergency Criteria

Emergency changes are permitted for:
- Critical security vulnerability remediation
- System outage restoration
- Data loss prevention
- Regulatory compliance requirement

#### 13.7.2 Emergency Process

1. Emergency CAB convened (minimum 2 members)
2. Risk assessment expedited
3. Verbal approval with documentation
4. Change implemented
5. Full documentation completed within 24 hours
6. Retrospective review within 1 week

### 13.8 Change Documentation

All changes must document:
- RFC (Request for Change) number
- Description and justification
- Impact assessment
- Implementation plan
- Testing results
- Rollback plan
- Approval records
- Post-implementation review

---

## 14. Monitoring & Privacy Policy

### 14.1 Policy Statement

Smart Dairy monitors its information systems to ensure security, compliance, and operational integrity. This policy establishes the scope of monitoring and balances security needs with employee privacy expectations.

### 14.2 Monitoring Scope

#### 14.2.1 Systems Monitored

| System | Monitoring Type |
|--------|----------------|
| **Network Traffic** | Flow analysis, DLP inspection |
| **Email** | Metadata, content scanning (for DLP/security) |
| **Web Access** | URL filtering, activity logging |
| **Endpoint Activity** | Process execution, file access |
| **Authentication** | Login attempts, access patterns |
| **Data Access** | Database queries, file access |
| **Privileged Operations** | Command logging, session recording |

#### 14.2.2 Physical Monitoring

- Video surveillance in common areas
- Access control logs
- Visitor sign-in records
- No surveillance in private areas (restrooms, break rooms)

### 14.3 Privacy Expectations

#### 14.3.1 No Expectation of Privacy

Employees are informed that:
- Company systems are for business purposes
- All use may be monitored
- Deleted data may still exist in backups
- Personal use is subject to monitoring

#### 14.3.2 Limited Privacy Areas

| Context | Privacy Level |
|---------|--------------|
| Personal email (webmail on company systems) | Subject to monitoring |
| Personal devices (without MDM) | Not monitored |
| Break rooms, non-work areas | Video only; no audio |
| Personal phone calls | Not monitored |

### 14.4 Monitoring Practices

#### 14.4.1 Proactive Monitoring

| Activity | Purpose |
|----------|---------|
| **Security Event Monitoring** | Detect intrusions, malware |
| **Performance Monitoring** | System health, capacity |
| **Compliance Monitoring** | Policy adherence |
| **DLP Monitoring** | Prevent data exfiltration |

#### 14.4.2 Investigative Monitoring

Enhanced monitoring may occur:
- During security incident investigation
- Based on suspicious activity reports
- As required by legal proceedings
- With management approval for performance issues

### 14.5 Log Management

#### 14.5.1 Log Retention

| Log Type | Retention Period |
|----------|-----------------|
| Authentication logs | 1 year |
| Firewall logs | 1 year |
| Email logs | 2 years |
| Web proxy logs | 1 year |
| System logs | 1 year |
| Security incident logs | 7 years |
| Audit trails (privileged) | 7 years |

#### 14.5.2 Log Protection

- Logs stored on tamper-resistant systems
- Access to logs restricted to authorized personnel
- Regular integrity verification
- Secure backup and archiving

### 14.6 Privacy Rights

#### 14.6.1 Employee Rights

Employees have the right to:
- Know what monitoring occurs (this policy)
- Request access to their personal data
- Request correction of inaccurate data
- Understand how their data is used

#### 14.6.2 Data Subject Requests

Requests regarding personal data:
- Must be submitted in writing to HR
- Will be processed within 30 days
- May require identity verification
- Subject to legal limitations

### 14.7 Data Protection

#### 14.7.1 Collected Data

Monitoring data collected includes:
- Activity timestamps
- System commands executed
- Files accessed
- Network connections
- Email metadata

#### 14.7.2 Data Usage

Collected data used for:
- Security investigations
- Compliance reporting
- Performance analysis
- Legal proceedings (if required)

#### 14.7.3 Data Sharing

Monitoring data shared only:
- With authorized personnel
- As required by law
- For legitimate business purposes
- With appropriate safeguards

---

## 15. Policy Enforcement

### 15.1 Policy Statement

All security policies are mandatory and must be enforced consistently. Violations will result in disciplinary action appropriate to the severity of the violation. Exceptions require formal approval through the exception process.

### 15.2 Policy Compliance

#### 15.2.1 Individual Responsibilities

All users must:
- Read and understand applicable policies
- Acknowledge policies annually
- Comply with all policy requirements
- Report violations
- Complete required training

#### 15.2.2 Manager Responsibilities

Managers must:
- Enforce policies within their teams
- Report violations to HR and Security
- Ensure team members complete training
- Model compliant behavior

### 15.3 Violation Classification

| Severity | Definition | Examples |
|----------|------------|----------|
| **Critical** | Deliberate action causing severe risk | Data theft, intentional malware introduction, system sabotage |
| **High** | Significant policy violation or negligent action | Sharing passwords, disabling security controls, unauthorized data access |
| **Medium** | Moderate violation or repeated minor violations | Unattended unlocked workstation, repeated phishing test failures |
| **Low** | Minor infraction; first-time occurrence | Not locking screen once, minor password policy violation |

### 15.4 Consequences

#### 15.4.1 Disciplinary Actions

| Severity | Potential Actions |
|----------|-------------------|
| **Critical** | Immediate termination; legal prosecution; industry blacklisting |
| **High** | Written warning; suspension; termination; loss of access privileges |
| **Medium** | Verbal or written warning; mandatory retraining; temporary access restriction |
| **Low** | Verbal reminder; coaching; documentation |

#### 15.4.2 Consequence Factors

Disciplinary decisions consider:
- Intent (deliberate vs. negligent)
- Impact (actual and potential)
- History (first-time vs. repeat)
- Cooperation (responsiveness to investigation)
- Remediation (efforts to correct)

### 15.5 Exception Process

#### 15.5.1 Exception Request

To request a policy exception:
1. Complete Policy Exception Request Form (Appendix E)
2. Document business justification
3. Identify compensating controls
4. Specify exception duration
5. Obtain manager approval
6. Submit to CISO for review

#### 15.5.2 Exception Approval

| Exception Type | Approval Authority |
|----------------|-------------------|
| Technical security exception | CISO |
| Operational exception | CISO + Department Head |
| High-risk exception | CISO + IT Director |
| Extended duration (>90 days) | CISO + Managing Director |

#### 15.5.3 Exception Requirements

Exceptions must include:
- Clear business justification
- Risk assessment
- Compensating controls
- Time limitation (maximum 1 year)
- Review date
- Responsible party

### 15.6 Appeals Process

Employees may appeal disciplinary actions:
1. Submit written appeal to HR within 5 business days
2. HR reviews with relevant parties
3. Decision communicated within 10 business days
4. Final appeal to Managing Director if requested

### 15.7 Annual Review

All policies reviewed annually:
- Effectiveness assessment
- Compliance analysis
- Incident lessons learned
- Regulatory changes
- Technology changes
- Industry best practices

---

## 16. Appendices

### Appendix A: Policy Templates

#### A.1 Policy Statement Template

```markdown
# [Policy Name]

## Document Control
- Version: X.X
- Effective Date: [Date]
- Owner: [Role]
- Review Cycle: [Frequency]

## Policy Statement
[Clear, concise statement of the policy]

## Scope
[Who and what the policy applies to]

## Policy Details
[Specific requirements]

## Compliance
[How compliance is measured]

## Exceptions
[Exception process reference]
```

#### A.2 Standard Operating Procedure Template

```markdown
# SOP: [Title]

## Purpose
[Why this SOP exists]

## Scope
[When to use this SOP]

## Responsibilities
[Who does what]

## Procedure
1. [Step 1]
2. [Step 2]
3. [Step 3]

## References
[Related documents]

## Revision History
| Version | Date | Author | Changes |
```

---

### Appendix B: User Acknowledgement Forms

#### B.1 Acceptable Use Policy Acknowledgement

---

**SMART DAIRY LTD.**
**Acceptable Use Policy Acknowledgement**

---

Employee Information:
- Name: _________________________________
- Employee ID: __________________________
- Department: ___________________________
- Position: _____________________________
- Start Date: ___________________________

I acknowledge that I have received, read, and understand the Smart Dairy Ltd. Acceptable Use Policy (F-007, Section 2). I agree to comply with all terms and conditions stated in the policy.

I understand that:
- Company systems are for business purposes
- My use may be monitored
- Violations may result in disciplinary action
- I am responsible for protecting company information

Signature: _________________________________
Date: _____________________________________

Manager Signature: _________________________
Date: _____________________________________

---

**For HR/File:**
- Filed by: ________________________________
- Date Filed: ______________________________
- Location: ________________________________

---

#### B.2 Security Policies General Acknowledgement

---

**SMART DAIRY LTD.**
**Information Security Policies Acknowledgement**

---

I, _________________________________ (Employee Name), acknowledge the following:

1. I have received and read the Smart Dairy Ltd. Security Policies & Procedures (Document F-007)
2. I understand my responsibilities under these policies including:
   - Acceptable Use Policy
   - Password Policy
   - Data Classification Policy
   - Incident Reporting requirements
   - Mobile Device Policy (if applicable)
   - Remote Access Policy (if applicable)

3. I understand that security is a shared responsibility
4. I agree to report security incidents and suspicious activities
5. I understand the consequences of policy violations

6. I have completed the required security awareness training

Signature: _________________________________
Date: _____________________________________

Employee ID: _______________________________
Department: ________________________________

Next Review Due: ___________________________

---

### Appendix C: Access Request Form

---

**SMART DAIRY LTD.**
**System Access Request Form**

---

**Request Information:**
- Request Date: ___________________________
- Request Type: [ ] New Employee  [ ] Role Change  [ ] Additional Access  [ ] Temporary
- Requested By: ___________________________

**Employee Information:**
- Name: _________________________________
- Employee ID: __________________________
- Department: ___________________________
- Position/Title: _________________________
- Manager: ______________________________
- Start Date: ____________________________
- Employment Type: [ ] Full-time  [ ] Part-time  [ ] Contract  [ ] Temporary

**Access Requested:**

| System/Application | Access Level | Justification | Duration |
|-------------------|--------------|---------------|----------|
| | | | |
| | | | |
| | | | |

**Data Classification Access:**
- [ ] RESTRICTED (requires CISO approval)
- [ ] CONFIDENTIAL
- [ ] INTERNAL
- [ ] PUBLIC only

**Approvals:**

Manager Approval:
- Name: ________________________________
- Signature: ___________________________
- Date: ________________________________

Department Head Approval (if required):
- Name: ________________________________
- Signature: ___________________________
- Date: ________________________________

IT Security Approval:
- Name: ________________________________
- Signature: ___________________________
- Date: ________________________________

CISO Approval (RESTRICTED access):
- Name: ________________________________
- Signature: ___________________________
- Date: ________________________________

**IT Implementation:**
- Completed By: ________________________
- Completion Date: _____________________
- Access Enabled: ______________________

---

### Appendix D: Work From Home Security Agreement

---

**SMART DAIRY LTD.**
**Work From Home Security Agreement**

---

Employee Name: _____________________________
Employee ID: ______________________________
Department: ________________________________
Manager: ___________________________________

**Agreement Terms:**

I understand and agree to the following security requirements while working from home:

**Workspace Security:**
- [ ] I will maintain a secure, private workspace
- [ ] I will ensure my screen is not visible to others
- [ ] I will lock my workstation when not in use
- [ ] I will secure company equipment when not in use

**Network Security:**
- [ ] I will use only secured home Wi-Fi (WPA2/WPA3)
- [ ] I will not use public Wi-Fi for company business
- [ ] I will use VPN for all company network access
- [ ] I will keep router firmware updated

**Data Protection:**
- [ ] I will not store company data on personal devices
- [ ] I will use only approved cloud storage
- [ ] I will shred physical documents when no longer needed
- [ ] I will report lost/stolen equipment immediately

**Equipment:**
- [ ] I will use company-provided equipment when available
- [ ] I will ensure personal devices used meet BYOD requirements
- [ ] I will report equipment issues promptly

**Acceptance:**

I understand that violation of these requirements may result in termination of WFH privileges and/or disciplinary action.

Employee Signature: _________________________
Date: _____________________________________

Manager Approval: __________________________
Date: _____________________________________

IT Security Acknowledgement: ________________
Date: _____________________________________

---

### Appendix E: Policy Exception Request Form

---

**SMART DAIRY LTD.**
**Policy Exception Request**

---

**Request Information:**
- Date: ___________________________________
- Requested By: ___________________________
- Department: _____________________________

**Policy Information:**
- Policy Document: ________________________
- Policy Section: _________________________
- Specific Requirement: ____________________

**Exception Details:**
- Duration Requested: _____________________
- Business Justification: 

_________________________________________________________________

_________________________________________________________________

_________________________________________________________________

**Risk Assessment:**
- Risk if Exception Granted: ______________
- Compensating Controls Proposed:

_________________________________________________________________

_________________________________________________________________

**Approvals:**

Manager Approval:
- Name: ________________________________
- Signature: ___________________________
- Date: ________________________________

CISO Review:
- Risk Assessment: _____________________
- Recommendation: _____________________
- Signature: ___________________________
- Date: ________________________________

Final Approval (if required):
- Authority: ___________________________
- Signature: ___________________________
- Date: ________________________________

**Exception Granted:**
- [ ] Approved  [ ] Denied  [ ] Approved with Conditions
- Conditions: __________________________
- Review Date: _________________________

---

### Appendix F: Policy Review Schedule

| Policy Section | Owner | Review Frequency | Last Review | Next Review |
|----------------|-------|------------------|-------------|-------------|
| 1. Introduction | CISO | Annual | Jan 2026 | Jan 2027 |
| 2. Acceptable Use | CISO | Annual | Jan 2026 | Jan 2027 |
| 3. Access Control | IT Director | Annual | Jan 2026 | Jan 2027 |
| 4. Password Policy | CISO | Annual | Jan 2026 | Jan 2027 |
| 5. Remote Access | IT Director | Annual | Jan 2026 | Jan 2027 |
| 6. Data Classification | CISO | Annual | Jan 2026 | Jan 2027 |
| 7. Incident Response | CISO | Annual | Jan 2026 | Jan 2027 |
| 8. Mobile Device | IT Director | Annual | Jan 2026 | Jan 2027 |
| 9. Email Security | CISO | Annual | Jan 2026 | Jan 2027 |
| 10. Social Engineering | CISO | Annual | Jan 2026 | Jan 2027 |
| 11. Vendor Security | CISO | Annual | Jan 2026 | Jan 2027 |
| 12. Physical Security | Facilities Mgr | Annual | Jan 2026 | Jan 2027 |
| 13. Change Management | IT Director | Annual | Jan 2026 | Jan 2027 |
| 14. Monitoring & Privacy | CISO | Annual | Jan 2026 | Jan 2027 |
| 15. Policy Enforcement | CISO | Annual | Jan 2026 | Jan 2027 |

### Appendix G: Glossary

| Term | Definition |
|------|------------|
| **Authentication** | Process of verifying identity |
| **Authorization** | Process of granting access based on identity |
| **BYOD** | Bring Your Own Device |
| **CAB** | Change Advisory Board |
| **CISO** | Chief Information Security Officer |
| **Data Classification** | Categorizing data by sensitivity |
| **DLP** | Data Loss Prevention |
| **DMARC** | Domain-based Message Authentication |
| **Encryption** | Converting data to unreadable format |
| **IRT** | Incident Response Team |
| **MDM** | Mobile Device Management |
| **MFA** | Multi-Factor Authentication |
| **PAW** | Privileged Access Workstation |
| **PCI DSS** | Payment Card Industry Data Security Standard |
| **PII** | Personally Identifiable Information |
| **RBAC** | Role-Based Access Control |
| **SaaS** | Software as a Service |
| **VPN** | Virtual Private Network |
| **WFH** | Work From Home |

---

## Document End

---

*This document is the property of Smart Dairy Ltd. Unauthorized distribution or copying is prohibited.*

**Classification:** INTERNAL USE ONLY

**Document ID:** F-007  
**Version:** 1.0  
**Date:** January 31, 2026
