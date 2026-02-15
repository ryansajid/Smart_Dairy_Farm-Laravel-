# Milestone 40 - Deployment & Go-Live

## Smart Dairy Smart Portal + ERP System Implementation

---

**Document Version:** 1.0  
**Phase:** Phase 4 - Production Deployment  
**Milestone:** 40 (Final Milestone)  
**Duration:** Days 391-400 (10 Days)  
**Date:** February 2026  

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Deployment Architecture](#2-deployment-architecture)
3. [Daily Task Allocation](#3-daily-task-allocation)
4. [Pre-Deployment Checklist](#4-pre-deployment-checklist)
5. [Deployment Procedures](#5-deployment-procedures)
6. [Data Migration Plan](#6-data-migration-plan)
7. [Training Plan](#7-training-plan)
8. [Go-Live Execution](#8-go-live-execution)
9. [Post Go-Live Support](#9-post-go-live-support)
10. [Project Handover](#10-project-handover)
11. [Appendices](#11-appendices)

---

## 1. Executive Summary

### 1.1 Deployment Objectives

The Deployment & Go-Live milestone represents the culmination of 400 days of development, testing, and preparation for the Smart Dairy Smart Portal + ERP System. This milestone encompasses the final activities required to transition the system from a development/testing environment to full production readiness.

**Primary Objectives:**

1. **Production Environment Establishment:** Configure and validate a robust, scalable, and secure production infrastructure capable of supporting Smart Dairy's operational requirements.

2. **Zero-Downtime Deployment:** Execute a seamless deployment strategy that minimizes or eliminates service interruption.

3. **Complete Data Migration:** Transfer all historical data, master records, and transactional information with 100% accuracy.

4. **User Empowerment:** Deliver comprehensive training programs for all user categories.

5. **Operational Readiness:** Establish monitoring, alerting, and support mechanisms.

6. **Risk Mitigation:** Implement comprehensive rollback procedures and contingency plans.

### 1.2 Go-Live Strategy

The Smart Dairy go-live strategy follows a Phased Deployment with Parallel Running approach:

**Phase 1: Infrastructure (Days 391-392)**
- Production servers provisioned and hardened
- Network connectivity established
- Security controls implemented
- Monitoring and alerting activated

**Phase 2: Data Preparation (Days 392-394)**
- Master data validation and cleansing
- Historical data extraction and transformation
- Test migrations completed
- Data quality verification

**Phase 3: Application Deployment (Days 394-396)**
- Backend services deployed
- Frontend applications deployed
- Database schema migrated
- Configuration activated

**Phase 4: User Training (Days 395-398)**
- Administrator training completed
- End-user training sessions delivered
- Training materials distributed
- User competency assessments

**Phase 5: Go-Live Execution (Days 398-400)**
- Final data synchronization
- Production cutover
- Smoke testing completed
- Hypercare support activated

**Phase 6: Stabilization (Days 400+)**
- Performance monitoring
- Issue resolution
- Optimization
- Project handover

### 1.3 Success Criteria

**Technical Success Criteria:**

| Criterion | Target | Measurement |
|-----------|--------|-------------|
| System Availability | 99.9% | First 7 days post go-live |
| Login Response Time | Less than 2 seconds | 95th percentile |
| Dashboard Load Time | Less than 3 seconds | 95th percentile |
| Transaction Processing | Less than 5 seconds | 95th percentile |
| Data Integrity | 100% | Zero data loss |
| Error Rate | Less than 0.1% | Transaction failure rate |
| Security Incidents | Zero | Unauthorized access attempts |

**Operational Success Criteria:**

| Criterion | Target | Measurement |
|-----------|--------|-------------|
| User Adoption | 95% | Login within 48 hours |
| Training Completion | 100% | Critical users only |
| Issue Resolution | 90% | Within 4 hours during hypercare |
| Business Continuity | 100% | No manual workarounds |

### 1.4 Rollback Plan

**Rollback Triggers:**

| Severity | Trigger Condition | Action |
|----------|-------------------|--------|
| Critical | Complete system unavailability greater than 30 minutes | Immediate full rollback |
| Critical | Data corruption affecting more than 5% of records | Immediate full rollback |
| High | Core functionality failure (milk collection, payments) | Evaluate partial rollback |
| High | Security breach or unauthorized access | Immediate security rollback |
| Medium | Performance degradation greater than 200% baseline | Evaluate optimization vs rollback |

**Rollback Time:** Maximum 75 minutes from decision to full restoration

---

## 2. Deployment Architecture

### 2.1 Production Architecture Overview

The Smart Dairy production environment is designed as a high-availability, horizontally scalable architecture deployed across multiple availability zones.

**Architecture Components:**

1. **CDN Layer:** CloudFront for static asset delivery
2. **Web Tier:** Nginx servers with auto-scaling
3. **Application Tier:** Node.js microservices with load balancing
4. **Data Tier:** PostgreSQL with Multi-AZ deployment
5. **Cache Tier:** Redis cluster for session and data caching
6. **Message Queue:** RabbitMQ for background jobs
7. **Storage Layer:** S3 for documents, EBS for application data

### 2.2 Infrastructure Specifications

**Compute Resources:**

| Component | Specification | Quantity | Purpose |
|-----------|--------------|----------|---------|
| Frontend Servers | t3.large (2 vCPU, 8GB RAM) | 4 | React application hosting |
| API Servers | c5.xlarge (4 vCPU, 8GB RAM) | 6 | Node.js backend services |
| Worker Servers | c5.large (2 vCPU, 4GB RAM) | 4 | Background job processing |
| Load Balancer | Application Load Balancer | 2 | Traffic distribution |

**Data Tier:**

| Component | Specification | Quantity | Purpose |
|-----------|--------------|----------|---------|
| Primary Database | db.r5.2xlarge | 2 | PostgreSQL Multi-AZ |
| Read Replica | db.r5.xlarge | 4 | Reporting and analytics |
| Cache Cluster | cache.r5.large | 6 | Redis cluster |
| Search Engine | Elasticsearch | 3 nodes | Full-text search |

**Storage Specifications:**

| Storage Type | Size | Purpose |
|-------------|------|---------|
| Database Storage (Primary) | 500GB SSD | Transactional data |
| Application Logs | 2TB | Centralized logging |
| Document Storage | 5TB | S3 for files |
| Backup Storage | 10TB | Automated backups |

### 2.3 Network Configuration

**VPC Architecture:**
- VPC CIDR: 10.0.0.0/16
- Public Subnets: 10.0.1.0/24, 10.0.2.0/24, 10.0.3.0/24
- Private App Subnets: 10.0.10.0/24, 10.0.11.0/24, 10.0.12.0/24
- Private Data Subnets: 10.0.20.0/24, 10.0.21.0/24, 10.0.22.0/24

**Security Groups:**

```yaml
web-tier-sg:
  inbound:
    - port: 80, 443 from alb-sg
    - port: 22 from bastion-sg
  outbound: all

app-tier-sg:
  inbound:
    - port: 3000 from web-tier-sg
    - port: 22 from bastion-sg
  outbound:
    - port: 5432 to db-tier-sg
    - port: 6379 to cache-tier-sg
    - port: 443 to internet

db-tier-sg:
  inbound:
    - port: 5432 from app-tier-sg
  outbound: none
```

### 2.4 Security Hardening

**Server Hardening Checklist:**

1. Apply all system updates and security patches
2. Disable root login via SSH
3. Enable key-based SSH authentication only
4. Configure UFW firewall with deny-all default
5. Install and configure Fail2Ban
6. Apply kernel hardening parameters
7. Remove unnecessary services
8. Configure file permissions
9. Install and configure auditd
10. Enable automatic security updates

**Database Security:**

```sql
-- Create dedicated application user
CREATE ROLE smart_dairy_app WITH LOGIN PASSWORD 'StrongRandomPassword123!';
GRANT CONNECT ON DATABASE smart_dairy_prod TO smart_dairy_app;
GRANT USAGE ON SCHEMA public TO smart_dairy_app;
GRANT SELECT, INSERT, UPDATE ON ALL TABLES IN SCHEMA public TO smart_dairy_app;
REVOKE DELETE ON ALL TABLES IN SCHEMA public FROM smart_dairy_app;

-- Enable SSL connections
-- Update postgresql.conf:
-- ssl = on
-- ssl_cert_file = 'server.crt'
-- ssl_key_file = 'server.key'

-- Configure pg_hba.conf for secure access
-- hostssl smart_dairy_prod smart_dairy_app 10.0.10.0/24 scram-sha-256
```

### 2.5 Monitoring Setup

**Key Metrics to Monitor:**

| Category | Metric | Warning Threshold | Critical Threshold |
|----------|--------|-------------------|-------------------|
| Infrastructure | CPU Usage | Greater than 70% for 5 min | Greater than 90% for 2 min |
| Infrastructure | Memory Usage | Greater than 80% for 5 min | Greater than 95% for 2 min |
| Application | Response Time (p95) | Greater than 500ms | Greater than 1000ms |
| Application | Error Rate | Greater than 1% | Greater than 5% |
| Database | Connection Pool | Greater than 70% | Greater than 90% |
| Database | Query Duration (p99) | Greater than 100ms | Greater than 500ms |

**Alert Rules:**

```yaml
groups:
  - name: infrastructure
    rules:
      - alert: HighCPUUsage
        expr: 100 - (avg by(instance) (irate(node_cpu_seconds_total{mode="idle"}[5m])) * 100) > 80
        for: 5m
        labels:
          severity: warning
        annotations:
          summary: "High CPU usage on {{ $labels.instance }}"
      
      - alert: ServiceDown
        expr: up == 0
        for: 1m
        labels:
          severity: critical
        annotations:
          summary: "Service {{ $labels.job }} is down"
```

---

## 3. Daily Task Allocation

### Days 391-400 - Detailed Breakdown

#### Developer 1 (Lead Dev - DevOps/Deployment)

**Day 391 - Infrastructure Provisioning**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-09:00 | Infrastructure Review | Review infrastructure architecture | Infrastructure checklist |
| 09:00-10:30 | VPC Setup | Create VPC, subnets, route tables, IGW | VPC configuration complete |
| 10:30-12:00 | Security Groups | Configure security groups, NACLs | Security rules applied |
| 13:00-15:00 | Server Provisioning | Provision EC2 instances for all tiers | Servers online |
| 15:00-16:30 | Load Balancer Setup | Configure ALB, target groups, health checks | Load balancer active |
| 16:30-18:00 | Bastion Host Setup | Configure bastion host, SSH keys | Secure access established |

**Day 392 - Database and Cache Setup**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-10:00 | RDS Setup | Provision PostgreSQL primary and replicas | Database cluster ready |
| 10:00-11:30 | Redis Setup | Configure Redis cluster, ElastiCache | Cache layer ready |
| 11:30-13:00 | Elasticsearch Setup | Deploy search cluster | Search service ready |
| 14:00-15:30 | Server Hardening | Apply security hardening scripts | Servers hardened |
| 15:30-17:00 | SSL Certificates | Provision and install SSL certificates | HTTPS enabled |
| 17:00-18:00 | Network Testing | Verify connectivity, latency tests | Network validated |

**Day 393 - Deployment Pipeline Setup**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-10:00 | CI/CD Pipeline | Configure Jenkins/GitLab CI pipelines | Pipeline configured |
| 10:00-11:30 | Artifact Repository | Setup Nexus/Artifactory for artifacts | Repository ready |
| 11:30-13:00 | Container Registry | Configure ECR, image scanning | Registry configured |
| 14:00-15:30 | Deployment Scripts | Create automated deployment scripts | Scripts tested |
| 15:30-17:00 | Blue-Green Setup | Configure blue-green deployment strategy | Deployment strategy ready |
| 17:00-18:00 | Pipeline Testing | Test full deployment pipeline | Pipeline validated |

**Day 394 - Monitoring and Alerting**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-10:00 | Prometheus Setup | Deploy Prometheus, configure scraping | Monitoring active |
| 10:00-11:30 | Grafana Setup | Install Grafana, import dashboards | Dashboards ready |
| 11:30-13:00 | Alert Manager | Configure Alertmanager, routing rules | Alerting configured |
| 14:00-15:30 | Log Aggregation | Setup ELK stack for centralized logging | Logging active |
| 15:30-17:00 | Custom Dashboards | Create Smart Dairy specific dashboards | Business dashboards |
| 17:00-18:00 | Alert Testing | Test all alert scenarios | Alerts verified |

**Day 395 - Backup and Recovery**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-10:00 | Backup Strategy | Configure automated database backups | Backups scheduled |
| 10:00-11:30 | Snapshot Setup | Configure EBS and RDS snapshots | Snapshots enabled |
| 11:30-13:00 | Backup Testing | Test backup restoration process | Restore validated |
| 14:00-15:30 | Disaster Recovery | Document and test DR procedures | DR plan ready |
| 15:30-17:00 | Cross-Region Setup | Configure cross-region replication | Multi-region ready |
| 17:00-18:00 | Recovery Testing | Execute DR drill | DR tested |

**Day 396 - Performance Tuning**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-10:00 | Load Testing Setup | Configure load testing environment | Load test ready |
| 10:00-12:00 | Initial Load Test | Execute baseline load test | Baseline metrics |
| 13:00-15:00 | Database Tuning | Optimize PostgreSQL configuration | DB optimized |
| 15:00-16:30 | Application Tuning | Tune Node.js and Nginx settings | App optimized |
| 16:30-18:00 | Cache Optimization | Configure Redis caching strategies | Cache optimized |

**Day 397 - Security Finalization**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-10:00 | WAF Configuration | Configure AWS WAF rules | WAF active |
| 10:00-11:30 | Security Scanning | Run vulnerability scans | Scan reports |
| 11:30-13:00 | Penetration Testing | Execute security penetration tests | Pen-test report |
| 14:00-15:30 | Remediation | Fix any security findings | Issues resolved |
| 15:30-17:00 | Compliance Check | Verify compliance requirements | Compliance verified |
| 17:00-18:00 | Security Sign-off | Obtain security approval | Security approved |

**Day 398 - Pre-Deployment Validation**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-10:00 | Environment Check | Full environment health check | Health report |
| 10:00-11:30 | Smoke Tests | Execute environment smoke tests | Smoke tests passed |
| 11:30-13:00 | Integration Tests | Validate all integrations | Integrations verified |
| 14:00-15:30 | Final Review | Review all infrastructure components | Review complete |
| 15:30-17:00 | Issue Resolution | Address any discovered issues | Issues resolved |
| 17:00-18:00 | Go/No-Go Decision | Make deployment go/no-go decision | Decision documented |

**Day 399 - Deployment Execution**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 06:00-06:30 | Pre-Deployment Check | Final system checks | Checklist complete |
| 06:30-07:30 | Database Migration | Execute production database migration | Migration complete |
| 07:30-09:00 | Application Deployment | Deploy all application components | Deployment complete |
| 09:00-10:00 | Configuration Activation | Activate production configurations | Config active |
| 10:00-11:00 | Smoke Testing | Execute post-deployment smoke tests | Smoke tests passed |
| 11:00-12:00 | Integration Validation | Validate all system integrations | Validations passed |
| 13:00-14:00 | Performance Check | Verify system performance | Performance OK |
| 14:00-15:00 | Security Validation | Verify security controls | Security OK |
| 15:00-16:00 | User Access Setup | Configure user accounts and permissions | Users configured |
| 16:00-17:00 | Go-Live Communication | Send go-live communications | Users notified |
| 17:00-18:00 | Hypercare Handover | Transition to hypercare team | Hypercare active |

**Day 400 - Post Go-Live Support**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 00:00-08:00 | Night Watch | Monitor overnight operations | Night report |
| 08:00-10:00 | Morning Review | Review overnight metrics and issues | Morning report |
| 10:00-12:00 | Issue Resolution | Address any overnight issues | Issues resolved |
| 13:00-15:00 | Performance Monitoring | Monitor and optimize performance | Performance stable |
| 15:00-17:00 | Documentation Update | Update all operational documentation | Docs updated |
| 17:00-18:00 | Handover | Transition to operations team | Handover complete |



#### Developer 2 (Backend Dev - Data & Config)

**Day 391 - Data Preparation Planning**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-09:30 | Migration Strategy Review | Review data migration approach | Strategy confirmed |
| 09:30-11:00 | Source Data Analysis | Analyze legacy data sources | Data inventory |
| 11:00-12:30 | Data Mapping | Create detailed field mappings | Mapping document |
| 14:00-15:30 | Migration Scripts | Develop migration scripts | Scripts ready |
| 15:30-17:00 | Validation Rules | Define data validation rules | Validation criteria |
| 17:00-18:00 | Test Plan | Create migration testing plan | Test plan complete |

**Day 392 - Master Data Extraction**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-10:00 | Farmer Data Export | Extract farmer master data | Farmer export complete |
| 10:00-11:30 | Collection Center Export | Extract collection center data | Center export complete |
| 11:30-13:00 | Product Export | Extract product catalog data | Product export complete |
| 14:00-15:30 | Account Export | Extract chart of accounts | Account export complete |
| 15:30-17:00 | Price List Export | Extract pricing data | Price export complete |
| 17:00-18:00 | Data Quality Check | Validate exported data quality | Quality report |

**Day 393 - Historical Data Preparation**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-10:00 | Collection History | Extract milk collection history | Collections exported |
| 10:00-11:30 | Payment History | Extract payment records | Payments exported |
| 11:30-13:00 | Quality Data | Extract quality test records | Quality data exported |
| 14:00-15:30 | Inventory History | Extract inventory transactions | Inventory exported |
| 15:30-17:00 | Transaction Data | Extract financial transactions | Transactions exported |
| 17:00-18:00 | Reconciliation | Reconcile extracted data totals | Reconciliation complete |

**Day 394 - Data Transformation**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-10:00 | Data Cleansing | Clean and standardize data | Data cleansed |
| 10:00-11:30 | Format Conversion | Convert to target format | Format converted |
| 11:30-13:00 | Data Enrichment | Add missing values, defaults | Data enriched |
| 14:00-15:30 | Relationship Mapping | Map foreign key relationships | Relationships mapped |
| 15:30-17:00 | Transformation Testing | Test transformation logic | Tests passed |
| 17:00-18:00 | Validation | Validate transformed data | Validation complete |

**Day 395 - Test Migration Execution**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-09:30 | Staging Setup | Prepare staging environment | Staging ready |
| 09:30-11:00 | Master Data Load | Load master data to staging | Master data loaded |
| 11:00-12:30 | Transactional Data Load | Load transactional data | Transactional data loaded |
| 14:00-15:30 | Data Validation | Validate loaded data accuracy | Validation complete |
| 15:30-17:00 | Reconciliation | Reconcile source vs target | Reconciliation passed |
| 17:00-18:00 | Issue Resolution | Fix any migration issues | Issues resolved |

**Day 396 - System Configuration**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-10:00 | Organization Setup | Configure organization structure | Org configured |
| 10:00-11:30 | User Configuration | Create user accounts and roles | Users created |
| 11:30-13:00 | Permission Setup | Configure role permissions | Permissions set |
| 14:00-15:30 | System Parameters | Configure system-wide parameters | Parameters set |
| 15:30-17:00 | Integration Config | Configure external integrations | Integrations configured |
| 17:00-18:00 | Configuration Testing | Test all configurations | Config tested |

**Day 397 - Integration Validation**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-10:00 | SMS Gateway Test | Test SMS notification integration | SMS working |
| 10:00-11:30 | Payment Gateway Test | Test payment processing | Payments working |
| 11:30-13:00 | Bank Integration Test | Test bank reconciliation | Bank integration OK |
| 14:00-15:30 | Reporting Test | Test report generation | Reports working |
| 15:30-17:00 | API Testing | Test all API endpoints | APIs validated |
| 17:00-18:00 | End-to-End Test | Execute full workflow tests | E2E tests passed |

**Day 398 - Final Data Preparation**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-10:00 | Delta Extraction | Extract latest changes from source | Delta extracted |
| 10:00-11:30 | Data Freezing | Coordinate data freeze in source | Data frozen |
| 11:30-13:00 | Final Validation | Final validation of all data | Validation complete |
| 14:00-15:30 | Rollback Prep | Prepare rollback scripts | Rollback ready |
| 15:30-17:00 | Migration Rehearsal | Dry run of production migration | Rehearsal complete |
| 17:00-18:00 | Sign-off | Obtain data migration sign-off | Sign-off obtained |

**Day 399 - Production Data Migration**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 06:00-06:30 | Pre-Migration Check | Final checks before migration | Checks complete |
| 06:30-07:00 | Database Backup | Create full database backup | Backup complete |
| 07:00-08:00 | Schema Migration | Execute database schema updates | Schema migrated |
| 08:00-09:30 | Master Data Load | Load master data to production | Master data loaded |
| 09:30-11:00 | Transactional Load | Load transactional data | Transactional data loaded |
| 11:00-12:00 | Data Validation | Validate loaded data | Validation complete |
| 13:00-14:00 | Reconciliation | Reconcile all data totals | Reconciliation passed |
| 14:00-15:00 | User Data Setup | Load user accounts and permissions | Users loaded |
| 15:00-16:00 | Configuration Load | Load system configurations | Config loaded |
| 16:00-17:00 | Final Verification | Complete final verification | Verification complete |
| 17:00-18:00 | Migration Sign-off | Obtain migration sign-off | Sign-off obtained |

**Day 400 - Post Migration Support**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-10:00 | Data Integrity Check | Verify data integrity overnight | Integrity confirmed |
| 10:00-11:30 | Transaction Monitoring | Monitor new transactions | Transactions flowing |
| 11:30-13:00 | Issue Resolution | Address any data-related issues | Issues resolved |
| 14:00-15:30 | Backup Verification | Verify automated backups | Backups verified |
| 15:30-17:00 | Documentation | Complete migration documentation | Docs complete |
| 17:00-18:00 | Handover | Handover to support team | Handover complete |

#### Developer 3 (Frontend Dev - UX & Training)

**Day 391 - UI Finalization**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-09:30 | UI Review | Final review of all UI components | Review complete |
| 09:30-11:00 | Bug Fixes | Address any outstanding UI bugs | Bugs fixed |
| 11:00-12:30 | Responsive Testing | Test on all device sizes | Responsive verified |
| 14:00-15:30 | Accessibility Check | Verify WCAG compliance | Accessibility OK |
| 15:30-17:00 | Performance Optimization | Optimize bundle size and loading | Performance improved |
| 17:00-18:00 | Cross-Browser Test | Test on all target browsers | Cross-browser OK |

**Day 392 - Training Materials Preparation**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-10:00 | Admin Guide | Complete administrator guide | Admin guide ready |
| 10:00-11:30 | User Manual | Complete end-user manual | User manual ready |
| 11:30-13:00 | Quick Start Guide | Create quick start guides | Quick start ready |
| 14:00-15:30 | Video Tutorials | Record video tutorial series | Videos recorded |
| 15:30-17:00 | FAQ Document | Compile frequently asked questions | FAQ ready |
| 17:00-18:00 | Material Review | Review all training materials | Materials approved |

**Day 393 - Training Portal Setup**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-10:00 | Portal Configuration | Set up training portal | Portal configured |
| 10:00-11:30 | Content Upload | Upload all training content | Content uploaded |
| 11:30-13:00 | Assessment Creation | Create training assessments | Assessments ready |
| 14:00-15:30 | User Registration | Register users for training | Users registered |
| 15:30-17:00 | Training Schedule | Publish training schedules | Schedule published |
| 17:00-18:00 | Portal Testing | Test training portal functionality | Portal tested |

**Day 394 - Administrator Training**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-09:00 | Session 1: System Overview | Overview of Smart Dairy system | Session 1 complete |
| 09:00-10:30 | Session 2: User Management | User and role management | Session 2 complete |
| 10:30-12:00 | Session 3: Configuration | System configuration | Session 3 complete |
| 14:00-15:00 | Session 4: Reporting | Report generation and scheduling | Session 4 complete |
| 15:00-16:30 | Session 5: Troubleshooting | Common issues and resolution | Session 5 complete |
| 16:30-18:00 | Assessment & Q&A | Admin assessment and questions | Assessment complete |

**Day 395 - End User Training - Part 1**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-09:00 | Batch 1: Farm Managers | Training for farm managers | Batch 1 trained |
| 09:00-10:00 | Batch 2: Collection Supervisors | Training for supervisors | Batch 2 trained |
| 10:00-11:00 | Batch 3: Accountants | Training for accounting staff | Batch 3 trained |
| 11:00-12:00 | Batch 4: Inventory Staff | Training for inventory staff | Batch 4 trained |
| 14:00-15:00 | Batch 5: Quality Staff | Training for quality staff | Batch 5 trained |
| 15:00-16:00 | Batch 6: Administrative Staff | Training for admin staff | Batch 6 trained |
| 16:00-18:00 | Hands-on Practice | Supervised practice sessions | Practice complete |

**Day 396 - End User Training - Part 2**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-09:00 | Batch 7: Field Officers | Training for field officers | Batch 7 trained |
| 09:00-10:00 | Batch 8: Procurement Staff | Training for procurement | Batch 8 trained |
| 10:00-11:00 | Batch 9: Sales Staff | Training for sales team | Batch 9 trained |
| 11:00-12:00 | Batch 10: Support Staff | Training for support team | Batch 10 trained |
| 14:00-15:00 | Mobile App Training | Training on mobile features | Mobile training done |
| 15:00-16:00 | Advanced Features | Advanced feature training | Advanced training done |
| 16:00-18:00 | Assessment | End-user assessments | Assessments complete |

**Day 397 - Support Documentation**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-10:00 | Runbook Creation | Create operational runbooks | Runbooks created |
| 10:00-11:30 | Troubleshooting Guide | Detailed troubleshooting guide | Guide created |
| 11:30-13:00 | Escalation Procedures | Document escalation procedures | Procedures documented |
| 14:00-15:30 | FAQ Update | Update FAQ based on training feedback | FAQ updated |
| 15:30-17:00 | Knowledge Base | Populate knowledge base articles | KB populated |
| 17:00-18:00 | Support Portal | Configure support portal | Portal ready |

**Day 398 - Go-Live Preparation**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-09:30 | Final UI Check | Final check of all UI elements | UI verified |
| 09:30-11:00 | User Acceptance Test | Support UAT activities | UAT supported |
| 11:00-12:30 | Issue Fixes | Fix any UAT issues | Issues fixed |
| 14:00-15:30 | Go-Live Communication | Prepare go-live communications | Communications ready |
| 15:30-17:00 | Support Team Briefing | Brief support team on common issues | Briefing complete |
| 17:00-18:00 | Final Preparation | Final go-live readiness checks | Preparation complete |

**Day 399 - Go-Live Day Support**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 06:00-08:00 | Pre-Go-Live | Final checks and preparation | Ready for go-live |
| 08:00-10:00 | Initial Support | Support first users logging in | Initial support provided |
| 10:00-12:00 | Active Monitoring | Monitor user activities and issues | Monitoring active |
| 13:00-15:00 | Issue Resolution | Address user-reported issues | Issues resolved |
| 15:00-17:00 | Guided Support | Provide guided assistance to users | Support provided |
| 17:00-18:00 | Day 1 Review | Review day 1 activities | Review complete |

**Day 400 - Post Go-Live Support**

| Time | Activity | Details | Deliverable |
|------|----------|---------|-------------|
| 08:00-10:00 | Issue Triage | Triage overnight and morning issues | Issues triaged |
| 10:00-11:30 | Training Follow-up | Follow-up training for struggling users | Follow-up complete |
| 11:30-13:00 | Documentation Update | Update docs based on user feedback | Docs updated |
| 14:00-15:30 | Feedback Collection | Collect and analyze user feedback | Feedback collected |
| 15:30-17:00 | Optimization | Implement UI/UX improvements | Optimizations done |
| 17:00-18:00 | Project Handover | Handover to ongoing support team | Handover complete |

---

## 4. Pre-Deployment Checklist

### 4.1 Infrastructure Readiness

#### 4.1.1 Server Infrastructure

| Item | Description | Status | Verified By | Date |
|------|-------------|--------|-------------|------|
| INF-001 | All production servers provisioned | Pending | | |
| INF-002 | Server specifications match requirements | Pending | | |
| INF-003 | Operating systems patched and updated | Pending | | |
| INF-004 | Security hardening applied | Pending | | |
| INF-005 | SSH key-based access configured | Pending | | |
| INF-006 | Firewall rules configured and tested | Pending | | |
| INF-007 | Server time synchronized (NTP) | Pending | | |
| INF-008 | Disk space adequate (more than 30% free) | Pending | | |
| INF-009 | Memory allocation verified | Pending | | |
| INF-010 | CPU capacity verified | Pending | | |
| INF-011 | Server naming convention applied | Pending | | |
| INF-012 | Server documentation complete | Pending | | |

#### 4.1.2 Network Infrastructure

| Item | Description | Status | Verified By | Date |
|------|-------------|--------|-------------|------|
| NET-001 | VPC created and configured | Pending | | |
| NET-002 | Subnets created (public/private) | Pending | | |
| NET-003 | Route tables configured | Pending | | |
| NET-004 | Internet Gateway attached | Pending | | |
| NET-005 | NAT Gateways configured | Pending | | |
| NET-006 | Security groups created | Pending | | |
| NET-007 | NACLs configured | Pending | | |
| NET-008 | VPC peering configured (if needed) | Pending | | |
| NET-009 | VPN/Direct Connect configured | Pending | | |
| NET-010 | DNS records created | Pending | | |
| NET-011 | SSL certificates installed | Pending | | |
| NET-012 | Load balancers configured | Pending | | |
| NET-013 | Health checks configured | Pending | | |
| NET-014 | Network connectivity tested | Pending | | |
| NET-015 | Latency within acceptable limits | Pending | | |

#### 4.1.3 Database Infrastructure

| Item | Description | Status | Verified By | Date |
|------|-------------|--------|-------------|------|
| DB-001 | Primary database instance created | Pending | | |
| DB-002 | Read replicas configured | Pending | | |
| DB-003 | Multi-AZ deployment enabled | Pending | | |
| DB-004 | Backup automation configured | Pending | | |
| DB-005 | Parameter groups optimized | Pending | | |
| DB-006 | Monitoring enabled | Pending | | |
| DB-007 | Performance Insights enabled | Pending | | |
| DB-008 | Encryption at rest enabled | Pending | | |
| DB-009 | Encryption in transit enabled | Pending | | |
| DB-010 | Security groups configured | Pending | | |
| DB-011 | Connection pooling configured | Pending | | |
| DB-012 | Replication lag within limits | Pending | | |
| DB-013 | Automated backups tested | Pending | | |
| DB-014 | Point-in-time recovery tested | Pending | | |
| DB-015 | Failover tested | Pending | | |

### 4.2 Code Freeze Procedures

#### 4.2.1 Code Freeze Checklist

| Item | Description | Status | Verified By | Date |
|------|-------------|--------|-------------|------|
| CF-001 | Code freeze announced to all teams | Pending | | |
| CF-002 | Feature freeze date communicated | Pending | | |
| CF-003 | Only critical bug fixes allowed | Pending | | |
| CF-004 | All code changes require CAB approval | Pending | | |
| CF-005 | Development branch locked | Pending | | |
| CF-006 | Release branch created | Pending | | |
| CF-007 | Version number finalized | Pending | | |
| CF-008 | Change log updated | Pending | | |
| CF-009 | Release notes prepared | Pending | | |
| CF-010 | All pending PRs reviewed and merged | Pending | | |
| CF-011 | Branch protection rules enabled | Pending | | |
| CF-012 | Commit signing enforced | Pending | | |

#### 4.2.2 Code Quality Verification

| Item | Description | Status | Verified By | Date |
|------|-------------|--------|-------------|------|
| CQ-001 | All unit tests passing | Pending | | |
| CQ-002 | Code coverage greater than 80% | Pending | | |
| CQ-003 | Integration tests passing | Pending | | |
| CQ-004 | E2E tests passing | Pending | | |
| CQ-005 | Security scan clean | Pending | | |
| CQ-006 | Static code analysis clean | Pending | | |
| CQ-007 | Dependency vulnerabilities resolved | Pending | | |
| CQ-008 | Performance benchmarks met | Pending | | |
| CQ-009 | API contract tests passing | Pending | | |
| CQ-010 | Accessibility tests passing | Pending | | |

### 4.3 Data Preparation

#### 4.3.1 Master Data Readiness

| Item | Description | Status | Verified By | Date |
|------|-------------|--------|-------------|------|
| DATA-001 | Farmer master data extracted | Pending | | |
| DATA-002 | Collection center data extracted | Pending | | |
| DATA-003 | Product catalog data extracted | Pending | | |
| DATA-004 | Chart of accounts extracted | Pending | | |
| DATA-005 | Price list data extracted | Pending | | |
| DATA-006 | User data extracted | Pending | | |
| DATA-007 | Supplier data extracted | Pending | | |
| DATA-008 | Customer data extracted | Pending | | |
| DATA-009 | All master data validated | Pending | | |
| DATA-010 | Data quality issues resolved | Pending | | |
| DATA-011 | Data mapping verified | Pending | | |
| DATA-012 | Transformation rules tested | Pending | | |

#### 4.3.2 Transactional Data Readiness

| Item | Description | Status | Verified By | Date |
|------|-------------|--------|-------------|------|
| TRANS-001 | Milk collection history extracted | Pending | | |
| TRANS-002 | Payment history extracted | Pending | | |
| TRANS-003 | Quality test records extracted | Pending | | |
| TRANS-004 | Inventory transactions extracted | Pending | | |
| TRANS-005 | Financial transactions extracted | Pending | | |
| TRANS-006 | Transaction data validated | Pending | | |
| TRANS-007 | Data totals reconciled | Pending | | |
| TRANS-008 | Data completeness verified | Pending | | |
| TRANS-009 | Data accuracy verified | Pending | | |
| TRANS-010 | Historical data retention confirmed | Pending | | |

### 4.4 Security Validation

#### 4.4.1 Infrastructure Security

| Item | Description | Status | Verified By | Date |
|------|-------------|--------|-------------|------|
| SEC-001 | Security groups reviewed | Pending | | |
| SEC-002 | NACLs reviewed | Pending | | |
| SEC-003 | WAF rules configured | Pending | | |
| SEC-004 | DDoS protection enabled | Pending | | |
| SEC-005 | Bastion hosts secured | Pending | | |
| SEC-006 | VPN access configured | Pending | | |
| SEC-007 | Network segmentation verified | Pending | | |
| SEC-008 | Port scanning completed | Pending | | |
| SEC-009 | Vulnerability scanning completed | Pending | | |
| SEC-010 | Penetration testing completed | Pending | | |

#### 4.4.2 Application Security

| Item | Description | Status | Verified By | Date |
|------|-------------|--------|-------------|------|
| APP-SEC-001 | Authentication configured | Pending | | |
| APP-SEC-002 | Authorization rules implemented | Pending | | |
| APP-SEC-003 | Session management configured | Pending | | |
| APP-SEC-004 | Input validation implemented | Pending | | |
| APP-SEC-005 | Output encoding implemented | Pending | | |
| APP-SEC-006 | CSRF protection enabled | Pending | | |
| APP-SEC-007 | XSS protection enabled | Pending | | |
| APP-SEC-008 | SQL injection prevention verified | Pending | | |
| APP-SEC-009 | Security headers configured | Pending | | |
| APP-SEC-010 | API rate limiting configured | Pending | | |

### 4.5 Performance Baseline

#### 4.5.1 Performance Requirements

| Metric | Requirement | Status |
|--------|-------------|--------|
| Page Load Time | Less than 3 seconds | Pending |
| API Response Time (p95) | Less than 500ms | Pending |
| Database Query Time (p95) | Less than 100ms | Pending |
| Concurrent Users | 1000+ | Pending |
| Transactions per Second | 100+ | Pending |
| Error Rate | Less than 0.1% | Pending |
| CPU Usage (peak) | Less than 70% | Pending |
| Memory Usage (peak) | Less than 80% | Pending |
| Cache Hit Ratio | Greater than 80% | Pending |
| Disk I/O Latency | Less than 10ms | Pending |

#### 4.5.2 Load Testing Results

| Test Scenario | Concurrent Users | Duration | Status |
|--------------|------------------|----------|--------|
| Login Flow | 500 | 30 min | Pending |
| Dashboard Load | 500 | 30 min | Pending |
| Milk Collection Entry | 200 | 30 min | Pending |
| Payment Processing | 100 | 30 min | Pending |
| Report Generation | 50 | 30 min | Pending |
| Mixed Workload | 1000 | 60 min | Pending |
| Peak Load Test | 2000 | 15 min | Pending |

---

## 5. Deployment Procedures

### 5.1 Pre-Deployment Phase

**Step 1: Environment Verification (T-60 minutes)**

```bash
#!/bin/bash
# pre_deployment_check.sh

echo "=== Pre-Deployment Environment Verification ==="

# Check server connectivity
echo "Checking server connectivity..."
for server in web-1a web-1b app-1a app-1b app-1c db-primary; do
    if ping -c 1 $server > /dev/null 2>&1; then
        echo "OK: $server is reachable"
    else
        echo "FAIL: $server is NOT reachable"
        exit 1
    fi
done

# Check disk space
echo "Checking disk space..."
for server in web-1a web-1b app-1a app-1b app-1c; do
    usage=$(ssh $server "df / | tail -1 | awk '{print \$5}' | sed 's/%//'")
    if [ "$usage" -gt 80 ]; then
        echo "FAIL: $server disk usage is ${usage}% (threshold: 80%)"
        exit 1
    else
        echo "OK: $server disk usage is ${usage}%"
    fi
done

# Check memory
echo "Checking memory..."
for server in app-1a app-1b app-1c; do
    usage=$(ssh $server "free | grep Mem | awk '{printf \"%.0f\", \$3/\$2 * 100.0}'")
    if [ "$usage" -gt 85 ]; then
        echo "FAIL: $server memory usage is ${usage}% (threshold: 85%)"
        exit 1
    else
        echo "OK: $server memory usage is ${usage}%"
    fi
done

echo "=== All Pre-Deployment Checks Passed ==="
```

**Step 2: Backup Creation (T-45 minutes)**

```bash
#!/bin/bash
# create_backup.sh

echo "=== Creating Production Backups ==="

BACKUP_TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/pre-deployment/${BACKUP_TIMESTAMP}"
mkdir -p $BACKUP_DIR

# Database Backup
echo "Creating database backup..."
pg_dump -h db-primary -U admin -d smart_dairy_prod \
    --verbose --format=custom --file="${BACKUP_DIR}/database_backup.dump"

if [ $? -eq 0 ]; then
    echo "OK: Database backup created"
else
    echo "FAIL: Database backup FAILED"
    exit 1
fi

# Application Code Backup
echo "Creating application code backup..."
tar -czf "${BACKUP_DIR}/application_backup.tar.gz" -C /opt/smart-dairy .

# Configuration Backup
echo "Creating configuration backup..."
tar -czf "${BACKUP_DIR}/config_backup.tar.gz" -C /etc/smart-dairy .

# Copy to S3
echo "Uploading backups to S3..."
aws s3 sync "${BACKUP_DIR}" "s3://smart-dairy-backups/pre-deployment/${BACKUP_TIMESTAMP}/"

echo "=== Backup Process Complete ==="
```

**Step 3: Maintenance Mode Activation (T-30 minutes)**

```bash
#!/bin/bash
# activate_maintenance_mode.sh

echo "=== Activating Maintenance Mode ==="

# Configure nginx maintenance mode
for server in web-1a web-1b; do
    echo "Configuring maintenance mode on $server..."
    ssh $server "
        cp /etc/nginx/sites-available/smart-dairy /etc/nginx/sites-available/smart-dairy.backup
        
        cat > /etc/nginx/sites-available/smart-dairy << 'NGINXCONF'
server {
    listen 80;
    server_name _;
    
    location / {
        root /var/www;
        try_files /maintenance.html =503;
    }
    
    location /health {
        access_log off;
        return 200 'OK';
        add_header Content-Type text/plain;
    }
}
NGINXCONF
        
        nginx -t && systemctl reload nginx
    "
    
    if [ $? -eq 0 ]; then
        echo "OK: Maintenance mode activated on $server"
    else
        echo "FAIL: Failed to activate maintenance mode on $server"
        exit 1
    fi
done

echo "=== Maintenance Mode Activated ==="
```



### 5.2 Database Migration Execution

**Step 4: Execute Database Migration (T-15 minutes)**

```bash
#!/bin/bash
# database_migration.sh

echo "=== Executing Database Migration ==="

MIGRATION_LOG="/var/log/smart-dairy/migration_$(date +%Y%m%d_%H%M%S).log"
mkdir -p /var/log/smart-dairy

# Pre-migration validation
echo "Running pre-migration validation..."
psql -h db-primary -U admin -d smart_dairy_prod \
    -f /opt/smart-dairy/migrations/pre_migration_checks.sql >> "$MIGRATION_LOG" 2>&1

if [ $? -ne 0 ]; then
    echo "FAIL: Pre-migration validation FAILED"
    exit 1
fi
echo "OK: Pre-migration validation passed"

# Execute migrations in transaction
echo "Executing database migrations..."
psql -h db-primary -U admin -d smart_dairy_prod << 'EOSQL' >> "$MIGRATION_LOG" 2>&1
BEGIN;

-- Migration tracking
CREATE TABLE IF NOT EXISTS schema_migrations (
    version VARCHAR(255) PRIMARY KEY,
    applied_at TIMESTAMP DEFAULT NOW(),
    description TEXT
);

-- Execute migration files in order
\i /opt/smart-dairy/migrations/V1_0__initial_schema.sql
\i /opt/smart-dairy/migrations/V1_1__add_indexes.sql
\i /opt/smart-dairy/migrations/V1_2__add_constraints.sql
\i /opt/smart-dairy/migrations/V1_3__create_views.sql
\i /opt/smart-dairy/migrations/V1_4__add_functions.sql
\i /opt/smart-dairy/migrations/V1_5__seed_data.sql

-- Record migration
INSERT INTO schema_migrations (version, description) 
VALUES ('1.5.0', 'Production deployment migration');

COMMIT;
EOSQL

if [ $? -eq 0 ]; then
    echo "OK: Database migration completed successfully"
else
    echo "FAIL: Database migration FAILED"
    exit 1
fi

# Post-migration validation
echo "Running post-migration validation..."
psql -h db-primary -U admin -d smart_dairy_prod \
    -f /opt/smart-dairy/migrations/post_migration_checks.sql >> "$MIGRATION_LOG" 2>&1

if [ $? -eq 0 ]; then
    echo "OK: Post-migration validation passed"
else
    echo "FAIL: Post-migration validation FAILED"
    exit 1
fi

# Run VACUUM ANALYZE
echo "Running VACUUM ANALYZE..."
psql -h db-primary -U admin -d smart_dairy_prod \
    -c "VACUUM ANALYZE;" >> "$MIGRATION_LOG" 2>&1

echo "OK: Database optimization complete"
```

**Step 5: Data Migration Execution (T-0 minutes - Go Time)**

```bash
#!/bin/bash
# data_migration.sh

echo "=== Executing Data Migration ==="

DATA_MIGRATION_LOG="/var/log/smart-dairy/data_migration_$(date +%Y%m%d_%H%M%S).log"
mkdir -p /var/log/smart-dairy

# Load master data
echo "Loading master data..."
psql -h db-primary -U admin -d smart_dairy_prod << 'EOSQL' >> "$DATA_MIGRATION_LOG" 2>&1
-- Load farmers
COPY farmers (id, name, phone, email, address, created_at)
FROM '/data/migration/farmers.csv'
WITH (FORMAT csv, HEADER true);

-- Load collection centers
COPY collection_centers (id, name, location, manager_id, created_at)
FROM '/data/migration/collection_centers.csv'
WITH (FORMAT csv, HEADER true);

-- Load products
COPY products (id, name, category, unit_price, unit, created_at)
FROM '/data/migration/products.csv'
WITH (FORMAT csv, HEADER true);

-- Load chart of accounts
COPY accounts (id, code, name, type, parent_id, created_at)
FROM '/data/migration/accounts.csv'
WITH (FORMAT csv, HEADER true);

-- Update sequences
SELECT setval('farmers_id_seq', (SELECT MAX(id) FROM farmers));
SELECT setval('collection_centers_id_seq', (SELECT MAX(id) FROM collection_centers));
SELECT setval('products_id_seq', (SELECT MAX(id) FROM products));
SELECT setval('accounts_id_seq', (SELECT MAX(id) FROM accounts));
EOSQL

if [ $? -eq 0 ]; then
    echo "OK: Master data loaded successfully"
else
    echo "FAIL: Master data loading FAILED"
    exit 1
fi

# Load transactional data
echo "Loading transactional data..."
psql -h db-primary -U admin -d smart_dairy_prod << 'EOSQL' >> "$DATA_MIGRATION_LOG" 2>&1
-- Load milk collections
COPY milk_collections (id, farmer_id, center_id, collection_date, quantity_liters, 
                       fat_percentage, snf_percentage, rate_per_liter, total_amount, created_at)
FROM '/data/migration/milk_collections.csv'
WITH (FORMAT csv, HEADER true);

-- Load quality tests
COPY quality_tests (id, collection_id, test_date, fat_content, snf_content, 
                    density, ph_level, result, tested_by, created_at)
FROM '/data/migration/quality_tests.csv'
WITH (FORMAT csv, HEADER true);

-- Load payments
COPY payments (id, farmer_id, payment_period_start, payment_period_end, total_quantity, 
               total_amount, deduction_amount, net_amount, payment_date, status, created_at)
FROM '/data/migration/payments.csv'
WITH (FORMAT csv, HEADER true);

-- Update sequences
SELECT setval('milk_collections_id_seq', (SELECT MAX(id) FROM milk_collections));
SELECT setval('quality_tests_id_seq', (SELECT MAX(id) FROM quality_tests));
SELECT setval('payments_id_seq', (SELECT MAX(id) FROM payments));
EOSQL

if [ $? -eq 0 ]; then
    echo "OK: Transactional data loaded successfully"
else
    echo "FAIL: Transactional data loading FAILED"
    exit 1
fi

echo "=== Data Migration Complete ==="
```

### 5.3 Application Deployment

**Step 6: Application Deployment (T+30 minutes)**

```bash
#!/bin/bash
# deploy_application.sh

echo "=== Deploying Smart Dairy Application ==="

DEPLOYMENT_ID=$(date +%Y%m%d_%H%M%S)
DEPLOYMENT_LOG="/var/log/smart-dairy/deployment_${DEPLOYMENT_ID}.log"
mkdir -p /var/log/smart-dairy

# Define servers
APP_SERVERS=("app-1a" "app-1b" "app-1c")
WEB_SERVERS=("web-1a" "web-1b")

# Deploy to application servers (rolling deployment)
for server in "${APP_SERVERS[@]}"; do
    echo "Deploying to $server..."
    
    # Remove from load balancer
    echo "  Removing $server from load balancer..."
    aws elbv2 deregister-targets \
        --target-group-arn "$TARGET_GROUP_ARN" \
        --targets Id="$server"
    
    sleep 30
    
    # Deploy application
    echo "  Copying new code..."
    rsync -avz --delete --exclude='node_modules' --exclude='.env' \
        /builds/smart-dairy/ "$server:/opt/smart-dairy/"
    
    # Install dependencies
    echo "  Installing dependencies..."
    ssh "$server" "cd /opt/smart-dairy && npm ci --production" >> "$DEPLOYMENT_LOG" 2>&1
    
    if [ $? -ne 0 ]; then
        echo "FAIL: Dependency installation failed on $server"
        exit 1
    fi
    
    # Restart application
    echo "  Restarting application..."
    ssh "$server" "systemctl restart smart-dairy-app"
    
    # Health check
    echo "  Performing health check..."
    for i in {1..10}; do
        if curl -sf "http://$server:3000/health" > /dev/null; then
            echo "  OK: Health check passed"
            break
        fi
        if [ "$i" -eq 10 ]; then
            echo "  FAIL: Health check failed"
            exit 1
        fi
        sleep 5
    done
    
    # Add back to load balancer
    echo "  Adding $server back to load balancer..."
    aws elbv2 register-targets \
        --target-group-arn "$TARGET_GROUP_ARN" \
        --targets Id="$server"
    
    echo "OK: $server deployment complete"
    sleep 60
done

# Deploy to web servers
for server in "${WEB_SERVERS[@]}"; do
    echo "Deploying to $server..."
    
    # Copy frontend build
    echo "  Copying frontend build..."
    rsync -avz --delete /builds/smart-dairy-frontend/build/ "$server:/var/www/smart-dairy/"
    
    # Update nginx config
    echo "  Updating nginx configuration..."
    scp /config/nginx/production.conf "$server:/etc/nginx/sites-available/smart-dairy"
    
    # Test and reload nginx
    echo "  Reloading nginx..."
    ssh "$server" "nginx -t && systemctl reload nginx"
    
    if [ $? -eq 0 ]; then
        echo "OK: $server deployment complete"
    else
        echo "FAIL: Nginx reload failed on $server"
        exit 1
    fi
done

echo "=== Application Deployment Complete ==="
```

**Step 7: Production Configuration (T+60 minutes)**

```bash
#!/bin/bash
# activate_production_config.sh

echo "=== Activating Production Configuration ==="

# Application Configuration
cat > /opt/smart-dairy/.env << 'EOF'
# Environment
NODE_ENV=production
APP_NAME=SmartDairy
APP_VERSION=1.5.0

# Server
PORT=3000
HOST=0.0.0.0

# Database
DB_HOST=db-primary.internal
DB_PORT=5432
DB_NAME=smart_dairy_prod
DB_USER=smart_dairy_app
DB_POOL_MIN=5
DB_POOL_MAX=50
DB_CONNECTION_TIMEOUT=30000

# Redis
REDIS_HOST=redis-cluster.internal
REDIS_PORT=6379
REDIS_DB=0

# Session
SESSION_TTL=86400

# JWT
JWT_EXPIRES_IN=24h
JWT_REFRESH_EXPIRES_IN=7d

# File Storage
S3_BUCKET=smart-dairy-documents-prod
S3_REGION=ap-south-1

# SMS Gateway
SMS_PROVIDER=twilio
SMS_FROM_NUMBER=+1234567890

# Email
SMTP_HOST=smtp.sendgrid.net
SMTP_PORT=587
SMTP_USER=apikey
EMAIL_FROM=noreply@smartdairy.com

# Payment Gateway
PAYMENT_GATEWAY=razorpay

# Logging
LOG_LEVEL=info
LOG_FORMAT=json

# Monitoring
METRICS_ENABLED=true
METRICS_PORT=9090
TRACING_ENABLED=true

# Feature Flags
FEATURE_ADVANCED_REPORTING=true
FEATURE_SMS_NOTIFICATIONS=true
FEATURE_PAYMENT_INTEGRATION=true
FEATURE_INVENTORY_MANAGEMENT=true
FEATURE_QUALITY_TRACKING=true
EOF

# Apply configuration
chmod 600 /opt/smart-dairy/.env
chown smart-dairy:smart-dairy /opt/smart-dairy/.env

# Restart application to pick up new config
echo "Restarting application with new configuration..."
systemctl restart smart-dairy-app

sleep 5
if curl -sf http://localhost:3000/api/health/config > /dev/null; then
    echo "OK: Configuration activated successfully"
else
    echo "FAIL: Configuration verification failed"
    exit 1
fi

echo "=== Production Configuration Activated ==="
```

### 5.4 Post-Deployment Verification

**Step 8: Post-Deployment Verification (T+90 minutes)**

```bash
#!/bin/bash
# post_deployment_verification.sh

echo "=== Post-Deployment Verification ==="

VERIFICATION_LOG="/var/log/smart-dairy/verification_$(date +%Y%m%d_%H%M%S).log"
mkdir -p /var/log/smart-dairy

VERIFICATION_STATUS="PASSED"

# 1. Health Checks
echo "1. Running health checks..."
for endpoint in \
    "https://api.smartdairy.com/health" \
    "https://api.smartdairy.com/health/db" \
    "https://api.smartdairy.com/health/redis" \
    "https://app.smartdairy.com/health"
do
    response=$(curl -sf -w "%{http_code}" "$endpoint" -o /dev/null)
    if [ "$response" = "200" ]; then
        echo "  OK: $endpoint"
    else
        echo "  FAIL: $endpoint - Status $response"
        VERIFICATION_STATUS="FAILED"
    fi
done

# 2. API Endpoints
echo "2. Testing API endpoints..."
API_TESTS=(
    "GET:/api/v1/auth/status"
    "GET:/api/v1/farmers"
    "GET:/api/v1/collection-centers"
    "GET:/api/v1/dashboard/summary"
)

for test in "${API_TESTS[@]}"; do
    IFS=':' read -r method endpoint <<< "$test"
    url="https://api.smartdairy.com${endpoint}"
    
    response=$(curl -sf -w "%{http_code}" -X "$method" "$url" -o /dev/null)
    if [ "$response" = "200" ]; then
        echo "  OK: $method $endpoint"
    else
        echo "  FAIL: $method $endpoint - Status $response"
        VERIFICATION_STATUS="FAILED"
    fi
done

# 3. Database Connectivity
echo "3. Testing database connectivity..."
if psql -h db-primary -U smart_dairy_app -d smart_dairy_prod \
    -c "SELECT COUNT(*) FROM farmers;" > /dev/null 2>&1; then
    echo "  OK: Database connectivity"
else
    echo "  FAIL: Database connectivity"
    VERIFICATION_STATUS="FAILED"
fi

# 4. Cache Connectivity
echo "4. Testing cache connectivity..."
if redis-cli -h redis-cluster -p 6379 ping | grep -q "PONG"; then
    echo "  OK: Cache connectivity"
else
    echo "  FAIL: Cache connectivity"
    VERIFICATION_STATUS="FAILED"
fi

# Summary
echo ""
echo "================================"
echo "VERIFICATION STATUS: $VERIFICATION_STATUS"
echo "================================"

if [ "$VERIFICATION_STATUS" = "FAILED" ]; then
    exit 1
fi
```

---

## 6. Data Migration Plan

### 6.1 Migration Strategy

**Migration Approach:** Big Bang with Multiple Dry-Runs

The Smart Dairy data migration follows a Big Bang approach for the final cutover, preceded by multiple dry-run migrations to ensure accuracy and completeness.

**Migration Timeline:**

| Phase | Activity | Duration | Day |
|-------|----------|----------|-----|
| Phase 1 | Historical Data Extraction | 2 days | 391-392 |
| Phase 2 | Test Migration 1 | 1 day | 393 |
| Phase 3 | Test Migration 2 | 1 day | 395 |
| Phase 4 | Final Dry Run | 1 day | 398 |
| Phase 5 | Production Migration | 1 day | 399 |

**Migration Scope:**

**Master Data:**
- Farmers: ~50,000 records
- Collection Centers: ~200 records
- Products: ~500 records
- Price Lists: ~2,000 records
- Chart of Accounts: ~300 records
- Users: ~500 records

**Transactional Data:**
- Milk Collections: ~5,000,000 records (5 years)
- Quality Tests: ~5,000,000 records (5 years)
- Payments: ~250,000 records (7 years)
- Feed Purchases: ~100,000 records (3 years)
- Medicine Purchases: ~50,000 records (3 years)
- Inventory Transactions: ~500,000 records (3 years)
- Financial Transactions: ~1,000,000 records (7 years)

### 6.2 Data Validation Framework

```sql
-- data_validation_framework.sql

-- Create validation results table
CREATE TABLE migration.validation_results (
    id SERIAL PRIMARY KEY,
    validation_name VARCHAR(100) NOT NULL,
    table_name VARCHAR(100),
    validation_type VARCHAR(50),
    status VARCHAR(20),
    expected_value TEXT,
    actual_value TEXT,
    details JSONB,
    executed_at TIMESTAMP DEFAULT NOW()
);

-- Validation function for row counts
CREATE OR REPLACE FUNCTION migration.validate_row_count(
    p_table_name VARCHAR,
    p_expected_count BIGINT
) RETURNS migration.validation_results AS $$
DECLARE
    v_actual_count BIGINT;
    v_result migration.validation_results;
BEGIN
    EXECUTE format('SELECT COUNT(*) FROM %I', p_table_name) INTO v_actual_count;
    
    v_result.validation_name := 'Row Count Check';
    v_result.table_name := p_table_name;
    v_result.validation_type := 'COUNT';
    v_result.expected_value := p_expected_count::TEXT;
    v_result.actual_value := v_actual_count::TEXT;
    v_result.status := CASE WHEN v_actual_count = p_expected_count THEN 'PASSED' ELSE 'FAILED' END;
    v_result.details := jsonb_build_object(
        'difference', v_actual_count - p_expected_count,
        'percentage_match', CASE 
            WHEN p_expected_count > 0 
            THEN ROUND((v_actual_count::NUMERIC / p_expected_count) * 100, 2)
            ELSE 100
        END
    );
    
    RETURN v_result;
END;
$$ LANGUAGE plpgsql;

-- Validation function for NULL checks
CREATE OR REPLACE FUNCTION migration.validate_nulls(
    p_table_name VARCHAR,
    p_column_name VARCHAR
) RETURNS migration.validation_results AS $$
DECLARE
    v_null_count BIGINT;
    v_total_count BIGINT;
    v_result migration.validation_results;
BEGIN
    EXECUTE format('SELECT COUNT(*) FROM %I WHERE %I IS NULL', p_table_name, p_column_name) INTO v_null_count;
    EXECUTE format('SELECT COUNT(*) FROM %I', p_table_name) INTO v_total_count;
    
    v_result.validation_name := 'NULL Value Check';
    v_result.table_name := p_table_name;
    v_result.validation_type := 'NULL_CHECK';
    v_result.expected_value := '0';
    v_result.actual_value := v_null_count::TEXT;
    v_result.status := CASE WHEN v_null_count = 0 THEN 'PASSED' ELSE 'FAILED' END;
    v_result.details := jsonb_build_object(
        'column', p_column_name,
        'null_count', v_null_count,
        'total_count', v_total_count
    );
    
    RETURN v_result;
END;
$$ LANGUAGE plpgsql;

-- Validation function for referential integrity
CREATE OR REPLACE FUNCTION migration.validate_referential_integrity(
    p_child_table VARCHAR,
    p_child_column VARCHAR,
    p_parent_table VARCHAR,
    p_parent_column VARCHAR
) RETURNS migration.validation_results AS $$
DECLARE
    v_orphan_count BIGINT;
    v_result migration.validation_results;
BEGIN
    EXECUTE format('SELECT COUNT(*) FROM %I c LEFT JOIN %I p ON c.%I = p.%I WHERE p.%I IS NULL AND c.%I IS NOT NULL',
        p_child_table, p_parent_table, p_child_column, p_parent_column, p_parent_column, p_child_column) 
    INTO v_orphan_count;
    
    v_result.validation_name := 'Referential Integrity Check';
    v_result.table_name := p_child_table;
    v_result.validation_type := 'FK_CHECK';
    v_result.expected_value := '0';
    v_result.actual_value := v_orphan_count::TEXT;
    v_result.status := CASE WHEN v_orphan_count = 0 THEN 'PASSED' ELSE 'FAILED' END;
    v_result.details := jsonb_build_object(
        'child_column', p_child_column,
        'parent_table', p_parent_table,
        'orphan_count', v_orphan_count
    );
    
    RETURN v_result;
END;
$$ LANGUAGE plpgsql;
```

### 6.3 Data Reconciliation

**Reconciliation Checklist:**

| Table | Source Count | Target Count | Match | Reconciled By | Date |
|-------|--------------|--------------|-------|---------------|------|
| farmers | | | Pending | | |
| collection_centers | | | Pending | | |
| products | | | Pending | | |
| accounts | | | Pending | | |
| price_lists | | | Pending | | |
| users | | | Pending | | |
| milk_collections | | | Pending | | |
| quality_tests | | | Pending | | |
| payments | | | Pending | | |
| inventory_transactions | | | Pending | | |

**Financial Reconciliation:**

| Metric | Source Total | Target Total | Variance | Status |
|--------|--------------|--------------|----------|--------|
| Total Milk Collections (Liters) | | | | Pending |
| Total Payments | | | | Pending |
| Total Feed Purchases | | | | Pending |
| Total Medicine Purchases | | | | Pending |
| Total Inventory Value | | | | Pending |

### 6.4 Rollback Procedures

**Data Migration Rollback:**

```bash
#!/bin/bash
# rollback_data_migration.sh

echo "=== Executing Data Migration Rollback ==="

# Confirm rollback
echo "WARNING: This will rollback the data migration!"
read -p "Are you sure? (yes/no): " confirm

if [ "$confirm" != "yes" ]; then
    echo "Rollback cancelled"
    exit 0
fi

# Restore database from pre-migration backup
BACKUP_FILE="/backups/pre-deployment/latest/database_backup.dump"

echo "Restoring database from backup..."
pg_restore -h db-primary -U admin -d smart_dairy_prod \
    --clean --if-exists --verbose "$BACKUP_FILE"

if [ $? -eq 0 ]; then
    echo "OK: Database restored successfully"
else
    echo "FAIL: Database restore failed"
    exit 1
fi

echo "=== Data Migration Rollback Complete ==="
```

---

## 7. Training Plan

### 7.1 Training Schedule

| Day | Time | Training Session | Audience | Trainer | Location |
|-----|------|------------------|----------|---------|----------|
| 394 | 08:00-09:00 | System Overview | Administrators | Dev 3 | Conference Room |
| 394 | 09:00-10:30 | User Management | Administrators | Dev 3 | Conference Room |
| 394 | 10:30-12:00 | System Configuration | Administrators | Dev 3 | Conference Room |
| 394 | 14:00-15:00 | Reporting | Administrators | Dev 3 | Conference Room |
| 394 | 15:00-16:30 | Troubleshooting | Administrators | Dev 3 | Conference Room |
| 395 | 08:00-09:00 | Farm Management | Farm Managers | Dev 3 | Training Room A |
| 395 | 09:00-10:00 | Collection Operations | Supervisors | Dev 3 | Training Room A |
| 395 | 10:00-11:00 | Accounting | Accountants | Dev 3 | Training Room B |
| 395 | 11:00-12:00 | Inventory Management | Inventory Staff | Dev 3 | Training Room B |
| 395 | 14:00-15:00 | Quality Control | Quality Staff | Dev 3 | Training Room A |
| 395 | 15:00-16:00 | Administration | Admin Staff | Dev 3 | Training Room B |
| 396 | 08:00-09:00 | Field Operations | Field Officers | Dev 3 | Training Room A |
| 396 | 09:00-10:00 | Procurement | Procurement Staff | Dev 3 | Training Room A |
| 396 | 10:00-11:00 | Sales | Sales Staff | Dev 3 | Training Room B |
| 396 | 11:00-12:00 | Support | Support Staff | Dev 3 | Training Room B |
| 396 | 14:00-15:00 | Mobile App | All Users | Dev 3 | Training Room A |
| 396 | 15:00-16:00 | Advanced Features | Power Users | Dev 3 | Training Room B |

### 7.2 User Groups

| Group | Users | Training Focus | Duration |
|-------|-------|----------------|----------|
| System Administrators | 5 | Full system administration | 8 hours |
| Farm Managers | 50 | Farm operations, reporting | 4 hours |
| Collection Supervisors | 200 | Milk collection, quality | 3 hours |
| Accountants | 20 | Financial operations | 4 hours |
| Inventory Staff | 30 | Inventory management | 3 hours |
| Quality Staff | 50 | Quality testing, compliance | 3 hours |
| Field Officers | 100 | Mobile app, field operations | 2 hours |
| Administrative Staff | 25 | General administration | 2 hours |

### 7.3 Training Materials

**Documentation:**

1. **Administrator Guide** (100 pages)
   - System architecture overview
   - User and role management
   - Configuration settings
   - Backup and recovery procedures
   - Troubleshooting guide

2. **End-User Manual** (80 pages)
   - Getting started
   - Module-specific guides
   - Common workflows
   - Tips and best practices
   - FAQ section

3. **Quick Start Guides** (2 pages each)
   - Farmer Registration
   - Milk Collection Entry
   - Quality Test Recording
   - Payment Processing
   - Report Generation

4. **Video Tutorials** (20 videos)
   - Introduction to Smart Dairy (5 min)
   - Dashboard Overview (10 min)
   - Farmer Management (15 min)
   - Milk Collection Process (15 min)
   - Quality Testing (10 min)
   - Payment Processing (15 min)
   - Inventory Management (15 min)
   - Report Generation (10 min)
   - Mobile App Usage (10 min)
   - Troubleshooting Common Issues (15 min)

### 7.4 Assessment Framework

**User Competency Assessment:**

| Module | Questions | Passing Score | Format |
|--------|-----------|---------------|--------|
| Login & Navigation | 5 | 80% | Multiple Choice |
| Farmer Management | 8 | 80% | Practical + Quiz |
| Milk Collection | 10 | 85% | Practical + Quiz |
| Quality Testing | 6 | 80% | Practical + Quiz |
| Payment Processing | 8 | 85% | Practical + Quiz |
| Reporting | 5 | 80% | Practical |
| Mobile App | 5 | 80% | Practical |

**Administrator Assessment:**

| Topic | Questions | Passing Score | Format |
|-------|-----------|---------------|--------|
| User Management | 10 | 90% | Practical |
| Configuration | 10 | 90% | Practical |
| Security | 8 | 95% | Quiz |
| Backup/Recovery | 5 | 90% | Practical |
| Troubleshooting | 10 | 85% | Scenario-based |

---

## 8. Go-Live Execution

### 8.1 Go-Live Checklist

**T-24 Hours:**

| Item | Description | Status | Owner |
|------|-------------|--------|-------|
| GL-001 | All pre-deployment checks completed | Pending | Dev 1 |
| GL-002 | Data migration scripts tested | Pending | Dev 2 |
| GL-003 | Rollback procedures tested | Pending | Dev 1 |
| GL-004 | Communication plan finalized | Pending | Dev 3 |
| GL-005 | Support team briefed | Pending | Dev 3 |
| GL-006 | Emergency contacts confirmed | Pending | PM |
| GL-007 | Backup verification completed | Pending | Dev 1 |

**T-4 Hours:**

| Item | Description | Status | Owner |
|------|-------------|--------|-------|
| GL-008 | Final data freeze initiated | Pending | Dev 2 |
| GL-009 | Production environment locked | Pending | Dev 1 |
| GL-010 | All stakeholders notified | Pending | Dev 3 |
| GL-011 | Hypercare team assembled | Pending | PM |

**T-1 Hour:**

| Item | Description | Status | Owner |
|------|-------------|--------|-------|
| GL-012 | Go/No-Go decision meeting | Pending | PM |
| GL-013 | Final system health check | Pending | Dev 1 |
| GL-014 | Communication channels tested | Pending | Dev 3 |

### 8.2 Cutover Procedures

**Go-Live Timeline:**

| Time | Activity | Owner | Duration |
|------|----------|-------|----------|
| 06:00 | Pre-deployment checks | Dev 1 | 30 min |
| 06:30 | Final data backup | Dev 2 | 15 min |
| 06:45 | Activate maintenance mode | Dev 1 | 15 min |
| 07:00 | Database migration | Dev 2 | 60 min |
| 08:00 | Data migration | Dev 2 | 90 min |
| 09:30 | Application deployment | Dev 1 | 60 min |
| 10:30 | Configuration activation | Dev 2 | 30 min |
| 11:00 | Smoke testing | Dev 1 | 30 min |
| 11:30 | Integration validation | Dev 2 | 30 min |
| 12:00 | Deactivate maintenance mode | Dev 1 | 15 min |
| 12:15 | User access verification | Dev 3 | 30 min |
| 12:45 | Go-Live announcement | Dev 3 | 15 min |
| 13:00 | System live for users | All | - |

### 8.3 Smoke Tests

**Smoke Test Checklist:**

| Test ID | Test Description | Expected Result | Status |
|---------|------------------|-----------------|--------|
| SM-001 | Application login | Successful login | Pending |
| SM-002 | Dashboard load | Dashboard displays within 3 seconds | Pending |
| SM-003 | Farmer list view | Farmer list loads correctly | Pending |
| SM-004 | Collection entry | Can enter milk collection | Pending |
| SM-005 | Quality test entry | Can record quality test | Pending |
| SM-006 | Payment view | Payment history displays | Pending |
| SM-007 | Report generation | Reports generate successfully | Pending |
| SM-008 | SMS notification | SMS sent successfully | Pending |
| SM-009 | Email notification | Email sent successfully | Pending |
| SM-010 | Mobile app sync | Mobile app syncs correctly | Pending |

### 8.4 Hypercare Plan

**Hypercare Schedule:**

| Period | Coverage | Support Level | Contact |
|--------|----------|---------------|---------|
| Day 1 (Go-Live) | 24/7 | On-site + Remote | All Devs |
| Day 2 | 24/7 | On-call | Dev 1, Dev 2 |
| Day 3 | 24/7 | On-call | Dev 1, Dev 2 |
| Week 1 | 12/7 (7AM-7PM) | Remote | Dev 1 |
| Week 2-4 | Business Hours | Remote | Support Team |

**Hypercare Activities:**

1. **Real-time Monitoring:**
   - System performance metrics
   - Error logs and alerts
   - User activity tracking
   - Transaction volume monitoring

2. **Issue Triage:**
   - P1 (Critical): < 15 minutes response
   - P2 (High): < 1 hour response
   - P3 (Medium): < 4 hours response
   - P4 (Low): < 24 hours response

3. **User Support:**
   - Live chat support
   - Phone hotline
   - Screen-sharing sessions
   - On-site assistance (if needed)

4. **Daily Reports:**
   - System health summary
   - Issue log and resolution status
   - User feedback summary
   - Performance metrics

### 8.5 Escalation Matrix

| Severity | Issue Type | First Response | Resolution Target | Escalation Path |
|----------|-----------|----------------|-------------------|-----------------|
| P1 - Critical | System down, data loss | 15 minutes | 2 hours | Dev 1 -> PM -> Management |
| P2 - High | Major feature broken | 1 hour | 4 hours | Dev 2 -> Dev 1 -> PM |
| P3 - Medium | Minor feature issue | 4 hours | 24 hours | Dev 3 -> Dev 2 |
| P4 - Low | Cosmetic issues | 24 hours | 72 hours | Support Team |

---

## 9. Post Go-Live Support

### 9.1 Support Structure

**Support Organization:**

```
Level 1 Support (L1)
├── Help Desk
├── First-line troubleshooting
├── User guidance
└── Ticket logging

Level 2 Support (L2)
├── Technical specialists
├── Complex issue resolution
├── Configuration changes
└── Escalation to L3

Level 3 Support (L3)
├── Development team
├── Bug fixes
├── Code changes
└── Root cause analysis
```

**Support Channels:**

| Channel | Availability | Response Time | Purpose |
|---------|-------------|---------------|---------|
| Phone Hotline | 24/7 (First week) | < 5 minutes | Urgent issues |
| Email Support | 24/7 | < 4 hours | General inquiries |
| Live Chat | Business hours | < 2 minutes | Quick questions |
| Support Portal | 24/7 | < 24 hours | Non-urgent requests |
| On-site Support | Scheduled | As needed | Complex issues |

### 9.2 Issue Resolution Process

**Issue Lifecycle:**

1. **Report:** User reports issue through support channel
2. **Log:** Issue logged in ticketing system with categorization
3. **Triage:** Support team assesses severity and assigns priority
4. **Investigate:** Technical team investigates root cause
5. **Resolve:** Issue is resolved or workaround provided
6. **Verify:** User verifies resolution
7. **Close:** Ticket is closed with documentation
8. **Review:** Periodic review of closed tickets for trends

**Issue Categories:**

| Category | Examples | Typical Resolution |
|----------|----------|-------------------|
| Login Issues | Forgot password, account locked | < 30 minutes |
| Data Issues | Missing records, incorrect data | < 4 hours |
| Performance | Slow loading, timeouts | < 2 hours |
| Feature Issues | Function not working | < 24 hours |
| Integration | SMS/email not sending | < 2 hours |
| Reporting | Report errors, missing data | < 4 hours |

### 9.3 Performance Monitoring

**Daily Monitoring Checklist:**

| Check | Target | Frequency | Action if Failed |
|-------|--------|-----------|------------------|
| System uptime | 99.9% | Continuous | Page on-call engineer |
| Response time (p95) | < 500ms | Every 5 minutes | Investigate bottlenecks |
| Error rate | < 0.1% | Every 5 minutes | Review error logs |
| Database connections | < 80% | Every 5 minutes | Check for connection leaks |
| Cache hit ratio | > 80% | Every 5 minutes | Review cache configuration |
| Disk space | < 80% | Every hour | Clean up or expand |
| Memory usage | < 85% | Every 5 minutes | Investigate memory leaks |
| CPU usage | < 70% | Every 5 minutes | Scale or optimize |

### 9.4 Optimization Activities

**Week 1-2 Optimization:**

| Area | Optimization | Expected Improvement | Owner |
|------|--------------|----------------------|-------|
| Database | Index optimization | 20% query improvement | Dev 2 |
| Caching | Cache warming strategy | 15% response time | Dev 1 |
| Frontend | Asset optimization | 30% load time | Dev 3 |
| API | Response compression | 40% bandwidth | Dev 1 |
| Workers | Background job tuning | 25% throughput | Dev 2 |

**Continuous Improvement:**

- Weekly performance reviews
- Monthly capacity planning
- Quarterly architecture reviews
- User feedback integration
- Feature enhancement prioritization

---

## 10. Project Handover

### 10.1 Handover Checklist

**Technical Handover:**

| Item | Description | Status | From | To |
|------|-------------|--------|------|-----|
| HD-001 | Source code repository access | Pending | Dev 1 | Ops Team |
| HD-002 | Infrastructure documentation | Pending | Dev 1 | Ops Team |
| HD-003 | Deployment runbooks | Pending | Dev 1 | Ops Team |
| HD-004 | Monitoring dashboards | Pending | Dev 1 | Ops Team |
| HD-005 | Alert configuration | Pending | Dev 1 | Ops Team |
| HD-006 | Backup procedures | Pending | Dev 2 | Ops Team |
| HD-007 | Database documentation | Pending | Dev 2 | Ops Team |
| HD-008 | API documentation | Pending | Dev 1 | Ops Team |
| HD-009 | Security documentation | Pending | Dev 1 | Security Team |
| HD-010 | SSL certificates | Pending | Dev 1 | Ops Team |

**Operational Handover:**

| Item | Description | Status | From | To |
|------|-------------|--------|------|-----|
| HD-011 | User guides and manuals | Pending | Dev 3 | Support Team |
| HD-012 | Training materials | Pending | Dev 3 | Support Team |
| HD-013 | FAQ and knowledge base | Pending | Dev 3 | Support Team |
| HD-014 | Troubleshooting guides | Pending | Dev 3 | Support Team |
| HD-015 | Escalation procedures | Pending | PM | Support Team |
| HD-016 | Contact lists | Pending | PM | Support Team |
| HD-017 | Vendor contacts | Pending | PM | Support Team |
| HD-018 | License information | Pending | PM | Ops Team |

### 10.2 Documentation Transfer

**Documentation Inventory:**

| Document | Location | Format | Owner | Status |
|----------|----------|--------|-------|--------|
| System Architecture | /docs/architecture | PDF, Draw.io | Dev 1 | Ready |
| Deployment Guide | /docs/deployment | Markdown | Dev 1 | Ready |
| Database Schema | /docs/database | SQL, PDF | Dev 2 | Ready |
| API Documentation | /docs/api | OpenAPI, HTML | Dev 1 | Ready |
| User Manual | /docs/user | PDF | Dev 3 | Ready |
| Admin Guide | /docs/admin | PDF | Dev 3 | Ready |
| Runbooks | /docs/runbooks | Markdown | Dev 1 | Ready |
| Security Guide | /docs/security | PDF | Dev 1 | Ready |
| Test Reports | /docs/testing | PDF | QA | Ready |
| Training Materials | /docs/training | PDF, Video | Dev 3 | Ready |

### 10.3 Knowledge Transfer

**Knowledge Transfer Sessions:**

| Session | Topic | Duration | Attendees | Trainer |
|---------|-------|----------|-----------|---------|
| KT-001 | System Architecture Overview | 4 hours | Ops Team | Dev 1 |
| KT-002 | Deployment Procedures | 4 hours | Ops Team | Dev 1 |
| KT-003 | Database Management | 4 hours | Ops Team | Dev 2 |
| KT-004 | Monitoring and Alerting | 2 hours | Ops Team | Dev 1 |
| KT-005 | Troubleshooting Common Issues | 4 hours | Support Team | Dev 3 |
| KT-006 | User Support Procedures | 4 hours | Support Team | Dev 3 |
| KT-007 | Security Incident Response | 2 hours | Security Team | Dev 1 |
| KT-008 | Backup and Recovery | 2 hours | Ops Team | Dev 2 |

### 10.4 Warranty Period

**Warranty Terms:**

| Aspect | Coverage | Duration | Response Time |
|--------|----------|----------|---------------|
| Bug Fixes | All critical and high bugs | 90 days | Per severity |
| Performance | System meets SLA | 90 days | 24 hours |
| Data Integrity | No data loss | 90 days | Immediate |
| Security | Vulnerability fixes | 90 days | 24 hours |
| Support | Technical assistance | 90 days | Business hours |

**Warranty Exclusions:**

- Issues caused by unauthorized modifications
- Issues caused by third-party integrations
- Issues caused by user error
- Issues caused by infrastructure changes
- New feature requests

---

## 11. Appendices

### Appendix A: Runbooks

#### A.1 Application Restart Runbook

```markdown
# Application Restart Runbook

## Purpose
Restart Smart Dairy application services safely

## Prerequisites
- SSH access to application servers
- sudo privileges

## Procedure

### 1. Identify Target Server
- Check monitoring dashboard for problematic server
- Verify server hostname

### 2. Drain Connections
```bash
# Remove from load balancer
aws elbv2 deregister-targets \
    --target-group-arn $TARGET_GROUP_ARN \
    --targets Id=<server-id>

# Wait 30 seconds for connection drain
sleep 30
```

### 3. Restart Application
```bash
# SSH to server
ssh <server-hostname>

# Check current status
sudo systemctl status smart-dairy-app

# Restart application
sudo systemctl restart smart-dairy-app

# Verify restart
sudo systemctl status smart-dairy-app
```

### 4. Health Check
```bash
# Wait for application to start
sleep 10

# Check health endpoint
curl -f http://localhost:3000/health
```

### 5. Return to Service
```bash
# Add back to load balancer
aws elbv2 register-targets \
    --target-group-arn $TARGET_GROUP_ARN \
    --targets Id=<server-id>
```

### 6. Verify
- Check load balancer target health
- Monitor error rates
- Verify response times

## Rollback
If restart fails:
1. Keep server out of load balancer
2. Investigate logs: `sudo journalctl -u smart-dairy-app`
3. Escalate to L3 support if needed

## References
- Application logs: `/var/log/smart-dairy/`
- System logs: `/var/log/syslog`
```

#### A.2 Database Failover Runbook

```markdown
# Database Failover Runbook

## Purpose
Execute database failover to standby instance

## When to Use
- Primary database instance failure
- Primary AZ outage
- Maintenance requiring failover

## Procedure

### 1. Verify Standby Status
```bash
# Check replication lag
psql -h db-replica -U admin -c "SELECT pg_last_xact_replay_timestamp();"

# Lag should be < 1 minute for failover
```

### 2. Initiate Failover
```bash
# Using AWS CLI
aws rds failover-db-cluster \
    --db-cluster-identifier smart-dairy-cluster \
    --target-db-instance-identifier db-replica-1
```

### 3. Verify Failover
```bash
# Check new primary
aws rds describe-db-instances \
    --db-instance-identifier db-replica-1 \
    --query 'DBInstances[0].DBInstanceStatus'

# Should show "available"
```

### 4. Update Application Configuration
If needed, update connection strings in:
- Application configuration
- Connection pool settings
- Monitoring scripts

### 5. Verify Application Connectivity
```bash
# Test database connection from app servers
psql -h <new-primary-endpoint> -U smart_dairy_app \
    -d smart_dairy_prod -c "SELECT 1;"
```

### 6. Monitor
- Watch replication to new standby
- Monitor application error rates
- Check query performance

## Post-Failover Actions
1. Investigate cause of primary failure
2. Schedule repair/replacement of old primary
3. Document incident
4. Review and update procedures if needed
```

#### A.3 Backup Restoration Runbook

```markdown
# Backup Restoration Runbook

## Purpose
Restore database from backup

## Prerequisites
- Backup file location (S3 or local)
- Database admin credentials
- Sufficient disk space

## Procedure

### 1. Assess Situation
- Determine scope of restoration (full or partial)
- Identify target backup file
- Notify stakeholders of downtime

### 2. Stop Application
```bash
# Stop all application servers
for server in app-1a app-1b app-1c; do
    ssh $server "sudo systemctl stop smart-dairy-app"
done
```

### 3. Create Pre-Restore Backup
```bash
# Backup current state (even if corrupted)
pg_dump -h db-primary -U admin -d smart_dairy_prod \
    -F c -f "/backups/pre-restore-$(date +%Y%m%d_%H%M%S).dump"
```

### 4. Download Backup (if in S3)
```bash
aws s3 cp s3://smart-dairy-backups/<backup-file> \
    /restore/backup.dump
```

### 5. Restore Database
```bash
# Drop and recreate database
psql -h db-primary -U admin -c "DROP DATABASE smart_dairy_prod;"
psql -h db-primary -U admin -c "CREATE DATABASE smart_dairy_prod;"

# Restore from backup
pg_restore -h db-primary -U admin -d smart_dairy_prod \
    --verbose /restore/backup.dump
```

### 6. Verify Restoration
```bash
# Check row counts
psql -h db-primary -U admin -d smart_dairy_prod << 'EOF'
SELECT 'farmers' as table_name, COUNT(*) FROM farmers
UNION ALL
SELECT 'milk_collections', COUNT(*) FROM milk_collections
UNION ALL
SELECT 'payments', COUNT(*) FROM payments;
EOF
```

### 7. Restart Application
```bash
for server in app-1a app-1b app-1c; do
    ssh $server "sudo systemctl start smart-dairy-app"
done
```

### 8. Post-Restore Verification
- Run smoke tests
- Verify critical functionality
- Check data integrity
- Monitor error logs

## Estimated Time
- Full restore: 2-4 hours (depending on size)
- Point-in-time: 1-2 hours

## References
- Backup inventory: s3://smart-dairy-backups/
- Recovery procedures: /docs/disaster-recovery.md
```

### Appendix B: Scripts

#### B.1 Health Check Script

```bash
#!/bin/bash
# health_check.sh - Comprehensive system health check

REPORT_FILE="/var/log/smart-dairy/health-report-$(date +%Y%m%d_%H%M%S).txt"

echo "Smart Dairy Health Check Report" > "$REPORT_FILE"
echo "Generated: $(date)" >> "$REPORT_FILE"
echo "========================================" >> "$REPORT_FILE"

check_service() {
    local name=$1
    local status=$(systemctl is-active "$name" 2>/dev/null)
    if [ "$status" = "active" ]; then
        echo "[PASS] $name is running" >> "$REPORT_FILE"
        return 0
    else
        echo "[FAIL] $name is not running (status: $status)" >> "$REPORT_FILE"
        return 1
    fi
}

check_disk() {
    local threshold=80
    df -h | awk 'NR>1 {
        gsub(/%/,"",$5);
        if ($5 > threshold) 
            print "[FAIL] Disk usage on " $6 ": " $5 "%";
        else 
            print "[PASS] Disk usage on " $6 ": " $5 "%";
    }' >> "$REPORT_FILE"
}

check_memory() {
    local usage=$(free | grep Mem | awk '{printf "%.0f", $3/$2 * 100.0}')
    if [ "$usage" -gt 85 ]; then
        echo "[FAIL] Memory usage: ${usage}%" >> "$REPORT_FILE"
    else
        echo "[PASS] Memory usage: ${usage}%" >> "$REPORT_FILE"
    fi
}

check_database() {
    if psql -h db-primary -U smart_dairy_app -d smart_dairy_prod \
        -c "SELECT 1;" > /dev/null 2>&1; then
        echo "[PASS] Database connectivity" >> "$REPORT_FILE"
    else
        echo "[FAIL] Database connectivity" >> "$REPORT_FILE"
    fi
}

check_redis() {
    if redis-cli -h redis-cluster ping | grep -q "PONG"; then
        echo "[PASS] Redis connectivity" >> "$REPORT_FILE"
    else
        echo "[FAIL] Redis connectivity" >> "$REPORT_FILE"
    fi
}

check_endpoint() {
    local url=$1
    local name=$2
    if curl -sf "$url" > /dev/null 2>&1; then
        echo "[PASS] $name is accessible" >> "$REPORT_FILE"
    else
        echo "[FAIL] $name is not accessible" >> "$REPORT_FILE"
    fi
}

# Run checks
echo "Checking services..." >> "$REPORT_FILE"
check_service "smart-dairy-app"
check_service "nginx"
check_service "postgresql"
check_service "redis-server"

echo "" >> "$REPORT_FILE"
echo "Checking resources..." >> "$REPORT_FILE"
check_disk
check_memory

echo "" >> "$REPORT_FILE"
echo "Checking connectivity..." >> "$REPORT_FILE"
check_database
check_redis

echo "" >> "$REPORT_FILE"
echo "Checking endpoints..." >> "$REPORT_FILE"
check_endpoint "http://localhost:3000/health" "Application Health"
check_endpoint "https://app.smartdairy.com" "Web Frontend"

echo "" >> "$REPORT_FILE"
echo "========================================" >> "$REPORT_FILE"
echo "Health check complete. Report: $REPORT_FILE"

cat "$REPORT_FILE"
```

#### B.2 Log Collection Script

```bash
#!/bin/bash
# collect_logs.sh - Collect logs for troubleshooting

OUTPUT_DIR="/tmp/log-collection-$(date +%Y%m%d_%H%M%S)"
mkdir -p "$OUTPUT_DIR"

echo "Collecting logs..."

# Application logs
cp /var/log/smart-dairy/*.log "$OUTPUT_DIR/" 2>/dev/null

# System logs
journalctl -u smart-dairy-app --since "24 hours ago" > "$OUTPUT_DIR/app-journal.log" 2>/dev/null
journalctl -u nginx --since "24 hours ago" > "$OUTPUT_DIR/nginx-journal.log" 2>/dev/null

# Database logs
cp /var/log/postgresql/*.log "$OUTPUT_DIR/" 2>/dev/null

# Nginx logs
cp /var/log/nginx/access.log "$OUTPUT_DIR/" 2>/dev/null
cp /var/log/nginx/error.log "$OUTPUT_DIR/" 2>/dev/null

# System information
uname -a > "$OUTPUT_DIR/system-info.txt"
free -h >> "$OUTPUT_DIR/system-info.txt"
df -h >> "$OUTPUT_DIR/system-info.txt"
ps aux >> "$OUTPUT_DIR/system-info.txt"

# Application status
systemctl status smart-dairy-app > "$OUTPUT_DIR/app-status.txt" 2>&1

# Create archive
tar -czf "${OUTPUT_DIR}.tar.gz" -C /tmp "$(basename "$OUTPUT_DIR")"

# Upload to S3
aws s3 cp "${OUTPUT_DIR}.tar.gz" "s3://smart-dairy-support/logs/"

echo "Logs collected: ${OUTPUT_DIR}.tar.gz"
echo "Uploaded to: s3://smart-dairy-support/logs/"
```

### Appendix C: Contact Lists

#### C.1 Project Team Contacts

| Role | Name | Phone | Email | Availability |
|------|------|-------|-------|--------------|
| Project Manager | | | | 24/7 (Go-Live week) |
| Lead Developer (DevOps) | Developer 1 | | | 24/7 (Go-Live week) |
| Backend Developer | Developer 2 | | | 24/7 (Go-Live week) |
| Frontend Developer | Developer 3 | | | 24/7 (Go-Live week) |
| QA Lead | | | | Business hours |
| Database Administrator | | | | 24/7 (Go-Live week) |

#### C.2 Vendor Contacts

| Vendor | Service | Contact | Phone | Email |
|--------|---------|---------|-------|-------|
| AWS | Infrastructure | Support | | support@amazon.com |
| Twilio | SMS Gateway | Support | | support@twilio.com |
| SendGrid | Email Service | Support | | support@sendgrid.com |
| Razorpay | Payments | Support | | support@razorpay.com |
| Cloudflare | CDN/DNS | Support | | support@cloudflare.com |

#### C.3 Emergency Contacts

| Organization | Contact | Phone | Purpose |
|--------------|---------|-------|---------|
| Smart Dairy Management | | | Business decisions |
| IT Security Team | | | Security incidents |
| Network Operations | | | Network issues |
| Data Center | | | Infrastructure issues |

### Appendix D: Templates

#### D.1 Incident Report Template

```markdown
# Incident Report

## Incident Details
- **Incident ID:** INC-YYYY-NNNN
- **Date/Time Started:** 
- **Date/Time Resolved:** 
- **Duration:** 
- **Severity:** P1/P2/P3/P4
- **Reporter:** 

## Summary
Brief description of the incident

## Impact
- Users affected:
- Services affected:
- Business impact:

## Root Cause
Description of root cause

## Timeline
| Time | Event |
|------|-------|
| | |

## Resolution Steps
1. Step 1
2. Step 2

## Lessons Learned
- 

## Preventive Actions
- [ ] Action 1
- [ ] Action 2
```

#### D.2 Change Request Template

```markdown
# Change Request

## Change Details
- **CR ID:** CR-YYYY-NNNN
- **Requested By:** 
- **Date:** 
- **Priority:** High/Medium/Low

## Change Description
Detailed description of the change

## Business Justification
Why is this change needed?

## Technical Details
- Components affected:
- Configuration changes:
- Database changes:

## Risk Assessment
| Risk | Likelihood | Impact | Mitigation |
|------|------------|--------|------------|
| | | | |

## Testing Plan
- [ ] Unit tests
- [ ] Integration tests
- [ ] UAT

## Rollback Plan
Steps to rollback if needed

## Approvals
- [ ] Technical Lead
- [ ] Project Manager
- [ ] Change Advisory Board
```

---

**Document Control**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-02 | Project Team | Initial release |

**Approval**

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Project Manager | | | |
| Technical Lead | | | |
| Client Representative | | | |

---

*This document is confidential and proprietary to Smart Dairy. Unauthorized distribution is prohibited.*

