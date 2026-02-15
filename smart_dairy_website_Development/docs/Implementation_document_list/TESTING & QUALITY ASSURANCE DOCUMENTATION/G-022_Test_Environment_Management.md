# Test Environment Management

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | G-022 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | DevOps Engineer |
| **Owner** | QA Lead |
| **Reviewer** | IT Director |
| **Classification** | Internal Use |
| **Status** | Draft |

### Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | 2026-01-31 | DevOps Engineer | Initial version |

### Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| QA Lead | | | |
| IT Director | | | |
| Project Manager | | | |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Environment Types](#2-environment-types)
3. [Environment Architecture](#3-environment-architecture)
4. [Environment Configuration](#4-environment-configuration)
5. [Data Management](#5-data-management)
6. [Access Control](#6-access-control)
7. [Deployment Process](#7-deployment-process)
8. [Monitoring & Health Checks](#8-monitoring--health-checks)
9. [Troubleshooting](#9-troubleshooting)
10. [Environment Booking](#10-environment-booking)
11. [Maintenance Windows](#11-maintenance-windows)
12. [Cost Management](#12-cost-management)
13. [Security](#13-security)
14. [Documentation](#14-documentation)
15. [Vendor Management](#15-vendor-management)
16. [Disaster Recovery](#16-disaster-recovery)
17. [Appendices](#17-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document establishes the framework for managing test environments for the Smart Dairy Web Portal System. It defines policies, procedures, and standards for environment provisioning, configuration, maintenance, and access control to ensure consistent and reliable testing capabilities across all project phases.

### 1.2 Scope

This document applies to:
- All test environments supporting Smart Dairy Web Portal development
- Development, QA, staging, UAT, pre-production, and production environments
- Infrastructure components (servers, databases, networks, middleware)
- Environment-related processes and procedures
- All team members requiring environment access

### 1.3 Objectives

The Test Environment Management strategy aims to:

| Objective | Description | Success Metric |
|-----------|-------------|----------------|
| **Availability** | Ensure environments are available when needed | 99.5% uptime for QA and above |
| **Consistency** | Maintain configuration consistency across environments | <5% configuration drift |
| **Isolation** | Prevent cross-environment contamination | Zero unauthorized data leaks |
| **Efficiency** | Optimize resource utilization and cost | 20% cost reduction YoY |
| **Security** | Protect sensitive data and access | 100% compliance with security policies |
| **Traceability** | Maintain audit trails for all changes | Complete change logs |

### 1.4 Environment Strategy

Smart Dairy employs a multi-tier environment strategy aligned with the Software Development Life Cycle (SDLC):

```
┌─────────────────────────────────────────────────────────────────────┐
│                    ENVIRONMENT PROMOTION PIPELINE                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│   [DEV] ──► [QA] ──► [STAGING] ──► [UAT] ──► [PRE-PROD] ──► [PROD] │
│    │        │         │           │          │           │         │
│   Local   Shared    System      User      Final       Live        │
│   Dev     Testing   Testing    Testing   Validation   Production   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 1.5 Governance

| Role | Responsibility |
|------|----------------|
| **QA Lead** | Overall environment strategy and policy enforcement |
| **DevOps Engineer** | Infrastructure provisioning and maintenance |
| **IT Director** | Security compliance and resource approval |
| **Development Lead** | Development environment standards |
| **Release Manager** | Deployment coordination across environments |

### 1.6 Document References

| Document ID | Title | Description |
|-------------|-------|-------------|
| G-001 | Project Governance | Overall project governance framework |
| G-020 | Test Strategy | Testing approach and methodologies |
| G-021 | Test Data Management | Data creation and management procedures |
| G-023 | Release Management | Deployment and release procedures |
| G-024 | Incident Management | Incident response procedures |

---

## 2. Environment Types

### 2.1 Environment Overview

Smart Dairy maintains six distinct environment tiers, each serving specific purposes in the development and testing lifecycle.

### 2.2 Development Environment (DEV)

| Attribute | Specification |
|-----------|---------------|
| **Purpose** | Individual developer workstations and local testing |
| **Users** | Developers, Technical Leads |
| **Data** | Synthetic/test data only |
| **Availability** | 24/7 (developer-managed) |
| **Support** | Self-service with documentation |

**Specifications:**
- **Per Developer:** 2 vCPU, 4GB RAM
- **Storage:** 50GB SSD per instance
- **Network:** Internal VPN access
- **Instances:** Scalable based on team size

**Usage Guidelines:**
- Used for feature development and unit testing
- Code must be committed to version control daily
- Local databases should use containerized instances
- No production data allowed

### 2.3 Quality Assurance Environment (QA)

| Attribute | Specification |
|-----------|---------------|
| **Purpose** | Functional testing, regression testing, integration testing |
| **Users** | QA Engineers, Automation Engineers |
| **Data** | Masked production data subset |
| **Availability** | 24/7 (scheduled maintenance windows) |
| **Support** | DevOps team during business hours |

**Specifications:**
- **Shared Infrastructure:** 4 vCPU, 8GB RAM
- **Storage:** 200GB SSD
- **Network:** Internal network with controlled external access
- **Instances:** 2 parallel QA environments (QA-1, QA-2)

**Usage Guidelines:**
- Dedicated QA environment for test execution
- Automated test suites run nightly
- Manual testing during business hours
- Refreshed with latest stable build daily

### 2.4 Staging Environment (STAGING)

| Attribute | Specification |
|-----------|---------------|
| **Purpose** | System testing, performance testing, security testing |
| **Users** | QA Team, DevOps, Security Team |
| **Data** | Full masked production dataset |
| **Availability** | 24/7 (minimal maintenance) |
| **Support** | DevOps team with on-call rotation |

**Specifications:**
- **Production-like:** 8 vCPU, 16GB RAM
- **Storage:** 500GB SSD (RAID 10)
- **Network:** DMZ with restricted access
- **Configuration:** Mirrors production architecture

**Usage Guidelines:**
- Production-equivalent environment for final validation
- Performance baseline testing
- Security scan target
- Load testing environment

### 2.5 User Acceptance Testing Environment (UAT)

| Attribute | Specification |
|-----------|---------------|
| **Purpose** | Business user validation and acceptance testing |
| **Users** | Business Analysts, End Users, Product Owners |
| **Data** | Production-like masked data |
| **Availability** | Business hours + scheduled extended hours |
| **Support** | Business hours support with escalation |

**Specifications:**
- **User Testing:** 8 vCPU, 16GB RAM
- **Storage:** 300GB SSD
- **Network:** Internal network with controlled external access
- **Access:** SSO-enabled with role-based permissions

**Usage Guidelines:**
- Controlled environment for business validation
- Scheduled UAT sessions with environment booking
- Stable builds only (release candidates)
- Direct feedback loop to development team

### 2.6 Pre-Production Environment (PRE-PROD)

| Attribute | Specification |
|-----------|---------------|
| **Purpose** | Final validation, deployment rehearsal, rollback testing |
| **Users** | Release Manager, DevOps, QA Lead |
| **Data** | Production data replica (synchronized) |
| **Availability** | 24/7 (freeze during deployments) |
| **Support** | 24/7 on-call support |

**Specifications:**
- **High-Availability:** 16 vCPU, 32GB RAM
- **Storage:** 1TB SSD (RAID 10)
- **Network**: Production-equivalent network topology
- **Monitoring:** Production-level monitoring stack |

**Usage Guidelines:**
- Final checkpoint before production deployment
- Blue-green deployment testing
- Database migration validation
- Disaster recovery rehearsal

### 2.7 Production Environment (PROD)

| Attribute | Specification |
|-----------|---------------|
| **Purpose** | Live system serving end users |
| **Users** | End Users, System Administrators (read-only monitoring) |
| **Data** | Live production data |
| **Availability** | 99.9% uptime SLA |
| **Support** | 24/7 premium support |

**Specifications:**
- **High-Availability:** Auto-scaling cluster
- **Storage**: Multi-region redundant storage
- **Network:** Global CDN with DDoS protection
- **Backup:** Continuous backup with point-in-time recovery |

### 2.8 Environment Comparison Matrix

| Criteria | DEV | QA | STAGING | UAT | PRE-PROD | PROD |
|----------|-----|-----|---------|-----|----------|------|
| **vCPU** | 2 | 4 | 8 | 8 | 16 | Auto-scale |
| **RAM (GB)** | 4 | 8 | 16 | 16 | 32 | Auto-scale |
| **Storage (GB)** | 50 | 200 | 500 | 300 | 1,000 | Multi-TB |
| **Uptime SLA** | Best Effort | 99.5% | 99.5% | 95% | 99.9% | 99.9% |
| **Data Type** | Synthetic | Masked Subset | Masked Full | Masked | Production Replica | Production |
| **Refresh Frequency** | On-demand | Daily | Weekly | Per-release | Pre-release | N/A |
| **Access Control** | Local | Role-based | Role-based | SSO + Approval | Strict Approval | Restricted |
| **Support Level** | Self | Business Hours | Business Hours | Business Hours | 24/7 | 24/7 |
| **Monitoring** | Basic | Standard | Advanced | Standard | Production | Enterprise |
| **Cost Tier** | $ | $$ | $$$ | $$ | $$$$ | $$$$$ |

---

## 3. Environment Architecture

### 3.1 High-Level Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         SMART DAIRY ENVIRONMENT LANDSCAPE                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐        │
│  │    DEV      │  │     QA      │  │   STAGING   │  │     UAT     │        │
│  │  (Local)    │  │  (Shared)   │  │  (System)   │  │  (Business) │        │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘        │
│         │                │                │                │               │
│         └────────────────┴────────────────┴────────────────┘               │
│                                   │                                         │
│                            ┌──────┴──────┐                                  │
│                            │   CI/CD     │                                  │
│                            │  Pipeline   │                                  │
│                            └──────┬──────┘                                  │
│                                   │                                         │
│         ┌─────────────────────────┼─────────────────────────┐               │
│         │                         │                         │               │
│  ┌──────┴──────┐           ┌──────┴──────┐           ┌──────┴──────┐        │
│  │  PRE-PROD   │◄─────────►│   PROD      │◄─────────►│   DR Site   │        │
│  │ (Final Val) │           │   (Live)    │           │ (Failover)  │        │
│  └─────────────┘           └─────────────┘           └─────────────┘        │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Network Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           NETWORK SEGMENTATION                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│   INTERNET                                                                 │
│      │                                                                     │
│      ▼                                                                     │
│   ┌─────────────┐                                                          │
│   │    WAF      │  Web Application Firewall                                │
│   │   (CDN)     │                                                          │
│   └──────┬──────┘                                                          │
│          │                                                                 │
│   ┌──────┴──────────────────────────────────────────────────────────┐      │
│   │                         DMZ                                      │      │
│   │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐              │      │
│   │  │   STAGING   │  │  PRE-PROD   │  │    PROD     │  (Edge)      │      │
│   │  │   Proxy     │  │   Proxy     │  │   Proxy     │              │      │
│   │  └─────────────┘  └─────────────┘  └─────────────┘              │      │
│   └──────┬────────────────────────────────────────────────────┬─────┘      │
│          │                                                    │            │
│   ┌──────┴────────────────────────┐     ┌────────────────────┴─────┐       │
│   │        INTERNAL NETWORK       │     │    MANAGEMENT NETWORK    │       │
│   │                               │     │                          │       │
│   │  ┌─────────┐  ┌─────────┐    │     │  ┌─────────┐  ┌────────┐ │       │
│   │  │   DEV   │  │   QA    │    │     │  │Monitoring│  │  Logs  │ │       │
│   │  │ Servers │  │ Servers │    │     │  │  Stack  │  │ Server │ │       │
│   │  └─────────┘  └─────────┘    │     │  └─────────┘  └────────┘ │       │
│   │                               │     │                          │       │
│   │  ┌─────────┐  ┌─────────┐    │     │  ┌─────────┐  ┌────────┐ │       │
│   │  │   UAT   │  │  BACKUP │    │     │  │ Jumpbox │  │  VPN   │ │       │
│   │  │ Servers │  │ Servers │    │     │  │ (Bastion)│  │ Gateway│ │       │
│   │  └─────────┘  └─────────┘    │     │  └─────────┘  └────────┘ │       │
│   └───────────────────────────────┘     └──────────────────────────┘       │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.3 Application Architecture per Environment

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    STANDARD APPLICATION STACK                               │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│   LAYER              COMPONENTS                                             │
│   ─────────────────────────────────────────────────────────────────────     │
│                                                                             │
│   Load Balancer      ┌─────────────────────────────────────────┐           │
│   (Layer 7)          │     Nginx / AWS ALB / Azure App GW      │           │
│                      └─────────────────────────────────────────┘           │
│                                    │                                       │
│   Web Tier           ┌─────────────┴─────────────┐                        │
│   (Node.js/React)    │   Web Application Servers   │                        │
│                      │   (Auto-scaling Group)      │                        │
│                      └─────────────┬─────────────┘                        │
│                                    │                                       │
│   API Tier           ┌─────────────┴─────────────┐                        │
│   (.NET Core)        │    API Application Servers  │                        │
│                      │    (Auto-scaling Group)     │                        │
│                      └─────────────┬─────────────┘                        │
│                                    │                                       │
│   Data Tier          ┌─────────────┴─────────────┐                        │
│   (PostgreSQL/Redis) │   Primary DB │  Replica DB │                        │
│                      │   Cache Layer (Redis)       │                        │
│                      └─────────────────────────────┘                        │
│                                                                             │
│   Storage            ┌─────────────────────────────────────────┐           │
│   (S3/Azure Blob)    │     Object Storage (Documents/Images)   │           │
│                      └─────────────────────────────────────────┘           │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.4 Environment Specifications Summary

| Component | DEV | QA | STAGING | UAT | PRE-PROD |
|-----------|-----|-----|---------|-----|----------|
| **Web Servers** | 1 | 2 | 2 | 2 | 3 |
| **API Servers** | 1 | 2 | 3 | 2 | 4 |
| **Database** | Container | Single | Primary+Replica | Primary+Replica | HA Cluster |
| **Cache** | Container | Single | Cluster | Cluster | Cluster |
| **Load Balancer** | None | Single | HA Pair | Single | HA Pair |

---

## 4. Environment Configuration

### 4.1 Hardware Specifications

#### 4.1.1 Compute Resources

| Environment | Instance Type | vCPU | RAM (GB) | Instances | Total vCPU | Total RAM (GB) |
|-------------|---------------|------|----------|-----------|------------|----------------|
| **DEV** | t3.medium | 2 | 4 | 10 | 20 | 40 |
| **QA** | t3.large | 4 | 8 | 4 | 16 | 32 |
| **STAGING** | c5.2xlarge | 8 | 16 | 5 | 40 | 80 |
| **UAT** | c5.2xlarge | 8 | 16 | 4 | 32 | 64 |
| **PRE-PROD** | c5.4xlarge | 16 | 32 | 7 | 112 | 224 |

#### 4.1.2 Storage Configuration

| Environment | OS Volume (GB) | Data Volume (GB) | Log Volume (GB) | Backup Volume (GB) | Total (TB) |
|-------------|----------------|------------------|-----------------|--------------------|------------|
| **DEV** | 20 | 30 | 10 | 20 | 0.8 |
| **QA** | 50 | 100 | 50 | 100 | 1.2 |
| **STAGING** | 100 | 300 | 100 | 200 | 3.5 |
| **UAT** | 100 | 150 | 50 | 100 | 2.0 |
| **PRE-PROD** | 200 | 600 | 200 | 500 | 11.0 |

#### 4.1.3 Network Configuration

| Parameter | DEV | QA | STAGING | UAT | PRE-PROD |
|-----------|-----|-----|---------|-----|----------|
| **VPC/Network** | dev-vpc | qa-vpc | staging-vpc | uat-vpc | preprod-vpc |
| **Subnets** | Private | Private | DMZ + Private | Private | DMZ + Private |
| **Public IPs** | NAT Gateway | NAT Gateway | ALB Public | NAT Gateway | ALB Public |
| **Bandwidth** | 100 Mbps | 500 Mbps | 1 Gbps | 500 Mbps | 2 Gbps |
| **Latency Target** | <50ms | <30ms | <20ms | <30ms | <10ms |

### 4.2 Software Configuration

#### 4.2.1 Operating System

| Component | Specification |
|-----------|---------------|
| **OS** | Ubuntu Server 22.04 LTS |
| **Kernel** | 5.15 LTS |
| **Updates** | Security patches auto-applied |
| **Hardening** | CIS Level 1 compliant |

#### 4.2.2 Runtime Environment

| Component | Version | Purpose |
|-----------|---------|---------|
| **Node.js** | 18.x LTS | Frontend runtime |
| **.NET Core** | 8.0 | Backend runtime |
| **PostgreSQL** | 15.x | Primary database |
| **Redis** | 7.x | Caching layer |
| **Nginx** | 1.24.x | Reverse proxy |
| **Docker** | 24.x | Container runtime |
| **Kubernetes** | 1.28.x | Container orchestration (Staging+) |

#### 4.2.3 Configuration Management

All environment configurations are managed through:
- **Infrastructure as Code:** Terraform
- **Configuration as Code:** Ansible
- **Secrets Management:** HashiCorp Vault / AWS Secrets Manager
- **Version Control:** Git with environment-specific branches

### 4.3 Environment Setup Procedures

#### 4.3.1 New Environment Provisioning

```
PROCEDURE: PROV-001 - New Environment Setup
──────────────────────────────────────────────────────────────────────────────

STEP 1: Request Submission
────────
□ Submit Environment Request Form (Appendix C)
□ Obtain approval from QA Lead and IT Director
□ Receive environment identifier (e.g., QA-03)

STEP 2: Infrastructure Provisioning
────────
□ Create Terraform workspace for environment
□ Execute terraform plan with environment variables
□ Review and approve resource creation
□ Execute terraform apply
□ Verify infrastructure creation in cloud console

STEP 3: Base Configuration
────────
□ Run Ansible playbook: site.yml
□ Configure OS hardening
□ Install monitoring agents
□ Configure log shipping
□ Validate base configuration

STEP 4: Application Deployment
────────
□ Deploy database schemas
□ Deploy application containers/images
□ Configure environment variables
□ Set up SSL certificates
□ Configure DNS entries

STEP 5: Data Provisioning
────────
□ Execute data provisioning script
□ Mask sensitive data (if applicable)
□ Validate data integrity
□ Verify application connectivity

STEP 6: Access Configuration
────────
□ Create user accounts per Access Matrix
□ Configure SSO integration
□ Set up role-based permissions
□ Test access for all user types

STEP 7: Monitoring Setup
────────
□ Configure health checks
□ Set up alerting rules
□ Verify dashboard visibility
□ Test alert notifications

STEP 8: Handover
────────
□ Update Environment Inventory (Appendix A)
□ Conduct handover session with requestor
□ Document any environment-specific notes
□ Close provisioning ticket

ESTIMATED TIME: 4-8 hours (depending on environment tier)
```

#### 4.3.2 Environment Decommissioning

```
PROCEDURE: PROV-002 - Environment Decommissioning
──────────────────────────────────────────────────────────────────────────────

STEP 1: Pre-Decommission Check
────────
□ Verify no active bookings for environment
□ Confirm data backup completion
□ Notify stakeholders 48 hours in advance
□ Obtain decommissioning approval

STEP 2: Data Preservation
────────
□ Export configuration data
□ Archive application logs (retain 90 days)
□ Document final state
□ Transfer ownership of any assets

STEP 3: Resource Cleanup
────────
□ Execute terraform destroy
□ Remove DNS entries
□ Revoke SSL certificates
□ Delete monitoring configurations

STEP 4: Access Revocation
────────
□ Disable user accounts
□ Remove from SSO configuration
□ Revoke API keys and tokens
□ Update access documentation

STEP 5: Documentation Update
────────
□ Update Environment Inventory
□ Archive environment documentation
□ Record decommissioning date
□ Close all related tickets

ESTIMATED TIME: 2-4 hours
```

---

## 5. Data Management

### 5.1 Data Classification

| Data Class | Description | Allowed Environments |
|------------|-------------|----------------------|
| **Class 1: Production** | Live customer data, PII, financial data | PROD only |
| **Class 2: Masked Production** | Production data with PII masked/anonymized | STAGING, UAT, PRE-PROD |
| **Class 3: Synthetic** | Generated test data with realistic patterns | DEV, QA |
| **Class 4: Public** | Non-sensitive, publicly available data | All environments |

### 5.2 Data Refresh Procedures

#### 5.2.1 Refresh Schedule

| Environment | Refresh Frequency | Source | Window | Duration |
|-------------|-------------------|--------|--------|----------|
| **DEV** | On-demand | Synthetic | Anytime | 30 minutes |
| **QA** | Daily | Masked Prod (subset) | 02:00-04:00 | 2 hours |
| **STAGING** | Weekly | Masked Prod (full) | Sunday 00:00-06:00 | 6 hours |
| **UAT** | Per-release | Masked Prod (subset) | Before UAT start | 2 hours |
| **PRE-PROD** | Pre-release | Production (sync) | 24h before release | 4 hours |

#### 5.2.2 Data Refresh Procedure

```
PROCEDURE: DATA-001 - Environment Data Refresh
──────────────────────────────────────────────────────────────────────────────

STEP 1: Pre-Refresh Preparation
────────
□ Verify environment is available for maintenance
□ Notify users of upcoming refresh
□ Create pre-refresh backup
□ Disable automated tests
□ Place environment in maintenance mode

STEP 2: Data Extraction (Source)
────────
□ Export data from source environment
□ Validate data export completeness
□ Encrypt sensitive data for transfer
□ Generate data integrity checksums

STEP 3: Data Masking (if applicable)
────────
□ Apply PII masking rules
□ Scramble sensitive identifiers
□ Preserve referential integrity
□ Validate masking effectiveness

STEP 4: Data Loading (Target)
────────
□ Truncate existing data (or drop/recreate)
□ Load new data into target environment
□ Rebuild indexes and constraints
□ Update statistics

STEP 5: Post-Refresh Validation
────────
□ Run data integrity checks
□ Verify row counts match expected
□ Test application connectivity
□ Execute smoke tests

STEP 6: Environment Restoration
────────
□ Remove maintenance mode
□ Re-enable automated tests
□ Notify users of completion
□ Update refresh log

ROLLBACK: If any step fails, restore from pre-refresh backup
```

### 5.3 Data Masking

#### 5.3.1 Masking Rules

| Data Type | Masking Technique | Example |
|-----------|-------------------|---------|
| **Names** | Faker substitution | "John Smith" → "Jane Doe" |
| **Email** | Domain preservation | "user@company.com" → "abc123@company.com" |
| **Phone** | Random generation | "+65-9123-4567" → "+65-9000-0000" |
| **NRIC/ID** | Consistent hashing | "S1234567A" → "S9876543Z" |
| **Credit Card** | Partial masking | "4111-1111-1111-1111" → "4111-****-****-1111" |
| **Addresses** | Random substitution | Real address → Random valid address |
| **Financial** | Variance application | $100.00 → $103.45 (±5%) |

#### 5.3.2 Masking Procedure

```
PROCEDURE: DATA-002 - Data Masking Execution
──────────────────────────────────────────────────────────────────────────────

REQUIRES: Data Administrator privilege

STEP 1: Identify Sensitive Columns
────────
□ Query information schema for PII columns
□ Verify masking rules for each column type
□ Document any custom masking requirements

STEP 2: Execute Masking
────────
□ Run masking script: scripts/data/mask_data.py
□ Specify source and target databases
□ Verify masking configuration
□ Execute masking in transaction

STEP 3: Validate Masking
────────
□ Sample 1000 rows for manual verification
□ Verify no original PII remains
□ Check referential integrity
□ Validate application functionality

STEP 4: Audit
────────
□ Log masking execution details
□ Store pre/post row counts
□ Document any exceptions
□ Sign off masking completion
```

### 5.4 Data Provisioning

| Request Type | SLA | Approval Required | Automated |
|--------------|-----|-------------------|-----------|
| Synthetic data generation | 1 hour | No | Yes |
| Masked subset refresh | 4 hours | QA Lead | Partial |
| Full masked refresh | 24 hours | IT Director | No |
| Production sync | 48 hours | CTO | No |

---

## 6. Access Control

### 6.1 Access Control Principles

- **Principle of Least Privilege:** Users receive minimum necessary access
- **Segregation of Duties:** No single user has unrestricted access
- **Need-to-Know:** Access granted based on role requirements
- **Regular Review:** Quarterly access certification

### 6.2 Role-Based Access Matrix

| Role | DEV | QA | STAGING | UAT | PRE-PROD | PROD |
|------|-----|-----|---------|-----|----------|------|
| **Developer** | Full | Read | None | None | None | None |
| **Senior Developer** | Full | Full | Read | Read | None | None |
| **QA Engineer** | None | Full | Read | Full | None | None |
| **QA Lead** | None | Full | Full | Full | Read | None |
| **DevOps Engineer** | Full | Full | Full | Full | Full | Read |
| **Release Manager** | None | None | None | None | Full | Deploy |
| **Business Analyst** | None | None | None | Full | None | None |
| **IT Director** | None | None | None | None | Full | Full |

### 6.3 Access Types

| Access Level | Permissions |
|--------------|-------------|
| **None** | No access to environment |
| **Read** | View-only access (logs, dashboards, read queries) |
| **Full** | Administrative access (deploy, configure, manage) |
| **Deploy** | Deployment-only access (CI/CD service accounts) |

### 6.4 Access Request Process

```
PROCEDURE: ACC-001 - Environment Access Request
──────────────────────────────────────────────────────────────────────────────

STEP 1: Request Submission
────────
□ Complete Environment Access Request Form (Appendix D)
□ Specify environment(s), duration, and justification
□ Obtain manager approval
□ Submit to QA Lead for review

STEP 2: Review and Approval
────────
□ QA Lead reviews request against Access Matrix
□ Verify business justification
□ Check for conflicting access
□ Approve or reject with explanation

STEP 3: Provisioning
────────
□ Create/update user account
□ Assign appropriate roles/groups
□ Configure MFA (if required)
□ Set access expiration date

STEP 4: Notification
────────
□ Notify user of access grant
□ Provide access instructions
□ Share environment credentials (securely)
□ Schedule access review

STEP 5: Monitoring
────────
□ Log all access activities
□ Alert on anomalous behavior
□ Review access quarterly
□ Revoke expired access automatically
```

### 6.5 Single Sign-On (SSO) Configuration

| Environment | SSO Provider | MFA Required | Session Timeout |
|-------------|--------------|--------------|-----------------|
| **DEV** | Azure AD | Optional | 8 hours |
| **QA** | Azure AD | Required | 4 hours |
| **STAGING** | Azure AD | Required | 2 hours |
| **UAT** | Azure AD | Required | 8 hours |
| **PRE-PROD** | Azure AD + Hardware Token | Required | 1 hour |
| **PROD** | Azure AD + Hardware Token | Required | 30 minutes |

### 6.6 Service Account Management

| Account Type | Usage | Rotation Frequency |
|--------------|-------|-------------------|
| **CI/CD Service** | Deployment automation | 90 days |
| **Monitoring Service** | Metrics collection | 180 days |
| **Backup Service** | Automated backups | 90 days |
| **Database Service** | App DB connections | 90 days |
| **API Service** | Inter-service communication | 90 days |

---

## 7. Deployment Process

### 7.1 Deployment Tiers

| Tier | Environments | Deployment Type | Approval |
|------|--------------|-----------------|----------|
| **Tier 1** | DEV | Automated | None |
| **Tier 2** | QA | Automated (CI/CD) | Automated tests pass |
| **Tier 3** | STAGING | Semi-automated | QA Lead approval |
| **Tier 4** | UAT | Manual | Product Owner approval |
| **Tier 5** | PRE-PROD, PROD | Manual with checklist | CAB approval |

### 7.2 Build Promotion Pipeline

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         BUILD PROMOTION WORKFLOW                            │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│   CODE COMMIT                                                               │
│       │                                                                     │
│       ▼                                                                     │
│   ┌─────────────┐    Unit Tests    ┌─────────────┐                         │
│   │    BUILD    │─────────────────►│   ARTIFACT  │                         │
│   │   & TEST    │    Lint/Scan     │   REPOSITORY│                         │
│   └─────────────┘                  └──────┬──────┘                         │
│                                           │                                 │
│       ┌───────────────────────────────────┼───────────────────┐            │
│       │                                   │                   │            │
│       ▼                                   ▼                   ▼            │
│   ┌─────────┐                        ┌─────────┐         ┌─────────┐       │
│   │   DEV   │                        │   QA    │         │   QA    │       │
│   │  AUTO   │                        │ Nightly │         │ Smoke   │       │
│   │ Deploy  │                        │  Test   │         │  Test   │       │
│   └────┬────┘                        └────┬────┘         └────┬────┘       │
│        │                                  │                    │           │
│        │    All tests pass                │                    │           │
│        └──────────────────────────────────┴────────────────────┘           │
│                                           │                                 │
│                                           ▼                                 │
│                                    ┌─────────────┐                         │
│                                    │   STAGING   │                         │
│                                    │   Deploy    │                         │
│                                    └──────┬──────┘                         │
│                                           │                                 │
│                              System Tests │ Performance                     │
│                              Pass         │ Tests Pass                      │
│                                           ▼                                 │
│                                    ┌─────────────┐                         │
│                                    │     UAT     │                         │
│                                    │   Deploy    │                         │
│                                    └──────┬──────┘                         │
│                                           │                                 │
│                              UAT Sign-off │ Security                        │
│                                           │ Scan Pass                       │
│                                           ▼                                 │
│                                    ┌─────────────┐                         │
│                                    │  PRE-PROD   │                         │
│                                    │   Deploy    │                         │
│                                    └──────┬──────┘                         │
│                                           │                                 │
│                              Final Validation                             │
│                              DR Test Pass                                 │
│                                           │                                 │
│                                           ▼                                 │
│                                    ┌─────────────┐                         │
│                                    │   PROD      │                         │
│                                    │   Deploy    │                         │
│                                    └─────────────┘                         │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 7.3 Deployment Procedures

#### 7.3.1 Standard Deployment

```
PROCEDURE: DEP-001 - Standard Environment Deployment
──────────────────────────────────────────────────────────────────────────────

STEP 1: Pre-Deployment
────────
□ Verify deployment window availability
□ Check environment health status
□ Review deployment notes and known issues
□ Confirm rollback plan
□ Notify stakeholders

STEP 2: Pre-Deployment Checks
────────
□ Verify artifact in repository
□ Check database migration scripts
□ Validate configuration files
□ Run pre-deployment tests
□ Confirm environment capacity

STEP 3: Deployment Execution
────────
□ Place environment in maintenance mode
□ Execute database migrations
□ Deploy application code
□ Update configuration
□ Verify deployment completion

STEP 4: Post-Deployment
────────
□ Run smoke tests
□ Remove maintenance mode
□ Verify monitoring dashboards
□ Test critical user journeys
□ Notify stakeholders of completion

STEP 5: Verification
────────
□ Monitor error rates for 30 minutes
□ Check application performance
□ Verify integration points
□ Confirm logging is active
□ Update deployment log
```

#### 7.3.2 Hotfix Deployment

```
PROCEDURE: DEP-002 - Hotfix Deployment
──────────────────────────────────────────────────────────────────────────────

EMERGENCY PROTOCOL - Requires Incident Manager approval

STEP 1: Emergency Assessment
────────
□ Classify severity (P1/P2)
□ Obtain Incident Manager approval
□ Assemble hotfix team
□ Prepare emergency change request

STEP 2: Expedited Testing
────────
□ Focus testing on fix area only
□ Run critical path smoke tests
□ Security scan (expedited)
□ QA Lead sign-off

STEP 3: Deployment
────────
□ Skip standard approval gates
□ Deploy directly to affected environment
□ Monitor continuously
□ Prepare immediate rollback capability

STEP 4: Post-Hotfix
────────
□ Document deviation from standard process
□ Schedule follow-up deployment to sync other environments
□ Retrospective within 48 hours
□ Update procedures if needed
```

### 7.4 Rollback Procedures

| Deployment Type | Rollback Time | Method |
|-----------------|---------------|--------|
| **Container-based** | <5 minutes | Revert to previous image tag |
| **VM-based** | <15 minutes | Restore from snapshot |
| **Database** | <30 minutes | Restore from backup + replay logs |
| **Full Stack** | <45 minutes | Disaster recovery procedure |

---

## 8. Monitoring & Health Checks

### 8.1 Monitoring Stack

| Layer | Tool | Purpose |
|-------|------|---------|
| **Infrastructure** | Prometheus + Grafana | Resource metrics |
| **Application** | Application Insights / Datadog | APM and tracing |
| **Logs** | ELK Stack / Splunk | Centralized logging |
| **Uptime** | Pingdom / UptimeRobot | External monitoring |
| **Alerting** | PagerDuty / Opsgenie | Incident management |

### 8.2 Health Check Endpoints

| Endpoint | Purpose | Frequency | Alert Threshold |
|----------|---------|-----------|-----------------|
| `/health` | Basic liveness | 30 seconds | 3 consecutive failures |
| `/health/ready` | Readiness probe | 30 seconds | Not ready >5 min |
| `/health/deep` | Dependency check | 5 minutes | Any dependency down |
| `/health/metrics` | Performance metrics | 1 minute | SLA breach |

### 8.3 Alert Configuration

| Severity | Response Time | Notification | Escalation |
|----------|---------------|--------------|------------|
| **P1 - Critical** | 15 minutes | Phone + SMS + Email | Auto-escalate to manager |
| **P2 - High** | 1 hour | SMS + Email | Escalate after 2 hours |
| **P3 - Medium** | 4 hours | Email | Next business day |
| **P4 - Low** | 24 hours | Email | Weekly digest |

### 8.4 Environment Health Dashboards

| Dashboard | URL | Update Frequency | Audience |
|-----------|-----|------------------|----------|
| **Environment Overview** | grafana.smartdairy.local/env | Real-time | All teams |
| **QA Metrics** | grafana.smartdairy.local/qa | Real-time | QA Team |
| **Performance** | grafana.smartdairy.local/perf | 1 minute | Dev/Perf teams |
| **Uptime SLA** | grafana.smartdairy.local/sla | 5 minutes | Management |

---

## 9. Troubleshooting

### 9.1 Common Issues and Resolution

#### Issue 1: Environment Unavailable

| Symptom | Cannot access environment URL |
|---------|-------------------------------|
| **Check** | Network connectivity, DNS resolution |
| **Common Causes** | Server down, network issue, maintenance mode |
| **Resolution** | 1. Check monitoring dashboard<br>2. Verify server status<br>3. Check load balancer health<br>4. Review recent deployments |
| **Escalation** | Contact DevOps if infrastructure issue |

#### Issue 2: Slow Performance

| Symptom | Response time > threshold |
|---------|---------------------------|
| **Check** | Resource utilization, database performance |
| **Common Causes** | High CPU/memory, database locks, network latency |
| **Resolution** | 1. Check resource metrics<br>2. Review slow query log<br>3. Check for resource contention<br>4. Scale if necessary |
| **Escalation** | Contact DevOps if resource-related |

#### Issue 3: Database Connection Failures

| Symptom | Application cannot connect to database |
|---------|----------------------------------------|
| **Check** | Database service status, connection pool |
| **Common Causes** | DB down, max connections reached, credentials expired |
| **Resolution** | 1. Verify database service<br>2. Check connection pool usage<br>3. Verify credentials<br>4. Restart connection pool |
| **Escalation** | Contact DBA if database issue |

#### Issue 4: Test Data Issues

| Symptom | Tests failing due to data problems |
|---------|-------------------------------------|
| **Check** | Data freshness, data integrity |
| **Common Causes** | Stale data, incomplete refresh, data corruption |
| **Resolution** | 1. Check last refresh date<br>2. Verify data row counts<br>3. Run data validation scripts<br>4. Request data refresh |
| **Escalation** | Contact QA Lead if data management issue |

#### Issue 5: Deployment Failures

| Symptom | Deployment pipeline fails |
|---------|---------------------------|
| **Check** | Build logs, test results, artifact availability |
| **Common Causes** | Build failure, test failure, infrastructure issue |
| **Resolution** | 1. Review pipeline logs<br>2. Check artifact in repository<br>3. Verify environment capacity<br>4. Retry deployment |
| **Escalation** | Contact DevOps if infrastructure-related |

### 9.2 Troubleshooting Runbook Template

```
RUNBOOK: [Issue Name]
──────────────────────────────────────────────────────────────────────────────

SUMMARY:
Brief description of the issue and its impact.

SYMPTOMS:
- Symptom 1
- Symptom 2

IMPACT:
- Affected environments
- Affected users
- Business impact

DIAGNOSIS:
Step 1: [Check command or procedure]
Expected result: [What you should see]

Step 2: [Next check based on results]
Expected result: [What you should see]

RESOLUTION:
Option A: [If condition X]
  1. Step 1
  2. Step 2

Option B: [If condition Y]
  1. Step 1
  2. Step 2

VERIFICATION:
How to confirm the issue is resolved.

ESCALATION:
When and who to escalate to if resolution fails.

REFERENCES:
- Related runbooks
- Documentation links
```

---

## 10. Environment Booking

### 10.1 Booking System

Environment booking is managed through the Environment Booking System (EBS) accessible at `ebs.smartdairy.local`.

### 10.2 Booking Types

| Booking Type | Duration | Approval | Notice Required |
|--------------|----------|----------|-----------------|
| **Ad-hoc Testing** | <4 hours | Auto-approved | 1 hour |
| **Test Execution** | 1-3 days | QA Lead | 24 hours |
| **UAT Session** | 1-2 weeks | Product Owner | 1 week |
| **Performance Test** | 2-5 days | Performance Team | 1 week |
| **Maintenance Window** | 2-8 hours | IT Director | 1 week |

### 10.3 Booking Procedure

```
PROCEDURE: BOOK-001 - Environment Booking
──────────────────────────────────────────────────────────────────────────────

STEP 1: Check Availability
────────
□ Log in to Environment Booking System
□ Select desired environment
□ View availability calendar
□ Identify suitable time slot

STEP 2: Submit Booking
────────
□ Click "New Booking"
□ Select date/time range
□ Specify purpose and activity type
□ List team members requiring access
□ Submit booking request

STEP 3: Await Approval (if required)
────────
□ Monitor email for approval notification
□ Respond to any clarification requests
□ Receive booking confirmation

STEP 4: Pre-Booking Preparation
────────
□ Verify environment is ready 30 min before
□ Confirm data state is appropriate
□ Prepare test materials
□ Notify team of booking start

STEP 5: During Booking
────────
□ Use environment exclusively
□ Log any issues encountered
□ Do not modify configuration without approval
□ Extend booking if needed (subject to availability)

STEP 6: Post-Booking
────────
□ Release environment on time
□ Document any changes made
□ Report any issues to DevOps
□ Mark booking as complete
```

### 10.4 Booking Conflicts

| Conflict Type | Resolution |
|---------------|------------|
| **Same Priority** | First-come, first-served; negotiate sharing |
| **Different Priority** | Higher priority takes precedence |
| **Emergency Override** | Incident Manager can override any booking |
| **Recurring Conflict** | Escalate to QA Lead for schedule adjustment |

### 10.5 Booking Violations

| Violation | Consequence |
|-----------|-------------|
| No-show (>30 min) | Booking released, warning issued |
| Overrun (>15 min) | Next booking delayed, notification sent |
| Unauthorized changes | Access review, possible revocation |
| Data contamination | Mandatory refresh at user's cost |

---

## 11. Maintenance Windows

### 11.1 Scheduled Maintenance

| Environment | Window (SGT) | Frequency | Duration |
|-------------|--------------|-----------|----------|
| **DEV** | Anytime | As needed | Varies |
| **QA** | Sat 02:00-06:00 | Weekly | 4 hours |
| **STAGING** | Sun 00:00-04:00 | Bi-weekly | 4 hours |
| **UAT** | By booking | Monthly | 4 hours |
| **PRE-PROD** | Sat 22:00-02:00 | Monthly | 4 hours |
| **PROD** | Sun 02:00-06:00 | Quarterly | 4 hours |

### 11.2 Maintenance Notification

| Notice Period | Notification Method | Recipients |
|---------------|---------------------|------------|
| **1 week** | Email + Calendar invite | All stakeholders |
| **48 hours** | Email + Slack | Directly affected users |
| **4 hours** | Slack + SMS | On-call personnel |
| **15 minutes** | Slack + Page | Directly affected users |

### 11.3 Maintenance Procedure

```
PROCEDURE: MAINT-001 - Scheduled Maintenance
──────────────────────────────────────────────────────────────────────────────

PRE-MAINTENANCE (T-24 hours):
□ Send maintenance notification
□ Block environment booking system
□ Verify backup completion
□ Prepare maintenance checklist
□ Brief maintenance team

PRE-MAINTENANCE (T-1 hour):
□ Notify users of impending maintenance
□ Begin monitoring maintenance channel
□ Verify rollback capability
□ Confirm maintenance team availability

MAINTENANCE WINDOW:

STEP 1: Preparation (0-15 min)
────────
□ Place environment in maintenance mode
□ Disable automated monitoring alerts
□ Verify no active connections
□ Take pre-maintenance snapshot

STEP 2: Execution (15-180 min)
────────
□ Execute maintenance tasks per checklist
□ Document any deviations
□ Test functionality after each major change
□ Monitor system health

STEP 3: Validation (180-210 min)
────────
□ Run comprehensive smoke tests
□ Verify all services operational
□ Check monitoring and logging
□ Validate user access

STEP 4: Restoration (210-240 min)
────────
□ Remove maintenance mode
□ Re-enable monitoring alerts
□ Notify users of completion
□ Update maintenance log

POST-MAINTENANCE:
□ Monitor for 2 hours post-maintenance
□ Document lessons learned
□ Update procedures if needed
□ Close maintenance ticket
```

---

## 12. Cost Management

### 12.1 Cost Allocation

| Environment | Monthly Budget | Cost Center |
|-------------|----------------|-------------|
| **DEV** | $2,000 | Development |
| **QA** | $3,500 | Quality Assurance |
| **STAGING** | $8,000 | Infrastructure |
| **UAT** | $5,000 | Quality Assurance |
| **PRE-PROD** | $15,000 | Infrastructure |
| **Total Non-Prod** | $33,500 | Various |

### 12.2 Cost Optimization Strategies

| Strategy | Implementation | Expected Savings |
|----------|----------------|------------------|
| **Auto-shutdown** | DEV environments shutdown 19:00-08:00 | 40% |
| **Reserved Instances** | 1-year commit for stable environments | 30% |
| **Right-sizing** | Quarterly resource utilization review | 15% |
| **Spot Instances** | Use for non-critical workloads | 60% |
| **Storage Tiering** | Move old backups to cold storage | 25% |

### 12.3 Cleanup Procedures

```
PROCEDURE: COST-001 - Resource Cleanup
──────────────────────────────────────────────────────────────────────────────

WEEKLY CLEANUP (Automated):
□ Delete temporary files older than 7 days
□ Purge old container images (keep last 10)
□ Archive logs older than 30 days
□ Remove unused snapshots

MONTHLY CLEANUP (Manual Review):
□ Review unattached storage volumes
□ Identify idle resources
□ Archive obsolete data
□ Update cost allocation tags

QUARTERLY CLEANUP (Full Audit):
□ Resource utilization analysis
□ Right-sizing recommendations
□ Environment consolidation review
□ Budget vs actual analysis
```

---

## 13. Security

### 13.1 Security Hardening

| Control | Implementation | Verification |
|---------|----------------|--------------|
| **OS Hardening** | CIS Benchmark Level 1 | Quarterly scan |
| **Network Segmentation** | VPC isolation, security groups | Continuous |
| **Encryption at Rest** | AES-256 for all storage | Weekly audit |
| **Encryption in Transit** | TLS 1.3 minimum | Continuous |
| **Patch Management** | Automated patching (critical within 48h) | Weekly |
| **Vulnerability Scanning** | Weekly automated scans | Weekly |

### 13.2 Patch Management

| Patch Type | DEV | QA | STAGING | UAT | PRE-PROD |
|------------|-----|-----|---------|-----|----------|
| **Critical Security** | Immediate | 24 hours | 48 hours | 1 week | 1 week |
| **High Security** | 48 hours | 1 week | 2 weeks | 1 month | 1 month |
| **Medium/Low** | Next cycle | Next cycle | Next cycle | Next cycle | Next cycle |
| **Feature Updates** | Immediate | Next cycle | Next cycle | Next release | Next release |

### 13.3 Compliance Requirements

| Standard | Requirement | Verification |
|----------|-------------|--------------|
| **ISO 27001** | Access control, audit logging | Annual audit |
| **PDPA** | Data protection, masking | Quarterly review |
| **SOC 2** | Security controls | Annual audit |
| **PCI DSS** | Payment data protection | Annual audit |

---

## 14. Documentation

### 14.1 Documentation Requirements

| Document Type | Owner | Update Frequency | Location |
|---------------|-------|------------------|----------|
| **Environment Inventory** | DevOps | Real-time | CMDB |
| **Runbooks** | DevOps | As needed | Confluence |
| **Configuration Docs** | DevOps | Per change | Git |
| **Access Lists** | QA Lead | Quarterly | SharePoint |
| **Change Log** | Automatic | Real-time | System |

### 14.2 Runbook Catalog

| Runbook ID | Title | Purpose |
|------------|-------|---------|
| **RUN-001** | Environment Provisioning | Create new environment |
| **RUN-002** | Data Refresh | Refresh environment data |
| **RUN-003** | Access Grant | Grant user access |
| **RUN-004** | Incident Response | Handle environment incidents |
| **RUN-005** | Deployment | Deploy to environment |
| **RUN-006** | Backup/Restore | Manage backups |

---

## 15. Vendor Management

### 15.1 Third-Party Services

| Service | Vendor | Purpose | Environment |
|---------|--------|---------|-------------|
| **Cloud Infrastructure** | AWS / Azure | Compute, storage | All |
| **Monitoring** | Datadog | APM and monitoring | All |
| **SSO** | Azure AD | Identity management | All |
| **Secrets** | HashiCorp Vault | Secret management | STAGING+ |
| **CDN** | CloudFlare | Content delivery | PROD |
| **Backup** | Veeam / Native | Data protection | All |

### 15.2 Vendor SLAs

| Vendor | Service | SLA | Escalation Contact |
|--------|---------|-----|-------------------|
| AWS | EC2 | 99.99% | Enterprise Support |
| Azure | VMs | 99.99% | Premier Support |
| Datadog | Monitoring | 99.9% | Technical Account Manager |
| CloudFlare | CDN | 100% | Enterprise Support |

---

## 16. Disaster Recovery

### 16.1 Backup Strategy

| Data Type | Frequency | Retention | Location |
|-----------|-----------|-----------|----------|
| **Database** | Daily full, hourly incremental | 30 days | Cross-region |
| **File Storage** | Continuous sync | 90 days | Cross-region |
| **Configuration** | On change | 1 year | Git + Object storage |
| **Logs** | Real-time streaming | 1 year | Centralized log store |

### 16.2 Recovery Objectives

| Environment | RTO (Recovery Time) | RPO (Data Loss) |
|-------------|---------------------|-----------------|
| **DEV** | 4 hours | 24 hours |
| **QA** | 4 hours | 24 hours |
| **STAGING** | 2 hours | 12 hours |
| **UAT** | 4 hours | 24 hours |
| **PRE-PROD** | 1 hour | 1 hour |
| **PROD** | 15 minutes | 5 minutes |

### 16.3 Recovery Procedures

```
PROCEDURE: DR-001 - Environment Recovery
──────────────────────────────────────────────────────────────────────────────

STEP 1: Assessment
────────
□ Identify scope of failure
□ Determine recovery method (failover/restore/rebuild)
□ Assess data loss impact
□ Notify stakeholders

STEP 2: Recovery Execution
────────
□ Initiate recovery procedure based on failure type:
  
  Option A - Database Failure:
  1. Identify latest consistent backup
  2. Restore database from backup
  3. Apply transaction logs to meet RPO
  4. Verify data integrity
  
  Option B - Server Failure:
  1. Launch replacement instance from template
  2. Restore configuration from backup
  3. Reattach data volumes
  4. Update load balancer
  
  Option C - Complete Environment Loss:
  1. Execute full environment rebuild
  2. Restore all components
  3. Verify integration points
  4. Conduct smoke tests

STEP 3: Validation
────────
□ Verify all services operational
□ Run comprehensive tests
□ Confirm data integrity
□ Validate user access

STEP 4: Restoration
────────
□ Return to normal operations
□ Update DNS/load balancer
□ Notify users of availability
□ Document incident details

STEP 5: Post-Recovery
────────
□ Conduct root cause analysis
□ Identify improvement opportunities
□ Update DR procedures
□ Schedule DR test
```

### 16.4 DR Testing Schedule

| Test Type | Frequency | Scope | Participants |
|-----------|-----------|-------|--------------|
| **Tabletop** | Quarterly | Process walkthrough | DR Team |
| **Component** | Bi-annually | Single system recovery | DevOps |
| **Full DR** | Annually | Complete failover | All teams |

---

## 17. Appendices

### Appendix A: Environment Inventory

| Environment ID | Type | Status | Owner | Created | Last Refresh |
|----------------|------|--------|-------|---------|--------------|
| DEV-001 | Development | Active | Dev Team | 2026-01-15 | On-demand |
| DEV-002 | Development | Active | Dev Team | 2026-01-15 | On-demand |
| QA-001 | QA | Active | QA Team | 2026-01-10 | 2026-01-31 |
| QA-002 | QA | Active | QA Team | 2026-01-10 | 2026-01-31 |
| STG-001 | Staging | Active | DevOps | 2026-01-05 | 2026-01-26 |
| UAT-001 | UAT | Active | BA Team | 2026-01-20 | 2026-01-25 |
| PRE-001 | Pre-Prod | Active | DevOps | 2026-01-01 | 2026-01-15 |

### Appendix B: Configuration Templates

#### B.1 Terraform Variables Template

```hcl
# environment.tfvars

environment_name = "qa-01"
environment_tier = "qa"

# Compute
web_server_count = 2
web_server_type  = "t3.large"
api_server_count = 2
api_server_type  = "t3.large"

# Database
db_instance_class = "db.t3.medium"
db_storage_gb     = 200
db_multi_az       = false

# Network
vpc_cidr = "10.1.0.0/16"
public_subnet_cidrs  = ["10.1.1.0/24", "10.1.2.0/24"]
private_subnet_cidrs = ["10.1.10.0/24", "10.1.20.0/24"]

# Features
enable_monitoring = true
enable_auto_scaling = false
enable_encryption = true
```

#### B.2 Ansible Inventory Template

```ini
# inventory/qa.ini
[web_servers]
qa-web-[01:02].smartdairy.local

[api_servers]
qa-api-[01:02].smartdairy.local

[database_servers]
qa-db-01.smartdairy.local

[all:vars]
env_name=qa
env_tier=2
monitoring_enabled=true
backup_enabled=true
```

### Appendix C: Environment Request Form

```
═══════════════════════════════════════════════════════════════════════════════
                    SMART DAIRY ENVIRONMENT REQUEST FORM
═══════════════════════════════════════════════════════════════════════════════

Request ID: ENV-REQ-[YYYY]-[NNNN]          Date: _______________

REQUESTOR INFORMATION:
────────────────────────────────────────────────────────────────────────────────
Name: _________________________________    Department: ___________________
Email: ________________________________    Phone: ________________________
Manager: ______________________________

ENVIRONMENT DETAILS:
────────────────────────────────────────────────────────────────────────────────
Environment Type:  [ ] DEV    [ ] QA    [ ] STAGING    [ ] UAT    [ ] Other
Environment Name: _______________________________
Requested Start Date: _______________    Duration: _______ days/weeks

PURPOSE:
────────────────────────────────────────────────────────────────────────────────
□ New project initialization
□ Feature development
□ Testing (specify type): ________________________________
□ Bug reproduction
□ Performance testing
□ Training
□ Other: ________________________________________________

Justification/Description:
_______________________________________________________________________________
_______________________________________________________________________________
_______________________________________________________________________________

RESOURCE REQUIREMENTS:
────────────────────────────────────────────────────────────────────────────────
Compute:    [ ] Use standard spec    [ ] Custom: vCPU: _____  RAM: _____ GB
Storage:    [ ] Use standard spec    [ ] Custom: _____ GB
Database:   [ ] None    [ ] Shared    [ ] Dedicated    [ ] Restore from: ______
Data Set:   [ ] Synthetic    [ ] Masked subset    [ ] Masked full    [ ] Empty

SPECIAL REQUIREMENTS:
────────────────────────────────────────────────────────────────────────────────
□ External network access
□ Third-party integration
□ Specific software installation: ____________________________________________
□ SSL certificate
□ VPN access
□ Other: ____________________________________________________________________

APPROVALS:
────────────────────────────────────────────────────────────────────────────────
Requestor Signature: _________________________    Date: _______________

Manager Approval:
Name: _________________________    Signature: _________________________
Date: _______________    [ ] Approved    [ ] Rejected

QA Lead Approval:
Name: _________________________    Signature: _________________________
Date: _______________    [ ] Approved    [ ] Rejected

IT Director Approval (for STAGING+):
Name: _________________________    Signature: _________________________
Date: _______________    [ ] Approved    [ ] Rejected

FOR DEVOPS USE ONLY:
────────────────────────────────────────────────────────────────────────────────
Environment ID Assigned: _______________________________
Provisioning Date: _______________    Completed By: ___________________
Handover Date: _______________    Handed Over To: ___________________
Notes: _______________________________________________________________________
_______________________________________________________________________________
═══════════════════════════════════════════════════════════════════════════════
```

### Appendix D: Environment Access Request Form

```
═══════════════════════════════════════════════════════════════════════════════
                 SMART DAIRY ENVIRONMENT ACCESS REQUEST FORM
═══════════════════════════════════════════════════════════════════════════════

Request ID: ACC-REQ-[YYYY]-[NNNN]          Date: _______________

EMPLOYEE INFORMATION:
────────────────────────────────────────────────────────────────────────────────
Name: _________________________________    Employee ID: ___________________
Email: ________________________________    Department: ___________________
Manager: ______________________________    Role: __________________________

ACCESS REQUESTED:
────────────────────────────────────────────────────────────────────────────────
| Environment | Current Access | Requested Access | Duration    | Business Need |
|-------------|----------------|------------------|-------------|---------------|
| DEV         | [ ] None       | [ ] Read [ ] Full| [ ] Permanent|               |
|             | [ ] Read       |                  | [ ] Temporary|               |
|             | [ ] Full       | Until: __________|             |               |
|-------------|----------------|------------------|-------------|---------------|
| QA          | [ ] None       | [ ] Read [ ] Full| [ ] Permanent|               |
|             | [ ] Read       |                  | [ ] Temporary|               |
|             | [ ] Full       | Until: __________|             |               |
|-------------|----------------|------------------|-------------|---------------|
| STAGING     | [ ] None       | [ ] Read [ ] Full| [ ] Permanent|               |
|             | [ ] Read       |                  | [ ] Temporary|               |
|             | [ ] Full       | Until: __________|             |               |
|-------------|----------------|------------------|-------------|---------------|
| UAT         | [ ] None       | [ ] Read [ ] Full| [ ] Permanent|               |
|             | [ ] Read       |                  | [ ] Temporary|               |
|             | [ ] Full       | Until: __________|             |               |
|-------------|----------------|------------------|-------------|---------------|
| PRE-PROD    | [ ] None       | [ ] Read [ ] Full| [ ] Permanent|               |
|             | [ ] Read       |                  | [ ] Temporary|               |
|             | [ ] Full       | Until: __________|             |               |

JUSTIFICATION:
────────────────────────────────────────────────────────────────────────────────
Please describe the business need for this access:
_______________________________________________________________________________
_______________________________________________________________________________
_______________________________________________________________________________

Project/Activity: ____________________________________________________________

SECURITY ACKNOWLEDGMENT:
────────────────────────────────────────────────────────────────────────────────
I understand and agree to:
□ Use access only for authorized business purposes
□ Not share credentials with any other person
□ Report any suspected security incidents immediately
□ Comply with all data handling policies
□ Complete required security training

Signature: _________________________    Date: _______________

APPROVALS:
────────────────────────────────────────────────────────────────────────────────
Manager Approval:
Name: _________________________    Signature: _________________________
Date: _______________    [ ] Approved    [ ] Rejected

QA Lead Approval (for QA, STAGING, UAT):
Name: _________________________    Signature: _________________________
Date: _______________    [ ] Approved    [ ] Rejected

IT Director Approval (for PRE-PROD):
Name: _________________________    Signature: _________________________
Date: _______________    [ ] Approved    [ ] Rejected

PROVISIONING:
────────────────────────────────────────────────────────────────────────────────
Access Granted Date: _______________    By: _______________________________
Account Created: [ ] Yes    [ ] Existing account modified
MFA Enabled: [ ] Yes    [ ] N/A
Access Expiration (if temporary): _______________

Notes: _______________________________________________________________________
═══════════════════════════════════════════════════════════════════════════════
```

### Appendix E: Incident Response Procedures

#### E.1 Incident Classification

| Severity | Definition | Examples | Response Time |
|----------|------------|----------|---------------|
| **P1 - Critical** | Complete environment outage, data loss | All environments down, database corruption | 15 minutes |
| **P2 - High** | Major functionality impaired, single environment down | QA environment down during release testing | 1 hour |
| **P3 - Medium** | Partial functionality impaired | Performance degradation, non-critical feature failure | 4 hours |
| **P4 - Low** | Minor issue, workaround available | UI glitch, documentation error | 24 hours |

#### E.2 Incident Response Procedure

```
PROCEDURE: INC-001 - Environment Incident Response
──────────────────────────────────────────────────────────────────────────────

STEP 1: Detection and Logging
────────
□ Incident detected (automated alert or manual report)
□ Log incident in ticketing system
□ Assign incident ID
□ Capture initial symptoms and timestamp

STEP 2: Triage (within 15 minutes for P1/P2)
────────
□ Classify incident severity
□ Identify affected environment(s)
□ Assess business impact
□ Determine if rollback is needed

STEP 3: Response Team Assembly
────────
□ Notify on-call engineer
□ For P1/P2: Page incident commander
□ Assemble war room (virtual or physical)
□ Establish communication channel

STEP 4: Investigation
────────
□ Gather relevant logs and metrics
□ Identify root cause
□ Determine if related to recent changes
□ Document findings in real-time

STEP 5: Resolution
────────
□ Implement fix or workaround
□ Apply rollback if necessary
□ Verify resolution
□ Monitor for stability

STEP 6: Communication
────────
□ Post incident status updates every 30 min (P1) / 1 hour (P2)
□ Notify stakeholders of resolution
□ Prepare incident summary

STEP 7: Post-Incident
────────
□ Conduct post-mortem within 48 hours (P1/P2) / 1 week (P3/P4)
□ Document lessons learned
□ Create action items to prevent recurrence
□ Update procedures if needed
```

#### E.3 Escalation Matrix

| Time Elapsed | P1 Action | P2 Action | P3 Action |
|--------------|-----------|-----------|-----------|
| **15 min** | Page on-call | Notify on-call | Create ticket |
| **30 min** | Page manager | Page on-call | - |
| **1 hour** | Page director | Page manager | Escalate to senior |
| **2 hours** | Executive notification | Director notification | Manager notification |

### Appendix F: Contact Information

| Role | Name | Email | Phone | Escalation |
|------|------|-------|-------|------------|
| **QA Lead** | | | | IT Director |
| **DevOps Lead** | | | | IT Director |
| **IT Director** | | | | CTO |
| **On-Call Engineer** | | | | DevOps Lead |
| **Security Team** | | | | CISO |
| **Database Admin** | | | | DevOps Lead |

### Appendix G: Glossary

| Term | Definition |
|------|------------|
| **CI/CD** | Continuous Integration / Continuous Deployment |
| **CMDB** | Configuration Management Database |
| **DR** | Disaster Recovery |
| **EBS** | Environment Booking System |
| **MFA** | Multi-Factor Authentication |
| **PII** | Personally Identifiable Information |
| **RPO** | Recovery Point Objective |
| **RTO** | Recovery Time Objective |
| **SDLC** | Software Development Life Cycle |
| **SSO** | Single Sign-On |
| **UAT** | User Acceptance Testing |
| **VPC** | Virtual Private Cloud |

---

## Document Approval

This document has been reviewed and approved by the following:

| Role | Name | Signature | Date |
|------|------|-----------|------|
| QA Lead | | | |
| IT Director | | | |
| Project Manager | | | |
| DevOps Lead | | | |

---

*Document Classification: Internal Use*

*Next Review Date: July 31, 2026*

---
