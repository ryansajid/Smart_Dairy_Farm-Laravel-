# SMART DAIRY LTD.
## INCIDENT RESPONSE PLAN
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | F-011 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Security Lead |
| **Owner** | Security Lead |
| **Reviewer** | CTO |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Incident Response Team](#2-incident-response-team)
3. [Incident Classification](#3-incident-classification)
4. [Response Procedures](#4-response-procedures)
5. [Communication Plan](#5-communication-plan)
6. [Containment Strategies](#6-containment-strategies)
7. [Eradication & Recovery](#7-eradication--recovery)
8. [Post-Incident Activities](#8-post-incident-activities)
9. [Incident Types](#9-incident-types)
10. [Tools & Resources](#10-tools--resources)
11. [Appendices](#11-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This Incident Response Plan (IRP) establishes the framework for detecting, responding to, and recovering from security incidents affecting the Smart Dairy Smart Web Portal System and Integrated ERP. The plan ensures a coordinated, effective response to minimize impact and restore normal operations.

### 1.2 Scope

This plan covers:
- **Cybersecurity incidents** - Attacks, breaches, malware
- **Infrastructure incidents** - System failures, outages, data loss
- **Application incidents** - Bugs, performance degradation
- **Compliance incidents** - Data breaches, regulatory violations
- **Third-party incidents** - Vendor/service provider issues

### 1.3 Objectives

1. Minimize business impact and data loss
2. Protect customer and company data
3. Maintain regulatory compliance
4. Preserve evidence for investigation
5. Restore services within defined SLAs
6. Prevent recurrence through lessons learned

---

## 2. INCIDENT RESPONSE TEAM

### 2.1 Team Structure

```
┌─────────────────────────────────────────────────────────────────┐
│              INCIDENT RESPONSE TEAM STRUCTURE                    │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────────────────────────────────────────────────────────┐│
│  │  INCIDENT COMMANDER (IC)                                     ││
│  │  • Overall incident ownership                                ││
│  │  • Resource allocation                                       ││
│  │  • External communications approval                          ││
│  │  • Primary: CTO / Secondary: Security Lead                   ││
│  └─────────────────────────────────────────────────────────────┘│
│                              │                                   │
│         ┌────────────────────┼────────────────────┐              │
│         ▼                    ▼                    ▼              │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐          │
│  │ TECHNICAL   │    │ COMMUNICA-  │    │ LEGAL/      │          │
│  │ LEAD        │    │ TIONS LEAD  │    │ COMPLIANCE  │          │
│  │             │    │             │    │ LEAD        │          │
│  │ • Technical │    │ • Internal  │    │ • Regulatory│          │
│  │   response  │    │   comms     │    │   reporting │          │
│  │ • Evidence  │    │ • Customer  │    │ • Legal     │          │
│  │   collection│    │   notificatn│    │   counsel   │          │
│  │ • Recovery  │    │ • Media     │    │ • Contracts │          │
│  └─────────────┘    └─────────────┘    └─────────────┘          │
│         │                                           │            │
│         ▼                                           ▼            │
│  ┌─────────────┐                           ┌─────────────┐      │
│  │ SUBJECT     │                           │ HR LEAD     │      │
│  │ MATTER      │                           │ (if needed) │      │
│  │ EXPERTS     │                           │             │      │
│  │             │                           │ • Employee  │      │
│  │ • Security  │                           │   incidents │      │
│  │ • DevOps    │                           │ • Insider   │      │
│  │ • Database  │                           │   threats   │      │
│  │ • App Dev   │                           └─────────────┘      │
│  └─────────────┘                                                │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 Roles and Responsibilities

| Role | Primary | Responsibilities |
|------|---------|------------------|
| **Incident Commander** | CTO | Overall incident management, stakeholder communication |
| **Technical Lead** | Security Lead | Technical investigation, containment, recovery |
| **Communications Lead** | Marketing Manager | Internal/external communications, customer updates |
| **Legal/Compliance Lead** | Legal Counsel | Regulatory reporting, legal obligations |
| **DevOps Lead** | DevOps Manager | Infrastructure response, system recovery |
| **Database Administrator** | DBA | Data integrity, backup/restore operations |

### 2.3 Contact Information

| Role | Primary Contact | Secondary Contact |
|------|-----------------|-------------------|
| Incident Commander | CTO: +880-XXXX-XXXXXX | Security Lead: +880-XXXX-XXXXXX |
| Technical Lead | Security Lead: +880-XXXX-XXXXXX | Senior DevOps: +880-XXXX-XXXXXX |
| Communications | Marketing Mgr: +880-XXXX-XXXXXX | PR Agency: +880-XXXX-XXXXXX |
| Legal | Legal Counsel: +880-XXXX-XXXXXX | External Firm: +880-XXXX-XXXXXX |
| AWS Support | Enterprise Support | 24/7 Hotline |

---

## 3. INCIDENT CLASSIFICATION

### 3.1 Severity Levels

| Severity | Criteria | Response Time | Escalation |
|----------|----------|---------------|------------|
| **P1 - Critical** | System down, data breach, active attack, payment failure | 15 minutes | CEO + Board |
| **P2 - High** | Major feature failure, performance degradation, potential breach | 1 hour | CTO + Management |
| **P3 - Medium** | Minor feature issues, non-critical bugs | 4 hours | Team Lead |
| **P4 - Low** | Cosmetic issues, enhancement requests | 24 hours | No escalation |

### 3.2 Incident Categories

```
┌─────────────────────────────────────────────────────────────────┐
│                    INCIDENT CATEGORIES                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  SECURITY INCIDENTS                                              │
│  ├── Data Breach (Customer/Employee data exposure)              │
│  ├── Unauthorized Access (Account compromise, insider threat)   │
│  ├── Malware/Ransomware (Infection, encryption)                 │
│  ├── DDoS Attack (Service unavailability)                       │
│  ├── Web Application Attack (SQLi, XSS, CSRF)                   │
│  └── API Abuse (Rate limit violations, scraping)                │
│                                                                  │
│  INFRASTRUCTURE INCIDENTS                                        │
│  ├── System Outage (Complete service failure)                   │
│  ├── Performance Degradation (Slow response times)              │
│  ├── Data Loss (Corruption, accidental deletion)                │
│  └── Network Failure (Connectivity issues)                      │
│                                                                  │
│  APPLICATION INCIDENTS                                           │
│  ├── Critical Bug (Data integrity, payment processing)          │
│  ├── Security Vulnerability (CVE, zero-day)                     │
│  └── Third-party Failure (Payment gateway, SMS provider)        │
│                                                                  │
│  COMPLIANCE INCIDENTS                                            │
│  ├── Regulatory Breach (VAT, data protection)                   │
│  ├── Audit Finding (Internal/external audit)                    │
│  └── Policy Violation (Security policy breach)                  │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 4. RESPONSE PROCEDURES

### 4.1 Incident Response Lifecycle

```
┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐
│ PREPARE  │───▶│ IDENTIFY │───▶│ CONTAIN  │───▶│ ERADICATE│───▶│ RECOVER  │
│          │    │          │    │          │    │          │    │          │
└──────────┘    └──────────┘    └──────────┘    └──────────┘    └────┬─────┘
       ▲                                                             │
       │                                                             ▼
       │                                                      ┌──────────┐
       └──────────────────────────────────────────────────────│  LESSONS │
                                                              │  LEARNED │
                                                              └──────────┘
```

### 4.2 Phase 1: Preparation

| Activity | Description | Frequency |
|----------|-------------|-----------|
| IR Team Training | Tabletop exercises, simulations | Quarterly |
| Tool Validation | Verify logging, monitoring, forensics tools | Monthly |
| Contact Updates | Update emergency contact lists | Monthly |
| Runbook Review | Update response procedures | Quarterly |
| Backup Testing | Verify backup and recovery capabilities | Monthly |

### 4.3 Phase 2: Identification

```python
# Automated incident detection and alerting
INCIDENT_DETECTION_RULES = {
    "data_exfiltration": {
        "condition": "outbound_traffic > 10GB AND destination NOT IN trusted_ips",
        "severity": "P1",
        "auto_alert": True
    },
    "brute_force_attack": {
        "condition": "failed_logins > 10 FROM same_ip IN 5_minutes",
        "severity": "P2",
        "auto_response": "block_ip"
    },
    "unauthorized_access": {
        "condition": "login_from_new_location AND user.is_admin",
        "severity": "P2",
        "auto_alert": True
    },
    "database_anomaly": {
        "condition": "query_count > 10x_average OR large_table_dump",
        "severity": "P1",
        "auto_alert": True
    },
    "payment_anomaly": {
        "condition": "failed_payments > 20% IN 1_hour",
        "severity": "P1",
        "auto_alert": True
    }
}
```

**Detection Checklist:**
- [ ] Verify incident through multiple sources
- [ ] Document initial observations (timestamp, symptoms, scope)
- [ ] Classify severity level
- [ ] Notify Incident Commander
- [ ] Create incident ticket in tracking system
- [ ] Begin evidence collection

### 4.4 Phase 3: Containment

**Short-term Containment (Immediate - 1 hour)**

| Action | Command/Procedure | Responsible |
|--------|-------------------|-------------|
| Isolate affected systems | `kubectl cordon <node>` | DevOps Lead |
| Block malicious IPs | Update WAF rules / Security Groups | Security Lead |
| Disable compromised accounts | `UPDATE users SET active=false WHERE id=<id>` | DBA |
| Revoke API tokens | Invalidate in Redis/Database | Security Lead |
| Enable enhanced logging | Increase log verbosity | DevOps Lead |
| Preserve evidence | Create disk/memory snapshots | Security Lead |

**Long-term Containment (1-4 hours)**

| Action | Description | Timeline |
|--------|-------------|----------|
| Deploy security patches | Apply fixes to vulnerabilities | 2 hours |
| Implement additional monitoring | Enhanced detection rules | 2 hours |
| Segment network | Isolate critical systems | 4 hours |
| Enable MFA enforcement | Require MFA for all access | 4 hours |
| Rotate credentials | Force password/API key rotation | 4 hours |

### 4.5 Phase 4: Eradication

```bash
#!/bin/bash
# Incident eradication procedures

# 1. Remove malware/backdoors
clamscan -r /var/www --remove=yes
rkhunter --check --sk

# 2. Patch vulnerabilities
apt-get update && apt-get upgrade -y
kubectl set image deployment/smart-dairy-odoo odoo=smartdairy/odoo:patched-v1.2.3

# 3. Remove unauthorized accounts
psql -c "DELETE FROM res_users WHERE login LIKE '%suspicious%'"

# 4. Clean compromised data
# (After forensic analysis)
psql -c "UPDATE audit_log SET compromised=true WHERE timestamp BETWEEN '2026-01-30' AND '2026-01-31'"

# 5. Verify system integrity
aide --check
sha256sum -c /etc/smart-dairy/file-manifest.sha256
```

### 4.6 Phase 5: Recovery

| Step | Action | Verification |
|------|--------|--------------|
| 1 | Restore systems from clean backups | Check file hashes |
| 2 | Rebuild affected servers | Fresh OS + hardened config |
| 3 | Apply security patches | Vulnerability scan |
| 4 | Re-enable services gradually | Monitor for anomalies |
| 5 | Restore user access with new credentials | Verify MFA enrollment |
| 6 | Resume normal operations | Performance benchmarks |

### 4.7 Phase 6: Lessons Learned

**Post-Incident Review Template:**

| Question | Answer |
|----------|--------|
| What happened? | Timeline of events |
| How was it detected? | Detection method, time to detect |
| What was the impact? | Data, systems, business affected |
| How was it contained? | Actions taken, effectiveness |
| What could be improved? | Process, tooling, training gaps |
| What preventive measures? | Technical and procedural |

---

## 5. COMMUNICATION PLAN

### 5.1 Communication Matrix

| Stakeholder | P1 Incident | P2 Incident | P3/P4 Incident |
|-------------|-------------|-------------|----------------|
| **Executive Team** | Immediate | Within 1 hour | Daily digest |
| **IR Team** | Immediate | Immediate | As needed |
| **Employees** | Within 1 hour | Same day | Not required |
| **Customers** | Within 4 hours | Within 24 hours | Not required |
| **Regulators** | As legally required | As required | Not required |
| **Media** | If public impact | As needed | Not required |

### 5.2 Communication Templates

**Internal Notification (P1)**
```
Subject: [INCIDENT] Critical Security Incident - Response Activated

A critical security incident has been identified affecting [SYSTEMS].

INCIDENT SUMMARY:
- Type: [DATA BREACH / ATTACK / OUTAGE]
- Detected: [TIMESTAMP]
- Severity: P1 - CRITICAL
- Impact: [BRIEF DESCRIPTION]

IMMEDIATE ACTIONS:
- Incident Response Team activated
- Affected systems isolated
- Investigation in progress

DO NOT:
- Discuss externally
- Post on social media
- Contact customers directly

Updates will be provided every 30 minutes.

Incident Commander: [NAME] | [PHONE]
```

**Customer Notification (Data Breach)**
```
Subject: Important Security Notice - Smart Dairy

Dear Valued Customer,

We are writing to inform you of a security incident that may have affected 
some of your account information.

WHAT HAPPENED:
On [DATE], we detected unauthorized access to our systems. We immediately 
initiated our incident response procedures and launched an investigation.

INFORMATION INVOLVED:
The accessed information may have included: [SPECIFIC DATA TYPES]

WHAT WE ARE DOING:
- Secured the vulnerability and removed unauthorized access
- Engaged cybersecurity experts for investigation
- Notified relevant authorities
- Implemented additional security measures

WHAT YOU SHOULD DO:
- Change your password immediately
- Enable two-factor authentication
- Monitor your account for unusual activity

We sincerely apologize for this incident and any inconvenience caused.

For questions, contact: security@smartdairybd.com | +880-XXXX-XXXXXX
```

---

## 6. CONTAINMENT STRATEGIES

### 6.1 Network Segmentation

```yaml
# Emergency network isolation
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata:
  name: incident-isolation
  namespace: production
spec:
  podSelector:
    matchLabels:
      incident-affected: "true"
  policyTypes:
    - Ingress
    - Egress
  # Block all ingress/egress except to security scanning
  ingress: []
  egress:
    - to:
        - namespaceSelector:
            matchLabels:
              name: security-tools
      ports:
        - protocol: TCP
          port: 443
```

### 6.2 Account Lockdown

```sql
-- Disable all non-essential accounts during incident
UPDATE res_users 
SET active = false 
WHERE id NOT IN (
  SELECT user_id FROM incident_response_team
);

-- Force password reset for all users
UPDATE res_users 
SET password = NULL, 
    password_write_date = NULL,
    mfa_required = true;

-- Revoke all active sessions
DELETE FROM ir_sessions;

-- Invalidate all API tokens
UPDATE res_users_apikeys SET active = false;
```

---

## 7. ERADICATION & RECOVERY

### 7.1 System Rebuild Checklist

- [ ] Provision new infrastructure (don't reuse compromised systems)
- [ ] Install hardened base image
- [ ] Apply latest security patches
- [ ] Install monitoring agents
- [ ] Deploy application from verified source
- [ ] Restore data from clean backup (pre-incident)
- [ ] Verify no persistence mechanisms
- [ ] Run security scans before go-live

### 7.2 Data Recovery

| Scenario | Recovery Method | Estimated Time |
|----------|-----------------|----------------|
| Accidental deletion | Point-in-time restore | 2-4 hours |
| Data corruption | Restore from clean backup | 4-8 hours |
| Ransomware | Clean rebuild + data restore | 8-24 hours |
| Database compromise | Full database rebuild | 12-24 hours |

---

## 8. POST-INCIDENT ACTIVITIES

### 8.1 Forensic Analysis

| Activity | Description | Timeline |
|----------|-------------|----------|
| Evidence preservation | Secure logs, disk images, memory dumps | Immediate |
| Timeline reconstruction | Establish attack timeline | 24-48 hours |
| Root cause analysis | Determine vulnerability/exploit | 48-72 hours |
| Impact assessment | Quantify data/system impact | 48-72 hours |
| Attribution (if applicable) | Identify threat actor | 1-2 weeks |

### 8.2 Regulatory Reporting

| Regulation | Trigger | Timeline | Authority |
|------------|---------|----------|-----------|
| Bangladesh DPA | Personal data breach | 72 hours | Bangladesh DPA Office |
| PCI DSS | Payment card data breach | 24 hours | Acquiring bank, PCI SSC |
| ISO 27001 | Significant security event | As per procedure | Certification body |
| Company Policy | Any P1 incident | 24 hours | Board of Directors |

---

## 9. INCIDENT TYPES

### 9.1 Data Breach Response

**Immediate Actions (0-1 hour):**
1. Identify scope of data accessed
2. Isolate affected systems
3. Preserve forensic evidence
4. Activate Legal/Compliance lead
5. Assess notification obligations

**Investigation Questions:**
- What data was accessed?
- How many records affected?
- Who was responsible?
- Is the breach ongoing?

### 9.2 Ransomware Response

**DO:**
- Isolate infected systems immediately
- Preserve evidence for investigation
- Contact law enforcement
- Assess backup integrity
- Engage cybersecurity experts

**DON'T:**
- Pay the ransom without legal consultation
- Connect infected systems to network
- Delete encrypted files
- Communicate with attackers without guidance

### 9.3 DDoS Attack Response

```bash
# Activate DDoS mitigation
# 1. Enable AWS Shield Advanced
aws shield create-protection --name SmartDairy --resource-arn <alb-arn>

# 2. Scale up resources
kubectl scale deployment smart-dairy-odoo --replicas=10

# 3. Enable CloudFront/WAF rate limiting
aws wafv2 update-web-acl --name SmartDairy-WAF \
  --rules '[{"Name":"RateLimit","Priority":1,"Statement":{"RateBasedStatement":{"Limit":2000,"AggregateKeyType":"IP"}},"Action":{"Block":{}},"VisibilityConfig":{"SampledRequestsEnabled":true,"CloudWatchMetricsEnabled":true,"MetricName":"RateLimitRule"}}]'

# 4. Contact AWS DDoS Response Team (Enterprise Support)
```

---

## 10. TOOLS & RESOURCES

### 10.1 Incident Response Tools

| Category | Tool | Purpose |
|----------|------|---------|
| **SIEM** | Splunk / ELK | Log aggregation, correlation |
| **EDR** | CrowdStrike / SentinelOne | Endpoint detection |
| **Network** | Wireshark, Zeek | Network forensics |
| **Memory** | Volatility | Memory analysis |
| **Disk** | Autopsy, Sleuthkit | Disk forensics |
| **Malware** | Cuckoo Sandbox | Malware analysis |
| **Threat Intel** | MISP, VirusTotal | IOC verification |
| **Communication** | Slack, PagerDuty | Team coordination |

### 10.2 External Resources

| Resource | Contact | Purpose |
|----------|---------|---------|
| Bangladesh Cyber Security Agency | cert@bccs.gov.bd | Government reporting |
| AWS Security | Enterprise Support | Cloud security incidents |
| Cyber Insurance | [Provider] | Coverage, forensics funding |
| External Forensics | [Firm] | Expert investigation |
| Legal Counsel | [Firm] | Regulatory advice |

---

## 11. APPENDICES

### Appendix A: Incident Response Flowchart

```
START
  │
  ▼
[Detection]
  │
  ├─▶ False Positive ──▶ END
  │
  ▼
[Initial Assessment]
  │
  ├─▶ P4 (Low) ────────▶ Standard Ticket
  │
  ▼
[Activate IRT]
  │
  ▼
[Containment]
  │
  ├─▶ Successful? ──NO─▶ Escalate
  │        │
  │       YES
  │        │
  ▼        ▼
[Eradication]
  │
  ▼
[Recovery]
  │
  ▼
[Post-Incident Review]
  │
  ▼
END
```

### Appendix B: Evidence Handling

| Evidence Type | Collection Method | Storage | Chain of Custody |
|---------------|-------------------|---------|------------------|
| System logs | Centralized logging | Write-once storage | Hash verification |
| Disk images | dd, forensic tools | Encrypted drive | Physical custody log |
| Memory dumps | LiME, AVML | Encrypted drive | Hash + signature |
| Network captures | tcpdump, Zeek | Secure storage | Access logging |

---

**END OF INCIDENT RESPONSE PLAN**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Jan 31, 2026 | Security Lead | Initial version |
