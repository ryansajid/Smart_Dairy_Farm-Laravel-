# SMART DAIRY LTD.
## DISASTER RECOVERY PLAN
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | D-012 |
| **Version** | 1.0 |
| **Date** | October 18, 2026 |
| **Author** | DevOps Lead |
| **Owner** | DevOps Lead |
| **Reviewer** | IT Director |
| **Classification** | CONFIDENTIAL |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [DR Architecture](#2-dr-architecture)
3. [Recovery Objectives](#3-recovery-objectives)
4. [Disaster Scenarios](#4-disaster-scenarios)
5. [Recovery Procedures](#5-recovery-procedures)
6. [Failover Procedures](#6-failover-procedures)
7. [DR Testing](#7-dr-testing)
8. [Roles & Responsibilities](#8-roles--responsibilities)
9. [Communication Plan](#9-communication-plan)
10. [Appendices](#10-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This Disaster Recovery Plan (DRP) establishes procedures to recover Smart Dairy's critical systems and data following a disaster. It ensures business continuity with defined recovery objectives and minimizes downtime impact on operations.

### 1.2 Scope

| Component | DR Coverage | Criticality |
|-----------|-------------|-------------|
| **Odoo ERP** | Full DR | Critical |
| **PostgreSQL Database** | Full DR | Critical |
| **TimescaleDB Analytics** | Full DR | Critical |
| **EMQX MQTT Broker** | Full DR | Critical |
| **B2C E-commerce** | Full DR | Critical |
| **B2B Portal** | Full DR | Critical |
| **IoT Data Pipeline** | Full DR | Critical |
| **Monitoring/Logging** | Partial DR | Important |
| **Development Environments** | No DR | Low |

### 1.3 Related Documents

- C-010 Backup & Recovery Procedures
- D-001 Infrastructure Architecture
- D-004 Kubernetes Deployment Guide
- C-009 Database Performance Tuning

---

## 2. DR ARCHITECTURE

### 2.1 Multi-Region Deployment

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                              DISASTER RECOVERY ARCHITECTURE                          │
└─────────────────────────────────────────────────────────────────────────────────────┘

     PRIMARY REGION                          DR REGION
   ┌──────────────────┐                   ┌──────────────────┐
   │  ap-south-1      │                   │ ap-southeast-1   │
   │  (Mumbai)        │                   │ (Singapore)      │
   │                  │                   │                  │
   │  ┌────────────┐  │                   │  ┌────────────┐  │
   │  │   EKS      │  │  ←─────────────→  │  │   EKS      │  │
   │  │  Cluster   │  │   Velero Backup   │  │  Cluster   │  │
   │  │  (Active)  │  │   Sync           │  │ (Standby)  │  │
   │  └────────────┘  │                   │  └────────────┘  │
   │        │         │                   │        │         │
   │  ┌────────────┐  │                   │  ┌────────────┐  │
   │  │    RDS     │  │  ←─────────────→  │  │    RDS     │  │
   │  │ PostgreSQL │  │   Async          │  │ PostgreSQL │  │
   │  │ (Primary)  │  │   Replication    │  │ (Replica)  │  │
   │  └────────────┘  │                   │  └────────────┘  │
   │        │         │                   │        │         │
   │  ┌────────────┐  │                   │  ┌────────────┐  │
   │  │    S3      │  │  ←─────────────→  │  │    S3      │  │
   │  │  Buckets   │  │   Cross-Region   │  │  Buckets   │  │
   │  │ (Source)   │  │   Replication    │  │  (Target)  │  │
   │  └────────────┘  │                   │  └────────────┘  │
   └──────────────────┘                   └──────────────────┘
            │                                      │
            │                                      │
            └───────────────┬──────────────────────┘
                            │
                    ┌───────▼────────┐
                    │  Route 53      │
                    │  Health Checks │
                    │  Failover      │
                    └────────────────┘
```

### 2.2 DR Site Specifications

| Component | Primary | DR Site | Notes |
|-----------|---------|---------|-------|
| **AWS Region** | ap-south-1 (Mumbai) | ap-southeast-1 (Singapore) | ~4,500 km separation |
| **EKS Nodes** | 3-10 nodes | 2-5 nodes (scaled) | Auto-scaling enabled |
| **RDS Instance** | db.r6g.2xlarge | db.r6g.xlarge | Can be promoted |
| **S3 Buckets** | Standard | Standard-IA (replica) | CRR enabled |
| **Network Latency** | < 1ms | ~60ms to Primary | Acceptable for DR |

---

## 3. RECOVERY OBJECTIVES

### 3.1 Recovery Time Objective (RTO)

| Service Tier | RTO | Description |
|--------------|-----|-------------|
| **Tier 1 - Critical** | 1 hour | ERP, Database, IoT Core, E-commerce |
| **Tier 2 - Important** | 4 hours | B2B Portal, Reporting, Analytics |
| **Tier 3 - Standard** | 24 hours | Monitoring, Non-prod environments |

### 3.2 Recovery Point Objective (RPO)

| Data Type | RPO | Mechanism |
|-----------|-----|-----------|
| **Database Transactions** | 15 minutes | Continuous WAL replication |
| **File Storage (S3)** | Near-zero | Cross-region replication |
| **Kubernetes State** | 1 hour | Velero hourly backups |
| **Configuration** | 1 hour | GitOps + Velero |

### 3.3 Maximum Tolerable Downtime (MTD)

| Business Function | MTD | Justification |
|-------------------|-----|---------------|
| **Milk Collection** | 4 hours | Manual processes can bridge |
| **Sales Processing** | 2 hours | Revenue impact |
| **IoT Monitoring** | 1 hour | Animal health critical |
| **B2B Ordering** | 4 hours | Contractual obligations |
| **B2C E-commerce** | 2 hours | Customer satisfaction |

---

## 4. DISASTER SCENARIOS

### 4.1 Scenario Matrix

| Scenario | Likelihood | Impact | Recovery Strategy |
|----------|------------|--------|-------------------|
| **A. Single AZ Failure** | High | Medium | Automatic failover |
| **B. Regional Outage** | Medium | Critical | DR activation |
| **C. Database Corruption** | Low | Critical | Point-in-time recovery |
| **D. Ransomware Attack** | Medium | Critical | DR activation + restore |
| **E. Human Error** | High | Medium | Backup restore |
| **F. Network Partition** | Medium | High | Split-brain prevention |

### 4.2 Scenario Details

#### Scenario A: Single Availability Zone Failure

```
Detection: AWS CloudWatch health checks
Response Time: Automatic (< 5 minutes)
Recovery Actions:
1. Kubernetes pods reschedule to healthy AZs
2. RDS Multi-AZ automatic failover
3. Load balancer removes unhealthy targets
4. No manual intervention required
```

#### Scenario B: Complete Region Outage

```
Detection: Route 53 health checks failing
Response Time: 30-60 minutes (manual activation)
Recovery Actions:
1. Activate DR site (scale up EKS nodes)
2. Promote RDS read replica to primary
3. Update Route 53 DNS to DR site
4. Verify application functionality
5. Notify stakeholders
```

#### Scenario C: Database Corruption

```
Detection: Data validation alerts, application errors
Response Time: 1-4 hours
Recovery Actions:
1. Stop application writes
2. Identify corruption point from logs
3. Restore from backup to PIT (point-in-time)
4. Verify data consistency
5. Resume operations
```

#### Scenario D: Ransomware/Malicious Attack

```
Detection: Encryption alerts, unusual access patterns
Response Time: 2-8 hours
Recovery Actions:
1. Isolate affected systems
2. Activate incident response team
3. Restore from offline/ immutable backups
4. Verify backup integrity before restore
5. Implement additional security measures
```

---

## 5. RECOVERY PROCEDURES

### 5.1 Database Recovery

```bash
#!/bin/bash
# db-recovery.sh - Database Recovery Script

DR_REGION="ap-southeast-1"
DB_INSTANCE="smart-dairy-dr-db"
RECOVERY_TIME="2026-10-18T10:00:00Z"  # PITR target

# 1. Promote read replica to standalone (for regional DR)
aws rds promote-read-replica \
    --db-instance-identifier $DB_INSTANCE \
    --region $DR_REGION

# 2. Or restore from snapshot with PITR (for corruption)
aws rds restore-db-instance-to-point-in-time \
    --source-db-instance-identifier smart-dairy-prod \
    --target-db-instance-identifier smart-dairy-recovered \
    --restore-time $RECOVERY_TIME \
    --region $DR_REGION

# 3. Wait for DB to be available
echo "Waiting for database to be available..."
aws rds wait db-instance-available \
    --db-instance-identifier $DB_INSTANCE \
    --region $DR_REGION

# 4. Update connection strings in Kubernetes
cat <<EOF | kubectl apply -f -
apiVersion: v1
kind: ConfigMap
metadata:
  name: db-config
data:
  DB_HOST: "$DB_INSTANCE.cluster-xxxx.ap-southeast-1.rds.amazonaws.com"
  DB_PORT: "5432"
EOF

# 5. Restart Odoo pods to pick up new connection
kubectl rollout restart deployment/odoo-web -n production

echo "Database recovery complete"
```

### 5.2 Kubernetes Recovery

```bash
#!/bin/bash
# k8s-recovery.sh - Kubernetes Recovery Script

DR_CLUSTER="smart-dairy-dr"
BACKUP_BUCKET="s3://smart-dairy-backups/velero"

# 1. Scale up DR cluster nodes
aws eks update-nodegroup-config \
    --cluster-name $DR_CLUSTER \
    --nodegroup-name general \
    --scaling-config minSize=3,maxSize=10,desiredSize=5

# 2. Wait for nodes to be ready
echo "Waiting for nodes..."
sleep 120
kubectl wait --for=condition=Ready nodes --all --timeout=300s

# 3. Restore from Velero backup
velero restore create dr-restore \
    --from-backup daily-backup \
    --include-namespaces production,staging \
    --exclude-resources events,controllerrevisions

# 4. Monitor restore progress
velero restore describe dr-restore
velero restore logs dr-restore

# 5. Verify critical workloads
kubectl get deployments -n production
kubectl get pods -n production

# 6. Run health checks
./health-check.sh

echo "Kubernetes recovery complete"
```

### 5.3 S3 Data Recovery

```bash
#!/bin/bash
# s3-recovery.sh - S3 Data Recovery

# Cross-region replication is automatic
# This script handles bucket-level recovery

BUCKETS=(
    "smart-dairy-media"
    "smart-dairy-exports"
    "smart-dairy-backups"
)

for BUCKET in "${BUCKETS[@]}"; do
    echo "Verifying DR bucket: $BUCKET-dr"
    
    # Check replication status
    aws s3api head-bucket --bucket $BUCKET-dr --region ap-southeast-1
    
    # List recent objects
    aws s3 ls s3://$BUCKET-dr --recursive --summarize | tail -20
done

# If primary bucket is compromised, redirect application to DR bucket
kubectl set env deployment/odoo-web \
    AWS_STORAGE_BUCKET_NAME=smart-dairy-media-dr

echo "S3 recovery verified"
```

### 5.4 Application Configuration Recovery

```bash
#!/bin/bash
# config-recovery.sh - Application Configuration Recovery

# 1. Verify GitOps repository is accessible
git clone https://github.com/smartdairy/k8s-manifests.git
cd k8s-manifests

# 2. Apply configurations to DR cluster
kubectl apply -k overlays/production/

# 3. Update DR-specific configurations
kubectl apply -f - <<EOF
apiVersion: v1
kind: ConfigMap
metadata:
  name: dr-config
  namespace: production
data:
  REGION: "ap-southeast-1"
  S3_ENDPOINT: "s3.ap-southeast-1.amazonaws.com"
  RDS_ENDPOINT: "smart-dairy-dr.cluster-xxx.ap-southeast-1.rds.amazonaws.com"
EOF

# 4. Apply secrets (from secure vault)
vault kv get -format=json secret/smartdairy/production | \
    jq -r '.data.data | to_entries[] | "\(.key)=\(.value)"' | \
    kubectl create secret generic app-secrets --from-env-file=/dev/stdin -n production

echo "Configuration recovery complete"
```

---

## 6. FAILOVER PROCEDURES

### 6.1 Automatic Failover (Single AZ)

```yaml
# No manual intervention required
# Handled by:
# - Kubernetes pod rescheduling
# - RDS Multi-AZ failover
# - Application Load Balancer health checks

# Verification steps:
verify_az_failover:
  - kubectl get nodes --label-columns failure-domain.beta.kubernetes.io/zone
  - aws rds describe-db-instances --query 'DBInstances[0].AvailabilityZone'
  - Check application health endpoints
```

### 6.2 Manual Failover (Regional DR)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    REGIONAL DR FAILOVER PROCEDURE                            │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  STEP 1: DECLARATION (0-15 min)                                              │
│  ├── Confirm primary region is down via multiple sources                     │
│  ├── Incident Commander declares DR activation                               │
│  └── Notify DR team via PagerDuty + Slack                                    │
│                                                                              │
│  STEP 2: DATABASE ACTIVATION (15-30 min)                                     │
│  ├── Promote RDS read replica to primary                                     │
│  └── Verify database connectivity                                            │
│                                                                              │
│  STEP 3: INFRASTRUCTURE ACTIVATION (30-45 min)                               │
│  ├── Scale up DR EKS cluster                                                 │
│  ├── Restore Kubernetes state from Velero                                    │
│  └── Deploy latest application version                                       │
│                                                                              │
│  STEP 4: APPLICATION VERIFICATION (45-60 min)                                │
│  ├── Run smoke tests                                                         │
│  ├── Verify critical business functions                                      │
│  └── Check integrations (payment gateway, SMS)                               │
│                                                                              │
│  STEP 5: DNS FAILOVER (50-60 min)                                            │
│  ├── Update Route 53 records to DR endpoints                                 │
│  └── Monitor traffic shifting                                                │
│                                                                              │
│  STEP 6: NOTIFICATION (60 min)                                               │
│  ├── Notify all stakeholders                                                 │
│  ├── Update status page                                                      │
│  └── Begin customer communication                                            │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 6.3 Failback Procedure

```bash
#!/bin/bash
# failback.sh - Return to Primary Region

# Prerequisites: Primary region is fully restored

# 1. Sync data from DR to primary
aws dms create-replication-task \
    --source-endpoint-arn $DR_ENDPOINT \
    --target-endpoint-arn $PRIMARY_ENDPOINT \
    --migration-type full-load-and-cdc

# 2. Put application in maintenance mode
kubectl set env deployment/odoo-web MAINTENANCE_MODE=true

# 3. Wait for data sync to complete
aws dms describe-replication-tasks --query 'ReplicationTasks[0].Status'

# 4. Switch DNS back to primary
aws route53 change-resource-record-sets \
    --hosted-zone-id $ZONE_ID \
    --change-batch file://failback-route53.json

# 5. Disable maintenance mode
kubectl set env deployment/odoo-web MAINTENANCE_MODE=false

# 6. Verify operations
./health-check.sh

# 7. Scale down DR environment
aws eks update-nodegroup-config \
    --cluster-name smart-dairy-dr \
    --nodegroup-name general \
    --scaling-config minSize=1,maxSize=2,desiredSize=1

echo "Failback complete"
```

---

## 7. DR TESTING

### 7.1 Testing Schedule

| Test Type | Frequency | Duration | Scope |
|-----------|-----------|----------|-------|
| **Tabletop Exercise** | Quarterly | 2 hours | Team procedures review |
| **Component Test** | Monthly | 4 hours | Single service recovery |
| **Full DR Drill** | Semi-annually | 8 hours | Complete site failover |
| **Unannounced Test** | Annually | 4 hours | Surprise activation |

### 7.2 Component Test Procedure

```bash
#!/bin/bash
# component-test.sh - Monthly Component Test

TEST_COMPONENT=$1  # database|kubernetes|s3

# 1. Pre-test checklist
python dr_test.py --phase=pre --component=$TEST_COMPONENT

# 2. Execute test
case $TEST_COMPONENT in
    database)
        # Test PITR restore to test instance
        aws rds restore-db-instance-to-point-in-time \
            --source-db-instance-identifier smart-dairy-prod \
            --target-db-instance-identifier test-restore \
            --restore-time $(date -u -d '1 hour ago' +%Y-%m-%dT%H:%M:%SZ)
        ;;
    kubernetes)
        # Test Velero restore to staging
        velero restore create test-restore \
            --from-backup daily-backup \
            --include-namespaces staging
        ;;
    s3)
        # Test object recovery
        aws s3 cp s3://smart-dairy-media/test-object.jpg /tmp/
        ;;
esac

# 3. Post-test validation
python dr_test.py --phase=post --component=$TEST_COMPONENT

# 4. Generate report
python dr_test.py --phase=report --component=$TEST_COMPONENT > test-report-$TEST_COMPONENT-$(date +%Y%m%d).md
```

### 7.3 Full DR Drill Checklist

```
□ Pre-drill: Schedule and notify stakeholders
□ Pre-drill: Ensure DR site is ready
□ Step 1: Initiate DR declaration
□ Step 2: Activate database in DR region
□ Step 3: Scale up DR infrastructure
□ Step 4: Restore application state
□ Step 5: Update DNS to DR
□ Step 6: Run full business function tests
□ Step 7: Verify IoT data ingestion
□ Step 8: Verify payment processing
□ Step 9: Document actual RTO/RPO
□ Step 10: Execute failback
□ Step 11: Verify primary site restored
□ Post-drill: Conduct lessons learned
□ Post-drill: Update DRP based on findings
```

### 7.4 Test Results Template

| Test Date | Type | RTO Achieved | RPO Achieved | Issues Found | Status |
|-----------|------|--------------|--------------|--------------|--------|
| 2026-10-20 | Component (DB) | N/A | 8 min | None | Pass |
| 2026-09-15 | Full DR | 52 min | 12 min | DNS TTL too high | Pass |

---

## 8. ROLES & RESPONSIBILITIES

### 8.1 DR Team Structure

| Role | Primary | Backup | Responsibilities |
|------|---------|--------|------------------|
| **Incident Commander** | IT Director | CTO | Overall DR activation and coordination |
| **DR Lead** | DevOps Lead | SRE Lead | Execute DR procedures |
| **Database Lead** | DBA | Senior DBA | Database recovery |
| **App Lead** | Tech Lead | Senior Dev | Application verification |
| **Communications Lead** | PMO | Marketing | Stakeholder communication |
| **Security Lead** | CISO | Security Eng | Security incident response |

### 8.2 Contact Information

| Role | Primary Contact | Backup Contact | Escalation |
|------|----------------|----------------|------------|
| Incident Commander | +91-xxx-xxx-0001 | +91-xxx-xxx-0002 | CEO |
| DR Lead | +91-xxx-xxx-0010 | +91-xxx-xxx-0011 | IT Director |
| Database Lead | +91-xxx-xxx-0020 | +91-xxx-xxx-0021 | Tech Lead |
| AWS Support | Enterprise | Enterprise | TAM |

---

## 9. COMMUNICATION PLAN

### 9.1 Internal Communication

| Timeline | Audience | Channel | Message |
|----------|----------|---------|---------|
| T+0 min | DR Team | PagerDuty | Incident declared |
| T+15 min | Management | Phone | DR activation notice |
| T+30 min | All Staff | Slack/Email | Service disruption notice |
| T+60 min | All Staff | Status Page | Recovery progress update |
| T+4 hours | Management | Phone | Recovery status |
| Post-recovery | All Staff | Email | All-clear notification |

### 9.2 External Communication

| Timeline | Audience | Channel | Message |
|----------|----------|---------|---------|
| T+30 min | Customers | Status Page | Service disruption notice |
| T+60 min | B2B Customers | Email | Detailed impact assessment |
| T+2 hours | Customers | Social Media | Updates on recovery |
| Post-recovery | Customers | Email | Service restoration notice |

### 9.3 Communication Templates

#### Service Disruption Notice

```
Subject: Smart Dairy Services - Temporary Disruption

Dear Customer,

We are currently experiencing a service disruption affecting [services].
Our team is working to restore full service as quickly as possible.

Impact: [description]
Estimated Resolution: [timeframe]
Alternative Access: [if applicable]

We apologize for any inconvenience and will provide updates every [frequency].

Status Page: https://status.smartdairy.com
Support: support@smartdairy.com | +91-xxx-xxx-xxxx

Smart Dairy Team
```

#### All-Clear Notice

```
Subject: Smart Dairy Services - Fully Restored

Dear Customer,

All Smart Dairy services have been fully restored and are operating normally.
We appreciate your patience during the service disruption.

Incident Summary:
- Duration: [start] to [end]
- Services Affected: [list]
- Root Cause: [brief description]
- Preventive Actions: [being taken]

If you experience any issues, please contact our support team.

Smart Dairy Team
```

---

## 10. APPENDICES

### Appendix A: DR Activation Checklist

```
INITIAL RESPONSE (0-15 minutes)
□ Incident detected and verified
□ Incident Commander notified
□ DR team assembled
□ Primary region status confirmed
□ DR activation decision made

DATABASE RECOVERY (15-30 minutes)
□ DR database promoted to primary
□ Database connectivity verified
□ Replication status confirmed
□ Backup integrity checked

INFRASTRUCTURE RECOVERY (30-45 minutes)
□ EKS cluster scaled up
□ Velero restore initiated
□ Pods scheduled and running
□ Services responding

APPLICATION RECOVERY (45-60 minutes)
□ Odoo ERP accessible
□ B2C portal functional
□ B2B portal functional
□ IoT ingestion working
□ Payment gateway connected

CUTOVER (50-60 minutes)
□ DNS updated to DR
□ SSL certificates valid
□ Monitoring dashboards active
□ All health checks passing
□ Stakeholders notified
```

### Appendix B: Emergency Contact List

| Organization | Contact | Purpose |
|--------------|---------|---------|
| AWS Support | Enterprise Support | Infrastructure issues |
| CloudFlare | Support Portal | DNS/CDN issues |
| Payment Gateway | Technical Support | Payment processing |
| SMS Provider | Technical Support | OTP delivery |
| ISV Partners | Account Manager | Third-party integration |

### Appendix C: Recovery Scripts Inventory

| Script | Location | Purpose | Last Tested |
|--------|----------|---------|-------------|
| db-recovery.sh | /opt/dr/scripts/ | Database restore | 2026-10-20 |
| k8s-recovery.sh | /opt/dr/scripts/ | Kubernetes restore | 2026-10-20 |
| s3-recovery.sh | /opt/dr/scripts/ | S3 verification | 2026-10-20 |
| health-check.sh | /opt/dr/scripts/ | Post-recovery validation | 2026-10-20 |
| dr_test.py | /opt/dr/scripts/ | DR testing automation | 2026-10-20 |

---

**END OF DISASTER RECOVERY PLAN**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | October 18, 2026 | DevOps Lead | Initial version |
