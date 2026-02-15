# F-010: Security Audit Checklist

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | F-010 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Security Lead |
| **Owner** | Security Lead |
| **Reviewer** | IT Director |
| **Classification** | CONFIDENTIAL |
| **Status** | APPROVED |

---

## Document History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-31 | Security Lead | Initial release |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Pre-Go-Live Security Audit](#2-pre-go-live-security-audit)
3. [Infrastructure Security Audit](#3-infrastructure-security-audit)
4. [Application Security Audit](#4-application-security-audit)
5. [Database Security Audit](#5-database-security-audit)
6. [Network Security Audit](#6-network-security-audit)
7. [Identity & Access Management Audit](#7-identity--access-management-audit)
8. [Data Protection Audit](#8-data-protection-audit)
9. [Third-Party Integration Audit](#9-third-party-integration-audit)
10. [Mobile App Security Audit](#10-mobile-app-security-audit)
11. [IoT Security Audit](#11-iot-security-audit)
12. [Compliance Audit](#12-compliance-audit)
13. [Operational Security Audit](#13-operational-security-audit)
14. [Audit Execution Guide](#14-audit-execution-guide)
15. [Remediation Tracking](#15-remediation-tracking)
16. [Appendices](#16-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides comprehensive security audit checklists for Smart Dairy Ltd.'s digital infrastructure, applications, and operations. It serves as the definitive reference for conducting security assessments across all systems.

### 1.2 Audit Types

| Audit Type | Description | Frequency | Conducted By |
|------------|-------------|-----------|--------------|
| **Internal Audit** | Self-assessment of security controls | Monthly | Internal Security Team |
| **External Audit** | Third-party independent assessment | Quarterly | External Auditor |
| **Compliance Audit** | Regulatory requirement verification | Semi-annual | Compliance Officer |
| **Technical Audit** | Deep technical security assessment | Quarterly | Security Engineers |
| **Pre-Go-Live Audit** | Production readiness verification | Per release | Security Lead |

### 1.3 Scope

- Web Application (Smart Dairy Portal)
- Mobile Applications (iOS/Android)
- Cloud Infrastructure (AWS)
- Database Systems (PostgreSQL)
- IoT Devices (Smart Farm Sensors)
- Third-party Integrations
- Network Infrastructure
- Identity Management Systems

### 1.4 Risk Scoring Matrix

| Score | Severity | Response Time | Action Required |
|-------|----------|---------------|-----------------|
| 9.0 - 10.0 | CRITICAL | Immediate (< 24h) | Emergency fix required |
| 7.0 - 8.9 | HIGH | 3 days | Must fix before go-live |
| 4.0 - 6.9 | MEDIUM | 14 days | Fix in next sprint |
| 1.0 - 3.9 | LOW | 30 days | Fix as resources allow |

---

## 2. Pre-Go-Live Security Audit

### 2.1 Pre-Go-Live Critical Checks

#### 2.1.1 General Readiness

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| PGL-001 | Readiness | All critical/high vulnerabilities remediated | Vulnerability scan review | ⬜ | Vuln Mgmt System | | CRITICAL |
| PGL-002 | Readiness | Security test plan executed and passed | Test report review | ⬜ | Test Reports | | CRITICAL |
| PGL-003 | Readiness | Penetration test completed with no critical findings | Pentest report | ⬜ | Pentest Report | | CRITICAL |
| PGL-004 | Readiness | Code review completed for all security-critical components | Code review logs | ⬜ | GitLab/GitHub | | CRITICAL |
| PGL-005 | Readiness | Security architecture review approved | Architecture review doc | ⬜ | Confluence | | CRITICAL |
| PGL-006 | Readiness | Incident response plan tested | IR drill report | ⬜ | IR Documentation | | HIGH |
| PGL-007 | Readiness | Rollback plan documented and tested | Rollback test report | ⬜ | Runbooks | | HIGH |
| PGL-008 | Readiness | Monitoring and alerting configured | Dashboard verification | ⬜ | CloudWatch/Datadog | | HIGH |
| PGL-009 | Readiness | SSL/TLS certificates installed and valid | Certificate check | ⬜ | AWS ACM | | CRITICAL |
| PGL-010 | Readiness | Domain security (DNSSEC, SPF, DKIM, DMARC) | DNS configuration check | ⬜ | DNS Records | | HIGH |

#### 2.1.2 Authentication & Authorization

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| PGL-011 | Auth | Multi-factor authentication enabled for all admin accounts | Configuration review | ⬜ | Cognito Console | | CRITICAL |
| PGL-012 | Auth | Strong password policy enforced (min 12 chars, complexity) | Policy verification | ⬜ | IAM/Cognito | | CRITICAL |
| PGL-013 | Auth | Session timeout configured (30 min idle, 8 hours max) | Session config check | ⬜ | Application config | | HIGH |
| PGL-014 | Auth | Account lockout after 5 failed attempts | Brute force test | ⬜ | Test results | | HIGH |
| PGL-015 | Auth | Principle of least privilege implemented | IAM policy review | ⬜ | IAM Console | | CRITICAL |
| PGL-016 | Auth | Service accounts use least privilege access | Service account audit | ⬜ | IAM Audit Report | | HIGH |
| PGL-017 | Auth | API keys rotated and stored in secrets manager | Secrets manager check | ⬜ | AWS Secrets Manager | | CRITICAL |
| PGL-018 | Auth | JWT tokens use secure signing (RS256) and short expiry | Token configuration | ⬜ | Code review | | CRITICAL |
| PGL-019 | Auth | OAuth 2.0/PKCE implemented for mobile apps | Auth flow test | ⬜ | Security test report | | HIGH |
| PGL-020 | Auth | Privileged access management (PAM) configured | PAM console check | ⬜ | PAM System | | HIGH |

#### 2.1.3 Data Protection

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| PGL-021 | Encryption | Data at rest encrypted (AES-256) | Encryption config check | ⬜ | AWS Console | | CRITICAL |
| PGL-022 | Encryption | Data in transit encrypted (TLS 1.2+) | SSL Labs scan | ⬜ | SSL Labs Report | | CRITICAL |
| PGL-023 | Encryption | Database encryption enabled (TDE) | RDS configuration | ⬜ | RDS Console | | CRITICAL |
| PGL-024 | Encryption | S3 buckets enforce encryption | S3 bucket policy | ⬜ | S3 Console | | CRITICAL |
| PGL-025 | Encryption | EBS volumes encrypted | EBS configuration | ⬜ | EC2 Console | | HIGH |
| PGL-026 | Encryption | Backup encryption verified | Backup restore test | ⬜ | Backup Reports | | HIGH |
| PGL-027 | PII | PII data classification implemented | Data inventory review | ⬜ | Data Catalog | | CRITICAL |
| PGL-028 | PII | PII access logging enabled | CloudTrail review | ⬜ | CloudTrail Logs | | CRITICAL |
| PGL-029 | PII | Data retention policies configured | Lifecycle policy check | ⬜ | S3/RDS Config | | HIGH |
| PGL-030 | PII | Data anonymization for non-prod environments | Anonymization script review | ⬜ | DevOps Repo | | HIGH |

#### 2.1.4 Infrastructure Security

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| PGL-031 | Network | VPC properly segmented (public/private/isolated) | VPC architecture review | ⬜ | AWS VPC Console | | CRITICAL |
| PGL-032 | Network | Security groups follow least privilege | SG rule review | ⬜ | AWS EC2 Console | | CRITICAL |
| PGL-033 | Network | NACLs configured for additional network layer | NACL review | ⬜ | VPC Console | | MEDIUM |
| PGL-034 | Network | No default security groups used | SG audit | ⬜ | AWS Config | | HIGH |
| PGL-035 | Network | WAF rules configured and tested | WAF rule review | ⬜ | AWS WAF Console | | CRITICAL |
| PGL-036 | Network | DDoS protection enabled (AWS Shield Advanced) | Shield configuration | ⬜ | AWS Shield Console | | HIGH |
| PGL-037 | Network | Network flow logging enabled | VPC Flow Logs check | ⬜ | CloudWatch Logs | | HIGH |
| PGL-038 | Compute | EC2 instances use hardened AMIs | AMI security scan | ⬜ | EC2 Console | | HIGH |
| PGL-039 | Compute | Auto Scaling configured for availability | ASG configuration | ⬜ | EC2 Console | | MEDIUM |
| PGL-040 | Compute | Container images scanned before deployment | ECR scan results | ⬜ | ECR Console | | CRITICAL |

#### 2.1.5 Application Security

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| PGL-041 | Input Validation | All user inputs validated and sanitized | Code review + testing | ⬜ | SAST Report | | CRITICAL |
| PGL-042 | Injection | SQL injection prevention implemented | OWASP ZAP scan | ⬜ | ZAP Report | | CRITICAL |
| PGL-043 | Injection | NoSQL injection prevention implemented | Security test | ⬜ | Test Report | | CRITICAL |
| PGL-044 | Injection | Command injection prevention implemented | Code review | ⬜ | SAST Report | | CRITICAL |
| PGL-045 | XSS | Output encoding to prevent XSS | SAST + DAST scan | ⬜ | Scan Reports | | CRITICAL |
| PGL-046 | XSS | CSP headers implemented and configured | Header inspection | ⬜ | Security Headers | | HIGH |
| PGL-047 | CSRF | CSRF tokens implemented for state-changing operations | Code review | ⬜ | SAST Report | | CRITICAL |
| PGL-048 | Auth | Broken authentication vulnerabilities remediated | Pentest findings | ⬜ | Pentest Report | | CRITICAL |
| PGL-049 | Config | Security misconfigurations remediated | CIS benchmark scan | ⬜ | CIS Report | | HIGH |
| PGL-050 | Components | No known vulnerable components (OWASP Top 10 A06) | Dependency scan | ⬜ | Snyk/OWASP DC | | CRITICAL |

#### 2.1.6 Database Security

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| PGL-051 | Access | Database not publicly accessible | RDS connectivity test | ⬜ | RDS Console | | CRITICAL |
| PGL-052 | Access | Database in private subnets only | VPC configuration | ⬜ | VPC Console | | CRITICAL |
| PGL-053 | Auth | Strong database passwords enforced | Password policy check | ⬜ | RDS Config | | CRITICAL |
| PGL-054 | Auth | Database users follow least privilege | User privilege audit | ⬜ | SQL Audit Script | | CRITICAL |
| PGL-055 | Logging | Database audit logging enabled | Parameter group check | ⬜ | RDS Config | | HIGH |
| PGL-056 | Backup | Automated backups configured (7 days retention) | Backup configuration | ⬜ | RDS Console | | CRITICAL |
| PGL-057 | Patching | Database engine at latest stable version | Version check | ⬜ | RDS Console | | HIGH |
| PGL-058 | SSL | SSL/TLS enforced for database connections | SSL parameter check | ⬜ | RDS Config | | CRITICAL |
| PGL-059 | Encryption | Performance Insights encrypted | PI configuration | ⬜ | RDS Console | | MEDIUM |
| PGL-060 | Maintenance | Maintenance windows configured | Maintenance config | ⬜ | RDS Console | | MEDIUM |

#### 2.1.7 Logging & Monitoring

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| PGL-061 | Logging | CloudTrail enabled in all regions | CloudTrail console | ⬜ | CloudTrail | | CRITICAL |
| PGL-062 | Logging | CloudTrail log file validation enabled | Trail configuration | ⬜ | CloudTrail | | HIGH |
| PGL-063 | Logging | S3 access logging enabled for buckets | Bucket logging check | ⬜ | S3 Console | | HIGH |
| PGL-064 | Logging | Application logs captured (security events) | Log configuration | ⬜ | CloudWatch | | CRITICAL |
| PGL-065 | Logging | Log retention policy configured (1 year min) | Retention settings | ⬜ | CloudWatch | | HIGH |
| PGL-066 | Monitoring | Security alarms configured (GuardDuty) | GuardDuty console | ⬜ | GuardDuty | | CRITICAL |
| PGL-067 | Monitoring | Failed login attempts alerted | CloudWatch alarms | ⬜ | CloudWatch | | HIGH |
| PGL-068 | Monitoring | Unusual API activity alerted | CloudWatch alarms | ⬜ | CloudWatch | | HIGH |
| PGL-069 | Monitoring | Infrastructure metrics monitored | Dashboard review | ⬜ | CloudWatch | | MEDIUM |
| PGL-070 | SIEM | SIEM integration configured | SIEM configuration | ⬜ | SIEM Console | | HIGH |

#### 2.1.8 Backup & Recovery

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| PGL-071 | Backup | Automated daily backups configured | Backup schedule | ⬜ | AWS Backup | | CRITICAL |
| PGL-072 | Backup | Cross-region backup replication enabled | Backup vault check | ⬜ | AWS Backup | | HIGH |
| PGL-073 | Backup | Backup integrity tested monthly | Restore test report | ⬜ | Test Documentation | | CRITICAL |
| PGL-074 | Backup | RTO/RPO objectives documented and tested | DR test report | ⬜ | DR Documentation | | CRITICAL |
| PGL-075 | Backup | Point-in-time recovery enabled | PITR configuration | ⬜ | RDS Console | | HIGH |
| PGL-076 | DR | Disaster recovery plan documented | DR plan document | ⬜ | Confluence | | HIGH |
| PGL-077 | DR | DR site configured and tested | DR test results | ⬜ | Test Reports | | HIGH |
| PGL-078 | DR | Failover procedures documented | Runbook review | ⬜ | Runbooks | | HIGH |
| PGL-079 | Snapshot | EBS snapshots automated | DLM policy check | ⬜ | DLM Console | | MEDIUM |
| PGL-080 | Snapshot | Snapshot encryption verified | Snapshot attributes | ⬜ | EC2 Console | | HIGH |

#### 2.1.9 Compliance

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| PGL-081 | PCI DSS | Cardholder data environment segmented | Network diagram review | ⬜ | Architecture Docs | | CRITICAL |
| PGL-082 | PCI DSS | Payment processing compliant with PCI DSS 4.0 | QSA assessment | ⬜ | PCI Report | | CRITICAL |
| PGL-083 | GDPR | Privacy notice published and accessible | Website review | ⬜ | Website | | CRITICAL |
| PGL-084 | GDPR | Cookie consent mechanism implemented | Cookie banner test | ⬜ | Website | | CRITICAL |
| PGL-085 | GDPR | Data subject request process documented | DSR procedure | ⬜ | Privacy Docs | | HIGH |
| PGL-086 | BD DPA | Bangladesh DPA compliance verified | Legal review | ⬜ | Legal Assessment | | CRITICAL |
| PGL-087 | Security | Security policy approved and published | Policy document | ⬜ | Policy Portal | | HIGH |
| PGL-088 | Security | Acceptable use policy communicated to users | AUP acknowledgment | ⬜ | HR Records | | MEDIUM |
| PGL-089 | Audit | Compliance evidence collection process defined | Evidence procedure | ⬜ | Compliance Docs | | HIGH |
| PGL-090 | Training | Security awareness training completed | Training records | ⬜ | LMS System | | HIGH |

---

## 3. Infrastructure Security Audit

### 3.1 AWS Account Security

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| INF-001 | Account | AWS root account MFA enabled | IAM credential report | ⬜ | IAM Console | | CRITICAL |
| INF-002 | Account | Root account access keys deleted | IAM credential report | ⬜ | IAM Console | | CRITICAL |
| INF-003 | Account | Root account usage monitored and alerted | CloudWatch alarms | ⬜ | CloudWatch | | HIGH |
| INF-004 | Account | AWS Organizations with SCPs implemented | Organizations console | ⬜ | AWS Orgs | | HIGH |
| INF-005 | Account | Billing alerts configured | Billing console | ⬜ | Billing Console | | MEDIUM |
| INF-006 | Account | Security contact email configured | Account settings | ⬜ | AWS Account | | HIGH |
| INF-007 | Account | AWS Config enabled with rules | Config console | ⬜ | AWS Config | | HIGH |
| INF-008 | Account | Config recorder includes global resources | Config settings | ⬜ | AWS Config | | HIGH |
| INF-009 | Account | Service Control Policies reviewed quarterly | Policy review log | ⬜ | Compliance Docs | | MEDIUM |
| INF-010 | Account | Unused AWS regions disabled (if applicable) | Regions settings | ⬜ | AWS Account | | LOW |

### 3.2 IAM Security

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| INF-011 | IAM | No IAM users with console access (use SSO) | IAM user audit | ⬜ | IAM Console | | CRITICAL |
| INF-012 | IAM | IAM Access Analyzer enabled | Access Analyzer | ⬜ | IAM Console | | HIGH |
| INF-013 | IAM | Password policy meets security standards | IAM password policy | ⬜ | IAM Console | | CRITICAL |
| INF-014 | IAM | IAM roles used instead of users where possible | IAM architecture | ⬜ | IAM Console | | HIGH |
| INF-015 | IAM | IAM policies follow least privilege | Policy simulator test | ⬜ | IAM Console | | CRITICAL |
| INF-016 | IAM | IAM credentials rotated every 90 days | Credential report | ⬜ | IAM Console | | HIGH |
| INF-017 | IAM | Unused IAM entities removed quarterly | Cleanup report | ⬜ | IAM Audit | | MEDIUM |
| INF-018 | IAM | Cross-account access uses roles (not access keys) | Trust policy review | ⬜ | IAM Console | | CRITICAL |
| INF-019 | IAM | IAM permissions boundaries implemented | Boundary policy check | ⬜ | IAM Console | | MEDIUM |
| INF-020 | IAM | Service-linked roles reviewed | IAM role audit | ⬜ | IAM Console | | MEDIUM |

### 3.3 Compute Security (EC2/ECS/EKS)

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| INF-021 | EC2 | EBS volumes encrypted by default | EC2 console | ⬜ | EC2 Console | | CRITICAL |
| INF-022 | EC2 | EBS snapshots encrypted | Snapshot check | ⬜ | EC2 Console | | HIGH |
| INF-023 | EC2 | EC2 instances use Instance Metadata Service v2 | Metadata options | ⬜ | EC2 Console | | CRITICAL |
| INF-024 | EC2 | Unencrypted AMI snapshots not shared | AMI permissions | ⬜ | EC2 Console | | HIGH |
| INF-025 | EC2 | EC2 instances have detailed monitoring | Monitoring check | ⬜ | CloudWatch | | MEDIUM |
| INF-026 | EC2 | SSM Agent installed and functional | SSM inventory | ⬜ | Systems Manager | | HIGH |
| INF-027 | EC2 | Patch Manager configured for auto-patching | Patch baseline | ⬜ | Systems Manager | | HIGH |
| INF-028 | ECS | ECS task definitions use non-root user | Task definition | ⬜ | ECS Console | | HIGH |
| INF-029 | ECS | ECS secrets stored in Secrets Manager | Task definition | ⬜ | ECS Console | | CRITICAL |
| INF-030 | ECS | ECS Fargate tasks use readonly root filesystem | Task definition | ⬜ | ECS Console | | HIGH |
| INF-031 | EKS | EKS control plane logging enabled | EKS cluster config | ⬜ | EKS Console | | HIGH |
| INF-032 | EKS | EKS private endpoint only (if applicable) | EKS endpoint config | ⬜ | EKS Console | | CRITICAL |
| INF-033 | EKS | EKS node groups use latest AMI version | Node group config | ⬜ | EKS Console | | HIGH |
| INF-034 | EKS | Pod security policies/network policies applied | K8s manifests | ⬜ | EKS Console | | HIGH |
| INF-035 | EKS | EKS secrets encryption enabled | Cluster config | ⬜ | EKS Console | | CRITICAL |
| INF-036 | Lambda | Lambda functions use least privilege execution role | IAM policy review | ⬜ | IAM Console | | CRITICAL |
| INF-037 | Lambda | Lambda environment variables encrypted | Function config | ⬜ | Lambda Console | | HIGH |
| INF-038 | Lambda | Lambda VPC configuration for private resources | Function config | ⬜ | Lambda Console | | HIGH |
| INF-039 | Lambda | Lambda function URLs have auth (if used) | Function URL config | ⬜ | Lambda Console | | HIGH |
| INF-040 | Lambda | Lambda dead letter queues configured | Function config | ⬜ | Lambda Console | | MEDIUM |

### 3.4 Storage Security (S3/EBS/EFS)

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| INF-041 | S3 | S3 buckets block public access | S3 Block Public Access | ⬜ | S3 Console | | CRITICAL |
| INF-042 | S3 | S3 bucket policies deny HTTP requests | Bucket policy | ⬜ | S3 Console | | HIGH |
| INF-043 | S3 | S3 buckets have versioning enabled | Bucket versioning | ⬜ | S3 Console | | HIGH |
| INF-044 | S3 | S3 buckets have lifecycle policies | Lifecycle rules | ⬜ | S3 Console | | MEDIUM |
| INF-045 | S3 | S3 buckets have MFA delete enabled (critical data) | Bucket versioning | ⬜ | S3 Console | | MEDIUM |
| INF-046 | S3 | S3 buckets have access logging enabled | Logging config | ⬜ | S3 Console | | HIGH |
| INF-047 | S3 | S3 buckets have object lock enabled (compliance) | Object lock | ⬜ | S3 Console | | MEDIUM |
| INF-048 | S3 | S3 server-side encryption enforced | Default encryption | ⬜ | S3 Console | | CRITICAL |
| INF-049 | S3 | S3 bucket ACLs disabled | ACL status | ⬜ | S3 Console | | HIGH |
| INF-050 | EFS | EFS encryption at rest enabled | EFS configuration | ⬜ | EFS Console | | CRITICAL |
| INF-051 | EFS | EFS encryption in transit enforced | Mount options | ⬜ | EFS Console | | HIGH |
| INF-052 | EFS | EFS access points with IAM auth | Access point config | ⬜ | EFS Console | | HIGH |
| INF-053 | EFS | EFS lifecycle policies configured | Lifecycle policy | ⬜ | EFS Console | | MEDIUM |
| INF-054 | EBS | EBS default encryption enabled | Region settings | ⬜ | EC2 Console | | CRITICAL |
| INF-055 | EBS | EBS snapshots shared only with authorized accounts | Snapshot permissions | ⬜ | EC2 Console | | HIGH |
| INF-056 | EBS | EBS volumes tagged for cost allocation | Tag compliance | ⬜ | AWS Config | | LOW |
| INF-057 | Storage | AWS Backup plan covers all critical resources | Backup plan | ⬜ | AWS Backup | | CRITICAL |
| INF-058 | Storage | Backup vault access policy restricted | Vault policy | ⬜ | AWS Backup | | HIGH |
| INF-059 | Storage | Cross-region backup copy configured | Backup plan | ⬜ | AWS Backup | | HIGH |
| INF-060 | Storage | Backup restore tested quarterly | Restore test report | ⬜ | Test Docs | | HIGH |

---

## 4. Application Security Audit

### 4.1 Secure Development Lifecycle

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| APP-001 | SDLC | Security requirements in project documentation | Project docs | ⬜ | Confluence | | HIGH |
| APP-002 | SDLC | Threat modeling completed for major features | Threat model doc | ⬜ | Security Docs | | HIGH |
| APP-003 | SDLC | Security code review checklist used | Code review records | ⬜ | GitLab/GitHub | | HIGH |
| APP-004 | SDLC | SAST integrated in CI/CD pipeline | Pipeline config | ⬜ | CI/CD Config | | CRITICAL |
| APP-005 | SDLC | DAST integrated in CI/CD pipeline | Pipeline config | ⬜ | CI/CD Config | | CRITICAL |
| APP-006 | SDLC | SCA (dependency) scan in CI/CD pipeline | Pipeline config | ⬜ | CI/CD Config | | CRITICAL |
| APP-007 | SDLC | Container image scanning in pipeline | Pipeline config | ⬜ | CI/CD Config | | CRITICAL |
| APP-008 | SDLC | Secrets detection in pre-commit hooks | Git hooks | ⬜ | Git Config | | CRITICAL |
| APP-009 | SDLC | Security tests in automated test suite | Test suite | ⬜ | Test Code | | HIGH |
| APP-010 | SDLC | Security champion assigned to each team | Team roster | ⬜ | Org Chart | | MEDIUM |

### 4.2 Input Validation & Injection Prevention

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| APP-011 | Input | All user inputs validated on server side | Code review | ⬜ | SAST Report | | CRITICAL |
| APP-012 | Input | Whitelist validation preferred over blacklist | Code review | ⬜ | SAST Report | | HIGH |
| APP-013 | Input | Input length limits enforced | Code review | ⬜ | SAST Report | | HIGH |
| APP-014 | Input | File upload restrictions (type, size, content) | Upload test | ⬜ | Test Report | | CRITICAL |
| APP-015 | Input | SQL queries use parameterized statements | Code review | ⬜ | SAST Report | | CRITICAL |
| APP-016 | Input | ORM used consistently (no raw SQL) | Code review | ⬜ | SAST Report | | HIGH |
| APP-017 | Input | LDAP injection prevention implemented | Code review | ⬜ | SAST Report | | MEDIUM |
| APP-018 | Input | XPath injection prevention implemented | Code review | ⬜ | SAST Report | | MEDIUM |
| APP-019 | Input | XML External Entity (XXE) prevention | Parser config | ⬜ | SAST Report | | HIGH |
| APP-020 | Input | Deserialization of untrusted data prevented | Code review | ⬜ | SAST Report | | CRITICAL |

### 4.3 Authentication & Session Management

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| APP-021 | Auth | Passwords hashed using bcrypt/Argon2 (not MD5/SHA1) | Code review | ⬜ | SAST Report | | CRITICAL |
| APP-022 | Auth | Salt used for password hashing | Code review | ⬜ | SAST Report | | CRITICAL |
| APP-023 | Auth | Session IDs random and unpredictable | Session test | ⬜ | Security Test | | CRITICAL |
| APP-024 | Auth | Session IDs invalidated on logout | Session test | ⬜ | Security Test | | CRITICAL |
| APP-025 | Auth | Concurrent session limits enforced | Application test | ⬜ | Test Report | | MEDIUM |
| APP-026 | Auth | "Remember me" tokens secure and revocable | Code review | ⬜ | SAST Report | | HIGH |
| APP-027 | Auth | Password reset tokens single-use and time-limited | Code review | ⬜ | SAST Report | | CRITICAL |
| APP-028 | Auth | Sensitive operations require re-authentication | Application test | ⬜ | Test Report | | HIGH |
| APP-029 | Auth | API rate limiting implemented | Configuration | ⬜ | API Gateway | | HIGH |
| APP-030 | Auth | Failed authentication rate limiting implemented | Configuration | ⬜ | Application Config | | HIGH |

### 4.4 Output Encoding & XSS Prevention

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| APP-031 | XSS | Context-aware output encoding implemented | Code review | ⬜ | SAST Report | | CRITICAL |
| APP-032 | XSS | HTML sanitization for rich text inputs | Code review | ⬜ | SAST Report | | CRITICAL |
| APP-033 | XSS | Content Security Policy (CSP) implemented | Header check | ⬜ | Security Headers | | CRITICAL |
| APP-034 | XSS | CSP includes script-src nonce/hash | Header check | ⬜ | Security Headers | | HIGH |
| APP-035 | XSS | X-XSS-Protection header configured | Header check | ⬜ | Security Headers | | HIGH |
| APP-036 | XSS | DOM-based XSS prevention in JavaScript | Code review | ⬜ | SAST Report | | HIGH |
| APP-037 | Output | JSON responses properly encoded | Code review | ⬜ | SAST Report | | HIGH |
| APP-038 | Output | HTTP headers prevent MIME sniffing | Header check | ⬜ | Security Headers | | HIGH |
| APP-039 | Output | X-Frame-Options or CSP frame-ancestors | Header check | ⬜ | Security Headers | | CRITICAL |
| APP-040 | Output | Referrer-Policy header configured | Header check | ⬜ | Security Headers | | MEDIUM |

### 4.5 Access Control

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| APP-041 | Access | Authorization checks on every request | Code review | ⬜ | SAST Report | | CRITICAL |
| APP-042 | Access | Server-side authorization (not client-side) | Code review | ⬜ | SAST Report | | CRITICAL |
| APP-043 | Access | Direct object reference protection implemented | Pentest | ⬜ | Pentest Report | | CRITICAL |
| APP-044 | Access | Role-based access control (RBAC) implemented | Architecture review | ⬜ | Design Docs | | CRITICAL |
| APP-045 | Access | Permission denied responses don't leak data | Test | ⬜ | Test Report | | HIGH |
| APP-046 | Access | Admin functions segregated from user functions | Architecture review | ⬜ | Design Docs | | HIGH |
| APP-047 | Access | API endpoints require authentication (unless public) | API scan | ⬜ | API Test | | CRITICAL |
| APP-048 | Access | CORS properly configured (not wildcard) | Configuration | ⬜ | Application Config | | CRITICAL |
| APP-049 | Access | API versioning for backward compatibility | API spec | ⬜ | API Documentation | | MEDIUM |
| APP-050 | Access | Business logic flaws addressed | Pentest | ⬜ | Pentest Report | | HIGH |

### 4.6 Error Handling & Logging

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| APP-051 | Error | Generic error messages to users (no stack traces) | Application test | ⬜ | Test Report | | CRITICAL |
| APP-052 | Error | Detailed error logging for security events | Log review | ⬜ | Application Logs | | CRITICAL |
| APP-053 | Error | Error logging doesn't contain sensitive data | Log review | ⬜ | Application Logs | | CRITICAL |
| APP-054 | Error | Exception handling covers all code paths | Code review | ⬜ | SAST Report | | HIGH |
| APP-055 | Logging | Security events logged (login, logout, access denied) | Log review | ⬜ | Application Logs | | CRITICAL |
| APP-056 | Logging | Log integrity protected (tamper-evident) | Configuration | ⬜ | CloudWatch/ SIEM | | HIGH |
| APP-057 | Logging | Sensitive data not logged (PII, passwords, tokens) | Log review | ⬜ | Application Logs | | CRITICAL |
| APP-058 | Logging | Logs include correlation IDs for tracing | Log review | ⬜ | Application Logs | | MEDIUM |
| APP-059 | Logging | Log retention meets compliance requirements | Configuration | ⬜ | CloudWatch | | HIGH |
| APP-060 | Logging | Centralized log aggregation configured | Architecture review | ⬜ | Architecture Docs | | HIGH |

### 4.7 Cryptography

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| APP-061 | Crypto | Strong encryption algorithms used (AES-256, RSA-2048+) | Code review | ⬜ | SAST Report | | CRITICAL |
| APP-062 | Crypto | Deprecated algorithms not used (MD5, SHA1, DES, RC4) | Code review | ⬜ | SAST Report | | CRITICAL |
| APP-063 | Crypto | Random number generation uses crypto-secure RNG | Code review | ⬜ | SAST Report | | CRITICAL |
| APP-064 | Crypto | Key management follows secure practices | Architecture review | ⬜ | Design Docs | | CRITICAL |
| APP-065 | Crypto | HSM or KMS used for key storage | Configuration | ⬜ | AWS KMS | | CRITICAL |
| APP-066 | Crypto | Keys rotated according to policy | KMS settings | ⬜ | AWS KMS | | HIGH |
| APP-067 | Crypto | TLS 1.2+ enforced for all connections | SSL scan | ⬜ | SSL Labs | | CRITICAL |
| APP-068 | Crypto | Weak cipher suites disabled | SSL scan | ⬜ | SSL Labs | | CRITICAL |
| APP-069 | Crypto | Certificate pinning considered for mobile | Mobile review | ⬜ | Mobile Test | | MEDIUM |
| APP-070 | Crypto | Secrets not hardcoded in source code | SAST scan | ⬜ | SAST Report | | CRITICAL |

### 4.8 API Security

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| APP-071 | API | API authentication implemented (OAuth 2.0/JWT) | API test | ⬜ | API Test | | CRITICAL |
| APP-072 | API | JWT tokens have appropriate expiration | Token analysis | ⬜ | Code review | | CRITICAL |
| APP-073 | API | JWT signature algorithm secure (RS256/ES256, not none/HS256) | Token analysis | ⬜ | Code review | | CRITICAL |
| APP-074 | API | API request size limits enforced | Configuration | ⬜ | API Gateway | | HIGH |
| APP-075 | API | API pagination limits enforced | API test | ⬜ | API Test | | HIGH |
| APP-076 | API | GraphQL query depth and complexity limited | Configuration | ⬜ | GraphQL Config | | HIGH |
| APP-077 | API | API versioning strategy implemented | API spec | ⬜ | API Documentation | | MEDIUM |
| APP-078 | API | API documentation doesn't expose sensitive endpoints | Doc review | ⬜ | API Docs | | MEDIUM |
| APP-079 | API | API gateway Web Application Firewall enabled | Configuration | ⬜ | AWS WAF | | CRITICAL |
| APP-080 | API | API throttling configured per client | Configuration | ⬜ | API Gateway | | HIGH |

### 4.9 File Handling

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| APP-081 | File | File uploads stored outside web root | Configuration | ⬜ | Application Config | | CRITICAL |
| APP-082 | File | File extensions validated against whitelist | Code review | ⬜ | SAST Report | | CRITICAL |
| APP-083 | File | File content type validated (magic bytes) | Code review | ⬜ | SAST Report | | CRITICAL |
| APP-084 | File | File size limits enforced | Configuration | ⬜ | Application Config | | HIGH |
| APP-085 | File | Antivirus scanning for uploaded files | Integration test | ⬜ | Test Report | | HIGH |
| APP-086 | File | File download authorization checks | Code review | ⬜ | SAST Report | | CRITICAL |
| APP-087 | File | Path traversal prevention implemented | Code review | ⬜ | SAST Report | | CRITICAL |
| APP-088 | File | Zip bomb protection for archive uploads | Code review | ⬜ | SAST Report | | MEDIUM |
| APP-089 | File | File naming sanitization prevents command injection | Code review | ⬜ | SAST Report | | HIGH |
| APP-090 | File | Temporary files securely deleted | Code review | ⬜ | SAST Report | | MEDIUM |

---

## 5. Database Security Audit

### 5.1 PostgreSQL Configuration

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| DB-001 | Config | PostgreSQL version is current stable release | Version check | ⬜ | RDS Console | | CRITICAL |
| DB-002 | Config | SSL connections enforced (ssl = on) | Parameter group | ⬜ | RDS Config | | CRITICAL |
| DB-003 | Config | Strong SSL ciphers configured | Parameter group | ⬜ | RDS Config | | HIGH |
| DB-004 | Config | Password encryption set to scram-sha-256 | Parameter group | ⬜ | RDS Config | | CRITICAL |
| DB-005 | Config | Logging collector enabled | Parameter group | ⬜ | RDS Config | | HIGH |
| DB-006 | Config | Appropriate log level configured (log_min_messages) | Parameter group | ⬜ | RDS Config | | MEDIUM |
| DB-007 | Config | Connection limits configured per user | Parameter group | ⬜ | RDS Config | | MEDIUM |
| DB-008 | Config | Statement timeout configured | Parameter group | ⬜ | RDS Config | | MEDIUM |
| DB-009 | Config | Idle connection timeout configured | Parameter group | ⬜ | RDS Config | | MEDIUM |
| DB-010 | Config | shared_preload_libraries includes pg_stat_statements | Parameter group | ⬜ | RDS Config | | MEDIUM |

### 5.2 Access Control

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| DB-011 | Users | Default postgres superuser password changed | User audit | ⬜ | SQL Query | | CRITICAL |
| DB-012 | Users | Application uses non-superuser account | User audit | ⬜ | SQL Query | | CRITICAL |
| DB-013 | Users | User privileges follow least privilege principle | Privilege audit | ⬜ | SQL Query | | CRITICAL |
| DB-014 | Users | Unused database users removed | User audit | ⬜ | SQL Query | | HIGH |
| DB-015 | Users | User password policies enforced | Policy check | ⬜ | SQL Query | | HIGH |
| DB-016 | Network | Database not accessible from public internet | Connectivity test | ⬜ | Network Test | | CRITICAL |
| DB-017 | Network | Security groups restrict database access | SG review | ⬜ | AWS Console | | CRITICAL |
| DB-018 | Network | Database in private subnet | VPC review | ⬜ | VPC Console | | CRITICAL |
| DB-019 | Auth | pg_hba.conf restricts access appropriately | Config review | ⬜ | Parameter Group | | CRITICAL |
| DB-020 | Auth | Strong authentication methods required | pg_hba.conf | ⬜ | Parameter Group | | CRITICAL |

### 5.3 Data Protection

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| DB-021 | Encryption | RDS storage encryption enabled | RDS config | ⬜ | RDS Console | | CRITICAL |
| DB-022 | Encryption | RDS performance insights encrypted | RDS config | ⬜ | RDS Console | | MEDIUM |
| DB-023 | Encryption | Backup encryption verified | Backup config | ⬜ | RDS Console | | CRITICAL |
| DB-024 | Encryption | Snapshot encryption verified | Snapshot check | ⬜ | RDS Console | | HIGH |
| DB-025 | PII | Sensitive columns encrypted at application level | Code review | ⬜ | Application Code | | HIGH |
| DB-026 | PII | Column-level encryption for PII where applicable | Schema review | ⬜ | Database Schema | | HIGH |
| DB-027 | PII | Data masking for non-production environments | Masking config | ⬜ | DevOps Scripts | | HIGH |
| DB-028 | PII | PII data inventory maintained | Data catalog | ⬜ | Data Catalog | | HIGH |
| DB-029 | Audit | Database audit logging enabled (pgaudit) | Parameter group | ⬜ | RDS Config | | CRITICAL |
| DB-030 | Audit | DDL operations logged | Audit log review | ⬜ | CloudWatch Logs | | HIGH |

### 5.4 Backup & Recovery

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| DB-031 | Backup | Automated backups enabled | RDS config | ⬜ | RDS Console | | CRITICAL |
| DB-032 | Backup | Backup retention period meets RPO (7+ days) | RDS config | ⬜ | RDS Console | | CRITICAL |
| DB-033 | Backup | Cross-region backup copy configured | Backup config | ⬜ | AWS Backup | | HIGH |
| DB-034 | PITR | Point-in-time recovery enabled | RDS config | ⬜ | RDS Console | | HIGH |
| DB-035 | Restore | Restore procedure documented and tested | DR test report | ⬜ | DR Docs | | CRITICAL |
| DB-036 | Restore | RTO for database recovery documented | DR plan | ⬜ | DR Docs | | HIGH |
| DB-037 | Snapshot | Manual snapshots before major changes | Procedure | ⬜ | Runbooks | | MEDIUM |
| DB-038 | Snapshot | Snapshot retention policy enforced | DLM config | ⬜ | DLM Console | | MEDIUM |
| DB-039 | Export | Database exports encrypted | Export config | ⬜ | S3 Bucket | | HIGH |
| DB-040 | Export | Database exports to secure S3 location | Bucket policy | ⬜ | S3 Console | | HIGH |

### 5.5 Maintenance & Patching

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| DB-041 | Patching | Minor version auto-patching enabled | RDS config | ⬜ | RDS Console | | HIGH |
| DB-042 | Patching | Major version upgrades tested in staging | Change record | ⬜ | Change Docs | | HIGH |
| DB-043 | Maintenance | Maintenance windows configured | RDS config | ⬜ | RDS Console | | MEDIUM |
| DB-044 | Maintenance | Maintenance notifications enabled | SNS config | ⬜ | SNS Console | | MEDIUM |
| DB-045 | Monitoring | CloudWatch alarms for database metrics | Alarm config | ⬜ | CloudWatch | | HIGH |
| DB-046 | Monitoring | Slow query logging enabled | Parameter group | ⬜ | RDS Config | | MEDIUM |
| DB-047 | Monitoring | Query performance insights enabled | RDS config | ⬜ | RDS Console | | MEDIUM |
| DB-048 | Monitoring | Disk space alarms configured | CloudWatch | ⬜ | CloudWatch | | CRITICAL |
| DB-049 | Monitoring | Connection limit alarms configured | CloudWatch | ⬜ | CloudWatch | | HIGH |
| DB-050 | Monitoring | Replica lag monitoring (if applicable) | CloudWatch | ⬜ | CloudWatch | | HIGH |



---

## 6. Network Security Audit

### 6.1 VPC Architecture

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| NET-001 | VPC | VPC properly designed with public/private subnets | Architecture review | ⬜ | Architecture Docs | | CRITICAL |
| NET-002 | VPC | VPC flow logs enabled | VPC config | ⬜ | VPC Console | | HIGH |
| NET-003 | VPC | VPC endpoints used for AWS services (where applicable) | Endpoint config | ⬜ | VPC Console | | HIGH |
| NET-004 | VPC | VPC peering connections reviewed and authorized | Peering review | ⬜ | VPC Console | | HIGH |
| NET-005 | VPC | Unused VPCs and resources removed | Resource audit | ⬜ | AWS Config | | MEDIUM |
| NET-006 | Subnet | Subnet CIDR blocks properly sized | IP planning doc | ⬜ | Architecture Docs | | MEDIUM |
| NET-007 | Subnet | Database subnets in isolated/private tier | Architecture review | ⬜ | VPC Console | | CRITICAL |
| NET-008 | Subnet | Public subnets only for load balancers/NAT | Architecture review | ⬜ | VPC Console | | CRITICAL |
| NET-009 | Routing | Route tables restrict traffic appropriately | Route table review | ⬜ | VPC Console | | HIGH |
| NET-010 | Routing | No internet gateway in private subnets | Route table review | ⬜ | VPC Console | | CRITICAL |

### 6.2 Security Groups & NACLs

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| NET-011 | SG | Security groups follow least privilege | SG rule review | ⬜ | EC2 Console | | CRITICAL |
| NET-012 | SG | No 0.0.0.0/0 inbound to sensitive ports | SG audit | ⬜ | AWS Config | | CRITICAL |
| NET-013 | SG | Security groups use specific CIDRs (not 0.0.0.0/0) | SG audit | ⬜ | AWS Config | | CRITICAL |
| NET-014 | SG | Security group descriptions for all rules | SG review | ⬜ | EC2 Console | | LOW |
| NET-015 | SG | Unused security groups removed | Cleanup report | ⬜ | AWS Config | | MEDIUM |
| NET-016 | SG | Default security groups unused or restrictive | SG audit | ⬜ | AWS Config | | HIGH |
| NET-017 | NACL | NACLs provide additional network layer protection | NACL review | ⬜ | VPC Console | | MEDIUM |
| NET-018 | NACL | NACL rules deny traffic by default | NACL review | ⬜ | VPC Console | | MEDIUM |
| NET-019 | NACL | NACL rules reviewed quarterly | Review record | ⬜ | Compliance Docs | | MEDIUM |
| NET-020 | NACL | Ephemeral port ranges allowed appropriately | NACL review | ⬜ | VPC Console | | MEDIUM |

### 6.3 Web Application Firewall (WAF)

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| NET-021 | WAF | AWS WAF enabled on CloudFront/ALB | WAF config | ⬜ | WAF Console | | CRITICAL |
| NET-022 | WAF | AWS Managed Rules baseline implemented | WAF rules | ⬜ | WAF Console | | CRITICAL |
| NET-023 | WAF | SQL injection rule group enabled | WAF rules | ⬜ | WAF Console | | CRITICAL |
| NET-024 | WAF | XSS rule group enabled | WAF rules | ⬜ | WAF Console | | CRITICAL |
| NET-025 | WAF | Rate limiting rules configured | WAF rules | ⬜ | WAF Console | | HIGH |
| NET-026 | WAF | Geo-blocking rules (if applicable) | WAF rules | ⬜ | WAF Console | | MEDIUM |
| NET-027 | WAF | IP reputation rules enabled | WAF rules | ⬜ | WAF Console | | HIGH |
| NET-028 | WAF | Custom rules for application-specific threats | WAF rules | ⬜ | WAF Console | | HIGH |
| NET-029 | WAF | WAF logging enabled to S3/CloudWatch | WAF config | ⬜ | WAF Console | | HIGH |
| NET-030 | WAF | WAF rule effectiveness reviewed monthly | Review report | ⬜ | Security Reports | | MEDIUM |

### 6.4 DDoS Protection

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| NET-031 | DDoS | AWS Shield Standard enabled (automatic) | Shield console | ⬜ | Shield Console | | HIGH |
| NET-032 | DDoS | AWS Shield Advanced considered for critical assets | Shield console | ⬜ | Shield Console | | MEDIUM |
| NET-033 | DDoS | DDoS response plan documented | DR plan | ⬜ | DR Docs | | HIGH |
| NET-034 | DDoS | DDoS testing performed (with AWS approval) | Test report | ⬜ | Security Tests | | MEDIUM |
| NET-035 | DDoS | CDN (CloudFront) used for DDoS mitigation | Architecture review | ⬜ | Architecture Docs | | HIGH |
| NET-036 | DDoS | Origin access identity/restrictions configured | CloudFront config | ⬜ | CloudFront | | HIGH |
| NET-037 | DDoS | Auto Scaling configured for traffic spikes | ASG config | ⬜ | EC2 Console | | HIGH |
| NET-038 | DDoS | Resource quotas/limits configured | Service quotas | ⬜ | AWS Console | | MEDIUM |
| NET-039 | DDoS | DDoS metrics monitored | CloudWatch | ⬜ | CloudWatch | | MEDIUM |
| NET-040 | DDoS | DDoS attack notifications configured | SNS config | ⬜ | SNS Console | | HIGH |

### 6.5 DNS & Certificate Management

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| NET-041 | DNS | Route 53 DNSSEC enabled | Route 53 config | ⬜ | Route 53 | | HIGH |
| NET-042 | DNS | DNS records use appropriate TTL values | DNS audit | ⬜ | Route 53 | | LOW |
| NET-043 | DNS | SPF records configured for email domains | DNS check | ⬜ | DNS Records | | HIGH |
| NET-044 | DNS | DKIM configured for email authentication | DNS check | ⬜ | DNS Records | | HIGH |
| NET-045 | DNS | DMARC policy configured and monitored | DNS check | ⬜ | DNS Records | | HIGH |
| NET-046 | SSL | TLS certificates from ACM (managed renewal) | ACM console | ⬜ | ACM Console | | CRITICAL |
| NET-047 | SSL | TLS 1.2+ enforced (TLS 1.0/1.1 disabled) | SSL scan | ⬜ | SSL Labs | | CRITICAL |
| NET-048 | SSL | Strong cipher suites only | SSL scan | ⬜ | SSL Labs | | CRITICAL |
| NET-049 | SSL | HSTS header configured | Header check | ⬜ | Security Headers | | HIGH |
| NET-050 | SSL | Certificate expiration monitoring | ACM/EventBridge | ⬜ | CloudWatch | | CRITICAL |

### 6.6 Load Balancer Security

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| NET-051 | ALB | Application Load Balancer in use (not Classic) | ELB console | ⬜ | EC2 Console | | MEDIUM |
| NET-052 | ALB | HTTPS listeners only (HTTP redirects to HTTPS) | ALB config | ⬜ | EC2 Console | | CRITICAL |
| NET-053 | ALB | SSL policy uses latest security policy | ALB config | ⬜ | EC2 Console | | CRITICAL |
| NET-054 | ALB | ALB access logs enabled | ALB attributes | ⬜ | S3 Bucket | | HIGH |
| NET-055 | ALB | Connection logs enabled (if applicable) | ALB attributes | ⬜ | S3 Bucket | | MEDIUM |
| NET-056 | ALB | ALB deletion protection enabled | ALB attributes | ⬜ | EC2 Console | | MEDIUM |
| NET-057 | ALB | Security groups restrict ALB access appropriately | SG review | ⬜ | EC2 Console | | CRITICAL |
| NET-058 | NLB | Network Load Balancer security groups configured | NLB config | ⬜ | EC2 Console | | HIGH |
| NET-059 | NLB | NLB TLS termination configured securely | NLB config | ⬜ | EC2 Console | | HIGH |
| NET-060 | NLB | NLB cross-zone load balancing configured | NLB attributes | ⬜ | EC2 Console | | LOW |

---

## 7. Identity & Access Management Audit

### 7.1 User Account Management

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| IAM-001 | Account | User onboarding process includes security review | Process review | ⬜ | HR/IT Procedures | | HIGH |
| IAM-002 | Account | User offboarding removes all access within 24 hours | Offboarding audit | ⬜ | Access Review | | CRITICAL |
| IAM-003 | Account | Regular access reviews conducted quarterly | Review records | ⬜ | Compliance Docs | | CRITICAL |
| IAM-004 | Account | Privileged access reviewed monthly | Review records | ⬜ | Compliance Docs | | CRITICAL |
| IAM-005 | Account | Service accounts documented and reviewed | Service account inventory | ⬜ | IAM Audit | | HIGH |
| IAM-006 | Account | Shared accounts eliminated where possible | Account audit | ⬜ | IAM Console | | HIGH |
| IAM-007 | Account | Generic accounts (admin, test) renamed or removed | Account audit | ⬜ | IAM Console | | HIGH |
| IAM-008 | Account | Emergency access accounts documented and secured | Break-glass procedure | ⬜ | Security Docs | | CRITICAL |
| IAM-009 | Account | User account naming convention followed | Account audit | ⬜ | IAM Console | | LOW |
| IAM-010 | Account | Contact information current for all accounts | Account audit | ⬜ | IAM Console | | MEDIUM |

### 7.2 Authentication Controls

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| IAM-011 | MFA | MFA enforced for all IAM users | Credential report | ⬜ | IAM Console | | CRITICAL |
| IAM-012 | MFA | MFA enforced for root account | Root MFA check | ⬜ | IAM Console | | CRITICAL |
| IAM-013 | MFA | Hardware MFA recommended for privileged users | MFA audit | ⬜ | IAM Console | | HIGH |
| IAM-014 | MFA | MFA for AWS SSO/Identity Center users | SSO config | ⬜ | IAM Identity Center | | CRITICAL |
| IAM-015 | Password | Password policy meets complexity requirements | IAM policy | ⬜ | IAM Console | | CRITICAL |
| IAM-016 | Password | Password expiration policy configured | IAM policy | ⬜ | IAM Console | | MEDIUM |
| IAM-017 | Password | Password reuse prevention configured | IAM policy | ⬜ | IAM Console | | MEDIUM |
| IAM-018 | Auth | AWS SSO/Identity Center used for workforce access | SSO config | ⬜ | IAM Identity Center | | HIGH |
| IAM-019 | Auth | Federation with corporate identity provider | IdP config | ⬜ | IAM Identity Center | | HIGH |
| IAM-020 | Auth | Conditional access policies configured | IAM policy | ⬜ | IAM Identity Center | | HIGH |

### 7.3 Authorization & Permissions

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| IAM-021 | Permissions | IAM policies follow least privilege | Policy review | ⬜ | IAM Console | | CRITICAL |
| IAM-022 | Permissions | IAM Access Analyzer findings reviewed weekly | Access Analyzer | ⬜ | IAM Console | | HIGH |
| IAM-023 | Permissions | External access findings remediated promptly | Finding review | ⬜ | IAM Console | | CRITICAL |
| IAM-024 | Permissions | IAM permissions boundaries used | Policy review | ⬜ | IAM Console | | MEDIUM |
| IAM-025 | Permissions | Managed policies preferred over inline policies | Policy audit | ⬜ | IAM Console | | MEDIUM |
| IAM-026 | Permissions | Resource-level permissions implemented | Policy review | ⬜ | IAM Console | | HIGH |
| IAM-027 | Permissions | Condition keys used for context-based access | Policy review | ⬜ | IAM Console | | MEDIUM |
| IAM-028 | Permissions | IAM roles used for applications (not users) | Role audit | ⬜ | IAM Console | | CRITICAL |
| IAM-029 | Permissions | Cross-account roles use external ID | Trust policy | ⬜ | IAM Console | | CRITICAL |
| IAM-030 | Permissions | Service-linked roles reviewed periodically | Role audit | ⬜ | IAM Console | | MEDIUM |

### 7.4 Secrets Management

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| IAM-031 | Secrets | AWS Secrets Manager used for secrets | Secrets audit | ⬜ | Secrets Manager | | CRITICAL |
| IAM-032 | Secrets | Secrets encrypted with KMS CMK | Secret config | ⬜ | Secrets Manager | | CRITICAL |
| IAM-033 | Secrets | Automatic secret rotation configured | Rotation config | ⬜ | Secrets Manager | | HIGH |
| IAM-034 | Secrets | Secret rotation tested successfully | Rotation test | ⬜ | Test Report | | HIGH |
| IAM-035 | Secrets | No hardcoded secrets in code repositories | Git scan | ⬜ | SAST/Secret Scan | | CRITICAL |
| IAM-036 | Secrets | Secrets not logged in application logs | Log review | ⬜ | CloudWatch Logs | | CRITICAL |
| IAM-037 | Secrets | Database credentials in Secrets Manager | Secrets audit | ⬜ | Secrets Manager | | CRITICAL |
| IAM-038 | Secrets | API keys in Secrets Manager or Parameter Store | Secrets audit | ⬜ | Secrets Manager | | CRITICAL |
| IAM-039 | Secrets | Secret access audited regularly | CloudTrail review | ⬜ | CloudTrail | | HIGH |
| IAM-040 | Secrets | Parameter Store SecureString for configuration | Parameter audit | ⬜ | Systems Manager | | HIGH |

### 7.5 Privileged Access Management

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| IAM-041 | PAM | Privileged accounts inventory maintained | PAM inventory | ⬜ | PAM System | | HIGH |
| IAM-042 | PAM | Just-in-time access for privileged operations | PAM config | ⬜ | PAM System | | HIGH |
| IAM-043 | PAM | Privileged session recording enabled | PAM config | ⬜ | PAM System | | HIGH |
| IAM-044 | PAM | Privileged access approval workflow documented | Procedure doc | ⬜ | Security Docs | | HIGH |
| IAM-045 | PAM | Break-glass procedures tested quarterly | DR test | ⬜ | Test Report | | CRITICAL |
| IAM-046 | PAM | Privileged access reviews conducted monthly | Review records | ⬜ | Compliance Docs | | CRITICAL |
| IAM-047 | PAM | Standing privileged access minimized | Access audit | ⬜ | IAM Console | | HIGH |
| IAM-048 | PAM | Emergency access procedures documented | Procedure doc | ⬜ | Security Docs | | CRITICAL |
| IAM-049 | PAM | Privileged operations logged and monitored | CloudTrail | ⬜ | CloudTrail | | CRITICAL |
| IAM-050 | PAM | Privileged access time-bound where possible | IAM policy | ⬜ | IAM Console | | HIGH |

---

## 8. Data Protection Audit

### 8.1 Data Classification

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| DTP-001 | Classification | Data classification policy documented | Policy doc | ⬜ | Security Policies | | HIGH |
| DTP-002 | Classification | Data inventory maintained and current | Data catalog | ⬜ | Data Catalog | | CRITICAL |
| DTP-003 | Classification | PII identified and catalogued | Data scan | ⬜ | Macie/Scan Report | | CRITICAL |
| DTP-004 | Classification | Financial data classified appropriately | Data catalog | ⬜ | Data Catalog | | CRITICAL |
| DTP-005 | Classification | Health data (if applicable) classified appropriately | Data catalog | ⬜ | Data Catalog | | CRITICAL |
| DTP-006 | Classification | Data classification labels/tags applied | Resource tags | ⬜ | AWS Console | | HIGH |
| DTP-007 | Classification | Classification review process defined | Procedure doc | ⬜ | Security Docs | | MEDIUM |
| DTP-008 | Classification | Data owner assigned for each data type | Data catalog | ⬜ | Data Catalog | | HIGH |
| DTP-009 | Classification | Data retention classification implemented | Policy doc | ⬜ | Security Policies | | HIGH |
| DTP-010 | Classification | Classification training provided to staff | Training records | ⬜ | LMS System | | MEDIUM |

### 8.2 Encryption at Rest

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| DTP-011 | Encryption | AWS KMS used for encryption key management | KMS config | ⬜ | KMS Console | | CRITICAL |
| DTP-012 | Encryption | Customer Managed Keys (CMK) for sensitive data | KMS keys | ⬜ | KMS Console | | CRITICAL |
| DTP-013 | Encryption | Automatic key rotation enabled | Key config | ⬜ | KMS Console | | HIGH |
| DTP-014 | Encryption | Key policies restrict access appropriately | Key policy | ⬜ | KMS Console | | CRITICAL |
| DTP-015 | Encryption | S3 default encryption with bucket policy enforcement | S3 config | ⬜ | S3 Console | | CRITICAL |
| DTP-016 | Encryption | EBS default encryption enabled in all regions | Region settings | ⬜ | EC2 Console | | CRITICAL |
| DTP-017 | Encryption | RDS encryption enabled | RDS config | ⬜ | RDS Console | | CRITICAL |
| DTP-018 | Encryption | DynamoDB encryption with CMK (if used) | DDB config | ⬜ | DynamoDB Console | | HIGH |
| DTP-019 | Encryption | EFS encryption enabled | EFS config | ⬜ | EFS Console | | CRITICAL |
| DTP-020 | Encryption | Snapshot and backup encryption verified | Resource check | ⬜ | AWS Console | | HIGH |

### 8.3 Encryption in Transit

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| DTP-021 | TLS | TLS 1.2+ enforced for all external connections | SSL scan | ⬜ | SSL Labs | | CRITICAL |
| DTP-022 | TLS | Weak cipher suites disabled | SSL scan | ⬜ | SSL Labs | | CRITICAL |
| DTP-023 | TLS | Certificate transparency logging enabled | Cert config | ⬜ | ACM Console | | MEDIUM |
| DTP-024 | TLS | HSTS preload ready (if applicable) | Header check | ⬜ | Security Headers | | MEDIUM |
| DTP-025 | TLS | Internal service-to-service encryption enforced | Config review | ⬜ | Service Config | | HIGH |
| DTP-026 | TLS | Database connections enforce SSL | RDS config | ⬜ | Parameter Group | | CRITICAL |
| DTP-027 | TLS | Redis/Memcached encryption in transit (if used) | Service config | ⬜ | ElastiCache | | HIGH |
| DTP-028 | TLS | API Gateway TLS configuration secure | API GW config | ⬜ | API Gateway | | CRITICAL |
| DTP-029 | TLS | Certificate validation not disabled in code | Code review | ⬜ | SAST Report | | CRITICAL |
| DTP-030 | TLS | mTLS for service-to-service authentication (if applicable) | Config review | ⬜ | Service Config | | HIGH |

### 8.4 Data Loss Prevention

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| DTP-031 | DLP | AWS Macie enabled for S3 data discovery | Macie config | ⬜ | Macie Console | | HIGH |
| DTP-032 | DLP | Macie findings reviewed and remediated | Finding review | ⬜ | Macie Console | | HIGH |
| DTP-033 | DLP | S3 bucket policies prevent unintended data exposure | Policy audit | ⬜ | S3 Console | | CRITICAL |
| DTP-034 | DLP | Public access blocked at account level | S3 settings | ⬜ | S3 Console | | CRITICAL |
| DTP-035 | DLP | VPC endpoints used to prevent data exfiltration | VPC config | ⬜ | VPC Console | | HIGH |
| DTP-036 | DLP | Data transfer monitoring implemented | CloudTrail | ⬜ | CloudTrail | | HIGH |
| DTP-037 | DLP | Large data transfers alerted | CloudWatch alarm | ⬜ | CloudWatch | | MEDIUM |
| DTP-038 | DLP | Email DLP configured (if applicable) | Email config | ⬜ | Email System | | MEDIUM |
| DTP-039 | DLP | USB/removable media controls (endpoints) | Endpoint protection | ⬜ | EDR Console | | MEDIUM |
| DTP-040 | DLP | Data exfiltration response procedure documented | IR plan | ⬜ | IR Documentation | | HIGH |

### 8.5 Backup & Recovery

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| DTP-041 | Backup | AWS Backup plan covers all critical data | Backup plan | ⬜ | AWS Backup | | CRITICAL |
| DTP-042 | Backup | Backup retention meets business requirements | Backup plan | ⬜ | AWS Backup | | CRITICAL |
| DTP-043 | Backup | Cross-region backup copy configured | Backup plan | ⬜ | AWS Backup | | HIGH |
| DTP-044 | Backup | Backup vault lock configured for compliance | Vault config | ⬜ | AWS Backup | | HIGH |
| DTP-045 | Backup | Backup restore tested quarterly | Restore test | ⬜ | Test Report | | CRITICAL |
| DTP-046 | Backup | RTO/RPO documented and validated | DR plan | ⬜ | DR Documentation | | CRITICAL |
| DTP-047 | Backup | Database point-in-time recovery enabled | RDS config | ⬜ | RDS Console | | HIGH |
| DTP-048 | Backup | S3 versioning enabled for critical buckets | S3 config | ⬜ | S3 Console | | HIGH |
| DTP-049 | Backup | S3 object lock for compliance data | S3 config | ⬜ | S3 Console | | MEDIUM |
| DTP-050 | Backup | Backup encryption verified | Backup test | ⬜ | Test Report | | CRITICAL |

### 8.6 Data Retention & Disposal

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| DTP-051 | Retention | Data retention policy documented | Policy doc | ⬜ | Security Policies | | HIGH |
| DTP-052 | Retention | S3 lifecycle policies enforce retention | Lifecycle rules | ⬜ | S3 Console | | HIGH |
| DTP-053 | Retention | Log retention configured appropriately | CloudWatch config | ⬜ | CloudWatch | | HIGH |
| DTP-054 | Retention | Database backup retention configured | RDS config | ⬜ | RDS Console | | HIGH |
| DTP-055 | Disposal | Secure deletion procedures documented | Procedure doc | ⬜ | Security Docs | | HIGH |
| DTP-056 | Disposal | Cryptographic erasure for encrypted data | Key deletion | ⬜ | KMS Console | | HIGH |
| DTP-057 | Disposal | Physical media disposal procedures (if applicable) | Procedure doc | ⬜ | Security Docs | | MEDIUM |
| DTP-058 | Disposal | Disposal attestation records maintained | Compliance records | ⬜ | Compliance Docs | | MEDIUM |
| DTP-059 | Retention | Legal hold procedures documented | Legal procedure | ⬜ | Legal Docs | | MEDIUM |
| DTP-060 | Retention | Retention exceptions documented and approved | Approval records | ⬜ | Compliance Docs | | MEDIUM |

---

## 9. Third-Party Integration Audit

### 9.1 Payment Gateway Security (bKash/Nagad)

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| TPI-001 | Payment | Payment integration uses official SDKs/APIs | Code review | ⬜ | Application Code | | CRITICAL |
| TPI-002 | Payment | API credentials stored in Secrets Manager | Secrets audit | ⬜ | Secrets Manager | | CRITICAL |
| TPI-003 | Payment | Webhook signature verification implemented | Code review | ⬜ | Application Code | | CRITICAL |
| TPI-004 | Payment | Idempotency keys used for transaction safety | Code review | ⬜ | Application Code | | HIGH |
| TPI-005 | Payment | Payment state machine handles all edge cases | Code review | ⬜ | Application Code | | HIGH |
| TPI-006 | Payment | Transaction logging for reconciliation | Log review | ⬜ | Application Logs | | HIGH |
| TPI-007 | Payment | No sensitive card data stored (PCI compliance) | Data scan | ⬜ | Macie/Scan | | CRITICAL |
| TPI-008 | Payment | Payment pages use HTTPS only | SSL scan | ⬜ | SSL Labs | | CRITICAL |
| TPI-009 | Payment | Payment gateway SLA and support contacts documented | Vendor doc | ⬜ | Vendor Files | | MEDIUM |
| TPI-010 | Payment | Payment failure handling tested | Test results | ⬜ | Test Reports | | HIGH |

### 9.2 SMS Gateway Security

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| TPI-011 | SMS | SMS provider API credentials secured | Secrets audit | ⬜ | Secrets Manager | | CRITICAL |
| TPI-012 | SMS | OTP rate limiting implemented | API config | ⬜ | API Gateway | | CRITICAL |
| TPI-013 | SMS | SMS template injection prevention | Code review | ⬜ | Application Code | | HIGH |
| TPI-014 | SMS | SMS delivery tracking and retry logic | Code review | ⬜ | Application Code | | MEDIUM |
| TPI-015 | SMS | SMS logs don't contain full OTP values | Log review | ⬜ | Application Logs | | CRITICAL |
| TPI-016 | SMS | International SMS restrictions (if applicable) | Provider config | ⬜ | SMS Provider | | MEDIUM |
| TPI-017 | SMS | SMS provider SLA documented | Vendor doc | ⬜ | Vendor Files | | LOW |
| TPI-018 | SMS | Sender ID registered appropriately | Provider config | ⬜ | SMS Provider | | MEDIUM |
| TPI-019 | SMS | SMS cost monitoring and alerting | CloudWatch | ⬜ | CloudWatch | | LOW |
| TPI-020 | SMS | Fallback SMS provider configured | Architecture | ⬜ | Architecture Docs | | MEDIUM |

### 9.3 Email Service Security

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| TPI-021 | Email | Email service (SES) configured with DKIM | SES config | ⬜ | SES Console | | HIGH |
| TPI-022 | Email | SPF record includes email service | DNS check | ⬜ | DNS Records | | HIGH |
| TPI-023 | Email | DMARC policy configured | DNS check | ⬜ | DNS Records | | HIGH |
| TPI-024 | Email | Dedicated IP for transactional email (if volume warrants) | SES config | ⬜ | SES Console | | MEDIUM |
| TPI-025 | Email | Email templates injection-safe | Code review | ⬜ | Application Code | | HIGH |
| TPI-026 | Email | Unsubscribe mechanism for marketing emails | Feature test | ⬜ | Test Results | | HIGH |
| TPI-027 | Email | Bounce/complaint handling automated | SES config | ⬜ | SES Console | | MEDIUM |
| TPI-028 | Email | Sending limits monitored | CloudWatch | ⬜ | CloudWatch | | LOW |
| TPI-029 | Email | Email credentials secured | Secrets audit | ⬜ | Secrets Manager | | CRITICAL |
| TPI-030 | Email | Sensitive data in emails encrypted or link-based | Code review | ⬜ | Application Code | | HIGH |

### 9.4 Map/Location Services

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| TPI-031 | Maps | Map API keys restricted by HTTP referrer | API console | ⬜ | Map Provider | | CRITICAL |
| TPI-032 | Maps | Map API keys restricted by IP (if applicable) | API console | ⬜ | Map Provider | | HIGH |
| TPI-033 | Maps | Map API keys stored securely | Secrets audit | ⬜ | Secrets Manager | | CRITICAL |
| TPI-034 | Maps | Geocoding results cached appropriately | Code review | ⬜ | Application Code | | LOW |
| TPI-035 | Maps | Location data privacy compliance | Privacy review | ⬜ | Privacy Docs | | HIGH |
| TPI-036 | Maps | Map usage monitored for anomalies | CloudWatch | ⬜ | CloudWatch | | LOW |
| TPI-037 | Maps | Map API rate limits handled gracefully | Code review | ⬜ | Application Code | | MEDIUM |
| TPI-038 | Maps | Fallback map provider considered | Architecture | ⬜ | Architecture Docs | | LOW |
| TPI-039 | Maps | User location consent obtained | Privacy review | ⬜ | Privacy Docs | | CRITICAL |
| TPI-040 | Maps | Precise location storage minimized | Data audit | ⬜ | Data Catalog | | HIGH |

### 9.5 CDN & External Services

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| TPI-041 | CDN | CloudFront origin access identity configured | CloudFront | ⬜ | CloudFront | | HIGH |
| TPI-042 | CDN | CloudFront signed URLs/cookies for private content | CloudFront | ⬜ | CloudFront | | HIGH |
| TPI-043 | CDN | Geo-restrictions configured (if applicable) | CloudFront | ⬜ | CloudFront | | MEDIUM |
| TPI-044 | CDN | CloudFront logging enabled | CloudFront | ⬜ | S3 Bucket | | HIGH |
| TPI-045 | CDN | Alternate domain names use valid certificates | ACM | ⬜ | ACM Console | | CRITICAL |
| TPI-046 | CDN | Field-level encryption for sensitive data | CloudFront | ⬜ | CloudFront | | HIGH |
| TPI-047 | CDN | Lambda@Edge functions security reviewed | Code review | ⬜ | Lambda Console | | HIGH |
| TPI-048 | CDN | DDoS protection integrated with CDN | Shield | ⬜ | Shield Console | | HIGH |
| TPI-049 | CDN | Cache behavior optimized for security | CloudFront | ⬜ | CloudFront | | MEDIUM |
| TPI-050 | CDN | Origin failover configured | CloudFront | ⬜ | CloudFront | | MEDIUM |

---

## 10. Mobile App Security Audit

### 10.1 iOS Application Security

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| MOB-001 | iOS | App uses iOS Keychain for sensitive data | Code review | ⬜ | iOS Source Code | | CRITICAL |
| MOB-002 | iOS | App doesn't disable ATS (App Transport Security) | Info.plist | ⬜ | iOS Source Code | | CRITICAL |
| MOB-003 | iOS | Jailbreak detection implemented (if required) | Code review | ⬜ | iOS Source Code | | MEDIUM |
| MOB-004 | iOS | Certificate pinning implemented | Code review | ⬜ | iOS Source Code | | HIGH |
| MOB-005 | iOS | Biometric authentication uses Secure Enclave | Code review | ⬜ | iOS Source Code | | HIGH |
| MOB-006 | iOS | Sensitive data excluded from backups | Code review | ⬜ | iOS Source Code | | HIGH |
| MOB-007 | iOS | Pasteboard disabled for sensitive fields | Code review | ⬜ | iOS Source Code | | MEDIUM |
| MOB-008 | iOS | Screenshots disabled for sensitive screens | Code review | ⬜ | iOS Source Code | | MEDIUM |
| MOB-009 | iOS | App uses WKWebView (not UIWebView) | Code review | ⬜ | iOS Source Code | | HIGH |
| MOB-010 | iOS | WebView JavaScript injection prevention | Code review | ⬜ | iOS Source Code | | HIGH |

### 10.2 Android Application Security

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| MOB-011 | Android | Android Keystore used for sensitive data | Code review | ⬜ | Android Source | | CRITICAL |
| MOB-012 | Android | Network security config enforces certificate pinning | network_security_config.xml | ⬜ | Android Source | | HIGH |
| MOB-013 | Android | Root detection implemented (if required) | Code review | ⬜ | Android Source | | MEDIUM |
| MOB-014 | Android | minSdkVersion appropriate for security | build.gradle | ⬜ | Android Source | | HIGH |
| MOB-015 | Android | ProGuard/R8 obfuscation enabled | build.gradle | ⬜ | Android Source | | MEDIUM |
| MOB-016 | Android | Debug mode disabled in release builds | build.gradle | ⬜ | Android Source | | CRITICAL |
| MOB-017 | Android | AllowBackup disabled for sensitive apps | AndroidManifest.xml | ⬜ | Android Source | | HIGH |
| MOB-018 | Android | Exported activities/services reviewed | AndroidManifest.xml | ⬜ | Android Source | | HIGH |
| MOB-019 | Android | Deep links validated | Code review | ⬜ | Android Source | | HIGH |
| MOB-020 | Android | Intent validation for inter-app communication | Code review | ⬜ | Android Source | | HIGH |

### 10.3 Mobile API Security

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| MOB-021 | API | Mobile API uses OAuth 2.0 with PKCE | Code review | ⬜ | Mobile Source | | CRITICAL |
| MOB-022 | API | Refresh token rotation implemented | Code review | ⬜ | Mobile Source | | CRITICAL |
| MOB-023 | API | Short-lived access tokens used | Code review | ⬜ | Mobile Source | | CRITICAL |
| MOB-024 | API | Token storage in Keychain/Keystore | Code review | ⬜ | Mobile Source | | CRITICAL |
| MOB-025 | API | API request signing (if applicable) | Code review | ⬜ | Mobile Source | | MEDIUM |
| MOB-026 | API | Certificate validation not bypassed | Code review | ⬜ | Mobile Source | | CRITICAL |
| MOB-027 | API | Device fingerprinting for fraud detection | Code review | ⬜ | Mobile Source | | MEDIUM |
| MOB-028 | API | API versioning handled gracefully | Code review | ⬜ | Mobile Source | | MEDIUM |
| MOB-029 | API | Offline data encryption | Code review | ⬜ | Mobile Source | | HIGH |
| MOB-030 | API | Secure local database (SQLCipher if using SQLite) | Code review | ⬜ | Mobile Source | | HIGH |

### 10.4 Mobile Data Protection

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| MOB-031 | Data | Sensitive data not logged to system logs | Code review | ⬜ | Mobile Source | | CRITICAL |
| MOB-032 | Data | Sensitive data cleared from memory after use | Code review | ⬜ | Mobile Source | | HIGH |
| MOB-033 | Data | Autocomplete disabled for sensitive fields | Code review | ⬜ | Mobile Source | | HIGH |
| MOB-034 | Data | Third-party keyboard disabled for sensitive input | Code review | ⬜ | Mobile Source | | MEDIUM |
| MOB-035 | Data | Application logs don't contain PII | Code review | ⬜ | Mobile Source | | CRITICAL |
| MOB-036 | Data | Crash logs sanitized before sending | Code review | ⬜ | Mobile Source | | HIGH |
| MOB-037 | Data | Analytics data anonymized | Code review | ⬜ | Mobile Source | | MEDIUM |
| MOB-038 | Data | Push notification content doesn't expose sensitive data | Code review | ⬜ | Mobile Source | | HIGH |
| MOB-039 | Data | Inter-process communication secured | Code review | ⬜ | Mobile Source | | MEDIUM |
| MOB-040 | Data | Clipboard cleared after sensitive operations | Code review | ⬜ | Mobile Source | | MEDIUM |

### 10.5 Mobile Build & Distribution

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| MOB-041 | Build | Code signing certificates secured | Certificate mgmt | ⬜ | DevOps System | | CRITICAL |
| MOB-042 | Build | Build pipeline includes security scans | CI/CD config | ⬜ | CI/CD System | | CRITICAL |
| MOB-043 | Build | Mobile SAST scan in CI/CD | CI/CD config | ⬜ | CI/CD System | | CRITICAL |
| MOB-044 | Build | Mobile dependency vulnerability scan | CI/CD config | ⬜ | CI/CD System | | CRITICAL |
| MOB-045 | Build | Beta/development builds clearly identifiable | Build config | ⬜ | CI/CD System | | MEDIUM |
| MOB-046 | Dist | App Store/Play Store developer accounts secured | Account config | ⬜ | Store Console | | CRITICAL |
| MOB-047 | Dist | App signing keys backed up securely | Backup verification | ⬜ | DevOps System | | CRITICAL |
| MOB-048 | Dist | App update mechanism secure | Architecture | ⬜ | Architecture Docs | | HIGH |
| MOB-049 | Dist | In-app update notifications verified | Feature test | ⬜ | Test Results | | MEDIUM |
| MOB-050 | Dist | App attestation (DeviceCheck/App Attest) configured | Code review | ⬜ | Mobile Source | | HIGH |

---

## 11. IoT Security Audit

### 11.1 MQTT Broker Security

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| IOT-001 | MQTT | MQTT broker requires authentication | Broker config | ⬜ | IoT Core/Config | | CRITICAL |
| IOT-002 | MQTT | TLS 1.2+ enforced for all connections | Broker config | ⬜ | IoT Core/Config | | CRITICAL |
| IOT-003 | MQTT | Client certificates for device authentication | IoT Policy | ⬜ | IoT Core | | CRITICAL |
| IOT-004 | MQTT | Topic-level authorization configured | IoT Policy | ⬜ | IoT Core | | CRITICAL |
| IOT-005 | MQTT | Wildcard subscriptions restricted | IoT Policy | ⬜ | IoT Core | | HIGH |
| IOT-006 | MQTT | MQTT over WebSocket secured | Configuration | ⬜ | IoT Core | | HIGH |
| IOT-007 | MQTT | Retained message policy defined | Broker config | ⬜ | IoT Core/Config | | MEDIUM |
| IOT-008 | MQTT | Last Will and Testament secured | Policy review | ⬜ | IoT Core | | MEDIUM |
| IOT-009 | MQTT | MQTT keep-alive configured appropriately | Device config | ⬜ | Device Firmware | | MEDIUM |
| IOT-010 | MQTT | MQTT message size limits enforced | Broker config | ⬜ | IoT Core/Config | | MEDIUM |

### 11.2 Device Security

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| IOT-011 | Device | Unique device certificates per device | Certificate inventory | ⬜ | IoT Core | | CRITICAL |
| IOT-012 | Device | Device certificate rotation process defined | Procedure doc | ⬜ | IoT Docs | | HIGH |
| IOT-013 | Device | Device firmware signed and verified | Firmware review | ⬜ | DevOps System | | CRITICAL |
| IOT-014 | Device | Secure boot enabled (if hardware supports) | Hardware spec | ⬜ | Device Specs | | HIGH |
| IOT-015 | Device | Device attestation on connection | IoT Policy | ⬜ | IoT Core | | HIGH |
| IOT-016 | Device | Physical tamper detection (if applicable) | Hardware spec | ⬜ | Device Specs | | MEDIUM |
| IOT-017 | Device | Device credentials secured in hardware (TPM/HSM) | Hardware spec | ⬜ | Device Specs | | HIGH |
| IOT-018 | Device | Device provisioning process secured | Provisioning doc | ⬜ | IoT Docs | | CRITICAL |
| IOT-019 | Device | Just-in-time provisioning (JITP) or JITR configured | IoT Config | ⬜ | IoT Core | | HIGH |
| IOT-020 | Device | Device decommissioning process defined | Procedure doc | ⬜ | IoT Docs | | HIGH |

### 11.3 IoT Data Security

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| IOT-021 | Data | IoT data encrypted in transit | Configuration | ⬜ | IoT Core | | CRITICAL |
| IOT-022 | Data | IoT data encrypted at rest (IoT Analytics) | Service config | ⬜ | IoT Analytics | | HIGH |
| IOT-023 | Data | IoT rules engine doesn't expose sensitive data | Rule review | ⬜ | IoT Core | | HIGH |
| IOT-024 | Data | Device data validation on ingestion | Code review | ⬜ | IoT Rules | | HIGH |
| IOT-025 | Data | IoT device shadow secured | Shadow config | ⬜ | IoT Core | | HIGH |
| IOT-026 | Data | Shadow access controlled per device | IoT Policy | ⬜ | IoT Core | | HIGH |
| IOT-027 | Data | IoT data retention policy configured | Service config | ⬜ | IoT Services | | MEDIUM |
| IOT-028 | Data | IoT data classification implemented | Data catalog | ⬜ | Data Catalog | | MEDIUM |
| IOT-029 | Data | IoT data anonymization for analytics | Pipeline config | ⬜ | IoT Analytics | | MEDIUM |
| IOT-030 | Data | IoT data pipeline integrity verified | Pipeline test | ⬜ | Test Results | | HIGH |

### 11.4 IoT Fleet Management

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| IOT-031 | Fleet | Device fleet indexing enabled | IoT Config | ⬜ | IoT Core | | MEDIUM |
| IOT-032 | Fleet | Device fleet monitoring dashboard | IoT Console | ⬜ | IoT Core | | MEDIUM |
| IOT-033 | Fleet | Device connectivity alerts configured | CloudWatch | ⬜ | CloudWatch | | HIGH |
| IOT-034 | Fleet | Device metric anomalies detected | IoT Analytics | ⬜ | IoT Analytics | | MEDIUM |
| IOT-035 | Fleet | Over-the-air (OTA) updates secured | OTA config | ⬜ | IoT Core | | CRITICAL |
| IOT-036 | Fleet | OTA update signing verification | OTA config | ⬜ | IoT Core | | CRITICAL |
| IOT-037 | Fleet | OTA rollback capability tested | Test results | ⬜ | Test Reports | | HIGH |
| IOT-038 | Fleet | Device jobs securely managed | Jobs config | ⬜ | IoT Core | | HIGH |
| IOT-039 | Fleet | Device group policies implemented | IoT Policy | ⬜ | IoT Core | | MEDIUM |
| IOT-040 | Fleet | Device thing groups for access control | IoT Config | ⬜ | IoT Core | | MEDIUM |

### 11.5 Smart Farm Specific

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| IOT-041 | Sensors | Temperature sensor data integrity verified | Data validation | ⬜ | Application Code | | HIGH |
| IOT-042 | Sensors | Humidity sensor data validated | Data validation | ⬜ | Application Code | | HIGH |
| IOT-043 | Sensors | Sensor data anomaly detection implemented | ML/Analytics | ⬜ | IoT Analytics | | MEDIUM |
| IOT-044 | Sensors | Sensor calibration tracking | Device registry | ⬜ | IoT Core | | MEDIUM |
| IOT-045 | Control | Automated control commands authenticated | Code review | ⬜ | Application Code | | CRITICAL |
| IOT-046 | Control | Manual override capabilities secured | Feature review | ⬜ | Application Code | | HIGH |
| IOT-047 | Control | Safety limits enforced on automated controls | Code review | ⬜ | Application Code | | CRITICAL |
| IOT-048 | Control | Emergency shutdown procedures tested | DR test | ⬜ | Test Reports | | CRITICAL |
| IOT-049 | Alerts | Critical sensor alert thresholds configured | Alert config | ⬜ | CloudWatch | | HIGH |
| IOT-050 | Alerts | Alert delivery redundancy implemented | Architecture | ⬜ | Architecture Docs | | HIGH |

---

## 12. Compliance Audit

### 12.1 PCI DSS Compliance

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| PCI-001 | Scope | Cardholder data environment (CDE) defined | Network diagram | ⬜ | Architecture Docs | | CRITICAL |
| PCI-002 | Scope | Network segmentation validated | Segmentation test | ⬜ | Pentest Report | | CRITICAL |
| PCI-003 | Firewall | Firewall configuration standards documented | Policy doc | ⬜ | Security Policies | | CRITICAL |
| PCI-004 | Firewall | Default deny-all rule implemented | Config review | ⬜ | Security Groups | | CRITICAL |
| PCI-005 | Encryption | Strong cryptography for data in transit | SSL scan | ⬜ | SSL Labs | | CRITICAL |
| PCI-006 | Encryption | Strong cryptography for data at rest | Encryption audit | ⬜ | AWS Console | | CRITICAL |
| PCI-007 | Vulnerability | Vulnerability management program | VM program | ⬜ | Security Program | | CRITICAL |
| PCI-008 | Vulnerability | Critical patches applied within 30 days | Patch report | ⬜ | Systems Manager | | CRITICAL |
| PCI-009 | Access | Unique user IDs for each person | IAM audit | ⬜ | IAM Console | | CRITICAL |
| PCI-010 | Access | Access restrictions based on need-to-know | IAM policy | ⬜ | IAM Console | | CRITICAL |
| PCI-011 | Monitoring | Audit trails for system components | CloudTrail | ⬜ | CloudTrail | | CRITICAL |
| PCI-012 | Monitoring | Audit trail protection | S3 bucket policy | ⬜ | S3 Console | | CRITICAL |
| PCI-013 | Testing | Security testing program | Pentest report | ⬜ | Pentest Report | | CRITICAL |
| PCI-014 | Testing | Intrusion detection/prevention systems | GuardDuty | ⬜ | GuardDuty | | CRITICAL |
| PCI-015 | Policy | Information security policy maintained | Policy doc | ⬜ | Security Policies | | CRITICAL |

### 12.2 GDPR Compliance

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| GDPR-001 | Notice | Privacy notice available and comprehensive | Website review | ⬜ | Website | | CRITICAL |
| GDPR-002 | Notice | Cookie consent mechanism implemented | Website test | ⬜ | Website | | CRITICAL |
| GDPR-003 | Notice | Data processing purposes clearly stated | Privacy notice | ⬜ | Privacy Docs | | CRITICAL |
| GDPR-004 | Lawfulness | Legal basis for processing documented | Privacy register | ⬜ | Privacy Docs | | CRITICAL |
| GDPR-005 | Rights | Data subject access request (DSAR) process | Procedure doc | ⬜ | Privacy Docs | | CRITICAL |
| GDPR-006 | Rights | Data erasure (right to be forgotten) process | Procedure doc | ⬜ | Privacy Docs | | CRITICAL |
| GDPR-007 | Rights | Data portability mechanism | Feature review | ⬜ | Application Code | | HIGH |
| GDPR-008 | Rights | Consent withdrawal process | Procedure doc | ⬜ | Privacy Docs | | HIGH |
| GDPR-009 | Protection | Data protection by design and default | Architecture | ⬜ | Architecture Docs | | CRITICAL |
| GDPR-010 | Protection | Privacy impact assessments (DPIA) | DPIA records | ⬜ | Privacy Docs | | HIGH |
| GDPR-011 | Breach | Data breach notification procedure | IR plan | ⬜ | IR Documentation | | CRITICAL |
| GDPR-012 | Breach | 72-hour notification capability | DR test | ⬜ | Test Reports | | CRITICAL |
| GDPR-013 | DPO | DPO contact information published | Privacy notice | ⬜ | Website | | HIGH |
| GDPR-014 | Records | Processing records maintained | Privacy register | ⬜ | Privacy Docs | | HIGH |
| GDPR-015 | Transfer | International transfer safeguards | Legal review | ⬜ | Legal Docs | | HIGH |

### 12.3 Bangladesh DPA Compliance

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| BDPA-001 | Notice | Bangla language privacy notice available | Website review | ⬜ | Website | | HIGH |
| BDPA-002 | Consent | Explicit consent for sensitive data | Consent flow | ⬜ | Application | | CRITICAL |
| BDPA-003 | Consent | Consent records maintained | Database audit | ⬜ | Application DB | | CRITICAL |
| BDPA-004 | Purpose | Data use limited to stated purposes | Privacy review | ⬜ | Privacy Docs | | HIGH |
| BDPA-005 | Retention | Data retention limits per BDPA requirements | Policy review | ⬜ | Privacy Docs | | HIGH |
| BDPA-006 | Security | Reasonable security safeguards implemented | Security audit | ⬜ | This Checklist | | CRITICAL |
| BDPA-007 | Breach | Breach notification to authorities | IR procedure | ⬜ | IR Documentation | | CRITICAL |
| BDPA-008 | Breach | Breach notification to affected individuals | IR procedure | ⬜ | IR Documentation | | CRITICAL |
| BDPA-009 | Rights | Data subject rights mechanism | Feature review | ⬜ | Application | | HIGH |
| BDPA-010 | Cross-border | Cross-border data transfer restrictions | Legal review | ⬜ | Legal Docs | | HIGH |

### 12.4 ISO 27001 Alignment

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| ISO-001 | Policy | Information security policies documented | Policy review | ⬜ | Security Policies | | CRITICAL |
| ISO-002 | Organization | Information security roles defined | Org chart | ⬜ | HR Docs | | HIGH |
| ISO-003 | Asset | Asset inventory maintained | Asset register | ⬜ | CMDB | | HIGH |
| ISO-004 | Asset | Asset classification implemented | Asset tags | ⬜ | CMDB | | HIGH |
| ISO-005 | Access | Access control policy enforced | Policy review | ⬜ | Security Policies | | CRITICAL |
| ISO-006 | Crypto | Cryptographic policy implemented | Policy review | ⬜ | Security Policies | | CRITICAL |
| ISO-007 | Physical | Physical security controls (if applicable) | Site audit | ⬜ | Facilities | | MEDIUM |
| ISO-008 | Ops | Change management process | Change records | ⬜ | ITSM System | | HIGH |
| ISO-009 | Ops | Capacity planning conducted | Capacity report | ⬜ | Operations | | MEDIUM |
| ISO-010 | Comms | Network security management | Network review | ⬜ | This Checklist | | CRITICAL |
| ISO-011 | Dev | Secure development policy | Policy review | ⬜ | Security Policies | | CRITICAL |
| ISO-012 | Supplier | Supplier security agreements | Contract review | ⬜ | Legal Docs | | HIGH |
| ISO-013 | Incident | Incident management procedure | IR plan | ⬜ | IR Documentation | | CRITICAL |
| ISO-014 | Business | Business continuity planning | BCP document | ⬜ | BCP Docs | | HIGH |
| ISO-015 | Compliance | Compliance monitoring | Audit records | ⬜ | Compliance Docs | | CRITICAL |

---

## 13. Operational Security Audit

### 13.1 Security Monitoring

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| OPS-001 | Monitor | Amazon GuardDuty enabled in all regions | GuardDuty | ⬜ | GuardDuty | | CRITICAL |
| OPS-002 | Monitor | GuardDuty findings reviewed daily | Finding review | ⬜ | GuardDuty | | CRITICAL |
| OPS-003 | Monitor | Critical/High GuardDuty findings alerted | EventBridge | ⬜ | EventBridge | | CRITICAL |
| OPS-004 | Monitor | AWS Security Hub enabled | Security Hub | ⬜ | Security Hub | | HIGH |
| OPS-005 | Monitor | Security Hub compliance standards enabled | Security Hub | ⬜ | Security Hub | | HIGH |
| OPS-006 | Monitor | Amazon Detective enabled for investigation | Detective | ⬜ | Detective | | MEDIUM |
| OPS-007 | Monitor | AWS Config conformance packs implemented | Config | ⬜ | AWS Config | | HIGH |
| OPS-008 | Monitor | Config rule violations alerted | EventBridge | ⬜ | EventBridge | | HIGH |
| OPS-009 | Monitor | VPC Flow Logs analyzed for anomalies | Flow log analysis | ⬜ | Athena/CloudWatch | | HIGH |
| OPS-010 | Monitor | DNS query logging enabled (if Route 53 Resolver) | Resolver logs | ⬜ | Route 53 | | MEDIUM |

### 13.2 Log Management

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| OPS-011 | Logging | CloudTrail enabled in all regions | CloudTrail | ⬜ | CloudTrail | | CRITICAL |
| OPS-012 | Logging | CloudTrail organization trail enabled | CloudTrail | ⬜ | CloudTrail | | CRITICAL |
| OPS-013 | Logging | CloudTrail log file validation enabled | Trail config | ⬜ | CloudTrail | | HIGH |
| OPS-014 | Logging | CloudTrail logs encrypted with KMS | S3 bucket | ⬜ | S3 Console | | HIGH |
| OPS-015 | Logging | CloudTrail logs in separate account (security account) | S3 bucket | ⬜ | S3 Console | | HIGH |
| OPS-016 | Logging | Log retention meets compliance requirements | Lifecycle | ⬜ | S3 Console | | HIGH |
| OPS-017 | Logging | Log centralization to SIEM | SIEM config | ⬜ | SIEM Console | | HIGH |
| OPS-018 | Logging | Application security logs centralized | Log config | ⬜ | CloudWatch | | HIGH |
| OPS-019 | Logging | Log integrity monitoring | Log validation | ⬜ | Security Tools | | MEDIUM |
| OPS-020 | Logging | Log backup to immutable storage | S3 config | ⬜ | S3 Console | | HIGH |

### 13.3 Incident Response

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| OPS-021 | IR | Incident response plan documented | IR plan | ⬜ | IR Documentation | | CRITICAL |
| OPS-022 | IR | IR team roles and contacts defined | IR plan | ⬜ | IR Documentation | | CRITICAL |
| OPS-023 | IR | Incident classification criteria defined | IR plan | ⬜ | IR Documentation | | CRITICAL |
| OPS-024 | IR | Incident communication plan documented | IR plan | ⬜ | IR Documentation | | CRITICAL |
| OPS-025 | IR | IR playbooks for common scenarios | Playbooks | ⬜ | IR Documentation | | HIGH |
| OPS-026 | IR | IR tools and access prepared | IR toolkit | ⬜ | IR Documentation | | HIGH |
| OPS-027 | IR | Tabletop exercises conducted quarterly | Exercise records | ⬜ | IR Documentation | | CRITICAL |
| OPS-028 | IR | Forensic investigation capability | Capability review | ⬜ | IR Documentation | | HIGH |
| OPS-029 | IR | Post-incident review process | Procedure | ⬜ | IR Documentation | | HIGH |
| OPS-030 | IR | Law enforcement contact procedures | IR plan | ⬜ | IR Documentation | | MEDIUM |

### 13.4 Vulnerability Management

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| OPS-031 | VM | Vulnerability scanning tools deployed | Tools inventory | ⬜ | Security Tools | | CRITICAL |
| OPS-032 | VM | Network vulnerability scanning monthly | Scan schedule | ⬜ | Security Tools | | CRITICAL |
| OPS-033 | VM | Web application scanning quarterly | Scan schedule | ⬜ | Security Tools | | CRITICAL |
| OPS-034 | VM | Container image scanning in CI/CD | CI/CD config | ⬜ | CI/CD System | | CRITICAL |
| OPS-035 | VM | Infrastructure as Code scanning | CI/CD config | ⬜ | CI/CD System | | HIGH |
| OPS-036 | VM | Penetration testing annual (or after major changes) | Pentest report | ⬜ | Pentest Report | | CRITICAL |
| OPS-037 | VM | Vulnerability remediation SLA defined | Policy doc | ⬜ | Security Policies | | CRITICAL |
| OPS-038 | VM | Critical vulnerabilities remediated < 24 hours | VM dashboard | ⬜ | VM System | | CRITICAL |
| OPS-039 | VM | High vulnerabilities remediated < 7 days | VM dashboard | ⬜ | VM System | | CRITICAL |
| OPS-040 | VM | Exception process for unremediated vulnerabilities | Policy doc | ⬜ | Security Policies | | MEDIUM |

### 13.5 Patch Management

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| OPS-041 | Patch | Patch management policy documented | Policy doc | ⬜ | Security Policies | | HIGH |
| OPS-042 | Patch | Automated patching for EC2 (Systems Manager) | Patch Manager | ⬜ | Systems Manager | | HIGH |
| OPS-043 | Patch | Patch baseline definitions current | Patch baseline | ⬜ | Systems Manager | | HIGH |
| OPS-044 | Patch | Emergency patching procedure defined | Procedure | ⬜ | Security Policies | | HIGH |
| OPS-045 | Patch | Pre-production patch testing | Change process | ⬜ | ITSM System | | HIGH |
| OPS-046 | Patch | Container base images updated regularly | Image scan | ⬜ | ECR Console | | HIGH |
| OPS-047 | Patch | RDS automatic minor version upgrades enabled | RDS config | ⬜ | RDS Console | | HIGH |
| OPS-048 | Patch | Lambda runtime versions current | Lambda config | ⬜ | Lambda Console | | MEDIUM |
| OPS-049 | Patch | Patch compliance reporting | Compliance report | ⬜ | Systems Manager | | HIGH |
| OPS-050 | Patch | Non-compliant instances identified and remediated | Patch compliance | ⬜ | Systems Manager | | HIGH |

### 13.6 Configuration Management

| Check ID | Control Area | Requirement | Verification Method | Status | Evidence Location | Remediation Required | Priority |
|----------|--------------|-------------|---------------------|--------|-------------------|---------------------|----------|
| OPS-051 | Config | AWS Config enabled in all regions | AWS Config | ⬜ | AWS Config | | HIGH |
| OPS-052 | Config | Config rules aligned with security baseline | Config rules | ⬜ | AWS Config | | HIGH |
| OPS-053 | Config | Config conformance packs deployed | Config | ⬜ | AWS Config | | HIGH |
| OPS-054 | Config | Drift detection enabled for CloudFormation | Stack config | ⬜ | CloudFormation | | MEDIUM |
| OPS-055 | Config | Infrastructure as Code (IaC) for all resources | Repo review | ⬜ | Git Repository | | HIGH |
| OPS-056 | Config | IaC security scanning in CI/CD | CI/CD config | ⬜ | CI/CD System | | CRITICAL |
| OPS-057 | Config | Resource tagging policy enforced | Tag policy | ⬜ | AWS Organizations | | MEDIUM |
| OPS-058 | Config | Configuration changes logged and alerted | CloudTrail | ⬜ | CloudTrail | | HIGH |
| OPS-059 | Config | Configuration backup procedures | Backup config | ⬜ | AWS Backup | | HIGH |
| OPS-060 | Config | Configuration versioning and rollback capability | Git history | ⬜ | Git Repository | | HIGH |

---

## 14. Audit Execution Guide

### 14.1 Audit Planning

| Activity | Description | Responsible | Timeline |
|----------|-------------|-------------|----------|
| Scope Definition | Define audit boundaries and objectives | Security Lead | Week -4 |
| Resource Planning | Assign auditors and schedule | Security Lead | Week -4 |
| Tool Preparation | Ensure access to scanning tools | Security Engineer | Week -3 |
| Documentation Review | Gather policies and procedures | Compliance Officer | Week -3 |
| Stakeholder Notification | Inform relevant teams | Security Lead | Week -2 |
| Baseline Establishment | Document current state | Security Engineer | Week -2 |

### 14.2 Evidence Collection

#### 14.2.1 Automated Evidence

| Evidence Type | Collection Method | Storage Location | Retention |
|---------------|-------------------|------------------|-----------|
| Vulnerability Scan Reports | Automated scans | S3: security-evidence/scans/ | 3 years |
| Configuration Snapshots | AWS Config export | S3: security-evidence/config/ | 3 years |
| CloudTrail Logs | Log export | S3: security-evidence/logs/ | 7 years |
| SAST/DAST Reports | CI/CD artifacts | S3: security-evidence/code/ | 3 years |
| Penetration Test Reports | Manual upload | S3: security-evidence/pentest/ | 3 years |
| Compliance Scan Results | Automated export | S3: security-evidence/compliance/ | 3 years |

#### 14.2.2 Manual Evidence

| Evidence Type | Collection Method | Storage Location | Retention |
|---------------|-------------------|------------------|-----------|
| Policy Documents | Document upload | S3: security-evidence/policies/ | 7 years |
| Interview Records | Meeting notes | S3: security-evidence/interviews/ | 3 years |
| Observation Records | Photo/screenshot | S3: security-evidence/observations/ | 3 years |
| Sample Testing Results | Spreadsheet upload | S3: security-evidence/testing/ | 3 years |
| Training Records | HR system export | S3: security-evidence/training/ | 7 years |

### 14.3 Audit Execution Steps

```
Phase 1: Pre-Audit (Week 1)
├── Stakeholder kickoff meeting
├── Documentation review
├── Tool access verification
└── Baseline documentation

Phase 2: Fieldwork (Weeks 2-4)
├── Automated scanning execution
├── Manual control testing
├── Interview key personnel
├── Evidence collection
└── Issue identification

Phase 3: Analysis (Week 5)
├── Evidence evaluation
├── Risk scoring
├── Root cause analysis
└── Draft findings preparation

Phase 4: Reporting (Week 6)
├── Draft report preparation
├── Management review
├── Final report issuance
└── Remediation planning

Phase 5: Follow-up (Ongoing)
├── Remediation tracking
├── Validation testing
├── Closure confirmation
└── Trend analysis
```

### 14.4 Sampling Guidelines

| Control Population | Sample Size | Method |
|-------------------|-------------|--------|
| User accounts (< 100) | 25% or minimum 10 | Random |
| User accounts (100-500) | 15% or minimum 25 | Random |
| User accounts (> 500) | 10% or minimum 50 | Random |
| IAM policies | 100% if < 50, else 25% | Stratified |
| S3 buckets | 100% | Complete |
| EC2 instances | 25% or minimum 10 | Random |
| Security groups | 100% | Complete |
| Code commits (monthly) | 10% of commits | Random |
| Access logs (monthly) | 1 week of logs | Systematic |

---

## 15. Remediation Tracking

### 15.1 Issue Tracking Process

| Step | Action | Owner | Timeline |
|------|--------|-------|----------|
| 1 | Issue documented in tracking system | Auditor | Within 24h of finding |
| 2 | Initial risk assessment completed | Security Lead | Within 48h |
| 3 | Remediation owner assigned | IT Director | Within 72h |
| 4 | Remediation plan submitted | Remediation Owner | 5 business days |
| 5 | Plan approved/rejected | Security Lead | 2 business days |
| 6 | Remediation executed | Remediation Owner | Per SLA |
| 7 | Validation testing completed | Security Team | Within 5 days of fix |
| 8 | Issue closed or re-opened | Security Lead | Upon validation |

### 15.2 Risk Scoring Matrix

| Factor | Criteria | Score |
|--------|----------|-------|
| **Likelihood** | | |
| | Very High - Exploit code available, no authentication | 4 |
| | High - Easily exploitable with authentication | 3 |
| | Medium - Requires some effort/expertise | 2 |
| | Low - Difficult to exploit | 1 |
| **Impact** | | |
| | Critical - System compromise, data breach | 4 |
| | High - Significant data exposure, service disruption | 3 |
| | Medium - Limited data exposure, minor disruption | 2 |
| | Low - Minimal impact | 1 |
| **Asset Value** | | |
| | Critical - Production systems, sensitive data | 3 |
| | High - Important systems, internal data | 2 |
| | Medium - Development, non-sensitive | 1 |

**Risk Score = Likelihood × Impact × Asset Value**

| Score Range | Severity | SLA |
|-------------|----------|-----|
| 36-48 | Critical | 24 hours |
| 24-35 | High | 7 days |
| 12-23 | Medium | 30 days |
| 3-11 | Low | 90 days |

### 15.3 Remediation Status Tracking

| Check ID | Finding | Risk Score | Owner | Target Date | Status | Validation Date |
|----------|---------|------------|-------|-------------|--------|-----------------|
| | | | | | Open/In Progress/Closed | |

Status Definitions:
- **Open**: Issue identified, no remediation started
- **In Progress**: Remediation plan approved, work underway
- **Pending Validation**: Remediation complete, awaiting validation
- **Closed**: Validation complete, issue resolved
- **Accepted Risk**: Risk accepted by management (requires exception)

### 15.4 Validation Requirements

| Severity | Validation Method | Evidence Required |
|----------|-------------------|-------------------|
| Critical | Re-scan + Manual verification | Scan report + Screenshots |
| High | Re-scan or Manual verification | Scan report or Screenshots |
| Medium | Spot check or Documentation review | Evidence sample |
| Low | Documentation review | Policy/procedure update |

---

## 16. Appendices

### Appendix A: Full Pre-Go-Live Checklist (300+ Checks Summary)

#### A.1 Critical Path Checks (Must Pass)

| Category | Check Count | Status |
|----------|-------------|--------|
| Authentication & Authorization | 25 | ⬜ |
| Data Encryption | 20 | ⬜ |
| Network Security | 20 | ⬜ |
| Application Security | 30 | ⬜ |
| Database Security | 20 | ⬜ |
| Logging & Monitoring | 20 | ⬜ |
| **Total Critical** | **135** | |

#### A.2 High Priority Checks

| Category | Check Count | Status |
|----------|-------------|--------|
| Infrastructure Security | 40 | ⬜ |
| Access Management | 30 | ⬜ |
| Data Protection | 25 | ⬜ |
| Third-Party Security | 20 | ⬜ |
| Mobile Security | 20 | ⬜ |
| **Total High** | **135** | |

#### A.3 Standard Checks

| Category | Check Count | Status |
|----------|-------------|--------|
| IoT Security | 25 | ⬜ |
| Operational Security | 20 | ⬜ |
| Compliance Documentation | 15 | ⬜ |
| **Total Standard** | **60** | |

### Appendix B: Ongoing Audit Schedule

| Audit Type | Frequency | Scope | Duration | Responsible |
|------------|-----------|-------|----------|-------------|
| Vulnerability Scan | Weekly | All external-facing systems | 4 hours | Security Engineer |
| Configuration Review | Monthly | Infrastructure changes | 8 hours | Security Engineer |
| Access Review | Monthly | Privileged accounts | 4 hours | IAM Administrator |
| SAST Scan | Per commit | Code changes | Automated | CI/CD |
| DAST Scan | Weekly | Staging environment | 8 hours | Security Engineer |
| Dependency Scan | Weekly | All dependencies | Automated | CI/CD |
| Container Scan | Per build | All container images | Automated | CI/CD |
| Compliance Scan | Monthly | AWS Config rules | 4 hours | Compliance Officer |
| Penetration Test | Quarterly | Full application | 2 weeks | External Vendor |
| Full Security Audit | Semi-annual | Complete infrastructure | 4 weeks | Security Lead |

### Appendix C: Compliance Mapping

#### C.1 PCI DSS 4.0 Mapping

| PCI Requirement | Relevant Check IDs |
|-----------------|-------------------|
| 1. Firewall Configuration | NET-011 to NET-020 |
| 2. System Passwords | IAM-011 to IAM-020 |
| 3. Stored Cardholder Data | DTP-001 to DTP-010 |
| 4. Encryption in Transit | DTP-021 to DTP-030 |
| 5. Anti-Malware | OPS-031 to OPS-040 |
| 6. Secure Development | APP-001 to APP-090 |
| 7. Access Control | IAM-021 to IAM-050 |
| 8. Identity Management | IAM-001 to IAM-010 |
| 9. Physical Access | (Physical controls) |
| 10. Logging & Monitoring | OPS-001 to OPS-020 |
| 11. Security Testing | OPS-031 to OPS-040 |
| 12. Information Security | ISO-001 to ISO-015 |

#### C.2 ISO 27001:2022 Mapping

| ISO Control | Relevant Check IDs |
|-------------|-------------------|
| 5.1 Policies | ISO-001, PCI-015 |
| 5.2 Roles | ISO-002 |
| 5.3 Segregation | NET-001 to NET-010 |
| 5.4 Management | OPS-051 to OPS-060 |
| 5.5 Policies | All policy references |
| 6.1 Screening | HR procedures |
| 6.2 Terms | HR procedures |
| 6.3 Awareness | PGL-090 |
| 6.4 Disciplinary | HR procedures |
| 6.5 Responsibilities | IAM-001 to IAM-010 |

#### C.3 GDPR Article Mapping

| GDPR Article | Relevant Check IDs |
|--------------|-------------------|
| Art. 5 Principles | DTP-001 to DTP-060 |
| Art. 6 Lawfulness | GDPR-004 |
| Art. 7 Consent | GDPR-002, BDPA-002 |
| Art. 12 Transparent | GDPR-001 |
| Art. 15 Access | GDPR-005 |
| Art. 16 Rectification | GDPR-005 |
| Art. 17 Erasure | GDPR-006 |
| Art. 25 PbD | GDPR-009 |
| Art. 32 Security | All security controls |
| Art. 33 Breach | GDPR-011, GDPR-012 |

### Appendix D: Scoring Rubrics

#### D.1 Maturity Scoring (1-5)

| Level | Description | Criteria |
|-------|-------------|----------|
| 1 - Initial | Ad-hoc, reactive | No documented process; inconsistent implementation |
| 2 - Developing | Basic, documented | Process documented; partially implemented |
| 3 - Defined | Standardized | Process standardized; consistently implemented |
| 4 - Quantitative | Measured | Process measured; metrics collected |
| 5 - Optimizing | Continuous improvement | Process optimized; automated where possible |

#### D.2 Control Effectiveness Scoring

| Score | Rating | Evidence Quality | Implementation |
|-------|--------|------------------|----------------|
| 5 | Excellent | Complete, verified | Fully implemented, automated |
| 4 | Good | Substantial | Implemented with minor gaps |
| 3 | Satisfactory | Adequate | Implemented but not verified |
| 2 | Needs Improvement | Limited | Partially implemented |
| 1 | Unsatisfactory | None | Not implemented |
| 0 | Not Applicable | N/A | Control not applicable |

### Appendix E: Report Templates

#### E.1 Executive Summary Template

```markdown
# Security Audit Executive Summary

## Audit Details
- **Audit Period**: [Start Date] - [End Date]
- **Audit Type**: [Internal/External/Compliance]
- **Scope**: [Systems in scope]
- **Auditors**: [Names]

## Overall Assessment
[One paragraph summary of overall security posture]

## Key Findings Summary
| Severity | Count | Trend |
|----------|-------|-------|
| Critical | X | ⬆/⬇/➡ |
| High | X | ⬆/⬇/➡ |
| Medium | X | ⬆/⬇/➡ |
| Low | X | ⬆/⬇/➡ |

## Top 5 Risks
1. [Risk description and business impact]
2. ...

## Recommendations
[Top 3-5 strategic recommendations]

## Management Response
[To be completed by management]
```

#### E.2 Detailed Finding Template

```markdown
## Finding ID: [XXX-###]

### Title
[Brief description]

### Description
[Detailed description of the finding]

### Risk Rating
- **Likelihood**: [High/Medium/Low]
- **Impact**: [Critical/High/Medium/Low]
- **Overall**: [Critical/High/Medium/Low]
- **CVSS Score**: [If applicable]

### Affected Resources
- [Resource 1]
- [Resource 2]

### Evidence
[Evidence description and location]

### Root Cause
[Underlying cause of the finding]

### Remediation
[Specific steps to remediate]

### Timeline
- **Target Date**: [Date]
- **Responsible**: [Name/Role]

### Compliance Impact
[Applicable regulations/standards affected]
```

#### E.3 Audit Certificate Template

```markdown
# SECURITY AUDIT CERTIFICATE

**Document**: F-010 Security Audit Checklist
**Audit Date**: [Date]
**Auditor**: [Name/Organization]

## Scope Verification
The following systems were audited:
- [System 1]
- [System 2]

## Findings Summary
| Category | Pass | Fail | N/A |
|----------|------|------|-----|
| Pre-Go-Live | X | X | X |
| Infrastructure | X | X | X |
| Application | X | X | X |
| Database | X | X | X |
| Network | X | X | X |
| IAM | X | X | X |
| Data Protection | X | X | X |
| Third-Party | X | X | X |
| Mobile | X | X | X |
| IoT | X | X | X |
| Compliance | X | X | X |
| Operations | X | X | X |

## Overall Result
☐ PASS - All critical and high findings remediated
☐ CONDITIONAL PASS - Remediation plan approved
☐ FAIL - Critical findings require immediate attention

## Auditor Sign-off
Name: _________________________
Signature: _________________________
Date: _________________________

## Management Sign-off
Name: _________________________
Signature: _________________________
Date: _________________________
```

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Security Lead | | |
| Reviewer | IT Director | | |
| Approver | CTO | | |

---

## Distribution List

| Role | Department | Format |
|------|------------|--------|
| CTO | Technology | Electronic |
| IT Director | Technology | Electronic |
| Security Lead | Security | Electronic |
| Compliance Officer | Legal/Compliance | Electronic |
| DevOps Lead | Technology | Electronic |

---

*Document Control: This document is controlled. All copies are managed through the document management system. Any printed copies are considered uncontrolled.*

*Classification: CONFIDENTIAL - Internal Use Only*

---

**END OF DOCUMENT**

